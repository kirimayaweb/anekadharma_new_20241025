<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function tbl_penjualan_accounting_format_nominal($value, $decimals = 0)
{
	if ($value === null || $value === '') {
		return '';
	}
	return number_format((float) $value, (int) $decimals, ',', '.');
}

function tbl_penjualan_accounting_format_tanggal($tanggal)
{
	$ts = strtotime((string) $tanggal);
	return $ts ? date('d M Y', $ts) : trim((string) $tanggal);
}

function tbl_penjualan_accounting_hitung_kolom($list_data)
{
	$jumlah_total = (float) $list_data->jumlah * (float) $list_data->harga_satuan;

	return array(
		'jumlah_qty' => (float) $list_data->jumlah,
		'harga_satuan' => (float) $list_data->harga_satuan,
		'jumlah_total' => $jumlah_total,
		'umpphpsl22' => ($jumlah_total * 1.351351) / 100,
		'piutang' => $jumlah_total - (($jumlah_total * 11.261261) / 100),
		'penjualandpp' => ($jumlah_total * 90.090090) / 100,
		'utangppn' => ($jumlah_total * 9.909910) / 100,
	);
}

function tbl_penjualan_accounting_build_export_rows($Tbl_penjualan_data)
{
	$export_rows = array();
	$compare_tgl_jual = 0;
	$compare_nmr_kirim = 0;
	$Total_Jumlah_per_nmrkirim = 0;
	$Total_UMPPHPSL22_per_nmrkirim = 0;
	$Total_piutang_per_nmrkirim = 0;
	$Total_penjualandpp_per_nmrkirim = 0;
	$Total_utangppn_per_nmrkirim = 0;
	$TOTAL_ALL_JUMLAH = 0;
	$TOTAL_ALL_UMPPHPSL22 = 0;
	$TOTAL_ALL_piutang = 0;
	$TOTAL_ALL_penjualandpp = 0;
	$TOTAL_ALL_utangppn = 0;
	$start = 0;

	foreach ($Tbl_penjualan_data as $list_data) {
		if (($start >= 1) && (((string) $compare_nmr_kirim !== (string) $list_data->nmrkirim) || ((string) $compare_tgl_jual !== (string) $list_data->tgl_jual))) {
			$export_rows[] = tbl_penjualan_accounting_build_subtotal_row(
				++$start,
				$compare_nmr_kirim,
				$Total_Jumlah_per_nmrkirim,
				$Total_UMPPHPSL22_per_nmrkirim,
				$Total_piutang_per_nmrkirim,
				$Total_penjualandpp_per_nmrkirim,
				$Total_utangppn_per_nmrkirim
			);

			$Total_Jumlah_per_nmrkirim = 0;
			$Total_UMPPHPSL22_per_nmrkirim = 0;
			$Total_piutang_per_nmrkirim = 0;
			$Total_penjualandpp_per_nmrkirim = 0;
			$Total_utangppn_per_nmrkirim = 0;
		}

		$calc = tbl_penjualan_accounting_hitung_kolom($list_data);
		$Total_Jumlah_per_nmrkirim += $calc['jumlah_total'];
		$Total_UMPPHPSL22_per_nmrkirim += $calc['umpphpsl22'];
		$Total_piutang_per_nmrkirim += $calc['piutang'];
		$Total_penjualandpp_per_nmrkirim += $calc['penjualandpp'];
		$Total_utangppn_per_nmrkirim += $calc['utangppn'];
		$TOTAL_ALL_JUMLAH += $calc['jumlah_total'];
		$TOTAL_ALL_UMPPHPSL22 += $calc['umpphpsl22'];
		$TOTAL_ALL_piutang += $calc['piutang'];
		$TOTAL_ALL_penjualandpp += $calc['penjualandpp'];
		$TOTAL_ALL_utangppn += $calc['utangppn'];

		$export_rows[] = array(
			'type' => 'data',
			'no' => ++$start,
			'tgl_jual' => tbl_penjualan_accounting_format_tanggal($list_data->tgl_jual),
			'nmrkirim' => (string) $list_data->nmrkirim,
			'nmrpesan' => (string) $list_data->nmrpesan,
			'konsumen_nama' => (string) $list_data->konsumen_nama,
			'kode_barang' => (string) $list_data->kode_barang,
			'nama_barang' => (string) $list_data->nama_barang,
			'unit' => (string) $list_data->unit,
			'satuan' => (string) $list_data->satuan,
			'harga_satuan' => $calc['harga_satuan'],
			'jumlah' => $calc['jumlah_qty'],
			'total_harga' => $calc['jumlah_total'],
			'umpphpsl22' => $calc['umpphpsl22'],
			'piutang' => $calc['piutang'],
			'penjualandpp' => $calc['penjualandpp'],
			'utangppn' => $calc['utangppn'],
		);

		$compare_nmr_kirim = $list_data->nmrkirim;
		$compare_tgl_jual = $list_data->tgl_jual;
	}

	if (!empty($export_rows)) {
		$export_rows[] = tbl_penjualan_accounting_build_subtotal_row(
			++$start,
			$compare_nmr_kirim,
			$Total_Jumlah_per_nmrkirim,
			$Total_UMPPHPSL22_per_nmrkirim,
			$Total_piutang_per_nmrkirim,
			$Total_penjualandpp_per_nmrkirim,
			$Total_utangppn_per_nmrkirim
		);

		$export_rows[] = array(
			'type' => 'grand_total',
			'no' => '',
			'tgl_jual' => '',
			'nmrkirim' => '',
			'nmrpesan' => '',
			'konsumen_nama' => '',
			'kode_barang' => '',
			'nama_barang' => '',
			'unit' => '',
			'satuan' => '',
			'harga_satuan' => '',
			'jumlah' => 'TOTAL',
			'total_harga' => $TOTAL_ALL_JUMLAH,
			'umpphpsl22' => $TOTAL_ALL_UMPPHPSL22,
			'piutang' => $TOTAL_ALL_piutang,
			'penjualandpp' => $TOTAL_ALL_penjualandpp,
			'utangppn' => $TOTAL_ALL_utangppn,
		);
	}

	return $export_rows;
}

function tbl_penjualan_accounting_build_subtotal_row($no, $nmrkirim, $total_jumlah, $total_umpphpsl22, $total_piutang, $total_penjualandpp, $total_utangppn)
{
	return array(
		'type' => 'subtotal',
		'no' => $no,
		'tgl_jual' => 'TOTAL',
		'nmrkirim' => (string) $nmrkirim,
		'nmrpesan' => '',
		'konsumen_nama' => '',
		'kode_barang' => '',
		'nama_barang' => '',
		'unit' => '',
		'satuan' => '',
		'harga_satuan' => '',
		'jumlah' => '',
		'total_harga' => $total_jumlah,
		'umpphpsl22' => $total_umpphpsl22,
		'piutang' => $total_piutang,
		'penjualandpp' => $total_penjualandpp,
		'utangppn' => $total_utangppn,
	);
}

function tbl_penjualan_accounting_excel_write_merged_row($row, $text, $styleIndex, $colEnd = 15)
{
	xlsAddMerge($row, 0, $row, (int) $colEnd);
	xlsWriteCellStyle($row, 0, $text, $styleIndex);
	for ($c = 1; $c <= (int) $colEnd; $c++) {
		xlsEnsureCellStyle($row, $c, $styleIndex, '');
	}
}

function tbl_penjualan_accounting_excel_write_title_block()
{
	$styleTitleBoldLeft = 11;
	$styleTitleItalicLeft = 12;
	$styleTitleBoldCenter = 13;
	$styleMetaLeft = 16;

	tbl_penjualan_accounting_excel_write_merged_row(0, 'PERUMDA ANEKA DHARMA', $styleTitleBoldLeft);
	tbl_penjualan_accounting_excel_write_merged_row(1, 'KABUPATEN BANTUL', $styleTitleBoldLeft);
	tbl_penjualan_accounting_excel_write_merged_row(2, 'Jl. Jend. Sudirman 36 Bantul. Telp / Fax : 0274 367123', $styleTitleItalicLeft);
	tbl_penjualan_accounting_excel_write_merged_row(4, 'DATA PENJUALAN ACCOUNTING', $styleTitleBoldCenter);
	tbl_penjualan_accounting_excel_write_merged_row(5, 'Dicetak: ' . date('d/m/Y H:i:s'), $styleMetaLeft);
}

function tbl_penjualan_accounting_excel_write_headers()
{
	$styleHeader = 14;
	$rowTop = 7;
	$rowSub = 8;

	$topHeaders = array(
		array('label' => 'No', 'col' => 0, 'rowspan' => 2),
		array('label' => 'Tgl Jual', 'col' => 1, 'rowspan' => 2),
		array('label' => 'nmrkirim', 'col' => 2, 'rowspan' => 2),
		array('label' => 'nmrpesan', 'col' => 3, 'rowspan' => 2),
		array('label' => 'Konsumen', 'col' => 4, 'rowspan' => 2),
		array('label' => 'Kode', 'col' => 5, 'rowspan' => 2),
		array('label' => 'Nama Barang', 'col' => 6, 'rowspan' => 2),
		array('label' => 'Unit', 'col' => 7, 'rowspan' => 2),
		array('label' => 'Satuan', 'col' => 8, 'rowspan' => 2),
		array('label' => 'Harga Satuan', 'col' => 9, 'rowspan' => 2),
		array('label' => 'Jumlah', 'col' => 10, 'rowspan' => 2),
		array('label' => 'Total harga', 'col' => 11, 'rowspan' => 2),
		array('label' => 'Debit', 'col' => 12, 'colspan' => 2),
		array('label' => 'Kredit', 'col' => 14, 'colspan' => 2),
	);

	foreach ($topHeaders as $header) {
		$col = (int) $header['col'];
		xlsWriteCellStyle($rowTop, $col, $header['label'], $styleHeader);
		if (!empty($header['rowspan']) && (int) $header['rowspan'] > 1) {
			xlsAddMerge($rowTop, $col, $rowSub, $col);
			xlsEnsureCellStyle($rowSub, $col, $styleHeader, '');
		}
		if (!empty($header['colspan']) && (int) $header['colspan'] > 1) {
			$colEnd = $col + (int) $header['colspan'] - 1;
			xlsAddMerge($rowTop, $col, $rowTop, $colEnd);
			for ($c = $col + 1; $c <= $colEnd; $c++) {
				xlsEnsureCellStyle($rowTop, $c, $styleHeader, '');
			}
		}
	}

	$subHeaders = array(
		12 => 'UM PPH PSL 22',
		13 => 'Piutang',
		14 => 'Penjualan DPP',
		15 => 'Utang PPN',
	);
	foreach ($subHeaders as $col => $label) {
		xlsWriteCellStyle($rowSub, (int) $col, $label, $styleHeader);
	}

	return $rowSub + 1;
}

function tbl_penjualan_accounting_excel_write_text_cell($rowNum, $col, $value, $styleIndex)
{
	xlsWriteCellStyle($rowNum, (int) $col, (string) $value, (int) $styleIndex);
}

function tbl_penjualan_accounting_excel_write_amount_cell($rowNum, $col, $value, $styleIndex, $decimals = 2)
{
	if ($value === '' || $value === null) {
		tbl_penjualan_accounting_excel_write_text_cell($rowNum, $col, '', $styleIndex);
		return;
	}

	tbl_penjualan_accounting_excel_write_text_cell(
		$rowNum,
		$col,
		tbl_penjualan_accounting_format_nominal($value, $decimals),
		$styleIndex
	);
}

function tbl_penjualan_accounting_excel_write_row($rowNum, $item)
{
	$styleBorder = 3;
	$styleLeft = 7;
	$styleRight = 8;
	$styleSubtotal = 6;
	$styleSubtotalRight = 10;
	$styleGrandTotal = 4;
	$styleGrandTotalRight = 10;

	$type = isset($item['type']) ? $item['type'] : 'data';

	if ($type === 'subtotal') {
		tbl_penjualan_accounting_excel_write_text_cell($rowNum, 0, $item['no'], $styleSubtotal);
		tbl_penjualan_accounting_excel_write_text_cell($rowNum, 1, $item['tgl_jual'], $styleSubtotal);
		tbl_penjualan_accounting_excel_write_text_cell($rowNum, 2, $item['nmrkirim'], $styleSubtotal);
		for ($col = 3; $col <= 10; $col++) {
			tbl_penjualan_accounting_excel_write_text_cell($rowNum, $col, '', $styleSubtotal);
		}
		tbl_penjualan_accounting_excel_write_amount_cell($rowNum, 11, $item['total_harga'], $styleSubtotalRight);
		tbl_penjualan_accounting_excel_write_amount_cell($rowNum, 12, $item['umpphpsl22'], $styleSubtotalRight);
		tbl_penjualan_accounting_excel_write_amount_cell($rowNum, 13, $item['piutang'], $styleSubtotalRight);
		tbl_penjualan_accounting_excel_write_amount_cell($rowNum, 14, $item['penjualandpp'], $styleSubtotalRight);
		tbl_penjualan_accounting_excel_write_amount_cell($rowNum, 15, $item['utangppn'], $styleSubtotalRight);
		return;
	}

	if ($type === 'grand_total') {
		for ($col = 0; $col <= 9; $col++) {
			tbl_penjualan_accounting_excel_write_text_cell($rowNum, $col, '', $styleGrandTotal);
		}
		tbl_penjualan_accounting_excel_write_text_cell($rowNum, 10, $item['jumlah'], $styleGrandTotal);
		tbl_penjualan_accounting_excel_write_amount_cell($rowNum, 11, $item['total_harga'], $styleGrandTotalRight);
		tbl_penjualan_accounting_excel_write_amount_cell($rowNum, 12, $item['umpphpsl22'], $styleGrandTotalRight);
		tbl_penjualan_accounting_excel_write_amount_cell($rowNum, 13, $item['piutang'], $styleGrandTotalRight);
		tbl_penjualan_accounting_excel_write_amount_cell($rowNum, 14, $item['penjualandpp'], $styleGrandTotalRight);
		tbl_penjualan_accounting_excel_write_amount_cell($rowNum, 15, $item['utangppn'], $styleGrandTotalRight);
		return;
	}

	tbl_penjualan_accounting_excel_write_text_cell($rowNum, 0, $item['no'], $styleBorder);
	tbl_penjualan_accounting_excel_write_text_cell($rowNum, 1, $item['tgl_jual'], $styleBorder);
	tbl_penjualan_accounting_excel_write_text_cell($rowNum, 2, $item['nmrkirim'], $styleLeft);
	tbl_penjualan_accounting_excel_write_text_cell($rowNum, 3, $item['nmrpesan'], $styleLeft);
	tbl_penjualan_accounting_excel_write_text_cell($rowNum, 4, $item['konsumen_nama'], $styleLeft);
	tbl_penjualan_accounting_excel_write_text_cell($rowNum, 5, $item['kode_barang'], $styleLeft);
	tbl_penjualan_accounting_excel_write_text_cell($rowNum, 6, $item['nama_barang'], $styleLeft);
	tbl_penjualan_accounting_excel_write_text_cell($rowNum, 7, $item['unit'], $styleLeft);
	tbl_penjualan_accounting_excel_write_text_cell($rowNum, 8, $item['satuan'], $styleLeft);
	tbl_penjualan_accounting_excel_write_amount_cell($rowNum, 9, $item['harga_satuan'], $styleRight, 2);
	tbl_penjualan_accounting_excel_write_amount_cell($rowNum, 10, $item['jumlah'], $styleRight, 0);
	tbl_penjualan_accounting_excel_write_amount_cell($rowNum, 11, $item['total_harga'], $styleRight, 2);
	tbl_penjualan_accounting_excel_write_amount_cell($rowNum, 12, $item['umpphpsl22'], $styleRight, 2);
	tbl_penjualan_accounting_excel_write_amount_cell($rowNum, 13, $item['piutang'], $styleRight, 2);
	tbl_penjualan_accounting_excel_write_amount_cell($rowNum, 14, $item['penjualandpp'], $styleRight, 2);
	tbl_penjualan_accounting_excel_write_amount_cell($rowNum, 15, $item['utangppn'], $styleRight, 2);
}

function tbl_penjualan_accounting_export_excel_output($CI)
{
	@set_time_limit(600);
	$CI->load->helper('exportexcel');

	$rows = $CI->Tbl_penjualan_accounting_model->get_all_group_by_tgl_jual_nmrpesan_nmr_kirim();
	$data = tbl_penjualan_accounting_build_export_rows($rows);

	$namaFile = 'DATA_PENJUALAN_ACCOUNTING_' . date('Y-m-d_H-i-s') . '.xlsx';
	excel_prepare_download($namaFile);

	xlsBOF();
	xlsSetColumnWidths(array(6, 14, 12, 12, 24, 10, 28, 10, 10, 14, 10, 16, 16, 16, 16, 16));

	tbl_penjualan_accounting_excel_write_title_block();
	$rowNum = tbl_penjualan_accounting_excel_write_headers();

	foreach ($data as $item) {
		tbl_penjualan_accounting_excel_write_row($rowNum, $item);
		$rowNum++;
	}

	xlsEOF();
}
