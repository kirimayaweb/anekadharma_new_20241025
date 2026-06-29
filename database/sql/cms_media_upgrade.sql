-- Upgrade CMS: Layanan bisnis + Media Sosial Embed + Galeri diperluas
-- Jalankan sekali pada database yang sudah punya CMS

SET NAMES utf8;

CREATE TABLE IF NOT EXISTS `cms_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `slug` varchar(150) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(80) DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `link_url` varchar(500) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `published_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `cms_media_embeds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(300) NOT NULL,
  `description` text DEFAULT NULL,
  `platform` enum('youtube','tiktok','instagram','facebook','twitter','embed') NOT NULL DEFAULT 'youtube',
  `source_url` varchar(500) NOT NULL,
  `embed_code` text DEFAULT NULL,
  `thumbnail_url` varchar(500) DEFAULT NULL,
  `category` varchar(50) NOT NULL DEFAULT 'umum',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `published_at` datetime DEFAULT NULL,
  `expire_at` datetime DEFAULT NULL,
  `share_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_cms_media_pub` (`is_published`,`is_active`,`published_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Perluas galeri (kolom ditambah otomatis via cms_ensure_gallery_schema() saat install/upgrade)
ALTER TABLE `cms_gallery`
  MODIFY COLUMN `media_type` enum('image','video','youtube','tiktok','instagram','facebook') NOT NULL DEFAULT 'image';

ALTER TABLE `cms_gallery` ADD COLUMN `thumbnail_url` varchar(500) DEFAULT NULL;
ALTER TABLE `cms_gallery` ADD COLUMN `category` varchar(50) DEFAULT 'umum';
ALTER TABLE `cms_gallery` ADD COLUMN `is_published` tinyint(1) NOT NULL DEFAULT 1;
ALTER TABLE `cms_gallery` ADD COLUMN `published_at` datetime DEFAULT NULL;
ALTER TABLE `cms_gallery` ADD COLUMN `share_title` varchar(300) DEFAULT NULL;

-- Update pengaturan bisnis ATK & Jasa
UPDATE `cms_settings` SET `setting_value` = 'Aneka Dharma — ATK, Fotokopi, Percetakan & Jasa' WHERE `setting_key` = 'site_title';
UPDATE `cms_settings` SET `setting_value` = 'Solusi lengkap alat tulis kantor, fotokopi, percetakan, dan layanan jasa profesional' WHERE `setting_key` = 'site_tagline';
UPDATE `cms_settings` SET `setting_value` = 'PT Aneka Dharma' WHERE `setting_key` = 'company_name';
UPDATE `cms_settings` SET `setting_value` = 'PT Aneka Dharma hadir melayani kebutuhan alat tulis kantor (ATK), layanan fotokopi & digital printing, percetakan berbagai media, serta berbagai jasa pendukung bisnis dan kantor. Kami berkomitmen memberikan produk berkualitas, harga kompetitif, dan pelayanan cepat ramah.' WHERE `setting_key` = 'company_about';

-- Kategori layanan
INSERT IGNORE INTO `cms_categories` (`slug`, `name`, `description`, `icon`, `sort_order`, `is_active`, `created_at`) VALUES
('alat-tulis-kantor', 'Alat Tulis Kantor', 'Produk ATK lengkap untuk kantor & sekolah', 'bi bi-pencil-square', 4, 1, NOW()),
('fotokopi-digital', 'Fotokopi & Digital', 'Fotocopy, scan, print dokumen', 'bi bi-printer', 5, 1, NOW()),
('percetakan', 'Percetakan', 'Cetak brosur, banner, kartu nama & lainnya', 'bi bi-layers', 6, 1, NOW()),
('jasa-layanan', 'Jasa & Layanan', 'Berbagai layanan jasa untuk kebutuhan Anda', 'bi bi-briefcase', 7, 1, NOW());

-- Layanan / ilustrasi bisnis (gambar Unsplash — bisa diganti upload admin)
INSERT INTO `cms_services` (`title`, `slug`, `description`, `icon`, `image_url`, `link_url`, `sort_order`, `is_active`, `is_published`, `published_at`, `created_at`)
SELECT 'Alat Tulis Kantor (ATK)', 'atk',
  'Pensil, pulpen, kertas, map, binder, stapler, dan ribuan produk ATK untuk kantor, sekolah, dan usaha.',
  'bi bi-pencil-square',
  'https://images.unsplash.com/photo-1586953208448-b95a79798f07?w=1200&q=80',
  '', 1, 1, 1, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT id FROM cms_services WHERE slug='atk' LIMIT 1);

INSERT INTO `cms_services` (`title`, `slug`, `description`, `icon`, `image_url`, `link_url`, `sort_order`, `is_active`, `is_published`, `published_at`, `created_at`)
SELECT 'Fotokopi & Digital Printing', 'fotokopi', 'Fotocopy hitam putih & warna, scan dokumen, print PDF, laminating, dan jasa digital printing cepat.', 'bi bi-printer', 'https://images.unsplash.com/photo-1612815159322-444f042139bb?w=1200&q=80', '', 2, 1, 1, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT id FROM cms_services WHERE slug='fotokopi' LIMIT 1);

INSERT INTO `cms_services` (`title`, `slug`, `description`, `icon`, `image_url`, `link_url`, `sort_order`, `is_active`, `is_published`, `published_at`, `created_at`)
SELECT 'Percetakan & Media Promosi', 'percetakan', 'Cetak brosur, flyer, spanduk, kartu nama, stiker, buku, dan berbagai kebutuhan percetakan promosi.', 'bi bi-layers', 'https://images.unsplash.com/photo-1562564055-71e051d33c19?w=1200&q=80', '', 3, 1, 1, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT id FROM cms_services WHERE slug='percetakan' LIMIT 1);

INSERT INTO `cms_services` (`title`, `slug`, `description`, `icon`, `image_url`, `link_url`, `sort_order`, `is_active`, `is_published`, `published_at`, `created_at`)
SELECT 'Jasa & Layanan Profesional', 'jasa', 'Penjilidan, cutting, design grafis, pengiriman dokumen, dan layanan jasa pendukung operasional kantor & bisnis.', 'bi bi-briefcase', 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=1200&q=80', '', 4, 1, 1, NOW(), NOW()
FROM DUAL WHERE NOT EXISTS (SELECT id FROM cms_services WHERE slug='jasa' LIMIT 1);

-- Slider hero bisnis
INSERT INTO `cms_sliders` (`title`, `subtitle`, `image`, `link_url`, `button_text`, `sort_order`, `is_active`, `created_at`)
SELECT 'ATK Lengkap & Harga Terbaik', 'Stok alat tulis kantor untuk perusahaan, sekolah, dan personal — siap kirim cepat.', 'https://images.unsplash.com/photo-1586953208448-b95a79798f07?w=1600&q=80', '', 'Lihat Layanan', 1, 1, NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_sliders LIMIT 1);

INSERT INTO `cms_sliders` (`title`, `subtitle`, `image`, `link_url`, `button_text`, `sort_order`, `is_active`, `created_at`)
SELECT 'Fotokopi & Cetak Digital', 'Dokumen, skripsi, rapat — cetak rapi dengan mesin modern.', 'https://images.unsplash.com/photo-1612815159322-444f042139bb?w=1600&q=80', '', 'Hubungi Kami', 2, 1, NOW()
FROM DUAL WHERE (SELECT COUNT(*) FROM cms_sliders) < 2;

INSERT INTO `cms_sliders` (`title`, `subtitle`, `image`, `link_url`, `button_text`, `sort_order`, `is_active`, `created_at`)
SELECT 'Percetakan Promosi & Jasa', 'Brosur, banner, kartu nama — plus layanan jasa pendukung bisnis Anda.', 'https://images.unsplash.com/photo-1562564055-71e051d33c19?w=1600&q=80', '', 'Selengkapnya', 3, 1, NOW()
FROM DUAL WHERE (SELECT COUNT(*) FROM cms_sliders) < 3;

-- Galeri ilustrasi
INSERT INTO `cms_gallery` (`title`, `description`, `media_type`, `file_path`, `external_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_title`, `created_at`)
SELECT 'Rak Produk ATK', 'Beragam alat tulis kantor siap pilih', 'image', NULL, 'https://images.unsplash.com/photo-1586953208448-b95a79798f07?w=1200&q=80', 'https://images.unsplash.com/photo-1586953208448-b95a79798f07?w=600&q=80', 'atk', 1, 1, 1, NOW(), 'Produk ATK Aneka Dharma', NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_gallery WHERE title='Rak Produk ATK' LIMIT 1);

INSERT INTO `cms_gallery` (`title`, `description`, `media_type`, `file_path`, `external_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_title`, `created_at`)
SELECT 'Layanan Fotokopi', 'Cetak dokumen cepat & berkualitas', 'image', NULL, 'https://images.unsplash.com/photo-1612815159322-444f042139bb?w=1200&q=80', 'https://images.unsplash.com/photo-1612815159322-444f042139bb?w=600&q=80', 'fotokopi', 2, 1, 1, NOW(), 'Fotokopi & Digital Print', NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_gallery WHERE title='Layanan Fotokopi' LIMIT 1);

INSERT INTO `cms_gallery` (`title`, `description`, `media_type`, `file_path`, `external_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_title`, `created_at`)
SELECT 'Percetakan Offset', 'Mesin cetak untuk kebutuhan promosi', 'image', NULL, 'https://images.unsplash.com/photo-1562564055-71e051d33c19?w=1200&q=80', 'https://images.unsplash.com/photo-1562564055-71e051d33c19?w=600&q=80', 'percetakan', 3, 1, 1, NOW(), 'Jasa Percetakan', NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_gallery WHERE title='Percetakan Offset' LIMIT 1);

INSERT INTO `cms_gallery` (`title`, `description`, `media_type`, `file_path`, `external_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_title`, `created_at`)
SELECT 'Ruang Kerja Modern', 'Mendukung produktivitas kantor Anda', 'image', NULL, 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=1200&q=80', 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=600&q=80', 'jasa', 4, 1, 1, NOW(), 'Solusi Kantor & Jasa', NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_gallery WHERE title='Ruang Kerja Modern' LIMIT 1);

INSERT INTO `cms_gallery` (`title`, `description`, `media_type`, `file_path`, `external_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_title`, `created_at`)
SELECT 'Packaging & Supplies', 'Kebutuhan packaging kantor', 'image', NULL, 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=1200&q=80', 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=600&q=80', 'atk', 5, 1, 1, NOW(), 'ATK & Packaging', NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_gallery WHERE title='Packaging & Supplies' LIMIT 1);

INSERT INTO `cms_gallery` (`title`, `description`, `media_type`, `file_path`, `external_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_title`, `created_at`)
SELECT 'Design & Branding', 'Layanan design untuk media promosi', 'image', NULL, 'https://images.unsplash.com/photo-1626785774573-4b799315345d?w=1200&q=80', 'https://images.unsplash.com/photo-1626785774573-4b799315345d?w=600&q=80', 'jasa', 6, 1, 1, NOW(), 'Jasa Design Grafis', NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_gallery WHERE title='Design & Branding' LIMIT 1);

-- Video embed YouTube (contoh — ganti URL di admin)
INSERT INTO `cms_media_embeds` (`title`, `description`, `platform`, `source_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_enabled`, `created_at`)
SELECT 'Tips Mengelola ATK Kantor', 'Video inspirasi organisasi alat tulis kantor yang rapi dan efisien.', 'youtube', 'https://www.youtube.com/watch?v=5MgBikgcWnY', 'https://img.youtube.com/vi/5MgBikgcWnY/hqdefault.jpg', 'atk', 1, 1, 1, NOW(), 1, NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_media_embeds LIMIT 1);

INSERT INTO `cms_media_embeds` (`title`, `description`, `platform`, `source_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_enabled`, `created_at`)
SELECT 'Proses Digital Printing', 'Ilustrasi layanan cetak digital modern untuk dokumen & promosi.', 'youtube', 'https://www.youtube.com/watch?v=u8XQzAgMoB8', 'https://img.youtube.com/vi/u8XQzAgMoB8/hqdefault.jpg', 'fotokopi', 2, 1, 1, NOW(), 1, NOW()
FROM DUAL WHERE (SELECT COUNT(*) FROM cms_media_embeds) < 2;

INSERT INTO `cms_media_embeds` (`title`, `description`, `platform`, `source_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_enabled`, `created_at`)
SELECT 'Percetakan & Promosi Bisnis', 'Ide media promosi cetak untuk mengembangkan usaha Anda.', 'youtube', 'https://www.youtube.com/watch?v=7O0ZuBkZceQ', 'https://img.youtube.com/vi/7O0ZuBkZceQ/hqdefault.jpg', 'percetakan', 3, 1, 1, NOW(), 1, NOW()
FROM DUAL WHERE (SELECT COUNT(*) FROM cms_media_embeds) < 3;

INSERT INTO `cms_media_embeds` (`title`, `description`, `platform`, `source_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_enabled`, `created_at`)
SELECT 'Layanan Jasa Profesional', 'Contoh slot video — tambahkan link TikTok/Instagram/Facebook Anda di admin.', 'youtube', 'https://www.youtube.com/watch?v=L_LUpnjgPso', 'https://img.youtube.com/vi/L_LUpnjgPso/hqdefault.jpg', 'jasa', 4, 1, 1, NOW(), 1, NOW()
FROM DUAL WHERE (SELECT COUNT(*) FROM cms_media_embeds) < 4;

-- Link sosial media (placeholder — admin isi URL asli)
INSERT INTO `cms_social_links` (`platform`, `url`, `label`, `icon_class`, `sort_order`, `is_active`)
SELECT 'instagram', 'https://instagram.com/', 'Instagram', 'bi bi-instagram', 1, 1 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_social_links WHERE platform='instagram' LIMIT 1);

INSERT INTO `cms_social_links` (`platform`, `url`, `label`, `icon_class`, `sort_order`, `is_active`)
SELECT 'tiktok', 'https://tiktok.com/', 'TikTok', 'bi bi-tiktok', 2, 1 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_social_links WHERE platform='tiktok' LIMIT 1);

INSERT INTO `cms_social_links` (`platform`, `url`, `label`, `icon_class`, `sort_order`, `is_active`)
SELECT 'youtube', 'https://youtube.com/', 'YouTube', 'bi bi-youtube', 3, 1 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_social_links WHERE platform='youtube' LIMIT 1);

INSERT INTO `cms_social_links` (`platform`, `url`, `label`, `icon_class`, `sort_order`, `is_active`)
SELECT 'facebook', 'https://facebook.com/', 'Facebook', 'bi bi-facebook', 4, 1 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_social_links WHERE platform='facebook' LIMIT 1);

INSERT INTO `cms_social_links` (`platform`, `url`, `label`, `icon_class`, `sort_order`, `is_active`)
SELECT 'whatsapp', 'https://wa.me/', 'WhatsApp', 'bi bi-whatsapp', 5, 1 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_social_links WHERE platform='whatsapp' LIMIT 1);

-- Konten berita/informasi sample
INSERT INTO `cms_posts` (`category_id`, `post_type`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `video_type`, `is_featured`, `is_published`, `published_at`, `created_at`)
SELECT c.id, 'berita', 'Promo ATK Bulan Ini — Hemat hingga 20%', 'promo-atk-bulan-ini',
  'Dapatkan diskon alat tulis kantor pilihan untuk kebutuhan kantor dan sekolah.',
  '<p>Kami mengadakan promo spesial produk ATK: pulpen, kertas A4, map, dan perlengkapan kantor lainnya. Stok terbatas — hubungi tim kami untuk penawaran terbaik.</p>',
  'https://images.unsplash.com/photo-1586953208448-b95a79798f07?w=1200&q=80', 'none', 1, 1, NOW(), NOW()
FROM cms_categories c WHERE c.slug='alat-tulis-kantor' AND NOT EXISTS (SELECT 1 FROM cms_posts WHERE slug='promo-atk-bulan-ini') LIMIT 1;

INSERT INTO `cms_posts` (`category_id`, `post_type`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `video_type`, `video_url`, `is_featured`, `is_published`, `published_at`, `created_at`)
SELECT c.id, 'informasi', 'Layanan Fotokopi & Print 24 Jam', 'layanan-fotokopi-print',
  'Cetak dokumen, skripsi, dan file digital dengan harga transparan.',
  '<p>Layanan fotokopi hitam putih & warna, scan, print PDF, dan laminating. Cocok untuk mahasiswa, kantor, dan UMKM.</p>',
  'https://images.unsplash.com/photo-1612815159322-444f042139bb?w=1200&q=80', 'youtube', 'https://www.youtube.com/watch?v=u8XQzAgMoB8', 1, 1, NOW(), NOW()
FROM cms_categories c WHERE c.slug='fotokopi-digital' AND NOT EXISTS (SELECT 1 FROM cms_posts WHERE slug='layanan-fotokopi-print') LIMIT 1;

INSERT INTO `cms_posts` (`category_id`, `post_type`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `video_type`, `is_published`, `published_at`, `created_at`)
SELECT c.id, 'profil', 'Tentang PT Aneka Dharma', 'tentang-aneka-dharma',
  'Penyedia ATK, fotokopi, percetakan, dan jasa terpercaya.',
  '<p>PT Aneka Dharma melayani kebutuhan alat tulis kantor, fotokopi & digital printing, percetakan media promosi, serta berbagai layanan jasa. Kami mengutamakan kualitas produk, ketepatan waktu, dan kepuasan pelanggan.</p>',
  'https://images.unsplash.com/photo-1497366216548-37526070297c?w=1200&q=80', 'none', 1, NOW(), NOW()
FROM cms_categories c WHERE c.slug='company-profile' AND NOT EXISTS (SELECT 1 FROM cms_posts WHERE slug='tentang-aneka-dharma') LIMIT 1;
