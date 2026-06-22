<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function uang_muka_didepan_format_nominal($value)
{
	if ($value === null || $value === '') {
		return '';
	}
	return number_format((float) $value, 0, ',', '.');
}

function uang_muka_didepan_format_tanggal($tanggal)
{
	$ts = strtotime((string) $tanggal);
	return $ts ? date('d-m-Y', $ts) : trim((string) $tanggal);
}

function uang_muka_didepan_compute_rows($rows)
{
	$result = array();
	$no = 1;
	$total_nominal = 0;

	foreach ($rows as $row) {
		$nominal = (float) $row->nominal;
		$total_nominal += $nominal;

		$result[] = array(
			'no' => $no++,
			'tgl_transaksi' => uang_muka_didepan_format_tanggal($row->tgl_transaksi),
			'dari' => (string) $row->dari,
			'uraian' => (string) $row->uraian,
			'nominal' => $nominal,
			'bank' => (string) $row->bank,
			'nmr_rekening' => (string) $row->nmr_rekening,
		);
	}

	return array(
		'rows' => $result,
		'total_nominal' => $total_nominal,
	);
}

function uang_muka_didepan_excel_write_merged_row($row, $text, $styleIndex, $colEnd = 6)
{
	xlsAddMerge($row, 0, $row, (int) $colEnd);
	xlsWriteCellStyle($row, 0, $text, $styleIndex);
	for ($c = 1; $c <= (int) $colEnd; $c++) {
		xlsEnsureCellStyle($row, $c, $styleIndex, '');
	}
}

function uang_muka_didepan_excel_write_title_block()
{
	$styleTitleBoldLeft = 11;
	$styleTitleItalicLeft = 12;
	$styleTitleBoldCenter = 13;
	$styleMetaLeft = 16;

	uang_muka_didepan_excel_write_merged_row(0, 'PERUMDA ANEKA DHARMA', $styleTitleBoldLeft);
	uang_muka_didepan_excel_write_merged_row(1, 'KABUPATEN BANTUL', $styleTitleBoldLeft);
	uang_muka_didepan_excel_write_merged_row(2, 'Jl. Jend. Sudirman 36 Bantul. Telp / Fax : 0274 367123', $styleTitleItalicLeft);
	uang_muka_didepan_excel_write_merged_row(4, 'DATA UANG MUKA DI DEPAN', $styleTitleBoldCenter);
	uang_muka_didepan_excel_write_merged_row(5, 'Dicetak: ' . date('d/m/Y H:i:s'), $styleMetaLeft);
}

function uang_muka_didepan_excel_write_headers($rowNum)
{
	$styleHeader = 14;
	$headers = array('No', 'Tgl Transaksi', 'Dari', 'Uraian', 'Nominal', 'Bank', 'Nmr Rekening');
	foreach ($headers as $col => $label) {
		xlsWriteCellStyle($rowNum, $col, $label, $styleHeader);
	}
	return $rowNum + 1;
}

function uang_muka_didepan_excel_write_row($rowNum, $item)
{
	$styleBorder = 3;
	$styleLeft = 7;
	$styleRight = 8;

	xlsWriteCellStyle($rowNum, 0, (string) $item['no'], $styleBorder);
	xlsWriteCellStyle($rowNum, 1, $item['tgl_transaksi'], $styleBorder);
	xlsWriteCellStyle($rowNum, 2, $item['dari'], $styleLeft);
	xlsWriteCellStyle($rowNum, 3, $item['uraian'], $styleLeft);
	xlsWriteCellStyle($rowNum, 4, uang_muka_didepan_format_nominal($item['nominal']), $styleRight);
	xlsWriteCellStyle($rowNum, 5, $item['bank'], $styleLeft);
	xlsWriteCellStyle($rowNum, 6, $item['nmr_rekening'], $styleLeft);
}

function uang_muka_didepan_excel_write_footer($rowNum, $total_nominal)
{
	$styleFooter = 6;
	$styleFooterRight = 10;

	xlsWriteCellStyle($rowNum, 0, '', $styleFooter);
	xlsWriteCellStyle($rowNum, 1, '', $styleFooter);
	xlsWriteCellStyle($rowNum, 2, '', $styleFooter);
	xlsWriteCellStyle($rowNum, 3, 'TOTAL', $styleFooter);
	xlsWriteCellStyle($rowNum, 4, uang_muka_didepan_format_nominal($total_nominal), $styleFooterRight);
	xlsWriteCellStyle($rowNum, 5, '', $styleFooter);
	xlsWriteCellStyle($rowNum, 6, '', $styleFooter);
}

function uang_muka_didepan_export_excel_output($CI)
{
	@set_time_limit(600);
	$CI->load->helper('exportexcel');

	$rows = $CI->Tbl_uang_muka_didepan_model->get_all();
	$data = uang_muka_didepan_compute_rows($rows);

	$namaFile = 'DATA_UANG_MUKA_DI_DEPAN_' . date('Y-m-d_H-i-s') . '.xlsx';
	excel_prepare_download($namaFile);

	xlsBOF();
	xlsSetColumnWidths(array(6, 14, 22, 42, 18, 18, 20));

	uang_muka_didepan_excel_write_title_block();

	$rowNum = uang_muka_didepan_excel_write_headers(7);
	foreach ($data['rows'] as $item) {
		uang_muka_didepan_excel_write_row($rowNum, $item);
		$rowNum++;
	}

	uang_muka_didepan_excel_write_footer($rowNum, $data['total_nominal']);

	xlsEOF();
}
