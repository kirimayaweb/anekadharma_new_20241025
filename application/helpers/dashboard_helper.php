<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Rentang bulan valid untuk datatable Dashboard: Jan 2026 s/d bulan aktif.
 */
function dashboard_bulan_in_valid_range($year, $month, $min_year = 2026, $min_month = 1)
{
	$year = (int) $year;
	$month = (int) $month;
	if ($month < 1 || $month > 12) {
		return false;
	}

	$current_year = (int) date('Y');
	$current_month = (int) date('m');
	$min_year = (int) $min_year;
	$min_month = (int) $min_month;

	if ($year < $min_year || ($year === $min_year && $month < $min_month)) {
		return false;
	}
	if ($year > $current_year || ($year === $current_year && $month > $current_month)) {
		return false;
	}

	return true;
}

function dashboard_get_date_range($min_date = '2026-01-01')
{
	return array(
		'min_date' => $min_date,
		'max_date' => date('Y-m-t'),
		'current_year' => (int) date('Y'),
		'current_month' => (int) date('m'),
	);
}

function dashboard_filter_pembelian_bulan_rows($rows)
{
	$filtered = array();
	if (!is_array($rows) && !($rows instanceof Traversable)) {
		return $filtered;
	}

	foreach ($rows as $row) {
		$year = isset($row->tahun_neraca) ? (int) $row->tahun_neraca : 0;
		$month = isset($row->bulan_neraca) ? (int) $row->bulan_neraca : 0;
		if (dashboard_bulan_in_valid_range($year, $month)) {
			$filtered[] = $row;
		}
	}

	return $filtered;
}

function dashboard_filter_jurnal_kas_bulan_rows($rows)
{
	$filtered = array();
	if (!is_array($rows) && !($rows instanceof Traversable)) {
		return $filtered;
	}

	foreach ($rows as $row) {
		$year = isset($row->year_process) ? (int) $row->year_process : 0;
		$month = isset($row->month_process) ? (int) $row->month_process : 0;
		if (dashboard_bulan_in_valid_range($year, $month)) {
			$filtered[] = $row;
		}
	}

	return $filtered;
}

/**
 * Daftar bulan Jan 2026 s/d bulan aktif (sama untuk Jurnal Kas, Laba-Rugi, Neraca).
 */
function dashboard_bulan_list_range($min_year = 2026, $min_month = 1)
{
	$current_year = (int) date('Y');
	$current_month = (int) date('m');
	$min_year = (int) $min_year;
	$min_month = (int) $min_month;

	$list = array();
	$year = $min_year;
	$month = $min_month;

	while ($year < $current_year || ($year === $current_year && $month <= $current_month)) {
		if (!dashboard_bulan_in_valid_range($year, $month, $min_year, $min_month)) {
			break;
		}

		$list[] = (object) array(
			'year_process' => $year,
			'month_process' => $month,
			'is_current_month' => ($year === $current_year && $month === $current_month),
		);

		if ($year === $current_year && $month === $current_month) {
			break;
		}

		$month++;
		if ($month > 12) {
			$month = 1;
			$year++;
		}
	}

	usort($list, function ($a, $b) {
		if ((int) $a->year_process !== (int) $b->year_process) {
			return (int) $b->year_process - (int) $a->year_process;
		}
		return (int) $b->month_process - (int) $a->month_process;
	});

	return $list;
}

function dashboard_laporan_has_saved_data($CI, $table, $year, $month)
{
	$year = (int) $year;
	$month = (int) $month;
	if (!$CI->db->table_exists($table)) {
		return false;
	}

	$CI->db->where('tahun_transaksi', $year);
	$CI->db->where('bulan_transaksi', $month);

	return $CI->db->count_all_results($table) > 0;
}

/**
 * Ambil id_user_level dari session (sess_id_user_level atau id_user_level).
 */
function dashboard_session_user_level($CI)
{
	$level = $CI->session->userdata('sess_id_user_level');
	if ($level === null || $level === '' || $level === false) {
		$level = $CI->session->userdata('id_user_level');
	}

	return (int) $level;
}

/**
 * Level yang boleh melihat tombol Cetak Laba-Rugi / Neraca (setelah publish).
 */
function dashboard_laporan_cetak_level_ids()
{
	$levels = array(1, 2, 6, 9, 99, 888, 999);

	if (function_exists('hak_akses_keuangan_level_ids')) {
		$levels = array_merge($levels, hak_akses_keuangan_level_ids());
	} else {
		$levels[] = 666;
	}

	$levels = array_map('intval', $levels);

	return array_values(array_unique($levels));
}

function dashboard_user_can_cetak_laporan($CI)
{
	return in_array(dashboard_session_user_level($CI), dashboard_laporan_cetak_level_ids(), true);
}

/**
 * Admin (2), accounting (9), administrator (99) — Update Laba-Rugi/Neraca semua bulan.
 */
function dashboard_user_can_update_laporan_bulanan($CI)
{
	$level = dashboard_session_user_level($CI);

	return in_array($level, array(1, 2, 9, 99), true);
}

function dashboard_laba_rugi_bulan_list($CI)
{
	$CI->load->helper('dashboard_laporan_publish');
	$always_show_update = dashboard_user_can_update_laporan_bulanan($CI);
	$list = dashboard_bulan_list_range();
	foreach ($list as $row) {
		$has_data = dashboard_laporan_has_saved_data($CI, 'tbl_laba_rugi', $row->year_process, $row->month_process);
		$is_published = dashboard_laporan_is_published($CI, 'laba_rugi', $row->year_process, $row->month_process);
		$row->has_data = $has_data;
		$row->is_published = $is_published;
		$row->show_update = $always_show_update ? true : $has_data;
		$row->show_publish = $always_show_update && !$is_published;
		$row->show_cancel_publish = $always_show_update && $is_published;
		$row->cetak_enabled = $has_data && $is_published;
		$row->show_cetak = $row->cetak_enabled;
		$row->update_url = site_url('Tbl_laba_rugi/labarugi_form/' . $row->year_process . '/' . $row->month_process);
		$row->cetak_url = site_url('Tbl_laba_rugi/labarugi_print/' . $row->year_process . '/' . $row->month_process);
		$row->cetak_url_rinci = site_url('Tbl_laba_rugi/labarugi_print_unit/' . $row->year_process . '/' . $row->month_process . '/rinci');
		$row->cetak_url_sederhana = site_url('Tbl_laba_rugi/labarugi_print_unit/' . $row->year_process . '/' . $row->month_process . '/sederhana');
	}

	return $list;
}

function dashboard_laba_rugi_cetak_buttons_html($year, $month)
{
	$tahun = (int) $year;
	$bulan = (int) $month;
	$out = '<div class="dashboard-dt-cetak-group">';
	$out .= anchor(
		site_url('Tbl_laba_rugi/labarugi_print/' . $tahun . '/' . $bulan),
		'<i class="fa fa-print"></i> Cetak Laba-Rugi',
		'class="btn btn-dt-cetak btn-sm dashboard-dt-btn-cetak dashboard-dt-btn-cetak-main" target="_blank" title="Cetak Laba Rugi Konsolidasi"'
	);
	$out .= anchor(
		site_url('Tbl_laba_rugi/labarugi_print_unit/' . $tahun . '/' . $bulan . '/rinci'),
		'<i class="fa fa-print"></i> LR Unit (Rinci)',
		'class="btn btn-dt-cetak-rinci btn-sm dashboard-dt-btn-cetak-rinci" target="_blank" title="Cetak Laba Rugi Per Unit Rinci"'
	);
	$out .= anchor(
		site_url('Tbl_laba_rugi/labarugi_print_unit/' . $tahun . '/' . $bulan . '/sederhana'),
		'<i class="fa fa-print"></i> LR Unit (Sederhana)',
		'class="btn btn-dt-cetak-sederhana btn-sm dashboard-dt-btn-cetak-sederhana" target="_blank" title="Cetak Laba Rugi Per Unit Sederhana"'
	);
	$out .= '</div>';

	return $out;
}

function dashboard_neraca_bulan_list($CI)
{
	$CI->load->helper('dashboard_laporan_publish');
	$always_show_update = dashboard_user_can_update_laporan_bulanan($CI);
	$list = dashboard_bulan_list_range();
	foreach ($list as $row) {
		$has_data = dashboard_laporan_has_saved_data($CI, 'tbl_neraca_data', $row->year_process, $row->month_process);
		$is_published = dashboard_laporan_is_published($CI, 'neraca', $row->year_process, $row->month_process);
		$row->has_data = $has_data;
		$row->is_published = $is_published;
		$row->show_update = $always_show_update ? true : $has_data;
		$row->show_publish = $always_show_update && !$is_published;
		$row->show_cancel_publish = $always_show_update && $is_published;
		$row->cetak_enabled = $has_data && $is_published;
		$row->show_cetak = $row->cetak_enabled;
		$row->update_url = site_url('Tbl_neraca_data/neraca_form/' . $row->year_process . '/' . $row->month_process);
		$row->cetak_url = site_url('Tbl_neraca_data/neraca_cetak/' . $row->year_process . '/' . $row->month_process);
	}

	return $list;
}
