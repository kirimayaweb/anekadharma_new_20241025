<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function labarugi_kode_akun_table_name()
{
    return 'sys_labarugi_kode_akun';
}

function labarugi_kode_akun_generate_uuid()
{
    if (function_exists('com_create_guid')) {
        return str_replace('-', '', trim(com_create_guid(), '{}'));
    }
    return md5(uniqid('labarugi_ka_', true));
}

function labarugi_kode_akun_ensure_table($CI)
{
    $table = labarugi_kode_akun_table_name();
    if ($CI->db->table_exists($table)) {
        return true;
    }

    $sql = "CREATE TABLE IF NOT EXISTS `" . $table . "` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `uuid_setting_kode_akun_labarugi` varchar(64) NOT NULL,
        `uuid_nama_keterangan` varchar(128) NOT NULL,
        `nama_keterangan` varchar(255) DEFAULT NULL,
        `kode_akun` varchar(32) NOT NULL,
        `status_labarugi` varchar(20) NOT NULL DEFAULT 'rinci',
        `date_input` datetime DEFAULT NULL,
        `date_update` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `uniq_labarugi_ket_akun` (`uuid_nama_keterangan`, `kode_akun`, `status_labarugi`),
        UNIQUE KEY `uniq_uuid_setting_ka` (`uuid_setting_kode_akun_labarugi`),
        KEY `idx_labarugi_ka_ket` (`uuid_nama_keterangan`, `status_labarugi`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

    return (bool) $CI->db->query($sql);
}

function labarugi_kode_akun_selected_kodes($CI, $uuid_nama_keterangan, $status_labarugi)
{
    labarugi_kode_akun_ensure_table($CI);
    $rows = $CI->db->get_where(labarugi_kode_akun_table_name(), array(
        'uuid_nama_keterangan' => $uuid_nama_keterangan,
        'status_labarugi' => $status_labarugi,
    ))->result();

    $kodes = array();
    foreach ($rows as $row) {
        $kodes[] = (string) $row->kode_akun;
    }
    return $kodes;
}

function labarugi_kode_akun_selected_map_by_tab($CI, $status_labarugi)
{
    labarugi_kode_akun_ensure_table($CI);
    $rows = $CI->db
        ->select('uuid_nama_keterangan, kode_akun')
        ->where('status_labarugi', $status_labarugi)
        ->get(labarugi_kode_akun_table_name())
        ->result();

    $map = array();
    foreach ($rows as $row) {
        $uuid = (string) $row->uuid_nama_keterangan;
        if (!isset($map[$uuid])) {
            $map[$uuid] = array();
        }
        $map[$uuid][] = (string) $row->kode_akun;
    }
    return $map;
}

function labarugi_kode_akun_format_nominal($value)
{
    return number_format((float) $value, 2, ',', '.');
}

function labarugi_kode_akun_unit_matches($pl, $unit_key, $unit_label = '')
{
    $pl_norm = strtoupper(trim((string) $pl));
    if ($pl_norm === '') {
        return false;
    }
    $candidates = array();
    foreach (array($unit_key, $unit_label) as $val) {
        $v = strtoupper(trim((string) $val));
        if ($v !== '') {
            $candidates[] = $v;
        }
    }
    foreach ($candidates as $c) {
        if ($pl_norm === $c || strpos($pl_norm, $c) !== false || strpos($c, $pl_norm) !== false) {
            return true;
        }
    }
    return false;
}

function labarugi_kode_akun_merged_rows($CI, $tahun, $bulan)
{
    static $cache = array();
    $tahun = (int) $tahun;
    $bulan = (int) $bulan;
    $key = $tahun . '-' . $bulan;
    if (!isset($cache[$key])) {
        $CI->load->helper('buku_besar_list');
        $cache[$key] = buku_besar_merge_source_rows($CI, $bulan, $tahun, '');
    }
    return $cache[$key];
}

function labarugi_kode_akun_neraca_saldo_nominal($CI, $kode_akun, $tahun, $bulan, $rows_by_kode = null)
{
    $CI->load->helper('neraca_kode_akun');
    if ($rows_by_kode === null) {
        $saldo_map = neraca_get_neraca_saldo_map($CI, $tahun, $bulan);
        $rows_by_kode = $saldo_map['rows_by_kode'];
    }
    return neraca_kode_akun_nominal_saldo($CI, $kode_akun, 'kas', $tahun, $bulan, $rows_by_kode);
}

function labarugi_kode_akun_unit_nominal_from_data($merged_rows, $kodes, $unit_key, $unit_label = '')
{
    if (empty($kodes) || empty($merged_rows)) {
        return 0.0;
    }
    $kode_flip = array_flip($kodes);
    $debet = 0.0;
    $kredit = 0.0;
    foreach ($merged_rows as $row) {
        if (!isset($kode_flip[(string) $row['kode_akun']])) {
            continue;
        }
        if (!labarugi_kode_akun_unit_matches($row['pl'], $unit_key, $unit_label)) {
            continue;
        }
        $debet += (float) $row['debet'];
        $kredit += (float) $row['kredit'];
    }
    return $debet - $kredit;
}

function labarugi_kode_akun_unit_nominal($CI, $uuid_nama_keterangan, $status_labarugi, $unit_key, $unit_label, $tahun, $bulan)
{
    $kodes = labarugi_kode_akun_selected_kodes($CI, $uuid_nama_keterangan, $status_labarugi);
    return labarugi_kode_akun_unit_nominal_from_data(
        labarugi_kode_akun_merged_rows($CI, $tahun, $bulan),
        $kodes,
        $unit_key,
        $unit_label
    );
}

function labarugi_kode_akun_unit_transactions($CI, $uuid_nama_keterangan, $status_labarugi, $unit_key, $unit_label, $tahun, $bulan)
{
    $kodes = labarugi_kode_akun_selected_kodes($CI, $uuid_nama_keterangan, $status_labarugi);
    if (empty($kodes)) {
        return array();
    }

    $out = array();
    foreach (labarugi_kode_akun_merged_rows($CI, $tahun, $bulan) as $row) {
        if (!in_array((string) $row['kode_akun'], $kodes, true)) {
            continue;
        }
        if (!labarugi_kode_akun_unit_matches($row['pl'], $unit_key, $unit_label)) {
            continue;
        }
        $out[] = array(
            'tanggal' => isset($row['tanggal']) ? $row['tanggal'] : '',
            'pl' => isset($row['pl']) ? $row['pl'] : '',
            'kode' => isset($row['kode']) ? $row['kode'] : '',
            'kode_akun' => isset($row['kode_akun']) ? $row['kode_akun'] : '',
            'nama_akun' => isset($row['nama_akun']) ? $row['nama_akun'] : '',
            'debet' => (float) (isset($row['debet']) ? $row['debet'] : 0),
            'kredit' => (float) (isset($row['kredit']) ? $row['kredit'] : 0),
            'debet_formatted' => labarugi_kode_akun_format_nominal(isset($row['debet']) ? $row['debet'] : 0),
            'kredit_formatted' => labarugi_kode_akun_format_nominal(isset($row['kredit']) ? $row['kredit'] : 0),
            'source_key' => isset($row['source_key']) ? $row['source_key'] : '',
        );
    }

    return $out;
}
