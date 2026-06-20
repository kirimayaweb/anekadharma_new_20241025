<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function pengeluaran_kas_compare_online_table()
{
	return 'jurnal_pengeluaran_kas';
}

function pengeluaran_kas_compare_field_definitions()
{
	return array(
		'tanggal' => array(
			'label' => 'tanggal',
			'required' => true,
			'aliases' => array('tanggal', 'tgl', 'date', 'tgl_transaksi'),
		),
		'nomor_bukti_bkk' => array(
			'label' => 'nomor_bukti_bkk',
			'required' => true,
			'aliases' => array('nomor_bukti_bkk', 'no_bukti_bkk', 'nobukti_bkk', 'bukti_bkk', 'no bukti bkk', 'bukti'),
		),
		'pl' => array(
			'label' => 'pl',
			'required' => true,
			'aliases' => array('pl', 'kode_pl', 'profit_center', 'kode pl'),
		),
		'keterangan' => array(
			'label' => 'keterangan',
			'required' => true,
			'aliases' => array('keterangan', 'ket', 'uraian', 'deskripsi'),
		),
		'debet_21101uu_dagang' => array(
			'label' => 'debet_21101uu_dagang',
			'required' => false,
			'aliases' => array('debet_21101uu_dagang', 'debet_21101', '21101_uu_dagang', '21101', 'debet uu dagang'),
		),
		'serba_serbi_nomor_rekening' => array(
			'label' => 'serba_serbi_nomor_rekening',
			'required' => false,
			'aliases' => array(
				'serba-serbi_nomor_rekening',
				'serba_serbi_nomor_rekening',
				'serba_serbi_nomor_rekening',
				'nomor_rekening',
				'no_rek',
				'no rek',
				'rekening',
			),
		),
		'serba_serbi_jumlah' => array(
			'label' => 'serba_serbi_jumlah',
			'required' => false,
			'aliases' => array('serba_serbi_jumlah', 'serba_serbi_jumlah', 'jumlah', 'serba serbi jumlah'),
		),
		'kredit_11101_kas_besar' => array(
			'label' => 'kredit_11101_kas_besar',
			'required' => false,
			'aliases' => array('kredit_11101_kas_besar', 'kredit_11101', '11101_kas_besar', '11101', 'kas_besar', 'kredit kas besar'),
		),
	);
}

function pengeluaran_kas_compare_build_column_map($fields)
{
	$analysis = pengeluaran_kas_compare_analyze_column_map($fields);
	return !empty($analysis['ok']) ? $analysis['map'] : null;
}

function pengeluaran_kas_compare_analyze_column_map($fields)
{
	if (!is_array($fields) || count($fields) === 0) {
		return array('ok' => false, 'message' => 'Tabel tidak memiliki kolom.');
	}

	$normalized = array();
	foreach ($fields as $field) {
		$normalized[] = trim((string) $field);
	}

	$defs = pengeluaran_kas_compare_field_definitions();
	$map = array();
	foreach ($defs as $key => $def) {
		$map[$key] = persediaan_compare_pick_column($normalized, $def['aliases']);
	}

	$missing_required = array();
	foreach ($defs as $key => $def) {
		if (empty($map[$key]) && !empty($def['required'])) {
			$missing_required[] = $def['label'];
		}
	}

	$amount_missing = empty($map['debet_21101uu_dagang'])
		&& empty($map['serba_serbi_jumlah'])
		&& empty($map['kredit_11101_kas_besar']);
	if ($amount_missing) {
		$missing_required[] = 'debet_21101uu_dagang / serba_serbi_jumlah / kredit_11101_kas_besar';
	}

	$ok = count($missing_required) === 0;
	$message = '';
	if (!$ok) {
		$message = 'Kolom wajib tidak ditemukan: ' . implode(', ', $missing_required)
			. '. Kolom compare: tanggal, nomor_bukti_bkk, pl, keterangan, debet_21101uu_dagang, serba_serbi_nomor_rekening, serba_serbi_jumlah, kredit_11101_kas_besar.';
	}

	$mapped = array();
	foreach ($map as $key => $col) {
		if ($col !== null && $col !== '') {
			$mapped[$key] = $col;
		}
	}

	return array(
		'ok' => $ok,
		'map' => $map,
		'mapped' => $mapped,
		'missing_required' => $missing_required,
		'fields' => $normalized,
		'message' => $message,
	);
}

function pengeluaran_kas_compare_validate_online_table_detail($CI)
{
	$table = pengeluaran_kas_compare_online_table();
	if (!$CI->db->table_exists($table)) {
		return array(
			'ok' => false,
			'message' => 'Tabel online `' . $table . '` tidak ditemukan di database.',
			'missing_fields' => array($table . ' (tabel)'),
		);
	}

	$fields = $CI->db->list_fields($table);
	$analysis = pengeluaran_kas_compare_analyze_column_map($fields);
	return array(
		'ok' => !empty($analysis['ok']),
		'table' => $table,
		'map' => isset($analysis['map']) ? $analysis['map'] : array(),
		'mapped' => isset($analysis['mapped']) ? $analysis['mapped'] : array(),
		'missing_fields' => isset($analysis['missing_required']) ? $analysis['missing_required'] : array(),
		'fields' => $fields,
		'message' => isset($analysis['message']) ? $analysis['message'] : '',
	);
}

function pengeluaran_kas_compare_validate_table($CI, $table)
{
	if (!persediaan_compare_is_valid_table_name($table)) {
		return array('ok' => false, 'message' => 'Nama tabel tidak valid.');
	}
	if (!$CI->db->table_exists($table)) {
		return array('ok' => false, 'message' => 'Tabel tidak ditemukan di database.');
	}

	$fields = $CI->db->list_fields($table);
	$analysis = pengeluaran_kas_compare_analyze_column_map($fields);
	if (empty($analysis['ok'])) {
		return array(
			'ok' => false,
			'message' => isset($analysis['message']) ? $analysis['message'] : 'Struktur tabel manual tidak valid.',
			'missing_fields' => isset($analysis['missing_required']) ? $analysis['missing_required'] : array(),
			'fields' => $fields,
		);
	}

	return array(
		'ok' => true,
		'map' => $analysis['map'],
		'fields' => $fields,
		'mapped' => $analysis['mapped'],
		'missing_fields' => array(),
	);
}

function pengeluaran_kas_compare_normalize_tanggal_for_db($value, $ref_month = 0, $ref_year = 0)
{
	return jurnal_kas_compare_normalize_tanggal_for_db($value, $ref_month, $ref_year);
}

function pengeluaran_kas_compare_row_matches_bulan($tanggal_ymd, $bulan)
{
	return jurnal_kas_compare_row_matches_bulan($tanggal_ymd, $bulan);
}

function pengeluaran_kas_compare_normalize_bukti($bukti)
{
	return strtoupper(trim((string) $bukti));
}

function pengeluaran_kas_compare_normalize_pl($pl)
{
	return strtolower(trim((string) $pl));
}

function pengeluaran_kas_compare_normalize_keterangan($keterangan)
{
	return strtolower(trim(preg_replace('/\s+/', ' ', (string) $keterangan)));
}

function pengeluaran_kas_compare_normalize_text($value)
{
	return strtolower(trim((string) $value));
}

function pengeluaran_kas_compare_normalize_jumlah($value)
{
	return (int) round(pembelian_jurnal_compare_parse_jumlah_nominal($value));
}

function pengeluaran_kas_compare_format_jumlah_display($jumlah)
{
	return number_format((int) $jumlah, 0, ',', '.');
}

function pengeluaran_kas_compare_standard_headers()
{
	return array(
		'No', 'Tanggal', 'No Bukti BKK', 'PL', 'Keterangan',
		'Debet 21101', 'No Rek', 'Jumlah Serbi', 'Kredit Kas Besar', 'Catatan',
	);
}

function pengeluaran_kas_compare_is_row_analyzable($row)
{
	$tanggal = pembelian_jurnal_compare_normalize_tanggal(isset($row['tanggal']) ? $row['tanggal'] : '');
	if ($tanggal === '' || $tanggal === '0000-00-00') {
		return false;
	}
	$deb = pengeluaran_kas_compare_normalize_jumlah(isset($row['debet_21101uu_dagang']) ? $row['debet_21101uu_dagang'] : 0);
	$jml = pengeluaran_kas_compare_normalize_jumlah(isset($row['serba_serbi_jumlah']) ? $row['serba_serbi_jumlah'] : 0);
	$kre = pengeluaran_kas_compare_normalize_jumlah(isset($row['kredit_11101_kas_besar']) ? $row['kredit_11101_kas_besar'] : 0);
	return ($deb > 0 || $jml > 0 || $kre > 0);
}

function pengeluaran_kas_compare_row_unprocessed_reasons($row)
{
	$reasons = array();
	if (pembelian_jurnal_compare_normalize_tanggal(isset($row['tanggal']) ? $row['tanggal'] : '') === '') {
		$reasons[] = 'tanggal kosong/tidak valid';
	}
	if (pengeluaran_kas_compare_normalize_bukti(isset($row['nomor_bukti_bkk']) ? $row['nomor_bukti_bkk'] : '') === '') {
		$reasons[] = 'nomor_bukti_bkk kosong';
	}
	if (pengeluaran_kas_compare_normalize_pl(isset($row['pl']) ? $row['pl'] : '') === '') {
		$reasons[] = 'pl kosong';
	}
	if (trim((string) (isset($row['keterangan']) ? $row['keterangan'] : '')) === '') {
		$reasons[] = 'keterangan kosong';
	}
	if (!pengeluaran_kas_compare_is_row_analyzable($row)) {
		$reasons[] = 'semua nominal kosong';
	}
	return $reasons;
}

function pengeluaran_kas_compare_make_full_key($row)
{
	return pembelian_jurnal_compare_normalize_tanggal(isset($row['tanggal']) ? $row['tanggal'] : '')
		. '|' . pengeluaran_kas_compare_normalize_bukti(isset($row['nomor_bukti_bkk']) ? $row['nomor_bukti_bkk'] : '')
		. '|' . pengeluaran_kas_compare_normalize_pl(isset($row['pl']) ? $row['pl'] : '')
		. '|' . pengeluaran_kas_compare_normalize_keterangan(isset($row['keterangan']) ? $row['keterangan'] : '')
		. '|' . pengeluaran_kas_compare_normalize_jumlah(isset($row['debet_21101uu_dagang']) ? $row['debet_21101uu_dagang'] : 0)
		. '|' . pengeluaran_kas_compare_normalize_text(isset($row['serba_serbi_nomor_rekening']) ? $row['serba_serbi_nomor_rekening'] : '')
		. '|' . pengeluaran_kas_compare_normalize_jumlah(isset($row['serba_serbi_jumlah']) ? $row['serba_serbi_jumlah'] : 0)
		. '|' . pengeluaran_kas_compare_normalize_jumlah(isset($row['kredit_11101_kas_besar']) ? $row['kredit_11101_kas_besar'] : 0);
}

function pengeluaran_kas_compare_row_to_display($row, $catatan = '')
{
	$deb = pengeluaran_kas_compare_normalize_jumlah(isset($row['debet_21101uu_dagang']) ? $row['debet_21101uu_dagang'] : 0);
	$jml = pengeluaran_kas_compare_normalize_jumlah(isset($row['serba_serbi_jumlah']) ? $row['serba_serbi_jumlah'] : 0);
	$kre = pengeluaran_kas_compare_normalize_jumlah(isset($row['kredit_11101_kas_besar']) ? $row['kredit_11101_kas_besar'] : 0);

	return array(
		'tanggal' => isset($row['tanggal']) ? pembelian_jurnal_compare_format_tanggal_display($row['tanggal']) : '',
		'nomor_bukti_bkk' => isset($row['nomor_bukti_bkk']) ? $row['nomor_bukti_bkk'] : '',
		'pl' => isset($row['pl']) ? $row['pl'] : '',
		'keterangan' => isset($row['keterangan']) ? $row['keterangan'] : '',
		'debet_21101uu_dagang' => $deb > 0 ? pengeluaran_kas_compare_format_jumlah_display($deb) : '',
		'serba_serbi_nomor_rekening' => isset($row['serba_serbi_nomor_rekening']) ? $row['serba_serbi_nomor_rekening'] : '',
		'serba_serbi_jumlah' => $jml > 0 ? pengeluaran_kas_compare_format_jumlah_display($jml) : '',
		'kredit_11101_kas_besar' => $kre > 0 ? pengeluaran_kas_compare_format_jumlah_display($kre) : '',
		'catatan' => $catatan,
	);
}

function pengeluaran_kas_compare_get_row_value($row, $col)
{
	if ($col === null || $col === '') {
		return '';
	}
	return persediaan_compare_row_get($row, $col);
}

function pengeluaran_kas_compare_manual_row_from_db($row, $map, $default_tanggal = '')
{
	$tanggal_raw = !empty($map['tanggal']) ? pengeluaran_kas_compare_get_row_value($row, $map['tanggal']) : $default_tanggal;
	$tanggal_norm = pembelian_jurnal_compare_normalize_tanggal($tanggal_raw);
	if (($tanggal_norm === '' || $tanggal_norm === '0000-00-00') && $default_tanggal !== '') {
		$tanggal_norm = pembelian_jurnal_compare_normalize_tanggal($default_tanggal);
	}

	return array(
		'tanggal' => $tanggal_norm,
		'nomor_bukti_bkk' => trim((string) pengeluaran_kas_compare_get_row_value($row, !empty($map['nomor_bukti_bkk']) ? $map['nomor_bukti_bkk'] : '')),
		'pl' => trim((string) pengeluaran_kas_compare_get_row_value($row, !empty($map['pl']) ? $map['pl'] : '')),
		'keterangan' => trim((string) pengeluaran_kas_compare_get_row_value($row, !empty($map['keterangan']) ? $map['keterangan'] : '')),
		'debet_21101uu_dagang' => pengeluaran_kas_compare_normalize_jumlah(pengeluaran_kas_compare_get_row_value($row, !empty($map['debet_21101uu_dagang']) ? $map['debet_21101uu_dagang'] : '')),
		'serba_serbi_nomor_rekening' => trim((string) pengeluaran_kas_compare_get_row_value($row, !empty($map['serba_serbi_nomor_rekening']) ? $map['serba_serbi_nomor_rekening'] : '')),
		'serba_serbi_jumlah' => pengeluaran_kas_compare_normalize_jumlah(pengeluaran_kas_compare_get_row_value($row, !empty($map['serba_serbi_jumlah']) ? $map['serba_serbi_jumlah'] : '')),
		'kredit_11101_kas_besar' => pengeluaran_kas_compare_normalize_jumlah(pengeluaran_kas_compare_get_row_value($row, !empty($map['kredit_11101_kas_besar']) ? $map['kredit_11101_kas_besar'] : '')),
	);
}

function pengeluaran_kas_compare_load_manual_rows_for_bulan($CI, $table, $bulan)
{
	$valid = pengeluaran_kas_compare_validate_table($CI, $table);
	if (empty($valid['ok'])) {
		return $valid;
	}

	$range = persediaan_compare_bulan_to_date_range($bulan);
	if ($range === null) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');
	}

	$sql = 'SELECT * FROM `' . $table . '` ORDER BY id ASC';
	$all_rows = $CI->db->query($sql)->result();
	$default_tanggal = $range['tgl_akhir'];
	$filtered_rows = array();
	$warnings = array();

	foreach ((array) $all_rows as $row) {
		$item = pengeluaran_kas_compare_manual_row_from_db($row, $valid['map'], $default_tanggal);
		if (pengeluaran_kas_compare_row_matches_bulan($item['tanggal'], $bulan)) {
			$filtered_rows[] = $row;
		}
	}

	if (count($filtered_rows) === 0 && count($all_rows) > 0) {
		$filtered_rows = $all_rows;
		$warnings[] = 'Tidak ada baris manual dengan tanggal pada bulan ' . $range['bulan_label']
			. ' — menampilkan semua ' . count($all_rows) . ' baris dari tabel `' . $table . '`.';
	}

	return array(
		'ok' => true,
		'map' => $valid['map'],
		'rows' => $filtered_rows,
		'all_rows' => $all_rows,
		'range' => $range,
		'warnings' => $warnings,
		'stats' => array(
			'raw_total' => count($all_rows),
			'filtered_total' => count($filtered_rows),
		),
	);
}

function pengeluaran_kas_compare_load_manual_all($CI, $table, $bulan)
{
	$valid = pengeluaran_kas_compare_validate_table($CI, $table);
	if (empty($valid['ok'])) {
		return $valid;
	}

	$loaded = pengeluaran_kas_compare_load_manual_rows_for_bulan($CI, $table, $bulan);
	if (empty($loaded['ok'])) {
		return $loaded;
	}

	$map = $loaded['map'];
	$default_tanggal = $loaded['range']['tgl_akhir'];
	$list = array();
	$display_all = array();

	foreach ($loaded['rows'] as $row) {
		$item = pengeluaran_kas_compare_manual_row_from_db($row, $map, $default_tanggal);
		$reasons = pengeluaran_kas_compare_row_unprocessed_reasons($item);
		$display_all[] = pengeluaran_kas_compare_row_to_display(
			$item,
			count($reasons) > 0 ? ('Info: ' . implode(', ', $reasons)) : ''
		);
		if (pengeluaran_kas_compare_is_row_analyzable($item)) {
			$item['_full_key'] = pengeluaran_kas_compare_make_full_key($item);
			$list[] = $item;
		}
	}

	return array(
		'ok' => true,
		'list' => $list,
		'display_list' => $display_all,
		'warnings' => isset($loaded['warnings']) ? $loaded['warnings'] : array(),
		'stats' => array(
			'raw_total' => isset($loaded['stats']['raw_total']) ? (int) $loaded['stats']['raw_total'] : count($loaded['rows']),
			'filtered_total' => count($loaded['rows']),
			'processed' => count($list),
		),
		'table' => $table,
		'range' => $loaded['range'],
		'mapped' => isset($valid['mapped']) ? $valid['mapped'] : array(),
	);
}

function pengeluaran_kas_compare_online_row_from_db($row, $map)
{
	return array(
		'tanggal' => pembelian_jurnal_compare_normalize_tanggal(isset($row->tanggal) ? $row->tanggal : ''),
		'nomor_bukti_bkk' => trim((string) pengeluaran_kas_compare_get_row_value($row, !empty($map['nomor_bukti_bkk']) ? $map['nomor_bukti_bkk'] : '')),
		'pl' => trim((string) pengeluaran_kas_compare_get_row_value($row, !empty($map['pl']) ? $map['pl'] : '')),
		'keterangan' => trim((string) pengeluaran_kas_compare_get_row_value($row, !empty($map['keterangan']) ? $map['keterangan'] : '')),
		'debet_21101uu_dagang' => pengeluaran_kas_compare_normalize_jumlah(pengeluaran_kas_compare_get_row_value($row, !empty($map['debet_21101uu_dagang']) ? $map['debet_21101uu_dagang'] : '')),
		'serba_serbi_nomor_rekening' => trim((string) pengeluaran_kas_compare_get_row_value($row, !empty($map['serba_serbi_nomor_rekening']) ? $map['serba_serbi_nomor_rekening'] : '')),
		'serba_serbi_jumlah' => pengeluaran_kas_compare_normalize_jumlah(pengeluaran_kas_compare_get_row_value($row, !empty($map['serba_serbi_jumlah']) ? $map['serba_serbi_jumlah'] : '')),
		'kredit_11101_kas_besar' => pengeluaran_kas_compare_normalize_jumlah(pengeluaran_kas_compare_get_row_value($row, !empty($map['kredit_11101_kas_besar']) ? $map['kredit_11101_kas_besar'] : '')),
	);
}

function pengeluaran_kas_compare_load_online_all($CI, $bulan)
{
	$range = persediaan_compare_bulan_to_date_range($bulan);
	if ($range === null) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');
	}

	$online_fields = pengeluaran_kas_compare_validate_online_table_detail($CI);
	if (empty($online_fields['ok'])) {
		return array(
			'ok' => false,
			'message' => isset($online_fields['message']) ? $online_fields['message'] : 'Struktur tabel online tidak valid.',
			'field_validation' => array('online' => $online_fields),
		);
	}

	$table = pengeluaran_kas_compare_online_table();
	$month = (int) substr($bulan, 5, 2);
	$year = (int) substr($bulan, 0, 4);
	$map = $online_fields['map'];

	$sql = 'SELECT * FROM `' . $table . '`
		WHERE tanggal IS NOT NULL AND tanggal <> \'0000-00-00\'
		AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?
		ORDER BY tanggal, nomor';

	$list = array();
	$display_all = array();

	foreach ($CI->db->query($sql, array($month, $year))->result() as $row) {
		$item = pengeluaran_kas_compare_online_row_from_db($row, $map);
		$reasons = pengeluaran_kas_compare_row_unprocessed_reasons($item);
		$display_all[] = pengeluaran_kas_compare_row_to_display(
			$item,
			count($reasons) > 0 ? ('Info: ' . implode(', ', $reasons)) : ''
		);
		if (pengeluaran_kas_compare_is_row_analyzable($item)) {
			$item['_full_key'] = pengeluaran_kas_compare_make_full_key($item);
			$list[] = $item;
		}
	}

	return array(
		'ok' => true,
		'list' => $list,
		'display_list' => $display_all,
		'stats' => array(
			'raw_total' => count($display_all),
			'processed' => count($list),
		),
		'range' => $range,
	);
}

function pengeluaran_kas_compare_sort_display_rows($a, $b)
{
	$cmp = strcmp((string) $a['tanggal'], (string) $b['tanggal']);
	if ($cmp !== 0) {
		return $cmp;
	}
	$cmp = strcmp((string) $a['nomor_bukti_bkk'], (string) $b['nomor_bukti_bkk']);
	if ($cmp !== 0) {
		return $cmp;
	}
	return strcmp((string) $a['pl'], (string) $b['pl']);
}

function pengeluaran_kas_compare_run($CI, $bulan, $table = '')
{
	$table = trim((string) $table);
	if ($table === '') {
		return array('ok' => false, 'message' => 'Pilih tabel manual yang akan dibandingkan.');
	}

	$online_fields = pengeluaran_kas_compare_validate_online_table_detail($CI);
	if (empty($online_fields['ok'])) {
		return array(
			'ok' => false,
			'message' => isset($online_fields['message']) ? $online_fields['message'] : 'Struktur tabel online tidak valid.',
			'field_validation' => array('online' => $online_fields),
		);
	}

	$manual = pengeluaran_kas_compare_load_manual_all($CI, $table, $bulan);
	if (empty($manual['ok'])) {
		return $manual;
	}

	$online = pengeluaran_kas_compare_load_online_all($CI, $bulan);
	if (empty($online['ok'])) {
		return $online;
	}

	$warnings = isset($manual['warnings']) ? $manual['warnings'] : array();
	if ((int) $manual['stats']['raw_total'] === 0) {
		return array(
			'ok' => false,
			'message' => 'Tabel manual `' . $table . '` tidak memiliki baris data.',
		);
	}

	$data_cocok = array();
	$manual_tidak = array();
	$online_used = array();

	$online_pool = array();
	foreach ($online['list'] as $idx => $item) {
		$online_pool[$idx] = $item;
	}

	foreach ($manual['list'] as $manual_row) {
		$matched_idx = null;
		foreach ($online_pool as $o_idx => $online_row) {
			if (!empty($online_used[$o_idx])) {
				continue;
			}
			if ($manual_row['_full_key'] === $online_row['_full_key']) {
				$matched_idx = $o_idx;
				break;
			}
		}

		if ($matched_idx !== null) {
			$online_used[$matched_idx] = true;
			$data_cocok[] = pengeluaran_kas_compare_row_to_display($manual_row, 'Data manual dan online cocok (semua field sama)');
		} else {
			$manual_tidak[] = pengeluaran_kas_compare_row_to_display(
				$manual_row,
				'Tidak ditemukan pasangan identik di jurnal_pengeluaran_kas online.'
			);
		}
	}

	$online_tidak = array();
	foreach ($online_pool as $o_idx => $online_row) {
		if (empty($online_used[$o_idx])) {
			$online_tidak[] = pengeluaran_kas_compare_row_to_display(
				$online_row,
				'Tidak ditemukan pasangan identik di tabel manual terpilih.'
			);
		}
	}

	usort($data_cocok, 'pengeluaran_kas_compare_sort_display_rows');
	usort($manual_tidak, 'pengeluaran_kas_compare_sort_display_rows');
	usort($online_tidak, 'pengeluaran_kas_compare_sort_display_rows');

	return array(
		'ok' => true,
		'bulan' => $bulan,
		'bulan_label' => pembelian_jurnal_compare_bulan_label($bulan),
		'table' => $table,
		'data_manual' => $manual['display_list'],
		'data_online' => $online['display_list'],
		'data_cocok' => $data_cocok,
		'manual_tidak_di_online' => $manual_tidak,
		'online_tidak_di_manual' => $online_tidak,
		'warnings' => $warnings,
		'field_validation' => array(
			'manual' => array(
				'ok' => true,
				'table' => $table,
				'mapped' => isset($manual['mapped']) ? $manual['mapped'] : array(),
			),
			'online' => $online_fields,
		),
		'stats' => array(
			'data_manual' => count($manual['display_list']),
			'data_online' => count($online['display_list']),
			'data_cocok' => count($data_cocok),
			'manual_tidak_di_online' => count($manual_tidak),
			'online_tidak_di_manual' => count($online_tidak),
		),
	);
}

function pengeluaran_kas_compare_item_to_row_cells($item, $no)
{
	return array(
		$no,
		isset($item['tanggal']) ? $item['tanggal'] : '',
		isset($item['nomor_bukti_bkk']) ? $item['nomor_bukti_bkk'] : '',
		isset($item['pl']) ? $item['pl'] : '',
		isset($item['keterangan']) ? $item['keterangan'] : '',
		isset($item['debet_21101uu_dagang']) ? $item['debet_21101uu_dagang'] : '',
		isset($item['serba_serbi_nomor_rekening']) ? $item['serba_serbi_nomor_rekening'] : '',
		isset($item['serba_serbi_jumlah']) ? $item['serba_serbi_jumlah'] : '',
		isset($item['kredit_11101_kas_besar']) ? $item['kredit_11101_kas_besar'] : '',
		isset($item['catatan']) ? $item['catatan'] : '',
	);
}

function pengeluaran_kas_compare_excel_all_total_cols()
{
	return 54;
}

function pengeluaran_kas_compare_excel_all_group_definitions()
{
	return array(
		array('title' => '1. DATA MANUAL', 'col_start' => 0, 'col_end' => 9),
		array('title' => '2. DATA ONLINE', 'col_start' => 11, 'col_end' => 20),
		array('title' => '3. DATA COCOK', 'col_start' => 22, 'col_end' => 31),
		array('title' => '4. MANUAL TIDAK DI ONLINE', 'col_start' => 33, 'col_end' => 42),
		array('title' => '5. ONLINE TIDAK DI MANUAL', 'col_start' => 44, 'col_end' => 53),
	);
}

function pengeluaran_kas_compare_excel_all_headers()
{
	$block = pengeluaran_kas_compare_standard_headers();
	return array_merge($block, array(''), $block, array(''), $block, array(''), $block, array(''), $block);
}

function pengeluaran_kas_compare_excel_sheet_name_from_bulan($bulan)
{
	$bulan = trim((string) $bulan);
	if (!preg_match('/^(\d{4})-(\d{2})$/', $bulan, $m)) {
		return 'Compare Pengeluaran Kas';
	}
	$tahun = (int) $m[1];
	$bulan_num = (int) $m[2];
	$nama_bulan = persediaan_compare_excel_all_bulan_nama_id();
	$label_bulan = isset($nama_bulan[$bulan_num]) ? $nama_bulan[$bulan_num] : ('Bulan ' . $bulan_num);
	return excel_sheet_safe_name('Compare Pengeluaran Kas ' . $label_bulan . ' ' . $tahun);
}

function pengeluaran_kas_compare_excel_items_to_cells($items)
{
	$rows = array();
	$no = 0;
	foreach ((array) $items as $item) {
		$no++;
		$rows[] = pengeluaran_kas_compare_item_to_row_cells($item, $no);
	}
	return $rows;
}

function pengeluaran_kas_compare_excel_all_empty_row()
{
	return array_fill(0, pengeluaran_kas_compare_excel_all_total_cols(), '');
}

function pengeluaran_kas_compare_excel_all_write_merged_rows($rowNum, $sections, $groups)
{
	$maxLen = 0;
	foreach ($sections as $sectionRows) {
		$maxLen = max($maxLen, count((array) $sectionRows));
	}
	for ($i = 0; $i < $maxLen; $i++) {
		$cells = pengeluaran_kas_compare_excel_all_empty_row();
		foreach ($sections as $colStart => $sectionRows) {
			if (!isset($sectionRows[$i])) {
				continue;
			}
			foreach ($sectionRows[$i] as $offset => $value) {
				$cells[(int) $colStart + (int) $offset] = $value;
			}
		}
		persediaan_rekonsiliasi_tx_write_row_grouped($rowNum, $cells, $groups);
		$rowNum++;
	}
	return ($maxLen > 0) ? ($rowNum - 1) : ($rowNum);
}

function pengeluaran_kas_compare_export_excel_all_output($CI, $bulan, $table = '', $result = null)
{
	@set_time_limit(600);
	@ini_set('memory_limit', '512M');
	if ($result === null) {
		$result = pengeluaran_kas_compare_run($CI, $bulan, $table);
	}
	if (empty($result['ok'])) {
		xlsAddSheet('Compare Pengeluaran Kas');
		xlsWriteLabel(0, 0, isset($result['message']) ? $result['message'] : 'Export Excel gagal.');
		xlsMultiEOF();
		return;
	}

	$groups = pengeluaran_kas_compare_excel_all_group_definitions();
	$headers = pengeluaran_kas_compare_excel_all_headers();
	$groupTitleRow = 2;
	$headerRow = 3;
	$dataStartRow = 4;
	$stats = isset($result['stats']) ? $result['stats'] : array();

	xlsAddSheet(pengeluaran_kas_compare_excel_sheet_name_from_bulan($bulan));
	xlsWriteLabelBold14(0, 0, 'Compare Jurnal Pengeluaran Kas — Manual vs Online — Bulan ' . $result['bulan_label']);
	xlsWriteLabel(1, 0, 'Tabel manual: ' . $result['table']
		. ' | Dicetak: ' . date('d/m/Y H:i:s')
		. ' | Manual: ' . (isset($stats['data_manual']) ? (int) $stats['data_manual'] : 0)
		. ' | Online: ' . (isset($stats['data_online']) ? (int) $stats['data_online'] : 0)
		. ' | Cocok: ' . (isset($stats['data_cocok']) ? (int) $stats['data_cocok'] : 0)
		. ' | Manual tidak di online: ' . (isset($stats['manual_tidak_di_online']) ? (int) $stats['manual_tidak_di_online'] : 0)
		. ' | Online tidak di manual: ' . (isset($stats['online_tidak_di_manual']) ? (int) $stats['online_tidak_di_manual'] : 0));

	persediaan_rekonsiliasi_tx_write_group_titles($groupTitleRow, $groups);
	persediaan_rekonsiliasi_tx_write_headers_grouped($headerRow, $headers, $groups);

	$sections = array(
		0 => pengeluaran_kas_compare_excel_items_to_cells(isset($result['data_manual']) ? $result['data_manual'] : array()),
		11 => pengeluaran_kas_compare_excel_items_to_cells(isset($result['data_online']) ? $result['data_online'] : array()),
		22 => pengeluaran_kas_compare_excel_items_to_cells(isset($result['data_cocok']) ? $result['data_cocok'] : array()),
		33 => pengeluaran_kas_compare_excel_items_to_cells(isset($result['manual_tidak_di_online']) ? $result['manual_tidak_di_online'] : array()),
		44 => pengeluaran_kas_compare_excel_items_to_cells(isset($result['online_tidak_di_manual']) ? $result['online_tidak_di_manual'] : array()),
	);

	$lastRow = pengeluaran_kas_compare_excel_all_write_merged_rows($dataStartRow, $sections, $groups);
	if ($lastRow < $dataStartRow) {
		$lastRow = $headerRow;
	}
	persediaan_rekonsiliasi_tx_finalize_group_borders($groupTitleRow, $lastRow, $groups);
	xlsMultiEOF();
}

function pengeluaran_kas_compare_export_excel_output($CI, $bulan, $table = '')
{
	@set_time_limit(600);
	@ini_set('memory_limit', '512M');
	$CI->load->helper(array('exportexcel', 'persediaan_display', 'pembelian_persediaan'));

	$result = pengeluaran_kas_compare_run($CI, $bulan, $table);
	if (empty($result['ok'])) {
		xlsBOF();
		xlsWriteLabel(0, 0, isset($result['message']) ? $result['message'] : 'Compare gagal.');
		xlsEOF();
		return;
	}

	pengeluaran_kas_compare_export_excel_all_output($CI, $bulan, $table, $result);
}

function pengeluaran_kas_compare_csv_column_error_detail($raw_headers)
{
	$analysis = pengeluaran_kas_compare_analyze_column_map(
		is_array($raw_headers) ? persediaan_compare_sanitize_csv_headers($raw_headers) : array()
	);
	if (!empty($analysis['message'])) {
		return $analysis['message'];
	}

	$lines = array('Header CSV tidak memenuhi syarat Jurnal Pengeluaran Kas.');
	$lines[] = 'Kolom wajib: tanggal, nomor_bukti_bkk, pl, keterangan, dan minimal satu nominal (debet_21101uu_dagang / serba_serbi_jumlah / kredit_11101_kas_besar).';
	if (is_array($raw_headers) && count($raw_headers) > 0) {
		$lines[] = 'Header terdeteksi: ' . implode(', ', $raw_headers);
	}
	return implode("\n", $lines);
}

function pengeluaran_kas_compare_validate_csv_file($filepath)
{
	if (!is_readable($filepath)) {
		return array('ok' => false, 'stage' => 'read_file', 'message' => 'File CSV tidak dapat dibaca.');
	}

	$handle = persediaan_compare_csv_open_handle($filepath);
	if (!$handle) {
		return array('ok' => false, 'stage' => 'open_file', 'message' => 'Gagal membuka file CSV.');
	}

	$raw_headers = persediaan_compare_csv_read_header($handle);
	fclose($handle);

	if ($raw_headers === null) {
		return array('ok' => false, 'stage' => 'read_header', 'message' => 'File CSV kosong atau baris header tidak valid.');
	}

	$map = pengeluaran_kas_compare_build_column_map(persediaan_compare_sanitize_csv_headers($raw_headers));
	if ($map === null) {
		return array(
			'ok' => false,
			'stage' => 'validate_columns',
			'message' => pengeluaran_kas_compare_csv_column_error_detail($raw_headers),
		);
	}

	return array('ok' => true, 'map' => $map, 'headers' => $raw_headers);
}

function pengeluaran_kas_compare_import_csv_to_db($CI, $filepath, $original_filename, $bulan_key = '', $bulan_num = 0, $tahun = 0)
{
	$file_label = trim((string) $original_filename);
	if ($file_label === '') {
		$file_label = basename((string) $filepath);
	}

	$validated = pengeluaran_kas_compare_validate_csv_file($filepath);
	if (empty($validated['ok'])) {
		$validated['file'] = $file_label;
		return $validated;
	}

	if (!is_readable($filepath)) {
		return array('ok' => false, 'stage' => 'read_file', 'message' => "File `{$file_label}` tidak dapat dibaca dari server.");
	}

	$handle = persediaan_compare_csv_open_handle($filepath);
	if (!$handle) {
		return array('ok' => false, 'stage' => 'open_file', 'message' => "Gagal membuka file CSV `{$file_label}`.");
	}

	$raw_headers = persediaan_compare_csv_read_header($handle);
	if ($raw_headers === null) {
		fclose($handle);
		return array('ok' => false, 'stage' => 'read_header', 'message' => "File `{$file_label}` kosong atau baris header CSV tidak valid.");
	}

	$db_columns_sanitized = persediaan_compare_sanitize_csv_headers($raw_headers);
	$column_map = pengeluaran_kas_compare_build_column_map($db_columns_sanitized);
	$csv_has_id = penjualan_jurnal_compare_csv_has_id_column($raw_headers);

	$csv_col_index = array();
	foreach ($db_columns_sanitized as $idx => $col) {
		$csv_col_index[$col] = $idx;
	}

	$amount_cols = array('debet_21101uu_dagang', 'serba_serbi_jumlah', 'kredit_11101_kas_besar');
	$col_tanggal = !empty($column_map['tanggal']) ? $column_map['tanggal'] : 'tanggal';

	$db_columns = array();
	foreach ($db_columns_sanitized as $col) {
		if (strtolower($col) === 'id') {
			continue;
		}
		$db_columns[] = $col;
	}
	foreach (array_keys(pengeluaran_kas_compare_field_definitions()) as $required_col) {
		if (!in_array($required_col, $db_columns, true) && !empty($column_map[$required_col])) {
			$db_columns[] = $column_map[$required_col];
		}
	}

	$bulan_tahun_ref = penjualan_jurnal_compare_parse_bulan_tahun_from_import($bulan_key, $bulan_num, $tahun);
	$base_table = persediaan_compare_sanitize_table_name_from_csv($original_filename, $bulan_key);
	$resolved = persediaan_compare_resolve_unique_table_name($CI, $base_table);
	if (empty($resolved['ok'])) {
		fclose($handle);
		return array(
			'ok' => false,
			'stage' => 'nama_tabel',
			'message' => isset($resolved['message']) ? $resolved['message'] : "Nama tabel hasil import dari file `{$file_label}` tidak valid.",
			'file' => $file_label,
		);
	}

	$table = $resolved['table'];
	$table_suffix = isset($resolved['suffix']) ? (int) $resolved['suffix'] : 0;
	$table_base = isset($resolved['base']) ? $resolved['base'] : $base_table;
	$table_exists_before = $CI->db->table_exists($table_base);

	$field_defs = array('`id` INT(9) NOT NULL AUTO_INCREMENT');
	foreach ($db_columns as $col) {
		if ($col === $col_tanggal) {
			$field_defs[] = '`' . $col . '` DATE NULL';
		} elseif (in_array($col, $amount_cols, true)) {
			$field_defs[] = '`' . $col . '` INT(9) NULL';
		} else {
			$field_defs[] = '`' . $col . '` TEXT NULL';
		}
	}

	$create_sql = 'CREATE TABLE `' . $table . '` (' . implode(', ', $field_defs)
		. ', PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';

	if ($CI->db->query($create_sql) === false) {
		fclose($handle);
		return array(
			'ok' => false,
			'stage' => 'create_table',
			'message' => "Gagal membuat tabel baru `{$table}` dari file `{$file_label}`.\n"
				. 'Detail database: ' . persediaan_compare_db_last_error_message($CI),
			'table' => $table,
			'file' => $file_label,
		);
	}

	persediaan_compare_clear_db_schema_cache($CI);

	$inserted = 0;
	$batch = array();
	$batch_size = 100;
	$line_no = 1;

	while (($row = fgetcsv($handle, 0, ',', '"')) !== false) {
		$line_no++;
		if (!is_array($row)) {
			continue;
		}
		$is_empty = true;
		foreach ($row as $cell) {
			if (trim((string) $cell) !== '') {
				$is_empty = false;
				break;
			}
		}
		if ($is_empty) {
			continue;
		}

		$data = array();
		foreach ($db_columns as $col) {
			$csv_idx = isset($csv_col_index[$col]) ? $csv_col_index[$col] : null;
			$data[$col] = ($csv_idx !== null && isset($row[$csv_idx])) ? trim((string) $row[$csv_idx]) : '';
		}

		$data[$col_tanggal] = pengeluaran_kas_compare_normalize_tanggal_for_db(
			isset($data[$col_tanggal]) ? $data[$col_tanggal] : '',
			$bulan_tahun_ref['month'],
			$bulan_tahun_ref['year']
		);

		foreach ($amount_cols as $amount_col) {
			if (isset($data[$amount_col])) {
				$data[$amount_col] = pengeluaran_kas_compare_normalize_jumlah($data[$amount_col]);
			}
		}

		$batch[] = $data;
		if (count($batch) >= $batch_size) {
			if ($CI->db->insert_batch($table, $batch) === false) {
				fclose($handle);
				$CI->db->query('DROP TABLE IF EXISTS `' . $table . '`');
				persediaan_compare_clear_db_schema_cache($CI);
				return array(
					'ok' => false,
					'stage' => 'insert_data',
					'message' => "Gagal meng-upload data CSV ke tabel baru `{$table}` (sekitar baris {$line_no}).",
					'table' => $table,
					'file' => $file_label,
				);
			}
			$inserted += count($batch);
			$batch = array();
		}
	}
	fclose($handle);

	if (count($batch) > 0) {
		if ($CI->db->insert_batch($table, $batch) === false) {
			$CI->db->query('DROP TABLE IF EXISTS `' . $table . '`');
			persediaan_compare_clear_db_schema_cache($CI);
			return array(
				'ok' => false,
				'stage' => 'insert_data',
				'message' => "Gagal meng-upload sisa data CSV ke tabel baru `{$table}`.",
				'table' => $table,
				'file' => $file_label,
			);
		}
		$inserted += count($batch);
	}

	if ($inserted === 0) {
		$CI->db->query('DROP TABLE IF EXISTS `' . $table . '`');
		persediaan_compare_clear_db_schema_cache($CI);
		return array(
			'ok' => false,
			'stage' => 'insert_data',
			'message' => "File `{$file_label}` tidak memiliki baris data setelah header.",
			'table' => $table,
			'file' => $file_label,
		);
	}

	$id_info = $csv_has_id
		? "Kolom `id` dari CSV diabaikan — tabel memakai kolom `id` INT(9) AUTO_INCREMENT PRIMARY KEY baru.\n"
		: "Kolom `id` INT(9) AUTO_INCREMENT PRIMARY KEY ditambahkan otomatis.\n";

	return array(
		'ok' => true,
		'stage' => 'success',
		'table' => $table,
		'table_base' => $table_base,
		'table_suffix' => $table_suffix,
		'table_exists_before' => $table_exists_before,
		'rows' => $inserted,
		'columns' => count($db_columns),
		'csv_has_id' => $csv_has_id,
		'file' => $file_label,
		'tanggal_normalized' => true,
		'bulan_tahun_ref' => $bulan_tahun_ref['month'] . '/' . $bulan_tahun_ref['year'],
		'tanggal_akhir_ref' => $bulan_tahun_ref['tanggal_akhir_display'],
		'message' => "Import CSV berhasil.\n"
			. ($table_suffix > 0 || $table_exists_before
				? "Tabel `{$table_base}` sudah ada — dibuat tabel baru: `{$table}`.\n"
				: "1. Tabel baru dibuat: `{$table}`\n")
			. '2. Kolom disesuaikan dari header CSV (' . count($db_columns) . " kolom).\n"
			. '3. ' . $id_info
			. "4. Data ter-upload: {$inserted} baris\n"
			. 'Silahkan lanjut compare menggunakan tabel ini.',
	);
}
