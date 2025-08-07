<?php
require_once __DIR__ . '/../../../model/branch.php';
require_once __DIR__ . '/../../../model/asset.php';

$token = $_COOKIE['token'] ?? '';
$branches = get_branch($token);

$branch_id = $_GET['branch_id'] ?? null;
if (!$branch_id && !empty($branches)) {
    $branch_id = $branches[0]['tu_user_branch_id'];
}

$assets = $branch_id ? get_branch_asset($branch_id) : [];
?>

<section class="content m-4">
    <!-- Dropdown untuk memilih cabang -->
    <form method="get" class="mb-3">
        <div class="form-group">
            <label for="branch_id">Pilih Cabang:</label>
            <select name="branch_id" id="branch_id" class="form-control" onchange="this.form.submit()">
                <?php foreach ($branches as $branch): ?>
                <option value="<?= $branch['tu_user_branch_id'] ?>"
                    <?= $branch['tu_user_branch_id'] == $branch_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($branch['branch_name']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <!-- Katalog Produk -->
    <div class="container-fluid mt-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Katalog</h3>
            </div>
            <div class="card-body p-2" style="max-height: 500px; overflow-y: auto;">
                <div class="row">
                    <?php foreach ($assets as $asset): ?>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="info-box shadow-sm cursor-pointer add-to-cart"
                            data-id="<?= $asset['tu_branch_asset_id'] ?>"
                            data-name="<?= htmlspecialchars($asset['name']) ?>"
                            data-price="<?= $asset['sell_price'] ?>">
                            <span
                                class="info-box-icon bg-<?= ['info','success','warning','danger'][$asset['tu_branch_asset_id'] % 4] ?>">
                                <i class="fas fa-box"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text"><?= htmlspecialchars($asset['name']) ?></span>
                                <span class="info-box-number">Rp
                                    <?= number_format($asset['sell_price'], 0, ',', '.') ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Keranjang -->
        <div class="card collapsed-card mt-3" id="cart-card">
            <div class="card-header">
                <h3 class="card-title">Keranjang</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-striped mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="cart-items">
                        <!-- Diisi via JS -->
                    </tbody>
                </table>
            </div>
            <div class="card-footer text-right">
                <strong>Total: Rp <span id="cart-total">0</span></strong>
                <form id="checkout-form" method="post" action="/dashboard/transaksi/controller" class="d-inline">
                    <input type="hidden" name="branch_id" value="<?= $branch_id ?>">
                    <input type="hidden" name="cart_data" id="cart-data">
                    <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
let cart = {};

function updateCartUI() {
    const tbody = document.getElementById('cart-items');
    tbody.innerHTML = '';
    let total = 0;

    for (const id in cart) {
        const item = cart[id];
        const row = document.createElement('tr');
        row.dataset.id = id;
        row.innerHTML = `
            <td class="item-name">${item.name}</td>
            <td class="item-qty">${item.qty}</td>
            <td class="item-price">${item.price.toLocaleString('id-ID')}</td>
            <td>${(item.price * item.qty).toLocaleString('id-ID')}</td>
            <td>
                <button class="btn btn-sm btn-success" onclick="changeQty(${id}, 1)">+</button>
                <button class="btn btn-sm btn-warning" onclick="changeQty(${id}, -1)">-</button>
                <button class="btn btn-sm btn-danger" onclick="removeItem(${id})">x</button>
            </td>
        `;
        tbody.appendChild(row);
        total += item.price * item.qty;
    }

    document.getElementById('cart-total').textContent = total.toLocaleString('id-ID');
}

function changeQty(id, delta) {
    if (cart[id]) {
        cart[id].qty += delta;
        if (cart[id].qty <= 0) {
            delete cart[id];
        }
        updateCartUI();
    }
}

function removeItem(id) {
    delete cart[id];
    updateCartUI();
}

document.querySelectorAll('.add-to-cart').forEach(el => {
    el.addEventListener('click', () => {
        const id = el.dataset.id;
        const name = el.dataset.name;
        const price = parseInt(el.dataset.price);

        if (cart[id]) {
            cart[id].qty += 1;
        } else {
            cart[id] = {
                name,
                price,
                qty: 1
            };
        }

        updateCartUI();
    });
});

document.getElementById('checkout-form').addEventListener('submit', function(e) {
    const cartItems = [];
    for (const id in cart) {
        const item = cart[id];
        cartItems.push({
            id: id,
            name: item.name,
            quantity: item.qty,
            price: item.price
        });
    }
    document.getElementById('cart-data').value = JSON.stringify(cartItems);
});
</script>

<style>
.cursor-pointer {
    cursor: pointer;
}
</style>