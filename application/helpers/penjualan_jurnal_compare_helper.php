<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function penjualan_jurnal_compare_pick_tanggal_column($fields)
{
	return persediaan_compare_pick_column($fields, array(
		'tanggal', 'tgl_jual', 'tgl', 'date', 'tgl_transaksi',
	));
}

function penjualan_jurnal_compare_build_column_map($fields)
{
	if (!is_array($fields) || count($fields) === 0) {
		return null;
	}

	$normalized = array();
	foreach ($fields as $field) {
		$normalized[] = trim((string) $field);
	}

	$map = array(
		'tanggal' => penjualan_jurnal_compare_pick_tanggal_column($normalized),
		'keterangan' => persediaan_compare_pick_column($normalized, array(
			'keterangan', 'ket', 'nama_akun', 'uraian', 'deskripsi', 'keterangan_akun',
		)),
		'kode_rekening' => persediaan_compare_pick_column($normalized, array(
			'kode_rekening', 'kode_rek', 'kode_akun', 'kode akun', 'rek', 'rekening', 'no_rek',
		)),
		'debet' => persediaan_compare_pick_column($normalized, array('debet', 'debit')),
		'kredit' => persediaan_compare_pick_column($normalized, array('kredit', 'credit')),
	);

	if (empty($map['keterangan'])) {
		return null;
	}
	if (empty($map['debet']) && empty($map['kredit'])) {
		return null;
	}

	return $map;
}

function penjualan_jurnal_compare_validate_table($CI, $table)
{
	if (!persediaan_compare_is_valid_table_name($table)) {
		return array('ok' => false, 'message' => 'Nama tabel tidak valid.');
	}

	if (!$CI->db->table_exists($table)) {
		return array('ok' => false, 'message' => 'Tabel tidak ditemukan di database.');
	}

	$fields = $CI->db->list_fields($table);
	$map = penjualan_jurnal_compare_build_column_map($fields);
	if ($map === null) {
		return array(
			'ok' => false,
			'message' => 'Tabel harus punya kolom minimal: keterangan, debet atau kredit (dan id, tanggal otomatis dari import CSV).',
		);
	}

	return array(
		'ok' => true,
		'map' => $map,
		'fields' => $fields,
	);
}

function penjualan_jurnal_compare_normalize_keterangan($keterangan)
{
	return strtolower(trim(preg_replace('/\s+/', ' ', (string) $keterangan)));
}

function penjualan_jurnal_compare_normalize_kode_rekening($kode)
{
	return trim((string) $kode);
}

function penjualan_jurnal_compare_standard_headers()
{
	return array('No', 'Tanggal', 'Kode Rekening', 'Keterangan', 'Debet', 'Kredit', 'Catatan');
}

function penjualan_jurnal_compare_is_row_complete($keterangan, $debet, $kredit)
{
	if (trim((string) $keterangan) === '') {
		return false;
	}
	$debet_val = pembelian_jurnal_compare_normalize_jumlah($debet);
	$kredit_val = pembelian_jurnal_compare_normalize_jumlah($kredit);
	return ($debet_val > 0 || $kredit_val > 0);
}

function penjualan_jurnal_compare_make_match_key($kode_rekening, $keterangan, $debet, $kredit)
{
	return penjualan_jurnal_compare_normalize_kode_rekening($kode_rekening)
		. '|' . penjualan_jurnal_compare_normalize_keterangan($keterangan)
		. '|' . number_format(pembelian_jurnal_compare_normalize_jumlah($debet), 2, '.', '')
		. '|' . number_format(pembelian_jurnal_compare_normalize_jumlah($kredit), 2, '.', '');
}

function penjualan_jurnal_compare_row_to_display($row, $catatan = '')
{
	$debet = pembelian_jurnal_compare_normalize_jumlah(isset($row['debet']) ? $row['debet'] : 0);
	$kredit = pembelian_jurnal_compare_normalize_jumlah(isset($row['kredit']) ? $row['kredit'] : 0);
	$tanggal = isset($row['tanggal']) ? pembelian_jurnal_compare_format_tanggal_display($row['tanggal']) : '';

	return array(
		'tanggal' => $tanggal,
		'kode_rekening' => isset($row['kode_rekening']) ? $row['kode_rekening'] : '',
		'keterangan' => isset($row['keterangan']) ? $row['keterangan'] : '',
		'debet' => $debet > 0 ? pembelian_jurnal_compare_format_jumlah_display($debet) : '',
		'kredit' => $kredit > 0 ? pembelian_jurnal_compare_format_jumlah_display($kredit) : '',
		'catatan' => $catatan,
	);
}

function penjualan_jurnal_compare_rows_match($manual, $online)
{
	if (!is_array($manual) || !is_array($online)) {
		return false;
	}

	$key_m = penjualan_jurnal_compare_make_match_key(
		isset($manual['kode_rekening']) ? $manual['kode_rekening'] : '',
		isset($manual['keterangan']) ? $manual['keterangan'] : '',
		isset($manual['debet']) ? $manual['debet'] : 0,
		isset($manual['kredit']) ? $manual['kredit'] : 0
	);
	$key_o = penjualan_jurnal_compare_make_match_key(
		isset($online['kode_rekening']) ? $online['kode_rekening'] : '',
		isset($online['keterangan']) ? $online['keterangan'] : '',
		isset($online['debet']) ? $online['debet'] : 0,
		isset($online['kredit']) ? $online['kredit'] : 0
	);

	return $key_m === $key_o;
}

function penjualan_jurnal_compare_build_catatan_mismatch($manual, $online)
{
	if ($manual === null && $online === null) {
		return '';
	}
	if ($manual === null) {
		return 'Tidak ada pasangan di data manual.';
	}
	if ($online === null) {
		return 'Tidak ada pasangan di data online.';
	}

	$parts = array();
	$kr_m = penjualan_jurnal_compare_normalize_kode_rekening(isset($manual['kode_rekening']) ? $manual['kode_rekening'] : '');
	$kr_o = penjualan_jurnal_compare_normalize_kode_rekening(isset($online['kode_rekening']) ? $online['kode_rekening'] : '');
	if ($kr_m !== $kr_o) {
		$parts[] = 'Kode rekening berbeda (Manual: ' . $manual['kode_rekening'] . ', Online: ' . $online['kode_rekening'] . ')';
	}

	$ket_m = penjualan_jurnal_compare_normalize_keterangan(isset($manual['keterangan']) ? $manual['keterangan'] : '');
	$ket_o = penjualan_jurnal_compare_normalize_keterangan(isset($online['keterangan']) ? $online['keterangan'] : '');
	if ($ket_m !== $ket_o) {
		$parts[] = 'Keterangan berbeda (Manual: ' . $manual['keterangan'] . ', Online: ' . $online['keterangan'] . ')';
	}

	$deb_m = pembelian_jurnal_compare_normalize_jumlah(isset($manual['debet']) ? $manual['debet'] : 0);
	$deb_o = pembelian_jurnal_compare_normalize_jumlah(isset($online['debet']) ? $online['debet'] : 0);
	if (abs($deb_m - $deb_o) >= 0.01) {
		$parts[] = 'Debet berbeda (Manual: ' . pembelian_jurnal_compare_format_jumlah_display($deb_m)
			. ', Online: ' . pembelian_jurnal_compare_format_jumlah_display($deb_o) . ')';
	}

	$kre_m = pembelian_jurnal_compare_normalize_jumlah(isset($manual['kredit']) ? $manual['kredit'] : 0);
	$kre_o = pembelian_jurnal_compare_normalize_jumlah(isset($online['kredit']) ? $online['kredit'] : 0);
	if (abs($kre_m - $kre_o) >= 0.01) {
		$parts[] = 'Kredit berbeda (Manual: ' . pembelian_jurnal_compare_format_jumlah_display($kre_m)
			. ', Online: ' . pembelian_jurnal_compare_format_jumlah_display($kre_o) . ')';
	}

	return count($parts) > 0 ? implode('; ', $parts) : 'Data cocok.';
}

function penjualan_jurnal_compare_manual_row_from_db($row, $map, $default_tanggal = '')
{
	$keterangan = persediaan_compare_row_get($row, $map['keterangan']);
	$kode_rekening = !empty($map['kode_rekening']) ? persediaan_compare_row_get($row, $map['kode_rekening']) : '';
	$debet_raw = !empty($map['debet']) ? persediaan_compare_row_get($row, $map['debet']) : 0;
	$kredit_raw = !empty($map['kredit']) ? persediaan_compare_row_get($row, $map['kredit']) : 0;
	$tanggal_raw = !empty($map['tanggal']) ? persediaan_compare_row_get($row, $map['tanggal']) : $default_tanggal;
	$tanggal_norm = pembelian_jurnal_compare_normalize_tanggal($tanggal_raw);
	if ($tanggal_norm === '' && $default_tanggal !== '') {
		$tanggal_norm = pembelian_jurnal_compare_normalize_tanggal($default_tanggal);
	}

	return array(
		'tanggal' => $tanggal_norm,
		'kode_rekening' => trim((string) $kode_rekening),
		'keterangan' => trim((string) $keterangan),
		'debet' => pembelian_jurnal_compare_normalize_jumlah($debet_raw),
		'kredit' => pembelian_jurnal_compare_normalize_jumlah($kredit_raw),
	);
}

function penjualan_jurnal_compare_load_manual_rows_for_bulan($CI, $table, $bulan)
{
	$valid = penjualan_jurnal_compare_validate_table($CI, $table);
	if (empty($valid['ok'])) {
		return array('ok' => false, 'message' => $valid['message']);
	}

	$range = persediaan_compare_bulan_to_date_range($bulan);
	if ($range === null) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');
	}

	$fields = $valid['fields'];
	$date_col = !empty($valid['map']['tanggal']) ? $valid['map']['tanggal'] : persediaan_compare_detect_manual_date_column($fields);
	$params = array();
	$where = '';

	if ($date_col !== null) {
		$col_l = strtolower((string) $date_col);
		if ($col_l === 'tanggal') {
			$where = ' WHERE `' . $date_col . '` IS NOT NULL AND `' . $date_col . "` <> '0000-00-00'"
				. ' AND DATE(`' . $date_col . '`) >= ? AND DATE(`' . $date_col . '`) <= ?';
			$params = array($range['tgl_awal'], $range['tgl_akhir']);
		} elseif ($col_l === 'tgl_jual' || $col_l === 'tgl' || $col_l === 'tgl_transaksi') {
			$where = ' WHERE `' . $date_col . '` IS NOT NULL AND `' . $date_col . "` <> '0000-00-00'"
				. ' AND DATE(`' . $date_col . '`) >= ? AND DATE(`' . $date_col . '`) <= ?';
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

function penjualan_jurnal_compare_load_manual_rows($CI, $table, $bulan)
{
	$valid = penjualan_jurnal_compare_validate_table($CI, $table);
	if (empty($valid['ok'])) {
		return $valid;
	}

	$range = persediaan_compare_bulan_to_date_range($bulan);
	if ($range === null) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');
	}

	$loaded = penjualan_jurnal_compare_load_manual_rows_for_bulan($CI, $table, $bulan);
	if (empty($loaded['ok'])) {
		return $loaded;
	}

	$map = $loaded['map'];
	$default_tanggal = $range['tgl_akhir'];
	$rows = array();

	foreach ($loaded['rows'] as $row) {
		$item = penjualan_jurnal_compare_manual_row_from_db($row, $map, $default_tanggal);
		if (!penjualan_jurnal_compare_is_row_complete($item['keterangan'], $item['debet'], $item['kredit'])) {
			continue;
		}
		$key = penjualan_jurnal_compare_make_match_key($item['kode_rekening'], $item['keterangan'], $item['debet'], $item['kredit']);
		if (!isset($rows[$key])) {
			$rows[$key] = $item;
		}
	}

	return array(
		'ok' => true,
		'rows' => $rows,
		'list' => array_values(array_map(function ($r) {
			return penjualan_jurnal_compare_row_to_display($r);
		}, $rows)),
		'table' => $table,
		'range' => $range,
	);
}

function penjualan_jurnal_compare_load_online_rows($CI, $bulan)
{
	$range = persediaan_compare_bulan_to_date_range($bulan);
	if ($range === null) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');
	}

	$month = (int) substr($bulan, 5, 2);
	$year = (int) substr($bulan, 0, 4);

	$sql = "SELECT
		DATE(tanggal) AS tanggal,
		kode_akun AS kode_rekening,
		COALESCE(NULLIF(TRIM(keterangan), ''), NULLIF(TRIM(nama_akun), ''), '') AS keterangan,
		SUM(COALESCE(debet, 0)) AS debet,
		SUM(COALESCE(kredit, 0)) AS kredit
		FROM buku_besar
		WHERE source = 'penjualan'
		AND tanggal IS NOT NULL AND tanggal <> '0000-00-00'
		AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?
		AND kode_akun IS NOT NULL AND TRIM(kode_akun) <> ''
		GROUP BY kode_akun, COALESCE(NULLIF(TRIM(keterangan), ''), NULLIF(TRIM(nama_akun), ''), ''), debet, kredit, DATE(tanggal)";

	$rows = array();
	foreach ($CI->db->query($sql, array($month, $year))->result() as $row) {
		$item = array(
			'tanggal' => pembelian_jurnal_compare_normalize_tanggal($row->tanggal),
			'kode_rekening' => trim((string) $row->kode_rekening),
			'keterangan' => trim((string) $row->keterangan),
			'debet' => pembelian_jurnal_compare_normalize_jumlah($row->debet),
			'kredit' => pembelian_jurnal_compare_normalize_jumlah($row->kredit),
		);
		if (!penjualan_jurnal_compare_is_row_complete($item['keterangan'], $item['debet'], $item['kredit'])) {
			continue;
		}
		$key = penjualan_jurnal_compare_make_match_key($item['kode_rekening'], $item['keterangan'], $item['debet'], $item['kredit']);
		$rows[$key] = $item;
	}

	return array(
		'ok' => true,
		'rows' => $rows,
		'list' => array_values(array_map(function ($r) {
			return penjualan_jurnal_compare_row_to_display($r);
		}, $rows)),
		'range' => $range,
	);
}

function penjualan_jurnal_compare_load_online_tanpa_kode($CI, $bulan)
{
	$range = persediaan_compare_bulan_to_date_range($bulan);
	if ($range === null) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');
	}

	$month = (int) substr($bulan, 5, 2);
	$year = (int) substr($bulan, 0, 4);

	$sql = "SELECT
		DATE(tgl_jual) AS tanggal,
		nmrkirim,
		nmrpesan,
		konsumen_nama,
		SUM(jumlah * harga_satuan) AS total_jumlah
		FROM tbl_penjualan
		WHERE tgl_jual IS NOT NULL AND tgl_jual <> '0000-00-00'
		AND MONTH(tgl_jual) = ? AND YEAR(tgl_jual) = ?
		AND (kode_akun IS NULL OR TRIM(kode_akun) = '')
		GROUP BY DATE(tgl_jual), nmrkirim, nmrpesan, konsumen_nama
		ORDER BY tanggal, nmrkirim";

	$list = array();
	foreach ($CI->db->query($sql, array($month, $year))->result() as $row) {
		$list[] = array(
			'tanggal' => pembelian_jurnal_compare_format_tanggal_display(pembelian_jurnal_compare_normalize_tanggal($row->tanggal)),
			'kode_rekening' => '',
			'keterangan' => trim((string) $row->nmrkirim) . ' / ' . trim((string) $row->nmrpesan) . ' — ' . trim((string) $row->konsumen_nama),
			'debet' => '',
			'kredit' => pembelian_jurnal_compare_format_jumlah_display($row->total_jumlah),
			'catatan' => 'Belum setting kode akun penjualan',
		);
	}

	return array(
		'ok' => true,
		'list' => $list,
		'range' => $range,
	);
}

function penjualan_jurnal_compare_run($CI, $bulan, $table = '')
{
	$table = trim((string) $table);
	if ($table === '') {
		return array('ok' => false, 'message' => 'Pilih tabel manual yang akan dibandingkan.');
	}

	$valid = penjualan_jurnal_compare_validate_table($CI, $table);
	if (empty($valid['ok'])) {
		return $valid;
	}

	$manual = penjualan_jurnal_compare_load_manual_rows($CI, $table, $bulan);
	if (empty($manual['ok'])) {
		return $manual;
	}

	$online = penjualan_jurnal_compare_load_online_rows($CI, $bulan);
	if (empty($online['ok'])) {
		return $online;
	}

	$tanpa_kode = penjualan_jurnal_compare_load_online_tanpa_kode($CI, $bulan);

	$manual_tidak = array();
	$online_tidak = array();

	foreach ($manual['rows'] as $key => $manual_row) {
		if (!isset($online['rows'][$key])) {
			$manual_tidak[] = penjualan_jurnal_compare_row_to_display(
				$manual_row,
				'Tidak ditemukan di data online (kode rekening/keterangan/debet/kredit tidak cocok)'
			);
		}
	}

	foreach ($online['rows'] as $key => $online_row) {
		if (!isset($manual['rows'][$key])) {
			$online_tidak[] = penjualan_jurnal_compare_row_to_display(
				$online_row,
				'Tidak ditemukan di data manual (kode rekening/keterangan/debet/kredit tidak cocok)'
			);
		}
	}

	usort($manual_tidak, 'penjualan_jurnal_compare_sort_display_rows');
	usort($online_tidak, 'penjualan_jurnal_compare_sort_display_rows');

	return array(
		'ok' => true,
		'bulan' => $bulan,
		'bulan_label' => pembelian_jurnal_compare_bulan_label($bulan),
		'table' => $table,
		'data_manual' => $manual['list'],
		'data_online' => $online['list'],
		'manual_tidak_di_online' => $manual_tidak,
		'online_tidak_di_manual' => $online_tidak,
		'online_tanpa_kode_rekening' => isset($tanpa_kode['list']) ? $tanpa_kode['list'] : array(),
		'stats' => array(
			'data_manual' => count($manual['list']),
			'data_online' => count($online['list']),
			'manual_tidak_di_online' => count($manual_tidak),
			'online_tidak_di_manual' => count($online_tidak),
			'online_tanpa_kode_rekening' => count(isset($tanpa_kode['list']) ? $tanpa_kode['list'] : array()),
		),
	);
}

function penjualan_jurnal_compare_sort_display_rows($a, $b)
{
	$cmp = strcmp((string) $a['kode_rekening'], (string) $b['kode_rekening']);
	if ($cmp !== 0) {
		return $cmp;
	}
	return strcmp((string) $a['keterangan'], (string) $b['keterangan']);
}

function penjualan_jurnal_compare_jenis_definitions()
{
	$headers = penjualan_jurnal_compare_standard_headers();
	return array(
		'data_manual' => array('title' => '1. Data Manual', 'headers' => $headers, 'file_suffix' => 'Data_Manual'),
		'data_online' => array('title' => '2. Data Online', 'headers' => $headers, 'file_suffix' => 'Data_Online'),
		'manual_tidak_di_online' => array('title' => '3. Manual Tidak Ada di Online', 'headers' => $headers, 'file_suffix' => 'Manual_Tidak_Online'),
		'online_tidak_di_manual' => array('title' => '4. Online Tidak Ada di Manual', 'headers' => $headers, 'file_suffix' => 'Online_Tidak_Manual'),
		'online_tanpa_kode_rekening' => array('title' => '5. Online Tanpa Kode Rekening', 'headers' => $headers, 'file_suffix' => 'Online_Tanpa_Kode'),
		'semua' => array('title' => 'Compare Lengkap', 'headers' => $headers, 'file_suffix' => 'Compare_Lengkap'),
	);
}

function penjualan_jurnal_compare_get_items_by_jenis($result, $jenis)
{
	$key_map = array(
		'data_manual' => 'data_manual',
		'data_online' => 'data_online',
		'manual_tidak_di_online' => 'manual_tidak_di_online',
		'online_tidak_di_manual' => 'online_tidak_di_manual',
		'online_tanpa_kode_rekening' => 'online_tanpa_kode_rekening',
	);
	if (!isset($key_map[$jenis])) {
		return array();
	}
	$k = $key_map[$jenis];
	return isset($result[$k]) ? $result[$k] : array();
}

function penjualan_jurnal_compare_item_to_row_cells($item, $no)
{
	return array(
		$no,
		isset($item['tanggal']) ? $item['tanggal'] : '',
		isset($item['kode_rekening']) ? $item['kode_rekening'] : '',
		isset($item['keterangan']) ? $item['keterangan'] : '',
		isset($item['debet']) ? $item['debet'] : '',
		isset($item['kredit']) ? $item['kredit'] : '',
		isset($item['catatan']) ? $item['catatan'] : '',
	);
}

function penjualan_jurnal_compare_excel_block_headers()
{
	return array('No', 'Tanggal', 'Kode Rekening', 'Keterangan', 'Debet', 'Kredit', 'Catatan');
}

function penjualan_jurnal_compare_excel_all_total_cols()
{
	return 39;
}

function penjualan_jurnal_compare_excel_all_group_definitions()
{
	return array(
		array('title' => '1. DATA MANUAL (TABEL TERPILIH)', 'col_start' => 0, 'col_end' => 6),
		array('title' => '2. DATA ONLINE (BUKU BESAR PENJUALAN)', 'col_start' => 8, 'col_end' => 14),
		array('title' => '3. MANUAL TIDAK ADA DI ONLINE', 'col_start' => 16, 'col_end' => 22),
		array('title' => '4. ONLINE TIDAK ADA DI MANUAL', 'col_start' => 24, 'col_end' => 30),
		array('title' => '5. ONLINE TANPA KODE REKENING', 'col_start' => 32, 'col_end' => 38),
	);
}

function penjualan_jurnal_compare_excel_all_headers()
{
	$block = penjualan_jurnal_compare_excel_block_headers();
	return array_merge(
		$block,
		array(''),
		$block,
		array(''),
		$block,
		array(''),
		$block,
		array(''),
		$block
	);
}

function penjualan_jurnal_compare_excel_sheet_name_from_bulan($bulan)
{
	$bulan = trim((string) $bulan);
	if (!preg_match('/^(\d{4})-(\d{2})$/', $bulan, $m)) {
		return 'Compare Penjualan';
	}

	$tahun = (int) $m[1];
	$bulan_num = (int) $m[2];
	$nama_bulan = persediaan_compare_excel_all_bulan_nama_id();
	$label_bulan = isset($nama_bulan[$bulan_num]) ? $nama_bulan[$bulan_num] : ('Bulan ' . $bulan_num);

	return excel_sheet_safe_name('Compare Penjualan ' . $label_bulan . ' ' . $tahun);
}

function penjualan_jurnal_compare_excel_items_to_cells($items)
{
	$rows = array();
	$no = 0;
	foreach ((array) $items as $item) {
		$no++;
		$rows[] = penjualan_jurnal_compare_item_to_row_cells($item, $no);
	}

	return $rows;
}

function penjualan_jurnal_compare_excel_single_group($title)
{
	return array(
		array(
			'title' => strtoupper(trim((string) $title)),
			'col_start' => 0,
			'col_end' => 6,
		),
	);
}

function penjualan_jurnal_compare_excel_all_empty_row()
{
	return array_fill(0, penjualan_jurnal_compare_excel_all_total_cols(), '');
}

function penjualan_jurnal_compare_excel_all_write_merged_rows($rowNum, $sections, $groups)
{
	$totalCols = penjualan_jurnal_compare_excel_all_total_cols();
	$maxLen = 0;
	foreach ($sections as $sectionRows) {
		$maxLen = max($maxLen, count((array) $sectionRows));
	}

	for ($i = 0; $i < $maxLen; $i++) {
		$cells = penjualan_jurnal_compare_excel_all_empty_row();
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

function penjualan_jurnal_compare_excel_write_styled_table($groupTitleRow, $headerRow, $dataStartRow, $groups, $headers, $items)
{
	$headerCells = array_fill(0, 7, '');
	foreach ((array) $headers as $idx => $label) {
		$headerCells[(int) $idx] = $label;
	}

	persediaan_rekonsiliasi_tx_write_group_titles($groupTitleRow, $groups);
	persediaan_rekonsiliasi_tx_write_headers_grouped($headerRow, $headerCells, $groups);

	$row = $dataStartRow;
	$no = 0;
	foreach ((array) $items as $item) {
		$no++;
		$rowCells = array_fill(0, 7, '');
		$cells = penjualan_jurnal_compare_item_to_row_cells($item, $no);
		foreach ($cells as $idx => $value) {
			$rowCells[(int) $idx] = $value;
		}
		persediaan_rekonsiliasi_tx_write_row_grouped($row, $rowCells, $groups);
		$row++;
	}

	$lastRow = ($row > $dataStartRow) ? ($row - 1) : $headerRow;
	persediaan_rekonsiliasi_tx_finalize_group_borders($groupTitleRow, $lastRow, $groups);

	return $lastRow;
}

function penjualan_jurnal_compare_export_excel_all_output($CI, $bulan, $table = '', $result = null)
{
	@set_time_limit(600);
	@ini_set('memory_limit', '512M');

	if ($result === null) {
		$result = penjualan_jurnal_compare_run($CI, $bulan, $table);
	}
	if (empty($result['ok'])) {
		xlsAddSheet('Compare Penjualan');
		xlsWriteLabel(0, 0, isset($result['message']) ? $result['message'] : 'Export Excel ALL gagal.');
		xlsMultiEOF();
		return;
	}

	$groups = penjualan_jurnal_compare_excel_all_group_definitions();
	$headers = penjualan_jurnal_compare_excel_all_headers();
	$groupTitleRow = 2;
	$headerRow = 3;
	$dataStartRow = 4;
	$stats = isset($result['stats']) ? $result['stats'] : array();

	xlsAddSheet(penjualan_jurnal_compare_excel_sheet_name_from_bulan($bulan));
	xlsWriteLabelBold14(0, 0, 'Compare Jurnal Penjualan — Manual vs Online — Bulan ' . $result['bulan_label']);
	xlsWriteLabel(1, 0, 'Tabel manual: ' . $result['table']
		. ' | Dicetak: ' . date('d/m/Y H:i:s')
		. ' | Data manual: ' . (isset($stats['data_manual']) ? (int) $stats['data_manual'] : 0)
		. ' | Data online: ' . (isset($stats['data_online']) ? (int) $stats['data_online'] : 0)
		. ' | Manual tidak di online: ' . (isset($stats['manual_tidak_di_online']) ? (int) $stats['manual_tidak_di_online'] : 0)
		. ' | Online tidak di manual: ' . (isset($stats['online_tidak_di_manual']) ? (int) $stats['online_tidak_di_manual'] : 0)
		. ' | Tanpa kode rekening: ' . (isset($stats['online_tanpa_kode_rekening']) ? (int) $stats['online_tanpa_kode_rekening'] : 0));

	persediaan_rekonsiliasi_tx_write_group_titles($groupTitleRow, $groups);
	persediaan_rekonsiliasi_tx_write_headers_grouped($headerRow, $headers, $groups);

	$sections = array(
		0 => penjualan_jurnal_compare_excel_items_to_cells(isset($result['data_manual']) ? $result['data_manual'] : array()),
		8 => penjualan_jurnal_compare_excel_items_to_cells(isset($result['data_online']) ? $result['data_online'] : array()),
		16 => penjualan_jurnal_compare_excel_items_to_cells(isset($result['manual_tidak_di_online']) ? $result['manual_tidak_di_online'] : array()),
		24 => penjualan_jurnal_compare_excel_items_to_cells(isset($result['online_tidak_di_manual']) ? $result['online_tidak_di_manual'] : array()),
		32 => penjualan_jurnal_compare_excel_items_to_cells(isset($result['online_tanpa_kode_rekening']) ? $result['online_tanpa_kode_rekening'] : array()),
	);

	$lastRow = penjualan_jurnal_compare_excel_all_write_merged_rows($dataStartRow, $sections, $groups);
	if ($lastRow < $dataStartRow) {
		$lastRow = $headerRow;
	}

	persediaan_rekonsiliasi_tx_finalize_group_borders($groupTitleRow, $lastRow, $groups);
	xlsMultiEOF();
}

function penjualan_jurnal_compare_export_excel_output($CI, $bulan, $jenis, $table = '')
{
	@set_time_limit(600);
	@ini_set('memory_limit', '512M');
	$CI->load->helper(array('exportexcel', 'persediaan_display', 'pembelian_persediaan'));

	$defs = penjualan_jurnal_compare_jenis_definitions();
	if (!isset($defs[$jenis])) {
		xlsBOF();
		xlsWriteLabel(0, 0, 'Jenis export compare tidak valid.');
		xlsEOF();
		return;
	}

	$result = penjualan_jurnal_compare_run($CI, $bulan, $table);
	if (empty($result['ok'])) {
		xlsBOF();
		xlsWriteLabel(0, 0, isset($result['message']) ? $result['message'] : 'Compare gagal.');
		xlsEOF();
		return;
	}

	if ($jenis === 'semua') {
		penjualan_jurnal_compare_export_excel_all_output($CI, $bulan, $table, $result);
		return;
	}

	$def = $defs[$jenis];
	$items = penjualan_jurnal_compare_get_items_by_jenis($result, $jenis);
	$groups = penjualan_jurnal_compare_excel_single_group($def['title']);

	xlsBOF();
	xlsWriteLabelBold14(0, 0, $def['title'] . ' — Bulan ' . $result['bulan_label']);
	xlsWriteLabel(1, 0, 'Tabel manual: ' . $result['table']);
	xlsWriteLabel(2, 0, 'Dicetak: ' . date('d/m/Y H:i:s') . ' | Jumlah baris: ' . count($items));

	penjualan_jurnal_compare_excel_write_styled_table(3, 4, 5, $groups, $def['headers'], $items);
	xlsEOF();
}


function penjualan_jurnal_compare_csv_column_error_detail($raw_headers)
{
	$normalized = array();
	foreach ((array) $raw_headers as $header) {
		$normalized[] = trim((string) $header);
	}

	$found = penjualan_jurnal_compare_build_column_map($normalized);
	$lines = array();
	$lines[] = 'File CSV tidak dapat digunakan. Kolom wajib berikut harus ada di baris header:';
	$lines[] = '';
	$lines[] = '• keterangan / ket / uraian';
	$lines[] = '• debet atau kredit (idealnya keduanya)';
	$lines[] = '• opsional: tanggal, kode_rekening / kode_akun / rek';
	$lines[] = '';
	$lines[] = 'Header terbaca: ' . persediaan_compare_csv_headers_preview($raw_headers);
	if ($found === null) {
		$lines[] = '';
		$lines[] = 'Kolom wajib tidak lengkap atau tidak dikenali.';
	}

	return implode("\n", $lines);
}

function penjualan_jurnal_compare_validate_csv_file($filepath)
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

	$map = penjualan_jurnal_compare_build_column_map(persediaan_compare_sanitize_csv_headers($raw_headers));
	if ($map === null) {
		return array(
			'ok' => false,
			'stage' => 'validate_columns',
			'message' => penjualan_jurnal_compare_csv_column_error_detail($raw_headers),
		);
	}

	return array(
		'ok' => true,
		'map' => $map,
		'headers' => $raw_headers,
	);
}

function penjualan_jurnal_compare_csv_has_id_column($raw_headers)
{
	foreach ((array) $raw_headers as $header) {
		if (strtolower(trim((string) $header)) === 'id') {
			return true;
		}
	}

	return false;
}

function penjualan_jurnal_compare_parse_bulan_tahun_from_import($bulan_key = '', $bulan_num = 0, $tahun = 0)
{
	$bulan_key = trim((string) $bulan_key);
	if (preg_match('/^(\d{4})-(\d{2})$/', $bulan_key, $m)) {
		$year = (int) $m[1];
		$month = (int) $m[2];
	} else {
		$month = (int) $bulan_num;
		$year = (int) $tahun;
	}

	if ($month < 1 || $month > 12) {
		$month = (int) date('n');
	}
	if ($year < 2000) {
		$year = (int) date('Y');
	}

	$last_day = (int) date('t', mktime(0, 0, 0, $month, 1, $year));

	return array(
		'month' => $month,
		'year' => $year,
		'last_day' => $last_day,
		'tanggal_akhir' => sprintf('%04d-%02d-%02d', $year, $month, $last_day),
		'tanggal_akhir_display' => sprintf('%02d-%02d-%04d', $last_day, $month, $year),
	);
}

function penjualan_jurnal_compare_normalize_tanggal_csv_cell($value, $ref_month, $ref_year)
{
	$ref_month = (int) $ref_month;
	$ref_year = (int) $ref_year;
	if ($ref_month < 1 || $ref_month > 12) {
		$ref_month = (int) date('n');
	}
	if ($ref_year < 2000) {
		$ref_year = (int) date('Y');
	}

	$last_day = (int) date('t', mktime(0, 0, 0, $ref_month, 1, $ref_year));
	$value = trim((string) $value);

	if ($value === '') {
		return sprintf('%04d-%02d-%02d', $ref_year, $ref_month, $last_day);
	}

	if (preg_match('/^\d{1,2}$/', $value)) {
		$day = (int) $value;
		if ($day < 1) {
			$day = $last_day;
		} elseif ($day > $last_day) {
			$day = $last_day;
		}

		return sprintf('%04d-%02d-%02d', $ref_year, $ref_month, $day);
	}

	$norm = pembelian_jurnal_compare_normalize_tanggal($value);
	if ($norm !== '') {
		$ts = strtotime($norm);
		if ($ts !== false) {
			return date('Y-m-d', $ts);
		}
	}

	return sprintf('%04d-%02d-%02d', $ref_year, $ref_month, $last_day);
}

function penjualan_jurnal_compare_check_csv_table_name($CI, $original_filename, $bulan_key = '')
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

function penjualan_jurnal_compare_import_csv_to_db($CI, $filepath, $original_filename, $bulan_key = '', $bulan_num = 0, $tahun = 0)
{
	$file_label = trim((string) $original_filename);
	if ($file_label === '') {
		$file_label = basename((string) $filepath);
	}

	$validated = penjualan_jurnal_compare_validate_csv_file($filepath);
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
	$penjualan_map = penjualan_jurnal_compare_build_column_map($db_columns_sanitized);
	$csv_has_id = penjualan_jurnal_compare_csv_has_id_column($raw_headers);

	$csv_col_index = array();
	foreach ($db_columns_sanitized as $idx => $col) {
		$csv_col_index[$col] = $idx;
	}

	$col_tanggal = !empty($penjualan_map['tanggal']) ? $penjualan_map['tanggal'] : 'tanggal';
	$col_keterangan = $penjualan_map['keterangan'];
	$col_debet = !empty($penjualan_map['debet']) ? $penjualan_map['debet'] : 'debet';
	$col_kredit = !empty($penjualan_map['kredit']) ? $penjualan_map['kredit'] : 'kredit';

	$db_columns = array();
	foreach ($db_columns_sanitized as $col) {
		if (strtolower($col) === 'id') {
			continue;
		}
		$db_columns[] = $col;
	}
	foreach (array($col_tanggal, $col_keterangan, $col_debet, $col_kredit) as $required_col) {
		if (!in_array($required_col, $db_columns, true)) {
			$db_columns[] = $required_col;
		}
	}
	if (!empty($penjualan_map['kode_rekening']) && !in_array($penjualan_map['kode_rekening'], $db_columns, true)) {
		$db_columns[] = $penjualan_map['kode_rekening'];
	}

	$bulan_tahun_ref = penjualan_jurnal_compare_parse_bulan_tahun_from_import($bulan_key, $bulan_num, $tahun);
	$tanggal_col = $col_tanggal;
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
		if ($col === $tanggal_col) {
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

		$data[$tanggal_col] = penjualan_jurnal_compare_normalize_tanggal_csv_cell(
			isset($data[$tanggal_col]) ? $data[$tanggal_col] : '',
			$bulan_tahun_ref['month'],
			$bulan_tahun_ref['year']
		);

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
		? "Kolom `id` dari CSV diabaikan — tabel memakai kolom `id` INTEGER AUTO_INCREMENT PRIMARY KEY baru.\n"
		: "Kolom `id` (INTEGER AUTO_INCREMENT PRIMARY KEY) ditambahkan otomatis karena tidak ada di file CSV.\n";

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
			. "4. Data ter-upload: {$inserted} baris\n"
			. '5. Kolom tanggal dinormalisasi ke format DATE (YYYY-MM-DD) dengan bulan/tahun dari combobox: '
			. $bulan_tahun_ref['month'] . '/' . $bulan_tahun_ref['year']
			. ' — tanggal kosong atau tidak valid menjadi tanggal akhir bulan: '
			. $bulan_tahun_ref['tanggal_akhir_display'] . ".\n"
			. '6. Klik tombol Detail Tabel untuk melihat preview isi tabel.\n'
			. 'Silahkan lanjut compare menggunakan tabel ini.',
	);
}
