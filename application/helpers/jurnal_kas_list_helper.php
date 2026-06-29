<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function jurnal_kas_bulan_teks($angka_bulan)
{
	$angka_bulan = (int) $angka_bulan;
	$nama = array(
		1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
		5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
		9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
	);
	return isset($nama[$angka_bulan]) ? $nama[$angka_bulan] : '';
}

function jurnal_kas_format_rupiah($value, $always_show = false)
{
	if ($value === null || $value === '') {
		return '';
	}
	if (!$always_show && (float) $value == 0) {
		return '';
	}
	return number_format((float) $value, 2, ',', '.');
}

function jurnal_kas_get_saldo_bulan_lalu($CI, $month, $year)
{
	$month = (int) $month;
	$year = (int) $year;

	if ($month > 1) {
		$bulan_lalu = $month - 1;
		$tahun_saldo = $year;
		$label = 'Saldo akhir bulan: ' . jurnal_kas_bulan_teks($bulan_lalu) . ' ' . $year;
		$tanggal_saldo = sprintf('%04d-%02d-01', $year, $bulan_lalu);
	} else {
		$bulan_lalu = 12;
		$tahun_saldo = $year - 1;
		$label = 'Saldo akhir bulan: Desember ' . $tahun_saldo;
		$tanggal_saldo = sprintf('%04d-12-01', $tahun_saldo);
	}

	$CI->db->where('tanggal', $tanggal_saldo);
	$query = $CI->db->get('jurnal_kas_saldo_akhir_bulan');
	if ($query->num_rows() > 0) {
		$saldo = (float) $query->row()->saldo;
	} else {
		// Fallback key lama: saldo bulan lalu pernah disimpan di tanggal-01 bulan berjalan.
		$legacy_tanggal = sprintf('%04d-%02d-01', $year, $month);
		if ($legacy_tanggal !== $tanggal_saldo) {
			$CI->db->where('tanggal', $legacy_tanggal);
			$legacy_query = $CI->db->get('jurnal_kas_saldo_akhir_bulan');
			$saldo = ($legacy_query->num_rows() > 0) ? (float) $legacy_query->row()->saldo : 0.0;
		} else {
			$saldo = 0.0;
		}
	}

	return array(
		'label' => $label,
		'saldo' => $saldo,
		'tanggal_saldo' => $tanggal_saldo,
	);
}

function jurnal_kas_compute_list_data($CI, $month, $year)
{
	$month = (int) $month;
	$year = (int) $year;

	$saldo_info = jurnal_kas_get_saldo_bulan_lalu($CI, $month, $year);
	$saldo_bulan_lalu = (float) $saldo_info['saldo'];

	$TOTAL_debet = 0.0;
	$TOTAL_kredit = 0.0;
	if ($saldo_bulan_lalu > 0) {
		$TOTAL_debet += $saldo_bulan_lalu;
	} else {
		$TOTAL_kredit += $saldo_bulan_lalu;
	}

	$rows = array();
	$no = 1;

	$rows[] = array(
		'type' => 'saldo',
		'no' => $no++,
		'sumber' => '',
		'unit' => '',
		'tanggal' => '',
		'bukti' => '',
		'keterangan' => $saldo_info['label'],
		'debet' => ($saldo_bulan_lalu > 0) ? $saldo_bulan_lalu : null,
		'kredit' => ($saldo_bulan_lalu < 1) ? $saldo_bulan_lalu : null,
	);

	$CI->load->helper('jurnal_kas_sources');
	$data_kas = jurnal_kas_fetch_merged_rows($CI, $month, $year);

	foreach ($data_kas as $row) {
		$debet = ((float) $row->debet > 0) ? (float) $row->debet : null;
		$kredit = ((float) $row->kredit > 0) ? (float) $row->kredit : null;
		if ($debet > 0) {
			$TOTAL_debet += $debet;
		}
		if ($kredit > 0) {
			$TOTAL_kredit += $kredit;
		}

		$rows[] = array(
			'type' => 'data',
			'no' => $no++,
			'sumber' => isset($row->source_label) ? $row->source_label : '',
			'unit' => isset($row->kode_unit) ? $row->kode_unit : '',
			'tanggal' => date('d-m-Y', strtotime($row->tanggal)),
			'bukti' => isset($row->bukti) ? $row->bukti : '',
			'keterangan' => isset($row->keterangan) ? $row->keterangan : '',
			'debet' => $debet,
			'kredit' => $kredit,
		);
	}

	$SALDO_AKHIR = $TOTAL_debet - $TOTAL_kredit;

	return array(
		'month' => $month,
		'year' => $year,
		'bulan_label' => jurnal_kas_bulan_teks($month) . ' ' . $year,
		'rows' => $rows,
		'TOTAL_debet' => $TOTAL_debet,
		'TOTAL_kredit' => $TOTAL_kredit,
		'SALDO_AKHIR' => $SALDO_AKHIR,
		'data_kas' => $data_kas,
	);
}

function jurnal_kas_excel_write_merged_row($rowNum, $text, $styleIndex, $colEnd = 7)
{
	xlsAddMerge($rowNum, 0, $rowNum, $colEnd);
	xlsWriteCellStyle($rowNum, 0, $text, $styleIndex);
	for ($c = 1; $c <= $colEnd; $c++) {
		xlsEnsureCellStyle($rowNum, $c, $styleIndex, '');
	}
}

function jurnal_kas_excel_write_merged_cols($rowNum, $text, $colStart, $colEnd, $styleIndex)
{
	xlsAddMerge($rowNum, $colStart, $rowNum, $colEnd);
	xlsWriteCellStyle($rowNum, $colStart, $text, $styleIndex);
	for ($c = $colStart + 1; $c <= $colEnd; $c++) {
		xlsEnsureCellStyle($rowNum, $c, $styleIndex, '');
	}
}

function jurnal_kas_excel_tanggal_akhir_bulan_label($month, $year)
{
	$month = (int) $month;
	$year = (int) $year;
	$lastDay = (int) date('t', mktime(0, 0, 0, $month, 1, $year));

	return 'Bantul, ' . $lastDay . ' ' . jurnal_kas_bulan_teks($month) . ' ' . $year;
}

function jurnal_kas_excel_write_signature_names_row($rowNum, $leftText, $middleText, $rightText, $styleCenter, $styleLeft)
{
	jurnal_kas_excel_write_merged_cols($rowNum, $leftText, 0, 2, $styleCenter);
	xlsWriteCellStyle($rowNum, 3, $middleText, $styleLeft);
	jurnal_kas_excel_write_merged_cols($rowNum, $rightText, 5, 7, $styleCenter);
}

function jurnal_kas_excel_write_data_row($rowNum, $item, $styleBorder, $styleLeft, $styleRight)
{
	xlsWriteCellStyle($rowNum, 0, $item['no'], $styleBorder);
	xlsWriteCellStyle($rowNum, 1, isset($item['sumber']) ? $item['sumber'] : '', $styleLeft);
	xlsWriteCellStyle($rowNum, 2, isset($item['unit']) ? $item['unit'] : '', $styleLeft);
	xlsWriteCellStyle($rowNum, 3, $item['tanggal'], $styleLeft);
	xlsWriteCellStyle($rowNum, 4, $item['bukti'], $styleLeft);
	xlsWriteCellStyle($rowNum, 5, $item['keterangan'], $styleLeft);

	if ($item['debet'] !== null && $item['debet'] !== '') {
		$showZero = ($item['type'] === 'saldo');
		xlsWriteCellStyle($rowNum, 6, jurnal_kas_format_rupiah($item['debet'], $showZero), $styleRight);
	} else {
		xlsWriteCellStyle($rowNum, 6, '', $styleBorder);
	}

	if ($item['kredit'] !== null && $item['kredit'] !== '') {
		$showZero = ($item['type'] === 'saldo');
		xlsWriteCellStyle($rowNum, 7, jurnal_kas_format_rupiah($item['kredit'], $showZero), $styleRight);
	} else {
		xlsWriteCellStyle($rowNum, 7, '', $styleBorder);
	}
}

function jurnal_kas_excel_write_footer_row($rowNum, $label, $debet, $kredit, $styleFooterLabel, $styleFooterAmount)
{
	xlsAddMerge($rowNum, 0, $rowNum, 5);
	xlsWriteCellStyle($rowNum, 0, $label, $styleFooterLabel);
	for ($c = 1; $c <= 5; $c++) {
		xlsEnsureCellStyle($rowNum, $c, $styleFooterLabel, '');
	}

	if ($debet !== null && $debet !== '') {
		xlsWriteCellStyle($rowNum, 6, jurnal_kas_format_rupiah($debet, true), $styleFooterAmount);
	} else {
		xlsWriteCellStyle($rowNum, 6, '', $styleFooterAmount);
	}

	if ($kredit !== null && $kredit !== '') {
		xlsWriteCellStyle($rowNum, 7, jurnal_kas_format_rupiah($kredit, true), $styleFooterAmount);
	} else {
		xlsWriteCellStyle($rowNum, 7, '', $styleFooterAmount);
	}
}

function jurnal_kas_export_excel_list_output($CI, $month, $year)
{
	@set_time_limit(600);
	$CI->load->helper('exportexcel');

	$data = jurnal_kas_compute_list_data($CI, $month, $year);
	$month = (int) $data['month'];
	$year = (int) $data['year'];
	$bulan_nama = jurnal_kas_bulan_teks($month);

	$namaFile = 'Jurnal_Kas_' . str_pad((string) $month, 2, '0', STR_PAD_LEFT) . '_' . $year . '_' . date('Y-m-d_H-i-s') . '.xlsx';
	excel_prepare_download($namaFile);

	$styleBorder = 3;
	$styleRight = 8;
	$styleFooterLabel = 9;
	$styleFooterAmount = 10;
	$styleLeft = 7;
	$styleTitleBoldLeft = 11;
	$styleTitleItalicLeft = 12;
	$styleTitleBoldCenter = 13;
	$styleTableHeaderGreen = 14;
	$styleSignatureCenter = 15;
	$styleSignatureLeft = 16;

	xlsBOF();
	xlsSetColumnWidths(array(5, 24, 8, 14, 6, 42, 14, 14));

	// Baris 1-4: kop surat
	jurnal_kas_excel_write_merged_row(0, 'PERUMDA ANEKA DHARMA', $styleTitleBoldLeft);
	jurnal_kas_excel_write_merged_row(1, 'KABUPATEN BANTUL', $styleTitleBoldLeft);
	jurnal_kas_excel_write_merged_row(2, 'Jl. Jend. Sudirman 36 Bantul. Telp / Fax : 0274 367123', $styleTitleItalicLeft);
	jurnal_kas_excel_write_merged_row(
		3,
		'JURNAL KAS BULAN ' . strtoupper($bulan_nama) . ' TAHUN ' . $year,
		$styleTitleBoldCenter
	);

	// Baris 5: spacer kosong (indeks baris 4)

	$headerRow = 5;
	$headers = array('NO', 'SUMBER', 'UNIT', 'TANGGAL', 'BUKTI', 'KETERANGAN', 'DEBET', 'KREDIT');
	foreach ($headers as $col => $label) {
		xlsWriteCellStyle($headerRow, $col, $label, $styleTableHeaderGreen);
	}

	$rowNum = 6;
	foreach ($data['rows'] as $item) {
		jurnal_kas_excel_write_data_row($rowNum, $item, $styleBorder, $styleLeft, $styleRight);
		$rowNum++;
	}

	$TOTAL_debet = $data['TOTAL_debet'];
	$TOTAL_kredit = $data['TOTAL_kredit'];
	$SALDO_AKHIR = $data['SALDO_AKHIR'];

	jurnal_kas_excel_write_footer_row($rowNum++, 'JUMLAH DEBET / KREDIT', $TOTAL_debet, $TOTAL_kredit, $styleFooterLabel, $styleFooterAmount);
	jurnal_kas_excel_write_footer_row(
		$rowNum++,
		'Saldo akhir Kas Bulan ' . $bulan_nama,
		null,
		$SALDO_AKHIR,
		$styleFooterLabel,
		$styleFooterAmount
	);
	jurnal_kas_excel_write_footer_row(
		$rowNum++,
		'JUMLAH SEIMBANG',
		($SALDO_AKHIR >= 0) ? $TOTAL_debet : $SALDO_AKHIR,
		($SALDO_AKHIR >= 0) ? $TOTAL_debet : $SALDO_AKHIR,
		$styleFooterLabel,
		$styleFooterAmount
	);

	// 2 baris kosong setelah jumlah seimbang
	$rowNum += 2;

	// Tanda tangan — merge kolom 6, 7, 8 (indeks 5-7)
	jurnal_kas_excel_write_merged_cols($rowNum++, jurnal_kas_excel_tanggal_akhir_bulan_label($month, $year), 5, 7, $styleSignatureCenter);
	jurnal_kas_excel_write_merged_cols($rowNum++, 'Perusahaan Daerah Aneka Dharma', 5, 7, $styleSignatureCenter);
	jurnal_kas_excel_write_merged_cols($rowNum++, 'Kabupaten Bantul', 5, 7, $styleSignatureCenter);

	// 4 baris kosong setelah Kabupaten Bantul
	$rowNum += 4;

	// Baris nama & jabatan penandatangan
	jurnal_kas_excel_write_signature_names_row(
		$rowNum++,
		'Dwi Indah P',
		'Sulistyowati',
		'Yuli Budi sasangka, ST',
		$styleSignatureCenter,
		$styleSignatureLeft
	);
	jurnal_kas_excel_write_signature_names_row(
		$rowNum++,
		'Staf Akuntansi',
		'Ka. Bag. Keuangan',
		'Direktur',
		$styleSignatureCenter,
		$styleSignatureLeft
	);

	xlsEOF();
}

/**
 * Hitung baris data Jurnal Kas selaras halaman Jurnal_kas/cari_between_date:
 * Tab 1 (merged online) + data tersimpan/import di tabel jurnal_kas.
 */
function dashboard_jurnal_kas_count_page_data_rows($CI, $month, $year)
{
	$CI->load->helper('jurnal_kas_sources');

	$month = (int) $month;
	$year = (int) $year;

	$merged_rows = jurnal_kas_fetch_merged_rows($CI, $month, $year);
	$merged_count = count($merged_rows);
	if ($merged_count >= 1) {
		return $merged_count;
	}

	if ($CI->db->table_exists('jurnal_kas')) {
		$CI->db->where('MONTH(`tanggal`)', $month);
		$CI->db->where('YEAR(`tanggal`)', $year);
		$jk_count = (int) $CI->db->count_all_results('jurnal_kas');
		if ($jk_count >= 1) {
			return $jk_count;
		}
	}

	$CI->load->helper('jurnal_kas_lap');
	$report = jurnal_kas_lap_compute_report_data($CI, $month, $year, null, null, true);
	if (!empty($report['ok']) && !empty($report['rows'])) {
		$data_rows = 0;
		foreach ((array) $report['rows'] as $row) {
			if (isset($row['type']) && $row['type'] === 'data') {
				$data_rows++;
			}
		}
		if ($data_rows >= 1) {
			return $data_rows;
		}
	}

	return 0;
}

/**
 * Daftar bulan Jurnal Kas untuk Dashboard (Jan 2026 s/d bulan aktif).
 * Semua bulan dalam rentang selalu ditampilkan; tombol aksi jika ada data.
 */
function dashboard_jurnal_kas_bulan_list($CI, $min_year = 2026, $min_month = 1)
{
	$CI->load->helper(array('jurnal_kas_sources', 'dashboard'));

	$list = array();
	foreach (dashboard_bulan_list_range($min_year, $min_month) as $base) {
		$year = (int) $base->year_process;
		$month = (int) $base->month_process;
		$data_row_count = dashboard_jurnal_kas_count_page_data_rows($CI, $month, $year);
		$has_data = ($data_row_count >= 1);

		$list[] = (object) array(
			'year_process' => $year,
			'month_process' => $month,
			'data_row_count' => $data_row_count,
			'has_data' => $has_data,
			'show_update' => $has_data,
			'show_cetak' => $has_data,
			'is_current_month' => !empty($base->is_current_month),
			'update_url' => site_url('jurnal_kas/cari_between_date/' . $year . '/' . $month),
			'cetak_url' => site_url('Jurnal_kas/excel/' . $year . '/' . $month),
		);
	}

	return $list;
}
