<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function neraca_saldo_parse_bulan_ns($bulan_ns, $fallback_month = null, $fallback_year = null)
{
	$bulan_ns = trim((string) $bulan_ns);
	if (preg_match('/^(\d{4})-(\d{2})$/', $bulan_ns, $m)) {
		return array(
			'year' => (int) $m[1],
			'month' => (int) $m[2],
			'bulan_ns_value' => sprintf('%04d-%02d', (int) $m[1], (int) $m[2]),
		);
	}

	return array(
		'year' => $fallback_year !== null ? (int) $fallback_year : (int) date('Y'),
		'month' => $fallback_month !== null ? (int) $fallback_month : (int) date('m'),
		'bulan_ns_value' => sprintf(
			'%04d-%02d',
			$fallback_year !== null ? (int) $fallback_year : (int) date('Y'),
			$fallback_month !== null ? (int) $fallback_month : (int) date('m')
		),
	);
}

function neraca_saldo_format_rupiah($value, $always_show = false)
{
	if ($value === null || $value === '') {
		return $always_show ? '0,00' : '';
	}
	if (!$always_show && (float) $value == 0) {
		return '';
	}
	return number_format((float) $value, 2, ',', '.');
}

function neraca_saldo_parse_nominal($value)
{
	if ($value === null || $value === '') {
		return 0.0;
	}
	$s = trim((string) $value);
	if ($s === '') {
		return 0.0;
	}
	$s = str_replace('.', '', $s);
	$s = str_replace(',', '.', $s);
	return is_numeric($s) ? (float) $s : 0.0;
}

function neraca_saldo_format_amount_display($value)
{
	return neraca_saldo_format_rupiah($value, true);
}

function neraca_saldo_compute_list_data($CI, $month, $year)
{
	$CI->load->helper('buku_besar_list');

	$month = (int) $month;
	$year = (int) $year;
	if ($month < 1 || $month > 12) {
		$month = (int) date('m');
	}
	if ($year < 2000) {
		$year = (int) date('Y');
	}

	$akun_list = $CI->Sys_kode_akun_model->get_all_order_by_kode_akun_ASC();
	$rows = array();
	$no = 0;

	foreach ((array) $akun_list as $list_data) {
		$kode_akun = (int) $list_data->kode_akun;

		$sql_bb = 'SELECT sum(`debet`) as debet, sum(`kredit`) as kredit FROM `buku_besar`'
			. ' WHERE MONTH(`tanggal`) = ? AND YEAR(`tanggal`) = ? AND `kode_akun` = ? GROUP BY `kode_akun`';
		$buku_besar_data = $CI->db->query($sql_bb, array($month, $year, $kode_akun));

		$sql_jp = 'SELECT sum(`debet`) as debet, sum(`kredit`) as kredit FROM `jurnal_penyesuaian`'
			. ' WHERE MONTH(`tanggal`) = ? AND YEAR(`tanggal`) = ? AND `kode_akun` = ? GROUP BY `kode_akun`';
		$jurnal_penyesuaian_data = $CI->db->query($sql_jp, array($month, $year, $kode_akun));

		$sql_ns = 'SELECT * FROM `neraca_saldo`'
			. ' WHERE MONTH(`tanggal`) = ? AND YEAR(`tanggal`) = ? AND `kode_akun` = ?';
		$neraca_saldo_data = $CI->db->query($sql_ns, array($month, $year, $kode_akun));
		$ns_row = ($neraca_saldo_data->num_rows() > 0) ? $neraca_saldo_data->row() : null;

		$bb_debet = ($buku_besar_data->num_rows() > 0) ? (float) $buku_besar_data->row()->debet : 0;
		$bb_kredit = ($buku_besar_data->num_rows() > 0) ? (float) $buku_besar_data->row()->kredit : 0;
		$jp_debet = ($jurnal_penyesuaian_data->num_rows() > 0) ? (float) $jurnal_penyesuaian_data->row()->debet : 0;
		$jp_kredit = ($jurnal_penyesuaian_data->num_rows() > 0) ? (float) $jurnal_penyesuaian_data->row()->kredit : 0;
		$has_activity = ($buku_besar_data->num_rows() > 0 || $jurnal_penyesuaian_data->num_rows() > 0);

		$debet_tahun_lalu = ($ns_row && !is_null($ns_row->debet_akhir_tahun_lalu))
			? neraca_saldo_format_rupiah($ns_row->debet_akhir_tahun_lalu, true)
			: '';
		$kredit_tahun_lalu = ($ns_row && !is_null($ns_row->kredit_akhir_tahun_lalu))
			? neraca_saldo_format_rupiah($ns_row->kredit_akhir_tahun_lalu, true)
			: '';

		$ns_debet = $has_activity ? ($bb_debet + $jp_debet) : 0;
		$ns_kredit = $has_activity ? ($bb_kredit + $jp_kredit) : 0;

		$no++;
		$rows[] = array(
			'no' => $no,
			'tanggal' => isset($list_data->tanggal) ? $list_data->tanggal : '',
			'kode_akun' => $kode_akun,
			'nama_akun' => isset($list_data->nama_akun) ? $list_data->nama_akun : '',
			'debet_tahun_lalu' => $debet_tahun_lalu,
			'kredit_tahun_lalu' => $kredit_tahun_lalu,
			'penyesuaian_debet' => neraca_saldo_format_amount_display($bb_debet),
			'penyesuaian_kredit' => neraca_saldo_format_amount_display($bb_kredit),
			'ns_debet' => neraca_saldo_format_amount_display($ns_debet),
			'ns_kredit' => neraca_saldo_format_amount_display($ns_kredit),
			'laba_debet' => neraca_saldo_format_amount_display($ns_debet),
			'laba_kredit' => neraca_saldo_format_amount_display($ns_kredit),
		);
	}

	return array(
		'rows' => $rows,
		'total_rows' => count($rows),
		'month' => $month,
		'year' => $year,
		'bulan_ns_value' => sprintf('%04d-%02d', $year, $month),
		'periode_label' => buku_besar_bulan_teks($month) . ' ' . $year,
		'tahun_lalu' => (int) date('Y', strtotime('-1 year')),
	);
}

function neraca_saldo_excel_write_table_header($header_row, $tahun_lalu)
{
	$styleHeader = 4;
	$group_labels = array(
		'NERACA SALDO 31 Desember ' . (int) $tahun_lalu,
		'PENYESUAIAN',
		'NS SETELAH PENYESUAIAN',
		'LABA/ RUGI',
	);

	$row1 = (int) $header_row;
	$row2 = $row1 + 1;

	$single_headers = array('No', 'Tanggal', 'Kode Rek.', 'Uraian');
	foreach ($single_headers as $col => $label) {
		xlsAddMerge($row1, $col, $row2, $col);
		xlsWriteCellStyle($row1, $col, $label, $styleHeader);
		for ($r = $row1 + 1; $r <= $row2; $r++) {
			xlsEnsureCellStyle($r, $col, $styleHeader, '');
		}
	}

	$sub_headers = array('debet', 'kredit');
	foreach ($group_labels as $group_index => $group_label) {
		$col_start = 4 + ($group_index * 2);
		$col_end = $col_start + 1;

		xlsAddMerge($row1, $col_start, $row1, $col_end);
		xlsWriteCellStyle($row1, $col_start, $group_label, $styleHeader);
		xlsEnsureCellStyle($row1, $col_end, $styleHeader, '');

		foreach ($sub_headers as $sub_index => $sub_label) {
			xlsWriteCellStyle($row2, $col_start + $sub_index, $sub_label, $styleHeader);
		}
	}
}

function neraca_saldo_export_excel_list_output($CI, $month = null, $year = null)
{
	@set_time_limit(600);
	$CI->load->helper('exportexcel');

	$bulan_ns = trim((string) $CI->input->post('bulan_ns', TRUE));
	if ($month === null || $year === null) {
		$parsed = neraca_saldo_parse_bulan_ns($bulan_ns);
		$month = $parsed['month'];
		$year = $parsed['year'];
	}

	$data = neraca_saldo_compute_list_data($CI, $month, $year);
	$namaFile = 'Neraca_Saldo_'
		. str_pad((string) $data['month'], 2, '0', STR_PAD_LEFT) . '_'
		. (int) $data['year'] . '_'
		. date('Y-m-d_H-i-s') . '.xlsx';

	excel_prepare_download($namaFile);

	$styleBorder = 3;
	$styleLeft = 7;
	$styleRight = 8;

	xlsBOF();
	xlsSetColumnWidths(array(6, 14, 12, 28, 16, 16, 16, 16, 16, 16, 16, 16));

	xlsWriteLabelBold14(0, 0, 'NERACA SALDO ' . strtoupper($data['periode_label']));
	xlsWriteLabel(1, 0, 'Dicetak: ' . date('d/m/Y H:i:s') . ' | Total baris: ' . (int) $data['total_rows']);

	$headerRow = 3;
	neraca_saldo_excel_write_table_header($headerRow, $data['tahun_lalu']);

	$rowNum = $headerRow + 2;
	foreach ($data['rows'] as $item) {
		xlsWriteCellStyle($rowNum, 0, $item['no'], $styleBorder);
		xlsWriteCellStyle($rowNum, 1, $item['tanggal'], $styleLeft);
		xlsWriteCellStyle($rowNum, 2, $item['kode_akun'], $styleLeft);
		xlsWriteCellStyle($rowNum, 3, $item['nama_akun'], $styleLeft);
		xlsWriteCellStyle($rowNum, 4, $item['debet_tahun_lalu'], $styleRight);
		xlsWriteCellStyle($rowNum, 5, $item['kredit_tahun_lalu'], $styleRight);
		xlsWriteCellStyle($rowNum, 6, $item['penyesuaian_debet'], $styleRight);
		xlsWriteCellStyle($rowNum, 7, $item['penyesuaian_kredit'], $styleRight);
		xlsWriteCellStyle($rowNum, 8, $item['ns_debet'], $styleRight);
		xlsWriteCellStyle($rowNum, 9, $item['ns_kredit'], $styleRight);
		xlsWriteCellStyle($rowNum, 10, $item['laba_debet'], $styleRight);
		xlsWriteCellStyle($rowNum, 11, $item['laba_kredit'], $styleRight);
		$rowNum++;
	}

	xlsEOF();
}
