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
            $active_tab = 'setting';
        }
        $tab_setting_active = ($active_tab !== 'compare');
        $tab_compare_active = ($active_tab === 'compare');
        $url_compare_jurnal_pembelian_run = isset($url_compare_jurnal_pembelian_run)
            ? $url_compare_jurnal_pembelian_run
            : site_url('tbl_pembelian/ajax_compare_jurnal_pembelian_manual_online');
        $url_compare_jurnal_pembelian_excel = isset($url_compare_jurnal_pembelian_excel)
            ? $url_compare_jurnal_pembelian_excel
            : site_url('tbl_pembelian/excel_compare_jurnal_pembelian_manual_online');
        $url_compare_jurnal_pembelian_excel_all = isset($url_compare_jurnal_pembelian_excel_all)
            ? $url_compare_jurnal_pembelian_excel_all
            : site_url('tbl_pembelian/excel_compare_jurnal_pembelian_all');
        $url_compare_jurnal_pembelian_import_csv = isset($url_compare_jurnal_pembelian_import_csv)
            ? $url_compare_jurnal_pembelian_import_csv
            : site_url('tbl_pembelian/ajax_compare_import_csv_jurnal_pembelian');
        $url_compare_jurnal_pembelian_check_csv = isset($url_compare_jurnal_pembelian_check_csv)
            ? $url_compare_jurnal_pembelian_check_csv
            : site_url('tbl_pembelian/ajax_compare_check_csv_jurnal_pembelian');
        $url_compare_jurnal_pembelian_validate_csv = isset($url_compare_jurnal_pembelian_validate_csv)
            ? $url_compare_jurnal_pembelian_validate_csv
            : site_url('tbl_pembelian/ajax_compare_validate_csv_jurnal_pembelian');
        $url_compare_jurnal_pembelian_tabel_list = isset($url_compare_jurnal_pembelian_tabel_list)
            ? $url_compare_jurnal_pembelian_tabel_list
            : site_url('tbl_pembelian/ajax_compare_tabel_list_jurnal_pembelian');
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

                        <ul class="nav nav-tabs setting-kode-akun-pembelian-tabs" id="setting-kode-akun-pembelian-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link<?php echo $tab_setting_active ? ' active' : ''; ?>" id="tab-setting-kode" data-toggle="pill" href="#panel-setting-kode" role="tab" aria-controls="panel-setting-kode" aria-selected="<?php echo $tab_setting_active ? 'true' : 'false'; ?>">Setting kode</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link<?php echo $tab_compare_active ? ' active' : ''; ?>" id="tab-compare-manual-online" data-toggle="pill" href="#panel-compare-manual-online" role="tab" aria-controls="panel-compare-manual-online" aria-selected="<?php echo $tab_compare_active ? 'true' : 'false'; ?>">Compare manual-online</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="setting-kode-akun-pembelian-tabs-content">
                            <div class="tab-pane fade<?php echo $tab_setting_active ? ' show active' : ''; ?>" id="panel-setting-kode" role="tabpanel" aria-labelledby="tab-setting-kode">

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

                            </div><!-- /.tab-pane setting kode -->

                            <div class="tab-pane fade<?php echo $tab_compare_active ? ' show active' : ''; ?>" id="panel-compare-manual-online" role="tabpanel" aria-labelledby="tab-compare-manual-online">

                                <div class="row mb-2">
                                    <div class="col-md-12">
                                        <small class="text-muted d-block mb-2">
                                            Bandingkan <strong>tbl_pembelian</strong> (online: tgl_po, SPOP, supplier, jumlah × harga_satuan)
                                            dengan tabel manual (tanggal, SPOP, SUPPLIER, jumlah) per bulan terpilih.
                                        </small>
                                        <label for="compare_pembelian_csv_file" class="mb-1">Pilih file CSV ( untuk di upload ke database sistem / aplikasi menjadi tabel data )</label>
                                        <div class="d-flex flex-wrap align-items-end compare-csv-upload-row mb-3">
                                            <div class="custom-file custom-file-sm mb-0 compare-csv-file-wrap">
                                                <input type="file" class="custom-file-input" id="compare_pembelian_csv_file" accept=".csv,text/csv">
                                                <label class="custom-file-label" for="compare_pembelian_csv_file" data-browse="Pilih">Cari / pilih file CSV...</label>
                                            </div>
                                        </div>
                                        <div id="compare-pembelian-csv-upload-info" class="alert alert-light border py-2 d-none mb-3">
                                            <div class="small mb-1">
                                                <span class="text-muted">File:</span>
                                                <strong id="compare-pembelian-csv-filename">—</strong>
                                            </div>
                                            <div class="small mb-1">
                                                <span class="text-muted">Tabel DB:</span>
                                                <strong id="compare-pembelian-csv-tablename" class="text-primary">—</strong>
                                                <span class="text-muted" id="compare-pembelian-csv-rowcount"></span>
                                            </div>
                                            <div class="small text-muted mb-2">
                                                Tabel memiliki kolom <strong>id</strong> (INTEGER AUTO_INCREMENT). Klik <strong>Cek Data</strong> untuk melihat isi tabel hasil upload CSV.
                                            </div>
                                            <button type="button" id="btn-compare-pembelian-csv-cek-data" class="btn btn-outline-info btn-sm">
                                                <i class="fas fa-search"></i> Cek Data
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-end compare-toolbar-row flex-wrap">
                                    <div class="col-auto mb-2">
                                        <label for="compare_bulan_pembelian" class="small mb-1">Bulan</label>
                                        <select id="compare_bulan_pembelian" class="form-control form-control-sm compare-toolbar-control">
                                            <?php foreach ($nama_bulan_id as $num => $label_bulan) { ?>
                                                <option value="<?php echo (int) $num; ?>"<?php echo ((int) $num === (int) $compare_bulan_num) ? ' selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($label_bulan, ENT_QUOTES, 'UTF-8'); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-auto mb-2">
                                        <label for="compare_tahun_pembelian" class="small mb-1">Tahun</label>
                                        <select id="compare_tahun_pembelian" class="form-control form-control-sm compare-toolbar-control">
                                            <?php for ($th = $gen_tahun_max; $th >= $gen_tahun_min; $th--) { ?>
                                                <option value="<?php echo (int) $th; ?>"<?php echo ((int) $th === (int) $compare_tahun_num) ? ' selected' : ''; ?>>
                                                    <?php echo (int) $th; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-auto mb-2">
                                        <label for="compare_tabel_pembelian" class="small mb-1">Pilih tabel database</label>
                                        <select id="compare_tabel_pembelian" class="form-control form-control-sm compare-toolbar-control compare-toolbar-tabel">
                                            <option value="">— Muat daftar tabel —</option>
                                        </select>
                                    </div>
                                    <div class="col-auto mb-2">
                                        <label class="small mb-1 d-block">&nbsp;</label>
                                        <div class="d-flex flex-wrap align-items-center">
                                            <button type="button" id="btn-compare-jurnal-pembelian" class="btn btn-info btn-sm d-none">
                                                <i class="fas fa-columns"></i> Compare
                                            </button>
                                            <button type="button" id="btn-compare-pembelian-excel-all" class="btn btn-success btn-sm d-none ml-2">
                                                <i class="fa fa-file-excel-o"></i> Cetak ke Excel
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-secondary py-2" id="compare-pembelian-info-ringkas">
                                    <strong>Bulan:</strong> <span id="compare-pembelian-label-bulan">—</span>
                                    &nbsp;|&nbsp; <strong>Tabel manual:</strong> <span id="compare-pembelian-label-tabel">—</span>
                                    &nbsp;|&nbsp; <strong>Manual tidak di online:</strong> <span id="compare-pembelian-count-manual">—</span>
                                    &nbsp;|&nbsp; <strong>Online tidak di manual:</strong> <span id="compare-pembelian-count-online">—</span>
                                    &nbsp;|&nbsp; <strong>Cocok:</strong> <span id="compare-pembelian-count-cocok">—</span>
                                    &nbsp;|&nbsp; <strong>Tidak lengkap manual:</strong> <span id="compare-pembelian-count-tidak-lengkap-manual">—</span>
                                    &nbsp;|&nbsp; <strong>Tidak lengkap online:</strong> <span id="compare-pembelian-count-tidak-lengkap-online">—</span>
                                </div>
                                <div class="alert alert-info py-2 mb-3" id="compare-pembelian-status">
                                    Pilih bulan, tahun, dan tabel manual — lalu klik <strong>Compare</strong>. Setelah compare selesai, tombol <strong>Cetak ke Excel</strong> akan muncul di sebelah Compare.
                                </div>

                                <h6 class="d-flex align-items-center flex-wrap mt-3">
                                    <span>1. Data Manual tidak ada di Online <span id="compare-pembelian-badge-manual" class="badge badge-warning">0</span></span>
                                    <button type="button" class="btn btn-xs btn-outline-success btn-compare-pembelian-excel ml-2 mb-1" data-jenis="hanya_manual"><i class="fa fa-file-excel-o"></i> Cetak ke Excel</button>
                                </h6>
                                <div class="compare-dt-wrap mb-4">
                                    <table id="table-compare-pembelian-manual" class="table table-bordered table-striped table-sm compare-dt" style="width:100%;font-size:13px;">
                                        <thead><tr>
                                            <th>No</th><th>Tanggal</th><th>SPOP</th><th>Supplier</th><th>Jumlah</th><th>Keterangan</th>
                                        </tr></thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <h6 class="d-flex align-items-center flex-wrap">
                                    <span>2. Data Cocok (Manual &amp; Online) <span id="compare-pembelian-badge-cocok" class="badge badge-success">0</span></span>
                                    <button type="button" class="btn btn-xs btn-outline-success btn-compare-pembelian-excel ml-2 mb-1" data-jenis="cocok"><i class="fa fa-file-excel-o"></i> Cetak ke Excel</button>
                                </h6>
                                <div class="compare-dt-wrap mb-4">
                                    <table id="table-compare-pembelian-cocok" class="table table-bordered table-striped table-sm compare-dt" style="width:100%;font-size:13px;">
                                        <thead><tr>
                                            <th>No</th><th>Tanggal</th><th>SPOP</th><th>Supplier</th><th>Jumlah</th><th>Keterangan</th>
                                        </tr></thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <h6 class="d-flex align-items-center flex-wrap">
                                    <span>3. Data Online tidak ada di Manual <span id="compare-pembelian-badge-online" class="badge badge-info">0</span></span>
                                    <button type="button" class="btn btn-xs btn-outline-success btn-compare-pembelian-excel ml-2 mb-1" data-jenis="hanya_online"><i class="fa fa-file-excel-o"></i> Cetak ke Excel</button>
                                </h6>
                                <div class="compare-dt-wrap mb-4">
                                    <table id="table-compare-pembelian-online" class="table table-bordered table-striped table-sm compare-dt" style="width:100%;font-size:13px;">
                                        <thead><tr>
                                            <th>No</th><th>Tanggal</th><th>SPOP</th><th>Supplier</th><th>Jumlah</th><th>Keterangan</th>
                                        </tr></thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <h6 class="d-flex align-items-center flex-wrap">
                                    <span>4. Data Manual tidak ada di Online <span id="compare-pembelian-badge-manual-duplikat" class="badge badge-warning">0</span></span>
                                </h6>
                                <div class="alert alert-secondary py-2 small mb-2">
                                    Ringkasan sama dengan tabel <strong>1</strong> — ditampilkan terpisah agar urutan nomor selaras dengan layout Excel.
                                </div>
                                <div class="compare-dt-wrap mb-4">
                                    <table id="table-compare-pembelian-manual-duplikat" class="table table-bordered table-striped table-sm compare-dt" style="width:100%;font-size:13px;">
                                        <thead><tr>
                                            <th>No</th><th>Tanggal</th><th>SPOP</th><th>Supplier</th><th>Jumlah</th><th>Keterangan</th>
                                        </tr></thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <h6 class="d-flex align-items-center flex-wrap">
                                    <span>5. Data Tidak Lengkap (data manual) <span id="compare-pembelian-badge-tidak-lengkap-manual" class="badge badge-danger">0</span></span>
                                    <button type="button" class="btn btn-xs btn-outline-success btn-compare-pembelian-excel ml-2 mb-1" data-jenis="tidak_lengkap_manual"><i class="fa fa-file-excel-o"></i> Cetak ke Excel</button>
                                </h6>
                                <div class="alert alert-warning py-2 small mb-2">
                                    Data manual (tabel CSV) dengan <strong>SPOP</strong>, <strong>Supplier</strong>, atau <strong>Jumlah</strong> kosong
                                    tidak ikut proses compare dan ditampilkan di tabel ini.
                                </div>
                                <div class="compare-dt-wrap mb-4">
                                    <table id="table-compare-pembelian-tidak-lengkap-manual" class="table table-bordered table-striped table-sm compare-dt" style="width:100%;font-size:13px;">
                                        <thead><tr>
                                            <th>No</th><th>Tanggal</th><th>SPOP</th><th>Supplier</th><th>Jumlah</th><th>Keterangan</th>
                                        </tr></thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <h6 class="d-flex align-items-center flex-wrap">
                                    <span>6. Data Tidak Lengkap (di data online) <span id="compare-pembelian-badge-tidak-lengkap-online" class="badge badge-danger">0</span></span>
                                    <button type="button" class="btn btn-xs btn-outline-success btn-compare-pembelian-excel ml-2 mb-1" data-jenis="tidak_lengkap_online"><i class="fa fa-file-excel-o"></i> Cetak ke Excel</button>
                                </h6>
                                <div class="alert alert-warning py-2 small mb-2">
                                    Data online (<strong>tbl_pembelian</strong>) dengan <strong>SPOP</strong>, <strong>Supplier</strong>, atau <strong>Jumlah</strong> kosong
                                    tidak ikut proses compare dan ditampilkan di tabel ini.
                                </div>
                                <div class="compare-dt-wrap mb-4">
                                    <table id="table-compare-pembelian-tidak-lengkap-online" class="table table-bordered table-striped table-sm compare-dt" style="width:100%;font-size:13px;">
                                        <thead><tr>
                                            <th>No</th><th>Tanggal</th><th>SPOP</th><th>Supplier</th><th>Jumlah</th><th>Keterangan</th>
                                        </tr></thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <div class="modal fade" id="modal-compare-pembelian-csv-preview" tabindex="-1" role="dialog" aria-labelledby="modalComparePembelianCsvPreviewLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document" style="max-width:95%;">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white py-2">
                                                <h5 class="modal-title" id="modalComparePembelianCsvPreviewLabel">Data Tabel CSV</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body pb-2">
                                                <p class="text-muted small mb-2" id="compare-pembelian-csv-preview-meta">
                                                    Memuat informasi tabel...
                                                </p>
                                                <div id="compare-pembelian-csv-preview-loading" class="text-center py-4 text-muted d-none">
                                                    <i class="fas fa-spinner fa-spin"></i> Memuat data tabel...
                                                </div>
                                                <div class="compare-pembelian-csv-preview-dt-wrap">
                                                    <table id="table-compare-pembelian-csv-preview" class="table table-bordered table-striped table-sm" style="width:100%;font-size:12px;">
                                                        <thead><tr></tr></thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="modal-footer py-2">
                                                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Tutup</button>
                                            </div>
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
    .nav-tabs.setting-kode-akun-pembelian-tabs {
        border-bottom: 2px solid #ffc107;
        margin-bottom: 15px;
    }
    .nav-tabs.setting-kode-akun-pembelian-tabs .nav-item {
        margin-bottom: -2px;
    }
    .nav-tabs.setting-kode-akun-pembelian-tabs .nav-link {
        background-color: #ffffff;
        border: 2px solid #ffc107;
        border-bottom: none;
        color: #888888;
        font-weight: normal;
        margin-right: 4px;
        border-radius: 4px 4px 0 0;
        opacity: 0.75;
    }
    .nav-tabs.setting-kode-akun-pembelian-tabs .nav-link:hover {
        background-color: #f8f9fa;
        color: #666666;
        opacity: 0.9;
    }
    .nav-tabs.setting-kode-akun-pembelian-tabs .nav-link.active {
        background-color: #007bff;
        border-color: #ffc107;
        color: #000000;
        font-weight: bold;
        opacity: 1;
    }
    .compare-dt-wrap {
        width: 100%;
        overflow: auto;
        max-height: 420px;
    }
    .compare-dt-wrap .dataTables_wrapper {
        width: 100%;
        font-size: 13px;
    }
    .compare-dt-wrap table.dataTable thead th,
    .compare-dt-wrap table.dataTable tbody td {
        white-space: nowrap;
        font-size: 13px;
        padding: 6px 8px;
    }
    .compare-toolbar-row .compare-toolbar-control {
        width: 110px;
        min-width: 110px;
    }
    #compare_tahun_pembelian.compare-toolbar-control {
        width: 88px;
        min-width: 88px;
    }
    #compare_tabel_pembelian.compare-toolbar-tabel {
        width: 360px;
        min-width: 270px;
        max-width: 480px;
    }
    .compare-csv-file-wrap {
        max-width: 520px;
        min-width: 280px;
        flex: 0 1 520px;
    }
    .custom-file-sm .custom-file-label,
    .custom-file-sm .custom-file-label::after {
        height: calc(1.8125rem + 2px);
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
    }
    .swal-pembelian-upload-success {
        border-radius: 12px !important;
        box-shadow: 0 12px 40px rgba(0, 123, 255, 0.25) !important;
    }
    .swal-pembelian-upload-success .swal2-title {
        font-size: 1.35rem !important;
    }
    .swal-pembelian-upload-success .swal2-html-container {
        font-size: 0.95rem !important;
        text-align: left !important;
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

    window.addEventListener('load', function() {
        if (!window.jQuery) {
            console.error('Compare Pembelian: jQuery belum dimuat. Muat ulang halaman.');
            return;
        }

        var urlCompareJurnalPembelianRun = <?php echo json_encode($url_compare_jurnal_pembelian_run); ?>;
        var urlCompareJurnalPembelianExcel = <?php echo json_encode($url_compare_jurnal_pembelian_excel); ?>;
        var urlCompareJurnalPembelianExcelAll = <?php echo json_encode($url_compare_jurnal_pembelian_excel_all); ?>;
        var urlCompareJurnalPembelianImportCsv = <?php echo json_encode($url_compare_jurnal_pembelian_import_csv); ?>;
        var urlCompareJurnalPembelianCheckCsv = <?php echo json_encode($url_compare_jurnal_pembelian_check_csv); ?>;
        var urlCompareJurnalPembelianValidateCsv = <?php echo json_encode($url_compare_jurnal_pembelian_validate_csv); ?>;
        var urlCompareJurnalPembelianTabelList = <?php echo json_encode($url_compare_jurnal_pembelian_tabel_list); ?>;
        var urlCompareJurnalPembelianTabelPreview = <?php echo json_encode($url_compare_jurnal_pembelian_tabel_preview); ?>;
        var comparePembelianLastResult = null;
        var comparePembelianDtInstances = {};
        var comparePembelianTablesLoaded = false;
        var comparePembelianCsvLastUpload = null;
        var comparePembelianCsvPreviewDt = null;
        var comparePembelianExcelAllReady = false;

        function toggleComparePembelianExcelAllButton(show) {
            comparePembelianExcelAllReady = !!show;
            jQuery('#btn-compare-pembelian-excel-all').toggleClass('d-none', !comparePembelianExcelAllReady);
        }

        function hideComparePembelianExcelAllButton() {
            toggleComparePembelianExcelAllButton(false);
        }

        function getTglAwalSettingKodeAkun() {
            var el = document.querySelector('#form-cari-setting-kode-akun input[name="tgl_awal"]');
            return el ? String(el.value || '').trim() : '';
        }

        function resetComparePembelianCsvInput() {
            jQuery('#compare_pembelian_csv_file').val('');
            jQuery('#compare_pembelian_csv_file').next('.custom-file-label').text('Cari / pilih file CSV...');
        }

        function parseComparePembelianAjaxResponse(xhr) {
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

        function showComparePembelianCsvProcessing(fileName) {
            fileName = fileName || '';
            var safeName = fileName ? jQuery('<span>').text(fileName).html() : '';
            setComparePembelianStatus('info', '<i class="fas fa-spinner fa-spin"></i> <strong>Memproses CSV'
                + (safeName ? ': ' + safeName : '')
                + '</strong> — membuat tabel baru, menyesuaikan kolom, dan meng-upload data...');
            if (!window.Swal) {
                return;
            }
            Swal.fire({
                title: 'Memproses CSV...',
                html: (safeName ? '<p class="mb-2"><strong>File:</strong> ' + safeName + '</p>' : '')
                    + '<p class="mb-0">Membuat tabel baru, menyesuaikan kolom, dan meng-upload data.</p>',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: function() {
                    Swal.showLoading();
                }
            });
        }

        function showComparePembelianCsvError(message, title) {
            title = title || 'Validasi CSV gagal';
            if (window.Swal) {
                Swal.fire({
                    icon: 'error',
                    title: title,
                    html: '<div style="text-align:left;white-space:pre-wrap;font-size:14px;">' + jQuery('<div>').text(message || '').html() + '</div>'
                });
            } else {
                alert((title ? title + '\n\n' : '') + (message || 'Validasi CSV gagal.'));
            }
        }

        function showComparePembelianUploadSuccess(res) {
            var tableName = (res && res.table) ? res.table : '—';
            var rows = (res && res.rows) ? res.rows : 0;
            var msg = (res && res.message) ? String(res.message).replace(/\n/g, '<br/>') : 'Tabel baru berhasil dibuat.';
            var html = '<div style="text-align:center;">'
                + '<div style="font-size:48px;line-height:1;margin-bottom:8px;">✅</div>'
                + '<p style="margin:0 0 8px;font-weight:600;color:#155724;">Upload CSV berhasil!</p>'
                + '<p style="margin:0 0 4px;"><strong>Tabel:</strong> <code>' + tableName + '</code></p>'
                + '<p style="margin:0 0 12px;"><strong>Baris data:</strong> ' + rows + '</p>'
                + '<p style="margin:0 0 12px;font-size:13px;color:#495057;">Klik tombol <strong>Cek Data</strong> pada kotak info upload untuk melihat preview isi tabel di database.</p>'
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
                    timer: 2000,
                    timerProgressBar: true,
                    allowOutsideClick: true,
                    customClass: {
                        popup: 'swal-pembelian-upload-success'
                    }
                });
            } else {
                alert('Upload berhasil. Tabel: ' + tableName);
            }
        }

        function startImportComparePembelianCsv(file) {
            if (!file) {
                return;
            }

            var ext = (file.name || '').split('.').pop().toLowerCase();
            if (ext !== 'csv') {
                showComparePembelianCsvError('File harus berformat .csv', 'Perhatian');
                resetComparePembelianCsvInput();
                return;
            }

            showComparePembelianCsvProcessing(file.name || '');

            var formData = new FormData();
            formData.append('csv_file', file);
            formData.append('bulan_num', jQuery('#compare_bulan_pembelian').val() || '');
            formData.append('tahun', jQuery('#compare_tahun_pembelian').val() || '');
            formData.append('tgl_awal', getTglAwalSettingKodeAkun());

            jQuery.ajax({
                url: urlCompareJurnalPembelianImportCsv,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).done(function(res) {
                if (window.Swal) {
                    Swal.close();
                }
                if (!res || !res.ok) {
                    showComparePembelianCsvError((res && res.message) ? res.message : 'Gagal import CSV.', 'Import CSV gagal');
                    resetComparePembelianCsvInput();
                    return;
                }

                comparePembelianTablesLoaded = false;
                loadComparePembelianTableList(true, res.table || '');
                updateComparePembelianCsvUploadInfo({
                    file: res.file || file.name,
                    table: res.table || '',
                    rows: res.rows || 0
                });
                setComparePembelianStatus('success', (res.message || 'Import CSV berhasil.').replace(/\n/g, '<br/>'));
                showComparePembelianUploadSuccess(res);
                resetComparePembelianCsvInput();
            }).fail(function(xhr) {
                if (window.Swal) {
                    Swal.close();
                }
                var parsed = parseComparePembelianAjaxResponse(xhr);
                var msg = (parsed && parsed.message)
                    ? parsed.message
                    : 'Import CSV gagal. Periksa koneksi server atau muat ulang halaman.';
                if (xhr && xhr.status) {
                    msg += ' (HTTP ' + xhr.status + ')';
                }
                setComparePembelianStatus('danger', msg);
                showComparePembelianCsvError(msg, 'Import CSV gagal');
                resetComparePembelianCsvInput();
            });
        }

        function getBulanKeyComparePembelian() {
            var bulan = parseInt(jQuery('#compare_bulan_pembelian').val(), 10);
            var tahun = parseInt(jQuery('#compare_tahun_pembelian').val(), 10);
            if (!bulan || !tahun) {
                return '';
            }
            return tahun + '-' + String(bulan).padStart(2, '0');
        }

        function toggleComparePembelianButton() {
            var show = getBulanKeyComparePembelian() !== '' && (jQuery('#compare_tabel_pembelian').val() || '') !== '';
            jQuery('#btn-compare-jurnal-pembelian').toggleClass('d-none', !show);
            if (!show) {
                hideComparePembelianExcelAllButton();
            }
        }

        function setComparePembelianStatus(type, html) {
            var $el = jQuery('#compare-pembelian-status');
            $el.removeClass('alert-info alert-success alert-danger alert-warning');
            $el.addClass(type === 'success' ? 'alert-success' : (type === 'danger' ? 'alert-danger' : (type === 'warning' ? 'alert-warning' : 'alert-info')));
            $el.html(html);
        }

        function updateComparePembelianInfoRingkas(res) {
            res = res || comparePembelianLastResult || {};
            var stats = res.stats || {};
            jQuery('#compare-pembelian-label-bulan').text(res.bulan_label || getBulanKeyComparePembelian() || '—');
            jQuery('#compare-pembelian-label-tabel').text(res.table || jQuery('#compare_tabel_pembelian').val() || '—');
            jQuery('#compare-pembelian-count-manual').text(typeof stats.hanya_manual !== 'undefined' ? stats.hanya_manual : '—');
            jQuery('#compare-pembelian-count-online').text(typeof stats.hanya_online !== 'undefined' ? stats.hanya_online : '—');
            jQuery('#compare-pembelian-count-cocok').text(typeof stats.cocok !== 'undefined' ? stats.cocok : '—');
            jQuery('#compare-pembelian-count-tidak-lengkap-manual').text(typeof stats.tidak_lengkap_manual !== 'undefined' ? stats.tidak_lengkap_manual : '—');
            jQuery('#compare-pembelian-count-tidak-lengkap-online').text(typeof stats.tidak_lengkap_online !== 'undefined' ? stats.tidak_lengkap_online : '—');
            jQuery('#compare-pembelian-badge-manual').text(typeof stats.hanya_manual !== 'undefined' ? stats.hanya_manual : 0);
            jQuery('#compare-pembelian-badge-online').text(typeof stats.hanya_online !== 'undefined' ? stats.hanya_online : 0);
            jQuery('#compare-pembelian-badge-cocok').text(typeof stats.cocok !== 'undefined' ? stats.cocok : 0);
            jQuery('#compare-pembelian-badge-tidak-lengkap-manual').text(typeof stats.tidak_lengkap_manual !== 'undefined' ? stats.tidak_lengkap_manual : 0);
            jQuery('#compare-pembelian-badge-tidak-lengkap-online').text(typeof stats.tidak_lengkap_online !== 'undefined' ? stats.tidak_lengkap_online : 0);
            jQuery('#compare-pembelian-badge-manual-duplikat').text(typeof stats.hanya_manual !== 'undefined' ? stats.hanya_manual : 0);
        }

        function fillComparePembelianTableSelect(tables, selectTable) {
            var $sel = jQuery('#compare_tabel_pembelian');
            var cur = selectTable || $sel.val();
            $sel.find('option:not(:first)').remove();
            (tables || []).forEach(function(tbl) {
                $sel.append(jQuery('<option>', { value: tbl, text: tbl }));
            });
            if (cur) {
                $sel.val(cur);
            }
            toggleComparePembelianButton();
        }

        function loadComparePembelianTableList(force, selectTable) {
            if (comparePembelianTablesLoaded && !force) {
                if (selectTable) {
                    jQuery('#compare_tabel_pembelian').val(selectTable);
                }
                toggleComparePembelianButton();
                return;
            }
            jQuery('#compare_tabel_pembelian').prop('disabled', true);
            jQuery.ajax({
                url: urlCompareJurnalPembelianTabelList,
                type: 'POST',
                dataType: 'json'
            }).done(function(res) {
                if (!res || !res.ok) {
                    setComparePembelianStatus('danger', (res && res.message) ? res.message : 'Gagal memuat daftar tabel.');
                    return;
                }
                fillComparePembelianTableSelect(res.tables || [], selectTable);
                comparePembelianTablesLoaded = true;
            }).fail(function() {
                setComparePembelianStatus('danger', 'Tidak dapat memuat daftar tabel dari server.');
            }).always(function() {
                jQuery('#compare_tabel_pembelian').prop('disabled', false);
                toggleComparePembelianButton();
            });
        }

        function buildComparePembelianRows(items) {
            return (items || []).map(function(it, i) {
                return [
                    i + 1,
                    it.tanggal || '',
                    it.spop || '',
                    it.supplier || '',
                    it.jumlah || '',
                    it.keterangan || ''
                ];
            });
        }

        function renderComparePembelianTable(tableId, items) {
            var $table = jQuery(tableId);
            if (!$table.length) {
                return;
            }
            if (jQuery.fn.DataTable.isDataTable($table)) {
                $table.DataTable().clear().destroy();
            }
            $table.find('tbody').empty();
            var rows = buildComparePembelianRows(items);
            var dt = $table.DataTable({
                data: rows,
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                scrollX: true,
                autoWidth: false,
                pageLength: 25,
                columnDefs: [
                    { targets: 5, className: 'text-wrap', width: '280px' }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.4/i18n/id.json'
                }
            });
            comparePembelianDtInstances[tableId] = dt;
        }

        function renderComparePembelianAllTables(res) {
            renderComparePembelianTable('#table-compare-pembelian-manual', res.hanya_manual || []);
            renderComparePembelianTable('#table-compare-pembelian-cocok', res.cocok || []);
            renderComparePembelianTable('#table-compare-pembelian-online', res.hanya_online || []);
            renderComparePembelianTable('#table-compare-pembelian-manual-duplikat', res.hanya_manual || []);
            renderComparePembelianTable('#table-compare-pembelian-tidak-lengkap-manual', res.tidak_lengkap_manual || []);
            renderComparePembelianTable('#table-compare-pembelian-tidak-lengkap-online', res.tidak_lengkap_online || []);
        }

        function runCompareJurnalPembelian() {
            var bulanKey = getBulanKeyComparePembelian();
            var tabel = jQuery('#compare_tabel_pembelian').val() || '';
            if (!bulanKey) {
                if (window.Swal) {
                    Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih bulan dan tahun.' });
                } else {
                    alert('Pilih bulan dan tahun.');
                }
                return;
            }
            if (!tabel) {
                if (window.Swal) {
                    Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih tabel database yang akan dibandingkan.' });
                } else {
                    alert('Pilih tabel database yang akan dibandingkan.');
                }
                return;
            }

            setComparePembelianStatus('info', '<i class="fas fa-spinner fa-spin"></i> Membandingkan tabel <strong>' + tabel + '</strong> dengan tbl_pembelian...');

            jQuery.ajax({
                url: urlCompareJurnalPembelianRun,
                type: 'POST',
                dataType: 'json',
                data: {
                    bulan: bulanKey,
                    bulan_num: jQuery('#compare_bulan_pembelian').val(),
                    tahun: jQuery('#compare_tahun_pembelian').val(),
                    tabel: tabel
                }
            }).done(function(res) {
                if (!res || !res.ok) {
                    hideComparePembelianExcelAllButton();
                    setComparePembelianStatus('danger', (res && res.message) ? res.message : 'Compare gagal.');
                    if (window.Swal) {
                        Swal.fire({ icon: 'error', title: 'Compare gagal', text: (res && res.message) ? res.message : 'Gagal compare.' });
                    }
                    return;
                }
                comparePembelianLastResult = res;
                renderComparePembelianAllTables(res);
                updateComparePembelianInfoRingkas(res);
                toggleComparePembelianExcelAllButton(true);
                var s = res.stats || {};
                setComparePembelianStatus('success', 'Compare selesai — Manual tidak di online: <strong>' + (s.hanya_manual || 0) + '</strong>, '
                    + 'Online tidak di manual: <strong>' + (s.hanya_online || 0) + '</strong>, '
                    + 'Cocok: <strong>' + (s.cocok || 0) + '</strong>, '
                    + 'Tidak lengkap manual: <strong>' + (s.tidak_lengkap_manual || 0) + '</strong>, '
                    + 'Tidak lengkap online: <strong>' + (s.tidak_lengkap_online || 0) + '</strong>. Tabel: <strong>' + (res.table || tabel) + '</strong>.');
            }).fail(function() {
                hideComparePembelianExcelAllButton();
                setComparePembelianStatus('danger', 'Tidak dapat menghubungi server.');
                if (window.Swal) {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat menghubungi server.' });
                }
            });
        }

        function updateComparePembelianCsvUploadInfo(data) {
            var $box = jQuery('#compare-pembelian-csv-upload-info');
            if (!data || !data.table) {
                comparePembelianCsvLastUpload = null;
                $box.addClass('d-none');
                jQuery('#compare-pembelian-csv-filename').text('—');
                jQuery('#compare-pembelian-csv-tablename').text('—');
                jQuery('#compare-pembelian-csv-rowcount').text('');
                return;
            }
            comparePembelianCsvLastUpload = {
                file: data.file || '',
                table: data.table || '',
                rows: data.rows || 0
            };
            jQuery('#compare-pembelian-csv-filename').text(comparePembelianCsvLastUpload.file || '—');
            jQuery('#compare-pembelian-csv-tablename').text(comparePembelianCsvLastUpload.table || '—');
            jQuery('#compare-pembelian-csv-rowcount').text(
                comparePembelianCsvLastUpload.rows ? (' (' + comparePembelianCsvLastUpload.rows + ' baris)') : ''
            );
            $box.removeClass('d-none');
        }

        function renderComparePembelianCsvPreviewTable(res) {
            res = res || {};
            var cols = res.columns || [];
            var rows = res.rows || [];
            var $table = jQuery('#table-compare-pembelian-csv-preview');

            if (jQuery.fn.DataTable && jQuery.fn.DataTable.isDataTable($table)) {
                $table.DataTable().destroy();
            }
            $table.find('thead tr').empty();
            $table.find('tbody').empty();

            cols.forEach(function(col) {
                $table.find('thead tr').append(jQuery('<th>').text(col));
            });

            var dtRows = rows.map(function(row) {
                return cols.map(function(col) {
                    return (row && row[col] != null) ? String(row[col]) : '';
                });
            });

            comparePembelianCsvPreviewDt = $table.DataTable({
                data: dtRows,
                scrollX: true,
                scrollY: 400,
                scrollCollapse: true,
                paging: true,
                pageLength: 25,
                order: [],
                autoWidth: false,
                deferRender: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.4/i18n/id.json'
                }
            });

            setTimeout(function() {
                if (comparePembelianCsvPreviewDt && comparePembelianCsvPreviewDt.columns) {
                    try {
                        comparePembelianCsvPreviewDt.columns.adjust().draw(false);
                    } catch (eAdj) {}
                }
            }, 200);
        }

        function openComparePembelianCsvPreviewModal(table, fileLabel) {
            table = (table || '').trim();
            if (!table) {
                if (window.Swal) {
                    Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Belum ada tabel hasil upload CSV untuk ditampilkan.' });
                } else {
                    alert('Belum ada tabel hasil upload CSV untuk ditampilkan.');
                }
                return;
            }

            jQuery('#compare-pembelian-csv-preview-meta').text('Memuat data tabel `' + table + '`...');
            jQuery('#compare-pembelian-csv-preview-loading').removeClass('d-none');
            jQuery('.compare-pembelian-csv-preview-dt-wrap').addClass('d-none');
            jQuery('#modal-compare-pembelian-csv-preview').modal('show');

            jQuery.ajax({
                url: urlCompareJurnalPembelianTabelPreview,
                type: 'POST',
                dataType: 'json',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                data: {
                    tabel: table,
                    limit: 1000
                }
            }).done(function(res) {
                jQuery('#compare-pembelian-csv-preview-loading').addClass('d-none');
                jQuery('.compare-pembelian-csv-preview-dt-wrap').removeClass('d-none');
                if (!res || !res.ok) {
                    jQuery('#compare-pembelian-csv-preview-meta').text((res && res.message) ? res.message : 'Gagal memuat data tabel.');
                    renderComparePembelianCsvPreviewTable({ columns: [], rows: [] });
                    return;
                }
                var meta = 'File: ' + (fileLabel || '—')
                    + ' | Tabel: `' + (res.table || table) + '`'
                    + ' | Total: ' + (res.total || 0) + ' baris';
                if (res.truncated) {
                    meta += ' (ditampilkan ' + (res.shown || 0) + ' baris pertama)';
                }
                jQuery('#compare-pembelian-csv-preview-meta').text(meta);
                jQuery('#modalComparePembelianCsvPreviewLabel').text('Data Tabel — ' + (res.table || table));
                renderComparePembelianCsvPreviewTable(res);
            }).fail(function(xhr) {
                jQuery('#compare-pembelian-csv-preview-loading').addClass('d-none');
                jQuery('.compare-pembelian-csv-preview-dt-wrap').removeClass('d-none');
                var parsed = parseComparePembelianAjaxResponse(xhr);
                var msg = (parsed && parsed.message)
                    ? parsed.message
                    : 'Gagal memuat preview tabel. Periksa koneksi server.';
                jQuery('#compare-pembelian-csv-preview-meta').text(msg);
                renderComparePembelianCsvPreviewTable({ columns: [], rows: [] });
            });
        }

        function exportComparePembelianExcelAll() {
            if (!comparePembelianExcelAllReady) {
                if (window.Swal) {
                    Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Jalankan Compare terlebih dahulu sebelum cetak Excel.' });
                } else {
                    alert('Jalankan Compare terlebih dahulu sebelum cetak Excel.');
                }
                return;
            }

            var bulanKey = getBulanKeyComparePembelian();
            var tabel = jQuery('#compare_tabel_pembelian').val() || '';
            if (!bulanKey || !tabel) {
                if (window.Swal) {
                    Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih bulan, tahun, dan tabel terlebih dahulu.' });
                }
                return;
            }

            var form = document.createElement('form');
            form.method = 'POST';
            form.action = urlCompareJurnalPembelianExcelAll;
            form.target = '_blank';
            form.style.display = 'none';

            var fields = {
                bulan: bulanKey,
                bulan_num: jQuery('#compare_bulan_pembelian').val(),
                tahun: jQuery('#compare_tahun_pembelian').val(),
                tabel: tabel
            };

            Object.keys(fields).forEach(function(key) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = fields[key];
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }

        function exportComparePembelianExcel(jenis) {
            var bulanKey = getBulanKeyComparePembelian();
            var tabel = jQuery('#compare_tabel_pembelian').val() || '';
            if (!bulanKey || !tabel) {
                if (window.Swal) {
                    Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih bulan, tahun, dan tabel terlebih dahulu.' });
                }
                return;
            }

            var form = document.createElement('form');
            form.method = 'POST';
            form.action = urlCompareJurnalPembelianExcel;
            form.target = '_blank';
            form.style.display = 'none';

            var fields = {
                bulan: bulanKey,
                bulan_num: jQuery('#compare_bulan_pembelian').val(),
                tahun: jQuery('#compare_tahun_pembelian').val(),
                jenis: jenis,
                tabel: tabel
            };

            Object.keys(fields).forEach(function(key) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = fields[key];
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }

        jQuery(document).on('click', '#btn-compare-pembelian-csv-cek-data', function() {
            if (!comparePembelianCsvLastUpload || !comparePembelianCsvLastUpload.table) {
                if (window.Swal) {
                    Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Upload CSV terlebih dahulu.' });
                } else {
                    alert('Upload CSV terlebih dahulu.');
                }
                return;
            }
            openComparePembelianCsvPreviewModal(
                comparePembelianCsvLastUpload.table,
                comparePembelianCsvLastUpload.file
            );
        });

        jQuery('#modal-compare-pembelian-csv-preview').on('hidden.bs.modal', function() {
            var $table = jQuery('#table-compare-pembelian-csv-preview');
            if (jQuery.fn.DataTable && jQuery.fn.DataTable.isDataTable($table)) {
                $table.DataTable().destroy();
                $table.find('thead tr').empty();
                $table.find('tbody').empty();
            }
            comparePembelianCsvPreviewDt = null;
        });

        jQuery(document).on('change', '#compare_pembelian_csv_file', function() {
            var file = (this.files && this.files[0]) ? this.files[0] : null;
            var label = file ? file.name : 'Cari / pilih file CSV...';
            jQuery(this).next('.custom-file-label').text(label);
            if (!file) {
                resetComparePembelianCsvInput();
                return;
            }
            startImportComparePembelianCsv(file);
        });

        jQuery('#compare_bulan_pembelian, #compare_tahun_pembelian, #compare_tabel_pembelian').on('change', function() {
            hideComparePembelianExcelAllButton();
            toggleComparePembelianButton();
            updateComparePembelianInfoRingkas({
                bulan_label: getBulanKeyComparePembelian(),
                table: jQuery('#compare_tabel_pembelian').val()
            });
        });

        jQuery(document).on('click', '#btn-compare-jurnal-pembelian', function() {
            runCompareJurnalPembelian();
        });

        jQuery(document).on('click', '#btn-compare-pembelian-excel-all', function() {
            exportComparePembelianExcelAll();
        });

        jQuery(document).on('click', '.btn-compare-pembelian-excel', function() {
            exportComparePembelianExcel(jQuery(this).attr('data-jenis') || '');
        });

        jQuery('#setting-kode-akun-pembelian-tabs a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
            var target = jQuery(e.target).attr('href') || '';
            if (target === '#panel-compare-manual-online') {
                loadComparePembelianTableList(false);
                jQuery.each(comparePembelianDtInstances, function(id, dt) {
                    if (dt && dt.columns) {
                        dt.columns.adjust();
                    }
                });
            }
        });

        if (jQuery('#panel-compare-manual-online').hasClass('active') || jQuery('#panel-compare-manual-online').hasClass('show')) {
            loadComparePembelianTableList(false);
        }
        toggleComparePembelianButton();
    });
</script>