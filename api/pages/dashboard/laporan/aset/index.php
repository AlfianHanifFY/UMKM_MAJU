<?php
require_once __DIR__ . '/../../../../model/report.php';

$token = $_COOKIE['token'] ?? null;
$summary = get_asset_summary($token);

// Persiapkan data untuk Chart.js
$labels = [];
$data = [];

foreach ($summary['modal_per_branch'] as $row) {
    $labels[] = $row['branch_name'];
    $data[] = $row['modal_per_branch'];
}

// Konversi ke JSON untuk dipakai di JS
$chart_labels = json_encode($labels);
$chart_data = json_encode($data);
?>

<section class="content m-4">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>

    <!-- Content Wrapper -->
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">
                <i class="far fa-chart-bar"></i>
                Distribusi Modal Aset per Cabang
            </h3>
        </div>
        <div class="card-body">
            <canvas id="donutChart" style="min-height: 300px; height: 300px;"></canvas>
        </div>
    </div>

    <!-- Tabel Sisa Aset -->
    <div class="card card-outline card-success">
        <div class="card-header">
            <h3 class="card-title">Sisa Aset per Cabang</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Cabang</th>
                        <th>Nama Aset</th>
                        <th>Sisa Qty</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($summary['sisa_asset_per_branch'] as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['branch_name']) ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= (int)$row['sisa_qty'] ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabel Penjualan Aset -->
    <div class="card card-outline card-info">
        <div class="card-header">
            <h3 class="card-title">Aset Terjual per Cabang</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Cabang</th>
                        <th>Nama Aset</th>
                        <th>Total Terjual</th>
                        <th>Total Income</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($summary['penjualan_per_branch'] as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['branch_name']) ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= (int)$row['total_terjual'] ?></td>
                            <td>Rp<?= number_format((int)$row['total_income'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- JS -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById("donutChart").getContext("2d");
        const chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: <?= $chart_labels ?>,
                datasets: [{
                    data: <?= $chart_data ?>,
                    backgroundColor: ['#007bff', '#dc3545', '#ffc107', '#28a745', '#6610f2',
                        '#17a2b8'
                    ]
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: 'Distribusi Modal per Cabang'
                }
            }
        });
    });
</script>