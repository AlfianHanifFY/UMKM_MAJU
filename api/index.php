<?php
ob_start();
$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

$path = preg_replace('#^api/#', '', $path);

if ($path === '') {
    $path = 'dashboard';
}

$publicPages = ['login', 'logout', 'register', 'test'];

if (!in_array($path, $publicPages) && !isset($_COOKIE['token'])) {
    header('Location: /login');
    exit;
}
if ($path === 'register') {
    require __DIR__ . '/pages/register/index.php';
    exit;
}

if ($path === 'login') {
    require __DIR__ . '/pages/login/index.php';
    exit;
}

if ($path === 'logout') {
    setcookie("user", '', time() - 3600, "/");
    header('Location: /login');
    exit;
}

$pageFile = __DIR__ . '/pages/' . $path . '/index.php';

if (file_exists($pageFile)) {
    require_once __DIR__ . '/layout/layout.php';
    renderPage(ucfirst(basename($path)), $pageFile);
} else {
    http_response_code(404);
    echo "404 Not Found";
}
ob_end_flush();
