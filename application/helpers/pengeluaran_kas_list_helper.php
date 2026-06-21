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

	return array(
		'no' => $no,
		'pk' => $pk,
		'tanggal' => pengeluaran_kas_format_tanggal_display(isset($row->tanggal) ? $row->tanggal : ''),
		'nomor_bukti_bkk' => isset($row->nomor_bukti_bkk) ? $row->nomor_bukti_bkk : '',
		'pl' => isset($row->pl) ? $row->pl : '',
		'keterangan' => isset($row->keterangan) ? $row->keterangan : '',
		'debet_21101uu_dagang' => pengeluaran_kas_parse_amount(isset($row->debet_21101uu_dagang) ? $row->debet_21101uu_dagang : 0),
		'serba_serbi_nomor_rekening' => isset($row->serba_serbi_nomor_rekening) ? $row->serba_serbi_nomor_rekening : '',
		'serba_serbi_jumlah' => pengeluaran_kas_parse_amount(isset($row->serba_serbi_jumlah) ? $row->serba_serbi_jumlah : 0),
		'kredit_11101_kas_besar' => pengeluaran_kas_parse_amount(isset($row->kredit_11101_kas_besar) ? $row->kredit_11101_kas_besar : 0),
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

	$data = pengeluaran_kas_build_db_payload_from_post($CI);
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

	$data = pengeluaran_kas_build_db_payload_from_post($CI);
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

function pengeluaran_kas_ajax_list_response($CI, $tgl_awal, $tgl_akhir)
{
	if ($tgl_awal === '' || $tgl_akhir === '') {
		return array('ok' => false, 'message' => 'Tanggal awal dan akhir wajib diisi.');
	}

	if (date('Y', strtotime($tgl_awal)) < 2020) {
		$date_awal = date('Y-m-d', strtotime('-1 day'));
	} else {
		$date_awal = date('Y-m-d 00:00:00', strtotime($tgl_awal));
	}

	if (date('Y', strtotime($tgl_akhir)) < 2020) {
		$date_akhir = date('Y-m-d 00:00:00');
	} else {
		$date_akhir = date('Y-m-d 23:59:59', strtotime($tgl_akhir));
	}

	$list = pengeluaran_kas_compute_list_data($CI, $date_awal, $date_akhir);

	return array(
		'ok' => true,
		'rows' => $list['rows'],
		'totals' => array(
			'debet_21101' => $list['TOTAL_debet_21101_SEMUA'],
			'serba_serbi_jumlah' => $list['TOTAL_serba_serbi_jumlah_SEMUA'],
			'kredit_kas' => $list['TOTAL_kredit_11101_SEMUA'],
		),
		'periode_label' => $list['periode_label'],
		'bulan_label' => $list['bulan_label'],
	);
}

function pengeluaran_kas_export_excel_write_headers()
{
	$styleHeader = 4;
	$headers = array(
		'No', 'Tanggal', 'No. Bukti BKK', 'PL', 'KETERANGAN',
		'21101-UU Dagang', 'No. Rek Serba-Serbi', 'Jumlah Serba-Serbi', '11101-Kas Besar',
	);
	foreach ($headers as $col => $label) {
		xlsWriteCellStyle(4, $col, $label, $styleHeader);
	}
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

	xlsWriteLabelBold14(0, 0, 'JURNAL PENGELUARAN KAS');
	xlsWriteLabelBold14(1, 0, 'Periode: ' . $data['periode_label']);
	xlsWriteLabel(2, 0, 'Bulan/Tahun referensi: ' . $data['bulan_label'] . ' | Dicetak: ' . date('d/m/Y H:i:s'));

	pengeluaran_kas_export_excel_write_headers();

	$rowNum = 5;
	foreach ($data['rows'] as $item) {
		pengeluaran_kas_export_excel_write_row($rowNum, $item);
		$rowNum++;
	}

	$styleFooter = 5;
	xlsWriteCellStyle($rowNum, 0, '', $styleFooter);
	xlsWriteCellStyle($rowNum, 1, '', $styleFooter);
	xlsWriteCellStyle($rowNum, 2, '', $styleFooter);
	xlsWriteCellStyle($rowNum, 3, '', $styleFooter);
	xlsWriteCellStyle($rowNum, 4, 'TOTAL', $styleFooter);
	xlsWriteCellStyle($rowNum, 5, pengeluaran_kas_format_rupiah($data['TOTAL_debet_21101_SEMUA'], true), $styleFooter);
	xlsWriteCellStyle($rowNum, 6, '', $styleFooter);
	xlsWriteCellStyle($rowNum, 7, pengeluaran_kas_format_rupiah($data['TOTAL_serba_serbi_jumlah_SEMUA'], true), $styleFooter);
	xlsWriteCellStyle($rowNum, 8, pengeluaran_kas_format_rupiah($data['TOTAL_kredit_11101_SEMUA'], true), $styleFooter);

	xlsEOF();
}
