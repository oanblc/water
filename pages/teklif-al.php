<?php
/**
 * Water Prime Su Arıtma - Teklif Al Sayfası (Landing Page - Google Ads)
 */

require_once dirname(__DIR__) . '/config/config.php';

$pageTitle = 'Ücretsiz Teklif Al | ' . SITE_NAME;
$pageDescription = 'Su arıtma cihazı için ücretsiz teklif alın. Ankara\'da ücretsiz keşif ve montaj. Hemen formu doldurun, sizi arayalım!';
$canonicalUrl = SITE_URL . '/teklif-al';

// URL'den ürün bilgisi
$productSlug = $_GET['urun'] ?? null;
$selectedProduct = null;

if ($productSlug) {
    $db = getDB();
    $stmt = $db->prepare("SELECT id, name FROM products WHERE slug = ? AND is_active = 1");
    $stmt->execute([$productSlug]);
    $selectedProduct = $stmt->fetch();
}

// Form gönderimi
$formSuccess = false;
$formError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $note = trim($_POST['note'] ?? '');
    $productId = $_POST['product_id'] ?? null;
    $csrf = $_POST['csrf_token'] ?? '';

    // CSRF kontrolü
    if (!validateCSRFToken($csrf)) {
        $formError = 'Güvenlik doğrulaması başarısız. Lütfen tekrar deneyin.';
    }
    // Validasyon
    elseif (empty($name) || empty($phone)) {
        $formError = 'Lütfen zorunlu alanları doldurun.';
    }
    else {
        try {
            $db = getDB();
            $stmt = $db->prepare("
                INSERT INTO contact_requests (name, phone, message, product_id, source, ip_address, created_at)
                VALUES (?, ?, ?, ?, 'teklif', ?, NOW())
            ");
            $stmt->execute([$name, $phone, $note, $productId ?: null, $_SERVER['REMOTE_ADDR']]);
            $formSuccess = true;

            // Conversion tracking için
            $conversionId = $db->lastInsertId();

            // Form verilerini temizle
            $name = $phone = $note = '';
        } catch (Exception $e) {
            $formError = 'Bir hata oluştu. Lütfen daha sonra tekrar deneyin.';
        }
    }
}

include INCLUDES_PATH . 'header.php';
?>

<!-- Landing Page - Minimal, focused design -->
<section class="min-h-[80vh] bg-gradient-to-br from-primary to-primary-700 py-16 flex items-center">
    <div class="container">
        <div class="max-w-5xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="text-white">
                    <span class="inline-block px-4 py-2 bg-accent/20 rounded-full text-accent-light text-sm font-medium mb-6">
                        Ücretsiz Keşif & Montaj
                    </span>

                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-6 leading-tight">
                        Sağlıklı Su İçin<br>
                        <span class="text-accent">Hemen Teklif Alın</span>
                    </h1>

                    <p class="text-lg text-white/80 mb-8">
                        Formu doldurun, uzman ekibimiz sizi arasın. Ücretsiz keşif ve en uygun fiyat garantisi!
                    </p>

                    <!-- Trust Points -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-accent/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-white/90">2 Yıl Garanti - Tüm Ürünlerde</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-accent/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-white/90">Ücretsiz Montaj - Aynı Gün Kurulum</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-accent/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-white/90">Orijinal Ürün & TSE Belgeli</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-accent/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-white/90">7/24 Teknik Destek</span>
                        </div>
                    </div>

                    <!-- Phone CTA -->
                    <div class="mt-10 pt-8 border-t border-white/20">
                        <p class="text-white/60 text-sm mb-2">Hemen aramak isterseniz:</p>
                        <a href="<?= CONTACT_PHONE_LINK ?>" class="inline-flex items-center gap-3 text-2xl font-bold text-white hover:text-accent transition-colors">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <?= CONTACT_PHONE ?>
                        </a>
                    </div>
                </div>

                <!-- Right - Form -->
                <div>
                    <div class="bg-white rounded-3xl shadow-2xl p-8">
                        <?php if ($formSuccess): ?>
                        <!-- Success State -->
                        <div class="text-center py-8">
                            <div class="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-primary mb-3">Talebiniz Alındı!</h2>
                            <p class="text-gray-600 mb-6">
                                En kısa sürede sizi arayacağız.<br>
                                Teşekkür ederiz!
                            </p>
                            <a href="<?= SITE_URL ?>" class="inline-flex items-center gap-2 text-accent font-medium hover:underline">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                                </svg>
                                Ana Sayfaya Dön
                            </a>
                        </div>

                        <!-- Conversion Tracking Script -->
                        <script>
                            // Google Ads conversion tracking
                            if (typeof gtag !== 'undefined') {
                                gtag('event', 'conversion', {
                                    'send_to': 'AW-CONVERSION_ID/CONVERSION_LABEL',
                                    'value': 1.0,
                                    'currency': 'TRY'
                                });
                            }

                            // Facebook Pixel
                            if (typeof fbq !== 'undefined') {
                                fbq('track', 'Lead', {
                                    content_name: 'Teklif Formu',
                                    content_category: 'Su Arıtma'
                                });
                            }
                        </script>
                        <?php else: ?>
                        <!-- Form -->
                        <h2 class="text-2xl font-bold text-primary mb-2">Ücretsiz Teklif Al</h2>
                        <p class="text-gray-600 mb-6">Formu doldurun, sizi arayalım</p>

                        <?php if ($formError): ?>
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
                            <?= e($formError) ?>
                        </div>
                        <?php endif; ?>

                        <form method="POST" action="" data-validate>
                            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                            <?php if ($selectedProduct): ?>
                            <input type="hidden" name="product_id" value="<?= $selectedProduct['id'] ?>">
                            <?php endif; ?>

                            <!-- Selected Product -->
                            <?php if ($selectedProduct): ?>
                            <div class="bg-accent/10 rounded-xl px-4 py-3 mb-6">
                                <span class="text-sm text-gray-600">Seçilen Ürün:</span>
                                <span class="block font-semibold text-primary"><?= e($selectedProduct['name']) ?></span>
                            </div>
                            <?php endif; ?>

                            <!-- Name -->
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Ad Soyad <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="name" name="name" required
                                       value="<?= e($name ?? '') ?>"
                                       class="w-full px-4 py-4 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all text-lg"
                                       placeholder="Adınız Soyadınız">
                            </div>

                            <!-- Phone -->
                            <div class="mb-4">
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Telefon <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" id="phone" name="phone" required
                                       value="<?= e($phone ?? '') ?>"
                                       class="w-full px-4 py-4 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all text-lg"
                                       placeholder="05XX XXX XX XX"
                                       oninput="formatPhoneNumber(this)">
                            </div>

                            <!-- Note (Optional) -->
                            <div class="mb-6">
                                <label for="note" class="block text-sm font-medium text-gray-700 mb-2">
                                    Not (İsteğe bağlı)
                                </label>
                                <textarea id="note" name="note" rows="3"
                                          class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all resize-none"
                                          placeholder="Eklemek istediğiniz not..."><?= e($note ?? '') ?></textarea>
                            </div>

                            <!-- Submit -->
                            <button type="submit"
                                    class="w-full bg-accent hover:bg-accent-dark text-white px-8 py-4 rounded-xl font-bold text-lg transition-all hover:shadow-lg hover:shadow-accent/30">
                                Teklif İste
                            </button>

                            <p class="text-center text-sm text-gray-500 mt-4">
                                Bilgileriniz gizli tutulacaktır
                            </p>
                        </form>
                        <?php endif; ?>
                    </div>

                    <!-- WhatsApp Alternative -->
                    <div class="mt-6 text-center">
                        <span class="text-white/60 text-sm">veya</span>
                        <a href="https://wa.me/<?= CONTACT_WHATSAPP ?>?text=Merhaba,%20su%20arıtma%20cihazı%20için%20teklif%20almak%20istiyorum."
                           target="_blank"
                           class="flex items-center justify-center gap-3 mt-3 bg-green-500 hover:bg-green-600 text-white py-4 px-6 rounded-xl font-semibold transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            WhatsApp ile Yaz
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include INCLUDES_PATH . 'footer.php'; ?>
