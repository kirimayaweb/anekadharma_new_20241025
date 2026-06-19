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

        <?php
        if (!isset($date_awal)) {
            $date_awal = date('Y-m-1 00:00:00');
        }
        if (!isset($date_akhir)) {
            $date_akhir = date('Y-m-t 23:59:59');
        }
        $search_filter = isset($search_filter) ? trim((string) $search_filter) : '';
        if (date('Y', strtotime($date_awal)) < 2020) {
            $Get_date_awal = date('d-m-Y');
        } else {
            $Get_date_awal = date('d-m-Y', strtotime($date_awal));
        }
        if (date('Y', strtotime($date_akhir)) < 2020) {
            $Get_date_akhir = date('d-m-Y');
        } else {
            $Get_date_akhir = date('d-m-Y', strtotime($date_akhir));
        }

        $excel_export_ids = array();
        if (!empty($Tbl_penjualan_data)) {
            foreach ($Tbl_penjualan_data as $row_export) {
                if (!empty($row_export->id)) {
                    $excel_export_ids[] = (int) $row_export->id;
                }
            }
        }
        $excel_export_ids_str = implode(',', $excel_export_ids);

        if (!isset($compare_bulan_num)) {
            $compare_bulan_num = (int) date('m');
        }
        if (!isset($compare_tahun_num)) {
            $compare_tahun_num = (int) date('Y');
        }
        if (!isset($nama_bulan_id) || !is_array($nama_bulan_id)) {
            $nama_bulan_id = array(
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
            );
        }
        if (!isset($gen_tahun_min)) {
            $gen_tahun_min = 2019;
        }
        if (!isset($gen_tahun_max)) {
            $gen_tahun_max = (int) date('Y') + 1;
        }
        if (!isset($active_tab)) {
            $active_tab = 'data';
        }
        $tab_data_active = ($active_tab !== 'compare');
        $tab_compare_active = ($active_tab === 'compare');
        $url_compare_jurnal_penjualan_run = isset($url_compare_jurnal_penjualan_run)
            ? $url_compare_jurnal_penjualan_run
            : site_url('Tbl_penjualan/ajax_compare_jurnal_penjualan_manual_online');
        $url_compare_jurnal_penjualan_excel = isset($url_compare_jurnal_penjualan_excel)
            ? $url_compare_jurnal_penjualan_excel
            : site_url('Tbl_penjualan/excel_compare_jurnal_penjualan_manual_online');
        $url_compare_jurnal_penjualan_import_csv = isset($url_compare_jurnal_penjualan_import_csv)
            ? $url_compare_jurnal_penjualan_import_csv
            : site_url('Tbl_penjualan/ajax_compare_import_csv_jurnal_penjualan');
        $url_compare_jurnal_penjualan_check_csv = isset($url_compare_jurnal_penjualan_check_csv)
            ? $url_compare_jurnal_penjualan_check_csv
            : site_url('Tbl_penjualan/ajax_compare_check_csv_jurnal_penjualan');
        $url_compare_jurnal_penjualan_validate_csv = isset($url_compare_jurnal_penjualan_validate_csv)
            ? $url_compare_jurnal_penjualan_validate_csv
            : site_url('Tbl_penjualan/ajax_compare_validate_csv_jurnal_penjualan');
        $url_compare_jurnal_penjualan_tabel_list = isset($url_compare_jurnal_penjualan_tabel_list)
            ? $url_compare_jurnal_penjualan_tabel_list
            : site_url('Tbl_penjualan/ajax_compare_tabel_list_jurnal_penjualan');
        $url_compare_jurnal_penjualan_tabel_preview = isset($url_compare_jurnal_penjualan_tabel_preview)
            ? $url_compare_jurnal_penjualan_tabel_preview
            : site_url('Tbl_penjualan/ajax_compare_tabel_preview_jurnal_penjualan');
        ?>

        <div class="box box-warning box-solid">

            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <strong style="line-height:1.35; display:inline-block;">SETTING KODE AKUN<br>PENJUALAN</strong>
                            </div>
                            <div class="col-md-7">
                                <?php $action_cari_setting_kode_akun = site_url('Tbl_penjualan/cari_between_date_setting_kode_akun_penjualan'); ?>
                                <form id="form-cari-setting-kode-akun-penjualan" action="<?php echo $action_cari_setting_kode_akun; ?>" method="post">
                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <div class="input-group date" id="tgl_awal" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#tgl_awal" name="tgl_awal" value="<?php echo $Get_date_awal; ?>" required autocomplete="off" />
                                                <div class="input-group-append" data-target="#tgl_awal" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-1 text-center">s/d</div>
                                        <div class="col-md-3">
                                            <div class="input-group date" id="tgl_akhir" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#tgl_akhir" name="tgl_akhir" value="<?php echo $Get_date_akhir; ?>" required autocomplete="off" />
                                                <div class="input-group-append" data-target="#tgl_akhir" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-danger btn-block btn-flat"><i class="fa fa-search" aria-hidden="true"></i> Cari</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-2 text-right">
                                <input type="hidden" id="excel-export-ids" value="<?php echo htmlspecialchars($excel_export_ids_str, ENT_QUOTES, 'UTF-8'); ?>" />
                                <button type="button" class="btn btn-success btn-block" onclick="cetakExcelSettingKodeAkunPenjualan(); return false;">
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Cetak ke Excel
                                </button>
                            </div>
                        </div>
                    </div>
                   

                    <!--  -->



                    <div class="card-body">

                        <ul class="nav nav-tabs setting-kode-akun-penjualan-tabs" id="setting-kode-akun-penjualan-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link<?php echo $tab_data_active ? ' active' : ''; ?>" id="tab-setting-data" data-toggle="pill" href="#panel-setting-data" role="tab" aria-controls="panel-setting-data" aria-selected="<?php echo $tab_data_active ? 'true' : 'false'; ?>">Data</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link<?php echo $tab_compare_active ? ' active' : ''; ?>" id="tab-compare-manual-online" data-toggle="pill" href="#panel-compare-manual-online" role="tab" aria-controls="panel-compare-manual-online" aria-selected="<?php echo $tab_compare_active ? 'true' : 'false'; ?>">Compare data manual - online</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="setting-kode-akun-penjualan-tabs-content">
                            <div class="tab-pane fade<?php echo $tab_data_active ? ' show active' : ''; ?>" id="panel-setting-data" role="tabpanel" aria-labelledby="tab-setting-data">

                        <table id="tblSettingKodeAkunPenjualan" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="text-align:center" width="10px">No</th>
                                    <th rowspan="2" style="text-align:center" width="10px">Kode Akun</th>
                                    <th rowspan="2">Tgl Jual</th>
                                    <th rowspan="2">nmrkirim</th>
                                    <th rowspan="2">nmrpesan</th>
                                    <th rowspan="2">Konsumen</th>
                                    <th rowspan="2">Kode</th>
                                    <th rowspan="2">Nama Barang</th>
                                    <th rowspan="2">Unit</th>
                                    <th rowspan="2">Satuan</th>
                                    <th rowspan="2">Harga Satuan</th>
                                    <th rowspan="2">Jumlah</th>
                                    <th rowspan="2">Total <br> harga</th>
                                    <th colspan="2" style="text-align:center" width="50px">Debit</th>
                                    <th colspan="2" style="text-align:center" width="50px">Kredit</th>
                                </tr>
                                <tr>
                                    <th width="25px">UM PPH PSL 22</th>
                                    <th>Piutang</th>
                                    <th width="25px">Penjualan DPP</th>
                                    <th>Utang PPN</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!function_exists('url_input_kode_akun_penjualan')) {
                                    function url_input_kode_akun_penjualan($nmrkirim, $tgl_jual, $nmrpesan)
                                    {
                                        $get_date = date('Y-m-d', strtotime($tgl_jual));
                                        return site_url('Tbl_penjualan/input_kode_akun/' . rawurlencode($nmrkirim) . '/' . $get_date)
                                            . '?nmrpesan=' . rawurlencode($nmrpesan);
                                    }
                                }
                                if (!function_exists('url_ubah_kode_akun_penjualan')) {
                                    function url_ubah_kode_akun_penjualan($nmrkirim, $tgl_jual, $nmrpesan)
                                    {
                                        $get_date = date('Y-m-d', strtotime($tgl_jual));
                                        return site_url('Tbl_penjualan/ubah_kode_akun/' . rawurlencode($nmrkirim) . '/' . $get_date)
                                            . '?nmrpesan=' . rawurlencode($nmrpesan);
                                    }
                                }

                                $compare_tgl_jual = 0;
                                $compare_nmr_pesan = 0;
                                $compare_nmr_kirim = 0;
                                $compare_uuid_penjualan = 0;

                                $Total_Jumlah_per_nmrkirim = 0;
                                $Total_UMPPHPSL22_per_nmrkirim = 0;
                                $Total_piutang_per_nmrkirim = 0;
                                $Total_penjualandpp_per_nmrkirim = 0;
                                $Total_utangppn_per_nmrkirim = 0;

                                $TOTAL_ALL_JUMLAH = 0;
                                $TOTAL_ALL_UMPPHPSL22 = 0;
                                $TOTAL_ALL_piutang = 0;
                                $TOTAL_ALL_penjualandpp = 0;
                                $TOTAL_ALL_utangppn = 0;
                                $start = 0;
                                foreach ($Tbl_penjualan_data as $list_data) {

                                    $GET_tgl_jual = date("d-m-Y", strtotime($list_data->tgl_jual));

                                    if (($start >= 1) and (((string) $compare_nmr_kirim !== (string) $list_data->nmrkirim) || ((string) $compare_nmr_pesan !== (string) $list_data->nmrpesan))) {
                                        // Buat 1 baris untuk total dan background = KUNING

                                ?>


                                        <!-- // Buat 1 baris untuk total dan background = KUNING -->
                                        <tr class="row-penjualan-subtotal" data-group-nmrkirim="<?php echo htmlspecialchars($compare_nmr_kirim, ENT_QUOTES, 'UTF-8'); ?>" data-group-nmrpesan="<?php echo htmlspecialchars($compare_nmr_pesan, ENT_QUOTES, 'UTF-8'); ?>">
                                            <!-- BARIS TOTAL -->
                                            <td><?php echo ++$start; ?></td>
                                            <td data-order="1">
                                                <?php 
                                                // echo "Kode Akun 1"; 
                                                ?>
                                            </td>
                                            <td style="background-color:yellow;" align="right"><?php
                                                                                                // echo date("d M Y", strtotime($list_data->tgl_jual));
                                                                                                // echo $GET_tgl_jual;

                                                                                                echo "<font color='red'><strong>TOTAL</strong></font>"

                                                                                                ?>
                                            </td>

                                            <!-- Kolom Nomor Kirim -->
                                            <td style="background-color:yellow;" align="left" data-order="<?php echo htmlspecialchars($compare_nmr_kirim, ENT_QUOTES, 'UTF-8'); ?>"><?php echo "<font color='red'><strong>" . htmlspecialchars($compare_nmr_kirim, ENT_QUOTES, 'UTF-8') . "</strong></font>"; ?></td>
                                            
                                            <!-- kolom Nomor pesan -->
                                            <td style="background-color:yellow;" align="left" data-order="<?php echo htmlspecialchars($compare_nmr_pesan, ENT_QUOTES, 'UTF-8'); ?>"><?php echo "<font color='red'><strong>" . htmlspecialchars($compare_nmr_pesan, ENT_QUOTES, 'UTF-8') . "</strong></font>"; ?></td>
                                            
                                            <td></td>
                                            <td></td>
                                            <td><?php //echo $list_data->nama_barang; 
                                                ?></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>


                                            <td style="background-color:yellow;" align="right"> 
                                                <?php 
                                                // echo "<font color='red'><strong>" . nominal($Total_Jumlah_per_nmrkirim) . "</strong></font>"; 
                                                echo "<font color='red'><strong>" . number_format($Total_Jumlah_per_nmrkirim, 2, ',', '.') . "</strong></font>"; 
                                                ?> 
                                                </td>
                                            <td style="background-color:yellow;" align="right"> 
                                                <?php 
                                                //echo "<font color='red'><strong>" . nominal($Total_UMPPHPSL22_per_nmrkirim) . "</strong></font>"; 
                                                echo "<font color='red'><strong>" . number_format($Total_UMPPHPSL22_per_nmrkirim, 2, ',', '.') . "</strong></font>"; 
                                                ?> 
                                                </td>
                                            <td style="background-color:yellow;" align="right"> 
                                                <?php 
                                                // echo "<font color='red'><strong>" . nominal($Total_piutang_per_nmrkirim) . "</strong></font>" 
                                                echo "<font color='red'><strong>" . number_format($Total_piutang_per_nmrkirim, 2, ',', '.') . "</strong></font>" 
                                                ?> 
                                                </td>
                                            <td style="background-color:yellow;" align="right"> 
                                                <?php 
                                                // echo "<font color='red'><strong>" . nominal($Total_penjualandpp_per_nmrkirim) . "</strong></font>";
                                                echo "<font color='red'><strong>" . number_format($Total_penjualandpp_per_nmrkirim, 2, ',', '.') . "</strong></font>";
                                                 ?> 
                                                 </td>
                                            <td style="background-color:yellow;" align="right"> 
                                                <?php 
                                                // echo "<font color='red'><strong>" . nominal($Total_utangppn_per_nmrkirim) . "</strong></font>" 
                                                echo "<font color='red'><strong>" . number_format($Total_utangppn_per_nmrkirim, 2, ',', '.') . "</strong></font>" 
                                                ?> 
                                                </td>
                                            <?php
                                            // nmrkirim baru , me NOL kan total nmrkirim
                                            $Total_Jumlah_per_nmrkirim = 0;
                                            $Total_UMPPHPSL22_per_nmrkirim = 0;
                                            $Total_piutang_per_nmrkirim = 0;
                                            $Total_penjualandpp_per_nmrkirim = 0;
                                            $Total_utangppn_per_nmrkirim = 0;
                                            ?>
                                            <!-- END OF BARIS TOTAL -->
                                        </tr>

                                        <!-- Tgl Jual & nmrpesan baru -->

                                        <tr class="row-penjualan-data" data-penjualan-id="<?php echo (int) $list_data->id; ?>" data-row-id="<?php echo (int) $list_data->id; ?>" data-nmrkirim="<?php echo htmlspecialchars($list_data->nmrkirim, ENT_QUOTES, 'UTF-8'); ?>" data-nmrpesan="<?php echo htmlspecialchars($list_data->nmrpesan, ENT_QUOTES, 'UTF-8'); ?>">

                                            <td><?php echo ++$start; ?><span class="d-none export-row-id"><?php echo (int) $list_data->id; ?></span></td>
                                            <td data-order="0">
                                                <?php 
                                                // echo "Kode Akun 2"; 


                                                $Get_date = date("Y-m-d", strtotime($list_data->tgl_jual));

                                                if ($list_data->kode_akun) {
                                                    echo $list_data->kode_akun;
                                                    echo "<br/>";



                                                    echo anchor(url_ubah_kode_akun_penjualan($list_data->nmrkirim, $list_data->tgl_jual, $list_data->nmrpesan), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH KODE AKUN</i>', 'class="btn btn-warning btn-xs link-kode-akun-penjualan"');
                                                }else{
                                                    echo anchor(url_input_kode_akun_penjualan($list_data->nmrkirim, $list_data->tgl_jual, $list_data->nmrpesan), '<i class="fa fa-pencil-square-o" aria-hidden="true">INPUT KODE AKUN</i>', 'class="btn btn-danger btn-xs link-kode-akun-penjualan"');
                                                }
                                                
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo date("d M Y", strtotime($list_data->tgl_jual));
                                                // echo $GET_tgl_jual;

                                                echo "<br/>";

                                                $date_tgl_jual = date("Y-m-d", strtotime($list_data->tgl_jual));

                                                echo anchor(site_url('Tbl_penjualan/cetak_penjualan_per_uuid_penjualan/' . $list_data->uuid_penjualan . '/' . $date_tgl_jual . '/' . $list_data->nmrkirim), '<i class="fa fa-pencil-square-o" aria-hidden="true">Cetak </i>', 'class="btn btn-success btn-xs"  target="_blank"');

                                                // echo anchor(site_url('Tbl_penjualan/kasir_penjualan/' . $list_data->uuid_penjualan . '/' . $date_tgl_jual . '/' . $list_data->nmrkirim), '<i class="fa fa-pencil-square-o" aria-hidden="true">Tambah </i>', 'class="btn btn-danger btn-xs"  target="_blank"');
                                                
                                                echo anchor(site_url('tbl_penjualan/kasir_penjualan/' . $list_data->uuid_penjualan), '<i class="fa fa-pencil-square-o" aria-hidden="true">Ubah Data</i>', 'class="btn btn-warning btn-xs"  ');



                                                ?>
                                            </td>
                                            
                                            <!-- Kolom Nomor Kirim -->
                                            <td><?php echo $list_data->nmrkirim; ?></td>

                                            <td align="left">
                                                <?php
                                                echo $list_data->nmrpesan;
                                                echo "<br/>";

                                                // echo anchor(site_url('tbl_penjualan/update_penjualan/' . $list_data->uuid_penjualan_proses), '<i class="fa fa-pencil-square-o" aria-hidden="true">Ubah</i>', 'class="btn btn-warning btn-xs"  ');
                                                // echo "";
                                                // HAPUS
                                                // echo anchor(site_url('tbl_penjualan/delete/' . $list_data->id), 'Hapus', 'onclick="javasciprt: return confirm(\'Anda Yakin Akan Menghapus Data Penjualan ini ?\')" ');
                                                ?>
                                            </td>
                                            
                                            <td align="left"> <?php echo $list_data->konsumen_nama; ?> </td>


                                            <td align="left"><?php echo $list_data->kode_barang; ?></td>
                                            <td align="left"><?php echo $list_data->nama_barang; ?></td>
                                            <td align="left"><?php echo $list_data->unit; ?></td>
                                            <td align="left"><?php echo $list_data->satuan; ?></td>
                                            <td align="right">
                                                <?php
                                                // echo number_to_amount('123,456,789,012', 2, 'de_DE'); // Returns 123,46 billion
                                                // echo "<br/>";
                                                // echo nominal($list_data->harga_satuan); 
                                                echo number_format($list_data->harga_satuan, 2, ',', '.'); 
                                                
                                                ?>
                                                
                                            </td>


                                            <td align="right">
                                                <?php

                                                $jumlah_per_nmrkirim = $list_data->jumlah * $list_data->harga_satuan;

                                                // echo nominal($jumlah_per_nmrkirim);
                                                echo nominal($list_data->jumlah);

                                                $Total_Jumlah_per_nmrkirim = $Total_Jumlah_per_nmrkirim + $jumlah_per_nmrkirim;
                                                $TOTAL_ALL_JUMLAH = $TOTAL_ALL_JUMLAH + $jumlah_per_nmrkirim;

                                                // umpphpsl22
                                                $x_var_umpphpsl22 = 1.351351;
                                                $umpphpsl22_per_nmrkirim = ($jumlah_per_nmrkirim * $x_var_umpphpsl22) / 100;
                                                $Total_UMPPHPSL22_per_nmrkirim = $Total_UMPPHPSL22_per_nmrkirim + $umpphpsl22_per_nmrkirim;
                                                $TOTAL_ALL_UMPPHPSL22 = $TOTAL_ALL_UMPPHPSL22 + $umpphpsl22_per_nmrkirim;

                                                $x_piutang_percentage = 11.261261;
                                                $piutang_per_nmrkirim = ($jumlah_per_nmrkirim - (($jumlah_per_nmrkirim * $x_piutang_percentage) / 100));
                                                $Total_piutang_per_nmrkirim = $Total_piutang_per_nmrkirim + $piutang_per_nmrkirim;
                                                $TOTAL_ALL_piutang = $TOTAL_ALL_piutang + $piutang_per_nmrkirim;

                                                $x_penjualandpp_percentage = 90.090090;
                                                $penjualandpp_per_nmrkirim = ($jumlah_per_nmrkirim * $x_penjualandpp_percentage) / 100;
                                                $Total_penjualandpp_per_nmrkirim = $Total_penjualandpp_per_nmrkirim + $penjualandpp_per_nmrkirim;
                                                $TOTAL_ALL_penjualandpp = $TOTAL_ALL_penjualandpp + $penjualandpp_per_nmrkirim;


                                                $x_utangppn_percentage = 9.909910;
                                                $utangppn_per_nmrkirim = ($jumlah_per_nmrkirim * $x_utangppn_percentage) / 100;
                                                $Total_utangppn_per_nmrkirim = $Total_utangppn_per_nmrkirim + $utangppn_per_nmrkirim;
                                                $TOTAL_ALL_utangppn = $TOTAL_ALL_utangppn + $utangppn_per_nmrkirim;

                                                ?>

                                            </td>

                                            <td align="right"> <?php echo nominal($jumlah_per_nmrkirim); ?> </td>
                                            <td align="right"> 
                                                <?php 
                                                // echo nominal($umpphpsl22_per_nmrkirim); 
                                                echo number_format($umpphpsl22_per_nmrkirim, 2, ',', '.'); 
                                                ?> 
                                                </td>

                                            <td align="right">
                                                <?php
                                                // echo nominal($piutang_per_nmrkirim);
                                                echo number_format($piutang_per_nmrkirim, 2, ',', '.');
                                                ?></td>

                                            <td align="right">
                                                <?php
                                                // echo nominal($penjualandpp_per_nmrkirim);
                                                echo number_format($penjualandpp_per_nmrkirim, 2, ',', '.');
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php
                                                // echo nominal($utangppn_per_nmrkirim);
                                                echo number_format($utangppn_per_nmrkirim, 2, ',', '.');
                                                ?>
                                            </td>



                                            <!-- END OF Tgl Jual & nmrpesan baru -->

                                        </tr>


                                    <?php

                                        $compare_nmr_pesan = $list_data->nmrpesan;
                                        $compare_nmr_kirim = $list_data->nmrkirim;
                                        $compare_tgl_jual = $list_data->tgl_jual;
                                    } else {
                                    ?>

                                        <tr class="row-penjualan-data" data-penjualan-id="<?php echo (int) $list_data->id; ?>" data-row-id="<?php echo (int) $list_data->id; ?>" data-nmrkirim="<?php echo htmlspecialchars($list_data->nmrkirim, ENT_QUOTES, 'UTF-8'); ?>" data-nmrpesan="<?php echo htmlspecialchars($list_data->nmrpesan, ENT_QUOTES, 'UTF-8'); ?>">

                                            <td><?php echo ++$start; ?><span class="d-none export-row-id"><?php echo (int) $list_data->id; ?></span></td>
                                            <td data-order="0">
                                                <?php 
                                                // echo "Kode Akun 3"; 

                                                $Get_date = date("Y-m-d", strtotime($list_data->tgl_jual));

                                                if ($list_data->kode_akun) {
                                                    echo $list_data->kode_akun;
                                                    echo "<br/>";
                                                    echo anchor(url_ubah_kode_akun_penjualan($list_data->nmrkirim, $list_data->tgl_jual, $list_data->nmrpesan), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH KODE AKUN</i>', 'class="btn btn-warning btn-xs link-kode-akun-penjualan"');
                                                }else{
                                                    echo anchor(url_input_kode_akun_penjualan($list_data->nmrkirim, $list_data->tgl_jual, $list_data->nmrpesan), '<i class="fa fa-pencil-square-o" aria-hidden="true">INPUT KODE AKUN</i>', 'class="btn btn-danger btn-xs link-kode-akun-penjualan"');
                                                }

                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo date("d M Y", strtotime($list_data->tgl_jual));
                                                // echo $GET_tgl_jual;

                                                echo "<br/>";

                                                $date_tgl_jual = date("Y-m-d", strtotime($list_data->tgl_jual));

                                                echo anchor(site_url('Tbl_penjualan/cetak_penjualan_per_uuid_penjualan/' . $list_data->uuid_penjualan . '/' . $date_tgl_jual . '/' . $list_data->nmrkirim), '<i class="fa fa-pencil-square-o" aria-hidden="true">Cetak </i>', 'class="btn btn-success btn-xs"  target="_blank"');

                                                // echo anchor(site_url('Tbl_penjualan/kasir_penjualan/' . $list_data->uuid_penjualan . '/' . $date_tgl_jual . '/' . $list_data->nmrkirim), '<i class="fa fa-pencil-square-o" aria-hidden="true">Tambah </i>', 'class="btn btn-danger btn-xs"  target="_blank"');

                                                // echo "<br/>";

                                                echo anchor(site_url('tbl_penjualan/kasir_penjualan/' . $list_data->uuid_penjualan), '<i class="fa fa-pencil-square-o" aria-hidden="true">Ubah Data</i>', 'class="btn btn-warning btn-xs"  ');
                                                // echo "";
                                            //    HAPUS
                                                // echo anchor(site_url('tbl_penjualan/delete/' . $list_data->id), 'Hapus', 'onclick="javasciprt: return confirm(\'Anda Yakin Akan Menghapus Data Penjualan ini ?\')" ');
                                                


                                                ?>
                                            </td>
                                            
                                            <td><?php echo $list_data->nmrkirim; ?></td>
                                            <td align="left">
                                                <?php
                                                echo $list_data->nmrpesan;
                                                ?>
                                            </td>
                                            
                                            <td align="left"> <?php echo $list_data->konsumen_nama; ?> </td>


                                            <td align="left"><?php echo $list_data->kode_barang; ?></td>
                                            <td align="left"><?php echo $list_data->nama_barang; ?></td>
                                            <td align="left"><?php echo $list_data->unit; ?></td>
                                            <td align="left"><?php echo $list_data->satuan; ?></td>
                                            <td align="right">
                                                <?php
                                                // echo number_to_amount('123,456,789,012', 2, 'de_DE'); // Returns 123,46 billion
                                                // echo "<br/>";
                                                // echo nominal($list_data->harga_satuan); 
                                                echo number_format($list_data->harga_satuan, 2, ',', '.'); 
                                                ?>
                                                </td>


                                            <td align="right">
                                                <?php

                                                $jumlah_per_nmrkirim = $list_data->jumlah * $list_data->harga_satuan;

                                                // echo nominal($jumlah_per_nmrkirim);
                                                echo nominal($list_data->jumlah);

                                                $Total_Jumlah_per_nmrkirim = $Total_Jumlah_per_nmrkirim + $jumlah_per_nmrkirim;
                                                $TOTAL_ALL_JUMLAH = $TOTAL_ALL_JUMLAH + $jumlah_per_nmrkirim;

                                                // umpphpsl22
                                                $x_var_umpphpsl22 = 1.351351;
                                                $umpphpsl22_per_nmrkirim = ($jumlah_per_nmrkirim * $x_var_umpphpsl22) / 100;
                                                $Total_UMPPHPSL22_per_nmrkirim = $Total_UMPPHPSL22_per_nmrkirim + $umpphpsl22_per_nmrkirim;
                                                $TOTAL_ALL_UMPPHPSL22 = $TOTAL_ALL_UMPPHPSL22 + $umpphpsl22_per_nmrkirim;

                                                $x_piutang_percentage = 11.261261;
                                                $piutang_per_nmrkirim = ($jumlah_per_nmrkirim - (($jumlah_per_nmrkirim * $x_piutang_percentage) / 100));
                                                $Total_piutang_per_nmrkirim = $Total_piutang_per_nmrkirim + $piutang_per_nmrkirim;
                                                $TOTAL_ALL_piutang = $TOTAL_ALL_piutang + $piutang_per_nmrkirim;

                                                $x_penjualandpp_percentage = 90.090090;
                                                $penjualandpp_per_nmrkirim = ($jumlah_per_nmrkirim * $x_penjualandpp_percentage) / 100;
                                                $Total_penjualandpp_per_nmrkirim = $Total_penjualandpp_per_nmrkirim + $penjualandpp_per_nmrkirim;
                                                $TOTAL_ALL_penjualandpp = $TOTAL_ALL_penjualandpp + $penjualandpp_per_nmrkirim;


                                                $x_utangppn_percentage = 9.909910;
                                                $utangppn_per_nmrkirim = ($jumlah_per_nmrkirim * $x_utangppn_percentage) / 100;
                                                $Total_utangppn_per_nmrkirim = $Total_utangppn_per_nmrkirim + $utangppn_per_nmrkirim;
                                                $TOTAL_ALL_utangppn = $TOTAL_ALL_utangppn + $utangppn_per_nmrkirim;

                                                ?>

                                            </td>

                                            <td align="right"> 
                                                <?php 
                                                // echo nominal($jumlah_per_nmrkirim); 
                                                echo number_format($jumlah_per_nmrkirim, 2, ',', '.'); 
                                                ?> 
                                                </td>
                                            <td align="right"> 
                                                <?php 
                                                // echo nominal($umpphpsl22_per_nmrkirim); 
                                                echo number_format($umpphpsl22_per_nmrkirim, 2, ',', '.'); 
                                                ?> 
                                                </td>

                                            <td align="right">
                                                <?php
                                                // echo nominal($piutang_per_nmrkirim);
                                                echo number_format($piutang_per_nmrkirim, 2, ',', '.');
                                                ?></td>

                                            <td align="right">
                                                <?php
                                                // echo nominal($penjualandpp_per_nmrkirim);
                                                echo number_format($penjualandpp_per_nmrkirim, 2, ',', '.');
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php
                                                // echo nominal($utangppn_per_nmrkirim);
                                                echo number_format($utangppn_per_nmrkirim, 2, ',', '.');
                                                ?>
                                            </td>



                                        </tr>

                                    <?php

                                        $compare_nmr_pesan = $list_data->nmrpesan;
                                        $compare_nmr_kirim = $list_data->nmrkirim;
                                        $compare_tgl_jual = $list_data->tgl_jual;
                                        
                                    }
                                    ?>



                                    <!-- ================================ -->



                                <?php

                                    // $compare_nmr_kirim = $list_data->nmrkirim;
                                    // $compare_tgl_jual = $list_data->tgl_jual;
                                    // $compare_uuid_penjualan = $list_data->uuid_penjualan;
                                }
                                ?>





                                <?php if (!empty($Tbl_penjualan_data)) { ?>
                                <!-- TOTAL nmrkirim AKHIR -->
                                <tr class="row-penjualan-subtotal" data-group-nmrkirim="<?php echo htmlspecialchars($compare_nmr_kirim, ENT_QUOTES, 'UTF-8'); ?>" data-group-nmrpesan="<?php echo htmlspecialchars($compare_nmr_pesan, ENT_QUOTES, 'UTF-8'); ?>">
                                    <td><?php echo ++$start; ?></td>
                                    <td data-order="1"></td>
                                    <td style="background-color:yellow;" align="right"><?php echo "<font color='red'><strong>TOTAL</strong></font>"; ?></td>
                                    <td style="background-color:yellow;" align="left" data-order="<?php echo htmlspecialchars($compare_nmr_kirim, ENT_QUOTES, 'UTF-8'); ?>"><?php echo isset($compare_nmr_kirim) ? "<font color='red'><strong>" . htmlspecialchars($compare_nmr_kirim, ENT_QUOTES, 'UTF-8') . "</strong></font>" : ''; ?></td>
                                    <td style="background-color:yellow;" align="left" data-order="<?php echo htmlspecialchars($compare_nmr_pesan, ENT_QUOTES, 'UTF-8'); ?>"><?php echo isset($compare_nmr_pesan) ? "<font color='red'><strong>" . htmlspecialchars($compare_nmr_pesan, ENT_QUOTES, 'UTF-8') . "</strong></font>" : ''; ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>

                                    <td style="background-color:yellow; text-align: right" align="right"> 
                                        <?php 
                                        // echo "<font color='red'><strong>" . nominal($Total_Jumlah_per_nmrkirim) . "</strong></font>"; 
                                        echo "<font color='red'><strong>" . number_format($Total_Jumlah_per_nmrkirim, 2, ',', '.') . "</strong></font>"; 
                                        ?> 
                                        </td>
                                    <td style="background-color:yellow; text-align: right" align="right"> 
                                        <?php 
                                        // echo "<font color='red'><strong>" . nominal($Total_UMPPHPSL22_per_nmrkirim) . "</strong></font>"; 
                                        echo "<font color='red'><strong>" . number_format($Total_UMPPHPSL22_per_nmrkirim, 2, ',', '.') . "</strong></font>"; 
                                        ?> 
                                        </td>
                                    <td style="background-color:yellow;" align="right"> 
                                        <?php 
                                        //  echo "<font color='red'><strong>" . nominal($Total_piutang_per_nmrkirim) . "</strong></font>"; 
                                         echo "<font color='red'><strong>" . number_format($Total_piutang_per_nmrkirim, 2, ',', '.') . "</strong></font>"; 
                                        ?> 
                                        </td>
                                    <td style="background-color:yellow;" align="right"> 
                                        <?php 
                                        // echo "<font color='red'><strong>" . nominal($Total_penjualandpp_per_nmrkirim) . "</strong></font>"; 
                                        echo "<font color='red'><strong>" . number_format($Total_penjualandpp_per_nmrkirim, 2, ',', '.') . "</strong></font>"; 
                                        ?> 
                                        </td>
                                    <td style="background-color:yellow;" align="right"> 
                                        <?php 
                                        // echo "<font color='red'><strong>" . nominal($Total_utangppn_per_nmrkirim) . "</strong></font>"; 
                                        echo "<font color='red'><strong>" . number_format($Total_utangppn_per_nmrkirim, 2, ',', '.') . "</strong></font>"; 
                                        ?> 
                                        </td>



                                </tr>
                                <?php } ?>
                            </tbody>

                            <tfoot>

                                <tr>
                                    <th>No</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th style="text-align:right">TOTAL</th>
                                    <th style="text-align:right" align="right">
                                        <?php 
                                        // echo nominal($TOTAL_ALL_JUMLAH); 
                                        echo number_format($TOTAL_ALL_JUMLAH, 2, ',', '.'); 
                                        ?>
                                        </th>
                                    <th style="text-align:right" align="right">
                                        <?php 
                                        // echo nominal($TOTAL_ALL_UMPPHPSL22); 
                                        echo number_format($TOTAL_ALL_UMPPHPSL22, 2, ',', '.'); 
                                        ?>
                                        </th>
                                    <th style="text-align:right" align="right">
                                        <?php 
                                        // echo nominal($TOTAL_ALL_piutang); 
                                        echo number_format($TOTAL_ALL_piutang, 2, ',', '.'); 
                                        ?>
                                        </th>
                                    <th style="text-align:right" align="right">
                                        <?php 
                                        // echo nominal($TOTAL_ALL_penjualandpp); 
                                        echo number_format($TOTAL_ALL_penjualandpp, 2, ',', '.'); 
                                        ?>
                                        </th>
                                    <th style="text-align:right" align="right">
                                        <?php 
                                        // echo nominal($TOTAL_ALL_utangppn); 
                                        echo number_format($TOTAL_ALL_utangppn, 2, ',', '.'); 
                                        ?>
                                        </th>

                                </tr>

                            </tfoot>

                        </table>

                            </div><!-- /.tab-pane data -->

                            <div class="tab-pane fade<?php echo $tab_compare_active ? ' show active' : ''; ?>" id="panel-compare-manual-online" role="tabpanel" aria-labelledby="tab-compare-manual-online">

                                <div class="row mb-2">
                                    <div class="col-md-12">
                                        <small class="text-muted d-block mb-2">
                                            Bandingkan data jurnal online (<strong>buku_besar source=penjualan</strong> — sudah setting kode akun per bulan/tahun)
                                            dengan tabel manual hasil upload CSV (format jurnal: tanggal, keterangan, kode rekening, debet, kredit).
                                            Pilih file CSV — tabel database akan langsung dibuat otomatis (sama seperti halaman setting kode pembelian).
                                        </small>

                                        <label for="compare_penjualan_csv_file" class="mb-1">Pilih file CSV (upload ke database menjadi tabel data)</label>
                                        <div class="d-flex flex-wrap align-items-end compare-csv-upload-row mb-3">
                                            <div class="custom-file custom-file-sm mb-0 compare-csv-file-wrap">
                                                <input type="file" class="custom-file-input" id="compare_penjualan_csv_file" accept=".csv,text/csv">
                                                <label class="custom-file-label" for="compare_penjualan_csv_file" data-browse="Pilih">Cari / pilih file CSV...</label>
                                            </div>
                                        </div>

                                        <div id="compare-penjualan-csv-processing" class="alert alert-info py-2 d-none mb-3">
                                            <i class="fas fa-spinner fa-spin"></i>
                                            <strong id="compare-penjualan-csv-processing-title">Memproses CSV...</strong>
                                            <span id="compare-penjualan-csv-processing-text" class="d-block small mt-1 mb-0">Membuat tabel baru, menyesuaikan kolom id/tanggal/keterangan/debet/kredit, dan meng-upload data...</span>
                                        </div>

                                        <div id="compare-penjualan-csv-upload-info" class="alert alert-light border py-2 d-none mb-3">
                                            <div class="small mb-1"><span class="text-muted">File:</span> <strong id="compare-penjualan-csv-filename">—</strong></div>
                                            <div class="small mb-1"><span class="text-muted">Tabel DB:</span> <strong id="compare-penjualan-csv-tablename" class="text-primary">—</strong> <span class="text-muted" id="compare-penjualan-csv-rowcount"></span></div>
                                            <div class="small text-muted mb-2">
                                                Tabel otomatis memiliki kolom <strong>id</strong> (INTEGER AUTO_INCREMENT PRIMARY KEY).
                                                Kolom <strong>tanggal</strong> dinormalisasi ke DATE dengan bulan/tahun terpilih — tanggal kosong menjadi tanggal akhir bulan.
                                            </div>
                                            <button type="button" id="btn-compare-penjualan-csv-cek-data" class="btn btn-outline-info btn-sm"><i class="fas fa-table"></i> Detail Tabel</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-end compare-toolbar-row flex-wrap">
                                    <div class="col-auto mb-2">
                                        <label for="compare_bulan_penjualan" class="small mb-1">Bulan</label>
                                        <select id="compare_bulan_penjualan" class="form-control form-control-sm compare-toolbar-control">
                                            <?php foreach ($nama_bulan_id as $num => $label_bulan) { ?>
                                                <option value="<?php echo (int) $num; ?>"<?php echo ((int) $num === (int) $compare_bulan_num) ? ' selected' : ''; ?>><?php echo htmlspecialchars($label_bulan, ENT_QUOTES, 'UTF-8'); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-auto mb-2">
                                        <label for="compare_tahun_penjualan" class="small mb-1">Tahun</label>
                                        <select id="compare_tahun_penjualan" class="form-control form-control-sm compare-toolbar-control">
                                            <?php for ($th = $gen_tahun_max; $th >= $gen_tahun_min; $th--) { ?>
                                                <option value="<?php echo (int) $th; ?>"<?php echo ((int) $th === (int) $compare_tahun_num) ? ' selected' : ''; ?>><?php echo (int) $th; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-auto mb-2">
                                        <label for="compare_tabel_penjualan" class="small mb-1">Pilih tabel database</label>
                                        <select id="compare_tabel_penjualan" class="form-control form-control-sm compare-toolbar-control compare-toolbar-tabel">
                                            <option value="">— Muat daftar tabel —</option>
                                        </select>
                                    </div>
                                    <div class="col-auto mb-2">
                                        <label class="small mb-1 d-block">&nbsp;</label>
                                        <div class="d-flex flex-wrap align-items-center">
                                            <button type="button" id="btn-compare-jurnal-penjualan" class="btn btn-info btn-sm d-none"><i class="fas fa-columns"></i> Compare</button>
                                            <button type="button" id="btn-compare-penjualan-excel-all" class="btn btn-success btn-sm d-none ml-2"><i class="fa fa-file-excel-o"></i> Cetak ke Excel</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-secondary py-2" id="compare-penjualan-info-ringkas">
                                    <strong>Bulan:</strong> <span id="compare-penjualan-label-bulan">—</span>
                                    &nbsp;|&nbsp; <strong>Tabel manual:</strong> <span id="compare-penjualan-label-tabel">—</span>
                                    &nbsp;|&nbsp; <strong>Data manual:</strong> <span id="compare-penjualan-count-data-manual">—</span>
                                    &nbsp;|&nbsp; <strong>Data online:</strong> <span id="compare-penjualan-count-data-online">—</span>
                                    &nbsp;|&nbsp; <strong>Manual tidak di online:</strong> <span id="compare-penjualan-count-manual">—</span>
                                    &nbsp;|&nbsp; <strong>Online tidak di manual:</strong> <span id="compare-penjualan-count-online">—</span>
                                    &nbsp;|&nbsp; <strong>Tanpa kode rekening:</strong> <span id="compare-penjualan-count-tanpa-kode">—</span>
                                </div>
                                <div class="alert alert-info py-2 mb-3" id="compare-penjualan-status">
                                    Pilih file CSV (otomatis generate tabel), lalu pilih bulan, tahun, dan tabel manual — klik <strong>Compare</strong>. Setelah compare selesai, tombol <strong>Cetak ke Excel</strong> akan muncul.
                                </div>

                                <div id="compare-penjualan-results-panel" class="d-none">
                                <div class="compare-penjualan-stats-grid mb-3">
                                    <div class="compare-stat-card compare-stat-manual">
                                        <div class="compare-stat-label">Data Manual</div>
                                        <div class="compare-stat-value" id="compare-stat-data-manual">0</div>
                                    </div>
                                    <div class="compare-stat-card compare-stat-online">
                                        <div class="compare-stat-label">Data Online</div>
                                        <div class="compare-stat-value" id="compare-stat-data-online">0</div>
                                    </div>
                                    <div class="compare-stat-card compare-stat-warning">
                                        <div class="compare-stat-label">Manual ≠ Online</div>
                                        <div class="compare-stat-value" id="compare-stat-manual-miss">0</div>
                                    </div>
                                    <div class="compare-stat-card compare-stat-info">
                                        <div class="compare-stat-label">Online ≠ Manual</div>
                                        <div class="compare-stat-value" id="compare-stat-online-miss">0</div>
                                    </div>
                                    <div class="compare-stat-card compare-stat-danger">
                                        <div class="compare-stat-label">Tanpa Kode Rek.</div>
                                        <div class="compare-stat-value" id="compare-stat-tanpa-kode">0</div>
                                    </div>
                                </div>

                                <?php
                                $compare_penjualan_sections = array(
                                    array(
                                        'jenis' => 'data_manual',
                                        'num' => '1',
                                        'label' => 'Data Manual',
                                        'subtitle' => 'Tabel CSV / database yang dipilih',
                                        'badge' => 'compare-penjualan-badge-data-manual',
                                        'table' => 'table-compare-penjualan-data-manual',
                                        'theme' => 'primary',
                                        'icon' => 'fa-database',
                                        'col' => 'col-lg-6',
                                    ),
                                    array(
                                        'jenis' => 'data_online',
                                        'num' => '2',
                                        'label' => 'Data Online',
                                        'subtitle' => 'Buku besar penjualan (sudah setting kode akun)',
                                        'badge' => 'compare-penjualan-badge-data-online',
                                        'table' => 'table-compare-penjualan-data-online',
                                        'theme' => 'info',
                                        'icon' => 'fa-cloud',
                                        'col' => 'col-lg-6',
                                    ),
                                    array(
                                        'jenis' => 'manual_tidak_di_online',
                                        'num' => '3',
                                        'label' => 'Manual tidak ada di Online',
                                        'subtitle' => 'Ada di manual, tidak cocok / tidak ada di online',
                                        'badge' => 'compare-penjualan-badge-manual',
                                        'table' => 'table-compare-penjualan-manual',
                                        'theme' => 'warning',
                                        'icon' => 'fa-exclamation-triangle',
                                        'col' => 'col-lg-6',
                                    ),
                                    array(
                                        'jenis' => 'online_tidak_di_manual',
                                        'num' => '4',
                                        'label' => 'Online tidak ada di Manual',
                                        'subtitle' => 'Ada di online, tidak cocok / tidak ada di manual',
                                        'badge' => 'compare-penjualan-badge-online',
                                        'table' => 'table-compare-penjualan-online',
                                        'theme' => 'cyan',
                                        'icon' => 'fa-exchange-alt',
                                        'col' => 'col-lg-6',
                                    ),
                                    array(
                                        'jenis' => 'online_tanpa_kode_rekening',
                                        'num' => '5',
                                        'label' => 'Online tanpa kode rekening',
                                        'subtitle' => 'Penjualan belum di-setting kode akun',
                                        'badge' => 'compare-penjualan-badge-tanpa-kode',
                                        'table' => 'table-compare-penjualan-tanpa-kode',
                                        'theme' => 'danger',
                                        'icon' => 'fa-unlink',
                                        'col' => 'col-12',
                                    ),
                                );
                                ?>
                                <div class="compare-penjualan-pair-hint alert alert-light border-left border-primary py-2 px-3 mb-3">
                                    <i class="fas fa-columns text-primary mr-1"></i>
                                    <strong>Tabel 1 &amp; 2</strong> ditampilkan berdampingan untuk membandingkan data manual vs online.
                                    <strong>Tabel 3 &amp; 4</strong> menampilkan selisih / ketidakcocokan antar kedua sumber.
                                </div>
                                <div class="row">
                                <?php foreach ($compare_penjualan_sections as $sec) { ?>
                                <div class="<?php echo $sec['col']; ?> mb-3">
                                    <div class="compare-penjualan-section-card compare-theme-<?php echo $sec['theme']; ?>">
                                        <div class="compare-penjualan-section-header">
                                            <div class="compare-penjualan-section-title">
                                                <span class="compare-section-num"><?php echo $sec['num']; ?></span>
                                                <i class="fas <?php echo $sec['icon']; ?> compare-section-icon"></i>
                                                <div>
                                                    <div class="compare-section-label"><?php echo htmlspecialchars($sec['label'], ENT_QUOTES, 'UTF-8'); ?></div>
                                                    <div class="compare-section-subtitle"><?php echo htmlspecialchars($sec['subtitle'], ENT_QUOTES, 'UTF-8'); ?></div>
                                                </div>
                                            </div>
                                            <div class="compare-penjualan-section-actions">
                                                <span id="<?php echo $sec['badge']; ?>" class="badge compare-section-badge">0</span>
                                                <button type="button" class="btn btn-sm btn-outline-success btn-compare-penjualan-excel" data-jenis="<?php echo $sec['jenis']; ?>" title="Cetak ke Excel">
                                                    <i class="fa fa-file-excel-o"></i> Excel
                                                </button>
                                            </div>
                                        </div>
                                        <div class="compare-dt-wrap compare-penjualan-dt-wrap">
                                            <table id="<?php echo $sec['table']; ?>" class="table table-bordered table-sm compare-dt compare-penjualan-dt" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th class="col-no">No</th>
                                                        <th class="col-tanggal">Tanggal</th>
                                                        <th class="col-kode">Kode Rekening</th>
                                                        <th class="col-ket">Keterangan</th>
                                                        <th class="col-debet">Debet</th>
                                                        <th class="col-kredit">Kredit</th>
                                                        <th class="col-catatan">Catatan</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot>
                                                    <tr class="compare-dt-total-row">
                                                        <th colspan="4" class="text-right">Total</th>
                                                        <th class="compare-total-debet text-right">—</th>
                                                        <th class="compare-total-kredit text-right">—</th>
                                                        <th></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                                </div><!-- /.row compare sections -->
                                </div><!-- /#compare-penjualan-results-panel -->

                                <div class="modal fade" id="modal-compare-penjualan-csv-preview" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document" style="max-width:95%;">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white py-2">
                                                <h5 class="modal-title">Detail Tabel CSV</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <div class="modal-body pb-2">
                                                <p class="text-muted small mb-2" id="compare-penjualan-csv-preview-meta">Memuat...</p>
                                                <div id="compare-penjualan-csv-preview-loading" class="text-center py-4 text-muted d-none"><i class="fas fa-spinner fa-spin"></i> Memuat...</div>
                                                <div class="compare-penjualan-csv-preview-dt-wrap">
                                                    <table id="table-compare-penjualan-csv-preview" class="table table-bordered table-striped table-sm" style="width:100%;font-size:12px;">
                                                        <thead><tr></tr></thead><tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="modal-footer py-2"><button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Tutup</button></div>
                                        </div>
                                    </div>
                                </div>

                            </div><!-- /.tab-pane compare -->
                        </div><!-- /.tab-content -->
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </section>
</div>

<style type="text/css">
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
    .bootstrap-datetimepicker-widget {
        z-index: 99999 !important;
    }
    .DTFC_Cloned tfoot {
        display: none !important;
    }
    #tblSettingKodeAkunPenjualan thead th.sorting,
    #tblSettingKodeAkunPenjualan thead th.sorting_asc,
    #tblSettingKodeAkunPenjualan thead th.sorting_desc {
        cursor: pointer;
    }
    .nav-tabs.setting-kode-akun-penjualan-tabs {
        border-bottom: 2px solid #ffc107;
        margin-bottom: 15px;
    }
    .nav-tabs.setting-kode-akun-penjualan-tabs .nav-item {
        margin-bottom: -2px;
    }
    .nav-tabs.setting-kode-akun-penjualan-tabs .nav-link {
        background-color: #ffffff;
        border: 2px solid #ffc107;
        border-bottom: none;
        color: #888888;
        font-weight: normal;
        margin-right: 4px;
        border-radius: 4px 4px 0 0;
        opacity: 0.75;
    }
    .nav-tabs.setting-kode-akun-penjualan-tabs .nav-link:hover {
        background-color: #f8f9fa;
        color: #666666;
        opacity: 0.9;
    }
    .nav-tabs.setting-kode-akun-penjualan-tabs .nav-link.active {
        background-color: #007bff;
        border-color: #ffc107;
        color: #000000;
        font-weight: bold;
        opacity: 1;
    }
    .compare-dt-wrap {
        width: 100%;
        overflow: hidden;
    }
    .compare-dt-wrap .dataTables_wrapper {
        width: 100%;
        font-size: 13px;
    }
    .compare-dt-wrap table.dataTable thead th,
    .compare-dt-wrap table.dataTable tbody td {
        font-size: 12.5px;
        padding: 7px 10px;
        vertical-align: middle;
    }
    .compare-toolbar-row .compare-toolbar-control {
        width: 110px;
        min-width: 110px;
    }
    #compare_tahun_penjualan.compare-toolbar-control {
        width: 88px;
        min-width: 88px;
    }
    #compare_tabel_penjualan.compare-toolbar-tabel {
        width: 360px;
        min-width: 270px;
        max-width: 480px;
    }
    .compare-csv-file-wrap {
        max-width: 520px;
        min-width: 280px;
        flex: 0 1 520px;
    }
    /* Compare penjualan — panel hasil & statistik */
    #compare-penjualan-results-panel {
        margin-top: 8px;
        animation: comparePenjualanFadeIn 0.35s ease;
    }
    @keyframes comparePenjualanFadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .compare-penjualan-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 10px;
    }
    .compare-stat-card {
        border-radius: 8px;
        padding: 12px 14px;
        text-align: center;
        border: 1px solid #dee2e6;
        background: #fff;
        box-shadow: 0 1px 3px rgba(0,0,0,.06);
    }
    .compare-stat-card .compare-stat-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: #6c757d;
        font-weight: 600;
        margin-bottom: 4px;
    }
    .compare-stat-card .compare-stat-value {
        font-size: 22px;
        font-weight: 700;
        line-height: 1.2;
    }
    .compare-stat-manual { border-left: 4px solid #007bff; }
    .compare-stat-manual .compare-stat-value { color: #007bff; }
    .compare-stat-online { border-left: 4px solid #17a2b8; }
    .compare-stat-online .compare-stat-value { color: #17a2b8; }
    .compare-stat-warning { border-left: 4px solid #ffc107; }
    .compare-stat-warning .compare-stat-value { color: #d39e00; }
    .compare-stat-info { border-left: 4px solid #6f42c1; }
    .compare-stat-info .compare-stat-value { color: #6f42c1; }
    .compare-stat-danger { border-left: 4px solid #dc3545; }
    .compare-stat-danger .compare-stat-value { color: #dc3545; }
    .compare-penjualan-pair-hint {
        border-left-width: 4px !important;
        font-size: 13px;
    }
    /* Card per section */
    .compare-penjualan-section-card {
        border-radius: 10px;
        border: 1px solid #dee2e6;
        background: #fff;
        overflow: hidden;
        height: 100%;
        box-shadow: 0 2px 8px rgba(0,0,0,.05);
        display: flex;
        flex-direction: column;
    }
    .compare-penjualan-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
        padding: 10px 14px;
        border-bottom: 1px solid rgba(0,0,0,.08);
    }
    .compare-penjualan-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }
    .compare-section-num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        font-size: 13px;
        font-weight: 700;
        flex-shrink: 0;
        color: #fff;
    }
    .compare-section-icon {
        font-size: 18px;
        opacity: 0.85;
        flex-shrink: 0;
    }
    .compare-section-label {
        font-weight: 700;
        font-size: 14px;
        line-height: 1.25;
        color: #212529;
    }
    .compare-section-subtitle {
        font-size: 11px;
        color: #6c757d;
        line-height: 1.3;
        margin-top: 2px;
    }
    .compare-penjualan-section-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }
    .compare-section-badge {
        font-size: 13px;
        padding: 5px 10px;
        border-radius: 20px;
        min-width: 32px;
    }
    .compare-theme-primary .compare-penjualan-section-header { background: linear-gradient(135deg, #e7f1ff 0%, #f8fbff 100%); }
    .compare-theme-primary .compare-section-num { background: #007bff; }
    .compare-theme-primary .compare-section-icon { color: #007bff; }
    .compare-theme-primary .compare-section-badge { background: #007bff; color: #fff; }
    .compare-theme-info .compare-penjualan-section-header { background: linear-gradient(135deg, #e0f7fa 0%, #f0fdff 100%); }
    .compare-theme-info .compare-section-num { background: #17a2b8; }
    .compare-theme-info .compare-section-icon { color: #17a2b8; }
    .compare-theme-info .compare-section-badge { background: #17a2b8; color: #fff; }
    .compare-theme-warning .compare-penjualan-section-header { background: linear-gradient(135deg, #fff8e1 0%, #fffdf5 100%); }
    .compare-theme-warning .compare-section-num { background: #ffc107; color: #212529; }
    .compare-theme-warning .compare-section-icon { color: #d39e00; }
    .compare-theme-warning .compare-section-badge { background: #ffc107; color: #212529; }
    .compare-theme-cyan .compare-penjualan-section-header { background: linear-gradient(135deg, #ede7f6 0%, #faf8ff 100%); }
    .compare-theme-cyan .compare-section-num { background: #6f42c1; }
    .compare-theme-cyan .compare-section-icon { color: #6f42c1; }
    .compare-theme-cyan .compare-section-badge { background: #6f42c1; color: #fff; }
    .compare-theme-danger .compare-penjualan-section-header { background: linear-gradient(135deg, #fdecea 0%, #fff8f8 100%); }
    .compare-theme-danger .compare-section-num { background: #dc3545; }
    .compare-theme-danger .compare-section-icon { color: #dc3545; }
    .compare-theme-danger .compare-section-badge { background: #dc3545; color: #fff; }
    .compare-penjualan-dt-wrap {
        flex: 1 1 auto;
        padding: 0;
        min-height: 200px;
    }
    .compare-penjualan-dt-wrap .dataTables_scrollHead {
        background: #f4f6f9;
    }
    .compare-penjualan-dt thead th {
        background: #343a40 !important;
        color: #fff !important;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        border-color: #454d55 !important;
        white-space: nowrap;
    }
    .compare-penjualan-dt tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }
    .compare-penjualan-dt tbody tr:hover {
        background-color: #e8f4fd !important;
    }
    .compare-penjualan-dt .col-no { width: 42px; text-align: center; }
    .compare-penjualan-dt .col-tanggal { width: 95px; white-space: nowrap; }
    .compare-penjualan-dt .col-kode { width: 110px; font-family: Consolas, Monaco, monospace; font-size: 12px; }
    .compare-penjualan-dt .col-ket { min-width: 180px; max-width: 280px; }
    .compare-penjualan-dt .col-debet,
    .compare-penjualan-dt .col-kredit { width: 105px; }
    .compare-penjualan-dt .col-catatan { min-width: 160px; max-width: 240px; }
    .compare-penjualan-dt td.text-kode {
        font-family: Consolas, Monaco, monospace;
        font-weight: 600;
        color: #0056b3;
        background: #f0f7ff;
    }
    .compare-penjualan-dt td.text-ket {
        white-space: normal;
        word-break: break-word;
        line-height: 1.35;
    }
    .compare-penjualan-dt td.text-catatan {
        white-space: normal;
        word-break: break-word;
        font-size: 11.5px;
        color: #856404;
        background: #fffbf0;
        line-height: 1.35;
    }
    .compare-penjualan-dt td.text-amount {
        text-align: right;
        font-family: Consolas, Monaco, monospace;
        font-weight: 600;
        white-space: nowrap;
    }
    .compare-penjualan-dt td.text-amount-debet { color: #155724; }
    .compare-penjualan-dt td.text-amount-kredit { color: #721c24; }
    .compare-penjualan-dt td.text-amount-empty { color: #adb5bd; }
    .compare-penjualan-dt tfoot .compare-dt-total-row th {
        background: #e9ecef;
        font-weight: 700;
        font-size: 12px;
        border-top: 2px solid #adb5bd;
        padding: 8px 10px;
    }
    .compare-penjualan-dt tfoot .compare-total-debet { color: #155724; font-family: Consolas, Monaco, monospace; }
    .compare-penjualan-dt tfoot .compare-total-kredit { color: #721c24; font-family: Consolas, Monaco, monospace; }
    .compare-penjualan-dt-wrap .dataTables_filter input {
        border-radius: 4px;
        border: 1px solid #ced4da;
        padding: 4px 8px;
        font-size: 12px;
    }
    .compare-penjualan-dt-wrap .dataTables_info,
    .compare-penjualan-dt-wrap .dataTables_length,
    .compare-penjualan-dt-wrap .dataTables_paginate {
        font-size: 12px;
        padding: 6px 10px;
    }
</style>
<script>
    var TABLE_SETTING_KODE_AKUN_PENJUALAN = '#tblSettingKodeAkunPenjualan';
    var initialSearchFilterSettingKodeAkunPenjualan = <?php echo json_encode($search_filter); ?>;

    function isDataTableSettingKodeAkunPenjualanAktif() {
        return !!(window.jQuery && jQuery.fn.DataTable && jQuery.fn.DataTable.isDataTable(TABLE_SETTING_KODE_AKUN_PENJUALAN));
    }

    function formatRupiahIDR(value) {
        return new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(value || 0);
    }

    function parseRupiahToFloat(text) {
        var normalized = String(text || '')
            .replace(/\./g, '')
            .replace(',', '.')
            .replace(/[^0-9.-]/g, '');
        var num = parseFloat(normalized);
        return isNaN(num) ? 0 : num;
    }

    function ambilTextCellDariDataTableCell(cellHtmlOrText) {
        return jQuery('<div>').html(cellHtmlOrText).text().trim();
    }

    function isBarisDataPenjualanSetting(node) {
        return !!(node && node.getAttribute && node.getAttribute('data-penjualan-id'));
    }

    function tulisFooterTotalPenjualanSetting(totalJumlah, totalUmp, totalPiutang, totalDpp, totalPpn) {
        var $allRows = jQuery(TABLE_SETTING_KODE_AKUN_PENJUALAN + ' tfoot tr, .dataTables_scrollFoot tfoot tr');
        $allRows.each(function() {
            var $cells = jQuery(this).find('th,td');
            if ($cells.length < 17) {
                return;
            }
            $cells.eq(12).text(formatRupiahIDR(totalJumlah));
            $cells.eq(13).text(formatRupiahIDR(totalUmp));
            $cells.eq(14).text(formatRupiahIDR(totalPiutang));
            $cells.eq(15).text(formatRupiahIDR(totalDpp));
            $cells.eq(16).text(formatRupiahIDR(totalPpn));
        });
    }

    function updateTotalFooterDariDataTablePenjualanSetting() {
        if (!isDataTableSettingKodeAkunPenjualanAktif()) {
            return;
        }
        var table = jQuery(TABLE_SETTING_KODE_AKUN_PENJUALAN).DataTable();
        var totalJumlah = 0;
        var totalUmp = 0;
        var totalPiutang = 0;
        var totalDpp = 0;
        var totalPpn = 0;

        table.rows({ search: 'applied', page: 'all' }).every(function() {
            var node = this.node();
            if (!isBarisDataPenjualanSetting(node)) {
                return;
            }
            var rowData = this.data();
            if (!rowData || rowData.length < 17) {
                return;
            }
            totalJumlah += parseRupiahToFloat(ambilTextCellDariDataTableCell(rowData[12]));
            totalUmp += parseRupiahToFloat(ambilTextCellDariDataTableCell(rowData[13]));
            totalPiutang += parseRupiahToFloat(ambilTextCellDariDataTableCell(rowData[14]));
            totalDpp += parseRupiahToFloat(ambilTextCellDariDataTableCell(rowData[15]));
            totalPpn += parseRupiahToFloat(ambilTextCellDariDataTableCell(rowData[16]));
        });

        tulisFooterTotalPenjualanSetting(totalJumlah, totalUmp, totalPiutang, totalDpp, totalPpn);
    }

    function ambilIdDariBarisPenjualanSetting(tr) {
        if (!tr) {
            return 0;
        }
        var rawId = tr.getAttribute('data-penjualan-id') || tr.getAttribute('data-row-id');
        if (!rawId) {
            var span = tr.querySelector('.export-row-id');
            if (span) {
                rawId = span.textContent;
            }
        }
        var id = parseInt(rawId, 10);
        return (!isNaN(id) && id > 0) ? id : 0;
    }

    function kumpulkanIdArrayPenjualanSettingDariDom() {
        var ids = [];
        var tbody = document.querySelector(TABLE_SETTING_KODE_AKUN_PENJUALAN + ' tbody');
        if (!tbody) {
            return ids;
        }
        Array.prototype.forEach.call(tbody.querySelectorAll('tr.row-penjualan-data'), function(tr) {
            var id = ambilIdDariBarisPenjualanSetting(tr);
            if (id > 0) {
                ids.push(id);
            }
        });
        return ids;
    }

    function kumpulkanIdArrayPenjualanSettingDariHidden() {
        var ids = [];
        var fallback = document.getElementById('excel-export-ids');
        if (!fallback || !fallback.value) {
            return ids;
        }
        fallback.value.split(',').forEach(function(v) {
            var id = parseInt(v, 10);
            if (!isNaN(id) && id > 0) {
                ids.push(id);
            }
        });
        return ids;
    }

    function dedupeIdsPenjualanSetting(ids) {
        var seen = {};
        return ids.filter(function(id) {
            if (seen[id]) {
                return false;
            }
            seen[id] = true;
            return true;
        });
    }

    function kumpulkanVisibleIdsSettingKodeAkunPenjualan() {
        var ids = [];

        if (isDataTableSettingKodeAkunPenjualanAktif()) {
            var table = jQuery(TABLE_SETTING_KODE_AKUN_PENJUALAN).DataTable();
            table.rows({ search: 'applied', order: 'applied', page: 'all' }).every(function() {
                var id = ambilIdDariBarisPenjualanSetting(this.node());
                if (id > 0) {
                    ids.push(id);
                }
            });
        }

        if (!ids.length) {
            ids = kumpulkanIdArrayPenjualanSettingDariDom();
        }
        if (!ids.length) {
            ids = kumpulkanIdArrayPenjualanSettingDariHidden();
        }

        return dedupeIdsPenjualanSetting(ids).join(',');
    }

    function ambilSearchFilterAwalSettingKodeAkunPenjualan() {
        var val = (initialSearchFilterSettingKodeAkunPenjualan || '').trim();
        if (val) {
            return val;
        }
        try {
            val = (window.localStorage.getItem('setting_kode_akun_penjualan_search_filter') || '').trim();
        } catch (e) {
            val = '';
        }
        return val;
    }

    function sisipkanSearchFilterKeUrl(url, searchFilter) {
        if (!searchFilter) {
            return url;
        }
        var hash = '';
        var hashPos = url.indexOf('#');
        if (hashPos >= 0) {
            hash = url.substring(hashPos);
            url = url.substring(0, hashPos);
        }
        url = url.replace(/([?&])search_filter=[^&#]*/g, '').replace(/[?&]$/, '');
        var separator = url.indexOf('?') >= 0 ? '&' : '?';
        return url + separator + 'search_filter=' + encodeURIComponent(searchFilter) + hash;
    }

    function updateLinkKodeAkunDenganFilterPenjualan() {
        var searchFilter = '';
        if (isDataTableSettingKodeAkunPenjualanAktif()) {
            searchFilter = jQuery(TABLE_SETTING_KODE_AKUN_PENJUALAN).DataTable().search() || '';
            try {
                window.localStorage.setItem('setting_kode_akun_penjualan_search_filter', searchFilter);
            } catch (e) {}
        }
        jQuery('a[href*="/Tbl_penjualan/input_kode_akun/"], a[href*="/tbl_penjualan/input_kode_akun/"], a[href*="/Tbl_penjualan/ubah_kode_akun/"], a[href*="/tbl_penjualan/ubah_kode_akun/"]').each(function() {
            var hrefAwal = jQuery(this).attr('data-href-awal-kode-akun');
            if (!hrefAwal) {
                hrefAwal = jQuery(this).attr('href') || '';
                jQuery(this).attr('data-href-awal-kode-akun', hrefAwal);
            }
            if (!hrefAwal) {
                return;
            }
            jQuery(this).attr('href', sisipkanSearchFilterKeUrl(hrefAwal, searchFilter));
        });
    }

    function applySearchFilterKeDataTablePenjualan(searchValue) {
        var val = (searchValue || '').trim();
        if (!isDataTableSettingKodeAkunPenjualanAktif()) {
            return;
        }
        var table = jQuery(TABLE_SETTING_KODE_AKUN_PENJUALAN).DataTable();
        if (table.search() !== val) {
            table.search(val).draw();
        }
        try {
            window.localStorage.setItem('setting_kode_akun_penjualan_search_filter', val);
        } catch (e) {}
    }

    function registerSortTypeRupiahSettingKodeAkunPenjualan() {
        if (!window.jQuery || !jQuery.fn.dataTableExt || jQuery.fn.dataTableExt.oSort['id-rupiah-asc']) {
            return;
        }
        var parseIdRupiah = function(val) {
            val = jQuery('<div>').html(val).text().trim();
            if (!val) {
                return 0;
            }
            return parseFloat(val.replace(/\./g, '').replace(',', '.')) || 0;
        };
        jQuery.extend(jQuery.fn.dataTableExt.oSort, {
            'id-rupiah-asc': function(a, b) {
                return parseIdRupiah(a) - parseIdRupiah(b);
            },
            'id-rupiah-desc': function(a, b) {
                return parseIdRupiah(b) - parseIdRupiah(a);
            }
        });
    }

    function initDataTableSettingKodeAkunPenjualan() {
        if (!window.jQuery || !jQuery.fn.DataTable) {
            return null;
        }
        var $table = jQuery(TABLE_SETTING_KODE_AKUN_PENJUALAN);
        if (!$table.length) {
            return null;
        }
        if (jQuery.fn.DataTable.isDataTable(TABLE_SETTING_KODE_AKUN_PENJUALAN)) {
            jQuery(TABLE_SETTING_KODE_AKUN_PENJUALAN).DataTable().destroy();
            $table = jQuery(TABLE_SETTING_KODE_AKUN_PENJUALAN);
        }

        registerSortTypeRupiahSettingKodeAkunPenjualan();

        var table = $table.DataTable({
            scrollX: true,
            scrollY: '700px',
            scrollCollapse: true,
            paging: true,
            ordering: true,
            order: [],
            orderMulti: true,
            orderFixed: {
                pre: [[3, 'asc'], [4, 'asc'], [1, 'asc']]
            },
            searching: true,
            info: true,
            lengthChange: true,
            autoWidth: false,
            deferRender: true,
            columnDefs: [
                { targets: '_all', orderable: true },
                { targets: 0, type: 'num' },
                { targets: 1, type: 'num' },
                { targets: [10, 11, 12, 13, 14, 15, 16], type: 'id-rupiah' }
            ],
            language: {
                search: 'Cari:',
                lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
                infoFiltered: '(difilter dari _MAX_ total data)',
                zeroRecords: 'Data tidak ditemukan',
                paginate: {
                    first: 'Awal',
                    last: 'Akhir',
                    next: 'Berikutnya',
                    previous: 'Sebelumnya'
                }
            }
        });

        if (jQuery.fn.dataTable && jQuery.fn.dataTable.FixedColumns) {
            new jQuery.fn.dataTable.FixedColumns(table, {
                leftColumns: 3
            });
        }

        return table;
    }

    function bindEventDataTableSettingKodeAkunPenjualan(table) {
        if (!table) {
            return;
        }
        var initialSearch = ambilSearchFilterAwalSettingKodeAkunPenjualan();
        applySearchFilterKeDataTablePenjualan(initialSearch);
        updateTotalFooterDariDataTablePenjualanSetting();
        updateLinkKodeAkunDenganFilterPenjualan();

        table.off('draw.settingKodeAkunPenjualan').on('draw.settingKodeAkunPenjualan', function() {
            updateTotalFooterDariDataTablePenjualanSetting();
            updateLinkKodeAkunDenganFilterPenjualan();
        });
        table.off('search.settingKodeAkunPenjualan page.settingKodeAkunPenjualan length.settingKodeAkunPenjualan order.settingKodeAkunPenjualan')
            .on('search.settingKodeAkunPenjualan page.settingKodeAkunPenjualan length.settingKodeAkunPenjualan order.settingKodeAkunPenjualan', function() {
                updateTotalFooterDariDataTablePenjualanSetting();
                updateLinkKodeAkunDenganFilterPenjualan();
            });

        jQuery(document).off('keyup.settingKodeAkunPenjualanSearch input.settingKodeAkunPenjualanSearch', TABLE_SETTING_KODE_AKUN_PENJUALAN + '_wrapper .dataTables_filter input');
        jQuery(document).on('keyup.settingKodeAkunPenjualanSearch input.settingKodeAkunPenjualanSearch', TABLE_SETTING_KODE_AKUN_PENJUALAN + '_wrapper .dataTables_filter input', function() {
            setTimeout(function() {
                updateTotalFooterDariDataTablePenjualanSetting();
                updateLinkKodeAkunDenganFilterPenjualan();
            }, 0);
        });

        jQuery(document).off('click.settingKodeAkunPenjualanLink', 'a[href*="/Tbl_penjualan/input_kode_akun/"], a[href*="/tbl_penjualan/input_kode_akun/"], a[href*="/Tbl_penjualan/ubah_kode_akun/"], a[href*="/tbl_penjualan/ubah_kode_akun/"]');
        jQuery(document).on('click.settingKodeAkunPenjualanLink', 'a[href*="/Tbl_penjualan/input_kode_akun/"], a[href*="/tbl_penjualan/input_kode_akun/"], a[href*="/Tbl_penjualan/ubah_kode_akun/"], a[href*="/tbl_penjualan/ubah_kode_akun/"]', function(e) {
            var href = jQuery(this).attr('href') || '';
            if (!href) {
                return;
            }
            e.preventDefault();
            var searchFilter = isDataTableSettingKodeAkunPenjualanAktif() ? jQuery(TABLE_SETTING_KODE_AKUN_PENJUALAN).DataTable().search() : '';
            try {
                window.localStorage.setItem('setting_kode_akun_penjualan_search_filter', searchFilter);
            } catch (err) {}
            window.location.href = sisipkanSearchFilterKeUrl(href, searchFilter);
        });
    }

    function cetakExcelSettingKodeAkunPenjualan() {
        var ids = kumpulkanVisibleIdsSettingKodeAkunPenjualan();
        if (!ids) {
            alert('Tidak ada data untuk diekspor. Silakan cari data terlebih dahulu.');
            return;
        }
        var tglAwalEl = document.querySelector('#form-cari-setting-kode-akun-penjualan input[name="tgl_awal"]');
        var tglAkhirEl = document.querySelector('#form-cari-setting-kode-akun-penjualan input[name="tgl_akhir"]');
        var searchText = isDataTableSettingKodeAkunPenjualanAktif() ? jQuery(TABLE_SETTING_KODE_AKUN_PENJUALAN).DataTable().search() : '';
        var params = new URLSearchParams();
        params.set('ids', ids);
        params.set('date_awal_display', tglAwalEl ? tglAwalEl.value : '');
        params.set('date_akhir_display', tglAkhirEl ? tglAkhirEl.value : '');
        params.set('filter_text', searchText ? searchText : '-');
        window.location.href = '<?php echo site_url('Tbl_penjualan/excel_setting_kode_akun_penjualan'); ?>?' + params.toString();
    }

    (function() {
        var submitTimer = null;

        function submitCariSettingKodeAkunOtomatis() {
            clearTimeout(submitTimer);
            submitTimer = setTimeout(function() {
                var form = document.getElementById('form-cari-setting-kode-akun-penjualan');
                if (!form) {
                    return;
                }
                var tglAwal = form.querySelector('input[name="tgl_awal"]');
                var tglAkhir = form.querySelector('input[name="tgl_akhir"]');
                if (tglAwal && tglAkhir && tglAwal.value && tglAkhir.value) {
                    form.submit();
                }
            }, 400);
        }

        function initDatePickerSettingKodeAkunPenjualan() {
            if (!window.jQuery || !jQuery.fn.datetimepicker) {
                return;
            }
            var $ = jQuery;
            var opts = { format: 'D-M-YYYY' };
            if ($('#tgl_awal').length) {
                if ($('#tgl_awal').data('datetimepicker')) {
                    $('#tgl_awal').datetimepicker('destroy');
                }
                $('#tgl_awal').datetimepicker(opts);
            }
            if ($('#tgl_akhir').length) {
                if ($('#tgl_akhir').data('datetimepicker')) {
                    $('#tgl_akhir').datetimepicker('destroy');
                }
                $('#tgl_akhir').datetimepicker(opts);
            }
            $('#tgl_awal, #tgl_akhir').off('change.datetimepicker.settingPenjualan hide.datetimepicker.settingPenjualan');
            $('#tgl_awal, #tgl_akhir').on('change.datetimepicker.settingPenjualan hide.datetimepicker.settingPenjualan', submitCariSettingKodeAkunOtomatis);
        }

        function initHalamanSettingKodeAkunPenjualan() {
            initDatePickerSettingKodeAkunPenjualan();
            var form = document.getElementById('form-cari-setting-kode-akun-penjualan');
            if (form) {
                form.querySelectorAll('input[name="tgl_awal"], input[name="tgl_akhir"]').forEach(function(el) {
                    el.addEventListener('change', submitCariSettingKodeAkunOtomatis);
                });
            }
            var table = initDataTableSettingKodeAkunPenjualan();
            bindEventDataTableSettingKodeAkunPenjualan(table);
            setTimeout(updateTotalFooterDariDataTablePenjualanSetting, 200);
        }

        if (document.readyState === 'complete') {
            initHalamanSettingKodeAkunPenjualan();
        } else {
            window.addEventListener('load', initHalamanSettingKodeAkunPenjualan);
        }
    })();

    window.addEventListener('load', function() {
        if (!window.jQuery) {
            console.error('Compare Penjualan: jQuery belum dimuat. Muat ulang halaman.');
            return;
        }

        var urlCompareJurnalPenjualanRun = <?php echo json_encode($url_compare_jurnal_penjualan_run); ?>;
        var urlCompareJurnalPenjualanExcel = <?php echo json_encode($url_compare_jurnal_penjualan_excel); ?>;
        var urlCompareJurnalPenjualanImportCsv = <?php echo json_encode($url_compare_jurnal_penjualan_import_csv); ?>;
        var urlCompareJurnalPenjualanTabelList = <?php echo json_encode($url_compare_jurnal_penjualan_tabel_list); ?>;
        var urlCompareJurnalPenjualanTabelPreview = <?php echo json_encode($url_compare_jurnal_penjualan_tabel_preview); ?>;
        var comparePenjualanLastResult = null;
        var comparePenjualanDtInstances = {};
        var comparePenjualanTablesLoaded = false;
        var comparePenjualanCsvLastUpload = null;
        var comparePenjualanCsvImportBusy = false;

        function getBulanTahunComparePenjualanForCsv() {
            var now = new Date();
            var bulan = parseInt(jQuery('#compare_bulan_penjualan').val(), 10);
            var tahun = parseInt(jQuery('#compare_tahun_penjualan').val(), 10);
            if (!bulan || bulan < 1 || bulan > 12) {
                bulan = now.getMonth() + 1;
            }
            if (!tahun || tahun < 2000) {
                tahun = now.getFullYear();
            }
            return { bulan: bulan, tahun: tahun };
        }

        function parseComparePenjualanAjaxResponse(xhr) {
            if (!xhr) {
                return null;
            }
            if (xhr.responseJSON) {
                return xhr.responseJSON;
            }
            if (!xhr.responseText) {
                return null;
            }
            try {
                return JSON.parse(xhr.responseText);
            } catch (e) {
                return null;
            }
        }

        function showComparePenjualanCsvProcessing(fileName) {
            fileName = fileName || '';
            var safeName = fileName ? jQuery('<span>').text(fileName).html() : '';
            var ref = getBulanTahunComparePenjualanForCsv();
            jQuery('#compare-penjualan-csv-processing').removeClass('d-none');
            jQuery('#compare-penjualan-csv-processing-title').html('Memproses CSV' + (safeName ? ': <strong>' + safeName + '</strong>' : '') + '...');
            jQuery('#compare-penjualan-csv-processing-text').text(
                'Membuat tabel baru di database, menambahkan kolom id (AUTO_INCREMENT), tanggal (DATE akhir bulan '
                + ref.bulan + '/' + ref.tahun + '), keterangan, debet, kredit — lalu meng-upload data CSV...'
            );
            setComparePenjualanStatus('info', '<i class="fas fa-spinner fa-spin"></i> <strong>Memproses CSV'
                + (safeName ? ': ' + safeName : '')
                + '</strong> — generate tabel baru dan upload data...');
            if (!window.Swal) {
                return;
            }
            Swal.fire({
                title: 'Memproses CSV...',
                html: (safeName ? '<p class="mb-2"><strong>File:</strong> ' + safeName + '</p>' : '')
                    + '<p class="mb-0">Membuat tabel baru, menyesuaikan kolom, dan meng-upload data ke database.</p>',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: function() {
                    Swal.showLoading();
                }
            });
        }

        function escapeComparePenjualanHtml(text) {
            return jQuery('<div>').text(text == null ? '' : String(text)).html();
        }

        function formatComparePenjualanMessageHtml(message) {
            return escapeComparePenjualanHtml(message || '').replace(/\n/g, '<br/>');
        }

        function hideComparePenjualanCsvProcessing() {
            jQuery('#compare-penjualan-csv-processing').addClass('d-none');
            if (window.Swal) {
                Swal.close();
            }
        }

        function showComparePenjualanCsvError(message, title) {
            title = title || 'Import CSV gagal';
            hideComparePenjualanCsvProcessing();
            var htmlMsg = formatComparePenjualanMessageHtml(message);
            if (window.Swal) {
                Swal.fire({
                    icon: 'error',
                    title: title,
                    html: '<div style="text-align:left;font-size:14px;">' + htmlMsg + '</div>'
                });
            } else {
                alert((title ? title + '\n\n' : '') + (message || 'Import CSV gagal.'));
            }
        }

        function showComparePenjualanUploadSuccess(res) {
            var tableName = (res && res.table) ? res.table : '—';
            var rows = (res && res.rows) ? res.rows : 0;
            var msg = (res && res.message) ? String(res.message).replace(/\n/g, '<br/>') : 'Tabel baru berhasil dibuat.';
            var html = '<div style="text-align:center;">'
                + '<div style="font-size:48px;line-height:1;margin-bottom:8px;">✅</div>'
                + '<p style="margin:0 0 8px;font-weight:600;color:#155724;">Generate tabel berhasil!</p>'
                + '<p style="margin:0 0 4px;"><strong>Tabel:</strong> <code>' + jQuery('<span>').text(tableName).html() + '</code></p>'
                + '<p style="margin:0 0 12px;"><strong>Baris data:</strong> ' + rows + '</p>'
                + '<p style="margin:0 0 12px;font-size:13px;color:#495057;">Klik tombol <strong>Detail Tabel</strong> pada kotak info upload untuk melihat isi tabel.</p>'
                + '<div style="text-align:left;font-size:13px;color:#495057;background:#f8f9fa;border:1px solid #dee2e6;border-radius:6px;padding:10px;">' + msg + '</div>'
                + '</div>';

            if (window.Swal) {
                Swal.fire({
                    icon: 'success',
                    title: 'Tabel Baru Berhasil Dibuat',
                    html: html,
                    showConfirmButton: true,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#28a745',
                    timer: 3000,
                    timerProgressBar: true,
                    allowOutsideClick: true
                });
            }
        }

        function getTglAwalSettingKodeAkunPenjualan() {
            var el = document.querySelector('#form-cari-setting-kode-akun-penjualan input[name="tgl_awal"]');
            return el ? String(el.value || '').trim() : '';
        }

        function getBulanKeyComparePenjualan() {
            var bulan = parseInt(jQuery('#compare_bulan_penjualan').val(), 10);
            var tahun = parseInt(jQuery('#compare_tahun_penjualan').val(), 10);
            if (!bulan || !tahun) return '';
            return tahun + '-' + String(bulan).padStart(2, '0');
        }

        function toggleComparePenjualanButton() {
            var show = getBulanKeyComparePenjualan() !== '' && (jQuery('#compare_tabel_penjualan').val() || '') !== '';
            jQuery('#btn-compare-jurnal-penjualan').toggleClass('d-none', !show);
            if (!show) {
                jQuery('#btn-compare-penjualan-excel-all').addClass('d-none');
            }
        }

        function toggleComparePenjualanExcelAllButton(show) {
            jQuery('#btn-compare-penjualan-excel-all').toggleClass('d-none', !show);
        }

        function setComparePenjualanStatus(type, html) {
            var $el = jQuery('#compare-penjualan-status');
            $el.removeClass('alert-info alert-success alert-danger alert-warning');
            $el.addClass(type === 'success' ? 'alert-success' : (type === 'danger' ? 'alert-danger' : (type === 'warning' ? 'alert-warning' : 'alert-info')));
            $el.html(html);
        }

        function updateComparePenjualanInfoRingkas(res) {
            res = res || comparePenjualanLastResult || {};
            var stats = res.stats || {};
            jQuery('#compare-penjualan-label-bulan').text(res.bulan_label || getBulanKeyComparePenjualan() || '—');
            jQuery('#compare-penjualan-label-tabel').text(res.table || jQuery('#compare_tabel_penjualan').val() || '—');
            jQuery('#compare-penjualan-count-data-manual').text(typeof stats.data_manual !== 'undefined' ? stats.data_manual : '—');
            jQuery('#compare-penjualan-count-data-online').text(typeof stats.data_online !== 'undefined' ? stats.data_online : '—');
            jQuery('#compare-penjualan-count-manual').text(typeof stats.manual_tidak_di_online !== 'undefined' ? stats.manual_tidak_di_online : '—');
            jQuery('#compare-penjualan-count-online').text(typeof stats.online_tidak_di_manual !== 'undefined' ? stats.online_tidak_di_manual : '—');
            jQuery('#compare-penjualan-count-tanpa-kode').text(typeof stats.online_tanpa_kode_rekening !== 'undefined' ? stats.online_tanpa_kode_rekening : '—');
            jQuery('#compare-penjualan-badge-data-manual').text(stats.data_manual || 0);
            jQuery('#compare-penjualan-badge-data-online').text(stats.data_online || 0);
            jQuery('#compare-penjualan-badge-manual').text(stats.manual_tidak_di_online || 0);
            jQuery('#compare-penjualan-badge-online').text(stats.online_tidak_di_manual || 0);
            jQuery('#compare-penjualan-badge-tanpa-kode').text(stats.online_tanpa_kode_rekening || 0);
            jQuery('#compare-stat-data-manual').text(stats.data_manual || 0);
            jQuery('#compare-stat-data-online').text(stats.data_online || 0);
            jQuery('#compare-stat-manual-miss').text(stats.manual_tidak_di_online || 0);
            jQuery('#compare-stat-online-miss').text(stats.online_tidak_di_manual || 0);
            jQuery('#compare-stat-tanpa-kode').text(stats.online_tanpa_kode_rekening || 0);
        }

        function toggleComparePenjualanResultsPanel(show) {
            jQuery('#compare-penjualan-results-panel').toggleClass('d-none', !show);
        }

        function parseComparePenjualanAmount(val) {
            if (val === null || val === undefined || val === '') {
                return 0;
            }
            var text = String(val);
            if (text.indexOf('<') >= 0) {
                text = jQuery('<div>').html(text).text();
            }
            text = text.replace(/\./g, '').replace(',', '.').replace(/[^0-9.-]/g, '');
            var n = parseFloat(text);
            return isNaN(n) ? 0 : n;
        }

        function formatComparePenjualanAmountCell(val, type) {
            var num = parseComparePenjualanAmount(val);
            if (!val || num === 0) {
                return '<span class="text-amount text-amount-empty">—</span>';
            }
            var cls = type === 'debet' ? 'text-amount-debet' : 'text-amount-kredit';
            return '<span class="text-amount ' + cls + '">' + jQuery('<span>').text(String(val)).html() + '</span>';
        }

        function updateComparePenjualanTableFooter($table, items) {
            var totalDebet = 0;
            var totalKredit = 0;
            (items || []).forEach(function(it) {
                totalDebet += parseComparePenjualanAmount(it.debet);
                totalKredit += parseComparePenjualanAmount(it.kredit);
            });
            var debetText = totalDebet > 0 ? formatRupiahIDR(totalDebet) : '—';
            var kreditText = totalKredit > 0 ? formatRupiahIDR(totalKredit) : '—';
            $table.find('tfoot .compare-total-debet').text(debetText);
            $table.find('tfoot .compare-total-kredit').text(kreditText);
        }

        function registerComparePenjualanDtSortTypes() {
            if (!window.jQuery || !jQuery.fn.dataTableExt || jQuery.fn.dataTableExt.oSort['compare-amount-asc']) {
                return;
            }
            jQuery.extend(jQuery.fn.dataTableExt.oSort, {
                'compare-amount-asc': function(a, b) {
                    return parseComparePenjualanAmount(a) - parseComparePenjualanAmount(b);
                },
                'compare-amount-desc': function(a, b) {
                    return parseComparePenjualanAmount(b) - parseComparePenjualanAmount(a);
                }
            });
        }

        function buildComparePenjualanRows(items) {
            return (items || []).map(function(it, i) {
                var kode = it.kode_rekening || '';
                var ket = it.keterangan || '';
                var cat = it.catatan || '';
                return [
                    i + 1,
                    it.tanggal || '',
                    kode ? '<span class="text-kode">' + jQuery('<span>').text(kode).html() + '</span>' : '',
                    ket ? '<span class="text-ket">' + jQuery('<span>').text(ket).html() + '</span>' : '',
                    formatComparePenjualanAmountCell(it.debet, 'debet'),
                    formatComparePenjualanAmountCell(it.kredit, 'kredit'),
                    cat ? '<span class="text-catatan">' + jQuery('<span>').text(cat).html() + '</span>' : ''
                ];
            });
        }

        function renderComparePenjualanTable(tableId, items) {
            var $table = jQuery(tableId);
            if (!$table.length) return;
            items = items || [];
            if (jQuery.fn.DataTable.isDataTable($table)) {
                $table.DataTable().clear().destroy();
            }
            $table.find('tbody').empty();
            registerComparePenjualanDtSortTypes();
            var rows = buildComparePenjualanRows(items);
            var dt = $table.DataTable({
                data: rows,
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                scrollX: true,
                scrollY: '320px',
                scrollCollapse: true,
                autoWidth: false,
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']],
                order: [[1, 'asc'], [2, 'asc']],
                columnDefs: [
                    { targets: 0, className: 'text-center', orderable: true, width: '42px' },
                    { targets: 1, className: 'text-nowrap', width: '95px' },
                    { targets: 2, className: 'text-kode', width: '110px' },
                    { targets: 3, className: 'text-ket', orderable: true },
                    { targets: [4, 5], className: 'text-right', type: 'compare-amount', width: '105px' },
                    { targets: 6, className: 'text-catatan', orderable: false }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.4/i18n/id.json',
                    emptyTable: 'Tidak ada data untuk ditampilkan',
                    zeroRecords: 'Tidak ada data yang cocok dengan pencarian'
                },
                drawCallback: function() {
                    updateComparePenjualanTableFooter($table, items);
                }
            });
            updateComparePenjualanTableFooter($table, items);
            comparePenjualanDtInstances[tableId] = dt;
            setTimeout(function() {
                if (dt && dt.columns) {
                    dt.columns.adjust();
                }
            }, 100);
        }

        function renderComparePenjualanAllTables(res) {
            renderComparePenjualanTable('#table-compare-penjualan-data-manual', res.data_manual || []);
            renderComparePenjualanTable('#table-compare-penjualan-data-online', res.data_online || []);
            renderComparePenjualanTable('#table-compare-penjualan-manual', res.manual_tidak_di_online || []);
            renderComparePenjualanTable('#table-compare-penjualan-online', res.online_tidak_di_manual || []);
            renderComparePenjualanTable('#table-compare-penjualan-tanpa-kode', res.online_tanpa_kode_rekening || []);
        }

        function fillComparePenjualanTableSelect(tables, selectTable) {
            var $sel = jQuery('#compare_tabel_penjualan');
            var cur = selectTable || $sel.val();
            $sel.find('option:not(:first)').remove();
            (tables || []).forEach(function(tbl) { $sel.append(jQuery('<option>', { value: tbl, text: tbl })); });
            if (cur) $sel.val(cur);
            toggleComparePenjualanButton();
        }

        function loadComparePenjualanTableList(force, selectTable) {
            if (comparePenjualanTablesLoaded && !force) {
                if (selectTable) jQuery('#compare_tabel_penjualan').val(selectTable);
                toggleComparePenjualanButton();
                return;
            }
            jQuery('#compare_tabel_penjualan').prop('disabled', true);
            jQuery.ajax({ url: urlCompareJurnalPenjualanTabelList, type: 'POST', dataType: 'json' })
                .done(function(res) {
                    if (!res || !res.ok) {
                        setComparePenjualanStatus('danger', (res && res.message) ? res.message : 'Gagal memuat daftar tabel.');
                        return;
                    }
                    fillComparePenjualanTableSelect(res.tables || [], selectTable);
                    comparePenjualanTablesLoaded = true;
                })
                .fail(function() { setComparePenjualanStatus('danger', 'Tidak dapat memuat daftar tabel dari server.'); })
                .always(function() { jQuery('#compare_tabel_penjualan').prop('disabled', false); toggleComparePenjualanButton(); });
        }

        function runCompareJurnalPenjualan() {
            var bulanKey = getBulanKeyComparePenjualan();
            var tabel = jQuery('#compare_tabel_penjualan').val() || '';
            if (!bulanKey || !tabel) {
                alert('Pilih bulan, tahun, dan tabel database.');
                return;
            }
            setComparePenjualanStatus('info', '<i class="fas fa-spinner fa-spin"></i> Membandingkan tabel <strong>' + tabel + '</strong> dengan buku_besar penjualan...');
            toggleComparePenjualanResultsPanel(false);
            toggleComparePenjualanExcelAllButton(false);
            jQuery.ajax({
                url: urlCompareJurnalPenjualanRun, type: 'POST', dataType: 'json',
                data: { bulan: bulanKey, bulan_num: jQuery('#compare_bulan_penjualan').val(), tahun: jQuery('#compare_tahun_penjualan').val(), tabel: tabel }
            }).done(function(res) {
                if (!res || !res.ok) {
                    toggleComparePenjualanExcelAllButton(false);
                    setComparePenjualanStatus('danger', (res && res.message) ? res.message : 'Compare gagal.');
                    return;
                }
                comparePenjualanLastResult = res;
                renderComparePenjualanAllTables(res);
                updateComparePenjualanInfoRingkas(res);
                toggleComparePenjualanExcelAllButton(true);
                toggleComparePenjualanResultsPanel(true);
                var s = res.stats || {};
                setComparePenjualanStatus('success', '<i class="fas fa-check-circle"></i> Compare selesai — lihat <strong>5 tabel</strong> di bawah. '
                    + 'Manual: <strong>' + (s.data_manual || 0) + '</strong>, '
                    + 'Online: <strong>' + (s.data_online || 0) + '</strong>, '
                    + 'Selisih manual: <strong>' + (s.manual_tidak_di_online || 0) + '</strong>, '
                    + 'Selisih online: <strong>' + (s.online_tidak_di_manual || 0) + '</strong>, '
                    + 'Tanpa kode: <strong>' + (s.online_tanpa_kode_rekening || 0) + '</strong>.');
                jQuery('html, body').animate({
                    scrollTop: jQuery('#compare-penjualan-results-panel').offset().top - 80
                }, 400);
                jQuery.each(comparePenjualanDtInstances, function(id, dt) {
                    if (dt && dt.columns) {
                        dt.columns.adjust();
                    }
                });
            }).fail(function() { setComparePenjualanStatus('danger', 'Tidak dapat menghubungi server.'); });
        }

        function resetComparePenjualanCsvInput() {
            comparePenjualanCsvImportBusy = false;
            jQuery('#compare_penjualan_csv_file').prop('disabled', false).val('');
            jQuery('#compare_penjualan_csv_file').next('.custom-file-label').text('Cari / pilih file CSV...');
        }

        function updateComparePenjualanCsvUploadInfo(data) {
            var $box = jQuery('#compare-penjualan-csv-upload-info');
            if (!data || !data.table) {
                comparePenjualanCsvLastUpload = null;
                $box.addClass('d-none');
                return;
            }
            comparePenjualanCsvLastUpload = data;
            jQuery('#compare-penjualan-csv-filename').text(data.file || '—');
            jQuery('#compare-penjualan-csv-tablename').text(data.table || '—');
            jQuery('#compare-penjualan-csv-rowcount').text(data.rows ? (' (' + data.rows + ' baris)') : '');
            $box.removeClass('d-none');
        }

        function startImportComparePenjualanCsv(file) {
            if (!file || comparePenjualanCsvImportBusy) {
                return;
            }

            var ext = (file.name || '').split('.').pop().toLowerCase();
            if (ext !== 'csv') {
                showComparePenjualanCsvError('File harus berformat .csv', 'Perhatian');
                resetComparePenjualanCsvInput();
                return;
            }

            var ref = getBulanTahunComparePenjualanForCsv();
            comparePenjualanCsvImportBusy = true;
            jQuery('#compare_penjualan_csv_file').prop('disabled', true);
            showComparePenjualanCsvProcessing(file.name || '');

            var formData = new FormData();
            formData.append('csv_file', file);
            formData.append('bulan_num', ref.bulan);
            formData.append('tahun', ref.tahun);
            formData.append('bulan', ref.tahun + '-' + String(ref.bulan).padStart(2, '0'));
            formData.append('tgl_awal', getTglAwalSettingKodeAkunPenjualan());

            jQuery.ajax({
                url: urlCompareJurnalPenjualanImportCsv,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).done(function(res) {
                hideComparePenjualanCsvProcessing();
                comparePenjualanCsvImportBusy = false;
                if (!res || !res.ok) {
                    showComparePenjualanCsvError((res && res.message) ? res.message : 'Gagal import CSV.', 'Import CSV gagal');
                    setComparePenjualanStatus('danger', (res && res.message) ? String(res.message).replace(/\n/g, '<br/>') : 'Gagal import CSV.');
                    resetComparePenjualanCsvInput();
                    return;
                }
                comparePenjualanTablesLoaded = false;
                loadComparePenjualanTableList(true, res.table || '');
                updateComparePenjualanCsvUploadInfo({ file: res.file || file.name, table: res.table || '', rows: res.rows || 0 });
                setComparePenjualanStatus('success', (res.message || 'Import berhasil.').replace(/\n/g, '<br/>'));
                showComparePenjualanUploadSuccess(res);
                resetComparePenjualanCsvInput();
            }).fail(function(xhr) {
                hideComparePenjualanCsvProcessing();
                comparePenjualanCsvImportBusy = false;
                var parsed = parseComparePenjualanAjaxResponse(xhr);
                var msg = (parsed && parsed.message)
                    ? parsed.message
                    : 'Import CSV gagal. Periksa koneksi server atau muat ulang halaman.';
                if (xhr && xhr.status) {
                    msg += ' (HTTP ' + xhr.status + ')';
                }
                setComparePenjualanStatus('danger', msg);
                showComparePenjualanCsvError(msg, 'Import CSV gagal');
                resetComparePenjualanCsvInput();
            });
        }

        function handleComparePenjualanCsvFileSelected(input) {
            if (!input) {
                return;
            }
            var file = (input.files && input.files[0]) ? input.files[0] : null;
            var label = file ? file.name : 'Cari / pilih file CSV...';
            jQuery(input).next('.custom-file-label').text(label);
            if (!file) {
                return;
            }
            startImportComparePenjualanCsv(file);
        }

        function exportComparePenjualanExcel(jenis) {
            var bulanKey = getBulanKeyComparePenjualan();
            var tabel = jQuery('#compare_tabel_penjualan').val() || '';
            if (!bulanKey || !tabel || !jenis) { alert('Jalankan compare terlebih dahulu.'); return; }
            var form = jQuery('<form>', { method: 'POST', action: urlCompareJurnalPenjualanExcel, target: '_blank' });
            form.append(jQuery('<input>', { type: 'hidden', name: 'bulan', value: bulanKey }));
            form.append(jQuery('<input>', { type: 'hidden', name: 'bulan_num', value: jQuery('#compare_bulan_penjualan').val() }));
            form.append(jQuery('<input>', { type: 'hidden', name: 'tahun', value: jQuery('#compare_tahun_penjualan').val() }));
            form.append(jQuery('<input>', { type: 'hidden', name: 'tabel', value: tabel }));
            form.append(jQuery('<input>', { type: 'hidden', name: 'jenis', value: jenis }));
            jQuery('body').append(form);
            form.submit();
            form.remove();
        }

        jQuery(document).on('change', '#compare_penjualan_csv_file', function() {
            handleComparePenjualanCsvFileSelected(this);
        });

        jQuery('#compare_bulan_penjualan, #compare_tahun_penjualan, #compare_tabel_penjualan').on('change', function() {
            toggleComparePenjualanButton();
            toggleComparePenjualanResultsPanel(false);
            toggleComparePenjualanExcelAllButton(false);
            updateComparePenjualanInfoRingkas({ bulan_label: getBulanKeyComparePenjualan(), table: jQuery('#compare_tabel_penjualan').val() });
        });

        jQuery(document).on('click', '#btn-compare-jurnal-penjualan', runCompareJurnalPenjualan);
        jQuery(document).on('click', '#btn-compare-penjualan-excel-all', function() {
            exportComparePenjualanExcel('semua');
        });
        jQuery(document).on('click', '.btn-compare-penjualan-excel', function() {
            exportComparePenjualanExcel(jQuery(this).attr('data-jenis') || '');
        });

        jQuery(document).on('click', '#btn-compare-penjualan-csv-cek-data', function() {
            var table = (comparePenjualanCsvLastUpload && comparePenjualanCsvLastUpload.table) ? comparePenjualanCsvLastUpload.table : (jQuery('#compare_tabel_penjualan').val() || '');
            if (!table) {
                if (window.Swal) {
                    Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Belum ada tabel CSV.' });
                } else {
                    alert('Belum ada tabel CSV.');
                }
                return;
            }
            jQuery('#modal-compare-penjualan-csv-preview').modal('show');
            jQuery.ajax({ url: urlCompareJurnalPenjualanTabelPreview, type: 'POST', dataType: 'json', data: { tabel: table, limit: 2000 } })
                .done(function(res) {
                    var cols = (res && res.columns) ? res.columns : [];
                    var rows = (res && res.rows) ? res.rows : [];
                    var $table = jQuery('#table-compare-penjualan-csv-preview');
                    if (jQuery.fn.DataTable.isDataTable($table)) $table.DataTable().destroy();
                    $table.find('thead tr').empty();
                    $table.find('tbody').empty();
                    cols.forEach(function(c) { $table.find('thead tr').append(jQuery('<th>').text(c)); });
                    var dtRows = rows.map(function(r) { return cols.map(function(c) { return (r && r[c] != null) ? String(r[c]) : ''; }); });
                    $table.DataTable({ data: dtRows, scrollX: true, pageLength: 25 });
                    jQuery('#compare-penjualan-csv-preview-meta').text('Tabel: ' + table + ' | Total: ' + ((res && res.total) ? res.total : rows.length));
                });
        });

        jQuery('#setting-kode-akun-penjualan-tabs a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
            if (jQuery(e.target).attr('href') === '#panel-compare-manual-online') {
                loadComparePenjualanTableList(false);
                setTimeout(function() {
                    jQuery.each(comparePenjualanDtInstances, function(id, dt) {
                        if (dt && dt.columns) {
                            dt.columns.adjust();
                        }
                    });
                }, 150);
            }
        });

        if (jQuery('#panel-compare-manual-online').hasClass('active') || jQuery('#panel-compare-manual-online').hasClass('show')) {
            loadComparePenjualanTableList(false);
        }
        toggleComparePenjualanButton();
    });
</script>