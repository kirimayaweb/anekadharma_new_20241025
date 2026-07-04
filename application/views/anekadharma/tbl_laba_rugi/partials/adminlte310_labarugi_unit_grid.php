<?php defined('BASEPATH') OR exit('No direct script access allowed');
if (!isset($labarugi_view_mode)) { $labarugi_view_mode = 'rinci'; }
if (!isset($labarugi_tab_key)) { $labarugi_tab_key = $labarugi_view_mode; }
if (!isset($list_unit)) { $list_unit = array(); }
if (!isset($labarugi_detail_maps)) { $labarugi_detail_maps = array(); }
if (!isset($labarugi_unit_publish_maps)) { $labarugi_unit_publish_maps = array(); }
if (!isset($uuid_data_laba_rugi)) { $uuid_data_laba_rugi = ''; }
if (!isset($tahun_neraca)) { $tahun_neraca = (int) date('Y'); }
if (!isset($bulan_transaksi)) { $bulan_transaksi = (int) date('m'); }

if ($labarugi_view_mode === 'utama' || empty($list_unit)) {
    return;
}

$this->load->helper(array('laba_rugi_detail', 'laba_rugi_keterangan', 'laba_rugi_kode_akun', 'laba_rugi_unit_publish'));
$jenis_tab = ($labarugi_view_mode === 'sederhana') ? 'sederhana' : 'rinci';
$keterangan_rows = labarugi_keterangan_rows_by_tab($this, $jenis_tab);
$detail_map = isset($labarugi_detail_maps[$jenis_tab]) ? $labarugi_detail_maps[$jenis_tab] : array();
$publish_map = isset($labarugi_unit_publish_maps[$jenis_tab]) ? $labarugi_unit_publish_maps[$jenis_tab] : array();
$bb_merged_rows = labarugi_kode_akun_merged_rows($this, $tahun_neraca, $bulan_transaksi);
$ka_selected_map = labarugi_kode_akun_selected_map_by_tab($this, $jenis_tab);
$uuid_laba_rugi = isset($uuid_data_laba_rugi) ? $uuid_data_laba_rugi : '';
$save_url = site_url('Tbl_laba_rugi/save_labarugi_detail');
$publish_url = site_url('Tbl_laba_rugi/save_labarugi_unit_publish');
$grid_id = 'labarugiGrid_' . htmlspecialchars($labarugi_tab_key, ENT_QUOTES, 'UTF-8');
?>
<div class="labarugi-unit-grid-wrap"
    data-tahun="<?php echo (int) $tahun_neraca; ?>"
    data-bulan="<?php echo (int) $bulan_transaksi; ?>"
    data-jenis-tab="<?php echo htmlspecialchars($jenis_tab, ENT_QUOTES, 'UTF-8'); ?>"
    data-publish-url="<?php echo htmlspecialchars($publish_url, ENT_QUOTES, 'UTF-8'); ?>">
    <div class="labarugi-unit-grid-toolbar">
        <button type="button"
            class="btn btn-sm btn-setting-keterangan labarugi-btn-setting-keterangan"
            data-jenis-tab="<?php echo htmlspecialchars($jenis_tab, ENT_QUOTES, 'UTF-8'); ?>"
            data-tab-label="<?php echo ($jenis_tab === 'sederhana') ? 'Sederhana' : 'Rinci'; ?>">
            <i class="fa fa-cog"></i> Setting Keterangan
        </button>
        <?php if (!empty($bulan_transaksi) && (int) $bulan_transaksi > 0) { ?>
        <a href="<?php echo site_url('Tbl_laba_rugi/labarugi_print_unit/' . (int) $tahun_neraca . '/' . (int) $bulan_transaksi . '/' . $jenis_tab); ?>"
            class="btn btn-sm btn-info"
            target="_blank"
            title="Cetak hanya unit yang dicentang Publish">
            <i class="fa fa-print"></i> Cetak Per Unit (Publish)
        </a>
        <?php } ?>
    </div>
    <div class="labarugi-unit-grid-header">
        <h5 class="labarugi-per-unit-heading mb-1">
            <i class="fa fa-th"></i>
            Input Laba Rugi Per Unit — <?php echo ($jenis_tab === 'sederhana') ? 'Sederhana' : 'Rinci'; ?>
        </h5>
        <p class="text-muted small mb-0">Isi nominal per unit. Centang <strong>Publish</strong> di header unit untuk mempublikasikan kolom unit (hijau). Kolom tanpa centang = tidak publish (merah). Sel kuning = nilai input berbeda dari sistem.</p>
    </div>

    <p class="labarugi-unit-grid-scroll-hint"><i class="fa fa-arrows-h"></i> Scroll horizontal atas atau bawah untuk melihat unit lainnya — kolom Keterangan tetap diam</p>

    <div class="labarugi-unit-grid-scroll-shell">
        <div class="labarugi-unit-grid-scroll-top" aria-hidden="true" tabindex="-1">
            <div class="labarugi-unit-grid-scroll-top-inner"></div>
        </div>
        <div class="labarugi-unit-grid-scroll labarugi-unit-grid-scroll-main">
        <table class="labarugi-unit-grid-table" id="<?php echo $grid_id; ?>">
            <thead>
                <tr>
                    <th class="labarugi-grid-col-ket sticky-col">Keterangan</th>
                    <?php foreach ($list_unit as $unit_row) {
                        $unit_key = labarugi_detail_unit_key($unit_row);
                        $unit_label = isset($unit_row->nama_unit) ? $unit_row->nama_unit : $unit_key;
                        $is_published = labarugi_unit_publish_is_published($publish_map, $unit_key);
                        $unit_col_class = $is_published ? 'labarugi-unit-col-published' : 'labarugi-unit-col-unpublished';
                    ?>
                        <th class="labarugi-grid-col-unit <?php echo $unit_col_class; ?>"
                            data-unit-key="<?php echo htmlspecialchars($unit_key, ENT_QUOTES, 'UTF-8'); ?>"
                            title="<?php echo htmlspecialchars($unit_label, ENT_QUOTES, 'UTF-8'); ?>">
                            <div class="labarugi-grid-unit-head">
                                <div class="labarugi-grid-unit-head-text">
                                    <span class="labarugi-grid-unit-kode"><?php echo htmlspecialchars($unit_key, ENT_QUOTES, 'UTF-8'); ?></span>
                                    <span class="labarugi-grid-unit-nama"><?php echo htmlspecialchars($unit_label, ENT_QUOTES, 'UTF-8'); ?></span>
                                </div>
                                <label class="labarugi-unit-publish-check" title="Publish unit ini">
                                    <input type="checkbox"
                                        class="labarugi-unit-publish-cb"
                                        data-unit="<?php echo htmlspecialchars($unit_key, ENT_QUOTES, 'UTF-8'); ?>"
                                        <?php echo $is_published ? 'checked' : ''; ?>>
                                    <span class="labarugi-unit-publish-label">Publish</span>
                                </label>
                            </div>
                        </th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($keterangan_rows as $ket_row) {
                    $ket_key = $ket_row['key'];
                    $ket_label = $ket_row['label'];
                ?>
                    <tr>
                        <td class="labarugi-grid-col-ket sticky-col">
                            <div class="labarugi-ket-label-row">
                                <strong class="labarugi-ket-label-text"><?php echo htmlspecialchars($ket_label, ENT_QUOTES, 'UTF-8'); ?></strong>
                                <button type="button"
                                    class="btn btn-xs labarugi-btn-setting-kode-akun"
                                    data-ket-key="<?php echo htmlspecialchars($ket_key, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-ket-label="<?php echo htmlspecialchars($ket_label, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-jenis-tab="<?php echo htmlspecialchars($jenis_tab, ENT_QUOTES, 'UTF-8'); ?>"
                                    title="Setting Kode Akun <?php echo htmlspecialchars($ket_label, ENT_QUOTES, 'UTF-8'); ?>">
                                    <i class="fa fa-book"></i> Setting Kode Akun
                                </button>
                            </div>
                        </td>
                        <?php foreach ($list_unit as $unit_row) {
                            $unit_key = labarugi_detail_unit_key($unit_row);
                            $unit_label = isset($unit_row->nama_unit) ? $unit_row->nama_unit : $unit_key;
                            $is_published = labarugi_unit_publish_is_published($publish_map, $unit_key);
                            $unit_col_class = $is_published ? 'labarugi-unit-col-published' : 'labarugi-unit-col-unpublished';
                            $saved = null;
                            if (isset($detail_map[$ket_key][$unit_key])) {
                                $saved = $detail_map[$ket_key][$unit_key];
                            }
                            $val = '';
                            if ($saved && $saved->nominal_update !== null) {
                                $val = labarugi_detail_format_nominal($saved->nominal_update);
                            } elseif ($saved && $saved->nominal !== null) {
                                $val = labarugi_detail_format_nominal($saved->nominal);
                            }
                            $input_id = $grid_id . '_' . $ket_key . '_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $unit_key);
                            $ket_kodes = isset($ka_selected_map[$ket_key]) ? $ka_selected_map[$ket_key] : array();
                            $ns_nominal = labarugi_kode_akun_unit_nominal_from_data($bb_merged_rows, $ket_kodes, $unit_key, $unit_label);
                            $ns_formatted = labarugi_kode_akun_format_nominal($ns_nominal);
                        ?>
                            <td class="labarugi-grid-cell <?php echo $unit_col_class; ?>"
                                data-unit-key="<?php echo htmlspecialchars($unit_key, ENT_QUOTES, 'UTF-8'); ?>">
                                <div class="labarugi-grid-input-group"
                                    data-save-url="<?php echo htmlspecialchars($save_url, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-tahun="<?php echo (int) $tahun_neraca; ?>"
                                    data-bulan="<?php echo (int) $bulan_transaksi; ?>"
                                    data-jenis-tab="<?php echo htmlspecialchars($jenis_tab, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-nama-laba-rugi="<?php echo htmlspecialchars($ket_key, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-nama-label="<?php echo htmlspecialchars($ket_label, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-unit="<?php echo htmlspecialchars($unit_key, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-uuid="<?php echo htmlspecialchars($uuid_laba_rugi, ENT_QUOTES, 'UTF-8'); ?>">
                                    <button type="button"
                                        class="labarugi-grid-ns-nominal btn btn-link p-0"
                                        data-ket-key="<?php echo htmlspecialchars($ket_key, ENT_QUOTES, 'UTF-8'); ?>"
                                        data-ket-label="<?php echo htmlspecialchars($ket_label, ENT_QUOTES, 'UTF-8'); ?>"
                                        data-jenis-tab="<?php echo htmlspecialchars($jenis_tab, ENT_QUOTES, 'UTF-8'); ?>"
                                        data-unit="<?php echo htmlspecialchars($unit_key, ENT_QUOTES, 'UTF-8'); ?>"
                                        data-unit-label="<?php echo htmlspecialchars($unit_label, ENT_QUOTES, 'UTF-8'); ?>"
                                        data-tahun="<?php echo (int) $tahun_neraca; ?>"
                                        data-bulan="<?php echo (int) $bulan_transaksi; ?>"
                                        data-nominal="<?php echo htmlspecialchars((string) $ns_nominal, ENT_QUOTES, 'UTF-8'); ?>"
                                        title="Klik lihat rincian transaksi per unit"><?php echo htmlspecialchars($ns_formatted, ENT_QUOTES, 'UTF-8'); ?></button>
                                    <input type="tel"
                                        class="labarugi-grid-input form-control form-control-sm"
                                        id="<?php echo $input_id; ?>"
                                        pattern="[0-9(,.)]{1,22}"
                                        maxlength="22"
                                        size="18"
                                        placeholder="0,00"
                                        value="<?php echo htmlspecialchars($val, ENT_QUOTES, 'UTF-8'); ?>"
                                        autocomplete="off" />
                                    <button type="button"
                                        class="btn btn-sm labarugi-grid-btn-save"
                                        disabled
                                        title="Simpan <?php echo htmlspecialchars($ket_label, ENT_QUOTES, 'UTF-8'); ?> — <?php echo htmlspecialchars($unit_key, ENT_QUOTES, 'UTF-8'); ?>">
                                        <i class="fa fa-save"></i> Simpan
                                    </button>
                                    <span class="labarugi-grid-status"></span>
                                </div>
                            </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
