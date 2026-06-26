<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function buku_besar_bulan_teks($angka_bulan)
{
	$angka_bulan = (int) $angka_bulan;
	$nama = array(
		1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
		5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
		9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
	);
	return isset($nama[$angka_bulan]) ? $nama[$angka_bulan] : '';
}

function buku_besar_format_rupiah($value, $always_show = false)
{
	if ($value === null || $value === '') {
		return '';
	}
	if (!$always_show && (float) $value == 0) {
		return '';
	}
	return number_format((float) $value, 2, ',', '.');
}

function buku_besar_format_tanggal_display($tanggal)
{
	$tanggal = trim((string) $tanggal);
	if ($tanggal === '' || $tanggal === '0000-00-00') {
		return '';
	}
	$ts = strtotime($tanggal);
	return $ts ? date('d-M-Y', $ts) : $tanggal;
}

function buku_besar_parse_bulan_ns($bulan_ns, $fallback_month = null, $fallback_year = null)
{
	$bulan_ns = trim((string) $bulan_ns);
	if (preg_match('/^(\d{4})-(\d{2})$/', $bulan_ns, $m)) {
		return array('year' => (int) $m[1], 'month' => (int) $m[2]);
	}
	return array(
		'year' => $fallback_year !== null ? (int) $fallback_year : (int) date('Y'),
		'month' => $fallback_month !== null ? (int) $fallback_month : (int) date('m'),
	);
}

function buku_besar_get_data_source_registry()
{
	return array(
		'pembelian' => array(
			'label' => 'Pembelian',
			'origin_table' => 'tbl_pembelian',
			'buku_besar_source' => 'pembelian',
		),
		'penjualan' => array(
			'label' => 'Penjualan',
			'origin_table' => 'tbl_penjualan',
			'buku_besar_source' => 'penjualan',
		),
		'jurnal_kas' => array(
			'label' => 'Jurnal Kas',
			'origin_table' => 'jurnal_kas',
			'buku_besar_source' => 'jurnal_kas',
		),
	);
}

function buku_besar_source_label($source_key)
{
	$source_key = trim((string) $source_key);
	if ($source_key === '') {
		return '';
	}
	$registry = buku_besar_get_data_source_registry();
	return isset($registry[$source_key]['label']) ? $registry[$source_key]['label'] : ucfirst($source_key);
}

function buku_besar_source_badge_html($source_key, $source_label = '')
{
	$source_key = trim((string) $source_key);
	if ($source_key === '') {
		return '';
	}
	$label = trim((string) $source_label);
	if ($label === '') {
		$label = buku_besar_source_label($source_key);
	}
	$badge_map = array(
		'pembelian' => array('class' => 'bb-source-pembelian', 'icon' => 'fa-shopping-cart'),
		'penjualan' => array('class' => 'bb-source-penjualan', 'icon' => 'fa-line-chart'),
		'jurnal_kas' => array('class' => 'bb-source-jurnal-kas', 'icon' => 'fa-money'),
	);
	$badge = isset($badge_map[$source_key]) ? $badge_map[$source_key] : array('class' => 'bb-source-default', 'icon' => 'fa-database');
	return '<span class="bb-source-badge ' . htmlspecialchars($badge['class'], ENT_QUOTES, 'UTF-8') . '">'
		. '<i class="fa ' . htmlspecialchars($badge['icon'], ENT_QUOTES, 'UTF-8') . '"></i> '
		. htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</span>';
}

function buku_besar_lookup_nama_akun($CI, $kode_akun, &$cache = null)
{
	$kode_akun = trim((string) $kode_akun);
	if ($kode_akun === '') {
		return '';
	}
	if (!is_array($cache)) {
		$cache = array();
	}
	if (!isset($cache[$kode_akun])) {
		$row = $CI->db->where('kode_akun', $kode_akun)->get('sys_kode_akun')->row();
		$cache[$kode_akun] = $row ? trim((string) $row->nama_akun) : '';
	}
	return $cache[$kode_akun];
}

function buku_besar_normalize_row($CI, $row, $source_key, &$nama_akun_cache = null)
{
	$kode_akun = isset($row['kode_akun']) ? trim((string) $row['kode_akun']) : '';
	if ($kode_akun === '') {
		return null;
	}

	$debet = (float) (isset($row['debet']) ? $row['debet'] : 0);
	$kredit = (float) (isset($row['kredit']) ? $row['kredit'] : 0);
	if ($debet == 0 && $kredit == 0) {
		return null;
	}

	$nama_akun = isset($row['nama_akun']) ? trim((string) $row['nama_akun']) : '';
	if ($nama_akun === '') {
		$nama_akun = buku_besar_lookup_nama_akun($CI, $kode_akun, $nama_akun_cache);
	}

	$tanggal = isset($row['tanggal']) ? $row['tanggal'] : '';
	if ($tanggal !== '' && $tanggal !== '0000-00-00') {
		$ts = strtotime((string) $tanggal);
		$tanggal = $ts ? date('Y-m-d', $ts) : trim((string) $tanggal);
	} else {
		$tanggal = '';
	}

	return array(
		'source_key' => $source_key,
		'sort_kode_akun' => $kode_akun,
		'sort_tanggal' => $tanggal,
		'sort_id' => isset($row['sort_id']) ? (int) $row['sort_id'] : 0,
		'tanggal' => $tanggal,
		'pl' => isset($row['pl']) ? trim((string) $row['pl']) : '',
		'kode' => isset($row['kode']) ? trim((string) $row['kode']) : '',
		'kode_akun' => $kode_akun,
		'nama_akun' => $nama_akun,
		'debet' => $debet,
		'kredit' => $kredit,
	);
}

function buku_besar_collect_rows_from_buku_besar_source($CI, $source_key, $buku_besar_source, $month, $year)
{
	$sql = 'SELECT `id`, `tanggal`, `pl`, `kode`, `kode_akun`, `nama_akun`, `debet`, `kredit`'
		. ' FROM `buku_besar`'
		. ' WHERE MONTH(`tanggal`) = ? AND YEAR(`tanggal`) = ? AND `source` = ?'
		. ' ORDER BY `id` ASC';

	$rows_db = $CI->db->query($sql, array((int) $month, (int) $year, $buku_besar_source))->result();
	$rows = array();
	foreach ((array) $rows_db as $row) {
		$rows[] = array(
			'sort_id' => isset($row->id) ? (int) $row->id : 0,
			'tanggal' => isset($row->tanggal) ? $row->tanggal : '',
			'pl' => isset($row->pl) ? $row->pl : '',
			'kode' => isset($row->kode) ? $row->kode : '',
			'kode_akun' => isset($row->kode_akun) ? $row->kode_akun : '',
			'nama_akun' => isset($row->nama_akun) ? $row->nama_akun : '',
			'debet' => isset($row->debet) ? $row->debet : 0,
			'kredit' => isset($row->kredit) ? $row->kredit : 0,
			'source_key' => $source_key,
		);
	}
	return $rows;
}

function buku_besar_collect_rows_from_tbl_pembelian($CI, $month, $year)
{
	$CI = get_instance();
	$month = (int) $month;
	$year = (int) $year;
	$month_start = sprintf('%04d-%02d-01 00:00:00', $year, $month);

	$CI->load->model('Tbl_pembelian_model');
	$groups = $CI->Tbl_pembelian_model->get_jurnal_pembelian2_group_rows_by_month($month, $year);
	$rows = array();
	$sort_id = 0;
	$nama_akun_cache = array();

	foreach ((array) $groups as $group) {
		$nominal = (float) (isset($group->total_pembelian) ? $group->total_pembelian : 0);
		if ($nominal == 0) {
			continue;
		}

		$kode_akun = isset($group->kode_akun) ? trim((string) $group->kode_akun) : '';
		if ($kode_akun === '') {
			$fallback = $CI->Tbl_pembelian_model->get_kode_akun_fallback_by_uuid_supplier(
				isset($group->uuid_supplier) ? $group->uuid_supplier : '',
				$month_start
			);
			if ($fallback && trim((string) $fallback->kode_akun) !== '') {
				$kode_akun = trim((string) $fallback->kode_akun);
			}
		}
		if ($kode_akun === '') {
			continue;
		}

		$uuid_spop = isset($group->uuid_spop) ? trim((string) $group->uuid_spop) : '';
		$kredit = $nominal;
		if ($uuid_spop !== '') {
			$CI->db->select('kredit');
			$CI->db->where('kode_akun', 21101);
			$CI->db->where('uuid_spop', $uuid_spop);
			$row_kredit = $CI->db->get('buku_besar')->row();
			if ($row_kredit && (float) $row_kredit->kredit > 0) {
				$kredit = (float) $row_kredit->kredit;
			}
		}

		$rows[] = array(
			'sort_id' => ++$sort_id,
			'tanggal' => isset($group->tanggal) ? $group->tanggal : '',
			'pl' => isset($group->kode_pl) ? $group->kode_pl : '',
			'kode' => isset($group->kode_bb) ? $group->kode_bb : '',
			'kode_akun' => $kode_akun,
			'nama_akun' => buku_besar_lookup_nama_akun($CI, $kode_akun, $nama_akun_cache),
			'debet' => $nominal,
			'kredit' => 0,
			'source_key' => 'pembelian',
		);

		$rows[] = array(
			'sort_id' => ++$sort_id,
			'tanggal' => isset($group->tanggal) ? $group->tanggal : '',
			'pl' => isset($group->kode_pl) ? $group->kode_pl : '',
			'kode' => isset($group->kode_bb) ? $group->kode_bb : '',
			'kode_akun' => '21101',
			'nama_akun' => buku_besar_lookup_nama_akun($CI, '21101', $nama_akun_cache),
			'debet' => 0,
			'kredit' => $kredit,
			'source_key' => 'pembelian',
		);
	}

	return $rows;
}

function buku_besar_collect_rows_from_tbl_penjualan($CI, $month, $year)
{
	$month = (int) $month;
	$year = (int) $year;
	$month_start = sprintf('%04d-%02d-01 00:00:00', $year, $month);
	$month_end = date('Y-m-t 23:59:59', strtotime($month_start));

	$sql = 'SELECT `id`, `tgl_jual` AS `tanggal`, `kode_pl` AS `pl`, `kode_bb` AS `kode`,'
		. ' TRIM(`kode_akun`) AS `kode_akun`, `nmrkirim`,'
		. ' (`jumlah` * `harga_satuan`) AS `nominal`'
		. ' FROM `tbl_penjualan`'
		. ' WHERE `tgl_jual` >= ? AND `tgl_jual` <= ?'
		. ' AND TRIM(COALESCE(`kode_akun`, \'\')) <> \'\''
		. ' ORDER BY `tgl_jual` ASC, `id` ASC';

	$rows_db = $CI->db->query($sql, array($month_start, $month_end))->result();
	$rows = array();
	$sort_id = 0;
	$nmrkirim_groups = array();

	foreach ((array) $rows_db as $row) {
		$nominal = (float) (isset($row->nominal) ? $row->nominal : 0);
		if ($nominal == 0) {
			continue;
		}

		$kode_akun = isset($row->kode_akun) ? trim((string) $row->kode_akun) : '';
		$nmrkirim = isset($row->nmrkirim) ? trim((string) $row->nmrkirim) : '';
		$x_penjualandpp_percentage = 90.090090;
		$penjualandpp = ($nominal * $x_penjualandpp_percentage) / 100;

		$rows[] = array(
			'sort_id' => ++$sort_id,
			'tanggal' => isset($row->tanggal) ? $row->tanggal : '',
			'pl' => isset($row->pl) ? $row->pl : '',
			'kode' => isset($row->kode) ? $row->kode : '',
			'kode_akun' => $kode_akun,
			'nama_akun' => '',
			'debet' => 0,
			'kredit' => $penjualandpp,
			'source_key' => 'penjualan',
		);

		if ($nmrkirim === '') {
			continue;
		}
		if (!isset($nmrkirim_groups[$nmrkirim])) {
			$nmrkirim_groups[$nmrkirim] = array(
				'tanggal' => isset($row->tanggal) ? $row->tanggal : '',
				'pl' => isset($row->pl) ? $row->pl : '',
				'kode' => isset($row->kode) ? $row->kode : '',
				'total_nominal' => 0,
			);
		}
		$nmrkirim_groups[$nmrkirim]['total_nominal'] += $nominal;
	}

	$x_piutang_percentage = 11.261261;
	$x_utangppn_percentage = 9.909910;
	foreach ($nmrkirim_groups as $group) {
		$nominal = (float) $group['total_nominal'];
		if ($nominal == 0) {
			continue;
		}
		$piutang = $nominal - (($nominal * $x_piutang_percentage) / 100);
		$utangppn = ($nominal * $x_utangppn_percentage) / 100;

		if ($piutang > 0) {
			$rows[] = array(
				'sort_id' => ++$sort_id,
				'tanggal' => $group['tanggal'],
				'pl' => $group['pl'],
				'kode' => $group['kode'],
				'kode_akun' => '11301',
				'nama_akun' => '',
				'debet' => $piutang,
				'kredit' => 0,
				'source_key' => 'penjualan',
			);
		}
		if ($utangppn > 0) {
			$rows[] = array(
				'sort_id' => ++$sort_id,
				'tanggal' => $group['tanggal'],
				'pl' => $group['pl'],
				'kode' => $group['kode'],
				'kode_akun' => '21201',
				'nama_akun' => '',
				'debet' => 0,
				'kredit' => $utangppn,
				'source_key' => 'penjualan',
			);
		}
	}

	return $rows;
}

function buku_besar_collect_rows_from_jurnal_kas($CI, $month, $year)
{
	$month = (int) $month;
	$year = (int) $year;
	$kas_kode = '11101';

	$sql = 'SELECT `id`, `tanggal`, `bukti`, `pl`, `kode_unit`, `kode_rekening`, `kode_akun`, `debet`, `kredit`'
		. ' FROM `jurnal_kas`'
		. ' WHERE MONTH(`tanggal`) = ? AND YEAR(`tanggal`) = ?'
		. ' AND TRIM(COALESCE(`kode_akun`, \'\')) <> \'\''
		. ' AND (`debet` > 0 OR `kredit` > 0)'
		. ' ORDER BY `tanggal` ASC, `id` ASC';

	$rows_db = $CI->db->query($sql, array($month, $year))->result();
	$rows = array();
	$sort_id = 0;
	$nama_akun_cache = array();

	foreach ((array) $rows_db as $row) {
		$kode_akun = trim((string) (isset($row->kode_akun) ? $row->kode_akun : ''));
		if ($kode_akun === '') {
			continue;
		}

		$debet = (float) (isset($row->debet) ? $row->debet : 0);
		$kredit = (float) (isset($row->kredit) ? $row->kredit : 0);
		if ($debet <= 0 && $kredit <= 0) {
			continue;
		}

		$tanggal = isset($row->tanggal) ? $row->tanggal : '';
		$pl = isset($row->pl) ? trim((string) $row->pl) : '';
		if ($pl === '' && isset($row->kode_unit)) {
			$pl = trim((string) $row->kode_unit);
		}

		$kode = '';
		if (isset($row->kode_rekening) && trim((string) $row->kode_rekening) !== '') {
			$kode = trim((string) $row->kode_rekening);
		} elseif (isset($row->kode_unit) && trim((string) $row->kode_unit) !== '') {
			$kode = trim((string) $row->kode_unit);
		} elseif (isset($row->bukti)) {
			$kode = trim((string) $row->bukti);
		}

		$make_row = function ($kode_akun_row, $debet_val, $kredit_val) use (&$rows, &$sort_id, $tanggal, $pl, $kode, $CI, &$nama_akun_cache) {
			$rows[] = array(
				'sort_id' => ++$sort_id,
				'tanggal' => $tanggal,
				'pl' => $pl,
				'kode' => $kode,
				'kode_akun' => $kode_akun_row,
				'nama_akun' => buku_besar_lookup_nama_akun($CI, $kode_akun_row, $nama_akun_cache),
				'debet' => (float) $debet_val,
				'kredit' => (float) $kredit_val,
				'source_key' => 'jurnal_kas',
			);
		};

		if ($debet > 0) {
			$make_row($kas_kode, $debet, 0);
			$make_row($kode_akun, 0, $debet);
		}
		if ($kredit > 0) {
			$make_row($kode_akun, $kredit, 0);
			$make_row($kas_kode, 0, $kredit);
		}
	}

	return $rows;
}

function buku_besar_collect_rows_from_source($CI, $source_key, $source_def, $month, $year)
{
	$buku_besar_source = isset($source_def['buku_besar_source']) ? $source_def['buku_besar_source'] : $source_key;
	$rows = buku_besar_collect_rows_from_buku_besar_source($CI, $source_key, $buku_besar_source, $month, $year);
	if (!empty($rows)) {
		return $rows;
	}

	$origin_table = isset($source_def['origin_table']) ? $source_def['origin_table'] : '';
	if ($origin_table === 'tbl_pembelian') {
		return buku_besar_collect_rows_from_tbl_pembelian($CI, $month, $year);
	}
	if ($origin_table === 'tbl_penjualan') {
		return buku_besar_collect_rows_from_tbl_penjualan($CI, $month, $year);
	}
	if ($origin_table === 'jurnal_kas') {
		$rows_bb = buku_besar_collect_rows_from_buku_besar_source($CI, $source_key, $buku_besar_source, $month, $year);
		if (!empty($rows_bb)) {
			return $rows_bb;
		}
		return buku_besar_collect_rows_from_jurnal_kas($CI, $month, $year);
	}

	return array();
}

function buku_besar_merge_source_rows($CI, $month, $year, $kode_akun_filter = '')
{
	$month = (int) $month;
	$year = (int) $year;
	$kode_akun_filter = trim((string) $kode_akun_filter);
	$merged = array();
	$nama_akun_cache = array();

	foreach (buku_besar_get_data_source_registry() as $source_key => $source_def) {
		$raw_rows = buku_besar_collect_rows_from_source($CI, $source_key, $source_def, $month, $year);
		foreach ((array) $raw_rows as $raw_row) {
			$normalized = buku_besar_normalize_row($CI, $raw_row, $source_key, $nama_akun_cache);
			if ($normalized === null) {
				continue;
			}
			if ($kode_akun_filter !== '' && $normalized['kode_akun'] !== $kode_akun_filter) {
				continue;
			}
			$merged[] = $normalized;
		}
	}

	usort($merged, function ($a, $b) {
		$cmp = strcmp((string) $a['sort_kode_akun'], (string) $b['sort_kode_akun']);
		if ($cmp !== 0) {
			return $cmp;
		}
		$cmp = strcmp((string) $a['sort_tanggal'], (string) $b['sort_tanggal']);
		if ($cmp !== 0) {
			return $cmp;
		}
		if ((int) $a['sort_id'] === (int) $b['sort_id']) {
			return strcmp((string) $a['source_key'], (string) $b['source_key']);
		}
		return ((int) $a['sort_id'] < (int) $b['sort_id']) ? -1 : 1;
	});

	return $merged;
}

function buku_besar_apply_table_filter($merged_rows, $filter_kode = '', $filter_teks = '')
{
	$filter_kode = trim((string) $filter_kode);
	$filter_teks = trim((string) $filter_teks);

	if ($filter_kode !== '' && strcasecmp($filter_kode, 'semua') !== 0) {
		$filtered = array();
		foreach ((array) $merged_rows as $row) {
			if (isset($row['kode_akun']) && (string) $row['kode_akun'] === $filter_kode) {
				$filtered[] = $row;
			}
		}
		return $filtered;
	}

	if ($filter_teks !== '') {
		$needle = strtolower($filter_teks);
		$filtered = array();
		foreach ((array) $merged_rows as $row) {
			$kode_akun = strtolower(isset($row['kode_akun']) ? (string) $row['kode_akun'] : '');
			$nama_akun = strtolower(isset($row['nama_akun']) ? (string) $row['nama_akun'] : '');
			if (strpos($kode_akun, $needle) !== false || strpos($nama_akun, $needle) !== false) {
				$filtered[] = $row;
			}
		}
		return $filtered;
	}

	return $merged_rows;
}

function buku_besar_get_source_kode_akun_stats($CI, $month, $year)
{
	$month = (int) $month;
	$year = (int) $year;
	$month_start = sprintf('%04d-%02d-01 00:00:00', $year, $month);
	$month_end = date('Y-m-t 23:59:59', strtotime($month_start));

	$stats = array();

	$sql_tpl = 'SELECT COUNT(*) AS total,'
		. ' SUM(CASE WHEN TRIM(COALESCE(`kode_akun`, \'\')) <> \'\' THEN 1 ELSE 0 END) AS sudah,'
		. ' SUM(CASE WHEN TRIM(COALESCE(`kode_akun`, \'\')) = \'\' THEN 1 ELSE 0 END) AS belum'
		. ' FROM `%s` WHERE `%s` >= ? AND `%s` <= ?';

	$row_pembelian = $CI->db->query(sprintf($sql_tpl, 'tbl_pembelian', 'tgl_po', 'tgl_po'), array($month_start, $month_end))->row();
	$stats['pembelian'] = array(
		'label' => 'Pembelian',
		'table' => 'tbl_pembelian',
		'total' => $row_pembelian ? (int) $row_pembelian->total : 0,
		'sudah' => $row_pembelian ? (int) $row_pembelian->sudah : 0,
		'belum' => $row_pembelian ? (int) $row_pembelian->belum : 0,
	);

	$row_penjualan = $CI->db->query(sprintf($sql_tpl, 'tbl_penjualan', 'tgl_jual', 'tgl_jual'), array($month_start, $month_end))->row();
	$stats['penjualan'] = array(
		'label' => 'Penjualan',
		'table' => 'tbl_penjualan',
		'total' => $row_penjualan ? (int) $row_penjualan->total : 0,
		'sudah' => $row_penjualan ? (int) $row_penjualan->sudah : 0,
		'belum' => $row_penjualan ? (int) $row_penjualan->belum : 0,
	);

	$row_jurnal_kas = $CI->db->query(sprintf($sql_tpl, 'jurnal_kas', 'tanggal', 'tanggal'), array($month_start, $month_end))->row();
	$stats['jurnal_kas'] = array(
		'label' => 'Jurnal Kas',
		'table' => 'jurnal_kas',
		'total' => $row_jurnal_kas ? (int) $row_jurnal_kas->total : 0,
		'sudah' => $row_jurnal_kas ? (int) $row_jurnal_kas->sudah : 0,
		'belum' => $row_jurnal_kas ? (int) $row_jurnal_kas->belum : 0,
	);

	return $stats;
}

function buku_besar_resolve_kode_akun_filter($CI, $uuid_kode_akun)
{
	$uuid_kode_akun = trim((string) $uuid_kode_akun);
	if ($uuid_kode_akun === '' || $uuid_kode_akun === 'tampil_semua') {
		return array(
			'uuid_kode_akun' => $uuid_kode_akun === '' ? '' : 'tampil_semua',
			'kode_akun' => '',
			'nama_akun' => '',
			'filter_sql' => '',
			'filter_params' => array(),
		);
	}

	$row = $CI->db->where('uuid_kode_akun', $uuid_kode_akun)->get('sys_kode_akun')->row();
	if (!$row) {
		return array(
			'uuid_kode_akun' => $uuid_kode_akun,
			'kode_akun' => '',
			'nama_akun' => '',
			'filter_sql' => '',
			'filter_params' => array(),
		);
	}

	return array(
		'uuid_kode_akun' => $uuid_kode_akun,
		'kode_akun' => $row->kode_akun,
		'nama_akun' => $row->nama_akun,
		'filter_sql' => ' AND `kode_akun` = ? ',
		'filter_params' => array($row->kode_akun),
	);
}

function buku_besar_build_grouped_display_rows($merged_rows)
{
	$rows = array();
	$no = 0;
	$grand_debet = 0.0;
	$grand_kredit = 0.0;
	$current_kode = null;
	$group_debet = 0.0;
	$group_kredit = 0.0;
	$group_nama = '';
	$group_last_no = 0;

	$flush_group = function () use (&$rows, &$current_kode, &$group_debet, &$group_kredit, &$group_nama, &$group_last_no) {
		if ($current_kode === null) {
			return;
		}
		$rows[] = array(
			'row_type' => 'subtotal',
			'no' => '',
			'sort_no' => $group_last_no + 0.5,
			'source_key' => '',
			'source_label' => '',
			'kode' => '',
			'kode_akun' => '',
			'sort_kode_akun' => $current_kode . ' z',
			'nama_akun' => 'Total ' . ($group_nama !== '' ? $group_nama : $current_kode),
			'sort_nama_akun' => 'zzz ' . ($group_nama !== '' ? $group_nama : $current_kode),
			'debet' => $group_debet,
			'kredit' => $group_kredit,
			'debet_display' => buku_besar_format_rupiah($group_debet, true),
			'kredit_display' => buku_besar_format_rupiah($group_kredit, true),
		);
	};

	foreach ((array) $merged_rows as $row) {
		$kode_akun = isset($row['kode_akun']) ? trim((string) $row['kode_akun']) : '';
		if ($kode_akun === '') {
			continue;
		}

		if ($current_kode !== null && $kode_akun !== $current_kode) {
			$flush_group();
			$group_debet = 0.0;
			$group_kredit = 0.0;
			$group_nama = '';
			$group_last_no = 0;
		}
		if ($current_kode === null || $kode_akun !== $current_kode) {
			$current_kode = $kode_akun;
			$group_nama = isset($row['nama_akun']) ? trim((string) $row['nama_akun']) : '';
		}

		$debet = (float) (isset($row['debet']) ? $row['debet'] : 0);
		$kredit = (float) (isset($row['kredit']) ? $row['kredit'] : 0);
		$group_debet += $debet;
		$group_kredit += $kredit;
		$grand_debet += $debet;
		$grand_kredit += $kredit;
		$no++;
		$group_last_no = $no;

		$source_key = isset($row['source_key']) ? trim((string) $row['source_key']) : '';
		$rows[] = array(
			'row_type' => 'data',
			'no' => $no,
			'source_key' => $source_key,
			'source_label' => buku_besar_source_label($source_key),
			'kode' => isset($row['kode']) ? $row['kode'] : '',
			'kode_akun' => $kode_akun,
			'nama_akun' => isset($row['nama_akun']) ? $row['nama_akun'] : '',
			'debet' => $debet,
			'kredit' => $kredit,
			'debet_display' => buku_besar_format_rupiah($debet),
			'kredit_display' => buku_besar_format_rupiah($kredit),
		);
	}

	$flush_group();

	return array(
		'rows' => $rows,
		'total_debet' => $grand_debet,
		'total_kredit' => $grand_kredit,
	);
}

function buku_besar_compute_list_data($CI, $month, $year, $uuid_kode_akun = '', $bb_filter_kode = 'semua', $bb_filter_teks = '')
{
	$month = (int) $month;
	$year = (int) $year;
	if ($month < 1 || $month > 12) {
		$month = (int) date('m');
	}
	if ($year < 2000) {
		$year = (int) date('Y');
	}

	$filter = buku_besar_resolve_kode_akun_filter($CI, $uuid_kode_akun);
	$merged_rows = buku_besar_merge_source_rows($CI, $month, $year, $filter['kode_akun']);
	$merged_rows = buku_besar_apply_table_filter($merged_rows, $bb_filter_kode, $bb_filter_teks);
	$grouped = buku_besar_build_grouped_display_rows($merged_rows);
	$source_stats = buku_besar_get_source_kode_akun_stats($CI, $month, $year);

	return array(
		'data_Buku_besar' => $grouped['rows'],
		'total_debet' => $grouped['total_debet'],
		'total_kredit' => $grouped['total_kredit'],
		'total_rows' => count($grouped['rows']),
		'month' => $month,
		'year' => $year,
		'bulan_ns_value' => sprintf('%04d-%02d', $year, $month),
		'bulan_label' => buku_besar_bulan_teks($month) . ' ' . $year,
		'uuid_kode_akun' => $filter['uuid_kode_akun'],
		'kode_akun' => $filter['kode_akun'],
		'nama_akun' => $filter['nama_akun'],
		'bb_filter_kode' => trim((string) $bb_filter_kode) !== '' ? trim((string) $bb_filter_kode) : 'semua',
		'bb_filter_teks' => trim((string) $bb_filter_teks),
		'source_stats' => $source_stats,
	);
}

function buku_besar_excel_block_stride()
{
	return buku_besar_excel_block_width() + 1;
}

function buku_besar_excel_block_width()
{
	return 4;
}

function buku_besar_excel_block_col_start($block_index)
{
	return (int) $block_index * buku_besar_excel_block_stride();
}

function buku_besar_excel_write_block_merged_row($row, $col_start, $text, $style_index)
{
	$col_end = (int) $col_start + buku_besar_excel_block_width() - 1;
	xlsAddMerge($row, $col_start, $row, $col_end);
	xlsWriteCellStyle($row, $col_start, $text, $style_index);
	for ($c = $col_start + 1; $c <= $col_end; $c++) {
		xlsEnsureCellStyle($row, $c, $style_index, '');
	}
}

function buku_besar_excel_apply_block_border($row_start, $row_end, $col_start)
{
	$style_border = 3;
	$col_end = (int) $col_start + buku_besar_excel_block_width() - 1;
	for ($r = (int) $row_start; $r <= (int) $row_end; $r++) {
		for ($c = (int) $col_start; $c <= $col_end; $c++) {
			xlsEnsureCellStyle($r, $c, $style_border, '');
		}
	}
}

function buku_besar_excel_load_akun_blocks($CI, $month, $year, $bb_filter_kode = 'semua', $bb_filter_teks = '')
{
	$month = (int) $month;
	$year = (int) $year;
	$bb_filter_kode = trim((string) $bb_filter_kode);
	$bb_filter_teks = trim((string) $bb_filter_teks);
	if ($bb_filter_kode === '') {
		$bb_filter_kode = 'semua';
	}

	$merged_rows = buku_besar_merge_source_rows($CI, $month, $year, '');
	$merged_rows = buku_besar_apply_table_filter($merged_rows, $bb_filter_kode, $bb_filter_teks);

	$rows_by_akun = array();
	$nama_by_akun = array();
	foreach ((array) $merged_rows as $row) {
		$kode_akun = isset($row['kode_akun']) ? trim((string) $row['kode_akun']) : '';
		if ($kode_akun === '') {
			continue;
		}

		if (!isset($rows_by_akun[$kode_akun])) {
			$rows_by_akun[$kode_akun] = array();
			$nama_by_akun[$kode_akun] = isset($row['nama_akun']) ? trim((string) $row['nama_akun']) : '';
		}

		$debet = (float) (isset($row['debet']) ? $row['debet'] : 0);
		$kredit = (float) (isset($row['kredit']) ? $row['kredit'] : 0);
		$rows_by_akun[$kode_akun][] = array(
			'kode' => isset($row['kode']) ? trim((string) $row['kode']) : '',
			'kode_akun' => $kode_akun,
			'debet' => $debet,
			'kredit' => $kredit,
			'debet_display' => buku_besar_format_rupiah($debet),
			'kredit_display' => buku_besar_format_rupiah($kredit),
		);
	}

	ksort($rows_by_akun, SORT_STRING);

	$blocks = array();
	$max_data_rows = 0;
	foreach ($rows_by_akun as $kode_akun => $source_rows) {
		$total_debet = 0.0;
		$total_kredit = 0.0;
		foreach ((array) $source_rows as $item) {
			$total_debet += (float) $item['debet'];
			$total_kredit += (float) $item['kredit'];
		}

		$max_data_rows = max($max_data_rows, count($source_rows));
		$blocks[] = array(
			'kode_akun' => $kode_akun,
			'nama_akun' => isset($nama_by_akun[$kode_akun]) ? $nama_by_akun[$kode_akun] : '',
			'rows' => $source_rows,
			'total_debet' => $total_debet,
			'total_kredit' => $total_kredit,
		);
	}

	return array(
		'blocks' => $blocks,
		'max_data_rows' => $max_data_rows,
	);
}

function buku_besar_excel_build_column_widths($block_count)
{
	$widths = array();
	$block_count = (int) $block_count;

	for ($i = 0; $i < $block_count; $i++) {
		$widths[] = 12;
		$widths[] = 14;
		$widths[] = 16;
		$widths[] = 16;
		if ($i < $block_count - 1) {
			$widths[] = 2;
		}
	}

	if (count($widths) === 0) {
		$widths = array(12, 14, 16, 16);
	}

	return $widths;
}

function buku_besar_excel_write_per_akun_layout($blocks, $max_data_rows, $start_row = 0)
{
	$style_kode_akun_title = 4;
	$style_nama_akun_sub = 12;
	$style_col_header = 4;
	$style_data_left = 7;
	$style_data_right = 8;
	$style_total_label = 14;
	$style_total_amount = 10;
	$col_headers = array('Kode', 'Kode Akun', 'Debet', 'Kredit');

	$row_kode_title = (int) $start_row;
	$row_nama_title = $row_kode_title + 1;
	$row_col_header = $row_kode_title + 2;
	$data_start_row = $row_kode_title + 3;
	$max_data_rows = max(0, (int) $max_data_rows);
	$total_row = $data_start_row + $max_data_rows;

	foreach ((array) $blocks as $block_index => $block) {
		$col_start = buku_besar_excel_block_col_start($block_index);
		$col_end = $col_start + buku_besar_excel_block_width() - 1;

		buku_besar_excel_write_block_merged_row($row_kode_title, $col_start, $block['kode_akun'], $style_kode_akun_title);
		buku_besar_excel_write_block_merged_row($row_nama_title, $col_start, $block['nama_akun'], $style_nama_akun_sub);

		foreach ($col_headers as $offset => $label) {
			xlsWriteCellStyle($row_col_header, $col_start + (int) $offset, $label, $style_col_header);
		}

		foreach ((array) $block['rows'] as $offset => $item) {
			$row = $data_start_row + (int) $offset;
			xlsWriteCellStyle($row, $col_start, $item['kode'], $style_data_left);
			xlsWriteCellStyle($row, $col_start + 1, $item['kode_akun'], $style_data_left);
			xlsWriteCellStyle($row, $col_start + 2, $item['debet_display'], $style_data_right);
			xlsWriteCellStyle($row, $col_start + 3, $item['kredit_display'], $style_data_right);
		}

		for ($offset = count((array) $block['rows']); $offset < $max_data_rows; $offset++) {
			$row = $data_start_row + $offset;
			xlsEnsureCellStyle($row, $col_start, $style_data_left, '');
			xlsEnsureCellStyle($row, $col_start + 1, $style_data_left, '');
			xlsEnsureCellStyle($row, $col_start + 2, $style_data_right, '');
			xlsEnsureCellStyle($row, $col_start + 3, $style_data_right, '');
		}

		xlsAddMerge($total_row, $col_start, $total_row, $col_start + 1);
		xlsWriteCellStyle($total_row, $col_start, 'TOTAL', $style_total_label);
		xlsEnsureCellStyle($total_row, $col_start + 1, $style_total_label, '');
		xlsWriteCellStyle(
			$total_row,
			$col_start + 2,
			buku_besar_format_rupiah($block['total_debet'], true),
			$style_total_amount
		);
		xlsWriteCellStyle(
			$total_row,
			$col_start + 3,
			buku_besar_format_rupiah($block['total_kredit'], true),
			$style_total_amount
		);
	}
}

function buku_besar_excel_write_title_block($month, $year, $kode_akun_label = '')
{
	$styleTitleBoldLeft = 11;
	$styleTitleItalicLeft = 12;
	$styleTitleBoldCenter = 13;
	$titleColEnd = 7;
	$bulan_nama = strtoupper(buku_besar_bulan_teks((int) $month));

	xlsAddMerge(0, 0, 0, $titleColEnd);
	xlsWriteCellStyle(0, 0, 'PERUMDA ANEKA DHARMA', $styleTitleBoldLeft);
	xlsAddMerge(1, 0, 1, $titleColEnd);
	xlsWriteCellStyle(1, 0, 'KABUPATEN BANTUL', $styleTitleBoldLeft);
	xlsAddMerge(2, 0, 2, $titleColEnd);
	xlsWriteCellStyle(2, 0, 'Jl.Jend Sudirman 36 Bantul. Telp/Fax : 0274 367123', $styleTitleItalicLeft);

	$title = 'BUKU BESAR ' . $bulan_nama . ' ' . (int) $year;
	if ($kode_akun_label !== '') {
		$title .= ' — ' . $kode_akun_label;
	}
	xlsAddMerge(4, 0, 4, $titleColEnd);
	xlsWriteCellStyle(4, 0, $title, $styleTitleBoldCenter);
}

function buku_besar_export_excel_list_output($CI, $month = null, $year = null, $uuid_kode_akun = '')
{
	@set_time_limit(600);
	$CI->load->helper('exportexcel');

	$bulan_ns = trim((string) $CI->input->post('bulan_ns', TRUE));
	if ($month === null || $year === null) {
		$parsed = buku_besar_parse_bulan_ns($bulan_ns);
		$month = $parsed['month'];
		$year = $parsed['year'];
	}

	$month = (int) $month;
	$year = (int) $year;
	if ($month < 1 || $month > 12) {
		$month = (int) date('m');
	}
	if ($year < 2000) {
		$year = (int) date('Y');
	}

	$bb_filter_kode = trim((string) $CI->input->post('bb_filter_kode', TRUE));
	$bb_filter_teks = trim((string) $CI->input->post('bb_filter_teks', TRUE));
	if ($bb_filter_kode === '') {
		$bb_filter_kode = 'semua';
	}

	$loaded = buku_besar_excel_load_akun_blocks($CI, $month, $year, $bb_filter_kode, $bb_filter_teks);
	$blocks = isset($loaded['blocks']) ? $loaded['blocks'] : array();
	$max_data_rows = isset($loaded['max_data_rows']) ? (int) $loaded['max_data_rows'] : 0;

	$namaFile = 'Buku_Besar_' . str_pad((string) $month, 2, '0', STR_PAD_LEFT) . '_' . $year . '_' . date('Y-m-d_H-i-s') . '.xlsx';
	excel_prepare_download($namaFile);

	xlsBOF();
	xlsSetColumnWidths(buku_besar_excel_build_column_widths(count($blocks)));
	buku_besar_excel_write_title_block($month, $year);
	xlsWriteLabel(5, 0, 'Dicetak: ' . date('d/m/Y H:i:s') . ' | Total kode akun: ' . count($blocks));

	$block_start_row = 7;
	if (count($blocks) > 0) {
		buku_besar_excel_write_per_akun_layout($blocks, $max_data_rows, $block_start_row);
	} else {
		xlsWriteLabelBold14($block_start_row, 0, 'Tidak ada data untuk bulan dan filter terpilih.');
	}
	xlsEOF();
}
