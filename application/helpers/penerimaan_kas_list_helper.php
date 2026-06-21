<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function penerimaan_kas_bulan_teks($angka_bulan)
{
	$angka_bulan = (int) $angka_bulan;
	$nama = array(
		1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
		5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
		9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
	);
	return isset($nama[$angka_bulan]) ? $nama[$angka_bulan] : '';
}

function penerimaan_kas_parse_amount($value)
{
	if ($value === null || $value === '') {
		return 0.0;
	}
	if (is_numeric($value)) {
		return (float) $value;
	}
	$s = trim((string) $value);
	$s = str_replace('.', '', $s);
	$s = str_replace(',', '.', $s);
	return is_numeric($s) ? (float) $s : 0.0;
}

function penerimaan_kas_format_rupiah($value, $always_show = false)
{
	if ($value === null || $value === '') {
		return '';
	}
	if (!$always_show && (float) $value == 0) {
		return '';
	}
	return number_format((float) $value, 2, ',', '.');
}

function penerimaan_kas_parse_tanggal_to_ts($tanggal_str)
{
	$tanggal_str = trim((string) $tanggal_str);
	if ($tanggal_str === '') {
		return false;
	}
	if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $tanggal_str, $m)) {
		return strtotime(sprintf('%04d-%02d-%02d', (int) $m[3], (int) $m[2], (int) $m[1]));
	}
	if (preg_match('/^(\d{1,2})-(\d{1,2})-(\d{4})$/', $tanggal_str, $m)) {
		return strtotime(sprintf('%04d-%02d-%02d', (int) $m[3], (int) $m[2], (int) $m[1]));
	}
	if (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})(?:\s|$)/', $tanggal_str, $m)) {
		return strtotime(sprintf('%04d-%02d-%02d', (int) $m[1], (int) $m[2], (int) $m[3]));
	}
	$ts = strtotime($tanggal_str);
	return $ts ? $ts : false;
}

function penerimaan_kas_parse_filter_input_to_ymd($date_str)
{
	$date_str = trim((string) $date_str);
	if ($date_str === '') {
		return null;
	}
	if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $date_str, $m)) {
		return sprintf('%04d-%02d-%02d', (int) $m[3], (int) $m[2], (int) $m[1]);
	}
	if (preg_match('/^(\d{1,2})-(\d{1,2})-(\d{4})$/', $date_str, $m)) {
		return sprintf('%04d-%02d-%02d', (int) $m[3], (int) $m[2], (int) $m[1]);
	}
	if (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})/', $date_str, $m)) {
		return sprintf('%04d-%02d-%02d', (int) $m[1], (int) $m[2], (int) $m[3]);
	}
	$ts = strtotime($date_str);
	if (!$ts || (int) date('Y', $ts) < 2020) {
		return null;
	}
	return date('Y-m-d', $ts);
}

function penerimaan_kas_parse_filter_date($date_str, $is_end = false)
{
	$ymd = penerimaan_kas_parse_filter_input_to_ymd($date_str);
	if ($ymd === null) {
		return null;
	}
	return $is_end ? ($ymd . ' 23:59:59') : ($ymd . ' 00:00:00');
}

function penerimaan_kas_parse_filter_month($bulan_ns)
{
	$bulan_ns = trim((string) $bulan_ns);
	if (!preg_match('/^(\d{4})-(\d{2})$/', $bulan_ns, $m)) {
		return null;
	}

	$year = (int) $m[1];
	$month = (int) $m[2];
	if ($year < 2020 || $month < 1 || $month > 12) {
		return null;
	}

	$date_awal = sprintf('%04d-%02d-01 00:00:00', $year, $month);
	$date_akhir = date('Y-m-t 23:59:59', strtotime($date_awal));

	return array(
		'date_awal' => $date_awal,
		'date_akhir' => $date_akhir,
		'month' => $month,
		'year' => $year,
		'bulan_ns' => sprintf('%04d-%02d', $year, $month),
	);
}

function penerimaan_kas_sql_tanggal_expr()
{
	return "COALESCE(
		STR_TO_DATE(NULLIF(TRIM(tanggal), ''), '%d/%m/%Y'),
		STR_TO_DATE(NULLIF(TRIM(tanggal), ''), '%e/%c/%Y'),
		STR_TO_DATE(NULLIF(TRIM(tanggal), ''), '%d-%m-%Y'),
		STR_TO_DATE(NULLIF(TRIM(tanggal), ''), '%Y-%m-%d')
	)";
}

function penerimaan_kas_normalize_filter_range($date_awal, $date_akhir)
{
	$start_ymd = penerimaan_kas_parse_filter_input_to_ymd($date_awal);
	$end_ymd = penerimaan_kas_parse_filter_input_to_ymd($date_akhir);

	if ($start_ymd === null || $end_ymd === null) {
		return array($date_awal, $date_akhir);
	}

	if ($start_ymd > $end_ymd) {
		return array(
			$end_ymd . ' 00:00:00',
			$start_ymd . ' 23:59:59',
		);
	}

	return array(
		$start_ymd . ' 00:00:00',
		$end_ymd . ' 23:59:59',
	);
}

function penerimaan_kas_fetch_records_in_range($CI, $date_awal, $date_akhir)
{
	list($date_awal, $date_akhir) = penerimaan_kas_normalize_filter_range($date_awal, $date_akhir);

	$start_ymd = penerimaan_kas_parse_filter_input_to_ymd($date_awal);
	$end_ymd = penerimaan_kas_parse_filter_input_to_ymd($date_akhir);

	if ($start_ymd === null) {
		$start_ymd = date('Y-m-d', strtotime($date_awal));
	}
	if ($end_ymd === null) {
		$end_ymd = date('Y-m-d', strtotime($date_akhir));
	}

	$tanggal_expr = penerimaan_kas_sql_tanggal_expr();
	$sql = "SELECT * FROM `jurnal_penerimaan_kas`
		WHERE tanggal IS NOT NULL
		AND TRIM(tanggal) <> ''
		AND {$tanggal_expr} IS NOT NULL
		AND DATE({$tanggal_expr}) >= ?
		AND DATE({$tanggal_expr}) <= ?
		ORDER BY `pl` ASC, `nomor` ASC";

	$rows = $CI->db->query($sql, array($start_ymd, $end_ymd))->result();
	if (!empty($rows)) {
		return $rows;
	}

	$all = $CI->db->order_by('pl', 'ASC')->order_by('nomor', 'ASC')->get('jurnal_penerimaan_kas')->result();
	$start_ts = strtotime($start_ymd . ' 00:00:00');
	$end_ts = strtotime($end_ymd . ' 23:59:59');
	$filtered = array();

	foreach ($all as $row) {
		$ts = penerimaan_kas_parse_tanggal_to_ts(isset($row->tanggal) ? $row->tanggal : '');
		if ($ts && $ts >= $start_ts && $ts <= $end_ts) {
			$filtered[] = $row;
		}
	}

	return $filtered;
}

function penerimaan_kas_format_tanggal_display($tanggal_str)
{
	$ts = penerimaan_kas_parse_tanggal_to_ts($tanggal_str);
	return $ts ? date('d-m-Y', $ts) : $tanggal_str;
}

function penerimaan_kas_format_tanggal_storage($tanggal_str)
{
	$ts = penerimaan_kas_parse_tanggal_to_ts($tanggal_str);
	return $ts ? date('d/m/Y', $ts) : trim((string) $tanggal_str);
}

function penerimaan_kas_build_row_from_item($list_data, $no)
{
	$debet = penerimaan_kas_parse_amount(isset($list_data->debet_11101_kas_besar) ? $list_data->debet_11101_kas_besar : 0);
	$kredit = penerimaan_kas_parse_amount(isset($list_data->kredit_11301_pu_non_angsuran) ? $list_data->kredit_11301_pu_non_angsuran : 0);
	$jumlah = penerimaan_kas_parse_amount(isset($list_data->serba_serbi_jumlah) ? $list_data->serba_serbi_jumlah : 0);

	return array(
		'type' => 'data',
		'no' => $no,
		'tanggal' => penerimaan_kas_format_tanggal_display(isset($list_data->tanggal) ? $list_data->tanggal : ''),
		'kode_akun' => '',
		'bukti' => isset($list_data->nomorbuktibkm) ? $list_data->nomorbuktibkm : '',
		'pl' => isset($list_data->pl) ? $list_data->pl : '',
		'keterangan' => isset($list_data->keterangan) ? $list_data->keterangan : '',
		'debet_11101' => $debet > 0 ? $debet : null,
		'kredit_11301' => $kredit > 0 ? $kredit : null,
		'kode_rekening' => isset($list_data->serba_serbi_rekening) ? $list_data->serba_serbi_rekening : '',
		'jumlah' => $jumlah > 0 ? $jumlah : null,
	);
}

function penerimaan_kas_build_subtotal_row($no, $total_debet, $total_11301, $total_jumlah)
{
	return array(
		'type' => 'subtotal',
		'no' => $no,
		'tanggal' => '',
		'kode_akun' => '',
		'bukti' => '',
		'pl' => '',
		'keterangan' => '',
		'debet_11101' => $total_debet,
		'kredit_11301' => $total_11301,
		'kode_rekening' => '',
		'jumlah' => $total_jumlah,
	);
}

function penerimaan_kas_compute_list_data($CI, $date_awal, $date_akhir)
{
	$data_kas = penerimaan_kas_fetch_records_in_range($CI, $date_awal, $date_akhir);

	$rows = array();
	$no = 0;
	$total_debet_pl = 0.0;
	$total_11301_pl = 0.0;
	$total_jumlah_pl = 0.0;
	$total_debet_semua = 0.0;
	$total_11301_semua = 0.0;
	$total_jumlah_semua = 0.0;
	$pl_data = null;
	$first = true;

	foreach ($data_kas as $list_data) {
		$debet_val = penerimaan_kas_parse_amount(isset($list_data->debet_11101_kas_besar) ? $list_data->debet_11101_kas_besar : 0);
		$kredit_val = penerimaan_kas_parse_amount(isset($list_data->kredit_11301_pu_non_angsuran) ? $list_data->kredit_11301_pu_non_angsuran : 0);
		$jumlah_val = penerimaan_kas_parse_amount(isset($list_data->serba_serbi_jumlah) ? $list_data->serba_serbi_jumlah : 0);

		if ($first) {
			$rows[] = penerimaan_kas_build_row_from_item($list_data, ++$no);
			$total_debet_pl += $debet_val;
			$total_11301_pl += $kredit_val;
			$total_jumlah_pl += $jumlah_val;
			$total_debet_semua += $debet_val;
			$total_11301_semua += $kredit_val;
			$total_jumlah_semua += $jumlah_val;
			$pl_data = isset($list_data->pl) ? $list_data->pl : null;
			$first = false;
			continue;
		}

		if ($pl_data == $list_data->pl) {
			$rows[] = penerimaan_kas_build_row_from_item($list_data, ++$no);
		} else {
			$rows[] = penerimaan_kas_build_subtotal_row(++$no, $total_debet_pl, $total_11301_pl, $total_jumlah_pl);
			$total_debet_pl = 0.0;
			$total_11301_pl = 0.0;
			$total_jumlah_pl = 0.0;
			$rows[] = penerimaan_kas_build_row_from_item($list_data, ++$no);
		}

		$total_debet_pl += $debet_val;
		$total_11301_pl += $kredit_val;
		$total_jumlah_pl += $jumlah_val;
		$total_debet_semua += $debet_val;
		$total_11301_semua += $kredit_val;
		$total_jumlah_semua += $jumlah_val;
		$pl_data = isset($list_data->pl) ? $list_data->pl : null;
	}

	if (!$first) {
		$rows[] = penerimaan_kas_build_subtotal_row(++$no, $total_debet_pl, $total_11301_pl, $total_jumlah_pl);
	}

	$label_awal = date('d-m-Y', strtotime($date_awal));
	$label_akhir = date('d-m-Y', strtotime($date_akhir));
	$month_akhir = (int) date('m', strtotime($date_akhir));
	$year_akhir = (int) date('Y', strtotime($date_akhir));

	return array(
		'date_awal' => $date_awal,
		'date_akhir' => $date_akhir,
		'periode_label' => $label_awal . ' s/d ' . $label_akhir,
		'bulan_label' => penerimaan_kas_bulan_teks($month_akhir) . ' ' . $year_akhir,
		'month_akhir' => $month_akhir,
		'year_akhir' => $year_akhir,
		'rows' => $rows,
		'TOTAL_debet_11101_SEMUA' => $total_debet_semua,
		'TOTAL_kredit_11301_SEMUA' => $total_11301_semua,
		'TOTAL_kredit_jumlah_SEMUA' => $total_jumlah_semua,
	);
}

function penerimaan_kas_save_from_post($CI)
{
	$tanggal = trim((string) $CI->input->post('tanggal', TRUE));
	$pl = trim((string) $CI->input->post('pl', TRUE));
	$keterangan = trim((string) $CI->input->post('keterangan', TRUE));

	if ($tanggal === '') {
		return array('ok' => false, 'message' => 'Tanggal wajib diisi.');
	}
	if ($pl === '') {
		return array('ok' => false, 'message' => 'PL wajib diisi.');
	}
	if ($keterangan === '') {
		return array('ok' => false, 'message' => 'Keterangan wajib diisi.');
	}

	$debet_raw = trim((string) $CI->input->post('debet_11101_kas_besar', TRUE));
	$kredit_raw = trim((string) $CI->input->post('kredit_11301_pu_non_angsuran', TRUE));
	$rek_raw = trim((string) $CI->input->post('serba_serbi_rekening', TRUE));
	$jumlah_raw = trim((string) $CI->input->post('serba_serbi_jumlah', TRUE));

	$debet_val = penerimaan_kas_parse_amount($debet_raw);
	$kredit_val = penerimaan_kas_parse_amount($kredit_raw);
	$jumlah_val = penerimaan_kas_parse_amount($jumlah_raw);

	$has_amount = ($debet_val > 0) || ($kredit_val > 0) || ($rek_raw !== '') || ($jumlah_val > 0);
	if (!$has_amount) {
		return array(
			'ok' => false,
			'message' => 'Minimal salah satu field Debet 11101, Kredit 11301, Serba-Serbi Rekening, atau Serba-Serbi Jumlah harus diisi.',
		);
	}

	if (!penerimaan_kas_parse_tanggal_to_ts($tanggal)) {
		return array('ok' => false, 'message' => 'Format tanggal tidak valid.');
	}

	$data = array(
		'tanggal' => penerimaan_kas_format_tanggal_storage($tanggal),
		'nomorbuktibkm' => trim((string) $CI->input->post('nomorbuktibkm', TRUE)),
		'pl' => $pl,
		'keterangan' => $keterangan,
		'debet_11101_kas_besar' => $debet_val > 0 ? penerimaan_kas_format_rupiah($debet_val, true) : '',
		'kredit_11301_pu_non_angsuran' => $kredit_val > 0 ? penerimaan_kas_format_rupiah($kredit_val, true) : '',
		'serba_serbi_rekening' => $rek_raw,
		'serba_serbi_jumlah' => $jumlah_val > 0 ? penerimaan_kas_format_rupiah($jumlah_val, true) : '',
	);

	$CI->Jurnal_penerimaan_kas_model->insert($data);

	if ($CI->db->affected_rows() <= 0) {
		return array('ok' => false, 'message' => 'Gagal menyimpan data jurnal penerimaan kas.');
	}

	return array(
		'ok' => true,
		'message' => 'Data jurnal penerimaan kas berhasil disimpan.',
		'id' => $CI->db->insert_id(),
	);
}

function penerimaan_kas_excel_title_col_end()
{
	return 6;
}

function penerimaan_kas_excel_write_merged_row($rowNum, $text, $styleIndex, $colEnd = null)
{
	if ($colEnd === null) {
		$colEnd = penerimaan_kas_excel_title_col_end();
	}

	xlsAddMerge($rowNum, 0, $rowNum, $colEnd);
	xlsWriteCellStyle($rowNum, 0, $text, $styleIndex);
	for ($c = 1; $c <= $colEnd; $c++) {
		xlsEnsureCellStyle($rowNum, $c, $styleIndex, '');
	}
}

function penerimaan_kas_excel_write_title_block($month, $year)
{
	$styleTitleBoldLeft = 11;
	$styleTitleItalicLeft = 12;
	$styleTitleBoldCenter = 13;
	$bulan_nama = strtoupper(penerimaan_kas_bulan_teks((int) $month));
	$titleColEnd = penerimaan_kas_excel_title_col_end();

	penerimaan_kas_excel_write_merged_row(0, 'PERUMDA ANEKA DHARMA', $styleTitleBoldLeft, $titleColEnd);
	penerimaan_kas_excel_write_merged_row(1, 'KABUPATEN BANTUL', $styleTitleBoldLeft, $titleColEnd);
	penerimaan_kas_excel_write_merged_row(2, 'Jl. Jend. Sudirman 36 Bantul. Telp / Fax : 0274 367123', $styleTitleItalicLeft, $titleColEnd);
	penerimaan_kas_excel_write_merged_row(
		3,
		'JURNAL PENERIMAAN KAS ' . $bulan_nama . ' ' . (int) $year,
		$styleTitleBoldCenter,
		$titleColEnd
	);
}

function penerimaan_kas_export_excel_write_headers()
{
	$styleTableHeaderGreen = 14;
	$headerRow = 5;

	$headers = array(
		'NO',
		'TANGGAL',
		'NO. BUKTI BKM',
		'PL',
		'KETERANGAN',
		'11101-KAS BESAR',
		'11301-PU NON ANGSURAN',
		'NO. REK',
		'JUMLAH',
	);

	foreach ($headers as $col => $label) {
		xlsWriteCellStyle($headerRow, $col, $label, $styleTableHeaderGreen);
	}
}

function penerimaan_kas_export_excel_write_row($rowNum, $item)
{
	$styleBorder = 3;
	$styleRight = 8;
	$styleLeft = 7;
	$styleSubtotal = 6;

	$isSubtotal = (isset($item['type']) && $item['type'] === 'subtotal');
	$styleText = $isSubtotal ? $styleSubtotal : $styleLeft;
	$styleNum = $isSubtotal ? $styleSubtotal : $styleRight;

	xlsWriteCellStyle($rowNum, 0, $item['no'], $styleBorder);
	xlsWriteCellStyle($rowNum, 1, $item['tanggal'], $styleText);
	xlsWriteCellStyle($rowNum, 2, $item['bukti'], $styleText);
	xlsWriteCellStyle($rowNum, 3, $item['pl'], $styleText);
	xlsWriteCellStyle($rowNum, 4, $item['keterangan'], $styleText);
	xlsWriteCellStyle($rowNum, 5, penerimaan_kas_format_rupiah($item['debet_11101'], $isSubtotal), $styleNum);
	xlsWriteCellStyle($rowNum, 6, penerimaan_kas_format_rupiah($item['kredit_11301'], $isSubtotal), $styleNum);
	xlsWriteCellStyle($rowNum, 7, $item['kode_rekening'], $styleText);
	xlsWriteCellStyle($rowNum, 8, penerimaan_kas_format_rupiah($item['jumlah'], $isSubtotal), $styleNum);
}

function penerimaan_kas_export_excel_list_output($CI, $date_awal, $date_akhir)
{
	@set_time_limit(600);
	$CI->load->helper('exportexcel');

	$data = penerimaan_kas_compute_list_data($CI, $date_awal, $date_akhir);
	$month = (int) $data['month_akhir'];
	$year = (int) $data['year_akhir'];
	$periode_slug = date('Ymd', strtotime($date_awal)) . '_' . date('Ymd', strtotime($date_akhir));
	$namaFile = 'Jurnal_Penerimaan_Kas_' . $periode_slug . '_' . date('Y-m-d_H-i-s') . '.xlsx';
	excel_prepare_download($namaFile);

	xlsBOF();
	xlsSetColumnWidths(array(5, 12, 14, 6, 36, 14, 16, 12, 14));

	penerimaan_kas_excel_write_title_block($month, $year);

	penerimaan_kas_export_excel_write_headers();

	$rowNum = 6;
	foreach ($data['rows'] as $item) {
		penerimaan_kas_export_excel_write_row($rowNum, $item);
		$rowNum++;
	}

	$styleFooter = 5;
	xlsWriteCellStyle($rowNum, 0, '', $styleFooter);
	xlsWriteCellStyle($rowNum, 1, '', $styleFooter);
	xlsWriteCellStyle($rowNum, 2, '', $styleFooter);
	xlsWriteCellStyle($rowNum, 3, '', $styleFooter);
	xlsWriteCellStyle($rowNum, 4, 'GRAND TOTAL', $styleFooter);
	xlsWriteCellStyle($rowNum, 5, penerimaan_kas_format_rupiah($data['TOTAL_debet_11101_SEMUA'], true), $styleFooter);
	xlsWriteCellStyle($rowNum, 6, '', $styleFooter);
	xlsWriteCellStyle($rowNum, 7, '', $styleFooter);
	xlsWriteCellStyle($rowNum, 8, penerimaan_kas_format_rupiah($data['TOTAL_kredit_jumlah_SEMUA'] + $data['TOTAL_kredit_11301_SEMUA'], true), $styleFooter);

	xlsEOF();
}
