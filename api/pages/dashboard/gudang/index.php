<?php
require_once __DIR__ . '/../../../model/branch.php';
require_once __DIR__ . '/../../../model/asset.php';

$branches = get_branch($_COOKIE['token']);

$selected_branch_id = $_GET['branch_id'] ?? ($branches[0]['tu_user_branch_id'] ?? null);

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Get assets and count for pagination
$assets = [];
$total_assets = 0;
if ($selected_branch_id) {
    // Check if the paginated function exists, otherwise use regular function
    if (function_exists('get_branch_asset_paginated')) {
        $assets = get_branch_asset_paginated($selected_branch_id, $limit, $offset);
        $total_assets = get_branch_asset_count($selected_branch_id);
    } else {
        // Fallback to regular function if paginated version doesn't exist
        $all_assets = get_branch_asset($selected_branch_id);
        $total_assets = count($all_assets);
        $assets = array_slice($all_assets, $offset, $limit);
    }
}

$total_pages = ceil($total_assets / $limit);

// Handle export requests
if (isset($_GET['export'])) {
    $export_type = $_GET['export'];
    $all_assets = $selected_branch_id ? get_branch_asset($selected_branch_id) : [];

    switch ($export_type) {
        case 'csv':
            exportCSV($all_assets);
            break;
        case 'excel':
            exportExcel($all_assets);
            break;
        case 'pdf':
            exportPDF($all_assets);
            break;
    }
    exit;
}

function exportCSV($assets)
{
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="assets_' . date('Y-m-d') . '.csv"');

    $output = fopen('php://output', 'w');

    // Header
    fputcsv($output, ['Nama', 'Harga Satuan', 'Harga Jual', 'Qty', 'Status']);

    // Data
    foreach ($assets as $asset) {
        fputcsv($output, [
            $asset['name'],
            $asset['buy_price'],
            $asset['sell_price'],
            $asset['quantity'],
            $asset['status'] ? 'Aktif' : 'Nonaktif'
        ]);
    }

    fclose($output);
}

function exportExcel($assets)
{
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="assets_' . date('Y-m-d') . '.xls"');

    echo "<table border='1'>";
    echo "<tr><th>Nama</th><th>Harga Satuan</th><th>Harga Jual</th><th>Qty</th><th>Status</th></tr>";

    foreach ($assets as $asset) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($asset['name']) . "</td>";
        echo "<td>" . number_format($asset['buy_price']) . "</td>";
        echo "<td>" . number_format($asset['sell_price']) . "</td>";
        echo "<td>" . $asset['quantity'] . "</td>";
        echo "<td>" . ($asset['status'] ? 'Aktif' : 'Nonaktif') . "</td>";
        echo "</tr>";
    }

    echo "</table>";
}

function exportPDF($assets)
{
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="assets_' . date('Y-m-d') . '.pdf"');

    // Simple HTML to PDF conversion (you might want to use a proper PDF library like TCPDF)
    echo "PDF export functionality needs a proper PDF library like TCPDF or FPDF";
}
?>

<section class="content m-4">
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">Cabang</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <form method="GET">
                <div class="form-group">
                    <label>Nama Cabang</label>
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

            <?php if ($selected_branch_id): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Menampilkan data untuk cabang:
                    <strong><?= htmlspecialchars(array_filter($branches, function ($b) use ($selected_branch_id) {
                                return $b['tu_user_branch_id'] == $selected_branch_id;
                            })[0]['branch_name'] ?? 'Unknown') ?></strong>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Asset (<?= $total_assets ?> item<?= $total_assets != 1 ? 's' : '' ?>)</h3>
        </div>
        <div class="card-body">
            <?php if (!$selected_branch_id): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    Silakan pilih cabang terlebih dahulu untuk melihat data asset.
                </div>
            <?php else: ?>
                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <div class="row">
                        <div class="col-sm-12 col-md-6 m-2">
                            <div class="dt-buttons btn-group flex-wrap">
                                <a href="/dashboard/gudang/tambah<?= $selected_branch_id ? '?branch_id=' . $selected_branch_id : '' ?>"
                                    class="btn btn-info buttons-copy buttons-html5" tabindex="0" aria-controls="example1"
                                    type="button">
                                    <i class="fas fa-plus"></i> <span>Tambah</span>
                                </a>
                                <button class="btn btn-secondary buttons-csv buttons-html5" tabindex="0"
                                    aria-controls="example1" type="button" onclick="exportData('csv')">
                                    <i class="fas fa-file-csv"></i> <span>CSV</span>
                                </button>
                                <button class="btn btn-secondary buttons-excel buttons-html5" tabindex="0"
                                    aria-controls="example1" type="button" onclick="exportData('excel')">
                                    <i class="fas fa-file-excel"></i> <span>Excel</span>
                                </button>
                                <button class="btn btn-secondary buttons-pdf buttons-html5" tabindex="0"
                                    aria-controls="example1" type="button" onclick="exportData('pdf')">
                                    <i class="fas fa-file-pdf"></i> <span>PDF</span>
                                </button>
                                <button class="btn btn-secondary buttons-print" tabindex="0" aria-controls="example1"
                                    type="button" onclick="printTable()">
                                    <i class="fas fa-print"></i> <span>Print</span>
                                </button>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <table id="example1" class="table table-bordered table-striped dataTable dtr-inline"
                                aria-describedby="example1_info">
                                <thead>
                                    <tr>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
                                            Nama</th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
                                            Harga Satuan</th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
                                            Harga Jual</th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
                                            Qty</th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
                                            Status</th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($assets)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                <i class="fas fa-box-open fa-2x text-muted mb-2"></i><br>
                                                Tidak ada data asset untuk cabang ini
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($assets as $asset): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($asset['name']) ?></td>
                                                <td>Rp <?= number_format($asset['buy_price'], 0, ',', '.') ?></td>
                                                <td>Rp <?= number_format($asset['sell_price'], 0, ',', '.') ?></td>
                                                <td>
                                                    <span
                                                        class="badge <?= $asset['quantity'] > 0 ? 'badge-success' : 'badge-danger' ?>">
                                                        <?= $asset['quantity'] ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge <?= $asset['status'] ? 'badge-success' : 'badge-secondary' ?>">
                                                        <?= $asset['status'] ? 'Aktif' : 'Nonaktif' ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="/dashboard/gudang/view?id=<?= $asset['tu_branch_asset_id'] ?>"
                                                            class="btn btn-info btn-sm" title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="/dashboard/gudang/edit?id=<?= $asset['tu_branch_asset_id'] ?>"
                                                            class="btn btn-warning btn-sm" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="/dashboard/gudang/hapus?id=<?= $asset['tu_branch_asset_id'] ?>"
                                                            class="btn btn-danger btn-sm" title="Delete"
                                                            onclick="return confirm('Yakin ingin menghapus asset ini?')">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <div class="row">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">
                                    Showing <?= min($offset + 1, $total_assets) ?> to
                                    <?= min($offset + $limit, $total_assets) ?> of <?= $total_assets ?> entries
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate paging_simple_numbers" id="example1_paginate">
                                    <ul class="pagination">
                                        <!-- Previous button -->
                                        <li class="paginate_button page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                            <a href="<?= $page > 1 ? '?' . http_build_query(array_merge($_GET, ['page' => $page - 1])) : '#' ?>"
                                                aria-controls="example1" class="page-link">Previous</a>
                                        </li>

                                        <?php
                                        // Calculate pagination range
                                        $start_page = max(1, $page - 2);
                                        $end_page = min($total_pages, $page + 2);

                                        // Show first page if not in range
                                        if ($start_page > 1): ?>
                                            <li class="paginate_button page-item">
                                                <a href="?<?= http_build_query(array_merge($_GET, ['page' => 1])) ?>"
                                                    aria-controls="example1" class="page-link">1</a>
                                            </li>
                                            <?php if ($start_page > 2): ?>
                                                <li class="paginate_button page-item disabled">
                                                    <span class="page-link">...</span>
                                                </li>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <!-- Page numbers -->
                                        <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                                            <li class="paginate_button page-item <?= $i == $page ? 'active' : '' ?>">
                                                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"
                                                    aria-controls="example1" class="page-link"><?= $i ?></a>
                                            </li>
                                        <?php endfor; ?>

                                        <!-- Show last page if not in range -->
                                        <?php if ($end_page < $total_pages): ?>
                                            <?php if ($end_page < $total_pages - 1): ?>
                                                <li class="paginate_button page-item disabled">
                                                    <span class="page-link">...</span>
                                                </li>
                                            <?php endif; ?>
                                            <li class="paginate_button page-item">
                                                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $total_pages])) ?>"
                                                    aria-controls="example1" class="page-link"><?= $total_pages ?></a>
                                            </li>
                                        <?php endif; ?>

                                        <!-- Next button -->
                                        <li class="paginate_button page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                                            <a href="<?= $page < $total_pages ? '?' . http_build_query(array_merge($_GET, ['page' => $page + 1])) : '#' ?>"
                                                aria-controls="example1" class="page-link">Next</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>


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
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                placeholder: '-- Pilih Cabang --',
                allowClear: false
            });

            // Handle branch selection change - ROBUST METHOD
            $('#branchSelect').on('select2:select', function(e) {
                const selectedBranchId = e.params.data.id;
                console.log('Branch selected:', selectedBranchId); // Debug log

                if (selectedBranchId && selectedBranchId !== '') {
                    // Show loading indicator
                    const currentText = $(this).next('.select2').find('.select2-selection__rendered')
                        .html();
                    $(this).next('.select2').find('.select2-selection__rendered').html(
                        '<i class="fas fa-spinner fa-spin"></i> Loading...');

                    // Build new URL
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('branch_id', selectedBranchId);
                    currentUrl.searchParams.delete('page'); // Reset pagination

                    // Navigate to new URL
                    window.location.href = currentUrl.toString();
                }
            });

            // Alternative fallback method for change event
            $('#branchSelect').on('change', function() {
                const selectedBranchId = $(this).val();
                console.log('Branch changed (fallback):', selectedBranchId); // Debug log

                if (selectedBranchId && selectedBranchId !== '') {
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('branch_id', selectedBranchId);
                    currentUrl.searchParams.delete('page'); // Reset pagination

                    window.location.href = currentUrl.toString();
                }
            });

            // Simple search functionality (client-side)
            $('#searchInput').on('keyup', function() {
                const value = $(this).val().toLowerCase();
                $('#example1 tbody tr').each(function() {
                    const text = $(this).text().toLowerCase();
                    $(this).toggle(text.indexOf(value) > -1);
                });
            });
        });

        // Export functions
        function exportData(type) {
            const branchId = $('#branchSelect').val();
            if (!branchId || branchId === '') {
                alert('Pilih cabang terlebih dahulu');
                return;
            }

            const url = new URL(window.location.href);
            url.searchParams.set('export', type);
            url.searchParams.set('branch_id', branchId);

            // Open in new window to avoid losing current page
            window.open(url.toString(), '_blank');
        }

        // Print function
        function printTable() {
            const branchName = $('#branchSelect option:selected').text();
            if (!branchName || branchName === '-- Pilih Cabang --') {
                alert('Pilih cabang terlebih dahulu');
                return;
            }

            const tableHtml = $('#example1').clone();
            // Remove action column for printing
            tableHtml.find('th:last-child, td:last-child').remove();

            const printContent = `
                <html>
                <head>
                    <title>Asset Report - ${branchName}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 12px; }
                        th { background-color: #f2f2f2; font-weight: bold; }
                        h1 { text-align: center; margin-bottom: 10px; }
                        .info { text-align: center; margin-bottom: 20px; color: #666; }
                        @media print {
                            body { margin: 0; }
                        }
                    </style>
                </head>
                <body>
                    <h1>Asset Report</h1>
                    <div class="info">
                        <strong>Cabang:</strong> ${branchName}<br>
                        <strong>Generated on:</strong> ${new Date().toLocaleDateString('id-ID')}
                    </div>
                    ${tableHtml[0].outerHTML}
                </body>
                </html>
            `;

            const printWindow = window.open('', '_blank');
            printWindow.document.write(printContent);
            printWindow.document.close();
            printWindow.print();
        }
    </script>
</section>