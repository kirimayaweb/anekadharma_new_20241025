<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function bukubank_bulan_teks($angka_bulan)
{
	$angka_bulan = (int) $angka_bulan;
	$nama = array(
		1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
		5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
		9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
	);
	return isset($nama[$angka_bulan]) ? $nama[$angka_bulan] : '';
}

function bukubank_format_rupiah($value, $always_show = false)
{
	if ($value === null || $value === '') {
		return '';
	}
	if (!$always_show && (float) $value == 0) {
		return '';
	}
	return number_format((float) $value, 2, ',', '.');
}

function bukubank_format_tanggal_display($tanggal)
{
	$tanggal = trim((string) $tanggal);
	if ($tanggal === '' || $tanggal === '0000-00-00') {
		return '';
	}
	$ts = strtotime($tanggal);
	return $ts ? date('d-M-Y', $ts) : $tanggal;
}

function bukubank_parse_bulan_ns($bulan_ns, $fallback_month = null, $fallback_year = null)
{
	$bulan_ns = trim((string) $bulan_ns);
	if (preg_match('/^(\d{4})-(\d{2})$/', $bulan_ns, $m)) {
		return array('year' => (int) $m[1], 'month' => (int) $m[2]);
	}
	return array(
		'year' => $fallback_year !== null ? (int) $fallback_year : (int) date('Y'),
		'month' => $fallback_month !== null ? (int) $fallback_month : (int) date('m'),
	);
}

function bukubank_parse_kredit_nominal($value)
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

function bukubank_compute_list_data($CI, $month = null, $year = null)
{
	if ($month === null || $year === null) {
		$month = (int) date('m');
		$year = (int) date('Y');
	}
	$month = (int) $month;
	$year = (int) $year;
	if ($month < 1 || $month > 12) {
		$month = (int) date('m');
	}
	if ($year < 2000) {
		$year = (int) date('Y');
	}

	$sql = 'SELECT * FROM `bukubank` WHERE MONTH(`tanggal`) = ? AND YEAR(`tanggal`) = ? ORDER BY `tanggal` ASC, `id` ASC';
	$rows_db = $CI->db->query($sql, array($month, $year))->result();

	$rows = array();
	$no = 0;
	$total_debet = 0.0;
	$total_kredit = 0.0;
	$running_saldo = 0.0;

	foreach ($rows_db as $row) {
		$no++;
		$debet = (float) (isset($row->debet) ? $row->debet : 0);
		$kredit = bukubank_parse_kredit_nominal(isset($row->kredit) ? $row->kredit : 0);
		$total_debet += $debet;
		$total_kredit += $kredit;
		$running_saldo = $total_debet - $total_kredit;

		$rows[] = array(
			'id' => (int) $row->id,
			'no' => $no,
			'tanggal' => bukubank_format_tanggal_display($row->tanggal),
			'bank' => isset($row->bank) ? $row->bank : '',
			'norek' => isset($row->norek) ? $row->norek : '',
			'keterangan' => isset($row->keterangan) ? $row->keterangan : '',
			'kode' => isset($row->kode) ? $row->kode : '',
			'debet' => $debet,
			'kredit' => $kredit,
			'saldo' => $running_saldo,
			'debet_display' => bukubank_format_rupiah($debet),
			'kredit_display' => bukubank_format_rupiah($kredit),
			'saldo_display' => bukubank_format_rupiah($running_saldo, true),
		);
	}

	return array(
		'data_buku_bank' => $rows,
		'total_debet' => $total_debet,
		'total_kredit' => $total_kredit,
		'total_saldo' => $running_saldo,
		'total_rows' => count($rows),
		'month' => $month,
		'year' => $year,
		'bulan_ns_value' => sprintf('%04d-%02d', $year, $month),
		'bulan_label' => bukubank_bulan_teks($month) . ' ' . $year,
	);
}

function bukubank_export_excel_list_output($CI, $month = null, $year = null)
{
	@set_time_limit(600);
	$CI->load->helper('exportexcel');

	$bulan_ns = trim((string) $CI->input->post('bulan_ns', TRUE));
	if ($month === null || $year === null) {
		$parsed = bukubank_parse_bulan_ns($bulan_ns);
		$month = $parsed['month'];
		$year = $parsed['year'];
	}

	$list = bukubank_compute_list_data($CI, (int) $month, (int) $year);
	$rows = isset($list['data_buku_bank']) ? $list['data_buku_bank'] : array();

	$namaFile = 'Buku_Bank_' . str_pad((string) $month, 2, '0', STR_PAD_LEFT) . '_' . $year . '_' . date('Y-m-d_H-i-s') . '.xlsx';
	excel_prepare_download($namaFile);

	$styleHeader = 4;
	$styleBorder = 3;
	$styleRight = 8;
	$styleLeft = 7;
	$headers = array('No', 'Tanggal', 'Bank', 'Nomor Rekening', 'Keterangan', 'Kode', 'Debet', 'Kredit', 'Saldo');

	xlsBOF();
	xlsSetColumnWidths(array(5, 12, 10, 18, 36, 10, 14, 14, 14));
	xlsWriteLabelBold14(0, 0, 'BUKU BANK — ' . strtoupper(bukubank_bulan_teks((int) $month)) . ' ' . (int) $year);
	xlsWriteLabel(1, 0, 'Dicetak: ' . date('d/m/Y H:i:s') . ' | Total baris: ' . count($rows));

	$headerRow = 3;
	foreach ($headers as $i => $label) {
		xlsWriteCellStyle($headerRow, $i, $label, $styleHeader);
	}

	$rowNum = 4;
	foreach ($rows as $item) {
		xlsWriteCellStyle($rowNum, 0, (int) $item['no'], $styleBorder);
		xlsWriteCellStyle($rowNum, 1, $item['tanggal'], $styleLeft);
		xlsWriteCellStyle($rowNum, 2, $item['bank'], $styleLeft);
		xlsWriteCellStyle($rowNum, 3, $item['norek'], $styleLeft);
		xlsWriteCellStyle($rowNum, 4, $item['keterangan'], $styleLeft);
		xlsWriteCellStyle($rowNum, 5, $item['kode'], $styleLeft);
		if ((float) $item['debet'] > 0) {
			xlsWriteCellStyle($rowNum, 6, bukubank_format_rupiah($item['debet']), $styleRight);
		} else {
			xlsWriteCellStyle($rowNum, 6, '', $styleBorder);
		}
		if ((float) $item['kredit'] > 0) {
			xlsWriteCellStyle($rowNum, 7, bukubank_format_rupiah($item['kredit']), $styleRight);
		} else {
			xlsWriteCellStyle($rowNum, 7, '', $styleBorder);
		}
		xlsWriteCellStyle($rowNum, 8, bukubank_format_rupiah($item['saldo'], true), $styleRight);
		$rowNum++;
	}

	$totalRow = $rowNum;
	xlsWriteCellStyle($totalRow, 5, 'JUMLAH', $styleHeader);
	xlsWriteCellStyle($totalRow, 6, bukubank_format_rupiah($list['total_debet'], true), $styleRight);
	xlsWriteCellStyle($totalRow, 7, bukubank_format_rupiah($list['total_kredit'], true), $styleRight);
	xlsWriteCellStyle($totalRow, 8, bukubank_format_rupiah($list['total_saldo'], true), $styleRight);

	xlsEOF();
}
