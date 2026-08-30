<?php
$bulan_tampil = isset($bulan_persediaan_selected) && $bulan_persediaan_selected !== ''
    ? $bulan_persediaan_selected
    : date('Y-m');
$Data_stock = isset($Data_stock) && is_array($Data_stock) ? $Data_stock : array();
$Data_history_pecah_satuan = isset($Data_history_pecah_satuan) && is_array($Data_history_pecah_satuan) ? $Data_history_pecah_satuan : array();
$tab_aktif = isset($tab_aktif) && in_array($tab_aktif, array('data-barang', 'pecah-satuan', 'verifikasi-persediaan', 'history-pecah-satuan'), true)
    ? $tab_aktif
    : 'data-barang';
if ($tab_aktif === 'history-pecah-satuan') {
    $tab_aktif = 'verifikasi-persediaan';
}
$tab_barang_active = ($tab_aktif === 'data-barang') ? ' active' : '';
$tab_pecah_active = ($tab_aktif === 'pecah-satuan') ? ' active' : '';
$tab_verifikasi_active = ($tab_aktif === 'verifikasi-persediaan') ? ' active' : '';
$panel_barang_show = ($tab_aktif === 'data-barang') ? ' show active' : '';
$panel_pecah_show = ($tab_aktif === 'pecah-satuan') ? ' show active' : '';
$panel_verifikasi_show = ($tab_aktif === 'verifikasi-persediaan') ? ' show active' : '';
$nama_bulan_id = array(
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
);
$ts_bulan_tampil = strtotime($bulan_tampil . '-01');
$bulan_angka_tampil = ($ts_bulan_tampil !== false) ? (int) date('n', $ts_bulan_tampil) : (int) date('n');
$tahun_tampil = ($ts_bulan_tampil !== false) ? date('Y', $ts_bulan_tampil) : date('Y');
$nama_bulan_tampil = isset($nama_bulan_id[$bulan_angka_tampil]) ? $nama_bulan_id[$bulan_angka_tampil] : date('m', $ts_bulan_tampil);
$pecah_verifikasi_persediaan = isset($pecah_verifikasi_persediaan) && is_array($pecah_verifikasi_persediaan)
    ? $pecah_verifikasi_persediaan
    : array('count_belum' => 0, 'count_manual' => 0, 'count_otomatis' => 0);
$count_pecah_verifikasi_belum = isset($pecah_verifikasi_persediaan['count_belum']) ? (int) $pecah_verifikasi_persediaan['count_belum'] : 0;
$count_pecah_satuan_bulan = count($Data_history_pecah_satuan);
$url_ajax_pecah_verifikasi_by_bulan = isset($url_ajax_pecah_verifikasi_by_bulan)
    ? $url_ajax_pecah_verifikasi_by_bulan
    : site_url('Tbl_pembelian/ajax_pecah_verifikasi_by_bulan');
$url_pecah_auto_verifikasi = isset($url_pecah_auto_verifikasi)
    ? $url_pecah_auto_verifikasi
    : site_url('Tbl_pembelian/ajax_pecah_auto_verifikasi_bulan');

if (!function_exists('pecah_satuan_list_parse_angka')) {
    function pecah_satuan_list_parse_angka($nilai)
    {
        if ($nilai === null || $nilai === '') {
            return 0.0;
        }
        return (float) preg_replace('/[^0-9.-]/', '', (string) $nilai);
    }
}
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
                <div class="card card-primary card-tabs">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <strong>PECAH SATUAN</strong>
                            </div>
                            <div class="col">
                                <form action="<?php echo $action_cari_gudang; ?>" method="post" id="form-pecah-satuan-bulan" class="d-flex flex-wrap align-items-center ml-md-3">
                                    <label for="bulan_persediaan" class="mb-0 mr-2">Bulan:</label>
                                    <input type="hidden" name="tab_aktif" id="tab_aktif" value="<?php echo htmlspecialchars($tab_aktif, ENT_QUOTES, 'UTF-8'); ?>">
                                    <input type="month" id="bulan_persediaan" name="bulan_persediaan" class="form-control" style="width:auto;max-width:180px;" value="<?php echo htmlspecialchars($bulan_tampil, ENT_QUOTES, 'UTF-8'); ?>">
                                    <span class="ml-3 mb-0" id="keterangan-bulan-pecah-satuan">
                                        Anda bekerja dengan data pecah satuan di bulan
                                        <strong id="label-nama-bulan"><?php echo htmlspecialchars($nama_bulan_tampil, ENT_QUOTES, 'UTF-8'); ?></strong>
                                        dan tahun
                                        <strong id="label-tahun"><?php echo htmlspecialchars($tahun_tampil, ENT_QUOTES, 'UTF-8'); ?></strong>
                                    </span>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card-header p-0 pt-1 border-top-0">
                        <ul class="nav nav-tabs" id="pecah-satuan-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link<?php echo $tab_barang_active; ?>" id="tab-data-barang" data-toggle="pill" href="#panel-data-barang" role="tab" aria-controls="panel-data-barang" aria-selected="<?php echo ($tab_aktif === 'data-barang') ? 'true' : 'false'; ?>">Data Barang</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link<?php echo $tab_pecah_active; ?>" id="tab-pecah-satuan-bulan" data-toggle="pill" href="#panel-pecah-satuan" role="tab" aria-controls="panel-pecah-satuan" aria-selected="<?php echo ($tab_aktif === 'pecah-satuan') ? 'true' : 'false'; ?>">
                                    <span id="tab-pecah-satuan-bulan-label">Pecah Satuan <?php echo htmlspecialchars($nama_bulan_tampil, ENT_QUOTES, 'UTF-8'); ?> <?php echo htmlspecialchars($tahun_tampil, ENT_QUOTES, 'UTF-8'); ?></span>
                                    <span class="badge badge-primary ml-1" id="badge-count-pecah-satuan-bulan"><?php echo (int) $count_pecah_satuan_bulan; ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link<?php echo $tab_verifikasi_active; ?>" id="tab-verifikasi-persediaan" data-toggle="pill" href="#panel-verifikasi-persediaan" role="tab" aria-controls="panel-verifikasi-persediaan" aria-selected="<?php echo ($tab_aktif === 'verifikasi-persediaan') ? 'true' : 'false'; ?>">
                                    <span id="tab-verifikasi-persediaan-label">Verifikasi Persediaan</span>
                                    <span class="badge badge-warning ml-1" id="badge-count-pecah-verifikasi-belum"><?php echo (int) $count_pecah_verifikasi_belum; ?></span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content" id="pecah-satuan-tabs-content">

                        <div class="tab-pane fade<?php echo $panel_barang_show; ?>" id="panel-data-barang" role="tabpanel" aria-labelledby="tab-data-barang">
                        <?php if ($this->session->flashdata('pesan_pecah_satuan')): ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($this->session->flashdata('pesan_pecah_satuan'), ENT_QUOTES, 'UTF-8'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php endif; ?>
                        Pilih Salah Satu produk yang akan di pecah satuanya dengan klik nama barang atau klik tombol pecah satuan. <br/>
                        <span class="text-muted small">Baris dengan persediaan = 0, beli = 0, dan terjual = 0 disembunyikan. Barang dengan persediaan = 0 tetapi sudah ada penjualan/beli tetap ditampilkan (tidak dapat dipilih — stok habis).</span><br/>
                        - DATA PERSEDIAAN (STOCK BARANG)
                        <div class="pecah-satuan-dt-wrap pecah-satuan-dt-wrap-barang">
                        <table id="example" class="display nowrap table table-bordered table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="text-align:center" width="10px">No</th>
                                    <!-- <th style="text-align:center" width="100px">Action</th> -->
                                    <th>Action</th>
                                    <th>Tanggal</th>

                                    <!-- <th>Gudang</th> -->
                                    <th>SPOP</th>
                                    <th>nama barang</th>
                                    <th>harga satuan</th>
                                    <th>satuan</th>
                                    <th>Persediaan</th>
                                    <th>jumlah <br />beli</th>

                                    <!-- <th>nama_barang_jual</th> -->
                                    <th>jumlah <br />terjual</th>
                                    <th>Pecah Satuan</th>
                                    <th>Bahan Produksi</th>
                                    <!-- <th>harga_satuan_jual</th> -->
                                    <!-- <th>margin</th> -->
                                    <th>Sisa <br />Stock</th>
                                    <th>Nominal Stock</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $compare_spop = 0;
                                $Total_per_SPOP = 0;
                                $TOTAL_LUNAS = 0;
                                $TOTAL_HUTANG = 0;
                                $start = 0;
                                $TOTAL_PERSEDIAAN = 0;
                                $TOTAL_NILAI_PERSEDIAAN = 0;
                                foreach ($Data_stock as $list_data) {

                                    // if (($list_data->jumlah_belanja - $list_data->jumlah_terjual) > 0) { //HIDE SISA STOCK =0;

                                    // if ($list_data->uuid_barang) {



                                    // $get_uuid_persediaan = $list_data->uuid_persediaan;

                                    // $sql_penjualan_per_uuid_persediaan = "SELECT `uuid_persediaan`,`uuid_barang`,sum(`jumlah`) as jumlah_per_uuid_persediaan FROM `tbl_penjualan` WHERE `uuid_persediaan`='$get_uuid_persediaan' GROUP by `uuid_persediaan`;";

                                    // // print_r($this->db->query($sql_penjualan_per_uuid_persediaan)->row());

                                    // if ($this->db->query($sql_penjualan_per_uuid_persediaan)->num_rows() > 0) {
                                    //     $Get_data_Rows = $this->db->query($sql_penjualan_per_uuid_persediaan)->row();
                                    //     $Jumlah_penjualan_per_uuid_persediaan = $Get_data_Rows->jumlah_per_uuid_persediaan + $list_data->pecah_satuan;
                                    // } else {
                                    //     $Jumlah_penjualan_per_uuid_persediaan = 0 + $list_data->pecah_satuan;
                                    // }

                                    $Jumlah_penjualan_per_uuid_persediaan = pecah_satuan_list_parse_angka($list_data->penjualan)
                                        + pecah_satuan_list_parse_angka($list_data->pecah_satuan)
                                        + pecah_satuan_list_parse_angka($list_data->bahan_produksi);
                                    $jumlah_persediaan = pecah_satuan_list_parse_angka($list_data->jumlah_sediaan);
                                    $nilai_persediaan_row = pecah_satuan_list_parse_angka($list_data->nilai_persediaan);

                                    if ($jumlah_persediaan > 0 && $Jumlah_penjualan_per_uuid_persediaan > 0) {
                                        $sisa_stock_row = $jumlah_persediaan - $Jumlah_penjualan_per_uuid_persediaan;
                                    } elseif ($jumlah_persediaan > 0) {
                                        $sisa_stock_row = $jumlah_persediaan;
                                    } else {
                                        $sisa_stock_row = 0;
                                    }
                                    if ($sisa_stock_row < 0) {
                                        $sisa_stock_row = 0;
                                    }

                                    $bisa_pilih_pecah = ($jumlah_persediaan > 0 && $sisa_stock_row > 0 && $nilai_persediaan_row > 0);

                                ?>
                                    <tr class="<?php echo $bisa_pilih_pecah ? '' : 'pecah-satuan-row-tidak-tersedia'; ?>">
                                        <td style="text-align:center"><?php echo ++$start ?></td>
                                        <td style="text-align:center">
                                            <?php
                                            if ($bisa_pilih_pecah) {
                                                echo anchor(site_url('tbl_pembelian/pecah_satuan_proses/' . $list_data->uuid_persediaan), '<i class="fa fa-pencil-square-o">Pilih Buat Satuan Baru </i>', array('title' => 'Pilih Buat Satuan Baru ', 'class' => 'btn btn-success btn-sm'));
                                            } else {
                                                echo '<span class="btn btn-secondary btn-sm disabled" title="Tidak ada persediaan — barang tidak dapat dipilih"><i class="fa fa-ban"></i> Tidak dapat dipilih</span>';
                                            }

                                            // Cek apakah UUID_barang ada di tbl_pembelian_pecah_satuan , jika ada ==> tambahkan tombol rollback
                                            // echo "<br/>";
                                            // echo $list_data->uuid_barang;
                                            // echo "<br/>";
                                            // echo $list_data->uuid_barang_pecah;

                                            $this->db->where('uuid_barang_baru', $list_data->uuid_barang);
                                            $get_data_pecah_satuan = $this->db->get('tbl_pembelian_pecah_satuan');
                                    
                                            if ($get_data_pecah_satuan->num_rows() > 0) {

                                            // if( $list_data->uuid_barang_baru){
                                                // echo "<br/>";
                                                // echo $get_data_pecah_satuan->row()->uuid_barang_baru;
                                                echo "<br/>";
                                                echo anchor(site_url('tbl_pembelian/rollback_satuan_proses/' . $list_data->uuid_persediaan), '<i class="fa fa-pencil-square-o">Mengembalikan ke Satuan Awal </i>', array('title' => 'Pilih Buat Satuan Baru ', 'class' => 'btn btn-danger btn-sm'));
                                            }
                                            ?>
                                        </td>
                                        <td style="text-align:left"><?php echo date("d-m-Y", strtotime($list_data->tanggal_beli_persediaan)); ?></td>

                                        <!-- Gudang -->
                                        <!-- <td style="text-align:left;text-transform: uppercase;">
                                            <?php

                                            //echo anchor(site_url('tbl_pembelian/pecah_satuan_proses/' . $list_data->uuid_persediaan), '<i class="fa fa-pencil-square-o" aria-hidden="true">' . $list_data->nama_gudang . '</i>', 'class=""');

                                            ?>

                                            </td> -->


                                            <td style="text-align:left;text-transform: uppercase;">
                                            <?php

                                            echo $list_data->spop;
                                            ?>

                                        </td>

                                        <!-- Nama Barang -->
                                        <td style="text-align:left">
                                            <?php


                                            // echo anchor(site_url('tbl_pembelian/pecah_satuan/' . $list_data->uuid_pembelian), '<i class="fa fa-pencil-square-o" aria-hidden="true">' . $list_data->nama_barang_beli . '</i>', 'class=""');

                                            echo $list_data->nama_barang_persediaan;

                                            ?>
                                        </td>


                                        <!-- Harga Satuan  -->

                                        <td style="text-align:right">
                                            <?php

                                            // if ($list_data->harga_satuan_persediaan and $list_data->harga_satuan_persediaan > 0) {
                                            if (!empty($list_data->harga_satuan_persediaan)) {
                                                echo nominal($list_data->harga_satuan_persediaan);
                                                $X_harga_satuan = $list_data->harga_satuan_persediaan;
                                            } else {
                                                echo "0";
                                                $X_harga_satuan = 0;
                                            }

                                            ?>
                                        </td>


                                        <!-- satuan -->
                                        <td style="text-align:center"><?php echo $list_data->satuan; ?></td>

                                        <!-- nominal Persediaan -->
                                        <td style="text-align:right">
                                            <?php
                                            if ($jumlah_persediaan > 0) {
                                                echo nominal($jumlah_persediaan);
                                                $stock_persediaan = $jumlah_persediaan;
                                            } else {
                                                echo "0";
                                                $stock_persediaan = 0;
                                            }
                                            ?>
                                        </td>

                                        <!-- Jumlah belanja/beli -->
                                        <td style="text-align:right">
                                            <?php
                                            $jumlah_beli_row = pecah_satuan_list_parse_angka(isset($list_data->beli) ? $list_data->beli : 0);
                                            echo nominal($jumlah_beli_row);
                                            ?>
                                        </td>

                                        <!-- Jumlah penjualan -->
                                        <td style="text-align:right">
                                            <?php
                                            // echo $this->db->query($sql_penjualan_per_uuid_persediaan)->num_rows();
                                            // echo "<br/>";
                                            echo nominal(pecah_satuan_list_parse_angka($list_data->penjualan));

 

                                            // DATA PENJUALAN PER SPOP
                                            // if ($list_data->jumlah_terjual and $list_data->jumlah_terjual > 0) {
                                            //     echo nominal($list_data->jumlah_terjual);
                                            //     $x_jumlah_terjual = $list_data->jumlah_terjual;
                                            // } else {
                                            //     echo "0";
                                            //     $x_jumlah_terjual = 0;
                                            // }

                                            ?>
                                        </td>
                                        
                                        <!-- Jumlah pecah satuan -->
                                        <td style="text-align:right">
                                            <?php
                                            // echo $this->db->query($sql_penjualan_per_uuid_persediaan)->num_rows();
                                            // echo "<br/>";
                                            echo nominal($list_data->pecah_satuan);
 
                                            // DATA PENJUALAN PER SPOP
                                            // if ($list_data->jumlah_terjual and $list_data->jumlah_terjual > 0) {
                                            //     echo nominal($list_data->jumlah_terjual);
                                            //     $x_jumlah_terjual = $list_data->jumlah_terjual;
                                            // } else {
                                            //     echo "0";
                                            //     $x_jumlah_terjual = 0;
                                            // }

                                            ?>
                                        </td>
                                        <!-- Jumlah bahan produksi -->
                                        <td style="text-align:right">
                                            <?php
                                            // echo $this->db->query($sql_penjualan_per_uuid_persediaan)->num_rows();
                                            // echo "<br/>";
                                            echo nominal($list_data->bahan_produksi);
                                            // DATA PENJUALAN PER SPOP
                                            // if ($list_data->jumlah_terjual and $list_data->jumlah_terjual > 0) {
                                            //     echo nominal($list_data->jumlah_terjual);
                                            //     $x_jumlah_terjual = $list_data->jumlah_terjual;
                                            // } else {
                                            //     echo "0";
                                            //     $x_jumlah_terjual = 0;
                                            // }

                                            ?>
                                        </td>

                                        <!-- Sisa stock -->
                                        <td style="text-align:right">
                                            <?php echo nominal($sisa_stock_row); ?>
                                        </td>
                                        <!-- 
                                        <td style="text-align:right">
                                            <?php

                                            // echo nominal(($stock_persediaan + $x_jumlah_belanja - $x_jumlah_terjual) * $X_harga_satuan);

                                            // $TOTAL_PERSEDIAAN = $TOTAL_PERSEDIAAN + (($stock_persediaan + $x_jumlah_belanja - $x_jumlah_terjual) * $X_harga_satuan);
                                            ?>
                                        </td> -->

                                        <!-- NOMINAL PERSEDIAAN -->
                                        <td style="text-align:right">

                                            <?php
                                            $TOTAL_NILAI_PERSEDIAAN = $TOTAL_NILAI_PERSEDIAAN + $nilai_persediaan_row;
                                            echo nominal($nilai_persediaan_row);
                                            ?>

                                        </td>

                                        <!-- TOTAL PERSEDIAAN -->
                                        <!-- <td style="text-align:right">

                                            <?php
                                            // $TOTAL_PERSEDIAAN_X = $TOTAL_PERSEDIAAN_X + $list_data->nilai_persediaan;
                                            // echo nominal($TOTAL_PERSEDIAAN_X);
                                            ?>

                                        </td> -->


                                    </tr>

                                <?php
                                    // } //if (($list_data->jumlah_belanja - $list_data->jumlah_terjual) > 0)
                                    // }
                                }
                                ?>


                            </tbody>

                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>TOTAL</th>
                                    <th style="text-align:right"><?php echo nominal($TOTAL_NILAI_PERSEDIAAN); ?></th>

                                </tr>
                            </tfoot>

                        </table>
                        </div>
                        </div>

                        <div class="tab-pane fade<?php echo $panel_pecah_show; ?>" id="panel-pecah-satuan" role="tabpanel" aria-labelledby="tab-pecah-satuan-bulan">
                            <p class="mb-2">
                                Riwayat pecah satuan yang sudah terproses pada bulan
                                <strong><?php echo htmlspecialchars($nama_bulan_tampil, ENT_QUOTES, 'UTF-8'); ?> <?php echo htmlspecialchars($tahun_tampil, ENT_QUOTES, 'UTF-8'); ?></strong>
                                — menampilkan <?php echo (int) $count_pecah_satuan_bulan; ?> transaksi dari <code>tbl_pembelian_pecah_satuan</code>.
                            </p>
                            <div class="pecah-satuan-dt-wrap pecah-satuan-dt-wrap-history">
                            <table id="table-history-pecah-satuan" class="display nowrap table table-bordered table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="text-align:center" width="40px">No</th>
                                        <th>Tanggal Proses</th>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang (Sumber)</th>
                                        <th>Jumlah Dipecah</th>
                                        <th>Satuan</th>
                                        <th>Harga Satuan</th>
                                        <th>Kode Barang Baru</th>
                                        <th>Nama Barang Baru</th>
                                        <th>Jumlah Baru</th>
                                        <th>Satuan Baru</th>
                                        <th>Harga Satuan Baru</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $start_history = 0;
                                    foreach ($Data_history_pecah_satuan as $row_history) {
                                        $tgl_proses = !empty($row_history->proses_input) ? $row_history->proses_input : $row_history->tgl_po;
                                    ?>
                                    <tr>
                                        <td style="text-align:center"><?php echo ++$start_history; ?></td>
                                        <td><?php echo !empty($tgl_proses) ? date('d-m-Y H:i', strtotime($tgl_proses)) : ''; ?></td>
                                        <td><?php echo htmlspecialchars(isset($row_history->kode_barang) ? $row_history->kode_barang : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars(isset($row_history->uraian) ? $row_history->uraian : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td style="text-align:right"><?php echo nominal(isset($row_history->jumlah) ? $row_history->jumlah : 0); ?></td>
                                        <td style="text-align:center"><?php echo htmlspecialchars(isset($row_history->satuan) ? $row_history->satuan : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td style="text-align:right"><?php echo nominal(isset($row_history->harga_satuan) ? $row_history->harga_satuan : 0); ?></td>
                                        <td><?php echo htmlspecialchars(isset($row_history->kode_barang_baru) ? $row_history->kode_barang_baru : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars(isset($row_history->nama_barang_baru) ? $row_history->nama_barang_baru : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td style="text-align:right"><?php echo nominal(isset($row_history->jumlah_barang_baru) ? $row_history->jumlah_barang_baru : 0); ?></td>
                                        <td style="text-align:center"><?php echo htmlspecialchars(isset($row_history->satuan_barang_baru) ? $row_history->satuan_barang_baru : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td style="text-align:right"><?php echo nominal(isset($row_history->harga_satuan_barang_baru) ? $row_history->harga_satuan_barang_baru : 0); ?></td>
                                        <td style="text-align:center">
                                            <?php
                                            if (!empty($row_history->uuid_persediaan)) {
                                                echo anchor(
                                                    site_url('tbl_pembelian/rollback_satuan_proses/' . $row_history->uuid_persediaan),
                                                    '<i class="fa fa-undo"></i> Rollback',
                                                    array('title' => 'Mengembalikan ke Satuan Awal', 'class' => 'btn btn-danger btn-sm')
                                                );
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            </div>
                        </div>

                        <div class="tab-pane fade<?php echo $panel_verifikasi_show; ?>" id="panel-verifikasi-persediaan" role="tabpanel" aria-labelledby="tab-verifikasi-persediaan">
                            <div id="pecah-verifikasi-container">
                                <?php $this->load->view('anekadharma/pecah_satuan/_adminlte310_pecah_satuan_verifikasi_fragment', $pecah_verifikasi_persediaan); ?>
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

<?php $this->load->view('anekadharma/pecah_satuan/_adminlte310_pecah_verifikasi_referensi_modals'); ?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
<style type="text/css">
    .pecah-satuan-dt-wrap {
        width: 100%;
        overflow: hidden;
        margin-top: 0.5rem;
    }
    .pecah-satuan-dt-wrap .dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
    .pecah-satuan-dt-wrap .dataTables_scroll {
        width: 100% !important;
    }
    .pecah-satuan-dt-wrap .dataTables_scrollHead,
    .pecah-satuan-dt-wrap .dataTables_scrollBody {
        overflow-x: auto !important;
        overflow-y: auto !important;
        -webkit-overflow-scrolling: touch;
    }
    .pecah-satuan-dt-wrap-barang .dataTables_scrollBody {
        max-height: 500px;
    }
    .pecah-satuan-dt-wrap-history .dataTables_scrollBody {
        max-height: 500px;
    }
    .pecah-satuan-dt-wrap table.dataTable thead th,
    .pecah-satuan-dt-wrap table.dataTable tbody td {
        white-space: nowrap;
        vertical-align: middle;
    }
    #example tbody tr.pecah-satuan-row-tidak-tersedia {
        background-color: #f4f4f4;
        color: #6c757d;
    }
    #example tbody tr.pecah-satuan-row-tidak-tersedia td {
        opacity: 0.85;
    }
    #keterangan-bulan-pecah-satuan {
        font-size: 1rem;
        line-height: 1.4;
        font-weight: 700;
        color: #FFEB3B;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.45);
    }
    #keterangan-bulan-pecah-satuan strong {
        font-weight: 800;
        color: #FFFFFF;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.55);
    }
    .pecah-ref-manual-diff {
        background-color: #fff3cd !important;
        color: #856404;
        font-weight: 600;
    }
    #modal-pecah-referensi-persediaan {
        z-index: 1065 !important;
    }
    .pecah-verifikasi-dt-wrap .dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
    @media (max-width: 767.98px) {
        #form-pecah-satuan-bulan {
            margin-left: 0 !important;
            margin-top: 0.5rem;
        }
        #keterangan-bulan-pecah-satuan {
            margin-left: 0 !important;
            margin-top: 0.5rem;
            display: block;
            width: 100%;
        }
    }
</style>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script>
window.addEventListener('load', function() {
    if (!window.jQuery || !jQuery.fn || !jQuery.fn.dataTable) {
        console.error('Pecah Satuan: jQuery/DataTables belum dimuat. Muat ulang halaman.');
        return;
    }
    var $ = window.jQuery;
    var bulanPecahSatuanAktif = <?php echo json_encode($bulan_tampil); ?>;
    var urlAjaxPecahVerifikasiByBulan = <?php echo json_encode($url_ajax_pecah_verifikasi_by_bulan); ?>;
    var urlPecahAutoVerifikasi = <?php echo json_encode($url_pecah_auto_verifikasi); ?>;
    var namaBulanId = <?php echo json_encode(array_values($nama_bulan_id)); ?>;
    var dtBarang = null;
    var dtHistory = null;
    var pecahVerifikasiDtInitialized = false;

    var dtLanguageUmum = {
        lengthMenu: 'Tampilkan _MENU_ baris',
        info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
        infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
        infoFiltered: '(disaring dari _MAX_ total data)',
        search: 'Cari:',
        zeroRecords: 'Tidak ada data yang cocok',
        paginate: { first: 'Awal', last: 'Akhir', next: 'Berikutnya', previous: 'Sebelumnya' }
    };

    function adjustDataTableScroll(dt) {
        if (!dt) {
            return;
        }
        dt.columns.adjust();
        if (dt.scroller && typeof dt.scroller.measure === 'function') {
            dt.scroller.measure();
        }
    }

    function updateKeteranganBulan(ym) {
        var parts = (ym || '').split('-');
        if (parts.length !== 2) {
            return;
        }
        var tahun = parts[0];
        var bulanNum = parseInt(parts[1], 10);
        var namaBulan = namaBulanId[bulanNum - 1] || parts[1];
        $('#label-nama-bulan').text(namaBulan);
        $('#label-tahun').text(tahun);
        $('#tab-pecah-satuan-bulan-label').text('Pecah Satuan ' + namaBulan + ' ' + tahun);
    }

    function destroyPecahVerifikasiDt() {
        $('.pecah-verifikasi-dt-table').each(function() {
            var sel = '#' + $(this).attr('id');
            if ($.fn.DataTable.isDataTable(sel)) {
                $(sel).DataTable().destroy();
            }
        });
    }

    function initPecahVerifikasiDt() {
        destroyPecahVerifikasiDt();
        var $activePane = $('#pecah-persediaan-subtabs-content .tab-pane.active');
        var $tables = $activePane.length
            ? $activePane.find('table.pecah-verifikasi-dt-table')
            : $('table.pecah-verifikasi-dt-table');
        if (!$tables.length) {
            $tables = $('#table-pecah-verifikasi-belum');
        }
        $tables.each(function() {
            var sel = '#' + $(this).attr('id');
            if (!$(sel).length || $.fn.DataTable.isDataTable(sel)) {
                return;
            }
            var hasAksi = $(sel + ' thead th').filter(function() {
                return $(this).text().trim() === 'Aksi';
            }).length > 0;
            var orderCol = hasAksi ? 2 : 1;
            $(sel).DataTable({
                scrollY: 450,
                scrollX: true,
                scrollCollapse: true,
                pageLength: 10,
                order: [[orderCol, 'desc']],
                language: $.extend({}, dtLanguageUmum, { emptyTable: 'Tidak ada data' }),
                columnDefs: [{ targets: 0, orderable: false }]
            });
        });
        pecahVerifikasiDtInitialized = true;
    }

    function updateInfoPecahVerifikasi(res) {
        var cBelum = res && res.count_belum != null ? parseInt(res.count_belum, 10) : 0;
        var cManual = res && res.count_manual != null ? parseInt(res.count_manual, 10) : 0;
        var cOtomatis = res && res.count_otomatis != null ? parseInt(res.count_otomatis, 10) : 0;
        $('#info-jumlah-pecah-verifikasi-bulan').text(
            'Belum: ' + cBelum + ' | Manual: ' + cManual + ' | Otomatis: ' + cOtomatis
            + ' — bulan ' + (res && res.bulan_label ? res.bulan_label : '')
        );
        $('#badge-count-pecah-verifikasi-belum').text(cBelum);
        $('.pecah-verifikasi-count-belum').text(cBelum);
        $('.pecah-verifikasi-count-manual').text(cManual);
        $('.pecah-verifikasi-count-otomatis').text(cOtomatis);
    }

    function loadPecahVerifikasiByBulan(bulanYm) {
        if (!bulanYm) return;
        destroyPecahVerifikasiDt();
        $('#pecah-verifikasi-container').html('<div class="text-center text-muted py-4">Memuat verifikasi pecah satuan...</div>');
        $.ajax({
            url: urlAjaxPecahVerifikasiByBulan,
            type: 'GET',
            data: { bulan: bulanYm },
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).done(function(res) {
            if (!res || !res.ok) {
                $('#pecah-verifikasi-container').html('<div class="alert alert-danger mb-0">' + (res && res.message ? res.message : 'Gagal memuat.') + '</div>');
                return;
            }
            $('#pecah-verifikasi-container').html(res.html || '');
            updateInfoPecahVerifikasi(res);
            pecahVerifikasiDtInitialized = false;
            if ($('#panel-verifikasi-persediaan').hasClass('active')) {
                initPecahVerifikasiDt();
            }
        }).fail(function() {
            $('#pecah-verifikasi-container').html('<div class="alert alert-danger mb-0">Gagal memuat verifikasi pecah satuan.</div>');
        });
    }
    window.loadPecahVerifikasiByBulan = loadPecahVerifikasiByBulan;

    $(document).on('click', '#btn-pecah-auto-verifikasi-bulan', function() {
        var bulanYm = $('#bulan_persediaan').val() || bulanPecahSatuanAktif || '';
        if (!bulanYm) {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Perhatian', 'Pilih bulan persediaan terlebih dahulu.', 'warning');
            } else {
                alert('Pilih bulan persediaan terlebih dahulu.');
            }
            return;
        }
        var doProses = function() {
            var $btn = $('#btn-pecah-auto-verifikasi-bulan');
            $btn.prop('disabled', true);
            destroyPecahVerifikasiDt();
            $('#pecah-verifikasi-container').html('<div class="text-center text-muted py-4">Memproses verifikasi otomatis pecah satuan...</div>');
            $.ajax({
                url: urlPecahAutoVerifikasi,
                type: 'POST',
                data: { bulan: bulanYm },
                dataType: 'json',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).done(function(res) {
                $btn.prop('disabled', false);
                if (!res || !res.ok) {
                    $('#pecah-verifikasi-container').html('<div class="alert alert-danger mb-0">' + (res && res.message ? res.message : 'Gagal memproses otomatis.') + '</div>');
                    return;
                }
                $('#pecah-verifikasi-container').html(res.html || '');
                updateInfoPecahVerifikasi(res);
                pecahVerifikasiDtInitialized = false;
                if ($('#panel-verifikasi-persediaan').hasClass('active')) {
                    initPecahVerifikasiDt();
                }
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Selesai', res.message || 'Proses otomatis selesai.', 'success');
                }
            }).fail(function() {
                $btn.prop('disabled', false);
                $('#pecah-verifikasi-container').html('<div class="alert alert-danger mb-0">Gagal memproses verifikasi otomatis pecah satuan.</div>');
            });
        };
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Proses Otomatis Semua?',
                html: 'Akan sinkronkan link pecah satuan ke persediaan bulan <strong>' + bulanYm + '</strong>, terapkan efek stok (sumber &amp; target), dan tandai verifikasi otomatis.<br/>Record refered manual tidak diubah.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Proses',
                cancelButtonText: 'Batal'
            }).then(function(r) {
                if (r.isConfirmed) {
                    doProses();
                }
            });
        } else if (confirm('Proses otomatis verifikasi pecah satuan bulan ' + bulanYm + '?')) {
            doProses();
        }
    });

    $('#pecah-satuan-tabs a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
        var panelId = $(e.target).attr('href') || '';
        var tabKey = panelId.replace('#panel-', '');
        if (tabKey) {
            $('#tab_aktif').val(tabKey);
        }
        if (panelId === '#panel-verifikasi-persediaan') {
            if (!pecahVerifikasiDtInitialized) {
                initPecahVerifikasiDt();
            } else {
                $('.pecah-verifikasi-dt-table').each(function() {
                    var sel = '#' + $(this).attr('id');
                    if ($.fn.DataTable.isDataTable(sel)) {
                        adjustDataTableScroll($(sel).DataTable());
                    }
                });
            }
        } else if (panelId === '#panel-pecah-satuan') {
            adjustDataTableScroll(dtHistory);
        } else if (panelId === '#panel-data-barang') {
            adjustDataTableScroll(dtBarang);
        }
    });

    $(document).on('shown.bs.tab', '#pecah-persediaan-subtabs a[data-toggle="tab"]', function() {
        setTimeout(function() { initPecahVerifikasiDt(); }, 60);
    });

    $('#bulan_persediaan').on('change', function() {
        var bulan = $(this).val() || '';
        if (!bulan) {
            return;
        }
        updateKeteranganBulan(bulan);
        if (bulan === bulanPecahSatuanAktif) {
            return;
        }
        bulanPecahSatuanAktif = bulan;
        $('#form-pecah-satuan-bulan').trigger('submit');
    });

    try {
        if ($.fn.DataTable.isDataTable('#example')) {
            $('#example').DataTable().destroy();
        }
        dtBarang = $('#example').DataTable({
            scrollY: '500px',
            scrollX: true,
            scrollCollapse: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, 'Semua']],
            order: [[3, 'asc']],
            columnDefs: [
                { targets: 0, orderable: false }
            ],
            language: $.extend({}, dtLanguageUmum, {
                emptyTable: 'Belum ada data persediaan untuk bulan ini'
            })
        });
    } catch (dtErrBarang) {
        console.warn('DataTable data barang:', dtErrBarang);
    }

    try {
        if ($.fn.DataTable.isDataTable('#table-history-pecah-satuan')) {
            $('#table-history-pecah-satuan').DataTable().destroy();
        }
        dtHistory = $('#table-history-pecah-satuan').DataTable({
            scrollY: '500px',
            scrollX: true,
            scrollCollapse: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, 'Semua']],
            order: [[1, 'desc']],
            columnDefs: [
                { targets: 0, orderable: false }
            ],
            language: $.extend({}, dtLanguageUmum, {
                emptyTable: 'Belum ada riwayat pecah satuan untuk bulan ini',
                info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ transaksi',
                infoEmpty: 'Menampilkan 0 sampai 0 dari 0 transaksi'
            })
        });
    } catch (dtErrHistory) {
        console.warn('DataTable history pecah satuan:', dtErrHistory);
    }

    if ($('#panel-verifikasi-persediaan').hasClass('active')) {
        initPecahVerifikasiDt();
    } else if ($('#panel-pecah-satuan').hasClass('active')) {
        adjustDataTableScroll(dtHistory);
    } else {
        adjustDataTableScroll(dtBarang);
    }
});
</script>
<?php $this->load->view('anekadharma/pecah_satuan/_adminlte310_pecah_verifikasi_referensi_init'); ?>