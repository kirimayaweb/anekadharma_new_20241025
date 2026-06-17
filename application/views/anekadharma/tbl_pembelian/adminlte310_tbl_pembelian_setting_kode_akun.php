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
        if (!empty($Tbl_pembelian_data)) {
            foreach ($Tbl_pembelian_data as $row_export) {
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

                        <div class="row">
                            <div class="col-md-2" text-align="left"><strong>SETTING KODE AKUN PEMBELIAN</strong></div>
                            <div class="col-md-8">
                                <?php $action_cari_setting_kode_akun = site_url('tbl_pembelian/cari_between_date_setting_kode_akun'); ?>
                                <form id="form-cari-setting-kode-akun" action="<?php echo $action_cari_setting_kode_akun; ?>" method="post">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="input-group date" id="tgl_awal" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#tgl_awal" name="tgl_awal" value="<?php echo $Get_date_awal; ?>" required autocomplete="off" />
                                                <div class="input-group-append" data-target="#tgl_awal" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-1 text-center align-self-center">s/d</div>
                                        <div class="col-md-4">
                                            <div class="input-group date" id="tgl_akhir" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#tgl_akhir" name="tgl_akhir" value="<?php echo $Get_date_akhir; ?>" required autocomplete="off" />
                                                <div class="input-group-append" data-target="#tgl_akhir" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-danger btn-block btn-flat"><i class="fa fa-search" aria-hidden="true"></i> Cari</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-2" text-align="right" align="right">
                                <input type="hidden" id="excel-export-ids" value="<?php echo htmlspecialchars($excel_export_ids_str, ENT_QUOTES, 'UTF-8'); ?>" />
                                <button type="button" class="btn btn-success btn-block" onclick="cetakExcelSettingKodeAkun(); return false;">
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Cetak ke Excel
                                </button>
                            </div>
                        </div>

                    </div>



                    <div class="card-body">

                        <table id="tglSPOPFreeze" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="text-align:center" width="10px">No</th>
                                    <th style="text-align:center">Kode Akun</th>
                                    <th style="text-align:center">Spop <br /> Tgl PO</th>
                                    <!-- <th style="text-align:center">Kode Akun</th> -->
                                    <th style="text-align:center">No. faktur/ kwitansi</th>
                                    <th style="text-align:center">Supplier</th>
                                    <th style="text-align:center">Kode Barang</th>
                                    <th style="text-align:center">Nama Barang</th>
                                    <th style="text-align:center">Jumlah</th>
                                    <th style="text-align:center">Satuan</th>
                                    <th style="text-align:center">Konsumen</th>
                                    <th style="text-align:center">Harga Satuan</th>
                                    <th style="text-align:center">Harga Total</th>
                                    <th style="text-align:center">Statuslu</th>
                                    <th style="text-align:center">Kas / Bank</th>
                                    <th style="text-align:center">Tgl Bayar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!isset($pengajuan_by_uuid_spop)) {
                                    $pengajuan_by_uuid_spop = array();
                                }
                                if (!isset($pengajuan_sum_by_uuid_spop)) {
                                    $pengajuan_sum_by_uuid_spop = array();
                                }
                                if (!function_exists('render_pengajuan_pembelian_cell')) {
                                    function render_pengajuan_pembelian_cell($uuid_spop, $compare_uuid_spop, $Total_per_SPOP, $list_spop_status_lu, $pengajuan_by_uuid_spop, $pengajuan_sum_by_uuid_spop)
                                    {
                                        $result_pengajuan_by_uuid_spop = isset($pengajuan_by_uuid_spop[$uuid_spop]) ? $pengajuan_by_uuid_spop[$uuid_spop] : array();
                                        $TOTAL_Nominal_pengajuan = isset($pengajuan_sum_by_uuid_spop[$uuid_spop]) ? $pengajuan_sum_by_uuid_spop[$uuid_spop] : 0;

                                        if ($result_pengajuan_by_uuid_spop) {
                                            $startx = 0;
                                            foreach ($result_pengajuan_by_uuid_spop as $list_data_pengajuan) {
                                                echo anchor(site_url('tbl_pembelian/cetak_pengajuan_bayar_per_spop/' . $list_data_pengajuan->uuid_pengajuan_bayar), '<i class="fa fa-pencil-square-o" aria-hidden="true">Cetak Pengajuan ' . ++$startx . '</i>', 'class="btn btn-success btn-xs" target="_blank"');
                                            }

                                            if ($TOTAL_Nominal_pengajuan < $Total_per_SPOP) {
                                                if ($list_spop_status_lu == "Hutang" or $list_spop_status_lu == "U") {
                                                    echo anchor(site_url('tbl_pembelian/create_pembayaran/' . $compare_uuid_spop . '/pembelian'), '<i class="fa fa-pencil-square-o" aria-hidden="true">Pengajuan Pembayaran</i>', 'class="btn btn-warning btn-xs"');
                                                }
                                            }
                                        } elseif ($list_spop_status_lu == "Hutang" or $list_spop_status_lu == "U") {
                                            echo anchor(site_url('tbl_pembelian/create_pembayaran/' . $compare_uuid_spop . '/pembelian'), '<i class="fa fa-pencil-square-o" aria-hidden="true">Pengajuan Pembayaran</i>', 'class="btn btn-warning btn-xs"');
                                        }
                                    }
                                }
                                if (!function_exists('url_input_kode_akun_pembelian')) {
                                    function url_input_kode_akun_pembelian($uuid_spop, $tgl_po)
                                    {
                                        $get_date = date('Y-m-d', strtotime($tgl_po));
                                        return site_url('tbl_pembelian/input_kode_akun/' . rawurlencode($uuid_spop) . '/' . $get_date);
                                    }
                                }
                                if (!function_exists('url_ubah_kode_akun_pembelian')) {
                                    function url_ubah_kode_akun_pembelian($uuid_spop, $tgl_po)
                                    {
                                        $get_date = date('Y-m-d', strtotime($tgl_po));
                                        return site_url('tbl_pembelian/ubah_kode_akun/' . rawurlencode($uuid_spop) . '/' . $get_date);
                                    }
                                }

                                $compare_spop = 0;
                                $compare_uuid_spop = 0;
                                $Total_per_SPOP = 0;
                                $TOTAL_LUNAS = 0;
                                $TOTAL_HUTANG = 0;
                                $list_spop_status_lu = "";
                                $x_button = 0;
                                foreach ($Tbl_pembelian_data as $list_data) {

                                    // $list_spop_status_lu = $list_data->statuslu; // untuk cek kondisi di baris terakhir (SPOP)
                                    // if (($compare_spop <> $list_data->spop) and ($start >= 1)) {
                                    if (($compare_uuid_spop <> $list_data->uuid_spop) and ($start >= 1)) {
                                        // Buat 1 baris untuk total dan background = KUNING
                                ?>

                                        <!-- BARIS TOTAL PER SPOP (BARIS KUNING) -->
                                        <tr>
                                            <td><?php
                                                echo ++$start;
                                                // echo "-compare : ";
                                                // echo $compare_spop;
                                                // echo "- spop : ";
                                                // echo $list_data->spop;
                                                // echo " ---- : ";
                                                // echo $list_spop_status_lu;
                                                // echo " ---- : ";
                                                // echo $list_data->statuslu;
                                                ?></td>
                                            <td>
                                                <?php
                                                // echo $compare_spop . " - " . $list_data->spop;
                                                // echo "... 1 TOTAL SPOP";

                                                // echo "baris x";
                                                // if ($x_button == 1) {
                                                // echo anchor(site_url('tbl_pembelian/update_per_spop/' . $list_data->spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH</i>', 'class="btn btn-warning btn-xs"');

                                                // echo anchor(site_url('tbl_pembelian/delete_per_spop/' . $list_data->spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">HAPUS</i>', 'class="btn btn-danger btn-xs"');
                                                // }
                                                ?>

                                            </td>
                                            <td style="background-color:yellow;" align="left">
                                                <?php
                                                // "<strong> SPOP: " . $list_data->spop . "</strong>"
                                                echo "<strong> SPOP: " . $compare_spop . "</strong>";
                                                // echo "<br/>";
                                                // echo date("d M Y", strtotime($list_data->tgl_po));


                                                ?>
                                            </td>
                                            <!-- <td><?php //echo "Kode Akun 1"; 
                                                        ?></td> -->
                                            <td style="background-color:yellow;" align="right"></td>
                                            <!-- <td></td> -->
                                            <!-- <td></td> -->
                                            <td style="background-color:yellow;" align="right"></td>
                                            <td style="background-color:yellow;" align="right"></td>
                                            <td style="background-color:yellow;" align="right"></td>
                                            <td style="background-color:yellow;" align="right"></td>
                                            <td style="background-color:yellow;" align="right"></td>
                                            <td style="background-color:yellow;" align="right"></td>
                                            <td style="background-color:yellow;" align="right"></td>
                                            <td style="background-color:yellow;" align="right">
                                                <?php
                                                // echo "<font color='red'><strong>" . nominal($Total_per_SPOP) . "</strong></font>"; 
                                                echo "<font color='red'><strong>" . number_format($Total_per_SPOP, 2, ',', '.')  . "</strong></font>";
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                render_pengajuan_pembelian_cell($compare_uuid_spop, $compare_uuid_spop, $Total_per_SPOP, $list_spop_status_lu, $pengajuan_by_uuid_spop, $pengajuan_sum_by_uuid_spop);
                                                $list_spop_status_lu = $list_data->statuslu; // untuk cek kondisi di baris terakhir (SPOP) ==> Ubah status_lu dengan status data record yang baru.
                                                ?>
                                            </td>
                                            <td></td>
                                            <td></td>

                                        </tr>
                                    <?php
                                    }
                                    ?>
                                    <tr data-row-id="<?php echo (int) $list_data->id; ?>">
                                        <?php
                                        if ($compare_uuid_spop == $list_data->uuid_spop) {
                                        ?>
                                            <td><?php echo ++$start ?></td>
                                            <td>

                                                <?php

                                                // echo $list_data->spop;
                                                // echo "..... test 2";
                                                if ($list_data->kode_akun) {
                                                    echo "Kode Akun: " . $list_data->kode_akun;
                                                    echo "<br/>";
                                                    echo anchor(url_ubah_kode_akun_pembelian($list_data->uuid_spop, $list_data->tgl_po), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH KODE AKUN</i>', 'class="btn btn-warning btn-xs"');
                                                } else {
                                                    echo anchor(url_input_kode_akun_pembelian($list_data->uuid_spop, $list_data->tgl_po), '<i class="fa fa-pencil-square-o" aria-hidden="true">INPUT KODE AKUN</i>', 'class="btn btn-danger btn-xs"');
                                                }



                                                if (($compare_uuid_spop == $list_data->uuid_spop) and $x_button == 1) {
                                                    // echo anchor(site_url('tbl_pembelian/update_per_spop/' . $list_data->spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH</i>', 'class="btn btn-warning btn-xs"');

                                                    // echo anchor(site_url('tbl_pembelian/delete_per_spop/' . $list_data->spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">HAPUS</i>', 'class="btn btn-danger btn-xs"');
                                                    $x_button_show = 1;
                                                    $x_button = $x_button + 1;

                                                    // echo "jghjghjghhhhh";
                                                } else {
                                                    // echo "oooooooooooo";
                                                    // echo date("d M Y", strtotime($list_data->tgl_po));
                                                    // echo "<br/>";

                                                    // User : Pembelian
                                                    // echo anchor(site_url('tbl_pembelian/create_add_uraian_update/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH</i>', 'class="btn btn-warning btn-xs"');

                                                    // echo " ";

                                                    // echo anchor(site_url('Tbl_pembelian/delete_per_spop/' . $list_data->uuid_spop), '<i class="fa fa-trash-o" aria-hidden="true">HAPUS SPOP</i>', 'class="btn btn-danger btn-sm" Delete', 'onclick="javasciprt: return confirm(\'Anda Yakin akan Menghapus data SPOP ini?\')"');

                                                    // User : Accounting

                                                    // echo "Kode Akun 2 barang ke 2 dst";


                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo "SPOP: " . $list_data->spop;
                                                echo "<br/>";
                                                echo date("d M Y", strtotime($list_data->tgl_po));
                                                ?>
                                            </td>
                                            <!-- <td><?php //echo "kode akun 3"; 
                                                        ?></td> -->
                                            <td align="center"><?php echo $list_data->nmrfakturkwitansi; ?></td>


                                            <td align="left"><?php echo $list_data->supplier_nama; ?></td>
                                            <!-- <td></td>
                                            <td></td> -->
                                        <?php
                                        } else {
                                            // SPOP baru , me NOL kan total SPOP
                                            $Total_per_SPOP = 0;
                                            $x_button = 0;
                                            $x_button_show = 0;
                                        ?>
                                            <td><?php echo ++$start ?></td>
                                            <td><?php



                                                if ($list_data->kode_akun) {
                                                    echo "Kode Akun: " . $list_data->kode_akun;
                                                    echo "<br/>";
                                                    echo anchor(url_ubah_kode_akun_pembelian($list_data->uuid_spop, $list_data->tgl_po), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH KODE AKUN</i>', 'class="btn btn-warning btn-xs"');
                                                } else {
                                                    echo anchor(url_input_kode_akun_pembelian($list_data->uuid_spop, $list_data->tgl_po), '<i class="fa fa-pencil-square-o" aria-hidden="true">INPUT KODE AKUN</i>', 'class="btn btn-danger btn-xs"');
                                                }

                                                // echo "<br/>";

                                                // User : Pembelian
                                                // echo anchor(site_url('Tbl_pembelian/create_add_uraian_update/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH</i>', 'class="btn btn-warning btn-xs"');

                                                // echo " ";

                                                // echo anchor(site_url('Tbl_pembelian/delete_per_spop/' . $list_data->uuid_spop), '<i class="fa fa-trash-o" aria-hidden="true">HAPUS SPOP</i>', 'class="btn btn-danger btn-sm" Delete', 'onclick="javasciprt: return confirm(\'Anda Yakin akan Menghapus data SPOP ini?\')"');

                                                // User : Accounting

                                                // echo "Kode akun 4 Barang Pertama";
                                                // Cek di record pertama dari spop, apakah field kode akun sudah ada isi ? , jika belum tampilkan tombol modal



                                                ?>


                                            </td>
                                            <td align="left">
                                                <?php
                                                echo "SPOP: " . $list_data->spop;
                                                echo "<br/>";
                                                // echo date("d M Y", strtotime($list_data->tgl_po));

                                                echo date("d M Y", strtotime($list_data->tgl_po));

                                                $x_button = $x_button + 1;


                                                // echo "  ";
                                                // if ($list_data->status_spop) {
                                                //     echo anchor(site_url('tbl_pembelian/update_status_per_spop/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">' . $list_data->status_spop . '</i>', 'class="btn btn-success btn-xs"');
                                                // } else {
                                                //     echo anchor(site_url('tbl_pembelian/update_status_per_spop/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">STATUS</i>', 'class="btn btn-danger btn-xs"');
                                                // }

                                                ?>
                                            </td>

                                            <!-- <td align="center"><?php //echo "kode AKun 5"; 
                                                                    ?></td> -->
                                            <td align="center"><?php echo $list_data->nmrfakturkwitansi; ?></td>

                                            <td align="left"><?php echo $list_data->supplier_nama; ?></td>

                                        <?php
                                        }
                                        ?>



                                        <td align="center"><?php echo $list_data->kode_barang; ?></td>
                                        <td align="left"><?php echo $list_data->uraian; ?></td>
                                        <td align="right"><?php echo nominal($list_data->jumlah); ?></td>
                                        <td align="left"><?php echo $list_data->satuan; ?></td>
                                        <td align="left"><?php echo $list_data->konsumen; ?></td>
                                        <td align="right">
                                            <?php

                                            echo number_format($list_data->harga_satuan, 2, ',', '.');

                                            ?>
                                        </td>
                                        <td align="right">
                                            <?php
                                            $total_per_uraian = $list_data->jumlah * $list_data->harga_satuan;

                                            echo number_format($total_per_uraian, 2, ',', '.');

                                            $Total_per_SPOP = $Total_per_SPOP + $total_per_uraian;

                                            ?>
                                        </td>
                                        <td align="center">
                                            <?php
                                            if ($list_data->statuslu == "U") {
                                                echo "<font color='red'>" . $list_data->statuslu . "</font>";
                                                $TOTAL_HUTANG = $TOTAL_HUTANG + $total_per_uraian;
                                            } else {
                                                echo $list_data->statuslu;
                                                $TOTAL_LUNAS = $TOTAL_LUNAS + $total_per_uraian;
                                            }


                                            ?>
                                        </td>

                                        <td align="center">
                                            <?php

                                            if ($list_data->statuslu == "Lunas"  or $list_data->statuslu == "L") {
                                                echo $list_data->kas_bank;
                                            }

                                            ?>
                                        </td>

                                        <td align="center">
                                            <?php


                                            if (date("Y", strtotime($this->input->post('tgl_po', TRUE))) < 2020) {
                                                echo "";
                                            } else {
                                                echo $list_data->tgl_bayar;
                                            }

                                            ?>
                                        </td>
                                        <?php
                                        $compare_spop = $list_data->spop;
                                        $compare_uuid_spop = $list_data->uuid_spop;
                                        $list_spop_status_lu = $list_data->statuslu;
                                        ?>
                                    </tr>
                                <?php
                                }
                                ?>

                                <!-- TOTAL SPOP AKHIR -->
                                <tr>
                                    <td><?php echo ++$start ?></td>
                                    <td>
                                        <?php
                                        // if ($x_button == 1) {
                                        //     echo anchor(site_url('tbl_pembelian/update_per_spop/' . $list_data->spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH</i>', 'class="btn btn-warning btn-xs"');

                                        //     echo anchor(site_url('tbl_pembelian/delete_per_spop/' . $list_data->spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">HAPUS</i>', 'class="btn btn-danger btn-xs"');
                                        // }
                                        ?>
                                    </td>
                                    <!-- <td></td> -->
                                    <!-- <td></td> -->
                                    <td style="background-color:yellow;" align="right">
                                        <?php
                                        echo "<strong> SPOP: " . $list_data->spop . "</strong>";
                                        echo "<br/>";
                                        // echo date("d M Y", strtotime($list_data->tgl_po));

                                        echo date("d M Y", strtotime($list_data->tgl_po));

                                        ?>


                                    </td>
                                    <!-- <td><?php //echo "Kode akun 6"; 
                                                ?></td> -->
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="background-color:yellow;" align="right">
                                        <?php
                                        // echo "<font color='red'><strong>" . nominal($Total_per_SPOP) . "</strong></font>";
                                        echo "<font color='red'><strong>" . number_format($Total_per_SPOP, 2, ',', '.') . "</strong></font>";
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        render_pengajuan_pembelian_cell($list_data->uuid_spop, $compare_uuid_spop, $Total_per_SPOP, $list_spop_status_lu, $pengajuan_by_uuid_spop, $pengajuan_sum_by_uuid_spop);
                                        ?>
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>

                            <!-- tfoot -->

                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <!-- <th></th> -->
                                    <!-- <th></th> -->
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th style="text-align:right">TOTAL LUNAS</th>
                                    <th style="text-align:right">
                                        <?php
                                        // echo nominal($TOTAL_LUNAS);
                                        echo number_format($TOTAL_LUNAS, 2, ',', '.');
                                        ?>
                                    </th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <!-- <th></th> -->
                                    <!-- <th></th> -->
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th style="text-align:right"><?php echo "<font color='red'>TOTAL HUTANG</font>"; ?></th>
                                    <th style="text-align:right">
                                        <?php
                                        // echo "<font color='red'>" . nominal($TOTAL_HUTANG) . "</font>"; 
                                        echo "<font color='red'>" . number_format($TOTAL_HUTANG, 2, ',', '.') . "</font>";
                                        ?>
                                    </th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>



                            <!-- end of tfoot -->


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
</style>
<script>
    var initialSearchFilterSettingKodeAkun = <?php echo json_encode($search_filter); ?>;

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

    function isStatusDataPembelian(text) {
        var status = String(text || '').trim().toUpperCase();
        return status === 'U' || status === 'L' || status === 'LUNAS' || status === 'HUTANG';
    }

    function tulisFooterTotalPembelian(totalLunas, totalHutang) {
        var totalLunasText = formatRupiahIDR(totalLunas);
        var totalHutangHtml = "<font color='red'>" + formatRupiahIDR(totalHutang) + "</font>";
        var $allRows = jQuery('.dataTables_scrollFoot tfoot tr, #tglSPOPFreeze tfoot tr');
        $allRows.each(function() {
            var $cells = jQuery(this).find('th,td');
            var rowText = jQuery(this).text().toUpperCase();
            if (rowText.indexOf('TOTAL LUNAS') !== -1 && $cells.length > 11) {
                $cells.eq(11).text(totalLunasText);
            }
            if (rowText.indexOf('TOTAL HUTANG') !== -1 && $cells.length > 11) {
                $cells.eq(11).html(totalHutangHtml);
            }
        });
    }

    function ambilTextCellDariDataTableCell(cellHtmlOrText) {
        return jQuery('<div>').html(cellHtmlOrText).text().trim();
    }

    function updateTotalFooterDariDataTable() {
        if (!window.jQuery || !jQuery.fn.DataTable || !jQuery.fn.DataTable.isDataTable('#tglSPOPFreeze')) {
            return;
        }
        var table = jQuery('#tglSPOPFreeze').DataTable();
        var totalLunas = 0;
        var totalHutang = 0;

        table.rows({
            search: 'applied'
        }).every(function() {
            var rowData = this.data();
            if (!rowData || rowData.length < 13) {
                return;
            }
            var statusText = ambilTextCellDariDataTableCell(rowData[12]);
            if (!isStatusDataPembelian(statusText)) {
                return;
            }
            var hargaTotal = parseRupiahToFloat(ambilTextCellDariDataTableCell(rowData[11]));
            if (statusText.toUpperCase() === 'U' || statusText.toUpperCase() === 'HUTANG') {
                totalHutang += hargaTotal;
            } else {
                totalLunas += hargaTotal;
            }
        });
        tulisFooterTotalPembelian(totalLunas, totalHutang);
    }

    function kumpulkanVisibleIdsSettingKodeAkun() {
        if (!window.jQuery || !jQuery.fn.DataTable || !jQuery.fn.DataTable.isDataTable('#tglSPOPFreeze')) {
            var fallback = document.getElementById('excel-export-ids');
            return fallback ? fallback.value : '';
        }
        var table = jQuery('#tglSPOPFreeze').DataTable();
        var ids = [];
        table.rows({
            search: 'applied'
        }).nodes().to$().each(function() {
            var rowId = jQuery(this).attr('data-row-id');
            if (rowId) {
                ids.push(parseInt(rowId, 10));
            }
        });
        return ids.join(',');
    }

    function simpanSearchFilterAktifSettingKodeAkun() {
        if (!window.jQuery || !jQuery.fn.DataTable || !jQuery.fn.DataTable.isDataTable('#tglSPOPFreeze')) {
            return '';
        }
        var val = jQuery('#tglSPOPFreeze').DataTable().search() || '';
        try {
            window.localStorage.setItem('setting_kode_akun_search_filter', val);
        } catch (e) {}
        return val;
    }

    function ambilSearchFilterAwalSettingKodeAkun() {
        var val = (initialSearchFilterSettingKodeAkun || '').trim();
        if (val) {
            return val;
        }
        try {
            val = (window.localStorage.getItem('setting_kode_akun_search_filter') || '').trim();
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
        var separator = url.indexOf('?') >= 0 ? '&' : '?';
        return url + separator + 'search_filter=' + encodeURIComponent(searchFilter) + hash;
    }

    function updateLinkKodeAkunDenganSearchFilter() {
        var searchFilter = simpanSearchFilterAktifSettingKodeAkun();
        jQuery('a[href*="/tbl_pembelian/input_kode_akun/"], a[href*="/Tbl_pembelian/input_kode_akun/"], a[href*="/tbl_pembelian/ubah_kode_akun/"], a[href*="/Tbl_pembelian/ubah_kode_akun/"]').each(function() {
            var hrefAwal = jQuery(this).attr('data-href-awal-kode-akun');
            if (!hrefAwal) {
                hrefAwal = jQuery(this).attr('href') || '';
                jQuery(this).attr('data-href-awal-kode-akun', hrefAwal);
            }
            if (!hrefAwal) {
                return;
            }
            var hrefFinal = hrefAwal.replace(/([?&])search_filter=[^&#]*/g, '').replace(/[?&]$/, '');
            hrefFinal = sisipkanSearchFilterKeUrl(hrefFinal, searchFilter);
            jQuery(this).attr('href', hrefFinal);
        });
    }

    function ambilSearchAktifDariDataTableAtauInput() {
        if (window.jQuery && jQuery.fn.DataTable && jQuery.fn.DataTable.isDataTable('#tglSPOPFreeze')) {
            return jQuery('#tglSPOPFreeze').DataTable().search() || '';
        }
        var filterInput = document.querySelector('div.dataTables_filter input');
        return filterInput ? (filterInput.value || '') : '';
    }

    function applySearchFilterKeDataTable(searchValue) {
        var val = (searchValue || '').trim();
        if (!window.jQuery || !jQuery.fn.DataTable || !jQuery.fn.DataTable.isDataTable('#tglSPOPFreeze')) {
            return;
        }
        var table = jQuery('#tglSPOPFreeze').DataTable();
        if (table.search() !== val) {
            table.search(val).draw();
        } else {
            var filterInput = document.querySelector('div.dataTables_filter input');
            if (filterInput) {
                filterInput.value = val;
            }
        }
        try {
            window.localStorage.setItem('setting_kode_akun_search_filter', val);
        } catch (e) {}
    }

    function cetakExcelSettingKodeAkun() {
        var ids = kumpulkanVisibleIdsSettingKodeAkun();
        if (!ids) {
            alert('Tidak ada data untuk diekspor. Silakan cari data terlebih dahulu.');
            return;
        }
        var tglAwalEl = document.querySelector('#form-cari-setting-kode-akun input[name="tgl_awal"]');
        var tglAkhirEl = document.querySelector('#form-cari-setting-kode-akun input[name="tgl_akhir"]');
        var searchText = '';
        if (window.jQuery && jQuery.fn.DataTable && jQuery.fn.DataTable.isDataTable('#tglSPOPFreeze')) {
            searchText = jQuery('#tglSPOPFreeze').DataTable().search() || '';
        }
        var params = new URLSearchParams();
        params.set('ids', ids);
        params.set('date_awal_display', tglAwalEl ? tglAwalEl.value : '');
        params.set('date_akhir_display', tglAkhirEl ? tglAkhirEl.value : '');
        params.set('filter_text', searchText ? searchText : '-');
        var url = '<?php echo site_url('Tbl_pembelian/excel_setting_kode_akun'); ?>?' + params.toString();
        window.location.href = url;
    }

    (function() {
        var submitTimer = null;

        function submitCariSettingKodeAkunOtomatis() {
            clearTimeout(submitTimer);
            submitTimer = setTimeout(function() {
                var form = document.getElementById('form-cari-setting-kode-akun');
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

        function initDatePickerSettingKodeAkun() {
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
            $('#tgl_awal, #tgl_akhir').off('change.datetimepicker.setting hide.datetimepicker.setting');
            $('#tgl_awal, #tgl_akhir').on('change.datetimepicker.setting hide.datetimepicker.setting', submitCariSettingKodeAkunOtomatis);
        }

        function initAutoCariSettingKodeAkun() {
            var form = document.getElementById('form-cari-setting-kode-akun');
            if (!form) {
                return;
            }
            initDatePickerSettingKodeAkun();
            form.querySelectorAll('input[name="tgl_awal"], input[name="tgl_akhir"]').forEach(function(el) {
                el.addEventListener('change', submitCariSettingKodeAkunOtomatis);
            });
            if (window.jQuery && jQuery.fn.DataTable && jQuery.fn.DataTable.isDataTable('#tglSPOPFreeze')) {
                var table = jQuery('#tglSPOPFreeze').DataTable();
                var initialSearch = ambilSearchFilterAwalSettingKodeAkun();
                applySearchFilterKeDataTable(initialSearch);
                updateTotalFooterDariDataTable();
                updateLinkKodeAkunDenganSearchFilter();
                table.off('draw.settingKodeAkun').on('draw.settingKodeAkun', function() {
                    updateTotalFooterDariDataTable();
                    updateLinkKodeAkunDenganSearchFilter();
                });
                table.off('search.settingKodeAkun order.settingKodeAkun page.settingKodeAkun')
                    .on('search.settingKodeAkun order.settingKodeAkun page.settingKodeAkun', function() {
                        updateTotalFooterDariDataTable();
                        updateLinkKodeAkunDenganSearchFilter();
                    });
                jQuery(document).off('click.settingKodeAkunLink', 'a[href*="/tbl_pembelian/input_kode_akun/"], a[href*="/Tbl_pembelian/input_kode_akun/"], a[href*="/tbl_pembelian/ubah_kode_akun/"], a[href*="/Tbl_pembelian/ubah_kode_akun/"]');
                jQuery(document).on('click.settingKodeAkunLink', 'a[href*="/tbl_pembelian/input_kode_akun/"], a[href*="/Tbl_pembelian/input_kode_akun/"], a[href*="/tbl_pembelian/ubah_kode_akun/"], a[href*="/Tbl_pembelian/ubah_kode_akun/"]', function(e) {
                    var href = jQuery(this).attr('href') || '';
                    if (!href) {
                        return;
                    }
                    e.preventDefault();
                    var searchFilter = ambilSearchAktifDariDataTableAtauInput();
                    try {
                        window.localStorage.setItem('setting_kode_akun_search_filter', searchFilter);
                    } catch (err) {}
                    var hrefFinal = href.replace(/([?&])search_filter=[^&#]*/g, '').replace(/[?&]$/, '');
                    hrefFinal = sisipkanSearchFilterKeUrl(hrefFinal, searchFilter);
                    window.location.href = hrefFinal;
                });
                jQuery(document).off('keyup.settingKodeAkunSearch input.settingKodeAkunSearch', 'div.dataTables_filter input');
                jQuery(document).on('keyup.settingKodeAkunSearch input.settingKodeAkunSearch', 'div.dataTables_filter input', function() {
                    setTimeout(function() {
                        updateTotalFooterDariDataTable();
                        updateLinkKodeAkunDenganSearchFilter();
                    }, 0);
                });
                setTimeout(updateTotalFooterDariDataTable, 200);
                setTimeout(updateLinkKodeAkunDenganSearchFilter, 200);
            }
        }

        if (document.readyState === 'complete') {
            initAutoCariSettingKodeAkun();
        } else {
            window.addEventListener('load', initAutoCariSettingKodeAkun);
        }
    })();
</script>