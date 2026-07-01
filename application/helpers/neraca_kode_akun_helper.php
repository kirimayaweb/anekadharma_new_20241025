<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function neraca_field_labels()
{
    return array(
        'kas' => 'Kas',
        'bank' => 'Bank',
        'piutang_usaha' => 'Piutang Usaha',
        'persediaan' => 'Persediaan',
        'uang_muka_pajak' => 'Uang Muka Pajak',
        'aktiva_tetap_berwujud' => 'Aktiva Tetap Berwujud',
        'akumulasi_depresiasi_atb' => 'Akumulasi Depresiasi ATB',
        'piutang_non_usaha_pihak_ketiga' => 'Piutang Non Usaha Pihak Ketiga',
        'piutang_non_usaha_radio' => 'Piutang Non Usaha Radio',
        'piutang_non_usaha' => 'Piutang Non Usaha',
        'utang_usaha' => 'Utang Usaha',
        'utang_pajak' => 'Utang Pajak',
        'utang_lain_lain' => 'Utang Lain-lain',
        'utang_afiliasi' => 'Utang Afiliasi',
        'modal_dasar_dan_penyertaan' => 'Modal Dasar dan Penyertaan',
        'cadangan_umum' => 'Cadangan Umum',
        'laba_bumd_pad' => 'Laba BUMD PAD',
        'ljpj_taman_gedung_kesenian_gabusan' => 'LJPJ Taman Gedung Kesenian Gabusan',
        'ljpj_kompleks_gedung_kesenian' => 'LJPJ Kompleks Gedung Kesenian',
        'ljpj_radio' => 'LJPJ Radio',
        'laba_rugi_tahun_lalu' => 'Laba Rugi Tahun Lalu',
        'ljpj_kerjasama_operasi_apotek_dharma_usaha' => 'LJPJ Kerjasama Operasi Apotek Dharma Usaha',
        'laba_rugi_tahun_berjalan' => 'Laba Rugi Tahun Berjalan',
        'ljpj_peternakan' => 'LJPJ Peternakan',
        'ljpj_kerjasama_adwm' => 'LJPJ Kerjasama ADWM',
        'ljpj_kerjasama_pdu_cabean_panggungharjo' => 'LJPJ Kerjasama PDU Cabean Panggung Harjo',
    );
}

function neraca_get_field_label($field_neraca)
{
    $labels = neraca_field_labels();
    if (isset($labels[$field_neraca])) {
        return $labels[$field_neraca];
    }
    return ucwords(str_replace('_', ' ', $field_neraca));
}

/**
 * HTML kolom keterangan neraca: teks label + tombol Setting Kode Akun (inline).
 */
function neraca_render_label_keterangan($field_neraca, $label_text = null)
{
    $field_neraca = trim((string) $field_neraca);
    $canonical_label = neraca_get_field_label($field_neraca);
    $display_label = ($label_text !== null && $label_text !== '')
        ? (string) $label_text
        : $canonical_label;
    $btn_label = 'Setting Kode Akun ' . $canonical_label;

    return '<div class="neraca-label-wrap">'
        . '<span class="neraca-label-text">' . htmlspecialchars($display_label, ENT_QUOTES, 'UTF-8') . '</span>'
        . '<span class="neraca-label-setting">'
        . '<button type="button" class="btn btn-warning btn-xs btn-neraca-get-kode-akun-form" data-field-neraca="'
        . htmlspecialchars($field_neraca, ENT_QUOTES, 'UTF-8') . '" onclick="return (window.neracaOpenSettingKodeAkun ? neracaOpenSettingKodeAkun(this) : false);">'
        . '<i class="fa fa-cog"></i> ' . htmlspecialchars($btn_label, ENT_QUOTES, 'UTF-8')
        . '</button></span></div>';
}

function neraca_system_total_value($field_neraca, $system_totals)
{
    $field_neraca = trim((string) $field_neraca);
    if (!is_array($system_totals) || $field_neraca === '') {
        return 0.0;
    }
    return isset($system_totals[$field_neraca]) ? (float) $system_totals[$field_neraca] : 0.0;
}

function neraca_render_calc_legacy($field_neraca, $system_totals)
{
    $total = neraca_system_total_value($field_neraca, $system_totals);
    return '<div class="neraca-calc-legacy" style="display:none;">'
        . htmlspecialchars((string) $total, ENT_QUOTES, 'UTF-8')
        . '</div>';
}

function neraca_field_input_value($data_row, $field_neraca)
{
    $field_neraca = trim((string) $field_neraca);
    if (!is_object($data_row) || $field_neraca === '' || !isset($data_row->$field_neraca)) {
        return '0,00';
    }
    return number_format((float) $data_row->$field_neraca, 2, ',', '.');
}

function neraca_prepare_form_row_defaults()
{
    $row = new stdClass();
    foreach (array_keys(neraca_field_labels()) as $field_neraca) {
        $row->$field_neraca = 0;
    }
    return $row;
}

function neraca_field_fallback_groups()
{
    return array(
        'kas' => null,
        'bank' => 'BANK',
        'utang_usaha' => 'utang_usaha',
        'utang_pajak' => 'utang_usaha',
        'piutang_usaha' => 'PIUTANGUSAHA',
        'utang_lain_lain' => 'utang_lain_lain',
        'piutang_non_usaha' => 'PIUTANGUSAHA',
        'persediaan' => 'PERSEDIAAN',
        'uang_muka_pajak' => 'uang_muka_pajak',
        'aktiva_tetap_berwujud' => 'aktiva_tetap_berwujud',
        'utang_afiliasi' => 'utang_afiliasi',
        'akumulasi_depresiasi_atb' => 'akumulasi_depresiasi_atb',
        'piutang_non_usaha_pihak_ketiga' => 'PIUTANGNONUSAHAPIHAKKETIGA',
        'modal_dasar_dan_penyertaan' => 'modal_dasar_dan_penyertaan',
        'piutang_non_usaha_radio' => 'PIUTANGNONUSAHARADIO',
        'cadangan_umum' => 'cadangan_umum',
        'ljpj_taman_gedung_kesenian_gabusan' => 'ljpj_taman_gedung_kesenian_gabusan',
        'laba_bumd_pad' => 'laba_bumd_pad',
        'ljpj_kompleks_gedung_kesenian' => 'ljpj_kompleks_gedung_kesenian',
        'ljpj_radio' => 'ljpj_radio',
        'laba_rugi_tahun_lalu' => 'laba_rugi_tahun_lalu',
        'ljpj_kerjasama_operasi_apotek_dharma_usaha' => 'ljpj_kerjasama_operasi_apotek_dharma_usaha',
        'laba_rugi_tahun_berjalan' => 'laba_rugi_tahun_berjalan',
        'ljpj_peternakan' => 'ljpj_peternakan',
        'ljpj_kerjasama_adwm' => 'ljpj_kerjasama_adwm',
        'ljpj_kerjasama_pdu_cabean_panggungharjo' => 'ljpj_kerjasama_pdu_cabean_panggungharjo',
    );
}

function neraca_get_kode_akun_list($CI, $field_neraca, $fallback_group = null, $settings_by_field = null)
{
    $field_neraca = trim((string) $field_neraca);
    $kodes = array();

    if (is_array($settings_by_field) && isset($settings_by_field[$field_neraca])) {
        $kodes = $settings_by_field[$field_neraca];
    } else {
        $CI->load->model('Sys_setting_neraca_kode_akun_model');
        $kodes = $CI->Sys_setting_neraca_kode_akun_model->get_kode_akun_by_field($field_neraca);
    }

    if (!empty($kodes)) {
        return $kodes;
    }

    if ($field_neraca === 'kas') {
        return array('11101');
    }

    if ($fallback_group === null) {
        $groups = neraca_field_fallback_groups();
        $fallback_group = isset($groups[$field_neraca]) ? $groups[$field_neraca] : null;
    }

    if ($fallback_group) {
        $rows = $CI->db->where('group', $fallback_group)->order_by('kode_akun', 'ASC')->get('sys_kode_akun')->result();
        $kodes = array();
        foreach ($rows as $row) {
            $kodes[] = $row->kode_akun;
        }
        return $kodes;
    }

    return array();
}

function neraca_preload_field_kode_lists($CI)
{
    $map = array();
    if (!$CI->db->table_exists('sys_setting_neraca_kode_akun')) {
        return $map;
    }

    $rows = $CI->db
        ->select('field_neraca, kode_akun')
        ->order_by('field_neraca', 'ASC')
        ->order_by('kode_akun', 'ASC')
        ->get('sys_setting_neraca_kode_akun')
        ->result();

    foreach ($rows as $row) {
        $field = (string) $row->field_neraca;
        if (!isset($map[$field])) {
            $map[$field] = array();
        }
        $map[$field][] = $row->kode_akun;
    }

    return $map;
}

function neraca_calc_jurnal_kas($CI, $field_neraca, $tahun_neraca, $bulan_transaksi = 0, $fallback_group = null, $rows_by_kode = null, $settings_by_field = null)
{
    return neraca_calc_from_neraca_saldo($CI, $field_neraca, $tahun_neraca, $bulan_transaksi, $fallback_group, $rows_by_kode, $settings_by_field);
}

function neraca_calc_from_neraca_saldo($CI, $field_neraca, $tahun_neraca, $bulan_transaksi = 0, $fallback_group = null, $rows_by_kode = null, $settings_by_field = null)
{
    $kode_list = neraca_get_kode_akun_list($CI, $field_neraca, $fallback_group, $settings_by_field);

    if ($rows_by_kode === null) {
        $CI->load->helper('neraca_saldo_list');
        $month = (int) $bulan_transaksi;
        if ($month < 1 || $month > 12) {
            $month = 12;
        }
        $year = (int) $tahun_neraca;
        if ($year < 2000) {
            $year = (int) date('Y');
        }

        $ns_data = neraca_saldo_compute_list_data($CI, $month, $year);
        $rows_by_kode = array();
        foreach ($ns_data['rows'] as $row) {
            $rows_by_kode[(string) $row['kode_akun']] = $row;
        }
    }

    $total_debet = 0;
    $total_kredit = 0;
    foreach ($kode_list as $kode_akun) {
        $key = (string) $kode_akun;
        if (!isset($rows_by_kode[$key])) {
            continue;
        }
        $total_debet += (float) $rows_by_kode[$key]['ns_debet_raw'];
        $total_kredit += (float) $rows_by_kode[$key]['ns_kredit_raw'];
    }

    return array(
        'debet' => $total_debet,
        'kredit' => $total_kredit,
    );
}

function neraca_is_pasiva_field($field_neraca)
{
    $pasiva_fields = array(
        'utang_usaha', 'utang_pajak', 'utang_lain_lain', 'utang_afiliasi',
        'modal_dasar_dan_penyertaan', 'cadangan_umum', 'laba_bumd_pad',
        'ljpj_taman_gedung_kesenian_gabusan', 'ljpj_kompleks_gedung_kesenian',
        'ljpj_radio', 'laba_rugi_tahun_lalu', 'ljpj_kerjasama_operasi_apotek_dharma_usaha',
        'laba_rugi_tahun_berjalan', 'ljpj_peternakan', 'ljpj_kerjasama_adwm',
        'ljpj_kerjasama_pdu_cabean_panggungharjo',
    );
    return in_array($field_neraca, $pasiva_fields, true);
}

function neraca_calc_system_nominal($CI, $field_neraca, $tahun_neraca, $bulan_transaksi = 0, $fallback_group = null, $rows_by_kode = null, $settings_by_field = null)
{
    $groups = neraca_field_fallback_groups();
    if ($fallback_group === null && isset($groups[$field_neraca])) {
        $fallback_group = $groups[$field_neraca];
    }

    $saldo = neraca_calc_from_neraca_saldo($CI, $field_neraca, $tahun_neraca, $bulan_transaksi, $fallback_group, $rows_by_kode, $settings_by_field);

    if (neraca_is_pasiva_field($field_neraca)) {
        return (float) $saldo['kredit'] - (float) $saldo['debet'];
    }

    return (float) $saldo['debet'] - (float) $saldo['kredit'];
}

function neraca_compute_all_system_totals($CI, $tahun_neraca, $bulan_transaksi = 0)
{
    $saldo_map = neraca_get_neraca_saldo_map($CI, $tahun_neraca, $bulan_transaksi);
    $rows_by_kode = $saldo_map['rows_by_kode'];
    $settings_by_field = neraca_preload_field_kode_lists($CI);

    $totals = array();
    foreach (array_keys(neraca_field_labels()) as $field_neraca) {
        $totals[$field_neraca] = neraca_calc_system_nominal(
            $CI,
            $field_neraca,
            $saldo_map['year'],
            $saldo_map['month'],
            null,
            $rows_by_kode,
            $settings_by_field
        );
    }

    return $totals;
}

function neraca_nominal_match_class($system_total, $manual_total)
{
    $system = round((float) $system_total, 2);
    $manual = round((float) $manual_total, 2);
    if (abs($system - $manual) < 0.01) {
        return 'neraca-nominal-match';
    }
    return 'neraca-nominal-mismatch';
}

function neraca_get_neraca_saldo_map($CI, $tahun_neraca, $bulan_transaksi = 0)
{
    $CI->load->helper('neraca_saldo_list');

    $month = (int) $bulan_transaksi;
    if ($month < 1 || $month > 12) {
        $month = 12;
    }
    $year = (int) $tahun_neraca;
    if ($year < 2000) {
        $year = (int) date('Y');
    }

    $ns_data = neraca_saldo_compute_list_data($CI, $month, $year);
    $rows_by_kode = array();
    foreach ($ns_data['rows'] as $row) {
        $rows_by_kode[(string) $row['kode_akun']] = $row;
    }

    return array(
        'year' => $year,
        'month' => $month,
        'rows_by_kode' => $rows_by_kode,
    );
}

function neraca_kode_akun_nominal_saldo($CI, $kode_akun, $field_neraca, $tahun_neraca, $bulan_transaksi = 0, $rows_by_kode = null)
{
    if ($rows_by_kode === null) {
        $saldo_map = neraca_get_neraca_saldo_map($CI, $tahun_neraca, $bulan_transaksi);
        $rows_by_kode = $saldo_map['rows_by_kode'];
    }

    $key = (string) $kode_akun;
    if (!isset($rows_by_kode[$key])) {
        return 0.0;
    }

    $debet = (float) $rows_by_kode[$key]['ns_debet_raw'];
    $kredit = (float) $rows_by_kode[$key]['ns_kredit_raw'];

    if (neraca_is_pasiva_field($field_neraca)) {
        return (float) $kredit - (float) $debet;
    }

    return (float) $debet - (float) $kredit;
}
