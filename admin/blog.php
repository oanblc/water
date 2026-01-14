<?php
/**
 * Water Prime Su Arıtma - Admin Blog Yönetimi
 */

session_start();
require_once dirname(__DIR__) . '/config/config.php';

$pageTitle = 'Blog';
$db = getDB();

$success = $_GET['success'] ?? '';
$error = '';

// Silme
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $db->prepare("DELETE FROM blog_posts WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: blog.php?success=deleted');
        exit;
    } catch (Exception $e) {
        $error = 'Silme işlemi başarısız.';
    }
}

// Form gönderimi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '') ?: turkishSlug($title);
    $excerpt = trim($_POST['excerpt'] ?? '');
    $content = $_POST['content'] ?? '';
    $is_published = isset($_POST['is_published']) ? 1 : 0;
    $meta_title = trim($_POST['meta_title'] ?? '');
    $meta_description = trim($_POST['meta_description'] ?? '');

    // Yayın tarihi
    $published_at = $is_published ? ($_POST['published_at'] ?: date('Y-m-d H:i:s')) : null;

    // Görsel yükleme
    $image = $_POST['current_image'] ?? '';
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = dirname(__DIR__) . '/assets/images/';
        $fileName = 'blog_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['image']['name']);
        $uploadPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            if ($image && file_exists($uploadDir . $image)) {
                unlink($uploadDir . $image);
            }
            $image = $fileName;
        }
    }

    if (empty($title)) {
        $error = 'Başlık zorunludur.';
    } else {
        try {
            if ($id) {
                $stmt = $db->prepare("
                    UPDATE blog_posts SET
                        title = ?, slug = ?, excerpt = ?, content = ?, image = ?,
                        is_published = ?, published_at = ?, meta_title = ?, meta_description = ?,
                        updated_at = NOW()
                    WHERE id = ?
                ");
                $stmt->execute([$title, $slug, $excerpt, $content, $image, $is_published, $published_at, $meta_title, $meta_description, $id]);
                header('Location: blog.php?success=updated');
            } else {
                $stmt = $db->prepare("
                    INSERT INTO blog_posts (title, slug, excerpt, content, image, author_id, is_published, published_at, meta_title, meta_description)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$title, $slug, $excerpt, $content, $image, $_SESSION['admin_user_id'], $is_published, $published_at, $meta_title, $meta_description]);
                header('Location: blog.php?success=added');
            }
            exit;
        } catch (Exception $e) {
            $error = 'İşlem başarısız: ' . $e->getMessage();
        }
    }
}

// Düzenleme için veri çek
$editPost = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM blog_posts WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editPost = $stmt->fetch();
}

$isAddMode = isset($_GET['action']) && $_GET['action'] === 'add';

// Blog listesi
$posts = $db->query("
    SELECT bp.*, u.full_name as author_name
    FROM blog_posts bp
    LEFT JOIN users u ON bp.author_id = u.id
    ORDER BY bp.created_at DESC
")->fetchAll();

include 'includes/admin-header.php';
?>

<?php if ($success): ?>
<div class="flash-message bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6">
    <?php
    switch ($success) {
        case 'added': echo 'Yazı başarıyla eklendi.'; break;
        case 'updated': echo 'Yazı başarıyla güncellendi.'; break;
        case 'deleted': echo 'Yazı başarıyla silindi.'; break;
    }
    ?>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($editPost || $isAddMode): ?>
<!-- Blog Formu -->
<div class="bg-white rounded-2xl shadow-sm">
    <div class="p-6 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-primary">
                <?= $editPost ? 'Yazı Düzenle' : 'Yeni Yazı Ekle' ?>
            </h2>
            <a href="blog.php" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
        </div>
    </div>

    <form method="POST" enctype="multipart/form-data" class="p-6">
        <?php if ($editPost): ?>
        <input type="hidden" name="id" value="<?= $editPost['id'] ?>">
        <input type="hidden" name="current_image" value="<?= htmlspecialchars($editPost['image']) ?>">
        <?php endif; ?>

        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Başlık *</label>
                    <input type="text" name="title" required
                           value="<?= htmlspecialchars($editPost['title'] ?? '') ?>"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">URL (Slug)</label>
                    <input type="text" name="slug"
                           value="<?= htmlspecialchars($editPost['slug'] ?? '') ?>"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Özet</label>
                    <textarea name="excerpt" rows="2"
                              class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20"><?= htmlspecialchars($editPost['excerpt'] ?? '') ?></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">İçerik</label>
                    <textarea name="content" rows="12"
                              class="wysiwyg-editor w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20"><?= htmlspecialchars($editPost['content'] ?? '') ?></textarea>
                </div>

                <div class="bg-gray-50 rounded-xl p-4 space-y-4">
                    <h3 class="font-medium text-primary">SEO Ayarları</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Meta Başlık</label>
                        <input type="text" name="meta_title"
                               value="<?= htmlspecialchars($editPost['meta_title'] ?? '') ?>"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Meta Açıklama</label>
                        <textarea name="meta_description" rows="2"
                                  class="w-full px-4 py-3 rounded-xl border border-gray-200"><?= htmlspecialchars($editPost['meta_description'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kapak Görseli</label>
                    <?php if (!empty($editPost['image'])): ?>
                    <div class="mb-3">
                        <img src="<?= ASSETS_URL ?>/images/<?= htmlspecialchars($editPost['image']) ?>"
                             class="w-full h-40 object-cover rounded-xl">
                    </div>
                    <?php endif; ?>
                    <input type="file" name="image" accept="image/*"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Yayın Tarihi</label>
                    <input type="datetime-local" name="published_at"
                           value="<?= $editPost['published_at'] ? date('Y-m-d\TH:i', strtotime($editPost['published_at'])) : '' ?>"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200">
                </div>

                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_published" value="1"
                               <?= ($editPost['is_published'] ?? 0) ? 'checked' : '' ?>
                               class="w-5 h-5 rounded border-gray-300 text-accent focus:ring-accent">
                        <span class="text-gray-700">Yayınla</span>
                    </label>
                </div>

                <button type="submit"
                        class="w-full bg-accent hover:bg-accent-dark text-white px-6 py-3 rounded-xl font-semibold transition-colors">
                    <?= $editPost ? 'Güncelle' : 'Yazı Ekle' ?>
                </button>
            </div>
        </div>
    </form>
</div>

<?php else: ?>
<!-- Blog Listesi -->
<div class="bg-white rounded-2xl shadow-sm">
    <div class="p-6 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-primary">Blog Yazıları (<?= count($posts) ?>)</h2>
            <a href="blog.php?action=add"
               class="inline-flex items-center gap-2 bg-accent hover:bg-accent-dark text-white px-4 py-2 rounded-xl font-medium transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Yeni Yazı
            </a>
        </div>
    </div>

    <?php if (!empty($posts)): ?>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Başlık</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Durum</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Görüntülenme</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Tarih</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase">İşlemler</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($posts as $post): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-12 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0">
                                <?php if ($post['image']): ?>
                                <img src="<?= ASSETS_URL ?>/images/<?= htmlspecialchars($post['image']) ?>"
                                     class="w-full h-full object-cover">
                                <?php endif; ?>
                            </div>
                            <div>
                                <a href="<?= SITE_URL ?>/blog/<?= htmlspecialchars($post['slug']) ?>" target="_blank"
                                   class="font-medium text-primary hover:text-accent">
                                    <?= htmlspecialchars($post['title']) ?>
                                </a>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <?php if ($post['is_published']): ?>
                        <span class="inline-flex px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">Yayında</span>
                        <?php else: ?>
                        <span class="inline-flex px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full">Taslak</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-center text-gray-600"><?= number_format($post['view_count']) ?></td>
                    <td class="px-6 py-4 text-center text-gray-600 text-sm">
                        <?= $post['published_at'] ? formatDate($post['published_at'], 'd.m.Y') : '-' ?>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <a href="blog.php?edit=<?= $post['id'] ?>"
                               class="p-2 text-gray-500 hover:text-accent hover:bg-gray-100 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <a href="blog.php?delete=<?= $post['id'] ?>"
                               onclick="return confirmDelete('Bu yazıyı silmek istediğinizden emin misiniz?')"
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
        <p class="text-gray-500 mb-4">Henüz blog yazısı yok.</p>
        <a href="blog.php?action=add" class="inline-flex items-center gap-2 bg-accent text-white px-6 py-3 rounded-xl">
            Yeni Yazı Ekle
        </a>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php include 'includes/admin-footer.php'; ?>
