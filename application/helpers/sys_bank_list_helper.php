<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function sys_bank_compute_rows($rows)
{
	$result = array();
	$no = 1;

	foreach ($rows as $row) {
		$result[] = array(
			'no' => $no++,
			'kode_bank' => (string) $row->kode_bank,
			'nama_bank' => (string) $row->nama_bank,
			'nmr_rekening' => (string) $row->nmr_rekening,
		);
	}

	return $result;
}

function sys_bank_excel_write_merged_row($row, $text, $styleIndex, $colEnd = 3)
{
	xlsAddMerge($row, 0, $row, (int) $colEnd);
	xlsWriteCellStyle($row, 0, $text, $styleIndex);
	for ($c = 1; $c <= (int) $colEnd; $c++) {
		xlsEnsureCellStyle($row, $c, $styleIndex, '');
	}
}

function sys_bank_excel_write_title_block()
{
	$styleTitleBoldLeft = 11;
	$styleTitleItalicLeft = 12;
	$styleTitleBoldCenter = 13;
	$styleMetaLeft = 16;

	sys_bank_excel_write_merged_row(0, 'PERUMDA ANEKA DHARMA', $styleTitleBoldLeft);
	sys_bank_excel_write_merged_row(1, 'KABUPATEN BANTUL', $styleTitleBoldLeft);
	sys_bank_excel_write_merged_row(2, 'Jl. Jend. Sudirman 36 Bantul. Telp / Fax : 0274 367123', $styleTitleItalicLeft);
	sys_bank_excel_write_merged_row(4, 'DATA BANK', $styleTitleBoldCenter);
	sys_bank_excel_write_merged_row(5, 'Dicetak: ' . date('d/m/Y H:i:s'), $styleMetaLeft);
}

function sys_bank_excel_write_headers($rowNum)
{
	$styleHeader = 14;
	$headers = array('No', 'Kode Bank', 'Nama Bank', 'Nmr Rekening');
	foreach ($headers as $col => $label) {
		xlsWriteCellStyle($rowNum, $col, $label, $styleHeader);
	}
	return $rowNum + 1;
}

function sys_bank_excel_write_row($rowNum, $item)
{
	$styleBorder = 3;
	$styleLeft = 7;

	xlsWriteCellStyle($rowNum, 0, (string) $item['no'], $styleBorder);
	xlsWriteCellStyle($rowNum, 1, $item['kode_bank'], $styleLeft);
	xlsWriteCellStyle($rowNum, 2, $item['nama_bank'], $styleLeft);
	xlsWriteCellStyle($rowNum, 3, $item['nmr_rekening'], $styleLeft);
}

function sys_bank_export_excel_output($CI)
{
	@set_time_limit(600);
	$CI->load->helper('exportexcel');

	$rows = $CI->Sys_bank_model->get_all();
	$data = sys_bank_compute_rows($rows);

	$namaFile = 'DATA_BANK_' . date('Y-m-d_H-i-s') . '.xlsx';
	excel_prepare_download($namaFile);

	xlsBOF();
	xlsSetColumnWidths(array(6, 14, 32, 24));

	sys_bank_excel_write_title_block();

	$rowNum = sys_bank_excel_write_headers(7);
	foreach ($data as $item) {
		sys_bank_excel_write_row($rowNum, $item);
		$rowNum++;
	}

	xlsEOF();
}
