<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function labarugi_utama_unit_key()
{
    return 'UTAMA';
}

function labarugi_utama_editable_fields()
{
    $CI = get_instance();
    $CI->load->helper('laba_rugi_keterangan');
    return labarugi_keterangan_input_keys();
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

function labarugi_utama_field_value($data_tbl_laba_rugi, $field_key, $sync_map = null)
{
    if (!labarugi_utama_is_editable_field($field_key)) {
        return 0.0;
    }

    if (is_array($sync_map) && isset($sync_map[$field_key])) {
        $saved = $sync_map[$field_key];
        if ($saved && $saved->nominal_update !== null) {
            return (float) $saved->nominal_update;
        }
        if ($saved && $saved->nominal !== null) {
            return (float) $saved->nominal;
        }
    }

    $CI = get_instance();
    $CI->load->helper('laba_rugi_keterangan');
    $tbl_field = labarugi_keterangan_tbl_field_for_key($field_key);
    if ($tbl_field === '') {
        $tbl_field = $field_key;
    }

    if ($data_tbl_laba_rugi && isset($data_tbl_laba_rugi->$tbl_field)) {
        return (float) $data_tbl_laba_rugi->$tbl_field;
    }

    return 0.0;
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

function labarugi_utama_update_field($CI, $record_id, $field_key, $nominal, $tahun = 0, $bulan = 0, $uuid_laba_rugi = null)
{
    if (!labarugi_utama_is_editable_field($field_key)) {
        return false;
    }

    $record_id = (int) $record_id;
    $nominal = (float) $nominal;

    $CI->load->helper('laba_rugi_keterangan');
    $tbl_field = labarugi_keterangan_tbl_field_for_key($field_key);
    if ($tbl_field === '') {
        $tbl_field = $field_key;
    }

    if ($record_id > 0 && $CI->db->field_exists($tbl_field, 'tbl_laba_rugi')) {
        $CI->db->where('id', $record_id)->update('tbl_laba_rugi', array(
            $tbl_field => $nominal,
        ));
    }

    if ($tahun > 0 && $bulan > 0) {
        $CI->load->helper('laba_rugi_detail');
        if ($uuid_laba_rugi === null || $uuid_laba_rugi === '') {
            $uuid_laba_rugi = labarugi_detail_resolve_parent_uuid($CI, $tahun, $bulan);
        }
        if ($uuid_laba_rugi) {
            $CI->load->model('Tbl_laba_rugi_detail_model');
            $CI->Tbl_laba_rugi_detail_model->upsert(array(
                'tanggal' => labarugi_detail_tanggal_periode($tahun, $bulan),
                'uuid_laba_rugi' => $uuid_laba_rugi,
                'nama_laba_rugi' => $field_key,
                'unit' => labarugi_utama_unit_key(),
                'nominal' => $nominal,
                'nominal_update' => $nominal,
                'jenis_tab' => 'utama',
                'tahun_transaksi' => $tahun,
                'bulan_transaksi' => $bulan,
            ));
        }
    }

    return true;
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
