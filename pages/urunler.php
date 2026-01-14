<?php
/**
 * Water Prime Su Arıtma - Ürünler Sayfası
 */

require_once dirname(__DIR__) . '/config/config.php';

// Kategori filtresi
$categorySlug = $_GET['kategori'] ?? null;
$currentCategory = null;

// Veritabanından verileri çek
$db = getDB();

// Kategorileri al
$categories = getCategories();

// Ürünleri al
if ($categorySlug) {
    // Kategoriyi bul
    foreach ($categories as $cat) {
        if ($cat['slug'] === $categorySlug) {
            $currentCategory = $cat;
            break;
        }
    }

    if ($currentCategory) {
        $stmt = $db->prepare("
            SELECT p.*, c.name as category_name, c.slug as category_slug
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.is_active = 1 AND p.category_id = ?
            ORDER BY p.sort_order ASC, p.created_at DESC
        ");
        $stmt->execute([$currentCategory['id']]);
        $products = $stmt->fetchAll();
    } else {
        // Kategori bulunamadı, tüm ürünleri göster
        $products = [];
    }
} else {
    // Tüm ürünler
    $stmt = $db->query("
        SELECT p.*, c.name as category_name, c.slug as category_slug
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.is_active = 1
        ORDER BY p.sort_order ASC, p.created_at DESC
    ");
    $products = $stmt->fetchAll();
}

// Sayfa SEO ayarları
if ($currentCategory) {
    $pageTitle = e($currentCategory['name']) . ' | ' . SITE_NAME;
    $pageDescription = $currentCategory['meta_description'] ?: e($currentCategory['description']);
    $canonicalUrl = SITE_URL . '/urunler/' . e($currentCategory['slug']);
} else {
    $pageTitle = 'Su Arıtma Ürünleri | ' . SITE_NAME;
    $pageDescription = 'Ev tipi su arıtma cihazları, tezgah altı ve tezgah üstü sistemler, filtreler ve yedek parçalar. Ankara\'da ücretsiz montaj.';
    $canonicalUrl = SITE_URL . '/urunler';
}

include INCLUDES_PATH . 'header.php';
?>

<!-- Breadcrumb -->
<section class="bg-gray-50 py-4 border-b border-gray-100">
    <div class="container">
        <nav class="flex items-center gap-2 text-sm">
            <a href="<?= SITE_URL ?>" class="text-gray-500 hover:text-accent transition-colors">Ana Sayfa</a>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <?php if ($currentCategory): ?>
            <a href="<?= SITE_URL ?>/urunler" class="text-gray-500 hover:text-accent transition-colors">Ürünler</a>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-primary font-medium"><?= e($currentCategory['name']) ?></span>
            <?php else: ?>
            <span class="text-primary font-medium">Ürünler</span>
            <?php endif; ?>
        </nav>
    </div>
</section>

<!-- Page Header -->
<section class="bg-gradient-to-br from-primary to-primary-700 py-16">
    <div class="container text-center">
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">
            <?= $currentCategory ? e($currentCategory['name']) : 'Su Arıtma Ürünleri' ?>
        </h1>
        <p class="text-white/80 text-lg max-w-2xl mx-auto">
            <?= $currentCategory ? e($currentCategory['description']) : 'Eviniz için en kaliteli su arıtma sistemlerini keşfedin. Tüm ürünlerimiz 2 yıl garantili ve ücretsiz montaj dahil.' ?>
        </p>
    </div>
</section>

<!-- Products Section -->
<section class="py-16">
    <div class="container">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar - Categories -->
            <aside class="lg:w-64 flex-shrink-0">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                    <h2 class="text-lg font-semibold text-primary mb-4">Kategoriler</h2>
                    <nav class="space-y-2">
                        <a href="<?= SITE_URL ?>/urunler"
                           class="block py-2 px-4 rounded-lg transition-colors <?= !$currentCategory ? 'bg-accent text-white' : 'text-gray-600 hover:bg-gray-50' ?>">
                            Tüm Ürünler
                        </a>
                        <?php foreach ($categories as $category): ?>
                        <a href="<?= SITE_URL ?>/urunler/<?= e($category['slug']) ?>"
                           class="block py-2 px-4 rounded-lg transition-colors <?= ($currentCategory && $currentCategory['id'] === $category['id']) ? 'bg-accent text-white' : 'text-gray-600 hover:bg-gray-50' ?>">
                            <?= e($category['name']) ?>
                        </a>
                        <?php endforeach; ?>
                    </nav>

                    <!-- Quick Contact -->
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <h3 class="font-semibold text-primary mb-3">Yardıma mı ihtiyacınız var?</h3>
                        <p class="text-sm text-gray-600 mb-4">Size en uygun ürünü seçmenize yardımcı olalım.</p>
                        <a href="<?= CONTACT_PHONE_LINK ?>" class="flex items-center gap-2 text-accent font-medium hover:underline">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <?= CONTACT_PHONE ?>
                        </a>
                    </div>
                </div>
            </aside>

            <!-- Products Grid -->
            <div class="flex-1">
                <?php if (!empty($products)): ?>
                <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">
                    <?php foreach ($products as $product): ?>
                    <article class="group bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-xl hover:shadow-gray-200/50 transition-all duration-300 hover:-translate-y-1">
                        <!-- Product Image -->
                        <a href="<?= SITE_URL ?>/urun/<?= e($product['slug']) ?>" class="block aspect-square bg-gray-50 relative overflow-hidden">
                            <?php if ($product['image']): ?>
                            <img src="<?= getImageUrl($product['image']) ?>"
                                 alt="<?= e($product['name']) ?>"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                 loading="lazy">
                            <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <?php endif; ?>

                            <!-- Category Badge -->
                            <?php if ($product['category_name'] && !$currentCategory): ?>
                            <span class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-xs font-medium text-primary">
                                <?= e($product['category_name']) ?>
                            </span>
                            <?php endif; ?>

                            <?php if ($product['is_featured']): ?>
                            <span class="absolute top-4 right-4 px-3 py-1 bg-accent text-white rounded-full text-xs font-medium">
                                Öne Çıkan
                            </span>
                            <?php endif; ?>
                        </a>

                        <!-- Product Info -->
                        <div class="p-5">
                            <h3 class="text-lg font-semibold text-primary mb-2 group-hover:text-accent transition-colors">
                                <a href="<?= SITE_URL ?>/urun/<?= e($product['slug']) ?>">
                                    <?= e($product['name']) ?>
                                </a>
                            </h3>
                            <?php if ($product['short_description']): ?>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                <?= e($product['short_description']) ?>
                            </p>
                            <?php endif; ?>

                            <div class="flex items-center justify-between">
                                <a href="<?= SITE_URL ?>/urun/<?= e($product['slug']) ?>"
                                   class="inline-flex items-center gap-2 text-accent font-medium text-sm group-hover:gap-3 transition-all">
                                    Detayları Gör
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </a>
                                <a href="<?= SITE_URL ?>/teklif-al?urun=<?= e($product['slug']) ?>"
                                   class="text-sm text-gray-500 hover:text-accent transition-colors">
                                    Teklif Al
                                </a>
                            </div>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-16">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Henüz ürün eklenmemiş</h3>
                    <p class="text-gray-500 mb-6">Bu kategoride henüz ürün bulunmuyor.</p>
                    <a href="<?= SITE_URL ?>/urunler" class="inline-flex items-center gap-2 text-accent font-medium hover:underline">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                        </svg>
                        Tüm Ürünlere Dön
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Schema.org Product List -->
<?php if (!empty($products)): ?>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ItemList",
    "name": "<?= $currentCategory ? e($currentCategory['name']) : 'Su Arıtma Ürünleri' ?>",
    "itemListElement": [
        <?php foreach ($products as $index => $product): ?>
        {
            "@type": "ListItem",
            "position": <?= $index + 1 ?>,
            "item": {
                "@type": "Product",
                "name": "<?= e($product['name']) ?>",
                "url": "<?= SITE_URL ?>/urun/<?= e($product['slug']) ?>",
                "description": "<?= e($product['short_description']) ?>"
                <?php if ($product['image']): ?>
                ,"image": "<?= getImageUrl($product['image']) ?>"
                <?php endif; ?>
            }
        }<?= $index < count($products) - 1 ? ',' : '' ?>
        <?php endforeach; ?>
    ]
}
</script>
<?php endif; ?>

<?php include INCLUDES_PATH . 'footer.php'; ?>
