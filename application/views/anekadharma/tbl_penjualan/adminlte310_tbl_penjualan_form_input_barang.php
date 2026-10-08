<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
$this->load->helper('pembelian_persediaan');
if (!isset($filter_bulan_penjualan)) {
	$filter_bulan_penjualan = penjualan_sync_filter_bulan_from_tgl_jual($this, isset($tgl_jual) ? $tgl_jual : null);
}
if (!isset($Data_stock)) {
	$Data_stock = penjualan_get_stock_persediaan_rows(
		$this,
		isset($tgl_jual) ? $tgl_jual : null,
		isset($uuid_unit) ? $uuid_unit : null
	);
}
if (!isset($jumlah_barang_penjualan)) {
	$jumlah_barang_penjualan = 0;
}
if (!isset($penjualan_bulan_key)) {
	$penjualan_bulan_key = penjualan_get_bulan_key_from_tgl(isset($tgl_jual) ? $tgl_jual : null);
}
if (!isset($uuid_penjualan)) {
	$uuid_penjualan = '';
}
if (!isset($penjualan_list_bulan_key)) {
	$list_ctx_penjualan = penjualan_get_list_bulan_context($this);
	$penjualan_list_bulan_key = $list_ctx_penjualan['bulan_key'];
	$penjualan_list_bulan_label = $list_ctx_penjualan['bulan_label'];
}
if (!isset($penjualan_list_bulan_label)) {
	$penjualan_list_bulan_label = penjualan_get_bulan_label_from_key(isset($penjualan_list_bulan_key) ? $penjualan_list_bulan_key : '');
}
if (!isset($penjualan_redirect_list_url)) {
	$penjualan_redirect_list_url = penjualan_build_redirect_list_url($this, isset($tgl_jual) ? $tgl_jual : null);
}
if (!isset($action_ubah_detail_nomor_kirim) || trim((string) $action_ubah_detail_nomor_kirim) === '') {
	$action_ubah_detail_nomor_kirim = site_url('tbl_penjualan/action_ubah_detail_nomor_kirim');
}
if (!isset($action_hapus_group_penjualan)) {
	$action_hapus_group_penjualan = site_url('tbl_penjualan/hapus_group_detail_penjualan');
}
if (!isset($action_ubah_group_penjualan)) {
	$action_ubah_group_penjualan = site_url('tbl_penjualan/ubah_group_detail_penjualan/');
}
$tgl_jual_X_modal = isset($tgl_jual) ? penjualan_format_tgl_jual_tampil($tgl_jual) : date('d-m-Y');
$render_modal_pilih_barang = penjualan_render_modal_pilih_barang($this, array(
	'Data_stock' => $Data_stock,
	'tgl_jual' => isset($tgl_jual) ? $tgl_jual : null,
	'tgl_jual_X' => $tgl_jual_X_modal,
	'uuid_penjualan' => $uuid_penjualan,
	'action' => isset($action) ? $action : site_url('tbl_penjualan/create_action_simpan_barang/'),
	'uuid_unit' => isset($uuid_unit) ? $uuid_unit : '',
	'uuid_konsumen' => isset($uuid_konsumen) ? $uuid_konsumen : '',
	'nmrpesan' => isset($nmrpesan) ? $nmrpesan : '',
	'nmrkirim' => isset($nmrkirim) ? $nmrkirim : '',
));
?>
<style>
	/* Modal pilih barang: ~1 cm dari pinggir layar (kiri/kanan/atas/bawah) */
	#modal-xl.modal-pilih-barang-penjualan {
		padding: 1cm !important;
	}
	#modal-xl.modal-pilih-barang-penjualan .modal-dialog.modal-pilih-barang-wide {
		max-width: calc(100vw - 2cm);
		width: calc(100vw - 2cm);
		max-height: calc(100vh - 2cm);
		height: calc(100vh - 2cm);
		margin: 0 auto;
	}
	#modal-xl.modal-pilih-barang-penjualan .modal-content {
		height: 100%;
		max-height: 100%;
		display: flex;
		flex-direction: column;
	}
	#modal-xl.modal-pilih-barang-penjualan .modal-header {
		flex: 0 0 auto;
		padding: 0.5rem 0.75rem;
	}
	#modal-xl.modal-pilih-barang-penjualan .modal-body {
		flex: 1 1 auto;
		min-height: 0;
		overflow: auto;
		padding: 0.5rem 0.65rem;
		display: flex;
		flex-direction: column;
	}
	#modal-xl.modal-pilih-barang-penjualan .modal-pilih-barang-table-wrap {
		flex: 1 1 auto;
		min-height: 0;
		overflow: auto;
	}
	#modal-xl.modal-pilih-barang-penjualan #table-pilih-barang-penjualan {
		width: 100% !important;
	}
	#modal-xl.modal-pilih-barang-penjualan .dataTables_wrapper {
		width: 100%;
		height: 100%;
		display: flex;
		flex-direction: column;
	}
	#modal-xl.modal-pilih-barang-penjualan .dataTables_wrapper .row:first-child {
		flex: 0 0 auto;
	}
	#modal-xl.modal-pilih-barang-penjualan .dataTables_filter {
		text-align: right;
		width: 100%;
	}
	#modal-xl.modal-pilih-barang-penjualan .dataTables_filter label {
		font-weight: 600;
		margin-bottom: 0;
	}
	#modal-xl.modal-pilih-barang-penjualan .dataTables_filter input {
		display: inline-block;
		width: min(320px, 55vw);
		margin-left: 0.35rem;
	}
	#modal-xl.modal-pilih-barang-penjualan .dataTables_length select {
		min-width: 4.5rem;
	}
	#modal-xl.modal-pilih-barang-penjualan div.dataTables_scrollBody {
		max-height: none !important;
		overflow: auto !important;
	}
	#container-modal-pilih-barang-nested .modal,
	.modal.modal-isi-jumlah-barang-host,
	body > .modal[id^="modal-xl_1_"] {
		z-index: 1065;
	}
	#container-modal-pilih-barang-nested .modal-dialog.modal-isi-jumlah-barang,
	body > .modal[id^="modal-xl_1_"] .modal-dialog.modal-isi-jumlah-barang {
		max-width: min(720px, 96vw);
	}
	#container-modal-pilih-barang-nested .penjualan-label-info-jumlah,
	body > .modal[id^="modal-xl_1_"] .penjualan-label-info-jumlah {
		display: block;
		width: 100%;
		min-width: 0;
		margin-bottom: 0.4rem;
		padding: 0;
		color: #dc3545 !important;
		font-size: 0.95rem;
		font-weight: 600;
		line-height: 1.35;
		white-space: nowrap;
		overflow-x: auto;
		overflow-y: hidden;
	}
</style>
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
                <div class="card card-warning">
                    <div class="card-header">
                        <div class="row">
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="row">
                                    <div class="col-12" text-align="center"> <strong>
                                            Input Penjualan
                                        </strong></div>

                                </div>


                            </div>
                            <div class="col-6">

                            </div>



                        </div>



                    </div>
                    <br />

                    <?php
                    $flash_penjualan = $this->session->flashdata('message');
                    if (!empty($flash_penjualan)) {
                        echo '<div class="alert alert-info alert-dismissible fade show mx-3" role="alert">'
                            . htmlspecialchars($flash_penjualan, ENT_QUOTES, 'UTF-8')
                            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'
                            . '</div>';
                    }
                    ?>

                    <div class="card-body">


                        <!-- <form action="<?php //echo $action; 
                                            ?>" method="post"> -->



                        <form action="<?php echo htmlspecialchars($action_ubah_detail_nomor_kirim, ENT_QUOTES, 'UTF-8'); ?>" id="form_update_nmrkirim" method="post">

                            <div class="form-group">
                                <label for="datetime">Tgl Jual <?php echo form_error('tgl_jual') ?></label>
                                <div class="col-4">
                                    <?php
                                    $tgl_jual_X = penjualan_format_tgl_jual_tampil($tgl_jual);
                                    ?>
                                    <div class="input-group date" id="dt_tgl_jual_penjualan" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#dt_tgl_jual_penjualan" id="input_tgl_jual_penjualan" name="tgl_jual" value="<?php echo htmlspecialchars($tgl_jual_X, ENT_QUOTES, 'UTF-8'); ?>" required autocomplete="off" />
                                        <div class="input-group-append" data-target="#dt_tgl_jual_penjualan" data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                                <small class="text-muted d-block mt-1" id="info-bulan-persediaan-penjualan">
                                    Stok mengikuti bulan persediaan yang dipilih, dikurangi penjualan pada bulan-bulan sesudahnya sampai hari ini; jasa tidak ditampilkan.
                                </small>
                                <?php if ((int) $jumlah_barang_penjualan > 0) { ?>
                                <small class="text-danger d-block mt-1" id="info-tgl-jual-terkunci">
                                    Tgl Jual tidak boleh diubah ke bulan lain karena sudah ada data barang penjualan pada bulan ini. Hapus semua barang terlebih dahulu jika ingin memindahkan transaksi ke bulan persediaan lain.
                                </small>
                                <?php } ?>

                            </div>

                            <div class="form-group">
                                <div class="row">

                                    <!-- Unit -->
                                    <div class="col-3">
                                        <label for="unit_nama">Unit <?php echo form_error('unit') ?></label>
                                        <select name="uuid_unit" id="uuid_unit" class="form-control select2" style="width: 100%; height: 40px;" required>
                                            <option value="">Pilih Unit</option>
                                            <?php
                                            $sql = "select * from sys_unit order by nama_unit ASC ";
                                            foreach ($this->db->query($sql)->result() as $m) {
                                                $sel_unit = (isset($uuid_unit) && (string) $uuid_unit === (string) $m->uuid_unit) ? ' selected="selected"' : '';
                                                echo "<option value='" . htmlspecialchars($m->uuid_unit, ENT_QUOTES, 'UTF-8') . "'{$sel_unit}>" . strtoupper($m->nama_unit) . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <!-- Konsumen -->
                                    <div class="col-3">
                                        <label for="konsumen_nama">Konsumen <?php echo form_error('konsumen_nama') ?></label>
                                        <select name="uuid_konsumen" id="uuid_konsumen" class="form-control select2" style="width: 100%; height: 40px;" required>
                                            <option value="">Pilih Konsumen</option>
                                            <?php
                                            $sql = "select * from sys_unit order by nama_unit ASC ";
                                            foreach ($this->db->query($sql)->result() as $m) {
                                                $sel_konsumen = (isset($uuid_konsumen) && (string) $uuid_konsumen === (string) $m->uuid_unit) ? ' selected="selected"' : '';
                                                echo "<option value='" . htmlspecialchars($m->uuid_unit, ENT_QUOTES, 'UTF-8') . "'{$sel_konsumen}>" . strtoupper($m->nama_unit) . "  ==> [UNIT]</option>";
                                            }
                                            $sql = "select * from sys_konsumen order by nama_konsumen ASC ";
                                            foreach ($this->db->query($sql)->result() as $m) {
                                                $sel_konsumen = (isset($uuid_konsumen) && (string) $uuid_konsumen === (string) $m->uuid_konsumen) ? ' selected="selected"' : '';
                                                echo "<option value='" . htmlspecialchars($m->uuid_konsumen, ENT_QUOTES, 'UTF-8') . "'{$sel_konsumen}>" . strtoupper($m->nama_konsumen) . strtoupper($m->nmr_kontak_konsumen) . strtoupper($m->alamat_konsumen) . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="col-3">
                                        <label for="nmrpesan">Nomor Pesan <?php echo form_error('nmrpesan') ?></label>
                                        <input type="text" class="form-control" rows="3" name="nmrpesan" id="nmrpesan" value="<?php echo $nmrpesan ?>" placeholder="nmrpesan">
                                    </div>

                                    <div class="col-3">
                                        <label for="nmrkirim">Nomor Kirim <?php echo form_error('nmrkirim') ?></label>
                                        <input type="text" class="form-control" rows="3" name="nmrkirim" id="nmrkirim" value="<?php echo $nmrkirim ?>" placeholder="nmrkirim">
                                    </div>


                                </div>

                            </div>


                            <div class="form-group">
                                <div class="row">
                                    <div class="col-4">
                                    </div>
                                    <div class="col-4">


                                        <!-- <input type="text" name="id" value="<?php //echo $id; 
                                                                                    ?>" /> -->
                                        <input type="hidden" name="uuid_penjualan_proses" id="uuid_penjualan_proses" value="<?php echo $uuid_penjualan; ?>" />
                                        <input type="hidden" name="nmrkirim_proses" id="nmrkirim_proses" value="<?php echo $nmrkirim; ?>" />

                                        <button type="button" id="btn-simpan-detail-nmrkirim" class="btn btn-primary"><?php echo $button_detail_nomor_kirim; ?></button>
                                    </div>
                                    <div class="col-4">
                                    </div>
                                </div>
                            </div>



                        </form>

                        <form id="form-reload-penjualan-inisiasi" method="post" action="<?php echo site_url('tbl_penjualan/create_action_inisiasi/new'); ?>" class="d-none" aria-hidden="true">
                            <input type="hidden" name="tgl_jual" id="reload_penjualan_tgl_jual" value="<?php echo htmlspecialchars($tgl_jual_X_modal, ENT_QUOTES, 'UTF-8'); ?>">
                            <input type="hidden" name="uuid_unit" id="reload_penjualan_uuid_unit" value="<?php echo htmlspecialchars(isset($uuid_unit) ? $uuid_unit : '', ENT_QUOTES, 'UTF-8'); ?>">
                            <input type="hidden" name="uuid_konsumen" id="reload_penjualan_uuid_konsumen" value="<?php echo htmlspecialchars(isset($uuid_konsumen) ? $uuid_konsumen : '', ENT_QUOTES, 'UTF-8'); ?>">
                            <input type="hidden" name="nmrpesan" id="reload_penjualan_nmrpesan" value="<?php echo htmlspecialchars(isset($nmrpesan) ? $nmrpesan : '', ENT_QUOTES, 'UTF-8'); ?>">
                            <input type="hidden" name="nmrkirim" id="reload_penjualan_nmrkirim" value="<?php echo htmlspecialchars(isset($nmrkirim) ? $nmrkirim : '', ENT_QUOTES, 'UTF-8'); ?>">
                        </form>

                        <br />

                        <div class="card card-success">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-12" text-align="center"> <strong>Detail Barang</strong> <button type="button" class="btn btn-warning btn-lg" id="btn-input-detail-barang-penjualan">
                                            Input Detail Barang
                                        </button></div>

                                </div>
                            </div>
                            <div class="card-body">

                                <?php

                                if (isset($data_penjualan_per_uuid_penjualan)) {
                                ?>

                                    <table id="example1" class="display nowrap" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th style="text-align:center">No</th>
                                                <th style="text-align:center">Update</th>
                                                <th style="text-align:left">Tgl Persediaan</th>
                                                <th style="text-align:left">Tgl Jual</th>
                                                <th style="text-align:center">Nama Barang</th>
                                                <th style="text-align:left">SPOP</th>
                                                <!-- <th style="text-align:center">Unit</th> -->

                                                <th style="text-align:center">Satuan</th>
                                                <th style="text-align:right">Jumlah</th>
                                                <th style="text-align:right">Harga Satuan</th>
                                                <th style="text-align:right">Total</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php

                                            $start = 0;
                                            $get_jumlah_barang = 0;
                                            $get_total_harga = 0;
                                            foreach ($data_penjualan_per_uuid_penjualan as $list_data) {

                                            ?>
                                                <tr>
                                                    <td><?php echo ++$start; ?></td>

                                                    <!-- Ubah dan hapus -->
                                                    <td>
                                                        <!-- <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-xl-update_<?php //echo $list_data->id; 
                                                                                                                                                                    ?>">
                                                            Ubah Barang <?php //echo $list_data->id; 
                                                                        ?>
                                                        </button> -->
                                                        <form action="<?php echo $action_hapus_group_penjualan; ?>" method="post" class="d-inline" onsubmit="return confirm('Anda yakin akan menghapus seluruh jumlah pada kelompok barang ini?');">
                                                            <input type="hidden" name="uuid_penjualan" value="<?php echo htmlspecialchars($uuid_penjualan, ENT_QUOTES, 'UTF-8'); ?>">
                                                            <?php foreach ($list_data->group_ids as $group_id) { ?>
                                                                <input type="hidden" name="group_ids[]" value="<?php echo (int) $group_id; ?>">
                                                            <?php } ?>
                                                            <button type="submit" class="btn btn-danger btn-xs">HAPUS</button>
                                                        </form>

                                                        <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#modal-xl-input-barang_<?php echo $list_data->id ?>">
                                                            UBAH <?php //echo $list_data->id 
                                                                    ?>
                                                        </button>

                                                        <!-- <button type="button"  class="btn btn-outline-info btn-block btn-flat"> <i class="fa fa-book"></i> <?php // $button; 
                                                                                                                                                                ?></button> -->
                                                    </td>


                                                    <td data-order="<?php echo !empty($list_data->tgl_persediaan) ? htmlspecialchars($list_data->tgl_persediaan, ENT_QUOTES, 'UTF-8') : ''; ?>">
                                                        <?php
                                                        if (!empty($list_data->tgl_persediaan)) {
                                                            $ts_tgl_persediaan = strtotime($list_data->tgl_persediaan);
                                                            echo $ts_tgl_persediaan !== false ? date('d M Y', $ts_tgl_persediaan) : htmlspecialchars($list_data->tgl_persediaan, ENT_QUOTES, 'UTF-8');
                                                        } else {
                                                            echo '-';
                                                        }
                                                        ?>
                                                    </td>

                                                    <td>
                                                        <?php
                                                        // echo $list_data->tgl_po; 

                                                        echo date("d M Y", strtotime($list_data->tgl_jual));

                                                        ?>
                                                    </td>



                                                    <td><?php echo $list_data->nama_barang; ?></td>
                                                    <td style="text-align:left"><?php echo htmlspecialchars(isset($list_data->spop) ? trim((string) $list_data->spop) : '', ENT_QUOTES, 'UTF-8') ?: '-'; ?></td>
                                                    <!-- <td><?php //echo $list_data->unit; 
                                                                ?></td> -->

                                                    <td style="text-align:center"><?php echo $list_data->satuan; ?></td>
                                                    <td style="text-align:right">
                                                        <?php
                                                        echo nominal($list_data->jumlah);
                                                        $get_jumlah_barang = $get_jumlah_barang + $list_data->jumlah;
                                                        ?>
                                                    </td>
                                                    <td style="text-align:right">
                                                        <?php
                                                        // echo nominal($list_data->harga_satuan); 
                                                        // echo "<br/>";
                                                        echo number_format($list_data->harga_satuan, 2, ',', '.');
                                                        ?>
                                                    </td>
                                                    <td style="text-align:right">
                                                        <?php
                                                        // echo nominal($list_data->jumlah * $list_data->harga_satuan);
                                                        echo number_format($list_data->jumlah * $list_data->harga_satuan, 2, ',', '.');
                                                        $get_total_harga = $get_total_harga + ($list_data->jumlah * $list_data->harga_satuan);
                                                        ?>
                                                    </td>









                                                </tr>



                                            <?php
                                            }
                                            ?>


                                        </tbody>


                                        <tfoot>
                                            <tr>
                                                <th style="text-align:center"></th>
                                                <th style="text-align:left"></th>
                                                <th style="text-align:center"></th>
                                                <th style="text-align:center"></th>
                                                <th style="text-align:center"></th>
                                                <th style="text-align:center"></th>
                                                <th style="text-align:center"></th>

                                                <th style="text-align:right"><?php echo nominal($get_jumlah_barang);  ?></th>
                                                <th style="text-align:right"></th>
                                                <th style="text-align:right">
                                                    <?php
                                                    // echo nominal($get_total_harga); 
                                                    echo number_format($get_total_harga, 2, ',', '.');
                                                    ?>
                                                </th>

                                            </tr>
                                        </tfoot>



                                    </table>


                                <?php
                                }
                                ?>



                            </div>
                        </div>







                        <!-- <input type="hidden" name="id" value="<?php //echo $id; 
                                                                    ?>" /> -->



                        <!-- <button type="submit" class="btn btn-primary"><?php //echo $button 
                                                                            ?></button> -->
                        <button type="button" class="btn btn-default" id="btn-kembali-halaman-penjualan">Kembali ke Halaman Data Penjualan</button>
                        <?php
                        if (isset($uuid_penjualan)) {
                        ?>

                            <a href="<?php echo site_url('tbl_penjualan/cetak_penjualan_per_uuid_penjualan/' . $uuid_penjualan) ?>" class="btn btn-primary" target="_blank">Cetak Penjualan</a>

                        <?php
                        }
                        ?>

                        <!-- <a href="<?php //echo site_url('tbl_penjualan') 
                                        ?>" class="btn btn-default">Cancel</a> -->
                        <!-- </form> -->


                    </div>

                    <!-- /.card-body -->
                </div>

            </div>

        </div>
    </section>
</div>




<!-- /.modal -->

<div class="modal fade modal-pilih-barang-penjualan" id="modal-xl" tabindex="-1">
    <div class="modal-dialog modal-pilih-barang-wide">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Pilih Barang <small class="text-muted" id="modal-pilih-barang-bulan-label">(Saldo bulan terpilih setelah dikurangi penjualan bulan berikutnya)</small></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-pilih-barang-table-wrap card-body p-0">
                    <div id="modal-pilih-barang-loading" class="penjualan-stock-loading d-none" role="status" aria-live="polite">
                        <span class="penjualan-stock-loading-spinner"></span>
                        <span>Memuat data persediaan...</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-2 px-2 pt-2">
                        <div class="d-flex align-items-center flex-wrap">
                            <label for="filter-bulan-persediaan-penjualan" class="mb-0 mr-2">Bulan persediaan:</label>
                            <input type="month" id="filter-bulan-persediaan-penjualan" class="form-control form-control-sm mr-3" style="width:180px">
                            <small class="text-muted" id="modal-pilih-barang-cache-status">Pilih bulan untuk melihat saldo stok yang masih tersedia.</small>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="btn-refresh-pilih-barang">
                            <i class="fas fa-sync-alt"></i> Refresh data terbaru
                        </button>
                    </div>

                    <table id="table-pilih-barang-penjualan" class="display nowrap table table-bordered table-sm" style="width:100%">
                        <!-- <table id="example" class="display nowrap" style="width:100%"> -->
                        <thead>
                            <tr>
                                <th style="text-align:center">No</th>
                                <th style="text-align:center">Pilih</th>
                                <th style="text-align:center">Tgl PO</th>
                                <th style="text-align:center">SPOP</th>
                                <th style="text-align:center">Kategori</th>
                                <th style="text-align:center">Nama barang</th>
                                <th style="text-align:right">Harga satuan</th>
                                <th style="text-align:right">satuan</th>
                                <th style="text-align:center">Sisa Stock</th>
                                <th style="text-align:left">Pilih</th>

                            </tr>
                        </thead>
                        <tbody id="tbody-pilih-barang-penjualan">
                        </tbody>



                    </table>
                </div>
                <div id="container-modal-pilih-barang-nested"></div>

            </div>

        </div>
    </div>
</div>

<!-- /.modal -->

<!-- ============== -->




<?php

if (isset($data_penjualan_per_uuid_penjualan) && is_array($data_penjualan_per_uuid_penjualan)) {
foreach ($data_penjualan_per_uuid_penjualan as $list_data) {
?>
    <!-- MODAL EXTRA LARGE UPDATE PER ID -->
    <form action="<?php echo $action_ubah_group_penjualan; ?>" method="post" class="penjualan-form-ubah-barang">
        <input type="hidden" name="konfirmasi_ubah_harga" value="0">
        <input type="hidden" name="uuid_penjualan" value="<?php echo htmlspecialchars($uuid_penjualan, ENT_QUOTES, 'UTF-8'); ?>">
        <?php foreach ($list_data->group_ids as $group_id) { ?>
            <input type="hidden" name="group_ids[]" value="<?php echo (int) $group_id; ?>">
        <?php } ?>
        <div class="modal fade" id="modal-xl-input-barang_<?php echo $list_data->id ?>">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Update Kelompok Barang</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <?php
                    $row_data_barang_jual = $list_data;
                    $id_persediaan_barang = (int) $list_data->id_persediaan_barang;
                    $penjualan_kolom_unit_modal = penjualan_resolve_kolom_persediaan_unit($this, isset($uuid_unit) ? $uuid_unit : '');
                    $row_persediaan = null;

                    if ($id_persediaan_barang > 0 && !empty($Data_stock)) {
                        foreach ($Data_stock as $stock_row) {
                            if ((int) $stock_row->id === $id_persediaan_barang) {
                                $row_persediaan = $stock_row;
                                break;
                            }
                        }
                    }
                    if ($row_persediaan === null && $id_persediaan_barang > 0) {
                        $row_persediaan = $this->Persediaan_model->get_by_id($id_persediaan_barang);
                    }
                    if ($row_persediaan === null && !empty($list_data->uuid_persediaan)) {
                        $row_persediaan = $this->Persediaan_model->get_by_uuid_persediaan($list_data->uuid_persediaan);
                    }

                    $jumlah_jual_saat_ini = (int) $row_data_barang_jual->jumlah;
                    if ($row_persediaan !== null) {
                        $Get_stock_di_persediaan = penjualan_get_sisa_stock_penjualan($row_persediaan, $penjualan_kolom_unit_modal) + $jumlah_jual_saat_ini;
                    } else {
                        $Get_stock_di_persediaan = max($jumlah_jual_saat_ini, 1);
                    }
                    if ($Get_stock_di_persediaan < 1) {
                        $Get_stock_di_persediaan = max($jumlah_jual_saat_ini, 1);
                    }
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
                                    <?php
                                    $harga_awal_angka_kasir = penjualan_parse_harga_satuan_input($row_data_barang_jual->harga_satuan);
                                    $harga_awal_tampil_kasir = number_format($row_data_barang_jual->harga_satuan, 2, ',', '.');
                                    ?>
                                    <input type="text" class="form-control input-harga-satuan-ubah" name="harga_satuan" value="<?php echo $harga_awal_tampil_kasir; ?>" data-harga-awal="<?php echo htmlspecialchars((string) $harga_awal_angka_kasir, ENT_QUOTES, 'UTF-8'); ?>" data-harga-awal-tampil="<?php echo htmlspecialchars($harga_awal_tampil_kasir, ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo $harga_awal_tampil_kasir; ?>">
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
}
?>

<?php $this->load->view('anekadharma/tbl_penjualan/_penjualan_form_ubah_barang_js'); ?>





<style type="text/css">
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }

    .modal-pilih-barang-table-wrap {
        position: relative;
        min-height: 230px;
    }

    .penjualan-stock-loading {
        position: absolute;
        inset: 0;
        z-index: 20;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 14px;
        color: #344054;
        font-weight: 600;
        background: rgba(255, 255, 255, .94);
        backdrop-filter: blur(3px);
    }

    .penjualan-stock-loading.d-none {
        display: none !important;
    }

    .penjualan-stock-loading-spinner {
        width: 52px;
        height: 52px;
        border: 4px solid #dbeafe;
        border-top-color: #2563eb;
        border-right-color: #7c3aed;
        border-radius: 50%;
        animation: penjualan-stock-spin .8s linear infinite;
        box-shadow: 0 0 22px rgba(37, 99, 235, .2);
    }

    @keyframes penjualan-stock-spin {
        to { transform: rotate(360deg); }
    }

    #table-pilih-barang-penjualan_processing {
        display: none !important;
    }
</style>

<script>
/* Inisialisasi setelah jQuery layout (AdminLTE) dimuat */
window.penjualanDtPilihBarang = null;
window.penjualanTablePilihBarangId = '#table-pilih-barang-penjualan';
window.penjualanPilihBarangCachePrefix = 'penjualan-pilih-barang-cache-v4:';
window.penjualanStockCacheById = {};
window.penjualanBulanPersediaanAktif = '';
window.penjualanPilihBarangConfig = {
    url: <?php echo json_encode(site_url('tbl_penjualan/list_persediaan_penjualan_ajax')); ?>,
    urlSimpanBarang: <?php echo json_encode(site_url('tbl_penjualan/create_action_simpan_barang/')); ?>,
    uuidPenjualan: <?php echo json_encode($uuid_penjualan); ?>
};

window.clearPenjualanPilihBarangCache = function() {
    try {
        var storage = window.localStorage;
        var keys = [];
        for (var i = 0; i < storage.length; i++) {
            var key = storage.key(i);
            if (key && key.indexOf(window.penjualanPilihBarangCachePrefix) === 0) {
                keys.push(key);
            }
        }
        for (var keyIndex = 0; keyIndex < keys.length; keyIndex++) {
            storage.removeItem(keys[keyIndex]);
        }
        return true;
    } catch (error) {
        console.warn('Cache persediaan tidak dapat dibersihkan:', error);
        return false;
    }
};

window.updatePenjualanPilihBarangRangeLabel = function(payload) {
    var month = payload && payload.bulanPersediaan ? payload.bulanPersediaan : '';
    var count = payload && payload.recordsTotal ? payload.recordsTotal : 0;
    var monthLabel = month;
    var monthMatch = String(month).match(/^(\d{4})-(\d{2})$/);
    if (monthMatch) {
        monthLabel = monthMatch[2] + '/' + monthMatch[1];
    }
    $('#modal-pilih-barang-bulan-label').text(
        '(Stok ' + monthLabel + ' setelah penjualan bulan berikutnya, ' + count + ' barang)'
    );
};

window.penjualanBulanDariTanggalJual = function(tanggalJual) {
    var match = String(tanggalJual || '').match(/^(\d{1,2})[-\/\.](\d{1,2})[-\/\.](\d{4})$/);
    if (!match) {
        return '';
    }
    return match[3] + '-' + ('0' + parseInt(match[2], 10)).slice(-2);
};

window.penjualanHitungTanggalAkhirPersediaan = function(tanggalJual, bulanPersediaan) {
    var saleDateMatch = String(tanggalJual || '').match(/^(\d{1,2})[-\/\.](\d{1,2})[-\/\.](\d{4})$/);
    var monthMatch = String(bulanPersediaan || '').match(/^(\d{4})-(0[1-9]|1[0-2])$/);
    if (!saleDateMatch || !monthMatch) {
        return '';
    }
    var saleDate = new Date(
        parseInt(saleDateMatch[3], 10),
        parseInt(saleDateMatch[2], 10) - 1,
        parseInt(saleDateMatch[1], 10)
    );
    if (isNaN(saleDate.getTime())
        || saleDate.getFullYear() !== parseInt(saleDateMatch[3], 10)
        || saleDate.getMonth() + 1 !== parseInt(saleDateMatch[2], 10)
        || saleDate.getDate() !== parseInt(saleDateMatch[1], 10)) {
        return '';
    }
    var monthEnd = new Date(parseInt(monthMatch[1], 10), parseInt(monthMatch[2], 10), 0);
    var effectiveEnd = saleDate < monthEnd ? saleDate : monthEnd;
    return effectiveEnd.getFullYear() + '-' +
        ('0' + (effectiveEnd.getMonth() + 1)).slice(-2) + '-' +
        ('0' + effectiveEnd.getDate()).slice(-2);
};

window.penjualanEscapeHtml = function(value) {
    return String(value == null ? '' : value).replace(/[&<>"']/g, function(character) {
        return {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        }[character];
    });
};

window.openPenjualanPilihBarangModal = function(id) {
    var $ = window.jQuery;
    var item = window.penjualanStockCacheById[String(id)];
    if (!item || !item.sisa || item.sisa < 1) {
        penjualanAlertPesan('Stok tidak tersedia', 'Perbarui daftar persediaan sebelum memilih barang ini.', 'warning');
        return;
    }

    var modalId = 'modal-xl_1_' + parseInt(item.id, 10);
    var uuid = window.penjualanPilihBarangConfig.uuidPenjualan || 'new';
    var action = window.penjualanPilihBarangConfig.urlSimpanBarang + uuid + '/' + parseInt(item.id, 10);
    var nama = window.penjualanEscapeHtml(item.namabarang);
    var satuan = window.penjualanEscapeHtml(item.satuan);
    var harga = parseFloat(item.hpp) || 0;
    var hargaTampil = harga.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    var stok = Math.floor(parseFloat(item.sisa) || 0);
    var labelJumlah = 'Jumlah Maks= ' + stok;
    var labelUnit = '';
    if (window.penjualanPilihBarangConfig.labelUnit) {
        labelUnit = window.penjualanEscapeHtml(window.penjualanPilihBarangConfig.labelUnit);
        labelJumlah += ' | Unit ' + labelUnit + ': ' +
            Math.floor(parseFloat(item.nilai_unit || 0));
    }
    $('#' + modalId).remove();
    var modalHtml = '<div class="modal fade" id="' + modalId + '" tabindex="-1">' +
        '<div class="modal-dialog modal-lg modal-isi-jumlah-barang"><div class="modal-content">' +
        '<form class="form-simpan-jumlah-barang-penjualan" action="' + action + '" method="post">' +
        '<div class="modal-header"><h4 class="modal-title">Isi Jumlah Barang</h4>' +
        '<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>' +
        '<div class="modal-body"><div class="form-group"><label>Barang</label>' +
        '<input type="text" class="form-control" value="' + nama + '" disabled></div>' +
        '<div class="row"><div class="col-md-5 col-12"><div class="form-group mb-md-0">' +
        '<label>Harga Satuan</label><input type="text" class="form-control" name="harga_satuan_beli" value="' + hargaTampil + '">' +
        '</div></div><div class="col-md-7 col-12"><div class="form-group mb-0">' +
        '<label class="penjualan-label-info-jumlah d-block" for="jumlah_barang_' + item.id + '">' +
        window.penjualanEscapeHtml(labelJumlah) + '</label>' +
        '<input type="number" class="form-control" id="jumlah_barang_' + item.id + '" name="jumlah" min="1" max="' + stok + '" placeholder="Isi jumlah barang" required>' +
        '</div></div></div></div><div class="modal-footer justify-content-between">' +
        '<input type="hidden" name="ajax" value="1">' +
        '<input type="hidden" name="tgl_jual" value="' + window.penjualanEscapeHtml($('#input_tgl_jual_penjualan').val()) + '">' +
        '<input type="hidden" name="bulan_persediaan" value="' + window.penjualanEscapeHtml($('#filter-bulan-persediaan-penjualan').val()) + '">' +
        '<input type="hidden" name="uuid_unit" value="' + window.penjualanEscapeHtml($('#uuid_unit').val()) + '">' +
        '<input type="hidden" name="uuid_konsumen" value="' + window.penjualanEscapeHtml($('#uuid_konsumen').val()) + '">' +
        '<input type="hidden" name="uuid_persediaan" value="' + window.penjualanEscapeHtml(item.uuid_persediaan) + '">' +
        '<input type="hidden" name="id_persediaan_barang" value="' + parseInt(item.id, 10) + '">' +
        '<input type="hidden" name="uuid_penjualan" value="' + window.penjualanEscapeHtml(uuid) + '">' +
        '<input type="hidden" name="uuid_penjualan_proses" value="' + window.penjualanEscapeHtml(uuid) + '">' +
        '<input type="hidden" name="nmrpesan" value="' + window.penjualanEscapeHtml($('#nmrpesan').val()) + '">' +
        '<input type="hidden" name="nmrkirim" value="' + window.penjualanEscapeHtml($('#nmrkirim').val()) + '">' +
        '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
        '<button type="submit" class="btn btn-primary btn-simpan-jumlah-barang">SIMPAN</button>' +
        '</div></form></div></div></div>';
    $('body').append(modalHtml);
    $('#' + modalId).modal('show');
};

window.destroyDataTablePilihBarang = function() {
    var $ = window.jQuery;
    if (!$ || !$.fn.DataTable) {
        return;
    }
    var $table = $(window.penjualanTablePilihBarangId);
    if ($table.length && $.fn.DataTable.isDataTable($table)) {
        try {
            $table.DataTable().clear().destroy();
        } catch (e1) {
            try {
                $table.DataTable().destroy();
            } catch (e2) {}
        }
    }
    window.penjualanDtPilihBarang = null;
};

window.hitungScrollYPilihBarangPenjualan = function() {
    var $modal = $('#modal-xl.modal-pilih-barang-penjualan');
    if (!$modal.length) {
        return Math.max(360, Math.floor(window.innerHeight * 0.62));
    }
    var tinggiModal = $modal.find('.modal-dialog').innerHeight() || (window.innerHeight - Math.round(2 * 37.8));
    var headerH = $modal.find('.modal-header').outerHeight(true) || 52;
    var toolH = 72;
    var footDt = 56;
    return Math.max(340, Math.floor(tinggiModal - headerH - toolH - footDt - 18));
};

window.initDataTablePilihBarang = function(onReady, onFinish) {
    var $ = window.jQuery;
    if (!$ || !$.fn.DataTable) {
        $('#modal-pilih-barang-loading').addClass('d-none');
        penjualanAlertPesan('Gagal memuat data', 'Library DataTables belum siap.', 'error');
        if (typeof onFinish === 'function') {
            onFinish();
        }
        return;
    }
    window.destroyDataTablePilihBarang();
    var $table = $(window.penjualanTablePilihBarangId);
    if (!$table.length) {
        $('#modal-pilih-barang-loading').addClass('d-none');
        penjualanAlertPesan('Gagal memuat data', 'Tabel pilih barang tidak ditemukan.', 'error');
        if (typeof onFinish === 'function') {
            onFinish();
        }
        return;
    }
    try {
        if (!$table.data('penjualan-loading-bound')) {
            $table.on('processing.dt', function(event, settings, processing) {
                $('#modal-pilih-barang-loading').toggleClass('d-none', !processing);
            });
            $table.data('penjualan-loading-bound', true);
        }
        window.penjualanDtPilihBarang = $table.DataTable({
            scrollY: window.hitungScrollYPilihBarangPenjualan(),
            scrollX: true,
            scrollCollapse: true,
            destroy: true,
            processing: true,
            serverSide: false,
            paging: true,
            searching: true,
            lengthChange: true,
            info: true,
            autoWidth: false,
            order: [[5, 'asc'], [2, 'asc']],
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            dom: '<"row align-items-center mb-2"<"col-sm-6"l><"col-sm-6"f>>rt<"row mt-2"<"col-sm-5"i><"col-sm-7"p>>',
            ajax: function(data, callback) {
                data.tgl_jual = $('#input_tgl_jual_penjualan').val() || '';
                data.bulan_persediaan = $('#filter-bulan-persediaan-penjualan').val() || '';
                data.tanggal_akhir_persediaan = window.penjualanHitungTanggalAkhirPersediaan(
                    data.tgl_jual,
                    data.bulan_persediaan
                );
                if (data.bulan_persediaan < '2026-01') {
                    var oldDateMessage = 'Tidak ada persediaan sebelum 1 Januari 2026. Silakan pilih bulan mulai Januari 2026.';
                    callback({
                        draw: data.draw,
                        recordsTotal: 0,
                        recordsFiltered: 0,
                        data: []
                    });
                    $('#modal-pilih-barang-cache-status').text(oldDateMessage);
                    $('#modal-pilih-barang-loading').addClass('d-none');
                    penjualanAlertPesan('Bulan persediaan tidak tersedia', oldDateMessage, 'warning');
                    return;
                }
                if (!data.tanggal_akhir_persediaan) {
                    var invalidDateMessage = 'Bulan persediaan atau tanggal input penjualan tidak valid.';
                    callback({
                        draw: data.draw,
                        recordsTotal: 0,
                        recordsFiltered: 0,
                        data: []
                    });
                    $('#modal-pilih-barang-cache-status').text(invalidDateMessage);
                    $('#modal-pilih-barang-loading').addClass('d-none');
                    penjualanAlertPesan('Tanggal tidak valid', invalidDateMessage, 'warning');
                    return;
                }

                window.penjualanForceRefreshPilihBarang = false;
                $('#modal-pilih-barang-cache-status').text('Mengambil data terbaru langsung dari tabel persediaan...');
                data.all_records = 1;
                data.search = { value: '', regex: false };
                data.start = 0;
                data.length = -1;
                data.uuid_penjualan = window.penjualanPilihBarangConfig.uuidPenjualan;
                data.uuid_unit = $('#uuid_unit').val() || '';
                data.uuid_konsumen = $('#uuid_konsumen').val() || '';
                data.nmrpesan = $('#nmrpesan').val() || '';
                data.nmrkirim = $('#nmrkirim').val() || '';
                $.ajax({
                    url: window.penjualanPilihBarangConfig.url,
                    type: 'POST',
                    dataType: 'json',
                    data: data
                }).done(function(json) {
                    if (!json || json.error) {
                        $('#modal-pilih-barang-loading').addClass('d-none');
                        window.penjualanForceRefreshPilihBarangDone = false;
                        var errorPayload = json || {};
                        errorPayload.draw = data.draw;
                        errorPayload.recordsTotal = errorPayload.recordsTotal || 0;
                        errorPayload.recordsFiltered = errorPayload.recordsFiltered || 0;
                        errorPayload.data = errorPayload.data || [];
                        callback(errorPayload);
                        var oldDateNotice = errorPayload.error &&
                            errorPayload.error.indexOf('Tidak ada persediaan sebelum 1 Januari 2026') === 0;
                        penjualanAlertPesan(
                            oldDateNotice ? 'Bulan persediaan tidak tersedia' : 'Gagal memuat data',
                            errorPayload.error || 'Tidak dapat memuat daftar persediaan.',
                            oldDateNotice ? 'warning' : 'error'
                        );
                        return;
                    }
                    window.penjualanStockCacheById = json.stockData || {};
                    $('#container-modal-pilih-barang-nested').empty();
                    $('#modal-pilih-barang-cache-status').text(
                        'Menampilkan saldo bulan terpilih setelah dikurangi penjualan pada bulan-bulan sesudahnya sampai hari ini.'
                    );
                    window.updatePenjualanPilihBarangRangeLabel(json);
                    json.draw = data.draw;
                    callback(json);
                    if (window.penjualanForceRefreshPilihBarangDone) {
                        window.penjualanForceRefreshPilihBarangDone = false;
                        penjualanAlertPesan('Data diperbarui', 'Daftar persediaan terbaru sudah dimuat.', 'success');
                    }
                }).fail(function(xhr) {
                    $('#modal-pilih-barang-loading').addClass('d-none');
                    var message = 'Tidak dapat memuat daftar persediaan.';
                    if (xhr && xhr.responseJSON && xhr.responseJSON.error) {
                        message = xhr.responseJSON.error;
                    }
                    window.penjualanForceRefreshPilihBarangDone = false;
                    var oldDateNotice = message.indexOf('Tidak ada persediaan sebelum 1 Januari 2026') === 0;
                    penjualanAlertPesan(
                        oldDateNotice ? 'Bulan persediaan tidak tersedia' : 'Gagal memuat data',
                        message,
                        oldDateNotice ? 'warning' : 'error'
                    );
                    $('#modal-pilih-barang-cache-status').text('Gagal memuat data terbaru dari server.');
                    callback({
                        draw: data.draw,
                        recordsTotal: 0,
                        recordsFiltered: 0,
                        data: [],
                        error: message
                    });
                    if (typeof onFinish === 'function') {
                        onFinish();
                    }
                }
                );
            },
            columns: [
                { data: 0, orderable: false, searchable: false, className: 'text-center' },
                { data: 1, orderable: false, searchable: false, className: 'text-center' },
                {
                    data: 2,
                    render: function(data, type) {
                        if (type === 'sort' || type === 'type') {
                            var match = String(data || '').match(/data-order="([^"]*)"/);
                            return match ? match[1] : '';
                        }
                        if (type === 'filter') {
                            return $('<div>').html(data || '').text();
                        }
                        return data;
                    }
                },
                { data: 3 },
                { data: 4 },
                { data: 5 },
                { data: 6, className: 'text-right' },
                { data: 7 },
                { data: 8, className: 'text-right' },
                { data: 9, orderable: false, searchable: false, className: 'text-center' }
            ],
            language: {
                processing: 'Memuat...',
                search: 'Cari:',
                searchPlaceholder: 'Nama barang, SPOP, kategori...',
                lengthMenu: 'Tampil _MENU_ baris',
                info: 'Baris _START_–_END_ dari _TOTAL_ barang',
                infoEmpty: 'Tidak ada data',
                infoFiltered: '(filter dari _MAX_ barang)',
                zeroRecords: 'Tidak ada barang yang cocok',
                paginate: {
                    first: 'Awal',
                    last: 'Akhir',
                    next: '›',
                    previous: '‹'
                }
            },
            initComplete: function(settings, json) {
                $('#modal-pilih-barang-loading').addClass('d-none');
                if (json && json.error) {
                    penjualanAlertPesan('Gagal memuat data', json.error, 'error');
                } else {
                    window.updatePenjualanPilihBarangRangeLabel(json);
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Data siap',
                            text: 'Daftar barang berhasil dimuat dan siap dipilih.',
                            timer: 2000,
                            timerProgressBar: true,
                            showConfirmButton: true,
                            confirmButtonText: 'OK'
                        });
                    }
                    if (typeof onReady === 'function') {
                        onReady(json);
                    }
                }
                if (typeof onFinish === 'function') {
                    onFinish();
                }
            }
        });
    } catch (errDt) {
        console.error('DataTable pilih barang:', errDt);
        $('#modal-pilih-barang-loading').addClass('d-none');
        penjualanAlertPesan('Gagal memuat data', 'DataTable persediaan tidak dapat diinisialisasi.', 'error');
        if (typeof onFinish === 'function') {
            onFinish();
        }
    }
};

window.sesuaikanDataTablePilihBarang = function() {
    if (!window.penjualanDtPilihBarang) {
        window.initDataTablePilihBarang();
        return;
    }
    try {
        var y = window.hitungScrollYPilihBarangPenjualan();
        window.penjualanDtPilihBarang.columns.adjust();
        if (window.penjualanDtPilihBarang.settings()[0].oScroll) {
            $(window.penjualanDtPilihBarang.table().container())
                .find('div.dataTables_scrollBody')
                .css({ 'max-height': y + 'px', 'height': y + 'px', 'overflow': 'auto' });
        }
        window.penjualanDtPilihBarang.draw(false);
    } catch (eAdj) {
        console.warn('Sesuaikan DataTable pilih barang:', eAdj);
    }
};

function penjualanAlertPesan(judul, pesan, icon) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({ icon: icon || 'info', title: judul, text: pesan });
    } else {
        alert(judul + (pesan ? '\n' + pesan : ''));
    }
}

function penjualanInitInputBarangScript() {
    var $ = window.jQuery;
    if (!$) {
        setTimeout(penjualanInitInputBarangScript, 80);
        return;
    }

(function($) {
    var cfg = {
        urlListPersediaan: <?php echo json_encode(site_url('tbl_penjualan/list_persediaan_penjualan_ajax')); ?>,
        urlUbahDetailNomorKirim: <?php echo json_encode($action_ubah_detail_nomor_kirim); ?>,
        urlKasirPenjualan: <?php echo json_encode($uuid_penjualan !== '' ? site_url('tbl_penjualan/kasir_penjualan/' . $uuid_penjualan) : site_url('tbl_penjualan')); ?>,
        bulanKeyAwal: <?php echo json_encode($penjualan_bulan_key); ?>,
        bulanLabelAwal: <?php echo json_encode(isset($filter_bulan_penjualan['bulan_label']) ? $filter_bulan_penjualan['bulan_label'] : ''); ?>,
        listBulanKey: <?php echo json_encode(isset($penjualan_list_bulan_key) ? $penjualan_list_bulan_key : ''); ?>,
        listBulanLabel: <?php echo json_encode(isset($penjualan_list_bulan_label) ? $penjualan_list_bulan_label : ''); ?>,
        redirectListBase: <?php echo json_encode(site_url('tbl_penjualan')); ?>,
        jumlahBarang: <?php echo (int) $jumlah_barang_penjualan; ?>,
        uuidPenjualan: <?php echo json_encode($uuid_penjualan); ?>
    };

    var tglJualTimer = null;
    var tglJualBulanKey = cfg.bulanKeyAwal;
    var tglJualNilaiAktif = '';
    var sedangBlokirBulan = false;

    function getInputTglJual() {
        var $el = $('#input_tgl_jual_penjualan');
        if ($el.length) {
            return $el;
        }
        return $('#form_update_nmrkirim input[name="tgl_jual"]').first();
    }

    function getTglJualVal() {
        return $.trim(getInputTglJual().val() || '');
    }

    function parseBulanKey(tglStr) {
        var p = tglStr.split(/[-\/\.]/);
        if (p.length === 3) {
            var d = parseInt(p[0], 10), m = parseInt(p[1], 10), y = parseInt(p[2], 10);
            if (y < 100) {
                y += 2000;
            }
            if (m >= 1 && m <= 12 && d >= 1 && d <= 31) {
                return y + '-' + ('0' + m).slice(-2);
            }
        }
        return '';
    }

    function bulanLabelFromKey(bulanKey) {
        var parts = String(bulanKey || '').split('-');
        if (parts.length === 2) {
            return parts[1] + '/' + parts[0];
        }
        return bulanKey || '';
    }

    function buildRedirectListUrlDariTglJual(tglStr) {
        var bulanKey = parseBulanKey(tglStr);
        if (!bulanKey) {
            return cfg.redirectListBase;
        }
        var parts = bulanKey.split('-');
        var y = parseInt(parts[0], 10);
        var m = parseInt(parts[1], 10);
        var lastDay = new Date(y, m, 0).getDate();
        var awal = '1-' + m + '-' + y;
        var akhir = lastDay + '-' + m + '-' + y;
        return cfg.redirectListBase
            + '?tgl_awal=' + encodeURIComponent(awal)
            + '&tgl_akhir=' + encodeURIComponent(akhir);
    }

    function navigasiKembaliKeHalamanPenjualan() {
        var tgl = getTglJualVal();
        var url = buildRedirectListUrlDariTglJual(tgl);
        var bulanInput = parseBulanKey(tgl);
        var bulanList = cfg.listBulanKey || '';

        if (bulanList && bulanInput && bulanInput !== bulanList) {
            var labelList = cfg.listBulanLabel || bulanLabelFromKey(bulanList);
            var labelInput = bulanLabelFromKey(bulanInput);
            var pesan = 'Bekerja di halaman penjualan bulan <strong>' + labelList + '</strong>, '
                + 'tetapi input data penjualan pada bulan <strong>' + labelInput + '</strong>.<br><br>'
                + 'Data penjualan akan ditampilkan sesuai bulan Tgl Jual (<strong>' + labelInput + '</strong>). Lanjutkan?';
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perbedaan bulan penjualan',
                    html: pesan,
                    showCancelButton: true,
                    confirmButtonText: 'OK, tampilkan data',
                    cancelButtonText: 'Batal'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
                return;
            }
            if (!confirm('Bulan halaman penjualan (' + labelList + ') berbeda dengan Tgl Jual (' + labelInput + '). Lanjutkan?')) {
                return;
            }
        }

        window.location.href = url;
    }

    function syncReloadFormFields() {
        $('#reload_penjualan_tgl_jual').val(getTglJualVal());
        $('#reload_penjualan_uuid_unit').val($('#form_update_nmrkirim select[name="uuid_unit"]').val() || '');
        $('#reload_penjualan_uuid_konsumen').val($('#form_update_nmrkirim select[name="uuid_konsumen"]').val() || '');
        $('#reload_penjualan_nmrpesan').val($('#form_update_nmrkirim #nmrpesan').val() || '');
        $('#reload_penjualan_nmrkirim').val($('#form_update_nmrkirim #nmrkirim').val() || '');
    }

    function prepareFormUpdateNmrkirimForSubmit() {
        var $form = $('#form_update_nmrkirim');
        if (!$form.length) {
            return;
        }

        $form.find('select.select2').each(function() {
            var $sel = $(this);
            if ($sel.data('select2')) {
                var val = $sel.val();
                if (val !== null && val !== undefined) {
                    $sel.val(val);
                }
            }
        });

        var $tgl = getInputTglJual();
        if ($tgl.length) {
            $tgl.prop('disabled', false).prop('readonly', false);
            if (!$tgl.val()) {
                $tgl.val(tglJualNilaiAktif || '');
            }
        }
    }

    function simpanDetailNomorKirimPenjualan() {
        var $form = $('#form_update_nmrkirim');
        if (!$form.length) {
            return;
        }

        prepareFormUpdateNmrkirimForSubmit();

        var kirimBaru = $.trim($('#form_update_nmrkirim #nmrkirim').val() || '');
        var kirimAwal = $.trim($('#form_update_nmrkirim #nmrkirim_proses').val() || '');
        if (kirimBaru !== kirimAwal) {
            var text = 'Nomor Kirim terjadi PERBEDAAN:\n\nNomor Kirim awal: ' + kirimAwal
                + '\nNomor Kirim baru: ' + kirimBaru
                + '\n\nApakah tetap diproses PERUBAHAN Nomor Kirim?';
            if (!confirm(text)) {
                return;
            }
        }

        var $btn = $('#btn-simpan-detail-nmrkirim');
        $btn.prop('disabled', true);

        $.ajax({
            url: cfg.urlUbahDetailNomorKirim,
            type: 'POST',
            dataType: 'json',
            data: $form.serialize()
        }).done(function(res) {
            if (res && res.ok) {
                window.location.href = (res.redirect || cfg.urlKasirPenjualan);
                return;
            }
            penjualanAlertPesan('Gagal menyimpan', (res && res.message) ? res.message : 'Perubahan tidak dapat disimpan.', 'error');
            $btn.prop('disabled', false);
        }).fail(function(xhr) {
            var msg = 'Tidak dapat menyimpan perubahan detail penjualan.';
            if (xhr && xhr.responseText) {
                try {
                    var parsed = JSON.parse(xhr.responseText);
                    if (parsed && parsed.message) {
                        msg = parsed.message;
                    }
                } catch (ignoreJson) {
                    if (xhr.status === 303 || xhr.status === 302) {
                        window.location.href = cfg.urlKasirPenjualan;
                        return;
                    }
                }
            }
            penjualanAlertPesan('Gagal menyimpan', msg, 'error');
            $btn.prop('disabled', false);
        });
    }

    function submitReloadHalaman() {
        syncReloadFormFields();
        $('#form-reload-penjualan-inisiasi').submit();
    }

    function muatModalPilihBarang(callback, onFinish) {
        var tgl = getTglJualVal();
        if (!tgl) {
            penjualanAlertPesan('Tgl Jual belum diisi', 'Isi tanggal jual terlebih dahulu.', 'warning');
            if (typeof onFinish === 'function') {
                onFinish();
            }
            return;
        }

        var bulanJual = window.penjualanBulanDariTanggalJual(tgl);
        var $bulanPersediaan = $('#filter-bulan-persediaan-penjualan');
        if (bulanJual) {
            $bulanPersediaan.attr('max', bulanJual);
        }
        if (!$bulanPersediaan.val()) {
            $bulanPersediaan.val(bulanJual || '2026-01');
        } else if (bulanJual && $bulanPersediaan.val() > bulanJual) {
            $bulanPersediaan.val(bulanJual);
        }
        window.penjualanBulanPersediaanAktif = $bulanPersediaan.val();

        $('#modal-pilih-barang-loading').removeClass('d-none');
        window.destroyDataTablePilihBarang();
        tglJualNilaiAktif = tgl;
        window.initDataTablePilihBarang(callback, onFinish);
    }

    function getPickerTglJual() {
        return $('#dt_tgl_jual_penjualan');
    }

    function initDatepickerTglJualPenjualan() {
        var $picker = getPickerTglJual();
        if (!$picker.length) {
            return;
        }
        if ($picker.data('DateTimePicker')) {
            return;
        }
        $picker.datetimepicker({
            format: 'D-M-YYYY',
            useCurrent: false
        });
    }

    function revertTglJualPicker() {
        getInputTglJual().val(tglJualNilaiAktif);
        var $picker = getPickerTglJual();
        if ($picker.length && $picker.data('DateTimePicker') && typeof moment !== 'undefined') {
            var m = moment(tglJualNilaiAktif, 'D-M-YYYY', true);
            if (!m.isValid()) {
                m = moment(tglJualNilaiAktif, 'DD-MM-YYYY', true);
            }
            if (m.isValid()) {
                $picker.datetimepicker('date', m);
            }
        }
    }

    function tampilkanBlokirUbahBulan() {
        var labelBulan = cfg.bulanLabelAwal || tglJualBulanKey;
        var pesan = 'Tidak boleh mengubah Tgl Jual ke bulan lain karena sudah ada data barang penjualan pada bulan <strong>' + labelBulan + '</strong>.<br><br>' +
            'Data persediaan berbeda per bulan dan transaksi penjualan harus sesuai bulan persediaan yang dipakai.<br><br>' +
            'Hapus semua barang di Detail Barang terlebih dahulu jika ingin bertransaksi di bulan lain.';

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Tgl Jual tidak dapat diubah',
                html: pesan
            });
        } else {
            alert('Tidak boleh mengubah Tgl Jual ke bulan lain karena sudah ada data barang penjualan pada bulan ini.');
        }
    }

    function setKunciTglJual(terkunci) {
        var $input = getInputTglJual();
        var $picker = getPickerTglJual();
        $input.prop('readonly', !!terkunci);
        if ($picker.length && $picker.data('DateTimePicker')) {
            if (!terkunci) {
                $picker.datetimepicker('enable');
            }
        }
        $picker.find('[data-toggle="datetimepicker"]').css('pointer-events', terkunci ? 'none' : '');
    }

    function onTglJualBerubah() {
        if (sedangBlokirBulan) {
            return;
        }
        var tglBaru = getTglJualVal();
        if (!tglBaru) {
            return;
        }
        var bulanKeyBaru = parseBulanKey(tglBaru);
        if (!bulanKeyBaru || bulanKeyBaru === tglJualBulanKey) {
            return;
        }

        if (cfg.jumlahBarang > 0) {
            sedangBlokirBulan = true;
            revertTglJualPicker();
            tampilkanBlokirUbahBulan();
            setTimeout(function() {
                sedangBlokirBulan = false;
            }, 300);
            return;
        }

        tglJualBulanKey = bulanKeyBaru;
        tglJualNilaiAktif = tglBaru;
    }

    function initPenjualanInputBarang() {
        initDatepickerTglJualPenjualan();
        tglJualNilaiAktif = getTglJualVal();
        getInputTglJual().off('change.penjualanTgl hide.penjualanTgl')
            .on('change.datetimepicker.penjualanTgl hide.datetimepicker.penjualanTgl change.penjualanTgl', function() {
                clearTimeout(tglJualTimer);
                tglJualTimer = setTimeout(onTglJualBerubah, 400);
            });
        getPickerTglJual().off('change.datetimepicker.penjualanTglDp hide.datetimepicker.penjualanTglDp')
            .on('change.datetimepicker.penjualanTglDp hide.datetimepicker.penjualanTglDp', function() {
                clearTimeout(tglJualTimer);
                tglJualTimer = setTimeout(onTglJualBerubah, 400);
            });

        if (cfg.jumlahBarang > 0) {
            setKunciTglJual(true);
        }

        $(document).off('click.penjualanSimpanDetail', '#btn-simpan-detail-nmrkirim')
            .on('click.penjualanSimpanDetail', '#btn-simpan-detail-nmrkirim', function(e) {
                e.preventDefault();
                simpanDetailNomorKirimPenjualan();
            });
    }

    $('#modal-xl.modal-pilih-barang-penjualan').on('shown.bs.modal', function() {
        setTimeout(function() {
            window.sesuaikanDataTablePilihBarang();
        }, 60);
    });

    $(document).on('change', '#filter-bulan-persediaan-penjualan', function() {
        var $picker = $(this);
        var bulanBaru = $.trim($picker.val() || '');
        var bulanJual = window.penjualanBulanDariTanggalJual(getTglJualVal());
        if (!/^\d{4}-(0[1-9]|1[0-2])$/.test(bulanBaru)) {
            return;
        }
        if (bulanBaru < '2026-01') {
            var pesanTanggalLama = 'Tidak ada persediaan sebelum 1 Januari 2026. Silakan pilih bulan mulai Januari 2026.';
            penjualanAlertPesan('Bulan persediaan tidak tersedia', pesanTanggalLama, 'warning');
            $picker.val(window.penjualanBulanPersediaanAktif || bulanJual || '2026-01');
            return;
        }
        if (bulanJual && bulanBaru > bulanJual) {
            penjualanAlertPesan(
                'Bulan persediaan melewati tanggal penjualan',
                'Persediaan setelah tanggal input penjualan tidak dapat digunakan.',
                'warning'
            );
            $picker.val(window.penjualanBulanPersediaanAktif || bulanJual);
            return;
        }
        if (bulanBaru === window.penjualanBulanPersediaanAktif) {
            return;
        }
        window.penjualanBulanPersediaanAktif = bulanBaru;
        $('#modal-pilih-barang-loading').removeClass('d-none');
        if (window.penjualanDtPilihBarang) {
            window.penjualanDtPilihBarang.ajax.reload(null, true);
        } else {
            window.initDataTablePilihBarang();
        }
    });

    $(document).on('click', '#btn-refresh-pilih-barang', function() {
        var $button = $(this);
        if ($button.prop('disabled')) {
            return;
        }
        window.clearPenjualanPilihBarangCache();
        window.penjualanForceRefreshPilihBarang = true;
        window.penjualanForceRefreshPilihBarangDone = true;
        $button.prop('disabled', true);
        $('#modal-pilih-barang-loading').removeClass('d-none');
        if (window.penjualanDtPilihBarang) {
            window.penjualanDtPilihBarang.ajax.reload(function() {
                $button.prop('disabled', false);
            }, true);
        } else {
            window.initDataTablePilihBarang(null, function() {
                $button.prop('disabled', false);
            });
        }
    });

    $(document).on('click', '#table-pilih-barang-penjualan .btn-pilih-barang-penjualan', function() {
        window.openPenjualanPilihBarangModal($(this).attr('data-id'));
    });

    // Nested modal "Isi Jumlah" dipindah ke body agar submit & fokus tidak tertahan parent modal
    $(document).on('show.bs.modal', '#container-modal-pilih-barang-nested .modal', function() {
        var $m = $(this);
        if (!$m.parent().is('body')) {
            $m.appendTo('body');
        }
    });

    $(window).on('resize.penjualanPilihBarang', function() {
        if ($('#modal-xl.modal-pilih-barang-penjualan').hasClass('show')) {
            window.sesuaikanDataTablePilihBarang();
        }
    });

    $(document).on('click', '#btn-input-detail-barang-penjualan', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var $btn = $(this);
        if ($btn.prop('disabled')) {
            return;
        }

        var tgl = getTglJualVal();
        if (!tgl) {
            penjualanAlertPesan('Tgl Jual belum diisi', 'Isi tanggal jual terlebih dahulu.', 'warning');
            return;
        }

        $btn.prop('disabled', true);
        $('#modal-pilih-barang-loading').removeClass('d-none');
        $('#modal-xl').modal('show');

        muatModalPilihBarang(function() {
            /* data sudah dimuat di dalam modal */
        }, function() {
            $btn.prop('disabled', false);
        });
    });

    // Simpan jumlah barang via AJAX: tutup modal → animasi proses → sukses → reload kasir
    $(document).on('submit', '.form-simpan-jumlah-barang-penjualan', function(e) {
        e.preventDefault();
        e.stopPropagation();

        var $form = $(this);
        var $btn = $form.find('.btn-simpan-jumlah-barang');
        var $modalIsi = $form.closest('.modal');
        var uuid = cfg.uuidPenjualan || '';
        if (!uuid) {
            uuid = 'new';
        }

        $form.find('input[name="ajax"]').val('1');
        $form.find('input[name="uuid_penjualan"]').val(uuid);
        $form.find('input[name="uuid_penjualan_proses"]').val(uuid);
        $form.find('input[name="tgl_jual"]').val(getTglJualVal());
        $form.find('input[name="uuid_unit"]').val($('#uuid_unit').val() || '');
        $form.find('input[name="uuid_konsumen"]').val($('#uuid_konsumen').val() || '');
        $form.find('input[name="nmrpesan"]').val($('#nmrpesan').val() || '');
        $form.find('input[name="nmrkirim"]').val($('#nmrkirim').val() || '');

        var jumlahVal = $.trim($form.find('input[name="jumlah"]').val() || '');
        if (!jumlahVal || parseInt(jumlahVal, 10) <= 0) {
            penjualanAlertPesan('Jumlah belum diisi', 'Isi jumlah barang terlebih dahulu.', 'warning');
            return;
        }

        var postData = $form.serialize();
        var postUrl = $form.attr('action');
        var namaBarang = $.trim($form.find('.modal-body input.form-control:disabled').val() || 'barang');

        $btn.prop('disabled', true).text('Menyimpan...');

        // Tutup modal Isi Jumlah + Pilih Barang segera setelah klik Simpan
        if ($modalIsi.length) {
            $modalIsi.modal('hide');
        }
        $('#modal-xl').modal('hide');
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open').css('padding-right', '');

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Menambahkan data penjualan',
                html: 'Sedang memproses <strong>' + $('<div>').text(namaBarang).html() + '</strong>...<br><small>Mohon tunggu sebentar</small>',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: function() {
                    Swal.showLoading();
                }
            });
        }

        $.ajax({
            url: postUrl,
            type: 'POST',
            dataType: 'json',
            data: postData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).done(function(res) {
            if (res && res.ok) {
                window.clearPenjualanPilihBarangCache();
                var redirectUrl = res.redirect || cfg.urlKasirPenjualan;
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil menambahkan barang penjualan',
                        text: (res.message || 'Barang berhasil ditambahkan ke list penjualan.'),
                        confirmButtonText: 'OK',
                        timer: 1800,
                        timerProgressBar: true
                    }).then(function() {
                        window.location.href = redirectUrl;
                    });
                    setTimeout(function() {
                        window.location.href = redirectUrl;
                    }, 1900);
                } else {
                    alert((res.message || 'Barang berhasil ditambahkan.'));
                    window.location.href = redirectUrl;
                }
                return;
            }

            $btn.prop('disabled', false).text('SIMPAN');
            penjualanAlertPesan('Gagal menyimpan', (res && res.message) ? res.message : 'Barang tidak dapat ditambahkan.', 'error');
        }).fail(function(xhr) {
            var msg = 'Tidak dapat menyimpan barang penjualan.';
            if (xhr && xhr.responseText) {
                try {
                    var parsed = JSON.parse(xhr.responseText);
                    if (parsed && parsed.message) {
                        msg = parsed.message;
                    }
                } catch (ignoreJson) {
                    if (xhr.responseText.indexOf('Database Error') !== -1) {
                        msg = 'Error database saat menyimpan penjualan.';
                    }
                }
            }
            $btn.prop('disabled', false).text('SIMPAN');
            penjualanAlertPesan('Gagal menyimpan', msg, 'error');
        });
    });

    initPenjualanInputBarang();

    $(document).on('click', '#btn-kembali-halaman-penjualan', function(e) {
        e.preventDefault();
        navigasiKembaliKeHalamanPenjualan();
    });
})(jQuery);

    if ($('#example1').length && $.fn.DataTable && !$.fn.DataTable.isDataTable('#example1')) {
        try {
            $('#example1').DataTable({
                scrollY: 500,
                scrollX: true
            });
        } catch (eEx1) {}
    }
}

if (document.readyState === 'complete') {
    penjualanInitInputBarangScript();
} else {
    window.addEventListener('load', penjualanInitInputBarangScript);
}
</script>