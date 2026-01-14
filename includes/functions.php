<?php
/**
 * Water Prime Su Arıtma - Yardımcı Fonksiyonlar
 */

/**
 * XSS koruması için HTML escape
 */
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * URL dostu slug oluştur
 */
function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return empty($text) ? 'n-a' : $text;
}

/**
 * Türkçe karakterleri URL dostu yap
 */
function turkishSlug($text) {
    $turkish = ['ş', 'Ş', 'ı', 'İ', 'ğ', 'Ğ', 'ü', 'Ü', 'ö', 'Ö', 'ç', 'Ç'];
    $english = ['s', 's', 'i', 'i', 'g', 'g', 'u', 'u', 'o', 'o', 'c', 'c'];
    $text = str_replace($turkish, $english, $text);
    $text = preg_replace('/[^a-zA-Z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    $text = trim($text, '-');
    return strtolower($text);
}

/**
 * Aktif sayfa kontrolü
 */
function isActivePage($page) {
    $currentPage = basename($_SERVER['PHP_SELF'], '.php');
    $currentPage = $currentPage === 'index' ? 'anasayfa' : $currentPage;
    return $currentPage === $page ? 'active' : '';
}

/**
 * Tarih formatla
 */
function formatDate($date, $format = 'd.m.Y') {
    return date($format, strtotime($date));
}

/**
 * Metin kısalt
 */
function truncate($text, $length = 100, $suffix = '...') {
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    return mb_substr($text, 0, $length) . $suffix;
}

/**
 * CSRF Token oluştur
 */
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * CSRF Token doğrula
 */
function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Site ayarını getir
 */
function getSetting($key, $default = '') {
    static $settings = null;

    if ($settings === null) {
        try {
            $db = getDB();
            $stmt = $db->query("SELECT setting_key, setting_value FROM settings");
            $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        } catch (Exception $e) {
            $settings = [];
        }
    }

    return $settings[$key] ?? $default;
}

/**
 * Kategorileri getir
 */
function getCategories($activeOnly = true) {
    $db = getDB();
    $sql = "SELECT * FROM categories";
    if ($activeOnly) {
        $sql .= " WHERE is_active = 1";
    }
    $sql .= " ORDER BY sort_order ASC";
    $stmt = $db->query($sql);
    return $stmt->fetchAll();
}

/**
 * Öne çıkan ürünleri getir
 */
function getFeaturedProducts($limit = 4) {
    $db = getDB();
    $stmt = $db->prepare("
        SELECT p.*, c.name as category_name, c.slug as category_slug
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.is_active = 1 AND p.is_featured = 1
        ORDER BY p.sort_order ASC
        LIMIT ?
    ");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

/**
 * Hizmetleri getir
 */
function getServices($activeOnly = true) {
    $db = getDB();
    $sql = "SELECT * FROM services";
    if ($activeOnly) {
        $sql .= " WHERE is_active = 1";
    }
    $sql .= " ORDER BY sort_order ASC";
    $stmt = $db->query($sql);
    return $stmt->fetchAll();
}

/**
 * Son blog yazılarını getir
 */
function getLatestPosts($limit = 3) {
    $db = getDB();
    $stmt = $db->prepare("
        SELECT * FROM blog_posts
        WHERE is_published = 1
        ORDER BY published_at DESC
        LIMIT ?
    ");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

/**
 * SSS getir
 */
function getFAQ($activeOnly = true) {
    $db = getDB();
    $sql = "SELECT * FROM faq";
    if ($activeOnly) {
        $sql .= " WHERE is_active = 1";
    }
    $sql .= " ORDER BY sort_order ASC";
    $stmt = $db->query($sql);
    return $stmt->fetchAll();
}

/**
 * Müşteri yorumlarını getir
 */
function getTestimonials($activeOnly = true) {
    $db = getDB();
    $sql = "SELECT * FROM testimonials";
    if ($activeOnly) {
        $sql .= " WHERE is_active = 1";
    }
    $sql .= " ORDER BY sort_order ASC";
    $stmt = $db->query($sql);
    return $stmt->fetchAll();
}

/**
 * Okunmamış talep sayısı
 */
function getUnreadRequestCount() {
    $db = getDB();
    $stmt = $db->query("SELECT COUNT(*) FROM contact_requests WHERE is_read = 0");
    return $stmt->fetchColumn();
}

/**
 * Flash mesaj ayarla
 */
function setFlash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Flash mesaj göster
 */
function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Resim yolu kontrol
 */
function getImageUrl($path, $default = '/assets/images/placeholder.jpg') {
    if (empty($path)) {
        return ASSETS_URL . $default;
    }
    if (strpos($path, 'http') === 0) {
        return $path;
    }
    return ASSETS_URL . '/images/' . $path;
}

/**
 * Sayfa görüntülenme sayısını artır
 */
function incrementViewCount($table, $id) {
    $db = getDB();
    $allowedTables = ['products', 'blog_posts'];
    if (!in_array($table, $allowedTables)) {
        return false;
    }
    $stmt = $db->prepare("UPDATE {$table} SET view_count = view_count + 1 WHERE id = ?");
    return $stmt->execute([$id]);
}
