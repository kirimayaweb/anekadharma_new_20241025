<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function pengeluaran_kas_bulan_teks($angka_bulan)
{
	$angka_bulan = (int) $angka_bulan;
	$nama = array(
		1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
		5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
		9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
	);
	return isset($nama[$angka_bulan]) ? $nama[$angka_bulan] : '';
}

function pengeluaran_kas_table_name()
{
	return 'jurnal_pengeluaran_kas';
}

function pengeluaran_kas_parse_amount($value)
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

function pengeluaran_kas_format_rupiah($value, $always_show = false)
{
	if ($value === null || $value === '') {
		return '';
	}
	if (!$always_show && (float) $value == 0) {
		return '';
	}
	return number_format((float) $value, 0, ',', '.');
}

function pengeluaran_kas_compute_footer_totals($total_debet, $total_jumlah, $total_kredit)
{
	$total_debet = (float) $total_debet;
	$total_jumlah = (float) $total_jumlah;
	$total_kredit = (float) $total_kredit;

	return array(
		'debet_21101' => $total_debet,
		'serba_serbi_jumlah' => $total_jumlah,
		'kredit_kas' => $total_kredit,
		'combined_debet_21101' => $total_debet + $total_jumlah,
	);
}

function pengeluaran_kas_parse_tanggal_to_ts($tanggal_str)
{
	$tanggal_str = trim((string) $tanggal_str);
	if ($tanggal_str === '') {
		return false;
	}
	if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $tanggal_str, $m)) {
		return strtotime($m[3] . '-' . $m[2] . '-' . $m[1]);
	}
	if (preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $tanggal_str, $m)) {
		return strtotime($m[3] . '-' . $m[2] . '-' . $m[1]);
	}
	$ts = strtotime($tanggal_str);
	return $ts ? $ts : false;
}

function pengeluaran_kas_format_tanggal_display($tanggal_str)
{
	$ts = pengeluaran_kas_parse_tanggal_to_ts($tanggal_str);
	return $ts ? date('d-m-Y', $ts) : trim((string) $tanggal_str);
}

function pengeluaran_kas_format_tanggal_storage($tanggal_str)
{
	$ts = pengeluaran_kas_parse_tanggal_to_ts($tanggal_str);
	return $ts ? date('Y-m-d', $ts) : trim((string) $tanggal_str);
}

function pengeluaran_kas_parse_filter_date($date_str, $is_end = false)
{
	$date_str = trim((string) $date_str);
	if ($date_str === '' || (int) date('Y', strtotime($date_str)) < 2020) {
		return null;
	}
	if ($is_end) {
		return date('Y-m-d 23:59:59', strtotime($date_str));
	}
	return date('Y-m-d 00:00:00', strtotime($date_str));
}

function pengeluaran_kas_parse_filter_month($bulan_ns)
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

function pengeluaran_kas_resolve_filter_dates($tgl_awal, $tgl_akhir, $bulan_ns = '')
{
	$parsed_month = pengeluaran_kas_parse_filter_month($bulan_ns);
	if ($parsed_month !== null) {
		return array(
			'date_awal' => $parsed_month['date_awal'],
			'date_akhir' => $parsed_month['date_akhir'],
			'month_akhir' => (int) $parsed_month['month'],
		);
	}

	if (date('Y', strtotime($tgl_awal)) < 2020) {
		$date_awal = date('Y-m-d', strtotime('-1 day'));
	} else {
		$date_awal = date('Y-m-d 00:00:00', strtotime($tgl_awal));
	}

	if (date('Y', strtotime($tgl_akhir)) < 2020) {
		$date_akhir = date('Y-m-d 00:00:00');
		$month_akhir = (int) date('m');
	} else {
		$date_akhir = date('Y-m-d 23:59:59', strtotime($tgl_akhir));
		$month_akhir = (int) date('m', strtotime($tgl_akhir));
	}

	return array(
		'date_awal' => $date_awal,
		'date_akhir' => $date_akhir,
		'month_akhir' => $month_akhir,
	);
}

function pengeluaran_kas_pk_column($CI)
{
	$table = pengeluaran_kas_table_name();
	if (!$CI->db->table_exists($table)) {
		return 'nomor';
	}
	$fields = $CI->db->list_fields($table);
	if (in_array('id', $fields, true)) {
		return 'id';
	}
	return 'nomor';
}

function pengeluaran_kas_rekening_db_column($CI)
{
	static $cache = null;
	if ($cache !== null) {
		return $cache;
	}

	$table = pengeluaran_kas_table_name();
	if (!$CI->db->table_exists($table)) {
		$cache = 'serba_serbi_nomor_rekening';
		return $cache;
	}

	$fields = $CI->db->list_fields($table);
	if (in_array('serba-serbi_nomor_rekening', $fields, true)) {
		$cache = 'serba-serbi_nomor_rekening';
	} else {
		$cache = 'serba_serbi_nomor_rekening';
	}

	return $cache;
}

function pengeluaran_kas_align_insert_row($CI, $data)
{
	if (!is_array($data)) {
		return array();
	}

	$rek_key = pengeluaran_kas_rekening_db_column($CI);
	if ($rek_key !== 'serba_serbi_nomor_rekening' && array_key_exists('serba_serbi_nomor_rekening', $data)) {
		$data[$rek_key] = $data['serba_serbi_nomor_rekening'];
		unset($data['serba_serbi_nomor_rekening']);
	}

	$table = pengeluaran_kas_table_name();
	if (!$CI->db->table_exists($table)) {
		return $data;
	}

	$fields = $CI->db->list_fields($table);
	$out = array();
	foreach ($data as $key => $value) {
		if (in_array($key, $fields, true)) {
			$out[$key] = $value;
		}
	}

	return $out;
}

function pengeluaran_kas_session_bulan_key()
{
	return 'jurnal_pengeluaran_kas_bulan_terpilih';
}

function pengeluaran_kas_save_bulan_session($CI, $bulan_ns)
{
	$parsed = pengeluaran_kas_parse_filter_month($bulan_ns);
	if ($parsed !== null) {
		$CI->session->set_userdata(pengeluaran_kas_session_bulan_key(), $parsed['bulan_ns']);
	}
}

function pengeluaran_kas_get_bulan_from_session($CI)
{
	$val = trim((string) $CI->session->userdata(pengeluaran_kas_session_bulan_key()));
	return pengeluaran_kas_parse_filter_month($val);
}

function pengeluaran_kas_row_pk($row)
{
	if (is_array($row)) {
		return isset($row['id']) ? $row['id'] : (isset($row['nomor']) ? $row['nomor'] : null);
	}
	return isset($row->id) ? $row->id : (isset($row->nomor) ? $row->nomor : null);
}

function pengeluaran_kas_build_row_from_db($row, $no, $can_manage = true)
{
	$pk = pengeluaran_kas_row_pk($row);
	$rek = '';
	if (is_array($row)) {
		$rek = isset($row['serba_serbi_nomor_rekening']) ? $row['serba_serbi_nomor_rekening'] : (isset($row['serba-serbi_nomor_rekening']) ? $row['serba-serbi_nomor_rekening'] : '');
	} else {
		$rek = isset($row->serba_serbi_nomor_rekening) ? $row->serba_serbi_nomor_rekening : (isset($row->{'serba-serbi_nomor_rekening'}) ? $row->{'serba-serbi_nomor_rekening'} : '');
	}

	return array(
		'no' => $no,
		'pk' => $pk,
		'tanggal' => pengeluaran_kas_format_tanggal_display(isset($row->tanggal) ? $row->tanggal : (is_array($row) && isset($row['tanggal']) ? $row['tanggal'] : '')),
		'nomor_bukti_bkk' => isset($row->nomor_bukti_bkk) ? $row->nomor_bukti_bkk : (is_array($row) && isset($row['nomor_bukti_bkk']) ? $row['nomor_bukti_bkk'] : ''),
		'pl' => isset($row->pl) ? $row->pl : (is_array($row) && isset($row['pl']) ? $row['pl'] : ''),
		'keterangan' => isset($row->keterangan) ? $row->keterangan : (is_array($row) && isset($row['keterangan']) ? $row['keterangan'] : ''),
		'debet_21101uu_dagang' => pengeluaran_kas_parse_amount(isset($row->debet_21101uu_dagang) ? $row->debet_21101uu_dagang : (is_array($row) && isset($row['debet_21101uu_dagang']) ? $row['debet_21101uu_dagang'] : 0)),
		'serba_serbi_nomor_rekening' => $rek,
		'serba_serbi_jumlah' => pengeluaran_kas_parse_amount(isset($row->serba_serbi_jumlah) ? $row->serba_serbi_jumlah : (is_array($row) && isset($row['serba_serbi_jumlah']) ? $row['serba_serbi_jumlah'] : 0)),
		'kredit_11101_kas_besar' => pengeluaran_kas_parse_amount(isset($row->kredit_11101_kas_besar) ? $row->kredit_11101_kas_besar : (is_array($row) && isset($row['kredit_11101_kas_besar']) ? $row['kredit_11101_kas_besar'] : 0)),
		'can_manage' => $can_manage,
	);
}

function pengeluaran_kas_compute_list_data($CI, $date_awal, $date_akhir)
{
	$table = pengeluaran_kas_table_name();
	if (!$CI->db->table_exists($table)) {
		return array(
			'rows' => array(),
			'TOTAL_debet_21101_SEMUA' => 0,
			'TOTAL_serba_serbi_jumlah_SEMUA' => 0,
			'TOTAL_kredit_11101_SEMUA' => 0,
			'periode_label' => '',
			'bulan_label' => '',
		);
	}

	$start_ymd = date('Y-m-d', strtotime($date_awal));
	$end_ymd = date('Y-m-d', strtotime($date_akhir));

	$sql = 'SELECT * FROM `' . $table . '`
		WHERE tanggal IS NOT NULL AND tanggal <> \'0000-00-00\'
		AND DATE(tanggal) >= ? AND DATE(tanggal) <= ?
		ORDER BY tanggal ASC, nomor ASC';

	$rows_db = $CI->db->query($sql, array($start_ymd, $end_ymd))->result();

	$rows = array();
	$no = 0;
	$total_debet = 0.0;
	$total_jumlah = 0.0;
	$total_kredit = 0.0;

	foreach ($rows_db as $row) {
		$item = pengeluaran_kas_build_row_from_db($row, ++$no, true);
		$rows[] = $item;
		$total_debet += (float) $item['debet_21101uu_dagang'];
		$total_jumlah += (float) $item['serba_serbi_jumlah'];
		$total_kredit += (float) $item['kredit_11101_kas_besar'];
	}

	$label_awal = date('d-m-Y', strtotime($date_awal));
	$label_akhir = date('d-m-Y', strtotime($date_akhir));
	$month_akhir = (int) date('m', strtotime($date_akhir));
	$year_akhir = (int) date('Y', strtotime($date_akhir));

	return array(
		'date_awal' => $date_awal,
		'date_akhir' => $date_akhir,
		'periode_label' => $label_awal . ' s/d ' . $label_akhir,
		'bulan_label' => pengeluaran_kas_bulan_teks($month_akhir) . ' ' . $year_akhir,
		'month_akhir' => $month_akhir,
		'year_akhir' => $year_akhir,
		'rows' => $rows,
		'TOTAL_debet_21101_SEMUA' => $total_debet,
		'TOTAL_serba_serbi_jumlah_SEMUA' => $total_jumlah,
		'TOTAL_kredit_11101_SEMUA' => $total_kredit,
		'TOTAL_combined_debet_21101_SEMUA' => $total_debet + $total_jumlah,
	);
}

function pengeluaran_kas_validate_form_post($CI, $is_update = false)
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
	if (!pengeluaran_kas_parse_tanggal_to_ts($tanggal)) {
		return array('ok' => false, 'message' => 'Format tanggal tidak valid.');
	}

	if ($is_update) {
		$pk = trim((string) $CI->input->post('pk', TRUE));
		if ($pk === '') {
			return array('ok' => false, 'message' => 'Data tidak ditemukan (ID kosong).');
		}
	}

	return array('ok' => true);
}

function pengeluaran_kas_build_db_payload_from_post($CI)
{
	$deb = pengeluaran_kas_parse_amount($CI->input->post('debet_21101uu_dagang', TRUE));
	$jml = pengeluaran_kas_parse_amount($CI->input->post('serba_serbi_jumlah', TRUE));
	$kre = pengeluaran_kas_parse_amount($CI->input->post('kredit_11101_kas_besar', TRUE));

	return array(
		'tanggal' => pengeluaran_kas_format_tanggal_storage($CI->input->post('tanggal', TRUE)),
		'nomor_bukti_bkk' => trim((string) $CI->input->post('nomor_bukti_bkk', TRUE)),
		'pl' => trim((string) $CI->input->post('pl', TRUE)),
		'keterangan' => trim((string) $CI->input->post('keterangan', TRUE)),
		'debet_21101uu_dagang' => $deb > 0 ? (string) (int) round($deb) : '',
		'serba_serbi_nomor_rekening' => trim((string) $CI->input->post('serba_serbi_nomor_rekening', TRUE)),
		'serba_serbi_jumlah' => $jml > 0 ? (string) (int) round($jml) : '',
		'kredit_11101_kas_besar' => $kre > 0 ? (string) (int) round($kre) : '',
	);
}

function pengeluaran_kas_get_record($CI, $pk)
{
	$table = pengeluaran_kas_table_name();
	$pk_col = pengeluaran_kas_pk_column($CI);
	$row = $CI->db->get_where($table, array($pk_col => $pk))->row();
	if (!$row) {
		return array('ok' => false, 'message' => 'Data tidak ditemukan.');
	}

	$item = pengeluaran_kas_build_row_from_db($row, 0, true);
	return array('ok' => true, 'data' => $item);
}

function pengeluaran_kas_save_from_post($CI)
{
	$valid = pengeluaran_kas_validate_form_post($CI, false);
	if (empty($valid['ok'])) {
		return $valid;
	}

	$data = pengeluaran_kas_align_insert_row($CI, pengeluaran_kas_build_db_payload_from_post($CI));
	$CI->Jurnal_pengeluaran_kas_model->insert($data);

	if ($CI->db->affected_rows() <= 0) {
		return array('ok' => false, 'message' => 'Gagal menyimpan data jurnal pengeluaran kas.');
	}

	return array(
		'ok' => true,
		'message' => 'Data jurnal pengeluaran kas berhasil disimpan.',
		'id' => $CI->db->insert_id(),
	);
}

function pengeluaran_kas_update_from_post($CI)
{
	$valid = pengeluaran_kas_validate_form_post($CI, true);
	if (empty($valid['ok'])) {
		return $valid;
	}

	$pk = trim((string) $CI->input->post('pk', TRUE));
	$pk_col = pengeluaran_kas_pk_column($CI);
	$table = pengeluaran_kas_table_name();

	$exists = $CI->db->get_where($table, array($pk_col => $pk))->row();
	if (!$exists) {
		return array('ok' => false, 'message' => 'Data tidak ditemukan.');
	}

	$data = pengeluaran_kas_align_insert_row($CI, pengeluaran_kas_build_db_payload_from_post($CI));
	$CI->db->where($pk_col, $pk);
	$CI->db->update($table, $data);

	if ($CI->db->affected_rows() < 0) {
		return array('ok' => false, 'message' => 'Gagal mengubah data jurnal pengeluaran kas.');
	}

	return array(
		'ok' => true,
		'message' => 'Data jurnal pengeluaran kas berhasil diubah.',
	);
}

function pengeluaran_kas_delete_by_pk($CI, $pk)
{
	$pk = trim((string) $pk);
	if ($pk === '') {
		return array('ok' => false, 'message' => 'ID data tidak valid.');
	}

	$pk_col = pengeluaran_kas_pk_column($CI);
	$table = pengeluaran_kas_table_name();
	$exists = $CI->db->get_where($table, array($pk_col => $pk))->row();
	if (!$exists) {
		return array('ok' => false, 'message' => 'Data tidak ditemukan.');
	}

	$CI->db->where($pk_col, $pk);
	$CI->db->delete($table);

	if ($CI->db->affected_rows() <= 0) {
		return array('ok' => false, 'message' => 'Gagal menghapus data jurnal pengeluaran kas.');
	}

	return array(
		'ok' => true,
		'message' => 'Data jurnal pengeluaran kas berhasil dihapus.',
	);
}

function pengeluaran_kas_ajax_list_response($CI, $tgl_awal, $tgl_akhir, $bulan_ns = '')
{
	$bulan_ns = trim((string) $bulan_ns);
	if ($bulan_ns === '' && ($tgl_awal === '' || $tgl_akhir === '')) {
		return array('ok' => false, 'message' => 'Pilih bulan terlebih dahulu.');
	}

	$resolved = pengeluaran_kas_resolve_filter_dates($tgl_awal, $tgl_akhir, $bulan_ns);
	if ($bulan_ns !== '') {
		pengeluaran_kas_save_bulan_session($CI, $bulan_ns);
	} elseif ($tgl_awal !== '' && $tgl_akhir !== '') {
		$month_from_range = pengeluaran_kas_parse_filter_month(date('Y-m', strtotime($resolved['date_akhir'])));
		if ($month_from_range !== null) {
			pengeluaran_kas_save_bulan_session($CI, $month_from_range['bulan_ns']);
		}
	}
	$list = pengeluaran_kas_compute_list_data($CI, $resolved['date_awal'], $resolved['date_akhir']);
	$footer_totals = pengeluaran_kas_compute_footer_totals(
		$list['TOTAL_debet_21101_SEMUA'],
		$list['TOTAL_serba_serbi_jumlah_SEMUA'],
		$list['TOTAL_kredit_11101_SEMUA']
	);

	return array(
		'ok' => true,
		'rows' => $list['rows'],
		'totals' => $footer_totals,
		'periode_label' => $list['periode_label'],
		'bulan_label' => $list['bulan_label'],
		'bulan_ns' => sprintf('%04d-%02d', (int) $list['year_akhir'], (int) $list['month_akhir']),
	);
}

function pengeluaran_kas_excel_title_col_end()
{
	return 7;
}

function pengeluaran_kas_excel_write_merged_row($rowNum, $text, $styleIndex, $colEnd = null)
{
	if ($colEnd === null) {
		$colEnd = pengeluaran_kas_excel_title_col_end();
	}

	xlsAddMerge($rowNum, 0, $rowNum, $colEnd);
	xlsWriteCellStyle($rowNum, 0, $text, $styleIndex);
	for ($c = 1; $c <= $colEnd; $c++) {
		xlsEnsureCellStyle($rowNum, $c, $styleIndex, '');
	}
}

function pengeluaran_kas_excel_write_title_block($month, $year)
{
	$styleTitleBoldLeft = 11;
	$styleTitleItalicLeft = 12;
	$styleTitleBoldCenter = 13;
	$bulan_nama = strtoupper(pengeluaran_kas_bulan_teks((int) $month));
	$titleColEnd = pengeluaran_kas_excel_title_col_end();

	// Baris 1-3: kop surat (colspan 8, rata kiri)
	pengeluaran_kas_excel_write_merged_row(0, 'PERUMDA ANEKA DHARMA', $styleTitleBoldLeft, $titleColEnd);
	pengeluaran_kas_excel_write_merged_row(1, 'KABUPATEN BANTUL', $styleTitleBoldLeft, $titleColEnd);
	pengeluaran_kas_excel_write_merged_row(2, 'Jl. Jend. Sudirman 36 Bantul. Telp / Fax : 0274 367123', $styleTitleItalicLeft, $titleColEnd);

	// Baris 4 kosong (indeks 3)

	// Baris 5: judul laporan (colspan 8, rata tengah)
	pengeluaran_kas_excel_write_merged_row(
		4,
		'JURNAL PENGELUARAN KAS ' . $bulan_nama . ' ' . (int) $year,
		$styleTitleBoldCenter,
		$titleColEnd
	);

	// Baris 6 kosong (indeks 5)
}

function pengeluaran_kas_excel_write_merged_region($rowStart, $colStart, $rowEnd, $colEnd, $text, $styleIndex)
{
	if ($rowStart !== $rowEnd || $colStart !== $colEnd) {
		xlsAddMerge($rowStart, $colStart, $rowEnd, $colEnd);
	}
	xlsWriteCellStyle($rowStart, $colStart, $text, $styleIndex);
	for ($r = (int) $rowStart; $r <= (int) $rowEnd; $r++) {
		for ($c = (int) $colStart; $c <= (int) $colEnd; $c++) {
			if ($r === (int) $rowStart && $c === (int) $colStart) {
				continue;
			}
			xlsEnsureCellStyle($r, $c, $styleIndex, '');
		}
	}
}

function pengeluaran_kas_export_excel_write_headers()
{
	$styleTableHeaderGreen = 14;
	$rowGroup1 = 6;
	$rowGroup2 = 7;
	$rowLeaf = 8;

	$labelsFixed = array('NO', 'TANGGAL', 'NO. BUKTI BKK', 'PL', 'KETERANGAN');
	foreach ($labelsFixed as $col => $label) {
		pengeluaran_kas_excel_write_merged_region($rowGroup1, $col, $rowLeaf, $col, $label, $styleTableHeaderGreen);
	}

	pengeluaran_kas_excel_write_merged_region($rowGroup1, 5, $rowGroup1, 7, 'DEBIT', $styleTableHeaderGreen);
	pengeluaran_kas_excel_write_merged_region($rowGroup1, 8, $rowGroup1, 8, 'KREDIT', $styleTableHeaderGreen);

	pengeluaran_kas_excel_write_merged_region($rowGroup2, 5, $rowLeaf, 5, '21101-UU DAGANG', $styleTableHeaderGreen);
	pengeluaran_kas_excel_write_merged_region($rowGroup2, 6, $rowGroup2, 7, 'SERBA - SERBI', $styleTableHeaderGreen);
	pengeluaran_kas_excel_write_merged_region($rowGroup2, 8, $rowLeaf, 8, '11101-KAS BESAR', $styleTableHeaderGreen);

	xlsWriteCellStyle($rowLeaf, 6, 'NO. REK', $styleTableHeaderGreen);
	xlsWriteCellStyle($rowLeaf, 7, 'JUMLAH SERBA-SERBI', $styleTableHeaderGreen);

	return 9;
}

function pengeluaran_kas_export_excel_write_row($rowNum, $item)
{
	$styleBorder = 3;
	$styleRight = 8;
	$styleLeft = 7;

	xlsWriteCellStyle($rowNum, 0, $item['no'], $styleBorder);
	xlsWriteCellStyle($rowNum, 1, $item['tanggal'], $styleLeft);
	xlsWriteCellStyle($rowNum, 2, $item['nomor_bukti_bkk'], $styleLeft);
	xlsWriteCellStyle($rowNum, 3, $item['pl'], $styleLeft);
	xlsWriteCellStyle($rowNum, 4, $item['keterangan'], $styleLeft);
	xlsWriteCellStyle($rowNum, 5, pengeluaran_kas_format_rupiah($item['debet_21101uu_dagang']), $styleRight);
	xlsWriteCellStyle($rowNum, 6, $item['serba_serbi_nomor_rekening'], $styleLeft);
	xlsWriteCellStyle($rowNum, 7, pengeluaran_kas_format_rupiah($item['serba_serbi_jumlah']), $styleRight);
	xlsWriteCellStyle($rowNum, 8, pengeluaran_kas_format_rupiah($item['kredit_11101_kas_besar']), $styleRight);
}

function pengeluaran_kas_export_excel_list_output($CI, $date_awal, $date_akhir)
{
	@set_time_limit(600);
	$CI->load->helper('exportexcel');

	$data = pengeluaran_kas_compute_list_data($CI, $date_awal, $date_akhir);
	$periode_slug = date('Ymd', strtotime($date_awal)) . '_' . date('Ymd', strtotime($date_akhir));
	$namaFile = 'Jurnal_Pengeluaran_Kas_' . $periode_slug . '_' . date('Y-m-d_H-i-s') . '.xlsx';
	excel_prepare_download($namaFile);

	xlsBOF();
	xlsSetColumnWidths(array(5, 12, 14, 6, 36, 14, 12, 16, 14));

	pengeluaran_kas_excel_write_title_block($data['month_akhir'], $data['year_akhir']);

	$rowNum = pengeluaran_kas_export_excel_write_headers();
	foreach ($data['rows'] as $item) {
		pengeluaran_kas_export_excel_write_row($rowNum, $item);
		$rowNum++;
	}

	$footer_totals = pengeluaran_kas_compute_footer_totals(
		$data['TOTAL_debet_21101_SEMUA'],
		$data['TOTAL_serba_serbi_jumlah_SEMUA'],
		$data['TOTAL_kredit_11101_SEMUA']
	);
	$styleFooter = 5;

	xlsWriteCellStyle($rowNum, 0, '', $styleFooter);
	xlsWriteCellStyle($rowNum, 1, '', $styleFooter);
	xlsWriteCellStyle($rowNum, 2, '', $styleFooter);
	xlsWriteCellStyle($rowNum, 3, '', $styleFooter);
	xlsWriteCellStyle($rowNum, 4, 'GRAND TOTAL', $styleFooter);
	xlsWriteCellStyle($rowNum, 5, pengeluaran_kas_format_rupiah($footer_totals['debet_21101'], true), $styleFooter);
	xlsWriteCellStyle($rowNum, 6, '', $styleFooter);
	xlsWriteCellStyle($rowNum, 7, pengeluaran_kas_format_rupiah($footer_totals['serba_serbi_jumlah'], true), $styleFooter);
	xlsWriteCellStyle($rowNum, 8, pengeluaran_kas_format_rupiah($footer_totals['kredit_kas'], true), $styleFooter);
	$rowNum++;

	xlsWriteCellStyle($rowNum, 0, '', $styleFooter);
	xlsWriteCellStyle($rowNum, 1, '', $styleFooter);
	xlsWriteCellStyle($rowNum, 2, '', $styleFooter);
	xlsWriteCellStyle($rowNum, 3, '', $styleFooter);
	xlsWriteCellStyle($rowNum, 4, 'TOTAL', $styleFooter);
	xlsWriteCellStyle($rowNum, 5, pengeluaran_kas_format_rupiah($footer_totals['combined_debet_21101'], true), $styleFooter);
	xlsWriteCellStyle($rowNum, 6, '', $styleFooter);
	xlsWriteCellStyle($rowNum, 7, '', $styleFooter);
	xlsWriteCellStyle($rowNum, 8, pengeluaran_kas_format_rupiah($footer_totals['kredit_kas'], true), $styleFooter);

	xlsEOF();
}
