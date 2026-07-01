<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * CMS Admin — manajemen konten publikasi (wajib login + hak akses).
 * Route alias dapat diubah di application/config/routes.php
 */
class Cms_admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('cms', 'url', 'form'));
        $this->load->library('form_validation');
        $this->load->config('cms', true);
        $this->load->model(array(
            'Cms_post_model',
            'Cms_category_model',
            'Cms_slider_model',
            'Cms_gallery_model',
            'Cms_social_model',
            'Cms_settings_model',
            'Cms_admin_access_model',
            'Cms_service_model',
            'Cms_media_embed_model',
            'Tbl_user_model',
            'Tbl_user_level_model',
        ));

        cms_require_manage();
    }

    private function admin_layout($content_view, $data = array())
    {
        $data['cms_installed'] = cms_is_installed();
        $this->template->load(
            'anekadharma/adminlte310_anekadharma_topnav_aside',
            'anekadharma/cms_admin/' . $content_view,
            $data
        );
    }

    private function current_user_id()
    {
        $id = $this->session->userdata('sess_iduser');
        return $id !== null && $id !== '' ? (int) $id : (int) $this->session->userdata('id_users');
    }

    public function index()
    {
        if (!cms_is_installed()) {
            $this->admin_layout('adminlte310_cms_install', array(
                'page_title' => 'Instalasi CMS',
            ));
            return;
        }

        $data = array(
            'page_title' => 'Dashboard CMS Publikasi',
            'total_posts' => count($this->Cms_post_model->get_all()),
            'published_posts' => count($this->Cms_post_model->get_published()),
            'total_sliders' => count($this->Cms_slider_model->get_all()),
            'total_gallery' => count($this->Cms_gallery_model->get_all()),
            'recent_posts' => array_slice($this->Cms_post_model->get_all(), 0, 5),
            'public_url' => cms_public_url(),
        );

        $this->admin_layout('adminlte310_cms_dashboard', $data);
    }

    public function install_action()
    {
        cms_verify_csrf();

        if (cms_is_installed()) {
            $this->_run_media_upgrade();
            $this->session->set_flashdata('message', 'Upgrade media & konten bisnis selesai.');
            redirect('cms-admin');
            return;
        }

        $sql_file = FCPATH . 'database/sql/cms_publication.sql';
        if (!is_file($sql_file)) {
            $this->session->set_flashdata('message', 'File SQL tidak ditemukan.');
            redirect('cms-admin');
            return;
        }

        $sql = file_get_contents($sql_file);
        $statements = preg_split('/;\s*\n/', $sql);

        foreach ($statements as $statement) {
            $statement = trim($statement);
            if ($statement === '' || stripos($statement, '--') === 0) {
                continue;
            }
            @$this->db->query($statement);
        }

        cms_ensure_gallery_schema();

        $upload_base = $this->config->item('cms_upload_path', 'cms');
        if ($upload_base && !is_dir($upload_base)) {
            mkdir($upload_base, 0755, true);
            mkdir($upload_base . 'images', 0755, true);
            mkdir($upload_base . 'videos', 0755, true);
            mkdir($upload_base . 'sliders', 0755, true);
        }

        $this->_run_media_upgrade();

        $this->session->set_flashdata('message', 'CMS berhasil diinstal.');
        redirect('cms-admin');
    }

    public function demo_seed()
    {
        cms_verify_csrf();
        $this->_run_media_upgrade();
        $this->session->set_flashdata('message', 'Data demo presentasi berhasil dimuat (galeri, berita harga ATK, video).');
        redirect('cms-admin');
    }

    public function upgrade_media()
    {
        cms_verify_csrf();
        $this->_run_media_upgrade();
        $this->session->set_flashdata('message', 'Upgrade CMS media & layanan bisnis berhasil.');
        redirect('cms-admin');
    }

    private function _run_media_upgrade()
    {
        cms_ensure_gallery_schema();

        $files = array(
            FCPATH . 'database/sql/cms_media_upgrade.sql',
            FCPATH . 'database/sql/cms_demo_presentation.sql',
        );
        foreach ($files as $sql_file) {
            if (!is_file($sql_file)) {
                continue;
            }
            $sql = file_get_contents($sql_file);
            $statements = preg_split('/;\s*\n/', $sql);
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if ($statement === '' || stripos($statement, '--') === 0) {
                    continue;
                }
                @$this->db->query($statement);
            }
        }
    }

    /* ===================== POSTS ===================== */

    public function posts()
    {
        $this->_require_tables();
        $data = array(
            'page_title' => 'Kelola Konten',
            'posts' => $this->Cms_post_model->get_all(),
            'categories' => $this->Cms_category_model->get_all(),
        );
        $this->admin_layout('adminlte310_cms_posts_list', $data);
    }

    public function post_create()
    {
        $this->_require_tables();
        $data = $this->_post_form_data(null);
        $this->admin_layout('adminlte310_cms_post_form', $data);
    }

    public function post_edit($id = null)
    {
        $this->_require_tables();
        $row = $this->Cms_post_model->get_by_id($id);
        if (!$row) {
            show_404();
            return;
        }
        $data = $this->_post_form_data($row);
        $this->admin_layout('adminlte310_cms_post_form', $data);
    }

    public function post_save()
    {
        $this->_require_tables();
        cms_verify_csrf();
        $this->_post_rules();

        if ($this->form_validation->run() === false) {
            $id = $this->input->post('id');
            if ($id) {
                $this->post_edit($id);
            } else {
                $this->post_create();
            }
            return;
        }

        $id = (int) $this->input->post('id');
        $title = $this->input->post('title', true);
        $slug_input = $this->input->post('slug', true);
        $slug = cms_unique_slug($slug_input !== '' ? $slug_input : $title, 'cms_posts', $id ?: null);

        $payload = array(
            'category_id' => $this->input->post('category_id') ? (int) $this->input->post('category_id') : null,
            'post_type' => $this->input->post('post_type', true),
            'title' => $title,
            'slug' => $slug,
            'excerpt' => $this->input->post('excerpt', true),
            'content' => $this->input->post('content'),
            'video_url' => $this->input->post('video_url', true),
            'video_type' => $this->input->post('video_type', true),
            'meta_title' => $this->input->post('meta_title', true),
            'meta_description' => $this->input->post('meta_description', true),
            'tags' => $this->input->post('tags', true),
            'sort_order' => (int) $this->input->post('sort_order'),
            'is_featured' => $this->input->post('is_featured') ? 1 : 0,
            'is_published' => $this->input->post('is_published') ? 1 : 0,
            'published_at' => $this->input->post('published_at') ? $this->input->post('published_at') : date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->current_user_id(),
        );

        $upload = cms_upload_file('featured_image', 'images');
        if ($upload['success']) {
            $payload['featured_image'] = $upload['filename'];
        } elseif ($id) {
            $existing = $this->Cms_post_model->get_by_id($id);
            if ($existing) {
                $payload['featured_image'] = $existing->featured_image;
            }
        }

        if ($id) {
            $this->Cms_post_model->update($id, $payload);
            $this->session->set_flashdata('message', 'Konten berhasil diperbarui.');
        } else {
            $payload['created_at'] = date('Y-m-d H:i:s');
            $payload['created_by'] = $this->current_user_id();
            $this->Cms_post_model->insert($payload);
            $this->session->set_flashdata('message', 'Konten berhasil ditambahkan.');
        }

        redirect('cms-admin/posts');
    }

    public function post_delete($id)
    {
        $this->_require_tables();
        cms_verify_csrf();
        $row = $this->Cms_post_model->get_by_id($id);
        if ($row) {
            if ($row->featured_image) {
                cms_delete_upload($row->featured_image);
            }
            $this->Cms_post_model->delete($id);
            $this->session->set_flashdata('message', 'Konten dihapus.');
        }
        redirect('cms-admin/posts');
    }

    /* ===================== CATEGORIES ===================== */

    public function categories()
    {
        $this->_require_tables();
        $data = array(
            'page_title' => 'Kategori',
            'categories' => $this->Cms_category_model->get_all(),
        );
        $this->admin_layout('adminlte310_cms_categories', $data);
    }

    public function category_save()
    {
        $this->_require_tables();
        cms_verify_csrf();

        $id = (int) $this->input->post('id');
        $name = $this->input->post('name', true);
        $slug = cms_unique_slug($this->input->post('slug', true) ?: $name, 'cms_categories', $id ?: null);

        $payload = array(
            'name' => $name,
            'slug' => $slug,
            'description' => $this->input->post('description', true),
            'icon' => $this->input->post('icon', true),
            'sort_order' => (int) $this->input->post('sort_order'),
            'is_active' => $this->input->post('is_active') ? 1 : 0,
            'updated_at' => date('Y-m-d H:i:s'),
        );

        if ($id) {
            $this->Cms_category_model->update($id, $payload);
        } else {
            $payload['created_at'] = date('Y-m-d H:i:s');
            $this->Cms_category_model->insert($payload);
        }

        redirect('cms-admin/categories');
    }

    public function category_delete($id)
    {
        $this->_require_tables();
        cms_verify_csrf();
        $this->Cms_category_model->delete($id);
        redirect('cms-admin/categories');
    }

    /* ===================== SLIDERS ===================== */

    public function sliders()
    {
        $this->_require_tables();
        $data = array(
            'page_title' => 'Slider Hero',
            'sliders' => $this->Cms_slider_model->get_all(),
        );
        $this->admin_layout('adminlte310_cms_sliders', $data);
    }

    public function slider_save()
    {
        $this->_require_tables();
        cms_verify_csrf();

        $id = (int) $this->input->post('id');
        $payload = array(
            'title' => $this->input->post('title', true),
            'subtitle' => $this->input->post('subtitle', true),
            'link_url' => $this->input->post('link_url', true),
            'button_text' => $this->input->post('button_text', true),
            'sort_order' => (int) $this->input->post('sort_order'),
            'is_active' => $this->input->post('is_active') ? 1 : 0,
            'updated_at' => date('Y-m-d H:i:s'),
        );

        $upload = cms_upload_file('image', 'sliders');
        if ($upload['success']) {
            $payload['image'] = $upload['filename'];
        } elseif ($id) {
            $existing = $this->Cms_slider_model->get_by_id($id);
            if ($existing) {
                $payload['image'] = $existing->image;
            }
        }

        if ($id) {
            $this->Cms_slider_model->update($id, $payload);
        } else {
            $payload['created_at'] = date('Y-m-d H:i:s');
            $this->Cms_slider_model->insert($payload);
        }

        redirect('cms-admin/sliders');
    }

    public function slider_delete($id)
    {
        $this->_require_tables();
        cms_verify_csrf();
        $row = $this->Cms_slider_model->get_by_id($id);
        if ($row && $row->image) {
            cms_delete_upload($row->image);
        }
        $this->Cms_slider_model->delete($id);
        redirect('cms-admin/sliders');
    }

    /* ===================== GALLERY ===================== */

    public function gallery()
    {
        $this->_require_tables();
        $data = array(
            'page_title' => 'Galeri Media',
            'gallery' => $this->Cms_gallery_model->get_all(),
        );
        $this->admin_layout('adminlte310_cms_gallery', $data);
    }

    public function gallery_save()
    {
        $this->_require_tables();
        cms_verify_csrf();

        $id = (int) $this->input->post('id');
        $media_type = $this->input->post('media_type', true);

        $payload = array(
            'title' => $this->input->post('title', true),
            'description' => $this->input->post('description', true),
            'media_type' => $media_type,
            'external_url' => $this->input->post('external_url', true),
            'thumbnail_url' => $this->input->post('thumbnail_url', true),
            'category' => $this->input->post('category', true) ?: 'umum',
            'share_title' => $this->input->post('share_title', true),
            'sort_order' => (int) $this->input->post('sort_order'),
            'is_active' => $this->input->post('is_active') ? 1 : 0,
            'is_published' => $this->input->post('is_published') ? 1 : 0,
            'published_at' => $this->input->post('published_at') ? $this->input->post('published_at') : date('Y-m-d H:i:s'),
        );

        $subdir = $media_type === 'video' ? 'videos' : 'images';
        $upload = cms_upload_file('file_path', $subdir);
        if ($upload['success']) {
            $payload['file_path'] = $upload['filename'];
        } elseif ($id) {
            $existing = $this->Cms_gallery_model->get_by_id($id);
            if ($existing) {
                $payload['file_path'] = $existing->file_path;
            }
        }

        if ($id) {
            $this->Cms_gallery_model->update($id, $payload);
        } else {
            $payload['created_at'] = date('Y-m-d H:i:s');
            $this->Cms_gallery_model->insert($payload);
        }

        redirect('cms-admin/gallery');
    }

    public function gallery_delete($id)
    {
        $this->_require_tables();
        cms_verify_csrf();
        $row = $this->Cms_gallery_model->get_by_id($id);
        if ($row && $row->file_path) {
            cms_delete_upload($row->file_path);
        }
        $this->Cms_gallery_model->delete($id);
        redirect('cms-admin/gallery');
    }

    public function gallery_toggle_active($id)
    {
        $this->_require_tables();
        cms_verify_csrf();
        $this->Cms_gallery_model->toggle_active($id);
        redirect('cms-admin/gallery');
    }

    public function gallery_toggle_publish($id)
    {
        $this->_require_tables();
        cms_verify_csrf();
        $this->Cms_gallery_model->toggle_published($id);
        redirect('cms-admin/gallery');
    }

    /* ===================== LAYANAN BISNIS ===================== */

    public function services()
    {
        $this->_require_tables();
        if (!$this->db->table_exists('cms_services')) {
            $this->session->set_flashdata('message', 'Jalankan upgrade SQL: database/sql/cms_media_upgrade.sql');
            redirect('cms-admin');
            return;
        }
        $data = array(
            'page_title' => 'Layanan & Ilustrasi Bisnis',
            'services' => $this->Cms_service_model->get_all(),
            'categories' => $this->config->item('cms_business_categories', 'cms'),
        );
        $this->admin_layout('adminlte310_cms_services', $data);
    }

    public function service_save()
    {
        $this->_require_tables();
        cms_verify_csrf();

        $id = (int) $this->input->post('id');
        $title = $this->input->post('title', true);
        $slug = cms_unique_slug($this->input->post('slug', true) ?: $title, 'cms_services', $id ?: null);

        $payload = array(
            'title' => $title,
            'slug' => $slug,
            'description' => $this->input->post('description', true),
            'icon' => $this->input->post('icon', true),
            'image_url' => $this->input->post('image_url', true),
            'link_url' => $this->input->post('link_url', true),
            'sort_order' => (int) $this->input->post('sort_order'),
            'is_active' => $this->input->post('is_active') ? 1 : 0,
            'is_published' => $this->input->post('is_published') ? 1 : 0,
            'published_at' => $this->input->post('published_at') ? $this->input->post('published_at') : date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        );

        $upload = cms_upload_file('image_file', 'images');
        if ($upload['success']) {
            $payload['image_url'] = cms_upload_url($upload['filename']);
        }

        if ($id) {
            $this->Cms_service_model->update($id, $payload);
        } else {
            $payload['created_at'] = date('Y-m-d H:i:s');
            $this->Cms_service_model->insert($payload);
        }

        redirect('cms-admin/services');
    }

    public function service_delete($id)
    {
        $this->_require_tables();
        cms_verify_csrf();
        $this->Cms_service_model->delete($id);
        redirect('cms-admin/services');
    }

    public function service_toggle_active($id)
    {
        $this->_require_tables();
        cms_verify_csrf();
        $this->Cms_service_model->toggle_active($id);
        redirect('cms-admin/services');
    }

    public function service_toggle_publish($id)
    {
        $this->_require_tables();
        cms_verify_csrf();
        $this->Cms_service_model->toggle_published($id);
        redirect('cms-admin/services');
    }

    /* ===================== MEDIA EMBED SOSIAL ===================== */

    public function media_embeds()
    {
        $this->_require_tables();
        if (!$this->db->table_exists('cms_media_embeds')) {
            $this->session->set_flashdata('message', 'Jalankan upgrade SQL: database/sql/cms_media_upgrade.sql');
            redirect('cms-admin');
            return;
        }
        $data = array(
            'page_title' => 'Video & Embed Media Sosial',
            'media_embeds' => $this->Cms_media_embed_model->get_all(),
            'platforms' => $this->config->item('cms_embed_platforms', 'cms'),
            'categories' => $this->config->item('cms_business_categories', 'cms'),
        );
        $this->admin_layout('adminlte310_cms_media_embeds', $data);
    }

    public function media_embed_save()
    {
        $this->_require_tables();
        cms_verify_csrf();

        $id = (int) $this->input->post('id');
        $payload = array(
            'title' => $this->input->post('title', true),
            'description' => $this->input->post('description', true),
            'platform' => $this->input->post('platform', true),
            'source_url' => $this->input->post('source_url', true),
            'embed_code' => $this->input->post('embed_code'),
            'thumbnail_url' => $this->input->post('thumbnail_url', true),
            'category' => $this->input->post('category', true) ?: 'umum',
            'sort_order' => (int) $this->input->post('sort_order'),
            'is_active' => $this->input->post('is_active') ? 1 : 0,
            'is_published' => $this->input->post('is_published') ? 1 : 0,
            'published_at' => $this->input->post('published_at') ? $this->input->post('published_at') : date('Y-m-d H:i:s'),
            'expire_at' => $this->input->post('expire_at') ? $this->input->post('expire_at') : null,
            'share_enabled' => $this->input->post('share_enabled') ? 1 : 0,
            'updated_at' => date('Y-m-d H:i:s'),
        );

        if ($id) {
            $this->Cms_media_embed_model->update($id, $payload);
        } else {
            $payload['created_at'] = date('Y-m-d H:i:s');
            $this->Cms_media_embed_model->insert($payload);
        }

        redirect('cms-admin/media_embeds');
    }

    public function media_embed_delete($id)
    {
        $this->_require_tables();
        cms_verify_csrf();
        $this->Cms_media_embed_model->delete($id);
        redirect('cms-admin/media_embeds');
    }

    public function media_embed_toggle_active($id)
    {
        $this->_require_tables();
        cms_verify_csrf();
        $this->Cms_media_embed_model->toggle_active($id);
        redirect('cms-admin/media_embeds');
    }

    public function media_embed_toggle_publish($id)
    {
        $this->_require_tables();
        cms_verify_csrf();
        $this->Cms_media_embed_model->toggle_published($id);
        redirect('cms-admin/media_embeds');
    }

    /* ===================== SOCIAL ===================== */

    public function social()
    {
        $this->_require_tables();
        $data = array(
            'page_title' => 'Media Sosial',
            'social_links' => $this->Cms_social_model->get_all(),
            'platforms' => $this->config->item('cms_social_platforms', 'cms'),
        );
        $this->admin_layout('adminlte310_cms_social', $data);
    }

    public function social_save()
    {
        $this->_require_tables();
        cms_verify_csrf();

        $id = (int) $this->input->post('id');
        $platform = $this->input->post('platform', true);
        $platforms = $this->config->item('cms_social_platforms', 'cms');
        $icon = isset($platforms[$platform]['icon']) ? $platforms[$platform]['icon'] : 'bi bi-link';

        $payload = array(
            'platform' => $platform,
            'url' => $this->input->post('url', true),
            'label' => $this->input->post('label', true),
            'icon_class' => $this->input->post('icon_class', true) ?: $icon,
            'sort_order' => (int) $this->input->post('sort_order'),
            'is_active' => $this->input->post('is_active') ? 1 : 0,
        );

        if ($id) {
            $this->Cms_social_model->update($id, $payload);
        } else {
            $this->Cms_social_model->insert($payload);
        }

        redirect('cms-admin/social');
    }

    public function social_delete($id)
    {
        $this->_require_tables();
        cms_verify_csrf();
        $this->Cms_social_model->delete($id);
        redirect('cms-admin/social');
    }

    /* ===================== SETTINGS ===================== */

    public function settings()
    {
        $this->_require_tables();
        $data = array(
            'page_title' => 'Pengaturan Situs',
            'settings' => $this->Cms_settings_model->get_all_map(),
        );
        $this->admin_layout('adminlte310_cms_settings', $data);
    }

    public function settings_save()
    {
        $this->_require_tables();
        cms_verify_csrf();

        $keys = array(
            'site_title', 'site_tagline', 'company_name', 'company_about',
            'contact_email', 'contact_phone', 'contact_address',
            'primary_color', 'secondary_color', 'allowed_admin_levels',
        );

        $payload = array();
        foreach ($keys as $key) {
            $payload[$key] = $this->input->post($key, true);
        }

        $this->Cms_settings_model->set_many($payload, $this->current_user_id());
        $this->session->set_flashdata('message', 'Pengaturan disimpan.');
        redirect('cms-admin/settings');
    }

    /* ===================== ACCESS ===================== */

    public function access()
    {
        $this->_require_tables();
        $data = array(
            'page_title' => 'Hak Akses CMS Admin',
            'access_rows' => $this->Cms_admin_access_model->get_all(),
            'users' => $this->Tbl_user_model->get_all(),
            'user_levels' => $this->Tbl_user_level_model->get_all(),
            'settings' => $this->Cms_settings_model->get_all_map(),
        );
        $this->admin_layout('adminlte310_cms_access', $data);
    }

    public function access_grant_user()
    {
        $this->_require_tables();
        cms_verify_csrf();
        $id_user = (int) $this->input->post('id_user');
        if ($id_user) {
            $this->Cms_admin_access_model->grant_user($id_user, $this->current_user_id());
        }
        redirect('cms-admin/access');
    }

    public function access_grant_level()
    {
        $this->_require_tables();
        cms_verify_csrf();
        $id_level = (int) $this->input->post('id_user_level');
        if ($id_level) {
            $this->Cms_admin_access_model->grant_level($id_level, $this->current_user_id());
        }
        redirect('cms-admin/access');
    }

    public function access_revoke_user($id_user)
    {
        $this->_require_tables();
        cms_verify_csrf();
        $this->Cms_admin_access_model->revoke_user($id_user);
        redirect('cms-admin/access');
    }

    public function access_revoke_level($id_level)
    {
        $this->_require_tables();
        cms_verify_csrf();
        $this->Cms_admin_access_model->revoke_level($id_level);
        redirect('cms-admin/access');
    }

    /* ===================== PRIVATE ===================== */

    private function _require_tables()
    {
        if (!cms_is_installed()) {
            redirect('cms-admin');
            exit;
        }
    }

    private function _post_form_data($row)
    {
        $is_edit = (bool) $row;
        return array(
            'page_title' => $is_edit ? 'Edit Konten' : 'Tambah Konten',
            'row' => $row,
            'categories' => $this->Cms_category_model->get_all(true),
            'post_types' => $this->config->item('cms_post_types', 'cms'),
            'video_types' => $this->config->item('cms_video_types', 'cms'),
            'action' => site_url('cms-admin/post_save'),
        );
    }

    private function _post_rules()
    {
        $this->form_validation->set_rules('title', 'Judul', 'trim|required|max_length[300]');
        $this->form_validation->set_rules('post_type', 'Tipe', 'trim|required');
        $this->form_validation->set_rules('content', 'Konten', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
}
