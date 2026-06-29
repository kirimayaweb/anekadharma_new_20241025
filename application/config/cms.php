<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| CMS Publikasi — Konfigurasi
|--------------------------------------------------------------------------
| Ubah route alias di application/config/routes.php (blok CMS Publikasi).
| Controller publik: Cms | Controller admin: Cms_admin
*/

$config['cms_upload_path'] = FCPATH . 'assets/cms_uploads/';
$config['cms_upload_url_path'] = 'assets/cms_uploads/';

$config['cms_allowed_image_types'] = 'gif|jpg|jpeg|png|webp';
$config['cms_allowed_video_types'] = 'mp4|webm';
$config['cms_max_image_size_kb']   = 5120;
$config['cms_max_video_size_kb']   = 51200;

/* Level user default yang boleh akses CMS Admin (selain tbl_hak_akses & cms_admin_access) */
$config['cms_default_admin_levels'] = array(1, 2, 99);

/* Post types */
$config['cms_post_types'] = array(
    'berita'     => 'Berita',
    'informasi'  => 'Informasi Umum',
    'profil'     => 'Company Profile',
    'halaman'    => 'Halaman Khusus',
);

$config['cms_video_types'] = array(
    'none'      => 'Tanpa Video',
    'youtube'   => 'YouTube',
    'tiktok'    => 'TikTok',
    'instagram' => 'Instagram',
    'facebook'  => 'Facebook',
    'embed'     => 'Embed URL',
);

$config['cms_business_categories'] = array(
    'atk'         => 'Alat Tulis Kantor (ATK)',
    'fotokopi'    => 'Fotokopi & Digital',
    'percetakan'  => 'Percetakan',
    'jasa'        => 'Jasa & Layanan',
    'umum'        => 'Umum',
);

$config['cms_embed_platforms'] = array(
    'youtube'   => array('label' => 'YouTube', 'icon' => 'bi bi-youtube', 'color' => '#FF0000'),
    'tiktok'    => array('label' => 'TikTok', 'icon' => 'bi bi-tiktok', 'color' => '#000000'),
    'instagram' => array('label' => 'Instagram', 'icon' => 'bi bi-instagram', 'color' => '#E4405F'),
    'facebook'  => array('label' => 'Facebook', 'icon' => 'bi bi-facebook', 'color' => '#1877F2'),
    'twitter'   => array('label' => 'X / Twitter', 'icon' => 'bi bi-twitter-x', 'color' => '#000000'),
    'embed'     => array('label' => 'Embed Kustom', 'icon' => 'bi bi-code-slash', 'color' => '#64748b'),
);

$config['cms_social_platforms'] = array(
    'instagram' => array('label' => 'Instagram', 'icon' => 'fab fa-instagram', 'color' => '#E4405F'),
    'tiktok'    => array('label' => 'TikTok', 'icon' => 'fab fa-tiktok', 'color' => '#000000'),
    'youtube'   => array('label' => 'YouTube', 'icon' => 'fab fa-youtube', 'color' => '#FF0000'),
    'facebook'  => array('label' => 'Facebook', 'icon' => 'fab fa-facebook-f', 'color' => '#1877F2'),
    'twitter'   => array('label' => 'X / Twitter', 'icon' => 'fab fa-x-twitter', 'color' => '#000000'),
    'whatsapp'  => array('label' => 'WhatsApp', 'icon' => 'fab fa-whatsapp', 'color' => '#25D366'),
    'linkedin'  => array('label' => 'LinkedIn', 'icon' => 'fab fa-linkedin-in', 'color' => '#0A66C2'),
    'website'   => array('label' => 'Website', 'icon' => 'fas fa-globe', 'color' => '#0d6efd'),
);
