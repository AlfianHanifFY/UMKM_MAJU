<?php
require_once __DIR__ . '/../../../../model/branch.php';


$branch_id = $_GET['id'] ?? null; 
if ($branch_id === null) {
    die("ID cabang tidak ditemukan.");
}
delete_branch($branch_id);
header("Location: /dashboard/cabang");
exit;