<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function sys_kode_akun_compute_rows($rows)
{
	$result = array();
	$no = 1;

	foreach ($rows as $row) {
		$result[] = array(
			'no' => $no++,
			'kode_akun' => (string) $row->kode_akun,
			'nama_akun' => (string) $row->nama_akun,
			'group' => (string) $row->group,
		);
	}

	return $result;
}

function sys_kode_akun_excel_write_merged_row($row, $text, $styleIndex, $colEnd = 3)
{
	xlsAddMerge($row, 0, $row, (int) $colEnd);
	xlsWriteCellStyle($row, 0, $text, $styleIndex);
	for ($c = 1; $c <= (int) $colEnd; $c++) {
		xlsEnsureCellStyle($row, $c, $styleIndex, '');
	}
}

function sys_kode_akun_excel_write_title_block()
{
	$styleTitleBoldLeft = 11;
	$styleTitleItalicLeft = 12;
	$styleTitleBoldCenter = 13;
	$styleMetaLeft = 16;

	sys_kode_akun_excel_write_merged_row(0, 'PERUMDA ANEKA DHARMA', $styleTitleBoldLeft);
	sys_kode_akun_excel_write_merged_row(1, 'KABUPATEN BANTUL', $styleTitleBoldLeft);
	sys_kode_akun_excel_write_merged_row(2, 'Jl. Jend. Sudirman 36 Bantul. Telp / Fax : 0274 367123', $styleTitleItalicLeft);
	sys_kode_akun_excel_write_merged_row(4, 'DATA KODE AKUN', $styleTitleBoldCenter);
	sys_kode_akun_excel_write_merged_row(5, 'Dicetak: ' . date('d/m/Y H:i:s'), $styleMetaLeft);
}

function sys_kode_akun_excel_write_headers($rowNum)
{
	$styleHeader = 14;
	$headers = array('No', 'Kode Akun', 'Nama Akun', 'Group');
	foreach ($headers as $col => $label) {
		xlsWriteCellStyle($rowNum, $col, $label, $styleHeader);
	}
	return $rowNum + 1;
}

function sys_kode_akun_excel_write_row($rowNum, $item)
{
	$styleBorder = 3;
	$styleLeft = 7;

	xlsWriteCellStyle($rowNum, 0, (string) $item['no'], $styleBorder);
	xlsWriteCellStyle($rowNum, 1, $item['kode_akun'], $styleLeft);
	xlsWriteCellStyle($rowNum, 2, $item['nama_akun'], $styleLeft);
	xlsWriteCellStyle($rowNum, 3, $item['group'], $styleLeft);
}

function sys_kode_akun_export_excel_output($CI)
{
	@set_time_limit(600);
	$CI->load->helper('exportexcel');

	$rows = $CI->Sys_kode_akun_model->get_all();
	$data = sys_kode_akun_compute_rows($rows);

	$namaFile = 'DATA_KODE_AKUN_' . date('Y-m-d_H-i-s') . '.xlsx';
	excel_prepare_download($namaFile);

	xlsBOF();
	xlsSetColumnWidths(array(6, 14, 42, 20));

	sys_kode_akun_excel_write_title_block();

	$rowNum = sys_kode_akun_excel_write_headers(7);
	foreach ($data as $item) {
		sys_kode_akun_excel_write_row($rowNum, $item);
		$rowNum++;
	}

	xlsEOF();
}
