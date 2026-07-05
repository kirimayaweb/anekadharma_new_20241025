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
        $rinci_map = isset($detail_maps['rinci']) ? $detail_maps['rinci'] : array();
        if (function_exists('labarugi_unit_merge_detail_nominal')) {
            return labarugi_unit_merge_detail_nominal($rinci_map, $ket_key, $unit_key);
        }
        return labarugi_unit_publish_detail_nominal($rinci_map, $ket_key, $unit_key);
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
        $rinci_map = isset($detail_maps['rinci']) ? $detail_maps['rinci'] : array();
        if (function_exists('labarugi_unit_merge_detail_saved_row')) {
            return labarugi_unit_merge_detail_saved_row($rinci_map, $ket_key, $unit_key);
        }
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
