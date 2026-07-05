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

$this->load->helper(array('laba_rugi_detail', 'laba_rugi_keterangan', 'laba_rugi_kode_akun', 'laba_rugi_unit_publish', 'laba_rugi_unit_merge', 'laba_rugi_unit_tab_sync'));
$jenis_tab = ($labarugi_view_mode === 'sederhana') ? 'sederhana' : 'rinci';
$list_unit = labarugi_unit_merge_display_units($list_unit);
$keterangan_rows = labarugi_keterangan_rows_by_tab($this, $jenis_tab);
$detail_map = isset($labarugi_detail_maps[$jenis_tab]) ? $labarugi_detail_maps[$jenis_tab] : array();
$publish_map = isset($labarugi_unit_publish_maps[$jenis_tab]) ? $labarugi_unit_publish_maps[$jenis_tab] : array();
$bb_merged_rows = labarugi_kode_akun_merged_rows($this, $tahun_neraca, $bulan_transaksi);
$ka_selected_map = labarugi_kode_akun_selected_map_by_tab($this, $jenis_tab);
$uuid_laba_rugi = isset($uuid_data_laba_rugi) ? $uuid_data_laba_rugi : '';
$save_url = site_url('Tbl_laba_rugi/save_labarugi_detail');
$sync_auto_url = site_url('Tbl_laba_rugi/save_labarugi_detail_sync_auto');
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
        <p class="text-muted small mb-0">Isi nominal per unit. Centang <strong>Publish</strong> di header unit untuk mempublikasikan kolom unit (hijau). Centang <strong>sync</strong> di kanan nilai sistem untuk salin otomatis ke input. Sel <strong>hijau cerah</strong> = input sama dengan sistem. Sel kuning = input berbeda dari sistem. <?php if ($jenis_tab === 'sederhana') { ?><strong>Tab Sederhana</strong> otomatis menyamakan input dengan tab RINCI (kecuali sub-rincian BOK/BOU). <?php } else { ?><strong>Tab RINCI</strong> otomatis menyamakan input dengan tab Sederhana untuk keterangan yang sama. <?php } ?></p>
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
                        $is_published = labarugi_unit_merge_is_published($publish_map, $unit_key);
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
                    $is_title = labarugi_keterangan_is_title_row($ket_row);
                    $is_calc = labarugi_keterangan_is_calculated_key_for_tab($ket_key, $jenis_tab);
                    $is_deep_indent = labarugi_keterangan_is_deep_indent_row_key($ket_key, $jenis_tab);
                    $is_bok_sub = labarugi_keterangan_is_bok_sub_row_key($ket_key, $jenis_tab);
                    $is_muted_input = labarugi_keterangan_is_muted_input_row_key($ket_key, $jenis_tab);
                    $input_muted_class = $is_muted_input ? ' labarugi-input-sub-muted' : '';
                    $is_rinci_derived_sederhana = ($jenis_tab === 'sederhana' && labarugi_unit_tab_sync_is_rinci_derived_key($ket_key));
                    $is_summary = $is_calc && labarugi_keterangan_row_style_for_key($ket_key, $jenis_tab) === 'summary';
                    $row_class = 'labarugi-grid-input-row';
                    if ($is_title) {
                        $row_class = 'labarugi-grid-title-row';
                    } elseif ($is_summary) {
                        $row_class = 'labarugi-grid-summary-row';
                    } elseif ($is_calc) {
                        $row_class = 'labarugi-grid-calc-row';
                    }
                ?>
                    <tr class="<?php echo $row_class; ?>">
                        <td class="labarugi-grid-col-ket sticky-col<?php echo $is_title ? ' labarugi-ket-title-row' : ($is_summary ? ' labarugi-ket-summary-row' : ''); ?>">
                            <?php if ($is_title) { ?>
                                <strong class="labarugi-ket-title-text"><?php echo htmlspecialchars($ket_label, ENT_QUOTES, 'UTF-8'); ?></strong>
                            <?php } elseif ($is_calc) { ?>
                                <strong class="<?php echo $is_summary ? 'labarugi-ket-title-text' : 'labarugi-ket-label-text labarugi-ket-label-indent'; ?>"><?php echo htmlspecialchars($ket_label, ENT_QUOTES, 'UTF-8'); ?></strong>
                            <?php } else { ?>
                            <div class="labarugi-ket-label-row">
                                <?php if ($is_bok_sub) { ?>
                                <span class="labarugi-ket-label-text labarugi-ket-label-sub-muted labarugi-ket-label-deep-indent"><?php echo htmlspecialchars($ket_label, ENT_QUOTES, 'UTF-8'); ?></span>
                                <?php } else { ?>
                                <strong class="labarugi-ket-label-text <?php echo $is_deep_indent ? 'labarugi-ket-label-deep-indent' : 'labarugi-ket-label-indent'; ?>"><?php echo htmlspecialchars($ket_label, ENT_QUOTES, 'UTF-8'); ?></strong>
                                <?php } ?>
                                <button type="button"
                                    class="btn btn-xs labarugi-btn-setting-kode-akun"
                                    data-ket-key="<?php echo htmlspecialchars($ket_key, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-ket-label="<?php echo htmlspecialchars($ket_label, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-jenis-tab="<?php echo htmlspecialchars($jenis_tab, ENT_QUOTES, 'UTF-8'); ?>"
                                    title="Setting Kode Akun <?php echo htmlspecialchars($ket_label, ENT_QUOTES, 'UTF-8'); ?>">
                                    <i class="fa fa-book"></i> Setting Kode Akun
                                </button>
                            </div>
                            <?php } ?>
                        </td>
                        <?php foreach ($list_unit as $unit_row) {
                            $unit_key = labarugi_detail_unit_key($unit_row);
                            $unit_label = isset($unit_row->nama_unit) ? $unit_row->nama_unit : $unit_key;
                            $is_published = labarugi_unit_merge_is_published($publish_map, $unit_key);
                            $unit_col_class = $is_published ? 'labarugi-unit-col-published' : 'labarugi-unit-col-unpublished';
                            if ($is_title) {
                        ?>
                            <td class="labarugi-grid-cell labarugi-grid-title-cell <?php echo $unit_col_class; ?>"
                                data-unit-key="<?php echo htmlspecialchars($unit_key, ENT_QUOTES, 'UTF-8'); ?>">
                                <span class="text-muted">&mdash;</span>
                            </td>
                        <?php
                                continue;
                            }
                            $saved = labarugi_unit_tab_sync_detail_saved_row($labarugi_detail_maps, $jenis_tab, $ket_key, $unit_key);
                            $val = '';
                            if ($is_calc) {
                                if ($saved && $saved->nominal_update !== null) {
                                    $val = labarugi_detail_format_nominal($saved->nominal_update);
                                } elseif ($saved && $saved->nominal !== null) {
                                    $val = labarugi_detail_format_nominal($saved->nominal);
                                } else {
                                    $val = labarugi_detail_format_nominal(labarugi_unit_tab_sync_detail_nominal($labarugi_detail_maps, $jenis_tab, $ket_key, $unit_key));
                                }
                            } else {
                                $sync_auto = labarugi_unit_merge_row_sync_auto($detail_map, $ket_key, $unit_key);
                                if ($is_rinci_derived_sederhana) {
                                    $sync_auto = 0;
                                }
                                $ket_kodes = isset($ka_selected_map[$ket_key]) ? $ka_selected_map[$ket_key] : array();
                                $ns_nominal = labarugi_unit_merge_kode_akun_nominal($this, $bb_merged_rows, $ket_kodes, $unit_key, $unit_label);
                                $ns_formatted = labarugi_kode_akun_format_nominal($ns_nominal);
                                if ($sync_auto === 1) {
                                    $val = $ns_formatted;
                                } elseif ($saved && $saved->nominal_update !== null) {
                                    $val = labarugi_detail_format_nominal($saved->nominal_update);
                                } elseif ($saved && $saved->nominal !== null) {
                                    $val = labarugi_detail_format_nominal($saved->nominal);
                                } else {
                                    $val = labarugi_detail_format_nominal(labarugi_unit_tab_sync_detail_nominal($labarugi_detail_maps, $jenis_tab, $ket_key, $unit_key));
                                }
                            }
                            $input_id = $grid_id . '_' . $ket_key . '_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $unit_key);
                            if ($is_calc) {
                                $calc_tier_class = labarugi_keterangan_calc_display_tier_class($ket_key);
                        ?>
                            <td class="labarugi-grid-cell labarugi-grid-calc-cell <?php echo htmlspecialchars($calc_tier_class, ENT_QUOTES, 'UTF-8'); ?> <?php echo $unit_col_class; ?>"
                                data-unit-key="<?php echo htmlspecialchars($unit_key, ENT_QUOTES, 'UTF-8'); ?>">
                                <div class="labarugi-grid-input-group labarugi-calc-input-group <?php echo htmlspecialchars($calc_tier_class, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-is-calculated="1"
                                    data-calc-key="<?php echo htmlspecialchars($ket_key, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-save-url="<?php echo htmlspecialchars($save_url, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-tahun="<?php echo (int) $tahun_neraca; ?>"
                                    data-bulan="<?php echo (int) $bulan_transaksi; ?>"
                                    data-jenis-tab="<?php echo htmlspecialchars($jenis_tab, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-nama-laba-rugi="<?php echo htmlspecialchars($ket_key, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-nama-label="<?php echo htmlspecialchars($ket_label, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-unit="<?php echo htmlspecialchars($unit_key, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-uuid="<?php echo htmlspecialchars($uuid_laba_rugi, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-sync-auto="0">
                                    <input type="tel"
                                        class="labarugi-grid-input labarugi-calc-input form-control form-control-sm <?php echo htmlspecialchars($calc_tier_class, ENT_QUOTES, 'UTF-8'); ?><?php echo $input_muted_class; ?>"
                                        id="<?php echo $input_id; ?>"
                                        pattern="[0-9(,.)]{1,22}"
                                        maxlength="22"
                                        size="18"
                                        placeholder="0,00"
                                        value="<?php echo htmlspecialchars($val, ENT_QUOTES, 'UTF-8'); ?>"
                                        readonly="readonly"
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
                        <?php
                                continue;
                            }
                            $sync_auto = labarugi_unit_merge_row_sync_auto($detail_map, $ket_key, $unit_key);
                            if ($is_rinci_derived_sederhana) {
                                $sync_auto = 0;
                            }
                            $ket_kodes = isset($ka_selected_map[$ket_key]) ? $ka_selected_map[$ket_key] : array();
                            $ns_nominal = labarugi_unit_merge_kode_akun_nominal($this, $bb_merged_rows, $ket_kodes, $unit_key, $unit_label);
                            $ns_formatted = labarugi_kode_akun_format_nominal($ns_nominal);
                        ?>
                            <td class="labarugi-grid-cell <?php echo $unit_col_class; ?><?php echo $is_rinci_derived_sederhana ? ' labarugi-grid-cell-rinci-derived' : ''; ?>"
                                data-unit-key="<?php echo htmlspecialchars($unit_key, ENT_QUOTES, 'UTF-8'); ?>">
                                <div class="labarugi-grid-input-group"
                                    data-save-url="<?php echo htmlspecialchars($save_url, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-sync-auto-url="<?php echo htmlspecialchars($sync_auto_url, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-tahun="<?php echo (int) $tahun_neraca; ?>"
                                    data-bulan="<?php echo (int) $bulan_transaksi; ?>"
                                    data-jenis-tab="<?php echo htmlspecialchars($jenis_tab, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-nama-laba-rugi="<?php echo htmlspecialchars($ket_key, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-nama-label="<?php echo htmlspecialchars($ket_label, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-unit="<?php echo htmlspecialchars($unit_key, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-uuid="<?php echo htmlspecialchars($uuid_laba_rugi, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-sync-auto="<?php echo $sync_auto === 1 ? '1' : '0'; ?>"
                                    <?php echo $is_rinci_derived_sederhana ? ' data-rinci-derived="1"' : ''; ?>>
                                    <?php if (!$is_rinci_derived_sederhana) { ?>
                                    <div class="labarugi-grid-auto-row">
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
                                            data-view-mode="unit"
                                            title="Klik lihat rincian transaksi per unit"><?php echo htmlspecialchars($ns_formatted, ENT_QUOTES, 'UTF-8'); ?></button>
                                        <label class="labarugi-grid-sync-auto-check" title="Centang: salin nilai sistem ke input teks (tersimpan)">
                                            <input type="checkbox"
                                                class="labarugi-grid-sync-auto-cb"
                                                <?php echo $sync_auto === 1 ? 'checked' : ''; ?>>
                                            <span class="labarugi-grid-sync-auto-label"><i class="fa fa-refresh"></i></span>
                                        </label>
                                    </div>
                                    <?php } else { ?>
                                    <div class="labarugi-grid-rinci-derived-hint text-muted small mb-1" title="Nilai dihitung dari tab RINCI">
                                        <i class="fa fa-link"></i> Dari tab RINCI
                                    </div>
                                    <?php } ?>
                                    <input type="tel"
                                        class="labarugi-grid-input form-control form-control-sm<?php echo $input_muted_class; ?><?php echo $is_rinci_derived_sederhana ? ' labarugi-grid-input-rinci-derived' : ''; ?>"
                                        id="<?php echo $input_id; ?>"
                                        pattern="[0-9(,.)]{1,22}"
                                        maxlength="22"
                                        size="18"
                                        placeholder="0,00"
                                        value="<?php echo htmlspecialchars($val, ENT_QUOTES, 'UTF-8'); ?>"
                                        <?php echo $is_rinci_derived_sederhana ? 'readonly="readonly" tabindex="-1"' : ''; ?>
                                        autocomplete="off" />
                                    <button type="button"
                                        class="btn btn-sm labarugi-grid-btn-save"
                                        <?php echo $is_rinci_derived_sederhana ? 'disabled' : 'disabled'; ?>
                                        title="<?php echo $is_rinci_derived_sederhana ? 'Diisi otomatis dari tab RINCI' : ('Simpan ' . htmlspecialchars($ket_label, ENT_QUOTES, 'UTF-8') . ' — ' . htmlspecialchars($unit_key, ENT_QUOTES, 'UTF-8')); ?>">
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
