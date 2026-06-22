<?php
$this->load->helper('bukubank_list');

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
    $bulan_label = bukubank_bulan_teks($compare_bulan_num) . ' ' . $compare_tahun_num;
}
if (!isset($data_buku_bank)) {
    $data_buku_bank = array();
}
if (!isset($total_debet)) {
    $total_debet = 0;
}
if (!isset($total_kredit)) {
    $total_kredit = 0;
}
if (!isset($total_saldo)) {
    $total_saldo = 0;
}
$url_ajax_list = isset($url_ajax_list) ? $url_ajax_list : site_url('Bukubank/ajax_list_data');
$url_excel = isset($url_bukubank_excel) ? $url_bukubank_excel : site_url('Bukubank/excel_list');
$url_compare_run = isset($url_compare_run) ? $url_compare_run : site_url('Bukubank/ajax_compare_bukubank_manual_online');
$url_compare_excel = isset($url_compare_excel) ? $url_compare_excel : site_url('Bukubank/excel_compare_bukubank_manual_online');
$url_compare_import = isset($url_compare_import) ? $url_compare_import : site_url('Bukubank/ajax_compare_import_csv_bukubank');
$url_compare_list = isset($url_compare_list) ? $url_compare_list : site_url('Bukubank/ajax_compare_tabel_list_bukubank');
$url_compare_validate = isset($url_compare_validate) ? $url_compare_validate : site_url('Bukubank/ajax_compare_tabel_validate_bukubank');
$url_compare_detail = isset($url_compare_detail) ? $url_compare_detail : site_url('Bukubank/ajax_compare_tabel_detail_bukubank');
$url_compare_tabel_import = isset($url_compare_tabel_import) ? $url_compare_tabel_import : site_url('Bukubank/ajax_compare_import_table_to_bukubank');
$url_compare_detail_excel = isset($url_compare_detail_excel) ? $url_compare_detail_excel : site_url('Bukubank/excel_compare_tabel_detail_bukubank');
$url_compare_section_excel = isset($url_compare_section_excel) ? $url_compare_section_excel : site_url('Bukubank/excel_compare_section_bukubank');

$compare_sections = array(
    array('jenis' => 'data_manual', 'num' => '1', 'label' => 'Data Manual', 'subtitle' => 'Data lengkap dari tabel terpilih', 'badge' => 'compare-bk-badge-manual', 'table' => 'table-compare-bk-manual', 'theme' => 'primary', 'icon' => 'fa-database', 'col' => 'col-lg-6'),
    array('jenis' => 'data_online', 'num' => '2', 'label' => 'Data Online', 'subtitle' => 'Data bukubank (online) bulan terpilih', 'badge' => 'compare-bk-badge-online', 'table' => 'table-compare-bk-online', 'theme' => 'info', 'icon' => 'fa-cloud', 'col' => 'col-lg-6'),
    array('jenis' => 'data_cocok', 'num' => '3', 'label' => 'Data Cocok (Manual & Online)', 'subtitle' => 'Tanggal, bank, norek, kode, keterangan, debet, kredit sama', 'badge' => 'compare-bk-badge-cocok', 'table' => 'table-compare-bk-cocok', 'theme' => 'success', 'icon' => 'fa-check-circle', 'col' => 'col-lg-6'),
    array('jenis' => 'manual_tidak_di_online', 'num' => '4', 'label' => 'Manual Tidak Ada di Online', 'subtitle' => 'Ada di manual, tidak cocok / tidak ada di bukubank', 'badge' => 'compare-bk-badge-manual-miss', 'table' => 'table-compare-bk-manual-miss', 'theme' => 'warning', 'icon' => 'fa-exclamation-triangle', 'col' => 'col-lg-6'),
    array('jenis' => 'online_tidak_di_manual', 'num' => '5', 'label' => 'Online Tidak Ada di Manual', 'subtitle' => 'Ada di bukubank, tidak cocok di manual', 'badge' => 'compare-bk-badge-online-miss', 'table' => 'table-compare-bk-online-miss', 'theme' => 'cyan', 'icon' => 'fa-exchange-alt', 'col' => 'col-lg-12'),
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
                                <strong>BUKU BANK</strong>
                                <span class="text-muted small d-block" id="bk-bulan-label"><?php echo htmlspecialchars($bulan_label, ENT_QUOTES, 'UTF-8'); ?></span>
                            </div>
                            <div class="col-lg-9">
                                <form id="form-cari-buku-bank" method="post" action="#" onsubmit="return false;">
                                <div class="bk-filter-row d-flex flex-wrap justify-content-center align-items-center">
                                    <input type="month" class="form-control form-control-sm bk-month-input" id="bulan_ns" name="bulan_ns" value="<?php echo htmlspecialchars($bulan_ns_value, ENT_QUOTES, 'UTF-8'); ?>">
                                    <button type="button" class="btn btn-danger btn-sm btn-flat ml-2" id="btn-cari-bk">
                                        <i class="fa fa-search" aria-hidden="true"></i> Cari
                                    </button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <ul class="nav nav-tabs buku-bank-tabs" id="buku-bank-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link<?php echo $tab_data_active ? ' active' : ''; ?>" id="tab-bk-data" data-toggle="pill" href="#panel-bk-data" role="tab">Buku Bank</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link<?php echo $tab_compare_active ? ' active' : ''; ?>" id="tab-compare-bk" data-toggle="pill" href="#panel-compare-bk" role="tab">Compare Data Manual - Online</a>
                            </li>
                        </ul>

                        <div class="tab-content mt-3" id="buku-bank-tabs-content">
                            <div class="tab-pane fade<?php echo $tab_data_active ? ' show active' : ''; ?>" id="panel-bk-data" role="tabpanel">
                                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 buku-bank-tab1-toolbar">
                                    <div class="d-flex flex-wrap align-items-center">
                                        <h5 class="mb-0 text-primary mr-2"><strong>Buku Bank</strong></h5>
                                        <?php echo anchor(site_url('Bukubank/create'), '<i class="fa fa-plus"></i> Input Buku Bank', 'class="btn btn-success btn-sm"'); ?>
                                        <small class="text-muted ml-2 d-none d-md-inline">Pilih bulan di atas — data dimuat otomatis</small>
                                    </div>
                                    <button type="button" class="btn btn-success mt-2 mt-md-0" id="btn-buku-bank-excel">
                                        <i class="fa fa-file-excel-o"></i> Cetak ke Excel
                                    </button>
                                </div>

                                <div class="bk-dt-wrap">
                                    <table id="table-buku-bank-data" class="table table-bordered table-striped table-sm display nowrap bk-main-dt" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th rowspan="2" style="text-align:center">No</th>
                                                <th rowspan="2" style="text-align:center">Action</th>
                                                <th rowspan="2">Tanggal</th>
                                                <th colspan="2" style="text-align:center">Rekening</th>
                                                <th rowspan="2">Keterangan</th>
                                                <th rowspan="2">Kode</th>
                                                <th rowspan="2">Debet</th>
                                                <th rowspan="2">Kredit</th>
                                                <th rowspan="2">Saldo</th>
                                            </tr>
                                            <tr>
                                                <th>Bank</th>
                                                <th>Nomor rekening</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($data_buku_bank as $list_data) { ?>
                                            <tr>
                                                <td><?php echo (int) $list_data['no']; ?></td>
                                                <td style="text-align:left">
                                                    <?php
                                                    echo anchor(site_url('Bukubank/update/' . $list_data['id']), '<i class="fa fa-pencil-square-o">Ubah</i>', array('title' => 'edit', 'class' => 'btn btn-warning btn-sm'));
                                                    echo ' ';
                                                    echo anchor(site_url('Bukubank/delete/' . $list_data['id']), '<i class="fa fa-trash-o">Hapus</i>', 'title="delete" class="btn btn-danger btn-sm" onclick="javasciprt: return confirm(\'Anda Yakin Akan Menghapus Data ini ?\')"');
                                                    ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($list_data['tanggal'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($list_data['bank'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($list_data['norek'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($list_data['keterangan'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($list_data['kode'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="text-right"><?php echo htmlspecialchars($list_data['debet_display'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="text-right"><?php echo htmlspecialchars($list_data['kredit_display'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="text-right"><?php echo htmlspecialchars($list_data['saldo_display'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr class="bk-total-row">
                                                <th colspan="6" class="text-right">JUMLAH DEBET / KREDIT</th>
                                                <th class="text-right bk-total-debet"><?php echo bukubank_format_rupiah($total_debet, true); ?></th>
                                                <th class="text-right bk-total-kredit"><?php echo bukubank_format_rupiah($total_kredit, true); ?></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade<?php echo $tab_compare_active ? ' show active' : ''; ?>" id="panel-compare-bk" role="tabpanel">
                                <small class="text-muted d-block mb-2">
                                    Bandingkan data buku bank online (<strong>bukubank</strong>) dengan tabel manual hasil upload CSV.
                                    Kolom compare: <strong>tanggal, bank, norek, keterangan, kode, debet, kredit</strong>.
                                </small>

                                <label class="mb-1 d-block">Pilih file CSV</label>
                                <div class="d-flex flex-wrap align-items-center compare-csv-upload-row mb-3">
                                    <button type="button" id="btn-bk-pick-csv" class="btn btn-primary btn-sm mr-2 mb-1">
                                        <i class="fas fa-file-csv"></i> Pilih File CSV
                                    </button>
                                    <span id="compare-bk-csv-selected-name" class="text-muted small mb-1">Belum ada file dipilih</span>
                                    <input type="file" class="d-none" id="compare_bk_csv_file" accept=".csv,text/csv">
                                </div>

                                <div id="compare-bk-csv-upload-info" class="compare-bk-tabel-info-box py-3 d-none mb-3">
                                    <div class="compare-info-title"><i class="fas fa-info-circle"></i> Informasi Tabel Hasil Upload CSV</div>
                                    <div class="small mb-1 compare-info-line"><span class="text-muted">File:</span> <strong id="compare-bk-csv-filename">—</strong></div>
                                    <div class="small mb-1 compare-info-line"><span class="text-muted">Nama Tabel DB:</span> <strong id="compare-bk-csv-tablename" class="text-primary">—</strong> <span class="text-muted" id="compare-bk-csv-rowcount"></span></div>
                                    <div class="small mb-2 compare-info-line"><span class="text-muted">Kolom:</span> <strong id="compare-bk-csv-colcount">—</strong></div>
                                    <button type="button" id="btn-compare-bk-csv-detail" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-table"></i> Detail Tabel
                                    </button>
                                </div>

                                <div class="row mb-3 align-items-end compare-toolbar-row flex-wrap">
                                    <div class="col-auto mb-2">
                                        <label for="compare_bulan_bk" class="small mb-1">Bulan</label>
                                        <select id="compare_bulan_bk" class="form-control form-control-sm compare-toolbar-control">
                                            <?php foreach ($nama_bulan_id as $num => $label_bulan) { ?>
                                                <option value="<?php echo (int) $num; ?>"<?php echo ((int) $num === (int) $compare_bulan_num) ? ' selected' : ''; ?>><?php echo htmlspecialchars($label_bulan, ENT_QUOTES, 'UTF-8'); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-auto mb-2">
                                        <label for="compare_tahun_bk" class="small mb-1">Tahun</label>
                                        <select id="compare_tahun_bk" class="form-control form-control-sm compare-toolbar-control">
                                            <?php for ($th = $gen_tahun_max; $th >= $gen_tahun_min; $th--) { ?>
                                                <option value="<?php echo (int) $th; ?>"<?php echo ((int) $th === (int) $compare_tahun_num) ? ' selected' : ''; ?>><?php echo (int) $th; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-auto mb-2">
                                        <label for="compare_tabel_bk" class="small mb-1">Pilih tabel</label>
                                        <select id="compare_tabel_bk" class="form-control form-control-sm compare-toolbar-control compare-toolbar-tabel">
                                            <option value="">— Muat daftar tabel —</option>
                                        </select>
                                    </div>
                                    <div class="col-auto mb-2">
                                        <label class="small mb-1 d-block">&nbsp;</label>
                                        <div class="d-flex flex-wrap align-items-center">
                                            <button type="button" id="btn-compare-bk" class="btn btn-info btn-sm d-none"><i class="fas fa-columns"></i> Compare</button>
                                            <button type="button" id="btn-compare-bk-excel-all" class="btn btn-success btn-sm d-none ml-2"><i class="fa fa-file-excel-o"></i> Cetak ke Excel</button>
                                        </div>
                                    </div>
                                </div>

                                <div id="compare-bk-tabel-actions" class="compare-bk-tabel-info-box py-3 mb-3 d-none">
                                    <div id="compare-bk-tabel-info-body" class="mb-2"></div>
                                    <div id="compare-bk-tabel-import-note" class="small mb-2"></div>
                                    <div class="d-flex flex-wrap align-items-center">
                                        <button type="button" id="btn-compare-bk-tabel-detail" class="btn btn-outline-primary btn-sm mr-2 mb-1">
                                            <i class="fas fa-table"></i> Detail Tabel
                                        </button>
                                        <button type="button" id="btn-compare-bk-tabel-import" class="btn btn-success btn-sm mb-1" disabled>
                                            <i class="fas fa-database"></i> Proses Simpan Data ke Tabel Utama : Buku Bank
                                        </button>
                                    </div>
                                </div>

                                <div class="alert alert-secondary py-2" id="compare-bk-info-ringkas">
                                    <strong>Bulan:</strong> <span id="compare-bk-label-bulan">—</span>
                                    &nbsp;|&nbsp; <strong>Tabel manual:</strong> <span id="compare-bk-label-tabel">—</span>
                                    &nbsp;|&nbsp; <strong>Manual:</strong> <span id="compare-bk-count-manual">—</span>
                                    &nbsp;|&nbsp; <strong>Online:</strong> <span id="compare-bk-count-online">—</span>
                                    &nbsp;|&nbsp; <strong>Cocok:</strong> <span id="compare-bk-count-cocok">—</span>
                                </div>
                                <div class="alert alert-info py-2 mb-3" id="compare-bk-status">
                                    Pilih file CSV, bulan, tahun, dan tabel manual — klik <strong>Compare</strong>.
                                </div>
                                <div class="alert alert-warning py-2 mb-3 d-none" id="compare-bk-field-info"></div>
                                <div class="alert alert-warning py-2 mb-3 d-none" id="compare-bk-warnings"></div>

                                <div id="compare-bk-results-panel" class="d-none">
                                    <h5 class="mb-3 text-primary"><i class="fas fa-chart-bar"></i> Hasil Komparasi Buku Bank</h5>
                                    <div class="row">
                                    <?php foreach ($compare_sections as $sec) { ?>
                                        <div class="<?php echo $sec['col']; ?> mb-3">
                                            <div class="compare-bk-section-card compare-theme-<?php echo $sec['theme']; ?>">
                                                <div class="compare-bk-section-header">
                                                    <div class="compare-bk-section-title">
                                                        <span class="compare-section-num"><?php echo $sec['num']; ?></span>
                                                        <i class="fas <?php echo $sec['icon']; ?> compare-section-icon"></i>
                                                        <div>
                                                            <div class="compare-section-label"><?php echo htmlspecialchars($sec['label'], ENT_QUOTES, 'UTF-8'); ?><?php if ($sec['jenis'] === 'data_manual') { ?> <span class="compare-manual-table-name text-info"></span><?php } ?></div>
                                                            <div class="compare-section-subtitle"><?php echo htmlspecialchars($sec['subtitle'], ENT_QUOTES, 'UTF-8'); ?></div>
                                                        </div>
                                                    </div>
                                                    <span id="<?php echo $sec['badge']; ?>" class="badge compare-section-badge">0</span>
                                                </div>
                                                <div class="compare-dt-wrap">
                                                    <div class="d-flex justify-content-end mb-1">
                                                        <button type="button" class="btn btn-success btn-xs btn-compare-bk-section-excel" data-jenis="<?php echo htmlspecialchars($sec['jenis'], ENT_QUOTES, 'UTF-8'); ?>">
                                                            <i class="fa fa-file-excel-o"></i> Cetak ke Excel
                                                        </button>
                                                    </div>
                                                    <table id="<?php echo $sec['table']; ?>" class="table table-bordered table-sm compare-dt compare-bk-dt" style="width:100%;">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th><th>Tanggal</th><th>Bank</th><th>Norek</th><th>Keterangan</th><th>Kode</th><th>Debet</th><th>Kredit</th><th>Saldo</th><th>Catatan</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                        <tfoot>
                                                            <tr class="compare-dt-total-row">
                                                                <th colspan="6" class="text-right">Total</th>
                                                                <th class="compare-total-debet text-right">—</th>
                                                                <th class="compare-total-kredit text-right">—</th>
                                                                <th class="compare-total-saldo text-right">—</th>
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

                                <div class="modal fade" id="modal-compare-bk-tabel-detail" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document" style="max-width:95%;">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info text-white py-2">
                                                <h5 class="modal-title" id="modal-compare-bk-tabel-detail-title">Detail Tabel</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <div class="modal-body pb-2">
                                                <div class="d-flex flex-wrap align-items-center mb-2">
                                                    <p class="text-muted small mb-0 mr-3" id="compare-bk-tabel-detail-meta">Memuat...</p>
                                                    <button type="button" id="btn-compare-bk-tabel-detail-excel" class="btn btn-success btn-sm">
                                                        <i class="fa fa-file-excel-o"></i> Cetak ke Excel
                                                    </button>
                                                </div>
                                                <div class="compare-dt-wrap bk-detail-dt-wrap">
                                                <table id="table-compare-bk-tabel-detail" class="table table-bordered table-striped table-sm compare-bk-detail-dt" style="width:100%;font-size:12px;">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th><th>Tanggal</th><th>Bank</th><th>Norek</th><th>Keterangan</th><th>Kode</th><th>Debet</th><th>Kredit</th><th>Saldo</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                    <tfoot>
                                                        <tr class="compare-dt-total-row">
                                                            <th colspan="6" class="text-right">Total</th>
                                                            <th class="compare-total-debet text-right">—</th>
                                                            <th class="compare-total-kredit text-right">—</th>
                                                            <th class="compare-total-saldo text-right">—</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                                </div>
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
    .bk-filter-row { gap: 0; }
    .bk-month-input { width: auto; min-width: 150px; }
    .nav-tabs.buku-bank-tabs { border-bottom: 2px solid #007bff; margin-bottom: 0; }
    .nav-tabs.buku-bank-tabs .nav-link { border: 2px solid #007bff; border-bottom: none; color: #666; margin-right: 4px; border-radius: 4px 4px 0 0; background: #fff; }
    .nav-tabs.buku-bank-tabs .nav-link.active { background: #007bff; color: #fff; font-weight: bold; }
    .buku-bank-tab1-toolbar { padding: 10px 12px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 6px; }
    .bk-dt-wrap { border: 2px solid #007bff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,123,255,.12); padding: 8px; background: #fff; }
    .bk-main-dt thead th { background: linear-gradient(180deg, #e7f1ff, #f8f9fa); border-color: #b8daff !important; font-size: 12px; white-space: nowrap; vertical-align: middle; }
    .bk-main-dt tbody td { font-size: 12px; border-color: #dee2e6 !important; padding: 6px 8px; }
    .bk-main-dt tfoot .bk-total-row th { background: #fff3cd !important; font-weight: 700; border-color: #ffc107 !important; }
    .bk-kode-akun-wrap { max-width: 300px; width: 100%; }
    .bk-kode-akun-select { max-width: 300px; width: 100% !important; }
    .bk-kode-akun-wrap .select2-container { max-width: 300px !important; width: 100% !important; }
    .compare-toolbar-row .compare-toolbar-control { width: 110px; min-width: 110px; }
    #compare_tabel_bk.compare-toolbar-tabel { width: 320px; min-width: 240px; max-width: 420px; }
    .compare-csv-file-wrap { max-width: 520px; min-width: 280px; flex: 0 1 520px; }
    .compare-bk-section-card { border-radius: 10px; border: 1px solid #dee2e6; background: #fff; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.05); height: 100%; }
    .compare-bk-section-header { display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; border-bottom: 1px solid rgba(0,0,0,.08); }
    .compare-bk-section-title { display: flex; align-items: center; gap: 10px; }
    .compare-section-num { display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background: rgba(0,0,0,.08); font-weight: 700; font-size: 12px; }
    .compare-section-label { font-weight: 700; font-size: 14px; }
    .compare-section-subtitle { font-size: 11px; color: #6c757d; }
    .compare-theme-primary .compare-bk-section-header { background: linear-gradient(90deg, #e7f1ff, #fff); border-left: 4px solid #007bff; }
    .compare-theme-info .compare-bk-section-header { background: linear-gradient(90deg, #e8f7fa, #fff); border-left: 4px solid #17a2b8; }
    .compare-theme-success .compare-bk-section-header { background: linear-gradient(90deg, #e8f5e9, #fff); border-left: 4px solid #28a745; }
    .compare-theme-warning .compare-bk-section-header { background: linear-gradient(90deg, #fff8e1, #fff); border-left: 4px solid #ffc107; }
    .compare-theme-cyan .compare-bk-section-header { background: linear-gradient(90deg, #e0f7fa, #fff); border-left: 4px solid #17a2b8; }
    .compare-dt-wrap table.dataTable thead th { background: #f8f9fa; font-size: 11px; white-space: nowrap; }
    .compare-dt-wrap table.dataTable tbody td { font-size: 11px; padding: 5px 7px; vertical-align: middle; }
    .compare-dt-total-row th { background: #fff3cd !important; font-weight: 700; }
    .text-amount-debet { color: #155724; font-weight: 600; }
    .text-amount-kredit { color: #0c5460; font-weight: 600; }
    .text-catatan { font-size: 11px; color: #856404; }
    .compare-bk-tabel-info-box {
        border: 1px solid #b8daff; border-radius: 8px; background: linear-gradient(180deg, #f8fbff, #fff);
        padding: 12px 16px; box-shadow: 0 1px 6px rgba(0,123,255,.08);
    }
    .compare-bk-tabel-info-box .compare-info-title { font-weight: 700; margin-bottom: 6px; color: #004085; }
    .compare-bk-tabel-info-box .compare-info-line { font-size: 13px; margin-bottom: 4px; }
    .bk-detail-dt-wrap { border: 2px solid #17a2b8; border-radius: 8px; padding: 8px; background: #fff; box-shadow: 0 2px 10px rgba(23,162,184,.12); }
    .compare-bk-detail-dt thead th { background: linear-gradient(180deg, #e8f7fa, #f8f9fa) !important; font-size: 12px; white-space: nowrap; }
    .compare-bk-detail-dt tbody td { font-size: 12px; padding: 6px 8px; vertical-align: middle; }
    #table-buku-bank-data.bk-table-loading { opacity: 0.55; pointer-events: none; }
</style>

<script>
(function() {
    var LS_BULAN_NS_STORE = 'bk_bulan_ns';
    var LS_ACTIVE_TAB = 'bk_active_tab';

    function saveBkLocalStorage() {
        try {
            localStorage.setItem(LS_BULAN_NS_STORE, jQuery('#bulan_ns').val() || '');
            var activeTab = document.querySelector('#buku-bank-tabs .nav-link.active');
            localStorage.setItem(LS_ACTIVE_TAB, (activeTab && activeTab.id === 'tab-compare-bk') ? 'compare' : 'data');
        } catch (eLs) {}
    }

    function restoreBkLocalStorage() {
        try {
            var lsBulan = localStorage.getItem(LS_BULAN_NS_STORE);
            var lsTab = localStorage.getItem(LS_ACTIVE_TAB);
            if (lsBulan && /^\d{4}-\d{2}$/.test(lsBulan)) {
                jQuery('#bulan_ns').val(lsBulan);
            }
            if (lsTab === 'compare') {
                jQuery('#tab-compare-bk').tab('show');
            } else if (lsTab === 'data') {
                jQuery('#tab-bk-data').tab('show');
            }
        } catch (eLs) {}
    }

    var urlAjaxList = <?php echo json_encode($url_ajax_list); ?>;
    var urlExcel = <?php echo json_encode($url_excel); ?>;
    var urlRun = <?php echo json_encode($url_compare_run); ?>;
    var urlCompareExcel = <?php echo json_encode($url_compare_excel); ?>;
    var urlImport = <?php echo json_encode($url_compare_import); ?>;
    var urlList = <?php echo json_encode($url_compare_list); ?>;
    var urlValidate = <?php echo json_encode($url_compare_validate); ?>;
    var urlDetail = <?php echo json_encode($url_compare_detail); ?>;
    var urlTabelImport = <?php echo json_encode($url_compare_tabel_import); ?>;
    var urlDetailExcel = <?php echo json_encode($url_compare_detail_excel); ?>;
    var urlSectionExcel = <?php echo json_encode($url_compare_section_excel); ?>;

    var bkInitializing = true;
    var bkMainDt = null;
    var bkMonthRefreshTimer = null;
    var bkLastBulanNs = '';

    function initBkMainDt(forceReinit) {
        if (!window.jQuery || !jQuery.fn.DataTable) return null;
        var $t = jQuery('#table-buku-bank-data');
        if (!$t.length) return null;
        if (forceReinit && bkMainDt && jQuery.fn.DataTable.isDataTable($t)) {
            try { $t.DataTable().destroy(); } catch (eDestroy) {}
            bkMainDt = null;
        }
        if (bkMainDt) return bkMainDt;
        try {
            bkMainDt = $t.DataTable({
                scrollX: true,
                scrollY: '450px',
                scrollCollapse: true,
                paging: true,
                searching: true,
                ordering: true,
                pageLength: 50,
                order: [[2, 'asc']],
                orderCellsTop: true,
                autoWidth: false,
                columnDefs: [{ orderable: false, targets: [1] }],
                language: { url: '//cdn.datatables.net/plug-ins/1.11.4/i18n/id.json', emptyTable: 'Tidak ada data pada bulan terpilih' }
            });
        } catch (eDt) {
            console.error('Buku Bank: gagal init DataTable tab 1', eDt);
            bkMainDt = null;
        }
        return bkMainDt;
    }

    function renderBukuBankRowsFallback(items) {
        var $t = jQuery('#table-buku-bank-data');
        var baseUpdate = <?php echo json_encode(site_url('Bukubank/update/')); ?>;
        var baseDelete = <?php echo json_encode(site_url('Bukubank/delete/')); ?>;
        var html = '';
        (items || []).forEach(function(it) {
            var action = '<a href="' + baseUpdate + it.id + '" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o">Ubah</i></a> '
                + '<a href="' + baseDelete + it.id + '" class="btn btn-danger btn-sm" onclick="return confirm(\'Anda Yakin Akan Menghapus Data ini ?\')"><i class="fa fa-trash-o">Hapus</i></a>';
            html += '<tr>'
                + '<td>' + bkEscapeHtml(it.no || '') + '</td>'
                + '<td style="text-align:left">' + action + '</td>'
                + '<td>' + bkEscapeHtml(it.tanggal || '') + '</td>'
                + '<td>' + bkEscapeHtml(it.bank || '') + '</td>'
                + '<td>' + bkEscapeHtml(it.norek || '') + '</td>'
                + '<td>' + bkEscapeHtml(it.keterangan || '') + '</td>'
                + '<td class="text-center">' + bkEscapeHtml(it.kode || '') + '</td>'
                + '<td class="text-right">' + bkEscapeHtml(it.debet_display || '') + '</td>'
                + '<td class="text-right">' + bkEscapeHtml(it.kredit_display || '') + '</td>'
                + '<td class="text-right">' + bkEscapeHtml(it.saldo_display || '') + '</td>'
                + '</tr>';
        });
        if (!html) {
            html = '<tr><td colspan="10" class="text-center text-muted py-3">Tidak ada data pada bulan terpilih</td></tr>';
        }
        $t.find('tbody').html(html);
    }

    function applyBukuBankListResult(res) {
        if (!res || !res.ok) return;
        var rows = buildMainDtRowsBk(res.rows);
        var dt = initBkMainDt(false);
        if (dt) {
            try {
                dt.clear();
                if (rows.length) dt.rows.add(rows);
                dt.draw(false);
            } catch (eRow) {
                console.error('Buku Bank: gagal update DataTable, fallback HTML', eRow);
                initBkMainDt(true);
                renderBukuBankRowsFallback(res.rows || []);
            }
        } else {
            renderBukuBankRowsFallback(res.rows || []);
        }
        var $t = jQuery('#table-buku-bank-data');
        $t.find('tfoot .bk-total-debet').text(res.total_debet || '—');
        $t.find('tfoot .bk-total-kredit').text(res.total_kredit || '—');
        $t.find('tfoot .bk-total-saldo').text(res.total_saldo || '—');
        if (res.bulan_label) {
            jQuery('#bk-bulan-label').text(res.bulan_label);
        }
        bkLastBulanNs = jQuery('#bulan_ns').val() || '';
    }

    function bkEscapeHtml(text) {
        return jQuery('<div>').text(text == null ? '' : String(text)).html();
    }
    function bkBuildAjaxErrorMessage(xhr, res, defaultMsg) {
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
            lines.push('HTTP ' + xhr.status + (xhr.statusText ? ' (' + xhr.statusText + ')' : '') );
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
                    msg = 'Error database saat menyimpan ke bukubank (tabel utama).';
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
    function bkShowSaveError(title, xhr, res, defaultMsg) {
        var msg = bkBuildAjaxErrorMessage(xhr, res, defaultMsg);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: title || 'Simpan Gagal',
                html: '<div style="text-align:left;white-space:pre-wrap;font-size:14px;line-height:1.5;">' + bkEscapeHtml(msg) + '</div>',
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
        jQuery('#compare_tahun_bk').val(parseInt(parts[0], 10));
        jQuery('#compare_bulan_bk').val(parseInt(parts[1], 10));
    }

    function updateActiveTabInput() {
        /* tab state disimpan via localStorage */
    }

    function buildMainDtRowsBk(items) {
        var baseUpdate = <?php echo json_encode(site_url('Bukubank/update/')); ?>;
        var baseDelete = <?php echo json_encode(site_url('Bukubank/delete/')); ?>;
        return (items || []).map(function(it) {
            var action = '<a href="' + baseUpdate + it.id + '" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o">Ubah</i></a> '
                + '<a href="' + baseDelete + it.id + '" class="btn btn-danger btn-sm" onclick="return confirm(\'Anda Yakin Akan Menghapus Data ini ?\')"><i class="fa fa-trash-o">Hapus</i></a>';
            return [
                it.no || '',
                action,
                it.tanggal || '',
                it.bank || '',
                it.norek || '',
                it.keterangan || '',
                it.kode || '',
                it.debet_display || '',
                it.kredit_display || '',
                it.saldo_display || ''
            ];
        });
    }

    function loadBukuBankData() {
        if (!window.jQuery) return;
        var bulanVal = jQuery('#bulan_ns').val() || '';
        jQuery('#table-buku-bank-data').addClass('bk-table-loading');
        jQuery.ajax({
            url: urlAjaxList,
            type: 'POST',
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            data: {
                bulan_ns: bulanVal
            }
        }).done(function(res) {
            if (!res || !res.ok) {
                if (typeof Swal !== 'undefined' && res && res.message) {
                    Swal.fire({ icon: 'warning', title: 'Gagal Memuat Data', text: res.message });
                }
                return;
            }
            applyBukuBankListResult(res);
        }).fail(function() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: 'Gagal Memuat Data', text: 'Tidak dapat menghubungi server.' });
            }
        }).always(function() {
            jQuery('#table-buku-bank-data').removeClass('bk-table-loading');
        });
    }

    function scheduleBkMonthRefresh() {
        if (bkInitializing) return;
        var val = jQuery('#bulan_ns').val() || '';
        if (val === bkLastBulanNs && bkLastBulanNs !== '') return;
        clearTimeout(bkMonthRefreshTimer);
        bkMonthRefreshTimer = setTimeout(function() {
            refreshBukuBankFromFilters();
        }, 150);
    }

    function refreshBukuBankFromFilters() {
        saveBkLocalStorage();
        syncCompareFromBulanNs();
        loadBukuBankData();
    }

    function submitCariBukuBankForm() {
        clearTimeout(bkMonthRefreshTimer);
        bkLastBulanNs = '';
        updateActiveTabInput();
        saveBkLocalStorage();
        refreshBukuBankFromFilters();
    }

    window.addEventListener('load', function() {
        if (!window.jQuery) {
            console.error('Buku Bank: jQuery belum dimuat.');
            return;
        }
        var $ = window.jQuery;
        initBkMainDt(false);

        restoreBkLocalStorage();
        syncCompareFromBulanNs();

        bkInitializing = false;
        bkLastBulanNs = '';
        loadBukuBankData();

        jQuery('#bulan_ns').on('change input', function() {
            scheduleBkMonthRefresh();
        });
        jQuery('#btn-cari-bk').on('click', function(e) {
            e.preventDefault();
            submitCariBukuBankForm();
        });
        jQuery('#form-cari-buku-bank').on('submit', function(e) {
            e.preventDefault();
            submitCariBukuBankForm();
        });

        jQuery('#btn-buku-bank-excel').on('click', function() {
            var f = jQuery('<form method="post" target="_blank"></form>');
            f.attr('action', urlExcel);
            f.append(jQuery('<input type="hidden" name="bulan_ns">').val(jQuery('#bulan_ns').val() || ''));
            jQuery('body').append(f);
            f.submit();
            f.remove();
        });

        jQuery('#buku-bank-tabs a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
            updateActiveTabInput();
            saveBkLocalStorage();
        });

        var lastResult = null, dtMap = {}, tablesLoaded = false, csvBusy = false, csvLast = null;
        var tabelImportState = null, tabelImportBusy = false;

        function bulanKey() {
            var b = parseInt(jQuery('#compare_bulan_bk').val(), 10);
            var t = parseInt(jQuery('#compare_tahun_bk').val(), 10);
            return (b && t) ? (t + '-' + String(b).padStart(2, '0')) : '';
        }
        function parseAmt(v) {
            if (v == null || v === '') return 0;
            var s = String(v);
            if (s.indexOf('<') >= 0) s = jQuery('<div>').html(s).text();
            s = s.replace(/\./g, '').replace(',', '.').replace(/[^0-9.-]/g, '');
            var n = parseFloat(s);
            return isNaN(n) ? 0 : n;
        }
        function fmtAmtCell(v, type) {
            var n = parseAmt(v);
            if (!v || n === 0) return '<span class="text-muted">—</span>';
            return '<span class="text-amount text-amount-' + type + '">' + jQuery('<span>').text(String(v)).html() + '</span>';
        }
        function setStatus(type, html) {
            var $el = jQuery('#compare-bk-status');
            $el.removeClass('alert-info alert-success alert-danger alert-warning')
                .addClass(type === 'success' ? 'alert-success' : (type === 'danger' ? 'alert-danger' : (type === 'warning' ? 'alert-warning' : 'alert-info')))
                .html(html);
        }
        function hideTabelActions() {
            jQuery('#compare-bk-tabel-actions').addClass('d-none');
            jQuery('#compare-bk-tabel-info-body').empty();
            jQuery('#btn-compare-bk-tabel-import').prop('disabled', true);
            jQuery('#compare-bk-tabel-import-note').text('').removeClass('text-danger text-success text-muted text-warning');
        }
        function buildTabelInfoHtml(res) {
            var tbl = jQuery('#compare_tabel_bk').val() || (res && res.table) || '—';
            var bk = bulanKey() || (res && res.bulan) || '—';
            var stats = (res && res.stats) ? res.stats : {};
            var map = (res && res.map) ? res.map : {};
            var mapParts = [];
            ['tanggal', 'bank', 'norek', 'keterangan', 'kode', 'debet', 'kredit', 'saldo'].forEach(function(key) {
                if (map[key]) mapParts.push(key + ' → <code>' + jQuery('<span>').text(map[key]).html() + '</code>');
            });
            var html = '<div class="compare-info-title"><i class="fas fa-info-circle"></i> Informasi Tabel — Siap Diproses ke Buku Bank</div>';
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
            if (res.bukubank_bulan_conflict && res.conflict_warning) {
                html += '<div class="compare-info-line text-warning"><i class="fas fa-exclamation-triangle"></i> ' + jQuery('<span>').text(res.conflict_warning).html() + '</div>';
            }
            return html;
        }
        function applyTabelImportState(res) {
            tabelImportState = res || null;
            var tbl = jQuery('#compare_tabel_bk').val() || '';
            if (!tbl) { hideTabelActions(); return; }
            jQuery('#compare-bk-tabel-actions').removeClass('d-none');
            jQuery('#btn-compare-bk-tabel-detail').prop('disabled', false);
            if (!res || !res.eligible) {
                jQuery('#compare-bk-tabel-info-body').html(
                    '<div class="compare-info-title text-warning"><i class="fas fa-exclamation-triangle"></i> Tabel belum memenuhi syarat import</div>'
                    + '<div class="compare-info-line">Tabel: <strong>' + jQuery('<span>').text(tbl).html() + '</strong></div>'
                    + '<div class="compare-info-line">' + jQuery('<span>').text((res && res.message) ? res.message : 'Kolom wajib minimal: tanggal, debet atau kredit.').html() + '</div>'
                );
                jQuery('#btn-compare-bk-tabel-detail').prop('disabled', true);
                jQuery('#btn-compare-bk-tabel-import').prop('disabled', true);
                return;
            }
            jQuery('#compare-bk-tabel-info-body').html(buildTabelInfoHtml(res));
            var enabled = !!res.import_enabled;
            jQuery('#btn-compare-bk-tabel-import').prop('disabled', !enabled);
            var $note = jQuery('#compare-bk-tabel-import-note');
            $note.removeClass('text-danger text-success text-muted text-warning');
            if (enabled) {
                $note.addClass(res.bukubank_bulan_conflict ? 'text-warning' : 'text-success');
                $note.html('<i class="fas fa-check-circle"></i> ' + (res.import_message || 'Siap disimpan ke bukubank (tabel utama).'));
            } else {
                $note.addClass('text-danger').html('<i class="fas fa-exclamation-circle"></i> ' + (res.import_message || 'Tidak ada data yang bisa disimpan.'));
            }
        }
        function validateTabelForImport() {
            var tbl = jQuery('#compare_tabel_bk').val() || '';
            if (!tbl) { hideTabelActions(); toggleBtns(); return; }
            jQuery('#compare-bk-tabel-actions').removeClass('d-none');
            jQuery('#compare-bk-tabel-info-body').html('<div class="compare-info-title"><i class="fas fa-spinner fa-spin"></i> Memeriksa tabel terpilih...</div>');
            jQuery('#btn-compare-bk-tabel-detail, #btn-compare-bk-tabel-import').prop('disabled', true);
            var bk = bulanKey();
            jQuery.ajax({
                url: urlValidate,
                type: 'POST',
                dataType: 'json',
                data: { tabel: tbl, bulan: bk, bulan_num: jQuery('#compare_bulan_bk').val(), tahun: jQuery('#compare_tahun_bk').val() }
            }).done(applyTabelImportState).fail(function() {
                jQuery('#compare-bk-tabel-info-body').html('<div class="compare-info-title text-danger">Gagal memeriksa tabel</div>');
            }).always(toggleBtns);
        }
        function toggleBtns() {
            var show = bulanKey() !== '' && (jQuery('#compare_tabel_bk').val() || '') !== '';
            jQuery('#btn-compare-bk').toggleClass('d-none', !show);
            if (!show) jQuery('#btn-compare-bk-excel-all').addClass('d-none');
        }
        function buildRows(items) {
            return (items || []).map(function(it, i) {
                return [
                    i + 1,
                    it.tanggal || '',
                    it.bank || '',
                    it.norek || '',
                    it.keterangan || '',
                    it.kode || '',
                    fmtAmtCell(it.debet, 'debet'),
                    fmtAmtCell(it.kredit, 'kredit'),
                    fmtAmtCell(it.saldo, 'saldo'),
                    it.catatan ? '<span class="text-catatan">' + jQuery('<span>').text(it.catatan).html() + '</span>' : ''
                ];
            });
        }
        function buildDetailRows(items) {
            return (items || []).map(function(it) {
                return [
                    it.no || '',
                    it.tanggal || '',
                    it.bank || '',
                    it.norek || '',
                    it.keterangan || '',
                    it.kode || '',
                    fmtAmtCell(it.debet, 'debet'),
                    fmtAmtCell(it.kredit, 'kredit'),
                    fmtAmtCell(it.saldo, 'saldo')
                ];
            });
        }
        function renderTable(sel, items) {
            var $t = jQuery(sel);
            if (!$t.length) return;
            items = items || [];
            if (jQuery.fn.DataTable.isDataTable($t)) $t.DataTable().clear().destroy();
            $t.find('tbody').empty();
            $t.DataTable({
                data: buildRows(items),
                paging: true,
                searching: true,
                ordering: true,
                scrollX: true,
                scrollY: '280px',
                scrollCollapse: true,
                pageLength: 25,
                order: [[1, 'asc']],
                autoWidth: false,
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
            renderTable('#table-compare-bk-manual', res.data_manual);
            renderTable('#table-compare-bk-online', res.data_online);
            renderTable('#table-compare-bk-cocok', res.data_cocok);
            renderTable('#table-compare-bk-manual-miss', res.manual_tidak_di_online);
            renderTable('#table-compare-bk-online-miss', res.online_tidak_di_manual);
        }
        function updateInfo(res) {
            res = res || lastResult || {};
            var s = res.stats || {};
            jQuery('#compare-bk-label-bulan').text(res.bulan_label || bulanKey() || '—');
            jQuery('#compare-bk-label-tabel').text(res.table || jQuery('#compare_tabel_bk').val() || '—');
            jQuery('.compare-manual-table-name').text('(' + (res.table || jQuery('#compare_tabel_bk').val() || '—') + ')');
            jQuery('#compare-bk-count-manual').text(s.data_manual != null ? s.data_manual : '—');
            jQuery('#compare-bk-count-online').text(s.data_online != null ? s.data_online : '—');
            jQuery('#compare-bk-count-cocok').text(s.data_cocok != null ? s.data_cocok : '—');
            jQuery('#compare-bk-badge-manual').text(s.data_manual || 0);
            jQuery('#compare-bk-badge-online').text(s.data_online || 0);
            jQuery('#compare-bk-badge-cocok').text(s.data_cocok || 0);
            jQuery('#compare-bk-badge-manual-miss').text(s.manual_tidak_di_online || 0);
            jQuery('#compare-bk-badge-online-miss').text(s.online_tidak_di_manual || 0);
        }
        function loadTableList(force, selectTable) {
            if (tablesLoaded && !force) {
                if (selectTable) jQuery('#compare_tabel_bk').val(selectTable);
                validateTabelForImport();
                return;
            }
            jQuery.ajax({ url: urlList, type: 'POST', dataType: 'json' }).done(function(res) {
                if (!res || !res.ok) {
                    setStatus('danger', (res && res.message) || 'Gagal memuat daftar tabel.');
                    return;
                }
                var $sel = jQuery('#compare_tabel_bk'), cur = selectTable || $sel.val();
                $sel.find('option:not(:first)').remove();
                (res.tables || []).forEach(function(tbl) {
                    $sel.append(jQuery('<option>', { value: tbl, text: tbl }));
                });
                if (cur) $sel.val(cur);
                tablesLoaded = true;
                validateTabelForImport();
            }).fail(toggleBtns);
        }
        function runCompare() {
            var bk = bulanKey(), tbl = jQuery('#compare_tabel_bk').val() || '';
            if (!bk || !tbl) {
                alert('Pilih bulan, tahun, dan tabel database.');
                return;
            }
            if (typeof Swal !== 'undefined') {
                Swal.fire({ title: 'Memproses Compare...', html: 'Membandingkan data manual vs online bukubank', allowOutsideClick: false, didOpen: function() { Swal.showLoading(); } });
            }
            jQuery('#compare-bk-results-panel, #btn-compare-bk-excel-all').addClass('d-none');
            jQuery.ajax({
                url: urlRun,
                type: 'POST',
                dataType: 'json',
                data: { bulan: bk, bulan_num: jQuery('#compare_bulan_bk').val(), tahun: jQuery('#compare_tahun_bk').val(), tabel: tbl }
            }).done(function(res) {
                if (typeof Swal !== 'undefined') Swal.close();
                if (!res || !res.ok) {
                    setStatus('danger', (res && res.message) || 'Compare gagal.');
                    if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Compare Gagal', text: (res && res.message) || 'Compare gagal.' });
                    return;
                }
                lastResult = res;
                renderAll(res);
                updateInfo(res);
                jQuery('#compare-bk-results-panel, #btn-compare-bk-excel-all').removeClass('d-none');
                setStatus('success', '<i class="fas fa-check-circle"></i> Compare selesai.');
                if (typeof Swal !== 'undefined') Swal.fire({ icon: 'success', title: 'Compare Selesai', timer: 2000, timerProgressBar: true, showConfirmButton: false });
            }).fail(function() {
                if (typeof Swal !== 'undefined') Swal.close();
                setStatus('danger', 'Tidak dapat menghubungi server.');
            });
        }
        function exportCompareExcel() {
            var bk = bulanKey(), tbl = jQuery('#compare_tabel_bk').val() || '';
            if (!bk || !tbl) return;
            var f = jQuery('<form method="post" target="_blank"></form>');
            f.attr('action', urlCompareExcel);
            f.append(jQuery('<input type="hidden" name="bulan">').val(bk));
            f.append(jQuery('<input type="hidden" name="bulan_num">').val(jQuery('#compare_bulan_bk').val()));
            f.append(jQuery('<input type="hidden" name="tahun">').val(jQuery('#compare_tahun_bk').val()));
            f.append(jQuery('<input type="hidden" name="tabel">').val(tbl));
            jQuery('body').append(f);
            f.submit();
            f.remove();
        }
        function openTabelDetailModal() {
            var tbl = jQuery('#compare_tabel_bk').val() || '';
            var bk = bulanKey();
            if (!tbl || !bk) {
                alert('Pilih tabel dan bulan terlebih dahulu.');
                return;
            }
            jQuery('#modal-compare-bk-tabel-detail-title').text('Detail Tabel — ' + tbl);
            jQuery('#compare-bk-tabel-detail-meta').text('Memuat data tabel `' + tbl + '` bulan ' + bk + '...');
            jQuery('#modal-compare-bk-tabel-detail').modal('show');
            jQuery.ajax({ url: urlDetail, type: 'POST', dataType: 'json', data: { tabel: tbl, bulan: bk } })
            .done(function(res) {
                if (!res || !res.ok) {
                    jQuery('#compare-bk-tabel-detail-meta').text((res && res.message) || 'Gagal memuat detail tabel.');
                    return;
                }
                var items = res.rows || [];
                jQuery('#compare-bk-tabel-detail-meta').text('Tabel: ' + (res.table || tbl) + ' | Bulan: ' + (res.bulan_label || bk) + ' | Total: ' + (res.total || items.length) + ' baris');
                var $t = jQuery('#table-compare-bk-tabel-detail');
                if (jQuery.fn.DataTable.isDataTable($t)) $t.DataTable().clear().destroy();
                $t.find('tbody').empty();
                $t.DataTable({
                    data: buildDetailRows(items),
                    paging: true,
                    searching: true,
                    ordering: true,
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
                jQuery('#compare-bk-tabel-detail-meta').text('Gagal memuat detail tabel.');
            });
        }
        function exportTabelDetailExcel() {
            var tbl = jQuery('#compare_tabel_bk').val() || '';
            var bk = bulanKey();
            if (!tbl || !bk) return;
            var f = jQuery('<form method="post" target="_blank"></form>');
            f.attr('action', urlDetailExcel);
            f.append(jQuery('<input type="hidden" name="tabel">').val(tbl));
            f.append(jQuery('<input type="hidden" name="bulan">').val(bk));
            jQuery('body').append(f);
            f.submit();
            f.remove();
        }
        function reloadBukuBankAfterImport() {
            syncCompareFromBulanNs();
            loadBukuBankData();
            if (jQuery('#tab-bk-data').length && !jQuery('#tab-bk-data').hasClass('active')) {
                jQuery('#tab-bk-data').tab('show');
                updateActiveTabInput();
                saveBkLocalStorage();
            }
        }
        function showImportSuccessAlert(msg) {
            if (typeof Swal === 'undefined') {
                alert(msg || 'Data berhasil disimpan.');
                reloadBukuBankAfterImport();
                return;
            }
            Swal.fire({
                icon: 'success',
                title: 'Proses Sukses!',
                html: '<div style="font-size:15px;line-height:1.6;">' + jQuery('<div>').text(msg || 'Data berhasil disimpan ke bukubank (tabel utama).').html()
                    + '<br><span style="font-size:13px;color:#666;">Datatable Tab 1 akan dimuat ulang otomatis...</span></div>',
                confirmButtonText: 'OK',
                confirmButtonColor: '#28a745',
                timer: 2000,
                timerProgressBar: true,
                allowOutsideClick: false
            }).then(function() { reloadBukuBankAfterImport(); });
        }
        function runTabelImportRequest(tbl, bk) {
            tabelImportBusy = true;
            jQuery('#btn-compare-bk-tabel-import').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
            if (typeof Swal !== 'undefined') {
                Swal.fire({ title: 'Memproses Transfer Data...', html: 'Menyimpan data ke tabel <strong>bukubank</strong> (utama)', allowOutsideClick: false, didOpen: function() { Swal.showLoading(); } });
            }
            jQuery.ajax({ url: urlTabelImport, type: 'POST', dataType: 'json', data: { tabel: tbl, bulan: bk } })
            .done(function(res) {
                tabelImportBusy = false;
                if (typeof Swal !== 'undefined') Swal.close();
                jQuery('#btn-compare-bk-tabel-import').html('<i class="fas fa-database"></i> Proses Simpan Data ke Tabel Utama : Buku Bank');
                if (!res || !res.ok) {
                    bkShowSaveError('Simpan Gagal', null, res, 'Gagal menyimpan data ke bukubank (tabel utama).');
                    validateTabelForImport();
                    return;
                }
                setStatus('success', res.message || 'Berhasil menyimpan data.');
                validateTabelForImport();
                showImportSuccessAlert(res.message);
            }).fail(function(xhr) {
                tabelImportBusy = false;
                if (typeof Swal !== 'undefined') Swal.close();
                jQuery('#btn-compare-bk-tabel-import').html('<i class="fas fa-database"></i> Proses Simpan Data ke Tabel Utama : Buku Bank');
                validateTabelForImport();
                bkShowSaveError('Simpan Gagal', xhr, null, 'Tidak dapat menghubungi server atau respons server tidak valid.');
            });
        }
        function importTabelToBukuBesar() {
            if (tabelImportBusy) return;
            var tbl = jQuery('#compare_tabel_bk').val() || '';
            var bk = bulanKey();
            if (!tbl || !bk) {
                alert('Pilih tabel dan bulan terlebih dahulu.');
                return;
            }
            if (!tabelImportState || !tabelImportState.import_enabled) {
                alert((tabelImportState && tabelImportState.import_message) || 'Data tidak bisa disimpan.');
                return;
            }
            var doImport = function() { runTabelImportRequest(tbl, bk); };
            if (tabelImportState.bukubank_bulan_conflict && tabelImportState.conflict_warning) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Data Bulan Sudah Ada di Buku Bank',
                        html: jQuery('<div>').text(tabelImportState.conflict_warning).html() + '<br><br><strong>Lanjutkan simpan?</strong>',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, lanjutkan',
                        cancelButtonText: 'Batal'
                    }).then(function(r) { if (r.isConfirmed) doImport(); });
                    return;
                }
            }
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'question',
                    title: 'Konfirmasi Simpan',
                    text: 'Proses simpan semua data valid tabel `' + tbl + '` ke bukubank bulan ' + bk + '?',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, simpan',
                    cancelButtonText: 'Batal'
                }).then(function(r) { if (r.isConfirmed) doImport(); });
                return;
            }
            if (window.confirm('Proses simpan data ke bukubank?')) doImport();
        }
        function pad2(num) {
            num = parseInt(num, 10);
            return (num < 10 ? '0' : '') + num;
        }
        function runCsvImportAjax(file) {
            if (!file || csvBusy) return;
            csvBusy = true;
            var refBulan = parseInt(jQuery('#compare_bulan_bk').val(), 10);
            var refTahun = parseInt(jQuery('#compare_tahun_bk').val(), 10);
            if (!refBulan || refBulan < 1 || refBulan > 12) refBulan = parseInt((jQuery('#bulan_ns').val() || '').split('-')[1], 10) || (new Date()).getMonth() + 1;
            if (!refTahun || refTahun < 2000) refTahun = parseInt((jQuery('#bulan_ns').val() || '').split('-')[0], 10) || (new Date()).getFullYear();

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Memproses Generate Tabel...',
                    html: 'Upload file <strong>' + jQuery('<span>').text(file.name).html() + '</strong><br>Membuat tabel database dari CSV...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: function() { Swal.showLoading(); }
                });
            }

            var fd = new FormData();
            fd.append('csv_file', file);
            fd.append('bulan_num', refBulan);
            fd.append('tahun', refTahun);
            fd.append('bulan', refTahun + '-' + pad2(refBulan));

            jQuery.ajax({
                url: urlImport,
                type: 'POST',
                data: fd,
                processData: false,
                contentType: false,
                dataType: 'json',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).done(function(res) {
                csvBusy = false;
                jQuery('#compare_bk_csv_file').val('');
                jQuery('#compare-bk-csv-selected-name').text('Belum ada file dipilih');
                if (typeof Swal !== 'undefined') Swal.close();

                if (!res || !res.ok) {
                    var errMsg = (res && res.message) ? String(res.message).replace(/\n/g, '<br/>') : 'Import CSV gagal.';
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'error', title: 'Generate Tabel Gagal', html: errMsg, width: 640 });
                    } else {
                        alert(errMsg.replace(/<br\/?>/g, '\n'));
                    }
                    return;
                }

                csvLast = res;
                jQuery('#compare-bk-csv-filename').text(res.file || file.name || '—');
                jQuery('#compare-bk-csv-tablename').text(res.table || '—');
                jQuery('#compare-bk-csv-rowcount').text(res.rows ? (' | ' + res.rows + ' baris') : '');
                jQuery('#compare-bk-csv-colcount').text(res.columns ? (res.columns + ' kolom') : '—');
                jQuery('#compare-bk-csv-upload-info').removeClass('d-none');

                loadTableList(true, res.table);
                validateTabelForImport();
                setStatus('success', '<i class="fas fa-check-circle"></i> Tabel <strong>' + (res.table || '') + '</strong> berhasil digenerate dari CSV.');

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Generate Tabel Berhasil',
                        html: 'Tabel <strong>' + (res.table || '') + '</strong> berhasil dibuat.<br>Baris: <strong>' + (res.rows || 0) + '</strong> | Kolom: <strong>' + (res.columns || 0) + '</strong>',
                        timer: 2500,
                        timerProgressBar: true,
                        showConfirmButton: true
                    });
                }
            }).fail(function(xhr) {
                csvBusy = false;
                if (typeof Swal !== 'undefined') Swal.close();
                var msg = 'Tidak dapat menghubungi server.';
                if (xhr && xhr.responseText) {
                    try {
                        var j = JSON.parse(xhr.responseText);
                        if (j && j.message) msg = j.message;
                    } catch (eJson) {
                        if (xhr.responseText.length < 500) msg = xhr.responseText;
                    }
                }
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Generate Tabel Gagal', html: jQuery('<div>').text(msg).html() });
                } else {
                    alert(msg);
                }
            });
        }
        function importCsv(file) {
            if (!file || csvBusy) return;
            var ext = (file.name || '').split('.').pop().toLowerCase();
            if (ext !== 'csv') {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Format Salah', text: 'File harus berformat .csv' });
                } else {
                    alert('File harus berformat .csv');
                }
                return;
            }

            var tableNameGuess = (file.name || '').replace(/\.csv$/i, '').replace(/[^a-zA-Z0-9_]+/g, '_').toLowerCase();
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'info',
                    title: 'Proses Generate Tabel dari CSV',
                    html: 'File: <strong>' + jQuery('<span>').text(file.name).html() + '</strong><br>'
                        + 'Akan dibuat tabel database: <strong>' + jQuery('<span>').text(tableNameGuess).html() + '</strong><br><br>'
                        + 'Lanjutkan proses generate tabel?',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Proses',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#007bff'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        runCsvImportAjax(file);
                    } else {
                        jQuery('#compare_bk_csv_file').val('');
                        jQuery('#compare-bk-csv-selected-name').text('Belum ada file dipilih');
                    }
                });
                return;
            }
            if (window.confirm('Proses generate tabel dari file ' + file.name + '?')) {
                runCsvImportAjax(file);
            }
        }

        jQuery('#btn-bk-pick-csv').on('click', function() {
            jQuery('#compare_bk_csv_file').trigger('click');
        });
        jQuery(document).on('change', '#compare_bk_csv_file', function() {
            var f = this.files && this.files[0];
            if (f) {
                jQuery('#compare-bk-csv-selected-name').text(f.name);
                importCsv(f);
            }
        });
        jQuery('#compare_bulan_bk, #compare_tahun_bk').on('change', function() {
            validateTabelForImport();
            toggleBtns();
        });
        jQuery('#compare_tabel_bk').on('change', validateTabelForImport);
        function exportCompareSectionExcel(jenis) {
            var bk = bulanKey(), tbl = jQuery('#compare_tabel_bk').val() || '';
            if (!bk || !tbl || !jenis) return;
            var f = jQuery('<form method="post" target="_blank"></form>');
            f.attr('action', urlSectionExcel);
            f.append(jQuery('<input type="hidden" name="bulan">').val(bk));
            f.append(jQuery('<input type="hidden" name="bulan_num">').val(jQuery('#compare_bulan_bk').val()));
            f.append(jQuery('<input type="hidden" name="tahun">').val(jQuery('#compare_tahun_bk').val()));
            f.append(jQuery('<input type="hidden" name="tabel">').val(tbl));
            f.append(jQuery('<input type="hidden" name="jenis">').val(jenis));
            jQuery('body').append(f); f.submit(); f.remove();
        }

        jQuery(document).on('click', '.btn-compare-bk-section-excel', function() {
            exportCompareSectionExcel(jQuery(this).data('jenis'));
        });

        jQuery('#btn-compare-bk').on('click', runCompare);
        jQuery('#btn-compare-bk-excel-all').on('click', exportCompareExcel);
        jQuery('#btn-compare-bk-tabel-detail').on('click', openTabelDetailModal);
        jQuery('#btn-compare-bk-csv-detail').on('click', function() {
            var tbl = (csvLast && csvLast.table) ? csvLast.table : jQuery('#compare-bk-csv-tablename').text();
            if (tbl && tbl !== '—') {
                jQuery('#compare_tabel_bk').val(tbl);
                validateTabelForImport();
            }
            openTabelDetailModal();
        });
        jQuery('#btn-compare-bk-tabel-detail-excel').on('click', exportTabelDetailExcel);
        jQuery('#btn-compare-bk-tabel-import').on('click', importTabelToBukuBesar);
        jQuery('#tab-compare-bk').on('shown.bs.tab', function() { loadTableList(false); });
        if (jQuery('#tab-compare-bk').hasClass('active')) loadTableList(false);
        toggleBtns();
    });
})();
</script>
