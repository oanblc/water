<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- SEO Meta Tags -->
    <title><?= e($pageTitle ?? SITE_NAME . ' | ' . SITE_SLOGAN) ?></title>
    <meta name="description" content="<?= e($pageDescription ?? 'Ankara\'nın güvenilir su arıtma uzmanı. Ev tipi su arıtma cihazları, montaj, bakım ve servis hizmetleri.') ?>">
    <meta name="keywords" content="<?= e($pageKeywords ?? 'su arıtma, su arıtma cihazı, ankara su arıtma, ev tipi su arıtma, su filtresi') ?>">
    <meta name="author" content="Water Prime Su Arıtma">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= e($canonicalUrl ?? SITE_URL) ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= e($canonicalUrl ?? SITE_URL) ?>">
    <meta property="og:title" content="<?= e($pageTitle ?? SITE_NAME) ?>">
    <meta property="og:description" content="<?= e($pageDescription ?? SITE_SLOGAN) ?>">
    <meta property="og:image" content="<?= ASSETS_URL ?>/images/og-image.jpg">
    <meta property="og:locale" content="tr_TR">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($pageTitle ?? SITE_NAME) ?>">
    <meta name="twitter:description" content="<?= e($pageDescription ?? SITE_SLOGAN) ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?= ASSETS_URL ?>/images/favicon.svg">
    <link rel="apple-touch-icon" href="<?= ASSETS_URL ?>/images/apple-touch-icon.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                container: {
                    center: true,
                    padding: {
                        DEFAULT: '1rem',
                        sm: '1.5rem',
                        lg: '2rem'
                    }
                },
                screens: {
                    'sm': '640px',
                    'md': '768px',
                    'lg': '1024px',
                    'xl': '1280px',
                    '2xl': '1400px'
                },
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#0A2647',
                            50: '#E8F4FC',
                            100: '#C5E4F7',
                            200: '#8CC9EF',
                            300: '#53AEE7',
                            400: '#1A93DF',
                            500: '#0A2647',
                            600: '#081F3A',
                            700: '#06172C',
                            800: '#04101F',
                            900: '#020811'
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

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/style.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Schema.org LocalBusiness -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "LocalBusiness",
        "name": "<?= SITE_NAME ?>",
        "description": "Ankara'da su arıtma cihazı satış, montaj ve servis hizmetleri",
        "url": "<?= SITE_URL ?>",
        "telephone": "<?= CONTACT_PHONE ?>",
        "email": "<?= CONTACT_EMAIL ?>",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Ankara",
            "addressCountry": "TR"
        },
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": "39.9334",
            "longitude": "32.8597"
        },
        "openingHours": "Mo-Fr 09:00-18:00",
        "priceRange": "₺₺",
        "image": "<?= ASSETS_URL ?>/images/logo.svg",
        "sameAs": [
            "<?= SOCIAL_FACEBOOK ?>",
            "<?= SOCIAL_INSTAGRAM ?>"
        ]
    }
    </script>

    <!-- Google Tag Manager (canlıda aktif edilecek) -->
    <?php if ($gtmId = getSetting('gtm_id')): ?>
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','<?= e($gtmId) ?>');</script>
    <?php endif; ?>
</head>
<body class="font-sans text-gray-700 antialiased" x-data="{ mobileMenuOpen: false }">
    <!-- GTM noscript -->
    <?php if ($gtmId = getSetting('gtm_id')): ?>
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?= e($gtmId) ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <?php endif; ?>

    <!-- Top Bar -->
    <div class="bg-primary text-white py-2 hidden md:block">
        <div class="container">
            <div class="flex justify-between items-center text-sm">
                <div class="flex items-center gap-6">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <?= CONTACT_ADDRESS ?>
                    </span>
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <?= WORKING_HOURS ?>
                    </span>
                </div>
                <div class="flex items-center gap-4">
                    <a href="mailto:<?= CONTACT_EMAIL ?>" class="flex items-center gap-2 hover:text-accent transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <?= CONTACT_EMAIL ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50" x-data="{ scrolled: false }" @scroll.window="scrolled = window.scrollY > 50">
        <div class="container">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <a href="<?= SITE_URL ?>" class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-accent to-accent-dark rounded-xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 18.55C9.79 17.93 7 13.18 7 9c0-2.76 2.24-5 5-5s5 2.24 5 5c0 4.18-2.79 8.93-5 11.55z"/>
                            <circle cx="12" cy="9" r="2.5"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-xl font-bold text-primary block leading-tight">Water Prime</span>
                        <span class="text-sm text-gray-500">Su Arıtma</span>
                    </div>
                </a>

                <!-- Desktop Navigation -->
                <nav class="hidden lg:flex items-center gap-8">
                    <a href="<?= SITE_URL ?>" class="text-gray-700 hover:text-accent font-medium transition-colors <?= isActivePage('anasayfa') ? 'text-accent' : '' ?>">
                        Ana Sayfa
                    </a>
                    <div class="relative group">
                        <a href="<?= SITE_URL ?>/urunler" class="text-gray-700 hover:text-accent font-medium transition-colors flex items-center gap-1 <?= isActivePage('urunler') ? 'text-accent' : '' ?>">
                            Ürünler
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </a>
                        <!-- Dropdown -->
                        <div class="absolute top-full left-0 pt-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            <div class="bg-white rounded-xl shadow-xl border border-gray-100 py-2 min-w-[220px]">
                                <?php foreach (getCategories() as $category): ?>
                                <a href="<?= SITE_URL ?>/urunler/<?= e($category['slug']) ?>" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-accent transition-colors">
                                    <?= e($category['name']) ?>
                                </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <a href="<?= SITE_URL ?>/hizmetler" class="text-gray-700 hover:text-accent font-medium transition-colors <?= isActivePage('hizmetler') ? 'text-accent' : '' ?>">
                        Hizmetler
                    </a>
                    <a href="<?= SITE_URL ?>/hakkimizda" class="text-gray-700 hover:text-accent font-medium transition-colors <?= isActivePage('hakkimizda') ? 'text-accent' : '' ?>">
                        Hakkımızda
                    </a>
                    <a href="<?= SITE_URL ?>/blog" class="text-gray-700 hover:text-accent font-medium transition-colors <?= isActivePage('blog') ? 'text-accent' : '' ?>">
                        Blog
                    </a>
                    <a href="<?= SITE_URL ?>/sss" class="text-gray-700 hover:text-accent font-medium transition-colors <?= isActivePage('sss') ? 'text-accent' : '' ?>">
                        SSS
                    </a>
                    <a href="<?= SITE_URL ?>/iletisim" class="text-gray-700 hover:text-accent font-medium transition-colors <?= isActivePage('iletisim') ? 'text-accent' : '' ?>">
                        İletişim
                    </a>
                </nav>

                <!-- CTA Buttons -->
                <div class="hidden md:flex items-center gap-4">
                    <!-- Phone -->
                    <a href="<?= CONTACT_PHONE_LINK ?>" class="flex items-center gap-2 text-primary hover:text-accent transition-colors group">
                        <div class="w-10 h-10 rounded-full bg-accent/10 flex items-center justify-center group-hover:bg-accent/20 transition-colors">
                            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 block">Hemen Arayın</span>
                            <span class="font-semibold"><?= CONTACT_PHONE ?></span>
                        </div>
                    </a>

                    <!-- Teklif Al Button -->
                    <a href="<?= SITE_URL ?>/teklif-al" class="bg-accent hover:bg-accent-dark text-white px-6 py-3 rounded-xl font-semibold transition-all hover:shadow-lg hover:shadow-accent/30">
                        Teklif Al
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="lg:hidden border-t border-gray-100 bg-white">
            <div class="container py-4">
                <nav class="flex flex-col gap-2">
                    <a href="<?= SITE_URL ?>" class="py-3 px-4 rounded-lg hover:bg-gray-50 font-medium transition-colors">Ana Sayfa</a>
                    <a href="<?= SITE_URL ?>/urunler" class="py-3 px-4 rounded-lg hover:bg-gray-50 font-medium transition-colors">Ürünler</a>
                    <a href="<?= SITE_URL ?>/hizmetler" class="py-3 px-4 rounded-lg hover:bg-gray-50 font-medium transition-colors">Hizmetler</a>
                    <a href="<?= SITE_URL ?>/hakkimizda" class="py-3 px-4 rounded-lg hover:bg-gray-50 font-medium transition-colors">Hakkımızda</a>
                    <a href="<?= SITE_URL ?>/blog" class="py-3 px-4 rounded-lg hover:bg-gray-50 font-medium transition-colors">Blog</a>
                    <a href="<?= SITE_URL ?>/sss" class="py-3 px-4 rounded-lg hover:bg-gray-50 font-medium transition-colors">SSS</a>
                    <a href="<?= SITE_URL ?>/iletisim" class="py-3 px-4 rounded-lg hover:bg-gray-50 font-medium transition-colors">İletişim</a>
                </nav>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <a href="<?= CONTACT_PHONE_LINK ?>" class="flex items-center gap-3 py-3 px-4 rounded-lg bg-accent/10 text-accent font-semibold mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <?= CONTACT_PHONE ?>
                    </a>
                    <a href="<?= SITE_URL ?>/teklif-al" class="block text-center bg-accent hover:bg-accent-dark text-white px-6 py-3 rounded-xl font-semibold transition-colors">
                        Teklif Al
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
