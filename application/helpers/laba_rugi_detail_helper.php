<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function labarugi_detail_table_name()
{
    return 'tbl_laba_rugi_detail';
}

function labarugi_detail_ensure_table($CI)
{
    $table = labarugi_detail_table_name();
    if ($CI->db->table_exists($table)) {
        labarugi_detail_ensure_columns($CI);
        return true;
    }

    $sql = "CREATE TABLE IF NOT EXISTS `" . $table . "` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `tanggal` date NOT NULL,
        `uuid_laba_rugi` varchar(255) NOT NULL,
        `nama_laba_rugi` varchar(128) NOT NULL,
        `unit` varchar(128) DEFAULT NULL,
        `nominal` double(15,2) DEFAULT NULL,
        `nominal_update` double(15,2) DEFAULT NULL,
        `auto_sistem` double(15,2) DEFAULT NULL,
        `status_sync_auto` tinyint(1) NOT NULL DEFAULT 0,
        `keterangan_data` text DEFAULT NULL,
        `jenis_tab` varchar(20) NOT NULL DEFAULT 'rinci',
        `tahun_transaksi` int(4) NOT NULL,
        `bulan_transaksi` int(2) NOT NULL,
        `date_input` datetime DEFAULT NULL,
        `date_update` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `uniq_labarugi_detail` (`uuid_laba_rugi`, `nama_laba_rugi`, `unit`, `jenis_tab`),
        KEY `idx_labarugi_periode` (`tahun_transaksi`, `bulan_transaksi`, `jenis_tab`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

    $created = (bool) $CI->db->query($sql);
    if ($created) {
        labarugi_detail_ensure_columns($CI);
    }
    return $created;
}

function labarugi_detail_clear_field_cache($CI, $table)
{
    if (isset($CI->db->data_cache['field_names'][$table])) {
        unset($CI->db->data_cache['field_names'][$table]);
    }
}

function labarugi_detail_ensure_columns($CI)
{
    static $done = false;
    if ($done) {
        return true;
    }

    $table = labarugi_detail_table_name();
    if (!$CI->db->table_exists($table)) {
        return false;
    }

    labarugi_detail_clear_field_cache($CI, $table);

    if (!$CI->db->field_exists('status_sync_auto', $table)) {
        $after = $CI->db->field_exists('auto_sistem', $table) ? 'auto_sistem' : 'nominal_update';
        $CI->db->query("ALTER TABLE `" . $table . "` ADD `status_sync_auto` tinyint(1) NOT NULL DEFAULT 0 AFTER `" . $after . "`");
        labarugi_detail_clear_field_cache($CI, $table);
    }

    $done = true;
    return true;
}

function labarugi_detail_row_sync_auto($row)
{
    if (!$row || !isset($row->status_sync_auto)) {
        return 0;
    }
    return (int) $row->status_sync_auto === 1 ? 1 : 0;
}

function labarugi_detail_keterangan_rinci()
{
    return array(
        array('key' => 'penjualan', 'label' => 'PENJUALAN'),
        array('key' => 'beban_pokok_penjualan', 'label' => 'BEBAN POKOK PENJUALAN'),
        array('key' => 'beban_depresiasi_dan_amortisasi', 'label' => 'BEBAN DEPRESIASI DAN AMORTISASI'),
        array('key' => 'beban_operasional_karyawan', 'label' => 'BEBAN OPERASIONAL KARYAWAN'),
        array('key' => 'beban_operasional_promosi', 'label' => 'BEBAN OPERASIONAL PROMOSI'),
        array('key' => 'beban_perjalanan_dinas', 'label' => 'BEBAN PERJALANAN DINAS'),
        array('key' => 'beban_transportasi', 'label' => 'BEBAN TRANSPORTASI'),
        array('key' => 'beban_pemeliharaan', 'label' => 'BEBAN PEMELIHARAAN'),
        array('key' => 'total_beban_operasional_umum', 'label' => 'TOTAL BEBAN OPERASIONAL UMUM'),
        array('key' => 'pendapatan_bunga_bank', 'label' => 'PENDAPATAN BUNGA BANK'),
        array('key' => 'pendapatan_rupa_rupa', 'label' => 'PENDAPATAN RUPA-RUPA'),
        array('key' => 'beban_bunga_dan_adm_bank', 'label' => 'BEBAN BUNGA DAN ADM BANK'),
        array('key' => 'beban_rupa_rupa', 'label' => 'BEBAN RUPA-RUPA'),
        array('key' => 'pajak', 'label' => 'PAJAK'),
    );
}

function labarugi_detail_keterangan_sederhana()
{
    return array(
        array('key' => 'penjualan', 'label' => 'PENJUALAN'),
        array('key' => 'beban_pokok_penjualan', 'label' => 'BEBAN POKOK PENJUALAN'),
        array('key' => 'total_beban_operasional', 'label' => 'TOTAL BEBAN OPERASIONAL'),
        array('key' => 'pajak', 'label' => 'PAJAK'),
    );
}

function labarugi_detail_keterangan_by_tab($jenis_tab)
{
    if ($jenis_tab === 'sederhana') {
        return labarugi_detail_keterangan_sederhana();
    }
    if ($jenis_tab === 'utama') {
        return labarugi_detail_keterangan_rinci();
    }
    return labarugi_detail_keterangan_rinci();
}

function labarugi_detail_parse_nominal($raw)
{
    $raw = trim((string) $raw);
    if ($raw === '') {
        return null;
    }
    $raw = str_replace('.', '', $raw);
    $raw = str_replace(',', '.', $raw);
    if (!is_numeric($raw)) {
        return null;
    }
    return (float) $raw;
}

function labarugi_detail_format_nominal($value)
{
    if ($value === null || $value === '') {
        return '';
    }
    return number_format((float) $value, 2, ',', '.');
}

function labarugi_detail_tanggal_periode($tahun, $bulan)
{
    $tahun = (int) $tahun;
    $bulan = (int) $bulan;
    if ($bulan < 1 || $bulan > 12) {
        return date('Y-m-d');
    }
    return date('Y-m-t', strtotime(sprintf('%04d-%02d-01', $tahun, $bulan)));
}

function labarugi_detail_resolve_parent_uuid($CI, $tahun, $bulan)
{
    $tahun = (int) $tahun;
    $bulan = (int) $bulan;

    $row = $CI->db->get_where('tbl_laba_rugi', array(
        'tahun_transaksi' => $tahun,
        'bulan_transaksi' => $bulan,
    ))->row();

    if ($row && !empty($row->uuid_data_laba_rugi)) {
        return $row->uuid_data_laba_rugi;
    }

    $CI->load->model('Tbl_laba_rugi_model');
    $id = $CI->Tbl_laba_rugi_model->insert(array(
        'date_input' => date('Y-m-d H:i:s'),
        'date_transaksi' => date('Y-m-d H:i:s'),
        'tahun_transaksi' => $tahun,
        'bulan_transaksi' => $bulan,
    ));

    $row = $CI->Tbl_laba_rugi_model->get_by_id($id);
    return $row ? $row->uuid_data_laba_rugi : null;
}

function labarugi_detail_load_map($CI, $uuid_laba_rugi, $jenis_tab)
{
    $map = array();
    if ($uuid_laba_rugi === null || $uuid_laba_rugi === '') {
        return $map;
    }

    labarugi_detail_ensure_table($CI);
    $rows = $CI->db->get_where(labarugi_detail_table_name(), array(
        'uuid_laba_rugi' => $uuid_laba_rugi,
        'jenis_tab' => $jenis_tab,
    ))->result();

    foreach ($rows as $row) {
        $unit_key = ($row->unit === null || $row->unit === '') ? '' : (string) $row->unit;
        if (!isset($map[$row->nama_laba_rugi])) {
            $map[$row->nama_laba_rugi] = array();
        }
        $map[$row->nama_laba_rugi][$unit_key] = $row;
    }

    return $map;
}

function labarugi_detail_unit_key($unit_row)
{
    if (is_object($unit_row)) {
        $kode = isset($unit_row->kode_unit) ? trim((string) $unit_row->kode_unit) : '';
        if ($kode !== '') {
            return $kode;
        }
        return isset($unit_row->nama_unit) ? trim((string) $unit_row->nama_unit) : '';
    }
    return trim((string) $unit_row);
}
