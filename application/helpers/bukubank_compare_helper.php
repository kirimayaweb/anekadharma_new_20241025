<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function bukubank_compare_pick_bukubank_db_column($fields, $logical_key)
{
	$fields = array_map('strval', (array) $fields);
	$candidates = array();
	if ($logical_key === 'debet') {
		$candidates = array('debit', 'debet');
	} elseif ($logical_key === 'kredit') {
		$candidates = array('kredit');
	} else {
		return in_array($logical_key, $fields, true) ? $logical_key : null;
	}

	foreach ($candidates as $col) {
		if (in_array($col, $fields, true)) {
			return $col;
		}
	}

	return null;
}

function bukubank_compare_bukubank_target_column_map($CI)
{
	$fields = $CI->db->list_fields('bukubank');

	return array(
		'tanggal' => bukubank_compare_pick_bukubank_db_column($fields, 'tanggal'),
		'bank' => bukubank_compare_pick_bukubank_db_column($fields, 'bank'),
		'norek' => bukubank_compare_pick_bukubank_db_column($fields, 'norek'),
		'keterangan' => bukubank_compare_pick_bukubank_db_column($fields, 'keterangan'),
		'kode' => bukubank_compare_pick_bukubank_db_column($fields, 'kode'),
		'debet' => bukubank_compare_pick_bukubank_db_column($fields, 'debet'),
		'kredit' => bukubank_compare_pick_bukubank_db_column($fields, 'kredit'),
		'saldo' => bukubank_compare_pick_bukubank_db_column($fields, 'saldo'),
		'_fields' => $fields,
	);
}

function bukubank_compare_row_get_kredit_raw($row)
{
	if (isset($row->kredit)) {
		return $row->kredit;
	}
	return '';
}

function bukubank_compare_row_get_debet_raw($row)
{
	if (isset($row->debet)) {
		return $row->debet;
	}
	if (isset($row->debit)) {
		return $row->debit;
	}

	return 0;
}

function bukubank_compare_build_bukubank_insert_row($CI, $item, $tanggal_db)
{
	$target = bukubank_compare_bukubank_target_column_map($CI);
	$missing = array();
	if (empty($target['debet'])) {
		$missing[] = 'debet atau debit';
	}
	if (empty($target['kredit'])) {
		$missing[] = 'kredit';
	}
	if (count($missing) > 0) {
		return array(
			'ok' => false,
			'message' => 'Kolom wajib tidak ditemukan di tabel bukubank: ' . implode(', ', $missing) . '.',
		);
	}

	$data = array();
	if (!empty($target['tanggal'])) {
		$data[$target['tanggal']] = $tanggal_db;
	}
	if (!empty($target['bank'])) {
		$data[$target['bank']] = $item['bank'] !== '' ? $item['bank'] : '';
	}
	if (!empty($target['norek'])) {
		$data[$target['norek']] = $item['norek'] !== '' ? $item['norek'] : '';
	}
	if (!empty($target['keterangan'])) {
		$data[$target['keterangan']] = $item['keterangan'] !== '' ? $item['keterangan'] : '';
	}
	if (!empty($target['kode'])) {
		$data[$target['kode']] = $item['kode'] !== '' ? $item['kode'] : '';
	}
	if (!empty($target['saldo'])) {
		$data[$target['saldo']] = $item['saldo'] !== '' ? $item['saldo'] : '';
	}

	$data[$target['debet']] = $item['debet'] > 0 ? $item['debet'] : 0;
	$kredit_val = $item['kredit'] > 0 ? bukubank_compare_format_kredit_for_db($item['kredit']) : '';
	$data[$target['kredit']] = $kredit_val;

	return array('ok' => true, 'data' => $data, 'target_columns' => $target);
}

function bukubank_compare_field_definitions()
{
	return array(
		'tanggal' => array('label' => 'tanggal', 'required' => true, 'aliases' => array('tanggal', 'tgl', 'date', 'tgl_transaksi')),
		'bank' => array('label' => 'bank', 'required' => false, 'aliases' => array('bank', 'nama_bank', 'nama bank')),
		'norek' => array('label' => 'norek', 'required' => false, 'aliases' => array('norek', 'no_rek', 'no rekening', 'nomor rekening', 'rekening')),
		'keterangan' => array('label' => 'keterangan', 'required' => false, 'aliases' => array('keterangan', 'ket', 'uraian transaksi', 'deskripsi')),
		'kode' => array('label' => 'kode', 'required' => false, 'aliases' => array('kode', 'no_kode', 'kode transaksi')),
		'debet' => array('label' => 'debet', 'required' => false, 'aliases' => array('debet', 'debit')),
		'kredit' => array('label' => 'kredit', 'required' => false, 'aliases' => array('kredit', 'credit')),
		'saldo' => array('label' => 'saldo', 'required' => false, 'aliases' => array('saldo', 'balance')),
	);
}

function bukubank_compare_build_column_map($fields)
{
	$analysis = bukubank_compare_analyze_column_map($fields);
	return !empty($analysis['ok']) ? $analysis['map'] : null;
}

function bukubank_compare_analyze_column_map($fields)
{
	if (!is_array($fields) || count($fields) === 0) {
		return array('ok' => false, 'message' => 'Tabel tidak memiliki kolom.');
	}

	$normalized = array();
	foreach ($fields as $field) {
		$normalized[] = trim((string) $field);
	}

	$defs = bukubank_compare_field_definitions();
	$map = array(
		'tanggal' => penjualan_jurnal_compare_pick_tanggal_column($normalized),
		'bank' => persediaan_compare_pick_column($normalized, $defs['bank']['aliases']),
		'norek' => persediaan_compare_pick_column($normalized, $defs['norek']['aliases']),
		'keterangan' => persediaan_compare_pick_column($normalized, $defs['keterangan']['aliases']),
		'kode' => persediaan_compare_pick_column($normalized, $defs['kode']['aliases']),
		'debet' => persediaan_compare_pick_column($normalized, $defs['debet']['aliases']),
		'kredit' => persediaan_compare_pick_column($normalized, $defs['kredit']['aliases']),
		'saldo' => persediaan_compare_pick_column($normalized, $defs['saldo']['aliases']),
	);

	$missing_required = array();
	foreach ($defs as $key => $def) {
		if (empty($map[$key]) && !empty($def['required'])) {
			$missing_required[] = $def['label'];
		}
	}

	if (empty($map['debet']) && empty($map['kredit'])) {
		$missing_required[] = 'debet atau kredit';
	}

	$ok = count($missing_required) === 0;
	$message = '';
	if (!$ok) {
		$message = 'Kolom wajib tidak ditemukan: ' . implode(', ', $missing_required)
			. '. Kolom compare: tanggal, debet atau kredit.';
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

function bukubank_compare_validate_online_table_detail($CI)
{
	if (!$CI->db->table_exists('bukubank')) {
		return array(
			'ok' => false,
			'message' => 'Tabel online `bukubank` tidak ditemukan di database.',
			'missing_fields' => array('bukubank (tabel)'),
		);
	}

	$fields = $CI->db->list_fields('bukubank');
	$map = array(
		'tanggal' => bukubank_compare_pick_bukubank_db_column($fields, 'tanggal'),
		'bank' => bukubank_compare_pick_bukubank_db_column($fields, 'bank'),
		'norek' => bukubank_compare_pick_bukubank_db_column($fields, 'norek'),
		'keterangan' => bukubank_compare_pick_bukubank_db_column($fields, 'keterangan'),
		'kode' => bukubank_compare_pick_bukubank_db_column($fields, 'kode'),
		'debet' => bukubank_compare_pick_bukubank_db_column($fields, 'debet'),
		'kredit' => bukubank_compare_pick_bukubank_db_column($fields, 'kredit'),
		'saldo' => bukubank_compare_pick_bukubank_db_column($fields, 'saldo'),
	);

	$critical_missing = array();
	if (empty($map['tanggal'])) {
		$critical_missing[] = 'tanggal';
	}
	if (empty($map['debet']) && empty($map['kredit'])) {
		$critical_missing[] = 'debet/debit atau kredit';
	}

	$soft_missing = array();
	foreach (array('bank', 'norek', 'keterangan', 'kode', 'saldo') as $k) {
		if (empty($map[$k])) {
			$soft_missing[] = $k;
		}
	}

	$ok = count($critical_missing) === 0;
	$message = '';
	if (!$ok) {
		$message = 'Tabel online `bukubank` tidak memiliki kolom wajib: ' . implode(', ', $critical_missing) . '.';
	} elseif (count($soft_missing) > 0) {
		$message = 'Kolom compare online tidak ditemukan (diisi kosong): ' . implode(', ', $soft_missing) . '.';
	}

	$mapped = array();
	foreach ($map as $key => $col) {
		if ($col !== null && $col !== '') {
			$mapped[$key] = $col;
		}
	}

	return array(
		'ok' => $ok,
		'table' => 'bukubank',
		'map' => $map,
		'mapped' => $mapped,
		'missing_fields' => array_merge($critical_missing, $soft_missing),
		'critical_missing' => $critical_missing,
		'soft_missing' => $soft_missing,
		'fields' => $fields,
		'message' => $message,
	);
}

function bukubank_compare_validate_table($CI, $table)
{
	if (!persediaan_compare_is_valid_table_name($table)) {
		return array('ok' => false, 'message' => 'Nama tabel tidak valid.');
	}
	if (!$CI->db->table_exists($table)) {
		return array('ok' => false, 'message' => 'Tabel tidak ditemukan di database.');
	}

	$fields = $CI->db->list_fields($table);
	$analysis = bukubank_compare_analyze_column_map($fields);
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

function bukubank_compare_normalize_tanggal_for_db($value, $ref_month = 0, $ref_year = 0)
{
	$value = trim((string) $value);
	if ($value === '') {
		if ((int) $ref_month >= 1 && (int) $ref_month <= 12 && (int) $ref_year >= 2000) {
			return date('Y-m-t', mktime(0, 0, 0, (int) $ref_month, 1, (int) $ref_year));
		}
		return '';
	}

	if (preg_match('/^\d{1,2}$/', $value) && (int) $ref_month >= 1 && (int) $ref_year >= 2000) {
		$day = (int) $value;
		if ($day >= 1 && $day <= 31) {
			return date('Y-m-d', mktime(0, 0, 0, (int) $ref_month, $day, (int) $ref_year));
		}
	}

	$norm = pembelian_jurnal_compare_normalize_tanggal($value);
	return $norm !== '' ? $norm : '';
}

function bukubank_compare_row_matches_bulan($tanggal_ymd, $bulan)
{
	$tanggal_ymd = pembelian_jurnal_compare_normalize_tanggal($tanggal_ymd);
	if ($tanggal_ymd === '' || !preg_match('/^\d{4}-\d{2}$/', (string) $bulan)) {
		return false;
	}

	return substr($tanggal_ymd, 0, 7) === $bulan;
}

function bukubank_compare_normalize_bank($value)
{
	return strtoupper(trim((string) $value));
}

function bukubank_compare_normalize_norek($value)
{
	return trim((string) $value);
}

function bukubank_compare_normalize_kode($value)
{
	return trim((string) $value);
}

function bukubank_compare_format_kredit_for_db($value)
{
	$n = bukubank_compare_normalize_jumlah($value);
	if ($n <= 0) {
		return '';
	}
	if (fmod($n, 1.0) == 0.0) {
		return (string) (int) $n;
	}
	return (string) $n;
}

function bukubank_compare_normalize_keterangan($value)
{
	return trim((string) $value);
}

function bukubank_compare_normalize_text($value)
{
	return strtolower(trim(preg_replace('/\s+/', ' ', (string) $value)));
}

function bukubank_compare_normalize_jumlah($value)
{
	return round(pembelian_jurnal_compare_parse_jumlah_nominal($value), 2);
}

function bukubank_compare_format_jumlah_display($jumlah)
{
	if ($jumlah === null || $jumlah === '' || (float) $jumlah == 0) {
		return '';
	}
	return number_format((float) $jumlah, 2, ',', '.');
}

function bukubank_compare_standard_headers()
{
	return array('No', 'Tanggal', 'Bank', 'Norek', 'Keterangan', 'Kode', 'Debet', 'Kredit', 'Saldo', 'Catatan');
}

function bukubank_compare_is_row_analyzable($row)
{
	$tanggal = pembelian_jurnal_compare_normalize_tanggal(isset($row['tanggal']) ? $row['tanggal'] : '');
	if ($tanggal === '' || $tanggal === '0000-00-00') {
		return false;
	}
	$deb = bukubank_compare_normalize_jumlah(isset($row['debet']) ? $row['debet'] : 0);
	$kre = bukubank_compare_normalize_jumlah(isset($row['kredit']) ? $row['kredit'] : 0);
	return ($deb > 0 || $kre > 0);
}

function bukubank_compare_row_unprocessed_reasons($row)
{
	$reasons = array();
	if (pembelian_jurnal_compare_normalize_tanggal(isset($row['tanggal']) ? $row['tanggal'] : '') === '') {
		$reasons[] = 'tanggal kosong/tidak valid';
	}
	if (bukubank_compare_normalize_jumlah(isset($row['debet']) ? $row['debet'] : 0) <= 0
		&& bukubank_compare_normalize_jumlah(isset($row['kredit']) ? $row['kredit'] : 0) <= 0) {
		$reasons[] = 'debet dan kredit kosong';
	}
	return $reasons;
}

function bukubank_compare_make_hard_key($tanggal, $bank, $norek, $kode)
{
	return pembelian_jurnal_compare_normalize_tanggal($tanggal)
		. '|' . bukubank_compare_normalize_bank($bank)
		. '|' . bukubank_compare_normalize_norek($norek)
		. '|' . bukubank_compare_normalize_kode($kode);
}

function bukubank_compare_make_full_key($row)
{
	return bukubank_compare_make_hard_key(
		isset($row['tanggal']) ? $row['tanggal'] : '',
		isset($row['bank']) ? $row['bank'] : '',
		isset($row['norek']) ? $row['norek'] : '',
		isset($row['kode']) ? $row['kode'] : ''
	)
		. '|' . bukubank_compare_normalize_keterangan(isset($row['keterangan']) ? $row['keterangan'] : '')
		. '|' . bukubank_compare_normalize_jumlah(isset($row['debet']) ? $row['debet'] : 0)
		. '|' . bukubank_compare_normalize_jumlah(isset($row['kredit']) ? $row['kredit'] : 0);
}

function bukubank_compare_row_to_display($row, $catatan = '')
{
	$debet = bukubank_compare_normalize_jumlah(isset($row['debet']) ? $row['debet'] : 0);
	$kredit = bukubank_compare_normalize_jumlah(isset($row['kredit']) ? $row['kredit'] : 0);
	$saldo = bukubank_compare_normalize_jumlah(isset($row['saldo']) ? $row['saldo'] : 0);

	return array(
		'tanggal' => isset($row['tanggal']) ? pembelian_jurnal_compare_format_tanggal_display($row['tanggal']) : '',
		'bank' => isset($row['bank']) ? $row['bank'] : '',
		'norek' => isset($row['norek']) ? $row['norek'] : '',
		'keterangan' => isset($row['keterangan']) ? $row['keterangan'] : '',
		'kode' => isset($row['kode']) ? $row['kode'] : '',
		'debet' => $debet > 0 ? bukubank_compare_format_jumlah_display($debet) : '',
		'kredit' => $kredit > 0 ? bukubank_compare_format_jumlah_display($kredit) : '',
		'saldo' => $saldo != 0 ? bukubank_compare_format_jumlah_display($saldo) : (isset($row['saldo']) ? trim((string) $row['saldo']) : ''),
		'catatan' => $catatan,
	);
}

function bukubank_compare_build_diff_catatan($row, $other, $other_label)
{
	if (!is_array($row) || !is_array($other)) {
		return 'Tidak ditemukan pasangan di data ' . $other_label . '.';
	}

	$similar = array('Tanggal', 'Bank', 'Norek', 'Kode');
	$diff_parts = array();

	if (bukubank_compare_normalize_keterangan($row['keterangan']) === bukubank_compare_normalize_keterangan($other['keterangan'])) {
		$similar[] = 'Keterangan';
	} else {
		$diff_parts[] = 'Keterangan berbeda (Manual: ' . $row['keterangan'] . ', ' . ucfirst($other_label) . ': ' . $other['keterangan'] . ')';
	}

	$deb_r = bukubank_compare_normalize_jumlah($row['debet']);
	$deb_o = bukubank_compare_normalize_jumlah($other['debet']);
	if ($deb_r === $deb_o) {
		$similar[] = 'Debet';
	} else {
		$diff_parts[] = 'Debet berbeda (Manual: ' . bukubank_compare_format_jumlah_display($deb_r)
			. ', ' . ucfirst($other_label) . ': ' . bukubank_compare_format_jumlah_display($deb_o) . ')';
	}

	$kre_r = bukubank_compare_normalize_jumlah($row['kredit']);
	$kre_o = bukubank_compare_normalize_jumlah($other['kredit']);
	if ($kre_r === $kre_o) {
		$similar[] = 'Kredit';
	} else {
		$diff_parts[] = 'Kredit berbeda (Manual: ' . bukubank_compare_format_jumlah_display($kre_r)
			. ', ' . ucfirst($other_label) . ': ' . bukubank_compare_format_jumlah_display($kre_o) . ')';
	}

	$parts = array();
	if (count($similar) > 0) {
		$parts[] = 'Field sama: ' . implode(', ', $similar);
	}
	if (count($diff_parts) > 0) {
		$parts[] = 'Field berbeda: ' . implode('; ', $diff_parts);
	}

	if (count($parts) === 0) {
		return 'Tidak ditemukan pasangan lengkap di data ' . $other_label . '.';
	}

	return implode('; ', $parts);
}

function bukubank_compare_manual_row_from_db($row, $map, $default_tanggal = '')
{
	$tanggal_raw = !empty($map['tanggal']) ? persediaan_compare_row_get($row, $map['tanggal']) : $default_tanggal;
	$tanggal_norm = pembelian_jurnal_compare_normalize_tanggal($tanggal_raw);
	if (($tanggal_norm === '' || $tanggal_norm === '0000-00-00') && $default_tanggal !== '') {
		$tanggal_norm = pembelian_jurnal_compare_normalize_tanggal($default_tanggal);
	}

	return array(
		'tanggal' => $tanggal_norm,
		'bank' => trim((string) (!empty($map['bank']) ? persediaan_compare_row_get($row, $map['bank']) : '')),
		'norek' => trim((string) (!empty($map['norek']) ? persediaan_compare_row_get($row, $map['norek']) : '')),
		'keterangan' => trim((string) (!empty($map['keterangan']) ? persediaan_compare_row_get($row, $map['keterangan']) : '')),
		'kode' => trim((string) (!empty($map['kode']) ? persediaan_compare_row_get($row, $map['kode']) : '')),
		'debet' => bukubank_compare_normalize_jumlah(!empty($map['debet']) ? persediaan_compare_row_get($row, $map['debet']) : 0),
		'kredit' => bukubank_compare_normalize_jumlah(!empty($map['kredit']) ? persediaan_compare_row_get($row, $map['kredit']) : 0),
		'saldo' => bukubank_compare_normalize_jumlah(!empty($map['saldo']) ? persediaan_compare_row_get($row, $map['saldo']) : 0),
	);
}

function bukubank_compare_load_manual_rows_for_bulan($CI, $table, $bulan)
{
	$valid = bukubank_compare_validate_table($CI, $table);
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
		$item = bukubank_compare_manual_row_from_db($row, $valid['map'], $default_tanggal);
		if (bukubank_compare_row_matches_bulan($item['tanggal'], $bulan)) {
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

function bukubank_compare_load_manual_all($CI, $table, $bulan)
{
	$valid = bukubank_compare_validate_table($CI, $table);
	if (empty($valid['ok'])) {
		return $valid;
	}

	$loaded = bukubank_compare_load_manual_rows_for_bulan($CI, $table, $bulan);
	if (empty($loaded['ok'])) {
		return $loaded;
	}

	$map = $loaded['map'];
	$default_tanggal = $loaded['range']['tgl_akhir'];
	$list = array();
	$by_full = array();
	$by_hard = array();
	$unprocessed = array();
	$display_all = array();

	foreach ($loaded['rows'] as $row) {
		$item = bukubank_compare_manual_row_from_db($row, $map, $default_tanggal);
		$reasons = bukubank_compare_row_unprocessed_reasons($item);
		$display_all[] = bukubank_compare_row_to_display(
			$item,
			count($reasons) > 0 ? ('Info: ' . implode(', ', $reasons)) : ''
		);

		if (!bukubank_compare_is_row_analyzable($item)) {
			$unprocessed[] = bukubank_compare_row_to_display($item, 'Manual tidak terproses: ' . implode(', ', $reasons));
			continue;
		}

		$full_key = bukubank_compare_make_full_key($item);
		$hard_key = bukubank_compare_make_hard_key($item['tanggal'], $item['bank'], $item['norek'], $item['kode']);
		$item['_full_key'] = $full_key;
		$item['_hard_key'] = $hard_key;

		$list[] = $item;
		if (!isset($by_full[$full_key])) {
			$by_full[$full_key] = array();
		}
		$by_full[$full_key][] = $item;
		if (!isset($by_hard[$hard_key])) {
			$by_hard[$hard_key] = array();
		}
		$by_hard[$hard_key][] = $item;
	}

	return array(
		'ok' => true,
		'list' => $list,
		'by_full' => $by_full,
		'by_hard' => $by_hard,
		'display_list' => $display_all,
		'unprocessed' => $unprocessed,
		'warnings' => isset($loaded['warnings']) ? $loaded['warnings'] : array(),
		'stats' => array(
			'raw_total' => isset($loaded['stats']['raw_total']) ? (int) $loaded['stats']['raw_total'] : count($loaded['rows']),
			'filtered_total' => count($loaded['rows']),
			'processed' => count($list),
			'unprocessed' => count($unprocessed),
		),
		'table' => $table,
		'range' => $loaded['range'],
		'mapped' => isset($valid['mapped']) ? $valid['mapped'] : array(),
	);
}

function bukubank_compare_online_row_from_db($row)
{
	return array(
		'tanggal' => pembelian_jurnal_compare_normalize_tanggal(isset($row->tanggal) ? $row->tanggal : ''),
		'bank' => isset($row->bank) ? trim((string) $row->bank) : '',
		'norek' => isset($row->norek) ? trim((string) $row->norek) : '',
		'keterangan' => isset($row->keterangan) ? trim((string) $row->keterangan) : '',
		'kode' => isset($row->kode) ? trim((string) $row->kode) : '',
		'debet' => bukubank_compare_normalize_jumlah(bukubank_compare_row_get_debet_raw($row)),
		'kredit' => bukubank_compare_normalize_jumlah(bukubank_compare_row_get_kredit_raw($row)),
		'saldo' => bukubank_compare_normalize_jumlah(isset($row->saldo) ? $row->saldo : 0),
	);
}

function bukubank_compare_load_online_all($CI, $bulan)
{
	$range = persediaan_compare_bulan_to_date_range($bulan);
	if ($range === null) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');
	}

	$online_fields = bukubank_compare_validate_online_table_detail($CI);
	if (empty($online_fields['ok'])) {
		return array(
			'ok' => false,
			'message' => isset($online_fields['message']) ? $online_fields['message'] : 'Struktur tabel online bukubank tidak valid.',
			'field_validation' => array('online' => $online_fields),
		);
	}

	$month = (int) substr($bulan, 5, 2);
	$year = (int) substr($bulan, 0, 4);

	$sql = "SELECT DATE(tanggal) AS tanggal,
		COALESCE(NULLIF(TRIM(bank), ''), '') AS bank,
		COALESCE(NULLIF(TRIM(norek), ''), '') AS norek,
		COALESCE(NULLIF(TRIM(keterangan), ''), '') AS keterangan,
		COALESCE(NULLIF(TRIM(kode), ''), '') AS kode,
		COALESCE(debet, 0) AS debet,
		COALESCE(kredit, '') AS kredit,
		COALESCE(saldo, '') AS saldo
		FROM bukubank
		WHERE tanggal IS NOT NULL AND tanggal <> '0000-00-00'
		AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?
		ORDER BY tanggal, id";

	$list = array();
	$by_full = array();
	$by_hard = array();
	$unprocessed = array();
	$display_all = array();

	foreach ($CI->db->query($sql, array($month, $year))->result() as $row) {
		$item = bukubank_compare_online_row_from_db($row);
		$reasons = bukubank_compare_row_unprocessed_reasons($item);
		$display_all[] = bukubank_compare_row_to_display(
			$item,
			count($reasons) > 0 ? ('Info: ' . implode(', ', $reasons)) : ''
		);

		if (!bukubank_compare_is_row_analyzable($item)) {
			$unprocessed[] = bukubank_compare_row_to_display($item, 'Online tidak terproses: ' . implode(', ', $reasons));
			continue;
		}

		$full_key = bukubank_compare_make_full_key($item);
		$hard_key = bukubank_compare_make_hard_key($item['tanggal'], $item['bank'], $item['norek'], $item['kode']);
		$item['_full_key'] = $full_key;
		$item['_hard_key'] = $hard_key;

		$list[] = $item;
		if (!isset($by_full[$full_key])) {
			$by_full[$full_key] = array();
		}
		$by_full[$full_key][] = $item;
		if (!isset($by_hard[$hard_key])) {
			$by_hard[$hard_key] = array();
		}
		$by_hard[$hard_key][] = $item;
	}

	return array(
		'ok' => true,
		'list' => $list,
		'by_full' => $by_full,
		'by_hard' => $by_hard,
		'display_list' => $display_all,
		'unprocessed' => $unprocessed,
		'stats' => array(
			'raw_total' => count($display_all),
			'processed' => count($list),
			'unprocessed' => count($unprocessed),
		),
		'range' => $range,
	);
}

function bukubank_compare_pick_best_candidate($row, $candidates)
{
	if (!is_array($candidates) || count($candidates) === 0) {
		return null;
	}

	$best = null;
	$best_score = -1;
	foreach ($candidates as $candidate) {
		$score = 0;
		if (bukubank_compare_normalize_keterangan($row['keterangan']) === bukubank_compare_normalize_keterangan($candidate['keterangan'])) {
			$score += 3;
		}
		if (bukubank_compare_normalize_jumlah($row['debet']) === bukubank_compare_normalize_jumlah($candidate['debet'])) {
			$score += 2;
		}
		if (bukubank_compare_normalize_jumlah($row['kredit']) === bukubank_compare_normalize_jumlah($candidate['kredit'])) {
			$score += 2;
		}
		if ($score > $best_score) {
			$best_score = $score;
			$best = $candidate;
		}
	}

	return $best;
}

function bukubank_compare_sort_display_rows($a, $b)
{
	$cmp = strcmp((string) $a['tanggal'], (string) $b['tanggal']);
	if ($cmp !== 0) {
		return $cmp;
	}
	$cmp = strcmp((string) $a['bank'], (string) $b['bank']);
	if ($cmp !== 0) {
		return $cmp;
	}
	return strcmp((string) $a['norek'], (string) $b['norek']);
}

function bukubank_compare_run($CI, $bulan, $table = '')
{
	$table = trim((string) $table);
	if ($table === '') {
		return array('ok' => false, 'message' => 'Pilih tabel manual yang akan dibandingkan.');
	}

	$online_fields = bukubank_compare_validate_online_table_detail($CI);
	if (empty($online_fields['ok'])) {
		return array(
			'ok' => false,
			'message' => isset($online_fields['message']) ? $online_fields['message'] : 'Struktur tabel online bukubank tidak valid.',
			'field_validation' => array('online' => $online_fields),
		);
	}

	$manual = bukubank_compare_load_manual_all($CI, $table, $bulan);
	if (empty($manual['ok'])) {
		$manual['field_validation'] = array(
			'manual' => array(
				'ok' => false,
				'table' => $table,
				'missing_fields' => isset($manual['missing_fields']) ? $manual['missing_fields'] : array(),
				'message' => isset($manual['message']) ? $manual['message'] : '',
			),
			'online' => $online_fields,
		);
		return $manual;
	}

	$online = bukubank_compare_load_online_all($CI, $bulan);
	if (empty($online['ok'])) {
		return $online;
	}

	$warnings = array();
	if (!empty($online_fields['soft_missing'])) {
		$warnings[] = 'Kolom online tidak ditemukan (diisi kosong): ' . implode(', ', $online_fields['soft_missing']);
	}
	if (!empty($manual['warnings']) && is_array($manual['warnings'])) {
		$warnings = array_merge($warnings, $manual['warnings']);
	}
	if ((int) $manual['stats']['raw_total'] === 0) {
		return array(
			'ok' => false,
			'message' => 'Tabel manual `' . $table . '` tidak memiliki baris data.',
			'field_validation' => array(
				'manual' => array(
					'ok' => true,
					'table' => $table,
					'mapped' => isset($manual['mapped']) ? $manual['mapped'] : array(),
					'missing_fields' => array(),
				),
				'online' => $online_fields,
			),
		);
	}

	$data_cocok = array();
	$manual_tidak = array();
	$online_tidak = array();
	$online_pool = array();
	foreach ($online['list'] as $idx => $item) {
		$online_pool[$idx] = $item;
	}
	$online_used = array();

	foreach ($manual['list'] as $manual_row) {
		$matched_online_idx = null;
		foreach ($online_pool as $o_idx => $online_row) {
			if (!empty($online_used[$o_idx])) {
				continue;
			}
			if ($manual_row['_full_key'] === $online_row['_full_key']) {
				$matched_online_idx = $o_idx;
				break;
			}
		}

		if ($matched_online_idx !== null) {
			$online_used[$matched_online_idx] = true;
			$data_cocok[] = bukubank_compare_row_to_display($manual_row, 'Data manual dan online cocok');
			continue;
		}

		$candidates = array();
		foreach ($online_pool as $o_idx => $online_row) {
			if (!empty($online_used[$o_idx])) {
				continue;
			}
			if ($manual_row['_hard_key'] === $online_row['_hard_key']) {
				$candidates[] = $online_row;
			}
		}

		if (count($candidates) > 0) {
			$best = bukubank_compare_pick_best_candidate($manual_row, $candidates);
			foreach ($online_pool as $o_idx => $online_row) {
				if ($best !== null && $online_row === $best) {
					$online_used[$o_idx] = true;
					break;
				}
			}
			$manual_tidak[] = bukubank_compare_row_to_display(
				$manual_row,
				bukubank_compare_build_diff_catatan($manual_row, $best, 'online')
			);
		} else {
			$manual_tidak[] = bukubank_compare_row_to_display(
				$manual_row,
				'Tidak ditemukan di data online.'
			);
		}
	}

	foreach ($online_pool as $o_idx => $online_row) {
		if (!empty($online_used[$o_idx])) {
			continue;
		}

		$candidates = array();
		foreach ($manual['list'] as $manual_row) {
			if ($online_row['_hard_key'] === $manual_row['_hard_key']) {
				$candidates[] = $manual_row;
			}
		}

		if (count($candidates) > 0) {
			$best = bukubank_compare_pick_best_candidate($online_row, $candidates);
			$online_tidak[] = bukubank_compare_row_to_display(
				$online_row,
				bukubank_compare_build_diff_catatan($online_row, $best, 'manual')
			);
		} else {
			$online_tidak[] = bukubank_compare_row_to_display(
				$online_row,
				'Tidak ditemukan di data manual.'
			);
		}
	}

	usort($data_cocok, 'bukubank_compare_sort_display_rows');
	usort($manual_tidak, 'bukubank_compare_sort_display_rows');
	usort($online_tidak, 'bukubank_compare_sort_display_rows');

	return array(
		'ok' => true,
		'bulan' => $bulan,
		'bulan_label' => pembelian_jurnal_compare_bulan_label($bulan),
		'table' => $table,
		'data_cocok' => $data_cocok,
		'data_manual' => $manual['display_list'],
		'data_online' => $online['display_list'],
		'manual_tidak_di_online' => $manual_tidak,
		'online_tidak_di_manual' => $online_tidak,
		'warnings' => $warnings,
		'field_validation' => array(
			'manual' => array(
				'ok' => true,
				'table' => $table,
				'mapped' => isset($manual['mapped']) ? $manual['mapped'] : array(),
				'missing_fields' => array(),
			),
			'online' => $online_fields,
		),
		'stats' => array(
			'data_cocok' => count($data_cocok),
			'data_manual' => count($manual['display_list']),
			'data_online' => count($online['display_list']),
			'manual_tidak_di_online' => count($manual_tidak),
			'online_tidak_di_manual' => count($online_tidak),
			'manual_raw_total' => isset($manual['stats']['raw_total']) ? (int) $manual['stats']['raw_total'] : 0,
			'manual_processed' => isset($manual['stats']['processed']) ? (int) $manual['stats']['processed'] : 0,
			'manual_unprocessed' => isset($manual['stats']['unprocessed']) ? (int) $manual['stats']['unprocessed'] : 0,
			'online_processed' => isset($online['stats']['processed']) ? (int) $online['stats']['processed'] : 0,
			'online_unprocessed' => isset($online['stats']['unprocessed']) ? (int) $online['stats']['unprocessed'] : 0,
		),
	);
}

function bukubank_compare_item_to_row_cells($item, $no)
{
	return array(
		$no,
		isset($item['tanggal']) ? $item['tanggal'] : '',
		isset($item['bank']) ? $item['bank'] : '',
		isset($item['norek']) ? $item['norek'] : '',
		isset($item['keterangan']) ? $item['keterangan'] : '',
		isset($item['kode']) ? $item['kode'] : '',
		isset($item['debet']) ? $item['debet'] : '',
		isset($item['kredit']) ? $item['kredit'] : '',
		isset($item['saldo']) ? $item['saldo'] : '',
		isset($item['catatan']) ? $item['catatan'] : '',
	);
}

function bukubank_compare_excel_all_total_cols()
{
	return 54;
}

function bukubank_compare_excel_all_group_definitions()
{
	return array(
		array('title' => 'DATA MANUAL', 'col_start' => 0, 'col_end' => 9),
		array('title' => 'DATA ONLINE', 'col_start' => 11, 'col_end' => 20),
		array('title' => 'DATA COCOK', 'col_start' => 22, 'col_end' => 31),
		array('title' => 'MANUAL TIDAK DI ONLINE', 'col_start' => 33, 'col_end' => 42),
		array('title' => 'ONLINE TIDAK DI MANUAL', 'col_start' => 44, 'col_end' => 53),
	);
}

function bukubank_compare_excel_all_headers()
{
	$block = bukubank_compare_standard_headers();
	return array_merge($block, array(''), $block, array(''), $block, array(''), $block, array(''), $block);
}

function bukubank_compare_excel_sheet_name_from_bulan($bulan)
{
	$bulan = trim((string) $bulan);
	if (!preg_match('/^(\d{4})-(\d{2})$/', $bulan, $m)) {
		return 'Compare Buku Bank';
	}
	$tahun = (int) $m[1];
	$bulan_num = (int) $m[2];
	$nama_bulan = persediaan_compare_excel_all_bulan_nama_id();
	$label_bulan = isset($nama_bulan[$bulan_num]) ? $nama_bulan[$bulan_num] : ('Bulan ' . $bulan_num);
	return excel_sheet_safe_name('Compare Buku Bank ' . $label_bulan . ' ' . $tahun);
}

function bukubank_compare_excel_items_to_cells($items)
{
	$rows = array();
	$no = 0;
	foreach ((array) $items as $item) {
		$no++;
		$rows[] = bukubank_compare_item_to_row_cells($item, $no);
	}
	return $rows;
}

function bukubank_compare_excel_all_empty_row()
{
	return array_fill(0, bukubank_compare_excel_all_total_cols(), '');
}

function bukubank_compare_excel_all_write_merged_rows($rowNum, $sections, $groups)
{
	$maxLen = 0;
	foreach ($sections as $sectionRows) {
		$maxLen = max($maxLen, count((array) $sectionRows));
	}
	for ($i = 0; $i < $maxLen; $i++) {
		$cells = bukubank_compare_excel_all_empty_row();
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

function bukubank_compare_export_excel_all_output($CI, $bulan, $table = '', $result = null)
{
	@set_time_limit(600);
	@ini_set('memory_limit', '512M');
	if ($result === null) {
		$result = bukubank_compare_run($CI, $bulan, $table);
	}
	if (empty($result['ok'])) {
		xlsAddSheet('Compare Buku Bank');
		xlsWriteLabel(0, 0, isset($result['message']) ? $result['message'] : 'Export Excel gagal.');
		xlsMultiEOF();
		return;
	}

	$groups = bukubank_compare_excel_all_group_definitions();
	$headers = bukubank_compare_excel_all_headers();
	$groupTitleRow = 2;
	$headerRow = 3;
	$dataStartRow = 4;
	$stats = isset($result['stats']) ? $result['stats'] : array();

	xlsAddSheet(bukubank_compare_excel_sheet_name_from_bulan($bulan));
	xlsWriteLabelBold14(0, 0, 'Compare Buku Bank — Manual vs Online — Bulan ' . $result['bulan_label']);
	xlsWriteLabel(1, 0, 'Tabel manual: ' . $result['table']
		. ' | Dicetak: ' . date('d/m/Y H:i:s')
		. ' | Manual: ' . (isset($stats['data_manual']) ? (int) $stats['data_manual'] : 0)
		. ' | Online: ' . (isset($stats['data_online']) ? (int) $stats['data_online'] : 0)
		. ' | Cocok: ' . (isset($stats['data_cocok']) ? (int) $stats['data_cocok'] : 0));

	persediaan_rekonsiliasi_tx_write_group_titles($groupTitleRow, $groups);
	persediaan_rekonsiliasi_tx_write_headers_grouped($headerRow, $headers, $groups);

	$sections = array(
		0 => bukubank_compare_excel_items_to_cells(isset($result['data_manual']) ? $result['data_manual'] : array()),
		11 => bukubank_compare_excel_items_to_cells(isset($result['data_online']) ? $result['data_online'] : array()),
		22 => bukubank_compare_excel_items_to_cells(isset($result['data_cocok']) ? $result['data_cocok'] : array()),
		33 => bukubank_compare_excel_items_to_cells(isset($result['manual_tidak_di_online']) ? $result['manual_tidak_di_online'] : array()),
		44 => bukubank_compare_excel_items_to_cells(isset($result['online_tidak_di_manual']) ? $result['online_tidak_di_manual'] : array()),
	);

	$lastRow = bukubank_compare_excel_all_write_merged_rows($dataStartRow, $sections, $groups);
	if ($lastRow < $dataStartRow) {
		$lastRow = $headerRow;
	}
	persediaan_rekonsiliasi_tx_finalize_group_borders($groupTitleRow, $lastRow, $groups);
	xlsMultiEOF();
}

function bukubank_compare_export_excel_output($CI, $bulan, $table = '')
{
	@set_time_limit(600);
	@ini_set('memory_limit', '512M');
	$CI->load->helper(array('exportexcel', 'persediaan_display', 'pembelian_persediaan'));

	$result = bukubank_compare_run($CI, $bulan, $table);
	if (empty($result['ok'])) {
		xlsBOF();
		xlsWriteLabel(0, 0, isset($result['message']) ? $result['message'] : 'Compare gagal.');
		xlsEOF();
		return;
	}

	bukubank_compare_export_excel_all_output($CI, $bulan, $table, $result);
}

function bukubank_compare_validate_csv_file($filepath)
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

	$map = bukubank_compare_build_column_map(persediaan_compare_sanitize_csv_headers($raw_headers));
	if ($map === null) {
		return array(
			'ok' => false,
			'stage' => 'validate_columns',
			'message' => 'Header CSV tidak memenuhi syarat Buku Bank. Kolom wajib: tanggal, debet atau kredit.',
		);
	}

	return array('ok' => true, 'map' => $map, 'headers' => $raw_headers);
}

function bukubank_compare_import_csv_to_db($CI, $filepath, $original_filename, $bulan_key = '', $bulan_num = 0, $tahun = 0)
{
	$file_label = trim((string) $original_filename);
	if ($file_label === '') {
		$file_label = basename((string) $filepath);
	}

	$validated = bukubank_compare_validate_csv_file($filepath);
	if (empty($validated['ok'])) {
		$validated['file'] = $file_label;
		return $validated;
	}

	$handle = persediaan_compare_csv_open_handle($filepath);
	if (!$handle) {
		return array('ok' => false, 'stage' => 'open_file', 'message' => "Gagal membuka file CSV `{$file_label}`.");
	}

	$raw_headers = persediaan_compare_csv_read_header($handle);
	if ($raw_headers === null) {
		fclose($handle);
		return array('ok' => false, 'stage' => 'read_header', 'message' => "File `{$file_label}` kosong atau header tidak valid.");
	}

	$db_columns_sanitized = persediaan_compare_sanitize_csv_headers($raw_headers);
	$column_map = bukubank_compare_build_column_map($db_columns_sanitized);
	$csv_col_index = array();
	foreach ($db_columns_sanitized as $idx => $col) {
		$csv_col_index[$col] = $idx;
	}

	$col_tanggal = !empty($column_map['tanggal']) ? $column_map['tanggal'] : 'tanggal';
	$col_bank = !empty($column_map['bank']) ? $column_map['bank'] : 'bank';
	$col_norek = !empty($column_map['norek']) ? $column_map['norek'] : 'norek';
	$col_keterangan = !empty($column_map['keterangan']) ? $column_map['keterangan'] : 'keterangan';
	$col_kode = !empty($column_map['kode']) ? $column_map['kode'] : 'kode';
	$col_debet = !empty($column_map['debet']) ? $column_map['debet'] : 'debet';
	$col_kredit = !empty($column_map['kredit']) ? $column_map['kredit'] : 'kredit';
	$col_saldo = !empty($column_map['saldo']) ? $column_map['saldo'] : 'saldo';

	$db_columns = array();
	foreach ($db_columns_sanitized as $col) {
		if (strtolower($col) === 'id') {
			continue;
		}
		$db_columns[] = $col;
	}
	foreach (array($col_tanggal, $col_bank, $col_norek, $col_keterangan, $col_kode, $col_debet, $col_kredit, $col_saldo) as $required_col) {
		if (!in_array($required_col, $db_columns, true)) {
			$db_columns[] = $required_col;
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
			'message' => isset($resolved['message']) ? $resolved['message'] : "Nama tabel dari file `{$file_label}` tidak valid.",
			'file' => $file_label,
		);
	}

	$table = $resolved['table'];
	$table_base = isset($resolved['base']) ? $resolved['base'] : $base_table;
	$table_exists_before = $CI->db->table_exists($table_base);

	$field_defs = array('`id` INT(9) NOT NULL AUTO_INCREMENT');
	foreach ($db_columns as $col) {
		if ($col === $col_tanggal) {
			$field_defs[] = '`' . $col . '` DATE NULL';
		} elseif ($col === $col_debet || $col === $col_kredit) {
			$field_defs[] = '`' . $col . '` DECIMAL(18,2) NULL';
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
			'message' => "Gagal membuat tabel `{$table}`.\n" . persediaan_compare_db_last_error_message($CI),
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

		$data[$col_tanggal] = bukubank_compare_normalize_tanggal_for_db(
			isset($data[$col_tanggal]) ? $data[$col_tanggal] : '',
			$bulan_tahun_ref['month'],
			$bulan_tahun_ref['year']
		);

		if ($col_debet !== '') {
			$data[$col_debet] = bukubank_compare_normalize_jumlah(isset($data[$col_debet]) ? $data[$col_debet] : 0);
		}
		if ($col_kredit !== '') {
			$data[$col_kredit] = bukubank_compare_normalize_jumlah(isset($data[$col_kredit]) ? $data[$col_kredit] : 0);
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
					'message' => "Gagal upload CSV ke tabel `{$table}` (baris {$line_no}).",
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
				'message' => "Gagal upload sisa data CSV ke tabel `{$table}`.",
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

	return array(
		'ok' => true,
		'stage' => 'success',
		'table' => $table,
		'table_base' => $table_base,
		'table_exists_before' => $table_exists_before,
		'rows' => $inserted,
		'columns' => count($db_columns),
		'file' => $file_label,
		'message' => "Import CSV berhasil.\nTabel: `{$table}`\nBaris: {$inserted}\nKolom: " . count($db_columns),
	);
}

function bukubank_compare_import_field_definitions()
{
	return bukubank_compare_field_definitions();
}

function bukubank_compare_is_import_row_saveable($row)
{
	$tanggal = pembelian_jurnal_compare_normalize_tanggal(isset($row['tanggal']) ? $row['tanggal'] : '');
	if ($tanggal === '' || $tanggal === '0000-00-00') {
		return false;
	}
	$deb = bukubank_compare_normalize_jumlah(isset($row['debet']) ? $row['debet'] : 0);
	$kre = bukubank_compare_normalize_jumlah(isset($row['kredit']) ? $row['kredit'] : 0);

	return ($deb > 0 || $kre > 0);
}

function bukubank_compare_analyze_import_column_map($fields)
{
	return bukubank_compare_analyze_column_map($fields);
}

function bukubank_compare_validate_import_table($CI, $table)
{
	if (!persediaan_compare_is_valid_table_name($table)) {
		return array('ok' => false, 'message' => 'Nama tabel tidak valid.');
	}
	if (!$CI->db->table_exists($table)) {
		return array('ok' => false, 'message' => 'Tabel tidak ditemukan di database.');
	}

	$fields = $CI->db->list_fields($table);
	$analysis = bukubank_compare_analyze_import_column_map($fields);
	if (empty($analysis['ok'])) {
		return array(
			'ok' => false,
			'message' => isset($analysis['message']) ? $analysis['message'] : 'Struktur tabel tidak valid untuk import buku besar.',
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

function bukubank_compare_import_row_from_db($row, $map, $ref_month = 0, $ref_year = 0)
{
	return bukubank_compare_manual_row_from_db($row, $map, bukubank_compare_normalize_tanggal_for_db('', $ref_month, $ref_year));
}

function bukubank_compare_count_bukubank_rows_for_bulan($CI, $bulan)
{
	if (!preg_match('/^\d{4}-\d{2}$/', (string) $bulan)) {
		return 0;
	}

	$year = (int) substr($bulan, 0, 4);
	$month = (int) substr($bulan, 5, 2);
	$row = $CI->db->query(
		'SELECT COUNT(*) AS c FROM `bukubank` WHERE YEAR(`tanggal`) = ? AND MONTH(`tanggal`) = ?',
		array($year, $month)
	)->row();

	return $row ? (int) $row->c : 0;
}

function bukubank_compare_validate_table_for_import($CI, $table, $bulan)
{
	if (!preg_match('/^\d{4}-\d{2}$/', (string) $bulan)) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');
	}

	$valid = bukubank_compare_validate_import_table($CI, $table);
	if (empty($valid['ok'])) {
		return array(
			'ok' => true,
			'eligible' => false,
			'import_enabled' => false,
			'bulan_match' => false,
			'message' => isset($valid['message']) ? $valid['message'] : 'Tabel tidak memenuhi syarat import.',
			'missing_fields' => isset($valid['missing_fields']) ? $valid['missing_fields'] : array(),
			'table' => $table,
			'bulan' => $bulan,
		);
	}

	$ref_year = (int) substr($bulan, 0, 4);
	$ref_month = (int) substr($bulan, 5, 2);
	$map = $valid['map'];
	$all_rows = $CI->db->query('SELECT * FROM `' . $table . '` ORDER BY id ASC')->result();

	$with_tanggal = 0;
	$in_bulan = 0;
	$out_bulan = 0;
	$empty_tanggal = 0;
	$saveable_in_bulan = 0;
	$invalid_in_bulan = 0;

	foreach ((array) $all_rows as $row) {
		$item = bukubank_compare_import_row_from_db($row, $map, $ref_month, $ref_year);
		$tanggal_norm = pembelian_jurnal_compare_normalize_tanggal($item['tanggal']);
		if ($tanggal_norm === '' || $tanggal_norm === '0000-00-00') {
			$empty_tanggal++;
			continue;
		}
		$with_tanggal++;
		if (bukubank_compare_row_matches_bulan($tanggal_norm, $bulan)) {
			$in_bulan++;
			if (bukubank_compare_is_import_row_saveable($item)) {
				$saveable_in_bulan++;
			} else {
				$invalid_in_bulan++;
			}
		} else {
			$out_bulan++;
		}
	}

	$import_enabled = ($saveable_in_bulan > 0);
	$import_message = '';
	if ($import_enabled) {
		$import_message = 'Siap disimpan ke bukubank: ' . $saveable_in_bulan . ' baris valid pada bulan terpilih.';
		if ($out_bulan > 0) {
			$import_message .= ' (' . $out_bulan . ' baris di luar bulan akan dilewati.)';
		}
	} elseif ($in_bulan > 0) {
		$import_message = 'Ada ' . $in_bulan . ' baris pada bulan terpilih, tetapi tidak ada yang memenuhi syarat tanggal dan debet/kredit.';
	} elseif ($out_bulan > 0) {
		$import_message = 'Tidak ada data dengan tanggal pada bulan terpilih.';
	} else {
		$import_message = 'Tidak ada data dengan tanggal valid pada tabel ini.';
	}

	$existing_count = bukubank_compare_count_bukubank_rows_for_bulan($CI, $bulan);
	$conflict_warning = '';
	if ($existing_count > 0) {
		$conflict_warning = 'Perhatian: di tabel bukubank sudah ada ' . $existing_count
			. ' data pada bulan ' . pembelian_jurnal_compare_bulan_label($bulan)
			. '. Proses simpan akan menambahkan semua baris valid apa adanya tanpa cek duplikat.';
	}

	return array(
		'ok' => true,
		'eligible' => true,
		'import_enabled' => $import_enabled,
		'bulan_match' => $import_enabled,
		'import_message' => $import_message,
		'message' => 'Tabel `' . $table . '` memenuhi syarat kolom import buku bank.',
		'map' => $map,
		'mapped' => isset($valid['mapped']) ? $valid['mapped'] : array(),
		'table' => $table,
		'bulan' => $bulan,
		'bukubank_bulan_conflict' => ($existing_count > 0),
		'bukubank_existing_count' => $existing_count,
		'conflict_warning' => $conflict_warning,
		'stats' => array(
			'total_rows' => count($all_rows),
			'with_tanggal' => $with_tanggal,
			'in_bulan' => $in_bulan,
			'out_bulan' => $out_bulan,
			'empty_tanggal' => $empty_tanggal,
			'saveable_in_bulan' => $saveable_in_bulan,
			'invalid_in_bulan' => $invalid_in_bulan,
		),
	);
}

function bukubank_compare_load_table_detail_for_bulan($CI, $table, $bulan)
{
	if (!preg_match('/^\d{4}-\d{2}$/', (string) $bulan)) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');
	}

	$valid = bukubank_compare_validate_import_table($CI, $table);
	if (empty($valid['ok'])) {
		return $valid;
	}

	$ref_year = (int) substr($bulan, 0, 4);
	$ref_month = (int) substr($bulan, 5, 2);
	$map = $valid['map'];
	$range = persediaan_compare_bulan_to_date_range($bulan);
	$all_rows = $CI->db->query('SELECT * FROM `' . $table . '` ORDER BY id ASC')->result();
	$items = array();
	$no = 0;

	foreach ((array) $all_rows as $row) {
		$item = bukubank_compare_import_row_from_db($row, $map, $ref_month, $ref_year);
		if (!bukubank_compare_row_matches_bulan($item['tanggal'], $bulan)) {
			continue;
		}
		$no++;
		$debet = (float) $item['debet'];
		$kredit = (float) $item['kredit'];
		$saldo = (float) $item['saldo'];
		$items[] = array(
			'no' => $no,
			'tanggal' => pembelian_jurnal_compare_format_tanggal_display($item['tanggal']),
			'bank' => $item['bank'],
			'norek' => $item['norek'],
			'keterangan' => $item['keterangan'],
			'kode' => $item['kode'],
			'debet' => $debet > 0 ? bukubank_compare_format_jumlah_display($debet) : '',
			'kredit' => $kredit > 0 ? bukubank_compare_format_jumlah_display($kredit) : '',
			'saldo' => $saldo != 0 ? bukubank_compare_format_jumlah_display($saldo) : '',
			'debet_raw' => $debet,
			'kredit_raw' => $kredit,
			'saldo_raw' => $saldo,
		);
	}

	return array(
		'ok' => true,
		'headers' => array('No', 'Tanggal', 'Bank', 'Norek', 'Keterangan', 'Kode', 'Debet', 'Kredit', 'Saldo'),
		'rows' => $items,
		'table' => $table,
		'bulan' => $bulan,
		'bulan_label' => $range ? $range['bulan_label'] : $bulan,
		'total' => count($items),
	);
}

function bukubank_compare_import_to_bukubank($CI, $table, $bulan)
{
	$status = bukubank_compare_validate_table_for_import($CI, $table, $bulan);
	if (empty($status['ok'])) {
		return $status;
	}
	if (empty($status['eligible'])) {
		return array('ok' => false, 'message' => isset($status['message']) ? $status['message'] : 'Tabel tidak memenuhi syarat import.');
	}
	if (empty($status['import_enabled'])) {
		return array(
			'ok' => false,
			'message' => isset($status['import_message']) ? $status['import_message'] : 'Tidak ada data yang bisa disimpan pada bulan terpilih.',
		);
	}

	if (!$CI->db->table_exists('bukubank')) {
		return array('ok' => false, 'message' => 'Tabel `bukubank` tidak ditemukan di database.');
	}

	$valid = bukubank_compare_validate_import_table($CI, $table);
	$map = $valid['map'];
	$ref_year = (int) substr($bulan, 0, 4);
	$ref_month = (int) substr($bulan, 5, 2);
	$all_rows = $CI->db->query('SELECT * FROM `' . $table . '` ORDER BY id ASC')->result();
	$inserted = 0;
	$skipped_out_bulan = 0;
	$skipped_invalid = 0;
	$row_num = 0;

	$CI->load->model('Bukubank_model');
	$CI->db->trans_start();
	foreach ((array) $all_rows as $row) {
		$row_num++;
		$item = bukubank_compare_import_row_from_db($row, $map, $ref_month, $ref_year);
		if (!bukubank_compare_row_matches_bulan($item['tanggal'], $bulan)) {
			$skipped_out_bulan++;
			continue;
		}
		if (!bukubank_compare_is_import_row_saveable($item)) {
			$skipped_invalid++;
			continue;
		}

		$tanggal_db = pembelian_jurnal_compare_normalize_tanggal($item['tanggal']);
		$built = bukubank_compare_build_bukubank_insert_row($CI, $item, $tanggal_db);
		if (empty($built['ok'])) {
			$CI->db->trans_rollback();
			return array(
				'ok' => false,
				'message' => isset($built['message']) ? $built['message'] : 'Gagal menyiapkan data insert bukubank.',
				'failed_row' => $row_num,
			);
		}

		$CI->Bukubank_model->insert($built['data']);
		$db_err = $CI->db->error();
		if (!empty($db_err['message'])) {
			$CI->db->trans_rollback();
			return array(
				'ok' => false,
				'message' => 'Gagal insert baris ke-' . ($inserted + 1) . ' (sumber baris #' . $row_num . ', tanggal ' . $tanggal_db . ').',
				'db_error' => persediaan_compare_db_last_error_message($CI),
				'failed_row' => $row_num,
				'inserted_before_fail' => $inserted,
				'target_columns' => isset($built['target_columns']) ? $built['target_columns'] : array(),
			);
		}
		$inserted++;
	}
	$CI->db->trans_complete();

	if ($CI->db->trans_status() === FALSE) {
		return array(
			'ok' => false,
			'message' => 'Transaksi database gagal saat menyimpan ke bukubank.',
			'db_error' => persediaan_compare_db_last_error_message($CI),
			'inserted_before_fail' => $inserted,
		);
	}

	$message = 'Berhasil menambahkan ' . $inserted . ' data ke bukubank (tanpa cek duplikat).';
	if ($skipped_out_bulan > 0) {
		$message .= ' ' . $skipped_out_bulan . ' baris di luar bulan tidak disimpan.';
	}
	if ($skipped_invalid > 0) {
		$message .= ' ' . $skipped_invalid . ' baris tidak valid (tanggal/debet-kredit) tidak disimpan.';
	}

	return array(
		'ok' => true,
		'inserted' => $inserted,
		'skipped_out_bulan' => $skipped_out_bulan,
		'skipped_invalid' => $skipped_invalid,
		'message' => $message,
	);
}

function bukubank_compare_export_table_detail_excel($CI, $table, $bulan)
{
	@set_time_limit(600);
	@ini_set('memory_limit', '512M');
	$CI->load->helper(array('exportexcel', 'pembelian_persediaan', 'bukubank_list'));

	$result = bukubank_compare_load_table_detail_for_bulan($CI, $table, $bulan);
	if (empty($result['ok'])) {
		xlsBOF();
		xlsWriteLabel(0, 0, isset($result['message']) ? $result['message'] : 'Export Excel gagal.');
		xlsEOF();
		return;
	}

	$headers = isset($result['headers']) ? $result['headers'] : array();
	$rows = isset($result['rows']) ? $result['rows'] : array();
	$bulan_label = isset($result['bulan_label']) ? $result['bulan_label'] : $bulan;
	$styleHeader = 4;
	$styleBorder = 3;
	$styleRight = 8;
	$styleLeft = 7;

	xlsBOF();
	xlsWriteLabelBold14(0, 0, 'Detail Tabel ' . $table . ' — Bulan ' . $bulan_label);
	xlsWriteLabel(1, 0, 'Dicetak: ' . date('d/m/Y H:i:s') . ' | Total baris: ' . count($rows));

	$headerRow = 3;
	foreach ($headers as $col => $label) {
		xlsWriteCellStyle($headerRow, $col, $label, $styleHeader);
	}

	$rowNum = 4;
	foreach ($rows as $item) {
		xlsWriteCellStyle($rowNum, 0, isset($item['no']) ? (int) $item['no'] : 0, $styleBorder);
		xlsWriteCellStyle($rowNum, 1, isset($item['tanggal']) ? $item['tanggal'] : '', $styleLeft);
		xlsWriteCellStyle($rowNum, 2, isset($item['bank']) ? $item['bank'] : '', $styleLeft);
		xlsWriteCellStyle($rowNum, 3, isset($item['norek']) ? $item['norek'] : '', $styleLeft);
		xlsWriteCellStyle($rowNum, 4, isset($item['keterangan']) ? $item['keterangan'] : '', $styleLeft);
		xlsWriteCellStyle($rowNum, 5, isset($item['kode']) ? $item['kode'] : '', $styleLeft);
		$deb = isset($item['debet_raw']) ? (float) $item['debet_raw'] : 0;
		$kre = isset($item['kredit_raw']) ? (float) $item['kredit_raw'] : 0;
		$sal = isset($item['saldo_raw']) ? (float) $item['saldo_raw'] : 0;
		if ($deb > 0) {
			xlsWriteCellStyle($rowNum, 6, bukubank_format_rupiah($deb), $styleRight);
		} else {
			xlsWriteCellStyle($rowNum, 6, '', $styleBorder);
		}
		if ($kre > 0) {
			xlsWriteCellStyle($rowNum, 7, bukubank_format_rupiah($kre), $styleRight);
		} else {
			xlsWriteCellStyle($rowNum, 7, '', $styleBorder);
		}
		if ($sal != 0) {
			xlsWriteCellStyle($rowNum, 8, bukubank_format_rupiah($sal), $styleRight);
		} else {
			xlsWriteCellStyle($rowNum, 8, '', $styleBorder);
		}
		$rowNum++;
	}

	xlsEOF();
}

function bukubank_compare_section_labels()
{
	return array(
		'data_manual' => 'Data Manual',
		'data_online' => 'Data Online',
		'data_cocok' => 'Data Cocok',
		'manual_tidak_di_online' => 'Manual Tidak Ada di Online',
		'online_tidak_di_manual' => 'Online Tidak Ada di Manual',
	);
}

function bukubank_compare_export_section_excel($CI, $bulan, $table, $jenis)
{
	@set_time_limit(600);
	@ini_set('memory_limit', '512M');
	$CI->load->helper(array('exportexcel', 'pembelian_persediaan', 'bukubank_list'));

	$labels = bukubank_compare_section_labels();
	if (!isset($labels[$jenis])) {
		xlsBOF();
		xlsWriteLabel(0, 0, 'Jenis section compare tidak valid.');
		xlsEOF();
		return;
	}

	$result = bukubank_compare_run($CI, $bulan, $table);
	if (empty($result['ok'])) {
		xlsBOF();
		xlsWriteLabel(0, 0, isset($result['message']) ? $result['message'] : 'Compare gagal.');
		xlsEOF();
		return;
	}

	$items = isset($result[$jenis]) ? $result[$jenis] : array();
	$styleHeader = 14;
	$styleBorder = 3;
	$styleRight = 8;
	$styleLeft = 7;
	$headers = bukubank_compare_standard_headers();

	xlsBOF();
	xlsSetColumnWidths(array(5, 12, 8, 10, 12, 24, 28, 14, 14, 36));
	xlsWriteLabelBold14(0, 0, $labels[$jenis] . ' — Compare Buku Bank ' . (isset($result['bulan_label']) ? $result['bulan_label'] : $bulan));
	xlsWriteLabel(1, 0, 'Tabel manual: ' . $table . ' | Dicetak: ' . date('d/m/Y H:i:s') . ' | Baris: ' . count($items));

	$headerRow = 3;
	foreach ($headers as $i => $label) {
		xlsWriteCellStyle($headerRow, $i, $label, $styleHeader);
	}

	$rowNum = 4;
	$no = 0;
	foreach ((array) $items as $item) {
		$no++;
		$cells = bukubank_compare_item_to_row_cells($item, $no);
		foreach ($cells as $col => $val) {
			$style = ($col === 7 || $col === 8) ? $styleRight : (($col === 0) ? $styleBorder : $styleLeft);
			xlsWriteCellStyle($rowNum, $col, $val, $style);
		}
		$rowNum++;
	}

	xlsEOF();
}
