<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1 class="m-0"><?php echo htmlspecialchars(isset($page_title) ? $page_title : 'CMS Publikasi', ENT_QUOTES, 'UTF-8'); ?></h1>
                </div>
                <div class="col-sm-4 text-right">
                    <?php if (!empty($public_url)): ?>
                        <a href="<?php echo htmlspecialchars($public_url, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" class="btn btn-outline-primary btn-sm"><i class="fa fa-external-link"></i> Lihat Halaman Publik</a>
                    <?php else: ?>
                        <a href="<?php echo cms_public_url(); ?>" target="_blank" class="btn btn-outline-primary btn-sm"><i class="fa fa-external-link"></i> Lihat Halaman Publik</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <?php if ($this->session->flashdata('message')): ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?php echo $this->session->flashdata('message'); ?>
                </div>
            <?php endif; ?>

            <?php
            $seg2 = strtolower((string) $this->uri->segment(2));
            $nav_items = array(
                array('label' => 'Dashboard', 'url' => 'cms-admin', 'match' => array('', 'index')),
                array('label' => 'Konten', 'url' => 'cms-admin/posts', 'match' => array('posts', 'post_create', 'post_edit', 'post_save')),
                array('label' => 'Layanan', 'url' => 'cms-admin/services', 'match' => array('services', 'service_save', 'service_delete', 'service_toggle_active', 'service_toggle_publish')),
                array('label' => 'Video Sosial', 'url' => 'cms-admin/media_embeds', 'match' => array('media_embeds', 'media_embed_save', 'media_embed_delete', 'media_embed_toggle_active', 'media_embed_toggle_publish')),
                array('label' => 'Kategori', 'url' => 'cms-admin/categories', 'match' => array('categories', 'category_save', 'category_delete')),
                array('label' => 'Slider', 'url' => 'cms-admin/sliders', 'match' => array('sliders', 'slider_save', 'slider_delete')),
                array('label' => 'Galeri', 'url' => 'cms-admin/gallery', 'match' => array('gallery', 'gallery_save', 'gallery_delete', 'gallery_toggle_active', 'gallery_toggle_publish')),
                array('label' => 'Sosial Media', 'url' => 'cms-admin/social', 'match' => array('social', 'social_save', 'social_delete')),
                array('label' => 'Pengaturan', 'url' => 'cms-admin/settings', 'match' => array('settings', 'settings_save')),
                array('label' => 'Hak Akses', 'url' => 'cms-admin/access', 'match' => array('access', 'access_grant_user', 'access_grant_level', 'access_revoke_user', 'access_revoke_level')),
            );
            ?>

            <?php if (isset($cms_installed) && $cms_installed): ?>
            <ul class="nav nav-pills mb-3 flex-wrap">
                <?php foreach ($nav_items as $item):
                    $active = in_array($seg2, $item['match'], true);
                    if ($seg2 === '' && $item['url'] === 'cms-admin') {
                        $active = true;
                    }
                ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $active ? 'active' : ''; ?>" href="<?php echo site_url($item['url']); ?>"><?php echo $item['label']; ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
