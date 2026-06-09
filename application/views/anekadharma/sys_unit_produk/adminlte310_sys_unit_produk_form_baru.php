<?php
$bulan_produksi_selected = isset($bulan_produksi_selected) && $bulan_produksi_selected !== ''
    ? $bulan_produksi_selected
    : date('Y-m');
$bulan_produksi_ym = isset($bulan_produksi_ym) && $bulan_produksi_ym !== ''
    ? $bulan_produksi_ym
    : $bulan_produksi_selected;
$bulan_produksi_label = isset($bulan_produksi_label) && $bulan_produksi_label !== ''
    ? $bulan_produksi_label
    : date('F Y', strtotime($bulan_produksi_selected . '-01'));
$url_ajax_stock_persediaan = isset($url_ajax_stock_persediaan)
    ? $url_ajax_stock_persediaan
    : site_url('Sys_unit_produk/ajax_stock_persediaan_by_bulan');
$tgl_transaksi_bahan = isset($tgl_transaksi_bahan) ? $tgl_transaksi_bahan : ($bulan_produksi_selected . '-01');
$jumlah_bahan_count = isset($jumlah_bahan_count) ? (int) $jumlah_bahan_count : 0;
$produk_sudah_ada = isset($produk_sudah_ada) ? (bool) $produk_sudah_ada : false;
$label_btn_produk = isset($label_btn_produk) ? $label_btn_produk : 'Simpan';
$action_simpan_produk_form = isset($action_simpan_produk_form) ? $action_simpan_produk_form : '';
$data_bahan_list = isset($data_bahan_produk_unit) && is_array($data_bahan_produk_unit) ? $data_bahan_produk_unit : array();
$TOTAL_HARGA = 0;
foreach ($data_bahan_list as $_bahan_row) {
    $TOTAL_HARGA += $_bahan_row->jumlah_bahan * $_bahan_row->harga_satuan_bahan;
}
if (!empty($tgl_transaksi)) {
    if (preg_match('/^\d{2}-\d{2}-\d{4}(\s+\d{2}:\d{2}:\d{2})?$/', trim($tgl_transaksi))) {
        $tgl_transaksi_produk_X = trim($tgl_transaksi);
        if (strlen($tgl_transaksi_produk_X) === 10) {
            $tgl_transaksi_produk_X .= ' 00:00:00';
        }
    } elseif (date('Y', strtotime($tgl_transaksi)) < 2020) {
        $tgl_transaksi_produk_X = date('d-m-Y H:i:s');
    } else {
        $tgl_transaksi_produk_X = date('d-m-Y H:i:s', strtotime($tgl_transaksi));
    }
} else {
    $tgl_transaksi_produk_X = date('d-m-Y H:i:s', strtotime($bulan_produksi_selected . '-01'));
}
$harga_satuan_tampil = isset($harga_satuan) && $harga_satuan !== '' ? number_format((float) preg_replace('/[^0-9.]/', '', $harga_satuan), 0, ',', '.') : '';
$btn_produk_siap = ($jumlah_bahan_count >= 1 && !empty($id_persediaan_barang) && !empty($action_simpan_produk_form));
$produk_draft = isset($produk_draft) && is_array($produk_draft) ? $produk_draft : array();
$hapus_produk_draft_client = isset($hapus_produk_draft_client) ? (bool) $hapus_produk_draft_client : false;
$data_penjualan_per_uuid_penjualan = isset($data_penjualan_per_uuid_penjualan) && is_array($data_penjualan_per_uuid_penjualan)
    ? $data_penjualan_per_uuid_penjualan
    : array();
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


        <!-- <div class="col-md-1"></div> -->
        <div class="col-md-12">
            <div class="box box-warning box-solid">


                <div class="card card-primary">
                    <div class="card-body">

                        <?php if ($this->session->flashdata('message')): ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($this->session->flashdata('message'), ENT_QUOTES, 'UTF-8'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php endif; ?>

                        <div class="card card-info mb-3">
                            <div class="card-header">
                                <strong>Produk Baru</strong>
                            </div>
                            <div class="card-body">
                                <form id="form-produk-baru" method="post" action="<?php echo htmlspecialchars($action_simpan_produk_form, ENT_QUOTES, 'UTF-8'); ?>">
                                    <input type="hidden" name="id_persediaan_barang" value="<?php echo isset($id_persediaan_barang) ? (int) $id_persediaan_barang : ''; ?>">
                                    <input type="hidden" name="uuid_barang" value="<?php echo isset($uuid_barang) ? htmlspecialchars($uuid_barang, ENT_QUOTES, 'UTF-8') : ''; ?>">
                                    <input type="hidden" name="bulan_produksi" value="<?php echo htmlspecialchars($bulan_produksi_selected, ENT_QUOTES, 'UTF-8'); ?>">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="tgl_transaksi_produk">Tanggal Produksi</label>
                                            <div class="input-group date" id="tgl_transaksi_produk_wrap" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input field-produk-baru" data-target="#tgl_transaksi_produk_wrap" id="tgl_transaksi_produk" name="tgl_transaksi" value="<?php echo htmlspecialchars($tgl_transaksi_produk_X, ENT_QUOTES, 'UTF-8'); ?>" required>
                                                <div class="input-group-append" data-target="#tgl_transaksi_produk_wrap" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="uuid_unit_produk">Unit</label>
                                            <select name="uuid_unit" id="uuid_unit_produk" class="form-control select2 field-produk-baru" style="width:100%;" required>
                                                <option value="">Pilih Unit</option>
                                                <?php
                                                $sql_unit = 'SELECT * FROM sys_unit ORDER BY nama_unit ASC';
                                                foreach ($this->db->query($sql_unit)->result() as $m) {
                                                    $sel = (isset($uuid_unit) && $uuid_unit === $m->uuid_unit) ? ' selected' : '';
                                                    echo '<option value="' . htmlspecialchars($m->uuid_unit, ENT_QUOTES, 'UTF-8') . '"' . $sel . '>' . strtoupper($m->nama_unit) . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="jumlah_produksi_produk">Jumlah Produksi</label>
                                            <input class="form-control uang field-produk-baru" name="jumlah_produksi" id="jumlah_produksi_produk" value="<?php echo isset($jumlah_produksi) ? htmlspecialchars($jumlah_produksi, ENT_QUOTES, 'UTF-8') : ''; ?>" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="nama_barang_produk">Nama Produk</label>
                                            <input class="form-control field-produk-baru" name="nama_barang" id="nama_barang_produk" value="<?php echo isset($nama_barang) ? htmlspecialchars($nama_barang, ENT_QUOTES, 'UTF-8') : ''; ?>" required>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-2">
                                            <label for="satuan_produk">Satuan</label>
                                            <input class="form-control field-produk-baru" name="satuan" id="satuan_produk" value="<?php echo isset($satuan) ? htmlspecialchars($satuan, ENT_QUOTES, 'UTF-8') : ''; ?>" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="harga_satuan_produk">Harga Satuan</label>
                                            <input class="form-control uang field-produk-baru" name="harga_satuan" id="harga_satuan_produk" value="<?php echo htmlspecialchars($harga_satuan_tampil, ENT_QUOTES, 'UTF-8'); ?>" required>
                                            <?php if ($TOTAL_HARGA > 0) { ?>
                                            <small class="text-muted">Total harga bahan: <?php echo number_format($TOTAL_HARGA, 0, ',', '.'); ?></small>
                                            <?php } ?>
                                        </div>
                                        <div class="col-md-7">
                                            <label for="keterangan_produk">Keterangan</label>
                                            <input type="text" class="form-control field-produk-baru" name="keterangan" id="keterangan_produk" value="<?php echo isset($keterangan) ? htmlspecialchars($keterangan, ENT_QUOTES, 'UTF-8') : ''; ?>" required>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                            <div class="card card-success">
                                <div class="card-header">

                                    <div class="row align-items-center flex-wrap">
                                        <div class="col-auto">
                                            <strong>Detail Bahan-bahan:</strong>
                                        </div>
                                        <div class="col-auto">
                                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-xl-select-unit">
                                                Input Bahan
                                            </button>
                                        </div>
                                        <div class="col-auto d-flex align-items-center flex-wrap">
                                            <label for="bulan_produksi_bahan" class="mb-0 mr-2">Bulan:</label>
                                            <input type="month" id="bulan_produksi_bahan" name="bulan_produksi_bahan" class="form-control d-inline-block" style="width:auto;" value="<?php echo htmlspecialchars($bulan_produksi_selected, ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="col">
                                            <span class="small" id="keterangan-bulan-produksi-bahan">
                                                Anda bekerja dengan data pada Bulan <strong id="label-bulan-produksi-bahan"><?php echo htmlspecialchars($bulan_produksi_label, ENT_QUOTES, 'UTF-8'); ?></strong>
                                            </span>
                                        </div>
                                    </div>

                                </div>


                                <?php

                                // print_r($data_bahan_produk_unit);

                                ?>

                                <div class="card-body">

                                    <table id="tglSPOPFreeze" class="display nowrap" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th style="text-align:left" width="30px">No</th>
                                                <th style="text-align:left">Action</th>

                                                <th style="text-align:left">Gudang</th>
                                                <th style="text-align:center">Tanggal Beli</th>
                                                <th style="text-align:left">Nama Barang</th>
                                                <th style="text-align:center">Jumlah</th>
                                                <th style="text-align:center">Satuan</th>
                                                <!-- <th>Konsumen</th> -->
                                                <th style="text-align:right">Harga Satuan</th>
                                                <th style="text-align:right">Harga Total</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $compare_spop = 0;
                                            $Total_per_SPOP = 0;
                                            $TOTAL_LUNAS = 0;
                                            $TOTAL_HUTANG = 0;
                                            $TOTAL_HARGA = 0;
                                            $start = 0;
                                            $jumlah_barang = 0;
                                            foreach ((isset($data_bahan_produk_unit) ? $data_bahan_produk_unit : array()) as $list_data) {
                                                $tanggal_beli_bahan_tampil = '-';
                                                $bulan_bahan_ym = '';
                                                $sesuai_bulan_bahan = false;
                                                if (!empty($list_data->uuid_persediaan_bahan)) {
                                                    $this->db->where('uuid_persediaan', $list_data->uuid_persediaan_bahan);
                                                    $row_persediaan_bahan = $this->db->get('persediaan')->row();
                                                    if ($row_persediaan_bahan && !empty($row_persediaan_bahan->tanggal_beli)) {
                                                        $ts_beli_bahan = strtotime($row_persediaan_bahan->tanggal_beli);
                                                        if ($ts_beli_bahan) {
                                                            $tanggal_beli_bahan_tampil = date('d-M-Y', $ts_beli_bahan);
                                                            $bulan_bahan_ym = date('Y-m', $ts_beli_bahan);
                                                            $sesuai_bulan_bahan = ($bulan_bahan_ym === $bulan_produksi_ym);
                                                        }
                                                    }
                                                }
                                            ?>


                                                <tr class="<?php echo $sesuai_bulan_bahan ? '' : 'table-danger'; ?>">

                                                    <td style="text-align:center"><?php echo ++$start ?></td>

                                                    <td align="left">

                                                        <!-- <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#modal-xl-input-barang_<?php //echo $list_data->id 
                                                                                                                                                                            ?>">
                                                            UBAH <?php //echo $list_data->id 
                                                                    ?>
                                                        </button> -->

                                                        <?php
                                                        // echo anchor(site_url('tbl_pembelian/create_add_uraian_update/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH</i>', 'class="btn btn-warning btn-xs"');

                                                        // echo anchor(site_url('tbl_pembelian/delete_by_uuid_pembelian_from_per_spop_update/' . $list_data->uuid_pembelian . '/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">HAPUS</i>', 'class="btn btn-danger btn-xs"');

                                                        echo anchor(site_url('Sys_unit_produk_bahan/delete/' . $list_data->id . '/Sys_unit_produk/create_produksi/' . $id_persediaan_barang), '<i class="fa fa-trash-o" aria-hidden="true">Hapus Bahan</i>', 'class="btn btn-danger btn-sm" Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');

                                                        ?>



                                                    </td>
                                                    <td align="left"><?php //echo $list_data->nama_gudang; 
                                                                        ?></td>
                                                    <td align="center" style="<?php echo $sesuai_bulan_bahan ? '' : 'color:#c0392b;font-weight:bold;'; ?>">
                                                        <?php echo htmlspecialchars($tanggal_beli_bahan_tampil, ENT_QUOTES, 'UTF-8'); ?>
                                                        <?php if (!$sesuai_bulan_bahan && $bulan_bahan_ym !== '') { ?>
                                                            <br /><small>Bulan tidak sesuai</small>
                                                        <?php } ?>
                                                    </td>
                                                    <td align="left"><?php echo $list_data->nama_barang_bahan; ?></td>
                                                    <td align="center">
                                                        <?php
                                                        // echo nominal($list_data->jumlah);                                                         
                                                        echo number_format($list_data->jumlah_bahan, 0, ',', '.');
                                                        $jumlah_barang = $jumlah_barang + $list_data->jumlah_bahan;

                                                        ?>
                                                    </td>
                                                    <td align="center"><?php echo $list_data->satuan_bahan; ?></td>

                                                    <td align="right">
                                                        <?php
                                                        // echo nominal($list_data->harga_satuan); 
                                                        echo number_format($list_data->harga_satuan_bahan, 2, ',', '.');
                                                        ?>
                                                    </td>
                                                    <td align="right">
                                                        <?php
                                                        $total_per_uraian = $list_data->jumlah_bahan * $list_data->harga_satuan_bahan;

                                                        // echo nominal($total_per_uraian);

                                                        echo number_format($total_per_uraian, 2, ',', '.');

                                                        $TOTAL_HARGA = $TOTAL_HARGA + $total_per_uraian;


                                                        ?>
                                                    </td>

                                                </tr>





                                            <?php
                                            }
                                            ?>

                                        </tbody>

                                        <tfoot>
                                            <tr>
                                                <th style="text-align:left" width="30px"></th>
                                                <th style="text-align:left"></th>
                                                <th style="text-align:left"></th>
                                                <th style="text-align:center"></th>
                                                <th style="text-align:right">TOTAL</th>
                                                <th style="text-align:center"><?php
                                                                                // echo $jumlah_barang; 
                                                                                echo number_format($jumlah_barang, 0, ',', '.');
                                                                                ?></th>
                                                <th style="text-align:center">

                                                </th>
                                                <th style="text-align:right"></th>
                                                <th style="text-align:right">
                                                    <?php
                                                    // echo $Total_per_SPOP; 
                                                    echo number_format($TOTAL_HARGA, 2, ',', '.');
                                                    ?>
                                                </th>
                                            </tr>
                                        </tfoot>


                                    </table>
                                </div>

                                <div class="card-body border-top pt-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <small class="text-muted d-block" id="info-btn-produk-baru">
                                                <?php if ($jumlah_bahan_count < 1) { ?>
                                                    Tambahkan minimal 1 bahan pada tabel di atas untuk mengaktifkan tombol simpan.
                                                <?php } elseif (empty($id_persediaan_barang)) { ?>
                                                    Simpan bahan terlebih dahulu agar data produk dapat disimpan.
                                                <?php } else { ?>
                                                    Lengkapi semua isian produk di atas untuk menyimpan data produk.
                                                <?php } ?>
                                            </small>
                                        </div>
                                        <div class="col-md-4 text-md-right mt-2 mt-md-0">
                                            <button type="submit" form="form-produk-baru" class="btn btn-primary btn-lg" id="btn-simpan-produk-baru" <?php echo $btn_produk_siap ? '' : 'disabled'; ?>>
                                                <?php echo htmlspecialchars($label_btn_produk, ENT_QUOTES, 'UTF-8'); ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row" align="center">
                                        <div class="col-12">
                                            <a href="<?php echo site_url('Sys_unit_produk') ?>" class="btn btn-success">Kembali ke data produk</a>
                                        </div>
                                    </div>
                                </div>

                            </div>



                        <!-- TAMBAH BARANG MODAL EXTRA LARGE -->
                        <form action="<?php echo $action_simpan_bahan; ?>" method="post">
                            <div class="modal fade" id="modal-xl-select-unit_bu">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Input Bahan Produksi</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="form-group">


                                                <div class="row">
                                                    <div class="col-4">
                                                        <label for="uuid_persediaan">Bahan <?php echo form_error('uuid_persediaan') ?></label>

                                                        <select name="uuid_persediaan" id="uuid_persediaan" class="form-control select2" style="width: 100%; height: 80px;" required>
                                                            <option value="">Pilih Bahan</option>
                                                            <?php

                                                            // $sql = "SELECT `uuid_barang`,`kode_barang`,`nama_barang` FROM `sys_nama_barang` ORDER by `nama_barang` ASC";
                                                            $sql = "SELECT `uuid_persediaan`,`uuid_barang`,`kode_barang`,`namabarang`,`spop` FROM `persediaan` WHERE `namabarang`<>'' GROUP by `namabarang`,`spop`,`satuan`";
                                                            foreach ($this->db->query($sql)->result() as $m) {
                                                                echo "<option value='$m->uuid_persediaan' ";
                                                                echo ">  " . strtoupper($m->namabarang) . " --> SPOP: " . $m->spop . "</option>";
                                                            }
                                                            ?>
                                                        </select>

                                                        <div class="row">
                                                            <div class="col-8">
                                                                <?php //echo anchor(site_url('sys_nama_barang/create/pembelian'), 'Input Barang Baru', 'class="btn btn-block btn-danger"'); 
                                                                ?>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="col-4">
                                                        <label for="jumlah">Jumlah <?php //echo form_error('nmrpesan') 
                                                                                    ?></label>
                                                        <!-- <input type="text" class="form-control" rows="3" name="jumlah" id="jumlah" placeholder="Jumlah" required> -->
                                                        <input type="text" name="jumlah" id="jumlah" placeholder="Jumlah" class="form-control" required>
                                                    </div>

                                                </div>



                                            </div>

                                        </div>

                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                            <!-- <button type="button" class="btn btn-primary">Simpan</button> -->
                                            <button type="submit" class="btn btn-primary">Proses</button>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                        </form>
                        <!-- END OF MODAL EXTRA LARGE -->






                    </div>
                    <!-- /.card-body -->
                </div>

            </div>
        </div>
        <!-- <div class="col-md-1"></div> -->
    </section>

</div>













<!-- MODAL TABEL DATA PERSEDIAAN DAN SUB MODAL UNTUK INPUT JUMLAH STOCK YANG DI GUNAKAN -->
<!-- /.modal -->

<div class="modal fade" id="modal-xl-select-unit">
    <div class="modal-dialog modal-xl modal-pilih-barang-wide">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title mb-0">
                    Pilih Barang
                    <small class="text-muted d-block d-md-inline ml-md-2" id="modal-pilih-barang-bulan-label">
                        (Bulan: <?php echo htmlspecialchars($bulan_produksi_label, ENT_QUOTES, 'UTF-8'); ?>)
                    </small>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-1">

                <!-- ----------- -->

                <div id="stock-persediaan-modal-body">
                    <?php $this->load->view('anekadharma/sys_unit_produk/_partial_modal_stock_persediaan_table', array(
                        'Data_stock' => isset($Data_stock) ? $Data_stock : array(),
                        'action_simpan_bahan' => $action_simpan_bahan,
                        'tgl_transaksi_bahan' => $tgl_transaksi_bahan,
                        'bulan_produksi_ym' => $bulan_produksi_ym,
                    )); ?>
                </div>
                <!-- ----------- -->



            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- Modal isi jumlah bahan (di luar modal pilih barang agar tidak tertutup overflow) -->
<div class="modal fade" id="modal-input-jumlah-bahan" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="form-simpan-bahan-produksi" method="post" action="<?php echo isset($action_simpan_bahan) ? $action_simpan_bahan : ''; ?>">
                <div class="modal-header">
                    <h4 class="modal-title">Isi Jumlah Bahan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info py-2 mb-3" id="input-bahan-info-tanggal"></div>
                    <div class="form-group">
                        <label>Barang</label>
                        <input type="text" class="form-control" id="input-bahan-nama-tampil" disabled>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Harga Satuan</label>
                            <input type="text" class="form-control" name="harga_satuan_beli" id="input-bahan-harga-satuan" readonly>
                        </div>
                        <div class="col-md-6">
                            <label style="color:red" id="input-bahan-label-maks">Jumlah Maks</label>
                            <input type="number" class="form-control" name="jumlah" id="input-bahan-jumlah" min="1" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <input type="hidden" name="uuid_persediaan" id="input-bahan-uuid-persediaan" value="">
                    <input type="hidden" name="id_persediaan" id="input-bahan-id-persediaan" value="">
                    <input type="hidden" name="tgl_transaksi" id="input-bahan-tgl-transaksi" value="">
                    <input type="hidden" name="bulan_produksi" id="input-bahan-bulan-produksi" value="">
                    <input type="hidden" name="draft_nama_barang" id="draft-nama-barang" value="">
                    <input type="hidden" name="draft_satuan" id="draft-satuan" value="">
                    <input type="hidden" name="draft_harga_satuan" id="draft-harga-satuan" value="">
                    <input type="hidden" name="draft_tgl_transaksi" id="draft-tgl-transaksi" value="">
                    <input type="hidden" name="draft_uuid_unit" id="draft-uuid-unit" value="">
                    <input type="hidden" name="draft_jumlah_produksi" id="draft-jumlah-produksi" value="">
                    <input type="hidden" name="draft_keterangan" id="draft-keterangan" value="">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">PROSES SIMPAN BAHAN</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- /.modal -->

<!-- ============== -->




<?php

foreach ($data_penjualan_per_uuid_penjualan as $list_data) {
?>
    <!-- MODAL EXTRA LARGE UPDATE PER ID -->
    <form action="<?php echo $action_ubah_per_id . $list_data->id; ?>" method="post">
        <div class="modal fade" id="modal-xl-input-barang_<?php echo $list_data->id ?>">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Update Barang <?php echo $list_data->id
                                                                ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <?php
                    // echo $action_ubah_per_id . $list_data->id;

                    $row_data_barang_jual = $this->Tbl_penjualan_model->get_by_id($list_data->id);

                    // cek data stock persediaan dengan filter by id_persediaan_barang dari tabel penjualan


                    $get_data_Persediaan_by_id = $this->Persediaan_model->get_by_id($row_data_barang_jual->id_persediaan_barang);

                    // Jumlah stock dikurangi , sudah terjual yang dikurang barang terjual di id penjualan ini
                    $Get_stock_di_persediaan = $get_data_Persediaan_by_id->total_10 - ($get_data_Persediaan_by_id->penjualan - $row_data_barang_jual->jumlah);

                    // print_r($row_data_barang_jual);

                    // `id`, `uuid_penjualan_proses`, `uuid_penjualan`, `uuid_persediaan`, `id_persediaan_barang`, `uuid_barang`, `tgl_input`, `tgl_jual`, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `umpphpsl22`, `piutang`, `penjualandpp`, `utangppn`, `cetak_bukti_penjualan`, `id_usr`, ``, ``, ``, ``, ``, ``


                    ?>

                    <div class="modal-body">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-12">
                                    <label for="konsumen_nama">Barang</label>
                                    <input type="text" class="form-control" rows="3" name="nama_barang" id="nama_barang" placeholder="nama_barang" value="<?php echo $row_data_barang_jual->nama_barang ?>" disabled>
                                </div>
                            </div>



                            <div class="row">
                                <div class="col-4">
                                    <label for="nmrpesan">Harga Satuan </label>
                                    <!-- <input type="text" class="form-control" rows="3" name="harga_satuan_beli" id="harga_satuan_beli" value="<?php // echo number_format($row_data_barang_jual->harga_satuan, 2, ',', '.'); 
                                                                                                                                                    ?>" placeholder="<?php // echo nominal($list_data->harga_satuan_persediaan);  echo number_format($list_data->harga_satuan_persediaan, 2, ',', '.'); 
                                                                                                                                                                        ?>"> -->
                                </div>
                                <div class="col-4">
                                    <label style="color:red" for="nmrkirim">Jumlah Maks= <?php echo $Get_stock_di_persediaan; ?></label>
                                </div>
                            </div>
                            <div class="row">


                                <div class="col-4">
                                    <input type="text" class="form-control" rows="3" name="harga_satuan" id="harga_satuan" value="
                                    <?php echo number_format($row_data_barang_jual->harga_satuan, 2, ',', '.'); ?>" placeholder="<?php echo number_format($row_data_barang_jual->harga_satuan, 2, ',', '.'); ?>">
                                </div>
                                <div class="col-4">
                                    <!-- <input type="text" class="form-control" rows="3" name="jumlah" id="jumlah" min="1" max="5" placeholder="jumlah"> -->
                                    <input type="number" class="form-control" id="jumlah" name="jumlah" value="<?php echo $row_data_barang_jual->jumlah; ?>" min="1" max="<?php echo $Get_stock_di_persediaan ?>">

                                </div>

                            </div>

                        </div>


                    </div>


                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <!-- <button type="button" class="btn btn-primary">Simpan</button> -->
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </form>
    <!-- END OF MODAL EXTRA LARGE -->
<?php
}
?>


<!-- END OF MODAL TABEL DATA PERSEDIAAN DAN SUB MODAL UNTUK INPUT JUMLAH STOCK YANG DI GUNAKAN -->







<style type="text/css">
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
    #keterangan-bulan-produksi-bahan,
    #keterangan-bulan-produksi-bahan strong,
    #label-bulan-produksi-bahan {
        color: #ffeb3b;
        font-weight: 700;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.35);
    }
    #modal-xl-select-unit {
        align-items: flex-start;
        padding-top: 1cm;
        padding-bottom: 1cm;
    }
    #modal-xl-select-unit .modal-pilih-barang-wide {
        max-width: 98%;
        width: 98%;
        height: calc(100vh - 2cm);
        max-height: calc(100vh - 2cm);
        margin: 0 auto;
    }
    #modal-xl-select-unit .modal-content {
        height: 100%;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    #modal-xl-select-unit .modal-header {
        flex: 0 0 auto;
        padding: 0.5rem 0.85rem;
    }
    #modal-xl-select-unit .modal-body {
        flex: 1 1 auto;
        overflow: hidden !important;
        padding: 0.25rem 0.35rem 0.35rem;
        min-height: 0;
    }
    #stock-persediaan-modal-body {
        height: 100%;
        overflow: hidden;
    }
    #stock-persediaan-modal-body .card-body {
        height: 100%;
    }
    #stock-persediaan-modal-body .dataTables_wrapper {
        width: 100%;
        position: relative;
    }
    #stock-persediaan-modal-body .dataTables_filter {
        margin-bottom: 0.35rem;
    }
    #stock-persediaan-modal-body .dataTables_scroll {
        overflow: visible;
    }
    #stock-persediaan-modal-body .dataTables_scrollHead {
        overflow: hidden !important;
    }
    #stock-persediaan-modal-body .dataTables_scrollBody {
        overflow-x: auto !important;
        overflow-y: auto !important;
        -webkit-overflow-scrolling: touch;
        border-bottom: 1px solid #dee2e6;
    }
    #stock-persediaan-modal-body .dataTables_scrollHeadInner table,
    #stock-persediaan-modal-body .dataTables_scrollBody table {
        min-width: 1100px !important;
        width: 1100px !important;
    }
    #stock-persediaan-modal-body table#example {
        margin-bottom: 0 !important;
    }
    #stock-persediaan-modal-body table#example th,
    #stock-persediaan-modal-body table#example td {
        white-space: nowrap;
        vertical-align: middle !important;
    }
    #stock-persediaan-modal-body .dataTables_info {
        padding: 0.4rem 0.25rem 0.15rem;
        font-size: 0.875rem;
        float: left;
    }
    #stock-persediaan-modal-body .dataTables_paginate {
        padding: 0.15rem 0.25rem 0.35rem;
        font-size: 0.875rem;
        float: right;
    }
    #stock-persediaan-modal-body .dataTables_wrapper::after {
        content: '';
        display: block;
        clear: both;
    }
</style>

<script>
window.addEventListener('load', function() {
    if (!window.jQuery || !jQuery.fn || !jQuery.fn.dataTable) {
        console.error('Produksi bahan: jQuery/DataTables belum dimuat.');
        return;
    }
    var $ = window.jQuery;
    var urlAjaxStockPersediaan = <?php echo json_encode($url_ajax_stock_persediaan); ?>;
    var actionSimpanBahan = <?php echo json_encode(isset($action_simpan_bahan) ? $action_simpan_bahan : ''); ?>;
    var idPersediaanBarang = <?php echo json_encode(isset($id_persediaan_barang) ? $id_persediaan_barang : ''); ?>;
    var bulanProduksiBahanAktif = <?php echo json_encode($bulan_produksi_selected); ?>;
    var jumlahBahanMin = <?php echo (int) $jumlah_bahan_count; ?>;
    var idPersediaanProduk = <?php echo json_encode(isset($id_persediaan_barang) ? (int) $id_persediaan_barang : 0); ?>;
    var actionSimpanProdukForm = <?php echo json_encode($action_simpan_produk_form); ?>;
    var labelBtnProdukSimpan = <?php echo json_encode('Simpan'); ?>;
    var labelBtnProdukUpdate = <?php echo json_encode('Update Produk'); ?>;
    var produkSudahAda = <?php echo $produk_sudah_ada ? 'true' : 'false'; ?>;
    var produkDraftServer = <?php echo json_encode($produk_draft); ?>;
    var hapusProdukDraftClient = <?php echo $hapus_produk_draft_client ? 'true' : 'false'; ?>;
    var storageKeyProdukDraft = 'produksi_form_draft_' + (idPersediaanProduk > 0 ? idPersediaanProduk : 'baru');

    var namaBulanIndonesia = {
        '01': 'Januari', '02': 'Februari', '03': 'Maret', '04': 'April',
        '05': 'Mei', '06': 'Juni', '07': 'Juli', '08': 'Agustus',
        '09': 'September', '10': 'Oktober', '11': 'November', '12': 'Desember'
    };

    function parseBulanYmDariTglProduk(tglStr) {
        tglStr = String(tglStr || '').trim();
        if (!tglStr) {
            return '';
        }
        var m = tglStr.match(/^(\d{2})-(\d{2})-(\d{4})/);
        if (m) {
            return m[3] + '-' + m[2];
        }
        var d = new Date(tglStr);
        if (!isNaN(d.getTime())) {
            var bulan = String(d.getMonth() + 1);
            if (bulan.length < 2) {
                bulan = '0' + bulan;
            }
            return d.getFullYear() + '-' + bulan;
        }
        return '';
    }

    function labelBulanIndonesia(bulanYm) {
        if (!bulanYm || !/^\d{4}-\d{2}$/.test(bulanYm)) {
            return '';
        }
        var parts = bulanYm.split('-');
        return (namaBulanIndonesia[parts[1]] || parts[1]) + ' ' + parts[0];
    }

    function syncBulanDariTglProduk(reloadModalStock) {
        var tglProduk = $('#tgl_transaksi_produk').val() || '';
        var bulanYm = parseBulanYmDariTglProduk(tglProduk);
        if (!bulanYm) {
            bulanYm = $('#bulan_produksi_bahan').val() || bulanProduksiBahanAktif;
        }
        if (!bulanYm) {
            return '';
        }
        bulanProduksiBahanAktif = bulanYm;
        $('#bulan_produksi_bahan').val(bulanYm);
        $('#form-produk-baru input[name="bulan_produksi"]').val(bulanYm);
        updateLabelBulanProduksi(labelBulanIndonesia(bulanYm));
        if (reloadModalStock && $('#modal-xl-select-unit').hasClass('show')) {
            loadStockPersediaanByBulan(bulanYm, adjustStockDataTableColumns);
        }
        return bulanYm;
    }

    function getNilaiFieldProduk($el) {
        if (!$el || !$el.length) {
            return '';
        }
        var val = $el.val();
        if (val === null || val === undefined) {
            return '';
        }
        val = String(val).trim();
        if ($el.hasClass('uang')) {
            return val.replace(/\./g, '');
        }
        return val;
    }

    function kumpulkanDataFormProduk() {
        return {
            tgl_transaksi: getNilaiFieldProduk($('#tgl_transaksi_produk')),
            uuid_unit: getNilaiFieldProduk($('#uuid_unit_produk')),
            jumlah_produksi: getNilaiFieldProduk($('#jumlah_produksi_produk')),
            nama_barang: getNilaiFieldProduk($('#nama_barang_produk')),
            satuan: getNilaiFieldProduk($('#satuan_produk')),
            harga_satuan: getNilaiFieldProduk($('#harga_satuan_produk')),
            keterangan: getNilaiFieldProduk($('#keterangan_produk'))
        };
    }

    function simpanDraftProdukKeStorage() {
        try {
            localStorage.setItem(storageKeyProdukDraft, JSON.stringify(kumpulkanDataFormProduk()));
        } catch (e) {}
    }

    function formatUangTampil(angka) {
        angka = String(angka || '').replace(/[^0-9]/g, '');
        if (!angka) {
            return '';
        }
        return angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function terapkanDataFormProduk(data) {
        if (!data || typeof data !== 'object') {
            return;
        }
        if (data.tgl_transaksi) {
            $('#tgl_transaksi_produk').val(data.tgl_transaksi);
        }
        if (data.uuid_unit) {
            $('#uuid_unit_produk').val(data.uuid_unit).trigger('change');
        }
        if (data.jumlah_produksi !== undefined && data.jumlah_produksi !== '') {
            $('#jumlah_produksi_produk').val(formatUangTampil(data.jumlah_produksi)).trigger('input');
        }
        if (data.nama_barang) {
            $('#nama_barang_produk').val(data.nama_barang);
        }
        if (data.satuan) {
            $('#satuan_produk').val(data.satuan);
        }
        if (data.harga_satuan !== undefined && data.harga_satuan !== '') {
            $('#harga_satuan_produk').val(formatUangTampil(data.harga_satuan)).trigger('input');
        }
        if (data.keterangan) {
            $('#keterangan_produk').val(data.keterangan);
        }
    }

    function muatDraftProdukKeForm() {
        if (hapusProdukDraftClient) {
            try {
                localStorage.removeItem(storageKeyProdukDraft);
            } catch (e) {}
            return;
        }

        var data = null;
        try {
            var raw = localStorage.getItem(storageKeyProdukDraft);
            if (raw) {
                data = JSON.parse(raw);
            }
        } catch (e) {}

        if ((!data || !Object.keys(data).length) && produkDraftServer && Object.keys(produkDraftServer).length) {
            data = produkDraftServer;
        }
        if (!data || !Object.keys(data).length) {
            return;
        }

        terapkanDataFormProduk(data);
    }

    function salinDraftProdukKeFormBahan() {
        simpanDraftProdukKeStorage();
        var data = kumpulkanDataFormProduk();
        $('#draft-nama-barang').val(data.nama_barang || '');
        $('#draft-satuan').val(data.satuan || '');
        $('#draft-harga-satuan').val(data.harga_satuan || '');
        $('#draft-tgl-transaksi').val(data.tgl_transaksi || '');
        $('#draft-uuid-unit').val(data.uuid_unit || '');
        $('#draft-jumlah-produksi').val(data.jumlah_produksi || '');
        $('#draft-keterangan').val(data.keterangan || '');
    }

    function parseMomentTglProduk(val) {
        if (typeof moment === 'undefined') {
            return null;
        }
        val = String(val || '').trim();
        if (!val) {
            return null;
        }
        var m = moment(val, 'DD-MM-YYYY HH:mm:ss', true);
        if (!m.isValid()) {
            m = moment(val, 'DD-MM-YYYY', true);
        }
        return m.isValid() ? m : null;
    }

    function ambilBagianWaktuTglProduk(val) {
        var match = String(val || '').match(/(\d{2}:\d{2}:\d{2})/);
        return match ? match[1] : '00:00:00';
    }

    var syncingTglProdukPicker = false;

    function setTglProdukDariPicker(momentDate) {
        if (syncingTglProdukPicker) {
            return;
        }
        if (!momentDate || typeof moment === 'undefined' || !moment.isMoment(momentDate) || !momentDate.isValid()) {
            return;
        }
        syncingTglProdukPicker = true;
        var $wrap = $('#tgl_transaksi_produk_wrap');
        var $input = $('#tgl_transaksi_produk');
        var waktu = ambilBagianWaktuTglProduk($input.val());
        var formatted = momentDate.format('DD-MM-YYYY') + ' ' + waktu;
        $input.val(formatted);
        $wrap.datetimepicker('date', momentDate);
        $wrap.datetimepicker('hide');
        syncingTglProdukPicker = false;
        syncBulanDariTglProduk(true);
        simpanDraftProdukKeStorage();
        refreshTombolProdukBaru();
    }

    if ($('#tgl_transaksi_produk_wrap').length && $.fn.datetimepicker) {
        var $tglProdukWrap = $('#tgl_transaksi_produk_wrap');
        $tglProdukWrap.datetimepicker({
            format: 'DD-MM-YYYY',
            useCurrent: false,
            allowInputToggle: true,
            icons: {
                time: 'far fa-clock',
                date: 'far fa-calendar',
                up: 'fas fa-arrow-up',
                down: 'fas fa-arrow-down',
                previous: 'fas fa-chevron-left',
                next: 'fas fa-chevron-right',
                today: 'far fa-calendar-check',
                clear: 'far fa-trash-alt',
                close: 'fas fa-times'
            }
        });

        var tglProdukAwal = parseMomentTglProduk($('#tgl_transaksi_produk').val());
        if (tglProdukAwal) {
            $tglProdukWrap.datetimepicker('date', tglProdukAwal);
        }

        $tglProdukWrap.on('show.datetimepicker', function() {
            var m = parseMomentTglProduk($('#tgl_transaksi_produk').val());
            if (m) {
                $tglProdukWrap.datetimepicker('date', m);
            }
        });

        $tglProdukWrap.on('change.datetimepicker', function(e) {
            if (syncingTglProdukPicker) {
                return;
            }
            if (e.date) {
                setTglProdukDariPicker(e.date);
            }
        });

        $tglProdukWrap.on('hide.datetimepicker', function() {
            var m = parseMomentTglProduk($('#tgl_transaksi_produk').val());
            if (m) {
                $tglProdukWrap.datetimepicker('date', m);
            }
        });
    }
    $('#tgl_transaksi_produk').on('change blur', function() {
        var m = parseMomentTglProduk($(this).val());
        if (m && $('#tgl_transaksi_produk_wrap').length && $.fn.datetimepicker) {
            $('#tgl_transaksi_produk_wrap').datetimepicker('date', m);
        }
        syncBulanDariTglProduk(true);
    });
    if ($('#uuid_unit_produk').length && $.fn.select2) {
        $('#uuid_unit_produk').select2({ width: '100%' });
    }

    function fieldProdukBaruTerisi() {
        var ok = true;
        $('#form-produk-baru .field-produk-baru[required]').each(function() {
            var val = $(this).val();
            if (val === null || String(val).trim() === '') {
                ok = false;
            }
        });
        return ok;
    }

    function refreshTombolProdukBaru() {
        var siap = fieldProdukBaruTerisi() && jumlahBahanMin >= 1 && idPersediaanProduk > 0 && actionSimpanProdukForm;
        var $btn = $('#btn-simpan-produk-baru');
        $btn.prop('disabled', !siap);
        if (produkSudahAda && jumlahBahanMin >= 1 && fieldProdukBaruTerisi()) {
            $btn.text(labelBtnProdukUpdate);
        } else {
            $btn.text(labelBtnProdukSimpan);
        }
        var $info = $('#info-btn-produk-baru');
        if (jumlahBahanMin < 1) {
            $info.text('Tambahkan minimal 1 bahan pada tabel di bawah untuk mengaktifkan tombol simpan.');
        } else if (idPersediaanProduk <= 0) {
            $info.text('Simpan bahan terlebih dahulu agar data produk dapat disimpan.');
        } else if (!fieldProdukBaruTerisi()) {
            $info.text('Lengkapi semua isian untuk menyimpan data produk.');
        } else {
            $info.text(produkSudahAda ? 'Data produk siap diperbarui.' : 'Data produk siap disimpan.');
        }
    }

    $(document).on('input change', '#form-produk-baru .field-produk-baru', function() {
        simpanDraftProdukKeStorage();
        refreshTombolProdukBaru();
    });

    $('#form-produk-baru').on('submit', function() {
        try {
            localStorage.removeItem(storageKeyProdukDraft);
        } catch (e) {}
    });

    $('#modal-input-jumlah-bahan').appendTo('body');

    $('#form-simpan-bahan-produksi').on('submit', function() {
        salinDraftProdukKeFormBahan();
    });

    $(document).on('click', '.btn-pilih-barang-bahan', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var $btn = $(this);
        salinDraftProdukKeFormBahan();
        var bulanYm = syncBulanDariTglProduk(false) || $('#bulan_produksi_bahan').val() || bulanProduksiBahanAktif;
        var tglTransaksi = $('#tgl_transaksi_produk').val() || (bulanYm ? (bulanYm + '-01') : '');
        $('#form-simpan-bahan-produksi').attr('action', actionSimpanBahan);
        $('#input-bahan-id-persediaan').val($btn.data('id-persediaan'));
        $('#input-bahan-uuid-persediaan').val($btn.data('uuid-persediaan'));
        $('#input-bahan-nama-tampil').val($btn.data('nama-barang'));
        $('#input-bahan-harga-satuan').val($btn.data('harga-satuan'));
        $('#input-bahan-jumlah').val('').attr('max', $btn.data('sisa-stock'));
        $('#input-bahan-label-maks').text('Jumlah Maks= ' + $btn.data('sisa-stock'));
        $('#input-bahan-tgl-transaksi').val(tglTransaksi);
        $('#input-bahan-bulan-produksi').val(bulanYm);
        $('#input-bahan-info-tanggal').html(
            'Tanggal beli persediaan: <strong>' + $btn.data('tanggal-beli') + '</strong>'
        );
        $('#modal-input-jumlah-bahan').modal({
            backdrop: 'static',
            keyboard: true,
            show: true
        });
    });

    $('#modal-input-jumlah-bahan').on('show.bs.modal', function() {
        if ($('#modal-xl-select-unit').hasClass('show')) {
            var z = parseInt($('#modal-xl-select-unit').css('z-index'), 10) || 1050;
            $(this).css('z-index', z + 20);
            setTimeout(function() {
                $('.modal-backdrop').last().css('z-index', z + 10);
            }, 0);
        }
    });

    $('#modal-input-jumlah-bahan').on('hidden.bs.modal', function() {
        if ($('#modal-xl-select-unit').hasClass('show')) {
            $('body').addClass('modal-open');
        }
    });

    function destroyStockDataTable() {
        if ($.fn.DataTable.isDataTable('#example')) {
            $('#example').DataTable().destroy();
        }
    }

    function getStockDataTableScrollHeight() {
        var $modalBody = $('#modal-xl-select-unit .modal-body');
        var bodyH = $modalBody.length ? $modalBody.innerHeight() : ($(window).height() - 120);
        var $wrapper = $('#stock-persediaan-modal-body .dataTables_wrapper');
        var reserved = 12;
        if ($wrapper.length) {
            reserved += $wrapper.find('.dataTables_filter').outerHeight(true) || 40;
            reserved += $wrapper.find('.dataTables_info').outerHeight(true) || 28;
            reserved += $wrapper.find('.dataTables_paginate').outerHeight(true) || 34;
            reserved += 18;
        } else {
            reserved = 120;
        }
        return Math.max(180, bodyH - reserved) + 'px';
    }

    function bindStockDataTableScrollSync() {
        if (!$.fn.DataTable.isDataTable('#example')) {
            return;
        }
        var $container = $('#example').closest('.dataTables_wrapper');
        var $scrollBody = $container.find('.dataTables_scrollBody');
        var $scrollHead = $container.find('.dataTables_scrollHead');
        $scrollBody.off('scroll.stockSync').on('scroll.stockSync', function() {
            $scrollHead.scrollLeft($scrollBody.scrollLeft());
        });
    }

    function initStockDataTable() {
        destroyStockDataTable();
        if (!$('#example').length) {
            return null;
        }
        var dt = $('#example').DataTable({
            scrollY: getStockDataTableScrollHeight(),
            scrollX: true,
            scrollCollapse: false,
            autoWidth: false,
            paging: true,
            pageLength: 25,
            lengthChange: false,
            searching: true,
            info: true,
            ordering: true,
            dom: 'frtip',
            language: {
                emptyTable: 'Belum ada stock persediaan pada bulan terpilih',
                info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
                infoFiltered: '(disaring dari _MAX_ data)',
                search: 'Cari:',
                zeroRecords: 'Tidak ada data yang cocok',
                paginate: {
                    first: 'Awal',
                    last: 'Akhir',
                    next: 'Berikutnya',
                    previous: 'Sebelumnya'
                }
            },
            drawCallback: function() {
                applyStockDataTableScrollHeight();
            }
        });
        bindStockDataTableScrollSync();
        return dt;
    }

    function applyStockDataTableScrollHeight() {
        if (!$.fn.DataTable.isDataTable('#example')) {
            return;
        }
        var dt = $('#example').DataTable();
        var newH = getStockDataTableScrollHeight();
        var $scrollBody = $(dt.table().container()).find('.dataTables_scrollBody');
        $scrollBody.css({
            'max-height': newH,
            'height': newH,
            'overflow-x': 'auto',
            'overflow-y': 'auto'
        });
        dt.columns.adjust();
        bindStockDataTableScrollSync();
    }

    function adjustStockDataTableColumns() {
        applyStockDataTableScrollHeight();
    }

    function updateLabelBulanProduksi(label) {
        $('#label-bulan-produksi-bahan').text(label);
        $('#modal-pilih-barang-bulan-label').text('(Bulan: ' + label + ')');
    }

    function loadStockPersediaanByBulan(bulanYm, callback) {
        if (!bulanYm) {
            return;
        }
        destroyStockDataTable();
        $('#stock-persediaan-modal-body').html('<div class="p-3 text-center text-muted">Memuat data persediaan bulan ' + bulanYm + '...</div>');
        $.ajax({
            url: urlAjaxStockPersediaan,
            type: 'GET',
            data: {
                bulan: bulanYm,
                id_persediaan_barang: idPersediaanBarang
            },
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).done(function(res) {
            if (!res || !res.ok) {
                var msg = res && res.message ? res.message : 'Gagal memuat data persediaan.';
                $('#stock-persediaan-modal-body').html('<div class="p-3 text-center text-danger">' + msg + '</div>');
                return;
            }
            $('#stock-persediaan-modal-body').html(res.html);
            updateLabelBulanProduksi(res.bulan_label);
            initStockDataTable();
            setTimeout(function() {
                applyStockDataTableScrollHeight();
                if (typeof callback === 'function') {
                    callback();
                }
            }, 80);
            return;
        }).fail(function() {
            $('#stock-persediaan-modal-body').html('<div class="p-3 text-center text-danger">Gagal memuat data persediaan.</div>');
        });
    }

    $('#bulan_produksi_bahan').on('change', function() {
        var bulan = $(this).val() || '';
        if (!bulan) {
            return;
        }
        bulanProduksiBahanAktif = bulan;
        if ($('#modal-xl-select-unit').hasClass('show')) {
            loadStockPersediaanByBulan(bulan, adjustStockDataTableColumns);
        }
    });

    $('[data-target="#modal-xl-select-unit"]').on('click', function() {
        salinDraftProdukKeFormBahan();
        syncBulanDariTglProduk(false);
    });

    $('#modal-xl-select-unit').on('show.bs.modal', function() {
        var bulan = syncBulanDariTglProduk(false) || $('#bulan_produksi_bahan').val() || bulanProduksiBahanAktif;
        if (!bulan) {
            return;
        }
        bulanProduksiBahanAktif = bulan;
        loadStockPersediaanByBulan(bulan);
    });

    $('#modal-xl-select-unit').on('shown.bs.modal', function() {
        setTimeout(function() {
            applyStockDataTableScrollHeight();
        }, 80);
    });

    $(window).on('resize', function() {
        if ($('#modal-xl-select-unit').hasClass('show')) {
            applyStockDataTableScrollHeight();
        }
    });

    muatDraftProdukKeForm();
    refreshTombolProdukBaru();
    syncBulanDariTglProduk(false);
});
</script>
