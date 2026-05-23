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

	foreach (array('kode_unit', 'nama_unit', 'keterangan') as $prop) {
		if (!isset($row_unit->{$prop})) {
			continue;
		}
		$key = penjualan_normalize_unit_key($row_unit->{$prop});
		if ($key !== '' && penjualan_is_valid_persediaan_column_name($key)) {
			return $key;
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

	$non_unit = penjualan_persediaan_kolom_non_unit();
	$fields = $CI->db->list_fields('persediaan');
	$cols = array();

	foreach ($fields as $f) {
		if ($f === 'total_10') {
			break;
		}
		if (!in_array($f, $non_unit, true) && penjualan_is_valid_persediaan_column_name($f)) {
			$cols[] = $f;
		}
	}

	return $cols;
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
function penjualan_resolve_kolom_persediaan_unit($CI, $uuid_unit)
{
	$uuid_unit = trim((string) $uuid_unit);
	if ($uuid_unit === '') {
		return null;
	}

	penjualan_ensure_persediaan_kolom_unit($CI, $uuid_unit);

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
		if (in_array($key, $allowed, true)) {
			return $key;
		}
		if (isset($aliases[$key]) && in_array($aliases[$key], $allowed, true)) {
			return $aliases[$key];
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
			if (strpos($key, $kolom) !== false || strpos($kolom, $key) !== false) {
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
	$prop = (string) $kolom_unit;
	if (!is_object($row) || !isset($row->{$prop})) {
		return 0;
	}
	return persediaan_parse_angka($row->{$prop});
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
