<?php
/**
 * Water Prime Su Arıtma - Blog Sayfası
 */

require_once dirname(__DIR__) . '/config/config.php';

$pageTitle = 'Blog | ' . SITE_NAME;
$pageDescription = 'Su arıtma, su kalitesi ve sağlık hakkında faydalı bilgiler. Water Prime blog yazıları.';
$canonicalUrl = SITE_URL . '/blog';

// Blog yazılarını çek
$db = getDB();
$stmt = $db->query("
    SELECT bp.*, u.full_name as author_name
    FROM blog_posts bp
    LEFT JOIN users u ON bp.author_id = u.id
    WHERE bp.is_published = 1
    ORDER BY bp.published_at DESC
");
$posts = $stmt->fetchAll();

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
            <span class="text-primary font-medium">Blog</span>
        </nav>
    </div>
</section>

<!-- Page Header -->
<section class="bg-gradient-to-br from-primary to-primary-700 py-16">
    <div class="container text-center">
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Blog</h1>
        <p class="text-white/80 text-lg max-w-2xl mx-auto">
            Su kalitesi ve sağlık hakkında faydalı bilgiler
        </p>
    </div>
</section>

<!-- Blog Posts -->
<section class="py-16">
    <div class="container">
        <?php if (!empty($posts)): ?>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($posts as $post): ?>
            <article class="group bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-xl transition-all">
                <!-- Image -->
                <a href="<?= SITE_URL ?>/blog/<?= e($post['slug']) ?>" class="block aspect-video bg-gray-100 relative overflow-hidden">
                    <?php if ($post['image']): ?>
                    <img src="<?= getImageUrl($post['image']) ?>"
                         alt="<?= e($post['title']) ?>"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                         loading="lazy">
                    <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </div>
                    <?php endif; ?>
                </a>

                <!-- Content -->
                <div class="p-6">
                    <!-- Date -->
                    <time class="text-sm text-gray-500 mb-2 block">
                        <?= formatDate($post['published_at'], 'd F Y') ?>
                    </time>

                    <!-- Title -->
                    <h2 class="text-xl font-semibold text-primary group-hover:text-accent transition-colors mb-3">
                        <a href="<?= SITE_URL ?>/blog/<?= e($post['slug']) ?>">
                            <?= e($post['title']) ?>
                        </a>
                    </h2>

                    <!-- Excerpt -->
                    <?php if ($post['excerpt']): ?>
                    <p class="text-gray-600 mb-4 line-clamp-3">
                        <?= e($post['excerpt']) ?>
                    </p>
                    <?php endif; ?>

                    <a href="<?= SITE_URL ?>/blog/<?= e($post['slug']) ?>"
                       class="inline-flex items-center gap-2 text-accent font-medium">
                        Devamını Oku
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-16">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Henüz blog yazısı yok</h3>
            <p class="text-gray-500">Yakında faydalı içerikler paylaşacağız.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include INCLUDES_PATH . 'footer.php'; ?>
