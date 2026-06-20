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
        $url_compare_penerimaan_kas_run = isset($url_compare_penerimaan_kas_run)
            ? $url_compare_penerimaan_kas_run
            : site_url('Jurnal_kas/ajax_compare_penerimaan_kas_manual_online');
        $url_compare_penerimaan_kas_excel = isset($url_compare_penerimaan_kas_excel)
            ? $url_compare_penerimaan_kas_excel
            : site_url('Jurnal_kas/excel_compare_penerimaan_kas_manual_online');
        $url_compare_penerimaan_kas_import_csv = isset($url_compare_penerimaan_kas_import_csv)
            ? $url_compare_penerimaan_kas_import_csv
            : site_url('Jurnal_kas/ajax_compare_import_csv_penerimaan_kas');
        $url_compare_penerimaan_kas_tabel_list = isset($url_compare_penerimaan_kas_tabel_list)
            ? $url_compare_penerimaan_kas_tabel_list
            : site_url('Jurnal_kas/ajax_compare_tabel_list_penerimaan_kas');
        $url_compare_penerimaan_kas_tabel_preview = isset($url_compare_penerimaan_kas_tabel_preview)
            ? $url_compare_penerimaan_kas_tabel_preview
            : site_url('Jurnal_kas/ajax_compare_tabel_preview_penerimaan_kas');

        ?>



        <div class="box box-warning box-solid">

            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">

                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-4" align="left">
                                        <div class="col-12" text-align="center"> <strong>JURNAL PENERIMAAN KAS</strong></div>
                                    </div>
                                    
                                    <div class="col-6" align="left">
                                    <?php
                                        $action_cari_between_date = site_url('Jurnal_kas/Jurnal_penerimaan_kas_cari_between_date');
                                        ?>

                                        <form id="form-cari-penerimaan-kas" action="<?php echo $action_cari_between_date; ?>" method="post">
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

                        <ul class="nav nav-tabs jurnal-penerimaan-kas-tabs" id="jurnal-penerimaan-kas-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link<?php echo $tab_data_active ? ' active' : ''; ?>" id="tab-penerimaan-data" data-toggle="pill" href="#panel-penerimaan-data" role="tab" aria-controls="panel-penerimaan-data" aria-selected="<?php echo $tab_data_active ? 'true' : 'false'; ?>">Data</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link<?php echo $tab_compare_active ? ' active' : ''; ?>" id="tab-compare-penerimaan-kas" data-toggle="pill" href="#panel-compare-penerimaan-kas" role="tab" aria-controls="panel-compare-penerimaan-kas" aria-selected="<?php echo $tab_compare_active ? 'true' : 'false'; ?>">Compare Data Manual - Online</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="jurnal-penerimaan-kas-tabs-content">
                            <div class="tab-pane fade<?php echo $tab_data_active ? ' show active' : ''; ?>" id="panel-penerimaan-data" role="tabpanel" aria-labelledby="tab-penerimaan-data">

                        <!-- <table id="example" class="table table-striped dt-responsive w-100 table-bordered display nowrap table-hover mb-0" style="width:100%"> -->
                        <table id="tglSPOPFreeze" class="display nowrap" style="width:100%">
                            <thead>

                                <!-- <tr>
                                    <th rowspan="3" style="text-align:left" width="10px">No</th>
                                    <th rowspan="3" style="text-align:left" width="10px">Tanggal</th>
                                    <th rowspan="3" style="text-align:center">Kode Akun</th>
                                    <th rowspan="3" style="text-align:center">No. Bukti BKM</th>
                                    <th rowspan="3" style="text-align:center">PL</th>
                                    <th rowspan="3" style="text-align:center">KETERANGAN</th>

                                    <th colspan="1" style="text-align:center">Debit</th>


                                    <th colspan="3" style="text-align:center">KREDIT</th>
                                <tr>
                                    <th rowspan="2" style="text-align:right">11101-Kas Besar</th>
                                    <th rowspan="2" style="text-align:center">11301-PU <br />Non Angsuran</th>
                                    <th colspan="2" style="text-align:center">Serba-Serbi</th>
                                </tr>
                                <tr>
                                    <th rowspan="3" style="text-align:left">Rek</th>
                                    <th style="text-align:center">Jumlah</th>
                                </tr> -->



                                <tr>

                                    <th rowspan="3" style="text-align:left" width="10px">No</th>
                                    <th rowspan="3" style="text-align:left" width="10px">Tanggal</th>
                                    <th rowspan="3" style="text-align:center">Kode Akun</th>
                                    <th rowspan="3" style="text-align:center">No. Bukti BKM</th>
                                    <th rowspan="3" style="text-align:center">PL</th>
                                    <th rowspan="3" style="text-align:center">KETERANGAN</th>
                                    <th colspan="1" style="text-align:center">DEBET</th>
                                    <th colspan="3" style="text-align:center">KREDIT</th>

                                </tr>
                                <tr>
                                    <th rowspan="2" style="text-align:center">11101-Kas Besar</th>
                                    <th rowspan="2" style="text-align:center">11301-PU Non Angsuran</th>
                                    <th colspan="2" style="text-align:right">Serba-Serbi</th>
                                </tr>
                                <tr>
                                    <th style="text-align:left">No. Rek</th>
                                    <th style="text-align:right">Jumlah</th>
                                </tr>





                            </thead>
                            <tbody>
                                <?php
                                $start = 0;
                                $TOTAL_debet_11101 = 0;
                                $TOTAL_kredit_11301 = 0;
                                $TOTAL_kredit_jumlah = 0;
                                $TOTAL_saldo = 0;

                                $TOTAL_debet_11101_SEMUA = 0;
                                $TOTAL_kredit_11301_SEMUA = 0;
                                $TOTAL_kredit_jumlah_SEMUA = 0;
                                $TOTAL_saldo_SEMUA = 0;

                                $PL_Data = 0;

                                foreach ($Data_kas as $list_data) {

                                ?>

                                    <?php
                                    // BARIS PERTAMA == ++START = 1
                                    $First_row= ++$start;
                                    if ($First_row == 1) {
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
                                                if ($list_data->kode_akun) {
                                                    echo $list_data->kode_akun;
                                                    echo "<br/>";
                                                    echo anchor(site_url('Jurnal_kas/ubah_kode_akun_penerimaan/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH KODE AKUN</i>', 'class="btn btn-warning btn-xs"');
                                                } else {
                                                    echo anchor(site_url('Jurnal_kas/ubah_kode_akun_penerimaan/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">INPUT KODE AKUN</i>', 'class="btn btn-danger btn-xs"');
                                                }
                                                ?>
                                            </td>

                                            <td><?php
                                                echo $list_data->bukti;
                                                ?>
                                            </td>
                                            <td><?php
                                                echo $list_data->pl;
                                                ?>
                                            </td>
                                            <td align="left">
                                                <?php
                                                echo $list_data->keterangan;
                                                ?>
                                            </td>

                                            <!-- Debet -->
                                            <td style="text-align:right">
                                                <?php
                                                if ($list_data->debet > 0) {
                                                    echo number_format($list_data->debet, 2, ',', '.');
                                                    $TOTAL_debet_11101 = $TOTAL_debet_11101 + $list_data->debet;
                                                    $TOTAL_debet_11101_SEMUA = $TOTAL_debet_11101_SEMUA + $list_data->debet;
                                                } else {
                                                    echo "";
                                                }
                                                ?>
                                            </td>

                                            <!-- Kredit -->
                                            <td style="text-align:right">
                                                <?php
                                                if ($list_data->kode_akun == "11301") {
                                                    echo number_format($list_data->debet, 2, ',', '.');
                                                    $TOTAL_kredit_11301 = $TOTAL_kredit_11301 + $list_data->debet;
                                                    $TOTAL_kredit_11301_SEMUA = $TOTAL_kredit_11301_SEMUA + $list_data->debet;
                                                } else {
                                                    echo "";
                                                }


                                                ?>


                                            </td>


                                            <!-- rekening -->
                                            <td align="left">
                                                <?php
                                                echo $list_data->kode_rekening;
                                                ?>
                                            </td>


                                            <td style="text-align:right">
                                                <?php
                                                if ($list_data->kode_akun <> "11301") {
                                                    echo number_format($list_data->debet, 2, ',', '.');
                                                    $TOTAL_kredit_jumlah = $TOTAL_kredit_jumlah + $list_data->debet;
                                                    $TOTAL_kredit_jumlah_SEMUA = $TOTAL_kredit_jumlah_SEMUA + $list_data->debet;
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

                                        // Baris 2 dst : cek apakah $PL_Data == $list_data->pl? jika ya, maka tampilkan baris baru , jika beda , maka tampilkan baris total pl lama 
                                        // dan buat baris baru lagi untuk data selanjutrnya

                                        if ($PL_Data == $list_data->pl) {
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
                                                    if ($list_data->kode_akun) {
                                                        echo $list_data->kode_akun;
                                                        echo "<br/>";
                                                        echo anchor(site_url('Jurnal_kas/ubah_kode_akun_penerimaan/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH KODE AKUN</i>', 'class="btn btn-warning btn-xs"');
                                                    } else {
                                                        echo anchor(site_url('Jurnal_kas/ubah_kode_akun_penerimaan/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">INPUT KODE AKUN</i>', 'class="btn btn-danger btn-xs"');
                                                    }
                                                    ?>
                                                </td>

                                                <td><?php
                                                    echo $list_data->bukti;
                                                    ?>
                                                </td>
                                                <td><?php
                                                    echo $list_data->pl;
                                                    ?>
                                                </td>
                                                <td align="left">
                                                    <?php
                                                    echo $list_data->keterangan;
                                                    ?>
                                                </td>

                                                <!-- Debet -->
                                                <td style="text-align:right">
                                                    <?php
                                                    if ($list_data->debet > 0) {
                                                        echo number_format($list_data->debet, 2, ',', '.');
                                                        $TOTAL_debet_11101 = $TOTAL_debet_11101 + $list_data->debet;
                                                        $TOTAL_debet_11101_SEMUA = $TOTAL_debet_11101_SEMUA + $list_data->debet;
                                                    } else {
                                                        echo "";
                                                    }
                                                    ?>
                                                </td>

                                                <!-- Kredit -->
                                                <td style="text-align:right">
                                                    <?php
                                                    if ($list_data->kode_akun == "11301") {
                                                        echo number_format($list_data->debet, 2, ',', '.');
                                                        $TOTAL_kredit_11301 = $TOTAL_kredit_11301 + $list_data->debet;
                                                        $TOTAL_kredit_11301_SEMUA = $TOTAL_kredit_11301_SEMUA + $list_data->debet;
                                                        // echo "<br/>";
                                                        // echo number_format($TOTAL_kredit_11301, 2, ',', '.');
                                                    } else {
                                                        echo "";
                                                    }


                                                    ?>


                                                </td>


                                                <!-- rekening -->
                                                <td align="left">
                                                    <?php
                                                    echo $list_data->kode_rekening;
                                                    ?>
                                                </td>


                                                <td style="text-align:right">
                                                    <?php
                                                    if ($list_data->kode_akun <> "11301") {
                                                        echo number_format($list_data->debet, 2, ',', '.');
                                                        $TOTAL_kredit_jumlah = $TOTAL_kredit_jumlah + $list_data->debet;
                                                        $TOTAL_kredit_jumlah_SEMUA = $TOTAL_kredit_jumlah_SEMUA + $list_data->debet;
                                                    } else {
                                                        echo "";
                                                    }

                                                    ?>
                                                </td>

                                            </tr>

                                        <?php
                                        } else {
                                            // PL beda   
                                            // 1. BARIS TOTAL PL SEBELUMNYA
                                        ?>

                                            <!-- TOTAL DARI KELOMPOK PL -->
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
                                                <td>
                                                    <?php
                                                    // if ($list_data->kode_akun) {
                                                    //     echo $list_data->kode_akun;
                                                    //     echo "<br/>";
                                                    //     echo anchor(site_url('Jurnal_kas/ubah_kode_akun_penerimaan/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH KODE AKUN</i>', 'class="btn btn-warning btn-xs"');
                                                    // } else {
                                                    //     echo anchor(site_url('Jurnal_kas/ubah_kode_akun_penerimaan/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">INPUT KODE AKUN</i>', 'class="btn btn-danger btn-xs"');
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

                                                <!-- Debet -->
                                                <td style="background-color:yellow;text-align:right">
                                                    <?php
                                                    // if ($list_data->debet > 0) {
                                                    echo "<font color='red'><strong>" . number_format($TOTAL_debet_11101, 2, ',', '.') . "</strong></font>";
                                                    // $TOTAL_debet_11101 = $TOTAL_debet_11101 + $list_data->debet;
                                                    // } else {
                                                    //     echo "";
                                                    // }
                                                    ?>
                                                </td>

                                                <!-- Kredit -->
                                                <td style="background-color:yellow;text-align:right">
                                                    <?php
                                                    // if ($list_data->kode_akun == "11301") {
                                                    echo "<font color='red'><strong>" . number_format($TOTAL_kredit_11301, 2, ',', '.') . "</strong></font>";
                                                    // $TOTAL_kredit_11301 = $TOTAL_kredit_11301 + $list_data->debet;
                                                    // } else {
                                                    //     echo "";
                                                    // }


                                                    ?>


                                                </td>


                                                <!-- rekening -->
                                                <td style="background-color:yellow;text-align:right">
                                                    <?php
                                                    // echo $list_data->kode_rekening;
                                                    ?>
                                                </td>


                                                <td style="background-color:yellow;text-align:right">
                                                    <?php
                                                    // if ($list_data->kode_akun <> "11301") {
                                                    echo "<font color='red'><strong>" . number_format($TOTAL_kredit_jumlah, 2, ',', '.') . "</strong></font>";
                                                    // $TOTAL_kredit_11301 = $TOTAL_kredit_11301 + $list_data->debet;
                                                    // } else {
                                                    //     echo "";
                                                    // }

                                                    ?>
                                                </td>

                                            </tr>
                                            <?php

                                            // 2. BARIS TOTAL PL BARU
                                            // menol kan total per kelompok $list_data->pl , karena sudah beda pl
                                            $TOTAL_debet_11101 = 0;
                                            $TOTAL_kredit_jumlah = 0;
                                            $TOTAL_kredit_11301 = 0;
                                            $TOTAL_saldo = 0;

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
                                                    if ($list_data->kode_akun) {
                                                        echo $list_data->kode_akun;
                                                        echo "<br/>";
                                                        echo anchor(site_url('Jurnal_kas/ubah_kode_akun_penerimaan/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH KODE AKUN</i>', 'class="btn btn-warning btn-xs"');
                                                    } else {
                                                        echo anchor(site_url('Jurnal_kas/ubah_kode_akun_penerimaan/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">INPUT KODE AKUN</i>', 'class="btn btn-danger btn-xs"');
                                                    }
                                                    ?>
                                                </td>

                                                <td><?php
                                                    echo $list_data->bukti;
                                                    ?>
                                                </td>
                                                <td><?php
                                                    echo $list_data->pl;
                                                    ?>
                                                </td>
                                                <td align="left">
                                                    <?php
                                                    echo $list_data->keterangan;
                                                    ?>
                                                </td>

                                                <!-- Debet -->
                                                <td style="text-align:right">
                                                    <?php
                                                    if ($list_data->debet > 0) {
                                                        echo number_format($list_data->debet, 2, ',', '.');
                                                        $TOTAL_debet_11101 = $TOTAL_debet_11101 + $list_data->debet;
                                                        $TOTAL_debet_11101_SEMUA = $TOTAL_debet_11101_SEMUA + $list_data->debet;
                                                    } else {
                                                        echo "";
                                                    }
                                                    ?>
                                                </td>

                                                <!-- Kredit -->
                                                <td style="text-align:right">
                                                    <?php
                                                    if ($list_data->kode_akun == "11301") {
                                                        echo number_format($list_data->debet, 2, ',', '.');
                                                        $TOTAL_kredit_11301 = $TOTAL_kredit_11301 + $list_data->debet;
                                                        $TOTAL_kredit_11301_SEMUA = $TOTAL_kredit_11301_SEMUA + $list_data->debet;
                                                    } else {
                                                        echo "";
                                                    }


                                                    ?>


                                                </td>


                                                <!-- rekening -->
                                                <td align="left">
                                                    <?php
                                                    echo $list_data->kode_rekening;
                                                    ?>
                                                </td>


                                                <td style="text-align:right">
                                                    <?php
                                                    if ($list_data->kode_akun <> "11301") {
                                                        echo number_format($list_data->debet, 2, ',', '.');
                                                        $TOTAL_kredit_jumlah = $TOTAL_kredit_jumlah + $list_data->debet;
                                                        $TOTAL_kredit_jumlah_SEMUA = $TOTAL_kredit_jumlah_SEMUA + $list_data->debet;
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
                                    // END OF FOR EACH $Data_kas 
                                }
                                ?>

                                <!-- BARIS TERAKHIR TOTAL DARI KELOMPOK PL -->
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
                                    <td>
                                        <?php
                                        // if ($list_data->kode_akun) {
                                        //     echo $list_data->kode_akun;
                                        //     echo "<br/>";
                                        //     echo anchor(site_url('Jurnal_kas/ubah_kode_akun_penerimaan/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH KODE AKUN</i>', 'class="btn btn-warning btn-xs"');
                                        // } else {
                                        //     echo anchor(site_url('Jurnal_kas/ubah_kode_akun_penerimaan/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">INPUT KODE AKUN</i>', 'class="btn btn-danger btn-xs"');
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

                                    <!-- Debet -->
                                    <td style="background-color:yellow;text-align:right">
                                        <?php
                                        // if ($list_data->debet > 0) {
                                        echo "<font color='red'><strong>" . number_format($TOTAL_debet_11101, 2, ',', '.') . "</strong></font>";
                                        // $TOTAL_debet_11101 = $TOTAL_debet_11101 + $list_data->debet;
                                        // } else {
                                        //     echo "";
                                        // }
                                        ?>
                                    </td>

                                    <!-- Kredit -->
                                    <td style="background-color:yellow;text-align:right">
                                        <?php
                                        // if ($list_data->kode_akun == "11301") {
                                        echo "<font color='red'><strong>" . number_format($TOTAL_kredit_11301, 2, ',', '.') . "</strong></font>";
                                        // $TOTAL_kredit_11301 = $TOTAL_kredit_11301 + $list_data->debet;
                                        // } else {
                                        //     echo "";
                                        // }


                                        ?>


                                    </td>


                                    <!-- rekening -->
                                    <td style="background-color:yellow;text-align:right">
                                        <?php
                                        // echo $list_data->kode_rekening;
                                        ?>
                                    </td>


                                    <td style="background-color:yellow;text-align:right">
                                        <?php
                                        // if ($list_data->kode_akun <> "11301") {
                                        echo "<font color='red'><strong>" . number_format($TOTAL_kredit_jumlah, 2, ',', '.') . "</strong></font>";
                                        // $TOTAL_kredit_11301 = $TOTAL_kredit_11301 + $list_data->debet;
                                        // } else {
                                        //     echo "";
                                        // }

                                        ?>
                                    </td>

                                </tr>



                            </tbody>

                            <!-- tfoot -->

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
                                        echo "<font color='blue'><strong>" . number_format($TOTAL_debet_11101_SEMUA, 2, ',', '.') . "</strong></font>";
                                        ?>
                                    </th> <!-- TOTAL DEBET -->
                                    <th style="text-align:right">
                                        <?php
                                        // echo number_format($TOTAL_kredit_11301_SEMUA, 2, ',', '.');
                                        ?>
                                    </th>
                                    <th style="text-align:center"></th>
                                    <th style="text-align:right">
                                        <?php
                                        echo "<font color='blue'><strong>" . number_format($TOTAL_kredit_jumlah_SEMUA + $TOTAL_kredit_11301_SEMUA, 2, ',', '.') . "</strong></font>";
                                        ?>
                                    </th> <!-- TOTAL JUMLAH -->

                                </tr>

                            </tfoot>



                            <!-- end of tfoot -->


                        </table>
                            </div><!-- /.tab-pane data -->

                            <?php
                            $compare_penerimaan_sections = array(
                                array('jenis' => 'data_cocok', 'num' => '1', 'label' => 'Data Manual dan Online Cocok', 'subtitle' => 'Tanggal, keterangan, debet, kredit sama', 'badge' => 'compare-penerimaan-badge-cocok', 'table' => 'table-compare-penerimaan-cocok', 'theme' => 'success', 'icon' => 'fa-check-circle', 'col' => 'col-lg-6'),
                                array('jenis' => 'manual_tidak_di_online', 'num' => '2', 'label' => 'Manual Tidak Ada di Online', 'subtitle' => 'Selisih / tidak ditemukan di jurnal_kas online', 'badge' => 'compare-penerimaan-badge-manual', 'table' => 'table-compare-penerimaan-manual-miss', 'theme' => 'warning', 'icon' => 'fa-exclamation-triangle', 'col' => 'col-lg-6'),
                                array('jenis' => 'online_tidak_di_manual', 'num' => '3', 'label' => 'Online Tidak Ada di Manual', 'subtitle' => 'Ada di jurnal_kas, tidak cocok di manual', 'badge' => 'compare-penerimaan-badge-online', 'table' => 'table-compare-penerimaan-online-miss', 'theme' => 'cyan', 'icon' => 'fa-exchange-alt', 'col' => 'col-lg-6'),
                                array('jenis' => 'tidak_bisa_dianalisa', 'num' => '4', 'label' => 'Tidak Bisa Dianalisa', 'subtitle' => 'Tanggal/keterangan kosong atau debet & kredit kosong', 'badge' => 'compare-penerimaan-badge-analisa', 'table' => 'table-compare-penerimaan-analisa', 'theme' => 'danger', 'icon' => 'fa-ban', 'col' => 'col-lg-6'),
                            );
                            $compare_penerimaan_pair_sections = array(
                                array('jenis' => 'data_manual', 'num' => 'A', 'label' => 'Data Manual', 'subtitle' => 'Tabel CSV / database terpilih', 'badge' => 'compare-penerimaan-badge-data-manual', 'table' => 'table-compare-penerimaan-data-manual', 'theme' => 'primary', 'icon' => 'fa-database'),
                                array('jenis' => 'data_online_cocok', 'num' => 'B', 'label' => 'Data Online Cocok di Manual', 'subtitle' => 'jurnal_kas penerimaan yang cocok', 'badge' => 'compare-penerimaan-badge-data-online', 'table' => 'table-compare-penerimaan-data-online', 'theme' => 'info', 'icon' => 'fa-cloud'),
                                array('jenis' => 'manual_tidak_di_online', 'num' => 'C', 'label' => 'Manual Tidak Ada di Online', 'subtitle' => 'Selisih manual vs online', 'badge' => 'compare-penerimaan-badge-manual-pair', 'table' => 'table-compare-penerimaan-manual-pair', 'theme' => 'warning', 'icon' => 'fa-exclamation-triangle'),
                                array('jenis' => 'manual_tidak_terproses', 'num' => 'D', 'label' => 'Manual Tidak Terproses', 'subtitle' => 'Data manual tidak lengkap', 'badge' => 'compare-penerimaan-badge-manual-unproc', 'table' => 'table-compare-penerimaan-manual-unproc', 'theme' => 'secondary', 'icon' => 'fa-times-circle'),
                                array('jenis' => 'online_tidak_terproses', 'num' => 'E', 'label' => 'Online Tidak Terproses', 'subtitle' => 'Data online tidak lengkap', 'badge' => 'compare-penerimaan-badge-online-unproc', 'table' => 'table-compare-penerimaan-online-unproc', 'theme' => 'dark', 'icon' => 'fa-times-circle'),
                            );
                            ?>

                            <div class="tab-pane fade<?php echo $tab_compare_active ? ' show active' : ''; ?>" id="panel-compare-penerimaan-kas" role="tabpanel" aria-labelledby="tab-compare-penerimaan-kas">

                                <div class="row mb-2">
                                    <div class="col-md-12">
                                        <small class="text-muted d-block mb-2">
                                            Bandingkan data jurnal penerimaan kas online (<strong>jurnal_kas debet &gt; 0</strong>)
                                            dengan tabel manual hasil upload CSV (format: tanggal, keterangan, debet, kredit).
                                            Pilih file CSV — tabel database akan langsung dibuat otomatis.
                                        </small>
                                        <label for="compare_penerimaan_csv_file" class="mb-1">Pilih file CSV (upload ke database menjadi tabel data)</label>
                                        <div class="d-flex flex-wrap align-items-end compare-csv-upload-row mb-3">
                                            <div class="custom-file custom-file-sm mb-0 compare-csv-file-wrap">
                                                <input type="file" class="custom-file-input" id="compare_penerimaan_csv_file" accept=".csv,text/csv">
                                                <label class="custom-file-label" for="compare_penerimaan_csv_file" data-browse="Pilih">Cari / pilih file CSV...</label>
                                            </div>
                                        </div>
                                        <div id="compare-penerimaan-csv-processing" class="alert alert-info py-2 d-none mb-3">
                                            <i class="fas fa-spinner fa-spin"></i>
                                            <strong id="compare-penerimaan-csv-processing-title">Memproses CSV...</strong>
                                            <span id="compare-penerimaan-csv-processing-text" class="d-block small mt-1 mb-0">Membuat tabel baru, kolom id INT(9) AUTO_INCREMENT, debet/kredit INT(9)...</span>
                                        </div>
                                        <div id="compare-penerimaan-csv-upload-info" class="alert alert-light border py-2 d-none mb-3">
                                            <div class="small mb-1"><span class="text-muted">File:</span> <strong id="compare-penerimaan-csv-filename">—</strong></div>
                                            <div class="small mb-1"><span class="text-muted">Tabel DB:</span> <strong id="compare-penerimaan-csv-tablename" class="text-primary">—</strong> <span class="text-muted" id="compare-penerimaan-csv-rowcount"></span></div>
                                            <button type="button" id="btn-compare-penerimaan-csv-cek-data" class="btn btn-outline-info btn-sm"><i class="fas fa-table"></i> Detail Tabel</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-end compare-toolbar-row flex-wrap">
                                    <div class="col-auto mb-2">
                                        <label for="compare_bulan_penerimaan" class="small mb-1">Bulan</label>
                                        <select id="compare_bulan_penerimaan" class="form-control form-control-sm compare-toolbar-control">
                                            <?php foreach ($nama_bulan_id as $num => $label_bulan) { ?>
                                                <option value="<?php echo (int) $num; ?>"<?php echo ((int) $num === (int) $compare_bulan_num) ? ' selected' : ''; ?>><?php echo htmlspecialchars($label_bulan, ENT_QUOTES, 'UTF-8'); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-auto mb-2">
                                        <label for="compare_tahun_penerimaan" class="small mb-1">Tahun</label>
                                        <select id="compare_tahun_penerimaan" class="form-control form-control-sm compare-toolbar-control">
                                            <?php for ($th = $gen_tahun_max; $th >= $gen_tahun_min; $th--) { ?>
                                                <option value="<?php echo (int) $th; ?>"<?php echo ((int) $th === (int) $compare_tahun_num) ? ' selected' : ''; ?>><?php echo (int) $th; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-auto mb-2">
                                        <label for="compare_tabel_penerimaan" class="small mb-1">Pilih tabel database</label>
                                        <select id="compare_tabel_penerimaan" class="form-control form-control-sm compare-toolbar-control compare-toolbar-tabel">
                                            <option value="">— Muat daftar tabel —</option>
                                        </select>
                                    </div>
                                    <div class="col-auto mb-2">
                                        <label class="small mb-1 d-block">&nbsp;</label>
                                        <div class="d-flex flex-wrap align-items-center">
                                            <button type="button" id="btn-compare-penerimaan-kas" class="btn btn-info btn-sm d-none"><i class="fas fa-columns"></i> Compare</button>
                                            <button type="button" id="btn-compare-penerimaan-excel-all" class="btn btn-success btn-sm d-none ml-2"><i class="fa fa-file-excel-o"></i> Cetak ke Excel</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-secondary py-2" id="compare-penerimaan-info-ringkas">
                                    <strong>Bulan:</strong> <span id="compare-penerimaan-label-bulan">—</span>
                                    &nbsp;|&nbsp; <strong>Tabel manual:</strong> <span id="compare-penerimaan-label-tabel">—</span>
                                    &nbsp;|&nbsp; <strong>Cocok:</strong> <span id="compare-penerimaan-count-cocok">—</span>
                                    &nbsp;|&nbsp; <strong>Manual tidak di online:</strong> <span id="compare-penerimaan-count-manual">—</span>
                                    &nbsp;|&nbsp; <strong>Online tidak di manual:</strong> <span id="compare-penerimaan-count-online">—</span>
                                    &nbsp;|&nbsp; <strong>Tidak bisa dianalisa:</strong> <span id="compare-penerimaan-count-analisa">—</span>
                                </div>
                                <div class="alert alert-info py-2 mb-3" id="compare-penerimaan-status">
                                    Pilih file CSV, bulan, tahun, dan tabel manual — klik <strong>Compare</strong>. Setelah selesai, tombol <strong>Cetak ke Excel</strong> dan tabel hasil akan muncul.
                                </div>

                                <div id="compare-penerimaan-results-panel" class="d-none">
                                    <h5 class="mb-3 text-primary"><i class="fas fa-chart-bar"></i> Hasil Komparasi</h5>
                                    <div class="row">
                                    <?php foreach ($compare_penerimaan_sections as $sec) { ?>
                                    <div class="<?php echo $sec['col']; ?> mb-3">
                                        <div class="compare-penerimaan-section-card compare-theme-<?php echo $sec['theme']; ?>">
                                            <div class="compare-penerimaan-section-header">
                                                <div class="compare-penerimaan-section-title">
                                                    <span class="compare-section-num"><?php echo $sec['num']; ?></span>
                                                    <i class="fas <?php echo $sec['icon']; ?> compare-section-icon"></i>
                                                    <div>
                                                        <div class="compare-section-label"><?php echo htmlspecialchars($sec['label'], ENT_QUOTES, 'UTF-8'); ?></div>
                                                        <div class="compare-section-subtitle"><?php echo htmlspecialchars($sec['subtitle'], ENT_QUOTES, 'UTF-8'); ?></div>
                                                    </div>
                                                </div>
                                                <div class="compare-penerimaan-section-actions">
                                                    <span id="<?php echo $sec['badge']; ?>" class="badge compare-section-badge">0</span>
                                                    <button type="button" class="btn btn-sm btn-outline-success btn-compare-penerimaan-excel" data-jenis="<?php echo $sec['jenis']; ?>" title="Cetak ke Excel"><i class="fa fa-file-excel-o"></i> Excel</button>
                                                </div>
                                            </div>
                                            <div class="compare-dt-wrap">
                                                <table id="<?php echo $sec['table']; ?>" class="table table-bordered table-sm compare-dt" style="width:100%;">
                                                    <thead><tr><th>No</th><th>Tanggal</th><th>Keterangan</th><th>Debet</th><th>Kredit</th><th>Catatan</th></tr></thead>
                                                    <tbody></tbody>
                                                    <tfoot><tr class="compare-dt-total-row"><th colspan="3" class="text-right">Total</th><th class="compare-total-debet text-right">—</th><th class="compare-total-kredit text-right">—</th><th></th></tr></tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    </div>

                                    <hr class="my-4">
                                    <h5 class="mb-3 text-info"><i class="fas fa-columns"></i> Tampilan Berdampingan (5 Tabel)</h5>
                                    <div class="compare-penerimaan-pair-scroll">
                                    <?php foreach ($compare_penerimaan_pair_sections as $idx => $sec) { ?>
                                        <?php if ($idx > 0) { ?><div class="compare-pair-gap" aria-hidden="true"></div><?php } ?>
                                        <div class="compare-pair-col">
                                            <div class="compare-penerimaan-section-card compare-theme-<?php echo $sec['theme']; ?> compare-pair-card">
                                                <div class="compare-penerimaan-section-header">
                                                    <div class="compare-penerimaan-section-title">
                                                        <span class="compare-section-num"><?php echo $sec['num']; ?></span>
                                                        <i class="fas <?php echo $sec['icon']; ?> compare-section-icon"></i>
                                                        <div>
                                                            <div class="compare-section-label"><?php echo htmlspecialchars($sec['label'], ENT_QUOTES, 'UTF-8'); ?></div>
                                                            <div class="compare-section-subtitle"><?php echo htmlspecialchars($sec['subtitle'], ENT_QUOTES, 'UTF-8'); ?></div>
                                                        </div>
                                                    </div>
                                                    <div class="compare-penerimaan-section-actions">
                                                        <span id="<?php echo $sec['badge']; ?>" class="badge compare-section-badge">0</span>
                                                        <button type="button" class="btn btn-sm btn-outline-success btn-compare-penerimaan-excel" data-jenis="<?php echo $sec['jenis']; ?>" title="Cetak ke Excel"><i class="fa fa-file-excel-o"></i></button>
                                                    </div>
                                                </div>
                                                <div class="compare-dt-wrap">
                                                    <table id="<?php echo $sec['table']; ?>" class="table table-bordered table-sm compare-dt" style="width:100%;">
                                                        <thead><tr><th>No</th><th>Tanggal</th><th>Keterangan</th><th>Debet</th><th>Kredit</th><th>Catatan</th></tr></thead>
                                                        <tbody></tbody>
                                                        <tfoot><tr class="compare-dt-total-row"><th colspan="3" class="text-right">Total</th><th class="compare-total-debet text-right">—</th><th class="compare-total-kredit text-right">—</th><th></th></tr></tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    </div>
                                </div>

                                <div class="modal fade" id="modal-compare-penerimaan-csv-preview" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document" style="max-width:95%;">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white py-2">
                                                <h5 class="modal-title">Detail Tabel CSV</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <div class="modal-body pb-2">
                                                <p class="text-muted small mb-2" id="compare-penerimaan-csv-preview-meta">Memuat...</p>
                                                <div id="compare-penerimaan-csv-preview-loading" class="text-center py-4 text-muted d-none"><i class="fas fa-spinner fa-spin"></i> Memuat...</div>
                                                <table id="table-compare-penerimaan-csv-preview" class="table table-bordered table-striped table-sm" style="width:100%;font-size:12px;">
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

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
<style type="text/css">
    div.dataTables_wrapper { width: 100%; margin: 0 auto; }
    .nav-tabs.jurnal-penerimaan-kas-tabs { border-bottom: 2px solid #ffc107; margin-bottom: 15px; }
    .nav-tabs.jurnal-penerimaan-kas-tabs .nav-link { background: #fff; border: 2px solid #ffc107; border-bottom: none; color: #888; margin-right: 4px; border-radius: 4px 4px 0 0; opacity: .75; }
    .nav-tabs.jurnal-penerimaan-kas-tabs .nav-link.active { background: #007bff; color: #000; font-weight: bold; opacity: 1; }
    .compare-toolbar-row .compare-toolbar-control { width: 110px; min-width: 110px; }
    #compare_tahun_penerimaan.compare-toolbar-control { width: 88px; min-width: 88px; }
    #compare_tabel_penerimaan.compare-toolbar-tabel { width: 360px; min-width: 270px; max-width: 480px; }
    .compare-csv-file-wrap { max-width: 520px; min-width: 280px; flex: 0 1 520px; }
    #compare-penerimaan-results-panel { margin-top: 8px; animation: comparePenerimaanFadeIn .35s ease; }
    @keyframes comparePenerimaanFadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    .compare-penerimaan-section-card { border-radius: 10px; border: 1px solid #dee2e6; background: #fff; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.05); display: flex; flex-direction: column; height: 100%; }
    .compare-penerimaan-section-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; padding: 10px 14px; border-bottom: 1px solid rgba(0,0,0,.08); }
    .compare-penerimaan-section-title { display: flex; align-items: center; gap: 10px; }
    .compare-section-num { display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background: rgba(0,0,0,.08); font-weight: 700; font-size: 12px; }
    .compare-section-label { font-weight: 700; font-size: 14px; line-height: 1.2; }
    .compare-section-subtitle { font-size: 11px; color: #6c757d; }
    .compare-section-badge { font-size: 12px; margin-right: 6px; }
    .compare-theme-primary .compare-penerimaan-section-header { background: linear-gradient(90deg, #e7f1ff, #fff); border-left: 4px solid #007bff; }
    .compare-theme-info .compare-penerimaan-section-header { background: linear-gradient(90deg, #e8f7fa, #fff); border-left: 4px solid #17a2b8; }
    .compare-theme-success .compare-penerimaan-section-header { background: linear-gradient(90deg, #e8f5e9, #fff); border-left: 4px solid #28a745; }
    .compare-theme-warning .compare-penerimaan-section-header { background: linear-gradient(90deg, #fff8e1, #fff); border-left: 4px solid #ffc107; }
    .compare-theme-cyan .compare-penerimaan-section-header { background: linear-gradient(90deg, #e0f7fa, #fff); border-left: 4px solid #17a2b8; }
    .compare-theme-danger .compare-penerimaan-section-header { background: linear-gradient(90deg, #fdecea, #fff); border-left: 4px solid #dc3545; }
    .compare-theme-secondary .compare-penerimaan-section-header { background: linear-gradient(90deg, #f1f3f5, #fff); border-left: 4px solid #6c757d; }
    .compare-theme-dark .compare-penerimaan-section-header { background: linear-gradient(90deg, #ececec, #fff); border-left: 4px solid #343a40; }
    .compare-dt-wrap .dataTables_wrapper { font-size: 13px; }
    .compare-dt-wrap table.dataTable thead th { background: #f8f9fa; font-size: 12px; }
    .compare-dt-wrap table.dataTable tbody td { font-size: 12.5px; padding: 7px 10px; vertical-align: middle; }
    .compare-dt-wrap .text-amount-debet { color: #155724; font-weight: 600; }
    .compare-dt-wrap .text-amount-kredit { color: #0c5460; font-weight: 600; }
    .compare-dt-wrap .text-catatan { font-size: 11.5px; color: #856404; }
    .compare-penerimaan-pair-scroll { display: flex; flex-wrap: nowrap; overflow-x: auto; gap: 0; padding-bottom: 12px; }
    .compare-pair-col { flex: 0 0 420px; max-width: 420px; min-width: 320px; }
    .compare-pair-gap { flex: 0 0 24px; min-width: 24px; }
    .compare-pair-card { min-height: 380px; }
    .compare-dt-total-row th { background: #fff3cd !important; font-weight: 700; }
</style>

<script>
(function() {
    var submitTimer = null;

    function getTanggalFilterPenerimaanKas() {
        var form = document.getElementById('form-cari-penerimaan-kas');
        if (!form) {
            return { awal: '', akhir: '' };
        }
        var tglAwal = form.querySelector('input[name="tgl_awal"]');
        var tglAkhir = form.querySelector('input[name="tgl_akhir"]');
        return {
            awal: tglAwal ? String(tglAwal.value || '').trim() : '',
            akhir: tglAkhir ? String(tglAkhir.value || '').trim() : ''
        };
    }

    function submitCariPenerimaanKasOtomatis() {
        clearTimeout(submitTimer);
        submitTimer = setTimeout(function() {
            var form = document.getElementById('form-cari-penerimaan-kas');
            if (!form) {
                return;
            }
            var tgl = getTanggalFilterPenerimaanKas();
            if (tgl.awal && tgl.akhir) {
                form.submit();
            }
        }, 400);
    }

    function initAutoCariPenerimaanKas() {
        var form = document.getElementById('form-cari-penerimaan-kas');
        if (!form) {
            return;
        }
        form.querySelectorAll('input[name="tgl_awal"], input[name="tgl_akhir"]').forEach(function(el) {
            el.addEventListener('change', submitCariPenerimaanKasOtomatis);
        });
        if (window.jQuery) {
            jQuery('#tgl_awal, #tgl_akhir').off('change.datetimepicker.penerimaanKas hide.datetimepicker.penerimaanKas');
            jQuery('#tgl_awal, #tgl_akhir').on('change.datetimepicker.penerimaanKas hide.datetimepicker.penerimaanKas', submitCariPenerimaanKasOtomatis);
        }
    }

    if (document.readyState === 'complete') {
        initAutoCariPenerimaanKas();
    } else {
        window.addEventListener('load', initAutoCariPenerimaanKas);
    }
})();
</script>

<script>
window.addEventListener('load', function() {
    if (!window.jQuery || !jQuery.fn.DataTable) {
        console.error('Compare Penerimaan Kas: jQuery/DataTables belum dimuat.');
        return;
    }
    var urlRun = <?php echo json_encode($url_compare_penerimaan_kas_run); ?>;
    var urlExcel = <?php echo json_encode($url_compare_penerimaan_kas_excel); ?>;
    var urlImport = <?php echo json_encode($url_compare_penerimaan_kas_import_csv); ?>;
    var urlList = <?php echo json_encode($url_compare_penerimaan_kas_tabel_list); ?>;
    var urlPreview = <?php echo json_encode($url_compare_penerimaan_kas_tabel_preview); ?>;
    var lastResult = null, dtMap = {}, tablesLoaded = false, csvBusy = false, csvLast = null;

    function bulanKey() {
        var b = parseInt(jQuery('#compare_bulan_penerimaan').val(), 10);
        var t = parseInt(jQuery('#compare_tahun_penerimaan').val(), 10);
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
    function setStatus(type, html) {
        var $el = jQuery('#compare-penerimaan-status');
        $el.removeClass('alert-info alert-success alert-danger alert-warning');
        $el.addClass(type === 'success' ? 'alert-success' : (type === 'danger' ? 'alert-danger' : (type === 'warning' ? 'alert-warning' : 'alert-info')));
        $el.html(html);
    }
    function toggleBtns() {
        var show = bulanKey() !== '' && (jQuery('#compare_tabel_penerimaan').val() || '') !== '';
        jQuery('#btn-compare-penerimaan-kas').toggleClass('d-none', !show);
        if (!show) jQuery('#btn-compare-penerimaan-excel-all').addClass('d-none');
    }
    function buildRows(items) {
        return (items || []).map(function(it, i) {
            return [i + 1, it.tanggal || '', it.keterangan ? '<span class="text-ket">' + jQuery('<span>').text(it.keterangan).html() + '</span>' : '', fmtAmtCell(it.debet, 'debet'), fmtAmtCell(it.kredit, 'kredit'), it.catatan ? '<span class="text-catatan">' + jQuery('<span>').text(it.catatan).html() + '</span>' : ''];
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
            language: { url: '//cdn.datatables.net/plug-ins/1.11.4/i18n/id.json', emptyTable: 'Tidak ada data' },
            drawCallback: function() {
                var td = 0, tk = 0;
                items.forEach(function(it) { td += parseAmt(it.debet); tk += parseAmt(it.kredit); });
                $t.find('.compare-total-debet').text(td > 0 ? td.toLocaleString('id-ID') : '—');
                $t.find('.compare-total-kredit').text(tk > 0 ? tk.toLocaleString('id-ID') : '—');
            }
        });
        dtMap[sel] = dt;
    }
    function renderAll(res) {
        renderTable('#table-compare-penerimaan-cocok', res.data_cocok);
        renderTable('#table-compare-penerimaan-manual-miss', res.manual_tidak_di_online);
        renderTable('#table-compare-penerimaan-online-miss', res.online_tidak_di_manual);
        renderTable('#table-compare-penerimaan-analisa', res.tidak_bisa_dianalisa);
        renderTable('#table-compare-penerimaan-data-manual', res.data_manual);
        renderTable('#table-compare-penerimaan-data-online', res.data_online_cocok);
        renderTable('#table-compare-penerimaan-manual-pair', res.manual_tidak_di_online);
        renderTable('#table-compare-penerimaan-manual-unproc', res.manual_tidak_terproses);
        renderTable('#table-compare-penerimaan-online-unproc', res.online_tidak_terproses);
    }
    function updateInfo(res) {
        res = res || lastResult || {};
        var s = res.stats || {};
        jQuery('#compare-penerimaan-label-bulan').text(res.bulan_label || bulanKey() || '—');
        jQuery('#compare-penerimaan-label-tabel').text(res.table || jQuery('#compare_tabel_penerimaan').val() || '—');
        jQuery('#compare-penerimaan-count-cocok').text(s.data_cocok != null ? s.data_cocok : '—');
        jQuery('#compare-penerimaan-count-manual').text(s.manual_tidak_di_online != null ? s.manual_tidak_di_online : '—');
        jQuery('#compare-penerimaan-count-online').text(s.online_tidak_di_manual != null ? s.online_tidak_di_manual : '—');
        jQuery('#compare-penerimaan-count-analisa').text(s.tidak_bisa_dianalisa != null ? s.tidak_bisa_dianalisa : '—');
        jQuery('#compare-penerimaan-badge-cocok').text(s.data_cocok || 0);
        jQuery('#compare-penerimaan-badge-manual').text(s.manual_tidak_di_online || 0);
        jQuery('#compare-penerimaan-badge-online').text(s.online_tidak_di_manual || 0);
        jQuery('#compare-penerimaan-badge-analisa').text(s.tidak_bisa_dianalisa || 0);
        jQuery('#compare-penerimaan-badge-data-manual').text(s.data_manual || 0);
        jQuery('#compare-penerimaan-badge-data-online').text(s.data_online_cocok || 0);
        jQuery('#compare-penerimaan-badge-manual-pair').text(s.manual_tidak_di_online || 0);
        jQuery('#compare-penerimaan-badge-manual-unproc').text(s.manual_tidak_terproses || 0);
        jQuery('#compare-penerimaan-badge-online-unproc').text(s.online_tidak_terproses || 0);
    }
    function loadTableList(force, selectTable) {
        if (tablesLoaded && !force) {
            if (selectTable) jQuery('#compare_tabel_penerimaan').val(selectTable);
            toggleBtns(); return;
        }
        jQuery.ajax({ url: urlList, type: 'POST', dataType: 'json' }).done(function(res) {
            if (!res || !res.ok) { setStatus('danger', (res && res.message) || 'Gagal memuat daftar tabel.'); return; }
            var $sel = jQuery('#compare_tabel_penerimaan');
            var cur = selectTable || $sel.val();
            $sel.find('option:not(:first)').remove();
            (res.tables || []).forEach(function(tbl) { $sel.append(jQuery('<option>', { value: tbl, text: tbl })); });
            if (cur) $sel.val(cur);
            tablesLoaded = true;
        }).always(toggleBtns);
    }
    function runCompare() {
        var bk = bulanKey(), tbl = jQuery('#compare_tabel_penerimaan').val() || '';
        if (!bk || !tbl) { alert('Pilih bulan, tahun, dan tabel database.'); return; }
        setStatus('info', '<i class="fas fa-spinner fa-spin"></i> Membandingkan...');
        jQuery('#compare-penerimaan-results-panel').addClass('d-none');
        jQuery('#btn-compare-penerimaan-excel-all').addClass('d-none');
        jQuery.ajax({
            url: urlRun, type: 'POST', dataType: 'json',
            data: { bulan: bk, bulan_num: jQuery('#compare_bulan_penerimaan').val(), tahun: jQuery('#compare_tahun_penerimaan').val(), tabel: tbl }
        }).done(function(res) {
            if (!res || !res.ok) { setStatus('danger', (res && res.message) || 'Compare gagal.'); return; }
            lastResult = res; renderAll(res); updateInfo(res);
            jQuery('#compare-penerimaan-results-panel').removeClass('d-none');
            jQuery('#btn-compare-penerimaan-excel-all').removeClass('d-none');
            setStatus('success', '<i class="fas fa-check-circle"></i> Compare selesai. Lihat tabel hasil di bawah.');
            jQuery('html, body').animate({ scrollTop: jQuery('#compare-penerimaan-results-panel').offset().top - 80 }, 400);
        }).fail(function() { setStatus('danger', 'Tidak dapat menghubungi server.'); });
    }
    function exportExcel(jenis) {
        var bk = bulanKey(), tbl = jQuery('#compare_tabel_penerimaan').val() || '';
        if (!bk || !tbl) { alert('Pilih bulan, tahun, dan tabel.'); return; }
        var f = jQuery('<form method="post" target="_blank"></form>');
        f.attr('action', urlExcel);
        f.append(jQuery('<input type="hidden" name="bulan">').val(bk));
        f.append(jQuery('<input type="hidden" name="bulan_num">').val(jQuery('#compare_bulan_penerimaan').val()));
        f.append(jQuery('<input type="hidden" name="tahun">').val(jQuery('#compare_tahun_penerimaan').val()));
        f.append(jQuery('<input type="hidden" name="tabel">').val(tbl));
        f.append(jQuery('<input type="hidden" name="jenis">').val(jenis || 'semua'));
        jQuery('body').append(f); f.submit(); f.remove();
    }
    function importCsv(file) {
        if (!file || csvBusy) return;
        if ((file.name || '').split('.').pop().toLowerCase() !== 'csv') { alert('File harus .csv'); return; }
        csvBusy = true;
        jQuery('#compare_penerimaan_csv_file').prop('disabled', true);
        jQuery('#compare-penerimaan-csv-processing').removeClass('d-none');
        var ref = { bulan: parseInt(jQuery('#compare_bulan_penerimaan').val(), 10), tahun: parseInt(jQuery('#compare_tahun_penerimaan').val(), 10) };
        var fd = new FormData();
        fd.append('csv_file', file);
        fd.append('bulan_num', ref.bulan); fd.append('tahun', ref.tahun);
        fd.append('bulan', ref.tahun + '-' + String(ref.bulan).padStart(2, '0'));
        jQuery.ajax({ url: urlImport, type: 'POST', data: fd, processData: false, contentType: false, dataType: 'json' })
        .done(function(res) {
            jQuery('#compare-penerimaan-csv-processing').addClass('d-none');
            csvBusy = false;
            jQuery('#compare_penerimaan_csv_file').prop('disabled', false).val('');
            jQuery('#compare_penerimaan_csv_file').next('.custom-file-label').text('Cari / pilih file CSV...');
            if (!res || !res.ok) { setStatus('danger', (res && res.message) ? String(res.message).replace(/\n/g, '<br/>') : 'Import gagal.'); return; }
            csvLast = res;
            jQuery('#compare-penerimaan-csv-filename').text(res.file || '—');
            jQuery('#compare-penerimaan-csv-tablename').text(res.table || '—');
            jQuery('#compare-penerimaan-csv-rowcount').text(res.rows ? (' (' + res.rows + ' baris)') : '');
            jQuery('#compare-penerimaan-csv-upload-info').removeClass('d-none');
            loadTableList(true, res.table);
            setStatus('success', 'Tabel <strong>' + (res.table || '') + '</strong> berhasil dibuat (' + (res.rows || 0) + ' baris).');
        }).fail(function() {
            csvBusy = false;
            jQuery('#compare-penerimaan-csv-processing').addClass('d-none');
            jQuery('#compare_penerimaan_csv_file').prop('disabled', false);
            setStatus('danger', 'Import CSV gagal.');
        });
    }
    jQuery('#compare_penerimaan_csv_file').on('change', function() {
        var f = this.files && this.files[0];
        if (f) { jQuery(this).next('.custom-file-label').text(f.name); importCsv(f); }
    });
    jQuery('#compare_bulan_penerimaan, #compare_tahun_penerimaan, #compare_tabel_penerimaan').on('change', toggleBtns);
    jQuery('#btn-compare-penerimaan-kas').on('click', runCompare);
    jQuery('#btn-compare-penerimaan-excel-all').on('click', function() { exportExcel('semua'); });
    jQuery(document).on('click', '.btn-compare-penerimaan-excel', function() { exportExcel(jQuery(this).data('jenis')); });
    jQuery('#tab-compare-penerimaan-kas').on('shown.bs.tab', function() { loadTableList(false); });
    jQuery('#btn-compare-penerimaan-csv-cek-data').on('click', function() {
        var tbl = (csvLast && csvLast.table) || jQuery('#compare_tabel_penerimaan').val();
        if (!tbl) { alert('Belum ada tabel.'); return; }
        jQuery('#compare-penerimaan-csv-preview-meta').text('Tabel: ' + tbl);
        jQuery('#modal-compare-penerimaan-csv-preview').modal('show');
        jQuery.ajax({ url: urlPreview, type: 'POST', dataType: 'json', data: { tabel: tbl, limit: 500 } })
        .done(function(res) {
            if (!res || !res.ok) { jQuery('#compare-penerimaan-csv-preview-meta').text((res && res.message) || 'Gagal preview.'); return; }
            var cols = res.columns || [];
            var $thead = jQuery('#table-compare-penerimaan-csv-preview thead tr').empty();
            cols.forEach(function(c) { $thead.append(jQuery('<th>').text(c)); });
            var rows = (res.rows || []).map(function(r) { return cols.map(function(c) { return r[c] != null ? r[c] : ''; }); });
            var $t = jQuery('#table-compare-penerimaan-csv-preview');
            if (jQuery.fn.DataTable.isDataTable($t)) $t.DataTable().destroy();
            $t.DataTable({ data: rows, scrollX: true, pageLength: 25 });
        });
    });
    if (jQuery('#tab-compare-penerimaan-kas').hasClass('active')) loadTableList(false);
    toggleBtns();
});
</script>