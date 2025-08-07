<?php 

require_once __DIR__ . '/../../../../model/branch.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $name = $_POST['branch_name'] ?? '';
    $address = $_POST['branch_address'] ?? '';
    $description = $_POST['branch_description'] ?? '';
    $status = $_POST['branch_status'] ?? '';
    
    $token = $_COOKIE['token'] ?? ''; 

    
    if ($name && $address && $description && $status && $token) {
        try {
            
            add_branch($token, $name, $address, $description, $status);
            echo "<p class='alert alert-success'>Cabang berhasil ditambahkan!</p>";
        } catch (Exception $e) {
            echo "<p class='alert alert-danger'>Error: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p class='alert alert-warning'>Semua field wajib diisi.</p>";
    }
}
?>

<section class="content m-4">
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">Tambah Cabang</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="post">
            <div class="card-body">
                <div class="form-group">
                    <label for="namaCabang">Nama Cabang</label>
                    <input type="text" class="form-control" name="branch_name" id="namaCabang"
                        placeholder="Masukkan nama cabang">
                </div>
                <div class="form-group">
                    <label for="alamatCabang">Alamat</label>
                    <input type="text" class="form-control" name="branch_address" id="alamatCabang"
                        placeholder="Masukkan alamat">
                </div>
                <div class="form-group">
                    <label for="deskripsiCabang">Deskripsi</label>
                    <input type="text" class="form-control" name="branch_description" id="deskripsiCabang"
                        placeholder="Masukkan deskripsi">
                </div>
                <div class="form-group">
                    <label for="statusCabang">Status</label>
                    <input type="text" class="form-control" name="branch_status" id="statusCabang"
                        placeholder="Masukkan status">
                </div>

            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <button type="submit" class="btn btn-info">Submit</button>
            </div>
        </form>
    </div>
</section>