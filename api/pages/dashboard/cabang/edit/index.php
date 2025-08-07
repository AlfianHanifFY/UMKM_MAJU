<?php
require_once __DIR__ . '/../../../../model/branch.php';

// Ambil branch_id dari URL
$branch_id = $_GET['id'] ?? null; // Pastikan ada parameter 'id' dalam URL
if ($branch_id === null) {
    die("ID cabang tidak ditemukan.");
}

// Ambil data cabang berdasarkan branch_id
$branch = get_branch_by_id($branch_id);

$error = '';
$success = '';

// Proses form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $name = trim($_POST['branch_name'] ?? '');
    $address = trim($_POST['branch_address'] ?? '');
    $description = trim($_POST['branch_description'] ?? '');
    $status = trim($_POST['branch_status'] ?? '');

    // Validasi
    if (!$name || !$address || !$description || !$status) {
        $error = 'Semua field wajib diisi.';
    } else {
        try {
            // Update data cabang
            update_branch($branch_id, $name, $address, $description, $status);
            $success = 'Cabang berhasil diperbarui!';
        } catch (Exception $e) {
            $error = 'Gagal memperbarui cabang: ' . $e->getMessage();
        }
    }
}

?>

<section class="content m-4">
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">Edit Cabang</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="post">
            <div class="card-body">
                <!-- Pesan error atau sukses -->
                <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="branch_name">Nama Cabang</label>
                    <input type="text" class="form-control" id="branch_name" name="branch_name"
                        value="<?= htmlspecialchars($branch['branch_name']) ?>" placeholder="Masukkan nama cabang">
                </div>
                <div class="form-group">
                    <label for="branch_address">Alamat</label>
                    <input type="text" class="form-control" id="branch_address" name="branch_address"
                        value="<?= htmlspecialchars($branch['branch_address']) ?>" placeholder="Masukkan alamat">
                </div>
                <div class="form-group">
                    <label for="branch_description">Deskripsi</label>
                    <input type="text" class="form-control" id="branch_description" name="branch_description"
                        value="<?= htmlspecialchars($branch['branch_description']) ?>" placeholder="Masukkan deskripsi">
                </div>
                <div class="form-group">
                    <label for="branch_status">Status</label>
                    <input type="text" class="form-control" id="branch_status" name="branch_status"
                        value="<?= htmlspecialchars($branch['status']) ?>" placeholder="Masukkan status">
                </div>

            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <button type="submit" class="btn btn-info">Perbarui Cabang</button>
            </div>
        </form>
    </div>
</section>