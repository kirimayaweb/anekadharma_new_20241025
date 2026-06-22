<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function kas_kecil_format_amount($value)
{
	if ($value === null || $value === '') {
		return '';
	}
	return number_format((float) $value, 2, ',', '.');
}

function kas_kecil_format_tanggal($tanggal)
{
	$ts = strtotime((string) $tanggal);
	return $ts ? date('d-m-Y', $ts) : trim((string) $tanggal);
}

function kas_kecil_compute_rows($rows)
{
	$result = array();
	$no = 1;
	$get_saldo = 0;
	$get_total_debet = 0;
	$get_total_kredit = 0;

	foreach ($rows as $row) {
		$debet = (float) $row->debet;
		$kredit = (float) $row->kredit;
		$get_total_debet += $debet;
		$get_total_kredit += $kredit;

		if ($get_saldo == 0) {
			$get_saldo = $debet - $kredit;
		} else {
			$get_saldo = $get_saldo + $debet - $kredit;
		}

		$result[] = array(
			'no' => $no++,
			'tanggal' => kas_kecil_format_tanggal($row->tanggal),
			'unit' => (string) $row->unit,
			'keterangan' => (string) $row->keterangan,
			'debet' => $debet,
			'kredit' => $kredit,
			'saldo' => $get_saldo,
		);
	}

	return array(
		'rows' => $result,
		'total_debet' => $get_total_debet,
		'total_kredit' => $get_total_kredit,
		'saldo_akhir' => $get_saldo,
	);
}

function kas_kecil_excel_write_merged_row($row, $text, $styleIndex, $colEnd = 6)
{
	xlsAddMerge($row, 0, $row, (int) $colEnd);
	xlsWriteCellStyle($row, 0, $text, $styleIndex);
	for ($c = 1; $c <= (int) $colEnd; $c++) {
		xlsEnsureCellStyle($row, $c, $styleIndex, '');
	}
}

function kas_kecil_excel_write_title_block()
{
	$styleTitleBoldLeft = 11;
	$styleTitleItalicLeft = 12;
	$styleTitleBoldCenter = 13;

	kas_kecil_excel_write_merged_row(0, 'PERUMDA ANEKA DHARMA', $styleTitleBoldLeft);
	kas_kecil_excel_write_merged_row(1, 'KABUPATEN BANTUL', $styleTitleBoldLeft);
	kas_kecil_excel_write_merged_row(2, 'Jl. Jend. Sudirman 36 Bantul. Telp / Fax : 0274 367123', $styleTitleItalicLeft);
	kas_kecil_excel_write_merged_row(4, 'KAS KECIL', $styleTitleBoldCenter);
}

function kas_kecil_excel_write_headers($rowNum)
{
	$styleHeader = 6;
	$headers = array('No', 'Tanggal', 'Unit', 'Keterangan', 'Debet', 'Kredit', 'Saldo');
	foreach ($headers as $col => $label) {
		xlsWriteCellStyle($rowNum, $col, $label, $styleHeader);
	}
	return $rowNum + 1;
}

function kas_kecil_excel_write_row($rowNum, $item)
{
	$styleBorder = 3;
	$styleLeft = 7;
	$styleRight = 8;

	xlsWriteCellStyle($rowNum, 0, (string) $item['no'], $styleBorder);
	xlsWriteCellStyle($rowNum, 1, $item['tanggal'], $styleBorder);
	xlsWriteCellStyle($rowNum, 2, $item['unit'], $styleLeft);
	xlsWriteCellStyle($rowNum, 3, $item['keterangan'], $styleLeft);
	xlsWriteCellStyle($rowNum, 4, kas_kecil_format_amount($item['debet']), $styleRight);
	xlsWriteCellStyle($rowNum, 5, kas_kecil_format_amount($item['kredit']), $styleRight);
	xlsWriteCellStyle($rowNum, 6, kas_kecil_format_amount($item['saldo']), $styleRight);
}

function kas_kecil_excel_write_footer($rowNum, $total_debet, $total_kredit, $saldo_akhir)
{
	$styleFooter = 5;
	$styleFooterRight = 10;

	xlsWriteCellStyle($rowNum, 0, '', $styleFooter);
	xlsWriteCellStyle($rowNum, 1, '', $styleFooter);
	xlsWriteCellStyle($rowNum, 2, '', $styleFooter);
	xlsWriteCellStyle($rowNum, 3, 'TOTAL', $styleFooter);
	xlsWriteCellStyle($rowNum, 4, kas_kecil_format_amount($total_debet), $styleFooterRight);
	xlsWriteCellStyle($rowNum, 5, kas_kecil_format_amount($total_kredit), $styleFooterRight);
	xlsWriteCellStyle($rowNum, 6, kas_kecil_format_amount($saldo_akhir), $styleFooterRight);
}

function kas_kecil_export_excel_output($CI)
{
	@set_time_limit(600);
	$CI->load->helper('exportexcel');

	$rows = $CI->Tbl_kas_kecil_model->get_all();
	$data = kas_kecil_compute_rows($rows);

	$namaFile = 'KAS_KECIL_' . date('Y-m-d_H-i-s') . '.xlsx';
	excel_prepare_download($namaFile);

	xlsBOF();
	xlsSetColumnWidths(array(6, 12, 14, 42, 16, 16, 16));

	kas_kecil_excel_write_title_block();

	$rowNum = kas_kecil_excel_write_headers(6);
	foreach ($data['rows'] as $item) {
		kas_kecil_excel_write_row($rowNum, $item);
		$rowNum++;
	}

	kas_kecil_excel_write_footer(
		$rowNum,
		$data['total_debet'],
		$data['total_kredit'],
		$data['saldo_akhir']
	);

	xlsEOF();
}
