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

function labarugi_kode_akun_setting_list_payload($CI, $uuid_nama_keterangan, $status_labarugi, $nama_keterangan = '')
{
    labarugi_kode_akun_ensure_table($CI);

    $table_ka = labarugi_kode_akun_table_name();
    $sql = 'SELECT ska.kode_akun, ska.nama_akun, (lka.kode_akun IS NOT NULL) AS is_selected'
        . ' FROM sys_kode_akun ska'
        . ' LEFT JOIN `' . $table_ka . '` lka ON lka.kode_akun = ska.kode_akun'
        . ' AND lka.uuid_nama_keterangan = ? AND lka.status_labarugi = ?'
        . ' ORDER BY ska.kode_akun ASC';

    $rows = $CI->db->query($sql, array($uuid_nama_keterangan, $status_labarugi))->result();

    $available = array();
    $terpilih = array();
    foreach ($rows as $row) {
        $item = array(
            'kode_akun' => $row->kode_akun,
            'nama_akun' => isset($row->nama_akun) ? $row->nama_akun : '',
        );
        if (!empty($row->is_selected)) {
            $terpilih[] = $item;
        } else {
            $available[] = $item;
        }
    }

    if ($nama_keterangan === '') {
        $ket_row = $CI->db->select('nama_keterangan')->get_where('sys_labarugi_keterangan', array(
            'uuid_nama_keterangan' => $uuid_nama_keterangan,
            'status_labarugi' => $status_labarugi,
        ))->row();
        if ($ket_row) {
            $nama_keterangan = $ket_row->nama_keterangan;
        }
    }

    return array(
        'nama_keterangan' => $nama_keterangan,
        'available' => $available,
        'terpilih' => $terpilih,
    );
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

function labarugi_kode_akun_unit_table_name()
{
    return 'sys_unit_kode_akun';
}

function labarugi_kode_akun_unit_map_rows($CI, $unit_key, $unit_label = '')
{
    if (!$CI->db->table_exists(labarugi_kode_akun_unit_table_name())) {
        return array();
    }

    $unit_key_u = strtoupper(trim((string) $unit_key));
    $unit_label_u = strtoupper(trim((string) $unit_label));
    if ($unit_key_u === '' && $unit_label_u === '') {
        return array();
    }

    $rows = $CI->db->get(labarugi_kode_akun_unit_table_name())->result();
    $out = array();
    foreach ($rows as $row) {
        $kode_unit_u = strtoupper(trim((string) (isset($row->kode_unit) ? $row->kode_unit : '')));
        $nama_unit_u = strtoupper(trim((string) (isset($row->nama_unit) ? $row->nama_unit : '')));
        $matched = false;

        if ($unit_key_u !== '') {
            if ($kode_unit_u === $unit_key_u || $nama_unit_u === $unit_key_u) {
                $matched = true;
            } elseif ($kode_unit_u !== '' && (strpos($kode_unit_u, $unit_key_u) !== false || strpos($unit_key_u, $kode_unit_u) !== false)) {
                $matched = true;
            }
        }

        if (!$matched && $unit_label_u !== '') {
            if ($nama_unit_u === $unit_label_u || $kode_unit_u === $unit_label_u) {
                $matched = true;
            } elseif ($nama_unit_u !== '' && (strpos($nama_unit_u, $unit_label_u) !== false || strpos($unit_label_u, $nama_unit_u) !== false)) {
                $matched = true;
            }
        }

        if ($matched) {
            $out[] = $row;
        }
    }

    return $out;
}

function labarugi_kode_akun_resolve_unit_kodes($CI, $selected_kodes, $unit_key, $unit_label = '')
{
    if (empty($selected_kodes)) {
        return array();
    }

    $unit_rows = labarugi_kode_akun_unit_map_rows($CI, $unit_key, $unit_label);
    if (empty($unit_rows)) {
        return array();
    }

    $allowed = array();
    foreach ($unit_rows as $row) {
        $kode = trim((string) $row->kode_akun);
        if ($kode !== '') {
            $allowed[$kode] = true;
        }
    }

    $out = array();
    foreach ($selected_kodes as $kode) {
        $kode = (string) $kode;
        if (isset($allowed[$kode])) {
            $out[] = $kode;
        }
    }

    return array_values(array_unique($out));
}

function labarugi_kode_akun_empty_message($code)
{
    $map = array(
        'NO_LABARUGI_KODE_AKUN' => 'Belum ada Setting Kode Akun untuk keterangan ini. Silakan klik tombol "Setting Kode Akun" pada baris keterangan.',
        'NO_UNIT_KODE_AKUN' => 'Belum ada mapping kode akun untuk unit ini di menu Setting Kode Akun Unit (sys_unit_kode_akun). Silakan cek setting kode akun unit.',
        'NO_TRANSACTION' => 'Tidak ada transaksi jurnal untuk kode akun terpilih pada periode ini.',
    );
    return isset($map[$code]) ? $map[$code] : 'Data tidak ditemukan.';
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

function labarugi_kode_akun_buku_besar_nominal($CI, $kode_akun, $tahun, $bulan, $merged_rows = null)
{
    $kode_akun = trim((string) $kode_akun);
    if ($kode_akun === '') {
        return 0.0;
    }
    if ($merged_rows === null) {
        $merged_rows = labarugi_kode_akun_merged_rows($CI, $tahun, $bulan);
    }
    return labarugi_kode_akun_unit_nominal_from_data($merged_rows, array($kode_akun), '', '', false);
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

function labarugi_kode_akun_unit_nominal_from_data($merged_rows, $kodes, $unit_key, $unit_label = '', $filter_by_unit = true)
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
        if ($filter_by_unit && !labarugi_kode_akun_unit_matches($row['pl'], $unit_key, $unit_label)) {
            continue;
        }
        $debet += (float) $row['debet'];
        $kredit += (float) $row['kredit'];
    }
    return $debet - $kredit;
}

function labarugi_kode_akun_transactions_payload($CI, $uuid_nama_keterangan, $status_labarugi, $tahun, $bulan, $unit_key = '', $unit_label = '', $view_mode = 'unit')
{
    $selected_kodes = labarugi_kode_akun_selected_kodes($CI, $uuid_nama_keterangan, $status_labarugi);
    if (empty($selected_kodes)) {
        return array(
            'rows' => array(),
            'total' => 0.0,
            'empty_code' => 'NO_LABARUGI_KODE_AKUN',
            'empty_message' => labarugi_kode_akun_empty_message('NO_LABARUGI_KODE_AKUN'),
        );
    }

    if ($view_mode === 'utama') {
        $effective_kodes = $selected_kodes;
        $filter_by_unit = false;
    } else {
        $effective_kodes = labarugi_kode_akun_resolve_unit_kodes($CI, $selected_kodes, $unit_key, $unit_label);
        $filter_by_unit = true;
        if (empty($effective_kodes)) {
            return array(
                'rows' => array(),
                'total' => 0.0,
                'empty_code' => 'NO_UNIT_KODE_AKUN',
                'empty_message' => labarugi_kode_akun_empty_message('NO_UNIT_KODE_AKUN'),
            );
        }
    }

    $rows = array();
    $total = 0.0;
    foreach (labarugi_kode_akun_merged_rows($CI, $tahun, $bulan) as $row) {
        if (!in_array((string) $row['kode_akun'], $effective_kodes, true)) {
            continue;
        }
        if ($filter_by_unit && !labarugi_kode_akun_unit_matches($row['pl'], $unit_key, $unit_label)) {
            continue;
        }
        $debet = (float) (isset($row['debet']) ? $row['debet'] : 0);
        $kredit = (float) (isset($row['kredit']) ? $row['kredit'] : 0);
        $total += ($debet - $kredit);
        $rows[] = array(
            'tanggal' => isset($row['tanggal']) ? $row['tanggal'] : '',
            'pl' => isset($row['pl']) ? $row['pl'] : '',
            'kode' => isset($row['kode']) ? $row['kode'] : '',
            'kode_akun' => isset($row['kode_akun']) ? $row['kode_akun'] : '',
            'nama_akun' => isset($row['nama_akun']) ? $row['nama_akun'] : '',
            'debet' => $debet,
            'kredit' => $kredit,
            'debet_formatted' => labarugi_kode_akun_format_nominal($debet),
            'kredit_formatted' => labarugi_kode_akun_format_nominal($kredit),
            'source_key' => isset($row['source_key']) ? $row['source_key'] : '',
        );
    }

    if (empty($rows)) {
        return array(
            'rows' => array(),
            'total' => 0.0,
            'empty_code' => 'NO_TRANSACTION',
            'empty_message' => labarugi_kode_akun_empty_message('NO_TRANSACTION'),
        );
    }

    return array(
        'rows' => $rows,
        'total' => $total,
        'empty_code' => '',
        'empty_message' => '',
        'effective_kodes' => $effective_kodes,
    );
}

function labarugi_kode_akun_unit_nominal($CI, $uuid_nama_keterangan, $status_labarugi, $unit_key, $unit_label, $tahun, $bulan, $view_mode = 'unit')
{
    $kodes = labarugi_kode_akun_selected_kodes($CI, $uuid_nama_keterangan, $status_labarugi);
    if ($view_mode === 'utama') {
        $effective_kodes = $kodes;
        $filter_by_unit = false;
    } else {
        $effective_kodes = labarugi_kode_akun_resolve_unit_kodes($CI, $kodes, $unit_key, $unit_label);
        $filter_by_unit = true;
    }

    return labarugi_kode_akun_unit_nominal_from_data(
        labarugi_kode_akun_merged_rows($CI, $tahun, $bulan),
        $effective_kodes,
        $unit_key,
        $unit_label,
        $filter_by_unit
    );
}

function labarugi_kode_akun_unit_transactions($CI, $uuid_nama_keterangan, $status_labarugi, $unit_key, $unit_label, $tahun, $bulan, $view_mode = 'unit')
{
    $payload = labarugi_kode_akun_transactions_payload($CI, $uuid_nama_keterangan, $status_labarugi, $tahun, $bulan, $unit_key, $unit_label, $view_mode);
    return isset($payload['rows']) ? $payload['rows'] : array();
}
