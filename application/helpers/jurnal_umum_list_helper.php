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
		$debet = (float) (isset($row->debet) ? $row->debet : (isset($row->debit) ? $row->debit : 0));
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
			'kode_rekening' => isset($row->kode_rekening) ? $row->kode_rekening : (isset($row->uraian_kode_rekening) ? $row->uraian_kode_rekening : ''),
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
		'month' => (int) date('n', strtotime($date_awal)),
		'year' => (int) date('Y', strtotime($date_awal)),
		'bulan_label' => jurnal_umum_bulan_teks((int) date('n', strtotime($date_awal))) . ' ' . date('Y', strtotime($date_awal)),
		'periode_label' => jurnal_umum_format_tanggal_display($date_awal) . ' s/d ' . jurnal_umum_format_tanggal_display($date_akhir),
	);
}

function jurnal_umum_excel_title_col_end()
{
	return 8;
}

function jurnal_umum_excel_write_merged_row($rowNum, $text, $styleIndex, $colEnd = null)
{
	if ($colEnd === null) {
		$colEnd = jurnal_umum_excel_title_col_end();
	}

	xlsAddMerge($rowNum, 0, $rowNum, $colEnd);
	xlsWriteCellStyle($rowNum, 0, $text, $styleIndex);
	for ($c = 1; $c <= $colEnd; $c++) {
		xlsEnsureCellStyle($rowNum, $c, $styleIndex, '');
	}
}

function jurnal_umum_excel_write_merged_region($rowStart, $colStart, $rowEnd, $colEnd, $text, $styleIndex)
{
	if ($rowStart !== $rowEnd || $colStart !== $colEnd) {
		xlsAddMerge($rowStart, $colStart, $rowEnd, $colEnd);
	}
	xlsWriteCellStyle($rowStart, $colStart, $text, $styleIndex);
	for ($r = (int) $rowStart; $r <= (int) $rowEnd; $r++) {
		for ($c = (int) $colStart; $c <= (int) $colEnd; $c++) {
			if ($r === (int) $rowStart && $c === (int) $colStart) {
				continue;
			}
			xlsEnsureCellStyle($r, $c, $styleIndex, '');
		}
	}
}

function jurnal_umum_excel_write_title_block($month, $year)
{
	$styleTitleBoldLeft = 11;
	$styleTitleItalicLeft = 12;
	$styleTitleBoldCenter = 13;
	$bulan_nama = strtoupper(jurnal_umum_bulan_teks((int) $month));
	$titleColEnd = jurnal_umum_excel_title_col_end();

	jurnal_umum_excel_write_merged_row(0, 'PERUMDA ANEKA DHARMA', $styleTitleBoldLeft, $titleColEnd);
	jurnal_umum_excel_write_merged_row(1, 'KABUPATEN BANTUL', $styleTitleBoldLeft, $titleColEnd);
	jurnal_umum_excel_write_merged_row(2, 'Jl.Jend Sudirman 36 Bantul. Telp/Fax : 0274 367123', $styleTitleItalicLeft, $titleColEnd);

	jurnal_umum_excel_write_merged_row(
		4,
		'JURNAL UMUM ' . $bulan_nama . ' ' . (int) $year,
		$styleTitleBoldCenter,
		$titleColEnd
	);
}

function jurnal_umum_excel_write_table_headers()
{
	$styleTableHeaderGreen = 14;
	$rowGroup1 = 6;
	$rowGroup2 = 7;

	$labelsFixed = array('No', 'Tanggal', 'Bukti', 'PL', 'Ref');
	foreach ($labelsFixed as $col => $label) {
		jurnal_umum_excel_write_merged_region($rowGroup1, $col, $rowGroup2, $col, $label, $styleTableHeaderGreen);
	}

	jurnal_umum_excel_write_merged_region($rowGroup1, 5, $rowGroup1, 6, 'Uraian', $styleTableHeaderGreen);
	jurnal_umum_excel_write_merged_region($rowGroup1, 7, $rowGroup2, 7, 'Debet', $styleTableHeaderGreen);
	jurnal_umum_excel_write_merged_region($rowGroup1, 8, $rowGroup2, 8, 'Kredit', $styleTableHeaderGreen);
	xlsWriteCellStyle($rowGroup2, 5, 'Kode Rek.', $styleTableHeaderGreen);
	xlsWriteCellStyle($rowGroup2, 6, 'Rek.', $styleTableHeaderGreen);

	return 8;
}

function jurnal_umum_excel_write_data_row($rowNum, $item)
{
	$styleBorder = 3;
	$styleRight = 8;
	$styleLeft = 7;

	xlsWriteCellStyle($rowNum, 0, $item['no'], $styleBorder);
	xlsWriteCellStyle($rowNum, 1, $item['tanggal'], $styleLeft);
	xlsWriteCellStyle($rowNum, 2, $item['bukti'], $styleLeft);
	xlsWriteCellStyle($rowNum, 3, $item['pl'], $styleLeft);
	xlsWriteCellStyle($rowNum, 4, $item['ref'], $styleLeft);
	xlsWriteCellStyle($rowNum, 5, $item['kode_rekening'], $styleLeft);
	xlsWriteCellStyle($rowNum, 6, $item['rekening'], $styleLeft);

	if ($item['debet'] !== null && $item['debet'] !== '') {
		xlsWriteCellStyle($rowNum, 7, isset($item['debet_display']) ? $item['debet_display'] : jurnal_umum_format_rupiah($item['debet']), $styleRight);
	} else {
		xlsWriteCellStyle($rowNum, 7, '', $styleBorder);
	}

	if ($item['kredit'] !== null && $item['kredit'] !== '') {
		xlsWriteCellStyle($rowNum, 8, isset($item['kredit_display']) ? $item['kredit_display'] : jurnal_umum_format_rupiah($item['kredit']), $styleRight);
	} else {
		xlsWriteCellStyle($rowNum, 8, '', $styleBorder);
	}
}

function jurnal_umum_excel_write_footer_row($rowNum, $total_debet, $total_kredit)
{
	$styleFooterLabel = 9;
	$styleFooterAmount = 10;

	xlsAddMerge($rowNum, 0, $rowNum, 6);
	xlsWriteCellStyle($rowNum, 0, 'JUMLAH DEBET / KREDIT', $styleFooterLabel);
	for ($c = 1; $c <= 6; $c++) {
		xlsEnsureCellStyle($rowNum, $c, $styleFooterLabel, '');
	}
	xlsWriteCellStyle($rowNum, 7, jurnal_umum_format_rupiah($total_debet, true), $styleFooterAmount);
	xlsWriteCellStyle($rowNum, 8, jurnal_umum_format_rupiah($total_kredit, true), $styleFooterAmount);
}

function jurnal_umum_export_excel_list_output($CI, $date_awal = null, $date_akhir = null)
{
	@set_time_limit(600);
	$CI->load->helper('exportexcel');

	if ($date_awal === null) {
		$bulan_ns = trim((string) $CI->input->post('bulan_ns', TRUE));
		if (preg_match('/^(\d{4})-(\d{2})$/', $bulan_ns, $m)) {
			$year = (int) $m[1];
			$month = (int) $m[2];
			$date_awal = sprintf('%04d-%02d-01', $year, $month);
			$date_akhir = date('Y-m-t', strtotime($date_awal));
		} else {
			$date_awal = $CI->input->post('tgl_awal', TRUE);
		}
	}
	if ($date_akhir === null) {
		$date_akhir = $CI->input->post('tgl_akhir', TRUE);
	}

	$data = jurnal_umum_compute_list_data_by_date_range($CI, $date_awal, $date_akhir);
	$month = (int) $data['month'];
	$year = (int) $data['year'];

	$namaFile = 'Jurnal_Umum_' . str_pad((string) $month, 2, '0', STR_PAD_LEFT) . '_' . $year . '_' . date('Y-m-d_H-i-s') . '.xlsx';
	excel_prepare_download($namaFile);

	xlsBOF();
	xlsSetColumnWidths(array(5, 12, 12, 6, 8, 12, 34, 14, 14));

	jurnal_umum_excel_write_title_block($month, $year);

	$rowNum = jurnal_umum_excel_write_table_headers();
	foreach ($data['Data_Jurnal_Umum'] as $item) {
		jurnal_umum_excel_write_data_row($rowNum, $item);
		$rowNum++;
	}

	jurnal_umum_excel_write_footer_row($rowNum, $data['Total_debet'], $data['Total_kredit']);

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
