<?php
/**
 * Water Prime Su Arıtma - Admin Hizmetler Yönetimi
 */

session_start();
require_once dirname(__DIR__) . '/config/config.php';

$pageTitle = 'Hizmetler';
$db = getDB();

$success = $_GET['success'] ?? '';
$error = '';

// Silme
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $db->prepare("DELETE FROM services WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: hizmetler.php?success=deleted');
        exit;
    } catch (Exception $e) {
        $error = 'Silme işlemi başarısız.';
    }
}

// Form gönderimi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '') ?: turkishSlug($name);
    $short_description = trim($_POST['short_description'] ?? '');
    $description = $_POST['description'] ?? '';
    $icon = trim($_POST['icon'] ?? '');
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $meta_title = trim($_POST['meta_title'] ?? '');
    $meta_description = trim($_POST['meta_description'] ?? '');

    // Görsel yükleme
    $image = $_POST['current_image'] ?? '';
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = dirname(__DIR__) . '/assets/images/';
        $fileName = 'service_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['image']['name']);
        $uploadPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            if ($image && file_exists($uploadDir . $image)) {
                unlink($uploadDir . $image);
            }
            $image = $fileName;
        }
    }

    if (empty($name)) {
        $error = 'Hizmet adı zorunludur.';
    } else {
        try {
            if ($id) {
                $stmt = $db->prepare("
                    UPDATE services SET
                        name = ?, slug = ?, short_description = ?, description = ?,
                        icon = ?, image = ?, is_active = ?, sort_order = ?,
                        meta_title = ?, meta_description = ?, updated_at = NOW()
                    WHERE id = ?
                ");
                $stmt->execute([$name, $slug, $short_description, $description, $icon, $image, $is_active, $sort_order, $meta_title, $meta_description, $id]);
                header('Location: hizmetler.php?success=updated');
            } else {
                $stmt = $db->prepare("
                    INSERT INTO services (name, slug, short_description, description, icon, image, is_active, sort_order, meta_title, meta_description)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$name, $slug, $short_description, $description, $icon, $image, $is_active, $sort_order, $meta_title, $meta_description]);
                header('Location: hizmetler.php?success=added');
            }
            exit;
        } catch (Exception $e) {
            $error = 'İşlem başarısız: ' . $e->getMessage();
        }
    }
}

// Düzenleme için veri çek
$editService = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editService = $stmt->fetch();
}

$isAddMode = isset($_GET['action']) && $_GET['action'] === 'add';

// Hizmet listesi
$services = $db->query("SELECT * FROM services ORDER BY sort_order ASC")->fetchAll();

include 'includes/admin-header.php';
?>

<?php if ($success): ?>
<div class="flash-message bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6">
    <?php
    switch ($success) {
        case 'added': echo 'Hizmet başarıyla eklendi.'; break;
        case 'updated': echo 'Hizmet başarıyla güncellendi.'; break;
        case 'deleted': echo 'Hizmet başarıyla silindi.'; break;
    }
    ?>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($editService || $isAddMode): ?>
<!-- Hizmet Formu -->
<div class="bg-white rounded-2xl shadow-sm">
    <div class="p-6 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-primary">
                <?= $editService ? 'Hizmet Düzenle' : 'Yeni Hizmet Ekle' ?>
            </h2>
            <a href="hizmetler.php" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
        </div>
    </div>

    <form method="POST" enctype="multipart/form-data" class="p-6">
        <?php if ($editService): ?>
        <input type="hidden" name="id" value="<?= $editService['id'] ?>">
        <input type="hidden" name="current_image" value="<?= htmlspecialchars($editService['image']) ?>">
        <?php endif; ?>

        <div class="grid lg:grid-cols-2 gap-6">
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hizmet Adı *</label>
                    <input type="text" name="name" required
                           value="<?= htmlspecialchars($editService['name'] ?? '') ?>"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">URL (Slug)</label>
                    <input type="text" name="slug"
                           value="<?= htmlspecialchars($editService['slug'] ?? '') ?>"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kısa Açıklama</label>
                    <textarea name="short_description" rows="2"
                              class="w-full px-4 py-3 rounded-xl border border-gray-200"><?= htmlspecialchars($editService['short_description'] ?? '') ?></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Detaylı Açıklama</label>
                    <textarea name="description" rows="6"
                              class="wysiwyg-editor w-full px-4 py-3 rounded-xl border border-gray-200"><?= htmlspecialchars($editService['description'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">İkon Adı</label>
                    <input type="text" name="icon"
                           value="<?= htmlspecialchars($editService['icon'] ?? '') ?>"
                           placeholder="wrench, cog, refresh vb."
                           class="w-full px-4 py-3 rounded-xl border border-gray-200">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Görsel</label>
                    <?php if (!empty($editService['image'])): ?>
                    <div class="mb-3">
                        <img src="<?= ASSETS_URL ?>/images/<?= htmlspecialchars($editService['image']) ?>"
                             class="w-32 h-32 object-cover rounded-xl">
                    </div>
                    <?php endif; ?>
                    <input type="file" name="image" accept="image/*"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sıralama</label>
                    <input type="number" name="sort_order"
                           value="<?= htmlspecialchars($editService['sort_order'] ?? '0') ?>"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200">
                </div>

                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1"
                               <?= ($editService['is_active'] ?? 1) ? 'checked' : '' ?>
                               class="w-5 h-5 rounded border-gray-300 text-accent focus:ring-accent">
                        <span class="text-gray-700">Aktif</span>
                    </label>
                </div>

                <div class="bg-gray-50 rounded-xl p-4 space-y-4">
                    <h3 class="font-medium text-primary">SEO Ayarları</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Meta Başlık</label>
                        <input type="text" name="meta_title"
                               value="<?= htmlspecialchars($editService['meta_title'] ?? '') ?>"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Meta Açıklama</label>
                        <textarea name="meta_description" rows="2"
                                  class="w-full px-4 py-3 rounded-xl border border-gray-200"><?= htmlspecialchars($editService['meta_description'] ?? '') ?></textarea>
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-accent hover:bg-accent-dark text-white px-6 py-3 rounded-xl font-semibold transition-colors">
                    <?= $editService ? 'Güncelle' : 'Hizmet Ekle' ?>
                </button>
            </div>
        </div>
    </form>
</div>

<?php else: ?>
<!-- Hizmet Listesi -->
<div class="bg-white rounded-2xl shadow-sm">
    <div class="p-6 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-primary">Hizmetler (<?= count($services) ?>)</h2>
            <a href="hizmetler.php?action=add"
               class="inline-flex items-center gap-2 bg-accent hover:bg-accent-dark text-white px-4 py-2 rounded-xl font-medium transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Yeni Hizmet
            </a>
        </div>
    </div>

    <?php if (!empty($services)): ?>
    <div class="divide-y divide-gray-100">
        <?php foreach ($services as $service): ?>
        <div class="p-6 hover:bg-gray-50 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-accent/10 flex items-center justify-center">
                    <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <span class="font-medium text-primary"><?= htmlspecialchars($service['name']) ?></span>
                    <span class="block text-sm text-gray-500"><?= htmlspecialchars($service['short_description']) ?></span>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <?php if ($service['is_active']): ?>
                <span class="inline-flex px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">Aktif</span>
                <?php else: ?>
                <span class="inline-flex px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">Pasif</span>
                <?php endif; ?>
                <div class="flex items-center gap-2">
                    <a href="hizmetler.php?edit=<?= $service['id'] ?>"
                       class="p-2 text-gray-500 hover:text-accent hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </a>
                    <a href="hizmetler.php?delete=<?= $service['id'] ?>"
                       onclick="return confirmDelete('Bu hizmeti silmek istediğinizden emin misiniz?')"
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
        <p class="text-gray-500 mb-4">Henüz hizmet yok.</p>
        <a href="hizmetler.php?action=add" class="inline-flex items-center gap-2 bg-accent text-white px-6 py-3 rounded-xl">
            Yeni Hizmet Ekle
        </a>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php include 'includes/admin-footer.php'; ?>
