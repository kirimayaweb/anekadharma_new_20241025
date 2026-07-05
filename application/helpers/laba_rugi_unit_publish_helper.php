<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function labarugi_unit_publish_table_name()
{
    return 'sys_labarugi_unit_publish';
}

function labarugi_unit_publish_clear_field_cache($CI, $table)
{
    if (isset($CI->db->data_cache['field_names'][$table])) {
        unset($CI->db->data_cache['field_names'][$table]);
    }
}

function labarugi_unit_publish_ensure_columns($CI)
{
    static $done = false;
    if ($done) {
        return true;
    }

    $table = labarugi_unit_publish_table_name();
    if (!$CI->db->table_exists($table)) {
        return false;
    }

    labarugi_unit_publish_clear_field_cache($CI, $table);

    if (!$CI->db->field_exists('status_publish_unit', $table)) {
        if ($CI->db->field_exists('is_publish', $table)) {
            $CI->db->query("ALTER TABLE `" . $table . "` ADD `status_publish_unit` tinyint(1) NOT NULL DEFAULT 0 AFTER `bulan_transaksi`");
            $CI->db->query("UPDATE `" . $table . "` SET `status_publish_unit` = IFNULL(`is_publish`, 0)");
        } else {
            $CI->db->query("ALTER TABLE `" . $table . "` ADD `status_publish_unit` tinyint(1) NOT NULL DEFAULT 0 AFTER `bulan_transaksi`");
        }
        labarugi_unit_publish_clear_field_cache($CI, $table);
    }

    $done = true;
    return true;
}

function labarugi_unit_publish_ensure_table($CI)
{
    static $done = false;
    if ($done) {
        return true;
    }

    $table = labarugi_unit_publish_table_name();
    if (!$CI->db->table_exists($table)) {
        $sql = "CREATE TABLE IF NOT EXISTS `" . $table . "` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `uuid_laba_rugi` varchar(255) NOT NULL,
            `unit` varchar(128) NOT NULL,
            `jenis_tab` varchar(20) NOT NULL DEFAULT 'rinci',
            `tahun_transaksi` int(4) NOT NULL,
            `bulan_transaksi` int(2) NOT NULL,
            `status_publish_unit` tinyint(1) NOT NULL DEFAULT 0,
            `is_publish` tinyint(1) NOT NULL DEFAULT 0,
            `date_input` datetime DEFAULT NULL,
            `date_update` datetime DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uniq_labarugi_unit_publish` (`uuid_laba_rugi`, `unit`, `jenis_tab`),
            KEY `idx_labarugi_unit_publish_periode` (`tahun_transaksi`, `bulan_transaksi`, `jenis_tab`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        $CI->db->query($sql);
        labarugi_unit_publish_clear_field_cache($CI, $table);
    }

    labarugi_unit_publish_ensure_columns($CI);
    $done = true;
    return true;
}

function labarugi_unit_publish_row_status($row)
{
    if (isset($row->status_publish_unit)) {
        return (int) $row->status_publish_unit === 1 ? 1 : 0;
    }
    if (isset($row->is_publish)) {
        return (int) $row->is_publish === 1 ? 1 : 0;
    }
    return 0;
}

function labarugi_unit_publish_resolve_uuid($CI, $uuid_laba_rugi, $tahun, $bulan)
{
    if ($uuid_laba_rugi !== null && $uuid_laba_rugi !== '') {
        return $uuid_laba_rugi;
    }
    if ((int) $tahun <= 0 || (int) $bulan <= 0) {
        return '';
    }
    $row = $CI->db->get_where('tbl_laba_rugi', array(
        'tahun_transaksi' => (int) $tahun,
        'bulan_transaksi' => (int) $bulan,
    ))->row();
    return ($row && !empty($row->uuid_data_laba_rugi)) ? $row->uuid_data_laba_rugi : '';
}

function labarugi_unit_publish_load_map($CI, $uuid_laba_rugi, $jenis_tab, $tahun = 0, $bulan = 0)
{
    $map = array();
    labarugi_unit_publish_ensure_table($CI);

    $uuid_laba_rugi = labarugi_unit_publish_resolve_uuid($CI, $uuid_laba_rugi, $tahun, $bulan);

    $CI->db->where('jenis_tab', $jenis_tab);
    if ((int) $tahun > 0) {
        $CI->db->where('tahun_transaksi', (int) $tahun);
    }
    if ((int) $bulan > 0) {
        $CI->db->where('bulan_transaksi', (int) $bulan);
    }
    if ($uuid_laba_rugi !== '') {
        $CI->db->where('uuid_laba_rugi', $uuid_laba_rugi);
    }

    $rows = $CI->db->get(labarugi_unit_publish_table_name())->result();
    foreach ($rows as $row) {
        $map[(string) $row->unit] = (labarugi_unit_publish_row_status($row) === 1);
    }

    return $map;
}

function labarugi_unit_publish_is_published($map, $unit_key)
{
    $unit_key = (string) $unit_key;
    return isset($map[$unit_key]) && $map[$unit_key] === true;
}

function labarugi_unit_publish_published_units($list_unit, $publish_map)
{
    $out = array();
    foreach ($list_unit as $unit_row) {
        $unit_key = labarugi_detail_unit_key($unit_row);
        if (labarugi_unit_publish_is_published($publish_map, $unit_key)) {
            $out[] = $unit_row;
        }
    }
    return $out;
}

function labarugi_unit_publish_detail_nominal($detail_map, $ket_key, $unit_key)
{
    if (!isset($detail_map[$ket_key])) {
        return 0.0;
    }
    $row = null;
    if (isset($detail_map[$ket_key][$unit_key])) {
        $row = $detail_map[$ket_key][$unit_key];
    } elseif (function_exists('labarugi_unit_merge_normalize_key')) {
        $norm = labarugi_unit_merge_normalize_key($unit_key);
        if (isset($detail_map[$ket_key][$norm])) {
            $row = $detail_map[$ket_key][$norm];
        }
    }
    if ($row === null) {
        return 0.0;
    }
    if ($row->nominal_update !== null && $row->nominal_update !== '') {
        return (float) $row->nominal_update;
    }
    if ($row->nominal !== null && $row->nominal !== '') {
        return (float) $row->nominal;
    }
    return 0.0;
}
