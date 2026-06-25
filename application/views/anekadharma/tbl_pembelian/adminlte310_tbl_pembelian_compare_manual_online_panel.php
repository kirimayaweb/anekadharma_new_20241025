<?php
if (!isset($nama_bulan_id) || !is_array($nama_bulan_id)) {
    $nama_bulan_id = array(
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    );
}
if (!isset($compare_bulan_num)) {
    $compare_bulan_num = (int) date('m');
}
if (!isset($compare_tahun_num)) {
    $compare_tahun_num = (int) date('Y');
}
if (!isset($gen_tahun_min)) {
    $gen_tahun_min = 2019;
}
if (!isset($gen_tahun_max)) {
    $gen_tahun_max = (int) date('Y') + 1;
}
?>
<div class="row mb-2">
    <div class="col-md-12">
        <small class="text-muted d-block mb-2">
            Bandingkan <strong>tbl_pembelian</strong> (online: tgl_po, SPOP, supplier, jumlah × harga_satuan)
            dengan tabel manual (tanggal, SPOP, SUPPLIER, jumlah) per bulan terpilih.
        </small>
        <label for="compare_pembelian_csv_file" class="mb-1">Pilih file CSV ( untuk di upload ke database sistem / aplikasi menjadi tabel data )</label>
        <div class="d-flex flex-wrap align-items-end compare-csv-upload-row mb-3">
            <div class="custom-file custom-file-sm mb-0 compare-csv-file-wrap">
                <input type="file" class="custom-file-input" id="compare_pembelian_csv_file" accept=".csv,text/csv">
                <label class="custom-file-label" for="compare_pembelian_csv_file" data-browse="Pilih">Cari / pilih file CSV...</label>
            </div>
        </div>
        <div id="compare-pembelian-csv-upload-info" class="alert alert-light border py-2 d-none mb-3">
            <div class="small mb-1">
                <span class="text-muted">File:</span>
                <strong id="compare-pembelian-csv-filename">—</strong>
            </div>
            <div class="small mb-1">
                <span class="text-muted">Tabel DB:</span>
                <strong id="compare-pembelian-csv-tablename" class="text-primary">—</strong>
                <span class="text-muted" id="compare-pembelian-csv-rowcount"></span>
            </div>
            <div class="small text-muted mb-2">
                Tabel memiliki kolom <strong>id</strong> (INTEGER AUTO_INCREMENT). Klik <strong>Cek Data</strong> untuk melihat isi tabel hasil upload CSV.
            </div>
            <button type="button" id="btn-compare-pembelian-csv-cek-data" class="btn btn-outline-info btn-sm">
                <i class="fas fa-search"></i> Cek Data
            </button>
        </div>
    </div>
</div>

<div class="row mb-3 align-items-end compare-toolbar-row flex-wrap">
    <div class="col-auto mb-2">
        <label for="compare_bulan_pembelian" class="small mb-1">Bulan</label>
        <select id="compare_bulan_pembelian" class="form-control form-control-sm compare-toolbar-control">
            <?php foreach ($nama_bulan_id as $num => $label_bulan) { ?>
                <option value="<?php echo (int) $num; ?>"<?php echo ((int) $num === (int) $compare_bulan_num) ? ' selected' : ''; ?>>
                    <?php echo htmlspecialchars($label_bulan, ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php } ?>
        </select>
    </div>
    <div class="col-auto mb-2">
        <label for="compare_tahun_pembelian" class="small mb-1">Tahun</label>
        <select id="compare_tahun_pembelian" class="form-control form-control-sm compare-toolbar-control">
            <?php for ($th = $gen_tahun_max; $th >= $gen_tahun_min; $th--) { ?>
                <option value="<?php echo (int) $th; ?>"<?php echo ((int) $th === (int) $compare_tahun_num) ? ' selected' : ''; ?>>
                    <?php echo (int) $th; ?>
                </option>
            <?php } ?>
        </select>
    </div>
    <div class="col-auto mb-2">
        <label for="compare_tabel_pembelian" class="small mb-1">Pilih tabel database</label>
        <select id="compare_tabel_pembelian" class="form-control form-control-sm compare-toolbar-control compare-toolbar-tabel">
            <option value="">— Muat daftar tabel —</option>
        </select>
    </div>
    <div class="col-auto mb-2">
        <label class="small mb-1 d-block">&nbsp;</label>
        <div class="d-flex flex-wrap align-items-center">
            <button type="button" id="btn-compare-jurnal-pembelian" class="btn btn-info btn-sm d-none">
                <i class="fas fa-columns"></i> Compare
            </button>
            <button type="button" id="btn-compare-pembelian-excel-all" class="btn btn-success btn-sm d-none ml-2">
                <i class="fa fa-file-excel-o"></i> Cetak ke Excel
            </button>
        </div>
    </div>
</div>

<div class="alert alert-secondary py-2" id="compare-pembelian-info-ringkas">
    <strong>Bulan:</strong> <span id="compare-pembelian-label-bulan">—</span>
    &nbsp;|&nbsp; <strong>Tabel manual:</strong> <span id="compare-pembelian-label-tabel">—</span>
    &nbsp;|&nbsp; <strong>Manual tidak di online:</strong> <span id="compare-pembelian-count-manual">—</span>
    &nbsp;|&nbsp; <strong>Online tidak di manual:</strong> <span id="compare-pembelian-count-online">—</span>
    &nbsp;|&nbsp; <strong>Cocok:</strong> <span id="compare-pembelian-count-cocok">—</span>
    &nbsp;|&nbsp; <strong>Tidak lengkap manual:</strong> <span id="compare-pembelian-count-tidak-lengkap-manual">—</span>
    &nbsp;|&nbsp; <strong>Tidak lengkap online:</strong> <span id="compare-pembelian-count-tidak-lengkap-online">—</span>
</div>
<div class="alert alert-info py-2 mb-3" id="compare-pembelian-status">
    Pilih bulan, tahun, dan tabel manual — lalu klik <strong>Compare</strong>. Setelah compare selesai, tombol <strong>Cetak ke Excel</strong> akan muncul di sebelah Compare.
</div>

<h6 class="d-flex align-items-center flex-wrap mt-3">
    <span>1. Data Manual tidak ada di Online <span id="compare-pembelian-badge-manual" class="badge badge-warning">0</span></span>
    <button type="button" class="btn btn-xs btn-outline-success btn-compare-pembelian-excel ml-2 mb-1" data-jenis="hanya_manual"><i class="fa fa-file-excel-o"></i> Cetak ke Excel</button>
</h6>
<div class="compare-dt-wrap mb-4">
    <table id="table-compare-pembelian-manual" class="table table-bordered table-striped table-sm compare-dt" style="width:100%;font-size:13px;">
        <thead><tr>
            <th>No</th><th>Tanggal</th><th>SPOP</th><th>Supplier</th><th>Jumlah</th><th>Keterangan</th>
        </tr></thead>
        <tbody></tbody>
    </table>
</div>

<h6 class="d-flex align-items-center flex-wrap">
    <span>2. Data Cocok (Manual &amp; Online) <span id="compare-pembelian-badge-cocok" class="badge badge-success">0</span></span>
    <button type="button" class="btn btn-xs btn-outline-success btn-compare-pembelian-excel ml-2 mb-1" data-jenis="cocok"><i class="fa fa-file-excel-o"></i> Cetak ke Excel</button>
</h6>
<div class="compare-dt-wrap mb-4">
    <table id="table-compare-pembelian-cocok" class="table table-bordered table-striped table-sm compare-dt" style="width:100%;font-size:13px;">
        <thead><tr>
            <th>No</th><th>Tanggal</th><th>SPOP</th><th>Supplier</th><th>Jumlah</th><th>Keterangan</th>
        </tr></thead>
        <tbody></tbody>
    </table>
</div>

<h6 class="d-flex align-items-center flex-wrap">
    <span>3. Data Online tidak ada di Manual <span id="compare-pembelian-badge-online" class="badge badge-info">0</span></span>
    <button type="button" class="btn btn-xs btn-outline-success btn-compare-pembelian-excel ml-2 mb-1" data-jenis="hanya_online"><i class="fa fa-file-excel-o"></i> Cetak ke Excel</button>
</h6>
<div class="compare-dt-wrap mb-4">
    <table id="table-compare-pembelian-online" class="table table-bordered table-striped table-sm compare-dt" style="width:100%;font-size:13px;">
        <thead><tr>
            <th>No</th><th>Tanggal</th><th>SPOP</th><th>Supplier</th><th>Jumlah</th><th>Keterangan</th>
        </tr></thead>
        <tbody></tbody>
    </table>
</div>

<h6 class="d-flex align-items-center flex-wrap">
    <span>4. Data Manual tidak ada di Online <span id="compare-pembelian-badge-manual-duplikat" class="badge badge-warning">0</span></span>
    <button type="button" class="btn btn-xs btn-outline-success btn-compare-pembelian-excel ml-2 mb-1" data-jenis="hanya_manual_4"><i class="fa fa-file-excel-o"></i> Cetak ke Excel</button>
</h6>
<div class="alert alert-secondary py-2 small mb-2">
    Ringkasan sama dengan tabel <strong>1</strong> — ditampilkan terpisah agar urutan nomor selaras dengan layout Excel.
</div>
<div class="compare-dt-wrap mb-4">
    <table id="table-compare-pembelian-manual-duplikat" class="table table-bordered table-striped table-sm compare-dt" style="width:100%;font-size:13px;">
        <thead><tr>
            <th>No</th><th>Tanggal</th><th>SPOP</th><th>Supplier</th><th>Jumlah</th><th>Keterangan</th>
        </tr></thead>
        <tbody></tbody>
    </table>
</div>

<h6 class="d-flex align-items-center flex-wrap">
    <span>5. Data Tidak Lengkap (data manual) <span id="compare-pembelian-badge-tidak-lengkap-manual" class="badge badge-danger">0</span></span>
    <button type="button" class="btn btn-xs btn-outline-success btn-compare-pembelian-excel ml-2 mb-1" data-jenis="tidak_lengkap_manual"><i class="fa fa-file-excel-o"></i> Cetak ke Excel</button>
</h6>
<div class="alert alert-warning py-2 small mb-2">
    Data manual (tabel CSV) dengan <strong>SPOP</strong>, <strong>Supplier</strong>, atau <strong>Jumlah</strong> kosong
    tidak ikut proses compare dan ditampilkan di tabel ini.
</div>
<div class="compare-dt-wrap mb-4">
    <table id="table-compare-pembelian-tidak-lengkap-manual" class="table table-bordered table-striped table-sm compare-dt" style="width:100%;font-size:13px;">
        <thead><tr>
            <th>No</th><th>Tanggal</th><th>SPOP</th><th>Supplier</th><th>Jumlah</th><th>Keterangan</th>
        </tr></thead>
        <tbody></tbody>
    </table>
</div>

<h6 class="d-flex align-items-center flex-wrap">
    <span>6. Data Tidak Lengkap (di data online) <span id="compare-pembelian-badge-tidak-lengkap-online" class="badge badge-danger">0</span></span>
    <button type="button" class="btn btn-xs btn-outline-success btn-compare-pembelian-excel ml-2 mb-1" data-jenis="tidak_lengkap_online"><i class="fa fa-file-excel-o"></i> Cetak ke Excel</button>
</h6>
<div class="alert alert-warning py-2 small mb-2">
    Data online (<strong>tbl_pembelian</strong>) dengan <strong>SPOP</strong>, <strong>Supplier</strong>, atau <strong>Jumlah</strong> kosong
    tidak ikut proses compare dan ditampilkan di tabel ini.
</div>
<div class="compare-dt-wrap mb-4">
    <table id="table-compare-pembelian-tidak-lengkap-online" class="table table-bordered table-striped table-sm compare-dt" style="width:100%;font-size:13px;">
        <thead><tr>
            <th>No</th><th>Tanggal</th><th>SPOP</th><th>Supplier</th><th>Jumlah</th><th>Keterangan</th>
        </tr></thead>
        <tbody></tbody>
    </table>
</div>

<div class="modal fade" id="modal-compare-pembelian-csv-preview" tabindex="-1" role="dialog" aria-labelledby="modalComparePembelianCsvPreviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document" style="max-width:95%;">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title" id="modalComparePembelianCsvPreviewLabel">Data Tabel CSV</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pb-2">
                <p class="text-muted small mb-2" id="compare-pembelian-csv-preview-meta">
                    Memuat informasi tabel...
                </p>
                <div id="compare-pembelian-csv-preview-loading" class="text-center py-4 text-muted d-none">
                    <i class="fas fa-spinner fa-spin"></i> Memuat data tabel...
                </div>
                <div class="compare-pembelian-csv-preview-dt-wrap">
                    <table id="table-compare-pembelian-csv-preview" class="table table-bordered table-striped table-sm" style="width:100%;font-size:12px;">
                        <thead><tr></tr></thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
