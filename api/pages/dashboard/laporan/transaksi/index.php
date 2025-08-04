<section class="content m-4">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">List Transaksi</h3>

            <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                    <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Detil</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>183</td>
                        <td>John Doe</td>
                        <td>11-7-2014</td>
                        <td><span class="tag tag-success">Approved</span></td>
                        <td><a href="/dashboard/laporan/transaksi/detil" type="button" class="btn btn-info">
                                <i class="fas fa-book"></i>
                            </a></td>
                    </tr>
                    <tr>
                        <td>219</td>
                        <td>Alexander Pierce</td>
                        <td>11-7-2014</td>
                        <td><span class="tag tag-warning">Pending</span></td>
                        <td><a href="/dashboard/laporan/transaksi/detil" type="button" class="btn btn-info">
                                <i class="fas fa-book"></i>
                            </a></td>
                    </tr>
                    <tr>
                        <td>657</td>
                        <td>Bob Doe</td>
                        <td>11-7-2014</td>
                        <td><span class="tag tag-info">Approved</span></td>
                        <td><a href="/dashboard/laporan/transaksi/detil" type="button" class="btn btn-info">
                                <i class="fas fa-book"></i>
                            </a></td>
                    </tr>
                    <tr>
                        <td>175</td>
                        <td>Mike Doe</td>
                        <td>11-7-2014</td>
                        <td><span class="tag tag-danger">Denied</span></td>
                        <td><a href="/dashboard/laporan/transaksi/detil" type="button" class="btn btn-info">
                                <i class="fas fa-book"></i>
                            </a></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
</section>