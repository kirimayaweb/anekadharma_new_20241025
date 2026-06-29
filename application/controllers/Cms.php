<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * CMS Publik — halaman informasi publik (tanpa login).
 * Route alias dapat diubah di application/config/routes.php
 */
class Cms extends CI_Controller
{
    private $settings = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('cms', 'url', 'form'));
        $this->load->model(array(
            'Cms_post_model',
            'Cms_category_model',
            'Cms_slider_model',
            'Cms_gallery_model',
            'Cms_social_model',
            'Cms_settings_model',
            'Cms_service_model',
            'Cms_media_embed_model',
        ));

        if (cms_is_installed()) {
            $this->settings = cms_get_settings_map();
        }
    }

    private function render($view, $data = array())
    {
        $data['settings'] = $this->settings;
        $data['site_title'] = isset($this->settings['site_title']) ? $this->settings['site_title'] : 'Informasi & Publikasi';
        $data['social_links'] = cms_is_installed() ? $this->Cms_social_model->get_all(true) : array();
        $data['categories'] = cms_is_installed() ? $this->Cms_category_model->get_all(true) : array();
        $data['page_title'] = isset($data['page_title']) ? $data['page_title'] : $data['site_title'];

        $this->load->view('cms_public/layout/header', $data);
        $this->load->view('cms_public/' . $view, $data);
        $this->load->view('cms_public/layout/footer', $data);
    }

    public function index()
    {
        if (!cms_is_installed()) {
            show_error('CMS belum diinstal. Hubungi administrator.', 503);
            return;
        }

        $data = array(
            'page_title' => isset($this->settings['site_title']) ? $this->settings['site_title'] : 'Informasi',
            'sliders' => $this->Cms_slider_model->get_all(true),
            'services' => $this->db->table_exists('cms_services') ? $this->Cms_service_model->get_all(true) : array(),
            'media_embeds' => $this->db->table_exists('cms_media_embeds') ? $this->Cms_media_embed_model->get_all(true) : array(),
            'featured_posts' => $this->Cms_post_model->get_published(8, 0, null, null, true),
            'latest_news' => $this->Cms_post_model->get_published(12, 0, 'berita'),
            'info_posts' => $this->Cms_post_model->get_published(6, 0, 'informasi'),
            'profile_posts' => $this->Cms_post_model->get_published(2, 0, 'profil'),
            'gallery' => $this->Cms_gallery_model->get_all(true, true),
        );

        $this->render('home', $data);
    }

    public function berita()
    {
        if (!cms_is_installed()) {
            show_404();
            return;
        }

        $page = max(1, (int) $this->input->get('page'));
        $per_page = 12;
        $offset = ($page - 1) * $per_page;

        $data = array(
            'page_title' => 'Berita',
            'posts' => $this->Cms_post_model->get_published($per_page, $offset, 'berita'),
            'total' => $this->Cms_post_model->count_published('berita'),
            'current_page' => $page,
            'per_page' => $per_page,
            'post_type' => 'berita',
        );

        $this->render('listing', $data);
    }

    public function informasi()
    {
        if (!cms_is_installed()) {
            show_404();
            return;
        }

        $page = max(1, (int) $this->input->get('page'));
        $per_page = 12;
        $offset = ($page - 1) * $per_page;

        $data = array(
            'page_title' => 'Informasi Umum',
            'posts' => $this->Cms_post_model->get_published($per_page, $offset, 'informasi'),
            'total' => $this->Cms_post_model->count_published('informasi'),
            'current_page' => $page,
            'per_page' => $per_page,
            'post_type' => 'informasi',
        );

        $this->render('listing', $data);
    }

    public function profil()
    {
        if (!cms_is_installed()) {
            show_404();
            return;
        }

        $data = array(
            'page_title' => 'Company Profile',
            'posts' => $this->Cms_post_model->get_published(null, 0, 'profil'),
            'company_about' => isset($this->settings['company_about']) ? $this->settings['company_about'] : '',
            'company_name' => isset($this->settings['company_name']) ? $this->settings['company_name'] : '',
        );

        $this->render('profil', $data);
    }

    public function galeri()
    {
        if (!cms_is_installed()) {
            show_404();
            return;
        }

        $data = array(
            'page_title' => 'Galeri Media',
            'gallery' => $this->Cms_gallery_model->get_all(true, true),
        );

        $this->render('galeri', $data);
    }

    public function media_sosial()
    {
        if (!cms_is_installed()) {
            show_404();
            return;
        }

        $data = array(
            'page_title' => 'Media Sosial & Video',
            'media_embeds' => $this->db->table_exists('cms_media_embeds') ? $this->Cms_media_embed_model->get_all(true) : array(),
            'gallery' => $this->Cms_gallery_model->get_all(true, true),
        );

        $this->render('media_sosial', $data);
    }

    public function kategori($slug = '')
    {
        if (!cms_is_installed() || $slug === '') {
            show_404();
            return;
        }

        $category = $this->Cms_category_model->get_by_slug($slug);
        if (!$category) {
            show_404();
            return;
        }

        $page = max(1, (int) $this->input->get('page'));
        $per_page = 12;
        $offset = ($page - 1) * $per_page;

        $data = array(
            'page_title' => $category->name,
            'category' => $category,
            'posts' => $this->Cms_post_model->get_published($per_page, $offset, null, $category->id),
            'total' => $this->Cms_post_model->count_published(null, $category->id),
            'current_page' => $page,
            'per_page' => $per_page,
        );

        $this->render('listing', $data);
    }

    public function detail($slug = '')
    {
        if (!cms_is_installed() || $slug === '') {
            show_404();
            return;
        }

        $post = $this->Cms_post_model->get_published_by_slug($slug);
        if (!$post) {
            show_404();
            return;
        }

        $this->Cms_post_model->increment_view($post->id);

        $category = null;
        if ($post->category_id) {
            $category = $this->Cms_category_model->get_by_id($post->category_id);
        }

        $related = $this->Cms_post_model->get_published(3, 0, $post->post_type);
        $filtered_related = array();
        foreach ($related as $r) {
            if ((int) $r->id !== (int) $post->id) {
                $filtered_related[] = $r;
            }
        }

        $data = array(
            'page_title' => $post->title,
            'post' => $post,
            'category' => $category,
            'related_posts' => array_slice($filtered_related, 0, 3),
        );

        $this->render('detail', $data);
    }

    public function halaman($slug = '')
    {
        $this->detail($slug);
    }

    public function cari()
    {
        if (!cms_is_installed()) {
            show_404();
            return;
        }

        $q = trim((string) $this->input->get('q'));
        $data = array(
            'page_title' => 'Pencarian',
            'keyword' => $q,
            'posts' => $this->Cms_post_model->search_published($q, 20),
        );

        $this->render('search', $data);
    }
}
