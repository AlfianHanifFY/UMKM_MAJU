<section class="content m-4">

    <form action="simple-results.html">
        <div class="input-group">
            <input type="search" class="form-control form-control-lg" placeholder="Cari Barang">
            <div class="input-group-append">
                <button type="submit" class="btn btn-lg btn-default">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </form>
    <?php
$n = 20;
?>
    <div class="container-fluid mt-2">
        <!-- Katalog Produk -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Katalog</h3>
            </div>
            <div class="card-body p-2" style="max-height: 500px; overflow-y: auto;">
                <div class="row">
                    <?php for ($i = 1; $i <= $n; $i++): ?>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="#" class="text-dark text-decoration-none add-to-cart" data-id="<?= $i ?>"
                            data-name="Item <?= $i ?>" data-price="<?= 10000 + $i * 1000 ?>">
                            <div class="info-box shadow-sm">
                                <span class="info-box-icon bg-<?= ['info','success','warning','danger'][$i % 4] ?>">
                                    <i class="fas fa-box"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Item <?= $i ?></span>
                                    <span class="info-box-number">Rp
                                        <?= number_format(10000 + $i * 1000, 0, ',', '.') ?></span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>

        <!-- Keranjang Expandable -->
        <div class="card collapsed-card mt-3" id="cart-card">
            <div class="card-header">
                <h3 class="card-title">Keranjang</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                            class="fas fa-plus"></i></button>
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
                        <!-- Diisi lewat JavaScript -->
                    </tbody>
                </table>
            </div>
            <div class="card-footer text-right">
                <strong>Total: Rp <span id="cart-total">0</span></strong>
            </div>
        </div>
    </div>


</section>