<?php
/**
 * Water Prime Su Arıtma - İletişim Sayfası
 */

require_once dirname(__DIR__) . '/config/config.php';

$pageTitle = 'İletişim | ' . SITE_NAME;
$pageDescription = 'Water Prime Su Arıtma ile iletişime geçin. Ankara\'da su arıtma cihazı satış, montaj ve servis için bize ulaşın.';
$canonicalUrl = SITE_URL . '/iletisim';

// Form gönderimi
$formSuccess = false;
$formError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $csrf = $_POST['csrf_token'] ?? '';

    // CSRF kontrolü
    if (!validateCSRFToken($csrf)) {
        $formError = 'Güvenlik doğrulaması başarısız. Lütfen tekrar deneyin.';
    }
    // Validasyon
    elseif (empty($name) || empty($phone)) {
        $formError = 'Lütfen zorunlu alanları doldurun.';
    }
    elseif (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $formError = 'Geçerli bir e-posta adresi giriniz.';
    }
    else {
        try {
            $db = getDB();
            $stmt = $db->prepare("
                INSERT INTO contact_requests (name, phone, email, subject, message, source, ip_address, created_at)
                VALUES (?, ?, ?, ?, ?, 'contact', ?, NOW())
            ");
            $stmt->execute([$name, $phone, $email, $subject, $message, $_SERVER['REMOTE_ADDR']]);
            $formSuccess = true;

            // Form verilerini temizle
            $name = $phone = $email = $subject = $message = '';
        } catch (Exception $e) {
            $formError = 'Bir hata oluştu. Lütfen daha sonra tekrar deneyin.';
        }
    }
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
            <span class="text-primary font-medium">İletişim</span>
        </nav>
    </div>
</section>

<!-- Page Header -->
<section class="bg-gradient-to-br from-primary to-primary-700 py-16">
    <div class="container text-center">
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">İletişim</h1>
        <p class="text-white/80 text-lg max-w-2xl mx-auto">
            Sorularınız için bize ulaşın. En kısa sürede size dönüş yapacağız.
        </p>
    </div>
</section>

<!-- Contact Section -->
<section class="py-16">
    <div class="container">
        <div class="grid lg:grid-cols-3 gap-12">
            <!-- Contact Info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Phone -->
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <div class="w-14 h-14 rounded-xl bg-accent/10 flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-primary mb-2">Telefon</h3>
                    <a href="<?= CONTACT_PHONE_LINK ?>" class="text-gray-600 hover:text-accent transition-colors text-lg">
                        <?= CONTACT_PHONE ?>
                    </a>
                    <p class="text-sm text-gray-500 mt-2">Hemen arayın, size yardımcı olalım</p>
                </div>

                <!-- WhatsApp -->
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <div class="w-14 h-14 rounded-xl bg-green-500/10 flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-primary mb-2">WhatsApp</h3>
                    <a href="https://wa.me/<?= CONTACT_WHATSAPP ?>" target="_blank" class="text-gray-600 hover:text-green-500 transition-colors text-lg">
                        <?= CONTACT_PHONE ?>
                    </a>
                    <p class="text-sm text-gray-500 mt-2">WhatsApp üzerinden yazışın</p>
                </div>

                <!-- Email -->
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <div class="w-14 h-14 rounded-xl bg-accent/10 flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-primary mb-2">E-posta</h3>
                    <a href="mailto:<?= CONTACT_EMAIL ?>" class="text-gray-600 hover:text-accent transition-colors">
                        <?= CONTACT_EMAIL ?>
                    </a>
                </div>

                <!-- Address -->
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <div class="w-14 h-14 rounded-xl bg-accent/10 flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-primary mb-2">Adres</h3>
                    <p class="text-gray-600"><?= CONTACT_ADDRESS ?></p>
                </div>

                <!-- Working Hours -->
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <div class="w-14 h-14 rounded-xl bg-accent/10 flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-primary mb-2">Çalışma Saatleri</h3>
                    <p class="text-gray-600"><?= WORKING_HOURS ?></p>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl border border-gray-100 p-8">
                    <h2 class="text-2xl font-bold text-primary mb-6">Bize Ulaşın</h2>

                    <?php if ($formSuccess): ?>
                    <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl mb-6">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <strong>Mesajınız alındı!</strong>
                                <p class="text-sm">En kısa sürede size dönüş yapacağız.</p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($formError): ?>
                    <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-xl mb-6">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span><?= e($formError) ?></span>
                        </div>
                    </div>
                    <?php endif; ?>

                    <form method="POST" action="" data-validate>
                        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

                        <div class="grid md:grid-cols-2 gap-6 mb-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Ad Soyad <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="name" name="name" required
                                       value="<?= e($name ?? '') ?>"
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all"
                                       placeholder="Adınız Soyadınız">
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Telefon <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" id="phone" name="phone" required
                                       value="<?= e($phone ?? '') ?>"
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all"
                                       placeholder="05XX XXX XX XX"
                                       oninput="formatPhoneNumber(this)">
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6 mb-6">
                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    E-posta
                                </label>
                                <input type="email" id="email" name="email"
                                       value="<?= e($email ?? '') ?>"
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all"
                                       placeholder="ornek@email.com">
                            </div>

                            <!-- Subject -->
                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                                    Konu
                                </label>
                                <select id="subject" name="subject"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all">
                                    <option value="">Konu Seçin</option>
                                    <option value="Ürün Bilgisi" <?= ($subject ?? '') === 'Ürün Bilgisi' ? 'selected' : '' ?>>Ürün Bilgisi</option>
                                    <option value="Fiyat Teklifi" <?= ($subject ?? '') === 'Fiyat Teklifi' ? 'selected' : '' ?>>Fiyat Teklifi</option>
                                    <option value="Servis Talebi" <?= ($subject ?? '') === 'Servis Talebi' ? 'selected' : '' ?>>Servis Talebi</option>
                                    <option value="Filtre Değişimi" <?= ($subject ?? '') === 'Filtre Değişimi' ? 'selected' : '' ?>>Filtre Değişimi</option>
                                    <option value="Diğer" <?= ($subject ?? '') === 'Diğer' ? 'selected' : '' ?>>Diğer</option>
                                </select>
                            </div>
                        </div>

                        <!-- Message -->
                        <div class="mb-6">
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                Mesajınız
                            </label>
                            <textarea id="message" name="message" rows="5"
                                      class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all resize-none"
                                      placeholder="Mesajınızı yazın..."><?= e($message ?? '') ?></textarea>
                        </div>

                        <!-- Submit -->
                        <button type="submit"
                                class="w-full bg-accent hover:bg-accent-dark text-white px-8 py-4 rounded-xl font-semibold transition-all hover:shadow-lg hover:shadow-accent/30">
                            Mesaj Gönder
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Google Map -->
        <div class="mt-16">
            <div class="bg-gray-200 rounded-2xl overflow-hidden h-96">
                <!-- Google Maps Embed - Ankara merkez koordinatları -->
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d195884.30596!2d32.62340935!3d39.9035557!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14d347d520732db1%3A0xbdc57b0c0842b8d!2sAnkara!5e0!3m2!1str!2str!4v1234567890"
                    width="100%"
                    height="100%"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>
</section>

<?php include INCLUDES_PATH . 'footer.php'; ?>
