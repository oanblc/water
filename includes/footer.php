    </main>

    <!-- CTA Section -->
    <section class="bg-gradient-to-r from-primary to-primary-700 py-16">
        <div class="container text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Sağlıklı Su İçin Hemen Arayın</h2>
            <p class="text-white/80 text-lg mb-8 max-w-2xl mx-auto">
                Ücretsiz keşif ve fiyat teklifi için bizimle iletişime geçin. Uzman ekibimiz size en uygun çözümü sunacaktır.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?= CONTACT_PHONE_LINK ?>" class="inline-flex items-center justify-center gap-2 bg-white text-primary px-8 py-4 rounded-xl font-semibold hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <?= CONTACT_PHONE ?>
                </a>
                <a href="<?= SITE_URL ?>/teklif-al" class="inline-flex items-center justify-center gap-2 bg-accent hover:bg-accent-dark text-white px-8 py-4 rounded-xl font-semibold transition-colors">
                    Ücretsiz Teklif Al
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-primary text-white">
        <!-- Main Footer -->
        <div class="container py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- About -->
                <div>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-accent to-accent-dark rounded-xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 18.55C9.79 17.93 7 13.18 7 9c0-2.76 2.24-5 5-5s5 2.24 5 5c0 4.18-2.79 8.93-5 11.55z"/>
                                <circle cx="12" cy="9" r="2.5"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xl font-bold block leading-tight">Water Prime</span>
                            <span class="text-sm text-white/60">Su Arıtma</span>
                        </div>
                    </div>
                    <p class="text-white/70 mb-6">
                        <?= SITE_SLOGAN ?>. Ankara'da ev tipi su arıtma cihazları satış, montaj ve servis hizmetleri.
                    </p>
                    <!-- Social Media -->
                    <div class="flex gap-3">
                        <?php if (SOCIAL_FACEBOOK !== '#'): ?>
                        <a href="<?= SOCIAL_FACEBOOK ?>" target="_blank" rel="noopener" class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center hover:bg-accent transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/>
                            </svg>
                        </a>
                        <?php endif; ?>
                        <?php if (SOCIAL_INSTAGRAM !== '#'): ?>
                        <a href="<?= SOCIAL_INSTAGRAM ?>" target="_blank" rel="noopener" class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center hover:bg-accent transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        <?php endif; ?>
                        <?php if (SOCIAL_YOUTUBE !== '#'): ?>
                        <a href="<?= SOCIAL_YOUTUBE ?>" target="_blank" rel="noopener" class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center hover:bg-accent transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-6">Hızlı Linkler</h3>
                    <ul class="space-y-3">
                        <li>
                            <a href="<?= SITE_URL ?>/urunler" class="text-white/70 hover:text-accent transition-colors">Ürünler</a>
                        </li>
                        <li>
                            <a href="<?= SITE_URL ?>/hizmetler" class="text-white/70 hover:text-accent transition-colors">Hizmetler</a>
                        </li>
                        <li>
                            <a href="<?= SITE_URL ?>/hakkimizda" class="text-white/70 hover:text-accent transition-colors">Hakkımızda</a>
                        </li>
                        <li>
                            <a href="<?= SITE_URL ?>/blog" class="text-white/70 hover:text-accent transition-colors">Blog</a>
                        </li>
                        <li>
                            <a href="<?= SITE_URL ?>/sss" class="text-white/70 hover:text-accent transition-colors">Sık Sorulan Sorular</a>
                        </li>
                        <li>
                            <a href="<?= SITE_URL ?>/iletisim" class="text-white/70 hover:text-accent transition-colors">İletişim</a>
                        </li>
                    </ul>
                </div>

                <!-- Products -->
                <div>
                    <h3 class="text-lg font-semibold mb-6">Ürünlerimiz</h3>
                    <ul class="space-y-3">
                        <?php foreach (getCategories() as $category): ?>
                        <li>
                            <a href="<?= SITE_URL ?>/urunler/<?= e($category['slug']) ?>" class="text-white/70 hover:text-accent transition-colors">
                                <?= e($category['name']) ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="text-lg font-semibold mb-6">İletişim</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-accent mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="text-white/70"><?= CONTACT_ADDRESS ?></span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <a href="<?= CONTACT_PHONE_LINK ?>" class="text-white/70 hover:text-accent transition-colors"><?= CONTACT_PHONE ?></a>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <a href="mailto:<?= CONTACT_EMAIL ?>" class="text-white/70 hover:text-accent transition-colors"><?= CONTACT_EMAIL ?></a>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-white/70"><?= WORKING_HOURS ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="border-t border-white/10">
            <div class="container py-6">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-white/50 text-sm">
                        &copy; <?= date('Y') ?> <?= SITE_NAME ?>. Tüm hakları saklıdır.
                    </p>
                    <div class="flex gap-6 text-sm">
                        <a href="<?= SITE_URL ?>/gizlilik-politikasi" class="text-white/50 hover:text-white transition-colors">Gizlilik Politikası</a>
                        <a href="<?= SITE_URL ?>/kullanim-kosullari" class="text-white/50 hover:text-white transition-colors">Kullanım Koşulları</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp Button -->
    <a href="https://wa.me/<?= CONTACT_WHATSAPP ?>?text=Merhaba,%20su%20arıtma%20cihazı%20hakkında%20bilgi%20almak%20istiyorum."
       target="_blank"
       rel="noopener"
       class="fixed bottom-6 right-6 z-50 group"
       aria-label="WhatsApp ile iletişime geçin">
        <div class="relative">
            <!-- Pulse Animation -->
            <div class="absolute inset-0 bg-green-500 rounded-full animate-ping opacity-25"></div>
            <!-- Button -->
            <div class="relative w-14 h-14 bg-green-500 rounded-full flex items-center justify-center shadow-lg hover:bg-green-600 transition-all hover:scale-110 hover:shadow-xl">
                <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
            </div>
            <!-- Tooltip -->
            <div class="absolute bottom-full right-0 mb-2 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                <div class="bg-gray-900 text-white text-sm px-3 py-2 rounded-lg whitespace-nowrap">
                    WhatsApp ile yazın
                    <div class="absolute top-full right-4 border-8 border-transparent border-t-gray-900"></div>
                </div>
            </div>
        </div>
    </a>

    <!-- Back to Top Button -->
    <button x-data="{ show: false }"
            @scroll.window="show = window.scrollY > 500"
            x-show="show"
            x-cloak
            @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            class="fixed bottom-6 right-24 z-50 w-12 h-12 bg-primary/80 hover:bg-primary text-white rounded-full flex items-center justify-center shadow-lg transition-all hover:scale-110"
            aria-label="Yukarı çık">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
        </svg>
    </button>

    <!-- Custom JS -->
    <script src="<?= ASSETS_URL ?>/js/main.js"></script>
</body>
</html>
