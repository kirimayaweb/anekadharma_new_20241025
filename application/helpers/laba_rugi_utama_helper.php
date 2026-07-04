<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function labarugi_utama_unit_key()
{
    return 'UTAMA';
}

function labarugi_utama_editable_fields()
{
    return array(
        'penjualan',
        'beban_pokok_penjualan',
        'beban_depresiasi_dan_amortisasi',
        'beban_operasional_karyawan',
        'beban_operasional_promosi',
        'beban_perjalanan_dinas',
        'beban_transportasi',
        'beban_pemeliharaan',
        'total_beban_operasional_umum',
        'pendapatan_bunga_bank',
        'pendapatan_rupa_rupa',
        'beban_bunga_dan_adm_bank',
        'beban_rupa_rupa',
        'pajak',
    );
}

function labarugi_utama_is_editable_field($field_key)
{
    return in_array((string) $field_key, labarugi_utama_editable_fields(), true);
}

function labarugi_utama_sync_load_map($CI, $uuid_laba_rugi)
{
    $CI->load->helper('laba_rugi_detail');
    $map = labarugi_detail_load_map($CI, $uuid_laba_rugi, 'utama');
    $unit_key = labarugi_utama_unit_key();
    $out = array();
    foreach ($map as $ket_key => $units) {
        if (isset($units[$unit_key])) {
            $out[$ket_key] = $units[$unit_key];
        }
    }
    return $out;
}

function labarugi_utama_field_value($data_tbl_laba_rugi, $field_key)
{
    if (!$data_tbl_laba_rugi || !labarugi_utama_is_editable_field($field_key)) {
        return 0.0;
    }
    return isset($data_tbl_laba_rugi->$field_key) ? (float) $data_tbl_laba_rugi->$field_key : 0.0;
}

function labarugi_utama_system_nominal($CI, $field_key, $ka_map, $bb_rows, $tahun, $bulan)
{
    $CI->load->helper('laba_rugi_kode_akun');
    $kodes = isset($ka_map[$field_key]) ? $ka_map[$field_key] : array();
    if ($bb_rows === null) {
        $bb_rows = labarugi_kode_akun_merged_rows($CI, $tahun, $bulan);
    }
    return labarugi_kode_akun_unit_nominal_from_data($bb_rows, $kodes, '', '', false);
}

function labarugi_utama_update_field($CI, $record_id, $field_key, $nominal)
{
    if (!labarugi_utama_is_editable_field($field_key)) {
        return false;
    }
    $record_id = (int) $record_id;
    if ($record_id < 1) {
        return false;
    }
    if (!$CI->db->field_exists($field_key, 'tbl_laba_rugi')) {
        return false;
    }
    return (bool) $CI->db->where('id', $record_id)->update('tbl_laba_rugi', array(
        $field_key => (float) $nominal,
    ));
}

function labarugi_utama_resolve_record_id($CI, $record_id, $tahun, $bulan)
{
    $record_id = (int) $record_id;
    if ($record_id > 0) {
        return $record_id;
    }
    $row = $CI->db->get_where('tbl_laba_rugi', array(
        'tahun_transaksi' => (int) $tahun,
        'bulan_transaksi' => (int) $bulan,
    ))->row();
    return ($row && isset($row->id)) ? (int) $row->id : 0;
}
