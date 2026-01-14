<?php
/**
 * Water Prime - PHP Built-in Server Router
 * Railway deployment icin URL routing
 */

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rawurldecode($uri);

// Statik dosyalar (assets, images, css, js)
if (preg_match('/\.(css|js|png|jpg|jpeg|gif|svg|ico|woff|woff2|ttf|eot)$/i', $uri)) {
    return false; // PHP built-in server statik dosyayi serve etsin
}

// Admin panel
if (strpos($uri, '/admin') === 0) {
    return false; // Admin dosyalarini direkt serve et
}

// Route tanimlari
$routes = [
    '/' => 'index.php',
    '/urunler' => 'pages/urunler.php',
    '/hizmetler' => 'pages/hizmetler.php',
    '/hakkimizda' => 'pages/hakkimizda.php',
    '/blog' => 'pages/blog.php',
    '/sss' => 'pages/sss.php',
    '/iletisim' => 'pages/iletisim.php',
    '/teklif-al' => 'pages/teklif-al.php',
];

// Exact match
if (isset($routes[$uri])) {
    require __DIR__ . '/' . $routes[$uri];
    return true;
}

// Urun detay: /urun/slug
if (preg_match('#^/urun/([a-z0-9-]+)$#', $uri, $matches)) {
    $_GET['slug'] = $matches[1];
    require __DIR__ . '/pages/urun-detay.php';
    return true;
}

// Kategori urunleri: /urunler/kategori-slug
if (preg_match('#^/urunler/([a-z0-9-]+)$#', $uri, $matches)) {
    $_GET['kategori'] = $matches[1];
    require __DIR__ . '/pages/urunler.php';
    return true;
}

// Hizmet detay: /hizmet/slug
if (preg_match('#^/hizmet/([a-z0-9-]+)$#', $uri, $matches)) {
    $_GET['slug'] = $matches[1];
    require __DIR__ . '/pages/hizmet-detay.php';
    return true;
}

// Blog detay: /blog/slug
if (preg_match('#^/blog/([a-z0-9-]+)$#', $uri, $matches)) {
    $_GET['slug'] = $matches[1];
    require __DIR__ . '/pages/blog-detay.php';
    return true;
}

// 404 - Sayfa bulunamadi
http_response_code(404);
require __DIR__ . '/pages/404.php';
return true;
