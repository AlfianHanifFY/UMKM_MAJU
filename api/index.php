<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: /api/login.php");
    exit;
}

require_once __DIR__ . '/layout/layout.php';

// ambil URL path dari request
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = str_replace('/api/', '', $uri); // hilangkan prefix "/api/"
$uri = trim($uri, '/'); // bersihkan slash awal/akhir

// default ke "dashboard"
$page = $uri === '' ? 'dashboard' : $uri;

$allowedPages = ['dashboard', 'user', 'order'];
if (!in_array($page, $allowedPages)) {
    http_response_code(404);
    echo "404 Not Found";
    exit;
}

$contentPath = __DIR__ . '/pages/' . $page . '.php';
renderPage(ucfirst($page), $contentPath);

?>