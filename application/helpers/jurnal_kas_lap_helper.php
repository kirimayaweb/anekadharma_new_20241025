<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function jurnal_kas_lap_publish_table_name()
{
	return 'jurnal_kas_lap_publish';
}

function jurnal_kas_lap_ensure_publish_table($CI)
{
	$table = jurnal_kas_lap_publish_table_name();
	if ($CI->db->table_exists($table)) {
		return true;
	}

	$sql = "CREATE TABLE IF NOT EXISTS `" . $table . "` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`bulan_key` varchar(7) NOT NULL,
		`source_type` varchar(16) NOT NULL DEFAULT 'asli',
		`source_table` varchar(128) DEFAULT NULL,
		`published_at` datetime DEFAULT NULL,
		`published_by` int(11) DEFAULT NULL,
		PRIMARY KEY (`id`),
		UNIQUE KEY `bulan_key` (`bulan_key`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8";

	return (bool) $CI->db->query($sql);
}

function jurnal_kas_lap_normalize_bulan_key($year, $month)
{
	$year = (int) $year;
	$month = (int) $month;
	if ($month < 1 || $month > 12 || $year < 2000) {
		return null;
	}

	return sprintf('%04d-%02d', $year, $month);
}

function jurnal_kas_lap_get_publish_setting($CI, $bulan_key)
{
	if (!preg_match('/^\d{4}-\d{2}$/', (string) $bulan_key)) {
		return null;
	}

	jurnal_kas_lap_ensure_publish_table($CI);
	$table = jurnal_kas_lap_publish_table_name();
	$row = $CI->db->get_where($table, array('bulan_key' => $bulan_key))->row();
	if (!$row) {
		return null;
	}

	return array(
		'bulan_key' => $row->bulan_key,
		'source_type' => $row->source_type,
		'source_table' => $row->source_table,
		'published_at' => $row->published_at,
		'published_by' => $row->published_by,
	);
}

function jurnal_kas_lap_save_publish_setting($CI, $bulan_key, $source_type, $source_table = null)
{
	$CI->load->helper(array('pembelian_persediaan', 'jurnal_kas_compare'));

	if (!preg_match('/^\d{4}-\d{2}$/', (string) $bulan_key)) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');
	}

	$source_type = trim((string) $source_type);
	if ($source_type !== 'asli' && $source_type !== 'tabel') {
		return array('ok' => false, 'message' => 'Jenis sumber data tidak valid.');
	}

	$source_table = trim((string) $source_table);
	if ($source_type === 'tabel') {
		if ($source_table === '' || !persediaan_compare_is_valid_table_name($source_table)) {
			return array('ok' => false, 'message' => 'Nama tabel tidak valid.');
		}
		if (!$CI->db->table_exists($source_table)) {
			return array('ok' => false, 'message' => 'Tabel `' . $source_table . '` tidak ditemukan.');
		}
		$CI->load->helper('jurnal_kas_compare');
		$valid = jurnal_kas_compare_validate_import_table($CI, $source_table);
		if (empty($valid['ok'])) {
			return array(
				'ok' => false,
				'message' => isset($valid['message']) ? $valid['message'] : 'Struktur tabel tidak valid untuk jurnal kas.',
			);
		}
	} else {
		$source_table = null;
	}

	jurnal_kas_lap_ensure_publish_table($CI);
	$table = jurnal_kas_lap_publish_table_name();
	$user_id = (int) $CI->session->userdata('id_user');
	$payload = array(
		'bulan_key' => $bulan_key,
		'source_type' => $source_type,
		'source_table' => $source_table,
		'published_at' => date('Y-m-d H:i:s'),
		'published_by' => $user_id > 0 ? $user_id : null,
	);

	$existing = $CI->db->get_where($table, array('bulan_key' => $bulan_key))->row();
	if ($existing) {
		$CI->db->where('id', (int) $existing->id);
		$CI->db->update($table, $payload);
	} else {
		$CI->db->insert($table, $payload);
	}

	return array(
		'ok' => true,
		'message' => 'Pengaturan laporan jurnal kas berhasil dipublish.',
		'setting' => jurnal_kas_lap_get_publish_setting($CI, $bulan_key),
	);
}

function jurnal_kas_lap_compute_from_table($CI, $month, $year, $table)
{
	$CI->load->helper(array('jurnal_kas_compare', 'jurnal_kas_list', 'pembelian_persediaan'));

	$bulan_key = jurnal_kas_lap_normalize_bulan_key($year, $month);
	if ($bulan_key === null) {
		return array('ok' => false, 'message' => 'Bulan atau tahun tidak valid.');
	}

	$detail = jurnal_kas_compare_load_table_detail_for_bulan($CI, $table, $bulan_key);
	if (empty($detail['ok'])) {
		return $detail;
	}

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
		'sumber' => 'Tabel: ' . $table,
		'unit' => '',
		'tanggal' => '',
		'bukti' => '',
		'keterangan' => $saldo_info['label'],
		'debet' => ($saldo_bulan_lalu > 0) ? $saldo_bulan_lalu : null,
		'kredit' => ($saldo_bulan_lalu < 1) ? $saldo_bulan_lalu : null,
	);

	foreach ((array) $detail['rows'] as $item) {
		$debet = isset($item['debet_raw']) ? (float) $item['debet_raw'] : 0.0;
		$kredit = isset($item['kredit_raw']) ? (float) $item['kredit_raw'] : 0.0;
		if ($debet > 0) {
			$TOTAL_debet += $debet;
		}
		if ($kredit > 0) {
			$TOTAL_kredit += $kredit;
		}

		$rows[] = array(
			'type' => 'data',
			'no' => $no++,
			'sumber' => 'Tabel: ' . $table,
			'unit' => isset($item['kode_rekening']) ? $item['kode_rekening'] : '',
			'tanggal' => isset($item['tanggal']) ? $item['tanggal'] : '',
			'bukti' => isset($item['bukti']) ? $item['bukti'] : '',
			'keterangan' => isset($item['keterangan']) ? $item['keterangan'] : '',
			'debet' => ($debet > 0) ? $debet : null,
			'kredit' => ($kredit > 0) ? $kredit : null,
		);
	}

	$SALDO_AKHIR = $TOTAL_debet - $TOTAL_kredit;

	return array(
		'ok' => true,
		'month' => (int) $month,
		'year' => (int) $year,
		'bulan_key' => $bulan_key,
		'bulan_label' => jurnal_kas_bulan_teks($month) . ' ' . $year,
		'source_type' => 'tabel',
		'source_table' => $table,
		'rows' => $rows,
		'TOTAL_debet' => $TOTAL_debet,
		'TOTAL_kredit' => $TOTAL_kredit,
		'SALDO_AKHIR' => $SALDO_AKHIR,
	);
}

function jurnal_kas_lap_compute_report_data($CI, $month, $year, $source_type = null, $source_table = null, $use_publish = true)
{
	$CI->load->helper('jurnal_kas_list');

	$month = (int) $month;
	$year = (int) $year;
	$bulan_key = jurnal_kas_lap_normalize_bulan_key($year, $month);
	if ($bulan_key === null) {
		return array('ok' => false, 'message' => 'Bulan atau tahun tidak valid.');
	}

	$publish = null;
	if ($use_publish && ($source_type === null || $source_type === '')) {
		$publish = jurnal_kas_lap_get_publish_setting($CI, $bulan_key);
		if ($publish) {
			$source_type = $publish['source_type'];
			$source_table = $publish['source_table'];
		}
	}

	if ($source_type === null || $source_type === '') {
		$source_type = 'asli';
	}

	if ($source_type === 'tabel' && $source_table) {
		$result = jurnal_kas_lap_compute_from_table($CI, $month, $year, $source_table);
	} else {
		$result = jurnal_kas_compute_list_data($CI, $month, $year);
		$result['ok'] = true;
		$result['source_type'] = 'asli';
		$result['source_table'] = null;
		$result['bulan_key'] = $bulan_key;
	}

	if (!empty($result['ok'])) {
		$result['publish_setting'] = $publish ? $publish : jurnal_kas_lap_get_publish_setting($CI, $bulan_key);
	}

	return $result;
}

function jurnal_kas_lap_ajax_preview_response($CI, $month, $year, $source_type, $source_table = null)
{
	$result = jurnal_kas_lap_compute_report_data($CI, $month, $year, $source_type, $source_table, false);
	if (empty($result['ok'])) {
		return array(
			'ok' => false,
			'message' => isset($result['message']) ? $result['message'] : 'Gagal memuat data preview.',
		);
	}

	$bulan_key = isset($result['bulan_key']) ? $result['bulan_key'] : jurnal_kas_lap_normalize_bulan_key($year, $month);
	$publish = jurnal_kas_lap_get_publish_setting($CI, $bulan_key);

	return array(
		'ok' => true,
		'rows' => $result['rows'],
		'TOTAL_debet' => $result['TOTAL_debet'],
		'TOTAL_kredit' => $result['TOTAL_kredit'],
		'SALDO_AKHIR' => $result['SALDO_AKHIR'],
		'bulan_label' => $result['bulan_label'],
		'source_type' => $result['source_type'],
		'source_table' => $result['source_table'],
		'publish_setting' => $publish,
	);
}

function jurnal_kas_lap_format_rows_for_json($rows)
{
	$CI =& get_instance();
	$CI->load->helper('jurnal_kas_list');

	$out = array();
	foreach ((array) $rows as $row) {
		$out[] = array(
			'type' => isset($row['type']) ? $row['type'] : 'data',
			'no' => isset($row['no']) ? $row['no'] : '',
			'sumber' => isset($row['sumber']) ? $row['sumber'] : '',
			'unit' => isset($row['unit']) ? $row['unit'] : '',
			'tanggal' => isset($row['tanggal']) ? $row['tanggal'] : '',
			'bukti' => isset($row['bukti']) ? $row['bukti'] : '',
			'keterangan' => isset($row['keterangan']) ? $row['keterangan'] : '',
			'debet' => ($row['debet'] !== null && $row['debet'] !== '') ? jurnal_kas_format_rupiah($row['debet'], !empty($row['type']) && $row['type'] === 'saldo') : '',
			'kredit' => ($row['kredit'] !== null && $row['kredit'] !== '') ? jurnal_kas_format_rupiah($row['kredit'], !empty($row['type']) && $row['type'] === 'saldo') : '',
			'debet_raw' => isset($row['debet']) ? $row['debet'] : null,
			'kredit_raw' => isset($row['kredit']) ? $row['kredit'] : null,
		);
	}

	return $out;
}

function jurnal_kas_lap_excel_style_map()
{
	return array(
		'title' => 26,
		'header' => 17,
		'data_left' => 18,
		'data_left_alt' => 19,
		'data_right' => 20,
		'data_right_alt' => 21,
		'data_no' => 22,
		'data_no_alt' => 23,
		'summary_label' => 24,
		'summary_amount' => 25,
	);
}

function jurnal_kas_lap_excel_write_merged_row($rowNum, $text, $styleIndex, $colEnd = 5)
{
	xlsAddMerge($rowNum, 0, $rowNum, $colEnd);
	xlsWriteCellStyle($rowNum, 0, $text, $styleIndex);
	for ($c = 1; $c <= $colEnd; $c++) {
		xlsEnsureCellStyle($rowNum, $c, $styleIndex, '');
	}
}

function jurnal_kas_lap_excel_write_data_row($rowNum, $item, $styles, $isAlt = false)
{
	$styleNo = $isAlt ? $styles['data_no_alt'] : $styles['data_no'];
	$styleLeft = $isAlt ? $styles['data_left_alt'] : $styles['data_left'];
	$styleRight = $isAlt ? $styles['data_right_alt'] : $styles['data_right'];

	xlsWriteCellStyle($rowNum, 0, isset($item['no']) ? $item['no'] : '', $styleNo);
	xlsWriteCellStyle($rowNum, 1, isset($item['tanggal']) ? $item['tanggal'] : '', $styleLeft);
	xlsWriteCellStyle($rowNum, 2, isset($item['bukti']) ? $item['bukti'] : '', $styleLeft);
	xlsWriteCellStyle($rowNum, 3, isset($item['keterangan']) ? $item['keterangan'] : '', $styleLeft);

	$isSaldo = (isset($item['type']) && $item['type'] === 'saldo');
	if ($item['debet'] !== null && $item['debet'] !== '') {
		xlsWriteCellStyle($rowNum, 4, jurnal_kas_format_rupiah($item['debet'], $isSaldo), $styleRight);
	} else {
		xlsWriteCellStyle($rowNum, 4, '', $styleRight);
	}

	if ($item['kredit'] !== null && $item['kredit'] !== '') {
		xlsWriteCellStyle($rowNum, 5, jurnal_kas_format_rupiah($item['kredit'], $isSaldo), $styleRight);
	} else {
		xlsWriteCellStyle($rowNum, 5, '', $styleRight);
	}
}

function jurnal_kas_lap_excel_write_footer_row($rowNum, $label, $debet, $kredit, $styles)
{
	xlsAddMerge($rowNum, 0, $rowNum, 3);
	xlsWriteCellStyle($rowNum, 0, $label, $styles['summary_label']);
	for ($c = 1; $c <= 3; $c++) {
		xlsEnsureCellStyle($rowNum, $c, $styles['summary_label'], '');
	}

	if ($debet !== null && $debet !== '') {
		xlsWriteCellStyle($rowNum, 4, jurnal_kas_format_rupiah($debet, true), $styles['summary_amount']);
	} else {
		xlsWriteCellStyle($rowNum, 4, '', $styles['summary_amount']);
	}

	if ($kredit !== null && $kredit !== '') {
		xlsWriteCellStyle($rowNum, 5, jurnal_kas_format_rupiah($kredit, true), $styles['summary_amount']);
	} else {
		xlsWriteCellStyle($rowNum, 5, '', $styles['summary_amount']);
	}
}

function jurnal_kas_lap_export_excel_output($CI, $month, $year)
{
	$data = jurnal_kas_lap_compute_report_data($CI, $month, $year, null, null, true);
	if (empty($data['ok'])) {
		show_error(isset($data['message']) ? $data['message'] : 'Data laporan tidak tersedia.', 400);
		return;
	}

	jurnal_kas_lap_export_excel_from_rows($CI, $data, 'Lap_Buku_Kas');
}

function jurnal_kas_lap_export_excel_from_rows($CI, $data, $title_prefix = 'Lap_Buku_Kas')
{
	@set_time_limit(600);
	$CI->load->helper(array('exportexcel', 'jurnal_kas_list'));

	$month = (int) $data['month'];
	$year = (int) $data['year'];
	$bulan_nama = jurnal_kas_bulan_teks($month);
	$suffix = (!empty($data['source_type']) && $data['source_type'] === 'tabel' && !empty($data['source_table']))
		? '_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $data['source_table'])
		: '';

	$namaFile = $title_prefix . '_' . str_pad((string) $month, 2, '0', STR_PAD_LEFT) . '_' . $year . $suffix . '_' . date('Y-m-d_H-i-s') . '.xlsx';
	excel_prepare_download($namaFile);

	$styles = jurnal_kas_lap_excel_style_map();

	xlsBOF();
	xlsSetColumnWidths(array(8, 16, 14, 52, 20, 20));

	jurnal_kas_lap_excel_write_merged_row(
		0,
		'LAPORAN BUKU KAS — ' . $bulan_nama . ' ' . $year,
		$styles['title'],
		5
	);

	$headerRow = 2;
	$headers = array('NO', 'TANGGAL', 'BUKTI', 'KETERANGAN', 'DEBET', 'KREDIT');
	foreach ($headers as $col => $label) {
		xlsWriteCellStyle($headerRow, $col, $label, $styles['header']);
	}

	$rowNum = 3;
	$dataRowIndex = 0;
	foreach ($data['rows'] as $item) {
		jurnal_kas_lap_excel_write_data_row($rowNum, $item, $styles, ($dataRowIndex % 2) === 1);
		$rowNum++;
		$dataRowIndex++;
	}

	$TOTAL_debet = $data['TOTAL_debet'];
	$TOTAL_kredit = $data['TOTAL_kredit'];
	$SALDO_AKHIR = $data['SALDO_AKHIR'];
	$seimbangVal = ($SALDO_AKHIR >= 0) ? $TOTAL_debet : $SALDO_AKHIR;

	jurnal_kas_lap_excel_write_footer_row($rowNum++, 'JUMLAH DEBET / KREDIT', $TOTAL_debet, $TOTAL_kredit, $styles);
	jurnal_kas_lap_excel_write_footer_row(
		$rowNum++,
		'Saldo akhir Kas Bulan ' . $bulan_nama,
		null,
		$SALDO_AKHIR,
		$styles
	);
	jurnal_kas_lap_excel_write_footer_row(
		$rowNum++,
		'JUMLAH SEIMBANG',
		$seimbangVal,
		$seimbangVal,
		$styles
	);

	xlsEOF();
}
