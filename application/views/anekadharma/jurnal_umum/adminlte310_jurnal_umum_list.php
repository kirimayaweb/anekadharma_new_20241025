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
if (!isset($Get_date_awal)) {
    $Get_date_awal = date('d-m-Y');
}
if (!isset($Get_date_akhir)) {
    $Get_date_akhir = date('d-m-Y');
}
if (!isset($periode_label)) {
    $periode_label = $Get_date_awal . ' s/d ' . $Get_date_akhir;
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
$url_compare_preview = isset($url_compare_jurnal_umum_tabel_preview) ? $url_compare_jurnal_umum_tabel_preview : site_url('Jurnal_umum/ajax_compare_tabel_preview_jurnal_umum');
$url_compare_tabel_excel = isset($url_compare_jurnal_umum_tabel_excel) ? $url_compare_jurnal_umum_tabel_excel : site_url('Jurnal_umum/excel_compare_tabel_preview_jurnal_umum');

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
                            <div class="col-lg-3">
                                <strong>JURNAL UMUM</strong>
                            </div>
                            <div class="col-lg-9">
                                <form id="form-cari-jurnal-umum" action="<?php echo htmlspecialchars($url_cari, ENT_QUOTES, 'UTF-8'); ?>" method="post">
                                    <input type="hidden" name="active_tab" id="active_tab_input" value="<?php echo $tab_compare_active ? 'compare' : 'data'; ?>">
                                    <div class="row justify-content-end align-items-center">
                                        <div class="col-md-3">
                                            <div class="input-group date" id="tgl_awal" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#tgl_awal" name="tgl_awal" value="<?php echo htmlspecialchars($Get_date_awal, ENT_QUOTES, 'UTF-8'); ?>" required />
                                                <div class="input-group-append" data-target="#tgl_awal" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-1 text-center">s/d</div>
                                        <div class="col-md-3">
                                            <div class="input-group date" id="tgl_akhir" data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input" data-target="#tgl_akhir" name="tgl_akhir" value="<?php echo htmlspecialchars($Get_date_akhir, ENT_QUOTES, 'UTF-8'); ?>" required />
                                                <div class="input-group-append" data-target="#tgl_akhir" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
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
                                        <small class="text-muted">Periode: <span id="ju-label-periode"><?php echo htmlspecialchars($periode_label, ENT_QUOTES, 'UTF-8'); ?></span></small>
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
                                    <button type="button" id="btn-compare-ju-csv-detail" class="btn btn-outline-info btn-sm"><i class="fas fa-table"></i> Detail Tabel</button>
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

                                <div class="modal fade" id="modal-compare-ju-csv-preview" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document" style="max-width:95%;">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white py-2">
                                                <h5 class="modal-title">Detail Tabel CSV</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <div class="modal-body pb-2">
                                                <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
                                                    <p class="text-muted small mb-0" id="compare-ju-csv-preview-meta">Memuat...</p>
                                                    <button type="button" class="btn btn-success btn-sm" id="btn-compare-ju-modal-excel"><i class="fa fa-file-excel-o"></i> Cetak ke Excel</button>
                                                </div>
                                                <table id="table-compare-ju-csv-preview" class="table table-bordered table-striped table-sm" style="width:100%;font-size:12px;">
                                                    <thead><tr></tr></thead><tbody></tbody>
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
</style>

<script>
(function() {
    var urlExcel = <?php echo json_encode($url_excel); ?>;
    var urlRun = <?php echo json_encode($url_compare_run); ?>;
    var urlCompareExcel = <?php echo json_encode($url_compare_excel); ?>;
    var urlImport = <?php echo json_encode($url_compare_import); ?>;
    var urlList = <?php echo json_encode($url_compare_list); ?>;
    var urlPreview = <?php echo json_encode($url_compare_preview); ?>;
    var urlTabelExcel = <?php echo json_encode($url_compare_tabel_excel); ?>;

    function parseDateDMY(str) {
        if (!str) return null;
        var p = String(str).split(/[-\/]/);
        if (p.length !== 3) return null;
        var d = parseInt(p[0], 10), m = parseInt(p[1], 10), y = parseInt(p[2], 10);
        if (!d || !m || !y) return null;
        return new Date(y, m - 1, d);
    }

    function syncCompareMonthYearFromDates() {
        var form = document.getElementById('form-cari-jurnal-umum');
        if (!form || !window.jQuery) return;
        var awal = parseDateDMY(form.querySelector('[name="tgl_awal"]').value);
        if (!awal) return;
        jQuery('#compare_bulan_ju').val(awal.getMonth() + 1);
        jQuery('#compare_tahun_ju').val(awal.getFullYear());
    }

    function submitCariJurnalUmum() {
        syncCompareMonthYearFromDates();
        var activeTab = document.querySelector('#jurnal-umum-tabs .nav-link.active');
        document.getElementById('active_tab_input').value = (activeTab && activeTab.id === 'tab-compare-ju') ? 'compare' : 'data';
        document.getElementById('form-cari-jurnal-umum').submit();
    }

    window.addEventListener('load', function() {
        if (!window.jQuery || !jQuery.fn.DataTable) return;

        jQuery('#table-jurnal-umum-data').DataTable({
            scrollX: true,
            scrollY: '450px',
            scrollCollapse: true,
            paging: true,
            searching: true,
            ordering: true,
            pageLength: 50,
            order: [[1, 'asc']],
            language: { url: '//cdn.datatables.net/plug-ins/1.11.4/i18n/id.json' }
        });

        if (jQuery.fn.datetimepicker) {
            jQuery('#tgl_awal, #tgl_akhir').datetimepicker({ format: 'DD-MM-YYYY', locale: 'id' });
            jQuery('#tgl_awal, #tgl_akhir').on('change.datetimepicker hide.datetimepicker', submitCariJurnalUmum);
        }
        jQuery('#form-cari-jurnal-umum input[name="tgl_awal"], #form-cari-jurnal-umum input[name="tgl_akhir"]').on('change blur', function() {
            setTimeout(submitCariJurnalUmum, 150);
        });

        jQuery('#btn-jurnal-umum-excel').on('click', function() {
            var form = document.getElementById('form-cari-jurnal-umum');
            var f = jQuery('<form method="post" target="_blank"></form>');
            f.attr('action', urlExcel);
            f.append(jQuery('<input type="hidden" name="tgl_awal">').val(form.querySelector('[name="tgl_awal"]').value));
            f.append(jQuery('<input type="hidden" name="tgl_akhir">').val(form.querySelector('[name="tgl_akhir"]').value));
            jQuery('body').append(f); f.submit(); f.remove();
        });

        jQuery('#jurnal-umum-tabs a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
            document.getElementById('active_tab_input').value = (e.target.id === 'tab-compare-ju') ? 'compare' : 'data';
        });

        var lastResult = null, dtMap = {}, tablesLoaded = false, csvBusy = false, csvLast = null, previewTableName = '';

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
            $el.removeClass('alert-info alert-success alert-danger alert-warning').addClass(type === 'success' ? 'alert-success' : (type === 'danger' ? 'alert-danger' : (type === 'warning' ? 'alert-warning' : 'alert-info'))).html(html);
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
        function renderTable(sel, items) {
            var $t = jQuery(sel); if (!$t.length) return;
            items = items || [];
            if (jQuery.fn.DataTable.isDataTable($t)) $t.DataTable().clear().destroy();
            $t.find('tbody').empty();
            var dt = $t.DataTable({
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
            dtMap[sel] = dt;
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
                toggleBtns(); return;
            }
            jQuery.ajax({ url: urlList, type: 'POST', dataType: 'json' }).done(function(res) {
                if (!res || !res.ok) { setStatus('danger', (res && res.message) || 'Gagal memuat daftar tabel.'); return; }
                var $sel = jQuery('#compare_tabel_ju'), cur = selectTable || $sel.val();
                $sel.find('option:not(:first)').remove();
                (res.tables || []).forEach(function(tbl) { $sel.append(jQuery('<option>', { value: tbl, text: tbl })); });
                if (cur) $sel.val(cur);
                tablesLoaded = true;
            }).always(toggleBtns);
        }
        function runCompare() {
            var bk = bulanKey(), tbl = jQuery('#compare_tabel_ju').val() || '';
            if (!bk || !tbl) { alert('Pilih bulan, tahun, dan tabel database.'); return; }
            if (typeof Swal !== 'undefined') {
                Swal.fire({ title: 'Memproses Compare...', html: 'Membandingkan data manual vs online jurnal_umum', allowOutsideClick: false, didOpen: function() { Swal.showLoading(); } });
            }
            setStatus('info', '<i class="fas fa-spinner fa-spin"></i> Membandingkan...');
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
                setStatus('success', '<i class="fas fa-check-circle"></i> Compare selesai. Manual: ' + (res.stats.data_manual || 0) + ', Online: ' + (res.stats.data_online || 0) + ', Cocok: ' + (res.stats.data_cocok || 0) + '.');
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
                previewTableName = res.table || '';
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
        function openPreviewModal(tbl) {
            previewTableName = tbl;
            jQuery('#compare-ju-csv-preview-meta').text('Tabel: ' + tbl);
            jQuery('#modal-compare-ju-csv-preview').modal('show');
            jQuery.ajax({ url: urlPreview, type: 'POST', dataType: 'json', data: { tabel: tbl, limit: 500 } })
            .done(function(res) {
                if (!res || !res.ok) { jQuery('#compare-ju-csv-preview-meta').text((res && res.message) || 'Gagal preview.'); return; }
                var cols = res.columns || [];
                var $thead = jQuery('#table-compare-ju-csv-preview thead tr').empty();
                cols.forEach(function(c) { $thead.append(jQuery('<th>').text(c)); });
                var rows = (res.rows || []).map(function(r) { return cols.map(function(c) { return r[c] != null ? r[c] : ''; }); });
                var $t = jQuery('#table-compare-ju-csv-preview');
                if (jQuery.fn.DataTable.isDataTable($t)) $t.DataTable().destroy();
                $t.DataTable({ data: rows, scrollX: true, pageLength: 25, language: { url: '//cdn.datatables.net/plug-ins/1.11.4/i18n/id.json' } });
            });
        }

        jQuery('#compare_ju_csv_file').on('change', function() {
            var f = this.files && this.files[0];
            if (f) { jQuery(this).next('.custom-file-label').text(f.name); importCsv(f); }
        });
        jQuery('#compare_bulan_ju, #compare_tahun_ju, #compare_tabel_ju').on('change', toggleBtns);
        jQuery('#btn-compare-ju').on('click', runCompare);
        jQuery('#btn-compare-ju-excel-all').on('click', exportCompareExcel);
        jQuery('#tab-compare-ju').on('shown.bs.tab', function() { loadTableList(false); });
        jQuery('#btn-compare-ju-csv-detail').on('click', function() {
            var tbl = (csvLast && csvLast.table) || jQuery('#compare_tabel_ju').val();
            if (!tbl) { alert('Belum ada tabel.'); return; }
            openPreviewModal(tbl);
        });
        jQuery('#btn-compare-ju-modal-excel').on('click', function() {
            if (!previewTableName) return;
            var f = jQuery('<form method="post" target="_blank"></form>');
            f.attr('action', urlTabelExcel);
            f.append(jQuery('<input type="hidden" name="tabel">').val(previewTableName));
            jQuery('body').append(f); f.submit(); f.remove();
        });
        if (jQuery('#tab-compare-ju').hasClass('active')) loadTableList(false);
        toggleBtns();
        syncCompareMonthYearFromDates();
    });
})();
</script>
