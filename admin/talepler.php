<?php
/**
 * Water Prime Su Arıtma - Admin Talepler Yönetimi
 */

session_start();
require_once dirname(__DIR__) . '/config/config.php';

$pageTitle = 'Talepler';
$db = getDB();

$success = $_GET['success'] ?? '';
$error = '';

// Silme
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $db->prepare("DELETE FROM contact_requests WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: talepler.php?success=deleted');
        exit;
    } catch (Exception $e) {
        $error = 'Silme işlemi başarısız.';
    }
}

// Okundu işaretle
if (isset($_GET['mark_read']) && is_numeric($_GET['mark_read'])) {
    $id = (int)$_GET['mark_read'];
    $stmt = $db->prepare("UPDATE contact_requests SET is_read = 1 WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: talepler.php?id=' . $id);
    exit;
}

// Tümünü okundu işaretle
if (isset($_GET['mark_all_read'])) {
    $db->query("UPDATE contact_requests SET is_read = 1 WHERE is_read = 0");
    header('Location: talepler.php?success=marked');
    exit;
}

// Not güncelleme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_notes'])) {
    $id = (int)$_POST['id'];
    $notes = trim($_POST['notes'] ?? '');
    $is_replied = isset($_POST['is_replied']) ? 1 : 0;

    $stmt = $db->prepare("UPDATE contact_requests SET notes = ?, is_replied = ? WHERE id = ?");
    $stmt->execute([$notes, $is_replied, $id]);
    header('Location: talepler.php?id=' . $id . '&success=updated');
    exit;
}

// Detay görüntüleme
$viewRequest = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $stmt = $db->prepare("
        SELECT cr.*, p.name as product_name, p.slug as product_slug
        FROM contact_requests cr
        LEFT JOIN products p ON cr.product_id = p.id
        WHERE cr.id = ?
    ");
    $stmt->execute([$_GET['id']]);
    $viewRequest = $stmt->fetch();

    // Okundu işaretle
    if ($viewRequest && !$viewRequest['is_read']) {
        $stmt = $db->prepare("UPDATE contact_requests SET is_read = 1 WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $viewRequest['is_read'] = 1;
    }
}

// Filtre
$filter = $_GET['filter'] ?? 'all';
$filterSql = '';
switch ($filter) {
    case 'unread':
        $filterSql = 'WHERE is_read = 0';
        break;
    case 'replied':
        $filterSql = 'WHERE is_replied = 1';
        break;
    case 'teklif':
        $filterSql = "WHERE source = 'teklif'";
        break;
    case 'contact':
        $filterSql = "WHERE source = 'contact'";
        break;
}

// Talepler listesi
$requests = $db->query("
    SELECT cr.*, p.name as product_name
    FROM contact_requests cr
    LEFT JOIN products p ON cr.product_id = p.id
    {$filterSql}
    ORDER BY cr.created_at DESC
")->fetchAll();

$unreadCount = $db->query("SELECT COUNT(*) FROM contact_requests WHERE is_read = 0")->fetchColumn();

include 'includes/admin-header.php';
?>

<?php if ($success): ?>
<div class="flash-message bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6">
    <?php
    switch ($success) {
        case 'deleted': echo 'Talep başarıyla silindi.'; break;
        case 'updated': echo 'Notlar güncellendi.'; break;
        case 'marked': echo 'Tüm talepler okundu olarak işaretlendi.'; break;
    }
    ?>
</div>
<?php endif; ?>

<?php if ($viewRequest): ?>
<!-- Talep Detay -->
<div class="mb-6">
    <a href="talepler.php" class="inline-flex items-center gap-2 text-gray-600 hover:text-accent">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
        </svg>
        Tüm Talepler
    </a>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <!-- Ana Bilgiler -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-primary">Talep Detayı</h2>
                <div class="flex items-center gap-2">
                    <?php if ($viewRequest['source'] === 'teklif'): ?>
                    <span class="px-3 py-1 bg-accent/10 text-accent text-sm rounded-full">Teklif Talebi</span>
                    <?php else: ?>
                    <span class="px-3 py-1 bg-gray-100 text-gray-600 text-sm rounded-full">İletişim</span>
                    <?php endif; ?>
                    <?php if ($viewRequest['is_replied']): ?>
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-sm rounded-full">Yanıtlandı</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm text-gray-500 mb-1">Ad Soyad</label>
                    <p class="text-lg font-medium text-primary"><?= htmlspecialchars($viewRequest['name']) ?></p>
                </div>
                <div>
                    <label class="block text-sm text-gray-500 mb-1">Telefon</label>
                    <a href="tel:<?= htmlspecialchars($viewRequest['phone']) ?>" class="text-lg font-medium text-accent hover:underline">
                        <?= htmlspecialchars($viewRequest['phone']) ?>
                    </a>
                </div>
                <?php if ($viewRequest['email']): ?>
                <div>
                    <label class="block text-sm text-gray-500 mb-1">E-posta</label>
                    <a href="mailto:<?= htmlspecialchars($viewRequest['email']) ?>" class="text-accent hover:underline">
                        <?= htmlspecialchars($viewRequest['email']) ?>
                    </a>
                </div>
                <?php endif; ?>
                <?php if ($viewRequest['subject']): ?>
                <div>
                    <label class="block text-sm text-gray-500 mb-1">Konu</label>
                    <p class="text-primary"><?= htmlspecialchars($viewRequest['subject']) ?></p>
                </div>
                <?php endif; ?>
            </div>

            <?php if ($viewRequest['product_name']): ?>
            <div class="mb-6 p-4 bg-accent/5 rounded-xl">
                <label class="block text-sm text-gray-500 mb-1">İlgilendiği Ürün</label>
                <a href="<?= SITE_URL ?>/urun/<?= htmlspecialchars($viewRequest['product_slug']) ?>" target="_blank"
                   class="font-medium text-accent hover:underline">
                    <?= htmlspecialchars($viewRequest['product_name']) ?>
                </a>
            </div>
            <?php endif; ?>

            <?php if ($viewRequest['message']): ?>
            <div>
                <label class="block text-sm text-gray-500 mb-2">Mesaj</label>
                <div class="p-4 bg-gray-50 rounded-xl">
                    <p class="text-gray-700 whitespace-pre-wrap"><?= htmlspecialchars($viewRequest['message']) ?></p>
                </div>
            </div>
            <?php endif; ?>

            <div class="mt-6 pt-6 border-t border-gray-100 flex items-center justify-between text-sm text-gray-500">
                <span>Tarih: <?= formatDate($viewRequest['created_at'], 'd.m.Y H:i') ?></span>
                <span>IP: <?= htmlspecialchars($viewRequest['ip_address']) ?></span>
            </div>
        </div>
    </div>

    <!-- Sağ Panel -->
    <div class="space-y-6">
        <!-- Hızlı İşlemler -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-semibold text-primary mb-4">Hızlı İşlemler</h3>
            <div class="space-y-3">
                <a href="tel:<?= htmlspecialchars($viewRequest['phone']) ?>"
                   class="flex items-center gap-3 w-full p-3 bg-accent/10 text-accent rounded-xl hover:bg-accent/20 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    Ara
                </a>
                <a href="https://wa.me/90<?= preg_replace('/[^0-9]/', '', $viewRequest['phone']) ?>"
                   target="_blank"
                   class="flex items-center gap-3 w-full p-3 bg-green-50 text-green-600 rounded-xl hover:bg-green-100 transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                    </svg>
                    WhatsApp
                </a>
            </div>
        </div>

        <!-- Notlar -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-semibold text-primary mb-4">Notlar</h3>
            <form method="POST">
                <input type="hidden" name="id" value="<?= $viewRequest['id'] ?>">
                <input type="hidden" name="update_notes" value="1">
                <textarea name="notes" rows="4" placeholder="Görüşme notları..."
                          class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20 mb-3"><?= htmlspecialchars($viewRequest['notes']) ?></textarea>
                <label class="flex items-center gap-3 mb-4 cursor-pointer">
                    <input type="checkbox" name="is_replied" value="1"
                           <?= $viewRequest['is_replied'] ? 'checked' : '' ?>
                           class="w-5 h-5 rounded border-gray-300 text-accent focus:ring-accent">
                    <span class="text-gray-700">Yanıtlandı</span>
                </label>
                <button type="submit" class="w-full bg-primary hover:bg-primary-600 text-white py-2 rounded-xl font-medium transition-colors">
                    Kaydet
                </button>
            </form>
        </div>

        <!-- Sil -->
        <a href="talepler.php?delete=<?= $viewRequest['id'] ?>"
           onclick="return confirmDelete('Bu talebi silmek istediğinizden emin misiniz?')"
           class="flex items-center justify-center gap-2 w-full p-3 text-red-500 border border-red-200 rounded-xl hover:bg-red-50 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Talebi Sil
        </a>
    </div>
</div>

<?php else: ?>
<!-- Talepler Listesi -->
<div class="bg-white rounded-2xl shadow-sm">
    <div class="p-6 border-b border-gray-100">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <h2 class="text-lg font-semibold text-primary">Talepler</h2>
                <?php if ($unreadCount > 0): ?>
                <span class="px-3 py-1 bg-red-100 text-red-600 text-sm rounded-full"><?= $unreadCount ?> okunmamış</span>
                <?php endif; ?>
            </div>
            <div class="flex items-center gap-3">
                <!-- Filtreler -->
                <select onchange="window.location.href='talepler.php?filter='+this.value"
                        class="px-4 py-2 rounded-xl border border-gray-200 text-sm">
                    <option value="all" <?= $filter === 'all' ? 'selected' : '' ?>>Tümü</option>
                    <option value="unread" <?= $filter === 'unread' ? 'selected' : '' ?>>Okunmamış</option>
                    <option value="teklif" <?= $filter === 'teklif' ? 'selected' : '' ?>>Teklif Talepleri</option>
                    <option value="contact" <?= $filter === 'contact' ? 'selected' : '' ?>>İletişim</option>
                    <option value="replied" <?= $filter === 'replied' ? 'selected' : '' ?>>Yanıtlananlar</option>
                </select>
                <?php if ($unreadCount > 0): ?>
                <a href="talepler.php?mark_all_read=1"
                   class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-sm hover:bg-gray-200 transition-colors">
                    Tümünü Okundu İşaretle
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if (!empty($requests)): ?>
    <div class="divide-y divide-gray-100">
        <?php foreach ($requests as $request): ?>
        <a href="talepler.php?id=<?= $request['id'] ?>"
           class="block p-4 hover:bg-gray-50 transition-colors <?= !$request['is_read'] ? 'bg-accent/5' : '' ?>">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-full <?= !$request['is_read'] ? 'bg-accent' : 'bg-gray-200' ?> flex items-center justify-center flex-shrink-0">
                    <span class="<?= !$request['is_read'] ? 'text-white' : 'text-gray-600' ?> font-semibold">
                        <?= strtoupper(mb_substr($request['name'], 0, 1)) ?>
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="font-medium text-primary <?= !$request['is_read'] ? 'font-semibold' : '' ?>">
                            <?= htmlspecialchars($request['name']) ?>
                        </span>
                        <?php if (!$request['is_read']): ?>
                        <span class="px-2 py-0.5 bg-accent text-white text-xs rounded-full">Yeni</span>
                        <?php endif; ?>
                        <?php if ($request['is_replied']): ?>
                        <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full">Yanıtlandı</span>
                        <?php endif; ?>
                        <?php if ($request['source'] === 'teklif'): ?>
                        <span class="px-2 py-0.5 bg-accent/10 text-accent text-xs rounded-full">Teklif</span>
                        <?php endif; ?>
                    </div>
                    <p class="text-sm text-gray-600"><?= htmlspecialchars($request['phone']) ?></p>
                    <?php if ($request['product_name']): ?>
                    <p class="text-xs text-accent mt-1"><?= htmlspecialchars($request['product_name']) ?></p>
                    <?php endif; ?>
                    <?php if ($request['message']): ?>
                    <p class="text-sm text-gray-500 mt-1 truncate"><?= htmlspecialchars(substr($request['message'], 0, 100)) ?></p>
                    <?php endif; ?>
                </div>
                <div class="text-right flex-shrink-0">
                    <span class="text-xs text-gray-400"><?= formatDate($request['created_at'], 'd.m.Y') ?></span>
                    <span class="block text-xs text-gray-400"><?= formatDate($request['created_at'], 'H:i') ?></span>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="p-12 text-center">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
        <h3 class="text-lg font-medium text-gray-700 mb-2">Talep bulunamadı</h3>
        <p class="text-gray-500">Seçilen filtreye uygun talep yok.</p>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php include 'includes/admin-footer.php'; ?>
