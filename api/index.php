<?php
session_start();
ob_start();

// Ambil path dari URL
$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$path = preg_replace('#^api/#', '', $path); // hapus prefix api/

// Default page
if ($path === '') {
    $path = 'dashboard';
}

// Halaman publik
$publicPages = ['login', 'logout', 'register', 'test'];

// Cek autentikasi
if (!in_array($path, $publicPages) && !isset($_COOKIE['token'])) {
    header('Location: /login');
    exit;
}

// Handle halaman publik
switch ($path) {
    case 'register':
        require __DIR__ . '/pages/register/index.php';
        exit;

    case 'login':
        require __DIR__ . '/pages/login/index.php';
        exit;

    case 'logout':
        setcookie("user", '', time() - 3600, "/");
        setcookie("token", '', time() - 3600, "/");
        header('Location: /login');
        exit;
}

// Inisialisasi data user di session untuk menghindari query berulang
if (!isset($_SESSION['user_data'])) {
    require_once __DIR__ . '/model/user.php';
    $_SESSION['user_data'] = get_user($_COOKIE['token']);
}

// Inisialisasi daftar cabang sekali saja
if (!isset($_SESSION['branch_list'])) {
    require_once __DIR__ . '/model/branch.php';
    $_SESSION['branch_list'] = get_branch($_COOKIE['token']);
}

// Routing ke halaman
$pageFile = __DIR__ . '/pages/' . $path . '/index.php';
if (file_exists($pageFile)) {
    require_once __DIR__ . '/layout/layout.php';
    renderPage(ucfirst(basename($path)), $pageFile);
} else {
    http_response_code(404);
    echo "404 Not Found";
}

ob_end_flush();
