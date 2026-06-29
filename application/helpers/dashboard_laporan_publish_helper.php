<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function dashboard_laporan_publish_table_name()
{
	return 'dashboard_laporan_publish';
}

function dashboard_laporan_publish_ensure_table($CI)
{
	$table = dashboard_laporan_publish_table_name();
	if ($CI->db->table_exists($table)) {
		return true;
	}

	$sql = "CREATE TABLE IF NOT EXISTS `" . $table . "` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`report_type` varchar(32) NOT NULL,
		`bulan_key` varchar(7) NOT NULL,
		`published_at` datetime DEFAULT NULL,
		`published_by` int(11) DEFAULT NULL,
		PRIMARY KEY (`id`),
		UNIQUE KEY `report_bulan` (`report_type`, `bulan_key`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8";

	return (bool) $CI->db->query($sql);
}

function dashboard_laporan_publish_bulan_key($year, $month)
{
	$year = (int) $year;
	$month = (int) $month;
	if ($month < 1 || $month > 12 || $year < 2000) {
		return null;
	}

	return sprintf('%04d-%02d', $year, $month);
}

function dashboard_laporan_publish_valid_type($report_type)
{
	return in_array((string) $report_type, array('laba_rugi', 'neraca'), true);
}

function dashboard_laporan_is_published($CI, $report_type, $year, $month)
{
	if (!dashboard_laporan_publish_valid_type($report_type)) {
		return false;
	}

	$bulan_key = dashboard_laporan_publish_bulan_key($year, $month);
	if ($bulan_key === null) {
		return false;
	}

	dashboard_laporan_publish_ensure_table($CI);
	$table = dashboard_laporan_publish_table_name();

	$row = $CI->db->get_where($table, array(
		'report_type' => $report_type,
		'bulan_key' => $bulan_key,
	))->row();

	return $row && !empty($row->published_at);
}

function dashboard_laporan_set_published($CI, $report_type, $year, $month, $publish = true)
{
	if (!dashboard_laporan_publish_valid_type($report_type)) {
		return array('ok' => false, 'message' => 'Jenis laporan tidak valid.');
	}

	$bulan_key = dashboard_laporan_publish_bulan_key($year, $month);
	if ($bulan_key === null) {
		return array('ok' => false, 'message' => 'Bulan tidak valid.');
	}

	if (!dashboard_laporan_publish_ensure_table($CI)) {
		return array('ok' => false, 'message' => 'Gagal menyiapkan tabel publish.');
	}

	$table = dashboard_laporan_publish_table_name();
	$existing = $CI->db->get_where($table, array(
		'report_type' => $report_type,
		'bulan_key' => $bulan_key,
	))->row();

	if ($publish) {
		$data = array(
			'report_type' => $report_type,
			'bulan_key' => $bulan_key,
			'published_at' => date('Y-m-d H:i:s'),
			'published_by' => (int) $CI->session->userdata('id_user'),
		);

		if ($existing) {
			$CI->db->where('id', (int) $existing->id);
			$CI->db->update($table, $data);
		} else {
			$CI->db->insert($table, $data);
		}

		return array(
			'ok' => true,
			'message' => 'Laporan berhasil dipublish.',
			'is_published' => true,
		);
	}

	if ($existing) {
		$CI->db->where('id', (int) $existing->id);
		$CI->db->delete($table);
	}

	return array(
		'ok' => true,
		'message' => 'Publish laporan dibatalkan.',
		'is_published' => false,
	);
}

function dashboard_laporan_require_published_or_deny($CI, $report_type, $year, $month)
{
	if ((int) $month < 1) {
		return true;
	}

	if (dashboard_laporan_is_published($CI, $report_type, $year, $month)) {
		return true;
	}

	show_error('Laporan bulan ini belum dipublish. Silakan publish terlebih dahulu dari Dashboard.', 403, 'Akses Cetak Ditolak');
	return false;
}
