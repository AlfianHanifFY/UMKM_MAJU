<?php
require_once __DIR__ . '/../../../../model/branch.php';
require_once __DIR__ . '/../../../../model/transaction.php';

$branches = get_branch($_COOKIE['token']);

$selected_branch_id = $_GET['branch_id'] ?? ($branches[0]['tu_user_branch_id'] ?? null);
$transactions = $selected_branch_id ? get_all_transactions($selected_branch_id) : [];
?>

<section class="content m-4">
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">Pilih Cabang</h3>
        </div>
        <div class="card-body">
            <form method="GET">
                <div class="form-group">
                    <label for="branchSelect">Cabang</label>
                    <select name="branch_id" class="form-control select2" style="width: 100%;" id="branchSelect"
                        onchange="this.form.submit()">
                        <?php foreach ($branches as $branch): ?>
                            <option value="<?= $branch['tu_user_branch_id'] ?>"
                                <?= $branch['tu_user_branch_id'] == $selected_branch_id ? 'selected' : '' ?>>
                                <?= htmlspecialchars($branch['branch_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">List Transaksi</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <?php if (empty($transactions)): ?>
                <p class="p-3">Tidak ada transaksi untuk cabang ini.</p>
            <?php else: ?>
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Detil</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $transaction): ?>
                            <tr>
                                <td><?= htmlspecialchars($transaction['tu_transaction_id']) ?></td>
                                <td><?= date('d-m-Y', strtotime($transaction['createdat'])) ?></td>
                                <td><?= number_format($transaction['total']) ?></td>
                                <td><span
                                        class="tag tag-<?= $transaction['status'] === 'approved' ? 'success' : ($transaction['status'] === 'pending' ? 'warning' : 'danger') ?>">
                                        <?= htmlspecialchars(ucfirst($transaction['status'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="/dashboard/laporan/transaksi/detil?id=<?= $transaction['tu_transaction_id'] ?>"
                                        class="btn btn-info">
                                        <i class="fas fa-book"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- Include JS for Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js"></script>

    <script>
        $(function() {
            $('.select2').select2({
                theme: 'bootstrap4'
            });
        });
    </script>
</section>