<?php
/**
 * Water Prime Su Arıtma - Admin Ayarlar
 */

session_start();
require_once dirname(__DIR__) . '/config/config.php';

$pageTitle = 'Ayarlar';
$db = getDB();

$success = '';
$error = '';

// Ayarları çek
$settings = [];
$stmt = $db->query("SELECT setting_key, setting_value FROM settings");
while ($row = $stmt->fetch()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// Form gönderimi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tab = $_POST['tab'] ?? 'general';

    try {
        foreach ($_POST as $key => $value) {
            if ($key === 'tab') continue;

            // Ayar var mı kontrol et
            $stmt = $db->prepare("SELECT COUNT(*) FROM settings WHERE setting_key = ?");
            $stmt->execute([$key]);
            $exists = $stmt->fetchColumn();

            if ($exists) {
                $stmt = $db->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
                $stmt->execute([trim($value), $key]);
            } else {
                // Yeni ayar ekle
                $group = 'general';
                if (strpos($key, 'social_') === 0) $group = 'social';
                elseif (strpos($key, 'meta_') === 0) $group = 'seo';
                elseif (in_array($key, ['phone', 'whatsapp', 'email', 'address', 'working_hours', 'google_maps'])) $group = 'contact';
                elseif (in_array($key, ['gtm_id', 'ga_id'])) $group = 'analytics';

                $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value, setting_group) VALUES (?, ?, ?)");
                $stmt->execute([$key, trim($value), $group]);
            }
        }

        // Ayarları yeniden yükle
        $settings = [];
        $stmt = $db->query("SELECT setting_key, setting_value FROM settings");
        while ($row = $stmt->fetch()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }

        $success = 'Ayarlar başarıyla kaydedildi.';
    } catch (Exception $e) {
        $error = 'Ayarlar kaydedilirken hata oluştu.';
    }
}

// Aktif sekme
$activeTab = $_GET['tab'] ?? 'general';

include 'includes/admin-header.php';
?>

<?php if ($success): ?>
<div class="flash-message bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6">
    <?= $success ?>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6"><?= $error ?></div>
<?php endif; ?>

<div class="bg-white rounded-2xl shadow-sm">
    <!-- Tabs -->
    <div class="border-b border-gray-100">
        <nav class="flex -mb-px">
            <a href="ayarlar.php?tab=general"
               class="px-6 py-4 text-sm font-medium border-b-2 <?= $activeTab === 'general' ? 'border-accent text-accent' : 'border-transparent text-gray-500 hover:text-gray-700' ?>">
                Genel
            </a>
            <a href="ayarlar.php?tab=contact"
               class="px-6 py-4 text-sm font-medium border-b-2 <?= $activeTab === 'contact' ? 'border-accent text-accent' : 'border-transparent text-gray-500 hover:text-gray-700' ?>">
                İletişim
            </a>
            <a href="ayarlar.php?tab=social"
               class="px-6 py-4 text-sm font-medium border-b-2 <?= $activeTab === 'social' ? 'border-accent text-accent' : 'border-transparent text-gray-500 hover:text-gray-700' ?>">
                Sosyal Medya
            </a>
            <a href="ayarlar.php?tab=seo"
               class="px-6 py-4 text-sm font-medium border-b-2 <?= $activeTab === 'seo' ? 'border-accent text-accent' : 'border-transparent text-gray-500 hover:text-gray-700' ?>">
                SEO
            </a>
            <a href="ayarlar.php?tab=analytics"
               class="px-6 py-4 text-sm font-medium border-b-2 <?= $activeTab === 'analytics' ? 'border-accent text-accent' : 'border-transparent text-gray-500 hover:text-gray-700' ?>">
                Analytics
            </a>
        </nav>
    </div>

    <form method="POST" class="p-6">
        <input type="hidden" name="tab" value="<?= $activeTab ?>">

        <?php if ($activeTab === 'general'): ?>
        <!-- Genel Ayarlar -->
        <div class="space-y-6 max-w-2xl">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Site Adı</label>
                <input type="text" name="site_name"
                       value="<?= htmlspecialchars($settings['site_name'] ?? '') ?>"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Slogan</label>
                <input type="text" name="site_slogan"
                       value="<?= htmlspecialchars($settings['site_slogan'] ?? '') ?>"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Site Açıklaması</label>
                <textarea name="site_description" rows="3"
                          class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20"><?= htmlspecialchars($settings['site_description'] ?? '') ?></textarea>
            </div>
        </div>

        <?php elseif ($activeTab === 'contact'): ?>
        <!-- İletişim Ayarları -->
        <div class="space-y-6 max-w-2xl">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Telefon</label>
                <input type="text" name="phone"
                       value="<?= htmlspecialchars($settings['phone'] ?? '') ?>"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">WhatsApp (Ülke kodu ile, başında 0 olmadan)</label>
                <input type="text" name="whatsapp" placeholder="905551234567"
                       value="<?= htmlspecialchars($settings['whatsapp'] ?? '') ?>"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">E-posta</label>
                <input type="email" name="email"
                       value="<?= htmlspecialchars($settings['email'] ?? '') ?>"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Adres</label>
                <textarea name="address" rows="2"
                          class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20"><?= htmlspecialchars($settings['address'] ?? '') ?></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Çalışma Saatleri</label>
                <input type="text" name="working_hours"
                       value="<?= htmlspecialchars($settings['working_hours'] ?? '') ?>"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Google Maps Embed URL</label>
                <input type="text" name="google_maps" placeholder="https://www.google.com/maps/embed?..."
                       value="<?= htmlspecialchars($settings['google_maps'] ?? '') ?>"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20">
            </div>
        </div>

        <?php elseif ($activeTab === 'social'): ?>
        <!-- Sosyal Medya -->
        <div class="space-y-6 max-w-2xl">
            <p class="text-sm text-gray-500 mb-4">Kullanmadığınız alanları boş bırakın veya # yazın.</p>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Facebook</label>
                <input type="text" name="facebook"
                       value="<?= htmlspecialchars($settings['facebook'] ?? '') ?>"
                       placeholder="https://facebook.com/..."
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Instagram</label>
                <input type="text" name="instagram"
                       value="<?= htmlspecialchars($settings['instagram'] ?? '') ?>"
                       placeholder="https://instagram.com/..."
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Twitter / X</label>
                <input type="text" name="twitter"
                       value="<?= htmlspecialchars($settings['twitter'] ?? '') ?>"
                       placeholder="https://twitter.com/..."
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">YouTube</label>
                <input type="text" name="youtube"
                       value="<?= htmlspecialchars($settings['youtube'] ?? '') ?>"
                       placeholder="https://youtube.com/..."
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20">
            </div>
        </div>

        <?php elseif ($activeTab === 'seo'): ?>
        <!-- SEO Ayarları -->
        <div class="space-y-6 max-w-2xl">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Meta Başlık (Ana Sayfa)</label>
                <input type="text" name="meta_title"
                       value="<?= htmlspecialchars($settings['meta_title'] ?? '') ?>"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20">
                <p class="text-xs text-gray-500 mt-1">Önerilen uzunluk: 50-60 karakter</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Meta Açıklama (Ana Sayfa)</label>
                <textarea name="meta_description" rows="3"
                          class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20"><?= htmlspecialchars($settings['meta_description'] ?? '') ?></textarea>
                <p class="text-xs text-gray-500 mt-1">Önerilen uzunluk: 150-160 karakter</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Meta Anahtar Kelimeler</label>
                <input type="text" name="meta_keywords"
                       value="<?= htmlspecialchars($settings['meta_keywords'] ?? '') ?>"
                       placeholder="su arıtma, su arıtma cihazı, ankara su arıtma"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20">
                <p class="text-xs text-gray-500 mt-1">Virgülle ayırarak yazın</p>
            </div>
        </div>

        <?php elseif ($activeTab === 'analytics'): ?>
        <!-- Analytics Ayarları -->
        <div class="space-y-6 max-w-2xl">
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
                <p class="text-sm text-yellow-700">
                    <strong>Not:</strong> Bu ayarlar canlı siteye alındığında aktif olacaktır.
                </p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Google Tag Manager ID</label>
                <input type="text" name="gtm_id" placeholder="GTM-XXXXXXX"
                       value="<?= htmlspecialchars($settings['gtm_id'] ?? '') ?>"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Google Analytics ID</label>
                <input type="text" name="ga_id" placeholder="G-XXXXXXXXXX"
                       value="<?= htmlspecialchars($settings['ga_id'] ?? '') ?>"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20">
            </div>
        </div>
        <?php endif; ?>

        <div class="mt-8 pt-6 border-t border-gray-100">
            <button type="submit"
                    class="bg-accent hover:bg-accent-dark text-white px-8 py-3 rounded-xl font-semibold transition-colors">
                Kaydet
            </button>
        </div>
    </form>
</div>

<?php include 'includes/admin-footer.php'; ?>
