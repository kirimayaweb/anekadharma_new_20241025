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

	$parts = preg_split('/[-\/\.\s]+/', $tanggal_po);
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

/**
 * Tanggal PO terakhir di halaman create pembelian (session), fallback tanggal hari ini.
 */
function pembelian_get_default_tgl_po_create($CI)
{
	$saved = trim((string) $CI->session->userdata('filter_pembelian_create_tanggal_po'));
	if ($saved !== '' && pembelian_parse_tanggal_po($saved) !== false) {
		return $saved;
	}

	return date('j-n-Y');
}

function pembelian_get_barang_list_rows($CI)
{
	$tgl = pembelian_get_filter_tanggal($CI);

	$has_spop = $CI->db->field_exists('spop', 'persediaan');
	$spop_sql = $has_spop
		? "TRIM(COALESCE(`spop`, '')) AS `spop`"
		: "'' AS `spop`";
	$group_spop = $has_spop ? ', `spop`' : '';

	$sql = "SELECT
			COALESCE(NULLIF(`uuid_barang`, ''), `uuid_persediaan`) AS uuid_barang,
			`uuid_persediaan`,
			`kode` AS kode_barang,
			`namabarang` AS nama_barang,
			`satuan`,
			`hpp` AS harga_satuan,
			{$spop_sql}
		FROM `persediaan`
		WHERE TRIM(`namabarang`) <> ''
		AND DATE(`tanggal_beli`) >= ?
		AND DATE(`tanggal_beli`) <= ?
		GROUP BY COALESCE(NULLIF(`uuid_barang`, ''), `uuid_persediaan`), `uuid_persediaan`, `namabarang`, `kode`, `satuan`, `hpp`{$group_spop}
		ORDER BY `namabarang` ASC, `spop` ASC";

	return $CI->db->query($sql, array($tgl['awal'], $tgl['akhir']))->result();
}

/**
 * Daftar barang untuk combobox modal Tambah Barang Beli (create pembelian):
 * seluruh data persediaan, group by nama barang, satuan, dan HPP.
 */
function pembelian_get_barang_combobox_modal_rows($CI)
{
	$sql = "SELECT
			COALESCE(NULLIF(p.`uuid_barang`, ''), p.`uuid_persediaan`) AS uuid_barang,
			p.`uuid_persediaan`,
			p.`kode` AS kode_barang,
			p.`namabarang` AS nama_barang,
			p.`satuan`,
			p.`hpp` AS harga_satuan
		FROM `persediaan` p
		INNER JOIN (
			SELECT MAX(`id`) AS `id`
			FROM `persediaan`
			WHERE TRIM(`namabarang`) <> ''
			GROUP BY `namabarang`, `satuan`, `hpp`
		) g ON g.`id` = p.`id`
		ORDER BY p.`namabarang` ASC, p.`satuan` ASC, p.`hpp` ASC";

	return $CI->db->query($sql)->result();
}

function pembelian_format_barang_combobox_label($nama_barang, $satuan, $harga_satuan = null)
{
	$nama = strtoupper(trim((string) $nama_barang));
	$satuan_t = trim((string) $satuan);
	$harga_tampil = trim((string) $harga_satuan);
	$digits = preg_replace('/[^0-9]/', '', $harga_tampil);
	if ($digits !== '') {
		$harga_tampil = number_format((float) $digits, 0, ',', '.');
	}

	return $nama . ' ( satuan : ' . $satuan_t . ', harga satuan : ' . $harga_tampil . ' )';
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
			`id`,
			COALESCE(NULLIF(`uuid_barang`, ''), `uuid_persediaan`) AS uuid_barang,
			`kode` AS kode_barang,
			`namabarang` AS nama_barang,
			`satuan`,
			`hpp` AS harga_satuan,
			`tanggal_beli`
		FROM `persediaan`
		WHERE {$nama_expr} = ?
		AND TRIM(`namabarang`) <> ''
		AND `tanggal_beli` IS NOT NULL
		AND `tanggal_beli` <> '0000-00-00'
		AND `tanggal_beli` <> '0000-00-00 00:00:00'
		AND DATE(`tanggal_beli`) >= ?
		AND DATE(`tanggal_beli`) <= ?
		ORDER BY `tanggal_beli` DESC, `id` DESC
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
	$disp_awal = date('j-n-Y', strtotime($awal));
	$disp_akhir = date('j-n-Y', strtotime($akhir));
	$CI->session->set_userdata('filter_tbl_penjualan_date_awal', $awal);
	$CI->session->set_userdata('filter_tbl_penjualan_date_akhir', $akhir);
	$CI->session->set_userdata('filter_tbl_penjualan_tgl_awal_display', $disp_awal);
	$CI->session->set_userdata('filter_tbl_penjualan_tgl_akhir_display', $disp_akhir);
	$CI->session->set_userdata('filter_penjualan_create_tgl_jual', trim((string) $tgl_jual));
	return $tgl;
}

/**
 * Simpan konteks bulan halaman list penjualan (sebelum input penjualan baru).
 */
function penjualan_set_list_bulan_context($CI, $tgl_awal = null, $tgl_akhir = null)
{
	$ref = ($tgl_akhir !== null && trim((string) $tgl_akhir) !== '') ? $tgl_akhir : $tgl_awal;
	$bulan_key = penjualan_get_bulan_key_from_tgl($ref);
	if ($bulan_key !== '') {
		$parts = explode('-', $bulan_key);
		$CI->session->set_userdata('filter_penjualan_list_bulan_key', $bulan_key);
		$CI->session->set_userdata('filter_penjualan_list_bulan_label', $parts[1] . '/' . $parts[0]);
	}
	if ($tgl_awal !== null && trim((string) $tgl_awal) !== '') {
		$CI->session->set_userdata('filter_penjualan_list_tgl_awal', trim((string) $tgl_awal));
	}
	if ($tgl_akhir !== null && trim((string) $tgl_akhir) !== '') {
		$CI->session->set_userdata('filter_penjualan_list_tgl_akhir', trim((string) $tgl_akhir));
	}
}

/**
 * Konteks bulan halaman list penjualan saat user membuka input penjualan.
 */
function penjualan_get_list_bulan_context($CI)
{
	return array(
		'bulan_key' => (string) $CI->session->userdata('filter_penjualan_list_bulan_key'),
		'bulan_label' => (string) $CI->session->userdata('filter_penjualan_list_bulan_label'),
		'tgl_awal' => (string) $CI->session->userdata('filter_penjualan_list_tgl_awal'),
		'tgl_akhir' => (string) $CI->session->userdata('filter_penjualan_list_tgl_akhir'),
	);
}

/**
 * Default Tgl Jual di form create: tanggal akhir filter halaman list.
 */
function penjualan_get_default_tgl_jual_dari_filter_list($CI, $tgl_awal_get = null, $tgl_akhir_get = null)
{
	if ($tgl_akhir_get !== null && trim((string) $tgl_akhir_get) !== '') {
		return penjualan_format_tgl_jual_tampil($tgl_akhir_get);
	}

	$disp_akhir = $CI->session->userdata('filter_tbl_penjualan_tgl_akhir_display');
	if (!empty($disp_akhir)) {
		return penjualan_format_tgl_jual_tampil($disp_akhir);
	}

	$akhir = $CI->session->userdata('filter_tbl_penjualan_date_akhir');
	if (!empty($akhir)) {
		return penjualan_format_tgl_jual_tampil($akhir);
	}

	return date('d-m-Y');
}

/**
 * URL kembali ke list penjualan: rentang awal–akhir bulan sesuai Tgl Jual input.
 */
function penjualan_build_redirect_list_url($CI, $tgl_jual)
{
	$filter = penjualan_get_filter_tgl_jual($CI, $tgl_jual);
	penjualan_sync_filter_bulan_from_tgl_jual($CI, $tgl_jual);
	$tgl_awal_disp = date('j-n-Y', strtotime($filter['awal']));
	$tgl_akhir_disp = date('j-n-Y', strtotime($filter['akhir']));
	return site_url('tbl_penjualan')
		. '?tgl_awal=' . rawurlencode($tgl_awal_disp)
		. '&tgl_akhir=' . rawurlencode($tgl_akhir_disp);
}

function penjualan_get_bulan_label_from_key($bulan_key)
{
	$parts = explode('-', trim((string) $bulan_key));
	if (count($parts) === 2) {
		return $parts[1] . '/' . $parts[0];
	}
	return '';
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
 * Tambah kolom unit di persediaan dari teks unit penjualan/pembelian (jika belum ada).
 */
function penjualan_ensure_persediaan_kolom_unit_dari_teks($CI, $unit_txt)
{
	$unit_txt = trim((string) $unit_txt);
	if ($unit_txt === '') {
		return array('ok' => false, 'message' => 'Teks unit kosong.');
	}

	$CI->load->helper('persediaan_display');

	$kolom_ada = persediaan_resolve_unit_column_from_text($CI, $unit_txt);
	if ($kolom_ada !== null && $CI->db->field_exists($kolom_ada, 'persediaan')) {
		return array(
			'ok' => true,
			'kolom' => $kolom_ada,
			'created' => false,
			'message' => 'Kolom unit sudah ada.',
		);
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
			return penjualan_ensure_persediaan_kolom_unit($CI, $row_unit->uuid_unit);
		}
		if ($row_unit) {
			$kolom = penjualan_kolom_dari_sys_unit_row($row_unit);
			if ($kolom !== null && $CI->db->field_exists($kolom, 'persediaan')) {
				return array(
					'ok' => true,
					'kolom' => $kolom,
					'created' => false,
					'message' => 'Kolom unit sudah ada.',
				);
			}
		}
	}

	$kolom = penjualan_normalize_unit_key($unit_txt);
	if ($kolom === '' || !penjualan_is_valid_persediaan_column_name($kolom)) {
		return array(
			'ok' => false,
			'message' => 'Nama unit "' . $unit_txt . '" tidak valid untuk kolom persediaan.',
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

	$tgl_jual_ref = '';
	if (!empty($rows_penjualan[0]->tgl_jual)) {
		$tgl_jual_ref = trim((string) $rows_penjualan[0]->tgl_jual);
	}
	$map_bulan = null;
	if ($tgl_jual_ref !== '') {
		$tanggal_beli = penjualan_get_tanggal_beli_dari_tgl_jual($CI, $tgl_jual_ref);
		if ($tanggal_beli !== null) {
			$map_bulan = persediaan_recalculate_build_map_persediaan_bulan($CI, $tanggal_beli);
		}
	}

	foreach ($rows_penjualan as $row_penjualan) {
		$jumlah = isset($row_penjualan->jumlah)
			? (int) preg_replace('/[^0-9]/', '', (string) $row_penjualan->jumlah)
			: 0;

		if ($jumlah <= 0) {
			continue;
		}

		$row_persediaan = penjualan_resolve_persediaan_bulan_penjualan_row(
			$CI,
			$row_penjualan,
			$map_bulan,
			$tgl_jual_ref
		);
		if (empty($row_persediaan)) {
			$row_persediaan = penjualan_get_persediaan_by_penjualan_row($CI, $row_penjualan, $tgl_jual_ref);
		}
		if (empty($row_persediaan)) {
			continue;
		}

		$id_persediaan = (int) $row_persediaan->id;
		if ($id_persediaan <= 0) {
			continue;
		}

		if (!empty($row_penjualan->id)) {
			$id_persediaan_lama = isset($row_penjualan->id_persediaan_barang)
				? (int) $row_penjualan->id_persediaan_barang
				: 0;
			$uuid_persediaan_lama = isset($row_penjualan->uuid_persediaan)
				? trim((string) $row_penjualan->uuid_persediaan)
				: '';
			$uuid_persediaan_baru = isset($row_persediaan->uuid_persediaan)
				? trim((string) $row_persediaan->uuid_persediaan)
				: '';
			if ($id_persediaan_lama !== $id_persediaan || ($uuid_persediaan_baru !== '' && $uuid_persediaan_baru !== $uuid_persediaan_lama)) {
				$sync_penjualan = array('id_persediaan_barang' => $id_persediaan);
				if ($uuid_persediaan_baru !== '') {
					$sync_penjualan['uuid_persediaan'] = $uuid_persediaan_baru;
				}
				$CI->db->where('id', (int) $row_penjualan->id)->update('tbl_penjualan', $sync_penjualan);
			}
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

		$can_migrate = true;
		if ($kolom_lama !== null) {
			$nilai_kolom_lama = penjualan_get_nilai_kolom_unit($row_persediaan, $kolom_lama);
			if ($nilai_kolom_lama < $jumlah) {
				$can_migrate = false;
			} else {
				$hasil_kurang = penjualan_update_persediaan_kolom_unit_saja(
					$CI,
					$id_persediaan,
					$kolom_lama,
					$jumlah,
					'kurangi'
				);
				if (empty($hasil_kurang['ok'])) {
					$can_migrate = false;
				}
			}
		}

		if (!$can_migrate) {
			continue;
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
				continue;
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
 * tanggal_beli (Y-m-d) untuk map persediaan dari Tgl Jual penjualan.
 */
function penjualan_get_tanggal_beli_dari_tgl_jual($CI, $tgl_jual)
{
	$tgl_jual = trim((string) $tgl_jual);
	if ($tgl_jual === '') {
		return null;
	}

	$filter = pembelian_get_filter_tanggal($CI, $tgl_jual);
	if (empty($filter['awal'])) {
		return null;
	}

	$ts = strtotime($filter['awal']);
	return ($ts !== false) ? date('Y-m-d', $ts) : null;
}

/**
 * Cari baris persediaan bulan Tgl Jual untuk satu baris penjualan.
 * Dipakai saat id_persediaan_barang di penjualan sudah tidak ada di persediaan bulan aktif.
 */
function penjualan_resolve_persediaan_bulan_penjualan_row($CI, $row_penjualan, $map_bulan = null, $tgl_jual = null)
{
	if (empty($row_penjualan)) {
		return null;
	}

	if ($tgl_jual === null && isset($row_penjualan->tgl_jual)) {
		$tgl_jual = $row_penjualan->tgl_jual;
	}
	$tgl_jual = trim((string) $tgl_jual);
	if ($tgl_jual === '') {
		return null;
	}

	if (!is_array($map_bulan) || !isset($map_bulan['by_id'])) {
		$tanggal_beli = penjualan_get_tanggal_beli_dari_tgl_jual($CI, $tgl_jual);
		if ($tanggal_beli === null) {
			return null;
		}
		$map_bulan = persediaan_recalculate_build_map_persediaan_bulan($CI, $tanggal_beli);
	}

	if (!empty($map_bulan)) {
		$pers = persediaan_recalculate_find_persediaan_for_sync($row_penjualan, $map_bulan);
		if (!empty($pers)) {
			return $pers;
		}

		$uuid_barang = isset($row_penjualan->uuid_barang) ? trim((string) $row_penjualan->uuid_barang) : '';
		if ($uuid_barang !== '' && !empty($map_bulan['by_uuid_barang'][$uuid_barang])) {
			$hit = persediaan_recalculate_filter_kandidat_penjualan($map_bulan['by_uuid_barang'][$uuid_barang], $row_penjualan);
			if (!empty($hit)) {
				return $hit;
			}
			return $map_bulan['by_uuid_barang'][$uuid_barang][0];
		}
	}

	$uuid_barang = isset($row_penjualan->uuid_barang) ? trim((string) $row_penjualan->uuid_barang) : '';
	if ($uuid_barang === '') {
		return null;
	}

	$filter = pembelian_get_filter_tanggal($CI, $tgl_jual);
	$bulan_where = penjualan_sql_filter_bulan_persediaan_where('persediaan');
	$bulan_ym = date('Y-m', strtotime($filter['awal']));
	$row = $CI->db->query(
		"SELECT * FROM persediaan
		WHERE uuid_barang = ?
		AND {$bulan_where}
		ORDER BY id ASC
		LIMIT 1",
		array(
			$uuid_barang,
			$filter['awal'],
			$filter['akhir'],
			$filter['awal'],
			$filter['akhir'],
			$bulan_ym,
		)
	)->row();

	return !empty($row) ? $row : null;
}

/**
 * Ambil baris persediaan untuk satu baris penjualan (id → bulan Tgl Jual → uuid_persediaan → uuid_barang).
 */
function penjualan_get_persediaan_by_penjualan_row($CI, $row_penjualan, $tgl_jual = null)
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

	$row_bulan = penjualan_resolve_persediaan_bulan_penjualan_row($CI, $row_penjualan, null, $tgl_jual);
	if (!empty($row_bulan)) {
		return $row_bulan;
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
 * Bungkus nama konsumen per baris di kotak KEPADA YTH. (cetak penjualan PDF).
 * Pemisah baris hanya di spasi agar kata tidak terpotong.
 */
function wrap_kepada_yth_nama_cetak_lines($nama, $max_chars = 30)
{
	if (!is_string($nama)) {
		$nama = (string) $nama;
	}
	$nama = trim(preg_replace('/\s+/u', ' ', $nama));
	if ($nama === '') {
		return array('');
	}

	$strlen = function_exists('mb_strlen')
		? function ($s) {
			return mb_strlen($s, 'UTF-8');
		}
		: 'strlen';

	if ($strlen($nama) <= $max_chars) {
		return array($nama);
	}

	$words = preg_split('/\s+/u', $nama, -1, PREG_SPLIT_NO_EMPTY);
	$lines = array();
	$current = '';

	foreach ($words as $word) {
		$test = ($current === '') ? $word : $current . ' ' . $word;
		if ($strlen($test) <= $max_chars) {
			$current = $test;
			continue;
		}

		if ($current !== '') {
			$lines[] = $current;
			$current = $word;
			continue;
		}

		$lines[] = $word;
		$current = '';
	}

	if ($current !== '') {
		$lines[] = $current;
	}

	return $lines;
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
	return persediaan_recalculate_angka_match_key($hpp_persediaan)
		=== persediaan_recalculate_angka_match_key($harga_penjualan);
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

	$generate_key = persediaan_generate_recalculate_sync_key(
		$row->namabarang,
		isset($row->sa) ? $row->sa : 0,
		isset($row->hpp) ? $row->hpp : 0,
		isset($row->spop) ? $row->spop : ''
	);
	if ($generate_key !== '') {
		if (!isset($map['by_generate_key'][$generate_key])) {
			$map['by_generate_key'][$generate_key] = array();
		}
		$map['by_generate_key'][$generate_key][] = $row;
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

	$spop_key = persediaan_recalculate_spop_match_value(isset($row->spop) ? $row->spop : '');
	if ($spop_key !== '') {
		if (!isset($map['by_spop_valid'])) {
			$map['by_spop_valid'] = array();
		}
		if (!isset($map['by_spop_valid'][$spop_key])) {
			$map['by_spop_valid'][$spop_key] = array();
		}
		$map['by_spop_valid'][$spop_key][] = $row;
	}
}

/**
 * Ambil baris persediaan terbaru dari map (by_id) setelah update.
 */
function persediaan_recalculate_map_row_fresh($map, $row)
{
	if (empty($row)) {
		return null;
	}
	$id = (int) $row->id;
	if (!empty($map['by_id'][$id])) {
		return $map['by_id'][$id];
	}
	return $row;
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
	if (!$existing) {
		$spop_cek = isset($row_pembelian->spop) ? $row_pembelian->spop : '';
		if (persediaan_recalculate_spop_valid($spop_cek) && is_array($map)) {
			$existing = persediaan_generate_recalculate_find_target_by_spop_row($row_pembelian, $map);
		}
	}
	if (!$existing) {
		$nama_cek = isset($row_pembelian->uraian) ? $row_pembelian->uraian : '';
		$harga_cek = isset($row_pembelian->harga_satuan) ? $row_pembelian->harga_satuan : '';
		$spop_cek = isset($row_pembelian->spop) ? $row_pembelian->spop : '';
		$satuan_cek = isset($row_pembelian->satuan) ? $row_pembelian->satuan : '';
		$existing = persediaan_generate_recalculate_find_by_nama_hpp_spop($map, $nama_cek, $satuan_cek, $harga_cek, $spop_cek);
	}
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
		if (!$existing) {
			$existing = persediaan_recalculate_find_persediaan_valid_spop_tanpa_spop_pembelian($row, $map);
		}
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
				. ' (uraian+satuan+harga_satuan+spop = namabarang+satuan+hpp+spop, bulan sama';
			if (persediaan_recalculate_spop_kosong_atau_nol(isset($row->spop) ? $row->spop : '')
				&& persediaan_recalculate_spop_valid(isset($existing->spop) ? $existing->spop : '')) {
				$alasan .= ' | pembelian spop kosong/0 → pakai persediaan spop=' . trim((string) $existing->spop);
			}
			$alasan .= ')';
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
	$hpp_key = persediaan_recalculate_angka_match_key($hpp);

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
 * Nilai SPOP untuk perbandingan (kosong/0 → "", angka → tanpa leading zero).
 */
function persediaan_recalculate_spop_match_value($spop)
{
	$s = persediaan_recalculate_normalize_spop($spop);
	if (persediaan_recalculate_spop_kosong_atau_nol($s)) {
		return '';
	}
	if (preg_match('/^\d+$/', $s)) {
		return (string) (int) $s;
	}

	return $s;
}

/**
 * Bandingkan SPOP pembelian vs persediaan (keduanya kosong/0 = cocok).
 * Jika salah satu terisi, harus sama persis (termasuk filter numerik 013 = 13).
 */
function persediaan_recalculate_spop_cocok($spop_a, $spop_b)
{
	$a = persediaan_recalculate_spop_match_value($spop_a);
	$b = persediaan_recalculate_spop_match_value($spop_b);
	if ($a !== '' && $b !== '' && strcasecmp($a, $b) !== 0) {
		return false;
	}

	return ($a === '' && $b === '') || strcasecmp($a, $b) === 0;
}

/**
 * SPOP kosong atau nol ("" / "0" setelah trim).
 */
function persediaan_recalculate_spop_kosong_atau_nol($spop)
{
	$s = persediaan_recalculate_normalize_spop($spop);
	return ($s === '' || $s === '0');
}

/**
 * SPOP terisi dan bukan "0".
 */
function persediaan_recalculate_spop_valid($spop)
{
	return !persediaan_recalculate_spop_kosong_atau_nol($spop);
}

/**
 * Kunci grup persediaan beli=0: namabarang + sa + satuan (case-insensitive) + hpp.
 */
function persediaan_recalculate_group_key_beli_nol($row)
{
	$CI = function_exists('get_instance') ? get_instance() : null;
	if ($CI) {
		$CI->load->helper('persediaan_display');
	}

	$nama = persediaan_recalculate_normalize_nama(
		persediaan_recalculate_sanitize_nama_persediaan(isset($row->namabarang) ? $row->namabarang : '')
	);
	$satuan = persediaan_recalculate_satuan_key(
		persediaan_recalculate_sanitize_satuan_persediaan(isset($row->satuan) ? $row->satuan : '')
	);
	if ($nama === '' || $satuan === '') {
		return '';
	}

	$beli = (int) floor(persediaan_parse_angka(isset($row->beli) ? $row->beli : 0));
	if ($beli !== 0) {
		return '';
	}

	$sa = (string) (int) floor(persediaan_parse_angka(isset($row->sa) ? $row->sa : 0));
	$hpp = (string) (int) floor(persediaan_parse_angka(isset($row->hpp) ? $row->hpp : 0));

	return $nama . '|sa:' . $sa . '|sat:' . $satuan . '|hpp:' . $hpp;
}

/**
 * Hapus baris persediaan beli=0 dengan spop kosong/0 jika ada baris lain
 * (namabarang+sa+satuan+hpp+beli=0 sama, satuan tidak case-sensitive) yang spop-nya valid.
 */
function persediaan_recalculate_cleanup_duplikat_spop_kosong_beli_nol($CI, $tanggal_beli)
{
	$stats = array(
		'deleted' => 0,
		'groups' => 0,
		'details' => array(),
	);

	if (!$CI->db->table_exists('persediaan') || trim((string) $tanggal_beli) === '') {
		return $stats;
	}

	$CI->load->helper('persediaan_display');
	$has_spop = $CI->db->field_exists('spop', 'persediaan');
	$spop_sql = $has_spop ? '`spop`' : "'' AS `spop`";

	$rows = $CI->db->query(
		"SELECT `id`, `namabarang`, `sa`, `satuan`, `hpp`, `beli`, {$spop_sql}
		FROM `persediaan`
		WHERE `tanggal_beli` = ?
		ORDER BY `id` ASC",
		array($tanggal_beli)
	)->result();

	$groups = array();
	foreach ($rows as $row) {
		$key = persediaan_recalculate_group_key_beli_nol($row);
		if ($key === '') {
			continue;
		}
		if (!isset($groups[$key])) {
			$groups[$key] = array('valid' => array(), 'kosong' => array());
		}

		$spop = $has_spop && isset($row->spop) ? $row->spop : '';
		if (persediaan_recalculate_spop_valid($spop)) {
			$groups[$key]['valid'][] = $row;
		} elseif (persediaan_recalculate_spop_kosong_atau_nol($spop)) {
			$groups[$key]['kosong'][] = $row;
		}
	}

	$delete_ids = array();
	foreach ($groups as $key => $g) {
		if (count($g['valid']) === 0 || count($g['kosong']) === 0) {
			continue;
		}

		$stats['groups']++;
		foreach ($g['kosong'] as $row) {
			$delete_ids[] = (int) $row->id;
			if (count($stats['details']) < 50) {
				$stats['details'][] = array(
					'id' => (int) $row->id,
					'namabarang' => isset($row->namabarang) ? $row->namabarang : '',
					'satuan' => isset($row->satuan) ? $row->satuan : '',
					'spop' => isset($row->spop) ? $row->spop : '',
					'group_key' => $key,
				);
			}
		}
	}

	if (count($delete_ids) === 0) {
		return $stats;
	}

	$chunks = array_chunk($delete_ids, 200);
	foreach ($chunks as $chunk) {
		$CI->db->where('tanggal_beli', $tanggal_beli);
		$CI->db->where_in('id', $chunk);
		$CI->db->delete('persediaan');
		$stats['deleted'] += (int) $CI->db->affected_rows();
	}

	return $stats;
}

/**
 * Pesan ringkas hasil cleanup spop kosong.
 */
function persediaan_recalculate_cleanup_spop_kosong_pesan($cleanup)
{
	if (!is_array($cleanup) || (int) (isset($cleanup['deleted']) ? $cleanup['deleted'] : 0) <= 0) {
		return '';
	}

	return ' Hapus duplikat spop kosong/0 (beli=0, ada spop valid): '
		. (int) $cleanup['deleted'] . ' baris'
		. ' (' . (int) (isset($cleanup['groups']) ? $cleanup['groups'] : 0) . ' grup).';
}

/**
 * Cari baris persediaan bulan target yang sudah memakai SPOP yang sama (spop valid).
 */
function persediaan_ensure_asal_generate_column($CI)
{
	if (!$CI->db->table_exists('persediaan') || $CI->db->field_exists('asal_generate', 'persediaan')) {
		return;
	}

	$after = $CI->db->field_exists('spop', 'persediaan') ? ' AFTER `spop`' : '';
	$CI->db->query(
		'ALTER TABLE `persediaan` ADD COLUMN `asal_generate` TINYINT(1) NOT NULL DEFAULT 0'
		. $after
		. " COMMENT '1=saldo awal hasil generate bulan lalu'"
	);
}

function persediaan_row_apply_asal_generate_flag(&$data, $is_generate, $CI = null)
{
	if ($CI === null && function_exists('get_instance')) {
		$CI = get_instance();
	}
	if (!$CI || !$CI->db->field_exists('asal_generate', 'persediaan')) {
		return;
	}

	$data['asal_generate'] = $is_generate ? 1 : 0;
}

function persediaan_row_has_transaksi_beli_penjualan($row)
{
	$beli = max(0, (int) floor(persediaan_parse_angka(isset($row->beli) ? $row->beli : 0)));
	$penj = max(0, (int) floor(persediaan_parse_angka(isset($row->penjualan) ? $row->penjualan : 0)));

	return ($beli > 0 || $penj > 0);
}

/**
 * Pola saldo awal hasil generate: sa>0 dan belum ada data beli/penjualan.
 */
function persediaan_row_is_pola_saldo_generate($row, $CI = null)
{
	if ($CI === null && function_exists('get_instance')) {
		$CI = get_instance();
	}

	if ($CI && $CI->db->field_exists('asal_generate', 'persediaan')) {
		$flag = isset($row->asal_generate) ? (int) $row->asal_generate : 0;
		if ($flag === 1) {
			return true;
		}
		if ($flag === 0 && persediaan_row_has_transaksi_beli_penjualan($row)) {
			return false;
		}
	}

	$sa = max(0, (int) floor(persediaan_parse_angka(isset($row->sa) ? $row->sa : 0)));

	return ($sa > 0 && !persediaan_row_has_transaksi_beli_penjualan($row));
}

/**
 * Skor prioritas keeper duplikat SPOP — lebih tinggi = lebih layak dipertahankan.
 * Utamakan baris dengan beli/penjualan (dari tabel transaksi), bukan saldo generate.
 */
function persediaan_duplikat_spop_keeper_score($row, $CI = null)
{
	$beli = max(0, (int) floor(persediaan_parse_angka(isset($row->beli) ? $row->beli : 0)));
	$penj = max(0, (int) floor(persediaan_parse_angka(isset($row->penjualan) ? $row->penjualan : 0)));
	$score = 0;

	if ($beli > 0) {
		$score += 100000000 + ($beli * 1000);
	}
	if ($penj > 0) {
		$score += 50000000 + ($penj * 1000);
	}
	if ($score === 0 && persediaan_row_is_pola_saldo_generate($row, $CI)) {
		$sa = max(0, (int) floor(persediaan_parse_angka(isset($row->sa) ? $row->sa : 0)));
		$score = 1 + min($sa, 999999);
	}

	return $score;
}

function persediaan_duplikat_spop_pick_keeper($rows, $CI = null)
{
	if (empty($rows) || !is_array($rows)) {
		return null;
	}

	$keeper = $rows[0];
	$best = persediaan_duplikat_spop_keeper_score($keeper, $CI);

	foreach ($rows as $row) {
		$score = persediaan_duplikat_spop_keeper_score($row, $CI);
		if ($score > $best || ($score === $best && (int) $row->id < (int) $keeper->id)) {
			$keeper = $row;
			$best = $score;
		}
	}

	return $keeper;
}

function persediaan_generate_recalculate_find_target_by_spop_row($ref_row, $map)
{
	if (empty($map) || empty($ref_row)) {
		return null;
	}

	$spop = isset($ref_row->spop) ? $ref_row->spop : '';
	if (!persediaan_recalculate_spop_valid($spop)) {
		return null;
	}

	$spop_key = persediaan_recalculate_spop_match_value($spop);
	if ($spop_key === '' || empty($map['by_spop_valid'][$spop_key]) || !is_array($map['by_spop_valid'][$spop_key])) {
		return null;
	}

	$candidates = $map['by_spop_valid'][$spop_key];
	if (count($candidates) === 1) {
		return $candidates[0];
	}

	$nama_ref = '';
	if (isset($ref_row->namabarang)) {
		$nama_ref = $ref_row->namabarang;
	} elseif (isset($ref_row->uraian)) {
		$nama_ref = $ref_row->uraian;
	}
	$n_ref = persediaan_recalculate_normalize_nama(
		persediaan_recalculate_sanitize_nama_persediaan($nama_ref)
	);

	if ($n_ref !== '') {
		$filtered = array();
		foreach ($candidates as $candidate) {
			$n_c = persediaan_recalculate_normalize_nama(
				persediaan_recalculate_sanitize_nama_persediaan(isset($candidate->namabarang) ? $candidate->namabarang : '')
			);
			if ($n_c !== '' && $n_c === $n_ref) {
				$filtered[] = $candidate;
			}
		}
		if (count($filtered) > 0) {
			$candidates = $filtered;
		}
	}

	$CI = function_exists('get_instance') ? get_instance() : null;

	return persediaan_duplikat_spop_pick_keeper($candidates, $CI);
}

/**
 * Hapus / gabung duplikat SPOP valid dalam bulan yang sama (1 spop = 1 baris persediaan).
 * Keeper: baris dengan beli/penjualan > 0 (data transaksi). Hapus baris beli=0 & penjualan=0 (saldo generate).
 */
function persediaan_recalculate_cleanup_duplikat_spop_sama_bulan($CI, $tanggal_beli)
{
	$stats = array(
		'deleted' => 0,
		'groups' => 0,
		'merged' => 0,
		'details' => array(),
	);

	if (!$CI->db->table_exists('persediaan') || trim((string) $tanggal_beli) === '') {
		return $stats;
	}

	if (!$CI->db->field_exists('spop', 'persediaan')) {
		return $stats;
	}

	persediaan_ensure_asal_generate_column($CI);
	$CI->load->helper('persediaan_display');
	$rows = $CI->db->query(
		"SELECT * FROM `persediaan` WHERE `tanggal_beli` = ? ORDER BY `id` ASC",
		array($tanggal_beli)
	)->result();

	$groups = array();
	foreach ($rows as $row) {
		$spop_key = persediaan_recalculate_spop_match_value(isset($row->spop) ? $row->spop : '');
		if ($spop_key === '') {
			continue;
		}
		if (!isset($groups[$spop_key])) {
			$groups[$spop_key] = array();
		}
		$groups[$spop_key][] = $row;
	}

	$tanggal_tampil = date('d/m/Y', strtotime($tanggal_beli));

	foreach ($groups as $spop_key => $group_rows) {
		if (count($group_rows) < 2) {
			continue;
		}

		$stats['groups']++;
		$keeper = persediaan_duplikat_spop_pick_keeper($group_rows, $CI);
		if (!$keeper) {
			continue;
		}

		$sa_merged = 0;
		$beli_merged = max(0, (int) floor(persediaan_parse_angka(isset($keeper->beli) ? $keeper->beli : 0)));
		$penj_merged = max(0, (int) floor(persediaan_parse_angka(isset($keeper->penjualan) ? $keeper->penjualan : 0)));
		$pecah_merged = max(0, (int) floor(persediaan_parse_angka(isset($keeper->pecah_satuan) ? $keeper->pecah_satuan : 0)));
		$prod_merged = max(0, (int) floor(persediaan_parse_angka(isset($keeper->bahan_produksi) ? $keeper->bahan_produksi : 0)));

		foreach ($group_rows as $gr) {
			$sa_gr = max(0, (int) floor(persediaan_parse_angka(isset($gr->sa) ? $gr->sa : 0)));
			$sa_merged = max($sa_merged, $sa_gr);

			if (persediaan_row_has_transaksi_beli_penjualan($gr)) {
				$beli_merged = max($beli_merged, (int) floor(persediaan_parse_angka(isset($gr->beli) ? $gr->beli : 0)));
				$penj_merged = max($penj_merged, (int) floor(persediaan_parse_angka(isset($gr->penjualan) ? $gr->penjualan : 0)));
			}
			if ((int) $gr->id !== (int) $keeper->id) {
				$pecah_merged = max($pecah_merged, (int) floor(persediaan_parse_angka(isset($gr->pecah_satuan) ? $gr->pecah_satuan : 0)));
				$prod_merged = max($prod_merged, (int) floor(persediaan_parse_angka(isset($gr->bahan_produksi) ? $gr->bahan_produksi : 0)));
			}
		}

		$gross = $sa_merged + $beli_merged;
		$penj_merged = min($penj_merged, $gross);
		$sisa_penj = max(0, $gross - $penj_merged);
		$pecah_merged = min($pecah_merged, $sisa_penj);
		$sisa_pecah = max(0, $sisa_penj - $pecah_merged);
		$prod_merged = min($prod_merged, $sisa_pecah);
		$total_merged = max(0, $gross - $penj_merged - $pecah_merged - $prod_merged);

		$hpp = persediaan_parse_angka(isset($keeper->hpp) ? $keeper->hpp : 0);
		$nilai_merged = (int) floor($total_merged * $hpp);

		$upd = array(
			'sa' => (string) $sa_merged,
			'beli' => (string) $beli_merged,
			'penjualan' => (string) $penj_merged,
			'total_10' => (string) $total_merged,
			'nilai_persediaan' => (string) $nilai_merged,
			'tanggal' => $tanggal_tampil,
		);
		if ($CI->db->field_exists('pecah_satuan', 'persediaan')) {
			$upd['pecah_satuan'] = (string) $pecah_merged;
		}
		if ($CI->db->field_exists('bahan_produksi', 'persediaan')) {
			$upd['bahan_produksi'] = (string) $prod_merged;
		}
		if ($CI->db->field_exists('tuj', 'persediaan')) {
			$upd['tuj'] = (string) $total_merged;
		}
		persediaan_row_apply_asal_generate_flag($upd, false, $CI);

		$CI->db->where('id', (int) $keeper->id);
		$CI->db->update('persediaan', $upd);
		$stats['merged']++;

		$delete_ids = array();
		foreach ($group_rows as $gr) {
			if ((int) $gr->id === (int) $keeper->id) {
				continue;
			}

			$hapus = false;
			if (!persediaan_row_has_transaksi_beli_penjualan($gr)) {
				$hapus = true;
			} elseif (persediaan_row_has_transaksi_beli_penjualan($keeper)) {
				$hapus = true;
			} elseif ((int) $gr->id > (int) $keeper->id) {
				$hapus = true;
			}

			if (!$hapus) {
				continue;
			}

			$delete_ids[] = (int) $gr->id;
			if (count($stats['details']) < 50) {
				$stats['details'][] = array(
					'id' => (int) $gr->id,
					'keeper_id' => (int) $keeper->id,
					'spop' => $spop_key,
					'namabarang' => isset($gr->namabarang) ? $gr->namabarang : '',
					'sa' => isset($gr->sa) ? $gr->sa : '',
					'beli' => isset($gr->beli) ? $gr->beli : '',
					'penjualan' => isset($gr->penjualan) ? $gr->penjualan : '',
					'asal' => persediaan_row_is_pola_saldo_generate($gr, $CI) ? 'generate' : 'transaksi',
				);
			}
		}

		if (count($delete_ids) > 0) {
			$CI->db->where('tanggal_beli', $tanggal_beli);
			$CI->db->where_in('id', $delete_ids);
			$CI->db->delete('persediaan');
			$stats['deleted'] += (int) $CI->db->affected_rows();
		}
	}

	return $stats;
}

function persediaan_recalculate_cleanup_duplikat_spop_sama_pesan($cleanup)
{
	if (!is_array($cleanup) || (int) (isset($cleanup['deleted']) ? $cleanup['deleted'] : 0) <= 0) {
		return '';
	}

	return ' Hapus duplikat spop sama dalam bulan: '
		. (int) $cleanup['deleted'] . ' baris saldo generate (beli=0 & penjualan=0)'
		. ' (' . (int) (isset($cleanup['groups']) ? $cleanup['groups'] : 0) . ' grup, gabung '
		. (int) (isset($cleanup['merged']) ? $cleanup['merged'] : 0) . ' baris transaksi/pembelian).';
}

/**
 * Akumulasi statistik cleanup duplikat spop sama ke state generate & recalculate.
 */
function persediaan_generate_recalculate_merge_cleanup_duplikat_spop_state(&$state, $cleanup)
{
	if (!is_array($cleanup)) {
		return;
	}
	if (!isset($state['cleanup_duplikat_spop']) || !is_array($state['cleanup_duplikat_spop'])) {
		$state['cleanup_duplikat_spop'] = array('deleted' => 0, 'groups' => 0, 'merged' => 0);
	}
	$state['cleanup_duplikat_spop']['deleted'] = (int) (isset($state['cleanup_duplikat_spop']['deleted'])
		? $state['cleanup_duplikat_spop']['deleted'] : 0)
		+ (int) (isset($cleanup['deleted']) ? $cleanup['deleted'] : 0);
	$state['cleanup_duplikat_spop']['groups'] = (int) (isset($state['cleanup_duplikat_spop']['groups'])
		? $state['cleanup_duplikat_spop']['groups'] : 0)
		+ (int) (isset($cleanup['groups']) ? $cleanup['groups'] : 0);
	$state['cleanup_duplikat_spop']['merged'] = (int) (isset($state['cleanup_duplikat_spop']['merged'])
		? $state['cleanup_duplikat_spop']['merged'] : 0)
		+ (int) (isset($cleanup['merged']) ? $cleanup['merged'] : 0);
}

/**
 * Akumulasi statistik cleanup spop ke state generate & recalculate.
 */
function persediaan_generate_recalculate_merge_cleanup_spop_state(&$state, $cleanup)
{
	if (!is_array($cleanup)) {
		return;
	}
	if (!isset($state['cleanup_spop_kosong']) || !is_array($state['cleanup_spop_kosong'])) {
		$state['cleanup_spop_kosong'] = array('deleted' => 0, 'groups' => 0);
	}
	$state['cleanup_spop_kosong']['deleted'] = (int) (isset($state['cleanup_spop_kosong']['deleted'])
		? $state['cleanup_spop_kosong']['deleted'] : 0)
		+ (int) (isset($cleanup['deleted']) ? $cleanup['deleted'] : 0);
	$state['cleanup_spop_kosong']['groups'] = (int) (isset($state['cleanup_spop_kosong']['groups'])
		? $state['cleanup_spop_kosong']['groups'] : 0)
		+ (int) (isset($cleanup['groups']) ? $cleanup['groups'] : 0);
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

	return $base . '|spop:' . persediaan_recalculate_spop_key_for_sync($spop);
}

/**
 * Kunci pengelompokan batch pembelian: SPOP + uraian (jumlah dijumlahkan).
 * Fallback ke kunci penuh nama+satuan+hpp+spop jika SPOP/uraian tidak lengkap.
 */
function persediaan_recalculate_pembelian_group_key($nama, $satuan, $hpp, $spop)
{
	$spop_key = persediaan_recalculate_spop_key_for_sync($spop);
	$nama_n = persediaan_recalculate_normalize_nama($nama);
	if ($spop_key !== '' && $nama_n !== '') {
		return 'spopuraian:' . $spop_key . '|' . $nama_n;
	}

	return persediaan_recalculate_sync_pembelian_persediaan_key($nama, $satuan, $hpp, $spop);
}

/**
 * Rentang tanggal tgl_po (sama seperti halaman list pembelian: BETWEEN datetime).
 */
function persediaan_recalculate_pembelian_date_between_params($ctx)
{
	$tgl_awal = isset($ctx['tgl_awal']) ? trim((string) $ctx['tgl_awal']) : '';
	$tgl_akhir = isset($ctx['tgl_akhir']) ? trim((string) $ctx['tgl_akhir']) : '';
	if ($tgl_awal === '' || $tgl_akhir === '') {
		return array('', '');
	}

	return array($tgl_awal . ' 00:00:00', $tgl_akhir . ' 23:59:59');
}

/**
 * Statistik terakhir dari pembangunan queue pembelian generate/recalculate.
 */
function persediaan_generate_recalculate_pembelian_queue_stats_get()
{
	return isset($GLOBALS['_pembelian_gen_recalc_queue_stats']) && is_array($GLOBALS['_pembelian_gen_recalc_queue_stats'])
		? $GLOBALS['_pembelian_gen_recalc_queue_stats']
		: array();
}

/**
 * Baris ringkasan HTML pembelian generate/recalculate (jelaskan 169 vs 121).
 */
function persediaan_gen_recalc_pembelian_summary_html_line($summary)
{
	$summary = is_array($summary) ? $summary : array();
	$db_rows = (int) (isset($summary['pembelian_db_rows']) ? $summary['pembelian_db_rows'] : (isset($summary['total_pembelian']) ? $summary['total_pembelian'] : 0));
	$queue = (int) (isset($summary['total_pembelian']) ? $summary['total_pembelian'] : 0);
	$merged = (int) (isset($summary['pembelian_merged_rows']) ? $summary['pembelian_merged_rows'] : 0);
	$skipped = (int) (isset($summary['pembelian_skipped_jumlah_nol']) ? $summary['pembelian_skipped_jumlah_nol'] : 0);

	$line = 'Pembelian tbl_pembelian: <strong>' . $db_rows . '</strong> record (jumlah &gt; 0)';
	if ($merged > 0) {
		$line .= ' &rarr; diproses <strong>' . $queue . '</strong> batch (gabung SPOP+uraian: <strong>' . $merged . '</strong> record dijumlahkan)';
	} elseif ($queue !== $db_rows) {
		$line .= ' &rarr; diproses <strong>' . $queue . '</strong> batch';
	} else {
		$line .= ' &rarr; diproses <strong>' . $queue . '</strong> batch (per record)';
	}
	if ($skipped > 0) {
		$line .= ', lewati jumlah 0: <strong>' . $skipped . '</strong>';
	}
	$line .= '<br/><span class="text-muted small">Halaman list pembelian bisa menampilkan lebih banyak baris (mis. 169) karena ada <strong>baris subtotal SPOP</strong> (background kuning) — bukan record database.</span>';
	$line .= '<br/>Update beli ke persediaan: <strong>' . (int) (isset($summary['pembelian_update']) ? $summary['pembelian_update'] : 0) . '</strong>, '
		. 'Insert baru: <strong>' . (int) (isset($summary['pembelian_insert']) ? $summary['pembelian_insert'] : 0) . '</strong>';
	if ((int) (isset($summary['pembelian_gagal']) ? $summary['pembelian_gagal'] : 0) > 0) {
		$line .= ', Gagal: <strong class="text-danger">' . (int) $summary['pembelian_gagal'] . '</strong>';
	}

	return $line;
}

/**
 * Kunci SPOP untuk lookup ("" dan "0" dianggap sama).
 */
function persediaan_recalculate_spop_key_for_sync($spop)
{
	return strtolower(persediaan_recalculate_spop_match_value($spop));
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
		"SELECT `uraian`, `satuan`, `harga_satuan`, `jumlah`, `tgl_po`, {$spop_sql}
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
		'by_generate_key' => array(),
		'by_nama_satuan' => array(),
		'by_nama' => array(),
		'by_kode' => array(),
		'by_spop_valid' => array(),
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

/**
 * Kembalikan total_10 ke stok kotor (sa + beli) sebelum recalculate penjualan.
 */
function persediaan_recalculate_restore_gross_total_10_bulan($CI, $tanggal_beli)
{
	$CI->load->helper('persediaan_display');

	$rows = $CI->db->query(
		"SELECT `id`, `sa`, `beli`, `hpp` FROM `persediaan` WHERE `tanggal_beli` = ? ORDER BY `id` ASC",
		array($tanggal_beli)
	)->result();

	$updated = 0;
	foreach ($rows as $row) {
		$sa = max(0, (int) floor(persediaan_parse_angka(isset($row->sa) ? $row->sa : 0)));
		$beli = max(0, (int) floor(persediaan_parse_angka(isset($row->beli) ? $row->beli : 0)));
		$hpp = persediaan_parse_angka(isset($row->hpp) ? $row->hpp : 0);
		$total_10 = max(0, $sa + $beli);
		$nilai = (int) floor($total_10 * $hpp);

		$update = array(
			'total_10' => (string) $total_10,
			'nilai_persediaan' => (string) $nilai,
		);
		if ($CI->db->field_exists('tuj', 'persediaan')) {
			$update['tuj'] = (string) $total_10;
		}

		$CI->db->where('id', (int) $row->id);
		if ($CI->db->update('persediaan', $update)) {
			$updated++;
		}
	}

	return array(
		'updated' => $updated,
		'total_rows' => count($rows),
	);
}

/**
 * Persiapan fase penjualan: restore total_10 kotor lalu reset penjualan + kolom unit.
 * Jika $after_keluar_phases = true (produksi & pecah sudah diproses), lewati restore gross.
 */
function persediaan_recalculate_prepare_penjualan_bulan($CI, $ctx, $after_keluar_phases = false)
{
	$tanggal_beli = isset($ctx['tanggal_beli']) ? $ctx['tanggal_beli'] : '';
	if (isset($ctx['tanggal_beli_target']) && trim((string) $ctx['tanggal_beli_target']) !== '') {
		$tanggal_beli = $ctx['tanggal_beli_target'];
	}
	if (trim((string) $tanggal_beli) === '') {
		return array('restore_gross' => array('updated' => 0), 'reset' => array('record_direset' => 0));
	}

	if (function_exists('persediaan_generate_recalculate_ensure_unit_kolom_dari_transaksi')) {
		persediaan_generate_recalculate_ensure_unit_kolom_dari_transaksi($CI, $ctx);
	}

	$restore = array('updated' => 0, 'skipped' => 1);
	if (!$after_keluar_phases) {
		$restore = persediaan_recalculate_restore_gross_total_10_bulan($CI, $tanggal_beli);
	}
	$reset = persediaan_recalculate_reset_penjualan_persediaan_bulan($CI, $tanggal_beli);

	return array(
		'restore_gross' => $restore,
		'reset' => $reset,
		'tanggal_beli' => $tanggal_beli,
		'after_keluar_phases' => $after_keluar_phases ? 1 : 0,
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
		$cur = $CI->db->where('id', (int) $pers_id)->limit(1)->get('persediaan')->row();
		if (!$cur) {
			continue;
		}

		$sa = max(0, (int) floor(persediaan_parse_angka(isset($cur->sa) ? $cur->sa : 0)));
		$beli = max(0, (int) floor(persediaan_parse_angka(isset($cur->beli) ? $cur->beli : 0)));
		$hpp = persediaan_parse_angka(isset($cur->hpp) ? $cur->hpp : 0);
		$penj = max(0, (int) floor(isset($data['penjualan']) ? $data['penjualan'] : 0));
		$pecah = max(0, (int) floor(persediaan_parse_angka(isset($cur->pecah_satuan) ? $cur->pecah_satuan : 0)));
		$produksi = max(0, (int) floor(persediaan_parse_angka(isset($cur->bahan_produksi) ? $cur->bahan_produksi : 0)));
		$gross = $sa + $beli;
		$penj = min($penj, $gross);
		$sisa_setelah_penj = max(0, $gross - $penj);
		$pecah = min($pecah, $sisa_setelah_penj);
		$sisa_setelah_pecah = max(0, $sisa_setelah_penj - $pecah);
		$produksi = min($produksi, $sisa_setelah_pecah);
		$total_10 = max(0, $gross - $penj - $pecah - $produksi);
		$nilai = (int) floor($total_10 * $hpp);

		$units = isset($data['units']) && is_array($data['units']) ? $data['units'] : array();
		$sum_unit = 0;
		foreach ($units as $nilai_unit) {
			$sum_unit += max(0, (int) floor($nilai_unit));
		}
		if ($sum_unit > $penj && $penj > 0 && $sum_unit > 0) {
			$ratio = $penj / $sum_unit;
			foreach ($units as $kolom => $nilai_unit) {
				$units[$kolom] = (int) floor(max(0, (int) floor($nilai_unit)) * $ratio);
			}
		} elseif ($sum_unit > $penj) {
			foreach ($units as $kolom => $nilai_unit) {
				$units[$kolom] = 0;
			}
		}

		$update = array(
			'penjualan' => (string) $penj,
			'total_10' => (string) $total_10,
			'nilai_persediaan' => (string) $nilai,
		);
		if ($CI->db->field_exists('tuj', 'persediaan')) {
			$update['tuj'] = (string) $total_10;
		}

		foreach ($units as $kolom => $nilai) {
			$db_col = persediaan_resolve_db_field_name($CI, $kolom);
			if ($CI->db->field_exists($db_col, 'persediaan')) {
				$update[$db_col] = (string) max(0, (int) floor($nilai));
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

	$CI->load->helper('persediaan_display');

	$uuid_barang = trim((string) $row->uuid_barang);
	$nama_norm = persediaan_recalculate_normalize_nama(
		persediaan_recalculate_sanitize_nama_persediaan(isset($row->namabarang) ? $row->namabarang : '')
	);
	$satuan_pers = isset($row->satuan) ? $row->satuan : '';
	$hpp_pers = isset($row->hpp) ? $row->hpp : '';
	$spop_pers = isset($row->spop) ? $row->spop : '';

	if ($satuan_pers === '' || ($nama_norm === '' && $uuid_barang === '')) {
		return 0;
	}

	$spop_sql = $CI->db->field_exists('spop', 'tbl_penjualan')
		? 'TRIM(COALESCE(`spop`, \'\')) AS `spop`'
		: "'' AS `spop`";
	$list = $CI->db->query(
		"SELECT `uuid_barang`, `nama_barang`, `satuan`, `harga_satuan`, `jumlah`, {$spop_sql}
		FROM `tbl_penjualan`
		WHERE DATE(`tgl_jual`) >= ? AND DATE(`tgl_jual`) <= ?",
		array($tgl_awal, $tgl_akhir)
	)->result();

	$total = 0;
	foreach ($list as $r) {
		$uuid_j = trim((string) (isset($r->uuid_barang) ? $r->uuid_barang : ''));
		$cocok = false;
		if ($uuid_barang !== '' && $uuid_j !== '' && $uuid_barang === $uuid_j) {
			$cocok = true;
		} elseif ($nama_norm !== '') {
			$nama_j = persediaan_recalculate_normalize_nama(
				persediaan_recalculate_sanitize_nama_persediaan(isset($r->nama_barang) ? $r->nama_barang : '')
			);
			if ($nama_j !== '' && $nama_j === $nama_norm) {
				$cocok = true;
			}
		}
		if (!$cocok) {
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

		$total += persediaan_recalculate_parse_jumlah_penjualan(isset($r->jumlah) ? $r->jumlah : 0);
	}

	return max(0, (int) $total);
}

/**
 * Agregasi penjualan + pecah_satuan (sumber) + bahan_produksi per id persediaan bulan target.
 * Cocokkan: namabarang/uraian + satuan + hpp/harga_satuan + spop.
 */
function persediaan_generate_recalculate_build_keluar_agg_by_pers_id($CI, $ctx, $map)
{
	$CI->load->helper('persediaan_display');
	$agg = array();

	if ($CI->db->table_exists('tbl_penjualan')) {
		$list = $CI->db->query(
			"SELECT * FROM `tbl_penjualan`
			WHERE `tgl_jual` IS NOT NULL AND `tgl_jual` <> '0000-00-00'
			AND DATE(`tgl_jual`) >= ? AND DATE(`tgl_jual`) <= ?
			ORDER BY `id` ASC",
			array($ctx['tgl_awal'], $ctx['tgl_akhir'])
		)->result();

		foreach ($list as $row_penjualan) {
			$jumlah = persediaan_recalculate_parse_jumlah_penjualan(isset($row_penjualan->jumlah) ? $row_penjualan->jumlah : 0);
			if ($jumlah <= 0) {
				continue;
			}
			$match = persediaan_recalculate_match_penjualan_ke_persediaan($CI, $row_penjualan, $map);
			if (!$match || empty($match['row'])) {
				continue;
			}
			$pers_id = (int) $match['row']->id;
			if (!isset($agg[$pers_id])) {
				$agg[$pers_id] = array('penjualan' => 0, 'pecah_satuan' => 0, 'bahan_produksi' => 0);
			}
			$agg[$pers_id]['penjualan'] += $jumlah;
		}
	}

	if ($CI->db->table_exists('tbl_pembelian_pecah_satuan') && $CI->db->field_exists('pecah_satuan', 'persediaan')) {
		$list_pecah = $CI->db->query(
			"SELECT * FROM `tbl_pembelian_pecah_satuan`
			WHERE DATE(COALESCE(NULLIF(TRIM(`proses_input`), ''), `tgl_po`)) >= ?
			AND DATE(COALESCE(NULLIF(TRIM(`proses_input`), ''), `tgl_po`)) <= ?
			ORDER BY `id` ASC",
			array($ctx['tgl_awal'], $ctx['tgl_akhir'])
		)->result();

		foreach ($list_pecah as $row_pecah) {
			$jumlah = max(0, (int) floor(persediaan_parse_angka(isset($row_pecah->jumlah) ? $row_pecah->jumlah : 0)));
			if ($jumlah <= 0) {
				continue;
			}
			$pick = persediaan_generate_recalculate_find_persediaan_for_pecah_source($row_pecah, $map);
			if (!$pick) {
				continue;
			}
			$pers_id = (int) $pick->id;
			if (!isset($agg[$pers_id])) {
				$agg[$pers_id] = array('penjualan' => 0, 'pecah_satuan' => 0, 'bahan_produksi' => 0);
			}
			$agg[$pers_id]['pecah_satuan'] += $jumlah;
		}
	}

	if ($CI->db->table_exists('sys_unit_produk_bahan') && $CI->db->field_exists('bahan_produksi', 'persediaan')) {
		$list_prod = $CI->db->query(
			"SELECT * FROM `sys_unit_produk_bahan`
			WHERE `tgl_transaksi` IS NOT NULL AND `tgl_transaksi` <> '0000-00-00'
			AND DATE(`tgl_transaksi`) >= ? AND DATE(`tgl_transaksi`) <= ?
			ORDER BY `id` ASC",
			array($ctx['tgl_awal'], $ctx['tgl_akhir'])
		)->result();

		foreach ($list_prod as $row_bahan) {
			$jumlah = max(0, (int) floor(persediaan_parse_angka(isset($row_bahan->jumlah_bahan) ? $row_bahan->jumlah_bahan : 0)));
			if ($jumlah <= 0) {
				continue;
			}
			$pick = persediaan_generate_recalculate_find_persediaan_for_produksi_bahan($CI, $row_bahan, $map);
			if (!$pick) {
				continue;
			}
			$pers_id = (int) $pick->id;
			if (!isset($agg[$pers_id])) {
				$agg[$pers_id] = array('penjualan' => 0, 'pecah_satuan' => 0, 'bahan_produksi' => 0);
			}
			$agg[$pers_id]['bahan_produksi'] += $jumlah;
		}
	}

	return $agg;
}

/**
 * Finalisasi keluar stok per baris persediaan (nama+satuan+hpp+spop):
 * total_10 = (sa+beli) - (penjualan + pecah_satuan + bahan_produksi).
 */
function persediaan_generate_recalculate_finalize_keluar_per_persediaan_bulan($CI, $ctx, $penjualan_accum = null)
{
	$CI->load->helper('persediaan_display');

	$tanggal_beli = isset($ctx['tanggal_beli_target']) ? $ctx['tanggal_beli_target'] : $ctx['tanggal_beli'];
	if (trim((string) $tanggal_beli) === '') {
		return array('updated' => 0, 'with_penjualan' => 0, 'with_pecah' => 0, 'with_produksi' => 0);
	}

	persediaan_recalculate_prepare_produksi_bulan($CI, $ctx);
	persediaan_recalculate_prepare_pecah_satuan_bulan($CI, $ctx);

	$map = persediaan_recalculate_build_map_persediaan_bulan($CI, $tanggal_beli);
	$keluar_agg = persediaan_generate_recalculate_build_keluar_agg_by_pers_id($CI, $ctx, $map);
	$accum = is_array($penjualan_accum) ? $penjualan_accum : array();

	$rows = $CI->db->query(
		"SELECT * FROM `persediaan` WHERE `tanggal_beli` = ? ORDER BY `namabarang` ASC, `id` ASC",
		array($tanggal_beli)
	)->result();

	$stats = array(
		'updated' => 0,
		'with_penjualan' => 0,
		'with_pecah' => 0,
		'with_produksi' => 0,
		'total_rows' => count($rows),
	);

	foreach ($rows as $row) {
		$pers_id = (int) $row->id;
		$sa = max(0, (int) floor(persediaan_parse_angka(isset($row->sa) ? $row->sa : 0)));
		$beli = max(0, (int) floor(persediaan_parse_angka(isset($row->beli) ? $row->beli : 0)));
		$hpp = persediaan_parse_angka(isset($row->hpp) ? $row->hpp : 0);
		$gross = $sa + $beli;

		$penj = 0;
		if (isset($accum[$pers_id]['penjualan'])) {
			$penj = max(0, (int) floor($accum[$pers_id]['penjualan']));
		} elseif (isset($keluar_agg[$pers_id]['penjualan'])) {
			$penj = max(0, (int) floor($keluar_agg[$pers_id]['penjualan']));
		} else {
			$penj = persediaan_recalculate_sum_penjualan_for_row($CI, $ctx['tgl_awal'], $ctx['tgl_akhir'], $row);
		}

		$pecah = isset($keluar_agg[$pers_id]['pecah_satuan'])
			? max(0, (int) floor($keluar_agg[$pers_id]['pecah_satuan']))
			: 0;
		$produksi = isset($keluar_agg[$pers_id]['bahan_produksi'])
			? max(0, (int) floor($keluar_agg[$pers_id]['bahan_produksi']))
			: 0;

		$penj = min($penj, $gross);
		$sisa_setelah_penj = max(0, $gross - $penj);
		$pecah = min($pecah, $sisa_setelah_penj);
		$sisa_setelah_pecah = max(0, $sisa_setelah_penj - $pecah);
		$produksi = min($produksi, $sisa_setelah_pecah);

		$total_10 = max(0, $gross - $penj - $pecah - $produksi);
		$nilai = (int) floor($total_10 * $hpp);

		$update = array(
			'penjualan' => (string) (int) floor($penj),
			'total_10' => (string) (int) floor($total_10),
			'nilai_persediaan' => (string) $nilai,
		);
		if ($CI->db->field_exists('pecah_satuan', 'persediaan')) {
			$update['pecah_satuan'] = (string) (int) floor($pecah);
		}
		if ($CI->db->field_exists('bahan_produksi', 'persediaan')) {
			$update['bahan_produksi'] = (string) (int) floor($produksi);
		}
		if ($CI->db->field_exists('tuj', 'persediaan')) {
			$update['tuj'] = (string) (int) floor($total_10);
		}

		if (isset($accum[$pers_id]['units']) && is_array($accum[$pers_id]['units'])) {
			$units = $accum[$pers_id]['units'];
			$sum_unit = 0;
			foreach ($units as $nilai_unit) {
				$sum_unit += max(0, (int) floor($nilai_unit));
			}
			if ($sum_unit > $penj && $penj > 0 && $sum_unit > 0) {
				$ratio = $penj / $sum_unit;
				foreach ($units as $kolom => $nilai_unit) {
					$units[$kolom] = (int) floor(max(0, (int) floor($nilai_unit)) * $ratio);
				}
			} elseif ($sum_unit > $penj) {
				foreach ($units as $kolom => $nilai_unit) {
					$units[$kolom] = 0;
				}
			}
			foreach ($units as $kolom => $nilai_unit) {
				if ($CI->db->field_exists($kolom, 'persediaan')) {
					$update[$kolom] = (string) max(0, (int) floor($nilai_unit));
				}
			}
		}

		$CI->db->where('id', $pers_id);
		if ($CI->db->update('persediaan', $update)) {
			$stats['updated']++;
			if ($penj > 0) {
				$stats['with_penjualan']++;
			}
			if ($pecah > 0) {
				$stats['with_pecah']++;
			}
			if ($produksi > 0) {
				$stats['with_produksi']++;
			}
		}
	}

	return $stats;
}

/**
 * Terapkan target pecah satuan (sa/total_10 barang baru) setelah finalize sumber.
 */
function persediaan_generate_recalculate_apply_pecah_satuan_targets_bulan($CI, $ctx)
{
	$CI->load->helper('persediaan_display');

	if (!$CI->db->table_exists('tbl_pembelian_pecah_satuan') || !$CI->db->field_exists('pecah_satuan', 'persediaan')) {
		return array('matched' => 0, 'skipped' => 0, 'target_insert' => 0);
	}

	$tanggal_beli = isset($ctx['tanggal_beli_target']) ? $ctx['tanggal_beli_target'] : $ctx['tanggal_beli'];
	$map = persediaan_recalculate_build_map_persediaan_bulan($CI, $tanggal_beli);

	$list = $CI->db->query(
		"SELECT * FROM `tbl_pembelian_pecah_satuan`
		WHERE DATE(COALESCE(NULLIF(TRIM(`proses_input`), ''), `tgl_po`)) >= ?
		AND DATE(COALESCE(NULLIF(TRIM(`proses_input`), ''), `tgl_po`)) <= ?
		ORDER BY `id` ASC",
		array($ctx['tgl_awal'], $ctx['tgl_akhir'])
	)->result();

	$matched = 0;
	$skipped = 0;
	$target_insert = 0;

	foreach ($list as $row_pecah) {
		$jumlah_baru = max(0, (int) floor(persediaan_parse_angka(isset($row_pecah->jumlah_barang_baru) ? $row_pecah->jumlah_barang_baru : 0)));
		if ($jumlah_baru <= 0) {
			$skipped++;
			continue;
		}

		$pick_tgt = persediaan_generate_recalculate_find_persediaan_for_pecah_target($row_pecah, $map);
		if (!$pick_tgt) {
			$pick_tgt = persediaan_generate_recalculate_insert_persediaan_pecah_target($CI, $ctx, $row_pecah, $jumlah_baru, $map);
			if ($pick_tgt) {
				$target_insert++;
				$map = persediaan_recalculate_build_map_persediaan_bulan($CI, $tanggal_beli);
			}
		}

		if (!$pick_tgt) {
			$skipped++;
			continue;
		}

		$tgt = $CI->db->where('id', (int) $pick_tgt->id)->limit(1)->get('persediaan')->row();
		if (!$tgt) {
			$skipped++;
			continue;
		}

		$sa_lama = max(0, (int) floor(persediaan_parse_angka(isset($tgt->sa) ? $tgt->sa : 0)));
		$total_tgt_lama = max(0, (int) floor(persediaan_parse_angka(isset($tgt->total_10) ? $tgt->total_10 : 0)));
		$hpp_tgt = persediaan_parse_angka(isset($tgt->hpp) ? $tgt->hpp : 0);
		$sa_baru = $sa_lama + $jumlah_baru;
		$total_tgt_baru = $total_tgt_lama + $jumlah_baru;
		$nilai_tgt = (int) floor($total_tgt_baru * $hpp_tgt);

		$upd_tgt = array(
			'sa' => (string) $sa_baru,
			'total_10' => (string) $total_tgt_baru,
			'nilai_persediaan' => (string) $nilai_tgt,
		);
		if ($CI->db->field_exists('tuj', 'persediaan')) {
			$upd_tgt['tuj'] = (string) $total_tgt_baru;
		}
		$CI->db->where('id', (int) $tgt->id);
		$CI->db->update('persediaan', $upd_tgt);
		$matched++;
	}

	return array(
		'matched' => $matched,
		'skipped' => $skipped,
		'target_insert' => $target_insert,
	);
}

/**
 * Setelah fase penjualan batch: flush akumulator penjualan ke DB.
 * Jika produksi & pecah sudah diproses sebelumnya, tidak reset ulang bahan/pecah.
 */
function persediaan_generate_recalculate_finalize_keluar_after_penjualan($CI, $ctx, &$state)
{
	if (!empty($state['keluar_finalized'])) {
		return isset($state['keluar_finalize']) ? $state['keluar_finalize'] : array();
	}

	if (!empty($state['keluar_phases_done'])) {
		$accum = isset($state['penjualan_accum']) && is_array($state['penjualan_accum'])
			? $state['penjualan_accum']
			: array();
		$flushed = persediaan_recalculate_flush_penjualan_accum_net_bulan($CI, $accum);
		$reconcile = persediaan_generate_recalculate_reconcile_persediaan_bulan(
			$CI,
			isset($ctx['tanggal_beli_target']) ? $ctx['tanggal_beli_target'] : $ctx['tanggal_beli']
		);
		$finalize = array(
			'flushed' => (int) (isset($flushed['updated']) ? $flushed['updated'] : 0),
			'with_penjualan' => (int) (isset($flushed['with_penjualan']) ? $flushed['with_penjualan'] : 0),
			'updated' => (int) (isset($flushed['updated']) ? $flushed['updated'] : 0),
			'reconcile' => $reconcile,
			'keluar_phases_done' => 1,
		);
	} else {
		$finalize = persediaan_generate_recalculate_finalize_keluar_per_persediaan_bulan(
			$CI,
			$ctx,
			isset($state['penjualan_accum']) ? $state['penjualan_accum'] : null
		);
		$pecah_targets = persediaan_generate_recalculate_apply_pecah_satuan_targets_bulan($CI, $ctx);
		$finalize['pecah_targets'] = $pecah_targets;
		$finalize['matched'] = isset($finalize['with_penjualan']) ? (int) $finalize['with_penjualan'] : 0;
		$finalize['flushed'] = (int) (isset($finalize['updated']) ? $finalize['updated'] : 0);
	}

	$state['keluar_finalize'] = $finalize;
	$state['keluar_finalized'] = 1;

	return $finalize;
}

/**
 * Flush akumulator penjualan: total_10 -= penjualan, field penjualan & unit di-update.
 * Pecah satuan & bahan produksi tidak diubah (sudah diproses di fase sebelumnya).
 */
function persediaan_recalculate_flush_penjualan_accum_net_bulan($CI, $accum)
{
	$CI->load->helper('persediaan_display');

	$updated = 0;
	$with_penjualan = 0;

	if (!is_array($accum)) {
		return array('updated' => 0, 'with_penjualan' => 0);
	}

	foreach ($accum as $pers_id => $data) {
		$pers_id = (int) $pers_id;
		if ($pers_id <= 0) {
			continue;
		}

		$cur = $CI->db->where('id', $pers_id)->limit(1)->get('persediaan')->row();
		if (!$cur) {
			continue;
		}

		$penj = max(0, (int) floor(isset($data['penjualan']) ? $data['penjualan'] : 0));
		if ($penj <= 0) {
			continue;
		}

		$hpp = persediaan_parse_angka(isset($cur->hpp) ? $cur->hpp : 0);
		$total_10_lama = max(0, (int) floor(persediaan_parse_angka(isset($cur->total_10) ? $cur->total_10 : 0)));
		$total_10 = max(0, $total_10_lama - $penj);
		$nilai = (int) floor($total_10 * $hpp);

		$units = isset($data['units']) && is_array($data['units']) ? $data['units'] : array();
		$update = array(
			'penjualan' => (string) $penj,
			'total_10' => (string) $total_10,
			'nilai_persediaan' => (string) $nilai,
		);
		if ($CI->db->field_exists('tuj', 'persediaan')) {
			$update['tuj'] = (string) $total_10;
		}

		foreach ($units as $kolom => $nilai_unit) {
			$db_col = persediaan_resolve_db_field_name($CI, $kolom);
			if ($CI->db->field_exists($db_col, 'persediaan')) {
				$update[$db_col] = (string) max(0, (int) floor($nilai_unit));
			}
		}

		$CI->db->where('id', $pers_id);
		if ($CI->db->update('persediaan', $update)) {
			$updated++;
			$with_penjualan++;
		}
	}

	return array(
		'updated' => $updated,
		'with_penjualan' => $with_penjualan,
	);
}

/**
 * UUID persediaan bulan target yang valid untuk proses penjualan generate.
 */
function persediaan_generate_recalculate_valid_uuid_persediaan_bulan($CI, $tanggal_beli_target)
{
	$out = array();
	if (!$CI->db->field_exists('uuid_persediaan', 'persediaan')) {
		return $out;
	}

	$rows = $CI->db->query(
		"SELECT DISTINCT TRIM(`uuid_persediaan`) AS uuid_p
		FROM `persediaan`
		WHERE `tanggal_beli` = ?
		AND TRIM(COALESCE(`uuid_persediaan`, '')) <> ''",
		array($tanggal_beli_target)
	)->result();

	foreach ($rows as $row) {
		$uuid = trim((string) (isset($row->uuid_p) ? $row->uuid_p : ''));
		if ($uuid !== '') {
			$out[$uuid] = true;
		}
	}

	return $out;
}

/**
 * Update total_10 persediaan setelah recalculate penjualan:
 * total_10 = sa + beli - penjualan - pecah_satuan - bahan_produksi.
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
		$pecah = max(0, (int) floor(persediaan_parse_angka(isset($row->pecah_satuan) ? $row->pecah_satuan : 0)));
		$produksi = max(0, (int) floor(persediaan_parse_angka(isset($row->bahan_produksi) ? $row->bahan_produksi : 0)));

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

		$total_10 = max(0, (int) floor($sa + $beli - $jml_penjualan - $pecah - $produksi));
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
		return persediaan_recalculate_map_row_fresh($map, $map['by_sync_key'][$sync_key][0]);
	}

	$nama_key_hpp = persediaan_recalculate_nama_satuan_hpp_key($nama, $satuan, $harga);
	if ($nama_key_hpp !== '' && !empty($map['by_nama_key'][$nama_key_hpp])) {
		return persediaan_recalculate_map_row_fresh($map, $map['by_nama_key'][$nama_key_hpp][0]);
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

	$spop = isset($row_pembelian->spop) ? $row_pembelian->spop : '';
	if (!persediaan_generate_recalculate_stop_after_pembelian() && persediaan_recalculate_spop_valid($spop)) {
		$by_spop = persediaan_generate_recalculate_find_target_by_spop_row($row_pembelian, $map);
		if ($by_spop) {
			return $by_spop;
		}
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

	$spop_pem_valid = persediaan_recalculate_spop_valid($spop);

	foreach ($nama_var as $n_try) {
		foreach ($satuan_var as $s_try) {
			$key = persediaan_recalculate_sync_pembelian_persediaan_key($n_try, $s_try, $harga, $spop);
			if ($key !== '' && !empty($map['by_pembelian_sync_key'][$key])) {
				foreach ($map['by_pembelian_sync_key'][$key] as $candidate) {
					if (persediaan_recalculate_spop_cocok(
						isset($candidate->spop) ? $candidate->spop : '',
						$spop
					)) {
						return $candidate;
					}
				}
			}
		}
	}

	$n_only = persediaan_recalculate_normalize_nama($nama);
	if ($n_only === '' || empty($map['by_nama'][$n_only])) {
		return null;
	}

	foreach ($map['by_nama'][$n_only] as $row) {
		if ($spop_pem_valid && persediaan_recalculate_spop_valid(isset($row->spop) ? $row->spop : '')
			&& !persediaan_recalculate_spop_cocok(isset($row->spop) ? $row->spop : '', $spop)) {
			continue;
		}
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
 * Cari persediaan bulan target yang cocok SPOP + uraian/namabarang (tanpa wajib satuan+hpp sama).
 */
function persediaan_recalculate_find_persediaan_for_pembelian_spop_uraian($row_pembelian, $map)
{
	if (empty($map) || empty($row_pembelian)) {
		return null;
	}

	$spop = isset($row_pembelian->spop) ? $row_pembelian->spop : '';
	$nama = isset($row_pembelian->uraian) ? $row_pembelian->uraian : '';
	if (!persediaan_recalculate_spop_valid($spop) || trim((string) $nama) === '') {
		return null;
	}

	$nama_var = array(
		persediaan_recalculate_sanitize_nama_persediaan($nama),
		$nama,
		persediaan_recalculate_normalize_nama($nama),
	);
	$nama_var = array_values(array_unique(array_filter($nama_var, function ($v) {
		return trim((string) $v) !== '';
	}), SORT_REGULAR));

	foreach ($nama_var as $n_try) {
		$n_key = persediaan_recalculate_normalize_nama($n_try);
		if ($n_key === '' || empty($map['by_nama'][$n_key])) {
			continue;
		}
		foreach ($map['by_nama'][$n_key] as $candidate) {
			if (persediaan_recalculate_spop_cocok(
				isset($candidate->spop) ? $candidate->spop : '',
				$spop
			)) {
				return $candidate;
			}
		}
	}

	return null;
}

/**
 * Cari baris persediaan untuk pembelian: prioritas SPOP+uraian, lalu nama+satuan+hpp+spop.
 */
function persediaan_recalculate_find_persediaan_for_pembelian_match($row_pembelian, $map)
{
	$by_spop_uraian = persediaan_recalculate_find_persediaan_for_pembelian_spop_uraian($row_pembelian, $map);
	if ($by_spop_uraian) {
		return $by_spop_uraian;
	}

	return persediaan_recalculate_find_persediaan_for_pembelian($row_pembelian, $map);
}

/**
 * Cari persediaan beli=0 + spop valid untuk pembelian tanpa spop (hindari insert duplikat).
 */
function persediaan_recalculate_find_persediaan_valid_spop_tanpa_spop_pembelian($row_pembelian, $map)
{
	if (empty($map['by_id']) || empty($row_pembelian)) {
		return null;
	}

	$CI = function_exists('get_instance') ? get_instance() : null;
	if ($CI) {
		$CI->load->helper('persediaan_display');
	}

	$spop_pem = isset($row_pembelian->spop) ? $row_pembelian->spop : '';
	if (!persediaan_recalculate_spop_kosong_atau_nol($spop_pem)) {
		return null;
	}

	$nama = isset($row_pembelian->uraian) ? $row_pembelian->uraian : '';
	$satuan = isset($row_pembelian->satuan) ? $row_pembelian->satuan : '';
	$harga = isset($row_pembelian->harga_satuan) ? $row_pembelian->harga_satuan : '';

	foreach ($map['by_id'] as $row) {
		$beli = (int) floor(persediaan_parse_angka(isset($row->beli) ? $row->beli : 0));
		if ($beli !== 0) {
			continue;
		}
		if (!persediaan_recalculate_spop_valid(isset($row->spop) ? $row->spop : '')) {
			continue;
		}
		if (!persediaan_recalculate_satuan_cocok_pembelian(
			isset($row->satuan) ? $row->satuan : '',
			$satuan
		)) {
			continue;
		}
		if (!persediaan_recalculate_harga_cocok(isset($row->hpp) ? $row->hpp : 0, $harga)) {
			continue;
		}

		$nama_row = persediaan_recalculate_normalize_nama(
			persediaan_recalculate_sanitize_nama_persediaan(isset($row->namabarang) ? $row->namabarang : '')
		);
		$nama_pem = persediaan_recalculate_normalize_nama(
			persediaan_recalculate_sanitize_nama_persediaan($nama)
		);
		if ($nama_row === '' || $nama_pem === '' || $nama_row !== $nama_pem) {
			continue;
		}

		return $row;
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

	$reset_info = persediaan_recalculate_prepare_penjualan_bulan($CI, $ctx);
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
		$reset_info = persediaan_recalculate_prepare_penjualan_bulan($CI, $ctx);
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
	$total_produksi_bahan = 0;
	if ($CI->db->table_exists('sys_unit_produk_bahan')) {
		$row_prod = $CI->db->query(
			"SELECT COUNT(*) AS jml FROM `sys_unit_produk_bahan`
			WHERE `tgl_transaksi` IS NOT NULL AND `tgl_transaksi` <> '0000-00-00'
			AND DATE(`tgl_transaksi`) >= ? AND DATE(`tgl_transaksi`) <= ?",
			array($tgl_awal, $tgl_akhir)
		)->row();
		$total_produksi_bahan = $row_prod ? (int) $row_prod->jml : 0;
	}

	$total_unit_produk = 0;
	if ($CI->db->table_exists('sys_unit_produk')) {
		$row_unit = $CI->db->query(
			"SELECT COUNT(*) AS jml FROM `sys_unit_produk`
			WHERE `tgl_transaksi` IS NOT NULL AND `tgl_transaksi` <> '0000-00-00'
			AND DATE(`tgl_transaksi`) >= ? AND DATE(`tgl_transaksi`) <= ?",
			array($tgl_awal, $tgl_akhir)
		)->row();
		$total_unit_produk = $row_unit ? (int) $row_unit->jml : 0;
	}

	$total_pecah_satuan = 0;
	if ($CI->db->table_exists('tbl_pembelian_pecah_satuan')) {
		$row_pecah = $CI->db->query(
			"SELECT COUNT(*) AS jml FROM `tbl_pembelian_pecah_satuan`
			WHERE DATE(COALESCE(NULLIF(TRIM(`proses_input`), ''), `tgl_po`)) >= ?
			AND DATE(COALESCE(NULLIF(TRIM(`proses_input`), ''), `tgl_po`)) <= ?",
			array($tgl_awal, $tgl_akhir)
		)->row();
		$total_pecah_satuan = $row_pecah ? (int) $row_pecah->jml : 0;
	}

	$has_pembelian = ($total_pembelian_all > 0);
	$has_penjualan = ((int) $ctx['total_penjualan'] > 0);
	$has_produksi = ($total_produksi_bahan > 0);
	$has_unit_produk = ($total_unit_produk > 0);
	$has_pecah_satuan = ($total_pecah_satuan > 0);
	$can_proceed = ($has_pembelian || $has_penjualan || $has_produksi || $has_unit_produk || $has_pecah_satuan);

	$ctx['total_pembelian'] = $total_pembelian;
	$ctx['total_pembelian_jasa'] = $total_pembelian_jasa;
	$ctx['total_pembelian_all'] = $total_pembelian_all;
	$ctx['total_produksi_bahan'] = $total_produksi_bahan;
	$ctx['total_unit_produk'] = $total_unit_produk;
	$ctx['total_pecah_satuan'] = $total_pecah_satuan;
	$ctx['has_pembelian'] = $has_pembelian;
	$ctx['has_penjualan'] = $has_penjualan;
	$ctx['has_produksi'] = $has_produksi;
	$ctx['has_unit_produk'] = $has_unit_produk;
	$ctx['has_pecah_satuan'] = $has_pecah_satuan;
	$ctx['can_proceed'] = $can_proceed;

	if (!$can_proceed) {
		$ctx['message_empty'] = 'Tidak ada data di tbl_pembelian, tbl_pembelian_jasa, tbl_penjualan, sys_unit_produk, sys_unit_produk_bahan, maupun tbl_pembelian_pecah_satuan untuk bulan '
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
			. '— cocokkan uraian+satuan+harga_satuan+spop+tgl_po = namabarang+satuan+hpp+spop+tanggal_beli.';
	}
	if ($ctx['has_penjualan']) {
		$penjelasan[] = 'Update kolom <strong>penjualan</strong> + kolom <strong>unit</strong> dari tbl_penjualan ('
			. $ctx['total_penjualan'] . ' record, filter tgl_jual bulan ini) — cocokkan nama_barang+satuan+harga_satuan = namabarang+satuan+hpp, jumlah per kolom unit (field unit). '
			. 'total_10 = sa + beli - penjualan.';
	}
	if (!empty($ctx['has_produksi'])) {
		$penjelasan[] = 'Update kolom <strong>bahan_produksi</strong> dari sys_unit_produk_bahan ('
			. (int) $ctx['total_produksi_bahan'] . ' record, filter tgl_transaksi bulan ini) — cocokkan nama_barang_bahan+satuan_bahan+harga_satuan_bahan = namabarang+satuan+hpp. '
			. 'bahan_produksi += jumlah_bahan, total_10 -= jumlah_bahan, sisa stock = total_10.';
	}
	if (!empty($ctx['has_pecah_satuan'])) {
		$penjelasan[] = 'Update kolom <strong>pecah_satuan</strong> dari tbl_pembelian_pecah_satuan ('
			. (int) $ctx['total_pecah_satuan'] . ' record) — sumber: uraian+satuan+harga_satuan, pecah_satuan += jumlah, total_10 -= jumlah; '
			. 'target: nama_barang_baru+satuan_barang_baru+harga_satuan_barang_baru, sa += jumlah_barang_baru, total_10 += jumlah_barang_baru.';
	}
	$coverage = persediaan_recalculate_analisa_import_coverage($CI, $ctx);

	if ((int) $ctx['total_persediaan'] === 0) {
		$penjelasan[] = 'Belum ada data persediaan bulan ini (tanggal_beli ' . $ctx['tanggal_beli'] . ') — recalculate akan <strong>menambah</strong> baris dari pembelian/penjualan yang belum ada.';
	}
	$penjelasan[] = 'Sebelum recalculate: cek semua <strong>tbl_pembelian</strong> (uraian+satuan+harga_satuan+spop, bulan tgl_po) dan <strong>tbl_penjualan</strong> (nama_barang+satuan+harga_satuan, bulan tgl_jual) sudah ada di persediaan (namabarang+satuan+hpp+spop, tanggal_beli bulan sama). Yang belum ada akan <strong>ditambah sekali</strong> tanpa duplikasi.';

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
			$spop_key = persediaan_recalculate_spop_key_for_sync(isset($r->spop) ? $r->spop : '');
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
	$spop_key = persediaan_recalculate_spop_key_for_sync(isset($row->spop) ? $row->spop : '');
	if ($satuan_key === '') {
		return 0;
	}

	$pembelian_key = persediaan_recalculate_sync_pembelian_persediaan_key(
		$row->namabarang,
		$satuan,
		$row->hpp,
		isset($row->spop) ? $row->spop : ''
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
		$beli = persediaan_recalculate_hitung_beli_row_by_kategori(
			$CI,
			$row,
			$ctx['tgl_awal'],
			$ctx['tgl_akhir']
		);
		$upd = persediaan_recalculate_update_beli_row($CI, $row, $beli);
		$updated++;
		if ($beli > 0) {
			$with_beli++;
		}
		if (count($items) < 15) {
			$spop_row = isset($row->spop) ? $row->spop : '';
			$items[] = array(
				'status' => 'OK',
				'fase' => 'beli',
				'id_persediaan' => (int) $row->id,
				'namabarang' => $row->namabarang,
				'satuan' => $row->satuan,
				'hpp' => $row->hpp,
				'spop' => $spop_row,
				'beli' => $upd['beli'],
				'total_10' => $upd['total_10'],
				'keterangan' => 'beli=' . $upd['beli'] . ' dari pembelian (uraian+satuan+harga_satuan+spop = namabarang+satuan+hpp+spop)',
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
 * Fase produksi bahan: sys_unit_produk_bahan → bahan_produksi & total_10 persediaan.
 */
function persediaan_recalculate_produksi_phase_once($CI, $ctx)
{
	if (!$CI->db->table_exists('sys_unit_produk_bahan') || !$CI->db->field_exists('bahan_produksi', 'persediaan')) {
		return array(
			'ok' => true,
			'batch_ok' => 0,
			'matched' => 0,
			'skipped' => 0,
			'with_bahan' => 0,
			'flushed' => 0,
			'pesan' => '',
		);
	}

	$info = persediaan_generate_recalculate_reapply_produksi_bulan($CI, $ctx);
	$matched = (int) (isset($info['matched']) ? $info['matched'] : 0);
	$flushed = (int) (isset($info['flushed']) ? $info['flushed'] : 0);
	$with_bahan = (int) (isset($info['with_bahan']) ? $info['with_bahan'] : 0);

	return array(
		'ok' => true,
		'batch_ok' => $flushed,
		'matched' => $matched,
		'skipped' => (int) (isset($info['skipped']) ? $info['skipped'] : 0),
		'with_bahan' => $with_bahan,
		'flushed' => $flushed,
		'pesan' => ' Produksi bahan: ' . $matched . ' baris sys_unit_produk_bahan cocok, '
			. $with_bahan . ' record persediaan bahan_produksi>0, flush ' . $flushed . '.',
		'produksi_info' => $info,
	);
}

/**
 * Recalculate gabungan: fase beli (pembelian) lalu fase penjualan lalu fase produksi bahan.
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
		} elseif (!empty($ctx['has_produksi'])) {
			$phase = 'produksi';
		}

		$state = array(
			'phase' => $phase,
			'beli_selesai' => false,
			'stats' => array(
				'beli_updated' => 0,
				'penjualan_ok' => 0,
				'penjualan_skip' => 0,
				'produksi_ok' => 0,
			),
			'reset_beli' => null,
			'beli_info' => null,
		);
	}

	if ($state['phase'] === 'beli' && empty($state['beli_selesai'])) {
		$cleanup_spop = persediaan_recalculate_cleanup_duplikat_spop_kosong_beli_nol($CI, $ctx['tanggal_beli']);
		$import_pembelian = array();
		$sync_pembelian = array();
		if ($ctx['has_pembelian']) {
			$import_pembelian['tbl_pembelian'] = persediaan_recalculate_import_pembelian_tidak_sync($CI, $ctx, 'tbl_pembelian');
			$import_pembelian['tbl_pembelian_jasa'] = persediaan_recalculate_import_pembelian_tidak_sync($CI, $ctx, 'tbl_pembelian_jasa');
			$cleanup_spop_after = persediaan_recalculate_cleanup_duplikat_spop_kosong_beli_nol($CI, $ctx['tanggal_beli']);
			$cleanup_spop['deleted'] = (int) (isset($cleanup_spop['deleted']) ? $cleanup_spop['deleted'] : 0)
				+ (int) (isset($cleanup_spop_after['deleted']) ? $cleanup_spop_after['deleted'] : 0);
			$cleanup_spop['groups'] = (int) (isset($cleanup_spop['groups']) ? $cleanup_spop['groups'] : 0)
				+ (int) (isset($cleanup_spop_after['groups']) ? $cleanup_spop_after['groups'] : 0);
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
		$cleanup_pesan = persediaan_recalculate_cleanup_spop_kosong_pesan($cleanup_spop);
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
			'pesan' => (isset($res['pesan']) ? $res['pesan'] : '') . $import_pesan . $sync_pesan . $cleanup_pesan,
			'cleanup_spop_kosong' => $cleanup_spop,
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
			if (!empty($ctx['has_produksi'])) {
				$resProd = persediaan_recalculate_produksi_phase_once($CI, $ctx);
				$state['stats']['produksi_ok'] = (int) $resProd['batch_ok'];
				$resPenj['summary']['produksi_ok'] = (int) $resProd['batch_ok'];
				$resPenj['summary']['total_produksi_bahan'] = (int) $ctx['total_produksi_bahan'];
				$resPenj['produksi_info'] = isset($resProd['produksi_info']) ? $resProd['produksi_info'] : null;
				$resPenj['message_phase'] = trim($resPenj['message_phase'] . (isset($resProd['pesan']) ? $resProd['pesan'] : ''));
			}
			if (!empty($ctx['has_pecah_satuan'])) {
				$resPecah = persediaan_recalculate_pecah_satuan_phase_once($CI, $ctx);
				$state['stats']['pecah_ok'] = (int) $resPecah['batch_ok'];
				$resPenj['summary']['pecah_ok'] = (int) $resPecah['batch_ok'];
				$resPenj['summary']['total_pecah_satuan'] = (int) $ctx['total_pecah_satuan'];
				$resPenj['pecah_info'] = isset($resPecah['pecah_info']) ? $resPecah['pecah_info'] : null;
				$resPenj['message_phase'] = trim($resPenj['message_phase'] . (isset($resPecah['pesan']) ? $resPecah['pesan'] : ''));
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
		if (!empty($ctx['has_produksi'])) {
			$resProd = persediaan_recalculate_produksi_phase_once($CI, $ctx);
			$state['stats']['produksi_ok'] = (int) $resProd['batch_ok'];
			$res['summary']['produksi_ok'] = (int) $resProd['batch_ok'];
			$res['summary']['total_produksi_bahan'] = (int) $ctx['total_produksi_bahan'];
			$res['produksi_info'] = isset($resProd['produksi_info']) ? $resProd['produksi_info'] : null;
			$res['pesan'] = trim((isset($res['pesan']) ? $res['pesan'] : '') . (isset($resProd['pesan']) ? $resProd['pesan'] : ''));
		}
		if (!empty($ctx['has_pecah_satuan'])) {
			$resPecah = persediaan_recalculate_pecah_satuan_phase_once($CI, $ctx);
			$state['stats']['pecah_ok'] = (int) $resPecah['batch_ok'];
			$res['summary']['pecah_ok'] = (int) $resPecah['batch_ok'];
			$res['summary']['total_pecah_satuan'] = (int) $ctx['total_pecah_satuan'];
			$res['pecah_info'] = isset($resPecah['pecah_info']) ? $resPecah['pecah_info'] : null;
			$res['pesan'] = trim((isset($res['pesan']) ? $res['pesan'] : '') . (isset($resPecah['pesan']) ? $resPecah['pesan'] : ''));
		}
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
		if (!empty($ctx['has_produksi'])) {
			$resProd = persediaan_recalculate_produksi_phase_once($CI, $ctx);
			$state['stats']['produksi_ok'] = (int) $resProd['batch_ok'];
			$res['summary']['produksi_ok'] = (int) $resProd['batch_ok'];
			$res['summary']['total_produksi_bahan'] = (int) $ctx['total_produksi_bahan'];
			$res['produksi_info'] = isset($resProd['produksi_info']) ? $resProd['produksi_info'] : null;
			$res['pesan'] = trim((isset($res['pesan']) ? $res['pesan'] : '') . (isset($resProd['pesan']) ? $resProd['pesan'] : ''));
		}
		if (!empty($ctx['has_pecah_satuan'])) {
			$resPecah = persediaan_recalculate_pecah_satuan_phase_once($CI, $ctx);
			$state['stats']['pecah_ok'] = (int) $resPecah['batch_ok'];
			$res['summary']['pecah_ok'] = (int) $resPecah['batch_ok'];
			$res['summary']['total_pecah_satuan'] = (int) $ctx['total_pecah_satuan'];
			$res['pecah_info'] = isset($resPecah['pecah_info']) ? $resPecah['pecah_info'] : null;
			$res['pesan'] = trim((isset($res['pesan']) ? $res['pesan'] : '') . (isset($resPecah['pesan']) ? $resPecah['pesan'] : ''));
		}

		$CI->session->unset_userdata($state_key);
		$CI->session->unset_userdata('recalc_penj_reset_' . $bulan);
		$CI->session->unset_userdata('recalc_penj_stats_' . $bulan);

		return $res;
	}

	if ($state['phase'] === 'produksi') {
		$res = persediaan_recalculate_produksi_phase_once($CI, $ctx);
		if (!$res['ok']) {
			return $res;
		}

		$state['stats']['produksi_ok'] = (int) $res['batch_ok'];
		$res['summary'] = array(
			'bulan' => $ctx['bulan'],
			'bulan_label' => $ctx['bulan_label'],
			'total_persediaan' => (int) $ctx['total_persediaan'],
			'total_produksi_bahan' => (int) $ctx['total_produksi_bahan'],
			'beli_updated' => (int) $state['stats']['beli_updated'],
			'penjualan_ok' => (int) $state['stats']['penjualan_ok'],
			'penjualan_skip' => (int) $state['stats']['penjualan_skip'],
			'produksi_ok' => (int) $state['stats']['produksi_ok'],
			'produksi_info' => isset($res['produksi_info']) ? $res['produksi_info'] : null,
		);
		$res['done'] = true;
		if (!empty($ctx['has_pecah_satuan'])) {
			$resPecah = persediaan_recalculate_pecah_satuan_phase_once($CI, $ctx);
			$state['stats']['pecah_ok'] = (int) $resPecah['batch_ok'];
			$res['summary']['pecah_ok'] = (int) $resPecah['batch_ok'];
			$res['summary']['total_pecah_satuan'] = (int) $ctx['total_pecah_satuan'];
			$res['pecah_info'] = isset($resPecah['pecah_info']) ? $resPecah['pecah_info'] : null;
			$res['pesan'] = trim((isset($res['pesan']) ? $res['pesan'] : '') . (isset($resPecah['pesan']) ? $resPecah['pesan'] : ''));
		}
		$CI->session->unset_userdata($state_key);
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
		xlsWriteCellStyle($rowNum, $col, $label, 14);
	}
}

/**
 * Baris footer TOTAL untuk export Excel generate/recalculate (kolom numerik).
 */
function persediaan_gen_recalc_excel_footer_row($headers, $sum_field_by_header, $totals)
{
	$headers = is_array($headers) ? $headers : array();
	$row = array_fill(0, count($headers), '');
	if (count($row) > 0) {
		$row[0] = 'TOTAL';
	}

	$header_index = array();
	foreach ($headers as $idx => $label) {
		$header_index[strtoupper(trim((string) $label))] = (int) $idx;
	}

	foreach ($sum_field_by_header as $header_label => $field) {
		$key = strtoupper(trim((string) $header_label));
		if (!isset($header_index[$key]) || !isset($totals[$field])) {
			continue;
		}
		$row[$header_index[$key]] = persediaan_format_angka_tampil($totals[$field]);
	}

	return $row;
}

function persediaan_gen_recalc_sum_items_fields($items, $fields)
{
	$fields = is_array($fields) ? $fields : array();
	$totals = array();
	foreach ($fields as $field) {
		$totals[$field] = 0;
	}

	foreach ($items as $item) {
		if (!is_array($item)) {
			continue;
		}
		if (isset($item['row_type']) && $item['row_type'] === 'subtotal') {
			continue;
		}
		foreach ($fields as $field) {
			$val = null;
			if (isset($item[$field])) {
				$val = $item[$field];
			} elseif ($field === 'total_10_sumber' && isset($item['total_10'])) {
				$val = $item['total_10'];
			}
			if ($val === null) {
				continue;
			}
			$totals[$field] += persediaan_parse_angka($val);
		}
	}

	return $totals;
}

function persediaan_gen_recalc_jenis_footer_sum_map()
{
	return array(
		'persediaan_all' => array('SA' => 'sa', 'Beli' => 'beli', 'Total_10' => 'total_10'),
		'generate_update' => array('SA' => 'sa', 'Beli' => 'beli', 'Total_10' => 'total_10'),
		'generate_insert' => array('SA' => 'sa', 'Total_10' => 'total_10'),
		'generate_verifikasi' => array(
			'SA Sumber' => 'sa_sumber',
			'Total_10 Field Sumber' => 'total_10_field_sumber',
			'SA Target' => 'sa_target',
			'Beli Target' => 'beli_target',
			'Penjualan Target' => 'penjualan_target',
			'Total_10 Target' => 'total_10_target',
		),
		'generate_masalah' => array('Total_10 Sumber' => 'total_10_sumber'),
		'pembelian' => array('Jumlah' => 'jumlah_pembelian', 'Beli Baru' => 'beli_baru'),
		'pembelian_update' => array(
			'Jumlah' => 'jumlah_pembelian',
			'Beli Lama' => 'beli_lama',
			'Beli Baru' => 'beli_baru',
			'Total_10' => 'total_10',
		),
		'pembelian_baru' => array('Beli' => 'beli_baru'),
		'penjualan' => array(
			'Jumlah' => 'jumlah_penjualan',
			'Penjualan Baru' => 'penjualan_baru',
			'Total_10' => 'total_10',
		),
		'penjualan_update' => array(
			'Jumlah' => 'jumlah_penjualan',
			'Penjualan Lama' => 'penjualan_lama',
			'Penjualan Baru' => 'penjualan_baru',
			'Unit Lama' => 'unit_lama',
			'Unit Baru' => 'unit_baru',
			'Total_10' => 'total_10',
		),
		'produksi' => array(
			'Jumlah Bahan' => 'jumlah_bahan',
			'Bahan Produksi Baru' => 'bahan_produksi_baru',
			'Total_10' => 'total_10',
		),
		'produksi_update' => array(
			'Jumlah Bahan' => 'jumlah_bahan',
			'Bahan Lama' => 'bahan_produksi_lama',
			'Bahan Baru' => 'bahan_produksi_baru',
			'Total_10' => 'total_10',
			'Sisa Stock' => 'sisa_stock',
		),
		'unit_produk' => array(
			'Jumlah Produksi' => 'jumlah_produksi',
			'SA Baru' => 'sa_baru',
			'Total_10' => 'total_10',
		),
		'unit_produk_update' => array(
			'Jumlah Produksi' => 'jumlah_produksi',
			'SA Lama' => 'sa_lama',
			'SA Baru' => 'sa_baru',
			'Total_10' => 'total_10',
		),
		'pecah_satuan' => array(
			'Jumlah Pecah' => 'jumlah_pecah',
			'Pecah Satuan Baru' => 'pecah_satuan_baru',
			'Total_10 Sumber' => 'total_10_sumber',
			'Jumlah Baru' => 'jumlah_barang_baru',
			'SA Target' => 'sa_target',
			'Total_10 Target' => 'total_10_target',
		),
		'pecah_satuan_update' => array(
			'Jumlah Pecah' => 'jumlah_pecah',
			'Jumlah Baru' => 'jumlah_barang_baru',
			'Pecah Satuan Baru' => 'pecah_satuan_baru',
			'Total_10 Sumber' => 'total_10_sumber',
			'SA Target' => 'sa_target',
			'Total_10 Target' => 'total_10_target',
		),
		'gagal_generate_recalculate' => array('Jumlah' => 'jumlah'),
		'gagal_insert_persediaan' => array('Jumlah' => 'jumlah'),
		'pembelian_spop_multi_status' => array('Jumlah' => 'jumlah'),
		'pembelian_spop_single_status' => array('Jumlah' => 'jumlah'),
		'penjualan_berhasil' => array(
			'Jumlah' => 'jumlah',
			'Penjualan Lama' => 'penjualan_lama',
			'Penjualan Baru' => 'penjualan_baru',
			'Total_10' => 'total_10',
		),
		'penjualan_gagal' => array('Jumlah' => 'jumlah'),
		'penjualan_uuid_orphan' => array('Jumlah' => 'jumlah'),
	);
}

function persediaan_gen_recalc_jenis_data_key_map()
{
	return array(
		'pembelian_spop_multi_status' => 'pembelian_spop_multi',
		'pembelian_spop_single_status' => 'pembelian_spop_single',
	);
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
 * Cari record persediaan bulan target by nama+sa+hpp+spop.
 */
function persediaan_generate_recalculate_sync_key($nama, $sa, $hpp, $spop)
{
	$nama = persediaan_recalculate_normalize_nama(
		persediaan_recalculate_sanitize_nama_persediaan($nama)
	);
	if ($nama === '') {
		return '';
	}

	$CI = function_exists('get_instance') ? get_instance() : null;
	if ($CI) {
		$CI->load->helper('persediaan_display');
	}

	$sa_key = (string) (int) floor(persediaan_parse_angka($sa));
	$hpp_key = (string) (int) floor(persediaan_parse_angka($hpp));
	$spop_key = persediaan_recalculate_normalize_spop($spop);
	if ($spop_key === '') {
		$spop_key = '0';
	}

	return $nama . '|sa:' . $sa_key . '|hpp:' . $hpp_key . '|spop:' . $spop_key;
}

/**
 * Cocokkan baris persediaan dengan namabarang + sa + hpp + spop.
 */
function persediaan_generate_recalculate_row_cocok($row, $nama, $sa_angka, $hpp, $spop)
{
	if (empty($row)) {
		return false;
	}

	$CI = function_exists('get_instance') ? get_instance() : null;
	if ($CI) {
		$CI->load->helper('persediaan_display');
	}

	$nama_row = persediaan_recalculate_normalize_nama(
		persediaan_recalculate_sanitize_nama_persediaan(isset($row->namabarang) ? $row->namabarang : '')
	);
	$nama_cmp = persediaan_recalculate_normalize_nama(
		persediaan_recalculate_sanitize_nama_persediaan($nama)
	);
	if ($nama_row === '' || $nama_cmp === '' || $nama_row !== $nama_cmp) {
		return false;
	}

	$sa_row = (int) floor(persediaan_parse_angka(isset($row->sa) ? $row->sa : 0));
	$sa_cmp = (int) floor(persediaan_parse_angka($sa_angka));
	if ($sa_row !== $sa_cmp) {
		return false;
	}

	if (!persediaan_recalculate_harga_cocok(isset($row->hpp) ? $row->hpp : 0, $hpp)) {
		return false;
	}

	return persediaan_recalculate_spop_cocok(isset($row->spop) ? $row->spop : '', $spop);
}

function persediaan_generate_recalculate_find_target_in_map($row, $map, $sa_for_match = null)
{
	if (empty($map) || empty($row)) {
		return null;
	}

	$spop = isset($row->spop) ? $row->spop : '';
	if (persediaan_recalculate_spop_valid($spop)) {
		$by_spop = persediaan_generate_recalculate_find_target_by_spop_row($row, $map);
		if ($by_spop) {
			return $by_spop;
		}
	}

	$nama = isset($row->namabarang) ? $row->namabarang : '';
	$hpp = isset($row->hpp) ? $row->hpp : '';
	$spop = isset($row->spop) ? $row->spop : '';

	if ($sa_for_match === null) {
		$sa_for_match = persediaan_generate_recalculate_hitung_sa_dari_sumber($row);
	}

	$gen_key = persediaan_generate_recalculate_sync_key($nama, $sa_for_match, $hpp, $spop);
	if ($gen_key !== '' && !empty($map['by_generate_key'][$gen_key])) {
		return $map['by_generate_key'][$gen_key][0];
	}

	if (!empty($map['by_id']) && is_array($map['by_id'])) {
		foreach ($map['by_id'] as $candidate) {
			if (persediaan_generate_recalculate_row_cocok($candidate, $nama, $sa_for_match, $hpp, $spop)) {
				return $candidate;
			}
		}
	}

	$fake = (object) array(
		'uraian' => $nama,
		'satuan' => isset($row->satuan) ? $row->satuan : '',
		'harga_satuan' => $hpp,
		'spop' => $spop,
	);

	return persediaan_recalculate_find_persediaan_for_pembelian($fake, $map);
}

/**
 * Cari record persediaan bulan target by nama+satuan+hpp+spop.
 */
function persediaan_generate_recalculate_find_target($CI, $tanggal_beli_target, $row)
{
	$CI->load->helper('persediaan_display');

	$nama = persediaan_recalculate_sanitize_nama_persediaan(isset($row->namabarang) ? $row->namabarang : '');
	if ($nama === '') {
		return null;
	}

	$map = persediaan_recalculate_build_map_persediaan_bulan($CI, $tanggal_beli_target);

	return persediaan_generate_recalculate_find_target_in_map($row, $map);
}

/**
 * Hapus duplikat bulan target — delegasi ke cleanup spop sama (1 spop = 1 baris per bulan).
 */
function persediaan_generate_recalculate_hapus_duplikat_bulan_target($CI, $tanggal_beli_target)
{
	$res = persediaan_recalculate_cleanup_duplikat_spop_sama_bulan($CI, $tanggal_beli_target);

	return (int) (isset($res['deleted']) ? $res['deleted'] : 0);
}

/**
 * Nilai kolom total_10 di record sumber (bukan kalkulasi rumus).
 */
function persediaan_generate_recalculate_sumber_total10_field_val($row)
{
	if (empty($row)) {
		return 0;
	}

	$CI = function_exists('get_instance') ? get_instance() : null;
	if ($CI) {
		$CI->load->helper('persediaan_display');
	}

	return persediaan_parse_angka(isset($row->total_10) ? $row->total_10 : 0);
}

/**
 * Record bulan sumber layak di-copy jika kolom total_10 > 0.
 */
function persediaan_generate_recalculate_sumber_layak_generate($row)
{
	return persediaan_generate_recalculate_sumber_total10_field_val($row) > 0;
}

/**
 * Parse angka field persediaan di SQL — selaras dengan persediaan_parse_angka() di PHP.
 */
function persediaan_generate_recalculate_sql_cast_decimal($field)
{
	$field = preg_replace('/[^a-z0-9_]/i', '', (string) $field);
	$expr = "TRIM(COALESCE(`{$field}`, ''))";
	$strip_chars = array(' ', ',', '(', ')', "\t");
	foreach ($strip_chars as $ch) {
		$escaped = str_replace("'", "''", $ch);
		$expr = "REPLACE({$expr}, '{$escaped}', '')";
	}
	$expr = "NULLIF(NULLIF({$expr}, ''), '-')";

	return "CAST(COALESCE({$expr}, '0') AS DECIMAL(20,4))";
}

/**
 * Kosongkan seluruh persediaan bulan target sebelum generate ulang.
 */
function persediaan_generate_recalculate_kosongkan_bulan_target($CI, $tanggal_beli_target)
{
	$tanggal_beli_target = trim((string) $tanggal_beli_target);
	if ($tanggal_beli_target === '') {
		return array(
			'deleted' => 0,
			'remaining' => 0,
			'verified_empty' => false,
			'tanggal_beli_target' => $tanggal_beli_target,
		);
	}

	$row_cnt = $CI->db->query(
		"SELECT COUNT(*) AS jml FROM `persediaan` WHERE `tanggal_beli` = ?",
		array($tanggal_beli_target)
	)->row();

	$deleted = $row_cnt ? (int) $row_cnt->jml : 0;
	if ($deleted > 0) {
		$CI->db->where('tanggal_beli', $tanggal_beli_target);
		$CI->db->delete('persediaan');
	}

	$row_after = $CI->db->query(
		"SELECT COUNT(*) AS jml FROM `persediaan` WHERE `tanggal_beli` = ?",
		array($tanggal_beli_target)
	)->row();
	$remaining = $row_after ? (int) $row_after->jml : 0;

	if ($remaining > 0) {
		$CI->db->query(
			"DELETE FROM `persediaan` WHERE `tanggal_beli` = ?",
			array($tanggal_beli_target)
		);
		$row_after2 = $CI->db->query(
			"SELECT COUNT(*) AS jml FROM `persediaan` WHERE `tanggal_beli` = ?",
			array($tanggal_beli_target)
		)->row();
		$remaining = $row_after2 ? (int) $row_after2->jml : 0;
	}

	return array(
		'deleted' => $deleted,
		'remaining' => $remaining,
		'verified_empty' => ($remaining === 0),
		'tanggal_beli_target' => $tanggal_beli_target,
	);
}

/**
 * SQL tambahan: baris sumber layak generate (kolom total_10 > 0).
 */
function persediaan_generate_recalculate_sql_filter_total10_positif()
{
	$total = persediaan_generate_recalculate_sql_cast_decimal('total_10');

	return " AND {$total} > 0";
}

/**
 * SQL tambahan: baris target sa=0, beli=0, total_10=0 (dihapus setelah proses).
 */
function persediaan_generate_recalculate_sql_filter_semua_nol()
{
	$sa = persediaan_generate_recalculate_sql_cast_decimal('sa');
	$beli = persediaan_generate_recalculate_sql_cast_decimal('beli');
	$total = persediaan_generate_recalculate_sql_cast_decimal('total_10');

	return " AND {$sa} <= 0 AND {$beli} <= 0 AND {$total} <= 0";
}

/**
 * Hapus record bulan target yang sa=0, beli=0, dan total_10=0.
 */
function persediaan_generate_recalculate_hapus_baris_nol_bulan_target($CI, $tanggal_beli_target)
{
	$CI->db->query(
		"DELETE FROM `persediaan` WHERE `tanggal_beli` = ?"
		. persediaan_generate_recalculate_sql_filter_semua_nol(),
		array($tanggal_beli_target)
	);

	return (int) $CI->db->affected_rows();
}

/**
 * Tentukan kategori record sumber untuk generate (pertahankan jasa di tab Jasa).
 */
function persediaan_generate_recalculate_resolve_kategori_sumber($CI, $row)
{
	if (empty($row)) {
		return '';
	}

	$kat = isset($row->kategori) ? trim((string) $row->kategori) : '';
	if ($kat !== '') {
		return $kat;
	}

	if (!$CI || !$CI->db->field_exists('kategori', 'persediaan')) {
		return '';
	}

	$uuid = isset($row->uuid_barang) ? trim((string) $row->uuid_barang) : '';
	if ($uuid !== '' && $CI->db->table_exists('tbl_jasa')) {
		$cek_jasa = $CI->db->query(
			"SELECT 1 AS ada FROM `tbl_jasa` WHERE TRIM(COALESCE(`uuid_barang`, '')) = ? LIMIT 1",
			array($uuid)
		)->row();
		if ($cek_jasa) {
			return 'jasa';
		}
	}

	return '';
}

/**
 * Jumlah qty kolom unit (sekret, dinas_umum, ...) untuk saldo jasa.
 */
function persediaan_generate_recalculate_hitung_jumlah_unit_row($row, $CI = null)
{
	if ($CI === null && function_exists('get_instance')) {
		$CI = get_instance();
	}
	if ($CI) {
		$CI->load->helper('persediaan_display');
	}

	$sum = 0;
	foreach (persediaan_list_unit_columns($CI) as $field) {
		$sum += max(0, (int) floor(persediaan_parse_angka(persediaan_row_get($row, $field))));
	}

	return max(0, (int) $sum);
}

/**
 * Saldo awal bulan target dari record sumber (per baris/spop yang sama):
 * sisa stok akhir bulan sebelumnya = total_10 net = (sa + beli) - penjualan - pecah_satuan - bahan_produksi.
 * Bukan nilai total_10 kotor di DB yang belum dikurangi penjualan.
 */
function persediaan_generate_recalculate_hitung_sa_dari_sumber($row)
{
	if (empty($row)) {
		return 0;
	}

	$CI = function_exists('get_instance') ? get_instance() : null;
	if ($CI) {
		$CI->load->helper('persediaan_display');
	}

	$obj = is_array($row) ? (object) $row : $row;
	$saldo_akhir = persediaan_hitung_total_10_kalkulasi($obj);
	if ($saldo_akhir > 0) {
		return $saldo_akhir;
	}

	$saldo_net = persediaan_hitung_total_10_net($obj);
	if ($saldo_net > 0) {
		return $saldo_net;
	}

	$kategori = persediaan_generate_recalculate_resolve_kategori_sumber($CI, $obj);
	if (penjualan_is_kategori_jasa($kategori)) {
		$sum_unit = persediaan_generate_recalculate_hitung_jumlah_unit_row($obj, $CI);
		if ($sum_unit > 0) {
			$parts = persediaan_gen_recalc_total_10_formula_parts($obj);
			$saldo_unit = $sum_unit - (int) $parts['penjualan'] - (int) $parts['pecah_satuan'] - (int) $parts['bahan_produksi'];
			if ($saldo_unit > 0) {
				return max(0, (int) $saldo_unit);
			}
		}
	}

	return 0;
}

/**
 * Mode generate sederhana: salin 1 baris sumber → 1 baris target (tanpa merge/duplikat).
 * SA & total_10 target = nilai kolom total_10 sumber; beli/penjualan/pecah/produksi = 0.
 */
function persediaan_generate_recalculate_insert_copy_simple($CI, $ctx, $row_sumber, &$next_id, &$map)
{
	$CI->load->helper('persediaan_display');

	$tanggal_beli_target = $ctx['tanggal_beli_target'];
	$id_sumber = isset($row_sumber->id) ? (int) $row_sumber->id : 0;
	$total_10_src = (int) floor(persediaan_generate_recalculate_sumber_total10_field_val($row_sumber));
	$nama = persediaan_recalculate_sanitize_nama_persediaan(isset($row_sumber->namabarang) ? $row_sumber->namabarang : '');
	$satuan = persediaan_recalculate_sanitize_satuan_persediaan(isset($row_sumber->satuan) ? $row_sumber->satuan : '');
	$hpp_raw = isset($row_sumber->hpp) ? trim((string) $row_sumber->hpp) : '0';
	$spop = isset($row_sumber->spop) ? trim((string) $row_sumber->spop) : '0';
	$hpp_angka = persediaan_parse_angka($hpp_raw);
	$hpp_t = (string) (int) floor($hpp_angka);
	$sa_t = (string) $total_10_src;
	$beli_t = '0';
	$total_t = $sa_t;
	$nilai_t = (string) (int) floor($total_10_src * $hpp_angka);
	$kategori_sumber = persediaan_generate_recalculate_resolve_kategori_sumber($CI, $row_sumber);

	$new_id = (int) $next_id;
	$next_id = $new_id + 1;

	$fields_pers = $CI->db->list_fields('persediaan');
	$src_arr = (array) $row_sumber;
	$src_lookup = array();
	foreach ($src_arr as $sk => $sv) {
		$src_lookup[strtolower((string) $sk)] = $sv;
	}

	$data_insert = array();
	foreach ($fields_pers as $fp) {
		$key = strtolower((string) $fp);
		$data_insert[$fp] = isset($src_lookup[$key]) ? $src_lookup[$key] : '';
	}

	$data_insert['id'] = $new_id;
	$data_insert['tanggal_beli'] = $tanggal_beli_target;
	$data_insert['tanggal'] = $tanggal_beli_target;
	$data_insert['namabarang'] = $nama;
	$data_insert['satuan'] = $satuan;
	$data_insert['hpp'] = $hpp_t;
	if ($CI->db->field_exists('spop', 'persediaan')) {
		$data_insert['spop'] = $spop !== '' ? $spop : '0';
	}
	$data_insert['sa'] = $sa_t;
	$data_insert['beli'] = $beli_t;
	$data_insert['penjualan'] = '0';
	if ($CI->db->field_exists('pecah_satuan', 'persediaan')) {
		$data_insert['pecah_satuan'] = '0';
	}
	if ($CI->db->field_exists('bahan_produksi', 'persediaan')) {
		$data_insert['bahan_produksi'] = '0';
	}
	$data_insert['total_10'] = $total_t;
	if ($CI->db->field_exists('nilai_persediaan', 'persediaan')) {
		$data_insert['nilai_persediaan'] = $nilai_t;
	}
	if ($CI->db->field_exists('tuj', 'persediaan')) {
		$data_insert['tuj'] = $total_t;
	}
	if ($CI->db->field_exists('uuid_persediaan_lama', 'persediaan')) {
		$data_insert['uuid_persediaan_lama'] = 'gen_src:' . $id_sumber;
	}
	if ($CI->db->field_exists('uuid_persediaan', 'persediaan')) {
		unset($data_insert['uuid_persediaan']);
	}
	if ($kategori_sumber !== '' && $CI->db->field_exists('kategori', 'persediaan')) {
		$data_insert['kategori'] = $kategori_sumber;
	}
	persediaan_row_apply_asal_generate_flag($data_insert, true, $CI);

	$CI->db->set('uuid_persediaan', "replace(uuid(),'-','')", FALSE);
	if (!$CI->db->insert('persediaan', $data_insert)) {
		$err = $CI->db->error();
		throw new Exception(isset($err['message']) ? $err['message'] : 'Gagal insert persediaan generate (salin sederhana).');
	}

	$row_baru = $CI->db->where('id', $new_id)->limit(1)->get('persediaan')->row();
	if ($row_baru && is_array($map)) {
		persediaan_recalculate_map_add_row($map, $row_baru);
	}

	return array(
		'fase' => 'generate',
		'aksi' => 'INSERT',
		'id' => $new_id,
		'id_sumber' => $id_sumber,
		'uuid_persediaan' => $row_baru ? $row_baru->uuid_persediaan : '',
		'namabarang' => $nama,
		'satuan' => $satuan,
		'hpp' => $hpp_t,
		'spop' => $spop,
		'sa' => $sa_t,
		'beli' => $beli_t,
		'total_10' => $total_t,
		'nilai_persediaan' => $nilai_t,
		'keterangan' => 'Salin 1:1 dari bulan sumber (ID ' . $id_sumber . ', total_10=' . $total_t . ')',
	);
}

function persediaan_generate_recalculate_upsert_from_sumber($CI, $ctx, $row_sumber, &$next_id, &$map)
{
	$CI->load->helper('persediaan_display');

	if (!persediaan_generate_recalculate_sumber_layak_generate($row_sumber)) {
		$nama_skip = persediaan_recalculate_sanitize_nama_persediaan(isset($row_sumber->namabarang) ? $row_sumber->namabarang : '');
		$total10_field = persediaan_generate_recalculate_sumber_total10_field_val($row_sumber);
		$status_skip = ($total10_field < 0) ? 'NEGATIF' : 'SKIP';
		$ket_skip = ($total10_field < 0)
			? 'Lewati: total_10 minus (' . $total10_field . ') — tidak di-copy ke bulan target'
			: 'Lewati: total_10 <= 0 / kosong — tidak di-copy ke bulan target';
		return array(
			'fase' => 'generate',
			'aksi' => 'SKIP',
			'status' => $status_skip,
			'id' => isset($row_sumber->id) ? (int) $row_sumber->id : 0,
			'namabarang' => $nama_skip,
			'satuan' => isset($row_sumber->satuan) ? $row_sumber->satuan : '',
			'hpp' => isset($row_sumber->hpp) ? $row_sumber->hpp : '',
			'spop' => isset($row_sumber->spop) ? $row_sumber->spop : '',
			'sa' => '',
			'beli' => '',
			'total_10' => isset($row_sumber->total_10) ? $row_sumber->total_10 : '',
			'keterangan' => $ket_skip,
		);
	}

	if (persediaan_generate_recalculate_stop_after_generate()) {
		return persediaan_generate_recalculate_insert_copy_simple($CI, $ctx, $row_sumber, $next_id, $map);
	}

	$tanggal_beli_target = $ctx['tanggal_beli_target'];
	$tanggal_tampilan_target = $ctx['tanggal_tampilan_target'];
	$nama = persediaan_recalculate_sanitize_nama_persediaan(isset($row_sumber->namabarang) ? $row_sumber->namabarang : '');
	$satuan = persediaan_recalculate_sanitize_satuan_persediaan(isset($row_sumber->satuan) ? $row_sumber->satuan : '');
	$hpp_raw = isset($row_sumber->hpp) ? trim((string) $row_sumber->hpp) : '0';
	$uuid_barang = trim((string) $row_sumber->uuid_barang);
	$spop = isset($row_sumber->spop) ? trim((string) $row_sumber->spop) : '0';

	$total_10_sumber = persediaan_generate_recalculate_hitung_sa_dari_sumber($row_sumber);
	$sa_baru = $total_10_sumber;
	$total_10_baru = $sa_baru;
	$hpp_angka = persediaan_parse_angka($hpp_raw);
	$nilai_persediaan_baru = $total_10_baru * $hpp_angka;
	$kategori_sumber = persediaan_generate_recalculate_resolve_kategori_sumber($CI, $row_sumber);

	$sa_t = (string) (int) floor($sa_baru);
	$beli_t = '0';
	$total_t = (string) (int) floor($total_10_baru);
	$nilai_t = (string) (int) floor($nilai_persediaan_baru);
	$hpp_t = (string) (int) floor($hpp_angka);

	$existing = is_array($map)
		? persediaan_generate_recalculate_find_target_in_map($row_sumber, $map, $sa_baru)
		: persediaan_generate_recalculate_find_target($CI, $tanggal_beli_target, $row_sumber);

	if (!$existing && persediaan_recalculate_spop_valid($spop) && is_array($map)) {
		$existing = persediaan_generate_recalculate_find_target_by_spop_row($row_sumber, $map);
	}

	if ($existing) {
		$beli_existing = max(0, (int) floor(persediaan_parse_angka(isset($existing->beli) ? $existing->beli : 0)));
		$beli_t = (string) (int) floor($beli_existing);
		$total_10_baru = $sa_baru + $beli_existing;
		$total_t = (string) (int) floor($total_10_baru);
		$nilai_persediaan_baru = $total_10_baru * $hpp_angka;
		$nilai_t = (string) (int) floor($nilai_persediaan_baru);

		$upd = array(
			'uuid_barang' => $uuid_barang,
			'namabarang' => $nama,
			'satuan' => $satuan,
			'hpp' => $hpp_t,
			'sa' => $sa_t,
			'beli' => $beli_t,
			'penjualan' => '0',
			'total_10' => $total_t,
			'nilai_persediaan' => $nilai_t,
			'tuj' => $total_t,
			'tanggal' => $tanggal_tampilan_target,
		);
		if ($CI->db->field_exists('spop', 'persediaan')) {
			$upd['spop'] = $spop !== '' ? $spop : '0';
		}
		if ($beli_existing > 0) {
			persediaan_row_apply_asal_generate_flag($upd, false, $CI);
		} else {
			persediaan_row_apply_asal_generate_flag($upd, true, $CI);
		}
		if ($kategori_sumber !== '' && $CI->db->field_exists('kategori', 'persediaan')) {
			$upd['kategori'] = $kategori_sumber;
		}

		$CI->db->where('id', (int) $existing->id);
		$CI->db->update('persediaan', $upd);

		$row_baru = $CI->db->where('id', (int) $existing->id)->limit(1)->get('persediaan')->row();
		if ($row_baru && is_array($map)) {
			persediaan_recalculate_map_add_row($map, $row_baru);
		}

		return array(
			'fase' => 'generate',
			'aksi' => 'UPDATE',
			'id' => (int) $existing->id,
			'namabarang' => $nama,
			'satuan' => $satuan,
			'hpp' => $hpp_t,
			'spop' => $spop,
			'sa' => $sa_t,
			'beli' => $beli_t,
			'total_10' => $total_t,
			'keterangan' => 'Update dari bulan sumber (spop sama di bulan target — gabung saldo awal, beli dipertahankan)',
		);
	}

	$new_id = (int) $next_id;
	$next_id = $new_id + 1;

	$data_insert = array(
		'id' => $new_id,
		'uuid_persediaan_lama' => '',
		'uuid_spop' => '',
		'uuid_gudang' => '',
		'nama_gudang' => '',
		'uuid_barang' => $uuid_barang,
		'kode_barang' => '',
		'tanggal_beli' => $tanggal_beli_target,
		'tanggal' => $tanggal_tampilan_target,
		'kode' => '',
		'kategori' => ($kategori_sumber !== '' && $CI->db->field_exists('kategori', 'persediaan')) ? $kategori_sumber : '',
		'namabarang' => $nama,
		'satuan' => $satuan,
		'hpp' => $hpp_t,
		'sa' => $sa_t,
		'spop' => $spop !== '' ? $spop : '0',
		'beli' => $beli_t,
		'tuj' => '0',
		'penjualan' => '0',
		'pecah_satuan' => '0',
		'bahan_produksi' => '0',
		'total_10' => $total_t,
		'nilai_persediaan' => $nilai_t,
	);
	$data_insert = array_merge($data_insert, persediaan_generate_distribusi_nol_fields());
	persediaan_row_apply_asal_generate_flag($data_insert, true, $CI);

	$CI->db->set('uuid_persediaan', "replace(uuid(),'-','')", FALSE);
	if (!$CI->db->insert('persediaan', $data_insert)) {
		$err = $CI->db->error();
		throw new Exception(isset($err['message']) ? $err['message'] : 'Gagal insert persediaan generate.');
	}

	$row_baru = $CI->db->where('id', $new_id)->limit(1)->get('persediaan')->row();
	if ($row_baru && is_array($map)) {
		persediaan_recalculate_map_add_row($map, $row_baru);
	}

	return array(
		'fase' => 'generate',
		'aksi' => 'INSERT',
		'id' => $new_id,
		'uuid_persediaan' => $row_baru ? $row_baru->uuid_persediaan : '',
		'namabarang' => $nama,
		'satuan' => $satuan,
		'hpp' => $hpp_t,
		'spop' => $spop,
		'sa' => $sa_t,
		'beli' => $beli_t,
		'total_10' => $total_t,
		'keterangan' => 'Insert baru dari bulan sumber',
	);
}

function persediaan_generate_recalculate_find_by_nama_hpp_spop($map, $nama, $satuan, $hpp, $spop)
{
	if (empty($map) || empty($map['by_id']) || !is_array($map['by_id'])) {
		return null;
	}

	foreach ($map['by_id'] as $row) {
		if (persediaan_generate_recalculate_row_cocok_nama_hpp_spop($row, $nama, $satuan, $hpp, $spop)) {
			return $row;
		}
	}

	return null;
}

/**
 * Cocokkan namabarang + satuan + hpp + spop (tanpa syarat sa).
 */
function persediaan_generate_recalculate_row_cocok_nama_hpp_spop($row, $nama, $satuan, $hpp, $spop)
{
	if (empty($row)) {
		return false;
	}

	$nama_row = persediaan_recalculate_normalize_nama(
		persediaan_recalculate_sanitize_nama_persediaan(isset($row->namabarang) ? $row->namabarang : '')
	);
	$nama_cmp = persediaan_recalculate_normalize_nama(
		persediaan_recalculate_sanitize_nama_persediaan($nama)
	);
	if ($nama_row === '' || $nama_cmp === '' || $nama_row !== $nama_cmp) {
		return false;
	}

	if (!persediaan_recalculate_satuan_cocok_pembelian(
		isset($row->satuan) ? $row->satuan : '',
		$satuan
	)) {
		return false;
	}

	if (!persediaan_recalculate_harga_cocok(isset($row->hpp) ? $row->hpp : 0, $hpp)) {
		return false;
	}

	return persediaan_recalculate_spop_cocok(isset($row->spop) ? $row->spop : '', $spop);
}

function persediaan_gen_recalc_ensure_total_10_persediaan($CI, $pers_id)
{
	$CI->load->helper('persediaan_display');

	$pers_id = (int) $pers_id;
	if ($pers_id <= 0) {
		return null;
	}

	$row = $CI->db->where('id', $pers_id)->limit(1)->get('persediaan')->row();
	if (!$row) {
		return null;
	}

	$parts = persediaan_gen_recalc_total_10_formula_parts($row);
	$kalk = (int) $parts['total_10_kalkulasi'];
	$aktual = max(0, (int) floor(persediaan_parse_angka(isset($row->total_10) ? $row->total_10 : 0)));
	$check = persediaan_gen_recalc_check_total_10_keterangan($row, $aktual);

	if ($aktual !== $kalk) {
		$hpp = persediaan_parse_angka(isset($row->hpp) ? $row->hpp : 0);
		$nilai = (int) floor($kalk * $hpp);
		$update = array(
			'total_10' => (string) $kalk,
			'nilai_persediaan' => (string) $nilai,
		);
		if ($CI->db->field_exists('tuj', 'persediaan')) {
			$update['tuj'] = (string) $kalk;
		}
		$CI->db->where('id', $pers_id);
		$CI->db->update('persediaan', $update);

		if ($check !== '') {
			$check = 'Diperbaiki → total_10=' . $kalk . '. ' . $check;
		}
	}

	return array(
		'total_10' => (string) $kalk,
		'keterangan_check' => $check,
		'parts' => $parts,
	);
}

/**
 * Refresh total_10 & keterangan_check pada array item hasil batch (by id_persediaan).
 */
function persediaan_gen_recalc_refresh_items_total_10_check($CI, &$items, $id_field = 'id_persediaan', $total_field = 'total_10')
{
	if (!is_array($items)) {
		return;
	}

	foreach ($items as $idx => $item) {
		if (!is_array($item)) {
			continue;
		}
		$pid = (int) (isset($item[$id_field]) ? $item[$id_field] : 0);
		if ($pid <= 0) {
			continue;
		}
		$sync = persediaan_gen_recalc_ensure_total_10_persediaan($CI, $pid);
		if (!$sync) {
			continue;
		}
		$items[$idx][$total_field] = $sync['total_10'];
		$items[$idx]['keterangan_check'] = $sync['keterangan_check'];
	}
}

function persediaan_generate_recalculate_tambah_beli_row($CI, $row, $tambah_jumlah)
{
	$CI->load->helper('persediaan_display');
	$tambah = max(0, (int) floor(persediaan_parse_angka($tambah_jumlah)));
	$sa = max(0, (int) floor(persediaan_parse_angka(isset($row->sa) ? $row->sa : 0)));
	$beli_lama = max(0, (int) floor(persediaan_parse_angka(isset($row->beli) ? $row->beli : 0)));
	$beli_baru = $beli_lama + $tambah;
	$penj = max(0, (int) floor(persediaan_parse_angka(isset($row->penjualan) ? $row->penjualan : 0)));
	$pecah = max(0, (int) floor(persediaan_parse_angka(isset($row->pecah_satuan) ? $row->pecah_satuan : 0)));
	$produksi = max(0, (int) floor(persediaan_parse_angka(isset($row->bahan_produksi) ? $row->bahan_produksi : 0)));
	$gross = $sa + $beli_baru;
	$penj = min($penj, $gross);
	$sisa_setelah_penj = max(0, $gross - $penj);
	$pecah = min($pecah, $sisa_setelah_penj);
	$sisa_setelah_pecah = max(0, $sisa_setelah_penj - $pecah);
	$produksi = min($produksi, $sisa_setelah_pecah);
	$total_10 = max(0, $gross - $penj - $pecah - $produksi);
	$hpp = persediaan_parse_angka(isset($row->hpp) ? $row->hpp : 0);
	$nilai = (int) floor($total_10 * $hpp);

	$beli_t = (string) (int) floor($beli_baru);
	$total_t = (string) (int) floor($total_10);
	$nilai_t = (string) $nilai;

	$upd = array(
		'beli' => $beli_t,
		'total_10' => $total_t,
		'nilai_persediaan' => $nilai_t,
		'tuj' => $total_t,
	);
	persediaan_row_apply_asal_generate_flag($upd, false, $CI);

	$CI->db->where('id', (int) $row->id);
	$CI->db->update('persediaan', $upd);

	return array(
		'sa' => (string) (int) floor($sa),
		'beli_lama' => (string) (int) floor($beli_lama),
		'beli_baru' => $beli_t,
		'tambah' => (string) $tambah,
		'total_10_lama' => (string) max(0, (int) floor(persediaan_parse_angka(isset($row->total_10) ? $row->total_10 : 0))),
		'total_10' => $total_t,
		'nilai_persediaan' => $nilai_t,
	);
}

/**
 * Daftar baris persediaan bulan target dengan kode SPOP yang sama (spop valid).
 */
function persediaan_generate_recalculate_list_persediaan_by_spop($spop, $map)
{
	if (empty($map) || !persediaan_recalculate_spop_valid($spop)) {
		return array();
	}

	$spop_key = persediaan_recalculate_spop_match_value($spop);
	if ($spop_key === '' || empty($map['by_spop_valid'][$spop_key]) || !is_array($map['by_spop_valid'][$spop_key])) {
		return array();
	}

	return array_values($map['by_spop_valid'][$spop_key]);
}

/**
 * Jumlahkan field numerik dari beberapa baris persediaan (SPOP sama).
 */
function persediaan_generate_recalculate_sum_field_persediaan_rows($rows, $field)
{
	$CI = function_exists('get_instance') ? get_instance() : null;
	if ($CI) {
		$CI->load->helper('persediaan_display');
	}

	$sum = 0;
	if (!is_array($rows)) {
		return 0;
	}

	foreach ($rows as $row) {
		$sum += max(0, (int) floor(persediaan_parse_angka(isset($row->{$field}) ? $row->{$field} : 0)));
	}

	return max(0, (int) $sum);
}

/**
 * Update beli, total_10, tuj, nilai_persediaan pada keeper SPOP (rumus user: beli/total_10 += jumlah pembelian).
 */
function persediaan_generate_recalculate_set_beli_total_spop_keeper($CI, $keeper, $beli_baru, $total_10_baru)
{
	$CI->load->helper('persediaan_display');

	if (!$keeper || empty($keeper->id)) {
		return array();
	}

	$beli_baru = max(0, (int) floor($beli_baru));
	$total_10_baru = max(0, (int) floor($total_10_baru));
	$hpp = persediaan_parse_angka(isset($keeper->hpp) ? $keeper->hpp : 0);
	$nilai = (int) floor($total_10_baru * $hpp);

	$beli_t = (string) $beli_baru;
	$total_t = (string) $total_10_baru;
	$nilai_t = (string) $nilai;

	$upd = array(
		'beli' => $beli_t,
		'total_10' => $total_t,
		'nilai_persediaan' => $nilai_t,
		'tuj' => $total_t,
	);
	persediaan_row_apply_asal_generate_flag($upd, false, $CI);

	$CI->db->where('id', (int) $keeper->id);
	$CI->db->update('persediaan', $upd);

	return array(
		'beli_baru' => $beli_t,
		'total_10' => $total_t,
		'nilai_persediaan' => $nilai_t,
	);
}

/**
 * Proses pembelian per grup SPOP: jumlahkan semua record pembelian (field jumlah),
 * gabung beli & total_10 persediaan SPOP sama, lalu update keeper.
 */
function persediaan_generate_recalculate_proses_pembelian_spop_group($CI, $ctx, $queue_item, &$map)
{
	$CI->load->helper('persediaan_display');

	$tabel = isset($queue_item['tabel']) ? $queue_item['tabel'] : 'tbl_pembelian';
	$id = (int) (isset($queue_item['id']) ? $queue_item['id'] : 0);
	$spop = isset($queue_item['spop']) ? $queue_item['spop'] : '';
	$jumlah_total = max(0, (int) floor(persediaan_parse_angka(isset($queue_item['jumlah_total']) ? $queue_item['jumlah_total'] : 0)));
	$record_count = max(0, (int) (isset($queue_item['record_count']) ? $queue_item['record_count'] : 0));

	$row = $CI->db->where('id', $id)->limit(1)->get($tabel)->row();
	if (!$row) {
		return array(
			'fase' => 'pembelian',
			'aksi' => 'SKIP',
			'tabel' => $tabel,
			'id_pembelian' => $id,
			'spop' => $spop,
			'namabarang' => isset($queue_item['uraian']) ? $queue_item['uraian'] : '',
			'keterangan' => 'Record pembelian perwakilan SPOP tidak ditemukan',
		);
	}

	if ($jumlah_total <= 0) {
		return array(
			'fase' => 'pembelian',
			'aksi' => 'SKIP',
			'tabel' => $tabel,
			'id_pembelian' => $id,
			'spop' => $spop,
			'namabarang' => isset($row->uraian) ? $row->uraian : '',
			'jumlah_pembelian' => '0',
			'record_pembelian_spop' => (string) $record_count,
			'keterangan' => 'Lewati: total jumlah pembelian SPOP ' . $spop . ' = 0',
		);
	}

	$nama = isset($row->uraian) ? $row->uraian : '';
	$satuan = isset($row->satuan) ? $row->satuan : '';
	$harga = isset($row->harga_satuan) ? $row->harga_satuan : '';

	$existing = persediaan_recalculate_find_persediaan_for_pembelian_match($row, $map);
	if ($existing) {
		$existing = $CI->db->where('id', (int) $existing->id)->limit(1)->get('persediaan')->row();
	}

	if ($existing) {
		$upd = persediaan_generate_recalculate_tambah_beli_row($CI, $existing, $jumlah_total);
		$row_baru = $CI->db->where('id', (int) $existing->id)->limit(1)->get('persediaan')->row();
		if ($row_baru) {
			persediaan_recalculate_map_add_row($map, $row_baru);
		}

		$sync = persediaan_gen_recalc_ensure_total_10_persediaan($CI, (int) $existing->id);
		$total_10_tampil = $sync ? $sync['total_10'] : $upd['total_10'];
		$keterangan_check = $sync ? $sync['keterangan_check'] : '';

		$ket_grup = ($record_count > 1)
			? 'Grup SPOP+uraian (' . $record_count . ' record tbl_pembelian, id: '
				. implode(',', isset($queue_item['id_pembelian_list']) ? $queue_item['id_pembelian_list'] : array($id)) . '): '
			: 'Update beli (SPOP+uraian cocok): ';

		return array(
			'fase' => 'pembelian',
			'aksi' => 'UPDATE_BELI',
			'tabel' => $tabel,
			'id_pembelian' => $id,
			'id_persediaan' => (int) $existing->id,
			'namabarang' => $nama,
			'satuan' => $satuan,
			'hpp' => $harga,
			'spop' => $spop,
			'jumlah_pembelian' => (string) $jumlah_total,
			'record_pembelian_spop' => (string) $record_count,
			'beli_lama' => $upd['beli_lama'],
			'beli_baru' => $upd['beli_baru'],
			'total_10' => $total_10_tampil,
			'keterangan_check' => $keterangan_check,
			'keterangan' => $ket_grup . 'beli ' . $upd['beli_lama'] . ' + ' . $jumlah_total . ' = ' . $upd['beli_baru']
				. ' | total_10 = (sa+beli)-(penj+pecah+prod) = ' . $total_10_tampil,
		);
	}

	$created = persediaan_recalculate_insert_persediaan_from_pembelian($CI, $ctx, $row, $tabel, $map);
	if (empty($created['ok'])) {
		return array(
			'fase' => 'pembelian',
			'aksi' => 'GAGAL',
			'tabel' => $tabel,
			'id_pembelian' => $id,
			'spop' => $spop,
			'namabarang' => $nama,
			'jumlah_pembelian' => (string) $jumlah_total,
			'record_pembelian_spop' => (string) $record_count,
			'keterangan' => isset($created['alasan']) ? $created['alasan'] : 'Gagal insert persediaan dari pembelian (grup)',
		);
	}

	$row_p = $CI->db->where('id', (int) $created['id_persediaan'])->limit(1)->get('persediaan')->row();
	$upd = array('beli_baru' => (string) $jumlah_total, 'beli_lama' => '0', 'total_10' => '0');
	if ($row_p) {
		$upd = persediaan_generate_recalculate_tambah_beli_row($CI, $row_p, $jumlah_total);
		$row_p = $CI->db->where('id', (int) $created['id_persediaan'])->limit(1)->get('persediaan')->row();
		if ($row_p) {
			persediaan_recalculate_map_add_row($map, $row_p);
		}
	}

	$sync = persediaan_gen_recalc_ensure_total_10_persediaan($CI, (int) $created['id_persediaan']);
	$total_10_tampil = $sync ? $sync['total_10'] : $upd['total_10'];
	$keterangan_check = $sync ? $sync['keterangan_check'] : '';

	$ket_ins = ($record_count > 1)
		? 'Insert baru dari grup SPOP+uraian ' . $record_count . ' record (jumlah=' . $jumlah_total . ')'
		: 'Insert record persediaan baru dari pembelian';

	return array(
		'fase' => 'pembelian',
		'aksi' => 'INSERT_BARU',
		'tabel' => $tabel,
		'id_pembelian' => $id,
		'id_persediaan' => (int) $created['id_persediaan'],
		'namabarang' => $nama,
		'satuan' => $satuan,
		'hpp' => $harga,
		'spop' => $spop,
		'jumlah_pembelian' => (string) $jumlah_total,
		'record_pembelian_spop' => (string) $record_count,
		'beli_baru' => $upd['beli_baru'],
		'total_10' => $total_10_tampil,
		'keterangan_check' => $keterangan_check,
		'keterangan' => $ket_ins . ' | beli=' . $upd['beli_baru'] . ' | total_10=' . $total_10_tampil,
	);
}

function persediaan_generate_recalculate_build_pembelian_queue($CI, $ctx)
{
	$CI->load->helper('persediaan_display');
	$queue = array();
	$by_match = array();
	$db_rows_all = 0;
	$db_rows_jumlah_positif = 0;
	$skipped_jumlah_nol = 0;
	list($tgl_awal_dt, $tgl_akhir_dt) = persediaan_recalculate_pembelian_date_between_params($ctx);

	$tables = array('tbl_pembelian');
	if (!persediaan_generate_recalculate_stop_after_pembelian()) {
		$tables[] = 'tbl_pembelian_jasa';
	}

	foreach ($tables as $tbl) {
		if (!$CI->db->table_exists($tbl)) {
			continue;
		}

		$spop_sql = $CI->db->field_exists('spop', $tbl)
			? 'TRIM(COALESCE(`spop`, \'\')) AS spop'
			: "'' AS spop";

		$list = $CI->db->query(
			"SELECT `id`, `uraian`, `satuan`, `harga_satuan`, `jumlah`, {$spop_sql}
			FROM `{$tbl}`
			WHERE `tgl_po` IS NOT NULL AND `tgl_po` <> '0000-00-00'
			AND `tgl_po` >= ? AND `tgl_po` <= ?
			ORDER BY `uraian` ASC, `id` ASC",
			array($tgl_awal_dt, $tgl_akhir_dt)
		)->result();

		foreach ($list as $row) {
			$db_rows_all++;
			$jumlah = max(0, (int) floor(persediaan_parse_angka(isset($row->jumlah) ? $row->jumlah : 0)));
			if ($jumlah <= 0) {
				$skipped_jumlah_nol++;
				continue;
			}
			$db_rows_jumlah_positif++;

			$spop = isset($row->spop) ? $row->spop : '';
			$match_key = persediaan_recalculate_pembelian_group_key(
				isset($row->uraian) ? $row->uraian : '',
				isset($row->satuan) ? $row->satuan : '',
				isset($row->harga_satuan) ? $row->harga_satuan : '',
				$spop
			);

			if ($match_key === '') {
				$queue[] = array(
					'group_spop' => false,
					'tabel' => $tbl,
					'id' => (int) $row->id,
					'uraian' => isset($row->uraian) ? $row->uraian : '',
					'satuan' => isset($row->satuan) ? $row->satuan : '',
					'harga_satuan' => isset($row->harga_satuan) ? $row->harga_satuan : '',
					'jumlah' => isset($row->jumlah) ? $row->jumlah : 0,
					'spop' => $spop,
				);
				continue;
			}

			if (!isset($by_match[$match_key])) {
				$by_match[$match_key] = array(
					'group_spop' => true,
					'group_match_key' => $match_key,
					'tabel' => $tbl,
					'id' => (int) $row->id,
					'uraian' => isset($row->uraian) ? $row->uraian : '',
					'satuan' => isset($row->satuan) ? $row->satuan : '',
					'harga_satuan' => isset($row->harga_satuan) ? $row->harga_satuan : '',
					'spop' => $spop,
					'match_key' => $match_key,
					'jumlah_total' => 0,
					'record_count' => 0,
					'id_pembelian_list' => array(),
				);
			}

			$by_match[$match_key]['jumlah_total'] += $jumlah;
			$by_match[$match_key]['record_count']++;
			$by_match[$match_key]['id_pembelian_list'][] = (int) $row->id;
		}
	}

	foreach ($by_match as $group) {
		if ((int) $group['record_count'] <= 0) {
			continue;
		}

		if ((int) $group['record_count'] === 1) {
			$queue[] = array(
				'group_spop' => false,
				'tabel' => $group['tabel'],
				'id' => (int) $group['id'],
				'uraian' => $group['uraian'],
				'satuan' => $group['satuan'],
				'harga_satuan' => $group['harga_satuan'],
				'jumlah' => (string) $group['jumlah_total'],
				'spop' => $group['spop'],
			);
			continue;
		}

		$queue[] = $group;
	}

	usort($queue, function ($a, $b) {
		$na = persediaan_recalculate_normalize_nama(isset($a['uraian']) ? $a['uraian'] : '');
		$nb = persediaan_recalculate_normalize_nama(isset($b['uraian']) ? $b['uraian'] : '');
		$c = strcasecmp($na, $nb);
		if ($c !== 0) {
			return $c;
		}

		$sa = isset($a['match_key']) ? $a['match_key'] : (isset($a['spop']) ? $a['spop'] : '');
		$sb = isset($b['match_key']) ? $b['match_key'] : (isset($b['spop']) ? $b['spop'] : '');
		$c2 = strcasecmp((string) $sa, (string) $sb);
		if ($c2 !== 0) {
			return $c2;
		}

		return ((int) $a['id']) - ((int) $b['id']);
	});

	$queue_count = count($queue);
	$GLOBALS['_pembelian_gen_recalc_queue_stats'] = array(
		'db_rows_all' => (int) $db_rows_all,
		'db_rows_jumlah_positif' => (int) $db_rows_jumlah_positif,
		'skipped_jumlah_nol' => (int) $skipped_jumlah_nol,
		'queue_count' => (int) $queue_count,
		'merged_rows' => max(0, (int) $db_rows_jumlah_positif - (int) $queue_count),
	);

	return $queue;
}

function persediaan_generate_recalculate_proses_pembelian_row($CI, $ctx, $queue_item, &$map, $prefetch = null)
{
	$CI->load->helper('persediaan_display');

	if (!empty($queue_item['group_spop'])) {
		return persediaan_generate_recalculate_proses_pembelian_spop_group($CI, $ctx, $queue_item, $map);
	}

	$tabel = $queue_item['tabel'];
	$id = (int) $queue_item['id'];

	$prefetch_key = $tabel . '#' . $id;
	$row = (is_array($prefetch) && isset($prefetch[$prefetch_key])) ? $prefetch[$prefetch_key] : null;
	if (!$row) {
		$row = $CI->db->where('id', $id)->limit(1)->get($tabel)->row();
	}
	if (!$row) {
		return array(
			'fase' => 'pembelian',
			'aksi' => 'SKIP',
			'tabel' => $tabel,
			'id_pembelian' => $id,
			'namabarang' => isset($queue_item['uraian']) ? $queue_item['uraian'] : '',
			'keterangan' => 'Record pembelian tidak ditemukan',
		);
	}

	$jumlah = max(0, (int) floor(persediaan_parse_angka(isset($row->jumlah) ? $row->jumlah : 0)));
	$nama = isset($row->uraian) ? $row->uraian : '';
	$satuan = isset($row->satuan) ? $row->satuan : '';
	$harga = isset($row->harga_satuan) ? $row->harga_satuan : '';
	$spop = isset($row->spop) ? $row->spop : '';

	if ($jumlah <= 0) {
		return array(
			'fase' => 'pembelian',
			'aksi' => 'SKIP',
			'tabel' => $tabel,
			'id_pembelian' => $id,
			'namabarang' => $nama,
			'satuan' => $satuan,
			'hpp' => $harga,
			'spop' => $spop,
			'jumlah_pembelian' => '0',
			'keterangan' => 'Lewati: jumlah pembelian 0',
		);
	}

	$existing = persediaan_recalculate_find_persediaan_for_pembelian_match($row, $map);

	if ($existing) {
		$existing = $CI->db->where('id', (int) $existing->id)->limit(1)->get('persediaan')->row();
	}

	if ($existing) {
		$upd = persediaan_generate_recalculate_tambah_beli_row($CI, $existing, $jumlah);
		$row_baru = $CI->db->where('id', (int) $existing->id)->limit(1)->get('persediaan')->row();
		if ($row_baru) {
			persediaan_recalculate_map_add_row($map, $row_baru);
		}

		$sync = persediaan_gen_recalc_ensure_total_10_persediaan($CI, (int) $existing->id);
		$total_10_tampil = $sync ? $sync['total_10'] : $upd['total_10'];
		$keterangan_check = $sync ? $sync['keterangan_check'] : '';

		return array(
			'fase' => 'pembelian',
			'aksi' => 'UPDATE_BELI',
			'tabel' => $tabel,
			'id_pembelian' => $id,
			'id_persediaan' => (int) $existing->id,
			'namabarang' => $nama,
			'satuan' => $satuan,
			'hpp' => $harga,
			'spop' => $spop,
			'jumlah_pembelian' => (string) $jumlah,
			'beli_lama' => $upd['beli_lama'],
			'beli_baru' => $upd['beli_baru'],
			'total_10' => $total_10_tampil,
			'keterangan_check' => $keterangan_check,
			'keterangan' => 'Update beli (SPOP+uraian atau nama+satuan+hpp+spop cocok): beli ' . $upd['beli_lama'] . ' + ' . $jumlah . ' = ' . $upd['beli_baru']
				. ' | total_10 = (sa+beli)-(penj+pecah+prod) = ' . $total_10_tampil,
		);
	}

	$created = persediaan_recalculate_insert_persediaan_from_pembelian($CI, $ctx, $row, $tabel, $map);
	if (empty($created['ok'])) {
		return array(
			'fase' => 'pembelian',
			'aksi' => 'GAGAL',
			'tabel' => $tabel,
			'id_pembelian' => $id,
			'namabarang' => $nama,
			'satuan' => $satuan,
			'hpp' => $harga,
			'spop' => $spop,
			'jumlah_pembelian' => (string) $jumlah,
			'keterangan' => isset($created['alasan']) ? $created['alasan'] : 'Gagal insert persediaan dari pembelian',
		);
	}

	$row_p = $CI->db->where('id', (int) $created['id_persediaan'])->limit(1)->get('persediaan')->row();
	$upd = array('beli_baru' => (string) $jumlah, 'beli_lama' => '0', 'total_10' => '0');
	if ($row_p) {
		$upd = persediaan_generate_recalculate_tambah_beli_row($CI, $row_p, $jumlah);
		$row_p = $CI->db->where('id', (int) $created['id_persediaan'])->limit(1)->get('persediaan')->row();
		if ($row_p) {
			persediaan_recalculate_map_add_row($map, $row_p);
		}
	}

	$sync = persediaan_gen_recalc_ensure_total_10_persediaan($CI, (int) $created['id_persediaan']);
	$total_10_tampil = $sync ? $sync['total_10'] : $upd['total_10'];
	$keterangan_check = $sync ? $sync['keterangan_check'] : '';

	return array(
		'fase' => 'pembelian',
		'aksi' => 'INSERT_BARU',
		'tabel' => $tabel,
		'id_pembelian' => $id,
		'id_persediaan' => (int) $created['id_persediaan'],
		'namabarang' => $nama,
		'satuan' => $satuan,
		'hpp' => $harga,
		'spop' => $spop,
		'jumlah_pembelian' => (string) $jumlah,
		'beli_baru' => $upd['beli_baru'],
		'total_10' => $total_10_tampil,
		'keterangan_check' => $keterangan_check,
		'keterangan' => 'Insert record persediaan baru dari pembelian (spop berbeda / belum ada di bulan target), beli=' . $upd['beli_baru']
			. ', total_10 = (sa+beli)-(penj+pecah+prod) = ' . $total_10_tampil,
	);
}

/**
 * Filter kandidat persediaan menurut aturan SPOP penjualan:
 * 1) spop cocok pers↔jual, atau
 * 2) spop persediaan kosong/0 (fallback user).
 */
function persediaan_generate_recalculate_filter_kandidat_spop_penjualan($candidates, $spop_jual)
{
	if (!is_array($candidates) || count($candidates) === 0) {
		return array();
	}

	$exact = array();
	$fallback_pers_kosong = array();

	foreach ($candidates as $row) {
		$spop_pers = isset($row->spop) ? $row->spop : '';
		if (persediaan_recalculate_spop_cocok($spop_pers, $spop_jual)) {
			$exact[(int) $row->id] = $row;
		} elseif (persediaan_recalculate_spop_kosong_atau_nol($spop_pers)) {
			$fallback_pers_kosong[(int) $row->id] = $row;
		}
	}

	if (!empty($exact)) {
		return array_values($exact);
	}

	return array_values($fallback_pers_kosong);
}

/**
 * Stok tersedia penjualan: sa+beli-penj_accum (gross) atau total_10-penj_accum (net setelah produksi/pecah).
 */
function persediaan_generate_recalculate_stok_tersedia_accum($row, &$accum, $use_net_stock = false)
{
	$CI = get_instance();
	$CI->load->helper('persediaan_display');

	$pers_id = (int) $row->id;
	$penj_accum = 0;
	if (isset($accum[$pers_id]['penjualan'])) {
		$penj_accum = max(0, (int) floor($accum[$pers_id]['penjualan']));
	}

	if ($use_net_stock) {
		$base = max(0, (int) floor(persediaan_parse_angka(isset($row->total_10) ? $row->total_10 : 0)));
		return max(0, $base - $penj_accum);
	}

	$sa = max(0, (int) floor(persediaan_parse_angka(isset($row->sa) ? $row->sa : 0)));
	$beli = max(0, (int) floor(persediaan_parse_angka(isset($row->beli) ? $row->beli : 0)));
	$gross = $sa + $beli;

	return max(0, $gross - $penj_accum);
}

/**
 * Inisialisasi entri akumulator penjualan untuk satu baris persediaan.
 */
function persediaan_generate_recalculate_accum_init_pers($CI, &$accum, $row)
{
	$pers_id = (int) $row->id;
	if (isset($accum[$pers_id])) {
		return $pers_id;
	}

	$accum[$pers_id] = array(
		'penjualan' => 0,
		'units' => array(),
	);
	foreach (penjualan_persediaan_kolom_unit_existing($CI) as $kolom) {
		$db_col = persediaan_resolve_db_field_name($CI, $kolom);
		$accum[$pers_id]['units'][$db_col] = penjualan_get_nilai_kolom_unit($row, $db_col);
	}

	return $pers_id;
}

/**
 * Tambah penjualan ke akumulator dengan batas stok tersedia (tidak melebihi sa+beli).
 */
function persediaan_generate_recalculate_accum_tambah_penjualan($CI, &$accum, $row, $jumlah, $row_penjualan, $use_net_stock = false)
{
	$CI->load->helper('persediaan_display');
	$jumlah = max(0, (int) floor(persediaan_parse_angka($jumlah)));
	if ($jumlah <= 0 || empty($row)) {
		return 0;
	}

	$stok = persediaan_generate_recalculate_stok_tersedia_accum($row, $accum, $use_net_stock);
	if ($stok <= 0) {
		return 0;
	}

	$alloc = min($jumlah, $stok);
	$pers_id = persediaan_generate_recalculate_accum_init_pers($CI, $accum, $row);
	$accum[$pers_id]['penjualan'] += $alloc;

	$kolom_unit = penjualan_resolve_kolom_persediaan_unit_dari_penjualan($CI, $row_penjualan, true);
	if ($kolom_unit !== null && $kolom_unit !== '') {
		$db_col = persediaan_resolve_db_field_name($CI, $kolom_unit);
		if (!isset($accum[$pers_id]['units'][$db_col])) {
			$accum[$pers_id]['units'][$db_col] = 0;
		}
		$accum[$pers_id]['units'][$db_col] += $alloc;
	}

	return $alloc;
}

/**
 * Kumpulkan semua kandidat persediaan untuk satu baris penjualan (dedupe by id).
 */
function persediaan_generate_recalculate_kumpulkan_kandidat_penjualan($row_penjualan, $map, $strict_uuid_bulan = false)
{
	if (empty($map) || empty($row_penjualan)) {
		return array();
	}

	$nama = isset($row_penjualan->nama_barang) ? $row_penjualan->nama_barang : '';
	$satuan = isset($row_penjualan->satuan) ? $row_penjualan->satuan : '';
	$harga = isset($row_penjualan->harga_satuan) ? $row_penjualan->harga_satuan : '';
	$spop_jual = isset($row_penjualan->spop) ? $row_penjualan->spop : '';

	$raw = array();

	$add_rows = function ($rows) use (&$raw) {
		if (!is_array($rows)) {
			return;
		}
		foreach ($rows as $row) {
			$raw[(int) $row->id] = $row;
		}
	};

	$uuid_p = isset($row_penjualan->uuid_persediaan) ? trim((string) $row_penjualan->uuid_persediaan) : '';
	if ($strict_uuid_bulan && $uuid_p !== '' && !empty($map['by_uuid_pers'][$uuid_p])) {
		$add_rows($map['by_uuid_pers'][$uuid_p]);
	} elseif (!$strict_uuid_bulan) {
		$sync_key = persediaan_recalculate_sync_nama_satuan_hpp_key($nama, $satuan, $harga);
		if ($sync_key !== '' && !empty($map['by_sync_key'][$sync_key])) {
			$add_rows($map['by_sync_key'][$sync_key]);
		}

		$add_rows(persediaan_generate_recalculate_kandidat_penjualan_persediaan($map, $nama, $satuan, $harga));

		$uuid_b = isset($row_penjualan->uuid_barang) ? trim((string) $row_penjualan->uuid_barang) : '';
		if ($uuid_b !== '') {
			foreach (array('by_uuid_barang', 'by_master_uuid') as $idx) {
				if (empty($map[$idx][$uuid_b])) {
					continue;
				}
				foreach ($map[$idx][$uuid_b] as $row) {
					$nama_pers = persediaan_recalculate_normalize_nama(isset($row->namabarang) ? $row->namabarang : '');
					$nama_jual = persediaan_recalculate_normalize_nama($nama);
					if ($nama_pers !== '' && $nama_jual !== '' && $nama_pers === $nama_jual
						&& persediaan_recalculate_satuan_cocok_pembelian(isset($row->satuan) ? $row->satuan : '', $satuan)) {
						$raw[(int) $row->id] = $row;
					}
				}
			}
		}

		$id_pers = isset($row_penjualan->id_persediaan_barang)
			? (int) $row_penjualan->id_persediaan_barang
			: 0;
		if ($id_pers > 0 && isset($map['by_id'][$id_pers])) {
			$row = $map['by_id'][$id_pers];
			$nama_pers = persediaan_recalculate_normalize_nama(isset($row->namabarang) ? $row->namabarang : '');
			$nama_jual = persediaan_recalculate_normalize_nama($nama);
			if ($nama_pers !== '' && $nama_jual !== '' && $nama_pers === $nama_jual
				&& persediaan_recalculate_satuan_cocok_pembelian(isset($row->satuan) ? $row->satuan : '', $satuan)) {
				$raw[$id_pers] = $row;
			}
		}

		if ($uuid_p !== '' && !empty($map['by_uuid_pers'][$uuid_p])) {
			$add_rows($map['by_uuid_pers'][$uuid_p]);
		}

		$ns_key = persediaan_recalculate_nama_satuan_key($nama, $satuan);
		if ($ns_key !== '' && !empty($map['by_nama_satuan'][$ns_key])) {
			$add_rows($map['by_nama_satuan'][$ns_key]);
		}
	}

	$filtered = array();
	foreach ($raw as $row) {
		$spop_pers = isset($row->spop) ? $row->spop : '';
		if (persediaan_recalculate_spop_cocok($spop_pers, $spop_jual)) {
			$filtered[(int) $row->id] = $row;
		} elseif (persediaan_recalculate_spop_kosong_atau_nol($spop_pers)) {
			$filtered[(int) $row->id] = $row;
		} elseif (persediaan_recalculate_spop_kosong_atau_nol($spop_jual)) {
			$filtered[(int) $row->id] = $row;
		}
	}

	return array_values($filtered);
}

/**
 * Urutkan kandidat untuk alokasi penjualan: hpp cocok → sudah terisi → beli>0 → hpp terdekat → id.
 */
function persediaan_generate_recalculate_urut_kandidat_penjualan($candidates, $row_penjualan, &$accum)
{
	if (!is_array($candidates) || count($candidates) === 0) {
		return array();
	}

	$CI = get_instance();
	$CI->load->helper('persediaan_display');
	$harga = isset($row_penjualan->harga_satuan) ? $row_penjualan->harga_satuan : '';
	$harga_num = persediaan_parse_angka($harga);

	usort($candidates, function ($a, $b) use ($harga, $harga_num, $accum) {
		$a_exact = persediaan_recalculate_harga_cocok(isset($a->hpp) ? $a->hpp : 0, $harga) ? 1 : 0;
		$b_exact = persediaan_recalculate_harga_cocok(isset($b->hpp) ? $b->hpp : 0, $harga) ? 1 : 0;
		if ($a_exact !== $b_exact) {
			return $b_exact - $a_exact;
		}

		$a_id = (int) $a->id;
		$b_id = (int) $b->id;
		$a_penj_accum = isset($accum[$a_id]['penjualan']) ? (int) $accum[$a_id]['penjualan'] : 0;
		$b_penj_accum = isset($accum[$b_id]['penjualan']) ? (int) $accum[$b_id]['penjualan'] : 0;
		if ($a_penj_accum > 0 && $b_penj_accum === 0) {
			return -1;
		}
		if ($b_penj_accum > 0 && $a_penj_accum === 0) {
			return 1;
		}

		$a_beli = max(0, (int) floor(persediaan_parse_angka(isset($a->beli) ? $a->beli : 0)));
		$b_beli = max(0, (int) floor(persediaan_parse_angka(isset($b->beli) ? $b->beli : 0)));
		if ($a_beli > 0 && $b_beli === 0) {
			return -1;
		}
		if ($b_beli > 0 && $a_beli === 0) {
			return 1;
		}

		$a_diff = abs(persediaan_parse_angka(isset($a->hpp) ? $a->hpp : 0) - $harga_num);
		$b_diff = abs(persediaan_parse_angka(isset($b->hpp) ? $b->hpp : 0) - $harga_num);
		if ($a_diff !== $b_diff) {
			return ($a_diff < $b_diff) ? -1 : 1;
		}

		return $a_id - $b_id;
	});

	return $candidates;
}

/**
 * Alokasikan jumlah penjualan ke satu/lebih baris persediaan (maks = stok sa+beli per baris).
 */
function persediaan_generate_recalculate_allocate_penjualan($CI, $row_penjualan, $jumlah, $map, &$accum, $use_net_stock = false)
{
	$CI->load->helper('persediaan_display');
	$jumlah = max(0, (int) floor(persediaan_parse_angka($jumlah)));
	if ($jumlah <= 0) {
		return array('allocated' => 0, 'remaining' => 0, 'rows' => array());
	}

	$candidates = persediaan_generate_recalculate_kumpulkan_kandidat_penjualan($row_penjualan, $map, true);
	if (empty($candidates)) {
		return array('allocated' => 0, 'remaining' => $jumlah, 'rows' => array());
	}

	$candidates = persediaan_generate_recalculate_urut_kandidat_penjualan($candidates, $row_penjualan, $accum);
	$remaining = $jumlah;
	$allocated = 0;
	$rows_hit = array();

	foreach ($candidates as $row) {
		if ($remaining <= 0) {
			break;
		}
		$row_fresh = persediaan_recalculate_map_row_fresh($map, $row);
		if (!$row_fresh) {
			$row_fresh = $row;
		}
		$stok = persediaan_generate_recalculate_stok_tersedia_accum($row_fresh, $accum, $use_net_stock);
		if ($stok <= 0) {
			continue;
		}
		$chunk = persediaan_generate_recalculate_accum_tambah_penjualan(
			$CI,
			$accum,
			$row_fresh,
			$remaining,
			$row_penjualan,
			$use_net_stock
		);
		if ($chunk <= 0) {
			continue;
		}
		$allocated += $chunk;
		$remaining -= $chunk;
		$rows_hit[] = array(
			'id_persediaan' => (int) $row_fresh->id,
			'jumlah' => $chunk,
		);
	}

	return array(
		'allocated' => $allocated,
		'remaining' => $remaining,
		'rows' => $rows_hit,
	);
}

/**
 * Pilih satu baris persediaan dari beberapa kandidat (prioritas stok + hpp ≈ harga_satuan).
 */
function persediaan_generate_recalculate_pick_persediaan_penjualan($candidates, $row_penjualan, &$accum = null)
{
	if (!is_array($candidates) || count($candidates) === 0) {
		return null;
	}

	$accum_local = is_array($accum) ? $accum : array();
	$sorted = persediaan_generate_recalculate_urut_kandidat_penjualan($candidates, $row_penjualan, $accum_local);
	foreach ($sorted as $row) {
		if (persediaan_generate_recalculate_stok_tersedia_accum($row, $accum_local) > 0) {
			return $row;
		}
	}

	return persediaan_recalculate_pick_best_persediaan_row($candidates, $row_penjualan);
}

/**
 * Pastikan penjualan/total_10/tuj/kolom unit konsisten: total_10 = sa+beli-penjualan-pecah_satuan-bahan_produksi.
 */
function persediaan_generate_recalculate_reconcile_persediaan_bulan($CI, $tanggal_beli)
{
	$CI->load->helper('persediaan_display');

	$rows = $CI->db->query(
		"SELECT * FROM `persediaan` WHERE `tanggal_beli` = ? ORDER BY `id` ASC",
		array($tanggal_beli)
	)->result();

	$updated = 0;
	$fixed_penjualan = 0;

	foreach ($rows as $row) {
		$sa = max(0, (int) floor(persediaan_parse_angka(isset($row->sa) ? $row->sa : 0)));
		$beli = max(0, (int) floor(persediaan_parse_angka(isset($row->beli) ? $row->beli : 0)));
		$pecah = max(0, (int) floor(persediaan_parse_angka(isset($row->pecah_satuan) ? $row->pecah_satuan : 0)));
		$produksi = max(0, (int) floor(persediaan_parse_angka(isset($row->bahan_produksi) ? $row->bahan_produksi : 0)));
		$gross = $sa + $beli;
		$penj = max(0, (int) floor(persediaan_parse_angka(isset($row->penjualan) ? $row->penjualan : 0)));
		$penj_lama = $penj;

		if ($penj > $gross) {
			$penj = $gross;
			$fixed_penjualan++;
		}

		$total_10 = max(0, $gross - $penj - $pecah - $produksi);
		$hpp = persediaan_parse_angka(isset($row->hpp) ? $row->hpp : 0);
		$nilai = (int) floor($total_10 * $hpp);

		$update = array(
			'penjualan' => (string) $penj,
			'total_10' => (string) $total_10,
			'nilai_persediaan' => (string) $nilai,
		);
		if ($CI->db->field_exists('tuj', 'persediaan')) {
			$update['tuj'] = (string) $total_10;
		}

		$sum_unit = 0;
		$unit_cols = array();
		foreach (penjualan_persediaan_kolom_unit_existing($CI) as $kolom) {
			$db_col = persediaan_resolve_db_field_name($CI, $kolom);
			if (!$CI->db->field_exists($db_col, 'persediaan')) {
				continue;
			}
			$val = max(0, (int) floor(penjualan_get_nilai_kolom_unit($row, $db_col)));
			$unit_cols[$db_col] = $val;
			$sum_unit += $val;
		}
		if ($sum_unit > $penj && $penj > 0 && $sum_unit > 0) {
			$ratio = $penj / $sum_unit;
			foreach ($unit_cols as $db_col => $val) {
				$unit_cols[$db_col] = (int) floor($val * $ratio);
			}
		} elseif ($sum_unit > $penj) {
			foreach ($unit_cols as $db_col => $val) {
				$unit_cols[$db_col] = 0;
			}
		}
		foreach ($unit_cols as $db_col => $val) {
			$update[$db_col] = (string) max(0, (int) $val);
		}

		$total_lama = (string) max(0, (int) floor(persediaan_parse_angka(isset($row->total_10) ? $row->total_10 : 0)));
		$penj_str = (string) $penj;
		$total_str = (string) $total_10;
		$nilai_str = (string) $nilai;
		$needs = ($penj_str !== (string) (int) floor($penj_lama))
			|| ($total_str !== $total_lama)
			|| ((string) (isset($row->nilai_persediaan) ? $row->nilai_persediaan : '') !== $nilai_str);

		if (!$needs) {
			foreach ($unit_cols as $db_col => $val) {
				$cur_val = (string) max(0, (int) floor(penjualan_get_nilai_kolom_unit($row, $db_col)));
				if ($cur_val !== (string) max(0, (int) $val)) {
					$needs = true;
					break;
				}
			}
		}

		if ($needs) {
			$CI->db->where('id', (int) $row->id);
			if ($CI->db->update('persediaan', $update)) {
				$updated++;
			}
		}
	}

	return array(
		'updated' => $updated,
		'fixed_penjualan_cap' => $fixed_penjualan,
		'total_rows' => count($rows),
	);
}

/**
 * Resolve baris persediaan bulan target untuk satu baris tbl_penjualan.
 */
function persediaan_generate_recalculate_find_persediaan_for_penjualan($row_penjualan, $map, &$accum = null)
{
	if (empty($map) || empty($row_penjualan)) {
		return null;
	}

	$candidates = persediaan_generate_recalculate_kumpulkan_kandidat_penjualan($row_penjualan, $map);
	if (empty($candidates)) {
		return null;
	}

	$accum_ref = is_array($accum) ? $accum : array();
	$pick = persediaan_generate_recalculate_pick_persediaan_penjualan($candidates, $row_penjualan, $accum_ref);
	if (!$pick) {
		return null;
	}

	return persediaan_recalculate_map_row_fresh($map, $pick);
}

/**
 * Kandidat persediaan untuk penjualan: cocok nama + satuan + hpp.
 */
function persediaan_generate_recalculate_kandidat_penjualan_persediaan($map, $nama, $satuan, $harga)
{
	$found = array();

	$nama_key_hpp = persediaan_recalculate_nama_satuan_hpp_key($nama, $satuan, $harga);
	if ($nama_key_hpp !== '' && !empty($map['by_nama_key'][$nama_key_hpp])) {
		foreach ($map['by_nama_key'][$nama_key_hpp] as $row) {
			$found[(int) $row->id] = $row;
		}
	}

	$sync_key = persediaan_recalculate_sync_nama_satuan_hpp_key($nama, $satuan, $harga);
	if ($sync_key !== '' && !empty($map['by_sync_key'][$sync_key])) {
		foreach ($map['by_sync_key'][$sync_key] as $row) {
			$found[(int) $row->id] = $row;
		}
	}

	$ns_key = persediaan_recalculate_nama_satuan_key($nama, $satuan);
	if ($ns_key !== '' && !empty($map['by_nama_satuan'][$ns_key])) {
		foreach ($map['by_nama_satuan'][$ns_key] as $row) {
			if (persediaan_recalculate_harga_cocok(isset($row->hpp) ? $row->hpp : 0, $harga)) {
				$found[(int) $row->id] = $row;
			}
		}
	}

	$n_only = persediaan_recalculate_normalize_nama($nama);
	if ($n_only !== '' && !empty($map['by_nama'][$n_only])) {
		foreach ($map['by_nama'][$n_only] as $row) {
			if (persediaan_recalculate_satuan_cocok_pembelian(isset($row->satuan) ? $row->satuan : '', $satuan)
				&& persediaan_recalculate_harga_cocok(isset($row->hpp) ? $row->hpp : 0, $harga)) {
				$found[(int) $row->id] = $row;
			}
		}
	}

	return array_values($found);
}

/**
 * Reset & hitung ulang kolom beli persediaan bulan target dari pembelian (grup SPOP + per baris).
 */
function persediaan_generate_recalculate_reapply_pembelian_bulan($CI, $ctx)
{
	$CI->load->helper('persediaan_display');

	$tanggal_beli = isset($ctx['tanggal_beli_target']) ? $ctx['tanggal_beli_target'] : $ctx['tanggal_beli'];
	$reset_info = persediaan_recalculate_reset_beli_persediaan_bulan($CI, $tanggal_beli);

	$map = persediaan_recalculate_build_map_persediaan_bulan($CI, $tanggal_beli);
	$queue = persediaan_generate_recalculate_build_pembelian_queue($CI, $ctx);

	$updated = 0;
	$with_beli = 0;
	$processed_pers_ids = array();

	foreach ($queue as $queue_item) {
		if (!empty($queue_item['group_spop'])) {
			$spop = isset($queue_item['spop']) ? $queue_item['spop'] : '';
			$jumlah_total = max(0, (int) floor(persediaan_parse_angka(isset($queue_item['jumlah_total']) ? $queue_item['jumlah_total'] : 0)));
			if ($jumlah_total <= 0) {
				continue;
			}

			$pers_rows = persediaan_generate_recalculate_list_persediaan_by_spop($spop, $map);
			if (empty($pers_rows)) {
				continue;
			}

			foreach ($pers_rows as $idx => $pr) {
				$fresh = $CI->db->where('id', (int) $pr->id)->limit(1)->get('persediaan')->row();
				if ($fresh) {
					$pers_rows[$idx] = $fresh;
				}
			}

			$sum_beli_pers = persediaan_generate_recalculate_sum_field_persediaan_rows($pers_rows, 'beli');
			$sum_total_10_pers = persediaan_generate_recalculate_sum_field_persediaan_rows($pers_rows, 'total_10');
			$beli_baru = $sum_beli_pers + $jumlah_total;
			$total_10_baru = $sum_total_10_pers + $jumlah_total;

			$keeper = persediaan_duplikat_spop_pick_keeper($pers_rows, $CI);
			if (!$keeper) {
				$keeper = $pers_rows[0];
			}

			persediaan_generate_recalculate_set_beli_total_spop_keeper($CI, $keeper, $beli_baru, $total_10_baru);
			$row_keeper = $CI->db->where('id', (int) $keeper->id)->limit(1)->get('persediaan')->row();
			if ($row_keeper) {
				persediaan_recalculate_map_add_row($map, $row_keeper);
			}

			foreach ($pers_rows as $pr) {
				$processed_pers_ids[(int) $pr->id] = true;
			}
			$updated++;
			if ($beli_baru > 0) {
				$with_beli++;
			}
			continue;
		}
	}

	$rows = $CI->db->query(
		"SELECT * FROM `persediaan` WHERE `tanggal_beli` = ? ORDER BY `id` ASC",
		array($tanggal_beli)
	)->result();

	foreach ($rows as $row) {
		if (!empty($processed_pers_ids[(int) $row->id])) {
			continue;
		}

		$beli = persediaan_recalculate_hitung_beli_row_by_kategori(
			$CI,
			$row,
			$ctx['tgl_awal'],
			$ctx['tgl_akhir']
		);
		persediaan_recalculate_update_beli_row($CI, $row, $beli);
		$updated++;
		if ($beli > 0) {
			$with_beli++;
		}
	}

	return array(
		'reset_beli' => $reset_info,
		'updated' => $updated,
		'with_beli' => $with_beli,
	);
}

/**
 * Bangun akumulator penjualan dari seluruh tbl_penjualan bulan target lalu flush ke DB.
 */
function persediaan_generate_recalculate_reapply_penjualan_bulan($CI, $ctx)
{
	$CI->load->helper('persediaan_display');

	if (!$CI->db->table_exists('tbl_penjualan')) {
		return array('matched' => 0, 'skipped' => 0, 'flushed' => 0);
	}

	if (function_exists('persediaan_generate_recalculate_ensure_unit_kolom_dari_transaksi')) {
		persediaan_generate_recalculate_ensure_unit_kolom_dari_transaksi($CI, $ctx);
	}
	persediaan_recalculate_prepare_penjualan_bulan($CI, $ctx);

	$tanggal_beli = isset($ctx['tanggal_beli_target']) ? $ctx['tanggal_beli_target'] : $ctx['tanggal_beli'];
	$map = persediaan_recalculate_build_map_persediaan_bulan($CI, $tanggal_beli);

	$list = $CI->db->query(
		"SELECT * FROM `tbl_penjualan`
		WHERE `tgl_jual` IS NOT NULL AND `tgl_jual` <> '0000-00-00'
		AND DATE(`tgl_jual`) >= ? AND DATE(`tgl_jual`) <= ?
		ORDER BY `id` ASC",
		array($ctx['tgl_awal'], $ctx['tgl_akhir'])
	)->result();

	$accum = array();
	$matched = 0;
	$skipped = 0;
	$partial = 0;
	$overstock_prevented = 0;

	foreach ($list as $row_penjualan) {
		$jumlah = persediaan_recalculate_parse_jumlah_penjualan(isset($row_penjualan->jumlah) ? $row_penjualan->jumlah : 0);
		if ($jumlah <= 0) {
			$skipped++;
			continue;
		}

		$alloc = persediaan_generate_recalculate_allocate_penjualan($CI, $row_penjualan, $jumlah, $map, $accum);
		if ((int) $alloc['allocated'] <= 0) {
			$skipped++;
			continue;
		}

		$matched++;
		if ((int) $alloc['remaining'] > 0) {
			$partial++;
			$overstock_prevented += (int) $alloc['remaining'];
		}
	}

	$flushed = persediaan_generate_recalculate_finalize_keluar_per_persediaan_bulan($CI, $ctx, $accum);
	persediaan_generate_recalculate_apply_pecah_satuan_targets_bulan($CI, $ctx);
	$reconcile = persediaan_generate_recalculate_reconcile_persediaan_bulan($CI, $tanggal_beli);

	return array(
		'matched' => $matched,
		'skipped' => $skipped,
		'partial' => $partial,
		'overstock_prevented' => $overstock_prevented,
		'flushed' => isset($flushed['updated']) ? (int) $flushed['updated'] : 0,
		'reconcile' => $reconcile,
		'with_penjualan' => isset($flushed['with_penjualan']) ? (int) $flushed['with_penjualan'] : 0,
		'with_pecah' => isset($flushed['with_pecah']) ? (int) $flushed['with_pecah'] : 0,
		'with_produksi' => isset($flushed['with_produksi']) ? (int) $flushed['with_produksi'] : 0,
	);
}

/**
 * Pastikan kolom unit persediaan ada untuk semua unit di pembelian/penjualan bulan target.
 */
function persediaan_generate_recalculate_ensure_unit_kolom_dari_transaksi($CI, $ctx)
{
	$created = 0;
	$seen = array();

	if ($CI->db->table_exists('tbl_penjualan')) {
		$rows = $CI->db->query(
			"SELECT DISTINCT `uuid_unit`, `unit` FROM `tbl_penjualan`
			WHERE `tgl_jual` IS NOT NULL AND `tgl_jual` <> '0000-00-00'
			AND DATE(`tgl_jual`) >= ? AND DATE(`tgl_jual`) <= ?",
			array($ctx['tgl_awal'], $ctx['tgl_akhir'])
		)->result();

		foreach ($rows as $row) {
			$uuid = trim((string) (isset($row->uuid_unit) ? $row->uuid_unit : ''));
			if ($uuid !== '') {
				if (isset($seen['u:' . $uuid])) {
					continue;
				}
				$seen['u:' . $uuid] = true;
				$res = penjualan_ensure_persediaan_kolom_unit($CI, $uuid);
				if (!empty($res['created'])) {
					$created++;
				}
				continue;
			}

			$unit = trim((string) (isset($row->unit) ? $row->unit : ''));
			if ($unit === '' || isset($seen['t:' . strtolower($unit)])) {
				continue;
			}
			$seen['t:' . strtolower($unit)] = true;
			$res = penjualan_ensure_persediaan_kolom_unit_dari_teks($CI, $unit);
			if (!empty($res['created'])) {
				$created++;
			}
		}
	}

	foreach (array('tbl_pembelian', 'tbl_pembelian_jasa') as $tbl) {
		if (!$CI->db->table_exists($tbl) || !$CI->db->field_exists('uuid_unit', $tbl)) {
			continue;
		}

		$rows = $CI->db->query(
			"SELECT DISTINCT `uuid_unit` FROM `{$tbl}`
			WHERE `tgl_po` IS NOT NULL AND `tgl_po` <> '0000-00-00'
			AND DATE(`tgl_po`) >= ? AND DATE(`tgl_po`) <= ?
			AND TRIM(COALESCE(`uuid_unit`, '')) <> ''",
			array($ctx['tgl_awal'], $ctx['tgl_akhir'])
		)->result();

		foreach ($rows as $row) {
			$uuid = trim((string) $row->uuid_unit);
			if ($uuid === '' || isset($seen['u:' . $uuid])) {
				continue;
			}
			$seen['u:' . $uuid] = true;
			$res = penjualan_ensure_persediaan_kolom_unit($CI, $uuid);
			if (!empty($res['created'])) {
				$created++;
			}
		}
	}

	return array('created' => $created);
}

/**
 * Inisialisasi fase penjualan generate: kolom unit, reset penjualan, akumulator.
 */
function persediaan_generate_recalculate_init_penjualan_phase($CI, $ctx, &$state)
{
	if (empty($state['penjualan_phase_inited'])) {
		$after_keluar = !empty($state['keluar_phases_done']);
		$prep = persediaan_recalculate_prepare_penjualan_bulan($CI, $ctx, $after_keluar);
		$state['penjualan_prepare'] = $prep;
		$state['penjualan_phase_inited'] = 1;
		$state['penjualan_use_net_stock'] = $after_keluar ? 1 : 0;
		$state['penjualan_accum'] = array();
	}
	if (!isset($state['penjualan_accum']) || !is_array($state['penjualan_accum'])) {
		$state['penjualan_accum'] = array();
	}
}

/**
 * Stok tersedia penjualan generate: total_10 saat ini (setelah restore sa+beli).
 */
function persediaan_generate_recalculate_stok_tersedia_penjualan($row)
{
	$CI = get_instance();
	$CI->load->helper('persediaan_display');

	return max(0, (int) floor(persediaan_parse_angka(isset($row->total_10) ? $row->total_10 : 0)));
}

function persediaan_generate_recalculate_build_penjualan_queue($CI, $ctx)
{
	if (!$CI->db->table_exists('tbl_penjualan')) {
		return array();
	}

	$tanggal_beli_target = isset($ctx['tanggal_beli_target']) ? $ctx['tanggal_beli_target'] : $ctx['tanggal_beli'];

	$spop_sql = $CI->db->field_exists('spop', 'tbl_penjualan')
		? 'TRIM(COALESCE(`spop`, \'\')) AS spop'
		: "'' AS spop";
	$uuid_sql = $CI->db->field_exists('uuid_persediaan', 'tbl_penjualan')
		? 'TRIM(COALESCE(`uuid_persediaan`, \'\')) AS uuid_persediaan'
		: "'' AS uuid_persediaan";

	$list = $CI->db->query(
		"SELECT `id`, `nama_barang`, `satuan`, `harga_satuan`, `jumlah`, `uuid_unit`, `unit`, {$spop_sql}, {$uuid_sql}
		FROM `tbl_penjualan`
		WHERE `tgl_jual` IS NOT NULL AND `tgl_jual` <> '0000-00-00'
		AND DATE(`tgl_jual`) >= ? AND DATE(`tgl_jual`) <= ?
		ORDER BY `nama_barang` ASC, `id` ASC",
		array($ctx['tgl_awal'], $ctx['tgl_akhir'])
	)->result();

	$queue = array();
	foreach ($list as $row) {
		$uuid_p = isset($row->uuid_persediaan) ? trim((string) $row->uuid_persediaan) : '';

		$queue[] = array(
			'id' => (int) $row->id,
			'nama_barang' => isset($row->nama_barang) ? $row->nama_barang : '',
			'satuan' => isset($row->satuan) ? $row->satuan : '',
			'harga_satuan' => isset($row->harga_satuan) ? $row->harga_satuan : '',
			'jumlah' => isset($row->jumlah) ? $row->jumlah : 0,
			'spop' => isset($row->spop) ? $row->spop : '',
			'unit' => isset($row->unit) ? $row->unit : '',
			'uuid_persediaan' => $uuid_p,
			'_row' => $row,
		);
	}

	return $queue;
}

function persediaan_generate_recalculate_tambah_penjualan_row($CI, $row, $tambah_jumlah, $row_penjualan)
{
	$CI->load->helper('persediaan_display');

	$tambah = max(0, (int) floor(persediaan_parse_angka($tambah_jumlah)));
	$penjualan_lama = max(0, (int) floor(persediaan_parse_angka(isset($row->penjualan) ? $row->penjualan : 0)));
	$penjualan_baru = $penjualan_lama + $tambah;
	$total_10_lama = max(0, (int) floor(persediaan_parse_angka(isset($row->total_10) ? $row->total_10 : 0)));
	$total_10 = max(0, $total_10_lama - $tambah);
	$hpp = persediaan_parse_angka(isset($row->hpp) ? $row->hpp : 0);
	$nilai = (int) floor($total_10 * $hpp);

	$update = array(
		'penjualan' => (string) (int) floor($penjualan_baru),
		'total_10' => (string) (int) floor($total_10),
		'nilai_persediaan' => (string) $nilai,
		'tuj' => (string) (int) floor($total_10),
	);

	$kolom_unit = penjualan_resolve_kolom_persediaan_unit_dari_penjualan($CI, $row_penjualan, true);
	$unit_lama = 0;
	$unit_baru = 0;
	$nama_unit = isset($row_penjualan->unit) ? trim((string) $row_penjualan->unit) : '';
	if ($kolom_unit !== null && $kolom_unit !== '' && $CI->db->field_exists($kolom_unit, 'persediaan')) {
		$unit_lama = (int) floor(penjualan_get_nilai_kolom_unit($row, $kolom_unit));
		$unit_baru = $unit_lama + $tambah;
		$update[$kolom_unit] = (string) (int) floor($unit_baru);
		if ($nama_unit === '' && function_exists('penjualan_get_label_kolom_unit')) {
			$nama_unit = penjualan_get_label_kolom_unit($kolom_unit);
		}
	}

	$CI->db->where('id', (int) $row->id);
	$CI->db->update('persediaan', $update);

	return array(
		'penjualan_lama' => (string) (int) floor($penjualan_lama),
		'penjualan_baru' => (string) (int) floor($penjualan_baru),
		'total_10_lama' => (string) (int) floor($total_10_lama),
		'total_10' => (string) (int) floor($total_10),
		'sisa_stock' => (string) (int) floor($total_10),
		'nilai_persediaan' => (string) $nilai,
		'kolom_unit' => $kolom_unit ? $kolom_unit : '',
		'nama_unit' => $nama_unit,
		'unit_lama' => (string) (int) floor($unit_lama),
		'unit_baru' => (string) (int) floor($unit_baru),
		'tambah' => (string) $tambah,
	);
}

/**
 * Cari record persediaan bulan target untuk fase penjualan:
 * 1) uuid_persediaan, 2) fallback nama_barang + satuan.
 */
function persediaan_generate_recalculate_find_penjualan_target_persediaan($map, $nama, $satuan, $uuid_p, $ref = null)
{
	$uuid_p = trim((string) $uuid_p);
	if ($uuid_p !== '') {
		$by_uuid = persediaan_generate_recalculate_find_by_uuid_persediaan($map, $uuid_p, $ref);
		if ($by_uuid) {
			return array(
				'row' => $by_uuid,
				'match_via' => 'uuid_persediaan',
			);
		}
	}

	$nama = trim((string) $nama);
	$satuan = trim((string) $satuan);
	if ($nama === '' || $satuan === '') {
		return array(
			'row' => null,
			'match_via' => '',
		);
	}

	$ns_key = persediaan_recalculate_nama_satuan_key($nama, $satuan);
	if ($ns_key !== '' && !empty($map['by_nama_satuan'][$ns_key])) {
		$candidates = $map['by_nama_satuan'][$ns_key];
		$pick = null;
		if ($ref !== null && function_exists('persediaan_recalculate_pick_best_persediaan_row')) {
			$pick = persediaan_recalculate_pick_best_persediaan_row($candidates, $ref);
		}
		if (!$pick && !empty($candidates)) {
			$pick = $candidates[0];
		}
		if ($pick) {
			return array(
				'row' => $pick,
				'match_via' => 'nama_barang+satuan',
			);
		}
	}

	if (!empty($map['by_id']) && is_array($map['by_id'])) {
		foreach ($map['by_id'] as $row) {
			$nama_row = persediaan_recalculate_normalize_nama(
				persediaan_recalculate_sanitize_nama_persediaan(isset($row->namabarang) ? $row->namabarang : '')
			);
			$nama_cmp = persediaan_recalculate_normalize_nama(
				persediaan_recalculate_sanitize_nama_persediaan($nama)
			);
			if ($nama_row === '' || $nama_cmp === '' || $nama_row !== $nama_cmp) {
				continue;
			}
			if (!persediaan_recalculate_satuan_cocok_pembelian(isset($row->satuan) ? $row->satuan : '', $satuan)) {
				continue;
			}
			return array(
				'row' => $row,
				'match_via' => 'nama_barang+satuan',
			);
		}
	}

	return array(
		'row' => null,
		'match_via' => '',
	);
}

function persediaan_generate_recalculate_proses_penjualan_row($CI, $ctx, $queue_item, &$map, &$accum, $use_net_stock = false)
{
	$CI->load->helper('persediaan_display');
	$id = (int) $queue_item['id'];

	$row = (isset($queue_item['_row']) && is_object($queue_item['_row'])) ? $queue_item['_row'] : null;
	if (!$row) {
		$row = $CI->db->where('id', $id)->limit(1)->get('tbl_penjualan')->row();
	}
	if (!$row) {
		return array(
			'fase' => 'penjualan',
			'aksi' => 'SKIP',
			'id_penjualan' => $id,
			'namabarang' => isset($queue_item['nama_barang']) ? $queue_item['nama_barang'] : '',
			'keterangan' => 'Record penjualan tidak ditemukan',
		);
	}

	$jumlah = persediaan_recalculate_parse_jumlah_penjualan(isset($row->jumlah) ? $row->jumlah : 0);
	$nama = isset($row->nama_barang) ? $row->nama_barang : '';
	$satuan = isset($row->satuan) ? $row->satuan : '';
	$harga = isset($row->harga_satuan) ? $row->harga_satuan : '';
	$spop = isset($row->spop) ? $row->spop : '';
	$nama_unit = isset($row->unit) ? trim((string) $row->unit) : '';
	$uuid_p = isset($row->uuid_persediaan) ? trim((string) $row->uuid_persediaan) : '';
	if ($uuid_p === '' && isset($queue_item['uuid_persediaan'])) {
		$uuid_p = trim((string) $queue_item['uuid_persediaan']);
	}

	if ($jumlah <= 0) {
		return array(
			'fase' => 'penjualan',
			'aksi' => 'SKIP',
			'id_penjualan' => $id,
			'namabarang' => $nama,
			'satuan' => $satuan,
			'hpp' => $harga,
			'spop' => $spop,
			'uuid_persediaan' => $uuid_p,
			'jumlah_penjualan' => '0',
			'keterangan' => 'Lewati: jumlah penjualan 0',
		);
	}

	$ref_match = (object) array(
		'nama_barang' => $nama,
		'satuan' => $satuan,
		'harga_satuan' => $harga,
	);
	$lookup = persediaan_generate_recalculate_find_penjualan_target_persediaan($map, $nama, $satuan, $uuid_p, $ref_match);
	$existing = isset($lookup['row']) ? $lookup['row'] : null;
	$match_via = isset($lookup['match_via']) ? $lookup['match_via'] : '';

	if (!$existing) {
		$keterangan_gagal = '';
		if ($uuid_p !== '') {
			$keterangan_gagal = 'uuid_persediaan=' . $uuid_p . ' tidak ditemukan di persediaan bulan target';
			if ($nama !== '' && $satuan !== '') {
				$keterangan_gagal .= '; fallback nama_barang+satuan (' . $nama . ' / ' . $satuan . ') juga tidak ditemukan';
			}
		} elseif ($nama !== '' && $satuan !== '') {
			$keterangan_gagal = 'uuid_persediaan kosong; nama_barang+satuan (' . $nama . ' / ' . $satuan . ') tidak ditemukan di persediaan bulan target';
		} else {
			$keterangan_gagal = 'uuid_persediaan kosong dan nama_barang/satuan tidak lengkap — tidak dapat update persediaan';
		}

		return array(
			'fase' => 'penjualan',
			'aksi' => 'TIDAK_COCOK',
			'id_penjualan' => $id,
			'namabarang' => $nama,
			'satuan' => $satuan,
			'hpp' => $harga,
			'spop' => $spop,
			'uuid_persediaan' => $uuid_p,
			'jumlah_penjualan' => (string) $jumlah,
			'keterangan' => $keterangan_gagal,
		);
	}

	$existing = $CI->db->where('id', (int) $existing->id)->limit(1)->get('persediaan')->row();
	if (!$existing) {
		return array(
			'fase' => 'penjualan',
			'aksi' => 'GAGAL',
			'id_penjualan' => $id,
			'uuid_persediaan' => $uuid_p,
			'namabarang' => $nama,
			'satuan' => $satuan,
			'jumlah_penjualan' => (string) $jumlah,
			'keterangan' => 'Record persediaan tidak ditemukan setelah cocokkan ' . ($match_via !== '' ? $match_via : 'lookup'),
		);
	}

	$upd = persediaan_generate_recalculate_tambah_penjualan_row($CI, $existing, $jumlah, $row);
	$row_baru = $CI->db->where('id', (int) $existing->id)->limit(1)->get('persediaan')->row();
	if ($row_baru) {
		persediaan_recalculate_map_add_row($map, $row_baru);
	}

	$sync = persediaan_gen_recalc_ensure_total_10_persediaan($CI, (int) $existing->id);
	$total_10_tampil = $sync ? $sync['total_10'] : $upd['total_10'];
	$keterangan_check = $sync ? $sync['keterangan_check'] : '';

	$kolom_unit = isset($upd['kolom_unit']) ? $upd['kolom_unit'] : '';
	if ($nama_unit === '' && $kolom_unit !== '' && function_exists('penjualan_get_label_kolom_unit')) {
		$nama_unit = penjualan_get_label_kolom_unit($kolom_unit);
	}

	$keterangan = 'cocok via ' . ($match_via !== '' ? $match_via : 'lookup')
		. ($uuid_p !== '' ? ' | uuid_persediaan=' . $uuid_p : ' | uuid_persediaan kosong')
		. ' | unit+=' . $jumlah . ' (kolom ' . ($kolom_unit !== '' ? $kolom_unit : ($nama_unit !== '' ? $nama_unit : 'unit')) . ': ' . $upd['unit_lama'] . ' → ' . $upd['unit_baru'] . ')'
		. ' | penjualan+=' . $jumlah . ' (' . $upd['penjualan_lama'] . ' → ' . $upd['penjualan_baru'] . ')'
		. ' | total_10-=' . $jumlah . ' (' . $upd['total_10_lama'] . ' → ' . $total_10_tampil . ')';

	return array(
		'fase' => 'penjualan',
		'aksi' => 'UPDATE_PENJUALAN',
		'id_penjualan' => $id,
		'id_persediaan' => (int) $existing->id,
		'namabarang' => $nama,
		'satuan' => $satuan,
		'hpp' => $harga,
		'spop' => $spop,
		'uuid_persediaan' => $uuid_p,
		'match_via' => $match_via,
		'nama_unit' => $nama_unit,
		'kolom_unit' => $kolom_unit,
		'jumlah_penjualan' => (string) $jumlah,
		'jumlah_teralokasi' => (string) $jumlah,
		'penjualan_lama' => $upd['penjualan_lama'],
		'penjualan_baru' => $upd['penjualan_baru'],
		'unit_lama' => $upd['unit_lama'],
		'unit_baru' => $upd['unit_baru'],
		'total_10' => $total_10_tampil,
		'sisa_stock' => $total_10_tampil,
		'nilai_persediaan' => $upd['nilai_persediaan'],
		'keterangan' => $keterangan,
		'keterangan_check' => $keterangan_check,
	);
}

/**
 * Reset bahan_produksi=0 sebelum recalculate fase produksi (total_10 diatur fase penjualan).
 */
function persediaan_recalculate_reset_bahan_produksi_persediaan_bulan($CI, $tanggal_beli)
{
	$CI->load->helper('persediaan_display');

	if (!$CI->db->field_exists('bahan_produksi', 'persediaan')) {
		return array('record_direset' => 0, 'total_rows' => 0);
	}

	$rows = $CI->db->query(
		"SELECT `id`, `bahan_produksi` FROM `persediaan` WHERE `tanggal_beli` = ? ORDER BY `id` ASC",
		array($tanggal_beli)
	)->result();

	$updated = 0;
	foreach ($rows as $row) {
		$bahan = max(0, (int) floor(persediaan_parse_angka(isset($row->bahan_produksi) ? $row->bahan_produksi : 0)));
		if ($bahan <= 0) {
			continue;
		}

		$CI->db->where('id', (int) $row->id);
		if ($CI->db->update('persediaan', array('bahan_produksi' => '0'))) {
			$updated++;
		}
	}

	return array(
		'record_direset' => $updated,
		'total_rows' => count($rows),
	);
}

/**
 * -------------------------------------------------------------------------
 * Fase 3 — Produk jadi (sys_unit_produk → sa & total_10 persediaan)
 * -------------------------------------------------------------------------
 */
function persediaan_generate_recalculate_group_key_nama_satuan_hpp_spop($nama, $satuan, $harga, $spop)
{
	$nama_n = persediaan_recalculate_normalize_nama(
		persediaan_recalculate_sanitize_nama_persediaan($nama)
	);
	$satuan_n = persediaan_recalculate_sanitize_satuan_persediaan($satuan);
	$hpp_k = persediaan_recalculate_angka_match_key($harga);
	$spop_k = persediaan_recalculate_spop_match_value($spop);

	return $nama_n . '|' . $satuan_n . '|' . $hpp_k . '|' . $spop_k;
}

function persediaan_generate_recalculate_bahan_row_uuid_persediaan($row)
{
	if (empty($row)) {
		return '';
	}

	if (isset($row->uuid_persediaan) && trim((string) $row->uuid_persediaan) !== '') {
		return trim((string) $row->uuid_persediaan);
	}

	if (isset($row->uuid_persediaan_bahan) && trim((string) $row->uuid_persediaan_bahan) !== '') {
		return trim((string) $row->uuid_persediaan_bahan);
	}

	return '';
}

function persediaan_generate_recalculate_resolve_spop_dari_uuid_persediaan($CI, $uuid_pers)
{
	$uuid_pers = trim((string) $uuid_pers);
	if ($uuid_pers === '') {
		return '';
	}

	$row = $CI->db->where('uuid_persediaan', $uuid_pers)->limit(1)->get('persediaan')->row();
	if (!$row || !isset($row->spop)) {
		return '';
	}

	return trim((string) $row->spop);
}

function persediaan_generate_recalculate_prefix_nama_barang_spop($nama_barang)
{
	$nama_barang = trim((string) $nama_barang);
	if ($nama_barang === '') {
		return 'ITEM';
	}

	if (function_exists('mb_substr')) {
		$prefix = mb_substr($nama_barang, 0, 5, 'UTF-8');
	} else {
		$prefix = substr($nama_barang, 0, 5);
	}

	$prefix = preg_replace('/\s+/', '', $prefix);

	return $prefix !== '' ? $prefix : 'ITEM';
}

function persediaan_generate_recalculate_generate_spop_produksi($CI, $tgl_transaksi, $nama_barang = '')
{
	$ts = strtotime((string) $tgl_transaksi);
	if ($ts === false) {
		$ts = time();
	}

	$nama_kode = persediaan_generate_recalculate_prefix_nama_barang_spop($nama_barang);
	$kode_waktu = date('YmdHi', $ts);
	$spop_produksi = 'PRODUK_' . $nama_kode . '_' . $kode_waktu;

	$CI->db->where('spop', $spop_produksi);
	$jumlah_spop = $CI->db->count_all_results('persediaan');
	if ($jumlah_spop > 0) {
		$counter = 1;
		do {
			$spop_candidate = 'PRODUK_' . $nama_kode . '_' . $kode_waktu . '_' . $counter;
			$CI->db->where('spop', $spop_candidate);
			$exists = $CI->db->count_all_results('persediaan') > 0;
			$counter++;
		} while ($exists);
		$spop_produksi = $spop_candidate;
	}

	return $spop_produksi;
}

function persediaan_generate_recalculate_resolve_spop_unit_produk($CI, $row_unit)
{
	if (empty($row_unit)) {
		return '';
	}

	$uuid = isset($row_unit->uuid_persediaan) ? trim((string) $row_unit->uuid_persediaan) : '';
	if ($uuid !== '') {
		$spop = persediaan_generate_recalculate_resolve_spop_dari_uuid_persediaan($CI, $uuid);
		if ($spop !== '') {
			return $spop;
		}
	}

	$tgl = isset($row_unit->tgl_transaksi) ? $row_unit->tgl_transaksi : date('Y-m-d H:i:s');
	$nama = isset($row_unit->nama_barang) ? $row_unit->nama_barang : '';

	return persediaan_generate_recalculate_generate_spop_produksi($CI, $tgl, $nama);
}

function persediaan_generate_recalculate_resolve_spop_produksi_bahan($CI, $row_bahan)
{
	if (empty($row_bahan)) {
		return '';
	}

	$uuid = persediaan_generate_recalculate_bahan_row_uuid_persediaan($row_bahan);
	if ($uuid !== '') {
		$spop = persediaan_generate_recalculate_resolve_spop_dari_uuid_persediaan($CI, $uuid);
		if ($spop !== '') {
			return $spop;
		}
	}

	return '';
}

function persediaan_generate_recalculate_build_unit_produk_queue($CI, $ctx)
{
	if (!$CI->db->table_exists('sys_unit_produk')) {
		return array();
	}

	$list = $CI->db->query(
		"SELECT `id`, `uuid_persediaan`, `uuid_produk`, `kode_barang`, `nama_barang`, `satuan`, `harga_satuan`, `jumlah_produksi`, `nama_unit`, `tgl_transaksi`
		FROM `sys_unit_produk`
		WHERE `tgl_transaksi` IS NOT NULL AND `tgl_transaksi` <> '0000-00-00'
		AND DATE(`tgl_transaksi`) >= ? AND DATE(`tgl_transaksi`) <= ?
		ORDER BY `nama_barang` ASC, `id` ASC",
		array($ctx['tgl_awal'], $ctx['tgl_akhir'])
	)->result();

	$CI->load->helper('persediaan_display');
	$agg = array();

	foreach ($list as $row) {
		$jumlah = max(0, (int) floor(persediaan_parse_angka(isset($row->jumlah_produksi) ? $row->jumlah_produksi : 0)));
		if ($jumlah <= 0) {
			continue;
		}

		$spop = persediaan_generate_recalculate_resolve_spop_unit_produk($CI, $row);
		$uuid_p_row = isset($row->uuid_persediaan) ? trim((string) $row->uuid_persediaan) : '';
		if ($uuid_p_row !== '') {
			$key = 'uuid:' . strtolower($uuid_p_row);
		} else {
			$key = persediaan_generate_recalculate_group_key_nama_satuan_hpp_spop(
				isset($row->nama_barang) ? $row->nama_barang : '',
				isset($row->satuan) ? $row->satuan : '',
				isset($row->harga_satuan) ? $row->harga_satuan : '',
				$spop
			);
		}

		if (!isset($agg[$key])) {
			$uuid_barang = '';
			if (isset($row->uuid_produk) && trim((string) $row->uuid_produk) !== '') {
				$uuid_barang = trim((string) $row->uuid_produk);
			} elseif (isset($row->uuid_barang) && trim((string) $row->uuid_barang) !== '') {
				$uuid_barang = trim((string) $row->uuid_barang);
			}

			$agg[$key] = array(
				'aggregated' => 1,
				'group_key' => $key,
				'nama_barang' => isset($row->nama_barang) ? $row->nama_barang : '',
				'satuan' => isset($row->satuan) ? $row->satuan : '',
				'harga_satuan' => isset($row->harga_satuan) ? $row->harga_satuan : '',
				'spop' => $spop,
				'jumlah_produksi' => 0,
				'source_ids' => array(),
				'uuid_persediaan' => $uuid_p_row !== '' ? $uuid_p_row : (isset($row->uuid_persediaan) ? trim((string) $row->uuid_persediaan) : ''),
				'uuid_barang' => $uuid_barang,
				'kode_barang' => isset($row->kode_barang) ? $row->kode_barang : '',
				'nama_unit' => isset($row->nama_unit) ? $row->nama_unit : '',
				'tgl_transaksi' => isset($row->tgl_transaksi) ? $row->tgl_transaksi : '',
			);
		}

		$agg[$key]['jumlah_produksi'] += $jumlah;
		$agg[$key]['source_ids'][] = (int) $row->id;
	}

	return array_values($agg);
}

function persediaan_generate_recalculate_init_unit_produk_phase($CI, $ctx, &$state)
{
	if (empty($state['unit_produk_phase_inited'])) {
		$state['unit_produk_phase_inited'] = 1;
	}
}

function persediaan_generate_recalculate_tambah_sa_unit_produk_row($CI, $row, $tambah_jumlah)
{
	$CI->load->helper('persediaan_display');
	$tambah = max(0, (int) floor(persediaan_parse_angka($tambah_jumlah)));
	$sa_lama = max(0, (int) floor(persediaan_parse_angka(isset($row->sa) ? $row->sa : 0)));
	$sa_baru = $sa_lama + $tambah;
	$beli = max(0, (int) floor(persediaan_parse_angka(isset($row->beli) ? $row->beli : 0)));
	$penj = max(0, (int) floor(persediaan_parse_angka(isset($row->penjualan) ? $row->penjualan : 0)));
	$pecah = max(0, (int) floor(persediaan_parse_angka(isset($row->pecah_satuan) ? $row->pecah_satuan : 0)));
	$produksi = max(0, (int) floor(persediaan_parse_angka(isset($row->bahan_produksi) ? $row->bahan_produksi : 0)));
	$gross = $sa_baru + $beli;
	$penj = min($penj, $gross);
	$sisa_setelah_penj = max(0, $gross - $penj);
	$pecah = min($pecah, $sisa_setelah_penj);
	$sisa_setelah_pecah = max(0, $sisa_setelah_penj - $pecah);
	$produksi = min($produksi, $sisa_setelah_pecah);
	$total_10 = max(0, $gross - $penj - $pecah - $produksi);
	$hpp = persediaan_parse_angka(isset($row->hpp) ? $row->hpp : 0);
	$nilai = (int) floor($total_10 * $hpp);

	$sa_t = (string) (int) floor($sa_baru);
	$total_t = (string) (int) floor($total_10);
	$nilai_t = (string) $nilai;

	$upd = array(
		'sa' => $sa_t,
		'total_10' => $total_t,
		'nilai_persediaan' => $nilai_t,
		'tuj' => $total_t,
	);
	persediaan_row_apply_asal_generate_flag($upd, false, $CI);

	$CI->db->where('id', (int) $row->id);
	$CI->db->update('persediaan', $upd);

	return array(
		'sa_lama' => (string) (int) floor($sa_lama),
		'sa_baru' => $sa_t,
		'tambah' => (string) $tambah,
		'total_10' => $total_t,
		'nilai_persediaan' => $nilai_t,
	);
}

function persediaan_generate_recalculate_insert_persediaan_from_unit_produk($CI, $ctx, $queue_item, &$map)
{
	$nama = isset($queue_item['nama_barang']) ? trim((string) $queue_item['nama_barang']) : '';
	if ($nama === '') {
		return array('ok' => false, 'alasan' => 'nama_barang kosong');
	}

	$satuan = isset($queue_item['satuan']) ? trim((string) $queue_item['satuan']) : '';
	if ($satuan === '') {
		return array('ok' => false, 'alasan' => 'satuan kosong');
	}
	$satuan = persediaan_recalculate_sanitize_satuan_persediaan($satuan);
	if ($satuan === '') {
		return array('ok' => false, 'alasan' => 'satuan kosong');
	}

	$harga = isset($queue_item['harga_satuan']) ? $queue_item['harga_satuan'] : 0;
	$spop = isset($queue_item['spop']) ? $queue_item['spop'] : '';
	$existing = persediaan_generate_recalculate_find_by_nama_hpp_spop($map, $nama, $satuan, $harga, $spop);
	if ($existing) {
		return array(
			'ok' => false,
			'existing' => true,
			'alasan' => 'Sudah ada di persediaan id=' . (int) $existing->id . ' — tidak di-insert ulang',
		);
	}

	$CI->load->helper('persediaan_display');
	$jumlah = max(0, (int) floor(persediaan_parse_angka(isset($queue_item['jumlah_produksi']) ? $queue_item['jumlah_produksi'] : 0)));
	if ($jumlah <= 0) {
		return array('ok' => false, 'alasan' => 'jumlah_produksi 0');
	}

	$nama = persediaan_recalculate_sanitize_nama_persediaan($nama);
	$hpp = persediaan_parse_angka($harga);
	$uuid_src = isset($queue_item['uuid_barang']) ? $queue_item['uuid_barang'] : '';
	$uuid_res = persediaan_recalculate_resolve_uuid_barang_pembelian($CI, $uuid_src, $map);
	$uuid_barang = $uuid_res['uuid'];

	$kode = isset($queue_item['kode_barang']) ? trim((string) $queue_item['kode_barang']) : '';
	if (strlen($kode) > 255) {
		$kode = substr($kode, 0, 255);
	}

	$tgl = isset($queue_item['tgl_transaksi']) ? $queue_item['tgl_transaksi'] : date('Y-m-d H:i:s');
	$ts = strtotime((string) $tgl);
	if ($ts === false) {
		$ts = time();
	}

	if ($spop === '') {
		$spop = persediaan_generate_recalculate_generate_spop_produksi($CI, $tgl, $nama);
	}

	$new_id = persediaan_recalculate_next_id($CI, $map);
	$nilai = (int) floor($jumlah * $hpp);
	$data = array(
		'id' => $new_id,
		'tanggal' => date('Y-m-d H:i:s', $ts),
		'tanggal_beli' => $ctx['tanggal_beli_target'],
		'uuid_barang' => $uuid_barang,
		'kode' => $kode,
		'namabarang' => $nama,
		'satuan' => $satuan,
		'hpp' => (string) (int) floor($hpp),
		'sa' => (string) $jumlah,
		'beli' => '0',
		'penjualan' => '0',
		'spop' => $spop,
		'total_10' => (string) $jumlah,
		'nilai_persediaan' => (string) $nilai,
		'tuj' => (string) $jumlah,
	);

	if ($CI->db->field_exists('pecah_satuan', 'persediaan')) {
		$data['pecah_satuan'] = '0';
	}
	if ($CI->db->field_exists('bahan_produksi', 'persediaan')) {
		$data['bahan_produksi'] = '0';
	}

	persediaan_row_apply_asal_generate_flag($data, true, $CI);
	$CI->db->set('uuid_persediaan', "replace(uuid(),'-','')", FALSE);
	$CI->db->insert('persediaan', $data);

	$row_baru = $CI->db->where('id', (int) $new_id)->limit(1)->get('persediaan')->row();
	if ($row_baru) {
		persediaan_recalculate_map_add_row($map, $row_baru);
	}

	return array(
		'ok' => true,
		'id_persediaan' => (int) $new_id,
		'spop' => $spop,
	);
}

/**
 * Cari baris persediaan bulan target via uuid_persediaan (prioritas untuk sys_unit_produk).
 */
function persediaan_generate_recalculate_find_by_uuid_persediaan($map, $uuid_p, $ref = null)
{
	$uuid_p = trim((string) $uuid_p);
	if ($uuid_p === '' || empty($map['by_uuid_pers'][$uuid_p])) {
		return null;
	}

	$candidates = $map['by_uuid_pers'][$uuid_p];
	if (count($candidates) === 1) {
		return $candidates[0];
	}

	if ($ref !== null) {
		return persediaan_recalculate_pick_best_persediaan_row($candidates, $ref);
	}

	return $candidates[0];
}

function persediaan_generate_recalculate_proses_unit_produk_row($CI, $ctx, $queue_item, &$map)
{
	$CI->load->helper('persediaan_display');

	$jumlah = max(0, (int) floor(persediaan_parse_angka(isset($queue_item['jumlah_produksi']) ? $queue_item['jumlah_produksi'] : 0)));
	$nama = isset($queue_item['nama_barang']) ? $queue_item['nama_barang'] : '';
	$satuan = isset($queue_item['satuan']) ? $queue_item['satuan'] : '';
	$harga = isset($queue_item['harga_satuan']) ? $queue_item['harga_satuan'] : '';
	$spop = isset($queue_item['spop']) ? $queue_item['spop'] : '';
	$uuid_p = isset($queue_item['uuid_persediaan']) ? trim((string) $queue_item['uuid_persediaan']) : '';
	$nama_unit = isset($queue_item['nama_unit']) ? trim((string) $queue_item['nama_unit']) : '';
	$source_ids = isset($queue_item['source_ids']) && is_array($queue_item['source_ids']) ? $queue_item['source_ids'] : array();
	$ref_match = (object) array(
		'nama_barang' => $nama,
		'satuan' => $satuan,
		'harga_satuan' => $harga,
	);

	if ($jumlah <= 0) {
		return array(
			'fase' => 'unit_produk',
			'aksi' => 'SKIP',
			'id_unit_produk' => implode(',', $source_ids),
			'namabarang' => $nama,
			'satuan' => $satuan,
			'hpp' => $harga,
			'spop' => $spop,
			'jumlah_produksi' => '0',
			'keterangan' => 'Lewati: jumlah produksi 0',
		);
	}

	$match_via = '';
	$existing = null;
	if ($uuid_p !== '') {
		$existing = persediaan_generate_recalculate_find_by_uuid_persediaan($map, $uuid_p, $ref_match);
		if ($existing) {
			$match_via = 'uuid_persediaan';
		}
	}
	if (!$existing) {
		$existing = persediaan_generate_recalculate_find_by_nama_hpp_spop($map, $nama, $satuan, $harga, $spop);
		if ($existing) {
			$match_via = 'nama+satuan+hpp+spop';
		}
	}

	if ($existing) {
		$existing = $CI->db->where('id', (int) $existing->id)->limit(1)->get('persediaan')->row();
	}

	if ($existing) {
		$upd = persediaan_generate_recalculate_tambah_sa_unit_produk_row($CI, $existing, $jumlah);
		$row_baru = $CI->db->where('id', (int) $existing->id)->limit(1)->get('persediaan')->row();
		if ($row_baru) {
			persediaan_recalculate_map_add_row($map, $row_baru);
		}

		$sync = persediaan_gen_recalc_ensure_total_10_persediaan($CI, (int) $existing->id);
		$total_10_tampil = $sync ? $sync['total_10'] : $upd['total_10'];
		$keterangan_check = $sync ? $sync['keterangan_check'] : '';

		return array(
			'fase' => 'unit_produk',
			'aksi' => 'UPDATE_SA',
			'id_unit_produk' => implode(',', $source_ids),
			'id_persediaan' => (int) $existing->id,
			'namabarang' => $nama,
			'satuan' => $satuan,
			'hpp' => $harga,
			'spop' => $spop,
			'nama_unit' => $nama_unit,
			'jumlah_produksi' => (string) $jumlah,
			'sa_lama' => $upd['sa_lama'],
			'sa_baru' => $upd['sa_baru'],
			'total_10' => $total_10_tampil,
			'keterangan_check' => $keterangan_check,
			'keterangan' => 'Update SA (' . ($match_via !== '' ? $match_via : 'cocok') . '): sa ' . $upd['sa_lama'] . ' + ' . $jumlah . ' = ' . $upd['sa_baru']
				. ($uuid_p !== '' ? ' | uuid_persediaan=' . $uuid_p : '')
				. ' | total_10 = (sa+beli)-(penj+pecah+prod) = ' . $total_10_tampil
				. (count($source_ids) > 1 ? ' | agregasi ' . count($source_ids) . ' baris sys_unit_produk' : ''),
		);
	}

	$created = persediaan_generate_recalculate_insert_persediaan_from_unit_produk($CI, $ctx, $queue_item, $map);
	if (empty($created['ok'])) {
		return array(
			'fase' => 'unit_produk',
			'aksi' => 'GAGAL',
			'id_unit_produk' => implode(',', $source_ids),
			'namabarang' => $nama,
			'satuan' => $satuan,
			'hpp' => $harga,
			'spop' => $spop,
			'jumlah_produksi' => (string) $jumlah,
			'keterangan' => isset($created['alasan']) ? $created['alasan'] : 'Gagal insert persediaan dari sys_unit_produk',
		);
	}

	$row_p = $CI->db->where('id', (int) $created['id_persediaan'])->limit(1)->get('persediaan')->row();
	$sync = persediaan_gen_recalc_ensure_total_10_persediaan($CI, (int) $created['id_persediaan']);
	$total_10_tampil = $sync ? $sync['total_10'] : (string) $jumlah;
	$keterangan_check = $sync ? $sync['keterangan_check'] : '';
	$spop_ins = isset($created['spop']) ? $created['spop'] : $spop;

	return array(
		'fase' => 'unit_produk',
		'aksi' => 'INSERT_BARU',
		'id_unit_produk' => implode(',', $source_ids),
		'id_persediaan' => (int) $created['id_persediaan'],
		'namabarang' => $nama,
		'satuan' => $satuan,
		'hpp' => $harga,
		'spop' => $spop_ins,
		'nama_unit' => $nama_unit,
		'jumlah_produksi' => (string) $jumlah,
		'sa_baru' => $row_p ? (string) (isset($row_p->sa) ? $row_p->sa : $jumlah) : (string) $jumlah,
		'total_10' => $total_10_tampil,
		'keterangan_check' => $keterangan_check,
		'keterangan' => 'Insert record persediaan baru dari sys_unit_produk, sa=' . $jumlah
			. ', total_10 = (sa+beli)-(penj+pecah+prod) = ' . $total_10_tampil
			. (count($source_ids) > 1 ? ' | agregasi ' . count($source_ids) . ' baris sys_unit_produk' : ''),
	);
}

/**
 * Persiapan fase produksi: reset bahan_produksi=0 (total_10 sudah net setelah fase penjualan).
 */
function persediaan_recalculate_prepare_produksi_bulan($CI, $ctx)
{
	$tanggal_beli = isset($ctx['tanggal_beli']) ? $ctx['tanggal_beli'] : '';
	if (isset($ctx['tanggal_beli_target']) && trim((string) $ctx['tanggal_beli_target']) !== '') {
		$tanggal_beli = $ctx['tanggal_beli_target'];
	}

	if (trim((string) $tanggal_beli) === '') {
		return array('reset' => array('record_direset' => 0));
	}

	$reset = persediaan_recalculate_reset_bahan_produksi_persediaan_bulan($CI, $tanggal_beli);

	return array(
		'reset' => $reset,
		'tanggal_beli' => $tanggal_beli,
	);
}

/**
 * Kandidat persediaan untuk bahan produksi: uuid_persediaan + nama+satuan+hpp+spop.
 */
function persediaan_generate_recalculate_kumpulkan_kandidat_produksi_bahan($CI, $row_bahan, $map)
{
	if (empty($map) || empty($row_bahan)) {
		return array();
	}

	$raw = array();

	$uuid_p = persediaan_generate_recalculate_bahan_row_uuid_persediaan($row_bahan);
	if ($uuid_p !== '' && !empty($map['by_uuid_pers'][$uuid_p])) {
		foreach ($map['by_uuid_pers'][$uuid_p] as $row) {
			$raw[(int) $row->id] = $row;
		}
	}

	$nama = isset($row_bahan->nama_barang_bahan) ? $row_bahan->nama_barang_bahan : '';
	$satuan = isset($row_bahan->satuan_bahan) ? $row_bahan->satuan_bahan : '';
	$harga = isset($row_bahan->harga_satuan_bahan) ? $row_bahan->harga_satuan_bahan : '';
	$spop = persediaan_generate_recalculate_resolve_spop_produksi_bahan($CI, $row_bahan);

	$exact = persediaan_generate_recalculate_find_by_nama_hpp_spop($map, $nama, $satuan, $harga, $spop);
	if ($exact) {
		$raw[(int) $exact->id] = $exact;
	}

	foreach (persediaan_generate_recalculate_kandidat_penjualan_persediaan($map, $nama, $satuan, $harga) as $row) {
		if ($spop === '' || persediaan_recalculate_spop_cocok(isset($row->spop) ? $row->spop : '', $spop)) {
			$raw[(int) $row->id] = $row;
		}
	}

	return array_values($raw);
}

/**
 * Resolve baris persediaan bulan target untuk satu baris sys_unit_produk_bahan.
 */
function persediaan_generate_recalculate_find_persediaan_for_produksi_bahan($CI, $row_bahan, $map)
{
	$candidates = persediaan_generate_recalculate_kumpulkan_kandidat_produksi_bahan($CI, $row_bahan, $map);
	if (empty($candidates)) {
		return null;
	}

	$spop = persediaan_generate_recalculate_resolve_spop_produksi_bahan($CI, $row_bahan);
	if ($spop !== '') {
		$nama = isset($row_bahan->nama_barang_bahan) ? $row_bahan->nama_barang_bahan : '';
		$satuan = isset($row_bahan->satuan_bahan) ? $row_bahan->satuan_bahan : '';
		$harga = isset($row_bahan->harga_satuan_bahan) ? $row_bahan->harga_satuan_bahan : '';
		$exact = persediaan_generate_recalculate_find_by_nama_hpp_spop($map, $nama, $satuan, $harga, $spop);
		if ($exact) {
			return $exact;
		}
	}

	$ref = (object) array(
		'nama_barang' => isset($row_bahan->nama_barang_bahan) ? $row_bahan->nama_barang_bahan : '',
		'satuan' => isset($row_bahan->satuan_bahan) ? $row_bahan->satuan_bahan : '',
		'harga_satuan' => isset($row_bahan->harga_satuan_bahan) ? $row_bahan->harga_satuan_bahan : '',
	);

	return persediaan_recalculate_pick_best_persediaan_row($candidates, $ref);
}

function persediaan_generate_recalculate_build_produksi_queue($CI, $ctx)
{
	if (!$CI->db->table_exists('sys_unit_produk_bahan')) {
		return array();
	}

	$list = $CI->db->query(
		"SELECT `id`, `uuid_persediaan`, `nama_barang_bahan`, `satuan_bahan`, `harga_satuan_bahan`, `jumlah_bahan`, `nama_unit`, `kode_barang_bahan`
		FROM `sys_unit_produk_bahan`
		WHERE `tgl_transaksi` IS NOT NULL AND `tgl_transaksi` <> '0000-00-00'
		AND DATE(`tgl_transaksi`) >= ? AND DATE(`tgl_transaksi`) <= ?
		ORDER BY `nama_barang_bahan` ASC, `id` ASC",
		array($ctx['tgl_awal'], $ctx['tgl_akhir'])
	)->result();

	$CI->load->helper('persediaan_display');
	$agg = array();

	foreach ($list as $row) {
		$jumlah = max(0, (int) floor(persediaan_parse_angka(isset($row->jumlah_bahan) ? $row->jumlah_bahan : 0)));
		if ($jumlah <= 0) {
			continue;
		}

		$spop = persediaan_generate_recalculate_resolve_spop_produksi_bahan($CI, $row);
		$key = persediaan_generate_recalculate_group_key_nama_satuan_hpp_spop(
			isset($row->nama_barang_bahan) ? $row->nama_barang_bahan : '',
			isset($row->satuan_bahan) ? $row->satuan_bahan : '',
			isset($row->harga_satuan_bahan) ? $row->harga_satuan_bahan : '',
			$spop
		);

		if (!isset($agg[$key])) {
			$agg[$key] = array(
				'aggregated' => 1,
				'group_key' => $key,
				'id' => (int) $row->id,
				'uuid_persediaan' => persediaan_generate_recalculate_bahan_row_uuid_persediaan($row),
				'nama_barang_bahan' => isset($row->nama_barang_bahan) ? $row->nama_barang_bahan : '',
				'satuan_bahan' => isset($row->satuan_bahan) ? $row->satuan_bahan : '',
				'harga_satuan_bahan' => isset($row->harga_satuan_bahan) ? $row->harga_satuan_bahan : '',
				'spop' => $spop,
				'jumlah_bahan' => 0,
				'source_ids' => array(),
				'nama_unit' => isset($row->nama_unit) ? $row->nama_unit : '',
				'kode_barang_bahan' => isset($row->kode_barang_bahan) ? $row->kode_barang_bahan : '',
			);
		}

		$agg[$key]['jumlah_bahan'] += $jumlah;
		$agg[$key]['source_ids'][] = (int) $row->id;
	}

	return array_values($agg);
}

function persediaan_recalculate_flush_produksi_accum_to_db($CI, $accum)
{
	$CI->load->helper('persediaan_display');
	$updated = 0;

	foreach ($accum as $pers_id => $data) {
		$bahan = max(0, (int) floor(isset($data['bahan_produksi']) ? $data['bahan_produksi'] : 0));
		if ($bahan <= 0) {
			continue;
		}

		$cur = $CI->db->where('id', (int) $pers_id)->limit(1)->get('persediaan')->row();
		if (!$cur) {
			continue;
		}

		$sa = max(0, (int) floor(persediaan_parse_angka(isset($cur->sa) ? $cur->sa : 0)));
		$beli = max(0, (int) floor(persediaan_parse_angka(isset($cur->beli) ? $cur->beli : 0)));
		$penj = max(0, (int) floor(persediaan_parse_angka(isset($cur->penjualan) ? $cur->penjualan : 0)));
		$pecah = max(0, (int) floor(persediaan_parse_angka(isset($cur->pecah_satuan) ? $cur->pecah_satuan : 0)));
		$gross = $sa + $beli;
		$penj = min($penj, $gross);
		$sisa_setelah_penj = max(0, $gross - $penj);
		$pecah = min($pecah, $sisa_setelah_penj);
		$sisa_setelah_pecah = max(0, $sisa_setelah_penj - $pecah);
		$bahan = min($bahan, $sisa_setelah_pecah);
		$total_10 = max(0, $gross - $penj - $pecah - $bahan);
		$hpp = persediaan_parse_angka(isset($cur->hpp) ? $cur->hpp : 0);
		$nilai = (int) floor($total_10 * $hpp);

		$update = array(
			'bahan_produksi' => (string) $bahan,
			'total_10' => (string) $total_10,
			'nilai_persediaan' => (string) $nilai,
		);
		if ($CI->db->field_exists('tuj', 'persediaan')) {
			$update['tuj'] = (string) $total_10;
		}

		$CI->db->where('id', (int) $pers_id);
		$CI->db->update('persediaan', $update);
		$updated++;
	}

	return $updated;
}

/**
 * Recalculate bahan produksi dari sys_unit_produk_bahan bulan target.
 */
function persediaan_generate_recalculate_reapply_produksi_bulan($CI, $ctx)
{
	$CI->load->helper('persediaan_display');

	if (!$CI->db->table_exists('sys_unit_produk_bahan')) {
		return array('matched' => 0, 'skipped' => 0, 'flushed' => 0, 'with_bahan' => 0);
	}

	persediaan_recalculate_prepare_produksi_bulan($CI, $ctx);

	$tanggal_beli = isset($ctx['tanggal_beli_target']) ? $ctx['tanggal_beli_target'] : $ctx['tanggal_beli'];
	$map = persediaan_recalculate_build_map_persediaan_bulan($CI, $tanggal_beli);

	$list = $CI->db->query(
		"SELECT * FROM `sys_unit_produk_bahan`
		WHERE `tgl_transaksi` IS NOT NULL AND `tgl_transaksi` <> '0000-00-00'
		AND DATE(`tgl_transaksi`) >= ? AND DATE(`tgl_transaksi`) <= ?
		ORDER BY `id` ASC",
		array($ctx['tgl_awal'], $ctx['tgl_akhir'])
	)->result();

	$matched = 0;
	$skipped = 0;
	$flushed = 0;
	$with_bahan_ids = array();

	foreach ($list as $row_bahan) {
		$jumlah = max(0, (int) floor(persediaan_parse_angka(isset($row_bahan->jumlah_bahan) ? $row_bahan->jumlah_bahan : 0)));
		if ($jumlah <= 0) {
			$skipped++;
			continue;
		}

		$pick = persediaan_generate_recalculate_find_persediaan_for_produksi_bahan($CI, $row_bahan, $map);
		if (!$pick) {
			$skipped++;
			continue;
		}

		$pers_id = (int) $pick->id;
		$cur = $CI->db->where('id', $pers_id)->limit(1)->get('persediaan')->row();
		if (!$cur) {
			$skipped++;
			continue;
		}

		$bahan_lama = max(0, (int) floor(persediaan_parse_angka(isset($cur->bahan_produksi) ? $cur->bahan_produksi : 0)));
		$total_10_lama = max(0, (int) floor(persediaan_parse_angka(isset($cur->total_10) ? $cur->total_10 : 0)));
		$hpp = persediaan_parse_angka(isset($cur->hpp) ? $cur->hpp : 0);

		$bahan_baru = $bahan_lama + $jumlah;
		$total_10_baru = max(0, $total_10_lama - $jumlah);
		$nilai = (int) floor($total_10_baru * $hpp);

		$update = array(
			'bahan_produksi' => (string) $bahan_baru,
			'total_10' => (string) $total_10_baru,
			'nilai_persediaan' => (string) $nilai,
		);
		if ($CI->db->field_exists('tuj', 'persediaan')) {
			$update['tuj'] = (string) $total_10_baru;
		}

		$CI->db->where('id', $pers_id);
		if ($CI->db->update('persediaan', $update)) {
			$flushed++;
			$with_bahan_ids[$pers_id] = 1;
		}
		$matched++;
	}

	return array(
		'matched' => $matched,
		'skipped' => $skipped,
		'flushed' => $flushed,
		'with_bahan' => count($with_bahan_ids),
	);
}

function persediaan_generate_recalculate_init_produksi_phase($CI, $ctx, &$state)
{
	if (empty($state['produksi_phase_inited'])) {
		$prep = persediaan_recalculate_prepare_produksi_bulan($CI, $ctx);
		$state['produksi_prepare'] = $prep;
		$state['produksi_phase_inited'] = 1;
		$state['produksi_accum'] = array();
	}
	if (!isset($state['produksi_accum']) || !is_array($state['produksi_accum'])) {
		$state['produksi_accum'] = array();
	}
}

function persediaan_generate_recalculate_proses_produksi_row($CI, $ctx, $queue_item, &$map, &$produksi_accum, &$penjualan_accum = null)
{
	$CI->load->helper('persediaan_display');

	$source_ids = isset($queue_item['source_ids']) && is_array($queue_item['source_ids']) ? $queue_item['source_ids'] : array();
	$id = (int) (isset($queue_item['id']) ? $queue_item['id'] : 0);

	if (!empty($queue_item['aggregated'])) {
		$row = (object) array(
			'id' => $id,
			'uuid_persediaan' => isset($queue_item['uuid_persediaan']) ? $queue_item['uuid_persediaan'] : '',
			'nama_barang_bahan' => isset($queue_item['nama_barang_bahan']) ? $queue_item['nama_barang_bahan'] : '',
			'satuan_bahan' => isset($queue_item['satuan_bahan']) ? $queue_item['satuan_bahan'] : '',
			'harga_satuan_bahan' => isset($queue_item['harga_satuan_bahan']) ? $queue_item['harga_satuan_bahan'] : '',
			'jumlah_bahan' => isset($queue_item['jumlah_bahan']) ? $queue_item['jumlah_bahan'] : 0,
			'nama_unit' => isset($queue_item['nama_unit']) ? $queue_item['nama_unit'] : '',
		);
	} else {
		$row = $CI->db->where('id', $id)->limit(1)->get('sys_unit_produk_bahan')->row();
	}

	if (!$row) {
		return array(
			'fase' => 'produksi',
			'aksi' => 'SKIP',
			'id_produksi_bahan' => !empty($source_ids) ? implode(',', $source_ids) : (string) $id,
			'namabarang' => isset($queue_item['nama_barang_bahan']) ? $queue_item['nama_barang_bahan'] : '',
			'keterangan' => 'Record bahan produksi tidak ditemukan',
		);
	}

	$jumlah = max(0, (int) floor(persediaan_parse_angka(isset($row->jumlah_bahan) ? $row->jumlah_bahan : 0)));
	$nama = isset($row->nama_barang_bahan) ? $row->nama_barang_bahan : '';
	$satuan = isset($row->satuan_bahan) ? $row->satuan_bahan : '';
	$harga = isset($row->harga_satuan_bahan) ? $row->harga_satuan_bahan : '';
	$spop = isset($queue_item['spop']) ? $queue_item['spop'] : persediaan_generate_recalculate_resolve_spop_produksi_bahan($CI, $row);
	$nama_unit = isset($row->nama_unit) ? trim((string) $row->nama_unit) : '';
	$id_tampil = !empty($source_ids) ? implode(',', $source_ids) : (string) $id;

	if ($jumlah <= 0) {
		return array(
			'fase' => 'produksi',
			'aksi' => 'SKIP',
			'id_produksi_bahan' => $id_tampil,
			'namabarang' => $nama,
			'satuan' => $satuan,
			'hpp' => $harga,
			'spop' => $spop,
			'jumlah_bahan' => '0',
			'keterangan' => 'Lewati: jumlah bahan 0',
		);
	}

	$pick = persediaan_generate_recalculate_find_persediaan_for_produksi_bahan($CI, $row, $map);
	if (!$pick) {
		return array(
			'fase' => 'produksi',
			'aksi' => 'TIDAK_COCOK',
			'id_produksi_bahan' => $id_tampil,
			'namabarang' => $nama,
			'satuan' => $satuan,
			'hpp' => $harga,
			'spop' => $spop,
			'jumlah_bahan' => (string) $jumlah,
			'nama_unit' => $nama_unit,
			'keterangan' => 'Tidak cocok di persediaan (nama+satuan+hpp+spop) untuk bahan produksi ini'
				. (count($source_ids) > 1 ? ' | agregasi ' . count($source_ids) . ' baris sys_unit_produk_bahan' : ''),
		);
	}

	$row_pers = persediaan_recalculate_map_row_fresh($map, $pick);
	if (!$row_pers) {
		$row_pers = $pick;
	}

	$pers_id = (int) $row_pers->id;
	if (!isset($produksi_accum[$pers_id])) {
		$fresh = $CI->db->where('id', $pers_id)->limit(1)->get('persediaan')->row();
		$bahan_base = 0;
		$total_base = 0;
		if ($fresh) {
			$bahan_base = max(0, (int) floor(persediaan_parse_angka(isset($fresh->bahan_produksi) ? $fresh->bahan_produksi : 0)));
			$total_base = max(0, (int) floor(persediaan_parse_angka(isset($fresh->total_10) ? $fresh->total_10 : 0)));
		} else {
			$total_base = max(0, (int) floor(persediaan_parse_angka(isset($row_pers->total_10) ? $row_pers->total_10 : 0)));
		}
		$produksi_accum[$pers_id] = array(
			'bahan_produksi' => $bahan_base,
			'total_10' => $total_base,
		);
	}

	$bahan_lama = (int) $produksi_accum[$pers_id]['bahan_produksi'];
	$bahan_baru = $bahan_lama + $jumlah;
	$produksi_accum[$pers_id]['bahan_produksi'] = $bahan_baru;

	$fresh_calc = $CI->db->where('id', $pers_id)->limit(1)->get('persediaan')->row();
	$sa = $fresh_calc ? max(0, (int) floor(persediaan_parse_angka(isset($fresh_calc->sa) ? $fresh_calc->sa : 0))) : max(0, (int) floor(persediaan_parse_angka(isset($row_pers->sa) ? $row_pers->sa : 0)));
	$beli = $fresh_calc ? max(0, (int) floor(persediaan_parse_angka(isset($fresh_calc->beli) ? $fresh_calc->beli : 0))) : max(0, (int) floor(persediaan_parse_angka(isset($row_pers->beli) ? $row_pers->beli : 0)));
	$penj = $fresh_calc ? max(0, (int) floor(persediaan_parse_angka(isset($fresh_calc->penjualan) ? $fresh_calc->penjualan : 0))) : max(0, (int) floor(persediaan_parse_angka(isset($row_pers->penjualan) ? $row_pers->penjualan : 0)));
	$pecah = $fresh_calc ? max(0, (int) floor(persediaan_parse_angka(isset($fresh_calc->pecah_satuan) ? $fresh_calc->pecah_satuan : 0))) : max(0, (int) floor(persediaan_parse_angka(isset($row_pers->pecah_satuan) ? $row_pers->pecah_satuan : 0)));
	$gross = $sa + $beli;
	$penj = min($penj, $gross);
	$sisa_setelah_penj = max(0, $gross - $penj);
	$pecah = min($pecah, $sisa_setelah_penj);
	$sisa_setelah_pecah = max(0, $sisa_setelah_penj - $pecah);
	$bahan_efektif = min($bahan_baru, $sisa_setelah_pecah);
	$total_10_baru = max(0, $gross - $penj - $pecah - $bahan_efektif);
	$produksi_accum[$pers_id]['total_10'] = $total_10_baru;

	$nilai = (int) floor($total_10_baru * persediaan_parse_angka(isset($row_pers->hpp) ? $row_pers->hpp : 0));
	$keterangan_check = persediaan_gen_recalc_check_total_10_from_values($sa, $beli, $penj, $pecah, $bahan_efektif, $total_10_baru);

	return array(
		'fase' => 'produksi',
		'aksi' => 'UPDATE_PRODUKSI',
		'id_produksi_bahan' => $id_tampil,
		'id_persediaan' => $pers_id,
		'namabarang' => $nama,
		'satuan' => $satuan,
		'hpp' => $harga,
		'spop' => $spop,
		'nama_unit' => $nama_unit,
		'jumlah_bahan' => (string) $jumlah,
		'bahan_produksi_lama' => (string) $bahan_lama,
		'bahan_produksi_baru' => (string) $bahan_baru,
		'total_10' => (string) $total_10_baru,
		'sisa_stock' => (string) $total_10_baru,
		'nilai_persediaan' => (string) $nilai,
		'keterangan' => 'bahan_produksi+=' . $jumlah . ' → total_10=(sa+beli)-(penj+pecah+prod)=' . $total_10_baru
			. (count($source_ids) > 1 ? ' | agregasi ' . count($source_ids) . ' baris sys_unit_produk_bahan' : ''),
		'keterangan_check' => $keterangan_check,
	);
}

/**
 * -------------------------------------------------------------------------
 * Fase 5 — Pecah satuan (tbl_pembelian_pecah_satuan → pecah_satuan & total_10)
 * -------------------------------------------------------------------------
 */
function persediaan_recalculate_undo_pecah_satuan_target_bulan($CI, $ctx)
{
	$CI->load->helper('persediaan_display');

	if (!$CI->db->table_exists('tbl_pembelian_pecah_satuan')) {
		return array('reverted' => 0);
	}

	$tanggal_beli = isset($ctx['tanggal_beli_target']) ? $ctx['tanggal_beli_target'] : $ctx['tanggal_beli'];
	$map = persediaan_recalculate_build_map_persediaan_bulan($CI, $tanggal_beli);

	$list = $CI->db->query(
		"SELECT * FROM `tbl_pembelian_pecah_satuan`
		WHERE DATE(COALESCE(NULLIF(TRIM(`proses_input`), ''), `tgl_po`)) >= ?
		AND DATE(COALESCE(NULLIF(TRIM(`proses_input`), ''), `tgl_po`)) <= ?
		ORDER BY `id` DESC",
		array($ctx['tgl_awal'], $ctx['tgl_akhir'])
	)->result();

	$reverted = 0;
	foreach ($list as $row_pecah) {
		$jumlah_baru = max(0, (int) floor(persediaan_parse_angka(
			isset($row_pecah->jumlah_barang_baru) ? $row_pecah->jumlah_barang_baru : 0
		)));
		if ($jumlah_baru <= 0) {
			$jumlah_baru = max(0, (int) floor(persediaan_parse_angka(isset($row_pecah->jumlah) ? $row_pecah->jumlah : 0)));
		}
		if ($jumlah_baru <= 0) {
			continue;
		}

		$pick = persediaan_generate_recalculate_find_persediaan_for_pecah_target($row_pecah, $map);
		if (!$pick) {
			continue;
		}

		$cur = $CI->db->where('id', (int) $pick->id)->limit(1)->get('persediaan')->row();
		if (!$cur) {
			continue;
		}

		$sa = max(0, (int) floor(persediaan_parse_angka(isset($cur->sa) ? $cur->sa : 0)) - $jumlah_baru);
		$total_10 = max(0, (int) floor(persediaan_parse_angka(isset($cur->total_10) ? $cur->total_10 : 0)) - $jumlah_baru);
		$hpp = persediaan_parse_angka(isset($cur->hpp) ? $cur->hpp : 0);
		$nilai = (int) floor($total_10 * $hpp);

		$update = array(
			'sa' => (string) $sa,
			'total_10' => (string) $total_10,
			'nilai_persediaan' => (string) $nilai,
		);
		if ($CI->db->field_exists('tuj', 'persediaan')) {
			$update['tuj'] = (string) $total_10;
		}

		$CI->db->where('id', (int) $cur->id);
		if ($CI->db->update('persediaan', $update)) {
			$reverted++;
		}
	}

	return array('reverted' => $reverted);
}

function persediaan_recalculate_reset_pecah_satuan_persediaan_bulan($CI, $tanggal_beli)
{
	$CI->load->helper('persediaan_display');

	if (!$CI->db->field_exists('pecah_satuan', 'persediaan')) {
		return array('record_direset' => 0, 'total_rows' => 0);
	}

	$rows = $CI->db->query(
		"SELECT `id`, `pecah_satuan`, `total_10`, `hpp` FROM `persediaan` WHERE `tanggal_beli` = ? ORDER BY `id` ASC",
		array($tanggal_beli)
	)->result();

	$updated = 0;
	foreach ($rows as $row) {
		$pecah = max(0, (int) floor(persediaan_parse_angka(isset($row->pecah_satuan) ? $row->pecah_satuan : 0)));
		if ($pecah <= 0) {
			continue;
		}

		$total_10 = max(0, (int) floor(persediaan_parse_angka(isset($row->total_10) ? $row->total_10 : 0)) + $pecah);
		$hpp = persediaan_parse_angka(isset($row->hpp) ? $row->hpp : 0);
		$nilai = (int) floor($total_10 * $hpp);

		$update = array(
			'pecah_satuan' => '0',
			'total_10' => (string) $total_10,
			'nilai_persediaan' => (string) $nilai,
		);
		if ($CI->db->field_exists('tuj', 'persediaan')) {
			$update['tuj'] = (string) $total_10;
		}

		$CI->db->where('id', (int) $row->id);
		if ($CI->db->update('persediaan', $update)) {
			$updated++;
		}
	}

	return array(
		'record_direset' => $updated,
		'total_rows' => count($rows),
	);
}

function persediaan_recalculate_prepare_pecah_satuan_bulan($CI, $ctx)
{
	$tanggal_beli = isset($ctx['tanggal_beli_target']) ? $ctx['tanggal_beli_target'] : $ctx['tanggal_beli'];
	if (trim((string) $tanggal_beli) === '') {
		return array('undo_target' => array('reverted' => 0), 'reset' => array('record_direset' => 0));
	}

	$undo = persediaan_recalculate_undo_pecah_satuan_target_bulan($CI, $ctx);
	$reset = persediaan_recalculate_reset_pecah_satuan_persediaan_bulan($CI, $tanggal_beli);

	return array(
		'undo_target' => $undo,
		'reset' => $reset,
		'tanggal_beli' => $tanggal_beli,
	);
}

function persediaan_generate_recalculate_find_persediaan_for_pecah_source($row_pecah, $map)
{
	if (empty($map) || empty($row_pecah)) {
		return null;
	}

	$nama = isset($row_pecah->uraian) ? $row_pecah->uraian : '';
	$satuan = isset($row_pecah->satuan) ? $row_pecah->satuan : '';
	$harga = isset($row_pecah->harga_satuan) ? $row_pecah->harga_satuan : '';

	$candidates = persediaan_generate_recalculate_kandidat_penjualan_persediaan($map, $nama, $satuan, $harga);
	if (empty($candidates)) {
		return null;
	}

	$ref = (object) array(
		'nama_barang' => $nama,
		'satuan' => $satuan,
		'harga_satuan' => $harga,
	);

	return persediaan_recalculate_pick_best_persediaan_row($candidates, $ref);
}

function persediaan_generate_recalculate_find_persediaan_for_pecah_target($row_pecah, $map)
{
	if (empty($map) || empty($row_pecah)) {
		return null;
	}

	$nama = isset($row_pecah->nama_barang_baru) ? $row_pecah->nama_barang_baru : '';
	$satuan = isset($row_pecah->satuan_barang_baru) ? $row_pecah->satuan_barang_baru : '';
	$harga = isset($row_pecah->harga_satuan_barang_baru) ? $row_pecah->harga_satuan_barang_baru : '';

	$candidates = persediaan_generate_recalculate_kandidat_penjualan_persediaan($map, $nama, $satuan, $harga);
	if (empty($candidates)) {
		return null;
	}

	$ref = (object) array(
		'nama_barang' => $nama,
		'satuan' => $satuan,
		'harga_satuan' => $harga,
	);

	return persediaan_recalculate_pick_best_persediaan_row($candidates, $ref);
}

function persediaan_generate_recalculate_build_pecah_satuan_queue($CI, $ctx)
{
	if (!$CI->db->table_exists('tbl_pembelian_pecah_satuan')) {
		return array();
	}

	$list = $CI->db->query(
		"SELECT `id`, `uraian`, `satuan`, `harga_satuan`, `jumlah`, `nama_barang_baru`, `satuan_barang_baru`, `harga_satuan_barang_baru`, `jumlah_barang_baru`
		FROM `tbl_pembelian_pecah_satuan`
		WHERE DATE(COALESCE(NULLIF(TRIM(`proses_input`), ''), `tgl_po`)) >= ?
		AND DATE(COALESCE(NULLIF(TRIM(`proses_input`), ''), `tgl_po`)) <= ?
		ORDER BY `uraian` ASC, `id` ASC",
		array($ctx['tgl_awal'], $ctx['tgl_akhir'])
	)->result();

	$queue = array();
	foreach ($list as $row) {
		$queue[] = array(
			'id' => (int) $row->id,
			'uraian' => isset($row->uraian) ? $row->uraian : '',
			'satuan' => isset($row->satuan) ? $row->satuan : '',
			'harga_satuan' => isset($row->harga_satuan) ? $row->harga_satuan : '',
			'jumlah' => isset($row->jumlah) ? $row->jumlah : 0,
			'nama_barang_baru' => isset($row->nama_barang_baru) ? $row->nama_barang_baru : '',
			'satuan_barang_baru' => isset($row->satuan_barang_baru) ? $row->satuan_barang_baru : '',
			'harga_satuan_barang_baru' => isset($row->harga_satuan_barang_baru) ? $row->harga_satuan_barang_baru : '',
			'jumlah_barang_baru' => isset($row->jumlah_barang_baru) ? $row->jumlah_barang_baru : 0,
		);
	}

	return $queue;
}

function persediaan_generate_recalculate_insert_persediaan_pecah_target($CI, $ctx, $row_pecah, $jumlah_baru, &$map)
{
	$CI->load->helper('persediaan_display');

	$tanggal_beli = isset($ctx['tanggal_beli_target']) ? $ctx['tanggal_beli_target'] : $ctx['tanggal_beli'];
	$nama = isset($row_pecah->nama_barang_baru) ? trim((string) $row_pecah->nama_barang_baru) : '';
	$satuan = isset($row_pecah->satuan_barang_baru) ? trim((string) $row_pecah->satuan_barang_baru) : '';
	$hpp = persediaan_parse_angka(isset($row_pecah->harga_satuan_barang_baru) ? $row_pecah->harga_satuan_barang_baru : 0);
	$kode = isset($row_pecah->kode_barang_baru) ? trim((string) $row_pecah->kode_barang_baru) : '';
	if ($kode === '' && $nama !== '') {
		$kode = strtoupper(substr(preg_replace('/\s+/', '', $nama), 0, 8));
	}

	$jumlah = max(0, (int) floor($jumlah_baru));
	$nilai = (int) floor($jumlah * $hpp);

	$insert = array(
		'tanggal' => date('Y-m-d H:i:s'),
		'tanggal_beli' => $tanggal_beli,
		'kode_barang' => $kode,
		'kode' => $kode,
		'namabarang' => $nama,
		'satuan' => $satuan,
		'hpp' => (string) $hpp,
		'sa' => (string) $jumlah,
		'beli' => '0',
		'spop' => 'pecahsatuan',
		'total_10' => (string) $jumlah,
		'nilai_persediaan' => (string) $nilai,
		'penjualan' => '0',
		'pecah_satuan' => '0',
	);
	if ($CI->db->field_exists('bahan_produksi', 'persediaan')) {
		$insert['bahan_produksi'] = '0';
	}
	if ($CI->db->field_exists('tuj', 'persediaan')) {
		$insert['tuj'] = (string) $jumlah;
	}
	if ($CI->db->field_exists('uuid_barang', 'persediaan')) {
		$insert['uuid_barang'] = md5(uniqid((string) $nama, true));
	}
	if ($CI->db->field_exists('uuid_persediaan', 'persediaan')) {
		$CI->db->set('uuid_persediaan', "replace(uuid(),'-','')", false);
	}

	$CI->db->insert('persediaan', $insert);
	$new_id = (int) $CI->db->insert_id();
	if ($new_id <= 0) {
		return null;
	}

	$row = $CI->db->where('id', $new_id)->limit(1)->get('persediaan')->row();
	return $row;
}

function persediaan_generate_recalculate_reapply_pecah_satuan_bulan($CI, $ctx)
{
	$CI->load->helper('persediaan_display');

	if (!$CI->db->table_exists('tbl_pembelian_pecah_satuan') || !$CI->db->field_exists('pecah_satuan', 'persediaan')) {
		return array('matched' => 0, 'skipped' => 0, 'flushed' => 0, 'target_insert' => 0);
	}

	persediaan_recalculate_prepare_pecah_satuan_bulan($CI, $ctx);

	$tanggal_beli = isset($ctx['tanggal_beli_target']) ? $ctx['tanggal_beli_target'] : $ctx['tanggal_beli'];
	$map = persediaan_recalculate_build_map_persediaan_bulan($CI, $tanggal_beli);

	$list = $CI->db->query(
		"SELECT * FROM `tbl_pembelian_pecah_satuan`
		WHERE DATE(COALESCE(NULLIF(TRIM(`proses_input`), ''), `tgl_po`)) >= ?
		AND DATE(COALESCE(NULLIF(TRIM(`proses_input`), ''), `tgl_po`)) <= ?
		ORDER BY `id` ASC",
		array($ctx['tgl_awal'], $ctx['tgl_akhir'])
	)->result();

	$matched = 0;
	$skipped = 0;
	$flushed = 0;
	$target_insert = 0;

	foreach ($list as $row_pecah) {
		$jumlah_pecah = max(0, (int) floor(persediaan_parse_angka(isset($row_pecah->jumlah) ? $row_pecah->jumlah : 0)));
		$jumlah_baru = max(0, (int) floor(persediaan_parse_angka(isset($row_pecah->jumlah_barang_baru) ? $row_pecah->jumlah_barang_baru : 0)));
		if ($jumlah_pecah <= 0 || $jumlah_baru <= 0) {
			$skipped++;
			continue;
		}

		$pick_src = persediaan_generate_recalculate_find_persediaan_for_pecah_source($row_pecah, $map);
		if (!$pick_src) {
			$skipped++;
			continue;
		}

		$src = $CI->db->where('id', (int) $pick_src->id)->limit(1)->get('persediaan')->row();
		if (!$src) {
			$skipped++;
			continue;
		}

		$pecah_lama = max(0, (int) floor(persediaan_parse_angka(isset($src->pecah_satuan) ? $src->pecah_satuan : 0)));
		$total_src_lama = max(0, (int) floor(persediaan_parse_angka(isset($src->total_10) ? $src->total_10 : 0)));
		$pecah_baru = $pecah_lama + $jumlah_pecah;
		$total_src_baru = max(0, $total_src_lama - $jumlah_pecah);
		$hpp_src = persediaan_parse_angka(isset($src->hpp) ? $src->hpp : 0);
		$nilai_src = (int) floor($total_src_baru * $hpp_src);

		$upd_src = array(
			'pecah_satuan' => (string) $pecah_baru,
			'total_10' => (string) $total_src_baru,
			'nilai_persediaan' => (string) $nilai_src,
		);
		if ($CI->db->field_exists('tuj', 'persediaan')) {
			$upd_src['tuj'] = (string) $total_src_baru;
		}
		$CI->db->where('id', (int) $src->id);
		$CI->db->update('persediaan', $upd_src);

		$pick_tgt = persediaan_generate_recalculate_find_persediaan_for_pecah_target($row_pecah, $map);
		if (!$pick_tgt) {
			$pick_tgt = persediaan_generate_recalculate_insert_persediaan_pecah_target($CI, $ctx, $row_pecah, $jumlah_baru, $map);
			if ($pick_tgt) {
				$target_insert++;
				$map = persediaan_recalculate_build_map_persediaan_bulan($CI, $tanggal_beli);
			}
		}

		if (!$pick_tgt) {
			$skipped++;
			continue;
		}

		$tgt = $CI->db->where('id', (int) $pick_tgt->id)->limit(1)->get('persediaan')->row();
		if (!$tgt) {
			$skipped++;
			continue;
		}

		$sa_lama = max(0, (int) floor(persediaan_parse_angka(isset($tgt->sa) ? $tgt->sa : 0)));
		$total_tgt_lama = max(0, (int) floor(persediaan_parse_angka(isset($tgt->total_10) ? $tgt->total_10 : 0)));
		$hpp_tgt = persediaan_parse_angka(isset($tgt->hpp) ? $tgt->hpp : 0);
		$sa_baru = $sa_lama + $jumlah_baru;
		$total_tgt_baru = $total_tgt_lama + $jumlah_baru;
		$nilai_tgt = (int) floor($total_tgt_baru * $hpp_tgt);

		$upd_tgt = array(
			'sa' => (string) $sa_baru,
			'total_10' => (string) $total_tgt_baru,
			'nilai_persediaan' => (string) $nilai_tgt,
		);
		if ($CI->db->field_exists('tuj', 'persediaan')) {
			$upd_tgt['tuj'] = (string) $total_tgt_baru;
		}
		$CI->db->where('id', (int) $tgt->id);
		$CI->db->update('persediaan', $upd_tgt);

		$matched++;
		$flushed++;
	}

	return array(
		'matched' => $matched,
		'skipped' => $skipped,
		'flushed' => $flushed,
		'target_insert' => $target_insert,
	);
}

function persediaan_recalculate_pecah_satuan_phase_once($CI, $ctx)
{
	if (!$CI->db->table_exists('tbl_pembelian_pecah_satuan') || !$CI->db->field_exists('pecah_satuan', 'persediaan')) {
		return array(
			'ok' => true,
			'batch_ok' => 0,
			'matched' => 0,
			'skipped' => 0,
			'flushed' => 0,
			'target_insert' => 0,
			'pesan' => '',
		);
	}

	$info = persediaan_generate_recalculate_reapply_pecah_satuan_bulan($CI, $ctx);
	$matched = (int) (isset($info['matched']) ? $info['matched'] : 0);
	$flushed = (int) (isset($info['flushed']) ? $info['flushed'] : 0);

	return array(
		'ok' => true,
		'batch_ok' => $flushed,
		'matched' => $matched,
		'skipped' => (int) (isset($info['skipped']) ? $info['skipped'] : 0),
		'flushed' => $flushed,
		'target_insert' => (int) (isset($info['target_insert']) ? $info['target_insert'] : 0),
		'pesan' => ' Pecah satuan: ' . $matched . ' baris tbl_pembelian_pecah_satuan cocok, flush ' . $flushed
			. ', insert target baru ' . (int) (isset($info['target_insert']) ? $info['target_insert'] : 0) . '.',
		'pecah_info' => $info,
	);
}

function persediaan_generate_recalculate_init_pecah_satuan_phase($CI, $ctx, &$state)
{
	if (empty($state['pecah_phase_inited'])) {
		$prep = persediaan_recalculate_prepare_pecah_satuan_bulan($CI, $ctx);
		$state['pecah_prepare'] = $prep;
		$state['pecah_phase_inited'] = 1;
		$state['pecah_accum'] = array();
	}
	if (!isset($state['pecah_accum']) || !is_array($state['pecah_accum'])) {
		$state['pecah_accum'] = array();
	}
}

function persediaan_generate_recalculate_proses_pecah_satuan_row($CI, $ctx, $queue_item, &$map, &$pecah_accum)
{
	$CI->load->helper('persediaan_display');
	$id = (int) $queue_item['id'];

	$row = $CI->db->where('id', $id)->limit(1)->get('tbl_pembelian_pecah_satuan')->row();
	if (!$row) {
		return array(
			'fase' => 'pecah_satuan',
			'aksi' => 'SKIP',
			'id_pecah_satuan' => $id,
			'namabarang' => isset($queue_item['uraian']) ? $queue_item['uraian'] : '',
			'keterangan' => 'Record pecah satuan tidak ditemukan',
		);
	}

	$jumlah_pecah = max(0, (int) floor(persediaan_parse_angka(isset($row->jumlah) ? $row->jumlah : 0)));
	$jumlah_baru = max(0, (int) floor(persediaan_parse_angka(isset($row->jumlah_barang_baru) ? $row->jumlah_barang_baru : 0)));
	$nama_src = isset($row->uraian) ? $row->uraian : '';
	$satuan_src = isset($row->satuan) ? $row->satuan : '';
	$harga_src = isset($row->harga_satuan) ? $row->harga_satuan : '';
	$nama_tgt = isset($row->nama_barang_baru) ? $row->nama_barang_baru : '';
	$satuan_tgt = isset($row->satuan_barang_baru) ? $row->satuan_barang_baru : '';
	$harga_tgt = isset($row->harga_satuan_barang_baru) ? $row->harga_satuan_barang_baru : '';

	if ($jumlah_pecah <= 0 || $jumlah_baru <= 0) {
		return array(
			'fase' => 'pecah_satuan',
			'aksi' => 'SKIP',
			'id_pecah_satuan' => $id,
			'namabarang' => $nama_src,
			'keterangan' => 'Lewati: jumlah pecah atau jumlah barang baru 0',
		);
	}

	$pick_src = persediaan_generate_recalculate_find_persediaan_for_pecah_source($row, $map);
	if (!$pick_src) {
		return array(
			'fase' => 'pecah_satuan',
			'aksi' => 'TIDAK_COCOK',
			'id_pecah_satuan' => $id,
			'namabarang' => $nama_src,
			'satuan' => $satuan_src,
			'hpp' => $harga_src,
			'jumlah_pecah' => (string) $jumlah_pecah,
			'nama_barang_baru' => $nama_tgt,
			'satuan_barang_baru' => $satuan_tgt,
			'hpp_barang_baru' => $harga_tgt,
			'jumlah_barang_baru' => (string) $jumlah_baru,
			'keterangan' => 'Sumber tidak cocok di persediaan (uraian+satuan+hpp)',
		);
	}

	$src_id = (int) $pick_src->id;
	if (!isset($pecah_accum['src'][$src_id])) {
		$fresh = $CI->db->where('id', $src_id)->limit(1)->get('persediaan')->row();
		$pecah_accum['src'][$src_id] = array(
			'pecah_satuan' => $fresh ? max(0, (int) floor(persediaan_parse_angka($fresh->pecah_satuan))) : 0,
			'total_10' => $fresh ? max(0, (int) floor(persediaan_parse_angka($fresh->total_10))) : 0,
		);
	}
	$pecah_lama = (int) $pecah_accum['src'][$src_id]['pecah_satuan'];
	$pecah_baru = $pecah_lama + $jumlah_pecah;
	$pecah_accum['src'][$src_id]['pecah_satuan'] = $pecah_baru;

	$fresh_src = $CI->db->where('id', $src_id)->limit(1)->get('persediaan')->row();
	$sa = $fresh_src ? max(0, (int) floor(persediaan_parse_angka(isset($fresh_src->sa) ? $fresh_src->sa : 0))) : 0;
	$beli = $fresh_src ? max(0, (int) floor(persediaan_parse_angka(isset($fresh_src->beli) ? $fresh_src->beli : 0))) : 0;
	$penj = $fresh_src ? max(0, (int) floor(persediaan_parse_angka(isset($fresh_src->penjualan) ? $fresh_src->penjualan : 0))) : 0;
	$produksi = $fresh_src ? max(0, (int) floor(persediaan_parse_angka(isset($fresh_src->bahan_produksi) ? $fresh_src->bahan_produksi : 0))) : 0;
	$gross = $sa + $beli;
	$penj = min($penj, $gross);
	$sisa_setelah_penj = max(0, $gross - $penj);
	$pecah_efektif = min($pecah_baru, $sisa_setelah_penj);
	$sisa_setelah_pecah = max(0, $sisa_setelah_penj - $pecah_efektif);
	$produksi = min($produksi, $sisa_setelah_pecah);
	$total_src_baru = max(0, $gross - $penj - $pecah_efektif - $produksi);
	$pecah_accum['src'][$src_id]['total_10'] = $total_src_baru;

	$keterangan_check = persediaan_gen_recalc_check_total_10_from_values($sa, $beli, $penj, $pecah_efektif, $produksi, $total_src_baru);

	$pick_tgt = persediaan_generate_recalculate_find_persediaan_for_pecah_target($row, $map);
	$tgt_id = $pick_tgt ? (int) $pick_tgt->id : 0;
	$sa_baru = '';
	$total_tgt_baru = '';
	if ($pick_tgt) {
		if (!isset($pecah_accum['tgt'][$tgt_id])) {
			$fresh_t = $CI->db->where('id', $tgt_id)->limit(1)->get('persediaan')->row();
			$pecah_accum['tgt'][$tgt_id] = array(
				'sa' => $fresh_t ? max(0, (int) floor(persediaan_parse_angka($fresh_t->sa))) : 0,
				'total_10' => $fresh_t ? max(0, (int) floor(persediaan_parse_angka($fresh_t->total_10))) : 0,
			);
		}
		$sa_baru = (string) ((int) $pecah_accum['tgt'][$tgt_id]['sa'] + $jumlah_baru);
		$total_tgt_baru = (string) ((int) $pecah_accum['tgt'][$tgt_id]['total_10'] + $jumlah_baru);
		$pecah_accum['tgt'][$tgt_id]['sa'] = (int) $sa_baru;
		$pecah_accum['tgt'][$tgt_id]['total_10'] = (int) $total_tgt_baru;
	}

	return array(
		'fase' => 'pecah_satuan',
		'aksi' => $pick_tgt ? 'UPDATE_PECAH' : 'UPDATE_PECAH_TANPA_TARGET',
		'id_pecah_satuan' => $id,
		'id_persediaan_sumber' => $src_id,
		'id_persediaan_target' => $tgt_id > 0 ? $tgt_id : '',
		'namabarang' => $nama_src,
		'satuan' => $satuan_src,
		'hpp' => $harga_src,
		'jumlah_pecah' => (string) $jumlah_pecah,
		'pecah_satuan_baru' => (string) $pecah_baru,
		'total_10_sumber' => (string) $total_src_baru,
		'nama_barang_baru' => $nama_tgt,
		'satuan_barang_baru' => $satuan_tgt,
		'hpp_barang_baru' => $harga_tgt,
		'jumlah_barang_baru' => (string) $jumlah_baru,
		'sa_target' => $sa_baru,
		'total_10_target' => $total_tgt_baru,
		'keterangan' => 'sumber pecah_satuan+=' . $jumlah_pecah . ', total_10=(sa+beli)-(penj+pecah+prod)=' . $total_src_baru
			. ($pick_tgt ? '; target sa/total_10+=' . $jumlah_baru : '; target belum ada di persediaan'),
		'keterangan_check' => $keterangan_check,
	);
}

function persediaan_generate_recalculate_start_pecah_satuan_phase($CI, $bulan, $limit, &$state, $state_key, $ctx, $count_sumber, $carry_items)
{
	$state['phase'] = 'pecah_satuan';
	persediaan_generate_recalculate_init_pecah_satuan_phase($CI, $ctx, $state);
	persediaan_generate_recalc_clear_queue_cache($state);
	unset($state['persediaan_map_cache'], $state['persediaan_map_cache_tgl']);
	$CI->session->set_userdata($state_key, $state);

	$total_pecah = count(persediaan_generate_recalculate_get_pecah_satuan_queue($state, $CI, $ctx));
	if ($total_pecah === 0) {
		$state['keluar_phases_done'] = 1;
		$CI->session->set_userdata($state_key, $state);
		$carry_items['keluar_finalize'] = isset($state['keluar_finalize']) ? $state['keluar_finalize'] : array();
		return persediaan_generate_recalculate_finish_all(
			$CI, $bulan, $ctx, $count_sumber, $state, $state_key, $carry_items
		);
	}

	return persediaan_generate_recalculate_phase_transition_response(
		$CI,
		$state,
		$state_key,
		$ctx,
		$carry_items,
		'pecah_satuan',
		$total_pecah,
		'Lanjut fase pecah satuan: 0 / ' . $total_pecah . ' baris'
	);
}

/**
 * Mode debug: hentikan proses setelah fase generate (belum pembelian/recalculate).
 */
function persediaan_generate_recalculate_stop_after_generate()
{
	return false;
}

/**
 * Hentikan setelah fase pembelian (belum penjualan/produksi/pecah satuan).
 */
function persediaan_generate_recalculate_stop_after_pembelian()
{
	return false;
}

/**
 * Angka untuk pencocokan hpp/harga_satuan: hanya digit (tanpa titik/koma pemisah ribuan).
 */
function persediaan_recalculate_angka_match_key($value)
{
	$digits = preg_replace('/\D/', '', trim((string) $value));
	if ($digits === '') {
		return '0';
	}

	return (string) (int) $digits;
}

/**
 * Kumpulkan data bermasalah / tidak di-generate untuk history (nilai minus, skip, verifikasi gagal).
 */
function persediaan_generate_recalculate_build_masalah_generate($CI, $ctx, $verifikasi_items = array(), $generated_at = '')
{
	$CI->load->helper('persediaan_display');

	$tanggal_sumber = $ctx['tanggal_beli_sumber'];
	$generated_at = trim((string) $generated_at);
	if ($generated_at === '') {
		$generated_at = date('Y-m-d H:i:s');
	}

	$rows_sumber = $CI->db->query(
		"SELECT * FROM `persediaan` WHERE `tanggal_beli` = ? ORDER BY `id` ASC",
		array($tanggal_sumber)
	)->result();

	$items = array();
	$stats = array(
		'negatif' => 0,
		'skip_total10' => 0,
		'verifikasi_beda' => 0,
		'verifikasi_tidak_ada_target' => 0,
		'verifikasi_target_ekstra' => 0,
		'total' => 0,
	);

	foreach ($rows_sumber as $row_sumber) {
		$total10 = persediaan_generate_recalculate_sumber_total10_field_val($row_sumber);
		if ($total10 < 0) {
			$stats['negatif']++;
			$items[] = array(
				'fase' => 'generate_masalah',
				'status' => 'NEGATIF',
				'waktu_generate' => $generated_at,
				'id_sumber' => (int) $row_sumber->id,
				'id_target' => 0,
				'namabarang' => persediaan_recalculate_sanitize_nama_persediaan(isset($row_sumber->namabarang) ? $row_sumber->namabarang : ''),
				'satuan' => isset($row_sumber->satuan) ? $row_sumber->satuan : '',
				'hpp' => isset($row_sumber->hpp) ? $row_sumber->hpp : '',
				'spop' => isset($row_sumber->spop) ? $row_sumber->spop : '',
				'total_10_sumber' => (string) $total10,
				'keterangan' => 'total_10 minus — tidak di-copy ke bulan target',
			);
		} elseif ($total10 <= 0) {
			$stats['skip_total10']++;
			$items[] = array(
				'fase' => 'generate_masalah',
				'status' => 'SKIP',
				'waktu_generate' => $generated_at,
				'id_sumber' => (int) $row_sumber->id,
				'id_target' => 0,
				'namabarang' => persediaan_recalculate_sanitize_nama_persediaan(isset($row_sumber->namabarang) ? $row_sumber->namabarang : ''),
				'satuan' => isset($row_sumber->satuan) ? $row_sumber->satuan : '',
				'hpp' => isset($row_sumber->hpp) ? $row_sumber->hpp : '',
				'spop' => isset($row_sumber->spop) ? $row_sumber->spop : '',
				'total_10_sumber' => isset($row_sumber->total_10) ? (string) $row_sumber->total_10 : '0',
				'keterangan' => 'total_10 <= 0 / kosong — tidak di-copy ke bulan target',
			);
		}
	}

	if (is_array($verifikasi_items)) {
		foreach ($verifikasi_items as $v) {
			if (!is_array($v)) {
				continue;
			}
			$st = isset($v['status']) ? (string) $v['status'] : '';
			if ($st === 'BEDA') {
				$stats['verifikasi_beda']++;
			} elseif ($st === 'TIDAK_ADA_TARGET') {
				$stats['verifikasi_tidak_ada_target']++;
			} elseif ($st === 'TARGET_EKSTRA') {
				$stats['verifikasi_target_ekstra']++;
			} else {
				continue;
			}
			$items[] = array_merge($v, array(
				'fase' => 'generate_masalah',
				'waktu_generate' => $generated_at,
				'total_10_sumber' => isset($v['total_10_field_sumber']) ? $v['total_10_field_sumber'] : '',
				'keterangan' => isset($v['keterangan']) ? $v['keterangan'] : $st,
			));
		}
	}

	$stats['total'] = count($items);

	return array(
		'items' => $items,
		'stats' => $stats,
		'generated_at' => $generated_at,
	);
}

/**
 * Bandingkan record bulan target hasil generate dengan kolom total_10 bulan sumber (> 0).
 */
function persediaan_generate_recalculate_build_verifikasi_generate($CI, $ctx)
{
	$CI->load->helper('persediaan_display');

	$tanggal_sumber = $ctx['tanggal_beli_sumber'];
	$tanggal_target = $ctx['tanggal_beli_target'];
	$simple_copy = persediaan_generate_recalculate_stop_after_generate();

	$rows_sumber = $CI->db->query(
		"SELECT * FROM `persediaan` WHERE `tanggal_beli` = ? ORDER BY `namabarang` ASC, `id` ASC",
		array($tanggal_sumber)
	)->result();

	$rows_target = $CI->db->query(
		"SELECT * FROM `persediaan` WHERE `tanggal_beli` = ? ORDER BY `namabarang` ASC, `id` ASC",
		array($tanggal_target)
	)->result();

	$map_target_by_src = array();
	if ($simple_copy) {
		foreach ($rows_target as $row_target) {
			$lama = isset($row_target->uuid_persediaan_lama) ? trim((string) $row_target->uuid_persediaan_lama) : '';
			if (preg_match('/^gen_src:(\d+)$/', $lama, $m)) {
				$map_target_by_src[(int) $m[1]] = $row_target;
			}
		}
	} else {
		$map_target_by_src = null;
	}

	$map_target = ($simple_copy && !empty($map_target_by_src))
		? array()
		: persediaan_recalculate_build_map_persediaan_bulan($CI, $tanggal_target);

	$matched_target_ids = array();
	$items = array();
	$stats = array(
		'cocok' => 0,
		'beda' => 0,
		'tidak_ada_target' => 0,
		'sumber_skip' => 0,
	);

	foreach ($rows_sumber as $row_sumber) {
		if (!persediaan_generate_recalculate_sumber_layak_generate($row_sumber)) {
			$stats['sumber_skip']++;
			continue;
		}

		$sa_sumber = (int) floor(persediaan_generate_recalculate_sumber_total10_field_val($row_sumber));
		$total_10_field_sumber = $sa_sumber;

		$row_target = null;
		if ($simple_copy && is_array($map_target_by_src)) {
			$id_src = (int) $row_sumber->id;
			$row_target = isset($map_target_by_src[$id_src]) ? $map_target_by_src[$id_src] : null;
		} else {
			$row_target = persediaan_generate_recalculate_find_target_in_map($row_sumber, $map_target, $sa_sumber);
		}

		$id_target = 0;
		$sa_target = 0;
		$beli_target = 0;
		$penjualan_target = 0;
		$total_10_target = 0;
		$status = 'TIDAK_ADA_TARGET';
		$keterangan = 'Record sumber layak generate tidak ditemukan di bulan target';

		if ($row_target) {
			$id_target = (int) $row_target->id;
			$matched_target_ids[$id_target] = true;
			$sa_target = max(0, (int) floor(persediaan_parse_angka(isset($row_target->sa) ? $row_target->sa : 0)));
			$beli_target = max(0, (int) floor(persediaan_parse_angka(isset($row_target->beli) ? $row_target->beli : 0)));
			$penjualan_target = max(0, (int) floor(persediaan_parse_angka(isset($row_target->penjualan) ? $row_target->penjualan : 0)));
			$total_10_target = max(0, (int) floor(persediaan_parse_angka(isset($row_target->total_10) ? $row_target->total_10 : 0)));

			$cocok = ($sa_target === (int) $sa_sumber)
				&& ($beli_target === 0)
				&& ($penjualan_target === 0)
				&& ($total_10_target === (int) $sa_sumber);

			if ($cocok) {
				$status = 'COCOK';
				$keterangan = 'SA target = total_10 sumber; beli=0; penjualan=0; total_10=SA';
				$stats['cocok']++;
			} else {
				$status = 'BEDA';
				$beda = array();
				if ($sa_target !== (int) $sa_sumber) {
					$beda[] = 'SA target ' . $sa_target . ' ≠ total_10 sumber ' . (int) $sa_sumber;
				}
				if ($beli_target !== 0) {
					$beda[] = 'beli=' . $beli_target . ' (harus 0)';
				}
				if ($penjualan_target !== 0) {
					$beda[] = 'penjualan=' . $penjualan_target . ' (harus 0)';
				}
				if ($total_10_target !== (int) $sa_sumber) {
					$beda[] = 'total_10=' . $total_10_target . ' (harus ' . (int) $sa_sumber . ')';
				}
				$keterangan = implode('; ', $beda);
				$stats['beda']++;
			}
		} else {
			$stats['tidak_ada_target']++;
		}

		$items[] = array(
			'fase' => 'verifikasi_generate',
			'status' => $status,
			'id_sumber' => (int) $row_sumber->id,
			'id_target' => $id_target,
			'namabarang' => persediaan_recalculate_sanitize_nama_persediaan(isset($row_sumber->namabarang) ? $row_sumber->namabarang : ''),
			'satuan' => isset($row_sumber->satuan) ? $row_sumber->satuan : '',
			'hpp' => isset($row_sumber->hpp) ? $row_sumber->hpp : '',
			'spop' => isset($row_sumber->spop) ? $row_sumber->spop : '',
			'uuid_barang' => isset($row_sumber->uuid_barang) ? $row_sumber->uuid_barang : '',
			'sa_sumber' => (string) (int) $sa_sumber,
			'total_10_field_sumber' => (string) $total_10_field_sumber,
			'sa_target' => (string) $sa_target,
			'beli_target' => (string) $beli_target,
			'penjualan_target' => (string) $penjualan_target,
			'total_10_target' => (string) $total_10_target,
			'keterangan' => $keterangan,
		);
	}

	$target_ekstra = array();
	foreach ($rows_target as $row_target) {
		$id_t = (int) $row_target->id;
		if (!empty($matched_target_ids[$id_t])) {
			continue;
		}
		$target_ekstra[] = array(
			'fase' => 'verifikasi_generate',
			'status' => 'TARGET_EKSTRA',
			'id_sumber' => 0,
			'id_target' => $id_t,
			'namabarang' => persediaan_recalculate_sanitize_nama_persediaan(isset($row_target->namabarang) ? $row_target->namabarang : ''),
			'satuan' => isset($row_target->satuan) ? $row_target->satuan : '',
			'hpp' => isset($row_target->hpp) ? $row_target->hpp : '',
			'spop' => isset($row_target->spop) ? $row_target->spop : '',
			'uuid_barang' => isset($row_target->uuid_barang) ? $row_target->uuid_barang : '',
			'sa_sumber' => '',
			'total_10_field_sumber' => '',
			'sa_target' => (string) max(0, (int) floor(persediaan_parse_angka(isset($row_target->sa) ? $row_target->sa : 0))),
			'beli_target' => (string) max(0, (int) floor(persediaan_parse_angka(isset($row_target->beli) ? $row_target->beli : 0))),
			'penjualan_target' => (string) max(0, (int) floor(persediaan_parse_angka(isset($row_target->penjualan) ? $row_target->penjualan : 0))),
			'total_10_target' => (string) max(0, (int) floor(persediaan_parse_angka(isset($row_target->total_10) ? $row_target->total_10 : 0))),
			'keterangan' => 'Baris di bulan target tanpa pasangan sumber — tidak seharusnya ada setelah salin 1:1',
		);
	}

	$stats['target_ekstra'] = count($target_ekstra);
	$stats['total_sumber_layak'] = count($items);
	$stats['total_target'] = count($rows_target);

	return array(
		'items' => array_merge($items, $target_ekstra),
		'stats' => $stats,
	);
}

/**
 * Selesaikan proses hanya pada fase generate — tanpa pembelian/penjualan/produksi/pecah.
 */
function persediaan_generate_recalculate_finish_generate_only($CI, $bulan, $ctx, $count_sumber, &$state, $state_key, $batch_items)
{
	$verifikasi = persediaan_generate_recalculate_build_verifikasi_generate($CI, $ctx);
	$ver_stats = isset($verifikasi['stats']) ? $verifikasi['stats'] : array();
	$generated_at = date('Y-m-d H:i:s');
	$masalah = persediaan_generate_recalculate_build_masalah_generate(
		$CI,
		$ctx,
		isset($verifikasi['items']) ? $verifikasi['items'] : array(),
		$generated_at
	);
	$masalah_stats = isset($masalah['stats']) ? $masalah['stats'] : array();

	$summary_done = persediaan_generate_recalculate_build_summary($CI, $bulan, $ctx, $count_sumber, $state);
	$summary_done['generate_only'] = 1;
	$summary_done['generated_at'] = $generated_at;
	$summary_done['verifikasi_cocok'] = (int) (isset($ver_stats['cocok']) ? $ver_stats['cocok'] : 0);
	$summary_done['verifikasi_beda'] = (int) (isset($ver_stats['beda']) ? $ver_stats['beda'] : 0);
	$summary_done['verifikasi_tidak_ada_target'] = (int) (isset($ver_stats['tidak_ada_target']) ? $ver_stats['tidak_ada_target'] : 0);
	$summary_done['verifikasi_target_ekstra'] = (int) (isset($ver_stats['target_ekstra']) ? $ver_stats['target_ekstra'] : 0);
	$summary_done['total_sumber_layak'] = (int) (isset($ver_stats['total_sumber_layak']) ? $ver_stats['total_sumber_layak'] : 0);
	$summary_done['total_target'] = (int) (isset($ver_stats['total_target']) ? $ver_stats['total_target'] : 0);
	$summary_done['masalah_negatif'] = (int) (isset($masalah_stats['negatif']) ? $masalah_stats['negatif'] : 0);
	$summary_done['masalah_skip_total10'] = (int) (isset($masalah_stats['skip_total10']) ? $masalah_stats['skip_total10'] : 0);
	$summary_done['masalah_total'] = (int) (isset($masalah_stats['total']) ? $masalah_stats['total'] : 0);

	if (!empty($state['log_id']) && !empty($masalah['items']) && persediaan_gen_recalc_table_exists($CI)) {
		if (!isset($state['hist_nomor']) || !is_array($state['hist_nomor'])) {
			$state['hist_nomor'] = persediaan_gen_recalc_hist_nomor_init();
		}
		persediaan_gen_recalc_history_append_items($CI, $state['log_id'], 'generate_masalah', $masalah['items'], $state['hist_nomor']);
	}

	persediaan_gen_recalc_batch_finish_history($CI, $state, $summary_done);
	persediaan_history_generate_finish_from_batch($CI, $state, $bulan, $summary_done);
	$CI->session->unset_userdata($state_key);

	$items = is_array($batch_items) ? $batch_items : array();
	$pesan_ver = ' Verifikasi: cocok ' . (int) $summary_done['verifikasi_cocok']
		. ', beda ' . (int) $summary_done['verifikasi_beda']
		. ', tidak ada di target ' . (int) $summary_done['verifikasi_tidak_ada_target']
		. ', target ekstra ' . (int) $summary_done['verifikasi_target_ekstra'] . '.';
	$pesan_masalah = '';
	if ((int) $summary_done['masalah_total'] > 0) {
		$pesan_masalah = ' Data bermasalah/tidak di-generate: ' . (int) $summary_done['masalah_total']
			. ' (minus: ' . (int) $summary_done['masalah_negatif']
			. ', skip total_10≤0: ' . (int) $summary_done['masalah_skip_total10'] . ').';
	}

	return array_merge(array(
		'ok' => true,
		'phase' => 'generate',
		'done' => true,
		'generate_only' => true,
		'offset_selesai' => 0,
		'total_phase' => 0,
		'stats' => $state['stats'],
		'summary' => $summary_done,
		'history_saved' => !empty($state['log_id']),
		'hapus_sa_total10_nol' => (int) (isset($state['hapus_nol_target']) ? $state['hapus_nol_target'] : 0),
		'refresh_persediaan' => true,
		'items_generate_verifikasi' => isset($verifikasi['items']) ? $verifikasi['items'] : array(),
		'items_generate_masalah' => isset($masalah['items']) ? $masalah['items'] : array(),
		'has_masalah' => ((int) $summary_done['masalah_total'] > 0)
			|| ((int) $summary_done['verifikasi_beda'] > 0)
			|| ((int) $summary_done['verifikasi_tidak_ada_target'] > 0)
			|| ((int) $summary_done['verifikasi_target_ekstra'] > 0),
		'pesan' => 'Selesai fase generate: salin 1:1 dari bulan sumber (kolom total_10 > 0) ke bulan target.'
			. ' Beli=0, penjualan=0 di target. Fase pembelian/recalculate belum dijalankan.'
			. $pesan_ver
			. $pesan_masalah,
	), $items);
}

function persediaan_generate_recalculate_build_summary($CI, $bulan, $ctx, $count_sumber, $state)
{
	$total_pembelian = count(persediaan_generate_recalculate_build_pembelian_queue($CI, $ctx));
	$pemb_stats = persediaan_generate_recalculate_pembelian_queue_stats_get();
	$total_unit_produk = count(persediaan_generate_recalculate_build_unit_produk_queue($CI, $ctx));
	$total_penjualan = count(persediaan_generate_recalculate_build_penjualan_queue($CI, $ctx));
	$total_produksi = count(persediaan_generate_recalculate_build_produksi_queue($CI, $ctx));
	$total_pecah_satuan = count(persediaan_generate_recalculate_build_pecah_satuan_queue($CI, $ctx));

	return array(
		'bulan' => $bulan,
		'bulan_label' => $ctx['bulan_label'],
		'bulan_sumber_label' => $ctx['bulan_sumber_label'],
		'total_sumber' => $count_sumber,
		'total_pembelian' => $total_pembelian,
		'pembelian_db_rows' => (int) (isset($pemb_stats['db_rows_jumlah_positif']) ? $pemb_stats['db_rows_jumlah_positif'] : $total_pembelian),
		'pembelian_db_rows_all' => (int) (isset($pemb_stats['db_rows_all']) ? $pemb_stats['db_rows_all'] : 0),
		'pembelian_merged_rows' => (int) (isset($pemb_stats['merged_rows']) ? $pemb_stats['merged_rows'] : 0),
		'pembelian_skipped_jumlah_nol' => (int) (isset($pemb_stats['skipped_jumlah_nol']) ? $pemb_stats['skipped_jumlah_nol'] : 0),
		'total_unit_produk' => $total_unit_produk,
		'total_penjualan' => $total_penjualan,
		'total_produksi' => $total_produksi,
		'total_pecah_satuan' => $total_pecah_satuan,
		'generate_insert' => (int) $state['stats']['generate_insert'],
		'generate_update' => (int) $state['stats']['generate_update'],
		'generate_skip' => (int) (isset($state['stats']['generate_skip']) ? $state['stats']['generate_skip'] : 0),
		'pembelian_update' => (int) $state['stats']['pembelian_update'],
		'pembelian_insert' => (int) $state['stats']['pembelian_insert'],
		'pembelian_gagal' => (int) $state['stats']['pembelian_gagal'],
		'penjualan_update' => (int) (isset($state['stats']['penjualan_update']) ? $state['stats']['penjualan_update'] : 0),
		'penjualan_gagal' => (int) (isset($state['stats']['penjualan_gagal']) ? $state['stats']['penjualan_gagal'] : 0),
		'penjualan_tidak_cocok' => (int) (isset($state['stats']['penjualan_tidak_cocok']) ? $state['stats']['penjualan_tidak_cocok'] : 0),
		'penjualan_skip' => (int) (isset($state['stats']['penjualan_skip']) ? $state['stats']['penjualan_skip'] : 0),
		'produksi_update' => (int) (isset($state['stats']['produksi_update']) ? $state['stats']['produksi_update'] : 0),
		'produksi_gagal' => (int) (isset($state['stats']['produksi_gagal']) ? $state['stats']['produksi_gagal'] : 0),
		'produksi_tidak_cocok' => (int) (isset($state['stats']['produksi_tidak_cocok']) ? $state['stats']['produksi_tidak_cocok'] : 0),
		'produksi_skip' => (int) (isset($state['stats']['produksi_skip']) ? $state['stats']['produksi_skip'] : 0),
		'unit_produk_update' => (int) (isset($state['stats']['unit_produk_update']) ? $state['stats']['unit_produk_update'] : 0),
		'unit_produk_insert' => (int) (isset($state['stats']['unit_produk_insert']) ? $state['stats']['unit_produk_insert'] : 0),
		'unit_produk_gagal' => (int) (isset($state['stats']['unit_produk_gagal']) ? $state['stats']['unit_produk_gagal'] : 0),
		'unit_produk_skip' => (int) (isset($state['stats']['unit_produk_skip']) ? $state['stats']['unit_produk_skip'] : 0),
		'pecah_update' => (int) (isset($state['stats']['pecah_update']) ? $state['stats']['pecah_update'] : 0),
		'pecah_gagal' => (int) (isset($state['stats']['pecah_gagal']) ? $state['stats']['pecah_gagal'] : 0),
		'pecah_tidak_cocok' => (int) (isset($state['stats']['pecah_tidak_cocok']) ? $state['stats']['pecah_tidak_cocok'] : 0),
		'pecah_skip' => (int) (isset($state['stats']['pecah_skip']) ? $state['stats']['pecah_skip'] : 0),
		'cleanup_spop_kosong' => (int) (isset($state['cleanup_spop_kosong']['deleted']) ? $state['cleanup_spop_kosong']['deleted'] : 0),
		'cleanup_spop_kosong_grup' => (int) (isset($state['cleanup_spop_kosong']['groups']) ? $state['cleanup_spop_kosong']['groups'] : 0),
		'cleanup_duplikat_spop' => (int) (isset($state['cleanup_duplikat_spop']['deleted']) ? $state['cleanup_duplikat_spop']['deleted'] : 0),
		'cleanup_duplikat_spop_grup' => (int) (isset($state['cleanup_duplikat_spop']['groups']) ? $state['cleanup_duplikat_spop']['groups'] : 0),
		'reset_target' => (int) (isset($state['reset_target']) ? $state['reset_target'] : 0),
		'target_kosong_verified' => (int) (isset($state['target_kosong_verified']) ? $state['target_kosong_verified'] : 0),
		'tanggal_klik_generate' => isset($state['tanggal_klik_generate']) ? $state['tanggal_klik_generate'] : '',
	);
}

/**
 * Pastikan nilai_persediaan = total_10 × hpp untuk semua baris bulan target.
 */
function persediaan_generate_recalculate_refresh_nilai_persediaan_bulan($CI, $tanggal_beli)
{
	$CI->load->helper('persediaan_display');

	$rows = $CI->db->query(
		"SELECT `id`, `total_10`, `hpp` FROM `persediaan` WHERE `tanggal_beli` = ? ORDER BY `id` ASC",
		array($tanggal_beli)
	)->result();

	$updated = 0;
	foreach ($rows as $row) {
		$total_10 = max(0, (int) floor(persediaan_parse_angka(isset($row->total_10) ? $row->total_10 : 0)));
		$hpp = persediaan_parse_angka(isset($row->hpp) ? $row->hpp : 0);
		$nilai = (int) floor($total_10 * $hpp);

		$CI->db->where('id', (int) $row->id);
		if ($CI->db->update('persediaan', array('nilai_persediaan' => (string) $nilai))) {
			$updated++;
		}
	}

	return array(
		'updated' => $updated,
		'total_rows' => count($rows),
	);
}

function persediaan_generate_recalculate_finish_all($CI, $bulan, $ctx, $count_sumber, &$state, $state_key, $batch_items)
{
	$pembelian_info = null;
	$penjualan_info = null;
	$produksi_info = null;
	$pecah_info = null;
	$nilai_info = null;

	if ($CI->db->table_exists('tbl_pembelian') || $CI->db->table_exists('tbl_pembelian_jasa')) {
		$pembelian_info = persediaan_generate_recalculate_reapply_pembelian_bulan($CI, $ctx);
	}
	if (!empty($state['keluar_finalized']) && isset($state['keluar_finalize'])) {
		$keluar = $state['keluar_finalize'];
		$penjualan_info = array(
			'matched' => isset($keluar['with_penjualan']) ? (int) $keluar['with_penjualan'] : 0,
			'with_penjualan' => isset($keluar['with_penjualan']) ? (int) $keluar['with_penjualan'] : 0,
			'flushed' => isset($keluar['flushed']) ? (int) $keluar['flushed'] : 0,
			'keluar_finalized' => 1,
			'with_pecah' => isset($keluar['with_pecah']) ? (int) $keluar['with_pecah'] : 0,
			'with_produksi' => isset($keluar['with_produksi']) ? (int) $keluar['with_produksi'] : 0,
		);
		$pecah_tgt = isset($keluar['pecah_targets']) ? $keluar['pecah_targets'] : array();
		$produksi_info = array(
			'matched' => isset($keluar['with_produksi']) ? (int) $keluar['with_produksi'] : 0,
			'with_bahan' => isset($keluar['with_produksi']) ? (int) $keluar['with_produksi'] : 0,
			'flushed' => isset($keluar['updated']) ? (int) $keluar['updated'] : 0,
			'keluar_finalized' => 1,
		);
		$pecah_info = array(
			'matched' => isset($keluar['with_pecah']) ? (int) $keluar['with_pecah'] : 0,
			'flushed' => isset($keluar['updated']) ? (int) $keluar['updated'] : 0,
			'target_insert' => isset($pecah_tgt['target_insert']) ? (int) $pecah_tgt['target_insert'] : 0,
			'keluar_finalized' => 1,
		);
	} else {
		if ($CI->db->table_exists('tbl_penjualan')) {
			$penjualan_info = persediaan_generate_recalculate_reapply_penjualan_bulan($CI, $ctx);
		}
		if ($CI->db->table_exists('sys_unit_produk_bahan')) {
			$produksi_info = persediaan_generate_recalculate_reapply_produksi_bulan($CI, $ctx);
		}
		if ($CI->db->table_exists('tbl_pembelian_pecah_satuan')) {
			$pecah_info = persediaan_generate_recalculate_reapply_pecah_satuan_bulan($CI, $ctx);
		}
	}
	if (!empty($state['penjualan_phase_inited']) || !empty($state['produksi_phase_inited']) || !empty($state['pecah_phase_inited']) || $penjualan_info || $produksi_info || $pecah_info) {
		$nilai_info = persediaan_generate_recalculate_refresh_nilai_persediaan_bulan($CI, $ctx['tanggal_beli_target']);
	}

	$hapus_akhir = persediaan_generate_recalculate_hapus_baris_nol_bulan_target($CI, $ctx['tanggal_beli_target']);
	if (!isset($state['hapus_nol_target'])) {
		$state['hapus_nol_target'] = 0;
	}
	$state['hapus_nol_target'] += $hapus_akhir;
	persediaan_generate_recalculate_merge_cleanup_spop_state(
		$state,
		persediaan_recalculate_cleanup_duplikat_spop_kosong_beli_nol($CI, $ctx['tanggal_beli_target'])
	);
	persediaan_generate_recalculate_merge_cleanup_duplikat_spop_state(
		$state,
		persediaan_recalculate_cleanup_duplikat_spop_sama_bulan($CI, $ctx['tanggal_beli_target'])
	);

	$summary_done = persediaan_generate_recalculate_build_summary($CI, $bulan, $ctx, $count_sumber, $state);
	$items = is_array($batch_items) ? $batch_items : array();
	$extra_result_tables = persediaan_gen_recalc_build_result_extra_tables($CI, $ctx, $items);
	$summary_done['penjualan_uuid_orphan_count'] = (int) (isset($extra_result_tables['penjualan_uuid_orphan_count'])
		? $extra_result_tables['penjualan_uuid_orphan_count']
		: 0);
	persediaan_gen_recalc_batch_finish_history($CI, $state, $summary_done);
	persediaan_history_generate_finish_from_batch($CI, $state, $bulan, $summary_done);
	$CI->session->unset_userdata($state_key);

	$pesan_penj = '';
	if (is_array($penjualan_info)) {
		$pesan_penj = ' Penjualan: ' . (int) (isset($penjualan_info['matched']) ? $penjualan_info['matched'] : 0)
			. ' baris tbl_penjualan cocok, ' . (int) (isset($penjualan_info['with_penjualan']) ? $penjualan_info['with_penjualan'] : 0)
			. ' record persediaan penjualan>0, flush ' . (int) (isset($penjualan_info['flushed']) ? $penjualan_info['flushed'] : 0);
		if (!empty($penjualan_info['overstock_prevented'])) {
			$pesan_penj .= ', stok tidak cukup (tidak dialokasi): ' . (int) $penjualan_info['overstock_prevented'];
		}
		if (!empty($penjualan_info['reconcile']['fixed_penjualan_cap'])) {
			$pesan_penj .= ', koreksi penjualan>stok: ' . (int) $penjualan_info['reconcile']['fixed_penjualan_cap'];
		}
		$pesan_penj .= '.';
	}
	$pesan_prod = '';
	if (is_array($produksi_info)) {
		$pesan_prod = ' Produksi bahan: ' . (int) (isset($produksi_info['matched']) ? $produksi_info['matched'] : 0)
			. ' baris sys_unit_produk_bahan cocok, ' . (int) (isset($produksi_info['with_bahan']) ? $produksi_info['with_bahan'] : 0)
			. ' record persediaan bahan_produksi>0, flush ' . (int) (isset($produksi_info['flushed']) ? $produksi_info['flushed'] : 0) . '.';
	}
	$pesan_pecah = '';
	if (is_array($pecah_info)) {
		$pesan_pecah = ' Pecah satuan: ' . (int) (isset($pecah_info['matched']) ? $pecah_info['matched'] : 0)
			. ' baris tbl_pembelian_pecah_satuan cocok, flush ' . (int) (isset($pecah_info['flushed']) ? $pecah_info['flushed'] : 0)
			. ', insert target baru ' . (int) (isset($pecah_info['target_insert']) ? $pecah_info['target_insert'] : 0) . '.';
	}
	$pesan_nilai = '';
	if (is_array($nilai_info) && isset($nilai_info['updated'])) {
		$pesan_nilai = ' Refresh nilai_persediaan (total_10 × hpp): ' . (int) $nilai_info['updated'] . ' baris.';
	}

	return array_merge(array(
		'ok' => true,
		'phase' => 'pecah_satuan',
		'done' => true,
		'offset_selesai' => 0,
		'total_phase' => 0,
		'stats' => $state['stats'],
		'summary' => $summary_done,
		'summary_tables' => persediaan_gen_recalc_summary_tables_load_safe($CI, $bulan),
		'extra_result_tables' => $extra_result_tables,
		'history_saved' => !empty($state['log_id']),
		'hapus_sa_total10_nol' => (int) $state['hapus_nol_target'],
		'pembelian_reapply' => $pembelian_info,
		'penjualan_reapply' => $penjualan_info,
		'produksi_reapply' => $produksi_info,
		'pecah_reapply' => $pecah_info,
		'nilai_persediaan_refresh' => $nilai_info,
		'refresh_persediaan' => true,
		'pesan' => 'Selesai: generate dari bulan sumber (total_10 >= 1), pembelian, produksi, pecah satuan, lalu penjualan dari tbl_penjualan (uuid_persediaan bulan target).'
			. $pesan_penj
			. $pesan_prod
			. $pesan_pecah
			. $pesan_nilai
			. ' Baris target sa=0 & beli=0 & total_10=0 dihapus: ' . (int) $state['hapus_nol_target'] . '.'
			. persediaan_recalculate_cleanup_spop_kosong_pesan(isset($state['cleanup_spop_kosong']) ? $state['cleanup_spop_kosong'] : array())
			. persediaan_recalculate_cleanup_duplikat_spop_sama_pesan(isset($state['cleanup_duplikat_spop']) ? $state['cleanup_duplikat_spop'] : array()),
	), $items);
}

/**
 * Selesai setelah fase pembelian (belum penjualan/produksi/pecah satuan).
 */
function persediaan_generate_recalculate_finish_pembelian_only($CI, $bulan, $ctx, $count_sumber, &$state, $state_key, $batch_items)
{
	persediaan_generate_recalculate_refresh_nilai_persediaan_bulan($CI, $ctx['tanggal_beli_target']);

	$summary_done = persediaan_generate_recalculate_build_summary($CI, $bulan, $ctx, $count_sumber, $state);
	$summary_done['pembelian_only'] = 1;
	$summary_done['generated_at'] = date('Y-m-d H:i:s');

	$items = is_array($batch_items) ? $batch_items : array();
	$summary_tables = persediaan_gen_recalc_summary_tables_load_safe($CI, $bulan);
	$extra_result_tables = persediaan_gen_recalc_build_result_extra_tables($CI, $ctx, $items);
	$summary_done['penjualan_uuid_orphan_count'] = (int) (isset($extra_result_tables['penjualan_uuid_orphan_count'])
		? $extra_result_tables['penjualan_uuid_orphan_count']
		: 0);

	persediaan_gen_recalc_batch_finish_history($CI, $state, $summary_done);
	persediaan_history_generate_finish_from_batch($CI, $state, $bulan, $summary_done);
	$CI->session->unset_userdata($state_key);

	return array_merge(array(
		'ok' => true,
		'phase' => 'pembelian',
		'done' => true,
		'pembelian_only' => true,
		'offset_selesai' => 0,
		'total_phase' => 0,
		'stats' => $state['stats'],
		'summary' => $summary_done,
		'summary_tables' => $summary_tables,
		'extra_result_tables' => $extra_result_tables,
		'history_saved' => !empty($state['log_id']),
		'hapus_sa_total10_nol' => (int) (isset($state['hapus_nol_target']) ? $state['hapus_nol_target'] : 0),
		'refresh_persediaan' => true,
		'pesan' => 'Selesai fase generate + recalculate pembelian (tbl_pembelian).'
			. ((int) (isset($state['reset_target']) ? $state['reset_target'] : 0) > 0
				? ' Hapus bulan target: ' . (int) $state['reset_target'] . ' record.' : '')
			. ' Generate insert: ' . (int) $summary_done['generate_insert']
			. ', update: ' . (int) $summary_done['generate_update']
			. '. Pembelian diproses: ' . (int) $summary_done['total_pembelian']
			. ' — update beli: ' . (int) $summary_done['pembelian_update']
			. ', record baru: ' . (int) $summary_done['pembelian_insert']
			. (isset($summary_done['pembelian_gagal']) && (int) $summary_done['pembelian_gagal'] > 0
				? ', gagal: ' . (int) $summary_done['pembelian_gagal'] : '')
			. '. Fase penjualan, produksi, dan pecah satuan belum dijalankan.',
	), persediaan_generate_recalculate_batch_response_meta($state), $items);
}

function persediaan_generate_recalculate_start_unit_produk_phase($CI, $bulan, $limit, &$state, $state_key, $ctx, $count_sumber, $carry_items)
{
	$state['phase'] = 'unit_produk';
	persediaan_generate_recalculate_init_unit_produk_phase($CI, $ctx, $state);
	persediaan_generate_recalc_clear_queue_cache($state);
	unset($state['persediaan_map_cache'], $state['persediaan_map_cache_tgl']);
	$CI->session->set_userdata($state_key, $state);

	$total_unit = count(persediaan_generate_recalculate_get_unit_produk_queue($state, $CI, $ctx));
	if ($total_unit === 0) {
		return persediaan_generate_recalculate_start_produksi_phase(
			$CI, $bulan, $limit, $state, $state_key, $ctx, $count_sumber, $carry_items
		);
	}

	return persediaan_generate_recalculate_phase_transition_response(
		$CI,
		$state,
		$state_key,
		$ctx,
		$carry_items,
		'unit_produk',
		$total_unit,
		'Lanjut fase produk jadi (sys_unit_produk): 0 / ' . $total_unit . ' grup'
	);
}

function persediaan_generate_recalculate_start_produksi_phase($CI, $bulan, $limit, &$state, $state_key, $ctx, $count_sumber, $carry_items)
{
	$state['phase'] = 'produksi';
	persediaan_generate_recalculate_init_produksi_phase($CI, $ctx, $state);
	persediaan_generate_recalc_clear_queue_cache($state);
	unset($state['persediaan_map_cache'], $state['persediaan_map_cache_tgl']);
	$CI->session->set_userdata($state_key, $state);

	$total_produksi = count(persediaan_generate_recalculate_get_produksi_queue($state, $CI, $ctx));
	if ($total_produksi === 0) {
		return persediaan_generate_recalculate_start_penjualan_phase(
			$CI, $bulan, $limit, $state, $state_key, $ctx, $count_sumber, $carry_items
		);
	}

	return persediaan_generate_recalculate_phase_transition_response(
		$CI,
		$state,
		$state_key,
		$ctx,
		$carry_items,
		'produksi',
		$total_produksi,
		'Lanjut fase produksi bahan (sys_unit_produk_bahan): 0 / ' . $total_produksi . ' grup'
	);
}

function persediaan_generate_recalculate_start_penjualan_phase($CI, $bulan, $limit, &$state, $state_key, $ctx, $count_sumber, $carry_items)
{
	$state['phase'] = 'penjualan';
	$state['penjualan_mode'] = 'uuid_direct';
	persediaan_generate_recalculate_init_penjualan_phase($CI, $ctx, $state);
	persediaan_generate_recalc_clear_queue_cache($state);
	unset($state['persediaan_map_cache'], $state['persediaan_map_cache_tgl']);
	$CI->session->set_userdata($state_key, $state);

	$total_penjualan = count(persediaan_generate_recalculate_get_penjualan_queue($state, $CI, $ctx));
	if ($total_penjualan === 0) {
		persediaan_generate_recalculate_refresh_nilai_persediaan_bulan(
			$CI,
			isset($ctx['tanggal_beli_target']) ? $ctx['tanggal_beli_target'] : $ctx['tanggal_beli']
		);
		$CI->session->set_userdata($state_key, $state);

		return persediaan_generate_recalculate_start_pecah_satuan_phase(
			$CI, $bulan, $limit, $state, $state_key, $ctx, $count_sumber, $carry_items
		);
	}

	return persediaan_generate_recalculate_phase_transition_response(
		$CI,
		$state,
		$state_key,
		$ctx,
		$carry_items,
		'penjualan',
		$total_penjualan,
		'Lanjut fase penjualan (tbl_penjualan): 0 / ' . $total_penjualan . ' baris'
	);
}

function persediaan_generate_recalculate_default_batch_limit()
{
	return 250;
}

function persediaan_generate_recalculate_max_batch_limit()
{
	return 1000;
}

function persediaan_generate_recalculate_normalize_batch_limit($limit)
{
	$limit = (int) $limit;
	if ($limit < 1) {
		return persediaan_generate_recalculate_default_batch_limit();
	}

	return min($limit, persediaan_generate_recalculate_max_batch_limit());
}

function persediaan_generate_recalc_clear_queue_cache(&$state)
{
	foreach (array(
		'queue_pembelian',
		'queue_pembelian_total',
		'queue_unit_produk',
		'queue_unit_produk_total',
		'queue_produksi',
		'queue_produksi_total',
		'queue_penjualan',
		'queue_penjualan_total',
		'queue_pecah_satuan',
		'queue_pecah_satuan_total',
		'pembelian_queue_stats',
	) as $cache_key) {
		unset($state[$cache_key]);
	}
}

function persediaan_generate_recalculate_get_pembelian_queue(&$state, $CI, $ctx)
{
	if (!empty($state['queue_pembelian']) && is_array($state['queue_pembelian'])) {
		return $state['queue_pembelian'];
	}

	$queue = persediaan_generate_recalculate_build_pembelian_queue($CI, $ctx);
	$state['queue_pembelian'] = $queue;
	$state['queue_pembelian_total'] = count($queue);
	$stats = persediaan_generate_recalculate_pembelian_queue_stats_get();
	if (!empty($stats)) {
		$state['pembelian_queue_stats'] = $stats;
	}

	return $queue;
}

function persediaan_generate_recalculate_get_unit_produk_queue(&$state, $CI, $ctx)
{
	if (!empty($state['queue_unit_produk']) && is_array($state['queue_unit_produk'])) {
		return $state['queue_unit_produk'];
	}

	$queue = persediaan_generate_recalculate_build_unit_produk_queue($CI, $ctx);
	$state['queue_unit_produk'] = $queue;
	$state['queue_unit_produk_total'] = count($queue);

	return $queue;
}

function persediaan_generate_recalculate_get_produksi_queue(&$state, $CI, $ctx)
{
	if (!empty($state['queue_produksi']) && is_array($state['queue_produksi'])) {
		return $state['queue_produksi'];
	}

	$queue = persediaan_generate_recalculate_build_produksi_queue($CI, $ctx);
	$state['queue_produksi'] = $queue;
	$state['queue_produksi_total'] = count($queue);

	return $queue;
}

function persediaan_generate_recalculate_get_penjualan_queue(&$state, $CI, $ctx)
{
	if (!empty($state['queue_penjualan']) && is_array($state['queue_penjualan'])) {
		return $state['queue_penjualan'];
	}

	$queue = persediaan_generate_recalculate_build_penjualan_queue($CI, $ctx);
	$state['queue_penjualan'] = $queue;
	$state['queue_penjualan_total'] = count($queue);

	return $queue;
}

function persediaan_generate_recalculate_get_pecah_satuan_queue(&$state, $CI, $ctx)
{
	if (!empty($state['queue_pecah_satuan']) && is_array($state['queue_pecah_satuan'])) {
		return $state['queue_pecah_satuan'];
	}

	$queue = persediaan_generate_recalculate_build_pecah_satuan_queue($CI, $ctx);
	$state['queue_pecah_satuan'] = $queue;
	$state['queue_pecah_satuan_total'] = count($queue);

	return $queue;
}

function persediaan_generate_recalculate_get_map_cached(&$state, $CI, $tanggal_beli, $refresh = false)
{
	if (!$refresh
		&& !empty($state['persediaan_map_cache'])
		&& is_array($state['persediaan_map_cache'])
		&& isset($state['persediaan_map_cache_tgl'])
		&& $state['persediaan_map_cache_tgl'] === $tanggal_beli) {
		return $state['persediaan_map_cache'];
	}

	$map = persediaan_recalculate_build_map_persediaan_bulan($CI, $tanggal_beli);
	$state['persediaan_map_cache'] = $map;
	$state['persediaan_map_cache_tgl'] = $tanggal_beli;

	return $map;
}

function persediaan_generate_recalculate_prefetch_table_rows($CI, $table, $ids)
{
	$ids = array_values(array_unique(array_filter(array_map('intval', (array) $ids))));
	if ($table === '' || empty($ids) || !$CI->db->table_exists($table)) {
		return array();
	}

	$rows = $CI->db->where_in('id', $ids)->get($table)->result();
	$out = array();
	foreach ($rows as $row) {
		$out[(int) $row->id] = $row;
	}

	return $out;
}

function persediaan_generate_recalculate_prefetch_pembelian_slice($CI, $slice)
{
	$by_table = array();
	foreach ((array) $slice as $item) {
		if (!is_array($item) || !empty($item['group_spop'])) {
			continue;
		}
		$tbl = isset($item['tabel']) ? trim((string) $item['tabel']) : '';
		$id = isset($item['id']) ? (int) $item['id'] : 0;
		if ($tbl === '' || $id < 1) {
			continue;
		}
		if (!isset($by_table[$tbl])) {
			$by_table[$tbl] = array();
		}
		$by_table[$tbl][$id] = $id;
	}

	$out = array();
	foreach ($by_table as $tbl => $id_map) {
		$rows = persediaan_generate_recalculate_prefetch_table_rows($CI, $tbl, array_values($id_map));
		foreach ($rows as $id => $row) {
			$out[$tbl . '#' . $id] = $row;
		}
	}

	return $out;
}

function persediaan_generate_recalculate_batch_compact_items($payload, $compact = true)
{
	if (!$compact || !is_array($payload)) {
		return $payload;
	}

	if (!empty($payload['done']) || !empty($payload['phase_changed'])) {
		return $payload;
	}

	$item_keys = array(
		'items_persediaan',
		'items_pembelian',
		'items_pembelian_update',
		'items_pembelian_baru',
		'items_penjualan',
		'items_penjualan_update',
		'items_unit_produk',
		'items_unit_produk_update',
		'items_produksi',
		'items_produksi_update',
		'items_pecah_satuan',
		'items_pecah_satuan_update',
		'items_generate_verifikasi',
		'items_generate_masalah',
	);
	foreach ($item_keys as $key) {
		if (!empty($payload[$key]) && is_array($payload[$key])) {
			$payload[$key . '_batch_count'] = count($payload[$key]);
			$payload[$key] = array();
		}
	}
	$payload['response_compact'] = 1;

	return $payload;
}

function persediaan_generate_recalculate_phase_transition_response($CI, &$state, $state_key, $ctx, $carry_items, $phase, $total_phase, $pesan)
{
	$state['phase'] = $phase;
	persediaan_generate_recalc_clear_queue_cache($state);
	unset($state['persediaan_map_cache'], $state['persediaan_map_cache_tgl']);
	$CI->session->set_userdata($state_key, $state);

	$result = array_merge(array(
		'ok' => true,
		'phase' => $phase,
		'done' => false,
		'offset_selesai' => 0,
		'total_phase' => (int) $total_phase,
		'phase_changed' => true,
		'stats' => isset($state['stats']) ? $state['stats'] : array(),
		'pesan' => $pesan,
	), is_array($carry_items) ? $carry_items : array());

	return persediaan_generate_recalculate_batch_compact_items($result, false);
}

function persediaan_generate_recalculate_batch($CI, $bulan, $offset, $limit, $start = false)
{
	return persediaan_generate_v2_batch($CI, $bulan, $offset, $limit, $start);
}

/**
 * @deprecated Legacy batch — digantikan persediaan_generate_v2_batch.
 */
function persediaan_generate_recalculate_batch_legacy($CI, $bulan, $offset, $limit, $start = false)
{
	$CI->load->helper('persediaan_display');
	$limit = persediaan_generate_recalculate_normalize_batch_limit($limit);

	if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');
	}

	$ts_target = strtotime($bulan . '-01');
	if ($ts_target === false) {
		return array('ok' => false, 'message' => 'Bulan target tidak valid.');
	}

	$tanggal_beli_target = date('Y-m-01', $ts_target);
	$tanggal_beli_sumber = date('Y-m-01', strtotime('-1 month', $ts_target));
	$tgl_awal = $tanggal_beli_target;
	$tgl_akhir = date('Y-m-t', $ts_target);

	$state_key = 'gen_recalc_state_' . $bulan;
	$state_probe = $CI->session->userdata($state_key);
	$use_cached_counts = is_array($state_probe) && !empty($state_probe['ctx_counts']) && is_array($state_probe['ctx_counts']);

	if ($use_cached_counts) {
		$count_sumber = (int) (isset($state_probe['ctx_counts']['count_sumber']) ? $state_probe['ctx_counts']['count_sumber'] : 0);
		$count_sumber_all = (int) (isset($state_probe['ctx_counts']['count_sumber_all']) ? $state_probe['ctx_counts']['count_sumber_all'] : 0);
		$count_target = (int) (isset($state_probe['ctx_counts']['count_target']) ? $state_probe['ctx_counts']['count_target'] : 0);
	} else {
		$row_cnt = $CI->db->query(
			"SELECT COUNT(*) AS jml FROM `persediaan` WHERE `tanggal_beli` = ?"
			. persediaan_generate_recalculate_sql_filter_total10_positif(),
			array($tanggal_beli_sumber)
		)->row();
		$count_sumber = $row_cnt ? (int) $row_cnt->jml : 0;

		$row_cnt_all = $CI->db->query(
			"SELECT COUNT(*) AS jml FROM `persediaan` WHERE `tanggal_beli` = ?",
			array($tanggal_beli_sumber)
		)->row();
		$count_sumber_all = $row_cnt_all ? (int) $row_cnt_all->jml : 0;

		$row_cnt_target = $CI->db->query(
			"SELECT COUNT(*) AS jml FROM `persediaan` WHERE `tanggal_beli` = ?",
			array($tanggal_beli_target)
		)->row();
		$count_target = $row_cnt_target ? (int) $row_cnt_target->jml : 0;
	}

	$ctx_probe = persediaan_recalculate_full_context($CI, $bulan);
	$can_recalc_only = ($count_sumber_all === 0)
		&& !empty($ctx_probe['ok'])
		&& (!empty($ctx_probe['can_proceed']) || $count_target > 0);

	if ($count_sumber_all === 0 && !$can_recalc_only) {
		return array(
			'ok' => false,
			'no_source_data' => true,
			'message' => 'Belum ada data persediaan di bulan referensi (bulan sebelumnya: '
				. date('m/Y', strtotime($tanggal_beli_sumber)) . ', tanggal_beli = ' . $tanggal_beli_sumber . ') '
				. 'dan belum ada data/transaksi di bulan target.',
			'bulan_sumber' => date('Y-m', strtotime($tanggal_beli_sumber)),
			'tanggal_beli_sumber' => $tanggal_beli_sumber,
		);
	}

	$ctx = array(
		'ok' => true,
		'bulan' => $bulan,
		'bulan_label' => date('m/Y', $ts_target),
		'bulan_sumber_label' => date('m/Y', strtotime($tanggal_beli_sumber)),
		'tanggal_beli' => $tanggal_beli_target,
		'tanggal_beli_target' => $tanggal_beli_target,
		'tanggal_beli_sumber' => $tanggal_beli_sumber,
		'tanggal_tampilan_target' => date('d/m/Y', $ts_target),
		'tgl_awal' => $tgl_awal,
		'tgl_akhir' => $tgl_akhir,
		'total_sumber' => $count_sumber,
		'total_sumber_all' => $count_sumber_all,
		'count_sumber_skip_total10' => max(0, $count_sumber_all - $count_sumber),
		'recalculate_only' => ($count_sumber_all === 0),
		'count_target_existing' => $count_target,
	);

	$state = $CI->session->userdata($state_key);

	if ($start) {
		$CI->session->unset_userdata($state_key);
		$state = null;
	}

	if (!is_array($state)) {
		if ((int) $offset > 0 && !$start) {
			return array(
				'ok' => false,
				'message' => 'Sesi generate & recalculate tidak ditemukan. Silakan mulai ulang.',
			);
		}

		persediaan_ensure_asal_generate_column($CI);

		$hapus_nol_target = 0;
		$hapus_duplikat_target = 0;
		$cleanup_spop_kosong = array('deleted' => 0, 'groups' => 0);
		if ($start) {
			$tanggal_klik_generate = date('Y-m-d H:i:s');
			$reset_target_info = persediaan_generate_recalculate_kosongkan_bulan_target($CI, $tanggal_beli_target);
			if (empty($reset_target_info['verified_empty'])) {
				return array(
					'ok' => false,
					'message' => 'Gagal mengosongkan data persediaan bulan target (tanggal_beli = '
						. $tanggal_beli_target . '). Masih tersisa '
						. (int) (isset($reset_target_info['remaining']) ? $reset_target_info['remaining'] : 0)
						. ' record. Proses generate dibatalkan.',
					'reset_target' => (int) (isset($reset_target_info['deleted']) ? $reset_target_info['deleted'] : 0),
					'target_kosong_verified' => 0,
					'tanggal_klik_generate' => $tanggal_klik_generate,
				);
			}
			$reset_target_count = (int) (isset($reset_target_info['deleted']) ? $reset_target_info['deleted'] : 0);
			$hapus_nol_target = 0;
			$hapus_duplikat_target = 0;
			$cleanup_spop_kosong = array('deleted' => 0, 'groups' => 0);
		}

		$row_max = $CI->db->query("SELECT MAX(`id`) AS max_id FROM `persediaan`")->row();
		$state = array(
			'phase' => 'generate',
			'tanggal_klik_generate' => isset($tanggal_klik_generate) ? $tanggal_klik_generate : date('Y-m-d H:i:s'),
			'target_kosong_verified' => isset($reset_target_info) && !empty($reset_target_info['verified_empty']) ? 1 : 0,
			'stop_after_generate' => persediaan_generate_recalculate_stop_after_generate() ? 1 : 0,
			'stop_after_pembelian' => persediaan_generate_recalculate_stop_after_pembelian() ? 1 : 0,
			'next_id' => $row_max && $row_max->max_id ? ((int) $row_max->max_id + 1) : 1,
			'reset_target' => isset($reset_target_count) ? (int) $reset_target_count : 0,
			'hapus_nol_target' => $hapus_nol_target,
			'hapus_duplikat_target' => $hapus_duplikat_target,
			'cleanup_spop_kosong' => $cleanup_spop_kosong,
			'ctx_counts' => array(
				'count_sumber' => (int) $count_sumber,
				'count_sumber_all' => (int) $count_sumber_all,
				'count_target' => (int) $count_target,
			),
			'stats' => array(
				'generate_insert' => 0,
				'generate_update' => 0,
				'generate_skip' => 0,
				'pembelian_update' => 0,
				'pembelian_insert' => 0,
				'pembelian_gagal' => 0,
				'pembelian_skip' => 0,
				'penjualan_update' => 0,
				'penjualan_gagal' => 0,
				'penjualan_tidak_cocok' => 0,
				'penjualan_skip' => 0,
				'produksi_update' => 0,
				'produksi_gagal' => 0,
				'produksi_tidak_cocok' => 0,
				'produksi_skip' => 0,
				'unit_produk_update' => 0,
				'unit_produk_insert' => 0,
				'unit_produk_gagal' => 0,
				'unit_produk_skip' => 0,
				'pecah_update' => 0,
				'pecah_gagal' => 0,
				'pecah_tidak_cocok' => 0,
				'pecah_skip' => 0,
			),
		);

		if ($start && persediaan_gen_recalc_table_exists($CI)) {
			$log_id = persediaan_gen_recalc_history_start($CI, $ctx, $count_sumber);
			if ($log_id > 0) {
				$state['log_id'] = $log_id;
				$state['hist_nomor'] = persediaan_gen_recalc_hist_nomor_init();
			}
		}

		if ($start) {
			$hist_gen_id = persediaan_history_generate_start($CI, $ctx, $state['tanggal_klik_generate'], $state);
			if ($hist_gen_id > 0) {
				$state['history_generate_id'] = $hist_gen_id;
			}
		}

		if (empty($state['ctx_counts']) || !is_array($state['ctx_counts'])) {
			$state['ctx_counts'] = array(
				'count_sumber' => (int) $count_sumber,
				'count_sumber_all' => (int) $count_sumber_all,
				'count_target' => (int) $count_target,
			);
		}
	}

	$items_persediaan = array();
	$items_pembelian = array();
	$items_pembelian_update = array();
	$items_pembelian_baru = array();
	$items_penjualan = array();
	$items_penjualan_update = array();
	$items_produksi = array();
	$items_produksi_update = array();
	$items_unit_produk = array();
	$items_unit_produk_update = array();
	$items_pecah_satuan = array();
	$items_pecah_satuan_update = array();

	if ($state['phase'] === 'generate') {
		// Scan semua baris bulan sumber; filter total_10 >= 1 di PHP (selaras persediaan_parse_angka).
		$list_batch = $CI->db->query(
			"SELECT * FROM `persediaan` WHERE `tanggal_beli` = ?"
			. " ORDER BY `namabarang` ASC, `id` ASC LIMIT "
			. (int) $limit . ' OFFSET ' . (int) $offset,
			array($tanggal_beli_sumber)
		)->result();

		$map = persediaan_generate_recalculate_get_map_cached(
			$state,
			$CI,
			$tanggal_beli_target,
			((int) $offset === 0)
		);
		$next_id = (int) $state['next_id'];

		foreach ($list_batch as $row_sumber) {
			if (!persediaan_generate_recalculate_sumber_layak_generate($row_sumber)) {
				$state['stats']['generate_skip']++;
				continue;
			}

			$item = persediaan_generate_recalculate_upsert_from_sumber($CI, $ctx, $row_sumber, $next_id, $map);
			if ($item['aksi'] === 'SKIP') {
				$state['stats']['generate_skip']++;
				continue;
			}
			$items_persediaan[] = $item;
			if ($item['aksi'] === 'INSERT') {
				$state['stats']['generate_insert']++;
			} elseif ($item['aksi'] === 'UPDATE') {
				$state['stats']['generate_update']++;
			}
		}

		$state['persediaan_map_cache'] = $map;
		$state['persediaan_map_cache_tgl'] = $tanggal_beli_target;
		$state['next_id'] = $next_id;
		$offset_selesai = $offset + count($list_batch);
		$done_generate = ($count_sumber_all === 0 || $offset_selesai >= $count_sumber_all);

		if ($done_generate) {
			if (empty($state['stop_after_generate'])) {
				$hapus_setelah_generate = persediaan_generate_recalculate_hapus_baris_nol_bulan_target($CI, $tanggal_beli_target);
				$state['hapus_nol_target'] = (int) (isset($state['hapus_nol_target']) ? $state['hapus_nol_target'] : 0) + $hapus_setelah_generate;
				persediaan_generate_recalculate_merge_cleanup_spop_state(
					$state,
					persediaan_recalculate_cleanup_duplikat_spop_kosong_beli_nol($CI, $tanggal_beli_target)
				);
				persediaan_generate_recalculate_merge_cleanup_duplikat_spop_state(
					$state,
					persediaan_recalculate_cleanup_duplikat_spop_sama_bulan($CI, $tanggal_beli_target)
				);
			}

			persediaan_gen_recalc_history_save_batch($CI, $state, $items_persediaan, array(), array(), array(), array(), array());
			$CI->session->set_userdata($state_key, $state);

			$carry = array(
				'items_persediaan' => $items_persediaan,
				'items_pembelian' => array(),
				'items_pembelian_update' => array(),
				'items_pembelian_baru' => array(),
			);

			if (!empty($state['stop_after_generate'])) {
				return persediaan_generate_recalculate_finish_generate_only(
					$CI, $bulan, $ctx, $count_sumber, $state, $state_key, $carry
				);
			}

			$total_pembelian = count(persediaan_generate_recalculate_get_pembelian_queue($state, $CI, $ctx));
			if ($total_pembelian === 0) {
				if (persediaan_generate_recalculate_stop_after_pembelian()) {
					return persediaan_generate_recalculate_finish_pembelian_only(
						$CI, $bulan, $ctx, $count_sumber, $state, $state_key, $carry
					);
				}
				return persediaan_generate_recalculate_start_unit_produk_phase(
					$CI, $bulan, $limit, $state, $state_key, $ctx, $count_sumber, $carry
				);
			}

			$state['phase'] = 'pembelian';
			persediaan_generate_recalc_clear_queue_cache($state);
			unset($state['persediaan_map_cache'], $state['persediaan_map_cache_tgl']);
			$CI->session->set_userdata($state_key, $state);

			return persediaan_generate_recalculate_phase_transition_response(
				$CI,
				$state,
				$state_key,
				$ctx,
				$carry,
				'pembelian',
				$total_pembelian,
				'Generate selesai — lanjut fase pembelian: 0 / ' . $total_pembelian . ' batch pembelian'
			);
		}

		$CI->session->set_userdata($state_key, $state);

		return array_merge(array(
			'ok' => true,
			'phase' => 'generate',
			'done' => false,
			'offset_selesai' => $offset_selesai,
			'total_phase' => $count_sumber_all,
			'total_sumber' => $count_sumber,
			'total_sumber_all' => $count_sumber_all,
			'bulan_label' => $ctx['bulan_label'],
			'bulan_sumber_label' => $ctx['bulan_sumber_label'],
			'items_persediaan' => $items_persediaan,
			'stats' => $state['stats'],
			'pesan' => 'Generate fase 1: ' . $offset_selesai . ' / ' . $count_sumber_all . ' record sumber dipindai'
				. ' (' . $count_sumber . ' layak generate: kolom total_10 > 0)'
				. (isset($ctx['count_sumber_skip_total10']) && (int) $ctx['count_sumber_skip_total10'] > 0
					? ', lewati ' . (int) $ctx['count_sumber_skip_total10'] . ' record total_10 <= 0 / kosong' : '')
				. ((int) (isset($state['reset_target']) ? $state['reset_target'] : 0) > 0 && $offset_selesai <= (int) $limit
					? ' | Hapus data bulan target: ' . (int) $state['reset_target'] . ' record (tanggal_beli kosong diverifikasi)' : ''),
		), persediaan_generate_recalculate_batch_response_meta($state));
	}

	if ($state['phase'] === 'pembelian') {
		$queue = persediaan_generate_recalculate_get_pembelian_queue($state, $CI, $ctx);
		$total_pembelian = count($queue);
		$map = persediaan_generate_recalculate_get_map_cached(
			$state,
			$CI,
			$tanggal_beli_target,
			((int) $offset === 0)
		);

		$slice = array_slice($queue, $offset, $limit);
		$prefetch_pembelian = persediaan_generate_recalculate_prefetch_pembelian_slice($CI, $slice);
		foreach ($slice as $queue_item) {
			$item = persediaan_generate_recalculate_proses_pembelian_row($CI, $ctx, $queue_item, $map, $prefetch_pembelian);

			if ($item['aksi'] === 'SKIP') {
				$state['stats']['pembelian_skip']++;
				$items_pembelian[] = $item;
				continue;
			}

			$items_pembelian[] = $item;

			if ($item['aksi'] === 'UPDATE_BELI') {
				$state['stats']['pembelian_update']++;
				$items_pembelian_update[] = $item;
			} elseif ($item['aksi'] === 'INSERT_BARU') {
				$state['stats']['pembelian_insert']++;
				$items_pembelian_baru[] = $item;
			} elseif ($item['aksi'] === 'GAGAL') {
				$state['stats']['pembelian_gagal']++;
			}
		}

		if (!empty($items_pembelian) || !empty($items_pembelian_update) || !empty($items_pembelian_baru)) {
			persediaan_gen_recalc_history_save_batch(
				$CI,
				$state,
				array(),
				$items_pembelian,
				$items_pembelian_update,
				$items_pembelian_baru,
				array(),
				array()
			);
		}

		$state['persediaan_map_cache'] = $map;
		$state['persediaan_map_cache_tgl'] = $tanggal_beli_target;
		$offset_selesai = $offset + count($slice);
		$done = ($offset_selesai >= $total_pembelian);

		if ($done) {
			persediaan_generate_recalculate_merge_cleanup_spop_state(
				$state,
				persediaan_recalculate_cleanup_duplikat_spop_kosong_beli_nol($CI, $tanggal_beli_target)
			);
			persediaan_generate_recalculate_merge_cleanup_duplikat_spop_state(
				$state,
				persediaan_recalculate_cleanup_duplikat_spop_sama_bulan($CI, $tanggal_beli_target)
			);

			$carry = array(
				'items_persediaan' => $items_persediaan,
				'items_pembelian' => $items_pembelian,
				'items_pembelian_update' => $items_pembelian_update,
				'items_pembelian_baru' => $items_pembelian_baru,
			);

			if (persediaan_generate_recalculate_stop_after_pembelian()) {
				return persediaan_generate_recalculate_finish_pembelian_only(
					$CI, $bulan, $ctx, $count_sumber, $state, $state_key, $carry
				);
			}

			return persediaan_generate_recalculate_start_unit_produk_phase(
				$CI, $bulan, $limit, $state, $state_key, $ctx, $count_sumber, $carry
			);
		}

		persediaan_gen_recalc_history_save_batch(
			$CI,
			$state,
			$items_persediaan,
			$items_pembelian,
			$items_pembelian_update,
			$items_pembelian_baru,
			array(),
			array()
		);
		$CI->session->set_userdata($state_key, $state);

		return array(
			'ok' => true,
			'phase' => 'pembelian',
			'done' => false,
			'offset_selesai' => $offset_selesai,
			'total_phase' => $total_pembelian,
			'items_persediaan' => $items_persediaan,
			'items_pembelian' => $items_pembelian,
			'items_pembelian_update' => $items_pembelian_update,
			'items_pembelian_baru' => $items_pembelian_baru,
			'stats' => $state['stats'],
			'pesan' => 'Recalculate beli fase 2: ' . $offset_selesai . ' / ' . $total_pembelian . ' baris pembelian',
		);
	}

	if ($state['phase'] === 'penjualan') {
		if ((int) $offset === 0) {
			persediaan_generate_recalculate_init_penjualan_phase($CI, $ctx, $state);
		}
		if (!isset($state['penjualan_accum']) || !is_array($state['penjualan_accum'])) {
			$state['penjualan_accum'] = array();
		}

		$queue = persediaan_generate_recalculate_get_penjualan_queue($state, $CI, $ctx);
		$total_penjualan = count($queue);
		$map = persediaan_generate_recalculate_get_map_cached(
			$state,
			$CI,
			$tanggal_beli_target,
			((int) $offset === 0)
		);

		$slice = array_slice($queue, $offset, $limit);
		$use_net_stock = !empty($state['penjualan_use_net_stock']);
		foreach ($slice as $queue_item) {
			$item = persediaan_generate_recalculate_proses_penjualan_row(
				$CI,
				$ctx,
				$queue_item,
				$map,
				$state['penjualan_accum'],
				$use_net_stock
			);
			$items_penjualan[] = $item;

			if ($item['aksi'] === 'SKIP') {
				$state['stats']['penjualan_skip']++;
			} elseif ($item['aksi'] === 'TIDAK_COCOK') {
				$state['stats']['penjualan_tidak_cocok']++;
			} elseif ($item['aksi'] === 'UPDATE_PENJUALAN') {
				$state['stats']['penjualan_update']++;
				$items_penjualan_update[] = $item;
			} elseif ($item['aksi'] === 'GAGAL') {
				$state['stats']['penjualan_gagal']++;
			}
		}

		$state['persediaan_map_cache'] = $map;
		$state['persediaan_map_cache_tgl'] = $tanggal_beli_target;
		$offset_selesai = $offset + count($slice);
		$done = ($total_penjualan === 0 || $offset_selesai >= $total_penjualan);

		$CI->session->set_userdata($state_key, $state);

		if ($done) {
			persediaan_generate_recalculate_refresh_nilai_persediaan_bulan($CI, $tanggal_beli_target);
			persediaan_gen_recalc_refresh_items_total_10_check($CI, $items_penjualan);
			persediaan_gen_recalc_refresh_items_total_10_check($CI, $items_penjualan_update);
			$CI->session->set_userdata($state_key, $state);

			persediaan_gen_recalc_history_save_batch(
				$CI,
				$state,
				$items_persediaan,
				$items_pembelian,
				$items_pembelian_update,
				$items_pembelian_baru,
				$items_penjualan,
				$items_penjualan_update,
				$items_produksi,
				$items_produksi_update,
				$items_pecah_satuan,
				$items_pecah_satuan_update,
				$items_unit_produk,
				$items_unit_produk_update
			);

			$carry = array(
				'items_persediaan' => $items_persediaan,
				'items_pembelian' => $items_pembelian,
				'items_pembelian_update' => $items_pembelian_update,
				'items_pembelian_baru' => $items_pembelian_baru,
				'items_penjualan' => $items_penjualan,
				'items_penjualan_update' => $items_penjualan_update,
				'items_unit_produk' => $items_unit_produk,
				'items_unit_produk_update' => $items_unit_produk_update,
				'items_produksi' => $items_produksi,
				'items_produksi_update' => $items_produksi_update,
				'items_pecah_satuan' => $items_pecah_satuan,
				'items_pecah_satuan_update' => $items_pecah_satuan_update,
			);

			return persediaan_generate_recalculate_start_pecah_satuan_phase(
				$CI, $bulan, $limit, $state, $state_key, $ctx, $count_sumber, $carry
			);
		}

		return array(
			'ok' => true,
			'phase' => 'penjualan',
			'done' => false,
			'offset_selesai' => $offset_selesai,
			'total_phase' => $total_penjualan,
			'items_persediaan' => $items_persediaan,
			'items_pembelian' => $items_pembelian,
			'items_pembelian_update' => $items_pembelian_update,
			'items_pembelian_baru' => $items_pembelian_baru,
			'items_unit_produk' => $items_unit_produk,
			'items_unit_produk_update' => $items_unit_produk_update,
			'items_produksi' => $items_produksi,
			'items_produksi_update' => $items_produksi_update,
			'items_penjualan' => $items_penjualan,
			'items_penjualan_update' => $items_penjualan_update,
			'stats' => $state['stats'],
			'pesan' => 'Recalculate penjualan fase 5: ' . $offset_selesai . ' / ' . $total_penjualan . ' baris penjualan (uuid_persediaan / nama+satuan)',
		);
	}

	if ($state['phase'] === 'unit_produk') {
		if ((int) $offset === 0) {
			persediaan_generate_recalculate_init_unit_produk_phase($CI, $ctx, $state);
		}

		$queue = persediaan_generate_recalculate_get_unit_produk_queue($state, $CI, $ctx);
		$total_unit = count($queue);
		$map = persediaan_generate_recalculate_get_map_cached(
			$state,
			$CI,
			$tanggal_beli_target,
			((int) $offset === 0)
		);

		$slice = array_slice($queue, $offset, $limit);
		foreach ($slice as $queue_item) {
			$item = persediaan_generate_recalculate_proses_unit_produk_row($CI, $ctx, $queue_item, $map);
			$items_unit_produk[] = $item;

			if ($item['aksi'] === 'SKIP') {
				$state['stats']['unit_produk_skip']++;
			} elseif ($item['aksi'] === 'UPDATE_SA') {
				$state['stats']['unit_produk_update']++;
				$items_unit_produk_update[] = $item;
			} elseif ($item['aksi'] === 'INSERT_BARU') {
				$state['stats']['unit_produk_insert']++;
				$items_unit_produk_update[] = $item;
			} elseif ($item['aksi'] === 'GAGAL') {
				$state['stats']['unit_produk_gagal']++;
			}
		}

		$state['persediaan_map_cache'] = $map;
		$state['persediaan_map_cache_tgl'] = $tanggal_beli_target;
		$offset_selesai = $offset + count($slice);
		$done = ($total_unit === 0 || $offset_selesai >= $total_unit);

		$CI->session->set_userdata($state_key, $state);

		if ($done) {
			persediaan_gen_recalc_refresh_items_total_10_check($CI, $items_unit_produk);
			persediaan_gen_recalc_refresh_items_total_10_check($CI, $items_unit_produk_update);

			persediaan_gen_recalc_history_save_batch(
				$CI,
				$state,
				$items_persediaan,
				$items_pembelian,
				$items_pembelian_update,
				$items_pembelian_baru,
				$items_penjualan,
				$items_penjualan_update,
				$items_produksi,
				$items_produksi_update,
				$items_pecah_satuan,
				$items_pecah_satuan_update,
				$items_unit_produk,
				$items_unit_produk_update
			);

			$carry = array(
				'items_persediaan' => $items_persediaan,
				'items_pembelian' => $items_pembelian,
				'items_pembelian_update' => $items_pembelian_update,
				'items_pembelian_baru' => $items_pembelian_baru,
				'items_penjualan' => $items_penjualan,
				'items_penjualan_update' => $items_penjualan_update,
				'items_unit_produk' => $items_unit_produk,
				'items_unit_produk_update' => $items_unit_produk_update,
			);

			return persediaan_generate_recalculate_start_produksi_phase(
				$CI, $bulan, $limit, $state, $state_key, $ctx, $count_sumber, $carry
			);
		}

		return array(
			'ok' => true,
			'phase' => 'unit_produk',
			'done' => false,
			'offset_selesai' => $offset_selesai,
			'total_phase' => $total_unit,
			'items_persediaan' => $items_persediaan,
			'items_pembelian' => $items_pembelian,
			'items_pembelian_update' => $items_pembelian_update,
			'items_pembelian_baru' => $items_pembelian_baru,
			'items_penjualan' => $items_penjualan,
			'items_penjualan_update' => $items_penjualan_update,
			'items_unit_produk' => $items_unit_produk,
			'items_unit_produk_update' => $items_unit_produk_update,
			'stats' => $state['stats'],
			'pesan' => 'Recalculate produk jadi fase 3: ' . $offset_selesai . ' / ' . $total_unit . ' grup sys_unit_produk',
		);
	}

	if ($state['phase'] === 'produksi') {
		if ((int) $offset === 0) {
			persediaan_generate_recalculate_init_produksi_phase($CI, $ctx, $state);
		}
		if (!isset($state['produksi_accum']) || !is_array($state['produksi_accum'])) {
			$state['produksi_accum'] = array();
		}
		if (!isset($state['penjualan_accum']) || !is_array($state['penjualan_accum'])) {
			$state['penjualan_accum'] = array();
		}

		$queue = persediaan_generate_recalculate_get_produksi_queue($state, $CI, $ctx);
		$total_produksi = count($queue);
		$map = persediaan_generate_recalculate_get_map_cached(
			$state,
			$CI,
			$tanggal_beli_target,
			((int) $offset === 0)
		);

		$slice = array_slice($queue, $offset, $limit);
		foreach ($slice as $queue_item) {
			$item = persediaan_generate_recalculate_proses_produksi_row(
				$CI,
				$ctx,
				$queue_item,
				$map,
				$state['produksi_accum'],
				$state['penjualan_accum']
			);
			$items_produksi[] = $item;

			if ($item['aksi'] === 'SKIP') {
				$state['stats']['produksi_skip']++;
			} elseif ($item['aksi'] === 'TIDAK_COCOK') {
				$state['stats']['produksi_tidak_cocok']++;
			} elseif ($item['aksi'] === 'UPDATE_PRODUKSI') {
				$state['stats']['produksi_update']++;
				$items_produksi_update[] = $item;
			} elseif ($item['aksi'] === 'GAGAL') {
				$state['stats']['produksi_gagal']++;
			}
		}

		$state['persediaan_map_cache'] = $map;
		$state['persediaan_map_cache_tgl'] = $tanggal_beli_target;
		$offset_selesai = $offset + count($slice);
		$done = ($total_produksi === 0 || $offset_selesai >= $total_produksi);

		$CI->session->set_userdata($state_key, $state);

		if ($done) {
			if (!empty($state['produksi_accum'])) {
				persediaan_recalculate_flush_produksi_accum_to_db($CI, $state['produksi_accum']);
			}
			persediaan_gen_recalc_refresh_items_total_10_check($CI, $items_produksi);
			persediaan_gen_recalc_refresh_items_total_10_check($CI, $items_produksi_update);

			persediaan_gen_recalc_history_save_batch(
				$CI,
				$state,
				$items_persediaan,
				$items_pembelian,
				$items_pembelian_update,
				$items_pembelian_baru,
				$items_penjualan,
				$items_penjualan_update,
				$items_produksi,
				$items_produksi_update,
				$items_pecah_satuan,
				$items_pecah_satuan_update,
				$items_unit_produk,
				$items_unit_produk_update
			);

			$carry = array(
				'items_persediaan' => $items_persediaan,
				'items_pembelian' => $items_pembelian,
				'items_pembelian_update' => $items_pembelian_update,
				'items_pembelian_baru' => $items_pembelian_baru,
				'items_penjualan' => $items_penjualan,
				'items_penjualan_update' => $items_penjualan_update,
				'items_unit_produk' => $items_unit_produk,
				'items_unit_produk_update' => $items_unit_produk_update,
				'items_produksi' => $items_produksi,
				'items_produksi_update' => $items_produksi_update,
			);

			return persediaan_generate_recalculate_start_penjualan_phase(
				$CI, $bulan, $limit, $state, $state_key, $ctx, $count_sumber, $carry
			);
		}

		return array(
			'ok' => true,
			'phase' => 'produksi',
			'done' => false,
			'offset_selesai' => $offset_selesai,
			'total_phase' => $total_produksi,
			'items_persediaan' => $items_persediaan,
			'items_pembelian' => $items_pembelian,
			'items_pembelian_update' => $items_pembelian_update,
			'items_pembelian_baru' => $items_pembelian_baru,
			'items_penjualan' => $items_penjualan,
			'items_penjualan_update' => $items_penjualan_update,
			'items_unit_produk' => $items_unit_produk,
			'items_unit_produk_update' => $items_unit_produk_update,
			'items_produksi' => $items_produksi,
			'items_produksi_update' => $items_produksi_update,
			'stats' => $state['stats'],
			'pesan' => 'Recalculate produksi bahan fase 4: ' . $offset_selesai . ' / ' . $total_produksi . ' grup bahan produksi',
		);
	}

	if ($state['phase'] === 'pecah_satuan') {
		if ((int) $offset === 0) {
			persediaan_generate_recalculate_init_pecah_satuan_phase($CI, $ctx, $state);
		}
		if (!isset($state['pecah_accum']) || !is_array($state['pecah_accum'])) {
			$state['pecah_accum'] = array();
		}

		$queue = persediaan_generate_recalculate_get_pecah_satuan_queue($state, $CI, $ctx);
		$total_pecah = count($queue);
		$map = persediaan_generate_recalculate_get_map_cached(
			$state,
			$CI,
			$tanggal_beli_target,
			((int) $offset === 0)
		);

		$slice = array_slice($queue, $offset, $limit);
		foreach ($slice as $queue_item) {
			$item = persediaan_generate_recalculate_proses_pecah_satuan_row(
				$CI,
				$ctx,
				$queue_item,
				$map,
				$state['pecah_accum']
			);
			$items_pecah_satuan[] = $item;

			if ($item['aksi'] === 'SKIP') {
				$state['stats']['pecah_skip']++;
			} elseif ($item['aksi'] === 'TIDAK_COCOK') {
				$state['stats']['pecah_tidak_cocok']++;
			} elseif ($item['aksi'] === 'UPDATE_PECAH' || $item['aksi'] === 'UPDATE_PECAH_TANPA_TARGET') {
				$state['stats']['pecah_update']++;
				$items_pecah_satuan_update[] = $item;
			} elseif ($item['aksi'] === 'GAGAL') {
				$state['stats']['pecah_gagal']++;
			}
		}

		$offset_selesai = $offset + count($slice);
		$done = ($total_pecah === 0 || $offset_selesai >= $total_pecah);

		$state['persediaan_map_cache'] = $map;
		$state['persediaan_map_cache_tgl'] = $tanggal_beli_target;
		$CI->session->set_userdata($state_key, $state);

		if ($done) {
			persediaan_gen_recalc_refresh_items_total_10_check($CI, $items_pecah_satuan, 'id_persediaan_sumber', 'total_10_sumber');
			persediaan_gen_recalc_refresh_items_total_10_check($CI, $items_pecah_satuan_update, 'id_persediaan_sumber', 'total_10_sumber');

			persediaan_gen_recalc_history_save_batch(
				$CI,
				$state,
				$items_persediaan,
				$items_pembelian,
				$items_pembelian_update,
				$items_pembelian_baru,
				$items_penjualan,
				$items_penjualan_update,
				$items_produksi,
				$items_produksi_update,
				$items_pecah_satuan,
				$items_pecah_satuan_update,
				$items_unit_produk,
				$items_unit_produk_update
			);

			$carry = array(
				'items_persediaan' => $items_persediaan,
				'items_pembelian' => $items_pembelian,
				'items_pembelian_update' => $items_pembelian_update,
				'items_pembelian_baru' => $items_pembelian_baru,
				'items_penjualan' => $items_penjualan,
				'items_penjualan_update' => $items_penjualan_update,
				'items_unit_produk' => $items_unit_produk,
				'items_unit_produk_update' => $items_unit_produk_update,
				'items_produksi' => $items_produksi,
				'items_produksi_update' => $items_produksi_update,
				'items_pecah_satuan' => $items_pecah_satuan,
				'items_pecah_satuan_update' => $items_pecah_satuan_update,
			);

			$state['keluar_phases_done'] = 1;
			$CI->session->set_userdata($state_key, $state);
			return persediaan_generate_recalculate_finish_all(
				$CI, $bulan, $ctx, $count_sumber, $state, $state_key, $carry
			);
		}

		return array(
			'ok' => true,
			'phase' => 'pecah_satuan',
			'done' => false,
			'offset_selesai' => $offset_selesai,
			'total_phase' => $total_pecah,
			'items_persediaan' => $items_persediaan,
			'items_pembelian' => $items_pembelian,
			'items_pembelian_update' => $items_pembelian_update,
			'items_pembelian_baru' => $items_pembelian_baru,
			'items_penjualan' => $items_penjualan,
			'items_penjualan_update' => $items_penjualan_update,
			'items_produksi' => $items_produksi,
			'items_produksi_update' => $items_produksi_update,
			'items_unit_produk' => $items_unit_produk,
			'items_unit_produk_update' => $items_unit_produk_update,
			'items_pecah_satuan' => $items_pecah_satuan,
			'items_pecah_satuan_update' => $items_pecah_satuan_update,
			'stats' => $state['stats'],
			'pesan' => 'Recalculate pecah satuan fase 6: ' . $offset_selesai . ' / ' . $total_pecah . ' baris pecah satuan',
		);
	}

	return array('ok' => false, 'message' => 'Fase generate & recalculate tidak dikenali: ' . (isset($state['phase']) ? $state['phase'] : ''));
}

function persediaan_generate_recalculate_batch_response_meta($state)
{
	if (!is_array($state)) {
		return array();
	}

	return array(
		'reset_target' => (int) (isset($state['reset_target']) ? $state['reset_target'] : 0),
		'target_kosong_verified' => (int) (isset($state['target_kosong_verified']) ? $state['target_kosong_verified'] : 0),
		'tanggal_klik_generate' => isset($state['tanggal_klik_generate']) ? $state['tanggal_klik_generate'] : '',
		'history_generate_id' => (int) (isset($state['history_generate_id']) ? $state['history_generate_id'] : 0),
	);
}

/**
 * -------------------------------------------------------------------------
 * History Generate — persediaan_history_generate (+ data rekap)
 * -------------------------------------------------------------------------
 */
function persediaan_history_generate_table_exists($CI)
{
	static $cache = null;
	if ($cache !== null) {
		return $cache;
	}
	$cache = $CI->db->table_exists('persediaan_history_generate')
		&& $CI->db->table_exists('persediaan_history_generate_data');
	return $cache;
}

function persediaan_history_generate_ensure_tables($CI)
{
	if (persediaan_history_generate_table_exists($CI)) {
		return true;
	}

	$sql_paths = array(
		APPPATH . '../database/sql/persediaan_history_generate.sql',
	);
	if (defined('FCPATH')) {
		$sql_paths[] = FCPATH . '../database/sql/persediaan_history_generate.sql';
	}
	foreach ($sql_paths as $path) {
		if (!is_file($path)) {
			continue;
		}
		$sql = file_get_contents($path);
		if ($sql === false || trim($sql) === '') {
			continue;
		}
		$parts = preg_split('/;\s*\n/', $sql);
		foreach ($parts as $stmt) {
			$stmt = trim($stmt);
			if ($stmt === '' || stripos($stmt, 'CREATE TABLE') === false) {
				continue;
			}
			$CI->db->query($stmt);
		}
		break;
	}

	return persediaan_history_generate_table_exists($CI);
}

function persediaan_history_generate_rekap_jenis_definitions()
{
	return array(
		'persediaan_bulan_lalu' => 'Persediaan asli bulan sebelumnya',
		'persediaan_total_target' => 'Persediaan total bulan target',
		'pembelian_semua' => 'Pembelian semua',
		'pembelian_update_beli' => 'Pembelian update beli',
		'pembelian_insert_baru' => 'Pembelian insert baru',
		'persediaan_sumber_tidak_masuk' => 'Persediaan sumber tidak masuk',
		'pembelian_spop_multi' => 'Pembelian SPOP multi record',
		'pembelian_spop_single' => 'Pembelian SPOP 1 record',
	);
}

function persediaan_history_generate_start($CI, $ctx, $tanggal_klik_generate, &$state)
{
	if (!persediaan_history_generate_ensure_tables($CI)) {
		return 0;
	}

	$id_user = (int) $CI->session->userdata('sess_iduser');
	$nama_user = trim((string) $CI->session->userdata('sess_username'));

	$CI->db->insert('persediaan_history_generate', array(
		'bulan_target' => $ctx['bulan'],
		'tanggal_beli_target' => $ctx['tanggal_beli_target'],
		'tanggal_beli_sumber' => $ctx['tanggal_beli_sumber'],
		'tanggal_klik_generate' => $tanggal_klik_generate,
		'tanggal_selesai' => null,
		'reset_deleted_count' => (int) (isset($state['reset_target']) ? $state['reset_target'] : 0),
		'target_kosong_verified' => (int) (isset($state['target_kosong_verified']) ? $state['target_kosong_verified'] : 0),
		'id_gen_recalc_log' => !empty($state['log_id']) ? (int) $state['log_id'] : null,
		'status' => 'proses',
		'fase_terakhir' => 'generate',
		'id_user' => $id_user > 0 ? $id_user : null,
		'nama_user' => $nama_user !== '' ? $nama_user : null,
	));

	return (int) $CI->db->insert_id();
}

function persediaan_history_generate_append_data($CI, $history_id, $jenis, $judul, $rows, $totals = null)
{
	if ((int) $history_id < 1 || !persediaan_history_generate_table_exists($CI)) {
		return;
	}

	$data_json = json_encode(is_array($rows) ? $rows : array(), JSON_UNESCAPED_UNICODE);
	if ($data_json === false) {
		$data_json = '[]';
	}
	$totals_json = null;
	if (is_array($totals)) {
		$totals_json = json_encode($totals, JSON_UNESCAPED_UNICODE);
		if ($totals_json === false) {
			$totals_json = null;
		}
	}

	$CI->db->insert('persediaan_history_generate_data', array(
		'id_history' => (int) $history_id,
		'jenis' => (string) $jenis,
		'judul' => (string) $judul,
		'row_count' => is_array($rows) ? count($rows) : 0,
		'totals_json' => $totals_json,
		'data_json' => $data_json,
	));
}

function persediaan_history_generate_finish_from_batch($CI, &$state, $bulan, $summary, $summary_html = '')
{
	if (empty($state['history_generate_id']) || (int) $state['history_generate_id'] < 1) {
		return;
	}

	$summary_tables = persediaan_gen_recalc_summary_tables_load_safe($CI, $bulan);
	$proses_data = array();
	if (!empty($state['log_id'])) {
		$loaded = persediaan_gen_recalc_history_load_by_log_id($CI, (int) $state['log_id']);
		if (!empty($loaded['data']) && is_array($loaded['data'])) {
			$proses_data = $loaded['data'];
		}
	}

	persediaan_history_generate_finish(
		$CI,
		(int) $state['history_generate_id'],
		$summary,
		$summary_tables,
		$proses_data,
		$summary_html,
		$state
	);
}

function persediaan_history_generate_finish($CI, $history_id, $summary, $summary_tables, $proses_data, $summary_html, $state)
{
	if ((int) $history_id < 1 || !persediaan_history_generate_table_exists($CI)) {
		return;
	}

	$history_id = (int) $history_id;
	$CI->db->where('id', $history_id)->delete('persediaan_history_generate_data');

	$rekap_defs = persediaan_history_generate_rekap_jenis_definitions();
	if (is_array($summary_tables)) {
		foreach ($rekap_defs as $jenis => $judul) {
			$rows = isset($summary_tables[$jenis]) && is_array($summary_tables[$jenis]) ? $summary_tables[$jenis] : array();
			$totals_key = $jenis . '_totals';
			$totals = isset($summary_tables[$totals_key]) ? $summary_tables[$totals_key] : null;
			persediaan_history_generate_append_data($CI, $history_id, $jenis, $judul, $rows, $totals);
		}
		if (!empty($summary_tables['rekap_meta']) && is_array($summary_tables['rekap_meta'])) {
			persediaan_history_generate_append_data(
				$CI,
				$history_id,
				'rekap_meta',
				'Meta kolom rekap',
				array($summary_tables['rekap_meta']),
				null
			);
		}
	}

	if (is_array($proses_data)) {
		foreach (persediaan_gen_recalc_jenis_definitions() as $jenis => $def) {
			if (!isset($proses_data[$jenis]) || !is_array($proses_data[$jenis])) {
				continue;
			}
			$judul = isset($def['title']) ? $def['title'] : $jenis;
			persediaan_history_generate_append_data($CI, $history_id, 'proses_' . $jenis, $judul, $proses_data[$jenis], null);
		}
	}

	$summary_json = json_encode(is_array($summary) ? $summary : array(), JSON_UNESCAPED_UNICODE);
	$rekap_json = json_encode(is_array($summary_tables) ? $summary_tables : array(), JSON_UNESCAPED_UNICODE);
	$proses_json = json_encode(is_array($proses_data) ? $proses_data : array(), JSON_UNESCAPED_UNICODE);
	if ($summary_json === false) {
		$summary_json = null;
	}
	if ($rekap_json === false) {
		$rekap_json = null;
	}
	if ($proses_json === false) {
		$proses_json = null;
	}

	$fase = 'selesai';
	if (!empty($summary['pembelian_only'])) {
		$fase = 'pembelian';
	} elseif (!empty($summary['generate_only'])) {
		$fase = 'generate';
	}

	$CI->db->where('id', $history_id);
	$CI->db->update('persediaan_history_generate', array(
		'tanggal_selesai' => date('Y-m-d H:i:s'),
		'id_gen_recalc_log' => !empty($state['log_id']) ? (int) $state['log_id'] : null,
		'generate_insert' => (int) (isset($summary['generate_insert']) ? $summary['generate_insert'] : 0),
		'generate_update' => (int) (isset($summary['generate_update']) ? $summary['generate_update'] : 0),
		'pembelian_update' => (int) (isset($summary['pembelian_update']) ? $summary['pembelian_update'] : 0),
		'pembelian_insert' => (int) (isset($summary['pembelian_insert']) ? $summary['pembelian_insert'] : 0),
		'pembelian_gagal' => (int) (isset($summary['pembelian_gagal']) ? $summary['pembelian_gagal'] : 0),
		'total_pembelian' => (int) (isset($summary['total_pembelian']) ? $summary['total_pembelian'] : 0),
		'summary_json' => $summary_json,
		'rekap_json' => $rekap_json,
		'proses_json' => $proses_json,
		'summary_html' => $summary_html !== '' ? $summary_html : persediaan_gen_recalc_build_summary_html($summary),
		'status' => 'selesai',
		'fase_terakhir' => $fase,
	));
}

function persediaan_history_generate_list_by_bulan($CI, $bulan, $limit = 50)
{
	if (!persediaan_history_generate_ensure_tables($CI) || !preg_match('/^\d{4}-\d{2}$/', trim((string) $bulan))) {
		return array();
	}

	$limit = max(1, min(200, (int) $limit));
	return $CI->db->query(
		"SELECT `id`, `bulan_target`, `tanggal_beli_target`, `tanggal_beli_sumber`,
			`tanggal_klik_generate`, `tanggal_selesai`, `reset_deleted_count`, `target_kosong_verified`,
			`generate_insert`, `generate_update`, `pembelian_update`, `pembelian_insert`, `pembelian_gagal`,
			`total_pembelian`, `status`, `fase_terakhir`, `nama_user`
		FROM `persediaan_history_generate`
		WHERE `bulan_target` = ?
		ORDER BY `tanggal_klik_generate` DESC, `id` DESC
		LIMIT " . (int) $limit,
		array($bulan)
	)->result();
}

function persediaan_history_generate_load($CI, $history_id)
{
	if (!persediaan_history_generate_ensure_tables($CI) || (int) $history_id < 1) {
		return array('ok' => false, 'message' => 'History tidak ditemukan.');
	}

	$header = $CI->db->where('id', (int) $history_id)->limit(1)->get('persediaan_history_generate')->row();
	if (!$header) {
		return array('ok' => false, 'message' => 'History generate tidak ditemukan.');
	}

	$rows = $CI->db->query(
		"SELECT `jenis`, `judul`, `row_count`, `totals_json`, `data_json`
		FROM `persediaan_history_generate_data`
		WHERE `id_history` = ?
		ORDER BY `id` ASC",
		array((int) $history_id)
	)->result();

	$summary_tables = array('ok' => true);
	$proses_data = array();

	if (!empty($header->rekap_json)) {
		$decoded = json_decode($header->rekap_json, true);
		if (is_array($decoded)) {
			$summary_tables = array_merge($summary_tables, $decoded);
		}
	}

	foreach ($rows as $row) {
		$jenis = isset($row->jenis) ? $row->jenis : '';
		$data = json_decode(isset($row->data_json) ? $row->data_json : '[]', true);
		if (!is_array($data)) {
			$data = array();
		}
		$totals = null;
		if (!empty($row->totals_json)) {
			$totals = json_decode($row->totals_json, true);
		}

		if ($jenis === 'rekap_meta' && !empty($data[0]) && is_array($data[0])) {
			$summary_tables['rekap_meta'] = $data[0];
			continue;
		}

		if (strpos($jenis, 'proses_') === 0) {
			$pk = substr($jenis, 7);
			$proses_data[$pk] = $data;
			continue;
		}

		$summary_tables[$jenis] = $data;
		if (is_array($totals)) {
			$summary_tables[$jenis . '_totals'] = $totals;
		}
	}

	if (!empty($header->proses_json)) {
		$decoded_proses = json_decode($header->proses_json, true);
		if (is_array($decoded_proses)) {
			foreach ($decoded_proses as $k => $v) {
				if (!isset($proses_data[$k])) {
					$proses_data[$k] = $v;
				}
			}
		}
	}

	$summary = array();
	if (!empty($header->summary_json)) {
		$summary = json_decode($header->summary_json, true);
		if (!is_array($summary)) {
			$summary = array();
		}
	}

	$summary_tables['bulan'] = $header->bulan_target;
	$summary_tables['bulan_label'] = date('m/Y', strtotime($header->bulan_target . '-01'));
	$summary_tables['bulan_sumber_label'] = date('m/Y', strtotime($header->tanggal_beli_sumber));

	persediaan_gen_recalc_summary_rekap_meta_normalize($CI, $summary_tables);
	persediaan_gen_recalc_summary_tables_ensure_totals($CI, $summary_tables);

	return array(
		'ok' => true,
		'history_id' => (int) $header->id,
		'header' => array(
			'id' => (int) $header->id,
			'bulan_target' => $header->bulan_target,
			'tanggal_klik_generate' => $header->tanggal_klik_generate,
			'tanggal_selesai' => $header->tanggal_selesai,
			'reset_deleted_count' => (int) $header->reset_deleted_count,
			'target_kosong_verified' => (int) $header->target_kosong_verified,
			'status' => $header->status,
			'fase_terakhir' => $header->fase_terakhir,
			'nama_user' => isset($header->nama_user) ? $header->nama_user : '',
		),
		'summary' => $summary,
		'summary_html' => isset($header->summary_html) ? $header->summary_html : '',
		'summary_tables' => $summary_tables,
		'proses_data' => $proses_data,
		'has_history' => true,
		'data' => $proses_data,
		'created_at' => $header->tanggal_klik_generate,
		'nama_user' => isset($header->nama_user) ? $header->nama_user : '',
	);
}

/**
 * -------------------------------------------------------------------------
 * History Generate & Recalculate (tab Generate) — simpan / muat / export Excel
 * Tabel: persediaan_gen_recalc_log, persediaan_gen_recalc_item
 * -------------------------------------------------------------------------
 */
function persediaan_gen_recalc_table_exists($CI)
{
	static $cache = null;
	if ($cache !== null) {
		return $cache;
	}
	$cache = $CI->db->table_exists('persediaan_gen_recalc_log')
		&& $CI->db->table_exists('persediaan_gen_recalc_item');
	return $cache;
}

function persediaan_gen_recalc_gagal_row_normalize($fase, $aksi, $item)
{
	$item = is_array($item) ? $item : array();

	$id_sumber = '';
	if (isset($item['id_pembelian'])) {
		$id_sumber = $item['id_pembelian'];
	} elseif (isset($item['id_penjualan'])) {
		$id_sumber = $item['id_penjualan'];
	} elseif (isset($item['id_produksi_bahan'])) {
		$id_sumber = $item['id_produksi_bahan'];
	} elseif (isset($item['id_unit_produk'])) {
		$id_sumber = $item['id_unit_produk'];
	} elseif (isset($item['id_pecah_satuan'])) {
		$id_sumber = $item['id_pecah_satuan'];
	} elseif (isset($item['id_sumber'])) {
		$id_sumber = $item['id_sumber'];
	}

	$id_target = '';
	if (isset($item['id_persediaan'])) {
		$id_target = $item['id_persediaan'];
	} elseif (isset($item['id_target'])) {
		$id_target = $item['id_target'];
	} elseif (isset($item['id_persediaan_sumber'])) {
		$id_target = $item['id_persediaan_sumber'];
	}

	$jumlah = '';
	if (isset($item['jumlah_pembelian'])) {
		$jumlah = $item['jumlah_pembelian'];
	} elseif (isset($item['jumlah_penjualan'])) {
		$jumlah = $item['jumlah_penjualan'];
	} elseif (isset($item['jumlah_bahan'])) {
		$jumlah = $item['jumlah_bahan'];
	} elseif (isset($item['jumlah_produksi'])) {
		$jumlah = $item['jumlah_produksi'];
	} elseif (isset($item['jumlah_pecah'])) {
		$jumlah = $item['jumlah_pecah'];
	}

	return array(
		'fase' => $fase,
		'aksi' => $aksi,
		'tabel' => isset($item['tabel']) ? $item['tabel'] : '',
		'id_sumber' => $id_sumber,
		'id_target' => $id_target,
		'namabarang' => isset($item['namabarang']) ? $item['namabarang'] : '',
		'satuan' => isset($item['satuan']) ? $item['satuan'] : '',
		'hpp' => isset($item['hpp']) ? $item['hpp'] : '',
		'spop' => isset($item['spop']) ? $item['spop'] : '',
		'jumlah' => $jumlah,
		'keterangan' => isset($item['keterangan']) ? $item['keterangan'] : '',
	);
}

/**
 * Kumpulkan semua baris gagal dari snapshot proses generate/recalculate.
 */
function persediaan_gen_recalc_collect_gagal_generate_recalculate($data)
{
	$data = is_array($data) ? $data : array();
	$out = array();

	foreach (isset($data['generate_masalah']) ? $data['generate_masalah'] : array() as $item) {
		if (!is_array($item)) {
			continue;
		}
		$out[] = persediaan_gen_recalc_gagal_row_normalize(
			isset($item['fase']) ? $item['fase'] : 'generate',
			isset($item['status']) ? $item['status'] : 'MASALAH',
			$item
		);
	}

	$verifikasi_gagal_status = array('BEDA', 'TIDAK_ADA_TARGET', 'TARGET_EKSTRA');
	foreach (isset($data['generate_verifikasi']) ? $data['generate_verifikasi'] : array() as $item) {
		if (!is_array($item)) {
			continue;
		}
		$st = strtoupper(trim((string) (isset($item['status']) ? $item['status'] : '')));
		if (!in_array($st, $verifikasi_gagal_status, true)) {
			continue;
		}
		$out[] = persediaan_gen_recalc_gagal_row_normalize('generate_verifikasi', $st, $item);
	}

	$phase_fail_actions = array('GAGAL', 'TIDAK_COCOK');
	$phase_map = array(
		'pembelian' => 'pembelian',
		'penjualan' => 'penjualan',
		'unit_produk' => 'unit_produk',
		'produksi' => 'produksi',
		'pecah_satuan' => 'pecah_satuan',
	);

	foreach ($phase_map as $jenis => $fase_label) {
		foreach (isset($data[$jenis]) ? $data[$jenis] : array() as $item) {
			if (!is_array($item)) {
				continue;
			}
			$aksi = strtoupper(trim((string) (isset($item['aksi']) ? $item['aksi'] : '')));
			if (!in_array($aksi, $phase_fail_actions, true)) {
				continue;
			}
			$out[] = persediaan_gen_recalc_gagal_row_normalize($fase_label, $aksi, $item);
		}
	}

	return $out;
}

/**
 * Kumpulkan baris gagal insert/update ke tabel persediaan (produk jadi, pembelian baru, bahan produksi).
 */
function persediaan_gen_recalc_collect_gagal_insert_persediaan($data)
{
	$data = is_array($data) ? $data : array();
	$out = array();

	$phase_map = array(
		'pembelian' => array('fase' => 'pembelian', 'fail' => array('GAGAL')),
		'unit_produk' => array('fase' => 'unit_produk', 'fail' => array('GAGAL')),
		'produksi' => array('fase' => 'produksi', 'fail' => array('GAGAL', 'TIDAK_COCOK')),
		'pecah_satuan' => array('fase' => 'pecah_satuan', 'fail' => array('GAGAL')),
	);

	foreach ($phase_map as $jenis => $cfg) {
		foreach (isset($data[$jenis]) ? $data[$jenis] : array() as $item) {
			if (!is_array($item)) {
				continue;
			}
			$aksi = strtoupper(trim((string) (isset($item['aksi']) ? $item['aksi'] : '')));
			if (!in_array($aksi, $cfg['fail'], true)) {
				continue;
			}
			$out[] = persediaan_gen_recalc_gagal_row_normalize($cfg['fase'], $aksi, $item);
		}
	}

	return $out;
}

function persediaan_gen_recalc_jenis_definitions()
{
	return array(
		'persediaan_all' => array(
			'sheet' => '1 Persediaan All',
			'headers' => array('No', 'Aksi', 'ID', 'Nama Barang', 'Satuan', 'HPP', 'SPOP', 'SA', 'Beli', 'Total_10', 'Keterangan'),
		),
		'generate_update' => array(
			'sheet' => '2 Generate Update',
			'headers' => array('No', 'ID', 'Nama Barang', 'Satuan', 'HPP', 'SPOP', 'SA', 'Beli', 'Total_10', 'Keterangan'),
		),
		'generate_insert' => array(
			'sheet' => '3 Generate Insert',
			'headers' => array('No', 'ID', 'UUID', 'Nama Barang', 'Satuan', 'HPP', 'SPOP', 'SA', 'Total_10', 'Keterangan'),
		),
		'generate_verifikasi' => array(
			'sheet' => '3b Verifikasi Generate',
			'headers' => array('No', 'Status', 'ID Sumber', 'ID Target', 'Nama Barang', 'Satuan', 'HPP', 'SPOP', 'SA Sumber', 'Total_10 Field Sumber', 'SA Target', 'Beli Target', 'Penjualan Target', 'Total_10 Target', 'Keterangan'),
		),
		'generate_masalah' => array(
			'sheet' => '3c Generate Masalah',
			'headers' => array('No', 'Waktu Generate', 'Status', 'ID Sumber', 'ID Target', 'Nama Barang', 'Satuan', 'HPP', 'SPOP', 'Total_10 Sumber', 'Keterangan'),
		),
		'pembelian' => array(
			'sheet' => '4 Pembelian',
			'headers' => array('No', 'Aksi', 'Tabel', 'ID Pembelian', 'ID Persediaan', 'Nama', 'Satuan', 'HPP', 'SPOP', 'Jumlah', 'Beli Baru', 'Keterangan'),
		),
		'pembelian_update' => array(
			'sheet' => '5 Update Beli',
			'headers' => array('No', 'ID Pembelian', 'ID Persediaan', 'Nama', 'Jumlah', 'Beli Lama', 'Beli Baru', 'Total_10', 'Keterangan', 'Check Total_10'),
		),
		'pembelian_baru' => array(
			'sheet' => '6 Persediaan Baru',
			'headers' => array('No', 'ID Pembelian', 'ID Persediaan', 'Nama', 'Satuan', 'HPP', 'Beli', 'Keterangan'),
		),
		'penjualan' => array(
			'sheet' => '7 Penjualan',
			'headers' => array('No', 'Aksi', 'ID Penjualan', 'ID Persediaan', 'UUID Persediaan', 'Nama', 'Satuan', 'HPP', 'SPOP', 'Unit', 'Jumlah', 'Penjualan Baru', 'Total_10', 'Keterangan', 'Check Total_10'),
		),
		'penjualan_update' => array(
			'sheet' => '8 Update Penjualan',
			'headers' => array('No', 'ID Penjualan', 'ID Persediaan', 'UUID Persediaan', 'Nama', 'Unit', 'Jumlah', 'Penjualan Lama', 'Penjualan Baru', 'Unit Lama', 'Unit Baru', 'Total_10', 'Keterangan', 'Check Total_10'),
		),
		'produksi' => array(
			'sheet' => '9 Produksi Bahan',
			'headers' => array('No', 'Aksi', 'ID Bahan', 'ID Persediaan', 'Nama', 'Satuan', 'HPP', 'Unit Produksi', 'Jumlah Bahan', 'Bahan Produksi Baru', 'Total_10', 'Keterangan', 'Check Total_10'),
		),
		'produksi_update' => array(
			'sheet' => '10 Update Bahan Produksi',
			'headers' => array('No', 'ID Bahan', 'ID Persediaan', 'Nama', 'Unit Produksi', 'Jumlah Bahan', 'Bahan Lama', 'Bahan Baru', 'Total_10', 'Sisa Stock', 'Keterangan', 'Check Total_10'),
		),
		'unit_produk' => array(
			'sheet' => '9b Produk Jadi',
			'headers' => array('No', 'Aksi', 'ID Unit Produk', 'ID Persediaan', 'Nama', 'Satuan', 'HPP', 'SPOP', 'Unit', 'Jumlah Produksi', 'SA Baru', 'Total_10', 'Keterangan', 'Check Total_10'),
		),
		'unit_produk_update' => array(
			'sheet' => '9c Update Produk Jadi',
			'headers' => array('No', 'ID Unit Produk', 'ID Persediaan', 'Nama', 'Unit', 'Jumlah Produksi', 'SA Lama', 'SA Baru', 'Total_10', 'Keterangan', 'Check Total_10'),
		),
		'pecah_satuan' => array(
			'sheet' => '11 Pecah Satuan',
			'headers' => array('No', 'Aksi', 'ID Pecah', 'ID Sumber', 'ID Target', 'Nama Sumber', 'Satuan', 'HPP', 'Jumlah Pecah', 'Pecah Satuan Baru', 'Total_10 Sumber', 'Nama Baru', 'Satuan Baru', 'HPP Baru', 'Jumlah Baru', 'SA Target', 'Total_10 Target', 'Keterangan', 'Check Total_10'),
		),
		'pecah_satuan_update' => array(
			'sheet' => '12 Update Pecah Satuan',
			'headers' => array('No', 'ID Pecah', 'ID Sumber', 'ID Target', 'Nama Sumber', 'Nama Baru', 'Jumlah Pecah', 'Jumlah Baru', 'Pecah Satuan Baru', 'Total_10 Sumber', 'SA Target', 'Total_10 Target', 'Keterangan', 'Check Total_10'),
		),
		'gagal_generate_recalculate' => array(
			'sheet' => '99 Gagal Generate Recalc',
			'headers' => array('No', 'Fase', 'Aksi', 'Tabel', 'ID Sumber', 'ID Target', 'Nama', 'Satuan', 'HPP', 'SPOP', 'Jumlah', 'Keterangan'),
		),
		'gagal_insert_persediaan' => array(
			'sheet' => '98 Gagal Insert Persediaan',
			'headers' => array('No', 'Fase', 'Aksi', 'Tabel', 'ID Sumber', 'ID Target', 'Nama', 'Satuan', 'HPP', 'SPOP', 'Jumlah', 'Masalah / Error'),
		),
		'pembelian_spop_multi_status' => array(
			'sheet' => '13 Pembelian SPOP Multi',
			'headers' => array('No', 'Status Persediaan', 'ID Pembelian', 'Uraian', 'Satuan', 'Harga Satuan', 'Jumlah', 'SPOP', 'Tgl PO', 'Jumlah Record SPOP', 'Keterangan'),
		),
		'pembelian_spop_single_status' => array(
			'sheet' => '14 Pembelian SPOP Single',
			'headers' => array('No', 'Status Persediaan', 'ID Pembelian', 'Uraian', 'Satuan', 'Harga Satuan', 'Jumlah', 'SPOP', 'Tgl PO', 'Jumlah Record SPOP', 'Keterangan'),
		),
		'penjualan_berhasil' => array(
			'sheet' => '15 Penjualan Berhasil',
			'headers' => array('No', 'Status Persediaan', 'ID Penjualan', 'Nama Barang', 'Satuan', 'HPP', 'SPOP', 'UUID', 'Unit', 'Jumlah', 'Tgl Jual', 'ID Persediaan', 'Penjualan Lama', 'Penjualan Baru', 'Total_10', 'Keterangan'),
		),
		'penjualan_gagal' => array(
			'sheet' => '16 Penjualan Gagal',
			'headers' => array('No', 'Status Persediaan', 'ID Penjualan', 'Nama Barang', 'Satuan', 'HPP', 'SPOP', 'UUID', 'Unit', 'Jumlah', 'Tgl Jual', 'Aksi Proses', 'Keterangan'),
		),
		'penjualan_uuid_orphan' => array(
			'sheet' => '17 Penjualan UUID Orphan',
			'headers' => array('No', 'Status Referensi', 'ID Penjualan', 'Nama Barang', 'Satuan', 'HPP', 'SPOP', 'UUID', 'Unit', 'Jumlah', 'Tgl Jual', 'Ada di Pembelian', 'Ada di Persediaan', 'Keterangan'),
		),
	);
}

function persediaan_gen_recalc_hist_nomor_init()
{
	return array(
		'persediaan_all' => 0,
		'generate_update' => 0,
		'generate_insert' => 0,
		'generate_verifikasi' => 0,
		'generate_masalah' => 0,
		'pembelian' => 0,
		'pembelian_update' => 0,
		'pembelian_baru' => 0,
		'penjualan' => 0,
		'penjualan_update' => 0,
		'produksi' => 0,
		'produksi_update' => 0,
		'unit_produk' => 0,
		'unit_produk_update' => 0,
		'pecah_satuan' => 0,
		'pecah_satuan_update' => 0,
		'gagal_generate_recalculate' => 0,
		'gagal_insert_persediaan' => 0,
	);
}

function persediaan_gen_recalc_item_to_display_row($jenis, $item, $nomor)
{
	$item = is_array($item) ? $item : (array) $item;

	switch ($jenis) {
		case 'persediaan_all':
			return array(
				$nomor,
				isset($item['aksi']) ? $item['aksi'] : '',
				isset($item['id']) ? $item['id'] : '',
				isset($item['namabarang']) ? $item['namabarang'] : '',
				isset($item['satuan']) ? $item['satuan'] : '',
				isset($item['hpp']) ? $item['hpp'] : '',
				isset($item['spop']) ? $item['spop'] : '',
				isset($item['sa']) ? $item['sa'] : '',
				isset($item['beli']) ? $item['beli'] : '',
				isset($item['total_10']) ? $item['total_10'] : '',
				isset($item['keterangan']) ? $item['keterangan'] : '',
			);
		case 'generate_update':
			return array(
				$nomor,
				isset($item['id']) ? $item['id'] : '',
				isset($item['namabarang']) ? $item['namabarang'] : '',
				isset($item['satuan']) ? $item['satuan'] : '',
				isset($item['hpp']) ? $item['hpp'] : '',
				isset($item['spop']) ? $item['spop'] : '',
				isset($item['sa']) ? $item['sa'] : '',
				isset($item['beli']) ? $item['beli'] : '',
				isset($item['total_10']) ? $item['total_10'] : '',
				isset($item['keterangan']) ? $item['keterangan'] : '',
			);
		case 'generate_insert':
			return array(
				$nomor,
				isset($item['id']) ? $item['id'] : '',
				isset($item['uuid_persediaan']) ? $item['uuid_persediaan'] : '',
				isset($item['namabarang']) ? $item['namabarang'] : '',
				isset($item['satuan']) ? $item['satuan'] : '',
				isset($item['hpp']) ? $item['hpp'] : '',
				isset($item['spop']) ? $item['spop'] : '',
				isset($item['sa']) ? $item['sa'] : '',
				isset($item['total_10']) ? $item['total_10'] : '',
				isset($item['keterangan']) ? $item['keterangan'] : '',
			);
		case 'generate_verifikasi':
			return array(
				$nomor,
				isset($item['status']) ? $item['status'] : '',
				isset($item['id_sumber']) ? $item['id_sumber'] : '',
				isset($item['id_target']) ? $item['id_target'] : '',
				isset($item['namabarang']) ? $item['namabarang'] : '',
				isset($item['satuan']) ? $item['satuan'] : '',
				isset($item['hpp']) ? $item['hpp'] : '',
				isset($item['spop']) ? $item['spop'] : '',
				isset($item['sa_sumber']) ? $item['sa_sumber'] : '',
				isset($item['total_10_field_sumber']) ? $item['total_10_field_sumber'] : '',
				isset($item['sa_target']) ? $item['sa_target'] : '',
				isset($item['beli_target']) ? $item['beli_target'] : '',
				isset($item['penjualan_target']) ? $item['penjualan_target'] : '',
				isset($item['total_10_target']) ? $item['total_10_target'] : '',
				isset($item['keterangan']) ? $item['keterangan'] : '',
			);
		case 'generate_masalah':
			return array(
				$nomor,
				isset($item['waktu_generate']) ? $item['waktu_generate'] : '',
				isset($item['status']) ? $item['status'] : '',
				isset($item['id_sumber']) ? $item['id_sumber'] : '',
				isset($item['id_target']) ? $item['id_target'] : '',
				isset($item['namabarang']) ? $item['namabarang'] : '',
				isset($item['satuan']) ? $item['satuan'] : '',
				isset($item['hpp']) ? $item['hpp'] : '',
				isset($item['spop']) ? $item['spop'] : '',
				isset($item['total_10_sumber']) ? $item['total_10_sumber'] : (isset($item['total_10']) ? $item['total_10'] : ''),
				isset($item['keterangan']) ? $item['keterangan'] : '',
			);
		case 'gagal_generate_recalculate':
			return array(
				$nomor,
				isset($item['fase']) ? $item['fase'] : '',
				isset($item['aksi']) ? $item['aksi'] : '',
				isset($item['tabel']) ? $item['tabel'] : '',
				isset($item['id_sumber']) ? $item['id_sumber'] : '',
				isset($item['id_target']) ? $item['id_target'] : '',
				isset($item['namabarang']) ? $item['namabarang'] : '',
				isset($item['satuan']) ? $item['satuan'] : '',
				isset($item['hpp']) ? $item['hpp'] : '',
				isset($item['spop']) ? $item['spop'] : '',
				isset($item['jumlah']) ? $item['jumlah'] : '',
				isset($item['keterangan']) ? $item['keterangan'] : '',
			);
		case 'gagal_insert_persediaan':
			return array(
				$nomor,
				isset($item['fase']) ? $item['fase'] : '',
				isset($item['aksi']) ? $item['aksi'] : '',
				isset($item['tabel']) ? $item['tabel'] : '',
				isset($item['id_sumber']) ? $item['id_sumber'] : '',
				isset($item['id_target']) ? $item['id_target'] : '',
				isset($item['namabarang']) ? $item['namabarang'] : '',
				isset($item['satuan']) ? $item['satuan'] : '',
				isset($item['hpp']) ? $item['hpp'] : '',
				isset($item['spop']) ? $item['spop'] : '',
				isset($item['jumlah']) ? $item['jumlah'] : '',
				isset($item['keterangan']) ? $item['keterangan'] : '',
			);
		case 'pembelian':
			return array(
				$nomor,
				isset($item['aksi']) ? $item['aksi'] : '',
				isset($item['tabel']) ? $item['tabel'] : '',
				isset($item['id_pembelian']) ? $item['id_pembelian'] : '',
				isset($item['id_persediaan']) ? $item['id_persediaan'] : '',
				isset($item['namabarang']) ? $item['namabarang'] : '',
				isset($item['satuan']) ? $item['satuan'] : '',
				isset($item['hpp']) ? $item['hpp'] : '',
				isset($item['spop']) ? $item['spop'] : '',
				isset($item['jumlah_pembelian']) ? $item['jumlah_pembelian'] : '',
				isset($item['beli_baru']) ? $item['beli_baru'] : '',
				isset($item['keterangan']) ? $item['keterangan'] : '',
			);
		case 'pembelian_update':
			return array(
				$nomor,
				isset($item['id_pembelian']) ? $item['id_pembelian'] : '',
				isset($item['id_persediaan']) ? $item['id_persediaan'] : '',
				isset($item['namabarang']) ? $item['namabarang'] : '',
				isset($item['jumlah_pembelian']) ? $item['jumlah_pembelian'] : '',
				isset($item['beli_lama']) ? $item['beli_lama'] : '',
				isset($item['beli_baru']) ? $item['beli_baru'] : '',
				isset($item['total_10']) ? $item['total_10'] : '',
				isset($item['keterangan']) ? $item['keterangan'] : '',
				isset($item['keterangan_check']) ? $item['keterangan_check'] : '',
			);
		case 'pembelian_baru':
			return array(
				$nomor,
				isset($item['id_pembelian']) ? $item['id_pembelian'] : '',
				isset($item['id_persediaan']) ? $item['id_persediaan'] : '',
				isset($item['namabarang']) ? $item['namabarang'] : '',
				isset($item['satuan']) ? $item['satuan'] : '',
				isset($item['hpp']) ? $item['hpp'] : '',
				isset($item['beli_baru']) ? $item['beli_baru'] : '',
				isset($item['keterangan']) ? $item['keterangan'] : '',
			);
		case 'penjualan':
			return array(
				$nomor,
				isset($item['aksi']) ? $item['aksi'] : '',
				isset($item['id_penjualan']) ? $item['id_penjualan'] : '',
				isset($item['id_persediaan']) ? $item['id_persediaan'] : '',
				isset($item['uuid_persediaan']) ? $item['uuid_persediaan'] : '',
				isset($item['namabarang']) ? $item['namabarang'] : '',
				isset($item['satuan']) ? $item['satuan'] : '',
				isset($item['hpp']) ? $item['hpp'] : '',
				isset($item['spop']) ? $item['spop'] : '',
				isset($item['nama_unit']) ? $item['nama_unit'] : '',
				isset($item['jumlah_penjualan']) ? $item['jumlah_penjualan'] : '',
				isset($item['penjualan_baru']) ? $item['penjualan_baru'] : '',
				isset($item['total_10']) ? $item['total_10'] : '',
				isset($item['keterangan']) ? $item['keterangan'] : '',
				isset($item['keterangan_check']) ? $item['keterangan_check'] : '',
			);
		case 'penjualan_update':
			return array(
				$nomor,
				isset($item['id_penjualan']) ? $item['id_penjualan'] : '',
				isset($item['id_persediaan']) ? $item['id_persediaan'] : '',
				isset($item['uuid_persediaan']) ? $item['uuid_persediaan'] : '',
				isset($item['namabarang']) ? $item['namabarang'] : '',
				isset($item['nama_unit']) ? $item['nama_unit'] : '',
				isset($item['jumlah_penjualan']) ? $item['jumlah_penjualan'] : '',
				isset($item['penjualan_lama']) ? $item['penjualan_lama'] : '',
				isset($item['penjualan_baru']) ? $item['penjualan_baru'] : '',
				isset($item['unit_lama']) ? $item['unit_lama'] : '',
				isset($item['unit_baru']) ? $item['unit_baru'] : '',
				isset($item['total_10']) ? $item['total_10'] : '',
				isset($item['keterangan']) ? $item['keterangan'] : '',
				isset($item['keterangan_check']) ? $item['keterangan_check'] : '',
			);
		case 'produksi':
			return array(
				$nomor,
				isset($item['aksi']) ? $item['aksi'] : '',
				isset($item['id_produksi_bahan']) ? $item['id_produksi_bahan'] : '',
				isset($item['id_persediaan']) ? $item['id_persediaan'] : '',
				isset($item['namabarang']) ? $item['namabarang'] : '',
				isset($item['satuan']) ? $item['satuan'] : '',
				isset($item['hpp']) ? $item['hpp'] : '',
				isset($item['nama_unit']) ? $item['nama_unit'] : '',
				isset($item['jumlah_bahan']) ? $item['jumlah_bahan'] : '',
				isset($item['bahan_produksi_baru']) ? $item['bahan_produksi_baru'] : '',
				isset($item['total_10']) ? $item['total_10'] : '',
				isset($item['keterangan']) ? $item['keterangan'] : '',
				isset($item['keterangan_check']) ? $item['keterangan_check'] : '',
			);
		case 'produksi_update':
			return array(
				$nomor,
				isset($item['id_produksi_bahan']) ? $item['id_produksi_bahan'] : '',
				isset($item['id_persediaan']) ? $item['id_persediaan'] : '',
				isset($item['namabarang']) ? $item['namabarang'] : '',
				isset($item['nama_unit']) ? $item['nama_unit'] : '',
				isset($item['jumlah_bahan']) ? $item['jumlah_bahan'] : '',
				isset($item['bahan_produksi_lama']) ? $item['bahan_produksi_lama'] : '',
				isset($item['bahan_produksi_baru']) ? $item['bahan_produksi_baru'] : '',
				isset($item['total_10']) ? $item['total_10'] : '',
				isset($item['sisa_stock']) ? $item['sisa_stock'] : '',
				isset($item['keterangan']) ? $item['keterangan'] : '',
				isset($item['keterangan_check']) ? $item['keterangan_check'] : '',
			);
		case 'unit_produk':
			return array(
				$nomor,
				isset($item['aksi']) ? $item['aksi'] : '',
				isset($item['id_unit_produk']) ? $item['id_unit_produk'] : '',
				isset($item['id_persediaan']) ? $item['id_persediaan'] : '',
				isset($item['namabarang']) ? $item['namabarang'] : '',
				isset($item['satuan']) ? $item['satuan'] : '',
				isset($item['hpp']) ? $item['hpp'] : '',
				isset($item['spop']) ? $item['spop'] : '',
				isset($item['nama_unit']) ? $item['nama_unit'] : '',
				isset($item['jumlah_produksi']) ? $item['jumlah_produksi'] : '',
				isset($item['sa_baru']) ? $item['sa_baru'] : '',
				isset($item['total_10']) ? $item['total_10'] : '',
				isset($item['keterangan']) ? $item['keterangan'] : '',
				isset($item['keterangan_check']) ? $item['keterangan_check'] : '',
			);
		case 'unit_produk_update':
			return array(
				$nomor,
				isset($item['id_unit_produk']) ? $item['id_unit_produk'] : '',
				isset($item['id_persediaan']) ? $item['id_persediaan'] : '',
				isset($item['namabarang']) ? $item['namabarang'] : '',
				isset($item['nama_unit']) ? $item['nama_unit'] : '',
				isset($item['jumlah_produksi']) ? $item['jumlah_produksi'] : '',
				isset($item['sa_lama']) ? $item['sa_lama'] : '',
				isset($item['sa_baru']) ? $item['sa_baru'] : '',
				isset($item['total_10']) ? $item['total_10'] : '',
				isset($item['keterangan']) ? $item['keterangan'] : '',
				isset($item['keterangan_check']) ? $item['keterangan_check'] : '',
			);
		case 'pecah_satuan':
			return array(
				$nomor,
				isset($item['aksi']) ? $item['aksi'] : '',
				isset($item['id_pecah_satuan']) ? $item['id_pecah_satuan'] : '',
				isset($item['id_persediaan_sumber']) ? $item['id_persediaan_sumber'] : '',
				isset($item['id_persediaan_target']) ? $item['id_persediaan_target'] : '',
				isset($item['namabarang']) ? $item['namabarang'] : '',
				isset($item['satuan']) ? $item['satuan'] : '',
				isset($item['hpp']) ? $item['hpp'] : '',
				isset($item['jumlah_pecah']) ? $item['jumlah_pecah'] : '',
				isset($item['pecah_satuan_baru']) ? $item['pecah_satuan_baru'] : '',
				isset($item['total_10_sumber']) ? $item['total_10_sumber'] : '',
				isset($item['nama_barang_baru']) ? $item['nama_barang_baru'] : '',
				isset($item['satuan_barang_baru']) ? $item['satuan_barang_baru'] : '',
				isset($item['hpp_barang_baru']) ? $item['hpp_barang_baru'] : '',
				isset($item['jumlah_barang_baru']) ? $item['jumlah_barang_baru'] : '',
				isset($item['sa_target']) ? $item['sa_target'] : '',
				isset($item['total_10_target']) ? $item['total_10_target'] : '',
				isset($item['keterangan']) ? $item['keterangan'] : '',
				isset($item['keterangan_check']) ? $item['keterangan_check'] : '',
			);
		case 'pecah_satuan_update':
			return array(
				$nomor,
				isset($item['id_pecah_satuan']) ? $item['id_pecah_satuan'] : '',
				isset($item['id_persediaan_sumber']) ? $item['id_persediaan_sumber'] : '',
				isset($item['id_persediaan_target']) ? $item['id_persediaan_target'] : '',
				isset($item['namabarang']) ? $item['namabarang'] : '',
				isset($item['nama_barang_baru']) ? $item['nama_barang_baru'] : '',
				isset($item['jumlah_pecah']) ? $item['jumlah_pecah'] : '',
				isset($item['jumlah_barang_baru']) ? $item['jumlah_barang_baru'] : '',
				isset($item['pecah_satuan_baru']) ? $item['pecah_satuan_baru'] : '',
				isset($item['total_10_sumber']) ? $item['total_10_sumber'] : '',
				isset($item['sa_target']) ? $item['sa_target'] : '',
				isset($item['total_10_target']) ? $item['total_10_target'] : '',
				isset($item['keterangan']) ? $item['keterangan'] : '',
				isset($item['keterangan_check']) ? $item['keterangan_check'] : '',
			);
		case 'pembelian_spop_multi_status':
		case 'pembelian_spop_single_status':
			$no_tampil = isset($item['no']) && $item['no'] !== '' ? $item['no'] : $nomor;
			if (!empty($item['row_type']) && $item['row_type'] === 'subtotal') {
				$no_tampil = '';
			}
			return array(
				$no_tampil,
				isset($item['status_persediaan']) ? $item['status_persediaan'] : '',
				isset($item['id_pembelian']) ? $item['id_pembelian'] : '',
				isset($item['uraian']) ? $item['uraian'] : '',
				isset($item['satuan']) ? $item['satuan'] : '',
				isset($item['harga_satuan']) ? $item['harga_satuan'] : '',
				isset($item['jumlah']) ? $item['jumlah'] : '',
				isset($item['spop']) ? $item['spop'] : '',
				isset($item['tgl_po']) ? $item['tgl_po'] : '',
				isset($item['record_grup']) ? $item['record_grup'] : '',
				isset($item['keterangan_baris']) ? $item['keterangan_baris'] : '',
			);
		case 'penjualan_berhasil':
			return array(
				$nomor,
				isset($item['status_persediaan']) ? $item['status_persediaan'] : '',
				isset($item['id_penjualan']) ? $item['id_penjualan'] : '',
				isset($item['nama_barang']) ? $item['nama_barang'] : '',
				isset($item['satuan']) ? $item['satuan'] : '',
				isset($item['harga_satuan']) ? $item['harga_satuan'] : '',
				isset($item['spop']) ? $item['spop'] : '',
				isset($item['uuid_persediaan']) ? $item['uuid_persediaan'] : '',
				isset($item['unit']) ? $item['unit'] : '',
				isset($item['jumlah']) ? $item['jumlah'] : '',
				isset($item['tgl_jual']) ? $item['tgl_jual'] : '',
				isset($item['id_persediaan']) ? $item['id_persediaan'] : '',
				isset($item['penjualan_lama']) ? $item['penjualan_lama'] : '',
				isset($item['penjualan_baru']) ? $item['penjualan_baru'] : '',
				isset($item['total_10']) ? $item['total_10'] : '',
				isset($item['keterangan']) ? $item['keterangan'] : '',
			);
		case 'penjualan_gagal':
			return array(
				$nomor,
				isset($item['status_persediaan']) ? $item['status_persediaan'] : '',
				isset($item['id_penjualan']) ? $item['id_penjualan'] : '',
				isset($item['nama_barang']) ? $item['nama_barang'] : '',
				isset($item['satuan']) ? $item['satuan'] : '',
				isset($item['harga_satuan']) ? $item['harga_satuan'] : '',
				isset($item['spop']) ? $item['spop'] : '',
				isset($item['uuid_persediaan']) ? $item['uuid_persediaan'] : '',
				isset($item['unit']) ? $item['unit'] : '',
				isset($item['jumlah']) ? $item['jumlah'] : '',
				isset($item['tgl_jual']) ? $item['tgl_jual'] : '',
				isset($item['aksi_proses']) ? $item['aksi_proses'] : '',
				isset($item['keterangan']) ? $item['keterangan'] : '',
			);
		case 'penjualan_uuid_orphan':
			return array(
				$nomor,
				isset($item['status_referensi']) ? $item['status_referensi'] : (isset($item['status_persediaan']) ? $item['status_persediaan'] : ''),
				isset($item['id_penjualan']) ? $item['id_penjualan'] : '',
				isset($item['nama_barang']) ? $item['nama_barang'] : '',
				isset($item['satuan']) ? $item['satuan'] : '',
				isset($item['harga_satuan']) ? $item['harga_satuan'] : '',
				isset($item['spop']) ? $item['spop'] : '',
				isset($item['uuid_persediaan']) ? $item['uuid_persediaan'] : '',
				isset($item['unit']) ? $item['unit'] : '',
				isset($item['jumlah']) ? $item['jumlah'] : '',
				isset($item['tgl_jual']) ? $item['tgl_jual'] : '',
				isset($item['ada_di_pembelian']) ? $item['ada_di_pembelian'] : 'Tidak',
				isset($item['ada_di_persediaan']) ? $item['ada_di_persediaan'] : 'Tidak',
				isset($item['keterangan']) ? $item['keterangan'] : '',
			);
		default:
			return array($nomor);
	}
}

function persediaan_gen_recalc_build_summary_html($summary)
{
	$summary = is_array($summary) ? $summary : array();
	$bulan_label = isset($summary['bulan_label']) ? $summary['bulan_label'] : '';
	$sumber_label = isset($summary['bulan_sumber_label']) ? $summary['bulan_sumber_label'] : '';
	$reset_line = '';
	if ((int) (isset($summary['reset_target']) ? $summary['reset_target'] : 0) > 0
		|| (int) (isset($summary['target_kosong_verified']) ? $summary['target_kosong_verified'] : 0) === 1) {
		$reset_line = 'Hapus data bulan target: <strong>' . (int) (isset($summary['reset_target']) ? $summary['reset_target'] : 0) . '</strong> record'
			. ((int) (isset($summary['target_kosong_verified']) ? $summary['target_kosong_verified'] : 0) === 1
				? ' <span class="text-success">(tanggal_beli kosong diverifikasi)</span>'
				: ' <span class="text-warning">(verifikasi kosong belum lengkap)</span>')
			. '<br/>';
	}

	return '<strong>Generate &amp; Recalculate selesai</strong><br/>'
		. $reset_line
		. 'Bulan target: <strong>' . htmlspecialchars($bulan_label, ENT_QUOTES, 'UTF-8') . '</strong> '
		. '(sumber: ' . htmlspecialchars($sumber_label, ENT_QUOTES, 'UTF-8') . ')<br/>'
		. 'Generate — Insert: <strong>' . (int) (isset($summary['generate_insert']) ? $summary['generate_insert'] : 0) . '</strong>, '
		. 'Update: <strong>' . (int) (isset($summary['generate_update']) ? $summary['generate_update'] : 0) . '</strong><br/>'
		. persediaan_gen_recalc_pembelian_summary_html_line($summary)
		. '<br/>Penjualan diproses: <strong>' . (int) (isset($summary['total_penjualan']) ? $summary['total_penjualan'] : 0) . '</strong> — '
		. 'Update penjualan: <strong>' . (int) (isset($summary['penjualan_update']) ? $summary['penjualan_update'] : 0) . '</strong>'
		. ((int) (isset($summary['penjualan_tidak_cocok']) ? $summary['penjualan_tidak_cocok'] : 0) > 0
			? ', Tidak cocok: <strong>' . (int) $summary['penjualan_tidak_cocok'] . '</strong>' : '')
		. ((int) (isset($summary['penjualan_gagal']) ? $summary['penjualan_gagal'] : 0) > 0
			? ', Gagal: <strong>' . (int) $summary['penjualan_gagal'] . '</strong>' : '')
		. ((int) (isset($summary['penjualan_uuid_orphan_count']) ? $summary['penjualan_uuid_orphan_count'] : 0) > 0
			? '<br/>UUID penjualan tanpa referensi pembelian/persediaan: <strong class="text-warning">' . (int) $summary['penjualan_uuid_orphan_count'] . '</strong> record — lihat tabel <em>12. Penjualan — UUID Tidak Ada di Pembelian &amp; Persediaan</em>'
			: '')
		. '<br/>Produksi bahan diproses: <strong>' . (int) (isset($summary['total_produksi']) ? $summary['total_produksi'] : 0) . '</strong> — '
		. 'Update bahan_produksi: <strong>' . (int) (isset($summary['produksi_update']) ? $summary['produksi_update'] : 0) . '</strong>'
		. ((int) (isset($summary['produksi_tidak_cocok']) ? $summary['produksi_tidak_cocok'] : 0) > 0
			? ', Tidak cocok: <strong>' . (int) $summary['produksi_tidak_cocok'] . '</strong>' : '')
		. ((int) (isset($summary['produksi_gagal']) ? $summary['produksi_gagal'] : 0) > 0
			? ', Gagal: <strong>' . (int) $summary['produksi_gagal'] . '</strong>' : '')
		. '<br/>Pecah satuan diproses: <strong>' . (int) (isset($summary['total_pecah_satuan']) ? $summary['total_pecah_satuan'] : 0) . '</strong> — '
		. 'Update pecah: <strong>' . (int) (isset($summary['pecah_update']) ? $summary['pecah_update'] : 0) . '</strong>'
		. ((int) (isset($summary['pecah_tidak_cocok']) ? $summary['pecah_tidak_cocok'] : 0) > 0
			? ', Tidak cocok: <strong>' . (int) $summary['pecah_tidak_cocok'] . '</strong>' : '')
		. ((int) (isset($summary['pecah_gagal']) ? $summary['pecah_gagal'] : 0) > 0
			? ', Gagal: <strong>' . (int) $summary['pecah_gagal'] . '</strong>' : '');
}

function persediaan_gen_recalc_history_start($CI, $ctx, $count_sumber)
{
	if (!persediaan_gen_recalc_table_exists($CI)) {
		return 0;
	}

	$id_user = (int) $CI->session->userdata('sess_iduser');
	$nama_user = trim((string) $CI->session->userdata('sess_username'));

	$CI->db->insert('persediaan_gen_recalc_log', array(
		'bulan_target' => $ctx['bulan'],
		'tanggal_beli_target' => $ctx['tanggal_beli_target'],
		'tanggal_beli_sumber' => $ctx['tanggal_beli_sumber'],
		'total_sumber' => (int) $count_sumber,
		'total_pembelian' => 0,
		'generate_insert' => 0,
		'generate_update' => 0,
		'pembelian_update' => 0,
		'pembelian_insert' => 0,
		'pembelian_gagal' => 0,
		'summary_html' => null,
		'id_user' => $id_user > 0 ? $id_user : null,
		'nama_user' => $nama_user !== '' ? $nama_user : null,
		'created_at' => date('Y-m-d H:i:s'),
	));

	return (int) $CI->db->insert_id();
}

function persediaan_gen_recalc_history_append_items($CI, $log_id, $jenis, $items, &$hist_nomor)
{
	if ((int) $log_id < 1 || empty($items) || !is_array($items)) {
		return;
	}
	if (!isset($hist_nomor[$jenis])) {
		$hist_nomor[$jenis] = 0;
	}

	$batch = array();
	foreach ($items as $item) {
		$hist_nomor[$jenis]++;
		$json = json_encode($item, JSON_UNESCAPED_UNICODE);
		if ($json === false) {
			continue;
		}
		$batch[] = array(
			'id_log' => (int) $log_id,
			'jenis' => $jenis,
			'nomor' => (int) $hist_nomor[$jenis],
			'item_json' => $json,
		);
	}

	if (empty($batch)) {
		return;
	}

	$CI->db->insert_batch('persediaan_gen_recalc_item', $batch);
}

/**
 * Simpan snapshot tabel gagal ke history (supaya tetap tampil setelah refresh halaman).
 */
function persediaan_gen_recalc_history_save_gagal_snapshots($CI, $log_id, &$hist_nomor, array $data)
{
	$log_id = (int) $log_id;
	if ($log_id < 1 || !persediaan_gen_recalc_table_exists($CI)) {
		return;
	}

	if (!is_array($hist_nomor)) {
		$hist_nomor = persediaan_gen_recalc_hist_nomor_init();
	}

	$CI->db->where('id_log', $log_id);
	$CI->db->where_in('jenis', array('gagal_generate_recalculate', 'gagal_insert_persediaan'));
	$CI->db->delete('persediaan_gen_recalc_item');

	$hist_nomor['gagal_generate_recalculate'] = 0;
	$hist_nomor['gagal_insert_persediaan'] = 0;

	if (!empty($data['gagal_generate_recalculate']) && is_array($data['gagal_generate_recalculate'])) {
		$gagal = $data['gagal_generate_recalculate'];
	} else {
		$gagal = persediaan_gen_recalc_collect_gagal_generate_recalculate($data);
	}

	if (!empty($data['gagal_insert_persediaan']) && is_array($data['gagal_insert_persediaan'])) {
		$gagal_ins = $data['gagal_insert_persediaan'];
	} else {
		$gagal_ins = persediaan_gen_recalc_collect_gagal_insert_persediaan($data);
	}

	persediaan_gen_recalc_history_append_items($CI, $log_id, 'gagal_generate_recalculate', $gagal, $hist_nomor);
	persediaan_gen_recalc_history_append_items($CI, $log_id, 'gagal_insert_persediaan', $gagal_ins, $hist_nomor);
}

function persediaan_gen_recalc_history_merge_gagal_from_stored(array $data, array $rows_by_jenis)
{
	$data = is_array($data) ? $data : array();

	if (!empty($rows_by_jenis['gagal_generate_recalculate'])) {
		$data['gagal_generate_recalculate'] = $rows_by_jenis['gagal_generate_recalculate'];
	} else {
		$data['gagal_generate_recalculate'] = persediaan_gen_recalc_collect_gagal_generate_recalculate($data);
	}

	if (!empty($rows_by_jenis['gagal_insert_persediaan'])) {
		$data['gagal_insert_persediaan'] = $rows_by_jenis['gagal_insert_persediaan'];
	} else {
		$data['gagal_insert_persediaan'] = persediaan_gen_recalc_collect_gagal_insert_persediaan($data);
	}

	return $data;
}

function persediaan_gen_recalc_history_save_batch($CI, &$state, $items_persediaan, $items_pembelian, $items_pembelian_update, $items_pembelian_baru, $items_penjualan = array(), $items_penjualan_update = array(), $items_produksi = array(), $items_produksi_update = array(), $items_pecah_satuan = array(), $items_pecah_satuan_update = array(), $items_unit_produk = array(), $items_unit_produk_update = array())
{
	if (empty($state['log_id']) || !persediaan_gen_recalc_table_exists($CI)) {
		return;
	}

	if (!isset($state['hist_nomor']) || !is_array($state['hist_nomor'])) {
		$state['hist_nomor'] = persediaan_gen_recalc_hist_nomor_init();
	}

	persediaan_gen_recalc_history_append_items($CI, $state['log_id'], 'persediaan_all', $items_persediaan, $state['hist_nomor']);

	$upd = array();
	$ins = array();
	foreach ($items_persediaan as $it) {
		if (!is_array($it)) {
			continue;
		}
		if (isset($it['aksi']) && $it['aksi'] === 'UPDATE') {
			$upd[] = $it;
		} elseif (isset($it['aksi']) && $it['aksi'] === 'INSERT') {
			$ins[] = $it;
		}
	}
	persediaan_gen_recalc_history_append_items($CI, $state['log_id'], 'generate_update', $upd, $state['hist_nomor']);
	persediaan_gen_recalc_history_append_items($CI, $state['log_id'], 'generate_insert', $ins, $state['hist_nomor']);
	persediaan_gen_recalc_history_append_items($CI, $state['log_id'], 'pembelian', $items_pembelian, $state['hist_nomor']);
	persediaan_gen_recalc_history_append_items($CI, $state['log_id'], 'pembelian_update', $items_pembelian_update, $state['hist_nomor']);
	persediaan_gen_recalc_history_append_items($CI, $state['log_id'], 'pembelian_baru', $items_pembelian_baru, $state['hist_nomor']);
	persediaan_gen_recalc_history_append_items($CI, $state['log_id'], 'penjualan', $items_penjualan, $state['hist_nomor']);
	persediaan_gen_recalc_history_append_items($CI, $state['log_id'], 'penjualan_update', $items_penjualan_update, $state['hist_nomor']);
	persediaan_gen_recalc_history_append_items($CI, $state['log_id'], 'produksi', $items_produksi, $state['hist_nomor']);
	persediaan_gen_recalc_history_append_items($CI, $state['log_id'], 'produksi_update', $items_produksi_update, $state['hist_nomor']);
	persediaan_gen_recalc_history_append_items($CI, $state['log_id'], 'unit_produk', $items_unit_produk, $state['hist_nomor']);
	persediaan_gen_recalc_history_append_items($CI, $state['log_id'], 'unit_produk_update', $items_unit_produk_update, $state['hist_nomor']);
	persediaan_gen_recalc_history_append_items($CI, $state['log_id'], 'pecah_satuan', $items_pecah_satuan, $state['hist_nomor']);
	persediaan_gen_recalc_history_append_items($CI, $state['log_id'], 'pecah_satuan_update', $items_pecah_satuan_update, $state['hist_nomor']);
}

function persediaan_gen_recalc_history_finish($CI, $log_id, $summary, $summary_html = '')
{
	if ((int) $log_id < 1 || !persediaan_gen_recalc_table_exists($CI)) {
		return;
	}

	$summary = is_array($summary) ? $summary : array();
	if ($summary_html === '') {
		$summary_html = persediaan_gen_recalc_build_summary_html($summary);
	}

	$CI->db->where('id', (int) $log_id);
	$CI->db->update('persediaan_gen_recalc_log', array(
		'total_pembelian' => (int) (isset($summary['total_pembelian']) ? $summary['total_pembelian'] : 0),
		'generate_insert' => (int) (isset($summary['generate_insert']) ? $summary['generate_insert'] : 0),
		'generate_update' => (int) (isset($summary['generate_update']) ? $summary['generate_update'] : 0),
		'pembelian_update' => (int) (isset($summary['pembelian_update']) ? $summary['pembelian_update'] : 0),
		'pembelian_insert' => (int) (isset($summary['pembelian_insert']) ? $summary['pembelian_insert'] : 0),
		'pembelian_gagal' => (int) (isset($summary['pembelian_gagal']) ? $summary['pembelian_gagal'] : 0),
		'summary_html' => $summary_html,
	));
}

function persediaan_gen_recalc_history_get_log($CI, $bulan)
{
	if (!persediaan_gen_recalc_table_exists($CI) || !preg_match('/^\d{4}-\d{2}$/', $bulan)) {
		return null;
	}

	return $CI->db->query(
		"SELECT * FROM `persediaan_gen_recalc_log`
		WHERE `bulan_target` = ?
		ORDER BY `created_at` DESC, `id` DESC
		LIMIT 1",
		array($bulan)
	)->row();
}

function persediaan_gen_recalc_history_load_by_log_id($CI, $log_id)
{
	if (!persediaan_gen_recalc_table_exists($CI) || (int) $log_id < 1) {
		return array('ok' => false, 'has_history' => false, 'data' => array());
	}

	$log = $CI->db->where('id', (int) $log_id)->limit(1)->get('persediaan_gen_recalc_log')->row();
	if (!$log) {
		return array('ok' => false, 'has_history' => false, 'data' => array());
	}

	$rows = $CI->db->query(
		"SELECT `jenis`, `item_json` FROM `persediaan_gen_recalc_item`
		WHERE `id_log` = ?
		ORDER BY `jenis` ASC, `nomor` ASC",
		array((int) $log_id)
	)->result();

	$data = array(
		'persediaan_all' => array(),
		'generate_update' => array(),
		'generate_insert' => array(),
		'generate_masalah' => array(),
		'generate_verifikasi' => array(),
		'pembelian' => array(),
		'pembelian_update' => array(),
		'pembelian_baru' => array(),
		'penjualan' => array(),
		'penjualan_update' => array(),
		'produksi' => array(),
		'produksi_update' => array(),
		'unit_produk' => array(),
		'unit_produk_update' => array(),
		'pecah_satuan' => array(),
		'pecah_satuan_update' => array(),
		'gagal_generate_recalculate' => array(),
		'gagal_insert_persediaan' => array(),
	);

	$stored_gagal = array(
		'gagal_generate_recalculate' => array(),
		'gagal_insert_persediaan' => array(),
	);

	foreach ($rows as $row) {
		$jenis = isset($row->jenis) ? $row->jenis : '';
		$decoded = json_decode($row->item_json, true);
		if (!is_array($decoded)) {
			continue;
		}
		if (isset($stored_gagal[$jenis])) {
			$stored_gagal[$jenis][] = $decoded;
			continue;
		}
		if (!isset($data[$jenis])) {
			continue;
		}
		$data[$jenis][] = $decoded;
	}

	$data = persediaan_gen_recalc_history_merge_gagal_from_stored($data, $stored_gagal);

	return array(
		'ok' => true,
		'has_history' => true,
		'log_id' => (int) $log_id,
		'data' => $data,
	);
}

function persediaan_gen_recalc_history_load($CI, $bulan)
{
	if (!persediaan_gen_recalc_table_exists($CI)) {
		return array(
			'ok' => true,
			'has_history' => false,
			'tables_ready' => false,
			'message' => 'Tabel history belum ada. Jalankan SQL database/sql/persediaan_gen_recalc_history.sql',
		);
	}

	$log = persediaan_gen_recalc_history_get_log($CI, $bulan);
	if (!$log) {
		return array(
			'ok' => true,
			'has_history' => false,
			'tables_ready' => true,
			'message' => 'Belum ada history proses untuk bulan ini.',
		);
	}

	$rows = $CI->db->query(
		"SELECT `jenis`, `item_json` FROM `persediaan_gen_recalc_item`
		WHERE `id_log` = ?
		ORDER BY `jenis` ASC, `nomor` ASC",
		array((int) $log->id)
	)->result();

	$data = array(
		'persediaan_all' => array(),
		'generate_update' => array(),
		'generate_insert' => array(),
		'generate_masalah' => array(),
		'generate_verifikasi' => array(),
		'pembelian' => array(),
		'pembelian_update' => array(),
		'pembelian_baru' => array(),
		'penjualan' => array(),
		'penjualan_update' => array(),
		'produksi' => array(),
		'produksi_update' => array(),
		'unit_produk' => array(),
		'unit_produk_update' => array(),
		'pecah_satuan' => array(),
		'pecah_satuan_update' => array(),
		'gagal_generate_recalculate' => array(),
		'gagal_insert_persediaan' => array(),
	);

	$stored_gagal = array(
		'gagal_generate_recalculate' => array(),
		'gagal_insert_persediaan' => array(),
	);

	foreach ($rows as $row) {
		$jenis = isset($row->jenis) ? $row->jenis : '';
		$decoded = json_decode($row->item_json, true);
		if (!is_array($decoded)) {
			continue;
		}
		if (isset($stored_gagal[$jenis])) {
			$stored_gagal[$jenis][] = $decoded;
			continue;
		}
		if (!isset($data[$jenis])) {
			continue;
		}
		$data[$jenis][] = $decoded;
	}

	$data = persediaan_gen_recalc_history_merge_gagal_from_stored($data, $stored_gagal);

	$summary = array(
		'bulan' => $log->bulan_target,
		'bulan_label' => date('m/Y', strtotime($log->bulan_target . '-01')),
		'bulan_sumber_label' => date('m/Y', strtotime($log->tanggal_beli_sumber)),
		'total_sumber' => (int) $log->total_sumber,
		'total_pembelian' => (int) $log->total_pembelian,
		'generate_insert' => (int) $log->generate_insert,
		'generate_update' => (int) $log->generate_update,
		'pembelian_update' => (int) $log->pembelian_update,
		'pembelian_insert' => (int) $log->pembelian_insert,
		'pembelian_gagal' => (int) $log->pembelian_gagal,
	);

	$summary_html = trim((string) $log->summary_html);
	if ($summary_html === '') {
		$summary_html = persediaan_gen_recalc_build_summary_html($summary);
	}

	return array(
		'ok' => true,
		'has_history' => true,
		'tables_ready' => true,
		'log_id' => (int) $log->id,
		'created_at' => $log->created_at,
		'nama_user' => isset($log->nama_user) ? $log->nama_user : '',
		'summary' => $summary,
		'summary_html' => $summary_html,
		'data' => $data,
	);
}

function persediaan_gen_recalc_normalize_inline_export_data($data)
{
	$data = is_array($data) ? $data : array();

	if (!isset($data['gagal_generate_recalculate']) || !is_array($data['gagal_generate_recalculate'])) {
		$data['gagal_generate_recalculate'] = persediaan_gen_recalc_collect_gagal_generate_recalculate($data);
	}
	if (!isset($data['gagal_insert_persediaan']) || !is_array($data['gagal_insert_persediaan'])) {
		$data['gagal_insert_persediaan'] = persediaan_gen_recalc_collect_gagal_insert_persediaan($data);
	}

	return $data;
}

function persediaan_gen_recalc_decode_inline_export_data($raw)
{
	if ($raw === null || $raw === '') {
		return null;
	}

	if (is_array($raw)) {
		return persediaan_gen_recalc_normalize_inline_export_data($raw);
	}

	$decoded = json_decode((string) $raw, true);
	if (!is_array($decoded)) {
		return null;
	}

	return persediaan_gen_recalc_normalize_inline_export_data($decoded);
}

function persediaan_gen_recalc_inline_export_has_rows($data, $jenis_filter = null)
{
	$data = persediaan_gen_recalc_normalize_inline_export_data($data);
	$defs = persediaan_gen_recalc_jenis_definitions();

	if ($jenis_filter !== null && $jenis_filter !== '') {
		if (!isset($defs[$jenis_filter])) {
			return false;
		}
		$key = $jenis_filter;
		return !empty($data[$key]) && is_array($data[$key]);
	}

	foreach (array_keys($defs) as $jenis) {
		if (!empty($data[$jenis]) && is_array($data[$jenis])) {
			return true;
		}
	}

	return false;
}

function persediaan_gen_recalc_build_excel_sheets_from_data($data, $jenis_filter = null)
{
	$data = persediaan_gen_recalc_normalize_inline_export_data($data);
	$defs = persediaan_gen_recalc_jenis_definitions();
	$jenis_list = array_keys($defs);
	if ($jenis_filter !== null && $jenis_filter !== '') {
		if (!isset($defs[$jenis_filter])) {
			return array('ok' => false, 'message' => 'Jenis export tidak valid.');
		}
		$jenis_list = array($jenis_filter);
	}

	$sheets = array();
	$footer_maps = persediaan_gen_recalc_jenis_footer_sum_map();
	$data_key_map = persediaan_gen_recalc_jenis_data_key_map();
	foreach ($jenis_list as $jenis) {
		if ($jenis === 'gagal_generate_recalculate') {
			$items = isset($data['gagal_generate_recalculate']) ? $data['gagal_generate_recalculate'] : array();
		} elseif ($jenis === 'gagal_insert_persediaan') {
			$items = isset($data['gagal_insert_persediaan']) ? $data['gagal_insert_persediaan'] : array();
		} else {
			$data_key = isset($data_key_map[$jenis]) ? $data_key_map[$jenis] : $jenis;
			$items = isset($data[$data_key]) ? $data[$data_key] : array();
			if (empty($items) && isset($data[$jenis])) {
				$items = $data[$jenis];
			}
		}
		if (!is_array($items)) {
			$items = array();
		}

		$rows = array();
		$nomor = 0;
		foreach ($items as $item) {
			$nomor++;
			$rows[] = persediaan_gen_recalc_item_to_display_row($jenis, $item, $nomor);
		}

		$footer_row = null;
		if (!empty($footer_maps[$jenis]) && !empty($defs[$jenis]['headers'])) {
			$sum_fields = array_values($footer_maps[$jenis]);
			$totals = persediaan_gen_recalc_sum_items_fields($items, $sum_fields);
			$footer_row = persediaan_gen_recalc_excel_footer_row(
				$defs[$jenis]['headers'],
				$footer_maps[$jenis],
				$totals
			);
		}

		$sheets[$jenis] = array(
			'name' => $defs[$jenis]['sheet'],
			'headers' => $defs[$jenis]['headers'],
			'rows' => $rows,
			'footer_row' => $footer_row,
		);
	}

	return array('ok' => true, 'sheets' => $sheets);
}

function persediaan_gen_recalc_history_rows_for_excel($CI, $bulan, $jenis_filter = null, $inline_data = null)
{
	$loaded = persediaan_gen_recalc_history_load($CI, $bulan);
	$use_data = null;
	$bulan_label = date('m/Y', strtotime($bulan . '-01'));
	$created_at = '';

	if (!empty($loaded['has_history']) && !empty($loaded['data']) && is_array($loaded['data'])) {
		$use_data = $loaded['data'];
		$bulan_label = isset($loaded['summary']['bulan_label']) ? $loaded['summary']['bulan_label'] : $bulan_label;
		$created_at = isset($loaded['created_at']) ? $loaded['created_at'] : '';
	} else {
		$inline = persediaan_gen_recalc_decode_inline_export_data($inline_data);
		if (is_array($inline) && persediaan_gen_recalc_inline_export_has_rows($inline, $jenis_filter)) {
			$use_data = $inline;
			$created_at = date('d/m/Y H:i:s');
		}
	}

	if ($use_data === null) {
		$message = 'Data export tidak ditemukan.';
		if (!empty($loaded['message'])) {
			$message = $loaded['message'];
		} elseif ($inline_data !== null && $inline_data !== '') {
			$message = 'Data tabel di browser kosong atau tidak valid. Jalankan Generate & Recalculate terlebih dahulu.';
		}
		return array('ok' => false, 'message' => $message);
	}

	$built = persediaan_gen_recalc_build_excel_sheets_from_data($use_data, $jenis_filter);
	if (empty($built['ok'])) {
		return $built;
	}

	return array(
		'ok' => true,
		'bulan' => $bulan,
		'bulan_label' => $bulan_label,
		'created_at' => $created_at,
		'sheets' => $built['sheets'],
	);
}

function persediaan_gen_recalc_export_excel_output($CI, $bulan, $jenis_filter = null, $inline_data = null)
{
	$CI->load->helper('exportexcel');

	$pack = persediaan_gen_recalc_history_rows_for_excel($CI, $bulan, $jenis_filter, $inline_data);
	if (empty($pack['ok'])) {
		xlsBOF();
		xlsWriteLabel(0, 0, isset($pack['message']) ? $pack['message'] : 'Data tidak ditemukan.');
		xlsEOF();
		return;
	}

	$waktu = date('d/m/Y H:i:s');
	$judul = 'Generate & Recalculate Persediaan — Bulan ' . $pack['bulan_label'];
	if (!empty($pack['created_at'])) {
		$judul .= ' | Proses: ' . $pack['created_at'];
	}
	$judul .= ' | Dicetak: ' . $waktu;

	xlsMultiBOF();
	foreach ($pack['sheets'] as $jenis => $sheet) {
		xlsAddSheet($sheet['name']);
		xlsWriteLabelBold14(0, 0, $judul);
		persediaan_excel_write_headers(2, $sheet['headers']);
		$row = 3;
		foreach ($sheet['rows'] as $cells) {
			$col = 0;
			$rowStyle = ($row % 2 === 0) ? 18 : 7;
			foreach ($cells as $cell) {
				xlsWriteCellStyle($row, $col, $cell, $rowStyle);
				$col++;
			}
			$row++;
		}
		if (!empty($sheet['footer_row']) && is_array($sheet['footer_row'])) {
			$col = 0;
			foreach ($sheet['footer_row'] as $cell) {
				$style = ($col === 0) ? 24 : 25;
				xlsWriteCellStyle($row, $col, $cell, $style);
				$col++;
			}
		}
	}
	xlsMultiEOF();
}

function persediaan_gen_recalc_batch_finish_history($CI, &$state, $summary, $summary_html = '')
{
	if (empty($state['log_id'])) {
		return;
	}

	if (!isset($state['hist_nomor']) || !is_array($state['hist_nomor'])) {
		$state['hist_nomor'] = persediaan_gen_recalc_hist_nomor_init();
	}

	$loaded = persediaan_gen_recalc_history_load_by_log_id($CI, (int) $state['log_id']);
	if (!empty($loaded['data']) && is_array($loaded['data'])) {
		persediaan_gen_recalc_history_save_gagal_snapshots(
			$CI,
			(int) $state['log_id'],
			$state['hist_nomor'],
			$loaded['data']
		);
	}

	persediaan_gen_recalc_history_finish($CI, $state['log_id'], $summary, $summary_html);
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

/**
 * -------------------------------------------------------------------------
 * Compare tabel manual vs persediaan (tab Recalculate → Compare Tabel)
 * -------------------------------------------------------------------------
 */
function persediaan_compare_is_valid_table_name($name)
{
	return is_string($name) && preg_match('/^[a-zA-Z0-9_]+$/', $name) === 1;
}

function persediaan_compare_list_db_tables($CI)
{
	$db_name = $CI->db->database;
	if ($db_name === '') {
		return array();
	}

	$rows = $CI->db->query(
		"SELECT TABLE_NAME AS tbl FROM information_schema.TABLES
		WHERE TABLE_SCHEMA = ? AND TABLE_TYPE = 'BASE TABLE'
		ORDER BY TABLE_NAME ASC",
		array($db_name)
	)->result();

	$tables = array();
	foreach ($rows as $row) {
		if (!empty($row->tbl) && persediaan_compare_is_valid_table_name($row->tbl)) {
			$tables[] = $row->tbl;
		}
	}

	return $tables;
}

function persediaan_compare_pick_column($fields, $candidates)
{
	if (!is_array($fields) || count($fields) === 0) {
		return null;
	}

	$by_lower = array();
	foreach ($fields as $f) {
		$by_lower[strtolower($f)] = $f;
	}

	foreach ($candidates as $c) {
		$cl = strtolower($c);
		if (isset($by_lower[$cl])) {
			return $by_lower[$cl];
		}
	}

	foreach ($fields as $f) {
		$fl = strtolower($f);
		foreach ($candidates as $c) {
			$cl = strtolower($c);
			if ($fl === $cl || strpos($fl, $cl) !== false) {
				return $f;
			}
		}
	}

	return null;
}

function persediaan_compare_detect_column_map($CI, $table)
{
	if (!persediaan_compare_is_valid_table_name($table) || !$CI->db->table_exists($table)) {
		return null;
	}

	$fields = $CI->db->list_fields($table);
	$map = array(
		'nama' => persediaan_compare_pick_column($fields, array(
			'namabarang', 'nama_barang', 'nama_barang_persediaan', 'uraian', 'nama', 'barang', 'nama barang',
		)),
		'satuan' => persediaan_compare_pick_column($fields, array('satuan', 'sat')),
		'hpp' => persediaan_compare_pick_column($fields, array(
			'hpp', 'harga_satuan', 'harga_satuan_persediaan', 'harga',
		)),
		'spop' => persediaan_compare_pick_column($fields, array('spop')),
		'sa' => persediaan_compare_pick_column($fields, array('sa')),
		'beli' => persediaan_compare_pick_column($fields, array('beli')),
		'total_10' => persediaan_compare_excel_all_pick_col_10($fields),
	);

	foreach (array('nama', 'satuan', 'hpp', 'spop') as $req) {
		if (empty($map[$req])) {
			return null;
		}
	}

	return $map;
}

function persediaan_compare_validate_table($CI, $table)
{
	if (!persediaan_compare_is_valid_table_name($table)) {
		return array('ok' => false, 'message' => 'Nama tabel tidak valid.');
	}

	if (!$CI->db->table_exists($table)) {
		return array('ok' => false, 'message' => 'Tabel tidak ditemukan di database.');
	}

	$map = persediaan_compare_detect_column_map($CI, $table);
	if ($map === null) {
		return array(
			'ok' => false,
			'message' => 'Tabel harus punya kolom minimal: nama barang (namabarang/uraian/nama_barang), satuan, hpp/harga_satuan, dan spop.',
		);
	}

	return array(
		'ok' => true,
		'map' => $map,
		'fields' => $CI->db->list_fields($table),
	);
}

function persediaan_compare_clear_db_schema_cache($CI)
{
	if (!isset($CI->db) || !isset($CI->db->data_cache) || !is_array($CI->db->data_cache)) {
		return;
	}

	unset($CI->db->data_cache['table_names']);
	if (isset($CI->db->data_cache['field_names'])) {
		$CI->db->data_cache['field_names'] = array();
	}
}

function persediaan_compare_db_last_error_message($CI, $fallback = 'Error database tidak diketahui.')
{
	$err = $CI->db->error();
	if (!empty($err['message'])) {
		return trim((string) $err['message']);
	}

	return $fallback;
}

function persediaan_compare_build_column_map_from_fields($fields)
{
	if (!is_array($fields) || count($fields) === 0) {
		return null;
	}

	$normalized = array();
	foreach ($fields as $field) {
		$normalized[] = trim((string) $field);
	}

	$map = array(
		'nama' => persediaan_compare_pick_column($normalized, array(
			'namabarang', 'nama_barang', 'nama_barang_persediaan', 'nama barang', 'uraian', 'nama', 'barang',
		)),
		'satuan' => persediaan_compare_pick_column($normalized, array('satuan', 'sat')),
		'hpp' => persediaan_compare_pick_column($normalized, array(
			'hpp', 'harga_satuan', 'harga_satuan_persediaan', 'harga',
		)),
		'spop' => persediaan_compare_pick_column($normalized, array('spop')),
	);

	foreach (array('nama', 'satuan', 'hpp', 'spop') as $req) {
		if (empty($map[$req])) {
			return null;
		}
	}

	return $map;
}

function persediaan_compare_csv_headers_preview($headers, $limit = 15)
{
	$clean = array();
	foreach ((array) $headers as $header) {
		$label = trim((string) $header);
		if ($label !== '') {
			$clean[] = $label;
		}
	}

	if (count($clean) === 0) {
		return '(tidak ada header terbaca)';
	}

	if (count($clean) > $limit) {
		return implode(', ', array_slice($clean, 0, $limit))
			. ', ... (total ' . count($clean) . ' kolom)';
	}

	return implode(', ', $clean);
}

function persediaan_compare_csv_column_error_detail($raw_headers)
{
	$normalized = array();
	foreach ((array) $raw_headers as $header) {
		$normalized[] = trim((string) $header);
	}

	$labels = array(
		'nama' => 'nama barang (namabarang / nama_barang / NAMA BARANG / uraian)',
		'satuan' => 'satuan (satuan / SAT)',
		'hpp' => 'hpp / harga_satuan / HPP',
		'spop' => 'spop / SPOP',
	);

	$found = array(
		'nama' => persediaan_compare_pick_column($normalized, array(
			'namabarang', 'nama_barang', 'nama_barang_persediaan', 'nama barang', 'uraian', 'nama', 'barang',
		)),
		'satuan' => persediaan_compare_pick_column($normalized, array('satuan', 'sat')),
		'hpp' => persediaan_compare_pick_column($normalized, array(
			'hpp', 'harga_satuan', 'harga_satuan_persediaan', 'harga',
		)),
		'spop' => persediaan_compare_pick_column($normalized, array('spop')),
	);

	$lines = array(
		'File CSV ditolak sebelum dibuat tabel baru.',
		'Kolom wajib belum lengkap:',
	);
	foreach ($labels as $req => $req_label) {
		if (empty($found[$req])) {
			$lines[] = '- ' . $req_label;
		}
	}
	$lines[] = 'Header terbaca: ' . persediaan_compare_csv_headers_preview($raw_headers);

	return implode("\n", $lines);
}

function persediaan_compare_sanitize_column_name($name)
{
	$name = trim((string) $name);
	$name = preg_replace('/\s+/', '_', $name);
	$name = preg_replace('/[^a-zA-Z0-9_]/', '', $name);
	$name = trim($name, '_');

	if ($name === '') {
		$name = 'col';
	}
	if (preg_match('/^[0-9]/', $name)) {
		$name = 'col_' . $name;
	}

	return strtolower($name);
}

function persediaan_compare_sanitize_csv_headers($headers)
{
	$used = array();
	$out = array();

	foreach ($headers as $header) {
		$base = persediaan_compare_sanitize_column_name($header);
		$name = $base;
		$n = 2;
		while (isset($used[strtolower($name)])) {
			$name = $base . '_' . $n;
			$n++;
		}
		$used[strtolower($name)] = true;
		$out[] = $name;
	}

	return $out;
}

/**
 * Nama tabel database dari nama file CSV (tanpa ekstensi), disanitasi untuk MySQL.
 * Tidak menambahkan suffix bulan/tahun — gunakan nama file CSV langsung agar mudah dikenali.
 * Jika tabel sudah ada, pemanggil memakai persediaan_compare_resolve_unique_table_name() → _1, _2, ...
 */
function persediaan_compare_sanitize_table_name_from_csv($filename, $bulan_key = '')
{
	$base = pathinfo((string) $filename, PATHINFO_FILENAME);
	$base = strtolower(trim($base));
	$base = preg_replace('/[^a-z0-9_]+/', '_', $base);
	$base = trim($base, '_');

	if ($base === '') {
		$base = 'compare_manual';
	}
	if (preg_match('/^[0-9]/', $base)) {
		$base = 'tbl_' . $base;
	}

	if (strlen($base) > 60) {
		$base = substr($base, 0, 60);
		$base = rtrim($base, '_');
	}

	return $base;
}

function persediaan_compare_resolve_unique_table_name($CI, $base_table)
{
	$base_table = trim((string) $base_table);
	if (!persediaan_compare_is_valid_table_name($base_table)) {
		return array(
			'ok' => false,
			'message' => 'Nama tabel dasar tidak valid: `' . $base_table . '`.',
		);
	}

	persediaan_compare_clear_db_schema_cache($CI);

	$max_len = 64;
	$suffix_num = 0;
	$candidate = $base_table;

	while ($CI->db->table_exists($candidate)) {
		$suffix_num++;
		$suffix = '_' . $suffix_num;
		$root = $base_table;
		if (strlen($root) + strlen($suffix) > $max_len) {
			$root = substr($root, 0, $max_len - strlen($suffix));
			$root = rtrim($root, '_');
		}
		$candidate = $root . $suffix;

		if (!persediaan_compare_is_valid_table_name($candidate)) {
			return array(
				'ok' => false,
				'message' => 'Nama tabel unik hasil penomoran tidak valid: `' . $candidate . '`.',
			);
		}

		if ($suffix_num > 999) {
			return array(
				'ok' => false,
				'message' => 'Terlalu banyak tabel dengan nama serupa (`' . $base_table . '`). Hapus tabel lama atau ubah nama file CSV.',
			);
		}
	}

	return array(
		'ok' => true,
		'table' => $candidate,
		'base' => $base_table,
		'suffix' => $suffix_num,
	);
}

function persediaan_compare_detect_csv_column_map($headers)
{
	return persediaan_compare_build_column_map_from_fields($headers);
}

function persediaan_compare_csv_open_handle($filepath)
{
	$handle = @fopen($filepath, 'rb');
	if (!$handle) {
		return false;
	}

	$bom = fread($handle, 3);
	if ($bom !== chr(0xEF) . chr(0xBB) . chr(0xBF)) {
		rewind($handle);
	}

	return $handle;
}

function persediaan_compare_csv_read_header($handle)
{
	$row = fgetcsv($handle, 0, ',', '"');
	if (!is_array($row) || count($row) === 0) {
		return null;
	}

	$headers = array();
	foreach ($row as $cell) {
		$headers[] = trim((string) $cell);
	}

	return $headers;
}

function persediaan_compare_import_csv_to_db($CI, $filepath, $original_filename, $bulan_key = '')
{
	$file_label = trim((string) $original_filename);
	if ($file_label === '') {
		$file_label = basename((string) $filepath);
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

	$map_check = persediaan_compare_detect_csv_column_map($raw_headers);
	if ($map_check === null) {
		fclose($handle);
		return array(
			'ok' => false,
			'stage' => 'validasi_kolom',
			'message' => persediaan_compare_csv_column_error_detail($raw_headers),
			'file' => $file_label,
		);
	}

	$db_columns = persediaan_compare_sanitize_csv_headers($raw_headers);
	$base_table = persediaan_compare_sanitize_table_name_from_csv($original_filename, $bulan_key);
	$resolved = persediaan_compare_resolve_unique_table_name($CI, $base_table);
	if (empty($resolved['ok'])) {
		fclose($handle);
		return array(
			'ok' => false,
			'stage' => 'nama_tabel',
			'message' => isset($resolved['message'])
				? $resolved['message']
				: "Nama tabel hasil import dari file `{$file_label}` tidak valid.",
			'file' => $file_label,
		);
	}

	$table = $resolved['table'];
	$table_suffix = isset($resolved['suffix']) ? (int) $resolved['suffix'] : 0;
	$table_base = isset($resolved['base']) ? $resolved['base'] : $base_table;

	$field_defs = array();
	foreach ($db_columns as $col) {
		$field_defs[] = '`' . $col . '` TEXT NULL';
	}

	$create_sql = 'CREATE TABLE `' . $table . '` (' . implode(', ', $field_defs)
		. ') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';

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
		foreach ($db_columns as $idx => $col) {
			$data[$col] = isset($row[$idx]) ? trim((string) $row[$idx]) : '';
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

	$col_map = persediaan_compare_build_column_map_from_fields($db_columns);
	if ($col_map === null) {
		$CI->db->query('DROP TABLE IF EXISTS `' . $table . '`');
		persediaan_compare_clear_db_schema_cache($CI);
		return array(
			'ok' => false,
			'stage' => 'validasi_kolom_db',
			'message' => "Tabel `{$table}` gagal divalidasi setelah dibuat.\n"
				. 'Kolom hasil import: ' . implode(', ', $db_columns),
			'table' => $table,
			'file' => $file_label,
		);
	}

	return array(
		'ok' => true,
		'stage' => 'success',
		'table' => $table,
		'table_base' => $table_base,
		'table_suffix' => $table_suffix,
		'rows' => $inserted,
		'columns' => count($db_columns),
		'file' => $file_label,
		'message' => "Import CSV berhasil.\n"
			. ($table_suffix > 0
				? "Tabel `{$table_base}` sudah ada — dibuat tabel baru: `{$table}` (_{$table_suffix}).\n"
				: "1. Tabel baru dibuat: `{$table}`\n")
			. '2. Kolom disesuaikan dari header CSV (' . count($db_columns) . " kolom)\n"
			. "3. Data ter-upload: {$inserted} baris\n"
			. 'Silahkan lanjut compare menggunakan tabel ini.',
	);
}

function persediaan_compare_preview_table_data($CI, $table, $limit = 1000)
{
	$table = trim((string) $table);
	if (!persediaan_compare_is_valid_table_name($table)) {
		return array('ok' => false, 'message' => 'Nama tabel tidak valid.');
	}

	persediaan_compare_clear_db_schema_cache($CI);
	if (!$CI->db->table_exists($table)) {
		return array('ok' => false, 'message' => 'Tabel `' . $table . '` tidak ditemukan di database.');
	}

	$fields = $CI->db->list_fields($table);
	if (!is_array($fields) || count($fields) === 0) {
		return array('ok' => false, 'message' => 'Tabel `' . $table . '` tidak memiliki kolom.');
	}

	$total = (int) $CI->db->count_all($table);
	$limit = max(1, min(2000, (int) $limit));

	$rows_raw = $CI->db->query('SELECT * FROM `' . $table . '` LIMIT ' . (int) $limit)->result_array();
	$rows = array();
	foreach ($rows_raw as $row) {
		$item = array();
		foreach ($fields as $field) {
			$item[$field] = isset($row[$field]) ? (string) $row[$field] : '';
		}
		$rows[] = $item;
	}

	return array(
		'ok' => true,
		'table' => $table,
		'columns' => $fields,
		'rows' => $rows,
		'total' => $total,
		'shown' => count($rows),
		'truncated' => ($total > count($rows)),
	);
}

/**
 * Cek apakah tabel punya kolom lengkap untuk tombol insert ke persediaan (tab Compare — combobox DB).
 * Wajib: tanggal_beli, namabarang, satuan, hpp, spop, total_10 (case-insensitive).
 */
function persediaan_compare_table_eligible_insert_persediaan_fields($CI, $table)
{
	$table = trim((string) $table);
	if (!persediaan_compare_is_valid_table_name($table)) {
		return array('ok' => true, 'eligible' => false, 'message' => 'Nama tabel tidak valid.');
	}
	if (!$CI->db->table_exists($table)) {
		return array('ok' => true, 'eligible' => false, 'message' => 'Tabel tidak ditemukan di database.');
	}

	$fields = $CI->db->list_fields($table);
	$fields_lower = array();
	foreach ($fields as $f) {
		$fields_lower[strtolower((string) $f)] = $f;
	}

	$required = array('tanggal_beli', 'namabarang', 'satuan', 'hpp', 'spop', 'total_10');
	$missing = array();
	foreach ($required as $req) {
		if (!isset($fields_lower[$req])) {
			$missing[] = $req;
		}
	}

	if (!empty($missing)) {
		return array(
			'ok' => true,
			'eligible' => false,
			'missing' => $missing,
			'message' => 'Kolom belum lengkap untuk insert persediaan: ' . implode(', ', $missing) . '.',
		);
	}

	return array(
		'ok' => true,
		'eligible' => true,
		'message' => 'Tabel memiliki kolom lengkap untuk insert ke persediaan.',
	);
}

/**
 * Insert data dari tabel hasil import CSV compare ke tabel persediaan.
 * Aturan uuid_persediaan:
 * - Jika cocok ke tbl_penjualan by nama_barang+satuan+harga_satuan: copy uuid_persediaan dari tbl_penjualan.
 * - Jika tidak cocok: generate uuid_persediaan baru.
 * - Saat copy dari tbl_penjualan, isi uuid_persediaan_lama = "copy uuid_persediaan dari tbl_penjualan".
 */
function persediaan_compare_insert_table_to_persediaan($CI, $table, $bulan = '')
{
	$CI->load->helper('persediaan_display');

	$table = trim((string) $table);
	if (!persediaan_compare_is_valid_table_name($table)) {
		return array('ok' => false, 'message' => 'Nama tabel manual tidak valid.');
	}
	if (!$CI->db->table_exists($table)) {
		return array('ok' => false, 'message' => 'Tabel manual tidak ditemukan di database.');
	}
	if (!$CI->db->table_exists('persediaan')) {
		return array('ok' => false, 'message' => 'Tabel persediaan tidak ditemukan.');
	}

	$valid = persediaan_compare_validate_table($CI, $table);
	if (empty($valid['ok'])) {
		return array('ok' => false, 'message' => isset($valid['message']) ? $valid['message'] : 'Tabel manual tidak valid.');
	}
	$map = isset($valid['map']) ? $valid['map'] : array();

	$fields_src = $CI->db->list_fields($table);
	$fields_pers = $CI->db->list_fields('persediaan');
	$fields_src_lookup = array();
	foreach ($fields_src as $f) {
		$fields_src_lookup[strtolower((string) $f)] = $f;
	}
	$fields_pers_lookup = array();
	foreach ($fields_pers as $f) {
		$fields_pers_lookup[strtolower((string) $f)] = $f;
	}

	$bulan = trim((string) $bulan);
	$tanggal_beli_default = date('Y-m-01');
	if (preg_match('/^\d{4}-\d{2}$/', $bulan)) {
		$tanggal_beli_default = $bulan . '-01';
	}
	$tanggal_default = date('d/m/Y', strtotime($tanggal_beli_default));

	$rows = $CI->db->query("SELECT * FROM `{$table}` ORDER BY 1 ASC")->result_array();
	if (empty($rows)) {
		return array('ok' => false, 'message' => 'Tabel manual kosong. Tidak ada data untuk diinsert ke persediaan.');
	}

	$uuid_by_key = array();
	if ($CI->db->table_exists('tbl_penjualan')) {
		$penj_rows = $CI->db->query(
			"SELECT `nama_barang`, `satuan`, `harga_satuan`, `uuid_persediaan`
			 FROM `tbl_penjualan`
			 WHERE TRIM(COALESCE(`uuid_persediaan`, '')) <> ''"
		)->result();
		foreach ($penj_rows as $r) {
			$k = persediaan_recalculate_sync_nama_satuan_hpp_key(
				isset($r->nama_barang) ? $r->nama_barang : '',
				isset($r->satuan) ? $r->satuan : '',
				isset($r->harga_satuan) ? $r->harga_satuan : ''
			);
			if ($k === '') {
				continue;
			}
			if (empty($uuid_by_key[$k])) {
				$uuid_by_key[$k] = trim((string) $r->uuid_persediaan);
			}
		}
	}

	$used_ids = array();
	$id_rows = $CI->db->query("SELECT `id` FROM `persediaan` WHERE `id` IS NOT NULL")->result();
	foreach ($id_rows as $ir) {
		$used_ids[(int) $ir->id] = true;
	}
	$row_max = $CI->db->query("SELECT MAX(`id`) AS max_id FROM `persediaan`")->row();
	$next_id = $row_max && $row_max->max_id ? ((int) $row_max->max_id + 1) : 1;

	$inserted = 0;
	$copied_uuid = 0;
	$generated_uuid = 0;
	$skip_empty_name = 0;

	$CI->db->trans_begin();
	foreach ($rows as $src) {
		$src_obj = (object) $src;
		$data = array();
		foreach ($fields_pers as $fp) {
			$data[$fp] = '';
		}

		foreach ($fields_pers as $fp) {
			$key = strtolower((string) $fp);
			if (isset($fields_src_lookup[$key])) {
				$src_col = $fields_src_lookup[$key];
				$data[$fp] = isset($src[$src_col]) ? (string) $src[$src_col] : '';
			}
		}

		$nama = trim((string) persediaan_compare_row_get($src_obj, isset($map['nama']) ? $map['nama'] : null));
		$satuan = trim((string) persediaan_compare_row_get($src_obj, isset($map['satuan']) ? $map['satuan'] : null));
		$hpp = trim((string) persediaan_compare_row_get($src_obj, isset($map['hpp']) ? $map['hpp'] : null));
		$spop = trim((string) persediaan_compare_row_get($src_obj, isset($map['spop']) ? $map['spop'] : null));
		if ($nama === '') {
			$skip_empty_name++;
			continue;
		}

		if (isset($fields_pers_lookup['namabarang'])) {
			$data[$fields_pers_lookup['namabarang']] = $nama;
		}
		if (isset($fields_pers_lookup['satuan'])) {
			$data[$fields_pers_lookup['satuan']] = $satuan;
		}
		if (isset($fields_pers_lookup['hpp'])) {
			$data[$fields_pers_lookup['hpp']] = $hpp;
		}
		if (isset($fields_pers_lookup['spop'])) {
			$data[$fields_pers_lookup['spop']] = ($spop !== '' ? $spop : '0');
		}
		if (isset($fields_pers_lookup['tanggal_beli']) && trim((string) $data[$fields_pers_lookup['tanggal_beli']]) === '') {
			$data[$fields_pers_lookup['tanggal_beli']] = $tanggal_beli_default;
		}
		if (isset($fields_pers_lookup['tanggal']) && trim((string) $data[$fields_pers_lookup['tanggal']]) === '') {
			$data[$fields_pers_lookup['tanggal']] = $tanggal_default;
		}

		$id_val = 0;
		if (isset($fields_src_lookup['id'])) {
			$id_val = (int) $src[$fields_src_lookup['id']];
		}
		if ($id_val <= 0 || isset($used_ids[$id_val])) {
			while (isset($used_ids[$next_id])) {
				$next_id++;
			}
			$id_val = $next_id;
			$next_id++;
		}
		$used_ids[$id_val] = true;
		if (isset($fields_pers_lookup['id'])) {
			$data[$fields_pers_lookup['id']] = $id_val;
		}

		$uuid_key = persediaan_recalculate_sync_nama_satuan_hpp_key($nama, $satuan, $hpp);
		$uuid_copy = ($uuid_key !== '' && !empty($uuid_by_key[$uuid_key])) ? trim((string) $uuid_by_key[$uuid_key]) : '';
		if (isset($fields_pers_lookup['uuid_persediaan'])) {
			if ($uuid_copy !== '') {
				$data[$fields_pers_lookup['uuid_persediaan']] = $uuid_copy;
				$copied_uuid++;
				if (isset($fields_pers_lookup['uuid_persediaan_lama'])) {
					$data[$fields_pers_lookup['uuid_persediaan_lama']] = 'copy uuid_persediaan dari tbl_penjualan';
				}
			} else {
				try {
					$data[$fields_pers_lookup['uuid_persediaan']] = bin2hex(random_bytes(16));
				} catch (Exception $e) {
					$data[$fields_pers_lookup['uuid_persediaan']] = md5(uniqid('', true));
				}
				$generated_uuid++;
			}
		}

		if (!$CI->db->insert('persediaan', $data)) {
			$db_err = $CI->db->error();
			$CI->db->trans_rollback();
			return array(
				'ok' => false,
				'message' => 'Gagal insert ke persediaan pada data `' . $nama . '` (ID ' . $id_val . '). Detail: '
					. (isset($db_err['message']) ? $db_err['message'] : 'error database'),
			);
		}
		$inserted++;
	}

	if ($CI->db->trans_status() === false) {
		$CI->db->trans_rollback();
		return array('ok' => false, 'message' => 'Transaksi insert ke persediaan gagal.');
	}
	$CI->db->trans_commit();

	return array(
		'ok' => true,
		'table' => $table,
		'inserted' => $inserted,
		'copied_uuid' => $copied_uuid,
		'generated_uuid' => $generated_uuid,
		'skip_empty_name' => $skip_empty_name,
		'message' => 'Insert ke persediaan selesai. Berhasil: ' . $inserted
			. ' baris | uuid copy dari tbl_penjualan: ' . $copied_uuid
			. ' | uuid generate baru: ' . $generated_uuid
			. ($skip_empty_name > 0 ? ' | lewati nama kosong: ' . $skip_empty_name : ''),
	);
}

function persediaan_compare_get_persediaan_bulan_rows($CI, $bulan)
{
	$CI->load->helper('persediaan_display');

	$bulan = trim((string) $bulan);
	if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
		return array();
	}

	$ts = strtotime($bulan . '-01');
	if ($ts === false) {
		return array();
	}

	$tgl_awal = date('Y-m-01', $ts);
	$tgl_akhir = date('Y-m-t', $ts);

	$rows = $CI->db->query(
		"SELECT * FROM `persediaan`
		WHERE `tanggal_beli` IS NOT NULL AND `tanggal_beli` <> '0000-00-00'
		AND DATE(`tanggal_beli`) >= ? AND DATE(`tanggal_beli`) <= ?
		ORDER BY `namabarang` ASC, `id` ASC",
		array($tgl_awal, $tgl_akhir)
	)->result();

	return persediaan_export_sort_rows_by_namabarang($rows, 'namabarang');
}

/**
 * Rentang tanggal dari kunci bulan YYYY-MM.
 */
function persediaan_compare_bulan_to_date_range($bulan)
{
	$bulan = trim((string) $bulan);
	if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
		return null;
	}
	$ts = strtotime($bulan . '-01');
	if ($ts === false) {
		return null;
	}

	return array(
		'tgl_awal' => date('Y-m-01', $ts),
		'tgl_akhir' => date('Y-m-t', $ts),
		'tanggal_beli' => date('Y-m-01', $ts),
		'bulan_label' => date('m/Y', $ts),
	);
}

/**
 * Cek apakah nama tabel manual mengandung periode YYYYMM yang sesuai bulan terpilih.
 */
function persediaan_compare_table_name_matches_bulan($table, $bulan)
{
	if (!preg_match('/^\d{4}-\d{2}$/', trim((string) $bulan), $m)) {
		return false;
	}

	$tahun = $m[1];
	$bln = $m[2];
	$needle_a = $tahun . $bln;
	$needle_b = $tahun . '_' . $bln;
	$table_l = strtolower((string) $table);

	if (strpos($table_l, $needle_a) !== false || strpos($table_l, $needle_b) !== false) {
		return true;
	}

	// Tabel tanpa pola tanggal di nama — dianggap snapshot manual (tidak diblokir).
	return !preg_match('/\d{6,}/', $table_l);
}

function persediaan_compare_detect_manual_date_column($fields)
{
	return persediaan_compare_pick_column($fields, array(
		'tanggal_beli',
		'tanggal',
		'tgl',
		'tgl_po',
		'tgl_transaksi',
		'proses_input',
		'bulan',
		'tahun',
	));
}

/**
 * Muat baris tabel manual — hanya record yang sesuai bulan/tahun terpilih.
 */
function persediaan_compare_load_manual_rows_for_bulan($CI, $table, $bulan, $order_sql = '')
{
	$valid = persediaan_compare_validate_table($CI, $table);
	if (empty($valid['ok'])) {
		return array('ok' => false, 'message' => $valid['message']);
	}

	$range = persediaan_compare_bulan_to_date_range($bulan);
	if ($range === null) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');
	}

	$map = $valid['map'];
	$fields = $valid['fields'];
	$date_col = persediaan_compare_detect_manual_date_column($fields);
	$params = array();
	$where = '';

	if ($date_col !== null) {
		$col_l = strtolower((string) $date_col);
		if ($col_l === 'tanggal_beli' || $col_l === 'tgl_po' || $col_l === 'tgl_transaksi' || $col_l === 'proses_input' || $col_l === 'tgl') {
			$where = ' WHERE `' . $date_col . '` IS NOT NULL AND `' . $date_col . "` <> '0000-00-00'"
				. ' AND DATE(`' . $date_col . '`) >= ? AND DATE(`' . $date_col . '`) <= ?';
			$params = array($range['tgl_awal'], $range['tgl_akhir']);
		} elseif ($col_l === 'tanggal') {
			$where = " WHERE COALESCE(
				STR_TO_DATE(`{$date_col}`, '%d/%m/%Y'),
				STR_TO_DATE(`{$date_col}`, '%e/%c/%Y'),
				STR_TO_DATE(`{$date_col}`, '%Y-%m-%d'),
				STR_TO_DATE(`{$date_col}`, '%d-%m-%Y')
			) BETWEEN ? AND ?";
			$params = array($range['tgl_awal'], $range['tgl_akhir']);
		} elseif ($col_l === 'bulan') {
			$bulan_num = (int) substr($bulan, 5, 2);
			$tahun_num = (int) substr($bulan, 0, 4);
			$where = ' WHERE (CAST(`' . $date_col . '` AS UNSIGNED) = ? OR TRIM(`' . $date_col . '`) = ?)';
			$params = array($bulan_num, str_pad((string) $bulan_num, 2, '0', STR_PAD_LEFT));
			$tahun_col = persediaan_compare_pick_column($fields, array('tahun'));
			if ($tahun_col !== null) {
				$where .= ' AND CAST(`' . $tahun_col . '` AS UNSIGNED) = ?';
				$params[] = $tahun_num;
			}
		} elseif ($col_l === 'tahun') {
			$tahun_num = (int) substr($bulan, 0, 4);
			$where = ' WHERE CAST(`' . $date_col . '` AS UNSIGNED) = ?';
			$params = array($tahun_num);
		}
	} elseif (!persediaan_compare_table_name_matches_bulan($table, $bulan)) {
		return array(
			'ok' => false,
			'message' => 'Tabel manual `' . $table . '` tidak sesuai bulan/tahun terpilih (' . $range['bulan_label'] . ').',
		);
	}

	$sql = 'SELECT * FROM `' . $table . '`' . $where . $order_sql;
	$manual_rows = count($params) > 0
		? $CI->db->query($sql, $params)->result()
		: $CI->db->query($sql)->result();

	return array(
		'ok' => true,
		'map' => $map,
		'fields' => $fields,
		'rows' => $manual_rows,
		'range' => $range,
	);
}

function persediaan_compare_row_get($row, $col)
{
	if ($row === null || $col === null || $col === '') {
		return '';
	}
	if (is_array($row)) {
		return isset($row[$col]) ? $row[$col] : '';
	}
	return isset($row->{$col}) ? $row->{$col} : '';
}

/**
 * Nilai numerik kosong/null → 0 (selaras persediaan: beli=0, bukan blank).
 */
function persediaan_compare_excel_all_normalize_numeric_display($value)
{
	$CI = get_instance();
	$CI->load->helper('persediaan_display');
	$n = persediaan_parse_angka($value);
	if (abs($n - floor($n)) < 0.0001) {
		return (string) (int) floor($n);
	}

	return (string) $n;
}

/**
 * SPOP kosong/null → 0 (selaras hasil generate persediaan).
 */
function persediaan_compare_excel_all_normalize_spop_display($spop)
{
	if (persediaan_recalculate_spop_kosong_atau_nol($spop)) {
		return '0';
	}

	$s = persediaan_recalculate_normalize_spop($spop);
	if (preg_match('/^\d+$/', $s)) {
		return (string) (int) $s;
	}

	return $s;
}

function persediaan_compare_excel_all_format_tanggal_display($value)
{
	$value = trim((string) $value);
	if ($value === '' || $value === '0000-00-00' || $value === '0000-00-00 00:00:00') {
		return '';
	}

	$ts = pembelian_parse_tanggal_po($value);
	if ($ts === false) {
		$ts = strtotime(str_replace('/', '-', $value));
	}

	return ($ts !== false) ? date('d/m/Y', $ts) : $value;
}

function persediaan_compare_excel_all_total_nominal_display($harga, $jumlah)
{
	$CI = get_instance();
	$CI->load->helper('persediaan_display');

	return persediaan_compare_excel_all_normalize_numeric_display(
		persediaan_parse_angka($harga) * persediaan_parse_angka($jumlah)
	);
}

function persediaan_compare_build_key($nama, $satuan, $hpp, $spop)
{
	return persediaan_recalculate_sync_pembelian_persediaan_key($nama, $satuan, $hpp, $spop);
}

function persediaan_compare_load_table_rows($CI, $table)
{
	$valid = persediaan_compare_validate_table($CI, $table);
	if (empty($valid['ok'])) {
		return array('ok' => false, 'message' => $valid['message']);
	}

	$map = $valid['map'];
	$rows = $CI->db->query('SELECT * FROM `' . $table . '` ORDER BY `' . $map['nama'] . '` ASC')->result();

	return array(
		'ok' => true,
		'map' => $map,
		'rows' => $rows,
	);
}

function persediaan_compare_format_side($row, $prefix, $map = null)
{
	if ($row === null) {
		$empty = array(
			$prefix . '_namabarang' => '',
			$prefix . '_satuan' => '',
			$prefix . '_hpp' => '',
			$prefix . '_spop' => '',
			$prefix . '_sa' => '',
			$prefix . '_beli' => '',
			$prefix . '_total_10' => '',
		);
		return $empty;
	}

	if ($prefix === 'p') {
		return array(
			'p_namabarang' => isset($row->namabarang) ? $row->namabarang : '',
			'p_satuan' => isset($row->satuan) ? $row->satuan : '',
			'p_hpp' => isset($row->hpp) ? $row->hpp : '',
			'p_spop' => isset($row->spop) ? $row->spop : '',
			'p_sa' => isset($row->sa) ? $row->sa : '',
			'p_beli' => isset($row->beli) ? $row->beli : '',
			'p_total_10' => isset($row->total_10) ? $row->total_10 : '',
		);
	}

	return array(
		'c_namabarang' => persediaan_compare_row_get($row, $map['nama']),
		'c_satuan' => persediaan_compare_row_get($row, $map['satuan']),
		'c_hpp' => persediaan_compare_row_get($row, $map['hpp']),
		'c_spop' => persediaan_compare_row_get($row, $map['spop']),
		'c_sa' => persediaan_compare_row_get($row, isset($map['sa']) ? $map['sa'] : null),
		'c_beli' => persediaan_compare_row_get($row, isset($map['beli']) ? $map['beli'] : null),
		'c_total_10' => persediaan_compare_row_get($row, isset($map['total_10']) ? $map['total_10'] : null),
	);
}

function persediaan_compare_total10_kosong($row)
{
	$CI = get_instance();
	$CI->load->helper('persediaan_display');
	$v = persediaan_parse_angka(isset($row->total_10) ? $row->total_10 : 0);

	return $v <= 0;
}

function persediaan_compare_item_persediaan_only($row)
{
	return array(
		'p_namabarang' => isset($row->namabarang) ? $row->namabarang : '',
		'p_satuan' => isset($row->satuan) ? $row->satuan : '',
		'p_hpp' => isset($row->hpp) ? $row->hpp : '',
		'p_spop' => isset($row->spop) ? $row->spop : '',
		'p_sa' => isset($row->sa) ? $row->sa : '',
		'p_beli' => isset($row->beli) ? $row->beli : '',
		'p_total_10' => isset($row->total_10) ? $row->total_10 : '',
	);
}

function persediaan_compare_jenis_definitions()
{
	return array(
		'total10_kosong' => array(
			'title' => 'Persediaan Total_10 Kosong atau 0',
			'headers' => array('No', 'Namabarang', 'Satuan', 'HPP', 'SPOP', 'Sa', 'Beli', 'Total_10'),
			'file_suffix' => 'Total10_Kosong',
		),
		'tidak_di_tabel' => array(
			'title' => 'Persediaan Tidak Ada di Tabel Manual',
			'headers' => array('No', 'P_Namabarang', 'P_Satuan', 'P_HPP', 'P_SPOP', 'P_SA', 'P_Beli', 'P_Total_10', 'C_Nama', 'C_Satuan', 'C_HPP', 'C_SPOP', 'C_SA', 'C_Beli', 'C_Total_10'),
			'file_suffix' => 'Tidak_Di_Tabel',
		),
		'hanya_tabel' => array(
			'title' => 'Tabel Manual Tidak Ada di Persediaan',
			'headers' => array('No', 'P_Namabarang', 'P_Satuan', 'P_HPP', 'P_SPOP', 'P_SA', 'P_Beli', 'P_Total_10', 'C_Nama', 'C_Satuan', 'C_HPP', 'C_SPOP', 'C_SA', 'C_Beli', 'C_Total_10'),
			'file_suffix' => 'Hanya_Tabel',
		),
		'cocok' => array(
			'title' => 'Data Cocok Persediaan dan Tabel Manual',
			'headers' => array('No', 'P_Namabarang', 'P_Satuan', 'P_HPP', 'P_SPOP', 'P_SA', 'P_Beli', 'P_Total_10', 'C_Nama', 'C_Satuan', 'C_HPP', 'C_SPOP', 'C_SA', 'C_Beli', 'C_Total_10'),
			'file_suffix' => 'Cocok',
		),
		'pembelian_tidak_manual' => array(
			'title' => 'Pembelian Tidak Ada di Tabel Manual',
			'headers' => array('No', 'Tgl PO', 'Nama Barang', 'SPOP', 'Satuan', 'Harga Satuan', 'Jumlah', 'Keterangan'),
			'file_suffix' => 'Pembelian_Tidak_Manual',
		),
		'penjualan_tidak_manual' => array(
			'title' => 'Penjualan Tidak Ada di Tabel Manual',
			'headers' => array('No', 'Tgl Jual', 'Nama Barang', 'SPOP', 'Satuan', 'Harga Satuan', 'Jumlah', 'Keterangan'),
			'file_suffix' => 'Penjualan_Tidak_Manual',
		),
		'produksi_tidak_manual' => array(
			'title' => 'Produksi Tidak Ada di Tabel Manual',
			'headers' => array('No', 'Nama Barang Bahan', 'Satuan Bahan', 'Harga Satuan Bahan', 'SPOP', 'Jumlah Bahan', 'Tgl Transaksi'),
			'file_suffix' => 'Produksi_Tidak_Manual',
		),
		'pecah_tidak_manual' => array(
			'title' => 'Pecah Satuan Tidak Ada di Tabel Manual',
			'headers' => array('No', 'Uraian', 'Satuan', 'Harga Satuan', 'SPOP', 'Jumlah'),
			'file_suffix' => 'Pecah_Tidak_Manual',
		),
	);
}

function persediaan_compare_item_to_row_cells($jenis, $item, $nomor)
{
	$item = is_array($item) ? $item : array();

	switch ($jenis) {
		case 'total10_kosong':
			return array(
				$nomor,
				isset($item['p_namabarang']) ? $item['p_namabarang'] : '',
				isset($item['p_satuan']) ? $item['p_satuan'] : '',
				isset($item['p_hpp']) ? $item['p_hpp'] : '',
				isset($item['p_spop']) ? $item['p_spop'] : '',
				isset($item['p_sa']) ? $item['p_sa'] : '',
				isset($item['p_beli']) ? $item['p_beli'] : '',
				isset($item['p_total_10']) ? $item['p_total_10'] : '',
			);
		case 'tidak_di_tabel':
		case 'hanya_tabel':
		case 'cocok':
			return array(
				$nomor,
				isset($item['p_namabarang']) ? $item['p_namabarang'] : '',
				isset($item['p_satuan']) ? $item['p_satuan'] : '',
				isset($item['p_hpp']) ? $item['p_hpp'] : '',
				isset($item['p_spop']) ? $item['p_spop'] : '',
				isset($item['p_sa']) ? $item['p_sa'] : '',
				isset($item['p_beli']) ? $item['p_beli'] : '',
				isset($item['p_total_10']) ? $item['p_total_10'] : '',
				isset($item['c_namabarang']) ? $item['c_namabarang'] : '',
				isset($item['c_satuan']) ? $item['c_satuan'] : '',
				isset($item['c_hpp']) ? $item['c_hpp'] : '',
				isset($item['c_spop']) ? $item['c_spop'] : '',
				isset($item['c_sa']) ? $item['c_sa'] : '',
				isset($item['c_beli']) ? $item['c_beli'] : '',
				isset($item['c_total_10']) ? $item['c_total_10'] : '',
			);
		case 'pembelian_tidak_manual':
			return array(
				$nomor,
				isset($item['tgl_po']) ? $item['tgl_po'] : '',
				isset($item['uraian']) ? $item['uraian'] : '',
				isset($item['spop']) ? $item['spop'] : '',
				isset($item['satuan']) ? $item['satuan'] : '',
				isset($item['harga_satuan']) ? $item['harga_satuan'] : '',
				isset($item['jumlah']) ? $item['jumlah'] : '',
				isset($item['keterangan']) ? $item['keterangan'] : '',
			);
		case 'penjualan_tidak_manual':
			return array(
				$nomor,
				isset($item['tgl_jual']) ? $item['tgl_jual'] : '',
				isset($item['nama_barang']) ? $item['nama_barang'] : '',
				isset($item['spop']) ? $item['spop'] : '',
				isset($item['satuan']) ? $item['satuan'] : '',
				isset($item['harga_satuan']) ? $item['harga_satuan'] : '',
				isset($item['jumlah']) ? $item['jumlah'] : '',
				isset($item['keterangan']) ? $item['keterangan'] : '',
			);
		case 'produksi_tidak_manual':
			return array(
				$nomor,
				isset($item['nama_barang_bahan']) ? $item['nama_barang_bahan'] : '',
				isset($item['satuan_bahan']) ? $item['satuan_bahan'] : '',
				isset($item['harga_satuan_bahan']) ? $item['harga_satuan_bahan'] : '',
				isset($item['spop']) ? $item['spop'] : '',
				isset($item['jumlah_bahan']) ? $item['jumlah_bahan'] : '',
				isset($item['tgl_transaksi']) ? $item['tgl_transaksi'] : '',
			);
		case 'pecah_tidak_manual':
			return array(
				$nomor,
				isset($item['uraian']) ? $item['uraian'] : '',
				isset($item['satuan']) ? $item['satuan'] : '',
				isset($item['harga_satuan']) ? $item['harga_satuan'] : '',
				isset($item['spop']) ? $item['spop'] : '',
				isset($item['jumlah']) ? $item['jumlah'] : '',
			);
		default:
			return array($nomor);
	}
}

function persediaan_compare_get_items_by_jenis($result, $jenis)
{
	if (!is_array($result)) {
		return array();
	}

	switch ($jenis) {
		case 'total10_kosong':
			return isset($result['items_total10_kosong']) ? $result['items_total10_kosong'] : array();
		case 'tidak_di_tabel':
			return isset($result['items_tidak_di_tabel']) ? $result['items_tidak_di_tabel'] : array();
		case 'hanya_tabel':
			return isset($result['items_hanya_tabel']) ? $result['items_hanya_tabel'] : array();
		case 'cocok':
			return isset($result['items_cocok']) ? $result['items_cocok'] : array();
		case 'pembelian_tidak_manual':
			return isset($result['items_pembelian_tidak_manual']) ? $result['items_pembelian_tidak_manual'] : array();
		case 'penjualan_tidak_manual':
			return isset($result['items_penjualan_tidak_manual']) ? $result['items_penjualan_tidak_manual'] : array();
		case 'produksi_tidak_manual':
			return isset($result['items_produksi_tidak_manual']) ? $result['items_produksi_tidak_manual'] : array();
		case 'pecah_tidak_manual':
			return isset($result['items_pecah_tidak_manual']) ? $result['items_pecah_tidak_manual'] : array();
		default:
			return array();
	}
}

function persediaan_compare_build_manual_key_lookup($compare_rows, $map)
{
	$keys = array();
	foreach ($compare_rows as $row) {
		$key = persediaan_compare_build_key(
			persediaan_compare_row_get($row, $map['nama']),
			persediaan_compare_row_get($row, $map['satuan']),
			persediaan_compare_row_get($row, $map['hpp']),
			persediaan_compare_row_get($row, $map['spop'])
		);
		if ($key !== '') {
			$keys[$key] = true;
		}
	}

	return $keys;
}

function persediaan_compare_build_manual_by_nama($compare_rows, $map)
{
	$by_nama = array();
	foreach ($compare_rows as $row) {
		$nama = persediaan_compare_row_get($row, $map['nama']);
		$nama_key = persediaan_recalculate_normalize_nama($nama);
		if ($nama_key === '') {
			continue;
		}
		if (!isset($by_nama[$nama_key])) {
			$by_nama[$nama_key] = array();
		}
		$by_nama[$nama_key][] = array(
			'satuan' => persediaan_compare_row_get($row, $map['satuan']),
			'hpp' => persediaan_compare_row_get($row, $map['hpp']),
			'spop' => persediaan_compare_row_get($row, $map['spop']),
		);
	}

	return $by_nama;
}

function persediaan_compare_tx_satuan_label($satuan)
{
	$s = persediaan_recalculate_sanitize_satuan_persediaan($satuan);
	return ($s === '') ? '0' : $s;
}

function persediaan_compare_tx_keterangan_nama_manual($manual_by_nama, $nama, $satuan, $hpp, $spop, $compare_hpp = true)
{
	$nama_key = persediaan_recalculate_normalize_nama($nama);
	if ($nama_key === '' || empty($manual_by_nama[$nama_key])) {
		return '';
	}

	$tx_satuan = persediaan_compare_tx_satuan_label($satuan);
	$tx_hpp = persediaan_compare_excel_all_normalize_numeric_display($hpp);
	$tx_spop = persediaan_compare_excel_all_normalize_spop_display($spop);

	$messages = array();
	$row_no = 0;
	foreach ($manual_by_nama[$nama_key] as $m) {
		$row_no++;
		$m_satuan = persediaan_compare_tx_satuan_label($m['satuan']);
		$m_hpp = persediaan_compare_excel_all_normalize_numeric_display($m['hpp']);
		$m_spop = persediaan_compare_excel_all_normalize_spop_display($m['spop']);

		$diff_parts = array();
		if ($m_satuan !== $tx_satuan) {
			$diff_parts[] = 'beda satuan (manual: ' . $m_satuan . ', data: ' . $tx_satuan . ')';
		}
		if ($compare_hpp && $m_hpp !== $tx_hpp) {
			$diff_parts[] = 'beda harga satuan (manual: ' . $m_hpp . ', data: ' . $tx_hpp . ')';
		}
		if ($m_spop !== $tx_spop) {
			$diff_parts[] = 'beda SPOP (manual: ' . $m_spop . ', data: ' . $tx_spop . ')';
		}

		if (empty($diff_parts)) {
			continue;
		}

		$prefix = count($manual_by_nama[$nama_key]) > 1
			? ('Baris manual #' . $row_no . ': ')
			: 'Nama barang sama di tabel manual, ';
		$messages[] = $prefix . implode('; ', $diff_parts);
	}

	return implode(' | ', $messages);
}

function persediaan_compare_tx_key_in_manual($manual_keys, $key)
{
	return ($key !== '' && isset($manual_keys[$key]));
}

function persediaan_compare_collect_tx_not_in_manual($CI, $bulan, $compare_rows, $map, $persediaan_rows)
{
	$empty = array(
		'pembelian' => array(),
		'penjualan' => array(),
		'produksi' => array(),
		'pecah_satuan' => array(),
	);

	$bulan = trim((string) $bulan);
	if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
		return $empty;
	}

	$ts = strtotime($bulan . '-01');
	if ($ts === false) {
		return $empty;
	}

	$tgl_awal = date('Y-m-01', $ts);
	$tgl_akhir = date('Y-m-t', $ts);
	$manual_keys = persediaan_compare_build_manual_key_lookup($compare_rows, $map);
	$manual_by_nama = persediaan_compare_build_manual_by_nama($compare_rows, $map);
	$lists = persediaan_rekonsiliasi_tx_load_transaction_lists($CI, $tgl_awal, $tgl_akhir);
	$spop_maps = persediaan_compare_excel_all_build_spop_maps($CI, $persediaan_rows);

	$items_pembelian = array();
	foreach ($lists['pembelian'] as $r) {
		$key = persediaan_compare_excel_all_pembelian_full_key($r);
		if (persediaan_compare_tx_key_in_manual($manual_keys, $key)) {
			continue;
		}
		$nama = isset($r->uraian) ? $r->uraian : '';
		$satuan = isset($r->satuan) ? $r->satuan : '';
		$harga = isset($r->harga_satuan) ? $r->harga_satuan : '';
		$spop = isset($r->spop) ? $r->spop : '';
		$items_pembelian[] = array(
			'uraian' => $nama,
			'satuan' => $satuan,
			'harga_satuan' => persediaan_compare_excel_all_normalize_numeric_display($harga),
			'spop' => persediaan_compare_excel_all_normalize_spop_display($spop),
			'jumlah' => persediaan_compare_excel_all_normalize_numeric_display(isset($r->jumlah) ? $r->jumlah : ''),
			'tgl_po' => persediaan_compare_excel_all_format_tanggal_display(isset($r->tgl_po) ? $r->tgl_po : ''),
			'keterangan' => persediaan_compare_tx_keterangan_nama_manual(
				$manual_by_nama,
				$nama,
				$satuan,
				$harga,
				$spop
			),
		);
	}

	$items_penjualan = array();
	foreach ($lists['penjualan'] as $r) {
		$spop = persediaan_compare_excel_all_penjualan_spop($CI, $r, $spop_maps);
		$key = persediaan_compare_excel_all_full_key(
			isset($r->nama_barang) ? $r->nama_barang : '',
			isset($r->satuan) ? $r->satuan : '',
			isset($r->harga_satuan) ? $r->harga_satuan : '',
			$spop
		);
		if (persediaan_compare_tx_key_in_manual($manual_keys, $key)) {
			continue;
		}
		$nama = isset($r->nama_barang) ? $r->nama_barang : '';
		$satuan = isset($r->satuan) ? $r->satuan : '';
		$harga = isset($r->harga_satuan) ? $r->harga_satuan : '';
		$items_penjualan[] = array(
			'nama_barang' => $nama,
			'satuan' => $satuan,
			'harga_satuan' => persediaan_compare_excel_all_normalize_numeric_display($harga),
			'spop' => persediaan_compare_excel_all_normalize_spop_display($spop),
			'jumlah' => persediaan_compare_excel_all_normalize_numeric_display(isset($r->jumlah) ? $r->jumlah : ''),
			'tgl_jual' => persediaan_compare_excel_all_format_tanggal_display(isset($r->tgl_jual) ? $r->tgl_jual : ''),
			'keterangan' => persediaan_compare_tx_keterangan_nama_manual(
				$manual_by_nama,
				$nama,
				$satuan,
				$harga,
				$spop,
				false
			),
		);
	}

	$items_produksi = array();
	foreach ($lists['produksi'] as $r) {
		$spop = persediaan_compare_excel_all_produksi_spop($spop_maps, $r);
		$key = persediaan_compare_excel_all_produksi_full_key($r, $spop);
		if (persediaan_compare_tx_key_in_manual($manual_keys, $key)) {
			continue;
		}
		$items_produksi[] = array(
			'nama_barang_bahan' => isset($r->nama_barang_bahan) ? $r->nama_barang_bahan : '',
			'satuan_bahan' => isset($r->satuan_bahan) ? $r->satuan_bahan : '',
			'harga_satuan_bahan' => isset($r->harga_satuan_bahan) ? $r->harga_satuan_bahan : '',
			'spop' => $spop,
			'jumlah_bahan' => isset($r->jumlah_bahan) ? $r->jumlah_bahan : '',
			'tgl_transaksi' => isset($r->tgl_transaksi) ? $r->tgl_transaksi : '',
		);
	}

	$items_pecah = array();
	foreach ($lists['pecah_satuan'] as $r) {
		$spop = isset($r->spop) ? $r->spop : '';
		$key = persediaan_compare_excel_all_full_key(
			isset($r->uraian) ? $r->uraian : '',
			isset($r->satuan) ? $r->satuan : '',
			isset($r->harga_satuan) ? $r->harga_satuan : '',
			$spop
		);
		if (persediaan_compare_tx_key_in_manual($manual_keys, $key)) {
			continue;
		}
		$items_pecah[] = array(
			'uraian' => isset($r->uraian) ? $r->uraian : '',
			'satuan' => isset($r->satuan) ? $r->satuan : '',
			'harga_satuan' => isset($r->harga_satuan) ? $r->harga_satuan : '',
			'spop' => $spop,
			'jumlah' => isset($r->jumlah) ? $r->jumlah : '',
		);
	}

	return array(
		'pembelian' => $items_pembelian,
		'penjualan' => $items_penjualan,
		'produksi' => $items_produksi,
		'pecah_satuan' => $items_pecah,
	);
}

function persediaan_compare_run($CI, $bulan, $table)
{
	$CI->load->helper('persediaan_display');

	if (!preg_match('/^\d{4}-\d{2}$/', trim((string) $bulan))) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');
	}

	$loaded = persediaan_compare_load_manual_rows_for_bulan($CI, $table, $bulan);
	if (empty($loaded['ok'])) {
		return array('ok' => false, 'message' => $loaded['message']);
	}

	$map = $loaded['map'];
	$compare_rows = $loaded['rows'];
	$persediaan_rows = persediaan_compare_get_persediaan_bulan_rows($CI, $bulan);

	$compare_by_key = array();
	foreach ($compare_rows as $row) {
		$key = persediaan_compare_build_key(
			persediaan_compare_row_get($row, $map['nama']),
			persediaan_compare_row_get($row, $map['satuan']),
			persediaan_compare_row_get($row, $map['hpp']),
			persediaan_compare_row_get($row, $map['spop'])
		);
		if ($key === '') {
			continue;
		}
		if (!isset($compare_by_key[$key])) {
			$compare_by_key[$key] = array();
		}
		$compare_by_key[$key][] = $row;
	}

	$items_total10_kosong = array();
	$items_tidak_di_tabel = array();
	$items_hanya_tabel = array();
	$items_cocok = array();
	$stats = array(
		'total_persediaan' => count($persediaan_rows),
		'total_tabel' => count($compare_rows),
		'total10_kosong' => 0,
		'tidak_di_tabel' => 0,
		'cocok' => 0,
		'hanya_tabel' => 0,
	);

	foreach ($persediaan_rows as $prow) {
		if (persediaan_compare_total10_kosong($prow)) {
			$items_total10_kosong[] = persediaan_compare_item_persediaan_only($prow);
			$stats['total10_kosong']++;
		}
	}

	foreach ($persediaan_rows as $prow) {
		$key = persediaan_compare_build_key(
			isset($prow->namabarang) ? $prow->namabarang : '',
			isset($prow->satuan) ? $prow->satuan : '',
			isset($prow->hpp) ? $prow->hpp : '',
			isset($prow->spop) ? $prow->spop : ''
		);

		$crow = null;
		if ($key !== '' && !empty($compare_by_key[$key])) {
			$crow = array_shift($compare_by_key[$key]);
			if (count($compare_by_key[$key]) === 0) {
				unset($compare_by_key[$key]);
			}
			$stats['cocok']++;
			$items_cocok[] = array_merge(
				persediaan_compare_format_side($prow, 'p'),
				persediaan_compare_format_side($crow, 'c', $map)
			);
		} else {
			$stats['tidak_di_tabel']++;
			$items_tidak_di_tabel[] = array_merge(
				persediaan_compare_format_side($prow, 'p'),
				persediaan_compare_format_side(null, 'c', $map)
			);
		}
	}

	foreach ($compare_by_key as $key => $list) {
		foreach ($list as $crow) {
			$stats['hanya_tabel']++;
			$items_hanya_tabel[] = array_merge(
				persediaan_compare_format_side(null, 'p'),
				persediaan_compare_format_side($crow, 'c', $map)
			);
		}
	}

	$tx_not_manual = persediaan_compare_collect_tx_not_in_manual($CI, $bulan, $compare_rows, $map, $persediaan_rows);
	$items_pembelian_tidak_manual = $tx_not_manual['pembelian'];
	$items_penjualan_tidak_manual = $tx_not_manual['penjualan'];
	$items_produksi_tidak_manual = $tx_not_manual['produksi'];
	$items_pecah_tidak_manual = $tx_not_manual['pecah_satuan'];
	$stats['pembelian_tidak_manual'] = count($items_pembelian_tidak_manual);
	$stats['penjualan_tidak_manual'] = count($items_penjualan_tidak_manual);
	$stats['produksi_tidak_manual'] = count($items_produksi_tidak_manual);
	$stats['pecah_tidak_manual'] = count($items_pecah_tidak_manual);

	return array(
		'ok' => true,
		'bulan' => $bulan,
		'bulan_label' => date('m/Y', strtotime($bulan . '-01')),
		'table' => $table,
		'column_map' => $map,
		'stats' => $stats,
		'items_total10_kosong' => $items_total10_kosong,
		'items_tidak_di_tabel' => $items_tidak_di_tabel,
		'items_hanya_tabel' => $items_hanya_tabel,
		'items_cocok' => $items_cocok,
		'items_pembelian_tidak_manual' => $items_pembelian_tidak_manual,
		'items_penjualan_tidak_manual' => $items_penjualan_tidak_manual,
		'items_produksi_tidak_manual' => $items_produksi_tidak_manual,
		'items_pecah_tidak_manual' => $items_pecah_tidak_manual,
	);
}

function persediaan_compare_export_excel_output($CI, $bulan, $table, $jenis = 'cocok')
{
	$CI->load->helper(array('exportexcel', 'persediaan_display'));

	$defs = persediaan_compare_jenis_definitions();
	if (!isset($defs[$jenis])) {
		xlsBOF();
		xlsWriteLabel(0, 0, 'Jenis export compare tidak valid.');
		xlsEOF();
		return;
	}

	$result = persediaan_compare_run($CI, $bulan, $table);
	if (empty($result['ok'])) {
		xlsBOF();
		xlsWriteLabel(0, 0, isset($result['message']) ? $result['message'] : 'Compare gagal.');
		xlsEOF();
		return;
	}

	$def = $defs[$jenis];
	$items = persediaan_compare_get_items_by_jenis($result, $jenis);

	xlsBOF();
	xlsWriteLabelBold14(0, 0, $def['title'] . ' — ' . $result['table'] . ' — Bulan ' . $result['bulan_label']);
	xlsWriteLabel(1, 0, 'Dicetak: ' . date('d/m/Y H:i:s'));

	$col = 0;
	foreach ($def['headers'] as $h) {
		xlsWriteLabel(3, $col++, $h);
	}

	$row = 4;
	$no = 0;
	foreach ($items as $item) {
		$no++;
		$cells = persediaan_compare_item_to_row_cells($jenis, $item, $no);
		$col = 0;
		foreach ($cells as $cell) {
			xlsWriteLabel($row, $col++, $cell);
		}
		$row++;
	}

	xlsEOF();
}

/**
 * Compare tab — export Excel ALL (manual + persediaan + pembelian + penjualan + produksi + pecah satuan).
 */
function persediaan_compare_excel_all_col_starts()
{
	return array(
		'manual' => 0,
		'persediaan' => 10,
		'pembelian' => 20,
		'penjualan' => 28,
		'produksi' => 35,
		'pecah' => 42,
	);
}

function persediaan_compare_excel_all_group_definitions()
{
	return array(
		array('title' => 'DATA MANUAL', 'col_start' => 0, 'col_end' => 8),
		array('title' => 'DATA PERSEDIAAN', 'col_start' => 10, 'col_end' => 18),
		array('title' => 'DATA PEMBELIAN', 'col_start' => 20, 'col_end' => 26),
		array('title' => 'DATA PENJUALAN', 'col_start' => 28, 'col_end' => 33),
		array('title' => 'DATA PRODUKSI', 'col_start' => 35, 'col_end' => 40),
		array('title' => 'DATA PECAH SATUAN', 'col_start' => 42, 'col_end' => 47),
	);
}

function persediaan_compare_excel_all_headers()
{
	return array(
		'Nama Barang', 'Satuan', 'HPP', 'SPOP', 'SA', 'Beli', 'TUJ', '10', 'Nilai Persediaan', '',
		'Namabarang', 'Satuan', 'HPP', 'SPOP', 'SA', 'Beli', 'TUJ', 'Total_10', 'Nilai Persediaan', '',
		'Tgl Beli', 'SPOP', 'Nama Barang', 'Satuan', 'Harga Satuan', 'Jumlah', 'Total Nominal', '',
		'Tgl Jual', 'Nama Barang', 'Satuan', 'Harga Satuan', 'Jumlah', 'Total Nominal', '',
		'Tgl Produksi', 'Nama Barang', 'Satuan', 'Harga Satuan', 'SPOP', 'Jumlah', '',
		'Tgl Pecah', 'Nama Barang', 'Satuan', 'Harga Satuan', 'SPOP', 'Jumlah',
	);
}

function persediaan_compare_excel_all_empty_row()
{
	return array_fill(0, 48, '');
}

function persediaan_compare_excel_all_pecah_tanggal($r)
{
	if (!is_object($r)) {
		return '';
	}
	$proses = isset($r->proses_input) ? trim((string) $r->proses_input) : '';
	if ($proses !== '') {
		return $proses;
	}
	return isset($r->tgl_po) ? $r->tgl_po : '';
}

function persediaan_compare_excel_all_pick_col_10($fields)
{
	$col = persediaan_compare_pick_column($fields, array('total_10', 'col_10'));
	if ($col !== null) {
		return $col;
	}

	if (!is_array($fields)) {
		return null;
	}

	foreach ($fields as $f) {
		if (trim((string) $f) === '10') {
			return $f;
		}
	}

	return null;
}

function persediaan_compare_excel_all_detect_manual_extra_cols($fields)
{
	return array(
		'sa' => persediaan_compare_pick_column($fields, array('sa')),
		'beli' => persediaan_compare_pick_column($fields, array('beli')),
		'tuj' => persediaan_compare_pick_column($fields, array('tuj')),
		'col_10' => persediaan_compare_excel_all_pick_col_10($fields),
		'nilai_persediaan' => persediaan_compare_pick_column($fields, array(
			'nilai_persediaan', 'nilai persediaan', 'nilai_persediaan_manual',
		)),
	);
}

function persediaan_compare_excel_all_nama_key($nama)
{
	return persediaan_recalculate_normalize_nama($nama);
}

function persediaan_compare_excel_all_full_key($nama, $satuan, $hpp, $spop)
{
	return persediaan_compare_build_key($nama, $satuan, $hpp, $spop);
}

function persediaan_compare_excel_all_manual_full_key($manual_row, $map)
{
	return persediaan_compare_excel_all_full_key(
		persediaan_compare_row_get($manual_row, $map['nama']),
		persediaan_compare_row_get($manual_row, $map['satuan']),
		persediaan_compare_row_get($manual_row, $map['hpp']),
		persediaan_compare_row_get($manual_row, $map['spop'])
	);
}

function persediaan_compare_excel_all_pers_full_key($prow)
{
	return persediaan_compare_excel_all_full_key(
		isset($prow->namabarang) ? $prow->namabarang : '',
		isset($prow->satuan) ? $prow->satuan : '',
		isset($prow->hpp) ? $prow->hpp : '',
		isset($prow->spop) ? $prow->spop : ''
	);
}

function persediaan_compare_excel_all_pembelian_full_key($r)
{
	return persediaan_compare_excel_all_full_key(
		isset($r->uraian) ? $r->uraian : '',
		isset($r->satuan) ? $r->satuan : '',
		isset($r->harga_satuan) ? $r->harga_satuan : '',
		isset($r->spop) ? $r->spop : ''
	);
}

function persediaan_compare_excel_all_nsh_key($nama, $satuan, $hpp)
{
	return persediaan_recalculate_sync_nama_satuan_hpp_key($nama, $satuan, $hpp);
}

function persediaan_compare_excel_all_manual_nsh_key($manual_row, $map)
{
	return persediaan_compare_excel_all_nsh_key(
		persediaan_compare_row_get($manual_row, $map['nama']),
		persediaan_compare_row_get($manual_row, $map['satuan']),
		persediaan_compare_row_get($manual_row, $map['hpp'])
	);
}

function persediaan_compare_excel_all_penjualan_nsh_key($r)
{
	return persediaan_compare_excel_all_nsh_key(
		isset($r->nama_barang) ? $r->nama_barang : '',
		isset($r->satuan) ? $r->satuan : '',
		isset($r->harga_satuan) ? $r->harga_satuan : ''
	);
}

function persediaan_compare_excel_all_produksi_full_key($r, $spop = '')
{
	return persediaan_compare_excel_all_full_key(
		isset($r->nama_barang_bahan) ? $r->nama_barang_bahan : '',
		isset($r->satuan_bahan) ? $r->satuan_bahan : '',
		isset($r->harga_satuan_bahan) ? $r->harga_satuan_bahan : '',
		$spop
	);
}

function persediaan_compare_excel_all_index_row(&$index, $key, $ri)
{
	if ($key === '') {
		return;
	}
	if (!isset($index[$key])) {
		$index[$key] = array();
	}
	if (!in_array($ri, $index[$key], true)) {
		$index[$key][] = $ri;
	}
}

function persediaan_compare_excel_all_row_sort_key($row)
{
	$tuple = persediaan_compare_excel_all_row_sort_tuple($row);
	return $tuple[0];
}

function persediaan_compare_excel_all_row_sort_tuple($row)
{
	$starts = persediaan_compare_excel_all_col_starts();
	$s = $starts['manual'];
	if (isset($row[$s]) && trim((string) $row[$s]) !== '') {
		return array(
			trim((string) $row[$s]),
			trim((string) (isset($row[$s + 1]) ? $row[$s + 1] : '')),
			trim((string) (isset($row[$s + 2]) ? $row[$s + 2] : '')),
			trim((string) (isset($row[$s + 3]) ? $row[$s + 3] : '')),
		);
	}

	$s = $starts['persediaan'];
	if (isset($row[$s]) && trim((string) $row[$s]) !== '') {
		return array(
			trim((string) $row[$s]),
			trim((string) (isset($row[$s + 1]) ? $row[$s + 1] : '')),
			trim((string) (isset($row[$s + 2]) ? $row[$s + 2] : '')),
			trim((string) (isset($row[$s + 3]) ? $row[$s + 3] : '')),
		);
	}

	$s = $starts['pembelian'];
	if (isset($row[$s + 2]) && trim((string) $row[$s + 2]) !== '') {
		return array(
			trim((string) $row[$s + 2]),
			trim((string) (isset($row[$s + 3]) ? $row[$s + 3] : '')),
			trim((string) (isset($row[$s + 4]) ? $row[$s + 4] : '')),
			trim((string) (isset($row[$s + 1]) ? $row[$s + 1] : '')),
		);
	}

	$s = $starts['penjualan'];
	if (isset($row[$s + 1]) && trim((string) $row[$s + 1]) !== '') {
		return array(
			trim((string) $row[$s + 1]),
			trim((string) (isset($row[$s + 2]) ? $row[$s + 2] : '')),
			trim((string) (isset($row[$s + 3]) ? $row[$s + 3] : '')),
			'',
		);
	}

	$s = $starts['produksi'];
	if (isset($row[$s + 1]) && trim((string) $row[$s + 1]) !== '') {
		return array(
			trim((string) $row[$s + 1]),
			trim((string) (isset($row[$s + 2]) ? $row[$s + 2] : '')),
			trim((string) (isset($row[$s + 3]) ? $row[$s + 3] : '')),
			trim((string) (isset($row[$s + 4]) ? $row[$s + 4] : '')),
		);
	}

	$s = $starts['pecah'];
	if (isset($row[$s + 1]) && trim((string) $row[$s + 1]) !== '') {
		return array(
			trim((string) $row[$s + 1]),
			trim((string) (isset($row[$s + 2]) ? $row[$s + 2] : '')),
			trim((string) (isset($row[$s + 3]) ? $row[$s + 3] : '')),
			trim((string) (isset($row[$s + 4]) ? $row[$s + 4] : '')),
		);
	}

	return array('', '', '', '');
}

function persediaan_compare_excel_all_sort_rows(&$rows)
{
	if (!is_array($rows) || count($rows) < 2) {
		return;
	}

	usort($rows, function ($a, $b) {
		$tuple_a = persediaan_compare_excel_all_row_sort_tuple($a);
		$tuple_b = persediaan_compare_excel_all_row_sort_tuple($b);
		for ($i = 0; $i < 4; $i++) {
			$cmp = strcasecmp($tuple_a[$i], $tuple_b[$i]);
			if ($cmp !== 0) {
				return $cmp;
			}
		}

		return 0;
	});
}

/**
 * Alokasi baris manual: cocokkan nama+satuan+hpp+spop ke baris yang sama (jika slot manual kosong).
 */
function persediaan_compare_excel_all_alloc_manual_row(&$rows, &$index_by_full_key, &$index_by_nama, &$index_by_manual_nsh, $mr, $map, $extra_cols)
{
	$full_key = persediaan_compare_excel_all_manual_full_key($mr, $map);
	if ($full_key === '') {
		$full_key = '__manual_' . count($rows);
	}

	if (isset($index_by_full_key[$full_key])) {
		foreach ($index_by_full_key[$full_key] as $ri) {
			if (!persediaan_compare_excel_all_section_filled($rows[$ri], 'manual')) {
				persediaan_compare_excel_all_fill_manual($rows[$ri], $mr, $map, $extra_cols);
				return $ri;
			}
		}
	}

	$row = persediaan_compare_excel_all_empty_row();
	persediaan_compare_excel_all_fill_manual($row, $mr, $map, $extra_cols);
	$rows[] = $row;
	$ri = count($rows) - 1;

	persediaan_compare_excel_all_index_row($index_by_full_key, $full_key, $ri);

	$nama_key = persediaan_compare_excel_all_nama_key(persediaan_compare_row_get($mr, $map['nama']));
	if ($nama_key === '') {
		$nama_key = '__manual_' . $ri;
	}
	persediaan_compare_excel_all_index_row($index_by_nama, $nama_key, $ri);

	$nsh_key = persediaan_compare_excel_all_manual_nsh_key($mr, $map);
	if ($nsh_key === '') {
		$nsh_key = '__manual_nsh_' . $ri;
	}
	persediaan_compare_excel_all_index_row($index_by_manual_nsh, $nsh_key, $ri);

	return $ri;
}

/**
 * Alokasi baris persediaan: cocokkan nama+satuan+hpp+spop penuh.
 * Nama sama tetapi satuan/hpp/spop beda → baris baru.
 */
function persediaan_compare_excel_all_alloc_persediaan_row(&$rows, &$index_by_nama, &$index_by_full_key, $prow)
{
	$full_key = persediaan_compare_excel_all_pers_full_key($prow);
	$nama_key = persediaan_compare_excel_all_nama_key(isset($prow->namabarang) ? $prow->namabarang : '');

	if ($full_key === '') {
		$full_key = '__kosong_pers_' . count($rows);
	}

	if (isset($index_by_full_key[$full_key])) {
		foreach ($index_by_full_key[$full_key] as $ri) {
			if (!persediaan_compare_excel_all_section_filled($rows[$ri], 'persediaan')) {
				return $ri;
			}
		}
	}

	$rows[] = persediaan_compare_excel_all_empty_row();
	$ri = count($rows) - 1;

	persediaan_compare_excel_all_index_row($index_by_full_key, $full_key, $ri);
	persediaan_compare_excel_all_index_row($index_by_nama, $nama_key, $ri);

	return $ri;
}

/**
 * Alokasi baris pembelian: cocokkan uraian+satuan+harga_satuan+spop ke baris persediaan (full key).
 */
function persediaan_compare_excel_all_alloc_pembelian_row(&$rows, &$index_by_full_key, $r)
{
	$full_key = persediaan_compare_excel_all_pembelian_full_key($r);

	if ($full_key === '') {
		$full_key = '__kosong_pemb_' . count($rows);
	}

	if (isset($index_by_full_key[$full_key])) {
		foreach ($index_by_full_key[$full_key] as $ri) {
			if (!persediaan_compare_excel_all_section_filled($rows[$ri], 'pembelian')) {
				return $ri;
			}
		}
	}

	$rows[] = persediaan_compare_excel_all_empty_row();
	$ri = count($rows) - 1;
	persediaan_compare_excel_all_index_row($index_by_full_key, $full_key, $ri);

	return $ri;
}

/**
 * Alokasi baris produksi: cocokkan nama+satuan+harga_satuan+spop (full key).
 */
function persediaan_compare_excel_all_alloc_produksi_row(&$rows, &$index_by_full_key, $r, $spop = '')
{
	$full_key = persediaan_compare_excel_all_produksi_full_key($r, $spop);
	if ($full_key === '') {
		$full_key = '__kosong_prod_' . count($rows);
	}

	if (isset($index_by_full_key[$full_key])) {
		foreach ($index_by_full_key[$full_key] as $ri) {
			if (!persediaan_compare_excel_all_section_filled($rows[$ri], 'produksi')) {
				return $ri;
			}
		}
	}

	$rows[] = persediaan_compare_excel_all_empty_row();
	$ri = count($rows) - 1;
	persediaan_compare_excel_all_index_row($index_by_full_key, $full_key, $ri);

	return $ri;
}

/**
 * Alokasi baris pecah satuan: cocokkan nama+satuan+harga_satuan+spop (full key).
 */
function persediaan_compare_excel_all_alloc_pecah_row(&$rows, &$index_by_full_key, $nama, $satuan, $harga, $spop)
{
	$full_key = persediaan_compare_excel_all_full_key($nama, $satuan, $harga, $spop);
	if ($full_key === '') {
		$full_key = '__kosong_pecah_' . count($rows);
	}

	if (isset($index_by_full_key[$full_key])) {
		foreach ($index_by_full_key[$full_key] as $ri) {
			if (!persediaan_compare_excel_all_section_filled($rows[$ri], 'pecah')) {
				return $ri;
			}
		}
	}

	$rows[] = persediaan_compare_excel_all_empty_row();
	$ri = count($rows) - 1;
	persediaan_compare_excel_all_index_row($index_by_full_key, $full_key, $ri);

	return $ri;
}

/**
 * Alokasi baris penjualan: cocokkan nama_barang+satuan+harga_satuan ke baris manual (tanpa spop).
 */
function persediaan_compare_excel_all_alloc_penjualan_row(&$rows, &$index_by_manual_nsh, $r)
{
	$nsh_key = persediaan_compare_excel_all_penjualan_nsh_key($r);

	if ($nsh_key === '') {
		$nsh_key = '__kosong_penj_' . count($rows);
	}

	if (isset($index_by_manual_nsh[$nsh_key])) {
		foreach ($index_by_manual_nsh[$nsh_key] as $ri) {
			if (!persediaan_compare_excel_all_section_filled($rows[$ri], 'penjualan')) {
				return $ri;
			}
		}
	}

	$rows[] = persediaan_compare_excel_all_empty_row();
	$ri = count($rows) - 1;
	persediaan_compare_excel_all_index_row($index_by_manual_nsh, $nsh_key, $ri);

	return $ri;
}

function persediaan_compare_excel_all_section_filled($row, $section)
{
	$starts = persediaan_compare_excel_all_col_starts();
	if (!isset($starts[$section])) {
		return false;
	}
	$col = $starts[$section];
	if ($section === 'pembelian') {
		$col += 2;
	} elseif ($section === 'penjualan' || $section === 'produksi' || $section === 'pecah') {
		$col++;
	}
	return isset($row[$col]) && trim((string) $row[$col]) !== '';
}

function persediaan_compare_excel_all_fill_manual(&$row, $manual_row, $map, $extra_cols = array())
{
	$starts = persediaan_compare_excel_all_col_starts();
	$s = $starts['manual'];
	$extra_cols = is_array($extra_cols) ? $extra_cols : array();

	$row[$s] = persediaan_compare_row_get($manual_row, $map['nama']);
	$row[$s + 1] = persediaan_compare_row_get($manual_row, $map['satuan']);
	$row[$s + 2] = persediaan_compare_excel_all_normalize_numeric_display(
		persediaan_compare_row_get($manual_row, $map['hpp'])
	);
	$row[$s + 3] = persediaan_compare_excel_all_normalize_spop_display(
		persediaan_compare_row_get($manual_row, $map['spop'])
	);

	$sa_col = isset($extra_cols['sa']) ? $extra_cols['sa'] : null;
	$beli_col = isset($extra_cols['beli']) ? $extra_cols['beli'] : null;
	$tuj_col = isset($extra_cols['tuj']) ? $extra_cols['tuj'] : null;
	$col_10 = isset($extra_cols['col_10']) ? $extra_cols['col_10'] : null;
	$nilai_col = isset($extra_cols['nilai_persediaan']) ? $extra_cols['nilai_persediaan'] : null;

	$row[$s + 4] = ($sa_col !== null && $sa_col !== '')
		? persediaan_compare_excel_all_normalize_numeric_display(persediaan_compare_row_get($manual_row, $sa_col))
		: '0';
	$row[$s + 5] = ($beli_col !== null && $beli_col !== '')
		? persediaan_compare_excel_all_normalize_numeric_display(persediaan_compare_row_get($manual_row, $beli_col))
		: '0';
	$row[$s + 6] = ($tuj_col !== null && $tuj_col !== '')
		? persediaan_compare_excel_all_normalize_numeric_display(persediaan_compare_row_get($manual_row, $tuj_col))
		: '0';
	$row[$s + 7] = ($col_10 !== null && $col_10 !== '')
		? persediaan_compare_excel_all_normalize_numeric_display(persediaan_compare_row_get($manual_row, $col_10))
		: '0';
	$row[$s + 8] = ($nilai_col !== null && $nilai_col !== '')
		? persediaan_compare_excel_all_normalize_numeric_display(persediaan_compare_row_get($manual_row, $nilai_col))
		: '0';
}

function persediaan_compare_excel_all_fill_persediaan(&$row, $prow)
{
	$starts = persediaan_compare_excel_all_col_starts();
	$s = $starts['persediaan'];

	$nilai_persediaan = '';
	if (isset($prow->nilai_persediaan) && trim((string) $prow->nilai_persediaan) !== '') {
		$nilai_persediaan = $prow->nilai_persediaan;
	} else {
		$nilai_persediaan = persediaan_hitung_nilai_persediaan_row($prow);
	}

	$row[$s] = isset($prow->namabarang) ? $prow->namabarang : '';
	$row[$s + 1] = isset($prow->satuan) ? $prow->satuan : '';
	$row[$s + 2] = persediaan_compare_excel_all_normalize_numeric_display(isset($prow->hpp) ? $prow->hpp : '');
	$row[$s + 3] = persediaan_compare_excel_all_normalize_spop_display(isset($prow->spop) ? $prow->spop : '');
	$row[$s + 4] = persediaan_compare_excel_all_normalize_numeric_display(isset($prow->sa) ? $prow->sa : '');
	$row[$s + 5] = persediaan_compare_excel_all_normalize_numeric_display(isset($prow->beli) ? $prow->beli : '');
	$row[$s + 6] = persediaan_compare_excel_all_normalize_numeric_display(isset($prow->tuj) ? $prow->tuj : '');
	$row[$s + 7] = persediaan_compare_excel_all_normalize_numeric_display(isset($prow->total_10) ? $prow->total_10 : '');
	$row[$s + 8] = persediaan_compare_excel_all_normalize_numeric_display($nilai_persediaan);
}

function persediaan_compare_excel_all_fill_pembelian(&$row, $r)
{
	$starts = persediaan_compare_excel_all_col_starts();
	$s = $starts['pembelian'];
	$harga = isset($r->harga_satuan) ? $r->harga_satuan : '';
	$jumlah = isset($r->jumlah) ? $r->jumlah : '';
	$row[$s] = persediaan_compare_excel_all_format_tanggal_display(isset($r->tgl_po) ? $r->tgl_po : '');
	$row[$s + 1] = persediaan_compare_excel_all_normalize_spop_display(isset($r->spop) ? $r->spop : '');
	$row[$s + 2] = isset($r->uraian) ? $r->uraian : '';
	$row[$s + 3] = isset($r->satuan) ? $r->satuan : '';
	$row[$s + 4] = persediaan_compare_excel_all_normalize_numeric_display($harga);
	$row[$s + 5] = persediaan_compare_excel_all_normalize_numeric_display($jumlah);
	$row[$s + 6] = persediaan_compare_excel_all_total_nominal_display($harga, $jumlah);
}

function persediaan_compare_excel_all_fill_penjualan(&$row, $r)
{
	$starts = persediaan_compare_excel_all_col_starts();
	$s = $starts['penjualan'];
	$harga = isset($r->harga_satuan) ? $r->harga_satuan : '';
	$jumlah = isset($r->jumlah) ? $r->jumlah : '';
	$row[$s] = persediaan_compare_excel_all_format_tanggal_display(isset($r->tgl_jual) ? $r->tgl_jual : '');
	$row[$s + 1] = isset($r->nama_barang) ? $r->nama_barang : '';
	$row[$s + 2] = isset($r->satuan) ? $r->satuan : '';
	$row[$s + 3] = persediaan_compare_excel_all_normalize_numeric_display($harga);
	$row[$s + 4] = persediaan_compare_excel_all_normalize_numeric_display($jumlah);
	$row[$s + 5] = persediaan_compare_excel_all_total_nominal_display($harga, $jumlah);
}

function persediaan_compare_excel_all_fill_produksi(&$row, $r, $spop = '')
{
	$starts = persediaan_compare_excel_all_col_starts();
	$s = $starts['produksi'];
	$row[$s] = persediaan_compare_excel_all_format_tanggal_display(isset($r->tgl_transaksi) ? $r->tgl_transaksi : '');
	$row[$s + 1] = isset($r->nama_barang_bahan) ? $r->nama_barang_bahan : '';
	$row[$s + 2] = isset($r->satuan_bahan) ? $r->satuan_bahan : '';
	$row[$s + 3] = persediaan_compare_excel_all_normalize_numeric_display(isset($r->harga_satuan_bahan) ? $r->harga_satuan_bahan : '');
	$row[$s + 4] = persediaan_compare_excel_all_normalize_spop_display($spop);
	$row[$s + 5] = persediaan_compare_excel_all_normalize_numeric_display(isset($r->jumlah_bahan) ? $r->jumlah_bahan : '');
}

function persediaan_compare_excel_all_fill_pecah(&$row, $nama, $satuan, $harga, $spop, $jumlah, $tgl = '')
{
	$starts = persediaan_compare_excel_all_col_starts();
	$s = $starts['pecah'];
	$row[$s] = persediaan_compare_excel_all_format_tanggal_display($tgl);
	$row[$s + 1] = $nama;
	$row[$s + 2] = $satuan;
	$row[$s + 3] = persediaan_compare_excel_all_normalize_numeric_display($harga);
	$row[$s + 4] = persediaan_compare_excel_all_normalize_spop_display($spop);
	$row[$s + 5] = persediaan_compare_excel_all_normalize_numeric_display($jumlah);
}

function persediaan_compare_excel_all_alloc_row(&$rows, &$index_by_nama, $nama_key, $section)
{
	if ($nama_key === '') {
		$nama_key = '__kosong_' . count($rows);
	}

	if (!isset($index_by_nama[$nama_key])) {
		$index_by_nama[$nama_key] = array();
	}

	foreach ($index_by_nama[$nama_key] as $ri) {
		if (!persediaan_compare_excel_all_section_filled($rows[$ri], $section)) {
			return $ri;
		}
	}

	$rows[] = persediaan_compare_excel_all_empty_row();
	$ri = count($rows) - 1;
	$index_by_nama[$nama_key][] = $ri;

	return $ri;
}

function persediaan_compare_excel_all_build_spop_maps($CI, $pers_rows)
{
	$by_uuid = array();
	$by_id = array();
	foreach ($pers_rows as $p) {
		$spop = isset($p->spop) ? $p->spop : '';
		$id = isset($p->id) ? (int) $p->id : 0;
		if ($id > 0) {
			$by_id[$id] = $spop;
		}
		$uuid = isset($p->uuid_persediaan) ? trim((string) $p->uuid_persediaan) : '';
		if ($uuid !== '') {
			$by_uuid[$uuid] = $spop;
		}
	}

	return array('by_uuid' => $by_uuid, 'by_id' => $by_id);
}

function persediaan_compare_excel_all_spop_from_maps($maps, $uuid = '', $id = 0)
{
	$uuid = trim((string) $uuid);
	if ($uuid !== '' && isset($maps['by_uuid'][$uuid])) {
		return $maps['by_uuid'][$uuid];
	}
	$id = (int) $id;
	if ($id > 0 && isset($maps['by_id'][$id])) {
		return $maps['by_id'][$id];
	}
	return '';
}

function persediaan_compare_excel_all_penjualan_spop($CI, $row, $maps)
{
	$uuid = isset($row->uuid_persediaan) ? trim((string) $row->uuid_persediaan) : '';
	$id = isset($row->id_persediaan_barang) ? (int) $row->id_persediaan_barang : 0;
	$spop = persediaan_compare_excel_all_spop_from_maps($maps, $uuid, $id);
	if ($spop !== '') {
		return $spop;
	}

	$pers = penjualan_get_persediaan_by_penjualan_row($CI, $row);
	if (!empty($pers) && isset($pers->spop)) {
		return (string) $pers->spop;
	}

	return '';
}

function persediaan_compare_excel_all_collect_unused($index_list, &$used)
{
	$out = array();
	if (!is_array($index_list)) {
		return $out;
	}
	foreach ($index_list as $i) {
		if (empty($used[$i])) {
			$out[] = $i;
		}
	}
	return $out;
}

function persediaan_compare_excel_all_sum_jumlah(&$target, $add)
{
	if ($add === '' || $add === null) {
		return;
	}
	if (!is_numeric($add)) {
		$clean = preg_replace('/[^\d.,\-]/', '', (string) $add);
		$clean = str_replace(',', '.', $clean);
		$add = $clean;
	}
	if (!is_numeric($add)) {
		return;
	}
	$t = is_numeric($target) ? (float) $target : 0;
	$target = $t + (float) $add;
}

function persediaan_compare_excel_all_merge_spop_label(&$existing, $spop)
{
	$spop = trim((string) $spop);
	if ($spop === '') {
		return;
	}
	$existing = trim((string) $existing);
	if ($existing === '') {
		$existing = $spop;
		return;
	}
	$parts = array_map('trim', explode(',', $existing));
	if (!in_array($spop, $parts, true)) {
		$parts[] = $spop;
		$existing = implode(', ', $parts);
	}
}

function persediaan_compare_excel_all_aggregate_pembelian_list($rows)
{
	$bucket = array();
	foreach ($rows as $r) {
		$nama = isset($r->uraian) ? $r->uraian : '';
		$satuan = isset($r->satuan) ? $r->satuan : '';
		$hpp = isset($r->harga_satuan) ? $r->harga_satuan : '';
		$nsh = persediaan_compare_excel_all_nsh_key($nama, $satuan, $hpp);
		if ($nsh === '') {
			continue;
		}
		if (!isset($bucket[$nsh])) {
			$bucket[$nsh] = (object) array(
				'tgl_po' => isset($r->tgl_po) ? $r->tgl_po : '',
				'uraian' => $nama,
				'satuan' => $satuan,
				'harga_satuan' => $hpp,
				'spop' => '',
				'jumlah' => 0,
			);
		}
		$tgl_po = isset($r->tgl_po) ? trim((string) $r->tgl_po) : '';
		if ($tgl_po !== '' && trim((string) $bucket[$nsh]->tgl_po) === '') {
			$bucket[$nsh]->tgl_po = $tgl_po;
		}
		persediaan_compare_excel_all_sum_jumlah(
			$bucket[$nsh]->jumlah,
			isset($r->jumlah) ? $r->jumlah : 0
		);
		persediaan_compare_excel_all_merge_spop_label(
			$bucket[$nsh]->spop,
			isset($r->spop) ? $r->spop : ''
		);
	}

	return array_values($bucket);
}

function persediaan_compare_excel_all_aggregate_penjualan_list($rows)
{
	$bucket = array();
	foreach ($rows as $r) {
		$nama = isset($r->nama_barang) ? $r->nama_barang : '';
		$satuan = isset($r->satuan) ? $r->satuan : '';
		$hpp = isset($r->harga_satuan) ? $r->harga_satuan : '';
		$nsh = persediaan_compare_excel_all_nsh_key($nama, $satuan, $hpp);
		if ($nsh === '') {
			continue;
		}
		if (!isset($bucket[$nsh])) {
			$bucket[$nsh] = (object) array(
				'tgl_jual' => isset($r->tgl_jual) ? $r->tgl_jual : '',
				'nama_barang' => $nama,
				'satuan' => $satuan,
				'harga_satuan' => $hpp,
				'jumlah' => 0,
			);
		}
		$tgl_jual = isset($r->tgl_jual) ? trim((string) $r->tgl_jual) : '';
		if ($tgl_jual !== '' && trim((string) $bucket[$nsh]->tgl_jual) === '') {
			$bucket[$nsh]->tgl_jual = $tgl_jual;
		}
		persediaan_compare_excel_all_sum_jumlah(
			$bucket[$nsh]->jumlah,
			isset($r->jumlah) ? $r->jumlah : 0
		);
	}

	return array_values($bucket);
}

function persediaan_compare_excel_all_aggregate_produksi_list($rows, $spop_maps)
{
	$bucket = array();
	foreach ($rows as $r) {
		$nama = isset($r->nama_barang_bahan) ? $r->nama_barang_bahan : '';
		$satuan = isset($r->satuan_bahan) ? $r->satuan_bahan : '';
		$hpp = isset($r->harga_satuan_bahan) ? $r->harga_satuan_bahan : '';
		$nsh = persediaan_compare_excel_all_nsh_key($nama, $satuan, $hpp);
		if ($nsh === '') {
			continue;
		}
		if (!isset($bucket[$nsh])) {
			$bucket[$nsh] = (object) array(
				'tgl_transaksi' => isset($r->tgl_transaksi) ? $r->tgl_transaksi : '',
				'nama_barang_bahan' => $nama,
				'satuan_bahan' => $satuan,
				'harga_satuan_bahan' => $hpp,
				'spop' => '',
				'jumlah_bahan' => 0,
			);
		}
		$tgl_transaksi = isset($r->tgl_transaksi) ? trim((string) $r->tgl_transaksi) : '';
		if ($tgl_transaksi !== '' && trim((string) $bucket[$nsh]->tgl_transaksi) === '') {
			$bucket[$nsh]->tgl_transaksi = $tgl_transaksi;
		}
		persediaan_compare_excel_all_sum_jumlah(
			$bucket[$nsh]->jumlah_bahan,
			isset($r->jumlah_bahan) ? $r->jumlah_bahan : 0
		);
		persediaan_compare_excel_all_merge_spop_label(
			$bucket[$nsh]->spop,
			persediaan_compare_excel_all_produksi_spop($spop_maps, $r)
		);
	}

	return array_values($bucket);
}

function persediaan_compare_excel_all_aggregate_pecah_lists($rows)
{
	$uraian = array();
	$baru = array();

	foreach ($rows as $r) {
		$spop = isset($r->spop) ? $r->spop : '';

		$nama_u = isset($r->uraian) ? $r->uraian : '';
		$satuan_u = isset($r->satuan) ? $r->satuan : '';
		$hpp_u = isset($r->harga_satuan) ? $r->harga_satuan : '';
		$nsh_u = persediaan_compare_excel_all_nsh_key($nama_u, $satuan_u, $hpp_u);
		if ($nsh_u !== '') {
			if (!isset($uraian[$nsh_u])) {
				$uraian[$nsh_u] = (object) array(
					'tgl_pecah' => persediaan_compare_excel_all_pecah_tanggal($r),
					'uraian' => $nama_u,
					'satuan' => $satuan_u,
					'harga_satuan' => $hpp_u,
					'spop' => '',
					'jumlah' => 0,
				);
			}
			$tgl_pecah = persediaan_compare_excel_all_pecah_tanggal($r);
			if (trim((string) $tgl_pecah) !== '' && trim((string) $uraian[$nsh_u]->tgl_pecah) === '') {
				$uraian[$nsh_u]->tgl_pecah = $tgl_pecah;
			}
			persediaan_compare_excel_all_sum_jumlah(
				$uraian[$nsh_u]->jumlah,
				isset($r->jumlah) ? $r->jumlah : 0
			);
			persediaan_compare_excel_all_merge_spop_label($uraian[$nsh_u]->spop, $spop);
		}

		$nama_b = isset($r->nama_barang_baru) ? trim((string) $r->nama_barang_baru) : '';
		if ($nama_b !== '') {
			$satuan_b = isset($r->satuan_barang_baru) ? $r->satuan_barang_baru : '';
			$hpp_b = isset($r->harga_satuan_barang_baru) ? $r->harga_satuan_barang_baru : '';
			$nsh_b = persediaan_compare_excel_all_nsh_key($nama_b, $satuan_b, $hpp_b);
			if ($nsh_b !== '') {
				if (!isset($baru[$nsh_b])) {
					$baru[$nsh_b] = (object) array(
						'tgl_pecah' => persediaan_compare_excel_all_pecah_tanggal($r),
						'nama_barang_baru' => $nama_b,
						'satuan_barang_baru' => $satuan_b,
						'harga_satuan_barang_baru' => $hpp_b,
						'spop' => '',
						'jumlah_barang_baru' => 0,
					);
				}
				$tgl_pecah = persediaan_compare_excel_all_pecah_tanggal($r);
				if (trim((string) $tgl_pecah) !== '' && trim((string) $baru[$nsh_b]->tgl_pecah) === '') {
					$baru[$nsh_b]->tgl_pecah = $tgl_pecah;
				}
				persediaan_compare_excel_all_sum_jumlah(
					$baru[$nsh_b]->jumlah_barang_baru,
					isset($r->jumlah_barang_baru) ? $r->jumlah_barang_baru : 0
				);
				persediaan_compare_excel_all_merge_spop_label($baru[$nsh_b]->spop, $spop);
			}
		}
	}

	return array(
		'uraian' => array_values($uraian),
		'baru' => array_values($baru),
	);
}

function persediaan_compare_excel_all_index_nsh_list($rows, $nama_field, $satuan_field, $hpp_field)
{
	$index = array();
	foreach ($rows as $i => $r) {
		$nsh = persediaan_compare_excel_all_nsh_key(
			isset($r->{$nama_field}) ? $r->{$nama_field} : '',
			isset($r->{$satuan_field}) ? $r->{$satuan_field} : '',
			isset($r->{$hpp_field}) ? $r->{$hpp_field} : ''
		);
		if ($nsh === '') {
			continue;
		}
		$index[$nsh] = array($i);
	}

	return $index;
}

function persediaan_compare_excel_all_build_tx_indexes($CI, $pers_rows, $lists, $spop_maps)
{
	$index_pers = array();
	foreach ($pers_rows as $i => $pr) {
		$key = persediaan_compare_excel_all_pers_full_key($pr);
		if ($key === '') {
			continue;
		}
		if (!isset($index_pers[$key])) {
			$index_pers[$key] = array();
		}
		$index_pers[$key][] = $i;
	}

	$agg_pembelian = persediaan_compare_excel_all_aggregate_pembelian_list($lists['pembelian']);
	$agg_penjualan = persediaan_compare_excel_all_aggregate_penjualan_list($lists['penjualan']);
	$agg_produksi = persediaan_compare_excel_all_aggregate_produksi_list($lists['produksi'], $spop_maps);
	$agg_pecah = persediaan_compare_excel_all_aggregate_pecah_lists($lists['pecah_satuan']);

	$index_pembelian = persediaan_compare_excel_all_index_nsh_list(
		$agg_pembelian,
		'uraian',
		'satuan',
		'harga_satuan'
	);
	$index_penjualan = persediaan_compare_excel_all_index_nsh_list(
		$agg_penjualan,
		'nama_barang',
		'satuan',
		'harga_satuan'
	);
	$index_produksi = persediaan_compare_excel_all_index_nsh_list(
		$agg_produksi,
		'nama_barang_bahan',
		'satuan_bahan',
		'harga_satuan_bahan'
	);

	$index_pecah_uraian = array();
	foreach ($agg_pecah['uraian'] as $i => $r) {
		$nsh = persediaan_compare_excel_all_nsh_key(
			isset($r->uraian) ? $r->uraian : '',
			isset($r->satuan) ? $r->satuan : '',
			isset($r->harga_satuan) ? $r->harga_satuan : ''
		);
		if ($nsh !== '') {
			$index_pecah_uraian[$nsh] = array($i);
		}
	}

	$index_pecah_baru = array();
	foreach ($agg_pecah['baru'] as $i => $r) {
		$nsh = persediaan_compare_excel_all_nsh_key(
			isset($r->nama_barang_baru) ? $r->nama_barang_baru : '',
			isset($r->satuan_barang_baru) ? $r->satuan_barang_baru : '',
			isset($r->harga_satuan_barang_baru) ? $r->harga_satuan_barang_baru : ''
		);
		if ($nsh !== '') {
			$index_pecah_baru[$nsh] = array($i);
		}
	}

	return array(
		'pers' => $index_pers,
		'pembelian' => $index_pembelian,
		'penjualan' => $index_penjualan,
		'produksi' => $index_produksi,
		'pecah_uraian' => $index_pecah_uraian,
		'pecah_baru' => $index_pecah_baru,
		'agg' => array(
			'pembelian' => $agg_pembelian,
			'penjualan' => $agg_penjualan,
			'produksi' => $agg_produksi,
			'pecah_uraian' => $agg_pecah['uraian'],
			'pecah_baru' => $agg_pecah['baru'],
		),
	);
}

function persediaan_compare_excel_all_produksi_spop($maps, $row)
{
	$uuid = isset($row->uuid_persediaan_bahan) ? trim((string) $row->uuid_persediaan_bahan) : '';
	if ($uuid !== '') {
		return persediaan_compare_excel_all_spop_from_maps($maps, $uuid, 0);
	}
	$uuid2 = isset($row->uuid_persediaan) ? trim((string) $row->uuid_persediaan) : '';
	return persediaan_compare_excel_all_spop_from_maps($maps, $uuid2, 0);
}

function persediaan_compare_excel_all_row_matches_manual($nama, $satuan, $hpp, $spop, $m_nama, $m_satuan, $m_hpp, $m_spop)
{
	$key_manual = persediaan_compare_excel_all_full_key($m_nama, $m_satuan, $m_hpp, $m_spop);
	if ($key_manual === '') {
		return false;
	}

	return persediaan_compare_excel_all_full_key($nama, $satuan, $hpp, $spop) === $key_manual;
}

function persediaan_compare_excel_all_build_manual_by_key($manual_rows, $map)
{
	$by_key = array();
	foreach ($manual_rows as $mi => $mr) {
		$key = persediaan_compare_excel_all_manual_full_key($mr, $map);
		if ($key === '') {
			continue;
		}
		if (!isset($by_key[$key])) {
			$by_key[$key] = array();
		}
		$by_key[$key][] = array('i' => $mi, 'row' => $mr);
	}

	return $by_key;
}

function persediaan_compare_excel_all_register_nsh_only(&$nsh_by_nama, $nama_key, $nsh_key)
{
	if ($nama_key === '' || $nsh_key === '') {
		return;
	}
	if (!isset($nsh_by_nama[$nama_key])) {
		$nsh_by_nama[$nama_key] = array();
	}
	if (!in_array($nsh_key, $nsh_by_nama[$nama_key], true)) {
		$nsh_by_nama[$nama_key][] = $nsh_key;
	}
}

function persediaan_compare_excel_all_track_nama_label(&$manual_labels, &$other_labels, $nama, $from_manual = false)
{
	$nama_key = persediaan_compare_excel_all_nama_key($nama);
	if ($nama_key === '') {
		return '';
	}

	$label = trim((string) $nama);
	if ($from_manual) {
		if (!isset($manual_labels[$nama_key])) {
			$manual_labels[$nama_key] = $label;
		}
	} elseif (!isset($manual_labels[$nama_key]) && !isset($other_labels[$nama_key])) {
		$other_labels[$nama_key] = $label;
	}

	return $nama_key;
}

function persediaan_compare_excel_all_compare_labels($a, $b)
{
	return strcasecmp((string) $a, (string) $b);
}

function persediaan_compare_excel_all_sort_nama_label_map(&$label_map)
{
	if (!is_array($label_map) || count($label_map) < 2) {
		return;
	}

	uasort($label_map, 'persediaan_compare_excel_all_compare_labels');
}

function persediaan_compare_excel_all_register_nama_nsh(&$nama_order, &$nsh_by_nama, $nama, $nsh_key)
{
	$nama_key = persediaan_compare_excel_all_nama_key($nama);
	if ($nama_key === '') {
		return;
	}
	if (!isset($nama_order[$nama_key])) {
		$nama_order[$nama_key] = count($nama_order);
	}
	persediaan_compare_excel_all_register_nsh_only($nsh_by_nama, $nama_key, $nsh_key);
}

function persediaan_compare_excel_all_build_nama_groups($manual_rows, $map, $pers_rows, $tx_index)
{
	$nsh_by_nama = array();
	$manual_nama_labels = array();
	$other_nama_labels = array();

	foreach ($manual_rows as $mr) {
		$nama = persediaan_compare_row_get($mr, $map['nama']);
		$nama_key = persediaan_compare_excel_all_track_nama_label(
			$manual_nama_labels,
			$other_nama_labels,
			$nama,
			true
		);
		if ($nama_key === '') {
			continue;
		}
		persediaan_compare_excel_all_register_nsh_only(
			$nsh_by_nama,
			$nama_key,
			persediaan_compare_excel_all_manual_nsh_key($mr, $map)
		);
	}

	foreach ($pers_rows as $pr) {
		$nama = isset($pr->namabarang) ? $pr->namabarang : '';
		$nama_key = persediaan_compare_excel_all_track_nama_label(
			$manual_nama_labels,
			$other_nama_labels,
			$nama,
			false
		);
		if ($nama_key === '') {
			continue;
		}
		persediaan_compare_excel_all_register_nsh_only(
			$nsh_by_nama,
			$nama_key,
			persediaan_compare_excel_all_nsh_key(
				$nama,
				isset($pr->satuan) ? $pr->satuan : '',
				isset($pr->hpp) ? $pr->hpp : ''
			)
		);
	}

	$agg = isset($tx_index['agg']) ? $tx_index['agg'] : array();
	foreach (array('pembelian', 'penjualan', 'produksi') as $jenis) {
		if (empty($tx_index[$jenis]) || empty($agg[$jenis])) {
			continue;
		}
		foreach ($tx_index[$jenis] as $nsh => $idxs) {
			if ($nsh === '' || empty($idxs)) {
				continue;
			}
			$r = $agg[$jenis][$idxs[0]];
			$nama = '';
			if ($jenis === 'pembelian') {
				$nama = isset($r->uraian) ? $r->uraian : '';
			} elseif ($jenis === 'penjualan') {
				$nama = isset($r->nama_barang) ? $r->nama_barang : '';
			} else {
				$nama = isset($r->nama_barang_bahan) ? $r->nama_barang_bahan : '';
			}
			$nama_key = persediaan_compare_excel_all_track_nama_label(
				$manual_nama_labels,
				$other_nama_labels,
				$nama,
				false
			);
			if ($nama_key === '') {
				continue;
			}
			persediaan_compare_excel_all_register_nsh_only($nsh_by_nama, $nama_key, $nsh);
		}
	}

	foreach (array('pecah_uraian', 'pecah_baru') as $jenis) {
		if (empty($tx_index[$jenis])) {
			continue;
		}
		$agg_key = $jenis;
		if (empty($agg[$agg_key])) {
			continue;
		}
		foreach ($tx_index[$jenis] as $nsh => $idxs) {
			if ($nsh === '' || empty($idxs)) {
				continue;
			}
			$r = $agg[$agg_key][$idxs[0]];
			$nama = ($jenis === 'pecah_baru')
				? (isset($r->nama_barang_baru) ? $r->nama_barang_baru : '')
				: (isset($r->uraian) ? $r->uraian : '');
			$nama_key = persediaan_compare_excel_all_track_nama_label(
				$manual_nama_labels,
				$other_nama_labels,
				$nama,
				false
			);
			if ($nama_key === '') {
				continue;
			}
			persediaan_compare_excel_all_register_nsh_only($nsh_by_nama, $nama_key, $nsh);
		}
	}

	persediaan_compare_excel_all_sort_nama_label_map($manual_nama_labels);
	persediaan_compare_excel_all_sort_nama_label_map($other_nama_labels);

	foreach ($nsh_by_nama as &$nsh_list) {
		sort($nsh_list, SORT_STRING);
	}
	unset($nsh_list);

	return array(
		'nama_list' => array_merge(array_keys($manual_nama_labels), array_keys($other_nama_labels)),
		'nsh_by_nama' => $nsh_by_nama,
	);
}

function persediaan_compare_excel_all_append_nsh_block(
	&$rows,
	$nsh_key,
	$manual_rows,
	$map,
	$manual_extra_cols,
	$pers_rows,
	$tx_index,
	&$used_manual,
	&$used_pers,
	&$used_nsh_pembelian,
	&$used_nsh_penjualan,
	&$used_nsh_produksi,
	&$used_nsh_pecah_uraian,
	&$used_nsh_pecah_baru
) {
	if ($nsh_key === '') {
		return;
	}

	$agg = isset($tx_index['agg']) ? $tx_index['agg'] : array();

	$manual_entries = array();
	foreach ($manual_rows as $mi => $mr) {
		if (!empty($used_manual[$mi])) {
			continue;
		}
		if (persediaan_compare_excel_all_manual_nsh_key($mr, $map) !== $nsh_key) {
			continue;
		}
		$manual_entries[] = array('i' => $mi, 'row' => $mr);
	}

	$idx_pers = array();
	foreach ($pers_rows as $i => $pr) {
		if (!empty($used_pers[$i])) {
			continue;
		}
		if (persediaan_compare_excel_all_nsh_key(
			isset($pr->namabarang) ? $pr->namabarang : '',
			isset($pr->satuan) ? $pr->satuan : '',
			isset($pr->hpp) ? $pr->hpp : ''
		) !== $nsh_key) {
			continue;
		}
		$idx_pers[] = $i;
	}

	$pembelian_row = null;
	if (empty($used_nsh_pembelian[$nsh_key]) && isset($tx_index['pembelian'][$nsh_key][0])) {
		$bi = $tx_index['pembelian'][$nsh_key][0];
		if (isset($agg['pembelian'][$bi])) {
			$pembelian_row = $agg['pembelian'][$bi];
		}
	}

	$penjualan_row = null;
	if (empty($used_nsh_penjualan[$nsh_key]) && isset($tx_index['penjualan'][$nsh_key][0])) {
		$ni = $tx_index['penjualan'][$nsh_key][0];
		if (isset($agg['penjualan'][$ni])) {
			$penjualan_row = $agg['penjualan'][$ni];
		}
	}

	$produksi_row = null;
	if (empty($used_nsh_produksi[$nsh_key]) && isset($tx_index['produksi'][$nsh_key][0])) {
		$pri = $tx_index['produksi'][$nsh_key][0];
		if (isset($agg['produksi'][$pri])) {
			$produksi_row = $agg['produksi'][$pri];
		}
	}

	$pecah_uraian_row = null;
	if (empty($used_nsh_pecah_uraian[$nsh_key]) && isset($tx_index['pecah_uraian'][$nsh_key][0])) {
		$pui = $tx_index['pecah_uraian'][$nsh_key][0];
		if (isset($agg['pecah_uraian'][$pui])) {
			$pecah_uraian_row = $agg['pecah_uraian'][$pui];
		}
	}

	$pecah_baru_row = null;
	if (empty($used_nsh_pecah_baru[$nsh_key]) && isset($tx_index['pecah_baru'][$nsh_key][0])) {
		$pbi = $tx_index['pecah_baru'][$nsh_key][0];
		if (isset($agg['pecah_baru'][$pbi])) {
			$pecah_baru_row = $agg['pecah_baru'][$pbi];
		}
	}

	$has_agg = (
		$pembelian_row !== null
		|| $penjualan_row !== null
		|| $produksi_row !== null
		|| $pecah_uraian_row !== null
		|| $pecah_baru_row !== null
	);
	if (count($manual_entries) === 0 && count($idx_pers) === 0 && !$has_agg) {
		return;
	}

	$manual_by_fk = array();
	foreach ($manual_entries as $entry) {
		$fk = persediaan_compare_excel_all_manual_full_key($entry['row'], $map);
		if ($fk === '') {
			$fk = '__manual_nsh_' . $nsh_key . '_' . $entry['i'];
		}
		if (!isset($manual_by_fk[$fk])) {
			$manual_by_fk[$fk] = array();
		}
		$manual_by_fk[$fk][] = $entry;
	}

	$pers_by_fk = array();
	foreach ($idx_pers as $pi) {
		$fk = persediaan_compare_excel_all_pers_full_key($pers_rows[$pi]);
		if ($fk === '') {
			$fk = '__pers_nsh_' . $nsh_key . '_' . $pi;
		}
		if (!isset($pers_by_fk[$fk])) {
			$pers_by_fk[$fk] = array();
		}
		$pers_by_fk[$fk][] = $pi;
	}

	$full_keys = array_keys($manual_by_fk);
	foreach (array_keys($pers_by_fk) as $fk) {
		if (!in_array($fk, $full_keys, true)) {
			$full_keys[] = $fk;
		}
	}
	if (count($full_keys) === 0 && $has_agg) {
		$full_keys[] = '__agg_only_' . $nsh_key;
	}

	$nsh_line = 0;
	foreach ($full_keys as $fk) {
		$m_list = isset($manual_by_fk[$fk]) ? $manual_by_fk[$fk] : array();
		$p_list = isset($pers_by_fk[$fk]) ? $pers_by_fk[$fk] : array();
		$fk_lines = max(count($m_list), count($p_list), ($fk === '__agg_only_' . $nsh_key && $has_agg) ? 1 : 0);
		if ($fk_lines < 1) {
			$fk_lines = 1;
		}

		for ($line = 0; $line < $fk_lines; $line++) {
			$row = persediaan_compare_excel_all_empty_row();

			if (isset($m_list[$line])) {
				persediaan_compare_excel_all_fill_manual(
					$row,
					$m_list[$line]['row'],
					$map,
					$manual_extra_cols
				);
				$used_manual[$m_list[$line]['i']] = true;
			}

			if (isset($p_list[$line])) {
				$pi = $p_list[$line];
				persediaan_compare_excel_all_fill_persediaan($row, $pers_rows[$pi]);
				$used_pers[$pi] = true;
			}

			if ($nsh_line === 0) {
				if ($pembelian_row !== null) {
					persediaan_compare_excel_all_fill_pembelian($row, $pembelian_row);
					$used_nsh_pembelian[$nsh_key] = true;
				}
				if ($penjualan_row !== null) {
					persediaan_compare_excel_all_fill_penjualan($row, $penjualan_row);
					$used_nsh_penjualan[$nsh_key] = true;
				}
				if ($produksi_row !== null) {
					persediaan_compare_excel_all_fill_produksi(
						$row,
						$produksi_row,
						isset($produksi_row->spop) ? $produksi_row->spop : ''
					);
					$used_nsh_produksi[$nsh_key] = true;
				}
				if ($pecah_uraian_row !== null) {
					persediaan_compare_excel_all_fill_pecah(
						$row,
						isset($pecah_uraian_row->uraian) ? $pecah_uraian_row->uraian : '',
						isset($pecah_uraian_row->satuan) ? $pecah_uraian_row->satuan : '',
						isset($pecah_uraian_row->harga_satuan) ? $pecah_uraian_row->harga_satuan : '',
						isset($pecah_uraian_row->spop) ? $pecah_uraian_row->spop : '',
						isset($pecah_uraian_row->jumlah) ? $pecah_uraian_row->jumlah : '',
						isset($pecah_uraian_row->tgl_pecah) ? $pecah_uraian_row->tgl_pecah : ''
					);
					$used_nsh_pecah_uraian[$nsh_key] = true;
				}
				if ($pecah_baru_row !== null) {
					if ($pecah_uraian_row !== null) {
						$rows[] = $row;
						$row = persediaan_compare_excel_all_empty_row();
						if (isset($m_list[$line])) {
							persediaan_compare_excel_all_fill_manual(
								$row,
								$m_list[$line]['row'],
								$map,
								$manual_extra_cols
							);
						}
						if (isset($p_list[$line])) {
							persediaan_compare_excel_all_fill_persediaan($row, $pers_rows[$p_list[$line]]);
						}
					}
					persediaan_compare_excel_all_fill_pecah(
						$row,
						isset($pecah_baru_row->nama_barang_baru) ? $pecah_baru_row->nama_barang_baru : '',
						isset($pecah_baru_row->satuan_barang_baru) ? $pecah_baru_row->satuan_barang_baru : '',
						isset($pecah_baru_row->harga_satuan_barang_baru) ? $pecah_baru_row->harga_satuan_barang_baru : '',
						isset($pecah_baru_row->spop) ? $pecah_baru_row->spop : '',
						isset($pecah_baru_row->jumlah_barang_baru) ? $pecah_baru_row->jumlah_barang_baru : '',
						isset($pecah_baru_row->tgl_pecah) ? $pecah_baru_row->tgl_pecah : ''
					);
					$used_nsh_pecah_baru[$nsh_key] = true;
				}
			}

			$rows[] = $row;
			$nsh_line++;
		}
	}
}

function persediaan_compare_excel_all_build_rows($CI, $bulan, $table)
{
	$CI->load->helper('persediaan_display');

	if (!preg_match('/^\d{4}-\d{2}$/', trim((string) $bulan))) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');
	}

	$order_sql = '';
	$valid_pre = persediaan_compare_validate_table($CI, $table);
	if (!empty($valid_pre['ok']) && is_array($valid_pre['fields']) && in_array('id', $valid_pre['fields'], true)) {
		$order_sql = ' ORDER BY `id` ASC';
	}

	$loaded = persediaan_compare_load_manual_rows_for_bulan($CI, $table, $bulan, $order_sql);
	if (empty($loaded['ok'])) {
		return array('ok' => false, 'message' => $loaded['message']);
	}

	$map = $loaded['map'];
	$fields = $loaded['fields'];
	$manual_extra_cols = persediaan_compare_excel_all_detect_manual_extra_cols($fields);
	$manual_rows = $loaded['rows'];

	$range = $loaded['range'];
	$tgl_awal = $range['tgl_awal'];
	$tgl_akhir = $range['tgl_akhir'];
	$bulan_label = $range['bulan_label'];

	$pers_rows = persediaan_compare_get_persediaan_bulan_rows($CI, $bulan);
	$lists = persediaan_rekonsiliasi_tx_load_transaction_lists($CI, $tgl_awal, $tgl_akhir);
	$spop_maps = persediaan_compare_excel_all_build_spop_maps($CI, $pers_rows);
	$tx_index = persediaan_compare_excel_all_build_tx_indexes($CI, $pers_rows, $lists, $spop_maps);
	$nama_groups = persediaan_compare_excel_all_build_nama_groups($manual_rows, $map, $pers_rows, $tx_index);
	$agg = isset($tx_index['agg']) ? $tx_index['agg'] : array();

	$rows = array();
	$used_manual = array();
	$used_pers = array();
	$used_nsh_pembelian = array();
	$used_nsh_penjualan = array();
	$used_nsh_produksi = array();
	$used_nsh_pecah_uraian = array();
	$used_nsh_pecah_baru = array();

	// Baris manual tanpa identitas lengkap (nama/satuan/hpp/spop kosong).
	foreach ($manual_rows as $mi => $mr) {
		$key_manual = persediaan_compare_excel_all_manual_full_key($mr, $map);
		if ($key_manual !== '') {
			continue;
		}
		$row = persediaan_compare_excel_all_empty_row();
		persediaan_compare_excel_all_fill_manual($row, $mr, $map, $manual_extra_cols);
		$rows[] = $row;
		$used_manual[$mi] = true;
	}

	// Kelompok per nama barang, lalu per nama+satuan+hpp:
	// manual & persediaan per baris (beda spop = baris baru),
	// pembelian/penjualan/produksi/pecah dijumlahkan per NSH.
	foreach ($nama_groups['nama_list'] as $nama_key) {
		if (!isset($nama_groups['nsh_by_nama'][$nama_key])) {
			continue;
		}
		foreach ($nama_groups['nsh_by_nama'][$nama_key] as $nsh_key) {
			persediaan_compare_excel_all_append_nsh_block(
				$rows,
				$nsh_key,
				$manual_rows,
				$map,
				$manual_extra_cols,
				$pers_rows,
				$tx_index,
				$used_manual,
				$used_pers,
				$used_nsh_pembelian,
				$used_nsh_penjualan,
				$used_nsh_produksi,
				$used_nsh_pecah_uraian,
				$used_nsh_pecah_baru
			);
		}
	}

	// Sisa data tanpa kelompok nama / NSH yang teridentifikasi.
	foreach ($pers_rows as $i => $pr) {
		if (!empty($used_pers[$i])) {
			continue;
		}
		$row = persediaan_compare_excel_all_empty_row();
		persediaan_compare_excel_all_fill_persediaan($row, $pr);
		$rows[] = $row;
	}

	foreach (isset($agg['pembelian']) ? $agg['pembelian'] : array() as $r) {
		$nsh = persediaan_compare_excel_all_nsh_key(
			isset($r->uraian) ? $r->uraian : '',
			isset($r->satuan) ? $r->satuan : '',
			isset($r->harga_satuan) ? $r->harga_satuan : ''
		);
		if ($nsh !== '' && !empty($used_nsh_pembelian[$nsh])) {
			continue;
		}
		$row = persediaan_compare_excel_all_empty_row();
		persediaan_compare_excel_all_fill_pembelian($row, $r);
		$rows[] = $row;
	}

	foreach (isset($agg['penjualan']) ? $agg['penjualan'] : array() as $r) {
		$nsh = persediaan_compare_excel_all_nsh_key(
			isset($r->nama_barang) ? $r->nama_barang : '',
			isset($r->satuan) ? $r->satuan : '',
			isset($r->harga_satuan) ? $r->harga_satuan : ''
		);
		if ($nsh !== '' && !empty($used_nsh_penjualan[$nsh])) {
			continue;
		}
		$row = persediaan_compare_excel_all_empty_row();
		persediaan_compare_excel_all_fill_penjualan($row, $r);
		$rows[] = $row;
	}

	foreach (isset($agg['produksi']) ? $agg['produksi'] : array() as $r) {
		$nsh = persediaan_compare_excel_all_nsh_key(
			isset($r->nama_barang_bahan) ? $r->nama_barang_bahan : '',
			isset($r->satuan_bahan) ? $r->satuan_bahan : '',
			isset($r->harga_satuan_bahan) ? $r->harga_satuan_bahan : ''
		);
		if ($nsh !== '' && !empty($used_nsh_produksi[$nsh])) {
			continue;
		}
		$row = persediaan_compare_excel_all_empty_row();
		persediaan_compare_excel_all_fill_produksi($row, $r, isset($r->spop) ? $r->spop : '');
		$rows[] = $row;
	}

	foreach (isset($agg['pecah_uraian']) ? $agg['pecah_uraian'] : array() as $r) {
		$nsh = persediaan_compare_excel_all_nsh_key(
			isset($r->uraian) ? $r->uraian : '',
			isset($r->satuan) ? $r->satuan : '',
			isset($r->harga_satuan) ? $r->harga_satuan : ''
		);
		if ($nsh !== '' && !empty($used_nsh_pecah_uraian[$nsh])) {
			continue;
		}
		$row = persediaan_compare_excel_all_empty_row();
		persediaan_compare_excel_all_fill_pecah(
			$row,
			isset($r->uraian) ? $r->uraian : '',
			isset($r->satuan) ? $r->satuan : '',
			isset($r->harga_satuan) ? $r->harga_satuan : '',
			isset($r->spop) ? $r->spop : '',
			isset($r->jumlah) ? $r->jumlah : '',
			isset($r->tgl_pecah) ? $r->tgl_pecah : ''
		);
		$rows[] = $row;
	}

	foreach (isset($agg['pecah_baru']) ? $agg['pecah_baru'] : array() as $r) {
		$nsh = persediaan_compare_excel_all_nsh_key(
			isset($r->nama_barang_baru) ? $r->nama_barang_baru : '',
			isset($r->satuan_barang_baru) ? $r->satuan_barang_baru : '',
			isset($r->harga_satuan_barang_baru) ? $r->harga_satuan_barang_baru : ''
		);
		if ($nsh !== '' && !empty($used_nsh_pecah_baru[$nsh])) {
			continue;
		}
		$row = persediaan_compare_excel_all_empty_row();
		persediaan_compare_excel_all_fill_pecah(
			$row,
			isset($r->nama_barang_baru) ? $r->nama_barang_baru : '',
			isset($r->satuan_barang_baru) ? $r->satuan_barang_baru : '',
			isset($r->harga_satuan_barang_baru) ? $r->harga_satuan_barang_baru : '',
			isset($r->spop) ? $r->spop : '',
			isset($r->jumlah_barang_baru) ? $r->jumlah_barang_baru : '',
			isset($r->tgl_pecah) ? $r->tgl_pecah : ''
		);
		$rows[] = $row;
	}

	persediaan_compare_excel_all_sort_rows($rows);

	return array(
		'ok' => true,
		'bulan' => $bulan,
		'bulan_label' => $bulan_label,
		'table' => $table,
		'rows' => $rows,
	);
}

function persediaan_compare_excel_all_bulan_nama_id()
{
	return array(
		1 => 'Januari',
		2 => 'Februari',
		3 => 'Maret',
		4 => 'April',
		5 => 'Mei',
		6 => 'Juni',
		7 => 'Juli',
		8 => 'Agustus',
		9 => 'September',
		10 => 'Oktober',
		11 => 'November',
		12 => 'Desember',
	);
}

function persediaan_compare_excel_all_sheet_name_from_bulan($bulan)
{
	$bulan = trim((string) $bulan);
	if (!preg_match('/^(\d{4})-(\d{2})$/', $bulan, $m)) {
		return 'Sheet';
	}

	$tahun = (int) $m[1];
	$bulan_num = (int) $m[2];
	$nama_bulan = persediaan_compare_excel_all_bulan_nama_id();
	$label_bulan = isset($nama_bulan[$bulan_num]) ? $nama_bulan[$bulan_num] : ('Bulan ' . $bulan_num);

	return excel_sheet_safe_name($label_bulan . ' ' . $tahun);
}

function persediaan_compare_export_excel_all_output($CI, $bulan, $table)
{
	@set_time_limit(600);
	@ini_set('memory_limit', '512M');

	$CI->load->helper(array('exportexcel', 'persediaan_display'));

	$sheet_name = persediaan_compare_excel_all_sheet_name_from_bulan($bulan);

	$result = persediaan_compare_excel_all_build_rows($CI, $bulan, $table);
	if (empty($result['ok'])) {
		xlsAddSheet($sheet_name);
		xlsWriteLabel(0, 0, isset($result['message']) ? $result['message'] : 'Export Excel ALL gagal.');
		xlsMultiEOF();
		return;
	}

	$groups = persediaan_compare_excel_all_group_definitions();
	$headers = persediaan_compare_excel_all_headers();
	$groupTitleRow = 2;
	$headerRow = 3;
	$dataStartRow = 4;

	xlsAddSheet($sheet_name);
	xlsWriteLabelBold14(0, 0, 'Compare Excel ALL — ' . $result['table'] . ' — Bulan ' . $result['bulan_label']);
	xlsWriteLabel(1, 0, 'Dicetak: ' . date('d/m/Y H:i:s')
		. ' | Manual + Persediaan + Pembelian + Penjualan + Produksi + Pecah Satuan');
	persediaan_rekonsiliasi_tx_write_group_titles($groupTitleRow, $groups);
	persediaan_rekonsiliasi_tx_write_headers_grouped($headerRow, $headers, $groups);

	$row = $dataStartRow;
	foreach ($result['rows'] as $cells) {
		persediaan_rekonsiliasi_tx_write_row_grouped($row, $cells, $groups);
		$row++;
	}

	$lastRow = ($row > $dataStartRow) ? ($row - 1) : $headerRow;
	persediaan_rekonsiliasi_tx_finalize_group_borders($groupTitleRow, $lastRow, $groups);

	xlsMultiEOF();
}

/**
 * Rekonsiliasi transaksi persediaan vs pembelian/penjualan/produksi/pecah satuan (Excel).
 */
function persediaan_rekonsiliasi_tx_key($nama, $satuan, $hpp)
{
	return persediaan_recalculate_nama_satuan_hpp_key($nama, $satuan, $hpp);
}

function persediaan_rekonsiliasi_tx_push(&$bucket, $key, $row)
{
	if ($key === '') {
		return;
	}
	if (!isset($bucket[$key])) {
		$bucket[$key] = array();
	}
	$bucket[$key][] = $row;
}

function persediaan_rekonsiliasi_tx_format_lines($items, $fields)
{
	if (empty($items) || !is_array($items)) {
		return '';
	}
	$lines = array();
	foreach ($items as $it) {
		$parts = array();
		foreach ($fields as $f) {
			$parts[] = isset($it[$f]) ? (string) $it[$f] : '';
		}
		$lines[] = implode(' | ', $parts);
	}
	return implode("\n", $lines);
}

function persediaan_rekonsiliasi_tx_collect_maps($CI, $tgl_awal, $tgl_akhir)
{
	return persediaan_rekonsiliasi_tx_load_transaction_lists($CI, $tgl_awal, $tgl_akhir);
}

/**
 * Muat semua transaksi bulan target (list mentah untuk matching seperti recalculate).
 */
function persediaan_rekonsiliasi_tx_load_transaction_lists($CI, $tgl_awal, $tgl_akhir)
{
	$CI->load->helper('persediaan_display');

	$pembelian = array();
	foreach (array('tbl_pembelian', 'tbl_pembelian_jasa') as $tbl) {
		foreach (persediaan_recalculate_load_pembelian_bulan_rows($CI, $tbl, $tgl_awal, $tgl_akhir) as $r) {
			$pembelian[] = $r;
		}
	}

	$penjualan = array();
	if ($CI->db->table_exists('tbl_penjualan')) {
		$penjualan = $CI->db->query(
			"SELECT *
			FROM `tbl_penjualan`
			WHERE `tgl_jual` IS NOT NULL AND `tgl_jual` <> '0000-00-00'
			AND DATE(`tgl_jual`) >= ? AND DATE(`tgl_jual`) <= ?
			ORDER BY `id` ASC",
			array($tgl_awal, $tgl_akhir)
		)->result();
	}

	$produksi = array();
	if ($CI->db->table_exists('sys_unit_produk_bahan')) {
		$produksi = $CI->db->query(
			"SELECT *
			FROM `sys_unit_produk_bahan`
			WHERE `tgl_transaksi` IS NOT NULL AND `tgl_transaksi` <> '0000-00-00'
			AND DATE(`tgl_transaksi`) >= ? AND DATE(`tgl_transaksi`) <= ?
			ORDER BY `id` ASC",
			array($tgl_awal, $tgl_akhir)
		)->result();
	}

	$pecah_satuan = array();
	if ($CI->db->table_exists('tbl_pembelian_pecah_satuan')) {
		$pecah_satuan = $CI->db->query(
			"SELECT *
			FROM `tbl_pembelian_pecah_satuan`
			WHERE DATE(COALESCE(NULLIF(TRIM(`proses_input`), ''), `tgl_po`)) >= ?
			AND DATE(COALESCE(NULLIF(TRIM(`proses_input`), ''), `tgl_po`)) <= ?
			ORDER BY `id` ASC",
			array($tgl_awal, $tgl_akhir)
		)->result();
	}

	return array(
		'pembelian' => $pembelian,
		'penjualan' => $penjualan,
		'produksi' => $produksi,
		'pecah_satuan' => $pecah_satuan,
	);
}

function persediaan_rekonsiliasi_tx_filter_pembelian_for_pers($pers, $pembelian_list, $map)
{
	$out = array();
	$pers_id = (int) (isset($pers->id) ? $pers->id : 0);
	if ($pers_id <= 0 || empty($pembelian_list)) {
		return $out;
	}

	foreach ($pembelian_list as $r) {
		$pick = persediaan_recalculate_find_persediaan_for_pembelian($r, $map);
		if (!$pick || (int) $pick->id !== $pers_id) {
			continue;
		}
		$out[] = array(
			'nama' => isset($r->uraian) ? $r->uraian : '',
			'satuan' => isset($r->satuan) ? $r->satuan : '',
			'hpp' => isset($r->harga_satuan) ? $r->harga_satuan : '',
			'spop' => isset($r->spop) ? $r->spop : '',
			'jumlah' => isset($r->jumlah) ? $r->jumlah : '',
		);
	}

	return $out;
}

function persediaan_rekonsiliasi_tx_filter_penjualan_for_pers($CI, $pers, $penjualan_list, $map)
{
	$out = array();
	$pers_id = (int) (isset($pers->id) ? $pers->id : 0);
	if ($pers_id <= 0 || empty($penjualan_list)) {
		return $out;
	}

	foreach ($penjualan_list as $r) {
		$match = persediaan_recalculate_match_penjualan_ke_persediaan($CI, $r, $map);
		if (!$match || empty($match['row']) || (int) $match['row']->id !== $pers_id) {
			continue;
		}
		$out[] = array(
			'nama' => isset($r->nama_barang) ? $r->nama_barang : '',
			'satuan' => isset($r->satuan) ? $r->satuan : '',
			'hpp' => isset($r->harga_satuan) ? $r->harga_satuan : '',
			'jumlah' => isset($r->jumlah) ? $r->jumlah : '',
		);
	}

	return $out;
}

function persediaan_rekonsiliasi_tx_filter_produksi_for_pers($pers, $produksi_list, $map)
{
	$CI = function_exists('get_instance') ? get_instance() : null;
	$out = array();
	$pers_id = (int) (isset($pers->id) ? $pers->id : 0);
	if ($pers_id <= 0 || empty($produksi_list)) {
		return $out;
	}

	foreach ($produksi_list as $r) {
		$pick = $CI
			? persediaan_generate_recalculate_find_persediaan_for_produksi_bahan($CI, $r, $map)
			: null;
		if (!$pick || (int) $pick->id !== $pers_id) {
			continue;
		}
		$out[] = array(
			'nama' => isset($r->nama_barang_bahan) ? $r->nama_barang_bahan : '',
			'satuan' => isset($r->satuan_bahan) ? $r->satuan_bahan : '',
			'hpp' => isset($r->harga_satuan_bahan) ? $r->harga_satuan_bahan : '',
			'jumlah' => isset($r->jumlah_bahan) ? $r->jumlah_bahan : '',
		);
	}

	return $out;
}

function persediaan_rekonsiliasi_tx_filter_pecah_sumber_for_pers($pers, $pecah_list, $map)
{
	$out = array();
	$pers_id = (int) (isset($pers->id) ? $pers->id : 0);
	if ($pers_id <= 0 || empty($pecah_list)) {
		return $out;
	}

	foreach ($pecah_list as $r) {
		$pick = persediaan_generate_recalculate_find_persediaan_for_pecah_source($r, $map);
		if (!$pick || (int) $pick->id !== $pers_id) {
			continue;
		}
		$out[] = array(
			'nama' => isset($r->uraian) ? $r->uraian : '',
			'satuan' => isset($r->satuan) ? $r->satuan : '',
			'hpp' => isset($r->harga_satuan) ? $r->harga_satuan : '',
			'jumlah' => isset($r->jumlah) ? $r->jumlah : '',
		);
	}

	return $out;
}

function persediaan_rekonsiliasi_tx_filter_pecah_target_for_pers($pers, $pecah_list, $map)
{
	$out = array();
	$pers_id = (int) (isset($pers->id) ? $pers->id : 0);
	if ($pers_id <= 0 || empty($pecah_list)) {
		return $out;
	}

	foreach ($pecah_list as $r) {
		$pick = persediaan_generate_recalculate_find_persediaan_for_pecah_target($r, $map);
		if (!$pick || (int) $pick->id !== $pers_id) {
			continue;
		}
		$out[] = array(
			'nama' => isset($r->nama_barang_baru) ? $r->nama_barang_baru : '',
			'satuan' => isset($r->satuan_barang_baru) ? $r->satuan_barang_baru : '',
			'hpp' => isset($r->harga_satuan_barang_baru) ? $r->harga_satuan_barang_baru : '',
			'jumlah' => isset($r->jumlah_barang_baru) ? $r->jumlah_barang_baru : '',
		);
	}

	return $out;
}

function persediaan_rekonsiliasi_tx_build_cells_for_pers($CI, $pers, $lists, $map)
{
	$pemb = persediaan_rekonsiliasi_tx_filter_pembelian_for_pers($pers, $lists['pembelian'], $map);
	$penj = persediaan_rekonsiliasi_tx_filter_penjualan_for_pers($CI, $pers, $lists['penjualan'], $map);
	$prod = persediaan_rekonsiliasi_tx_filter_produksi_for_pers($pers, $lists['produksi'], $map);
	$pec_s = persediaan_rekonsiliasi_tx_filter_pecah_sumber_for_pers($pers, $lists['pecah_satuan'], $map);
	$pec_t = persediaan_rekonsiliasi_tx_filter_pecah_target_for_pers($pers, $lists['pecah_satuan'], $map);

	return array(
		isset($pers->namabarang) ? $pers->namabarang : '',
		isset($pers->satuan) ? $pers->satuan : '',
		isset($pers->hpp) ? $pers->hpp : '',
		isset($pers->sa) ? $pers->sa : '',
		isset($pers->beli) ? $pers->beli : '',
		isset($pers->total_10) ? $pers->total_10 : '',
		'', '',
		persediaan_rekonsiliasi_tx_format_lines($pemb, array('nama')),
		persediaan_rekonsiliasi_tx_format_lines($pemb, array('satuan')),
		persediaan_rekonsiliasi_tx_format_lines($pemb, array('hpp')),
		persediaan_rekonsiliasi_tx_format_lines($pemb, array('jumlah')),
		'', '',
		persediaan_rekonsiliasi_tx_format_lines($penj, array('nama')),
		persediaan_rekonsiliasi_tx_format_lines($penj, array('satuan')),
		persediaan_rekonsiliasi_tx_format_lines($penj, array('hpp')),
		persediaan_rekonsiliasi_tx_format_lines($penj, array('jumlah')),
		'', '',
		persediaan_rekonsiliasi_tx_format_lines($prod, array('nama')),
		persediaan_rekonsiliasi_tx_format_lines($prod, array('satuan')),
		persediaan_rekonsiliasi_tx_format_lines($prod, array('hpp')),
		persediaan_rekonsiliasi_tx_format_lines($prod, array('jumlah')),
		'', '',
		persediaan_rekonsiliasi_tx_format_lines($pec_s, array('nama')),
		persediaan_rekonsiliasi_tx_format_lines($pec_s, array('satuan')),
		persediaan_rekonsiliasi_tx_format_lines($pec_s, array('hpp')),
		persediaan_rekonsiliasi_tx_format_lines($pec_s, array('jumlah')),
		persediaan_rekonsiliasi_tx_format_lines($pec_t, array('nama')),
		persediaan_rekonsiliasi_tx_format_lines($pec_t, array('satuan')),
		persediaan_rekonsiliasi_tx_format_lines($pec_t, array('hpp')),
		persediaan_rekonsiliasi_tx_format_lines($pec_t, array('jumlah')),
	);
}

function persediaan_rekonsiliasi_tx_build_unmatched_rows($CI, $lists, $map)
{
	$rows = array();

	foreach ($lists['penjualan'] as $r) {
		$match = persediaan_recalculate_match_penjualan_ke_persediaan($CI, $r, $map);
		if ($match && !empty($match['row'])) {
			continue;
		}
		$rows[] = array(
			'[Penjualan tanpa match persediaan]',
			'',
			'',
			'',
			'',
			'',
			'', '',
			'',
			'',
			'',
			'',
			'', '',
			isset($r->nama_barang) ? $r->nama_barang : '',
			isset($r->satuan) ? $r->satuan : '',
			isset($r->harga_satuan) ? $r->harga_satuan : '',
			isset($r->jumlah) ? $r->jumlah : '',
			'', '',
			'',
			'',
			'',
			'',
			'', '',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
		);
	}

	return $rows;
}

/** Style Excel: border tipis (index 3) */
function persediaan_rekonsiliasi_tx_excel_style_border()
{
	return 3;
}

/** Style Excel: judul kelompok (index 4) */
function persediaan_rekonsiliasi_tx_excel_style_group_title()
{
	return 4;
}

/** Style Excel: header kolom dalam kelompok (index 5) */
function persediaan_rekonsiliasi_tx_excel_style_group_header()
{
	return 5;
}

function persediaan_rekonsiliasi_tx_group_definitions()
{
	return array(
		array('title' => 'KELOMPOK PERSEDIAAN', 'col_start' => 0, 'col_end' => 5),
		array('title' => 'KELOMPOK PEMBELIAN', 'col_start' => 8, 'col_end' => 11),
		array('title' => 'KELOMPOK PENJUALAN', 'col_start' => 14, 'col_end' => 17),
		array('title' => 'KELOMPOK INPUT PRODUKSI', 'col_start' => 20, 'col_end' => 23),
		array('title' => 'KELOMPOK PECAH SATUAN', 'col_start' => 26, 'col_end' => 33),
	);
}

function persediaan_rekonsiliasi_tx_col_in_groups($col, $groups)
{
	foreach ($groups as $g) {
		if ($col >= $g['col_start'] && $col <= $g['col_end']) {
			return true;
		}
	}
	return false;
}

function persediaan_rekonsiliasi_tx_write_group_titles($rowNum, $groups)
{
	$titleStyle = persediaan_rekonsiliasi_tx_excel_style_group_title();
	foreach ($groups as $g) {
		xlsWriteCellStyle($rowNum, $g['col_start'], $g['title'], $titleStyle);
		xlsAddMerge($rowNum, $g['col_start'], $rowNum, $g['col_end']);
		for ($c = $g['col_start'] + 1; $c <= $g['col_end']; $c++) {
			xlsWriteCellStyle($rowNum, $c, '', $titleStyle);
		}
	}
}

function persediaan_rekonsiliasi_tx_write_headers_grouped($rowNum, $headers, $groups)
{
	$headerStyle = persediaan_rekonsiliasi_tx_excel_style_group_header();
	$borderStyle = persediaan_rekonsiliasi_tx_excel_style_border();
	foreach ($headers as $col => $label) {
		if (persediaan_rekonsiliasi_tx_col_in_groups($col, $groups)) {
			$style = ($label !== '') ? $headerStyle : $borderStyle;
			xlsWriteCellStyle($rowNum, $col, $label, $style);
		} else {
			xlsWriteLabel($rowNum, $col, $label);
		}
	}
}

function persediaan_rekonsiliasi_tx_write_row_grouped($rowNum, $cells, $groups)
{
	$borderStyle = persediaan_rekonsiliasi_tx_excel_style_border();
	$col = 0;
	foreach ($cells as $cell) {
		if (persediaan_rekonsiliasi_tx_col_in_groups($col, $groups)) {
			xlsWriteCellStyle($rowNum, $col, $cell, $borderStyle);
		} else {
			xlsWriteLabel($rowNum, $col, $cell);
		}
		$col++;
	}
}

function persediaan_rekonsiliasi_tx_finalize_group_borders($rowStart, $rowEnd, $groups)
{
	$borderStyle = persediaan_rekonsiliasi_tx_excel_style_border();
	$titleStyle = persediaan_rekonsiliasi_tx_excel_style_group_title();
	$headerStyle = persediaan_rekonsiliasi_tx_excel_style_group_header();

	foreach ($groups as $g) {
		for ($r = $rowStart; $r <= $rowEnd; $r++) {
			for ($c = $g['col_start']; $c <= $g['col_end']; $c++) {
				if ($r === $rowStart) {
					xlsEnsureCellStyle($r, $c, $titleStyle);
				} elseif ($r === $rowStart + 1) {
					xlsEnsureCellStyle($r, $c, $headerStyle);
				} else {
					xlsEnsureCellStyle($r, $c, $borderStyle);
				}
			}
		}
	}
}

function persediaan_export_rekonsiliasi_transaksi_excel_output($CI, $bulan)
{
	$CI->load->helper(array('exportexcel', 'persediaan_display'));

	if (!preg_match('/^\d{4}-\d{2}$/', trim((string) $bulan))) {
		xlsBOF();
		xlsWriteLabel(0, 0, 'Format bulan tidak valid (YYYY-MM).');
		xlsEOF();
		return;
	}

	$ts = strtotime($bulan . '-01');
	if ($ts === false) {
		xlsBOF();
		xlsWriteLabel(0, 0, 'Bulan tidak valid.');
		xlsEOF();
		return;
	}

	$tanggal_beli = date('Y-m-01', $ts);
	$tgl_awal = $tanggal_beli;
	$tgl_akhir = date('Y-m-t', $ts);
	$bulan_label = date('m/Y', $ts);

	$lists = persediaan_rekonsiliasi_tx_load_transaction_lists($CI, $tgl_awal, $tgl_akhir);
	$map = persediaan_recalculate_build_map_persediaan_bulan($CI, $tanggal_beli);
	$pers_rows = $CI->db->query(
		"SELECT `id`, `uuid_barang`, `namabarang`, `satuan`, `hpp`, `spop`, `sa`, `beli`, `total_10`
		FROM `persediaan`
		WHERE `tanggal_beli` = ?
		ORDER BY `namabarang` ASC, `id` ASC",
		array($tanggal_beli)
	)->result();

	$headers = array(
		'Namabarang', 'Satuan', 'HPP', 'SA', 'Beli', 'Total_10', '', '',
		'Nama Barang', 'Satuan', 'HPP', 'Jumlah', '', '',
		'Nama Barang', 'Satuan', 'Harga Satuan', 'Jumlah', '', '',
		'Nama Barang', 'Satuan', 'Harga Satuan', 'Jumlah', '', '',
		'Sumber Nama', 'Sumber Satuan', 'Sumber HPP', 'Sumber Jumlah',
		'Target Nama', 'Target Satuan', 'Target HPP', 'Target Jumlah',
	);

	$groups = persediaan_rekonsiliasi_tx_group_definitions();
	$groupTitleRow = 2;
	$headerRow = 3;
	$dataStartRow = 4;

	xlsBOF();
	xlsWriteLabelBold14(0, 0, 'Rekonsiliasi TRANSAKSI Persediaan — Bulan ' . $bulan_label);
	xlsWriteLabel(1, 0, 'Dicetak: ' . date('d/m/Y H:i:s') . ' | Sumber: data persediaan + pembelian + penjualan + produksi + pecah satuan');
	persediaan_rekonsiliasi_tx_write_group_titles($groupTitleRow, $groups);
	persediaan_rekonsiliasi_tx_write_headers_grouped($headerRow, $headers, $groups);

	$row = $dataStartRow;
	foreach ($pers_rows as $p) {
		$cells = persediaan_rekonsiliasi_tx_build_cells_for_pers($CI, $p, $lists, $map);
		persediaan_rekonsiliasi_tx_write_row_grouped($row, $cells, $groups);
		$row++;
	}

	foreach (persediaan_rekonsiliasi_tx_build_unmatched_rows($CI, $lists, $map) as $cells) {
		persediaan_rekonsiliasi_tx_write_row_grouped($row, $cells, $groups);
		$row++;
	}

	$lastRow = ($row > $dataStartRow) ? ($row - 1) : $headerRow;
	persediaan_rekonsiliasi_tx_finalize_group_borders($groupTitleRow, $lastRow, $groups);

	xlsEOF();
}

/**
 * Definisi 6 tabel ringkasan hasil generate (datatable + excel).
 */
function persediaan_gen_recalc_rekap_persediaan_meta($CI)
{
	$CI->load->helper('persediaan_display');

	$headers = array('No', 'Nama Barang', 'Satuan', 'HPP', 'SA', 'SPOP', 'Beli');
	$keys = array('no', 'namabarang', 'satuan', 'hpp', 'sa', 'spop', 'beli');
	$sum_keys = array('sa', 'beli');

	if ($CI->db->field_exists('tuj', 'persediaan')) {
		$headers[] = 'Tuj';
		$keys[] = 'tuj';
		$sum_keys[] = 'tuj';
	}

	foreach (persediaan_list_fields_tgl_keluar_sampai_total_10($CI) as $field) {
		if ($field === 'tgl_keluar') {
			continue;
		}
		$headers[] = persediaan_field_label($field);
		$keys[] = $field;
		$sum_keys[] = $field;
		if (persediaan_field_has_nominal_column($field)) {
			$headers[] = persediaan_field_nominal_header_label($field);
			$keys[] = $field . '_nominal';
			$sum_keys[] = $field . '_nominal';
		}
	}

	$headers[] = 'Nilai Persediaan';
	$keys[] = 'nilai_persediaan';
	$sum_keys[] = 'nilai_persediaan';

	return array(
		'headers' => $headers,
		'keys' => $keys,
		'sum_keys' => $sum_keys,
	);
}

function persediaan_gen_recalc_rekap_persediaan_row($CI, $row, $no)
{
	$CI->load->helper('persediaan_display');
	$meta = persediaan_gen_recalc_rekap_persediaan_meta($CI);

	$out = array('no' => (int) $no);
	foreach ($meta['keys'] as $key) {
		if ($key === 'no') {
			continue;
		}
		if ($key === 'nilai_persediaan') {
			$out[$key] = isset($row->nilai_persediaan) ? $row->nilai_persediaan : '';
			continue;
		}
		if (substr($key, -8) === '_nominal') {
			$base = substr($key, 0, -8);
			$out[$key] = persediaan_tampil_kolom_nominal_row($row, $base);
			continue;
		}
		$out[$key] = persediaan_row_get($row, $key);
	}

	return $out;
}

function persediaan_gen_recalc_rekap_persediaan_totals($CI, $rows)
{
	$CI->load->helper('persediaan_display');
	$meta = persediaan_gen_recalc_rekap_persediaan_meta($CI);
	$totals = array();

	foreach ($meta['sum_keys'] as $key) {
		$totals[$key] = 0;
	}

	foreach ($rows as $row) {
		if (!is_array($row)) {
			continue;
		}
		foreach ($meta['sum_keys'] as $key) {
			if (!isset($row[$key])) {
				continue;
			}
			if (substr($key, -8) === '_nominal') {
				$totals[$key] += persediaan_parse_angka($row[$key]);
				continue;
			}
			$totals[$key] += persediaan_parse_angka($row[$key]);
		}
	}

	return $totals;
}

/**
 * Pastikan payload rekap punya baris totals (untuk history lama tanpa totals_json).
 */
function persediaan_gen_recalc_summary_rekap_meta_normalize($CI, &$summary_tables)
{
	if (!is_array($summary_tables)) {
		return;
	}

	$live = persediaan_gen_recalc_rekap_persediaan_meta($CI);
	$meta = isset($summary_tables['rekap_meta']) && is_array($summary_tables['rekap_meta'])
		? $summary_tables['rekap_meta']
		: array();

	$keys = isset($meta['persediaan_keys']) && is_array($meta['persediaan_keys'])
		? $meta['persediaan_keys']
		: array();
	$headers = isset($meta['persediaan_headers']) && is_array($meta['persediaan_headers'])
		? $meta['persediaan_headers']
		: array();
	$sum_keys = isset($meta['persediaan_sum_keys']) && is_array($meta['persediaan_sum_keys'])
		? $meta['persediaan_sum_keys']
		: array();

	$need_refresh = empty($keys)
		|| empty($headers)
		|| count($keys) !== count($headers)
		|| count($keys) !== count($live['keys']);

	if ($need_refresh) {
		$summary_tables['rekap_meta'] = array(
			'persediaan_headers' => $live['headers'],
			'persediaan_keys' => $live['keys'],
			'persediaan_sum_keys' => $live['sum_keys'],
		);
		return;
	}

	$summary_tables['rekap_meta'] = array(
		'persediaan_headers' => $live['headers'],
		'persediaan_keys' => $keys,
		'persediaan_sum_keys' => !empty($sum_keys) ? $sum_keys : $live['sum_keys'],
	);
}

function persediaan_gen_recalc_summary_tables_ensure_totals($CI, &$summary_tables)
{
	if (!is_array($summary_tables)) {
		return;
	}

	$pers_jenis = array(
		'persediaan_bulan_lalu',
		'persediaan_total_target',
		'persediaan_sumber_tidak_masuk',
	);
	foreach ($pers_jenis as $jenis) {
		$rows = isset($summary_tables[$jenis]) && is_array($summary_tables[$jenis])
			? $summary_tables[$jenis]
			: array();
		$totals_key = $jenis . '_totals';
		if (empty($summary_tables[$totals_key]) || !is_array($summary_tables[$totals_key])) {
			$summary_tables[$totals_key] = persediaan_gen_recalc_rekap_persediaan_totals($CI, $rows);
		}
	}

	$pembelian_map = array(
		'pembelian_update_beli' => 'persediaan_gen_recalc_rekap_pembelian_totals',
		'pembelian_insert_baru' => 'persediaan_gen_recalc_rekap_pembelian_totals',
	);
	foreach ($pembelian_map as $jenis => $fn) {
		$rows = isset($summary_tables[$jenis]) && is_array($summary_tables[$jenis])
			? $summary_tables[$jenis]
			: array();
		$totals_key = $jenis . '_totals';
		if (empty($summary_tables[$totals_key]) || !is_array($summary_tables[$totals_key])) {
			$summary_tables[$totals_key] = call_user_func($fn, $rows);
		}
	}

	if (empty($summary_tables['pembelian_semua_totals']) || !is_array($summary_tables['pembelian_semua_totals'])) {
		$totals = array('jumlah' => 0);
		$rows = isset($summary_tables['pembelian_semua']) && is_array($summary_tables['pembelian_semua'])
			? $summary_tables['pembelian_semua']
			: array();
		foreach ($rows as $row) {
			if (!is_array($row) || (isset($row['row_type']) && $row['row_type'] === 'subtotal')) {
				continue;
			}
			$totals['jumlah'] += persediaan_parse_angka(isset($row['jumlah']) ? $row['jumlah'] : 0);
		}
		$summary_tables['pembelian_semua_totals'] = $totals;
	}

	foreach (array('pembelian_spop_multi', 'pembelian_spop_single') as $jenis) {
		$totals_key = $jenis . '_totals';
		if (!empty($summary_tables[$totals_key]) && is_array($summary_tables[$totals_key])) {
			continue;
		}
		$rows = isset($summary_tables[$jenis]) && is_array($summary_tables[$jenis])
			? $summary_tables[$jenis]
			: array();
		$totals = array('jumlah' => 0);
		foreach ($rows as $row) {
			if (!is_array($row) || (isset($row['row_type']) && $row['row_type'] === 'subtotal')) {
				continue;
			}
			$totals['jumlah'] += persediaan_parse_angka(isset($row['jumlah']) ? $row['jumlah'] : 0);
		}
		$summary_tables[$totals_key] = $totals;
	}
}

/**
 * Peta status pembelian → persediaan dari hasil batch generate/recalculate.
 */
function persediaan_gen_recalc_pembelian_status_map_from_batch($pembelian, $pembelian_update, $pembelian_baru)
{
	$map = array();

	foreach (is_array($pembelian_update) ? $pembelian_update : array() as $it) {
		if (!is_array($it)) {
			continue;
		}
		$id = (int) (isset($it['id_pembelian']) ? $it['id_pembelian'] : 0);
		if ($id > 0) {
			$map[$id] = 'Sudah update ke persediaan';
		}
	}

	foreach (is_array($pembelian_baru) ? $pembelian_baru : array() as $it) {
		if (!is_array($it)) {
			continue;
		}
		$id = (int) (isset($it['id_pembelian']) ? $it['id_pembelian'] : 0);
		if ($id > 0) {
			$map[$id] = 'Sudah insert baru ke persediaan';
		}
	}

	foreach (is_array($pembelian) ? $pembelian : array() as $it) {
		if (!is_array($it)) {
			continue;
		}
		$id = (int) (isset($it['id_pembelian']) ? $it['id_pembelian'] : 0);
		if ($id <= 0 || isset($map[$id])) {
			continue;
		}
		$aksi = strtoupper(trim((string) (isset($it['aksi']) ? $it['aksi'] : '')));
		if ($aksi === 'UPDATE_BELI') {
			$map[$id] = 'Sudah update ke persediaan';
		} elseif ($aksi === 'INSERT_BARU') {
			$map[$id] = 'Sudah insert baru ke persediaan';
		} elseif ($aksi === 'GAGAL') {
			$map[$id] = 'Gagal — belum ke persediaan';
		} elseif ($aksi === 'TIDAK_COCOK') {
			$map[$id] = 'Tidak cocok — belum ke persediaan';
		} elseif ($aksi === 'SKIP') {
			$map[$id] = 'Lewati';
		}
	}

	return $map;
}

/**
 * Peta hasil proses penjualan per id_penjualan.
 */
function persediaan_gen_recalc_penjualan_proses_map_from_batch($penjualan, $penjualan_update)
{
	$map = array();

	foreach (is_array($penjualan_update) ? $penjualan_update : array() as $it) {
		if (!is_array($it)) {
			continue;
		}
		$id = (int) (isset($it['id_penjualan']) ? $it['id_penjualan'] : 0);
		if ($id > 0) {
			$map[$id] = array(
				'aksi' => 'UPDATE_PENJUALAN',
				'status' => 'Sudah update ke persediaan',
				'detail' => $it,
			);
		}
	}

	foreach (is_array($penjualan) ? $penjualan : array() as $it) {
		if (!is_array($it)) {
			continue;
		}
		$id = (int) (isset($it['id_penjualan']) ? $it['id_penjualan'] : 0);
		if ($id <= 0) {
			continue;
		}
		$aksi = strtoupper(trim((string) (isset($it['aksi']) ? $it['aksi'] : '')));
		if ($aksi === 'UPDATE_PENJUALAN') {
			$map[$id] = array(
				'aksi' => $aksi,
				'status' => 'Sudah update ke persediaan',
				'detail' => $it,
			);
		} elseif (in_array($aksi, array('TIDAK_COCOK', 'GAGAL'), true) && !isset($map[$id])) {
			$map[$id] = array(
				'aksi' => $aksi,
				'status' => 'Tidak bisa update ke persediaan',
				'detail' => $it,
			);
		} elseif ($aksi === 'SKIP' && !isset($map[$id])) {
			$map[$id] = array(
				'aksi' => $aksi,
				'status' => 'Lewati',
				'detail' => $it,
			);
		}
	}

	return $map;
}

function persediaan_gen_recalc_ctx_from_bulan($bulan)
{
	if (!preg_match('/^\d{4}-\d{2}$/', trim((string) $bulan))) {
		return null;
	}
	$ts = strtotime($bulan . '-01');
	if ($ts === false) {
		return null;
	}
	$tanggal_beli_target = date('Y-m-01', $ts);
	return array(
		'tanggal_beli' => $tanggal_beli_target,
		'tanggal_beli_target' => $tanggal_beli_target,
		'tgl_awal' => $tanggal_beli_target,
		'tgl_akhir' => date('Y-m-t', $ts),
	);
}

function persediaan_gen_recalc_rekap_pembelian_db_row($row, $no, $extra = array())
{
	$out = array(
		'no' => (int) $no,
		'status_persediaan' => '',
		'id_pembelian' => isset($row->id) ? (int) $row->id : '',
		'uraian' => isset($row->uraian) ? $row->uraian : '',
		'satuan' => isset($row->satuan) ? $row->satuan : '',
		'harga_satuan' => isset($row->harga_satuan) ? $row->harga_satuan : '',
		'jumlah' => isset($row->jumlah) ? $row->jumlah : '',
		'spop' => isset($row->spop) ? $row->spop : '',
		'tgl_po' => isset($row->tgl_po) ? $row->tgl_po : '',
		'row_type' => 'detail',
		'record_grup' => '',
		'keterangan_baris' => '',
	);
	if (is_array($extra)) {
		$out = array_merge($out, $extra);
	}

	return $out;
}

/**
 * Muat baris tbl_pembelian bulan target lengkap dengan id.
 */
function persediaan_gen_recalc_load_pembelian_bulan_full($CI, $tgl_awal, $tgl_akhir)
{
	if (!$CI->db->table_exists('tbl_pembelian')) {
		return array();
	}

	$spop_sql = $CI->db->field_exists('spop', 'tbl_pembelian')
		? 'TRIM(COALESCE(`spop`, \'\')) AS `spop`'
		: "'' AS `spop`";

	return $CI->db->query(
		"SELECT `id`, `uraian`, `satuan`, `harga_satuan`, `jumlah`, `tgl_po`, {$spop_sql}
		FROM `tbl_pembelian`
		WHERE `tgl_po` IS NOT NULL AND `tgl_po` <> '0000-00-00'
		AND DATE(`tgl_po`) >= ? AND DATE(`tgl_po`) <= ?
		ORDER BY `spop` ASC, `uraian` ASC, `id` ASC",
		array($tgl_awal, $tgl_akhir)
	)->result();
}

/**
 * Muat seluruh baris tbl_penjualan bulan target.
 */
function persediaan_gen_recalc_load_penjualan_bulan_full($CI, $tgl_awal, $tgl_akhir)
{
	if (!$CI->db->table_exists('tbl_penjualan')) {
		return array();
	}

	$spop_sql = $CI->db->field_exists('spop', 'tbl_penjualan')
		? 'TRIM(COALESCE(`spop`, \'\')) AS `spop`'
		: "'' AS `spop`";
	$uuid_sql = $CI->db->field_exists('uuid_persediaan', 'tbl_penjualan')
		? 'TRIM(COALESCE(`uuid_persediaan`, \'\')) AS `uuid_persediaan`'
		: "'' AS `uuid_persediaan`";

	return $CI->db->query(
		"SELECT `id`, `nama_barang`, `satuan`, `harga_satuan`, `jumlah`, `unit`, `tgl_jual`, {$spop_sql}, {$uuid_sql}
		FROM `tbl_penjualan`
		WHERE `tgl_jual` IS NOT NULL AND `tgl_jual` <> '0000-00-00'
		AND DATE(`tgl_jual`) >= ? AND DATE(`tgl_jual`) <= ?
		ORDER BY `nama_barang` ASC, `id` ASC",
		array($tgl_awal, $tgl_akhir)
	)->result();
}

function persediaan_gen_recalc_rekap_penjualan_db_row($row, $no, $extra = array())
{
	$out = array(
		'no' => (int) $no,
		'status_persediaan' => '',
		'id_penjualan' => isset($row->id) ? (int) $row->id : '',
		'nama_barang' => isset($row->nama_barang) ? $row->nama_barang : '',
		'satuan' => isset($row->satuan) ? $row->satuan : '',
		'harga_satuan' => isset($row->harga_satuan) ? $row->harga_satuan : '',
		'jumlah' => isset($row->jumlah) ? $row->jumlah : '',
		'spop' => isset($row->spop) ? $row->spop : '',
		'uuid_persediaan' => isset($row->uuid_persediaan) ? $row->uuid_persediaan : '',
		'unit' => isset($row->unit) ? $row->unit : '',
		'tgl_jual' => isset($row->tgl_jual) ? $row->tgl_jual : '',
		'id_persediaan' => '',
		'penjualan_lama' => '',
		'penjualan_baru' => '',
		'total_10' => '',
		'aksi_proses' => '',
		'keterangan' => '',
	);
	if (is_array($extra)) {
		$out = array_merge($out, $extra);
	}

	return $out;
}

/**
 * Normalisasi kunci uuid_persediaan untuk perbandingan.
 */
function persediaan_gen_recalc_normalize_uuid_key($uuid)
{
	return strtolower(trim((string) $uuid));
}

/**
 * Indeks uuid_persediaan yang ada di tbl_pembelian, tbl_pembelian_jasa, dan persediaan.
 */
function persediaan_gen_recalc_build_uuid_persediaan_reference_index($CI, $ctx = array())
{
	$index = array(
		'pembelian' => array(),
		'persediaan' => array(),
	);

	if ($CI->db->table_exists('persediaan') && $CI->db->field_exists('uuid_persediaan', 'persediaan')) {
		foreach ($CI->db->query(
			"SELECT DISTINCT TRIM(COALESCE(`uuid_persediaan`, '')) AS `u`
			FROM `persediaan`
			WHERE TRIM(COALESCE(`uuid_persediaan`, '')) <> ''"
		)->result() as $row) {
			$key = persediaan_gen_recalc_normalize_uuid_key(isset($row->u) ? $row->u : '');
			if ($key !== '') {
				$index['persediaan'][$key] = true;
			}
		}
	}

	foreach (array('tbl_pembelian', 'tbl_pembelian_jasa') as $tbl) {
		if (!$CI->db->table_exists($tbl) || !$CI->db->field_exists('uuid_persediaan', $tbl)) {
			continue;
		}
		foreach ($CI->db->query(
			"SELECT DISTINCT TRIM(COALESCE(`uuid_persediaan`, '')) AS `u`
			FROM `{$tbl}`
			WHERE TRIM(COALESCE(`uuid_persediaan`, '')) <> ''"
		)->result() as $row) {
			$key = persediaan_gen_recalc_normalize_uuid_key(isset($row->u) ? $row->u : '');
			if ($key !== '') {
				$index['pembelian'][$key] = true;
			}
		}
	}

	return $index;
}

/**
 * Apakah uuid_persediaan penjualan punya referensi di pembelian atau persediaan.
 */
function persediaan_gen_recalc_uuid_persediaan_has_reference($uuid, $index)
{
	$key = persediaan_gen_recalc_normalize_uuid_key($uuid);
	if ($key === '') {
		return false;
	}
	if (!is_array($index)) {
		return false;
	}

	return !empty($index['pembelian'][$key]) || !empty($index['persediaan'][$key]);
}

/**
 * tbl_penjualan bulan target: uuid_persediaan tidak ada di tbl_pembelian maupun persediaan.
 */
function persediaan_gen_recalc_build_penjualan_uuid_orphan_table($CI, $ctx)
{
	$CI->load->helper('persediaan_display');

	$tgl_awal = isset($ctx['tgl_awal']) ? $ctx['tgl_awal'] : '';
	$tgl_akhir = isset($ctx['tgl_akhir']) ? $ctx['tgl_akhir'] : '';
	$index = persediaan_gen_recalc_build_uuid_persediaan_reference_index($CI, $ctx);

	$rows = array();
	$totals = array('jumlah' => 0);
	$no = 0;

	foreach (persediaan_gen_recalc_load_penjualan_bulan_full($CI, $tgl_awal, $tgl_akhir) as $row) {
		$uuid = isset($row->uuid_persediaan) ? trim((string) $row->uuid_persediaan) : '';
		$uuid_key = persediaan_gen_recalc_normalize_uuid_key($uuid);
		$in_pembelian = ($uuid_key !== '' && !empty($index['pembelian'][$uuid_key]));
		$in_persediaan = ($uuid_key !== '' && !empty($index['persediaan'][$uuid_key]));

		if ($in_pembelian || $in_persediaan) {
			continue;
		}

		if ($uuid_key === '') {
			$status_ref = 'uuid_persediaan kosong';
			$keterangan = 'uuid_persediaan kosong — tidak ditemukan referensi di tbl_pembelian/tbl_pembelian_jasa maupun tabel persediaan';
		} else {
			$status_ref = 'UUID tidak ada referensi';
			$keterangan = 'uuid_persediaan=' . $uuid
				. ' tidak ada di tbl_pembelian/tbl_pembelian_jasa dan tidak ada di tabel persediaan';
		}

		$packed = persediaan_gen_recalc_rekap_penjualan_db_row($row, ++$no, array(
			'status_persediaan' => $status_ref,
			'status_referensi' => $status_ref,
			'ada_di_pembelian' => 'Tidak',
			'ada_di_persediaan' => 'Tidak',
			'keterangan' => $keterangan,
		));
		$rows[] = $packed;
		$totals['jumlah'] += persediaan_parse_angka(isset($packed['jumlah']) ? $packed['jumlah'] : 0);
	}

	return array(
		'rows' => $rows,
		'totals' => $totals,
		'count' => count($rows),
	);
}

/**
 * Tabel penjualan berhasil vs gagal update ke persediaan (seluruh tbl_penjualan bulan).
 */
function persediaan_gen_recalc_build_penjualan_result_tables($CI, $ctx, $penjualan, $penjualan_update)
{
	$CI->load->helper('persediaan_display');

	$tgl_awal = isset($ctx['tgl_awal']) ? $ctx['tgl_awal'] : '';
	$tgl_akhir = isset($ctx['tgl_akhir']) ? $ctx['tgl_akhir'] : '';
	$proses_map = persediaan_gen_recalc_penjualan_proses_map_from_batch($penjualan, $penjualan_update);

	$berhasil = array();
	$gagal = array();
	$no_ok = 0;
	$no_fail = 0;
	$berhasil_totals = array('jumlah' => 0);
	$gagal_totals = array('jumlah' => 0);

	foreach (persediaan_gen_recalc_load_penjualan_bulan_full($CI, $tgl_awal, $tgl_akhir) as $row) {
		$id = isset($row->id) ? (int) $row->id : 0;
		$extra = array();
		if (isset($proses_map[$id])) {
			$pm = $proses_map[$id];
			$extra['status_persediaan'] = isset($pm['status']) ? $pm['status'] : '';
			$extra['aksi_proses'] = isset($pm['aksi']) ? $pm['aksi'] : '';
			if (!empty($pm['detail']) && is_array($pm['detail'])) {
				$d = $pm['detail'];
				$extra['id_persediaan'] = isset($d['id_persediaan']) ? $d['id_persediaan'] : '';
				$extra['penjualan_lama'] = isset($d['penjualan_lama']) ? $d['penjualan_lama'] : '';
				$extra['penjualan_baru'] = isset($d['penjualan_baru']) ? $d['penjualan_baru'] : '';
				$extra['total_10'] = isset($d['total_10']) ? $d['total_10'] : '';
				$extra['keterangan'] = isset($d['keterangan']) ? $d['keterangan'] : '';
			}
		} else {
			$extra['status_persediaan'] = 'Belum diproses';
			$extra['aksi_proses'] = '';
			$extra['keterangan'] = 'Belum ada hasil proses generate/recalculate untuk record ini';
		}

		$aksi = isset($extra['aksi_proses']) ? strtoupper(trim((string) $extra['aksi_proses'])) : '';
		$packed = persediaan_gen_recalc_rekap_penjualan_db_row($row, 0, $extra);

		if ($aksi === 'UPDATE_PENJUALAN') {
			$packed['no'] = ++$no_ok;
			$berhasil[] = $packed;
			$berhasil_totals['jumlah'] += persediaan_parse_angka(isset($packed['jumlah']) ? $packed['jumlah'] : 0);
		} elseif (in_array($aksi, array('TIDAK_COCOK', 'GAGAL'), true) || $aksi === '' || $aksi === 'SKIP') {
			if ($aksi === 'SKIP') {
				continue;
			}
			$packed['no'] = ++$no_fail;
			$gagal[] = $packed;
			$gagal_totals['jumlah'] += persediaan_parse_angka(isset($packed['jumlah']) ? $packed['jumlah'] : 0);
		}
	}

	return array(
		'berhasil' => $berhasil,
		'gagal' => $gagal,
		'berhasil_totals' => $berhasil_totals,
		'gagal_totals' => $gagal_totals,
	);
}

/**
 * Tabel tambahan hasil generate: SPOP pembelian + penjualan berhasil/gagal.
 */
function persediaan_gen_recalc_build_result_extra_tables($CI, $ctx, $batch_items)
{
	if (!is_array($ctx) || !is_array($batch_items)) {
		return array(
			'pembelian_spop_multi' => array(),
			'pembelian_spop_single' => array(),
			'penjualan_berhasil' => array(),
			'penjualan_gagal' => array(),
			'penjualan_uuid_orphan' => array(),
		);
	}

	$tgl_awal = isset($ctx['tgl_awal']) ? $ctx['tgl_awal'] : '';
	$tgl_akhir = isset($ctx['tgl_akhir']) ? $ctx['tgl_akhir'] : '';

	$pembelian = isset($batch_items['items_pembelian']) ? $batch_items['items_pembelian'] : array();
	$pembelian_update = isset($batch_items['items_pembelian_update']) ? $batch_items['items_pembelian_update'] : array();
	$pembelian_baru = isset($batch_items['items_pembelian_baru']) ? $batch_items['items_pembelian_baru'] : array();
	$penjualan = isset($batch_items['items_penjualan']) ? $batch_items['items_penjualan'] : array();
	$penjualan_update = isset($batch_items['items_penjualan_update']) ? $batch_items['items_penjualan_update'] : array();

	$status_map = persediaan_gen_recalc_pembelian_status_map_from_batch($pembelian, $pembelian_update, $pembelian_baru);
	$spop = persediaan_gen_recalc_build_pembelian_spop_rekap($CI, $tgl_awal, $tgl_akhir, $status_map);
	$penj = persediaan_gen_recalc_build_penjualan_result_tables($CI, $ctx, $penjualan, $penjualan_update);
	$orphan = persediaan_gen_recalc_build_penjualan_uuid_orphan_table($CI, $ctx);

	return array(
		'pembelian_spop_multi' => isset($spop['multi']) ? $spop['multi'] : array(),
		'pembelian_spop_single' => isset($spop['single']) ? $spop['single'] : array(),
		'pembelian_spop_multi_totals' => isset($spop['multi_totals']) ? $spop['multi_totals'] : array(),
		'pembelian_spop_single_totals' => isset($spop['single_totals']) ? $spop['single_totals'] : array(),
		'pembelian_spop_multi_grup_count' => isset($spop['multi_grup_count']) ? (int) $spop['multi_grup_count'] : 0,
		'penjualan_berhasil' => isset($penj['berhasil']) ? $penj['berhasil'] : array(),
		'penjualan_gagal' => isset($penj['gagal']) ? $penj['gagal'] : array(),
		'penjualan_berhasil_totals' => isset($penj['berhasil_totals']) ? $penj['berhasil_totals'] : array(),
		'penjualan_gagal_totals' => isset($penj['gagal_totals']) ? $penj['gagal_totals'] : array(),
		'penjualan_uuid_orphan' => isset($orphan['rows']) ? $orphan['rows'] : array(),
		'penjualan_uuid_orphan_totals' => isset($orphan['totals']) ? $orphan['totals'] : array(),
		'penjualan_uuid_orphan_count' => isset($orphan['count']) ? (int) $orphan['count'] : 0,
	);
}

/**
 * Rekap pembelian per SPOP: multi-record (dengan baris subtotal) dan single-record.
 */
function persediaan_gen_recalc_build_pembelian_spop_rekap($CI, $tgl_awal, $tgl_akhir, $status_map = array())
{
	$CI->load->helper('persediaan_display');

	$multi = array();
	$single = array();
	$multi_totals = array('jumlah' => 0, 'record_grup' => 0);
	$single_totals = array('jumlah' => 0);
	$multi_grup_count = 0;

	$by_spop = array();
	foreach (persediaan_gen_recalc_load_pembelian_bulan_full($CI, $tgl_awal, $tgl_akhir) as $row) {
		$spop = isset($row->spop) ? $row->spop : '';
		$spop_key = persediaan_recalculate_spop_key_for_sync($spop);
		if ($spop_key === '') {
			continue;
		}

		if (!isset($by_spop[$spop_key])) {
			$by_spop[$spop_key] = array(
				'spop' => $spop,
				'rows' => array(),
			);
		}
		$by_spop[$spop_key]['rows'][] = $row;
	}

	uasort($by_spop, function ($a, $b) {
		return strcasecmp((string) $a['spop'], (string) $b['spop']);
	});

	$no_multi = 0;
	$no_single = 0;
	foreach ($by_spop as $group) {
		$rows = isset($group['rows']) ? $group['rows'] : array();
		$cnt = count($rows);
		if ($cnt <= 0) {
			continue;
		}

		$spop_tampil = isset($group['spop']) ? $group['spop'] : '';

		if ($cnt === 1) {
			$r = $rows[0];
			$id_pem = isset($r->id) ? (int) $r->id : 0;
			$status = ($id_pem > 0 && isset($status_map[$id_pem]))
				? $status_map[$id_pem]
				: 'Belum dimasukkan / update ke persediaan';
			$single[] = persediaan_gen_recalc_rekap_pembelian_db_row($r, ++$no_single, array(
				'status_persediaan' => $status,
				'record_grup' => '1',
				'keterangan_baris' => '1 record per SPOP',
			));
			$single_totals['jumlah'] += persediaan_parse_angka(isset($r->jumlah) ? $r->jumlah : 0);
			continue;
		}

		$multi_grup_count++;
		$sum_jumlah = 0;
		foreach ($rows as $r) {
			$sum_jumlah += persediaan_parse_angka(isset($r->jumlah) ? $r->jumlah : 0);
			$id_pem = isset($r->id) ? (int) $r->id : 0;
			$status = ($id_pem > 0 && isset($status_map[$id_pem]))
				? $status_map[$id_pem]
				: 'Belum dimasukkan / update ke persediaan';
			$multi[] = persediaan_gen_recalc_rekap_pembelian_db_row($r, ++$no_multi, array(
				'status_persediaan' => $status,
				'record_grup' => (string) $cnt,
				'keterangan_baris' => 'Detail record SPOP ' . $spop_tampil,
			));
		}

		$multi[] = array(
			'no' => '',
			'row_type' => 'subtotal',
			'status_persediaan' => '',
			'id_pembelian' => '',
			'uraian' => 'TOTAL SPOP ' . $spop_tampil,
			'satuan' => '',
			'harga_satuan' => '',
			'jumlah' => (string) (int) floor($sum_jumlah),
			'spop' => $spop_tampil,
			'tgl_po' => '',
			'record_grup' => (string) $cnt,
			'keterangan_baris' => 'Subtotal ' . $cnt . ' record — jumlah total',
		);

		$multi_totals['jumlah'] += $sum_jumlah;
		$multi_totals['record_grup'] += $cnt;
	}

	return array(
		'multi' => $multi,
		'single' => $single,
		'multi_totals' => $multi_totals,
		'single_totals' => $single_totals,
		'multi_grup_count' => $multi_grup_count,
	);
}

function persediaan_gen_recalc_rekap_pembelian_item_row($item, $no)
{
	return array(
		'no' => (int) $no,
		'aksi' => isset($item['aksi']) ? $item['aksi'] : '',
		'id_pembelian' => isset($item['id_pembelian']) ? $item['id_pembelian'] : '',
		'id_persediaan' => isset($item['id_persediaan']) ? $item['id_persediaan'] : '',
		'namabarang' => isset($item['namabarang']) ? $item['namabarang'] : '',
		'satuan' => isset($item['satuan']) ? $item['satuan'] : '',
		'hpp' => isset($item['hpp']) ? $item['hpp'] : '',
		'spop' => isset($item['spop']) ? $item['spop'] : '',
		'jumlah_pembelian' => isset($item['jumlah_pembelian']) ? $item['jumlah_pembelian'] : '',
		'record_grup' => isset($item['record_pembelian_spop']) ? $item['record_pembelian_spop'] : '',
		'beli_lama' => isset($item['beli_lama']) ? $item['beli_lama'] : '',
		'beli_baru' => isset($item['beli_baru']) ? $item['beli_baru'] : '',
		'total_10' => isset($item['total_10']) ? $item['total_10'] : '',
		'keterangan' => isset($item['keterangan']) ? $item['keterangan'] : '',
	);
}

function persediaan_gen_recalc_rekap_pembelian_totals($rows)
{
	if (function_exists('get_instance')) {
		get_instance()->load->helper('persediaan_display');
	}
	$totals = array('jumlah_pembelian' => 0, 'beli_lama' => 0, 'beli_baru' => 0, 'total_10' => 0);
	foreach ($rows as $row) {
		if (!is_array($row)) {
			continue;
		}
		foreach (array_keys($totals) as $key) {
			if (isset($row[$key])) {
				$totals[$key] += persediaan_parse_angka($row[$key]);
			}
		}
	}
	return $totals;
}

function persediaan_gen_recalc_build_persediaan_sumber_tidak_masuk($CI, $tanggal_beli_sumber, $tanggal_beli_target)
{
	$copied = array();
	$targets = $CI->db->query(
		"SELECT `uuid_persediaan_lama` FROM `persediaan` WHERE `tanggal_beli` = ?",
		array($tanggal_beli_target)
	)->result();

	foreach ($targets as $t) {
		$uuid = trim((string) (isset($t->uuid_persediaan_lama) ? $t->uuid_persediaan_lama : ''));
		if (preg_match('/^gen_src:(\d+)$/', $uuid, $m)) {
			$copied[(int) $m[1]] = true;
		}
	}

	$tidak_masuk = array();
	$rows = $CI->db->query(
		"SELECT * FROM `persediaan` WHERE `tanggal_beli` = ? ORDER BY `namabarang` ASC, `id` ASC",
		array($tanggal_beli_sumber)
	)->result();

	foreach ($rows as $row) {
		if (empty($copied[(int) $row->id])) {
			$tidak_masuk[] = $row;
		}
	}

	return $tidak_masuk;
}

function persediaan_gen_recalc_summary_jenis_definitions()
{
	return array(
		'persediaan_bulan_lalu' => array(
			'title' => 'Data Persediaan Asli Bulan Sebelumnya',
			'headers' => array(),
			'dynamic_persediaan' => true,
		),
		'persediaan_total_target' => array(
			'title' => 'Data Persediaan Total Bulan Target (Barang & Jasa)',
			'headers' => array(),
			'dynamic_persediaan' => true,
		),
		'pembelian_semua' => array(
			'title' => 'Data Pembelian Masuk ke Persediaan (Semua)',
			'headers' => array('No', 'Uraian', 'Satuan', 'Harga Satuan', 'Jumlah', 'SPOP', 'Tgl PO'),
		),
		'pembelian_update_beli' => array(
			'title' => 'Pembelian — Update Field Beli di Persediaan',
			'headers' => array('No', 'Aksi', 'ID Pembelian', 'ID Persediaan', 'Nama', 'Satuan', 'HPP', 'SPOP', 'Jumlah', 'Record Grup', 'Beli Lama', 'Beli Baru', 'Total 10', 'Keterangan'),
		),
		'pembelian_insert_baru' => array(
			'title' => 'Pembelian — Record Persediaan Baru',
			'headers' => array('No', 'Aksi', 'ID Pembelian', 'ID Persediaan', 'Nama', 'Satuan', 'HPP', 'SPOP', 'Jumlah', 'Record Grup', 'Beli Baru', 'Total 10', 'Keterangan'),
		),
		'persediaan_sumber_tidak_masuk' => array(
			'title' => 'Persediaan Bulan Lalu yang Tidak Masuk ke Bulan Ini',
			'headers' => array(),
			'dynamic_persediaan' => true,
		),
		'pembelian_spop_multi' => array(
			'title' => 'Pembelian — SPOP dengan Lebih dari 1 Record (per grup + subtotal)',
			'headers' => array('No', 'ID Pembelian', 'Uraian', 'Satuan', 'Harga Satuan', 'Jumlah', 'SPOP', 'Tgl PO', 'Jumlah Record SPOP', 'Keterangan'),
		),
		'pembelian_spop_single' => array(
			'title' => 'Pembelian — SPOP dengan 1 Record Saja',
			'headers' => array('No', 'ID Pembelian', 'Uraian', 'Satuan', 'Harga Satuan', 'Jumlah', 'SPOP', 'Tgl PO', 'Jumlah Record SPOP', 'Keterangan'),
		),
	);
}

/**
 * Muat data 6 tabel ringkasan untuk bulan target generate (aman dari error SQL).
 */
function persediaan_gen_recalc_summary_tables_load_safe($CI, $bulan)
{
	try {
		return persediaan_gen_recalc_summary_tables_load($CI, $bulan);
	} catch (Throwable $e) {
		log_message('error', 'persediaan_gen_recalc_summary_tables_load: ' . $e->getMessage());
		$empty = array(
			'ok' => false,
			'message' => $e->getMessage(),
			'persediaan_bulan_lalu' => array(),
			'persediaan_total_target' => array(),
			'pembelian_semua' => array(),
			'pembelian_update_beli' => array(),
			'pembelian_insert_baru' => array(),
			'persediaan_sumber_tidak_masuk' => array(),
			'pembelian_spop_multi' => array(),
			'pembelian_spop_single' => array(),
		);
		return $empty;
	}
}

/**
 * Muat data 6 tabel rekap hasil generate + pembelian untuk bulan target.
 */
function persediaan_gen_recalc_summary_tables_load($CI, $bulan)
{
	$CI->load->helper('persediaan_display');

	if (!preg_match('/^\d{4}-\d{2}$/', trim((string) $bulan))) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid.');
	}

	$ts = strtotime($bulan . '-01');
	if ($ts === false) {
		return array('ok' => false, 'message' => 'Bulan tidak valid.');
	}

	$tanggal_beli_target = date('Y-m-01', $ts);
	$tanggal_beli_sumber = date('Y-m-01', strtotime('-1 month', $ts));
	$tgl_awal = $tanggal_beli_target;
	$tgl_akhir = date('Y-m-t', $ts);
	$bulan_label = date('m/Y', $ts);
	$bulan_sumber_label = date('m/Y', strtotime($tanggal_beli_sumber));

	$pers_meta = persediaan_gen_recalc_rekap_persediaan_meta($CI);

	$persediaan_bulan_lalu = array();
	$n = 0;
	foreach ($CI->db->query(
		"SELECT * FROM `persediaan` WHERE `tanggal_beli` = ? ORDER BY `namabarang` ASC, `id` ASC",
		array($tanggal_beli_sumber)
	)->result() as $r) {
		$persediaan_bulan_lalu[] = persediaan_gen_recalc_rekap_persediaan_row($CI, $r, ++$n);
	}

	$persediaan_total_target = array();
	$n = 0;
	foreach ($CI->db->query(
		"SELECT * FROM `persediaan` WHERE `tanggal_beli` = ? ORDER BY `namabarang` ASC, `id` ASC",
		array($tanggal_beli_target)
	)->result() as $r) {
		$persediaan_total_target[] = persediaan_gen_recalc_rekap_persediaan_row($CI, $r, ++$n);
	}

	$persediaan_sumber_tidak_masuk = array();
	$n = 0;
	foreach (persediaan_gen_recalc_build_persediaan_sumber_tidak_masuk($CI, $tanggal_beli_sumber, $tanggal_beli_target) as $r) {
		$persediaan_sumber_tidak_masuk[] = persediaan_gen_recalc_rekap_persediaan_row($CI, $r, ++$n);
	}

	$pembelian_semua = array();
	if ($CI->db->table_exists('tbl_pembelian')) {
		$n = 0;
		foreach (persediaan_recalculate_load_pembelian_bulan_rows($CI, 'tbl_pembelian', $tgl_awal, $tgl_akhir) as $r) {
			$pembelian_semua[] = persediaan_gen_recalc_rekap_pembelian_db_row($r, ++$n);
		}
	}

	$items_update = array();
	$items_baru = array();
	$items_pembelian = array();
	$items_penjualan = array();
	$items_penjualan_update = array();
	$hist = persediaan_gen_recalc_history_load($CI, $bulan);
	if (!empty($hist['has_history']) && !empty($hist['data'])) {
		if (!empty($hist['data']['pembelian'])) {
			$items_pembelian = $hist['data']['pembelian'];
		}
		if (!empty($hist['data']['pembelian_update'])) {
			$items_update = $hist['data']['pembelian_update'];
		}
		if (!empty($hist['data']['pembelian_baru'])) {
			$items_baru = $hist['data']['pembelian_baru'];
		}
		if (!empty($hist['data']['penjualan'])) {
			$items_penjualan = $hist['data']['penjualan'];
		}
		if (!empty($hist['data']['penjualan_update'])) {
			$items_penjualan_update = $hist['data']['penjualan_update'];
		}
	}

	$pembelian_update_beli = array();
	$n = 0;
	foreach ($items_update as $item) {
		if (!is_array($item) || (isset($item['aksi']) && $item['aksi'] !== 'UPDATE_BELI')) {
			continue;
		}
		$pembelian_update_beli[] = persediaan_gen_recalc_rekap_pembelian_item_row($item, ++$n);
	}

	$pembelian_insert_baru = array();
	$n = 0;
	foreach ($items_baru as $item) {
		if (!is_array($item) || (isset($item['aksi']) && $item['aksi'] !== 'INSERT_BARU')) {
			continue;
		}
		$pembelian_insert_baru[] = persediaan_gen_recalc_rekap_pembelian_item_row($item, ++$n);
	}

	$pembelian_semua_totals = array('jumlah' => 0);
	foreach ($pembelian_semua as $row) {
		$pembelian_semua_totals['jumlah'] += persediaan_parse_angka(isset($row['jumlah']) ? $row['jumlah'] : 0);
	}

	$status_map = persediaan_gen_recalc_pembelian_status_map_from_batch($items_pembelian, $items_update, $items_baru);
	$spop_rekap = persediaan_gen_recalc_build_pembelian_spop_rekap($CI, $tgl_awal, $tgl_akhir, $status_map);
	$ctx_summary = array(
		'tgl_awal' => $tgl_awal,
		'tgl_akhir' => $tgl_akhir,
		'tanggal_beli_target' => $tanggal_beli_target,
	);
	$penj_rekap = persediaan_gen_recalc_build_penjualan_result_tables($CI, $ctx_summary, $items_penjualan, $items_penjualan_update);
	$orphan_rekap = persediaan_gen_recalc_build_penjualan_uuid_orphan_table($CI, $ctx_summary);

	$result = array(
		'ok' => true,
		'bulan' => $bulan,
		'bulan_label' => $bulan_label,
		'bulan_sumber_label' => $bulan_sumber_label,
		'rekap_meta' => array(
			'persediaan_headers' => $pers_meta['headers'],
			'persediaan_keys' => $pers_meta['keys'],
			'persediaan_sum_keys' => $pers_meta['sum_keys'],
		),
		'persediaan_bulan_lalu' => $persediaan_bulan_lalu,
		'persediaan_bulan_lalu_totals' => persediaan_gen_recalc_rekap_persediaan_totals($CI, $persediaan_bulan_lalu),
		'persediaan_total_target' => $persediaan_total_target,
		'persediaan_total_target_totals' => persediaan_gen_recalc_rekap_persediaan_totals($CI, $persediaan_total_target),
		'pembelian_semua' => $pembelian_semua,
		'pembelian_semua_totals' => $pembelian_semua_totals,
		'pembelian_update_beli' => $pembelian_update_beli,
		'pembelian_update_beli_totals' => persediaan_gen_recalc_rekap_pembelian_totals($pembelian_update_beli),
		'pembelian_insert_baru' => $pembelian_insert_baru,
		'pembelian_insert_baru_totals' => persediaan_gen_recalc_rekap_pembelian_totals($pembelian_insert_baru),
		'persediaan_sumber_tidak_masuk' => $persediaan_sumber_tidak_masuk,
		'persediaan_sumber_tidak_masuk_totals' => persediaan_gen_recalc_rekap_persediaan_totals($CI, $persediaan_sumber_tidak_masuk),
		'pembelian_spop_multi' => $spop_rekap['multi'],
		'pembelian_spop_multi_totals' => $spop_rekap['multi_totals'],
		'pembelian_spop_multi_grup_count' => (int) $spop_rekap['multi_grup_count'],
		'pembelian_spop_single' => $spop_rekap['single'],
		'pembelian_spop_single_totals' => $spop_rekap['single_totals'],
		'penjualan_berhasil' => isset($penj_rekap['berhasil']) ? $penj_rekap['berhasil'] : array(),
		'penjualan_gagal' => isset($penj_rekap['gagal']) ? $penj_rekap['gagal'] : array(),
		'penjualan_berhasil_totals' => isset($penj_rekap['berhasil_totals']) ? $penj_rekap['berhasil_totals'] : array(),
		'penjualan_gagal_totals' => isset($penj_rekap['gagal_totals']) ? $penj_rekap['gagal_totals'] : array(),
		'penjualan_uuid_orphan' => isset($orphan_rekap['rows']) ? $orphan_rekap['rows'] : array(),
		'penjualan_uuid_orphan_totals' => isset($orphan_rekap['totals']) ? $orphan_rekap['totals'] : array(),
		'penjualan_uuid_orphan_count' => isset($orphan_rekap['count']) ? (int) $orphan_rekap['count'] : 0,
	);

	return $result;
}

/**
 * Export Excel satu tabel ringkasan generate.
 */
function persediaan_gen_recalc_summary_export_excel_output($CI, $bulan, $jenis)
{
	$CI->load->helper(array('exportexcel', 'persediaan_display'));

	$defs = persediaan_gen_recalc_summary_jenis_definitions();
	if (!isset($defs[$jenis])) {
		xlsBOF();
		xlsWriteLabel(0, 0, 'Jenis export tidak valid.');
		xlsEOF();
		return;
	}

	$data = persediaan_gen_recalc_summary_tables_load($CI, $bulan);
	if (empty($data['ok'])) {
		xlsBOF();
		xlsWriteLabel(0, 0, isset($data['message']) ? $data['message'] : 'Gagal memuat data.');
		xlsEOF();
		return;
	}

	$def = $defs[$jenis];
	$rows = isset($data[$jenis]) && is_array($data[$jenis]) ? $data[$jenis] : array();
	$headers = $def['headers'];
	if (!empty($def['dynamic_persediaan']) && !empty($data['rekap_meta']['persediaan_headers'])) {
		$headers = $data['rekap_meta']['persediaan_headers'];
	}
	$keys = array();
	if (!empty($def['dynamic_persediaan']) && !empty($data['rekap_meta']['persediaan_keys'])) {
		$keys = $data['rekap_meta']['persediaan_keys'];
	} elseif ($jenis === 'pembelian_semua') {
		$keys = array('no', 'uraian', 'satuan', 'harga_satuan', 'jumlah', 'spop', 'tgl_po');
	} elseif ($jenis === 'pembelian_update_beli') {
		$keys = array('no', 'aksi', 'id_pembelian', 'id_persediaan', 'namabarang', 'satuan', 'hpp', 'spop', 'jumlah_pembelian', 'record_grup', 'beli_lama', 'beli_baru', 'total_10', 'keterangan');
	} elseif ($jenis === 'pembelian_insert_baru') {
		$keys = array('no', 'aksi', 'id_pembelian', 'id_persediaan', 'namabarang', 'satuan', 'hpp', 'spop', 'jumlah_pembelian', 'record_grup', 'beli_baru', 'total_10', 'keterangan');
	} elseif ($jenis === 'pembelian_spop_multi' || $jenis === 'pembelian_spop_single') {
		$keys = array('no', 'id_pembelian', 'uraian', 'satuan', 'harga_satuan', 'jumlah', 'spop', 'tgl_po', 'record_grup', 'keterangan_baris');
	}

	$totals_key = $jenis . '_totals';
	$totals = isset($data[$totals_key]) && is_array($data[$totals_key]) ? $data[$totals_key] : array();

	$title = $def['title'];
	if ($jenis === 'persediaan_bulan_lalu' || $jenis === 'persediaan_sumber_tidak_masuk') {
		$title .= ' — ' . $data['bulan_sumber_label'];
	} else {
		$title .= ' — ' . $data['bulan_label'];
	}

	xlsBOF();
	xlsWriteLabelBold14(0, 0, $title);
	xlsWriteLabel(1, 0, 'Dicetak: ' . date('d/m/Y H:i:s'));

	$headerRow = 3;
	persediaan_excel_write_headers($headerRow, $headers);

	$rowNum = $headerRow + 1;
	foreach ($rows as $row) {
		$col = 0;
		$rowStyle = ($rowNum % 2 === 0) ? 18 : 7;
		if (!empty($keys)) {
			foreach ($keys as $key) {
				$val = is_array($row) && isset($row[$key]) ? $row[$key] : '';
				$is_subtotal = is_array($row) && isset($row['row_type']) && $row['row_type'] === 'subtotal';
				if ($is_subtotal) {
					xlsWriteCellStyle($rowNum, $col, (string) $val, 13);
				} else {
					xlsWriteCellStyle($rowNum, $col, (string) $val, $rowStyle);
				}
				$col++;
			}
		} else {
			foreach ($row as $val) {
				xlsWriteCellStyle($rowNum, $col, (string) $val, $rowStyle);
				$col++;
			}
		}
		$rowNum++;
	}

	if (!empty($totals) && !empty($keys)) {
		$col = 0;
		foreach ($keys as $key) {
			if ($col === 0) {
				xlsWriteCellStyle($rowNum, $col, 'TOTAL', 24);
			} elseif (isset($totals[$key])) {
				xlsWriteCellStyle($rowNum, $col, (string) persediaan_format_angka_tampil($totals[$key]), 25);
			} else {
				xlsWriteCellStyle($rowNum, $col, '', 24);
			}
			$col++;
		}
	}

	xlsEOF();
}

/**
 * Sanitasi string agar aman di json_encode (UTF-8).
 */
function penjualan_sanitize_utf8_string($str)
{
	if (!is_string($str)) {
		return $str;
	}
	if (function_exists('mb_convert_encoding')) {
		return mb_convert_encoding($str, 'UTF-8', 'UTF-8');
	}
	return $str;
}

/**
 * Output JSON aman untuk endpoint AJAX penjualan.
 */
function penjualan_json_response($CI, array $payload)
{
	if (isset($payload['tbody'])) {
		$payload['tbody'] = penjualan_sanitize_utf8_string($payload['tbody']);
	}
	if (isset($payload['modals'])) {
		$payload['modals'] = penjualan_sanitize_utf8_string($payload['modals']);
	}

	$flags = JSON_UNESCAPED_UNICODE;
	if (defined('JSON_INVALID_UTF8_SUBSTITUTE')) {
		$flags |= JSON_INVALID_UTF8_SUBSTITUTE;
	}

	$json = json_encode($payload, $flags);
	if ($json === false) {
		$json = json_encode(array(
			'ok' => false,
			'message' => 'Gagal mengenkode data JSON: ' . json_last_error_msg(),
		), $flags);
	}

	$CI->output
		->set_content_type('application/json', 'utf-8')
		->set_output($json === false ? '{"ok":false,"message":"Gagal mengenkode JSON"}' : $json);
}

/**
 * Kondisi SQL: hanya kategori jasa (untuk modul penjualan jasa).
 */
function penjualan_sql_kategori_jasa_saja($CI, $alias = 'persediaan')
{
	if (!$CI->db->field_exists('kategori', 'persediaan')) {
		return '1=1';
	}
	$a = trim((string) $alias);
	return "LOWER(TRIM(COALESCE({$a}.kategori, ''))) = 'jasa'";
}

/**
 * Daftar persediaan kategori jasa untuk modal Pilih Jasa penjualan.
 */
function penjualan_get_stock_persediaan_jasa_rows($CI, $tgl_jual = null, $uuid_unit = null)
{
	$tgl_jual = trim((string) $tgl_jual);
	if ($tgl_jual === '') {
		return array();
	}

	$tgl = pembelian_get_filter_tanggal($CI, $tgl_jual);
	$has_kategori = $CI->db->field_exists('kategori', 'persediaan');
	$kategori_sql = $has_kategori ? 'persediaan.kategori AS kategori_barang' : "'' AS kategori_barang";
	$jasa_sql = penjualan_sql_kategori_jasa_saja($CI, 'persediaan');
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
		AND {$jasa_sql}
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
		$pesan = isset($err['message']) ? $err['message'] : 'Query persediaan jasa gagal.';
		throw new Exception($pesan);
	}

	return $query->result();
}

/**
 * Render HTML tbody + modal nested untuk Pilih Jasa penjualan.
 */
function penjualan_render_modal_pilih_jasa($CI, $data)
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
	$tbody = $CI->load->view('anekadharma/tbl_penjualan_jasa/_modal_pilih_jasa_penjualan_fragment', $data, TRUE);
	$data['fragment_part'] = 'modals';
	$modals = $CI->load->view('anekadharma/tbl_penjualan_jasa/_modal_pilih_jasa_penjualan_fragment', $data, TRUE);

	return array(
		'tbody' => $tbody,
		'modals' => $modals,
	);
}

/**
 * Hapus semua baris penjualan jasa satu transaksi + kembalikan field penjualan di persediaan.
 */
function penjualan_jasa_hapus_semua_by_uuid($CI, $uuid_penjualan)
{
	$uuid_penjualan = trim((string) $uuid_penjualan);
	if ($uuid_penjualan === '') {
		return 0;
	}

	$CI->load->model('Tbl_penjualan_jasa_model');
	$CI->load->model('Persediaan_model');
	$rows = $CI->Tbl_penjualan_jasa_model->get_all_by_uuid_penjualan($uuid_penjualan);
	$jumlah_hapus = 0;

	foreach ($rows as $row) {
		if (empty($row->id)) {
			continue;
		}
		$row_penjualan = $CI->Tbl_penjualan_jasa_model->get_by_id($row->id);
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

		$CI->Tbl_penjualan_jasa_model->delete($row->id);
		$jumlah_hapus++;
	}

	return $jumlah_hapus;
}

/**
 * Compare jurnal pembelian manual (tabel upload) vs online (tbl_pembelian).
 * Bandingkan: tanggal, SPOP, supplier, jumlah (online = jumlah × harga_satuan).
 */
function pembelian_jurnal_compare_pick_tanggal_column($fields)
{
	return persediaan_compare_pick_column($fields, array(
		'tanggal', 'tgl_po', 'tgl', 'tanggal_beli', 'tgl_transaksi', 'date', 'tgl_beli',
	));
}

function pembelian_jurnal_compare_build_column_map($fields)
{
	if (!is_array($fields) || count($fields) === 0) {
		return null;
	}

	$normalized = array();
	foreach ($fields as $field) {
		$normalized[] = trim((string) $field);
	}

	$map = array(
		'tanggal' => pembelian_jurnal_compare_pick_tanggal_column($normalized),
		'spop' => persediaan_compare_pick_column($normalized, array(
			'spop', 'no. spop', 'no spop', 'no_spop', 'nomor_spop', 'nmr_spop',
		)),
		'supplier' => persediaan_compare_pick_column($normalized, array(
			'supplier', 'supplier_nama', 'nama_supplier', 'supplier name', 'nama supplier', 'nama_supplier_pembelian',
		)),
		'jumlah' => persediaan_compare_pick_column($normalized, array(
			'jumlah', 'debet', 'debit', 'qty', 'quantity', 'nominal', 'total',
		)),
	);

	if (empty($map['spop']) || empty($map['supplier']) || empty($map['jumlah'])) {
		return null;
	}

	return $map;
}

function pembelian_jurnal_compare_validate_table($CI, $table)
{
	if (!persediaan_compare_is_valid_table_name($table)) {
		return array('ok' => false, 'message' => 'Nama tabel tidak valid.');
	}

	if (!$CI->db->table_exists($table)) {
		return array('ok' => false, 'message' => 'Tabel tidak ditemukan di database.');
	}

	$fields = $CI->db->list_fields($table);
	$map = pembelian_jurnal_compare_build_column_map($fields);
	if ($map === null) {
		return array(
			'ok' => false,
			'message' => 'Tabel harus punya kolom minimal: spop, supplier (SUPPLIER/supplier_nama), dan jumlah.',
		);
	}

	return array(
		'ok' => true,
		'map' => $map,
		'fields' => $fields,
	);
}

function pembelian_jurnal_compare_normalize_spop($spop)
{
	if (persediaan_recalculate_spop_kosong_atau_nol($spop)) {
		return '0';
	}

	$s = trim((string) $spop);
	if ($s === '') {
		return '0';
	}

	if (preg_match('/^\d+([.,]\d+)?$/', $s)) {
		$s = str_replace(',', '.', $s);
		$num = (float) $s;
		if (abs($num - round($num)) < 0.0001) {
			return (string) (int) round($num);
		}
	}

	if (preg_match('/^\d+$/', $s)) {
		return (string) (int) $s;
	}

	return strtolower(preg_replace('/\s+/', ' ', $s));
}

function pembelian_jurnal_compare_normalize_supplier($supplier)
{
	$supplier = trim((string) $supplier);
	$supplier = preg_replace('/[\x{00A0}\x{200B}\x{FEFF}]/u', '', $supplier);
	$supplier = preg_replace('/\s+/u', ' ', $supplier);

	if (function_exists('mb_strtolower')) {
		return mb_strtolower($supplier, 'UTF-8');
	}

	return strtolower($supplier);
}

function pembelian_jurnal_compare_is_row_complete($spop, $supplier, $jumlah)
{
	if (trim((string) $spop) === '') {
		return false;
	}
	if (trim((string) $supplier) === '') {
		return false;
	}
	if (trim((string) $jumlah) === '') {
		return false;
	}

	return true;
}

function pembelian_jurnal_compare_build_incomplete_keterangan($spop, $supplier, $jumlah, $sumber = 'manual')
{
	$missing = array();
	if (trim((string) $spop) === '') {
		$missing[] = 'SPOP';
	}
	if (trim((string) $supplier) === '') {
		$missing[] = 'Supplier';
	}
	if (trim((string) $jumlah) === '') {
		$missing[] = 'Jumlah';
	}

	$label = ($sumber === 'online') ? 'Online (tbl_pembelian)' : 'Manual (tabel CSV)';

	return $label . ': tidak dapat diproses compare — field kosong: ' . implode(', ', $missing) . '.';
}

function pembelian_jurnal_compare_incomplete_row_to_json($row, $keterangan)
{
	$tanggal = '';
	if (!empty($row['tanggal'])) {
		$tanggal = pembelian_jurnal_compare_format_tanggal_display($row['tanggal']);
	} elseif (!empty($row['tanggal_raw'])) {
		$tanggal = pembelian_jurnal_compare_format_tanggal_display(
			pembelian_jurnal_compare_normalize_tanggal($row['tanggal_raw'])
		);
	}

	return array(
		'sumber' => isset($row['sumber']) ? $row['sumber'] : '',
		'tanggal' => $tanggal,
		'spop' => isset($row['spop_display']) ? $row['spop_display'] : (isset($row['spop']) ? $row['spop'] : ''),
		'supplier' => isset($row['supplier']) ? $row['supplier'] : '',
		'jumlah' => trim((string) (isset($row['jumlah']) ? $row['jumlah'] : '')) !== ''
			? pembelian_jurnal_compare_format_jumlah_display($row['jumlah'])
			: '',
		'keterangan' => $keterangan,
	);
}

function pembelian_jurnal_compare_parse_jumlah_nominal($value)
{
	$v = trim((string) $value);
	if ($v === '' || $v === '-') {
		return 0.0;
	}

	$negative = false;
	if ($v[0] === '-' || $v[0] === '(') {
		$negative = true;
	}

	$v = preg_replace('/^\s*(Rp\.?\s*|IDR\s*)/i', '', $v);
	$v = trim(str_replace(' ', '', $v));
	$v = preg_replace('/[^0-9.,\-]/', '', $v);

	if ($v === '' || $v === '-') {
		return 0.0;
	}

	// Excel/jurnal sering menulis 3.208.000 menjadi 3.208.00 — lengkapi grup ribuan terakhir.
	if (preg_match('/^(\d{1,3}(?:\.\d{3})+)\.(\d{2})$/', $v, $m)) {
		$v = $m[1] . '.' . str_pad($m[2], 3, '0', STR_PAD_RIGHT);
	}

	$last_comma = strrpos($v, ',');
	$last_dot = strrpos($v, '.');

	if ($last_comma !== false && $last_dot !== false) {
		if ($last_comma > $last_dot) {
			$integer = str_replace('.', '', substr($v, 0, $last_comma));
			$decimal = substr($v, $last_comma + 1);
			$num = (float) ($integer . '.' . $decimal);
		} else {
			$num = (float) str_replace(',', '', $v);
		}
	} elseif ($last_comma !== false) {
		$after_comma = substr($v, $last_comma + 1);
		if (substr_count($v, ',') === 1 && strlen($after_comma) <= 2) {
			$integer = str_replace('.', '', substr($v, 0, $last_comma));
			$num = (float) ($integer . '.' . $after_comma);
		} else {
			$num = (float) str_replace(',', '', $v);
		}
	} elseif ($last_dot !== false) {
		if (substr_count($v, '.') > 1) {
			$num = (float) str_replace('.', '', $v);
		} else {
			$after_dot = substr($v, $last_dot + 1);
			if (strlen($after_dot) === 3 && strpos($v, ',') === false) {
				$num = (float) str_replace('.', '', $v);
			} else {
				$num = (float) $v;
			}
		}
	} else {
		$num = (float) $v;
	}

	if ($negative) {
		$num = -abs($num);
	}

	return $num;
}

function pembelian_jurnal_compare_normalize_jumlah_csv_cell($value)
{
	return (int) round(pembelian_jurnal_compare_parse_jumlah_nominal($value));
}

function pembelian_jurnal_compare_normalize_jumlah($value)
{
	return round(pembelian_jurnal_compare_parse_jumlah_nominal($value), 2);
}

function pembelian_jurnal_compare_normalize_tanggal($value)
{
	$value = trim((string) $value);
	if ($value === '' || $value === '0000-00-00' || $value === '0000-00-00 00:00:00') {
		return '';
	}

	$ts = strtotime($value);
	if ($ts !== false) {
		return date('Y-m-d', $ts);
	}

	if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $value, $m)) {
		$ts = mktime(0, 0, 0, (int) $m[2], (int) $m[1], (int) $m[3]);
		if ($ts !== false) {
			return date('Y-m-d', $ts);
		}
	}

	return '';
}

function pembelian_jurnal_compare_format_tanggal_display($ymd)
{
	$ymd = trim((string) $ymd);
	if ($ymd === '') {
		return '';
	}
	$ts = strtotime($ymd);
	if ($ts === false) {
		return $ymd;
	}

	return date('d-m-Y', $ts);
}

function pembelian_jurnal_compare_format_jumlah_display($jumlah)
{
	return number_format((float) $jumlah, 2, ',', '.');
}

function pembelian_jurnal_compare_make_pair_key($spop, $supplier)
{
	return pembelian_jurnal_compare_normalize_spop($spop)
		. '|' . pembelian_jurnal_compare_normalize_supplier($supplier);
}

function pembelian_jurnal_compare_find_group_key($groups, $spop, $supplier)
{
	if (!is_array($groups) || count($groups) === 0) {
		return null;
	}

	$target_key = pembelian_jurnal_compare_make_pair_key($spop, $supplier);
	if (isset($groups[$target_key])) {
		return $target_key;
	}

	$target_spop = pembelian_jurnal_compare_normalize_spop($spop);
	$target_sup = pembelian_jurnal_compare_normalize_supplier($supplier);

	foreach ($groups as $key => $row) {
		if (!is_array($row)) {
			continue;
		}
		$row_spop = pembelian_jurnal_compare_normalize_spop(isset($row['spop']) ? $row['spop'] : '');
		$row_sup = pembelian_jurnal_compare_normalize_supplier(isset($row['supplier']) ? $row['supplier'] : '');
		if ($row_spop === $target_spop && $row_sup === $target_sup) {
			return $key;
		}
	}

	return null;
}

function pembelian_jurnal_compare_jumlah_values_equal($jumlah_a, $jumlah_b)
{
	$a = pembelian_jurnal_compare_normalize_jumlah($jumlah_a);
	$b = pembelian_jurnal_compare_normalize_jumlah($jumlah_b);

	if (abs($a - $b) < 0.01) {
		return true;
	}

	$a_int = (int) round($a);
	$b_int = (int) round($b);

	return $a_int === $b_int;
}

function pembelian_jurnal_compare_format_total_display($total)
{
	return pembelian_jurnal_compare_format_jumlah_display($total);
}

function pembelian_jurnal_compare_normalize_total($value)
{
	return pembelian_jurnal_compare_normalize_jumlah($value);
}

function pembelian_jurnal_compare_bulan_label($bulan)
{
	$range = persediaan_compare_bulan_to_date_range($bulan);

	return $range ? $range['bulan_label'] : $bulan;
}

function pembelian_jurnal_compare_rows_match($manual, $online)
{
	if (!is_array($manual) || !is_array($online)) {
		return false;
	}

	if (pembelian_jurnal_compare_normalize_spop(isset($manual['spop']) ? $manual['spop'] : '')
		!== pembelian_jurnal_compare_normalize_spop(isset($online['spop']) ? $online['spop'] : '')) {
		return false;
	}

	if (pembelian_jurnal_compare_normalize_supplier(isset($manual['supplier']) ? $manual['supplier'] : '')
		!== pembelian_jurnal_compare_normalize_supplier(isset($online['supplier']) ? $online['supplier'] : '')) {
		return false;
	}

	return pembelian_jurnal_compare_jumlah_values_equal(
		isset($manual['jumlah']) ? $manual['jumlah'] : 0,
		isset($online['jumlah']) ? $online['jumlah'] : 0
	);
}

function pembelian_jurnal_compare_build_keterangan($manual, $online, $manual_groups = array(), $online_groups = array())
{
	if ($manual === null && $online !== null && is_array($manual_groups) && count($manual_groups) > 0) {
		$manual_match = pembelian_jurnal_compare_find_row_by_spop_supplier(
			$manual_groups,
			isset($online['spop']) ? $online['spop'] : '',
			isset($online['supplier']) ? $online['supplier'] : ''
		);
		if ($manual_match !== null) {
			return pembelian_jurnal_compare_build_keterangan($manual_match, $online, $manual_groups, $online_groups);
		}

		return pembelian_jurnal_compare_build_spop_reference_keterangan(
			$online,
			$manual_groups,
			'Online',
			'Manual'
		);
	}

	if ($online === null && $manual !== null && is_array($online_groups) && count($online_groups) > 0) {
		$online_match = pembelian_jurnal_compare_find_row_by_spop_supplier(
			$online_groups,
			isset($manual['spop']) ? $manual['spop'] : '',
			isset($manual['supplier']) ? $manual['supplier'] : ''
		);
		if ($online_match !== null) {
			return pembelian_jurnal_compare_build_keterangan($manual, $online_match, $manual_groups, $online_groups);
		}

		return pembelian_jurnal_compare_build_spop_reference_keterangan(
			$manual,
			$online_groups,
			'Manual',
			'Online'
		);
	}

	if ($manual === null && $online === null) {
		return '';
	}
	if ($manual === null) {
		return 'Tidak ditemukan pasangan di tabel manual untuk SPOP '
			. (isset($online['spop_display']) ? $online['spop_display'] : (isset($online['spop']) ? $online['spop'] : '—'))
			. ' dan Supplier '
			. (isset($online['supplier']) ? $online['supplier'] : '—') . '.';
	}
	if ($online === null) {
		return 'Tidak ditemukan pasangan di tbl_pembelian (online) untuk SPOP '
			. (isset($manual['spop_display']) ? $manual['spop_display'] : (isset($manual['spop']) ? $manual['spop'] : '—'))
			. ' dan Supplier '
			. (isset($manual['supplier']) ? $manual['supplier'] : '—') . '.';
	}

	$parts = array();
	$tgl_m = pembelian_jurnal_compare_normalize_tanggal($manual['tanggal']);
	$tgl_o = pembelian_jurnal_compare_normalize_tanggal($online['tanggal']);
	$spop_m = pembelian_jurnal_compare_normalize_spop($manual['spop']);
	$spop_o = pembelian_jurnal_compare_normalize_spop($online['spop']);
	$sup_m = pembelian_jurnal_compare_normalize_supplier($manual['supplier']);
	$sup_o = pembelian_jurnal_compare_normalize_supplier($online['supplier']);
	$jml_m = pembelian_jurnal_compare_normalize_jumlah($manual['jumlah']);
	$jml_o = pembelian_jurnal_compare_normalize_jumlah($online['jumlah']);

	$spop_same = ($spop_m === $spop_o);
	$sup_same = ($sup_m === $sup_o);
	$jml_diff = !pembelian_jurnal_compare_jumlah_values_equal($manual['jumlah'], $online['jumlah']);
	$tgl_diff = ($tgl_m !== $tgl_o && $tgl_m !== '' && $tgl_o !== '');

	if ($tgl_diff) {
		$parts[] = 'Tanggal berbeda (Manual: ' . pembelian_jurnal_compare_format_tanggal_display($tgl_m)
			. ', Online: ' . pembelian_jurnal_compare_format_tanggal_display($tgl_o) . ')';
	}

	if (!$spop_same) {
		$parts[] = 'SPOP berbeda (Manual: ' . (isset($manual['spop_display']) ? $manual['spop_display'] : $manual['spop'])
			. ', Online: ' . (isset($online['spop_display']) ? $online['spop_display'] : $online['spop']) . ')';
	}

	if ($spop_same && !$sup_same) {
		$parts[] = 'SPOP sama (' . (isset($manual['spop_display']) ? $manual['spop_display'] : $manual['spop'])
			. '), Supplier berbeda (Manual: ' . $manual['supplier'] . ', Online: ' . $online['supplier'] . ')';
	}

	if ($spop_same && $sup_same && $jml_diff) {
		$parts[] = 'SPOP sama (' . (isset($manual['spop_display']) ? $manual['spop_display'] : $manual['spop'])
			. '), Supplier sama (' . $manual['supplier'] . '), Jumlah berbeda (Manual: '
			. pembelian_jurnal_compare_format_jumlah_display($jml_m)
			. ', Online: ' . pembelian_jurnal_compare_format_jumlah_display($jml_o) . ')';
	} elseif ($jml_diff && !($spop_same && $sup_same)) {
		$parts[] = 'Jumlah berbeda (Manual: ' . pembelian_jurnal_compare_format_jumlah_display($jml_m)
			. ', Online: ' . pembelian_jurnal_compare_format_jumlah_display($jml_o) . ')';
	}

	$summary = '';
	if ($spop_same && $sup_same && $jml_diff) {
		$summary = 'SPOP sama, Supplier sama, Jumlah berbeda';
		if ($tgl_diff) {
			$summary .= ', Tanggal berbeda';
		}
	} elseif ($spop_same && !$sup_same) {
		$summary = 'SPOP sama, Supplier berbeda';
	} elseif (!$spop_same && $sup_same) {
		$summary = 'SPOP berbeda, Supplier sama';
	} elseif (!$spop_same && !$sup_same) {
		$summary = 'SPOP berbeda, Supplier berbeda';
	}

	if (count($parts) === 0) {
		return 'Semua field cocok (tanggal, SPOP, supplier, jumlah).';
	}

	$detail = implode('; ', $parts);

	return ($summary !== '') ? ($summary . ' — ' . $detail) : $detail;
}

function pembelian_jurnal_compare_is_summary_row($spop, $supplier, $jumlah_raw)
{
	$supplier_l = strtolower(trim((string) $supplier));
	if ($supplier_l !== '' && strpos($supplier_l, 'total pembelian unit') === 0) {
		return true;
	}

	$spop_l = strtolower(trim((string) $spop));
	if ($spop_l !== '' && strpos($spop_l, 'total pembelian unit') === 0) {
		return true;
	}

	$jumlah_l = strtolower(trim((string) $jumlah_raw));
	if ($jumlah_l !== '' && strpos($jumlah_l, 'total pembelian unit') === 0) {
		return true;
	}

	return false;
}

function pembelian_jurnal_compare_find_all_rows_by_spop($groups, $spop)
{
	if (!is_array($groups) || count($groups) === 0) {
		return array();
	}

	$target_spop = pembelian_jurnal_compare_normalize_spop($spop);
	$found = array();

	foreach ($groups as $row) {
		if (!is_array($row)) {
			continue;
		}
		if (pembelian_jurnal_compare_normalize_spop(isset($row['spop']) ? $row['spop'] : '') === $target_spop) {
			$found[] = $row;
		}
	}

	return $found;
}

function pembelian_jurnal_compare_build_spop_reference_keterangan($side_row, $reference_groups, $side_label, $reference_label)
{
	$spop_display = isset($side_row['spop_display'])
		? $side_row['spop_display']
		: (isset($side_row['spop']) ? $side_row['spop'] : '—');
	$candidates = pembelian_jurnal_compare_find_all_rows_by_spop(
		$reference_groups,
		isset($side_row['spop']) ? $side_row['spop'] : ''
	);

	if (count($candidates) === 0) {
		return 'Tidak ditemukan data ' . $reference_label . ' dengan SPOP ' . $spop_display . '.';
	}

	$side_supplier = isset($side_row['supplier']) ? $side_row['supplier'] : '';
	$side_sup_norm = pembelian_jurnal_compare_normalize_supplier($side_supplier);
	$side_jml = pembelian_jurnal_compare_normalize_jumlah(isset($side_row['jumlah']) ? $side_row['jumlah'] : 0);

	$lines = array();
	$lines[] = 'Referensi SPOP ' . $spop_display . ' — bandingkan ' . $side_label . ' vs ' . $reference_label . ':';

	$has_supplier_match = false;
	$has_jumlah_match = false;
	$has_exact_match = false;

	foreach ($candidates as $idx => $candidate) {
		$candidate_supplier = isset($candidate['supplier']) ? $candidate['supplier'] : '';
		$candidate_sup_norm = pembelian_jurnal_compare_normalize_supplier($candidate_supplier);
		$candidate_jml = pembelian_jurnal_compare_normalize_jumlah(isset($candidate['jumlah']) ? $candidate['jumlah'] : 0);
		$sup_same = ($candidate_sup_norm === $side_sup_norm);
		$jml_same = pembelian_jurnal_compare_jumlah_values_equal(
			isset($candidate['jumlah']) ? $candidate['jumlah'] : 0,
			isset($side_row['jumlah']) ? $side_row['jumlah'] : 0
		);

		if ($sup_same) {
			$has_supplier_match = true;
		}
		if ($jml_same) {
			$has_jumlah_match = true;
		}
		if ($sup_same && $jml_same) {
			$has_exact_match = true;
		}

		$lines[] = ($idx + 1) . '. Supplier ' . $reference_label . ': ' . ($candidate_supplier !== '' ? $candidate_supplier : '—')
			. ' (' . ($sup_same ? 'sama' : 'beda') . ' vs ' . $side_label . ': ' . ($side_supplier !== '' ? $side_supplier : '—') . ')'
			. '; Jumlah ' . $reference_label . ': ' . pembelian_jurnal_compare_format_jumlah_display($candidate_jml)
			. ' (' . ($jml_same ? 'sama' : 'beda') . ' vs ' . $side_label . ': ' . pembelian_jurnal_compare_format_jumlah_display($side_jml) . ')';
	}

	if ($has_exact_match) {
		$lines[] = 'Kesimpulan: SPOP, Supplier, dan Jumlah seharusnya cocok — periksa normalisasi data atau re-import CSV.';
	} elseif ($has_supplier_match && !$has_jumlah_match) {
		$lines[] = 'Kesimpulan: SPOP & Supplier sama, perbedaan di Jumlah.';
	} elseif (!$has_supplier_match && $has_jumlah_match) {
		$lines[] = 'Kesimpulan: SPOP & Jumlah sama, perbedaan di nama Supplier.';
	} elseif (!$has_supplier_match && !$has_jumlah_match) {
		$lines[] = 'Kesimpulan: SPOP sama, Supplier dan Jumlah berbeda.';
	} else {
		$lines[] = 'Kesimpulan: SPOP sama, lihat detail Supplier/Jumlah di atas.';
	}

	return implode(' ', $lines);
}

function pembelian_jurnal_compare_find_row_by_spop_supplier($groups, $spop, $supplier)
{
	if (!is_array($groups) || count($groups) === 0) {
		return null;
	}

	$target_spop = pembelian_jurnal_compare_normalize_spop($spop);
	$target_sup = pembelian_jurnal_compare_normalize_supplier($supplier);

	foreach ($groups as $row) {
		if (!is_array($row)) {
			continue;
		}
		if (pembelian_jurnal_compare_normalize_spop(isset($row['spop']) ? $row['spop'] : '')
			=== $target_spop
			&& pembelian_jurnal_compare_normalize_supplier(isset($row['supplier']) ? $row['supplier'] : '')
			=== $target_sup) {
			return $row;
		}
	}

	return null;
}

function pembelian_jurnal_compare_find_row_by_spop_only($groups, $spop)
{
	if (!is_array($groups) || count($groups) === 0) {
		return null;
	}

	$target_spop = pembelian_jurnal_compare_normalize_spop($spop);

	foreach ($groups as $row) {
		if (!is_array($row)) {
			continue;
		}
		if (pembelian_jurnal_compare_normalize_spop(isset($row['spop']) ? $row['spop'] : '') === $target_spop) {
			return $row;
		}
	}

	return null;
}

function pembelian_jurnal_compare_detect_csv_column_map($headers)
{
	return pembelian_jurnal_compare_build_column_map($headers);
}

function pembelian_jurnal_compare_csv_column_error_detail($raw_headers)
{
	$normalized = array();
	foreach ((array) $raw_headers as $header) {
		$normalized[] = trim((string) $header);
	}

	$labels = array(
		'tanggal' => 'tanggal (atau tgl / tgl_po / date)',
		'spop' => 'spop',
		'supplier' => 'supplier (SUPPLIER / supplier_nama / nama_supplier)',
		'jumlah' => 'jumlah',
	);

	$found = array(
		'tanggal' => pembelian_jurnal_compare_pick_tanggal_column($normalized),
		'spop' => persediaan_compare_pick_column($normalized, array('spop')),
		'supplier' => persediaan_compare_pick_column($normalized, array(
			'supplier', 'supplier_nama', 'nama_supplier', 'supplier name', 'nama supplier',
		)),
		'jumlah' => persediaan_compare_pick_column($normalized, array('jumlah', 'debet', 'debit')),
	);

	$missing = array();
	foreach ($labels as $key => $label) {
		if (empty($found[$key])) {
			$missing[] = $label;
		}
	}

	$lines = array();
	$lines[] = 'File CSV tidak dapat digunakan. Kolom wajib berikut harus ada di baris header:';
	$lines[] = '';
	$lines[] = '• tanggal';
	$lines[] = '• spop';
	$lines[] = '• supplier';
	$lines[] = '• jumlah';
	$lines[] = '';
	if (count($missing) > 0) {
		$lines[] = 'Kolom yang tidak ditemukan / tidak dikenali:';
		foreach ($missing as $label) {
			$lines[] = '  - ' . $label;
		}
	}
	$lines[] = '';
	$lines[] = 'Header terbaca: ' . persediaan_compare_csv_headers_preview($raw_headers);

	return implode("\n", $lines);
}

function pembelian_jurnal_compare_validate_csv_file($filepath)
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

	$map = pembelian_jurnal_compare_detect_csv_column_map($raw_headers);
	if ($map === null) {
		return array(
			'ok' => false,
			'stage' => 'validasi_kolom',
			'message' => pembelian_jurnal_compare_csv_column_error_detail($raw_headers),
			'headers' => $raw_headers,
		);
	}

	return array(
		'ok' => true,
		'stage' => 'valid',
		'message' => 'Kolom CSV valid: tanggal, spop, supplier, dan jumlah ditemukan.',
		'headers' => $raw_headers,
		'map' => $map,
	);
}

function pembelian_jurnal_compare_parse_tgl_awal_ref($tgl_awal_ref)
{
	$tgl_awal_ref = trim((string) $tgl_awal_ref);
	if ($tgl_awal_ref === '') {
		return array(
			'day' => (int) date('j'),
			'month' => (int) date('n'),
			'year' => (int) date('Y'),
		);
	}

	if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $tgl_awal_ref, $m)) {
		return array(
			'day' => (int) $m[1],
			'month' => (int) $m[2],
			'year' => (int) $m[3],
		);
	}

	$ts = strtotime($tgl_awal_ref);
	if ($ts !== false) {
		return array(
			'day' => (int) date('j', $ts),
			'month' => (int) date('n', $ts),
			'year' => (int) date('Y', $ts),
		);
	}

	return array(
		'day' => 1,
		'month' => (int) date('n'),
		'year' => (int) date('Y'),
	);
}

function pembelian_jurnal_compare_normalize_tanggal_csv_cell($value, $ref_month, $ref_year)
{
	$value = trim((string) $value);
	if ($value === '') {
		return '';
	}

	if (preg_match('/^\d{1,2}$/', $value)) {
		$day = (int) $value;
		if ($day >= 1 && $day <= 31) {
			$month = (int) $ref_month;
			$year = (int) $ref_year;
			if (!checkdate($month, $day, $year)) {
				return sprintf('%02d-%02d-%04d', $day, $month, $year);
			}

			return date('d-m-Y', mktime(0, 0, 0, $month, $day, $year));
		}
	}

	$norm = pembelian_jurnal_compare_normalize_tanggal($value);
	if ($norm !== '') {
		return pembelian_jurnal_compare_format_tanggal_display($norm);
	}

	return $value;
}

function pembelian_jurnal_compare_check_csv_table_name($CI, $original_filename, $bulan_key = '')
{
	$base_table = persediaan_compare_sanitize_table_name_from_csv($original_filename, $bulan_key);
	if (!persediaan_compare_is_valid_table_name($base_table)) {
		return array('ok' => false, 'message' => 'Nama tabel dari file CSV tidak valid.');
	}

	persediaan_compare_clear_db_schema_cache($CI);
	$exists = $CI->db->table_exists($base_table);
	$resolved = persediaan_compare_resolve_unique_table_name($CI, $base_table);
	if (empty($resolved['ok'])) {
		return $resolved;
	}

	return array(
		'ok' => true,
		'base_table' => $base_table,
		'table_exists' => $exists,
		'will_create' => $resolved['table'],
		'suffix' => isset($resolved['suffix']) ? (int) $resolved['suffix'] : 0,
	);
}

function pembelian_jurnal_compare_import_csv_to_db($CI, $filepath, $original_filename, $bulan_key = '', $tgl_awal_ref = '')
{
	$file_label = trim((string) $original_filename);
	if ($file_label === '') {
		$file_label = basename((string) $filepath);
	}

	$validated = pembelian_jurnal_compare_validate_csv_file($filepath);
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
	$pembelian_map = pembelian_jurnal_compare_build_column_map($db_columns_sanitized);
	$jumlah_col = ($pembelian_map && !empty($pembelian_map['jumlah'])) ? $pembelian_map['jumlah'] : null;
	$db_columns = array();
	foreach ($db_columns_sanitized as $idx => $col) {
		if (strtolower($col) === 'id') {
			continue;
		}
		$db_columns[$idx] = $col;
	}
	$tgl_ref = pembelian_jurnal_compare_parse_tgl_awal_ref($tgl_awal_ref);
	$tanggal_col = ($pembelian_map && !empty($pembelian_map['tanggal'])) ? $pembelian_map['tanggal'] : null;
	$base_table = persediaan_compare_sanitize_table_name_from_csv($original_filename, $bulan_key);
	$resolved = persediaan_compare_resolve_unique_table_name($CI, $base_table);
	if (empty($resolved['ok'])) {
		fclose($handle);
		return array(
			'ok' => false,
			'stage' => 'nama_tabel',
			'message' => isset($resolved['message'])
				? $resolved['message']
				: "Nama tabel hasil import dari file `{$file_label}` tidak valid.",
			'file' => $file_label,
		);
	}

	$table = $resolved['table'];
	$table_suffix = isset($resolved['suffix']) ? (int) $resolved['suffix'] : 0;
	$table_base = isset($resolved['base']) ? $resolved['base'] : $base_table;
	$table_exists_before = $CI->db->table_exists($table_base);

	$field_defs = array('`id` INT(11) NOT NULL AUTO_INCREMENT');
	foreach ($db_columns as $col) {
		if ($jumlah_col !== null && $col === $jumlah_col) {
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
		foreach ($db_columns as $idx => $col) {
			$data[$col] = isset($row[$idx]) ? trim((string) $row[$idx]) : '';
		}

		if ($tanggal_col !== null && isset($data[$tanggal_col])) {
			$data[$tanggal_col] = pembelian_jurnal_compare_normalize_tanggal_csv_cell(
				$data[$tanggal_col],
				$tgl_ref['month'],
				$tgl_ref['year']
			);
		}

		if ($jumlah_col !== null && isset($data[$jumlah_col]) && trim((string) $data[$jumlah_col]) !== '') {
			$data[$jumlah_col] = pembelian_jurnal_compare_normalize_jumlah_csv_cell($data[$jumlah_col]);
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

	return array(
		'ok' => true,
		'stage' => 'success',
		'table' => $table,
		'table_base' => $table_base,
		'table_suffix' => $table_suffix,
		'table_exists_before' => $table_exists_before,
		'rows' => $inserted,
		'columns' => count($db_columns),
		'file' => $file_label,
		'tanggal_normalized' => true,
		'tgl_awal_ref' => sprintf('%02d-%02d-%04d', $tgl_ref['day'], $tgl_ref['month'], $tgl_ref['year']),
		'message' => "Import CSV berhasil.\n"
			. ($table_suffix > 0 || $table_exists_before
				? "Tabel `{$table_base}` sudah ada — dibuat tabel baru: `{$table}`" . ($table_suffix > 0 ? " (_{$table_suffix}).\n" : ".\n")
				: "1. Tabel baru dibuat: `{$table}`\n")
			. '2. Kolom disesuaikan dari header CSV (' . count($db_columns) . " kolom) + kolom `id` (INT(11) AUTO_INCREMENT)\n"
			. ($jumlah_col !== null
				? "3. Kolom `{$jumlah_col}` disimpan sebagai INT(9); format rupiah/titik/koma dihilangkan saat import\n"
				: '')
			. ($jumlah_col !== null ? '4' : '3') . ". Data ter-upload: {$inserted} baris\n"
			. ($jumlah_col !== null ? '5' : '4') . '. Kolom tanggal dinormalisasi (angka 1–2 digit = hari + bulan/tahun dari tanggal awal: '
			. sprintf('%02d-%02d-%04d', $tgl_ref['day'], $tgl_ref['month'], $tgl_ref['year']) . ")\n"
			. ($jumlah_col !== null ? '6' : '5') . '. Klik tombol Cek Data untuk melihat preview isi tabel.\n'
			. 'Silahkan lanjut compare menggunakan tabel ini.',
	);
}

function pembelian_jurnal_compare_load_manual_rows_for_bulan($CI, $table, $bulan)
{
	$valid = pembelian_jurnal_compare_validate_table($CI, $table);
	if (empty($valid['ok'])) {
		return array('ok' => false, 'message' => $valid['message']);
	}

	$range = persediaan_compare_bulan_to_date_range($bulan);
	if ($range === null) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');
	}

	$fields = $valid['fields'];
	$date_col = persediaan_compare_detect_manual_date_column($fields);
	$params = array();
	$where = '';
	$skip_date_filter = persediaan_compare_table_name_matches_bulan($table, $bulan);

	if ($date_col !== null && !$skip_date_filter) {
		$col_l = strtolower((string) $date_col);
		if ($col_l === 'tanggal_beli' || $col_l === 'tgl_po' || $col_l === 'tgl_transaksi' || $col_l === 'proses_input' || $col_l === 'tgl') {
			$where = ' WHERE `' . $date_col . '` IS NOT NULL AND `' . $date_col . "` <> '0000-00-00'"
				. ' AND DATE(`' . $date_col . '`) >= ? AND DATE(`' . $date_col . '`) <= ?';
			$params = array($range['tgl_awal'], $range['tgl_akhir']);
		} elseif ($col_l === 'tanggal') {
			$where = " WHERE COALESCE(
				STR_TO_DATE(`{$date_col}`, '%d/%m/%Y'),
				STR_TO_DATE(`{$date_col}`, '%e/%c/%Y'),
				STR_TO_DATE(`{$date_col}`, '%Y-%m-%d'),
				STR_TO_DATE(`{$date_col}`, '%d-%m-%Y')
			) BETWEEN ? AND ?";
			$params = array($range['tgl_awal'], $range['tgl_akhir']);
		} elseif ($col_l === 'bulan') {
			$bulan_num = (int) substr($bulan, 5, 2);
			$tahun_num = (int) substr($bulan, 0, 4);
			$where = ' WHERE (CAST(`' . $date_col . '` AS UNSIGNED) = ? OR TRIM(`' . $date_col . '`) = ?)';
			$params = array($bulan_num, str_pad((string) $bulan_num, 2, '0', STR_PAD_LEFT));
			$tahun_col = persediaan_compare_pick_column($fields, array('tahun'));
			if ($tahun_col !== null) {
				$where .= ' AND CAST(`' . $tahun_col . '` AS UNSIGNED) = ?';
				$params[] = $tahun_num;
			}
		} elseif ($col_l === 'tahun') {
			$tahun_num = (int) substr($bulan, 0, 4);
			$where = ' WHERE CAST(`' . $date_col . '` AS UNSIGNED) = ?';
			$params = array($tahun_num);
		}
	} elseif (!persediaan_compare_table_name_matches_bulan($table, $bulan)) {
		return array(
			'ok' => false,
			'message' => 'Tabel manual `' . $table . '` tidak sesuai bulan/tahun terpilih (' . $range['bulan_label'] . ').',
		);
	}

	$sql = 'SELECT * FROM `' . $table . '`' . $where;
	$manual_rows = count($params) > 0
		? $CI->db->query($sql, $params)->result()
		: $CI->db->query($sql)->result();

	return array(
		'ok' => true,
		'map' => $valid['map'],
		'rows' => $manual_rows,
		'range' => $range,
	);
}

function pembelian_jurnal_compare_aggregate_manual_rows($rows, $map)
{
	$agg = array();
	$incomplete = array();
	$last_spop = '';
	$last_supplier = '';

	foreach ($rows as $row) {
		$spop = trim((string) persediaan_compare_row_get($row, $map['spop']));
		$supplier = trim((string) persediaan_compare_row_get($row, $map['supplier']));
		$jumlah_raw = persediaan_compare_row_get($row, $map['jumlah']);
		$tanggal_raw = !empty($map['tanggal']) ? persediaan_compare_row_get($row, $map['tanggal']) : '';
		$tanggal_norm = pembelian_jurnal_compare_normalize_tanggal($tanggal_raw);

		if (pembelian_jurnal_compare_is_summary_row($spop, $supplier, $jumlah_raw)) {
			continue;
		}

		if ($spop === '' && $last_spop !== '') {
			$spop = $last_spop;
		}
		if ($supplier === '' && $last_supplier !== '') {
			$supplier = $last_supplier;
		}
		if ($spop !== '') {
			$last_spop = $spop;
		}
		if ($supplier !== '') {
			$last_supplier = $supplier;
		}

		if (!pembelian_jurnal_compare_is_row_complete($spop, $supplier, $jumlah_raw)) {
			$incomplete[] = pembelian_jurnal_compare_incomplete_row_to_json(
				array(
					'tanggal' => $tanggal_norm,
					'tanggal_raw' => $tanggal_raw,
					'spop' => trim((string) $spop),
					'spop_display' => trim((string) $spop),
					'supplier' => trim((string) $supplier),
					'jumlah' => $jumlah_raw,
					'sumber' => 'Manual',
				),
				pembelian_jurnal_compare_build_incomplete_keterangan($spop, $supplier, $jumlah_raw, 'manual')
			);
			continue;
		}

		$jumlah = pembelian_jurnal_compare_normalize_jumlah($jumlah_raw);
		$key = pembelian_jurnal_compare_make_pair_key($spop, $supplier);

		if (!isset($agg[$key])) {
			$agg[$key] = array(
				'tanggal' => $tanggal_norm,
				'tanggal_raw' => $tanggal_raw,
				'spop' => pembelian_jurnal_compare_normalize_spop($spop),
				'spop_display' => trim((string) $spop),
				'supplier' => trim((string) $supplier),
				'jumlah' => 0,
			);
		}

		if ($tanggal_norm !== '' && ($agg[$key]['tanggal'] === '' || $tanggal_norm < $agg[$key]['tanggal'])) {
			$agg[$key]['tanggal'] = $tanggal_norm;
			$agg[$key]['tanggal_raw'] = $tanggal_raw;
		}

		$agg[$key]['jumlah'] += $jumlah;
	}

	foreach ($agg as $key => $item) {
		$agg[$key]['jumlah'] = pembelian_jurnal_compare_normalize_jumlah($item['jumlah']);
		if ($item['spop_display'] === '') {
			$agg[$key]['spop_display'] = $item['spop'];
		}
	}

	return array(
		'groups' => $agg,
		'incomplete' => $incomplete,
	);
}

function pembelian_jurnal_compare_load_manual_groups($CI, $table, $bulan)
{
	$loaded = pembelian_jurnal_compare_load_manual_rows_for_bulan($CI, $table, $bulan);
	if (empty($loaded['ok'])) {
		return $loaded;
	}

	$rows = $loaded['rows'];
	if (is_array($rows) && count($rows) > 1) {
		usort($rows, function ($a, $b) {
			$id_a = is_object($a) && isset($a->id) ? (int) $a->id : 0;
			$id_b = is_object($b) && isset($b->id) ? (int) $b->id : 0;

			return $id_a - $id_b;
		});
	}

	$aggregated = pembelian_jurnal_compare_aggregate_manual_rows($rows, $loaded['map']);

	return array(
		'ok' => true,
		'groups' => isset($aggregated['groups']) ? $aggregated['groups'] : array(),
		'incomplete' => isset($aggregated['incomplete']) ? $aggregated['incomplete'] : array(),
		'table' => $table,
		'range' => $loaded['range'],
	);
}

function pembelian_jurnal_compare_load_online_groups($CI, $bulan)
{
	$range = persediaan_compare_bulan_to_date_range($bulan);
	if ($range === null) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');
	}

	$sql = "SELECT
		MIN(DATE(tgl_po)) AS tanggal,
		spop,
		supplier_nama,
		SUM(jumlah * harga_satuan) AS jumlah
		FROM tbl_pembelian
		WHERE tgl_po IS NOT NULL AND tgl_po <> '0000-00-00'
		AND DATE(tgl_po) >= ? AND DATE(tgl_po) <= ?
		GROUP BY spop, supplier_nama";

	$rows = $CI->db->query($sql, array($range['tgl_awal'], $range['tgl_akhir']))->result();
	$groups = array();
	$incomplete = array();

	foreach ($rows as $row) {
		$spop = isset($row->spop) ? $row->spop : '';
		$supplier = isset($row->supplier_nama) ? $row->supplier_nama : '';
		$jumlah_raw = isset($row->jumlah) ? $row->jumlah : '';

		if (!pembelian_jurnal_compare_is_row_complete($spop, $supplier, $jumlah_raw)) {
			$incomplete[] = pembelian_jurnal_compare_incomplete_row_to_json(
				array(
					'tanggal' => pembelian_jurnal_compare_normalize_tanggal($row->tanggal),
					'tanggal_raw' => $row->tanggal,
					'spop' => trim((string) $spop),
					'spop_display' => trim((string) $spop),
					'supplier' => trim((string) $supplier),
					'jumlah' => $jumlah_raw,
					'sumber' => 'Online',
				),
				pembelian_jurnal_compare_build_incomplete_keterangan($spop, $supplier, $jumlah_raw, 'online')
			);
			continue;
		}

		$key = pembelian_jurnal_compare_make_pair_key($spop, $supplier);
		$groups[$key] = array(
			'tanggal' => pembelian_jurnal_compare_normalize_tanggal($row->tanggal),
			'tanggal_raw' => $row->tanggal,
			'spop' => pembelian_jurnal_compare_normalize_spop($spop),
			'spop_display' => trim((string) $spop),
			'supplier' => trim((string) $supplier),
			'jumlah' => pembelian_jurnal_compare_normalize_jumlah($jumlah_raw),
		);
	}

	return array(
		'ok' => true,
		'groups' => $groups,
		'incomplete' => $incomplete,
		'range' => $range,
	);
}

function pembelian_jurnal_compare_row_to_json($row, $keterangan)
{
	return array(
		'tanggal' => pembelian_jurnal_compare_format_tanggal_display(isset($row['tanggal']) ? $row['tanggal'] : ''),
		'spop' => isset($row['spop_display']) ? $row['spop_display'] : (isset($row['spop']) ? $row['spop'] : ''),
		'supplier' => isset($row['supplier']) ? $row['supplier'] : '',
		'jumlah' => pembelian_jurnal_compare_format_jumlah_display(isset($row['jumlah']) ? $row['jumlah'] : 0),
		'keterangan' => $keterangan,
	);
}

function pembelian_jurnal_compare_run($CI, $bulan, $table = '')
{
	$table = trim((string) $table);
	if ($table === '') {
		return array(
			'ok' => false,
			'message' => 'Pilih tabel manual yang akan dibandingkan.',
		);
	}

	$valid = pembelian_jurnal_compare_validate_table($CI, $table);
	if (empty($valid['ok'])) {
		return $valid;
	}

	$tables = persediaan_compare_list_db_tables($CI);
	if (!in_array($table, $tables, true)) {
		return array('ok' => false, 'message' => 'Tabel tidak valid atau tidak ditemukan.');
	}

	$manual = pembelian_jurnal_compare_load_manual_groups($CI, $table, $bulan);
	if (empty($manual['ok'])) {
		return $manual;
	}

	$online = pembelian_jurnal_compare_load_online_groups($CI, $bulan);
	if (empty($online['ok'])) {
		return $online;
	}

	$hanya_manual = array();
	$hanya_online = array();
	$cocok = array();
	$tidak_lengkap_manual = array();
	$tidak_lengkap_online = array();
	$online_handled = array();
	$manual_handled = array();

	if (!empty($manual['incomplete']) && is_array($manual['incomplete'])) {
		$tidak_lengkap_manual = $manual['incomplete'];
	}
	if (!empty($online['incomplete']) && is_array($online['incomplete'])) {
		$tidak_lengkap_online = $online['incomplete'];
	}

	foreach ($manual['groups'] as $m_key => $manual_row) {
		$online_key = pembelian_jurnal_compare_find_group_key(
			$online['groups'],
			isset($manual_row['spop']) ? $manual_row['spop'] : '',
			isset($manual_row['supplier']) ? $manual_row['supplier'] : ''
		);

		if ($online_key === null) {
			continue;
		}

		$online_row = $online['groups'][$online_key];
		$online_handled[$online_key] = true;
		$manual_handled[$m_key] = true;

		if (pembelian_jurnal_compare_rows_match($manual_row, $online_row)) {
			$cocok[] = pembelian_jurnal_compare_row_to_json(
				$manual_row,
				pembelian_jurnal_compare_build_keterangan($manual_row, $online_row, $manual['groups'], $online['groups'])
			);
		} else {
			$hanya_manual[] = pembelian_jurnal_compare_row_to_json(
				$manual_row,
				pembelian_jurnal_compare_build_keterangan($manual_row, $online_row, $manual['groups'], $online['groups'])
			);
			$hanya_online[] = pembelian_jurnal_compare_row_to_json(
				$online_row,
				pembelian_jurnal_compare_build_keterangan($manual_row, $online_row, $manual['groups'], $online['groups'])
			);
		}
	}

	foreach ($online['groups'] as $o_key => $online_row) {
		if (isset($online_handled[$o_key])) {
			continue;
		}

		$matched_manual_key = null;
		foreach ($manual['groups'] as $m_key => $manual_row) {
			if (isset($manual_handled[$m_key])) {
				continue;
			}
			if (pembelian_jurnal_compare_rows_match($manual_row, $online_row)) {
				$matched_manual_key = $m_key;
				break;
			}
		}

		if ($matched_manual_key !== null) {
			$manual_row = $manual['groups'][$matched_manual_key];
			$online_handled[$o_key] = true;
			$manual_handled[$matched_manual_key] = true;
			$cocok[] = pembelian_jurnal_compare_row_to_json(
				$manual_row,
				pembelian_jurnal_compare_build_keterangan($manual_row, $online_row, $manual['groups'], $online['groups'])
			);
			continue;
		}

		$hanya_online[] = pembelian_jurnal_compare_row_to_json(
			$online_row,
			pembelian_jurnal_compare_build_keterangan(null, $online_row, $manual['groups'], $online['groups'])
		);
	}

	foreach ($manual['groups'] as $m_key => $manual_row) {
		if (isset($manual_handled[$m_key])) {
			continue;
		}
		$hanya_manual[] = pembelian_jurnal_compare_row_to_json(
			$manual_row,
			pembelian_jurnal_compare_build_keterangan($manual_row, null, $manual['groups'], $online['groups'])
		);
	}

	usort($hanya_manual, 'pembelian_jurnal_compare_sort_json_rows');
	usort($hanya_online, 'pembelian_jurnal_compare_sort_json_rows');
	usort($cocok, 'pembelian_jurnal_compare_sort_json_rows');
	usort($tidak_lengkap_manual, 'pembelian_jurnal_compare_sort_json_rows');
	usort($tidak_lengkap_online, 'pembelian_jurnal_compare_sort_json_rows');

	return array(
		'ok' => true,
		'bulan' => $bulan,
		'bulan_label' => pembelian_jurnal_compare_bulan_label($bulan),
		'table' => $table,
		'stats' => array(
			'hanya_manual' => count($hanya_manual),
			'hanya_online' => count($hanya_online),
			'cocok' => count($cocok),
			'tidak_lengkap_manual' => count($tidak_lengkap_manual),
			'tidak_lengkap_online' => count($tidak_lengkap_online),
		),
		'hanya_manual' => $hanya_manual,
		'hanya_online' => $hanya_online,
		'cocok' => $cocok,
		'tidak_lengkap_manual' => $tidak_lengkap_manual,
		'tidak_lengkap_online' => $tidak_lengkap_online,
	);
}

function pembelian_jurnal_compare_sort_incomplete_json_rows($a, $b)
{
	$cmp = strcmp((string) (isset($a['sumber']) ? $a['sumber'] : ''), (string) (isset($b['sumber']) ? $b['sumber'] : ''));
	if ($cmp !== 0) {
		return $cmp;
	}

	return pembelian_jurnal_compare_sort_json_rows($a, $b);
}

function pembelian_jurnal_compare_sort_json_rows($a, $b)
{
	$cmp = strcmp((string) $a['spop'], (string) $b['spop']);
	if ($cmp !== 0) {
		return $cmp;
	}

	$cmp = strcmp((string) $a['supplier'], (string) $b['supplier']);
	if ($cmp !== 0) {
		return $cmp;
	}

	return strcmp((string) $a['tanggal'], (string) $b['tanggal']);
}

function pembelian_jurnal_compare_jenis_definitions()
{
	return array(
		'hanya_manual' => array(
			'title' => '1. Data Manual tidak ada di Online',
			'headers' => array('No', 'Tanggal', 'SPOP', 'Supplier', 'Jumlah', 'Keterangan'),
			'file_suffix' => '1_Manual_Tidak_Ada_Online',
		),
		'hanya_online' => array(
			'title' => '3. Data Online tidak ada di Manual',
			'headers' => array('No', 'Tanggal', 'SPOP', 'Supplier', 'Jumlah', 'Keterangan'),
			'file_suffix' => '3_Online_Tidak_Ada_Manual',
		),
		'cocok' => array(
			'title' => '2. Data Cocok (Manual & Online)',
			'headers' => array('No', 'Tanggal', 'SPOP', 'Supplier', 'Jumlah', 'Keterangan'),
			'file_suffix' => '2_Cocok_Manual_Online',
		),
		'hanya_manual_4' => array(
			'title' => '4. Data Manual tidak ada di Online',
			'headers' => array('No', 'Tanggal', 'SPOP', 'Supplier', 'Jumlah', 'Keterangan'),
			'file_suffix' => '4_Manual_Tidak_Ada_Online',
		),
		'tidak_lengkap_manual' => array(
			'title' => '5. Data Tidak Lengkap (data manual)',
			'headers' => array('No', 'Tanggal', 'SPOP', 'Supplier', 'Jumlah', 'Keterangan'),
			'file_suffix' => '5_Data_Tidak_Lengkap_Manual',
		),
		'tidak_lengkap_online' => array(
			'title' => '6. Data Tidak Lengkap (di data online)',
			'headers' => array('No', 'Tanggal', 'SPOP', 'Supplier', 'Jumlah', 'Keterangan'),
			'file_suffix' => '6_Data_Tidak_Lengkap_Online',
		),
	);
}

function pembelian_jurnal_compare_get_items_by_jenis($result, $jenis)
{
	if ($jenis === 'hanya_manual' || $jenis === 'hanya_manual_4') {
		return isset($result['hanya_manual']) ? $result['hanya_manual'] : array();
	}
	if ($jenis === 'hanya_online') {
		return isset($result['hanya_online']) ? $result['hanya_online'] : array();
	}
	if ($jenis === 'cocok') {
		return isset($result['cocok']) ? $result['cocok'] : array();
	}
	if ($jenis === 'tidak_lengkap_manual') {
		return isset($result['tidak_lengkap_manual']) ? $result['tidak_lengkap_manual'] : array();
	}
	if ($jenis === 'tidak_lengkap_online') {
		return isset($result['tidak_lengkap_online']) ? $result['tidak_lengkap_online'] : array();
	}

	return array();
}

function pembelian_jurnal_compare_item_to_row_cells($jenis, $item, $no)
{
	return array(
		$no,
		isset($item['tanggal']) ? $item['tanggal'] : '',
		isset($item['spop']) ? $item['spop'] : '',
		isset($item['supplier']) ? $item['supplier'] : '',
		isset($item['jumlah']) ? $item['jumlah'] : '',
		isset($item['keterangan']) ? $item['keterangan'] : '',
	);
}

function pembelian_jurnal_compare_export_excel_output($CI, $bulan, $jenis, $table = '')
{
	$CI->load->helper(array('exportexcel', 'persediaan_display'));

	$defs = pembelian_jurnal_compare_jenis_definitions();
	if (!isset($defs[$jenis])) {
		xlsBOF();
		xlsWriteLabel(0, 0, 'Jenis export compare tidak valid.');
		xlsEOF();
		return;
	}

	$result = pembelian_jurnal_compare_run($CI, $bulan, $table);
	if (empty($result['ok'])) {
		xlsBOF();
		xlsWriteLabel(0, 0, isset($result['message']) ? $result['message'] : 'Compare gagal.');
		xlsEOF();
		return;
	}

	$def = $defs[$jenis];
	$items = pembelian_jurnal_compare_get_items_by_jenis($result, $jenis);

	xlsBOF();
	xlsWriteLabelBold14(0, 0, $def['title'] . ' — Bulan ' . $result['bulan_label']);
	xlsWriteLabel(1, 0, 'Tabel manual: ' . $result['table']);
	xlsWriteLabel(2, 0, 'Dicetak: ' . date('d/m/Y H:i:s'));

	$col = 0;
	foreach ($def['headers'] as $header) {
		xlsWriteLabel(4, $col++, $header);
	}

	$row = 5;
	$no = 0;
	foreach ($items as $item) {
		$no++;
		$cells = pembelian_jurnal_compare_item_to_row_cells($jenis, $item, $no);
		$col = 0;
		foreach ($cells as $cell) {
			xlsWriteLabel($row, $col++, $cell);
		}
		$row++;
	}

	xlsEOF();
}

function pembelian_jurnal_compare_excel_all_group_definitions()
{
	return array(
		array('title' => 'DATA MANUAL (TABEL CSV)', 'col_start' => 0, 'col_end' => 4),
		array('title' => '1. MANUAL TIDAK ADA DI ONLINE', 'col_start' => 6, 'col_end' => 11),
		array('title' => '2. DATA COCOK (MANUAL & ONLINE)', 'col_start' => 13, 'col_end' => 18),
		array('title' => '3. ONLINE TIDAK ADA DI MANUAL', 'col_start' => 20, 'col_end' => 25),
		array('title' => '4. MANUAL TIDAK ADA DI ONLINE', 'col_start' => 27, 'col_end' => 32),
		array('title' => '5. DATA TIDAK LENGKAP (MANUAL)', 'col_start' => 34, 'col_end' => 39),
		array('title' => '6. DATA TIDAK LENGKAP (ONLINE)', 'col_start' => 41, 'col_end' => 46),
	);
}

function pembelian_jurnal_compare_excel_all_headers()
{
	return array(
		'No', 'Tanggal', 'SPOP', 'Supplier', 'Jumlah', '',
		'No', 'Tanggal', 'SPOP', 'Supplier', 'Jumlah', 'Keterangan', '',
		'No', 'Tanggal', 'SPOP', 'Supplier', 'Jumlah', 'Keterangan', '',
		'No', 'Tanggal', 'SPOP', 'Supplier', 'Jumlah', 'Keterangan', '',
		'No', 'Tanggal', 'SPOP', 'Supplier', 'Jumlah', 'Keterangan', '',
		'No', 'Tanggal', 'SPOP', 'Supplier', 'Jumlah', 'Keterangan', '',
		'No', 'Tanggal', 'SPOP', 'Supplier', 'Jumlah', 'Keterangan',
	);
}

function pembelian_jurnal_compare_excel_all_total_cols()
{
	return 47;
}

function pembelian_jurnal_compare_excel_all_empty_row()
{
	return array_fill(0, pembelian_jurnal_compare_excel_all_total_cols(), '');
}

function pembelian_jurnal_compare_excel_all_sheet_name_from_bulan($bulan)
{
	$bulan = trim((string) $bulan);
	if (!preg_match('/^(\d{4})-(\d{2})$/', $bulan, $m)) {
		return 'Compare Pembelian';
	}

	$tahun = (int) $m[1];
	$bulan_num = (int) $m[2];
	$nama_bulan = persediaan_compare_excel_all_bulan_nama_id();
	$label_bulan = isset($nama_bulan[$bulan_num]) ? $nama_bulan[$bulan_num] : ('Bulan ' . $bulan_num);

	return excel_sheet_safe_name('Compare ' . $label_bulan . ' ' . $tahun);
}

function pembelian_jurnal_compare_excel_all_build_manual_source_rows($CI, $table, $bulan)
{
	$loaded = pembelian_jurnal_compare_load_manual_rows_for_bulan($CI, $table, $bulan);
	if (empty($loaded['ok'])) {
		return array();
	}

	$map = $loaded['map'];
	$rows = array();
	$no = 0;

	foreach ($loaded['rows'] as $row) {
		$spop = persediaan_compare_row_get($row, $map['spop']);
		$supplier = persediaan_compare_row_get($row, $map['supplier']);
		$jumlah_raw = persediaan_compare_row_get($row, $map['jumlah']);
		if (!pembelian_jurnal_compare_is_row_complete($spop, $supplier, $jumlah_raw)) {
			continue;
		}

		$no++;
		$tanggal_raw = !empty($map['tanggal']) ? persediaan_compare_row_get($row, $map['tanggal']) : '';
		$rows[] = array(
			$no,
			pembelian_jurnal_compare_format_tanggal_display(pembelian_jurnal_compare_normalize_tanggal($tanggal_raw)),
			trim((string) $spop),
			trim((string) $supplier),
			pembelian_jurnal_compare_format_jumlah_display($jumlah_raw),
		);
	}

	return $rows;
}

function pembelian_jurnal_compare_excel_all_items_to_cells($jenis, $items)
{
	$rows = array();
	$no = 0;
	foreach ($items as $item) {
		$no++;
		$rows[] = pembelian_jurnal_compare_item_to_row_cells($jenis, $item, $no);
	}

	return $rows;
}

function pembelian_jurnal_compare_excel_all_write_merged_rows($rowNum, $sections, $groups)
{
	$maxLen = 0;
	foreach ($sections as $sectionRows) {
		$maxLen = max($maxLen, count($sectionRows));
	}

	for ($i = 0; $i < $maxLen; $i++) {
		$cells = pembelian_jurnal_compare_excel_all_empty_row();
		foreach ($sections as $colStart => $sectionRows) {
			if (!isset($sectionRows[$i])) {
				continue;
			}
			$data = $sectionRows[$i];
			foreach ($data as $offset => $value) {
				$cells[(int) $colStart + (int) $offset] = $value;
			}
		}
		persediaan_rekonsiliasi_tx_write_row_grouped($rowNum, $cells, $groups);
		$rowNum++;
	}

	return ($maxLen > 0) ? ($rowNum - 1) : ($rowNum);
}

function pembelian_jurnal_compare_export_excel_all_output($CI, $bulan, $table = '')
{
	@set_time_limit(600);
	@ini_set('memory_limit', '512M');

	$CI->load->helper(array('exportexcel', 'persediaan_display'));

	$sheet_name = pembelian_jurnal_compare_excel_all_sheet_name_from_bulan($bulan);
	$result = pembelian_jurnal_compare_run($CI, $bulan, $table);
	if (empty($result['ok'])) {
		xlsAddSheet($sheet_name);
		xlsWriteLabel(0, 0, isset($result['message']) ? $result['message'] : 'Export Excel ALL gagal.');
		xlsMultiEOF();
		return;
	}

	$groups = pembelian_jurnal_compare_excel_all_group_definitions();
	$headers = pembelian_jurnal_compare_excel_all_headers();
	$groupTitleRow = 2;
	$headerRow = 3;
	$dataStartRow = 4;

	$manualRows = pembelian_jurnal_compare_excel_all_build_manual_source_rows($CI, $table, $bulan);
	$hanyaManualRows = pembelian_jurnal_compare_excel_all_items_to_cells('hanya_manual', isset($result['hanya_manual']) ? $result['hanya_manual'] : array());
	$hanyaOnlineRows = pembelian_jurnal_compare_excel_all_items_to_cells('hanya_online', isset($result['hanya_online']) ? $result['hanya_online'] : array());
	$cocokRows = pembelian_jurnal_compare_excel_all_items_to_cells('cocok', isset($result['cocok']) ? $result['cocok'] : array());
	$tidakLengkapManualRows = pembelian_jurnal_compare_excel_all_items_to_cells('tidak_lengkap_manual', isset($result['tidak_lengkap_manual']) ? $result['tidak_lengkap_manual'] : array());
	$tidakLengkapOnlineRows = pembelian_jurnal_compare_excel_all_items_to_cells('tidak_lengkap_online', isset($result['tidak_lengkap_online']) ? $result['tidak_lengkap_online'] : array());

	$stats = isset($result['stats']) ? $result['stats'] : array();

	xlsAddSheet($sheet_name);
	xlsWriteLabelBold14(0, 0, 'Compare Jurnal Pembelian — Manual vs Online — Bulan ' . $result['bulan_label']);
	xlsWriteLabel(1, 0, 'Tabel manual: ' . $result['table']
		. ' | Dicetak: ' . date('d/m/Y H:i:s')
		. ' | Manual tidak di online: ' . (isset($stats['hanya_manual']) ? (int) $stats['hanya_manual'] : 0)
		. ' | Online tidak di manual: ' . (isset($stats['hanya_online']) ? (int) $stats['hanya_online'] : 0)
		. ' | Cocok: ' . (isset($stats['cocok']) ? (int) $stats['cocok'] : 0)
		. ' | Tidak lengkap manual: ' . (isset($stats['tidak_lengkap_manual']) ? (int) $stats['tidak_lengkap_manual'] : 0)
		. ' | Tidak lengkap online: ' . (isset($stats['tidak_lengkap_online']) ? (int) $stats['tidak_lengkap_online'] : 0));

	persediaan_rekonsiliasi_tx_write_group_titles($groupTitleRow, $groups);
	persediaan_rekonsiliasi_tx_write_headers_grouped($headerRow, $headers, $groups);

	$sections = array(
		0 => $manualRows,
		6 => $hanyaManualRows,
		13 => $cocokRows,
		20 => $hanyaOnlineRows,
		27 => $hanyaManualRows,
		34 => $tidakLengkapManualRows,
		41 => $tidakLengkapOnlineRows,
	);

	$lastRow = pembelian_jurnal_compare_excel_all_write_merged_rows($dataStartRow, $sections, $groups);
	if ($lastRow < $dataStartRow) {
		$lastRow = $headerRow;
	}

	persediaan_rekonsiliasi_tx_finalize_group_borders($groupTitleRow, $lastRow, $groups);

	xlsMultiEOF();
}

/**
 * Validasi tanggal_beli sesuai bulan target generate (YYYY-MM).
 */
function persediaan_gen_recalc_gagal_validate_tanggal_beli($tanggal_beli, $bulan)
{
	$bulan = trim((string) $bulan);
	if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
		return array('ok' => false, 'message' => 'Bulan target tidak valid.');
	}

	$tanggal_beli = trim((string) $tanggal_beli);
	if ($tanggal_beli === '') {
		return array(
			'ok' => false,
			'message' => 'tanggal_beli wajib diisi dan harus pada bulan ' . date('m/Y', strtotime($bulan . '-01')) . '.',
		);
	}

	$ts = strtotime($tanggal_beli);
	if ($ts === false) {
		return array('ok' => false, 'message' => 'Format tanggal_beli tidak valid.');
	}

	$actual_ym = date('Y-m', $ts);
	if ($actual_ym !== $bulan) {
		return array(
			'ok' => false,
			'message' => 'tanggal_beli harus pada bulan ' . date('m/Y', strtotime($bulan . '-01'))
				. ' (saat ini: ' . date('m/Y', $ts) . ').',
		);
	}

	return array(
		'ok' => true,
		'message' => 'Tanggal sesuai bulan target.',
		'normalized' => date('Y-m-01', $ts),
	);
}

/**
 * Muat baris sumber dari fase gagal generate/recalculate.
 */
function persediaan_gen_recalc_gagal_load_source_row($CI, $fase, $id_sumber, $tabel = '')
{
	$fase = strtolower(trim((string) $fase));
	$id_sumber = trim((string) $id_sumber);
	if ($id_sumber === '') {
		return null;
	}

	$parts = explode(',', $id_sumber);
	$first_id = (int) trim($parts[0]);
	if ($first_id < 1) {
		return null;
	}

	if ($fase === 'pembelian') {
		$tabel = trim((string) $tabel);
		if ($tabel === '' || !$CI->db->table_exists($tabel)) {
			$tabel = $CI->db->table_exists('tbl_pembelian') ? 'tbl_pembelian' : 'tbl_pembelian_jasa';
		}
		if (!$CI->db->table_exists($tabel)) {
			return null;
		}
		return $CI->db->get_where($tabel, array('id' => $first_id))->row();
	}

	if ($fase === 'penjualan' && $CI->db->table_exists('tbl_penjualan')) {
		return $CI->db->get_where('tbl_penjualan', array('id' => $first_id))->row();
	}

	if ($fase === 'unit_produk' && $CI->db->table_exists('sys_unit_produk')) {
		return $CI->db->get_where('sys_unit_produk', array('id' => $first_id))->row();
	}

	if ($fase === 'produksi' && $CI->db->table_exists('sys_unit_produk_bahan')) {
		return $CI->db->get_where('sys_unit_produk_bahan', array('id' => $first_id))->row();
	}

	if ($fase === 'pecah_satuan' && $CI->db->table_exists('tbl_pembelian_pecah_satuan')) {
		return $CI->db->get_where('tbl_pembelian_pecah_satuan', array('id' => $first_id))->row();
	}

	return null;
}

/**
 * Preview field persediaan untuk baris gagal generate/recalculate (belum di-insert).
 */
function persediaan_gen_recalc_gagal_preview_persediaan($CI, $bulan, array $params)
{
	$CI->load->helper('persediaan_display');

	$ctx = persediaan_gen_recalc_ctx_from_bulan($bulan);
	if (!$ctx) {
		return array('ok' => false, 'message' => 'Bulan target tidak valid.');
	}

	if (!$CI->db->table_exists('persediaan')) {
		return array('ok' => false, 'message' => 'Tabel persediaan tidak ditemukan.');
	}

	$fase = strtolower(trim((string) (isset($params['fase']) ? $params['fase'] : '')));
	$id_sumber = trim((string) (isset($params['id_sumber']) ? $params['id_sumber'] : ''));
	$tabel = trim((string) (isset($params['tabel']) ? $params['tabel'] : ''));
	$jumlah_param = isset($params['jumlah']) ? $params['jumlah'] : '';

	$map = persediaan_recalculate_build_map_persediaan_bulan($CI, $ctx['tanggal_beli']);
	$next_id = persediaan_recalculate_next_id($CI, $map);

	$fields = array();
	foreach ($CI->db->list_fields('persediaan') as $col) {
		$fields[$col] = '';
	}

	$fields['id'] = (string) $next_id;
	$fields['tanggal'] = date('Y-m-d H:i:s');
	$fields['tanggal_beli'] = $ctx['tanggal_beli'];
	$fields['sa'] = '0';
	$fields['beli'] = '0';
	$fields['penjualan'] = '0';
	$fields['total_10'] = '0';
	$fields['nilai_persediaan'] = '0';
	$fields['tuj'] = '0';

	$source = persediaan_gen_recalc_gagal_load_source_row($CI, $fase, $id_sumber, $tabel);
	$meta_jumlah = $jumlah_param;

	if ($fase === 'pembelian' && $source) {
		$nama = persediaan_recalculate_sanitize_nama_persediaan(isset($source->uraian) ? $source->uraian : '');
		$satuan = persediaan_recalculate_sanitize_satuan_persediaan(isset($source->satuan) ? $source->satuan : '');
		$hpp = persediaan_parse_angka(isset($source->harga_satuan) ? $source->harga_satuan : 0);
		$jumlah = $jumlah_param !== '' ? persediaan_parse_angka($jumlah_param) : persediaan_parse_angka(isset($source->jumlah) ? $source->jumlah : 0);
		$uuid_src = isset($source->uuid_barang) ? $source->uuid_barang : '';
		$uuid_res = persediaan_recalculate_resolve_uuid_barang_pembelian($CI, $uuid_src, $map);

		$fields['namabarang'] = $nama;
		$fields['satuan'] = $satuan;
		$fields['hpp'] = (string) (int) floor($hpp);
		$fields['beli'] = (string) (int) floor(max(0, $jumlah));
		$fields['spop'] = isset($source->spop) ? trim((string) $source->spop) : '';
		$fields['kode'] = isset($source->kode_barang) ? trim((string) $source->kode_barang) : '';
		$fields['uuid_barang'] = $uuid_res['uuid'];
		if ($tabel === 'tbl_pembelian_jasa' && $CI->db->field_exists('kategori', 'persediaan')) {
			$fields['kategori'] = 'jasa';
		}
		if (isset($source->uuid_spop) && $CI->db->field_exists('uuid_spop', 'persediaan')) {
			$fields['uuid_spop'] = trim((string) $source->uuid_spop);
		}
		$meta_jumlah = $fields['beli'];
	} elseif ($fase === 'penjualan' && $source) {
		$nama = persediaan_recalculate_sanitize_nama_persediaan(isset($source->nama_barang) ? $source->nama_barang : '');
		$satuan = persediaan_recalculate_sanitize_satuan_persediaan(isset($source->satuan) ? $source->satuan : '');
		$hpp = persediaan_parse_angka(isset($source->harga_satuan) ? $source->harga_satuan : 0);
		$uuid_src = isset($source->uuid_barang) ? $source->uuid_barang : '';
		$uuid_res = persediaan_recalculate_resolve_uuid_barang_pembelian($CI, $uuid_src, $map);

		$fields['namabarang'] = $nama;
		$fields['satuan'] = $satuan;
		$fields['hpp'] = (string) (int) floor($hpp);
		$fields['spop'] = isset($source->spop) ? trim((string) $source->spop) : '';
		$fields['kode'] = isset($source->kode_barang) ? trim((string) $source->kode_barang) : '';
		$fields['uuid_barang'] = $uuid_res['uuid'];
		if (isset($source->uuid_persediaan) && trim((string) $source->uuid_persediaan) !== '') {
			$fields['uuid_persediaan'] = trim((string) $source->uuid_persediaan);
		}
	} elseif ($fase === 'unit_produk' && $source) {
		$nama = persediaan_recalculate_sanitize_nama_persediaan(isset($source->nama_barang) ? $source->nama_barang : '');
		$satuan = persediaan_recalculate_sanitize_satuan_persediaan(isset($source->satuan) ? $source->satuan : '');
		$hpp = persediaan_parse_angka(isset($source->harga_satuan) ? $source->harga_satuan : 0);
		$jumlah = $jumlah_param !== '' ? persediaan_parse_angka($jumlah_param) : persediaan_parse_angka(isset($source->jumlah_produksi) ? $source->jumlah_produksi : 0);
		$spop = isset($source->spop) ? trim((string) $source->spop) : '';
		if ($spop === '') {
			$tgl = isset($source->tgl_transaksi) ? $source->tgl_transaksi : date('Y-m-d H:i:s');
			$spop = persediaan_generate_recalculate_generate_spop_produksi($CI, $tgl, $nama);
		}
		$uuid_src = isset($source->uuid_barang) ? $source->uuid_barang : '';
		$uuid_res = persediaan_recalculate_resolve_uuid_barang_pembelian($CI, $uuid_src, $map);
		$jumlah_int = (int) floor(max(0, $jumlah));
		$nilai = (int) floor($jumlah_int * $hpp);

		$fields['namabarang'] = $nama;
		$fields['satuan'] = $satuan;
		$fields['hpp'] = (string) (int) floor($hpp);
		$fields['spop'] = $spop;
		$fields['kode'] = isset($source->kode_barang) ? trim((string) $source->kode_barang) : '';
		$fields['uuid_barang'] = $uuid_res['uuid'];
		$fields['sa'] = (string) $jumlah_int;
		$fields['total_10'] = (string) $jumlah_int;
		$fields['nilai_persediaan'] = (string) $nilai;
		$fields['tuj'] = (string) $jumlah_int;
		$meta_jumlah = $fields['sa'];
	} else {
		$fields['namabarang'] = isset($params['namabarang']) ? trim((string) $params['namabarang']) : '';
		$fields['satuan'] = isset($params['satuan']) ? trim((string) $params['satuan']) : '';
		$fields['hpp'] = isset($params['hpp']) ? trim((string) $params['hpp']) : '';
		$fields['spop'] = isset($params['spop']) ? trim((string) $params['spop']) : '';
		if ($jumlah_param !== '') {
			$fields['beli'] = (string) (int) floor(max(0, persediaan_parse_angka($jumlah_param)));
		}
	}

	if (function_exists('persediaan_generate_distribusi_nol_fields')) {
		$fields = array_merge($fields, persediaan_generate_distribusi_nol_fields($CI));
	}
	if ($CI->db->field_exists('pecah_satuan', 'persediaan') && $fields['pecah_satuan'] === '') {
		$fields['pecah_satuan'] = '0';
	}
	if ($CI->db->field_exists('bahan_produksi', 'persediaan') && $fields['bahan_produksi'] === '') {
		$fields['bahan_produksi'] = '0';
	}

	$rows = array();
	$no = 0;
	foreach ($fields as $name => $value) {
		$rows[] = array(
			'no' => ++$no,
			'field' => $name,
			'nilai' => $value === null ? '' : (string) $value,
		);
	}

	$tanggal_check = persediaan_gen_recalc_gagal_validate_tanggal_beli($fields['tanggal_beli'], $bulan);

	return array(
		'ok' => true,
		'message' => 'Preview data persediaan siap.',
		'bulan' => $bulan,
		'bulan_label' => date('m/Y', strtotime($ctx['tanggal_beli'])),
		'fields' => $fields,
		'rows' => $rows,
		'tanggal_check' => $tanggal_check,
		'meta' => array(
			'fase' => $fase,
			'aksi' => isset($params['aksi']) ? $params['aksi'] : '',
			'tabel' => $tabel,
			'id_sumber' => $id_sumber,
			'id_target' => isset($params['id_target']) ? $params['id_target'] : '',
			'jumlah' => $meta_jumlah,
			'keterangan_gagal' => isset($params['keterangan']) ? $params['keterangan'] : '',
		),
	);
}

/**
 * Simpan manual record persediaan dari baris gagal generate/recalculate.
 */
function persediaan_gen_recalc_gagal_save_persediaan_manual($CI, $bulan, array $fields, array $meta = array())
{
	$CI->load->helper('persediaan_display');

	$ctx = persediaan_gen_recalc_ctx_from_bulan($bulan);
	if (!$ctx) {
		return array('ok' => false, 'message' => 'Bulan target tidak valid.');
	}
	if (!$CI->db->table_exists('persediaan')) {
		return array('ok' => false, 'message' => 'Tabel persediaan tidak ditemukan.');
	}

	$tanggal_beli = isset($fields['tanggal_beli']) ? trim((string) $fields['tanggal_beli']) : '';
	$tanggal_check = persediaan_gen_recalc_gagal_validate_tanggal_beli($tanggal_beli, $bulan);
	if (empty($tanggal_check['ok'])) {
		return array('ok' => false, 'message' => $tanggal_check['message']);
	}
	$fields['tanggal_beli'] = $tanggal_check['normalized'];

	$nama = isset($fields['namabarang']) ? trim((string) $fields['namabarang']) : '';
	if ($nama === '') {
		return array('ok' => false, 'message' => 'Nama barang (namabarang) wajib diisi.');
	}
	$satuan = isset($fields['satuan']) ? trim((string) $fields['satuan']) : '';
	if ($satuan === '') {
		return array('ok' => false, 'message' => 'Satuan wajib diisi.');
	}

	$map = persediaan_recalculate_build_map_persediaan_bulan($CI, $ctx['tanggal_beli']);
	$hpp = isset($fields['hpp']) ? $fields['hpp'] : '0';
	$spop = isset($fields['spop']) ? $fields['spop'] : '';
	$existing = persediaan_generate_recalculate_find_by_nama_hpp_spop($map, $nama, $satuan, $hpp, $spop);
	if ($existing) {
		return array(
			'ok' => false,
			'message' => 'Sudah ada di persediaan id=' . (int) $existing->id . ' — tidak di-insert ulang.',
		);
	}

	$pers_cols = $CI->db->list_fields('persediaan');
	$insert = array();
	foreach ($pers_cols as $col) {
		if ($col === 'uuid_persediaan') {
			continue;
		}
		$insert[$col] = isset($fields[$col]) ? (string) $fields[$col] : '';
	}

	$req_id = isset($insert['id']) ? (int) $insert['id'] : 0;
	if ($req_id < 1 || $CI->db->where('id', $req_id)->count_all_results('persediaan') > 0) {
		$insert['id'] = persediaan_recalculate_next_id($CI, $map);
	}

	if (trim((string) $insert['uuid_barang']) === '') {
		$insert['uuid_barang'] = str_replace('-', '', $CI->db->query("SELECT REPLACE(UUID(),'-','') AS u")->row()->u);
	}
	if (trim((string) $insert['tanggal']) === '') {
		$insert['tanggal'] = date('Y-m-d H:i:s');
	}

	$db_debug = $CI->db->db_debug;
	$CI->db->db_debug = false;
	$CI->db->set('uuid_persediaan', "replace(uuid(),'-','')", FALSE);
	$ok = $CI->db->insert('persediaan', $insert);
	$CI->db->db_debug = $db_debug;

	if (!$ok) {
		$err = $CI->db->error();
		$pesan = isset($err['message']) ? trim((string) $err['message']) : 'Gagal insert ke tabel persediaan.';
		return array('ok' => false, 'message' => $pesan);
	}

	$id_persediaan = (int) $insert['id'];
	$sync = persediaan_gen_recalc_ensure_total_10_persediaan($CI, $id_persediaan);
	persediaan_generate_recalculate_refresh_nilai_persediaan_bulan($CI, $ctx['tanggal_beli']);

	return array(
		'ok' => true,
		'message' => 'Data berhasil disimpan ke tabel persediaan (ID ' . $id_persediaan . ').',
		'id_persediaan' => $id_persediaan,
		'total_10' => $sync ? $sync['total_10'] : (isset($insert['total_10']) ? $insert['total_10'] : ''),
		'meta' => $meta,
	);
}

/**
 * -------------------------------------------------------------------------
 * Generate & Recalculate V2 — verifikasi → kosongkan target → salin sumber
 * -------------------------------------------------------------------------
 */
function persediaan_gen_v2_new_uuid($CI)
{
	$row = $CI->db->query("SELECT REPLACE(UUID(),'-','') AS u")->row();

	return $row ? trim((string) $row->u) : '';
}

function persediaan_gen_v2_uuid_kosong($uuid)
{
	return trim((string) $uuid) === '';
}

function persediaan_gen_v2_hpp_key($nilai)
{
	if (!function_exists('persediaan_parse_angka')) {
		$CI = get_instance();
		if ($CI) {
			$CI->load->helper('persediaan_display');
		}
	}

	return (string) (int) floor(persediaan_parse_angka($nilai));
}

function persediaan_gen_v2_adjust_tanggal_ke_bulan_target($tanggal_sumber, $tanggal_beli_target)
{
	$tanggal_beli_target = trim((string) $tanggal_beli_target);
	if ($tanggal_beli_target === '') {
		return date('Y-m-d');
	}

	$ts = strtotime($tanggal_sumber);
	$ts_target = strtotime($tanggal_beli_target);
	if ($ts === false || $ts_target === false) {
		return $tanggal_beli_target;
	}

	$day = (int) date('d', $ts);
	$last_day = (int) date('t', $ts_target);
	if ($day > $last_day) {
		$day = $last_day;
	}
	if ($day < 1) {
		$day = 1;
	}

	return date('Y-m-', $ts_target) . str_pad((string) $day, 2, '0', STR_PAD_LEFT);
}

function persediaan_gen_v2_resolve_uuid_from_penjualan($CI, $uraian, $satuan, $tgl_po)
{
	if (!$CI->db->table_exists('tbl_penjualan')) {
		return '';
	}

	$uraian = trim((string) $uraian);
	$satuan = trim((string) $satuan);
	if ($uraian === '' || $satuan === '') {
		return '';
	}

	$rows = $CI->db->query(
		"SELECT TRIM(COALESCE(`uuid_persediaan`, '')) AS `uuid_persediaan`, `tgl_jual`
		FROM `tbl_penjualan`
		WHERE TRIM(COALESCE(`nama_barang`, '')) = ?
		AND TRIM(COALESCE(`satuan`, '')) = ?
		AND TRIM(COALESCE(`uuid_persediaan`, '')) <> ''
		ORDER BY `tgl_jual` ASC, `id` ASC",
		array($uraian, $satuan)
	)->result();

	if (empty($rows)) {
		return '';
	}

	$by_uuid = array();
	foreach ($rows as $row) {
		$uuid = trim((string) $row->uuid_persediaan);
		if ($uuid === '') {
			continue;
		}
		if (!isset($by_uuid[$uuid])) {
			$by_uuid[$uuid] = array();
		}
		$by_uuid[$uuid][] = $row;
	}

	if (empty($by_uuid)) {
		return '';
	}

	$uuid_keys = array_keys($by_uuid);
	if (count($uuid_keys) === 1) {
		return $uuid_keys[0];
	}

	$ts_po = strtotime((string) $tgl_po);
	if ($ts_po === false) {
		return $uuid_keys[0];
	}

	$ts_po_plus1 = strtotime('+1 day', $ts_po);
	$best_uuid = '';
	$best_score = null;

	foreach ($rows as $row) {
		$uuid = trim((string) $row->uuid_persediaan);
		if ($uuid === '') {
			continue;
		}
		$ts_jual = strtotime((string) $row->tgl_jual);
		if ($ts_jual === false) {
			continue;
		}
		if ($ts_jual >= $ts_po && $ts_jual <= $ts_po_plus1) {
			$diff = abs($ts_jual - $ts_po);
			if ($best_score === null || $diff < $best_score) {
				$best_score = $diff;
				$best_uuid = $uuid;
			}
		}
	}

	if ($best_uuid !== '') {
		return $best_uuid;
	}

	$best_before = null;
	$best_uuid_before = '';
	foreach ($rows as $row) {
		$uuid = trim((string) $row->uuid_persediaan);
		if ($uuid === '') {
			continue;
		}
		$ts_jual = strtotime((string) $row->tgl_jual);
		if ($ts_jual === false || $ts_jual >= $ts_po) {
			continue;
		}
		if ($best_before === null || $ts_jual > $best_before) {
			$best_before = $ts_jual;
			$best_uuid_before = $uuid;
		}
	}

	return $best_uuid_before;
}

function persediaan_gen_v2_resolve_uuid_from_persediaan($CI, $uraian, $satuan, $harga_satuan)
{
	$uraian = trim((string) $uraian);
	$satuan = trim((string) $satuan);
	if ($uraian === '' || $satuan === '') {
		return '';
	}

	$hpp_key = persediaan_gen_v2_hpp_key($harga_satuan);
	$row = $CI->db->query(
		"SELECT TRIM(COALESCE(`uuid_persediaan`, '')) AS `uuid_persediaan`
		FROM `persediaan`
		WHERE TRIM(COALESCE(`namabarang`, '')) = ?
		AND TRIM(COALESCE(`satuan`, '')) = ?
		AND TRIM(COALESCE(`hpp`, '0')) = ?
		AND TRIM(COALESCE(`uuid_persediaan`, '')) <> ''
		ORDER BY `id` DESC
		LIMIT 1",
		array($uraian, $satuan, $hpp_key)
	)->row();

	return $row ? trim((string) $row->uuid_persediaan) : '';
}

function persediaan_gen_v2_verifikasi_pembelian_row($CI, $tabel, $row)
{
	$id = isset($row->id) ? (int) $row->id : 0;
	$uuid_lama = isset($row->uuid_persediaan) ? trim((string) $row->uuid_persediaan) : '';
	$uraian = isset($row->uraian) ? trim((string) $row->uraian) : '';
	$satuan = isset($row->satuan) ? trim((string) $row->satuan) : '';
	$harga = isset($row->harga_satuan) ? $row->harga_satuan : '';
	$tgl_po = isset($row->tgl_po) ? $row->tgl_po : '';

	if (!persediaan_gen_v2_uuid_kosong($uuid_lama)) {
		return array(
			'fase' => 'verifikasi_pembelian',
			'tabel' => $tabel,
			'aksi' => 'SKIP',
			'id' => $id,
			'uraian' => $uraian,
			'satuan' => $satuan,
			'uuid_persediaan' => $uuid_lama,
			'keterangan' => 'uuid_persediaan sudah ada',
		);
	}

	$sumber = '';
	$uuid_baru = persediaan_gen_v2_resolve_uuid_from_penjualan($CI, $uraian, $satuan, $tgl_po);
	if ($uuid_baru !== '') {
		$sumber = 'tbl_penjualan';
	} else {
		$uuid_baru = persediaan_gen_v2_resolve_uuid_from_persediaan($CI, $uraian, $satuan, $harga);
		if ($uuid_baru !== '') {
			$sumber = 'persediaan';
		}
	}

	$aksi = 'UPDATE_UUID';
	if ($uuid_baru === '') {
		$uuid_baru = persediaan_gen_v2_new_uuid($CI);
		$aksi = 'GENERATE_UUID';
		$sumber = 'generate_baru';
	}

	if ($uuid_baru === '') {
		return array(
			'fase' => 'verifikasi_pembelian',
			'tabel' => $tabel,
			'aksi' => 'GAGAL',
			'id' => $id,
			'uraian' => $uraian,
			'satuan' => $satuan,
			'keterangan' => 'Gagal menghasilkan uuid_persediaan baru',
		);
	}

	$db_debug = $CI->db->db_debug;
	$CI->db->db_debug = false;
	$CI->db->where('id', $id);
	$ok = $CI->db->update($tabel, array('uuid_persediaan' => $uuid_baru));
	$CI->db->db_debug = $db_debug;

	if (!$ok) {
		$err = $CI->db->error();
		return array(
			'fase' => 'verifikasi_pembelian',
			'tabel' => $tabel,
			'aksi' => 'GAGAL',
			'id' => $id,
			'uraian' => $uraian,
			'satuan' => $satuan,
			'keterangan' => isset($err['message']) ? trim((string) $err['message']) : 'Gagal update uuid_persediaan',
		);
	}

	return array(
		'fase' => 'verifikasi_pembelian',
		'tabel' => $tabel,
		'aksi' => $aksi,
		'id' => $id,
		'uraian' => $uraian,
		'satuan' => $satuan,
		'uuid_persediaan' => $uuid_baru,
		'sumber' => $sumber,
		'keterangan' => ($aksi === 'GENERATE_UUID')
			? 'Generate uuid_persediaan baru (belum ada di penjualan/persediaan)'
			: 'Salin uuid_persediaan dari ' . $sumber,
	);
}

function persediaan_gen_v2_build_verifikasi_cache($CI)
{
	$cache = array(
		'uuid_persediaan' => array(),
		'uuid_pembelian' => array(),
	);

	$rows_p = $CI->db->query(
		"SELECT DISTINCT TRIM(COALESCE(`uuid_persediaan`, '')) AS u FROM `persediaan`
		WHERE TRIM(COALESCE(`uuid_persediaan`, '')) <> ''"
	)->result();
	foreach ($rows_p as $r) {
		$cache['uuid_persediaan'][trim((string) $r->u)] = true;
	}

	foreach (array('tbl_pembelian', 'tbl_pembelian_jasa') as $tbl) {
		if (!$CI->db->table_exists($tbl) || !$CI->db->field_exists('uuid_persediaan', $tbl)) {
			continue;
		}
		$rows_b = $CI->db->query(
			"SELECT DISTINCT TRIM(COALESCE(`uuid_persediaan`, '')) AS u FROM `{$tbl}`
			WHERE TRIM(COALESCE(`uuid_persediaan`, '')) <> ''"
		)->result();
		foreach ($rows_b as $r) {
			$cache['uuid_pembelian'][trim((string) $r->u)] = true;
		}
	}

	return $cache;
}

function persediaan_gen_v2_resolve_uuid_from_sys_unit_produk($CI, $nama_barang, $satuan)
{
	if (!$CI->db->table_exists('sys_unit_produk')) {
		return '';
	}

	$nama_barang = trim((string) $nama_barang);
	$satuan = trim((string) $satuan);
	if ($nama_barang === '' || $satuan === '') {
		return '';
	}

	$row = $CI->db->query(
		"SELECT TRIM(COALESCE(`uuid_persediaan`, '')) AS `uuid_persediaan`
		FROM `sys_unit_produk`
		WHERE TRIM(COALESCE(`nama_barang`, '')) = ?
		AND TRIM(COALESCE(`satuan`, '')) = ?
		AND TRIM(COALESCE(`uuid_persediaan`, '')) <> ''
		ORDER BY `id` DESC
		LIMIT 1",
		array($nama_barang, $satuan)
	)->row();

	return $row ? trim((string) $row->uuid_persediaan) : '';
}

function persediaan_gen_v2_verifikasi_penjualan_row($CI, $row, &$cache)
{
	$id = isset($row->id) ? (int) $row->id : 0;
	$uuid_lama = isset($row->uuid_persediaan) ? trim((string) $row->uuid_persediaan) : '';
	$nama = isset($row->nama_barang) ? trim((string) $row->nama_barang) : '';
	$satuan = isset($row->satuan) ? trim((string) $row->satuan) : '';

	if ($uuid_lama !== '' && !empty($cache['uuid_persediaan'][$uuid_lama])) {
		return array(
			'fase' => 'verifikasi_penjualan',
			'aksi' => 'SKIP',
			'id' => $id,
			'nama_barang' => $nama,
			'satuan' => $satuan,
			'uuid_persediaan' => $uuid_lama,
			'keterangan' => 'uuid_persediaan ada di tabel persediaan',
		);
	}

	if ($uuid_lama !== '' && !empty($cache['uuid_pembelian'][$uuid_lama])) {
		return array(
			'fase' => 'verifikasi_penjualan',
			'aksi' => 'AMAN_PEMBELIAN',
			'id' => $id,
			'nama_barang' => $nama,
			'satuan' => $satuan,
			'uuid_persediaan' => $uuid_lama,
			'keterangan' => 'uuid_persediaan ada di tbl_pembelian (aman untuk recalculate nanti)',
		);
	}

	$uuid_baru = $uuid_lama;
	$sumber = '';
	if (persediaan_gen_v2_uuid_kosong($uuid_baru)) {
		$uuid_baru = persediaan_gen_v2_resolve_uuid_from_sys_unit_produk($CI, $nama, $satuan);
		if ($uuid_baru !== '') {
			$sumber = 'sys_unit_produk';
		}
	} elseif (!empty($cache['uuid_pembelian'][$uuid_lama])) {
		return array(
			'fase' => 'verifikasi_penjualan',
			'aksi' => 'AMAN_PEMBELIAN',
			'id' => $id,
			'nama_barang' => $nama,
			'satuan' => $satuan,
			'uuid_persediaan' => $uuid_lama,
			'keterangan' => 'uuid_persediaan ada di tbl_pembelian',
		);
	} else {
		$uuid_sys = persediaan_gen_v2_resolve_uuid_from_sys_unit_produk($CI, $nama, $satuan);
		if ($uuid_sys !== '') {
			$uuid_baru = $uuid_sys;
			$sumber = 'sys_unit_produk';
		}
	}

	if ($uuid_baru !== '' && $sumber === 'sys_unit_produk') {
		$db_debug = $CI->db->db_debug;
		$CI->db->db_debug = false;
		$CI->db->where('id', $id);
		$ok = $CI->db->update('tbl_penjualan', array('uuid_persediaan' => $uuid_baru));
		$CI->db->db_debug = $db_debug;

		if ($ok) {
			return array(
				'fase' => 'verifikasi_penjualan',
				'aksi' => 'UPDATE_UUID',
				'id' => $id,
				'nama_barang' => $nama,
				'satuan' => $satuan,
				'uuid_persediaan' => $uuid_baru,
				'sumber' => $sumber,
				'keterangan' => 'Salin uuid_persediaan dari sys_unit_produk (nama+satuan)',
			);
		}
	}

	if ($uuid_baru !== '' && !persediaan_gen_v2_uuid_kosong($uuid_baru)
		&& !empty($cache['uuid_pembelian'][$uuid_baru])) {
		return array(
			'fase' => 'verifikasi_penjualan',
			'aksi' => 'AMAN_PEMBELIAN',
			'id' => $id,
			'nama_barang' => $nama,
			'satuan' => $satuan,
			'uuid_persediaan' => $uuid_baru,
			'keterangan' => 'uuid_persediaan cocok dengan tbl_pembelian',
		);
	}

	return array(
		'fase' => 'verifikasi_penjualan',
		'aksi' => 'ORPHAN',
		'id' => $id,
		'nama_barang' => $nama,
		'satuan' => $satuan,
		'uuid_persediaan' => $uuid_lama,
		'keterangan' => 'Belum ada pembelian/persediaan — kemungkinan produk baru',
	);
}

function persediaan_gen_v2_insert_copy_from_sumber($CI, $ctx, $row_sumber, &$next_id, &$map)
{
	$CI->load->helper('persediaan_display');

	$tanggal_beli_target = $ctx['tanggal_beli_target'];
	$id_sumber = isset($row_sumber->id) ? (int) $row_sumber->id : 0;
	$total_10_src = (int) floor(persediaan_generate_recalculate_sumber_total10_field_val($row_sumber));
	$nama = persediaan_recalculate_sanitize_nama_persediaan(isset($row_sumber->namabarang) ? $row_sumber->namabarang : '');
	$satuan = persediaan_recalculate_sanitize_satuan_persediaan(isset($row_sumber->satuan) ? $row_sumber->satuan : '');
	$hpp_raw = isset($row_sumber->hpp) ? trim((string) $row_sumber->hpp) : '0';
	$spop = isset($row_sumber->spop) ? trim((string) $row_sumber->spop) : '0';
	$uuid_src = isset($row_sumber->uuid_persediaan) ? trim((string) $row_sumber->uuid_persediaan) : '';
	$hpp_angka = persediaan_parse_angka($hpp_raw);
	$hpp_t = (string) (int) floor($hpp_angka);
	$sa_t = (string) $total_10_src;
	$beli_t = '0';
	$total_t = $sa_t;
	$nilai_t = (string) (int) floor($total_10_src * $hpp_angka);
	$kategori_sumber = persediaan_generate_recalculate_resolve_kategori_sumber($CI, $row_sumber);
	$tanggal_baru = persediaan_gen_v2_adjust_tanggal_ke_bulan_target(
		isset($row_sumber->tanggal) ? $row_sumber->tanggal : $row_sumber->tanggal_beli,
		$tanggal_beli_target
	);

	$new_id = (int) $next_id;
	$next_id = $new_id + 1;

	$fields_pers = $CI->db->list_fields('persediaan');
	$src_arr = (array) $row_sumber;
	$src_lookup = array();
	foreach ($src_arr as $sk => $sv) {
		$src_lookup[strtolower((string) $sk)] = $sv;
	}

	$data_insert = array();
	foreach ($fields_pers as $fp) {
		$key = strtolower((string) $fp);
		$data_insert[$fp] = isset($src_lookup[$key]) ? $src_lookup[$key] : '';
	}

	$data_insert['id'] = $new_id;
	$data_insert['tanggal_beli'] = $tanggal_beli_target;
	$data_insert['tanggal'] = $tanggal_baru;
	$data_insert['namabarang'] = $nama;
	$data_insert['satuan'] = $satuan;
	$data_insert['hpp'] = $hpp_t;
	if ($CI->db->field_exists('spop', 'persediaan')) {
		$data_insert['spop'] = $spop !== '' ? $spop : '0';
	}
	$data_insert['sa'] = $sa_t;
	$data_insert['beli'] = $beli_t;
	$data_insert['penjualan'] = '0';
	if ($CI->db->field_exists('pecah_satuan', 'persediaan')) {
		$data_insert['pecah_satuan'] = '0';
	}
	if ($CI->db->field_exists('bahan_produksi', 'persediaan')) {
		$data_insert['bahan_produksi'] = '0';
	}
	$data_insert['total_10'] = $total_t;
	if ($CI->db->field_exists('nilai_persediaan', 'persediaan')) {
		$data_insert['nilai_persediaan'] = $nilai_t;
	}
	if ($CI->db->field_exists('tuj', 'persediaan')) {
		$data_insert['tuj'] = $total_t;
	}
	if ($CI->db->field_exists('uuid_persediaan_lama', 'persediaan')) {
		$data_insert['uuid_persediaan_lama'] = 'gen_src:' . $id_sumber;
	}
	if ($kategori_sumber !== '' && $CI->db->field_exists('kategori', 'persediaan')) {
		$data_insert['kategori'] = $kategori_sumber;
	}
	persediaan_row_apply_asal_generate_flag($data_insert, true, $CI);

	if ($uuid_src !== '' && $CI->db->field_exists('uuid_persediaan', 'persediaan')) {
		$data_insert['uuid_persediaan'] = $uuid_src;
		$insert_ok = $CI->db->insert('persediaan', $data_insert);
	} else {
		if ($CI->db->field_exists('uuid_persediaan', 'persediaan')) {
			unset($data_insert['uuid_persediaan']);
		}
		$CI->db->set('uuid_persediaan', "replace(uuid(),'-','')", FALSE);
		$insert_ok = $CI->db->insert('persediaan', $data_insert);
	}

	if (!$insert_ok) {
		$err = $CI->db->error();
		throw new Exception(isset($err['message']) ? $err['message'] : 'Gagal insert persediaan (salin dari bulan sumber).');
	}

	$row_baru = $CI->db->where('id', $new_id)->limit(1)->get('persediaan')->row();
	if ($row_baru && is_array($map)) {
		persediaan_recalculate_map_add_row($map, $row_baru);
	}

	return array(
		'fase' => 'copy_persediaan',
		'aksi' => 'INSERT',
		'id' => $new_id,
		'id_sumber' => $id_sumber,
		'uuid_persediaan' => $row_baru ? $row_baru->uuid_persediaan : $uuid_src,
		'namabarang' => $nama,
		'satuan' => $satuan,
		'hpp' => $hpp_t,
		'spop' => $spop,
		'sa' => $sa_t,
		'tuj' => $total_t,
		'beli' => $beli_t,
		'total_10' => $total_t,
		'nilai_persediaan' => $nilai_t,
		'tanggal' => $tanggal_baru,
		'keterangan' => 'Salin dari bulan sumber (ID ' . $id_sumber . ', sa/tuj/total_10=' . $total_t . ')',
	);
}

function persediaan_gen_v2_count_tabel($CI, $tabel)
{
	if (!$CI->db->table_exists($tabel)) {
		return 0;
	}

	$row = $CI->db->query("SELECT COUNT(*) AS jml FROM `{$tabel}`")->row();

	return $row ? (int) $row->jml : 0;
}

function persediaan_gen_v2_finish($CI, $bulan, $ctx, &$state, $state_key, $batch_items)
{
	$generated_at = date('Y-m-d H:i:s');
	$stats = isset($state['stats']) && is_array($state['stats']) ? $state['stats'] : array();

	$summary_done = array(
		'v2_ready' => 1,
		'generate_only' => 1,
		'generated_at' => $generated_at,
		'bulan_label' => $ctx['bulan_label'],
		'bulan_sumber_label' => $ctx['bulan_sumber_label'],
		'reset_target' => (int) (isset($state['reset_target']) ? $state['reset_target'] : 0),
		'target_kosong_verified' => (int) (isset($state['target_kosong_verified']) ? $state['target_kosong_verified'] : 0),
		'verifikasi_pembelian_update' => (int) (isset($stats['verifikasi_pembelian_update']) ? $stats['verifikasi_pembelian_update'] : 0),
		'verifikasi_pembelian_generate' => (int) (isset($stats['verifikasi_pembelian_generate']) ? $stats['verifikasi_pembelian_generate'] : 0),
		'verifikasi_pembelian_skip' => (int) (isset($stats['verifikasi_pembelian_skip']) ? $stats['verifikasi_pembelian_skip'] : 0),
		'verifikasi_penjualan_update' => (int) (isset($stats['verifikasi_penjualan_update']) ? $stats['verifikasi_penjualan_update'] : 0),
		'verifikasi_penjualan_orphan' => (int) (isset($stats['verifikasi_penjualan_orphan']) ? $stats['verifikasi_penjualan_orphan'] : 0),
		'verifikasi_penjualan_aman' => (int) (isset($stats['verifikasi_penjualan_aman']) ? $stats['verifikasi_penjualan_aman'] : 0),
		'verifikasi_penjualan_skip' => (int) (isset($stats['verifikasi_penjualan_skip']) ? $stats['verifikasi_penjualan_skip'] : 0),
		'generate_insert' => (int) (isset($stats['generate_insert']) ? $stats['generate_insert'] : 0),
		'total_sumber_all' => (int) (isset($ctx['total_sumber_all']) ? $ctx['total_sumber_all'] : 0),
	);

	if (persediaan_gen_recalc_table_exists($CI) && !empty($state['log_id'])) {
		persediaan_gen_recalc_batch_finish_history($CI, $state, $summary_done);
	}
	if (!empty($state['history_generate_id'])) {
		persediaan_history_generate_finish_from_batch($CI, $state, $bulan, $summary_done);
	}

	$CI->session->unset_userdata($state_key);

	$items = is_array($batch_items) ? $batch_items : array();

	return array_merge(array(
		'ok' => true,
		'phase' => 'done',
		'done' => true,
		'v2_ready' => true,
		'generate_only' => true,
		'offset_selesai' => 0,
		'total_phase' => 0,
		'stats' => $stats,
		'summary' => $summary_done,
		'progress_label' => 'Selesai — data persediaan siap',
		'refresh_persediaan' => true,
		'pesan' => 'Data persediaan sudah siap dan sesuai prosedur copy record persediaan dari bulan sebelumnya ('
			. $ctx['bulan_sumber_label'] . ' → ' . $ctx['bulan_label'] . ').',
	), $items, persediaan_generate_recalculate_batch_response_meta($state));
}

function persediaan_generate_v2_batch($CI, $bulan, $offset, $limit, $start = false)
{
	$CI->load->helper('persediaan_display');
	$limit = persediaan_generate_recalculate_normalize_batch_limit($limit);

	if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');
	}

	$ts_target = strtotime($bulan . '-01');
	if ($ts_target === false) {
		return array('ok' => false, 'message' => 'Bulan target tidak valid.');
	}

	$tanggal_beli_target = date('Y-m-01', $ts_target);
	$tanggal_beli_sumber = date('Y-m-01', strtotime('-1 month', $ts_target));
	$tgl_awal = $tanggal_beli_target;
	$tgl_akhir = date('Y-m-t', $ts_target);

	$state_key = 'gen_recalc_state_' . $bulan;
	$state_probe = $CI->session->userdata($state_key);
	$use_cached_counts = is_array($state_probe) && !empty($state_probe['ctx_counts']) && is_array($state_probe['ctx_counts']);

	if ($use_cached_counts) {
		$count_sumber_all = (int) (isset($state_probe['ctx_counts']['count_sumber_all']) ? $state_probe['ctx_counts']['count_sumber_all'] : 0);
		$count_target = (int) (isset($state_probe['ctx_counts']['count_target']) ? $state_probe['ctx_counts']['count_target'] : 0);
		$count_pembelian = (int) (isset($state_probe['ctx_counts']['count_pembelian']) ? $state_probe['ctx_counts']['count_pembelian'] : 0);
		$count_pembelian_jasa = (int) (isset($state_probe['ctx_counts']['count_pembelian_jasa']) ? $state_probe['ctx_counts']['count_pembelian_jasa'] : 0);
		$count_penjualan = (int) (isset($state_probe['ctx_counts']['count_penjualan']) ? $state_probe['ctx_counts']['count_penjualan'] : 0);
	} else {
		$row_cnt_all = $CI->db->query(
			"SELECT COUNT(*) AS jml FROM `persediaan` WHERE `tanggal_beli` = ?",
			array($tanggal_beli_sumber)
		)->row();
		$count_sumber_all = $row_cnt_all ? (int) $row_cnt_all->jml : 0;

		$row_cnt_target = $CI->db->query(
			"SELECT COUNT(*) AS jml FROM `persediaan` WHERE `tanggal_beli` = ?",
			array($tanggal_beli_target)
		)->row();
		$count_target = $row_cnt_target ? (int) $row_cnt_target->jml : 0;

		$count_pembelian = persediaan_gen_v2_count_tabel($CI, 'tbl_pembelian');
		$count_pembelian_jasa = persediaan_gen_v2_count_tabel($CI, 'tbl_pembelian_jasa');

		$count_penjualan = 0;
		if ($CI->db->table_exists('tbl_penjualan')) {
			$row_pen = $CI->db->query(
				"SELECT COUNT(*) AS jml FROM `tbl_penjualan`
				WHERE DATE(`tgl_jual`) >= ? AND DATE(`tgl_jual`) <= ?",
				array($tgl_awal, $tgl_akhir)
			)->row();
			$count_penjualan = $row_pen ? (int) $row_pen->jml : 0;
		}
	}

	if ($count_sumber_all === 0) {
		return array(
			'ok' => false,
			'no_source_data' => true,
			'message' => 'Belum ada data persediaan di bulan referensi (bulan sebelumnya: '
				. date('m/Y', strtotime($tanggal_beli_sumber)) . ', tanggal_beli = ' . $tanggal_beli_sumber . ').',
			'bulan_sumber' => date('Y-m', strtotime($tanggal_beli_sumber)),
			'tanggal_beli_sumber' => $tanggal_beli_sumber,
		);
	}

	$ctx = array(
		'ok' => true,
		'bulan' => $bulan,
		'bulan_label' => date('m/Y', $ts_target),
		'bulan_sumber_label' => date('m/Y', strtotime($tanggal_beli_sumber)),
		'tanggal_beli' => $tanggal_beli_target,
		'tanggal_beli_target' => $tanggal_beli_target,
		'tanggal_beli_sumber' => $tanggal_beli_sumber,
		'tgl_awal' => $tgl_awal,
		'tgl_akhir' => $tgl_akhir,
		'total_sumber_all' => $count_sumber_all,
	);

	$state = $CI->session->userdata($state_key);
	if ($start) {
		$CI->session->unset_userdata($state_key);
		$state = null;
	}

	if (!is_array($state)) {
		if ((int) $offset > 0 && !$start) {
			return array(
				'ok' => false,
				'message' => 'Sesi generate & recalculate tidak ditemukan. Silakan mulai ulang.',
			);
		}

		persediaan_ensure_asal_generate_column($CI);
		$tanggal_klik_generate = date('Y-m-d H:i:s');

		$row_max = $CI->db->query("SELECT MAX(`id`) AS max_id FROM `persediaan`")->row();
		$state = array(
			'phase' => 'verifikasi_pembelian',
			'verifikasi_pembelian_tabel' => 'tbl_pembelian',
			'tanggal_klik_generate' => $tanggal_klik_generate,
			'target_kosong_verified' => 0,
			'reset_target' => 0,
			'next_id' => $row_max && $row_max->max_id ? ((int) $row_max->max_id + 1) : 1,
			'ctx_counts' => array(
				'count_sumber_all' => (int) $count_sumber_all,
				'count_target' => (int) $count_target,
				'count_pembelian' => (int) $count_pembelian,
				'count_pembelian_jasa' => (int) $count_pembelian_jasa,
				'count_penjualan' => (int) $count_penjualan,
			),
			'stats' => array(
				'verifikasi_pembelian_update' => 0,
				'verifikasi_pembelian_generate' => 0,
				'verifikasi_pembelian_skip' => 0,
				'verifikasi_pembelian_gagal' => 0,
				'verifikasi_penjualan_update' => 0,
				'verifikasi_penjualan_orphan' => 0,
				'verifikasi_penjualan_skip' => 0,
				'verifikasi_penjualan_aman' => 0,
				'generate_insert' => 0,
			),
		);

		if ($start && persediaan_gen_recalc_table_exists($CI)) {
			$log_id = persediaan_gen_recalc_history_start($CI, $ctx, $count_sumber_all);
			if ($log_id > 0) {
				$state['log_id'] = $log_id;
				$state['hist_nomor'] = persediaan_gen_recalc_hist_nomor_init();
			}
		}

		if ($start) {
			$hist_gen_id = persediaan_history_generate_start($CI, $ctx, $state['tanggal_klik_generate'], $state);
			if ($hist_gen_id > 0) {
				$state['history_generate_id'] = $hist_gen_id;
			}
		}
	}

	$items_verifikasi_pembelian = array();
	$items_verifikasi_penjualan = array();
	$items_persediaan = array();

	// --- Fase 1: Verifikasi tbl_pembelian / tbl_pembelian_jasa ---
	if ($state['phase'] === 'verifikasi_pembelian') {
		$tabel = isset($state['verifikasi_pembelian_tabel']) ? $state['verifikasi_pembelian_tabel'] : 'tbl_pembelian';
		if (!$CI->db->table_exists($tabel)) {
			if ($tabel === 'tbl_pembelian') {
				$state['verifikasi_pembelian_tabel'] = 'tbl_pembelian_jasa';
				$CI->session->set_userdata($state_key, $state);
				return persediaan_generate_recalculate_phase_transition_response(
					$CI,
					$state,
					$state_key,
					$ctx,
					array('progress_label' => 'Verifikasi tbl_pembelian_jasa'),
					'verifikasi_pembelian',
					$count_pembelian_jasa,
					'Verifikasi tbl_pembelian selesai — lanjut tbl_pembelian_jasa'
				);
			}
			$state['phase'] = 'verifikasi_penjualan';
			$CI->session->set_userdata($state_key, $state);
			return persediaan_generate_recalculate_phase_transition_response(
				$CI,
				$state,
				$state_key,
				$ctx,
				array('progress_label' => 'Verifikasi tbl_penjualan (' . $ctx['bulan_label'] . ')'),
				'verifikasi_penjualan',
				$count_penjualan,
				'Verifikasi pembelian selesai — lanjut verifikasi penjualan bulan ' . $ctx['bulan_label']
			);
		}

		$total_tabel = ($tabel === 'tbl_pembelian_jasa') ? $count_pembelian_jasa : $count_pembelian;
		$list_batch = $CI->db->query(
			"SELECT `id`, `uuid_persediaan`, `uraian`, `satuan`, `harga_satuan`, `tgl_po`
			FROM `{$tabel}` ORDER BY `id` ASC LIMIT " . (int) $limit . ' OFFSET ' . (int) $offset
		)->result();

		foreach ($list_batch as $row_pem) {
			$item = persediaan_gen_v2_verifikasi_pembelian_row($CI, $tabel, $row_pem);
			$items_verifikasi_pembelian[] = $item;
			if ($item['aksi'] === 'SKIP') {
				$state['stats']['verifikasi_pembelian_skip']++;
			} elseif ($item['aksi'] === 'GENERATE_UUID') {
				$state['stats']['verifikasi_pembelian_generate']++;
				$state['stats']['verifikasi_pembelian_update']++;
			} elseif ($item['aksi'] === 'UPDATE_UUID') {
				$state['stats']['verifikasi_pembelian_update']++;
			} elseif ($item['aksi'] === 'GAGAL') {
				$state['stats']['verifikasi_pembelian_gagal']++;
			}
		}

		$offset_selesai = $offset + count($list_batch);
		$done_tabel = ($total_tabel === 0 || $offset_selesai >= $total_tabel);

		if ($done_tabel) {
			if ($tabel === 'tbl_pembelian' && $CI->db->table_exists('tbl_pembelian_jasa') && $count_pembelian_jasa > 0) {
				$state['verifikasi_pembelian_tabel'] = 'tbl_pembelian_jasa';
				$CI->session->set_userdata($state_key, $state);
				return persediaan_generate_recalculate_phase_transition_response(
					$CI,
					$state,
					$state_key,
					$ctx,
					array(
						'items_verifikasi_pembelian' => $items_verifikasi_pembelian,
						'progress_label' => 'Verifikasi tbl_pembelian_jasa',
					),
					'verifikasi_pembelian',
					$count_pembelian_jasa,
					'Verifikasi tbl_pembelian selesai — memproses tbl_pembelian_jasa'
				);
			}

			$state['phase'] = 'verifikasi_penjualan';
			if (empty($state['verifikasi_cache'])) {
				$state['verifikasi_cache'] = persediaan_gen_v2_build_verifikasi_cache($CI);
			}
			$CI->session->set_userdata($state_key, $state);
			return persediaan_generate_recalculate_phase_transition_response(
				$CI,
				$state,
				$state_key,
				$ctx,
				array(
					'items_verifikasi_pembelian' => $items_verifikasi_pembelian,
					'progress_label' => 'Verifikasi tbl_penjualan (' . $ctx['bulan_label'] . ')',
				),
				'verifikasi_penjualan',
				$count_penjualan,
				'Verifikasi pembelian selesai — memproses tbl_penjualan bulan ' . $ctx['bulan_label']
			);
		}

		$CI->session->set_userdata($state_key, $state);
		$label_tabel = ($tabel === 'tbl_pembelian_jasa') ? 'tbl_pembelian_jasa' : 'tbl_pembelian';

		return array_merge(array(
			'ok' => true,
			'phase' => 'verifikasi_pembelian',
			'done' => false,
			'offset_selesai' => $offset_selesai,
			'total_phase' => $total_tabel,
			'progress_label' => 'Verifikasi ' . $label_tabel,
			'items_verifikasi_pembelian' => $items_verifikasi_pembelian,
			'stats' => $state['stats'],
			'bulan_label' => $ctx['bulan_label'],
			'pesan' => 'Memverifikasi uuid_persediaan di ' . $label_tabel . ': ' . $offset_selesai . ' / ' . $total_tabel . ' record',
		), persediaan_generate_recalculate_batch_response_meta($state));
	}

	// --- Fase 2: Verifikasi tbl_penjualan ---
	if ($state['phase'] === 'verifikasi_penjualan') {
		if (empty($state['verifikasi_cache']) || !is_array($state['verifikasi_cache'])) {
			$state['verifikasi_cache'] = persediaan_gen_v2_build_verifikasi_cache($CI);
		}
		$cache = &$state['verifikasi_cache'];

		$list_batch = array();
		if ($CI->db->table_exists('tbl_penjualan') && $count_penjualan > 0) {
			$list_batch = $CI->db->query(
				"SELECT `id`, `uuid_persediaan`, `nama_barang`, `satuan`, `harga_satuan`, `tgl_jual`
				FROM `tbl_penjualan`
				WHERE DATE(`tgl_jual`) >= ? AND DATE(`tgl_jual`) <= ?
				ORDER BY `id` ASC LIMIT " . (int) $limit . ' OFFSET ' . (int) $offset,
				array($tgl_awal, $tgl_akhir)
			)->result();
		}

		foreach ($list_batch as $row_pen) {
			$item = persediaan_gen_v2_verifikasi_penjualan_row($CI, $row_pen, $cache);
			$items_verifikasi_penjualan[] = $item;
			if ($item['aksi'] === 'SKIP') {
				$state['stats']['verifikasi_penjualan_skip']++;
			} elseif ($item['aksi'] === 'UPDATE_UUID') {
				$state['stats']['verifikasi_penjualan_update']++;
			} elseif ($item['aksi'] === 'ORPHAN') {
				$state['stats']['verifikasi_penjualan_orphan']++;
			} elseif ($item['aksi'] === 'AMAN_PEMBELIAN') {
				$state['stats']['verifikasi_penjualan_aman']++;
			}
		}

		$offset_selesai = $offset + count($list_batch);
		$done_penjualan = ($count_penjualan === 0 || $offset_selesai >= $count_penjualan);

		if ($done_penjualan) {
			unset($state['verifikasi_cache']);
			$state['phase'] = 'reset_target';
			$CI->session->set_userdata($state_key, $state);
			return persediaan_generate_recalculate_phase_transition_response(
				$CI,
				$state,
				$state_key,
				$ctx,
				array(
					'items_verifikasi_penjualan' => $items_verifikasi_penjualan,
					'progress_label' => 'Mengosongkan persediaan bulan ' . $ctx['bulan_label'],
				),
				'reset_target',
				1,
				'Verifikasi penjualan selesai — mengosongkan persediaan bulan ' . $ctx['bulan_label']
			);
		}

		$CI->session->set_userdata($state_key, $state);

		return array_merge(array(
			'ok' => true,
			'phase' => 'verifikasi_penjualan',
			'done' => false,
			'offset_selesai' => $offset_selesai,
			'total_phase' => $count_penjualan,
			'progress_label' => 'Verifikasi tbl_penjualan (' . $ctx['bulan_label'] . ')',
			'items_verifikasi_penjualan' => $items_verifikasi_penjualan,
			'stats' => $state['stats'],
			'bulan_label' => $ctx['bulan_label'],
			'pesan' => 'Memverifikasi uuid_persediaan di tbl_penjualan (' . $ctx['bulan_label'] . '): '
				. $offset_selesai . ' / ' . $count_penjualan . ' record',
		), persediaan_generate_recalculate_batch_response_meta($state));
	}

	// --- Fase 3: Kosongkan bulan target ---
	if ($state['phase'] === 'reset_target') {
		$reset_target_info = persediaan_generate_recalculate_kosongkan_bulan_target($CI, $tanggal_beli_target);
		if (empty($reset_target_info['verified_empty'])) {
			return array(
				'ok' => false,
				'message' => 'Gagal mengosongkan data persediaan bulan target (tanggal_beli = '
					. $tanggal_beli_target . '). Masih tersisa '
					. (int) (isset($reset_target_info['remaining']) ? $reset_target_info['remaining'] : 0)
					. ' record.',
				'reset_target' => (int) (isset($reset_target_info['deleted']) ? $reset_target_info['deleted'] : 0),
			);
		}

		$state['reset_target'] = (int) (isset($reset_target_info['deleted']) ? $reset_target_info['deleted'] : 0);
		$state['target_kosong_verified'] = 1;
		$state['phase'] = 'copy_persediaan';
		$CI->session->set_userdata($state_key, $state);

		return persediaan_generate_recalculate_phase_transition_response(
			$CI,
			$state,
			$state_key,
			$ctx,
			array('progress_label' => 'Copy persediaan dari ' . $ctx['bulan_sumber_label']),
			'copy_persediaan',
			$count_sumber_all,
			'Persediaan bulan ' . $ctx['bulan_label'] . ' sudah kosong — memproses copy dari '
				. $ctx['bulan_sumber_label'] . ' (' . $count_sumber_all . ' record)'
		);
	}

	// --- Fase 4: Copy persediaan dari bulan sumber ---
	if ($state['phase'] === 'copy_persediaan') {
		$list_batch = $CI->db->query(
			"SELECT * FROM `persediaan` WHERE `tanggal_beli` = ?"
			. " ORDER BY `namabarang` ASC, `id` ASC LIMIT "
			. (int) $limit . ' OFFSET ' . (int) $offset,
			array($tanggal_beli_sumber)
		)->result();

		$map = persediaan_generate_recalculate_get_map_cached(
			$state,
			$CI,
			$tanggal_beli_target,
			((int) $offset === 0)
		);
		$next_id = (int) $state['next_id'];

		foreach ($list_batch as $row_sumber) {
			try {
				$item = persediaan_gen_v2_insert_copy_from_sumber($CI, $ctx, $row_sumber, $next_id, $map);
				$items_persediaan[] = $item;
				$state['stats']['generate_insert']++;
			} catch (Exception $ex) {
				$items_persediaan[] = array(
					'fase' => 'copy_persediaan',
					'aksi' => 'GAGAL',
					'id_sumber' => isset($row_sumber->id) ? (int) $row_sumber->id : 0,
					'namabarang' => isset($row_sumber->namabarang) ? $row_sumber->namabarang : '',
					'keterangan' => $ex->getMessage(),
				);
			}
		}

		$state['persediaan_map_cache'] = $map;
		$state['persediaan_map_cache_tgl'] = $tanggal_beli_target;
		$state['next_id'] = $next_id;
		$offset_selesai = $offset + count($list_batch);
		$done_copy = ($count_sumber_all === 0 || $offset_selesai >= $count_sumber_all);

		if ($done_copy) {
			if (!empty($items_persediaan) && !empty($state['log_id'])) {
				persediaan_gen_recalc_history_save_batch($CI, $state, $items_persediaan, array(), array(), array(), array(), array());
			}
			$CI->session->set_userdata($state_key, $state);

			return persediaan_gen_v2_finish(
				$CI,
				$bulan,
				$ctx,
				$state,
				$state_key,
				array('items_persediaan' => $items_persediaan)
			);
		}

		$CI->session->set_userdata($state_key, $state);

		return array_merge(array(
			'ok' => true,
			'phase' => 'copy_persediaan',
			'done' => false,
			'offset_selesai' => $offset_selesai,
			'total_phase' => $count_sumber_all,
			'progress_label' => 'Copy persediaan ' . $ctx['bulan_sumber_label'] . ' → ' . $ctx['bulan_label'],
			'items_persediaan' => $items_persediaan,
			'stats' => $state['stats'],
			'bulan_label' => $ctx['bulan_label'],
			'bulan_sumber_label' => $ctx['bulan_sumber_label'],
			'pesan' => 'Memproses copy pembelian ke persediaan bulan ' . $ctx['bulan_label']
				. ' dari ' . $ctx['bulan_sumber_label'] . ': ' . $offset_selesai . ' / ' . $count_sumber_all . ' record',
		), persediaan_generate_recalculate_batch_response_meta($state));
	}

	return array('ok' => false, 'message' => 'Fase proses tidak dikenali: ' . (isset($state['phase']) ? $state['phase'] : ''));
}
