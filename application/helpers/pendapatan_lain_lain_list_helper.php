<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function pendapatan_lain_lain_format_nominal($value)
{
	if ($value === null || $value === '') {
		return '';
	}
	return number_format((float) $value, 0, ',', '.');
}

function pendapatan_lain_lain_format_tanggal($tanggal)
{
	$ts = strtotime((string) $tanggal);
	return $ts ? date('d M Y', $ts) : trim((string) $tanggal);
}

function pendapatan_lain_lain_compute_rows($rows)
{
	$result = array();
	$no = 1;

	foreach ($rows as $row) {
		$result[] = array(
			'no' => $no++,
			'tgl_transaksi' => pendapatan_lain_lain_format_tanggal($row->tgl_transaksi),
			'kode' => (string) $row->kode,
			'dari' => (string) $row->dari,
			'uraian' => (string) $row->uraian,
			'nominal' => (float) $row->nominal,
			'bank' => (string) $row->bank,
			'nmr_rekening' => (string) $row->nmr_rekening,
		);
	}

	return $result;
}

function pendapatan_lain_lain_excel_write_merged_row($row, $text, $styleIndex, $colEnd = 7)
{
	xlsAddMerge($row, 0, $row, (int) $colEnd);
	xlsWriteCellStyle($row, 0, $text, $styleIndex);
	for ($c = 1; $c <= (int) $colEnd; $c++) {
		xlsEnsureCellStyle($row, $c, $styleIndex, '');
	}
}

function pendapatan_lain_lain_excel_write_title_block()
{
	$styleTitleBoldLeft = 11;
	$styleTitleItalicLeft = 12;
	$styleTitleBoldCenter = 13;
	$styleMetaLeft = 16;

	pendapatan_lain_lain_excel_write_merged_row(0, 'PERUMDA ANEKA DHARMA', $styleTitleBoldLeft);
	pendapatan_lain_lain_excel_write_merged_row(1, 'KABUPATEN BANTUL', $styleTitleBoldLeft);
	pendapatan_lain_lain_excel_write_merged_row(2, 'Jl. Jend. Sudirman 36 Bantul. Telp / Fax : 0274 367123', $styleTitleItalicLeft);
	pendapatan_lain_lain_excel_write_merged_row(4, 'PENDAPATAN LAIN-LAIN', $styleTitleBoldCenter);
	pendapatan_lain_lain_excel_write_merged_row(5, 'Dicetak: ' . date('d/m/Y H:i:s'), $styleMetaLeft);
}

function pendapatan_lain_lain_excel_write_headers($rowNum)
{
	$styleHeader = 14;
	$headers = array('No', 'Tgl Transaksi', 'Kode', 'Dari', 'Uraian', 'Nominal', 'Bank', 'Nmr Rekening');
	foreach ($headers as $col => $label) {
		xlsWriteCellStyle($rowNum, $col, $label, $styleHeader);
	}
	return $rowNum + 1;
}

function pendapatan_lain_lain_excel_write_row($rowNum, $item)
{
	$styleBorder = 3;
	$styleLeft = 7;
	$styleRight = 8;

	xlsWriteCellStyle($rowNum, 0, (string) $item['no'], $styleBorder);
	xlsWriteCellStyle($rowNum, 1, $item['tgl_transaksi'], $styleBorder);
	xlsWriteCellStyle($rowNum, 2, $item['kode'], $styleLeft);
	xlsWriteCellStyle($rowNum, 3, $item['dari'], $styleLeft);
	xlsWriteCellStyle($rowNum, 4, $item['uraian'], $styleLeft);
	xlsWriteCellStyle($rowNum, 5, pendapatan_lain_lain_format_nominal($item['nominal']), $styleRight);
	xlsWriteCellStyle($rowNum, 6, $item['bank'], $styleLeft);
	xlsWriteCellStyle($rowNum, 7, $item['nmr_rekening'], $styleLeft);
}

function pendapatan_lain_lain_export_excel_output($CI)
{
	@set_time_limit(600);
	$CI->load->helper('exportexcel');

	$rows = $CI->Tbl_pendapatan_lain_lain_model->get_all();
	$data = pendapatan_lain_lain_compute_rows($rows);

	$namaFile = 'PENDAPATAN_LAIN_LAIN_' . date('Y-m-d_H-i-s') . '.xlsx';
	excel_prepare_download($namaFile);

	xlsBOF();
	xlsSetColumnWidths(array(6, 14, 12, 22, 42, 18, 18, 20));

	pendapatan_lain_lain_excel_write_title_block();

	$rowNum = pendapatan_lain_lain_excel_write_headers(7);
	foreach ($data as $item) {
		pendapatan_lain_lain_excel_write_row($rowNum, $item);
		$rowNum++;
	}

	xlsEOF();
}
