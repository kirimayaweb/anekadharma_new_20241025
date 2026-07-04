<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
    return $CI->db
        ->where('source', 'penjualan')
        ->where('id_source', (int) $source_id)
        ->where('kode_akun', $kode_akun)
        ->where('tbl_source_name', $tbl_source_name)
        ->limit(1)
        ->get('buku_besar')
        ->row();
}

function bb_recalc_penjualan($CI, $month, $year)
{
    $CI->load->helper('buku_besar_list');
    $month = (int) $month;
    $year = (int) $year;
    if ($month < 1 || $month > 12 || $year < 2000) {
        return array('ok' => false, 'message' => 'Bulan atau tahun tidak valid.');
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

    $stats = array(
        'penjualan_total' => count($penjualan_rows),
        'penjualan_processed' => 0,
        'penjualan_skipped_no_unit' => 0,
        'penjualan_skipped_no_mapping' => 0,
        'buku_besar_inserted' => 0,
        'buku_besar_updated' => 0,
        'kode_akun_rows' => 0,
    );

    foreach ($penjualan_rows as $pj) {
        $uuid_unit = trim((string) $pj->uuid_unit);
        if ($uuid_unit === '') {
            $stats['penjualan_skipped_no_unit']++;
            continue;
        }
        if (!isset($unit_map[$uuid_unit]) || empty($unit_map[$uuid_unit])) {
            $stats['penjualan_skipped_no_mapping']++;
            continue;
        }

        $source_nominal = (float) $pj->harga_satuan * (float) $pj->jumlah;
        if (abs($source_nominal) < 0.000001) {
            continue;
        }

        $stats['penjualan_processed']++;
        $primary_bb_id = null;

        foreach ($unit_map[$uuid_unit] as $mapping) {
            $tbl_source = isset($mapping->tbl_source) ? trim((string) $mapping->tbl_source) : 'tbl_penjualan';
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

            $multiply = (float) $mapping->mutiply_processing;
            if ($multiply <= 0) {
                $multiply = 1;
            }
            $nilai = $source_nominal * $multiply;
            $dk = bb_recalc_assign_debet_kredit($kode_akun, $nilai);

            $bb_data = array(
                'tanggal' => $pj->tgl_jual,
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
            );

            $existing = bb_recalc_find_buku_besar_row($CI, $pj->id, $kode_akun, $tbl_source);
            if ($existing) {
                $CI->db->where('id', (int) $existing->id)->update('buku_besar', $bb_data);
                $stats['buku_besar_updated']++;
                if ($primary_bb_id === null) {
                    $primary_bb_id = (int) $existing->id;
                }
            } else {
                $CI->db->insert('buku_besar', $bb_data);
                $new_id = (int) $CI->db->insert_id();
                $stats['buku_besar_inserted']++;
                if ($primary_bb_id === null) {
                    $primary_bb_id = $new_id;
                }
            }
            $stats['kode_akun_rows']++;
        }

        if ($primary_bb_id !== null) {
            $CI->db->where('id', (int) $pj->id)->update('tbl_penjualan', array(
                'id_buku_besar' => $primary_bb_id,
            ));
        }
    }

    return array(
        'ok' => true,
        'message' => 'Recalculate selesai. Diproses ' . $stats['penjualan_processed'] . ' record penjualan, '
            . $stats['kode_akun_rows'] . ' baris kode akun (insert: ' . $stats['buku_besar_inserted']
            . ', update: ' . $stats['buku_besar_updated'] . ').',
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
