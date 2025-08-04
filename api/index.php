<?php
$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Hilangkan prefix "api/" jika ada
$path = preg_replace('#^api/#', '', $path);

// Default ke dashboard
if ($path === '') {
    $path = 'dashboard';
}

// Halaman publik (tanpa auth & tanpa layout)
$publicPages = ['login', 'logout'];

// Jika bukan halaman publik dan belum login, redirect ke /login
if (!in_array($path, $publicPages) && !isset($_COOKIE['user'])) {
    header('Location: /login');
    exit;
}

// Tangani halaman login langsung tanpa layout
if ($path === 'login') {
    // Tangani POST login
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $validUser = ($email === 'admin@example.com' && $password === 'admin123');

        if ($validUser) {
            setcookie("user", $email, time() + 3600, "/");
            echo "<script>window.location.href='/dashboard';</script>";
            exit;
        } else {
            $error = "Email atau password salah.";
        }
    }

    require __DIR__ . '/pages/login/index.php';
    exit;
}

// Tangani logout
if ($path === 'logout') {
    setcookie("user", '', time() - 3600, "/");
    header('Location: /login');
    exit;
}

// Path ke file halaman
$pageFile = __DIR__ . '/pages/' . $path . '/index.php';

// Render halaman dengan layout jika ditemukan
if (file_exists($pageFile)) {
    require_once __DIR__ . '/layout/layout.php';
    renderPage(ucfirst(basename($path)), $pageFile);
} else {
    http_response_code(404);
    echo "404 Not Found";
}