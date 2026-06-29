<section class="cms-section">
    <div class="container">
        <div class="text-center mb-4" data-aos="fade-up">
            <h1 class="cms-section-title">Galeri Foto</h1>
            <p class="cms-section-sub mb-3">ATK · Fotokopi · Percetakan · Jasa — ilustrasi bebas publikasi (Unsplash)</p>
            <div class="cms-gallery-filters btn-group flex-wrap" role="group">
                <button type="button" class="btn btn-sm btn-outline-primary active" data-filter="all">Semua</button>
                <button type="button" class="btn btn-sm btn-outline-primary" data-filter="atk">ATK</button>
                <button type="button" class="btn btn-sm btn-outline-primary" data-filter="fotokopi">Fotokopi</button>
                <button type="button" class="btn btn-sm btn-outline-primary" data-filter="percetakan">Percetakan</button>
                <button type="button" class="btn btn-sm btn-outline-primary" data-filter="jasa">Jasa</button>
            </div>
        </div>

        <?php if (empty($gallery)): ?>
            <div class="alert alert-light border text-center">Galeri masih kosong.</div>
        <?php else: ?>
            <div class="cms-gallery-grid" id="cmsGalleryGrid">
                <?php foreach ($gallery as $i => $item): ?>
                    <?php
                    $img = cms_gallery_image_url($item);
                    $href = $img;
                    $cat = isset($item->category) ? $item->category : 'umum';
                    if ($item->media_type === 'youtube' && $item->external_url) {
                        $yt = cms_extract_youtube_id($item->external_url);
                        $href = $yt ? 'https://www.youtube.com/watch?v=' . $yt : $item->external_url;
                    } elseif ($item->external_url) {
                        $href = $item->external_url;
                    } elseif ($item->file_path) {
                        $href = cms_upload_url($item->file_path);
                    }
                    $share_title = !empty($item->share_title) ? $item->share_title : $item->title;
                    ?>
                    <a href="<?php echo htmlspecialchars($href, ENT_QUOTES, 'UTF-8'); ?>"
                       class="cms-gallery-item glightbox cms-gal-cat-<?php echo htmlspecialchars($cat, ENT_QUOTES, 'UTF-8'); ?>"
                       data-category="<?php echo htmlspecialchars($cat, ENT_QUOTES, 'UTF-8'); ?>"
                       data-aos="zoom-in" data-aos-delay="<?php echo min(($i % 6) * 40, 200); ?>"
                       data-title="<?php echo htmlspecialchars($share_title, ENT_QUOTES, 'UTF-8'); ?>">
                        <img src="<?php echo htmlspecialchars($img, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8'); ?>" loading="lazy">
                        <div class="cms-gallery-overlay">
                            <span class="badge bg-light text-dark mb-1"><?php echo strtoupper(htmlspecialchars($cat, ENT_QUOTES, 'UTF-8')); ?></span>
                            <?php echo htmlspecialchars($item->title ? $item->title : 'Media', ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var btns = document.querySelectorAll('.cms-gallery-filters [data-filter]');
    var items = document.querySelectorAll('#cmsGalleryGrid .cms-gallery-item');
    btns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            btns.forEach(function (b) { b.classList.remove('active'); });
            btn.classList.add('active');
            var f = btn.getAttribute('data-filter');
            items.forEach(function (el) {
                var show = f === 'all' || el.getAttribute('data-category') === f;
                el.style.display = show ? '' : 'none';
            });
        });
    });
});
</script>
