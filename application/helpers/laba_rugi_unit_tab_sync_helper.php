<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function labarugi_unit_tab_sync_rinci_derived_keys()
{
    return array(
        'beban_pemeliharaan',
        'beban_operasional_karyawan',
        'total_beban_operasional_umum',
    );
}

function labarugi_unit_tab_sync_is_rinci_derived_key($key)
{
    return in_array((string) $key, labarugi_unit_tab_sync_rinci_derived_keys(), true);
}

function labarugi_unit_tab_sync_rinci_only_keys()
{
    if (!function_exists('labarugi_keterangan_rinci_pemeliharaan_sub_rows')) {
        $CI =& get_instance();
        $CI->load->helper('laba_rugi_keterangan');
    }
    $keys = array('grp_beban_pemeliharaan');
    foreach (labarugi_keterangan_rinci_pemeliharaan_sub_rows() as $row) {
        $keys[] = $row['key'];
    }
    foreach (labarugi_keterangan_rinci_bok_calc_parts() as $key) {
        $keys[] = $key;
    }
    foreach (labarugi_keterangan_rinci_bou_calc_parts() as $key) {
        $keys[] = $key;
    }
    return $keys;
}

function labarugi_unit_tab_sync_is_rinci_only_key($key)
{
    return in_array((string) $key, labarugi_unit_tab_sync_rinci_only_keys(), true);
}

function labarugi_unit_tab_sync_peer_tab($jenis_tab)
{
    return $jenis_tab === 'rinci' ? 'sederhana' : 'rinci';
}

function labarugi_unit_tab_sync_is_unit_tab($jenis_tab)
{
    return in_array($jenis_tab, array('rinci', 'sederhana'), true);
}

function labarugi_unit_tab_sync_should_mirror($nama_laba_rugi, $source_jenis_tab)
{
    if (!labarugi_unit_tab_sync_is_unit_tab($source_jenis_tab)) {
        return false;
    }
    if (labarugi_unit_tab_sync_is_rinci_only_key($nama_laba_rugi)) {
        return false;
    }
    if ($source_jenis_tab === 'sederhana' && labarugi_unit_tab_sync_is_rinci_derived_key($nama_laba_rugi)) {
        return false;
    }
    return true;
}

function labarugi_unit_tab_sync_peer_saveable($CI, $peer_tab, $nama_laba_rugi)
{
    if (!labarugi_unit_tab_sync_is_unit_tab($peer_tab)) {
        return false;
    }
    if (labarugi_unit_tab_sync_is_rinci_only_key($nama_laba_rugi)) {
        return false;
    }

    $allowed = labarugi_keterangan_allowed_keys($CI, $peer_tab);
    if (in_array($nama_laba_rugi, $allowed, true)) {
        return true;
    }
    return labarugi_keterangan_is_calculated_key_for_tab($nama_laba_rugi, $peer_tab);
}

function labarugi_unit_tab_sync_load_helpers($CI)
{
    $CI->load->helper(array('laba_rugi_keterangan', 'laba_rugi_detail', 'laba_rugi_unit_merge', 'laba_rugi_unit_publish'));
}

function labarugi_unit_tab_sync_rinci_derived_nominal($detail_maps, $ket_key, $unit_key)
{
    if (!labarugi_unit_tab_sync_is_rinci_derived_key($ket_key)) {
        return labarugi_unit_tab_sync_detail_nominal($detail_maps, 'rinci', $ket_key, $unit_key);
    }

    $rinci_map = isset($detail_maps['rinci']) ? $detail_maps['rinci'] : array();

    if (function_exists('labarugi_unit_merge_detail_saved_row')) {
        $saved = labarugi_unit_merge_detail_saved_row($rinci_map, $ket_key, $unit_key);
    } else {
        $saved = null;
    }

    if ($saved !== null) {
        if ($saved->nominal_update !== null && $saved->nominal_update !== '') {
            return (float) $saved->nominal_update;
        }
        if ($saved->nominal !== null && $saved->nominal !== '') {
            return (float) $saved->nominal;
        }
    }

    if (!function_exists('labarugi_keterangan_calc_definitions_for_tab')) {
        $CI =& get_instance();
        labarugi_unit_tab_sync_load_helpers($CI);
    }

    $defs = labarugi_keterangan_calc_definitions_for_tab('rinci');
    if (isset($defs[$ket_key]['type']) && $defs[$ket_key]['type'] === 'sum' && !empty($defs[$ket_key]['parts'])) {
        $total = 0.0;
        foreach ($defs[$ket_key]['parts'] as $part_key) {
            if (function_exists('labarugi_unit_merge_detail_nominal')) {
                $total += labarugi_unit_merge_detail_nominal($rinci_map, $part_key, $unit_key);
            } else {
                $total += labarugi_unit_publish_detail_nominal($rinci_map, $part_key, $unit_key);
            }
        }
        return $total;
    }

    if (function_exists('labarugi_unit_merge_detail_nominal')) {
        return labarugi_unit_merge_detail_nominal($rinci_map, $ket_key, $unit_key);
    }
    return labarugi_unit_publish_detail_nominal($rinci_map, $ket_key, $unit_key);
}

function labarugi_unit_tab_sync_push_derived_to_sederhana($CI, $uuid_laba_rugi, $tahun, $bulan, $unit, $detail_maps, $keterangan_data = '')
{
    if ($uuid_laba_rugi === '' || $unit === '') {
        return;
    }

    labarugi_unit_tab_sync_load_helpers($CI);

    if (!is_array($detail_maps) || !isset($detail_maps['rinci'])) {
        $detail_maps = array(
            'rinci' => labarugi_detail_load_map($CI, $uuid_laba_rugi, 'rinci'),
            'sederhana' => labarugi_detail_load_map($CI, $uuid_laba_rugi, 'sederhana'),
        );
    }

    foreach (labarugi_unit_tab_sync_rinci_derived_keys() as $derived_key) {
        $nominal = labarugi_unit_tab_sync_rinci_derived_nominal($detail_maps, $derived_key, $unit);
        labarugi_unit_tab_sync_mirror_detail(
            $CI,
            $uuid_laba_rugi,
            $tahun,
            $bulan,
            'rinci',
            $derived_key,
            $unit,
            $nominal,
            $keterangan_data !== '' ? $keterangan_data : $derived_key
        );
    }
}

function labarugi_unit_tab_sync_reconcile_derived_maps($CI, $uuid_laba_rugi, $tahun, $bulan, $detail_maps, $list_unit)
{
    if ($uuid_laba_rugi === '' || empty($list_unit)) {
        return $detail_maps;
    }

    labarugi_unit_tab_sync_load_helpers($CI);
    if (!function_exists('labarugi_unit_merge_display_units')) {
        $CI->load->helper('laba_rugi_unit_merge');
    }

    $display_units = labarugi_unit_merge_display_units($list_unit);
    foreach ($display_units as $unit_row) {
        $unit_key = labarugi_detail_unit_key($unit_row);
        labarugi_unit_tab_sync_push_derived_to_sederhana($CI, $uuid_laba_rugi, $tahun, $bulan, $unit_key, $detail_maps, '');
    }

    $detail_maps['sederhana'] = labarugi_detail_load_map($CI, $uuid_laba_rugi, 'sederhana');
    return $detail_maps;
}

function labarugi_unit_tab_sync_should_refresh_derived_after_rinci_save($nama_laba_rugi)
{
    return labarugi_unit_tab_sync_is_rinci_only_key($nama_laba_rugi)
        || labarugi_unit_tab_sync_is_rinci_derived_key($nama_laba_rugi);
}

function labarugi_unit_tab_sync_mirror_detail($CI, $uuid_laba_rugi, $tahun, $bulan, $source_jenis_tab, $nama_laba_rugi, $unit, $nominal, $keterangan_data, $extra = array())
{
    if (!labarugi_unit_tab_sync_should_mirror($nama_laba_rugi, $source_jenis_tab)) {
        return;
    }

    $peer_tab = labarugi_unit_tab_sync_peer_tab($source_jenis_tab);
    if (!labarugi_unit_tab_sync_peer_saveable($CI, $peer_tab, $nama_laba_rugi)) {
        return;
    }

    $payload = array(
        'tanggal' => labarugi_detail_tanggal_periode($tahun, $bulan),
        'uuid_laba_rugi' => $uuid_laba_rugi,
        'nama_laba_rugi' => $nama_laba_rugi,
        'unit' => $unit,
        'nominal' => $nominal,
        'nominal_update' => $nominal,
        'keterangan_data' => ($keterangan_data !== '') ? $keterangan_data : null,
        'jenis_tab' => $peer_tab,
        'tahun_transaksi' => $tahun,
        'bulan_transaksi' => $bulan,
    );

    if (array_key_exists('auto_sistem', $extra)) {
        $payload['auto_sistem'] = $extra['auto_sistem'];
    }
    if (array_key_exists('status_sync_auto', $extra)) {
        $payload['status_sync_auto'] = $extra['status_sync_auto'];
    }

    $CI->Tbl_laba_rugi_detail_model->upsert($payload);
}

function labarugi_unit_tab_sync_detail_nominal($detail_maps, $jenis_tab, $ket_key, $unit_key)
{
    if ($jenis_tab === 'sederhana' && labarugi_unit_tab_sync_is_rinci_derived_key($ket_key)) {
        return labarugi_unit_tab_sync_rinci_derived_nominal($detail_maps, $ket_key, $unit_key);
    }

    $detail_map = isset($detail_maps[$jenis_tab]) ? $detail_maps[$jenis_tab] : array();
    if (function_exists('labarugi_unit_merge_detail_nominal')) {
        return labarugi_unit_merge_detail_nominal($detail_map, $ket_key, $unit_key);
    }
    return labarugi_unit_publish_detail_nominal($detail_map, $ket_key, $unit_key);
}

function labarugi_unit_tab_sync_detail_saved_row($detail_maps, $jenis_tab, $ket_key, $unit_key)
{
    if ($jenis_tab === 'sederhana' && labarugi_unit_tab_sync_is_rinci_derived_key($ket_key)) {
        return null;
    }

    $detail_map = isset($detail_maps[$jenis_tab]) ? $detail_maps[$jenis_tab] : array();
    if (function_exists('labarugi_unit_merge_detail_saved_row')) {
        return labarugi_unit_merge_detail_saved_row($detail_map, $ket_key, $unit_key);
    }
    return null;
}

function labarugi_unit_tab_sync_js_config()
{
    return array(
        'rinciDerivedKeys' => labarugi_unit_tab_sync_rinci_derived_keys(),
        'rinciOnlyKeys' => labarugi_unit_tab_sync_rinci_only_keys(),
    );
}
