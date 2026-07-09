<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function labarugi_cetak_unit_load_helpers($CI)
{
    $CI->load->helper(array(
        'laba_rugi_keterangan',
        'laba_rugi_detail',
        'laba_rugi_kode_akun',
        'laba_rugi_unit_tab_sync',
        'laba_rugi_unit_merge',
        'laba_rugi_unit_publish',
    ));
}

function labarugi_cetak_unit_calc_value($def, array $values)
{
    if (!$def || empty($def['parts'])) {
        return 0.0;
    }
    $type = isset($def['type']) ? $def['type'] : '';
    if ($type === 'sum') {
        $total = 0.0;
        foreach ($def['parts'] as $key) {
            $total += isset($values[$key]) ? (float) $values[$key] : 0.0;
        }
        return $total;
    }
    if ($type === 'subtract') {
        $a = isset($def['parts'][0], $values[$def['parts'][0]]) ? (float) $values[$def['parts'][0]] : 0.0;
        $b = isset($def['parts'][1], $values[$def['parts'][1]]) ? (float) $values[$def['parts'][1]] : 0.0;
        return $a - $b;
    }
    if ($type === 'add_sub') {
        $a = isset($def['parts'][0], $values[$def['parts'][0]]) ? (float) $values[$def['parts'][0]] : 0.0;
        $b = isset($def['parts'][1], $values[$def['parts'][1]]) ? (float) $values[$def['parts'][1]] : 0.0;
        $c = isset($def['parts'][2], $values[$def['parts'][2]]) ? (float) $values[$def['parts'][2]] : 0.0;
        return $a + $b - $c;
    }
    return 0.0;
}

function labarugi_cetak_unit_editable_nominal($CI, array $detail_maps, $jenis_tab, $ket_key, $unit_key, $unit_label, $tahun, $bulan, array $ka_selected_map, array $bb_merged_rows)
{
    if ($jenis_tab === 'sederhana' && labarugi_unit_tab_sync_is_rinci_derived_key($ket_key)) {
        return labarugi_unit_tab_sync_rinci_derived_nominal($detail_maps, $ket_key, $unit_key);
    }

    $detail_map = isset($detail_maps[$jenis_tab]) ? $detail_maps[$jenis_tab] : array();
    $saved = labarugi_unit_tab_sync_detail_saved_row($detail_maps, $jenis_tab, $ket_key, $unit_key);
    $sync_auto = labarugi_unit_merge_row_sync_auto($detail_map, $ket_key, $unit_key);

    if ($sync_auto === 1) {
        $ket_kodes = isset($ka_selected_map[$ket_key]) ? $ka_selected_map[$ket_key] : array();
        return labarugi_unit_merge_kode_akun_nominal($CI, $bb_merged_rows, $ket_kodes, $unit_key, $unit_label);
    }

    if ($saved !== null) {
        if ($saved->nominal_update !== null && $saved->nominal_update !== '') {
            return (float) $saved->nominal_update;
        }
        if ($saved->nominal !== null && $saved->nominal !== '') {
            return (float) $saved->nominal;
        }
    }

    return labarugi_unit_tab_sync_detail_nominal($detail_maps, $jenis_tab, $ket_key, $unit_key);
}

function labarugi_cetak_unit_form_values_map($CI, array $detail_maps, $jenis_tab, $unit_key, $unit_label, $tahun, $bulan, array $keterangan_rows)
{
    labarugi_cetak_unit_load_helpers($CI);

    $bb_merged_rows = labarugi_kode_akun_merged_rows($CI, $tahun, $bulan);
    $ka_selected_map = labarugi_kode_akun_selected_map_by_tab($CI, $jenis_tab);

    $values = array();
    foreach ($keterangan_rows as $ket_row) {
        $ket_key = $ket_row['key'];
        if (labarugi_keterangan_is_title_row($ket_row)) {
            continue;
        }
        if (labarugi_keterangan_is_calculated_key_for_tab($ket_key, $jenis_tab)) {
            continue;
        }
        $values[$ket_key] = labarugi_cetak_unit_editable_nominal(
            $CI,
            $detail_maps,
            $jenis_tab,
            $ket_key,
            $unit_key,
            $unit_label,
            $tahun,
            $bulan,
            $ka_selected_map,
            $bb_merged_rows
        );
    }

    $calc_order = labarugi_keterangan_calc_order_for_tab($jenis_tab);
    $calc_defs = labarugi_keterangan_calc_definitions_for_tab($jenis_tab);
    foreach ($calc_order as $calc_key) {
        if (!isset($calc_defs[$calc_key])) {
            continue;
        }
        $values[$calc_key] = labarugi_cetak_unit_calc_value($calc_defs[$calc_key], $values);
    }

    return $values;
}

function labarugi_cetak_unit_judul_tab($jenis_tab)
{
    return ($jenis_tab === 'sederhana') ? 'SEDERHANA' : 'RINCI';
}
