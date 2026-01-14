<?php
/**
 * Water Prime Su Arıtma - Admin Panel Giriş
 */

session_start();

// Zaten giriş yapmışsa dashboard'a yönlendir
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

require_once dirname(__DIR__) . '/config/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Lütfen kullanıcı adı ve şifre giriniz.';
    } else {
        try {
            $db = getDB();
            $stmt = $db->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Giriş başarılı
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_user_id'] = $user['id'];
                $_SESSION['admin_username'] = $user['username'];
                $_SESSION['admin_fullname'] = $user['full_name'];

                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Kullanıcı adı veya şifre hatalı.';
            }
        } catch (Exception $e) {
            $error = 'Bir hata oluştu. Lütfen tekrar deneyin.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetim Paneli - <?= SITE_NAME ?></title>
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
</head>
<body class="font-sans bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-accent to-accent-dark rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 18.55C9.79 17.93 7 13.18 7 9c0-2.76 2.24-5 5-5s5 2.24 5 5c0 4.18-2.79 8.93-5 11.55z"/>
                        <circle cx="12" cy="9" r="2.5"/>
                    </svg>
                </div>
                <div>
                    <span class="text-xl font-bold text-primary block leading-tight">Water Prime</span>
                    <span class="text-sm text-gray-500">Yönetim Paneli</span>
                </div>
            </div>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h1 class="text-2xl font-bold text-primary mb-6 text-center">Giriş Yap</h1>

            <?php if ($error): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <?= htmlspecialchars($error) ?>
                </div>
            </div>
            <?php endif; ?>

            <form method="POST" action="">
                <!-- Username -->
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                        Kullanıcı Adı
                    </label>
                    <input type="text" id="username" name="username" required
                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all"
                           placeholder="Kullanıcı adınız">
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Şifre
                    </label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all"
                           placeholder="••••••••">
                </div>

                <!-- Submit -->
                <button type="submit"
                        class="w-full bg-accent hover:bg-accent-dark text-white px-6 py-3 rounded-xl font-semibold transition-all hover:shadow-lg">
                    Giriş Yap
                </button>
            </form>
        </div>

        <!-- Back to Site -->
        <div class="text-center mt-6">
            <a href="<?= SITE_URL ?>" class="text-gray-500 hover:text-accent text-sm transition-colors">
                ← Siteye Dön
            </a>
        </div>

        <!-- Default Credentials (Development Only - Remove in production!) -->
        <div class="mt-8 p-4 bg-yellow-50 border border-yellow-200 rounded-xl text-sm">
            <p class="font-semibold text-yellow-800 mb-1">Varsayılan Giriş Bilgileri:</p>
            <p class="text-yellow-700">Kullanıcı: <code class="bg-yellow-100 px-1 rounded">admin</code></p>
            <p class="text-yellow-700">Şifre: <code class="bg-yellow-100 px-1 rounded">admin123</code></p>
            <p class="text-yellow-600 text-xs mt-2">⚠️ Canlıya almadan önce şifreyi değiştirin!</p>
        </div>
    </div>
</body>
</html>
