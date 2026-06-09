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
	#container-modal-pilih-barang-nested .modal {
		z-index: 1065;
	}
	#container-modal-pilih-barang-nested .modal-dialog.modal-isi-jumlah-barang {
		max-width: min(720px, 96vw);
	}
	#container-modal-pilih-barang-nested .penjualan-label-info-jumlah {
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



                    <div class="card-body">


                        <!-- <form action="<?php //echo $action; 
                                            ?>" method="post"> -->



                        <form action="<?php echo $action_ubah_detail_nomor_kirim; ?>" id="form_update_nmrkirim" method="post">

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
                                    Daftar barang (persediaan) bulan: <strong><?php echo htmlspecialchars($filter_bulan_penjualan['bulan_label'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                    — mengikuti <em>Tgl Jual</em>, hanya <strong>barang</strong> (kategori jasa tidak ditampilkan)
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
                                            <option value="<?php echo $uuid_unit ?>"><?php echo $unit ?></option>
                                            <?php

                                            $sql = "select * from sys_unit order by nama_unit ASC ";
                                            foreach ($this->db->query($sql)->result() as $m) {
                                                echo "<option value='$m->uuid_unit' ";
                                                echo ">  " . strtoupper($m->nama_unit)  . "</option>";
                                            }

                                            ?>
                                        </select>


                                    </div>

                                    <!-- Konsumen -->
                                    <div class="col-3">
                                        <label for="konsumen_nama">Konsumen <?php echo form_error('konsumen_nama') ?></label>
                                        <select name="uuid_konsumen" id="uuid_konsumen" class="form-control select2" style="width: 100%; height: 40px;" required>
                                            <option value="<?php echo $uuid_konsumen ?>"><?php echo $nama_konsumen ?></option>
                                            <?php

                                            // Data Unit
                                            $sql = "select * from sys_unit order by nama_unit ASC ";
                                            foreach ($this->db->query($sql)->result() as $m) {
                                                echo "<option value='$m->uuid_unit' ";
                                                echo ">  " . strtoupper($m->nama_unit)  . "  ==> [UNIT] </option>";
                                            }
                                            // Data Sys_konsumen
                                            $sql = "select * from sys_konsumen order by nama_konsumen ASC ";
                                            foreach ($this->db->query($sql)->result() as $m) {
                                                echo "<option value='$m->uuid_konsumen' ";
                                                echo ">  " . strtoupper($m->nama_konsumen) . strtoupper($m->nmr_kontak_konsumen) . strtoupper($m->alamat_konsumen) . "</option>";
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

                                        <button type="submit" onclick="confirmUbahSPOP(event)" class="btn btn-primary"><?php echo $button_detail_nomor_kirim; ?></button>
                                    </div>
                                    <div class="col-4">
                                    </div>
                                </div>
                            </div>



                            <script>
                                function confirmUbahSPOP(e) {

                                    let input_spop = document.getElementById("nmrkirim").value;
                                    let input_nmrkirim_proses = document.getElementById("nmrkirim_proses").value;

                                    if (input_spop != input_nmrkirim_proses) {
                                        let text = "Nomor Kirim terjadi PERBEDAAN: \n\n Nomor Kirim awal:" + input_nmrkirim_proses + "\n Nomor Kirim baru: " + input_spop + "\n\n Apakah Tetap diproses PERUBAHAN Nomor Kirim? ";

                                        if (confirm(text))
                                            // alert('Proses Ubah SPOP !');
                                            // e.preventDefault();
                                            document.getElementById("form_update_nmrkirim").submit();
                                        else {
                                            // alert('Cancelled! \n harap SPOP dikembalikan ke: ' + input_spop_proses);
                                            e.preventDefault();
                                        }

                                    }


                                }
                            </script>






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
                                                <th style="text-align:left">Tgl Jual</th>
                                                <th style="text-align:center">Nama Barang</th>
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
                                                        <?php
                                                        echo anchor(site_url('tbl_penjualan/delete/' . $list_data->id . '/' . $list_data->uuid_penjualan), 'Hapus DATA', 'onclick="javascript: return confirm(\'Anda Yakin akan Menghapus Penjualan Barang ini ?\')"');

                                                        // echo anchor(site_url('tbl_penjualan/delete/' . $list_data->id . '/' . $list_data->uuid_penjualan), 'onclick="javascript: return confirm(\'Anda Yakin akan Menghapus Penjualan Barang ini ?\')"', '<i class="btn btn-outline-info btn-block btn-flat" aria-hidden="true">Hapus</i>', 'class="btn btn-block btn-flat"  ');

                                                        ?>


                                                        <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#modal-xl-input-barang_<?php echo $list_data->id ?>">
                                                            UBAH <?php //echo $list_data->id 
                                                                    ?>
                                                        </button>

                                                        <!-- <button type="button"  class="btn btn-outline-info btn-block btn-flat"> <i class="fa fa-book"></i> <?php // $button; 
                                                                                                                                                                ?></button> -->
                                                    </td>


                                                    <td>
                                                        <?php
                                                        // echo $list_data->tgl_po; 

                                                        echo date("d M Y", strtotime($list_data->tgl_jual));

                                                        ?>
                                                    </td>



                                                    <td><?php echo $list_data->nama_barang; ?></td>
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
                <h4 class="modal-title">Pilih Barang <small class="text-muted" id="modal-pilih-barang-bulan-label">(Bulan: <?php echo htmlspecialchars($filter_bulan_penjualan['bulan_label'], ENT_QUOTES, 'UTF-8'); ?> — barang saja, tanpa jasa)</small></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="modal-pilih-barang-loading" class="text-center text-muted py-3 d-none">Memuat data persediaan...</div>
                <div class="modal-pilih-barang-table-wrap card-body p-0">

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
                            <?php echo $render_modal_pilih_barang['tbody']; ?>
                        </tbody>



                    </table>
                </div>
                <div id="container-modal-pilih-barang-nested"><?php echo $render_modal_pilih_barang['modals']; ?></div>

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
}
?>





<style type="text/css">
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
</style>

<script>
/* Inisialisasi setelah jQuery layout (AdminLTE) dimuat */
window.penjualanDtPilihBarang = null;
window.penjualanTablePilihBarangId = '#table-pilih-barang-penjualan';

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

window.initDataTablePilihBarang = function() {
    var $ = window.jQuery;
    if (!$ || !$.fn.DataTable) {
        return;
    }
    window.destroyDataTablePilihBarang();
    var $table = $(window.penjualanTablePilihBarangId);
    if (!$table.length) {
        return;
    }
    try {
        window.penjualanDtPilihBarang = $table.DataTable({
            scrollY: window.hitungScrollYPilihBarangPenjualan(),
            scrollX: true,
            scrollCollapse: true,
            destroy: true,
            paging: true,
            searching: true,
            lengthChange: true,
            info: true,
            autoWidth: false,
            order: [[5, 'asc']],
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']],
            dom: '<"row align-items-center mb-2"<"col-sm-6"l><"col-sm-6"f>>rt<"row mt-2"<"col-sm-5"i><"col-sm-7"p>>',
            language: {
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
            }
        });
    } catch (errDt) {
        console.error('DataTable pilih barang:', errDt);
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

    function updateInfoBulan(label) {
        $('#info-bulan-persediaan-penjualan').html(
            'Daftar barang (persediaan) bulan: <strong>' + label + '</strong> — mengikuti <em>Tgl Jual</em>, hanya <strong>barang</strong> (kategori jasa tidak ditampilkan)'
        );
        $('#modal-pilih-barang-bulan-label').text('(Bulan: ' + label + ' — barang saja, tanpa jasa)');
    }

    function syncReloadFormFields() {
        $('#reload_penjualan_tgl_jual').val(getTglJualVal());
        $('#reload_penjualan_uuid_unit').val($('#uuid_unit').val() || '');
        $('#reload_penjualan_uuid_konsumen').val($('#uuid_konsumen').val() || '');
        $('#reload_penjualan_nmrpesan').val($('#nmrpesan').val() || '');
        $('#reload_penjualan_nmrkirim').val($('#nmrkirim').val() || '');
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

        $('#modal-pilih-barang-loading').removeClass('d-none');
        $.ajax({
            url: cfg.urlListPersediaan,
            type: 'POST',
            dataType: 'json',
            data: {
                tgl_jual: tgl,
                uuid_penjualan: cfg.uuidPenjualan,
                uuid_unit: $('#uuid_unit').val() || '',
                uuid_konsumen: $('#uuid_konsumen').val() || '',
                nmrpesan: $('#nmrpesan').val() || '',
                nmrkirim: $('#nmrkirim').val() || ''
            }
        }).done(function(res) {
            $('#modal-pilih-barang-loading').addClass('d-none');
            if (!res || !res.ok) {
                penjualanAlertPesan('Gagal memuat data', (res && res.message) ? res.message : 'Terjadi kesalahan.', 'error');
                return;
            }
            window.destroyDataTablePilihBarang();
            $('#tbody-pilih-barang-penjualan').html(res.tbody || '');
            $('#container-modal-pilih-barang-nested').html(res.modals || '');
            if (res.bulan_label) {
                updateInfoBulan(res.bulan_label);
                cfg.bulanLabelAwal = res.bulan_label;
            }
            if (res.bulan_key) {
                tglJualBulanKey = res.bulan_key;
            }
            tglJualNilaiAktif = getTglJualVal();
            $('#modal-pilih-barang-bulan-label').text('(Bulan: ' + (res.bulan_label || '') + ', ' + (res.jumlah_tampil || 0) + ' barang — tanpa jasa)');
            window.initDataTablePilihBarang();
            setTimeout(function() {
                window.sesuaikanDataTablePilihBarang();
            }, 80);
            if (typeof callback === 'function') {
                callback(res);
            }
        }).fail(function(xhr) {
            $('#modal-pilih-barang-loading').addClass('d-none');
            var msg = 'Tidak dapat memuat daftar persediaan.';
            if (xhr && xhr.responseText) {
                try {
                    var j = JSON.parse(xhr.responseText);
                    if (j && j.message) {
                        msg = j.message;
                    }
                } catch (eJson) {
                    if (xhr.responseText.indexOf('Database Error') !== -1) {
                        msg = 'Error database saat memuat persediaan. Periksa kolom unit di tabel persediaan.';
                    }
                }
            }
            penjualanAlertPesan('Gagal memuat data', msg, 'error');
        }).always(function() {
            if (typeof onFinish === 'function') {
                onFinish();
            }
        });
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
            if (terkunci) {
                $picker.datetimepicker('disable');
            } else {
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
        var parts = bulanKeyBaru.split('-');
        if (parts.length === 2) {
            updateInfoBulan(parts[1] + '/' + parts[0]);
        }
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
    }

    $('#modal-xl.modal-pilih-barang-penjualan').on('shown.bs.modal', function() {
        setTimeout(function() {
            window.sesuaikanDataTablePilihBarang();
        }, 60);
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