<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Helper CMS Publikasi — keamanan, media, akses admin.
 */

function cms_upload_url($filename = '')
{
    $ci = get_instance();
    $ci->config->load('cms', true);
    $base = $ci->config->item('cms_upload_url_path', 'cms');
    if ($base === null || $base === '') {
        $base = 'assets/cms_uploads/';
    }
    $url = base_url($base);
    if ($filename === '' || $filename === null) {
        return $url;
    }
    return $url . ltrim(str_replace('\\', '/', (string) $filename), '/');
}

function cms_is_installed()
{
    $ci = get_instance();
    return $ci->db->table_exists('cms_posts');
}

function cms_get_setting($key, $default = '')
{
    $ci = get_instance();
    if (!cms_is_installed()) {
        return $default;
    }
    $ci->load->model('Cms_settings_model');
    return $ci->Cms_settings_model->get_value($key, $default);
}

function cms_get_settings_map()
{
    $ci = get_instance();
    if (!cms_is_installed()) {
        return array();
    }
    $ci->load->model('Cms_settings_model');
    return $ci->Cms_settings_model->get_all_map();
}

function cms_can_manage()
{
    $ci = get_instance();

    if (!is_logged_in()) {
        return false;
    }

    $id_user = $ci->session->userdata('sess_iduser');
    if ($id_user === null || $id_user === '') {
        $id_user = $ci->session->userdata('id_users');
    }

    $id_user_level = $ci->session->userdata('sess_id_user_level');
    if ($id_user_level === null || $id_user_level === '') {
        $id_user_level = $ci->session->userdata('id_user_level');
    }

    if (!cms_is_installed()) {
        $ci->config->load('cms', true);
        $levels = $ci->config->item('cms_default_admin_levels', 'cms');
        return is_array($levels) && in_array((int) $id_user_level, array_map('intval', $levels), true);
    }

    $ci->load->model('Cms_admin_access_model');

    if ($ci->Cms_admin_access_model->user_has_access($id_user, $id_user_level)) {
        return true;
    }

    $modul = 'cms_admin';
    $menu = $ci->db->query(
        'SELECT id_menu FROM tbl_menu WHERE LOWER(url) = ? LIMIT 1',
        array($modul)
    )->row_array();

    if ($menu) {
        $hak = $ci->db->get_where('tbl_hak_akses', array(
            'id_menu' => $menu['id_menu'],
            'id_user_level' => $id_user_level,
        ));
        if ($hak->num_rows() > 0) {
            return true;
        }
    }

    return false;
}

function cms_require_manage()
{
    is_login();

    if (!cms_can_manage()) {
        $ci = get_instance();
        $ci->session->set_flashdata('message', 'Anda tidak memiliki akses ke CMS Publikasi.');
        redirect('blokir');
        exit;
    }
}

function cms_slugify($text)
{
    $text = strtolower(trim((string) $text));
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}

function cms_unique_slug($slug, $table, $exclude_id = null)
{
    $ci = get_instance();
    $base = cms_slugify($slug);
    if ($base === '') {
        $base = 'item-' . time();
    }

    $candidate = $base;
    $i = 1;
    while (true) {
        $ci->db->where('slug', $candidate);
        if ($exclude_id !== null) {
            $ci->db->where('id !=', (int) $exclude_id);
        }
        $exists = $ci->db->count_all_results($table) > 0;
        if (!$exists) {
            return $candidate;
        }
        $candidate = $base . '-' . $i;
        $i++;
    }
}

function cms_extract_youtube_id($url)
{
    $url = trim((string) $url);
    if ($url === '') {
        return '';
    }
    if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([A-Za-z0-9_-]{11})/', $url, $m)) {
        return $m[1];
    }
    return '';
}

function cms_youtube_embed($url, $attrs = '')
{
    $id = cms_extract_youtube_id($url);
    if ($id === '') {
        return '';
    }
    return '<div class="ratio ratio-16x9 cms-video-embed"><iframe src="https://www.youtube.com/embed/'
        . htmlspecialchars($id, ENT_QUOTES, 'UTF-8')
        . '" title="YouTube video" allowfullscreen loading="lazy" ' . $attrs . '></iframe></div>';
}

function cms_sanitize_html($html)
{
    $html = (string) $html;
    if ($html === '') {
        return '';
    }
    $allowed = '<p><br><strong><b><em><i><u><ul><ol><li><a><img><h1><h2><h3><h4><h5><h6><blockquote><table><thead><tbody><tr><th><td><span><div><hr><figure><figcaption><iframe><em>';
    return strip_tags($html, $allowed);
}

function cms_excerpt($text, $limit = 160)
{
    $text = strip_tags((string) $text);
    $text = preg_replace('/\s+/', ' ', $text);
    $text = trim($text);
    if (mb_strlen($text) <= $limit) {
        return $text;
    }
    return mb_substr($text, 0, $limit) . '…';
}

function cms_format_date($datetime, $format = 'd M Y')
{
    if ($datetime === null || $datetime === '' || $datetime === '0000-00-00 00:00:00') {
        return '-';
    }
    return date($format, strtotime($datetime));
}

function cms_csrf_field()
{
    $ci = get_instance();
    $token = $ci->session->userdata('cms_csrf_token');
    if (!$token) {
        $token = bin2hex(random_bytes(32));
        $ci->session->set_userdata('cms_csrf_token', $token);
    }
    return '<input type="hidden" name="cms_csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
}

function cms_verify_csrf()
{
    $ci = get_instance();
    $session_token = (string) $ci->session->userdata('cms_csrf_token');
    $post_token = (string) $ci->input->post('cms_csrf_token', true);

    if ($session_token === '' || $post_token === '' || !hash_equals($session_token, $post_token)) {
        show_error('Permintaan tidak valid (CSRF). Silakan muat ulang halaman.', 403);
        exit;
    }
}

function cms_upload_file($field_name, $subdir = 'images')
{
    $ci = get_instance();
    $ci->config->load('cms', true);

    if (empty($_FILES[$field_name]['name'])) {
        return array('success' => false, 'error' => 'Tidak ada file.');
    }

    $upload_path = rtrim($ci->config->item('cms_upload_path', 'cms'), '/\\') . DIRECTORY_SEPARATOR . $subdir . DIRECTORY_SEPARATOR;
    if (!is_dir($upload_path)) {
        mkdir($upload_path, 0755, true);
    }

    $ext = strtolower(pathinfo($_FILES[$field_name]['name'], PATHINFO_EXTENSION));
    $allowed_images = explode('|', $ci->config->item('cms_allowed_image_types', 'cms'));
    $allowed_videos = explode('|', $ci->config->item('cms_allowed_video_types', 'cms'));
    $allowed = array_merge($allowed_images, $allowed_videos);

    if (!in_array($ext, $allowed, true)) {
        return array('success' => false, 'error' => 'Tipe file tidak diizinkan.');
    }

    $is_video = in_array($ext, $allowed_videos, true);
    $max_kb = $is_video
        ? (int) $ci->config->item('cms_max_video_size_kb', 'cms')
        : (int) $ci->config->item('cms_max_image_size_kb', 'cms');

    if ($_FILES[$field_name]['size'] > ($max_kb * 1024)) {
        return array('success' => false, 'error' => 'Ukuran file melebihi batas.');
    }

    $filename = $subdir . '_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $dest = $upload_path . $filename;

    if (!move_uploaded_file($_FILES[$field_name]['tmp_name'], $dest)) {
        return array('success' => false, 'error' => 'Gagal mengunggah file.');
    }

    return array(
        'success' => true,
        'filename' => $subdir . '/' . $filename,
        'full_path' => $dest,
    );
}

function cms_delete_upload($relative_path)
{
    if ($relative_path === null || $relative_path === '') {
        return;
    }
    $ci = get_instance();
    $ci->config->load('cms', true);
    $base = rtrim($ci->config->item('cms_upload_path', 'cms'), '/\\');
    $file = $base . DIRECTORY_SEPARATOR . str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $relative_path);
    if (is_file($file)) {
        @unlink($file);
    }
}

function cms_post_type_label($type)
{
    $ci = get_instance();
    $ci->config->load('cms', true);
    $types = $ci->config->item('cms_post_types', 'cms');
    return isset($types[$type]) ? $types[$type] : ucfirst($type);
}

function cms_featured_image_url($path, $fallback = '')
{
    if ($path !== null && $path !== '') {
        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }
        return cms_upload_url($path);
    }
    return $fallback !== '' ? $fallback : base_url('assets/AdminLTE310/dist/img/AdminLTELogo.png');
}

function cms_render_video_block($post)
{
    if (!$post || empty($post->video_url) || $post->video_type === 'none') {
        return '';
    }

    return cms_render_media_embed((object) array(
        'platform' => $post->video_type,
        'source_url' => $post->video_url,
        'embed_code' => null,
        'title' => isset($post->title) ? $post->title : 'Video',
    ));
}

function cms_media_is_visible($row)
{
    if (!$row) {
        return false;
    }
    if (isset($row->is_active) && !(int) $row->is_active) {
        return false;
    }
    if (isset($row->is_published) && !(int) $row->is_published) {
        return false;
    }
    if (isset($row->published_at) && $row->published_at && $row->published_at !== '0000-00-00 00:00:00') {
        if (strtotime($row->published_at) > time()) {
            return false;
        }
    }
    if (isset($row->expire_at) && $row->expire_at && $row->expire_at !== '0000-00-00 00:00:00') {
        if (strtotime($row->expire_at) < time()) {
            return false;
        }
    }
    return true;
}

function cms_gallery_image_url($item)
{
    if (!$item) {
        return cms_featured_image_url('');
    }
    if (!empty($item->thumbnail_url)) {
        return cms_featured_image_url($item->thumbnail_url);
    }
    if (!empty($item->file_path)) {
        return cms_featured_image_url($item->file_path);
    }
    if (!empty($item->external_url) && $item->media_type === 'image') {
        return cms_featured_image_url($item->external_url);
    }
    if (!empty($item->external_url)) {
        $yt = cms_extract_youtube_id($item->external_url);
        if ($yt) {
            return 'https://img.youtube.com/vi/' . $yt . '/hqdefault.jpg';
        }
    }
    return cms_featured_image_url('');
}

function cms_share_url($title, $url = null)
{
    $url = $url ? $url : current_url();
    return array(
        'facebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($url),
        'twitter'  => 'https://twitter.com/intent/tweet?url=' . urlencode($url) . '&text=' . urlencode($title),
        'whatsapp' => 'https://wa.me/?text=' . urlencode($title . ' ' . $url),
        'telegram' => 'https://t.me/share/url?url=' . urlencode($url) . '&text=' . urlencode($title),
    );
}

function cms_render_media_embed($item)
{
    if (!$item || empty($item->source_url) && empty($item->embed_code)) {
        return '';
    }

    if (!empty($item->embed_code)) {
        return '<div class="cms-embed-custom">' . $item->embed_code . '</div>';
    }

    $url = trim((string) $item->source_url);
    $safe = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
    $platform = isset($item->platform) ? $item->platform : 'embed';

    switch ($platform) {
        case 'youtube':
            return cms_youtube_embed($url);
        case 'tiktok':
            return '<blockquote class="tiktok-embed cms-social-embed" cite="' . $safe . '" data-video-id="" style="max-width:605px;min-width:325px;"><section><a href="' . $safe . '" target="_blank" rel="noopener">Tonton di TikTok</a></section></blockquote>';
        case 'instagram':
            return '<blockquote class="instagram-media cms-social-embed" data-instgrm-permalink="' . $safe . '" data-instgrm-version="14" style="max-width:540px;width:100%;"><a href="' . $safe . '" target="_blank" rel="noopener">Lihat di Instagram</a></blockquote>';
        case 'facebook':
            $encoded = rawurlencode($url);
            return '<div class="cms-fb-embed ratio ratio-16x9"><iframe src="https://www.facebook.com/plugins/video.php?href=' . $encoded . '&show_text=false&width=560" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" loading="lazy"></iframe></div>';
        case 'twitter':
            return '<blockquote class="twitter-tweet cms-social-embed"><a href="' . $safe . '">Lihat di X</a></blockquote>';
        default:
            if (preg_match('#^https?://#i', $url)) {
                return '<div class="ratio ratio-16x9 cms-video-embed"><iframe src="' . $safe . '" loading="lazy" allowfullscreen></iframe></div>';
            }
            return '';
    }
}

function cms_render_media_card($item, $show_share = true)
{
    if (!cms_media_is_visible($item)) {
        return '';
    }

    $title = htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8');
    $desc = !empty($item->description) ? htmlspecialchars($item->description, ENT_QUOTES, 'UTF-8') : '';
    $thumb = !empty($item->thumbnail_url) ? cms_featured_image_url($item->thumbnail_url) : '';
    $platform = isset($item->platform) ? $item->platform : '';

    $ci = get_instance();
    $ci->config->load('cms', true);
    $platforms = $ci->config->item('cms_embed_platforms', 'cms');
    $plabel = isset($platforms[$platform]['label']) ? $platforms[$platform]['label'] : ucfirst($platform);
    $picon = isset($platforms[$platform]['icon']) ? $platforms[$platform]['icon'] : 'bi bi-play-btn';

    $share = '';
    if ($show_share && (!isset($item->share_enabled) || (int) $item->share_enabled)) {
        $links = cms_share_url($item->title, $item->source_url);
        $share = '<div class="cms-share-mini mt-2">'
            . '<a href="' . $links['whatsapp'] . '" target="_blank" rel="noopener" class="btn btn-sm btn-success rounded-circle" title="Share WhatsApp"><i class="bi bi-whatsapp"></i></a> '
            . '<a href="' . $links['facebook'] . '" target="_blank" rel="noopener" class="btn btn-sm btn-primary rounded-circle" title="Share Facebook"><i class="bi bi-facebook"></i></a> '
            . '<a href="' . $links['twitter'] . '" target="_blank" rel="noopener" class="btn btn-sm btn-dark rounded-circle" title="Share X"><i class="bi bi-twitter-x"></i></a>'
            . '</div>';
    }

    ob_start();
    ?>
    <div class="cms-media-card" data-aos="fade-up">
        <div class="cms-media-card-head">
            <span class="cms-media-platform"><i class="<?php echo htmlspecialchars($picon, ENT_QUOTES, 'UTF-8'); ?>"></i> <?php echo htmlspecialchars($plabel, ENT_QUOTES, 'UTF-8'); ?></span>
        </div>
        <?php if ($thumb && in_array($platform, array('youtube'), true)): ?>
            <div class="cms-media-thumb" style="background-image:url('<?php echo htmlspecialchars($thumb, ENT_QUOTES, 'UTF-8'); ?>')"></div>
        <?php endif; ?>
        <div class="cms-media-embed-wrap">
            <?php echo cms_render_media_embed($item); ?>
        </div>
        <div class="cms-media-card-body">
            <h3 class="h6 fw-bold mb-1"><?php echo $title; ?></h3>
            <?php if ($desc): ?><p class="small text-muted mb-0"><?php echo $desc; ?></p><?php endif; ?>
            <?php echo $share; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
