<?php
require_once __DIR__ . '/../../../../model/report.php'; // Sesuaikan path
require_once __DIR__ . '/../../../../model/branch.php'; // Sesuaikan path
$branches = get_branch($_COOKIE['token']);

$branch_id = $_GET['branch_id'] ?? ($branches[0]['tu_user_branch_id'] ?? null);
if (!$branch_id) {
    echo "Tidak ada cabang tersedia.";
    exit;
}

$report = get_financial_report_summary($branch_id);
$growth = get_income_growth($branch_id, 1);

$monthlyIncome = get_monthly_income($branch_id);

// Label bulan
$monthlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

// Data income per bulan untuk chart
$monthlyData = array_values(array_slice($monthlyIncome, 0, 12)); // contoh: ambil Jan–Jul
?>

<section class="content m-4">

    <!-- Dropdown Pilih Cabang -->
    <form method="GET" class="mb-4">
        <div class="form-group row">
            <label for="branch_id" class="col-sm-2 col-form-label">Pilih Cabang</label>
            <div class="col-sm-4">
                <select class="form-control" name="branch_id" id="branch_id" onchange="this.form.submit()">
                    <?php foreach ($branches as $branch): ?>
                        <option value="<?= $branch['tu_user_branch_id'] ?>"
                            <?= $branch['tu_user_branch_id'] == $branch_id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($branch['branch_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </form>

    <!-- Ringkasan Keuangan -->
    <div class="row">
        <div class="col-md-4">
            <div class="info-box bg-info">
                <span class="info-box-icon"><i class="fas fa-wallet"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Pendapatan</span>
                    <span class="info-box-number">Rp <?= number_format($report['total_income']) ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box bg-warning">
                <span class="info-box-icon"><i class="fas fa-boxes"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Modal</span>
                    <span class="info-box-number">Rp <?= number_format($report['total_cost']) ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box bg-success">
                <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Keuntungan</span>
                    <span class="info-box-number">Rp <?= number_format($report['total_profit']) ?></span>
                    <span class="progress-description"><?= round($growth, 2) ?>% pertumbuhan bulan ini</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik -->
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">Pertumbuhan Pendapatan Bulanan</h3>
        </div>
        <div class="card-body">
            <canvas id="monthlyIncomeChart" style="min-height:250px;height:250px;max-height:250px;width:100%;"></canvas>
        </div>
    </div>

    <!-- Tab Konten -->
    <div class="card">
        <div class="card-header d-flex p-0">
            <h3 class="card-title p-3">Ringkasan</h3>
            <ul class="nav nav-pills ml-auto p-2">
                <li class="nav-item"><a class="nav-link active" href="#tab_summary" data-toggle="tab">Summary</a></li>
                <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Detail</a></li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane active" id="tab_summary">
                    <p>Total Pendapatan: <strong>Rp <?= number_format($report['total_income']) ?></strong></p>
                    <p>Total Modal: <strong>Rp <?= number_format($report['total_cost']) ?></strong></p>
                    <p>Total Keuntungan: <strong>Rp <?= number_format($report['total_profit']) ?></strong></p>
                    <p>Pertumbuhan: <strong><?= round($growth, 2) ?>%</strong></p>
                </div>
                <div class="tab-pane" id="tab_2">
                    <p>Detail laporan akan ditambahkan di sini.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById("monthlyIncomeChart").getContext("2d");
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($monthlyLabels) ?>,
                datasets: [{
                    label: 'Pendapatan',
                    data: <?= json_encode($monthlyData) ?>,
                    backgroundColor: 'rgba(60,141,188,0.2)',
                    borderColor: 'rgba(60,141,188,1)',
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: 'rgba(60,141,188,1)',
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString();
                            }
                        }
                    }]
                }
            }
        });
    });
</script>