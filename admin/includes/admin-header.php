<?php
/**
 * Water Prime Su Arıtma - Admin Header
 */

// Oturum kontrolü
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$unreadCount = getUnreadRequestCount();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Yönetim Paneli' ?> - <?= SITE_NAME ?></title>
    <meta name="robots" content="noindex, nofollow">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#0A2647',
                            600: '#081F3A',
                            700: '#06172C'
                        },
                        accent: {
                            DEFAULT: '#00B4D8',
                            light: '#90E0EF',
                            dark: '#0096C7'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Summernote Editor -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/lang/summernote-tr-TR.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelector('.wysiwyg-editor')) {
                $('.wysiwyg-editor').summernote({
                    lang: 'tr-TR',
                    height: 350,
                    placeholder: 'İçeriği buraya yazın...',
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video', 'hr']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                    ],
                    styleTags: ['p', 'h2', 'h3', 'h4', 'blockquote'],
                    fontSizes: ['12', '14', '16', '18', '20', '24'],
                    callbacks: {
                        onImageUpload: function(files) {
                            // Görsel yükleme için basit base64 çözümü
                            var reader = new FileReader();
                            reader.onloadend = function() {
                                var img = $('<img>').attr('src', reader.result);
                                $('.wysiwyg-editor').summernote('insertNode', img[0]);
                            };
                            reader.readAsDataURL(files[0]);
                        }
                    }
                });
            }
        });
    </script>

    <style>
        [x-cloak] { display: none !important; }
        /* Summernote stil düzeltmeleri */
        .note-editor.note-frame { border-radius: 0.75rem !important; border-color: #e5e7eb !important; }
        .note-toolbar { border-radius: 0.75rem 0.75rem 0 0 !important; background: #f9fafb !important; }
        .note-editing-area { background: #fff; }
        .note-editable { font-family: Inter, sans-serif; font-size: 14px; line-height: 1.6; }
        .note-editor .note-dropzone { border-radius: 0.75rem; }
    </style>
</head>
<body class="font-sans bg-gray-100" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-primary text-white transform transition-transform duration-200 ease-in-out lg:translate-x-0">
            <!-- Logo -->
            <div class="p-6 border-b border-white/10">
                <a href="<?= SITE_URL ?>/admin/dashboard.php" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-accent to-accent-dark rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="font-bold block leading-tight">Water Prime</span>
                        <span class="text-xs text-white/60">Admin Panel</span>
                    </div>
                </a>
            </div>

            <!-- Navigation -->
            <nav class="p-4 space-y-1">
                <a href="dashboard.php"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors <?= $currentPage === 'dashboard' ? 'bg-accent text-white' : 'text-white/70 hover:bg-white/10' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    Dashboard
                </a>

                <a href="urunler.php"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors <?= $currentPage === 'urunler' ? 'bg-accent text-white' : 'text-white/70 hover:bg-white/10' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Ürünler
                </a>

                <a href="kategoriler.php"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors <?= $currentPage === 'kategoriler' ? 'bg-accent text-white' : 'text-white/70 hover:bg-white/10' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Kategoriler
                </a>

                <a href="hizmetler.php"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors <?= $currentPage === 'hizmetler' ? 'bg-accent text-white' : 'text-white/70 hover:bg-white/10' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Hizmetler
                </a>

                <a href="blog.php"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors <?= $currentPage === 'blog' ? 'bg-accent text-white' : 'text-white/70 hover:bg-white/10' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                    Blog
                </a>

                <a href="sss.php"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors <?= $currentPage === 'sss' ? 'bg-accent text-white' : 'text-white/70 hover:bg-white/10' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    SSS
                </a>

                <a href="talepler.php"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors <?= $currentPage === 'talepler' ? 'bg-accent text-white' : 'text-white/70 hover:bg-white/10' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Talepler
                    <?php if ($unreadCount > 0): ?>
                    <span class="ml-auto px-2 py-0.5 bg-red-500 text-white text-xs rounded-full"><?= $unreadCount ?></span>
                    <?php endif; ?>
                </a>

                <div class="pt-4 mt-4 border-t border-white/10">
                    <a href="ayarlar.php"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors <?= $currentPage === 'ayarlar' ? 'bg-accent text-white' : 'text-white/70 hover:bg-white/10' ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Ayarlar
                    </a>
                </div>
            </nav>

            <!-- User Info -->
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                        <span class="text-white font-semibold">
                            <?= strtoupper(substr($_SESSION['admin_fullname'] ?? $_SESSION['admin_username'], 0, 1)) ?>
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="block text-sm font-medium truncate"><?= htmlspecialchars($_SESSION['admin_fullname'] ?? $_SESSION['admin_username']) ?></span>
                        <span class="block text-xs text-white/50">Admin</span>
                    </div>
                    <a href="logout.php" class="p-2 rounded-lg hover:bg-white/10 transition-colors" title="Çıkış Yap">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Sidebar Overlay (Mobile) -->
        <div x-show="sidebarOpen" x-cloak
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm sticky top-0 z-30">
                <div class="flex items-center justify-between px-6 py-4">
                    <!-- Mobile Menu Toggle -->
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- Page Title -->
                    <h1 class="text-xl font-semibold text-primary hidden lg:block">
                        <?= $pageTitle ?? 'Dashboard' ?>
                    </h1>

                    <!-- Right Actions -->
                    <div class="flex items-center gap-4">
                        <!-- View Site -->
                        <a href="<?= SITE_URL ?>" target="_blank"
                           class="hidden sm:flex items-center gap-2 text-sm text-gray-600 hover:text-accent transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            Siteyi Gör
                        </a>

                        <!-- Notifications -->
                        <?php if ($unreadCount > 0): ?>
                        <a href="talepler.php" class="relative p-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span class="absolute top-0 right-0 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                                <?= $unreadCount ?>
                            </span>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6">
