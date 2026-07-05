<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function ska_source_ensure_source_field_column($CI)
{
    if (!$CI->db->table_exists('sys_unit_kode_akun')) {
        return false;
    }
    if (!$CI->db->field_exists('source_field', 'sys_unit_kode_akun')) {
        @$CI->db->query("ALTER TABLE `sys_unit_kode_akun` ADD COLUMN `source_field` VARCHAR(64) NULL DEFAULT NULL AFTER `tbl_source`");
    }
    return $CI->db->field_exists('source_field', 'sys_unit_kode_akun');
}

function ska_source_is_valid_identifier($name)
{
    return (bool) preg_match('/^[a-zA-Z0-9_]+$/', (string) $name);
}

function ska_source_value_label($tbl_source)
{
    $tbl_source = trim((string) $tbl_source);
    $map = array(
        'tbl_penjualan' => 'Unit',
        'tbl_pembelian' => 'Konsumen',
        'jurnal_kas' => 'Keterangan',
    );
    return isset($map[$tbl_source]) ? $map[$tbl_source] : 'Nilai Sumber';
}

function ska_source_default_field($tbl_source)
{
    $tbl_source = trim((string) $tbl_source);
    $map = array(
        'tbl_penjualan' => 'uuid_unit',
        'tbl_pembelian' => 'konsumen',
        'jurnal_kas' => 'keterangan',
    );
    return isset($map[$tbl_source]) ? $map[$tbl_source] : '';
}

function ska_source_suggested_fields($tbl_source)
{
    $tbl_source = trim((string) $tbl_source);
    $map = array(
        'tbl_penjualan' => array('uuid_unit', 'kode_pl', 'konsumen_nama', 'nama_barang'),
        'tbl_pembelian' => array('konsumen', 'supplier_nama', 'kode_pl', 'spop', 'uraian'),
        'jurnal_kas' => array('keterangan', 'pl', 'kode_unit', 'bukti'),
    );
    return isset($map[$tbl_source]) ? $map[$tbl_source] : array();
}

function ska_source_get_table_fields($CI, $table)
{
    $table = trim((string) $table);
    if (!ska_source_is_valid_identifier($table) || !$CI->db->table_exists($table)) {
        return array('ok' => false, 'message' => 'Tabel tidak valid atau tidak ditemukan.', 'fields' => array());
    }

    $fields = array();
    foreach ($CI->db->field_data($table) as $col) {
        $name = isset($col->name) ? (string) $col->name : '';
        if ($name === '' || $name === 'id') {
            continue;
        }
        $fields[] = array(
            'name' => $name,
            'type' => isset($col->type) ? (string) $col->type : '',
        );
    }

    $suggested = ska_source_suggested_fields($table);
    usort($fields, function ($a, $b) use ($suggested) {
        $pa = array_search($a['name'], $suggested, true);
        $pb = array_search($b['name'], $suggested, true);
        $pa = ($pa === false) ? 9999 : (int) $pa;
        $pb = ($pb === false) ? 9999 : (int) $pb;
        if ($pa !== $pb) {
            return $pa - $pb;
        }
        return strcmp($a['name'], $b['name']);
    });

    return array(
        'ok' => true,
        'fields' => $fields,
        'default_field' => ska_source_default_field($table),
        'value_label' => ska_source_value_label($table),
    );
}

function ska_source_uuid_column_for_field($table, $field)
{
    $table = trim((string) $table);
    $field = trim((string) $field);
    $map = array(
        'tbl_penjualan' => array(
            'uuid_unit' => 'uuid_unit',
            'kode_pl' => 'uuid_unit',
            'konsumen_nama' => 'uuid_konsumen',
            'konsumen' => 'uuid_konsumen',
        ),
        'tbl_pembelian' => array(
            'konsumen' => 'uuid_supplier',
            'supplier_nama' => 'uuid_supplier',
            'kode_pl' => 'uuid_supplier',
            'spop' => 'uuid_supplier',
        ),
        'jurnal_kas' => array(),
    );
    if (!isset($map[$table])) {
        return '';
    }
    if ($field !== '' && isset($map[$table][$field])) {
        return $map[$table][$field];
    }
    return '';
}

function ska_source_kode_column_for_field($table, $field)
{
    $table = trim((string) $table);
    $field = trim((string) $field);
    if ($table === 'tbl_penjualan' && in_array($field, array('uuid_unit', 'kode_pl'), true)) {
        return 'kode_pl';
    }
    if ($table === 'tbl_pembelian' && in_array($field, array('konsumen', 'supplier_nama', 'kode_pl'), true)) {
        return 'kode_pl';
    }
    if ($table === 'jurnal_kas' && in_array($field, array('keterangan', 'pl', 'kode_unit'), true)) {
        return $field === 'kode_unit' ? 'kode_unit' : 'pl';
    }
    return $field;
}

function ska_source_lookup_sys_unit($CI, $uuid_unit, $kode_hint = '')
{
    $uuid_unit = trim((string) $uuid_unit);
    $kode_hint = trim((string) $kode_hint);
    if ($uuid_unit !== '' && $CI->db->table_exists('sys_unit')) {
        $row = $CI->db->get_where('sys_unit', array('uuid_unit' => $uuid_unit))->row();
        if ($row) {
            return array(
                'uuid' => (string) $row->uuid_unit,
                'kode' => (string) $row->kode_unit,
                'nama' => (string) $row->nama_unit,
            );
        }
    }
    if ($kode_hint !== '' && $CI->db->table_exists('sys_unit')) {
        $row = $CI->db->get_where('sys_unit', array('kode_unit' => $kode_hint))->row();
        if ($row) {
            return array(
                'uuid' => (string) $row->uuid_unit,
                'kode' => (string) $row->kode_unit,
                'nama' => (string) $row->nama_unit,
            );
        }
    }
    return array('uuid' => $uuid_unit, 'kode' => $kode_hint, 'nama' => '');
}

function ska_source_get_field_values($CI, $table, $field, $limit = 500)
{
    $table = trim((string) $table);
    $field = trim((string) $field);
    $limit = max(1, min(2000, (int) $limit));

    if (!ska_source_is_valid_identifier($table) || !ska_source_is_valid_identifier($field)) {
        return array('ok' => false, 'message' => 'Tabel atau field tidak valid.', 'items' => array());
    }
    if (!$CI->db->table_exists($table) || !$CI->db->field_exists($field, $table)) {
        return array('ok' => false, 'message' => 'Tabel atau field tidak ditemukan.', 'items' => array());
    }

    $uuid_col = ska_source_uuid_column_for_field($table, $field);
    $kode_col = ska_source_kode_column_for_field($table, $field);
    $select_parts = array("`$field` AS field_value");
    if ($uuid_col !== '' && $uuid_col !== $field && $CI->db->field_exists($uuid_col, $table)) {
        $select_parts[] = "MAX(NULLIF(TRIM(`$uuid_col`), '')) AS uuid_value";
    }
    if ($kode_col !== '' && $kode_col !== $field && $CI->db->field_exists($kode_col, $table)) {
        $select_parts[] = "MAX(NULLIF(TRIM(`$kode_col`), '')) AS kode_value";
    }

    $sql = 'SELECT ' . implode(', ', $select_parts)
        . ' FROM `' . $table . '`'
        . ' WHERE `' . $field . '` IS NOT NULL AND TRIM(CAST(`' . $field . '` AS CHAR)) <> \'\''
        . ' GROUP BY `' . $field . '`'
        . ' ORDER BY `' . $field . '` ASC'
        . ' LIMIT ' . $limit;

    $rows = $CI->db->query($sql)->result();
    $items = array();
    $seen = array();

    foreach ((array) $rows as $row) {
        $label = trim((string) $row->field_value);
        if ($label === '' || isset($seen[$label])) {
            continue;
        }
        $seen[$label] = true;

        $uuid = isset($row->uuid_value) ? trim((string) $row->uuid_value) : '';
        $kode = isset($row->kode_value) ? trim((string) $row->kode_value) : '';
        if ($uuid === '' && $field === 'uuid_unit') {
            $uuid = $label;
        }
        if ($kode === '' && $field === 'kode_pl') {
            $kode = $label;
        }

        if ($table === 'tbl_penjualan' && in_array($field, array('uuid_unit', 'kode_pl'), true)) {
            $unit = ska_source_lookup_sys_unit($CI, $uuid, $kode !== '' ? $kode : ($field === 'kode_pl' ? $label : ''));
            if ($unit['uuid'] !== '') {
                $uuid = $unit['uuid'];
            }
            if ($unit['kode'] !== '') {
                $kode = $unit['kode'];
            }
            if ($unit['nama'] !== '') {
                $label = $unit['kode'] !== '' ? ($unit['kode'] . ' - ' . $unit['nama']) : $unit['nama'];
            }
        } elseif ($uuid === '' && $kode === '') {
            $kode = $label;
        }

        $items[] = array(
            'value' => trim((string) $row->field_value),
            'label' => $label,
            'uuid' => $uuid,
            'kode' => $kode,
            'nama' => ($table === 'tbl_penjualan' && isset($unit) && $unit['nama'] !== '') ? $unit['nama'] : trim((string) $row->field_value),
        );
        unset($unit);
    }

    return array(
        'ok' => true,
        'items' => $items,
        'value_label' => ska_source_value_label($table),
        'field' => $field,
        'table' => $table,
    );
}

function ska_source_panel_urls()
{
    return array(
        'url_fields' => site_url('Setting_kode_akun/ajax_source_fields'),
        'url_values' => site_url('Setting_kode_akun/ajax_source_field_values'),
    );
}
