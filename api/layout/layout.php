<?php
function renderPage($title, $contentPath)
{
    $currentUri = $_SERVER['REQUEST_URI'] ?? '';
    ob_start();
    include $contentPath;
    $pageContent = ob_get_clean();

    include __DIR__ . '/header.php';
    include __DIR__ . '/sidebar.php';

    echo '<div class="content-wrapper">';
    echo $pageContent;
    echo '</div>';

    include __DIR__ . '/footer.php';
}