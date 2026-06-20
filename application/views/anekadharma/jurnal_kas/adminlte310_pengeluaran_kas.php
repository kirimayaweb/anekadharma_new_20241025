<link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
<style type="text/css">
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
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

        function bulan_teks($angka_bulan)
        {
            if ($angka_bulan == 1) {
                $bulan_teks = "Januari";
            } elseif ($angka_bulan == 2) {
                $bulan_teks = "Februari";
            } elseif ($angka_bulan == 3) {
                $bulan_teks = "Maret";
            } elseif ($angka_bulan == 4) {
                $bulan_teks = "April";
            } elseif ($angka_bulan == 5) {
                $bulan_teks = "Mei";
            } elseif ($angka_bulan == 6) {
                $bulan_teks = "Juni";
            } elseif ($angka_bulan == 7) {
                $bulan_teks = "Juli";
            } elseif ($angka_bulan == 8) {
                $bulan_teks = "Agustus";
            } elseif ($angka_bulan == 9) {
                $bulan_teks = "September";
            } elseif ($angka_bulan == 10) {
                $bulan_teks = "Oktober";
            } elseif ($angka_bulan == 11) {
                $bulan_teks = "November";
            } elseif ($angka_bulan == 12) {
                $bulan_teks = "Desember";
            } else {
                $bulan_teks = "";
            }
            return $bulan_teks;
        }

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
        $url_compare_pengeluaran_kas_run = isset($url_compare_pengeluaran_kas_run)
            ? $url_compare_pengeluaran_kas_run
            : site_url('Jurnal_kas/ajax_compare_pengeluaran_kas_manual_online');
        $url_compare_pengeluaran_kas_excel = isset($url_compare_pengeluaran_kas_excel)
            ? $url_compare_pengeluaran_kas_excel
            : site_url('Jurnal_kas/excel_compare_pengeluaran_kas_manual_online');
        $url_compare_pengeluaran_kas_import_csv = isset($url_compare_pengeluaran_kas_import_csv)
            ? $url_compare_pengeluaran_kas_import_csv
            : site_url('Jurnal_kas/ajax_compare_import_csv_pengeluaran_kas');
        $url_compare_pengeluaran_kas_tabel_list = isset($url_compare_pengeluaran_kas_tabel_list)
            ? $url_compare_pengeluaran_kas_tabel_list
            : site_url('Jurnal_kas/ajax_compare_tabel_list_pengeluaran_kas');
        $url_compare_pengeluaran_kas_tabel_preview = isset($url_compare_pengeluaran_kas_tabel_preview)
            ? $url_compare_pengeluaran_kas_tabel_preview
            : site_url('Jurnal_kas/ajax_compare_tabel_preview_pengeluaran_kas');

        ?>


        <div class="box box-warning box-solid">

            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">

                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-4" align="left">
                                        <div class="col-12" text-align="center"> <strong>JURNAL PENGELUARAN KAS</strong></div>
                                    </div>
                                    
                                    <div class="col-6" align="left">
                                        <?php
                                    $action_cari_between_date = site_url('Jurnal_kas/Jurnal_pengeluaran_kas_cari_between_date');
                                        ?>

                                        <form action="<?php echo $action_cari_between_date; ?>" method="post">
                                            <div class="row">

                                                <div class="col-md-1" text-align="right" align="right"></div>

                                                <div class="col-md-3" text-align="right">
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

                                                <div class="col-md-3" text-align="left" align="left">
                                                    <div class="input-group date" id="tgl_akhir" name="tgl_akhir" data-target-input="nearest">
                                                        <input type="text" class="form-control datetimepicker-input" data-target="#tgl_akhir" id="tgl_akhir" name="tgl_akhir" value="<?php echo $Get_date_akhir; ?>" required />
                                                        <div class="input-group-append" data-target="#tgl_akhir" data-toggle="datetimepicker">
                                                            <div class="input-group-text">
                                                                <i class="fa fa-calendar"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-2" text-align="left" align="left">
                                                    <strong>
                                                        <button type="submit" class="btn btn-danger btn-block btn-flat"><i class="fa fa-sign-in" aria-hidden="true"></i> Cari</button>
                                                    </strong>
                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-2" align="right">

                                        <?php //echo anchor(site_url('jurnal_kas/excel'), 'Cetak ke Excel', 'class="btn btn-success"'); 
                                        ?>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>



                    <div class="card-body">

                        <ul class="nav nav-tabs jurnal-pengeluaran-kas-tabs" id="jurnal-pengeluaran-kas-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link<?php echo $tab_data_active ? ' active' : ''; ?>" id="tab-pengeluaran-data" data-toggle="pill" href="#panel-pengeluaran-data" role="tab" aria-controls="panel-pengeluaran-data" aria-selected="<?php echo $tab_data_active ? 'true' : 'false'; ?>">Data Jurnal Pengeluaran Kas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link<?php echo $tab_compare_active ? ' active' : ''; ?>" id="tab-compare-pengeluaran-kas" data-toggle="pill" href="#panel-compare-pengeluaran-kas" role="tab" aria-controls="panel-compare-pengeluaran-kas" aria-selected="<?php echo $tab_compare_active ? 'true' : 'false'; ?>">Compare Data Manual - Online</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="jurnal-pengeluaran-kas-tabs-content">
                            <div class="tab-pane fade<?php echo $tab_data_active ? ' show active' : ''; ?>" id="panel-pengeluaran-data" role="tabpanel" aria-labelledby="tab-pengeluaran-data">

                        <table id="tglSPOPFreeze" class="display nowrap" style="width:100%">
                            <thead>
                                <!-- <tr>

                                    <th rowspan="3" style="text-align:left" width="10px">Tanggal</th>
                                    <th rowspan="3" style="text-align:center">Kode Akun</th>
                                    <th rowspan="3" style="text-align:center">No. Bukti BKM</th>
                                    <th rowspan="3" style="text-align:center">PL</th>
                                    <th rowspan="3" style="text-align:center">KETERANGAN</th>

                                    <th colspan="3" style="text-align:center">Debet</th>


                                    <th colspan="1" style="text-align:center">Kredit</th>
                                </tr>
                                <tr>
                                    <th rowspan="2" style="text-align:center">21101-UU Dagang</th>
                                    <th colspan="2" style="text-align:center">Serba-Serbi</th>
                                    <th rowspan="2" style="text-align:right">11101-Kas Besar</th>
                                </tr>


                                <tr>
                                    <th rowspan="2" style="text-align:left">No. Rek</th>
                                    <th rowspan="2" style="text-align:right">Jumlah</th>

                                </tr>
                                 -->
                                <!-- ============================================ -->

                                <tr>

                                    <th rowspan="3" style="text-align:left" width="10px">No</th>
                                    <th rowspan="3" style="text-align:left" width="10px">Tanggal</th>
                                    <th rowspan="3" style="text-align:center">Kode Akun</th>
                                    <th rowspan="3" style="text-align:center">No. Bukti BKM</th>
                                    <th rowspan="3" style="text-align:center">PL</th>
                                    <th rowspan="3" style="text-align:center">KETERANGAN</th>
                                    <th colspan="3" style="text-align:center">DEBET</th>
                                    <th colspan="1" style="text-align:center">KREDIT</th>

                                </tr>
                                <tr>
                                    <th rowspan="2" style="text-align:center">21101-UU Dagang</th>
                                    <th colspan="2" style="text-align:center">serba-serbi</th>
                                    <th rowspan="2" style="text-align:right">11101-Kas Besar</th>
                                </tr>
                                <tr>
                                    <th style="text-align:left">No. Rek</th>
                                    <th style="text-align:right">Jumlah</th>
                                </tr>

                            </thead>
                            <tbody>
                                <?php
                                $start = 0;
                                $TOTAL_21101 = 0;
                                $TOTAL_debet_jumlah = 0;
                                $TOTAL_kredit_11101 = 0;
                                $TOTAL_21101_SEMUA = 0;
                                $TOTAL_debet_jumlah_SEMUA = 0;
                                $TOTAL_kredit_11101_SEMUA = 0;
                                $TOTAL_saldo = 0;
                                foreach ($Data_kas as $list_data) {

                                ?>

                                    <?php
                                    // Baris pertama start=1
                                    $First_row = ++$start;
                                    if ($First_row == 1) {
                                        // Baris pertama
                                    ?>

                                        <tr>
                                            <td>
                                                <?php
                                                echo $First_row;
                                                ?>
                                            </td>

                                            <td>
                                                <?php
                                                echo date("d-m-Y", strtotime($list_data->tanggal));
                                                ?>
                                            </td>

                                            <!-- Kode Akun -->
                                            <td><?php
                                                // echo "Kode Akun";
                                                if ($list_data->kode_akun) {
                                                    echo $list_data->kode_akun;
                                                    echo "<br/>";
                                                    echo anchor(site_url('Jurnal_kas/ubah_kode_akun_pengeluaran/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH KODE AKUN</i>', 'class="btn btn-warning btn-xs"');
                                                } else {
                                                    echo anchor(site_url('Jurnal_kas/ubah_kode_akun_pengeluaran/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">INPUT KODE AKUN</i>', 'class="btn btn-danger btn-xs"');
                                                }
                                                ?>
                                            </td>

                                            <td>
                                                <?php
                                                echo $list_data->bukti;
                                                ?>
                                            </td>

                                            <td>
                                                <?php
                                                echo $list_data->pl;
                                                ?>
                                            </td>

                                            <td align="left">
                                                <?php
                                                echo $list_data->keterangan;
                                                ?>
                                            </td>


                                            <!-- 21101-UU Dagang -->
                                            <td style="text-align:right">
                                                <?php
                                                if ($list_data->kode_akun == "21101") {
                                                    echo number_format($list_data->kredit, 2, ',', '.');
                                                    $TOTAL_21101 = $TOTAL_21101 + $list_data->kredit;
                                                    $TOTAL_21101_SEMUA = $TOTAL_21101_SEMUA + $list_data->kredit;
                                                    // echo "<br/>";
                                                    // echo number_format($TOTAL_21101, 2, ',', '.');
                                                    // echo "<br/>";
                                                    // echo number_format($TOTAL_21101_SEMUA, 2, ',', '.');
                                                } else {
                                                    echo "";
                                                }
                                                ?>
                                            </td>

                                            <!-- Serbi-Serbi No. Rek -->
                                            <td>
                                                <?php
                                                // echo "no rek";
                                                ?>
                                            </td>

                                            <!-- Serbi-Serbi Jumlah -->
                                            <td style="text-align:right">
                                                <?php
                                                if ($list_data->kode_akun == "21101") {
                                                    echo "";
                                                } else {
                                                    echo number_format($list_data->kredit, 2, ',', '.');
                                                    $TOTAL_debet_jumlah = $TOTAL_debet_jumlah + $list_data->kredit;
                                                    $TOTAL_debet_jumlah_SEMUA = $TOTAL_debet_jumlah_SEMUA + $list_data->kredit;
                                                }

                                                ?>
                                            </td>

                                            <!-- Kredit 11101-Kas Besar -->
                                            <td style="text-align:right">
                                                <?php
                                                if ($list_data->kredit > 0) {
                                                    echo number_format($list_data->kredit, 2, ',', '.');
                                                    $TOTAL_kredit_11101 = $TOTAL_kredit_11101 + $list_data->kredit;
                                                    $TOTAL_kredit_11101_SEMUA = $TOTAL_kredit_11101_SEMUA + $list_data->kredit;
                                                } else {
                                                    echo "";
                                                }
                                                ?>
                                            </td>


                                        </tr>

                                        <?php

                                        // Simpan data pl untuk referensi baris selanjutnya
                                        $PL_Data = $list_data->pl;
                                    } else {

                                        // Cek apakah pl sama ?
                                        if ($PL_Data == $list_data->pl) {
                                            // Jika sama maka tampilkan list data

                                        ?>
                                            <!-- BARIS  2 DST YANG MASIH PL SAMA -->
                                            <tr>
                                                <td>
                                                    <?php
                                                    echo ++$start;
                                                    ?>
                                                </td>

                                                <td>
                                                    <?php
                                                    echo date("d-m-Y", strtotime($list_data->tanggal));
                                                    ?>
                                                </td>

                                                <!-- Kode Akun -->
                                                <td><?php
                                                    // echo "Kode Akun";
                                                    if ($list_data->kode_akun) {
                                                        echo $list_data->kode_akun;
                                                        echo "<br/>";
                                                        echo anchor(site_url('Jurnal_kas/ubah_kode_akun_pengeluaran/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH KODE AKUN</i>', 'class="btn btn-warning btn-xs"');
                                                    } else {
                                                        echo anchor(site_url('Jurnal_kas/ubah_kode_akun_pengeluaran/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">INPUT KODE AKUN</i>', 'class="btn btn-danger btn-xs"');
                                                    }
                                                    ?>
                                                </td>

                                                <td>
                                                    <?php
                                                    echo $list_data->bukti;
                                                    ?>
                                                </td>

                                                <td>
                                                    <?php
                                                    echo $list_data->pl;
                                                    ?>
                                                </td>

                                                <td align="left">
                                                    <?php
                                                    echo $list_data->keterangan;
                                                    ?>
                                                </td>


                                                <!-- 21101-UU Dagang -->
                                                <td style="text-align:right">
                                                    <?php
                                                    if ($list_data->kode_akun == "21101") {
                                                        echo number_format($list_data->kredit, 2, ',', '.');
                                                        $TOTAL_21101 = $TOTAL_21101 + $list_data->kredit;
                                                        $TOTAL_21101_SEMUA = $TOTAL_21101_SEMUA + $list_data->kredit;
                                                    } else {
                                                        echo "";
                                                    }
                                                    ?>
                                                </td>

                                                <!-- Serbi-Serbi No. Rek -->
                                                <td>
                                                    <?php
                                                    // echo "no rek";
                                                    ?>
                                                </td>

                                                <!-- Serbi-Serbi Jumlah -->
                                                <td style="text-align:right">
                                                    <?php
                                                    if ($list_data->kode_akun == "21101") {
                                                        echo "";
                                                    } else {
                                                        echo number_format($list_data->kredit, 2, ',', '.');
                                                        $TOTAL_debet_jumlah = $TOTAL_debet_jumlah + $list_data->kredit;
                                                        $TOTAL_debet_jumlah_SEMUA = $TOTAL_debet_jumlah_SEMUA + $list_data->kredit;
                                                    }

                                                    ?>
                                                </td>

                                                <!-- Kredit 11101-Kas Besar -->
                                                <td style="text-align:right">
                                                    <?php
                                                    if ($list_data->kredit > 0) {
                                                        echo number_format($list_data->kredit, 2, ',', '.');
                                                        $TOTAL_kredit_11101 = $TOTAL_kredit_11101 + $list_data->kredit;
                                                        $TOTAL_kredit_11101_SEMUA = $TOTAL_kredit_11101_SEMUA + $list_data->kredit;
                                                    } else {
                                                        echo "";
                                                    }
                                                    ?>
                                                </td>


                                            </tr>

                                        <?php

                                        } else {
                                            // Jika berbeda maka tampilkan jumlah dari pl lama dan buat baris baru list data

                                            // TOTAL PER PL
                                        ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                    echo ++$start;
                                                    ?>
                                                </td>

                                                <td>
                                                    <?php
                                                    // echo date("d-m-Y", strtotime($list_data->tanggal));
                                                    ?>
                                                </td>

                                                <!-- Kode Akun -->
                                                <td><?php
                                                    // // echo "Kode Akun";
                                                    // if ($list_data->kode_akun) {
                                                    //     echo $list_data->kode_akun;
                                                    //     echo "<br/>";
                                                    //     echo anchor(site_url('Jurnal_kas/ubah_kode_akun_pengeluaran/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH KODE AKUN</i>', 'class="btn btn-warning btn-xs"');
                                                    // } else {
                                                    //     echo anchor(site_url('Jurnal_kas/ubah_kode_akun_pengeluaran/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">INPUT KODE AKUN</i>', 'class="btn btn-danger btn-xs"');
                                                    // }
                                                    ?>
                                                </td>

                                                <td>
                                                    <?php
                                                    // echo $list_data->bukti;
                                                    ?>
                                                </td>

                                                <td>
                                                    <?php
                                                    // echo $list_data->pl;
                                                    ?>
                                                </td>

                                                <td align="left">
                                                    <?php
                                                    // echo $list_data->keterangan;
                                                    ?>
                                                </td>

                                                <!-- 21101-UU Dagang -->
                                                <td style="background-color:yellow;text-align:right">
                                                    <?php
                                                    // if ($list_data->kode_akun == "21101") {

                                                    echo "<font color='red'><strong>" . number_format($TOTAL_21101, 2, ',', '.') . "</strong></font>";
                                                    $TOTAL_21101 = 0;

                                                    // $TOTAL_21101 = $TOTAL_21101 + $list_data->kredit;
                                                    // $TOTAL_21101_SEMUA = $TOTAL_21101_SEMUA + $list_data->kredit;
                                                    // } else {
                                                    //     echo "";
                                                    // }
                                                    ?>
                                                </td>

                                                <!-- Serbi-Serbi No. Rek -->
                                                <td>
                                                    <?php
                                                    // echo "no rek";
                                                    ?>
                                                </td>

                                                <!-- Serbi-Serbi Jumlah -->
                                                <td style="background-color:yellow;text-align:right">
                                                    <?php
                                                    // if ($list_data->kode_akun == "21101") {
                                                    //     echo "";
                                                    // } else {

                                                    echo "<font color='red'><strong>" . number_format($TOTAL_debet_jumlah, 2, ',', '.') . "</strong></font>";
                                                    $TOTAL_debet_jumlah = 0;

                                                    //     $TOTAL_debet_jumlah = $TOTAL_debet_jumlah + $list_data->kredit;
                                                    //     $TOTAL_debet_jumlah_SEMUA = $TOTAL_debet_jumlah_SEMUA + $list_data->kredit;
                                                    // }

                                                    ?>
                                                </td>

                                                <!-- Kredit 11101-Kas Besar -->
                                                <td style="background-color:yellow;text-align:right">
                                                    <?php
                                                    // if ($list_data->kredit > 0) {

                                                    echo "<font color='red'><strong>" . number_format($TOTAL_kredit_11101, 2, ',', '.') . "</strong></font>";
                                                    $TOTAL_kredit_11101 = 0;

                                                    //     $TOTAL_kredit_11101 = $TOTAL_kredit_11101 + $list_data->kredit;
                                                    //     $TOTAL_kredit_11101_SEMUA = $TOTAL_kredit_11101_SEMUA + $list_data->kredit;
                                                    // } else {
                                                    //     echo "";
                                                    // }
                                                    ?>
                                                </td>


                                            </tr>

                                            <?php
                                            $TOTAL_21101 = 0;
                                            $TOTAL_debet_jumlah = 0;
                                            $TOTAL_kredit_11101 = 0;

                                            // BARIS DATA BARU DENGAN PL dari awal
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                    echo ++$start;
                                                    ?>
                                                </td>

                                                <td>
                                                    <?php
                                                    echo date("d-m-Y", strtotime($list_data->tanggal));
                                                    ?>
                                                </td>

                                                <!-- Kode Akun -->
                                                <td><?php
                                                    // echo "Kode Akun";
                                                    if ($list_data->kode_akun) {
                                                        echo $list_data->kode_akun;
                                                        echo "<br/>";
                                                        echo anchor(site_url('Jurnal_kas/ubah_kode_akun_pengeluaran/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH KODE AKUN</i>', 'class="btn btn-warning btn-xs"');
                                                    } else {
                                                        echo anchor(site_url('Jurnal_kas/ubah_kode_akun_pengeluaran/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">INPUT KODE AKUN</i>', 'class="btn btn-danger btn-xs"');
                                                    }
                                                    ?>
                                                </td>

                                                <td>
                                                    <?php
                                                    echo $list_data->bukti;
                                                    ?>
                                                </td>

                                                <td>
                                                    <?php
                                                    echo $list_data->pl;
                                                    ?>
                                                </td>

                                                <td align="left">
                                                    <?php
                                                    echo $list_data->keterangan;
                                                    ?>
                                                </td>


                                                <!-- 21101-UU Dagang -->
                                                <td style="text-align:right">
                                                    <?php
                                                    if ($list_data->kode_akun == "21101") {
                                                        echo number_format($list_data->kredit, 2, ',', '.');
                                                        $TOTAL_21101 = $TOTAL_21101 + $list_data->kredit;
                                                        $TOTAL_21101_SEMUA = $TOTAL_21101_SEMUA + $list_data->kredit;
                                                        // echo "<br/>";
                                                        // echo number_format($TOTAL_21101, 2, ',', '.');
                                                        // echo "<br/>";
                                                        // echo number_format($TOTAL_21101_SEMUA, 2, ',', '.');
                                                    } else {
                                                        echo "";
                                                    }
                                                    ?>
                                                </td>

                                                <!-- Serbi-Serbi No. Rek -->
                                                <td>
                                                    <?php
                                                    // echo "no rek";
                                                    ?>
                                                </td>

                                                <!-- Serbi-Serbi Jumlah -->
                                                <td style="text-align:right">
                                                    <?php
                                                    if ($list_data->kode_akun == "21101") {
                                                        echo "";
                                                    } else {
                                                        echo number_format($list_data->kredit, 2, ',', '.');
                                                        $TOTAL_debet_jumlah = $TOTAL_debet_jumlah + $list_data->kredit;
                                                        $TOTAL_debet_jumlah_SEMUA = $TOTAL_debet_jumlah_SEMUA + $list_data->kredit;
                                                    }

                                                    ?>
                                                </td>

                                                <!-- Kredit 11101-Kas Besar -->
                                                <td style="text-align:right">
                                                    <?php
                                                    if ($list_data->kredit > 0) {
                                                        echo number_format($list_data->kredit, 2, ',', '.');
                                                        $TOTAL_kredit_11101 = $TOTAL_kredit_11101 + $list_data->kredit;
                                                        $TOTAL_kredit_11101_SEMUA = $TOTAL_kredit_11101_SEMUA + $list_data->kredit;
                                                    } else {
                                                        echo "";
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                <?php
                                }
                                ?>


                                <!-- TOTAL BARIS terakhir -->
                                <tr>
                                    <td>
                                        <?php
                                        echo ++$start;
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        // echo date("d-m-Y", strtotime($list_data->tanggal));
                                        ?>
                                    </td>

                                    <!-- Kode Akun -->
                                    <td><?php
                                        // // echo "Kode Akun";
                                        // if ($list_data->kode_akun) {
                                        //     echo $list_data->kode_akun;
                                        //     echo "<br/>";
                                        //     echo anchor(site_url('Jurnal_kas/ubah_kode_akun_pengeluaran/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH KODE AKUN</i>', 'class="btn btn-warning btn-xs"');
                                        // } else {
                                        //     echo anchor(site_url('Jurnal_kas/ubah_kode_akun_pengeluaran/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">INPUT KODE AKUN</i>', 'class="btn btn-danger btn-xs"');
                                        // }
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        // echo $list_data->bukti;
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        // echo $list_data->pl;
                                        ?>
                                    </td>

                                    <td align="left">
                                        <?php
                                        // echo $list_data->keterangan;
                                        ?>
                                    </td>


                                    <!-- 21101-UU Dagang -->
                                    <td style="background-color:yellow;text-align:right">
                                        <?php
                                        // if ($list_data->kode_akun == "21101") {
                                        echo "<font color='red'><strong>" . number_format($TOTAL_21101, 2, ',', '.') . "</strong></font>";
                                        // $TOTAL_21101 = $TOTAL_21101 + $list_data->kredit;
                                        // $TOTAL_21101_SEMUA = $TOTAL_21101_SEMUA + $list_data->kredit;
                                        // } else {
                                        //     echo "";
                                        // }
                                        ?>
                                    </td>

                                    <!-- Serbi-Serbi No. Rek -->
                                    <td>
                                        <?php
                                        // echo "no rek";
                                        ?>
                                    </td>

                                    <!-- Serbi-Serbi Jumlah -->
                                    <td style="background-color:yellow;text-align:right">
                                        <?php
                                        // if ($list_data->kode_akun == "21101") {
                                        //     echo "";
                                        // } else {
                                        echo "<font color='red'><strong>" . number_format($TOTAL_debet_jumlah, 2, ',', '.') . "</strong></font>";
                                        //     $TOTAL_debet_jumlah = $TOTAL_debet_jumlah + $list_data->kredit;
                                        //     $TOTAL_debet_jumlah_SEMUA = $TOTAL_debet_jumlah_SEMUA + $list_data->kredit;
                                        // }

                                        ?>
                                    </td>

                                    <!-- Kredit 11101-Kas Besar -->
                                    <td style="background-color:yellow;text-align:right">
                                        <?php
                                        // if ($list_data->kredit > 0) {
                                        echo "<font color='red'><strong>" . number_format($TOTAL_kredit_11101, 2, ',', '.') . "</strong></font>";
                                        //     $TOTAL_kredit_11101 = $TOTAL_kredit_11101 + $list_data->kredit;
                                        //     $TOTAL_kredit_11101_SEMUA = $TOTAL_kredit_11101_SEMUA + $list_data->kredit;
                                        // } else {
                                        //     echo "";
                                        // }
                                        ?>
                                    </td>


                                </tr>


                            </tbody>


                            <tfoot>
                                <tr>

                                    <th style="text-align:left" width="10px"></th>
                                    <th style="text-align:center"></th>
                                    <th style="text-align:center"></th>
                                    <th style="text-align:center"></th>
                                    <th style="text-align:center"></th>
                                    <th style="text-align:center"></th>
                                    <th style="text-align:right">
                                        <?php
                                        echo "<font color='blue'><strong>" . number_format($TOTAL_21101_SEMUA + $TOTAL_debet_jumlah_SEMUA, 2, ',', '.') . "</strong></font>";
                                        ?>
                                    </th> <!-- TOTAL DEBET -->
                                    <th style="text-align:right">
                                        <?php
                                        // echo number_format($TOTAL_kredit_11301_SEMUA, 2, ',', '.');
                                        ?>
                                    </th>
                                    <th style="text-align:center">

                                    </th>
                                    <th style="text-align:right">
                                        <?php
                                        echo "<font color='blue'><strong>" . number_format($TOTAL_kredit_11101_SEMUA , 2, ',', '.') . "</strong></font>";
                                        ?>
                                    </th> <!-- TOTAL JUMLAH -->

                                </tr>

                            </tfoot>







                        </table>
                            </div><!-- /.tab-pane data -->

                            <?php
                            $compare_pengeluaran_sections = array(
                                array('jenis' => 'data_manual', 'num' => '1', 'label' => 'Data Manual', 'subtitle' => 'Tabel CSV / database terpilih', 'badge' => 'compare-pengeluaran-badge-manual', 'table' => 'table-compare-pengeluaran-manual', 'theme' => 'primary', 'icon' => 'fa-database', 'col' => 'col-lg-6'),
                                array('jenis' => 'data_online', 'num' => '2', 'label' => 'Data Online', 'subtitle' => 'jurnal_pengeluaran_kas bulan terpilih', 'badge' => 'compare-pengeluaran-badge-online', 'table' => 'table-compare-pengeluaran-online', 'theme' => 'info', 'icon' => 'fa-cloud', 'col' => 'col-lg-6'),
                                array('jenis' => 'data_cocok', 'num' => '3', 'label' => 'Data Cocok (Manual & Online)', 'subtitle' => 'Semua field sama (tidak case-sensitive)', 'badge' => 'compare-pengeluaran-badge-cocok', 'table' => 'table-compare-pengeluaran-cocok', 'theme' => 'success', 'icon' => 'fa-check-circle', 'col' => 'col-lg-6'),
                                array('jenis' => 'manual_tidak_di_online', 'num' => '4', 'label' => 'Manual Tidak Ada di Online', 'subtitle' => 'Ada di manual, tidak cocok di jurnal_pengeluaran_kas', 'badge' => 'compare-pengeluaran-badge-manual-miss', 'table' => 'table-compare-pengeluaran-manual-miss', 'theme' => 'warning', 'icon' => 'fa-exclamation-triangle', 'col' => 'col-lg-6'),
                                array('jenis' => 'online_tidak_di_manual', 'num' => '5', 'label' => 'Online Tidak Ada di Manual', 'subtitle' => 'Ada di online, tidak cocok di manual', 'badge' => 'compare-pengeluaran-badge-online-miss', 'table' => 'table-compare-pengeluaran-online-miss', 'theme' => 'cyan', 'icon' => 'fa-exchange-alt', 'col' => 'col-lg-12'),
                            );
                            ?>

                            <div class="tab-pane fade<?php echo $tab_compare_active ? ' show active' : ''; ?>" id="panel-compare-pengeluaran-kas" role="tabpanel" aria-labelledby="tab-compare-pengeluaran-kas">

                                <div class="row mb-2">
                                    <div class="col-md-12">
                                        <small class="text-muted d-block mb-2">
                                            Bandingkan data jurnal pengeluaran kas online (<strong>jurnal_pengeluaran_kas</strong>)
                                            dengan tabel manual hasil upload CSV.
                                            Field compare: <strong>tanggal, nomor_bukti_bkk, pl, keterangan, debet_21101uu_dagang, serba_serbi_nomor_rekening, serba_serbi_jumlah, kredit_11101_kas_besar</strong>.
                                            Pilih file CSV — tabel database dibuat otomatis dari nama file.
                                        </small>
                                        <label for="compare_pengeluaran_csv_file" class="mb-1">Pilih file CSV (upload ke database menjadi tabel data)</label>
                                        <div class="d-flex flex-wrap align-items-end compare-csv-upload-row mb-3">
                                            <div class="custom-file custom-file-sm mb-0 compare-csv-file-wrap">
                                                <input type="file" class="custom-file-input" id="compare_pengeluaran_csv_file" accept=".csv,text/csv">
                                                <label class="custom-file-label" for="compare_pengeluaran_csv_file" data-browse="Pilih">Cari / pilih file CSV...</label>
                                            </div>
                                        </div>
                                        <div id="compare-pengeluaran-csv-upload-info" class="alert alert-light border py-2 d-none mb-3">
                                            <div class="small mb-1"><span class="text-muted">File:</span> <strong id="compare-pengeluaran-csv-filename">—</strong></div>
                                            <div class="small mb-1"><span class="text-muted">Tabel DB:</span> <strong id="compare-pengeluaran-csv-tablename" class="text-primary">—</strong> <span class="text-muted" id="compare-pengeluaran-csv-rowcount"></span></div>
                                            <button type="button" id="btn-compare-pengeluaran-csv-cek-data" class="btn btn-outline-info btn-sm"><i class="fas fa-table"></i> Cek Tabel</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-end compare-toolbar-row flex-wrap">
                                    <div class="col-auto mb-2">
                                        <label for="compare_bulan_pengeluaran" class="small mb-1">Bulan</label>
                                        <select id="compare_bulan_pengeluaran" class="form-control form-control-sm compare-toolbar-control">
                                            <?php foreach ($nama_bulan_id as $num => $label_bulan) { ?>
                                                <option value="<?php echo (int) $num; ?>"<?php echo ((int) $num === (int) $compare_bulan_num) ? ' selected' : ''; ?>><?php echo htmlspecialchars($label_bulan, ENT_QUOTES, 'UTF-8'); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-auto mb-2">
                                        <label for="compare_tahun_pengeluaran" class="small mb-1">Tahun</label>
                                        <select id="compare_tahun_pengeluaran" class="form-control form-control-sm compare-toolbar-control">
                                            <?php for ($th = $gen_tahun_max; $th >= $gen_tahun_min; $th--) { ?>
                                                <option value="<?php echo (int) $th; ?>"<?php echo ((int) $th === (int) $compare_tahun_num) ? ' selected' : ''; ?>><?php echo (int) $th; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-auto mb-2">
                                        <label for="compare_tabel_pengeluaran" class="small mb-1">Pilih tabel database</label>
                                        <select id="compare_tabel_pengeluaran" class="form-control form-control-sm compare-toolbar-control compare-toolbar-tabel">
                                            <option value="">— Muat daftar tabel —</option>
                                        </select>
                                    </div>
                                    <div class="col-auto mb-2">
                                        <label class="small mb-1 d-block">&nbsp;</label>
                                        <div class="d-flex flex-wrap align-items-center">
                                            <button type="button" id="btn-compare-pengeluaran-kas" class="btn btn-info btn-sm d-none"><i class="fas fa-columns"></i> Compare</button>
                                            <button type="button" id="btn-compare-pengeluaran-excel-all" class="btn btn-success btn-sm d-none ml-2"><i class="fa fa-file-excel-o"></i> Cetak ke Excel</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-secondary py-2" id="compare-pengeluaran-info-ringkas">
                                    <strong>Bulan:</strong> <span id="compare-pengeluaran-label-bulan">—</span>
                                    &nbsp;|&nbsp; <strong>Tabel manual:</strong> <span id="compare-pengeluaran-label-tabel">—</span>
                                    &nbsp;|&nbsp; <strong>Manual:</strong> <span id="compare-pengeluaran-count-manual">—</span>
                                    &nbsp;|&nbsp; <strong>Online:</strong> <span id="compare-pengeluaran-count-online">—</span>
                                    &nbsp;|&nbsp; <strong>Cocok:</strong> <span id="compare-pengeluaran-count-cocok">—</span>
                                    &nbsp;|&nbsp; <strong>Manual tidak di online:</strong> <span id="compare-pengeluaran-count-manual-miss">—</span>
                                    &nbsp;|&nbsp; <strong>Online tidak di manual:</strong> <span id="compare-pengeluaran-count-online-miss">—</span>
                                </div>
                                <div class="alert alert-info py-2 mb-3" id="compare-pengeluaran-status">
                                    Pilih file CSV, bulan, tahun, dan tabel manual — klik <strong>Compare</strong>. Setelah selesai, tombol <strong>Cetak ke Excel</strong> dan tabel hasil akan muncul.
                                </div>

                                <div id="compare-pengeluaran-results-panel" class="d-none">
                                    <h5 class="mb-3 text-primary"><i class="fas fa-chart-bar"></i> Hasil Komparasi Pengeluaran Kas</h5>
                                    <div class="row">
                                    <?php foreach ($compare_pengeluaran_sections as $sec) { ?>
                                    <div class="<?php echo $sec['col']; ?> mb-3">
                                        <div class="compare-pengeluaran-section-card compare-theme-<?php echo $sec['theme']; ?>">
                                            <div class="compare-pengeluaran-section-header">
                                                <div class="compare-pengeluaran-section-title">
                                                    <span class="compare-section-num"><?php echo $sec['num']; ?></span>
                                                    <i class="fas <?php echo $sec['icon']; ?> compare-section-icon"></i>
                                                    <div>
                                                        <div class="compare-section-label"><?php echo htmlspecialchars($sec['label'], ENT_QUOTES, 'UTF-8'); ?></div>
                                                        <div class="compare-section-subtitle"><?php echo htmlspecialchars($sec['subtitle'], ENT_QUOTES, 'UTF-8'); ?></div>
                                                    </div>
                                                </div>
                                                <div class="compare-pengeluaran-section-actions">
                                                    <span id="<?php echo $sec['badge']; ?>" class="badge compare-section-badge">0</span>
                                                </div>
                                            </div>
                                            <div class="compare-dt-wrap">
                                                <table id="<?php echo $sec['table']; ?>" class="table table-bordered table-sm compare-dt compare-pengeluaran-dt" style="width:100%;">
                                                    <thead><tr><th>No</th><th>Tanggal</th><th>No Bukti BKK</th><th>PL</th><th>Keterangan</th><th>Debet 21101</th><th>No Rek</th><th>Jumlah</th><th>Kredit Kas</th><th>Catatan</th></tr></thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    </div>
                                </div>

                                <div class="modal fade" id="modal-compare-pengeluaran-csv-preview" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document" style="max-width:95%;">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white py-2">
                                                <h5 class="modal-title">Detail Tabel CSV</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <div class="modal-body pb-2">
                                                <p class="text-muted small mb-2" id="compare-pengeluaran-csv-preview-meta">Memuat...</p>
                                                <table id="table-compare-pengeluaran-csv-preview" class="table table-bordered table-striped table-sm" style="width:100%;font-size:12px;">
                                                    <thead><tr></tr></thead><tbody></tbody>
                                                </table>
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
    div.dataTables_wrapper { width: 100%; margin: 0 auto; }
    .nav-tabs.jurnal-pengeluaran-kas-tabs { border-bottom: 2px solid #ffc107; margin-bottom: 15px; }
    .nav-tabs.jurnal-pengeluaran-kas-tabs .nav-link { background: #fff; border: 2px solid #ffc107; border-bottom: none; color: #888; margin-right: 4px; border-radius: 4px 4px 0 0; opacity: .75; }
    .nav-tabs.jurnal-pengeluaran-kas-tabs .nav-link.active { background: #007bff; color: #000; font-weight: bold; opacity: 1; }
    .compare-toolbar-row .compare-toolbar-control { width: 110px; min-width: 110px; }
    #compare_tahun_pengeluaran.compare-toolbar-control { width: 88px; min-width: 88px; }
    #compare_tabel_pengeluaran.compare-toolbar-tabel { width: 360px; min-width: 270px; max-width: 480px; }
    .compare-csv-file-wrap { max-width: 520px; min-width: 280px; flex: 0 1 520px; }
    #compare-pengeluaran-results-panel { margin-top: 8px; animation: comparePengeluaranFadeIn .35s ease; }
    @keyframes comparePengeluaranFadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    .compare-pengeluaran-section-card { border-radius: 10px; border: 1px solid #dee2e6; background: #fff; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.05); display: flex; flex-direction: column; height: 100%; }
    .compare-pengeluaran-section-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; padding: 10px 14px; border-bottom: 1px solid rgba(0,0,0,.08); }
    .compare-pengeluaran-section-title { display: flex; align-items: center; gap: 10px; }
    .compare-section-num { display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background: rgba(0,0,0,.08); font-weight: 700; font-size: 12px; }
    .compare-section-label { font-weight: 700; font-size: 14px; line-height: 1.2; }
    .compare-section-subtitle { font-size: 11px; color: #6c757d; }
    .compare-section-badge { font-size: 12px; margin-right: 6px; }
    .compare-theme-primary .compare-pengeluaran-section-header { background: linear-gradient(90deg, #e7f1ff, #fff); border-left: 4px solid #007bff; }
    .compare-theme-info .compare-pengeluaran-section-header { background: linear-gradient(90deg, #e8f7fa, #fff); border-left: 4px solid #17a2b8; }
    .compare-theme-success .compare-pengeluaran-section-header { background: linear-gradient(90deg, #e8f5e9, #fff); border-left: 4px solid #28a745; }
    .compare-theme-warning .compare-pengeluaran-section-header { background: linear-gradient(90deg, #fff8e1, #fff); border-left: 4px solid #ffc107; }
    .compare-theme-cyan .compare-pengeluaran-section-header { background: linear-gradient(90deg, #e0f7fa, #fff); border-left: 4px solid #17a2b8; }
    .compare-dt-wrap .dataTables_wrapper { font-size: 13px; }
    .compare-dt-wrap table.dataTable thead th { background: #f8f9fa; font-size: 11px; white-space: nowrap; }
    .compare-dt-wrap table.dataTable tbody td { font-size: 12px; padding: 6px 8px; vertical-align: middle; }
    .compare-dt-wrap .text-amount { font-weight: 600; }
    .compare-dt-wrap .text-amount-deb { color: #155724; }
    .compare-dt-wrap .text-amount-jml { color: #856404; }
    .compare-dt-wrap .text-amount-kre { color: #0c5460; }
    .compare-dt-wrap .text-catatan { font-size: 11px; color: #856404; }
</style>

<script>
window.addEventListener('load', function() {
    if (!window.jQuery || !jQuery.fn.DataTable) {
        console.error('Compare Pengeluaran Kas: jQuery/DataTables belum dimuat.');
        return;
    }
    var urlRun = <?php echo json_encode($url_compare_pengeluaran_kas_run); ?>;
    var urlExcel = <?php echo json_encode($url_compare_pengeluaran_kas_excel); ?>;
    var urlImport = <?php echo json_encode($url_compare_pengeluaran_kas_import_csv); ?>;
    var urlList = <?php echo json_encode($url_compare_pengeluaran_kas_tabel_list); ?>;
    var urlPreview = <?php echo json_encode($url_compare_pengeluaran_kas_tabel_preview); ?>;
    var lastResult = null, dtMap = {}, tablesLoaded = false, csvBusy = false, csvLast = null;

    function bulanKey() {
        var b = parseInt(jQuery('#compare_bulan_pengeluaran').val(), 10);
        var t = parseInt(jQuery('#compare_tahun_pengeluaran').val(), 10);
        if (!b || !t) return '';
        return t + '-' + String(b).padStart(2, '0');
    }
    function parseAmt(v) {
        if (v == null || v === '') return 0;
        var s = String(v);
        if (s.indexOf('<') >= 0) s = jQuery('<div>').html(s).text();
        s = s.replace(/\./g, '').replace(',', '.').replace(/[^0-9.-]/g, '');
        var n = parseFloat(s); return isNaN(n) ? 0 : n;
    }
    function fmtAmtCell(v, type) {
        var n = parseAmt(v);
        if (!v || n === 0) return '<span class="text-amount text-amount-empty">—</span>';
        return '<span class="text-amount text-amount-' + type + '">' + jQuery('<span>').text(String(v)).html() + '</span>';
    }
    function escText(v) {
        return v ? '<span class="text-ket">' + jQuery('<span>').text(v).html() + '</span>' : '';
    }
    function setStatus(type, html) {
        var $el = jQuery('#compare-pengeluaran-status');
        $el.removeClass('alert-info alert-success alert-danger alert-warning');
        $el.addClass(type === 'success' ? 'alert-success' : (type === 'danger' ? 'alert-danger' : (type === 'warning' ? 'alert-warning' : 'alert-info')));
        $el.html(html);
    }
    function toggleBtns() {
        var show = bulanKey() !== '' && (jQuery('#compare_tabel_pengeluaran').val() || '') !== '';
        jQuery('#btn-compare-pengeluaran-kas').toggleClass('d-none', !show);
        if (!show) jQuery('#btn-compare-pengeluaran-excel-all').addClass('d-none');
    }
    function buildRows(items) {
        return (items || []).map(function(it, i) {
            return [
                i + 1,
                it.tanggal || '',
                escText(it.nomor_bukti_bkk),
                escText(it.pl),
                escText(it.keterangan),
                fmtAmtCell(it.debet_21101uu_dagang, 'deb'),
                escText(it.serba_serbi_nomor_rekening),
                fmtAmtCell(it.serba_serbi_jumlah, 'jml'),
                fmtAmtCell(it.kredit_11101_kas_besar, 'kre'),
                it.catatan ? '<span class="text-catatan">' + jQuery('<span>').text(it.catatan).html() + '</span>' : ''
            ];
        });
    }
    function renderTable(sel, items) {
        var $t = jQuery(sel); if (!$t.length) return;
        items = items || [];
        if (jQuery.fn.DataTable.isDataTable($t)) $t.DataTable().clear().destroy();
        $t.find('tbody').empty();
        var dt = $t.DataTable({
            data: buildRows(items), paging: true, searching: true, ordering: true, info: true,
            scrollX: true, scrollY: '280px', scrollCollapse: true, pageLength: 25,
            order: [[1, 'asc']], autoWidth: false,
            language: { url: '//cdn.datatables.net/plug-ins/1.11.4/i18n/id.json', emptyTable: 'Tidak ada data' }
        });
        dtMap[sel] = dt;
    }
    function renderAll(res) {
        renderTable('#table-compare-pengeluaran-manual', res.data_manual);
        renderTable('#table-compare-pengeluaran-online', res.data_online);
        renderTable('#table-compare-pengeluaran-cocok', res.data_cocok);
        renderTable('#table-compare-pengeluaran-manual-miss', res.manual_tidak_di_online);
        renderTable('#table-compare-pengeluaran-online-miss', res.online_tidak_di_manual);
    }
    function updateInfo(res) {
        res = res || lastResult || {};
        var s = res.stats || {};
        jQuery('#compare-pengeluaran-label-bulan').text(res.bulan_label || bulanKey() || '—');
        jQuery('#compare-pengeluaran-label-tabel').text(res.table || jQuery('#compare_tabel_pengeluaran').val() || '—');
        jQuery('#compare-pengeluaran-count-manual').text(s.data_manual != null ? s.data_manual : '—');
        jQuery('#compare-pengeluaran-count-online').text(s.data_online != null ? s.data_online : '—');
        jQuery('#compare-pengeluaran-count-cocok').text(s.data_cocok != null ? s.data_cocok : '—');
        jQuery('#compare-pengeluaran-count-manual-miss').text(s.manual_tidak_di_online != null ? s.manual_tidak_di_online : '—');
        jQuery('#compare-pengeluaran-count-online-miss').text(s.online_tidak_di_manual != null ? s.online_tidak_di_manual : '—');
        jQuery('#compare-pengeluaran-badge-manual').text(s.data_manual || 0);
        jQuery('#compare-pengeluaran-badge-online').text(s.data_online || 0);
        jQuery('#compare-pengeluaran-badge-cocok').text(s.data_cocok || 0);
        jQuery('#compare-pengeluaran-badge-manual-miss').text(s.manual_tidak_di_online || 0);
        jQuery('#compare-pengeluaran-badge-online-miss').text(s.online_tidak_di_manual || 0);
    }
    function loadTableList(force, selectTable) {
        if (tablesLoaded && !force) {
            if (selectTable) jQuery('#compare_tabel_pengeluaran').val(selectTable);
            toggleBtns(); return;
        }
        jQuery.ajax({ url: urlList, type: 'POST', dataType: 'json' }).done(function(res) {
            if (!res || !res.ok) { setStatus('danger', (res && res.message) || 'Gagal memuat daftar tabel.'); return; }
            var $sel = jQuery('#compare_tabel_pengeluaran');
            var cur = selectTable || $sel.val();
            $sel.find('option:not(:first)').remove();
            (res.tables || []).forEach(function(tbl) { $sel.append(jQuery('<option>', { value: tbl, text: tbl })); });
            if (cur) $sel.val(cur);
            tablesLoaded = true;
        }).always(toggleBtns);
    }
    function runCompare() {
        var bk = bulanKey(), tbl = jQuery('#compare_tabel_pengeluaran').val() || '';
        if (!bk || !tbl) { alert('Pilih bulan, tahun, dan tabel database.'); return; }
        if (typeof Swal !== 'undefined') {
            Swal.fire({ title: 'Memproses Compare...', html: 'Membandingkan data manual vs jurnal_pengeluaran_kas online', allowOutsideClick: false, didOpen: function() { Swal.showLoading(); } });
        }
        setStatus('info', '<i class="fas fa-spinner fa-spin"></i> Membandingkan...');
        jQuery('#compare-pengeluaran-results-panel').addClass('d-none');
        jQuery('#btn-compare-pengeluaran-excel-all').addClass('d-none');
        jQuery.ajax({
            url: urlRun, type: 'POST', dataType: 'json',
            data: { bulan: bk, bulan_num: jQuery('#compare_bulan_pengeluaran').val(), tahun: jQuery('#compare_tahun_pengeluaran').val(), tabel: tbl }
        }).done(function(res) {
            if (typeof Swal !== 'undefined') Swal.close();
            if (!res || !res.ok) {
                var msg = (res && res.message) || 'Compare gagal.';
                setStatus('danger', msg);
                if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Compare Gagal', html: msg });
                return;
            }
            lastResult = res; renderAll(res); updateInfo(res);
            jQuery('#compare-pengeluaran-results-panel').removeClass('d-none');
            jQuery('#btn-compare-pengeluaran-excel-all').removeClass('d-none');
            setStatus('success', '<i class="fas fa-check-circle"></i> Compare selesai. Manual: ' + (res.stats.data_manual || 0) + ', Online: ' + (res.stats.data_online || 0) + ', Cocok: ' + (res.stats.data_cocok || 0) + '.');
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'success', title: 'Compare Selesai', text: 'Data berhasil dibandingkan. Tombol Cetak ke Excel sudah tersedia.', timer: 2500, showConfirmButton: false });
            }
            jQuery('html, body').animate({ scrollTop: jQuery('#compare-pengeluaran-results-panel').offset().top - 80 }, 400);
        }).fail(function() {
            if (typeof Swal !== 'undefined') Swal.close();
            setStatus('danger', 'Tidak dapat menghubungi server.');
        });
    }
    function exportExcel() {
        var bk = bulanKey(), tbl = jQuery('#compare_tabel_pengeluaran').val() || '';
        if (!bk || !tbl) { alert('Pilih bulan, tahun, dan tabel.'); return; }
        var f = jQuery('<form method="post" target="_blank"></form>');
        f.attr('action', urlExcel);
        f.append(jQuery('<input type="hidden" name="bulan">').val(bk));
        f.append(jQuery('<input type="hidden" name="bulan_num">').val(jQuery('#compare_bulan_pengeluaran').val()));
        f.append(jQuery('<input type="hidden" name="tahun">').val(jQuery('#compare_tahun_pengeluaran').val()));
        f.append(jQuery('<input type="hidden" name="tabel">').val(tbl));
        jQuery('body').append(f); f.submit(); f.remove();
    }
    function importCsv(file) {
        if (!file || csvBusy) return;
        if ((file.name || '').split('.').pop().toLowerCase() !== 'csv') {
            if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Format Salah', text: 'File harus berformat .csv' });
            else alert('File harus .csv');
            return;
        }
        csvBusy = true;
        jQuery('#compare_pengeluaran_csv_file').prop('disabled', true);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Memproses CSV...',
                html: 'Menyimpan file <strong>' + jQuery('<span>').text(file.name).html() + '</strong> ke database sebagai tabel baru.<br/><small>Membuat tabel dari nama file, kolom id AUTO_INCREMENT, normalisasi tanggal/nominal...</small>',
                allowOutsideClick: false,
                didOpen: function() { Swal.showLoading(); }
            });
        }
        var ref = { bulan: parseInt(jQuery('#compare_bulan_pengeluaran').val(), 10), tahun: parseInt(jQuery('#compare_tahun_pengeluaran').val(), 10) };
        var fd = new FormData();
        fd.append('csv_file', file);
        fd.append('bulan_num', ref.bulan); fd.append('tahun', ref.tahun);
        fd.append('bulan', ref.tahun + '-' + String(ref.bulan).padStart(2, '0'));
        jQuery.ajax({ url: urlImport, type: 'POST', data: fd, processData: false, contentType: false, dataType: 'json' })
        .done(function(res) {
            csvBusy = false;
            jQuery('#compare_pengeluaran_csv_file').prop('disabled', false).val('');
            jQuery('#compare_pengeluaran_csv_file').next('.custom-file-label').text('Cari / pilih file CSV...');
            if (!res || !res.ok) {
                if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Import Gagal', html: (res && res.message) ? String(res.message).replace(/\n/g, '<br/>') : 'Import gagal.' });
                else setStatus('danger', (res && res.message) ? String(res.message).replace(/\n/g, '<br/>') : 'Import gagal.');
                return;
            }
            csvLast = res;
            jQuery('#compare-pengeluaran-csv-filename').text(res.file || '—');
            jQuery('#compare-pengeluaran-csv-tablename').text(res.table || '—');
            jQuery('#compare-pengeluaran-csv-rowcount').text(res.rows ? (' (' + res.rows + ' baris)') : '');
            jQuery('#compare-pengeluaran-csv-upload-info').removeClass('d-none');
            loadTableList(true, res.table);
            setStatus('success', 'Tabel <strong>' + (res.table || '') + '</strong> berhasil dibuat (' + (res.rows || 0) + ' baris).');
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Import CSV Berhasil',
                    html: 'Tabel <strong>' + (res.table || '') + '</strong> dibuat dengan <strong>' + (res.rows || 0) + '</strong> baris.<br/>Silakan klik <strong>Compare</strong> atau <strong>Cek Tabel</strong>.',
                    confirmButtonText: 'OK'
                });
            }
        }).fail(function() {
            csvBusy = false;
            jQuery('#compare_pengeluaran_csv_file').prop('disabled', false);
            if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Import Gagal', text: 'Tidak dapat menghubungi server.' });
            else setStatus('danger', 'Import CSV gagal.');
        });
    }
    jQuery('#compare_pengeluaran_csv_file').on('change', function() {
        var f = this.files && this.files[0];
        if (f) { jQuery(this).next('.custom-file-label').text(f.name); importCsv(f); }
    });
    jQuery('#compare_bulan_pengeluaran, #compare_tahun_pengeluaran, #compare_tabel_pengeluaran').on('change', toggleBtns);
    jQuery('#btn-compare-pengeluaran-kas').on('click', runCompare);
    jQuery('#btn-compare-pengeluaran-excel-all').on('click', exportExcel);
    jQuery('#tab-compare-pengeluaran-kas').on('shown.bs.tab', function() { loadTableList(false); });
    jQuery('#btn-compare-pengeluaran-csv-cek-data').on('click', function() {
        var tbl = (csvLast && csvLast.table) || jQuery('#compare_tabel_pengeluaran').val();
        if (!tbl) { alert('Belum ada tabel.'); return; }
        jQuery('#compare-pengeluaran-csv-preview-meta').text('Tabel: ' + tbl);
        jQuery('#modal-compare-pengeluaran-csv-preview').modal('show');
        jQuery.ajax({ url: urlPreview, type: 'POST', dataType: 'json', data: { tabel: tbl, limit: 500 } })
        .done(function(res) {
            if (!res || !res.ok) { jQuery('#compare-pengeluaran-csv-preview-meta').text((res && res.message) || 'Gagal preview.'); return; }
            var cols = res.columns || [];
            var $thead = jQuery('#table-compare-pengeluaran-csv-preview thead tr').empty();
            cols.forEach(function(c) { $thead.append(jQuery('<th>').text(c)); });
            var rows = (res.rows || []).map(function(r) { return cols.map(function(c) { return r[c] != null ? r[c] : ''; }); });
            var $t = jQuery('#table-compare-pengeluaran-csv-preview');
            if (jQuery.fn.DataTable.isDataTable($t)) $t.DataTable().destroy();
            $t.DataTable({ data: rows, scrollX: true, pageLength: 25 });
        });
    });
    if (jQuery('#tab-compare-pengeluaran-kas').hasClass('active')) loadTableList(false);
    toggleBtns();
});
</script>