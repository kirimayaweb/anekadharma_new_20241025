<section class="cms-section">
    <div class="container">
        <div class="mb-4" data-aos="fade-up">
            <h1 class="cms-section-title"><?php echo htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?></h1>
            <p class="cms-section-sub mb-0">
                <?php if (isset($post_type) && $post_type === 'berita'): ?>
                    Informasi harga pasar ATK, harga pabrik, fotokopi & percetakan — update terkini
                <?php elseif (isset($category) && $category): ?>
                    Kategori: <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                <?php elseif (isset($post_type)): ?>
                    <?php echo htmlspecialchars(cms_post_type_label($post_type), ENT_QUOTES, 'UTF-8'); ?>
                <?php endif; ?>
            </p>
        </div>

        <div class="row g-4">
            <?php if (empty($posts)): ?>
                <div class="col-12">
                    <div class="alert alert-light border shadow-sm">Belum ada konten untuk ditampilkan.</div>
                </div>
            <?php else: ?>
                <?php foreach ($posts as $i => $post): ?>
                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?php echo min($i * 70, 210); ?>">
                        <article class="cms-card-post">
                            <a href="<?php echo cms_public_url('berita/' . $post->slug); ?>">
                                <img src="<?php echo htmlspecialchars(cms_featured_image_url($post->featured_image), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($post->title, ENT_QUOTES, 'UTF-8'); ?>">
                            </a>
                            <div class="cms-card-body">
                                <span class="cms-badge"><?php echo htmlspecialchars(cms_post_type_label($post->post_type), ENT_QUOTES, 'UTF-8'); ?></span>
                                <h2 class="cms-card-title h5"><a href="<?php echo cms_public_url('berita/' . $post->slug); ?>"><?php echo htmlspecialchars($post->title, ENT_QUOTES, 'UTF-8'); ?></a></h2>
                                <div class="cms-card-meta"><i class="bi bi-calendar3 me-1"></i><?php echo cms_format_date($post->published_at); ?> · <i class="bi bi-eye ms-2 me-1"></i><?php echo (int) $post->view_count; ?></div>
                                <p class="cms-card-excerpt"><?php echo htmlspecialchars(cms_excerpt($post->excerpt ? $post->excerpt : $post->content), ENT_QUOTES, 'UTF-8'); ?></p>
                                <a class="cms-link-more" href="<?php echo cms_public_url('berita/' . $post->slug); ?>">Baca selengkapnya</a>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if (isset($total, $per_page, $current_page) && $total > $per_page): ?>
            <?php $total_pages = (int) ceil($total / $per_page); ?>
            <nav class="mt-5 cms-pagination" aria-label="Pagination">
                <ul class="pagination justify-content-center">
                    <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                        <li class="page-item <?php echo $p === (int) $current_page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $p; ?>"><?php echo $p; ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</section>
