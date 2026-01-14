-- Water Prime Su Arıtma - Veritabanı Şeması
-- Oluşturma tarihi: 2026

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+03:00";

CREATE DATABASE IF NOT EXISTS `waterprime` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci;
USE `waterprime`;

-- --------------------------------------------------------
-- Tablo: users (Admin kullanıcıları)
-- --------------------------------------------------------
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- Varsayılan admin kullanıcısı (şifre: admin123 - değiştirmeyi unutmayın!)
INSERT INTO `users` (`username`, `email`, `password`, `full_name`) VALUES
('admin', 'iletisim@waterprimesuaritma.com', '$2y$10$YMpY1O6DrIU.9P7jNCHIhO8qnFbK2hL4rBZC8sIe.cFhVRZYaLiMC', 'Admin');

-- --------------------------------------------------------
-- Tablo: categories (Ürün kategorileri)
-- --------------------------------------------------------
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- Varsayılan kategoriler
INSERT INTO `categories` (`name`, `slug`, `description`, `sort_order`) VALUES
('Tezgah Altı Su Arıtma', 'tezgah-alti-su-aritma', 'Mutfak tezgahı altına monte edilen su arıtma cihazları', 1),
('Tezgah Üstü Su Arıtma', 'tezgah-ustu-su-aritma', 'Tezgah üzerinde kullanılan kompakt su arıtma cihazları', 2),
('Pompalı Sistemler', 'pompali-sistemler', 'Düşük su basıncı için pompalı su arıtma sistemleri', 3),
('Filtreler ve Yedek Parçalar', 'filtreler-yedek-parcalar', 'Su arıtma cihazları için filtreler ve yedek parçalar', 4);

-- --------------------------------------------------------
-- Tablo: products (Ürünler)
-- --------------------------------------------------------
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `short_description` text DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `features` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `gallery` text DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `view_count` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `category_id` (`category_id`),
  KEY `is_featured` (`is_featured`),
  KEY `is_active` (`is_active`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- Varsayılan ürünler (LG Su Arıtma Cihazları)
INSERT INTO `products` (`category_id`, `name`, `slug`, `short_description`, `description`, `features`, `is_featured`, `sort_order`) VALUES
(1, 'LG Ultra Alkali Su Arıtma Cihazı', 'lg-ultra-alkali-su-aritma-cihazi', '12 aşamalı filtrasyon sistemi ile alkali su üretimi. pH 8.5+ değerinde sağlıklı içme suyu.', '<p>LG Ultra Alkali Su Arıtma Cihazı, 12 aşamalı gelişmiş filtrasyon sistemi ile evinizde en saf ve sağlıklı suyu üretir.</p><p><strong>Alkali Su Faydaları:</strong></p><ul><li>Vücudun asit-baz dengesini korur</li><li>Antioksidan özelliği ile hücre yenilenmesine destek</li><li>Sindirimi kolaylaştırır</li><li>Enerji seviyesini artırır</li></ul><p>NSF, FDA ve TSE standartlarında üretilmiş yüksek kaliteli bileşenler kullanılmaktadır.</p>', '12 Aşamalı Filtrasyon|Alkali Su (pH 8.5+)|%100 Hindistan Cevizi Karbon|Antibakteriyel Tank|UV Sterilizasyon|Akıllı Filtre Uyarı Sistemi|2 Yıl Garanti|Ücretsiz Montaj', 1, 1),
(1, 'LG Ultra Eco Su Arıtma Cihazı', 'lg-ultra-eco-su-aritma-cihazi', 'Ekonomik ve çevre dostu su arıtma çözümü. %70 daha az atık su üretimi.', '<p>LG Ultra Eco, çevreye duyarlı tasarımı ile rakiplerine göre %70 daha az atık su üreterek hem doğayı hem de su faturanızı korur.</p><p><strong>Eco Teknolojisi:</strong></p><ul><li>Düşük enerji tüketimi</li><li>Minimum atık su</li><li>Uzun ömürlü filtreler</li><li>Sessiz çalışma</li></ul>', '12 Aşamalı Filtrasyon|%70 Daha Az Atık Su|Enerji Tasarruflu|Sessiz Çalışma|Kompakt Tasarım|12 Ay Filtre Ömrü|2 Yıl Garanti|Ücretsiz Montaj', 1, 2),
(1, 'LG Ultra Premium Su Arıtma Cihazı', 'lg-ultra-premium-su-aritma-cihazi', 'En üst düzey filtrasyon teknolojisi. Paslanmaz çelik tank ve UV sterilizasyon.', '<p>LG Ultra Premium, su arıtma teknolojisinin en gelişmiş örneğidir. Paslanmaz çelik tankı ve çift UV sterilizasyon sistemi ile maksimum hijyen sağlar.</p><p><strong>Premium Özellikler:</strong></p><ul><li>Paslanmaz çelik su tankı</li><li>Çift UV lamba sistemi</li><li>Gümüş iyon teknolojisi</li><li>Dijital kontrol paneli</li></ul>', '14 Aşamalı Filtrasyon|Paslanmaz Çelik Tank|Çift UV Sterilizasyon|Gümüş İyon Teknolojisi|Dijital Ekran|Akıllı Sensörler|3 Yıl Garanti|Ücretsiz Montaj', 1, 3),
(2, 'LG Ultra Tezgah Üstü Su Arıtma Cihazı', 'lg-ultra-tezgah-ustu-su-aritma-cihazi', 'Kompakt tasarım, kolay kurulum. Mutfak tezgahınızda şık görünüm.', '<p>Montaj gerektirmeyen, tezgah üstünde kullanıma hazır kompakt su arıtma cihazı. Şık tasarımı ile mutfağınıza değer katar.</p><p><strong>Avantajları:</strong></p><ul><li>Kolay kurulum - sadece musluk bağlantısı</li><li>Taşınabilir tasarım</li><li>Kiracılar için ideal</li><li>Modern görünüm</li></ul>', '8 Aşamalı Filtrasyon|Kompakt Tasarım|Kolay Kurulum|Taşınabilir|LED Gösterge|6 Ay Filtre Ömrü|2 Yıl Garanti|Ücretsiz Kurulum Desteği', 1, 4),
(3, 'LG Ultra Pompalı Su Arıtma Cihazı', 'lg-ultra-pompali-su-aritma-cihazi', 'Düşük su basıncı olan bölgeler için pompalı sistem. Güçlü performans.', '<p>Düşük su basıncının olduğu bölgelerde bile mükemmel performans sunan pompalı su arıtma sistemi.</p><p><strong>Pompalı Sistem Avantajları:</strong></p><ul><li>Düşük basınçta çalışır</li><li>Hızlı su üretimi</li><li>Sessiz pompa teknolojisi</li><li>Otomatik basınç ayarı</li></ul>', '12 Aşamalı Filtrasyon|Sessiz Pompa|Otomatik Basınç Ayarı|Düşük Basınçta Çalışma|Su Kaçağı Sensörü|12 Ay Filtre Ömrü|2 Yıl Garanti|Ücretsiz Montaj', 1, 5),
(4, 'LG Su Arıtma Filtre Seti (5li)', 'lg-su-aritma-filtre-seti-5li', 'Orijinal LG filtre seti. 1 yıllık kullanım için komple set.', '<p>LG su arıtma cihazları için orijinal yedek filtre seti. Tüm LG Ultra modelleri ile uyumludur.</p><p><strong>Set İçeriği:</strong></p><ul><li>1 Adet Sediment Filtre</li><li>2 Adet Aktif Karbon Filtre</li><li>1 Adet UF Membran</li><li>1 Adet Post Karbon Filtre</li></ul>', 'Orijinal LG Filtre|5 Parça Komple Set|1 Yıllık Kullanım|Tüm LG Modellerle Uyumlu|Kolay Değişim|NSF Sertifikalı', 0, 6),
(4, 'LG RO Membran Filtre', 'lg-ro-membran-filtre', 'Orijinal LG RO membran. 75 GPD kapasiteli, yüksek verimlilik.', '<p>LG su arıtma cihazları için orijinal RO (Reverse Osmosis) membran filtre. Yüksek arıtma kapasitesi ile uzun ömürlü kullanım.</p>', '75 GPD Kapasite|Orijinal LG Membran|2-3 Yıl Ömür|%99.9 Arıtma Oranı|NSF Sertifikalı', 0, 7),
(4, 'LG Alkali ve Mineral Filtre', 'lg-alkali-mineral-filtre', 'Suya mineral ve alkali değer katan özel filtre.', '<p>Arıtılmış suya doğal mineraller ekleyerek sağlıklı ve lezzetli içme suyu sağlar. pH değerini 8.5 üzerine çıkarır.</p>', 'Alkali pH 8.5+|Doğal Mineral Ekleme|Lezzetli Su|6-12 Ay Ömür|Kolay Değişim', 0, 8);

-- --------------------------------------------------------
-- Tablo: services (Hizmetler)
-- --------------------------------------------------------
CREATE TABLE `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `short_description` text DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- Varsayılan hizmetler
INSERT INTO `services` (`name`, `slug`, `short_description`, `icon`, `sort_order`) VALUES
('Montaj', 'montaj', 'Profesyonel ekibimiz tarafından ücretsiz montaj hizmeti', 'wrench', 1),
('Bakım ve Servis', 'bakim-servis', 'Periyodik bakım ve teknik servis hizmetleri', 'cog', 2),
('Filtre Değişimi', 'filtre-degisimi', 'Orijinal filtre değişimi ve bakım hizmeti', 'refresh', 3);

-- --------------------------------------------------------
-- Tablo: blog_posts (Blog yazıları)
-- --------------------------------------------------------
CREATE TABLE `blog_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT 0,
  `published_at` datetime DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `view_count` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `author_id` (`author_id`),
  KEY `is_published` (`is_published`),
  CONSTRAINT `blog_posts_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------
-- Tablo: faq (Sık Sorulan Sorular)
-- --------------------------------------------------------
CREATE TABLE `faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(500) NOT NULL,
  `answer` text NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- Varsayılan SSS
INSERT INTO `faq` (`question`, `answer`, `sort_order`) VALUES
('Su arıtma cihazı neden gereklidir?', 'Şebeke suyu birçok zararlı madde içerebilir. Su arıtma cihazları bu maddeleri filtreleyerek saf ve sağlıklı içme suyu sağlar.', 1),
('Filtreler ne sıklıkla değiştirilmelidir?', 'Filtre değişim süresi kullanım miktarına göre değişir. Genellikle 6-12 ay arasında değişim önerilir.', 2),
('Montaj ücreti var mı?', 'Hayır, tüm ürünlerimiz için ücretsiz montaj hizmeti sunuyoruz.', 3),
('Garanti süresi ne kadardır?', 'Tüm ürünlerimiz 2 yıl garanti kapsamındadır.', 4),
('Hangi bölgelere hizmet veriyorsunuz?', 'Şu an için Ankara ve çevresine hizmet vermekteyiz.', 5);

-- --------------------------------------------------------
-- Tablo: contact_requests (İletişim formları)
-- --------------------------------------------------------
CREATE TABLE `contact_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `source` varchar(50) DEFAULT 'contact',
  `ip_address` varchar(45) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `is_replied` tinyint(1) DEFAULT 0,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `is_read` (`is_read`),
  KEY `source` (`source`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `contact_requests_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------
-- Tablo: sliders (Ana sayfa slider)
-- --------------------------------------------------------
CREATE TABLE `sliders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `button_text` varchar(100) DEFAULT NULL,
  `button_link` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------
-- Tablo: settings (Site ayarları)
-- --------------------------------------------------------
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_group` varchar(50) DEFAULT 'general',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- Varsayılan ayarlar
INSERT INTO `settings` (`setting_key`, `setting_value`, `setting_group`) VALUES
('site_name', 'Water Prime Su Arıtma', 'general'),
('site_slogan', 'Saf Su, Sağlıklı Yaşam', 'general'),
('site_description', 'Ankara''nın güvenilir su arıtma uzmanı. Ev tipi su arıtma cihazları, montaj, bakım ve servis hizmetleri.', 'general'),
('phone', '0533 294 01 06', 'contact'),
('whatsapp', '905332940106', 'contact'),
('email', 'iletisim@waterprimesuaritma.com', 'contact'),
('address', 'Ankara', 'contact'),
('working_hours', 'Hafta içi 09:00 - 18:00', 'contact'),
('google_maps', '', 'contact'),
('facebook', '#', 'social'),
('instagram', '#', 'social'),
('twitter', '#', 'social'),
('youtube', '#', 'social'),
('meta_title', 'Water Prime Su Arıtma | Ankara Su Arıtma Cihazları', 'seo'),
('meta_description', 'Ankara''da su arıtma cihazı satış, montaj ve servis hizmetleri. Ev tipi su arıtma sistemleri, filtre değişimi. Ücretsiz keşif için hemen arayın!', 'seo'),
('meta_keywords', 'su arıtma, su arıtma cihazı, ankara su arıtma, ev tipi su arıtma, su filtresi, reverse osmosis', 'seo'),
('gtm_id', '', 'analytics'),
('ga_id', '', 'analytics');

-- --------------------------------------------------------
-- Tablo: pages (Statik sayfalar)
-- --------------------------------------------------------
CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- Varsayılan sayfalar
INSERT INTO `pages` (`title`, `slug`, `content`) VALUES
('Hakkımızda', 'hakkimizda', '<p>Water Prime Su Arıtma, Ankara merkezli olarak faaliyet gösteren güvenilir su arıtma firmasıdır.</p><p>Müşteri memnuniyetini ön planda tutarak, kaliteli ürünler ve profesyonel hizmet sunmaktayız.</p>'),
('Neden Su Arıtma?', 'neden-su-aritma', '<p>Şebeke suyu evlerimize ulaşana kadar birçok aşamadan geçer ve bu süreçte çeşitli kirleticilere maruz kalabilir.</p><p>Su arıtma cihazları, içme suyunuzdaki zararlı maddeleri filtreleyerek ailenizin sağlığını korur.</p>');

-- --------------------------------------------------------
-- Tablo: testimonials (Müşteri Yorumları)
-- --------------------------------------------------------
CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `content` text NOT NULL,
  `rating` tinyint(1) DEFAULT 5,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- Varsayılan yorumlar
INSERT INTO `testimonials` (`name`, `title`, `content`, `rating`, `sort_order`) VALUES
('Ahmet Y.', 'Ankara', 'Çok memnun kaldık. Montaj ekibi çok profesyoneldi, her şeyi detaylıca anlattılar.', 5, 1),
('Fatma K.', 'Ankara', 'Su kalitesindeki fark inanılmaz. Artık rahatça su içebiliyoruz.', 5, 2),
('Mehmet S.', 'Ankara', 'Filtre değişimi için aradığımda hemen geldiler. Teşekkürler Water Prime!', 5, 3);

COMMIT;
