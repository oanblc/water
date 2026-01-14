<?php
/**
 * Water Prime Su Arıtma - Ana Sayfa
 */

require_once 'config/config.php';

// Sayfa SEO ayarları
$pageTitle = SITE_NAME . ' | ' . SITE_SLOGAN . ' | Ankara Su Arıtma Cihazları';
$pageDescription = 'Ankara\'da su arıtma cihazı satış, montaj ve servis hizmetleri. Ev tipi su arıtma sistemleri, filtre değişimi. Ücretsiz keşif için hemen arayın!';
$canonicalUrl = SITE_URL;

// Verileri çek
$featuredProducts = getFeaturedProducts(4);
$services = getServices();
$testimonials = getTestimonials();
$latestPosts = getLatestPosts(3);

include INCLUDES_PATH . 'header.php';
?>

<!-- Hero Section -->
<section class="relative min-h-[600px] flex items-center overflow-hidden bg-gradient-to-br from-primary via-primary-600 to-primary-700">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
            <defs>
                <pattern id="water-pattern" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                    <circle cx="10" cy="10" r="2" fill="currentColor"/>
                </pattern>
            </defs>
            <rect fill="url(#water-pattern)" width="100%" height="100%"/>
        </svg>
    </div>

    <!-- Animated Water Drops -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="water-drop absolute top-20 left-10 w-4 h-4 bg-accent/30 rounded-full animate-float"></div>
        <div class="water-drop absolute top-40 right-20 w-6 h-6 bg-accent/20 rounded-full animate-float-delayed"></div>
        <div class="water-drop absolute bottom-40 left-1/4 w-3 h-3 bg-accent/40 rounded-full animate-float"></div>
        <div class="water-drop absolute top-60 right-1/3 w-5 h-5 bg-accent/25 rounded-full animate-float-delayed"></div>
    </div>

    <div class="container py-20 relative z-10">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Content -->
            <div class="text-white">
                <span class="inline-block px-4 py-2 bg-accent/20 rounded-full text-accent-light text-sm font-medium mb-6">
                    Ankara'nın Güvenilir Su Arıtma Uzmanı
                </span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                    Saf Su,<br>
                    <span class="text-accent">Sağlıklı Yaşam</span>
                </h1>
                <p class="text-lg md:text-xl text-white/80 mb-8 max-w-lg">
                    Ailenizin sağlığı için en kaliteli su arıtma sistemleri. Ücretsiz montaj ve 2 yıl garanti ile hizmetinizdeyiz.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="<?= SITE_URL ?>/urunler" class="inline-flex items-center justify-center gap-2 bg-white text-primary px-8 py-4 rounded-xl font-semibold hover:bg-gray-100 transition-all hover:shadow-lg">
                        Ürünleri İncele
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                    <a href="<?= SITE_URL ?>/teklif-al" class="inline-flex items-center justify-center gap-2 bg-accent hover:bg-accent-dark text-white px-8 py-4 rounded-xl font-semibold transition-all hover:shadow-lg hover:shadow-accent/30">
                        Hemen Teklif Al
                    </a>
                </div>

                <!-- Trust Badges -->
                <div class="flex flex-wrap gap-6 mt-10 pt-10 border-t border-white/20">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center">
                            <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="block text-white font-semibold">2 Yıl Garanti</span>
                            <span class="text-sm text-white/60">Tüm ürünlerde</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center">
                            <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="block text-white font-semibold">Ücretsiz Montaj</span>
                            <span class="text-sm text-white/60">Aynı gün kurulum</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center">
                            <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="block text-white font-semibold">7/24 Destek</span>
                            <span class="text-sm text-white/60">Her zaman yanınızdayız</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hero Image/Illustration -->
            <div class="hidden lg:block relative">
                <div class="relative w-full aspect-square max-w-lg mx-auto">
                    <!-- Water Drop Shape -->
                    <div class="absolute inset-0 bg-gradient-to-br from-accent/30 to-accent-dark/30 rounded-[40%_60%_60%_40%/60%_40%_60%_40%] animate-morph"></div>
                    <div class="absolute inset-4 bg-gradient-to-br from-accent/20 to-transparent rounded-[40%_60%_60%_40%/60%_40%_60%_40%] animate-morph-delayed"></div>

                    <!-- Center Icon -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-32 h-32 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 18.55C9.79 17.93 7 13.18 7 9c0-2.76 2.24-5 5-5s5 2.24 5 5c0 4.18-2.79 8.93-5 11.55z"/>
                                <circle cx="12" cy="9" r="2.5"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Wave Bottom -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
            <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="white"/>
        </svg>
    </div>
</section>

<!-- Featured Products Section -->
<section class="py-20 bg-white">
    <div class="container">
        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="inline-block px-4 py-1 bg-accent/10 rounded-full text-accent text-sm font-medium mb-4">Ürünlerimiz</span>
            <h2 class="text-3xl md:text-4xl font-bold text-primary mb-4">Öne Çıkan Ürünler</h2>
            <p class="text-gray-600">Eviniz için en kaliteli su arıtma sistemlerini keşfedin</p>
        </div>

        <!-- Products Grid -->
        <?php if (!empty($featuredProducts)): ?>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($featuredProducts as $product): ?>
            <article class="group bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-xl hover:shadow-gray-200/50 transition-all duration-300 hover:-translate-y-1">
                <!-- Product Image -->
                <div class="aspect-square bg-gray-50 relative overflow-hidden">
                    <?php if ($product['image']): ?>
                    <img src="<?= getImageUrl($product['image']) ?>"
                         alt="<?= e($product['name']) ?>"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                         loading="lazy">
                    <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <?php endif; ?>

                    <!-- Category Badge -->
                    <?php if ($product['category_name']): ?>
                    <span class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-xs font-medium text-primary">
                        <?= e($product['category_name']) ?>
                    </span>
                    <?php endif; ?>
                </div>

                <!-- Product Info -->
                <div class="p-5">
                    <h3 class="text-lg font-semibold text-primary mb-2 group-hover:text-accent transition-colors">
                        <?= e($product['name']) ?>
                    </h3>
                    <?php if ($product['short_description']): ?>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                        <?= e($product['short_description']) ?>
                    </p>
                    <?php endif; ?>
                    <a href="<?= SITE_URL ?>/urun/<?= e($product['slug']) ?>"
                       class="inline-flex items-center gap-2 text-accent font-medium text-sm group-hover:gap-3 transition-all">
                        Detayları Gör
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-12 text-gray-500">
            <p>Henüz ürün eklenmemiş.</p>
        </div>
        <?php endif; ?>

        <!-- View All Link -->
        <div class="text-center mt-10">
            <a href="<?= SITE_URL ?>/urunler" class="inline-flex items-center gap-2 bg-primary hover:bg-primary-600 text-white px-8 py-4 rounded-xl font-semibold transition-all hover:shadow-lg">
                Tüm Ürünleri Gör
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="py-20 bg-gray-50">
    <div class="container">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Content -->
            <div>
                <span class="inline-block px-4 py-1 bg-accent/10 rounded-full text-accent text-sm font-medium mb-4">Neden Biz?</span>
                <h2 class="text-3xl md:text-4xl font-bold text-primary mb-6">Neden Water Prime?</h2>
                <p class="text-gray-600 text-lg mb-8">
                    Yılların deneyimi ve müşteri memnuniyeti odaklı hizmet anlayışımızla, Ankara'nın en güvenilir su arıtma firması olmanın gururunu yaşıyoruz.
                </p>

                <div class="space-y-6">
                    <!-- Feature 1 -->
                    <div class="flex gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-accent/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-7 h-7 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-primary mb-1">Orijinal Ürün Garantisi</h3>
                            <p class="text-gray-600">Tüm ürünlerimiz orijinal ve TSE belgeli. 2 yıl resmi garanti.</p>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="flex gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-accent/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-7 h-7 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-primary mb-1">Hızlı Kurulum</h3>
                            <p class="text-gray-600">Sipariş verdiğiniz gün ücretsiz montaj. Profesyonel ekip.</p>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="flex gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-accent/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-7 h-7 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-primary mb-1">Satış Sonrası Destek</h3>
                            <p class="text-gray-600">7/24 teknik destek hattı. Periyodik bakım hatırlatması.</p>
                        </div>
                    </div>

                    <!-- Feature 4 -->
                    <div class="flex gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-accent/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-7 h-7 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-primary mb-1">Uygun Fiyat</h3>
                            <p class="text-gray-600">Piyasanın en uygun fiyatları. Taksit seçenekleri mevcut.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 gap-6">
                <div class="bg-white p-8 rounded-2xl shadow-sm text-center">
                    <div class="text-4xl md:text-5xl font-bold text-accent mb-2">500+</div>
                    <div class="text-gray-600">Mutlu Müşteri</div>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-sm text-center">
                    <div class="text-4xl md:text-5xl font-bold text-accent mb-2">5+</div>
                    <div class="text-gray-600">Yıllık Deneyim</div>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-sm text-center">
                    <div class="text-4xl md:text-5xl font-bold text-accent mb-2">%100</div>
                    <div class="text-gray-600">Memnuniyet</div>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-sm text-center">
                    <div class="text-4xl md:text-5xl font-bold text-accent mb-2">7/24</div>
                    <div class="text-gray-600">Destek</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="py-20 bg-white">
    <div class="container">
        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="inline-block px-4 py-1 bg-accent/10 rounded-full text-accent text-sm font-medium mb-4">Hizmetlerimiz</span>
            <h2 class="text-3xl md:text-4xl font-bold text-primary mb-4">Profesyonel Hizmetler</h2>
            <p class="text-gray-600">Satış öncesi ve sonrası tam destek</p>
        </div>

        <!-- Services Grid -->
        <?php if (!empty($services)): ?>
        <div class="grid md:grid-cols-3 gap-8">
            <?php
            $serviceIcons = [
                'montaj' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
                'bakim-servis' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/>',
                'filtre-degisimi' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>'
            ];
            foreach ($services as $service):
                $iconPath = $serviceIcons[$service['slug']] ?? $serviceIcons['montaj'];
            ?>
            <article class="group bg-gray-50 rounded-2xl p-8 hover:bg-gradient-to-br hover:from-accent hover:to-accent-dark transition-all duration-300">
                <!-- Icon -->
                <div class="w-16 h-16 rounded-2xl bg-accent/10 group-hover:bg-white/20 flex items-center justify-center mb-6 transition-colors">
                    <svg class="w-8 h-8 text-accent group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <?= $iconPath ?>
                    </svg>
                </div>

                <h3 class="text-xl font-semibold text-primary group-hover:text-white mb-3 transition-colors">
                    <?= e($service['name']) ?>
                </h3>
                <p class="text-gray-600 group-hover:text-white/80 mb-6 transition-colors">
                    <?= e($service['short_description']) ?>
                </p>
                <a href="<?= SITE_URL ?>/hizmet/<?= e($service['slug']) ?>"
                   class="inline-flex items-center gap-2 text-accent group-hover:text-white font-medium transition-colors">
                    Detaylı Bilgi
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </article>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-20 bg-gray-50">
    <div class="container">
        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="inline-block px-4 py-1 bg-accent/10 rounded-full text-accent text-sm font-medium mb-4">Müşteri Yorumları</span>
            <h2 class="text-3xl md:text-4xl font-bold text-primary mb-4">Müşterilerimiz Ne Diyor?</h2>
            <p class="text-gray-600">Yüzlerce mutlu müşterimizin deneyimleri</p>
        </div>

        <!-- Testimonials Grid -->
        <?php if (!empty($testimonials)): ?>
        <div class="grid md:grid-cols-3 gap-8">
            <?php foreach ($testimonials as $testimonial): ?>
            <article class="bg-white rounded-2xl p-8 shadow-sm">
                <!-- Stars -->
                <div class="flex gap-1 mb-4">
                    <?php for ($i = 0; $i < $testimonial['rating']; $i++): ?>
                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <?php endfor; ?>
                </div>

                <!-- Quote -->
                <blockquote class="text-gray-600 mb-6">
                    "<?= e($testimonial['content']) ?>"
                </blockquote>

                <!-- Author -->
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-accent/10 flex items-center justify-center">
                        <span class="text-accent font-semibold">
                            <?= mb_substr($testimonial['name'], 0, 1) ?>
                        </span>
                    </div>
                    <div>
                        <div class="font-semibold text-primary"><?= e($testimonial['name']) ?></div>
                        <?php if ($testimonial['title']): ?>
                        <div class="text-sm text-gray-500"><?= e($testimonial['title']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Blog Section -->
<?php if (!empty($latestPosts)): ?>
<section class="py-20 bg-white">
    <div class="container">
        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="inline-block px-4 py-1 bg-accent/10 rounded-full text-accent text-sm font-medium mb-4">Blog</span>
            <h2 class="text-3xl md:text-4xl font-bold text-primary mb-4">Son Yazılarımız</h2>
            <p class="text-gray-600">Su kalitesi ve sağlık hakkında faydalı bilgiler</p>
        </div>

        <!-- Posts Grid -->
        <div class="grid md:grid-cols-3 gap-8">
            <?php foreach ($latestPosts as $post): ?>
            <article class="group">
                <!-- Image -->
                <div class="aspect-video bg-gray-100 rounded-2xl overflow-hidden mb-4">
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
                </div>

                <!-- Date -->
                <time class="text-sm text-gray-500 mb-2 block">
                    <?= formatDate($post['published_at'], 'd F Y') ?>
                </time>

                <!-- Title -->
                <h3 class="text-xl font-semibold text-primary group-hover:text-accent transition-colors mb-3">
                    <a href="<?= SITE_URL ?>/blog/<?= e($post['slug']) ?>">
                        <?= e($post['title']) ?>
                    </a>
                </h3>

                <!-- Excerpt -->
                <?php if ($post['excerpt']): ?>
                <p class="text-gray-600 mb-4 line-clamp-2">
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
            </article>
            <?php endforeach; ?>
        </div>

        <!-- View All Link -->
        <div class="text-center mt-10">
            <a href="<?= SITE_URL ?>/blog" class="inline-flex items-center gap-2 text-primary hover:text-accent font-semibold transition-colors">
                Tüm Yazıları Gör
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<?php include INCLUDES_PATH . 'footer.php'; ?>
