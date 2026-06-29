<?php
$company_name = isset($settings['company_name']) ? $settings['company_name'] : 'Perusahaan Kami';
$tagline = isset($settings['site_tagline']) ? $settings['site_tagline'] : 'Informasi dan publikasi terkini';
$about = isset($settings['company_about']) ? $settings['company_about'] : '';

/* Item ticker hero: berita, informasi, layanan */
$hero_ticker_items = array();
if (!empty($latest_news)) {
    foreach (array_slice($latest_news, 0, 6) as $p) {
        $hero_ticker_items[] = array(
            'type' => 'berita', 'icon' => 'bi bi-newspaper', 'label' => 'Berita',
            'text' => $p->title, 'url' => site_url('publikasi/berita/' . $p->slug),
        );
    }
}
if (!empty($info_posts)) {
    foreach (array_slice($info_posts, 0, 4) as $p) {
        $hero_ticker_items[] = array(
            'type' => 'informasi', 'icon' => 'bi bi-info-circle', 'label' => 'Info',
            'text' => $p->title, 'url' => site_url('publikasi/berita/' . $p->slug),
        );
    }
}
if (!empty($services)) {
    foreach (array_slice($services, 0, 4) as $s) {
        $hero_ticker_items[] = array(
            'type' => 'produk', 'icon' => 'bi bi-box-seam', 'label' => 'Layanan',
            'text' => $s->title, 'url' => site_url('publikasi#galeri'),
        );
    }
}
if (empty($hero_ticker_items)) {
    $hero_ticker_items[] = array(
        'type' => 'info', 'icon' => 'bi bi-stars', 'label' => 'Aneka Dharma',
        'text' => 'ATK · Fotokopi · Percetakan · Jasa', 'url' => site_url('publikasi'),
    );
}

$hero_rotate_words = array();
if (!empty($services)) {
    foreach ($services as $s) {
        $hero_rotate_words[] = $s->title;
    }
} else {
    $hero_rotate_words = array('Alat Tulis Kantor', 'Fotokopi & Print', 'Percetakan', 'Jasa Profesional');
}

$hero_float_cards = array_slice($hero_ticker_items, 0, 4);
?>

<?php if (!empty($sliders)): ?>
<section class="cms-hero cms-hero-enhanced p-0" id="beranda-hero">
    <div class="cms-hero-particles" aria-hidden="true">
        <span></span><span></span><span></span><span></span><span></span><span></span>
    </div>

    <div class="swiper cms-hero-swiper">
        <div class="swiper-wrapper">
            <?php foreach ($sliders as $si => $slide): ?>
                <?php
                $bg = $slide->image ? cms_featured_image_url($slide->image) : '';
                $style = $bg ? 'background-image:url(' . htmlspecialchars($bg, ENT_QUOTES, 'UTF-8') . ');' : '';
                ?>
                <div class="swiper-slide">
                    <div class="cms-hero-slide" style="<?php echo $style; ?>">
                        <div class="container py-5">
                            <div class="row align-items-center g-4">
                                <div class="col-lg-7">
                                    <div class="cms-hero-content cms-hero-animate">
                                        <div class="cms-hero-kicker cms-hero-kicker-pulse">
                                            <i class="bi bi-lightning-charge-fill"></i>
                                            <span>Publikasi Resmi Aneka Dharma</span>
                                        </div>
                                        <?php if ($slide->title): ?>
                                            <h1 class="cms-hero-title cms-hero-title-reveal"><?php echo htmlspecialchars($slide->title, ENT_QUOTES, 'UTF-8'); ?></h1>
                                        <?php endif; ?>
                                        <p class="cms-hero-rotate-line mb-2">
                                            Spesialis
                                            <span class="cms-hero-word-rotate" data-words="<?php echo htmlspecialchars(json_encode($hero_rotate_words, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8'); ?>">
                                                <span class="cms-hero-word-inner"><?php echo htmlspecialchars($hero_rotate_words[0], ENT_QUOTES, 'UTF-8'); ?></span>
                                            </span>
                                        </p>
                                        <?php if ($slide->subtitle): ?>
                                            <p class="cms-hero-text cms-hero-text-reveal"><?php echo htmlspecialchars($slide->subtitle, ENT_QUOTES, 'UTF-8'); ?></p>
                                        <?php endif; ?>
                                        <div class="cms-hero-actions cms-hero-actions-reveal">
                                            <a href="<?php echo site_url('publikasi/berita'); ?>" class="btn btn-cms-primary btn-lg rounded-pill px-4 me-2 mb-2">
                                                <i class="bi bi-newspaper me-1"></i> Lihat Berita & Harga
                                            </a>
                                            <?php if ($slide->link_url): ?>
                                                <a href="<?php echo htmlspecialchars($slide->link_url, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-outline-light btn-lg rounded-pill px-4 mb-2">
                                                    <?php echo htmlspecialchars($slide->button_text ? $slide->button_text : 'Selengkapnya', ENT_QUOTES, 'UTF-8'); ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                        <div class="cms-hero-mini-stats">
                                            <div class="cms-hero-stat"><strong class="cms-count-up" data-target="<?php echo count($latest_news); ?>">0</strong><span>Berita</span></div>
                                            <div class="cms-hero-stat"><strong class="cms-count-up" data-target="<?php echo count($info_posts); ?>">0</strong><span>Informasi</span></div>
                                            <div class="cms-hero-stat"><strong class="cms-count-up" data-target="<?php echo count($gallery); ?>">0</strong><span>Media</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5 d-none d-lg-block">
                                    <div class="cms-hero-float-stack">
                                        <?php foreach ($hero_float_cards as $fi => $card): ?>
                                            <a href="<?php echo htmlspecialchars($card['url'], ENT_QUOTES, 'UTF-8'); ?>"
                                               class="cms-hero-float-card cms-hero-float-<?php echo ($fi % 4) + 1; ?>"
                                               style="animation-delay: <?php echo ($fi * 0.15); ?>s;">
                                                <span class="cms-hero-float-icon"><i class="<?php echo htmlspecialchars($card['icon'], ENT_QUOTES, 'UTF-8'); ?>"></i></span>
                                                <span class="cms-hero-float-label"><?php echo htmlspecialchars($card['label'], ENT_QUOTES, 'UTF-8'); ?></span>
                                                <span class="cms-hero-float-text"><?php echo htmlspecialchars(cms_excerpt($card['text'], 55), ENT_QUOTES, 'UTF-8'); ?></span>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="cms-hero-pagination"></div>
        <div class="cms-hero-prev swiper-button-prev"></div>
        <div class="cms-hero-next swiper-button-next"></div>
    </div>

    <div class="cms-hero-overlay-bottom">
        <a href="#konten-utama" class="cms-scroll-hint cms-scroll-hint-side" aria-label="Scroll ke bawah untuk informasi lebih lanjut">
            <span class="cms-scroll-hint-mouse" aria-hidden="true"><span class="cms-scroll-hint-wheel"></span></span>
            <span class="cms-scroll-hint-text">Scroll<br>info lebih lanjut</span>
            <i class="bi bi-chevron-double-down cms-scroll-hint-arrow" aria-hidden="true"></i>
        </a>
        <div class="cms-hero-ticker-wrap">
            <div class="container-fluid px-lg-4">
                <div class="cms-hero-ticker-bar">
                    <div class="cms-hero-ticker-badge"><i class="bi bi-broadcast"></i> Live Update</div>
                    <div class="cms-hero-ticker-viewport">
                        <div class="cms-hero-ticker-track">
                            <?php for ($dup = 0; $dup < 2; $dup++): ?>
                                <?php foreach ($hero_ticker_items as $item): ?>
                                    <a href="<?php echo htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8'); ?>" class="cms-ticker-item cms-ticker-<?php echo htmlspecialchars($item['type'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <i class="<?php echo htmlspecialchars($item['icon'], ENT_QUOTES, 'UTF-8'); ?>"></i>
                                        <span class="cms-ticker-label"><?php echo htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8'); ?></span>
                                        <span class="cms-ticker-text"><?php echo htmlspecialchars($item['text'], ENT_QUOTES, 'UTF-8'); ?></span>
                                        <i class="bi bi-chevron-right cms-ticker-arrow"></i>
                                    </a>
                                <?php endforeach; ?>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php else: ?>
<section class="cms-hero-fallback cms-hero-enhanced" id="beranda-hero">
    <div class="cms-hero-particles" aria-hidden="true"><span></span><span></span><span></span><span></span></div>
    <div class="container py-5">
        <div class="cms-hero-content cms-hero-animate is-visible">
            <div class="cms-hero-kicker cms-hero-kicker-pulse"><i class="bi bi-stars"></i> Selamat Datang</div>
            <h1 class="cms-hero-title"><?php echo htmlspecialchars($company_name, ENT_QUOTES, 'UTF-8'); ?></h1>
            <p class="cms-hero-rotate-line mb-2">Spesialis <span class="cms-hero-word-rotate" data-words='<?php echo htmlspecialchars(json_encode($hero_rotate_words, JSON_UNESCAPED_UNICODE), ENT_QUOTES, "UTF-8"); ?>'><span class="cms-hero-word-inner"><?php echo htmlspecialchars($hero_rotate_words[0], ENT_QUOTES, 'UTF-8'); ?></span></span></p>
            <p class="cms-hero-text"><?php echo htmlspecialchars($tagline, ENT_QUOTES, 'UTF-8'); ?></p>
            <a href="<?php echo site_url('publikasi/profil'); ?>" class="btn btn-light btn-lg rounded-pill px-4 fw-bold">Kenali Kami</a>
        </div>
    </div>
    <div class="cms-hero-overlay-bottom">
        <a href="#konten-utama" class="cms-scroll-hint cms-scroll-hint-side" aria-label="Scroll ke bawah">
            <span class="cms-scroll-hint-mouse"><span class="cms-scroll-hint-wheel"></span></span>
            <span class="cms-scroll-hint-text">Scroll<br>info lebih lanjut</span>
            <i class="bi bi-chevron-double-down cms-scroll-hint-arrow"></i>
        </a>
        <div class="cms-hero-ticker-wrap">
            <div class="container-fluid px-lg-4">
                <div class="cms-hero-ticker-bar">
                    <div class="cms-hero-ticker-badge"><i class="bi bi-broadcast"></i> Live Update</div>
                    <div class="cms-hero-ticker-viewport">
                        <div class="cms-hero-ticker-track">
                            <?php for ($dup = 0; $dup < 2; $dup++): ?>
                                <?php foreach ($hero_ticker_items as $item): ?>
                                    <a href="<?php echo htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8'); ?>" class="cms-ticker-item"><i class="<?php echo htmlspecialchars($item['icon'], ENT_QUOTES, 'UTF-8'); ?>"></i><span class="cms-ticker-label"><?php echo htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8'); ?></span><span class="cms-ticker-text"><?php echo htmlspecialchars($item['text'], ENT_QUOTES, 'UTF-8'); ?></span></a>
                                <?php endforeach; ?>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="cms-section" id="konten-utama">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="cms-profile-panel">
                    <span class="cms-badge">Company Profile</span>
                    <h2 class="cms-section-title"><?php echo htmlspecialchars($company_name, ENT_QUOTES, 'UTF-8'); ?></h2>
                    <p class="mb-0"><?php echo nl2br(htmlspecialchars($about, ENT_QUOTES, 'UTF-8')); ?></p>
                    <div class="cms-stat-grid">
                        <div class="cms-stat-item"><strong><?php echo count($latest_news); ?>+</strong><span>Berita</span></div>
                        <div class="cms-stat-item"><strong><?php echo count($info_posts); ?>+</strong><span>Informasi</span></div>
                        <div class="cms-stat-item"><strong><?php echo count($gallery); ?>+</strong><span>Media</span></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <?php if (!empty($profile_posts)): ?>
                    <?php $p = $profile_posts[0]; ?>
                    <div class="cms-card-post">
                        <img src="<?php echo htmlspecialchars(cms_featured_image_url($p->featured_image), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($p->title, ENT_QUOTES, 'UTF-8'); ?>">
                        <div class="cms-card-body">
                            <span class="cms-badge">Profil</span>
                            <h3 class="cms-card-title"><a href="<?php echo site_url('publikasi/berita/' . $p->slug); ?>"><?php echo htmlspecialchars($p->title, ENT_QUOTES, 'UTF-8'); ?></a></h3>
                            <p class="cms-card-excerpt"><?php echo htmlspecialchars(cms_excerpt($p->excerpt ? $p->excerpt : $p->content, 140), ENT_QUOTES, 'UTF-8'); ?></p>
                            <a class="cms-link-more" href="<?php echo site_url('publikasi/profil'); ?>">Lihat profil lengkap <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($services)): ?>
<section class="cms-section pt-0" id="layanan">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="cms-badge">Produk & Jasa</span>
            <h2 class="cms-section-title">Layanan Kami</h2>
            <p class="cms-section-sub">ATK · Fotokopi · Percetakan · Jasa profesional untuk kebutuhan kantor & bisnis Anda</p>
        </div>
        <div class="row g-4">
            <?php foreach ($services as $i => $svc): ?>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="<?php echo min($i * 80, 240); ?>">
                    <div class="cms-service-card h-100">
                        <div class="cms-service-img" style="background-image:url('<?php echo htmlspecialchars(cms_featured_image_url($svc->image_url), ENT_QUOTES, 'UTF-8'); ?>')">
                            <span class="cms-service-icon"><i class="<?php echo htmlspecialchars($svc->icon ? $svc->icon : 'bi bi-briefcase', ENT_QUOTES, 'UTF-8'); ?>"></i></span>
                        </div>
                        <div class="cms-service-body">
                            <h3 class="h5 fw-bold"><?php echo htmlspecialchars($svc->title, ENT_QUOTES, 'UTF-8'); ?></h3>
                            <p class="small text-muted mb-0"><?php echo htmlspecialchars(cms_excerpt($svc->description, 120), ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($media_embeds)): ?>
<section class="cms-section pt-0" id="video-sosial">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-end mb-4" data-aos="fade-up">
            <div>
                <h2 class="cms-section-title mb-1">Video & Media Sosial</h2>
                <p class="cms-section-sub mb-0">Konten menarik — mudah dibagikan ke WhatsApp, Instagram, TikTok & Facebook</p>
            </div>
            <a href="<?php echo site_url('publikasi/media-sosial'); ?>" class="cms-link-more">Semua video <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="row g-4">
            <?php foreach (array_slice($media_embeds, 0, 6) as $i => $media): ?>
                <div class="col-md-6" data-aos="zoom-in" data-aos-delay="<?php echo min($i * 100, 300); ?>">
                    <?php echo cms_render_media_card($media); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($featured_posts)): ?>
<section class="cms-section pt-0">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-end mb-4" data-aos="fade-up">
            <div>
                <h2 class="cms-section-title mb-1">Sorotan Utama</h2>
                <p class="cms-section-sub mb-0">Konten pilihan yang wajib Anda baca</p>
            </div>
            <a href="<?php echo site_url('publikasi/berita'); ?>" class="cms-link-more">Semua berita <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="row g-4">
            <?php foreach ($featured_posts as $i => $post): ?>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?php echo min($i * 80, 240); ?>">
                    <article class="cms-card-post">
                        <a href="<?php echo site_url('publikasi/berita/' . $post->slug); ?>">
                            <img src="<?php echo htmlspecialchars(cms_featured_image_url($post->featured_image), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($post->title, ENT_QUOTES, 'UTF-8'); ?>">
                        </a>
                        <div class="cms-card-body">
                            <span class="cms-badge"><?php echo htmlspecialchars(cms_post_type_label($post->post_type), ENT_QUOTES, 'UTF-8'); ?></span>
                            <h3 class="cms-card-title"><a href="<?php echo site_url('publikasi/berita/' . $post->slug); ?>"><?php echo htmlspecialchars($post->title, ENT_QUOTES, 'UTF-8'); ?></a></h3>
                            <div class="cms-card-meta"><i class="bi bi-calendar3 me-1"></i><?php echo cms_format_date($post->published_at); ?></div>
                            <p class="cms-card-excerpt"><?php echo htmlspecialchars(cms_excerpt($post->excerpt ? $post->excerpt : $post->content), ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="cms-section pt-0">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-end mb-4" data-aos="fade-up">
            <div>
                <h2 class="cms-section-title mb-1">Berita Terbaru</h2>
                <p class="cms-section-sub mb-0">Update dan kabar terkini dari kami</p>
            </div>
            <a href="<?php echo site_url('publikasi/berita'); ?>" class="cms-link-more">Lihat semua <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="row g-4">
            <?php if (empty($latest_news)): ?>
                <div class="col-12"><div class="alert alert-light border">Belum ada berita. Silakan kembali lagi nanti.</div></div>
            <?php else: ?>
                <?php foreach ($latest_news as $i => $post): ?>
                    <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="<?php echo min($i * 60, 180); ?>">
                        <article class="cms-card-post">
                            <a href="<?php echo site_url('publikasi/berita/' . $post->slug); ?>">
                                <img src="<?php echo htmlspecialchars(cms_featured_image_url($post->featured_image), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($post->title, ENT_QUOTES, 'UTF-8'); ?>">
                            </a>
                            <div class="cms-card-body">
                                <h3 class="cms-card-title"><a href="<?php echo site_url('publikasi/berita/' . $post->slug); ?>"><?php echo htmlspecialchars($post->title, ENT_QUOTES, 'UTF-8'); ?></a></h3>
                                <div class="cms-card-meta"><?php echo cms_format_date($post->published_at); ?></div>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php if (!empty($gallery)): ?>
<section class="cms-section pt-0">
    <div class="container">
        <div class="text-center mb-4" data-aos="fade-up">
            <h2 class="cms-section-title">Galeri & Media</h2>
            <p class="cms-section-sub">Foto, video, dan konten visual menarik</p>
        </div>
        <div class="cms-gallery-grid">
            <?php foreach (array_slice($gallery, 0, 12) as $i => $item): ?>
                <?php
                $href = cms_gallery_image_url($item);
                $img = cms_gallery_image_url($item);
                if ($item->media_type === 'youtube' && $item->external_url) {
                    $yt = cms_extract_youtube_id($item->external_url);
                    $href = $yt ? 'https://www.youtube.com/watch?v=' . $yt : $item->external_url;
                } elseif (!empty($item->external_url)) {
                    $href = $item->external_url;
                } elseif ($item->file_path) {
                    $href = cms_upload_url($item->file_path);
                }
                $share_title = !empty($item->share_title) ? $item->share_title : $item->title;
                ?>
                <a href="<?php echo htmlspecialchars($href, ENT_QUOTES, 'UTF-8'); ?>" class="cms-gallery-item glightbox" data-aos="fade-up" data-aos-delay="<?php echo min($i * 50, 200); ?>" data-title="<?php echo htmlspecialchars($share_title, ENT_QUOTES, 'UTF-8'); ?>">
                    <img src="<?php echo htmlspecialchars($img, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="cms-gallery-overlay"><?php echo htmlspecialchars($item->title ? $item->title : 'Media', ENT_QUOTES, 'UTF-8'); ?></div>
                </a>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="<?php echo site_url('publikasi/galeri'); ?>" class="btn btn-cms-primary rounded-pill px-4">Jelajahi Galeri</a>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($social_links)): ?>
<section class="cms-section pt-0 pb-5">
    <div class="container">
        <div class="text-center p-4 p-md-5 rounded-4" style="background:linear-gradient(135deg,var(--cms-primary),var(--cms-secondary));color:#fff;" data-aos="zoom-in">
            <h2 class="fw-bold mb-2">Terhubung dengan Kami</h2>
            <p class="mb-4 opacity-75">Ikuti media sosial kami untuk update terbaru</p>
            <div class="cms-social-row justify-content-center">
                <?php foreach ($social_links as $social): ?>
                    <a href="<?php echo htmlspecialchars($social->url, ENT_QUOTES, 'UTF-8'); ?>" class="cms-social-btn" target="_blank" rel="noopener noreferrer" style="background:rgba(255,255,255,0.15);">
                        <i class="<?php echo htmlspecialchars($social->icon_class ? $social->icon_class : 'bi bi-link-45deg', ENT_QUOTES, 'UTF-8'); ?>"></i>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<div class="modal fade" id="cmsVideoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark border-0">
            <div class="modal-body p-0">
                <div class="ratio ratio-16x9"><iframe src="" allowfullscreen></iframe></div>
            </div>
        </div>
    </div>
</div>
