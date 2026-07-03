<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function labarugi_keterangan_table_name()
{
    return 'sys_labarugi_keterangan';
}

function labarugi_keterangan_status_options()
{
    return array('Title', 'keterangan', 'jumlah', 'jumlah total');
}

function labarugi_keterangan_labarugi_status_options()
{
    return array('rinci', 'sederhana', 'utama');
}

function labarugi_keterangan_default_groups()
{
    return array('PENDAPATAN', 'HPP', 'BEBAN OPERASIONAL', 'PENDAPATAN LAIN', 'BEBAN LAIN', 'PAJAK', 'LAINNYA');
}

function labarugi_keterangan_clear_field_cache($CI, $table)
{
    if (isset($CI->db->data_cache['field_names'][$table])) {
        unset($CI->db->data_cache['field_names'][$table]);
    }
}

function labarugi_keterangan_ensure_columns($CI)
{
    static $done = false;
    if ($done) {
        return true;
    }

    labarugi_keterangan_ensure_table($CI);
    $table = labarugi_keterangan_table_name();
    if (!$CI->db->table_exists($table)) {
        return false;
    }

    labarugi_keterangan_clear_field_cache($CI, $table);

    if (!$CI->db->field_exists('nama_group', $table)) {
        $CI->db->query("ALTER TABLE `" . $table . "` ADD `nama_group` varchar(128) DEFAULT NULL AFTER `status_labarugi`");
        labarugi_keterangan_clear_field_cache($CI, $table);
    }

    if ($CI->db->field_exists('nama_group', $table)) {
        $rows = $CI->db
            ->select('id, uuid_nama_keterangan')
            ->group_start()
            ->where('nama_group IS NULL', null, false)
            ->or_where('nama_group', '')
            ->group_end()
            ->get($table)
            ->result();
        foreach ($rows as $row) {
            $group = labarugi_keterangan_seed_group_for_key($row->uuid_nama_keterangan);
            $CI->db->where('id', (int) $row->id)->update($table, array('nama_group' => $group));
        }
    }

    $done = true;
    return true;
}

function labarugi_keterangan_group_options($CI, $jenis_tab)
{
    labarugi_keterangan_ensure_columns($CI);
    $options = labarugi_keterangan_default_groups();
    $rows = $CI->db
        ->select('nama_group')
        ->distinct()
        ->where('status_labarugi', $jenis_tab)
        ->where('nama_group IS NOT NULL', null, false)
        ->where('nama_group !=', '')
        ->order_by('nama_group', 'ASC')
        ->get(labarugi_keterangan_table_name())
        ->result();

    foreach ($rows as $row) {
        $val = trim((string) $row->nama_group);
        if ($val !== '' && !in_array($val, $options, true)) {
            $options[] = $val;
        }
    }
    return $options;
}

function labarugi_keterangan_generate_uuid()
{
    if (function_exists('com_create_guid')) {
        return str_replace('-', '', trim(com_create_guid(), '{}'));
    }
    return md5(uniqid('labarugi_ket_', true));
}

function labarugi_keterangan_ensure_table($CI)
{
    $table = labarugi_keterangan_table_name();
    if ($CI->db->table_exists($table)) {
        return true;
    }

    $sql = "CREATE TABLE IF NOT EXISTS `" . $table . "` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `uuid_nama_keterangan` varchar(64) NOT NULL,
        `nama_keterangan` varchar(255) NOT NULL,
        `status_keterangan` varchar(32) NOT NULL DEFAULT 'keterangan',
        `status_labarugi` varchar(20) NOT NULL DEFAULT 'rinci',
        `nama_group` varchar(128) DEFAULT NULL,
        `keterangan` text DEFAULT NULL,
        `urutan` int(11) NOT NULL DEFAULT 0,
        `date_input` datetime DEFAULT NULL,
        `date_update` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `uniq_uuid_status_labarugi` (`uuid_nama_keterangan`, `status_labarugi`),
        KEY `idx_status_labarugi_urutan` (`status_labarugi`, `urutan`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

    return (bool) $CI->db->query($sql);
}

function labarugi_keterangan_seed_defaults($CI)
{
    labarugi_keterangan_ensure_table($CI);
    labarugi_keterangan_ensure_columns($CI);
    $table = labarugi_keterangan_table_name();

    if ((int) $CI->db->count_all($table) > 0) {
        return;
    }

    $CI->load->helper('laba_rugi_detail');
    $now = date('Y-m-d H:i:s');
    $urutan = 1;

    foreach (labarugi_detail_keterangan_rinci() as $row) {
        $CI->db->insert($table, array(
            'uuid_nama_keterangan' => $row['key'],
            'nama_keterangan' => $row['label'],
            'status_keterangan' => 'keterangan',
            'status_labarugi' => 'rinci',
            'nama_group' => labarugi_keterangan_seed_group_for_key($row['key']),
            'keterangan' => null,
            'urutan' => $urutan++,
            'date_input' => $now,
            'date_update' => $now,
        ));
    }

    $urutan = 1;
    foreach (labarugi_detail_keterangan_sederhana() as $row) {
        $CI->db->insert($table, array(
            'uuid_nama_keterangan' => $row['key'],
            'nama_keterangan' => $row['label'],
            'status_keterangan' => 'keterangan',
            'status_labarugi' => 'sederhana',
            'nama_group' => labarugi_keterangan_seed_group_for_key($row['key']),
            'keterangan' => null,
            'urutan' => $urutan++,
            'date_input' => $now,
            'date_update' => $now,
        ));
    }
}

function labarugi_keterangan_seed_group_for_key($key)
{
    $key = (string) $key;
    if ($key === 'penjualan') {
        return 'PENDAPATAN';
    }
    if ($key === 'beban_pokok_penjualan') {
        return 'HPP';
    }
    if ($key === 'pajak') {
        return 'PAJAK';
    }
    if ($key === 'total_beban_operasional' || $key === 'total_beban_operasional_umum') {
        return 'BEBAN OPERASIONAL';
    }
    if (strpos($key, 'pendapatan_') === 0) {
        return 'PENDAPATAN LAIN';
    }
    if (strpos($key, 'beban_') === 0) {
        return 'BEBAN OPERASIONAL';
    }
    return 'LAINNYA';
}

function labarugi_keterangan_rows_by_tab($CI, $jenis_tab)
{
    labarugi_keterangan_ensure_table($CI);
    labarugi_keterangan_seed_defaults($CI);

    $CI->db->where('status_labarugi', $jenis_tab);
    $CI->db->order_by('urutan', 'ASC');
    $CI->db->order_by('id', 'ASC');
    $rows = $CI->db->get(labarugi_keterangan_table_name())->result();

    if (empty($rows)) {
        $CI->load->helper('laba_rugi_detail');
        return labarugi_detail_keterangan_by_tab($jenis_tab);
    }

    $out = array();
    foreach ($rows as $row) {
        $out[] = array(
            'key' => $row->uuid_nama_keterangan,
            'label' => $row->nama_keterangan,
            'status_keterangan' => $row->status_keterangan,
        );
    }
    return $out;
}

function labarugi_keterangan_allowed_keys($CI, $jenis_tab)
{
    $keys = array();
    foreach (labarugi_keterangan_rows_by_tab($CI, $jenis_tab) as $row) {
        $keys[] = $row['key'];
    }
    return $keys;
}
