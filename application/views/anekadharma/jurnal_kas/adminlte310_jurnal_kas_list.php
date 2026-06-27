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



        $Get_month_from_date = $month_selected;
        $Get_month_from_date_lalu = $month_selected - 1;
        $Get_year_Tahun_ini = $year_selected;
        // $Get_year_Setahun_lalu = date("Y", strtotime('-1 year'));
        $Get_year_Setahun_lalu = $year_selected-1;


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
        $tab_data_active = ($active_tab === 'data');
        $tab_compare_active = ($active_tab === 'compare');
        $tab_setting_active = ($active_tab === 'setting');
        $url_compare_jurnal_kas_run = isset($url_compare_jurnal_kas_run)
            ? $url_compare_jurnal_kas_run
            : site_url('Jurnal_kas/ajax_compare_jurnal_kas_manual_online');
        $url_compare_jurnal_kas_excel = isset($url_compare_jurnal_kas_excel)
            ? $url_compare_jurnal_kas_excel
            : site_url('Jurnal_kas/excel_compare_jurnal_kas_manual_online');
        $url_compare_jurnal_kas_import_csv = isset($url_compare_jurnal_kas_import_csv)
            ? $url_compare_jurnal_kas_import_csv
            : site_url('Jurnal_kas/ajax_compare_import_csv_jurnal_kas');
        $url_compare_jurnal_kas_tabel_list = isset($url_compare_jurnal_kas_tabel_list)
            ? $url_compare_jurnal_kas_tabel_list
            : site_url('Jurnal_kas/ajax_compare_tabel_list_jurnal_kas');
        $url_compare_jurnal_kas_tabel_preview = isset($url_compare_jurnal_kas_tabel_preview)
            ? $url_compare_jurnal_kas_tabel_preview
            : site_url('Jurnal_kas/ajax_compare_tabel_preview_jurnal_kas');
        $url_compare_jurnal_kas_tabel_validate = isset($url_compare_jurnal_kas_tabel_validate)
            ? $url_compare_jurnal_kas_tabel_validate
            : site_url('Jurnal_kas/ajax_compare_tabel_validate_jurnal_kas');
        $url_compare_jurnal_kas_tabel_detail = isset($url_compare_jurnal_kas_tabel_detail)
            ? $url_compare_jurnal_kas_tabel_detail
            : site_url('Jurnal_kas/ajax_compare_tabel_detail_jurnal_kas');
        $url_compare_jurnal_kas_tabel_import = isset($url_compare_jurnal_kas_tabel_import)
            ? $url_compare_jurnal_kas_tabel_import
            : site_url('Jurnal_kas/ajax_compare_import_table_to_jurnal_kas');
        $url_compare_jurnal_kas_tabel_detail_excel = isset($url_compare_jurnal_kas_tabel_detail_excel)
            ? $url_compare_jurnal_kas_tabel_detail_excel
            : site_url('Jurnal_kas/excel_compare_tabel_detail_jurnal_kas');
        $url_compare_jurnal_kas_publish_status = isset($url_compare_jurnal_kas_publish_status)
            ? $url_compare_jurnal_kas_publish_status
            : site_url('Jurnal_kas/ajax_compare_jurnal_kas_publish_status');
        $url_jurnal_kas_excel = isset($url_jurnal_kas_excel)
            ? $url_jurnal_kas_excel
            : site_url('Jurnal_kas/excel/' . (int) $Get_year_Tahun_ini . '/' . (int) $Get_month_from_date);
        $bulan_ns_value = isset($bulan_ns_value)
            ? $bulan_ns_value
            : sprintf('%04d-%02d', (int) $Get_year_Tahun_ini, (int) $Get_month_from_date);
        $url_cari_jurnal_kas = isset($url_cari_jurnal_kas)
            ? $url_cari_jurnal_kas
            : site_url('Jurnal_kas/cari_between_date');
        $url_setting_jurnal_kas_data = isset($url_setting_jurnal_kas_data)
            ? $url_setting_jurnal_kas_data
            : site_url('Jurnal_kas/ajax_setting_jurnal_kas_data');
        $url_setting_jurnal_kas_publish = isset($url_setting_jurnal_kas_publish)
            ? $url_setting_jurnal_kas_publish
            : site_url('Jurnal_kas/ajax_setting_jurnal_kas_publish');
        $url_setting_jurnal_kas_tabel_list = isset($url_setting_jurnal_kas_tabel_list)
            ? $url_setting_jurnal_kas_tabel_list
            : site_url('Jurnal_kas/ajax_setting_jurnal_kas_tabel_list');
        $url_lap_jurnal_kas = isset($url_lap_jurnal_kas)
            ? $url_lap_jurnal_kas
            : site_url('Lap_Jurnal_kas');
        $url_setting_jurnal_kas_excel = isset($url_setting_jurnal_kas_excel)
            ? $url_setting_jurnal_kas_excel
            : site_url('Jurnal_kas/excel_setting_jurnal_kas');
        $url_ajax_pemasukan_kas = isset($url_ajax_pemasukan_kas)
            ? $url_ajax_pemasukan_kas
            : site_url('Jurnal_kas/ajax_pemasukan_kas_action');
        if (!isset($list_kode_pl) || !is_array($list_kode_pl)) {
            $list_kode_pl = array();
        }
        if (!isset($list_kode_akun) || !is_array($list_kode_akun)) {
            $list_kode_akun = array();
        }
        if (!isset($can_input_jurnal_kas)) {
            $can_input_jurnal_kas = in_array((int) $this->session->userdata('id_user_level'), array(1, 2, 9), true);
        }
        $modal_jk_tanggal_default = sprintf('01-%02d-%04d', (int) $Get_month_from_date, (int) $Get_year_Tahun_ini);

        ?>



        <div class="box box-warning box-solid">

            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">

                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-3" align="left">
                                        <div class="col-12" text-align="center"> <strong>JURNAL KAS</strong> <?php echo bulan_teks($Get_month_from_date) . " " . $Get_year_Tahun_ini ?> </div>
                                    </div>
                                    <!-- <div class="col-2" align="left">

                                        <?php //echo anchor(site_url('jurnal_kas/pengeluaran_kas'), 'Pengeluaran Kas', 'class="btn btn-success"');
                                        ?>
                                    </div> -->

                                    <div class="col-8" align="right">

                                        <!-- <?php
                                                // $action_cari_between_date = site_url('Jurnal_kas/cari_between_date');
                                                ?>

                                        <form action="<?php //echo $action_cari_between_date; 
                                                        ?>" method="post">
                                            <div class="row">

                                                <div class="col-md-1" text-align="right" align="right"></div>

                                                <div class="col-md-3" text-align="right">
                                                    <div class="input-group date" id="tgl_awal" name="tgl_awal" data-target-input="nearest">
                                                        <input type="text" class="form-control datetimepicker-input" data-target="#tgl_awal" id="tgl_awal" name="tgl_awal" value="<?php //echo $Get_date_awal; 
                                                                                                                                                                                    ?>" required />
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
                                                        <input type="text" class="form-control datetimepicker-input" data-target="#tgl_akhir" id="tgl_akhir" name="tgl_akhir" value="<?php //echo $Get_date_akhir; 
                                                                                                                                                                                        ?>" required />
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


                                        <br /> -->


                                        <?php
                                        $action_cari_between_date = $url_cari_jurnal_kas;
                                        ?>

                                        <div class="jurnal-kas-month-filter" id="jurnal-kas-month-filter">
                                            <div class="row">
                                                <div class="col-md-5" text-align="right" align="right">
                                                    <label for="bulan_ns" class="sr-only">Pilih Bulan</label>
                                                    <input type="month" class="form-control" id="bulan_ns" name="bulan_ns" value="<?php echo htmlspecialchars($bulan_ns_value, ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                </div>
                            </div>

                        </div>

                    </div>



                    <div class="card-body">

                        <ul class="nav nav-tabs jurnal-kas-tabs" id="jurnal-kas-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link<?php echo $tab_data_active ? ' active' : ''; ?>" id="tab-jurnal-kas-data" data-toggle="pill" href="#panel-jurnal-kas-data" role="tab" aria-controls="panel-jurnal-kas-data" aria-selected="<?php echo $tab_data_active ? 'true' : 'false'; ?>">Data Jurnal Kas (Bulan : <?php echo bulan_teks($Get_month_from_date) . ' ' . $Get_year_Tahun_ini; ?>)</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link<?php echo $tab_compare_active ? ' active' : ''; ?>" id="tab-compare-jurnal-kas" data-toggle="pill" href="#panel-compare-jurnal-kas" role="tab" aria-controls="panel-compare-jurnal-kas" aria-selected="<?php echo $tab_compare_active ? 'true' : 'false'; ?>">Compare Data Manual - Online</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link<?php echo $tab_setting_active ? ' active' : ''; ?>" id="tab-setting-jurnal-kas" data-toggle="pill" href="#panel-setting-jurnal-kas" role="tab" aria-controls="panel-setting-jurnal-kas" aria-selected="<?php echo $tab_setting_active ? 'true' : 'false'; ?>">Setting Jurnal Kas</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="jurnal-kas-tabs-content">
                            <div class="tab-pane fade<?php echo $tab_data_active ? ' show active' : ''; ?>" id="panel-jurnal-kas-data" role="tabpanel" aria-labelledby="tab-jurnal-kas-data">

                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 jurnal-kas-tab1-toolbar">
                            <div>
                                <h5 class="mb-0 text-primary jk-toolbar-title"><strong>Data Jurnal Kas</strong> <span class="text-muted" id="jurnal-kas-label-bulan">(Bulan : <?php echo bulan_teks($Get_month_from_date) . ' ' . $Get_year_Tahun_ini; ?>)</span></h5>
                                <div class="jk-toolbar-hint text-muted">Pilih bulan di atas — datatable otomatis dimuat ulang</div>
                                <div id="jurnal-kas-month-loading" class="text-info d-none mt-1"><i class="fas fa-spinner fa-spin"></i> Memuat data jurnal kas...</div>
                            </div>
                            <div class="d-flex flex-wrap align-items-center mt-2 mt-md-0">
                                <?php if ($can_input_jurnal_kas) { ?>
                                <button type="button" class="btn btn-danger mr-2 mb-2 mb-md-0" id="btn-jurnal-kas-input-data" data-toggle="modal" data-target="#modal-jurnal-kas-input">
                                    <i class="fa fa-plus"></i> INPUT DATA
                                </button>
                                <?php } ?>
                                <a href="<?php echo htmlspecialchars($url_jurnal_kas_excel, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-success mb-2 mb-md-0" id="btn-jurnal-kas-excel">
                                    <i class="fa fa-file-excel-o"></i> Cetak ke Excel
                                </a>
                            </div>
                        </div>

                        <!-- <table id="example" class="table table-striped dt-responsive w-100 table-bordered display nowrap table-hover mb-0" style="width:100%"> -->
                        <div id="jurnal-kas-table-wrap">
                        <div class="jurnal-kas-dt-responsive">
                        <table id="jurnalKasMainTable" class="table table-bordered jurnal-kas-grid-table mb-0">
                            <colgroup>
                                <col class="jk-w-no">
                                <col class="jk-w-sumber">
                                <col class="jk-w-unit">
                                <col class="jk-w-tanggal">
                                <col class="jk-w-bukti">
                                <col class="jk-w-keterangan">
                                <col class="jk-w-debet">
                                <col class="jk-w-kredit">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Sumber</th>
                                    <th>Unit</th>
                                    <th>Tanggal</th>
                                    <th>Bukti</th>
                                    <th>Keterangan</th>
                                    <th>Debet</th>
                                    <th>Kredit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $start = 0;
                                $TOTAL_debet = 0;
                                $TOTAL_kredit = 0;
                                $TOTAL_saldo = 0;



                                ?>


                                <!-- // LIST SALDO BULAN LALU -->

                                <tr>

                                    <!-- TANGGAL -->
                                    <td><?php
                                        echo ++$start;
                                        ?>
                                    </td>

                                    <td></td>

                                    <td></td>

                                    <!-- TANGGAL -->
                                    <td>
                                        <?php
                                        // echo date("d-m-Y", strtotime($list_data->tanggal));
                                        // echo "<br/>";

                                        // echo anchor(site_url('Jurnal_kas/pemasukan_kas_update/' . $list_data->id), '<i class="fa fa-pencil-square-o">Ubah</i>', array('title' => 'edit', 'class' => 'btn btn-warning btn-sm'));

                                        // echo ' ';
                                        // echo anchor(site_url('jurnal_kas/delete/' . $list_data->id), '<i class="fa fa-trash-o">Hapus</i>', 'title="delete" class="btn btn-danger btn-sm" onclick="javasciprt: return confirm(\'Anda Yakin akan menghapus data ini ?\')"');

                                        ?>
                                    </td>

                                    <!-- BUKTI -->
                                    <td><?php
                                        // echo $list_data->bukti;
                                        ?>
                                    </td>

                                    <!-- KETERANGAN -->
                                    <td align="left">
                                        <?php
                                        // echo $list_data->keterangan;
                                        if ($Get_month_from_date > 1) {
                                            // $Get_month_from_date = $Get_month_from_date_lalu;
                                            echo "Saldo akhir bulan: " . bulan_teks($Get_month_from_date_lalu) . " " . $Get_year_Tahun_ini;


                                            // GET NOMINAL SALDO DARI TABEL SALDO AKHIR BULAN KEMARIN
                                            $Get_bulan_saldo = date("$Get_year_Tahun_ini-$Get_month_from_date_lalu-01");

                                            $this->db->where('tanggal', $Get_bulan_saldo);
                                            $GET_jurnal_kas_saldo_akhir_bulan = $this->db->get('jurnal_kas_saldo_akhir_bulan');

                                            if ($GET_jurnal_kas_saldo_akhir_bulan->num_rows() > 0) {
                                                // echo $GET_jurnal_kas_saldo_akhir_bulan->row()->saldo;
                                                $SALDO_AKHIR_BULAN_LALU = $GET_jurnal_kas_saldo_akhir_bulan->row()->saldo;
                                            } else {
                                                // echo "0";
                                                $SALDO_AKHIR_BULAN_LALU = 0;
                                            }
                                        } else {

                                            echo "Saldo akhir bulan: Desember " . $Get_year_Setahun_lalu;

                                            // GET NOMINAL SALDO DARI TABEL SALDO AKHIR BULAN DESEMBER KEMARIN
                                            $Get_bulan_saldo = date("$Get_year_Setahun_lalu-12-01");

                                            $this->db->where('tanggal', $Get_bulan_saldo);
                                            $GET_jurnal_kas_saldo_akhir_bulan = $this->db->get('jurnal_kas_saldo_akhir_bulan');

                                            if ($GET_jurnal_kas_saldo_akhir_bulan->num_rows() > 0) {
                                                // echo $GET_jurnal_kas_saldo_akhir_bulan->row()->saldo;
                                                $SALDO_AKHIR_BULAN_LALU = $GET_jurnal_kas_saldo_akhir_bulan->row()->saldo;
                                            } else {
                                                // echo "0";
                                                $SALDO_AKHIR_BULAN_LALU = 0;
                                            }
                                        }

                                        ?>
                                    </td>

                                    <!-- Debet -->
                                    <td style="text-align:right">
                                        <?php
                                        if ($SALDO_AKHIR_BULAN_LALU > 0) {
                                            echo number_format($SALDO_AKHIR_BULAN_LALU, 2, ',', '.');
                                            $TOTAL_debet = $TOTAL_debet + $SALDO_AKHIR_BULAN_LALU;
                                        }
                                        ?>
                                    </td>

                                    <!-- Kredit -->
                                    <td style="text-align:right">
                                        <?php
                                        if ($SALDO_AKHIR_BULAN_LALU < 1) {
                                            echo number_format($SALDO_AKHIR_BULAN_LALU, 2, ',', '.');
                                            $TOTAL_kredit = $TOTAL_kredit + $SALDO_AKHIR_BULAN_LALU;
                                        }
                                        ?>
                                    </td>

                                </tr>

                                <!-- // END OF LIST SALDO BULAN LALU -->


                                <?php


                                foreach ($Data_kas as $list_data) {
                                    // [0] => stdClass Object ( [nomor] => 4280 [tanggal] => 30/09/2024 [bukti] => BKK [keterangan] => Biaya PU/ATK : Putro Bengkel (Pembayaran SPOP No 558 Tgl 30/09/2024) [kode_rekening] => 4 [debet] => [kredit] => 1.750.000,00 )
                                ?>

                                    <tr>
                                        <td><?php
                                            echo ++$start;
                                            ?></td>
                                        <td class="jk-col-sumber-cell" title="<?php echo htmlspecialchars(isset($list_data->source_label) ? $list_data->source_label : '', ENT_QUOTES, 'UTF-8'); ?>">
                                            <?php
                                            echo isset($list_data->source_label) ? htmlspecialchars($list_data->source_label, ENT_QUOTES, 'UTF-8') : '';
                                            ?>
                                        </td>
                                        <td class="jk-col-unit-cell">
                                            <?php
                                            echo $list_data->kode_unit;
                                            ?>
                                        </td>
                                        <td class="jk-col-tanggal-cell">
                                            <?php
                                            echo '<div class="jk-tanggal-cell">';
                                            echo '<div class="jk-tanggal-date">' . date('d-m-Y', strtotime($list_data->tanggal)) . '</div>';
                                            if (!empty($list_data->is_editable) && !empty($list_data->id)) {
                                                echo '<div class="jk-tanggal-actions">';
                                                echo anchor(
                                                    site_url('Jurnal_kas/pemasukan_kas_update/' . $list_data->id),
                                                    '<i class="fa fa-pencil"></i><span>Ubah</span>',
                                                    array('title' => 'Ubah data', 'class' => 'jk-btn-action jk-btn-edit')
                                                );
                                                echo anchor(
                                                    site_url('jurnal_kas/delete/' . $list_data->id),
                                                    '<i class="fa fa-trash"></i><span>Hapus</span>',
                                                    array(
                                                        'title' => 'Hapus data',
                                                        'class' => 'jk-btn-action jk-btn-delete',
                                                        'onclick' => "return confirm('Anda yakin akan menghapus data ini?');",
                                                    )
                                                );
                                                echo '</div>';
                                            }
                                            echo '</div>';
                                            ?>
                                        </td>

                                        <td><?php
                                            echo $list_data->bukti;
                                            ?>
                                        </td>

                                        <td class="jk-col-keterangan-cell" title="<?php echo htmlspecialchars(isset($list_data->keterangan) ? $list_data->keterangan : '', ENT_QUOTES, 'UTF-8'); ?>">
                                            <?php
                                            echo $list_data->keterangan;
                                            ?>
                                        </td>

                                        <!-- Debet -->
                                        <td style="text-align:right">
                                            <?php
                                            if ($list_data->debet > 0) {
                                                echo number_format($list_data->debet, 2, ',', '.');
                                                $TOTAL_debet = $TOTAL_debet + $list_data->debet;
                                            } else {
                                                echo "";
                                            }
                                            ?>
                                        </td>

                                        <!-- Kredit -->
                                        <td style="text-align:right">
                                            <?php
                                            if ($list_data->kredit > 0) {
                                                echo number_format($list_data->kredit, 2, ',', '.');
                                                $TOTAL_kredit = $TOTAL_kredit + $list_data->kredit;
                                            } else {
                                                echo "";
                                            }
                                            ?>
                                        </td>


                                    </tr>

                                <?php
                                }
                                $SALDO_AKHIR = $TOTAL_debet - $TOTAL_kredit;

                                $Get_bulan_saldo = date("$Get_year_Tahun_ini-$Get_month_from_date-01");
                                $this->db->where('tanggal', $Get_bulan_saldo);
                                $GET_jurnal_kas_saldo_akhir_bulan = $this->db->get('jurnal_kas_saldo_akhir_bulan');
                                if ($GET_jurnal_kas_saldo_akhir_bulan->num_rows() > 0) {
                                    $this->Jurnal_kas_saldo_akhir_bulan_model->update(
                                        $GET_jurnal_kas_saldo_akhir_bulan->row()->id,
                                        array('saldo' => $SALDO_AKHIR)
                                    );
                                } else {
                                    $this->Jurnal_kas_saldo_akhir_bulan_model->insert(array(
                                        'tanggal' => $Get_bulan_saldo,
                                        'saldo' => $SALDO_AKHIR,
                                    ));
                                }
                                ?>

                            </tbody>
                        </table>
                        </div><!-- /.jurnal-kas-dt-responsive -->

                        <div class="jurnal-kas-summary-wrap">
                        <table class="table table-bordered jurnal-kas-grid-table jurnal-kas-summary-table mb-0">
                            <colgroup>
                                <col class="jk-w-no">
                                <col class="jk-w-sumber">
                                <col class="jk-w-unit">
                                <col class="jk-w-tanggal">
                                <col class="jk-w-bukti">
                                <col class="jk-w-keterangan">
                                <col class="jk-w-debet">
                                <col class="jk-w-kredit">
                            </colgroup>
                            <tbody>
                                <tr class="jk-summary-row">
                                    <td colspan="6" class="jk-summary-label text-center font-weight-bold">JUMLAH DEBET / KREDIT</td>
                                    <td class="text-right font-weight-bold"><?php echo number_format($TOTAL_debet, 2, ',', '.'); ?></td>
                                    <td class="text-right font-weight-bold"><?php echo number_format($TOTAL_kredit, 2, ',', '.'); ?></td>
                                </tr>
                                <tr class="jk-summary-row jk-summary-row-saldo">
                                    <td colspan="6" class="jk-summary-label text-center font-weight-bold">Saldo akhir Kas Bulan <?php echo bulan_teks($Get_month_from_date); ?></td>
                                    <td class="text-right font-weight-bold"></td>
                                    <td class="text-right font-weight-bold"><?php echo number_format($SALDO_AKHIR, 2, ',', '.'); ?></td>
                                </tr>
                                <tr class="jk-summary-row jk-summary-row-last">
                                    <td colspan="6" class="jk-summary-label text-center font-weight-bold">JUMLAH SEIMBANG</td>
                                    <td class="text-right font-weight-bold">
                                        <?php
                                        if ($SALDO_AKHIR >= 0) {
                                            echo number_format($TOTAL_debet, 2, ',', '.');
                                        } else {
                                            echo number_format($SALDO_AKHIR, 2, ',', '.');
                                        }
                                        ?>
                                    </td>
                                    <td class="text-right font-weight-bold">
                                        <?php
                                        if ($SALDO_AKHIR >= 0) {
                                            echo number_format($TOTAL_debet, 2, ',', '.');
                                        } else {
                                            echo number_format($SALDO_AKHIR, 2, ',', '.');
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        </div><!-- /.jurnal-kas-summary-wrap -->

                        <?php
                        $jk_sources_summary = isset($jurnal_kas_sources_summary) && is_array($jurnal_kas_sources_summary)
                            ? $jurnal_kas_sources_summary
                            : array();
                        $jk_bulan_label = isset($nama_bulan_id[(int) $compare_bulan_num])
                            ? $nama_bulan_id[(int) $compare_bulan_num] . ' ' . (int) $compare_tahun_num
                            : '';
                        ?>
                        <div class="jk-sources-reference mt-3" id="jk-sources-reference">
                            <div class="jk-sources-reference-header">
                                <h6 class="mb-1"><i class="fas fa-database"></i> Referensi Sumber Data Jurnal Kas</h6>
                                <p class="jk-ref-intro text-muted mb-2">
                                    Bulan terpilih: <strong><?php echo htmlspecialchars($jk_bulan_label, ENT_QUOTES, 'UTF-8'); ?></strong>.
                                    Klik kartu sumber di bawah untuk membuka detail data dalam modal.
                                </p>
                            </div>
                            <div class="row">
                                <?php foreach ($jk_sources_summary as $jk_src) {
                                    $jk_src_key = isset($jk_src['key']) ? $jk_src['key'] : '';
                                    $jk_src_label = isset($jk_src['label']) ? $jk_src['label'] : $jk_src_key;
                                    $jk_src_table = isset($jk_src['table']) ? $jk_src['table'] : '';
                                    $jk_src_detail = isset($jk_src['detail']) ? $jk_src['detail'] : '';
                                    $jk_src_menu = isset($jk_src['menu_path']) ? site_url($jk_src['menu_path']) : '#';
                                    $jk_src_count = isset($jk_src['jumlah_baris']) ? (int) $jk_src['jumlah_baris'] : 0;
                                    $jk_src_debet = isset($jk_src['total_debet']) ? (float) $jk_src['total_debet'] : 0;
                                    $jk_src_kredit = isset($jk_src['total_kredit']) ? (float) $jk_src['total_kredit'] : 0;
                                    $jk_card_class = ($jk_src_count > 0) ? 'jk-source-card has-data' : 'jk-source-card is-empty';
                                ?>
                                <div class="col-md-6 col-xl-4 mb-3">
                                    <div class="<?php echo $jk_card_class; ?>"
                                         role="button"
                                         tabindex="0"
                                         data-source-key="<?php echo htmlspecialchars($jk_src_key, ENT_QUOTES, 'UTF-8'); ?>"
                                         data-source-label="<?php echo htmlspecialchars($jk_src_label, ENT_QUOTES, 'UTF-8'); ?>"
                                         data-source-table="<?php echo htmlspecialchars($jk_src_table, ENT_QUOTES, 'UTF-8'); ?>"
                                         data-source-detail="<?php echo htmlspecialchars($jk_src_detail, ENT_QUOTES, 'UTF-8'); ?>">
                                        <div class="jk-source-card-title"><?php echo htmlspecialchars($jk_src_label, ENT_QUOTES, 'UTF-8'); ?></div>
                                        <div class="jk-source-card-meta"><span class="text-muted">Tabel:</span> <code><?php echo htmlspecialchars($jk_src_table, ENT_QUOTES, 'UTF-8'); ?></code></div>
                                        <div class="jk-source-card-detail"><?php echo htmlspecialchars($jk_src_detail, ENT_QUOTES, 'UTF-8'); ?></div>
                                        <div class="jk-source-card-stats">
                                            <span class="badge badge-primary"><?php echo $jk_src_count; ?> baris</span>
                                            <span class="jk-stat-debet">Debet: <?php echo number_format($jk_src_debet, 2, ',', '.'); ?></span>
                                            <span class="jk-stat-kredit">Kredit: <?php echo number_format($jk_src_kredit, 2, ',', '.'); ?></span>
                                        </div>
                                        <div class="jk-source-card-foot">
                                            <span class="jk-source-card-hint"><i class="fas fa-external-link-alt"></i> Klik untuk detail</span>
                                            <a href="<?php echo htmlspecialchars($jk_src_menu, ENT_QUOTES, 'UTF-8'); ?>" class="jk-source-card-menu" target="_blank" onclick="event.stopPropagation();">Buka menu</a>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>

                        </div><!-- /#jurnal-kas-table-wrap -->
                            </div><!-- /.tab-pane data jurnal kas -->

                            <?php
                            $compare_jurnal_kas_sections = array(
                                array('jenis' => 'data_manual', 'num' => '1', 'label' => 'Data Manual', 'subtitle' => 'Tabel CSV / database terpilih', 'badge' => 'compare-jurnal-kas-badge-manual', 'table' => 'table-compare-jurnal-kas-manual', 'theme' => 'primary', 'icon' => 'fa-database', 'col' => 'col-lg-6'),
                                array('jenis' => 'data_online', 'num' => '2', 'label' => 'Data Online', 'subtitle' => 'Data aplikasi dari berbagai tabel sumber (sama seperti Tab 1)', 'badge' => 'compare-jurnal-kas-badge-online', 'table' => 'table-compare-jurnal-kas-online', 'theme' => 'info', 'icon' => 'fa-cloud', 'col' => 'col-lg-6'),
                                array('jenis' => 'data_cocok', 'num' => '3', 'label' => 'Data Cocok (Manual & Online)', 'subtitle' => 'Tanggal, bukti, kode rekening, keterangan, debet, kredit sama', 'badge' => 'compare-jurnal-kas-badge-cocok', 'table' => 'table-compare-jurnal-kas-cocok', 'theme' => 'success', 'icon' => 'fa-check-circle', 'col' => 'col-lg-6'),
                                array('jenis' => 'manual_tidak_di_online', 'num' => '4', 'label' => 'Manual Tidak Ada di Online', 'subtitle' => 'Tidak cocok / tidak ditemukan di data aplikasi (Tab 1)', 'badge' => 'compare-jurnal-kas-badge-manual-miss', 'table' => 'table-compare-jurnal-kas-manual-miss', 'theme' => 'warning', 'icon' => 'fa-exclamation-triangle', 'col' => 'col-lg-6'),
                                array('jenis' => 'online_tidak_di_manual', 'num' => '5', 'label' => 'Online Tidak Ada di Manual', 'subtitle' => 'Ada di data aplikasi, tidak cocok di manual', 'badge' => 'compare-jurnal-kas-badge-online-miss', 'table' => 'table-compare-jurnal-kas-online-miss', 'theme' => 'cyan', 'icon' => 'fa-exchange-alt', 'col' => 'col-lg-12'),
                            );
                            ?>

                            <div class="tab-pane fade<?php echo $tab_compare_active ? ' show active' : ''; ?>" id="panel-compare-jurnal-kas" role="tabpanel" aria-labelledby="tab-compare-jurnal-kas">

                                <div class="row mb-2">
                                    <div class="col-md-12">
                                        <small class="text-muted d-block mb-2">
                                            Bandingkan data jurnal kas online (<strong>data aplikasi Tab 1 — berbagai tabel sumber</strong>)
                                            dengan tabel manual hasil upload CSV.
                                            Kolom CSV minimal: <strong>tanggal, bukti, keterangan, kode_rekening, debet, kredit</strong>.
                                            Pilih file CSV — tabel database akan langsung dibuat otomatis.
                                        </small>
                                        <label for="compare_jurnal_kas_csv_file" class="mb-1">Pilih file CSV (upload ke database menjadi tabel data)</label>
                                        <div class="d-flex flex-wrap align-items-end compare-csv-upload-row mb-3">
                                            <div class="custom-file custom-file-sm mb-0 compare-csv-file-wrap">
                                                <input type="file" class="custom-file-input" id="compare_jurnal_kas_csv_file" accept=".csv,text/csv">
                                                <label class="custom-file-label" for="compare_jurnal_kas_csv_file" data-browse="Pilih">Cari / pilih file CSV...</label>
                                            </div>
                                        </div>
                                        <div id="compare-jurnal-kas-csv-upload-info" class="alert alert-light border py-2 d-none mb-3">
                                            <div class="small mb-1"><span class="text-muted">File:</span> <strong id="compare-jurnal-kas-csv-filename">—</strong></div>
                                            <div class="small mb-1"><span class="text-muted">Tabel DB:</span> <strong id="compare-jurnal-kas-csv-tablename" class="text-primary">—</strong> <span class="text-muted" id="compare-jurnal-kas-csv-rowcount"></span></div>
                                            <button type="button" id="btn-compare-jurnal-kas-csv-cek-data" class="btn btn-outline-info btn-sm"><i class="fas fa-table"></i> Detail Tabel</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-end compare-toolbar-row flex-wrap">
                                    <div class="col-auto mb-2">
                                        <label for="compare_bulan_jurnal_kas" class="small mb-1">Bulan</label>
                                        <select id="compare_bulan_jurnal_kas" class="form-control form-control-sm compare-toolbar-control">
                                            <?php foreach ($nama_bulan_id as $num => $label_bulan) { ?>
                                                <option value="<?php echo (int) $num; ?>"<?php echo ((int) $num === (int) $compare_bulan_num) ? ' selected' : ''; ?>><?php echo htmlspecialchars($label_bulan, ENT_QUOTES, 'UTF-8'); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-auto mb-2">
                                        <label for="compare_tahun_jurnal_kas" class="small mb-1">Tahun</label>
                                        <select id="compare_tahun_jurnal_kas" class="form-control form-control-sm compare-toolbar-control">
                                            <?php for ($th = $gen_tahun_max; $th >= $gen_tahun_min; $th--) { ?>
                                                <option value="<?php echo (int) $th; ?>"<?php echo ((int) $th === (int) $compare_tahun_num) ? ' selected' : ''; ?>><?php echo (int) $th; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-auto mb-2">
                                        <label for="compare_tabel_jurnal_kas" class="small mb-1">Pilih tabel database</label>
                                        <select id="compare_tabel_jurnal_kas" class="form-control form-control-sm compare-toolbar-control compare-toolbar-tabel">
                                            <option value="">— Muat daftar tabel —</option>
                                        </select>
                                    </div>
                                    <div class="col-auto mb-2">
                                        <label class="small mb-1 d-block">&nbsp;</label>
                                        <div class="d-flex flex-wrap align-items-center">
                                            <button type="button" id="btn-compare-jurnal-kas" class="btn btn-info btn-sm d-none"><i class="fas fa-columns"></i> Compare</button>
                                            <button type="button" id="btn-compare-jurnal-kas-excel-all" class="btn btn-success btn-sm d-none ml-2"><i class="fa fa-file-excel-o"></i> Cetak ke Excel</button>
                                        </div>
                                    </div>
                                </div>

                                <div id="compare-jurnal-kas-tabel-actions" class="compare-jurnal-kas-tabel-info-box py-3 mb-3 d-none">
                                    <div id="compare-jurnal-kas-tabel-info-body" class="mb-2"></div>
                                    <div id="compare-jurnal-kas-tabel-import-note" class="small mb-2"></div>
                                    <div class="d-flex flex-wrap align-items-center">
                                        <button type="button" id="btn-compare-jurnal-kas-tabel-detail" class="btn btn-outline-primary btn-sm mr-2 mb-1">
                                            <i class="fas fa-table"></i> Detail Tabel
                                        </button>
                                        <button type="button" id="btn-compare-jurnal-kas-tabel-import" class="btn btn-success btn-sm mb-1" disabled>
                                            <i class="fas fa-database"></i> Proses Simpan ke Database
                                        </button>
                                    </div>
                                </div>

                                <div class="alert alert-secondary py-2" id="compare-jurnal-kas-info-ringkas">
                                    <strong>Bulan:</strong> <span id="compare-jurnal-kas-label-bulan">—</span>
                                    &nbsp;|&nbsp; <strong>Tabel manual:</strong> <span id="compare-jurnal-kas-label-tabel">—</span>
                                    &nbsp;|&nbsp; <strong>Manual:</strong> <span id="compare-jurnal-kas-count-manual">—</span>
                                    &nbsp;|&nbsp; <strong>Online:</strong> <span id="compare-jurnal-kas-count-online">—</span>
                                    &nbsp;|&nbsp; <strong>Cocok:</strong> <span id="compare-jurnal-kas-count-cocok">—</span>
                                    &nbsp;|&nbsp; <strong>Manual tidak di online:</strong> <span id="compare-jurnal-kas-count-manual-miss">—</span>
                                    &nbsp;|&nbsp; <strong>Online tidak di manual:</strong> <span id="compare-jurnal-kas-count-online-miss">—</span>
                                </div>
                                <div class="alert alert-info py-2 mb-3" id="compare-jurnal-kas-status">
                                    Pilih file CSV, bulan, tahun, dan tabel manual — klik <strong>Compare</strong>. Setelah selesai, tombol <strong>Cetak ke Excel</strong> dan tabel hasil akan muncul.
                                </div>
                                <div class="alert alert-warning py-2 mb-3 d-none" id="compare-jurnal-kas-field-info"></div>
                                <div class="alert alert-warning py-2 mb-3 d-none" id="compare-jurnal-kas-warnings"></div>

                                <div id="compare-jurnal-kas-results-panel" class="d-none">
                                    <h5 class="mb-3 text-primary"><i class="fas fa-chart-bar"></i> Hasil Komparasi Jurnal Kas</h5>
                                    <div class="row">
                                    <?php foreach ($compare_jurnal_kas_sections as $sec) { ?>
                                    <div class="<?php echo $sec['col']; ?> mb-3">
                                        <div class="compare-jurnal-kas-section-card compare-theme-<?php echo $sec['theme']; ?>">
                                            <div class="compare-jurnal-kas-section-header">
                                                <div class="compare-jurnal-kas-section-title">
                                                    <span class="compare-section-num"><?php echo $sec['num']; ?></span>
                                                    <i class="fas <?php echo $sec['icon']; ?> compare-section-icon"></i>
                                                    <div>
                                                        <div class="compare-section-label"><?php echo htmlspecialchars($sec['label'], ENT_QUOTES, 'UTF-8'); ?></div>
                                                        <div class="compare-section-subtitle"><?php echo htmlspecialchars($sec['subtitle'], ENT_QUOTES, 'UTF-8'); ?></div>
                                                    </div>
                                                </div>
                                                <div class="compare-jurnal-kas-section-actions">
                                                    <span id="<?php echo $sec['badge']; ?>" class="badge compare-section-badge">0</span>
                                                </div>
                                            </div>
                                            <?php if ($sec['jenis'] === 'data_manual') { ?>
                                            <div id="compare-jurnal-kas-publish-notif-manual" class="compare-jurnal-kas-publish-notif d-none">
                                                <i class="fas fa-check-circle"></i> TAMPIL PUBLISH LAPORAN
                                            </div>
                                            <div class="compare-jurnal-kas-section-toolbar px-3 pt-2 pb-0">
                                                <button type="button" id="btn-compare-jurnal-kas-publish-manual" class="btn btn-success btn-sm">
                                                    <i class="fas fa-upload"></i> Publish ke Laporan
                                                </button>
                                            </div>
                                            <?php } elseif ($sec['jenis'] === 'data_online') { ?>
                                            <div id="compare-jurnal-kas-publish-notif-online" class="compare-jurnal-kas-publish-notif d-none">
                                                <i class="fas fa-check-circle"></i> PUBLISH KE LAPORAN BUKU KAS
                                            </div>
                                            <div class="compare-jurnal-kas-section-toolbar px-3 pt-2 pb-0">
                                                <button type="button" id="btn-compare-jurnal-kas-publish-online" class="btn btn-info btn-sm">
                                                    <i class="fas fa-cloud-upload-alt"></i> Publish Online ke Laporan
                                                </button>
                                            </div>
                                            <?php } ?>
                                            <div class="compare-dt-wrap">
                                                <table id="<?php echo $sec['table']; ?>" class="table table-bordered table-sm compare-dt compare-jurnal-kas-dt" style="width:100%;">
                                                    <thead><tr><th>No</th><th>Tanggal</th><th>Bukti</th><th>Kode Rek</th><th>Keterangan</th><th>Debet</th><th>Kredit</th><th>Catatan</th></tr></thead>
                                                    <tbody></tbody>
                                                    <tfoot><tr class="compare-dt-total-row"><th colspan="5" class="text-right">Total</th><th class="compare-total-debet text-right">—</th><th class="compare-total-kredit text-right">—</th><th></th></tr></tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    </div>
                                </div>

                                <div class="modal fade" id="modal-compare-jurnal-kas-csv-preview" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document" style="max-width:95%;">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white py-2">
                                                <h5 class="modal-title">Detail Tabel CSV</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <div class="modal-body pb-2">
                                                <div class="d-flex flex-wrap align-items-center mb-2">
                                                    <p class="text-muted small mb-0 mr-3" id="compare-jurnal-kas-csv-preview-meta">Memuat...</p>
                                                    <button type="button" id="btn-compare-jurnal-kas-csv-preview-excel" class="btn btn-success btn-sm">
                                                        <i class="fa fa-file-excel-o"></i> Cetak ke Excel
                                                    </button>
                                                </div>
                                                <table id="table-compare-jurnal-kas-csv-preview" class="table table-bordered table-striped compare-jk-readable-table" style="width:100%;">
                                                    <thead><tr></tr></thead><tbody></tbody>
                                                    <tfoot><tr class="compare-dt-total-row"><th colspan="1" class="text-right csv-preview-total-label">Total</th><th class="csv-preview-total-debet text-right">—</th><th class="csv-preview-total-kredit text-right">—</th></tr></tfoot>
                                                </table>
                                            </div>
                                            <div class="modal-footer py-2"><button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Tutup</button></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="modal-compare-jurnal-kas-tabel-detail" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document" style="max-width:95%;">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info text-white py-2">
                                                <h5 class="modal-title">Detail Tabel — Import Jurnal Kas</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <div class="modal-body pb-2">
                                                <div class="d-flex flex-wrap align-items-center mb-2">
                                                    <p class="text-muted small mb-0 mr-3" id="compare-jurnal-kas-tabel-detail-meta">Memuat...</p>
                                                    <button type="button" id="btn-compare-jurnal-kas-tabel-detail-excel" class="btn btn-success btn-sm">
                                                        <i class="fa fa-file-excel-o"></i> Cetak ke Excel
                                                    </button>
                                                </div>
                                                <table id="table-compare-jurnal-kas-tabel-detail" class="table table-bordered table-striped compare-jk-readable-table" style="width:100%;">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Tanggal</th>
                                                            <th>Bukti</th>
                                                            <th>Keterangan</th>
                                                            <th>Kode Rekening</th>
                                                            <th>Debet</th>
                                                            <th>Kredit</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                    <tfoot>
                                                        <tr class="compare-dt-total-row">
                                                            <th colspan="5" class="text-right">Total</th>
                                                            <th class="compare-total-debet text-right">—</th>
                                                            <th class="compare-total-kredit text-right">—</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            <div class="modal-footer py-2"><button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Tutup</button></div>
                                        </div>
                                    </div>
                                </div>

                            </div><!-- /.tab-pane compare -->

                            <div class="tab-pane fade<?php echo $tab_setting_active ? ' show active' : ''; ?>" id="panel-setting-jurnal-kas" role="tabpanel" aria-labelledby="tab-setting-jurnal-kas">
                                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 jurnal-kas-tab1-toolbar">
                                    <div>
                                        <h5 class="mb-0 text-primary jk-toolbar-title"><strong>Setting Jurnal Kas Laporan</strong> <span class="text-muted" id="setting-jurnal-kas-label-bulan">(Bulan : <?php echo bulan_teks($Get_month_from_date) . ' ' . $Get_year_Tahun_ini; ?>)</span></h5>
                                        <div class="jk-toolbar-hint text-muted">Pilih sumber data — preview tanpa input. Publish untuk tabel tertentu agar tampil di halaman Lap Jurnal Kas.</div>
                                        <div id="setting-jurnal-kas-loading" class="text-info d-none mt-1"><i class="fas fa-spinner fa-spin"></i> Memuat data setting...</div>
                                        <div id="setting-jurnal-kas-publish-info" class="small text-muted mt-1"></div>
                                    </div>
                                    <div class="d-flex flex-wrap align-items-center mt-2 mt-md-0">
                                        <button type="button" class="btn btn-primary mr-2 mb-2 mb-md-0 d-none" id="btn-setting-jurnal-kas-publish">
                                            <i class="fa fa-upload"></i> Publish
                                        </button>
                                        <a href="<?php echo htmlspecialchars($url_lap_jurnal_kas, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-outline-info mr-2 mb-2 mb-md-0" target="_blank" id="btn-setting-jurnal-kas-open-lap">
                                            <i class="fa fa-external-link"></i> Buka Lap Jurnal Kas
                                        </a>
                                        <button type="button" class="btn btn-success mb-2 mb-md-0" id="btn-setting-jurnal-kas-excel">
                                            <i class="fa fa-file-excel-o"></i> Cetak ke Excel
                                        </button>
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-end setting-toolbar-row flex-wrap">
                                    <div class="col-auto mb-2">
                                        <label for="setting_bulan_jurnal_kas" class="small mb-1">Bulan</label>
                                        <select id="setting_bulan_jurnal_kas" class="form-control form-control-sm setting-toolbar-control">
                                            <?php foreach ($nama_bulan_id as $num => $label_bulan) { ?>
                                                <option value="<?php echo (int) $num; ?>"<?php echo ((int) $num === (int) $compare_bulan_num) ? ' selected' : ''; ?>><?php echo htmlspecialchars($label_bulan, ENT_QUOTES, 'UTF-8'); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-auto mb-2">
                                        <label for="setting_tahun_jurnal_kas" class="small mb-1">Tahun</label>
                                        <select id="setting_tahun_jurnal_kas" class="form-control form-control-sm setting-toolbar-control">
                                            <?php for ($th = (int) $gen_tahun_min; $th <= (int) $gen_tahun_max; $th++) { ?>
                                                <option value="<?php echo (int) $th; ?>"<?php echo ((int) $th === (int) $compare_tahun_num) ? ' selected' : ''; ?>><?php echo (int) $th; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-auto mb-2">
                                        <label for="setting_sumber_jurnal_kas" class="small mb-1">Ambil data / Pilih tabel</label>
                                        <select id="setting_sumber_jurnal_kas" class="form-control form-control-sm setting-toolbar-sumber">
                                            <option value="asli">Jurnal_kas Asli</option>
                                        </select>
                                    </div>
                                </div>

                                <div id="setting-jurnal-kas-table-wrap">
                                    <div class="jurnal-kas-dt-responsive">
                                        <table id="settingJurnalKasMainTable" class="table table-bordered jurnal-kas-grid-table mb-0">
                                            <colgroup>
                                                <col class="jk-w-no">
                                                <col class="jk-w-sumber">
                                                <col class="jk-w-unit">
                                                <col class="jk-w-tanggal">
                                                <col class="jk-w-bukti">
                                                <col class="jk-w-keterangan">
                                                <col class="jk-w-debet">
                                                <col class="jk-w-kredit">
                                            </colgroup>
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Sumber</th>
                                                    <th>Unit</th>
                                                    <th>Tanggal</th>
                                                    <th>Bukti</th>
                                                    <th>Keterangan</th>
                                                    <th>Debet</th>
                                                    <th>Kredit</th>
                                                </tr>
                                            </thead>
                                            <tbody id="setting-jurnal-kas-tbody"></tbody>
                                        </table>
                                    </div>
                                    <div class="jurnal-kas-summary-wrap">
                                        <table class="table table-bordered jurnal-kas-grid-table jurnal-kas-summary-table mb-0">
                                            <colgroup>
                                                <col class="jk-w-no">
                                                <col class="jk-w-sumber">
                                                <col class="jk-w-unit">
                                                <col class="jk-w-tanggal">
                                                <col class="jk-w-bukti">
                                                <col class="jk-w-keterangan">
                                                <col class="jk-w-debet">
                                                <col class="jk-w-kredit">
                                            </colgroup>
                                            <tbody>
                                                <tr class="jk-summary-row">
                                                    <td colspan="6" class="jk-summary-label text-center font-weight-bold">JUMLAH DEBET / KREDIT</td>
                                                    <td class="text-right font-weight-bold" id="setting-jk-total-debet">—</td>
                                                    <td class="text-right font-weight-bold" id="setting-jk-total-kredit">—</td>
                                                </tr>
                                                <tr class="jk-summary-row jk-summary-row-saldo">
                                                    <td colspan="6" class="jk-summary-label text-center font-weight-bold">Saldo akhir Kas Bulan <span id="setting-jk-bulan-nama"><?php echo bulan_teks($Get_month_from_date); ?></span></td>
                                                    <td class="text-right font-weight-bold"></td>
                                                    <td class="text-right font-weight-bold" id="setting-jk-saldo-akhir">—</td>
                                                </tr>
                                                <tr class="jk-summary-row jk-summary-row-last">
                                                    <td colspan="6" class="jk-summary-label text-center font-weight-bold">JUMLAH SEIMBANG</td>
                                                    <td class="text-right font-weight-bold" id="setting-jk-seimbang-debet">—</td>
                                                    <td class="text-right font-weight-bold" id="setting-jk-seimbang-kredit">—</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div><!-- /.tab-pane setting -->

                        </div><!-- /.tab-content -->
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </section>

    <?php if ($can_input_jurnal_kas) { ?>
    <div class="modal fade" id="modal-jurnal-kas-input" tabindex="-1" role="dialog" aria-labelledby="modal-jurnal-kas-input-title" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document" style="max-width:960px;">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white py-2">
                    <h5 class="modal-title" id="modal-jurnal-kas-input-title"><i class="fa fa-edit"></i> Input Jurnal Kas</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="form-jurnal-kas-input-modal" autocomplete="off">
                    <div class="modal-body">
                        <div id="jurnal-kas-input-modal-errors" class="alert alert-danger d-none" role="alert"></div>
                        <div class="row">
                            <div class="form-group col-md-3 col-sm-6 mb-2">
                                <label for="modal_jk_tgl_po">Tanggal <span class="text-danger">*</span></label>
                                <div class="input-group date" id="modal_jk_tgl_po_wrap" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#modal_jk_tgl_po_wrap" id="modal_jk_tgl_po" name="tgl_po" value="<?php echo htmlspecialchars($modal_jk_tanggal_default, ENT_QUOTES, 'UTF-8'); ?>" required />
                                    <div class="input-group-append" data-target="#modal_jk_tgl_po_wrap" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3 col-sm-6 mb-2">
                                <label for="modal_jk_bukti">Bukti</label>
                                <input type="text" name="bukti" id="modal_jk_bukti" class="form-control" placeholder="Opsional">
                            </div>
                            <div class="form-group col-md-3 col-sm-6 mb-2">
                                <label for="modal_jk_pl">PL</label>
                                <select name="pl" id="modal_jk_pl" class="form-control modal-jk-select2" style="width:100%;">
                                    <option value="">Pilih Kode PL</option>
                                    <?php foreach ($list_kode_pl as $pl_row) { ?>
                                        <option value="<?php echo htmlspecialchars($pl_row->kode_pl, ENT_QUOTES, 'UTF-8'); ?>">
                                            <?php echo strtoupper($pl_row->kode_pl) . ' ==> ' . strtoupper($pl_row->keterangan); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3 col-sm-6 mb-2">
                                <label for="modal_jk_kode_rekening">Kode Rekening <span class="text-danger">*</span></label>
                                <input type="text" name="kode_rekening" id="modal_jk_kode_rekening" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4 col-sm-6 mb-2">
                                <label for="modal_jk_kode_akun">Kode Akun <span class="text-danger">*</span></label>
                                <select name="kode_akun" id="modal_jk_kode_akun" class="form-control modal-jk-select2" style="width:100%;" required>
                                    <option value="">Pilih Kode Akun</option>
                                    <?php foreach ($list_kode_akun as $akun_row) { ?>
                                        <option value="<?php echo htmlspecialchars($akun_row->kode_akun, ENT_QUOTES, 'UTF-8'); ?>">
                                            <?php echo strtoupper($akun_row->kode_akun) . ' ==> ' . strtoupper($akun_row->nama_akun); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group col-md-4 col-sm-6 mb-2">
                                <label for="modal_jk_status_proses">Debet / Kredit <span class="text-danger">*</span></label>
                                <select name="status_proses" id="modal_jk_status_proses" class="form-control" required>
                                    <option value=""></option>
                                    <option value="debet">Debet</option>
                                    <option value="kredit">Kredit</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4 col-sm-12 mb-2">
                                <label for="modal_jk_nominal_penyesuaian">Nominal <span class="text-danger">*</span></label>
                                <input type="number" name="nominal_penyesuaian" id="modal_jk_nominal_penyesuaian" class="form-control" min="0" step="any" required>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label for="modal_jk_keterangan">Keterangan</label>
                            <input type="text" name="keterangan" id="modal_jk_keterangan" class="form-control" placeholder="Opsional">
                        </div>
                    </div>
                    <div class="modal-footer py-2">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btn-jurnal-kas-input-simpan">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php } ?>

    <div class="modal fade" id="modal-jk-source-detail" tabindex="-1" role="dialog" aria-labelledby="modal-jk-source-title" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document" style="max-width:1200px;">
            <div class="modal-content">
                <div class="modal-header bg-info text-white py-2">
                    <div>
                        <h5 class="modal-title mb-0" id="modal-jk-source-title">Detail Sumber Data</h5>
                        <div class="small mt-1" id="modal-jk-source-meta"></div>
                    </div>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-2" id="modal-jk-source-desc"></p>
                    <div class="mb-2"><span class="badge badge-primary" id="modal-jk-source-count">0 baris</span></div>
                    <div class="table-responsive jk-source-modal-table-wrap">
                        <table id="table-jk-source-detail" class="table table-bordered table-striped jk-source-detail-table mb-0" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Unit</th>
                                    <th>Bukti</th>
                                    <th>Keterangan</th>
                                    <th>Debet</th>
                                    <th>Kredit</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    div.dataTables_wrapper { width: 100%; margin: 0 auto; }
    .nav-tabs.jurnal-kas-tabs { border-bottom: 2px solid #dc3545; margin-bottom: 15px; }
    .nav-tabs.jurnal-kas-tabs .nav-link { background: #fff; border: 2px solid #dc3545; border-bottom: none; color: #888; margin-right: 4px; border-radius: 4px 4px 0 0; opacity: .75; font-size: 18px; padding: 10px 16px; }
    #panel-jurnal-kas-data,
    #panel-compare-jurnal-kas,
    #panel-setting-jurnal-kas {
        font-size: 18px;
    }
    #panel-compare-jurnal-kas label.small,
    #panel-compare-jurnal-kas .text-muted,
    #panel-compare-jurnal-kas small {
        font-size: 17px !important;
    }
    #panel-compare-jurnal-kas .form-control-sm {
        font-size: 17px;
        height: auto;
        padding: 6px 10px;
    }
    #panel-compare-jurnal-kas .btn-sm {
        font-size: 17px;
        padding: 6px 12px;
    }
    .compare-jk-readable-table,
    .compare-jk-readable-table thead th,
    .compare-jk-readable-table tbody td {
        font-size: 18px !important;
    }
    .compare-jk-readable-table thead th,
    .compare-jk-readable-table tbody td {
        padding: 10px 12px !important;
    }
    .nav-tabs.jurnal-kas-tabs .nav-link.active { background: #007bff; color: #000; font-weight: bold; opacity: 1; }
    .compare-toolbar-row .compare-toolbar-control { width: 110px; min-width: 110px; }
    .setting-toolbar-row .setting-toolbar-control { width: 110px; min-width: 110px; }
    #setting_tahun_jurnal_kas.setting-toolbar-control { width: 88px; min-width: 88px; }
    #setting_sumber_jurnal_kas.setting-toolbar-sumber { width: 360px; min-width: 270px; max-width: 480px; }
    #setting-jurnal-kas-table-wrap {
        border: 1px solid #ced4da;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
    }
    #compare_tahun_jurnal_kas.compare-toolbar-control { width: 88px; min-width: 88px; }
    #compare_tabel_jurnal_kas.compare-toolbar-tabel { width: 360px; min-width: 270px; max-width: 480px; }
    .compare-jurnal-kas-tabel-info-box {
        background: #eef7ff !important;
        border: 1px solid #b8d9f5 !important;
        border-left: 4px solid #1976d2 !important;
        border-radius: 6px;
        color: #1a2e44 !important;
        box-shadow: 0 1px 4px rgba(25, 118, 210, 0.08);
    }
    .compare-jurnal-kas-tabel-info-box .compare-info-title {
        font-weight: 600;
        margin-bottom: 6px;
        color: #0d3d6b !important;
        font-size: 18px;
    }
    .compare-jurnal-kas-tabel-info-box .compare-info-line {
        font-size: 17px;
        line-height: 1.6;
        color: #1a2e44 !important;
    }
    .compare-jurnal-kas-tabel-info-box .compare-info-line strong {
        color: #0d3d6b !important;
    }
    .compare-jurnal-kas-tabel-info-box .compare-info-line code {
        background: #fff;
        color: #1565c0;
        border: 1px solid #cfe4f8;
        padding: 1px 4px;
        border-radius: 3px;
    }
    .compare-jurnal-kas-tabel-info-box #compare-jurnal-kas-tabel-import-note {
        color: #1a2e44 !important;
    }
    .compare-jurnal-kas-tabel-info-box #compare-jurnal-kas-tabel-import-note.text-success {
        color: #1b5e20 !important;
        font-weight: 600;
    }
    .compare-jurnal-kas-tabel-info-box #compare-jurnal-kas-tabel-import-note.text-danger {
        color: #b71c1c !important;
        font-weight: 600;
    }
    .compare-jurnal-kas-tabel-info-box #compare-jurnal-kas-tabel-import-note.text-warning {
        color: #e65100 !important;
    }
    .compare-jurnal-kas-tabel-info-box .compare-info-title.text-warning {
        color: #e65100 !important;
    }
    .compare-jurnal-kas-tabel-info-box .compare-info-title.text-danger {
        color: #b71c1c !important;
    }
    .compare-jurnal-kas-tabel-info-box .text-warning {
        color: #e65100 !important;
    }
    .compare-jurnal-kas-tabel-info-box .text-danger {
        color: #b71c1c !important;
    }
    .compare-jurnal-kas-tabel-info-box .btn-outline-primary {
        background: #fff !important;
        color: #1565c0 !important;
        border-color: #1565c0 !important;
    }
    .compare-jurnal-kas-tabel-info-box .btn-outline-primary:hover:not(:disabled) {
        background: #1565c0 !important;
        color: #fff !important;
    }
    .compare-jurnal-kas-tabel-info-box .btn-success {
        background: #2e7d32 !important;
        border-color: #2e7d32 !important;
        color: #fff !important;
    }
    .compare-jurnal-kas-tabel-info-box .btn-success:hover:not(:disabled) {
        background: #1b5e20 !important;
        border-color: #1b5e20 !important;
        color: #fff !important;
    }
    .compare-jurnal-kas-tabel-info-box .btn:disabled {
        opacity: 0.55;
        cursor: not-allowed;
    }
    .compare-csv-file-wrap { max-width: 520px; min-width: 280px; flex: 0 1 520px; }
    #compare-jurnal-kas-results-panel { margin-top: 8px; animation: compareJurnalKasFadeIn .35s ease; }
    @keyframes compareJurnalKasFadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    .compare-jurnal-kas-section-card { border-radius: 10px; border: 1px solid #dee2e6; background: #fff; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.05); display: flex; flex-direction: column; height: 100%; }
    .compare-jurnal-kas-section-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; padding: 10px 14px; border-bottom: 1px solid rgba(0,0,0,.08); }
    .compare-jurnal-kas-section-title { display: flex; align-items: center; gap: 10px; }
    .compare-section-num { display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 50%; background: rgba(0,0,0,.08); font-weight: 700; font-size: 17px; }
    .compare-section-label { font-weight: 700; font-size: 19px; line-height: 1.3; }
    .compare-section-subtitle { font-size: 16px; color: #6c757d; }
    .compare-section-badge { font-size: 17px; margin-right: 6px; }
    .compare-theme-primary .compare-jurnal-kas-section-header { background: linear-gradient(90deg, #e7f1ff, #fff); border-left: 4px solid #007bff; }
    .compare-theme-info .compare-jurnal-kas-section-header { background: linear-gradient(90deg, #e8f7fa, #fff); border-left: 4px solid #17a2b8; }
    .compare-theme-success .compare-jurnal-kas-section-header { background: linear-gradient(90deg, #e8f5e9, #fff); border-left: 4px solid #28a745; }
    .compare-theme-warning .compare-jurnal-kas-section-header { background: linear-gradient(90deg, #fff8e1, #fff); border-left: 4px solid #ffc107; }
    .compare-theme-cyan .compare-jurnal-kas-section-header { background: linear-gradient(90deg, #e0f7fa, #fff); border-left: 4px solid #17a2b8; }
    .compare-jurnal-kas-publish-notif {
        margin: 10px 14px 0;
        padding: 10px 14px;
        background: #d4edda;
        border: 1px solid #28a745;
        color: #155724;
        text-align: center;
        font-weight: 700;
        border-radius: 6px;
        font-size: 16px;
        letter-spacing: 0.3px;
    }
    .compare-jurnal-kas-section-toolbar { border-bottom: 1px solid rgba(0,0,0,.06); }
    .compare-dt-wrap .dataTables_wrapper { font-size: 18px; }
    .compare-dt-wrap table.dataTable thead th { background: #f8f9fa; font-size: 18px; white-space: nowrap; padding: 10px 12px; }
    .compare-dt-wrap table.dataTable tbody td { font-size: 18px; padding: 10px 12px; vertical-align: middle; line-height: 1.5; }
    .compare-dt-wrap .dataTables_wrapper .dataTables_length,
    .compare-dt-wrap .dataTables_wrapper .dataTables_filter,
    .compare-dt-wrap .dataTables_wrapper .dataTables_info,
    .compare-dt-wrap .dataTables_wrapper .dataTables_paginate {
        font-size: 17px;
        padding: 10px 12px;
    }
    .compare-dt-wrap .dataTables_wrapper .dataTables_length select,
    .compare-dt-wrap .dataTables_wrapper .dataTables_filter input {
        font-size: 17px;
        padding: 5px 10px;
        height: auto;
    }
    .compare-dt-wrap .dataTables_wrapper .dataTables_paginate .paginate_button {
        font-size: 17px;
        padding: 5px 12px;
    }
    .compare-dt-wrap .text-amount-debet { color: #155724; font-weight: 600; font-size: 18px; }
    .compare-dt-wrap .text-amount-kredit { color: #0c5460; font-weight: 600; font-size: 18px; }
    .compare-dt-wrap .text-catatan { font-size: 16px; color: #856404; }
    #compare-jurnal-kas-info-ringkas {
        font-size: 18px;
    }
    .compare-dt-total-row th { background: #fff3cd !important; font-weight: 700; }
    .jurnal-kas-tab1-toolbar {
        padding: 12px 14px;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 6px;
    }
    #panel-jurnal-kas-data .btn {
        font-size: 17px;
        padding: 8px 14px;
    }
    #jurnal-kas-month-loading {
        font-size: 19px;
    }
    #modal-jurnal-kas-input .modal-header { border-bottom: none; }
    #modal-jurnal-kas-input .modal-content { border: none; box-shadow: 0 8px 30px rgba(0,0,0,.18); border-radius: 8px; overflow: hidden; }
    #modal-jurnal-kas-input .modal-body { background: #fafbfc; }
    #modal-jurnal-kas-input label { font-weight: 600; font-size: 13px; margin-bottom: 4px; }
    #modal-jurnal-kas-input .select2-container { width: 100% !important; }

    /* Jurnal Kas Tab 1 — datatable nyaman dibaca */
    #jurnal-kas-table-wrap {
        border: 1px solid #ced4da;
        border-radius: 8px;
        padding: 0;
        background: #fff;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0, 0, 0, .06);
    }
    #jurnal-kas-table-wrap .jurnal-kas-dt-responsive {
        overflow-x: auto;
        overflow-y: visible;
    }
    .jurnal-kas-tab1-toolbar .jk-toolbar-title {
        font-size: 1.75rem;
        line-height: 1.35;
    }
    .jurnal-kas-tab1-toolbar .jk-toolbar-hint {
        font-size: 19px;
        line-height: 1.5;
        margin-top: 4px;
    }
    #jurnal-kas-table-wrap .jurnal-kas-grid-table {
        table-layout: fixed;
        width: 100%;
        min-width: 1100px;
        border-collapse: collapse;
        margin-bottom: 0;
        font-size: 22px;
        line-height: 1.55;
        color: #212529;
    }
    #jurnal-kas-table-wrap .jk-col-sumber-cell,
    #jurnal-kas-table-wrap .jk-col-unit-cell,
    #jurnal-kas-table-wrap .jk-col-keterangan-cell {
        font-size: 13px;
        line-height: 1.3;
        color: #212529;
        vertical-align: middle;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        word-break: normal;
        overflow-wrap: normal;
        max-width: 0;
        padding: 10px 8px !important;
    }
    #jurnal-kas-table-wrap col.jk-w-no { width: 48px; }
    #jurnal-kas-table-wrap col.jk-w-sumber { width: 100px; }
    #jurnal-kas-table-wrap col.jk-w-unit { width: 58px; }
    #jurnal-kas-table-wrap col.jk-w-tanggal { width: 112px; }
    #jurnal-kas-table-wrap col.jk-w-bukti { width: 58px; }
    #jurnal-kas-table-wrap col.jk-w-keterangan { width: 540px; }
    #jurnal-kas-table-wrap col.jk-w-debet { width: 128px; }
    #jurnal-kas-table-wrap col.jk-w-kredit { width: 128px; }
    #jurnal-kas-table-wrap .jurnal-kas-grid-table thead th {
        background: linear-gradient(180deg, #e9f5ec 0%, #d8eddc 100%);
        border: 1px solid #6c9a74;
        color: #1b4332;
        font-size: 20px;
        font-weight: 700;
        padding: 14px 12px;
        vertical-align: middle;
        white-space: nowrap;
        letter-spacing: 0.02em;
        text-transform: uppercase;
    }
    #jurnal-kas-table-wrap .jurnal-kas-grid-table tbody td {
        border: 1px solid #ced4da;
        font-size: 22px;
        padding: 13px 12px;
        vertical-align: middle;
        word-wrap: break-word;
        overflow-wrap: break-word;
        background: #fff;
    }
    #jurnal-kas-table-wrap .jurnal-kas-grid-table tbody tr:nth-child(even) td {
        background: #f8faf9;
    }
    #jurnal-kas-table-wrap .jurnal-kas-grid-table tbody tr:hover td {
        background: #eef6ff;
    }
    #jurnal-kas-table-wrap .jurnal-kas-grid-table tbody tr:first-child td {
        background: #fff9e6;
        font-weight: 600;
    }
    #jurnal-kas-table-wrap .jurnal-kas-grid-table tbody tr:first-child:hover td {
        background: #fff3cc;
    }
    #jurnal-kas-table-wrap .jurnal-kas-grid-table tbody td:nth-child(1),
    #jurnal-kas-table-wrap .jurnal-kas-grid-table thead th:nth-child(1) { text-align: center; }
    #jurnal-kas-table-wrap .jurnal-kas-grid-table tbody td:nth-child(2),
    #jurnal-kas-table-wrap .jurnal-kas-grid-table thead th:nth-child(2),
    #jurnal-kas-table-wrap .jurnal-kas-grid-table tbody td:nth-child(3),
    #jurnal-kas-table-wrap .jurnal-kas-grid-table thead th:nth-child(3),
    #jurnal-kas-table-wrap .jurnal-kas-grid-table tbody td:nth-child(6),
    #jurnal-kas-table-wrap .jurnal-kas-grid-table thead th:nth-child(6) {
        text-align: left;
        white-space: nowrap;
        line-height: 1.3;
        vertical-align: middle;
    }
    #jurnal-kas-table-wrap .jurnal-kas-grid-table thead th:nth-child(2),
    #jurnal-kas-table-wrap .jurnal-kas-grid-table thead th:nth-child(3),
    #jurnal-kas-table-wrap .jurnal-kas-grid-table thead th:nth-child(6) {
        font-size: 15px;
        padding: 12px 8px;
    }
    #jurnal-kas-table-wrap .jk-col-tanggal-cell {
        padding: 8px 6px !important;
        vertical-align: middle;
    }
    #jurnal-kas-table-wrap .jk-tanggal-cell {
        display: flex;
        flex-direction: column;
        gap: 6px;
        min-width: 0;
    }
    #jurnal-kas-table-wrap .jk-tanggal-date {
        font-size: 17px;
        font-weight: 600;
        white-space: nowrap;
        color: #212529;
    }
    #jurnal-kas-table-wrap .jk-tanggal-actions {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        flex-wrap: nowrap;
    }
    #jurnal-kas-table-wrap a.jk-btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 3px;
        padding: 3px 8px;
        min-height: 28px;
        font-size: 12px;
        font-weight: 600;
        line-height: 1.25;
        border-radius: 4px;
        border: 1px solid transparent;
        text-decoration: none !important;
        white-space: nowrap;
        transition: background-color .15s ease, color .15s ease, border-color .15s ease;
    }
    #jurnal-kas-table-wrap a.jk-btn-action i {
        font-size: 12px;
        line-height: 1;
    }
    #jurnal-kas-table-wrap a.jk-btn-edit {
        color: #856404;
        background: #fff8e1;
        border-color: #ffca28;
    }
    #jurnal-kas-table-wrap a.jk-btn-edit:hover {
        color: #212529;
        background: #ffc107;
        border-color: #ffb300;
    }
    #jurnal-kas-table-wrap a.jk-btn-delete {
        color: #842029;
        background: #fff5f5;
        border-color: #f1aeb5;
    }
    #jurnal-kas-table-wrap a.jk-btn-delete:hover {
        color: #fff;
        background: #dc3545;
        border-color: #dc3545;
    }
    #jurnal-kas-table-wrap .jurnal-kas-grid-table thead th:nth-child(4) {
        font-size: 16px;
        padding: 12px 6px;
    }
    #jurnal-kas-table-wrap .jurnal-kas-grid-table tbody td:nth-child(7),
    #jurnal-kas-table-wrap .jurnal-kas-grid-table tbody td:nth-child(8),
    #jurnal-kas-table-wrap .jurnal-kas-grid-table thead th:nth-child(7),
    #jurnal-kas-table-wrap .jurnal-kas-grid-table thead th:nth-child(8) {
        text-align: right;
        white-space: nowrap;
        font-variant-numeric: tabular-nums;
        font-weight: 600;
        font-size: 22px;
    }
    #jurnal-kas-table-wrap .jurnal-kas-summary-wrap {
        border-top: 2px solid #6c9a74;
        overflow-x: auto;
    }
    #jurnal-kas-table-wrap .jurnal-kas-summary-table tbody td {
        background: #f1f3f5;
        border: 1px solid #adb5bd;
        font-size: 22px;
        font-weight: 700;
        padding: 14px 12px;
        font-variant-numeric: tabular-nums;
    }
    #jurnal-kas-table-wrap .jurnal-kas-summary-table .jk-summary-label {
        text-align: center !important;
        font-weight: 700;
        vertical-align: middle;
        font-size: 22px;
    }
    .jk-sources-reference {
        border-top: 2px solid #dee2e6;
        padding: 18px 16px 10px;
        background: #f8fafb;
    }
    .jk-sources-reference-header h6 {
        font-size: 22px;
        font-weight: 700;
        color: #1b4332;
    }
    .jk-sources-reference-header .jk-ref-intro {
        font-size: 20px;
        line-height: 1.55;
    }
    .jk-source-card {
        height: 100%;
        border: 1px solid #ced4da;
        border-radius: 8px;
        background: #fff;
        padding: 14px 16px;
        cursor: pointer;
        transition: box-shadow .15s ease, border-color .15s ease, transform .1s ease;
    }
    .jk-source-card:hover,
    .jk-source-card:focus {
        outline: none;
        border-color: #17a2b8;
        box-shadow: 0 4px 14px rgba(23, 162, 184, .18);
        transform: translateY(-1px);
    }
    .jk-source-card.is-empty {
        opacity: .82;
        background: #fafafa;
    }
    .jk-source-card.has-data {
        border-left: 4px solid #17a2b8;
    }
    .jk-source-card-title {
        font-size: 20px;
        font-weight: 700;
        color: #212529;
        margin-bottom: 10px;
    }
    .jk-source-card-meta {
        font-size: 18px;
        margin-bottom: 10px;
    }
    .jk-source-card-meta code {
        font-size: 17px;
        color: #0c5460;
    }
    .jk-source-card-detail {
        font-size: 18px;
        color: #495057;
        line-height: 1.55;
        margin-bottom: 12px;
        min-height: 48px;
    }
    .jk-source-card-stats {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
        font-size: 18px;
        margin-bottom: 12px;
    }
    .jk-source-card-stats .badge {
        font-size: 17px;
        padding: 7px 12px;
    }
    .jk-source-card-stats .jk-stat-debet { color: #155724; font-weight: 600; }
    .jk-source-card-stats .jk-stat-kredit { color: #0c5460; font-weight: 600; }
    .jk-source-card-foot {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 17px;
        border-top: 1px dashed #dee2e6;
        padding-top: 12px;
    }
    .jk-source-card-hint { color: #17a2b8; font-weight: 600; }
    .jk-source-card-menu {
        color: #6c757d;
        text-decoration: underline;
    }
    .jk-source-card-menu:hover { color: #0056b3; }
    #modal-jk-source-detail .modal-title {
        font-size: 1.65rem;
        font-weight: 700;
    }
    #modal-jk-source-detail #modal-jk-source-meta {
        font-size: 19px;
    }
    #modal-jk-source-detail #modal-jk-source-desc {
        font-size: 20px;
        line-height: 1.55;
    }
    #modal-jk-source-detail #modal-jk-source-count {
        font-size: 19px;
        padding: 8px 14px;
    }
    #modal-jk-source-detail .dataTables_wrapper,
    #modal-jk-source-detail .dataTables_wrapper .dataTables_length,
    #modal-jk-source-detail .dataTables_wrapper .dataTables_filter,
    #modal-jk-source-detail .dataTables_wrapper .dataTables_info,
    #modal-jk-source-detail .dataTables_wrapper .dataTables_paginate {
        font-size: 18px;
    }
    #modal-jk-source-detail .dataTables_wrapper .dataTables_length select,
    #modal-jk-source-detail .dataTables_wrapper .dataTables_filter input {
        font-size: 18px;
        padding: 6px 12px;
    }
    #modal-jk-source-detail .jk-source-detail-table {
        font-size: 20px;
    }
    #modal-jk-source-detail .jk-source-detail-table thead th {
        background: #e9f5ec;
        font-size: 19px;
        font-weight: 700;
        padding: 12px 11px;
    }
    #modal-jk-source-detail .jk-source-detail-table tbody td {
        font-size: 20px;
        padding: 11px 11px;
        vertical-align: top;
        line-height: 1.5;
    }
    #modal-jk-source-detail .jk-source-detail-table tbody td:nth-child(6),
    #modal-jk-source-detail .jk-source-detail-table tbody td:nth-child(7) {
        text-align: right;
        white-space: nowrap;
        font-weight: 600;
    }
    #jurnal-kas-table-wrap .jurnal-kas-summary-table .jk-summary-row-saldo td {
        white-space: nowrap;
        background: #e8f4ea;
    }
    #jurnal-kas-table-wrap .jurnal-kas-summary-table .jk-summary-row-last td {
        background: #fff3cd;
    }
    #jurnal-kas-table-wrap .dataTables_wrapper {
        padding: 0;
        font-size: 18px;
        color: #212529;
    }
    #jurnal-kas-table-wrap .dataTables_wrapper .dataTables_length,
    #jurnal-kas-table-wrap .dataTables_wrapper .dataTables_filter,
    #jurnal-kas-table-wrap .dataTables_wrapper .dataTables_info,
    #jurnal-kas-table-wrap .dataTables_wrapper .dataTables_paginate {
        padding: 14px 16px;
        font-size: 18px;
    }
    #jurnal-kas-table-wrap .dataTables_wrapper .dataTables_length select,
    #jurnal-kas-table-wrap .dataTables_wrapper .dataTables_filter input {
        font-size: 18px;
        padding: 6px 12px;
        height: auto;
    }
    #jurnal-kas-table-wrap .dataTables_wrapper .dataTables_paginate .paginate_button {
        font-size: 18px;
        padding: 6px 14px;
    }
    #jurnal-kas-table-wrap table.dataTable {
        border-collapse: collapse !important;
        margin-top: 0 !important;
        margin-bottom: 0 !important;
    }
    #jurnal-kas-table-wrap table.dataTable thead th {
        border-bottom: 2px solid #6c9a74 !important;
    }
    #jurnal-kas-table-wrap table.dataTable.no-footer {
        border-bottom: none;
    }
    #jurnal-kas-table-wrap table.dataTable tbody td.sorting_1,
    #jurnal-kas-table-wrap table.dataTable tbody td.sorting_2,
    #jurnal-kas-table-wrap table.dataTable tbody td.sorting_3 {
        background-color: inherit;
    }
</style>

<script>
window.addEventListener('load', function() {
    if (!window.jQuery || !jQuery.fn.DataTable) {
        return;
    }

    var $ = jQuery;
    var $table = $('#panel-jurnal-kas-data #jurnalKasMainTable');
    if (!$table.length) {
        return;
    }

    if ($.fn.DataTable.isDataTable($table)) {
        $table.DataTable().destroy();
    }

    var jkColWidths = ['48px', '100px', '58px', '112px', '58px', '540px', '128px', '128px'];

    function syncJurnalKasTableWidths() {
        var $mainTable = $('#jurnalKasMainTable');
        var $summaryTable = $('.jurnal-kas-summary-table');
        if (!$mainTable.length) {
            return;
        }

        $mainTable.find('colgroup col').each(function(i) {
            if (jkColWidths[i]) {
                $(this).css('width', jkColWidths[i]);
            }
        });

        $summaryTable.find('colgroup col').each(function(i) {
            if (jkColWidths[i]) {
                $(this).css('width', jkColWidths[i]);
            }
        });

        $mainTable.css('width', '100%');
        $summaryTable.css('width', $mainTable.outerWidth() + 'px');
    }

    var jkDt = $table.DataTable({
        paging: true,
        pageLength: 50,
        lengthMenu: [[25, 50, 100, 250, -1], [25, 50, 100, 250, 'Semua']],
        searching: true,
        ordering: true,
        order: [],
        orderClasses: false,
        autoWidth: false,
        info: true,
        dom: 'lfrtip',
        columnDefs: [
            { targets: 0, width: jkColWidths[0], orderable: true },
            { targets: 1, width: jkColWidths[1], orderable: true },
            { targets: 2, width: jkColWidths[2], orderable: true },
            { targets: 3, width: jkColWidths[3], orderable: true },
            { targets: 4, width: jkColWidths[4], orderable: true },
            { targets: 5, width: jkColWidths[5], orderable: true },
            { targets: 6, width: jkColWidths[6], orderable: true, className: 'text-right' },
            { targets: 7, width: jkColWidths[7], orderable: true, className: 'text-right' }
        ],
        drawCallback: function() {
            syncJurnalKasTableWidths();
        },
        initComplete: function() {
            syncJurnalKasTableWidths();
        }
    });

    jkDt.on('order.dt column-visibility.dt page.dt length.dt', function() {
        setTimeout(syncJurnalKasTableWidths, 0);
    });

    $(window).on('resize.jurnalKasDt', function() {
        syncJurnalKasTableWidths();
    });

    $('a[data-toggle="pill"][href="#panel-jurnal-kas-data"]').on('shown.bs.tab', function() {
        setTimeout(syncJurnalKasTableWidths, 50);
    });

    var $mainScroll = $('.jurnal-kas-dt-responsive');
    var $summaryScroll = $('.jurnal-kas-summary-wrap');
    $mainScroll.on('scroll.jurnalKasSync', function() {
        $summaryScroll.scrollLeft($mainScroll.scrollLeft());
    });
    $summaryScroll.on('scroll.jurnalKasSync', function() {
        $mainScroll.scrollLeft($summaryScroll.scrollLeft());
    });

    setTimeout(syncJurnalKasTableWidths, 100);

    var jkSourcePayload = <?php echo json_encode(isset($jurnal_kas_sources_payload) ? $jurnal_kas_sources_payload : new stdClass(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
    var jkSourceSummary = <?php echo json_encode(isset($jurnal_kas_sources_summary) ? $jurnal_kas_sources_summary : array(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
    var $jkSourceModal = $('#modal-jk-source-detail');
    var $jkSourceTable = $('#table-jk-source-detail');
    var jkSourceDt = null;
    var jkSourcePendingKey = '';

    function jkEscapeHtml(value) {
        return $('<span>').text(value == null ? '' : String(value)).html();
    }

    function jkFindSourceDef(key) {
        for (var i = 0; i < jkSourceSummary.length; i++) {
            if (jkSourceSummary[i].key === key) {
                return jkSourceSummary[i];
            }
        }
        return null;
    }

    function jkDestroySourceDt() {
        if ($jkSourceTable.length && $.fn.DataTable.isDataTable($jkSourceTable)) {
            $jkSourceTable.DataTable().clear().destroy();
        }
        $jkSourceTable.find('tbody').empty();
        jkSourceDt = null;
    }

    function jkRenderSourceModal(key) {
        var def = jkFindSourceDef(key);
        var label = def ? def.label : key;
        var tableName = def ? def.table : '';
        var detail = def ? def.detail : '';
        var rows = (jkSourcePayload && jkSourcePayload[key]) ? jkSourcePayload[key] : [];

        $('#modal-jk-source-title').text(label);
        $('#modal-jk-source-meta').html('Tabel: <code>' + jkEscapeHtml(tableName) + '</code>');
        $('#modal-jk-source-desc').text(detail);
        $('#modal-jk-source-count').text(rows.length + ' baris');

        jkDestroySourceDt();

        var html = '';
        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            html += '<tr>'
                + '<td>' + (i + 1) + '</td>'
                + '<td>' + jkEscapeHtml(row.tanggal) + '</td>'
                + '<td>' + jkEscapeHtml(row.unit) + '</td>'
                + '<td>' + jkEscapeHtml(row.bukti) + '</td>'
                + '<td>' + jkEscapeHtml(row.keterangan) + '</td>'
                + '<td>' + jkEscapeHtml(row.debet) + '</td>'
                + '<td>' + jkEscapeHtml(row.kredit) + '</td>'
                + '</tr>';
        }
        $jkSourceTable.find('tbody').html(html);

        jkSourceDt = $jkSourceTable.DataTable({
            paging: true,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']],
            searching: true,
            ordering: true,
            order: [[1, 'asc']],
            autoWidth: false,
            info: true,
            columnDefs: [
                { targets: 0, width: '48px', orderable: false },
                { targets: [5, 6], className: 'text-right' }
            ]
        });
    }

    function jkOpenSourceModal(key) {
        jkSourcePendingKey = key;
        $jkSourceModal.modal('show');
    }

    $(document).on('click', '.jk-source-card', function() {
        jkOpenSourceModal($(this).data('source-key'));
    });
    $(document).on('keydown', '.jk-source-card', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            jkOpenSourceModal($(this).data('source-key'));
        }
    });

    $jkSourceModal.on('shown.bs.modal', function() {
        if (jkSourcePendingKey) {
            jkRenderSourceModal(jkSourcePendingKey);
        }
    });
    $jkSourceModal.on('hidden.bs.modal', function() {
        jkSourcePendingKey = '';
        jkDestroySourceDt();
    });
});
</script>

<script>
(function() {
    var urlCariBase = <?php echo json_encode($url_cari_jurnal_kas); ?>;
    var bulanNsServer = <?php echo json_encode($bulan_ns_value); ?>;
    var STORAGE_KEY = 'anekadharma_jurnal_kas_bulan_terpilih';
    var loading = false;
    var debounceTimer = null;

    function parseMonthValue(val) {
        if (!val || !/^\d{4}-\d{2}$/.test(val)) {
            return null;
        }
        var parts = val.split('-');
        var year = parseInt(parts[0], 10);
        var month = parseInt(parts[1], 10);
        if (!year || month < 1 || month > 12) {
            return null;
        }
        return { year: year, month: month };
    }

    function saveMonthChoice(val) {
        if (!val || !/^\d{4}-\d{2}$/.test(val)) {
            return;
        }
        try {
            sessionStorage.setItem(STORAGE_KEY, val);
        } catch (e) {}
    }

    function getSavedMonthChoice() {
        try {
            return sessionStorage.getItem(STORAGE_KEY) || '';
        } catch (e) {
            return '';
        }
    }

    function isOnCariBetweenDateUrl() {
        return /cari_between_date\/\d+\/\d+/i.test(window.location.pathname);
    }

    function redirectToSavedMonthIfNeeded() {
        if (isOnCariBetweenDateUrl()) {
            return;
        }
        var saved = getSavedMonthChoice();
        if (!saved || saved === bulanNsServer) {
            return;
        }
        var parsed = parseMonthValue(saved);
        if (parsed) {
            window.location.replace(urlCariBase + '/' + parsed.year + '/' + parsed.month);
        }
    }

    function showLoading() {
        var elLoading = document.getElementById('jurnal-kas-month-loading');
        var elWrap = document.getElementById('jurnal-kas-table-wrap');
        if (elLoading) {
            elLoading.classList.remove('d-none');
        }
        if (elWrap) {
            elWrap.style.opacity = '0.45';
            elWrap.style.pointerEvents = 'none';
        }
    }

    function reloadJurnalKasByMonth(monthValue) {
        var parsed = parseMonthValue(monthValue);
        if (!parsed || loading) {
            return;
        }
        saveMonthChoice(monthValue);
        loading = true;
        showLoading();
        window.location.href = urlCariBase + '/' + parsed.year + '/' + parsed.month;
    }

    function scheduleReload(monthValue) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function() {
            reloadJurnalKasByMonth(monthValue);
        }, 250);
    }

    function bindMonthPicker() {
        var bulanNs = document.getElementById('bulan_ns');
        if (!bulanNs) {
            return;
        }

        if (bulanNs.value) {
            saveMonthChoice(bulanNs.value);
        } else if (bulanNsServer) {
            bulanNs.value = bulanNsServer;
            saveMonthChoice(bulanNsServer);
        }

        var lastValue = bulanNs.value || '';

        function handleMonthChange() {
            var val = bulanNs.value || '';
            if (!val || val === lastValue) {
                return;
            }
            lastValue = val;
            scheduleReload(val);
        }

        bulanNs.addEventListener('change', handleMonthChange);
        bulanNs.addEventListener('input', handleMonthChange);
    }

    redirectToSavedMonthIfNeeded();

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bindMonthPicker);
    } else {
        bindMonthPicker();
    }
})();
</script>

<?php if ($can_input_jurnal_kas) { ?>
<script>
window.addEventListener('load', function() {
    if (!window.jQuery) {
        return;
    }

    var urlAjaxPemasukan = <?php echo json_encode($url_ajax_pemasukan_kas); ?>;
    var bulanNsDefault = <?php echo json_encode($bulan_ns_value); ?>;
    var modalTanggalFallback = <?php echo json_encode($modal_jk_tanggal_default); ?>;
    var $modal = jQuery('#modal-jurnal-kas-input');
    var $form = jQuery('#form-jurnal-kas-input-modal');
    var $btnSimpan = jQuery('#btn-jurnal-kas-input-simpan');
    var $errors = jQuery('#jurnal-kas-input-modal-errors');
    var saving = false;
    var modalPluginsReady = false;

    function showModalError(msg) {
        if (!msg) {
            $errors.addClass('d-none').empty();
            return;
        }
        $errors.removeClass('d-none').html('<i class="fa fa-exclamation-circle"></i> ' + jQuery('<span>').text(msg).html());
    }

    function getModalTanggalFromBulanNs() {
        var bulanVal = jQuery('#bulan_ns').val() || bulanNsDefault || '';
        if (bulanVal && /^\d{4}-\d{2}$/.test(bulanVal)) {
            var parts = bulanVal.split('-');
            return '01-' + parts[1] + '-' + parts[0];
        }
        return modalTanggalFallback;
    }

    function setModalTanggalDefault() {
        var tanggalStr = getModalTanggalFromBulanNs();
        jQuery('#modal_jk_tgl_po').val(tanggalStr);
        var $wrap = jQuery('#modal_jk_tgl_po_wrap');
        if ($wrap.data('datetimepicker') && typeof moment !== 'undefined') {
            var m = moment(tanggalStr, 'DD-MM-YYYY', true);
            if (m.isValid()) {
                $wrap.datetimepicker('date', m);
            }
        }
    }

    function resetInputForm() {
        if ($form.length && $form[0].reset) {
            $form[0].reset();
        }
        setModalTanggalDefault();
        jQuery('.modal-jk-select2').val('').trigger('change');
        showModalError('');
    }

    function initModalPlugins() {
        if (!$modal.length) {
            return;
        }
        jQuery('.modal-jk-select2').each(function() {
            var $sel = jQuery(this);
            if ($sel.hasClass('select2-hidden-accessible')) {
                $sel.select2('destroy');
            }
            $sel.select2({
                dropdownParent: $modal,
                width: '100%',
                placeholder: $sel.find('option:first').text() || 'Pilih'
            });
        });
        if (!jQuery('#modal_jk_tgl_po_wrap').data('datetimepicker')) {
            jQuery('#modal_jk_tgl_po_wrap').datetimepicker({ format: 'D-M-YYYY' });
        }
        modalPluginsReady = true;
    }

    function reloadJurnalKasTable() {
        window.location.reload();
    }

    function showSaveSuccessAlert() {
        if (typeof Swal === 'undefined') {
            alert('Data jurnal kas berhasil disimpan.');
            reloadJurnalKasTable();
            return;
        }
        Swal.fire({
            icon: 'success',
            title: 'Simpan Berhasil!',
            html: '<div style="font-size:15px;line-height:1.6;">Proses simpan jurnal kas telah <strong>terproses dan sukses</strong>.<br><span class="text-muted" style="font-size:13px;">Datatable akan dimuat ulang otomatis...</span></div>',
            confirmButtonText: 'OK',
            confirmButtonColor: '#28a745',
            timer: 2000,
            timerProgressBar: true,
            allowOutsideClick: false,
            customClass: { popup: 'jurnal-kas-swal-success' }
        }).then(function() {
            reloadJurnalKasTable();
        });
    }

    $modal.on('show.bs.modal', function() {
        if (!modalPluginsReady) {
            initModalPlugins();
        }
        resetInputForm();
    });

    $form.on('submit', function(e) {
        e.preventDefault();
        if (saving) {
            return;
        }
        showModalError('');

        var formData = $form.serializeArray();
        saving = true;
        $btnSimpan.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');

        jQuery.ajax({
            url: urlAjaxPemasukan,
            type: 'POST',
            dataType: 'json',
            data: formData
        }).done(function(res) {
            if (!res || !res.ok) {
                var msg = (res && res.message) ? res.message : 'Gagal menyimpan data jurnal kas.';
                showModalError(msg);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Gagal Menyimpan', text: msg, confirmButtonColor: '#dc3545' });
                }
                return;
            }
            $modal.modal('hide');
            showSaveSuccessAlert();
        }).fail(function() {
            var msg = 'Tidak dapat menghubungi server. Periksa koneksi lalu coba lagi.';
            showModalError(msg);
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: 'Kesalahan Server', text: msg, confirmButtonColor: '#dc3545' });
            }
        }).always(function() {
            saving = false;
            $btnSimpan.prop('disabled', false).html('<i class="fa fa-save"></i> Simpan');
        });
    });
});
</script>
<?php } ?>

<script>
(function compareJurnalKasInit() {
    function hasDataTable() {
        return window.jQuery && jQuery.fn && jQuery.fn.DataTable;
    }
    function bootCompareJurnalKas() {
    if (!window.jQuery) {
        console.error('Compare Jurnal Kas: jQuery belum dimuat.');
        return;
    }
    var urlRun = <?php echo json_encode($url_compare_jurnal_kas_run); ?>;
    var urlExcel = <?php echo json_encode($url_compare_jurnal_kas_excel); ?>;
    var urlImport = <?php echo json_encode($url_compare_jurnal_kas_import_csv); ?>;
    var urlList = <?php echo json_encode($url_compare_jurnal_kas_tabel_list); ?>;
    var urlPreview = <?php echo json_encode($url_compare_jurnal_kas_tabel_preview); ?>;
    var urlValidate = <?php echo json_encode($url_compare_jurnal_kas_tabel_validate); ?>;
    var urlDetail = <?php echo json_encode($url_compare_jurnal_kas_tabel_detail); ?>;
    var urlTabelImport = <?php echo json_encode($url_compare_jurnal_kas_tabel_import); ?>;
    var urlDetailExcel = <?php echo json_encode($url_compare_jurnal_kas_tabel_detail_excel); ?>;
    var urlPublish = <?php echo json_encode($url_setting_jurnal_kas_publish); ?>;
    var urlPublishStatus = <?php echo json_encode($url_compare_jurnal_kas_publish_status); ?>;
    var urlLapBase = <?php echo json_encode($url_lap_jurnal_kas); ?>;
    var urlCariJurnalKas = <?php echo json_encode($url_cari_jurnal_kas); ?>;
    var jurnalKasBulanStorageKey = 'anekadharma_jurnal_kas_bulan_terpilih';
    var lastResult = null, dtMap = {}, tablesLoaded = false, csvBusy = false, csvLast = null;
    var lastPublishSetting = null;
    var tabelImportState = null, tabelDetailDt = null, tabelImportBusy = false, csvPreviewTbl = null;

    function bulanKey() {
        var b = parseInt(jQuery('#compare_bulan_jurnal_kas').val(), 10);
        var t = parseInt(jQuery('#compare_tahun_jurnal_kas').val(), 10);
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
        var $el = jQuery('#compare-jurnal-kas-status');
        $el.removeClass('alert-info alert-success alert-danger alert-warning');
        $el.addClass(type === 'success' ? 'alert-success' : (type === 'danger' ? 'alert-danger' : (type === 'warning' ? 'alert-warning' : 'alert-info')));
        $el.html(html);
    }
    function updatePublishNotifs(publish, tbl) {
        publish = publish || null;
        tbl = tbl || jQuery('#compare_tabel_jurnal_kas').val() || '';
        lastPublishSetting = publish;
        var showManual = publish && publish.source_type === 'tabel' && publish.source_table && publish.source_table === tbl;
        var showOnline = publish && publish.source_type === 'asli';
        jQuery('#compare-jurnal-kas-publish-notif-manual').toggleClass('d-none', !showManual);
        jQuery('#compare-jurnal-kas-publish-notif-online').toggleClass('d-none', !showOnline);
    }
    function loadPublishStatus() {
        var b = parseInt(jQuery('#compare_bulan_jurnal_kas').val(), 10);
        var t = parseInt(jQuery('#compare_tahun_jurnal_kas').val(), 10);
        if (!b || !t) {
            updatePublishNotifs(null);
            return;
        }
        jQuery.ajax({
            url: urlPublishStatus,
            type: 'POST',
            dataType: 'json',
            data: { bulan_num: b, tahun: t }
        }).done(function(res) {
            if (res && res.ok) {
                updatePublishNotifs(res.publish_setting || null);
            }
        });
    }
    function doPublish(sourceType, sourceTable) {
        var b = parseInt(jQuery('#compare_bulan_jurnal_kas').val(), 10);
        var t = parseInt(jQuery('#compare_tahun_jurnal_kas').val(), 10);
        var tbl = jQuery('#compare_tabel_jurnal_kas').val() || '';
        if (!b || !t) {
            alert('Pilih bulan dan tahun terlebih dahulu.');
            return;
        }
        if (sourceType === 'tabel' && !tbl) {
            alert('Pilih tabel manual terlebih dahulu.');
            return;
        }
        var sumber = (sourceType === 'asli') ? 'asli' : tbl;
        var confirmTitle = (sourceType === 'asli') ? 'Publish Online ke Laporan?' : 'Publish Manual ke Laporan?';
        var confirmText = (sourceType === 'asli')
            ? 'Data online (aplikasi Tab 1) akan ditampilkan di Laporan Jurnal Kas dan menggantikan publish manual.'
            : 'Data manual dari tabel `' + tbl + '` akan ditampilkan di Laporan Jurnal Kas dan menggantikan publish online.';
        var runPublish = function() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ title: 'Memproses publish...', allowOutsideClick: false, didOpen: function() { Swal.showLoading(); } });
            }
            jQuery.ajax({
                url: urlPublish,
                type: 'POST',
                dataType: 'json',
                data: { bulan_num: b, tahun: t, sumber: sumber }
            }).done(function(res) {
                if (typeof Swal !== 'undefined') Swal.close();
                if (!res || !res.ok) {
                    var msg = (res && res.message) ? res.message : 'Publish gagal.';
                    if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Publish Gagal', text: msg });
                    else alert(msg);
                    return;
                }
                updatePublishNotifs(res.publish_setting || res.setting || null, tbl);
                var lapUrl = res.lap_url || (urlLapBase + '/cari_between_date/' + t + '/' + b);
                var okMsg = (sourceType === 'asli')
                    ? 'Data online berhasil dipublish ke Laporan Jurnal Kas.'
                    : 'Data manual berhasil dipublish ke Laporan Jurnal Kas.';
                setStatus('success', '<i class="fas fa-check-circle"></i> ' + okMsg + ' <a href="' + lapUrl + '" target="_blank" class="alert-link">Buka Laporan</a>');
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Publish Berhasil',
                        html: okMsg + '<br/><a href="' + lapUrl + '" target="_blank">Buka Laporan Jurnal Kas</a>',
                        confirmButtonText: 'OK'
                    });
                }
            }).fail(function() {
                if (typeof Swal !== 'undefined') Swal.close();
                alert('Tidak dapat menghubungi server.');
            });
        };
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'question',
                title: confirmTitle,
                text: confirmText,
                showCancelButton: true,
                confirmButtonText: 'Ya, Publish',
                cancelButtonText: 'Batal'
            }).then(function(r) { if (r.isConfirmed) runPublish(); });
        } else if (confirm(confirmText)) {
            runPublish();
        }
    }
    function toggleBtns() {
        var show = bulanKey() !== '' && (jQuery('#compare_tabel_jurnal_kas').val() || '') !== '';
        jQuery('#btn-compare-jurnal-kas').toggleClass('d-none', !show);
        if (!show) jQuery('#btn-compare-jurnal-kas-excel-all').addClass('d-none');
    }
    function hideTabelActions() {
        tabelImportState = null;
        jQuery('#compare-jurnal-kas-tabel-actions').addClass('d-none');
        jQuery('#compare-jurnal-kas-tabel-info-body').empty();
        jQuery('#btn-compare-jurnal-kas-tabel-import').prop('disabled', true);
        jQuery('#compare-jurnal-kas-tabel-import-note').text('').removeClass('text-danger text-success text-muted');
    }
    function buildTabelInfoHtml(res) {
        var tbl = jQuery('#compare_tabel_jurnal_kas').val() || (res && res.table) || '—';
        var bk = bulanKey() || (res && res.bulan) || '—';
        var stats = (res && res.stats) ? res.stats : {};
        var map = (res && res.map) ? res.map : {};
        var mapParts = [];
        var mapOrder = ['tanggal', 'keterangan', 'bukti', 'kode_rekening', 'debet', 'kredit'];
        mapOrder.forEach(function(key) {
            if (map[key]) {
                mapParts.push(key + ' → <code>' + jQuery('<span>').text(map[key]).html() + '</code>');
            }
        });
        var html = '<div class="compare-info-title"><i class="fas fa-info-circle"></i> Informasi Tabel — Siap Diproses ke Jurnal Kas</div>';
        html += '<div class="compare-info-line">Tabel terpilih: <strong>' + jQuery('<span>').text(tbl).html() + '</strong></div>';
        html += '<div class="compare-info-line">Bulan proses: <strong>' + jQuery('<span>').text(bk).html() + '</strong></div>';
        html += '<div class="compare-info-line">Kolom wajib: <strong>tanggal</strong>, <strong>keterangan</strong>, <strong>debet atau kredit</strong> (bukti &amp; kode_rekening opsional)</div>';
        html += '<div class="compare-info-line">Syarat per baris: tanggal valid, keterangan terisi, debet atau kredit &gt; 0</div>';
        if (mapParts.length) {
            html += '<div class="compare-info-line">Mapping kolom: ' + mapParts.join(' | ') + '</div>';
        }
        if (stats.saveable_in_bulan != null) {
            html += '<div class="compare-info-line">Baris siap simpan: <strong>' + (stats.saveable_in_bulan || 0) + '</strong>';
            if (stats.in_bulan != null) html += ' &nbsp;|&nbsp; baris bulan terpilih: <strong>' + (stats.in_bulan || 0) + '</strong>';
            if (stats.out_bulan > 0) html += ' &nbsp;|&nbsp; di luar bulan: <strong class="text-warning">' + stats.out_bulan + '</strong>';
            if (stats.invalid_in_bulan > 0) html += ' &nbsp;|&nbsp; tidak valid: <strong class="text-danger">' + stats.invalid_in_bulan + '</strong>';
            html += '</div>';
        } else if (stats.in_bulan != null) {
            html += '<div class="compare-info-line">Data sesuai bulan: <strong>' + (stats.in_bulan || 0) + '</strong> baris';
            if (stats.out_bulan > 0) {
                html += ' &nbsp;|&nbsp; di luar bulan: <strong class="text-warning">' + stats.out_bulan + '</strong> baris';
            }
            html += '</div>';
        }
        html += '<div class="compare-info-line">Mode simpan: <strong>semua baris valid disimpan apa adanya</strong> (tanpa cek duplikat).</div>';
        if (res.jurnal_kas_bulan_conflict && res.conflict_warning) {
            html += '<div class="compare-info-line text-warning"><i class="fas fa-exclamation-triangle"></i> '
                + jQuery('<span>').text(res.conflict_warning).html() + '</div>';
        }
        return html;
    }
    function applyTabelImportState(res) {
        tabelImportState = res || null;
        var tbl = jQuery('#compare_tabel_jurnal_kas').val() || '';
        if (!tbl) {
            hideTabelActions();
            return;
        }
        jQuery('#compare-jurnal-kas-tabel-actions').removeClass('d-none');
        jQuery('#btn-compare-jurnal-kas-tabel-detail').prop('disabled', false);
        if (!res || !res.eligible) {
            var miss = (res && res.missing_fields && res.missing_fields.length)
                ? ('Kolom kurang: <strong>' + res.missing_fields.join(', ') + '</strong>. ')
                : '';
            jQuery('#compare-jurnal-kas-tabel-info-body').html(
                '<div class="compare-info-title text-warning"><i class="fas fa-exclamation-triangle"></i> Tabel belum memenuhi syarat import</div>'
                + '<div class="compare-info-line">Tabel: <strong>' + jQuery('<span>').text(tbl).html() + '</strong></div>'
                + '<div class="compare-info-line">' + miss + jQuery('<span>').text((res && res.message) ? res.message : 'Kolom wajib minimal: tanggal, keterangan, debet atau kredit.').html() + '</div>'
                + '<div class="compare-info-line">Per baris: tanggal valid, keterangan terisi, debet atau kredit harus ada nilainya.</div>'
            );
            jQuery('#btn-compare-jurnal-kas-tabel-detail').prop('disabled', true);
            jQuery('#btn-compare-jurnal-kas-tabel-import').prop('disabled', true);
            jQuery('#compare-jurnal-kas-tabel-import-note').removeClass('text-danger text-success text-muted').addClass('text-muted').text('');
            return;
        }
        jQuery('#compare-jurnal-kas-tabel-info-body').html(buildTabelInfoHtml(res));
        var enabled = !!res.import_enabled;
        jQuery('#btn-compare-jurnal-kas-tabel-import').prop('disabled', !enabled);
        var $note = jQuery('#compare-jurnal-kas-tabel-import-note');
        $note.removeClass('text-danger text-success text-muted text-warning');
        if (enabled) {
            var noteHtml = '<i class="fas fa-check-circle"></i> ' + (res.import_message || 'Tanggal data sesuai bulan terpilih — siap disimpan ke database.');
            noteHtml += ' Semua baris valid akan disimpan apa adanya tanpa cek duplikat.';
            if (res.jurnal_kas_bulan_conflict && res.conflict_warning) {
                $note.addClass('text-warning');
                noteHtml += '<br><i class="fas fa-exclamation-triangle"></i> ' + jQuery('<span>').text(res.conflict_warning).html();
            } else {
                $note.addClass('text-success');
            }
            $note.html(noteHtml);
        } else {
            $note.addClass('text-danger').html('<i class="fas fa-exclamation-circle"></i> ' + (res.import_message || 'Data tidak bisa di masukan ke data jurnal kas karena berbeda bulan.'));
        }
    }
    function showTabelCheckingState(tbl) {
        jQuery('#compare-jurnal-kas-tabel-actions').removeClass('d-none');
        jQuery('#compare-jurnal-kas-tabel-info-body').html(
            '<div class="compare-info-title"><i class="fas fa-spinner fa-spin"></i> Memeriksa tabel terpilih...</div>'
            + '<div class="compare-info-line">Tabel: <strong>' + jQuery('<span>').text(tbl).html() + '</strong></div>'
        );
        jQuery('#compare-jurnal-kas-tabel-import-note').removeClass('text-danger text-success').addClass('text-muted').text('');
        jQuery('#btn-compare-jurnal-kas-tabel-detail').prop('disabled', true);
        jQuery('#btn-compare-jurnal-kas-tabel-import').prop('disabled', true);
    }
    function validateTabelForImport() {
        var tbl = jQuery('#compare_tabel_jurnal_kas').val() || '';
        if (!tbl) {
            hideTabelActions();
            return;
        }
        showTabelCheckingState(tbl);
        var bk = bulanKey();
        jQuery.ajax({
            url: urlValidate,
            type: 'POST',
            dataType: 'json',
            data: {
                tabel: tbl,
                bulan: bk,
                bulan_num: jQuery('#compare_bulan_jurnal_kas').val(),
                tahun: jQuery('#compare_tahun_jurnal_kas').val()
            }
        }).done(function(res) {
            applyTabelImportState(res);
        }).fail(function(xhr) {
            var errMsg = 'Tidak dapat menghubungi server. Silakan coba lagi.';
            if (xhr && xhr.responseText) {
                try {
                    var errJson = JSON.parse(xhr.responseText);
                    if (errJson && errJson.message) errMsg = errJson.message;
                } catch (e) {
                    if (xhr.status) errMsg += ' (HTTP ' + xhr.status + ')';
                }
            }
            jQuery('#compare-jurnal-kas-tabel-actions').removeClass('d-none');
            jQuery('#compare-jurnal-kas-tabel-info-body').html(
                '<div class="compare-info-title text-danger"><i class="fas fa-times-circle"></i> Gagal memeriksa tabel</div>'
                + '<div class="compare-info-line">Tabel: <strong>' + jQuery('<span>').text(tbl).html() + '</strong></div>'
                + '<div class="compare-info-line">' + jQuery('<span>').text(errMsg).html() + '</div>'
                + '<div class="compare-info-line text-muted">Pastikan kolom minimal: tanggal, keterangan, debet atau kredit.</div>'
            );
            jQuery('#btn-compare-jurnal-kas-tabel-detail').prop('disabled', true);
            jQuery('#btn-compare-jurnal-kas-tabel-import').prop('disabled', true);
        });
    }
    function buildDetailRows(items) {
        return (items || []).map(function(it) {
            return [
                it.no || '',
                it.tanggal || '',
                it.bukti ? jQuery('<span>').text(it.bukti).html() : '',
                it.keterangan ? '<span class="text-ket">' + jQuery('<span>').text(it.keterangan).html() + '</span>' : '',
                it.kode_rekening ? jQuery('<span>').text(it.kode_rekening).html() : '',
                fmtAmtCell(it.debet, 'debet'),
                fmtAmtCell(it.kredit, 'kredit')
            ];
        });
    }
    function openTabelDetailModal() {
        var tbl = jQuery('#compare_tabel_jurnal_kas').val() || '';
        var bk = bulanKey();
        if (!tbl || !bk) {
            alert('Pilih tabel dan bulan terlebih dahulu.');
            return;
        }
        jQuery('#compare-jurnal-kas-tabel-detail-meta').text('Memuat data tabel `' + tbl + '` bulan ' + bk + '...');
        jQuery('#modal-compare-jurnal-kas-tabel-detail').modal('show');
        jQuery.ajax({
            url: urlDetail,
            type: 'POST',
            dataType: 'json',
            data: { tabel: tbl, bulan: bk }
        }).done(function(res) {
            if (!res || !res.ok) {
                jQuery('#compare-jurnal-kas-tabel-detail-meta').text((res && res.message) || 'Gagal memuat detail tabel.');
                return;
            }
            var items = res.rows || [];
            jQuery('#compare-jurnal-kas-tabel-detail-meta').text(
                'Tabel: ' + (res.table || tbl) + ' | Bulan: ' + (res.bulan_label || bk) + ' | Total: ' + (res.total || items.length) + ' baris'
            );
            var $t = jQuery('#table-compare-jurnal-kas-tabel-detail');
            if (!hasDataTable()) {
                jQuery('#compare-jurnal-kas-tabel-detail-meta').text('DataTables belum dimuat.');
                return;
            }
            if (jQuery.fn.DataTable.isDataTable($t)) {
                $t.DataTable().clear().destroy();
            }
            $t.find('tbody').empty();
            tabelDetailDt = $t.DataTable({
                data: buildDetailRows(items),
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                scrollX: true,
                pageLength: 25,
                order: [[1, 'asc']],
                autoWidth: false,
                language: { url: '//cdn.datatables.net/plug-ins/1.11.4/i18n/id.json', emptyTable: 'Tidak ada data pada bulan terpilih' },
                drawCallback: function() {
                    var td = 0, tk = 0;
                    items.forEach(function(it) { td += parseAmt(it.debet); tk += parseAmt(it.kredit); });
                    $t.find('.compare-total-debet').text(td > 0 ? td.toLocaleString('id-ID') : '—');
                    $t.find('.compare-total-kredit').text(tk > 0 ? tk.toLocaleString('id-ID') : '—');
                }
            });
        }).fail(function() {
            jQuery('#compare-jurnal-kas-tabel-detail-meta').text('Gagal memuat detail tabel.');
        });
    }
    function exportTabelDetailExcel() {
        var tbl = jQuery('#compare_tabel_jurnal_kas').val() || '';
        var bk = bulanKey();
        if (!tbl || !bk) {
            alert('Pilih tabel dan bulan terlebih dahulu.');
            return;
        }
        var $form = jQuery('<form>', { method: 'POST', action: urlDetailExcel, target: '_blank' });
        $form.append(jQuery('<input>', { type: 'hidden', name: 'tabel', value: tbl }));
        $form.append(jQuery('<input>', { type: 'hidden', name: 'bulan', value: bk }));
        jQuery('body').append($form);
        $form.trigger('submit');
        $form.remove();
    }
    function reloadJurnalKasMainAfterImport() {
        var bk = bulanKey();
        if (bk && /^\d{4}-\d{2}$/.test(bk)) {
            var parts = bk.split('-');
            var year = parseInt(parts[0], 10);
            var month = parseInt(parts[1], 10);
            if (year && month >= 1 && month <= 12) {
                try {
                    sessionStorage.setItem(jurnalKasBulanStorageKey, bk);
                } catch (e) {}
                window.location.href = urlCariJurnalKas + '/' + year + '/' + month;
                return;
            }
        }
        window.location.reload();
    }
    function showCompareImportSuccessAlert(msg) {
        var safeMsg = jQuery('<div>').text(msg || 'Data berhasil disimpan ke jurnal kas.').html();
        if (typeof Swal === 'undefined') {
            alert(msg || 'Data berhasil disimpan ke jurnal kas.');
            reloadJurnalKasMainAfterImport();
            return;
        }
        Swal.fire({
            icon: 'success',
            title: 'Proses Sukses!',
            html: '<div style="font-size:15px;line-height:1.6;">' + safeMsg
                + '<br><span style="font-size:13px;color:#666;">Datatable Jurnal Kas (Tab 1) akan dimuat ulang otomatis...</span></div>',
            confirmButtonText: 'OK',
            confirmButtonColor: '#28a745',
            timer: 2000,
            timerProgressBar: true,
            allowOutsideClick: false
        }).then(function() {
            reloadJurnalKasMainAfterImport();
        });
    }
    function runTabelImportRequest(tbl, bk) {
        tabelImportBusy = true;
        jQuery('#btn-compare-jurnal-kas-tabel-import').prop('disabled', true);
        jQuery.ajax({
            url: urlTabelImport,
            type: 'POST',
            dataType: 'json',
            data: { tabel: tbl, bulan: bk }
        }).done(function(res) {
            tabelImportBusy = false;
            if (!res || !res.ok) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Import Gagal', text: (res && res.message) || 'Gagal menambahkan data.' });
                } else {
                    alert((res && res.message) || 'Gagal menambahkan data.');
                }
                validateTabelForImport();
                return;
            }
            var msg = res.message || ('Berhasil menambahkan ' + (res.inserted || 0) + ' data ke jurnal_kas.');
            setStatus('success', msg);
            validateTabelForImport();
            showCompareImportSuccessAlert(msg);
        }).fail(function() {
            tabelImportBusy = false;
            validateTabelForImport();
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: 'Import Gagal', text: 'Tidak dapat menghubungi server.' });
            } else {
                alert('Import gagal.');
            }
        });
    }
    function importTabelToJurnalKas() {
        if (tabelImportBusy) return;
        var tbl = jQuery('#compare_tabel_jurnal_kas').val() || '';
        var bk = bulanKey();
        if (!tbl || !bk) {
            alert('Pilih tabel dan bulan terlebih dahulu.');
            return;
        }
        if (!tabelImportState || !tabelImportState.import_enabled) {
            alert((tabelImportState && tabelImportState.import_message) || 'Data tidak bisa di masukan ke data jurnal kas karena berbeda bulan.');
            return;
        }

        var doImport = function() {
            runTabelImportRequest(tbl, bk);
        };

        if (tabelImportState.jurnal_kas_bulan_conflict && tabelImportState.conflict_warning) {
            var warnHtml = '<div style="text-align:left;font-size:14px;line-height:1.6;">'
                + jQuery('<div>').text(tabelImportState.conflict_warning).html()
                + '<br><br><strong>Lanjutkan simpan semua baris valid ke jurnal_kas?</strong></div>';
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Bulan Sudah Ada di Jurnal Kas',
                    html: warnHtml,
                    showCancelButton: true,
                    confirmButtonText: 'Ya, lanjutkan simpan',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#d33',
                    reverseButtons: true
                }).then(function(result) {
                    if (result.isConfirmed) doImport();
                });
                return;
            }
            if (!window.confirm(tabelImportState.conflict_warning + '\n\nLanjutkan simpan?')) return;
            doImport();
            return;
        }

        var confirmMsg = 'Proses simpan data tabel `' + tbl + '` ke jurnal_kas bulan ' + bk + '?\n'
            + 'Semua baris valid akan disimpan apa adanya (tanpa cek duplikat).';
        if (!window.confirm(confirmMsg)) return;
        doImport();
    }
    function buildRows(items) {
        return (items || []).map(function(it, i) {
            return [
                i + 1,
                it.tanggal || '',
                it.bukti ? jQuery('<span>').text(it.bukti).html() : '',
                it.kode_rekening ? jQuery('<span>').text(it.kode_rekening).html() : '',
                it.keterangan ? '<span class="text-ket">' + jQuery('<span>').text(it.keterangan).html() + '</span>' : '',
                fmtAmtCell(it.debet, 'debet'),
                fmtAmtCell(it.kredit, 'kredit'),
                it.catatan ? '<span class="text-catatan">' + jQuery('<span>').text(it.catatan).html() + '</span>' : ''
            ];
        });
    }
    function renderTable(sel, items) {
        var $t = jQuery(sel); if (!$t.length) return;
        items = items || [];
        if (!hasDataTable()) return;
        if (jQuery.fn.DataTable.isDataTable($t)) $t.DataTable().clear().destroy();
        $t.find('tbody').empty();
        var dt = $t.DataTable({
            data: buildRows(items), paging: true, searching: true, ordering: true, info: true,
            scrollX: true, scrollY: '300px', scrollCollapse: true, pageLength: 25,
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
        renderTable('#table-compare-jurnal-kas-manual', res.data_manual);
        renderTable('#table-compare-jurnal-kas-online', res.data_online);
        renderTable('#table-compare-jurnal-kas-cocok', res.data_cocok);
        renderTable('#table-compare-jurnal-kas-manual-miss', res.manual_tidak_di_online);
        renderTable('#table-compare-jurnal-kas-online-miss', res.online_tidak_di_manual);
    }
    function formatFieldValidation(fv) {
        if (!fv) return '';
        var parts = [];
        if (fv.manual) {
            if (fv.manual.mapped && typeof fv.manual.mapped === 'object') {
                var mapped = [];
                jQuery.each(fv.manual.mapped, function(k, v) { mapped.push(k + ' → ' + v); });
                if (mapped.length) parts.push('<strong>Tabel manual:</strong> ' + mapped.join(', '));
            }
            if (fv.manual.missing_fields && fv.manual.missing_fields.length) {
                parts.push('<strong>Manual kolom tidak ada:</strong> ' + fv.manual.missing_fields.join(', '));
            }
        }
        if (fv.online) {
            if (fv.online.mapped && typeof fv.online.mapped === 'object') {
                var mappedO = [];
                jQuery.each(fv.online.mapped, function(k, v) { mappedO.push(k + ' → ' + v); });
                if (mappedO.length) parts.push('<strong>Data online (Tab 1):</strong> ' + mappedO.join(', '));
            }
            if (fv.online.missing_fields && fv.online.missing_fields.length) {
                parts.push('<strong>Online kolom tidak ada:</strong> ' + fv.online.missing_fields.join(', '));
            }
        }
        return parts.join('<br/>');
    }
    function showWarnings(warnings) {
        var $w = jQuery('#compare-jurnal-kas-warnings');
        if (!warnings || !warnings.length) { $w.addClass('d-none').empty(); return; }
        $w.removeClass('d-none').html('<strong><i class="fas fa-exclamation-triangle"></i> Peringatan:</strong><ul class="mb-0 pl-3">'
            + warnings.map(function(w) { return '<li>' + jQuery('<span>').text(w).html() + '</li>'; }).join('') + '</ul>');
    }
    function showFieldInfo(fv) {
        var html = formatFieldValidation(fv);
        var $f = jQuery('#compare-jurnal-kas-field-info');
        if (!html) { $f.addClass('d-none').empty(); return; }
        $f.removeClass('d-none').html('<strong><i class="fas fa-info-circle"></i> Mapping kolom compare:</strong><br/>' + html);
    }
    function updateInfo(res) {
        res = res || lastResult || {};
        var s = res.stats || {};
        jQuery('#compare-jurnal-kas-label-bulan').text(res.bulan_label || bulanKey() || '—');
        jQuery('#compare-jurnal-kas-label-tabel').text(res.table || jQuery('#compare_tabel_jurnal_kas').val() || '—');
        jQuery('#compare-jurnal-kas-count-manual').text(s.data_manual != null ? s.data_manual : '—');
        jQuery('#compare-jurnal-kas-count-online').text(s.data_online != null ? s.data_online : '—');
        jQuery('#compare-jurnal-kas-count-cocok').text(s.data_cocok != null ? s.data_cocok : '—');
        jQuery('#compare-jurnal-kas-count-manual-miss').text(s.manual_tidak_di_online != null ? s.manual_tidak_di_online : '—');
        jQuery('#compare-jurnal-kas-count-online-miss').text(s.online_tidak_di_manual != null ? s.online_tidak_di_manual : '—');
        jQuery('#compare-jurnal-kas-badge-manual').text(s.data_manual || 0);
        jQuery('#compare-jurnal-kas-badge-online').text(s.data_online || 0);
        jQuery('#compare-jurnal-kas-badge-cocok').text(s.data_cocok || 0);
        jQuery('#compare-jurnal-kas-badge-manual-miss').text(s.manual_tidak_di_online || 0);
        jQuery('#compare-jurnal-kas-badge-online-miss').text(s.online_tidak_di_manual || 0);
        showFieldInfo(res.field_validation || null);
        showWarnings(res.warnings || []);
    }
    function loadTableList(force, selectTable) {
        if (tablesLoaded && !force) {
            if (selectTable) jQuery('#compare_tabel_jurnal_kas').val(selectTable);
            toggleBtns();
            validateTabelForImport();
            return;
        }
        jQuery.ajax({ url: urlList, type: 'POST', dataType: 'json' }).done(function(res) {
            if (!res || !res.ok) { setStatus('danger', (res && res.message) || 'Gagal memuat daftar tabel.'); return; }
            var $sel = jQuery('#compare_tabel_jurnal_kas');
            var cur = selectTable || $sel.val();
            $sel.find('option:not(:first)').remove();
            (res.tables || []).forEach(function(tbl) { $sel.append(jQuery('<option>', { value: tbl, text: tbl })); });
            if (cur) $sel.val(cur);
            tablesLoaded = true;
        }).always(function() {
            toggleBtns();
            validateTabelForImport();
        });
    }
    function runCompare() {
        var bk = bulanKey(), tbl = jQuery('#compare_tabel_jurnal_kas').val() || '';
        if (!bk || !tbl) { alert('Pilih bulan, tahun, dan tabel database.'); return; }
        if (typeof Swal !== 'undefined') {
            Swal.fire({ title: 'Memproses Compare...', html: 'Membandingkan data manual vs data aplikasi (Tab 1)', allowOutsideClick: false, didOpen: function() { Swal.showLoading(); } });
        }
        setStatus('info', '<i class="fas fa-spinner fa-spin"></i> Membandingkan...');
        jQuery('#compare-jurnal-kas-results-panel').addClass('d-none');
        jQuery('#btn-compare-jurnal-kas-excel-all').addClass('d-none');
        jQuery.ajax({
            url: urlRun, type: 'POST', dataType: 'json',
            data: { bulan: bk, bulan_num: jQuery('#compare_bulan_jurnal_kas').val(), tahun: jQuery('#compare_tahun_jurnal_kas').val(), tabel: tbl }
        }).done(function(res) {
            if (typeof Swal !== 'undefined') Swal.close();
            if (!res || !res.ok) {
                var msg = (res && res.message) || 'Compare gagal.';
                if (res && res.field_validation) {
                    showFieldInfo(res.field_validation);
                    var fvHtml = formatFieldValidation(res.field_validation);
                    if (fvHtml) msg += '<br/><br/>' + fvHtml;
                }
                setStatus('danger', msg);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Compare Gagal', html: msg });
                }
                return;
            }
            lastResult = res; renderAll(res); updateInfo(res);
            updatePublishNotifs(res.publish_setting || null, tbl);
            jQuery('#compare-jurnal-kas-results-panel').removeClass('d-none');
            jQuery('#btn-compare-jurnal-kas-excel-all').removeClass('d-none');
            var okMsg = '<i class="fas fa-check-circle"></i> Compare selesai. Manual: ' + (res.stats.data_manual || 0)
                + ' baris, Online: ' + (res.stats.data_online || 0) + ' baris, Cocok: ' + (res.stats.data_cocok || 0) + '.';
            if (res.stats.manual_unprocessed > 0 || res.stats.online_unprocessed > 0) {
                okMsg += ' (Manual tidak terproses: ' + (res.stats.manual_unprocessed || 0)
                    + ', Online tidak terproses: ' + (res.stats.online_unprocessed || 0) + ')';
            }
            setStatus('success', okMsg);
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'success', title: 'Compare Selesai', text: 'Data berhasil dibandingkan. Tombol Cetak ke Excel sudah tersedia.', timer: 2500, showConfirmButton: false });
            }
            jQuery('html, body').animate({ scrollTop: jQuery('#compare-jurnal-kas-results-panel').offset().top - 80 }, 400);
        }).fail(function() {
            if (typeof Swal !== 'undefined') Swal.close();
            setStatus('danger', 'Tidak dapat menghubungi server.');
        });
    }
    function exportExcel() {
        var bk = bulanKey(), tbl = jQuery('#compare_tabel_jurnal_kas').val() || '';
        if (!bk || !tbl) { alert('Pilih bulan, tahun, dan tabel.'); return; }
        var f = jQuery('<form method="post" target="_blank"></form>');
        f.attr('action', urlExcel);
        f.append(jQuery('<input type="hidden" name="bulan">').val(bk));
        f.append(jQuery('<input type="hidden" name="bulan_num">').val(jQuery('#compare_bulan_jurnal_kas').val()));
        f.append(jQuery('<input type="hidden" name="tahun">').val(jQuery('#compare_tahun_jurnal_kas').val()));
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
        jQuery('#compare_jurnal_kas_csv_file').prop('disabled', true);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Memproses CSV...',
                html: 'Menyimpan file <strong>' + jQuery('<span>').text(file.name).html() + '</strong> ke database sebagai tabel baru.<br/><small>Membuat tabel, kolom id AUTO_INCREMENT, normalisasi tanggal/debet/kredit...</small>',
                allowOutsideClick: false,
                didOpen: function() { Swal.showLoading(); }
            });
        }
        var ref = { bulan: parseInt(jQuery('#compare_bulan_jurnal_kas').val(), 10), tahun: parseInt(jQuery('#compare_tahun_jurnal_kas').val(), 10) };
        var fd = new FormData();
        fd.append('csv_file', file);
        fd.append('bulan_num', ref.bulan); fd.append('tahun', ref.tahun);
        fd.append('bulan', ref.tahun + '-' + String(ref.bulan).padStart(2, '0'));
        jQuery.ajax({ url: urlImport, type: 'POST', data: fd, processData: false, contentType: false, dataType: 'json' })
        .done(function(res) {
            csvBusy = false;
            jQuery('#compare_jurnal_kas_csv_file').prop('disabled', false).val('');
            jQuery('#compare_jurnal_kas_csv_file').next('.custom-file-label').text('Cari / pilih file CSV...');
            if (!res || !res.ok) {
                if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Import Gagal', html: (res && res.message) ? String(res.message).replace(/\n/g, '<br/>') : 'Import gagal.' });
                else setStatus('danger', (res && res.message) ? String(res.message).replace(/\n/g, '<br/>') : 'Import gagal.');
                return;
            }
            csvLast = res;
            jQuery('#compare-jurnal-kas-csv-filename').text(res.file || '—');
            jQuery('#compare-jurnal-kas-csv-tablename').text(res.table || '—');
            jQuery('#compare-jurnal-kas-csv-rowcount').text(res.rows ? (' (' + res.rows + ' baris)') : '');
            jQuery('#compare-jurnal-kas-csv-upload-info').removeClass('d-none');
            loadTableList(true, res.table);
            setStatus('success', 'Tabel <strong>' + (res.table || '') + '</strong> berhasil dibuat (' + (res.rows || 0) + ' baris).');
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Import CSV Berhasil',
                    html: 'Tabel <strong>' + (res.table || '') + '</strong> dibuat dengan <strong>' + (res.rows || 0) + '</strong> baris.<br/>Silakan klik <strong>Compare</strong>.',
                    confirmButtonText: 'OK'
                });
            }
        }).fail(function() {
            csvBusy = false;
            jQuery('#compare_jurnal_kas_csv_file').prop('disabled', false);
            if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Import Gagal', text: 'Tidak dapat menghubungi server.' });
            else setStatus('danger', 'Import CSV gagal.');
        });
    }
    jQuery('#compare_jurnal_kas_csv_file').on('change', function() {
        var f = this.files && this.files[0];
        if (f) { jQuery(this).next('.custom-file-label').text(f.name); importCsv(f); }
    });
    jQuery('#compare_tabel_jurnal_kas').on('change', function() {
        validateTabelForImport();
        updatePublishNotifs(lastPublishSetting, jQuery(this).val() || '');
    });
    jQuery('#compare_bulan_jurnal_kas, #compare_tahun_jurnal_kas').on('change', function() {
        toggleBtns();
        loadPublishStatus();
        if (jQuery('#compare_tabel_jurnal_kas').val()) {
            validateTabelForImport();
        }
    });
    jQuery('#btn-compare-jurnal-kas').on('click', runCompare);
    jQuery('#btn-compare-jurnal-kas-excel-all').on('click', exportExcel);
    jQuery('#btn-compare-jurnal-kas-tabel-detail').on('click', openTabelDetailModal);
    jQuery('#btn-compare-jurnal-kas-tabel-detail-excel').on('click', exportTabelDetailExcel);
    jQuery('#btn-compare-jurnal-kas-tabel-import').on('click', importTabelToJurnalKas);
    jQuery('#btn-compare-jurnal-kas-publish-manual').on('click', function() { doPublish('tabel'); });
    jQuery('#btn-compare-jurnal-kas-publish-online').on('click', function() { doPublish('asli'); });
    jQuery('#tab-compare-jurnal-kas').on('shown.bs.tab', function() {
        loadTableList(false);
        validateTabelForImport();
        loadPublishStatus();
    });
    jQuery('#btn-compare-jurnal-kas-csv-cek-data').on('click', function() {
        var tbl = (csvLast && csvLast.table) || jQuery('#compare_tabel_jurnal_kas').val();
        if (!tbl) { alert('Belum ada tabel.'); return; }
        csvPreviewTbl = tbl;
        jQuery('#compare-jurnal-kas-csv-preview-meta').text('Tabel: ' + tbl);
        jQuery('#modal-compare-jurnal-kas-csv-preview').modal('show');
        jQuery.ajax({ url: urlPreview, type: 'POST', dataType: 'json', data: { tabel: tbl, limit: 500 } })
        .done(function(res) {
            if (!res || !res.ok) { jQuery('#compare-jurnal-kas-csv-preview-meta').text((res && res.message) || 'Gagal preview.'); return; }
            var cols = res.columns || [];
            var $thead = jQuery('#table-compare-jurnal-kas-csv-preview thead tr').empty();
            cols.forEach(function(c) { $thead.append(jQuery('<th>').text(c)); });
            var rows = (res.rows || []).map(function(r) { return cols.map(function(c) { return r[c] != null ? r[c] : ''; }); });
            var debIdx = -1, kreIdx = -1;
            cols.forEach(function(c, i) {
                var lc = String(c).toLowerCase().replace(/\s+/g, '_');
                if (debIdx < 0 && (lc === 'debet' || lc.indexOf('debet') >= 0)) debIdx = i;
                if (kreIdx < 0 && (lc === 'kredit' || lc.indexOf('kredit') >= 0)) kreIdx = i;
            });
            var labelColspan = Math.max(1, (debIdx >= 0 ? debIdx : cols.length));
            var $tfoot = jQuery('#table-compare-jurnal-kas-csv-preview tfoot tr');
            $tfoot.empty();
            $tfoot.append(jQuery('<th>').attr('colspan', labelColspan).addClass('text-right').text('Total'));
            if (debIdx >= 0) {
                for (var g = labelColspan; g < debIdx; g++) $tfoot.append(jQuery('<th>'));
                $tfoot.append(jQuery('<th>').addClass('csv-preview-total-debet text-right').text('—'));
            }
            if (kreIdx >= 0) {
                for (var h = (debIdx >= 0 ? debIdx + 1 : labelColspan); h < kreIdx; h++) $tfoot.append(jQuery('<th>'));
                $tfoot.append(jQuery('<th>').addClass('csv-preview-total-kredit text-right').text('—'));
            }
            var restStart = Math.max(debIdx, kreIdx) + 1;
            for (var j = restStart; j < cols.length; j++) $tfoot.append(jQuery('<th>'));
            var td = 0, tk = 0;
            (res.rows || []).forEach(function(r) {
                if (debIdx >= 0) td += parseAmt(r[cols[debIdx]]);
                if (kreIdx >= 0) tk += parseAmt(r[cols[kreIdx]]);
            });
            $tfoot.find('.csv-preview-total-debet').text(td > 0 ? td.toLocaleString('id-ID') : '—');
            $tfoot.find('.csv-preview-total-kredit').text(tk > 0 ? tk.toLocaleString('id-ID') : '—');
            var $t = jQuery('#table-compare-jurnal-kas-csv-preview');
            if (jQuery.fn.DataTable.isDataTable($t)) $t.DataTable().destroy();
            $t.DataTable({
                data: rows,
                scrollX: true,
                pageLength: 25,
                drawCallback: function() {
                    $tfoot.find('.csv-preview-total-debet').text(td > 0 ? td.toLocaleString('id-ID') : '—');
                    $tfoot.find('.csv-preview-total-kredit').text(tk > 0 ? tk.toLocaleString('id-ID') : '—');
                }
            });
        });
    });
    jQuery('#btn-compare-jurnal-kas-csv-preview-excel').on('click', function() {
        var tbl = csvPreviewTbl || (csvLast && csvLast.table) || jQuery('#compare_tabel_jurnal_kas').val() || '';
        if (!tbl) { alert('Belum ada tabel.'); return; }
        var $form = jQuery('<form>', { method: 'POST', action: urlDetailExcel, target: '_blank' });
        $form.append(jQuery('<input>', { type: 'hidden', name: 'tabel', value: tbl }));
        $form.append(jQuery('<input>', { type: 'hidden', name: 'bulan', value: bulanKey() || '' }));
        jQuery('body').append($form);
        $form.trigger('submit');
        $form.remove();
    });
    if (jQuery('#tab-compare-jurnal-kas').hasClass('active')) loadTableList(false);
    toggleBtns();
    validateTabelForImport();
    loadPublishStatus();
    }

    if (document.readyState === 'complete') {
        bootCompareJurnalKas();
    } else {
        window.addEventListener('load', bootCompareJurnalKas);
    }
})();
</script>

<script>
(function settingJurnalKasInit() {
    function bootSettingJurnalKas() {
        if (!window.jQuery) return;

        var urlData = <?php echo json_encode($url_setting_jurnal_kas_data); ?>;
        var urlPublish = <?php echo json_encode($url_setting_jurnal_kas_publish); ?>;
        var urlList = <?php echo json_encode($url_setting_jurnal_kas_tabel_list); ?>;
        var urlLapBase = <?php echo json_encode($url_lap_jurnal_kas); ?>;
        var urlExcelBase = <?php echo json_encode($url_setting_jurnal_kas_excel); ?>;
        var namaBulan = <?php echo json_encode($nama_bulan_id); ?>;
        var tablesLoaded = false;
        var loading = false;
        var lastData = null;

        function bulanParts() {
            var b = parseInt(jQuery('#setting_bulan_jurnal_kas').val(), 10);
            var t = parseInt(jQuery('#setting_tahun_jurnal_kas').val(), 10);
            if (!b || !t) return null;
            return { bulan_num: b, tahun: t, bulan_key: t + '-' + String(b).padStart(2, '0') };
        }

        function selectedSumber() {
            return jQuery('#setting_sumber_jurnal_kas').val() || 'asli';
        }

        function togglePublishBtn() {
            var src = selectedSumber();
            jQuery('#btn-setting-jurnal-kas-publish').toggleClass('d-none', src === 'asli' || src === '');
        }

        function updateBulanLabel(parts, label) {
            var txt = label || (parts && namaBulan[parts.bulan_num] ? namaBulan[parts.bulan_num] + ' ' + parts.tahun : '—');
            jQuery('#setting-jurnal-kas-label-bulan').text('(Bulan : ' + txt + ')');
            jQuery('#setting-jk-bulan-nama').text(parts && namaBulan[parts.bulan_num] ? namaBulan[parts.bulan_num] : '—');
            if (parts) {
                jQuery('#btn-setting-jurnal-kas-open-lap').attr('href', urlLapBase + '/cari_between_date/' + parts.tahun + '/' + parts.bulan_num);
                jQuery('#btn-setting-jurnal-kas-excel').off('click.settingExcel').on('click.settingExcel', function() {
                    var src = selectedSumber();
                    var q = '?sumber=' + encodeURIComponent(src || 'asli');
                    window.location.href = urlExcelBase + '/' + parts.tahun + '/' + parts.bulan_num + q;
                });
            }
        }

        function updatePublishInfo(publish) {
            var $info = jQuery('#setting-jurnal-kas-publish-info');
            if (!publish || !publish.source_type) {
                $info.text('Belum ada publish untuk bulan ini.');
                return;
            }
            var txt = 'Publish aktif: ';
            if (publish.source_type === 'tabel' && publish.source_table) {
                txt += 'Tabel `' + publish.source_table + '`';
            } else {
                txt += 'Jurnal Kas Asli';
            }
            if (publish.published_at) {
                txt += ' — ' + publish.published_at;
            }
            $info.text(txt);
        }

        function renderRows(rows) {
            var html = '';
            (rows || []).forEach(function(r) {
                html += '<tr>'
                    + '<td>' + (r.no != null ? r.no : '') + '</td>'
                    + '<td>' + jQuery('<span>').text(r.sumber || '').html() + '</td>'
                    + '<td>' + jQuery('<span>').text(r.unit || '').html() + '</td>'
                    + '<td>' + jQuery('<span>').text(r.tanggal || '').html() + '</td>'
                    + '<td>' + jQuery('<span>').text(r.bukti || '').html() + '</td>'
                    + '<td>' + jQuery('<span>').text(r.keterangan || '').html() + '</td>'
                    + '<td class="text-right">' + (r.debet || '') + '</td>'
                    + '<td class="text-right">' + (r.kredit || '') + '</td>'
                    + '</tr>';
            });
            jQuery('#setting-jurnal-kas-tbody').html(html);
        }

        function renderSummary(res) {
            jQuery('#setting-jk-total-debet').text(res.TOTAL_debet_fmt || '—');
            jQuery('#setting-jk-total-kredit').text(res.TOTAL_kredit_fmt || '—');
            jQuery('#setting-jk-saldo-akhir').text(res.SALDO_AKHIR_fmt || '—');
            var saldo = parseFloat(res.SALDO_AKHIR) || 0;
            var debet = parseFloat(res.TOTAL_debet) || 0;
            var seimbang = saldo >= 0 ? debet : saldo;
            jQuery('#setting-jk-seimbang-debet').text(res.TOTAL_debet_fmt || '—');
            jQuery('#setting-jk-seimbang-kredit').text(res.TOTAL_debet_fmt || '—');
            if (saldo < 0 && res.SALDO_AKHIR_fmt) {
                jQuery('#setting-jk-seimbang-debet').text(res.SALDO_AKHIR_fmt);
                jQuery('#setting-jk-seimbang-kredit').text(res.SALDO_AKHIR_fmt);
            }
        }

        function loadSettingData() {
            var parts = bulanParts();
            if (!parts || loading) return;
            loading = true;
            jQuery('#setting-jurnal-kas-loading').removeClass('d-none');
            jQuery('#setting-jurnal-kas-table-wrap').css('opacity', '0.45');

            jQuery.ajax({
                url: urlData,
                type: 'POST',
                dataType: 'json',
                data: {
                    bulan_num: parts.bulan_num,
                    tahun: parts.tahun,
                    sumber: selectedSumber()
                }
            }).done(function(res) {
                if (!res || !res.ok) {
                    alert((res && res.message) ? res.message : 'Gagal memuat data setting.');
                    return;
                }
                lastData = res;
                renderRows(res.rows);
                renderSummary(res);
                updateBulanLabel(parts, res.bulan_label);
                updatePublishInfo(res.publish_setting);
            }).fail(function() {
                alert('Tidak dapat menghubungi server.');
            }).always(function() {
                loading = false;
                jQuery('#setting-jurnal-kas-loading').addClass('d-none');
                jQuery('#setting-jurnal-kas-table-wrap').css('opacity', '1');
            });
        }

        function loadTableList(force) {
            if (tablesLoaded && !force) return;
            jQuery.ajax({ url: urlList, type: 'POST', dataType: 'json' }).done(function(res) {
                if (!res || !res.ok) return;
                var $sel = jQuery('#setting_sumber_jurnal_kas');
                var cur = $sel.val() || 'asli';
                $sel.find('option:not(:first)').remove();
                (res.tables || []).forEach(function(tbl) {
                    $sel.append(jQuery('<option>', { value: tbl, text: tbl }));
                });
                $sel.val(cur);
                tablesLoaded = true;
                togglePublishBtn();
            });
        }

        function publishSetting() {
            var parts = bulanParts();
            var src = selectedSumber();
            if (!parts || src === 'asli' || src === '') {
                alert('Pilih tabel tertentu untuk publish.');
                return;
            }
            if (!confirm('Publish data tabel `' + src + '` untuk bulan ' + (namaBulan[parts.bulan_num] || '') + ' ' + parts.tahun + '?\n\nHalaman Lap Jurnal Kas akan menampilkan data ini.')) {
                return;
            }
            jQuery.ajax({
                url: urlPublish,
                type: 'POST',
                dataType: 'json',
                data: { bulan_num: parts.bulan_num, tahun: parts.tahun, sumber: src }
            }).done(function(res) {
                if (!res || !res.ok) {
                    alert((res && res.message) ? res.message : 'Publish gagal.');
                    return;
                }
                updatePublishInfo(res.setting || null);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Publish Berhasil',
                        html: 'Data laporan jurnal kas untuk bulan ini sudah dipublish.<br/><a href="' + (res.lap_url || '#') + '" target="_blank">Buka Lap Jurnal Kas</a>',
                        confirmButtonText: 'OK'
                    });
                } else {
                    alert(res.message || 'Publish berhasil.');
                }
            }).fail(function() {
                alert('Tidak dapat menghubungi server.');
            });
        }

        jQuery('#setting_sumber_jurnal_kas').on('change', function() {
            togglePublishBtn();
            loadSettingData();
        });
        jQuery('#setting_bulan_jurnal_kas, #setting_tahun_jurnal_kas').on('change', function() {
            togglePublishBtn();
            loadSettingData();
        });
        jQuery('#btn-setting-jurnal-kas-publish').on('click', publishSetting);
        jQuery('#tab-setting-jurnal-kas').on('shown.bs.tab', function() {
            loadTableList(false);
            togglePublishBtn();
            loadSettingData();
        });
        if (jQuery('#tab-setting-jurnal-kas').hasClass('active')) {
            loadTableList(false);
            togglePublishBtn();
            loadSettingData();
        }
    }

    if (document.readyState === 'complete') {
        bootSettingJurnalKas();
    } else {
        window.addEventListener('load', bootSettingJurnalKas);
    }
})();
</script>