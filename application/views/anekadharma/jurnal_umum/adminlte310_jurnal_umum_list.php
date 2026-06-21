<?php
$this->load->helper('jurnal_umum_list');

$tab_data_active = (!isset($active_tab) || $active_tab !== 'compare');
$tab_compare_active = (isset($active_tab) && $active_tab === 'compare');

if (!isset($nama_bulan_id) || !is_array($nama_bulan_id)) {
    $nama_bulan_id = array(
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    );
}
if (!isset($compare_bulan_num)) {
    $compare_bulan_num = (int) date('m');
}
if (!isset($compare_tahun_num)) {
    $compare_tahun_num = (int) date('Y');
}
if (!isset($gen_tahun_min)) {
    $gen_tahun_min = 2019;
}
if (!isset($gen_tahun_max)) {
    $gen_tahun_max = (int) date('Y') + 1;
}
if (!isset($bulan_ns_value)) {
    $bulan_ns_value = sprintf('%04d-%02d', (int) $compare_tahun_num, (int) $compare_bulan_num);
}
if (!isset($bulan_label)) {
    $bulan_label = jurnal_umum_bulan_teks($compare_bulan_num) . ' ' . $compare_tahun_num;
}
if (!isset($periode_label)) {
    $periode_label = $bulan_label;
}
if (!isset($Data_Jurnal_Umum)) {
    $Data_Jurnal_Umum = array();
}
if (!isset($Total_debet)) {
    $Total_debet = 0;
}
if (!isset($Total_kredit)) {
    $Total_kredit = 0;
}

$url_cari = isset($url_cari_between_date) ? $url_cari_between_date : site_url('Jurnal_umum/cari_between_date');
$url_excel = isset($url_jurnal_umum_excel) ? $url_jurnal_umum_excel : site_url('Jurnal_umum/excel_list');
$url_compare_run = isset($url_compare_jurnal_umum_run) ? $url_compare_jurnal_umum_run : site_url('Jurnal_umum/ajax_compare_jurnal_umum_manual_online');
$url_compare_excel = isset($url_compare_jurnal_umum_excel) ? $url_compare_jurnal_umum_excel : site_url('Jurnal_umum/excel_compare_jurnal_umum_manual_online');
$url_compare_import = isset($url_compare_jurnal_umum_import_csv) ? $url_compare_jurnal_umum_import_csv : site_url('Jurnal_umum/ajax_compare_import_csv_jurnal_umum');
$url_compare_list = isset($url_compare_jurnal_umum_tabel_list) ? $url_compare_jurnal_umum_tabel_list : site_url('Jurnal_umum/ajax_compare_tabel_list_jurnal_umum');
$url_compare_validate = isset($url_compare_jurnal_umum_tabel_validate) ? $url_compare_jurnal_umum_tabel_validate : site_url('Jurnal_umum/ajax_compare_tabel_validate_jurnal_umum');
$url_compare_detail = isset($url_compare_jurnal_umum_tabel_detail) ? $url_compare_jurnal_umum_tabel_detail : site_url('Jurnal_umum/ajax_compare_tabel_detail_jurnal_umum');
$url_compare_tabel_import = isset($url_compare_jurnal_umum_tabel_import) ? $url_compare_jurnal_umum_tabel_import : site_url('Jurnal_umum/ajax_compare_import_table_to_jurnal_umum');
$url_compare_detail_excel = isset($url_compare_jurnal_umum_tabel_detail_excel) ? $url_compare_jurnal_umum_tabel_detail_excel : site_url('Jurnal_umum/excel_compare_tabel_detail_jurnal_umum');

$compare_sections = array(
    array('jenis' => 'data_manual', 'num' => '1', 'label' => 'Data Manual', 'subtitle' => 'Tabel CSV / database terpilih', 'badge' => 'compare-ju-badge-manual', 'table' => 'table-compare-ju-manual', 'theme' => 'primary', 'icon' => 'fa-database', 'col' => 'col-lg-6'),
    array('jenis' => 'data_online', 'num' => '2', 'label' => 'Data Online', 'subtitle' => 'Data jurnal_umum bulan terpilih', 'badge' => 'compare-ju-badge-online', 'table' => 'table-compare-ju-online', 'theme' => 'info', 'icon' => 'fa-cloud', 'col' => 'col-lg-6'),
    array('jenis' => 'data_cocok', 'num' => '3', 'label' => 'Data Cocok (Manual & Online)', 'subtitle' => 'Tanggal, bukti, PL, ref, kode rekening, rekening, debet, kredit sama', 'badge' => 'compare-ju-badge-cocok', 'table' => 'table-compare-ju-cocok', 'theme' => 'success', 'icon' => 'fa-check-circle', 'col' => 'col-lg-6'),
    array('jenis' => 'manual_tidak_di_online', 'num' => '4', 'label' => 'Manual Tidak Ada di Online', 'subtitle' => 'Ada di manual, tidak cocok / tidak ada di jurnal_umum', 'badge' => 'compare-ju-badge-manual-miss', 'table' => 'table-compare-ju-manual-miss', 'theme' => 'warning', 'icon' => 'fa-exclamation-triangle', 'col' => 'col-lg-6'),
    array('jenis' => 'online_tidak_di_manual', 'num' => '5', 'label' => 'Online Tidak Ada di Manual', 'subtitle' => 'Ada di jurnal_umum, tidak cocok di manual', 'badge' => 'compare-ju-badge-online-miss', 'table' => 'table-compare-ju-online-miss', 'theme' => 'cyan', 'icon' => 'fa-exchange-alt', 'col' => 'col-lg-12'),
);
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1 class="m-0"></h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right"></ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="box box-warning box-solid">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-lg-4">
                                <strong>JURNAL UMUM</strong>
                                <span class="text-muted small d-block"><?php echo htmlspecialchars($bulan_label, ENT_QUOTES, 'UTF-8'); ?></span>
                            </div>
                            <div class="col-lg-8">
                                <form id="form-cari-jurnal-umum" action="<?php echo htmlspecialchars($url_cari, ENT_QUOTES, 'UTF-8'); ?>" method="post">
                                    <input type="hidden" name="active_tab" id="active_tab_input" value="<?php echo $tab_compare_active ? 'compare' : 'data'; ?>">
                                    <div class="row justify-content-end align-items-center">
                                        <div class="col-md-5">
                                            <label for="bulan_ns" class="sr-only">Pilih Bulan</label>
                                            <input type="month" class="form-control" id="bulan_ns" name="bulan_ns" value="<?php echo htmlspecialchars($bulan_ns_value, ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <ul class="nav nav-tabs jurnal-umum-tabs" id="jurnal-umum-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link<?php echo $tab_data_active ? ' active' : ''; ?>" id="tab-ju-data" data-toggle="pill" href="#panel-ju-data" role="tab">Data Jurnal Umum</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link<?php echo $tab_compare_active ? ' active' : ''; ?>" id="tab-compare-ju" data-toggle="pill" href="#panel-compare-ju" role="tab">Compare Data Manual - Online</a>
                            </li>
                        </ul>

                        <div class="tab-content mt-3" id="jurnal-umum-tabs-content">
                            <div class="tab-pane fade<?php echo $tab_data_active ? ' show active' : ''; ?>" id="panel-ju-data" role="tabpanel">
                                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 jurnal-umum-tab1-toolbar">
                                    <div>
                                        <h5 class="mb-0 text-primary"><strong>Data Jurnal Umum</strong></h5>
                                        <small class="text-muted">Pilih bulan di atas — data otomatis dimuat dari tanggal 1 s/d akhir bulan terpilih</small>
                                    </div>
                                    <button type="button" class="btn btn-success mt-2 mt-md-0" id="btn-jurnal-umum-excel">
                                        <i class="fa fa-file-excel-o"></i> Cetak ke Excel
                                    </button>
                                </div>

                                <div class="ju-dt-wrap">
                                    <table id="table-jurnal-umum-data" class="table table-bordered table-striped table-sm display nowrap ju-main-dt" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th rowspan="2">No</th>
                                                <th rowspan="2">Tanggal</th>
                                                <th rowspan="2">Bukti</th>
                                                <th rowspan="2">PL</th>
                                                <th rowspan="2">Ref</th>
                                                <th colspan="2" class="text-center">Uraian</th>
                                                <th rowspan="2" class="text-right">Debet</th>
                                                <th rowspan="2" class="text-right">Kredit</th>
                                            </tr>
                                            <tr>
                                                <th>Kode Rek.</th>
                                                <th>Rek.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($Data_Jurnal_Umum as $list_data) { ?>
                                            <tr>
                                                <td><?php echo (int) $list_data['no']; ?></td>
                                                <td><?php echo htmlspecialchars($list_data['tanggal'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($list_data['bukti'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($list_data['pl'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($list_data['ref'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($list_data['kode_rekening'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($list_data['rekening'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="text-right"><?php echo htmlspecialchars($list_data['debet_display'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="text-right"><?php echo htmlspecialchars($list_data['kredit_display'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr class="ju-total-row">
                                                <th colspan="7" class="text-right">JUMLAH DEBET / KREDIT</th>
                                                <th class="text-right"><?php echo jurnal_umum_format_rupiah($Total_debet, true); ?></th>
                                                <th class="text-right"><?php echo jurnal_umum_format_rupiah($Total_kredit, true); ?></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade<?php echo $tab_compare_active ? ' show active' : ''; ?>" id="panel-compare-ju" role="tabpanel">
                                <small class="text-muted d-block mb-2">
                                    Bandingkan data jurnal umum online (<strong>jurnal_umum</strong>) dengan tabel manual hasil upload CSV.
                                    Kolom compare: <strong>tanggal, bukti, PL, ref, kode_rekening, rekening, debet, kredit</strong>.
                                </small>

                                <label for="compare_ju_csv_file" class="mb-1">Pilih file CSV</label>
                                <div class="d-flex flex-wrap align-items-end compare-csv-upload-row mb-3">
                                    <div class="custom-file custom-file-sm mb-0 compare-csv-file-wrap">
                                        <input type="file" class="custom-file-input" id="compare_ju_csv_file" accept=".csv,text/csv">
                                        <label class="custom-file-label" for="compare_ju_csv_file" data-browse="Pilih">Cari / pilih file CSV...</label>
                                    </div>
                                </div>

                                <div id="compare-ju-csv-upload-info" class="alert alert-light border py-2 d-none mb-3">
                                    <div class="small mb-1"><span class="text-muted">File:</span> <strong id="compare-ju-csv-filename">—</strong></div>
                                    <div class="small mb-1"><span class="text-muted">Tabel DB:</span> <strong id="compare-ju-csv-tablename" class="text-primary">—</strong> <span class="text-muted" id="compare-ju-csv-rowcount"></span></div>
                                </div>

                                <div class="row mb-3 align-items-end compare-toolbar-row flex-wrap">
                                    <div class="col-auto mb-2">
                                        <label for="compare_bulan_ju" class="small mb-1">Bulan</label>
                                        <select id="compare_bulan_ju" class="form-control form-control-sm compare-toolbar-control">
                                            <?php foreach ($nama_bulan_id as $num => $label_bulan) { ?>
                                                <option value="<?php echo (int) $num; ?>"<?php echo ((int) $num === (int) $compare_bulan_num) ? ' selected' : ''; ?>><?php echo htmlspecialchars($label_bulan, ENT_QUOTES, 'UTF-8'); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-auto mb-2">
                                        <label for="compare_tahun_ju" class="small mb-1">Tahun</label>
                                        <select id="compare_tahun_ju" class="form-control form-control-sm compare-toolbar-control">
                                            <?php for ($th = $gen_tahun_max; $th >= $gen_tahun_min; $th--) { ?>
                                                <option value="<?php echo (int) $th; ?>"<?php echo ((int) $th === (int) $compare_tahun_num) ? ' selected' : ''; ?>><?php echo (int) $th; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-auto mb-2">
                                        <label for="compare_tabel_ju" class="small mb-1">Pilih tabel</label>
                                        <select id="compare_tabel_ju" class="form-control form-control-sm compare-toolbar-control compare-toolbar-tabel">
                                            <option value="">— Muat daftar tabel —</option>
                                        </select>
                                    </div>
                                    <div class="col-auto mb-2">
                                        <label class="small mb-1 d-block">&nbsp;</label>
                                        <div class="d-flex flex-wrap align-items-center">
                                            <button type="button" id="btn-compare-ju" class="btn btn-info btn-sm d-none"><i class="fas fa-columns"></i> Compare</button>
                                            <button type="button" id="btn-compare-ju-excel-all" class="btn btn-success btn-sm d-none ml-2"><i class="fa fa-file-excel-o"></i> Cetak ke Excel</button>
                                        </div>
                                    </div>
                                </div>

                                <div id="compare-ju-tabel-actions" class="compare-ju-tabel-info-box py-3 mb-3 d-none">
                                    <div id="compare-ju-tabel-info-body" class="mb-2"></div>
                                    <div id="compare-ju-tabel-import-note" class="small mb-2"></div>
                                    <div class="d-flex flex-wrap align-items-center">
                                        <button type="button" id="btn-compare-ju-tabel-detail" class="btn btn-outline-primary btn-sm mr-2 mb-1">
                                            <i class="fas fa-table"></i> Detail Tabel
                                        </button>
                                        <button type="button" id="btn-compare-ju-tabel-import" class="btn btn-success btn-sm mb-1" disabled>
                                            <i class="fas fa-database"></i> Proses Simpan Data ke Tabel Jurnal_umum
                                        </button>
                                    </div>
                                </div>

                                <div class="alert alert-secondary py-2" id="compare-ju-info-ringkas">
                                    <strong>Bulan:</strong> <span id="compare-ju-label-bulan">—</span>
                                    &nbsp;|&nbsp; <strong>Tabel manual:</strong> <span id="compare-ju-label-tabel">—</span>
                                    &nbsp;|&nbsp; <strong>Manual:</strong> <span id="compare-ju-count-manual">—</span>
                                    &nbsp;|&nbsp; <strong>Online:</strong> <span id="compare-ju-count-online">—</span>
                                    &nbsp;|&nbsp; <strong>Cocok:</strong> <span id="compare-ju-count-cocok">—</span>
                                </div>
                                <div class="alert alert-info py-2 mb-3" id="compare-ju-status">
                                    Pilih file CSV, bulan, tahun, dan tabel manual — klik <strong>Compare</strong>.
                                </div>
                                <div class="alert alert-warning py-2 mb-3 d-none" id="compare-ju-field-info"></div>
                                <div class="alert alert-warning py-2 mb-3 d-none" id="compare-ju-warnings"></div>

                                <div id="compare-ju-results-panel" class="d-none">
                                    <h5 class="mb-3 text-primary"><i class="fas fa-chart-bar"></i> Hasil Komparasi Jurnal Umum</h5>
                                    <div class="row">
                                    <?php foreach ($compare_sections as $sec) { ?>
                                        <div class="<?php echo $sec['col']; ?> mb-3">
                                            <div class="compare-ju-section-card compare-theme-<?php echo $sec['theme']; ?>">
                                                <div class="compare-ju-section-header">
                                                    <div class="compare-ju-section-title">
                                                        <span class="compare-section-num"><?php echo $sec['num']; ?></span>
                                                        <i class="fas <?php echo $sec['icon']; ?> compare-section-icon"></i>
                                                        <div>
                                                            <div class="compare-section-label"><?php echo htmlspecialchars($sec['label'], ENT_QUOTES, 'UTF-8'); ?></div>
                                                            <div class="compare-section-subtitle"><?php echo htmlspecialchars($sec['subtitle'], ENT_QUOTES, 'UTF-8'); ?></div>
                                                        </div>
                                                    </div>
                                                    <span id="<?php echo $sec['badge']; ?>" class="badge compare-section-badge">0</span>
                                                </div>
                                                <div class="compare-dt-wrap">
                                                    <table id="<?php echo $sec['table']; ?>" class="table table-bordered table-sm compare-dt compare-ju-dt" style="width:100%;">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th><th>Tanggal</th><th>Bukti</th><th>PL</th><th>Ref</th>
                                                                <th>Kode Rek.</th><th>Rek.</th><th>Debet</th><th>Kredit</th><th>Catatan</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                        <tfoot>
                                                            <tr class="compare-dt-total-row">
                                                                <th colspan="7" class="text-right">Total</th>
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
                                    </div>
                                </div>

                                <div class="modal fade" id="modal-compare-ju-tabel-detail" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document" style="max-width:95%;">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info text-white py-2">
                                                <h5 class="modal-title" id="modal-compare-ju-tabel-detail-title">Detail Tabel</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <div class="modal-body pb-2">
                                                <div class="d-flex flex-wrap align-items-center mb-2">
                                                    <p class="text-muted small mb-0 mr-3" id="compare-ju-tabel-detail-meta">Memuat...</p>
                                                    <button type="button" id="btn-compare-ju-tabel-detail-excel" class="btn btn-success btn-sm">
                                                        <i class="fa fa-file-excel-o"></i> Cetak ke Excel
                                                    </button>
                                                </div>
                                                <table id="table-compare-ju-tabel-detail" class="table table-bordered table-striped table-sm" style="width:100%;font-size:12px;">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th><th>Tanggal</th><th>Bukti</th><th>PL</th><th>Ref</th>
                                                            <th>Kode Rek.</th><th>Rek.</th><th>Debet</th><th>Kredit</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                    <tfoot>
                                                        <tr class="compare-dt-total-row">
                                                            <th colspan="7" class="text-right">Total</th>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style type="text/css">
    .nav-tabs.jurnal-umum-tabs { border-bottom: 2px solid #007bff; margin-bottom: 0; }
    .nav-tabs.jurnal-umum-tabs .nav-link { border: 2px solid #007bff; border-bottom: none; color: #666; margin-right: 4px; border-radius: 4px 4px 0 0; background: #fff; }
    .nav-tabs.jurnal-umum-tabs .nav-link.active { background: #007bff; color: #fff; font-weight: bold; }
    .jurnal-umum-tab1-toolbar { padding: 10px 12px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 6px; }
    .ju-dt-wrap { border: 2px solid #007bff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,123,255,.12); padding: 8px; background: #fff; }
    .ju-main-dt thead th { background: linear-gradient(180deg, #e7f1ff, #f8f9fa); border-color: #b8daff !important; font-size: 12px; white-space: nowrap; vertical-align: middle; }
    .ju-main-dt tbody td { font-size: 12px; border-color: #dee2e6 !important; padding: 6px 8px; }
    .ju-main-dt tfoot .ju-total-row th { background: #fff3cd !important; font-weight: 700; border-color: #ffc107 !important; }
    .compare-toolbar-row .compare-toolbar-control { width: 110px; min-width: 110px; }
    #compare_tabel_ju.compare-toolbar-tabel { width: 320px; min-width: 240px; max-width: 420px; }
    .compare-csv-file-wrap { max-width: 520px; min-width: 280px; flex: 0 1 520px; }
    .compare-ju-section-card { border-radius: 10px; border: 1px solid #dee2e6; background: #fff; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.05); height: 100%; }
    .compare-ju-section-header { display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; border-bottom: 1px solid rgba(0,0,0,.08); }
    .compare-ju-section-title { display: flex; align-items: center; gap: 10px; }
    .compare-section-num { display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background: rgba(0,0,0,.08); font-weight: 700; font-size: 12px; }
    .compare-section-label { font-weight: 700; font-size: 14px; }
    .compare-section-subtitle { font-size: 11px; color: #6c757d; }
    .compare-theme-primary .compare-ju-section-header { background: linear-gradient(90deg, #e7f1ff, #fff); border-left: 4px solid #007bff; }
    .compare-theme-info .compare-ju-section-header { background: linear-gradient(90deg, #e8f7fa, #fff); border-left: 4px solid #17a2b8; }
    .compare-theme-success .compare-ju-section-header { background: linear-gradient(90deg, #e8f5e9, #fff); border-left: 4px solid #28a745; }
    .compare-theme-warning .compare-ju-section-header { background: linear-gradient(90deg, #fff8e1, #fff); border-left: 4px solid #ffc107; }
    .compare-theme-cyan .compare-ju-section-header { background: linear-gradient(90deg, #e0f7fa, #fff); border-left: 4px solid #17a2b8; }
    .compare-dt-wrap table.dataTable thead th { background: #f8f9fa; font-size: 11px; white-space: nowrap; }
    .compare-dt-wrap table.dataTable tbody td { font-size: 11px; padding: 5px 7px; vertical-align: middle; }
    .compare-dt-total-row th { background: #fff3cd !important; font-weight: 700; }
    .text-amount-debet { color: #155724; font-weight: 600; }
    .text-amount-kredit { color: #0c5460; font-weight: 600; }
    .text-catatan { font-size: 11px; color: #856404; }
    .compare-ju-tabel-info-box {
        border: 1px solid #b8daff; border-radius: 8px; background: linear-gradient(180deg, #f8fbff, #fff);
        padding: 12px 16px; box-shadow: 0 1px 6px rgba(0,123,255,.08);
    }
    .compare-ju-tabel-info-box .compare-info-title { font-weight: 700; margin-bottom: 6px; color: #004085; }
    .compare-ju-tabel-info-box .compare-info-line { font-size: 13px; margin-bottom: 4px; }
</style>

<script>
(function() {
    var urlExcel = <?php echo json_encode($url_excel); ?>;
    var urlCari = <?php echo json_encode($url_cari); ?>;
    var urlRun = <?php echo json_encode($url_compare_run); ?>;
    var urlCompareExcel = <?php echo json_encode($url_compare_excel); ?>;
    var urlImport = <?php echo json_encode($url_compare_import); ?>;
    var urlList = <?php echo json_encode($url_compare_list); ?>;
    var urlValidate = <?php echo json_encode($url_compare_validate); ?>;
    var urlDetail = <?php echo json_encode($url_compare_detail); ?>;
    var urlTabelImport = <?php echo json_encode($url_compare_tabel_import); ?>;
    var urlDetailExcel = <?php echo json_encode($url_compare_detail_excel); ?>;

    function juEscapeHtml(text) {
        return jQuery('<div>').text(text == null ? '' : String(text)).html();
    }
    function juBuildAjaxErrorMessage(xhr, res, defaultMsg) {
        var lines = [];
        var msg = defaultMsg || 'Terjadi kesalahan.';
        if (res && res.message) {
            msg = res.message;
        }
        if (res && res.db_error) {
            lines.push('Database: ' + res.db_error);
        }
        if (res && res.error_detail) {
            lines.push('Detail: ' + res.error_detail);
        }
        if (res && res.failed_row) {
            lines.push('Baris sumber gagal: #' + res.failed_row);
        }
        if (res && typeof res.inserted_before_fail !== 'undefined') {
            lines.push('Berhasil disimpan sebelum gagal: ' + res.inserted_before_fail + ' baris');
        }
        if (xhr && xhr.status) {
            lines.push('HTTP ' + xhr.status + (xhr.statusText ? ' (' + xhr.statusText + ')' : ''));
        }
        if (xhr && xhr.responseText && (!res || !res.message)) {
            var rt = String(xhr.responseText).trim();
            try {
                var j = JSON.parse(rt);
                if (j && j.message) {
                    msg = j.message;
                }
                if (j && j.db_error) {
                    lines.push('Database: ' + j.db_error);
                }
                if (j && j.error_detail) {
                    lines.push('Detail: ' + j.error_detail);
                }
            } catch (eJson) {
                if (rt.indexOf('Database Error') !== -1) {
                    msg = 'Error database saat menyimpan ke jurnal_umum.';
                    var dbMatch = rt.match(/<p>([^<]+)<\/p>/i);
                    if (dbMatch && dbMatch[1]) {
                        lines.push(dbMatch[1].trim());
                    }
                } else if (rt.indexOf('<') === -1 && rt.length > 0 && rt.length < 800) {
                    lines.push(rt);
                } else if (rt.length > 0) {
                    var stripped = rt.replace(/<script[\s\S]*?<\/script>/gi, ' ').replace(/<style[\s\S]*?<\/style>/gi, ' ').replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim();
                    if (stripped.length > 0 && stripped.length < 800) {
                        lines.push(stripped);
                    }
                }
            }
        }
        if (lines.length) {
            msg += '\n\n' + lines.join('\n');
        }
        return msg;
    }
    function juShowSaveError(title, xhr, res, defaultMsg) {
        var msg = juBuildAjaxErrorMessage(xhr, res, defaultMsg);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: title || 'Simpan Gagal',
                html: '<div style="text-align:left;white-space:pre-wrap;font-size:14px;line-height:1.5;">' + juEscapeHtml(msg) + '</div>',
                width: 640
            });
        } else {
            alert((title || 'Simpan Gagal') + '\n\n' + msg);
        }
    }

    function syncCompareFromBulanNs() {
        var val = jQuery('#bulan_ns').val() || '';
        if (!/^\d{4}-\d{2}$/.test(val)) return;
        var parts = val.split('-');
        jQuery('#compare_tahun_ju').val(parseInt(parts[0], 10));
        jQuery('#compare_bulan_ju').val(parseInt(parts[1], 10));
    }

    function submitCariJurnalUmum() {
        syncCompareFromBulanNs();
        var activeTab = document.querySelector('#jurnal-umum-tabs .nav-link.active');
        document.getElementById('active_tab_input').value = (activeTab && activeTab.id === 'tab-compare-ju') ? 'compare' : 'data';
        document.getElementById('form-cari-jurnal-umum').submit();
    }

    var bulanNs = document.getElementById('bulan_ns');
    if (bulanNs) {
        bulanNs.addEventListener('change', function() {
            if (this.value) submitCariJurnalUmum();
        });
    }

    window.addEventListener('load', function() {
        if (!window.jQuery || !jQuery.fn.DataTable) return;

        jQuery('#table-jurnal-umum-data').DataTable({
            scrollX: true, scrollY: '450px', scrollCollapse: true,
            paging: true, searching: true, ordering: true, pageLength: 50,
            order: [[1, 'asc']],
            language: { url: '//cdn.datatables.net/plug-ins/1.11.4/i18n/id.json' }
        });

        jQuery('#btn-jurnal-umum-excel').on('click', function() {
            var f = jQuery('<form method="post" target="_blank"></form>');
            f.attr('action', urlExcel);
            f.append(jQuery('<input type="hidden" name="bulan_ns">').val(jQuery('#bulan_ns').val() || ''));
            jQuery('body').append(f); f.submit(); f.remove();
        });

        jQuery('#jurnal-umum-tabs a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
            document.getElementById('active_tab_input').value = (e.target.id === 'tab-compare-ju') ? 'compare' : 'data';
        });

        var lastResult = null, dtMap = {}, tablesLoaded = false, csvBusy = false, csvLast = null;
        var tabelImportState = null, tabelImportBusy = false;

        function bulanKey() {
            var b = parseInt(jQuery('#compare_bulan_ju').val(), 10);
            var t = parseInt(jQuery('#compare_tahun_ju').val(), 10);
            return (b && t) ? (t + '-' + String(b).padStart(2, '0')) : '';
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
            if (!v || n === 0) return '<span class="text-muted">—</span>';
            return '<span class="text-amount text-amount-' + type + '">' + jQuery('<span>').text(String(v)).html() + '</span>';
        }
        function setStatus(type, html) {
            var $el = jQuery('#compare-ju-status');
            $el.removeClass('alert-info alert-success alert-danger alert-warning')
                .addClass(type === 'success' ? 'alert-success' : (type === 'danger' ? 'alert-danger' : (type === 'warning' ? 'alert-warning' : 'alert-info')))
                .html(html);
        }
        function hideTabelActions() {
            jQuery('#compare-ju-tabel-actions').addClass('d-none');
            jQuery('#compare-ju-tabel-info-body').empty();
            jQuery('#btn-compare-ju-tabel-import').prop('disabled', true);
            jQuery('#compare-ju-tabel-import-note').text('').removeClass('text-danger text-success text-muted text-warning');
        }
        function buildTabelInfoHtml(res) {
            var tbl = jQuery('#compare_tabel_ju').val() || (res && res.table) || '—';
            var bk = bulanKey() || (res && res.bulan) || '—';
            var stats = (res && res.stats) ? res.stats : {};
            var map = (res && res.map) ? res.map : {};
            var mapParts = [];
            ['tanggal', 'bukti', 'pl', 'ref', 'kode_rekening', 'rekening', 'debet', 'kredit'].forEach(function(key) {
                if (map[key]) mapParts.push(key + ' → <code>' + jQuery('<span>').text(map[key]).html() + '</code>');
            });
            var html = '<div class="compare-info-title"><i class="fas fa-info-circle"></i> Informasi Tabel — Siap Diproses ke Jurnal Umum</div>';
            html += '<div class="compare-info-line">Tabel terpilih: <strong>' + jQuery('<span>').text(tbl).html() + '</strong></div>';
            html += '<div class="compare-info-line">Bulan proses: <strong>' + jQuery('<span>').text(bk).html() + '</strong></div>';
            if (mapParts.length) html += '<div class="compare-info-line">Mapping kolom: ' + mapParts.join(' | ') + '</div>';
            if (stats.saveable_in_bulan != null) {
                html += '<div class="compare-info-line">Baris siap simpan: <strong>' + (stats.saveable_in_bulan || 0) + '</strong>';
                if (stats.in_bulan != null) html += ' | baris bulan terpilih: <strong>' + (stats.in_bulan || 0) + '</strong>';
                if (stats.out_bulan > 0) html += ' | di luar bulan: <strong class="text-warning">' + stats.out_bulan + '</strong>';
                html += '</div>';
            }
            html += '<div class="compare-info-line">Mode simpan: <strong>semua baris valid disimpan apa adanya</strong> (tanpa cek duplikat).</div>';
            if (res.jurnal_umum_bulan_conflict && res.conflict_warning) {
                html += '<div class="compare-info-line text-warning"><i class="fas fa-exclamation-triangle"></i> ' + jQuery('<span>').text(res.conflict_warning).html() + '</div>';
            }
            return html;
        }
        function applyTabelImportState(res) {
            tabelImportState = res || null;
            var tbl = jQuery('#compare_tabel_ju').val() || '';
            if (!tbl) { hideTabelActions(); return; }
            jQuery('#compare-ju-tabel-actions').removeClass('d-none');
            jQuery('#btn-compare-ju-tabel-detail').prop('disabled', false);
            if (!res || !res.eligible) {
                jQuery('#compare-ju-tabel-info-body').html(
                    '<div class="compare-info-title text-warning"><i class="fas fa-exclamation-triangle"></i> Tabel belum memenuhi syarat import</div>'
                    + '<div class="compare-info-line">Tabel: <strong>' + jQuery('<span>').text(tbl).html() + '</strong></div>'
                    + '<div class="compare-info-line">' + jQuery('<span>').text((res && res.message) ? res.message : 'Kolom wajib minimal: tanggal, debet atau kredit.').html() + '</div>'
                );
                jQuery('#btn-compare-ju-tabel-detail').prop('disabled', true);
                jQuery('#btn-compare-ju-tabel-import').prop('disabled', true);
                return;
            }
            jQuery('#compare-ju-tabel-info-body').html(buildTabelInfoHtml(res));
            var enabled = !!res.import_enabled;
            jQuery('#btn-compare-ju-tabel-import').prop('disabled', !enabled);
            var $note = jQuery('#compare-ju-tabel-import-note');
            $note.removeClass('text-danger text-success text-muted text-warning');
            if (enabled) {
                $note.addClass(res.jurnal_umum_bulan_conflict ? 'text-warning' : 'text-success');
                $note.html('<i class="fas fa-check-circle"></i> ' + (res.import_message || 'Siap disimpan ke jurnal_umum.'));
            } else {
                $note.addClass('text-danger').html('<i class="fas fa-exclamation-circle"></i> ' + (res.import_message || 'Tidak ada data yang bisa disimpan.'));
            }
        }
        function validateTabelForImport() {
            var tbl = jQuery('#compare_tabel_ju').val() || '';
            if (!tbl) { hideTabelActions(); toggleBtns(); return; }
            jQuery('#compare-ju-tabel-actions').removeClass('d-none');
            jQuery('#compare-ju-tabel-info-body').html('<div class="compare-info-title"><i class="fas fa-spinner fa-spin"></i> Memeriksa tabel terpilih...</div>');
            jQuery('#btn-compare-ju-tabel-detail, #btn-compare-ju-tabel-import').prop('disabled', true);
            var bk = bulanKey();
            jQuery.ajax({
                url: urlValidate, type: 'POST', dataType: 'json',
                data: { tabel: tbl, bulan: bk, bulan_num: jQuery('#compare_bulan_ju').val(), tahun: jQuery('#compare_tahun_ju').val() }
            }).done(applyTabelImportState).fail(function() {
                jQuery('#compare-ju-tabel-info-body').html('<div class="compare-info-title text-danger">Gagal memeriksa tabel</div>');
            }).always(toggleBtns);
        }
        function toggleBtns() {
            var show = bulanKey() !== '' && (jQuery('#compare_tabel_ju').val() || '') !== '';
            jQuery('#btn-compare-ju').toggleClass('d-none', !show);
            if (!show) jQuery('#btn-compare-ju-excel-all').addClass('d-none');
        }
        function buildRows(items) {
            return (items || []).map(function(it, i) {
                return [i + 1, it.tanggal || '', it.bukti || '', it.pl || '', it.ref || '', it.kode_rekening || '', it.rekening || '',
                    fmtAmtCell(it.debet, 'debet'), fmtAmtCell(it.kredit, 'kredit'),
                    it.catatan ? '<span class="text-catatan">' + jQuery('<span>').text(it.catatan).html() + '</span>' : ''];
            });
        }
        function buildDetailRows(items) {
            return (items || []).map(function(it) {
                return [it.no || '', it.tanggal || '', it.bukti || '', it.pl || '', it.ref || '', it.kode_rekening || '', it.rekening || '',
                    fmtAmtCell(it.debet, 'debet'), fmtAmtCell(it.kredit, 'kredit')];
            });
        }
        function renderTable(sel, items) {
            var $t = jQuery(sel); if (!$t.length) return;
            items = items || [];
            if (jQuery.fn.DataTable.isDataTable($t)) $t.DataTable().clear().destroy();
            $t.find('tbody').empty();
            $t.DataTable({
                data: buildRows(items), paging: true, searching: true, ordering: true, scrollX: true, scrollY: '280px', scrollCollapse: true,
                pageLength: 25, order: [[1, 'asc']], autoWidth: false,
                language: { url: '//cdn.datatables.net/plug-ins/1.11.4/i18n/id.json', emptyTable: 'Tidak ada data' },
                drawCallback: function() {
                    var td = 0, tk = 0;
                    items.forEach(function(it) { td += parseAmt(it.debet); tk += parseAmt(it.kredit); });
                    $t.find('.compare-total-debet').text(td > 0 ? td.toLocaleString('id-ID') : '—');
                    $t.find('.compare-total-kredit').text(tk > 0 ? tk.toLocaleString('id-ID') : '—');
                }
            });
        }
        function renderAll(res) {
            renderTable('#table-compare-ju-manual', res.data_manual);
            renderTable('#table-compare-ju-online', res.data_online);
            renderTable('#table-compare-ju-cocok', res.data_cocok);
            renderTable('#table-compare-ju-manual-miss', res.manual_tidak_di_online);
            renderTable('#table-compare-ju-online-miss', res.online_tidak_di_manual);
        }
        function updateInfo(res) {
            res = res || lastResult || {};
            var s = res.stats || {};
            jQuery('#compare-ju-label-bulan').text(res.bulan_label || bulanKey() || '—');
            jQuery('#compare-ju-label-tabel').text(res.table || jQuery('#compare_tabel_ju').val() || '—');
            jQuery('#compare-ju-count-manual').text(s.data_manual != null ? s.data_manual : '—');
            jQuery('#compare-ju-count-online').text(s.data_online != null ? s.data_online : '—');
            jQuery('#compare-ju-count-cocok').text(s.data_cocok != null ? s.data_cocok : '—');
            jQuery('#compare-ju-badge-manual').text(s.data_manual || 0);
            jQuery('#compare-ju-badge-online').text(s.data_online || 0);
            jQuery('#compare-ju-badge-cocok').text(s.data_cocok || 0);
            jQuery('#compare-ju-badge-manual-miss').text(s.manual_tidak_di_online || 0);
            jQuery('#compare-ju-badge-online-miss').text(s.online_tidak_di_manual || 0);
        }
        function loadTableList(force, selectTable) {
            if (tablesLoaded && !force) {
                if (selectTable) jQuery('#compare_tabel_ju').val(selectTable);
                validateTabelForImport();
                return;
            }
            jQuery.ajax({ url: urlList, type: 'POST', dataType: 'json' }).done(function(res) {
                if (!res || !res.ok) { setStatus('danger', (res && res.message) || 'Gagal memuat daftar tabel.'); return; }
                var $sel = jQuery('#compare_tabel_ju'), cur = selectTable || $sel.val();
                $sel.find('option:not(:first)').remove();
                (res.tables || []).forEach(function(tbl) { $sel.append(jQuery('<option>', { value: tbl, text: tbl })); });
                if (cur) $sel.val(cur);
                tablesLoaded = true;
                validateTabelForImport();
            }).fail(toggleBtns);
        }
        function runCompare() {
            var bk = bulanKey(), tbl = jQuery('#compare_tabel_ju').val() || '';
            if (!bk || !tbl) { alert('Pilih bulan, tahun, dan tabel database.'); return; }
            if (typeof Swal !== 'undefined') {
                Swal.fire({ title: 'Memproses Compare...', html: 'Membandingkan data manual vs online jurnal_umum', allowOutsideClick: false, didOpen: function() { Swal.showLoading(); } });
            }
            jQuery('#compare-ju-results-panel, #btn-compare-ju-excel-all').addClass('d-none');
            jQuery.ajax({
                url: urlRun, type: 'POST', dataType: 'json',
                data: { bulan: bk, bulan_num: jQuery('#compare_bulan_ju').val(), tahun: jQuery('#compare_tahun_ju').val(), tabel: tbl }
            }).done(function(res) {
                if (typeof Swal !== 'undefined') Swal.close();
                if (!res || !res.ok) {
                    setStatus('danger', (res && res.message) || 'Compare gagal.');
                    if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Compare Gagal', text: (res && res.message) || 'Compare gagal.' });
                    return;
                }
                lastResult = res; renderAll(res); updateInfo(res);
                jQuery('#compare-ju-results-panel, #btn-compare-ju-excel-all').removeClass('d-none');
                setStatus('success', '<i class="fas fa-check-circle"></i> Compare selesai.');
                if (typeof Swal !== 'undefined') Swal.fire({ icon: 'success', title: 'Compare Selesai', timer: 2500, showConfirmButton: false });
            }).fail(function() {
                if (typeof Swal !== 'undefined') Swal.close();
                setStatus('danger', 'Tidak dapat menghubungi server.');
            });
        }
        function exportCompareExcel() {
            var bk = bulanKey(), tbl = jQuery('#compare_tabel_ju').val() || '';
            if (!bk || !tbl) return;
            var f = jQuery('<form method="post" target="_blank"></form>');
            f.attr('action', urlCompareExcel);
            f.append(jQuery('<input type="hidden" name="bulan">').val(bk));
            f.append(jQuery('<input type="hidden" name="bulan_num">').val(jQuery('#compare_bulan_ju').val()));
            f.append(jQuery('<input type="hidden" name="tahun">').val(jQuery('#compare_tahun_ju').val()));
            f.append(jQuery('<input type="hidden" name="tabel">').val(tbl));
            jQuery('body').append(f); f.submit(); f.remove();
        }
        function openTabelDetailModal() {
            var tbl = jQuery('#compare_tabel_ju').val() || '';
            var bk = bulanKey();
            if (!tbl || !bk) { alert('Pilih tabel dan bulan terlebih dahulu.'); return; }
            jQuery('#modal-compare-ju-tabel-detail-title').text('Detail Tabel — ' + tbl);
            jQuery('#compare-ju-tabel-detail-meta').text('Memuat data tabel `' + tbl + '` bulan ' + bk + '...');
            jQuery('#modal-compare-ju-tabel-detail').modal('show');
            jQuery.ajax({ url: urlDetail, type: 'POST', dataType: 'json', data: { tabel: tbl, bulan: bk } })
            .done(function(res) {
                if (!res || !res.ok) {
                    jQuery('#compare-ju-tabel-detail-meta').text((res && res.message) || 'Gagal memuat detail tabel.');
                    return;
                }
                var items = res.rows || [];
                jQuery('#compare-ju-tabel-detail-meta').text('Tabel: ' + (res.table || tbl) + ' | Bulan: ' + (res.bulan_label || bk) + ' | Total: ' + (res.total || items.length) + ' baris');
                var $t = jQuery('#table-compare-ju-tabel-detail');
                if (jQuery.fn.DataTable.isDataTable($t)) $t.DataTable().clear().destroy();
                $t.find('tbody').empty();
                $t.DataTable({
                    data: buildDetailRows(items), paging: true, searching: true, ordering: true, scrollX: true, pageLength: 25,
                    order: [[1, 'asc']], autoWidth: false,
                    language: { url: '//cdn.datatables.net/plug-ins/1.11.4/i18n/id.json', emptyTable: 'Tidak ada data pada bulan terpilih' },
                    drawCallback: function() {
                        var td = 0, tk = 0;
                        items.forEach(function(it) { td += parseAmt(it.debet); tk += parseAmt(it.kredit); });
                        $t.find('.compare-total-debet').text(td > 0 ? td.toLocaleString('id-ID') : '—');
                        $t.find('.compare-total-kredit').text(tk > 0 ? tk.toLocaleString('id-ID') : '—');
                    }
                });
            }).fail(function() {
                jQuery('#compare-ju-tabel-detail-meta').text('Gagal memuat detail tabel.');
            });
        }
        function exportTabelDetailExcel() {
            var tbl = jQuery('#compare_tabel_ju').val() || '';
            var bk = bulanKey();
            if (!tbl || !bk) return;
            var f = jQuery('<form method="post" target="_blank"></form>');
            f.attr('action', urlDetailExcel);
            f.append(jQuery('<input type="hidden" name="tabel">').val(tbl));
            f.append(jQuery('<input type="hidden" name="bulan">').val(bk));
            jQuery('body').append(f); f.submit(); f.remove();
        }
        function reloadJurnalUmumAfterImport() {
            var bk = bulanKey();
            if (bk && /^\d{4}-\d{2}$/.test(bk)) {
                var parts = bk.split('-');
                window.location.href = urlCari + '/' + parts[0] + '/' + parts[1];
                return;
            }
            window.location.reload();
        }
        function showImportSuccessAlert(msg) {
            if (typeof Swal === 'undefined') {
                alert(msg || 'Data berhasil disimpan.');
                reloadJurnalUmumAfterImport();
                return;
            }
            Swal.fire({
                icon: 'success',
                title: 'Proses Sukses!',
                html: '<div style="font-size:15px;line-height:1.6;">' + jQuery('<div>').text(msg || 'Data berhasil disimpan ke jurnal_umum.').html()
                    + '<br><span style="font-size:13px;color:#666;">Datatable Tab 1 akan dimuat ulang otomatis...</span></div>',
                confirmButtonText: 'OK',
                confirmButtonColor: '#28a745',
                timer: 2000,
                timerProgressBar: true,
                allowOutsideClick: false
            }).then(function() { reloadJurnalUmumAfterImport(); });
        }
        function runTabelImportRequest(tbl, bk) {
            tabelImportBusy = true;
            jQuery('#btn-compare-ju-tabel-import').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
            if (typeof Swal !== 'undefined') {
                Swal.fire({ title: 'Memproses Transfer Data...', html: 'Menyimpan data ke tabel <strong>jurnal_umum</strong>', allowOutsideClick: false, didOpen: function() { Swal.showLoading(); } });
            }
            jQuery.ajax({ url: urlTabelImport, type: 'POST', dataType: 'json', data: { tabel: tbl, bulan: bk } })
            .done(function(res) {
                tabelImportBusy = false;
                if (typeof Swal !== 'undefined') Swal.close();
                jQuery('#btn-compare-ju-tabel-import').html('<i class="fas fa-database"></i> Proses Simpan Data ke Tabel Jurnal_umum');
                if (!res || !res.ok) {
                    juShowSaveError('Simpan Gagal', null, res, 'Gagal menyimpan data ke jurnal_umum.');
                    validateTabelForImport();
                    return;
                }
                setStatus('success', res.message || 'Berhasil menyimpan data.');
                validateTabelForImport();
                showImportSuccessAlert(res.message);
            }).fail(function(xhr) {
                tabelImportBusy = false;
                if (typeof Swal !== 'undefined') Swal.close();
                jQuery('#btn-compare-ju-tabel-import').html('<i class="fas fa-database"></i> Proses Simpan Data ke Tabel Jurnal_umum');
                validateTabelForImport();
                juShowSaveError('Simpan Gagal', xhr, null, 'Tidak dapat menghubungi server atau respons server tidak valid.');
            });
        }
        function importTabelToJurnalUmum() {
            if (tabelImportBusy) return;
            var tbl = jQuery('#compare_tabel_ju').val() || '';
            var bk = bulanKey();
            if (!tbl || !bk) { alert('Pilih tabel dan bulan terlebih dahulu.'); return; }
            if (!tabelImportState || !tabelImportState.import_enabled) {
                alert((tabelImportState && tabelImportState.import_message) || 'Data tidak bisa disimpan.');
                return;
            }
            var doImport = function() { runTabelImportRequest(tbl, bk); };
            if (tabelImportState.jurnal_umum_bulan_conflict && tabelImportState.conflict_warning) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning', title: 'Data Bulan Sudah Ada di Jurnal Umum',
                        html: jQuery('<div>').text(tabelImportState.conflict_warning).html() + '<br><br><strong>Lanjutkan simpan?</strong>',
                        showCancelButton: true, confirmButtonText: 'Ya, lanjutkan', cancelButtonText: 'Batal'
                    }).then(function(r) { if (r.isConfirmed) doImport(); });
                    return;
                }
            }
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'question', title: 'Konfirmasi Simpan',
                    text: 'Proses simpan semua data valid tabel `' + tbl + '` ke jurnal_umum bulan ' + bk + '?',
                    showCancelButton: true, confirmButtonText: 'Ya, simpan', cancelButtonText: 'Batal'
                }).then(function(r) { if (r.isConfirmed) doImport(); });
                return;
            }
            if (window.confirm('Proses simpan data ke jurnal_umum?')) doImport();
        }
        function importCsv(file) {
            if (!file || csvBusy) return;
            if ((file.name || '').split('.').pop().toLowerCase() !== 'csv') {
                if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Format Salah', text: 'File harus .csv' });
                return;
            }
            csvBusy = true;
            if (typeof Swal !== 'undefined') {
                Swal.fire({ title: 'Memproses CSV...', html: 'Upload & generate tabel database...', allowOutsideClick: false, didOpen: function() { Swal.showLoading(); } });
            }
            var ref = { bulan: parseInt(jQuery('#compare_bulan_ju').val(), 10), tahun: parseInt(jQuery('#compare_tahun_ju').val(), 10) };
            var fd = new FormData();
            fd.append('csv_file', file);
            fd.append('bulan_num', ref.bulan); fd.append('tahun', ref.tahun);
            fd.append('bulan', ref.tahun + '-' + String(ref.bulan).padStart(2, '0'));
            jQuery.ajax({ url: urlImport, type: 'POST', data: fd, processData: false, contentType: false, dataType: 'json' })
            .done(function(res) {
                csvBusy = false;
                jQuery('#compare_ju_csv_file').val('').next('.custom-file-label').text('Cari / pilih file CSV...');
                if (typeof Swal !== 'undefined') Swal.close();
                if (!res || !res.ok) {
                    if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Import Gagal', html: (res && res.message) ? String(res.message).replace(/\n/g, '<br/>') : 'Import gagal.' });
                    return;
                }
                csvLast = res;
                jQuery('#compare-ju-csv-filename').text(res.file || '—');
                jQuery('#compare-ju-csv-tablename').text(res.table || '—');
                jQuery('#compare-ju-csv-rowcount').text(res.rows ? (' (' + res.rows + ' baris)') : '');
                jQuery('#compare-ju-csv-upload-info').removeClass('d-none');
                loadTableList(true, res.table);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'success', title: 'Import CSV Berhasil', html: 'Tabel <strong>' + (res.table || '') + '</strong> — ' + (res.rows || 0) + ' baris.' });
                }
            }).fail(function() {
                csvBusy = false;
                if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Import Gagal', text: 'Tidak dapat menghubungi server.' });
            });
        }

        jQuery('#compare_ju_csv_file').on('change', function() {
            var f = this.files && this.files[0];
            if (f) { jQuery(this).next('.custom-file-label').text(f.name); importCsv(f); }
        });
        jQuery('#compare_bulan_ju, #compare_tahun_ju').on('change', function() {
            validateTabelForImport();
            toggleBtns();
        });
        jQuery('#compare_tabel_ju').on('change', validateTabelForImport);
        jQuery('#btn-compare-ju').on('click', runCompare);
        jQuery('#btn-compare-ju-excel-all').on('click', exportCompareExcel);
        jQuery('#btn-compare-ju-tabel-detail').on('click', openTabelDetailModal);
        jQuery('#btn-compare-ju-tabel-detail-excel').on('click', exportTabelDetailExcel);
        jQuery('#btn-compare-ju-tabel-import').on('click', importTabelToJurnalUmum);
        jQuery('#tab-compare-ju').on('shown.bs.tab', function() { loadTableList(false); });
        if (jQuery('#tab-compare-ju').hasClass('active')) loadTableList(false);
        syncCompareFromBulanNs();
        toggleBtns();
    });
})();
</script>
