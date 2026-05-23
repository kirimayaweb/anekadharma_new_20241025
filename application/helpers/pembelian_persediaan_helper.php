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
		STR_TO_DATE({$a}.tanggal, '%Y-%m-%d'),
		STR_TO_DATE({$a}.tanggal, '%d-%m-%Y')
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
 * Daftar stock persediaan untuk modal Pilih Barang penjualan (filter bulan Tgl Jual).
 */
function penjualan_get_stock_persediaan_rows($CI, $tgl_jual = null)
{
	$tgl_jual = trim((string) $tgl_jual);
	if ($tgl_jual === '') {
		return array();
	}

	$tgl = pembelian_get_filter_tanggal($CI, $tgl_jual);
	$has_kategori = $CI->db->field_exists('kategori', 'persediaan');
	$kategori_sql = $has_kategori ? 'persediaan.kategori AS kategori_barang' : "'' AS kategori_barang";
	$tgl_expr = penjualan_sql_tanggal_persediaan_expr('persediaan');
	$bukan_jasa_sql = penjualan_sql_bukan_kategori_jasa($CI, 'persediaan');

	$sql = "SELECT persediaan.id AS id,
			persediaan.tanggal_beli AS tanggal_beli,
			persediaan.tanggal AS tanggal,
			persediaan.uuid_spop AS uuid_spop,
			persediaan.spop AS spop,
			persediaan.uuid_barang AS uuid_barang,
			persediaan.uuid_persediaan AS uuid_persediaan,
			persediaan.kode_barang AS kode_barang,
			persediaan.namabarang AS nama_barang_beli,
			persediaan.total_10 AS jumlah_sediaan,
			persediaan.hpp AS harga_satuan_persediaan,
			persediaan.satuan AS satuan_persediaan,
			persediaan.pecah_satuan AS pecah_satuan_persediaan,
			persediaan.bahan_produksi AS bahan_produksi,
			persediaan.penjualan AS penjualan,
			{$kategori_sql}
		FROM persediaan
		WHERE TRIM(COALESCE(persediaan.namabarang, '')) <> ''
		AND {$tgl_expr} >= ?
		AND {$tgl_expr} <= ?
		AND {$bukan_jasa_sql}
		ORDER BY persediaan.namabarang ASC, persediaan.id ASC";

	return $CI->db->query($sql, array($tgl['awal'], $tgl['akhir']))->result();
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
			$row_persediaan = $CI->Persediaan_model->get_by_id($id_persediaan);
			if ($row_persediaan && (int) $row_persediaan->penjualan >= (int) $row_penjualan->jumlah) {
				$CI->Persediaan_model->update($id_persediaan, array(
					'penjualan' => (int) $row_persediaan->penjualan - (int) $row_penjualan->jumlah,
				));
			}
		}

		$CI->Tbl_penjualan_model->delete($row->id);
		$jumlah_hapus++;
	}

	return $jumlah_hapus;
}

/**
 * Render HTML tbody + modal nested untuk Pilih Barang penjualan.
 */
function penjualan_render_modal_pilih_barang($CI, $data)
{
	$data['fragment_part'] = 'tbody';
	$tbody = $CI->load->view('anekadharma/tbl_penjualan/_modal_pilih_barang_penjualan_fragment', $data, TRUE);
	$data['fragment_part'] = 'modals';
	$modals = $CI->load->view('anekadharma/tbl_penjualan/_modal_pilih_barang_penjualan_fragment', $data, TRUE);

	return array(
		'tbody' => $tbody,
		'modals' => $modals,
	);
}
