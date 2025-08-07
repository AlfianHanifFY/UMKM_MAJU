<?php
require_once __DIR__ . '/../../../../model/transaction.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $branch_id = $_POST['branch_id'];
    $cart_data = json_decode($_POST['cart_data'], true);

    $total = 0;
    foreach ($cart_data as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    $transaction_id = create_transaction($branch_id, $total);

    foreach ($cart_data as $item) {
        add_asset_to_transaction($transaction_id, $item['id'], $item['quantity'], $item['price'] * $item['quantity']);
    }

    header("Location: /dashboard/transaksi");
    exit;
}