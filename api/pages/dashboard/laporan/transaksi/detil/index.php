<?php
require_once __DIR__ . '/../../../../../model/transaction.php';
require_once __DIR__ . '/../../../../../model/branch.php';

$transaction_id = $_GET['id'] ?? null;
$transaction_details = $transaction_id ? get_transaction_detail($transaction_id) : [];

if (!$transaction_details) {
    echo "<h3>Transaksi tidak ditemukan.</h3>";
    exit;
}

$transaction = $transaction_details[0]; // Header transaksi

$branch = get_branch_by_id($transaction['branch_id']);
?>


<section class="content ml-4">
    <div class="col-sm-6">
        <h1>Detil Transaksi</h1>
    </div>
    <div class="invoice p-3 mb-3">
        <!-- title row -->
        <div class="row">
            <div class="col-12">
                <h4>
                    <i class="fas fa-globe"></i> Teman Usaha
                    <small class="float-right">Date: <?= date('d/m/Y', strtotime($transaction['createdat'])) ?></small>
                </h4>
            </div>
        </div>

        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                From
                <address>
                    <strong>Cabang ID: <?= $transaction['branch_id'] ?></strong><br>
                    <?= $branch['branch_name'] ?><br>
                </address>
            </div>

            <div class="col-sm-4 invoice-col">
                To
                <address>
                    <strong>Pelanggan Umum</strong><br>

                </address>
            </div>

            <div class="col-sm-4 invoice-col">
                <b>Invoice ID #<?= $transaction['tu_transaction_id'] ?></b><br>
                <b>Status:</b> <?= $transaction['status'] ?><br>
                <b>Tanggal:</b> <?= date('d/m/Y', strtotime($transaction['createdat'])) ?><br>
            </div>
        </div>

        <!-- Table row -->
        <div class="row">
            <div class="col-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Qty</th>
                            <th>Product</th>
                            <th>Description</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transaction_details as $item): ?>
                            <tr>
                                <td><?= $item['quantity'] ?></td>
                                <td><?= htmlspecialchars($item['name']) ?></td>
                                <td>-</td>
                                <td>Rp<?= number_format($item['asset_total']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Total -->
        <div class="row">
            <div class="col-6">
                <p class="lead">Metode Pembayaran:</p>
                <p>Cash / Transfer (optional)</p>
            </div>

            <div class="col-6">
                <p class="lead">Total</p>
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th style="width:50%">Subtotal:</th>
                            <td>Rp<?= number_format($transaction['transaction_total']) ?></td>
                        </tr>
                        <tr>
                            <th>Total:</th>
                            <td>Rp<?= number_format($transaction['transaction_total']) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Print / Actions -->
        <div class="row no-print">
            <div class="col-12">
                <button class="btn btn-default" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
            </div>
        </div>
    </div>
</section>