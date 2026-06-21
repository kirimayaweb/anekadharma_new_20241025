<?php
$this->load->helper('jurnal_penyesuaian_list');

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
    $bulan_label = jurnal_penyesuaian_bulan_teks($compare_bulan_num) . ' ' . $compare_tahun_num;
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
if (!isset($Data_kas)) {
    $Data_kas = array();
}
if (!isset($Total_debet)) {
    $Total_debet = 0;
}
if (!isset($Total_kredit)) {
    $Total_kredit = 0;
}

$url_cari = isset($url_cari_between_date) ? $url_cari_between_date : site_url('Jurnal_penyesuaian/cari_between_date');
$url_excel = isset($url_jurnal_penyesuaian_excel) ? $url_jurnal_penyesuaian_excel : site_url('Jurnal_penyesuaian/excel_list');
$url_compare_run = isset($url_compare_jurnal_penyesuaian_run) ? $url_compare_jurnal_penyesuaian_run : site_url('Jurnal_penyesuaian/ajax_compare_jurnal_penyesuaian_manual_online');
$url_compare_excel = isset($url_compare_jurnal_penyesuaian_excel) ? $url_compare_jurnal_penyesuaian_excel : site_url('Jurnal_penyesuaian/excel_compare_jurnal_penyesuaian_manual_online');
$url_compare_import = isset($url_compare_jurnal_penyesuaian_import_csv) ? $url_compare_jurnal_penyesuaian_import_csv : site_url('Jurnal_penyesuaian/ajax_compare_import_csv_jurnal_penyesuaian');
$url_compare_list = isset($url_compare_jurnal_penyesuaian_tabel_list) ? $url_compare_jurnal_penyesuaian_tabel_list : site_url('Jurnal_penyesuaian/ajax_compare_tabel_list_jurnal_penyesuaian');
$url_compare_preview = isset($url_compare_jurnal_penyesuaian_tabel_preview) ? $url_compare_jurnal_penyesuaian_tabel_preview : site_url('Jurnal_penyesuaian/ajax_compare_tabel_preview_jurnal_penyesuaian');
$url_compare_tabel_excel = isset($url_compare_jurnal_penyesuaian_tabel_excel) ? $url_compare_jurnal_penyesuaian_tabel_excel : site_url('Jurnal_penyesuaian/excel_compare_tabel_preview_jurnal_penyesuaian');
$url_compare_validate = isset($url_compare_jurnal_penyesuaian_tabel_validate) ? $url_compare_jurnal_penyesuaian_tabel_validate : site_url('Jurnal_penyesuaian/ajax_compare_tabel_validate_jurnal_penyesuaian');
$url_compare_detail = isset($url_compare_jurnal_penyesuaian_tabel_detail) ? $url_compare_jurnal_penyesuaian_tabel_detail : site_url('Jurnal_penyesuaian/ajax_compare_tabel_detail_jurnal_penyesuaian');
$url_compare_tabel_import = isset($url_compare_jurnal_penyesuaian_tabel_import) ? $url_compare_jurnal_penyesuaian_tabel_import : site_url('Jurnal_penyesuaian/ajax_compare_import_table_to_jurnal_penyesuaian');
$url_compare_detail_excel = isset($url_compare_jurnal_penyesuaian_tabel_detail_excel) ? $url_compare_jurnal_penyesuaian_tabel_detail_excel : site_url('Jurnal_penyesuaian/excel_compare_tabel_detail_jurnal_penyesuaian');
$url_ajax_list = isset($url_ajax_list_jurnal_penyesuaian) ? $url_ajax_list_jurnal_penyesuaian : site_url('Jurnal_penyesuaian/ajax_list_jurnal_penyesuaian');
$url_ajax_simpan = isset($url_ajax_simpan_input) ? $url_ajax_simpan_input : site_url('Jurnal_penyesuaian/ajax_simpan_input_data');

$compare_sections = array(
    array('jenis' => 'data_manual', 'num' => '1', 'label' => 'Data Manual', 'subtitle' => 'Tabel CSV / database terpilih', 'badge' => 'compare-jp-badge-manual', 'table' => 'table-compare-jp-manual', 'theme' => 'primary', 'icon' => 'fa-database', 'col' => 'col-lg-6'),
    array('jenis' => 'data_online', 'num' => '2', 'label' => 'Data Online', 'subtitle' => 'Data jurnal_penyesuaian bulan terpilih', 'badge' => 'compare-jp-badge-online', 'table' => 'table-compare-jp-online', 'theme' => 'info', 'icon' => 'fa-cloud', 'col' => 'col-lg-6'),
    array('jenis' => 'data_cocok', 'num' => '3', 'label' => 'Data Cocok (Manual & Online)', 'subtitle' => 'Tanggal, kode_akun, keterangan, kode_rekening, debet, kredit sama', 'badge' => 'compare-jp-badge-cocok', 'table' => 'table-compare-jp-cocok', 'theme' => 'success', 'icon' => 'fa-check-circle', 'col' => 'col-lg-6'),
    array('jenis' => 'manual_tidak_di_online', 'num' => '4', 'label' => 'Manual Tidak Ada di Online', 'subtitle' => 'Ada di manual, tidak cocok / tidak ada di jurnal_penyesuaian', 'badge' => 'compare-jp-badge-manual-miss', 'table' => 'table-compare-jp-manual-miss', 'theme' => 'warning', 'icon' => 'fa-exclamation-triangle', 'col' => 'col-lg-6'),
    array('jenis' => 'online_tidak_di_manual', 'num' => '5', 'label' => 'Online Tidak Ada di Manual', 'subtitle' => 'Ada di jurnal_penyesuaian, tidak cocok di manual', 'badge' => 'compare-jp-badge-online-miss', 'table' => 'table-compare-jp-online-miss', 'theme' => 'cyan', 'icon' => 'fa-exchange-alt', 'col' => 'col-lg-12'),
);
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1 class="m-0"></h1></div>
                <div class="col-sm-6"><ol class="breadcrumb float-sm-right"></ol></div>
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
                                <strong>JURNAL PENYESUAIAN</strong>
                                <span class="text-muted small d-block"><?php echo htmlspecialchars($bulan_label, ENT_QUOTES, 'UTF-8'); ?></span>
                            </div>
                            <div class="col-lg-6">
                                <form id="form-cari-jurnal-penyesuaian" action="<?php echo htmlspecialchars($url_cari, ENT_QUOTES, 'UTF-8'); ?>" method="post">
                                    <input type="hidden" name="active_tab" id="active_tab_input" value="<?php echo $tab_compare_active ? 'compare' : 'data'; ?>">
                                    <div class="row justify-content-center align-items-center">
                                        <div class="col-md-6">
                                            <label for="bulan_ns" class="sr-only">Pilih Bulan</label>
                                            <input type="month" class="form-control text-center jp-month-picker" id="bulan_ns" name="bulan_ns" value="<?php echo htmlspecialchars($bulan_ns_value, ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-3"></div>
                        </div>
                    </div>

                    <div class="card-body">
                        <ul class="nav nav-tabs jurnal-penyesuaian-tabs" id="jurnal-penyesuaian-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link<?php echo $tab_data_active ? ' active' : ''; ?>" id="tab-jp-data" data-toggle="pill" href="#panel-jp-data" role="tab">Data Jurnal Penyesuaian</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link<?php echo $tab_compare_active ? ' active' : ''; ?>" id="tab-compare-jp" data-toggle="pill" href="#panel-compare-jp" role="tab">Compare Data Manual - Online</a>
                            </li>
                        </ul>

                        <div class="tab-content mt-3" id="jurnal-penyesuaian-tabs-content">
                            <div class="tab-pane fade<?php echo $tab_data_active ? ' show active' : ''; ?>" id="panel-jp-data" role="tabpanel">
                                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 jurnal-penyesuaian-tab1-toolbar">
                                    <div>
                                        <h5 class="mb-0 text-primary"><strong>Data Jurnal Penyesuaian</strong></h5>
                                        <small class="text-muted">Pilih bulan di atas — data otomatis dimuat dari tanggal 1 s/d akhir bulan terpilih (<span id="jp-label-periode"><?php echo htmlspecialchars($periode_label, ENT_QUOTES, 'UTF-8'); ?></span>)</small>
                                    </div>
                                    <div class="d-flex flex-wrap mt-2 mt-md-0">
                                        <button type="button" class="btn btn-danger mr-2 mb-1" id="btn-input-jurnal-penyesuaian" data-toggle="modal" data-target="#modal_input_jurnal_penyesuaian">
                                            <i class="fa fa-plus"></i> Input Data
                                        </button>
                                        <button type="button" class="btn btn-success mb-1" id="btn-jurnal-penyesuaian-excel">
                                            <i class="fa fa-file-excel-o"></i> Cetak ke Excel
                                        </button>
                                    </div>
                                </div>

                                <div class="jp-dt-wrap">
                                    <table id="table-jurnal-penyesuaian-data" class="table table-bordered table-striped table-sm display nowrap jp-main-dt" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Bukti</th>
                                                <th>PL</th>
                                                <th>Keterangan</th>
                                                <th>Kode Rekening</th>
                                                <th>Kode Akun</th>
                                                <th class="text-right">Debet</th>
                                                <th class="text-right">Kredit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($Data_kas as $list_data) { ?>
                                            <tr>
                                                <td><?php echo (int) $list_data['no']; ?></td>
                                                <td><?php echo htmlspecialchars($list_data['tanggal'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($list_data['bukti'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($list_data['pl'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($list_data['keterangan'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($list_data['kode_rekening'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($list_data['kode_akun'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="text-right"><?php echo htmlspecialchars($list_data['debet_display'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="text-right"><?php echo htmlspecialchars($list_data['kredit_display'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr class="jp-total-row">
                                                <th colspan="7" class="text-right">JUMLAH DEBET / KREDIT</th>
                                                <th class="text-right" id="jp-total-debet"><?php echo jurnal_penyesuaian_format_rupiah($Total_debet, true); ?></th>
                                                <th class="text-right" id="jp-total-kredit"><?php echo jurnal_penyesuaian_format_rupiah($Total_kredit, true); ?></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade<?php echo $tab_compare_active ? ' show active' : ''; ?>" id="panel-compare-jp" role="tabpanel">
                                <small class="text-muted d-block mb-2">
                                    Upload CSV dengan kolom wajib: <strong>tanggal, keterangan, akun, debet/kredit</strong>
                                    (isi keterangan dan akun boleh kosong; debet atau kredit salah satu harus terisi per baris).
                                </small>

                                <label for="compare_jp_csv_file" class="mb-1">Pilih file CSV</label>
                                <div class="d-flex flex-wrap align-items-end compare-csv-upload-row mb-3">
                                    <div class="custom-file custom-file-sm mb-0 compare-csv-file-wrap">
                                        <input type="file" class="custom-file-input" id="compare_jp_csv_file" accept=".csv,text/csv">
                                        <label class="custom-file-label" for="compare_jp_csv_file" data-browse="Pilih">Cari / pilih file CSV...</label>
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-end compare-toolbar-row flex-wrap">
                                    <div class="col-auto mb-2">
                                        <label for="compare_bulan_jp" class="small mb-1">Bulan</label>
                                        <select id="compare_bulan_jp" class="form-control form-control-sm compare-toolbar-control">
                                            <?php foreach ($nama_bulan_id as $num => $label_bulan) { ?>
                                                <option value="<?php echo (int) $num; ?>"<?php echo ((int) $num === (int) $compare_bulan_num) ? ' selected' : ''; ?>><?php echo htmlspecialchars($label_bulan, ENT_QUOTES, 'UTF-8'); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-auto mb-2">
                                        <label for="compare_tahun_jp" class="small mb-1">Tahun</label>
                                        <select id="compare_tahun_jp" class="form-control form-control-sm compare-toolbar-control">
                                            <?php for ($th = $gen_tahun_max; $th >= $gen_tahun_min; $th--) { ?>
                                                <option value="<?php echo (int) $th; ?>"<?php echo ((int) $th === (int) $compare_tahun_num) ? ' selected' : ''; ?>><?php echo (int) $th; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-auto mb-2">
                                        <label for="compare_tabel_jp" class="small mb-1">Pilih tabel</label>
                                        <select id="compare_tabel_jp" class="form-control form-control-sm compare-toolbar-control compare-toolbar-tabel">
                                            <option value="">— Muat daftar tabel —</option>
                                        </select>
                                    </div>
                                    <div class="col-auto mb-2">
                                        <label class="small mb-1 d-block">&nbsp;</label>
                                        <div class="d-flex flex-wrap align-items-center">
                                            <button type="button" id="btn-compare-jp" class="btn btn-info btn-sm d-none"><i class="fas fa-columns"></i> Compare</button>
                                            <button type="button" id="btn-compare-jp-excel-all" class="btn btn-success btn-sm d-none ml-2"><i class="fa fa-file-excel-o"></i> Cetak ke Excel</button>
                                        </div>
                                    </div>
                                </div>

                                <div id="compare-jp-tabel-actions" class="compare-jp-tabel-info-box py-3 mb-3 d-none">
                                    <div id="compare-jp-tabel-info-body" class="mb-2"></div>
                                    <div id="compare-jp-tabel-import-note" class="small mb-2"></div>
                                    <div class="d-flex flex-wrap align-items-center">
                                        <button type="button" id="btn-compare-jp-tabel-detail" class="btn btn-outline-primary btn-sm mr-2 mb-1">
                                            <i class="fas fa-table"></i> Detail Tabel
                                        </button>
                                        <button type="button" id="btn-compare-jp-tabel-import" class="btn btn-success btn-sm mb-1" disabled>
                                            <i class="fas fa-database"></i> Proses Simpan Data ke Tabel Jurnal Penyesuaian
                                        </button>
                                    </div>
                                </div>

                                <div class="alert alert-secondary py-2" id="compare-jp-info-ringkas">
                                    <strong>Bulan:</strong> <span id="compare-jp-label-bulan">—</span>
                                    &nbsp;|&nbsp; <strong>Tabel manual:</strong> <span id="compare-jp-label-tabel">—</span>
                                    &nbsp;|&nbsp; <strong>Manual:</strong> <span id="compare-jp-count-manual">—</span>
                                    &nbsp;|&nbsp; <strong>Online:</strong> <span id="compare-jp-count-online">—</span>
                                    &nbsp;|&nbsp; <strong>Cocok:</strong> <span id="compare-jp-count-cocok">—</span>
                                </div>
                                <div class="alert alert-info py-2 mb-3" id="compare-jp-status">
                                    Pilih file CSV, bulan, tahun, dan tabel manual — klik <strong>Compare</strong>.
                                </div>

                                <div id="compare-jp-results-panel" class="d-none">
                                    <h5 class="mb-3 text-primary"><i class="fas fa-chart-bar"></i> Hasil Komparasi Jurnal Penyesuaian</h5>
                                    <div class="row">
                                    <?php foreach ($compare_sections as $sec) { ?>
                                        <div class="<?php echo $sec['col']; ?> mb-3">
                                            <div class="compare-jp-section-card compare-theme-<?php echo $sec['theme']; ?>">
                                                <div class="compare-jp-section-header">
                                                    <div class="compare-jp-section-title">
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
                                                    <table id="<?php echo $sec['table']; ?>" class="table table-bordered table-sm compare-dt compare-jp-dt" style="width:100%;">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th><th>Tanggal</th><th>Bukti</th><th>PL</th><th>Keterangan</th>
                                                                <th>Kode Rek.</th><th>Kode Akun</th><th>Debet</th><th>Kredit</th><th>Catatan</th>
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

                                <div class="modal fade" id="modal-compare-jp-tabel-detail" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document" style="max-width:95%;">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white py-2">
                                                <h5 class="modal-title" id="modal-compare-jp-tabel-detail-title">Jurnal Penyesuaian</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <div class="modal-body pb-2">
                                                <div class="d-flex flex-wrap align-items-center mb-2">
                                                    <p class="text-muted small mb-0 mr-3" id="compare-jp-tabel-detail-meta">Memuat...</p>
                                                    <button type="button" id="btn-compare-jp-tabel-detail-excel" class="btn btn-success btn-sm">
                                                        <i class="fa fa-file-excel-o"></i> Cetak ke Excel
                                                    </button>
                                                </div>
                                                <table id="table-compare-jp-tabel-detail" class="table table-bordered table-striped table-sm" style="width:100%;font-size:12px;">
                                                    <thead id="compare-jp-tabel-detail-thead">
                                                        <tr>
                                                            <th>No</th><th>Tanggal</th><th>Keterangan</th><th>Akun</th><th>Debet</th><th>Kredit</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                    <tfoot id="compare-jp-tabel-detail-tfoot">
                                                        <tr class="compare-dt-total-row">
                                                            <th colspan="4" class="text-right compare-jp-detail-total-label">Total</th>
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

<?php $action_simpan = site_url('Jurnal_penyesuaian/ajax_simpan_input_data'); ?>
<form id="form-input-jurnal-penyesuaian" action="<?php echo $action_simpan; ?>" method="post">
    <div class="modal fade" id="modal_input_jurnal_penyesuaian">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">INPUT DATA PENYESUAIAN</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div id="form-input-jp-alert" class="alert alert-danger d-none py-2"></div>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="tgl_po">Tanggal <span class="text-danger">*</span></label>
                            <div class="input-group date" id="tgl_po" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#tgl_po" name="tgl_po" id="input_tgl_po" value="<?php echo date('d-m-Y'); ?>" required />
                                <div class="input-group-append" data-target="#tgl_po" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="input_bukti">Bukti</label>
                            <input type="text" name="bukti" id="input_bukti" class="form-control" placeholder="Opsional">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="input_pl">PL</label>
                            <select name="pl" id="input_pl" class="form-control select2" style="width:100%;">
                                <option value="">Pilih Kode PL</option>
                                <?php foreach ($this->db->query('SELECT * FROM sys_kode_pl ORDER BY kode_pl ASC')->result() as $pl_row) { ?>
                                    <option value="<?php echo htmlspecialchars($pl_row->kode_pl, ENT_QUOTES, 'UTF-8'); ?>"><?php echo strtoupper($pl_row->kode_pl) . ' ==> ' . strtoupper($pl_row->keterangan); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="kode_rekening">Kode Rekening <span class="text-danger">*</span></label>
                            <input type="text" name="kode_rekening" id="kode_rekening" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="kode_akun">Kode Akun <span class="text-danger">*</span></label>
                            <select name="kode_akun" id="kode_akun" class="form-control select2" style="width:100%;" required>
                                <option value="">Pilih Kode Akun</option>
                                <?php foreach ($this->db->query('SELECT * FROM sys_kode_akun ORDER BY kode_akun ASC')->result() as $m) { ?>
                                    <option value="<?php echo htmlspecialchars($m->kode_akun, ENT_QUOTES, 'UTF-8'); ?>"><?php echo strtoupper($m->kode_akun) . ' ==> ' . strtoupper($m->nama_akun); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="status_proses">Debet / Kredit <span class="text-danger">*</span></label>
                            <select name="status_proses" id="status_proses" class="form-control" required>
                                <option value=""></option>
                                <option value="debet">Debet</option>
                                <option value="kredit">Kredit</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="nominal_penyesuaian">Nominal <span class="text-danger">*</span></label>
                            <input type="number" name="nominal_penyesuaian" id="nominal_penyesuaian" class="form-control" min="0" step="any" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <input type="text" name="keterangan" id="keterangan" class="form-control" placeholder="Opsional">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn-simpan-jp"><i class="fa fa-save"></i> SIMPAN</button>
                </div>
            </div>
        </div>
    </div>
</form>

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
<style type="text/css">
    .nav-tabs.jurnal-penyesuaian-tabs { border-bottom: 2px solid #dc3545; margin-bottom: 0; }
    .nav-tabs.jurnal-penyesuaian-tabs .nav-link { border: 2px solid #dc3545; border-bottom: none; color: #666; margin-right: 4px; border-radius: 4px 4px 0 0; background: #fff; }
    .nav-tabs.jurnal-penyesuaian-tabs .nav-link.active { background: #dc3545; color: #fff; font-weight: bold; }
    .jurnal-penyesuaian-tab1-toolbar { padding: 10px 12px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 6px; }
    .jp-dt-wrap { border: 2px solid #dc3545; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(220,53,69,.12); padding: 8px; background: #fff; }
    .jp-main-dt thead th { background: linear-gradient(180deg, #fde8ea, #f8f9fa); border-color: #f5c6cb !important; font-size: 12px; white-space: nowrap; vertical-align: middle; }
    .jp-main-dt tbody td { font-size: 12px; border-color: #dee2e6 !important; padding: 6px 8px; }
    .jp-main-dt tfoot .jp-total-row th { background: #fff3cd !important; font-weight: 700; border-color: #ffc107 !important; }
    .compare-toolbar-row .compare-toolbar-control { width: 110px; min-width: 110px; }
    #compare_tabel_jp.compare-toolbar-tabel { width: 320px; min-width: 240px; max-width: 420px; }
    .compare-csv-file-wrap { max-width: 520px; min-width: 280px; flex: 0 1 520px; }
    .compare-jp-section-card { border-radius: 10px; border: 1px solid #dee2e6; background: #fff; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.05); height: 100%; }
    .compare-jp-section-header { display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; border-bottom: 1px solid rgba(0,0,0,.08); }
    .compare-jp-section-title { display: flex; align-items: center; gap: 10px; }
    .compare-section-num { display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background: rgba(0,0,0,.08); font-weight: 700; font-size: 12px; }
    .compare-section-label { font-weight: 700; font-size: 14px; }
    .compare-section-subtitle { font-size: 11px; color: #6c757d; }
    .compare-theme-primary .compare-jp-section-header { background: linear-gradient(90deg, #fde8ea, #fff); border-left: 4px solid #dc3545; }
    .compare-theme-info .compare-jp-section-header { background: linear-gradient(90deg, #e8f7fa, #fff); border-left: 4px solid #17a2b8; }
    .compare-theme-success .compare-jp-section-header { background: linear-gradient(90deg, #e8f5e9, #fff); border-left: 4px solid #28a745; }
    .compare-theme-warning .compare-jp-section-header { background: linear-gradient(90deg, #fff8e1, #fff); border-left: 4px solid #ffc107; }
    .compare-theme-cyan .compare-jp-section-header { background: linear-gradient(90deg, #e0f7fa, #fff); border-left: 4px solid #17a2b8; }
    .compare-dt-wrap table.dataTable thead th { background: #f8f9fa; font-size: 11px; white-space: nowrap; }
    .compare-dt-wrap table.dataTable tbody td { font-size: 11px; padding: 5px 7px; vertical-align: middle; }
    .compare-dt-total-row th { background: #fff3cd !important; font-weight: 700; }
    .text-amount-debet { color: #155724; font-weight: 600; }
    .text-amount-kredit { color: #0c5460; font-weight: 600; }
    .text-catatan { font-size: 11px; color: #856404; }
    .jp-month-picker { font-weight: 600; border: 2px solid #dc3545; border-radius: 6px; }
    .compare-jp-tabel-info-box {
        border: 1px solid #f5c6cb; border-radius: 8px; background: linear-gradient(180deg, #fff8f8, #fff);
        padding: 12px 16px; box-shadow: 0 1px 6px rgba(220,53,69,.08);
    }
    .compare-jp-tabel-info-box .compare-info-title { font-weight: 700; margin-bottom: 6px; color: #721c24; }
    .compare-jp-tabel-info-box .compare-info-line { font-size: 13px; margin-bottom: 4px; }
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
    var urlAjaxList = <?php echo json_encode($url_ajax_list); ?>;
    var urlAjaxSimpan = <?php echo json_encode($url_ajax_simpan); ?>;

    function escHtml(v) {
        return jQuery('<span>').text(v == null ? '' : String(v)).html();
    }

    function syncCompareFromBulanNs() {
        var val = jQuery('#bulan_ns').val() || '';
        if (!/^\d{4}-\d{2}$/.test(val)) return;
        var parts = val.split('-');
        jQuery('#compare_tahun_jp').val(parseInt(parts[0], 10));
        jQuery('#compare_bulan_jp').val(parseInt(parts[1], 10));
    }

    function submitCariJurnalPenyesuaian() {
        syncCompareFromBulanNs();
        var activeTab = document.querySelector('#jurnal-penyesuaian-tabs .nav-link.active');
        document.getElementById('active_tab_input').value = (activeTab && activeTab.id === 'tab-compare-jp') ? 'compare' : 'data';
        document.getElementById('form-cari-jurnal-penyesuaian').submit();
    }

    var bulanNs = document.getElementById('bulan_ns');
    if (bulanNs) {
        bulanNs.addEventListener('change', function() {
            if (this.value) submitCariJurnalPenyesuaian();
        });
    }

    window.addEventListener('load', function() {
        if (!window.jQuery || !jQuery.fn.DataTable) return;

        var mainDt = jQuery('#table-jurnal-penyesuaian-data').DataTable({
            scrollX: true, scrollY: '450px', scrollCollapse: true,
            paging: true, searching: true, ordering: true, pageLength: 50,
            order: [[1, 'asc']],
            language: { url: '//cdn.datatables.net/plug-ins/1.11.4/i18n/id.json' }
        });

        function refreshMainDatatable() {
            jQuery.ajax({
                url: urlAjaxList,
                type: 'POST',
                dataType: 'json',
                data: { bulan_ns: jQuery('#bulan_ns').val() || '' }
            }).done(function(res) {
                if (!res || !res.ok) return;
                var rows = (res.rows || []).map(function(r) {
                    return [
                        r.no,
                        escHtml(r.tanggal),
                        escHtml(r.bukti),
                        escHtml(r.pl),
                        escHtml(r.keterangan),
                        escHtml(r.kode_rekening),
                        escHtml(r.kode_akun),
                        escHtml(r.debet_display),
                        escHtml(r.kredit_display)
                    ];
                });
                mainDt.clear();
                mainDt.rows.add(rows);
                mainDt.draw();
                jQuery('#jp-total-debet').text(res.total_debet || '');
                jQuery('#jp-total-kredit').text(res.total_kredit || '');
                jQuery('#jp-label-periode').text(res.periode_label || '');
            });
        }

        function resetInputForm() {
            var $form = jQuery('#form-input-jurnal-penyesuaian');
            $form[0].reset();
            jQuery('#form-input-jp-alert').addClass('d-none').text('');
            jQuery('#kode_akun, #input_pl').val('').trigger('change');
            jQuery('#input_tgl_po').val('<?php echo date('d-m-Y'); ?>');
        }

        jQuery('#modal_input_jurnal_penyesuaian').on('show.bs.modal', function() {
            resetInputForm();
        });

        jQuery('#btn-simpan-jp').on('click', function() {
            var $form = jQuery('#form-input-jurnal-penyesuaian');
            var $btn = jQuery(this);
            if (!$form[0].checkValidity()) {
                $form[0].reportValidity();
                return;
            }
            $btn.prop('disabled', true);
            jQuery('#form-input-jp-alert').addClass('d-none').text('');
            jQuery.ajax({
                url: urlAjaxSimpan,
                type: 'POST',
                dataType: 'json',
                data: $form.serialize()
            }).done(function(res) {
                $btn.prop('disabled', false);
                if (!res || !res.ok) {
                    jQuery('#form-input-jp-alert').removeClass('d-none').text((res && res.message) ? res.message : 'Gagal menyimpan data.');
                    return;
                }
                jQuery('#modal_input_jurnal_penyesuaian').modal('hide');
                refreshMainDatatable();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message || 'Data berhasil disimpan.', timer: 2000, showConfirmButton: false });
                }
            }).fail(function() {
                $btn.prop('disabled', false);
                jQuery('#form-input-jp-alert').removeClass('d-none').text('Tidak dapat menghubungi server.');
            });
        });

        if (jQuery.fn.datetimepicker) {
            jQuery('#tgl_po').datetimepicker({ format: 'DD-MM-YYYY', locale: 'id' });
        }

        jQuery('#btn-jurnal-penyesuaian-excel').on('click', function() {
            var f = jQuery('<form method="post" target="_blank"></form>');
            f.attr('action', urlExcel);
            f.append(jQuery('<input type="hidden" name="bulan_ns">').val(jQuery('#bulan_ns').val() || ''));
            jQuery('body').append(f); f.submit(); f.remove();
        });

        jQuery('#jurnal-penyesuaian-tabs a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
            document.getElementById('active_tab_input').value = (e.target.id === 'tab-compare-jp') ? 'compare' : 'data';
        });

        var lastResult = null, dtMap = {}, tablesLoaded = false, csvBusy = false, csvLast = null;
        var tabelImportState = null, tabelImportBusy = false;

        function bulanKey() {
            var b = parseInt(jQuery('#compare_bulan_jp').val(), 10);
            var t = parseInt(jQuery('#compare_tahun_jp').val(), 10);
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
            var $el = jQuery('#compare-jp-status');
            $el.removeClass('alert-info alert-success alert-danger alert-warning').addClass(type === 'success' ? 'alert-success' : (type === 'danger' ? 'alert-danger' : (type === 'warning' ? 'alert-warning' : 'alert-info'))).html(html);
        }
        function hideTabelActions() {
            jQuery('#compare-jp-tabel-actions').addClass('d-none');
            jQuery('#compare-jp-tabel-info-body').empty();
            jQuery('#btn-compare-jp-tabel-import').prop('disabled', true);
            jQuery('#compare-jp-tabel-import-note').text('').removeClass('text-danger text-success text-muted text-warning');
        }
        function getSelectedTable() {
            return jQuery('#compare_tabel_jp').val() || '';
        }
        function getActiveBulanNs() {
            var bk = bulanKey();
            if (bk && /^\d{4}-\d{2}$/.test(bk)) return bk;
            return jQuery('#bulan_ns').val() || '';
        }
        function buildTabelInfoHtml(res) {
            var tbl = getSelectedTable() || (res && res.table) || '—';
            var bk = getActiveBulanNs() || (res && res.bulan) || '—';
            var stats = (res && res.stats) ? res.stats : {};
            var map = (res && res.map) ? res.map : {};
            var mapParts = [];
            ['tanggal', 'keterangan', 'kode_akun', 'kode_rekening', 'bukti', 'pl', 'debet', 'kredit'].forEach(function(key) {
                if (map[key]) mapParts.push(key + ' → <code>' + jQuery('<span>').text(map[key]).html() + '</code>');
            });
            var html = '<div class="compare-info-title"><i class="fas fa-info-circle"></i> Informasi Tabel Terpilih</div>';
            html += '<div class="compare-info-line">Tabel: <strong class="text-primary">' + jQuery('<span>').text(tbl).html() + '</strong></div>';
            html += '<div class="compare-info-line">Bulan proses: <strong>' + jQuery('<span>').text(bk).html() + '</strong></div>';
            if (mapParts.length) html += '<div class="compare-info-line">Mapping kolom: ' + mapParts.join(' | ') + '</div>';
            if (stats.saveable_in_bulan != null) {
                html += '<div class="compare-info-line">Baris siap simpan: <strong>' + (stats.saveable_in_bulan || 0) + '</strong>';
                if (stats.in_bulan != null) html += ' | baris bulan terpilih: <strong>' + (stats.in_bulan || 0) + '</strong>';
                if (stats.out_bulan > 0) html += ' | di luar bulan: <strong class="text-warning">' + stats.out_bulan + '</strong>';
                html += '</div>';
            }
            html += '<div class="compare-info-line">Syarat baris: tanggal valid, debet atau kredit terisi (keterangan/akun boleh kosong).</div>';
            if (res && res.jurnal_penyesuaian_bulan_conflict && res.conflict_warning) {
                html += '<div class="compare-info-line text-warning"><i class="fas fa-exclamation-triangle"></i> ' + jQuery('<span>').text(res.conflict_warning).html() + '</div>';
            }
            return html;
        }
        function applyTabelImportState(res) {
            tabelImportState = res || null;
            var tbl = getSelectedTable();
            if (!tbl) { hideTabelActions(); return; }
            jQuery('#compare-jp-tabel-actions').removeClass('d-none');
            jQuery('#btn-compare-jp-tabel-detail').prop('disabled', false);
            if (!res || res.ok === false || !res.eligible) {
                jQuery('#compare-jp-tabel-info-body').html(
                    '<div class="compare-info-title text-warning"><i class="fas fa-exclamation-triangle"></i> Tabel belum memenuhi syarat import</div>'
                    + '<div class="compare-info-line">Tabel: <strong>' + jQuery('<span>').text(tbl).html() + '</strong></div>'
                    + '<div class="compare-info-line">' + jQuery('<span>').text((res && res.message) ? res.message : 'Kolom wajib: tanggal, keterangan, akun, debet/kredit.').html() + '</div>'
                );
                jQuery('#btn-compare-jp-tabel-import').prop('disabled', true);
                return;
            }
            jQuery('#compare-jp-tabel-info-body').html(buildTabelInfoHtml(res));
            var enabled = !!res.import_enabled;
            jQuery('#btn-compare-jp-tabel-import').prop('disabled', !enabled);
            var $note = jQuery('#compare-jp-tabel-import-note');
            $note.removeClass('text-danger text-success text-muted text-warning');
            if (enabled) {
                $note.addClass(res.jurnal_penyesuaian_bulan_conflict ? 'text-warning' : 'text-success');
                $note.html('<i class="fas fa-check-circle"></i> ' + (res.import_message || 'Siap disimpan ke jurnal_penyesuaian.'));
            } else {
                $note.addClass('text-danger').html('<i class="fas fa-exclamation-circle"></i> ' + (res.import_message || 'Tidak ada data yang bisa disimpan.'));
            }
        }
        function parseValidateError(xhr, res, fallback) {
            var msg = fallback || 'Gagal memeriksa tabel terpilih.';
            if (res && res.message) return res.message;
            if (xhr && xhr.responseText) {
                try {
                    var j = JSON.parse(xhr.responseText);
                    if (j && j.message) return j.message;
                } catch (eJson) {
                    if (xhr.responseText.indexOf('Database Error') !== -1) return 'Error database saat memeriksa tabel.';
                }
            }
            return msg;
        }
        function validateTabelForImport() {
            var tbl = getSelectedTable();
            if (!tbl) { hideTabelActions(); toggleBtns(); return; }
            jQuery('#compare-jp-tabel-actions').removeClass('d-none');
            jQuery('#compare-jp-tabel-info-body').html('<div class="compare-info-title"><i class="fas fa-spinner fa-spin"></i> Memeriksa tabel terpilih...</div>');
            jQuery('#btn-compare-jp-tabel-detail, #btn-compare-jp-tabel-import').prop('disabled', true);
            var bk = getActiveBulanNs();
            jQuery.ajax({
                url: urlValidate, type: 'POST', dataType: 'json',
                data: { tabel: tbl, bulan: bk, bulan_num: jQuery('#compare_bulan_jp').val(), tahun: jQuery('#compare_tahun_jp').val() }
            }).done(function(res) {
                applyTabelImportState(res);
            }).fail(function(xhr) {
                var res = null;
                try { res = JSON.parse(xhr.responseText); } catch (e) {}
                jQuery('#compare-jp-tabel-info-body').html(
                    '<div class="compare-info-title text-danger"><i class="fas fa-times-circle"></i> Gagal memeriksa tabel</div>'
                    + '<div class="compare-info-line">' + jQuery('<span>').text(parseValidateError(xhr, res, 'Gagal memeriksa tabel terpilih.')).html() + '</div>'
                );
                jQuery('#btn-compare-jp-tabel-import').prop('disabled', true);
            }).always(toggleBtns);
        }
        function toggleBtns() {
            var show = bulanKey() !== '' && (jQuery('#compare_tabel_jp').val() || '') !== '';
            jQuery('#btn-compare-jp').toggleClass('d-none', !show);
            if (!show) jQuery('#btn-compare-jp-excel-all').addClass('d-none');
        }
        function buildRows(items) {
            return (items || []).map(function(it, i) {
                return [i + 1, it.tanggal || '', it.bukti || '', it.pl || '', it.keterangan || '',
                    it.kode_rekening || '', it.kode_akun || '',
                    fmtAmtCell(it.debet, 'debet'), fmtAmtCell(it.kredit, 'kredit'),
                    it.catatan ? '<span class="text-catatan">' + jQuery('<span>').text(it.catatan).html() + '</span>' : ''];
            });
        }
        function buildTabelDetailRows(items, displayColumns) {
            displayColumns = displayColumns || [
                { key: 'tanggal', label: 'Tanggal' },
                { key: 'keterangan', label: 'Keterangan' },
                { key: 'kode_akun', label: 'Akun' },
                { key: 'debet', label: 'Debet' },
                { key: 'kredit', label: 'Kredit' }
            ];
            return (items || []).map(function(it) {
                var row = [it.no || ''];
                displayColumns.forEach(function(col) {
                    if (col.key === 'debet' || col.key === 'kredit') {
                        row.push(fmtAmtCell(it[col.key], col.key));
                    } else {
                        row.push(it[col.key] || '');
                    }
                });
                return row;
            });
        }
        function setupTabelDetailTableHead(displayColumns) {
            displayColumns = displayColumns || [
                { key: 'tanggal', label: 'Tanggal' },
                { key: 'keterangan', label: 'Keterangan' },
                { key: 'kode_akun', label: 'Akun' },
                { key: 'debet', label: 'Debet' },
                { key: 'kredit', label: 'Kredit' }
            ];
            var $head = jQuery('#compare-jp-tabel-detail-thead tr').empty();
            var $foot = jQuery('#compare-jp-tabel-detail-tfoot tr.compare-dt-total-row').empty();
            $head.append('<th>No</th>');
            displayColumns.forEach(function(col) {
                $head.append('<th>' + escHtml(col.label || col.key) + '</th>');
            });
            var debetIdx = -1, kreditIdx = -1;
            displayColumns.forEach(function(col, idx) {
                if (col.key === 'debet') debetIdx = idx;
                if (col.key === 'kredit') kreditIdx = idx;
            });
            var mergeSpan = (debetIdx >= 0) ? (debetIdx + 1) : Math.max(1, displayColumns.length - 1);
            $foot.append('<th colspan="' + mergeSpan + '" class="text-right compare-jp-detail-total-label">Total</th>');
            if (debetIdx >= 0) {
                $foot.append('<th class="compare-total-debet text-right">—</th>');
            }
            if (kreditIdx >= 0) {
                $foot.append('<th class="compare-total-kredit text-right">—</th>');
            }
        }
        var tabelDetailColumns = null;
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
            renderTable('#table-compare-jp-manual', res.data_manual);
            renderTable('#table-compare-jp-online', res.data_online);
            renderTable('#table-compare-jp-cocok', res.data_cocok);
            renderTable('#table-compare-jp-manual-miss', res.manual_tidak_di_online);
            renderTable('#table-compare-jp-online-miss', res.online_tidak_di_manual);
        }
        function updateInfo(res) {
            res = res || lastResult || {};
            var s = res.stats || {};
            jQuery('#compare-jp-label-bulan').text(res.bulan_label || bulanKey() || '—');
            jQuery('#compare-jp-label-tabel').text(res.table || jQuery('#compare_tabel_jp').val() || '—');
            jQuery('#compare-jp-count-manual').text(s.data_manual != null ? s.data_manual : '—');
            jQuery('#compare-jp-count-online').text(s.data_online != null ? s.data_online : '—');
            jQuery('#compare-jp-count-cocok').text(s.data_cocok != null ? s.data_cocok : '—');
            jQuery('#compare-jp-badge-manual').text(s.data_manual || 0);
            jQuery('#compare-jp-badge-online').text(s.data_online || 0);
            jQuery('#compare-jp-badge-cocok').text(s.data_cocok || 0);
            jQuery('#compare-jp-badge-manual-miss').text(s.manual_tidak_di_online || 0);
            jQuery('#compare-jp-badge-online-miss').text(s.online_tidak_di_manual || 0);
        }
        function loadTableList(force, selectTable) {
            if (tablesLoaded && !force) {
                if (selectTable) jQuery('#compare_tabel_jp').val(selectTable);
                if (getSelectedTable()) validateTabelForImport();
                return;
            }
            jQuery.ajax({ url: urlList, type: 'POST', dataType: 'json' }).done(function(res) {
                if (!res || !res.ok) { setStatus('danger', (res && res.message) || 'Gagal memuat daftar tabel.'); return; }
                var $sel = jQuery('#compare_tabel_jp'), cur = selectTable || $sel.val();
                $sel.find('option:not(:first)').remove();
                (res.tables || []).forEach(function(tbl) { $sel.append(jQuery('<option>', { value: tbl, text: tbl })); });
                if (cur) $sel.val(cur);
                tablesLoaded = true;
                if (getSelectedTable()) validateTabelForImport();
            }).always(toggleBtns);
        }
        function runCompare() {
            var bk = bulanKey(), tbl = jQuery('#compare_tabel_jp').val() || '';
            if (!bk || !tbl) { alert('Pilih bulan, tahun, dan tabel database.'); return; }
            if (typeof Swal !== 'undefined') {
                Swal.fire({ title: 'Memproses Compare...', html: 'Membandingkan data manual vs online jurnal_penyesuaian', allowOutsideClick: false, didOpen: function() { Swal.showLoading(); } });
            }
            setStatus('info', '<i class="fas fa-spinner fa-spin"></i> Membandingkan...');
            jQuery('#compare-jp-results-panel, #btn-compare-jp-excel-all').addClass('d-none');
            jQuery.ajax({
                url: urlRun, type: 'POST', dataType: 'json',
                data: { bulan: bk, bulan_num: jQuery('#compare_bulan_jp').val(), tahun: jQuery('#compare_tahun_jp').val(), tabel: tbl }
            }).done(function(res) {
                if (typeof Swal !== 'undefined') Swal.close();
                if (!res || !res.ok) {
                    setStatus('danger', (res && res.message) || 'Compare gagal.');
                    if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Compare Gagal', text: (res && res.message) || 'Compare gagal.' });
                    return;
                }
                lastResult = res; renderAll(res); updateInfo(res);
                jQuery('#compare-jp-results-panel, #btn-compare-jp-excel-all').removeClass('d-none');
                setStatus('success', '<i class="fas fa-check-circle"></i> Compare selesai. Manual: ' + (res.stats.data_manual || 0) + ', Online: ' + (res.stats.data_online || 0) + ', Cocok: ' + (res.stats.data_cocok || 0) + '.');
                if (typeof Swal !== 'undefined') Swal.fire({ icon: 'success', title: 'Compare Selesai', timer: 2500, showConfirmButton: false });
            }).fail(function() {
                if (typeof Swal !== 'undefined') Swal.close();
                setStatus('danger', 'Tidak dapat menghubungi server.');
            });
        }
        function exportCompareExcel() {
            var bk = bulanKey(), tbl = jQuery('#compare_tabel_jp').val() || '';
            if (!bk || !tbl) return;
            var f = jQuery('<form method="post" target="_blank"></form>');
            f.attr('action', urlCompareExcel);
            f.append(jQuery('<input type="hidden" name="bulan">').val(bk));
            f.append(jQuery('<input type="hidden" name="bulan_num">').val(jQuery('#compare_bulan_jp').val()));
            f.append(jQuery('<input type="hidden" name="tahun">').val(jQuery('#compare_tahun_jp').val()));
            f.append(jQuery('<input type="hidden" name="tabel">').val(tbl));
            jQuery('body').append(f); f.submit(); f.remove();
        }
        function openTabelDetailModal() {
            var tbl = getSelectedTable();
            var bk = getActiveBulanNs();
            if (!tbl || !bk) { alert('Pilih tabel dan bulan terlebih dahulu.'); return; }
            jQuery('#modal-compare-jp-tabel-detail-title').text('Detail Tabel — ' + tbl);
            jQuery('#compare-jp-tabel-detail-meta').text('Memuat data tabel `' + tbl + '` bulan ' + bk + '...');
            jQuery('#modal-compare-jp-tabel-detail').modal('show');
            jQuery.ajax({ url: urlDetail, type: 'POST', dataType: 'json', data: { tabel: tbl, bulan: bk } })
            .done(function(res) {
                if (!res || !res.ok) {
                    jQuery('#compare-jp-tabel-detail-meta').text((res && res.message) || 'Gagal memuat detail tabel.');
                    return;
                }
                var items = res.rows || [];
                tabelDetailColumns = res.display_columns || null;
                setupTabelDetailTableHead(tabelDetailColumns);
                jQuery('#compare-jp-tabel-detail-meta').text(
                    'Tabel: ' + (res.table || tbl) + ' | Bulan: ' + (res.bulan_label || bk) + ' | Total: ' + (res.total || items.length) + ' baris'
                );
                var $t = jQuery('#table-compare-jp-tabel-detail');
                if (jQuery.fn.DataTable.isDataTable($t)) $t.DataTable().clear().destroy();
                $t.find('tbody').empty();
                $t.DataTable({
                    data: buildTabelDetailRows(items, tabelDetailColumns),
                    paging: true, searching: true, ordering: true, scrollX: true, pageLength: 25,
                    order: [[1, 'asc']], autoWidth: false,
                    language: { url: '//cdn.datatables.net/plug-ins/1.11.4/i18n/id.json', emptyTable: 'Tidak ada data pada bulan terpilih' },
                    drawCallback: function() {
                        var td = parseAmt(res.total_debet), tk = parseAmt(res.total_kredit);
                        if (!td && items.length) {
                            items.forEach(function(it) { td += parseAmt(it.debet_raw != null ? it.debet_raw : it.debet); });
                        }
                        if (!tk && items.length) {
                            items.forEach(function(it) { tk += parseAmt(it.kredit_raw != null ? it.kredit_raw : it.kredit); });
                        }
                        $t.find('.compare-total-debet').text(td > 0 ? td.toLocaleString('id-ID') : '—');
                        $t.find('.compare-total-kredit').text(tk > 0 ? tk.toLocaleString('id-ID') : '—');
                    }
                });
            }).fail(function() {
                jQuery('#compare-jp-tabel-detail-meta').text('Gagal memuat detail tabel.');
            });
        }
        function exportTabelDetailExcel() {
            var tbl = getSelectedTable();
            var bk = getActiveBulanNs();
            if (!tbl || !bk) { alert('Pilih tabel dan bulan terlebih dahulu.'); return; }
            var f = jQuery('<form method="post" target="_blank"></form>');
            f.attr('action', urlDetailExcel);
            f.append(jQuery('<input type="hidden" name="tabel">').val(tbl));
            f.append(jQuery('<input type="hidden" name="bulan">').val(bk));
            jQuery('body').append(f); f.submit(); f.remove();
        }
        function showImportSuccessAlert(msg) {
            if (typeof Swal === 'undefined') {
                alert(msg || 'Data berhasil disimpan.');
                refreshMainDatatable();
                return;
            }
            Swal.fire({
                icon: 'success',
                title: 'Proses Sukses!',
                html: '<div style="font-size:15px;line-height:1.6;">' + jQuery('<div>').text(msg || 'Data berhasil disimpan ke jurnal_penyesuaian.').html()
                    + '<br><span style="font-size:13px;color:#666;">Datatable Tab 1 akan dimuat ulang otomatis...</span></div>',
                confirmButtonText: 'OK',
                confirmButtonColor: '#28a745',
                timer: 2000,
                timerProgressBar: true,
                allowOutsideClick: false
            }).then(function() { refreshMainDatatable(); });
        }
        function runTabelImportRequest(tbl, bk) {
            tabelImportBusy = true;
            jQuery('#btn-compare-jp-tabel-import').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
            if (typeof Swal !== 'undefined') {
                Swal.fire({ title: 'Memproses Transfer Data...', html: 'Menyimpan data ke tabel <strong>jurnal_penyesuaian</strong>', allowOutsideClick: false, didOpen: function() { Swal.showLoading(); } });
            }
            jQuery.ajax({ url: urlTabelImport, type: 'POST', dataType: 'json', data: { tabel: tbl, bulan: bk } })
            .done(function(res) {
                tabelImportBusy = false;
                if (typeof Swal !== 'undefined') Swal.close();
                jQuery('#btn-compare-jp-tabel-import').html('<i class="fas fa-database"></i> Proses Simpan Data ke Tabel Jurnal Penyesuaian');
                if (!res || !res.ok) {
                    if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Simpan Gagal', text: (res && res.message) || 'Gagal menyimpan data.' });
                    validateTabelForImport();
                    return;
                }
                setStatus('success', res.message || 'Berhasil menyimpan data.');
                validateTabelForImport();
                showImportSuccessAlert(res.message);
            }).fail(function(xhr) {
                tabelImportBusy = false;
                if (typeof Swal !== 'undefined') Swal.close();
                jQuery('#btn-compare-jp-tabel-import').html('<i class="fas fa-database"></i> Proses Simpan Data ke Tabel Jurnal Penyesuaian');
                validateTabelForImport();
                var res = null;
                try { res = JSON.parse(xhr.responseText); } catch (e) {}
                if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Simpan Gagal', text: parseValidateError(xhr, res, 'Tidak dapat menghubungi server.') });
            });
        }
        function importTabelToJurnalPenyesuaian() {
            if (tabelImportBusy) return;
            var tbl = getSelectedTable();
            var bk = getActiveBulanNs();
            if (!tbl || !bk) { alert('Pilih tabel dan bulan terlebih dahulu.'); return; }
            if (!tabelImportState || !tabelImportState.import_enabled) {
                alert((tabelImportState && tabelImportState.import_message) || 'Data tidak bisa disimpan.');
                return;
            }
            var doImport = function() { runTabelImportRequest(tbl, bk); };
            var infoHtml = 'Data valid dari tabel <strong>' + jQuery('<span>').text(tbl).html() + '</strong> akan disimpan ke tabel utama <strong>jurnal_penyesuaian</strong> untuk bulan <strong>' + jQuery('<span>').text(bk).html() + '</strong>.';
            if (tabelImportState.jurnal_penyesuaian_bulan_conflict && tabelImportState.conflict_warning) {
                infoHtml += '<br><br><span class="text-warning">' + jQuery('<span>').text(tabelImportState.conflict_warning).html() + '</span>';
            }
            infoHtml += '<br><br>Klik <strong>OK</strong> untuk memproses transfer data.';
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'info',
                    title: 'Proses Simpan Data ke Jurnal Penyesuaian',
                    html: infoHtml,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#28a745',
                    showCancelButton: true,
                    cancelButtonText: 'Batal'
                }).then(function(r) { if (r.isConfirmed) doImport(); });
                return;
            }
            if (window.confirm('Proses simpan data ke jurnal_penyesuaian?')) doImport();
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
            var ref = { bulan: parseInt(jQuery('#compare_bulan_jp').val(), 10), tahun: parseInt(jQuery('#compare_tahun_jp').val(), 10) };
            var fd = new FormData();
            fd.append('csv_file', file);
            fd.append('bulan_num', ref.bulan); fd.append('tahun', ref.tahun);
            fd.append('bulan', ref.tahun + '-' + String(ref.bulan).padStart(2, '0'));
            jQuery.ajax({ url: urlImport, type: 'POST', data: fd, processData: false, contentType: false, dataType: 'json' })
            .done(function(res) {
                csvBusy = false;
                if (typeof Swal !== 'undefined') Swal.close();
                if (!res || !res.ok) {
                    jQuery('#compare_jp_csv_file').val('').next('.custom-file-label').text('Cari / pilih file CSV...');
                    if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Import Gagal', html: (res && res.message) ? String(res.message).replace(/\n/g, '<br/>') : 'Import gagal.' });
                    return;
                }
                csvLast = res;
                jQuery('#compare_jp_csv_file').val('');
                jQuery('#compare_jp_csv_file').next('.custom-file-label').text(res.file || file.name || 'File CSV terpilih');
                loadTableList(true, res.table);
                setStatus('info', 'CSV berhasil di-upload. Tabel <strong>' + (res.table || '') + '</strong> terpilih — periksa informasi di bawah combobox Pilih tabel.');
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'success', title: 'Import CSV Berhasil', html: 'Tabel <strong>' + (res.table || '') + '</strong> — ' + (res.rows || 0) + ' baris di-generate ke database.', timer: 2200, showConfirmButton: false });
                }
            }).fail(function() {
                csvBusy = false;
                if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Import Gagal', text: 'Tidak dapat menghubungi server.' });
            });
        }

        jQuery('#compare_jp_csv_file').on('change', function() {
            var f = this.files && this.files[0];
            if (f) { jQuery(this).next('.custom-file-label').text(f.name); importCsv(f); }
        });
        jQuery('#compare_bulan_jp, #compare_tahun_jp').on('change', function() {
            var bk = bulanKey();
            if (bk && /^\d{4}-\d{2}$/.test(bk)) {
                jQuery('#bulan_ns').val(bk);
            }
            if (getSelectedTable()) validateTabelForImport();
            toggleBtns();
        });
        jQuery('#compare_tabel_jp').on('change', function() {
            if (jQuery(this).val()) {
                validateTabelForImport();
            } else {
                hideTabelActions();
            }
            toggleBtns();
        });
        jQuery('#btn-compare-jp').on('click', runCompare);
        jQuery('#btn-compare-jp-excel-all').on('click', exportCompareExcel);
        jQuery('#btn-compare-jp-tabel-detail').on('click', openTabelDetailModal);
        jQuery('#btn-compare-jp-tabel-detail-excel').on('click', exportTabelDetailExcel);
        jQuery('#btn-compare-jp-tabel-import').on('click', importTabelToJurnalPenyesuaian);
        jQuery('#tab-compare-jp').on('shown.bs.tab', function() { loadTableList(false); });
        if (jQuery('#tab-compare-jp').hasClass('active')) loadTableList(false);
        toggleBtns();
        syncCompareFromBulanNs();
    });
})();
</script>
