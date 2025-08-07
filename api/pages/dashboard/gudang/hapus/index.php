<?php
require_once __DIR__ . '/../../../../model/asset.php';

$asset_id = $_GET['id'] ?? null;
if (!$asset_id) {
    die("ID asset tidak ditemukan.");
}

try {
    delete_branch_asset($asset_id);
    header("Location: /dashboard/aset");
    exit;
} catch (Exception $e) {
    die("Gagal menghapus asset: " . $e->getMessage());
}