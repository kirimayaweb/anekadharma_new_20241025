<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function jurnal_kas_sources_labels()
{
	return array(
		'pembayaran_dari_konsumen' => 'Pembayaran dari Konsumen',
		'pembayaran_ke_supplier' => 'Pembayaran ke Supplier',
		'bea_operasional' => 'Bea Operasional',
		'pembayaran_jasa_ke_supplier' => 'Pembayaran Jasa ke Supplier',
		'uang_muka_didepan' => 'Uang Muka di Depan',
		'pendapatan_lain_lain' => 'Pendapatan Lain-lain',
		'kas_kecil' => 'Kas Kecil',
		'penjualan_accounting' => 'Penjualan Accounting',
		'pembayaran_accounting' => 'Pembayaran Penjualan Accounting',
	);
}

function jurnal_kas_sources_label($source)
{
	$labels = jurnal_kas_sources_labels();
	$source = (string) $source;
	return isset($labels[$source]) ? $labels[$source] : $source;
}

function jurnal_kas_sources_definitions()
{
	return array(
		array(
			'key' => 'pembayaran_dari_konsumen',
			'label' => 'Pembayaran dari Konsumen',
			'table' => 'tbl_penjualan_pembayaran',
			'menu_path' => 'Tbl_pembelian/pembayaran_dari_konsumen',
			'detail' => 'Pembayaran masuk dari konsumen: tgl_bayar, nominal_bayar, nmr_bukti_bayar, konsumen_nama, nama_barang.',
		),
		array(
			'key' => 'pembayaran_ke_supplier',
			'label' => 'Pembayaran ke Supplier',
			'table' => 'tbl_pembelian_pengajuan_bayar',
			'menu_path' => 'Tbl_pembelian/pembayaran_ke_supplier',
			'detail' => 'Pembayaran pembelian ke supplier (uuid_spop pembelian): tgl_pembayaran, nominal_pengajuan, supplier_nama, keterangan, nomor_bkk.',
		),
		array(
			'key' => 'bea_operasional',
			'label' => 'Bea Operasional',
			'table' => 'tbl_bea_operasional',
			'menu_path' => 'Tbl_bea_operasional',
			'detail' => 'Biaya operasional: tanggal, unit, keterangan, debet, kredit.',
		),
		array(
			'key' => 'pembayaran_jasa_ke_supplier',
			'label' => 'Pembayaran Jasa ke Supplier',
			'table' => 'tbl_pembelian_pengajuan_bayar',
			'menu_path' => 'Tbl_pembelian_jasa/pembayaran_ke_supplier',
			'detail' => 'Pembayaran pembelian jasa ke supplier (uuid_spop pembelian jasa): tgl_pembayaran, nominal_pengajuan, supplier_nama, keterangan.',
		),
		array(
			'key' => 'uang_muka_didepan',
			'label' => 'Uang Muka di Depan',
			'table' => 'tbl_uang_muka_didepan',
			'menu_path' => 'Tbl_uang_muka_didepan',
			'detail' => 'Pemasukan uang muka di depan: tgl_transaksi, dari, uraian, nominal, kode.',
		),
		array(
			'key' => 'pendapatan_lain_lain',
			'label' => 'Pendapatan Lain-lain',
			'table' => 'tbl_pendapatan_lain_lain',
			'menu_path' => 'Tbl_pendapatan_lain_lain',
			'detail' => 'Pendapatan lain-lain: tgl_transaksi, dari, uraian, nominal, kode.',
		),
		array(
			'key' => 'kas_kecil',
			'label' => 'Kas Kecil',
			'table' => 'tbl_kas_kecil',
			'menu_path' => 'Tbl_kas_kecil',
			'detail' => 'Transaksi kas kecil: tanggal, unit, keterangan, debet, kredit.',
		),
		array(
			'key' => 'penjualan_accounting',
			'label' => 'Penjualan Accounting',
			'table' => 'tbl_penjualan_accounting',
			'menu_path' => 'Tbl_penjualan_accounting',
			'detail' => 'Penjualan accounting: tgl_jual, konsumen_nama, nama_barang, debet (piutang + UMPPH PSL 22), kredit (penjualan DPP + utang PPN).',
		),
		array(
			'key' => 'pembayaran_accounting',
			'label' => 'Pembayaran Penjualan Accounting',
			'table' => 'tbl_penjualan_accounting_pembayaran',
			'menu_path' => 'Tbl_penjualan_accounting',
			'detail' => 'Pembayaran penjualan accounting: tgl_bayar, nominal_bayar, konsumen_nama, nmr_bukti_bayar.',
		),
	);
}

function jurnal_kas_sources_definition_map()
{
	$map = array();
	foreach (jurnal_kas_sources_definitions() as $def) {
		$map[$def['key']] = $def;
	}
	return $map;
}

function jurnal_kas_sources_build_summary($rows)
{
	$summary = array();
	foreach (jurnal_kas_sources_definitions() as $def) {
		$summary[] = array_merge($def, array(
			'jumlah_baris' => 0,
			'total_debet' => 0.0,
			'total_kredit' => 0.0,
		));
	}

	$index = array();
	foreach ($summary as $i => $item) {
		$index[$item['key']] = $i;
	}

	foreach ((array) $rows as $row) {
		$key = isset($row->source) ? (string) $row->source : '';
		if ($key === '' || !isset($index[$key])) {
			continue;
		}
		$i = $index[$key];
		$summary[$i]['jumlah_baris']++;
		$summary[$i]['total_debet'] += (float) (isset($row->debet) ? $row->debet : 0);
		$summary[$i]['total_kredit'] += (float) (isset($row->kredit) ? $row->kredit : 0);
	}

	return $summary;
}

function jurnal_kas_sources_build_payload($rows)
{
	$payload = array();
	foreach (jurnal_kas_sources_definitions() as $def) {
		$payload[$def['key']] = array();
	}

	foreach ((array) $rows as $row) {
		$key = isset($row->source) ? (string) $row->source : '';
		if ($key === '' || !isset($payload[$key])) {
			continue;
		}

		$debet = (float) (isset($row->debet) ? $row->debet : 0);
		$kredit = (float) (isset($row->kredit) ? $row->kredit : 0);
		$payload[$key][] = array(
			'tanggal' => !empty($row->tanggal) ? date('d-m-Y', strtotime($row->tanggal)) : '',
			'unit' => isset($row->kode_unit) ? $row->kode_unit : '',
			'bukti' => isset($row->bukti) ? $row->bukti : '',
			'keterangan' => isset($row->keterangan) ? $row->keterangan : '',
			'debet' => ($debet > 0) ? number_format($debet, 2, ',', '.') : '',
			'kredit' => ($kredit > 0) ? number_format($kredit, 2, ',', '.') : '',
			'debet_num' => $debet,
			'kredit_num' => $kredit,
		);
	}

	return $payload;
}

function jurnal_kas_sources_trim_text($value)
{
	$value = trim((string) $value);
	if ($value === '' || $value === '-') {
		return '';
	}
	return $value;
}

function jurnal_kas_sources_join_keterangan($parts)
{
	$clean = array();
	foreach ((array) $parts as $part) {
		$part = jurnal_kas_sources_trim_text($part);
		if ($part !== '') {
			$clean[] = $part;
		}
	}
	return implode(' - ', $clean);
}

function jurnal_kas_sources_make_row($tanggal, $bukti, $keterangan, $kode_unit, $debet, $kredit, $source, $source_id, $editable = false)
{
	$debet = (float) $debet;
	$kredit = (float) $kredit;
	if ($debet <= 0 && $kredit <= 0) {
		return null;
	}

	$row = new stdClass();
	$row->id = $editable ? (int) $source_id : 0;
	$row->tanggal = $tanggal;
	$row->bukti = jurnal_kas_sources_trim_text($bukti);
	$row->keterangan = jurnal_kas_sources_trim_text($keterangan);
	$row->kode_unit = jurnal_kas_sources_trim_text($kode_unit);
	$row->debet = ($debet > 0) ? $debet : 0;
	$row->kredit = ($kredit > 0) ? $kredit : 0;
	$row->source = $source;
	$row->source_id = (int) $source_id;
	$row->source_label = jurnal_kas_sources_label($source);
	$row->is_editable = (bool) $editable;
	$row->_sort_ts = strtotime((string) $tanggal);
	if ($row->_sort_ts === false) {
		$row->_sort_ts = 0;
	}

	return $row;
}

function jurnal_kas_sources_fetch_pembayaran_dari_konsumen($CI, $month, $year)
{
	if (!$CI->db->table_exists('tbl_penjualan_pembayaran')) {
		return array();
	}

	$sql = "SELECT *
		FROM `tbl_penjualan_pembayaran`
		WHERE `tgl_bayar` IS NOT NULL
			AND MONTH(`tgl_bayar`) = ?
			AND YEAR(`tgl_bayar`) = ?
			AND COALESCE(`nominal_bayar`, 0) > 0
		ORDER BY `tgl_bayar`, `id`";
	$rows = $CI->db->query($sql, array($month, $year))->result();
	$out = array();

	foreach ($rows as $row) {
		$keterangan = jurnal_kas_sources_join_keterangan(array(
			'Pembayaran dari ' . (isset($row->konsumen_nama) ? $row->konsumen_nama : ''),
			isset($row->nama_barang) ? $row->nama_barang : '',
			isset($row->nmrkirim) ? 'No. Kirim ' . $row->nmrkirim : '',
		));
		$made = jurnal_kas_sources_make_row(
			$row->tgl_bayar,
			isset($row->nmr_bukti_bayar) ? $row->nmr_bukti_bayar : 'BKM',
			$keterangan,
			isset($row->unit) ? $row->unit : '',
			$row->nominal_bayar,
			0,
			'pembayaran_dari_konsumen',
			$row->id
		);
		if ($made !== null) {
			$out[] = $made;
		}
	}

	return $out;
}

function jurnal_kas_sources_fetch_pembayaran_accounting_pembayaran($CI, $month, $year)
{
	if (!$CI->db->table_exists('tbl_penjualan_accounting_pembayaran')) {
		return array();
	}

	$sql = "SELECT *
		FROM `tbl_penjualan_accounting_pembayaran`
		WHERE `tgl_bayar` IS NOT NULL
			AND MONTH(`tgl_bayar`) = ?
			AND YEAR(`tgl_bayar`) = ?
			AND COALESCE(`nominal_bayar`, 0) > 0
		ORDER BY `tgl_bayar`, `id`";
	$rows = $CI->db->query($sql, array($month, $year))->result();
	$out = array();

	foreach ($rows as $row) {
		$keterangan = jurnal_kas_sources_join_keterangan(array(
			'Pembayaran accounting dari ' . (isset($row->konsumen_nama) ? $row->konsumen_nama : ''),
			isset($row->nama_barang) ? $row->nama_barang : '',
			isset($row->nmrkirim) ? 'No. Kirim ' . $row->nmrkirim : '',
		));
		$made = jurnal_kas_sources_make_row(
			$row->tgl_bayar,
			isset($row->nmr_bukti_bayar) ? $row->nmr_bukti_bayar : 'BKM',
			$keterangan,
			isset($row->unit) ? $row->unit : '',
			$row->nominal_bayar,
			0,
			'pembayaran_accounting',
			$row->id
		);
		if ($made !== null) {
			$out[] = $made;
		}
	}

	return $out;
}

function jurnal_kas_sources_fetch_pembayaran_ke_supplier($CI, $month, $year, $is_jasa = false)
{
	if (!$CI->db->table_exists('tbl_pembelian_pengajuan_bayar')) {
		return array();
	}

	$exists_table = $is_jasa ? 'tbl_pembelian_jasa' : 'tbl_pembelian';
	if (!$CI->db->table_exists($exists_table)) {
		return array();
	}

	$not_jasa_sql = '';
	if (!$is_jasa && $CI->db->table_exists('tbl_pembelian_jasa')) {
		$not_jasa_sql = " AND NOT EXISTS (
			SELECT 1 FROM `tbl_pembelian_jasa` `j`
			WHERE `j`.`uuid_spop` = `pb`.`uuid_spop`
		)";
	}

	$source = $is_jasa ? 'pembayaran_jasa_ke_supplier' : 'pembayaran_ke_supplier';
	$prefix = $is_jasa ? 'Pembayaran jasa ke supplier' : 'Pembayaran ke supplier';

	$sql = "SELECT `pb`.*
		FROM `tbl_pembelian_pengajuan_bayar` `pb`
		WHERE `pb`.`tgl_pembayaran` IS NOT NULL
			AND MONTH(`pb`.`tgl_pembayaran`) = ?
			AND YEAR(`pb`.`tgl_pembayaran`) = ?
			AND COALESCE(`pb`.`nominal_pengajuan`, 0) > 0
			AND EXISTS (
				SELECT 1 FROM `{$exists_table}` `src`
				WHERE `src`.`uuid_spop` = `pb`.`uuid_spop`
			)
			{$not_jasa_sql}
		ORDER BY `pb`.`tgl_pembayaran`, `pb`.`id`";

	$rows = $CI->db->query($sql, array($month, $year))->result();
	$out = array();

	foreach ($rows as $row) {
		$detail = jurnal_kas_sources_trim_text(isset($row->keterangan) ? $row->keterangan : '');
		if ($detail === '') {
			$detail = jurnal_kas_sources_join_keterangan(array(
				$prefix . ': ' . (isset($row->supplier_nama) ? $row->supplier_nama : ''),
			));
		}
		$made = jurnal_kas_sources_make_row(
			$row->tgl_pembayaran,
			isset($row->nomor_bkk) ? $row->nomor_bkk : 'BKK',
			$detail,
			isset($row->account) ? $row->account : '',
			0,
			$row->nominal_pengajuan,
			$source,
			$row->id
		);
		if ($made !== null) {
			$out[] = $made;
		}
	}

	return $out;
}

function jurnal_kas_sources_fetch_bea_operasional($CI, $month, $year)
{
	if (!$CI->db->table_exists('tbl_bea_operasional')) {
		return array();
	}

	$sql = "SELECT *
		FROM `tbl_bea_operasional`
		WHERE `tanggal` IS NOT NULL
			AND MONTH(`tanggal`) = ?
			AND YEAR(`tanggal`) = ?
			AND (COALESCE(`debet`, 0) > 0 OR COALESCE(`kredit`, 0) > 0)
		ORDER BY `tanggal`, `id`";
	$rows = $CI->db->query($sql, array($month, $year))->result();
	$out = array();

	foreach ($rows as $row) {
		$made = jurnal_kas_sources_make_row(
			$row->tanggal,
			'',
			isset($row->keterangan) ? $row->keterangan : '',
			isset($row->unit) ? $row->unit : '',
			isset($row->debet) ? $row->debet : 0,
			isset($row->kredit) ? $row->kredit : 0,
			'bea_operasional',
			$row->id
		);
		if ($made !== null) {
			$out[] = $made;
		}
	}

	return $out;
}

function jurnal_kas_sources_fetch_uang_muka_didepan($CI, $month, $year)
{
	if (!$CI->db->table_exists('tbl_uang_muka_didepan')) {
		return array();
	}

	$sql = "SELECT *
		FROM `tbl_uang_muka_didepan`
		WHERE `tgl_transaksi` IS NOT NULL
			AND MONTH(`tgl_transaksi`) = ?
			AND YEAR(`tgl_transaksi`) = ?
			AND COALESCE(`nominal`, 0) > 0
		ORDER BY `tgl_transaksi`, `id`";
	$rows = $CI->db->query($sql, array($month, $year))->result();
	$out = array();

	foreach ($rows as $row) {
		$keterangan = jurnal_kas_sources_join_keterangan(array(
			isset($row->dari) ? $row->dari : '',
			isset($row->uraian) ? $row->uraian : '',
		));
		$made = jurnal_kas_sources_make_row(
			$row->tgl_transaksi,
			isset($row->kode) ? $row->kode : '',
			$keterangan,
			isset($row->kode) ? $row->kode : '',
			$row->nominal,
			0,
			'uang_muka_didepan',
			$row->id
		);
		if ($made !== null) {
			$out[] = $made;
		}
	}

	return $out;
}

function jurnal_kas_sources_fetch_pendapatan_lain_lain($CI, $month, $year)
{
	if (!$CI->db->table_exists('tbl_pendapatan_lain_lain')) {
		return array();
	}

	$sql = "SELECT *
		FROM `tbl_pendapatan_lain_lain`
		WHERE `tgl_transaksi` IS NOT NULL
			AND MONTH(`tgl_transaksi`) = ?
			AND YEAR(`tgl_transaksi`) = ?
			AND COALESCE(`nominal`, 0) > 0
		ORDER BY `tgl_transaksi`, `id`";
	$rows = $CI->db->query($sql, array($month, $year))->result();
	$out = array();

	foreach ($rows as $row) {
		$keterangan = jurnal_kas_sources_join_keterangan(array(
			isset($row->dari) ? $row->dari : '',
			isset($row->uraian) ? $row->uraian : '',
		));
		$made = jurnal_kas_sources_make_row(
			$row->tgl_transaksi,
			isset($row->kode) ? $row->kode : '',
			$keterangan,
			isset($row->kode) ? $row->kode : '',
			$row->nominal,
			0,
			'pendapatan_lain_lain',
			$row->id
		);
		if ($made !== null) {
			$out[] = $made;
		}
	}

	return $out;
}

function jurnal_kas_sources_fetch_kas_kecil($CI, $month, $year)
{
	if (!$CI->db->table_exists('tbl_kas_kecil')) {
		return array();
	}

	$sql = "SELECT *
		FROM `tbl_kas_kecil`
		WHERE `tanggal` IS NOT NULL
			AND MONTH(`tanggal`) = ?
			AND YEAR(`tanggal`) = ?
			AND (COALESCE(`debet`, 0) > 0 OR COALESCE(`kredit`, 0) > 0)
		ORDER BY `tanggal`, `id`";
	$rows = $CI->db->query($sql, array($month, $year))->result();
	$out = array();

	foreach ($rows as $row) {
		$made = jurnal_kas_sources_make_row(
			$row->tanggal,
			'',
			isset($row->keterangan) ? $row->keterangan : '',
			isset($row->unit) ? $row->unit : '',
			isset($row->debet) ? $row->debet : 0,
			isset($row->kredit) ? $row->kredit : 0,
			'kas_kecil',
			$row->id
		);
		if ($made !== null) {
			$out[] = $made;
		}
	}

	return $out;
}

function jurnal_kas_sources_fetch_penjualan_accounting($CI, $month, $year)
{
	if (!$CI->db->table_exists('tbl_penjualan_accounting')) {
		return array();
	}

	$sql = "SELECT *
		FROM `tbl_penjualan_accounting`
		WHERE `tgl_jual` IS NOT NULL
			AND MONTH(`tgl_jual`) = ?
			AND YEAR(`tgl_jual`) = ?
		ORDER BY `tgl_jual`, `id`";
	$rows = $CI->db->query($sql, array($month, $year))->result();
	$out = array();

	foreach ($rows as $row) {
		$debet = (float) (isset($row->umpphpsl22) ? $row->umpphpsl22 : 0)
			+ (float) (isset($row->piutang) ? $row->piutang : 0);
		$kredit = (float) (isset($row->penjualandpp) ? $row->penjualandpp : 0)
			+ (float) (isset($row->utangppn) ? $row->utangppn : 0);

		$keterangan = jurnal_kas_sources_join_keterangan(array(
			isset($row->konsumen_nama) ? $row->konsumen_nama : '',
			isset($row->nmrkirim) ? 'No. Kirim ' . $row->nmrkirim : '',
			isset($row->nama_barang) ? $row->nama_barang : '',
		));

		$bukti = '';
		if (!empty($row->nmr_bukti_bayar)) {
			$bukti = $row->nmr_bukti_bayar;
		} elseif (!empty($row->nmrkirim)) {
			$bukti = $row->nmrkirim;
		}

		$made = jurnal_kas_sources_make_row(
			$row->tgl_jual,
			$bukti,
			$keterangan,
			isset($row->unit) ? $row->unit : '',
			$debet,
			$kredit,
			'penjualan_accounting',
			$row->id
		);
		if ($made !== null) {
			$out[] = $made;
		}
	}

	return $out;
}

function jurnal_kas_sources_sort_rows($rows)
{
	usort($rows, function ($a, $b) {
		if ($a->_sort_ts === $b->_sort_ts) {
			if ($a->source === $b->source) {
				return $a->source_id - $b->source_id;
			}
			return strcmp($a->source, $b->source);
		}
		if ($a->_sort_ts < $b->_sort_ts) {
			return -1;
		}
		return 1;
	});

	return $rows;
}

function jurnal_kas_fetch_merged_rows($CI, $month, $year)
{
	$month = (int) $month;
	$year = (int) $year;
	if ($month < 1 || $month > 12) {
		$month = (int) date('m');
	}
	if ($year < 2000) {
		$year = (int) date('Y');
	}

	$rows = array();
	$rows = array_merge($rows, jurnal_kas_sources_fetch_pembayaran_dari_konsumen($CI, $month, $year));
	$rows = array_merge($rows, jurnal_kas_sources_fetch_pembayaran_ke_supplier($CI, $month, $year, false));
	$rows = array_merge($rows, jurnal_kas_sources_fetch_bea_operasional($CI, $month, $year));
	$rows = array_merge($rows, jurnal_kas_sources_fetch_pembayaran_ke_supplier($CI, $month, $year, true));
	$rows = array_merge($rows, jurnal_kas_sources_fetch_uang_muka_didepan($CI, $month, $year));
	$rows = array_merge($rows, jurnal_kas_sources_fetch_pendapatan_lain_lain($CI, $month, $year));
	$rows = array_merge($rows, jurnal_kas_sources_fetch_kas_kecil($CI, $month, $year));
	$rows = array_merge($rows, jurnal_kas_sources_fetch_penjualan_accounting($CI, $month, $year));
	$rows = array_merge($rows, jurnal_kas_sources_fetch_pembayaran_accounting_pembayaran($CI, $month, $year));

	return jurnal_kas_sources_sort_rows($rows);
}
