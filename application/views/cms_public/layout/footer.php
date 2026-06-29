</main>

<footer class="cms-footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <h5 class="cms-footer-title"><?php echo htmlspecialchars(isset($settings['company_name']) ? $settings['company_name'] : 'Informasi', ENT_QUOTES, 'UTF-8'); ?></h5>
                <p class="cms-footer-text"><?php echo htmlspecialchars(isset($settings['site_tagline']) ? $settings['site_tagline'] : '', ENT_QUOTES, 'UTF-8'); ?></p>
                <?php if (!empty($settings['contact_address'])): ?>
                    <p class="cms-footer-text mb-1"><i class="bi bi-geo-alt me-2"></i><?php echo htmlspecialchars($settings['contact_address'], ENT_QUOTES, 'UTF-8'); ?></p>
                <?php endif; ?>
                <?php if (!empty($settings['contact_phone'])): ?>
                    <p class="cms-footer-text mb-1"><i class="bi bi-telephone me-2"></i><?php echo htmlspecialchars($settings['contact_phone'], ENT_QUOTES, 'UTF-8'); ?></p>
                <?php endif; ?>
                <?php if (!empty($settings['contact_email'])): ?>
                    <p class="cms-footer-text"><i class="bi bi-envelope me-2"></i><?php echo htmlspecialchars($settings['contact_email'], ENT_QUOTES, 'UTF-8'); ?></p>
                <?php endif; ?>
            </div>
            <div class="col-lg-4">
                <h5 class="cms-footer-title">Navigasi</h5>
                <ul class="cms-footer-links list-unstyled">
                    <li><a href="<?php echo site_url('publikasi'); ?>">Beranda</a></li>
                    <li><a href="<?php echo site_url('publikasi/profil'); ?>">Company Profile</a></li>
                    <li><a href="<?php echo site_url('publikasi/berita'); ?>">Berita</a></li>
                    <li><a href="<?php echo site_url('publikasi/informasi'); ?>">Informasi Umum</a></li>
                    <li><a href="<?php echo site_url('publikasi/galeri'); ?>">Galeri</a></li>
                    <li><a href="<?php echo site_url('publikasi/media-sosial'); ?>">Video & Sosial</a></li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h5 class="cms-footer-title">Ikuti Kami</h5>
                <div class="cms-social-row">
                    <?php if (!empty($social_links)): ?>
                        <?php foreach ($social_links as $social): ?>
                            <a href="<?php echo htmlspecialchars($social->url, ENT_QUOTES, 'UTF-8'); ?>" class="cms-social-btn" target="_blank" rel="noopener noreferrer" title="<?php echo htmlspecialchars($social->label ? $social->label : $social->platform, ENT_QUOTES, 'UTF-8'); ?>">
                                <i class="<?php echo htmlspecialchars($social->icon_class ? $social->icon_class : 'bi bi-link-45deg', ENT_QUOTES, 'UTF-8'); ?>"></i>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="cms-footer-text">Media sosial belum dikonfigurasi.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="cms-footer-bottom text-center">
            <small>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars(isset($settings['company_name']) ? $settings['company_name'] : 'Aneka Dharma', ENT_QUOTES, 'UTF-8'); ?>. Semua hak dilindungi.</small>
        </div>
    </div>
</footer>

<a href="#" class="cms-back-top" id="cmsBackTop" aria-label="Kembali ke atas"><i class="bi bi-arrow-up"></i></a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
<script async src="//www.instagram.com/embed.js"></script>
<script async src="https://www.tiktok.com/embed.js"></script>
<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
<script src="<?php echo base_url('assets/cms_public/js/cms-public.js'); ?>"></script>
</body>
</html>
