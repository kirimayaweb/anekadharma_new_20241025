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

function neraca_saldo_periode_header_label($month, $year)
{
	$month = (int) $month;
	$year = (int) $year;
	if ($month < 1 || $month > 12) {
		$month = (int) date('m');
	}
	if ($year < 2000) {
		$year = (int) date('Y');
	}

	$CI = get_instance();
	$CI->load->helper('buku_besar_list');

	return 'NERACA SALDO 1 ' . buku_besar_bulan_teks($month) . ' ' . $year;
}

/**
 * Rekap total debet/kredit per kode akun dari sumber yang sama dengan halaman Buku Besar
 * (pembelian, penjualan, jurnal kas: pembayaran konsumen/supplier, kas kecil, dll).
 */
function neraca_saldo_aggregate_buku_besar_totals($CI, $month, $year)
{
	$CI->load->helper('buku_besar_list');
	$merged_rows = buku_besar_merge_source_rows($CI, (int) $month, (int) $year, '');
	$totals = array();

	foreach ((array) $merged_rows as $row) {
		$kode_akun = isset($row['kode_akun']) ? trim((string) $row['kode_akun']) : '';
		if ($kode_akun === '') {
			continue;
		}
		if (!isset($totals[$kode_akun])) {
			$totals[$kode_akun] = array(
				'debet' => 0.0,
				'kredit' => 0.0,
				'nama_akun' => '',
			);
		}
		$totals[$kode_akun]['debet'] += (float) (isset($row['debet']) ? $row['debet'] : 0);
		$totals[$kode_akun]['kredit'] += (float) (isset($row['kredit']) ? $row['kredit'] : 0);
		if ($totals[$kode_akun]['nama_akun'] === '' && !empty($row['nama_akun'])) {
			$totals[$kode_akun]['nama_akun'] = trim((string) $row['nama_akun']);
		}
	}

	return $totals;
}

function neraca_saldo_get_jurnal_penyesuaian_totals($CI, $month, $year, $kode_akun)
{
	$sql_jp = 'SELECT sum(`debet`) as debet, sum(`kredit`) as kredit FROM `jurnal_penyesuaian`'
		. ' WHERE MONTH(`tanggal`) = ? AND YEAR(`tanggal`) = ? AND `kode_akun` = ? GROUP BY `kode_akun`';
	$jurnal_penyesuaian_data = $CI->db->query($sql_jp, array((int) $month, (int) $year, (int) $kode_akun));

	if ($jurnal_penyesuaian_data->num_rows() > 0) {
		return array(
			'debet' => (float) $jurnal_penyesuaian_data->row()->debet,
			'kredit' => (float) $jurnal_penyesuaian_data->row()->kredit,
		);
	}

	return array('debet' => 0.0, 'kredit' => 0.0);
}

function neraca_saldo_get_neraca_saldo_row($CI, $month, $year, $kode_akun)
{
	$sql_ns = 'SELECT * FROM `neraca_saldo`'
		. ' WHERE MONTH(`tanggal`) = ? AND YEAR(`tanggal`) = ? AND `kode_akun` = ?';
	$neraca_saldo_data = $CI->db->query($sql_ns, array((int) $month, (int) $year, (int) $kode_akun));

	return ($neraca_saldo_data->num_rows() > 0) ? $neraca_saldo_data->row() : null;
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
	$bb_totals = neraca_saldo_aggregate_buku_besar_totals($CI, $month, $year);
	$rows = array();
	$no = 0;

	foreach ((array) $akun_list as $list_data) {
		$kode_akun = (int) $list_data->kode_akun;
		$kode_key = trim((string) $list_data->kode_akun);

		$bb_debet = isset($bb_totals[$kode_key]) ? (float) $bb_totals[$kode_key]['debet'] : 0;
		$bb_kredit = isset($bb_totals[$kode_key]) ? (float) $bb_totals[$kode_key]['kredit'] : 0;

		$jp_totals = neraca_saldo_get_jurnal_penyesuaian_totals($CI, $month, $year, $kode_akun);
		$jp_debet = (float) $jp_totals['debet'];
		$jp_kredit = (float) $jp_totals['kredit'];

		// Kolom NERACA SALDO = rekap transaksi per kode akun (pembelian, penjualan, jurnal kas, dll)
		$saldo_debet = $bb_debet;
		$saldo_kredit = $bb_kredit;

		// Kolom PENYESUAIAN = jurnal penyesuaian
		$penyesuaian_debet = $jp_debet;
		$penyesuaian_kredit = $jp_kredit;

		// Kolom NS SETELAH PENYESUAIAN = neraca saldo + penyesuaian
		$ns_debet = $saldo_debet + $penyesuaian_debet;
		$ns_kredit = $saldo_kredit + $penyesuaian_kredit;

		$debet_tahun_lalu = neraca_saldo_format_rupiah($saldo_debet, true);
		$kredit_tahun_lalu = neraca_saldo_format_rupiah($saldo_kredit, true);

		$no++;
		$rows[] = array(
			'no' => $no,
			'tanggal' => isset($list_data->tanggal) ? $list_data->tanggal : '',
			'kode_akun' => $kode_akun,
			'nama_akun' => isset($list_data->nama_akun) ? $list_data->nama_akun : '',
			'debet_tahun_lalu' => $debet_tahun_lalu,
			'kredit_tahun_lalu' => $kredit_tahun_lalu,
			'has_debet_tahun_lalu' => ($saldo_debet != 0),
			'has_kredit_tahun_lalu' => ($saldo_kredit != 0),
			'saldo_debet' => $saldo_debet,
			'saldo_kredit' => $saldo_kredit,
			'bb_debet' => $penyesuaian_debet,
			'bb_kredit' => $penyesuaian_kredit,
			'ns_debet_raw' => $ns_debet,
			'ns_kredit_raw' => $ns_kredit,
			'penyesuaian_debet' => neraca_saldo_format_amount_display($penyesuaian_debet),
			'penyesuaian_kredit' => neraca_saldo_format_amount_display($penyesuaian_kredit),
			'ns_debet' => neraca_saldo_format_amount_display($ns_debet),
			'ns_kredit' => neraca_saldo_format_amount_display($ns_kredit),
			'laba_debet' => neraca_saldo_format_amount_display($ns_debet),
			'laba_kredit' => neraca_saldo_format_amount_display($ns_kredit),
		);
	}

	$grand_totals = neraca_saldo_build_grand_totals($rows);

	return array(
		'rows' => $rows,
		'total_rows' => count($rows),
		'grand_totals' => $grand_totals,
		'month' => $month,
		'year' => $year,
		'bulan_ns_value' => sprintf('%04d-%02d', $year, $month),
		'periode_label' => buku_besar_bulan_teks($month) . ' ' . $year,
		'ns_header_label' => neraca_saldo_periode_header_label($month, $year),
		'tahun_lalu' => (int) date('Y', strtotime('-1 year')),
	);
}

function neraca_saldo_build_grand_totals($rows)
{
	$totals = array(
		'saldo_debet' => 0.0,
		'saldo_kredit' => 0.0,
		'penyesuaian_debet' => 0.0,
		'penyesuaian_kredit' => 0.0,
		'ns_debet' => 0.0,
		'ns_kredit' => 0.0,
		'laba_debet' => 0.0,
		'laba_kredit' => 0.0,
	);

	foreach ((array) $rows as $item) {
		$totals['saldo_debet'] += (float) (isset($item['saldo_debet']) ? $item['saldo_debet'] : 0);
		$totals['saldo_kredit'] += (float) (isset($item['saldo_kredit']) ? $item['saldo_kredit'] : 0);
		$totals['penyesuaian_debet'] += (float) (isset($item['bb_debet']) ? $item['bb_debet'] : 0);
		$totals['penyesuaian_kredit'] += (float) (isset($item['bb_kredit']) ? $item['bb_kredit'] : 0);
		$totals['ns_debet'] += (float) (isset($item['ns_debet_raw']) ? $item['ns_debet_raw'] : 0);
		$totals['ns_kredit'] += (float) (isset($item['ns_kredit_raw']) ? $item['ns_kredit_raw'] : 0);
		$totals['laba_debet'] += (float) (isset($item['ns_debet_raw']) ? $item['ns_debet_raw'] : 0);
		$totals['laba_kredit'] += (float) (isset($item['ns_kredit_raw']) ? $item['ns_kredit_raw'] : 0);
	}

	return $totals;
}

function neraca_saldo_render_tfoot_html($grand_totals)
{
	$grand_totals = is_array($grand_totals) ? $grand_totals : array();

	$cells = array(
		neraca_saldo_format_rupiah(isset($grand_totals['saldo_debet']) ? $grand_totals['saldo_debet'] : 0, true),
		neraca_saldo_format_rupiah(isset($grand_totals['saldo_kredit']) ? $grand_totals['saldo_kredit'] : 0, true),
		neraca_saldo_format_rupiah(isset($grand_totals['penyesuaian_debet']) ? $grand_totals['penyesuaian_debet'] : 0, true),
		neraca_saldo_format_rupiah(isset($grand_totals['penyesuaian_kredit']) ? $grand_totals['penyesuaian_kredit'] : 0, true),
		neraca_saldo_format_rupiah(isset($grand_totals['ns_debet']) ? $grand_totals['ns_debet'] : 0, true),
		neraca_saldo_format_rupiah(isset($grand_totals['ns_kredit']) ? $grand_totals['ns_kredit'] : 0, true),
		neraca_saldo_format_rupiah(isset($grand_totals['laba_debet']) ? $grand_totals['laba_debet'] : 0, true),
		neraca_saldo_format_rupiah(isset($grand_totals['laba_kredit']) ? $grand_totals['laba_kredit'] : 0, true),
	);

	$html = '<tr class="ns-grand-total-row">'
		. '<th></th><th></th><th></th>'
		. '<th class="text-right">GRAND TOTAL</th>';

	foreach ($cells as $cell) {
		$html .= '<th class="text-right">' . htmlspecialchars($cell, ENT_QUOTES, 'UTF-8') . '</th>';
	}

	$html .= '</tr>';

	return $html;
}

function neraca_saldo_excel_write_table_header($header_row, $month, $year)
{
	$styleHeader = 4;
	$group_labels = array(
		neraca_saldo_periode_header_label($month, $year),
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
	neraca_saldo_excel_write_table_header($headerRow, $data['month'], $data['year']);

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

	if (!empty($data['grand_totals'])) {
		$gt = $data['grand_totals'];
		xlsWriteCellStyle($rowNum, 0, 'GRAND TOTAL', $styleLeft);
		for ($c = 1; $c <= 3; $c++) {
			xlsWriteCellStyle($rowNum, $c, '', $styleLeft);
		}
		xlsWriteCellStyle($rowNum, 4, neraca_saldo_format_rupiah($gt['saldo_debet'], true), $styleRight);
		xlsWriteCellStyle($rowNum, 5, neraca_saldo_format_rupiah($gt['saldo_kredit'], true), $styleRight);
		xlsWriteCellStyle($rowNum, 6, neraca_saldo_format_rupiah($gt['penyesuaian_debet'], true), $styleRight);
		xlsWriteCellStyle($rowNum, 7, neraca_saldo_format_rupiah($gt['penyesuaian_kredit'], true), $styleRight);
		xlsWriteCellStyle($rowNum, 8, neraca_saldo_format_rupiah($gt['ns_debet'], true), $styleRight);
		xlsWriteCellStyle($rowNum, 9, neraca_saldo_format_rupiah($gt['ns_kredit'], true), $styleRight);
		xlsWriteCellStyle($rowNum, 10, neraca_saldo_format_rupiah($gt['laba_debet'], true), $styleRight);
		xlsWriteCellStyle($rowNum, 11, neraca_saldo_format_rupiah($gt['laba_kredit'], true), $styleRight);
	}

	xlsEOF();
}
