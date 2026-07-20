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
        $url_compare_pengeluaran_kas_tabel_validate = isset($url_compare_pengeluaran_kas_tabel_validate)
            ? $url_compare_pengeluaran_kas_tabel_validate
            : site_url('Jurnal_kas/ajax_compare_tabel_validate_pengeluaran_kas');
        $url_compare_pengeluaran_kas_tabel_detail = isset($url_compare_pengeluaran_kas_tabel_detail)
            ? $url_compare_pengeluaran_kas_tabel_detail
            : site_url('Jurnal_kas/ajax_compare_tabel_detail_pengeluaran_kas');
        $url_compare_pengeluaran_kas_tabel_import = isset($url_compare_pengeluaran_kas_tabel_import)
            ? $url_compare_pengeluaran_kas_tabel_import
            : site_url('Jurnal_kas/ajax_compare_import_table_to_pengeluaran_kas');
        $url_compare_pengeluaran_kas_tabel_detail_excel = isset($url_compare_pengeluaran_kas_tabel_detail_excel)
            ? $url_compare_pengeluaran_kas_tabel_detail_excel
            : site_url('Jurnal_kas/excel_compare_tabel_detail_pengeluaran_kas');
        $url_pengeluaran_kas_excel = isset($url_pengeluaran_kas_excel)
            ? $url_pengeluaran_kas_excel
            : site_url('Jurnal_kas/excel_pengeluaran_kas');
        $pengeluaran_periode_label = $Get_date_awal . ' s/d ' . $Get_date_akhir;
        $pengeluaran_bulan_label = bulan_teks($compare_bulan_num) . ' ' . $compare_tahun_num;

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

                                        <form id="form-cari-pengeluaran-kas" action="<?php echo $action_cari_between_date; ?>" method="post">
                                            <div class="pengeluaran-kas-month-filter d-flex align-items-center flex-wrap" id="pengeluaran-kas-month-filter">
                                                <input type="month" class="form-control form-control-sm mr-2" id="pgk_bulan_ns" name="bulan_ns" value="<?php echo htmlspecialchars($bulan_ns_value, ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off" required />
                                                <button type="button" id="btn-cari-pengeluaran-kas-bulan" class="btn btn-danger btn-sm btn-flat">
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

                        <ul class="nav nav-tabs jurnal-pengeluaran-kas-tabs" id="jurnal-pengeluaran-kas-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link<?php echo $tab_data_active ? ' active' : ''; ?>" id="tab-pengeluaran-data" data-toggle="pill" href="#panel-pengeluaran-data" role="tab" aria-controls="panel-pengeluaran-data" aria-selected="<?php echo $tab_data_active ? 'true' : 'false'; ?>">Data Jurnal Pengeluaran Kas (<?php echo htmlspecialchars($pengeluaran_bulan_label, ENT_QUOTES, 'UTF-8'); ?>)</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link<?php echo $tab_compare_active ? ' active' : ''; ?>" id="tab-compare-pengeluaran-kas" data-toggle="pill" href="#panel-compare-pengeluaran-kas" role="tab" aria-controls="panel-compare-pengeluaran-kas" aria-selected="<?php echo $tab_compare_active ? 'true' : 'false'; ?>">Compare Data Manual - Online</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="jurnal-pengeluaran-kas-tabs-content">
                            <div class="tab-pane fade<?php echo $tab_data_active ? ' show active' : ''; ?>" id="panel-pengeluaran-data" role="tabpanel" aria-labelledby="tab-pengeluaran-data">

                        <?php $this->load->helper('pengeluaran_kas_list'); include __DIR__ . '/adminlte310_pengeluaran_kas_tab_data.php'; ?>
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

                                <div id="compare-pengeluaran-tabel-actions" class="compare-pengeluaran-tabel-info-box py-3 mb-3 d-none">
                                    <div id="compare-pengeluaran-tabel-info-body" class="mb-2"></div>
                                    <div id="compare-pengeluaran-tabel-import-note" class="small mb-2"></div>
                                    <div class="d-flex flex-wrap align-items-center">
                                        <button type="button" id="btn-compare-pengeluaran-tabel-detail" class="btn btn-outline-primary btn-sm mr-2 mb-1">
                                            <i class="fas fa-table"></i> Detail Tabel
                                        </button>
                                        <button type="button" id="btn-compare-pengeluaran-tabel-import" class="btn btn-success btn-sm mb-1" disabled>
                                            <i class="fas fa-database"></i> Proses Simpan Data ke Tabel Jurnal Pengeluaran Kas
                                        </button>
                                    </div>
                                </div>

                                <div class="alert alert-secondary py-2" id="compare-pengeluaran-info-ringkas">
                                    <strong>Bulan:</strong> <span id="compare-pengeluaran-label-bulan"><?php echo htmlspecialchars($pengeluaran_bulan_label, ENT_QUOTES, 'UTF-8'); ?></span>
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

                                <div class="modal fade" id="modal-compare-pengeluaran-tabel-detail" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document" style="max-width:95%;">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info text-white py-2">
                                                <h5 class="modal-title">Detail Tabel — Import Jurnal Pengeluaran Kas</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <div class="modal-body pb-2">
                                                <div class="d-flex flex-wrap align-items-center mb-2">
                                                    <p class="text-muted small mb-0 mr-3" id="compare-pengeluaran-tabel-detail-meta">Memuat...</p>
                                                    <button type="button" id="btn-compare-pengeluaran-tabel-detail-excel" class="btn btn-success btn-sm">
                                                        <i class="fa fa-file-excel-o"></i> Cetak ke Excel
                                                    </button>
                                                </div>
                                                <table id="table-compare-pengeluaran-tabel-detail" class="table table-bordered table-striped table-sm" style="width:100%;font-size:12px;">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Tanggal</th>
                                                            <th>No. Bukti BKK</th>
                                                            <th>PL</th>
                                                            <th>Keterangan</th>
                                                            <th>21101-UU Dagang</th>
                                                            <th>No. Rek</th>
                                                            <th>Jumlah Serba-Serbi</th>
                                                            <th>11101-Kas Besar</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                    <tfoot>
                                                        <tr class="compare-dt-total-row">
                                                            <th colspan="5" class="text-right">Total</th>
                                                            <th class="compare-total-debet text-right">—</th>
                                                            <th></th>
                                                            <th class="compare-total-jumlah text-right">—</th>
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
    .pengeluaran-kas-tab1-toolbar { padding: 10px 12px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 6px; }

    /* ===== Pengeluaran Kas DataTable — border kuning hanya wrapper ===== */
    .pengeluaran-kas-dt-wrap {
        border: 1px solid #ffc107;
        border-radius: 4px;
        padding: 8px;
        background: #fff;
        overflow-x: auto;
    }
    .pengeluaran-kas-dt-wrap .dataTables_wrapper { width: 100%; }
    .pengeluaran-kas-dt-table {
        margin-bottom: 0 !important;
        table-layout: auto;
        width: 100% !important;
    }
    .pengeluaran-kas-dt-table col.pgk-col-no { width: 42px; }
    .pengeluaran-kas-dt-table col.pgk-col-tanggal { width: 108px; }
    .pengeluaran-kas-dt-table col.pgk-col-bukti { width: 115px; }
    .pengeluaran-kas-dt-table col.pgk-col-pl { width: 55px; }
    .pengeluaran-kas-dt-table col.pgk-col-ket { width: 240px; }
    .pengeluaran-kas-dt-table col.pgk-col-debit { width: 125px; }
    .pengeluaran-kas-dt-table col.pgk-col-rek { width: 88px; }
    .pengeluaran-kas-dt-table col.pgk-col-jumlah { width: 118px; }
    .pengeluaran-kas-dt-table col.pgk-col-kredit { width: 125px; }
    .pengeluaran-kas-dt-table thead th,
    .pengeluaran-kas-dt-table tbody td,
    .pengeluaran-kas-dt-table tfoot th {
        border: 1px solid #dee2e6 !important;
        vertical-align: middle;
        font-size: 13px;
        padding: 5px 8px;
    }
    .pengeluaran-kas-dt-table thead th {
        background: #f8f9fa;
        font-weight: 600;
        text-align: center;
        white-space: nowrap;
        line-height: 1.35;
    }
    .pengeluaran-kas-dt-table thead th.pgk-th-debit-group,
    .pengeluaran-kas-dt-table thead th.pgk-th-kredit-group,
    .pengeluaran-kas-dt-table thead th.pgk-th-serba-group {
        text-align: center;
        font-weight: 700;
    }
    .pengeluaran-kas-dt-table thead th.pgk-th-no-sort {
        cursor: default !important;
    }
    .pengeluaran-kas-dt-table tbody td { background: #fff; word-wrap: break-word; }
    .pengeluaran-kas-dt-table tbody td:first-child,
    .pengeluaran-kas-dt-table thead th:first-child,
    .pengeluaran-kas-dt-table tfoot th:first-child { text-align: center; }
    .pengeluaran-kas-dt-table tfoot th { background: #f8f9fa; font-weight: 600; }
    .pengeluaran-kas-dt-table tfoot tr.pgk-row-footer-balance th {
        background: #fff8e1;
        border-top: 2px solid #ffc107;
    }
    .pengeluaran-kas-dt-wrap table.dataTable thead .sorting:before,
    .pengeluaran-kas-dt-wrap table.dataTable thead .sorting:after,
    .pengeluaran-kas-dt-wrap table.dataTable thead .sorting_asc:before,
    .pengeluaran-kas-dt-wrap table.dataTable thead .sorting_asc:after,
    .pengeluaran-kas-dt-wrap table.dataTable thead .sorting_desc:before,
    .pengeluaran-kas-dt-wrap table.dataTable thead .sorting_desc:after { display: none !important; }
    .pengeluaran-kas-dt-wrap table.dataTable thead th.sorting,
    .pengeluaran-kas-dt-wrap table.dataTable thead th.sorting_asc,
    .pengeluaran-kas-dt-wrap table.dataTable thead th.sorting_desc {
        background-image: none !important;
        padding-right: 8px !important;
    }
    .pgk-cell-tanggal {
        min-width: 108px;
        padding: 4px 6px !important;
        vertical-align: top;
    }
    .pgk-tanggal-text {
        font-size: 14px;
        font-weight: 600;
        line-height: 1.25;
        color: #212529;
    }
    .pgk-row-actions {
        margin-top: 2px;
        line-height: 1;
    }
    .pgk-row-actions .btn-pgk-action {
        font-size: 10px;
        line-height: 1.15;
        padding: 1px 5px;
        margin-right: 2px;
        margin-bottom: 1px;
    }
    .pengeluaran-kas-dt-table tbody td:nth-child(6),
    .pengeluaran-kas-dt-table tbody td:nth-child(8),
    .pengeluaran-kas-dt-table tbody td:nth-child(9),
    .pengeluaran-kas-dt-table tfoot th:nth-child(6),
    .pengeluaran-kas-dt-table tfoot th:nth-child(8),
    .pengeluaran-kas-dt-table tfoot th:nth-child(9) {
        text-align: right;
    }
    .pengeluaran-kas-dt-table tbody td:nth-child(7),
    .pengeluaran-kas-dt-table tfoot th:nth-child(7) {
        text-align: center;
    }
    #modal-pengeluaran-kas-form .modal-header { border-bottom: none; }
    #modal-pengeluaran-kas-form .modal-content { border: none; box-shadow: 0 8px 30px rgba(0,0,0,.18); border-radius: 8px; overflow: hidden; }
    #modal-pengeluaran-kas-form .modal-body { background: #fafbfc; }
    #modal-pengeluaran-kas-form label { font-weight: 600; font-size: 13px; margin-bottom: 4px; }
    #modal-pengeluaran-kas-form .select2-container { width: 100% !important; }
    .pengeluaran-kas-month-filter input[type="month"] {
        width: 180px;
        min-width: 160px;
        max-width: 200px;
    }
    .pengeluaran-kas-month-filter .btn { white-space: nowrap; }
    .compare-pengeluaran-tabel-info-box {
        border: 1px solid #dee2e6;
        border-left: 4px solid #17a2b8;
        border-radius: 8px;
        background: linear-gradient(90deg, #e8f7fa 0%, #fff 40%);
        padding: 12px 16px;
    }
    .compare-pengeluaran-tabel-info-box .compare-info-title {
        font-weight: 700;
        font-size: 14px;
        margin-bottom: 6px;
        color: #0c5460;
    }
    .compare-pengeluaran-tabel-info-box .compare-info-line {
        font-size: 13px;
        margin-bottom: 4px;
        color: #495057;
    }
    .compare-pengeluaran-tabel-info-box .compare-info-line strong { color: #212529; }
    .compare-pengeluaran-tabel-info-box .compare-info-line code {
        background: #f1f3f5;
        padding: 1px 4px;
        border-radius: 3px;
        font-size: 12px;
    }
    .compare-pengeluaran-tabel-info-box #compare-pengeluaran-tabel-import-note.text-success { color: #155724 !important; }
    .compare-pengeluaran-tabel-info-box #compare-pengeluaran-tabel-import-note.text-danger { color: #721c24 !important; }
    .compare-pengeluaran-tabel-info-box .compare-info-title.text-warning { color: #856404; }
    .compare-pengeluaran-tabel-info-box .compare-info-title.text-danger { color: #721c24; }
</style>

<?php include __DIR__ . '/adminlte310_pengeluaran_kas_scripts.php'; ?>

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
    var urlValidate = <?php echo json_encode($url_compare_pengeluaran_kas_tabel_validate); ?>;
    var urlDetail = <?php echo json_encode($url_compare_pengeluaran_kas_tabel_detail); ?>;
    var urlTabelImport = <?php echo json_encode($url_compare_pengeluaran_kas_tabel_import); ?>;
    var urlDetailExcel = <?php echo json_encode($url_compare_pengeluaran_kas_tabel_detail_excel); ?>;
    var lastResult = null, dtMap = {}, tablesLoaded = false, csvBusy = false, csvLast = null;
    var tabelImportState = null, tabelImportBusy = false, tabelDetailDt = null, tabelDetailItems = [];

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
    window.toggleBtnsPengeluaranKas = toggleBtns;

    function hideTabelActions() {
        tabelImportState = null;
        jQuery('#compare-pengeluaran-tabel-actions').addClass('d-none');
        jQuery('#compare-pengeluaran-tabel-info-body').empty();
        jQuery('#btn-compare-pengeluaran-tabel-import').prop('disabled', true);
        jQuery('#compare-pengeluaran-tabel-import-note').text('').removeClass('text-danger text-success text-muted');
    }

    function buildTabelInfoHtml(res) {
        var tbl = jQuery('#compare_tabel_pengeluaran').val() || (res && res.table) || '—';
        var bk = bulanKey() || (res && res.bulan) || '—';
        var stats = (res && res.stats) ? res.stats : {};
        var map = (res && res.map) ? res.map : {};
        var mapParts = [];
        var mapOrder = ['tanggal', 'nomor_bukti_bkk', 'pl', 'keterangan', 'debet_21101uu_dagang', 'serba_serbi_nomor_rekening', 'serba_serbi_jumlah', 'kredit_11101_kas_besar'];
        mapOrder.forEach(function(key) {
            if (map[key]) {
                mapParts.push(key + ' → <code>' + jQuery('<span>').text(map[key]).html() + '</code>');
            }
        });
        var html = '<div class="compare-info-title"><i class="fas fa-info-circle"></i> Informasi Tabel — Siap Diproses ke Jurnal Pengeluaran Kas</div>';
        html += '<div class="compare-info-line">Tabel terpilih: <strong>' + jQuery('<span>').text(tbl).html() + '</strong></div>';
        html += '<div class="compare-info-line">Bulan proses: <strong>' + jQuery('<span>').text(bk).html() + '</strong></div>';
        if (mapParts.length) {
            html += '<div class="compare-info-line">Mapping kolom: ' + mapParts.join(' | ') + '</div>';
        }
        if (stats.saveable_in_bulan != null) {
            html += '<div class="compare-info-line">Baris siap simpan: <strong>' + (stats.saveable_in_bulan || 0) + '</strong>';
            if (stats.in_bulan != null) html += ' &nbsp;|&nbsp; baris bulan terpilih: <strong>' + (stats.in_bulan || 0) + '</strong>';
            if (stats.out_bulan > 0) html += ' &nbsp;|&nbsp; di luar bulan: <strong class="text-warning">' + stats.out_bulan + '</strong>';
            html += '</div>';
        }
        return html;
    }

    function applyTabelImportState(res) {
        tabelImportState = res || null;
        var tbl = jQuery('#compare_tabel_pengeluaran').val() || '';
        if (!tbl) {
            hideTabelActions();
            return;
        }
        jQuery('#compare-pengeluaran-tabel-actions').removeClass('d-none');
        jQuery('#btn-compare-pengeluaran-tabel-detail').prop('disabled', false);
        if (!res || !res.eligible) {
            var miss = (res && res.missing_fields && res.missing_fields.length)
                ? ('Kolom kurang: <strong>' + res.missing_fields.join(', ') + '</strong>. ')
                : '';
            jQuery('#compare-pengeluaran-tabel-info-body').html(
                '<div class="compare-info-title text-warning"><i class="fas fa-exclamation-triangle"></i> Tabel belum memenuhi syarat import</div>'
                + '<div class="compare-info-line">Tabel: <strong>' + jQuery('<span>').text(tbl).html() + '</strong></div>'
                + '<div class="compare-info-line">' + miss + jQuery('<span>').text((res && res.message) ? res.message : 'Kolom wajib tidak lengkap.').html() + '</div>'
            );
            jQuery('#btn-compare-pengeluaran-tabel-detail').prop('disabled', true);
            jQuery('#btn-compare-pengeluaran-tabel-import').prop('disabled', true);
            jQuery('#compare-pengeluaran-tabel-import-note').removeClass('text-danger text-success text-muted').addClass('text-muted').text('');
            return;
        }
        jQuery('#compare-pengeluaran-tabel-info-body').html(buildTabelInfoHtml(res));
        var enabled = !!res.import_enabled;
        jQuery('#btn-compare-pengeluaran-tabel-import').prop('disabled', !enabled);
        var $note = jQuery('#compare-pengeluaran-tabel-import-note');
        $note.removeClass('text-danger text-success text-muted');
        if (enabled) {
            $note.addClass('text-success').html('<i class="fas fa-check-circle"></i> ' + (res.import_message || 'Tanggal data sesuai bulan terpilih — siap disimpan ke jurnal_pengeluaran_kas.'));
        } else {
            $note.addClass('text-danger').html('<i class="fas fa-exclamation-circle"></i> ' + (res.import_message || 'Data tidak bisa dimasukkan ke jurnal pengeluaran kas.'));
        }
    }

    function showTabelCheckingState(tbl) {
        jQuery('#compare-pengeluaran-tabel-actions').removeClass('d-none');
        jQuery('#compare-pengeluaran-tabel-info-body').html(
            '<div class="compare-info-title"><i class="fas fa-spinner fa-spin"></i> Memeriksa tabel terpilih...</div>'
            + '<div class="compare-info-line">Tabel: <strong>' + jQuery('<span>').text(tbl).html() + '</strong></div>'
        );
        jQuery('#compare-pengeluaran-tabel-import-note').removeClass('text-danger text-success').addClass('text-muted').text('');
        jQuery('#btn-compare-pengeluaran-tabel-detail').prop('disabled', true);
        jQuery('#btn-compare-pengeluaran-tabel-import').prop('disabled', true);
    }

    function validateTabelForImport() {
        var tbl = jQuery('#compare_tabel_pengeluaran').val() || '';
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
                bulan_num: jQuery('#compare_bulan_pengeluaran').val(),
                tahun: jQuery('#compare_tahun_pengeluaran').val()
            }
        }).done(function(res) {
            applyTabelImportState(res);
        }).fail(function() {
            jQuery('#compare-pengeluaran-tabel-actions').removeClass('d-none');
            jQuery('#compare-pengeluaran-tabel-info-body').html(
                '<div class="compare-info-title text-danger"><i class="fas fa-times-circle"></i> Gagal memeriksa tabel</div>'
                + '<div class="compare-info-line">Tabel: <strong>' + jQuery('<span>').text(tbl).html() + '</strong></div>'
            );
            jQuery('#btn-compare-pengeluaran-tabel-detail').prop('disabled', true);
            jQuery('#btn-compare-pengeluaran-tabel-import').prop('disabled', true);
        });
    }
    window.validateTabelPengeluaranForImport = validateTabelForImport;

    function buildDetailRows(items) {
        return (items || []).map(function(it) {
            return [
                it.no || '',
                it.tanggal || '',
                it.nomor_bukti_bkk ? jQuery('<span>').text(it.nomor_bukti_bkk).html() : '',
                it.pl ? jQuery('<span>').text(it.pl).html() : '',
                it.keterangan ? '<span class="text-ket">' + jQuery('<span>').text(it.keterangan).html() + '</span>' : '',
                fmtAmtCell(it.debet_21101uu_dagang, 'deb'),
                it.serba_serbi_nomor_rekening ? jQuery('<span>').text(it.serba_serbi_nomor_rekening).html() : '',
                fmtAmtCell(it.serba_serbi_jumlah, 'jml'),
                fmtAmtCell(it.kredit_11101_kas_besar, 'kre')
            ];
        });
    }

    function openTabelDetailModal() {
        var tbl = jQuery('#compare_tabel_pengeluaran').val() || '';
        var bk = bulanKey();
        if (!tbl || !bk) {
            alert('Pilih tabel dan bulan terlebih dahulu.');
            return;
        }
        jQuery('#compare-pengeluaran-tabel-detail-meta').text('Memuat data tabel `' + tbl + '` bulan ' + bk + '...');
        jQuery('#modal-compare-pengeluaran-tabel-detail').modal('show');
        jQuery.ajax({
            url: urlDetail,
            type: 'POST',
            dataType: 'json',
            data: { tabel: tbl, bulan: bk }
        }).done(function(res) {
            if (!res || !res.ok) {
                jQuery('#compare-pengeluaran-tabel-detail-meta').text((res && res.message) || 'Gagal memuat detail tabel.');
                return;
            }
            tabelDetailItems = res.rows || [];
            jQuery('#compare-pengeluaran-tabel-detail-meta').text(
                'Tabel: ' + (res.table || tbl) + ' | Bulan: ' + (res.bulan_label || bk) + ' | Total: ' + (res.total || tabelDetailItems.length) + ' baris'
            );
            var $t = jQuery('#table-compare-pengeluaran-tabel-detail');
            if (jQuery.fn.DataTable.isDataTable($t)) {
                $t.DataTable().clear().destroy();
            }
            $t.find('tbody').empty();
            tabelDetailDt = $t.DataTable({
                data: buildDetailRows(tabelDetailItems),
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
                    var td = 0, tj = 0, tk = 0;
                    tabelDetailItems.forEach(function(it) {
                        td += parseAmt(it.debet_21101uu_dagang);
                        tj += parseAmt(it.serba_serbi_jumlah);
                        tk += parseAmt(it.kredit_11101_kas_besar);
                    });
                    $t.find('.compare-total-debet').text(td > 0 ? td.toLocaleString('id-ID') : '—');
                    $t.find('.compare-total-jumlah').text(tj > 0 ? tj.toLocaleString('id-ID') : '—');
                    $t.find('.compare-total-kredit').text(tk > 0 ? tk.toLocaleString('id-ID') : '—');
                }
            });
        }).fail(function() {
            jQuery('#compare-pengeluaran-tabel-detail-meta').text('Gagal memuat detail tabel.');
        });
    }

    function exportTabelDetailExcel() {
        var tbl = jQuery('#compare_tabel_pengeluaran').val() || '';
        var bk = bulanKey();
        if (!tbl || !bk) {
            alert('Pilih tabel dan bulan terlebih dahulu.');
            return;
        }
        var f = jQuery('<form method="post" target="_blank"></form>');
        f.attr('action', urlDetailExcel);
        f.append(jQuery('<input type="hidden" name="tabel">').val(tbl));
        f.append(jQuery('<input type="hidden" name="bulan">').val(bk));
        jQuery('body').append(f);
        f.submit();
        f.remove();
    }

    function importTabelToPengeluaranKas() {
        if (tabelImportBusy) return;
        var tbl = jQuery('#compare_tabel_pengeluaran').val() || '';
        var bk = bulanKey();
        if (!tbl || !bk) {
            alert('Pilih tabel dan bulan terlebih dahulu.');
            return;
        }
        if (!tabelImportState || !tabelImportState.import_enabled) {
            alert((tabelImportState && tabelImportState.import_message) || 'Data tidak bisa dimasukkan ke jurnal pengeluaran kas.');
            return;
        }
        var confirmMsg = 'Proses simpan semua data tabel `' + tbl + '` ke jurnal_pengeluaran_kas bulan ' + bk + '?\nSemua baris dengan tanggal pada bulan terpilih akan langsung disimpan (tanpa cek duplikat).';
        if (!window.confirm(confirmMsg)) return;

        tabelImportBusy = true;
        jQuery('#btn-compare-pengeluaran-tabel-import').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
        if (typeof Swal !== 'undefined') {
            Swal.fire({ title: 'Memproses Simpan...', html: 'Menyimpan data ke jurnal_pengeluaran_kas', allowOutsideClick: false, didOpen: function() { Swal.showLoading(); } });
        }
        jQuery.ajax({
            url: urlTabelImport,
            type: 'POST',
            dataType: 'json',
            data: { tabel: tbl, bulan: bk }
        }).done(function(res) {
            tabelImportBusy = false;
            jQuery('#btn-compare-pengeluaran-tabel-import').html('<i class="fas fa-database"></i> Proses Simpan Data ke Tabel Jurnal Pengeluaran Kas');
            if (typeof Swal !== 'undefined') Swal.close();
            if (!res || !res.ok) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Import Gagal', text: (res && res.message) || 'Gagal menambahkan data.' });
                } else {
                    alert((res && res.message) || 'Gagal menambahkan data.');
                }
                validateTabelForImport();
                return;
            }
            var msg = res.message || ('Berhasil menambahkan ' + (res.inserted || 0) + ' data ke jurnal_pengeluaran_kas.');
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Simpan Berhasil!',
                    html: '<div style="font-size:15px;">' + jQuery('<span>').text(msg).html() + '<br><span class="text-muted" style="font-size:13px;">Datatable Tab 1 akan dimuat ulang otomatis...</span></div>',
                    confirmButtonText: 'OK',
                    timer: 2500,
                    timerProgressBar: true
                });
            } else {
                alert(msg);
            }
            setStatus('success', '<i class="fas fa-check-circle"></i> ' + msg);
            validateTabelForImport();
            if (typeof window.reloadPengeluaranKasTable === 'function') {
                window.reloadPengeluaranKasTable(getSelectedMonthFromHeader());
            }
        }).fail(function() {
            tabelImportBusy = false;
            jQuery('#btn-compare-pengeluaran-tabel-import').html('<i class="fas fa-database"></i> Proses Simpan Data ke Tabel Jurnal Pengeluaran Kas');
            if (typeof Swal !== 'undefined') Swal.close();
            validateTabelForImport();
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: 'Import Gagal', text: 'Tidak dapat menghubungi server.' });
            } else {
                alert('Import gagal.');
            }
        });
    }

    function getSelectedMonthFromHeader() {
        var el = document.getElementById('pgk_bulan_ns');
        return el ? String(el.value || '').trim() : '';
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
        }).always(function() {
            toggleBtns();
            validateTabelForImport();
        });
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
            validateTabelForImport();
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
    jQuery('#compare_bulan_pengeluaran, #compare_tahun_pengeluaran').on('change', function() {
        toggleBtns();
        validateTabelForImport();
    });
    jQuery('#compare_tabel_pengeluaran').on('change', function() {
        toggleBtns();
        validateTabelForImport();
    });
    jQuery('#btn-compare-pengeluaran-kas').on('click', runCompare);
    jQuery('#btn-compare-pengeluaran-excel-all').on('click', exportExcel);
    jQuery('#btn-compare-pengeluaran-tabel-detail').on('click', openTabelDetailModal);
    jQuery('#btn-compare-pengeluaran-tabel-detail-excel').on('click', exportTabelDetailExcel);
    jQuery('#btn-compare-pengeluaran-tabel-import').on('click', importTabelToPengeluaranKas);
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