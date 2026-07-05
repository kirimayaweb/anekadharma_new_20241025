<?php defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('labarugi_utama_render_title_row')) {
    function labarugi_utama_render_title_row($label, array $ctx, $key = '')
    {
        $label_esc = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
        $label_class = $key !== ''
            ? labarugi_keterangan_utama_label_classes($key)
            : 'labarugi-ket-title-text labarugi-utama-label-utama_title labarugi-utama-label-bold';
        $row_class = $key !== ''
            ? labarugi_keterangan_utama_row_class($key, 'labarugi-utama-title-row')
            : 'labarugi-utama-title-row';
        ?>
                                <tr class="<?php echo htmlspecialchars($row_class, ENT_QUOTES, 'UTF-8'); ?>">
                                    <th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400">
                                        <strong class="<?php echo htmlspecialchars($label_class, ENT_QUOTES, 'UTF-8'); ?>"><?php echo $label_esc; ?></strong>
                                    </th>
                                    <th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
                                    <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>
                                    <th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200"></th>
                                    <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>
                                </tr>
        <?php
    }
}

if (!function_exists('labarugi_utama_render_calculated_row')) {
    function labarugi_utama_render_calculated_row($field_key, $label, array $ctx)
    {
        $data_tbl_laba_rugi = isset($ctx['data_tbl_laba_rugi']) ? $ctx['data_tbl_laba_rugi'] : null;
        $sync_map = isset($ctx['labarugi_utama_sync_map']) ? $ctx['labarugi_utama_sync_map'] : array();
        $save_url = isset($ctx['labarugi_utama_save_url']) ? $ctx['labarugi_utama_save_url'] : '';
        $tahun = isset($ctx['tahun_neraca']) ? (int) $ctx['tahun_neraca'] : 0;
        $bulan = isset($ctx['bulan_transaksi']) ? (int) $ctx['bulan_transaksi'] : 0;
        $record_id = isset($ctx['labarugi_utama_record_id']) ? (int) $ctx['labarugi_utama_record_id'] : 0;
        $uuid_laba_rugi = isset($ctx['uuid_data_laba_rugi']) ? $ctx['uuid_data_laba_rugi'] : '';
        $action = isset($ctx['action']) ? $ctx['action'] : '';

        $CI = get_instance();
        $CI->load->helper(array('laba_rugi_utama', 'laba_rugi_keterangan'));

        if ($field_key === 'laba_rugi_setelah_pajak') {
            $pajak_val = labarugi_utama_field_value($data_tbl_laba_rugi, 'pajak', $sync_map);
            $field_val = 0.0;
            if ($data_tbl_laba_rugi && isset($data_tbl_laba_rugi->pajak)) {
                $field_val = (float) $data_tbl_laba_rugi->pajak;
            }
            if ($data_tbl_laba_rugi) {
                $operasional = isset($data_tbl_laba_rugi->laba_rugi_operasional) ? (float) $data_tbl_laba_rugi->laba_rugi_operasional : 0;
                $pendapatan_lain = (isset($data_tbl_laba_rugi->pendapatan_bunga_bank) ? (float) $data_tbl_laba_rugi->pendapatan_bunga_bank : 0)
                    + (isset($data_tbl_laba_rugi->pendapatan_rupa_rupa) ? (float) $data_tbl_laba_rugi->pendapatan_rupa_rupa : 0);
                $beban_lain = (isset($data_tbl_laba_rugi->beban_bunga_dan_adm_bank) ? (float) $data_tbl_laba_rugi->beban_bunga_dan_adm_bank : 0)
                    + (isset($data_tbl_laba_rugi->beban_rupa_rupa) ? (float) $data_tbl_laba_rugi->beban_rupa_rupa : 0);
                $sebelum = $operasional + $pendapatan_lain - $beban_lain;
                $field_val = $sebelum - $pajak_val;
            }
        } else {
            $field_val = labarugi_utama_field_value($data_tbl_laba_rugi, $field_key, $sync_map);
        }

        $val = number_format($field_val, 2, ',', '.');
        $input_id = 'labarugiUtama_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $field_key);
        $label_esc = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
        $field_esc = htmlspecialchars($field_key, ENT_QUOTES, 'UTF-8');
        $label_class = labarugi_keterangan_utama_label_classes($field_key);
        $row_class = labarugi_keterangan_utama_row_class($field_key, 'labarugi-utama-calc-row');
        $calc_tier_class = labarugi_keterangan_calc_display_tier_class($field_key);
        $border_class = labarugi_keterangan_utama_nominal_border_class($field_key);
        $group_classes = trim('labarugi-utama-input-group labarugi-grid-input-group labarugi-utama-field-cell labarugi-calc-input-group ' . $calc_tier_class . ' ' . $border_class);
        ?>
                                <tr class="<?php echo htmlspecialchars($row_class, ENT_QUOTES, 'UTF-8'); ?>">
                                    <th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400">
                                        <strong class="<?php echo htmlspecialchars($label_class, ENT_QUOTES, 'UTF-8'); ?>"><?php echo $label_esc; ?></strong>
                                    </th>
                                    <th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
                                    <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
                                    <th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200">
                                        <div class="<?php echo htmlspecialchars($group_classes, ENT_QUOTES, 'UTF-8'); ?>"
                                            data-is-calculated="1"
                                            data-calc-key="<?php echo $field_esc; ?>"
                                            data-save-url="<?php echo htmlspecialchars($save_url, ENT_QUOTES, 'UTF-8'); ?>"
                                            data-fallback-action="<?php echo htmlspecialchars($action . '/' . $field_key, ENT_QUOTES, 'UTF-8'); ?>"
                                            data-field-key="<?php echo $field_esc; ?>"
                                            data-tahun="<?php echo $tahun; ?>"
                                            data-bulan="<?php echo $bulan; ?>"
                                            data-jenis-tab="utama"
                                            data-nama-laba-rugi="<?php echo $field_esc; ?>"
                                            data-nama-label="<?php echo $label_esc; ?>"
                                            data-unit="<?php echo htmlspecialchars(labarugi_utama_unit_key(), ENT_QUOTES, 'UTF-8'); ?>"
                                            data-record-id="<?php echo $record_id; ?>"
                                            data-uuid="<?php echo htmlspecialchars($uuid_laba_rugi, ENT_QUOTES, 'UTF-8'); ?>"
                                            data-sync-auto="0">
                                            <input type="tel"
                                                class="labarugi-grid-input labarugi-utama-input labarugi-calc-input form-control form-control-sm <?php echo htmlspecialchars(trim($calc_tier_class . ' ' . $border_class), ENT_QUOTES, 'UTF-8'); ?>"
                                                id="<?php echo $input_id; ?>"
                                                pattern="[0-9(,.)]{1,22}"
                                                maxlength="22"
                                                value="<?php echo htmlspecialchars($val, ENT_QUOTES, 'UTF-8'); ?>"
                                                readonly="readonly"
                                                autocomplete="off" />
                                            <button type="button"
                                                class="btn btn-sm labarugi-grid-btn-save labarugi-utama-btn-save"
                                                disabled
                                                title="Simpan <?php echo $label_esc; ?>">
                                                <i class="fa fa-save"></i> Simpan
                                            </button>
                                            <span class="labarugi-grid-status"></span>
                                        </div>
                                    </th>
                                    <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>
                                </tr>
        <?php
    }
}

if (!function_exists('labarugi_utama_render_editable_row')) {
    function labarugi_utama_render_editable_row($field_key, $label, array $ctx)
    {
        $data_tbl_laba_rugi = isset($ctx['data_tbl_laba_rugi']) ? $ctx['data_tbl_laba_rugi'] : null;
        $ka_map = isset($ctx['labarugi_utama_ka_map']) ? $ctx['labarugi_utama_ka_map'] : array();
        $bb_rows = isset($ctx['labarugi_utama_bb_rows']) ? $ctx['labarugi_utama_bb_rows'] : array();
        $sync_map = isset($ctx['labarugi_utama_sync_map']) ? $ctx['labarugi_utama_sync_map'] : array();
        $save_url = isset($ctx['labarugi_utama_save_url']) ? $ctx['labarugi_utama_save_url'] : '';
        $sync_url = isset($ctx['labarugi_utama_sync_url']) ? $ctx['labarugi_utama_sync_url'] : '';
        $tahun = isset($ctx['tahun_neraca']) ? (int) $ctx['tahun_neraca'] : 0;
        $bulan = isset($ctx['bulan_transaksi']) ? (int) $ctx['bulan_transaksi'] : 0;
        $record_id = isset($ctx['labarugi_utama_record_id']) ? (int) $ctx['labarugi_utama_record_id'] : 0;
        $uuid_laba_rugi = isset($ctx['uuid_data_laba_rugi']) ? $ctx['uuid_data_laba_rugi'] : '';
        $action = isset($ctx['action']) ? $ctx['action'] : '';

        $CI = get_instance();
        $CI->load->helper(array('laba_rugi_utama', 'laba_rugi_detail', 'laba_rugi_kode_akun', 'laba_rugi_keterangan'));

        $saved = isset($sync_map[$field_key]) ? $sync_map[$field_key] : null;
        $sync_auto = labarugi_detail_row_sync_auto($saved);
        $ns_nominal = labarugi_utama_system_nominal($CI, $field_key, $ka_map, $bb_rows, $tahun, $bulan);
        $ns_formatted = labarugi_kode_akun_format_nominal($ns_nominal);
        $field_val = labarugi_utama_field_value($data_tbl_laba_rugi, $field_key, $sync_map);
        $val = number_format($field_val, 2, ',', '.');
        if ($sync_auto === 1) {
            $val = $ns_formatted;
        }
        $input_id = 'labarugiUtama_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $field_key);
        $label_esc = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
        $field_esc = htmlspecialchars($field_key, ENT_QUOTES, 'UTF-8');
        $label_class = labarugi_keterangan_utama_label_classes($field_key);
        $row_class = labarugi_keterangan_utama_row_class($field_key, 'labarugi-utama-input-row');
        $border_class = labarugi_keterangan_utama_nominal_border_class($field_key);
        $group_classes = trim('labarugi-utama-input-group labarugi-grid-input-group labarugi-utama-field-cell ' . $border_class);
        $input_classes = trim('labarugi-grid-input labarugi-utama-input form-control form-control-sm ' . $border_class);
        ?>
                                <tr class="<?php echo htmlspecialchars($row_class, ENT_QUOTES, 'UTF-8'); ?>">
                                    <th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400">
                                        <div class="labarugi-ket-label-row">
                                            <strong class="<?php echo htmlspecialchars($label_class, ENT_QUOTES, 'UTF-8'); ?>"><?php echo $label_esc; ?></strong>
                                            <button type="button"
                                                class="btn btn-xs labarugi-btn-setting-kode-akun"
                                                data-ket-key="<?php echo $field_esc; ?>"
                                                data-ket-label="<?php echo $label_esc; ?>"
                                                data-jenis-tab="utama"
                                                title="Setting Kode Akun <?php echo $label_esc; ?>">
                                                <i class="fa fa-book"></i> Setting Kode Akun
                                            </button>
                                        </div>
                                    </th>
                                    <th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
                                    <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
                                    <th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200">
                                        <div class="<?php echo htmlspecialchars($group_classes, ENT_QUOTES, 'UTF-8'); ?>"
                                            data-save-url="<?php echo htmlspecialchars($save_url, ENT_QUOTES, 'UTF-8'); ?>"
                                            data-sync-auto-url="<?php echo htmlspecialchars($sync_url, ENT_QUOTES, 'UTF-8'); ?>"
                                            data-fallback-action="<?php echo htmlspecialchars($action . '/' . $field_key, ENT_QUOTES, 'UTF-8'); ?>"
                                            data-field-key="<?php echo $field_esc; ?>"
                                            data-tahun="<?php echo $tahun; ?>"
                                            data-bulan="<?php echo $bulan; ?>"
                                            data-jenis-tab="utama"
                                            data-nama-laba-rugi="<?php echo $field_esc; ?>"
                                            data-nama-label="<?php echo $label_esc; ?>"
                                            data-unit="<?php echo htmlspecialchars(labarugi_utama_unit_key(), ENT_QUOTES, 'UTF-8'); ?>"
                                            data-record-id="<?php echo $record_id; ?>"
                                            data-uuid="<?php echo htmlspecialchars($uuid_laba_rugi, ENT_QUOTES, 'UTF-8'); ?>"
                                            data-sync-auto="<?php echo $sync_auto === 1 ? '1' : '0'; ?>">
                                            <div class="labarugi-grid-auto-row">
                                                <button type="button"
                                                    class="labarugi-ns-nominal-utama labarugi-grid-ns-nominal btn btn-link p-0"
                                                    data-ket-key="<?php echo $field_esc; ?>"
                                                    data-ket-label="<?php echo $label_esc; ?>"
                                                    data-jenis-tab="utama"
                                                    data-view-mode="utama"
                                                    data-tahun="<?php echo $tahun; ?>"
                                                    data-bulan="<?php echo $bulan; ?>"
                                                    data-nominal="<?php echo htmlspecialchars((string) $ns_nominal, ENT_QUOTES, 'UTF-8'); ?>"
                                                    title="Klik lihat rincian transaksi"><?php echo htmlspecialchars($ns_formatted, ENT_QUOTES, 'UTF-8'); ?></button>
                                                <label class="labarugi-grid-sync-auto-check" title="Centang: salin nilai sistem ke input teks (tersimpan ke data cetak)">
                                                    <input type="checkbox"
                                                        class="labarugi-grid-sync-auto-cb"
                                                        <?php echo $sync_auto === 1 ? 'checked' : ''; ?>>
                                                    <span class="labarugi-grid-sync-auto-label"><i class="fa fa-refresh"></i></span>
                                                </label>
                                            </div>
                                            <input type="tel"
                                                class="<?php echo htmlspecialchars($input_classes, ENT_QUOTES, 'UTF-8'); ?>"
                                                id="<?php echo $input_id; ?>"
                                                pattern="[0-9(,.)]{1,22}"
                                                maxlength="22"
                                                value="<?php echo htmlspecialchars($val, ENT_QUOTES, 'UTF-8'); ?>"
                                                style="font-size:1.1vw;font-weight:bold;text-align:right;color:black;"
                                                autocomplete="off" />
                                            <button type="button"
                                                class="btn btn-sm labarugi-grid-btn-save labarugi-utama-btn-save"
                                                disabled
                                                title="Simpan <?php echo $label_esc; ?>">
                                                <i class="fa fa-save"></i> Simpan
                                            </button>
                                            <span class="labarugi-grid-status"></span>
                                        </div>
                                    </th>
                                    <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>
                                </tr>
        <?php
    }
}
