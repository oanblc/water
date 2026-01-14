<?php
/**
 * Water Prime Su Arıtma - Admin Dashboard
 */

session_start();
require_once dirname(__DIR__) . '/config/config.php';

$pageTitle = 'Dashboard';

// İstatistikleri çek
$db = getDB();

// Toplam ürün
$totalProducts = $db->query("SELECT COUNT(*) FROM products WHERE is_active = 1")->fetchColumn();

// Toplam kategori
$totalCategories = $db->query("SELECT COUNT(*) FROM categories WHERE is_active = 1")->fetchColumn();

// Toplam blog yazısı
$totalPosts = $db->query("SELECT COUNT(*) FROM blog_posts WHERE is_published = 1")->fetchColumn();

// Okunmamış talepler
$unreadRequests = $db->query("SELECT COUNT(*) FROM contact_requests WHERE is_read = 0")->fetchColumn();

// Son 7 günlük talepler
$weeklyRequests = $db->query("SELECT COUNT(*) FROM contact_requests WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetchColumn();

// Son talepler
$latestRequests = $db->query("
    SELECT cr.*, p.name as product_name
    FROM contact_requests cr
    LEFT JOIN products p ON cr.product_id = p.id
    ORDER BY cr.created_at DESC
    LIMIT 5
")->fetchAll();

// En çok görüntülenen ürünler
$popularProducts = $db->query("
    SELECT name, slug, view_count
    FROM products
    WHERE is_active = 1
    ORDER BY view_count DESC
    LIMIT 5
")->fetchAll();

include 'includes/admin-header.php';
?>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Products -->
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <a href="urunler.php" class="text-sm text-gray-500 hover:text-accent">Görüntüle →</a>
        </div>
        <h3 class="text-3xl font-bold text-primary mb-1"><?= $totalProducts ?></h3>
        <p class="text-gray-500 text-sm">Aktif Ürün</p>
    </div>

    <!-- Categories -->
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
            <a href="kategoriler.php" class="text-sm text-gray-500 hover:text-accent">Görüntüle →</a>
        </div>
        <h3 class="text-3xl font-bold text-primary mb-1"><?= $totalCategories ?></h3>
        <p class="text-gray-500 text-sm">Kategori</p>
    </div>

    <!-- Blog Posts -->
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
            </div>
            <a href="blog.php" class="text-sm text-gray-500 hover:text-accent">Görüntüle →</a>
        </div>
        <h3 class="text-3xl font-bold text-primary mb-1"><?= $totalPosts ?></h3>
        <p class="text-gray-500 text-sm">Blog Yazısı</p>
    </div>

    <!-- Requests -->
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <a href="talepler.php" class="text-sm text-gray-500 hover:text-accent">Görüntüle →</a>
        </div>
        <h3 class="text-3xl font-bold text-primary mb-1">
            <?= $unreadRequests ?>
            <?php if ($unreadRequests > 0): ?>
            <span class="text-sm font-normal text-red-500">yeni</span>
            <?php endif; ?>
        </h3>
        <p class="text-gray-500 text-sm">Okunmamış Talep</p>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <!-- Latest Requests -->
    <div class="bg-white rounded-2xl shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-primary">Son Talepler</h2>
                <span class="text-sm text-gray-500">Son 7 gün: <?= $weeklyRequests ?> talep</span>
            </div>
        </div>
        <div class="divide-y divide-gray-100">
            <?php if (!empty($latestRequests)): ?>
                <?php foreach ($latestRequests as $request): ?>
                <a href="talepler.php?id=<?= $request['id'] ?>" class="block p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-accent/10 flex items-center justify-center flex-shrink-0">
                            <span class="text-accent font-semibold"><?= strtoupper(mb_substr($request['name'], 0, 1)) ?></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-primary"><?= htmlspecialchars($request['name']) ?></span>
                                <?php if (!$request['is_read']): ?>
                                <span class="px-2 py-0.5 bg-red-100 text-red-600 text-xs rounded-full">Yeni</span>
                                <?php endif; ?>
                            </div>
                            <p class="text-sm text-gray-500 truncate"><?= htmlspecialchars($request['phone']) ?></p>
                            <?php if ($request['product_name']): ?>
                            <p class="text-xs text-accent mt-1"><?= htmlspecialchars($request['product_name']) ?></p>
                            <?php endif; ?>
                        </div>
                        <span class="text-xs text-gray-400 whitespace-nowrap">
                            <?= formatDate($request['created_at'], 'd.m H:i') ?>
                        </span>
                    </div>
                </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="p-8 text-center text-gray-500">
                    Henüz talep bulunmuyor.
                </div>
            <?php endif; ?>
        </div>
        <div class="p-4 border-t border-gray-100">
            <a href="talepler.php" class="text-sm text-accent hover:underline">Tüm talepleri görüntüle →</a>
        </div>
    </div>

    <!-- Popular Products -->
    <div class="bg-white rounded-2xl shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-primary">Popüler Ürünler</h2>
        </div>
        <div class="divide-y divide-gray-100">
            <?php if (!empty($popularProducts)): ?>
                <?php foreach ($popularProducts as $index => $product): ?>
                <div class="p-4 flex items-center gap-4">
                    <span class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-sm font-medium text-gray-600">
                        <?= $index + 1 ?>
                    </span>
                    <div class="flex-1 min-w-0">
                        <a href="<?= SITE_URL ?>/urun/<?= htmlspecialchars($product['slug']) ?>" target="_blank"
                           class="font-medium text-primary hover:text-accent transition-colors">
                            <?= htmlspecialchars($product['name']) ?>
                        </a>
                    </div>
                    <span class="text-sm text-gray-500"><?= number_format($product['view_count']) ?> görüntülenme</span>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="p-8 text-center text-gray-500">
                    Henüz ürün eklenmemiş.
                </div>
            <?php endif; ?>
        </div>
        <div class="p-4 border-t border-gray-100">
            <a href="urunler.php" class="text-sm text-accent hover:underline">Tüm ürünleri görüntüle →</a>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="mt-8">
    <h2 class="text-lg font-semibold text-primary mb-4">Hızlı İşlemler</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="urunler.php?action=add" class="bg-white rounded-xl p-4 shadow-sm hover:shadow-md transition-all text-center group">
            <div class="w-12 h-12 rounded-xl bg-accent/10 flex items-center justify-center mx-auto mb-3 group-hover:bg-accent/20 transition-colors">
                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            </div>
            <span class="text-sm font-medium text-primary">Yeni Ürün Ekle</span>
        </a>
        <a href="blog.php?action=add" class="bg-white rounded-xl p-4 shadow-sm hover:shadow-md transition-all text-center group">
            <div class="w-12 h-12 rounded-xl bg-accent/10 flex items-center justify-center mx-auto mb-3 group-hover:bg-accent/20 transition-colors">
                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </div>
            <span class="text-sm font-medium text-primary">Yeni Blog Yazısı</span>
        </a>
        <a href="sss.php?action=add" class="bg-white rounded-xl p-4 shadow-sm hover:shadow-md transition-all text-center group">
            <div class="w-12 h-12 rounded-xl bg-accent/10 flex items-center justify-center mx-auto mb-3 group-hover:bg-accent/20 transition-colors">
                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-sm font-medium text-primary">Yeni SSS Ekle</span>
        </a>
        <a href="ayarlar.php" class="bg-white rounded-xl p-4 shadow-sm hover:shadow-md transition-all text-center group">
            <div class="w-12 h-12 rounded-xl bg-accent/10 flex items-center justify-center mx-auto mb-3 group-hover:bg-accent/20 transition-colors">
                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <span class="text-sm font-medium text-primary">Site Ayarları</span>
        </a>
    </div>
</div>

<?php include 'includes/admin-footer.php'; ?>
