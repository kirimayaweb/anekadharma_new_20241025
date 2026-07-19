<?php
$bulan_tampil = isset($bulan_produksi_selected) && $bulan_produksi_selected !== ''
    ? $bulan_produksi_selected
    : date('Y-m');
$url_ajax_list_by_bulan = isset($url_ajax_list_by_bulan) ? $url_ajax_list_by_bulan : site_url('Sys_unit_produk/ajax_list_by_bulan');
?>
<div class="content-wrapper">


    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <!-- <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard v1</li> -->
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section class="content">



        <div class="box box-warning box-solid">

            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <div class="row">
                        </div>
                        <div class="row align-items-center flex-wrap">
                            <div class="col-auto">
                                <strong>PRODUKSI</strong>
                            </div>
                            <div class="col-auto">
                                <?php
                                $url_create_produksi_bulan = isset($url_create_produksi)
                                    ? $url_create_produksi . '?bulan=' . urlencode($bulan_tampil)
                                    : site_url('Sys_unit_produk/create_produksi?bulan=' . urlencode($bulan_tampil));
                                echo anchor($url_create_produksi_bulan, 'Input Produksi', 'class="btn btn-danger" id="btn-input-produksi"');
                                $url_create_produksi_tanpa_bahan_bulan = isset($url_create_produksi_tanpa_bahan)
                                    ? $url_create_produksi_tanpa_bahan . '?bulan=' . urlencode($bulan_tampil)
                                    : site_url('Sys_unit_produk/create_produksi_tanpa_bahan?bulan=' . urlencode($bulan_tampil));
                                echo ' ';
                                echo anchor($url_create_produksi_tanpa_bahan_bulan, 'Input Produksi Tanpa Bahan', 'class="btn btn-input-produksi-tanpa-bahan" id="btn-input-produksi-tanpa-bahan"');
                                ?>
                            </div>
                            <div class="col-auto d-flex align-items-center flex-wrap">
                                <label for="bulan_produksi" class="mb-0 mr-2">Bulan:</label>
                                <input type="month" id="bulan_produksi" name="bulan_produksi" class="form-control d-inline-block" style="width:auto;" value="<?php echo htmlspecialchars($bulan_tampil, ENT_QUOTES, 'UTF-8'); ?>">
                                <span class="ml-2 small" id="info-jumlah-produksi-bulan">
                                    Menampilkan <?php echo count($Sys_unit_produk_data); ?> data — bulan <?php echo htmlspecialchars(date('m/Y', strtotime($bulan_tampil . '-01')), ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </div>
                        </div>




                    </div>
                    <!-- <br /> -->



                    <div class="card-body">

                        <?php if ($this->session->flashdata('message')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($this->session->flashdata('message'), ENT_QUOTES, 'UTF-8'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-12 col-sm-12">
                                <div class="card card-primary card-tabs">

                                    <div class="card-body">
                                        <div class="tab-content" id="custom-tabs-one-tabContent">
                                            <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">

                                                <table id="example" class="display nowrap" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="80px">No</th>
                                                            <th width="200px">Action</th>
                                                            <!-- <th>Uuid Unit</th> -->
                                                            <!-- <th>Kode Unit</th> -->

                                                            <th>Tgl Transaksi<br /><strong>SPOP</strong></th>
                                                            <!-- <th>SPOP</th> -->
                                                            <th>Nama Unit</th>

                                                            <!-- <th>Uuid Barang</th> -->
                                                            <!-- <th>Kode Barang</th> -->
                                                            <th>Nama Barang</th>
                                                            <th>Jumlah Produksi</th>
                                                            <th>Satuan</th>
                                                            <th>Harga Satuan</th>


                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $start = 0;
                                                        $get_saldo = 0;
                                                        foreach ($Sys_unit_produk_data as $list_data) {

                                                        ?>
                                                            <tr>
                                                                <td style="text-align:center"><?php echo ++$start ?></td>
                                                                <td style="text-align:left">
                                                                    <?php

                                                                    $this->db->where('uuid_persediaan', $list_data->uuid_persediaan);
                                                                    $persediaan_nama_barang = $this->db->get('persediaan');
                                                                    $persediaan_row = $persediaan_nama_barang->row();
                                                                    $persediaan_id = $persediaan_row ? (int) $persediaan_row->id : 0;
                                                                    $spop = $persediaan_row ? $persediaan_row->spop : '';
                                                                    $sudah_terjual = !empty($list_data->sudah_terjual);

                                                                    if ($persediaan_id > 0) {
                                                                        echo anchor(site_url('Sys_unit_produk/update_produksi/' . $persediaan_id), '<i class="fa fa-pencil-square-o">Ubah</i>', array('title' => 'edit', 'class' => 'btn btn-warning btn-sm'));
                                                                    }
                                                                    echo ' ';
                                                                    if ($sudah_terjual) {
                                                                        echo '<button type="button" class="badge-sold-out btn-sold-out-detail" '
                                                                            . 'data-produk-id="' . (int) $list_data->id . '" '
                                                                            . 'data-uuid-persediaan="' . htmlspecialchars(isset($list_data->uuid_persediaan) ? $list_data->uuid_persediaan : '', ENT_QUOTES, 'UTF-8') . '" '
                                                                            . 'data-nama-barang="' . htmlspecialchars($list_data->nama_barang, ENT_QUOTES, 'UTF-8') . '" '
                                                                            . 'title="Lihat data penjualan">'
                                                                            . '<i class="fa fa-star"></i><span>SOLD OUT</span>'
                                                                            . '</button>';
                                                                    } else {
                                                                        echo '<button type="button" class="btn btn-danger btn-sm btn-hapus-produksi" '
                                                                            . 'data-produk-id="' . (int) $list_data->id . '" '
                                                                            . 'data-uuid-persediaan="' . htmlspecialchars(isset($list_data->uuid_persediaan) ? $list_data->uuid_persediaan : '', ENT_QUOTES, 'UTF-8') . '" '
                                                                            . 'data-nama-barang="' . htmlspecialchars($list_data->nama_barang, ENT_QUOTES, 'UTF-8') . '" '
                                                                            . 'title="hapus">'
                                                                            . '<i class="fa fa-trash-o">Hapus</i>'
                                                                            . '</button>';
                                                                    }
                                                                    ?>
                                                                </td>


                                                                <td style="text-align:left">
                                                                    <?php
                                                                    echo date("d-M-Y", strtotime($list_data->tgl_transaksi));
                                                                    echo "<br/>";
                                                                    echo "<strong>" . htmlspecialchars($spop, ENT_QUOTES, 'UTF-8') . "</strong>";
                                                                    ?>

                                                                </td>
                                                                <!-- 
                                                                <td style="text-align:left">
                                                                    <?php
                                                                    // echo $persediaan_nama_barang->row()->spop; 
                                                                    ?>

                                                                </td> -->
                                                                <td style="text-align:left"><?php echo $list_data->nama_unit; ?> </td>
                                                                <td style="text-align:left"><?php echo htmlspecialchars($list_data->nama_barang, ENT_QUOTES, 'UTF-8'); ?></td>
                                                                <td style="text-align:right"><?php echo $list_data->jumlah_produksi; ?> </td>
                                                                <td style="text-align:left"><?php echo $list_data->satuan; ?> </td>
                                                                <td style="text-align:right"><?php echo number_format($list_data->harga_satuan, 2, ',', '.'); ?> </td>


                                                                <!-- `id`, `uuid_unit`, `kode_unit`, `nama_unit`, ``, `uuid_produk`, `kode_barang`, `nama_barang`, `jumlah_produksi`, `satuan`, `harga_satuan` -->

                                                            </tr>
                                                        <?php
                                                        }
                                                        ?>


                                                    </tbody>



                                                </table>


                                            </div>

                                        </div>
                                    </div>
                                    <!-- /.card -->
                                </div>
                            </div>

                        </div>



                    </div>
                    <!-- /.card-body -->






                </div>
            </div>





        </div>




    </section>
</div>




<!-- MODAL SOLD OUT — DATA PENJUALAN -->
<div class="modal fade" id="modal-sold-out-penjualan" tabindex="-1" role="dialog" aria-labelledby="modalSoldOutPenjualanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="modalSoldOutPenjualanLabel">
                    <i class="fa fa-star"></i> Data Penjualan — SOLD OUT
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <div><strong>Nama Barang:</strong> <span id="modal-sold-out-nama-barang">-</span></div>
                    <div class="small text-muted"><strong>uuid_persediaan:</strong> <span id="modal-sold-out-uuid-persediaan">-</span></div>
                </div>
                <div class="table-responsive">
                    <table id="table-sold-out-penjualan" class="table table-bordered table-striped table-sm" style="width:100%">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Tgl Jual</th>
                                <th>Unit</th>
                                <th>Konsumen</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="sold-out-hapus-info mt-3" role="alert">
                    <i class="fa fa-exclamation-triangle"></i>
                    <div>
                        <strong>Produk ini tidak dapat dihapus.</strong>
                        Sudah terdapat data penjualan terkait produk ini.
                        Pastikan belum ada penjualan sebelum menghapus produk.
                        Produk yang sudah terjual tidak bisa dihapus.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL EXTRA LARGE -->

<div class="modal fade" id="modal-xl-select-unit">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Produk Barang</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <form action="<?php echo $action; ?>" method="post">



                        <div class="form-group">
                            <div class="row">
                                <div class="col-12">
                                    Silahkan isi nama produk baru jika belum nama produk di aplikasi, Kemudian klik Proses:
                                    <br />
                                </div>

                            </div>
                        </div>


                        <div class="form-group">
                            <div class="row">
                                <div class="col-4">
                                    <label for="keterangan">Tanggal Produksi <?php echo form_error('tgl_transaksi') ?></label>

                                    <div class="input-group date" id="tgl_transaksi" name="tgl_transaksi" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#tgl_transaksi" id="tgl_transaksi" name="tgl_transaksi" required />
                                        <div class="input-group-append" data-target="#tgl_transaksi" data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>

                                        </div>

                                    </div>
                                </div>



                            </div>
                        </div>



                        <div class="form-group">
                            <div class="row">

                                <div class="col-4">
                                    <label for="keterangan">Nama Produk Baru </label>

                                    <input class="form-control" rows="3" name="nama_barang" id="nama_barang" placeholder="nama_barang">

                                </div>

                                <div class="col-4">
                                    <label for="keterangan">Satuan <?php //echo form_error('satuan') 
                                                                    ?></label>
                                    <input class="form-control" rows="3" name="satuan" id="satuan" placeholder="satuan" required>

                                </div>
                                <div class="col-4">
                                    <label for="keterangan">Harga Satuan <?php //echo form_error('harga_satuan') 
                                                                            ?></label>
                                    <input class="form-control uang" rows="3" name="harga_satuan" id="harga_satuan" placeholder="harga_satuan" required>

                                </div>

                            </div>
                        </div>



                        <div class="form-group">
                            <div class="row">
                                <div class="col-4">
                                    <label for="konsumen_nama">Unit <?php echo form_error('konsumen_nama') ?></label>
                                    <select name="uuid_unit" id="uuid_unit" class="form-control select2" style="width: 100%; height: 40px;" required>
                                        <?php
                                        // if ($uuid_unit) {
                                        ?>
                                        <!-- <option value="<?php echo $uuid_unit; ?>"><?php echo $nama_unit; ?> </option> -->
                                        <?php
                                        // } else {
                                        ?>
                                        <option value="">Pilih Unit </option>
                                        <?php
                                        // }
                                        ?>

                                        <?php

                                        // $sql = "select * from sys_konsumen order by nama_konsumen ASC ";
                                        $sql = "select * from sys_unit order by nama_unit ASC ";
                                        foreach ($this->db->query($sql)->result() as $m) {
                                            echo "<option value='$m->uuid_unit' ";
                                            echo ">  " . strtoupper($m->nama_unit) .  "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-4">
                                    <label for="keterangan">Jumlah Produksi <?php echo form_error('jumlah_produksi') ?></label>
                                    <input class="form-control uang" rows="3" name="jumlah_produksi" id="jumlah_produksi" placeholder="jumlah_produksi" required>
                                </div>

                            </div>
                        </div>


                        <div class="form-group">
                            <div class="row">
                                <div class="col-4">

                                </div>
                                <div class="col-4" align="center">

                                    <button type="submit" class="btn btn-primary">Simpan dan lanjut mengisi bahan-bahan produksi</button>

                                </div>
                                <div class="col-4">

                                </div>
                            </div>
                        </div>





                    </form>
                </div>


            </div>

            <div class="modal-footer">

                <!-- <button type="submit" class="btn btn-primary">Proses</button> -->

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<!-- END OF MODAL EXTRA LARGE -->





<style type="text/css">
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
    #info-jumlah-produksi-bulan {
        color: #ffeb3b;
        font-weight: 700;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.35);
    }
    .btn-input-produksi-tanpa-bahan {
        background-color: #00e676 !important;
        color: #0d47a1 !important;
        border: 2px solid #ffeb3b !important;
        box-shadow: 0 0 8px rgba(255, 235, 59, 0.85);
        font-weight: 700;
    }
    .btn-input-produksi-tanpa-bahan:hover,
    .btn-input-produksi-tanpa-bahan:focus {
        background-color: #00c853 !important;
        color: #0a3d91 !important;
        border-color: #fff176 !important;
        box-shadow: 0 0 12px rgba(255, 235, 59, 1);
    }
    .swal-produksi-hapus-detail {
        max-height: none;
        overflow: visible;
        text-align: left;
        font-size: 12px;
    }
    .swal-produksi-hapus-detail table {
        width: 100%;
        margin-bottom: 12px;
        border-collapse: collapse;
    }
    .swal-produksi-hapus-detail th,
    .swal-produksi-hapus-detail td {
        border: 1px solid #dee2e6;
        padding: 4px 6px;
        vertical-align: top;
    }
    .swal-produksi-hapus-detail th {
        background: #f8f9fa;
        width: auto;
    }
    .swal-produksi-hapus-section {
        margin-bottom: 14px;
    }
    .swal-produksi-hapus-section h6 {
        font-weight: 700;
        margin: 0 0 6px;
        color: #333;
    }
    .swal-konfirmasi-produk-box {
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 8px 10px;
        background: #f8f9fa;
        margin-bottom: 10px;
        overflow-x: auto;
    }
    .swal-konfirmasi-produk-box h6 {
        font-weight: 700;
        margin: 0 0 6px;
        color: #333;
    }
    .swal-konfirmasi-produk-box table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
        margin: 0;
        white-space: nowrap;
    }
    .swal-konfirmasi-produk-box thead th {
        background: #fff3cd;
    }
    .swal-konfirmasi-hapus-grid {
        display: flex;
        gap: 10px;
        align-items: flex-start;
        flex-wrap: wrap;
        text-align: left;
    }
    .swal-konfirmasi-hapus-mid,
    .swal-konfirmasi-hapus-right {
        flex: 1 1 48%;
        min-width: 260px;
        max-height: 220px;
        overflow: auto;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 10px;
        background: #fff;
    }
    .swal-konfirmasi-hapus-mid table,
    .swal-konfirmasi-hapus-right table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
        margin: 0;
    }
    .swal-konfirmasi-hapus-mid th,
    .swal-konfirmasi-hapus-mid td,
    .swal-konfirmasi-hapus-right th,
    .swal-konfirmasi-hapus-right td {
        border: 1px solid #dee2e6;
        padding: 4px 6px;
        vertical-align: top;
    }
    .swal-konfirmasi-hapus-mid thead th {
        background: #e9f7ef;
        white-space: nowrap;
    }
    .swal-konfirmasi-hapus-right thead th {
        background: #e8f4fd;
        white-space: nowrap;
    }
    .swal-konfirmasi-hapus-tanya {
        margin: 14px 0 4px;
        text-align: center;
        font-size: 1.35rem;
        font-weight: 700;
        color: #333;
    }
    .swal-produksi-blokir-wrap {
        text-align: left;
    }
    .swal-produksi-blokir-alert {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        border-radius: 4px;
        padding: 10px 12px;
        margin-bottom: 12px;
        font-weight: 600;
    }
    .swal-produksi-blokir-grid {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        flex-wrap: wrap;
    }
    .swal-produksi-blokir-left {
        flex: 1 1 58%;
        min-width: 260px;
        max-height: 380px;
        overflow-y: auto;
    }
    .swal-produksi-blokir-right {
        flex: 1 1 34%;
        min-width: 180px;
        background: #fff3cd;
        border: 1px solid #ffeeba;
        border-radius: 4px;
        padding: 10px 12px;
    }
    .swal-produksi-blokir-right h6 {
        font-weight: 700;
        margin: 0 0 8px;
        color: #856404;
    }
    .swal-produksi-blokir-right .info-label {
        font-size: 11px;
        color: #6c757d;
        margin-bottom: 2px;
    }
    .swal-produksi-blokir-right .info-value {
        font-size: 14px;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
        word-break: break-word;
    }
    .swal-produksi-penjualan-item {
        border: 1px solid #f5c6cb;
        border-radius: 4px;
        padding: 8px;
        margin-bottom: 8px;
        background: #fff;
    }
    .badge-sold-out {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 800;
        letter-spacing: 0.8px;
        line-height: 1;
        color: #6b4e00;
        text-transform: uppercase;
        white-space: nowrap;
        vertical-align: middle;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        background: linear-gradient(135deg, #fffef0 0%, #fff4b0 22%, #ffe566 48%, #f0c94a 72%, #e8b923 100%);
        border: 1px solid #f0d060;
        box-shadow:
            0 1px 0 rgba(255, 255, 255, 0.85) inset,
            0 0 10px rgba(255, 215, 64, 0.55),
            0 3px 8px rgba(212, 160, 23, 0.35);
        transform: rotate(-5deg);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .badge-sold-out:hover,
    .badge-sold-out:focus {
        color: #5a4100;
        outline: none;
        transform: rotate(-5deg) scale(1.06);
        box-shadow:
            0 1px 0 rgba(255, 255, 255, 0.9) inset,
            0 0 14px rgba(255, 230, 100, 0.8),
            0 4px 12px rgba(212, 160, 23, 0.45);
    }
    .badge-sold-out i {
        font-size: 14px;
        color: #c99700;
        text-shadow: 0 1px 0 rgba(255, 255, 255, 0.7);
    }
    .sold-out-hapus-info {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 12px 14px;
        border-radius: 6px;
        border: 1px solid #f5c6cb;
        background: #f8d7da;
        color: #721c24;
        font-size: 13px;
        line-height: 1.45;
        animation: soldOutInfoBlink 1.4s ease-in-out infinite;
    }
    .sold-out-hapus-info i {
        margin-top: 2px;
        font-size: 16px;
        flex-shrink: 0;
    }
    .sold-out-hapus-info strong {
        display: block;
        margin-bottom: 2px;
    }
    @keyframes soldOutInfoBlink {
        0%, 100% {
            opacity: 1;
            box-shadow: 0 0 0 rgba(220, 53, 69, 0);
            background: #f8d7da;
        }
        50% {
            opacity: 0.55;
            box-shadow: 0 0 12px rgba(220, 53, 69, 0.45);
            background: #ffecee;
        }
    }
</style>

<script>
window.addEventListener('load', function() {
    if (!window.jQuery || !jQuery.fn || !jQuery.fn.dataTable) {
        console.error('Produksi: jQuery/DataTables belum dimuat. Muat ulang halaman.');
        return;
    }
    var $ = window.jQuery;
    var urlAjaxListByBulan = <?php echo json_encode($url_ajax_list_by_bulan); ?>;
    var urlAjaxDeleteProduksi = <?php echo json_encode(isset($url_ajax_delete_produksi) ? $url_ajax_delete_produksi : site_url('Sys_unit_produk/ajax_delete_produksi')); ?>;
    var urlAjaxCekProduksiHapus = <?php echo json_encode(isset($url_ajax_cek_produksi_hapus) ? $url_ajax_cek_produksi_hapus : site_url('Sys_unit_produk/ajax_cek_produksi_hapus')); ?>;
    var urlAjaxListPenjualanSoldOut = <?php echo json_encode(isset($url_ajax_list_penjualan_sold_out) ? $url_ajax_list_penjualan_sold_out : site_url('Sys_unit_produk/ajax_list_penjualan_sold_out')); ?>;
    var urlCreateProduksiBase = <?php echo json_encode(isset($url_create_produksi) ? $url_create_produksi : site_url('Sys_unit_produk/create_produksi')); ?>;
    var urlCreateProduksiTanpaBahanBase = <?php echo json_encode(isset($url_create_produksi_tanpa_bahan) ? $url_create_produksi_tanpa_bahan : site_url('Sys_unit_produk/create_produksi_tanpa_bahan')); ?>;
    var bulanProduksiAktif = <?php echo json_encode($bulan_tampil); ?>;
    var urlListProduksi = <?php echo json_encode(site_url('Sys_unit_produk')); ?>;
    var dtProduksi = null;
    var dtSoldOutPenjualan = null;

    function updateLinkInputProduksi(bulanYm) {
        var href = urlCreateProduksiBase + (urlCreateProduksiBase.indexOf('?') >= 0 ? '&' : '?') + 'bulan=' + encodeURIComponent(bulanYm);
        $('#btn-input-produksi').attr('href', href);
        var hrefTanpaBahan = urlCreateProduksiTanpaBahanBase + (urlCreateProduksiTanpaBahanBase.indexOf('?') >= 0 ? '&' : '?') + 'bulan=' + encodeURIComponent(bulanYm);
        $('#btn-input-produksi-tanpa-bahan').attr('href', hrefTanpaBahan);
    }

    function escapeHtml(text) {
        return String(text === null || text === undefined ? '' : text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function buildSoldOutBadgeHtml(row) {
        return '<button type="button" class="badge-sold-out btn-sold-out-detail" '
            + 'data-produk-id="' + escapeHtml(row.produk_id) + '" '
            + 'data-uuid-persediaan="' + escapeHtml(row.uuid_persediaan || '') + '" '
            + 'data-nama-barang="' + escapeHtml(row.nama_barang || '') + '" '
            + 'title="Lihat data penjualan">'
            + '<i class="fa fa-star"></i><span>SOLD OUT</span></button>';
    }

    function buildProduksiRowHtml(row) {
        var actionHtml = '';
        if (row.can_edit) {
            actionHtml += '<a href="' + escapeHtml(row.edit_url) + '" title="edit" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o">Ubah</i></a> ';
        }
        if (row.sudah_terjual) {
            actionHtml += buildSoldOutBadgeHtml(row);
        } else {
            actionHtml += '<button type="button" class="btn btn-danger btn-sm btn-hapus-produksi" '
                + 'data-produk-id="' + escapeHtml(row.produk_id) + '" '
                + 'data-uuid-persediaan="' + escapeHtml(row.uuid_persediaan || '') + '" '
                + 'data-nama-barang="' + escapeHtml(row.nama_barang) + '" '
                + 'title="hapus"><i class="fa fa-trash-o">Hapus</i></button>';
        }

        return '<tr>'
            + '<td style="text-align:center">' + escapeHtml(row.no) + '</td>'
            + '<td style="text-align:left">' + actionHtml + '</td>'
            + '<td style="text-align:left">' + escapeHtml(row.tgl_transaksi) + '<br/><strong>' + escapeHtml(row.spop) + '</strong></td>'
            + '<td style="text-align:left">' + escapeHtml(row.nama_unit) + '</td>'
            + '<td style="text-align:left">' + escapeHtml(row.nama_barang) + '</td>'
            + '<td style="text-align:right">' + escapeHtml(row.jumlah_produksi) + '</td>'
            + '<td style="text-align:left">' + escapeHtml(row.satuan) + '</td>'
            + '<td style="text-align:right">' + escapeHtml(row.harga_satuan) + '</td>'
            + '</tr>';
    }

    function buildDetailRecordTable(record) {
        if (!record || typeof record !== 'object') {
            return '<p class="text-muted mb-0">Tidak ada data.</p>';
        }
        var html = '<table><tbody>';
        for (var key in record) {
            if (!Object.prototype.hasOwnProperty.call(record, key)) {
                continue;
            }
            html += '<tr><th>' + escapeHtml(key) + '</th><td>' + escapeHtml(record[key]) + '</td></tr>';
        }
        html += '</tbody></table>';
        return html;
    }

    function buildHapusProduksiDetailHtml(detail) {
        if (!detail) {
            return '<p>Tidak ada detail proses.</p>';
        }
        var html = '<div class="swal-produksi-hapus-detail">';

        html += '<div class="swal-produksi-hapus-section">';
        html += '<h6>sys_unit_produk (dihapus)</h6>';
        html += buildDetailRecordTable(detail.sys_unit_produk_dihapus);
        html += '</div>';

        html += '<div class="swal-produksi-hapus-section">';
        html += '<h6>persediaan produk (dihapus)</h6>';
        html += detail.persediaan_produk_dihapus
            ? buildDetailRecordTable(detail.persediaan_produk_dihapus)
            : '<p class="text-muted mb-0">Record persediaan produk tidak ditemukan — dilewati.</p>';
        html += '</div>';

        if (Array.isArray(detail.persediaan_produk_bulan_berikutnya_dihapus) && detail.persediaan_produk_bulan_berikutnya_dihapus.length) {
            html += '<div class="swal-produksi-hapus-section">';
            html += '<h6>persediaan produk bulan berikutnya (dihapus)</h6>';
            for (var n = 0; n < detail.persediaan_produk_bulan_berikutnya_dihapus.length; n++) {
                html += '<p class="mb-1"><strong>Record #' + (n + 1) + '</strong></p>';
                html += buildDetailRecordTable(detail.persediaan_produk_bulan_berikutnya_dihapus[n]);
            }
            html += '</div>';
        }

        html += '<div class="swal-produksi-hapus-section">';
        html += '<h6>sys_unit_produk_bahan (dihapus)</h6>';
        if (Array.isArray(detail.sys_unit_produk_bahan_dihapus) && detail.sys_unit_produk_bahan_dihapus.length) {
            for (var i = 0; i < detail.sys_unit_produk_bahan_dihapus.length; i++) {
                html += '<p class="mb-1"><strong>Record #' + (i + 1) + '</strong></p>';
                html += buildDetailRecordTable(detail.sys_unit_produk_bahan_dihapus[i]);
            }
        } else {
            html += '<p class="text-muted mb-0">Tidak ada record bahan.</p>';
        }
        html += '</div>';

        html += '<div class="swal-produksi-hapus-section">';
        html += '<h6>persediaan bahan (dikembalikan)</h6>';
        if (Array.isArray(detail.persediaan_bahan_dikembalikan) && detail.persediaan_bahan_dikembalikan.length) {
            for (var j = 0; j < detail.persediaan_bahan_dikembalikan.length; j++) {
                var item = detail.persediaan_bahan_dikembalikan[j];
                html += '<p class="mb-1"><strong>Bahan #' + (j + 1) + ' — sebelum</strong></p>';
                html += buildDetailRecordTable(item.persediaan_sebelum);
                html += '<p class="mb-1"><strong>Bahan #' + (j + 1) + ' — sesudah</strong></p>';
                html += buildDetailRecordTable(item.persediaan_sesudah);
                html += '<p class="mb-2"><strong>sys_unit_produk_bahan terkait</strong></p>';
                html += buildDetailRecordTable(item.bahan_record);
            }
        } else {
            html += '<p class="text-muted mb-0">Tidak ada persediaan bahan yang diperbarui.</p>';
        }
        html += '</div>';

        if (Array.isArray(detail.persediaan_bahan_bulan_berikutnya_diupdate) && detail.persediaan_bahan_bulan_berikutnya_diupdate.length) {
            html += '<div class="swal-produksi-hapus-section">';
            html += '<h6>persediaan bahan bulan berikutnya (total_10 &amp; bahan_produksi di-update)</h6>';
            for (var m = 0; m < detail.persediaan_bahan_bulan_berikutnya_diupdate.length; m++) {
                var sync = detail.persediaan_bahan_bulan_berikutnya_diupdate[m];
                html += '<p class="mb-1"><strong>Sync #' + (m + 1) + '</strong> — jumlah dikembalikan: '
                    + escapeHtml(sync.jumlah_bahan || '') + '</p>';
                html += '<p class="mb-1"><strong>Sebelum</strong></p>';
                html += buildDetailRecordTable(sync.persediaan_sebelum);
                html += '<p class="mb-1"><strong>Sesudah</strong></p>';
                html += buildDetailRecordTable(sync.persediaan_sesudah);
            }
            html += '</div>';
        }

        if (Array.isArray(detail.persediaan_bahan_dilewati) && detail.persediaan_bahan_dilewati.length) {
            html += '<div class="swal-produksi-hapus-section">';
            html += '<h6>persediaan bahan (dilewati)</h6>';
            for (var k = 0; k < detail.persediaan_bahan_dilewati.length; k++) {
                var skip = detail.persediaan_bahan_dilewati[k];
                html += '<p class="mb-1"><strong>Record #' + (k + 1) + '</strong> — ' + escapeHtml(skip.keterangan || '') + '</p>';
                html += buildDetailRecordTable(skip.bahan_record);
            }
            html += '</div>';
        }

        html += '</div>';
        return html;
    }

    function buildKonfirmasiHapusRecordHtml(record, uuidPersediaan, bahanList, persediaanList) {
        var list = Array.isArray(bahanList) ? bahanList : [];
        var listPersediaan = Array.isArray(persediaanList) ? persediaanList : [];
        var r = record || {};
        var tglProduk = r.tgl_transaksi ? formatTanggalPenjualan(r.tgl_transaksi) : '-';

        var html = '<div class="swal-produksi-hapus-detail">';
        html += '<p class="mb-2 small text-muted"><strong>uuid_persediaan:</strong> ' + escapeHtml(uuidPersediaan || r.uuid_persediaan || '-') + '</p>';

        html += '<div class="swal-konfirmasi-produk-box">';
        html += '<h6>Data Produk</h6>';
        html += '<table><thead><tr>'
            + '<th>ID</th>'
            + '<th>Tgl Transaksi</th>'
            + '<th>Unit</th>'
            + '<th>Nama Barang</th>'
            + '<th>Jumlah</th>'
            + '<th>Satuan</th>'
            + '<th>Harga Satuan</th>'
            + '</tr></thead><tbody><tr>'
            + '<td style="text-align:center">' + escapeHtml(r.id || '-') + '</td>'
            + '<td>' + escapeHtml(tglProduk) + '</td>'
            + '<td>' + escapeHtml(r.nama_unit || r.kode_unit || '-') + '</td>'
            + '<td>' + escapeHtml(r.nama_barang || '-') + '</td>'
            + '<td style="text-align:right">' + escapeHtml(r.jumlah_produksi || '-') + '</td>'
            + '<td>' + escapeHtml(r.satuan || '-') + '</td>'
            + '<td style="text-align:right">' + escapeHtml(r.harga_satuan || '-') + '</td>'
            + '</tr></tbody></table>';
        html += '</div>';

        html += '<div class="swal-konfirmasi-hapus-grid">';
        html += '<div class="swal-konfirmasi-hapus-mid">';
        html += '<h6>Bahan - Bahan Produk</h6>';
        if (!list.length) {
            html += '<p class="text-muted mb-0">Tidak ada data bahan untuk produk ini.</p>';
        } else {
            html += '<table><thead><tr>'
                + '<th>No</th>'
                + '<th>Nama Bahan</th>'
                + '<th>Jumlah</th>'
                + '<th>Satuan</th>'
                + '<th>Harga Satuan</th>'
                + '<th>Total</th>'
                + '</tr></thead><tbody>';
            for (var i = 0; i < list.length; i++) {
                var b = list[i] || {};
                html += '<tr>'
                    + '<td style="text-align:center">' + escapeHtml(b.no) + '</td>'
                    + '<td>' + escapeHtml(b.nama_barang_bahan || '-') + '</td>'
                    + '<td style="text-align:right">' + escapeHtml(b.jumlah_bahan || '-') + '</td>'
                    + '<td>' + escapeHtml(b.satuan_bahan || '-') + '</td>'
                    + '<td style="text-align:right">' + escapeHtml(b.harga_satuan_bahan || '-') + '</td>'
                    + '<td style="text-align:right">' + escapeHtml(b.harga_total_bahan || '-') + '</td>'
                    + '</tr>';
            }
            html += '</tbody></table>';
        }
        html += '</div>';

        html += '<div class="swal-konfirmasi-hapus-right">';
        html += '<h6>Persediaan ( Stock )</h6>';
        if (!listPersediaan.length) {
            html += '<p class="text-muted mb-0">Record persediaan tidak ditemukan.</p>';
        } else {
            html += '<table><thead><tr>'
                + '<th>No</th>'
                + '<th>Tgl Beli</th>'
                + '<th>Nama Barang</th>'
                + '<th>SPOP</th>'
                + '<th>Satuan</th>'
                + '<th>Jml Produksi</th>'
                + '<th>sa</th>'
                + '<th>total_10</th>'
                + '<th>penjualan</th>'
                + '<th>bahan_produksi</th>'
                + '<th>pecah_satuan</th>'
                + '<th>Sisa Stock</th>'
                + '</tr></thead><tbody>';
            for (var j = 0; j < listPersediaan.length; j++) {
                var p = listPersediaan[j] || {};
                html += '<tr>'
                    + '<td style="text-align:center">' + escapeHtml(p.no) + '</td>'
                    + '<td>' + escapeHtml(p.tanggal_beli || '-') + '</td>'
                    + '<td>' + escapeHtml(p.namabarang || '-') + '</td>'
                    + '<td>' + escapeHtml(p.spop || '-') + '</td>'
                    + '<td>' + escapeHtml(p.satuan || '-') + '</td>'
                    + '<td style="text-align:right">' + escapeHtml(p.jumlah_produksi || '0') + '</td>'
                    + '<td style="text-align:right">' + escapeHtml(p.sa || '0') + '</td>'
                    + '<td style="text-align:right"><strong>' + escapeHtml(p.total_10 || '0') + '</strong></td>'
                    + '<td style="text-align:right"><strong>' + escapeHtml(p.penjualan || '0') + '</strong></td>'
                    + '<td style="text-align:right">' + escapeHtml(p.bahan_produksi || '0') + '</td>'
                    + '<td style="text-align:right">' + escapeHtml(p.pecah_satuan || '0') + '</td>'
                    + '<td style="text-align:right"><strong>' + escapeHtml(p.sisa_stock || '0') + '</strong></td>'
                    + '</tr>';
            }
            html += '</tbody></table>';
            html += '<p class="mb-0 mt-2 text-muted small">Sisa Stock = total_10 (stok aktual di persediaan).</p>';
        }
        html += '</div>';
        html += '</div>';

        html += '<p class="mb-2 mt-2 text-muted">Perhatian: data bahan produksi akan di-reset ke persediaan '
            + '(bahan_produksi dikurangi, total_10 ditambah sejumlah jumlah_bahan).</p>';
        html += '<div class="swal-konfirmasi-hapus-tanya">Hapus produksi?</div>';
        html += '<p class="mb-0 text-center text-muted small">Apakah benar-benar akan menghapus produk ini?</p>';
        html += '</div>';
        return html;
    }

    function formatTanggalPenjualan(value) {
        if (!value) {
            return '-';
        }
        var d = new Date(String(value).replace(' ', 'T'));
        if (isNaN(d.getTime())) {
            return String(value);
        }
        var dd = ('0' + d.getDate()).slice(-2);
        var mm = ('0' + (d.getMonth() + 1)).slice(-2);
        var yyyy = d.getFullYear();
        return dd + '-' + mm + '-' + yyyy;
    }

    function buildPenjualanBlokirHtml(penjualanList, produkInfo) {
        var info = produkInfo || {};
        var list = Array.isArray(penjualanList) ? penjualanList : [];
        var html = '<div class="swal-produksi-blokir-wrap">';
        html += '<div class="swal-produksi-blokir-alert">Tidak bisa dihapus, karena sudah dilakukan penjualan yaitu pada:</div>';
        html += '<div class="swal-produksi-blokir-grid">';

        html += '<div class="swal-produksi-blokir-left">';
        html += '<h6 style="font-weight:700;margin:0 0 8px;">Data penjualan</h6>';
        if (!list.length) {
            html += '<p class="text-muted mb-0">Tidak ada data penjualan.</p>';
        } else {
            for (var i = 0; i < list.length; i++) {
                var p = list[i] || {};
                html += '<div class="swal-produksi-penjualan-item">';
                html += '<table><tbody>';
                html += '<tr><th>Tgl Jual</th><td>' + escapeHtml(formatTanggalPenjualan(p.tgl_jual)) + '</td></tr>';
                html += '<tr><th>No. Kirim</th><td>' + escapeHtml(p.nmrkirim || '-') + '</td></tr>';
                html += '<tr><th>No. Pesan</th><td>' + escapeHtml(p.nmrpesan || '-') + '</td></tr>';
                html += '<tr><th>Konsumen</th><td>' + escapeHtml(p.konsumen_nama || '-') + '</td></tr>';
                html += '<tr><th>Nama Barang</th><td>' + escapeHtml(p.nama_barang || '-') + '</td></tr>';
                html += '<tr><th>Jumlah</th><td>' + escapeHtml(p.jumlah || '-') + ' ' + escapeHtml(p.satuan || '') + '</td></tr>';
                html += '<tr><th>Harga Satuan</th><td>' + escapeHtml(p.harga_satuan || '-') + '</td></tr>';
                html += '<tr><th>Total</th><td>' + escapeHtml(p.total_nominal || '-') + '</td></tr>';
                html += '</tbody></table>';
                html += '</div>';
            }
        }
        html += '</div>';

        html += '<div class="swal-produksi-blokir-right">';
        html += '<h6>Informasi produk ini</h6>';
        html += '<div class="info-label">Nama Barang</div>';
        html += '<div class="info-value">' + escapeHtml(info.nama_barang || '-') + '</div>';
        html += '<div class="info-label">SPOP</div>';
        html += '<div class="info-value">' + escapeHtml(info.spop || '-') + '</div>';
        html += '</div>';

        html += '</div></div>';
        return html;
    }

    function konfirmasiHapusProduksi(produkId, uuidPersediaan, namaBarang) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Memeriksa data...',
                html: 'Mengecek uuid_persediaan di sys_unit_produk dan tbl_penjualan.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: function() {
                    Swal.showLoading();
                }
            });
        }

        $.ajax({
            url: urlAjaxCekProduksiHapus,
            type: 'POST',
            data: {
                id: produkId,
                uuid_persediaan: uuidPersediaan || ''
            },
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).done(function(res) {
            if (!res || !res.ok || !res.record) {
                var errMsg = res && res.message ? res.message : 'Record sys_unit_produk tidak ditemukan.';
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Tidak dapat menghapus', text: errMsg });
                } else {
                    alert(errMsg);
                }
                return;
            }

            var produkIdFinal = parseInt(res.produk_id, 10) || produkId;
            var uuidFinal = res.uuid_persediaan || uuidPersediaan || '';
            var namaFinal = (res.record && res.record.nama_barang) ? res.record.nama_barang : namaBarang;

            if (res.sudah_terjual) {
                if (typeof Swal === 'undefined') {
                    alert('Tidak bisa dihapus, karena sudah dilakukan penjualan.');
                    return;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Tidak bisa dihapus',
                    html: buildPenjualanBlokirHtml(res.penjualan_list, res.produk_info),
                    width: '920px',
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#d33'
                });
                return;
            }

            if (typeof Swal === 'undefined') {
                if (window.confirm('Hapus produksi "' + namaFinal + '"?\n\nuuid_persediaan: ' + uuidFinal)) {
                    prosesHapusProduksi(produkIdFinal);
                }
                return;
            }

            Swal.fire({
                icon: 'warning',
                title: '',
                html: buildKonfirmasiHapusRecordHtml(
                    res.record,
                    uuidFinal,
                    res.bahan_list || [],
                    res.persediaan_list || []
                ),
                width: '1200px',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#d33',
                reverseButtons: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    prosesHapusProduksi(produkIdFinal);
                }
            });
        }).fail(function() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal memeriksa data produksi sebelum hapus.' });
            } else {
                alert('Gagal memeriksa data produksi sebelum hapus.');
            }
        });
    }

    function prosesHapusProduksi(produkId) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Memproses...',
                html: 'Menghapus produksi dan mengembalikan bahan ke persediaan.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: function() {
                    Swal.showLoading();
                }
            });
        }

        $.ajax({
            url: urlAjaxDeleteProduksi,
            type: 'POST',
            data: { id: produkId },
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).done(function(res) {
            if (!res || !res.ok) {
                if (res && res.sudah_terjual && typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Tidak bisa dihapus',
                        html: buildPenjualanBlokirHtml(res.penjualan_list, res.produk_info),
                        width: '920px',
                        confirmButtonText: 'Tutup',
                        confirmButtonColor: '#d33'
                    });
                    return;
                }
                var errMsg = res && res.message ? res.message : 'Gagal menghapus data produksi.';
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: errMsg });
                } else {
                    alert(errMsg);
                }
                return;
            }

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil dihapus',
                    html: '<p>' + escapeHtml(res.message || 'Proses hapus selesai.') + '</p>'
                        + buildHapusProduksiDetailHtml(res.detail),
                    width: '860px',
                    confirmButtonText: 'OK',
                    timer: 2000,
                    timerProgressBar: true,
                    allowOutsideClick: true,
                    allowEscapeKey: true
                }).then(function() {
                    var urlKembali = urlListProduksi
                        + (urlListProduksi.indexOf('?') >= 0 ? '&' : '?')
                        + 'bulan=' + encodeURIComponent(bulanProduksiAktif || '');
                    window.location.href = urlKembali;
                });
            } else {
                alert(res.message || 'Proses hapus selesai.');
                var urlKembaliFallback = urlListProduksi
                    + (urlListProduksi.indexOf('?') >= 0 ? '&' : '?')
                    + 'bulan=' + encodeURIComponent(bulanProduksiAktif || '');
                window.location.href = urlKembaliFallback;
            }
        }).fail(function() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan saat menghapus data produksi.' });
            } else {
                alert('Terjadi kesalahan saat menghapus data produksi.');
            }
        });
    }

    function destroyDataTableProduksi() {
        if ($.fn.DataTable.isDataTable('#example')) {
            $('#example').DataTable().destroy();
            dtProduksi = null;
        }
    }

    function initDataTableProduksi() {
        destroyDataTableProduksi();
        dtProduksi = $('#example').DataTable({
            scrollY: 700,
            scrollX: true,
            scrollCollapse: true,
            pageLength: 25,
            language: {
                emptyTable: 'Belum ada data produksi pada bulan ini',
                info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
                infoFiltered: '(disaring dari _MAX_ total data)',
                lengthMenu: 'Tampilkan _MENU_ data',
                search: 'Cari:',
                zeroRecords: 'Tidak ada data yang cocok',
                paginate: { first: 'Awal', last: 'Akhir', next: 'Berikutnya', previous: 'Sebelumnya' }
            }
        });
    }

    function updateInfoJumlah(rows, bulanLabel) {
        var jumlah = Array.isArray(rows) ? rows.length : 0;
        $('#info-jumlah-produksi-bulan').text('Menampilkan ' + jumlah + ' data — bulan ' + bulanLabel);
    }

    function renderProduksiRows(rows, bulanLabel) {
        var html = '';
        if (Array.isArray(rows)) {
            for (var i = 0; i < rows.length; i++) {
                html += buildProduksiRowHtml(rows[i]);
            }
        }
        $('#example tbody').html(html);
        updateInfoJumlah(rows, bulanLabel);
        initDataTableProduksi();
    }

    function loadProduksiByBulan(bulanYm) {
        if (!bulanYm) {
            return;
        }
        destroyDataTableProduksi();
        $('#example tbody').html('<tr><td colspan="8" class="text-center text-muted">Memuat data...</td></tr>');
        $.ajax({
            url: urlAjaxListByBulan,
            type: 'GET',
            data: { bulan: bulanYm },
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).done(function(res) {
            if (!res || !res.ok) {
                var msg = res && res.message ? res.message : 'Gagal memuat data produksi.';
                $('#example tbody').html('<tr><td colspan="8" class="text-center text-danger">' + escapeHtml(msg) + '</td></tr>');
                updateInfoJumlah([], bulanYm.replace(/^(\d{4})-(\d{2})$/, '$2/$1'));
                initDataTableProduksi();
                return;
            }
            renderProduksiRows(res.rows, res.bulan_label);
        }).fail(function() {
            $('#example tbody').html('<tr><td colspan="8" class="text-center text-danger">Gagal memuat data produksi.</td></tr>');
            updateInfoJumlah([], bulanYm.replace(/^(\d{4})-(\d{2})$/, '$2/$1'));
            initDataTableProduksi();
        });
    }

    $('#bulan_produksi').on('change', function() {
        var bulan = $(this).val() || '';
        if (!bulan || bulan === bulanProduksiAktif) {
            return;
        }
        bulanProduksiAktif = bulan;
        updateLinkInputProduksi(bulan);
        loadProduksiByBulan(bulan);
    });

    $(document).on('click', '.btn-hapus-produksi', function() {
        var produkId = parseInt($(this).data('produk-id'), 10) || 0;
        var uuidPersediaan = $(this).attr('data-uuid-persediaan') || $(this).data('uuid-persediaan') || '';
        var namaBarang = $(this).data('nama-barang') || '';
        if (produkId <= 0) {
            return;
        }
        konfirmasiHapusProduksi(produkId, uuidPersediaan, namaBarang);
    });

    function destroyDataTableSoldOutPenjualan() {
        if ($.fn.DataTable.isDataTable('#table-sold-out-penjualan')) {
            $('#table-sold-out-penjualan').DataTable().destroy();
            dtSoldOutPenjualan = null;
        }
    }

    function initDataTableSoldOutPenjualan() {
        destroyDataTableSoldOutPenjualan();
        dtSoldOutPenjualan = $('#table-sold-out-penjualan').DataTable({
            pageLength: 10,
            order: [[1, 'asc']],
            language: {
                emptyTable: 'Tidak ada data penjualan',
                info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
                infoFiltered: '(disaring dari _MAX_ total data)',
                lengthMenu: 'Tampilkan _MENU_ data',
                search: 'Cari:',
                zeroRecords: 'Tidak ada data yang cocok',
                paginate: { first: 'Awal', last: 'Akhir', next: 'Berikutnya', previous: 'Sebelumnya' }
            }
        });
    }

    function bukaModalSoldOutPenjualan(produkId, uuidPersediaan, namaBarang) {
        $('#modal-sold-out-nama-barang').text(namaBarang || '-');
        $('#modal-sold-out-uuid-persediaan').text(uuidPersediaan || '-');
        destroyDataTableSoldOutPenjualan();
        $('#table-sold-out-penjualan tbody').html(
            '<tr><td colspan="5" class="text-center text-muted">Memuat data penjualan...</td></tr>'
        );
        $('#modal-sold-out-penjualan').modal('show');

        $.ajax({
            url: urlAjaxListPenjualanSoldOut,
            type: 'GET',
            data: {
                produk_id: produkId,
                uuid_persediaan: uuidPersediaan || ''
            },
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).done(function(res) {
            if (!res || !res.ok) {
                var msg = res && res.message ? res.message : 'Gagal memuat data penjualan.';
                $('#table-sold-out-penjualan tbody').html(
                    '<tr><td colspan="5" class="text-center text-danger">' + escapeHtml(msg) + '</td></tr>'
                );
                initDataTableSoldOutPenjualan();
                return;
            }

            if (res.nama_barang) {
                $('#modal-sold-out-nama-barang').text(res.nama_barang);
            }
            if (res.uuid_persediaan) {
                $('#modal-sold-out-uuid-persediaan').text(res.uuid_persediaan);
            }

            var html = '';
            var rows = Array.isArray(res.rows) ? res.rows : [];
            if (!rows.length) {
                html = '<tr><td colspan="5" class="text-center text-muted">Tidak ada data penjualan.</td></tr>';
            } else {
                for (var i = 0; i < rows.length; i++) {
                    var r = rows[i];
                    html += '<tr>'
                        + '<td style="text-align:center">' + escapeHtml(r.no) + '</td>'
                        + '<td>' + escapeHtml(r.tgl_jual) + '</td>'
                        + '<td>' + escapeHtml(r.unit) + '</td>'
                        + '<td>' + escapeHtml(r.konsumen) + '</td>'
                        + '<td style="text-align:right">' + escapeHtml(r.jumlah) + '</td>'
                        + '</tr>';
                }
            }
            $('#table-sold-out-penjualan tbody').html(html);
            initDataTableSoldOutPenjualan();
        }).fail(function() {
            $('#table-sold-out-penjualan tbody').html(
                '<tr><td colspan="5" class="text-center text-danger">Gagal memuat data penjualan.</td></tr>'
            );
            initDataTableSoldOutPenjualan();
        });
    }

    $(document).on('click', '.btn-sold-out-detail', function() {
        var produkId = parseInt($(this).data('produk-id'), 10) || 0;
        var uuidPersediaan = $(this).attr('data-uuid-persediaan') || $(this).data('uuid-persediaan') || '';
        var namaBarang = $(this).data('nama-barang') || '';
        if (produkId <= 0 && !uuidPersediaan) {
            return;
        }
        bukaModalSoldOutPenjualan(produkId, uuidPersediaan, namaBarang);
    });

    updateLinkInputProduksi(bulanProduksiAktif);
    initDataTableProduksi();
});
</script>