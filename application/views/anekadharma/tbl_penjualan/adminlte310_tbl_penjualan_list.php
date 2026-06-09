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
        // echo $date_awal; 
        // echo "<br/>";

        if (date("Y", strtotime($date_awal)) < 2020) {
            $Get_date_awal = date("d-m-Y");
        } else {
            $Get_date_awal = date("d-m-Y", strtotime($date_awal));
        }

        // echo $Get_date_awal;
        // echo "<br/>";
        // echo "<br/>";


        // echo $date_akhir; 
        // echo "<br/>";

        if (date("Y", strtotime($date_akhir)) < 2020) {
            $Get_date_akhir = date("d-m-Y");
        } else {
            $Get_date_akhir = date("d-m-Y", strtotime($date_akhir));
        }

        // echo $Get_date_akhir;
        // echo "<br/>";
        // echo "<br/>";

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



        <!-- DATA PENJUALAN -->

        <div class="box box-warning box-solid">

            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <div class="row">
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="row">
                                    <!-- <div class="col-5" text-align="center"> <strong>DATA PENJUALAN</strong></div> -->
                                    <div class="col-12" text-align="center"> <strong><a href="<?php echo site_url('tbl_penjualan/create'); ?>" id="btn-input-penjualan-baru" class="btn btn-danger">Input PENJUALAN BARU</a></strong></div>

                                </div>


                            </div>
                            <div class="col-md-5">

                                <?php
                                // $action_cari_between_date="cari_between_date" ;
                                $action_cari_between_date = site_url('Tbl_penjualan/cari_between_date');

                                ?>

                                <form id="form-cari-penjualan" action="<?php echo $action_cari_between_date; ?>" method="post">
                                    <div class="row">

                                        <div class="col-md-4" text-align="right">
                                            <div class="input-group date" id="tgl_awal" name="tgl_awal" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#tgl_awal" id="tgl_awal" name="tgl_awal" value="<?php echo $Get_date_awal; ?>" required />
                                                <div class="input-group-append" data-target="#tgl_awal" data-toggle="datetimepicker">
                                                    <div class="input-group-text">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-1" text-align="center" align="center">s/d</div>

                                        <div class="col-md-4" text-align="left" align="left">
                                            <div class="input-group date" id="tgl_akhir" name="tgl_akhir" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#tgl_akhir" id="tgl_akhir" name="tgl_akhir" value="<?php echo $Get_date_akhir; ?>" required />
                                                <div class="input-group-append" data-target="#tgl_akhir" data-toggle="datetimepicker">
                                                    <div class="input-group-text">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3" text-align="left" align="left">
                                            <strong>
                                                <button type="submit" class="btn btn-danger btn-block btn-flat"><i class="fa fa-sign-in" aria-hidden="true"></i> Cari</button>
                                            </strong>
                                        </div>

                                    </div>
                                </form>

                            </div>

                            <div class="col-md-2">
                                <?php //echo anchor(site_url('tbl_penjualan/RekapPenjualanPerBarang'), 'Rekap Penjualan Per Barang', 'class="btn btn-success"'); ?>


                            </div>

                            <div class="col-md-2">
                                <?php //echo anchor(site_url('tbl_penjualan/RekapPenjualanPerKonsumen'), 'Rekap Penjualan Per Konsumen', 'class="btn btn-success"'); ?>
                                
                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-xl-select-unit">
                                    REKAP DATA
                                </button>

                            </div>

                            <div class="col-md-1">
                                <input type="hidden" id="excel-export-source" value="tbl_penjualan" />
                                <input type="hidden" id="excel-export-ids" value="<?php echo htmlspecialchars($excel_export_ids_str, ENT_QUOTES, 'UTF-8'); ?>" />
                                <button type="button" class="btn btn-success btn-block" onclick="cetakExcelPenjualan(); return false;">
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Cetak ke Excel (.xlsx)
                                </button>
                            </div>


                        </div>




                    </div>




                    <div class="card-body">

                        <table id="tglSPOPFreeze" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="text-align:center" width="10px">No</th>
                                    <!-- <th style="text-align:center" width="100px">Action</th> -->
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

                                    <!-- Colspan -->
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

                                    if (($start >= 1) and (($compare_nmr_kirim <> $list_data->nmrkirim)  or ($compare_tgl_jual <> $list_data->tgl_jual))) {
                                        // Buat 1 baris untuk total dan background = KUNING

                                ?>


                                        <!-- // Buat 1 baris untuk total dan background = KUNING -->
                                        <tr class="row-penjualan-subtotal">
                                            <!-- BARIS TOTAL -->
                                            <td><?php echo ++$start; ?></td>
                                            <td style="background-color:yellow;" align="right"><?php
                                                                                                // echo date("d M Y", strtotime($list_data->tgl_jual));
                                                                                                // echo $GET_tgl_jual;

                                                                                                echo "<font color='red'><strong>TOTAL</strong></font>"

                                                                                                ?>
                                            </td>

                                            <!-- Kolom Nomor Kirim -->
                                            <td style="background-color:yellow;" align="left"><?php echo "<font color='red'><strong>" . $compare_nmr_kirim . "</strong></font>"; ?></td>

                                            <!-- kolom Nomor pesan -->
                                            <td style="background-color:yellow;" align="left"><?php //echo  "<font color='red'><strong>" . $compare_nmr_pesan . "</strong></font>";  
                                                                                                ?></td>

                                            <td></td>
                                            <td></td>
                                            <td>
                                                <?php //echo $list_data->nama_barang; 
                                                ?>
                                            </td>
                                            <td>
                                                <?php //echo $list_data->unit; 
                                                ?>
                                            </td>
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

                                        <tr class="row-penjualan-data" data-penjualan-id="<?php echo (int) $list_data->id; ?>">

                                            <td><?php echo ++$start; ?></td>
                                            <td>
                                                <?php
                                                echo date("d M Y", strtotime($list_data->tgl_jual));
                                                // echo $GET_tgl_jual;

                                                echo "<br/>";

                                                $date_tgl_jual = date("Y-m-d", strtotime($list_data->tgl_jual));




                                                ?>
                                            </td>

                                            <!-- Kolom Nomor Kirim -->
                                            <td>
                                                <?php
                                                echo $list_data->nmrkirim;

                                                echo "<br/>";

                                                echo anchor(site_url('Tbl_penjualan/cetak_penjualan_per_uuid_penjualan/' . $list_data->uuid_penjualan . '/' . $date_tgl_jual . '/' . $list_data->nmrkirim), '<i class="fa fa-pencil-square-o" aria-hidden="true">Cetak </i>', 'class="btn btn-success btn-xs"  target="_blank"');

                                                // echo anchor(site_url('Tbl_penjualan/kasir_penjualan/' . $list_data->uuid_penjualan . '/' . $date_tgl_jual . '/' . $list_data->nmrkirim), '<i class="fa fa-pencil-square-o" aria-hidden="true">Tambah </i>', 'class="btn btn-danger btn-xs"  target="_blank"');

                                                echo anchor(site_url('tbl_penjualan/kasir_penjualan/' . $list_data->uuid_penjualan), '<i class="fa fa-pencil-square-o" aria-hidden="true">Ubah Data</i>', 'class="btn btn-warning btn-xs"  ');


                                                ?>
                                            </td>

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

                                        <tr class="row-penjualan-data" data-penjualan-id="<?php echo (int) $list_data->id; ?>">

                                            <td><?php echo ++$start; ?></td>
                                            <td>
                                                <?php
                                                echo date("d M Y", strtotime($list_data->tgl_jual));
                                                // echo $GET_tgl_jual;

                                                echo "<br/>";

                                                $date_tgl_jual = date("Y-m-d", strtotime($list_data->tgl_jual));





                                                ?>
                                            </td>

                                            <td>
                                                <?php
                                                echo $list_data->nmrkirim;
                                                echo "<br/>";

                                                echo anchor(site_url('Tbl_penjualan/cetak_penjualan_per_uuid_penjualan/' . $list_data->uuid_penjualan . '/' . $date_tgl_jual . '/' . $list_data->nmrkirim), '<i class="fa fa-pencil-square-o" aria-hidden="true">Cetak </i>', 'class="btn btn-success btn-xs"  target="_blank"');

                                                // echo anchor(site_url('Tbl_penjualan/kasir_penjualan/' . $list_data->uuid_penjualan . '/' . $date_tgl_jual . '/' . $list_data->nmrkirim), '<i class="fa fa-pencil-square-o" aria-hidden="true">Tambah </i>', 'class="btn btn-danger btn-xs"  target="_blank"');

                                                // echo "<br/>";

                                                echo anchor(site_url('tbl_penjualan/kasir_penjualan/' . $list_data->uuid_penjualan), '<i class="fa fa-pencil-square-o" aria-hidden="true">Ubah Data</i>', 'class="btn btn-warning btn-xs"  ');
                                                // echo "";
                                                //    HAPUS
                                                // echo anchor(site_url('tbl_penjualan/delete/' . $list_data->id), 'Hapus', 'onclick="javasciprt: return confirm(\'Anda Yakin Akan Menghapus Data Penjualan ini ?\')" ');
                                                ?>
                                            </td>
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





                                <!-- TOTAL nmrkirim AKHIR -->
                                <tr class="row-penjualan-subtotal">
                                    <td><?php echo ++$start; ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
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



<!-- TAMBAH BARANG MODAL EXTRA LARGE -->
<form action="<?php //echo $action_simpan_bahan; 
                ?>" method="post">
    <div class="modal fade" id="modal-xl-select-unit">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">REKAP DATA</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">


                        <p class="text-muted small mb-2" id="rekap-modal-periode-info">Periode mengikuti tanggal awal dan tanggal akhir di atas.</p>
                        <div class="row">
                            <div class="col-4">
                                <a href="#" class="btn btn-success btn-block btn-rekap-penjualan" data-field="nama_barang" target="_blank">Rekap Per Barang</a>
                            </div>
                            <div class="col-4">
                                <a href="#" class="btn btn-success btn-block btn-rekap-penjualan" data-field="konsumen_nama" target="_blank">Rekap Per Konsumen</a>
                            </div>
                            <div class="col-4">
                                <a href="#" class="btn btn-success btn-block btn-rekap-penjualan" data-field="unit" target="_blank">Rekap Per Unit</a>
                            </div>

                          

                        </div>



                    </div>

                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <!-- <button type="button" class="btn btn-primary">Simpan</button> -->
                    <!-- <button type="submit" class="btn btn-primary">Proses</button> -->
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</form>
<!-- END OF MODAL EXTRA LARGE -->




<script>
    function isDataTablePenjualanAktif() {
        return !!(window.jQuery && jQuery.fn.DataTable && jQuery.fn.DataTable.isDataTable('#tglSPOPFreeze'));
    }

    function ambilIdDariBarisPenjualan(tr) {
        if (!tr) {
            return 0;
        }
        var rawId = tr.getAttribute('data-penjualan-id');
        if (!rawId && tr.id) {
            var clone = document.getElementById(tr.id);
            if (clone) {
                rawId = clone.getAttribute('data-penjualan-id');
            }
        }
        var id = parseInt(rawId, 10);
        return (!isNaN(id) && id > 0) ? id : 0;
    }

    function kumpulkanIdPenjualanDariDataTable() {
        var ids = [];
        if (!isDataTablePenjualanAktif()) {
            return ids;
        }

        var table = jQuery('#tglSPOPFreeze').DataTable();
        var nodes = table.rows({ search: 'applied', order: 'applied', page: 'all' }).nodes();
        for (var i = 0; i < nodes.length; i++) {
            var tr = nodes[i];
            var id = ambilIdDariBarisPenjualan(tr);
            if (id > 0) {
                ids.push(id);
            }
        }

        if (!ids.length) {
            table.rows({ search: 'applied', order: 'applied', page: 'all' }).every(function() {
                var id = ambilIdDariBarisPenjualan(this.node());
                if (id > 0) {
                    ids.push(id);
                }
            });
        }

        return ids;
    }

    function kumpulkanIdPenjualanDariDomUrutanTabel() {
        var ids = [];
        var tbody = document.querySelector('#tglSPOPFreeze tbody');
        if (!tbody) {
            return ids;
        }
        Array.prototype.forEach.call(tbody.querySelectorAll('tr.row-penjualan-data'), function(tr) {
            var id = ambilIdDariBarisPenjualan(tr);
            if (id > 0) {
                ids.push(id);
            }
        });
        return ids;
    }

    function cetakExcelPenjualan() {
        var tglAwalEl = document.querySelector('#form-cari-penjualan input[name="tgl_awal"]');
        var tglAkhirEl = document.querySelector('#form-cari-penjualan input[name="tgl_akhir"]');
        var tglAwal = tglAwalEl ? tglAwalEl.value : '';
        var tglAkhir = tglAkhirEl ? tglAkhirEl.value : '';
        if (!tglAwal || !tglAkhir) {
            alert('Pilih tanggal awal dan tanggal akhir terlebih dahulu.');
            return;
        }

        var ids = kumpulkanIdPenjualanDariDataTable();
        if (!ids.length && !isDataTablePenjualanAktif()) {
            var idsEl = document.getElementById('excel-export-ids');
            if (idsEl && idsEl.value) {
                ids = idsEl.value.split(',').map(function(v) {
                    return parseInt(v, 10);
                }).filter(function(v) {
                    return !isNaN(v) && v > 0;
                });
            }
            if (!ids.length) {
                ids = kumpulkanIdPenjualanDariDomUrutanTabel();
            }
        }

        if (!ids.length) {
            alert('Tidak ada data penjualan untuk diekspor. Periksa filter/search DataTable atau rentang tanggal.');
            return;
        }

        var seenId = {};
        ids = ids.filter(function(id) {
            if (seenId[id]) {
                return false;
            }
            seenId[id] = true;
            return true;
        });

        var sourceEl = document.getElementById('excel-export-source');
        var source = sourceEl ? sourceEl.value : 'tbl_penjualan';
        var url = <?php echo json_encode(site_url('Tbl_penjualan/excel')); ?>
            + '?source=' + encodeURIComponent(source)
            + '&from_datatable=1'
            + '&ids=' + encodeURIComponent(ids.join(','))
            + '&tgl_awal=' + encodeURIComponent(tglAwal)
            + '&tgl_akhir=' + encodeURIComponent(tglAkhir);
        window.location.href = url;
    }

(function() {
    var baseRekapUrl = <?php echo json_encode(site_url('Tbl_penjualan/RekapData/')); ?>;

    function getTanggalFilterPenjualan() {
        var tglAwal = document.querySelector('#form-cari-penjualan input[name="tgl_awal"]');
        var tglAkhir = document.querySelector('#form-cari-penjualan input[name="tgl_akhir"]');
        return {
            awal: tglAwal ? tglAwal.value : '',
            akhir: tglAkhir ? tglAkhir.value : ''
        };
    }

    function buildUrlInputPenjualanBaru() {
        var baseCreate = <?php echo json_encode(site_url('tbl_penjualan/create')); ?>;
        var tgl = getTanggalFilterPenjualan();
        if (tgl.awal && tgl.akhir) {
            return baseCreate
                + '?tgl_awal=' + encodeURIComponent(tgl.awal)
                + '&tgl_akhir=' + encodeURIComponent(tgl.akhir);
        }
        return baseCreate;
    }

    function initLinkInputPenjualanBaru() {
        var btn = document.getElementById('btn-input-penjualan-baru');
        if (!btn) {
            return;
        }
        btn.href = buildUrlInputPenjualanBaru();
        btn.addEventListener('click', function(e) {
            btn.href = buildUrlInputPenjualanBaru();
            var tgl = getTanggalFilterPenjualan();
            if (!tgl.awal || !tgl.akhir) {
                e.preventDefault();
                alert('Pilih tanggal awal dan tanggal akhir terlebih dahulu.');
            }
        });
    }

    window.buildRekapPenjualanUrl = function(field) {
        var tgl = getTanggalFilterPenjualan();
        var url = baseRekapUrl + field;
        if (tgl.awal && tgl.akhir) {
            url += '?tgl_awal=' + encodeURIComponent(tgl.awal) + '&tgl_akhir=' + encodeURIComponent(tgl.akhir);
        }
        return url;
    };

    function updateRekapModalLinks() {
        var btnCreate = document.getElementById('btn-input-penjualan-baru');
        if (btnCreate && typeof buildUrlInputPenjualanBaru === 'function') {
            btnCreate.href = buildUrlInputPenjualanBaru();
        }

        var tgl = getTanggalFilterPenjualan();
        var info = document.getElementById('rekap-modal-periode-info');
        if (info) {
            if (tgl.awal && tgl.akhir) {
                info.textContent = 'Periode: ' + tgl.awal + ' s/d ' + tgl.akhir;
            } else {
                info.textContent = 'Pilih tanggal awal dan tanggal akhir terlebih dahulu.';
            }
        }
        document.querySelectorAll('.btn-rekap-penjualan').forEach(function(btn) {
            var field = btn.getAttribute('data-field');
            if (!field) {
                return;
            }
            if (tgl.awal && tgl.akhir) {
                btn.href = buildRekapPenjualanUrl(field);
                btn.classList.remove('disabled');
                btn.setAttribute('aria-disabled', 'false');
            } else {
                btn.href = '#';
                btn.classList.add('disabled');
                btn.setAttribute('aria-disabled', 'true');
            }
        });
    }

    document.querySelectorAll('.btn-rekap-penjualan').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            var tgl = getTanggalFilterPenjualan();
            if (!tgl.awal || !tgl.akhir) {
                e.preventDefault();
                alert('Pilih tanggal awal dan tanggal akhir terlebih dahulu.');
                return;
            }
            var field = btn.getAttribute('data-field');
            btn.href = buildRekapPenjualanUrl(field);
        });
    });

    if (window.jQuery) {
        jQuery('#modal-xl-select-unit').on('show.bs.modal', updateRekapModalLinks);
    }

    var submitTimer = null;
    function submitCariPenjualanOtomatis() {
        clearTimeout(submitTimer);
        submitTimer = setTimeout(function() {
            var form = document.getElementById('form-cari-penjualan');
            if (!form) {
                return;
            }
            var tgl = getTanggalFilterPenjualan();
            if (tgl.awal && tgl.akhir) {
                form.submit();
            }
        }, 400);
    }

    function initAutoCariPenjualan() {
        var form = document.getElementById('form-cari-penjualan');
        if (!form) {
            return;
        }
        form.querySelectorAll('input[name="tgl_awal"], input[name="tgl_akhir"]').forEach(function(el) {
            el.addEventListener('change', function() {
                updateRekapModalLinks();
                submitCariPenjualanOtomatis();
            });
        });
        if (window.jQuery) {
            jQuery('#tgl_awal, #tgl_akhir').on('change.datetimepicker hide.datetimepicker', function() {
                updateRekapModalLinks();
                submitCariPenjualanOtomatis();
            });
        }
        updateRekapModalLinks();
    }

    if (document.readyState === 'complete') {
        initAutoCariPenjualan();
        initLinkInputPenjualanBaru();
    } else {
        window.addEventListener('load', function() {
            initAutoCariPenjualan();
            initLinkInputPenjualanBaru();
        });
    }
})();
</script>