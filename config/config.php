<?php
/**
 * Water Prime Su Arıtma - Site Ayarları
 */

// Hata raporlama (production'da kapatılacak)
$isProduction = getenv('RAILWAY_ENVIRONMENT') || getenv('MYSQLHOST');
if ($isProduction) {
    error_reporting(0);
    ini_set('display_errors', 0);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// Zaman dilimi
date_default_timezone_set('Europe/Istanbul');

// Site URL - Railway veya localhost
if (getenv('RAILWAY_PUBLIC_DOMAIN')) {
    define('SITE_URL', 'https://' . getenv('RAILWAY_PUBLIC_DOMAIN'));
} elseif (isset($_SERVER['HTTP_HOST'])) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    define('SITE_URL', $protocol . '://' . $_SERVER['HTTP_HOST']);
} else {
    define('SITE_URL', 'http://localhost/waterprime');
}
define('SITE_NAME', 'Water Prime Su Arıtma');
define('SITE_SLOGAN', 'Saf Su, Sağlıklı Yaşam');

// Dosya yolları
define('ROOT_PATH', dirname(__DIR__) . '/');
define('INCLUDES_PATH', ROOT_PATH . 'includes/');
define('ASSETS_URL', SITE_URL . '/assets');

// İletişim bilgileri
define('CONTACT_PHONE', '0533 394 0106');
define('CONTACT_PHONE_LINK', 'tel:+905333940106');
define('CONTACT_WHATSAPP', '905333940106');
define('CONTACT_EMAIL', 'iletisim@waterprimesuaritma.com');
define('CONTACT_ADDRESS', 'Ankara');
define('WORKING_HOURS', 'Hafta içi 09:00 - 18:00');

// Sosyal medya
define('SOCIAL_FACEBOOK', '#');
define('SOCIAL_INSTAGRAM', '#');
define('SOCIAL_TWITTER', '#');
define('SOCIAL_YOUTUBE', '#');

// Session başlat
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Veritabanı bağlantısı
require_once ROOT_PATH . 'config/database.php';

// Yardımcı fonksiyonlar
require_once INCLUDES_PATH . 'functions.php';
