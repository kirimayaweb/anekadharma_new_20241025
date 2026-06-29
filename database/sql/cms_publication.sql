-- CMS Publikasi Aneka Dharma
-- Jalankan sekali di database anekadharma_db

SET NAMES utf8;

CREATE TABLE IF NOT EXISTS `cms_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_cms_settings_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `cms_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(150) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(80) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_cms_categories_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `cms_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(32) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `post_type` enum('berita','informasi','profil','halaman') NOT NULL DEFAULT 'berita',
  `title` varchar(300) NOT NULL,
  `slug` varchar(300) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `featured_image` varchar(500) DEFAULT NULL,
  `video_url` varchar(500) DEFAULT NULL,
  `video_type` enum('youtube','tiktok','instagram','embed','none') NOT NULL DEFAULT 'none',
  `meta_title` varchar(300) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `tags` varchar(500) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` datetime DEFAULT NULL,
  `view_count` int(11) NOT NULL DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_cms_posts_slug` (`slug`),
  KEY `idx_cms_posts_category` (`category_id`),
  KEY `idx_cms_posts_type` (`post_type`),
  KEY `idx_cms_posts_published` (`is_published`,`published_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `cms_sliders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(300) DEFAULT NULL,
  `subtitle` text DEFAULT NULL,
  `image` varchar(500) DEFAULT NULL,
  `link_url` varchar(500) DEFAULT NULL,
  `button_text` varchar(100) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `cms_gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(300) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `media_type` enum('image','video','youtube') NOT NULL DEFAULT 'image',
  `file_path` varchar(500) DEFAULT NULL,
  `external_url` varchar(500) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `cms_social_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `platform` enum('instagram','tiktok','youtube','facebook','twitter','whatsapp','website','linkedin') NOT NULL,
  `url` varchar(500) NOT NULL,
  `label` varchar(100) DEFAULT NULL,
  `icon_class` varchar(100) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `cms_admin_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `id_user_level` int(11) DEFAULT NULL,
  `granted_by` int(11) DEFAULT NULL,
  `granted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_cms_admin_user` (`id_user`),
  UNIQUE KEY `uk_cms_admin_level` (`id_user_level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Pengaturan default
INSERT IGNORE INTO `cms_settings` (`setting_key`, `setting_value`, `updated_at`) VALUES
('site_title', 'Aneka Dharma - Informasi & Publikasi', NOW()),
('site_tagline', 'Profil perusahaan, berita, dan informasi terkini', NOW()),
('company_name', 'PT Aneka Dharma', NOW()),
('company_about', 'Selamat datang di halaman informasi resmi kami. Temukan berita, profil perusahaan, dan update terbaru.', NOW()),
('contact_email', '', NOW()),
('contact_phone', '', NOW()),
('contact_address', '', NOW()),
('primary_color', '#0d6efd', NOW()),
('secondary_color', '#6610f2', NOW()),
('allowed_admin_levels', '1,2,99', NOW());

-- Kategori default
INSERT IGNORE INTO `cms_categories` (`slug`, `name`, `description`, `icon`, `sort_order`, `is_active`, `created_at`) VALUES
('berita', 'Berita', 'Berita dan update terbaru', 'fa-newspaper-o', 1, 1, NOW()),
('informasi-umum', 'Informasi Umum', 'Informasi penting untuk publik', 'fa-info-circle', 2, 1, NOW()),
('company-profile', 'Company Profile', 'Profil dan tentang perusahaan', 'fa-building-o', 3, 1, NOW());

-- Menu admin (tbl_menu) — sesuaikan jika bentrok
INSERT INTO `tbl_menu` (`title`, `url`, `icon`, `is_main_menu`, `is_aktif`)
SELECT 'CMS Publikasi', 'cms_admin', 'fa fa-newspaper-o', 0, 'y'
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `tbl_menu` WHERE LOWER(`url`) = 'cms_admin' LIMIT 1);

-- Hak akses admin level 1, 2, 99
INSERT INTO `tbl_hak_akses` (`id_menu`, `id_user_level`)
SELECT m.id_menu, ul.id_user_level
FROM `tbl_menu` m
CROSS JOIN (SELECT 1 AS id_user_level UNION SELECT 2 UNION SELECT 99) ul
WHERE LOWER(m.url) = 'cms_admin'
AND NOT EXISTS (
  SELECT 1 FROM `tbl_hak_akses` ha
  WHERE ha.id_menu = m.id_menu AND ha.id_user_level = ul.id_user_level
);
