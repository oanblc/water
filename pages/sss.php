<?php
/**
 * Water Prime Su Arıtma - SSS Sayfası
 */

require_once dirname(__DIR__) . '/config/config.php';

$pageTitle = 'Sık Sorulan Sorular | ' . SITE_NAME;
$pageDescription = 'Su arıtma cihazları hakkında sık sorulan sorular ve cevapları. Filtre değişimi, bakım, garanti ve daha fazlası.';
$canonicalUrl = SITE_URL . '/sss';

$faqs = getFAQ();

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
            <span class="text-primary font-medium">Sık Sorulan Sorular</span>
        </nav>
    </div>
</section>

<!-- Page Header -->
<section class="bg-gradient-to-br from-primary to-primary-700 py-16">
    <div class="container text-center">
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Sık Sorulan Sorular</h1>
        <p class="text-white/80 text-lg max-w-2xl mx-auto">
            Su arıtma cihazları hakkında merak ettikleriniz
        </p>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-16">
    <div class="container">
        <div class="max-w-3xl mx-auto">
            <?php if (!empty($faqs)): ?>
            <div class="space-y-4" x-data="{ active: null }">
                <?php foreach ($faqs as $index => $faq): ?>
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <button @click="active = active === <?= $index ?> ? null : <?= $index ?>"
                            class="w-full flex items-center justify-between p-6 text-left hover:bg-gray-50 transition-colors">
                        <span class="font-semibold text-primary pr-4"><?= e($faq['question']) ?></span>
                        <svg class="w-5 h-5 text-accent flex-shrink-0 transition-transform duration-200"
                             :class="active === <?= $index ?> ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="active === <?= $index ?>"
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="px-6 pb-6">
                        <p class="text-gray-600 leading-relaxed"><?= e($faq['answer']) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="text-center py-12 text-gray-500">
                Henüz SSS eklenmemiş.
            </div>
            <?php endif; ?>

            <!-- Still Have Questions -->
            <div class="mt-12 bg-gray-50 rounded-2xl p-8 text-center">
                <h2 class="text-xl font-bold text-primary mb-3">Sorunuz mu var?</h2>
                <p class="text-gray-600 mb-6">Aradığınız cevabı bulamadıysanız bize ulaşın.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?= CONTACT_PHONE_LINK ?>" class="inline-flex items-center justify-center gap-2 bg-accent hover:bg-accent-dark text-white px-6 py-3 rounded-xl font-semibold transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        Hemen Arayın
                    </a>
                    <a href="<?= SITE_URL ?>/iletisim" class="inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary-600 text-white px-6 py-3 rounded-xl font-semibold transition-colors">
                        İletişim Formu
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Schema.org FAQPage -->
<?php if (!empty($faqs)): ?>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        <?php foreach ($faqs as $index => $faq): ?>
        {
            "@type": "Question",
            "name": "<?= e($faq['question']) ?>",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "<?= e($faq['answer']) ?>"
            }
        }<?= $index < count($faqs) - 1 ? ',' : '' ?>
        <?php endforeach; ?>
    ]
}
</script>
<?php endif; ?>

<?php include INCLUDES_PATH . 'footer.php'; ?>
