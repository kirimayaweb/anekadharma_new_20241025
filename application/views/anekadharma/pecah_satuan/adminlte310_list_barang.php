<?php
$bulan_tampil = isset($bulan_persediaan_selected) && $bulan_persediaan_selected !== ''
    ? $bulan_persediaan_selected
    : date('Y-m');
$Data_stock = isset($Data_stock) && is_array($Data_stock) ? $Data_stock : array();
$Data_history_pecah_satuan = isset($Data_history_pecah_satuan) && is_array($Data_history_pecah_satuan) ? $Data_history_pecah_satuan : array();
$tab_aktif = isset($tab_aktif) && in_array($tab_aktif, array('data-barang', 'history-pecah-satuan'), true) ? $tab_aktif : 'data-barang';
$tab_barang_active = ($tab_aktif === 'data-barang') ? ' active' : '';
$tab_history_active = ($tab_aktif === 'history-pecah-satuan') ? ' active' : '';
$panel_barang_show = ($tab_aktif === 'data-barang') ? ' show active' : '';
$panel_history_show = ($tab_aktif === 'history-pecah-satuan') ? ' show active' : '';
$nama_bulan_id = array(
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
);
$ts_bulan_tampil = strtotime($bulan_tampil . '-01');
$bulan_angka_tampil = ($ts_bulan_tampil !== false) ? (int) date('n', $ts_bulan_tampil) : (int) date('n');
$tahun_tampil = ($ts_bulan_tampil !== false) ? date('Y', $ts_bulan_tampil) : date('Y');
$nama_bulan_tampil = isset($nama_bulan_id[$bulan_angka_tampil]) ? $nama_bulan_id[$bulan_angka_tampil] : date('m', $ts_bulan_tampil);

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
                                <a class="nav-link<?php echo $tab_history_active; ?>" id="tab-history-pecah-satuan" data-toggle="pill" href="#panel-history-pecah-satuan" role="tab" aria-controls="panel-history-pecah-satuan" aria-selected="<?php echo ($tab_aktif === 'history-pecah-satuan') ? 'true' : 'false'; ?>">
                                    <span id="tab-history-pecah-satuan-label">Data Pecah Satuan bulan <?php echo htmlspecialchars($nama_bulan_tampil, ENT_QUOTES, 'UTF-8'); ?> <?php echo htmlspecialchars($tahun_tampil, ENT_QUOTES, 'UTF-8'); ?></span>
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
                                            $TOTAL_NILAI_PERSEDIAAN = $TOTAL_NILAI_PERSEDIAAN + $list_data->nilai_persediaan;
                                            echo nominal($list_data->nilai_persediaan);
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
                                    <th style="text-align:right"><?php echo nominal($TOTAL_PERSEDIAAN); ?></th>

                                </tr>
                            </tfoot>

                        </table>
                        </div>
                        </div>

                        <div class="tab-pane fade<?php echo $panel_history_show; ?>" id="panel-history-pecah-satuan" role="tabpanel" aria-labelledby="tab-history-pecah-satuan">
                            <p class="mb-2">
                                Riwayat pecah satuan yang sudah terproses pada bulan
                                <strong><?php echo htmlspecialchars($nama_bulan_tampil, ENT_QUOTES, 'UTF-8'); ?> <?php echo htmlspecialchars($tahun_tampil, ENT_QUOTES, 'UTF-8'); ?></strong>
                                — menampilkan <?php echo count($Data_history_pecah_satuan); ?> transaksi.
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

                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </section>
</div>

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
    var namaBulanId = <?php echo json_encode(array_values($nama_bulan_id)); ?>;
    var dtBarang = null;
    var dtHistory = null;

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
        $('#tab-history-pecah-satuan-label').text('Data Pecah Satuan bulan ' + namaBulan + ' ' + tahun);
    }

    $('#pecah-satuan-tabs a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
        var panelId = $(e.target).attr('href') || '';
        var tabKey = panelId.replace('#panel-', '');
        if (tabKey) {
            $('#tab_aktif').val(tabKey);
        }
        if (panelId === '#panel-history-pecah-satuan') {
            adjustDataTableScroll(dtHistory);
        } else if (panelId === '#panel-data-barang') {
            adjustDataTableScroll(dtBarang);
        }
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
            pageLength: 25,
            lengthMenu: [[25, 50, 100, 250, -1], [25, 50, 100, 250, 'Semua']],
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
            pageLength: 25,
            lengthMenu: [[25, 50, 100, 250, -1], [25, 50, 100, 250, 'Semua']],
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

    if ($('#panel-history-pecah-satuan').hasClass('active')) {
        adjustDataTableScroll(dtHistory);
    } else {
        adjustDataTableScroll(dtBarang);
    }
});
</script>