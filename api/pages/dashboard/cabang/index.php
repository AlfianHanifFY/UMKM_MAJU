<?php

    require_once __DIR__ . '/../../../model/branch.php';
    $branches = get_branch($_COOKIE['token']);

?>


<section class="content m-4">
    <div class="col-md-2 m-2 ml-auto"><a href="/dashboard/cabang/tambah" type="button"
            class="btn btn-info btn-block btn-flat"><i class="fa fa-plus"></i> tambah cabang</a></div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">List Cabang</h3>
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
                        <th>Cabang</th>
                        <th>Alamat</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($branches as $branch): ?>
                    <tr>
                        <td><?= htmlspecialchars($branch['tu_user_branch_id']) ?></td>
                        <td><?= htmlspecialchars($branch['branch_name']) ?></td>
                        <td><?= htmlspecialchars($branch['branch_address']) ?></td>
                        <td>
                            <span class="tag 
                    <?= $branch['status'] === 'ACTIVE' ? 'tag-success' : 
                        ($branch['status'] === 'pending' ? 'tag-warning' : 
                        ($branch['status'] === 'denied' ? 'tag-danger' : 'tag-info')) ?>">
                                <?= ucfirst($branch['status']) ?>
                            </span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="/dashboard/cabang/edit?id=<?= urlencode($branch['tu_user_branch_id']) ?>"
                                    class="btn btn-info">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="/dashboard/cabang/hapus?id=<?= urlencode($branch['tu_user_branch_id']) ?>"
                                    class="btn btn-info" onclick="return confirm('Yakin ingin menghapus cabang ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
</section>