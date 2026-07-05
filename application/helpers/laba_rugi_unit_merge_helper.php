<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function labarugi_unit_merge_normalize_key($key)
{
    $key = strtolower(trim((string) $key));
    $key = str_replace(array(' ', '-'), '_', $key);
    while (strpos($key, '__') !== false) {
        $key = str_replace('__', '_', $key);
    }
    return $key;
}

function labarugi_unit_merge_defs()
{
    return array(
        'atk' => array(
            'label' => 'ATK',
            'sources' => array('atk_rsud', 'dinas_umum'),
        ),
    );
}

function labarugi_unit_merge_source_keys_for($merge_key)
{
    $merge_key = labarugi_unit_merge_normalize_key($merge_key);
    $defs = labarugi_unit_merge_defs();
    return isset($defs[$merge_key]['sources']) ? $defs[$merge_key]['sources'] : array();
}

function labarugi_unit_merge_is_merged_key($unit_key)
{
    $unit_key = labarugi_unit_merge_normalize_key($unit_key);
    return isset(labarugi_unit_merge_defs()[$unit_key]);
}

function labarugi_unit_merge_is_hidden_source_key($unit_key)
{
    $unit_key = labarugi_unit_merge_normalize_key($unit_key);
    foreach (labarugi_unit_merge_defs() as $def) {
        foreach ($def['sources'] as $src) {
            if (labarugi_unit_merge_normalize_key($src) === $unit_key) {
                return true;
            }
        }
    }
    return false;
}

function labarugi_unit_merge_source_label($source_key)
{
    $map = array(
        'atk_rsud' => 'ATK RSUD',
        'dinas_umum' => 'ATK Dinas Umum',
    );
    $key = labarugi_unit_merge_normalize_key($source_key);
    return isset($map[$key]) ? $map[$key] : strtoupper(str_replace('_', ' ', $key));
}

function labarugi_unit_merge_display_units($list_unit)
{
    $defs = labarugi_unit_merge_defs();
    $hidden = array();
    foreach ($defs as $merge_key => $def) {
        foreach ($def['sources'] as $src) {
            $hidden[labarugi_unit_merge_normalize_key($src)] = $merge_key;
        }
    }

    $out = array();
    $merged_inserted = array();
    foreach ($list_unit as $unit_row) {
        $unit_key = labarugi_unit_merge_normalize_key(labarugi_detail_unit_key($unit_row));
        if (isset($hidden[$unit_key])) {
            $merge_key = $hidden[$unit_key];
            if (!isset($merged_inserted[$merge_key])) {
                $def = $defs[$merge_key];
                $out[] = (object) array(
                    'kode_unit' => $merge_key,
                    'nama_unit' => $def['label'],
                );
                $merged_inserted[$merge_key] = true;
            }
            continue;
        }
        if (isset($defs[$unit_key]) && !isset($merged_inserted[$unit_key])) {
            $merged_inserted[$unit_key] = true;
        }
        $out[] = $unit_row;
    }

    return $out;
}

function labarugi_unit_merge_is_published($publish_map, $unit_key)
{
    $key = labarugi_unit_merge_normalize_key($unit_key);
    if (labarugi_unit_merge_is_merged_key($key)) {
        if (labarugi_unit_publish_is_published($publish_map, $key)) {
            return true;
        }
        foreach (labarugi_unit_merge_source_keys_for($key) as $src) {
            if (labarugi_unit_publish_is_published($publish_map, $src)) {
                return true;
            }
        }
        return false;
    }
    return labarugi_unit_publish_is_published($publish_map, $unit_key);
}

function labarugi_unit_merge_published_units($list_unit, $publish_map)
{
    $out = array();
    foreach ($list_unit as $unit_row) {
        $unit_key = labarugi_detail_unit_key($unit_row);
        if (labarugi_unit_merge_is_published($publish_map, $unit_key)) {
            $out[] = $unit_row;
        }
    }
    return $out;
}

function labarugi_unit_merge_detail_saved_row($detail_map, $ket_key, $unit_key)
{
    $key = labarugi_unit_merge_normalize_key($unit_key);
    if (isset($detail_map[$ket_key][$key])) {
        return $detail_map[$ket_key][$key];
    }
    if (isset($detail_map[$ket_key][$unit_key])) {
        return $detail_map[$ket_key][$unit_key];
    }
    return null;
}

function labarugi_unit_merge_detail_nominal($detail_map, $ket_key, $unit_key)
{
    $key = labarugi_unit_merge_normalize_key($unit_key);
    if (!labarugi_unit_merge_is_merged_key($key)) {
        return labarugi_unit_publish_detail_nominal($detail_map, $ket_key, $unit_key);
    }

    $saved = labarugi_unit_merge_detail_saved_row($detail_map, $ket_key, $key);
    if ($saved !== null) {
        return labarugi_unit_publish_detail_nominal($detail_map, $ket_key, $key);
    }

    $total = 0.0;
    foreach (labarugi_unit_merge_source_keys_for($key) as $src) {
        $total += labarugi_unit_publish_detail_nominal($detail_map, $ket_key, $src);
    }
    return $total;
}

function labarugi_unit_merge_row_sync_auto($detail_map, $ket_key, $unit_key)
{
    $key = labarugi_unit_merge_normalize_key($unit_key);
    $saved = labarugi_unit_merge_detail_saved_row($detail_map, $ket_key, $key);
    if ($saved !== null) {
        return labarugi_detail_row_sync_auto($saved);
    }
    if (!labarugi_unit_merge_is_merged_key($key)) {
        return 0;
    }
    foreach (labarugi_unit_merge_source_keys_for($key) as $src) {
        if (isset($detail_map[$ket_key][$src]) && labarugi_detail_row_sync_auto($detail_map[$ket_key][$src]) === 1) {
            return 1;
        }
    }
    return 0;
}

function labarugi_unit_merge_kode_akun_nominal($CI, $bb_merged_rows, $ket_kodes, $unit_key, $unit_label)
{
    $key = labarugi_unit_merge_normalize_key($unit_key);
    if (!labarugi_unit_merge_is_merged_key($key)) {
        $effective_kodes = labarugi_kode_akun_resolve_unit_kodes($CI, $ket_kodes, $unit_key, $unit_label);
        return labarugi_kode_akun_unit_nominal_from_data($bb_merged_rows, $effective_kodes, $unit_key, $unit_label, true);
    }

    $total = 0.0;
    foreach (labarugi_unit_merge_source_keys_for($key) as $src) {
        $src_label = labarugi_unit_merge_source_label($src);
        $effective_kodes = labarugi_kode_akun_resolve_unit_kodes($CI, $ket_kodes, $src, $src_label);
        $total += labarugi_kode_akun_unit_nominal_from_data($bb_merged_rows, $effective_kodes, $src, $src_label, true);
    }
    return $total;
}

function labarugi_unit_merge_transactions_payload($CI, $uuid_nama_keterangan, $jenis_tab, $tahun, $bulan, $unit_key, $unit_label)
{
    $key = labarugi_unit_merge_normalize_key($unit_key);
    if (!labarugi_unit_merge_is_merged_key($key)) {
        return labarugi_kode_akun_transactions_payload($CI, $uuid_nama_keterangan, $jenis_tab, $tahun, $bulan, $unit_key, $unit_label, 'unit');
    }

    $rows = array();
    $total = 0.0;
    $effective_kodes = array();
    foreach (labarugi_unit_merge_source_keys_for($key) as $src) {
        $src_label = labarugi_unit_merge_source_label($src);
        $payload = labarugi_kode_akun_transactions_payload($CI, $uuid_nama_keterangan, $jenis_tab, $tahun, $bulan, $src, $src_label, 'unit');
        if (!empty($payload['rows'])) {
            foreach ($payload['rows'] as $row) {
                $rows[] = $row;
            }
        }
        $total += isset($payload['total']) ? (float) $payload['total'] : 0.0;
        if (!empty($payload['effective_kodes'])) {
            $effective_kodes = array_merge($effective_kodes, $payload['effective_kodes']);
        }
    }

    if (empty($rows)) {
        return array(
            'rows' => array(),
            'total' => 0.0,
            'empty_code' => 'NO_TRANSACTION',
            'empty_message' => labarugi_kode_akun_empty_message('NO_TRANSACTION'),
            'effective_kodes' => array_values(array_unique($effective_kodes)),
        );
    }

    return array(
        'rows' => $rows,
        'total' => $total,
        'empty_code' => '',
        'empty_message' => '',
        'effective_kodes' => array_values(array_unique($effective_kodes)),
    );
}
