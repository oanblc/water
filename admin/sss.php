<?php
/**
 * Water Prime Su Arıtma - Admin SSS Yönetimi
 */

session_start();
require_once dirname(__DIR__) . '/config/config.php';

$pageTitle = 'Sık Sorulan Sorular';
$db = getDB();

$success = $_GET['success'] ?? '';
$error = '';

// Silme
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $db->prepare("DELETE FROM faq WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: sss.php?success=deleted');
        exit;
    } catch (Exception $e) {
        $error = 'Silme işlemi başarısız.';
    }
}

// Form gönderimi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $question = trim($_POST['question'] ?? '');
    $answer = trim($_POST['answer'] ?? '');
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $sort_order = (int)($_POST['sort_order'] ?? 0);

    if (empty($question) || empty($answer)) {
        $error = 'Soru ve cevap zorunludur.';
    } else {
        try {
            if ($id) {
                $stmt = $db->prepare("
                    UPDATE faq SET question = ?, answer = ?, is_active = ?, sort_order = ?, updated_at = NOW()
                    WHERE id = ?
                ");
                $stmt->execute([$question, $answer, $is_active, $sort_order, $id]);
                header('Location: sss.php?success=updated');
            } else {
                $stmt = $db->prepare("
                    INSERT INTO faq (question, answer, is_active, sort_order) VALUES (?, ?, ?, ?)
                ");
                $stmt->execute([$question, $answer, $is_active, $sort_order]);
                header('Location: sss.php?success=added');
            }
            exit;
        } catch (Exception $e) {
            $error = 'İşlem başarısız.';
        }
    }
}

// Düzenleme için veri çek
$editFaq = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM faq WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editFaq = $stmt->fetch();
}

$isAddMode = isset($_GET['action']) && $_GET['action'] === 'add';

// SSS listesi
$faqs = $db->query("SELECT * FROM faq ORDER BY sort_order ASC")->fetchAll();

include 'includes/admin-header.php';
?>

<?php if ($success): ?>
<div class="flash-message bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6">
    <?php
    switch ($success) {
        case 'added': echo 'SSS başarıyla eklendi.'; break;
        case 'updated': echo 'SSS başarıyla güncellendi.'; break;
        case 'deleted': echo 'SSS başarıyla silindi.'; break;
    }
    ?>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($editFaq || $isAddMode): ?>
<!-- SSS Formu -->
<div class="bg-white rounded-2xl shadow-sm">
    <div class="p-6 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-primary">
                <?= $editFaq ? 'SSS Düzenle' : 'Yeni SSS Ekle' ?>
            </h2>
            <a href="sss.php" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
        </div>
    </div>

    <form method="POST" class="p-6">
        <?php if ($editFaq): ?>
        <input type="hidden" name="id" value="<?= $editFaq['id'] ?>">
        <?php endif; ?>

        <div class="space-y-6 max-w-2xl">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Soru *</label>
                <input type="text" name="question" required
                       value="<?= htmlspecialchars($editFaq['question'] ?? '') ?>"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cevap *</label>
                <textarea name="answer" rows="4" required
                          class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20"><?= htmlspecialchars($editFaq['answer'] ?? '') ?></textarea>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sıralama</label>
                    <input type="number" name="sort_order"
                           value="<?= htmlspecialchars($editFaq['sort_order'] ?? '0') ?>"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200">
                </div>
                <div class="flex items-center">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1"
                               <?= ($editFaq['is_active'] ?? 1) ? 'checked' : '' ?>
                               class="w-5 h-5 rounded border-gray-300 text-accent focus:ring-accent">
                        <span class="text-gray-700">Aktif</span>
                    </label>
                </div>
            </div>

            <button type="submit"
                    class="bg-accent hover:bg-accent-dark text-white px-8 py-3 rounded-xl font-semibold transition-colors">
                <?= $editFaq ? 'Güncelle' : 'SSS Ekle' ?>
            </button>
        </div>
    </form>
</div>

<?php else: ?>
<!-- SSS Listesi -->
<div class="bg-white rounded-2xl shadow-sm">
    <div class="p-6 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-primary">Sık Sorulan Sorular (<?= count($faqs) ?>)</h2>
            <a href="sss.php?action=add"
               class="inline-flex items-center gap-2 bg-accent hover:bg-accent-dark text-white px-4 py-2 rounded-xl font-medium transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Yeni SSS
            </a>
        </div>
    </div>

    <?php if (!empty($faqs)): ?>
    <div class="divide-y divide-gray-100">
        <?php foreach ($faqs as $faq): ?>
        <div class="p-6 hover:bg-gray-50">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-sm text-gray-500">#<?= $faq['sort_order'] ?></span>
                        <?php if ($faq['is_active']): ?>
                        <span class="inline-flex px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full">Aktif</span>
                        <?php else: ?>
                        <span class="inline-flex px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-full">Pasif</span>
                        <?php endif; ?>
                    </div>
                    <h3 class="font-semibold text-primary mb-2"><?= htmlspecialchars($faq['question']) ?></h3>
                    <p class="text-gray-600 text-sm"><?= htmlspecialchars($faq['answer']) ?></p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="sss.php?edit=<?= $faq['id'] ?>"
                       class="p-2 text-gray-500 hover:text-accent hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </a>
                    <a href="sss.php?delete=<?= $faq['id'] ?>"
                       onclick="return confirmDelete('Bu SSS\'yi silmek istediğinizden emin misiniz?')"
                       class="p-2 text-gray-500 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="p-12 text-center">
        <p class="text-gray-500 mb-4">Henüz SSS yok.</p>
        <a href="sss.php?action=add" class="inline-flex items-center gap-2 bg-accent text-white px-6 py-3 rounded-xl">
            Yeni SSS Ekle
        </a>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php include 'includes/admin-footer.php'; ?>
