<section class="cms-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="cms-badge">Share ke Sosial Media</span>
            <h1 class="cms-section-title">Video & Media Sosial</h1>
            <p class="cms-section-sub mb-0">YouTube, TikTok, Instagram, Facebook — konten ATK, fotokopi, percetakan & jasa</p>
        </div>

        <?php if (!empty($social_links)): ?>
        <div class="text-center mb-5" data-aos="fade-up">
            <div class="cms-social-row justify-content-center">
                <?php foreach ($social_links as $social): ?>
                    <a href="<?php echo htmlspecialchars($social->url, ENT_QUOTES, 'UTF-8'); ?>" class="cms-social-btn cms-social-btn-lg" target="_blank" rel="noopener noreferrer" style="background:linear-gradient(135deg,var(--cms-primary),var(--cms-secondary));">
                        <i class="<?php echo htmlspecialchars($social->icon_class ? $social->icon_class : 'bi bi-link-45deg', ENT_QUOTES, 'UTF-8'); ?>"></i>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if (empty($media_embeds)): ?>
            <div class="alert alert-light border text-center">Belum ada video/media sosial. Admin dapat menambahkan di CMS Admin → Video Sosial.</div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($media_embeds as $i => $media): ?>
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="<?php echo min($i * 80, 320); ?>">
                        <?php echo cms_render_media_card($media); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($gallery)): ?>
        <div class="mt-5 pt-4">
            <h2 class="cms-section-title text-center mb-4" data-aos="fade-up">Galeri Foto Layanan</h2>
            <div class="cms-gallery-grid">
                <?php foreach ($gallery as $i => $item): ?>
                    <?php
                    $href = cms_gallery_image_url($item);
                    $img = cms_gallery_image_url($item);
                    if ($item->external_url) { $href = $item->external_url; }
                    ?>
                    <a href="<?php echo htmlspecialchars($href, ENT_QUOTES, 'UTF-8'); ?>" class="cms-gallery-item glightbox" data-aos="zoom-in">
                        <img src="<?php echo htmlspecialchars($img, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8'); ?>">
                        <div class="cms-gallery-overlay"><?php echo htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8'); ?></div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>
