<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?> | <?php echo htmlspecialchars($site_title, ENT_QUOTES, 'UTF-8'); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars(isset($settings['site_tagline']) ? $settings['site_tagline'] : '', ENT_QUOTES, 'UTF-8'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" rel="stylesheet">
    <link href="<?php echo base_url('assets/cms_public/css/cms-public.css'); ?>" rel="stylesheet">
    <style>
        :root {
            --cms-primary: <?php echo htmlspecialchars(isset($settings['primary_color']) ? $settings['primary_color'] : '#2563eb', ENT_QUOTES, 'UTF-8'); ?>;
            --cms-secondary: <?php echo htmlspecialchars(isset($settings['secondary_color']) ? $settings['secondary_color'] : '#7c3aed', ENT_QUOTES, 'UTF-8'); ?>;
        }
    </style>
</head>
<body class="cms-public-body">

<?php
$cms_uri = trim(uri_string(), '/');
$cms_nav_items = array(
    array('key' => 'home', 'label' => 'Beranda', 'icon' => 'bi-house-door-fill', 'url' => cms_public_url()),
    array('key' => 'profil', 'label' => 'Profil', 'icon' => 'bi-building', 'url' => cms_public_url('profil')),
    array('key' => 'berita', 'label' => 'Berita', 'icon' => 'bi-newspaper', 'url' => cms_public_url('berita')),
    array('key' => 'informasi', 'label' => 'Informasi', 'icon' => 'bi-info-circle-fill', 'url' => cms_public_url('informasi')),
    array('key' => 'galeri', 'label' => 'Galeri', 'icon' => 'bi-images', 'url' => cms_public_url('galeri')),
    array('key' => 'media', 'label' => 'Video & Sosial', 'icon' => 'bi-play-btn-fill', 'url' => cms_public_url('media-sosial')),
);
if (!function_exists('cms_nav_is_active')) {
    function cms_nav_is_active($key, $uri)
    {
        if ($key === 'home') {
            return ($uri === '' || $uri === 'publikasi');
        }
        if ($key === 'berita') {
            return (strpos($uri, 'publikasi/berita') === 0);
        }
        if ($key === 'informasi') {
            return (strpos($uri, 'publikasi/informasi') === 0);
        }
        if ($key === 'profil') {
            return (strpos($uri, 'publikasi/profil') === 0);
        }
        if ($key === 'galeri') {
            return (strpos($uri, 'publikasi/galeri') === 0);
        }
        if ($key === 'media') {
            return (strpos($uri, 'publikasi/media-sosial') === 0);
        }
        return false;
    }
}
$cms_tagline = isset($settings['site_tagline']) ? $settings['site_tagline'] : 'Publikasi Resmi';
?>

<nav class="cms-navbar navbar navbar-expand-lg fixed-top">
    <div class="cms-navbar-accent" aria-hidden="true"></div>
    <div class="container">
        <a class="navbar-brand cms-brand" href="<?php echo cms_public_url(); ?>">
            <span class="cms-brand-icon"><i class="bi bi-stars"></i></span>
            <span class="cms-brand-text">
                <span class="cms-brand-name"><?php echo htmlspecialchars(isset($settings['company_name']) ? $settings['company_name'] : 'Informasi', ENT_QUOTES, 'UTF-8'); ?></span>
                <span class="cms-brand-tagline"><?php echo htmlspecialchars($cms_tagline, ENT_QUOTES, 'UTF-8'); ?></span>
            </span>
        </a>
        <button class="navbar-toggler cms-nav-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#cmsNav" aria-controls="cmsNav" aria-expanded="false" aria-label="Buka menu navigasi">
            <span class="cms-nav-toggler-bar"></span>
            <span class="cms-nav-toggler-bar"></span>
            <span class="cms-nav-toggler-bar"></span>
        </button>
        <div class="collapse navbar-collapse" id="cmsNav">
            <ul class="navbar-nav cms-nav-menu ms-auto mb-2 mb-lg-0 align-items-lg-center">
                <?php foreach ($cms_nav_items as $nav): ?>
                    <?php $nav_active = cms_nav_is_active($nav['key'], $cms_uri) ? ' active' : ''; ?>
                    <li class="nav-item">
                        <a class="nav-link cms-nav-link<?php echo $nav_active; ?>" href="<?php echo htmlspecialchars($nav['url'], ENT_QUOTES, 'UTF-8'); ?>">
                            <span class="cms-nav-icon"><i class="bi <?php echo htmlspecialchars($nav['icon'], ENT_QUOTES, 'UTF-8'); ?>"></i></span>
                            <span class="cms-nav-label"><?php echo htmlspecialchars($nav['label'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
                <li class="nav-item ms-lg-2">
                    <form class="cms-search-form" action="<?php echo cms_public_url('cari'); ?>" method="get" role="search">
                        <div class="cms-search-wrap">
                            <i class="bi bi-search cms-search-icon" aria-hidden="true"></i>
                            <input type="search" name="q" class="form-control cms-search-input" placeholder="Cari informasi..." aria-label="Cari informasi">
                            <button class="cms-search-btn" type="submit" aria-label="Cari">
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script>
(function () {
    var nav = document.querySelector('.cms-navbar');
    if (nav) {
        document.documentElement.style.setProperty('--cms-navbar-height', Math.ceil(nav.getBoundingClientRect().height) + 'px');
    }
})();
</script>

<main class="cms-main">
