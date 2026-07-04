<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function bb_ska_table_setting()
{
    return 'sys_bb_setting_tabel';
}

function bb_ska_table_record()
{
    return 'sys_bb_setting_kode_akun_record';
}

function bb_ska_generate_uuid()
{
    if (function_exists('com_create_guid')) {
        return str_replace('-', '', trim(com_create_guid(), '{}'));
    }
    return md5(uniqid('bb_ska_', true));
}

function bb_ska_ensure_tables($CI)
{
    $t1 = bb_ska_table_setting();
    if (!$CI->db->table_exists($t1)) {
        $CI->db->query("CREATE TABLE IF NOT EXISTS `" . $t1 . "` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `uuid_setting` varchar(64) NOT NULL,
            `nama_tabel` varchar(128) NOT NULL,
            `kolom_nilai` varchar(64) DEFAULT NULL,
            `kolom_pk` varchar(64) DEFAULT 'id',
            `urutan` int(11) NOT NULL DEFAULT 0,
            `date_input` datetime DEFAULT NULL,
            `date_update` datetime DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uniq_nama_tabel` (`nama_tabel`),
            UNIQUE KEY `uniq_uuid_setting_tabel` (`uuid_setting`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
    }

    $t2 = bb_ska_table_record();
    if (!$CI->db->table_exists($t2)) {
        $CI->db->query("CREATE TABLE IF NOT EXISTS `" . $t2 . "` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `uuid_setting` varchar(64) NOT NULL,
            `nama_tabel` varchar(128) NOT NULL,
            `record_id` varchar(64) NOT NULL,
            `kode_akun` varchar(32) NOT NULL DEFAULT '',
            `date_input` datetime DEFAULT NULL,
            `date_update` datetime DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uniq_tabel_record` (`nama_tabel`, `record_id`),
            UNIQUE KEY `uniq_uuid_setting_record` (`uuid_setting`),
            KEY `idx_kode_akun` (`kode_akun`),
            KEY `idx_nama_tabel` (`nama_tabel`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
    }

    return true;
}

function bb_ska_format_nominal($value)
{
    return number_format((float) $value, 2, ',', '.');
}

function bb_ska_is_valid_table_name($table)
{
    return (bool) preg_match('/^[a-zA-Z0-9_]+$/', (string) $table);
}

function bb_ska_list_all_db_tables($CI)
{
    $CI->load->helper('pembelian_persediaan');
    return persediaan_compare_list_db_tables($CI);
}

function bb_ska_list_selected_tables($CI)
{
    bb_ska_ensure_tables($CI);
    return $CI->db
        ->order_by('urutan', 'ASC')
        ->order_by('nama_tabel', 'ASC')
        ->get(bb_ska_table_setting())
        ->result();
}

function bb_ska_get_selected_table_names($CI)
{
    $names = array();
    foreach (bb_ska_list_selected_tables($CI) as $row) {
        $names[] = (string) $row->nama_tabel;
    }
    return $names;
}

function bb_ska_save_selected_tables($CI, $tables)
{
    bb_ska_ensure_tables($CI);
    if (!is_array($tables)) {
        $tables = array();
    }

    $valid_all = bb_ska_list_all_db_tables($CI);
    $valid_flip = array_flip($valid_all);
    $clean = array();
    foreach ($tables as $tbl) {
        $tbl = trim((string) $tbl);
        if ($tbl !== '' && isset($valid_flip[$tbl])) {
            $clean[$tbl] = $tbl;
        }
    }
    $clean = array_values($clean);

    $existing = bb_ska_list_selected_tables($CI);
    $existing_map = array();
    foreach ($existing as $row) {
        $existing_map[(string) $row->nama_tabel] = $row;
    }

    $now = date('Y-m-d H:i:s');
    $urutan = 0;
    foreach ($clean as $tbl) {
        $urutan++;
        if (isset($existing_map[$tbl])) {
            $CI->db->where('id', (int) $existing_map[$tbl]->id)->update(bb_ska_table_setting(), array(
                'urutan' => $urutan,
                'date_update' => $now,
            ));
            unset($existing_map[$tbl]);
        } else {
            $meta = bb_ska_detect_table_meta($CI, $tbl);
            $CI->db->insert(bb_ska_table_setting(), array(
                'uuid_setting' => bb_ska_generate_uuid(),
                'nama_tabel' => $tbl,
                'kolom_nilai' => $meta['kolom_nilai'],
                'kolom_pk' => $meta['kolom_pk'],
                'urutan' => $urutan,
                'date_input' => $now,
                'date_update' => $now,
            ));
        }
    }

    foreach ($existing_map as $row) {
        $CI->db->where('id', (int) $row->id)->delete(bb_ska_table_setting());
    }

    return array(
        'ok' => true,
        'message' => 'Setting tabel berhasil disimpan.',
        'tables' => bb_ska_tables_for_ui($CI),
    );
}

function bb_ska_tables_for_ui($CI)
{
    $out = array();
    foreach (bb_ska_list_selected_tables($CI) as $row) {
        $out[] = array(
            'nama_tabel' => (string) $row->nama_tabel,
            'kolom_nilai' => (string) $row->kolom_nilai,
            'kolom_pk' => (string) $row->kolom_pk,
            'urutan' => (int) $row->urutan,
        );
    }
    return $out;
}

function bb_ska_list_tables_with_selection($CI)
{
    $selected = array_flip(bb_ska_get_selected_table_names($CI));
    $rows = array();
    foreach (bb_ska_list_all_db_tables($CI) as $tbl) {
        $rows[] = array(
            'nama_tabel' => $tbl,
            'selected' => isset($selected[$tbl]) ? 1 : 0,
        );
    }
    return $rows;
}

function bb_ska_get_table_columns($CI, $table)
{
    if (!bb_ska_is_valid_table_name($table) || !$CI->db->table_exists($table)) {
        return array();
    }
    $db_name = $CI->db->database;
    $rows = $CI->db->query(
        "SELECT COLUMN_NAME FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?
        ORDER BY ORDINAL_POSITION ASC",
        array($db_name, $table)
    )->result();

    $cols = array();
    foreach ($rows as $row) {
        $cols[] = (string) $row->COLUMN_NAME;
    }
    return $cols;
}

function bb_ska_detect_pk_column($CI, $table, $columns = null)
{
    if ($columns === null) {
        $columns = bb_ska_get_table_columns($CI, $table);
    }
    if (in_array('id', $columns, true)) {
        return 'id';
    }

    $db_name = $CI->db->database;
    $row = $CI->db->query(
        "SELECT COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_NAME = 'PRIMARY'
        ORDER BY ORDINAL_POSITION ASC LIMIT 1",
        array($db_name, $table)
    )->row();

    if ($row && !empty($row->COLUMN_NAME)) {
        return (string) $row->COLUMN_NAME;
    }

    return !empty($columns[0]) ? (string) $columns[0] : 'id';
}

function bb_ska_detect_nilai_column($CI, $table, $columns = null)
{
    if ($columns === null) {
        $columns = bb_ska_get_table_columns($CI, $table);
    }
    $candidates = array('harga_total', 'total_nominal', 'nilai', 'nominal', 'total', 'jumlah', 'saldo', 'debet', 'kredit', 'harga_satuan', 'amount', 'value');
    foreach ($candidates as $col) {
        if (in_array($col, $columns, true)) {
            return $col;
        }
    }
    return '';
}

function bb_ska_table_tanggal_defaults()
{
    return array(
        'tbl_pembelian' => 'tgl_po',
        'tbl_pembelian_jasa' => 'tgl_po',
        'tbl_penjualan' => 'tgl_jual',
        'tbl_penjualan_jasa' => 'tgl_jual',
        'jurnal_kas' => 'tanggal',
        'buku_besar' => 'tanggal',
        'bukubank' => 'tanggal',
        'jurnal_penyesuaian' => 'tanggal',
        'jurnal_umum' => 'tanggal',
    );
}

function bb_ska_detect_tanggal_column($columns, $table = '')
{
    $table = trim((string) $table);
    $defaults = bb_ska_table_tanggal_defaults();
    if ($table !== '' && isset($defaults[$table]) && in_array($defaults[$table], $columns, true)) {
        return $defaults[$table];
    }

    $candidates = array(
        'tanggal', 'tgl_jual', 'tgl_po', 'tgl_transaksi', 'tgl_input', 'tgl',
        'tgl_bayar', 'date_input', 'created_at', 'tgl_bayar_input',
    );
    foreach ($candidates as $col) {
        if (in_array($col, $columns, true)) {
            return $col;
        }
    }
    foreach ($columns as $col) {
        if (stripos($col, 'tgl_') === 0) {
            return $col;
        }
    }
    foreach ($columns as $col) {
        if (stripos($col, 'tanggal') !== false) {
            return $col;
        }
    }
    return '';
}

function bb_ska_display_columns_for_table($table, $columns)
{
    $presets = array(
        'tbl_pembelian' => array('tgl_po', 'spop', 'nmrfakturkwitansi', 'supplier_nama', 'uraian', 'jumlah', 'harga_total'),
        'tbl_pembelian_jasa' => array('tgl_po', 'spop', 'supplier_nama', 'uraian', 'jumlah', 'harga_total'),
        'tbl_penjualan' => array('tgl_jual', 'kode', 'nama', 'pl', 'total_nominal', 'konsumen'),
        'tbl_penjualan_jasa' => array('tgl_jual', 'kode', 'nama', 'total_nominal'),
        'jurnal_kas' => array('tanggal', 'pl', 'kode', 'keterangan', 'debet', 'kredit'),
        'buku_besar' => array('tanggal', 'pl', 'kode', 'nama_akun', 'keterangan', 'debet', 'kredit'),
        'bukubank' => array('tanggal', 'pl', 'kode', 'keterangan', 'debet', 'kredit'),
    );

    $skip = array('id', 'uuid_buku_besar', 'uuid_spop', 'uuid_gudang', 'uuid_kode_akun', 'password', 'pass');
    $out = array();

    if (isset($presets[$table])) {
        foreach ($presets[$table] as $col) {
            if (in_array($col, $columns, true)) {
                $out[] = $col;
            }
        }
        if (!empty($out)) {
            return $out;
        }
    }

    foreach ($columns as $col) {
        if (in_array($col, $skip, true)) {
            continue;
        }
        if (stripos($col, 'uuid_') === 0) {
            continue;
        }
        $out[] = $col;
        if (count($out) >= 6) {
            break;
        }
    }
    return $out;
}

function bb_ska_format_cell_value($val)
{
    if ($val === null || $val === '') {
        return '';
    }
    if (is_numeric($val) && !preg_match('/^0\d/', (string) $val)) {
        return bb_ska_format_nominal($val);
    }
    $str = (string) $val;
    if (strlen($str) > 100) {
        return substr($str, 0, 100) . '…';
    }
    return $str;
}

function bb_ska_detect_table_meta($CI, $table)
{
    $columns = bb_ska_get_table_columns($CI, $table);
    return array(
        'columns' => $columns,
        'kolom_pk' => bb_ska_detect_pk_column($CI, $table, $columns),
        'kolom_nilai' => bb_ska_detect_nilai_column($CI, $table, $columns),
        'kolom_tanggal' => bb_ska_detect_tanggal_column($columns, $table),
        'display_columns' => bb_ska_display_columns_for_table($table, $columns),
        'has_kode_akun' => in_array('kode_akun', $columns, true),
        'has_debet' => in_array('debet', $columns, true),
        'has_kredit' => in_array('kredit', $columns, true),
    );
}

function bb_ska_get_setting_row($CI, $table)
{
    bb_ska_ensure_tables($CI);
    return $CI->db->get_where(bb_ska_table_setting(), array('nama_tabel' => $table))->row();
}

function bb_ska_record_map_for_table($CI, $table)
{
    bb_ska_ensure_tables($CI);
    $rows = $CI->db->get_where(bb_ska_table_record(), array('nama_tabel' => $table))->result();
    $map = array();
    foreach ($rows as $row) {
        $map[(string) $row->record_id] = (string) $row->kode_akun;
    }
    return $map;
}

function bb_ska_resolve_kode_akun_for_record($record_map, $row_obj, $has_kode_akun_col)
{
    $pk_val = '';
    if (is_object($row_obj)) {
        foreach ($record_map as $rid => $ka) {
            foreach (get_object_vars($row_obj) as $k => $v) {
                if ((string) $v === (string) $rid) {
                    $pk_val = (string) $rid;
                    break 2;
                }
            }
        }
    }
    return $pk_val;
}

function bb_ska_extract_record_id($row_obj, $pk_col)
{
    if (!is_object($row_obj) || $pk_col === '') {
        return '';
    }
    return isset($row_obj->$pk_col) ? (string) $row_obj->$pk_col : '';
}

function bb_ska_compute_row_nominal($row_obj, $meta)
{
    if (!is_object($row_obj)) {
        return 0.0;
    }
    if (!empty($meta['has_debet']) && !empty($meta['has_kredit'])) {
        $debet = isset($row_obj->debet) ? (float) $row_obj->debet : 0.0;
        $kredit = isset($row_obj->kredit) ? (float) $row_obj->kredit : 0.0;
        return $debet - $kredit;
    }
    $col = isset($meta['kolom_nilai']) ? (string) $meta['kolom_nilai'] : '';
    if ($col !== '' && isset($row_obj->$col)) {
        return (float) $row_obj->$col;
    }
    return 0.0;
}

function bb_ska_fetch_table_records($CI, $table, $month = 0, $year = 0)
{
    if (!bb_ska_is_valid_table_name($table) || !$CI->db->table_exists($table)) {
        return array('ok' => false, 'message' => 'Tabel tidak ditemukan.', 'rows' => array(), 'meta' => array());
    }

    $setting = bb_ska_get_setting_row($CI, $table);
    $meta = bb_ska_detect_table_meta($CI, $table);
    if ($setting) {
        if (!empty($setting->kolom_pk)) {
            $meta['kolom_pk'] = (string) $setting->kolom_pk;
        }
        if (!empty($setting->kolom_nilai)) {
            $meta['kolom_nilai'] = (string) $setting->kolom_nilai;
        }
    }

    $pk = $meta['kolom_pk'];
    $record_map = bb_ska_record_map_for_table($CI, $table);
    $has_ka_col = !empty($meta['has_kode_akun']);
    $display_cols = !empty($meta['display_columns']) ? $meta['display_columns'] : array();

    $sql = 'SELECT * FROM `' . $table . '`';
    $params = array();
    $tanggal_col = isset($meta['kolom_tanggal']) ? $meta['kolom_tanggal'] : '';
    $filter_applied = false;
    $bulan_label = '';

    if ($month >= 1 && $month <= 12 && $year >= 2000) {
        $CI->load->helper('buku_besar_list');
        $bulan_label = buku_besar_bulan_teks($month) . ' ' . $year;
        if ($tanggal_col !== '') {
            $date_awal = sprintf('%04d-%02d-01 00:00:00', $year, $month);
            $date_akhir = date('Y-m-t 23:59:59', strtotime($date_awal));
            $sql .= ' WHERE `' . $tanggal_col . '` >= ? AND `' . $tanggal_col . '` <= ?';
            $params[] = $date_awal;
            $params[] = $date_akhir;
            $filter_applied = true;
        }
    }
    $sql .= ' ORDER BY `' . $pk . '` ASC LIMIT 10000';

    $db_rows = empty($params)
        ? $CI->db->query($sql)->result()
        : $CI->db->query($sql, $params)->result();

    $nama_akun_map = bb_ska_nama_akun_map($CI);
    $out = array();
    $no = 0;
    foreach ($db_rows as $row) {
        $no++;
        $record_id = bb_ska_extract_record_id($row, $pk);
        $kode_akun = '';
        if ($record_id !== '' && isset($record_map[$record_id])) {
            $kode_akun = $record_map[$record_id];
        } elseif ($has_ka_col && isset($row->kode_akun)) {
            $kode_akun = trim((string) $row->kode_akun);
        }
        $nominal = bb_ska_compute_row_nominal($row, $meta);
        $display = array();
        foreach ($display_cols as $col) {
            $val = isset($row->$col) ? $row->$col : '';
            $display[$col] = bb_ska_format_cell_value($val);
        }

        $out[] = array(
            'no' => $no,
            'record_id' => $record_id,
            'kode_akun' => $kode_akun,
            'nama_akun' => isset($nama_akun_map[$kode_akun]) ? $nama_akun_map[$kode_akun] : '',
            'nominal' => $nominal,
            'nominal_formatted' => bb_ska_format_nominal($nominal),
            'columns' => $display,
        );
    }

    return array(
        'ok' => true,
        'rows' => $out,
        'meta' => $meta,
        'display_columns' => $display_cols,
        'total_rows' => count($out),
        'filter_applied' => $filter_applied,
        'kolom_tanggal' => $tanggal_col,
        'bulan_label' => $bulan_label,
        'bulan_num' => (int) $month,
        'tahun' => (int) $year,
        'message' => ($filter_applied ? '' : ($month >= 1 && $tanggal_col === '' ? 'Kolom tanggal tidak terdeteksi — menampilkan semua record.' : '')),
    );
}

function bb_ska_nama_akun_map($CI)
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }
    $cache = array();
    if ($CI->db->table_exists('sys_kode_akun')) {
        foreach ($CI->db->select('kode_akun, nama_akun')->get('sys_kode_akun')->result() as $row) {
            $cache[(string) $row->kode_akun] = (string) $row->nama_akun;
        }
    }
    return $cache;
}

function bb_ska_save_record_kode_akun($CI, $table, $record_id, $kode_akun)
{
    bb_ska_ensure_tables($CI);
    $table = trim((string) $table);
    $record_id = trim((string) $record_id);
    $kode_akun = trim((string) $kode_akun);

    if (!bb_ska_is_valid_table_name($table) || $record_id === '') {
        return array('ok' => false, 'message' => 'Parameter tidak valid.');
    }

    $selected = bb_ska_get_selected_table_names($CI);
    if (!in_array($table, $selected, true)) {
        return array('ok' => false, 'message' => 'Tabel belum dipilih di setting.');
    }

    $now = date('Y-m-d H:i:s');
    $existing = $CI->db->get_where(bb_ska_table_record(), array(
        'nama_tabel' => $table,
        'record_id' => $record_id,
    ))->row();

    if ($existing) {
        $CI->db->where('id', (int) $existing->id)->update(bb_ska_table_record(), array(
            'kode_akun' => $kode_akun,
            'date_update' => $now,
        ));
    } else {
        $CI->db->insert(bb_ska_table_record(), array(
            'uuid_setting' => bb_ska_generate_uuid(),
            'nama_tabel' => $table,
            'record_id' => $record_id,
            'kode_akun' => $kode_akun,
            'date_input' => $now,
            'date_update' => $now,
        ));
    }

    $meta = bb_ska_detect_table_meta($CI, $table);
    if (!empty($meta['has_kode_akun'])) {
        $pk = $meta['kolom_pk'];
        $CI->db->where($pk, $record_id)->update($table, array('kode_akun' => $kode_akun));
    }

    return array(
        'ok' => true,
        'message' => 'Kode akun record berhasil disimpan.',
        'kode_akun' => $kode_akun,
        'nama_akun' => isset(bb_ska_nama_akun_map($CI)[$kode_akun]) ? bb_ska_nama_akun_map($CI)[$kode_akun] : '',
    );
}

function bb_ska_compute_all_kode_akun_values($CI, $month = 0, $year = 0)
{
    bb_ska_ensure_tables($CI);
    $totals = array();
    $details = array();

    foreach (bb_ska_list_selected_tables($CI) as $setting_row) {
        $table = (string) $setting_row->nama_tabel;
        $result = bb_ska_fetch_table_records($CI, $table, $month, $year);
        if (empty($result['ok'])) {
            continue;
        }
        foreach ($result['rows'] as $row) {
            $ka = trim((string) $row['kode_akun']);
            if ($ka === '') {
                continue;
            }
            if (!isset($totals[$ka])) {
                $totals[$ka] = 0.0;
                $details[$ka] = array('record_count' => 0, 'tables' => array());
            }
            $totals[$ka] += (float) $row['nominal'];
            $details[$ka]['record_count']++;
            if (!isset($details[$ka]['tables'][$table])) {
                $details[$ka]['tables'][$table] = 0;
            }
            $details[$ka]['tables'][$table]++;
        }
    }

    return array(
        'totals' => $totals,
        'details' => $details,
    );
}

function bb_ska_build_values_payload($CI, $month = 0, $year = 0, $include_zero = true)
{
    $computed = bb_ska_compute_all_kode_akun_values($CI, $month, $year);
    $totals = $computed['totals'];
    $details = $computed['details'];
    $nama_map = bb_ska_nama_akun_map($CI);

    $values = array();
    if ($CI->db->table_exists('sys_kode_akun')) {
        foreach ($CI->db->select('kode_akun, nama_akun')->order_by('kode_akun', 'ASC')->get('sys_kode_akun')->result() as $row) {
            $ka = (string) $row->kode_akun;
            $nominal = isset($totals[$ka]) ? (float) $totals[$ka] : 0.0;
            if (!$include_zero && abs($nominal) < 0.00001) {
                continue;
            }
            $values[$ka] = array(
                'kode_akun' => $ka,
                'nama_akun' => (string) $row->nama_akun,
                'nominal' => $nominal,
                'nominal_formatted' => bb_ska_format_nominal($nominal),
                'record_count' => isset($details[$ka]['record_count']) ? (int) $details[$ka]['record_count'] : 0,
                'tables' => isset($details[$ka]['tables']) ? $details[$ka]['tables'] : array(),
            );
        }
    }

    foreach ($totals as $ka => $nominal) {
        if (isset($values[$ka])) {
            continue;
        }
        if (!$include_zero && abs($nominal) < 0.00001) {
            continue;
        }
        $values[$ka] = array(
            'kode_akun' => $ka,
            'nama_akun' => isset($nama_map[$ka]) ? $nama_map[$ka] : '',
            'nominal' => (float) $nominal,
            'nominal_formatted' => bb_ska_format_nominal($nominal),
            'record_count' => isset($details[$ka]['record_count']) ? (int) $details[$ka]['record_count'] : 0,
            'tables' => isset($details[$ka]['tables']) ? $details[$ka]['tables'] : array(),
        );
    }

    ksort($values);
    return array_values($values);
}

function bb_ska_get_kode_akun_value($CI, $kode_akun, $month = 0, $year = 0)
{
    $kode_akun = trim((string) $kode_akun);
    $computed = bb_ska_compute_all_kode_akun_values($CI, $month, $year);
    $nominal = isset($computed['totals'][$kode_akun]) ? (float) $computed['totals'][$kode_akun] : 0.0;
    $nama_map = bb_ska_nama_akun_map($CI);
    return array(
        'kode_akun' => $kode_akun,
        'nama_akun' => isset($nama_map[$kode_akun]) ? $nama_map[$kode_akun] : '',
        'nominal' => $nominal,
        'nominal_formatted' => bb_ska_format_nominal($nominal),
    );
}

function bb_ska_values_map($CI, $month = 0, $year = 0)
{
    $map = array();
    foreach (bb_ska_build_values_payload($CI, $month, $year, true) as $row) {
        $map[$row['kode_akun']] = $row['nominal'];
    }
    return $map;
}

function bb_ska_parse_bulan_from_post($CI)
{
    $bulan_ns = trim((string) $CI->input->post('bulan_ns', TRUE));
    if (preg_match('/^(\d{4})-(\d{2})$/', $bulan_ns, $m)) {
        return array('year' => (int) $m[1], 'month' => (int) $m[2]);
    }
    $month = (int) $CI->input->post('bulan_num', TRUE);
    $year = (int) $CI->input->post('tahun', TRUE);
    if ($month >= 1 && $month <= 12 && $year >= 2000) {
        return array('year' => $year, 'month' => $month);
    }
    return array('year' => (int) date('Y'), 'month' => (int) date('m'));
}
