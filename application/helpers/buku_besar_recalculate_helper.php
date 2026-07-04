<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function bb_recalc_column_cache_ref()
{
    static $cache = array();
    return $cache;
}

function bb_recalc_column_exists($CI, $table, $column)
{
    $cache = &bb_recalc_column_cache_ref();
    $key = $table . '.' . $column;
    if (!isset($cache[$key])) {
        $cache[$key] = $CI->db->table_exists($table) && $CI->db->field_exists($column, $table);
    }
    return $cache[$key];
}

function bb_recalc_column_cache_set($table, $column, $exists)
{
    $cache = &bb_recalc_column_cache_ref();
    $cache[$table . '.' . $column] = (bool) $exists;
}

function bb_recalc_filter_table_data($CI, $table, $data)
{
    if (!is_array($data) || !$CI->db->table_exists($table)) {
        return array();
    }
    $filtered = array();
    foreach ($data as $key => $value) {
        if (bb_recalc_column_exists($CI, $table, $key)) {
            $filtered[$key] = $value;
        }
    }
    return $filtered;
}

function bb_recalc_ensure_buku_besar_columns($CI)
{
    static $done = false;
    if ($done || !$CI->db->table_exists('buku_besar')) {
        return array('ok' => true, 'added' => array());
    }
    $done = true;

    $columns = array(
        'id_source' => 'INT(11) NULL DEFAULT NULL',
        'tbl_source_name' => "VARCHAR(64) NULL DEFAULT NULL",
        'uuid_unit' => "VARCHAR(64) NULL DEFAULT NULL",
        'nama_unit' => "VARCHAR(255) NULL DEFAULT NULL",
        'source_nominal' => 'DOUBLE NULL DEFAULT NULL',
        'source_percen_setting' => 'DOUBLE NULL DEFAULT NULL',
        'nilai_kalkulasi_per_kode_akun' => 'DOUBLE NULL DEFAULT NULL',
    );

    $added = array();
    $errors = array();
    foreach ($columns as $column => $definition) {
        if (bb_recalc_column_exists($CI, 'buku_besar', $column)) {
            continue;
        }
        $sql = 'ALTER TABLE `buku_besar` ADD COLUMN `' . $column . '` ' . $definition;
        if (@$CI->db->query($sql)) {
            $added[] = $column;
            bb_recalc_column_cache_set('buku_besar', $column, true);
        } else {
            $errors[] = $column . ': ' . strip_tags((string) $CI->db->error()['message']);
        }
    }

    if (!empty($errors) && empty($added)) {
        return array(
            'ok' => false,
            'message' => 'Kolom buku_besar untuk recalculate belum lengkap. Jalankan SQL migration atau beri hak ALTER TABLE.',
            'db_errors' => $errors,
        );
    }

    return array('ok' => true, 'added' => $added, 'db_errors' => $errors);
}

function bb_recalc_penjualan_month_bounds($month, $year)
{
    $month = (int) $month;
    $year = (int) $year;
    $start = sprintf('%04d-%02d-01 00:00:00', $year, $month);
    $end = date('Y-m-t 23:59:59', strtotime($start));
    return array('start' => $start, 'end' => $end);
}

function bb_recalc_unit_kode_akun_map($CI)
{
    if (!$CI->db->table_exists('sys_unit_kode_akun')) {
        return array();
    }
    $map = array();
    foreach ($CI->db->get('sys_unit_kode_akun')->result() as $row) {
        $uuid = trim((string) $row->uuid_unit);
        if ($uuid === '') {
            continue;
        }
        if (!isset($map[$uuid])) {
            $map[$uuid] = array();
        }
        $map[$uuid][] = $row;
    }
    return $map;
}

function bb_recalc_nama_akun_map($CI)
{
    $map = array();
    if (!$CI->db->table_exists('sys_kode_akun')) {
        return $map;
    }
    foreach ($CI->db->select('kode_akun, nama_akun')->get('sys_kode_akun')->result() as $row) {
        $map[(string) $row->kode_akun] = (string) $row->nama_akun;
    }
    return $map;
}

function bb_recalc_assign_debet_kredit($kode_akun, $nilai)
{
    $nilai = (float) $nilai;
    $ka = trim((string) $kode_akun);
    if ($ka === '11301' || (strlen($ka) >= 1 && $ka[0] === '1')) {
        return array('debet' => $nilai, 'kredit' => 0);
    }
    return array('debet' => 0, 'kredit' => $nilai);
}

function bb_recalc_find_buku_besar_row($CI, $source_id, $kode_akun, $tbl_source_name = 'tbl_penjualan')
{
    $source_id = (int) $source_id;
    $kode_akun = trim((string) $kode_akun);
    $tbl_source_name = trim((string) $tbl_source_name);
    if ($kode_akun === '' || !$CI->db->table_exists('buku_besar')) {
        return null;
    }

    $CI->db->from('buku_besar');
    $CI->db->where('source', 'penjualan');
    $CI->db->where('kode_akun', $kode_akun);

    if (bb_recalc_column_exists($CI, 'buku_besar', 'id_source')) {
        $CI->db->where('id_source', $source_id);
        if (bb_recalc_column_exists($CI, 'buku_besar', 'tbl_source_name') && $tbl_source_name !== '') {
            $CI->db->where('tbl_source_name', $tbl_source_name);
        }
    } else {
        $CI->db->like('keterangan', 'Recalculate penjualan ID ' . $source_id, 'after');
    }

    return $CI->db->limit(1)->get()->row();
}

function bb_recalc_db_error_message($CI)
{
    $err = $CI->db->error();
    if (!empty($err['message']) && (int) $err['code'] !== 0) {
        return trim((string) $err['message']);
    }
    return '';
}

function bb_recalc_penjualan($CI, $month, $year)
{
    $CI->load->helper('buku_besar_list');
    $month = (int) $month;
    $year = (int) $year;
    if ($month < 1 || $month > 12 || $year < 2000) {
        return array('ok' => false, 'message' => 'Bulan atau tahun tidak valid.');
    }

    if (!$CI->db->table_exists('tbl_penjualan')) {
        return array('ok' => false, 'message' => 'Tabel tbl_penjualan tidak ditemukan.');
    }
    if (!$CI->db->table_exists('buku_besar')) {
        return array('ok' => false, 'message' => 'Tabel buku_besar tidak ditemukan.');
    }

    $schema = bb_recalc_ensure_buku_besar_columns($CI);
    if (empty($schema['ok'])) {
        return array(
            'ok' => false,
            'message' => isset($schema['message']) ? $schema['message'] : 'Skema buku_besar belum siap untuk recalculate.',
            'db_error' => isset($schema['db_errors']) ? implode('; ', (array) $schema['db_errors']) : '',
        );
    }

    $bounds = bb_recalc_penjualan_month_bounds($month, $year);
    $unit_map = bb_recalc_unit_kode_akun_map($CI);
    if (empty($unit_map)) {
        return array('ok' => false, 'message' => 'Belum ada data di sys_unit_kode_akun. Setting di tab Setting Kode Akun Unit.');
    }

    $nama_akun_map = bb_recalc_nama_akun_map($CI);
    $penjualan_rows = $CI->db
        ->where('tgl_jual >=', $bounds['start'])
        ->where('tgl_jual <=', $bounds['end'])
        ->order_by('id', 'ASC')
        ->get('tbl_penjualan')
        ->result();

    $db_error = bb_recalc_db_error_message($CI);
    if ($db_error !== '') {
        return array('ok' => false, 'message' => 'Gagal membaca tbl_penjualan.', 'db_error' => $db_error);
    }

    $stats = array(
        'penjualan_total' => count($penjualan_rows),
        'penjualan_processed' => 0,
        'penjualan_skipped_no_unit' => 0,
        'penjualan_skipped_no_mapping' => 0,
        'buku_besar_inserted' => 0,
        'buku_besar_updated' => 0,
        'kode_akun_rows' => 0,
        'schema_columns_added' => isset($schema['added']) ? $schema['added'] : array(),
    );

    $has_uuid_unit = bb_recalc_column_exists($CI, 'tbl_penjualan', 'uuid_unit');
    $has_id_buku_besar = bb_recalc_column_exists($CI, 'tbl_penjualan', 'id_buku_besar');
    $has_tbl_source_map = bb_recalc_column_exists($CI, 'sys_unit_kode_akun', 'tbl_source');
    $has_mutiply_map = bb_recalc_column_exists($CI, 'sys_unit_kode_akun', 'mutiply_processing');

    foreach ($penjualan_rows as $pj) {
        $uuid_unit = ($has_uuid_unit && isset($pj->uuid_unit)) ? trim((string) $pj->uuid_unit) : '';
        if ($uuid_unit === '') {
            $stats['penjualan_skipped_no_unit']++;
            continue;
        }
        if (!isset($unit_map[$uuid_unit]) || empty($unit_map[$uuid_unit])) {
            $stats['penjualan_skipped_no_mapping']++;
            continue;
        }

        $source_nominal = (float) (isset($pj->harga_satuan) ? $pj->harga_satuan : 0) * (float) (isset($pj->jumlah) ? $pj->jumlah : 0);
        if (abs($source_nominal) < 0.000001) {
            continue;
        }

        $stats['penjualan_processed']++;
        $primary_bb_id = null;

        foreach ($unit_map[$uuid_unit] as $mapping) {
            $tbl_source = 'tbl_penjualan';
            if ($has_tbl_source_map && isset($mapping->tbl_source)) {
                $tbl_source = trim((string) $mapping->tbl_source);
            }
            if ($tbl_source === '') {
                $tbl_source = 'tbl_penjualan';
            }
            if ($tbl_source !== 'tbl_penjualan') {
                continue;
            }

            $kode_akun = trim((string) $mapping->kode_akun);
            if ($kode_akun === '') {
                continue;
            }

            $multiply = 1.0;
            if ($has_mutiply_map && isset($mapping->mutiply_processing)) {
                $multiply = (float) $mapping->mutiply_processing;
            }
            if ($multiply <= 0) {
                $multiply = 1;
            }
            $nilai = $source_nominal * $multiply;
            $dk = bb_recalc_assign_debet_kredit($kode_akun, $nilai);

            $bb_data = bb_recalc_filter_table_data($CI, 'buku_besar', array(
                'tanggal' => isset($pj->tgl_jual) ? $pj->tgl_jual : null,
                'kode_akun' => $kode_akun,
                'nama_akun' => isset($nama_akun_map[$kode_akun]) ? $nama_akun_map[$kode_akun] : '',
                'source' => 'penjualan',
                'id_source' => (int) $pj->id,
                'tbl_source_name' => $tbl_source,
                'uuid_unit' => $uuid_unit,
                'nama_unit' => isset($mapping->nama_unit) ? $mapping->nama_unit : '',
                'nokirim' => isset($pj->nmrkirim) ? $pj->nmrkirim : '',
                'uuid_konsumen' => isset($pj->uuid_konsumen) ? $pj->uuid_konsumen : null,
                'konsumen_id' => isset($pj->konsumen_id) ? $pj->konsumen_id : null,
                'konsumen_nama' => isset($pj->konsumen_nama) ? $pj->konsumen_nama : null,
                'keterangan' => 'Recalculate penjualan ID ' . (int) $pj->id,
                'kode' => isset($pj->kode_bb) ? $pj->kode_bb : '',
                'pl' => isset($pj->kode_pl) ? $pj->kode_pl : '',
                'source_nominal' => $source_nominal,
                'source_percen_setting' => $multiply,
                'nilai_kalkulasi_per_kode_akun' => $nilai,
                'debet' => $dk['debet'],
                'kredit' => $dk['kredit'],
            ));

            if (empty($bb_data)) {
                return array('ok' => false, 'message' => 'Tidak ada kolom buku_besar yang cocok untuk insert/update recalculate.');
            }

            $existing = bb_recalc_find_buku_besar_row($CI, $pj->id, $kode_akun, $tbl_source);
            if ($existing) {
                $CI->db->where('id', (int) $existing->id)->update('buku_besar', $bb_data);
                $db_error = bb_recalc_db_error_message($CI);
                if ($db_error !== '') {
                    return array(
                        'ok' => false,
                        'message' => 'Gagal update buku_besar (penjualan ID ' . (int) $pj->id . ', kode akun ' . $kode_akun . ').',
                        'db_error' => $db_error,
                        'stats' => $stats,
                    );
                }
                $stats['buku_besar_updated']++;
                if ($primary_bb_id === null) {
                    $primary_bb_id = (int) $existing->id;
                }
            } else {
                $CI->db->insert('buku_besar', $bb_data);
                $db_error = bb_recalc_db_error_message($CI);
                if ($db_error !== '') {
                    return array(
                        'ok' => false,
                        'message' => 'Gagal insert buku_besar (penjualan ID ' . (int) $pj->id . ', kode akun ' . $kode_akun . ').',
                        'db_error' => $db_error,
                        'stats' => $stats,
                    );
                }
                $new_id = (int) $CI->db->insert_id();
                $stats['buku_besar_inserted']++;
                if ($primary_bb_id === null) {
                    $primary_bb_id = $new_id;
                }
            }
            $stats['kode_akun_rows']++;
        }

        if ($primary_bb_id !== null && $has_id_buku_besar) {
            $CI->db->where('id', (int) $pj->id)->update('tbl_penjualan', array(
                'id_buku_besar' => $primary_bb_id,
            ));
            $db_error = bb_recalc_db_error_message($CI);
            if ($db_error !== '') {
                return array(
                    'ok' => false,
                    'message' => 'Recalculate buku_besar selesai sebagian, tetapi gagal update id_buku_besar di tbl_penjualan.',
                    'db_error' => $db_error,
                    'stats' => $stats,
                );
            }
        }
    }

    $message = 'Recalculate selesai. Diproses ' . $stats['penjualan_processed'] . ' record penjualan, '
        . $stats['kode_akun_rows'] . ' baris kode akun (insert: ' . $stats['buku_besar_inserted']
        . ', update: ' . $stats['buku_besar_updated'] . ').';
    if (!empty($stats['schema_columns_added'])) {
        $message .= ' Kolom baru ditambahkan: ' . implode(', ', $stats['schema_columns_added']) . '.';
    }

    return array(
        'ok' => true,
        'message' => $message,
        'stats' => $stats,
        'bulan_label' => buku_besar_bulan_teks($month) . ' ' . $year,
    );
}

function bb_setting_kode_akun_panel_data($CI)
{
    $CI->load->model(array('Sys_unit_kode_akun_model', 'Sys_unit_model', 'Sys_kode_akun_model'));
    return array(
        'data_list' => $CI->Sys_unit_kode_akun_model->get_all(),
        'data_unit' => $CI->Sys_unit_model->get_all(),
        'data_kode_akun' => $CI->Sys_kode_akun_model->get_all_order_by_kode_akun_ASC(),
        'tbl_source_options' => $CI->Sys_unit_kode_akun_model->get_tbl_source_options(),
        'url_create' => site_url('Setting_kode_akun/create_action_ajax'),
        'url_update' => site_url('Setting_kode_akun/update_action_ajax'),
        'url_delete' => site_url('Setting_kode_akun/delete_action_ajax'),
        'url_excel' => site_url('Setting_kode_akun/excel'),
        'url_reload_panel' => site_url('Buku_besar/ajax_setting_kode_akun_panel'),
        'embed_mode' => true,
    );
}
