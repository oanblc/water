<?php
/**
 * Water Prime Su Arıtma - Ürün Detay Sayfası
 */

require_once dirname(__DIR__) . '/config/config.php';

$slug = $_GET['slug'] ?? null;

if (!$slug) {
    header('Location: ' . SITE_URL . '/urunler');
    exit;
}

$db = getDB();

// Ürünü getir
$stmt = $db->prepare("
    SELECT p.*, c.name as category_name, c.slug as category_slug
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.slug = ? AND p.is_active = 1
");
$stmt->execute([$slug]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: ' . SITE_URL . '/urunler');
    exit;
}

// Görüntülenme sayısını artır
incrementViewCount('products', $product['id']);

// İlgili ürünleri getir
$stmt = $db->prepare("
    SELECT p.*, c.name as category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.is_active = 1 AND p.category_id = ? AND p.id != ?
    ORDER BY RAND()
    LIMIT 3
");
$stmt->execute([$product['category_id'], $product['id']]);
$relatedProducts = $stmt->fetchAll();

// Sayfa SEO ayarları
$pageTitle = e($product['meta_title'] ?: $product['name']) . ' | ' . SITE_NAME;
$pageDescription = $product['meta_description'] ?: e($product['short_description']);
$canonicalUrl = SITE_URL . '/urun/' . e($product['slug']);

// Özellikleri parse et (| veya satır sonu ile ayrılmış olabilir)
$features = [];
if ($product['features']) {
    // Önce | ile ayırmayı dene, yoksa satır sonu ile ayır
    if (strpos($product['features'], '|') !== false) {
        $features = array_filter(array_map('trim', explode('|', $product['features'])));
    } else {
        $features = array_filter(array_map('trim', explode("\n", $product['features'])));
    }
}

include INCLUDES_PATH . 'header.php';
?>

<!-- Breadcrumb -->
<section class="bg-gray-50 py-4 border-b border-gray-100">
    <div class="container">
        <nav class="flex items-center gap-2 text-sm flex-wrap">
            <a href="<?= SITE_URL ?>" class="text-gray-500 hover:text-accent transition-colors">Ana Sayfa</a>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <a href="<?= SITE_URL ?>/urunler" class="text-gray-500 hover:text-accent transition-colors">Ürünler</a>
            <?php if ($product['category_name']): ?>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <a href="<?= SITE_URL ?>/urunler/<?= e($product['category_slug']) ?>" class="text-gray-500 hover:text-accent transition-colors">
                <?= e($product['category_name']) ?>
            </a>
            <?php endif; ?>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-primary font-medium"><?= e($product['name']) ?></span>
        </nav>
    </div>
</section>

<!-- Product Detail -->
<section class="py-12">
    <div class="container">
        <div class="grid lg:grid-cols-2 gap-12">
            <!-- Product Image -->
            <div class="space-y-4">
                <div class="aspect-square bg-gray-50 rounded-2xl overflow-hidden">
                    <?php if ($product['image']): ?>
                    <img src="<?= getImageUrl($product['image']) ?>"
                         alt="<?= e($product['name']) ?>"
                         class="w-full h-full object-cover"
                         id="mainImage">
                    <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-32 h-32 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Gallery (if multiple images) -->
                <?php if ($product['gallery']):
                    $gallery = json_decode($product['gallery'], true);
                    if ($gallery && is_array($gallery)):
                ?>
                <div class="grid grid-cols-4 gap-4">
                    <button onclick="changeImage('<?= getImageUrl($product['image']) ?>')"
                            class="aspect-square bg-gray-50 rounded-xl overflow-hidden border-2 border-accent">
                        <img src="<?= getImageUrl($product['image']) ?>"
                             alt="<?= e($product['name']) ?>"
                             class="w-full h-full object-cover">
                    </button>
                    <?php foreach ($gallery as $img): ?>
                    <button onclick="changeImage('<?= getImageUrl($img) ?>')"
                            class="aspect-square bg-gray-50 rounded-xl overflow-hidden border-2 border-transparent hover:border-accent transition-colors">
                        <img src="<?= getImageUrl($img) ?>"
                             alt="<?= e($product['name']) ?>"
                             class="w-full h-full object-cover">
                    </button>
                    <?php endforeach; ?>
                </div>
                <?php endif; endif; ?>
            </div>

            <!-- Product Info -->
            <div>
                <?php if ($product['category_name']): ?>
                <a href="<?= SITE_URL ?>/urunler/<?= e($product['category_slug']) ?>"
                   class="inline-block px-3 py-1 bg-accent/10 text-accent text-sm font-medium rounded-full mb-4 hover:bg-accent/20 transition-colors">
                    <?= e($product['category_name']) ?>
                </a>
                <?php endif; ?>

                <h1 class="text-3xl md:text-4xl font-bold text-primary mb-4">
                    <?= e($product['name']) ?>
                </h1>

                <?php if ($product['short_description']): ?>
                <p class="text-lg text-gray-600 mb-6">
                    <?= e($product['short_description']) ?>
                </p>
                <?php endif; ?>

                <!-- Features -->
                <?php if (!empty($features)): ?>
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-primary mb-4">Özellikler</h2>
                    <div class="grid grid-cols-2 gap-3">
                        <?php foreach ($features as $feature): ?>
                        <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-accent/5 to-transparent border border-accent/10 rounded-xl hover:border-accent/30 transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-accent/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-gray-700 text-sm font-medium"><?= e($feature) ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Trust Badges -->
                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                        <div class="w-10 h-10 rounded-lg bg-accent/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="block text-sm font-semibold text-primary">2 Yıl Garanti</span>
                            <span class="text-xs text-gray-500">Resmi garanti</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                        <div class="w-10 h-10 rounded-lg bg-accent/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="block text-sm font-semibold text-primary">Ücretsiz Montaj</span>
                            <span class="text-xs text-gray-500">Aynı gün kurulum</span>
                        </div>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 mb-8">
                    <a href="<?= SITE_URL ?>/teklif-al?urun=<?= e($product['slug']) ?>"
                       class="flex-1 inline-flex items-center justify-center gap-2 bg-accent hover:bg-accent-dark text-white px-8 py-4 rounded-xl font-semibold transition-all hover:shadow-lg hover:shadow-accent/30">
                        Teklif Al
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                    <a href="<?= CONTACT_PHONE_LINK ?>"
                       class="flex-1 inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary-600 text-white px-8 py-4 rounded-xl font-semibold transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        Hemen Ara
                    </a>
                </div>

                <!-- WhatsApp -->
                <a href="https://wa.me/<?= CONTACT_WHATSAPP ?>?text=Merhaba,%20<?= urlencode($product['name']) ?>%20hakkında%20bilgi%20almak%20istiyorum."
                   target="_blank"
                   class="flex items-center justify-center gap-3 w-full py-4 bg-green-500 hover:bg-green-600 text-white rounded-xl font-semibold transition-colors">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    WhatsApp ile Bilgi Al
                </a>
            </div>
        </div>

        <!-- Product Description -->
        <?php if ($product['description']): ?>
        <div class="mt-16 pt-12 border-t border-gray-100">
            <h2 class="text-2xl font-bold text-primary mb-6">Ürün Açıklaması</h2>
            <div class="prose prose-lg max-w-none">
                <?= $product['description'] ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Related Products -->
<?php if (!empty($relatedProducts)): ?>
<section class="py-16 bg-gray-50">
    <div class="container">
        <h2 class="text-2xl font-bold text-primary mb-8">Benzer Ürünler</h2>
        <div class="grid md:grid-cols-3 gap-6">
            <?php foreach ($relatedProducts as $related): ?>
            <article class="group bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300">
                <a href="<?= SITE_URL ?>/urun/<?= e($related['slug']) ?>" class="block aspect-square bg-gray-50 relative overflow-hidden">
                    <?php if ($related['image']): ?>
                    <img src="<?= getImageUrl($related['image']) ?>"
                         alt="<?= e($related['name']) ?>"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                         loading="lazy">
                    <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <?php endif; ?>
                </a>
                <div class="p-5">
                    <h3 class="text-lg font-semibold text-primary group-hover:text-accent transition-colors">
                        <a href="<?= SITE_URL ?>/urun/<?= e($related['slug']) ?>">
                            <?= e($related['name']) ?>
                        </a>
                    </h3>
                    <?php if ($related['short_description']): ?>
                    <p class="text-gray-600 text-sm mt-2 line-clamp-2"><?= e($related['short_description']) ?></p>
                    <?php endif; ?>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Schema.org Product -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Product",
    "name": "<?= e($product['name']) ?>",
    "description": "<?= e($product['short_description']) ?>",
    "url": "<?= $canonicalUrl ?>",
    <?php if ($product['image']): ?>
    "image": "<?= getImageUrl($product['image']) ?>",
    <?php endif; ?>
    "brand": {
        "@type": "Brand",
        "name": "Water Prime"
    },
    "offers": {
        "@type": "Offer",
        "availability": "https://schema.org/InStock",
        "seller": {
            "@type": "Organization",
            "name": "<?= SITE_NAME ?>"
        }
    }
}
</script>

<script>
function changeImage(src) {
    document.getElementById('mainImage').src = src;
}
</script>

<?php include INCLUDES_PATH . 'footer.php'; ?>
