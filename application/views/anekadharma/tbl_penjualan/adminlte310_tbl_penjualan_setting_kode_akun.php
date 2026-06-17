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
</script>