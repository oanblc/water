<?php
/**
 * Water Prime Su Arıtma - Hakkımızda Sayfası
 */

require_once dirname(__DIR__) . '/config/config.php';

$pageTitle = 'Hakkımızda | ' . SITE_NAME;
$pageDescription = 'Water Prime Su Arıtma hakkında. Ankara\'da güvenilir su arıtma cihazları satış, montaj ve servis hizmetleri.';
$canonicalUrl = SITE_URL . '/hakkimizda';

// Sayfa içeriğini veritabanından çek
$db = getDB();
$stmt = $db->prepare("SELECT * FROM pages WHERE slug = 'hakkimizda'");
$stmt->execute();
$page = $stmt->fetch();

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
            <span class="text-primary font-medium">Hakkımızda</span>
        </nav>
    </div>
</section>

<!-- Page Header -->
<section class="bg-gradient-to-br from-primary to-primary-700 py-16">
    <div class="container text-center">
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Hakkımızda</h1>
        <p class="text-white/80 text-lg max-w-2xl mx-auto">
            Ankara'nın güvenilir su arıtma uzmanı
        </p>
    </div>
</section>

<!-- Content Section -->
<section class="py-16">
    <div class="container">
        <div class="max-w-4xl mx-auto">
            <!-- Main Content -->
            <div class="bg-white rounded-2xl border border-gray-100 p-8 md:p-12 mb-12">
                <div class="prose prose-lg max-w-none">
                    <?php if ($page && $page['content']): ?>
                        <?= $page['content'] ?>
                    <?php else: ?>
                    <p class="lead text-xl text-gray-600 mb-8">
                        <strong>Water Prime Su Arıtma</strong>, Ankara merkezli olarak faaliyet gösteren güvenilir su arıtma firmasıdır.
                    </p>

                    <h2>Misyonumuz</h2>
                    <p>
                        Ailelerin sağlıklı ve temiz su içebilmesi için en kaliteli su arıtma sistemlerini uygun fiyatlarla sunmak.
                        Müşteri memnuniyetini ön planda tutarak, satış öncesi ve sonrası tam destek sağlamak.
                    </p>

                    <h2>Vizyonumuz</h2>
                    <p>
                        Türkiye'nin her köşesinde, her evin temiz ve sağlıklı suya erişebilmesini sağlamak.
                        Su arıtma sektöründe güvenin ve kalitenin simgesi olmak.
                    </p>

                    <h2>Neden Water Prime?</h2>
                    <ul>
                        <li><strong>Kaliteli Ürünler:</strong> TSE belgeli, orijinal su arıtma cihazları</li>
                        <li><strong>Ücretsiz Montaj:</strong> Aynı gün profesyonel kurulum</li>
                        <li><strong>2 Yıl Garanti:</strong> Tüm ürünlerde resmi garanti</li>
                        <li><strong>7/24 Destek:</strong> Teknik destek her zaman yanınızda</li>
                        <li><strong>Uygun Fiyat:</strong> Piyasanın en rekabetçi fiyatları</li>
                    </ul>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Values -->
            <div class="grid md:grid-cols-3 gap-6 mb-12">
                <div class="bg-white rounded-2xl border border-gray-100 p-6 text-center">
                    <div class="w-14 h-14 rounded-xl bg-accent/10 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-primary mb-2">Güvenilirlik</h3>
                    <p class="text-gray-600 text-sm">Yılların deneyimi ve yüzlerce mutlu müşteri</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 p-6 text-center">
                    <div class="w-14 h-14 rounded-xl bg-accent/10 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-primary mb-2">Hız</h3>
                    <p class="text-gray-600 text-sm">Aynı gün teslimat ve kurulum</p>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 p-6 text-center">
                    <div class="w-14 h-14 rounded-xl bg-accent/10 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-primary mb-2">Müşteri Odaklılık</h3>
                    <p class="text-gray-600 text-sm">%100 müşteri memnuniyeti hedefi</p>
                </div>
            </div>

            <!-- CTA -->
            <div class="bg-gradient-to-r from-accent to-accent-dark rounded-2xl p-8 text-center text-white">
                <h2 class="text-2xl font-bold mb-4">Bizimle Çalışmak İster misiniz?</h2>
                <p class="text-white/80 mb-6">Ücretsiz keşif ve fiyat teklifi için hemen iletişime geçin.</p>
                <a href="<?= SITE_URL ?>/teklif-al" class="inline-flex items-center gap-2 bg-white text-primary px-8 py-4 rounded-xl font-semibold hover:bg-gray-100 transition-colors">
                    Teklif Al
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>

<?php include INCLUDES_PATH . 'footer.php'; ?>
