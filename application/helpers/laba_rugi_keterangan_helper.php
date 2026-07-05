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

function labarugi_keterangan_master_structure()
{
    return array(
        array('key' => 'grp_penjualan_dan_pengurangnya', 'label' => 'Penjualan dan Pengurangnya', 'status_keterangan' => 'Title', 'nama_group' => 'PENJUALAN'),
        array('key' => 'penjualan', 'label' => 'Penjualan', 'status_keterangan' => 'keterangan', 'nama_group' => 'PENJUALAN', 'tbl_field' => 'penjualan'),
        array('key' => 'retur_penjualan', 'label' => 'Retur Penjualan', 'status_keterangan' => 'keterangan', 'nama_group' => 'PENJUALAN'),
        array('key' => 'total_penjualan_bersih', 'label' => 'Total Penjualan Bersih', 'status_keterangan' => 'jumlah total', 'nama_group' => 'PENJUALAN', 'row_style' => 'subtotal', 'is_calculated' => true),
        array('key' => 'grp_beban_pokok_penjualan', 'label' => 'Beban Pokok Penjualan', 'status_keterangan' => 'Title', 'nama_group' => 'HPP'),
        array('key' => 'boi_penjualan', 'label' => 'BOI Penjualan', 'status_keterangan' => 'keterangan', 'nama_group' => 'HPP'),
        array('key' => 'beban_pokok_penjualan', 'label' => 'Beban Pokok Penjualan', 'status_keterangan' => 'keterangan', 'nama_group' => 'HPP', 'tbl_field' => 'beban_pokok_penjualan'),
        array('key' => 'total_beban_pokok_penjualan', 'label' => 'Total Beban Pokok Penjualan', 'status_keterangan' => 'jumlah total', 'nama_group' => 'HPP', 'row_style' => 'subtotal', 'is_calculated' => true),
        array('key' => 'laba_rugi_bruto', 'label' => 'Laba/ Rugi Bruto', 'status_keterangan' => 'jumlah total', 'nama_group' => 'LABA BRUTO', 'row_style' => 'summary', 'is_calculated' => true),
        array('key' => 'beban_operasional_promosi', 'label' => 'Beban Operasional Promosi', 'status_keterangan' => 'keterangan', 'nama_group' => 'BEBAN OPERASIONAL', 'tbl_field' => 'beban_operasional_promosi'),
        array('key' => 'beban_perjalanan_dinas', 'label' => 'Beban Perjalanan Dinas', 'status_keterangan' => 'keterangan', 'nama_group' => 'BEBAN OPERASIONAL', 'tbl_field' => 'beban_perjalanan_dinas'),
        array('key' => 'beban_transportasi', 'label' => 'Beban Transportasi', 'status_keterangan' => 'keterangan', 'nama_group' => 'BEBAN OPERASIONAL', 'tbl_field' => 'beban_transportasi'),
        array('key' => 'beban_pemeliharaan', 'label' => 'Beban Pemeliharaan', 'status_keterangan' => 'keterangan', 'nama_group' => 'BEBAN OPERASIONAL', 'tbl_field' => 'beban_pemeliharaan'),
        array('key' => 'beban_depresiasi_dan_amortisasi', 'label' => 'Beban Depresiasi dan Amortisasi', 'status_keterangan' => 'keterangan', 'nama_group' => 'BEBAN OPERASIONAL', 'tbl_field' => 'beban_depresiasi_dan_amortisasi'),
        array('key' => 'beban_operasional_karyawan', 'label' => 'Total Beban Operasional Karyawan', 'status_keterangan' => 'keterangan', 'nama_group' => 'BEBAN OPERASIONAL', 'tbl_field' => 'beban_operasional_karyawan'),
        array('key' => 'total_beban_operasional_umum', 'label' => 'Total Beban Operasional Umum', 'status_keterangan' => 'keterangan', 'nama_group' => 'BEBAN OPERASIONAL', 'tbl_field' => 'total_beban_operasional_umum'),
        array('key' => 'total_beban_operasional', 'label' => 'Total Beban Operasional', 'status_keterangan' => 'jumlah total', 'nama_group' => 'BEBAN OPERASIONAL', 'row_style' => 'summary', 'is_calculated' => true),
        array('key' => 'laba_rugi_operasional', 'label' => 'Laba/ Rugi Operasional', 'status_keterangan' => 'jumlah total', 'nama_group' => 'LABA OPERASIONAL', 'row_style' => 'summary', 'is_calculated' => true),
        array('key' => 'pendapatan_bunga_bank', 'label' => 'Pendapatan Bunga Bank', 'status_keterangan' => 'keterangan', 'nama_group' => 'PENDAPATAN LAIN', 'tbl_field' => 'pendapatan_bunga_bank'),
        array('key' => 'pendapatan_rupa_rupa', 'label' => 'Pendapatan Rupa - Rupa', 'status_keterangan' => 'keterangan', 'nama_group' => 'PENDAPATAN LAIN', 'tbl_field' => 'pendapatan_rupa_rupa'),
        array('key' => 'total_pendapatan_lain_lain', 'label' => 'Total Pendapatan Lain - Lain', 'status_keterangan' => 'jumlah total', 'nama_group' => 'PENDAPATAN LAIN', 'row_style' => 'subtotal', 'is_calculated' => true),
        array('key' => 'beban_bunga_dan_adm_bank', 'label' => 'Beban Bunga dan Adm Bank', 'status_keterangan' => 'keterangan', 'nama_group' => 'BEBAN LAIN', 'tbl_field' => 'beban_bunga_dan_adm_bank'),
        array('key' => 'beban_rupa_rupa', 'label' => 'Beban Rupa - rupa', 'status_keterangan' => 'keterangan', 'nama_group' => 'BEBAN LAIN', 'tbl_field' => 'beban_rupa_rupa'),
        array('key' => 'total_beban_lain_lain', 'label' => 'Total Beban Lain- lain', 'status_keterangan' => 'jumlah total', 'nama_group' => 'BEBAN LAIN', 'row_style' => 'subtotal', 'is_calculated' => true),
        array('key' => 'laba_rugi_sebelum_pajak', 'label' => 'Laba/ Rugi Sebelum Pajak', 'status_keterangan' => 'jumlah total', 'nama_group' => 'LABA SEBELUM PAJAK', 'row_style' => 'summary', 'is_calculated' => true),
    );
}

function labarugi_keterangan_is_title_row($row)
{
    if (is_array($row)) {
        $status = isset($row['status_keterangan']) ? $row['status_keterangan'] : '';
    } else {
        $status = isset($row->status_keterangan) ? $row->status_keterangan : '';
    }
    return strcasecmp((string) $status, 'Title') === 0;
}

function labarugi_keterangan_is_input_row($row)
{
    return !labarugi_keterangan_is_title_row($row);
}

function labarugi_keterangan_input_keys()
{
    $keys = array();
    foreach (labarugi_keterangan_master_structure() as $row) {
        if (labarugi_keterangan_is_input_row($row)) {
            $keys[] = $row['key'];
        }
    }
    return $keys;
}

function labarugi_keterangan_calc_definitions()
{
    return array(
        'total_penjualan_bersih' => array(
            'type' => 'sum',
            'parts' => array('penjualan', 'retur_penjualan'),
        ),
        'total_beban_pokok_penjualan' => array(
            'type' => 'sum',
            'parts' => array('boi_penjualan', 'beban_pokok_penjualan'),
        ),
        'laba_rugi_bruto' => array(
            'type' => 'subtract',
            'parts' => array('total_penjualan_bersih', 'total_beban_pokok_penjualan'),
        ),
        'total_beban_operasional' => array(
            'type' => 'sum',
            'parts' => array(
                'beban_operasional_promosi',
                'beban_perjalanan_dinas',
                'beban_transportasi',
                'beban_pemeliharaan',
                'beban_depresiasi_dan_amortisasi',
                'beban_operasional_karyawan',
                'total_beban_operasional_umum',
            ),
        ),
        'laba_rugi_operasional' => array(
            'type' => 'subtract',
            'parts' => array('laba_rugi_bruto', 'total_beban_operasional'),
        ),
        'total_pendapatan_lain_lain' => array(
            'type' => 'sum',
            'parts' => array('pendapatan_bunga_bank', 'pendapatan_rupa_rupa'),
        ),
        'total_beban_lain_lain' => array(
            'type' => 'sum',
            'parts' => array('beban_bunga_dan_adm_bank', 'beban_rupa_rupa'),
        ),
        'laba_rugi_sebelum_pajak' => array(
            'type' => 'add_sub',
            'parts' => array('laba_rugi_operasional', 'total_pendapatan_lain_lain', 'total_beban_lain_lain'),
        ),
    );
}

function labarugi_keterangan_calc_order()
{
    return array(
        'total_penjualan_bersih',
        'total_beban_pokok_penjualan',
        'laba_rugi_bruto',
        'total_beban_operasional',
        'laba_rugi_operasional',
        'total_pendapatan_lain_lain',
        'total_beban_lain_lain',
        'laba_rugi_sebelum_pajak',
    );
}

function labarugi_keterangan_is_calculated_key($key)
{
    return isset(labarugi_keterangan_calc_definitions()[(string) $key]);
}

function labarugi_keterangan_row_style($key)
{
    foreach (labarugi_keterangan_master_structure() as $row) {
        if ($row['key'] === (string) $key) {
            if (!empty($row['row_style'])) {
                return $row['row_style'];
            }
            if (labarugi_keterangan_is_calculated_key($key)) {
                return 'subtotal';
            }
            return 'input';
        }
    }
    return 'input';
}

function labarugi_keterangan_is_summary_row_key($key)
{
    return labarugi_keterangan_row_style($key) === 'summary';
}

function labarugi_keterangan_calc_display_tier($key)
{
    $key = (string) $key;
    $major_keys = array(
        'laba_rugi_bruto',
        'laba_rugi_operasional',
        'laba_rugi_sebelum_pajak',
    );
    if (in_array($key, $major_keys, true)) {
        return 'major';
    }
    if (labarugi_keterangan_is_calculated_key($key)) {
        return 'subtotal';
    }
    return '';
}

function labarugi_keterangan_calc_display_tier_class($key, $prefix = 'labarugi-calc-tier')
{
    $tier = labarugi_keterangan_calc_display_tier($key);
    if ($tier === '') {
        return '';
    }
    return $prefix . '-' . $tier;
}

function labarugi_keterangan_sync_master_structure($CI, $jenis_tab)
{
    if (!in_array($jenis_tab, labarugi_keterangan_labarugi_status_options(), true)) {
        return false;
    }

    labarugi_keterangan_ensure_table($CI);
    labarugi_keterangan_ensure_columns($CI);
    $table = labarugi_keterangan_table_name();
    $now = date('Y-m-d H:i:s');
    $urutan = 1;

    foreach (labarugi_keterangan_master_structure() as $def) {
        $existing = $CI->db->get_where($table, array(
            'uuid_nama_keterangan' => $def['key'],
            'status_labarugi' => $jenis_tab,
        ))->row();

        $payload = array(
            'nama_keterangan' => $def['label'],
            'status_keterangan' => $def['status_keterangan'],
            'nama_group' => $def['nama_group'],
            'urutan' => $urutan++,
            'date_update' => $now,
        );

        if ($existing) {
            $CI->db->where('id', (int) $existing->id)->update($table, $payload);
        } else {
            $payload['uuid_nama_keterangan'] = $def['key'];
            $payload['status_labarugi'] = $jenis_tab;
            $payload['date_input'] = $now;
            $CI->db->insert($table, $payload);
        }
    }

    return true;
}

function labarugi_keterangan_tbl_field_for_key($key)
{
    foreach (labarugi_keterangan_master_structure() as $row) {
        if ($row['key'] === $key && !empty($row['tbl_field'])) {
            return $row['tbl_field'];
        }
    }
    return '';
}
function labarugi_keterangan_seed_defaults($CI)
{
    labarugi_keterangan_ensure_table($CI);
    labarugi_keterangan_ensure_columns($CI);

    foreach (array('rinci', 'sederhana', 'utama') as $jenis_tab) {
        labarugi_keterangan_sync_master_structure($CI, $jenis_tab);
    }
}

function labarugi_keterangan_seed_utama($CI)
{
    labarugi_keterangan_sync_master_structure($CI, 'utama');
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
    labarugi_keterangan_sync_master_structure($CI, $jenis_tab);

    $CI->db->where('status_labarugi', $jenis_tab);
    $CI->db->order_by('urutan', 'ASC');
    $CI->db->order_by('id', 'ASC');
    $rows = $CI->db->get(labarugi_keterangan_table_name())->result();

    $master_flip = array();
    foreach (labarugi_keterangan_master_structure() as $def) {
        $master_flip[$def['key']] = $def;
    }

    $out = array();
    foreach ($rows as $row) {
        $key = (string) $row->uuid_nama_keterangan;
        if (!isset($master_flip[$key])) {
            continue;
        }
        $out[] = array(
            'key' => $key,
            'label' => $row->nama_keterangan,
            'status_keterangan' => $row->status_keterangan,
            'nama_group' => $row->nama_group,
        );
    }

    if (empty($out)) {
        foreach (labarugi_keterangan_master_structure() as $def) {
            $out[] = array(
                'key' => $def['key'],
                'label' => $def['label'],
                'status_keterangan' => $def['status_keterangan'],
                'nama_group' => $def['nama_group'],
            );
        }
    }

    return $out;
}

function labarugi_keterangan_allowed_keys($CI, $jenis_tab)
{
    return labarugi_keterangan_input_keys();
}
