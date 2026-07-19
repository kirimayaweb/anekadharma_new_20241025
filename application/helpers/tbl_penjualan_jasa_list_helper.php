<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function tbl_penjualan_jasa_format_nominal($value, $decimals = 2)
{
	if ($value === null || $value === '') {
		return '';
	}
	return number_format((float) $value, (int) $decimals, ',', '.');
}

function tbl_penjualan_jasa_format_tanggal($tanggal)
{
	$ts = strtotime((string) $tanggal);
	return $ts ? date('d M Y', $ts) : trim((string) $tanggal);
}

/**
 * Rumus kolom tampilan penjualan jasa (sama dengan DataTable).
 */
function tbl_penjualan_jasa_hitung_kolom($list_data)
{
	$jumlah_qty = (float) $list_data->jumlah;
	$harga_satuan = (float) $list_data->harga_satuan;
	$nilai_kontrak = $jumlah_qty * $harga_satuan;
	$nilai_bpp_os = 10500;
	$utang_pph23 = ($nilai_kontrak * 2) / 100;

	return array(
		'jumlah_qty' => $jumlah_qty,
		'harga_satuan' => $harga_satuan,
		'nilai_kontrak' => $nilai_kontrak,
		'piutang_11301' => $nilai_kontrak - $nilai_bpp_os,
		'bpp_os_51132' => $nilai_bpp_os,
		'penjualan_jasa_41131' => $nilai_kontrak - $utang_pph23,
		'utang_pph23_21204' => $utang_pph23,
	);
}

function tbl_penjualan_jasa_build_subtotal_row($no, $nmrkirim, $total_kontrak, $total_11301, $total_51132, $total_41131, $total_21204)
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
		'nilai_kontrak' => $total_kontrak,
		'piutang_11301' => $total_11301,
		'bpp_os_51132' => $total_51132,
		'penjualan_jasa_41131' => $total_41131,
		'utang_pph23_21204' => $total_21204,
	);
}

/**
 * Bangun baris export persis struktur DataTable (data + subtotal per nmrkirim + grand total).
 */
function tbl_penjualan_jasa_build_export_rows($Tbl_penjualan_data)
{
	$export_rows = array();
	$compare_tgl_jual = 0;
	$compare_nmr_kirim = 0;
	$Total_kontrak = 0;
	$Total_11301 = 0;
	$Total_51132 = 0;
	$Total_41131 = 0;
	$Total_21204 = 0;
	$ALL_kontrak = 0;
	$ALL_11301 = 0;
	$ALL_51132 = 0;
	$ALL_41131 = 0;
	$ALL_21204 = 0;
	$start = 0;

	foreach ($Tbl_penjualan_data as $list_data) {
		if (($start >= 1) && (((string) $compare_nmr_kirim !== (string) $list_data->nmrkirim) || ((string) $compare_tgl_jual !== (string) $list_data->tgl_jual))) {
			$export_rows[] = tbl_penjualan_jasa_build_subtotal_row(
				++$start,
				$compare_nmr_kirim,
				$Total_kontrak,
				$Total_11301,
				$Total_51132,
				$Total_41131,
				$Total_21204
			);

			$Total_kontrak = 0;
			$Total_11301 = 0;
			$Total_51132 = 0;
			$Total_41131 = 0;
			$Total_21204 = 0;
		}

		$calc = tbl_penjualan_jasa_hitung_kolom($list_data);
		$Total_kontrak += $calc['nilai_kontrak'];
		$Total_11301 += $calc['piutang_11301'];
		$Total_51132 += $calc['bpp_os_51132'];
		$Total_41131 += $calc['penjualan_jasa_41131'];
		$Total_21204 += $calc['utang_pph23_21204'];
		$ALL_kontrak += $calc['nilai_kontrak'];
		$ALL_11301 += $calc['piutang_11301'];
		$ALL_51132 += $calc['bpp_os_51132'];
		$ALL_41131 += $calc['penjualan_jasa_41131'];
		$ALL_21204 += $calc['utang_pph23_21204'];

		$export_rows[] = array(
			'type' => 'data',
			'no' => ++$start,
			'tgl_jual' => tbl_penjualan_jasa_format_tanggal($list_data->tgl_jual),
			'nmrkirim' => (string) $list_data->nmrkirim,
			'nmrpesan' => (string) $list_data->nmrpesan,
			'konsumen_nama' => (string) $list_data->konsumen_nama,
			'kode_barang' => (string) $list_data->kode_barang,
			'nama_barang' => (string) $list_data->nama_barang,
			'unit' => (string) $list_data->unit,
			'satuan' => (string) $list_data->satuan,
			'harga_satuan' => $calc['harga_satuan'],
			'jumlah' => $calc['jumlah_qty'],
			'nilai_kontrak' => $calc['nilai_kontrak'],
			'piutang_11301' => $calc['piutang_11301'],
			'bpp_os_51132' => $calc['bpp_os_51132'],
			'penjualan_jasa_41131' => $calc['penjualan_jasa_41131'],
			'utang_pph23_21204' => $calc['utang_pph23_21204'],
		);

		$compare_nmr_kirim = $list_data->nmrkirim;
		$compare_tgl_jual = $list_data->tgl_jual;
	}

	if (!empty($export_rows)) {
		$export_rows[] = tbl_penjualan_jasa_build_subtotal_row(
			++$start,
			$compare_nmr_kirim,
			$Total_kontrak,
			$Total_11301,
			$Total_51132,
			$Total_41131,
			$Total_21204
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
			'nilai_kontrak' => $ALL_kontrak,
			'piutang_11301' => $ALL_11301,
			'bpp_os_51132' => $ALL_51132,
			'penjualan_jasa_41131' => $ALL_41131,
			'utang_pph23_21204' => $ALL_21204,
		);
	}

	return $export_rows;
}

function tbl_penjualan_jasa_excel_write_merged_row($row, $text, $styleIndex, $colEnd = 15)
{
	xlsAddMerge($row, 0, $row, (int) $colEnd);
	xlsWriteCellStyle($row, 0, $text, $styleIndex);
	for ($c = 1; $c <= (int) $colEnd; $c++) {
		xlsEnsureCellStyle($row, $c, $styleIndex, '');
	}
}

function tbl_penjualan_jasa_excel_write_title_block($periode_awal = '', $periode_akhir = '')
{
	$styleTitleBoldLeft = 11;
	$styleTitleItalicLeft = 12;
	$styleTitleBoldCenter = 13;
	$styleMetaLeft = 16;

	tbl_penjualan_jasa_excel_write_merged_row(0, 'PERUMDA ANEKA DHARMA', $styleTitleBoldLeft);
	tbl_penjualan_jasa_excel_write_merged_row(1, 'KABUPATEN BANTUL', $styleTitleBoldLeft);
	tbl_penjualan_jasa_excel_write_merged_row(2, 'Jl. Jend. Sudirman 36 Bantul. Telp / Fax : 0274 367123', $styleTitleItalicLeft);
	tbl_penjualan_jasa_excel_write_merged_row(4, 'DATA PENJUALAN JASA', $styleTitleBoldCenter);

	$periode = '';
	if ($periode_awal !== '' && $periode_akhir !== '') {
		$periode = 'Periode: ' . $periode_awal . ' s/d ' . $periode_akhir . '  |  ';
	}
	tbl_penjualan_jasa_excel_write_merged_row(5, $periode . 'Dicetak: ' . date('d/m/Y H:i:s'), $styleMetaLeft);
}

function tbl_penjualan_jasa_excel_write_headers()
{
	$styleHeader = 14;
	$styleHeaderDebit = 17;
	$styleHeaderKredit = 6;
	$rowTop = 7;
	$rowSub = 8;

	$topHeaders = array(
		array('label' => 'No', 'col' => 0, 'rowspan' => 2),
		array('label' => 'Tgl Jual', 'col' => 1, 'rowspan' => 2),
		array('label' => 'nmrkirim', 'col' => 2, 'rowspan' => 2),
		array('label' => 'nmrpesan', 'col' => 3, 'rowspan' => 2),
		array('label' => 'Konsumen', 'col' => 4, 'rowspan' => 2),
		array('label' => 'Kode', 'col' => 5, 'rowspan' => 2),
		array('label' => 'NAMA JASA', 'col' => 6, 'rowspan' => 2),
		array('label' => 'Unit', 'col' => 7, 'rowspan' => 2),
		array('label' => 'Satuan', 'col' => 8, 'rowspan' => 2),
		array('label' => 'Harga Satuan', 'col' => 9, 'rowspan' => 2),
		array('label' => 'Jumlah', 'col' => 10, 'rowspan' => 2),
		array('label' => 'Nilai Kontrak', 'col' => 11, 'rowspan' => 2),
		array('label' => 'Debit', 'col' => 12, 'colspan' => 2, 'style' => $styleHeaderDebit),
		array('label' => 'Kredit', 'col' => 14, 'colspan' => 2, 'style' => $styleHeaderKredit),
	);

	foreach ($topHeaders as $header) {
		$col = (int) $header['col'];
		$style = isset($header['style']) ? (int) $header['style'] : $styleHeader;
		xlsWriteCellStyle($rowTop, $col, $header['label'], $style);
		if (!empty($header['rowspan']) && (int) $header['rowspan'] > 1) {
			xlsAddMerge($rowTop, $col, $rowSub, $col);
			xlsEnsureCellStyle($rowSub, $col, $style, '');
		}
		if (!empty($header['colspan']) && (int) $header['colspan'] > 1) {
			$colEnd = $col + (int) $header['colspan'] - 1;
			xlsAddMerge($rowTop, $col, $rowTop, $colEnd);
			for ($c = $col + 1; $c <= $colEnd; $c++) {
				xlsEnsureCellStyle($rowTop, $c, $style, '');
			}
		}
	}

	$subHeaders = array(
		12 => array('label' => "11301\nPiutang", 'style' => $styleHeaderDebit),
		13 => array('label' => "51132\nBPP OS", 'style' => $styleHeaderDebit),
		14 => array('label' => "41131\nPenjualan Jasa Outsourcing", 'style' => $styleHeaderKredit),
		15 => array('label' => "21204\nUtang PPH 23 (2%)", 'style' => $styleHeaderKredit),
	);
	foreach ($subHeaders as $col => $header) {
		xlsWriteCellStyle($rowSub, (int) $col, $header['label'], $header['style']);
	}

	return $rowSub + 1;
}

function tbl_penjualan_jasa_excel_write_text_cell($rowNum, $col, $value, $styleIndex)
{
	xlsWriteCellStyle($rowNum, (int) $col, (string) $value, (int) $styleIndex);
}

function tbl_penjualan_jasa_excel_write_amount_cell($rowNum, $col, $value, $styleIndex, $decimals = 2)
{
	if ($value === '' || $value === null) {
		tbl_penjualan_jasa_excel_write_text_cell($rowNum, $col, '', $styleIndex);
		return;
	}

	tbl_penjualan_jasa_excel_write_text_cell(
		$rowNum,
		$col,
		tbl_penjualan_jasa_format_nominal($value, $decimals),
		$styleIndex
	);
}

function tbl_penjualan_jasa_excel_write_row($rowNum, $item, $zebra = false)
{
	$styleLeft = $zebra ? 19 : 7;
	$styleRight = $zebra ? 21 : 8;
	$styleCenter = $zebra ? 23 : 22;
	$styleSubtotal = 24;
	$styleSubtotalRight = 25;
	$styleGrandTotal = 14;
	$styleGrandTotalRight = 10;

	$type = isset($item['type']) ? $item['type'] : 'data';

	if ($type === 'subtotal') {
		tbl_penjualan_jasa_excel_write_text_cell($rowNum, 0, $item['no'], $styleSubtotal);
		tbl_penjualan_jasa_excel_write_text_cell($rowNum, 1, $item['tgl_jual'], $styleSubtotal);
		tbl_penjualan_jasa_excel_write_text_cell($rowNum, 2, $item['nmrkirim'], $styleSubtotal);
		for ($col = 3; $col <= 10; $col++) {
			tbl_penjualan_jasa_excel_write_text_cell($rowNum, $col, '', $styleSubtotal);
		}
		tbl_penjualan_jasa_excel_write_amount_cell($rowNum, 11, $item['nilai_kontrak'], $styleSubtotalRight);
		tbl_penjualan_jasa_excel_write_amount_cell($rowNum, 12, $item['piutang_11301'], $styleSubtotalRight);
		tbl_penjualan_jasa_excel_write_amount_cell($rowNum, 13, $item['bpp_os_51132'], $styleSubtotalRight);
		tbl_penjualan_jasa_excel_write_amount_cell($rowNum, 14, $item['penjualan_jasa_41131'], $styleSubtotalRight);
		tbl_penjualan_jasa_excel_write_amount_cell($rowNum, 15, $item['utang_pph23_21204'], $styleSubtotalRight);
		return;
	}

	if ($type === 'grand_total') {
		for ($col = 0; $col <= 9; $col++) {
			tbl_penjualan_jasa_excel_write_text_cell($rowNum, $col, '', $styleGrandTotal);
		}
		tbl_penjualan_jasa_excel_write_text_cell($rowNum, 10, $item['jumlah'], $styleGrandTotal);
		tbl_penjualan_jasa_excel_write_amount_cell($rowNum, 11, $item['nilai_kontrak'], $styleGrandTotalRight);
		tbl_penjualan_jasa_excel_write_amount_cell($rowNum, 12, $item['piutang_11301'], $styleGrandTotalRight);
		tbl_penjualan_jasa_excel_write_amount_cell($rowNum, 13, $item['bpp_os_51132'], $styleGrandTotalRight);
		tbl_penjualan_jasa_excel_write_amount_cell($rowNum, 14, $item['penjualan_jasa_41131'], $styleGrandTotalRight);
		tbl_penjualan_jasa_excel_write_amount_cell($rowNum, 15, $item['utang_pph23_21204'], $styleGrandTotalRight);
		return;
	}

	tbl_penjualan_jasa_excel_write_text_cell($rowNum, 0, $item['no'], $styleCenter);
	tbl_penjualan_jasa_excel_write_text_cell($rowNum, 1, $item['tgl_jual'], $styleLeft);
	tbl_penjualan_jasa_excel_write_text_cell($rowNum, 2, $item['nmrkirim'], $styleLeft);
	tbl_penjualan_jasa_excel_write_text_cell($rowNum, 3, $item['nmrpesan'], $styleLeft);
	tbl_penjualan_jasa_excel_write_text_cell($rowNum, 4, $item['konsumen_nama'], $styleLeft);
	tbl_penjualan_jasa_excel_write_text_cell($rowNum, 5, $item['kode_barang'], $styleLeft);
	tbl_penjualan_jasa_excel_write_text_cell($rowNum, 6, $item['nama_barang'], $styleLeft);
	tbl_penjualan_jasa_excel_write_text_cell($rowNum, 7, $item['unit'], $styleLeft);
	tbl_penjualan_jasa_excel_write_text_cell($rowNum, 8, $item['satuan'], $styleLeft);
	tbl_penjualan_jasa_excel_write_amount_cell($rowNum, 9, $item['harga_satuan'], $styleRight, 2);
	tbl_penjualan_jasa_excel_write_amount_cell($rowNum, 10, $item['jumlah'], $styleRight, 0);
	tbl_penjualan_jasa_excel_write_amount_cell($rowNum, 11, $item['nilai_kontrak'], $styleRight, 2);
	tbl_penjualan_jasa_excel_write_amount_cell($rowNum, 12, $item['piutang_11301'], $styleRight, 2);
	tbl_penjualan_jasa_excel_write_amount_cell($rowNum, 13, $item['bpp_os_51132'], $styleRight, 2);
	tbl_penjualan_jasa_excel_write_amount_cell($rowNum, 14, $item['penjualan_jasa_41131'], $styleRight, 2);
	tbl_penjualan_jasa_excel_write_amount_cell($rowNum, 15, $item['utang_pph23_21204'], $styleRight, 2);
}

/**
 * Generate file Excel penjualan jasa (tampilan mirip DataTable).
 */
function tbl_penjualan_jasa_export_excel_output($rows, $periode_awal = '', $periode_akhir = '')
{
	@set_time_limit(600);

	$data = tbl_penjualan_jasa_build_export_rows($rows);

	$namaFile = 'DATA_PENJUALAN_JASA_' . date('Y-m-d_H-i-s') . '.xlsx';
	excel_prepare_download($namaFile);

	xlsBOF();
	xlsSetColumnWidths(array(6, 14, 12, 12, 24, 10, 28, 10, 10, 14, 10, 16, 16, 14, 28, 18));

	tbl_penjualan_jasa_excel_write_title_block($periode_awal, $periode_akhir);
	$rowNum = tbl_penjualan_jasa_excel_write_headers();

	$dataIndex = 0;
	foreach ($data as $item) {
		$zebra = false;
		if (isset($item['type']) && $item['type'] === 'data') {
			$zebra = ($dataIndex % 2 === 1);
			$dataIndex++;
		}
		tbl_penjualan_jasa_excel_write_row($rowNum, $item, $zebra);
		$rowNum++;
	}

	xlsEOF();
}
