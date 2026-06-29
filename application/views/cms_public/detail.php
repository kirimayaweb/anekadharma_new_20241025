<section class="cms-detail-hero">
    <div class="container">
        <div data-aos="fade-up">
            <span class="cms-badge bg-white text-dark"><?php echo htmlspecialchars(cms_post_type_label($post->post_type), ENT_QUOTES, 'UTF-8'); ?></span>
            <h1 class="display-5 fw-bold mt-2 mb-3"><?php echo htmlspecialchars($post->title, ENT_QUOTES, 'UTF-8'); ?></h1>
            <div class="opacity-75">
                <i class="bi bi-calendar3 me-1"></i><?php echo cms_format_date($post->published_at, 'd F Y'); ?>
                · <i class="bi bi-eye ms-2 me-1"></i><?php echo (int) $post->view_count; ?> views
                <?php if ($category): ?>
                    · <i class="bi bi-folder2-open ms-2 me-1"></i><?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<section class="cms-section pt-0">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <article class="cms-detail-content" data-aos="fade-up">
                    <?php if ($post->featured_image): ?>
                        <img src="<?php echo htmlspecialchars(cms_featured_image_url($post->featured_image), ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid mb-4" alt="<?php echo htmlspecialchars($post->title, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php endif; ?>

                    <?php if ($post->excerpt): ?>
                        <p class="lead text-muted"><?php echo htmlspecialchars($post->excerpt, ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endif; ?>

                    <div class="cms-content-body">
                        <?php echo cms_sanitize_html($post->content); ?>
                    </div>

                    <?php if ($post->video_url && $post->video_type !== 'none'): ?>
                        <div class="mt-4">
                            <h4 class="fw-bold mb-3">Video</h4>
                            <?php echo cms_render_video_block($post); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($post->tags): ?>
                        <div class="mt-4">
                            <?php foreach (explode(',', $post->tags) as $tag): ?>
                                <span class="badge rounded-pill text-bg-light me-1 mb-1"><?php echo htmlspecialchars(trim($tag), ENT_QUOTES, 'UTF-8'); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </article>
            </div>
            <div class="col-lg-4">
                <div class="cms-card-post p-3 mb-4" data-aos="fade-left">
                    <h5 class="fw-bold mb-3">Bagikan</h5>
                    <div class="d-flex gap-2 flex-wrap">
                        <a class="btn btn-sm btn-outline-primary rounded-pill" target="_blank" rel="noopener" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(current_url()); ?>"><i class="bi bi-facebook"></i></a>
                        <a class="btn btn-sm btn-outline-info rounded-pill" target="_blank" rel="noopener" href="https://twitter.com/intent/tweet?url=<?php echo urlencode(current_url()); ?>&text=<?php echo urlencode($post->title); ?>"><i class="bi bi-twitter-x"></i></a>
                        <a class="btn btn-sm btn-outline-success rounded-pill" target="_blank" rel="noopener" href="https://wa.me/?text=<?php echo urlencode($post->title . ' ' . current_url()); ?>"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>

                <?php if (!empty($related_posts)): ?>
                    <div class="cms-card-post p-3" data-aos="fade-left" data-aos-delay="100">
                        <h5 class="fw-bold mb-3">Terkait</h5>
                        <?php foreach ($related_posts as $rel): ?>
                            <div class="mb-3 pb-3 border-bottom">
                                <a href="<?php echo site_url('publikasi/berita/' . $rel->slug); ?>" class="fw-semibold text-decoration-none text-dark"><?php echo htmlspecialchars($rel->title, ENT_QUOTES, 'UTF-8'); ?></a>
                                <div class="small text-muted"><?php echo cms_format_date($rel->published_at); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
