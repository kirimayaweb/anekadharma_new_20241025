-- CMS Demo Presentasi — Aneka Dharma
-- Gambar: Unsplash (lisensi gratis publikasi) | Video: YouTube embed edukasi
-- Jalankan: mysql -u root anekadharma_db < cms_demo_presentation.sql

SET NAMES utf8;

UPDATE `cms_settings` SET `setting_value` = 'Aneka Dharma — ATK, Fotokopi, Percetakan & Jasa Terpercaya' WHERE `setting_key` = 'site_title';
UPDATE `cms_settings` SET `setting_value` = 'Informasi harga pasar ATK, galeri produk, berita percetakan & layanan jasa profesional' WHERE `setting_key` = 'site_tagline';
UPDATE `cms_settings` SET `setting_value` = 'PT Aneka Dharma adalah mitra terpercaya untuk kebutuhan alat tulis kantor (ATK), layanan fotokopi & digital printing, percetakan media promosi, serta berbagai jasa pendukung bisnis. Kami menyediakan informasi harga transparan, stok lengkap, dan pelayanan cepat untuk perusahaan, sekolah, instansi, dan UMKM di seluruh Indonesia.' WHERE `setting_key` = 'company_about';
UPDATE `cms_settings` SET `setting_value` = 'Jl. Contoh Raya No. 123, Jakarta' WHERE `setting_key` = 'contact_address';
UPDATE `cms_settings` SET `setting_value` = '(021) 1234-5678' WHERE `setting_key` = 'contact_phone';
UPDATE `cms_settings` SET `setting_value` = 'info@anekadharma.co.id' WHERE `setting_key` = 'contact_email';

-- ===================== GALERI FOTO (Unsplash — bebas publikasi) =====================

INSERT INTO `cms_gallery` (`title`, `description`, `media_type`, `external_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_title`, `created_at`)
SELECT 'Rak ATK Lengkap', 'Display produk alat tulis kantor di toko', 'image',
  'https://images.unsplash.com/photo-1586953208448-b95a79798f07?w=1200&q=80',
  'https://images.unsplash.com/photo-1586953208448-b95a79798f07?w=600&q=80', 'atk', 1, 1, 1, NOW(), 'Rak ATK Aneka Dharma', NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_gallery WHERE title='Rak ATK Lengkap' LIMIT 1);

INSERT INTO `cms_gallery` (`title`, `description`, `media_type`, `external_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_title`, `created_at`)
SELECT 'Pulpen & Spidol Warna', 'Aneka pulpen untuk kantor dan sekolah', 'image',
  'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=1200&q=80',
  'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&q=80', 'atk', 2, 1, 1, NOW(), 'Pulpen & Spidol', NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_gallery WHERE title='Pulpen & Spidol Warna' LIMIT 1);

INSERT INTO `cms_gallery` (`title`, `description`, `media_type`, `external_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_title`, `created_at`)
SELECT 'Notebook & Buku Catatan', 'Buku tulis dan notebook berbagai ukuran', 'image',
  'https://images.unsplash.com/photo-1518458047352-571aa397916b?w=1200&q=80',
  'https://images.unsplash.com/photo-1518458047352-571aa397916b?w=600&q=80', 'atk', 3, 1, 1, NOW(), 'Notebook ATK', NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_gallery WHERE title='Notebook & Buku Catatan' LIMIT 1);

INSERT INTO `cms_gallery` (`title`, `description`, `media_type`, `external_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_title`, `created_at`)
SELECT 'Sticky Notes & Kertas Memo', 'Post-it dan kertas memo untuk kantor', 'image',
  'https://images.unsplash.com/photo-1531346878687-a7bc9594b09b?w=1200&q=80',
  'https://images.unsplash.com/photo-1531346878687-a7bc9594b09b?w=600&q=80', 'atk', 4, 1, 1, NOW(), 'Sticky Notes', NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_gallery WHERE title='Sticky Notes & Kertas Memo' LIMIT 1);

INSERT INTO `cms_gallery` (`title`, `description`, `media_type`, `external_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_title`, `created_at`)
SELECT 'Kertas A4 & HVS', 'Stok kertas fotokopi dan print', 'image',
  'https://images.unsplash.com/photo-1588196749597-976712094ba0?w=1200&q=80',
  'https://images.unsplash.com/photo-1588196749597-976712094ba0?w=600&q=80', 'atk', 5, 1, 1, NOW(), 'Kertas A4 HVS', NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_gallery WHERE title='Kertas A4 & HVS' LIMIT 1);

INSERT INTO `cms_gallery` (`title`, `description`, `media_type`, `external_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_title`, `created_at`)
SELECT 'Meja Kerja & ATK', 'Setup meja kerja modern dengan perlengkapan', 'image',
  'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=1200&q=80',
  'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=600&q=80', 'atk', 6, 1, 1, NOW(), 'Meja Kerja ATK', NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_gallery WHERE title='Meja Kerja & ATK' LIMIT 1);

INSERT INTO `cms_gallery` (`title`, `description`, `media_type`, `external_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_title`, `created_at`)
SELECT 'Packaging & Map Dokumen', 'Map, folder, dan packaging kantor', 'image',
  'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=1200&q=80',
  'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=600&q=80', 'atk', 7, 1, 1, NOW(), 'Packaging ATK', NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_gallery WHERE title='Packaging & Map Dokumen' LIMIT 1);

INSERT INTO `cms_gallery` (`title`, `description`, `media_type`, `external_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_title`, `created_at`)
SELECT 'Mesin Fotokopi Modern', 'Layanan fotokopi dokumen cepat', 'image',
  'https://images.unsplash.com/photo-1612815159322-444f042139bb?w=1200&q=80',
  'https://images.unsplash.com/photo-1612815159322-444f042139bb?w=600&q=80', 'fotokopi', 8, 1, 1, NOW(), 'Fotokopi Digital', NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_gallery WHERE title='Mesin Fotokopi Modern' LIMIT 1);

INSERT INTO `cms_gallery` (`title`, `description`, `media_type`, `external_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_title`, `created_at`)
SELECT 'Print Dokumen Warna', 'Cetak full color untuk presentasi', 'image',
  'https://images.unsplash.com/photo-1595526116515-43697509958a?w=1200&q=80',
  'https://images.unsplash.com/photo-1595526116515-43697509958a?w=600&q=80', 'fotokopi', 9, 1, 1, NOW(), 'Print Warna', NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_gallery WHERE title='Print Dokumen Warna' LIMIT 1);

INSERT INTO `cms_gallery` (`title`, `description`, `media_type`, `external_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_title`, `created_at`)
SELECT 'Scan & Digitalisasi', 'Scan dokumen arsip ke digital', 'image',
  'https://images.unsplash.com/photo-1568992687947-868a62a9f521?w=1200&q=80',
  'https://images.unsplash.com/photo-1568992687947-868a62a9f521?w=600&q=80', 'fotokopi', 10, 1, 1, NOW(), 'Scan Dokumen', NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_gallery WHERE title='Scan & Digitalisasi' LIMIT 1);

INSERT INTO `cms_gallery` (`title`, `description`, `media_type`, `external_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_title`, `created_at`)
SELECT 'Mesin Offset Printing', 'Percetakan skala medium & besar', 'image',
  'https://images.unsplash.com/photo-1562564055-71e051d33c19?w=1200&q=80',
  'https://images.unsplash.com/photo-1562564055-71e051d33c19?w=600&q=80', 'percetakan', 11, 1, 1, NOW(), 'Offset Printing', NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_gallery WHERE title='Mesin Offset Printing' LIMIT 1);

INSERT INTO `cms_gallery` (`title`, `description`, `media_type`, `external_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_title`, `created_at`)
SELECT 'Brosur & Flyer Promosi', 'Hasil cetak media promosi', 'image',
  'https://images.unsplash.com/photo-1626785774573-4b799315345d?w=1200&q=80',
  'https://images.unsplash.com/photo-1626785774573-4b799315345d?w=600&q=80', 'percetakan', 12, 1, 1, NOW(), 'Brosur & Flyer', NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_gallery WHERE title='Brosur & Flyer Promosi' LIMIT 1);

INSERT INTO `cms_gallery` (`title`, `description`, `media_type`, `external_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_title`, `created_at`)
SELECT 'Kartu Nama & Stationery', 'Cetak kartu nama profesional', 'image',
  'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=1200&q=80',
  'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=600&q=80', 'percetakan', 13, 1, 1, NOW(), 'Kartu Nama', NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_gallery WHERE title='Kartu Nama & Stationery' LIMIT 1);

INSERT INTO `cms_gallery` (`title`, `description`, `media_type`, `external_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_title`, `created_at`)
SELECT 'Spanduk & Banner', 'Media cetak outdoor & indoor', 'image',
  'https://images.unsplash.com/photo-1557804506-669a67965ba0?w=1200&q=80',
  'https://images.unsplash.com/photo-1557804506-669a67965ba0?w=600&q=80', 'percetakan', 14, 1, 1, NOW(), 'Banner Promosi', NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_gallery WHERE title='Spanduk & Banner' LIMIT 1);

INSERT INTO `cms_gallery` (`title`, `description`, `media_type`, `external_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_title`, `created_at`)
SELECT 'Kantor Modern', 'Lingkungan kerja profesional', 'image',
  'https://images.unsplash.com/photo-1497366216548-37526070297c?w=1200&q=80',
  'https://images.unsplash.com/photo-1497366216548-37526070297c?w=600&q=80', 'jasa', 15, 1, 1, NOW(), 'Solusi Kantor', NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_gallery WHERE title='Kantor Modern' LIMIT 1);

INSERT INTO `cms_gallery` (`title`, `description`, `media_type`, `external_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_title`, `created_at`)
SELECT 'Rapat & Presentasi', 'Layanan jasa pendukung bisnis', 'image',
  'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=1200&q=80',
  'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=600&q=80', 'jasa', 16, 1, 1, NOW(), 'Jasa Presentasi', NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_gallery WHERE title='Rapat & Presentasi' LIMIT 1);

INSERT INTO `cms_gallery` (`title`, `description`, `media_type`, `external_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_title`, `created_at`)
SELECT 'Tim Layanan Pelanggan', 'Pelayanan ramah & profesional', 'image',
  'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=1200&q=80',
  'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=600&q=80', 'jasa', 17, 1, 1, NOW(), 'Tim Aneka Dharma', NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_gallery WHERE title='Tim Layanan Pelanggan' LIMIT 1);

INSERT INTO `cms_gallery` (`title`, `description`, `media_type`, `external_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_title`, `created_at`)
SELECT 'Gudang Stok ATK', 'Persediaan barang siap kirim', 'image',
  'https://images.unsplash.com/photo-1553413077-190dd305871c?w=1200&q=80',
  'https://images.unsplash.com/photo-1553413077-190dd305871c?w=600&q=80', 'atk', 18, 1, 1, NOW(), 'Gudang ATK', NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_gallery WHERE title='Gudang Stok ATK' LIMIT 1);

-- ===================== VIDEO EMBED (YouTube edukasi — industri ATK & percetakan) =====================

INSERT INTO `cms_media_embeds` (`title`, `description`, `platform`, `source_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_enabled`, `created_at`)
SELECT 'Cara Kerja Mesin Offset Printing', 'Video edukasi proses percetakan offset — industri grafika.', 'youtube',
  'https://www.youtube.com/watch?v=u8XQzAgMoB8', 'https://img.youtube.com/vi/u8XQzAgMoB8/hqdefault.jpg', 'percetakan', 1, 1, 1, NOW(), 1, NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_media_embeds WHERE title LIKE 'Cara Kerja Mesin Offset%' LIMIT 1);

INSERT INTO `cms_media_embeds` (`title`, `description`, `platform`, `source_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_enabled`, `created_at`)
SELECT 'Digital Printing vs Offset — Perbedaan & Kapan Digunakan', 'Informasi teknologi cetak digital dan offset untuk kebutuhan bisnis.', 'youtube',
  'https://www.youtube.com/watch?v=dUWKuf9L9xk', 'https://img.youtube.com/vi/dUWKuf9L9xk/hqdefault.jpg', 'percetakan', 2, 1, 1, NOW(), 1, NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_media_embeds WHERE title LIKE 'Digital Printing vs Offset%' LIMIT 1);

INSERT INTO `cms_media_embeds` (`title`, `description`, `platform`, `source_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_enabled`, `created_at`)
SELECT 'Proses Produksi Kertas — Industri Pulpa & Paper', 'Memahami rantai pasok kertas ATK dari hulu ke hilir.', 'youtube',
  'https://www.youtube.com/watch?v=llK7cN8Mpg0', 'https://img.youtube.com/vi/llK7cN8Mpg0/hqdefault.jpg', 'atk', 3, 1, 1, NOW(), 1, NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_media_embeds WHERE title LIKE 'Proses Produksi Kertas%' LIMIT 1);

INSERT INTO `cms_media_embeds` (`title`, `description`, `platform`, `source_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_enabled`, `created_at`)
SELECT 'Organisasi Alat Tulis Kantor yang Efisien', 'Tips tata letak dan manajemen ATK di kantor modern.', 'youtube',
  'https://www.youtube.com/watch?v=5MgBikgcWnY', 'https://img.youtube.com/vi/5MgBikgcWnY/hqdefault.jpg', 'atk', 4, 1, 1, NOW(), 1, NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_media_embeds WHERE title LIKE 'Organisasi Alat Tulis%' LIMIT 1);

INSERT INTO `cms_media_embeds` (`title`, `description`, `platform`, `source_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_enabled`, `created_at`)
SELECT 'Screen Printing — Teknik Sablon untuk Media Promosi', 'Teknik cetak sablon untuk kaos, spanduk, dan merchandise.', 'youtube',
  'https://www.youtube.com/watch?v=e2F1-aPGnGc', 'https://img.youtube.com/vi/e2F1-aPGnGc/hqdefault.jpg', 'percetakan', 5, 1, 1, NOW(), 1, NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_media_embeds WHERE title LIKE 'Screen Printing%' LIMIT 1);

INSERT INTO `cms_media_embeds` (`title`, `description`, `platform`, `source_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_enabled`, `created_at`)
SELECT 'Desain Grafis untuk Media Cetak', 'Dasar desain brosur, flyer, dan kartu nama yang efektif.', 'youtube',
  'https://www.youtube.com/watch?v=7O0ZuBkZceQ', 'https://img.youtube.com/vi/7O0ZuBkZceQ/hqdefault.jpg', 'jasa', 6, 1, 1, NOW(), 1, NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_media_embeds WHERE title LIKE 'Desain Grafis untuk Media%' LIMIT 1);

INSERT INTO `cms_media_embeds` (`title`, `description`, `platform`, `source_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_enabled`, `created_at`)
SELECT 'Tren Industri Percetakan 2025', 'Perkembangan terkini teknologi dan tren dunia percetakan.', 'youtube',
  'https://www.youtube.com/watch?v=L_LUpnjgPso', 'https://img.youtube.com/vi/L_LUpnjgPso/hqdefault.jpg', 'percetakan', 7, 1, 1, NOW(), 1, NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_media_embeds WHERE title LIKE 'Tren Industri Percetakan%' LIMIT 1);

INSERT INTO `cms_media_embeds` (`title`, `description`, `platform`, `source_url`, `thumbnail_url`, `category`, `sort_order`, `is_active`, `is_published`, `published_at`, `share_enabled`, `created_at`)
SELECT 'Memilih ATK Berkualitas untuk Perusahaan', 'Panduan procurement ATK untuk efisiensi biaya kantor.', 'youtube',
  'https://www.youtube.com/watch?v=G3JMSGDIzrI', 'https://img.youtube.com/vi/G3JMSGDIzrI/hqdefault.jpg', 'atk', 8, 1, 1, NOW(), 1, NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM cms_media_embeds WHERE title LIKE 'Memilih ATK Berkualitas%' LIMIT 1);

-- ===================== BERITA — Harga Pasar ATK & Harga Pabrik =====================

INSERT INTO `cms_posts` (`category_id`, `post_type`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `video_type`, `tags`, `is_featured`, `is_published`, `published_at`, `created_at`)
SELECT c.id, 'berita', 'Update Harga Pasar ATK Juni 2025 — Kertas, Pulpen & Penjepit',
  'update-harga-pasar-atk-juni-2025',
  'Ringkasan harga eceran pasar alat tulis kantor terbaru untuk kebutuhan kantor dan sekolah.',
  '<p>Berikut <strong>estimasi harga pasar eceran</strong> produk ATK populer (harga dapat berubah sesuai wilayah dan volume pembelian):</p>
<table class="table table-bordered"><thead><tr><th>Produk</th><th>Spesifikasi</th><th>Harga Ecer Pasar</th></tr></thead><tbody>
<tr><td>Kertas HVS A4</td><td>70 gsm, 1 rim (500 lbr)</td><td>Rp 42.000 – Rp 52.000</td></tr>
<tr><td>Pulpen Standard</td><td>1 pcs</td><td>Rp 2.500 – Rp 5.000</td></tr>
<tr><td>Pulpen Gel Premium</td><td>1 pcs</td><td>Rp 6.000 – Rp 12.000</td></tr>
<tr><td>Penghapus & Tip-Ex</td><td>1 pcs</td><td>Rp 3.000 – Rp 8.000</td></tr>
<tr><td>Stapler Mini</td><td>1 unit</td><td>Rp 15.000 – Rp 35.000</td></tr>
<tr><td>Isi Staples No.10</td><td>1 box</td><td>Rp 4.000 – Rp 7.000</td></tr>
<tr><td>Map Plastik</td><td>1 pcs</td><td>Rp 2.000 – Rp 5.000</td></tr>
<tr><td>Binder A4</td><td>1 unit</td><td>Rp 18.000 – Rp 45.000</td></tr>
</tbody></table>
<p><em>Catatan: Harga di atas bersifat informasi pasar umum. Hubungi Aneka Dharma untuk penawaran grosir & kontrak korporat.</em></p>',
  'https://images.unsplash.com/photo-1586953208448-b95a79798f07?w=1200&q=80', 'none', 'harga ATK, pasar, kertas A4, pulpen', 1, 1, NOW(), NOW()
FROM cms_categories c WHERE c.slug='alat-tulis-kantor' AND NOT EXISTS (SELECT 1 FROM cms_posts WHERE slug='update-harga-pasar-atk-juni-2025') LIMIT 1;

INSERT INTO `cms_posts` (`category_id`, `post_type`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `video_type`, `tags`, `is_featured`, `is_published`, `published_at`, `created_at`)
SELECT c.id, 'berita', 'Daftar Harga Grosir Pabrik ATK — Pulpen, Pensil & Penghapus',
  'harga-grosir-pabrik-pulpen-pensil',
  'Referensi harga jual pabrik (FOB/industry) untuk produk alat tulis — minimal order grosir.',
  '<p>Informasi <strong>harga jual dari pabrik/pabrikasi ATK</strong> (tier grosir, belum termasuk ongkir & PPN):</p>
<table class="table table-bordered"><thead><tr><th>Produk</th><th>MOQ</th><th>Harga Pabrik/Dus</th></tr></thead><tbody>
<tr><td>Pulpen Ballpoint Economy</td><td>1 dus (12 lusin)</td><td>Rp 85.000 – Rp 120.000/dus</td></tr>
<tr><td>Pulpen Gel 0.5mm</td><td>1 dus (10 lusin)</td><td>Rp 180.000 – Rp 250.000/dus</td></tr>
<tr><td>Pensil HB</td><td>1 gross (144 pcs)</td><td>Rp 95.000 – Rp 140.000/gross</td></tr>
<tr><td>Penghapus Putih</td><td>1 gross</td><td>Rp 55.000 – Rp 80.000/gross</td></tr>
<tr><td>Spidol Whiteboard</td><td>1 lusin</td><td>Rp 72.000 – Rp 110.000/lusin</td></tr>
<tr><td>Highlighter</td><td>1 lusin</td><td>Rp 48.000 – Rp 75.000/lusin</td></tr>
</tbody></table>
<p>Aneka Dharma bekerja sama dengan berbagai pabrik ATK nasional untuk menyediakan harga kompetitif bagi distributor, sekolah, dan korporat.</p>',
  'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=1200&q=80', 'none', 'harga pabrik, grosir, pulpen, pensil', 1, 1, DATE_SUB(NOW(), INTERVAL 1 DAY), NOW()
FROM cms_categories c WHERE c.slug='alat-tulis-kantor' AND NOT EXISTS (SELECT 1 FROM cms_posts WHERE slug='harga-grosir-pabrik-pulpen-pensil') LIMIT 1;

INSERT INTO `cms_posts` (`category_id`, `post_type`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `video_type`, `tags`, `is_featured`, `is_published`, `published_at`, `created_at`)
SELECT c.id, 'berita', 'Harga Kertas A4 & HVS dari Pabrik Kertas — Update Terbaru',
  'harga-kertas-a4-pabrik-terbaru',
  'Perbandingan harga kertas HVS 70/80 gsm dari pabrik ke distributor dan ecer.',
  '<p><strong>Harga kertas HVS</strong> dipengaruhi fluktuasi pulp dunia, kurs, dan musim sekolah:</p>
<table class="table table-bordered"><thead><tr><th>Jenis Kertas</th><th>Harga Pabrik/Rim</th><th>Harga Distributor</th><th>Harga Ecer</th></tr></thead><tbody>
<tr><td>HVS A4 70 gsm</td><td>Rp 28.000 – Rp 32.000</td><td>Rp 34.000 – Rp 38.000</td><td>Rp 42.000 – Rp 52.000</td></tr>
<tr><td>HVS A4 80 gsm</td><td>Rp 32.000 – Rp 37.000</td><td>Rp 38.000 – Rp 43.000</td><td>Rp 48.000 – Rp 58.000</td></tr>
<tr><td>Folio/F4 70 gsm</td><td>Rp 30.000 – Rp 35.000</td><td>Rp 36.000 – Rp 42.000</td><td>Rp 45.000 – Rp 55.000</td></tr>
<tr><td>Art Paper 150 gsm</td><td>Rp 55.000 – Rp 70.000</td><td>Rp 65.000 – Rp 82.000</td><td>Rp 80.000+</td></tr>
</tbody></table>
<p>Untuk pembelian rim partai (50+ rim), silakan minta quotation resmi ke tim sales Aneka Dharma.</p>',
  'https://images.unsplash.com/photo-1588196749597-976712094ba0?w=1200&q=80', 'none', 'kertas A4, HVS, harga pabrik', 1, 1, DATE_SUB(NOW(), INTERVAL 2 DAY), NOW()
FROM cms_categories c WHERE c.slug='alat-tulis-kantor' AND NOT EXISTS (SELECT 1 FROM cms_posts WHERE slug='harga-kertas-a4-pabrik-terbaru') LIMIT 1;

INSERT INTO `cms_posts` (`category_id`, `post_type`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `video_type`, `tags`, `is_featured`, `is_published`, `published_at`, `created_at`)
SELECT c.id, 'berita', 'Perbandingan Harga ATK Merk Lokal vs Import — Mana Lebih Hemat?',
  'perbandingan-harga-atk-lokal-import',
  'Analisis harga dan kualitas produk ATK lokal dan import untuk pengadaan kantor.',
  '<p>Perusahaan sering dihadapkan pilihan antara ATK merk lokal dan import. Berikut perbandingan umum:</p>
<ul><li><strong>Lokal:</strong> Harga 15–30% lebih murah, stok cepat, after-sales lokal.</li><li><strong>Import:</strong> Variasi desain lebih banyak, cocok untuk segment premium.</li></ul>
<p>Untuk kebutuhan korporat 100+ karyawan, pengadaan ATK lokal dengan kontrak tahunan dapat menghemat anggaran hingga 20% dibanding pembelian ecer berkala.</p>',
  'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=1200&q=80', 'none', 'ATK lokal, import, perbandingan harga', 0, 1, DATE_SUB(NOW(), INTERVAL 3 DAY), NOW()
FROM cms_categories c WHERE c.slug='berita' AND NOT EXISTS (SELECT 1 FROM cms_posts WHERE slug='perbandingan-harga-atk-lokal-import') LIMIT 1;

INSERT INTO `cms_posts` (`category_id`, `post_type`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `video_type`, `video_url`, `tags`, `is_featured`, `is_published`, `published_at`, `created_at`)
SELECT c.id, 'berita', 'Harga Jasa Fotokopi & Print per Lembar — Standar Pasar 2025',
  'harga-jasa-fotokopi-print-2025',
  'Tarif fotokopi hitam putih, warna, dan print digital per lembar.',
  '<table class="table table-bordered"><thead><tr><th>Layanan</th><th>Ukuran</th><th>Harga/Lbr</th></tr></thead><tbody>
<tr><td>Fotokopi Hitam Putih</td><td>A4</td><td>Rp 200 – Rp 400</td></tr>
<tr><td>Fotokopi Warna</td><td>A4</td><td>Rp 1.000 – Rp 2.500</td></tr>
<tr><td>Print Digital BW</td><td>A4</td><td>Rp 300 – Rp 600</td></tr>
<tr><td>Print Digital Warna</td><td>A4</td><td>Rp 1.500 – Rp 3.500</td></tr>
<tr><td>Laminating</td><td>A4</td><td>Rp 3.000 – Rp 7.000</td></tr>
<tr><td>Scan ke PDF</td><td>A4</td><td>Rp 500 – Rp 1.500</td></tr>
</tbody></table>
<p>Diskon volume tersedia untuk proyek 500+ lembar. Lihat video proses digital printing di bawah.</p>',
  'https://images.unsplash.com/photo-1612815159322-444f042139bb?w=1200&q=80', 'youtube', 'https://www.youtube.com/watch?v=dUWKuf9L9xk', 'fotokopi, print, harga', 1, 1, DATE_SUB(NOW(), INTERVAL 4 DAY), NOW()
FROM cms_categories c WHERE c.slug='fotokopi-digital' AND NOT EXISTS (SELECT 1 FROM cms_posts WHERE slug='harga-jasa-fotokopi-print-2025') LIMIT 1;

INSERT INTO `cms_posts` (`category_id`, `post_type`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `video_type`, `tags`, `is_featured`, `is_published`, `published_at`, `created_at`)
SELECT c.id, 'berita', 'Harga Cetak Brosur, Banner & Kartu Nama — Tarif Percetakan',
  'harga-cetak-brosur-banner-kartu-nama',
  'Referensi harga jasa percetakan media promosi untuk UMKM dan korporat.',
  '<table class="table table-bordered"><thead><tr><th>Produk</th><th>Qty Min</th><th>Harga Estimasi</th></tr></thead><tbody>
<tr><td>Brosur A5 2 sisi</td><td>100 lbr</td><td>Rp 250.000 – Rp 450.000</td></tr>
<tr><td>Flyer A5 1 sisi</td><td>500 lbr</td><td>Rp 750.000 – Rp 1.200.000</td></tr>
<tr><td>Kartu Nama 2 sisi</td><td>2 box (200 lbr)</td><td>Rp 80.000 – Rp 150.000</td></tr>
<tr><td>Spanduk 3×1 m</td><td>1 pcs</td><td>Rp 35.000 – Rp 90.000</td></tr>
<tr><td>Stiker vinyl</td><td>100 pcs</td><td>Rp 150.000 – Rp 350.000</td></tr>
</tbody></table>
<p>Desain grafis dapat ditambahkan sebagai layanan jasa. Konsultasi gratis dengan tim Aneka Dharma.</p>',
  'https://images.unsplash.com/photo-1626785774573-4b799315345d?w=1200&q=80', 'none', 'percetakan, brosur, banner, kartu nama', 1, 1, DATE_SUB(NOW(), INTERVAL 5 DAY), NOW()
FROM cms_categories c WHERE c.slug='percetakan' AND NOT EXISTS (SELECT 1 FROM cms_posts WHERE slug='harga-cetak-brosur-banner-kartu-nama') LIMIT 1;

INSERT INTO `cms_posts` (`category_id`, `post_type`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `video_type`, `tags`, `is_featured`, `is_published`, `published_at`, `created_at`)
SELECT c.id, 'berita', 'Promo ATK Paket Kantor — Hemat 15% untuk Pembelian Bundling',
  'promo-atk-paket-kantor-bundling',
  'Paket lengkap ATK starter untuk kantor baru: pulpen, kertas, stapler, map, dan binder.',
  '<p>Paket <strong>Office Starter</strong> berisi: 5 rim kertas A4, 2 dus pulpen, 3 stapler, 10 pack map, 5 binder. Harga paket mulai <strong>Rp 1.850.000</strong> (hemat ~15% vs beli satuan).</p><p>Promo berlaku untuk 50 paket pertama. Hubungi sales untuk pre-order.</p>',
  'https://images.unsplash.com/photo-1553413077-190dd305871c?w=1200&q=80', 'none', 'promo, paket kantor, ATK', 1, 1, DATE_SUB(NOW(), INTERVAL 6 DAY), NOW()
FROM cms_categories c WHERE c.slug='alat-tulis-kantor' AND NOT EXISTS (SELECT 1 FROM cms_posts WHERE slug='promo-atk-paket-kantor-bundling') LIMIT 1;

INSERT INTO `cms_posts` (`category_id`, `post_type`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `video_type`, `video_url`, `tags`, `is_featured`, `is_published`, `published_at`, `created_at`)
SELECT c.id, 'berita', 'Industri Percetakan Indonesia — Tren & Prospek 2025',
  'industri-percetakan-indonesia-tren-2025',
  'Gambaran perkembangan industri grafika dan percetakan di Indonesia.',
  '<p>Industri percetakan Indonesia terus beradaptasi dengan digital printing, packaging premium, dan sustainable paper. UMKM percetakan tumbuh 8–12% per tahun didorong e-commerce dan kebutuhan media promosi lokal.</p>
<p>Aneka Dharma mengikuti perkembangan ini dengan investasi mesin digital print dan layanan design terintegrasi.</p>',
  'https://images.unsplash.com/photo-1562564055-71e051d33c19?w=1200&q=80', 'youtube', 'https://www.youtube.com/watch?v=u8XQzAgMoB8', 'percetakan, industri, tren 2025', 1, 1, DATE_SUB(NOW(), INTERVAL 7 DAY), NOW()
FROM cms_categories c WHERE c.slug='percetakan' AND NOT EXISTS (SELECT 1 FROM cms_posts WHERE slug='industri-percetakan-indonesia-tren-2025') LIMIT 1;

-- ===================== INFORMASI UMUM =====================

INSERT INTO `cms_posts` (`category_id`, `post_type`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `video_type`, `tags`, `is_featured`, `is_published`, `published_at`, `created_at`)
SELECT c.id, 'informasi', 'Panduan Memilih ATK Berkualitas untuk Kantor',
  'panduan-memilih-atk-kantor',
  'Tips memilih pulpen, kertas, dan perlengkapan kantor yang tahan lama.',
  '<p>Pilih ATK berdasarkan: (1) ergonomi — pulpen nyaman dipakai 8 jam; (2) sertifikasi kertas — gramatur sesuai kebutuhan print; (3) garansi distributor resmi; (4) efisiensi pembelian grosir.</p>',
  'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=1200&q=80', 'none', 'panduan, ATK, kantor', 0, 1, NOW(), NOW()
FROM cms_categories c WHERE c.slug='informasi-umum' AND NOT EXISTS (SELECT 1 FROM cms_posts WHERE slug='panduan-memilih-atk-kantor') LIMIT 1;

INSERT INTO `cms_posts` (`category_id`, `post_type`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `video_type`, `tags`, `is_featured`, `is_published`, `published_at`, `created_at`)
SELECT c.id, 'informasi', 'Jenis-Jenis Kertas untuk Percetakan — Art Paper, HVS, Matte',
  'jenis-kertas-untuk-percetakan',
  'Kenali perbedaan jenis kertas sebelum cetak brosur atau buku.',
  '<ul><li><strong>HVS</strong> — dokumen, handout, fotokopi</li><li><strong>Art Paper</strong> — brosur premium, katalog</li><li><strong>Matte/Glossy</strong> — foto, poster</li><li><strong>Concorde/Kraft</strong> — packaging, paper bag</li></ul>',
  'https://images.unsplash.com/photo-1588196749597-976712094ba0?w=1200&q=80', 'none', 'kertas, percetakan, art paper', 0, 1, DATE_SUB(NOW(), INTERVAL 1 DAY), NOW()
FROM cms_categories c WHERE c.slug='informasi-umum' AND NOT EXISTS (SELECT 1 FROM cms_posts WHERE slug='jenis-kertas-untuk-percetakan') LIMIT 1;

INSERT INTO `cms_posts` (`category_id`, `post_type`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `video_type`, `tags`, `is_featured`, `is_published`, `published_at`, `created_at`)
SELECT c.id, 'informasi', 'Fotokopi vs Digital Print — Kapan Pakai yang Mana?',
  'fotokopi-vs-digital-print',
  'Perbedaan teknologi fotokopi dan digital printing untuk dokumen Anda.',
  '<p><strong>Fotokopi</strong> cocok untuk reproduksi cepat dokumen existing. <strong>Digital print</strong> ideal untuk file PDF/design dengan kualitas konsisten dan finishing laminating.</p>',
  'https://images.unsplash.com/photo-1595526116515-43697509958a?w=1200&q=80', 'none', 'fotokopi, digital print', 0, 1, DATE_SUB(NOW(), INTERVAL 2 DAY), NOW()
FROM cms_categories c WHERE c.slug='informasi-umum' AND NOT EXISTS (SELECT 1 FROM cms_posts WHERE slug='fotokopi-vs-digital-print') LIMIT 1;

INSERT INTO `cms_posts` (`category_id`, `post_type`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `video_type`, `tags`, `is_featured`, `is_published`, `published_at`, `created_at`)
SELECT c.id, 'informasi', 'Layanan Jasa Penjilidan, Cutting & Finishing Dokumen',
  'layanan-jasa-penjilidan-cutting',
  'Overview layanan jasa finishing untuk skripsi, laporan, dan proposal.',
  '<p>Layanan jasa kami meliputi: soft/hard cover binding, spiral, cutting custom, laminating, dan pengiriman dokumen. Turnaround 1–3 hari kerja.</p>',
  'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=1200&q=80', 'none', 'jasa, penjilidan, finishing', 0, 1, DATE_SUB(NOW(), INTERVAL 3 DAY), NOW()
FROM cms_categories c WHERE c.slug='jasa-layanan' AND NOT EXISTS (SELECT 1 FROM cms_posts WHERE slug='layanan-jasa-penjilidan-cutting') LIMIT 1;

INSERT INTO `cms_posts` (`category_id`, `post_type`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `video_type`, `tags`, `is_featured`, `is_published`, `published_at`, `created_at`)
SELECT c.id, 'informasi', 'Cara Pemesanan ATK Grosir ke Aneka Dharma',
  'cara-pemesanan-atk-grosir',
  'Langkah mudah order ATK partai besar untuk sekolah dan perusahaan.',
  '<ol><li>Hubungi sales via WhatsApp/email</li><li>Kirim list kebutuhan (Excel/PDF)</li><li>Terima quotation 1×24 jam</li><li>Konfirmasi PO & jadwal kirim</li><li>Delivery & invoice</li></ol>',
  'https://images.unsplash.com/photo-1553413077-190dd305871c?w=1200&q=80', 'none', 'pemesanan, grosir, ATK', 0, 1, DATE_SUB(NOW(), INTERVAL 4 DAY), NOW()
FROM cms_categories c WHERE c.slug='informasi-umum' AND NOT EXISTS (SELECT 1 FROM cms_posts WHERE slug='cara-pemesanan-atk-grosir') LIMIT 1;

INSERT INTO `cms_posts` (`category_id`, `post_type`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `video_type`, `video_url`, `tags`, `is_featured`, `is_published`, `published_at`, `created_at`)
SELECT c.id, 'informasi', 'Teknologi Digital Printing Terkini — UV, DTF & Large Format',
  'teknologi-digital-printing-terkini',
  'Informasi teknologi cetak modern untuk media promosi.',
  '<p>UV print untuk rigid material, DTF untuk textile, dan large format untuk banner indoor/outdoor — semua tersedia sebagai layanan jasa Aneka Dharma.</p>',
  'https://images.unsplash.com/photo-1568992687947-868a62a9f521?w=1200&q=80', 'youtube', 'https://www.youtube.com/watch?v=dUWKuf9L9xk', 'digital printing, UV, teknologi', 0, 1, DATE_SUB(NOW(), INTERVAL 5 DAY), NOW()
FROM cms_categories c WHERE c.slug='informasi-umum' AND NOT EXISTS (SELECT 1 FROM cms_posts WHERE slug='teknologi-digital-printing-terkini') LIMIT 1;

-- ===================== PROFIL PERUSAHAAN =====================

INSERT INTO `cms_posts` (`category_id`, `post_type`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `video_type`, `tags`, `is_featured`, `is_published`, `published_at`, `created_at`)
SELECT c.id, 'profil', 'Profil PT Aneka Dharma — Mitra ATK & Percetakan Terpercaya',
  'profil-pt-aneka-dharma',
  'Sejarah, visi, dan komitmen pelayanan Aneka Dharma.',
  '<p><strong>PT Aneka Dharma</strong> didirikan untuk melayani kebutuhan alat tulis kantor, fotokopi, percetakan, dan jasa pendukung bisnis. Dengan jaringan supplier pabrik ATK nasional dan mesin cetak modern, kami hadir sebagai one-stop solution untuk korporat, instansi pendidikan, dan UMKM.</p>
<p><strong>Visi:</strong> Menjadi distributor ATK & jasa percetakan terdepan yang memberikan nilai terbaik.<br>
<strong>Misi:</strong> Harga transparan, stok reliable, pelayanan cepat, dan informasi pasar yang akurat.</p>',
  'https://images.unsplash.com/photo-1497366216548-37526070297c?w=1200&q=80', 'none', 'profil, aneka dharma', 1, 1, NOW(), NOW()
FROM cms_categories c WHERE c.slug='company-profile' AND NOT EXISTS (SELECT 1 FROM cms_posts WHERE slug='profil-pt-aneka-dharma') LIMIT 1;

INSERT INTO `cms_posts` (`category_id`, `post_type`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `video_type`, `tags`, `is_featured`, `is_published`, `published_at`, `created_at`)
SELECT c.id, 'profil', 'Visi & Misi — Komitmen Pelayanan Pelanggan',
  'visi-misi-aneka-dharma',
  'Nilai-nilai inti yang menjadi fondasi pelayanan kami.',
  '<p>Kami berkomitmen pada: (1) Integritas harga; (2) Kualitas produk terjamin; (3) Respons cepat; (4) Solusi customized untuk setiap klien.</p>',
  'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=1200&q=80', 'none', 'visi, misi', 0, 1, NOW(), NOW()
FROM cms_categories c WHERE c.slug='company-profile' AND NOT EXISTS (SELECT 1 FROM cms_posts WHERE slug='visi-misi-aneka-dharma') LIMIT 1;
