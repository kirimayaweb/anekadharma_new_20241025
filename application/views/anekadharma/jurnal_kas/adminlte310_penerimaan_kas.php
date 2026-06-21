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
        if (!isset($bulan_ns_value) || $bulan_ns_value === '') {
            $bulan_ns_value = sprintf(
                '%04d-%02d',
                (int) date('Y', strtotime($date_akhir)),
                (int) date('m', strtotime($date_akhir))
            );
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
        $url_compare_penerimaan_kas_tabel_validate = isset($url_compare_penerimaan_kas_tabel_validate)
            ? $url_compare_penerimaan_kas_tabel_validate
            : site_url('Jurnal_kas/ajax_compare_tabel_validate_penerimaan_kas');
        $url_compare_penerimaan_kas_tabel_detail = isset($url_compare_penerimaan_kas_tabel_detail)
            ? $url_compare_penerimaan_kas_tabel_detail
            : site_url('Jurnal_kas/ajax_compare_tabel_detail_penerimaan_kas');
        $url_compare_penerimaan_kas_tabel_import = isset($url_compare_penerimaan_kas_tabel_import)
            ? $url_compare_penerimaan_kas_tabel_import
            : site_url('Jurnal_kas/ajax_compare_import_table_to_penerimaan_kas');
        $url_penerimaan_kas_excel = isset($url_penerimaan_kas_excel)
            ? $url_penerimaan_kas_excel
            : site_url('Jurnal_kas/excel_penerimaan_kas');
        $url_ajax_penerimaan_kas_input = isset($url_ajax_penerimaan_kas_input)
            ? $url_ajax_penerimaan_kas_input
            : site_url('Jurnal_kas/ajax_penerimaan_kas_input_action');
        if (!isset($list_kode_pl) || !is_array($list_kode_pl)) {
            $list_kode_pl = array();
        }
        $can_input_penerimaan_kas = isset($can_input_penerimaan_kas) ? (bool) $can_input_penerimaan_kas : false;
        if (!isset($penerimaan_rows) || !is_array($penerimaan_rows)) {
            $penerimaan_rows = array();
        }
        if (!isset($TOTAL_debet_11101_SEMUA)) {
            $TOTAL_debet_11101_SEMUA = 0;
        }
        if (!isset($TOTAL_kredit_11301_SEMUA)) {
            $TOTAL_kredit_11301_SEMUA = 0;
        }
        if (!isset($TOTAL_kredit_jumlah_SEMUA)) {
            $TOTAL_kredit_jumlah_SEMUA = 0;
        }
        if (!isset($modal_pk_tanggal_default)) {
            $modal_pk_tanggal_default = date('d-m-Y');
        }
        $this->load->helper('penerimaan_kas_list');
        $penerimaan_periode_label = $Get_date_awal . ' s/d ' . $Get_date_akhir;
        $penerimaan_bulan_label = bulan_teks($compare_bulan_num) . ' ' . $compare_tahun_num;

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
                                            <div class="penerimaan-kas-month-filter d-flex align-items-center flex-wrap" id="penerimaan-kas-month-filter">
                                                <input type="month" class="form-control form-control-sm mr-2" id="pk_bulan_ns" name="bulan_ns" value="<?php echo htmlspecialchars($bulan_ns_value, ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off" required />
                                                <button type="button" id="btn-cari-penerimaan-kas-bulan" class="btn btn-danger btn-sm btn-flat">
                                                    <i class="fa fa-search" aria-hidden="true"></i> Cari
                                                </button>
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
                                <a class="nav-link<?php echo $tab_data_active ? ' active' : ''; ?>" id="tab-penerimaan-data" data-toggle="pill" href="#panel-penerimaan-data" role="tab" aria-controls="panel-penerimaan-data" aria-selected="<?php echo $tab_data_active ? 'true' : 'false'; ?>">Data Jurnal Penerimaan Kas (<?php echo htmlspecialchars($penerimaan_bulan_label, ENT_QUOTES, 'UTF-8'); ?>)</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link<?php echo $tab_compare_active ? ' active' : ''; ?>" id="tab-compare-penerimaan-kas" data-toggle="pill" href="#panel-compare-penerimaan-kas" role="tab" aria-controls="panel-compare-penerimaan-kas" aria-selected="<?php echo $tab_compare_active ? 'true' : 'false'; ?>">Compare Data Manual - Online</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="jurnal-penerimaan-kas-tabs-content">
                            <div class="tab-pane fade<?php echo $tab_data_active ? ' show active' : ''; ?>" id="panel-penerimaan-data" role="tabpanel" aria-labelledby="tab-penerimaan-data">

                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 penerimaan-kas-tab1-toolbar">
                            <div>
                                <h5 class="mb-0 text-primary"><strong>Data Jurnal Penerimaan Kas</strong> <span class="text-muted" id="penerimaan-kas-label-periode">(<?php echo htmlspecialchars($penerimaan_bulan_label, ENT_QUOTES, 'UTF-8'); ?>)</span></h5>
                                <small class="text-muted">Periode: <span id="penerimaan-kas-label-range"><?php echo htmlspecialchars($penerimaan_periode_label, ENT_QUOTES, 'UTF-8'); ?></span> — pilih bulan di atas untuk memuat ulang otomatis</small>
                                <div id="penerimaan-kas-month-loading" class="text-info d-none mt-1"><i class="fas fa-spinner fa-spin"></i> Memuat data jurnal penerimaan kas...</div>
                            </div>
                            <div class="d-flex flex-wrap align-items-center mt-2 mt-md-0">
                                <?php if ($can_input_penerimaan_kas) { ?>
                                <button type="button" class="btn btn-warning mr-2 mb-2 mb-md-0" id="btn-penerimaan-kas-input-data" data-toggle="modal" data-target="#modal-penerimaan-kas-input">
                                    <i class="fa fa-plus"></i> Input Data
                                </button>
                                <?php } ?>
                                <button type="button" class="btn btn-success mb-2 mb-md-0" id="btn-penerimaan-kas-excel">
                                    <i class="fa fa-file-excel-o"></i> Cetak ke Excel
                                </button>
                            </div>
                        </div>

                        <div id="penerimaan-kas-table-wrap" class="penerimaan-kas-dt-wrap">
                        <table id="penerimaan-kas-datatable" class="table table-bordered display nowrap penerimaan-kas-dt-table" style="width:100%">
                            <colgroup>
                                <col class="pk-col-no">
                                <col class="pk-col-tanggal">
                                <col class="pk-col-kode">
                                <col class="pk-col-bukti">
                                <col class="pk-col-pl">
                                <col class="pk-col-ket">
                                <col class="pk-col-debit">
                                <col class="pk-col-kredit">
                                <col class="pk-col-rek">
                                <col class="pk-col-jumlah">
                            </colgroup>
                            <thead>
                                <tr class="pk-head-group">
                                    <th rowspan="3" class="pk-th-sort">No</th>
                                    <th rowspan="3" class="pk-th-sort">Tanggal</th>
                                    <th rowspan="3" class="pk-th-sort">Kode Akun</th>
                                    <th rowspan="3" class="pk-th-sort">No. Bukti BKM</th>
                                    <th rowspan="3" class="pk-th-sort">PL</th>
                                    <th rowspan="3" class="pk-th-sort">KETERANGAN</th>
                                    <th colspan="1" class="pk-th-debit-group pk-th-no-sort">DEBIT</th>
                                    <th colspan="3" class="pk-th-kredit-group pk-th-no-sort">KREDIT</th>
                                </tr>
                                <tr class="pk-head-group">
                                    <th rowspan="2" class="pk-th-sort text-right">11101-Kas Besar</th>
                                    <th rowspan="2" class="pk-th-sort text-right">11301-PU Non Angsuran</th>
                                    <th colspan="2" class="pk-th-serba-group pk-th-no-sort">Serba - serbi</th>
                                </tr>
                                <tr class="pk-head-leaf">
                                    <th class="pk-th-sort">No. Rek</th>
                                    <th class="pk-th-sort text-right">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($penerimaan_rows as $row) {
                                    $is_subtotal = (isset($row['type']) && $row['type'] === 'subtotal');
                                    $tr_class = $is_subtotal ? ' class="pk-row-subtotal"' : '';
                                    $fmt_force = $is_subtotal;
                                ?>
                                <tr<?php echo $tr_class; ?>>
                                    <?php
                                    $pk_order_tanggal = penerimaan_kas_parse_tanggal_to_ts(isset($row['tanggal']) ? $row['tanggal'] : '');
                                    $pk_order_debet = penerimaan_kas_parse_amount(isset($row['debet_11101']) ? $row['debet_11101'] : 0);
                                    $pk_order_kredit = penerimaan_kas_parse_amount(isset($row['kredit_11301']) ? $row['kredit_11301'] : 0);
                                    $pk_order_jumlah = penerimaan_kas_parse_amount(isset($row['jumlah']) ? $row['jumlah'] : 0);
                                    $pk_order_no = is_numeric($row['no']) ? (float) $row['no'] : 0;
                                    ?>
                                    <td data-order="<?php echo htmlspecialchars((string) $pk_order_no, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($row['no'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td data-order="<?php echo $pk_order_tanggal ? (int) $pk_order_tanggal : 0; ?>"><?php echo htmlspecialchars($row['tanggal'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row['kode_akun'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row['bukti'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row['pl'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row['keterangan'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="pk-cell-amount text-right" data-order="<?php echo htmlspecialchars((string) $pk_order_debet, ENT_QUOTES, 'UTF-8'); ?>"><?php echo penerimaan_kas_format_rupiah($row['debet_11101'], $fmt_force); ?></td>
                                    <td class="pk-cell-amount text-right" data-order="<?php echo htmlspecialchars((string) $pk_order_kredit, ENT_QUOTES, 'UTF-8'); ?>"><?php echo penerimaan_kas_format_rupiah($row['kredit_11301'], $fmt_force); ?></td>
                                    <td><?php echo htmlspecialchars($row['kode_rekening'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="pk-cell-amount text-right" data-order="<?php echo htmlspecialchars((string) $pk_order_jumlah, ENT_QUOTES, 'UTF-8'); ?>"><?php echo penerimaan_kas_format_rupiah($row['jumlah'], $fmt_force); ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>

                            <!-- tfoot -->

                            <tfoot>
                                <tr class="pk-row-footer-grand">
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th class="text-right">GRAND TOTAL</th>
                                    <th class="text-right"><?php echo number_format($TOTAL_debet_11101_SEMUA, 2, ',', '.'); ?></th>
                                    <th class="text-right"></th>
                                    <th></th>
                                    <th class="text-right"><?php echo number_format($TOTAL_kredit_jumlah_SEMUA + $TOTAL_kredit_11301_SEMUA, 2, ',', '.'); ?></th>
                                </tr>
                            </tfoot>



                            <!-- end of tfoot -->


                        </table>
                        </div><!-- /#penerimaan-kas-table-wrap -->
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

                                <div id="compare-penerimaan-tabel-actions" class="compare-penerimaan-tabel-info-box py-3 mb-3 d-none">
                                    <div id="compare-penerimaan-tabel-info-body" class="mb-2"></div>
                                    <div id="compare-penerimaan-tabel-import-note" class="small mb-2"></div>
                                    <div class="d-flex flex-wrap align-items-center">
                                        <button type="button" id="btn-compare-penerimaan-tabel-detail" class="btn btn-outline-primary btn-sm mr-2 mb-1">
                                            <i class="fas fa-table"></i> Detail Tabel
                                        </button>
                                        <button type="button" id="btn-compare-penerimaan-tabel-import" class="btn btn-success btn-sm mb-1" disabled>
                                            <i class="fas fa-database"></i> Simpan ke jurnal_penerimaan_kas
                                        </button>
                                    </div>
                                </div>

                                <div class="alert alert-secondary py-2" id="compare-penerimaan-info-ringkas">
                                    <strong>Bulan:</strong> <span id="compare-penerimaan-label-bulan"><?php echo htmlspecialchars($penerimaan_bulan_label, ENT_QUOTES, 'UTF-8'); ?></span>
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

                                <div class="modal fade" id="modal-compare-penerimaan-tabel-detail" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document" style="max-width:95%;">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info text-white py-2">
                                                <h5 class="modal-title">Detail Tabel — Import Jurnal Penerimaan Kas</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <div class="modal-body pb-2">
                                                <p class="text-muted small mb-2" id="compare-penerimaan-tabel-detail-meta">Memuat...</p>
                                                <table id="table-compare-penerimaan-tabel-detail" class="table table-bordered table-striped table-sm" style="width:100%;font-size:12px;">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Tanggal</th>
                                                            <th>PL</th>
                                                            <th>Bukti BKM</th>
                                                            <th>Keterangan</th>
                                                            <th>No. Rek</th>
                                                            <th>Debet</th>
                                                            <th>Kredit</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                    <tfoot>
                                                        <tr class="compare-dt-total-row">
                                                            <th colspan="6" class="text-right">Total</th>
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
                        </div><!-- /.tab-content -->
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </section>

    <?php if ($can_input_penerimaan_kas) { ?>
    <div class="modal fade" id="modal-penerimaan-kas-input" tabindex="-1" role="dialog" aria-labelledby="modal-penerimaan-kas-input-title" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document" style="max-width:960px;">
            <div class="modal-content">
                <div class="modal-header bg-warning py-2">
                    <h5 class="modal-title" id="modal-penerimaan-kas-input-title"><i class="fa fa-edit"></i> Input Penerimaan Kas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="form-penerimaan-kas-input-modal" autocomplete="off">
                    <div class="modal-body">
                        <div id="penerimaan-kas-input-modal-errors" class="alert alert-danger d-none" role="alert"></div>
                        <p class="text-muted small mb-3">Field wajib: Tanggal, PL, Keterangan. Minimal salah satu dari Debet 11101, Kredit 11301, Serba-Serbi Rekening, atau Serba-Serbi Jumlah harus diisi.</p>
                        <div class="row">
                            <div class="form-group col-md-3 col-sm-6 mb-2">
                                <label for="modal_pk_tanggal">Tanggal <span class="text-danger">*</span></label>
                                <div class="input-group date" id="modal_pk_tanggal_wrap" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#modal_pk_tanggal_wrap" id="modal_pk_tanggal" name="tanggal" value="<?php echo htmlspecialchars($modal_pk_tanggal_default, ENT_QUOTES, 'UTF-8'); ?>" required />
                                    <div class="input-group-append" data-target="#modal_pk_tanggal_wrap" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3 col-sm-6 mb-2">
                                <label for="modal_pk_bukti">No. Bukti BKM</label>
                                <input type="text" name="nomorbuktibkm" id="modal_pk_bukti" class="form-control" placeholder="Opsional">
                            </div>
                            <div class="form-group col-md-3 col-sm-6 mb-2">
                                <label for="modal_pk_pl">PL <span class="text-danger">*</span></label>
                                <select name="pl" id="modal_pk_pl" class="form-control modal-pk-select2" style="width:100%;" required>
                                    <option value="">Pilih Kode PL</option>
                                    <?php foreach ($list_kode_pl as $pl_row) { ?>
                                        <option value="<?php echo htmlspecialchars($pl_row->kode_pl, ENT_QUOTES, 'UTF-8'); ?>">
                                            <?php echo strtoupper($pl_row->kode_pl) . ' ==> ' . strtoupper($pl_row->keterangan); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3 col-sm-6 mb-2">
                                <label for="modal_pk_keterangan">Keterangan <span class="text-danger">*</span></label>
                                <input type="text" name="keterangan" id="modal_pk_keterangan" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3 col-sm-6 mb-2">
                                <label for="modal_pk_debet">11101 - Kas Besar</label>
                                <input type="text" name="debet_11101_kas_besar" id="modal_pk_debet" class="form-control" placeholder="0">
                            </div>
                            <div class="form-group col-md-3 col-sm-6 mb-2">
                                <label for="modal_pk_kredit">11301 - PU Non Angsuran</label>
                                <input type="text" name="kredit_11301_pu_non_angsuran" id="modal_pk_kredit" class="form-control" placeholder="0">
                            </div>
                            <div class="form-group col-md-3 col-sm-6 mb-2">
                                <label for="modal_pk_rekening">Serba-Serbi Rekening</label>
                                <input type="text" name="serba_serbi_rekening" id="modal_pk_rekening" class="form-control" placeholder="No. rekening">
                            </div>
                            <div class="form-group col-md-3 col-sm-6 mb-2">
                                <label for="modal_pk_jumlah">Serba-Serbi Jumlah</label>
                                <input type="text" name="serba_serbi_jumlah" id="modal_pk_jumlah" class="form-control" placeholder="0">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer py-2">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btn-penerimaan-kas-input-simpan">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php } ?>
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
    .compare-penerimaan-tabel-info-box {
        border: 1px solid #dee2e6;
        border-left: 4px solid #17a2b8;
        border-radius: 6px;
        background: #f8fbff;
        padding: 12px 16px;
    }
    .compare-penerimaan-tabel-info-box .compare-info-title {
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 8px;
        color: #0c5460;
    }
    .compare-penerimaan-tabel-info-box .compare-info-line {
        font-size: 13px;
        margin-bottom: 4px;
        line-height: 1.45;
    }
    .compare-penerimaan-tabel-info-box .compare-info-line strong { color: #212529; }
    .compare-penerimaan-tabel-info-box .compare-info-line code {
        background: #eef6ff;
        padding: 1px 4px;
        border-radius: 3px;
        font-size: 12px;
    }
    .compare-penerimaan-tabel-info-box #compare-penerimaan-tabel-import-note.text-success { color: #155724 !important; }
    .compare-penerimaan-tabel-info-box #compare-penerimaan-tabel-import-note.text-danger { color: #721c24 !important; }
    .compare-penerimaan-tabel-info-box .compare-info-title.text-warning { color: #856404; }
    .compare-penerimaan-tabel-info-box .compare-info-title.text-danger { color: #721c24; }
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
    .penerimaan-kas-tab1-toolbar { padding: 10px 12px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 6px; }

    /* ===== Penerimaan Kas DataTable — border kuning hanya wrapper ===== */
    .penerimaan-kas-dt-wrap {
        border: 2px solid #ffc107;
        border-radius: 4px;
        padding: 8px;
        background: #fff;
        overflow-x: auto;
    }
    .penerimaan-kas-dt-wrap .dataTables_wrapper {
        width: 100%;
        min-width: 1180px;
    }
    .penerimaan-kas-dt-table {
        margin-bottom: 0 !important;
        table-layout: fixed;
        width: 100% !important;
        min-width: 1180px;
        border-collapse: collapse;
    }
    .penerimaan-kas-dt-table col.pk-col-no { width: 45px; }
    .penerimaan-kas-dt-table col.pk-col-tanggal { width: 95px; }
    .penerimaan-kas-dt-table col.pk-col-kode { width: 100px; }
    .penerimaan-kas-dt-table col.pk-col-bukti { width: 115px; }
    .penerimaan-kas-dt-table col.pk-col-pl { width: 55px; }
    .penerimaan-kas-dt-table col.pk-col-ket { width: 260px; }
    .penerimaan-kas-dt-table col.pk-col-debit { width: 130px; }
    .penerimaan-kas-dt-table col.pk-col-kredit { width: 150px; }
    .penerimaan-kas-dt-table col.pk-col-rek { width: 90px; }
    .penerimaan-kas-dt-table col.pk-col-jumlah { width: 115px; }
    .penerimaan-kas-dt-table thead th,
    .penerimaan-kas-dt-table tbody td,
    .penerimaan-kas-dt-table tfoot th {
        border: 1px solid #dee2e6 !important;
        vertical-align: middle;
        font-size: 13px;
        padding: 6px 8px;
    }
    .penerimaan-kas-dt-table thead th {
        background: #f8f9fa;
        font-weight: 500;
        text-align: center;
        white-space: nowrap;
        line-height: 1.35;
    }
    .penerimaan-kas-dt-table thead th.pk-th-debit-group,
    .penerimaan-kas-dt-table thead th.pk-th-kredit-group,
    .penerimaan-kas-dt-table thead th.pk-th-serba-group {
        text-align: center;
        font-weight: 600;
    }
    .penerimaan-kas-dt-table thead th.pk-th-no-sort {
        cursor: default !important;
    }
    .penerimaan-kas-dt-table thead th.pk-th-sort,
    .penerimaan-kas-dt-wrap table.dataTable thead th.pk-th-sort.sorting,
    .penerimaan-kas-dt-wrap table.dataTable thead th.pk-th-sort.sorting_asc,
    .penerimaan-kas-dt-wrap table.dataTable thead th.pk-th-sort.sorting_desc {
        cursor: pointer;
        padding-right: 24px !important;
        position: relative;
    }
    .penerimaan-kas-dt-wrap table.dataTable thead th.pk-th-no-sort.sorting,
    .penerimaan-kas-dt-wrap table.dataTable thead th.pk-th-no-sort.sorting_asc,
    .penerimaan-kas-dt-wrap table.dataTable thead th.pk-th-no-sort.sorting_desc {
        background-image: none !important;
        cursor: default !important;
        padding-right: 8px !important;
    }
    .penerimaan-kas-dt-wrap table.dataTable thead th.pk-th-no-sort:before,
    .penerimaan-kas-dt-wrap table.dataTable thead th.pk-th-no-sort:after {
        display: none !important;
    }
    .penerimaan-kas-dt-table thead th.text-right,
    .penerimaan-kas-dt-table tbody td:nth-child(7),
    .penerimaan-kas-dt-table tbody td:nth-child(8),
    .penerimaan-kas-dt-table tbody td:nth-child(10),
    .penerimaan-kas-dt-table tfoot th:nth-child(7),
    .penerimaan-kas-dt-table tfoot th:nth-child(8),
    .penerimaan-kas-dt-table tfoot th:nth-child(10) {
        text-align: right;
    }
    .penerimaan-kas-dt-table tbody td:nth-child(9),
    .penerimaan-kas-dt-table tfoot th:nth-child(9) {
        text-align: center;
    }
    .penerimaan-kas-dt-table tbody td {
        background: #fff;
        word-wrap: break-word;
    }
    .penerimaan-kas-dt-table tbody td:first-child,
    .penerimaan-kas-dt-table thead th:first-child,
    .penerimaan-kas-dt-table tfoot th:first-child {
        text-align: center;
    }
    .penerimaan-kas-dt-table tbody tr.pk-row-subtotal td {
        background: #f8f9fa;
        font-weight: 600;
    }
    .penerimaan-kas-dt-table tfoot th {
        background: #f8f9fa;
        font-weight: 600;
    }
    .penerimaan-kas-dt-wrap .dataTables_info,
    .penerimaan-kas-dt-wrap .dataTables_length,
    .penerimaan-kas-dt-wrap .dataTables_filter,
    .penerimaan-kas-dt-wrap .dataTables_paginate {
        font-size: 13px;
    }
    #modal-penerimaan-kas-input .modal-header { border-bottom: none; }
    #modal-penerimaan-kas-input .modal-content { border: none; box-shadow: 0 8px 30px rgba(0,0,0,.18); border-radius: 8px; overflow: hidden; }
    #modal-penerimaan-kas-input .modal-body { background: #fafbfc; }
    #modal-penerimaan-kas-input label { font-weight: 600; font-size: 13px; margin-bottom: 4px; }
    #modal-penerimaan-kas-input .select2-container { width: 100% !important; }
    .penerimaan-kas-month-filter input[type="month"] {
        width: 180px;
        min-width: 160px;
        max-width: 200px;
    }
    .penerimaan-kas-month-filter .btn {
        white-space: nowrap;
    }
</style>

<script>
(function() {
    var submitTimer = null;
    var urlExcel = <?php echo json_encode($url_penerimaan_kas_excel); ?>;
    var namaBulan = <?php echo json_encode($nama_bulan_id); ?>;
    var bulanNsServer = <?php echo json_encode($bulan_ns_value); ?>;
    var pageReady = false;
    var loading = false;
    var lastMonth = '';

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

    function daysInMonth(year, month) {
        return new Date(year, month, 0).getDate();
    }

    function pad2(n) {
        return String(n).padStart(2, '0');
    }

    function buildRangeLabel(parsed) {
        var awal = '01-' + pad2(parsed.month) + '-' + parsed.year;
        var akhir = pad2(daysInMonth(parsed.year, parsed.month)) + '-' + pad2(parsed.month) + '-' + parsed.year;
        return awal + ' s/d ' + akhir;
    }

    function getSelectedMonthValue() {
        var el = document.getElementById('pk_bulan_ns');
        return el ? String(el.value || '').trim() : '';
    }

    function showMonthLoading() {
        var elLoading = document.getElementById('penerimaan-kas-month-loading');
        var elWrap = document.getElementById('penerimaan-kas-table-wrap');
        if (elLoading) {
            elLoading.classList.remove('d-none');
        }
        if (elWrap) {
            elWrap.style.opacity = '0.45';
            elWrap.style.pointerEvents = 'none';
        }
    }

    function syncCompareBulanTahunFromMonth(monthValue) {
        var parsed = parseMonthValue(monthValue);
        if (!parsed) {
            return;
        }

        var $bulan = window.jQuery ? jQuery('#compare_bulan_penerimaan') : null;
        var $tahun = window.jQuery ? jQuery('#compare_tahun_penerimaan') : null;
        if ($bulan && $bulan.length) {
            $bulan.val(String(parsed.month));
        }
        if ($tahun && $tahun.length) {
            if ($tahun.find('option[value="' + parsed.year + '"]').length === 0) {
                $tahun.prepend(jQuery('<option>', { value: parsed.year, text: parsed.year }));
            }
            $tahun.val(String(parsed.year));
        }

        var bulanLabel = (namaBulan && namaBulan[parsed.month]) ? namaBulan[parsed.month] : String(parsed.month);
        var labelText = bulanLabel + ' ' + parsed.year;
        var rangeText = buildRangeLabel(parsed);

        jQuery('#penerimaan-kas-label-periode').text('(' + labelText + ')');
        jQuery('#penerimaan-kas-label-range').text(rangeText);
        jQuery('#compare-penerimaan-label-bulan').text(labelText);
        jQuery('#tab-penerimaan-data').text('Data Jurnal Penerimaan Kas (' + labelText + ')');

        if (window.jQuery && typeof window.toggleBtnsPenerimaanKas === 'function') {
            window.toggleBtnsPenerimaanKas();
        }
    }

    function submitCariPenerimaanKasByMonth(monthValue, force) {
        if (loading) {
            return;
        }
        var parsed = parseMonthValue(monthValue);
        if (!parsed) {
            if (force) {
                alert('Pilih bulan terlebih dahulu.');
            }
            return;
        }
        if (!force && !pageReady) {
            return;
        }
        if (!force && monthValue === lastMonth) {
            return;
        }

        var form = document.getElementById('form-cari-penerimaan-kas');
        if (!form) {
            return;
        }

        lastMonth = monthValue;
        loading = true;
        showMonthLoading();
        syncCompareBulanTahunFromMonth(monthValue);
        form.submit();
    }

    function scheduleSubmitByMonth(monthValue) {
        clearTimeout(submitTimer);
        submitTimer = setTimeout(function() {
            submitCariPenerimaanKasByMonth(monthValue, false);
        }, 300);
    }

    function exportPenerimaanKasExcel() {
        var monthValue = getSelectedMonthValue();
        if (!parseMonthValue(monthValue)) {
            alert('Pilih bulan terlebih dahulu.');
            return;
        }
        var f = document.createElement('form');
        f.method = 'post';
        f.action = urlExcel;
        f.target = '_blank';
        f.style.display = 'none';

        var inpBulan = document.createElement('input');
        inpBulan.type = 'hidden';
        inpBulan.name = 'bulan_ns';
        inpBulan.value = monthValue;
        f.appendChild(inpBulan);

        document.body.appendChild(f);
        f.submit();
        document.body.removeChild(f);
    }

    function initAutoCariPenerimaanKas() {
        var form = document.getElementById('form-cari-penerimaan-kas');
        var bulanNs = document.getElementById('pk_bulan_ns');
        if (!form || !bulanNs) {
            return;
        }

        if (!bulanNs.value && bulanNsServer) {
            bulanNs.value = bulanNsServer;
        }
        lastMonth = bulanNs.value || bulanNsServer || '';
        syncCompareBulanTahunFromMonth(lastMonth);

        var btnExcel = document.getElementById('btn-penerimaan-kas-excel');
        if (btnExcel) {
            btnExcel.addEventListener('click', exportPenerimaanKasExcel);
        }

        var btnCari = document.getElementById('btn-cari-penerimaan-kas-bulan');
        if (btnCari) {
            btnCari.addEventListener('click', function() {
                submitCariPenerimaanKasByMonth(getSelectedMonthValue(), true);
            });
        }

        function handleMonthChange() {
            var val = bulanNs.value || '';
            if (!val || val === lastMonth) {
                return;
            }
            scheduleSubmitByMonth(val);
        }

        bulanNs.addEventListener('change', handleMonthChange);
        bulanNs.addEventListener('input', handleMonthChange);

        setTimeout(function() {
            pageReady = true;
        }, 600);
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
        return;
    }

    var $wrap = jQuery('#penerimaan-kas-table-wrap');
    var $table = jQuery('#panel-penerimaan-data #penerimaan-kas-datatable');
    if (!$wrap.length || !$table.length) {
        return;
    }

    if (jQuery.fn.DataTable.isDataTable('#tglSPOPFreeze')) {
        jQuery('#tglSPOPFreeze').DataTable().destroy();
    }

    $table.find('tbody tr').each(function() {
        var $tr = jQuery(this);
        if ($tr.find('.pk-cell-subtotal').length) {
            $tr.addClass('pk-row-subtotal');
        }
    });

    if (jQuery.fn.DataTable.isDataTable($table)) {
        $table.DataTable().clear().destroy();
        $table.find('tfoot').appendTo($table);
    }

    jQuery('.DTFC_Cloned').remove();
    jQuery('.DTFC_LeftWrapper, .DTFC_RightWrapper').remove();

    var pkColWidths = ['45px', '95px', '100px', '115px', '55px', '260px', '130px', '150px', '90px', '115px'];
    var pkColClasses = ['pk-col-no', 'pk-col-tanggal', 'pk-col-kode', 'pk-col-bukti', 'pk-col-pl', 'pk-col-ket', 'pk-col-debit', 'pk-col-kredit', 'pk-col-rek', 'pk-col-jumlah'];
    var dt = null;

    function applyPenerimaanKasColWidths() {
        $table.find('colgroup col').each(function(i) {
            if (pkColWidths[i]) {
                jQuery(this).attr('class', pkColClasses[i]).css('width', pkColWidths[i]);
            }
        });
    }

    function fixPenerimaanKasHeaderSort() {
        $table.find('thead th.pk-th-no-sort')
            .removeClass('sorting sorting_asc sorting_desc sorting_disabled')
            .attr('aria-sort', null)
            .css('cursor', 'default');
    }

    dt = $table.DataTable({
        paging: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']],
        ordering: true,
        order: [],
        orderCellsTop: false,
        orderClasses: false,
        searching: true,
        info: true,
        autoWidth: false,
        columnDefs: [
            { orderable: true, targets: '_all' },
            { width: pkColWidths[0], targets: 0 },
            { width: pkColWidths[1], targets: 1 },
            { width: pkColWidths[2], targets: 2 },
            { width: pkColWidths[3], targets: 3 },
            { width: pkColWidths[4], targets: 4 },
            { width: pkColWidths[5], targets: 5 },
            { width: pkColWidths[6], targets: 6, className: 'text-right' },
            { width: pkColWidths[7], targets: 7, className: 'text-right' },
            { width: pkColWidths[8], targets: 8 },
            { width: pkColWidths[9], targets: 9, className: 'text-right' }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.11.4/i18n/id.json'
        },
        drawCallback: function() {
            applyPenerimaanKasColWidths();
            fixPenerimaanKasHeaderSort();
        },
        initComplete: function() {
            applyPenerimaanKasColWidths();
            fixPenerimaanKasHeaderSort();
        }
    });

    dt.on('order.dt', function() {
        fixPenerimaanKasHeaderSort();
    });

    $table.on('click', 'thead th.pk-th-no-sort', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        return false;
    });

    jQuery(window).on('resize.penerimaanKasDt', function() {
        applyPenerimaanKasColWidths();
    });

    jQuery('#tab-penerimaan-data').on('shown.bs.tab', function() {
        setTimeout(function() {
            applyPenerimaanKasColWidths();
            if (dt) {
                dt.columns.adjust();
            }
        }, 50);
    });

    applyPenerimaanKasColWidths();
});
</script>

<?php if ($can_input_penerimaan_kas) { ?>
<script>
window.addEventListener('load', function() {
    if (!window.jQuery) {
        return;
    }

    var urlAjaxInput = <?php echo json_encode($url_ajax_penerimaan_kas_input); ?>;
    var modalTanggalFallback = <?php echo json_encode($modal_pk_tanggal_default); ?>;
    var $modal = jQuery('#modal-penerimaan-kas-input');
    var $form = jQuery('#form-penerimaan-kas-input-modal');
    var $btnSimpan = jQuery('#btn-penerimaan-kas-input-simpan');
    var $errors = jQuery('#penerimaan-kas-input-modal-errors');
    var saving = false;
    var modalPluginsReady = false;

    function showModalError(msg) {
        if (!msg) {
            $errors.addClass('d-none').empty();
            return;
        }
        $errors.removeClass('d-none').html('<i class="fa fa-exclamation-circle"></i> ' + jQuery('<span>').text(msg).html());
    }

    function setModalTanggalDefault() {
        jQuery('#modal_pk_tanggal').val(modalTanggalFallback);
        var $wrap = jQuery('#modal_pk_tanggal_wrap');
        if ($wrap.data('datetimepicker') && typeof moment !== 'undefined') {
            var m = moment(modalTanggalFallback, 'DD-MM-YYYY', true);
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
        jQuery('.modal-pk-select2').val('').trigger('change');
        showModalError('');
    }

    function initModalPlugins() {
        if (!$modal.length) {
            return;
        }
        jQuery('.modal-pk-select2').each(function() {
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
        if (!jQuery('#modal_pk_tanggal_wrap').data('datetimepicker')) {
            jQuery('#modal_pk_tanggal_wrap').datetimepicker({ format: 'D-M-YYYY' });
        }
        modalPluginsReady = true;
    }

    function reloadPenerimaanKasPage() {
        window.location.reload();
    }

    function showSavingAlert() {
        if (typeof Swal === 'undefined') {
            return;
        }
        Swal.fire({
            title: 'Menyimpan Data...',
            html: '<div style="font-size:14px;">Mohon tunggu, sedang memproses penerimaan kas.</div>',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: function() {
                Swal.showLoading();
            }
        });
    }

    function closeSavingAlert() {
        if (typeof Swal !== 'undefined' && Swal.isVisible()) {
            Swal.close();
        }
    }

    function showSaveSuccessAlert() {
        if (typeof Swal === 'undefined') {
            alert('Data penerimaan kas berhasil disimpan.');
            reloadPenerimaanKasPage();
            return;
        }
        Swal.fire({
            icon: 'success',
            title: 'Simpan Berhasil!',
            html: '<div style="font-size:15px;line-height:1.6;">Proses simpan penerimaan kas telah <strong>terproses dan sukses</strong>.<br><span class="text-muted" style="font-size:13px;">Halaman akan dimuat ulang otomatis...</span></div>',
            confirmButtonText: 'OK',
            confirmButtonColor: '#28a745',
            timer: 2000,
            timerProgressBar: true,
            allowOutsideClick: false
        }).then(function() {
            reloadPenerimaanKasPage();
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
        $btnSimpan.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Proses...');
        showSavingAlert();

        jQuery.ajax({
            url: urlAjaxInput,
            type: 'POST',
            dataType: 'json',
            data: formData
        }).done(function(res) {
            closeSavingAlert();
            if (!res || !res.ok) {
                var msg = (res && res.message) ? res.message : 'Gagal menyimpan data penerimaan kas.';
                showModalError(msg);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Gagal Menyimpan', text: msg, confirmButtonColor: '#dc3545' });
                }
                return;
            }
            $modal.modal('hide');
            showSaveSuccessAlert();
        }).fail(function() {
            closeSavingAlert();
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
    var urlValidate = <?php echo json_encode($url_compare_penerimaan_kas_tabel_validate); ?>;
    var urlDetail = <?php echo json_encode($url_compare_penerimaan_kas_tabel_detail); ?>;
    var urlTabelImport = <?php echo json_encode($url_compare_penerimaan_kas_tabel_import); ?>;
    var lastResult = null, dtMap = {}, tablesLoaded = false, csvBusy = false, csvLast = null;
    var tabelImportState = null, tabelDetailDt = null, tabelImportBusy = false;

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
    window.toggleBtnsPenerimaanKas = toggleBtns;

    function hideTabelActions() {
        tabelImportState = null;
        jQuery('#compare-penerimaan-tabel-actions').addClass('d-none');
        jQuery('#compare-penerimaan-tabel-info-body').empty();
        jQuery('#btn-compare-penerimaan-tabel-import').prop('disabled', true);
        jQuery('#compare-penerimaan-tabel-import-note').text('').removeClass('text-danger text-success text-muted');
    }

    function buildTabelInfoHtml(res) {
        var tbl = jQuery('#compare_tabel_penerimaan').val() || (res && res.table) || '—';
        var bk = bulanKey() || (res && res.bulan) || '—';
        var stats = (res && res.stats) ? res.stats : {};
        var map = (res && res.map) ? res.map : {};
        var mapParts = [];
        var mapOrder = ['tanggal', 'pl', 'keterangan', 'bukti', 'kode_rekening', 'debet', 'kredit'];
        mapOrder.forEach(function(key) {
            if (map[key]) {
                mapParts.push(key + ' → <code>' + jQuery('<span>').text(map[key]).html() + '</code>');
            }
        });
        var html = '<div class="compare-info-title"><i class="fas fa-info-circle"></i> Informasi Tabel — Siap Diproses ke Jurnal Penerimaan Kas</div>';
        html += '<div class="compare-info-line">Tabel terpilih: <strong>' + jQuery('<span>').text(tbl).html() + '</strong></div>';
        html += '<div class="compare-info-line">Bulan proses: <strong>' + jQuery('<span>').text(bk).html() + '</strong></div>';
        html += '<div class="compare-info-line">Kolom wajib: <strong>tanggal</strong>, <strong>pl</strong>, <strong>keterangan</strong>, minimal salah satu <strong>debet/kredit/rekening</strong></div>';
        if (mapParts.length) {
            html += '<div class="compare-info-line">Mapping kolom: ' + mapParts.join(' | ') + '</div>';
        }
        if (stats.saveable_in_bulan != null) {
            html += '<div class="compare-info-line">Baris siap simpan: <strong>' + (stats.saveable_in_bulan || 0) + '</strong>';
            if (stats.in_bulan != null) html += ' &nbsp;|&nbsp; baris bulan terpilih: <strong>' + (stats.in_bulan || 0) + '</strong>';
            if (stats.out_bulan > 0) html += ' &nbsp;|&nbsp; di luar bulan: <strong class="text-warning">' + stats.out_bulan + '</strong>';
            if (stats.invalid_in_bulan > 0) html += ' &nbsp;|&nbsp; tidak valid: <strong class="text-danger">' + stats.invalid_in_bulan + '</strong>';
            html += '</div>';
        }
        return html;
    }

    function applyTabelImportState(res) {
        tabelImportState = res || null;
        var tbl = jQuery('#compare_tabel_penerimaan').val() || '';
        if (!tbl) {
            hideTabelActions();
            return;
        }
        jQuery('#compare-penerimaan-tabel-actions').removeClass('d-none');
        jQuery('#btn-compare-penerimaan-tabel-detail').prop('disabled', false);
        if (!res || !res.eligible) {
            var miss = (res && res.missing_fields && res.missing_fields.length)
                ? ('Kolom kurang: <strong>' + res.missing_fields.join(', ') + '</strong>. ')
                : '';
            jQuery('#compare-penerimaan-tabel-info-body').html(
                '<div class="compare-info-title text-warning"><i class="fas fa-exclamation-triangle"></i> Tabel belum memenuhi syarat import</div>'
                + '<div class="compare-info-line">Tabel: <strong>' + jQuery('<span>').text(tbl).html() + '</strong></div>'
                + '<div class="compare-info-line">' + miss + jQuery('<span>').text((res && res.message) ? res.message : 'Kolom wajib minimal: tanggal, pl, keterangan, debet atau kredit.').html() + '</div>'
            );
            jQuery('#btn-compare-penerimaan-tabel-detail').prop('disabled', true);
            jQuery('#btn-compare-penerimaan-tabel-import').prop('disabled', true);
            jQuery('#compare-penerimaan-tabel-import-note').removeClass('text-danger text-success text-muted').addClass('text-muted').text('');
            return;
        }
        jQuery('#compare-penerimaan-tabel-info-body').html(buildTabelInfoHtml(res));
        var enabled = !!res.import_enabled;
        jQuery('#btn-compare-penerimaan-tabel-import').prop('disabled', !enabled);
        var $note = jQuery('#compare-penerimaan-tabel-import-note');
        $note.removeClass('text-danger text-success text-muted');
        if (enabled) {
            $note.addClass('text-success').html('<i class="fas fa-check-circle"></i> ' + (res.import_message || 'Tanggal data sesuai bulan terpilih — siap disimpan ke jurnal_penerimaan_kas.'));
        } else {
            $note.addClass('text-danger').html('<i class="fas fa-exclamation-circle"></i> ' + (res.import_message || 'Data tidak bisa dimasukkan ke jurnal penerimaan kas.'));
        }
    }

    function showTabelCheckingState(tbl) {
        jQuery('#compare-penerimaan-tabel-actions').removeClass('d-none');
        jQuery('#compare-penerimaan-tabel-info-body').html(
            '<div class="compare-info-title"><i class="fas fa-spinner fa-spin"></i> Memeriksa tabel terpilih...</div>'
            + '<div class="compare-info-line">Tabel: <strong>' + jQuery('<span>').text(tbl).html() + '</strong></div>'
        );
        jQuery('#compare-penerimaan-tabel-import-note').removeClass('text-danger text-success').addClass('text-muted').text('');
        jQuery('#btn-compare-penerimaan-tabel-detail').prop('disabled', true);
        jQuery('#btn-compare-penerimaan-tabel-import').prop('disabled', true);
    }

    function validateTabelForImport() {
        var tbl = jQuery('#compare_tabel_penerimaan').val() || '';
        if (!tbl) {
            hideTabelActions();
            return;
        }
        showTabelCheckingState(tbl);
        jQuery.ajax({
            url: urlValidate,
            type: 'POST',
            dataType: 'json',
            data: {
                tabel: tbl,
                bulan: bulanKey(),
                bulan_num: jQuery('#compare_bulan_penerimaan').val(),
                tahun: jQuery('#compare_tahun_penerimaan').val()
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
            jQuery('#compare-penerimaan-tabel-actions').removeClass('d-none');
            jQuery('#compare-penerimaan-tabel-info-body').html(
                '<div class="compare-info-title text-danger"><i class="fas fa-times-circle"></i> Gagal memeriksa tabel</div>'
                + '<div class="compare-info-line">Tabel: <strong>' + jQuery('<span>').text(tbl).html() + '</strong></div>'
                + '<div class="compare-info-line">' + jQuery('<span>').text(errMsg).html() + '</div>'
            );
            jQuery('#btn-compare-penerimaan-tabel-detail').prop('disabled', true);
            jQuery('#btn-compare-penerimaan-tabel-import').prop('disabled', true);
        });
    }

    function buildDetailRows(items) {
        return (items || []).map(function(it) {
            return [
                it.no || '',
                it.tanggal || '',
                it.pl ? jQuery('<span>').text(it.pl).html() : '',
                it.bukti ? jQuery('<span>').text(it.bukti).html() : '',
                it.keterangan ? '<span class="text-ket">' + jQuery('<span>').text(it.keterangan).html() + '</span>' : '',
                it.kode_rekening ? jQuery('<span>').text(it.kode_rekening).html() : '',
                fmtAmtCell(it.debet, 'debet'),
                fmtAmtCell(it.kredit, 'kredit')
            ];
        });
    }

    function openTabelDetailModal() {
        var tbl = jQuery('#compare_tabel_penerimaan').val() || '';
        var bk = bulanKey();
        if (!tbl || !bk) {
            alert('Pilih tabel dan bulan terlebih dahulu.');
            return;
        }
        jQuery('#compare-penerimaan-tabel-detail-meta').text('Memuat data tabel `' + tbl + '` bulan ' + bk + '...');
        jQuery('#modal-compare-penerimaan-tabel-detail').modal('show');
        jQuery.ajax({
            url: urlDetail,
            type: 'POST',
            dataType: 'json',
            data: { tabel: tbl, bulan: bk }
        }).done(function(res) {
            if (!res || !res.ok) {
                jQuery('#compare-penerimaan-tabel-detail-meta').text((res && res.message) || 'Gagal memuat detail tabel.');
                return;
            }
            var items = res.rows || [];
            jQuery('#compare-penerimaan-tabel-detail-meta').text(
                'Tabel: ' + (res.table || tbl) + ' | Bulan: ' + (res.bulan_label || bk) + ' | Total: ' + (res.total || items.length) + ' baris'
            );
            var $t = jQuery('#table-compare-penerimaan-tabel-detail');
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
            jQuery('#compare-penerimaan-tabel-detail-meta').text('Gagal memuat detail tabel.');
        });
    }

    function importTabelToPenerimaanKas() {
        if (tabelImportBusy) return;
        var tbl = jQuery('#compare_tabel_penerimaan').val() || '';
        var bk = bulanKey();
        if (!tbl || !bk) {
            alert('Pilih tabel dan bulan terlebih dahulu.');
            return;
        }
        if (!tabelImportState || !tabelImportState.import_enabled) {
            alert((tabelImportState && tabelImportState.import_message) || 'Data tidak bisa dimasukkan ke jurnal penerimaan kas.');
            return;
        }
        var confirmMsg = 'Proses simpan data tabel `' + tbl + '` ke jurnal_penerimaan_kas bulan ' + bk + '?\nRecord yang sama tidak akan disimpan ulang.';
        if (!window.confirm(confirmMsg)) return;
        tabelImportBusy = true;
        jQuery('#btn-compare-penerimaan-tabel-import').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
        jQuery.ajax({
            url: urlTabelImport,
            type: 'POST',
            dataType: 'json',
            data: { tabel: tbl, bulan: bk }
        }).done(function(res) {
            tabelImportBusy = false;
            jQuery('#btn-compare-penerimaan-tabel-import').html('<i class="fas fa-database"></i> Simpan ke jurnal_penerimaan_kas');
            if (!res || !res.ok) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Import Gagal', text: (res && res.message) || 'Gagal menambahkan data.' });
                } else {
                    alert((res && res.message) || 'Gagal menambahkan data.');
                }
                validateTabelForImport();
                return;
            }
            var msg = res.message || ('Berhasil menambahkan ' + (res.inserted || 0) + ' data.');
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'success', title: 'Simpan Berhasil', text: msg, confirmButtonText: 'OK', timer: 2000, timerProgressBar: true });
            } else {
                alert(msg);
            }
            setStatus('success', msg);
            validateTabelForImport();
        }).fail(function() {
            tabelImportBusy = false;
            jQuery('#btn-compare-penerimaan-tabel-import').html('<i class="fas fa-database"></i> Simpan ke jurnal_penerimaan_kas');
            validateTabelForImport();
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: 'Import Gagal', text: 'Tidak dapat menghubungi server.' });
            } else {
                alert('Import gagal.');
            }
        });
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
            toggleBtns();
            validateTabelForImport();
            return;
        }
        jQuery.ajax({ url: urlList, type: 'POST', dataType: 'json' }).done(function(res) {
            if (!res || !res.ok) { setStatus('danger', (res && res.message) || 'Gagal memuat daftar tabel.'); return; }
            var $sel = jQuery('#compare_tabel_penerimaan');
            var cur = selectTable || $sel.val();
            $sel.find('option:not(:first)').remove();
            (res.tables || []).forEach(function(tbl) { $sel.append(jQuery('<option>', { value: tbl, text: tbl })); });
            if (cur) $sel.val(cur);
            tablesLoaded = true;
            validateTabelForImport();
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
    jQuery('#compare_tabel_penerimaan').on('change', function() {
        toggleBtns();
        validateTabelForImport();
    });
    jQuery('#compare_bulan_penerimaan, #compare_tahun_penerimaan').on('change', function() {
        var b = parseInt(jQuery('#compare_bulan_penerimaan').val(), 10);
        var t = parseInt(jQuery('#compare_tahun_penerimaan').val(), 10);
        if (b && t) {
            var bulanNames = <?php echo json_encode($nama_bulan_id); ?>;
            var labelText = (bulanNames[b] || b) + ' ' + t;
            jQuery('#compare-penerimaan-label-bulan').text(labelText);
        }
        toggleBtns();
        if (jQuery('#compare_tabel_penerimaan').val()) {
            validateTabelForImport();
        }
    });
    jQuery('#btn-compare-penerimaan-kas').on('click', runCompare);
    jQuery('#btn-compare-penerimaan-excel-all').on('click', function() { exportExcel('semua'); });
    jQuery(document).on('click', '.btn-compare-penerimaan-excel', function() { exportExcel(jQuery(this).data('jenis')); });
    jQuery('#btn-compare-penerimaan-tabel-detail').on('click', openTabelDetailModal);
    jQuery('#btn-compare-penerimaan-tabel-import').on('click', importTabelToPenerimaanKas);
    jQuery('#tab-compare-penerimaan-kas').on('shown.bs.tab', function() {
        loadTableList(false);
        validateTabelForImport();
    });
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
    if (jQuery('#tab-compare-penerimaan-kas').hasClass('active')) {
        loadTableList(false);
        validateTabelForImport();
    }
    toggleBtns();
});
</script>