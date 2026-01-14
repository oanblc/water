<?php
/**
 * 404 - Sayfa Bulunamadi
 */
require_once dirname(__DIR__) . '/config/config.php';

$pageTitle = 'Sayfa Bulunamadi | ' . SITE_NAME;
$pageDescription = 'Aradığınız sayfa bulunamadı.';

include INCLUDES_PATH . 'header.php';
?>

<section class="py-20 bg-gray-50 min-h-[60vh] flex items-center">
    <div class="container">
        <div class="text-center max-w-lg mx-auto">
            <div class="text-8xl font-bold text-accent mb-4">404</div>
            <h1 class="text-3xl font-bold text-primary mb-4">Sayfa Bulunamadı</h1>
            <p class="text-gray-600 mb-8">Aradığınız sayfa mevcut değil veya taşınmış olabilir.</p>
            <a href="<?= SITE_URL ?>" class="inline-flex items-center gap-2 bg-accent hover:bg-accent-dark text-white px-8 py-4 rounded-xl font-semibold transition-all">
                Ana Sayfaya Dön
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </a>
        </div>
    </div>
</section>

<?php include INCLUDES_PATH . 'footer.php'; ?>
