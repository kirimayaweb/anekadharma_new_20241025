<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Sumber data barang/jasa untuk modul pembelian: tabel persediaan
 * difilter rentang tanggal (session list pembelian atau tanggal PO di form create).
 */

function pembelian_parse_tanggal_po($tanggal_po)
{
	$tanggal_po = trim((string) $tanggal_po);
	if ($tanggal_po === '') {
		return false;
	}

	$parts = preg_split('/[-\/\.]/', $tanggal_po);
	if (count($parts) === 3) {
		$hari = (int) $parts[0];
		$bulan = (int) $parts[1];
		$tahun = (int) $parts[2];
		if ($tahun < 100) {
			$tahun += 2000;
		}
		if (checkdate($bulan, $hari, $tahun)) {
			return mktime(0, 0, 0, $bulan, $hari, $tahun);
		}
	}

	$ts = strtotime(str_replace('/', '-', $tanggal_po));
	return ($ts !== false) ? $ts : false;
}

function pembelian_get_filter_tanggal($CI, $tanggal_po = null)
{
	if ($tanggal_po !== null && trim((string) $tanggal_po) !== '') {
		$ts = pembelian_parse_tanggal_po($tanggal_po);
		if ($ts !== false) {
			return array(
				'awal' => date('Y-m-01', $ts),
				'akhir' => date('Y-m-t', $ts),
				'awal_bulan' => date('Y-m-01', $ts),
				'bulan_label' => date('m/Y', $ts),
			);
		}
	}

	$awal = $CI->session->userdata('filter_tbl_pembelian_date_awal');
	$akhir = $CI->session->userdata('filter_tbl_pembelian_date_akhir');

	if (empty($awal)) {
		$awal = $CI->session->userdata('filter_tbl_pembelian_jasa_date_awal');
		$akhir = $CI->session->userdata('filter_tbl_pembelian_jasa_date_akhir');
	}

	if (empty($awal)) {
		$awal = date('Y-m-01 00:00:00');
	}
	if (empty($akhir)) {
		$akhir = date('Y-m-t 23:59:59');
	}

	$ts_awal = strtotime($awal);

	return array(
		'awal' => date('Y-m-d', $ts_awal),
		'akhir' => date('Y-m-d', strtotime($akhir)),
		'awal_bulan' => date('Y-m-01', $ts_awal),
		'bulan_label' => date('m/Y', $ts_awal),
	);
}

/**
 * Sinkronkan filter bulan persediaan dari tanggal PO (datepicker create / modal).
 */
function pembelian_sync_filter_bulan_from_tanggal_po($CI, $tanggal_po)
{
	$tgl = pembelian_get_filter_tanggal($CI, $tanggal_po);
	$awal = $tgl['awal'] . ' 00:00:00';
	$akhir = $tgl['akhir'] . ' 23:59:59';
	$CI->session->set_userdata('filter_tbl_pembelian_date_awal', $awal);
	$CI->session->set_userdata('filter_tbl_pembelian_date_akhir', $akhir);
	$CI->session->set_userdata('filter_tbl_pembelian_jasa_date_awal', $awal);
	$CI->session->set_userdata('filter_tbl_pembelian_jasa_date_akhir', $akhir);
	$CI->session->set_userdata('filter_pembelian_create_tanggal_po', trim((string) $tanggal_po));
	return $tgl;
}

function pembelian_get_barang_list_rows($CI)
{
	$tgl = pembelian_get_filter_tanggal($CI);

	$sql = "SELECT
			COALESCE(NULLIF(`uuid_barang`, ''), `uuid_persediaan`) AS uuid_barang,
			`kode` AS kode_barang,
			`namabarang` AS nama_barang,
			`satuan`,
			`hpp` AS harga_satuan
		FROM `persediaan`
		WHERE TRIM(`namabarang`) <> ''
		AND DATE(`tanggal_beli`) >= ?
		AND DATE(`tanggal_beli`) <= ?
		GROUP BY COALESCE(NULLIF(`uuid_barang`, ''), `uuid_persediaan`), `namabarang`, `kode`, `satuan`, `hpp`
		ORDER BY `namabarang` ASC";

	return $CI->db->query($sql, array($tgl['awal'], $tgl['akhir']))->result();
}

function pembelian_get_barang_by_uuid($CI, $uuid_barang)
{
	$uuid_barang = trim((string) $uuid_barang);
	if ($uuid_barang === '') {
		return null;
	}

	$tgl = pembelian_get_filter_tanggal($CI);

	$sql = "SELECT
			COALESCE(NULLIF(`uuid_barang`, ''), `uuid_persediaan`) AS uuid_barang,
			`kode` AS kode_barang,
			`namabarang` AS nama_barang,
			`satuan`,
			`hpp` AS harga_satuan
		FROM `persediaan`
		WHERE (`uuid_barang` = ? OR `uuid_persediaan` = ?)
		AND DATE(`tanggal_beli`) >= ?
		AND DATE(`tanggal_beli`) <= ?
		ORDER BY `id` DESC
		LIMIT 1";

	return $CI->db->query($sql, array($uuid_barang, $uuid_barang, $tgl['awal'], $tgl['akhir']))->row();
}

function pembelian_normalize_nama_barang($nama_barang)
{
	$nama_barang = str_replace(array("\r", "\n", "\t"), ' ', (string) $nama_barang);
	$nama_barang = preg_replace('/\s+/u', ' ', trim($nama_barang));
	return $nama_barang;
}

function pembelian_sql_nama_barang_normalized()
{
	return "LOWER(TRIM(REPLACE(REPLACE(REPLACE(`namabarang`, CHAR(13), ' '), CHAR(10), ' '), '  ', ' ')))";
}

/**
 * Cari record persediaan untuk referensi modal (exact dulu, lalu mirip LIKE).
 */
function pembelian_find_barang_referensi_persediaan($CI, $nama_barang)
{
	$nama_norm = pembelian_normalize_nama_barang($nama_barang);
	if ($nama_norm === '') {
		return array();
	}

	$nama_lower = function_exists('mb_strtolower')
		? mb_strtolower($nama_norm, 'UTF-8')
		: strtolower($nama_norm);

	$nama_expr = pembelian_sql_nama_barang_normalized();

	$sql = "SELECT
			`id`,
			COALESCE(NULLIF(`uuid_barang`, ''), `uuid_persediaan`) AS uuid_barang,
			`kode` AS kode_barang,
			`namabarang` AS nama_barang,
			`satuan`,
			`hpp` AS harga_satuan,
			`tanggal_beli`,
			DATE_FORMAT(`tanggal_beli`, '%m/%Y') AS bulan_label
		FROM `persediaan`
		WHERE TRIM(`namabarang`) <> ''
		AND {$nama_expr} = ?
		ORDER BY `tanggal_beli` DESC, `id` DESC";

	$rows = $CI->db->query($sql, array($nama_lower))->result();
	if (!empty($rows)) {
		return $rows;
	}

	if (strlen($nama_lower) < 2) {
		return array();
	}

	$like = '%' . $CI->db->escape_like_str($nama_lower) . '%';
	$sql_like = "SELECT
			`id`,
			COALESCE(NULLIF(`uuid_barang`, ''), `uuid_persediaan`) AS uuid_barang,
			`kode` AS kode_barang,
			`namabarang` AS nama_barang,
			`satuan`,
			`hpp` AS harga_satuan,
			`tanggal_beli`,
			DATE_FORMAT(`tanggal_beli`, '%m/%Y') AS bulan_label
		FROM `persediaan`
		WHERE TRIM(`namabarang`) <> ''
		AND {$nama_expr} LIKE ? ESCAPE '!'
		ORDER BY `tanggal_beli` DESC, `id` DESC
		LIMIT 200";

	return $CI->db->query($sql_like, array($like))->result();
}

function pembelian_find_barang_by_nama($CI, $nama_barang, $tanggal_po = null)
{
	$nama_norm = pembelian_normalize_nama_barang($nama_barang);
	if ($nama_norm === '') {
		return null;
	}

	$tgl = pembelian_get_filter_tanggal($CI, $tanggal_po);
	$nama_lower = function_exists('mb_strtolower')
		? mb_strtolower($nama_norm, 'UTF-8')
		: strtolower($nama_norm);
	$nama_expr = pembelian_sql_nama_barang_normalized();

	$sql = "SELECT
			COALESCE(NULLIF(`uuid_barang`, ''), `uuid_persediaan`) AS uuid_barang,
			`kode` AS kode_barang,
			`namabarang` AS nama_barang,
			`satuan`,
			`hpp` AS harga_satuan
		FROM `persediaan`
		WHERE {$nama_expr} = ?
		AND TRIM(`namabarang`) <> ''
		AND `tanggal_beli` IS NOT NULL
		AND `tanggal_beli` <> '0000-00-00'
		AND `tanggal_beli` <> '0000-00-00 00:00:00'
		AND DATE(`tanggal_beli`) >= ?
		AND DATE(`tanggal_beli`) <= ?
		ORDER BY `id` DESC
		LIMIT 1";

	return $CI->db->query($sql, array($nama_lower, $tgl['awal'], $tgl['akhir']))->row();
}

/**
 * Semua record persediaan dengan nama barang sama / mirip (semua bulan).
 */
function pembelian_find_barang_by_nama_semua_bulan($CI, $nama_barang)
{
	return pembelian_find_barang_referensi_persediaan($CI, $nama_barang);
}

function pembelian_get_persediaan_record_by_id($CI, $persediaan_id)
{
	$persediaan_id = (int) $persediaan_id;
	if ($persediaan_id <= 0) {
		return null;
	}

	$sql = "SELECT
			`id`,
			COALESCE(NULLIF(`uuid_barang`, ''), `uuid_persediaan`) AS uuid_barang,
			`kode` AS kode_barang,
			`namabarang` AS nama_barang,
			`satuan`,
			`hpp` AS harga_satuan,
			`tanggal_beli`,
			DATE_FORMAT(`tanggal_beli`, '%m/%Y') AS bulan_label
		FROM `persediaan`
		WHERE `id` = ?
		LIMIT 1";

	return $CI->db->query($sql, array($persediaan_id))->row();
}

function pembelian_kode_barang_opsional($nama_barang, $kode_input)
{
	$kode = strtoupper(trim((string) $kode_input));
	if ($kode !== '') {
		return $kode;
	}
	return '';
}

function pembelian_persediaan_punya_kategori()
{
	return false;
}

/**
 * Tahun kalender dari Tgl PO (datepicker).
 */
function pembelian_get_tahun_from_tanggal_po($tanggal_po)
{
	$ts = pembelian_parse_tanggal_po($tanggal_po);
	if ($ts === false) {
		return (int) date('Y');
	}
	return (int) date('Y', $ts);
}

/**
 * Cek apakah nomor SPOP sudah ada di tabel persediaan pada tahun Tgl PO.
 */
function pembelian_cek_spop_di_persediaan_tahun($CI, $spop, $tanggal_po)
{
	$spop = trim((string) $spop);
	$tahun = pembelian_get_tahun_from_tanggal_po($tanggal_po);

	if ($spop === '') {
		return array(
			'exists' => false,
			'tahun' => $tahun,
			'tahun_label' => (string) $tahun,
		);
	}

	$sql = "SELECT COUNT(*) AS jml
		FROM `persediaan`
		WHERE TRIM(`spop`) <> ''
		AND TRIM(`spop`) = ?
		AND YEAR(`tanggal_beli`) = ?
		LIMIT 1";
	$row = $CI->db->query($sql, array($spop, $tahun))->row();
	$jml = ($row && isset($row->jml)) ? (int) $row->jml : 0;

	return array(
		'exists' => ($jml > 0),
		'tahun' => $tahun,
		'tahun_label' => (string) $tahun,
	);
}

/**
 * Filter bulan persediaan untuk modul penjualan (berdasarkan Tgl Jual).
 */
function penjualan_get_filter_tgl_jual($CI, $tgl_jual = null)
{
	if ($tgl_jual !== null && trim((string) $tgl_jual) !== '') {
		return pembelian_get_filter_tanggal($CI, $tgl_jual);
	}

	$awal = $CI->session->userdata('filter_tbl_penjualan_date_awal');
	$akhir = $CI->session->userdata('filter_tbl_penjualan_date_akhir');

	if (!empty($awal) && !empty($akhir)) {
		$ts_awal = strtotime($awal);
		return array(
			'awal' => date('Y-m-d', $ts_awal),
			'akhir' => date('Y-m-d', strtotime($akhir)),
			'awal_bulan' => date('Y-m-01', $ts_awal),
			'bulan_label' => date('m/Y', $ts_awal),
		);
	}

	return pembelian_get_filter_tanggal($CI, null);
}

function penjualan_sync_filter_bulan_from_tgl_jual($CI, $tgl_jual)
{
	$tgl = penjualan_get_filter_tgl_jual($CI, $tgl_jual);
	$awal = $tgl['awal'] . ' 00:00:00';
	$akhir = $tgl['akhir'] . ' 23:59:59';
	$CI->session->set_userdata('filter_tbl_penjualan_date_awal', $awal);
	$CI->session->set_userdata('filter_tbl_penjualan_date_akhir', $akhir);
	$CI->session->set_userdata('filter_penjualan_create_tgl_jual', trim((string) $tgl_jual));
	return $tgl;
}

/**
 * Ekspresi SQL tanggal efektif persediaan (tanggal_beli atau kolom tanggal varchar).
 */
function penjualan_sql_tanggal_persediaan_expr($alias = 'persediaan')
{
	$a = $alias;
	return "COALESCE(
		NULLIF(DATE({$a}.tanggal_beli), '0000-00-00'),
		STR_TO_DATE({$a}.tanggal, '%d/%m/%Y'),
		STR_TO_DATE({$a}.tanggal, '%e/%c/%Y'),
		STR_TO_DATE({$a}.tanggal, '%d-%m-%Y'),
		STR_TO_DATE({$a}.tanggal, '%e-%c-%Y'),
		STR_TO_DATE({$a}.tanggal, '%Y-%m-%d'),
		STR_TO_DATE({$a}.tanggal, '%d/%m/%y'),
		STR_TO_DATE({$a}.tanggal, '%e/%c/%y')
	)";
}

/**
 * Format Tgl Jual untuk tampilan / form (d-m-Y).
 */
function penjualan_format_tgl_jual_tampil($tgl_jual)
{
	$ts = pembelian_parse_tanggal_po($tgl_jual);
	if ($ts === false) {
		$ts = strtotime(str_replace('/', '-', trim((string) $tgl_jual)));
	}
	return ($ts !== false) ? date('d-m-Y', $ts) : date('d-m-Y');
}

function penjualan_is_kategori_jasa($kategori)
{
	return strtolower(trim((string) $kategori)) === 'jasa';
}

/**
 * Kondisi SQL: hanya barang (bukan kategori jasa).
 */
function penjualan_sql_bukan_kategori_jasa($CI, $alias = 'persediaan')
{
	if (!$CI->db->field_exists('kategori', 'persediaan')) {
		return '1=1';
	}
	$a = $alias;
	return "LOWER(TRIM(COALESCE({$a}.kategori, ''))) <> 'jasa'";
}

/**
 * Ekspresi uuid barang efektif (uuid_barang → uuid_persediaan → id).
 */
function penjualan_sql_uuid_barang_expr($alias = 'persediaan')
{
	$a = $alias;
	return "COALESCE(
		NULLIF(TRIM({$a}.uuid_barang), ''),
		NULLIF(TRIM({$a}.uuid_persediaan), ''),
		CONCAT('pid_', {$a}.id)
	)";
}

/**
 * Filter bulan persediaan: tanggal_beli ATAU kolom tanggal (varchar). Gunakan 4 binding: awal, akhir, awal, akhir.
 */
function penjualan_sql_filter_bulan_persediaan_where($alias = 'persediaan')
{
	$a = $alias;
	$tgl_expr = penjualan_sql_tanggal_persediaan_expr($a);
	$invalid_tgl = "'0000-00-00', '0000-00-00 00:00:00'";
	return "(
		({$a}.tanggal_beli IS NOT NULL AND {$a}.tanggal_beli NOT IN ({$invalid_tgl})
			AND DATE({$a}.tanggal_beli) >= ? AND DATE({$a}.tanggal_beli) <= ?)
		OR ({$tgl_expr} IS NOT NULL AND {$tgl_expr} >= ? AND {$tgl_expr} <= ?)
		OR (DATE_FORMAT({$tgl_expr}, '%Y-%m') = ?)
	)";
}

/**
 * Kolom standar unit di persediaan (referensi / alias).
 */
function penjualan_persediaan_kolom_unit_allowed()
{
	return array(
		'cetak',
		'grafikita',
		'sekret',
		'medis',
		'ppbmp',
		'dinas_umum',
		'atk_rsud',
		'siiplah_bosda',
		'ppbmp_kbs',
		'kbs',
		'sembako',
		'fc_psamya',
		'fc_gose',
		'fc_manding',
		'tuj',
	);
}

/**
 * Field persediaan yang bukan kolom unit (di luar zona sebelum total_10).
 */
function penjualan_persediaan_kolom_non_unit()
{
	return array(
		'id',
		'uuid_persediaan',
		'uuid_spop',
		'uuid_gudang',
		'nama_gudang',
		'uuid_barang',
		'kode_barang',
		'tanggal_beli',
		'tanggal',
		'kode',
		'namabarang',
		'satuan',
		'hpp',
		'sa',
		'spop',
		'beli',
		'tuj',
		'tgl_keluar',
		'kategori',
		'penjualan',
		'pecah_satuan',
		'bahan_produksi',
		'total_10',
		'nilai_persediaan',
	);
}

function penjualan_normalize_unit_key($text)
{
	$key = strtolower(trim((string) $text));
	$key = preg_replace('/[^a-z0-9]+/', '_', $key);
	return trim($key, '_');
}

function penjualan_is_valid_persediaan_column_name($name)
{
	return (bool) preg_match('/^[a-z][a-z0-9_]{0,63}$/', (string) $name);
}

/**
 * Nama kolom persediaan dari baris sys_unit (prioritas kode_unit).
 */
function penjualan_kolom_dari_sys_unit_row($row_unit)
{
	if (empty($row_unit)) {
		return null;
	}

	$CI = get_instance();
	$CI->load->helper('persediaan_display');

	foreach (array('kode_unit', 'nama_unit', 'keterangan') as $prop) {
		if (!isset($row_unit->{$prop})) {
			continue;
		}
		$kolom = persediaan_resolve_unit_column_from_text($CI, $row_unit->{$prop});
		if ($kolom !== null) {
			return $kolom;
		}
		$key = penjualan_normalize_unit_key($row_unit->{$prop});
		if ($key !== '' && penjualan_is_valid_persediaan_column_name($key)) {
			return persediaan_resolve_db_field_name($CI, $key);
		}
	}

	return null;
}

/**
 * Semua kolom unit yang ada di tabel persediaan (sebelum total_10).
 */
function penjualan_persediaan_kolom_unit_existing($CI)
{
	if (!$CI->db->table_exists('persediaan')) {
		return array();
	}

	$CI->load->helper('persediaan_display');
	return persediaan_list_unit_columns($CI);
}

/**
 * Kolom terakhir sebelum total_10 (untuk posisi ADD COLUMN ... AFTER di MariaDB/MySQL).
 */
function penjualan_get_kolom_referensi_sebelum_total_10($CI)
{
	if (!$CI->db->table_exists('persediaan')) {
		return null;
	}

	$fields = $CI->db->list_fields('persediaan');
	$prev = null;
	foreach ($fields as $f) {
		if ($f === 'total_10') {
			return $prev;
		}
		$prev = $f;
	}

	$existing = penjualan_persediaan_kolom_unit_existing($CI);
	if (!empty($existing)) {
		return end($existing);
	}

	foreach (array('fc_psamya', 'fc_manding', 'fc_gose', 'sembako', 'tuj') as $fallback) {
		if ($CI->db->field_exists($fallback, 'persediaan')) {
			return $fallback;
		}
	}

	return null;
}

/**
 * Tambah kolom unit di tabel persediaan jika belum ada (dari sys_unit.kode_unit), sebelum total_10.
 */
function penjualan_ensure_persediaan_kolom_unit($CI, $uuid_unit)
{
	$uuid_unit = trim((string) $uuid_unit);
	if ($uuid_unit === '') {
		return array('ok' => false, 'message' => 'Unit belum dipilih.');
	}

	if (!$CI->db->table_exists('persediaan')) {
		return array('ok' => false, 'message' => 'Tabel persediaan tidak ditemukan.');
	}

	$row_unit = $CI->db->where('uuid_unit', $uuid_unit)->limit(1)->get('sys_unit')->row();
	if (empty($row_unit)) {
		return array('ok' => false, 'message' => 'Data unit tidak ditemukan di sys_unit.');
	}

	$kolom = penjualan_kolom_dari_sys_unit_row($row_unit);
	if ($kolom === null) {
		return array(
			'ok' => false,
			'message' => 'Kode unit tidak valid untuk nama kolom persediaan (isi kode_unit di master unit).',
		);
	}

	if (in_array($kolom, penjualan_persediaan_kolom_non_unit(), true)) {
		return array(
			'ok' => false,
			'message' => 'Nama kolom "' . $kolom . '" bentrok dengan field sistem persediaan.',
		);
	}

	if ($CI->db->field_exists($kolom, 'persediaan')) {
		return array(
			'ok' => true,
			'kolom' => $kolom,
			'created' => false,
			'message' => 'Kolom unit sudah ada.',
		);
	}

	$kolom_sql = $CI->db->escape_identifiers($kolom);
	$sql = 'ALTER TABLE ' . $CI->db->escape_identifiers('persediaan')
		. ' ADD COLUMN ' . $kolom_sql . " VARCHAR(50) NULL DEFAULT '0'";

	// MariaDB/MySQL: gunakan AFTER (bukan BEFORE) — kolom baru ditempatkan sebelum total_10
	$after_col = penjualan_get_kolom_referensi_sebelum_total_10($CI);
	if ($after_col !== null && $after_col !== '' && $after_col !== $kolom) {
		$sql .= ' AFTER ' . $CI->db->escape_identifiers($after_col);
	}

	if (!$CI->db->query($sql)) {
		$err = $CI->db->error();
		$pesan = isset($err['message']) ? trim((string) $err['message']) : 'Gagal menambah kolom unit di persediaan.';
		return array('ok' => false, 'message' => $pesan);
	}

	return array(
		'ok' => true,
		'kolom' => $kolom,
		'created' => true,
		'message' => 'Kolom unit "' . $kolom . '" ditambahkan ke tabel persediaan.',
	);
}

/**
 * Map uuid_unit (sys_unit) ke kolom persediaan (sekret, cetak, ...).
 */
function penjualan_resolve_kolom_persediaan_unit($CI, $uuid_unit, $ensure_column = true)
{
	$uuid_unit = trim((string) $uuid_unit);
	if ($uuid_unit === '') {
		return null;
	}

	if ($ensure_column) {
		penjualan_ensure_persediaan_kolom_unit($CI, $uuid_unit);
	}

	$row = $CI->db->where('uuid_unit', $uuid_unit)->limit(1)->get('sys_unit')->row();
	if (empty($row)) {
		return null;
	}

	$kolom_utama = penjualan_kolom_dari_sys_unit_row($row);
	if ($kolom_utama !== null && $CI->db->field_exists($kolom_utama, 'persediaan')) {
		return $kolom_utama;
	}

	$allowed = penjualan_persediaan_kolom_unit_existing($CI);
	if (empty($allowed)) {
		return null;
	}

	$candidates = array();
	foreach (array($row->kode_unit, $row->nama_unit, $row->keterangan) as $src) {
		$key = penjualan_normalize_unit_key($src);
		if ($key !== '') {
			$candidates[] = $key;
		}
	}

	$aliases = array(
		'cetak_grafikita' => 'grafikita',
		'grafik' => 'grafikita',
		'cetak_grafik' => 'grafikita',
		'dinas' => 'dinas_umum',
		'umum' => 'dinas_umum',
		'dinas_umum' => 'dinas_umum',
		'rsud' => 'atk_rsud',
		'atk' => 'atk_rsud',
		'siiplah' => 'siiplah_bosda',
		'bosda' => 'siiplah_bosda',
		'ppbmp_kbs' => 'ppbmp_kbs',
		'kbs_ppbmp' => 'ppbmp_kbs',
		'fc_psamya' => 'fc_psamya',
		'psamya' => 'fc_psamya',
		'fc_gose' => 'fc_gose',
		'gose' => 'fc_gose',
		'fc_manding' => 'fc_manding',
		'manding' => 'fc_manding',
	);

	foreach ($candidates as $key) {
		foreach ($allowed as $kolom) {
			if (penjualan_normalize_unit_key($kolom) === $key) {
				return $kolom;
			}
		}
		if (isset($aliases[$key])) {
			foreach ($allowed as $kolom) {
				if (penjualan_normalize_unit_key($kolom) === penjualan_normalize_unit_key($aliases[$key])) {
					return $kolom;
				}
			}
		}
	}

	usort($allowed, function ($a, $b) {
		return strlen($b) - strlen($a);
	});
	foreach ($allowed as $kolom) {
		foreach ($candidates as $key) {
			if ($key === '' || strlen($kolom) < 3) {
				continue;
			}
			$col_key = penjualan_normalize_unit_key($kolom);
			if (strpos($key, $col_key) !== false || strpos($col_key, $key) !== false) {
				return $kolom;
			}
		}
	}

	return null;
}

function penjualan_get_label_kolom_unit($kolom)
{
	$labels = array(
		'cetak' => 'Cetak',
		'grafikita' => 'Cetak Grafikita',
		'sekret' => 'Sekret',
		'medis' => 'Medis',
		'ppbmp' => 'PPBMP',
		'dinas_umum' => 'Dinas & Umum',
		'atk_rsud' => 'ATK RSUD',
		'siiplah_bosda' => 'Siiplah & Bosda',
		'ppbmp_kbs' => 'PPBMP KBS',
		'kbs' => 'KBS',
		'sembako' => 'Sembako',
		'fc_psamya' => 'FC Psamya',
		'fc_gose' => 'FC Gose',
		'fc_manding' => 'FC Manding',
		'tuj' => 'TUJ',
	);
	return isset($labels[$kolom]) ? $labels[$kolom] : strtoupper(str_replace('_', ' ', (string) $kolom));
}

/**
 * Nilai angka kolom unit pada baris persediaan.
 */
function penjualan_get_nilai_kolom_unit($row, $kolom_unit)
{
	if ($kolom_unit === null || $kolom_unit === '') {
		return 0;
	}
	$CI = get_instance();
	$CI->load->helper('persediaan_display');
	if (!is_object($row)) {
		return 0;
	}
	return persediaan_parse_angka(persediaan_row_get($row, $kolom_unit));
}

/**
 * Normalisasi properti baris hasil query stock modal (alias jumlah_sediaan / pecah_satuan_persediaan).
 */
function penjualan_normalize_row_untuk_hitung_sisa($row)
{
	if (!is_object($row)) {
		return $row;
	}
	if (!isset($row->total_10) && isset($row->jumlah_sediaan)) {
		$row->total_10 = $row->jumlah_sediaan;
	}
	if (!isset($row->pecah_satuan) && isset($row->pecah_satuan_persediaan)) {
		$row->pecah_satuan = $row->pecah_satuan_persediaan;
	}
	return $row;
}

/**
 * Sisa stock untuk penjualan: total_10 - penjualan - pecah_satuan - bahan_produksi.
 */
function penjualan_get_sisa_stock_penjualan($row, $kolom_unit = null)
{
	$CI = get_instance();
	$CI->load->helper('persediaan_display');

	$row = penjualan_normalize_row_untuk_hitung_sisa($row);
	return max(0, (int) floor(persediaan_hitung_sisa_stock($row)));
}

/**
 * Tambah / kurangi penjualan di persediaan (field penjualan + kolom unit terpilih).
 */
function penjualan_update_persediaan_saat_jual($CI, $id_persediaan, $uuid_unit, $jumlah, $mode = 'tambah')
{
	$CI->load->helper('persediaan_display');
	$CI->load->model('Persediaan_model');

	$id_persediaan = (int) $id_persediaan;
	$jumlah = (int) preg_replace('/[^0-9]/', '', (string) $jumlah);
	if ($id_persediaan <= 0 || $jumlah <= 0) {
		return array('ok' => false, 'message' => 'Data persediaan atau jumlah tidak valid.');
	}

	$row = $CI->Persediaan_model->get_by_id($id_persediaan);
	if (empty($row)) {
		return array('ok' => false, 'message' => 'Barang persediaan tidak ditemukan.');
	}

	$row = penjualan_normalize_row_untuk_hitung_sisa($row);
	$kolom_unit = penjualan_resolve_kolom_persediaan_unit($CI, $uuid_unit);
	$penjualan_lama = (int) floor(persediaan_parse_angka($row->penjualan));

	if ($mode === 'tambah') {
		$sisa = penjualan_get_sisa_stock_penjualan($row, $kolom_unit);
		if ($jumlah > $sisa) {
			$label = $kolom_unit ? penjualan_get_label_kolom_unit($kolom_unit) : 'stok';
			return array(
				'ok' => false,
				'message' => 'Jumlah melebihi stok tersedia (' . $label . ': ' . $sisa . ').',
			);
		}
		$update = array('penjualan' => $penjualan_lama + $jumlah);
		if ($kolom_unit !== null && $CI->db->field_exists($kolom_unit, 'persediaan')) {
			$unit_lama = penjualan_get_nilai_kolom_unit($row, $kolom_unit);
			$update[$kolom_unit] = $unit_lama + $jumlah;
		}
	} else {
		if ($penjualan_lama < $jumlah) {
			return array('ok' => false, 'message' => 'Jumlah penjualan persediaan tidak mencukupi untuk dikurangi.');
		}
		$update = array('penjualan' => $penjualan_lama - $jumlah);
		if ($kolom_unit !== null && $CI->db->field_exists($kolom_unit, 'persediaan')) {
			$unit_lama = penjualan_get_nilai_kolom_unit($row, $kolom_unit);
			$update[$kolom_unit] = max(0, $unit_lama - $jumlah);
		}
	}

	$CI->Persediaan_model->update($id_persediaan, $update);
	return array('ok' => true, 'kolom_unit' => $kolom_unit);
}

/**
 * Tambah/kurangi hanya kolom unit di persediaan (field penjualan global tidak diubah).
 */
function penjualan_update_persediaan_kolom_unit_saja($CI, $id_persediaan, $kolom_unit, $jumlah, $mode = 'tambah')
{
	$CI->load->model('Persediaan_model');

	$id_persediaan = (int) $id_persediaan;
	$jumlah = (int) preg_replace('/[^0-9]/', '', (string) $jumlah);
	$kolom_unit = trim((string) $kolom_unit);

	if ($id_persediaan <= 0 || $jumlah <= 0 || $kolom_unit === '' || !$CI->db->field_exists($kolom_unit, 'persediaan')) {
		return array('ok' => true, 'kolom_unit' => $kolom_unit);
	}

	$row = $CI->Persediaan_model->get_by_id($id_persediaan);
	if (empty($row)) {
		return array('ok' => false, 'message' => 'Barang persediaan tidak ditemukan (id ' . $id_persediaan . ').');
	}

	$nilai_unit = penjualan_get_nilai_kolom_unit($row, $kolom_unit);
	if ($mode === 'kurangi') {
		if ($nilai_unit < $jumlah) {
			return array(
				'ok' => false,
				'message' => 'Kolom ' . penjualan_get_label_kolom_unit($kolom_unit)
					. ' tidak mencukupi (tersedia: ' . (int) floor($nilai_unit) . ', dikurangi: ' . $jumlah . ').',
			);
		}
		$CI->Persediaan_model->update($id_persediaan, array(
			$kolom_unit => max(0, $nilai_unit - $jumlah),
		));
	} else {
		$CI->Persediaan_model->update($id_persediaan, array(
			$kolom_unit => $nilai_unit + $jumlah,
		));
	}

	return array('ok' => true, 'kolom_unit' => $kolom_unit);
}

/**
 * Pindahkan penjualan per barang dari kolom unit lama ke unit baru (semua baris transaksi).
 */
function penjualan_pindah_unit_semua_barang($CI, $rows_penjualan, $uuid_unit_lama, $uuid_unit_baru)
{
	$uuid_unit_lama = trim((string) $uuid_unit_lama);
	$uuid_unit_baru = trim((string) $uuid_unit_baru);

	if ($uuid_unit_lama === '' || $uuid_unit_baru === '' || $uuid_unit_lama === $uuid_unit_baru) {
		return array('ok' => true, 'message' => 'Unit tidak berubah.');
	}

	$kolom_lama = penjualan_resolve_kolom_persediaan_unit($CI, $uuid_unit_lama);
	$kolom_baru = penjualan_resolve_kolom_persediaan_unit($CI, $uuid_unit_baru);

	if ($kolom_lama === $kolom_baru && $kolom_lama !== null) {
		return array('ok' => true, 'message' => 'Kolom unit sama, tidak perlu pindah.');
	}

	if (!is_array($rows_penjualan) || count($rows_penjualan) === 0) {
		return array('ok' => true, 'message' => 'Tidak ada barang penjualan.');
	}

	if ($kolom_lama === null && $kolom_baru === null) {
		return array('ok' => true, 'message' => 'Kolom unit persediaan tidak dikenali.');
	}

	$CI->load->model('Persediaan_model');
	$CI->db->trans_start();

	foreach ($rows_penjualan as $row_penjualan) {
		$id_persediaan = isset($row_penjualan->id_persediaan_barang)
			? (int) $row_penjualan->id_persediaan_barang
			: 0;
		$jumlah = isset($row_penjualan->jumlah)
			? (int) preg_replace('/[^0-9]/', '', (string) $row_penjualan->jumlah)
			: 0;

		if ($id_persediaan <= 0 || $jumlah <= 0) {
			continue;
		}

		$row_persediaan = $CI->Persediaan_model->get_by_id($id_persediaan);
		if (empty($row_persediaan)) {
			return array(
				'ok' => false,
				'message' => 'Persediaan id ' . $id_persediaan . ' tidak ditemukan.',
			);
		}

		$uuid_barang_penjualan = isset($row_penjualan->uuid_barang) ? trim((string) $row_penjualan->uuid_barang) : '';
		$uuid_barang_persediaan = isset($row_persediaan->uuid_barang) ? trim((string) $row_persediaan->uuid_barang) : '';
		if ($uuid_barang_penjualan !== '' && $uuid_barang_persediaan !== ''
			&& $uuid_barang_penjualan !== $uuid_barang_persediaan) {
			$nama = isset($row_penjualan->nama_barang) ? $row_penjualan->nama_barang : 'barang';
			return array(
				'ok' => false,
				'message' => 'uuid_barang tidak cocok untuk ' . $nama . '.',
			);
		}

		if ($kolom_lama !== null) {
			$hasil_kurang = penjualan_update_persediaan_kolom_unit_saja(
				$CI,
				$id_persediaan,
				$kolom_lama,
				$jumlah,
				'kurangi'
			);
			if (empty($hasil_kurang['ok'])) {
				$CI->db->trans_rollback();
				$nama = isset($row_penjualan->nama_barang) ? $row_penjualan->nama_barang : '';
				$pesan = isset($hasil_kurang['message']) ? $hasil_kurang['message'] : 'Gagal mengurangi unit lama.';
				if ($nama !== '') {
					$pesan .= ' (' . $nama . ')';
				}
				return array('ok' => false, 'message' => $pesan);
			}
		}

		if ($kolom_baru !== null) {
			$hasil_tambah = penjualan_update_persediaan_kolom_unit_saja(
				$CI,
				$id_persediaan,
				$kolom_baru,
				$jumlah,
				'tambah'
			);
			if (empty($hasil_tambah['ok'])) {
				$CI->db->trans_rollback();
				$nama = isset($row_penjualan->nama_barang) ? $row_penjualan->nama_barang : '';
				$pesan = isset($hasil_tambah['message']) ? $hasil_tambah['message'] : 'Gagal menambah unit baru.';
				if ($nama !== '') {
					$pesan .= ' (' . $nama . ')';
				}
				return array('ok' => false, 'message' => $pesan);
			}
		}
	}

	$CI->db->trans_complete();
	if ($CI->db->trans_status() === false) {
		return array('ok' => false, 'message' => 'Gagal menyimpan perubahan unit di persediaan.');
	}

	return array('ok' => true);
}

/**
 * Sesuaikan kolom unit saat jumlah penjualan diubah (selisih delta).
 */
function penjualan_update_persediaan_selisih_jual($CI, $id_persediaan, $uuid_unit, $jumlah_lama, $jumlah_baru)
{
	$jumlah_lama = (int) preg_replace('/[^0-9]/', '', (string) $jumlah_lama);
	$jumlah_baru = (int) preg_replace('/[^0-9]/', '', (string) $jumlah_baru);
	$delta = $jumlah_baru - $jumlah_lama;
	if ($delta === 0) {
		return array('ok' => true);
	}
	if ($delta > 0) {
		return penjualan_update_persediaan_saat_jual($CI, $id_persediaan, $uuid_unit, $delta, 'tambah');
	}
	return penjualan_update_persediaan_saat_jual($CI, $id_persediaan, $uuid_unit, abs($delta), 'kurangi');
}

/**
 * Daftar stock persediaan untuk modal Pilih Barang penjualan (filter bulan Tgl Jual).
 */
function penjualan_get_stock_persediaan_rows($CI, $tgl_jual = null, $uuid_unit = null)
{
	$tgl_jual = trim((string) $tgl_jual);
	if ($tgl_jual === '') {
		return array();
	}

	$tgl = pembelian_get_filter_tanggal($CI, $tgl_jual);
	$has_kategori = $CI->db->field_exists('kategori', 'persediaan');
	$kategori_sql = $has_kategori ? 'persediaan.kategori AS kategori_barang' : "'' AS kategori_barang";
	$bukan_jasa_sql = penjualan_sql_bukan_kategori_jasa($CI, 'persediaan');
	$uuid_barang_sql = penjualan_sql_uuid_barang_expr('persediaan');
	$bulan_where_sql = penjualan_sql_filter_bulan_persediaan_where('persediaan');

	$unit_cols_sql = '';
	foreach (penjualan_persediaan_kolom_unit_existing($CI) as $kolom) {
		$unit_cols_sql .= ",\n\t\t\tpersediaan.`{$kolom}` AS `{$kolom}`";
	}

	$sql = "SELECT persediaan.id AS id,
			persediaan.tanggal_beli AS tanggal_beli,
			persediaan.tanggal AS tanggal,
			persediaan.uuid_spop AS uuid_spop,
			persediaan.spop AS spop,
			{$uuid_barang_sql} AS uuid_barang,
			persediaan.uuid_persediaan AS uuid_persediaan,
			persediaan.kode_barang AS kode_barang,
			persediaan.namabarang AS nama_barang_beli,
			persediaan.total_10 AS total_10,
			persediaan.total_10 AS jumlah_sediaan,
			persediaan.hpp AS harga_satuan_persediaan,
			persediaan.satuan AS satuan_persediaan,
			persediaan.pecah_satuan AS pecah_satuan,
			persediaan.pecah_satuan AS pecah_satuan_persediaan,
			persediaan.bahan_produksi AS bahan_produksi,
			persediaan.penjualan AS penjualan{$unit_cols_sql},
			{$kategori_sql}
		FROM persediaan
		WHERE TRIM(COALESCE(persediaan.namabarang, '')) <> ''
		AND {$bulan_where_sql}
		AND {$bukan_jasa_sql}
		ORDER BY persediaan.namabarang ASC, persediaan.id ASC";

	$bulan_ym = date('Y-m', strtotime($tgl['awal']));
	$query = $CI->db->query($sql, array(
		$tgl['awal'],
		$tgl['akhir'],
		$tgl['awal'],
		$tgl['akhir'],
		$bulan_ym,
	));
	if ($query === false) {
		$err = $CI->db->error();
		$pesan = isset($err['message']) ? $err['message'] : 'Query persediaan gagal.';
		throw new Exception($pesan);
	}

	return $query->result();
}

/**
 * Kunci bulan (Y-m) dari Tgl Jual untuk perbandingan perubahan datepicker.
 */
function penjualan_get_bulan_key_from_tgl($tgl_jual)
{
	$ts = pembelian_parse_tanggal_po($tgl_jual);
	if ($ts === false) {
		$ts = strtotime(str_replace('/', '-', trim((string) $tgl_jual)));
	}
	return ($ts !== false) ? date('Y-m', $ts) : '';
}

/**
 * Hapus semua baris penjualan satu transaksi + kembalikan field penjualan di persediaan.
 */
function penjualan_hapus_semua_barang_by_uuid($CI, $uuid_penjualan)
{
	$uuid_penjualan = trim((string) $uuid_penjualan);
	if ($uuid_penjualan === '') {
		return 0;
	}

	$CI->load->model('Tbl_penjualan_model');
	$CI->load->model('Persediaan_model');
	$rows = $CI->Tbl_penjualan_model->get_all_by_uuid_penjualan($uuid_penjualan);
	$jumlah_hapus = 0;

	foreach ($rows as $row) {
		if (empty($row->id)) {
			continue;
		}
		$row_penjualan = $CI->Tbl_penjualan_model->get_by_id($row->id);
		if (empty($row_penjualan)) {
			continue;
		}

		$id_persediaan = $row_penjualan->id_persediaan_barang;
		if (!empty($id_persediaan)) {
			penjualan_update_persediaan_saat_jual(
				$CI,
				$id_persediaan,
				isset($row_penjualan->uuid_unit) ? $row_penjualan->uuid_unit : '',
				$row_penjualan->jumlah,
				'kurangi'
			);
		}

		$CI->Tbl_penjualan_model->delete($row->id);
		$jumlah_hapus++;
	}

	return $jumlah_hapus;
}

/**
 * Ambil baris persediaan untuk satu baris penjualan (id → uuid_persediaan → uuid_barang).
 */
function penjualan_get_persediaan_by_penjualan_row($CI, $row_penjualan)
{
	if (empty($row_penjualan)) {
		return null;
	}

	$CI->load->model('Persediaan_model');

	$id_persediaan = isset($row_penjualan->id_persediaan_barang)
		? (int) $row_penjualan->id_persediaan_barang
		: 0;
	if ($id_persediaan > 0) {
		$row = $CI->Persediaan_model->get_by_id($id_persediaan);
		if (!empty($row)) {
			return $row;
		}
	}

	$uuid_persediaan = isset($row_penjualan->uuid_persediaan)
		? trim((string) $row_penjualan->uuid_persediaan)
		: '';
	if ($uuid_persediaan !== '') {
		$row = $CI->db->where('uuid_persediaan', $uuid_persediaan)->limit(1)->get('persediaan')->row();
		if (!empty($row)) {
			return $row;
		}
	}

	$uuid_barang = isset($row_penjualan->uuid_barang)
		? trim((string) $row_penjualan->uuid_barang)
		: '';
	if ($uuid_barang !== '') {
		$row = $CI->db->where('uuid_barang', $uuid_barang)
			->order_by('id', 'DESC')
			->limit(1)
			->get('persediaan')
			->row();
		if (!empty($row)) {
			return $row;
		}
	}

	return null;
}

/**
 * Lengkapi baris penjualan dengan data barang/stock dari tabel persediaan (untuk cetak).
 */
function penjualan_enrich_baris_cetak($CI, $row_penjualan)
{
	$CI->load->helper('persediaan_display');

	if (is_array($row_penjualan)) {
		$row_penjualan = (object) $row_penjualan;
	}

	$persediaan = penjualan_get_persediaan_by_penjualan_row($CI, $row_penjualan);
	if (empty($persediaan)) {
		return $row_penjualan;
	}

	if (!empty($persediaan->uuid_persediaan)) {
		$row_penjualan->uuid_persediaan = $persediaan->uuid_persediaan;
	}
	if (!empty($persediaan->uuid_barang)) {
		$row_penjualan->uuid_barang = $persediaan->uuid_barang;
	}
	if (!empty($persediaan->id)) {
		$row_penjualan->id_persediaan_barang = $persediaan->id;
	}
	if (!empty($persediaan->kode_barang)) {
		$row_penjualan->kode_barang = $persediaan->kode_barang;
	}
	if (!empty($persediaan->namabarang)) {
		$row_penjualan->nama_barang = $persediaan->namabarang;
	}
	if (!empty($persediaan->satuan)) {
		$row_penjualan->satuan = $persediaan->satuan;
	}

	$row_norm = penjualan_normalize_row_untuk_hitung_sisa($persediaan);
	$row_penjualan->sisa_stock = penjualan_get_sisa_stock_penjualan($row_norm);
	$row_penjualan->total_10_persediaan = persediaan_parse_angka(
		isset($persediaan->total_10) ? $persediaan->total_10 : 0
	);

	return $row_penjualan;
}

/**
 * Daftar baris penjualan untuk cetak, semua data barang dari persediaan.
 */
function penjualan_enrich_data_cetak_penjualan($CI, $rows_penjualan)
{
	$hasil = array();
	if (!is_array($rows_penjualan)) {
		return $hasil;
	}
	foreach ($rows_penjualan as $row) {
		$hasil[] = penjualan_enrich_baris_cetak($CI, $row);
	}
	return $hasil;
}

/**
 * Render HTML tbody + modal nested untuk Pilih Barang penjualan.
 */
function penjualan_render_modal_pilih_barang($CI, $data)
{
	$uuid_unit = isset($data['uuid_unit']) ? $data['uuid_unit'] : '';
	if (!isset($data['penjualan_kolom_unit'])) {
		$data['penjualan_kolom_unit'] = penjualan_resolve_kolom_persediaan_unit($CI, $uuid_unit);
	}
	if (!isset($data['penjualan_label_unit'])) {
		$data['penjualan_label_unit'] = $data['penjualan_kolom_unit']
			? penjualan_get_label_kolom_unit($data['penjualan_kolom_unit'])
			: '';
	}

	$data['fragment_part'] = 'tbody';
	$tbody = $CI->load->view('anekadharma/tbl_penjualan/_modal_pilih_barang_penjualan_fragment', $data, TRUE);
	$data['fragment_part'] = 'modals';
	$modals = $CI->load->view('anekadharma/tbl_penjualan/_modal_pilih_barang_penjualan_fragment', $data, TRUE);

	return array(
		'tbody' => $tbody,
		'modals' => $modals,
	);
}

/**
 * Bandingkan HPP persediaan dengan harga_satuan penjualan (angka).
 */
function persediaan_recalculate_harga_cocok($hpp_persediaan, $harga_penjualan)
{
	$CI = get_instance();
	$CI->load->helper('persediaan_display');
	$a = persediaan_parse_angka($hpp_persediaan);
	$b = persediaan_parse_angka($harga_penjualan);
	return abs($a - $b) < 0.01;
}

function persediaan_recalculate_satuan_key($satuan)
{
	$s = strtolower(trim((string) $satuan));
	if ($s === '') {
		return '';
	}

	$aliases = array(
		'dz' => 'dzn',
		'dzn' => 'dzn',
		'dus kecil' => 'dus kecil',
		'dus' => 'dus',
	);

	return isset($aliases[$s]) ? $aliases[$s] : $s;
}

function persediaan_recalculate_satuan_cocok($satuan_persediaan, $satuan_penjualan)
{
	$a = persediaan_recalculate_satuan_key($satuan_persediaan);
	$b = persediaan_recalculate_satuan_key($satuan_penjualan);
	if ($a !== '' && $b !== '' && $a === $b) {
		return true;
	}

	return strcasecmp(trim((string) $satuan_persediaan), trim((string) $satuan_penjualan)) === 0;
}

function persediaan_recalculate_normalize_nama($nama)
{
	$nama = trim((string) $nama);
	if ($nama === '') {
		return '';
	}
	$fixed = preg_replace('/\s+/u', ' ', $nama);
	if ($fixed === null) {
		$fixed = preg_replace('/\s+/', ' ', $nama);
	}
	$nama = is_string($fixed) ? $fixed : $nama;
	return strtolower($nama);
}

function persediaan_recalculate_master_barang_table($CI)
{
	foreach (array('sys_nama_barang_x', 'sys_nama_barang') as $tbl) {
		if ($CI->db->table_exists($tbl)) {
			return $tbl;
		}
	}

	return null;
}

function persediaan_recalculate_map_add_row(&$map, $row)
{
	$map['by_id'][(int) $row->id] = $row;

	$uuid_p = trim((string) $row->uuid_persediaan);
	if ($uuid_p !== '') {
		if (!isset($map['by_uuid_pers'][$uuid_p])) {
			$map['by_uuid_pers'][$uuid_p] = array();
		}
		$map['by_uuid_pers'][$uuid_p][] = $row;
	}

	$uuid_b = trim((string) $row->uuid_barang);
	if ($uuid_b !== '') {
		if (!isset($map['by_uuid_barang'][$uuid_b])) {
			$map['by_uuid_barang'][$uuid_b] = array();
		}
		$map['by_uuid_barang'][$uuid_b][] = $row;
	}

	$nama_key = persediaan_recalculate_nama_satuan_hpp_key($row->namabarang, $row->satuan, $row->hpp);
	if ($nama_key !== '') {
		if (!isset($map['by_nama_key'][$nama_key])) {
			$map['by_nama_key'][$nama_key] = array();
		}
		$map['by_nama_key'][$nama_key][] = $row;
	}

	$sync_key = persediaan_recalculate_sync_nama_satuan_hpp_key($row->namabarang, $row->satuan, $row->hpp);
	if ($sync_key !== '') {
		if (!isset($map['by_sync_key'][$sync_key])) {
			$map['by_sync_key'][$sync_key] = array();
		}
		$map['by_sync_key'][$sync_key][] = $row;
	}

	$pembelian_key = persediaan_recalculate_sync_pembelian_persediaan_key(
		$row->namabarang,
		$row->satuan,
		$row->hpp,
		isset($row->spop) ? $row->spop : ''
	);
	if ($pembelian_key !== '') {
		if (!isset($map['by_pembelian_sync_key'][$pembelian_key])) {
			$map['by_pembelian_sync_key'][$pembelian_key] = array();
		}
		$map['by_pembelian_sync_key'][$pembelian_key][] = $row;
	}

	$ns_key = persediaan_recalculate_nama_satuan_key($row->namabarang, $row->satuan);
	if ($ns_key !== '') {
		if (!isset($map['by_nama_satuan'][$ns_key])) {
			$map['by_nama_satuan'][$ns_key] = array();
		}
		$map['by_nama_satuan'][$ns_key][] = $row;
	}

	$n_only = persediaan_recalculate_normalize_nama($row->namabarang);
	if ($n_only !== '') {
		if (!isset($map['by_nama'][$n_only])) {
			$map['by_nama'][$n_only] = array();
		}
		$map['by_nama'][$n_only][] = $row;
	}

	$kode = trim((string) (isset($row->kode) ? $row->kode : ''));
	if ($kode === '' && isset($row->kode_barang)) {
		$kode = trim((string) $row->kode_barang);
	}
	if ($kode !== '') {
		$kode_key = strtolower($kode);
		if (!isset($map['by_kode'][$kode_key])) {
			$map['by_kode'][$kode_key] = array();
		}
		$map['by_kode'][$kode_key][] = $row;
	}
}

function persediaan_recalculate_build_master_uuid_index($CI, $rows)
{
	$index = array(
		'by_master_uuid' => array(),
		'master_by_uuid' => array(),
	);

	$tbl = persediaan_recalculate_master_barang_table($CI);
	if ($tbl === null) {
		return $index;
	}

	$masters = $CI->db->query("SELECT * FROM `{$tbl}`")->result();
	$master_nama_to_uuid = array();
	foreach ($masters as $master) {
		$muuid = trim((string) $master->uuid_barang);
		if ($muuid !== '') {
			$index['master_by_uuid'][$muuid] = $master;
		}

		$nk = persediaan_recalculate_normalize_nama(isset($master->nama_barang) ? $master->nama_barang : '');
		if ($nk === '' || $muuid === '') {
			continue;
		}
		if (!isset($master_nama_to_uuid[$nk])) {
			$master_nama_to_uuid[$nk] = array();
		}
		$master_nama_to_uuid[$nk][$muuid] = $muuid;
	}

	foreach ($rows as $row) {
		$nk = persediaan_recalculate_normalize_nama($row->namabarang);
		if ($nk === '' || empty($master_nama_to_uuid[$nk])) {
			continue;
		}
		foreach ($master_nama_to_uuid[$nk] as $muuid) {
			if (!isset($index['by_master_uuid'][$muuid])) {
				$index['by_master_uuid'][$muuid] = array();
			}
			$index['by_master_uuid'][$muuid][] = $row;
		}
	}

	return $index;
}

function persediaan_recalculate_match_try_kandidat($kandidat, $row_penjualan, $metode)
{
	$row = persediaan_recalculate_filter_kandidat_penjualan($kandidat, $row_penjualan);
	if ($row) {
		return array('ok' => true, 'row' => $row, 'metode' => $metode);
	}

	return null;
}

function persediaan_recalculate_next_id($CI, &$map = null)
{
	if (is_array($map) && isset($map['_next_id']) && (int) $map['_next_id'] > 0) {
		$id = (int) $map['_next_id'];
		$map['_next_id'] = $id + 1;
		return $id;
	}

	$row = $CI->db->query("SELECT MAX(`id`) AS max_id FROM `persediaan`")->row();
	$id = 1;
	if ($row && $row->max_id !== null && $row->max_id !== '') {
		$id = (int) $row->max_id + 1;
	}

	if (is_array($map)) {
		$map['_next_id'] = $id + 1;
	}

	return $id;
}

function persediaan_recalculate_generate_uuid($CI)
{
	$row = $CI->db->query("SELECT REPLACE(UUID(),'-','') AS u")->row();
	return ($row && trim((string) $row->u) !== '') ? trim((string) $row->u) : '';
}

function persediaan_recalculate_resolve_uuid_barang_pembelian($CI, $uuid_pembelian, &$map)
{
	$uuid = trim((string) $uuid_pembelian);
	if ($uuid === '') {
		return array(
			'uuid' => persediaan_recalculate_generate_uuid($CI),
			'regenerated' => true,
			'note' => 'uuid_barang pembelian kosong → generate baru',
		);
	}

	if (!empty($map['by_uuid_barang'][$uuid])) {
		return array(
			'uuid' => persediaan_recalculate_generate_uuid($CI),
			'regenerated' => true,
			'note' => 'uuid_barang pembelian sudah ada di persediaan bulan ini → generate baru',
			'uuid_asli' => $uuid,
		);
	}

	return array(
		'uuid' => $uuid,
		'regenerated' => false,
		'note' => 'uuid_barang dari pembelian',
		'uuid_asli' => $uuid,
	);
}

function persediaan_recalculate_insert_persediaan_from_pembelian($CI, $ctx, $row_pembelian, $tabel, &$map)
{
	$existing = persediaan_recalculate_find_persediaan_for_pembelian($row_pembelian, $map);
	if ($existing) {
		return array(
			'ok' => false,
			'existing' => true,
			'alasan' => 'Sudah ada di persediaan id=' . (int) $existing->id . ' — tidak di-insert ulang',
		);
	}

	$nama = isset($row_pembelian->uraian) ? trim((string) $row_pembelian->uraian) : '';
	if ($nama === '') {
		return array('ok' => false, 'alasan' => 'uraian kosong');
	}

	$satuan = isset($row_pembelian->satuan) ? trim((string) $row_pembelian->satuan) : '';
	if ($satuan === '') {
		return array('ok' => false, 'alasan' => 'satuan kosong');
	}
	$satuan = persediaan_recalculate_sanitize_satuan_persediaan($satuan);
	if ($satuan === '') {
		return array('ok' => false, 'alasan' => 'satuan kosong');
	}

	$nama = persediaan_recalculate_sanitize_nama_persediaan($nama);
	$CI->load->helper('persediaan_display');
	$hpp = persediaan_parse_angka(isset($row_pembelian->harga_satuan) ? $row_pembelian->harga_satuan : 0);

	$uuid_src = isset($row_pembelian->uuid_barang) ? $row_pembelian->uuid_barang : '';
	$uuid_res = persediaan_recalculate_resolve_uuid_barang_pembelian($CI, $uuid_src, $map);
	$uuid_barang = $uuid_res['uuid'];

	$kode = '';
	if (isset($row_pembelian->kode_barang)) {
		$kode = trim((string) $row_pembelian->kode_barang);
	}
	if (strlen($kode) > 255) {
		$kode = substr($kode, 0, 255);
	}

	$new_id = persediaan_recalculate_next_id($CI, $map);
	$data = array(
		'id' => $new_id,
		'tanggal' => date('Y-m-d H:i:s'),
		'tanggal_beli' => $ctx['tanggal_beli'],
		'uuid_barang' => $uuid_barang,
		'kode' => $kode,
		'namabarang' => $nama,
		'satuan' => $satuan,
		'hpp' => (string) (int) floor($hpp),
		'sa' => '0',
		'beli' => '0',
		'penjualan' => '0',
		'total_10' => '0',
		'nilai_persediaan' => '0',
		'tuj' => '0',
	);

	if ($tabel === 'tbl_pembelian_jasa') {
		$data['kategori'] = 'jasa';
	}

	if (isset($row_pembelian->uuid_spop) && trim((string) $row_pembelian->uuid_spop) !== ''
		&& $CI->db->field_exists('uuid_spop', 'persediaan')) {
		$data['uuid_spop'] = trim((string) $row_pembelian->uuid_spop);
	}
	if (isset($row_pembelian->spop) && trim((string) $row_pembelian->spop) !== ''
		&& $CI->db->field_exists('spop', 'persediaan')) {
		$data['spop'] = trim((string) $row_pembelian->spop);
	}

	$db_debug = $CI->db->db_debug;
	$CI->db->db_debug = false;
	$CI->db->set('uuid_persediaan', "replace(uuid(),'-','')", FALSE);
	$insert_ok = $CI->db->insert('persediaan', $data);
	$CI->db->db_debug = $db_debug;

	if (!$insert_ok) {
		$err = $CI->db->error();
		$pesan = isset($err['message']) ? trim((string) $err['message']) : 'gagal insert persediaan';
		return array('ok' => false, 'alasan' => $pesan);
	}

	$row = $CI->db->where('id', $new_id)->limit(1)->get('persediaan')->row();
	if (!$row) {
		return array('ok' => false, 'alasan' => 'baris persediaan baru tidak ditemukan');
	}

	persediaan_recalculate_map_add_row($map, $row);
	$map['total'] = isset($map['total']) ? ((int) $map['total'] + 1) : 1;

	return array(
		'ok' => true,
		'row' => $row,
		'id_persediaan' => (int) $row->id,
		'uuid_barang' => trim((string) $row->uuid_barang),
		'uuid_persediaan' => trim((string) $row->uuid_persediaan),
		'uuid_regenerated' => !empty($uuid_res['regenerated']),
		'uuid_note' => isset($uuid_res['note']) ? $uuid_res['note'] : '',
		'uuid_barang_pembelian' => trim((string) $uuid_src),
	);
}

/**
 * Import baris pembelian yang belum ada di persediaan bulan terpilih → insert ke persediaan.
 */
function persediaan_recalculate_import_pembelian_tidak_sync($CI, $ctx, $tabel)
{
	$stats = array(
		'tabel' => $tabel,
		'total_pembelian' => 0,
		'unique_tidak_sync' => 0,
		'created' => 0,
		'failed' => 0,
		'skipped_existing' => 0,
		'skipped_beli_ok' => 0,
		'uuid_regenerated' => 0,
		'log' => array(),
	);

	if (!$CI->db->table_exists($tabel)) {
		return $stats;
	}

	$CI->load->helper('persediaan_display');
	$map = persediaan_recalculate_build_map_persediaan_bulan($CI, $ctx['tanggal_beli']);
	$list = $CI->db->query(
		"SELECT * FROM `{$tabel}`
		WHERE `tgl_po` IS NOT NULL AND `tgl_po` <> '0000-00-00'
		AND DATE(`tgl_po`) >= ? AND DATE(`tgl_po`) <= ?
		ORDER BY `id` ASC",
		array($ctx['tgl_awal'], $ctx['tgl_akhir'])
	)->result();

	$stats['total_pembelian'] = count($list);
	$processed_keys = array();

	if (!empty($map['by_pembelian_sync_key'])) {
		foreach ($map['by_pembelian_sync_key'] as $sk => $rows_p) {
			if (empty($rows_p[0]) || $sk === '') {
				continue;
			}
			$processed_keys[$sk] = persediaan_recalculate_pembelian_persediaan_ref_from_row($rows_p[0]);
		}
	}

	foreach ($list as $row) {
		$nama = isset($row->uraian) ? $row->uraian : '';
		$satuan = isset($row->satuan) ? $row->satuan : '';
		$harga = isset($row->harga_satuan) ? $row->harga_satuan : '';
		$spop = isset($row->spop) ? $row->spop : '';
		$id_pem = (int) $row->id;
		$key = persediaan_recalculate_sync_pembelian_persediaan_key($nama, $satuan, $harga, $spop);
		if ($key === '') {
			$key = persediaan_recalculate_sync_nama_satuan_hpp_key($nama, $satuan, $harga);
			if ($key === '') {
				$key = persediaan_recalculate_nama_satuan_hpp_key($nama, $satuan, $harga);
			}
		}

		$existing = persediaan_recalculate_find_persediaan_for_pembelian($row, $map);
		if ($existing) {
			$stats['skipped_existing']++;
			if ($key !== '') {
				$processed_keys[$key] = persediaan_recalculate_pembelian_persediaan_ref_from_row($existing);
			}

			$beli_ok = persediaan_recalculate_beli_persediaan_sudah_cocok(
				$CI,
				$existing,
				$ctx['tgl_awal'],
				$ctx['tgl_akhir']
			);
			if ($beli_ok) {
				$stats['skipped_beli_ok']++;
			}

			$ref = persediaan_recalculate_pembelian_persediaan_ref_from_row($existing);
			$alasan = 'Sudah ada di persediaan id=' . $ref['id_persediaan']
				. ', uuid_barang=' . $ref['uuid_barang']
				. ', uuid_persediaan=' . $ref['uuid_persediaan']
				. ' (uraian+satuan+harga_satuan+spop = namabarang+satuan+hpp+spop, bulan sama)';
			if ($beli_ok) {
				$alasan .= ' | beli=' . (int) floor(persediaan_parse_angka($existing->beli))
					. ' sudah sesuai total jumlah pembelian — tidak di-copy ulang';
			} else {
				$alasan .= ' | tidak di-copy ulang (beli akan di-update fase recalculate)';
			}

			$stats['log'][$id_pem] = array(
				'status' => $beli_ok ? 'sudah_ada' : 'sudah_ada',
				'alasan' => $alasan,
				'id_persediaan' => $ref['id_persediaan'],
				'uuid_barang' => $ref['uuid_barang'],
				'uuid_persediaan' => $ref['uuid_persediaan'],
			);
			continue;
		}

		if ($key === '') {
			$stats['log'][$id_pem] = array(
				'status' => 'gagal',
				'alasan' => 'Data tidak lengkap (uraian/satuan/harga kosong)',
			);
			$stats['failed']++;
			continue;
		}

		if (isset($processed_keys[$key])) {
			$ref = $processed_keys[$key];
			$stats['log'][$id_pem] = array(
				'status' => 'copied',
				'alasan' => 'Sudah di-copy ke persediaan id=' . (int) $ref['id_persediaan']
					. ', uuid_barang=' . $ref['uuid_barang']
					. ', uuid_persediaan=' . $ref['uuid_persediaan']
					. ' (baris pembelian sama: uraian+satuan+harga_satuan+spop — tidak duplikasi)',
				'id_persediaan' => (int) $ref['id_persediaan'],
				'uuid_barang' => $ref['uuid_barang'],
				'uuid_persediaan' => $ref['uuid_persediaan'],
			);
			continue;
		}

		$stats['unique_tidak_sync']++;
		$created = persediaan_recalculate_insert_persediaan_from_pembelian($CI, $ctx, $row, $tabel, $map);
		if (empty($created['ok'])) {
			if (!empty($created['existing'])) {
				$stats['skipped_existing']++;
				continue;
			}
			$stats['failed']++;
			$stats['log'][(int) $row->id] = array(
				'status' => 'gagal',
				'alasan' => isset($created['alasan']) ? $created['alasan'] : 'Gagal insert persediaan',
			);
			continue;
		}

		$stats['created']++;
		if (!empty($created['uuid_regenerated'])) {
			$stats['uuid_regenerated']++;
		}

		$ref = array(
			'id_persediaan' => (int) $created['id_persediaan'],
			'uuid_barang' => $created['uuid_barang'],
			'uuid_persediaan' => $created['uuid_persediaan'],
		);
		$processed_keys[$key] = $ref;

		$alasan = 'Sudah di-copy ke persediaan id=' . $ref['id_persediaan']
			. ', uuid_barang=' . $ref['uuid_barang']
			. ', uuid_persediaan=' . $ref['uuid_persediaan'];
		if (!empty($created['uuid_regenerated'])) {
			$alasan .= ' | uuid_barang BARU (' . $created['uuid_note'] . ', asli pembelian: '
				. $created['uuid_barang_pembelian'] . ')';
		} else {
			$alasan .= ' | uuid_barang dari pembelian';
		}

		$stats['log'][(int) $row->id] = array(
			'status' => 'copied',
			'alasan' => $alasan,
			'id_persediaan' => $ref['id_persediaan'],
			'uuid_barang' => $ref['uuid_barang'],
			'uuid_persediaan' => $ref['uuid_persediaan'],
		);
	}

	foreach ($list as $row) {
		$id = (int) $row->id;
		if (isset($stats['log'][$id])) {
			continue;
		}

		$nama = isset($row->uraian) ? $row->uraian : '';
		$satuan = isset($row->satuan) ? $row->satuan : '';
		$harga = isset($row->harga_satuan) ? $row->harga_satuan : '';
		$spop = isset($row->spop) ? $row->spop : '';
		$key = persediaan_recalculate_sync_pembelian_persediaan_key($nama, $satuan, $harga, $spop);
		if ($key === '') {
			$key = persediaan_recalculate_sync_nama_satuan_hpp_key($nama, $satuan, $harga);
			if ($key === '') {
				$key = persediaan_recalculate_nama_satuan_hpp_key($nama, $satuan, $harga);
			}
		}

		if ($key !== '' && isset($processed_keys[$key])) {
			$ref = $processed_keys[$key];
			$stats['log'][$id] = array(
				'status' => 'copied',
				'alasan' => 'Sudah di-copy ke persediaan id=' . (int) $ref['id_persediaan']
					. ', uuid_barang=' . $ref['uuid_barang']
					. ', uuid_persediaan=' . $ref['uuid_persediaan']
					. ' (baris pembelian sama: uraian+satuan+harga_satuan+spop)',
				'id_persediaan' => (int) $ref['id_persediaan'],
				'uuid_barang' => $ref['uuid_barang'],
				'uuid_persediaan' => $ref['uuid_persediaan'],
			);
		}
	}

	return $stats;
}

function persediaan_recalculate_get_pembelian_sync_log($CI, $bulan)
{
	$log = $CI->session->userdata('recalc_pembelian_sync_' . $bulan);
	return is_array($log) ? $log : array();
}

/**
 * Update uuid_barang + uuid_persediaan (+ id_persediaan_barang) di tbl_pembelian agar sesuai persediaan bulan yang sama.
 * Match: uraian+satuan+harga_satuan+spop = namabarang+satuan+hpp+spop, bulan/tahun tgl_po = tanggal_beli.
 */
function persediaan_recalculate_sync_uuid_pembelian_bulan($CI, $ctx, $tabel)
{
	$stats = array(
		'tabel' => $tabel,
		'total' => 0,
		'updated' => 0,
		'sudah_sesuai' => 0,
		'tidak_ditemukan' => 0,
		'gagal' => 0,
		'log' => array(),
	);

	if (!$CI->db->table_exists($tabel)) {
		return $stats;
	}

	$map = persediaan_recalculate_build_map_persediaan_bulan($CI, $ctx['tanggal_beli']);
	$spop_sql = $CI->db->field_exists('spop', $tabel) ? '`spop`' : "'' AS `spop`";
	$list = $CI->db->query(
		"SELECT `id`, `uuid_persediaan`, `id_persediaan_barang`, `uuid_barang`,
			`uraian`, `satuan`, `harga_satuan`, {$spop_sql}, `tgl_po`
		FROM `{$tabel}`
		WHERE `tgl_po` IS NOT NULL AND `tgl_po` <> '0000-00-00'
		AND DATE(`tgl_po`) >= ? AND DATE(`tgl_po`) <= ?
		ORDER BY `id` ASC",
		array($ctx['tgl_awal'], $ctx['tgl_akhir'])
	)->result();

	$stats['total'] = count($list);
	if (empty($list)) {
		return $stats;
	}

	$has_uuid_barang = $CI->db->field_exists('uuid_barang', $tabel);
	$has_id_pers = $CI->db->field_exists('id_persediaan_barang', $tabel);
	$label = ($tabel === 'tbl_pembelian_jasa') ? 'pembelian jasa' : 'pembelian';
	$db_debug = $CI->db->db_debug;
	$CI->db->db_debug = false;

	foreach ($list as $row_pembelian) {
		$id_pem = (int) $row_pembelian->id;

		$pers = persediaan_recalculate_find_persediaan_for_pembelian($row_pembelian, $map);
		if (!$pers) {
			$stats['tidak_ditemukan']++;
			$stats['log'][$id_pem] = array(
				'status' => 'tidak_sync',
				'alasan' => 'Tidak ada di persediaan bulan ' . $ctx['bulan_label']
					. ' (' . $label . ': uraian + satuan + harga_satuan + spop harus sama dengan namabarang + satuan + hpp + spop)',
			);
			continue;
		}

		$uuid_baru = trim((string) $pers->uuid_persediaan);
		$id_baru = (int) $pers->id;
		$uuid_barang_baru = $has_uuid_barang ? trim((string) $pers->uuid_barang) : '';

		if ($uuid_baru === '') {
			$stats['gagal']++;
			$stats['log'][$id_pem] = array(
				'status' => 'gagal',
				'alasan' => 'Persediaan cocok ditemukan (id ' . $id_baru . ') tetapi uuid_persediaan kosong',
			);
			continue;
		}

		if (persediaan_recalculate_penjualan_uuid_sudah_cocok($row_pembelian, $pers, $has_uuid_barang)) {
			$stats['sudah_sesuai']++;
			$stats['log'][$id_pem] = array(
				'status' => 'sudah_sesuai',
				'alasan' => 'Sudah sinkronisasi dengan persediaan id=' . $id_baru
					. ', uuid_barang=' . $uuid_barang_baru
					. ', uuid_persediaan=' . $uuid_baru,
			);
			continue;
		}

		$update = array(
			'uuid_persediaan' => $uuid_baru,
		);
		if ($has_id_pers) {
			$update['id_persediaan_barang'] = $id_baru;
		}
		if ($has_uuid_barang && $uuid_barang_baru !== '') {
			$update['uuid_barang'] = $uuid_barang_baru;
		}

		$CI->db->where('id', $id_pem);
		if ($CI->db->update($tabel, $update)) {
			$stats['updated']++;
			$stats['log'][$id_pem] = array(
				'status' => 'synced',
				'alasan' => 'Sudah sinkronisasi dengan persediaan id=' . $id_baru
					. ', uuid_barang=' . $uuid_barang_baru
					. ', uuid_persediaan=' . $uuid_baru,
				'id_persediaan' => $id_baru,
				'uuid_barang' => $uuid_barang_baru,
				'uuid_persediaan' => $uuid_baru,
			);
		} else {
			$stats['gagal']++;
			$err = $CI->db->error();
			$pesan = isset($err['message']) ? trim((string) $err['message']) : 'Gagal update ' . $tabel;
			$stats['log'][$id_pem] = array(
				'status' => 'gagal',
				'alasan' => $pesan,
			);
		}
	}

	$CI->db->db_debug = $db_debug;

	return $stats;
}

function persediaan_recalculate_get_pembelian_import_log($CI, $bulan)
{
	$log = $CI->session->userdata('recalc_pembelian_import_' . $bulan);
	return is_array($log) ? $log : array();
}

function persediaan_recalculate_insert_persediaan_from_penjualan($CI, $ctx, $row_penjualan, &$map)
{
	$nama = isset($row_penjualan->nama_barang) ? trim((string) $row_penjualan->nama_barang) : '';
	if ($nama === '') {
		return array('ok' => false, 'alasan' => 'nama_barang kosong');
	}

	$satuan = isset($row_penjualan->satuan) ? trim((string) $row_penjualan->satuan) : '';
	if ($satuan === '') {
		return array('ok' => false, 'alasan' => 'satuan kosong');
	}
	$satuan = persediaan_recalculate_sanitize_satuan_persediaan($satuan);
	if ($satuan === '') {
		return array('ok' => false, 'alasan' => 'satuan kosong');
	}

	$nama = persediaan_recalculate_sanitize_nama_persediaan($nama);
	$CI->load->helper('persediaan_display');
	$hpp = persediaan_parse_angka(isset($row_penjualan->harga_satuan) ? $row_penjualan->harga_satuan : 0);

	$uuid_src = isset($row_penjualan->uuid_barang) ? $row_penjualan->uuid_barang : '';
	$uuid_res = persediaan_recalculate_resolve_uuid_barang_pembelian($CI, $uuid_src, $map);
	$uuid_barang = $uuid_res['uuid'];

	$kode = '';
	if (isset($row_penjualan->kode_barang)) {
		$kode = trim((string) $row_penjualan->kode_barang);
	}
	if (strlen($kode) > 255) {
		$kode = substr($kode, 0, 255);
	}

	$new_id = persediaan_recalculate_next_id($CI, $map);
	$data = array(
		'id' => $new_id,
		'tanggal' => date('Y-m-d H:i:s'),
		'tanggal_beli' => $ctx['tanggal_beli'],
		'uuid_barang' => $uuid_barang,
		'kode' => $kode,
		'namabarang' => $nama,
		'satuan' => $satuan,
		'hpp' => (string) (int) floor($hpp),
		'sa' => '0',
		'beli' => '0',
		'penjualan' => '0',
		'total_10' => '0',
		'nilai_persediaan' => '0',
		'tuj' => '0',
	);

	$db_debug = $CI->db->db_debug;
	$CI->db->db_debug = false;
	$CI->db->set('uuid_persediaan', "replace(uuid(),'-','')", FALSE);
	$insert_ok = $CI->db->insert('persediaan', $data);
	$CI->db->db_debug = $db_debug;

	if (!$insert_ok) {
		$err = $CI->db->error();
		$pesan = isset($err['message']) ? trim((string) $err['message']) : 'gagal insert persediaan';
		return array('ok' => false, 'alasan' => $pesan);
	}

	$row = $CI->db->where('id', $new_id)->limit(1)->get('persediaan')->row();
	if (!$row) {
		return array('ok' => false, 'alasan' => 'baris persediaan baru tidak ditemukan');
	}

	persediaan_recalculate_map_add_row($map, $row);
	$map['total'] = isset($map['total']) ? ((int) $map['total'] + 1) : 1;

	return array(
		'ok' => true,
		'row' => $row,
		'id_persediaan' => (int) $row->id,
		'uuid_barang' => trim((string) $row->uuid_barang),
		'uuid_persediaan' => trim((string) $row->uuid_persediaan),
		'uuid_regenerated' => !empty($uuid_res['regenerated']),
		'uuid_note' => isset($uuid_res['note']) ? $uuid_res['note'] : '',
		'uuid_barang_penjualan' => trim((string) $uuid_src),
	);
}

/**
 * Import baris penjualan yang belum ada di persediaan bulan terpilih (nama+satuan+harga) → insert ke persediaan.
 */
function persediaan_recalculate_import_penjualan_tidak_sync($CI, $ctx)
{
	$stats = array(
		'total_penjualan' => 0,
		'unique_tidak_sync' => 0,
		'created' => 0,
		'failed' => 0,
		'skipped_sudah_ada' => 0,
		'uuid_regenerated' => 0,
		'created_details' => array(),
		'log' => array(),
	);

	if (!$CI->db->table_exists('tbl_penjualan')) {
		return $stats;
	}

	$map = persediaan_recalculate_build_map_persediaan_bulan($CI, $ctx['tanggal_beli']);
	$list = $CI->db->query(
		"SELECT * FROM `tbl_penjualan`
		WHERE DATE(`tgl_jual`) >= ? AND DATE(`tgl_jual`) <= ?
		ORDER BY `id` ASC",
		array($ctx['tgl_awal'], $ctx['tgl_akhir'])
	)->result();

	$stats['total_penjualan'] = count($list);
	$processed_keys = array();

	foreach ($list as $row) {
		$id_penj = (int) $row->id;
		$nama = isset($row->nama_barang) ? $row->nama_barang : '';
		$satuan = isset($row->satuan) ? $row->satuan : '';
		$harga = isset($row->harga_satuan) ? $row->harga_satuan : '';
		$key = persediaan_recalculate_sync_nama_satuan_hpp_key($nama, $satuan, $harga);

		$pers_ada = persediaan_recalculate_find_persediaan_for_sync($row, $map);
		if ($pers_ada) {
			$ref = persediaan_recalculate_pembelian_persediaan_ref_from_row($pers_ada);
			if ($key !== '') {
				$processed_keys[$key] = $ref;
			}
			$stats['skipped_sudah_ada']++;
			$stats['log'][$id_penj] = array(
				'status' => 'sudah_ada',
				'alasan' => 'Sudah ada di persediaan id=' . (int) $ref['id_persediaan']
					. ' (nama+satuan+harga cocok, tidak di-copy ulang)',
				'id_persediaan' => (int) $ref['id_persediaan'],
				'uuid_barang' => $ref['uuid_barang'],
				'uuid_persediaan' => $ref['uuid_persediaan'],
			);
			continue;
		}

		$match = persediaan_recalculate_match_penjualan_ke_persediaan($CI, $row, $map);
		if (!empty($match['ok']) && !empty($match['row'])) {
			$ref = persediaan_recalculate_pembelian_persediaan_ref_from_row($match['row']);
			if ($key !== '') {
				$processed_keys[$key] = $ref;
			}
			$stats['skipped_sudah_ada']++;
			$stats['log'][$id_penj] = array(
				'status' => 'sudah_ada',
				'alasan' => 'Sudah ada di persediaan id=' . (int) $ref['id_persediaan']
					. ' via ' . (isset($match['metode']) ? $match['metode'] : 'match') . ' (tidak di-copy ulang)',
				'id_persediaan' => (int) $ref['id_persediaan'],
				'uuid_barang' => $ref['uuid_barang'],
				'uuid_persediaan' => $ref['uuid_persediaan'],
			);
			continue;
		}

		if ($key === '') {
			$stats['log'][$id_penj] = array(
				'status' => 'gagal',
				'alasan' => 'Data tidak lengkap (nama_barang/satuan/harga kosong)',
			);
			$stats['failed']++;
			continue;
		}

		if (isset($processed_keys[$key])) {
			$ref = $processed_keys[$key];
			$stats['log'][$id_penj] = array(
				'status' => 'copied',
				'alasan' => 'Sudah di-copy ke persediaan id=' . (int) $ref['id_persediaan']
					. ', uuid_barang=' . $ref['uuid_barang']
					. ', uuid_persediaan=' . $ref['uuid_persediaan']
					. ' (baris penjualan sama: nama_barang+satuan+harga_satuan)',
				'id_persediaan' => (int) $ref['id_persediaan'],
				'uuid_barang' => $ref['uuid_barang'],
				'uuid_persediaan' => $ref['uuid_persediaan'],
			);
			continue;
		}

		$stats['unique_tidak_sync']++;
		$alasan_tidak_ketemu = persediaan_recalculate_alasan_penjualan_tidak_ketemu_map($row, $map);
		$created = persediaan_recalculate_insert_persediaan_from_penjualan($CI, $ctx, $row, $map);
		if (empty($created['ok'])) {
			$stats['failed']++;
			$stats['log'][$id_penj] = array(
				'status' => 'gagal',
				'alasan' => isset($created['alasan']) ? $created['alasan'] : 'Gagal insert persediaan',
			);
			continue;
		}

		$stats['created']++;
		if (!empty($created['uuid_regenerated'])) {
			$stats['uuid_regenerated']++;
		}

		$ref = array(
			'id_persediaan' => (int) $created['id_persediaan'],
			'uuid_barang' => $created['uuid_barang'],
			'uuid_persediaan' => $created['uuid_persediaan'],
		);
		$processed_keys[$key] = $ref;

		$stats['created_details'][] = array(
			'id_penjualan' => $id_penj,
			'nama_barang' => $nama,
			'satuan_penjualan' => $satuan,
			'harga_satuan' => $harga,
			'satuan_disimpan' => persediaan_recalculate_sanitize_satuan_persediaan($satuan),
			'nama_disimpan' => persediaan_recalculate_sanitize_nama_persediaan($nama),
			'sync_key' => $key,
			'id_persediaan' => (int) $created['id_persediaan'],
			'uuid_barang' => $created['uuid_barang'],
			'uuid_persediaan' => $created['uuid_persediaan'],
			'alasan_insert' => $alasan_tidak_ketemu,
		);

		$alasan = 'BARU di-copy ke persediaan id=' . $ref['id_persediaan']
			. ', uuid_barang=' . $ref['uuid_barang']
			. ', uuid_persediaan=' . $ref['uuid_persediaan']
			. '. ' . $alasan_tidak_ketemu;
		if (!empty($created['uuid_regenerated'])) {
			$alasan .= ' | uuid_barang BARU (' . $created['uuid_note'] . ', asli penjualan: '
				. $created['uuid_barang_penjualan'] . ')';
		}

		$stats['log'][$id_penj] = array(
			'status' => 'copied',
			'alasan' => $alasan,
			'id_persediaan' => $ref['id_persediaan'],
			'uuid_barang' => $ref['uuid_barang'],
			'uuid_persediaan' => $ref['uuid_persediaan'],
		);
	}

	foreach ($list as $row) {
		$id = (int) $row->id;
		if (isset($stats['log'][$id])) {
			continue;
		}

		$nama = isset($row->nama_barang) ? $row->nama_barang : '';
		$satuan = isset($row->satuan) ? $row->satuan : '';
		$harga = isset($row->harga_satuan) ? $row->harga_satuan : '';
		$key = persediaan_recalculate_sync_nama_satuan_hpp_key($nama, $satuan, $harga);

		if ($key !== '' && isset($processed_keys[$key])) {
			$ref = $processed_keys[$key];
			$stats['log'][$id] = array(
				'status' => 'copied',
				'alasan' => 'Sudah di-copy ke persediaan id=' . (int) $ref['id_persediaan']
					. ', uuid_barang=' . $ref['uuid_barang']
					. ', uuid_persediaan=' . $ref['uuid_persediaan']
					. ' (baris penjualan sama: nama_barang+satuan+harga_satuan)',
				'id_persediaan' => (int) $ref['id_persediaan'],
				'uuid_barang' => $ref['uuid_barang'],
				'uuid_persediaan' => $ref['uuid_persediaan'],
			);
		}
	}

	return $stats;
}

function persediaan_recalculate_get_penjualan_sync_log($CI, $bulan)
{
	$log = $CI->session->userdata('recalc_penjualan_sync_' . $bulan);
	return is_array($log) ? $log : array();
}

function persediaan_recalculate_penjualan_uuid_sudah_cocok($row_penjualan, $pers, $has_uuid_barang)
{
	$uuid_baru = trim((string) $pers->uuid_persediaan);
	$id_baru = (int) $pers->id;
	if ($uuid_baru === '') {
		return false;
	}

	$uuid_lama = trim((string) $row_penjualan->uuid_persediaan);
	$id_lama = (int) $row_penjualan->id_persediaan_barang;
	$sudah_ok = ($uuid_lama === $uuid_baru && $id_lama === $id_baru);

	if ($has_uuid_barang) {
		$uuid_barang_lama = trim((string) $row_penjualan->uuid_barang);
		$uuid_barang_baru = trim((string) $pers->uuid_barang);
		if ($uuid_barang_baru !== '') {
			$sudah_ok = $sudah_ok && ($uuid_barang_lama === $uuid_barang_baru);
		}
	}

	return $sudah_ok;
}

function persediaan_recalculate_sanitize_satuan_persediaan($satuan)
{
	$s = trim((string) $satuan);
	if ($s === '') {
		return '';
	}

	$key = strtolower($s);
	$aliases = array(
		'dus kecil' => 'dus k',
		'dus besar' => 'dus',
		'pak besar' => 'pak',
		'rim' => 'rim',
		'roll' => 'roll',
		'dzn' => 'dzn',
		'dz' => 'dzn',
	);

	if (isset($aliases[$key])) {
		return $aliases[$key];
	}

	if (function_exists('mb_substr')) {
		return mb_substr($s, 0, 5, 'UTF-8');
	}

	return substr($s, 0, 5);
}

function persediaan_recalculate_sanitize_nama_persediaan($nama)
{
	$nama = trim((string) $nama);
	if ($nama === '') {
		return '';
	}

	if (function_exists('mb_substr')) {
		return mb_substr($nama, 0, 109, 'UTF-8');
	}

	return substr($nama, 0, 109);
}

function persediaan_recalculate_ensure_persediaan_dari_penjualan($CI, $ctx, $row_penjualan, &$map)
{
	$uuid = isset($row_penjualan->uuid_barang) ? trim((string) $row_penjualan->uuid_barang) : '';
	if ($uuid === '') {
		return array('ok' => false, 'alasan' => 'uuid_barang kosong');
	}

	if (!empty($map['by_uuid_barang'][$uuid]) || !empty($map['by_master_uuid'][$uuid])) {
		return array('ok' => false, 'alasan' => 'sudah ada di map');
	}

	$master = null;
	$tbl = persediaan_recalculate_master_barang_table($CI);
	if ($tbl !== null) {
		$master = $CI->db->where('uuid_barang', $uuid)->limit(1)->get($tbl)->row();
	}

	$nama = isset($row_penjualan->nama_barang) ? trim((string) $row_penjualan->nama_barang) : '';
	if ($master && trim((string) $master->nama_barang) !== '') {
		$nama = trim((string) $master->nama_barang);
	}
	if ($nama === '') {
		return array('ok' => false, 'alasan' => 'nama barang kosong');
	}

	$nkey = persediaan_recalculate_normalize_nama($nama);
	if ($nkey !== '' && !empty($map['by_nama'][$nkey])) {
		return array('ok' => false, 'alasan' => 'nama sudah ada di persediaan bulan ini');
	}

	$satuan = isset($row_penjualan->satuan) ? trim((string) $row_penjualan->satuan) : '';
	if ($satuan === '' && $master && trim((string) $master->satuan) !== '') {
		$satuan = trim((string) $master->satuan);
	}
	if ($satuan === '') {
		return array('ok' => false, 'alasan' => 'satuan kosong');
	}

	$satuan = persediaan_recalculate_sanitize_satuan_persediaan($satuan);
	if ($satuan === '') {
		return array('ok' => false, 'alasan' => 'satuan kosong');
	}

	$nama = persediaan_recalculate_sanitize_nama_persediaan($nama);

	$CI->load->helper('persediaan_display');
	$hpp = persediaan_parse_angka(isset($row_penjualan->harga_satuan) ? $row_penjualan->harga_satuan : 0);
	if ($hpp <= 0 && $master) {
		$hpp = persediaan_parse_angka($master->harga_satuan);
	}

	$kode = '';
	if ($master && trim((string) $master->kode_barang) !== '') {
		$kode = trim((string) $master->kode_barang);
	} elseif (isset($row_penjualan->kode_barang)) {
		$kode = trim((string) $row_penjualan->kode_barang);
	}
	if (strlen($kode) > 255) {
		$kode = substr($kode, 0, 255);
	}

	$new_id = persediaan_recalculate_next_id($CI, $map);
	$data = array(
		'id' => $new_id,
		'tanggal' => date('Y-m-d H:i:s'),
		'tanggal_beli' => $ctx['tanggal_beli'],
		'uuid_barang' => $uuid,
		'kode' => $kode,
		'namabarang' => $nama,
		'satuan' => $satuan,
		'hpp' => (string) (int) floor($hpp),
		'sa' => '0',
		'beli' => '0',
		'penjualan' => '0',
		'total_10' => '0',
		'nilai_persediaan' => '0',
		'tuj' => '0',
	);

	$db_debug = $CI->db->db_debug;
	$CI->db->db_debug = false;
	$CI->db->set('uuid_persediaan', "replace(uuid(),'-','')", FALSE);
	$insert_ok = $CI->db->insert('persediaan', $data);
	$CI->db->db_debug = $db_debug;

	if (!$insert_ok) {
		$err = $CI->db->error();
		$pesan = isset($err['message']) ? trim((string) $err['message']) : 'gagal insert persediaan';
		return array('ok' => false, 'alasan' => $pesan);
	}

	$row = $CI->db->where('id', $new_id)->limit(1)->get('persediaan')->row();
	if (!$row) {
		return array('ok' => false, 'alasan' => 'baris persediaan baru tidak ditemukan');
	}

	persediaan_recalculate_map_add_row($map, $row);
	if (!isset($map['by_master_uuid'])) {
		$map['by_master_uuid'] = array();
	}
	if (!isset($map['by_master_uuid'][$uuid])) {
		$map['by_master_uuid'][$uuid] = array();
	}
	$map['by_master_uuid'][$uuid][] = $row;
	$map['total'] = isset($map['total']) ? ((int) $map['total'] + 1) : 1;

	return array('ok' => true, 'row' => $row, 'created' => true);
}

function persediaan_recalculate_nama_satuan_key($nama, $satuan)
{
	$nama = persediaan_recalculate_normalize_nama($nama);
	$satuan = trim((string) $satuan);
	if ($nama === '' || $satuan === '') {
		return '';
	}
	return $nama . '|' . strtolower($satuan);
}

function persediaan_recalculate_nama_satuan_hpp_key($nama, $satuan, $hpp)
{
	$nama = persediaan_recalculate_normalize_nama($nama);
	$satuan = persediaan_recalculate_satuan_key($satuan);
	if ($nama === '' || $satuan === '') {
		return '';
	}
	$CI = get_instance();
	$CI->load->helper('persediaan_display');
	return $nama . '|' . $satuan . '|' . (string) persediaan_parse_angka($hpp);
}

/**
 * Kunci lookup import/sync penjualan↔persediaan (sama dengan field yang disimpan ke DB).
 */
function persediaan_recalculate_sync_nama_satuan_hpp_key($nama, $satuan, $hpp)
{
	$nama = persediaan_recalculate_normalize_nama(
		persediaan_recalculate_sanitize_nama_persediaan($nama)
	);
	$satuan = persediaan_recalculate_sanitize_satuan_persediaan($satuan);
	$satuan = strtolower(trim((string) $satuan));
	if ($nama === '' || $satuan === '') {
		return '';
	}
	$CI = get_instance();
	$CI->load->helper('persediaan_display');
	$hpp_key = (string) (int) floor(persediaan_parse_angka($hpp));

	return $nama . '|' . $satuan . '|' . $hpp_key;
}

/**
 * Normalisasi nomor SPOP untuk lookup (trim).
 */
function persediaan_recalculate_normalize_spop($spop)
{
	return trim((string) $spop);
}

/**
 * Bandingkan SPOP pembelian vs persediaan (keduanya kosong = cocok).
 */
function persediaan_recalculate_spop_cocok($spop_a, $spop_b)
{
	return persediaan_recalculate_normalize_spop($spop_a) === persediaan_recalculate_normalize_spop($spop_b);
}

/**
 * Kunci lookup pembelian↔persediaan: namabarang+satuan+hpp+spop (bulan via tanggal_beli).
 */
function persediaan_recalculate_sync_pembelian_persediaan_key($nama, $satuan, $hpp, $spop)
{
	$base = persediaan_recalculate_sync_nama_satuan_hpp_key($nama, $satuan, $hpp);
	if ($base === '') {
		return '';
	}

	return $base . '|spop:' . persediaan_recalculate_normalize_spop($spop);
}

/**
 * Bandingkan satuan persediaan vs pembelian (termasuk alias/sanitize).
 */
function persediaan_recalculate_satuan_cocok_pembelian($satuan_persediaan, $satuan_pembelian)
{
	if (persediaan_recalculate_satuan_cocok($satuan_persediaan, $satuan_pembelian)) {
		return true;
	}

	$sp = persediaan_recalculate_sanitize_satuan_persediaan($satuan_persediaan);
	$sb = persediaan_recalculate_sanitize_satuan_persediaan($satuan_pembelian);
	if ($sp !== '' && $sb !== '' && strcasecmp($sp, $sb) === 0) {
		return true;
	}

	return false;
}

function persediaan_recalculate_load_pembelian_bulan_rows($CI, $tabel, $tgl_awal, $tgl_akhir)
{
	static $cache = array();
	$key = $tabel . '|' . $tgl_awal . '|' . $tgl_akhir;

	if (isset($cache[$key])) {
		return $cache[$key];
	}

	if (!$CI->db->table_exists($tabel)) {
		$cache[$key] = array();
		return $cache[$key];
	}

	$spop_sql = $CI->db->field_exists('spop', $tabel)
		? 'TRIM(COALESCE(`spop`, \'\')) AS `spop`'
		: "'' AS `spop`";
	$cache[$key] = $CI->db->query(
		"SELECT `uraian`, `satuan`, `harga_satuan`, `jumlah`, {$spop_sql}
		FROM `{$tabel}`
		WHERE `tgl_po` IS NOT NULL AND `tgl_po` <> '0000-00-00'
		AND DATE(`tgl_po`) >= ? AND DATE(`tgl_po`) <= ?",
		array($tgl_awal, $tgl_akhir)
	)->result();

	return $cache[$key];
}

function persediaan_recalculate_build_map_persediaan_bulan($CI, $tanggal_beli)
{
	$rows = $CI->db->query(
		"SELECT * FROM `persediaan` WHERE `tanggal_beli` = ? ORDER BY `id` ASC",
		array($tanggal_beli)
	)->result();

	$map = array(
		'by_id' => array(),
		'by_uuid_pers' => array(),
		'by_uuid_barang' => array(),
		'by_master_uuid' => array(),
		'by_nama_key' => array(),
		'by_sync_key' => array(),
		'by_pembelian_sync_key' => array(),
		'by_nama_satuan' => array(),
		'by_nama' => array(),
		'by_kode' => array(),
		'total' => count($rows),
	);

	foreach ($rows as $row) {
		persediaan_recalculate_map_add_row($map, $row);
	}

	$master_index = persediaan_recalculate_build_master_uuid_index($CI, $rows);
	$map['by_master_uuid'] = $master_index['by_master_uuid'];
	$map['master_by_uuid'] = $master_index['master_by_uuid'];

	return $map;
}

function persediaan_recalculate_filter_kandidat_penjualan($kandidat, $row_penjualan)
{
	if (!is_array($kandidat) || count($kandidat) === 0) {
		return null;
	}

	$satuan_jual = isset($row_penjualan->satuan) ? $row_penjualan->satuan : '';
	$harga_jual = isset($row_penjualan->harga_satuan) ? $row_penjualan->harga_satuan : '';

	$cocok_satuan = array();
	foreach ($kandidat as $row) {
		if (persediaan_recalculate_satuan_cocok($row->satuan, $satuan_jual)) {
			$cocok_satuan[] = $row;
		}
	}

	if (count($cocok_satuan) === 0) {
		if (count($kandidat) === 1) {
			return $kandidat[0];
		}
		return null;
	}

	return persediaan_recalculate_pick_best_persediaan_row($cocok_satuan, $row_penjualan);
}

/**
 * Pilih satu baris persediaan dari beberapa kandidat (satuan sudah cocok).
 * Prioritas: harga=hpp, lalu uuid_barang sama, lalu baris pertama.
 */
function persediaan_recalculate_pick_best_persediaan_row($kandidat, $row_penjualan)
{
	if (!is_array($kandidat) || count($kandidat) === 0) {
		return null;
	}

	if (count($kandidat) === 1) {
		return $kandidat[0];
	}

	$harga_jual = isset($row_penjualan->harga_satuan) ? $row_penjualan->harga_satuan : '';
	foreach ($kandidat as $row) {
		if (persediaan_recalculate_harga_cocok($row->hpp, $harga_jual)) {
			return $row;
		}
	}

	$uuid_b_jual = isset($row_penjualan->uuid_barang) ? trim((string) $row_penjualan->uuid_barang) : '';
	if ($uuid_b_jual !== '') {
		foreach ($kandidat as $row) {
			if (trim((string) $row->uuid_barang) === $uuid_b_jual) {
				return $row;
			}
		}
	}

	return $kandidat[0];
}

function persediaan_recalculate_match_penjualan_ke_persediaan($CI, $row_penjualan, $map)
{
	if (empty($map) || empty($row_penjualan)) {
		return array('ok' => false, 'alasan' => 'Data tidak valid');
	}

	$pers_strict = persediaan_recalculate_find_persediaan_for_sync($row_penjualan, $map);
	if ($pers_strict) {
		return array(
			'ok' => true,
			'row' => $pers_strict,
			'metode' => 'nama_barang+satuan+harga_satuan',
		);
	}

	$uuid_b = isset($row_penjualan->uuid_barang) ? trim((string) $row_penjualan->uuid_barang) : '';
	if ($uuid_b !== '' && !empty($map['by_uuid_barang'][$uuid_b])) {
		$hit = persediaan_recalculate_match_try_kandidat($map['by_uuid_barang'][$uuid_b], $row_penjualan, 'uuid_barang+satuan');
		if ($hit) {
			return $hit;
		}
	}

	if ($uuid_b !== '' && !empty($map['by_master_uuid'][$uuid_b])) {
		$hit = persediaan_recalculate_match_try_kandidat($map['by_master_uuid'][$uuid_b], $row_penjualan, 'master_uuid_barang+satuan');
		if ($hit) {
			return $hit;
		}
	}

	$kode_jual = isset($row_penjualan->kode_barang) ? strtolower(trim((string) $row_penjualan->kode_barang)) : '';
	if ($kode_jual !== '' && !empty($map['by_kode'][$kode_jual])) {
		$hit = persediaan_recalculate_match_try_kandidat($map['by_kode'][$kode_jual], $row_penjualan, 'kode_barang+satuan');
		if ($hit) {
			return $hit;
		}
	}

	$id_pers = isset($row_penjualan->id_persediaan_barang)
		? (int) $row_penjualan->id_persediaan_barang
		: 0;
	if ($id_pers > 0 && isset($map['by_id'][$id_pers])) {
		$row = $map['by_id'][$id_pers];
		$uuid_j = trim((string) $uuid_b);
		$uuid_row = trim((string) $row->uuid_barang);
		if ($uuid_j === '' || $uuid_row === '' || $uuid_j === $uuid_row) {
			if (persediaan_recalculate_satuan_cocok($row->satuan, $row_penjualan->satuan)) {
				return array('ok' => true, 'row' => $row, 'metode' => 'id_persediaan_barang+satuan');
			}
		}
	}
	if ($id_pers > 0) {
		$ref = $CI->db->where('id', $id_pers)->limit(1)->get('persediaan')->row();
		if ($ref) {
			$uuid_ref = trim((string) $ref->uuid_barang);
			if ($uuid_ref !== '' && !empty($map['by_uuid_barang'][$uuid_ref])) {
				$hit = persediaan_recalculate_match_try_kandidat($map['by_uuid_barang'][$uuid_ref], $row_penjualan, 'id_persediaan_bridge+satuan');
				if ($hit) {
					return $hit;
				}
			}
			if ($uuid_ref !== '' && !empty($map['by_master_uuid'][$uuid_ref])) {
				$hit = persediaan_recalculate_match_try_kandidat($map['by_master_uuid'][$uuid_ref], $row_penjualan, 'id_persediaan_master_bridge+satuan');
				if ($hit) {
					return $hit;
				}
			}
		}
	}

	$uuid_p = isset($row_penjualan->uuid_persediaan) ? trim((string) $row_penjualan->uuid_persediaan) : '';
	if ($uuid_p !== '' && !empty($map['by_uuid_pers'][$uuid_p])) {
		$hit = persediaan_recalculate_match_try_kandidat($map['by_uuid_pers'][$uuid_p], $row_penjualan, 'uuid_persediaan+satuan');
		if ($hit) {
			return $hit;
		}
	}
	if ($uuid_p !== '') {
		$ref = $CI->db->where('uuid_persediaan', $uuid_p)->limit(1)->get('persediaan')->row();
		if ($ref) {
			$uuid_ref = trim((string) $ref->uuid_barang);
			if ($uuid_ref !== '' && !empty($map['by_uuid_barang'][$uuid_ref])) {
				$hit = persediaan_recalculate_match_try_kandidat($map['by_uuid_barang'][$uuid_ref], $row_penjualan, 'uuid_persediaan_bridge+satuan');
				if ($hit) {
					return $hit;
				}
			}
			if ($uuid_ref !== '' && !empty($map['by_master_uuid'][$uuid_ref])) {
				$hit = persediaan_recalculate_match_try_kandidat($map['by_master_uuid'][$uuid_ref], $row_penjualan, 'uuid_persediaan_master_bridge+satuan');
				if ($hit) {
					return $hit;
				}
			}
		}
	}

	$nama = isset($row_penjualan->nama_barang) ? $row_penjualan->nama_barang : '';
	$satuan = isset($row_penjualan->satuan) ? $row_penjualan->satuan : '';
	$harga = isset($row_penjualan->harga_satuan) ? $row_penjualan->harga_satuan : '';

	if ($uuid_b !== '' && !empty($map['master_by_uuid'][$uuid_b])) {
		$master = $map['master_by_uuid'][$uuid_b];
		$nama_master = isset($master->nama_barang) ? $master->nama_barang : '';
		if (trim((string) $nama_master) !== '') {
			$nama = $nama_master;
		}
	}

	$nama_key_hpp = persediaan_recalculate_nama_satuan_hpp_key($nama, $satuan, $harga);
	if ($nama_key_hpp !== '' && !empty($map['by_nama_key'][$nama_key_hpp])) {
		return array(
			'ok' => true,
			'row' => $map['by_nama_key'][$nama_key_hpp][0],
			'metode' => 'nama+satuan+hpp',
		);
	}

	$ns_key = persediaan_recalculate_nama_satuan_key($nama, $satuan);
	if ($ns_key !== '' && !empty($map['by_nama_satuan'][$ns_key])) {
		$row = persediaan_recalculate_pick_best_persediaan_row($map['by_nama_satuan'][$ns_key], $row_penjualan);
		if ($row) {
			return array('ok' => true, 'row' => $row, 'metode' => 'nama+satuan');
		}
	}

	$n_only = persediaan_recalculate_normalize_nama($nama);
	if ($n_only !== '' && !empty($map['by_nama'][$n_only])) {
		$hit = persediaan_recalculate_match_try_kandidat($map['by_nama'][$n_only], $row_penjualan, 'nama+satuan(fuzzy)');
		if ($hit) {
			return $hit;
		}
	}

	return array('ok' => false, 'alasan' => 'Barang tidak ditemukan di persediaan bulan terpilih');
}

/**
 * Resolve kolom unit persediaan dari baris penjualan (uuid_unit atau teks unit).
 */
function penjualan_resolve_kolom_persediaan_unit_dari_penjualan($CI, $row_penjualan, $ensure_column = true)
{
	$CI->load->helper('persediaan_display');

	$uuid_unit = isset($row_penjualan->uuid_unit) ? trim((string) $row_penjualan->uuid_unit) : '';
	if ($uuid_unit !== '') {
		$kolom = penjualan_resolve_kolom_persediaan_unit($CI, $uuid_unit, $ensure_column);
		if ($kolom !== null) {
			return persediaan_resolve_db_field_name($CI, $kolom);
		}
	}

	$unit_txt = isset($row_penjualan->unit) ? trim((string) $row_penjualan->unit) : '';
	if ($unit_txt === '') {
		return null;
	}

	$kolom_langsung = persediaan_resolve_unit_column_from_text($CI, $unit_txt);
	if ($kolom_langsung !== null) {
		return $kolom_langsung;
	}

	if ($CI->db->table_exists('sys_unit')) {
		$row_unit = $CI->db->query(
			"SELECT * FROM `sys_unit`
			WHERE LOWER(TRIM(COALESCE(`nama_unit`, ''))) = LOWER(?)
			OR LOWER(TRIM(COALESCE(`kode_unit`, ''))) = LOWER(?)
			LIMIT 1",
			array($unit_txt, $unit_txt)
		)->row();
		if ($row_unit && !empty($row_unit->uuid_unit)) {
			$kolom = penjualan_resolve_kolom_persediaan_unit($CI, $row_unit->uuid_unit, $ensure_column);
			if ($kolom !== null) {
				return persediaan_resolve_db_field_name($CI, $kolom);
			}
		}
		if ($row_unit) {
			$kolom = penjualan_kolom_dari_sys_unit_row($row_unit);
			if ($kolom !== null) {
				return persediaan_resolve_db_field_name($CI, $kolom);
			}
		}
	}

	return null;
}

function persediaan_recalculate_parse_jumlah_penjualan($jumlah)
{
	$CI = get_instance();
	$CI->load->helper('persediaan_display');
	$v = persediaan_parse_angka($jumlah);
	return max(0, (int) floor($v));
}

function persediaan_recalculate_reset_penjualan_persediaan_bulan($CI, $tanggal_beli)
{
	$CI->load->helper('persediaan_display');
	$unit_cols = penjualan_persediaan_kolom_unit_existing($CI);
	$update = array('penjualan' => '0');
	foreach ($unit_cols as $kolom) {
		$db_col = persediaan_resolve_db_field_name($CI, $kolom);
		if ($CI->db->field_exists($db_col, 'persediaan')) {
			$update[$db_col] = '0';
		}
	}

	$CI->db->where('tanggal_beli', $tanggal_beli);
	$CI->db->update('persediaan', $update);

	return array(
		'record_direset' => $CI->db->affected_rows(),
		'unit_kolom' => $unit_cols,
	);
}

function persediaan_recalculate_penjualan_context($CI, $bulan)
{
	if (!preg_match('/^\d{4}-\d{2}$/', trim((string) $bulan))) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');
	}

	$ts = strtotime($bulan . '-01');
	if ($ts === false) {
		return array('ok' => false, 'message' => 'Bulan tidak valid.');
	}

	$tanggal_beli = date('Y-m-01', $ts);
	$tgl_awal = date('Y-m-01', $ts);
	$tgl_akhir = date('Y-m-t', $ts);

	$row_p = $CI->db->query(
		"SELECT COUNT(*) AS jml FROM `persediaan` WHERE `tanggal_beli` = ?",
		array($tanggal_beli)
	)->row();
	$row_j = $CI->db->query(
		"SELECT COUNT(*) AS jml FROM `tbl_penjualan`
		WHERE DATE(`tgl_jual`) >= ? AND DATE(`tgl_jual`) <= ?",
		array($tgl_awal, $tgl_akhir)
	)->row();

	return array(
		'ok' => true,
		'bulan' => $bulan,
		'bulan_label' => date('m/Y', $ts),
		'tanggal_beli' => $tanggal_beli,
		'tgl_awal' => $tgl_awal,
		'tgl_akhir' => $tgl_akhir,
		'total_persediaan' => $row_p ? (int) $row_p->jml : 0,
		'total_penjualan' => $row_j ? (int) $row_j->jml : 0,
	);
}

function persediaan_recalculate_flush_accum_to_db($CI, $accum)
{
	$CI->load->helper('persediaan_display');
	$updated = 0;
	foreach ($accum as $pers_id => $data) {
		$update = array('penjualan' => (string) (int) floor($data['penjualan']));
		foreach ($data['units'] as $kolom => $nilai) {
			$db_col = persediaan_resolve_db_field_name($CI, $kolom);
			if ($CI->db->field_exists($db_col, 'persediaan')) {
				$update[$db_col] = (string) (int) floor($nilai);
			}
		}
		$CI->db->where('id', (int) $pers_id);
		$CI->db->update('persediaan', $update);
		$updated++;
	}
	return $updated;
}

/**
 * Jumlah penjualan dari tbl_penjualan bulan terpilih (nama_barang + satuan + harga_satuan = persediaan).
 */
function persediaan_recalculate_sum_penjualan_for_row($CI, $tgl_awal, $tgl_akhir, $row)
{
	if (!$CI->db->table_exists('tbl_penjualan')) {
		return 0;
	}

	$uuid_barang = trim((string) $row->uuid_barang);
	$nama = trim((string) $row->namabarang);
	$satuan = trim((string) $row->satuan);
	$hpp = trim((string) $row->hpp);

	if ($satuan === '') {
		return 0;
	}

	$link = array();
	$params = array($tgl_awal, $tgl_akhir);

	if ($uuid_barang !== '') {
		$link[] = 'TRIM(COALESCE(`uuid_barang`, \'\')) = ?';
		$params[] = $uuid_barang;
	}
	if ($nama !== '') {
		$link[] = 'LOWER(TRIM(COALESCE(`nama_barang`, \'\'))) = ?';
		$params[] = persediaan_recalculate_normalize_nama($nama);
	}

	if (empty($link)) {
		return 0;
	}

	$params[] = $satuan;
	$params[] = $hpp;

	$sql = 'SELECT COALESCE(SUM(CAST(`jumlah` AS SIGNED)), 0) AS jml FROM `tbl_penjualan`
		WHERE DATE(`tgl_jual`) >= ? AND DATE(`tgl_jual`) <= ?
		AND (' . implode(' OR ', $link) . ')
		AND LOWER(TRIM(COALESCE(`satuan`, \'\'))) = LOWER(?)
		AND CAST(REPLACE(TRIM(`harga_satuan`), \',\', \'\') AS DECIMAL(18,2))
			= CAST(REPLACE(?, \',\', \'\') AS DECIMAL(18,2))';

	$found = $CI->db->query($sql, $params)->row();
	return $found ? max(0, (int) $found->jml) : 0;
}

/**
 * Update total_10 persediaan setelah recalculate penjualan: total_10 = sa + beli - jumlah penjualan.
 */
function persediaan_recalculate_refresh_total_10_penjualan_bulan($CI, $ctx)
{
	$CI->load->helper('persediaan_display');

	$rows = $CI->db->query(
		"SELECT `id`, `sa`, `beli`, `penjualan`, `hpp`, `namabarang`, `satuan`, `uuid_barang`
		FROM `persediaan` WHERE `tanggal_beli` = ? ORDER BY `id` ASC",
		array($ctx['tanggal_beli'])
	)->result();

	$stats = array(
		'total_persediaan' => count($rows),
		'updated' => 0,
		'with_penjualan' => 0,
	);

	foreach ($rows as $row) {
		$sa = persediaan_parse_angka($row->sa);
		$beli = persediaan_parse_angka($row->beli);
		$hpp = persediaan_parse_angka($row->hpp);

		$jml_field = max(0, (int) floor(persediaan_parse_angka($row->penjualan)));
		$jml_tbl = persediaan_recalculate_sum_penjualan_for_row(
			$CI,
			$ctx['tgl_awal'],
			$ctx['tgl_akhir'],
			$row
		);
		$jml_penjualan = max($jml_field, $jml_tbl);

		if ($jml_penjualan > 0) {
			$stats['with_penjualan']++;
		}

		$total_10 = max(0, (int) floor($sa + $beli - $jml_penjualan));
		$nilai = $total_10 * $hpp;
		$total_t = (string) $total_10;
		$nilai_t = (string) (int) floor($nilai);
		$penj_t = (string) (int) floor($jml_penjualan);

		$update = array(
			'penjualan' => $penj_t,
			'total_10' => $total_t,
			'nilai_persediaan' => $nilai_t,
		);
		if ($CI->db->field_exists('tuj', 'persediaan')) {
			$update['tuj'] = $total_t;
		}

		$CI->db->where('id', (int) $row->id);
		if ($CI->db->update('persediaan', $update)) {
			$stats['updated']++;
		}
	}

	return $stats;
}

/**
 * Cari baris persediaan bulan terpilih untuk sinkron uuid_penjualan:
 * nama_barang = namabarang, satuan sama, harga_satuan = hpp.
 */
function persediaan_recalculate_find_persediaan_for_sync($row_penjualan, $map)
{
	if (empty($map) || empty($row_penjualan)) {
		return null;
	}

	$nama = isset($row_penjualan->nama_barang) ? $row_penjualan->nama_barang : '';
	$satuan = isset($row_penjualan->satuan) ? $row_penjualan->satuan : '';
	$harga = isset($row_penjualan->harga_satuan) ? $row_penjualan->harga_satuan : '';

	$sync_key = persediaan_recalculate_sync_nama_satuan_hpp_key($nama, $satuan, $harga);
	if ($sync_key !== '' && !empty($map['by_sync_key'][$sync_key])) {
		return $map['by_sync_key'][$sync_key][0];
	}

	$nama_key_hpp = persediaan_recalculate_nama_satuan_hpp_key($nama, $satuan, $harga);
	if ($nama_key_hpp !== '' && !empty($map['by_nama_key'][$nama_key_hpp])) {
		return $map['by_nama_key'][$nama_key_hpp][0];
	}

	$n_only = persediaan_recalculate_normalize_nama($nama);
	if ($n_only === '' || empty($map['by_nama'][$n_only])) {
		return null;
	}

	foreach ($map['by_nama'][$n_only] as $row) {
		if (persediaan_recalculate_satuan_cocok_pembelian($row->satuan, $satuan)
			&& persediaan_recalculate_harga_cocok($row->hpp, $harga)) {
			return $row;
		}
	}

	return null;
}

/**
 * Alasan baris penjualan tidak terdeteksi di map (untuk log import).
 */
function persediaan_recalculate_alasan_penjualan_tidak_ketemu_map($row_penjualan, $map)
{
	$nama = isset($row_penjualan->nama_barang) ? $row_penjualan->nama_barang : '';
	$satuan = isset($row_penjualan->satuan) ? $row_penjualan->satuan : '';
	$harga = isset($row_penjualan->harga_satuan) ? $row_penjualan->harga_satuan : '';

	$sync_key = persediaan_recalculate_sync_nama_satuan_hpp_key($nama, $satuan, $harga);
	$legacy_key = persediaan_recalculate_nama_satuan_hpp_key($nama, $satuan, $harga);
	$nama_simpan = persediaan_recalculate_sanitize_nama_persediaan($nama);
	$satuan_simpan = persediaan_recalculate_sanitize_satuan_persediaan($satuan);

	$alasan = 'Tidak cocok di map persediaan bulan ini.';
	if ($sync_key !== $legacy_key) {
		$alasan .= ' Kunci lookup berbeda: sync="' . $sync_key . '" vs legacy="' . $legacy_key . '"'
			. ' (nama disimpan="' . $nama_simpan . '", satuan disimpan="' . $satuan_simpan . '").';
	} else {
		$alasan .= ' Kunci="' . $sync_key . '".';
	}

	if ($sync_key !== '' && !empty($map['by_sync_key'][$sync_key]) && count($map['by_sync_key'][$sync_key]) > 1) {
		$alasan .= ' Ada ' . count($map['by_sync_key'][$sync_key]) . ' baris persediaan duplikat dengan kunci sama.';
	}

	return $alasan;
}

/**
 * Cari baris persediaan untuk baris pembelian
 * (uraian+satuan+harga_satuan+spop = namabarang+satuan+hpp+spop, bulan tanggal_beli sama).
 */
function persediaan_recalculate_find_persediaan_for_pembelian($row_pembelian, $map)
{
	if (empty($map) || empty($row_pembelian)) {
		return null;
	}

	$nama = isset($row_pembelian->uraian) ? $row_pembelian->uraian : '';
	$satuan = isset($row_pembelian->satuan) ? $row_pembelian->satuan : '';
	$harga = isset($row_pembelian->harga_satuan) ? $row_pembelian->harga_satuan : '';
	$spop = isset($row_pembelian->spop) ? $row_pembelian->spop : '';

	$nama_var = array(
		persediaan_recalculate_sanitize_nama_persediaan($nama),
		$nama,
	);
	$satuan_var = array(
		persediaan_recalculate_sanitize_satuan_persediaan($satuan),
		$satuan,
	);
	$nama_var = array_values(array_unique($nama_var, SORT_REGULAR));
	$satuan_var = array_values(array_unique($satuan_var, SORT_REGULAR));

	foreach ($nama_var as $n_try) {
		foreach ($satuan_var as $s_try) {
			$key = persediaan_recalculate_sync_pembelian_persediaan_key($n_try, $s_try, $harga, $spop);
			if ($key !== '' && !empty($map['by_pembelian_sync_key'][$key])) {
				return $map['by_pembelian_sync_key'][$key][0];
			}
		}
	}

	$n_only = persediaan_recalculate_normalize_nama($nama);
	if ($n_only === '' || empty($map['by_nama'][$n_only])) {
		return null;
	}

	foreach ($map['by_nama'][$n_only] as $row) {
		if (!persediaan_recalculate_spop_cocok(
			isset($row->spop) ? $row->spop : '',
			$spop
		)) {
			continue;
		}
		if (persediaan_recalculate_satuan_cocok_pembelian($row->satuan, $satuan)
			&& persediaan_recalculate_harga_cocok($row->hpp, $harga)) {
			return $row;
		}
	}

	return null;
}

/**
 * Hitung baris pembelian/penjualan bulan terpilih yang sudah / belum ada di persediaan (tanggal_beli sama).
 */
function persediaan_recalculate_analisa_import_coverage($CI, $ctx)
{
	$CI->load->helper('persediaan_display');
	$map = persediaan_recalculate_build_map_persediaan_bulan($CI, $ctx['tanggal_beli']);

	$out = array(
		'pembelian_total' => 0,
		'pembelian_sudah_ada' => 0,
		'pembelian_belum_ada' => 0,
		'pembelian_jasa_total' => 0,
		'pembelian_jasa_sudah_ada' => 0,
		'pembelian_jasa_belum_ada' => 0,
		'penjualan_total' => 0,
		'penjualan_sudah_ada' => 0,
		'penjualan_belum_ada' => 0,
	);

	foreach (array('tbl_pembelian' => 'pembelian', 'tbl_pembelian_jasa' => 'pembelian_jasa') as $tabel => $prefix) {
		if (!$CI->db->table_exists($tabel)) {
			continue;
		}
		$list = $CI->db->query(
			"SELECT * FROM `{$tabel}`
			WHERE `tgl_po` IS NOT NULL AND `tgl_po` <> '0000-00-00'
			AND DATE(`tgl_po`) >= ? AND DATE(`tgl_po`) <= ?
			ORDER BY `id` ASC",
			array($ctx['tgl_awal'], $ctx['tgl_akhir'])
		)->result();

		$out[$prefix . '_total'] = count($list);
		foreach ($list as $row) {
			if (persediaan_recalculate_find_persediaan_for_pembelian($row, $map)) {
				$out[$prefix . '_sudah_ada']++;
			} else {
				$out[$prefix . '_belum_ada']++;
			}
		}
	}

	if ($CI->db->table_exists('tbl_penjualan')) {
		$list_penj = $CI->db->query(
			"SELECT * FROM `tbl_penjualan`
			WHERE DATE(`tgl_jual`) >= ? AND DATE(`tgl_jual`) <= ?
			ORDER BY `id` ASC",
			array($ctx['tgl_awal'], $ctx['tgl_akhir'])
		)->result();
		$out['penjualan_total'] = count($list_penj);
		foreach ($list_penj as $row) {
			if (persediaan_recalculate_find_persediaan_for_sync($row, $map)) {
				$out['penjualan_sudah_ada']++;
			} else {
				$out['penjualan_belum_ada']++;
			}
		}
	}

	$out['pembelian_all_total'] = (int) $out['pembelian_total'] + (int) $out['pembelian_jasa_total'];
	$out['pembelian_all_sudah_ada'] = (int) $out['pembelian_sudah_ada'] + (int) $out['pembelian_jasa_sudah_ada'];
	$out['pembelian_all_belum_ada'] = (int) $out['pembelian_belum_ada'] + (int) $out['pembelian_jasa_belum_ada'];

	return $out;
}

/**
 * Apakah field beli persediaan sudah sama dengan total jumlah pembelian bulan tersebut.
 */
function persediaan_recalculate_beli_persediaan_sudah_cocok($CI, $pers_row, $tgl_awal, $tgl_akhir)
{
	$CI->load->helper('persediaan_display');
	$beli_field = max(0, (int) floor(persediaan_parse_angka(isset($pers_row->beli) ? $pers_row->beli : 0)));
	$beli_hitung = persediaan_recalculate_hitung_beli_row_by_kategori($CI, $pers_row, $tgl_awal, $tgl_akhir);
	return $beli_hitung > 0 && $beli_field === (int) $beli_hitung;
}

function persediaan_recalculate_pembelian_persediaan_ref_from_row($row)
{
	return array(
		'id_persediaan' => (int) $row->id,
		'uuid_barang' => trim((string) $row->uuid_barang),
		'uuid_persediaan' => trim((string) $row->uuid_persediaan),
	);
}

/**
 * Update uuid_persediaan (+ id_persediaan_barang) di tbl_penjualan agar sesuai persediaan bulan yang sama.
 */
function persediaan_recalculate_sync_uuid_penjualan_bulan($CI, $ctx)
{
	$map = persediaan_recalculate_build_map_persediaan_bulan($CI, $ctx['tanggal_beli']);

	$list = $CI->db->query(
		"SELECT `id`, `uuid_persediaan`, `id_persediaan_barang`, `uuid_barang`,
			`nama_barang`, `satuan`, `harga_satuan`, `tgl_jual`
		FROM `tbl_penjualan`
		WHERE DATE(`tgl_jual`) >= ? AND DATE(`tgl_jual`) <= ?
		ORDER BY `id` ASC",
		array($ctx['tgl_awal'], $ctx['tgl_akhir'])
	)->result();

	$stats = array(
		'total' => count($list),
		'updated' => 0,
		'sudah_sesuai' => 0,
		'tidak_ditemukan' => 0,
		'gagal' => 0,
		'log' => array(),
	);

	if (empty($list)) {
		return $stats;
	}

	$has_uuid_barang = $CI->db->field_exists('uuid_barang', 'tbl_penjualan');
	$db_debug = $CI->db->db_debug;
	$CI->db->db_debug = false;

	foreach ($list as $row_penjualan) {
		$id_penj = (int) $row_penjualan->id;
		$pers = persediaan_recalculate_find_persediaan_for_sync($row_penjualan, $map);
		if (!$pers) {
			$stats['tidak_ditemukan']++;
			$stats['log'][$id_penj] = array(
				'status' => 'tidak_sync',
				'alasan' => 'Tidak ada di persediaan bulan ' . $ctx['bulan_label']
					. ' (nama_barang + satuan + harga_satuan harus sama dengan namabarang + satuan + hpp)',
			);
			continue;
		}

		$uuid_baru = trim((string) $pers->uuid_persediaan);
		$id_baru = (int) $pers->id;
		$uuid_barang_baru = $has_uuid_barang ? trim((string) $pers->uuid_barang) : '';

		if ($uuid_baru === '') {
			$stats['gagal']++;
			$stats['log'][$id_penj] = array(
				'status' => 'gagal',
				'alasan' => 'Persediaan cocok ditemukan (id ' . $id_baru . ') tetapi uuid_persediaan kosong',
			);
			continue;
		}

		if (persediaan_recalculate_penjualan_uuid_sudah_cocok($row_penjualan, $pers, $has_uuid_barang)) {
			$stats['sudah_sesuai']++;
			$stats['log'][$id_penj] = array(
				'status' => 'sudah_sesuai',
				'alasan' => 'Sudah sinkronisasi dengan persediaan id=' . $id_baru
					. ', uuid_barang=' . $uuid_barang_baru
					. ', uuid_persediaan=' . $uuid_baru,
			);
			continue;
		}

		$update = array(
			'uuid_persediaan' => $uuid_baru,
			'id_persediaan_barang' => $id_baru,
		);
		if ($has_uuid_barang && $uuid_barang_baru !== '') {
			$update['uuid_barang'] = $uuid_barang_baru;
		}

		$CI->db->where('id', $id_penj);
		if ($CI->db->update('tbl_penjualan', $update)) {
			$stats['updated']++;
			$stats['log'][$id_penj] = array(
				'status' => 'synced',
				'alasan' => 'Sudah sinkronisasi dengan persediaan id=' . $id_baru
					. ', uuid_barang=' . $uuid_barang_baru
					. ', uuid_persediaan=' . $uuid_baru,
				'id_persediaan' => $id_baru,
				'uuid_barang' => $uuid_barang_baru,
				'uuid_persediaan' => $uuid_baru,
			);
		} else {
			$stats['gagal']++;
			$err = $CI->db->error();
			$pesan = isset($err['message']) ? trim((string) $err['message']) : 'Gagal update tbl_penjualan';
			$stats['log'][$id_penj] = array(
				'status' => 'gagal',
				'alasan' => $pesan,
			);
		}
	}

	$CI->db->db_debug = $db_debug;

	return $stats;
}

/**
 * Fase penjualan sekali jalan: semua tbl_penjualan bulan terpilih → persediaan (penjualan + kolom unit).
 */
function persediaan_recalculate_penjualan_phase_once($CI, $ctx)
{
	$CI->load->helper('persediaan_display');

	$import_penjualan = persediaan_recalculate_import_penjualan_tidak_sync($CI, $ctx);
	$sync_uuid = persediaan_recalculate_sync_uuid_penjualan_bulan($CI, $ctx);
	$CI->session->set_userdata('recalc_penjualan_sync_' . $ctx['bulan'], array(
		'import' => $import_penjualan,
		'sync' => $sync_uuid,
	));

	$reset_info = persediaan_recalculate_reset_penjualan_persediaan_bulan($CI, $ctx['tanggal_beli']);
	$map = persediaan_recalculate_build_map_persediaan_bulan($CI, $ctx['tanggal_beli']);

	$list = $CI->db->query(
		"SELECT * FROM `tbl_penjualan`
		WHERE DATE(`tgl_jual`) >= ? AND DATE(`tgl_jual`) <= ?
		ORDER BY `id` ASC",
		array($ctx['tgl_awal'], $ctx['tgl_akhir'])
	)->result();

	$batch_items = array();
	$batch_ok = 0;
	$batch_skip = 0;
	$batch_created = 0;
	$accum = array();

	foreach ($list as $row_penjualan) {
		$jumlah = persediaan_recalculate_parse_jumlah_penjualan($row_penjualan->jumlah);
		if ($jumlah <= 0) {
			$batch_skip++;
			continue;
		}

		$match = persediaan_recalculate_match_penjualan_ke_persediaan($CI, $row_penjualan, $map);
		if (!$match['ok']) {
			$created = persediaan_recalculate_ensure_persediaan_dari_penjualan($CI, $ctx, $row_penjualan, $map);
			if (!empty($created['ok'])) {
				$batch_created++;
				$match = persediaan_recalculate_match_penjualan_ke_persediaan($CI, $row_penjualan, $map);
				if ($match['ok']) {
					$match['metode'] = 'auto_create+' . $match['metode'];
				}
			}
		}

		if (!$match['ok']) {
			$batch_skip++;
			if (count($batch_items) < 20) {
				$batch_items[] = array(
					'status' => 'SKIP',
					'fase' => 'penjualan',
					'id_penjualan' => (int) $row_penjualan->id,
					'nama_barang' => $row_penjualan->nama_barang,
					'keterangan' => $match['alasan'],
				);
			}
			continue;
		}

		$kolom_unit = penjualan_resolve_kolom_persediaan_unit_dari_penjualan($CI, $row_penjualan, false);
		$pers_row = $match['row'];
		$pers_id = (int) $pers_row->id;

		if (!isset($accum[$pers_id])) {
			$cur = $CI->db->where('id', $pers_id)->limit(1)->get('persediaan')->row();
			$accum[$pers_id] = array(
				'penjualan' => 0,
				'units' => array(),
			);
			foreach (penjualan_persediaan_kolom_unit_existing($CI) as $kolom) {
				$db_col = persediaan_resolve_db_field_name($CI, $kolom);
				$accum[$pers_id]['units'][$db_col] = penjualan_get_nilai_kolom_unit($cur, $db_col);
			}
		}

		$accum[$pers_id]['penjualan'] += $jumlah;
		if ($kolom_unit !== null) {
			$db_col = persediaan_resolve_db_field_name($CI, $kolom_unit);
			if (!isset($accum[$pers_id]['units'][$db_col])) {
				$accum[$pers_id]['units'][$db_col] = 0;
			}
			$accum[$pers_id]['units'][$db_col] += $jumlah;
		}

		$batch_ok++;
		if (count($batch_items) < 20) {
			$batch_items[] = array(
				'status' => 'OK',
				'fase' => 'penjualan',
				'id_penjualan' => (int) $row_penjualan->id,
				'id_persediaan' => $pers_id,
				'namabarang' => $pers_row->namabarang,
				'jumlah' => $jumlah,
				'unit' => isset($row_penjualan->unit) ? $row_penjualan->unit : '',
				'kolom_unit' => $kolom_unit,
				'metode' => $match['metode'],
				'keterangan' => 'penjualan+=' . $jumlah . ' → ' . ($kolom_unit ? $kolom_unit : 'global'),
			);
		}
	}

	$batch_update = persediaan_recalculate_flush_accum_to_db($CI, $accum);
	$total_10_info = persediaan_recalculate_refresh_total_10_penjualan_bulan($CI, $ctx);
	$with_penjualan = 0;
	foreach ($accum as $data) {
		if ((int) $data['penjualan'] > 0) {
			$with_penjualan++;
		}
	}

	$import_pesan = '';
	if ((int) $import_penjualan['created'] > 0 || (int) $import_penjualan['failed'] > 0) {
		$import_pesan = ' Import penjualan ke persediaan: ' . (int) $import_penjualan['created'] . ' baris baru'
			. ((int) $import_penjualan['failed'] > 0 ? ', ' . (int) $import_penjualan['failed'] . ' gagal' : '')
			. ((int) (isset($import_penjualan['skipped_sudah_ada']) ? $import_penjualan['skipped_sudah_ada'] : 0) > 0
				? ', ' . (int) $import_penjualan['skipped_sudah_ada'] . ' sudah ada (tidak di-copy ulang)' : '')
			. '.';
	}

	return array(
		'ok' => true,
		'phase' => 'penjualan',
		'done' => true,
		'offset_selesai' => count($list),
		'total_phase' => (int) $ctx['total_penjualan'],
		'total_penjualan' => (int) $ctx['total_penjualan'],
		'batch_ok' => $batch_ok,
		'batch_skip' => $batch_skip,
		'batch_created' => $batch_created,
		'batch_update_persediaan' => $batch_update,
		'total_10_penjualan' => $total_10_info,
		'with_penjualan' => $with_penjualan,
		'import_penjualan' => $import_penjualan,
		'sync_uuid_penjualan' => $sync_uuid,
		'reset' => $reset_info,
		'items' => $batch_items,
		'pesan' => 'Penjualan ' . $ctx['bulan_label'] . ': import/sync dulu, lalu ' . count($list) . ' record penjualan diproses, '
			. $batch_ok . ' berhasil di-update, ' . $batch_skip . ' dilewati'
			. ($batch_created > 0 ? ', ' . $batch_created . ' baris persediaan baru ditambahkan' : '') . '. '
			. $with_penjualan . ' baris persediaan dapat penjualan > 0.'
			. ' Update total_10 (sa+beli-penjualan): ' . (int) $total_10_info['updated'] . ' baris.'
			. $import_pesan
			. ' Sinkron uuid penjualan: ' . (int) $sync_uuid['updated'] . ' di-update, '
			. (int) $sync_uuid['sudah_sesuai'] . ' sudah sesuai, '
			. (int) $sync_uuid['tidak_ditemukan'] . ' tidak cocok (nama+satuan+harga)'
			. ((int) $sync_uuid['gagal'] > 0 ? ', ' . (int) $sync_uuid['gagal'] . ' gagal update' : '') . '.',
	);
}

function persediaan_recalculate_penjualan_batch($CI, $bulan, $offset, $limit)
{
	$CI->load->helper('persediaan_display');
	$ctx = persediaan_recalculate_penjualan_context($CI, $bulan);
	if (!$ctx['ok']) {
		return $ctx;
	}

	$session_reset_key = 'recalc_penj_reset_' . $bulan;
	$session_stats_key = 'recalc_penj_stats_' . $bulan;
	$reset_info = null;

	if ($offset === 0) {
		$CI->session->unset_userdata($session_reset_key);
		$CI->session->unset_userdata($session_stats_key);
	}

	$stats = $CI->session->userdata($session_stats_key);
	if (!is_array($stats)) {
		$stats = array(
			'total_ok' => 0,
			'total_skip' => 0,
			'total_update_persediaan' => 0,
		);
	}

	if (!$CI->session->userdata($session_reset_key)) {
		$reset_info = persediaan_recalculate_reset_penjualan_persediaan_bulan($CI, $ctx['tanggal_beli']);
		$CI->session->set_userdata($session_reset_key, 1);
	}

	$map = persediaan_recalculate_build_map_persediaan_bulan($CI, $ctx['tanggal_beli']);

	$list = $CI->db->query(
		"SELECT * FROM `tbl_penjualan`
		WHERE DATE(`tgl_jual`) >= ? AND DATE(`tgl_jual`) <= ?
		ORDER BY `id` ASC
		LIMIT " . (int) $limit . " OFFSET " . (int) $offset,
		array($ctx['tgl_awal'], $ctx['tgl_akhir'])
	)->result();

	$batch_items = array();
	$batch_ok = 0;
	$batch_skip = 0;
	$accum = array();

	foreach ($list as $row_penjualan) {
		$jumlah = persediaan_recalculate_parse_jumlah_penjualan($row_penjualan->jumlah);
		if ($jumlah <= 0) {
			$batch_skip++;
			$batch_items[] = array(
				'status' => 'SKIP',
				'id_penjualan' => (int) $row_penjualan->id,
				'nama_barang' => $row_penjualan->nama_barang,
				'keterangan' => 'Jumlah penjualan 0',
			);
			continue;
		}

		$match = persediaan_recalculate_match_penjualan_ke_persediaan($CI, $row_penjualan, $map);
		if (!$match['ok']) {
			$batch_skip++;
			$batch_items[] = array(
				'status' => 'SKIP',
				'id_penjualan' => (int) $row_penjualan->id,
				'nama_barang' => $row_penjualan->nama_barang,
				'satuan' => $row_penjualan->satuan,
				'harga_satuan' => $row_penjualan->harga_satuan,
				'keterangan' => $match['alasan'],
			);
			continue;
		}

		$uuid_unit = isset($row_penjualan->uuid_unit) ? trim((string) $row_penjualan->uuid_unit) : '';
		$kolom_unit = penjualan_resolve_kolom_persediaan_unit_dari_penjualan($CI, $row_penjualan);
		$pers_row = $match['row'];
		$pers_id = (int) $pers_row->id;

		if (!isset($accum[$pers_id])) {
			$cur = $CI->db->where('id', $pers_id)->limit(1)->get('persediaan')->row();
			$accum[$pers_id] = array(
				'penjualan' => persediaan_parse_angka($cur ? $cur->penjualan : 0),
				'units' => array(),
			);
			foreach (penjualan_persediaan_kolom_unit_existing($CI) as $kolom) {
				$db_col = persediaan_resolve_db_field_name($CI, $kolom);
				$accum[$pers_id]['units'][$db_col] = penjualan_get_nilai_kolom_unit($cur, $db_col);
			}
		}

		$accum[$pers_id]['penjualan'] += $jumlah;
		if ($kolom_unit !== null) {
			$db_col = persediaan_resolve_db_field_name($CI, $kolom_unit);
			if (!isset($accum[$pers_id]['units'][$db_col])) {
				$accum[$pers_id]['units'][$db_col] = 0;
			}
			$accum[$pers_id]['units'][$db_col] += $jumlah;
		}

		$batch_ok++;
		$unit_note = ($uuid_unit !== '' && $kolom_unit === null)
			? ' (unit tidak dipetakan ke kolom)'
			: '';
		$batch_items[] = array(
			'status' => 'OK',
			'id_penjualan' => (int) $row_penjualan->id,
			'id_persediaan' => $pers_id,
			'namabarang' => $pers_row->namabarang,
			'satuan' => $pers_row->satuan,
			'hpp' => $pers_row->hpp,
			'jumlah' => $jumlah,
			'unit' => isset($row_penjualan->unit) ? $row_penjualan->unit : '',
			'kolom_unit' => $kolom_unit,
			'metode' => $match['metode'],
			'keterangan' => 'Tambah penjualan=' . $jumlah . ' ke kolom ' . ($kolom_unit ? $kolom_unit : '(global)') . $unit_note,
		);
	}

	$batch_update = persediaan_recalculate_flush_accum_to_db($CI, $accum);

	$offset_selesai = $offset + count($list);
	$total_penjualan = (int) $ctx['total_penjualan'];
	$done = ($offset_selesai >= $total_penjualan);

	$stats['total_ok'] += $batch_ok;
	$stats['total_skip'] += $batch_skip;
	$stats['total_update_persediaan'] += $batch_update;
	$CI->session->set_userdata($session_stats_key, $stats);

	$summary = null;
	if ($done) {
		$summary = array(
			'bulan' => $ctx['bulan'],
			'bulan_label' => $ctx['bulan_label'],
			'tanggal_beli' => $ctx['tanggal_beli'],
			'tgl_awal' => $ctx['tgl_awal'],
			'tgl_akhir' => $ctx['tgl_akhir'],
			'total_persediaan' => $ctx['total_persediaan'],
			'total_penjualan' => $total_penjualan,
			'total_ok' => (int) $stats['total_ok'],
			'total_skip' => (int) $stats['total_skip'],
			'total_update_persediaan' => (int) $stats['total_update_persediaan'],
			'reset' => $reset_info,
		);
		$CI->session->unset_userdata($session_reset_key);
		$CI->session->unset_userdata($session_stats_key);
	}

	return array(
		'ok' => true,
		'done' => $done,
		'offset_selesai' => $offset_selesai,
		'total_penjualan' => $total_penjualan,
		'total_persediaan' => $ctx['total_persediaan'],
		'batch_ok' => $batch_ok,
		'batch_skip' => $batch_skip,
		'batch_update_persediaan' => $batch_update,
		'reset' => ($offset === 0 && $reset_info !== null) ? $reset_info : null,
		'items' => $batch_items,
		'last_five' => count($batch_items) > 5 ? array_slice($batch_items, -5) : $batch_items,
		'summary' => $summary,
	);
}

/**
 * Konteks lengkap recalculate (pembelian + penjualan + persediaan).
 */
function persediaan_recalculate_full_context($CI, $bulan)
{
	$ctx = persediaan_recalculate_penjualan_context($CI, $bulan);
	if (!$ctx['ok']) {
		return $ctx;
	}

	$tgl_awal = $ctx['tgl_awal'];
	$tgl_akhir = $ctx['tgl_akhir'];
	$total_pembelian = 0;
	$total_pembelian_jasa = 0;

	if ($CI->db->table_exists('tbl_pembelian')) {
		$row = $CI->db->query(
			"SELECT COUNT(*) AS jml FROM `tbl_pembelian`
			WHERE `tgl_po` IS NOT NULL AND `tgl_po` <> '0000-00-00'
			AND DATE(`tgl_po`) >= ? AND DATE(`tgl_po`) <= ?",
			array($tgl_awal, $tgl_akhir)
		)->row();
		$total_pembelian = $row ? (int) $row->jml : 0;
	}

	if ($CI->db->table_exists('tbl_pembelian_jasa')) {
		$row = $CI->db->query(
			"SELECT COUNT(*) AS jml FROM `tbl_pembelian_jasa`
			WHERE `tgl_po` IS NOT NULL AND `tgl_po` <> '0000-00-00'
			AND DATE(`tgl_po`) >= ? AND DATE(`tgl_po`) <= ?",
			array($tgl_awal, $tgl_akhir)
		)->row();
		$total_pembelian_jasa = $row ? (int) $row->jml : 0;
	}

	$total_pembelian_all = $total_pembelian + $total_pembelian_jasa;
	$has_pembelian = ($total_pembelian_all > 0);
	$has_penjualan = ((int) $ctx['total_penjualan'] > 0);
	$can_proceed = ($has_pembelian || $has_penjualan);

	$ctx['total_pembelian'] = $total_pembelian;
	$ctx['total_pembelian_jasa'] = $total_pembelian_jasa;
	$ctx['total_pembelian_all'] = $total_pembelian_all;
	$ctx['has_pembelian'] = $has_pembelian;
	$ctx['has_penjualan'] = $has_penjualan;
	$ctx['can_proceed'] = $can_proceed;

	if (!$can_proceed) {
		$ctx['message_empty'] = 'Tidak ada data di tbl_pembelian, tbl_pembelian_jasa, maupun tbl_penjualan untuk bulan '
			. $ctx['bulan_label'] . '.';
	}

	return $ctx;
}

/**
 * Analisa sebelum recalculate (tab Persediaan).
 */
function persediaan_recalculate_full_analisa($CI, $bulan)
{
	$ctx = persediaan_recalculate_full_context($CI, $bulan);
	if (!$ctx['ok']) {
		return array('ok' => false, 'message' => $ctx['message']);
	}

	$penjelasan = array();
	if ($ctx['has_pembelian']) {
		$penjelasan[] = 'Update kolom <strong>beli</strong> dari tbl_pembelian (' . $ctx['total_pembelian']
			. ' record) + tbl_pembelian_jasa (' . $ctx['total_pembelian_jasa'] . ' record) '
			. '— cocokkan uraian+satuan+harga_satuan+tgl_po = namabarang+satuan+hpp+tanggal_beli.';
	}
	if ($ctx['has_penjualan']) {
		$penjelasan[] = 'Update kolom <strong>penjualan</strong> + kolom <strong>unit</strong> dari tbl_penjualan ('
			. $ctx['total_penjualan'] . ' record, filter tgl_jual bulan ini) — cocokkan nama_barang+satuan+harga_satuan = namabarang+satuan+hpp, jumlah per kolom unit (field unit). '
			. 'total_10 = sa + beli - penjualan.';
	}
	$coverage = persediaan_recalculate_analisa_import_coverage($CI, $ctx);

	if ((int) $ctx['total_persediaan'] === 0) {
		$penjelasan[] = 'Belum ada data persediaan bulan ini (tanggal_beli ' . $ctx['tanggal_beli'] . ') — recalculate akan <strong>menambah</strong> baris dari pembelian/penjualan yang belum ada.';
	}
	$penjelasan[] = 'Sebelum recalculate: cek semua <strong>tbl_pembelian</strong> (uraian+satuan+harga_satuan, bulan tgl_po) dan <strong>tbl_penjualan</strong> (nama_barang+satuan+harga_satuan, bulan tgl_jual) sudah ada di persediaan (namabarang+satuan+hpp, tanggal_beli bulan sama). Yang belum ada akan <strong>ditambah sekali</strong> tanpa duplikasi.';

	return array(
		'ok' => true,
		'bulan' => $ctx['bulan'],
		'bulan_label' => $ctx['bulan_label'],
		'tanggal_beli' => $ctx['tanggal_beli'],
		'tgl_awal' => $ctx['tgl_awal'],
		'tgl_akhir' => $ctx['tgl_akhir'],
		'total_persediaan' => (int) $ctx['total_persediaan'],
		'total_pembelian' => (int) $ctx['total_pembelian'],
		'total_pembelian_jasa' => (int) $ctx['total_pembelian_jasa'],
		'total_pembelian_all' => (int) $ctx['total_pembelian_all'],
		'total_penjualan' => (int) $ctx['total_penjualan'],
		'has_pembelian' => $ctx['has_pembelian'],
		'has_penjualan' => $ctx['has_penjualan'],
		'can_proceed' => $ctx['can_proceed'],
		'pembelian_sudah_ada' => (int) $coverage['pembelian_all_sudah_ada'],
		'pembelian_belum_ada' => (int) $coverage['pembelian_all_belum_ada'],
		'penjualan_sudah_ada' => (int) $coverage['penjualan_sudah_ada'],
		'penjualan_belum_ada' => (int) $coverage['penjualan_belum_ada'],
		'penjelasan' => implode('<br/>', $penjelasan),
		'message_empty' => isset($ctx['message_empty']) ? $ctx['message_empty'] : '',
	);
}

function persediaan_recalculate_sum_pembelian_table_for_row($CI, $tabel, $tgl_awal, $tgl_akhir, $row)
{
	$CI->load->helper('persediaan_display');

	$nama_norm = persediaan_recalculate_normalize_nama(isset($row->namabarang) ? $row->namabarang : '');
	$satuan_pers = isset($row->satuan) ? $row->satuan : '';
	$hpp_pers = isset($row->hpp) ? $row->hpp : '';
	$spop_pers = isset($row->spop) ? $row->spop : '';

	if ($nama_norm === '' || trim((string) $satuan_pers) === '') {
		return 0;
	}

	$list = persediaan_recalculate_load_pembelian_bulan_rows($CI, $tabel, $tgl_awal, $tgl_akhir);
	$total = 0;

	foreach ($list as $r) {
		$uraian_norm = persediaan_recalculate_normalize_nama(isset($r->uraian) ? $r->uraian : '');
		if ($uraian_norm === '' || $uraian_norm !== $nama_norm) {
			continue;
		}
		if (!persediaan_recalculate_spop_cocok($spop_pers, isset($r->spop) ? $r->spop : '')) {
			continue;
		}
		if (!persediaan_recalculate_satuan_cocok_pembelian($satuan_pers, isset($r->satuan) ? $r->satuan : '')) {
			continue;
		}
		if (!persediaan_recalculate_harga_cocok($hpp_pers, isset($r->harga_satuan) ? $r->harga_satuan : '')) {
			continue;
		}

		$total += max(0, (int) floor(persediaan_parse_angka(isset($r->jumlah) ? $r->jumlah : 0)));
	}

	return max(0, (int) $total);
}

function persediaan_recalculate_hitung_beli_row($CI, $row, $tgl_awal, $tgl_akhir)
{
	$jumlah = persediaan_recalculate_sum_pembelian_table_for_row($CI, 'tbl_pembelian', $tgl_awal, $tgl_akhir, $row);
	$jumlah += persediaan_recalculate_sum_pembelian_table_for_row($CI, 'tbl_pembelian_jasa', $tgl_awal, $tgl_akhir, $row);
	return max(0, (int) $jumlah);
}

function persediaan_recalculate_hitung_beli_row_by_kategori($CI, $row, $tgl_awal, $tgl_akhir)
{
	if (persediaan_row_is_kategori_jasa($row)) {
		$beli = persediaan_recalculate_sum_pembelian_table_for_row($CI, 'tbl_pembelian_jasa', $tgl_awal, $tgl_akhir, $row);
		if ($beli <= 0) {
			$beli = persediaan_recalculate_sum_pembelian_table_for_row($CI, 'tbl_pembelian', $tgl_awal, $tgl_akhir, $row);
		}
	} else {
		$beli = persediaan_recalculate_sum_pembelian_table_for_row($CI, 'tbl_pembelian', $tgl_awal, $tgl_akhir, $row);
		if ($beli <= 0) {
			$beli = persediaan_recalculate_sum_pembelian_table_for_row($CI, 'tbl_pembelian_jasa', $tgl_awal, $tgl_akhir, $row);
		}
	}

	return max(0, (int) $beli);
}

function persediaan_recalculate_reset_beli_persediaan_bulan($CI, $tanggal_beli)
{
	$CI->load->helper('persediaan_display');
	$rows = $CI->db->query(
		"SELECT `id`,`sa`,`hpp` FROM `persediaan` WHERE `tanggal_beli` = ?",
		array($tanggal_beli)
	)->result();

	$count = 0;
	foreach ($rows as $row) {
		$sa = persediaan_parse_angka($row->sa);
		$hpp = persediaan_parse_angka($row->hpp);
		$total_10 = $sa;
		$nilai = $total_10 * $hpp;
		$CI->db->where('id', (int) $row->id);
		$CI->db->update('persediaan', array(
			'beli' => '0',
			'total_10' => (string) (int) floor($total_10),
			'nilai_persediaan' => (string) (int) floor($nilai),
			'tuj' => (string) (int) floor($total_10),
		));
		$count++;
	}

	return array('record_direset' => $count);
}

function persediaan_recalculate_update_beli_row($CI, $row, $beli_angka)
{
	$CI->load->helper('persediaan_display');
	$beli = max(0, (int) $beli_angka);
	$sa = persediaan_parse_angka($row->sa);
	$hpp = persediaan_parse_angka($row->hpp);
	$total_10 = $sa + $beli;
	$nilai = $total_10 * $hpp;

	$beli_t = (string) (int) floor($beli);
	$total_t = (string) (int) floor($total_10);
	$nilai_t = (string) (int) floor($nilai);

	$CI->db->where('id', (int) $row->id);
	$CI->db->update('persediaan', array(
		'beli' => $beli_t,
		'total_10' => $total_t,
		'nilai_persediaan' => $nilai_t,
		'tuj' => $total_t,
	));

	return array(
		'beli' => $beli_t,
		'total_10' => $total_t,
		'nilai_persediaan' => $nilai_t,
	);
}

function persediaan_recalculate_build_pembelian_agg_map($CI, $tgl_awal, $tgl_akhir)
{
	$CI->load->helper('persediaan_display');
	$map = array();
	$total_rows_pembelian = 0;

	foreach (array('tbl_pembelian', 'tbl_pembelian_jasa') as $tbl) {
		if (!$CI->db->table_exists($tbl)) {
			continue;
		}

		$has_uuid_pers = $CI->db->field_exists('uuid_persediaan', $tbl);
		$uuid_pers_sql = $has_uuid_pers
			? 'TRIM(COALESCE(`uuid_persediaan`, \'\')) AS uuid_persediaan'
			: '\'\' AS uuid_persediaan';
		$has_spop = $CI->db->field_exists('spop', $tbl);
		$spop_sql = $has_spop
			? 'TRIM(COALESCE(`spop`, \'\')) AS spop'
			: "'' AS spop";
		$group_spop = $has_spop ? ', spop' : '';

		$sql = "SELECT TRIM(COALESCE(`uuid_barang`, '')) AS uuid_barang,
			{$uuid_pers_sql},
			{$spop_sql},
			TRIM(COALESCE(`uraian`, '')) AS uraian,
			TRIM(COALESCE(`satuan`, '')) AS satuan,
			TRIM(COALESCE(`harga_satuan`, '')) AS harga_satuan,
			SUM(CAST(`jumlah` AS SIGNED)) AS jml
			FROM `{$tbl}`
			WHERE `tgl_po` IS NOT NULL AND `tgl_po` <> '0000-00-00'
			AND DATE(`tgl_po`) >= ? AND DATE(`tgl_po`) <= ?
			GROUP BY uuid_barang, uuid_persediaan, uraian, satuan, harga_satuan{$group_spop}";

		$rows = $CI->db->query($sql, array($tgl_awal, $tgl_akhir))->result();
		$total_rows_pembelian += count($rows);

		foreach ($rows as $r) {
			$jml = (int) $r->jml;
			if ($jml <= 0) {
				continue;
			}

			$satuan = trim((string) $r->satuan);
			$satuan_key = persediaan_recalculate_satuan_key($satuan);
			$hpp_key = (string) persediaan_parse_angka($r->harga_satuan);
			$spop_key = persediaan_recalculate_normalize_spop(isset($r->spop) ? $r->spop : '');
			if ($satuan_key === '') {
				continue;
			}

			$uuid_b = trim((string) $r->uuid_barang);
			if ($uuid_b !== '') {
				$k = 'ub|' . $uuid_b . '|' . $satuan_key . '|' . $hpp_key . '|spop:' . $spop_key;
				$map[$k] = isset($map[$k]) ? $map[$k] + $jml : $jml;
			}

			$uuid_p = trim((string) $r->uuid_persediaan);
			if ($uuid_p !== '') {
				$k = 'up|' . $uuid_p . '|' . $satuan_key . '|' . $hpp_key . '|spop:' . $spop_key;
				$map[$k] = isset($map[$k]) ? $map[$k] + $jml : $jml;
			}

			$nama = trim((string) $r->uraian);
			if ($nama !== '') {
				$pk = persediaan_recalculate_sync_pembelian_persediaan_key($nama, $satuan, $r->harga_satuan, $spop_key);
				if ($pk !== '') {
					$k = 'nm|' . $pk;
					$map[$k] = isset($map[$k]) ? $map[$k] + $jml : $jml;
				}
			}
		}
	}

	return array(
		'map' => $map,
		'grup_pembelian' => $total_rows_pembelian,
	);
}

function persediaan_recalculate_lookup_beli_dari_map($map, $row)
{
	$CI = get_instance();
	$CI->load->helper('persediaan_display');

	$satuan = trim((string) $row->satuan);
	$satuan_key = persediaan_recalculate_satuan_key($satuan);
	$hpp_key = (string) persediaan_parse_angka($row->hpp);
	$spop_key = persediaan_recalculate_normalize_spop(isset($row->spop) ? $row->spop : '');
	if ($satuan_key === '') {
		return 0;
	}

	$pembelian_key = persediaan_recalculate_sync_pembelian_persediaan_key(
		$row->namabarang,
		$satuan,
		$row->hpp,
		$spop_key
	);
	if ($pembelian_key !== '') {
		$k = 'nm|' . $pembelian_key;
		if (!empty($map[$k])) {
			return (int) $map[$k];
		}
	}

	$uuid_b = trim((string) $row->uuid_barang);
	if ($uuid_b !== '') {
		$k = 'ub|' . $uuid_b . '|' . $satuan_key . '|' . $hpp_key . '|spop:' . $spop_key;
		if (!empty($map[$k])) {
			return (int) $map[$k];
		}
	}

	$uuid_p = trim((string) $row->uuid_persediaan);
	if ($uuid_p !== '') {
		$k = 'up|' . $uuid_p . '|' . $satuan_key . '|' . $hpp_key . '|spop:' . $spop_key;
		if (!empty($map[$k])) {
			return (int) $map[$k];
		}
	}

	return 0;
}

/**
 * Fase beli sekali jalan: agregasi tbl_pembelian + tbl_pembelian_jasa bulan terpilih, update semua persediaan.
 */
function persediaan_recalculate_beli_phase_once($CI, $ctx)
{
	$reset_info = persediaan_recalculate_reset_beli_persediaan_bulan($CI, $ctx['tanggal_beli']);

	$agg = persediaan_recalculate_build_pembelian_agg_map($CI, $ctx['tgl_awal'], $ctx['tgl_akhir']);
	$map = $agg['map'];

	$rows = $CI->db->query(
		"SELECT * FROM `persediaan` WHERE `tanggal_beli` = ? ORDER BY `id` ASC",
		array($ctx['tanggal_beli'])
	)->result();

	$items = array();
	$updated = 0;
	$with_beli = 0;

	foreach ($rows as $row) {
		$beli_map = persediaan_recalculate_lookup_beli_dari_map($map, $row);
		$beli_strict = persediaan_recalculate_hitung_beli_row_by_kategori(
			$CI,
			$row,
			$ctx['tgl_awal'],
			$ctx['tgl_akhir']
		);
		$beli = max((int) $beli_map, (int) $beli_strict);
		$upd = persediaan_recalculate_update_beli_row($CI, $row, $beli);
		$updated++;
		if ($beli > 0) {
			$with_beli++;
		}
		if (count($items) < 15) {
			$items[] = array(
				'status' => 'OK',
				'fase' => 'beli',
				'id_persediaan' => (int) $row->id,
				'namabarang' => $row->namabarang,
				'satuan' => $row->satuan,
				'hpp' => $row->hpp,
				'beli' => $upd['beli'],
				'total_10' => $upd['total_10'],
				'keterangan' => 'beli=' . $upd['beli'] . ' dari pembelian (uraian+satuan+harga_satuan = namabarang+satuan+hpp)',
			);
		}
	}

	return array(
		'ok' => true,
		'phase' => 'beli',
		'done' => true,
		'offset_selesai' => $updated,
		'total_phase' => (int) $ctx['total_persediaan'],
		'batch_ok' => $updated,
		'batch_skip' => 0,
		'with_beli' => $with_beli,
		'grup_pembelian' => (int) $ctx['total_pembelian_all'],
		'reset_beli' => $reset_info,
		'items' => $items,
		'last_five' => count($items) > 5 ? array_slice($items, -5) : $items,
		'pesan' => 'Pembelian bulan ' . $ctx['bulan_label'] . ' diproses (' . $ctx['total_pembelian']
			. ' barang + ' . $ctx['total_pembelian_jasa'] . ' jasa). '
			. $with_beli . ' baris persediaan dapat beli > 0.',
	);
}

function persediaan_recalculate_beli_batch($CI, $ctx, $offset, $limit)
{
	// Legacy batch — tidak dipakai; redirect ke phase once.
	return persediaan_recalculate_beli_phase_once($CI, $ctx);
}

/**
 * Recalculate gabungan: fase beli (pembelian) lalu fase penjualan.
 */
function persediaan_recalculate_full_batch($CI, $bulan, $offset, $limit, $start = false)
{
	$ctx = persediaan_recalculate_full_context($CI, $bulan);
	if (!$ctx['ok']) {
		return $ctx;
	}

	if (!$ctx['can_proceed']) {
		return array('ok' => false, 'message' => $ctx['message_empty']);
	}

	$state_key = 'recalc_full_state_' . $bulan;
	$state = $CI->session->userdata($state_key);

	// Inisialisasi HANYA saat start=1 (klik Recalculate) — bukan setiap offset=0
	if ($start) {
		$CI->session->unset_userdata($state_key);
		$CI->session->unset_userdata('recalc_penj_reset_' . $bulan);
		$CI->session->unset_userdata('recalc_penj_stats_' . $bulan);
		$CI->session->unset_userdata('recalc_pembelian_import_' . $bulan);
		$CI->session->unset_userdata('recalc_pembelian_sync_' . $bulan);
		$CI->session->unset_userdata('recalc_penjualan_sync_' . $bulan);
		$state = null;
	}

	if (is_array($state) && $state['phase'] === 'beli' && !empty($state['beli_selesai'])) {
		$state['phase'] = $ctx['has_penjualan'] ? 'penjualan' : 'done';
	}

	if (!is_array($state)) {
		if ((int) $offset > 0 && !$start) {
			return array(
				'ok' => false,
				'message' => 'Sesi recalculate tidak ditemukan atau sudah habis. Silakan klik Recalculate lagi.',
			);
		}

		$phase = 'done';
		if ($ctx['has_pembelian']) {
			$phase = 'beli';
		} elseif ($ctx['has_penjualan']) {
			$phase = 'penjualan';
		}

		$state = array(
			'phase' => $phase,
			'beli_selesai' => false,
			'stats' => array(
				'beli_updated' => 0,
				'penjualan_ok' => 0,
				'penjualan_skip' => 0,
			),
			'reset_beli' => null,
			'beli_info' => null,
		);
	}

	if ($state['phase'] === 'beli' && empty($state['beli_selesai'])) {
		$import_pembelian = array();
		$sync_pembelian = array();
		if ($ctx['has_pembelian']) {
			$import_pembelian['tbl_pembelian'] = persediaan_recalculate_import_pembelian_tidak_sync($CI, $ctx, 'tbl_pembelian');
			$import_pembelian['tbl_pembelian_jasa'] = persediaan_recalculate_import_pembelian_tidak_sync($CI, $ctx, 'tbl_pembelian_jasa');
			$sync_pembelian['tbl_pembelian'] = persediaan_recalculate_sync_uuid_pembelian_bulan($CI, $ctx, 'tbl_pembelian');
			$sync_pembelian['tbl_pembelian_jasa'] = persediaan_recalculate_sync_uuid_pembelian_bulan($CI, $ctx, 'tbl_pembelian_jasa');
			$CI->session->set_userdata('recalc_pembelian_sync_' . $bulan, array(
				'import' => $import_pembelian,
				'sync' => $sync_pembelian,
			));
			$CI->session->set_userdata('recalc_pembelian_import_' . $bulan, $import_pembelian);

			$row_p = $CI->db->query(
				"SELECT COUNT(*) AS jml FROM `persediaan` WHERE `tanggal_beli` = ?",
				array($ctx['tanggal_beli'])
			)->row();
			$ctx['total_persediaan'] = $row_p ? (int) $row_p->jml : (int) $ctx['total_persediaan'];
		}

		$res = persediaan_recalculate_beli_phase_once($CI, $ctx);
		$state['beli_selesai'] = true;
		$state['reset_beli'] = isset($res['reset_beli']) ? $res['reset_beli'] : null;
		$import_pesan = '';
		$sync_pesan = '';
		if (!empty($import_pembelian)) {
			$ib = isset($import_pembelian['tbl_pembelian']) ? $import_pembelian['tbl_pembelian'] : array();
			$ij = isset($import_pembelian['tbl_pembelian_jasa']) ? $import_pembelian['tbl_pembelian_jasa'] : array();
			$import_pesan = ' Import pembelian→persediaan: barang '
				. (int) (isset($ib['created']) ? $ib['created'] : 0) . ' baru, jasa '
				. (int) (isset($ij['created']) ? $ij['created'] : 0) . ' baru'
				. ' (skip sudah ada: barang '
				. (int) (isset($ib['skipped_existing']) ? $ib['skipped_existing'] : 0) . ', jasa '
				. (int) (isset($ij['skipped_existing']) ? $ij['skipped_existing'] : 0) . ')'
				. ' (uuid baru jika bentrok: '
				. ((int) (isset($ib['uuid_regenerated']) ? $ib['uuid_regenerated'] : 0)
					+ (int) (isset($ij['uuid_regenerated']) ? $ij['uuid_regenerated'] : 0)) . ').';
		}
		if (!empty($sync_pembelian)) {
			$sb = isset($sync_pembelian['tbl_pembelian']) ? $sync_pembelian['tbl_pembelian'] : array();
			$sj = isset($sync_pembelian['tbl_pembelian_jasa']) ? $sync_pembelian['tbl_pembelian_jasa'] : array();
			$sync_pesan = ' Sinkron uuid pembelian: barang '
				. (int) (isset($sb['updated']) ? $sb['updated'] : 0) . ' di-update, '
				. (int) (isset($sb['sudah_sesuai']) ? $sb['sudah_sesuai'] : 0) . ' sudah sesuai, '
				. (int) (isset($sb['tidak_ditemukan']) ? $sb['tidak_ditemukan'] : 0) . ' tidak cocok; jasa '
				. (int) (isset($sj['updated']) ? $sj['updated'] : 0) . ' di-update, '
				. (int) (isset($sj['sudah_sesuai']) ? $sj['sudah_sesuai'] : 0) . ' sudah sesuai, '
				. (int) (isset($sj['tidak_ditemukan']) ? $sj['tidak_ditemukan'] : 0) . ' tidak cocok.';
		}
		$state['beli_info'] = array(
			'grup_pembelian' => isset($res['grup_pembelian']) ? $res['grup_pembelian'] : 0,
			'with_beli' => isset($res['with_beli']) ? $res['with_beli'] : 0,
			'pesan' => (isset($res['pesan']) ? $res['pesan'] : '') . $import_pesan . $sync_pesan,
			'import_pembelian' => $import_pembelian,
			'sync_pembelian' => $sync_pembelian,
		);
		$state['stats']['beli_updated'] = (int) $res['batch_ok'];

		if ($ctx['has_penjualan']) {
			$CI->session->unset_userdata('recalc_penj_reset_' . $bulan);
			$CI->session->unset_userdata('recalc_penj_stats_' . $bulan);
			$state['phase'] = 'penjualan';

			$resPenj = persediaan_recalculate_penjualan_phase_once($CI, $ctx);
			if (empty($resPenj['ok'])) {
				$CI->session->set_userdata($state_key, $state);
				return $resPenj;
			}

			$state['stats']['penjualan_ok'] = (int) $resPenj['batch_ok'];
			$state['stats']['penjualan_skip'] = (int) $resPenj['batch_skip'];
			$CI->session->unset_userdata($state_key);

			$resPenj['summary'] = array(
				'bulan' => $ctx['bulan'],
				'bulan_label' => $ctx['bulan_label'],
				'total_persediaan' => (int) $ctx['total_persediaan'],
				'total_penjualan' => (int) $ctx['total_penjualan'],
				'beli_updated' => (int) $state['stats']['beli_updated'],
				'penjualan_ok' => (int) $state['stats']['penjualan_ok'],
				'penjualan_skip' => (int) $state['stats']['penjualan_skip'],
				'penjualan_created' => isset($resPenj['batch_created']) ? (int) $resPenj['batch_created'] : 0,
				'with_penjualan' => isset($resPenj['with_penjualan']) ? (int) $resPenj['with_penjualan'] : 0,
				'sync_uuid_penjualan' => isset($resPenj['sync_uuid_penjualan']) ? $resPenj['sync_uuid_penjualan'] : null,
				'import_penjualan' => isset($resPenj['import_penjualan']) ? $resPenj['import_penjualan'] : null,
				'total_10_penjualan' => isset($resPenj['total_10_penjualan']) ? $resPenj['total_10_penjualan'] : null,
				'reset_beli' => $state['reset_beli'],
				'beli_info' => $state['beli_info'],
				'import_pembelian' => isset($state['beli_info']['import_pembelian']) ? $state['beli_info']['import_pembelian'] : null,
				'sync_pembelian' => isset($state['beli_info']['sync_pembelian']) ? $state['beli_info']['sync_pembelian'] : null,
				'reset_penjualan' => isset($resPenj['reset']) ? $resPenj['reset'] : null,
				'penjualan_info' => array(
					'pesan' => isset($resPenj['pesan']) ? $resPenj['pesan'] : '',
					'with_penjualan' => isset($resPenj['with_penjualan']) ? (int) $resPenj['with_penjualan'] : 0,
					'sync_uuid_penjualan' => isset($resPenj['sync_uuid_penjualan']) ? $resPenj['sync_uuid_penjualan'] : null,
					'import_penjualan' => isset($resPenj['import_penjualan']) ? $resPenj['import_penjualan'] : null,
					'total_10_penjualan' => isset($resPenj['total_10_penjualan']) ? $resPenj['total_10_penjualan'] : null,
				),
			);
			$resPenj['done'] = true;
			$resPenj['reset_beli'] = $state['reset_beli'];
			$resPenj['beli_info'] = $state['beli_info'];
			$resPenj['message_phase'] = trim(
				(isset($res['pesan']) ? $res['pesan'] : '') . ' '
				. (isset($resPenj['pesan']) ? $resPenj['pesan'] : '')
			);
			$merged_items = array();
			if (!empty($res['items'])) {
				$merged_items = array_merge($merged_items, array_slice($res['items'], 0, 10));
			}
			if (!empty($resPenj['items'])) {
				$merged_items = array_merge($merged_items, array_slice($resPenj['items'], 0, 10));
			}
			if (!empty($merged_items)) {
				$resPenj['items'] = $merged_items;
			}
			return $resPenj;
		}

		$res['summary'] = array(
			'bulan' => $ctx['bulan'],
			'bulan_label' => $ctx['bulan_label'],
			'total_persediaan' => (int) $ctx['total_persediaan'],
			'beli_updated' => (int) $state['stats']['beli_updated'],
			'penjualan_ok' => 0,
			'penjualan_skip' => 0,
			'reset_beli' => $state['reset_beli'],
			'beli_info' => $state['beli_info'],
		);
		$CI->session->unset_userdata($state_key);
		return $res;
	}

	if ($state['phase'] === 'penjualan') {
		$res = persediaan_recalculate_penjualan_phase_once($CI, $ctx);
		if (!$res['ok']) {
			return $res;
		}

		$state['stats']['penjualan_ok'] = (int) $res['batch_ok'];
		$state['stats']['penjualan_skip'] = (int) $res['batch_skip'];

		$res['summary'] = array(
			'bulan' => $ctx['bulan'],
			'bulan_label' => $ctx['bulan_label'],
			'total_persediaan' => (int) $ctx['total_persediaan'],
			'total_penjualan' => (int) $ctx['total_penjualan'],
			'beli_updated' => (int) $state['stats']['beli_updated'],
			'penjualan_ok' => (int) $state['stats']['penjualan_ok'],
			'penjualan_skip' => (int) $state['stats']['penjualan_skip'],
			'penjualan_created' => isset($res['batch_created']) ? (int) $res['batch_created'] : 0,
			'with_penjualan' => isset($res['with_penjualan']) ? (int) $res['with_penjualan'] : 0,
			'sync_uuid_penjualan' => isset($res['sync_uuid_penjualan']) ? $res['sync_uuid_penjualan'] : null,
			'reset_beli' => $state['reset_beli'],
			'beli_info' => isset($state['beli_info']) ? $state['beli_info'] : null,
			'reset_penjualan' => isset($res['reset']) ? $res['reset'] : null,
			'penjualan_info' => array(
				'pesan' => isset($res['pesan']) ? $res['pesan'] : '',
				'with_penjualan' => isset($res['with_penjualan']) ? (int) $res['with_penjualan'] : 0,
				'sync_uuid_penjualan' => isset($res['sync_uuid_penjualan']) ? $res['sync_uuid_penjualan'] : null,
			),
		);
		$CI->session->unset_userdata($state_key);
		$CI->session->unset_userdata('recalc_penj_reset_' . $bulan);
		$CI->session->unset_userdata('recalc_penj_stats_' . $bulan);

		return $res;
	}

	return array('ok' => false, 'message' => 'Tidak ada fase recalculate yang dijalankan.');
}

function persediaan_row_beli_positif($row)
{
	$CI = get_instance();
	$CI->load->helper('persediaan_display');
	return persediaan_parse_angka(isset($row->beli) ? $row->beli : 0) > 0;
}

function persediaan_row_penjualan_positif($row)
{
	$CI = get_instance();
	$CI->load->helper('persediaan_display');
	return persediaan_parse_angka(isset($row->penjualan) ? $row->penjualan : 0) > 0;
}

function persediaan_row_is_kategori_jasa($row)
{
	return penjualan_is_kategori_jasa(isset($row->kategori) ? $row->kategori : '');
}

function persediaan_export_column_defs_from_tanggal_beli($CI)
{
	$defs = array();
	if (!$CI->db->table_exists('persediaan')) {
		return $defs;
	}

	$started = false;
	foreach ($CI->db->list_fields('persediaan') as $field) {
		if ($field === 'tanggal_beli') {
			$started = true;
			$defs[] = array('field' => 'tanggal_beli', 'label' => 'Tanggal');
			continue;
		}
		if ($started) {
			$defs[] = array(
				'field' => $field,
				'label' => ucwords(str_replace('_', ' ', $field)),
			);
		}
	}

	return $defs;
}

function persediaan_export_row_value_by_field($row, $field)
{
	if (!isset($row->$field)) {
		return '';
	}
	$val = $row->$field;
	if ($field === 'tanggal_beli' && $val !== '' && $val !== null) {
		$ts = strtotime((string) $val);
		if ($ts !== false) {
			return date('d/m/Y', $ts);
		}
	}
	return $val;
}

function persediaan_recalculate_collect_penjualan_tidak_sync($CI, $ctx)
{
	$out = array();

	if (!$CI->db->table_exists('tbl_penjualan')) {
		return $out;
	}

	$session = persediaan_recalculate_get_penjualan_sync_log($CI, $ctx['bulan']);
	$import_log = array();
	$sync_log = array();
	if (isset($session['import']['log']) && is_array($session['import']['log'])) {
		$import_log = $session['import']['log'];
	}
	if (isset($session['sync']['log']) && is_array($session['sync']['log'])) {
		$sync_log = $session['sync']['log'];
	}

	$map = persediaan_recalculate_build_map_persediaan_bulan($CI, $ctx['tanggal_beli']);
	$has_uuid_barang = $CI->db->field_exists('uuid_barang', 'tbl_penjualan');

	$list = $CI->db->query(
		"SELECT * FROM `tbl_penjualan`
		WHERE DATE(`tgl_jual`) >= ? AND DATE(`tgl_jual`) <= ?
		ORDER BY `id` ASC",
		array($ctx['tgl_awal'], $ctx['tgl_akhir'])
	)->result();

	foreach ($list as $row) {
		$id = (int) $row->id;

		if (isset($sync_log[$id])) {
			$entry = $sync_log[$id];
			$st = isset($entry['status']) ? $entry['status'] : '';
			if ($st === 'sudah_sesuai') {
				continue;
			}
			$out[] = array(
				'row' => $row,
				'status' => $st,
				'alasan' => isset($entry['alasan']) ? $entry['alasan'] : '',
			);
			continue;
		}

		if (isset($import_log[$id])) {
			$entry = $import_log[$id];
			$out[] = array(
				'row' => $row,
				'status' => isset($entry['status']) ? $entry['status'] : 'copied',
				'alasan' => isset($entry['alasan']) ? $entry['alasan'] : 'Sudah di-copy ke persediaan',
			);
			continue;
		}

		$pers = persediaan_recalculate_find_persediaan_for_sync($row, $map);
		if (!$pers) {
			$out[] = array(
				'row' => $row,
				'status' => 'tidak_sync',
				'alasan' => 'Tidak ada di persediaan bulan ' . $ctx['bulan_label']
					. ' (nama_barang + satuan + harga_satuan harus sama dengan namabarang + satuan + hpp)',
			);
			continue;
		}

		$uuid_pers = trim((string) $pers->uuid_persediaan);
		if ($uuid_pers === '') {
			$out[] = array(
				'row' => $row,
				'status' => 'tidak_sync',
				'alasan' => 'Persediaan cocok ditemukan (id ' . (int) $pers->id . ') tetapi uuid_persediaan kosong',
			);
			continue;
		}

		if (!persediaan_recalculate_penjualan_uuid_sudah_cocok($row, $pers, $has_uuid_barang)) {
			$out[] = array(
				'row' => $row,
				'status' => 'tidak_sync',
				'alasan' => 'Persediaan ditemukan (id ' . (int) $pers->id . ') tetapi uuid penjualan belum sesuai'
					. ' (uuid_persediaan / uuid_barang / id_persediaan_barang berbeda)',
			);
		}
	}

	return $out;
}

function persediaan_recalculate_collect_pembelian_tidak_sync($CI, $ctx, $tabel)
{
	$out = array();
	if (!$CI->db->table_exists($tabel)) {
		return $out;
	}

	$session = persediaan_recalculate_get_pembelian_sync_log($CI, $ctx['bulan']);
	$import_log = array();
	$sync_log = array();
	if (isset($session['import'][$tabel]['log']) && is_array($session['import'][$tabel]['log'])) {
		$import_log = $session['import'][$tabel]['log'];
	} elseif ($tabel === 'tbl_pembelian' || $tabel === 'tbl_pembelian_jasa') {
		$import_all = persediaan_recalculate_get_pembelian_import_log($CI, $ctx['bulan']);
		if (isset($import_all[$tabel]['log']) && is_array($import_all[$tabel]['log'])) {
			$import_log = $import_all[$tabel]['log'];
		}
	}
	if (isset($session['sync'][$tabel]['log']) && is_array($session['sync'][$tabel]['log'])) {
		$sync_log = $session['sync'][$tabel]['log'];
	}

	$map = persediaan_recalculate_build_map_persediaan_bulan($CI, $ctx['tanggal_beli']);
	$has_uuid_barang = $CI->db->field_exists('uuid_barang', $tabel);
	$label = ($tabel === 'tbl_pembelian_jasa') ? 'pembelian jasa' : 'pembelian';

	$list = $CI->db->query(
		"SELECT * FROM `{$tabel}`
		WHERE `tgl_po` IS NOT NULL AND `tgl_po` <> '0000-00-00'
		AND DATE(`tgl_po`) >= ? AND DATE(`tgl_po`) <= ?
		ORDER BY `id` ASC",
		array($ctx['tgl_awal'], $ctx['tgl_akhir'])
	)->result();

	foreach ($list as $row) {
		$id = (int) $row->id;

		if (isset($sync_log[$id])) {
			$entry = $sync_log[$id];
			$st = isset($entry['status']) ? $entry['status'] : '';
			if ($st === 'sudah_sesuai') {
				continue;
			}
			$out[] = array(
				'row' => $row,
				'status' => $st,
				'alasan' => isset($entry['alasan']) ? $entry['alasan'] : '',
			);
			continue;
		}

		if (isset($import_log[$id])) {
			$entry = $import_log[$id];
			$out[] = array(
				'row' => $row,
				'status' => isset($entry['status']) ? $entry['status'] : 'copied',
				'alasan' => isset($entry['alasan']) ? $entry['alasan'] : 'Sudah di-copy ke persediaan',
			);
			continue;
		}

		$nama = isset($row->uraian) ? $row->uraian : '';
		$satuan = isset($row->satuan) ? $row->satuan : '';
		$harga = isset($row->harga_satuan) ? $row->harga_satuan : '';
		$fake = (object) array(
			'nama_barang' => $nama,
			'satuan' => $satuan,
			'harga_satuan' => $harga,
		);

		$pers = persediaan_recalculate_find_persediaan_for_sync($fake, $map);
		if (!$pers) {
			$out[] = array(
				'row' => $row,
				'status' => 'tidak_sync',
				'alasan' => 'Belum ada di persediaan bulan ' . $ctx['bulan_label']
					. ' (' . $label . ': uraian + satuan + harga_satuan harus sama dengan namabarang + satuan + hpp)',
			);
			continue;
		}

		$uuid_pers = trim((string) $pers->uuid_persediaan);
		if ($uuid_pers === '') {
			$out[] = array(
				'row' => $row,
				'status' => 'tidak_sync',
				'alasan' => 'Persediaan cocok ditemukan (id ' . (int) $pers->id . ') tetapi uuid_persediaan kosong',
			);
			continue;
		}

		if (!persediaan_recalculate_penjualan_uuid_sudah_cocok($row, $pers, $has_uuid_barang)) {
			$out[] = array(
				'row' => $row,
				'status' => 'tidak_sync',
				'alasan' => 'Persediaan ditemukan (id ' . (int) $pers->id . ') tetapi uuid pembelian belum sesuai'
					. ' (uuid_persediaan / uuid_barang / id_persediaan_barang berbeda)',
			);
		}
	}

	return $out;
}

function persediaan_export_recalculate_sheet_tidak_sync($sheet_name, $title, $ctx, $fields, $rows_with_alasan, $status_keterangan = null)
{
	xlsAddSheet($sheet_name);
	xlsWriteLabelBold14(0, 0, $title . ' — ' . $ctx['bulan_label'] . ' | Dicetak: ' . date('d/m/Y H:i:s'));
	if ($status_keterangan === null) {
		$status_keterangan = 'Status: sudah_ada = sudah ada di persediaan (tidak di-copy ulang) | copied = sudah di-copy ke persediaan | synced = sudah sinkronisasi uuid | tidak_sync = belum ada / belum cocok | gagal = insert/update gagal.';
	}
	xlsWriteLabel(1, 0, $status_keterangan);

	$headers = array_merge(array('Status', 'Alasan / Keterangan'), $fields);
	persediaan_excel_write_headers(2, $headers);

	$row_num = 3;
	foreach ($rows_with_alasan as $item) {
		$row = isset($item['row']) ? $item['row'] : null;
		$alasan = isset($item['alasan']) ? $item['alasan'] : '';
		$status = isset($item['status']) ? $item['status'] : '';
		if (!$row) {
			continue;
		}
		xlsWriteLabel($row_num, 0, $status);
		xlsWriteLabel($row_num, 1, $alasan);
		persediaan_excel_write_object_fields($row_num, 2, $fields, $row);
		$row_num++;
	}

	if ($row_num === 3) {
		xlsWriteLabel(3, 0, 'OK');
		xlsWriteLabel(3, 1, '(Tidak ada data — semua pembelian sudah sinkron / sudah di-copy ke persediaan)');
	}
}

function persediaan_export_pembelian_rows_for_persediaan($CI, $tabel, $row, $tgl_awal, $tgl_akhir)
{
	if (!$CI->db->table_exists($tabel)) {
		return array();
	}

	$uuid_barang = trim((string) $row->uuid_barang);
	$uuid_persediaan = trim((string) $row->uuid_persediaan);
	$nama = trim((string) $row->namabarang);
	$satuan = trim((string) $row->satuan);
	$hpp = trim((string) $row->hpp);

	if ($satuan === '') {
		return array();
	}

	$link = array();
	$params = array($tgl_awal, $tgl_akhir);

	if ($uuid_barang !== '') {
		$link[] = 'TRIM(COALESCE(`uuid_barang`, \'\')) = ?';
		$params[] = $uuid_barang;
	}
	if ($uuid_persediaan !== '' && $CI->db->field_exists('uuid_persediaan', $tabel)) {
		$link[] = 'TRIM(COALESCE(`uuid_persediaan`, \'\')) = ?';
		$params[] = $uuid_persediaan;
	}
	if ($nama !== '') {
		$link[] = 'LOWER(TRIM(COALESCE(`uraian`, \'\'))) = ?';
		$params[] = persediaan_recalculate_normalize_nama($nama);
	}

	if (empty($link)) {
		return array();
	}

	$params[] = $satuan;
	$params[] = $hpp;

	$sql = 'SELECT * FROM `' . $tabel . '`
		WHERE `tgl_po` IS NOT NULL AND `tgl_po` <> \'0000-00-00\'
		AND DATE(`tgl_po`) >= ? AND DATE(`tgl_po`) <= ?
		AND (' . implode(' OR ', $link) . ')
		AND LOWER(TRIM(COALESCE(`satuan`, \'\'))) = LOWER(?)
		AND CAST(REPLACE(TRIM(`harga_satuan`), \',\', \'\') AS DECIMAL(18,2))
			= CAST(REPLACE(?, \',\', \'\') AS DECIMAL(18,2))
		ORDER BY `uraian` ASC, `id` ASC';

	return $CI->db->query($sql, $params)->result();
}

function persediaan_export_sort_rows_by_namabarang($rows, $field = 'namabarang')
{
	if (!is_array($rows) || empty($rows)) {
		return $rows;
	}

	usort($rows, function ($a, $b) use ($field) {
		$name_a = persediaan_export_row_nama_label($a, $field);
		$name_b = persediaan_export_row_nama_label($b, $field);
		$cmp = strcasecmp($name_a, $name_b);
		if ($cmp !== 0) {
			return $cmp;
		}

		$id_a = is_object($a) && isset($a->id) ? (int) $a->id : 0;
		$id_b = is_object($b) && isset($b->id) ? (int) $b->id : 0;
		if ($id_a !== $id_b) {
			return ($id_a < $id_b) ? -1 : 1;
		}

		return 0;
	});

	return $rows;
}

function persediaan_export_row_nama_label($row, $preferred_field = 'namabarang')
{
	if (!is_object($row)) {
		return '';
	}

	$candidates = array($preferred_field, 'namabarang', 'nama_barang', 'uraian');
	foreach ($candidates as $field) {
		if ($field !== '' && isset($row->$field) && trim((string) $row->$field) !== '') {
			return trim((string) $row->$field);
		}
	}

	return '';
}

function persediaan_export_sort_tidak_sync_by_namabarang($rows_with_alasan)
{
	if (!is_array($rows_with_alasan) || empty($rows_with_alasan)) {
		return $rows_with_alasan;
	}

	usort($rows_with_alasan, function ($a, $b) {
		$row_a = isset($a['row']) ? $a['row'] : null;
		$row_b = isset($b['row']) ? $b['row'] : null;
		$name_a = persediaan_export_row_nama_label($row_a, 'namabarang');
		$name_b = persediaan_export_row_nama_label($row_b, 'namabarang');
		$cmp = strcasecmp($name_a, $name_b);
		if ($cmp !== 0) {
			return $cmp;
		}

		$id_a = is_object($row_a) && isset($row_a->id) ? (int) $row_a->id : 0;
		$id_b = is_object($row_b) && isset($row_b->id) ? (int) $row_b->id : 0;
		return ($id_a < $id_b) ? -1 : (($id_a > $id_b) ? 1 : 0);
	});

	return $rows_with_alasan;
}

function persediaan_excel_write_headers($rowNum, $headers)
{
	foreach ($headers as $col => $label) {
		xlsWriteLabel($rowNum, $col, $label);
	}
}

function persediaan_excel_write_object_fields($rowNum, $startCol, $fields, $obj)
{
	$col = $startCol;
	foreach ($fields as $field) {
		$val = is_object($obj) && isset($obj->$field) ? $obj->$field : '';
		xlsWriteLabel($rowNum, $col, $val);
		$col++;
	}
	return $col;
}

function persediaan_export_recalculate_excel_output($CI, $bulan)
{
	$CI->load->helper(array('exportexcel', 'persediaan_display'));

	$ctx = persediaan_recalculate_full_context($CI, $bulan);
	if (!$ctx['ok']) {
		xlsBOF();
		xlsWriteLabel(0, 0, $ctx['message']);
		xlsEOF();
		return;
	}

	if ($ctx['has_pembelian']) {
		$import_pembelian = array(
			'tbl_pembelian' => persediaan_recalculate_import_pembelian_tidak_sync($CI, $ctx, 'tbl_pembelian'),
			'tbl_pembelian_jasa' => persediaan_recalculate_import_pembelian_tidak_sync($CI, $ctx, 'tbl_pembelian_jasa'),
		);
		$sync_pembelian = array(
			'tbl_pembelian' => persediaan_recalculate_sync_uuid_pembelian_bulan($CI, $ctx, 'tbl_pembelian'),
			'tbl_pembelian_jasa' => persediaan_recalculate_sync_uuid_pembelian_bulan($CI, $ctx, 'tbl_pembelian_jasa'),
		);
		$CI->session->set_userdata('recalc_pembelian_sync_' . $bulan, array(
			'import' => $import_pembelian,
			'sync' => $sync_pembelian,
		));
		$CI->session->set_userdata('recalc_pembelian_import_' . $bulan, $import_pembelian);
	}

	if ($ctx['has_penjualan']) {
		$import_penjualan = persediaan_recalculate_import_penjualan_tidak_sync($CI, $ctx);
		$sync_penjualan = persediaan_recalculate_sync_uuid_penjualan_bulan($CI, $ctx);
		$CI->session->set_userdata('recalc_penjualan_sync_' . $bulan, array(
			'import' => $import_penjualan,
			'sync' => $sync_penjualan,
		));
	}

	$waktu_cetak = date('d/m/Y H:i:s');
	$persediaan_rows = $CI->db->query(
		"SELECT * FROM `persediaan` WHERE `tanggal_beli` = ? ORDER BY `namabarang` ASC, `id` ASC",
		array($ctx['tanggal_beli'])
	)->result();

	$col_defs = persediaan_export_column_defs_from_tanggal_beli($CI);
	$pembelian_fields = $CI->db->table_exists('tbl_pembelian') ? $CI->db->list_fields('tbl_pembelian') : array();
	$pembelian_jasa_fields = $CI->db->table_exists('tbl_pembelian_jasa') ? $CI->db->list_fields('tbl_pembelian_jasa') : array();
	$penjualan_fields = $CI->db->table_exists('tbl_penjualan') ? $CI->db->list_fields('tbl_penjualan') : array();

	$prefix_pembelian = array(
		'ID Persediaan', 'UUID Barang Pers', 'Namabarang Pers', 'Satuan Pers', 'HPP Pers', 'Beli Pers', 'Kategori Pers',
	);
	$prefix_penjualan = array(
		'ID Persediaan', 'UUID Barang Pers', 'Namabarang Pers', 'Satuan Pers', 'HPP Pers', 'Penjualan Pers',
	);

	xlsMultiBOF();

	// Sheet 1: Persediaan
	xlsAddSheet('Persediaan');
	xlsWriteLabelBold14(0, 0, 'Recalculate Persediaan — Bulan: ' . $ctx['bulan_label'] . ' | Dicetak: ' . $waktu_cetak);
	$headers_pers = array();
	foreach ($col_defs as $def) {
		$headers_pers[] = $def['label'];
	}
	persediaan_excel_write_headers(2, $headers_pers);
	$row_pers = 3;
	foreach ($persediaan_rows as $pr) {
		$col = 0;
		foreach ($col_defs as $def) {
			xlsWriteLabel($row_pers, $col, persediaan_export_row_value_by_field($pr, $def['field']));
			$col++;
		}
		$row_pers++;
	}

	// Sheet 2: Pembelian (bukan jasa)
	xlsAddSheet('Pembelian');
	xlsWriteLabelBold14(0, 0, 'Persediaan beli > 0 (selain jasa) + tbl_pembelian — ' . $ctx['bulan_label']);
	$headers_beli = array_merge($prefix_pembelian, $pembelian_fields);
	persediaan_excel_write_headers(2, $headers_beli);
	$row_beli = 3;
	foreach ($persediaan_rows as $pr) {
		if (!persediaan_row_beli_positif($pr) || persediaan_row_is_kategori_jasa($pr)) {
			continue;
		}
		$matches = persediaan_export_pembelian_rows_for_persediaan(
			$CI,
			'tbl_pembelian',
			$pr,
			$ctx['tgl_awal'],
			$ctx['tgl_akhir']
		);
		$matches = persediaan_export_sort_rows_by_namabarang($matches, 'uraian');
		if (count($matches) === 0) {
			continue;
		}
		foreach ($matches as $pb) {
			$col = 0;
			xlsWriteLabel($row_beli, $col++, (int) $pr->id);
			xlsWriteLabel($row_beli, $col++, isset($pr->uuid_barang) ? $pr->uuid_barang : '');
			xlsWriteLabel($row_beli, $col++, isset($pr->namabarang) ? $pr->namabarang : '');
			xlsWriteLabel($row_beli, $col++, isset($pr->satuan) ? $pr->satuan : '');
			xlsWriteLabel($row_beli, $col++, isset($pr->hpp) ? $pr->hpp : '');
			xlsWriteLabel($row_beli, $col++, isset($pr->beli) ? $pr->beli : '');
			xlsWriteLabel($row_beli, $col++, isset($pr->kategori) ? $pr->kategori : '');
			persediaan_excel_write_object_fields($row_beli, $col, $pembelian_fields, $pb);
			$row_beli++;
		}
	}

	// Sheet 3: Pembelian Jasa
	xlsAddSheet('Pembelian Jasa');
	xlsWriteLabelBold14(0, 0, 'Persediaan beli > 0 (jasa) + tbl_pembelian_jasa — ' . $ctx['bulan_label']);
	$headers_jasa = array_merge($prefix_pembelian, $pembelian_jasa_fields);
	persediaan_excel_write_headers(2, $headers_jasa);
	$row_jasa = 3;
	foreach ($persediaan_rows as $pr) {
		if (!persediaan_row_beli_positif($pr) || !persediaan_row_is_kategori_jasa($pr)) {
			continue;
		}
		$matches = persediaan_export_pembelian_rows_for_persediaan(
			$CI,
			'tbl_pembelian_jasa',
			$pr,
			$ctx['tgl_awal'],
			$ctx['tgl_akhir']
		);
		$matches = persediaan_export_sort_rows_by_namabarang($matches, 'uraian');
		if (count($matches) === 0) {
			continue;
		}
		foreach ($matches as $pb) {
			$col = 0;
			xlsWriteLabel($row_jasa, $col++, (int) $pr->id);
			xlsWriteLabel($row_jasa, $col++, isset($pr->uuid_barang) ? $pr->uuid_barang : '');
			xlsWriteLabel($row_jasa, $col++, isset($pr->namabarang) ? $pr->namabarang : '');
			xlsWriteLabel($row_jasa, $col++, isset($pr->satuan) ? $pr->satuan : '');
			xlsWriteLabel($row_jasa, $col++, isset($pr->hpp) ? $pr->hpp : '');
			xlsWriteLabel($row_jasa, $col++, isset($pr->beli) ? $pr->beli : '');
			xlsWriteLabel($row_jasa, $col++, isset($pr->kategori) ? $pr->kategori : '');
			persediaan_excel_write_object_fields($row_jasa, $col, $pembelian_jasa_fields, $pb);
			$row_jasa++;
		}
	}

	// Sheet 4: Penjualan
	xlsAddSheet('Penjualan');
	xlsWriteLabelBold14(0, 0, 'Persediaan penjualan > 0 + tbl_penjualan — ' . $ctx['bulan_label']);
	$headers_jual = array_merge($prefix_penjualan, $penjualan_fields);
	persediaan_excel_write_headers(2, $headers_jual);
	$row_jual = 3;

	$map = persediaan_recalculate_build_map_persediaan_bulan($CI, $ctx['tanggal_beli']);
	$penjualan_list = array();
	if ($CI->db->table_exists('tbl_penjualan')) {
		$penjualan_list = $CI->db->query(
			"SELECT * FROM `tbl_penjualan`
			WHERE DATE(`tgl_jual`) >= ? AND DATE(`tgl_jual`) <= ?
			ORDER BY `nama_barang` ASC, `id` ASC",
			array($ctx['tgl_awal'], $ctx['tgl_akhir'])
		)->result();
	}

	foreach ($penjualan_list as $pj) {
		$match = persediaan_recalculate_match_penjualan_ke_persediaan($CI, $pj, $map);
		if (empty($match['ok']) || empty($match['row'])) {
			continue;
		}
		$pr = $match['row'];
		if (!persediaan_row_penjualan_positif($pr)) {
			continue;
		}

		$col = 0;
		xlsWriteLabel($row_jual, $col++, (int) $pr->id);
		xlsWriteLabel($row_jual, $col++, isset($pr->uuid_barang) ? $pr->uuid_barang : '');
		xlsWriteLabel($row_jual, $col++, isset($pr->namabarang) ? $pr->namabarang : '');
		xlsWriteLabel($row_jual, $col++, isset($pr->satuan) ? $pr->satuan : '');
		xlsWriteLabel($row_jual, $col++, isset($pr->hpp) ? $pr->hpp : '');
		xlsWriteLabel($row_jual, $col++, isset($pr->penjualan) ? $pr->penjualan : '');
		persediaan_excel_write_object_fields($row_jual, $col, $penjualan_fields, $pj);
		$row_jual++;
	}

	// Sheet 5–7: Data tidak sinkron dengan persediaan (pengecekan manual)
	$penjualan_tidak_sync = persediaan_export_sort_tidak_sync_by_namabarang(
		persediaan_recalculate_collect_penjualan_tidak_sync($CI, $ctx)
	);
	persediaan_export_recalculate_sheet_tidak_sync(
		'Penjualan Tidak Sync',
		'Penjualan — import / sinkron uuid ke persediaan',
		$ctx,
		$penjualan_fields,
		$penjualan_tidak_sync,
		'Status: copied = sudah di-copy ke persediaan | synced = sudah sinkronisasi dengan persediaan (uuid_barang + uuid_persediaan) | tidak_sync = belum ada / belum cocok | gagal = insert/update gagal.'
	);

	$pembelian_tidak_sync = persediaan_export_sort_tidak_sync_by_namabarang(
		persediaan_recalculate_collect_pembelian_tidak_sync($CI, $ctx, 'tbl_pembelian')
	);
	persediaan_export_recalculate_sheet_tidak_sync(
		'Pembelian Tidak Sync',
		'Pembelian (tbl_pembelian) — import / sinkron uuid ke persediaan',
		$ctx,
		$pembelian_fields,
		$pembelian_tidak_sync,
		'Status: copied = sudah di-copy ke persediaan | synced = sudah sinkronisasi dengan persediaan (uuid_barang + uuid_persediaan) | tidak_sync = belum ada / belum cocok | gagal = insert/update gagal.'
	);

	$pembelian_jasa_tidak_sync = persediaan_export_sort_tidak_sync_by_namabarang(
		persediaan_recalculate_collect_pembelian_tidak_sync($CI, $ctx, 'tbl_pembelian_jasa')
	);
	persediaan_export_recalculate_sheet_tidak_sync(
		'Pembelian Jasa Tidak Sync',
		'Pembelian jasa — import / sinkron uuid ke persediaan',
		$ctx,
		$pembelian_jasa_fields,
		$pembelian_jasa_tidak_sync,
		'Status: copied = sudah di-copy ke persediaan | synced = sudah sinkronisasi dengan persediaan (uuid_barang + uuid_persediaan) | tidak_sync = belum ada / belum cocok | gagal = insert/update gagal.'
	);

	xlsMultiEOF();
}

/**
 * Output JSON aman untuk endpoint AJAX (bersihkan buffer, hindari HTML/warning).
 */
function persediaan_ajax_json_output($CI, $data)
{
	while (ob_get_level() > 0) {
		ob_end_clean();
	}

	$flags = JSON_UNESCAPED_UNICODE;
	if (defined('JSON_INVALID_UTF8_SUBSTITUTE')) {
		$flags |= JSON_INVALID_UTF8_SUBSTITUTE;
	}

	$json = json_encode($data, $flags);
	if ($json === false) {
		$json = json_encode(array(
			'ok' => false,
			'message' => 'Gagal encode JSON: ' . json_last_error_msg(),
		));
	}

	$CI->output->enable_profiler(false);
	$CI->output->set_content_type('application/json', 'utf-8')->set_output($json);
}
