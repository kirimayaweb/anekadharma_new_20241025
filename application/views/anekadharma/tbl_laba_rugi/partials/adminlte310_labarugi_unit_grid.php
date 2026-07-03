<?php defined('BASEPATH') OR exit('No direct script access allowed');
if (!isset($labarugi_view_mode)) { $labarugi_view_mode = 'rinci'; }
if (!isset($labarugi_tab_key)) { $labarugi_tab_key = $labarugi_view_mode; }
if (!isset($list_unit)) { $list_unit = array(); }
if (!isset($labarugi_detail_maps)) { $labarugi_detail_maps = array(); }
if (!isset($uuid_data_laba_rugi)) { $uuid_data_laba_rugi = ''; }
if (!isset($tahun_neraca)) { $tahun_neraca = (int) date('Y'); }
if (!isset($bulan_transaksi)) { $bulan_transaksi = (int) date('m'); }

if ($labarugi_view_mode === 'utama' || empty($list_unit)) {
    return;
}

$this->load->helper(array('laba_rugi_detail', 'laba_rugi_keterangan', 'laba_rugi_kode_akun'));
$jenis_tab = ($labarugi_view_mode === 'sederhana') ? 'sederhana' : 'rinci';
$keterangan_rows = labarugi_keterangan_rows_by_tab($this, $jenis_tab);
$detail_map = isset($labarugi_detail_maps[$jenis_tab]) ? $labarugi_detail_maps[$jenis_tab] : array();
$bb_merged_rows = labarugi_kode_akun_merged_rows($this, $tahun_neraca, $bulan_transaksi);
$ka_selected_map = labarugi_kode_akun_selected_map_by_tab($this, $jenis_tab);
$uuid_laba_rugi = isset($uuid_data_laba_rugi) ? $uuid_data_laba_rugi : '';
$save_url = site_url('Tbl_laba_rugi/save_labarugi_detail');
$grid_id = 'labarugiGrid_' . htmlspecialchars($labarugi_tab_key, ENT_QUOTES, 'UTF-8');
?>
<div class="labarugi-unit-grid-wrap" data-tahun="<?php echo (int) $tahun_neraca; ?>" data-bulan="<?php echo (int) $bulan_transaksi; ?>">
    <div class="labarugi-unit-grid-toolbar">
        <button type="button"
            class="btn btn-sm btn-setting-keterangan labarugi-btn-setting-keterangan"
            data-jenis-tab="<?php echo htmlspecialchars($jenis_tab, ENT_QUOTES, 'UTF-8'); ?>"
            data-tab-label="<?php echo ($jenis_tab === 'sederhana') ? 'Sederhana' : 'Rinci'; ?>">
            <i class="fa fa-cog"></i> Setting Keterangan
        </button>
    </div>
    <div class="labarugi-unit-grid-header">
        <h5 class="labarugi-per-unit-heading mb-1">
            <i class="fa fa-th"></i>
            Input Laba Rugi Per Unit — <?php echo ($jenis_tab === 'sederhana') ? 'Sederhana' : 'Rinci'; ?>
        </h5>
        <p class="text-muted small mb-0">Isi nominal per unit. Tombol Simpan aktif jika kolom terisi.</p>
    </div>

    <p class="labarugi-unit-grid-scroll-hint"><i class="fa fa-arrows-h"></i> Scroll horizontal untuk melihat unit lainnya — semua baris keterangan tampil penuh, kolom Keterangan tetap diam</p>

    <div class="labarugi-unit-grid-scroll">
        <table class="labarugi-unit-grid-table" id="<?php echo $grid_id; ?>">
            <thead>
                <tr>
                    <th class="labarugi-grid-col-ket sticky-col">Keterangan</th>
                    <?php foreach ($list_unit as $unit_row) {
                        $unit_key = labarugi_detail_unit_key($unit_row);
                        $unit_label = isset($unit_row->nama_unit) ? $unit_row->nama_unit : $unit_key;
                    ?>
                        <th class="labarugi-grid-col-unit" title="<?php echo htmlspecialchars($unit_label, ENT_QUOTES, 'UTF-8'); ?>">
                            <span class="labarugi-grid-unit-kode"><?php echo htmlspecialchars($unit_key, ENT_QUOTES, 'UTF-8'); ?></span>
                            <span class="labarugi-grid-unit-nama"><?php echo htmlspecialchars($unit_label, ENT_QUOTES, 'UTF-8'); ?></span>
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
                            <td class="labarugi-grid-cell">
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
