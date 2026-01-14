<?php
/**
 * Water Prime Su Arıtma - Admin Kategoriler Yönetimi
 */

session_start();
require_once dirname(__DIR__) . '/config/config.php';

$pageTitle = 'Kategoriler';
$db = getDB();

$success = $_GET['success'] ?? '';
$error = '';

// Silme işlemi
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        // Önce bu kategorideki ürünleri kontrol et
        $stmt = $db->prepare("SELECT COUNT(*) FROM products WHERE category_id = ?");
        $stmt->execute([$id]);
        $productCount = $stmt->fetchColumn();

        if ($productCount > 0) {
            $error = 'Bu kategoride ' . $productCount . ' ürün var. Önce ürünleri başka kategoriye taşıyın.';
        } else {
            $stmt = $db->prepare("DELETE FROM categories WHERE id = ?");
            $stmt->execute([$id]);
            header('Location: kategoriler.php?success=deleted');
            exit;
        }
    } catch (Exception $e) {
        $error = 'Silme işlemi başarısız.';
    }
}

// Form gönderimi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '') ?: turkishSlug($name);
    $description = trim($_POST['description'] ?? '');
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $meta_title = trim($_POST['meta_title'] ?? '');
    $meta_description = trim($_POST['meta_description'] ?? '');

    // Görsel yükleme
    $image = $_POST['current_image'] ?? '';
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = dirname(__DIR__) . '/assets/images/';
        $fileName = 'category_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['image']['name']);
        $uploadPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            if ($image && file_exists($uploadDir . $image)) {
                unlink($uploadDir . $image);
            }
            $image = $fileName;
        }
    }

    if (empty($name)) {
        $error = 'Kategori adı zorunludur.';
    } else {
        try {
            if ($id) {
                $stmt = $db->prepare("
                    UPDATE categories SET
                        name = ?, slug = ?, description = ?, image = ?,
                        is_active = ?, sort_order = ?, meta_title = ?, meta_description = ?,
                        updated_at = NOW()
                    WHERE id = ?
                ");
                $stmt->execute([$name, $slug, $description, $image, $is_active, $sort_order, $meta_title, $meta_description, $id]);
                header('Location: kategoriler.php?success=updated');
            } else {
                $stmt = $db->prepare("
                    INSERT INTO categories (name, slug, description, image, is_active, sort_order, meta_title, meta_description)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$name, $slug, $description, $image, $is_active, $sort_order, $meta_title, $meta_description]);
                header('Location: kategoriler.php?success=added');
            }
            exit;
        } catch (Exception $e) {
            $error = 'İşlem başarısız: ' . $e->getMessage();
        }
    }
}

// Düzenleme için veri çek
$editCategory = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editCategory = $stmt->fetch();
}

$isAddMode = isset($_GET['action']) && $_GET['action'] === 'add';

// Kategori listesi
$categories = $db->query("
    SELECT c.*, (SELECT COUNT(*) FROM products WHERE category_id = c.id) as product_count
    FROM categories c
    ORDER BY c.sort_order ASC
")->fetchAll();

include 'includes/admin-header.php';
?>

<?php if ($success): ?>
<div class="flash-message bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6">
    <?php
    switch ($success) {
        case 'added': echo 'Kategori başarıyla eklendi.'; break;
        case 'updated': echo 'Kategori başarıyla güncellendi.'; break;
        case 'deleted': echo 'Kategori başarıyla silindi.'; break;
    }
    ?>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($editCategory || $isAddMode): ?>
<!-- Kategori Formu -->
<div class="bg-white rounded-2xl shadow-sm">
    <div class="p-6 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-primary">
                <?= $editCategory ? 'Kategori Düzenle' : 'Yeni Kategori Ekle' ?>
            </h2>
            <a href="kategoriler.php" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
        </div>
    </div>

    <form method="POST" enctype="multipart/form-data" class="p-6">
        <?php if ($editCategory): ?>
        <input type="hidden" name="id" value="<?= $editCategory['id'] ?>">
        <input type="hidden" name="current_image" value="<?= htmlspecialchars($editCategory['image']) ?>">
        <?php endif; ?>

        <div class="grid lg:grid-cols-2 gap-6">
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori Adı *</label>
                    <input type="text" name="name" required
                           value="<?= htmlspecialchars($editCategory['name'] ?? '') ?>"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">URL (Slug)</label>
                    <input type="text" name="slug"
                           value="<?= htmlspecialchars($editCategory['slug'] ?? '') ?>"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Açıklama</label>
                    <textarea name="description" rows="3"
                              class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20"><?= htmlspecialchars($editCategory['description'] ?? '') ?></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Görsel</label>
                    <?php if (!empty($editCategory['image'])): ?>
                    <div class="mb-3">
                        <img src="<?= ASSETS_URL ?>/images/<?= htmlspecialchars($editCategory['image']) ?>"
                             class="w-32 h-32 object-cover rounded-xl">
                    </div>
                    <?php endif; ?>
                    <input type="file" name="image" accept="image/*"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200">
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sıralama</label>
                    <input type="number" name="sort_order"
                           value="<?= htmlspecialchars($editCategory['sort_order'] ?? '0') ?>"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20">
                </div>

                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1"
                               <?= ($editCategory['is_active'] ?? 1) ? 'checked' : '' ?>
                               class="w-5 h-5 rounded border-gray-300 text-accent focus:ring-accent">
                        <span class="text-gray-700">Aktif</span>
                    </label>
                </div>

                <div class="bg-gray-50 rounded-xl p-4 space-y-4">
                    <h3 class="font-medium text-primary">SEO Ayarları</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Meta Başlık</label>
                        <input type="text" name="meta_title"
                               value="<?= htmlspecialchars($editCategory['meta_title'] ?? '') ?>"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Meta Açıklama</label>
                        <textarea name="meta_description" rows="2"
                                  class="w-full px-4 py-3 rounded-xl border border-gray-200"><?= htmlspecialchars($editCategory['meta_description'] ?? '') ?></textarea>
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-accent hover:bg-accent-dark text-white px-6 py-3 rounded-xl font-semibold transition-colors">
                    <?= $editCategory ? 'Güncelle' : 'Kategori Ekle' ?>
                </button>
            </div>
        </div>
    </form>
</div>

<?php else: ?>
<!-- Kategori Listesi -->
<div class="bg-white rounded-2xl shadow-sm">
    <div class="p-6 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-primary">Kategoriler (<?= count($categories) ?>)</h2>
            <a href="kategoriler.php?action=add"
               class="inline-flex items-center gap-2 bg-accent hover:bg-accent-dark text-white px-4 py-2 rounded-xl font-medium transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Yeni Kategori
            </a>
        </div>
    </div>

    <?php if (!empty($categories)): ?>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Kategori</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Ürün Sayısı</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Sıra</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Durum</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase">İşlemler</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($categories as $category): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-lg bg-accent/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                            </div>
                            <div>
                                <span class="font-medium text-primary"><?= htmlspecialchars($category['name']) ?></span>
                                <span class="block text-sm text-gray-500">/urunler/<?= htmlspecialchars($category['slug']) ?></span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center text-gray-600"><?= $category['product_count'] ?></td>
                    <td class="px-6 py-4 text-center text-gray-600"><?= $category['sort_order'] ?></td>
                    <td class="px-6 py-4 text-center">
                        <?php if ($category['is_active']): ?>
                        <span class="inline-flex px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">Aktif</span>
                        <?php else: ?>
                        <span class="inline-flex px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">Pasif</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <a href="kategoriler.php?edit=<?= $category['id'] ?>"
                               class="p-2 text-gray-500 hover:text-accent hover:bg-gray-100 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <a href="kategoriler.php?delete=<?= $category['id'] ?>"
                               onclick="return confirmDelete('Bu kategoriyi silmek istediğinizden emin misiniz?')"
                               class="p-2 text-gray-500 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="p-12 text-center">
        <p class="text-gray-500 mb-4">Henüz kategori yok.</p>
        <a href="kategoriler.php?action=add" class="inline-flex items-center gap-2 bg-accent text-white px-6 py-3 rounded-xl">
            Yeni Kategori Ekle
        </a>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php include 'includes/admin-footer.php'; ?>
