<section class="content m-4">
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">Tambah Aset</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form>
            <div class="card-body">
                <div class="form-group">
                    <label for="namaAsset">Nama Aset</label>
                    <input type="text" class="form-control" id="namaAsset" placeholder="Masukkan nama aset">
                </div>
                <div class="form-group">
                    <label for="hargaBeli">Harga Beli Satuan</label>
                    <input type="text" class="form-control" id="hargaBeli" placeholder="Masukkan harga beli satuan">
                </div>
                <div class="form-group">
                    <label for="hargaJual">Harga Jual Satuan</label>
                    <input type="text" class="form-control" id="hargaJual" placeholder="Masukkan harga jual satuan">
                </div>
                <div class="form-group">
                    <label for="quantitas">Quantitas</label>
                    <input type="text" class="form-control" id="quantitas" placeholder="Masukkan quantitas aset">
                </div>
                <div class="form-group">
                    <label for="statusModal">Status Modal</label>
                    <input type="text" class="form-control" id="statusModal" placeholder="Masukkan modal">
                </div>
                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <input type="text" class="form-control" id="deskripsi" placeholder="Masukkan deskripsi">
                </div>
                <div class="form-group">
                    <label for="foto">Foto</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="foto">
                            <label class="custom-file-label" for="foto">Choose file</label>
                        </div>
                        <div class="input-group-append">
                            <span class="input-group-text">Upload</span>
                        </div>
                    </div>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">Check me out</label>
                </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <button type="submit" class="btn btn-info">simpan</button>
            </div>
        </form>
    </div>
</section>