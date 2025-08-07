<?php
require_once __DIR__ . '/../../../../model/asset.php';
require_once __DIR__ . '/../../../../model/branch.php';

$asset_id = $_GET['id'] ?? null;
if (!$asset_id) {
    die("ID asset tidak ditemukan.");
}

$asset = get_branch_asset_by_id($asset_id);
$branches = get_branch($_COOKIE['token'] ?? '');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $branch_id = $_POST['branch_id'] ?? '';
    $name = $_POST['asset_name'] ?? '';
    $quantity = $_POST['asset_quantity'] ?? 0;
    $buy_price = $_POST['asset_buy_price'] ?? 0;
    $sell_price = $_POST['asset_sell_price'] ?? 0;
    $fund_type = $_POST['asset_fund_type'] ?? '';
    $description = $_POST['asset_description'] ?? '';
    $status = isset($_POST['asset_status']) ? true : false;
    $photo = $_POST['asset_photo'] ?? '';

    if ($branch_id && $name) {
        try {
            update_branch_asset($asset_id,  $name, $quantity, $buy_price, $sell_price, $fund_type, $description, $status, $photo);
            $success = 'Asset berhasil diperbarui!';
            $asset = get_branch_asset_by_id($asset_id); // Refresh data
        } catch (Exception $e) {
            $error = 'Gagal memperbarui asset: ' . $e->getMessage();
        }
    } else {
        $error = 'Nama dan cabang wajib diisi.';
    }
}
?>

<section class="content m-4">
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">Edit Asset</h3>
        </div>
        <form method="post">
            <div class="card-body">
                <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="branchSelect">Cabang</label>
                    <select class="form-control select2" name="branch_id" id="branchSelect" required>
                        <?php foreach ($branches as $branch): ?>
                        <option value="<?= $branch['tu_user_branch_id'] ?>"
                            <?= $branch['tu_user_branch_id'] == $asset['branch_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($branch['branch_name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="assetName">Nama Asset</label>
                    <input type="text" class="form-control" name="asset_name" id="assetName"
                        value="<?= htmlspecialchars($asset['name']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="assetQty">Jumlah</label>
                    <input type="number" class="form-control" name="asset_quantity" id="assetQty"
                        value="<?= htmlspecialchars($asset['quantity']) ?>">
                </div>

                <div class="form-group">
                    <label for="buyPrice">Harga Beli</label>
                    <input type="number" class="form-control" name="asset_buy_price" id="buyPrice"
                        value="<?= htmlspecialchars($asset['buy_price']) ?>">
                </div>

                <div class="form-group">
                    <label for="sellPrice">Harga Jual</label>
                    <input type="number" class="form-control" name="asset_sell_price" id="sellPrice"
                        value="<?= htmlspecialchars($asset['sell_price']) ?>">
                </div>

                <div class="form-group">
                    <label for="fundType">Sumber Dana</label>
                    <input type="text" class="form-control" name="asset_fund_type" id="fundType"
                        value="<?= htmlspecialchars($asset['fund_type']) ?>">
                </div>

                <div class="form-group">
                    <label for="assetDesc">Deskripsi</label>
                    <textarea class="form-control" name="asset_description"
                        id="assetDesc"><?= htmlspecialchars($asset['description']) ?></textarea>
                </div>

                <div class="form-group">
                    <label>Status Aktif</label><br>
                    <input type="checkbox" name="asset_status" id="assetStatus"
                        <?= $asset['status'] ? 'checked' : '' ?>>
                    <label for="assetStatus">Aktif</label>
                </div>

                <div class="form-group">
                    <label for="photo">URL Foto</label>
                    <input type="text" class="form-control" name="asset_photo" id="photo"
                        value="<?= htmlspecialchars($asset['photo']) ?>">
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-info">Perbarui Asset</button>
            </div>
        </form>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js"></script>
<script>
$(function() {
    $('.select2').select2({
        theme: 'bootstrap4'
    });
});
</script>