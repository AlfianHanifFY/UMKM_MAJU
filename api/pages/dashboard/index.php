<?php
require_once __DIR__ . '/../../model/user.php';
require_once __DIR__ . '/../../model/dashboard.php';

// Ambil data user
$user = get_user($_COOKIE['token']);
$nama = $user['display_name'];

// Ambil data ringkasan dashboard
$summary = get_dashboard_summary($_COOKIE['token']);
?>
<div class="content">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>

    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Halo, <?php echo htmlspecialchars($nama); ?>!</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">

                <!-- Card Total Cabang -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?php echo $summary['total_cabang']; ?></h3>
                            <p>Total Cabang Usaha</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-store"></i>
                        </div>
                    </div>
                </div>

                <!-- Card Total Modal -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>Rp <?php echo number_format($summary['total_modal'], 0, ',', '.'); ?></h3>
                            <p>Total Modal</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-coins"></i>
                        </div>
                    </div>
                </div>

                <!-- Card Total Transaksi -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>Rp <?php echo number_format($summary['total_transaksi'], 0, ',', '.'); ?></h3>
                            <p>Total Transaksi</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-cash-register"></i>
                        </div>
                    </div>
                </div>

                <!-- Card Total Profit -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>Rp <?php echo number_format($summary['total_profit'], 0, ',', '.'); ?></h3>
                            <p>Total Profit</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.row -->

            <!-- Tabel Ringkasan Asset -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">Ringkasan Aset per Cabang</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-striped table-valign-middle">
                                <thead>
                                    <tr>
                                        <th>Cabang</th>
                                        <th>Jumlah Item</th>
                                        <th>Total Stok</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($summary['asset_overview'] as $row): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['branch_name']); ?></td>
                                            <td><?php echo $row['total_item']; ?></td>
                                            <td><?php echo $row['total_qty']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($summary['asset_overview'])): ?>
                                        <tr>
                                            <td colspan="3" class="text-center">Belum ada data aset</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.container-fluid -->
    </div>
</div>