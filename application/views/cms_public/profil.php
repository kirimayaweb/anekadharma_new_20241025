<section class="cms-section">
    <div class="container">
        <div class="cms-profile-panel mb-5" data-aos="fade-up">
            <span class="cms-badge">Tentang Kami</span>
            <h1 class="cms-section-title"><?php echo htmlspecialchars($company_name, ENT_QUOTES, 'UTF-8'); ?></h1>
            <p class="mb-0 fs-5"><?php echo nl2br(htmlspecialchars($company_about, ENT_QUOTES, 'UTF-8')); ?></p>
        </div>

        <?php if (!empty($posts)): ?>
            <div class="row g-4">
                <?php foreach ($posts as $i => $post): ?>
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="<?php echo min($i * 80, 160); ?>">
                        <article class="cms-card-post">
                            <?php if ($post->featured_image): ?>
                                <img src="<?php echo htmlspecialchars(cms_featured_image_url($post->featured_image), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($post->title, ENT_QUOTES, 'UTF-8'); ?>">
                            <?php endif; ?>
                            <div class="cms-card-body">
                                <h2 class="cms-card-title h4"><?php echo htmlspecialchars($post->title, ENT_QUOTES, 'UTF-8'); ?></h2>
                                <div class="cms-content-body">
                                    <?php echo cms_sanitize_html($post->content); ?>
                                </div>
                                <?php if ($post->video_url && $post->video_type !== 'none'): ?>
                                    <div class="mt-3"><?php echo cms_render_video_block($post); ?></div>
                                <?php endif; ?>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-light border">Profil perusahaan sedang disiapkan.</div>
        <?php endif; ?>
    </div>
</section>
