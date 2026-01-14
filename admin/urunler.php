<?php
/**
 * Water Prime Su Arıtma - Admin Ürünler Yönetimi
 */

session_start();
require_once dirname(__DIR__) . '/config/config.php';

$pageTitle = 'Ürünler';
$db = getDB();

// İşlem mesajları
$success = $_GET['success'] ?? '';
$error = '';

// Silme işlemi
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $db->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: urunler.php?success=deleted');
        exit;
    } catch (Exception $e) {
        $error = 'Silme işlemi başarısız.';
    }
}

// Form gönderimi (Ekle/Düzenle)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $category_id = $_POST['category_id'] ?: null;
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '') ?: turkishSlug($name);
    $short_description = trim($_POST['short_description'] ?? '');
    $description = $_POST['description'] ?? '';
    $features = trim($_POST['features'] ?? '');
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $meta_title = trim($_POST['meta_title'] ?? '');
    $meta_description = trim($_POST['meta_description'] ?? '');

    // Görsel yükleme
    $image = $_POST['current_image'] ?? '';
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = dirname(__DIR__) . '/assets/images/products/';
        $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['image']['name']);
        $uploadPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            // Eski görseli sil
            if ($image && file_exists($uploadDir . $image)) {
                unlink($uploadDir . $image);
            }
            $image = 'products/' . $fileName;
        }
    }

    if (empty($name)) {
        $error = 'Ürün adı zorunludur.';
    } else {
        try {
            if ($id) {
                // Güncelle
                $stmt = $db->prepare("
                    UPDATE products SET
                        category_id = ?, name = ?, slug = ?, short_description = ?,
                        description = ?, features = ?, image = ?, is_featured = ?,
                        is_active = ?, sort_order = ?, meta_title = ?, meta_description = ?,
                        updated_at = NOW()
                    WHERE id = ?
                ");
                $stmt->execute([
                    $category_id, $name, $slug, $short_description, $description,
                    $features, $image, $is_featured, $is_active, $sort_order,
                    $meta_title, $meta_description, $id
                ]);
                header('Location: urunler.php?success=updated');
            } else {
                // Ekle
                $stmt = $db->prepare("
                    INSERT INTO products (category_id, name, slug, short_description, description,
                        features, image, is_featured, is_active, sort_order, meta_title, meta_description)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $category_id, $name, $slug, $short_description, $description,
                    $features, $image, $is_featured, $is_active, $sort_order,
                    $meta_title, $meta_description
                ]);
                header('Location: urunler.php?success=added');
            }
            exit;
        } catch (Exception $e) {
            $error = 'İşlem başarısız: ' . $e->getMessage();
        }
    }
}

// Düzenleme için veri çek
$editProduct = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editProduct = $stmt->fetch();
}

// Ekleme modu
$isAddMode = isset($_GET['action']) && $_GET['action'] === 'add';

// Ürün listesi
$products = $db->query("
    SELECT p.*, c.name as category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    ORDER BY p.sort_order ASC, p.created_at DESC
")->fetchAll();

// Kategoriler
$categories = getCategories(false);

include 'includes/admin-header.php';
?>

<?php if ($success): ?>
<div class="flash-message bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 transition-all">
    <?php
    switch ($success) {
        case 'added': echo 'Ürün başarıyla eklendi.'; break;
        case 'updated': echo 'Ürün başarıyla güncellendi.'; break;
        case 'deleted': echo 'Ürün başarıyla silindi.'; break;
    }
    ?>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
    <?= htmlspecialchars($error) ?>
</div>
<?php endif; ?>

<?php if ($editProduct || $isAddMode): ?>
<!-- Ürün Formu -->
<div class="bg-white rounded-2xl shadow-sm">
    <div class="p-6 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-primary">
                <?= $editProduct ? 'Ürün Düzenle' : 'Yeni Ürün Ekle' ?>
            </h2>
            <a href="urunler.php" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
        </div>
    </div>

    <form method="POST" enctype="multipart/form-data" class="p-6">
        <?php if ($editProduct): ?>
        <input type="hidden" name="id" value="<?= $editProduct['id'] ?>">
        <input type="hidden" name="current_image" value="<?= htmlspecialchars($editProduct['image']) ?>">
        <?php endif; ?>

        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Sol Kolon - Ana Bilgiler -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Ürün Adı -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ürün Adı *</label>
                    <input type="text" name="name" required
                           value="<?= htmlspecialchars($editProduct['name'] ?? '') ?>"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20">
                </div>

                <!-- Slug -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">URL (Slug)</label>
                    <input type="text" name="slug"
                           value="<?= htmlspecialchars($editProduct['slug'] ?? '') ?>"
                           placeholder="Boş bırakılırsa otomatik oluşturulur"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20">
                </div>

                <!-- Kısa Açıklama -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kısa Açıklama</label>
                    <textarea name="short_description" rows="2"
                              class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20"><?= htmlspecialchars($editProduct['short_description'] ?? '') ?></textarea>
                </div>

                <!-- Detaylı Açıklama -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Detaylı Açıklama</label>
                    <textarea name="description" rows="6"
                              class="wysiwyg-editor w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20"><?= htmlspecialchars($editProduct['description'] ?? '') ?></textarea>
                </div>

                <!-- Özellikler -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Özellikler</label>
                    <textarea name="features" rows="4" placeholder="Her satıra bir özellik yazın"
                              class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20"><?= htmlspecialchars($editProduct['features'] ?? '') ?></textarea>
                </div>

                <!-- SEO -->
                <div class="bg-gray-50 rounded-xl p-4 space-y-4">
                    <h3 class="font-medium text-primary">SEO Ayarları</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Meta Başlık</label>
                        <input type="text" name="meta_title"
                               value="<?= htmlspecialchars($editProduct['meta_title'] ?? '') ?>"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Meta Açıklama</label>
                        <textarea name="meta_description" rows="2"
                                  class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20"><?= htmlspecialchars($editProduct['meta_description'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Sağ Kolon -->
            <div class="space-y-6">
                <!-- Kategori -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="category_id"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20">
                        <option value="">Kategori Seçin</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($editProduct['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Görsel -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ürün Görseli</label>
                    <?php if (!empty($editProduct['image'])): ?>
                    <div class="mb-3">
                        <img src="<?= ASSETS_URL ?>/images/<?= htmlspecialchars($editProduct['image']) ?>"
                             class="w-full h-40 object-cover rounded-xl">
                    </div>
                    <?php endif; ?>
                    <input type="file" name="image" accept="image/*"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20">
                </div>

                <!-- Sıralama -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sıralama</label>
                    <input type="number" name="sort_order"
                           value="<?= htmlspecialchars($editProduct['sort_order'] ?? '0') ?>"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20">
                </div>

                <!-- Durumlar -->
                <div class="space-y-3">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1"
                               <?= ($editProduct['is_active'] ?? 1) ? 'checked' : '' ?>
                               class="w-5 h-5 rounded border-gray-300 text-accent focus:ring-accent">
                        <span class="text-gray-700">Aktif</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1"
                               <?= ($editProduct['is_featured'] ?? 0) ? 'checked' : '' ?>
                               class="w-5 h-5 rounded border-gray-300 text-accent focus:ring-accent">
                        <span class="text-gray-700">Öne Çıkan</span>
                    </label>
                </div>

                <!-- Kaydet Butonu -->
                <button type="submit"
                        class="w-full bg-accent hover:bg-accent-dark text-white px-6 py-3 rounded-xl font-semibold transition-colors">
                    <?= $editProduct ? 'Güncelle' : 'Ürün Ekle' ?>
                </button>
            </div>
        </div>
    </form>
</div>

<?php else: ?>
<!-- Ürün Listesi -->
<div class="bg-white rounded-2xl shadow-sm">
    <div class="p-6 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-primary">Ürünler (<?= count($products) ?>)</h2>
            <a href="urunler.php?action=add"
               class="inline-flex items-center gap-2 bg-accent hover:bg-accent-dark text-white px-4 py-2 rounded-xl font-medium transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Yeni Ürün
            </a>
        </div>
    </div>

    <?php if (!empty($products)): ?>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Ürün</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Kategori</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Öne Çıkan</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Durum</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Görüntülenme</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase">İşlemler</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($products as $product): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0">
                                <?php if ($product['image']): ?>
                                <img src="<?= ASSETS_URL ?>/images/<?= htmlspecialchars($product['image']) ?>"
                                     class="w-full h-full object-cover">
                                <?php endif; ?>
                            </div>
                            <div>
                                <a href="<?= SITE_URL ?>/urun/<?= htmlspecialchars($product['slug']) ?>" target="_blank"
                                   class="font-medium text-primary hover:text-accent">
                                    <?= htmlspecialchars($product['name']) ?>
                                </a>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        <?= htmlspecialchars($product['category_name'] ?? '-') ?>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <?php if ($product['is_featured']): ?>
                        <span class="inline-flex items-center px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            Öne Çıkan
                        </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <?php if ($product['is_active']): ?>
                        <span class="inline-flex px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">Aktif</span>
                        <?php else: ?>
                        <span class="inline-flex px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">Pasif</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-center text-gray-600">
                        <?= number_format($product['view_count']) ?>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <a href="urunler.php?edit=<?= $product['id'] ?>"
                               class="p-2 text-gray-500 hover:text-accent hover:bg-gray-100 rounded-lg transition-colors"
                               title="Düzenle">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <a href="urunler.php?delete=<?= $product['id'] ?>"
                               onclick="return confirmDelete('Bu ürünü silmek istediğinizden emin misiniz?')"
                               class="p-2 text-gray-500 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                               title="Sil">
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
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
        </svg>
        <h3 class="text-lg font-medium text-gray-700 mb-2">Henüz ürün yok</h3>
        <p class="text-gray-500 mb-4">İlk ürününüzü ekleyerek başlayın.</p>
        <a href="urunler.php?action=add" class="inline-flex items-center gap-2 bg-accent hover:bg-accent-dark text-white px-6 py-3 rounded-xl font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Yeni Ürün Ekle
        </a>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php include 'includes/admin-footer.php'; ?>
