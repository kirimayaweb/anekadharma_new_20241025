<section class="cms-section">
    <div class="container">
        <div class="mb-4" data-aos="fade-up">
            <h1 class="cms-section-title">Hasil Pencarian</h1>
            <p class="cms-section-sub">Kata kunci: <strong><?php echo htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8'); ?></strong></p>
        </div>

        <div class="row g-4">
            <?php if ($keyword === ''): ?>
                <div class="col-12"><div class="alert alert-light border">Masukkan kata kunci pencarian.</div></div>
            <?php elseif (empty($posts)): ?>
                <div class="col-12"><div class="alert alert-light border">Tidak ditemukan hasil untuk pencarian Anda.</div></div>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <div class="col-md-6 col-lg-4">
                        <article class="cms-card-post">
                            <div class="cms-card-body">
                                <span class="cms-badge"><?php echo htmlspecialchars(cms_post_type_label($post->post_type), ENT_QUOTES, 'UTF-8'); ?></span>
                                <h2 class="cms-card-title h5"><a href="<?php echo site_url('publikasi/berita/' . $post->slug); ?>"><?php echo htmlspecialchars($post->title, ENT_QUOTES, 'UTF-8'); ?></a></h2>
                                <p class="cms-card-excerpt"><?php echo htmlspecialchars(cms_excerpt($post->excerpt ? $post->excerpt : $post->content), ENT_QUOTES, 'UTF-8'); ?></p>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>
