<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function tbl_neraca_cetak_excel_col_end()
{
	return 5;
}

function tbl_neraca_cetak_bulan_teks($angka_bulan)
{
	$angka_bulan = (int) $angka_bulan;
	$nama = array(
		1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
		5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
		9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
	);

	return isset($nama[$angka_bulan]) ? $nama[$angka_bulan] : '';
}

function tbl_neraca_cetak_tanggal_akhir_bulan_label($month, $year)
{
	$month = (int) $month;
	$year = (int) $year;
	$lastDay = (int) date('t', mktime(0, 0, 0, $month, 1, $year));

	return 'Bantul, ' . $lastDay . ' ' . tbl_neraca_cetak_bulan_teks($month) . ' ' . $year;
}

function tbl_neraca_cetak_format_amount($value)
{
	if ($value === null || $value === '') {
		return '';
	}

	return number_format((float) $value, 2, ',', '.');
}

function tbl_neraca_cetak_excel_write_merged_row($rowNum, $text, $styleIndex, $colEnd = null)
{
	if ($colEnd === null) {
		$colEnd = tbl_neraca_cetak_excel_col_end();
	}

	xlsAddMerge($rowNum, 0, $rowNum, $colEnd);
	xlsWriteCellStyle($rowNum, 0, $text, $styleIndex);
	for ($c = 1; $c <= $colEnd; $c++) {
		xlsEnsureCellStyle($rowNum, $c, $styleIndex, '');
	}
}

function tbl_neraca_cetak_excel_write_merged_cols($rowNum, $text, $colStart, $colEnd, $styleIndex)
{
	xlsAddMerge($rowNum, $colStart, $rowNum, $colEnd);
	xlsWriteCellStyle($rowNum, $colStart, $text, $styleIndex);
	for ($c = $colStart + 1; $c <= $colEnd; $c++) {
		xlsEnsureCellStyle($rowNum, $c, $styleIndex, '');
	}
}

function tbl_neraca_cetak_excel_write_section_pair($rowNum, $aktivaText, $pasivaText, $styleSection)
{
	tbl_neraca_cetak_excel_write_merged_cols($rowNum, $aktivaText, 0, 2, $styleSection);
	tbl_neraca_cetak_excel_write_merged_cols($rowNum, $pasivaText, 3, 5, $styleSection);
}

function tbl_neraca_cetak_excel_write_data_row($rowNum, $aktivaLabel, $aktivaAmount, $pasivaLabel, $pasivaAmount, $styleLeft, $styleRight, $styleBorder, $bold = false)
{
	$labelStyle = $bold ? 5 : $styleLeft;
	$amountStyle = $bold ? 9 : $styleRight;

	xlsWriteCellStyle($rowNum, 0, $aktivaLabel, $labelStyle);
	xlsWriteCellStyle($rowNum, 1, ($aktivaAmount !== null && $aktivaAmount !== '') ? 'Rp.' : '', $styleBorder);
	xlsWriteCellStyle($rowNum, 2, tbl_neraca_cetak_format_amount($aktivaAmount), $amountStyle);
	xlsWriteCellStyle($rowNum, 3, $pasivaLabel, $labelStyle);
	xlsWriteCellStyle($rowNum, 4, ($pasivaAmount !== null && $pasivaAmount !== '') ? 'Rp.' : '', $styleBorder);
	xlsWriteCellStyle($rowNum, 5, tbl_neraca_cetak_format_amount($pasivaAmount), $amountStyle);
}

function tbl_neraca_cetak_field_value($data, $field)
{
	if (!$data || !isset($data->$field)) {
		return null;
	}

	return (float) $data->$field;
}

function tbl_neraca_data_compute_cetak_excel_rows($data)
{
	$rows = array();
	if (!$data) {
		return $rows;
	}

	$TOTAL_AKIVA_LANCAR = 0.0;
	$Total_Aktiva_Tetap_Bersih = 0.0;
	$Aktiva_Lain_Lain = 0.0;
	$TOTAL_Utang_Lancar = 0.0;
	$TOTAL_Utang_Jangka_Panjang = 0.0;
	$TOTAL_Modal_dan_Laba_ditahan = 0.0;

	$val = function ($field) use ($data) {
		return tbl_neraca_cetak_field_value($data, $field);
	};
	$val_amount = function ($field) use ($val) {
		$amount = $val($field);
		return $amount !== null ? $amount : 0.0;
	};
	$add_aktiva_lancar = function ($field) use ($val_amount, &$TOTAL_AKIVA_LANCAR) {
		$amount = $val_amount($field);
		$TOTAL_AKIVA_LANCAR += $amount;
		return $amount;
	};
	$add_utang_lancar = function ($field) use ($val_amount, &$TOTAL_Utang_Lancar) {
		$amount = $val_amount($field);
		$TOTAL_Utang_Lancar += $amount;
		return $amount;
	};
	$add_utang_jp = function ($field) use ($val_amount, &$TOTAL_Utang_Jangka_Panjang) {
		$amount = $val_amount($field);
		$TOTAL_Utang_Jangka_Panjang += $amount;
		return $amount;
	};
	$add_aktiva_lain = function ($field) use ($val_amount, &$Aktiva_Lain_Lain) {
		$amount = $val_amount($field);
		$Aktiva_Lain_Lain += $amount;
		return $amount;
	};
	$add_modal = function ($field) use ($val_amount, &$TOTAL_Modal_dan_Laba_ditahan) {
		$amount = $val_amount($field);
		$TOTAL_Modal_dan_Laba_ditahan += $amount;
		return $amount;
	};
	$push = function ($type, $aktivaLabel = '', $aktivaAmount = null, $pasivaLabel = '', $pasivaAmount = null, $bold = false, $extra = array()) use (&$rows) {
		$row = array(
			'type' => $type,
			'aktiva_label' => $aktivaLabel,
			'aktiva_amount' => $aktivaAmount,
			'pasiva_label' => $pasivaLabel,
			'pasiva_amount' => $pasivaAmount,
			'bold' => $bold,
		);
		if (!empty($extra) && is_array($extra)) {
			$row = array_merge($row, $extra);
		}
		$rows[] = $row;
	};

	$TOTAL_Aktiva_Tetap_Berwujud_Bersih = 0.0;
	$add_berwujud = function ($field) use ($val_amount, &$TOTAL_Aktiva_Tetap_Berwujud_Bersih, &$Total_Aktiva_Tetap_Bersih) {
		$amount = $val_amount($field);
		$TOTAL_Aktiva_Tetap_Berwujud_Bersih += $amount;
		$Total_Aktiva_Tetap_Bersih += $amount;
		return $amount;
	};

	$TOTAL_Aktiva_Tetap_Tidak_Berwujud = 0.0;
	$add_tidak_berwujud = function ($field) use ($val_amount, &$TOTAL_Aktiva_Tetap_Tidak_Berwujud, &$Total_Aktiva_Tetap_Bersih) {
		$amount = $val_amount($field);
		$TOTAL_Aktiva_Tetap_Tidak_Berwujud += $amount;
		$Total_Aktiva_Tetap_Bersih += $amount;
		return $amount;
	};

	$push('section', 'AKTIVA', '', 'PASIVA', '');
	$push('section', 'Aktiva Lancar', '', 'Utang Lancar', '');
	$push('data', 'Kas', $add_aktiva_lancar('kas'), 'Utang Usaha', $add_utang_lancar('utang_usaha'));
	$push('data', 'Bank', $add_aktiva_lancar('bank'), 'Utang Pajak', $add_utang_lancar('utang_pajak'));
	$push('data', 'Piutang Usaha', $add_aktiva_lancar('piutang_usaha'), 'Utang Lain-lain', $add_utang_lancar('utang_lain_lain'));
	$push('data', 'Piutang Non Usaha', $add_aktiva_lancar('piutang_non_usaha'), '', $TOTAL_Utang_Lancar);
	$push('data', 'Persediaan', $add_aktiva_lancar('persediaan'), '', null);
	$push('data', 'Uang Muka Pajak', $add_aktiva_lancar('uang_muka_pajak'), '', null);
	$push('data', 'Total Aktiva Lancar', $TOTAL_AKIVA_LANCAR, 'Utang Jangka Panjang', '', true);
	$push('blank');
	$push('subsection', 'Aktiva Tetap', '', '', '');
	$push('data', 'Aktiva Tetap Berwujud', $add_berwujud('aktiva_tetap_berwujud'), 'Utang Afiliasi', $add_utang_jp('utang_afiliasi'));
	$push('data', 'Akumulasi Depresiasi ATB', $add_berwujud('akumulasi_depresiasi_atb'), '', null);
	$push('data', 'Total Aktiva Tetap Bersih', $TOTAL_Aktiva_Tetap_Berwujud_Bersih, '', null, true);
	$push('subsection', 'Aktiva tetap tidak berwujud', '', '', '');
	$push('data', 'Aktiva tetap tidak berwujud', $add_tidak_berwujud('aktiva_tetap'), '', null);
	$push('data', 'Akumulasi Depresiasi ATTB', $add_tidak_berwujud('akumulasi_depresiasi_attb'), '', null);
	$push('data', 'Total aktiva tetap bersih', $TOTAL_Aktiva_Tetap_Tidak_Berwujud, 'Total Utang', $TOTAL_Utang_Lancar + $TOTAL_Utang_Jangka_Panjang, true, array('indent_mid' => true));
	$push('blank');
	$push('section', 'Aktiva Lain-Lain', '', 'Modal dan Laba ditahan', '');
	$push('data', 'Piutang Non Usaha Pihak Ketiga', $add_aktiva_lain('piutang_non_usaha_pihak_ketiga'), 'Modal Dasar dan Penyertaan', $add_modal('modal_dasar_dan_penyertaan'));
	$push('data', 'Piutang Non Usaha Radio', $add_aktiva_lain('piutang_non_usaha_radio'), 'Cadangan Umum', $add_modal('cadangan_umum'));
	$push('data', 'ljpj-Taman Gedung Kesenian Gabusan', $add_aktiva_lain('ljpj_taman_gedung_kesenian_gabusan'), 'Laba BUMD (PAD)', $add_modal('laba_bumd_pad'));
	$push('data', 'ljpj-Kompleks Gedung Kesenian', $add_aktiva_lain('ljpj_kompleks_gedung_kesenian'), '', null);
	$push('data', 'ljpj-Radio', $add_aktiva_lain('ljpj_radio'), 'Laba (Rugi) Tahun Lalu', $add_modal('laba_rugi_tahun_lalu'));
	$push('data', 'ljpj-Kerjasama Operasi Apotek Dharma Usaha', $add_aktiva_lain('ljpj_kerjasama_operasi_apotek_dharma_usaha'), 'Laba (Rugi) Tahun Berjalan', $add_modal('laba_rugi_tahun_berjalan'));
	$push('data', 'ljpj-Peternakan', $add_aktiva_lain('ljpj_peternakan'), '', null);
	$push('data', 'ljpj-Kerjasama ADWM', $add_aktiva_lain('ljpj_kerjasama_adwm'), '', null);
	$push('data', 'ljpj-Kerjasama PDU Cabean Panggungharjo', $add_aktiva_lain('ljpj_kerjasama_pdu_cabean_panggungharjo'), '', null);
	$push('data', '', $Aktiva_Lain_Lain, '', $TOTAL_Modal_dan_Laba_ditahan, true);
	$push('blank');
	$push('data', 'TOTAL AKTIVA', $TOTAL_AKIVA_LANCAR + $Total_Aktiva_Tetap_Bersih + $Aktiva_Lain_Lain, 'TOTAL PASIVA', $TOTAL_Utang_Lancar + $TOTAL_Utang_Jangka_Panjang + $TOTAL_Modal_dan_Laba_ditahan, true);

	return $rows;
}

function tbl_neraca_cetak_excel_write_kop_surat($year, $month)
{
	$year = (int) $year;
	$month = (int) $month;
	$bulan_nama = tbl_neraca_cetak_bulan_teks($month);
	$colEnd = tbl_neraca_cetak_excel_col_end();

	$styleTitleBoldLeft = 11;
	$styleTitleItalicLeft = 12;
	$styleTitleBoldCenter = 13;

	tbl_neraca_cetak_excel_write_merged_row(0, 'PERUMDA ANEKA DHARMA', $styleTitleBoldLeft, $colEnd);
	tbl_neraca_cetak_excel_write_merged_row(1, 'KABUPATEN BANTUL', $styleTitleBoldLeft, $colEnd);
	tbl_neraca_cetak_excel_write_merged_row(2, 'Jl. Jend. Sudirman 36 Bantul. Telp / Fax : 0274 367123', $styleTitleItalicLeft, $colEnd);
	tbl_neraca_cetak_excel_write_merged_row(
		3,
		'JURNAL KAS BULAN ' . strtoupper($bulan_nama) . ' TAHUN ' . $year,
		$styleTitleBoldCenter,
		$colEnd
	);

	// Baris 5 (indeks 4) dibiarkan kosong.
}

function tbl_neraca_cetak_excel_write_footer($rowNum, $month, $year)
{
	$styleSignatureCenter = 15;

	$rowNum++;
	$rowNum++;
	tbl_neraca_cetak_excel_write_merged_cols(
		$rowNum,
		tbl_neraca_cetak_tanggal_akhir_bulan_label($month, $year),
		3,
		5,
		$styleSignatureCenter
	);
}

function tbl_neraca_data_export_excel_cetak_output($CI, $year, $month, $data_detail)
{
	@set_time_limit(600);
	$CI->load->helper('exportexcel');

	$year = (int) $year;
	$month = (int) $month;

	$namaFile = 'Cetak_Neraca_'
		. str_pad((string) $month, 2, '0', STR_PAD_LEFT) . '_'
		. $year . '_'
		. date('Y-m-d_H-i-s') . '.xlsx';
	excel_prepare_download($namaFile);

	$styleBorder = 3;
	$styleRight = 8;
	$styleLeft = 7;
	$styleSection = 5;

	xlsBOF();
	xlsSetColumnWidths(array(34, 6, 18, 34, 6, 18));

	tbl_neraca_cetak_excel_write_kop_surat($year, $month);

	$rowNum = 5;
	$tableRows = tbl_neraca_data_compute_cetak_excel_rows($data_detail);
	foreach ($tableRows as $item) {
		$type = isset($item['type']) ? $item['type'] : 'data';
		if ($type === 'section' || $type === 'subsection') {
			if ($type === 'subsection' && $item['aktiva_label'] !== '' && $item['pasiva_label'] === '') {
				tbl_neraca_cetak_excel_write_merged_cols(
					$rowNum++,
					$item['aktiva_label'],
					0,
					2,
					$styleSection
				);
			} else {
				tbl_neraca_cetak_excel_write_section_pair(
					$rowNum++,
					$item['aktiva_label'],
					$item['pasiva_label'],
					$styleSection
				);
			}
			continue;
		}
		if ($type === 'blank') {
			$rowNum++;
			continue;
		}

		tbl_neraca_cetak_excel_write_data_row(
			$rowNum++,
			$item['aktiva_label'],
			$item['aktiva_amount'],
			$item['pasiva_label'],
			$item['pasiva_amount'],
			$styleLeft,
			$styleRight,
			$styleBorder,
			!empty($item['bold'])
		);
	}

	tbl_neraca_cetak_excel_write_footer($rowNum, $month, $year);

	xlsEOF();
	exit();
}
