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
	return persediaan_hitung_total_10_net($row);
}

/**
 * Stok akhir tampilan tab Persediaan:
 * total_10 (nilai di DB) dikurangi terjual + pecah_satuan + bahan_produksi jika masih stok kotor;
 * jika total_10 sudah net (setelah recalculate), pakai nilai DB.
 */
function persediaan_hitung_total_10_net($row)
{
	$total_10 = persediaan_parse_angka(persediaan_row_get($row, 'total_10'));
	$penjualan = persediaan_parse_angka(persediaan_row_get($row, 'penjualan'));
	$pecah_satuan = persediaan_parse_angka(persediaan_row_get($row, 'pecah_satuan'));
	$bahan_produksi = persediaan_parse_angka(persediaan_row_get($row, 'bahan_produksi'));
	$deductions = $penjualan + $pecah_satuan + $bahan_produksi;

	if ($deductions <= 0) {
		return max(0, (int) floor($total_10));
	}

	$sa = persediaan_parse_angka(persediaan_row_get($row, 'sa'));
	$beli = persediaan_parse_angka(persediaan_row_get($row, 'beli'));
	$gross = $sa + $beli;
	$net_expected = max(0, (int) floor($gross - $deductions));

	if ($gross > 0 && abs($total_10 - $gross) < 0.01) {
		return max(0, (int) floor($total_10 - $deductions));
	}

	if (abs($total_10 - $net_expected) < 0.01) {
		return max(0, (int) floor($total_10));
	}

	return max(0, (int) floor($total_10 - $deductions));
}

function persediaan_tampil_total_10_net_row($row)
{
	$net = persediaan_hitung_total_10_net($row);
	if ($net == 0) {
		return persediaan_row_get($row, 'total_10') === '0' || persediaan_row_get($row, 'total_10') === 0 ? '0' : '';
	}
	return persediaan_format_angka_tampil($net);
}

/**
 * Halaman Stock: total_10 = (sa + beli) - (penjualan + pecah_satuan + bahan_produksi).
 */
function persediaan_hitung_total_10_kalkulasi($row)
{
	$parts = persediaan_gen_recalc_total_10_formula_parts($row);
	return (int) $parts['total_10_kalkulasi'];
}

/**
 * Komponen rumus total_10: (sa + beli) - (penjualan + pecah_satuan + bahan_produksi).
 */
function persediaan_gen_recalc_total_10_formula_parts($row)
{
	$sa = max(0, (int) floor(persediaan_parse_angka(persediaan_row_get($row, 'sa'))));
	$beli = max(0, (int) floor(persediaan_parse_angka(persediaan_row_get($row, 'beli'))));
	$penjualan = max(0, (int) floor(persediaan_parse_angka(persediaan_row_get($row, 'penjualan'))));
	$pecah_satuan = max(0, (int) floor(persediaan_parse_angka(persediaan_row_get($row, 'pecah_satuan'))));
	$bahan_produksi = max(0, (int) floor(persediaan_parse_angka(persediaan_row_get($row, 'bahan_produksi'))));
	$gross = $sa + $beli;

	$penjualan = min($penjualan, $gross);
	$sisa_setelah_penj = max(0, $gross - $penjualan);
	$pecah_satuan = min($pecah_satuan, $sisa_setelah_penj);
	$sisa_setelah_pecah = max(0, $sisa_setelah_penj - $pecah_satuan);
	$bahan_produksi = min($bahan_produksi, $sisa_setelah_pecah);

	return array(
		'sa' => $sa,
		'beli' => $beli,
		'penjualan' => $penjualan,
		'pecah_satuan' => $pecah_satuan,
		'bahan_produksi' => $bahan_produksi,
		'gross' => $gross,
		'total_10_kalkulasi' => max(0, $gross - $penjualan - $pecah_satuan - $bahan_produksi),
	);
}

/**
 * Keterangan review jika total_10 aktual ≠ kalkulasi rumus stok.
 */
function persediaan_gen_recalc_check_total_10_keterangan($row, $total_10_aktual = null)
{
	$parts = persediaan_gen_recalc_total_10_formula_parts($row);
	$kalk = (int) $parts['total_10_kalkulasi'];
	$aktual = $total_10_aktual !== null
		? max(0, (int) floor(persediaan_parse_angka($total_10_aktual)))
		: max(0, (int) floor(persediaan_parse_angka(persediaan_row_get($row, 'total_10'))));

	if ($aktual === $kalk) {
		return '';
	}

	return 'SELISIH total_10: nilai=' . $aktual . ', kalkulasi=' . $kalk
		. ' | (sa ' . $parts['sa'] . ' + beli ' . $parts['beli'] . ')'
		. ' - (penj ' . $parts['penjualan'] . ' + pecah ' . $parts['pecah_satuan'] . ' + prod ' . $parts['bahan_produksi'] . ')';
}

function persediaan_gen_recalc_check_total_10_from_values($sa, $beli, $penjualan, $pecah_satuan, $bahan_produksi, $total_10_aktual)
{
	$row = (object) array(
		'sa' => $sa,
		'beli' => $beli,
		'penjualan' => $penjualan,
		'pecah_satuan' => $pecah_satuan,
		'bahan_produksi' => $bahan_produksi,
		'total_10' => $total_10_aktual,
	);
	return persediaan_gen_recalc_check_total_10_keterangan($row, $total_10_aktual);
}

function persediaan_hitung_nilai_persediaan_stock_row($row)
{
	$total_10 = persediaan_hitung_total_10_kalkulasi($row);
	$hpp = persediaan_parse_angka(isset($row->hpp) ? $row->hpp : persediaan_row_get($row, 'hpp'));
	return $total_10 * $hpp;
}

function persediaan_tampil_total_10_stock_row($row)
{
	$val = persediaan_hitung_total_10_kalkulasi($row);
	if ($val == 0) {
		$raw = persediaan_row_get($row, 'total_10');
		return ($raw === '0' || $raw === 0) ? '0' : '';
	}
	return persediaan_format_angka_tampil($val);
}

/**
 * Tab Data Persediaan: baris ditampilkan/di-export jika namabarang terisi
 * dan minimal salah satu sa, beli, atau total_10 bernilai lebih dari 0.
 */
function persediaan_row_layak_tampil_tab_data($row)
{
	$nama = trim((string) persediaan_row_get($row, 'namabarang'));
	if ($nama === '') {
		return false;
	}

	$sa = persediaan_parse_angka(persediaan_row_get($row, 'sa'));
	$beli = persediaan_parse_angka(persediaan_row_get($row, 'beli'));
	$total_10 = persediaan_parse_angka(persediaan_row_get($row, 'total_10'));
	$bahan_produksi = persediaan_parse_angka(persediaan_row_get($row, 'bahan_produksi'));
	$penjualan = persediaan_parse_angka(persediaan_row_get($row, 'penjualan'));
	$pecah_satuan = persediaan_parse_angka(persediaan_row_get($row, 'pecah_satuan'));

	return ($sa > 0 || $beli > 0 || $total_10 > 0 || $bahan_produksi > 0 || $penjualan > 0 || $pecah_satuan > 0);
}

function persediaan_filter_rows_tab_data($rows)
{
	if (!is_array($rows) || count($rows) === 0) {
		return array();
	}

	$filtered = array();
	foreach ($rows as $row) {
		if (persediaan_row_layak_tampil_tab_data($row)) {
			$filtered[] = $row;
		}
	}

	return $filtered;
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
	return 11;
}

function persediaan_list_col_index_sa()
{
	return 5;
}

function persediaan_list_col_index_sa_nominal()
{
	return 6;
}

function persediaan_list_col_index_beli()
{
	return 8;
}

function persediaan_list_col_index_beli_nominal()
{
	return 9;
}

/**
 * Nominal SA baris = sa × hpp.
 */
function persediaan_hitung_sa_nominal_row($row)
{
	$sa = persediaan_parse_angka(isset($row->sa) ? $row->sa : persediaan_row_get($row, 'sa'));
	if ($sa == 0.0) {
		return 0;
	}
	$hpp = persediaan_parse_angka(isset($row->hpp) ? $row->hpp : persediaan_row_get($row, 'hpp'));

	return $sa * $hpp;
}

function persediaan_tampil_sa_nominal_row($row)
{
	return persediaan_format_rupiah_tampil(persediaan_hitung_sa_nominal_row($row));
}

/**
 * Nominal beli baris = beli × hpp.
 */
function persediaan_hitung_beli_nominal_row($row)
{
	$beli = persediaan_parse_angka(isset($row->beli) ? $row->beli : persediaan_row_get($row, 'beli'));
	if ($beli == 0.0) {
		return 0;
	}
	$hpp = persediaan_parse_angka(isset($row->hpp) ? $row->hpp : persediaan_row_get($row, 'hpp'));

	return $beli * $hpp;
}

function persediaan_tampil_beli_nominal_row($row)
{
	return persediaan_format_rupiah_tampil(persediaan_hitung_beli_nominal_row($row));
}

/**
 * Kolom unit (antara tgl_keluar dan total_10) yang mendapat kolom tambahan *_Nominal.
 */
function persediaan_field_has_nominal_column($field)
{
	return $field !== 'tgl_keluar' && $field !== 'total_10';
}

function persediaan_field_nominal_header_label($field)
{
	return persediaan_field_label($field) . ' Nominal';
}

/**
 * Nominal baris = qty kolom unit × hpp (0 jika qty kosong/0).
 */
function persediaan_hitung_kolom_nominal_row($row, $field)
{
	$qty = persediaan_parse_angka(persediaan_row_get($row, $field));
	if ($qty == 0.0) {
		return 0;
	}
	$hpp = persediaan_parse_angka(isset($row->hpp) ? $row->hpp : persediaan_row_get($row, 'hpp'));
	return $qty * $hpp;
}

function persediaan_tampil_kolom_nominal_row($row, $field)
{
	$nominal = persediaan_hitung_kolom_nominal_row($row, $field);
	return persediaan_format_rupiah_tampil($nominal);
}

/**
 * Jumlah kolom distribusi (tgl_keluar..total_10) termasuk kolom *_Nominal.
 */
function persediaan_count_distribusi_columns($CI = null)
{
	$n = 0;
	foreach (persediaan_list_fields_tgl_keluar_sampai_total_10($CI) as $field) {
		$n++;
		if (persediaan_field_has_nominal_column($field)) {
			$n++;
		}
	}
	return $n;
}

function persediaan_list_col_index_total_10($CI = null)
{
	$idx = persediaan_list_prefix_column_count();
	foreach (persediaan_list_fields_tgl_keluar_sampai_total_10($CI) as $field) {
		if ($field === 'total_10') {
			return $idx;
		}
		$idx++;
		if (persediaan_field_has_nominal_column($field)) {
			$idx++;
		}
	}
	return $idx;
}

function persediaan_list_col_index_nilai_persediaan($CI = null)
{
	return persediaan_list_col_index_total_10($CI) + 1;
}

function persediaan_list_col_index_terjual($CI = null)
{
	return persediaan_list_col_index_nilai_persediaan($CI) + 1;
}

function persediaan_list_col_index_pecah_satuan($CI = null)
{
	return persediaan_list_col_index_nilai_persediaan($CI) + 2;
}

function persediaan_list_col_index_bahan_produksi($CI = null)
{
	return persediaan_list_col_index_nilai_persediaan($CI) + 3;
}

/**
 * Baris footer datatable / export (label Total + jumlah per kolom).
 */
function persediaan_datatable_footer_cells($total_total_10, $total_nilai_persediaan, $totals_nominal_unit = null, $CI = null, $show_keluar_columns = true, $total_sa = null, $total_beli = null, $total_sa_nominal = null, $total_beli_nominal = null, $total_terjual = null, $total_pecah_satuan = null, $total_bahan_produksi = null)
{
	$totals_nominal_unit = is_array($totals_nominal_unit) ? $totals_nominal_unit : array();
	$footer = array();
	$prefix_count = persediaan_list_prefix_column_count();

	for ($i = 0; $i < $prefix_count; $i++) {
		$footer[] = ($i === 0) ? 'Total' : '';
	}

	$idx_sa = persediaan_list_col_index_sa();
	$idx_sa_nominal = persediaan_list_col_index_sa_nominal();
	$idx_beli = persediaan_list_col_index_beli();
	$idx_beli_nominal = persediaan_list_col_index_beli_nominal();
	if ($total_sa !== null && isset($footer[$idx_sa])) {
		$footer[$idx_sa] = persediaan_format_angka_tampil($total_sa);
	}
	if ($total_sa_nominal !== null && isset($footer[$idx_sa_nominal])) {
		$footer[$idx_sa_nominal] = persediaan_format_rupiah_tampil($total_sa_nominal, true);
	}
	if ($total_beli !== null && isset($footer[$idx_beli])) {
		$footer[$idx_beli] = persediaan_format_angka_tampil($total_beli);
	}
	if ($total_beli_nominal !== null && isset($footer[$idx_beli_nominal])) {
		$footer[$idx_beli_nominal] = persediaan_format_rupiah_tampil($total_beli_nominal, true);
	}

	foreach (persediaan_list_fields_tgl_keluar_sampai_total_10($CI) as $field) {
		if ($field === 'tgl_keluar') {
			$footer[] = '';
		} elseif ($field === 'total_10') {
			// Satu kolom saja (selaras header total_10, tanpa kolom nominal terpisah).
			$footer[] = persediaan_format_angka_tampil($total_total_10);
		} else {
			$footer[] = '';
			if (persediaan_field_has_nominal_column($field)) {
				$sum_nom = isset($totals_nominal_unit[$field]) ? (float) $totals_nominal_unit[$field] : 0;
				$footer[] = persediaan_format_rupiah_tampil($sum_nom, true);
			}
		}
	}

	$footer[] = persediaan_format_rupiah_tampil($total_nilai_persediaan, true);
	if ($show_keluar_columns) {
		$footer[] = ($total_terjual !== null)
			? persediaan_format_angka_tampil($total_terjual)
			: '';
		$footer[] = ($total_pecah_satuan !== null)
			? persediaan_format_angka_tampil($total_pecah_satuan)
			: '';
		$footer[] = ($total_bahan_produksi !== null)
			? persediaan_format_angka_tampil($total_bahan_produksi)
			: '';
	}

	return $footer;
}

function persediaan_hitung_nilai_persediaan_row($row)
{
	$total_10 = persediaan_hitung_total_10_net($row);
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

/**
 * Format tampilan uang/rupiah (pemisah ribuan Indonesia), kosong jika nol.
 */
function persediaan_format_rupiah_tampil($value, $always_show = false)
{
	if ($value === null || $value === '') {
		return '';
	}

	$raw = trim((string) $value);
	if ($raw === '' || $raw === '-') {
		return '';
	}

	if (!is_numeric($value) && !preg_match('/^[\d\s.,\-]+$/', $raw)) {
		return $value;
	}

	$angka = persediaan_parse_angka($value);
	if (!$always_show && $angka == 0.0) {
		return '';
	}

	return persediaan_format_angka_tampil($angka);
}

function persediaan_list_col_index_hpp($CI = null)
{
	return 4;
}

function persediaan_tab_data_show_keluar_columns($tab_mode = 'barang')
{
	return strtolower(trim((string) $tab_mode)) !== 'jasa';
}

function persediaan_tab_data_nama_barang_header($tab_mode = 'barang')
{
	return strtolower(trim((string) $tab_mode)) === 'jasa' ? 'Nama Jasa' : 'Namabarang';
}

/**
 * Header kolom awal tab Data Barang/Jasa (tanpa kategori).
 */
function persediaan_tab_data_prefix_headers($tab_mode = 'barang')
{
	return array(
		'No',
		'Tanggal',
		persediaan_tab_data_nama_barang_header($tab_mode),
		'Satuan',
		'Hpp',
		'Sa',
		'Sa. Nominal',
		'Spop',
		'Beli',
		'Beli Nmnl',
		'Tuj',
	);
}

function persediaan_tab_data_export_headers($CI = null, $show_keluar_columns = true, $tab_mode = 'barang')
{
	$headers = persediaan_tab_data_prefix_headers($tab_mode);

	foreach (persediaan_list_fields_tgl_keluar_sampai_total_10($CI) as $field) {
		$headers[] = persediaan_field_label($field);
		if (persediaan_field_has_nominal_column($field)) {
			$headers[] = persediaan_field_nominal_header_label($field);
		}
	}

	$headers[] = 'Nilai Persediaan';
	if ($show_keluar_columns) {
		$headers[] = 'Terjual';
		$headers[] = 'Jumlah Pecah Satuan';
		$headers[] = 'Bahan Produksi';
	}

	return $headers;
}

function persediaan_tab_data_export_column_types($CI = null, $show_keluar_columns = true, $tab_mode = 'barang')
{
	$types = array('number', 'text', 'text', 'text', 'number', 'number', 'number', 'number', 'number', 'number', 'number');

	foreach (persediaan_list_fields_tgl_keluar_sampai_total_10($CI) as $field) {
		if ($field === 'tgl_keluar') {
			$types[] = 'text';
		} else {
			$types[] = 'number';
			if (persediaan_field_has_nominal_column($field)) {
				$types[] = 'number';
			}
		}
	}

	$types[] = 'number';
	if ($show_keluar_columns) {
		$types[] = 'number';
		$types[] = 'number';
		$types[] = 'number';
	}

	return $types;
}

/**
 * Jumlah kolom kiri yang di-freeze saat scroll horizontal (No + Tanggal s/d Beli).
 */
function persediaan_tab_data_fixed_left_columns()
{
	return 9;
}

/**
 * Indeks kolom datatable tab Barang/Jasa yang ditampilkan sebagai uang (rata kanan).
 */
function persediaan_tab_data_money_column_indexes($CI = null)
{
	$indexes = array(
		persediaan_list_col_index_hpp($CI),
		persediaan_list_col_index_sa_nominal(),
		persediaan_list_col_index_beli_nominal(),
	);

	foreach (persediaan_list_unit_columns($CI) as $field) {
		if (!persediaan_field_has_nominal_column($field)) {
			continue;
		}
		$idx = persediaan_list_col_index_unit_nominal($field, $CI);
		if ($idx >= 0) {
			$indexes[] = $idx;
		}
	}

	$indexes[] = persediaan_list_col_index_nilai_persediaan($CI);

	return array_values(array_unique(array_map('intval', $indexes)));
}

function persediaan_tab_data_is_money_column($col_index, $CI = null)
{
	return in_array((int) $col_index, persediaan_tab_data_money_column_indexes($CI), true);
}

function persediaan_tampil_hpp_row($row)
{
	$hpp = isset($row->hpp) ? $row->hpp : persediaan_row_get($row, 'hpp');
	return persediaan_format_rupiah_tampil($hpp);
}

function persediaan_tampil_nilai_persediaan_row($row)
{
	return persediaan_format_rupiah_tampil(persediaan_hitung_nilai_persediaan_row($row));
}

/**
 * Export Excel/PDF: angka 0 ditampilkan kosong (bukan "0") agar mudah dibaca.
 */
function persediaan_export_blank_if_zero($value)
{
	if ($value === null || $value === '') {
		return '';
	}

	if (is_int($value) || is_float($value)) {
		if ((float) $value == 0.0) {
			return '';
		}
		return persediaan_format_angka_tampil($value);
	}

	$v = trim((string) $value);
	if ($v === '' || $v === '-') {
		return '';
	}

	if (!preg_match('/^[\d\s.,\-]+$/', $v)) {
		return $v;
	}

	$angka = persediaan_parse_angka($v);
	if ($angka == 0.0) {
		return '';
	}

	if (is_numeric($v)) {
		return persediaan_format_angka_tampil($angka);
	}

	return $v;
}

/**
 * Tipe kolom export Excel per indeks (selaras persediaan_export_headers).
 * 'number' = nilai numerik asli di Excel (bisa SUM/AVERAGE); 'text' = teks/tanggal.
 */
function persediaan_export_column_types($CI = null)
{
	$types = array('number', 'text', 'text', 'text', 'text', 'number', 'number', 'number', 'number', 'number', 'number', 'number');

	foreach (persediaan_list_fields_tgl_keluar_sampai_total_10($CI) as $field) {
		if ($field === 'tgl_keluar') {
			$types[] = 'text';
		} else {
			$types[] = 'number';
			if (persediaan_field_has_nominal_column($field)) {
				$types[] = 'number';
			}
		}
	}

	$types[] = 'number';
	$types[] = 'number';
	$types[] = 'number';
	$types[] = 'number';

	return $types;
}

/**
 * Ubah nilai tampilan export ke angka Excel; null = sel kosong (termasuk nol).
 */
function persediaan_export_excel_number($value)
{
	if ($value === null || $value === '') {
		return null;
	}

	if (is_int($value) || is_float($value)) {
		if ((float) $value == 0.0) {
			return null;
		}
		return (float) $value;
	}

	$v = trim((string) $value);
	if ($v === '' || $v === '-' || strcasecmp($v, 'Total') === 0) {
		return null;
	}

	if (!preg_match('/^[\d\s.,\-]+$/', $v)) {
		return null;
	}

	$angka = persediaan_parse_angka($v);
	if ($angka == 0.0) {
		return null;
	}

	return (float) $angka;
}

/**
 * Tulis satu sel export persediaan (angka asli atau teks).
 */
function persediaan_export_write_cell($row, $col, $value, $col_types)
{
	$col_type = isset($col_types[$col]) ? $col_types[$col] : 'text';

	if ($col_type === 'number') {
		if (is_string($value) && strcasecmp(trim($value), 'Total') === 0) {
			xlsWriteLabel($row, $col, 'Total', 'right');
			return;
		}

		$num = persediaan_export_excel_number($value);
		if ($num === null) {
			xlsWriteLabel($row, $col, '', 'right');
			return;
		}

		xlsWriteNumber($row, $col, $num, 'right');
		return;
	}

	$align = ($value !== '' && $value !== null && persediaan_export_excel_number($value) !== null) ? 'right' : null;
	xlsWriteLabel($row, $col, $value === null ? '' : $value, $align);
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
		'Sa. Nominal',
		'Spop',
		'Beli',
		'Beli Nmnl',
		'Tuj',
	);

	foreach (persediaan_list_fields_tgl_keluar_sampai_total_10($CI) as $field) {
		$headers[] = persediaan_field_label($field);
		if (persediaan_field_has_nominal_column($field)) {
			$headers[] = persediaan_field_nominal_header_label($field);
		}
	}

	$headers[] = 'Nilai Persediaan';
	$headers[] = 'Terjual';
	$headers[] = 'Jumlah Pecah Satuan';
	$headers[] = 'Bahan Produksi';

	return $headers;
}

function persediaan_export_row_cells($row, $no, $bulan_filter = '', $CI = null)
{
	$penjualan = isset($row->penjualan) ? $row->penjualan : 0;
	$pecah_satuan = isset($row->pecah_satuan) ? $row->pecah_satuan : 0;
	$bahan_produksi = isset($row->bahan_produksi) ? $row->bahan_produksi : 0;
	$nilai_persediaan = persediaan_hitung_nilai_persediaan_row($row);

	$cells = array(
		$no,
		persediaan_format_bulan_tahun($row, $bulan_filter),
		isset($row->kategori) ? $row->kategori : '',
		isset($row->namabarang) ? $row->namabarang : '',
		isset($row->satuan) ? $row->satuan : '',
		persediaan_export_blank_if_zero(isset($row->hpp) ? $row->hpp : ''),
		persediaan_export_blank_if_zero(isset($row->sa) ? $row->sa : ''),
		persediaan_export_blank_if_zero(persediaan_hitung_sa_nominal_row($row)),
		persediaan_export_blank_if_zero(isset($row->spop) ? $row->spop : ''),
		persediaan_export_blank_if_zero(isset($row->beli) ? $row->beli : ''),
		persediaan_export_blank_if_zero(persediaan_hitung_beli_nominal_row($row)),
		persediaan_export_blank_if_zero(isset($row->tuj) ? $row->tuj : ''),
	);

	foreach (persediaan_list_fields_tgl_keluar_sampai_total_10($CI) as $field) {
		if ($field === 'tgl_keluar') {
			$cells[] = persediaan_row_get($row, $field);
		} elseif ($field === 'total_10') {
			$cells[] = persediaan_export_blank_if_zero(persediaan_hitung_total_10_net($row));
		} else {
			$cells[] = persediaan_export_blank_if_zero(persediaan_row_get($row, $field));
			if (persediaan_field_has_nominal_column($field)) {
				$cells[] = persediaan_export_blank_if_zero(persediaan_hitung_kolom_nominal_row($row, $field));
			}
		}
	}

	$cells[] = persediaan_export_blank_if_zero($nilai_persediaan);
	$cells[] = persediaan_export_blank_if_zero($penjualan);
	$cells[] = persediaan_export_blank_if_zero($pecah_satuan);
	$cells[] = persediaan_export_blank_if_zero($bahan_produksi);

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

function persediaan_export_footer_cells($total_total_10, $total_nilai_persediaan, $totals_nominal_unit = null, $CI = null, $total_sa = null, $total_beli = null, $total_sa_nominal = null, $total_beli_nominal = null, $total_terjual = null, $total_pecah_satuan = null, $total_bahan_produksi = null)
{
	$footer = persediaan_datatable_footer_cells($total_total_10, $total_nilai_persediaan, $totals_nominal_unit, $CI, true, $total_sa, $total_beli, $total_sa_nominal, $total_beli_nominal, $total_terjual, $total_pecah_satuan, $total_bahan_produksi);
	$idx_total_10 = persediaan_list_col_index_total_10($CI);
	$idx_nilai = persediaan_list_col_index_nilai_persediaan($CI);
	$idx_sa = persediaan_list_col_index_sa();
	$idx_sa_nominal = persediaan_list_col_index_sa_nominal();
	$idx_beli = persediaan_list_col_index_beli();
	$idx_beli_nominal = persediaan_list_col_index_beli_nominal();
	if (isset($footer[$idx_total_10])) {
		$footer[$idx_total_10] = persediaan_export_blank_if_zero($total_total_10);
	}
	if (isset($footer[$idx_nilai])) {
		$footer[$idx_nilai] = persediaan_export_blank_if_zero($total_nilai_persediaan);
	}
	if ($total_sa !== null && isset($footer[$idx_sa])) {
		$footer[$idx_sa] = persediaan_export_blank_if_zero($total_sa);
	}
	if ($total_sa_nominal !== null && isset($footer[$idx_sa_nominal])) {
		$footer[$idx_sa_nominal] = persediaan_export_blank_if_zero($total_sa_nominal);
	}
	if ($total_beli !== null && isset($footer[$idx_beli])) {
		$footer[$idx_beli] = persediaan_export_blank_if_zero($total_beli);
	}
	if ($total_beli_nominal !== null && isset($footer[$idx_beli_nominal])) {
		$footer[$idx_beli_nominal] = persediaan_export_blank_if_zero($total_beli_nominal);
	}
	foreach (persediaan_list_unit_columns($CI) as $field) {
		if (!persediaan_field_has_nominal_column($field)) {
			continue;
		}
		$idx_nom = persediaan_list_col_index_unit_nominal($field, $CI);
		if ($idx_nom >= 0 && isset($footer[$idx_nom])) {
			$sum_nom = is_array($totals_nominal_unit) && isset($totals_nominal_unit[$field])
				? (float) $totals_nominal_unit[$field]
				: 0;
			$footer[$idx_nom] = persediaan_export_blank_if_zero($sum_nom);
		}
	}
	if ($total_terjual !== null) {
		$idx_terjual = persediaan_list_col_index_terjual($CI);
		if (isset($footer[$idx_terjual])) {
			$footer[$idx_terjual] = persediaan_export_blank_if_zero($total_terjual);
		}
	}
	if ($total_pecah_satuan !== null) {
		$idx_pecah = persediaan_list_col_index_pecah_satuan($CI);
		if (isset($footer[$idx_pecah])) {
			$footer[$idx_pecah] = persediaan_export_blank_if_zero($total_pecah_satuan);
		}
	}
	if ($total_bahan_produksi !== null) {
		$idx_bahan = persediaan_list_col_index_bahan_produksi($CI);
		if (isset($footer[$idx_bahan])) {
			$footer[$idx_bahan] = persediaan_export_blank_if_zero($total_bahan_produksi);
		}
	}
	return $footer;
}

/**
 * Indeks kolom *_Nominal di datatable/export (-1 jika tidak ada).
 */
function persediaan_list_col_index_unit_nominal($field, $CI = null)
{
	if (!persediaan_field_has_nominal_column($field)) {
		return -1;
	}
	$idx = persediaan_list_prefix_column_count();
	foreach (persediaan_list_fields_tgl_keluar_sampai_total_10($CI) as $f) {
		if ($f === $field) {
			return $idx + 1;
		}
		$idx++;
		if (persediaan_field_has_nominal_column($f)) {
			$idx++;
		}
	}
	return -1;
}

/**
 * Footer datatable halaman Stock (tanpa kolom Sisa/Stock).
 */
function persediaan_stock_datatable_footer_cells($total_total_10, $total_nilai_persediaan, $totals_nominal_unit = null, $CI = null)
{
	return persediaan_datatable_footer_cells($total_total_10, $total_nilai_persediaan, $totals_nominal_unit, $CI);
}

function persediaan_stock_export_headers($CI = null)
{
	return persediaan_export_headers($CI);
}

function persediaan_stock_export_column_types($CI = null)
{
	return persediaan_export_column_types($CI);
}

function persediaan_stock_export_row_cells($row, $no, $bulan_filter = '', $CI = null)
{
	$penjualan = isset($row->penjualan) ? $row->penjualan : 0;
	$pecah_satuan = isset($row->pecah_satuan) ? $row->pecah_satuan : 0;
	$bahan_produksi = isset($row->bahan_produksi) ? $row->bahan_produksi : 0;
	$nilai_persediaan = persediaan_hitung_nilai_persediaan_stock_row($row);

	$cells = array(
		$no,
		persediaan_format_bulan_tahun($row, $bulan_filter),
		isset($row->kategori) ? $row->kategori : '',
		isset($row->namabarang) ? $row->namabarang : '',
		isset($row->satuan) ? $row->satuan : '',
		persediaan_export_blank_if_zero(isset($row->hpp) ? $row->hpp : ''),
		persediaan_export_blank_if_zero(isset($row->sa) ? $row->sa : ''),
		persediaan_export_blank_if_zero(isset($row->spop) ? $row->spop : ''),
		persediaan_export_blank_if_zero(isset($row->beli) ? $row->beli : ''),
		persediaan_export_blank_if_zero(isset($row->tuj) ? $row->tuj : ''),
	);

	foreach (persediaan_list_fields_tgl_keluar_sampai_total_10($CI) as $field) {
		if ($field === 'tgl_keluar') {
			$cells[] = persediaan_row_get($row, $field);
		} elseif ($field === 'total_10') {
			$cells[] = persediaan_export_blank_if_zero(persediaan_hitung_total_10_kalkulasi($row));
		} else {
			$cells[] = persediaan_export_blank_if_zero(persediaan_row_get($row, $field));
			if (persediaan_field_has_nominal_column($field)) {
				$cells[] = persediaan_export_blank_if_zero(persediaan_hitung_kolom_nominal_row($row, $field));
			}
		}
	}

	$cells[] = persediaan_export_blank_if_zero($nilai_persediaan);
	$cells[] = persediaan_export_blank_if_zero($penjualan);
	$cells[] = persediaan_export_blank_if_zero($pecah_satuan);
	$cells[] = persediaan_export_blank_if_zero($bahan_produksi);

	return $cells;
}

function persediaan_stock_export_footer_cells($total_total_10, $total_nilai_persediaan, $totals_nominal_unit = null, $CI = null)
{
	$footer = persediaan_datatable_footer_cells($total_total_10, $total_nilai_persediaan, $totals_nominal_unit, $CI);
	$idx_total_10 = persediaan_list_col_index_total_10($CI);
	$idx_nilai = persediaan_list_col_index_nilai_persediaan($CI);
	if (isset($footer[$idx_total_10])) {
		$footer[$idx_total_10] = persediaan_export_blank_if_zero($total_total_10);
	}
	if (isset($footer[$idx_nilai])) {
		$footer[$idx_nilai] = persediaan_export_blank_if_zero($total_nilai_persediaan);
	}
	foreach (persediaan_list_unit_columns($CI) as $field) {
		if (!persediaan_field_has_nominal_column($field)) {
			continue;
		}
		$idx_nom = persediaan_list_col_index_unit_nominal($field, $CI);
		if ($idx_nom >= 0 && isset($footer[$idx_nom])) {
			$sum_nom = is_array($totals_nominal_unit) && isset($totals_nominal_unit[$field])
				? (float) $totals_nominal_unit[$field]
				: 0;
			$footer[$idx_nom] = persediaan_export_blank_if_zero($sum_nom);
		}
	}
	return $footer;
}

/**
 * Jalankan callback rekap dengan db_debug=false agar error DB tidak jadi halaman HTML 500.
 */
function persediaan_rekap_run_silent_db($CI, $callback)
{
	$prev = $CI->db->db_debug;
	$CI->db->db_debug = false;
	try {
		return call_user_func($callback);
	} finally {
		$CI->db->db_debug = $prev;
	}
}

/**
 * Pesan error database terakhir (CodeIgniter).
 */
function persediaan_rekap_db_error_message($CI, $label = '')
{
	$err = $CI->db->error();
	$msg = isset($err['message']) ? trim((string) $err['message']) : '';
	if ($msg === '') {
		return $label !== '' ? $label : 'Kesalahan database';
	}
	return ($label !== '' ? $label . ': ' : '') . $msg;
}

/**
 * Kolom tabel persediaan_rekap_view (cache per request).
 */
function persediaan_rekap_view_list_fields($CI)
{
	static $cache = null;
	if ($cache !== null) {
		return $cache;
	}
	if (!$CI->db->table_exists('persediaan_rekap_view')) {
		$cache = array();
		return $cache;
	}
	$cache = $CI->db->list_fields('persediaan_rekap_view');
	return $cache;
}

/**
 * Nama kolom UUID di persediaan_rekap_view (bervariasi antar server).
 */
function persediaan_rekap_view_uuid_column($CI)
{
	static $col = null;
	if ($col !== null) {
		return $col === false ? null : $col;
	}
	$col = false;
	foreach (array('uuid_persediaan_rekap_view', 'uuid_rekap_view', 'uuid_rekap', 'uuid') as $candidate) {
		if (in_array($candidate, persediaan_rekap_view_list_fields($CI), true)) {
			$col = $candidate;
			break;
		}
	}
	return $col === false ? null : $col;
}

function persediaan_rekap_view_uses_auto_increment_id($CI)
{
	static $auto = null;
	if ($auto !== null) {
		return $auto;
	}
	$auto = false;
	if (!in_array('id', persediaan_rekap_view_list_fields($CI), true)) {
		return $auto;
	}
	$row = $CI->db->query("SHOW COLUMNS FROM `persediaan_rekap_view` LIKE 'id'")->row();
	if ($row && stripos((string) $row->Extra, 'auto_increment') !== false) {
		$auto = true;
	}
	return $auto;
}

/**
 * Query DB rekap; lempar Exception jika gagal (untuk respons JSON, bukan HTML error).
 */
function persediaan_rekap_db_query($CI, $sql, $bind = null)
{
	$q = ($bind === null) ? $CI->db->query($sql) : $CI->db->query($sql, $bind);
	if ($q === false) {
		throw new Exception(persediaan_rekap_db_error_message($CI, 'Query rekap gagal'));
	}
	return $q;
}

/**
 * Filter baris persediaan tab Data: Barang (bukan jasa) atau Jasa saja.
 */
function persediaan_filter_rows_by_kategori_tab($rows, $want_jasa = false)
{
	if (!is_array($rows) || count($rows) === 0) {
		return array();
	}

	$CI =& get_instance();
	$CI->load->helper('pembelian_persediaan');

	$filtered = array();
	foreach ($rows as $row) {
		$is_jasa = persediaan_row_is_kategori_jasa($row);
		if ($want_jasa ? $is_jasa : !$is_jasa) {
			$filtered[] = $row;
		}
	}

	return $filtered;
}

/**
 * Sel baris datatable tab Data Persediaan (nilai tampilan sama dengan view).
 */
function persediaan_tab_data_display_cells($row, $no, $bulan_filter = '', $CI = null, $show_keluar_columns = true)
{
	$cells = array(
		$no,
		persediaan_format_bulan_tahun($row, $bulan_filter),
		isset($row->namabarang) ? $row->namabarang : '',
		isset($row->satuan) ? $row->satuan : '',
		persediaan_tampil_hpp_row($row),
		persediaan_export_blank_if_zero(isset($row->sa) ? $row->sa : ''),
		persediaan_tampil_sa_nominal_row($row),
		persediaan_export_blank_if_zero(isset($row->spop) ? $row->spop : ''),
		persediaan_export_blank_if_zero(isset($row->beli) ? $row->beli : ''),
		persediaan_tampil_beli_nominal_row($row),
		persediaan_export_blank_if_zero(isset($row->tuj) ? $row->tuj : ''),
	);

	foreach (persediaan_list_fields_tgl_keluar_sampai_total_10($CI) as $field) {
		if ($field === 'total_10') {
			$cells[] = persediaan_tampil_total_10_net_row($row);
		} else {
			$cells[] = persediaan_row_get($row, $field);
			if (persediaan_field_has_nominal_column($field)) {
				$cells[] = persediaan_tampil_kolom_nominal_row($row, $field);
			}
		}
	}

	$cells[] = persediaan_tampil_nilai_persediaan_row($row);
	if ($show_keluar_columns) {
		$cells[] = isset($row->penjualan) ? $row->penjualan : 0;
		$cells[] = isset($row->pecah_satuan) ? $row->pecah_satuan : 0;
		$cells[] = isset($row->bahan_produksi) ? $row->bahan_produksi : 0;
	}

	return $cells;
}

function persediaan_export_write_styled_cell($row, $col, $value, $col_types, $style_text = 3, $style_left = 7, $style_num = 8, $style_total = 5)
{
	$col_type = isset($col_types[$col]) ? $col_types[$col] : 'text';

	if ($col_type === 'number') {
		if (is_string($value) && strcasecmp(trim($value), 'Total') === 0) {
			xlsWriteCellStyle($row, $col, 'Total', $style_total);
			return;
		}

		$trimmed = trim((string) $value);
		if ($trimmed === '' || $trimmed === '0' || $trimmed === '0,00' || $trimmed === '0.00') {
			xlsWriteCellStyle($row, $col, '', $style_text);
			return;
		}

		xlsWriteCellStyle($row, $col, $value, $style_num);
		return;
	}

	xlsWriteCellStyle($row, $col, $value === null ? '' : $value, $style_left);
}

/**
 * Export Excel tab Data Persediaan (Barang / Jasa) — tampilan selaras datatable + style tabel.
 */
function persediaan_export_excel_tab_data_output($CI, $bulan, $rows, $filter_kategori = 'barang', $prepare_download = true, $title_override = '')
{
	$CI->load->helper('exportexcel');

	$filter_kategori = strtolower(trim((string) $filter_kategori));
	if ($filter_kategori === 'jasa') {
		$judul_jenis = 'Jasa';
	} elseif ($filter_kategori === 'barang') {
		$judul_jenis = 'Barang';
	} else {
		$judul_jenis = 'Semua';
	}

	$bulan = trim((string) $bulan);
	$bagian_bulan = ($bulan !== '') ? $bulan : 'semua';
	$bulan_label = ($bulan !== '' && preg_match('/^\d{4}-\d{2}$/', $bulan))
		? date('m/Y', strtotime($bulan . '-01'))
		: $bagian_bulan;

	$show_keluar_columns = persediaan_tab_data_show_keluar_columns($filter_kategori);

	$styleHeader = 4;
	$styleBorder = 3;
	$styleRight = 8;
	$styleLeft = 7;
	$styleFooter = 5;

	$headers = persediaan_tab_data_export_headers($CI, $show_keluar_columns, $filter_kategori);
	$col_types = persediaan_tab_data_export_column_types($CI, $show_keluar_columns, $filter_kategori);
	$col_count = count($headers);

	$widths = array(5, 10, 28, 8, 10, 8, 12, 8, 8, 12, 8);
	while (count($widths) < $col_count) {
		$widths[] = 11;
	}

	$total_total_10 = 0;
	$total_nilai_persediaan = 0;
	$total_sa = 0;
	$total_sa_nominal = 0;
	$total_beli = 0;
	$total_beli_nominal = 0;
	$total_terjual = 0;
	$total_pecah_satuan = 0;
	$total_bahan_produksi = 0;
	$totals_nominal_unit = array();
	foreach (persediaan_list_unit_columns($CI) as $uf) {
		$totals_nominal_unit[$uf] = 0;
	}

	if ($prepare_download) {
		excel_prepare_download(
			'Persediaan_' . preg_replace('/[^A-Za-z0-9_]+/', '_', $judul_jenis) . '_' . $bagian_bulan . '_' . date('Y-m-d_H-i-s') . '.xlsx'
		);
	}

	xlsBOF();
	xlsSetColumnWidths($widths);
	$title_line = ($title_override !== '')
		? (string) $title_override
		: ('DATA PERSEDIAAN — ' . strtoupper($judul_jenis) . ' — Bulan ' . $bulan_label);
	xlsWriteLabelBold14(0, 0, $title_line);
	xlsWriteLabel(1, 0, 'Dicetak: ' . date('d/m/Y H:i:s') . ' | Total baris: ' . count($rows));

	$header_row = 3;
	foreach ($headers as $i => $label) {
		xlsWriteCellStyle($header_row, $i, $label, $styleHeader);
	}

	$row_num = 4;
	$no = 0;
	foreach ($rows as $data) {
		$no++;
		$total_total_10 += persediaan_hitung_total_10_net($data);
		$total_nilai_persediaan += persediaan_hitung_nilai_persediaan_row($data);
		$total_sa += persediaan_parse_angka(isset($data->sa) ? $data->sa : persediaan_row_get($data, 'sa'));
		$total_sa_nominal += persediaan_hitung_sa_nominal_row($data);
		$total_beli += persediaan_parse_angka(isset($data->beli) ? $data->beli : persediaan_row_get($data, 'beli'));
		$total_beli_nominal += persediaan_hitung_beli_nominal_row($data);
		foreach (persediaan_list_unit_columns($CI) as $uf) {
			$totals_nominal_unit[$uf] += persediaan_hitung_kolom_nominal_row($data, $uf);
		}
		if ($show_keluar_columns) {
			$total_terjual += persediaan_parse_angka(isset($data->penjualan) ? $data->penjualan : 0);
			$total_pecah_satuan += persediaan_parse_angka(isset($data->pecah_satuan) ? $data->pecah_satuan : 0);
			$total_bahan_produksi += persediaan_parse_angka(isset($data->bahan_produksi) ? $data->bahan_produksi : 0);
		}

		$cells = persediaan_tab_data_display_cells($data, $no, $bulan, $CI, $show_keluar_columns);
		$col = 0;
		foreach ($cells as $cell) {
			if ($col === 0) {
				xlsWriteCellStyle($row_num, $col, $cell, $styleBorder);
			} else {
				persediaan_export_write_styled_cell($row_num, $col, $cell, $col_types, $styleBorder, $styleLeft, $styleRight, $styleFooter);
			}
			$col++;
		}
		$row_num++;
	}

	$footer_cells = persediaan_datatable_footer_cells($total_total_10, $total_nilai_persediaan, $totals_nominal_unit, $CI, $show_keluar_columns, $total_sa, $total_beli, $total_sa_nominal, $total_beli_nominal, $total_terjual, $total_pecah_satuan, $total_bahan_produksi);
	$idx_total_10 = persediaan_list_col_index_total_10($CI);
	$idx_nilai = persediaan_list_col_index_nilai_persediaan($CI);
	$idx_sa = persediaan_list_col_index_sa();
	$idx_sa_nominal = persediaan_list_col_index_sa_nominal();
	$idx_beli = persediaan_list_col_index_beli();
	$idx_beli_nominal = persediaan_list_col_index_beli_nominal();
	$money_cols = persediaan_tab_data_money_column_indexes($CI);
	if (isset($footer_cells[$idx_total_10])) {
		$footer_cells[$idx_total_10] = persediaan_format_angka_tampil($total_total_10);
	}
	if (isset($footer_cells[$idx_nilai])) {
		$footer_cells[$idx_nilai] = persediaan_format_rupiah_tampil($total_nilai_persediaan, true);
	}
	if (isset($footer_cells[$idx_sa])) {
		$footer_cells[$idx_sa] = persediaan_format_angka_tampil($total_sa);
	}
	if (isset($footer_cells[$idx_sa_nominal])) {
		$footer_cells[$idx_sa_nominal] = persediaan_format_rupiah_tampil($total_sa_nominal, true);
	}
	if (isset($footer_cells[$idx_beli])) {
		$footer_cells[$idx_beli] = persediaan_format_angka_tampil($total_beli);
	}
	if (isset($footer_cells[$idx_beli_nominal])) {
		$footer_cells[$idx_beli_nominal] = persediaan_format_rupiah_tampil($total_beli_nominal, true);
	}
	foreach (persediaan_list_unit_columns($CI) as $field) {
		if (!persediaan_field_has_nominal_column($field)) {
			continue;
		}
		$idx_nom = persediaan_list_col_index_unit_nominal($field, $CI);
		if ($idx_nom >= 0 && isset($footer_cells[$idx_nom])) {
			$sum_nom = isset($totals_nominal_unit[$field]) ? (float) $totals_nominal_unit[$field] : 0;
			$footer_cells[$idx_nom] = persediaan_format_rupiah_tampil($sum_nom, true);
		}
	}
	if ($show_keluar_columns) {
		$idx_terjual = persediaan_list_col_index_terjual($CI);
		$idx_pecah = persediaan_list_col_index_pecah_satuan($CI);
		$idx_bahan = persediaan_list_col_index_bahan_produksi($CI);
		if (isset($footer_cells[$idx_terjual])) {
			$footer_cells[$idx_terjual] = persediaan_format_angka_tampil($total_terjual);
		}
		if (isset($footer_cells[$idx_pecah])) {
			$footer_cells[$idx_pecah] = persediaan_format_angka_tampil($total_pecah_satuan);
		}
		if (isset($footer_cells[$idx_bahan])) {
			$footer_cells[$idx_bahan] = persediaan_format_angka_tampil($total_bahan_produksi);
		}
	}

	$col = 0;
	foreach ($footer_cells as $cell) {
		if ((string) $cell === 'Total') {
			xlsWriteCellStyle($row_num, $col, 'Total', $styleFooter);
		} elseif (in_array($col, $money_cols, true)) {
			xlsWriteCellStyle($row_num, $col, $cell, $styleRight);
		} elseif ($col === $idx_total_10 || $col === $idx_sa || $col === $idx_beli) {
			xlsWriteCellStyle($row_num, $col, $cell, $styleRight);
		} else {
			$is_nominal = false;
			foreach (persediaan_list_unit_columns($CI) as $field) {
				if (persediaan_field_has_nominal_column($field)
					&& persediaan_list_col_index_unit_nominal($field, $CI) === $col) {
					$is_nominal = true;
					break;
				}
			}
			if ($is_nominal && (string) $cell !== '') {
				xlsWriteCellStyle($row_num, $col, $cell, $styleRight);
			} else {
				xlsWriteCellStyle($row_num, $col, $cell, $styleBorder);
			}
		}
		$col++;
	}

	xlsEOF();
}

/**
 * -------------------------------------------------------------------------
 * Generate Persediaan — tampilan proses (bulan sumber vs target + rekap)
 * -------------------------------------------------------------------------
 */
function persediaan_gen_proses_floats_equal($a, $b)
{
	return abs((float) $a - (float) $b) < 0.0001;
}

/**
 * Salin nilai kolom hpp ke bulan target tanpa memotong desimal (jangan floor ke integer).
 */
function persediaan_salin_field_hpp_dari_sumber($hpp_raw)
{
	$hpp_raw = trim((string) $hpp_raw);
	if ($hpp_raw === '' || $hpp_raw === '-') {
		return '0';
	}

	return $hpp_raw;
}

/**
 * Qty salin generate = floor(total_10) bulan sumber (selaras copy V2).
 */
function persediaan_gen_proses_qty_copy_dari_row($row, $field = 'total_10')
{
	return (int) floor(persediaan_parse_angka(persediaan_row_get($row, $field)));
}

/**
 * Nominal verifikasi copy: qty salin × hpp (hpp utuh, tanpa floor).
 */
function persediaan_gen_proses_hitung_nominal_copy_row($row, $qty_field = 'total_10')
{
	$qty = persediaan_gen_proses_qty_copy_dari_row($row, $qty_field);
	if ($qty <= 0) {
		return 0.0;
	}
	$hpp = persediaan_parse_angka(isset($row->hpp) ? $row->hpp : persediaan_row_get($row, 'hpp'));

	return (float) $qty * (float) $hpp;
}

function persediaan_gen_proses_sum_nominal_copy_rows($rows, $qty_field = 'total_10')
{
	$sum = 0.0;
	if (!is_array($rows)) {
		return $sum;
	}
	foreach ($rows as $row) {
		$sum += persediaan_gen_proses_hitung_nominal_copy_row($row, $qty_field);
	}

	return $sum;
}

function persediaan_gen_proses_nominal_equal($a, $b)
{
	return abs((float) $a - (float) $b) < 1.0;
}

/**
 * Record persediaan bulan target hasil copy dari bulan sumber (bukan insert pembelian).
 */
function persediaan_gen_proses_row_is_hasil_copy($row)
{
	if (empty($row)) {
		return false;
	}

	$lama = trim((string) persediaan_row_get($row, 'uuid_persediaan_lama'));
	if ($lama !== '' && strpos($lama, 'gen_pembelian:') === 0) {
		return false;
	}
	if ($lama !== '' && strpos($lama, 'gen_src:') === 0) {
		return true;
	}

	$beli = persediaan_parse_angka(persediaan_row_get($row, 'beli'));

	return $beli <= 0.0001;
}

/**
 * Record persediaan dari proses pembelian V2 (penanda gen_pembelian:* atau beli > 0 tanpa gen_src).
 */
function persediaan_gen_proses_row_is_hasil_pembelian($row)
{
	if (empty($row)) {
		return false;
	}

	$lama = trim((string) persediaan_row_get($row, 'uuid_persediaan_lama'));
	if ($lama !== '' && strpos($lama, 'gen_pembelian:') === 0) {
		return true;
	}
	if ($lama !== '' && strpos($lama, 'gen_src:') === 0) {
		return false;
	}

	return persediaan_parse_angka(persediaan_row_get($row, 'beli')) > 0.0001;
}

function persediaan_gen_proses_filter_rows_hasil_copy($rows)
{
	if (!is_array($rows)) {
		return array();
	}

	$out = array();
	foreach ($rows as $row) {
		if (persediaan_gen_proses_row_is_hasil_copy($row)) {
			$out[] = $row;
		}
	}

	return $out;
}

function persediaan_gen_proses_filter_rows_hasil_pembelian($rows)
{
	if (!is_array($rows)) {
		return array();
	}

	$out = array();
	foreach ($rows as $row) {
		if (persediaan_gen_proses_row_is_hasil_pembelian($row)) {
			$out[] = $row;
		}
	}

	return $out;
}

function persediaan_gen_proses_sum_field_rows($rows, $field)
{
	$sum = 0.0;
	if (!is_array($rows)) {
		return $sum;
	}
	foreach ($rows as $row) {
		$sum += persediaan_parse_angka(persediaan_row_get($row, $field));
	}

	return $sum;
}

function persediaan_gen_proses_sum_total_10_nominal_rows($rows)
{
	$sum = 0.0;
	if (!is_array($rows)) {
		return $sum;
	}
	foreach ($rows as $row) {
		$t10 = persediaan_parse_angka(persediaan_row_get($row, 'total_10'));
		$hpp = persediaan_parse_angka(isset($row->hpp) ? $row->hpp : persediaan_row_get($row, 'hpp'));
		$sum += $t10 * $hpp;
	}

	return $sum;
}

function persediaan_gen_proses_sum_sa_nominal_rows($rows)
{
	$sum = 0.0;
	if (!is_array($rows)) {
		return $sum;
	}
	foreach ($rows as $row) {
		$sum += persediaan_hitung_sa_nominal_row($row);
	}

	return $sum;
}

function persediaan_gen_proses_load_rows_bulan($CI, $bulan)
{
	if (!$CI || !$CI->db->table_exists('persediaan')) {
		return array();
	}

	$bulan = trim((string) $bulan);
	if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
		return array();
	}

	$ts = strtotime($bulan . '-01');
	if ($ts === false) {
		return array();
	}

	$tanggal_beli = date('Y-m-01', $ts);
	$rows = $CI->db->query(
		"SELECT * FROM `persediaan` WHERE `tanggal_beli` = ? ORDER BY `namabarang` ASC, `id` ASC",
		array($tanggal_beli)
	)->result();

	$CI->load->helper('pembelian_persediaan');
	$rows = persediaan_export_sort_rows_by_namabarang($rows, 'namabarang');

	return persediaan_filter_rows_tab_data($rows);
}

function persediaan_gen_v2_copy_rekap_session_key($bulan)
{
	return 'gen_v2_copy_rekap_' . trim((string) $bulan);
}

/**
 * Simpan snapshot rekap verifikasi copy (saat copy selesai, sebelum pembelian/produksi/penjualan).
 */
function persediaan_gen_v2_save_copy_rekap($CI, $bulan, $rekap, $verified_at = null)
{
	if (!$CI || !is_array($rekap)) {
		return false;
	}

	$bulan = trim((string) $bulan);
	if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
		return false;
	}

	$CI->session->set_userdata(persediaan_gen_v2_copy_rekap_session_key($bulan), array(
		'rekap' => $rekap,
		'verified_at' => $verified_at ? (string) $verified_at : date('Y-m-d H:i:s'),
		'bulan' => $bulan,
	));

	return true;
}

/**
 * Muat snapshot rekap copy dari session (atau batch state aktif).
 */
function persediaan_gen_v2_load_copy_rekap_meta($CI, $bulan)
{
	$bulan = trim((string) $bulan);
	$empty = array(
		'rekap' => null,
		'verified_at' => '',
		'from_snapshot' => false,
	);

	if (!$CI || !preg_match('/^\d{4}-\d{2}$/', $bulan)) {
		return $empty;
	}

	$data = $CI->session->userdata(persediaan_gen_v2_copy_rekap_session_key($bulan));
	if (is_array($data) && !empty($data['rekap']) && is_array($data['rekap'])) {
		return array(
			'rekap' => $data['rekap'],
			'verified_at' => isset($data['verified_at']) ? (string) $data['verified_at'] : '',
			'from_snapshot' => true,
		);
	}

	$state = $CI->session->userdata('gen_recalc_state_' . $bulan);
	if (is_array($state) && !empty($state['gen_proses_copy_rekap']) && is_array($state['gen_proses_copy_rekap'])) {
		return array(
			'rekap' => $state['gen_proses_copy_rekap'],
			'verified_at' => isset($state['gen_proses_copy_verified_at']) ? (string) $state['gen_proses_copy_verified_at'] : '',
			'from_snapshot' => true,
		);
	}

	return $empty;
}

function persediaan_gen_proses_build_rekap($rows_sumber, $rows_target)
{
	$sum_total10_sumber = persediaan_gen_proses_sum_field_rows($rows_sumber, 'total_10');
	$sum_sa_target = persediaan_gen_proses_sum_field_rows($rows_target, 'sa');
	$sum_total10_target = persediaan_gen_proses_sum_field_rows($rows_target, 'total_10');

	$qty_ok = persediaan_gen_proses_floats_equal($sum_total10_sumber, $sum_sa_target)
		&& persediaan_gen_proses_floats_equal($sum_sa_target, $sum_total10_target);

	// Bulan sumber: nominal total_10 pakai qty salin (floor) × hpp utuh — selaras prosedur copy V2.
	$nom_total10_sumber = persediaan_gen_proses_sum_nominal_copy_rows($rows_sumber, 'total_10');
	$nom_sa_target = persediaan_gen_proses_sum_nominal_copy_rows($rows_target, 'sa');
	$nom_total10_target = persediaan_gen_proses_sum_nominal_copy_rows($rows_target, 'total_10');

	$nominal_ok = persediaan_gen_proses_nominal_equal($nom_total10_sumber, $nom_sa_target)
		&& persediaan_gen_proses_nominal_equal($nom_sa_target, $nom_total10_target);

	return array(
		'qty_ok' => $qty_ok ? 1 : 0,
		'nominal_ok' => $nominal_ok ? 1 : 0,
		'sum_total10_sumber' => $sum_total10_sumber,
		'sum_sa_target' => $sum_sa_target,
		'sum_total10_target' => $sum_total10_target,
		'nom_total10_sumber' => $nom_total10_sumber,
		'nom_sa_target' => $nom_sa_target,
		'nom_total10_target' => $nom_total10_target,
		'sum_total10_sumber_fmt' => persediaan_format_angka_tampil($sum_total10_sumber),
		'sum_sa_target_fmt' => persediaan_format_angka_tampil($sum_sa_target),
		'sum_total10_target_fmt' => persediaan_format_angka_tampil($sum_total10_target),
		'nom_total10_sumber_fmt' => persediaan_format_rupiah_tampil($nom_total10_sumber, true),
		'nom_sa_target_fmt' => persediaan_format_rupiah_tampil($nom_sa_target, true),
		'nom_total10_target_fmt' => persediaan_format_rupiah_tampil($nom_total10_target, true),
		'count_sumber' => is_array($rows_sumber) ? count($rows_sumber) : 0,
		'count_target' => is_array($rows_target) ? count($rows_target) : 0,
	);
}

function persediaan_generate_proses_package($CI, $bulan_target)
{
	$bulan_target = trim((string) $bulan_target);
	if (!preg_match('/^\d{4}-\d{2}$/', $bulan_target)) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');
	}

	$ts = strtotime($bulan_target . '-01');
	if ($ts === false) {
		return array('ok' => false, 'message' => 'Bulan target tidak valid.');
	}

	$bulan_sumber = date('Y-m', strtotime('-1 month', $ts));
	$rows_sumber = persediaan_gen_proses_load_rows_bulan($CI, $bulan_sumber);
	$rows_target_all = persediaan_gen_proses_load_rows_bulan($CI, $bulan_target);
	$rows_target = persediaan_gen_proses_filter_rows_hasil_copy($rows_target_all);

	$copy_meta = persediaan_gen_v2_load_copy_rekap_meta($CI, $bulan_target);
	if (!empty($copy_meta['rekap']) && is_array($copy_meta['rekap'])) {
		$rekap = $copy_meta['rekap'];
	} else {
		$rekap = persediaan_gen_proses_build_rekap($rows_sumber, $rows_target);
	}

	return array(
		'ok' => true,
		'bulan_target' => $bulan_target,
		'bulan_sumber' => $bulan_sumber,
		'bulan_target_label' => date('m/Y', $ts),
		'bulan_sumber_label' => date('m/Y', strtotime($bulan_sumber . '-01')),
		'verifikasi_mode' => 'copy_persediaan',
		'rows_sumber_barang' => persediaan_filter_rows_by_kategori_tab($rows_sumber, false),
		'rows_sumber_jasa' => persediaan_filter_rows_by_kategori_tab($rows_sumber, true),
		'rows_target_barang' => persediaan_filter_rows_by_kategori_tab($rows_target, false),
		'rows_target_jasa' => persediaan_filter_rows_by_kategori_tab($rows_target, true),
		'count_target_copy' => count($rows_target),
		'count_target_all' => is_array($rows_target_all) ? count($rows_target_all) : 0,
		'rekap' => $rekap,
		'copy_verified_at' => isset($copy_meta['verified_at']) ? $copy_meta['verified_at'] : '',
		'rekap_from_snapshot' => !empty($copy_meta['from_snapshot']),
	);
}

/**
 * Rekap verifikasi persediaan setelah semua proses (copy + pembelian + produksi + penjualan).
 */
function persediaan_gen_proses_build_rekap_full($rows_sumber, $rows_target_all, $CI, $bulan_target)
{
	$CI->load->helper('pembelian_persediaan');

	$sum_total10_sumber = persediaan_gen_proses_sum_field_rows($rows_sumber, 'total_10');
	$sum_sa_target = persediaan_gen_proses_sum_field_rows($rows_target_all, 'sa');
	$sum_total10_target = persediaan_gen_proses_sum_field_rows($rows_target_all, 'total_10');
	$sum_beli = persediaan_gen_proses_sum_field_rows($rows_target_all, 'beli');
	$sum_penjualan = persediaan_gen_proses_sum_field_rows($rows_target_all, 'penjualan');
	$sum_pecah = persediaan_gen_proses_sum_field_rows($rows_target_all, 'pecah_satuan');
	$sum_bahan = persediaan_gen_proses_sum_field_rows($rows_target_all, 'bahan_produksi');

	$sa_ok = persediaan_gen_proses_floats_equal($sum_total10_sumber, $sum_sa_target);
	$expected_total10 = $sum_sa_target + $sum_beli - $sum_penjualan - $sum_pecah - $sum_bahan;
	$saldo_ok = persediaan_gen_proses_floats_equal($expected_total10, $sum_total10_target);
	$qty_ok = $sa_ok && $saldo_ok;

	$nom_total10_sumber = persediaan_gen_proses_sum_total_10_nominal_rows($rows_sumber);
	$nom_sa_target = persediaan_gen_proses_sum_sa_nominal_rows($rows_target_all);
	$nom_total10_target = persediaan_gen_proses_sum_total_10_nominal_rows($rows_target_all);

	$sum_beli_nom = 0.0;
	$sum_keluar_nom = 0.0;
	if (is_array($rows_target_all)) {
		foreach ($rows_target_all as $row) {
			$hpp = persediaan_parse_angka(isset($row->hpp) ? $row->hpp : persediaan_row_get($row, 'hpp'));
			$sum_beli_nom += persediaan_parse_angka(persediaan_row_get($row, 'beli')) * $hpp;
			$sum_keluar_nom += (
				persediaan_parse_angka(persediaan_row_get($row, 'penjualan'))
				+ persediaan_parse_angka(persediaan_row_get($row, 'pecah_satuan'))
				+ persediaan_parse_angka(persediaan_row_get($row, 'bahan_produksi'))
			) * $hpp;
		}
	}

	$sa_nominal_ok = persediaan_gen_proses_nominal_equal($nom_total10_sumber, $nom_sa_target);
	$expected_nom_total10 = $nom_sa_target + $sum_beli_nom - $sum_keluar_nom;
	$saldo_nominal_ok = persediaan_gen_proses_nominal_equal($expected_nom_total10, $nom_total10_target);
	$nominal_ok = $sa_nominal_ok && $saldo_nominal_ok;

	$rekap_pembelian = persediaan_gen_proses_pembelian_build_rekap($CI, $bulan_target);
	$rekap_produksi = persediaan_gen_proses_produksi_build_rekap($CI, $bulan_target);
	$rekap_penjualan = persediaan_gen_proses_penjualan_build_rekap($CI, $bulan_target);

	$pembelian_ok = !empty($rekap_pembelian['barang_ok']) && !empty($rekap_pembelian['jasa_ok']);
	$produksi_ok = !empty($rekap_produksi['produksi_ok']);
	$penjualan_ok = !empty($rekap_penjualan['penjualan_ok']);
	$all_ok = $qty_ok && $nominal_ok && $pembelian_ok && $produksi_ok && $penjualan_ok;

	return array(
		'verifikasi_mode' => 'full_persediaan',
		'all_ok' => $all_ok ? 1 : 0,
		'qty_ok' => $qty_ok ? 1 : 0,
		'nominal_ok' => $nominal_ok ? 1 : 0,
		'sa_ok' => $sa_ok ? 1 : 0,
		'saldo_ok' => $saldo_ok ? 1 : 0,
		'sa_nominal_ok' => $sa_nominal_ok ? 1 : 0,
		'saldo_nominal_ok' => $saldo_nominal_ok ? 1 : 0,
		'pembelian_ok' => $pembelian_ok ? 1 : 0,
		'produksi_ok' => $produksi_ok ? 1 : 0,
		'penjualan_ok' => $penjualan_ok ? 1 : 0,
		'sum_total10_sumber' => $sum_total10_sumber,
		'sum_sa_target' => $sum_sa_target,
		'sum_total10_target' => $sum_total10_target,
		'sum_beli' => $sum_beli,
		'sum_penjualan' => $sum_penjualan,
		'sum_pecah_satuan' => $sum_pecah,
		'sum_bahan_produksi' => $sum_bahan,
		'expected_total10' => $expected_total10,
		'nom_total10_sumber' => $nom_total10_sumber,
		'nom_sa_target' => $nom_sa_target,
		'nom_total10_target' => $nom_total10_target,
		'expected_nom_total10' => $expected_nom_total10,
		'sum_total10_sumber_fmt' => persediaan_format_angka_tampil($sum_total10_sumber),
		'sum_sa_target_fmt' => persediaan_format_angka_tampil($sum_sa_target),
		'sum_total10_target_fmt' => persediaan_format_angka_tampil($sum_total10_target),
		'sum_beli_fmt' => persediaan_format_angka_tampil($sum_beli),
		'sum_penjualan_fmt' => persediaan_format_angka_tampil($sum_penjualan),
		'sum_pecah_satuan_fmt' => persediaan_format_angka_tampil($sum_pecah),
		'sum_bahan_produksi_fmt' => persediaan_format_angka_tampil($sum_bahan),
		'expected_total10_fmt' => persediaan_format_angka_tampil($expected_total10),
		'nom_total10_sumber_fmt' => persediaan_format_rupiah_tampil($nom_total10_sumber, true),
		'nom_sa_target_fmt' => persediaan_format_rupiah_tampil($nom_sa_target, true),
		'nom_total10_target_fmt' => persediaan_format_rupiah_tampil($nom_total10_target, true),
		'expected_nom_total10_fmt' => persediaan_format_rupiah_tampil($expected_nom_total10, true),
		'count_sumber' => is_array($rows_sumber) ? count($rows_sumber) : 0,
		'count_target' => is_array($rows_target_all) ? count($rows_target_all) : 0,
		'rekap_pembelian' => $rekap_pembelian,
		'rekap_produksi' => $rekap_produksi,
		'rekap_penjualan' => $rekap_penjualan,
	);
}

function persediaan_generate_proses_persediaan_full_package($CI, $bulan_target)
{
	$bulan_target = trim((string) $bulan_target);
	if (!preg_match('/^\d{4}-\d{2}$/', $bulan_target)) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');
	}

	$ts = strtotime($bulan_target . '-01');
	if ($ts === false) {
		return array('ok' => false, 'message' => 'Bulan target tidak valid.');
	}

	$bulan_sumber = date('Y-m', strtotime('-1 month', $ts));
	$rows_sumber = persediaan_gen_proses_load_rows_bulan($CI, $bulan_sumber);
	$rows_target_all = persediaan_gen_proses_load_rows_bulan($CI, $bulan_target);

	return array(
		'ok' => true,
		'bulan_target' => $bulan_target,
		'bulan_sumber' => $bulan_sumber,
		'bulan_target_label' => date('m/Y', $ts),
		'bulan_sumber_label' => date('m/Y', strtotime($bulan_sumber . '-01')),
		'verifikasi_mode' => 'full_persediaan',
		'rows_target_barang' => persediaan_filter_rows_by_kategori_tab($rows_target_all, false),
		'rows_target_jasa' => persediaan_filter_rows_by_kategori_tab($rows_target_all, true),
		'count_target_all' => is_array($rows_target_all) ? count($rows_target_all) : 0,
		'rekap' => persediaan_gen_proses_build_rekap_full($rows_sumber, $rows_target_all, $CI, $bulan_target),
	);
}

/**
 * -------------------------------------------------------------------------
 * Generate Persediaan — box proses pembelian (verifikasi + datatable)
 * -------------------------------------------------------------------------
 */
function persediaan_gen_proses_pembelian_format_tgl($value)
{
	$value = trim((string) $value);
	if ($value === '' || $value === '0000-00-00') {
		return '';
	}
	$ts = strtotime($value);
	if ($ts === false) {
		return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
	}

	return date('d M Y', $ts);
}

function persediaan_gen_proses_pembelian_format_nominal($value)
{
	if (!function_exists('persediaan_format_rupiah_tampil')) {
		return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
	}

	return persediaan_format_rupiah_tampil($value, true);
}

function persediaan_gen_proses_pembelian_format_jumlah($value)
{
	if (!function_exists('persediaan_format_angka_tampil')) {
		return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
	}

	return persediaan_format_angka_tampil(persediaan_parse_angka($value));
}

function persediaan_gen_proses_pembelian_build_rekap($CI, $bulan_target)
{
	$CI->load->helper('pembelian_persediaan');
	$bulan_target = trim((string) $bulan_target);
	$ts = strtotime($bulan_target . '-01');
	$tgl_awal = date('Y-m-01', $ts);
	$tgl_akhir = date('Y-m-t', $ts);

	$count_barang = persediaan_gen_v2_count_pembelian_bulan($CI, 'tbl_pembelian', $tgl_awal, $tgl_akhir);
	$count_jasa = persediaan_gen_v2_count_pembelian_bulan($CI, 'tbl_pembelian_jasa', $tgl_awal, $tgl_akhir);
	$sum_jumlah_barang = persediaan_gen_v2_sum_jumlah_pembelian_bulan($CI, 'tbl_pembelian', $tgl_awal, $tgl_akhir);
	$sum_jumlah_jasa = persediaan_gen_v2_sum_jumlah_pembelian_bulan($CI, 'tbl_pembelian_jasa', $tgl_awal, $tgl_akhir);
	$sum_beli_barang = persediaan_gen_v2_sum_beli_persediaan_kategori($CI, $tgl_awal, $tgl_akhir, 'barang');
	$sum_beli_jasa = persediaan_gen_v2_sum_beli_persediaan_kategori($CI, $tgl_awal, $tgl_akhir, 'jasa');

	$barang_ok = persediaan_gen_proses_floats_equal($sum_jumlah_barang, $sum_beli_barang);
	$jasa_ok = persediaan_gen_proses_floats_equal($sum_jumlah_jasa, $sum_beli_jasa);

	return array(
		'barang_ok' => $barang_ok ? 1 : 0,
		'jasa_ok' => $jasa_ok ? 1 : 0,
		'count_barang' => $count_barang,
		'count_jasa' => $count_jasa,
		'sum_jumlah_barang' => $sum_jumlah_barang,
		'sum_jumlah_jasa' => $sum_jumlah_jasa,
		'sum_beli_barang' => $sum_beli_barang,
		'sum_beli_jasa' => $sum_beli_jasa,
		'sum_jumlah_barang_fmt' => persediaan_format_angka_tampil($sum_jumlah_barang),
		'sum_jumlah_jasa_fmt' => persediaan_format_angka_tampil($sum_jumlah_jasa),
		'sum_beli_barang_fmt' => persediaan_format_angka_tampil($sum_beli_barang),
		'sum_beli_jasa_fmt' => persediaan_format_angka_tampil($sum_beli_jasa),
	);
}

function persediaan_generate_proses_pembelian_package($CI, $bulan_target)
{
	$bulan_target = trim((string) $bulan_target);
	if (!preg_match('/^\d{4}-\d{2}$/', $bulan_target)) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');
	}

	$ts = strtotime($bulan_target . '-01');
	if ($ts === false) {
		return array('ok' => false, 'message' => 'Bulan target tidak valid.');
	}

	$tgl_awal = date('Y-m-01', $ts);
	$tgl_akhir = date('Y-m-t', $ts);
	$CI->load->helper('pembelian_persediaan');

	return array(
		'ok' => true,
		'bulan_target' => $bulan_target,
		'bulan_target_label' => date('m/Y', $ts),
		'tgl_awal' => $tgl_awal,
		'tgl_akhir' => $tgl_akhir,
		'rows_pembelian_barang' => persediaan_gen_v2_load_pembelian_bulan_rows($CI, 'tbl_pembelian', $tgl_awal, $tgl_akhir),
		'rows_pembelian_jasa' => persediaan_gen_v2_load_pembelian_bulan_rows($CI, 'tbl_pembelian_jasa', $tgl_awal, $tgl_akhir),
		'rekap' => persediaan_gen_proses_pembelian_build_rekap($CI, $bulan_target),
	);
}

/**
 * -------------------------------------------------------------------------
 * Generate Persediaan — box proses produksi (sys_unit_produk)
 * -------------------------------------------------------------------------
 */
function persediaan_gen_proses_produksi_build_rekap($CI, $bulan_target)
{
	$CI->load->helper('pembelian_persediaan');
	$bulan_target = trim((string) $bulan_target);
	$ts = strtotime($bulan_target . '-01');
	$tgl_awal = date('Y-m-01', $ts);
	$tgl_akhir = date('Y-m-t', $ts);

	$count_unit = persediaan_gen_v2_count_unit_produk_bulan($CI, $tgl_awal, $tgl_akhir);
	$sum_jumlah = persediaan_gen_v2_sum_jumlah_produksi_bulan($CI, $tgl_awal, $tgl_akhir);
	$sum_beli = persediaan_gen_v2_sum_beli_persediaan_unit_produk($CI, $tgl_awal, $tgl_akhir);
	$produksi_ok = persediaan_gen_proses_floats_equal($sum_jumlah, $sum_beli);

	$count_bahan = 0;
	$sum_jumlah_bahan = 0.0;
	$sum_nominal_bahan = 0.0;
	$sum_nominal_produk = 0.0;
	$sum_bahan_real = 0.0;

	$rows_bahan = persediaan_gen_proses_produksi_load_bahan_rows($CI, $tgl_awal, $tgl_akhir);
	$count_bahan = count($rows_bahan);
	foreach ($rows_bahan as $rb) {
		$sum_jumlah_bahan += (float) (isset($rb->jumlah_bahan_num) ? $rb->jumlah_bahan_num : 0);
		$sum_nominal_bahan += (float) (isset($rb->total_nominal_bahan) ? $rb->total_nominal_bahan : 0);
	}

	$rows_real = persediaan_gen_proses_produksi_build_bahan_real_rows($CI, $tgl_awal, $tgl_akhir);
	foreach ($rows_real as $rr) {
		$sum_nominal_produk += (float) (isset($rr->nominal_produk) ? $rr->nominal_produk : 0);
		$sum_bahan_real += (float) (isset($rr->bahan_real) ? $rr->bahan_real : 0);
	}

	return array(
		'produksi_ok' => $produksi_ok ? 1 : 0,
		'count_unit_produk' => $count_unit,
		'sum_jumlah_produksi' => $sum_jumlah,
		'sum_beli_persediaan' => $sum_beli,
		'sum_jumlah_produksi_fmt' => persediaan_format_angka_tampil($sum_jumlah),
		'sum_beli_persediaan_fmt' => persediaan_format_angka_tampil($sum_beli),
		'count_bahan' => $count_bahan,
		'sum_jumlah_bahan' => $sum_jumlah_bahan,
		'sum_nominal_bahan' => $sum_nominal_bahan,
		'sum_jumlah_bahan_fmt' => persediaan_format_angka_tampil($sum_jumlah_bahan),
		'sum_nominal_bahan_fmt' => persediaan_format_angka_tampil($sum_nominal_bahan),
		'sum_nominal_produk' => $sum_nominal_produk,
		'sum_nominal_produk_fmt' => persediaan_format_angka_tampil($sum_nominal_produk),
		'sum_bahan_real' => $sum_bahan_real,
		'sum_bahan_real_fmt' => persediaan_format_angka_tampil($sum_bahan_real),
	);
}

/**
 * Cari baris persediaan berdasarkan uuid_persediaan (= uuid_persediaan_bahan).
 * Prioritas: tanggal_beli di bulan target, lalu baris terbaru.
 */
function persediaan_gen_proses_produksi_lookup_persediaan_bahan($CI, $uuid_bahan, $tgl_awal, $tgl_akhir)
{
	$uuid_bahan = trim((string) $uuid_bahan);
	if ($uuid_bahan === '' || !$CI->db->table_exists('persediaan') || !$CI->db->field_exists('uuid_persediaan', 'persediaan')) {
		return null;
	}

	$rows = $CI->db->query(
		"SELECT `id`, `uuid_persediaan`, `namabarang`, `satuan`, `hpp`, `tanggal_beli`, `spop`, `kode_barang`, `total_10`
		FROM `persediaan`
		WHERE `uuid_persediaan` = ?
		ORDER BY `tanggal_beli` DESC, `id` DESC
		LIMIT 20",
		array($uuid_bahan)
	)->result();

	if (empty($rows)) {
		return null;
	}

	$tgl_awal = trim((string) $tgl_awal);
	$tgl_akhir = trim((string) $tgl_akhir);
	foreach ($rows as $r) {
		$tb = isset($r->tanggal_beli) ? trim((string) $r->tanggal_beli) : '';
		if ($tb !== '' && $tb !== '0000-00-00' && $tb >= $tgl_awal && $tb <= $tgl_akhir) {
			return $r;
		}
	}

	return $rows[0];
}

function persediaan_gen_v2_load_unit_produk_bahan_bulan_rows($CI, $tgl_awal, $tgl_akhir)
{
	if (!$CI->db->table_exists('sys_unit_produk_bahan')) {
		return array();
	}

	return $CI->db->query(
		"SELECT * FROM `sys_unit_produk_bahan`
		WHERE `tgl_transaksi` IS NOT NULL AND `tgl_transaksi` <> '0000-00-00'
		AND DATE(`tgl_transaksi`) >= ? AND DATE(`tgl_transaksi`) <= ?
		ORDER BY `tgl_transaksi` ASC, `nama_barang_bahan` ASC, `id` ASC",
		array(trim((string) $tgl_awal), trim((string) $tgl_akhir))
	)->result();
}

/**
 * Tab 3: detail bahan produk — harga/satuan diambil dari persediaan via uuid_persediaan_bahan.
 */
function persediaan_gen_proses_produksi_load_bahan_rows($CI, $tgl_awal, $tgl_akhir)
{
	$CI->load->helper('pembelian_persediaan');
	$raw = persediaan_gen_v2_load_unit_produk_bahan_bulan_rows($CI, $tgl_awal, $tgl_akhir);
	$out = array();

	// Map produk untuk nama produk (via uuid_persediaan bahan = uuid_persediaan produk)
	$produk_by_uuid = array();
	$produk_rows = persediaan_gen_v2_load_unit_produk_bulan_rows($CI, $tgl_awal, $tgl_akhir);
	foreach ($produk_rows as $p) {
		$u = isset($p->uuid_persediaan) ? trim((string) $p->uuid_persediaan) : '';
		if ($u !== '') {
			$produk_by_uuid[$u] = $p;
		}
	}

	foreach ($raw as $row) {
		$uuid_bahan = isset($row->uuid_persediaan_bahan) ? trim((string) $row->uuid_persediaan_bahan) : '';
		$jumlah = (float) persediaan_parse_angka(isset($row->jumlah_bahan) ? $row->jumlah_bahan : 0);
		$pers = $uuid_bahan !== '' ? persediaan_gen_proses_produksi_lookup_persediaan_bahan($CI, $uuid_bahan, $tgl_awal, $tgl_akhir) : null;

		$harga_pers = $pers && isset($pers->hpp) ? (float) persediaan_parse_angka($pers->hpp) : 0.0;
		$harga_bahan_tbl = (float) persediaan_parse_angka(isset($row->harga_satuan_bahan) ? $row->harga_satuan_bahan : 0);
		$harga_pakai = $harga_pers > 0 ? $harga_pers : $harga_bahan_tbl;
		$satuan_pers = $pers && isset($pers->satuan) ? trim((string) $pers->satuan) : '';
		$satuan_bahan = isset($row->satuan_bahan) ? trim((string) $row->satuan_bahan) : '';
		$nama_pers = $pers && isset($pers->namabarang) ? trim((string) $pers->namabarang) : '';

		$uuid_produk_link = isset($row->uuid_persediaan) ? trim((string) $row->uuid_persediaan) : '';
		$produk = ($uuid_produk_link !== '' && isset($produk_by_uuid[$uuid_produk_link])) ? $produk_by_uuid[$uuid_produk_link] : null;

		$item = clone $row;
		$item->jumlah_bahan_num = $jumlah;
		$item->harga_satuan_persediaan = $harga_pakai;
		$item->harga_sumber = $harga_pers > 0 ? 'persediaan' : 'sys_unit_produk_bahan';
		$item->satuan_tampil = $satuan_pers !== '' ? $satuan_pers : $satuan_bahan;
		$item->nama_bahan_tampil = $nama_pers !== '' ? $nama_pers : (isset($row->nama_barang_bahan) ? (string) $row->nama_barang_bahan : '');
		$item->total_nominal_bahan = $jumlah * $harga_pakai;
		$item->nama_produk = $produk && isset($produk->nama_barang) ? (string) $produk->nama_barang : '';
		$item->id_persediaan_bahan = $pers && isset($pers->id) ? (int) $pers->id : 0;
		$item->match_persediaan = $pers ? 1 : 0;
		$out[] = $item;
	}

	return $out;
}

/**
 * Tab 2: bahan real produk = nominal harga jual produk − total nominal bahan.
 */
function persediaan_gen_proses_produksi_build_bahan_real_rows($CI, $tgl_awal, $tgl_akhir)
{
	$CI->load->helper('pembelian_persediaan');
	$produk_rows = persediaan_gen_v2_load_unit_produk_bulan_rows($CI, $tgl_awal, $tgl_akhir);
	$bahan_rows = persediaan_gen_proses_produksi_load_bahan_rows($CI, $tgl_awal, $tgl_akhir);

	$bahan_by_produk_uuid = array();
	foreach ($bahan_rows as $b) {
		$key = isset($b->uuid_persediaan) ? trim((string) $b->uuid_persediaan) : '';
		if ($key === '') {
			continue;
		}
		if (!isset($bahan_by_produk_uuid[$key])) {
			$bahan_by_produk_uuid[$key] = array(
				'jumlah' => 0.0,
				'nominal' => 0.0,
				'count' => 0,
			);
		}
		$bahan_by_produk_uuid[$key]['jumlah'] += (float) (isset($b->jumlah_bahan_num) ? $b->jumlah_bahan_num : 0);
		$bahan_by_produk_uuid[$key]['nominal'] += (float) (isset($b->total_nominal_bahan) ? $b->total_nominal_bahan : 0);
		$bahan_by_produk_uuid[$key]['count']++;
	}

	$out = array();
	foreach ($produk_rows as $p) {
		$uuid = isset($p->uuid_persediaan) ? trim((string) $p->uuid_persediaan) : '';
		$jumlah = (float) persediaan_parse_angka(isset($p->jumlah_produksi) ? $p->jumlah_produksi : 0);
		$harga = (float) persediaan_parse_angka(isset($p->harga_satuan) ? $p->harga_satuan : 0);
		$nominal_produk = $jumlah * $harga;
		$agg = ($uuid !== '' && isset($bahan_by_produk_uuid[$uuid])) ? $bahan_by_produk_uuid[$uuid] : array(
			'jumlah' => 0.0,
			'nominal' => 0.0,
			'count' => 0,
		);

		$item = clone $p;
		$item->jumlah_produksi_num = $jumlah;
		$item->harga_satuan_num = $harga;
		$item->nominal_produk = $nominal_produk;
		$item->total_jumlah_bahan = (float) $agg['jumlah'];
		$item->total_nominal_bahan = (float) $agg['nominal'];
		$item->count_bahan = (int) $agg['count'];
		$item->bahan_real = $nominal_produk - (float) $agg['nominal'];
		$item->spop_tampil = persediaan_gen_v2_resolve_spop_unit_produk_row($CI, $p);
		$out[] = $item;
	}

	return $out;
}

/**
 * Tab 1 — Produksi Riil: produk + detail bahan + margin per baris bahan.
 * Margin baris = (harga_produk − harga_satuan_bahan) × jumlah_bahan
 */
function persediaan_gen_proses_produksi_build_riil_groups($CI, $tgl_awal, $tgl_akhir)
{
	$CI->load->helper('pembelian_persediaan');
	$produk_rows = persediaan_gen_v2_load_unit_produk_bulan_rows($CI, $tgl_awal, $tgl_akhir);
	$bahan_rows = persediaan_gen_proses_produksi_load_bahan_rows($CI, $tgl_awal, $tgl_akhir);

	$bahan_by_produk = array();
	foreach ($bahan_rows as $b) {
		$key = isset($b->uuid_persediaan) ? trim((string) $b->uuid_persediaan) : '';
		if ($key === '') {
			$key = '_tanpa_produk_' . (isset($b->id) ? (int) $b->id : uniqid());
		}
		if (!isset($bahan_by_produk[$key])) {
			$bahan_by_produk[$key] = array();
		}
		$bahan_by_produk[$key][] = $b;
	}

	$groups = array();
	$sum_all_bahan = 0.0;
	$sum_all_margin = 0.0;
	$sum_all_produk = 0.0;

	foreach ($produk_rows as $p) {
		$uuid = isset($p->uuid_persediaan) ? trim((string) $p->uuid_persediaan) : '';
		$jumlah_produk = (float) persediaan_parse_angka(isset($p->jumlah_produksi) ? $p->jumlah_produksi : 0);
		$harga_produk = (float) persediaan_parse_angka(isset($p->harga_satuan) ? $p->harga_satuan : 0);
		$nominal_produk = $jumlah_produk * $harga_produk;
		$bahan_list = ($uuid !== '' && isset($bahan_by_produk[$uuid])) ? $bahan_by_produk[$uuid] : array();
		if ($uuid !== '' && isset($bahan_by_produk[$uuid])) {
			unset($bahan_by_produk[$uuid]);
		}

		$detail = array();
		$total_harga_bahan = 0.0;
		$total_margin = 0.0;
		$total_jumlah_bahan = 0.0;
		$no_bahan = 0;
		foreach ($bahan_list as $b) {
			$no_bahan++;
			$jb = (float) (isset($b->jumlah_bahan_num) ? $b->jumlah_bahan_num : 0);
			$hb = (float) (isset($b->harga_satuan_persediaan) ? $b->harga_satuan_persediaan : 0);
			$harga_bahan_line = $jb * $hb;
			// Margin = (harga produk − harga satuan bahan) × jumlah bahan
			$margin_line = ($harga_produk - $hb) * $jb;
			$total_harga_bahan += $harga_bahan_line;
			$total_margin += $margin_line;
			$total_jumlah_bahan += $jb;

			$detail[] = (object) array(
				'no' => $no_bahan,
				'id_bahan' => isset($b->id) ? (int) $b->id : 0,
				'tgl_transaksi' => isset($b->tgl_transaksi) ? $b->tgl_transaksi : '',
				'kode_barang_bahan' => isset($b->kode_barang_bahan) ? (string) $b->kode_barang_bahan : '',
				'nama_bahan' => isset($b->nama_bahan_tampil) ? (string) $b->nama_bahan_tampil : (isset($b->nama_barang_bahan) ? (string) $b->nama_barang_bahan : ''),
				'uuid_persediaan_bahan' => isset($b->uuid_persediaan_bahan) ? (string) $b->uuid_persediaan_bahan : '',
				'jumlah_bahan' => $jb,
				'satuan' => isset($b->satuan_tampil) ? (string) $b->satuan_tampil : '',
				'harga_satuan_bahan' => $hb,
				'total_harga_bahan' => $harga_bahan_line,
				'harga_produk' => $harga_produk,
				'harga_margin' => $margin_line,
				'harga_sumber' => isset($b->harga_sumber) ? (string) $b->harga_sumber : '',
				'match_persediaan' => !empty($b->match_persediaan) ? 1 : 0,
			);
		}

		$sum_all_bahan += $total_harga_bahan;
		$sum_all_margin += $total_margin;
		$sum_all_produk += $nominal_produk;

		$groups[] = (object) array(
			'produk' => $p,
			'uuid_persediaan' => $uuid,
			'nama_produk' => isset($p->nama_barang) ? (string) $p->nama_barang : '',
			'nama_unit' => isset($p->nama_unit) ? (string) $p->nama_unit : '',
			'kode_barang' => isset($p->kode_barang) ? (string) $p->kode_barang : '',
			'tgl_transaksi' => isset($p->tgl_transaksi) ? $p->tgl_transaksi : '',
			'spop' => persediaan_gen_v2_resolve_spop_unit_produk_row($CI, $p),
			'jumlah_produksi' => $jumlah_produk,
			'harga_produk' => $harga_produk,
			'nominal_produk' => $nominal_produk,
			'satuan' => isset($p->satuan) ? (string) $p->satuan : '',
			'bahan' => $detail,
			'count_bahan' => count($detail),
			'total_jumlah_bahan' => $total_jumlah_bahan,
			'total_harga_bahan' => $total_harga_bahan,
			'total_margin' => $total_margin,
		);
	}

	// Bahan tanpa produk terhubung
	foreach ($bahan_by_produk as $orphan_list) {
		if (empty($orphan_list)) {
			continue;
		}
		$first = $orphan_list[0];
		$detail = array();
		$total_harga_bahan = 0.0;
		$total_margin = 0.0;
		$total_jumlah_bahan = 0.0;
		$no_bahan = 0;
		foreach ($orphan_list as $b) {
			$no_bahan++;
			$jb = (float) (isset($b->jumlah_bahan_num) ? $b->jumlah_bahan_num : 0);
			$hb = (float) (isset($b->harga_satuan_persediaan) ? $b->harga_satuan_persediaan : 0);
			$harga_bahan_line = $jb * $hb;
			$total_harga_bahan += $harga_bahan_line;
			$total_jumlah_bahan += $jb;
			$detail[] = (object) array(
				'no' => $no_bahan,
				'id_bahan' => isset($b->id) ? (int) $b->id : 0,
				'tgl_transaksi' => isset($b->tgl_transaksi) ? $b->tgl_transaksi : '',
				'kode_barang_bahan' => isset($b->kode_barang_bahan) ? (string) $b->kode_barang_bahan : '',
				'nama_bahan' => isset($b->nama_bahan_tampil) ? (string) $b->nama_bahan_tampil : '',
				'uuid_persediaan_bahan' => isset($b->uuid_persediaan_bahan) ? (string) $b->uuid_persediaan_bahan : '',
				'jumlah_bahan' => $jb,
				'satuan' => isset($b->satuan_tampil) ? (string) $b->satuan_tampil : '',
				'harga_satuan_bahan' => $hb,
				'total_harga_bahan' => $harga_bahan_line,
				'harga_produk' => 0.0,
				'harga_margin' => 0.0 - $harga_bahan_line,
				'harga_sumber' => isset($b->harga_sumber) ? (string) $b->harga_sumber : '',
				'match_persediaan' => !empty($b->match_persediaan) ? 1 : 0,
			);
			$total_margin += (0.0 - $harga_bahan_line);
		}
		$sum_all_bahan += $total_harga_bahan;
		$sum_all_margin += $total_margin;
		$groups[] = (object) array(
			'produk' => null,
			'uuid_persediaan' => isset($first->uuid_persediaan) ? (string) $first->uuid_persediaan : '',
			'nama_produk' => '(Produk tidak terhubung)',
			'nama_unit' => isset($first->nama_unit) ? (string) $first->nama_unit : '',
			'kode_barang' => '',
			'tgl_transaksi' => isset($first->tgl_transaksi) ? $first->tgl_transaksi : '',
			'spop' => '',
			'jumlah_produksi' => 0.0,
			'harga_produk' => 0.0,
			'nominal_produk' => 0.0,
			'satuan' => '',
			'bahan' => $detail,
			'count_bahan' => count($detail),
			'total_jumlah_bahan' => $total_jumlah_bahan,
			'total_harga_bahan' => $total_harga_bahan,
			'total_margin' => $total_margin,
		);
	}

	return array(
		'groups' => $groups,
		'sum_nominal_produk' => $sum_all_produk,
		'sum_harga_bahan' => $sum_all_bahan,
		'sum_margin' => $sum_all_margin,
	);
}

/**
 * Tab 2 — Margin Produk: agregasi bahan dengan harga asli persediaan (pengurangan stok).
 */
function persediaan_gen_proses_produksi_build_margin_produk_rows($CI, $tgl_awal, $tgl_akhir)
{
	$CI->load->helper('pembelian_persediaan');
	$bahan_rows = persediaan_gen_proses_produksi_load_bahan_rows($CI, $tgl_awal, $tgl_akhir);
	$agg = array();

	foreach ($bahan_rows as $b) {
		$uuid = isset($b->uuid_persediaan_bahan) ? trim((string) $b->uuid_persediaan_bahan) : '';
		$key = $uuid !== '' ? $uuid : ('id:' . (isset($b->id) ? (int) $b->id : 0));
		$jb = (float) (isset($b->jumlah_bahan_num) ? $b->jumlah_bahan_num : 0);
		$hb = (float) (isset($b->harga_satuan_persediaan) ? $b->harga_satuan_persediaan : 0);
		$nominal = $jb * $hb;

		if (!isset($agg[$key])) {
			$pers_total_10 = 0.0;
			$pers_nama = isset($b->nama_bahan_tampil) ? (string) $b->nama_bahan_tampil : '';
			$pers_satuan = isset($b->satuan_tampil) ? (string) $b->satuan_tampil : '';
			$pers_hpp = $hb;
			$pers_id = isset($b->id_persediaan_bahan) ? (int) $b->id_persediaan_bahan : 0;

			if ($uuid !== '') {
				$pers = persediaan_gen_proses_produksi_lookup_persediaan_bahan($CI, $uuid, $tgl_awal, $tgl_akhir);
				if ($pers) {
					if (isset($pers->total_10)) {
						$pers_total_10 = (float) persediaan_parse_angka($pers->total_10);
					} else {
						// ambil total_10 terpisah jika lookup terbatas kolom
						$row_full = $CI->db->select('total_10, namabarang, satuan, hpp, id')
							->from('persediaan')
							->where('uuid_persediaan', $uuid)
							->order_by('tanggal_beli', 'DESC')
							->limit(1)
							->get()
							->row();
						if ($row_full) {
							$pers_total_10 = (float) persediaan_parse_angka(isset($row_full->total_10) ? $row_full->total_10 : 0);
							if (!empty($row_full->namabarang)) {
								$pers_nama = (string) $row_full->namabarang;
							}
							if (!empty($row_full->satuan)) {
								$pers_satuan = (string) $row_full->satuan;
							}
							if (isset($row_full->hpp) && persediaan_parse_angka($row_full->hpp) > 0) {
								$pers_hpp = (float) persediaan_parse_angka($row_full->hpp);
							}
							$pers_id = isset($row_full->id) ? (int) $row_full->id : $pers_id;
						}
					}
				}
			}

			$agg[$key] = array(
				'uuid_persediaan_bahan' => $uuid,
				'id_persediaan' => $pers_id,
				'nama_bahan' => $pers_nama !== '' ? $pers_nama : (isset($b->nama_barang_bahan) ? (string) $b->nama_barang_bahan : ''),
				'kode_barang_bahan' => isset($b->kode_barang_bahan) ? (string) $b->kode_barang_bahan : '',
				'satuan' => $pers_satuan,
				'harga_satuan_asli' => $pers_hpp > 0 ? $pers_hpp : $hb,
				'jumlah_digunakan' => 0.0,
				'total_pengurangan' => 0.0,
				'total_10_persediaan' => $pers_total_10,
				'count_transaksi' => 0,
				'match_persediaan' => !empty($b->match_persediaan) ? 1 : 0,
				'produk_terkait' => array(),
			);
		}

		$agg[$key]['jumlah_digunakan'] += $jb;
		$agg[$key]['total_pengurangan'] += $nominal;
		$agg[$key]['count_transaksi']++;
		$nama_produk = isset($b->nama_produk) ? trim((string) $b->nama_produk) : '';
		if ($nama_produk !== '' && !in_array($nama_produk, $agg[$key]['produk_terkait'], true)) {
			$agg[$key]['produk_terkait'][] = $nama_produk;
		}
	}

	$out = array();
	$sum_jumlah = 0.0;
	$sum_pengurangan = 0.0;
	foreach ($agg as $row) {
		$harga = (float) $row['harga_satuan_asli'];
		$jumlah = (float) $row['jumlah_digunakan'];
		$pengurangan = $jumlah * $harga;
		$total_10 = (float) $row['total_10_persediaan'];
		$sisa_estimasi = $total_10; // total_10 sudah setelah proses; tampilkan sebagai stok saat ini
		$sum_jumlah += $jumlah;
		$sum_pengurangan += $pengurangan;

		$out[] = (object) array(
			'uuid_persediaan_bahan' => $row['uuid_persediaan_bahan'],
			'id_persediaan' => $row['id_persediaan'],
			'nama_bahan' => $row['nama_bahan'],
			'kode_barang_bahan' => $row['kode_barang_bahan'],
			'satuan' => $row['satuan'],
			'harga_satuan_asli' => $harga,
			'jumlah_digunakan' => $jumlah,
			'total_pengurangan' => $pengurangan,
			'total_10_persediaan' => $total_10,
			'sisa_setelah_pakai_estimasi' => $sisa_estimasi,
			'count_transaksi' => (int) $row['count_transaksi'],
			'match_persediaan' => (int) $row['match_persediaan'],
			'produk_terkait' => implode(', ', $row['produk_terkait']),
		);
	}

	usort($out, function ($a, $b) {
		return strcmp((string) $a->nama_bahan, (string) $b->nama_bahan);
	});

	return array(
		'rows' => $out,
		'sum_jumlah' => $sum_jumlah,
		'sum_pengurangan' => $sum_pengurangan,
	);
}

function persediaan_generate_proses_produksi_package($CI, $bulan_target)
{
	$bulan_target = trim((string) $bulan_target);
	if (!preg_match('/^\d{4}-\d{2}$/', $bulan_target)) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');
	}

	$ts = strtotime($bulan_target . '-01');
	if ($ts === false) {
		return array('ok' => false, 'message' => 'Bulan target tidak valid.');
	}

	$tgl_awal = date('Y-m-01', $ts);
	$tgl_akhir = date('Y-m-t', $ts);
	$CI->load->helper('pembelian_persediaan');

	$rows_unit_produk = persediaan_gen_v2_load_unit_produk_bulan_rows($CI, $tgl_awal, $tgl_akhir);
	$rows_bahan = persediaan_gen_proses_produksi_load_bahan_rows($CI, $tgl_awal, $tgl_akhir);
	$rows_bahan_real = persediaan_gen_proses_produksi_build_bahan_real_rows($CI, $tgl_awal, $tgl_akhir);
	$riil = persediaan_gen_proses_produksi_build_riil_groups($CI, $tgl_awal, $tgl_akhir);
	$margin = persediaan_gen_proses_produksi_build_margin_produk_rows($CI, $tgl_awal, $tgl_akhir);
	$rekap = persediaan_gen_proses_produksi_build_rekap($CI, $bulan_target);
	$rekap['sum_margin_riil'] = isset($riil['sum_margin']) ? $riil['sum_margin'] : 0;
	$rekap['sum_margin_riil_fmt'] = persediaan_format_angka_tampil($rekap['sum_margin_riil']);
	$rekap['sum_pengurangan_persediaan'] = isset($margin['sum_pengurangan']) ? $margin['sum_pengurangan'] : 0;
	$rekap['sum_pengurangan_persediaan_fmt'] = persediaan_format_angka_tampil($rekap['sum_pengurangan_persediaan']);

	return array(
		'ok' => true,
		'bulan_target' => $bulan_target,
		'bulan_target_label' => date('m/Y', $ts),
		'tgl_awal' => $tgl_awal,
		'tgl_akhir' => $tgl_akhir,
		'rows_unit_produk' => $rows_unit_produk,
		'rows_bahan_real' => $rows_bahan_real,
		'rows_bahan' => $rows_bahan,
		'groups_produksi_riil' => isset($riil['groups']) ? $riil['groups'] : array(),
		'riil_summary' => $riil,
		'rows_margin_produk' => isset($margin['rows']) ? $margin['rows'] : array(),
		'margin_summary' => $margin,
		'rekap' => $rekap,
	);
}

/**
 * -------------------------------------------------------------------------
 * Generate Persediaan — box proses penjualan (tbl_penjualan)
 * -------------------------------------------------------------------------
 */
function persediaan_gen_proses_penjualan_build_rekap($CI, $bulan_target)
{
	$CI->load->helper('pembelian_persediaan');
	$bulan_target = trim((string) $bulan_target);
	$ts = strtotime($bulan_target . '-01');
	$tgl_awal = date('Y-m-01', $ts);
	$tgl_akhir = date('Y-m-t', $ts);

	$count_penjualan = persediaan_gen_v2_count_penjualan_bulan($CI, $tgl_awal, $tgl_akhir);
	$sum_jumlah = persediaan_gen_v2_sum_jumlah_penjualan_bulan($CI, $tgl_awal, $tgl_akhir);
	$sum_penj_pers = persediaan_gen_v2_sum_penjualan_persediaan_bulan($CI, $tgl_awal, $tgl_akhir);
	$penjualan_ok = persediaan_gen_proses_floats_equal($sum_jumlah, $sum_penj_pers);

	$ctx = array(
		'tgl_awal' => $tgl_awal,
		'tgl_akhir' => $tgl_akhir,
		'tanggal_beli_target' => $tgl_awal,
	);
	$map = persediaan_gen_v2_build_map_persediaan_bulan_range($CI, $tgl_awal, $tgl_akhir);
	$cache_pembelian = persediaan_gen_v2_build_verifikasi_cache($CI);
	$rows_all = persediaan_gen_v2_load_penjualan_bulan_rows($CI, $tgl_awal, $tgl_akhir);

	$count_masuk = 0;
	$count_tidak_masuk = 0;
	$count_manual = 0;
	$count_skip = 0;
	foreach ($rows_all as $row_pen) {
		$cls = persediaan_gen_v2_classify_penjualan_row_display($CI, $ctx, $row_pen, $map, $cache_pembelian);
		$kat = isset($cls->status_kategori) ? $cls->status_kategori : 'tidak_masuk';
		if ($kat === 'masuk') {
			$count_masuk++;
		} elseif ($kat === 'manual') {
			$count_manual++;
		} elseif ($kat === 'skip') {
			$count_skip++;
		} else {
			$count_tidak_masuk++;
		}
	}

	return array(
		'penjualan_ok' => $penjualan_ok ? 1 : 0,
		'count_penjualan' => $count_penjualan,
		'sum_jumlah_penjualan' => $sum_jumlah,
		'sum_penjualan_persediaan' => $sum_penj_pers,
		'sum_jumlah_penjualan_fmt' => persediaan_format_angka_tampil($sum_jumlah),
		'sum_penjualan_persediaan_fmt' => persediaan_format_angka_tampil($sum_penj_pers),
		'count_masuk' => $count_masuk,
		'count_tidak_masuk' => $count_tidak_masuk,
		'count_manual' => $count_manual,
		'count_skip' => $count_skip,
	);
}

function persediaan_generate_proses_penjualan_package($CI, $bulan_target)
{
	$bulan_target = trim((string) $bulan_target);
	if (!preg_match('/^\d{4}-\d{2}$/', $bulan_target)) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');
	}

	$ts = strtotime($bulan_target . '-01');
	if ($ts === false) {
		return array('ok' => false, 'message' => 'Bulan target tidak valid.');
	}

	$tgl_awal = date('Y-m-01', $ts);
	$tgl_akhir = date('Y-m-t', $ts);
	$CI->load->helper('pembelian_persediaan');

	$ctx = array(
		'tgl_awal' => $tgl_awal,
		'tgl_akhir' => $tgl_akhir,
		'tanggal_beli_target' => $tgl_awal,
	);
	$map = persediaan_gen_v2_build_map_persediaan_bulan_range($CI, $tgl_awal, $tgl_akhir);
	$cache_pembelian = persediaan_gen_v2_build_verifikasi_cache($CI);
	$rows_all = persediaan_gen_v2_load_penjualan_bulan_rows($CI, $tgl_awal, $tgl_akhir);

	$rows_masuk = array();
	$rows_tidak_masuk = array();
	$rows_manual = array();
	foreach ($rows_all as $row_pen) {
		$cls = persediaan_gen_v2_classify_penjualan_row_display($CI, $ctx, $row_pen, $map, $cache_pembelian);
		$kat = isset($cls->status_kategori) ? $cls->status_kategori : 'tidak_masuk';
		if ($kat === 'masuk') {
			$rows_masuk[] = $cls;
		} elseif ($kat === 'manual') {
			$rows_manual[] = $cls;
		} elseif ($kat !== 'skip') {
			$rows_tidak_masuk[] = $cls;
		}
	}

	return array(
		'ok' => true,
		'bulan_target' => $bulan_target,
		'bulan_target_label' => date('m/Y', $ts),
		'tgl_awal' => $tgl_awal,
		'tgl_akhir' => $tgl_akhir,
		'rows_masuk' => $rows_masuk,
		'rows_tidak_masuk' => $rows_tidak_masuk,
		'rows_manual' => $rows_manual,
		'rekap' => persediaan_gen_proses_penjualan_build_rekap($CI, $bulan_target),
	);
}

/**
 * -------------------------------------------------------------------------
 * Generate Persediaan — export Excel datatable box verifikasi proses
 * -------------------------------------------------------------------------
 */
function persediaan_gen_proses_excel_jenis_definitions()
{
	return array(
		'proses_persediaan_sumber_barang' => array(
			'title' => 'Verifikasi Copy — Persediaan Sumber Barang',
			'type' => 'persediaan',
			'kategori' => 'barang',
		),
		'proses_persediaan_sumber_jasa' => array(
			'title' => 'Verifikasi Copy — Persediaan Sumber Jasa',
			'type' => 'persediaan',
			'kategori' => 'jasa',
		),
		'proses_persediaan_target_copy_barang' => array(
			'title' => 'Verifikasi Copy — Hasil Copy Barang',
			'type' => 'persediaan_copy',
			'kategori' => 'barang',
		),
		'proses_persediaan_target_copy_jasa' => array(
			'title' => 'Verifikasi Copy — Hasil Copy Jasa',
			'type' => 'persediaan_copy',
			'kategori' => 'jasa',
		),
		'proses_persediaan_full_barang' => array(
			'title' => 'Verifikasi Lengkap — Persediaan Barang',
			'type' => 'persediaan',
			'kategori' => 'barang',
		),
		'proses_persediaan_full_jasa' => array(
			'title' => 'Verifikasi Lengkap — Persediaan Jasa',
			'type' => 'persediaan',
			'kategori' => 'jasa',
		),
		'proses_pembelian_barang' => array(
			'title' => 'Verifikasi Pembelian — Barang',
			'type' => 'pembelian',
			'tab_mode' => 'barang',
		),
		'proses_pembelian_jasa' => array(
			'title' => 'Verifikasi Pembelian — Jasa',
			'type' => 'pembelian',
			'tab_mode' => 'jasa',
		),
		'proses_produksi' => array(
			'title' => 'Verifikasi Produksi — Data Produk (sys_unit_produk)',
			'type' => 'produksi',
			'produksi_mode' => 'produk',
		),
		'proses_produksi_bahan_real' => array(
			'title' => 'Verifikasi Produksi — Data Bahan Real Produk',
			'type' => 'produksi',
			'produksi_mode' => 'bahan_real',
		),
		'proses_produksi_bahan' => array(
			'title' => 'Verifikasi Produksi — Data Bahan Produk',
			'type' => 'produksi',
			'produksi_mode' => 'bahan',
		),
		'proses_produksi_riil' => array(
			'title' => 'Verifikasi Produksi — Data Produksi Riil',
			'type' => 'produksi',
			'produksi_mode' => 'riil',
		),
		'proses_produksi_margin' => array(
			'title' => 'Verifikasi Produksi — Margin Produk / Pengurangan Persediaan',
			'type' => 'produksi',
			'produksi_mode' => 'margin',
		),
		'proses_penjualan_masuk' => array(
			'title' => 'Verifikasi Penjualan — Masuk Persediaan',
			'type' => 'penjualan',
			'penjualan_kategori' => 'masuk',
		),
		'proses_penjualan_tidak' => array(
			'title' => 'Verifikasi Penjualan — Tidak Masuk',
			'type' => 'penjualan',
			'penjualan_kategori' => 'tidak_masuk',
		),
		'proses_penjualan_manual' => array(
			'title' => 'Verifikasi Penjualan — Verifikasi Manual',
			'type' => 'penjualan',
			'penjualan_kategori' => 'manual',
		),
	);
}

function persediaan_gen_proses_excel_load_rows($CI, $bulan_target, $jenis, $def)
{
	$CI->load->helper('pembelian_persediaan');
	$bulan_target = trim((string) $bulan_target);
	$ts = strtotime($bulan_target . '-01');
	$bulan_sumber = date('Y-m', strtotime('-1 month', $ts));
	$tgl_awal = date('Y-m-01', $ts);
	$tgl_akhir = date('Y-m-t', $ts);
	$type = isset($def['type']) ? $def['type'] : '';

	if ($type === 'persediaan') {
		$bulan_data = $bulan_target;
		if (strpos($jenis, '_sumber_') !== false) {
			$bulan_data = $bulan_sumber;
		}
		$rows = persediaan_gen_proses_load_rows_bulan($CI, $bulan_data);
		$is_jasa = isset($def['kategori']) && $def['kategori'] === 'jasa';
		return persediaan_filter_rows_by_kategori_tab($rows, $is_jasa);
	}

	if ($type === 'persediaan_copy') {
		$rows_all = persediaan_gen_proses_load_rows_bulan($CI, $bulan_target);
		$rows = persediaan_gen_proses_filter_rows_hasil_copy($rows_all);
		$is_jasa = isset($def['kategori']) && $def['kategori'] === 'jasa';
		return persediaan_filter_rows_by_kategori_tab($rows, $is_jasa);
	}

	if ($type === 'pembelian') {
		$tabel = (isset($def['tab_mode']) && $def['tab_mode'] === 'jasa') ? 'tbl_pembelian_jasa' : 'tbl_pembelian';
		return persediaan_gen_v2_load_pembelian_bulan_rows($CI, $tabel, $tgl_awal, $tgl_akhir);
	}

	if ($type === 'produksi') {
		$mode = isset($def['produksi_mode']) ? $def['produksi_mode'] : 'produk';
		if ($mode === 'bahan') {
			return persediaan_gen_proses_produksi_load_bahan_rows($CI, $tgl_awal, $tgl_akhir);
		}
		if ($mode === 'bahan_real') {
			return persediaan_gen_proses_produksi_build_bahan_real_rows($CI, $tgl_awal, $tgl_akhir);
		}
		if ($mode === 'riil') {
			$riil = persediaan_gen_proses_produksi_build_riil_groups($CI, $tgl_awal, $tgl_akhir);
			return isset($riil['groups']) ? $riil['groups'] : array();
		}
		if ($mode === 'margin') {
			$margin = persediaan_gen_proses_produksi_build_margin_produk_rows($CI, $tgl_awal, $tgl_akhir);
			return isset($margin['rows']) ? $margin['rows'] : array();
		}
		return persediaan_gen_v2_load_unit_produk_bulan_rows($CI, $tgl_awal, $tgl_akhir);
	}

	if ($type === 'penjualan') {
		$ctx = array(
			'tgl_awal' => $tgl_awal,
			'tgl_akhir' => $tgl_akhir,
			'tanggal_beli_target' => $tgl_awal,
		);
		$map = persediaan_gen_v2_build_map_persediaan_bulan_range($CI, $tgl_awal, $tgl_akhir);
		$cache_pembelian = persediaan_gen_v2_build_verifikasi_cache($CI);
		$rows_all = persediaan_gen_v2_load_penjualan_bulan_rows($CI, $tgl_awal, $tgl_akhir);
		$kat_want = isset($def['penjualan_kategori']) ? $def['penjualan_kategori'] : 'masuk';
		$out = array();
		foreach ($rows_all as $row_pen) {
			$cls = persediaan_gen_v2_classify_penjualan_row_display($CI, $ctx, $row_pen, $map, $cache_pembelian);
			$kat = isset($cls->status_kategori) ? $cls->status_kategori : 'tidak_masuk';
			if ($kat_want === 'masuk' && $kat === 'masuk') {
				$out[] = $cls;
			} elseif ($kat_want === 'tidak_masuk' && $kat !== 'masuk' && $kat !== 'manual' && $kat !== 'skip') {
				$out[] = $cls;
			} elseif ($kat_want === 'manual' && $kat === 'manual') {
				$out[] = $cls;
			}
		}
		return $out;
	}

	return array();
}

function persediaan_gen_proses_excel_bulan_label($bulan)
{
	$bulan = trim((string) $bulan);
	if (preg_match('/^\d{4}-\d{2}$/', $bulan)) {
		return date('m/Y', strtotime($bulan . '-01'));
	}
	return $bulan;
}

function persediaan_gen_proses_excel_write_pembelian_rows($rows, $tab_mode, $title, $bulan_label)
{
	$CI = function_exists('get_instance') ? get_instance() : null;
	if ($CI) {
		$CI->load->helper('persediaan_display');
	}

	$show_kategori = (strtolower(trim((string) $tab_mode)) !== 'jasa');
	$headers = array('No', 'Tgl Po', 'Spop');
	if ($show_kategori) {
		$headers[] = 'Kategori';
	}
	$headers = array_merge($headers, array(
		'No. Faktur/Kwitansi', 'Supplier', 'Kode Barang', 'Nama Barang', 'Jumlah', 'Satuan',
		'Konsumen', 'Harga Satuan', 'Harga Total', 'Statuslu', 'Kas/Bank', 'Tgl Bayar',
	));

	$sum_jumlah = 0.0;
	$sum_harga_total = 0.0;
	$col_jumlah = $show_kategori ? 8 : 7;
	$col_harga_total = $show_kategori ? 12 : 11;

	xlsBOF();
	xlsWriteLabelBold14(0, 0, $title . ' — ' . $bulan_label);
	xlsWriteLabel(1, 0, 'Dicetak: ' . date('d/m/Y H:i:s') . ' | Total baris: ' . count($rows));
	persediaan_excel_write_headers(3, $headers);

	$row_num = 4;
	$no = 0;
	foreach ($rows as $row) {
		$no++;
		$harga_satuan = isset($row->harga_satuan) ? $row->harga_satuan : 0;
		$jumlah = isset($row->jumlah) ? $row->jumlah : 0;
		$harga_total = isset($row->harga_total) ? $row->harga_total : ((float) persediaan_parse_angka($jumlah) * (float) persediaan_parse_angka($harga_satuan));
		$sum_jumlah += (float) persediaan_parse_angka($jumlah);
		$sum_harga_total += (float) persediaan_parse_angka($harga_total);

		$cells = array(
			(string) $no,
			persediaan_gen_proses_pembelian_format_tgl(isset($row->tgl_po) ? $row->tgl_po : ''),
			isset($row->spop) ? (string) $row->spop : '',
		);
		if ($show_kategori) {
			$cells[] = isset($row->kategori) ? (string) $row->kategori : '';
		}
		$cells = array_merge($cells, array(
			isset($row->nmrfakturkwitansi) ? (string) $row->nmrfakturkwitansi : '',
			isset($row->supplier_nama) ? (string) $row->supplier_nama : '',
			isset($row->kode_barang) ? (string) $row->kode_barang : '',
			isset($row->uraian) ? (string) $row->uraian : '',
			persediaan_gen_proses_pembelian_format_jumlah($jumlah),
			isset($row->satuan) ? (string) $row->satuan : '',
			isset($row->konsumen) ? (string) $row->konsumen : '',
			persediaan_gen_proses_pembelian_format_nominal($harga_satuan),
			persediaan_gen_proses_pembelian_format_nominal($harga_total),
			isset($row->statuslu) ? (string) $row->statuslu : '',
			isset($row->kas_bank) ? (string) $row->kas_bank : (isset($row->kasbank) ? (string) $row->kasbank : ''),
			persediaan_gen_proses_pembelian_format_tgl(isset($row->tgl_bayar) ? $row->tgl_bayar : ''),
		));

		$row_style = ($row_num % 2 === 0) ? 18 : 7;
		foreach ($cells as $col => $cell) {
			$style = $row_style;
			if ($col === $col_jumlah || $col === $col_harga_total) {
				$style = 8;
			}
			xlsWriteCellStyle($row_num, $col, strip_tags((string) $cell), $style);
		}
		$row_num++;
	}

	foreach ($headers as $col => $label) {
		if ($col === 0) {
			xlsWriteCellStyle($row_num, $col, 'TOTAL', 24);
		} elseif ($col === $col_jumlah) {
			xlsWriteCellStyle($row_num, $col, persediaan_gen_proses_pembelian_format_jumlah($sum_jumlah), 25);
		} elseif ($col === $col_harga_total) {
			xlsWriteCellStyle($row_num, $col, persediaan_gen_proses_pembelian_format_nominal($sum_harga_total), 25);
		} else {
			xlsWriteCellStyle($row_num, $col, '', 24);
		}
	}

	xlsEOF();
}

function persediaan_gen_proses_excel_write_produksi_rows($CI, $rows, $title, $bulan_label)
{
	$CI->load->helper('pembelian_persediaan');
	$headers = array(
		'No', 'Tgl Transaksi', 'SPOP', 'Nama Unit', 'Kode Barang', 'Nama Barang',
		'Jumlah Produksi', 'Satuan', 'Harga Satuan', 'Total Nominal', 'Keterangan',
	);
	$col_jumlah = 6;
	$col_total_nominal = 9;
	$sum_jumlah = 0.0;
	$sum_total_nominal = 0.0;

	xlsBOF();
	xlsWriteLabelBold14(0, 0, $title . ' — ' . $bulan_label);
	xlsWriteLabel(1, 0, 'Dicetak: ' . date('d/m/Y H:i:s') . ' | Total baris: ' . count($rows));
	persediaan_excel_write_headers(3, $headers);

	$row_num = 4;
	$no = 0;
	foreach ($rows as $row) {
		$no++;
		$jumlah = isset($row->jumlah_produksi) ? $row->jumlah_produksi : 0;
		$harga_satuan = isset($row->harga_satuan) ? $row->harga_satuan : 0;
		$total_nominal = (float) persediaan_parse_angka($jumlah) * (float) persediaan_parse_angka($harga_satuan);
		$sum_jumlah += (float) persediaan_parse_angka($jumlah);
		$sum_total_nominal += $total_nominal;
		$spop_tampil = persediaan_gen_v2_resolve_spop_unit_produk_row($CI, $row);

		$cells = array(
			(string) $no,
			persediaan_gen_proses_pembelian_format_tgl(isset($row->tgl_transaksi) ? $row->tgl_transaksi : ''),
			$spop_tampil,
			isset($row->nama_unit) ? (string) $row->nama_unit : '',
			isset($row->kode_barang) ? (string) $row->kode_barang : '',
			isset($row->nama_barang) ? (string) $row->nama_barang : '',
			persediaan_gen_proses_pembelian_format_jumlah($jumlah),
			isset($row->satuan) ? (string) $row->satuan : '',
			persediaan_gen_proses_pembelian_format_nominal($harga_satuan),
			persediaan_gen_proses_pembelian_format_nominal($total_nominal),
			isset($row->keterangan) ? (string) $row->keterangan : '',
		);

		$row_style = ($row_num % 2 === 0) ? 18 : 7;
		foreach ($cells as $col => $cell) {
			$style = ($col === $col_jumlah || $col === $col_total_nominal) ? 8 : $row_style;
			xlsWriteCellStyle($row_num, $col, strip_tags((string) $cell), $style);
		}
		$row_num++;
	}

	foreach ($headers as $col => $label) {
		if ($col === 0) {
			xlsWriteCellStyle($row_num, $col, 'TOTAL', 24);
		} elseif ($col === $col_jumlah) {
			xlsWriteCellStyle($row_num, $col, persediaan_gen_proses_pembelian_format_jumlah($sum_jumlah), 25);
		} elseif ($col === $col_total_nominal) {
			xlsWriteCellStyle($row_num, $col, persediaan_gen_proses_pembelian_format_nominal($sum_total_nominal), 25);
		} else {
			xlsWriteCellStyle($row_num, $col, '', 24);
		}
	}

	xlsEOF();
}

function persediaan_gen_proses_excel_write_produksi_bahan_real_rows($rows, $title, $bulan_label)
{
	$headers = array(
		'No', 'Tgl Transaksi', 'SPOP', 'Nama Unit', 'Kode Barang', 'Nama Produk',
		'Jumlah Produksi', 'Harga Jual', 'Nominal Produk', 'Σ Jumlah Bahan', 'Σ Nominal Bahan',
		'Bahan Real (Produk−Bahan)', 'Jml Baris Bahan',
	);
	$col_jumlah_produk = 6;
	$col_nominal_produk = 8;
	$col_jumlah_bahan = 9;
	$col_nominal_bahan = 10;
	$col_bahan_real = 11;
	$sum_jumlah_produk = 0.0;
	$sum_nominal_produk = 0.0;
	$sum_jumlah_bahan = 0.0;
	$sum_nominal_bahan = 0.0;
	$sum_bahan_real = 0.0;

	xlsBOF();
	xlsWriteLabelBold14(0, 0, $title . ' — ' . $bulan_label);
	xlsWriteLabel(1, 0, 'Dicetak: ' . date('d/m/Y H:i:s') . ' | Total baris: ' . count($rows) . ' | Bahan Real = Nominal Produk − Nominal Bahan');
	persediaan_excel_write_headers(3, $headers);

	$row_num = 4;
	$no = 0;
	foreach ($rows as $row) {
		$no++;
		$jumlah = isset($row->jumlah_produksi_num) ? $row->jumlah_produksi_num : (isset($row->jumlah_produksi) ? $row->jumlah_produksi : 0);
		$harga = isset($row->harga_satuan_num) ? $row->harga_satuan_num : (isset($row->harga_satuan) ? $row->harga_satuan : 0);
		$nominal_produk = isset($row->nominal_produk) ? (float) $row->nominal_produk : ((float) persediaan_parse_angka($jumlah) * (float) persediaan_parse_angka($harga));
		$total_jb = isset($row->total_jumlah_bahan) ? (float) $row->total_jumlah_bahan : 0.0;
		$total_nb = isset($row->total_nominal_bahan) ? (float) $row->total_nominal_bahan : 0.0;
		$bahan_real = isset($row->bahan_real) ? (float) $row->bahan_real : ($nominal_produk - $total_nb);
		$sum_jumlah_produk += (float) persediaan_parse_angka($jumlah);
		$sum_nominal_produk += $nominal_produk;
		$sum_jumlah_bahan += $total_jb;
		$sum_nominal_bahan += $total_nb;
		$sum_bahan_real += $bahan_real;

		$cells = array(
			(string) $no,
			persediaan_gen_proses_pembelian_format_tgl(isset($row->tgl_transaksi) ? $row->tgl_transaksi : ''),
			isset($row->spop_tampil) ? (string) $row->spop_tampil : '',
			isset($row->nama_unit) ? (string) $row->nama_unit : '',
			isset($row->kode_barang) ? (string) $row->kode_barang : '',
			isset($row->nama_barang) ? (string) $row->nama_barang : '',
			persediaan_gen_proses_pembelian_format_jumlah($jumlah),
			persediaan_gen_proses_pembelian_format_nominal($harga),
			persediaan_gen_proses_pembelian_format_nominal($nominal_produk),
			persediaan_gen_proses_pembelian_format_jumlah($total_jb),
			persediaan_gen_proses_pembelian_format_nominal($total_nb),
			persediaan_gen_proses_pembelian_format_nominal($bahan_real),
			(string) (int) (isset($row->count_bahan) ? $row->count_bahan : 0),
		);

		$row_style = ($row_num % 2 === 0) ? 18 : 7;
		$money_cols = array($col_jumlah_produk, $col_nominal_produk, $col_jumlah_bahan, $col_nominal_bahan, $col_bahan_real);
		foreach ($cells as $col => $cell) {
			$style = in_array($col, $money_cols, true) ? 8 : $row_style;
			xlsWriteCellStyle($row_num, $col, strip_tags((string) $cell), $style);
		}
		$row_num++;
	}

	foreach ($headers as $col => $label) {
		if ($col === 0) {
			xlsWriteCellStyle($row_num, $col, 'TOTAL', 24);
		} elseif ($col === $col_jumlah_produk) {
			xlsWriteCellStyle($row_num, $col, persediaan_gen_proses_pembelian_format_jumlah($sum_jumlah_produk), 25);
		} elseif ($col === $col_nominal_produk) {
			xlsWriteCellStyle($row_num, $col, persediaan_gen_proses_pembelian_format_nominal($sum_nominal_produk), 25);
		} elseif ($col === $col_jumlah_bahan) {
			xlsWriteCellStyle($row_num, $col, persediaan_gen_proses_pembelian_format_jumlah($sum_jumlah_bahan), 25);
		} elseif ($col === $col_nominal_bahan) {
			xlsWriteCellStyle($row_num, $col, persediaan_gen_proses_pembelian_format_nominal($sum_nominal_bahan), 25);
		} elseif ($col === $col_bahan_real) {
			xlsWriteCellStyle($row_num, $col, persediaan_gen_proses_pembelian_format_nominal($sum_bahan_real), 25);
		} else {
			xlsWriteCellStyle($row_num, $col, '', 24);
		}
	}

	xlsEOF();
}

function persediaan_gen_proses_excel_write_produksi_bahan_rows($rows, $title, $bulan_label)
{
	$headers = array(
		'No', 'Tgl Transaksi', 'Nama Unit', 'Nama Produk', 'Kode Bahan', 'Nama Bahan',
		'UUID Persediaan Bahan', 'Jumlah Bahan', 'Satuan', 'Harga Satuan (Persediaan)',
		'Total Nominal', 'Sumber Harga', 'Match Persediaan',
	);
	$col_jumlah = 7;
	$col_total_nominal = 10;
	$sum_jumlah = 0.0;
	$sum_total_nominal = 0.0;

	xlsBOF();
	xlsWriteLabelBold14(0, 0, $title . ' — ' . $bulan_label);
	xlsWriteLabel(1, 0, 'Dicetak: ' . date('d/m/Y H:i:s') . ' | Total baris: ' . count($rows) . ' | Harga dari persediaan.uuid_persediaan = uuid_persediaan_bahan');
	persediaan_excel_write_headers(3, $headers);

	$row_num = 4;
	$no = 0;
	foreach ($rows as $row) {
		$no++;
		$jumlah = isset($row->jumlah_bahan_num) ? $row->jumlah_bahan_num : (isset($row->jumlah_bahan) ? $row->jumlah_bahan : 0);
		$harga = isset($row->harga_satuan_persediaan) ? $row->harga_satuan_persediaan : (isset($row->harga_satuan_bahan) ? $row->harga_satuan_bahan : 0);
		$total_nominal = isset($row->total_nominal_bahan) ? (float) $row->total_nominal_bahan : ((float) persediaan_parse_angka($jumlah) * (float) persediaan_parse_angka($harga));
		$sum_jumlah += (float) persediaan_parse_angka($jumlah);
		$sum_total_nominal += $total_nominal;

		$cells = array(
			(string) $no,
			persediaan_gen_proses_pembelian_format_tgl(isset($row->tgl_transaksi) ? $row->tgl_transaksi : ''),
			isset($row->nama_unit) ? (string) $row->nama_unit : '',
			isset($row->nama_produk) ? (string) $row->nama_produk : '',
			isset($row->kode_barang_bahan) ? (string) $row->kode_barang_bahan : '',
			isset($row->nama_bahan_tampil) ? (string) $row->nama_bahan_tampil : (isset($row->nama_barang_bahan) ? (string) $row->nama_barang_bahan : ''),
			isset($row->uuid_persediaan_bahan) ? (string) $row->uuid_persediaan_bahan : '',
			persediaan_gen_proses_pembelian_format_jumlah($jumlah),
			isset($row->satuan_tampil) ? (string) $row->satuan_tampil : (isset($row->satuan_bahan) ? (string) $row->satuan_bahan : ''),
			persediaan_gen_proses_pembelian_format_nominal($harga),
			persediaan_gen_proses_pembelian_format_nominal($total_nominal),
			isset($row->harga_sumber) ? (string) $row->harga_sumber : '',
			!empty($row->match_persediaan) ? 'Ada' : 'Tidak ada',
		);

		$row_style = ($row_num % 2 === 0) ? 18 : 7;
		foreach ($cells as $col => $cell) {
			$style = ($col === $col_jumlah || $col === $col_total_nominal) ? 8 : $row_style;
			xlsWriteCellStyle($row_num, $col, strip_tags((string) $cell), $style);
		}
		$row_num++;
	}

	foreach ($headers as $col => $label) {
		if ($col === 0) {
			xlsWriteCellStyle($row_num, $col, 'TOTAL', 24);
		} elseif ($col === $col_jumlah) {
			xlsWriteCellStyle($row_num, $col, persediaan_gen_proses_pembelian_format_jumlah($sum_jumlah), 25);
		} elseif ($col === $col_total_nominal) {
			xlsWriteCellStyle($row_num, $col, persediaan_gen_proses_pembelian_format_nominal($sum_total_nominal), 25);
		} else {
			xlsWriteCellStyle($row_num, $col, '', 24);
		}
	}

	xlsEOF();
}

function persediaan_gen_proses_excel_write_produksi_riil_rows($groups, $title, $bulan_label)
{
	$headers = array(
		'No Produk', 'Tgl', 'SPOP', 'Nama Produk', 'Qty Produksi', 'Harga Produk', 'Nominal Produk',
		'No Bahan', 'Kode Bahan', 'Nama Bahan', 'Jumlah Bahan', 'Satuan',
		'Harga Satuan Bahan', 'Total Harga Bahan', 'Harga Margin', 'Sumber Harga',
	);
	$col_qty_produk = 4;
	$col_nominal_produk = 6;
	$col_jumlah_bahan = 10;
	$col_total_bahan = 13;
	$col_margin = 14;
	$sum_qty = 0.0;
	$sum_nominal_produk = 0.0;
	$sum_jumlah_bahan = 0.0;
	$sum_total_bahan = 0.0;
	$sum_margin = 0.0;
	$baris_count = 0;

	xlsBOF();
	xlsWriteLabelBold14(0, 0, $title . ' — ' . $bulan_label);
	xlsWriteLabel(1, 0, 'Dicetak: ' . date('d/m/Y H:i:s') . ' | Margin = (Harga produk − Harga satuan bahan) × Jumlah bahan');
	persediaan_excel_write_headers(3, $headers);

	$row_num = 4;
	$no_produk = 0;
	foreach ($groups as $g) {
		$no_produk++;
		$sum_qty += (float) (isset($g->jumlah_produksi) ? $g->jumlah_produksi : 0);
		$sum_nominal_produk += (float) (isset($g->nominal_produk) ? $g->nominal_produk : 0);
		$sum_jumlah_bahan += (float) (isset($g->total_jumlah_bahan) ? $g->total_jumlah_bahan : 0);
		$sum_total_bahan += (float) (isset($g->total_harga_bahan) ? $g->total_harga_bahan : 0);
		$sum_margin += (float) (isset($g->total_margin) ? $g->total_margin : 0);

		$bahan = isset($g->bahan) && is_array($g->bahan) ? $g->bahan : array();
		if (empty($bahan)) {
			$baris_count++;
			$cells = array(
				(string) $no_produk,
				persediaan_gen_proses_pembelian_format_tgl(isset($g->tgl_transaksi) ? $g->tgl_transaksi : ''),
				isset($g->spop) ? (string) $g->spop : '',
				isset($g->nama_produk) ? (string) $g->nama_produk : '',
				persediaan_gen_proses_pembelian_format_jumlah(isset($g->jumlah_produksi) ? $g->jumlah_produksi : 0),
				persediaan_gen_proses_pembelian_format_nominal(isset($g->harga_produk) ? $g->harga_produk : 0),
				persediaan_gen_proses_pembelian_format_nominal(isset($g->nominal_produk) ? $g->nominal_produk : 0),
				'',
				'',
				'(tanpa bahan)',
				'',
				'',
				'',
				'',
				'',
				'',
			);
			$row_style = ($row_num % 2 === 0) ? 18 : 7;
			foreach ($cells as $col => $cell) {
				xlsWriteCellStyle($row_num, $col, strip_tags((string) $cell), $row_style);
			}
			$row_num++;
			continue;
		}

		foreach ($bahan as $b) {
			$baris_count++;
			$cells = array(
				(string) $no_produk,
				persediaan_gen_proses_pembelian_format_tgl(isset($g->tgl_transaksi) ? $g->tgl_transaksi : ''),
				isset($g->spop) ? (string) $g->spop : '',
				isset($g->nama_produk) ? (string) $g->nama_produk : '',
				persediaan_gen_proses_pembelian_format_jumlah(isset($g->jumlah_produksi) ? $g->jumlah_produksi : 0),
				persediaan_gen_proses_pembelian_format_nominal(isset($g->harga_produk) ? $g->harga_produk : 0),
				persediaan_gen_proses_pembelian_format_nominal(isset($g->nominal_produk) ? $g->nominal_produk : 0),
				(string) (int) (isset($b->no) ? $b->no : 0),
				isset($b->kode_barang_bahan) ? (string) $b->kode_barang_bahan : '',
				isset($b->nama_bahan) ? (string) $b->nama_bahan : '',
				persediaan_gen_proses_pembelian_format_jumlah(isset($b->jumlah_bahan) ? $b->jumlah_bahan : 0),
				isset($b->satuan) ? (string) $b->satuan : '',
				persediaan_gen_proses_pembelian_format_nominal(isset($b->harga_satuan_bahan) ? $b->harga_satuan_bahan : 0),
				persediaan_gen_proses_pembelian_format_nominal(isset($b->total_harga_bahan) ? $b->total_harga_bahan : 0),
				persediaan_gen_proses_pembelian_format_nominal(isset($b->harga_margin) ? $b->harga_margin : 0),
				isset($b->harga_sumber) ? (string) $b->harga_sumber : '',
			);
			$row_style = ($row_num % 2 === 0) ? 18 : 7;
			$money_cols = array($col_qty_produk, $col_nominal_produk, $col_jumlah_bahan, $col_total_bahan, $col_margin);
			foreach ($cells as $col => $cell) {
				$style = in_array($col, $money_cols, true) ? 8 : $row_style;
				xlsWriteCellStyle($row_num, $col, strip_tags((string) $cell), $style);
			}
			$row_num++;
		}

		// Baris total per produk
		$cells_total = array(
			'',
			'',
			'',
			'TOTAL — ' . (isset($g->nama_produk) ? (string) $g->nama_produk : ''),
			'',
			'',
			'',
			'',
			'',
			'',
			persediaan_gen_proses_pembelian_format_jumlah(isset($g->total_jumlah_bahan) ? $g->total_jumlah_bahan : 0),
			'',
			'',
			persediaan_gen_proses_pembelian_format_nominal(isset($g->total_harga_bahan) ? $g->total_harga_bahan : 0),
			persediaan_gen_proses_pembelian_format_nominal(isset($g->total_margin) ? $g->total_margin : 0),
			(string) ((int) (isset($g->count_bahan) ? $g->count_bahan : 0)) . ' bahan',
		);
		foreach ($cells_total as $col => $cell) {
			xlsWriteCellStyle($row_num, $col, strip_tags((string) $cell), 24);
		}
		$row_num++;
	}

	xlsWriteLabel(2, 0, 'Total baris detail: ' . $baris_count . ' | Produk: ' . $no_produk);

	foreach ($headers as $col => $label) {
		if ($col === 0) {
			xlsWriteCellStyle($row_num, $col, 'TOTAL SEMUA', 24);
		} elseif ($col === $col_qty_produk) {
			xlsWriteCellStyle($row_num, $col, persediaan_gen_proses_pembelian_format_jumlah($sum_qty), 25);
		} elseif ($col === $col_nominal_produk) {
			xlsWriteCellStyle($row_num, $col, persediaan_gen_proses_pembelian_format_nominal($sum_nominal_produk), 25);
		} elseif ($col === $col_jumlah_bahan) {
			xlsWriteCellStyle($row_num, $col, persediaan_gen_proses_pembelian_format_jumlah($sum_jumlah_bahan), 25);
		} elseif ($col === $col_total_bahan) {
			xlsWriteCellStyle($row_num, $col, persediaan_gen_proses_pembelian_format_nominal($sum_total_bahan), 25);
		} elseif ($col === $col_margin) {
			xlsWriteCellStyle($row_num, $col, persediaan_gen_proses_pembelian_format_nominal($sum_margin), 25);
		} else {
			xlsWriteCellStyle($row_num, $col, '', 24);
		}
	}

	xlsEOF();
}

function persediaan_gen_proses_excel_write_produksi_margin_rows($rows, $title, $bulan_label)
{
	$headers = array(
		'No', 'Kode Bahan', 'Nama Bahan', 'UUID Persediaan', 'Satuan',
		'Jumlah Digunakan', 'Harga Satuan Asli', 'Total Pengurangan',
		'Stok Persediaan (total_10)', 'Produk Terkait', 'Match',
	);
	$col_jumlah = 5;
	$col_pengurangan = 7;
	$sum_jumlah = 0.0;
	$sum_pengurangan = 0.0;

	xlsBOF();
	xlsWriteLabelBold14(0, 0, $title . ' — ' . $bulan_label);
	xlsWriteLabel(1, 0, 'Dicetak: ' . date('d/m/Y H:i:s') . ' | Total baris: ' . count($rows) . ' | Harga satuan asli dari persediaan.hpp');
	persediaan_excel_write_headers(3, $headers);

	$row_num = 4;
	$no = 0;
	foreach ($rows as $row) {
		$no++;
		$jumlah = isset($row->jumlah_digunakan) ? (float) $row->jumlah_digunakan : 0.0;
		$pengurangan = isset($row->total_pengurangan) ? (float) $row->total_pengurangan : 0.0;
		$sum_jumlah += $jumlah;
		$sum_pengurangan += $pengurangan;

		$cells = array(
			(string) $no,
			isset($row->kode_barang_bahan) ? (string) $row->kode_barang_bahan : '',
			isset($row->nama_bahan) ? (string) $row->nama_bahan : '',
			isset($row->uuid_persediaan_bahan) ? (string) $row->uuid_persediaan_bahan : '',
			isset($row->satuan) ? (string) $row->satuan : '',
			persediaan_gen_proses_pembelian_format_jumlah($jumlah),
			persediaan_gen_proses_pembelian_format_nominal(isset($row->harga_satuan_asli) ? $row->harga_satuan_asli : 0),
			persediaan_gen_proses_pembelian_format_nominal($pengurangan),
			persediaan_gen_proses_pembelian_format_jumlah(isset($row->total_10_persediaan) ? $row->total_10_persediaan : 0),
			isset($row->produk_terkait) ? (string) $row->produk_terkait : '',
			!empty($row->match_persediaan) ? 'Ada' : 'Tidak',
		);

		$row_style = ($row_num % 2 === 0) ? 18 : 7;
		foreach ($cells as $col => $cell) {
			$style = ($col === $col_jumlah || $col === $col_pengurangan) ? 8 : $row_style;
			xlsWriteCellStyle($row_num, $col, strip_tags((string) $cell), $style);
		}
		$row_num++;
	}

	foreach ($headers as $col => $label) {
		if ($col === 0) {
			xlsWriteCellStyle($row_num, $col, 'TOTAL', 24);
		} elseif ($col === $col_jumlah) {
			xlsWriteCellStyle($row_num, $col, persediaan_gen_proses_pembelian_format_jumlah($sum_jumlah), 25);
		} elseif ($col === $col_pengurangan) {
			xlsWriteCellStyle($row_num, $col, persediaan_gen_proses_pembelian_format_nominal($sum_pengurangan), 25);
		} else {
			xlsWriteCellStyle($row_num, $col, '', 24);
		}
	}

	xlsEOF();
}

function persediaan_gen_proses_excel_write_penjualan_rows($rows, $title, $bulan_label)
{
	$headers = array(
		'No', 'Status', 'ID', 'Tgl Jual', 'UUID Persediaan', 'Nama Barang', 'Satuan', 'Unit',
		'Harga Satuan', 'Jumlah', 'Total Harga', 'ID Persediaan', 'Match Via', 'Keterangan',
	);
	$col_jumlah = 9;
	$col_total_harga = 10;
	$sum_jumlah = 0.0;
	$sum_total_harga = 0.0;

	xlsBOF();
	xlsWriteLabelBold14(0, 0, $title . ' — ' . $bulan_label);
	xlsWriteLabel(1, 0, 'Dicetak: ' . date('d/m/Y H:i:s') . ' | Total baris: ' . count($rows));
	persediaan_excel_write_headers(3, $headers);

	$row_num = 4;
	$no = 0;
	foreach ($rows as $row) {
		$no++;
		$jumlah = isset($row->jumlah) ? $row->jumlah : 0;
		$harga = isset($row->harga_satuan) ? $row->harga_satuan : 0;
		$total_harga = (float) persediaan_parse_angka($jumlah) * (float) persediaan_parse_angka($harga);
		$sum_jumlah += (float) persediaan_parse_angka($jumlah);
		$sum_total_harga += $total_harga;

		$cells = array(
			(string) $no,
			isset($row->status_label) ? (string) $row->status_label : '',
			(string) (int) (isset($row->id) ? $row->id : 0),
			persediaan_gen_proses_pembelian_format_tgl(isset($row->tgl_jual) ? $row->tgl_jual : ''),
			isset($row->uuid_persediaan) ? (string) $row->uuid_persediaan : '',
			isset($row->nama_barang) ? (string) $row->nama_barang : '',
			isset($row->satuan) ? (string) $row->satuan : '',
			isset($row->unit) ? (string) $row->unit : '',
			persediaan_gen_proses_pembelian_format_nominal($harga),
			persediaan_gen_proses_pembelian_format_jumlah($jumlah),
			persediaan_gen_proses_pembelian_format_nominal($total_harga),
			isset($row->id_persediaan_match) ? (string) (int) $row->id_persediaan_match : '—',
			isset($row->match_via) ? (string) $row->match_via : '',
			isset($row->status_keterangan) ? (string) $row->status_keterangan : '',
		);

		$row_style = ($row_num % 2 === 0) ? 18 : 7;
		foreach ($cells as $col => $cell) {
			$style = ($col === $col_jumlah || $col === $col_total_harga) ? 8 : $row_style;
			xlsWriteCellStyle($row_num, $col, strip_tags((string) $cell), $style);
		}
		$row_num++;
	}

	foreach ($headers as $col => $label) {
		if ($col === 0) {
			xlsWriteCellStyle($row_num, $col, 'TOTAL', 24);
		} elseif ($col === $col_jumlah) {
			xlsWriteCellStyle($row_num, $col, persediaan_gen_proses_pembelian_format_jumlah($sum_jumlah), 25);
		} elseif ($col === $col_total_harga) {
			xlsWriteCellStyle($row_num, $col, persediaan_gen_proses_pembelian_format_nominal($sum_total_harga), 25);
		} else {
			xlsWriteCellStyle($row_num, $col, '', 24);
		}
	}

	xlsEOF();
}

function persediaan_gen_proses_export_excel_output($CI, $bulan_target, $jenis)
{
	$CI->load->helper(array('exportexcel', 'pembelian_persediaan', 'persediaan_display'));

	$defs = persediaan_gen_proses_excel_jenis_definitions();
	if (!isset($defs[$jenis])) {
		xlsBOF();
		xlsWriteLabel(0, 0, 'Jenis export tidak valid.');
		xlsEOF();
		return;
	}

	$def = $defs[$jenis];
	$rows = persediaan_gen_proses_excel_load_rows($CI, $bulan_target, $jenis, $def);
	$bulan_label = persediaan_gen_proses_excel_bulan_label($bulan_target);
	$title = isset($def['title']) ? $def['title'] : $jenis;
	$type = isset($def['type']) ? $def['type'] : '';
	$ts = strtotime($bulan_target . '-01');

	if ($type === 'persediaan' || $type === 'persediaan_copy') {
		$kategori = isset($def['kategori']) ? $def['kategori'] : 'barang';
		$bulan_export = $bulan_target;
		$bulan_title = $bulan_label;
		if (strpos($jenis, '_sumber_') !== false) {
			$bulan_export = date('Y-m', strtotime('-1 month', $ts));
			$bulan_title = persediaan_gen_proses_excel_bulan_label($bulan_export);
		}
		persediaan_export_excel_tab_data_output($CI, $bulan_export, $rows, $kategori, false, $title . ' — ' . $bulan_title);
		return;
	}

	if ($type === 'pembelian') {
		persediaan_gen_proses_excel_write_pembelian_rows(
			$rows,
			isset($def['tab_mode']) ? $def['tab_mode'] : 'barang',
			$title,
			$bulan_label
		);
		return;
	}

	if ($type === 'produksi') {
		$mode = isset($def['produksi_mode']) ? $def['produksi_mode'] : 'produk';
		if ($mode === 'bahan') {
			persediaan_gen_proses_excel_write_produksi_bahan_rows($rows, $title, $bulan_label);
			return;
		}
		if ($mode === 'bahan_real') {
			persediaan_gen_proses_excel_write_produksi_bahan_real_rows($rows, $title, $bulan_label);
			return;
		}
		if ($mode === 'riil') {
			persediaan_gen_proses_excel_write_produksi_riil_rows($rows, $title, $bulan_label);
			return;
		}
		if ($mode === 'margin') {
			persediaan_gen_proses_excel_write_produksi_margin_rows($rows, $title, $bulan_label);
			return;
		}
		persediaan_gen_proses_excel_write_produksi_rows($CI, $rows, $title, $bulan_label);
		return;
	}

	if ($type === 'penjualan') {
		persediaan_gen_proses_excel_write_penjualan_rows($rows, $title, $bulan_label);
		return;
	}

	xlsBOF();
	xlsWriteLabel(0, 0, 'Tipe export tidak dikenali.');
	xlsEOF();
}
