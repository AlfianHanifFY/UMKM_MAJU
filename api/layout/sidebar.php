<!-- Sidebar -->
<aside class="main-sidebar sidebar-dark-info elevation-4">
    <a href="/dashboard" class="brand-link">
        <span class="brand-text font-weight-light ml-3">Teman Usaha</span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
                <?php
                function isActive($pattern) {
                    global $currentUri;
                    return strpos($currentUri, $pattern) !== false ? 'active' : '';
                }
                ?>

                <li class="nav-item">
                    <a href="/dashboard/cabang" class="nav-link <?= isActive('/cabang') ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Cabang</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/dashboard/gudang" class="nav-link <?= isActive('/order') ?>">
                        <i class="nav-icon fas fa-warehouse"></i>
                        <p>Gudang</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/dashboard/transaksi" class="nav-link <?= isActive('/transaksi') ?>">
                        <i class="nav-icon fas fa-money-bill"></i>
                        <p>Transaksi</p>
                    </a>
                </li>

                <!-- Dropdown Menu -->
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-edit"></i>
                            <p>
                                Laporan
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/dashboard/laporan/keuangan" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Keuangan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/dashboard/laporan/transaksi" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Transaksi</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/dashboard/laporan/aset" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Aset</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/dashboard/laporan/perkembangan" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Perkembangan</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <li class="nav-item">
                    <a href="/dashboard/edukasi" class="nav-link <?= isActive('/transaksi') ?>">
                        <i class="nav-icon fas fa-school"></i>
                        <p>Edukasi</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>