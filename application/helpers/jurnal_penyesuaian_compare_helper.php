<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function jurnal_penyesuaian_compare_field_definitions()
{
	return array(
		'tanggal' => array('label' => 'tanggal', 'required' => true, 'aliases' => array('tanggal', 'tgl', 'date', 'tgl_transaksi')),
		'kode_akun' => array('label' => 'kode_akun', 'required' => true, 'aliases' => array('kode_akun', 'kode akun', 'akun')),
		'keterangan' => array('label' => 'keterangan', 'required' => true, 'aliases' => array('keterangan', 'ket', 'uraian', 'deskripsi')),
		'kode_rekening' => array('label' => 'kode_rekening', 'required' => true, 'aliases' => array('kode_rekening', 'kode_rek', 'uraian_kode_rekening', 'kode rek')),
		'bukti' => array('label' => 'bukti', 'required' => false, 'aliases' => array('bukti', 'no_bukti', 'nobukti', 'no bukti')),
		'pl' => array('label' => 'pl', 'required' => false, 'aliases' => array('pl', 'kode_pl', 'profit center')),
		'debet' => array('label' => 'debet', 'required' => false, 'aliases' => array('debet', 'debit')),
		'kredit' => array('label' => 'kredit', 'required' => false, 'aliases' => array('kredit', 'credit')),
	);
}

function jurnal_penyesuaian_compare_build_column_map($fields)
{
	$analysis = jurnal_penyesuaian_compare_analyze_column_map($fields);
	return !empty($analysis['ok']) ? $analysis['map'] : null;
}

function jurnal_penyesuaian_compare_analyze_column_map($fields)
{
	if (!is_array($fields) || count($fields) === 0) {
		return array('ok' => false, 'message' => 'Tabel tidak memiliki kolom.');
	}

	$normalized = array();
	foreach ($fields as $field) {
		$normalized[] = trim((string) $field);
	}

	$defs = jurnal_penyesuaian_compare_field_definitions();
	$map = array(
		'tanggal' => penjualan_jurnal_compare_pick_tanggal_column($normalized),
		'kode_akun' => persediaan_compare_pick_column($normalized, $defs['kode_akun']['aliases']),
		'keterangan' => persediaan_compare_pick_column($normalized, $defs['keterangan']['aliases']),
		'kode_rekening' => persediaan_compare_pick_column($normalized, $defs['kode_rekening']['aliases']),
		'bukti' => persediaan_compare_pick_column($normalized, $defs['bukti']['aliases']),
		'pl' => persediaan_compare_pick_column($normalized, $defs['pl']['aliases']),
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
			. '. Kolom compare: tanggal, kode_akun, keterangan, kode_rekening, debet atau kredit.';
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

function jurnal_penyesuaian_compare_validate_online_table_detail($CI)
{
	if (!$CI->db->table_exists('jurnal_penyesuaian')) {
		return array(
			'ok' => false,
			'message' => 'Tabel online `jurnal_penyesuaian` tidak ditemukan di database.',
			'missing_fields' => array('jurnal_penyesuaian (tabel)'),
		);
	}

	$fields = $CI->db->list_fields('jurnal_penyesuaian');
	$map = array(
		'tanggal' => in_array('tanggal', $fields, true) ? 'tanggal' : null,
		'kode_akun' => in_array('kode_akun', $fields, true) ? 'kode_akun' : null,
		'keterangan' => in_array('keterangan', $fields, true) ? 'keterangan' : null,
		'kode_rekening' => in_array('kode_rekening', $fields, true) ? 'kode_rekening' : null,
		'bukti' => in_array('bukti', $fields, true) ? 'bukti' : null,
		'pl' => in_array('pl', $fields, true) ? 'pl' : null,
		'debet' => in_array('debet', $fields, true) ? 'debet' : null,
		'kredit' => in_array('kredit', $fields, true) ? 'kredit' : null,
	);

	$critical_missing = array();
	if (empty($map['tanggal'])) {
		$critical_missing[] = 'tanggal';
	}
	if (empty($map['kode_akun'])) {
		$critical_missing[] = 'kode_akun';
	}
	if (empty($map['keterangan'])) {
		$critical_missing[] = 'keterangan';
	}
	if (empty($map['kode_rekening'])) {
		$critical_missing[] = 'kode_rekening';
	}
	if (empty($map['debet']) && empty($map['kredit'])) {
		$critical_missing[] = 'debet atau kredit';
	}

	$soft_missing = array();
	foreach (array('bukti', 'pl') as $k) {
		if (empty($map[$k])) {
			$soft_missing[] = $k;
		}
	}

	$ok = count($critical_missing) === 0;
	$message = '';
	if (!$ok) {
		$message = 'Tabel online `jurnal_penyesuaian` tidak memiliki kolom wajib: ' . implode(', ', $critical_missing) . '.';
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
		'table' => 'jurnal_penyesuaian',
		'map' => $map,
		'mapped' => $mapped,
		'missing_fields' => array_merge($critical_missing, $soft_missing),
		'critical_missing' => $critical_missing,
		'soft_missing' => $soft_missing,
		'fields' => $fields,
		'message' => $message,
	);
}

function jurnal_penyesuaian_compare_validate_table($CI, $table)
{
	if (!persediaan_compare_is_valid_table_name($table)) {
		return array('ok' => false, 'message' => 'Nama tabel tidak valid.');
	}
	if (!$CI->db->table_exists($table)) {
		return array('ok' => false, 'message' => 'Tabel tidak ditemukan di database.');
	}

	$fields = $CI->db->list_fields($table);
	$analysis = jurnal_penyesuaian_compare_analyze_column_map($fields);
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

function jurnal_penyesuaian_compare_normalize_tanggal_for_db($value, $ref_month = 0, $ref_year = 0)
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

function jurnal_penyesuaian_compare_row_matches_bulan($tanggal_ymd, $bulan)
{
	$tanggal_ymd = pembelian_jurnal_compare_normalize_tanggal($tanggal_ymd);
	if ($tanggal_ymd === '' || !preg_match('/^\d{4}-\d{2}$/', (string) $bulan)) {
		return false;
	}

	return substr($tanggal_ymd, 0, 7) === $bulan;
}

function jurnal_penyesuaian_compare_normalize_bukti($bukti)
{
	return strtoupper(trim((string) $bukti));
}

function jurnal_penyesuaian_compare_normalize_text($value)
{
	return strtolower(trim(preg_replace('/\s+/', ' ', (string) $value)));
}

function jurnal_penyesuaian_compare_normalize_jumlah($value)
{
	return round(pembelian_jurnal_compare_parse_jumlah_nominal($value), 2);
}

function jurnal_penyesuaian_compare_format_jumlah_display($jumlah)
{
	if ($jumlah === null || $jumlah === '' || (float) $jumlah == 0) {
		return '';
	}
	return number_format((float) $jumlah, 2, ',', '.');
}

function jurnal_penyesuaian_compare_standard_headers()
{
	return array('No', 'Tanggal', 'Bukti', 'PL', 'Keterangan', 'Kode Rek.', 'Kode Akun', 'Debet', 'Kredit', 'Catatan');
}

function jurnal_penyesuaian_compare_is_row_analyzable($row)
{
	$tanggal = pembelian_jurnal_compare_normalize_tanggal(isset($row['tanggal']) ? $row['tanggal'] : '');
	if ($tanggal === '' || $tanggal === '0000-00-00') {
		return false;
	}
	$deb = jurnal_penyesuaian_compare_normalize_jumlah(isset($row['debet']) ? $row['debet'] : 0);
	$kre = jurnal_penyesuaian_compare_normalize_jumlah(isset($row['kredit']) ? $row['kredit'] : 0);
	return ($deb > 0 || $kre > 0);
}

function jurnal_penyesuaian_compare_row_unprocessed_reasons($row)
{
	$reasons = array();
	if (pembelian_jurnal_compare_normalize_tanggal(isset($row['tanggal']) ? $row['tanggal'] : '') === '') {
		$reasons[] = 'tanggal kosong/tidak valid';
	}
	if (jurnal_penyesuaian_compare_normalize_jumlah(isset($row['debet']) ? $row['debet'] : 0) <= 0
		&& jurnal_penyesuaian_compare_normalize_jumlah(isset($row['kredit']) ? $row['kredit'] : 0) <= 0) {
		$reasons[] = 'debet dan kredit kosong';
	}
	if (trim((string) (isset($row['kode_akun']) ? $row['kode_akun'] : '')) === '') {
		$reasons[] = 'kode_akun kosong';
	}
	if (trim((string) (isset($row['keterangan']) ? $row['keterangan'] : '')) === '') {
		$reasons[] = 'keterangan kosong';
	}
	if (trim((string) (isset($row['kode_rekening']) ? $row['kode_rekening'] : '')) === '') {
		$reasons[] = 'kode_rekening kosong';
	}
	return $reasons;
}

function jurnal_penyesuaian_compare_make_hard_key($tanggal, $kode_akun, $keterangan, $kode_rekening)
{
	return pembelian_jurnal_compare_normalize_tanggal($tanggal)
		. '|' . jurnal_penyesuaian_compare_normalize_text($kode_akun)
		. '|' . jurnal_penyesuaian_compare_normalize_text($keterangan)
		. '|' . penjualan_jurnal_compare_normalize_kode_rekening($kode_rekening);
}

function jurnal_penyesuaian_compare_make_full_key($row)
{
	return jurnal_penyesuaian_compare_make_hard_key(
		isset($row['tanggal']) ? $row['tanggal'] : '',
		isset($row['kode_akun']) ? $row['kode_akun'] : '',
		isset($row['keterangan']) ? $row['keterangan'] : '',
		isset($row['kode_rekening']) ? $row['kode_rekening'] : ''
	)
		. '|' . jurnal_penyesuaian_compare_normalize_jumlah(isset($row['debet']) ? $row['debet'] : 0)
		. '|' . jurnal_penyesuaian_compare_normalize_jumlah(isset($row['kredit']) ? $row['kredit'] : 0);
}

function jurnal_penyesuaian_compare_row_to_display($row, $catatan = '')
{
	$debet = jurnal_penyesuaian_compare_normalize_jumlah(isset($row['debet']) ? $row['debet'] : 0);
	$kredit = jurnal_penyesuaian_compare_normalize_jumlah(isset($row['kredit']) ? $row['kredit'] : 0);

	return array(
		'tanggal' => isset($row['tanggal']) ? pembelian_jurnal_compare_format_tanggal_display($row['tanggal']) : '',
		'bukti' => isset($row['bukti']) ? $row['bukti'] : '',
		'pl' => isset($row['pl']) ? $row['pl'] : '',
		'keterangan' => isset($row['keterangan']) ? $row['keterangan'] : '',
		'kode_rekening' => isset($row['kode_rekening']) ? $row['kode_rekening'] : '',
		'kode_akun' => isset($row['kode_akun']) ? $row['kode_akun'] : '',
		'debet' => $debet > 0 ? jurnal_penyesuaian_compare_format_jumlah_display($debet) : '',
		'kredit' => $kredit > 0 ? jurnal_penyesuaian_compare_format_jumlah_display($kredit) : '',
		'catatan' => $catatan,
	);
}

function jurnal_penyesuaian_compare_build_diff_catatan($row, $other, $other_label)
{
	if (!is_array($row) || !is_array($other)) {
		return 'Tidak ditemukan pasangan di data ' . $other_label . '.';
	}

	$parts = array('Perbedaan dengan data ' . $other_label . ':');
	$fields = array(
		'bukti' => 'Bukti',
		'pl' => 'PL',
		'kode_akun' => 'Kode akun',
		'keterangan' => 'Keterangan',
		'kode_rekening' => 'Kode rekening',
	);

	foreach ($fields as $key => $label) {
		if (jurnal_penyesuaian_compare_normalize_text($row[$key]) !== jurnal_penyesuaian_compare_normalize_text($other[$key])) {
			$parts[] = $label . ' berbeda (Manual: ' . $row[$key] . ', ' . ucfirst($other_label) . ': ' . $other[$key] . ')';
		}
	}

	$deb_r = jurnal_penyesuaian_compare_normalize_jumlah($row['debet']);
	$deb_o = jurnal_penyesuaian_compare_normalize_jumlah($other['debet']);
	if ($deb_r !== $deb_o) {
		$parts[] = 'Debet berbeda (Manual: ' . jurnal_penyesuaian_compare_format_jumlah_display($deb_r)
			. ', ' . ucfirst($other_label) . ': ' . jurnal_penyesuaian_compare_format_jumlah_display($deb_o) . ')';
	}

	$kre_r = jurnal_penyesuaian_compare_normalize_jumlah($row['kredit']);
	$kre_o = jurnal_penyesuaian_compare_normalize_jumlah($other['kredit']);
	if ($kre_r !== $kre_o) {
		$parts[] = 'Kredit berbeda (Manual: ' . jurnal_penyesuaian_compare_format_jumlah_display($kre_r)
			. ', ' . ucfirst($other_label) . ': ' . jurnal_penyesuaian_compare_format_jumlah_display($kre_o) . ')';
	}

	if (count($parts) === 1) {
		return 'Tidak ditemukan pasangan lengkap di data ' . $other_label . ' (semua field compare harus sama).';
	}

	return implode('; ', $parts);
}

function jurnal_penyesuaian_compare_manual_row_from_db($row, $map, $default_tanggal = '')
{
	$tanggal_raw = !empty($map['tanggal']) ? persediaan_compare_row_get($row, $map['tanggal']) : $default_tanggal;
	$tanggal_norm = pembelian_jurnal_compare_normalize_tanggal($tanggal_raw);
	if (($tanggal_norm === '' || $tanggal_norm === '0000-00-00') && $default_tanggal !== '') {
		$tanggal_norm = pembelian_jurnal_compare_normalize_tanggal($default_tanggal);
	}

	return array(
		'tanggal' => $tanggal_norm,
		'kode_akun' => trim((string) (!empty($map['kode_akun']) ? persediaan_compare_row_get($row, $map['kode_akun']) : '')),
		'keterangan' => trim((string) (!empty($map['keterangan']) ? persediaan_compare_row_get($row, $map['keterangan']) : '')),
		'kode_rekening' => trim((string) (!empty($map['kode_rekening']) ? persediaan_compare_row_get($row, $map['kode_rekening']) : '')),
		'bukti' => trim((string) (!empty($map['bukti']) ? persediaan_compare_row_get($row, $map['bukti']) : '')),
		'pl' => trim((string) (!empty($map['pl']) ? persediaan_compare_row_get($row, $map['pl']) : '')),
		'debet' => jurnal_penyesuaian_compare_normalize_jumlah(!empty($map['debet']) ? persediaan_compare_row_get($row, $map['debet']) : 0),
		'kredit' => jurnal_penyesuaian_compare_normalize_jumlah(!empty($map['kredit']) ? persediaan_compare_row_get($row, $map['kredit']) : 0),
	);
}

function jurnal_penyesuaian_compare_load_manual_rows_for_bulan($CI, $table, $bulan)
{
	$valid = jurnal_penyesuaian_compare_validate_table($CI, $table);
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
		$item = jurnal_penyesuaian_compare_manual_row_from_db($row, $valid['map'], $default_tanggal);
		if (jurnal_penyesuaian_compare_row_matches_bulan($item['tanggal'], $bulan)) {
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

function jurnal_penyesuaian_compare_load_manual_all($CI, $table, $bulan)
{
	$valid = jurnal_penyesuaian_compare_validate_table($CI, $table);
	if (empty($valid['ok'])) {
		return $valid;
	}

	$loaded = jurnal_penyesuaian_compare_load_manual_rows_for_bulan($CI, $table, $bulan);
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
		$item = jurnal_penyesuaian_compare_manual_row_from_db($row, $map, $default_tanggal);
		$reasons = jurnal_penyesuaian_compare_row_unprocessed_reasons($item);
		$display_all[] = jurnal_penyesuaian_compare_row_to_display(
			$item,
			count($reasons) > 0 ? ('Info: ' . implode(', ', $reasons)) : ''
		);

		if (!jurnal_penyesuaian_compare_is_row_analyzable($item)) {
			$unprocessed[] = jurnal_penyesuaian_compare_row_to_display($item, 'Manual tidak terproses: ' . implode(', ', $reasons));
			continue;
		}

		$full_key = jurnal_penyesuaian_compare_make_full_key($item);
		$hard_key = jurnal_penyesuaian_compare_make_hard_key($item['tanggal'], $item['kode_akun'], $item['keterangan'], $item['kode_rekening']);
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

function jurnal_penyesuaian_compare_online_row_from_db($row)
{
	return array(
		'tanggal' => pembelian_jurnal_compare_normalize_tanggal(isset($row->tanggal) ? $row->tanggal : ''),
		'kode_akun' => isset($row->kode_akun) ? trim((string) $row->kode_akun) : '',
		'keterangan' => isset($row->keterangan) ? trim((string) $row->keterangan) : '',
		'kode_rekening' => isset($row->kode_rekening) ? trim((string) $row->kode_rekening) : '',
		'bukti' => isset($row->bukti) ? trim((string) $row->bukti) : '',
		'pl' => isset($row->pl) ? trim((string) $row->pl) : '',
		'debet' => jurnal_penyesuaian_compare_normalize_jumlah(isset($row->debet) ? $row->debet : 0),
		'kredit' => jurnal_penyesuaian_compare_normalize_jumlah(isset($row->kredit) ? $row->kredit : 0),
	);
}

function jurnal_penyesuaian_compare_load_online_all($CI, $bulan)
{
	$range = persediaan_compare_bulan_to_date_range($bulan);
	if ($range === null) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');
	}

	$online_fields = jurnal_penyesuaian_compare_validate_online_table_detail($CI);
	if (empty($online_fields['ok'])) {
		return array(
			'ok' => false,
			'message' => isset($online_fields['message']) ? $online_fields['message'] : 'Struktur tabel online jurnal_penyesuaian tidak valid.',
			'field_validation' => array('online' => $online_fields),
		);
	}

	$month = (int) substr($bulan, 5, 2);
	$year = (int) substr($bulan, 0, 4);

	$sql = 'SELECT * FROM jurnal_penyesuaian
		WHERE tanggal IS NOT NULL AND tanggal <> \'0000-00-00\'
		AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?
		ORDER BY tanggal, id';

	$list = array();
	$by_full = array();
	$by_hard = array();
	$unprocessed = array();
	$display_all = array();

	foreach ($CI->db->query($sql, array($month, $year))->result() as $row) {
		$item = jurnal_penyesuaian_compare_online_row_from_db($row);
		$reasons = jurnal_penyesuaian_compare_row_unprocessed_reasons($item);
		$display_all[] = jurnal_penyesuaian_compare_row_to_display(
			$item,
			count($reasons) > 0 ? ('Info: ' . implode(', ', $reasons)) : ''
		);

		if (!jurnal_penyesuaian_compare_is_row_analyzable($item)) {
			$unprocessed[] = jurnal_penyesuaian_compare_row_to_display($item, 'Online tidak terproses: ' . implode(', ', $reasons));
			continue;
		}

		$full_key = jurnal_penyesuaian_compare_make_full_key($item);
		$hard_key = jurnal_penyesuaian_compare_make_hard_key($item['tanggal'], $item['kode_akun'], $item['keterangan'], $item['kode_rekening']);
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

function jurnal_penyesuaian_compare_pick_best_candidate($row, $candidates)
{
	if (!is_array($candidates) || count($candidates) === 0) {
		return null;
	}

	$best = null;
	$best_score = -1;
	foreach ($candidates as $candidate) {
		$score = 0;
		if (jurnal_penyesuaian_compare_normalize_text($row['keterangan']) === jurnal_penyesuaian_compare_normalize_text($candidate['keterangan'])) {
			$score += 3;
		}
		if (jurnal_penyesuaian_compare_normalize_text($row['kode_akun']) === jurnal_penyesuaian_compare_normalize_text($candidate['kode_akun'])) {
			$score += 2;
		}
		if (jurnal_penyesuaian_compare_normalize_jumlah($row['debet']) === jurnal_penyesuaian_compare_normalize_jumlah($candidate['debet'])) {
			$score += 2;
		}
		if (jurnal_penyesuaian_compare_normalize_jumlah($row['kredit']) === jurnal_penyesuaian_compare_normalize_jumlah($candidate['kredit'])) {
			$score += 2;
		}
		if ($score > $best_score) {
			$best_score = $score;
			$best = $candidate;
		}
	}

	return $best;
}

function jurnal_penyesuaian_compare_sort_display_rows($a, $b)
{
	$cmp = strcmp((string) $a['tanggal'], (string) $b['tanggal']);
	if ($cmp !== 0) {
		return $cmp;
	}
	$cmp = strcmp((string) $a['kode_akun'], (string) $b['kode_akun']);
	if ($cmp !== 0) {
		return $cmp;
	}
	return strcmp((string) $a['kode_rekening'], (string) $b['kode_rekening']);
}

function jurnal_penyesuaian_compare_run($CI, $bulan, $table = '')
{
	$table = trim((string) $table);
	if ($table === '') {
		return array('ok' => false, 'message' => 'Pilih tabel manual yang akan dibandingkan.');
	}

	$online_fields = jurnal_penyesuaian_compare_validate_online_table_detail($CI);
	if (empty($online_fields['ok'])) {
		return array(
			'ok' => false,
			'message' => isset($online_fields['message']) ? $online_fields['message'] : 'Struktur tabel online jurnal_penyesuaian tidak valid.',
			'field_validation' => array('online' => $online_fields),
		);
	}

	$manual = jurnal_penyesuaian_compare_load_manual_all($CI, $table, $bulan);
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

	$online = jurnal_penyesuaian_compare_load_online_all($CI, $bulan);
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
			$data_cocok[] = jurnal_penyesuaian_compare_row_to_display($manual_row, 'Data manual dan online cocok');
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
			$best = jurnal_penyesuaian_compare_pick_best_candidate($manual_row, $candidates);
			foreach ($online_pool as $o_idx => $online_row) {
				if ($best !== null && $online_row === $best) {
					$online_used[$o_idx] = true;
					break;
				}
			}
			$manual_tidak[] = jurnal_penyesuaian_compare_row_to_display(
				$manual_row,
				jurnal_penyesuaian_compare_build_diff_catatan($manual_row, $best, 'online')
			);
		} else {
			$manual_tidak[] = jurnal_penyesuaian_compare_row_to_display(
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
			$best = jurnal_penyesuaian_compare_pick_best_candidate($online_row, $candidates);
			$online_tidak[] = jurnal_penyesuaian_compare_row_to_display(
				$online_row,
				jurnal_penyesuaian_compare_build_diff_catatan($online_row, $best, 'manual')
			);
		} else {
			$online_tidak[] = jurnal_penyesuaian_compare_row_to_display(
				$online_row,
				'Tidak ditemukan di data manual.'
			);
		}
	}

	usort($data_cocok, 'jurnal_penyesuaian_compare_sort_display_rows');
	usort($manual_tidak, 'jurnal_penyesuaian_compare_sort_display_rows');
	usort($online_tidak, 'jurnal_penyesuaian_compare_sort_display_rows');

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

function jurnal_penyesuaian_compare_item_to_row_cells($item, $no)
{
	return array(
		$no,
		isset($item['tanggal']) ? $item['tanggal'] : '',
		isset($item['bukti']) ? $item['bukti'] : '',
		isset($item['pl']) ? $item['pl'] : '',
		isset($item['keterangan']) ? $item['keterangan'] : '',
		isset($item['kode_rekening']) ? $item['kode_rekening'] : '',
		isset($item['kode_akun']) ? $item['kode_akun'] : '',
		isset($item['debet']) ? $item['debet'] : '',
		isset($item['kredit']) ? $item['kredit'] : '',
		isset($item['catatan']) ? $item['catatan'] : '',
	);
}

function jurnal_penyesuaian_compare_excel_all_total_cols()
{
	return 54;
}

function jurnal_penyesuaian_compare_excel_all_group_definitions()
{
	return array(
		array('title' => 'DATA MANUAL', 'col_start' => 0, 'col_end' => 9),
		array('title' => 'DATA ONLINE', 'col_start' => 11, 'col_end' => 20),
		array('title' => 'DATA COCOK', 'col_start' => 22, 'col_end' => 31),
		array('title' => 'MANUAL TIDAK DI ONLINE', 'col_start' => 33, 'col_end' => 42),
		array('title' => 'ONLINE TIDAK DI MANUAL', 'col_start' => 44, 'col_end' => 53),
	);
}

function jurnal_penyesuaian_compare_excel_all_headers()
{
	$block = jurnal_penyesuaian_compare_standard_headers();
	return array_merge($block, array(''), $block, array(''), $block, array(''), $block, array(''), $block);
}

function jurnal_penyesuaian_compare_excel_sheet_name_from_bulan($bulan)
{
	$bulan = trim((string) $bulan);
	if (!preg_match('/^(\d{4})-(\d{2})$/', $bulan, $m)) {
		return 'Compare Jurnal Penyesuaian';
	}
	$tahun = (int) $m[1];
	$bulan_num = (int) $m[2];
	$nama_bulan = persediaan_compare_excel_all_bulan_nama_id();
	$label_bulan = isset($nama_bulan[$bulan_num]) ? $nama_bulan[$bulan_num] : ('Bulan ' . $bulan_num);
	return excel_sheet_safe_name('Compare Jurnal Penyesuaian ' . $label_bulan . ' ' . $tahun);
}

function jurnal_penyesuaian_compare_excel_items_to_cells($items)
{
	$rows = array();
	$no = 0;
	foreach ((array) $items as $item) {
		$no++;
		$rows[] = jurnal_penyesuaian_compare_item_to_row_cells($item, $no);
	}
	return $rows;
}

function jurnal_penyesuaian_compare_excel_all_empty_row()
{
	return array_fill(0, jurnal_penyesuaian_compare_excel_all_total_cols(), '');
}

function jurnal_penyesuaian_compare_excel_all_write_merged_rows($rowNum, $sections, $groups)
{
	$maxLen = 0;
	foreach ($sections as $sectionRows) {
		$maxLen = max($maxLen, count((array) $sectionRows));
	}
	for ($i = 0; $i < $maxLen; $i++) {
		$cells = jurnal_penyesuaian_compare_excel_all_empty_row();
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

function jurnal_penyesuaian_compare_export_excel_all_output($CI, $bulan, $table = '', $result = null)
{
	@set_time_limit(600);
	@ini_set('memory_limit', '512M');
	if ($result === null) {
		$result = jurnal_penyesuaian_compare_run($CI, $bulan, $table);
	}
	if (empty($result['ok'])) {
		xlsAddSheet('Compare Jurnal Penyesuaian');
		xlsWriteLabel(0, 0, isset($result['message']) ? $result['message'] : 'Export Excel gagal.');
		xlsMultiEOF();
		return;
	}

	$groups = jurnal_penyesuaian_compare_excel_all_group_definitions();
	$headers = jurnal_penyesuaian_compare_excel_all_headers();
	$groupTitleRow = 2;
	$headerRow = 3;
	$dataStartRow = 4;
	$stats = isset($result['stats']) ? $result['stats'] : array();

	xlsAddSheet(jurnal_penyesuaian_compare_excel_sheet_name_from_bulan($bulan));
	xlsWriteLabelBold14(0, 0, 'Compare Jurnal Penyesuaian — Manual vs Online — Bulan ' . $result['bulan_label']);
	xlsWriteLabel(1, 0, 'Tabel manual: ' . $result['table']
		. ' | Dicetak: ' . date('d/m/Y H:i:s')
		. ' | Manual: ' . (isset($stats['data_manual']) ? (int) $stats['data_manual'] : 0)
		. ' | Online: ' . (isset($stats['data_online']) ? (int) $stats['data_online'] : 0)
		. ' | Cocok: ' . (isset($stats['data_cocok']) ? (int) $stats['data_cocok'] : 0));

	persediaan_rekonsiliasi_tx_write_group_titles($groupTitleRow, $groups);
	persediaan_rekonsiliasi_tx_write_headers_grouped($headerRow, $headers, $groups);

	$sections = array(
		0 => jurnal_penyesuaian_compare_excel_items_to_cells(isset($result['data_manual']) ? $result['data_manual'] : array()),
		11 => jurnal_penyesuaian_compare_excel_items_to_cells(isset($result['data_online']) ? $result['data_online'] : array()),
		22 => jurnal_penyesuaian_compare_excel_items_to_cells(isset($result['data_cocok']) ? $result['data_cocok'] : array()),
		33 => jurnal_penyesuaian_compare_excel_items_to_cells(isset($result['manual_tidak_di_online']) ? $result['manual_tidak_di_online'] : array()),
		44 => jurnal_penyesuaian_compare_excel_items_to_cells(isset($result['online_tidak_di_manual']) ? $result['online_tidak_di_manual'] : array()),
	);

	$lastRow = jurnal_penyesuaian_compare_excel_all_write_merged_rows($dataStartRow, $sections, $groups);
	if ($lastRow < $dataStartRow) {
		$lastRow = $headerRow;
	}
	persediaan_rekonsiliasi_tx_finalize_group_borders($groupTitleRow, $lastRow, $groups);
	xlsMultiEOF();
}

function jurnal_penyesuaian_compare_export_excel_output($CI, $bulan, $table = '')
{
	@set_time_limit(600);
	@ini_set('memory_limit', '512M');
	$CI->load->helper(array('exportexcel', 'persediaan_display', 'pembelian_persediaan'));

	$result = jurnal_penyesuaian_compare_run($CI, $bulan, $table);
	if (empty($result['ok'])) {
		xlsBOF();
		xlsWriteLabel(0, 0, isset($result['message']) ? $result['message'] : 'Compare gagal.');
		xlsEOF();
		return;
	}

	jurnal_penyesuaian_compare_export_excel_all_output($CI, $bulan, $table, $result);
}

function jurnal_penyesuaian_compare_validate_csv_file($filepath)
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

	$map = jurnal_penyesuaian_compare_build_column_map(persediaan_compare_sanitize_csv_headers($raw_headers));
	if ($map === null) {
		return array(
			'ok' => false,
			'stage' => 'validate_columns',
			'message' => 'Header CSV tidak memenuhi syarat Jurnal Penyesuaian. Kolom wajib: tanggal, kode_akun, keterangan, kode_rekening, debet atau kredit.',
		);
	}

	return array('ok' => true, 'map' => $map, 'headers' => $raw_headers);
}

function jurnal_penyesuaian_compare_import_csv_to_db($CI, $filepath, $original_filename, $bulan_key = '', $bulan_num = 0, $tahun = 0)
{
	$file_label = trim((string) $original_filename);
	if ($file_label === '') {
		$file_label = basename((string) $filepath);
	}

	$validated = jurnal_penyesuaian_compare_validate_csv_file($filepath);
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
	$column_map = jurnal_penyesuaian_compare_build_column_map($db_columns_sanitized);
	$csv_col_index = array();
	foreach ($db_columns_sanitized as $idx => $col) {
		$csv_col_index[$col] = $idx;
	}

	$col_tanggal = !empty($column_map['tanggal']) ? $column_map['tanggal'] : 'tanggal';
	$col_kode_akun = !empty($column_map['kode_akun']) ? $column_map['kode_akun'] : 'kode_akun';
	$col_keterangan = !empty($column_map['keterangan']) ? $column_map['keterangan'] : 'keterangan';
	$col_kode_rekening = !empty($column_map['kode_rekening']) ? $column_map['kode_rekening'] : 'kode_rekening';
	$col_bukti = !empty($column_map['bukti']) ? $column_map['bukti'] : 'bukti';
	$col_pl = !empty($column_map['pl']) ? $column_map['pl'] : 'pl';
	$col_debet = !empty($column_map['debet']) ? $column_map['debet'] : 'debet';
	$col_kredit = !empty($column_map['kredit']) ? $column_map['kredit'] : 'kredit';

	$db_columns = array();
	foreach ($db_columns_sanitized as $col) {
		if (strtolower($col) === 'id') {
			continue;
		}
		$db_columns[] = $col;
	}
	foreach (array($col_tanggal, $col_kode_akun, $col_keterangan, $col_kode_rekening, $col_bukti, $col_pl, $col_debet, $col_kredit) as $required_col) {
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

		$data[$col_tanggal] = jurnal_penyesuaian_compare_normalize_tanggal_for_db(
			isset($data[$col_tanggal]) ? $data[$col_tanggal] : '',
			$bulan_tahun_ref['month'],
			$bulan_tahun_ref['year']
		);

		if ($col_debet !== '') {
			$data[$col_debet] = jurnal_penyesuaian_compare_normalize_jumlah(isset($data[$col_debet]) ? $data[$col_debet] : 0);
		}
		if ($col_kredit !== '') {
			$data[$col_kredit] = jurnal_penyesuaian_compare_normalize_jumlah(isset($data[$col_kredit]) ? $data[$col_kredit] : 0);
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
