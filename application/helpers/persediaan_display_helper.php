<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Format & hitungan tampilan persediaan (list + export Excel).
 */

function persediaan_format_bulan_tahun($row, $bulan_filter = '')
{
	if (is_object($row) && !empty($row->tanggal_beli)) {
		$ts = strtotime($row->tanggal_beli);
		if ($ts !== false) {
			return date('m/Y', $ts);
		}
	}

	$bulan_filter = trim((string) $bulan_filter);
	if ($bulan_filter !== '' && preg_match('/^\d{4}-\d{2}$/', $bulan_filter)) {
		$ts = strtotime($bulan_filter . '-01');
		if ($ts !== false) {
			return date('m/Y', $ts);
		}
	}

	if (!is_object($row)) {
		return '';
	}

	$tanggal = trim((string) $row->tanggal);
	if ($tanggal === '') {
		return '';
	}

	if (preg_match('#^(\d{1,2})/(\d{1,2})/(\d{4})$#', $tanggal, $m)) {
		return sprintf('%02d/%s', (int) $m[2], $m[3]);
	}

	$ts = strtotime(str_replace('/', '-', $tanggal));
	if ($ts !== false) {
		return date('m/Y', $ts);
	}

	return $tanggal;
}

function persediaan_parse_angka($value)
{
	$v = trim((string) $value);
	if ($v === '' || $v === '-') {
		return 0;
	}
	$v = str_replace(',', '', $v);
	$v = preg_replace('/[^0-9.\-]/', '', $v);
	if ($v === '' || $v === '-') {
		return 0;
	}
	return (float) $v;
}

function persediaan_hitung_sisa_stock($row)
{
	$total_10 = persediaan_parse_angka(isset($row->total_10) ? $row->total_10 : 0);
	$penjualan = persediaan_parse_angka(isset($row->penjualan) ? $row->penjualan : 0);
	$pecah_satuan = persediaan_parse_angka(isset($row->pecah_satuan) ? $row->pecah_satuan : 0);
	$bahan_produksi = persediaan_parse_angka(isset($row->bahan_produksi) ? $row->bahan_produksi : 0);
	return $total_10 - ($penjualan + $pecah_satuan + $bahan_produksi);
}

/**
 * Alias nama kolom persediaan (DB lama vs aplikasi).
 */
function persediaan_field_lookup_keys($field)
{
	static $aliases = array(
		'sekret' => array('sekret', 'Sekretariat'),
		'cetak' => array('cetak', 'CETAK'),
		'grafikita' => array('grafikita', 'GRAFIKITA'),
	);

	$field = (string) $field;
	if (isset($aliases[$field])) {
		return $aliases[$field];
	}

	foreach ($aliases as $canonical => $keys) {
		if (in_array($field, $keys, true)) {
			return $keys;
		}
	}

	return array($field);
}

function persediaan_row_get($row, $field)
{
	if (!is_object($row)) {
		return '';
	}

	foreach (persediaan_field_lookup_keys($field) as $prop) {
		if (isset($row->{$prop})) {
			return $row->{$prop};
		}
	}

	$field_lower = strtolower((string) $field);
	foreach (get_object_vars($row) as $prop => $val) {
		if (strtolower((string) $prop) === $field_lower) {
			return $val;
		}
	}

	return '';
}

function persediaan_field_label($field)
{
	static $labels = array(
		'tgl_keluar' => 'Tgl Keluar',
		'sekret' => 'Sekret',
		'Sekretariat' => 'Sekret',
		'cetak' => 'Cetak',
		'CETAK' => 'Cetak',
		'grafikita' => 'Grafikita',
		'GRAFIKITA' => 'Grafikita',
		'dinas_umum' => 'Dinas Umum',
		'atk_rsud' => 'Atk Rsud',
		'ppbmp_kbs' => 'Ppbmp Kbs',
		'kbs' => 'Kbs',
		'ppbmp' => 'Ppbmp',
		'medis' => 'Medis',
		'siiplah_bosda' => 'Siiplah Bosda',
		'sembako' => 'Sembako',
		'fc_gose' => 'Fc Gose',
		'fc_manding' => 'Fc Manding',
		'fc_psamya' => 'Fc Psamya',
		'kop_mp' => 'Kop Mp',
		'pu_outsor' => 'Pu Outsor',
		'total_10' => 'Total 10',
	);

	$field = (string) $field;
	if (isset($labels[$field])) {
		return $labels[$field];
	}

	return ucwords(str_replace('_', ' ', $field));
}

/**
 * Kolom persediaan dari tgl_keluar sampai total_10 (inklusif), urut sesuai DB.
 */
function persediaan_list_fields_tgl_keluar_sampai_total_10($CI = null)
{
	static $fallback = array(
		'tgl_keluar',
		'sekret',
		'cetak',
		'grafikita',
		'dinas_umum',
		'atk_rsud',
		'ppbmp_kbs',
		'kbs',
		'ppbmp',
		'medis',
		'siiplah_bosda',
		'sembako',
		'fc_gose',
		'fc_manding',
		'fc_psamya',
		'kop_mp',
		'pu_outsor',
		'total_10',
	);

	if ($CI === null) {
		$CI =& get_instance();
	}

	if (!$CI->db->table_exists('persediaan')) {
		return $fallback;
	}

	$fields = array();
	$started = false;
	foreach ($CI->db->list_fields('persediaan') as $field) {
		if ($field === 'tgl_keluar') {
			$started = true;
		}
		if ($started) {
			$fields[] = $field;
			if ($field === 'total_10') {
				break;
			}
		}
	}

	return !empty($fields) ? $fields : $fallback;
}

function persediaan_list_prefix_column_count()
{
	return 10;
}

function persediaan_list_col_index_total_10($CI = null)
{
	return persediaan_list_prefix_column_count()
		+ count(persediaan_list_fields_tgl_keluar_sampai_total_10($CI))
		- 1;
}

function persediaan_list_col_index_nilai_persediaan($CI = null)
{
	return persediaan_list_col_index_total_10($CI) + 1;
}

function persediaan_hitung_nilai_persediaan_row($row)
{
	$total_10 = persediaan_parse_angka(persediaan_row_get($row, 'total_10'));
	$hpp = persediaan_parse_angka(isset($row->hpp) ? $row->hpp : persediaan_row_get($row, 'hpp'));
	return $total_10 * $hpp;
}

function persediaan_format_angka_tampil($value)
{
	if (!is_numeric($value)) {
		return $value;
	}
	if (floor($value) == $value) {
		return number_format($value, 0, ',', '.');
	}
	return number_format($value, 2, ',', '.');
}

function persediaan_export_headers($CI = null)
{
	$headers = array(
		'No',
		'Tanggal',
		'Kategori',
		'Namabarang',
		'Satuan',
		'Hpp',
		'Sa',
		'Spop',
		'Beli',
		'Tuj',
	);

	foreach (persediaan_list_fields_tgl_keluar_sampai_total_10($CI) as $field) {
		$headers[] = persediaan_field_label($field);
	}

	$headers[] = 'Nilai Persediaan';
	$headers[] = 'Terjual';
	$headers[] = 'Jumlah Pecah Satuan';
	$headers[] = 'Bahan Produksi';
	$headers[] = 'Sisa / Stock';

	return $headers;
}

function persediaan_export_row_cells($row, $no, $bulan_filter = '', $CI = null)
{
	$penjualan = isset($row->penjualan) ? $row->penjualan : 0;
	$pecah_satuan = isset($row->pecah_satuan) ? $row->pecah_satuan : 0;
	$bahan_produksi = isset($row->bahan_produksi) ? $row->bahan_produksi : 0;
	$sisa = persediaan_hitung_sisa_stock($row);
	$nilai_persediaan = persediaan_hitung_nilai_persediaan_row($row);

	$cells = array(
		$no,
		persediaan_format_bulan_tahun($row, $bulan_filter),
		isset($row->kategori) ? $row->kategori : '',
		isset($row->namabarang) ? $row->namabarang : '',
		isset($row->satuan) ? $row->satuan : '',
		isset($row->hpp) ? $row->hpp : '',
		isset($row->sa) ? $row->sa : '',
		isset($row->spop) ? $row->spop : '',
		isset($row->beli) ? $row->beli : '',
		isset($row->tuj) ? $row->tuj : '',
	);

	foreach (persediaan_list_fields_tgl_keluar_sampai_total_10($CI) as $field) {
		$cells[] = persediaan_row_get($row, $field);
	}

	$cells[] = persediaan_format_angka_tampil($nilai_persediaan);
	$cells[] = $penjualan;
	$cells[] = $pecah_satuan;
	$cells[] = $bahan_produksi;
	$cells[] = is_numeric($sisa) && floor($sisa) == $sisa ? (int) $sisa : $sisa;

	return $cells;
}

function persediaan_list_unit_columns($CI = null)
{
	$units = array();
	foreach (persediaan_list_fields_tgl_keluar_sampai_total_10($CI) as $field) {
		if ($field !== 'tgl_keluar' && $field !== 'total_10') {
			$units[] = $field;
		}
	}
	return $units;
}

function persediaan_resolve_db_field_name($CI, $field, $table = 'persediaan')
{
	$field = trim((string) $field);
	if ($field === '' || !$CI->db->table_exists($table)) {
		return $field;
	}

	if ($CI->db->field_exists($field, $table)) {
		return $field;
	}

	foreach (persediaan_field_lookup_keys($field) as $alias) {
		if ($CI->db->field_exists($alias, $table)) {
			return $alias;
		}
	}

	$field_lower = strtolower($field);
	foreach ($CI->db->list_fields($table) as $db_field) {
		if (strtolower((string) $db_field) === $field_lower) {
			return $db_field;
		}
	}

	return $field;
}

function persediaan_normalize_unit_key($text)
{
	$key = strtolower(trim((string) $text));
	$key = preg_replace('/[^a-z0-9]+/', '_', $key);
	return trim($key, '_');
}

/**
 * Cocokkan teks unit penjualan (field unit) ke kolom persediaan aktual di DB.
 */
function persediaan_resolve_unit_column_from_text($CI, $unit_txt)
{
	$unit_txt = trim((string) $unit_txt);
	if ($unit_txt === '') {
		return null;
	}

	$unit_cols = persediaan_list_unit_columns($CI);
	$key = persediaan_normalize_unit_key($unit_txt);

	foreach ($unit_cols as $kolom) {
		if (strcasecmp($unit_txt, $kolom) === 0) {
			return $kolom;
		}
	}

	foreach ($unit_cols as $kolom) {
		if (persediaan_normalize_unit_key($kolom) === $key) {
			return $kolom;
		}
	}

	static $alias_to_col = array(
		'pu_outsourcing' => array('pu_outsor'),
		'pu_outsor' => array('pu_outsor'),
		'pu_ppbmp' => array('ppbmp', 'ppbmp_kbs'),
		'ppbmp' => array('ppbmp', 'ppbmp_kbs'),
		'pu_kbs' => array('kbs', 'ppbmp_kbs'),
		'pu_atk' => array('atk_rsud'),
		'pu_sembako' => array('sembako'),
		'pu_fc_parasamya' => array('fc_psamya'),
		'pu_fc_manding' => array('fc_manding'),
		'pu_fc_gose' => array('fc_gose'),
		'pu_persampahan' => array('fc_psamya'),
		'sekretariat' => array('Sekretariat', 'sekret'),
		'cetak' => array('CETAK', 'cetak'),
		'grafikita' => array('GRAFIKITA', 'grafikita'),
	);

	if (isset($alias_to_col[$key])) {
		foreach ($alias_to_col[$key] as $candidate) {
			$resolved = persediaan_resolve_db_field_name($CI, $candidate);
			if ($CI->db->field_exists($resolved, 'persediaan')) {
				return $resolved;
			}
		}
	}

	foreach ($unit_cols as $kolom) {
		$col_key = persediaan_normalize_unit_key($kolom);
		if ($col_key !== '' && (strpos($key, $col_key) !== false || strpos($col_key, $key) !== false)) {
			return $kolom;
		}
	}

	return null;
}

function persediaan_generate_distribusi_nol_fields($CI = null)
{
	$fields = array();
	foreach (persediaan_list_fields_tgl_keluar_sampai_total_10($CI) as $field) {
		if ($field === 'total_10') {
			continue;
		}
		$fields[$field] = '0';
	}
	return $fields;
}

function persediaan_export_footer_cells($total_total_10, $total_nilai_persediaan, $CI = null)
{
	$headers = persediaan_export_headers($CI);
	$footer = array_fill(0, count($headers), '');

	$idx_total_10 = persediaan_list_col_index_total_10($CI);
	$idx_nilai = persediaan_list_col_index_nilai_persediaan($CI);

	if ($idx_total_10 > 0) {
		$footer[$idx_total_10 - 1] = 'Total';
	}
	$footer[$idx_total_10] = persediaan_format_angka_tampil($total_total_10);
	$footer[$idx_nilai] = persediaan_format_angka_tampil($total_nilai_persediaan);

	return $footer;
}
