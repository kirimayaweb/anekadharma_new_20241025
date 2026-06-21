<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function jurnal_umum_bulan_teks($angka_bulan)
{
	$angka_bulan = (int) $angka_bulan;
	$nama = array(
		1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
		5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
		9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
	);
	return isset($nama[$angka_bulan]) ? $nama[$angka_bulan] : '';
}

function jurnal_umum_format_rupiah($value, $always_show = false)
{
	if ($value === null || $value === '') {
		return '';
	}
	if (!$always_show && (float) $value == 0) {
		return '';
	}
	return number_format((float) $value, 2, ',', '.');
}

function jurnal_umum_format_tanggal_display($tanggal)
{
	$tanggal = trim((string) $tanggal);
	if ($tanggal === '' || $tanggal === '0000-00-00') {
		return '';
	}
	$ts = strtotime($tanggal);
	return $ts ? date('d-m-Y', $ts) : $tanggal;
}

function jurnal_umum_parse_date_input($value, $fallback = '')
{
	$value = trim((string) $value);
	if ($value === '') {
		return $fallback;
	}
	$ts = strtotime($value);
	if ($ts === false) {
		return $fallback;
	}
	return date('Y-m-d', $ts);
}

function jurnal_umum_compute_list_data_by_date_range($CI, $date_awal, $date_akhir)
{
	$date_awal = jurnal_umum_parse_date_input($date_awal, date('Y-m-01'));
	$date_akhir = jurnal_umum_parse_date_input($date_akhir, date('Y-m-t'));
	if ($date_awal > $date_akhir) {
		$tmp = $date_awal;
		$date_awal = $date_akhir;
		$date_akhir = $tmp;
	}

	$sql = 'SELECT * FROM `jurnal_umum` WHERE DATE(`tanggal`) BETWEEN ? AND ? ORDER BY `tanggal`, `nomor`';
	$rows_db = $CI->db->query($sql, array($date_awal, $date_akhir))->result();

	$rows = array();
	$no = 0;
	$total_debet = 0.0;
	$total_kredit = 0.0;

	foreach ($rows_db as $row) {
		$no++;
		$debet = (float) (isset($row->debit) ? $row->debit : 0);
		$kredit = (float) (isset($row->kredit) ? $row->kredit : 0);
		if ($debet > 0) {
			$total_debet += $debet;
		}
		if ($kredit > 0) {
			$total_kredit += $kredit;
		}

		$rows[] = array(
			'no' => $no,
			'nomor' => isset($row->nomor) ? $row->nomor : '',
			'tanggal' => jurnal_umum_format_tanggal_display(isset($row->tanggal) ? $row->tanggal : ''),
			'bukti' => isset($row->bukti) ? $row->bukti : '',
			'pl' => isset($row->pl) ? $row->pl : '',
			'ref' => isset($row->ref) ? $row->ref : '',
			'kode_rekening' => isset($row->uraian_kode_rekening) ? $row->uraian_kode_rekening : '',
			'rekening' => isset($row->rekening) ? $row->rekening : '',
			'debet' => $debet > 0 ? $debet : null,
			'kredit' => $kredit > 0 ? $kredit : null,
			'debet_display' => jurnal_umum_format_rupiah($debet),
			'kredit_display' => jurnal_umum_format_rupiah($kredit),
		);
	}

	return array(
		'Data_Jurnal_Umum' => $rows,
		'Total_debet' => $total_debet,
		'Total_kredit' => $total_kredit,
		'total_rows' => count($rows),
		'date_awal' => $date_awal,
		'date_akhir' => $date_akhir,
		'periode_label' => jurnal_umum_format_tanggal_display($date_awal) . ' s/d ' . jurnal_umum_format_tanggal_display($date_akhir),
	);
}

function jurnal_umum_compute_list_data($CI)
{
	return jurnal_umum_compute_list_data_by_date_range($CI, date('Y-m-01'), date('Y-m-t'));
}

function jurnal_umum_compute_list_data_by_bulan($CI, $month, $year)
{
	$month = (int) $month;
	$year = (int) $year;

	$sql = 'SELECT * FROM `jurnal_umum` WHERE MONTH(`tanggal`) = ? AND YEAR(`tanggal`) = ? ORDER BY `tanggal`, `nomor`';
	$rows_db = $CI->db->query($sql, array($month, $year))->result();

	$rows = array();
	$no = 0;
	$total_debet = 0.0;
	$total_kredit = 0.0;

	foreach ($rows_db as $row) {
		$no++;
		$debet = (float) (isset($row->debit) ? $row->debit : 0);
		$kredit = (float) (isset($row->kredit) ? $row->kredit : 0);
		if ($debet > 0) {
			$total_debet += $debet;
		}
		if ($kredit > 0) {
			$total_kredit += $kredit;
		}

		$rows[] = array(
			'no' => $no,
			'tanggal' => jurnal_umum_format_tanggal_display(isset($row->tanggal) ? $row->tanggal : ''),
			'bukti' => isset($row->bukti) ? $row->bukti : '',
			'pl' => isset($row->pl) ? $row->pl : '',
			'ref' => isset($row->ref) ? $row->ref : '',
			'kode_rekening' => isset($row->uraian_kode_rekening) ? $row->uraian_kode_rekening : '',
			'rekening' => isset($row->rekening) ? $row->rekening : '',
			'debet' => $debet > 0 ? $debet : null,
			'kredit' => $kredit > 0 ? $kredit : null,
		);
	}

	return array(
		'rows' => $rows,
		'Total_debet' => $total_debet,
		'Total_kredit' => $total_kredit,
		'bulan_label' => jurnal_umum_bulan_teks($month) . ' ' . $year,
		'month' => $month,
		'year' => $year,
	);
}

function jurnal_umum_export_excel_list_output($CI, $date_awal = null, $date_akhir = null)
{
	@set_time_limit(600);
	$CI->load->helper('exportexcel');

	if ($date_awal === null) {
		$date_awal = $CI->input->post('tgl_awal', TRUE);
	}
	if ($date_akhir === null) {
		$date_akhir = $CI->input->post('tgl_akhir', TRUE);
	}

	$data = jurnal_umum_compute_list_data_by_date_range($CI, $date_awal, $date_akhir);
	$namaFile = 'Jurnal_Umum_' . date('Y-m-d_H-i-s') . '.xlsx';
	excel_prepare_download($namaFile);

	$styleHeader = 4;
	$styleBorder = 3;
	$styleRight = 8;
	$styleFooter = 6;
	$styleLeft = 7;

	xlsBOF();

	xlsWriteLabelBold14(0, 0, 'JURNAL UMUM');
	xlsWriteLabel(1, 0, 'Periode: ' . $data['periode_label']
		. ' | Dicetak: ' . date('d/m/Y H:i:s')
		. ' | Total baris: ' . (int) $data['total_rows']);

	$headerRow = 3;
	$headers = array('No', 'Tanggal', 'Bukti', 'PL', 'Ref', 'Kode Rek.', 'Rek.', 'Debet', 'Kredit');
	foreach ($headers as $col => $label) {
		xlsWriteCellStyle($headerRow, $col, $label, $styleHeader);
	}

	$rowNum = 4;
	foreach ($data['Data_Jurnal_Umum'] as $item) {
		xlsWriteCellStyle($rowNum, 0, $item['no'], $styleBorder);
		xlsWriteCellStyle($rowNum, 1, $item['tanggal'], $styleLeft);
		xlsWriteCellStyle($rowNum, 2, $item['bukti'], $styleLeft);
		xlsWriteCellStyle($rowNum, 3, $item['pl'], $styleLeft);
		xlsWriteCellStyle($rowNum, 4, $item['ref'], $styleLeft);
		xlsWriteCellStyle($rowNum, 5, $item['kode_rekening'], $styleLeft);
		xlsWriteCellStyle($rowNum, 6, $item['rekening'], $styleLeft);
		if ($item['debet'] !== null) {
			xlsWriteCellStyle($rowNum, 7, jurnal_umum_format_rupiah($item['debet']), $styleRight);
		} else {
			xlsWriteCellStyle($rowNum, 7, '', $styleBorder);
		}
		if ($item['kredit'] !== null) {
			xlsWriteCellStyle($rowNum, 8, jurnal_umum_format_rupiah($item['kredit']), $styleRight);
		} else {
			xlsWriteCellStyle($rowNum, 8, '', $styleBorder);
		}
		$rowNum++;
	}

	xlsAddMerge($rowNum, 0, $rowNum, 6);
	xlsWriteCellStyle($rowNum, 0, 'JUMLAH DEBET / KREDIT', $styleFooter);
	for ($c = 1; $c <= 6; $c++) {
		xlsEnsureCellStyle($rowNum, $c, $styleFooter, '');
	}
	xlsWriteCellStyle($rowNum, 7, jurnal_umum_format_rupiah($data['Total_debet'], true), $styleFooter);
	xlsWriteCellStyle($rowNum, 8, jurnal_umum_format_rupiah($data['Total_kredit'], true), $styleFooter);

	xlsEOF();
}

function jurnal_umum_export_excel_table_preview_output($CI, $table)
{
	@set_time_limit(600);
	$CI->load->helper(array('exportexcel', 'pembelian_persediaan'));

	$preview = persediaan_compare_preview_table_data($CI, $table, 50000);
	if (empty($preview['ok'])) {
		xlsBOF();
		xlsWriteLabel(0, 0, isset($preview['message']) ? $preview['message'] : 'Export gagal.');
		xlsEOF();
		return;
	}

	$namaFile = 'Preview_' . $table . '_' . date('Y-m-d_H-i-s') . '.xlsx';
	excel_prepare_download($namaFile);

	$styleHeader = 4;
	$styleBorder = 3;
	$styleLeft = 7;

	xlsBOF();
	xlsWriteLabelBold14(0, 0, 'Detail Tabel: ' . $table);
	xlsWriteLabel(1, 0, 'Dicetak: ' . date('d/m/Y H:i:s'));

	$cols = isset($preview['columns']) ? $preview['columns'] : array();
	$headerRow = 3;
	foreach ($cols as $i => $col) {
		xlsWriteCellStyle($headerRow, $i, $col, $styleHeader);
	}

	$rowNum = 4;
	foreach ((array) (isset($preview['rows']) ? $preview['rows'] : array()) as $row) {
		foreach ($cols as $i => $col) {
			$val = isset($row[$col]) ? $row[$col] : '';
			xlsWriteCellStyle($rowNum, $i, $val, $styleLeft);
		}
		$rowNum++;
	}

	xlsEOF();
}
