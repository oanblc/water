<?php
/**
 * Water Prime Su Arıtma - Site Ayarları
 */

// Hata raporlama (canlıda kapatılacak)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Zaman dilimi
date_default_timezone_set('Europe/Istanbul');

// Site URL
define('SITE_URL', 'http://localhost/waterprime');
define('SITE_NAME', 'Water Prime Su Arıtma');
define('SITE_SLOGAN', 'Saf Su, Sağlıklı Yaşam');

// Dosya yolları
define('ROOT_PATH', dirname(__DIR__) . '/');
define('INCLUDES_PATH', ROOT_PATH . 'includes/');
define('ASSETS_URL', SITE_URL . '/assets');

// İletişim bilgileri
define('CONTACT_PHONE', '0533 294 01 06');
define('CONTACT_PHONE_LINK', 'tel:+905332940106');
define('CONTACT_WHATSAPP', '905332940106');
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
