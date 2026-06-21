<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function jurnal_kas_compare_field_definitions()
{
	return array(
		'tanggal' => array('label' => 'tanggal', 'required' => true, 'aliases' => array('tanggal', 'tgl', 'date', 'tgl_transaksi')),
		'bukti' => array('label' => 'bukti', 'required' => true, 'aliases' => array('bukti', 'no_bukti', 'nobukti', 'no bukti')),
		'keterangan' => array('label' => 'keterangan', 'required' => true, 'aliases' => array('keterangan', 'ket', 'uraian', 'deskripsi')),
		'kode_rekening' => array('label' => 'kode_rekening', 'required' => true, 'aliases' => array('kode_rekening', 'kode_rek', 'kode_akun', 'kode akun', 'rek', 'rekening', 'no_rek', 'kode_unit', 'kode unit', 'kode')),
		'debet' => array('label' => 'debet', 'required' => false, 'aliases' => array('debet', 'debit')),
		'kredit' => array('label' => 'kredit', 'required' => false, 'aliases' => array('kredit', 'credit')),
	);
}

function jurnal_kas_compare_build_column_map($fields)
{
	$analysis = jurnal_kas_compare_analyze_column_map($fields);
	return !empty($analysis['ok']) ? $analysis['map'] : null;
}

function jurnal_kas_compare_analyze_column_map($fields)
{
	if (!is_array($fields) || count($fields) === 0) {
		return array('ok' => false, 'message' => 'Tabel tidak memiliki kolom.');
	}

	$normalized = array();
	foreach ($fields as $field) {
		$normalized[] = trim((string) $field);
	}

	$defs = jurnal_kas_compare_field_definitions();
	$map = array(
		'tanggal' => penjualan_jurnal_compare_pick_tanggal_column($normalized),
		'bukti' => persediaan_compare_pick_column($normalized, $defs['bukti']['aliases']),
		'keterangan' => persediaan_compare_pick_column($normalized, $defs['keterangan']['aliases']),
		'kode_rekening' => persediaan_compare_pick_column($normalized, $defs['kode_rekening']['aliases']),
		'debet' => persediaan_compare_pick_column($normalized, $defs['debet']['aliases']),
		'kredit' => persediaan_compare_pick_column($normalized, $defs['kredit']['aliases']),
	);

	$missing_required = array();
	$missing_compare = array();
	$mapped = array();
	foreach ($defs as $key => $def) {
		if (!empty($map[$key])) {
			$mapped[$key] = $map[$key];
		} elseif (!empty($def['required'])) {
			$missing_required[] = $def['label'];
			$missing_compare[] = $def['label'];
		}
	}

	if (empty($map['debet']) && empty($map['kredit'])) {
		$missing_required[] = 'debet atau kredit';
	}

	$ok = count($missing_required) === 0;
	$message = '';
	if (!$ok) {
		$message = 'Kolom wajib tidak ditemukan: ' . implode(', ', $missing_required)
			. '. Kolom compare yang dibutuhkan: tanggal, bukti, keterangan, kode_rekening, debet atau kredit.';
	}

	return array(
		'ok' => $ok,
		'map' => $map,
		'mapped' => $mapped,
		'missing_required' => $missing_required,
		'missing_compare' => $missing_compare,
		'fields' => $normalized,
		'message' => $message,
	);
}

function jurnal_kas_compare_validate_online_table_detail($CI)
{
	if (!$CI->db->table_exists('jurnal_kas')) {
		return array(
			'ok' => false,
			'message' => 'Tabel online `jurnal_kas` tidak ditemukan di database.',
			'missing_fields' => array('jurnal_kas (tabel)'),
		);
	}

	$fields = $CI->db->list_fields('jurnal_kas');
	$analysis = jurnal_kas_compare_analyze_column_map($fields);
	$map = isset($analysis['map']) ? $analysis['map'] : array();
	$missing = isset($analysis['missing_compare']) ? $analysis['missing_compare'] : array();

	if (empty($map['kode_rekening'])) {
		$has_kode_unit = persediaan_compare_pick_column($fields, array('kode_unit', 'kode unit')) !== null;
		if ($has_kode_unit) {
			$map['kode_rekening'] = persediaan_compare_pick_column($fields, array('kode_unit', 'kode unit'));
			$missing = array_values(array_diff($missing, array('kode_rekening')));
		}
	}

	$critical_missing = array();
	if (empty($map['tanggal'])) {
		$critical_missing[] = 'tanggal';
	}
	if (empty($map['keterangan'])) {
		$critical_missing[] = 'keterangan';
	}
	if (empty($map['debet']) && empty($map['kredit'])) {
		$critical_missing[] = 'debet atau kredit';
	}

	$soft_missing = array_values(array_diff($missing, $critical_missing));
	$ok = count($critical_missing) === 0;
	$message = '';
	if (!$ok) {
		$message = 'Tabel online `jurnal_kas` tidak memiliki kolom wajib: ' . implode(', ', $critical_missing) . '.';
	} elseif (count($soft_missing) > 0) {
		$message = 'Tabel online `jurnal_kas` — kolom compare tidak ditemukan (akan diisi kosong): ' . implode(', ', $soft_missing) . '.';
	}

	$mapped = array();
	foreach ($map as $key => $col) {
		if ($col !== null && $col !== '') {
			$mapped[$key] = $col;
		}
	}

	return array(
		'ok' => $ok,
		'table' => 'jurnal_kas',
		'map' => $map,
		'mapped' => $mapped,
		'missing_fields' => array_merge($critical_missing, $soft_missing),
		'critical_missing' => $critical_missing,
		'soft_missing' => $soft_missing,
		'fields' => $fields,
		'message' => $message,
	);
}

function jurnal_kas_compare_validate_table($CI, $table)
{
	if (!persediaan_compare_is_valid_table_name($table)) {
		return array('ok' => false, 'message' => 'Nama tabel tidak valid.');
	}
	if (!$CI->db->table_exists($table)) {
		return array('ok' => false, 'message' => 'Tabel tidak ditemukan di database.');
	}

	$fields = $CI->db->list_fields($table);
	$analysis = jurnal_kas_compare_analyze_column_map($fields);
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

function jurnal_kas_compare_normalize_tanggal_for_db($value, $ref_month = 0, $ref_year = 0)
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

function jurnal_kas_compare_row_matches_bulan($tanggal_ymd, $bulan)
{
	$tanggal_ymd = pembelian_jurnal_compare_normalize_tanggal($tanggal_ymd);
	if ($tanggal_ymd === '' || !preg_match('/^\d{4}-\d{2}$/', (string) $bulan)) {
		return false;
	}

	return substr($tanggal_ymd, 0, 7) === $bulan;
}

function jurnal_kas_compare_normalize_bukti($bukti)
{
	return strtoupper(trim((string) $bukti));
}

function jurnal_kas_compare_normalize_keterangan($keterangan)
{
	return strtolower(trim(preg_replace('/\s+/', ' ', (string) $keterangan)));
}

function jurnal_kas_compare_normalize_jumlah($value)
{
	return (int) round(pembelian_jurnal_compare_parse_jumlah_nominal($value));
}

function jurnal_kas_compare_format_jumlah_display($jumlah)
{
	return number_format((int) $jumlah, 0, ',', '.');
}

function jurnal_kas_compare_standard_headers()
{
	return array('No', 'Tanggal', 'Bukti', 'Kode Rekening', 'Keterangan', 'Debet', 'Kredit', 'Catatan');
}

function jurnal_kas_compare_block_headers_without_no()
{
	return array('Tanggal', 'Bukti', 'Kode Rekening', 'Keterangan', 'Debet', 'Kredit', 'Catatan');
}

function jurnal_kas_compare_is_row_analyzable($row)
{
	$tanggal = pembelian_jurnal_compare_normalize_tanggal(isset($row['tanggal']) ? $row['tanggal'] : '');
	if ($tanggal === '' || $tanggal === '0000-00-00') {
		return false;
	}
	$deb = jurnal_kas_compare_normalize_jumlah(isset($row['debet']) ? $row['debet'] : 0);
	$kre = jurnal_kas_compare_normalize_jumlah(isset($row['kredit']) ? $row['kredit'] : 0);
	return ($deb > 0 || $kre > 0);
}

function jurnal_kas_compare_row_unprocessed_reasons($row)
{
	$reasons = array();
	if (pembelian_jurnal_compare_normalize_tanggal(isset($row['tanggal']) ? $row['tanggal'] : '') === '') {
		$reasons[] = 'tanggal kosong/tidak valid';
	}
	if (jurnal_kas_compare_normalize_jumlah(isset($row['debet']) ? $row['debet'] : 0) <= 0
		&& jurnal_kas_compare_normalize_jumlah(isset($row['kredit']) ? $row['kredit'] : 0) <= 0) {
		$reasons[] = 'debet dan kredit kosong';
	}
	if (trim((string) (isset($row['keterangan']) ? $row['keterangan'] : '')) === '') {
		$reasons[] = 'keterangan kosong';
	}
	if (jurnal_kas_compare_normalize_bukti(isset($row['bukti']) ? $row['bukti'] : '') === '') {
		$reasons[] = 'bukti kosong';
	}
	return $reasons;
}

function jurnal_kas_compare_make_hard_key($tanggal, $bukti, $kode_rekening)
{
	return pembelian_jurnal_compare_normalize_tanggal($tanggal)
		. '|' . jurnal_kas_compare_normalize_bukti($bukti)
		. '|' . penjualan_jurnal_compare_normalize_kode_rekening($kode_rekening);
}

function jurnal_kas_compare_make_full_key($row)
{
	return jurnal_kas_compare_make_hard_key(
		isset($row['tanggal']) ? $row['tanggal'] : '',
		isset($row['bukti']) ? $row['bukti'] : '',
		isset($row['kode_rekening']) ? $row['kode_rekening'] : ''
	)
		. '|' . jurnal_kas_compare_normalize_keterangan(isset($row['keterangan']) ? $row['keterangan'] : '')
		. '|' . jurnal_kas_compare_normalize_jumlah(isset($row['debet']) ? $row['debet'] : 0)
		. '|' . jurnal_kas_compare_normalize_jumlah(isset($row['kredit']) ? $row['kredit'] : 0);
}

function jurnal_kas_compare_row_to_display($row, $catatan = '')
{
	$debet = jurnal_kas_compare_normalize_jumlah(isset($row['debet']) ? $row['debet'] : 0);
	$kredit = jurnal_kas_compare_normalize_jumlah(isset($row['kredit']) ? $row['kredit'] : 0);
	$tanggal = isset($row['tanggal']) ? pembelian_jurnal_compare_format_tanggal_display($row['tanggal']) : '';

	return array(
		'tanggal' => $tanggal,
		'bukti' => isset($row['bukti']) ? $row['bukti'] : '',
		'kode_rekening' => isset($row['kode_rekening']) ? $row['kode_rekening'] : '',
		'keterangan' => isset($row['keterangan']) ? $row['keterangan'] : '',
		'debet' => $debet > 0 ? jurnal_kas_compare_format_jumlah_display($debet) : '',
		'kredit' => $kredit > 0 ? jurnal_kas_compare_format_jumlah_display($kredit) : '',
		'catatan' => $catatan,
	);
}

function jurnal_kas_compare_build_diff_catatan($row, $other, $other_label)
{
	if (!is_array($row) || !is_array($other)) {
		return 'Tidak ditemukan pasangan di data ' . $other_label . '.';
	}

	$parts = array('Perbedaan dengan data ' . $other_label . ':');

	if (jurnal_kas_compare_normalize_keterangan($row['keterangan']) !== jurnal_kas_compare_normalize_keterangan($other['keterangan'])) {
		$parts[] = 'Keterangan berbeda (Manual: ' . $row['keterangan'] . ', ' . ucfirst($other_label) . ': ' . $other['keterangan'] . ')';
	}

	$deb_r = jurnal_kas_compare_normalize_jumlah($row['debet']);
	$deb_o = jurnal_kas_compare_normalize_jumlah($other['debet']);
	if ($deb_r !== $deb_o) {
		$parts[] = 'Debet berbeda (Manual: ' . jurnal_kas_compare_format_jumlah_display($deb_r)
			. ', ' . ucfirst($other_label) . ': ' . jurnal_kas_compare_format_jumlah_display($deb_o) . ')';
	}

	$kre_r = jurnal_kas_compare_normalize_jumlah($row['kredit']);
	$kre_o = jurnal_kas_compare_normalize_jumlah($other['kredit']);
	if ($kre_r !== $kre_o) {
		$parts[] = 'Kredit berbeda (Manual: ' . jurnal_kas_compare_format_jumlah_display($kre_r)
			. ', ' . ucfirst($other_label) . ': ' . jurnal_kas_compare_format_jumlah_display($kre_o) . ')';
	}

	if (count($parts) === 1) {
		return 'Tidak ditemukan pasangan lengkap di data ' . $other_label . ' (tanggal/bukti/kode_rekening/keterangan/debet/kredit tidak cocok).';
	}

	return implode('; ', $parts);
}

function jurnal_kas_compare_manual_row_from_db($row, $map, $default_tanggal = '')
{
	$tanggal_raw = !empty($map['tanggal']) ? persediaan_compare_row_get($row, $map['tanggal']) : $default_tanggal;
	$tanggal_norm = pembelian_jurnal_compare_normalize_tanggal($tanggal_raw);
	if (($tanggal_norm === '' || $tanggal_norm === '0000-00-00') && $default_tanggal !== '') {
		$tanggal_norm = pembelian_jurnal_compare_normalize_tanggal($default_tanggal);
	}

	$kode_rekening = !empty($map['kode_rekening'])
		? trim((string) persediaan_compare_row_get($row, $map['kode_rekening']))
		: '';
	if ($kode_rekening === '') {
		$kode_rekening = trim((string) persediaan_compare_row_get($row, 'kode_unit'));
	}

	return array(
		'tanggal' => $tanggal_norm,
		'bukti' => trim((string) (!empty($map['bukti']) ? persediaan_compare_row_get($row, $map['bukti']) : '')),
		'keterangan' => trim((string) (!empty($map['keterangan']) ? persediaan_compare_row_get($row, $map['keterangan']) : '')),
		'kode_rekening' => $kode_rekening,
		'debet' => jurnal_kas_compare_normalize_jumlah(!empty($map['debet']) ? persediaan_compare_row_get($row, $map['debet']) : 0),
		'kredit' => jurnal_kas_compare_normalize_jumlah(!empty($map['kredit']) ? persediaan_compare_row_get($row, $map['kredit']) : 0),
	);
}

function jurnal_kas_compare_load_manual_rows_for_bulan($CI, $table, $bulan)
{
	$valid = jurnal_kas_compare_validate_table($CI, $table);
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
		$item = jurnal_kas_compare_manual_row_from_db($row, $valid['map'], $default_tanggal);
		if (jurnal_kas_compare_row_matches_bulan($item['tanggal'], $bulan)) {
			$filtered_rows[] = $row;
		}
	}

	if (count($filtered_rows) === 0 && count($all_rows) > 0) {
		$filtered_rows = $all_rows;
		$warnings[] = 'Tidak ada baris manual dengan tanggal pada bulan ' . $range['bulan_label']
			. ' — menampilkan semua ' . count($all_rows) . ' baris dari tabel `' . $table . '`. Periksa kolom tanggal atau pilih bulan yang sesuai.';
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

function jurnal_kas_compare_load_manual_all($CI, $table, $bulan)
{
	$valid = jurnal_kas_compare_validate_table($CI, $table);
	if (empty($valid['ok'])) {
		return $valid;
	}

	$loaded = jurnal_kas_compare_load_manual_rows_for_bulan($CI, $table, $bulan);
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
		$item = jurnal_kas_compare_manual_row_from_db($row, $map, $default_tanggal);
		$reasons = jurnal_kas_compare_row_unprocessed_reasons($item);
		$display_all[] = jurnal_kas_compare_row_to_display(
			$item,
			count($reasons) > 0 ? ('Info: ' . implode(', ', $reasons)) : ''
		);

		if (!jurnal_kas_compare_is_row_analyzable($item)) {
			$unprocessed[] = jurnal_kas_compare_row_to_display($item, 'Manual tidak terproses: ' . implode(', ', $reasons));
			continue;
		}

		$full_key = jurnal_kas_compare_make_full_key($item);
		$hard_key = jurnal_kas_compare_make_hard_key($item['tanggal'], $item['bukti'], $item['kode_rekening']);
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
		'display_processed' => array_values(array_map(function ($r) {
			return jurnal_kas_compare_row_to_display($r);
		}, $list)),
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

function jurnal_kas_compare_online_row_from_db($row)
{
	$kode = isset($row->kode_rekening) ? trim((string) $row->kode_rekening) : '';
	return array(
		'tanggal' => pembelian_jurnal_compare_normalize_tanggal(isset($row->tanggal) ? $row->tanggal : ''),
		'bukti' => isset($row->bukti) ? trim((string) $row->bukti) : '',
		'keterangan' => isset($row->keterangan) ? trim((string) $row->keterangan) : '',
		'kode_rekening' => $kode,
		'debet' => jurnal_kas_compare_normalize_jumlah(isset($row->debet) ? $row->debet : 0),
		'kredit' => jurnal_kas_compare_normalize_jumlah(isset($row->kredit) ? $row->kredit : 0),
	);
}

function jurnal_kas_compare_load_online_all($CI, $bulan)
{
	$range = persediaan_compare_bulan_to_date_range($bulan);
	if ($range === null) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');
	}

	$online_fields = jurnal_kas_compare_validate_online_table_detail($CI);
	if (empty($online_fields['ok'])) {
		return array(
			'ok' => false,
			'message' => isset($online_fields['message']) ? $online_fields['message'] : 'Struktur tabel online jurnal_kas tidak valid.',
			'field_validation' => array('online' => $online_fields),
		);
	}

	$month = (int) substr($bulan, 5, 2);
	$year = (int) substr($bulan, 0, 4);
	$fields = $online_fields['fields'];
	$has_kode_rekening = in_array('kode_rekening', $fields, true);
	$has_kode_unit = in_array('kode_unit', $fields, true);

	if ($has_kode_rekening && $has_kode_unit) {
		$kode_expr = "COALESCE(NULLIF(TRIM(kode_rekening), ''), NULLIF(TRIM(kode_unit), ''), '')";
	} elseif ($has_kode_rekening) {
		$kode_expr = "COALESCE(NULLIF(TRIM(kode_rekening), ''), '')";
	} elseif ($has_kode_unit) {
		$kode_expr = "COALESCE(NULLIF(TRIM(kode_unit), ''), '')";
	} else {
		$kode_expr = "''";
	}

	$sql = "SELECT DATE(tanggal) AS tanggal,
		COALESCE(NULLIF(TRIM(bukti), ''), '') AS bukti,
		COALESCE(NULLIF(TRIM(keterangan), ''), '') AS keterangan,
		{$kode_expr} AS kode_rekening,
		COALESCE(debet, 0) AS debet,
		COALESCE(kredit, 0) AS kredit
		FROM jurnal_kas
		WHERE tanggal IS NOT NULL AND tanggal <> '0000-00-00'
		AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?
		ORDER BY tanggal, id";

	$list = array();
	$by_full = array();
	$by_hard = array();
	$unprocessed = array();
	$display_all = array();

	foreach ($CI->db->query($sql, array($month, $year))->result() as $row) {
		$item = jurnal_kas_compare_online_row_from_db($row);
		$reasons = jurnal_kas_compare_row_unprocessed_reasons($item);
		$display_all[] = jurnal_kas_compare_row_to_display(
			$item,
			count($reasons) > 0 ? ('Info: ' . implode(', ', $reasons)) : ''
		);

		if (!jurnal_kas_compare_is_row_analyzable($item)) {
			$unprocessed[] = jurnal_kas_compare_row_to_display($item, 'Online tidak terproses: ' . implode(', ', $reasons));
			continue;
		}

		$full_key = jurnal_kas_compare_make_full_key($item);
		$hard_key = jurnal_kas_compare_make_hard_key($item['tanggal'], $item['bukti'], $item['kode_rekening']);
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
		'display_processed' => array_values(array_map(function ($r) {
			return jurnal_kas_compare_row_to_display($r);
		}, $list)),
		'unprocessed' => $unprocessed,
		'stats' => array(
			'raw_total' => count($display_all),
			'processed' => count($list),
			'unprocessed' => count($unprocessed),
		),
		'range' => $range,
	);
}

function jurnal_kas_compare_pick_best_candidate($row, $candidates)
{
	if (!is_array($candidates) || count($candidates) === 0) {
		return null;
	}

	$best = null;
	$best_score = -1;
	foreach ($candidates as $candidate) {
		$score = 0;
		if (jurnal_kas_compare_normalize_keterangan($row['keterangan']) === jurnal_kas_compare_normalize_keterangan($candidate['keterangan'])) {
			$score += 3;
		}
		if (jurnal_kas_compare_normalize_jumlah($row['debet']) === jurnal_kas_compare_normalize_jumlah($candidate['debet'])) {
			$score += 2;
		}
		if (jurnal_kas_compare_normalize_jumlah($row['kredit']) === jurnal_kas_compare_normalize_jumlah($candidate['kredit'])) {
			$score += 2;
		}
		if ($score > $best_score) {
			$best_score = $score;
			$best = $candidate;
		}
	}

	return $best;
}

function jurnal_kas_compare_sort_display_rows($a, $b)
{
	$cmp = strcmp((string) $a['tanggal'], (string) $b['tanggal']);
	if ($cmp !== 0) {
		return $cmp;
	}
	$cmp = strcmp((string) $a['bukti'], (string) $b['bukti']);
	if ($cmp !== 0) {
		return $cmp;
	}
	return strcmp((string) $a['keterangan'], (string) $b['keterangan']);
}

function jurnal_kas_compare_run($CI, $bulan, $table = '')
{
	$table = trim((string) $table);
	if ($table === '') {
		return array('ok' => false, 'message' => 'Pilih tabel manual yang akan dibandingkan.');
	}

	$online_fields = jurnal_kas_compare_validate_online_table_detail($CI);
	if (empty($online_fields['ok'])) {
		return array(
			'ok' => false,
			'message' => isset($online_fields['message']) ? $online_fields['message'] : 'Struktur tabel online jurnal_kas tidak valid.',
			'field_validation' => array(
				'online' => $online_fields,
			),
		);
	}

	$manual = jurnal_kas_compare_load_manual_all($CI, $table, $bulan);
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

	$online = jurnal_kas_compare_load_online_all($CI, $bulan);
	if (empty($online['ok'])) {
		return $online;
	}

	$warnings = array();
	if (!empty($online_fields['soft_missing'])) {
		$warnings[] = 'Kolom online tidak ditemukan (diisi kosong): ' . implode(', ', $online_fields['soft_missing']);
	}
	if (!empty($online_fields['message']) && !empty($online_fields['soft_missing'])) {
		$warnings[] = $online_fields['message'];
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
			'stats' => isset($manual['stats']) ? $manual['stats'] : array(),
		);
	}
	if ((int) $manual['stats']['processed'] === 0) {
		$warnings[] = 'Semua baris manual tidak dapat diproses compare. Periksa tanggal, debet/kredit, dan kelengkapan field.';
	}
	if ((int) $online['stats']['processed'] === 0) {
		$warnings[] = 'Tidak ada data online jurnal_kas yang dapat diproses pada bulan terpilih.';
	}

	$data_cocok = array();
	$manual_tidak = array();
	$online_tidak = array();

	$online_pool = array();
	foreach ($online['list'] as $idx => $item) {
		$online_pool[$idx] = $item;
	}
	$online_used = array();

	foreach ($manual['list'] as $m_idx => $manual_row) {
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
			$data_cocok[] = jurnal_kas_compare_row_to_display($manual_row, 'Data manual dan online cocok');
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
			$best = jurnal_kas_compare_pick_best_candidate($manual_row, $candidates);
			foreach ($online_pool as $o_idx => $online_row) {
				if ($best !== null && $online_row === $best) {
					$online_used[$o_idx] = true;
					break;
				}
			}
			$manual_tidak[] = jurnal_kas_compare_row_to_display(
				$manual_row,
				jurnal_kas_compare_build_diff_catatan($manual_row, $best, 'online')
			);
		} else {
			$manual_tidak[] = jurnal_kas_compare_row_to_display(
				$manual_row,
				'Tidak ditemukan di data online (tanggal/bukti/kode_rekening tidak cocok).'
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
			$best = jurnal_kas_compare_pick_best_candidate($online_row, $candidates);
			$online_tidak[] = jurnal_kas_compare_row_to_display(
				$online_row,
				jurnal_kas_compare_build_diff_catatan($online_row, $best, 'manual')
			);
		} else {
			$online_tidak[] = jurnal_kas_compare_row_to_display(
				$online_row,
				'Tidak ditemukan di data manual (tanggal/bukti/kode_rekening tidak cocok).'
			);
		}
	}

	usort($data_cocok, 'jurnal_kas_compare_sort_display_rows');
	usort($manual_tidak, 'jurnal_kas_compare_sort_display_rows');
	usort($online_tidak, 'jurnal_kas_compare_sort_display_rows');

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
		'manual_tidak_terproses' => isset($manual['unprocessed']) ? $manual['unprocessed'] : array(),
		'online_tidak_terproses' => isset($online['unprocessed']) ? $online['unprocessed'] : array(),
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

function jurnal_kas_compare_item_to_row_cells($item, $no)
{
	return array(
		$no,
		isset($item['tanggal']) ? $item['tanggal'] : '',
		isset($item['bukti']) ? $item['bukti'] : '',
		isset($item['kode_rekening']) ? $item['kode_rekening'] : '',
		isset($item['keterangan']) ? $item['keterangan'] : '',
		isset($item['debet']) ? $item['debet'] : '',
		isset($item['kredit']) ? $item['kredit'] : '',
		isset($item['catatan']) ? $item['catatan'] : '',
	);
}

function jurnal_kas_compare_excel_all_total_cols()
{
	return 35;
}

function jurnal_kas_compare_excel_all_group_definitions()
{
	return array(
		array('title' => 'DATA MANUAL', 'col_start' => 0, 'col_end' => 7),
		array('title' => 'ONLINE TIDAK ADA DI MANUAL', 'col_start' => 9, 'col_end' => 16),
		array('title' => 'MANUAL TIDAK ADA DI ONLINE', 'col_start' => 18, 'col_end' => 25),
		array('title' => 'DATA MANUAL & ONLINE COCOK', 'col_start' => 27, 'col_end' => 34),
	);
}

function jurnal_kas_compare_excel_all_headers()
{
	$block = jurnal_kas_compare_standard_headers();
	return array_merge($block, array(''), $block, array(''), $block, array(''), $block);
}

function jurnal_kas_compare_excel_sheet_name_from_bulan($bulan)
{
	$bulan = trim((string) $bulan);
	if (!preg_match('/^(\d{4})-(\d{2})$/', $bulan, $m)) {
		return 'Compare Jurnal Kas';
	}
	$tahun = (int) $m[1];
	$bulan_num = (int) $m[2];
	$nama_bulan = persediaan_compare_excel_all_bulan_nama_id();
	$label_bulan = isset($nama_bulan[$bulan_num]) ? $nama_bulan[$bulan_num] : ('Bulan ' . $bulan_num);
	return excel_sheet_safe_name('Compare Jurnal Kas ' . $label_bulan . ' ' . $tahun);
}

function jurnal_kas_compare_excel_items_to_cells($items)
{
	$rows = array();
	$no = 0;
	foreach ((array) $items as $item) {
		$no++;
		$rows[] = jurnal_kas_compare_item_to_row_cells($item, $no);
	}
	return $rows;
}

function jurnal_kas_compare_excel_all_empty_row()
{
	return array_fill(0, jurnal_kas_compare_excel_all_total_cols(), '');
}

function jurnal_kas_compare_excel_all_write_merged_rows($rowNum, $sections, $groups)
{
	$maxLen = 0;
	foreach ($sections as $sectionRows) {
		$maxLen = max($maxLen, count((array) $sectionRows));
	}
	for ($i = 0; $i < $maxLen; $i++) {
		$cells = jurnal_kas_compare_excel_all_empty_row();
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

function jurnal_kas_compare_export_excel_all_output($CI, $bulan, $table = '', $result = null)
{
	@set_time_limit(600);
	@ini_set('memory_limit', '512M');
	if ($result === null) {
		$result = jurnal_kas_compare_run($CI, $bulan, $table);
	}
	if (empty($result['ok'])) {
		xlsAddSheet('Compare Jurnal Kas');
		xlsWriteLabel(0, 0, isset($result['message']) ? $result['message'] : 'Export Excel gagal.');
		xlsMultiEOF();
		return;
	}

	$groups = jurnal_kas_compare_excel_all_group_definitions();
	$headers = jurnal_kas_compare_excel_all_headers();
	$groupTitleRow = 2;
	$headerRow = 3;
	$dataStartRow = 4;
	$stats = isset($result['stats']) ? $result['stats'] : array();

	xlsAddSheet(jurnal_kas_compare_excel_sheet_name_from_bulan($bulan));
	xlsWriteLabelBold14(0, 0, 'Compare Jurnal Kas — Manual vs Online — Bulan ' . $result['bulan_label']);
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
		0 => jurnal_kas_compare_excel_items_to_cells(isset($result['data_manual']) ? $result['data_manual'] : array()),
		9 => jurnal_kas_compare_excel_items_to_cells(isset($result['online_tidak_di_manual']) ? $result['online_tidak_di_manual'] : array()),
		18 => jurnal_kas_compare_excel_items_to_cells(isset($result['manual_tidak_di_online']) ? $result['manual_tidak_di_online'] : array()),
		27 => jurnal_kas_compare_excel_items_to_cells(isset($result['data_cocok']) ? $result['data_cocok'] : array()),
	);

	$lastRow = jurnal_kas_compare_excel_all_write_merged_rows($dataStartRow, $sections, $groups);
	if ($lastRow < $dataStartRow) {
		$lastRow = $headerRow;
	}
	persediaan_rekonsiliasi_tx_finalize_group_borders($groupTitleRow, $lastRow, $groups);
	xlsMultiEOF();
}

function jurnal_kas_compare_export_excel_output($CI, $bulan, $table = '')
{
	@set_time_limit(600);
	@ini_set('memory_limit', '512M');
	$CI->load->helper(array('exportexcel', 'persediaan_display', 'pembelian_persediaan'));

	$result = jurnal_kas_compare_run($CI, $bulan, $table);
	if (empty($result['ok'])) {
		xlsBOF();
		xlsWriteLabel(0, 0, isset($result['message']) ? $result['message'] : 'Compare gagal.');
		xlsEOF();
		return;
	}

	jurnal_kas_compare_export_excel_all_output($CI, $bulan, $table, $result);
}

function jurnal_kas_compare_csv_column_error_detail($raw_headers)
{
	$analysis = jurnal_kas_compare_analyze_column_map(
		is_array($raw_headers) ? persediaan_compare_sanitize_csv_headers($raw_headers) : array()
	);
	if (!empty($analysis['message'])) {
		return $analysis['message'];
	}

	$lines = array('Header CSV tidak memenuhi syarat Jurnal Kas.');
	$lines[] = 'Kolom wajib: tanggal, bukti, keterangan, kode_rekening, debet atau kredit.';
	if (is_array($raw_headers) && count($raw_headers) > 0) {
		$lines[] = 'Header terdeteksi: ' . implode(', ', $raw_headers);
	}
	return implode("\n", $lines);
}

function jurnal_kas_compare_validate_csv_file($filepath)
{
	if (!is_readable($filepath)) {
		return array(
			'ok' => false,
			'stage' => 'read_file',
			'message' => 'File CSV tidak dapat dibaca.',
		);
	}

	$handle = persediaan_compare_csv_open_handle($filepath);
	if (!$handle) {
		return array(
			'ok' => false,
			'stage' => 'open_file',
			'message' => 'Gagal membuka file CSV.',
		);
	}

	$raw_headers = persediaan_compare_csv_read_header($handle);
	fclose($handle);

	if ($raw_headers === null) {
		return array(
			'ok' => false,
			'stage' => 'read_header',
			'message' => 'File CSV kosong atau baris header tidak valid.',
		);
	}

	$map = jurnal_kas_compare_build_column_map(persediaan_compare_sanitize_csv_headers($raw_headers));
	if ($map === null) {
		return array(
			'ok' => false,
			'stage' => 'validate_columns',
			'message' => jurnal_kas_compare_csv_column_error_detail($raw_headers),
		);
	}

	return array(
		'ok' => true,
		'map' => $map,
		'headers' => $raw_headers,
	);
}

function jurnal_kas_compare_check_csv_table_name($CI, $original_filename, $bulan_key = '')
{
	return penjualan_jurnal_compare_check_csv_table_name($CI, $original_filename, $bulan_key);
}

function jurnal_kas_compare_import_csv_to_db($CI, $filepath, $original_filename, $bulan_key = '', $bulan_num = 0, $tahun = 0)
{
	$file_label = trim((string) $original_filename);
	if ($file_label === '') {
		$file_label = basename((string) $filepath);
	}

	$validated = jurnal_kas_compare_validate_csv_file($filepath);
	if (empty($validated['ok'])) {
		$validated['file'] = $file_label;
		return $validated;
	}

	if (!is_readable($filepath)) {
		return array(
			'ok' => false,
			'stage' => 'read_file',
			'message' => "File `{$file_label}` tidak dapat dibaca dari server.",
		);
	}

	$handle = persediaan_compare_csv_open_handle($filepath);
	if (!$handle) {
		return array(
			'ok' => false,
			'stage' => 'open_file',
			'message' => "Gagal membuka file CSV `{$file_label}`.",
		);
	}

	$raw_headers = persediaan_compare_csv_read_header($handle);
	if ($raw_headers === null) {
		fclose($handle);
		return array(
			'ok' => false,
			'stage' => 'read_header',
			'message' => "File `{$file_label}` kosong atau baris header CSV tidak valid.",
		);
	}

	$db_columns_sanitized = persediaan_compare_sanitize_csv_headers($raw_headers);
	$column_map = jurnal_kas_compare_build_column_map($db_columns_sanitized);
	$csv_has_id = penjualan_jurnal_compare_csv_has_id_column($raw_headers);

	$csv_col_index = array();
	foreach ($db_columns_sanitized as $idx => $col) {
		$csv_col_index[$col] = $idx;
	}

	$col_tanggal = !empty($column_map['tanggal']) ? $column_map['tanggal'] : 'tanggal';
	$col_bukti = !empty($column_map['bukti']) ? $column_map['bukti'] : 'bukti';
	$col_keterangan = !empty($column_map['keterangan']) ? $column_map['keterangan'] : 'keterangan';
	$col_kode_rekening = !empty($column_map['kode_rekening']) ? $column_map['kode_rekening'] : 'kode_rekening';
	$col_debet = !empty($column_map['debet']) ? $column_map['debet'] : 'debet';
	$col_kredit = !empty($column_map['kredit']) ? $column_map['kredit'] : 'kredit';

	$db_columns = array();
	foreach ($db_columns_sanitized as $col) {
		if (strtolower($col) === 'id') {
			continue;
		}
		$db_columns[] = $col;
	}
	foreach (array($col_tanggal, $col_bukti, $col_keterangan, $col_kode_rekening, $col_debet, $col_kredit) as $required_col) {
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
		} elseif ($col === $col_debet || $col === $col_kredit) {
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

		$data[$col_tanggal] = jurnal_kas_compare_normalize_tanggal_for_db(
			isset($data[$col_tanggal]) ? $data[$col_tanggal] : '',
			$bulan_tahun_ref['month'],
			$bulan_tahun_ref['year']
		);

		if ($col_debet !== '') {
			$data[$col_debet] = jurnal_kas_compare_normalize_jumlah(isset($data[$col_debet]) ? $data[$col_debet] : 0);
		}
		if ($col_kredit !== '') {
			$data[$col_kredit] = jurnal_kas_compare_normalize_jumlah(isset($data[$col_kredit]) ? $data[$col_kredit] : 0);
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
					'message' => "Gagal meng-upload data CSV ke tabel baru `{$table}` (sekitar baris {$line_no}).\n"
						. 'Detail database: ' . persediaan_compare_db_last_error_message($CI),
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
				'message' => "Gagal meng-upload sisa data CSV ke tabel baru `{$table}`.\n"
					. 'Detail database: ' . persediaan_compare_db_last_error_message($CI),
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
			'message' => "File `{$file_label}` tidak memiliki baris data setelah header.\nTabel `{$table}` dibatalkan dan tidak disimpan.",
			'table' => $table,
			'file' => $file_label,
		);
	}

	$id_info = $csv_has_id
		? "Kolom `id` dari CSV diabaikan — tabel memakai kolom `id` INT(9) AUTO_INCREMENT PRIMARY KEY baru.\n"
		: "Kolom `id` INT(9) AUTO_INCREMENT PRIMARY KEY ditambahkan otomatis karena tidak ada di file CSV.\n";

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
				? "Tabel `{$table_base}` sudah ada — dibuat tabel baru: `{$table}`" . ($table_suffix > 0 ? " (_{$table_suffix}).\n" : ".\n")
				: "1. Tabel baru dibuat: `{$table}`\n")
			. '2. Kolom disesuaikan dari header CSV (' . count($db_columns) . " kolom).\n"
			. '3. ' . $id_info
			. "4. Kolom debet dan kredit bertipe INT(9).\n"
			. "5. Data ter-upload: {$inserted} baris\n"
			. '6. Kolom tanggal dinormalisasi ke format DATE dengan bulan/tahun dari combobox: '
			. $bulan_tahun_ref['month'] . '/' . $bulan_tahun_ref['year']
			. ' — tanggal kosong menjadi tanggal akhir bulan: '
			. $bulan_tahun_ref['tanggal_akhir_display'] . ".\n"
			. 'Silahkan lanjut compare menggunakan tabel ini.',
	);
}

function jurnal_kas_compare_load_import_helpers($CI)
{
	if (is_object($CI)) {
		$CI->load->helper('penjualan_jurnal_compare');
	}
}

function jurnal_kas_compare_import_field_definitions()
{
	return array(
		'tanggal' => array('label' => 'tanggal', 'required' => true, 'aliases' => array('tanggal', 'tgl', 'date', 'tgl_transaksi')),
		'keterangan' => array('label' => 'keterangan', 'required' => true, 'aliases' => array('keterangan', 'ket', 'uraian', 'deskripsi')),
		'bukti' => array('label' => 'bukti', 'required' => false, 'aliases' => array('bukti', 'no_bukti', 'nobukti', 'no bukti')),
		'kode_rekening' => array('label' => 'kode_rekening', 'required' => false, 'aliases' => array('kode_rekening', 'kode_rek', 'kode_akun', 'kode akun', 'kode rekening', 'rek', 'rekening', 'no_rek', 'kode')),
		'debet' => array('label' => 'debet', 'required' => false, 'aliases' => array('debet', 'debit')),
		'kredit' => array('label' => 'kredit', 'required' => false, 'aliases' => array('kredit', 'credit')),
	);
}

function jurnal_kas_compare_is_import_row_saveable($row)
{
	$tanggal = pembelian_jurnal_compare_normalize_tanggal(isset($row['tanggal']) ? $row['tanggal'] : '');
	if ($tanggal === '' || $tanggal === '0000-00-00') {
		return false;
	}
	if (trim((string) (isset($row['keterangan']) ? $row['keterangan'] : '')) === '') {
		return false;
	}
	$deb = jurnal_kas_compare_normalize_jumlah(isset($row['debet']) ? $row['debet'] : 0);
	$kre = jurnal_kas_compare_normalize_jumlah(isset($row['kredit']) ? $row['kredit'] : 0);

	return ($deb > 0 || $kre > 0);
}

function jurnal_kas_compare_import_row_saveable_reasons($row)
{
	$reasons = array();
	if (pembelian_jurnal_compare_normalize_tanggal(isset($row['tanggal']) ? $row['tanggal'] : '') === '') {
		$reasons[] = 'tanggal kosong/tidak valid';
	}
	if (trim((string) (isset($row['keterangan']) ? $row['keterangan'] : '')) === '') {
		$reasons[] = 'keterangan kosong';
	}
	if (jurnal_kas_compare_normalize_jumlah(isset($row['debet']) ? $row['debet'] : 0) <= 0
		&& jurnal_kas_compare_normalize_jumlah(isset($row['kredit']) ? $row['kredit'] : 0) <= 0) {
		$reasons[] = 'debet dan kredit kosong';
	}

	return $reasons;
}

function jurnal_kas_compare_analyze_import_column_map($fields)
{
	if (!is_array($fields) || count($fields) === 0) {
		return array('ok' => false, 'message' => 'Tabel tidak memiliki kolom.');
	}

	$normalized = array();
	foreach ($fields as $field) {
		$normalized[] = trim((string) $field);
	}

	$defs = jurnal_kas_compare_import_field_definitions();
	$map = array(
		'tanggal' => penjualan_jurnal_compare_pick_tanggal_column($normalized),
		'bukti' => persediaan_compare_pick_column($normalized, $defs['bukti']['aliases']),
		'keterangan' => persediaan_compare_pick_column($normalized, $defs['keterangan']['aliases']),
		'kode_rekening' => persediaan_compare_pick_column($normalized, $defs['kode_rekening']['aliases']),
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
			. '. Kolom import minimal: tanggal, keterangan, debet atau kredit.';
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

function jurnal_kas_compare_validate_import_table($CI, $table)
{
	jurnal_kas_compare_load_import_helpers($CI);

	if (!persediaan_compare_is_valid_table_name($table)) {
		return array('ok' => false, 'message' => 'Nama tabel tidak valid.');
	}
	if (!$CI->db->table_exists($table)) {
		return array('ok' => false, 'message' => 'Tabel tidak ditemukan di database.');
	}

	$fields = $CI->db->list_fields($table);
	$analysis = jurnal_kas_compare_analyze_import_column_map($fields);
	if (empty($analysis['ok'])) {
		return array(
			'ok' => false,
			'message' => isset($analysis['message']) ? $analysis['message'] : 'Struktur tabel tidak valid untuk import jurnal kas.',
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

function jurnal_kas_compare_import_row_from_db($row, $map, $ref_month = 0, $ref_year = 0)
{
	$tanggal_raw = !empty($map['tanggal']) ? persediaan_compare_row_get($row, $map['tanggal']) : '';
	$tanggal_norm = jurnal_kas_compare_normalize_tanggal_for_db($tanggal_raw, $ref_month, $ref_year);

	return array(
		'tanggal' => $tanggal_norm,
		'bukti' => trim((string) (!empty($map['bukti']) ? persediaan_compare_row_get($row, $map['bukti']) : '')),
		'keterangan' => trim((string) (!empty($map['keterangan']) ? persediaan_compare_row_get($row, $map['keterangan']) : '')),
		'kode_rekening' => trim((string) (!empty($map['kode_rekening']) ? persediaan_compare_row_get($row, $map['kode_rekening']) : '')),
		'debet' => jurnal_kas_compare_normalize_jumlah(!empty($map['debet']) ? persediaan_compare_row_get($row, $map['debet']) : 0),
		'kredit' => jurnal_kas_compare_normalize_jumlah(!empty($map['kredit']) ? persediaan_compare_row_get($row, $map['kredit']) : 0),
	);
}

function jurnal_kas_compare_make_jurnal_kas_duplicate_key($row)
{
	$tanggal = pembelian_jurnal_compare_normalize_tanggal(isset($row['tanggal']) ? $row['tanggal'] : '');

	return $tanggal
		. '|' . jurnal_kas_compare_normalize_keterangan(isset($row['keterangan']) ? $row['keterangan'] : '')
		. '|' . jurnal_kas_compare_normalize_jumlah(isset($row['debet']) ? $row['debet'] : 0)
		. '|' . jurnal_kas_compare_normalize_jumlah(isset($row['kredit']) ? $row['kredit'] : 0);
}

function jurnal_kas_compare_validate_table_for_import($CI, $table, $bulan)
{
	jurnal_kas_compare_load_import_helpers($CI);

	if (!preg_match('/^\d{4}-\d{2}$/', (string) $bulan)) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');
	}

	$valid = jurnal_kas_compare_validate_import_table($CI, $table);
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
		$item = jurnal_kas_compare_import_row_from_db($row, $map, $ref_month, $ref_year);
		$tanggal_norm = pembelian_jurnal_compare_normalize_tanggal($item['tanggal']);
		if ($tanggal_norm === '' || $tanggal_norm === '0000-00-00') {
			$empty_tanggal++;
			continue;
		}
		$with_tanggal++;
		if (jurnal_kas_compare_row_matches_bulan($tanggal_norm, $bulan)) {
			$in_bulan++;
			if (jurnal_kas_compare_is_import_row_saveable($item)) {
				$saveable_in_bulan++;
			} else {
				$invalid_in_bulan++;
			}
		} else {
			$out_bulan++;
		}
	}

	$import_enabled = ($saveable_in_bulan > 0);
	$bulan_match = $import_enabled;
	$import_message = '';
	if ($import_enabled) {
		$import_message = 'Siap disimpan ke jurnal_kas: ' . $saveable_in_bulan . ' baris valid pada bulan terpilih.';
		if ($out_bulan > 0) {
			$import_message .= ' (' . $out_bulan . ' baris di luar bulan akan dilewati.)';
		}
		if ($invalid_in_bulan > 0) {
			$import_message .= ' (' . $invalid_in_bulan . ' baris bulan terpilih tidak valid: tanggal/keterangan/debet-kredit kosong.)';
		}
	} elseif ($in_bulan > 0) {
		$import_message = 'Ada ' . $in_bulan . ' baris pada bulan terpilih, tetapi tidak ada yang memenuhi syarat: tanggal, keterangan, dan debet atau kredit terisi.';
	} elseif ($out_bulan > 0) {
		$import_message = 'Tidak ada data dengan tanggal pada bulan terpilih. (' . $out_bulan . ' baris tanggalnya di bulan lain.)';
	} else {
		$import_message = 'Tidak ada data dengan tanggal valid pada tabel ini.';
	}

	$conflict = jurnal_kas_compare_build_jurnal_kas_bulan_conflict($CI, $table, $map, $ref_month, $ref_year);

	return array(
		'ok' => true,
		'eligible' => true,
		'import_enabled' => $import_enabled,
		'bulan_match' => $bulan_match,
		'import_message' => $import_message,
		'message' => 'Tabel `' . $table . '` memenuhi syarat kolom import jurnal kas (tanggal, keterangan, debet/kredit).',
		'map' => $map,
		'mapped' => isset($valid['mapped']) ? $valid['mapped'] : array(),
		'table' => $table,
		'bulan' => $bulan,
		'jurnal_kas_bulan_conflict' => !empty($conflict['jurnal_kas_bulan_conflict']),
		'first_row_bulan' => isset($conflict['first_row_bulan']) ? $conflict['first_row_bulan'] : '',
		'first_row_bulan_label' => isset($conflict['first_row_bulan_label']) ? $conflict['first_row_bulan_label'] : '',
		'first_row_tanggal' => isset($conflict['first_row_tanggal']) ? $conflict['first_row_tanggal'] : '',
		'jurnal_kas_existing_count' => isset($conflict['jurnal_kas_existing_count']) ? (int) $conflict['jurnal_kas_existing_count'] : 0,
		'conflict_warning' => isset($conflict['conflict_warning']) ? $conflict['conflict_warning'] : '',
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

function jurnal_kas_compare_load_table_detail_for_bulan($CI, $table, $bulan)
{
	if (!preg_match('/^\d{4}-\d{2}$/', (string) $bulan)) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');
	}

	$valid = jurnal_kas_compare_validate_import_table($CI, $table);
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
		$item = jurnal_kas_compare_import_row_from_db($row, $map, $ref_month, $ref_year);
		if (!jurnal_kas_compare_row_matches_bulan($item['tanggal'], $bulan)) {
			continue;
		}
		$no++;
		$debet = (int) $item['debet'];
		$kredit = (int) $item['kredit'];
		$items[] = array(
			'no' => $no,
			'tanggal' => pembelian_jurnal_compare_format_tanggal_display($item['tanggal']),
			'bukti' => $item['bukti'],
			'keterangan' => $item['keterangan'],
			'kode_rekening' => $item['kode_rekening'],
			'debet' => $debet > 0 ? jurnal_kas_compare_format_jumlah_display($debet) : '',
			'kredit' => $kredit > 0 ? jurnal_kas_compare_format_jumlah_display($kredit) : '',
			'debet_raw' => $debet,
			'kredit_raw' => $kredit,
		);
	}

	return array(
		'ok' => true,
		'headers' => array('No', 'Tanggal', 'Bukti', 'Keterangan', 'Kode Rekening', 'Debet', 'Kredit'),
		'rows' => $items,
		'table' => $table,
		'bulan' => $bulan,
		'bulan_label' => $range ? $range['bulan_label'] : $bulan,
		'total' => count($items),
	);
}

function jurnal_kas_compare_get_first_record_bulan_info($CI, $table, $map, $ref_month, $ref_year)
{
	$row = $CI->db->query('SELECT * FROM `' . $table . '` ORDER BY `id` ASC LIMIT 1')->row();
	if (!$row) {
		return null;
	}

	$item = jurnal_kas_compare_import_row_from_db($row, $map, $ref_month, $ref_year);
	$tanggal = pembelian_jurnal_compare_normalize_tanggal($item['tanggal']);
	if ($tanggal === '' || $tanggal === '0000-00-00') {
		return null;
	}

	$bulan_key = substr($tanggal, 0, 7);
	if (!preg_match('/^\d{4}-\d{2}$/', $bulan_key)) {
		return null;
	}

	$range = persediaan_compare_bulan_to_date_range($bulan_key);

	return array(
		'bulan' => $bulan_key,
		'bulan_label' => $range ? $range['bulan_label'] : $bulan_key,
		'tanggal' => $tanggal,
	);
}

function jurnal_kas_compare_count_jurnal_kas_rows_for_bulan($CI, $bulan)
{
	if (!preg_match('/^\d{4}-\d{2}$/', (string) $bulan)) {
		return 0;
	}

	$year = (int) substr($bulan, 0, 4);
	$month = (int) substr($bulan, 5, 2);
	$row = $CI->db->query(
		'SELECT COUNT(*) AS c FROM `jurnal_kas` WHERE YEAR(`tanggal`) = ? AND MONTH(`tanggal`) = ?',
		array($year, $month)
	)->row();

	return $row ? (int) $row->c : 0;
}

function jurnal_kas_compare_build_jurnal_kas_bulan_conflict($CI, $table, $map, $ref_month, $ref_year)
{
	$first = jurnal_kas_compare_get_first_record_bulan_info($CI, $table, $map, $ref_month, $ref_year);
	if ($first === null) {
		return array(
			'jurnal_kas_bulan_conflict' => false,
			'first_row_bulan' => '',
			'first_row_bulan_label' => '',
			'first_row_tanggal' => '',
			'jurnal_kas_existing_count' => 0,
			'conflict_warning' => '',
		);
	}

	$existing_count = jurnal_kas_compare_count_jurnal_kas_rows_for_bulan($CI, $first['bulan']);
	$conflict = ($existing_count > 0);
	$warning = '';
	if ($conflict) {
		$warning = 'Perhatian: record pertama tabel ini berada pada '
			. $first['bulan_label']
			. ' (' . pembelian_jurnal_compare_format_tanggal_display($first['tanggal']) . '). '
			. 'Di tabel jurnal_kas sudah ada ' . $existing_count . ' data pada bulan/tahun tersebut. '
			. 'Proses simpan akan menambahkan semua baris apa adanya — berisiko data bentrok atau duplikasi. '
			. 'Pastikan data di jurnal_kas sudah siap menerima data baru (hapus/backup data lama bila perlu).';
	}

	return array(
		'jurnal_kas_bulan_conflict' => $conflict,
		'first_row_bulan' => $first['bulan'],
		'first_row_bulan_label' => $first['bulan_label'],
		'first_row_tanggal' => $first['tanggal'],
		'jurnal_kas_existing_count' => $existing_count,
		'conflict_warning' => $warning,
	);
}

/**
 * Tanggal key saldo akhir di jurnal_kas_saldo_akhir_bulan:
 * tanggal-01 bulan berikutnya dari bulan terpilih (Jan 2026 → 2026-02-01).
 */
function jurnal_kas_compare_saldo_akhir_tanggal_for_bulan($bulan)
{
	if (!preg_match('/^(\d{4})-(\d{2})$/', (string) $bulan, $m)) {
		return '';
	}

	$year = (int) $m[1];
	$month = (int) $m[2];
	$month++;
	if ($month > 12) {
		$month = 1;
		$year++;
	}

	return sprintf('%04d-%02d-01', $year, $month);
}

function jurnal_kas_compare_save_saldo_akhir_bulan($CI, $bulan, $saldo_akhir)
{
	if (!preg_match('/^\d{4}-\d{2}$/', (string) $bulan)) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid untuk saldo akhir.');
	}

	if (!$CI->db->table_exists('jurnal_kas_saldo_akhir_bulan')) {
		return array('ok' => false, 'message' => 'Tabel `jurnal_kas_saldo_akhir_bulan` tidak ditemukan.');
	}

	$tanggal = jurnal_kas_compare_saldo_akhir_tanggal_for_bulan($bulan);
	if ($tanggal === '') {
		return array('ok' => false, 'message' => 'Tanggal saldo akhir tidak dapat ditentukan.');
	}

	$CI->load->model('Jurnal_kas_saldo_akhir_bulan_model');
	$saldo_value = round((float) $saldo_akhir, 2);

	$CI->db->where('tanggal', $tanggal);
	$query = $CI->db->get('jurnal_kas_saldo_akhir_bulan');

	if ($query->num_rows() > 0) {
		$CI->Jurnal_kas_saldo_akhir_bulan_model->update(
			$query->row()->id,
			array('saldo' => $saldo_value)
		);
		$action = 'update';
	} else {
		$CI->Jurnal_kas_saldo_akhir_bulan_model->insert(array(
			'tanggal' => $tanggal,
			'saldo' => $saldo_value,
		));
		$action = 'insert';
	}

	return array(
		'ok' => true,
		'action' => $action,
		'tanggal' => $tanggal,
		'saldo' => $saldo_value,
	);
}

function jurnal_kas_compare_import_to_jurnal_kas($CI, $table, $bulan)
{
	$status = jurnal_kas_compare_validate_table_for_import($CI, $table, $bulan);
	if (empty($status['ok'])) {
		return $status;
	}
	if (empty($status['eligible'])) {
		return array('ok' => false, 'message' => isset($status['message']) ? $status['message'] : 'Tabel tidak memenuhi syarat import.');
	}
	if (empty($status['import_enabled'])) {
		return array(
			'ok' => false,
			'message' => isset($status['import_message']) ? $status['import_message'] : 'Data tidak bisa di masukan ke data jurnal kas karena berbeda bulan.',
		);
	}

	if (!$CI->db->table_exists('jurnal_kas')) {
		return array('ok' => false, 'message' => 'Tabel `jurnal_kas` tidak ditemukan di database.');
	}

	$CI->load->model('Jurnal_kas_model');
	$valid = jurnal_kas_compare_validate_import_table($CI, $table);
	$map = $valid['map'];
	$ref_year = (int) substr($bulan, 0, 4);
	$ref_month = (int) substr($bulan, 5, 2);
	$all_rows = $CI->db->query('SELECT * FROM `' . $table . '` ORDER BY id ASC')->result();
	$inserted = 0;
	$skipped_out_bulan = 0;
	$skipped_invalid = 0;

	$CI->db->trans_start();
	foreach ((array) $all_rows as $row) {
		$item = jurnal_kas_compare_import_row_from_db($row, $map, $ref_month, $ref_year);
		if (!jurnal_kas_compare_row_matches_bulan($item['tanggal'], $bulan)) {
			$skipped_out_bulan++;
			continue;
		}
		if (!jurnal_kas_compare_is_import_row_saveable($item)) {
			$skipped_invalid++;
			continue;
		}

		$tanggal_db = pembelian_jurnal_compare_normalize_tanggal($item['tanggal']);
		$data = array(
			'tanggal' => $tanggal_db . ' ' . date('H:i:s'),
			'bukti' => $item['bukti'] !== '' ? $item['bukti'] : null,
			'keterangan' => substr($item['keterangan'], 0, 103),
			'kode_rekening' => $item['kode_rekening'] !== '' ? $item['kode_rekening'] : null,
			'debet' => $item['debet'] > 0 ? $item['debet'] : null,
			'kredit' => $item['kredit'] > 0 ? $item['kredit'] : null,
		);

		$CI->Jurnal_kas_model->insert($data);
		$inserted++;
	}

	// Saldo akhir Kas Bulan terpilih → jurnal_kas_saldo_akhir_bulan (tanggal = bulan+1)
	$CI->load->helper('jurnal_kas_list');
	$summary = jurnal_kas_compute_list_data($CI, $ref_month, $ref_year);
	$saldo_result = jurnal_kas_compare_save_saldo_akhir_bulan($CI, $bulan, $summary['SALDO_AKHIR']);

	$CI->db->trans_complete();

	if ($CI->db->trans_status() === FALSE) {
		return array('ok' => false, 'message' => 'Gagal menyimpan data ke jurnal_kas.');
	}

	$conflict = jurnal_kas_compare_build_jurnal_kas_bulan_conflict($CI, $table, $map, $ref_month, $ref_year);
	$message = 'Berhasil menambahkan ' . $inserted . ' data ke jurnal_kas (semua baris valid disimpan apa adanya).';
	if ($skipped_out_bulan > 0) {
		$message .= ' ' . $skipped_out_bulan . ' baris di luar bulan terpilih tidak disimpan.';
	}
	if ($skipped_invalid > 0) {
		$message .= ' ' . $skipped_invalid . ' baris tidak valid (tanggal/keterangan/debet-kredit kosong) tidak disimpan.';
	}
	if (!empty($saldo_result['ok'])) {
		$bulan_nama = jurnal_kas_bulan_teks($ref_month);
		$action_label = ($saldo_result['action'] === 'insert') ? 'disimpan' : 'diperbarui';
		$message .= ' Saldo akhir Kas Bulan ' . $bulan_nama . ' ' . $ref_year
			. ' (' . number_format((float) $saldo_result['saldo'], 2, ',', '.') . ') '
			. $action_label . ' ke jurnal_kas_saldo_akhir_bulan tanggal ' . $saldo_result['tanggal'] . '.';
	} elseif (!empty($saldo_result['message'])) {
		$message .= ' Peringatan saldo akhir: ' . $saldo_result['message'];
	}
	if (!empty($conflict['jurnal_kas_bulan_conflict'])) {
		$message .= ' Catatan: jurnal_kas sudah memiliki '
			. (int) $conflict['jurnal_kas_existing_count']
			. ' data pada ' . $conflict['first_row_bulan_label'] . '.';
	}

	return array(
		'ok' => true,
		'inserted' => $inserted,
		'skipped_out_bulan' => $skipped_out_bulan,
		'skipped_invalid' => $skipped_invalid,
		'saldo_akhir' => isset($summary['SALDO_AKHIR']) ? (float) $summary['SALDO_AKHIR'] : null,
		'saldo_tanggal' => !empty($saldo_result['tanggal']) ? $saldo_result['tanggal'] : '',
		'saldo_saved' => !empty($saldo_result['ok']),
		'jurnal_kas_bulan_conflict' => !empty($conflict['jurnal_kas_bulan_conflict']),
		'conflict_warning' => isset($conflict['conflict_warning']) ? $conflict['conflict_warning'] : '',
		'message' => $message,
	);
}

function jurnal_kas_compare_export_table_detail_excel($CI, $table, $bulan)
{
	@set_time_limit(600);
	@ini_set('memory_limit', '512M');
	$CI->load->helper(array('exportexcel', 'pembelian_persediaan'));

	$result = jurnal_kas_compare_load_table_detail_for_bulan($CI, $table, $bulan);
	if (empty($result['ok'])) {
		xlsBOF();
		xlsWriteLabel(0, 0, isset($result['message']) ? $result['message'] : 'Export Excel gagal.');
		xlsEOF();
		return;
	}

	$headers = isset($result['headers']) ? $result['headers'] : array();
	$rows = isset($result['rows']) ? $result['rows'] : array();
	$bulan_label = isset($result['bulan_label']) ? $result['bulan_label'] : $bulan;

	xlsBOF();
	xlsWriteLabelBold14(0, 0, 'Detail Tabel ' . $table . ' — Bulan ' . $bulan_label);
	xlsWriteLabel(1, 0, 'Dicetak: ' . date('d/m/Y H:i:s') . ' | Total baris: ' . count($rows));

	$headerRow = 3;
	foreach ($headers as $col => $label) {
		xlsWriteCellStyle($headerRow, $col, $label, 2);
	}

	$rowNum = $headerRow + 1;
	foreach ($rows as $item) {
		xlsWriteNumber($rowNum, 0, isset($item['no']) ? (int) $item['no'] : 0, 'center');
		xlsWriteLabel($rowNum, 1, isset($item['tanggal']) ? $item['tanggal'] : '');
		xlsWriteLabel($rowNum, 2, isset($item['bukti']) ? $item['bukti'] : '');
		xlsWriteLabel($rowNum, 3, isset($item['keterangan']) ? $item['keterangan'] : '');
		xlsWriteLabel($rowNum, 4, isset($item['kode_rekening']) ? $item['kode_rekening'] : '');
		$deb = isset($item['debet_raw']) ? (int) $item['debet_raw'] : 0;
		$kre = isset($item['kredit_raw']) ? (int) $item['kredit_raw'] : 0;
		if ($deb > 0) {
			xlsWriteRupiah($rowNum, 5, $deb);
		} else {
			xlsWriteLabel($rowNum, 5, '');
		}
		if ($kre > 0) {
			xlsWriteRupiah($rowNum, 6, $kre);
		} else {
			xlsWriteLabel($rowNum, 6, '');
		}
		$rowNum++;
	}

	if (count($rows) > 0) {
		$totalDeb = 0;
		$totalKre = 0;
		foreach ($rows as $item) {
			$totalDeb += isset($item['debet_raw']) ? (int) $item['debet_raw'] : 0;
			$totalKre += isset($item['kredit_raw']) ? (int) $item['kredit_raw'] : 0;
		}
		xlsWriteCellStyle($rowNum, 0, 'Total', 9);
		for ($c = 1; $c <= 4; $c++) {
			xlsWriteLabel($rowNum, $c, '');
		}
		xlsWriteCellStyle($rowNum, 5, $totalDeb > 0 ? $totalDeb : '', 10);
		xlsWriteCellStyle($rowNum, 6, $totalKre > 0 ? $totalKre : '', 10);
	}

	xlsSetColumnWidths(array(6, 14, 10, 40, 14, 14, 14));
	xlsEOF();
}
