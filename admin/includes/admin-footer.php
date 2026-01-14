            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-100 px-6 py-4">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-2 text-sm text-gray-500">
                    <span>&copy; <?= date('Y') ?> <?= SITE_NAME ?>. Tüm hakları saklıdır.</span>
                    <span>Yönetim Paneli v1.0</span>
                </div>
            </footer>
        </div>
    </div>

    <script>
        // Flash messages auto-hide
        document.addEventListener('DOMContentLoaded', function() {
            const flashMessages = document.querySelectorAll('.flash-message');
            flashMessages.forEach(msg => {
                setTimeout(() => {
                    msg.style.opacity = '0';
                    msg.style.transform = 'translateY(-10px)';
                    setTimeout(() => msg.remove(), 300);
                }, 5000);
            });
        });

        // Confirm delete
        function confirmDelete(message) {
            return confirm(message || 'Bu öğeyi silmek istediğinizden emin misiniz?');
        }
    </script>
</body>
</html>
