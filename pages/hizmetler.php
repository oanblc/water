<?php
/**
 * Water Prime Su Arıtma - Hizmetler Sayfası
 */

require_once dirname(__DIR__) . '/config/config.php';

$pageTitle = 'Hizmetlerimiz | ' . SITE_NAME;
$pageDescription = 'Su arıtma cihazı montaj, bakım, servis ve filtre değişimi hizmetleri. Ankara\'da profesyonel su arıtma hizmetleri.';
$canonicalUrl = SITE_URL . '/hizmetler';

$services = getServices();

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
            <span class="text-primary font-medium">Hizmetler</span>
        </nav>
    </div>
</section>

<!-- Page Header -->
<section class="bg-gradient-to-br from-primary to-primary-700 py-16">
    <div class="container text-center">
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Hizmetlerimiz</h1>
        <p class="text-white/80 text-lg max-w-2xl mx-auto">
            Satış öncesi ve sonrası tam destek. Profesyonel ekibimizle yanınızdayız.
        </p>
    </div>
</section>

<!-- Services -->
<section class="py-16">
    <div class="container">
        <?php if (!empty($services)): ?>
        <div class="grid md:grid-cols-3 gap-8">
            <?php
            $serviceIcons = [
                'montaj' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
                'bakim-servis' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/>',
                'filtre-degisimi' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>'
            ];
            foreach ($services as $service):
                $iconPath = $serviceIcons[$service['slug']] ?? $serviceIcons['montaj'];
            ?>
            <article class="bg-white rounded-2xl border border-gray-100 p-8 hover:shadow-xl transition-all">
                <div class="w-16 h-16 rounded-2xl bg-accent/10 flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <?= $iconPath ?>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-primary mb-4"><?= e($service['name']) ?></h2>
                <p class="text-gray-600 mb-6"><?= e($service['short_description']) ?></p>
                <?php if ($service['description']): ?>
                <div class="prose prose-sm text-gray-600">
                    <?= $service['description'] ?>
                </div>
                <?php endif; ?>
                <a href="<?= SITE_URL ?>/teklif-al" class="inline-flex items-center gap-2 text-accent font-medium mt-6 hover:gap-3 transition-all">
                    Teklif Al
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </article>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- CTA -->
        <div class="mt-16 bg-gradient-to-r from-accent to-accent-dark rounded-2xl p-8 md:p-12 text-center text-white">
            <h2 class="text-2xl md:text-3xl font-bold mb-4">Hizmetlerimiz Hakkında Bilgi Alın</h2>
            <p class="text-white/80 mb-8 max-w-2xl mx-auto">
                Size en uygun hizmeti belirlemek için uzman ekibimizle görüşün. Ücretsiz danışmanlık hizmeti sunuyoruz.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?= CONTACT_PHONE_LINK ?>" class="inline-flex items-center justify-center gap-2 bg-white text-primary px-8 py-4 rounded-xl font-semibold hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <?= CONTACT_PHONE ?>
                </a>
                <a href="<?= SITE_URL ?>/iletisim" class="inline-flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20 text-white px-8 py-4 rounded-xl font-semibold transition-colors">
                    İletişime Geç
                </a>
            </div>
        </div>
    </div>
</section>

<?php include INCLUDES_PATH . 'footer.php'; ?>
