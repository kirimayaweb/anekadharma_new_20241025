<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function jurnal_umum_compare_pick_jurnal_umum_db_column($fields, $logical_key)
{
	$fields = array_map('strval', (array) $fields);
	$candidates = array();
	if ($logical_key === 'kode_rekening') {
		$candidates = array('uraian_kode_rekening', 'kode_rekening');
	} elseif ($logical_key === 'debet') {
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

function jurnal_umum_compare_jurnal_umum_target_column_map($CI)
{
	$fields = $CI->db->list_fields('jurnal_umum');

	return array(
		'tanggal' => jurnal_umum_compare_pick_jurnal_umum_db_column($fields, 'tanggal'),
		'bukti' => jurnal_umum_compare_pick_jurnal_umum_db_column($fields, 'bukti'),
		'pl' => jurnal_umum_compare_pick_jurnal_umum_db_column($fields, 'pl'),
		'ref' => jurnal_umum_compare_pick_jurnal_umum_db_column($fields, 'ref'),
		'kode_rekening' => jurnal_umum_compare_pick_jurnal_umum_db_column($fields, 'kode_rekening'),
		'rekening' => jurnal_umum_compare_pick_jurnal_umum_db_column($fields, 'rekening'),
		'debet' => jurnal_umum_compare_pick_jurnal_umum_db_column($fields, 'debet'),
		'kredit' => jurnal_umum_compare_pick_jurnal_umum_db_column($fields, 'kredit'),
		'_fields' => $fields,
	);
}

function jurnal_umum_compare_row_get_kode_rekening($row)
{
	if (isset($row->kode_rekening)) {
		return trim((string) $row->kode_rekening);
	}
	if (isset($row->uraian_kode_rekening)) {
		return trim((string) $row->uraian_kode_rekening);
	}

	return '';
}

function jurnal_umum_compare_row_get_debet_raw($row)
{
	if (isset($row->debet)) {
		return $row->debet;
	}
	if (isset($row->debit)) {
		return $row->debit;
	}

	return 0;
}

function jurnal_umum_compare_build_jurnal_umum_insert_row($CI, $item, $tanggal_db)
{
	$target = jurnal_umum_compare_jurnal_umum_target_column_map($CI);
	$missing = array();
	if (empty($target['kode_rekening'])) {
		$missing[] = 'kode_rekening atau uraian_kode_rekening';
	}
	if (empty($target['debet'])) {
		$missing[] = 'debet atau debit';
	}
	if (empty($target['kredit'])) {
		$missing[] = 'kredit';
	}
	if (count($missing) > 0) {
		return array(
			'ok' => false,
			'message' => 'Kolom wajib tidak ditemukan di tabel jurnal_umum: ' . implode(', ', $missing) . '.',
		);
	}

	$data = array();
	if (!empty($target['tanggal'])) {
		$data[$target['tanggal']] = $tanggal_db;
	}
	if (!empty($target['bukti'])) {
		$data[$target['bukti']] = $item['bukti'] !== '' ? $item['bukti'] : '';
	}
	if (!empty($target['pl'])) {
		$data[$target['pl']] = $item['pl'] !== '' ? $item['pl'] : '';
	}
	if (!empty($target['ref'])) {
		$data[$target['ref']] = $item['ref'] !== '' ? $item['ref'] : '';
	}
	if (!empty($target['rekening'])) {
		$data[$target['rekening']] = $item['rekening'] !== '' ? $item['rekening'] : '';
	}

	$data[$target['kode_rekening']] = $item['kode_rekening'] !== '' ? $item['kode_rekening'] : '';
	$data[$target['debet']] = $item['debet'] > 0 ? $item['debet'] : 0;
	$data[$target['kredit']] = $item['kredit'] > 0 ? $item['kredit'] : 0;

	return array('ok' => true, 'data' => $data, 'target_columns' => $target);
}

function jurnal_umum_compare_field_definitions()
{
	return array(
		'tanggal' => array('label' => 'tanggal', 'required' => true, 'aliases' => array('tanggal', 'tgl', 'date', 'tgl_transaksi')),
		'bukti' => array('label' => 'bukti', 'required' => true, 'aliases' => array('bukti', 'no_bukti', 'nobukti', 'no bukti')),
		'pl' => array('label' => 'pl', 'required' => false, 'aliases' => array('pl', 'kode_pl', 'profit center')),
		'ref' => array('label' => 'ref', 'required' => false, 'aliases' => array('ref', 'referensi', 'reference')),
		'kode_rekening' => array('label' => 'kode_rekening', 'required' => true, 'aliases' => array('kode_rekening', 'kode_rek', 'uraian_kode_rekening', 'kode_akun', 'kode akun', 'kode')),
		'rekening' => array('label' => 'rekening', 'required' => false, 'aliases' => array('rekening', 'nama_rekening', 'nama akun', 'uraian')),
		'debet' => array('label' => 'debet', 'required' => false, 'aliases' => array('debet', 'debit')),
		'kredit' => array('label' => 'kredit', 'required' => false, 'aliases' => array('kredit', 'credit')),
	);
}

function jurnal_umum_compare_build_column_map($fields)
{
	$analysis = jurnal_umum_compare_analyze_column_map($fields);
	return !empty($analysis['ok']) ? $analysis['map'] : null;
}

function jurnal_umum_compare_analyze_column_map($fields)
{
	if (!is_array($fields) || count($fields) === 0) {
		return array('ok' => false, 'message' => 'Tabel tidak memiliki kolom.');
	}

	$normalized = array();
	foreach ($fields as $field) {
		$normalized[] = trim((string) $field);
	}

	$defs = jurnal_umum_compare_field_definitions();
	$map = array(
		'tanggal' => penjualan_jurnal_compare_pick_tanggal_column($normalized),
		'bukti' => persediaan_compare_pick_column($normalized, $defs['bukti']['aliases']),
		'pl' => persediaan_compare_pick_column($normalized, $defs['pl']['aliases']),
		'ref' => persediaan_compare_pick_column($normalized, $defs['ref']['aliases']),
		'kode_rekening' => persediaan_compare_pick_column($normalized, $defs['kode_rekening']['aliases']),
		'rekening' => persediaan_compare_pick_column($normalized, $defs['rekening']['aliases']),
		'debet' => persediaan_compare_pick_column($normalized, $defs['debet']['aliases']),
		'kredit' => persediaan_compare_pick_column($normalized, $defs['kredit']['aliases']),
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
			. '. Kolom compare: tanggal, bukti, kode_rekening, debet atau kredit.';
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

function jurnal_umum_compare_validate_online_table_detail($CI)
{
	if (!$CI->db->table_exists('jurnal_umum')) {
		return array(
			'ok' => false,
			'message' => 'Tabel online `jurnal_umum` tidak ditemukan di database.',
			'missing_fields' => array('jurnal_umum (tabel)'),
		);
	}

	$fields = $CI->db->list_fields('jurnal_umum');
	$map = array(
		'tanggal' => jurnal_umum_compare_pick_jurnal_umum_db_column($fields, 'tanggal'),
		'bukti' => jurnal_umum_compare_pick_jurnal_umum_db_column($fields, 'bukti'),
		'pl' => jurnal_umum_compare_pick_jurnal_umum_db_column($fields, 'pl'),
		'ref' => jurnal_umum_compare_pick_jurnal_umum_db_column($fields, 'ref'),
		'kode_rekening' => jurnal_umum_compare_pick_jurnal_umum_db_column($fields, 'kode_rekening'),
		'rekening' => jurnal_umum_compare_pick_jurnal_umum_db_column($fields, 'rekening'),
		'debet' => jurnal_umum_compare_pick_jurnal_umum_db_column($fields, 'debet'),
		'kredit' => jurnal_umum_compare_pick_jurnal_umum_db_column($fields, 'kredit'),
	);

	$critical_missing = array();
	if (empty($map['tanggal'])) {
		$critical_missing[] = 'tanggal';
	}
	if (empty($map['debet']) && empty($map['kredit'])) {
		$critical_missing[] = 'debet/debit atau kredit';
	}

	$soft_missing = array();
	foreach (array('bukti', 'pl', 'ref', 'kode_rekening', 'rekening') as $k) {
		if (empty($map[$k])) {
			$soft_missing[] = $k;
		}
	}

	$ok = count($critical_missing) === 0;
	$message = '';
	if (!$ok) {
		$message = 'Tabel online `jurnal_umum` tidak memiliki kolom wajib: ' . implode(', ', $critical_missing) . '.';
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
		'table' => 'jurnal_umum',
		'map' => $map,
		'mapped' => $mapped,
		'missing_fields' => array_merge($critical_missing, $soft_missing),
		'critical_missing' => $critical_missing,
		'soft_missing' => $soft_missing,
		'fields' => $fields,
		'message' => $message,
	);
}

function jurnal_umum_compare_validate_table($CI, $table)
{
	if (!persediaan_compare_is_valid_table_name($table)) {
		return array('ok' => false, 'message' => 'Nama tabel tidak valid.');
	}
	if (!$CI->db->table_exists($table)) {
		return array('ok' => false, 'message' => 'Tabel tidak ditemukan di database.');
	}

	$fields = $CI->db->list_fields($table);
	$analysis = jurnal_umum_compare_analyze_column_map($fields);
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

function jurnal_umum_compare_normalize_tanggal_for_db($value, $ref_month = 0, $ref_year = 0)
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

function jurnal_umum_compare_row_matches_bulan($tanggal_ymd, $bulan)
{
	$tanggal_ymd = pembelian_jurnal_compare_normalize_tanggal($tanggal_ymd);
	if ($tanggal_ymd === '' || !preg_match('/^\d{4}-\d{2}$/', (string) $bulan)) {
		return false;
	}

	return substr($tanggal_ymd, 0, 7) === $bulan;
}

function jurnal_umum_compare_normalize_bukti($bukti)
{
	return strtoupper(trim((string) $bukti));
}

function jurnal_umum_compare_normalize_text($value)
{
	return strtolower(trim(preg_replace('/\s+/', ' ', (string) $value)));
}

function jurnal_umum_compare_normalize_jumlah($value)
{
	return round(pembelian_jurnal_compare_parse_jumlah_nominal($value), 2);
}

function jurnal_umum_compare_format_jumlah_display($jumlah)
{
	if ($jumlah === null || $jumlah === '' || (float) $jumlah == 0) {
		return '';
	}
	return number_format((float) $jumlah, 2, ',', '.');
}

function jurnal_umum_compare_standard_headers()
{
	return array('No', 'Tanggal', 'Bukti', 'PL', 'Ref', 'Kode Rek.', 'Rek.', 'Debet', 'Kredit', 'Catatan');
}

function jurnal_umum_compare_is_row_analyzable($row)
{
	$tanggal = pembelian_jurnal_compare_normalize_tanggal(isset($row['tanggal']) ? $row['tanggal'] : '');
	if ($tanggal === '' || $tanggal === '0000-00-00') {
		return false;
	}
	$deb = jurnal_umum_compare_normalize_jumlah(isset($row['debet']) ? $row['debet'] : 0);
	$kre = jurnal_umum_compare_normalize_jumlah(isset($row['kredit']) ? $row['kredit'] : 0);
	return ($deb > 0 || $kre > 0);
}

function jurnal_umum_compare_row_unprocessed_reasons($row)
{
	$reasons = array();
	if (pembelian_jurnal_compare_normalize_tanggal(isset($row['tanggal']) ? $row['tanggal'] : '') === '') {
		$reasons[] = 'tanggal kosong/tidak valid';
	}
	if (jurnal_umum_compare_normalize_jumlah(isset($row['debet']) ? $row['debet'] : 0) <= 0
		&& jurnal_umum_compare_normalize_jumlah(isset($row['kredit']) ? $row['kredit'] : 0) <= 0) {
		$reasons[] = 'debet dan kredit kosong';
	}
	return $reasons;
}

function jurnal_umum_compare_make_hard_key($tanggal, $bukti, $pl, $ref, $kode_rekening)
{
	return pembelian_jurnal_compare_normalize_tanggal($tanggal)
		. '|' . jurnal_umum_compare_normalize_bukti($bukti)
		. '|' . jurnal_umum_compare_normalize_text($pl)
		. '|' . jurnal_umum_compare_normalize_text($ref)
		. '|' . penjualan_jurnal_compare_normalize_kode_rekening($kode_rekening);
}

function jurnal_umum_compare_make_full_key($row)
{
	return jurnal_umum_compare_make_hard_key(
		isset($row['tanggal']) ? $row['tanggal'] : '',
		isset($row['bukti']) ? $row['bukti'] : '',
		isset($row['pl']) ? $row['pl'] : '',
		isset($row['ref']) ? $row['ref'] : '',
		isset($row['kode_rekening']) ? $row['kode_rekening'] : ''
	)
		. '|' . jurnal_umum_compare_normalize_text(isset($row['rekening']) ? $row['rekening'] : '')
		. '|' . jurnal_umum_compare_normalize_jumlah(isset($row['debet']) ? $row['debet'] : 0)
		. '|' . jurnal_umum_compare_normalize_jumlah(isset($row['kredit']) ? $row['kredit'] : 0);
}

function jurnal_umum_compare_row_to_display($row, $catatan = '')
{
	$debet = jurnal_umum_compare_normalize_jumlah(isset($row['debet']) ? $row['debet'] : 0);
	$kredit = jurnal_umum_compare_normalize_jumlah(isset($row['kredit']) ? $row['kredit'] : 0);

	return array(
		'tanggal' => isset($row['tanggal']) ? pembelian_jurnal_compare_format_tanggal_display($row['tanggal']) : '',
		'bukti' => isset($row['bukti']) ? $row['bukti'] : '',
		'pl' => isset($row['pl']) ? $row['pl'] : '',
		'ref' => isset($row['ref']) ? $row['ref'] : '',
		'kode_rekening' => isset($row['kode_rekening']) ? $row['kode_rekening'] : '',
		'rekening' => isset($row['rekening']) ? $row['rekening'] : '',
		'debet' => $debet > 0 ? jurnal_umum_compare_format_jumlah_display($debet) : '',
		'kredit' => $kredit > 0 ? jurnal_umum_compare_format_jumlah_display($kredit) : '',
		'catatan' => $catatan,
	);
}

function jurnal_umum_compare_build_diff_catatan($row, $other, $other_label)
{
	if (!is_array($row) || !is_array($other)) {
		return 'Tidak ditemukan pasangan di data ' . $other_label . '.';
	}

	$parts = array('Perbedaan dengan data ' . $other_label . ':');
	$fields = array(
		'pl' => 'PL',
		'ref' => 'Ref',
		'rekening' => 'Rekening',
		'kode_rekening' => 'Kode rekening',
	);

	foreach ($fields as $key => $label) {
		if (jurnal_umum_compare_normalize_text($row[$key]) !== jurnal_umum_compare_normalize_text($other[$key])) {
			$parts[] = $label . ' berbeda (Manual: ' . $row[$key] . ', ' . ucfirst($other_label) . ': ' . $other[$key] . ')';
		}
	}

	$deb_r = jurnal_umum_compare_normalize_jumlah($row['debet']);
	$deb_o = jurnal_umum_compare_normalize_jumlah($other['debet']);
	if ($deb_r !== $deb_o) {
		$parts[] = 'Debet berbeda (Manual: ' . jurnal_umum_compare_format_jumlah_display($deb_r)
			. ', ' . ucfirst($other_label) . ': ' . jurnal_umum_compare_format_jumlah_display($deb_o) . ')';
	}

	$kre_r = jurnal_umum_compare_normalize_jumlah($row['kredit']);
	$kre_o = jurnal_umum_compare_normalize_jumlah($other['kredit']);
	if ($kre_r !== $kre_o) {
		$parts[] = 'Kredit berbeda (Manual: ' . jurnal_umum_compare_format_jumlah_display($kre_r)
			. ', ' . ucfirst($other_label) . ': ' . jurnal_umum_compare_format_jumlah_display($kre_o) . ')';
	}

	if (count($parts) === 1) {
		return 'Tidak ditemukan pasangan lengkap di data ' . $other_label . ' (semua field compare harus sama).';
	}

	return implode('; ', $parts);
}

function jurnal_umum_compare_manual_row_from_db($row, $map, $default_tanggal = '')
{
	$tanggal_raw = !empty($map['tanggal']) ? persediaan_compare_row_get($row, $map['tanggal']) : $default_tanggal;
	$tanggal_norm = pembelian_jurnal_compare_normalize_tanggal($tanggal_raw);
	if (($tanggal_norm === '' || $tanggal_norm === '0000-00-00') && $default_tanggal !== '') {
		$tanggal_norm = pembelian_jurnal_compare_normalize_tanggal($default_tanggal);
	}

	return array(
		'tanggal' => $tanggal_norm,
		'bukti' => trim((string) (!empty($map['bukti']) ? persediaan_compare_row_get($row, $map['bukti']) : '')),
		'pl' => trim((string) (!empty($map['pl']) ? persediaan_compare_row_get($row, $map['pl']) : '')),
		'ref' => trim((string) (!empty($map['ref']) ? persediaan_compare_row_get($row, $map['ref']) : '')),
		'kode_rekening' => trim((string) (!empty($map['kode_rekening']) ? persediaan_compare_row_get($row, $map['kode_rekening']) : '')),
		'rekening' => trim((string) (!empty($map['rekening']) ? persediaan_compare_row_get($row, $map['rekening']) : '')),
		'debet' => jurnal_umum_compare_normalize_jumlah(!empty($map['debet']) ? persediaan_compare_row_get($row, $map['debet']) : 0),
		'kredit' => jurnal_umum_compare_normalize_jumlah(!empty($map['kredit']) ? persediaan_compare_row_get($row, $map['kredit']) : 0),
	);
}

function jurnal_umum_compare_load_manual_rows_for_bulan($CI, $table, $bulan)
{
	$valid = jurnal_umum_compare_validate_table($CI, $table);
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
		$item = jurnal_umum_compare_manual_row_from_db($row, $valid['map'], $default_tanggal);
		if (jurnal_umum_compare_row_matches_bulan($item['tanggal'], $bulan)) {
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

function jurnal_umum_compare_load_manual_all($CI, $table, $bulan)
{
	$valid = jurnal_umum_compare_validate_table($CI, $table);
	if (empty($valid['ok'])) {
		return $valid;
	}

	$loaded = jurnal_umum_compare_load_manual_rows_for_bulan($CI, $table, $bulan);
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
		$item = jurnal_umum_compare_manual_row_from_db($row, $map, $default_tanggal);
		$reasons = jurnal_umum_compare_row_unprocessed_reasons($item);
		$display_all[] = jurnal_umum_compare_row_to_display(
			$item,
			count($reasons) > 0 ? ('Info: ' . implode(', ', $reasons)) : ''
		);

		if (!jurnal_umum_compare_is_row_analyzable($item)) {
			$unprocessed[] = jurnal_umum_compare_row_to_display($item, 'Manual tidak terproses: ' . implode(', ', $reasons));
			continue;
		}

		$full_key = jurnal_umum_compare_make_full_key($item);
		$hard_key = jurnal_umum_compare_make_hard_key($item['tanggal'], $item['bukti'], $item['pl'], $item['ref'], $item['kode_rekening']);
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

function jurnal_umum_compare_online_row_from_db($row)
{
	return array(
		'tanggal' => pembelian_jurnal_compare_normalize_tanggal(isset($row->tanggal) ? $row->tanggal : ''),
		'bukti' => isset($row->bukti) ? trim((string) $row->bukti) : '',
		'pl' => isset($row->pl) ? trim((string) $row->pl) : '',
		'ref' => isset($row->ref) ? trim((string) $row->ref) : '',
		'kode_rekening' => jurnal_umum_compare_row_get_kode_rekening($row),
		'rekening' => isset($row->rekening) ? trim((string) $row->rekening) : '',
		'debet' => jurnal_umum_compare_normalize_jumlah(jurnal_umum_compare_row_get_debet_raw($row)),
		'kredit' => jurnal_umum_compare_normalize_jumlah(isset($row->kredit) ? $row->kredit : 0),
	);
}

function jurnal_umum_compare_load_online_all($CI, $bulan)
{
	$range = persediaan_compare_bulan_to_date_range($bulan);
	if ($range === null) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');
	}

	$online_fields = jurnal_umum_compare_validate_online_table_detail($CI);
	if (empty($online_fields['ok'])) {
		return array(
			'ok' => false,
			'message' => isset($online_fields['message']) ? $online_fields['message'] : 'Struktur tabel online jurnal_umum tidak valid.',
			'field_validation' => array('online' => $online_fields),
		);
	}

	$month = (int) substr($bulan, 5, 2);
	$year = (int) substr($bulan, 0, 4);
	$col_kr = $online_fields['map']['kode_rekening'];
	$col_db = $online_fields['map']['debet'];
	$col_kr_sql = '`' . str_replace('`', '``', $col_kr) . '`';
	$col_db_sql = '`' . str_replace('`', '``', $col_db) . '`';

	$sql = "SELECT DATE(tanggal) AS tanggal,
		COALESCE(NULLIF(TRIM(bukti), ''), '') AS bukti,
		COALESCE(NULLIF(TRIM(pl), ''), '') AS pl,
		COALESCE(NULLIF(TRIM(ref), ''), '') AS ref,
		COALESCE(NULLIF(TRIM({$col_kr_sql}), ''), '') AS kode_rekening,
		COALESCE(NULLIF(TRIM(rekening), ''), '') AS rekening,
		COALESCE({$col_db_sql}, 0) AS debet,
		COALESCE(kredit, 0) AS kredit
		FROM jurnal_umum
		WHERE tanggal IS NOT NULL AND tanggal <> '0000-00-00'
		AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?
		ORDER BY tanggal, nomor";

	$list = array();
	$by_full = array();
	$by_hard = array();
	$unprocessed = array();
	$display_all = array();

	foreach ($CI->db->query($sql, array($month, $year))->result() as $row) {
		$item = jurnal_umum_compare_online_row_from_db($row);
		$reasons = jurnal_umum_compare_row_unprocessed_reasons($item);
		$display_all[] = jurnal_umum_compare_row_to_display(
			$item,
			count($reasons) > 0 ? ('Info: ' . implode(', ', $reasons)) : ''
		);

		if (!jurnal_umum_compare_is_row_analyzable($item)) {
			$unprocessed[] = jurnal_umum_compare_row_to_display($item, 'Online tidak terproses: ' . implode(', ', $reasons));
			continue;
		}

		$full_key = jurnal_umum_compare_make_full_key($item);
		$hard_key = jurnal_umum_compare_make_hard_key($item['tanggal'], $item['bukti'], $item['pl'], $item['ref'], $item['kode_rekening']);
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

function jurnal_umum_compare_pick_best_candidate($row, $candidates)
{
	if (!is_array($candidates) || count($candidates) === 0) {
		return null;
	}

	$best = null;
	$best_score = -1;
	foreach ($candidates as $candidate) {
		$score = 0;
		if (jurnal_umum_compare_normalize_text($row['rekening']) === jurnal_umum_compare_normalize_text($candidate['rekening'])) {
			$score += 3;
		}
		if (jurnal_umum_compare_normalize_jumlah($row['debet']) === jurnal_umum_compare_normalize_jumlah($candidate['debet'])) {
			$score += 2;
		}
		if (jurnal_umum_compare_normalize_jumlah($row['kredit']) === jurnal_umum_compare_normalize_jumlah($candidate['kredit'])) {
			$score += 2;
		}
		if ($score > $best_score) {
			$best_score = $score;
			$best = $candidate;
		}
	}

	return $best;
}

function jurnal_umum_compare_sort_display_rows($a, $b)
{
	$cmp = strcmp((string) $a['tanggal'], (string) $b['tanggal']);
	if ($cmp !== 0) {
		return $cmp;
	}
	$cmp = strcmp((string) $a['bukti'], (string) $b['bukti']);
	if ($cmp !== 0) {
		return $cmp;
	}
	return strcmp((string) $a['kode_rekening'], (string) $b['kode_rekening']);
}

function jurnal_umum_compare_run($CI, $bulan, $table = '')
{
	$table = trim((string) $table);
	if ($table === '') {
		return array('ok' => false, 'message' => 'Pilih tabel manual yang akan dibandingkan.');
	}

	$online_fields = jurnal_umum_compare_validate_online_table_detail($CI);
	if (empty($online_fields['ok'])) {
		return array(
			'ok' => false,
			'message' => isset($online_fields['message']) ? $online_fields['message'] : 'Struktur tabel online jurnal_umum tidak valid.',
			'field_validation' => array('online' => $online_fields),
		);
	}

	$manual = jurnal_umum_compare_load_manual_all($CI, $table, $bulan);
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

	$online = jurnal_umum_compare_load_online_all($CI, $bulan);
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
			$data_cocok[] = jurnal_umum_compare_row_to_display($manual_row, 'Data manual dan online cocok');
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
			$best = jurnal_umum_compare_pick_best_candidate($manual_row, $candidates);
			foreach ($online_pool as $o_idx => $online_row) {
				if ($best !== null && $online_row === $best) {
					$online_used[$o_idx] = true;
					break;
				}
			}
			$manual_tidak[] = jurnal_umum_compare_row_to_display(
				$manual_row,
				jurnal_umum_compare_build_diff_catatan($manual_row, $best, 'online')
			);
		} else {
			$manual_tidak[] = jurnal_umum_compare_row_to_display(
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
			$best = jurnal_umum_compare_pick_best_candidate($online_row, $candidates);
			$online_tidak[] = jurnal_umum_compare_row_to_display(
				$online_row,
				jurnal_umum_compare_build_diff_catatan($online_row, $best, 'manual')
			);
		} else {
			$online_tidak[] = jurnal_umum_compare_row_to_display(
				$online_row,
				'Tidak ditemukan di data manual.'
			);
		}
	}

	usort($data_cocok, 'jurnal_umum_compare_sort_display_rows');
	usort($manual_tidak, 'jurnal_umum_compare_sort_display_rows');
	usort($online_tidak, 'jurnal_umum_compare_sort_display_rows');

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

function jurnal_umum_compare_item_to_row_cells($item, $no)
{
	return array(
		$no,
		isset($item['tanggal']) ? $item['tanggal'] : '',
		isset($item['bukti']) ? $item['bukti'] : '',
		isset($item['pl']) ? $item['pl'] : '',
		isset($item['ref']) ? $item['ref'] : '',
		isset($item['kode_rekening']) ? $item['kode_rekening'] : '',
		isset($item['rekening']) ? $item['rekening'] : '',
		isset($item['debet']) ? $item['debet'] : '',
		isset($item['kredit']) ? $item['kredit'] : '',
		isset($item['catatan']) ? $item['catatan'] : '',
	);
}

function jurnal_umum_compare_excel_all_total_cols()
{
	return 54;
}

function jurnal_umum_compare_excel_all_group_definitions()
{
	return array(
		array('title' => 'DATA MANUAL', 'col_start' => 0, 'col_end' => 9),
		array('title' => 'DATA ONLINE', 'col_start' => 11, 'col_end' => 20),
		array('title' => 'DATA COCOK', 'col_start' => 22, 'col_end' => 31),
		array('title' => 'MANUAL TIDAK DI ONLINE', 'col_start' => 33, 'col_end' => 42),
		array('title' => 'ONLINE TIDAK DI MANUAL', 'col_start' => 44, 'col_end' => 53),
	);
}

function jurnal_umum_compare_excel_all_headers()
{
	$block = jurnal_umum_compare_standard_headers();
	return array_merge($block, array(''), $block, array(''), $block, array(''), $block, array(''), $block);
}

function jurnal_umum_compare_excel_sheet_name_from_bulan($bulan)
{
	$bulan = trim((string) $bulan);
	if (!preg_match('/^(\d{4})-(\d{2})$/', $bulan, $m)) {
		return 'Compare Jurnal Umum';
	}
	$tahun = (int) $m[1];
	$bulan_num = (int) $m[2];
	$nama_bulan = persediaan_compare_excel_all_bulan_nama_id();
	$label_bulan = isset($nama_bulan[$bulan_num]) ? $nama_bulan[$bulan_num] : ('Bulan ' . $bulan_num);
	return excel_sheet_safe_name('Compare Jurnal Umum ' . $label_bulan . ' ' . $tahun);
}

function jurnal_umum_compare_excel_items_to_cells($items)
{
	$rows = array();
	$no = 0;
	foreach ((array) $items as $item) {
		$no++;
		$rows[] = jurnal_umum_compare_item_to_row_cells($item, $no);
	}
	return $rows;
}

function jurnal_umum_compare_excel_all_empty_row()
{
	return array_fill(0, jurnal_umum_compare_excel_all_total_cols(), '');
}

function jurnal_umum_compare_excel_all_write_merged_rows($rowNum, $sections, $groups)
{
	$maxLen = 0;
	foreach ($sections as $sectionRows) {
		$maxLen = max($maxLen, count((array) $sectionRows));
	}
	for ($i = 0; $i < $maxLen; $i++) {
		$cells = jurnal_umum_compare_excel_all_empty_row();
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

function jurnal_umum_compare_export_excel_all_output($CI, $bulan, $table = '', $result = null)
{
	@set_time_limit(600);
	@ini_set('memory_limit', '512M');
	if ($result === null) {
		$result = jurnal_umum_compare_run($CI, $bulan, $table);
	}
	if (empty($result['ok'])) {
		xlsAddSheet('Compare Jurnal Umum');
		xlsWriteLabel(0, 0, isset($result['message']) ? $result['message'] : 'Export Excel gagal.');
		xlsMultiEOF();
		return;
	}

	$groups = jurnal_umum_compare_excel_all_group_definitions();
	$headers = jurnal_umum_compare_excel_all_headers();
	$groupTitleRow = 2;
	$headerRow = 3;
	$dataStartRow = 4;
	$stats = isset($result['stats']) ? $result['stats'] : array();

	xlsAddSheet(jurnal_umum_compare_excel_sheet_name_from_bulan($bulan));
	xlsWriteLabelBold14(0, 0, 'Compare Jurnal Umum — Manual vs Online — Bulan ' . $result['bulan_label']);
	xlsWriteLabel(1, 0, 'Tabel manual: ' . $result['table']
		. ' | Dicetak: ' . date('d/m/Y H:i:s')
		. ' | Manual: ' . (isset($stats['data_manual']) ? (int) $stats['data_manual'] : 0)
		. ' | Online: ' . (isset($stats['data_online']) ? (int) $stats['data_online'] : 0)
		. ' | Cocok: ' . (isset($stats['data_cocok']) ? (int) $stats['data_cocok'] : 0));

	persediaan_rekonsiliasi_tx_write_group_titles($groupTitleRow, $groups);
	persediaan_rekonsiliasi_tx_write_headers_grouped($headerRow, $headers, $groups);

	$sections = array(
		0 => jurnal_umum_compare_excel_items_to_cells(isset($result['data_manual']) ? $result['data_manual'] : array()),
		11 => jurnal_umum_compare_excel_items_to_cells(isset($result['data_online']) ? $result['data_online'] : array()),
		22 => jurnal_umum_compare_excel_items_to_cells(isset($result['data_cocok']) ? $result['data_cocok'] : array()),
		33 => jurnal_umum_compare_excel_items_to_cells(isset($result['manual_tidak_di_online']) ? $result['manual_tidak_di_online'] : array()),
		44 => jurnal_umum_compare_excel_items_to_cells(isset($result['online_tidak_di_manual']) ? $result['online_tidak_di_manual'] : array()),
	);

	$lastRow = jurnal_umum_compare_excel_all_write_merged_rows($dataStartRow, $sections, $groups);
	if ($lastRow < $dataStartRow) {
		$lastRow = $headerRow;
	}
	persediaan_rekonsiliasi_tx_finalize_group_borders($groupTitleRow, $lastRow, $groups);
	xlsMultiEOF();
}

function jurnal_umum_compare_export_excel_output($CI, $bulan, $table = '')
{
	@set_time_limit(600);
	@ini_set('memory_limit', '512M');
	$CI->load->helper(array('exportexcel', 'persediaan_display', 'pembelian_persediaan'));

	$result = jurnal_umum_compare_run($CI, $bulan, $table);
	if (empty($result['ok'])) {
		xlsBOF();
		xlsWriteLabel(0, 0, isset($result['message']) ? $result['message'] : 'Compare gagal.');
		xlsEOF();
		return;
	}

	jurnal_umum_compare_export_excel_all_output($CI, $bulan, $table, $result);
}

function jurnal_umum_compare_validate_csv_file($filepath)
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

	$map = jurnal_umum_compare_build_column_map(persediaan_compare_sanitize_csv_headers($raw_headers));
	if ($map === null) {
		return array(
			'ok' => false,
			'stage' => 'validate_columns',
			'message' => 'Header CSV tidak memenuhi syarat Jurnal Umum. Kolom wajib: tanggal, bukti, kode_rekening, debet atau kredit.',
		);
	}

	return array('ok' => true, 'map' => $map, 'headers' => $raw_headers);
}

function jurnal_umum_compare_import_csv_to_db($CI, $filepath, $original_filename, $bulan_key = '', $bulan_num = 0, $tahun = 0)
{
	$file_label = trim((string) $original_filename);
	if ($file_label === '') {
		$file_label = basename((string) $filepath);
	}

	$validated = jurnal_umum_compare_validate_csv_file($filepath);
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
	$column_map = jurnal_umum_compare_build_column_map($db_columns_sanitized);
	$csv_col_index = array();
	foreach ($db_columns_sanitized as $idx => $col) {
		$csv_col_index[$col] = $idx;
	}

	$col_tanggal = !empty($column_map['tanggal']) ? $column_map['tanggal'] : 'tanggal';
	$col_bukti = !empty($column_map['bukti']) ? $column_map['bukti'] : 'bukti';
	$col_pl = !empty($column_map['pl']) ? $column_map['pl'] : 'pl';
	$col_ref = !empty($column_map['ref']) ? $column_map['ref'] : 'ref';
	$col_kode_rekening = !empty($column_map['kode_rekening']) ? $column_map['kode_rekening'] : 'kode_rekening';
	$col_rekening = !empty($column_map['rekening']) ? $column_map['rekening'] : 'rekening';
	$col_debet = !empty($column_map['debet']) ? $column_map['debet'] : 'debet';
	$col_kredit = !empty($column_map['kredit']) ? $column_map['kredit'] : 'kredit';

	$db_columns = array();
	foreach ($db_columns_sanitized as $col) {
		if (strtolower($col) === 'id') {
			continue;
		}
		$db_columns[] = $col;
	}
	foreach (array($col_tanggal, $col_bukti, $col_pl, $col_ref, $col_kode_rekening, $col_rekening, $col_debet, $col_kredit) as $required_col) {
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

		$data[$col_tanggal] = jurnal_umum_compare_normalize_tanggal_for_db(
			isset($data[$col_tanggal]) ? $data[$col_tanggal] : '',
			$bulan_tahun_ref['month'],
			$bulan_tahun_ref['year']
		);

		if ($col_debet !== '') {
			$data[$col_debet] = jurnal_umum_compare_normalize_jumlah(isset($data[$col_debet]) ? $data[$col_debet] : 0);
		}
		if ($col_kredit !== '') {
			$data[$col_kredit] = jurnal_umum_compare_normalize_jumlah(isset($data[$col_kredit]) ? $data[$col_kredit] : 0);
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

function jurnal_umum_compare_import_field_definitions()
{
	return array(
		'tanggal' => array('label' => 'tanggal', 'required' => true, 'aliases' => array('tanggal', 'tgl', 'date', 'tgl_transaksi')),
		'bukti' => array('label' => 'bukti', 'required' => false, 'aliases' => array('bukti', 'no_bukti', 'nobukti', 'no bukti')),
		'pl' => array('label' => 'pl', 'required' => false, 'aliases' => array('pl', 'kode_pl', 'profit center')),
		'ref' => array('label' => 'ref', 'required' => false, 'aliases' => array('ref', 'referensi', 'reference')),
		'kode_rekening' => array('label' => 'kode_rekening', 'required' => false, 'aliases' => array('kode_rekening', 'kode_rek', 'uraian_kode_rekening', 'kode_akun', 'kode akun', 'kode')),
		'rekening' => array('label' => 'rekening', 'required' => false, 'aliases' => array('rekening', 'nama_rekening', 'nama akun', 'uraian')),
		'debet' => array('label' => 'debet', 'required' => false, 'aliases' => array('debet', 'debit')),
		'kredit' => array('label' => 'kredit', 'required' => false, 'aliases' => array('kredit', 'credit')),
	);
}

function jurnal_umum_compare_is_import_row_saveable($row)
{
	$tanggal = pembelian_jurnal_compare_normalize_tanggal(isset($row['tanggal']) ? $row['tanggal'] : '');
	if ($tanggal === '' || $tanggal === '0000-00-00') {
		return false;
	}
	$deb = jurnal_umum_compare_normalize_jumlah(isset($row['debet']) ? $row['debet'] : 0);
	$kre = jurnal_umum_compare_normalize_jumlah(isset($row['kredit']) ? $row['kredit'] : 0);

	return ($deb > 0 || $kre > 0);
}

function jurnal_umum_compare_analyze_import_column_map($fields)
{
	if (!is_array($fields) || count($fields) === 0) {
		return array('ok' => false, 'message' => 'Tabel tidak memiliki kolom.');
	}

	$normalized = array();
	foreach ($fields as $field) {
		$normalized[] = trim((string) $field);
	}

	$defs = jurnal_umum_compare_import_field_definitions();
	$map = array(
		'tanggal' => penjualan_jurnal_compare_pick_tanggal_column($normalized),
		'bukti' => persediaan_compare_pick_column($normalized, $defs['bukti']['aliases']),
		'pl' => persediaan_compare_pick_column($normalized, $defs['pl']['aliases']),
		'ref' => persediaan_compare_pick_column($normalized, $defs['ref']['aliases']),
		'kode_rekening' => persediaan_compare_pick_column($normalized, $defs['kode_rekening']['aliases']),
		'rekening' => persediaan_compare_pick_column($normalized, $defs['rekening']['aliases']),
		'debet' => persediaan_compare_pick_column($normalized, $defs['debet']['aliases']),
		'kredit' => persediaan_compare_pick_column($normalized, $defs['kredit']['aliases']),
	);

	$missing_required = array();
	$mapped = array();
	foreach ($defs as $key => $def) {
		if (!empty($map[$key])) {
			$mapped[$key] = $map[$key];
		} elseif (!empty($def['required'])) {
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
			. '. Kolom import minimal: tanggal, debet atau kredit.';
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

function jurnal_umum_compare_validate_import_table($CI, $table)
{
	if (!persediaan_compare_is_valid_table_name($table)) {
		return array('ok' => false, 'message' => 'Nama tabel tidak valid.');
	}
	if (!$CI->db->table_exists($table)) {
		return array('ok' => false, 'message' => 'Tabel tidak ditemukan di database.');
	}

	$fields = $CI->db->list_fields($table);
	$analysis = jurnal_umum_compare_analyze_import_column_map($fields);
	if (empty($analysis['ok'])) {
		return array(
			'ok' => false,
			'message' => isset($analysis['message']) ? $analysis['message'] : 'Struktur tabel tidak valid untuk import jurnal umum.',
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

function jurnal_umum_compare_import_row_from_db($row, $map, $ref_month = 0, $ref_year = 0)
{
	$tanggal_raw = !empty($map['tanggal']) ? persediaan_compare_row_get($row, $map['tanggal']) : '';
	$tanggal_norm = jurnal_umum_compare_normalize_tanggal_for_db($tanggal_raw, $ref_month, $ref_year);

	return array(
		'tanggal' => $tanggal_norm,
		'bukti' => trim((string) (!empty($map['bukti']) ? persediaan_compare_row_get($row, $map['bukti']) : '')),
		'pl' => trim((string) (!empty($map['pl']) ? persediaan_compare_row_get($row, $map['pl']) : '')),
		'ref' => trim((string) (!empty($map['ref']) ? persediaan_compare_row_get($row, $map['ref']) : '')),
		'kode_rekening' => trim((string) (!empty($map['kode_rekening']) ? persediaan_compare_row_get($row, $map['kode_rekening']) : '')),
		'rekening' => trim((string) (!empty($map['rekening']) ? persediaan_compare_row_get($row, $map['rekening']) : '')),
		'debet' => jurnal_umum_compare_normalize_jumlah(!empty($map['debet']) ? persediaan_compare_row_get($row, $map['debet']) : 0),
		'kredit' => jurnal_umum_compare_normalize_jumlah(!empty($map['kredit']) ? persediaan_compare_row_get($row, $map['kredit']) : 0),
	);
}

function jurnal_umum_compare_count_jurnal_umum_rows_for_bulan($CI, $bulan)
{
	if (!preg_match('/^\d{4}-\d{2}$/', (string) $bulan)) {
		return 0;
	}

	$year = (int) substr($bulan, 0, 4);
	$month = (int) substr($bulan, 5, 2);
	$row = $CI->db->query(
		'SELECT COUNT(*) AS c FROM `jurnal_umum` WHERE YEAR(`tanggal`) = ? AND MONTH(`tanggal`) = ?',
		array($year, $month)
	)->row();

	return $row ? (int) $row->c : 0;
}

function jurnal_umum_compare_validate_table_for_import($CI, $table, $bulan)
{
	if (!preg_match('/^\d{4}-\d{2}$/', (string) $bulan)) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');
	}

	$valid = jurnal_umum_compare_validate_import_table($CI, $table);
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
		$item = jurnal_umum_compare_import_row_from_db($row, $map, $ref_month, $ref_year);
		$tanggal_norm = pembelian_jurnal_compare_normalize_tanggal($item['tanggal']);
		if ($tanggal_norm === '' || $tanggal_norm === '0000-00-00') {
			$empty_tanggal++;
			continue;
		}
		$with_tanggal++;
		if (jurnal_umum_compare_row_matches_bulan($tanggal_norm, $bulan)) {
			$in_bulan++;
			if (jurnal_umum_compare_is_import_row_saveable($item)) {
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
		$import_message = 'Siap disimpan ke jurnal_umum: ' . $saveable_in_bulan . ' baris valid pada bulan terpilih.';
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

	$existing_count = jurnal_umum_compare_count_jurnal_umum_rows_for_bulan($CI, $bulan);
	$conflict_warning = '';
	if ($existing_count > 0) {
		$conflict_warning = 'Perhatian: di tabel jurnal_umum sudah ada ' . $existing_count
			. ' data pada bulan ' . pembelian_jurnal_compare_bulan_label($bulan)
			. '. Proses simpan akan menambahkan semua baris valid apa adanya tanpa cek duplikat.';
	}

	return array(
		'ok' => true,
		'eligible' => true,
		'import_enabled' => $import_enabled,
		'bulan_match' => $import_enabled,
		'import_message' => $import_message,
		'message' => 'Tabel `' . $table . '` memenuhi syarat kolom import jurnal umum.',
		'map' => $map,
		'mapped' => isset($valid['mapped']) ? $valid['mapped'] : array(),
		'table' => $table,
		'bulan' => $bulan,
		'jurnal_umum_bulan_conflict' => ($existing_count > 0),
		'jurnal_umum_existing_count' => $existing_count,
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

function jurnal_umum_compare_load_table_detail_for_bulan($CI, $table, $bulan)
{
	if (!preg_match('/^\d{4}-\d{2}$/', (string) $bulan)) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');
	}

	$valid = jurnal_umum_compare_validate_import_table($CI, $table);
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
		$item = jurnal_umum_compare_import_row_from_db($row, $map, $ref_month, $ref_year);
		if (!jurnal_umum_compare_row_matches_bulan($item['tanggal'], $bulan)) {
			continue;
		}
		$no++;
		$debet = (float) $item['debet'];
		$kredit = (float) $item['kredit'];
		$items[] = array(
			'no' => $no,
			'tanggal' => pembelian_jurnal_compare_format_tanggal_display($item['tanggal']),
			'bukti' => $item['bukti'],
			'pl' => $item['pl'],
			'ref' => $item['ref'],
			'kode_rekening' => $item['kode_rekening'],
			'rekening' => $item['rekening'],
			'debet' => $debet > 0 ? jurnal_umum_compare_format_jumlah_display($debet) : '',
			'kredit' => $kredit > 0 ? jurnal_umum_compare_format_jumlah_display($kredit) : '',
			'debet_raw' => $debet,
			'kredit_raw' => $kredit,
		);
	}

	return array(
		'ok' => true,
		'headers' => array('No', 'Tanggal', 'Bukti', 'PL', 'Ref', 'Kode Rek.', 'Rek.', 'Debet', 'Kredit'),
		'rows' => $items,
		'table' => $table,
		'bulan' => $bulan,
		'bulan_label' => $range ? $range['bulan_label'] : $bulan,
		'total' => count($items),
	);
}

function jurnal_umum_compare_import_to_jurnal_umum($CI, $table, $bulan)
{
	$status = jurnal_umum_compare_validate_table_for_import($CI, $table, $bulan);
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

	if (!$CI->db->table_exists('jurnal_umum')) {
		return array('ok' => false, 'message' => 'Tabel `jurnal_umum` tidak ditemukan di database.');
	}

	$valid = jurnal_umum_compare_validate_import_table($CI, $table);
	$map = $valid['map'];
	$ref_year = (int) substr($bulan, 0, 4);
	$ref_month = (int) substr($bulan, 5, 2);
	$all_rows = $CI->db->query('SELECT * FROM `' . $table . '` ORDER BY id ASC')->result();
	$inserted = 0;
	$skipped_out_bulan = 0;
	$skipped_invalid = 0;
	$row_num = 0;

	$CI->db->trans_start();
	foreach ((array) $all_rows as $row) {
		$row_num++;
		$item = jurnal_umum_compare_import_row_from_db($row, $map, $ref_month, $ref_year);
		if (!jurnal_umum_compare_row_matches_bulan($item['tanggal'], $bulan)) {
			$skipped_out_bulan++;
			continue;
		}
		if (!jurnal_umum_compare_is_import_row_saveable($item)) {
			$skipped_invalid++;
			continue;
		}

		$tanggal_db = pembelian_jurnal_compare_normalize_tanggal($item['tanggal']);
		$built = jurnal_umum_compare_build_jurnal_umum_insert_row($CI, $item, $tanggal_db);
		if (empty($built['ok'])) {
			$CI->db->trans_rollback();
			return array(
				'ok' => false,
				'message' => isset($built['message']) ? $built['message'] : 'Gagal menyiapkan data insert jurnal_umum.',
				'failed_row' => $row_num,
			);
		}

		$CI->db->insert('jurnal_umum', $built['data']);
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
			'message' => 'Transaksi database gagal saat menyimpan ke jurnal_umum.',
			'db_error' => persediaan_compare_db_last_error_message($CI),
			'inserted_before_fail' => $inserted,
		);
	}

	$message = 'Berhasil menambahkan ' . $inserted . ' data ke jurnal_umum (tanpa cek duplikat).';
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

function jurnal_umum_compare_export_table_detail_excel($CI, $table, $bulan)
{
	@set_time_limit(600);
	@ini_set('memory_limit', '512M');
	$CI->load->helper(array('exportexcel', 'pembelian_persediaan', 'jurnal_umum_list'));

	$result = jurnal_umum_compare_load_table_detail_for_bulan($CI, $table, $bulan);
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
		xlsWriteCellStyle($rowNum, 2, isset($item['bukti']) ? $item['bukti'] : '', $styleLeft);
		xlsWriteCellStyle($rowNum, 3, isset($item['pl']) ? $item['pl'] : '', $styleLeft);
		xlsWriteCellStyle($rowNum, 4, isset($item['ref']) ? $item['ref'] : '', $styleLeft);
		xlsWriteCellStyle($rowNum, 5, isset($item['kode_rekening']) ? $item['kode_rekening'] : '', $styleLeft);
		xlsWriteCellStyle($rowNum, 6, isset($item['rekening']) ? $item['rekening'] : '', $styleLeft);
		$deb = isset($item['debet_raw']) ? (float) $item['debet_raw'] : 0;
		$kre = isset($item['kredit_raw']) ? (float) $item['kredit_raw'] : 0;
		if ($deb > 0) {
			xlsWriteCellStyle($rowNum, 7, jurnal_umum_format_rupiah($deb), $styleRight);
		} else {
			xlsWriteCellStyle($rowNum, 7, '', $styleBorder);
		}
		if ($kre > 0) {
			xlsWriteCellStyle($rowNum, 8, jurnal_umum_format_rupiah($kre), $styleRight);
		} else {
			xlsWriteCellStyle($rowNum, 8, '', $styleBorder);
		}
		$rowNum++;
	}

	xlsEOF();
}
