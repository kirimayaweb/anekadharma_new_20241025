<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function penerimaan_kas_compare_build_column_map($fields)
{
	return penjualan_jurnal_compare_build_column_map($fields);
}

function penerimaan_kas_compare_validate_table($CI, $table)
{
	if (!persediaan_compare_is_valid_table_name($table)) {
		return array('ok' => false, 'message' => 'Nama tabel tidak valid.');
	}
	if (!$CI->db->table_exists($table)) {
		return array('ok' => false, 'message' => 'Tabel tidak ditemukan di database.');
	}

	$fields = $CI->db->list_fields($table);
	$map = penerimaan_kas_compare_build_column_map($fields);
	if ($map === null) {
		return array(
			'ok' => false,
			'message' => 'Tabel harus punya kolom minimal: keterangan, debet atau kredit (dan id, tanggal otomatis dari import CSV).',
		);
	}

	return array('ok' => true, 'map' => $map, 'fields' => $fields);
}

function penerimaan_kas_compare_normalize_keterangan($keterangan)
{
	return penjualan_jurnal_compare_normalize_keterangan($keterangan);
}

function penerimaan_kas_compare_normalize_jumlah($value)
{
	return (int) round(pembelian_jurnal_compare_parse_jumlah_nominal($value));
}

function penerimaan_kas_compare_format_jumlah_display($jumlah)
{
	return number_format((int) $jumlah, 0, ',', '.');
}

function penerimaan_kas_compare_standard_headers()
{
	return array('No', 'Tanggal', 'Keterangan', 'Debet', 'Kredit', 'Catatan');
}

function penerimaan_kas_compare_is_row_analyzable($tanggal, $keterangan, $debet, $kredit)
{
	if (pembelian_jurnal_compare_normalize_tanggal($tanggal) === '') {
		return false;
	}
	if (trim((string) $keterangan) === '') {
		return false;
	}
	$deb = penerimaan_kas_compare_normalize_jumlah($debet);
	$kre = penerimaan_kas_compare_normalize_jumlah($kredit);
	return ($deb > 0 || $kre > 0);
}

function penerimaan_kas_compare_make_match_key($tanggal, $keterangan, $debet, $kredit)
{
	$tgl = pembelian_jurnal_compare_normalize_tanggal($tanggal);
	return $tgl
		. '|' . penerimaan_kas_compare_normalize_keterangan($keterangan)
		. '|' . penerimaan_kas_compare_normalize_jumlah($debet)
		. '|' . penerimaan_kas_compare_normalize_jumlah($kredit);
}

function penerimaan_kas_compare_make_soft_key($tanggal, $keterangan)
{
	$tgl = pembelian_jurnal_compare_normalize_tanggal($tanggal);
	return $tgl . '|' . penerimaan_kas_compare_normalize_keterangan($keterangan);
}

function penerimaan_kas_compare_row_to_display($row, $catatan = '')
{
	$debet = penerimaan_kas_compare_normalize_jumlah(isset($row['debet']) ? $row['debet'] : 0);
	$kredit = penerimaan_kas_compare_normalize_jumlah(isset($row['kredit']) ? $row['kredit'] : 0);
	$tanggal = isset($row['tanggal']) ? pembelian_jurnal_compare_format_tanggal_display($row['tanggal']) : '';

	return array(
		'tanggal' => $tanggal,
		'keterangan' => isset($row['keterangan']) ? $row['keterangan'] : '',
		'debet' => $debet > 0 ? penerimaan_kas_compare_format_jumlah_display($debet) : '',
		'kredit' => $kredit > 0 ? penerimaan_kas_compare_format_jumlah_display($kredit) : '',
		'catatan' => $catatan,
	);
}

function penerimaan_kas_compare_build_mismatch_catatan($side_label, $row, $candidates, $other_label)
{
	if (!is_array($candidates) || count($candidates) === 0) {
		return 'Tidak ditemukan di data ' . $other_label . ' (tanggal/keterangan/debet/kredit tidak cocok).';
	}

	$best = null;
	$best_score = -1;
	foreach ($candidates as $candidate) {
		$score = 0;
		if (pembelian_jurnal_compare_normalize_tanggal($row['tanggal']) === pembelian_jurnal_compare_normalize_tanggal($candidate['tanggal'])) {
			$score += 2;
		}
		if (penerimaan_kas_compare_normalize_keterangan($row['keterangan']) === penerimaan_kas_compare_normalize_keterangan($candidate['keterangan'])) {
			$score += 3;
		}
		if ($score > $best_score) {
			$best_score = $score;
			$best = $candidate;
		}
	}

	if ($best === null) {
		return 'Tidak ditemukan di data ' . $other_label . '.';
	}

	$parts = array('Cek data ' . $other_label . ' yang hampir mirip:');
	$parts[] = 'Tanggal ' . $other_label . ': ' . pembelian_jurnal_compare_format_tanggal_display($best['tanggal'])
		. ' (Manual: ' . pembelian_jurnal_compare_format_tanggal_display($row['tanggal']) . ')';
	$parts[] = 'Keterangan ' . $other_label . ': ' . $best['keterangan'];

	$deb_m = penerimaan_kas_compare_normalize_jumlah($row['debet']);
	$deb_o = penerimaan_kas_compare_normalize_jumlah($best['debet']);
	if ($deb_m !== $deb_o) {
		$parts[] = 'Debet berbeda (Manual: ' . penerimaan_kas_compare_format_jumlah_display($deb_m)
			. ', ' . ucfirst($other_label) . ': ' . penerimaan_kas_compare_format_jumlah_display($deb_o) . ')';
	}

	$kre_m = penerimaan_kas_compare_normalize_jumlah($row['kredit']);
	$kre_o = penerimaan_kas_compare_normalize_jumlah($best['kredit']);
	if ($kre_m !== $kre_o) {
		$parts[] = 'Kredit berbeda (Manual: ' . penerimaan_kas_compare_format_jumlah_display($kre_m)
			. ', ' . ucfirst($other_label) . ': ' . penerimaan_kas_compare_format_jumlah_display($kre_o) . ')';
	}

	return implode('; ', $parts);
}

function penerimaan_kas_compare_manual_row_from_db($row, $map, $default_tanggal = '')
{
	$keterangan = persediaan_compare_row_get($row, $map['keterangan']);
	$debet_raw = !empty($map['debet']) ? persediaan_compare_row_get($row, $map['debet']) : 0;
	$kredit_raw = !empty($map['kredit']) ? persediaan_compare_row_get($row, $map['kredit']) : 0;
	$tanggal_raw = !empty($map['tanggal']) ? persediaan_compare_row_get($row, $map['tanggal']) : $default_tanggal;
	$tanggal_norm = pembelian_jurnal_compare_normalize_tanggal($tanggal_raw);
	if ($tanggal_norm === '' && $default_tanggal !== '') {
		$tanggal_norm = pembelian_jurnal_compare_normalize_tanggal($default_tanggal);
	}

	return array(
		'tanggal' => $tanggal_norm,
		'keterangan' => trim((string) $keterangan),
		'debet' => penerimaan_kas_compare_normalize_jumlah($debet_raw),
		'kredit' => penerimaan_kas_compare_normalize_jumlah($kredit_raw),
	);
}

function penerimaan_kas_compare_load_manual_rows_for_bulan($CI, $table, $bulan)
{
	return penjualan_jurnal_compare_load_manual_rows_for_bulan($CI, $table, $bulan);
}

function penerimaan_kas_compare_load_manual_all($CI, $table, $bulan)
{
	$valid = penerimaan_kas_compare_validate_table($CI, $table);
	if (empty($valid['ok'])) {
		return $valid;
	}

	$loaded = penerimaan_kas_compare_load_manual_rows_for_bulan($CI, $table, $bulan);
	if (empty($loaded['ok'])) {
		return $loaded;
	}

	$map = $loaded['map'];
	$default_tanggal = $loaded['range']['tgl_akhir'];
	$valid_rows = array();
	$unprocessed = array();

	foreach ($loaded['rows'] as $row) {
		$item = penerimaan_kas_compare_manual_row_from_db($row, $map, $default_tanggal);
		if (!penerimaan_kas_compare_is_row_analyzable($item['tanggal'], $item['keterangan'], $item['debet'], $item['kredit'])) {
			$reasons = array();
			if (pembelian_jurnal_compare_normalize_tanggal($item['tanggal']) === '') {
				$reasons[] = 'tanggal kosong';
			}
			if (trim((string) $item['keterangan']) === '') {
				$reasons[] = 'keterangan kosong';
			}
			if (penerimaan_kas_compare_normalize_jumlah($item['debet']) <= 0 && penerimaan_kas_compare_normalize_jumlah($item['kredit']) <= 0) {
				$reasons[] = 'debet dan kredit kosong';
			}
			$unprocessed[] = penerimaan_kas_compare_row_to_display($item, 'Manual tidak terproses: ' . implode(', ', $reasons));
			continue;
		}
		$key = penerimaan_kas_compare_make_match_key($item['tanggal'], $item['keterangan'], $item['debet'], $item['kredit']);
		if (!isset($valid_rows[$key])) {
			$valid_rows[$key] = $item;
		}
	}

	return array(
		'ok' => true,
		'rows' => $valid_rows,
		'list' => array_values(array_map(function ($r) {
			return penerimaan_kas_compare_row_to_display($r);
		}, $valid_rows)),
		'unprocessed' => $unprocessed,
		'soft_index' => penerimaan_kas_compare_build_soft_index($valid_rows),
		'table' => $table,
		'range' => $loaded['range'],
	);
}

function penerimaan_kas_compare_online_row_from_db($row)
{
	$debet = penerimaan_kas_compare_normalize_jumlah($row->debet);
	$kredit = penerimaan_kas_compare_normalize_jumlah($row->kredit);
	if ($kredit <= 0 && $debet > 0) {
		$kredit = $debet;
	}

	return array(
		'tanggal' => pembelian_jurnal_compare_normalize_tanggal($row->tanggal),
		'keterangan' => trim((string) $row->keterangan),
		'debet' => $debet,
		'kredit' => $kredit,
	);
}

function penerimaan_kas_compare_load_online_all($CI, $bulan)
{
	$range = persediaan_compare_bulan_to_date_range($bulan);
	if ($range === null) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');
	}

	$month = (int) substr($bulan, 5, 2);
	$year = (int) substr($bulan, 0, 4);

	$sql = "SELECT DATE(tanggal) AS tanggal, keterangan, debet, kredit
		FROM jurnal_kas
		WHERE debet > 0
		AND tanggal IS NOT NULL AND tanggal <> '0000-00-00'
		AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?
		ORDER BY tanggal, id";

	$valid_rows = array();
	$unprocessed = array();

	foreach ($CI->db->query($sql, array($month, $year))->result() as $row) {
		$item = penerimaan_kas_compare_online_row_from_db($row);
		if (!penerimaan_kas_compare_is_row_analyzable($item['tanggal'], $item['keterangan'], $item['debet'], $item['kredit'])) {
			$reasons = array();
			if (pembelian_jurnal_compare_normalize_tanggal($item['tanggal']) === '') {
				$reasons[] = 'tanggal kosong';
			}
			if (trim((string) $item['keterangan']) === '') {
				$reasons[] = 'keterangan kosong';
			}
			if (penerimaan_kas_compare_normalize_jumlah($item['debet']) <= 0 && penerimaan_kas_compare_normalize_jumlah($item['kredit']) <= 0) {
				$reasons[] = 'debet dan kredit kosong';
			}
			$unprocessed[] = penerimaan_kas_compare_row_to_display($item, 'Online tidak terproses: ' . implode(', ', $reasons));
			continue;
		}
		$key = penerimaan_kas_compare_make_match_key($item['tanggal'], $item['keterangan'], $item['debet'], $item['kredit']);
		if (!isset($valid_rows[$key])) {
			$valid_rows[$key] = $item;
		}
	}

	return array(
		'ok' => true,
		'rows' => $valid_rows,
		'list' => array_values(array_map(function ($r) {
			return penerimaan_kas_compare_row_to_display($r);
		}, $valid_rows)),
		'unprocessed' => $unprocessed,
		'soft_index' => penerimaan_kas_compare_build_soft_index($valid_rows),
		'range' => $range,
	);
}

function penerimaan_kas_compare_build_soft_index($rows_by_key)
{
	$index = array();
	foreach ((array) $rows_by_key as $row) {
		$soft = penerimaan_kas_compare_make_soft_key($row['tanggal'], $row['keterangan']);
		if (!isset($index[$soft])) {
			$index[$soft] = array();
		}
		$index[$soft][] = $row;
	}
	return $index;
}

function penerimaan_kas_compare_find_similar($soft_index, $row)
{
	$soft = penerimaan_kas_compare_make_soft_key($row['tanggal'], $row['keterangan']);
	return isset($soft_index[$soft]) ? $soft_index[$soft] : array();
}

function penerimaan_kas_compare_sort_display_rows($a, $b)
{
	$cmp = strcmp((string) $a['tanggal'], (string) $b['tanggal']);
	if ($cmp !== 0) {
		return $cmp;
	}
	return strcmp((string) $a['keterangan'], (string) $b['keterangan']);
}

function penerimaan_kas_compare_run($CI, $bulan, $table = '')
{
	$table = trim((string) $table);
	if ($table === '') {
		return array('ok' => false, 'message' => 'Pilih tabel manual yang akan dibandingkan.');
	}

	$manual = penerimaan_kas_compare_load_manual_all($CI, $table, $bulan);
	if (empty($manual['ok'])) {
		return $manual;
	}

	$online = penerimaan_kas_compare_load_online_all($CI, $bulan);
	if (empty($online['ok'])) {
		return $online;
	}

	$data_cocok = array();
	$data_online_cocok = array();
	$manual_tidak = array();
	$online_tidak = array();

	foreach ($manual['rows'] as $key => $manual_row) {
		if (isset($online['rows'][$key])) {
			$data_cocok[] = penerimaan_kas_compare_row_to_display($manual_row, 'Data manual dan online cocok');
			$data_online_cocok[] = penerimaan_kas_compare_row_to_display($online['rows'][$key], 'Data online cocok dengan manual');
		} else {
			$similar = penerimaan_kas_compare_find_similar($online['soft_index'], $manual_row);
			$manual_tidak[] = penerimaan_kas_compare_row_to_display(
				$manual_row,
				penerimaan_kas_compare_build_mismatch_catatan('manual', $manual_row, $similar, 'online')
			);
		}
	}

	foreach ($online['rows'] as $key => $online_row) {
		if (!isset($manual['rows'][$key])) {
			$similar = penerimaan_kas_compare_find_similar($manual['soft_index'], $online_row);
			$online_tidak[] = penerimaan_kas_compare_row_to_display(
				$online_row,
				penerimaan_kas_compare_build_mismatch_catatan('online', $online_row, $similar, 'manual')
			);
		}
	}

	$tidak_bisa = array_merge($manual['unprocessed'], $online['unprocessed']);

	usort($data_cocok, 'penerimaan_kas_compare_sort_display_rows');
	usort($data_online_cocok, 'penerimaan_kas_compare_sort_display_rows');
	usort($manual_tidak, 'penerimaan_kas_compare_sort_display_rows');
	usort($online_tidak, 'penerimaan_kas_compare_sort_display_rows');
	usort($tidak_bisa, 'penerimaan_kas_compare_sort_display_rows');

	return array(
		'ok' => true,
		'bulan' => $bulan,
		'bulan_label' => pembelian_jurnal_compare_bulan_label($bulan),
		'table' => $table,
		'data_cocok' => $data_cocok,
		'data_manual' => $manual['list'],
		'data_online_cocok' => $data_online_cocok,
		'manual_tidak_di_online' => $manual_tidak,
		'online_tidak_di_manual' => $online_tidak,
		'tidak_bisa_dianalisa' => $tidak_bisa,
		'manual_tidak_terproses' => $manual['unprocessed'],
		'online_tidak_terproses' => $online['unprocessed'],
		'stats' => array(
			'data_cocok' => count($data_cocok),
			'data_manual' => count($manual['list']),
			'data_online_cocok' => count($data_online_cocok),
			'manual_tidak_di_online' => count($manual_tidak),
			'online_tidak_di_manual' => count($online_tidak),
			'tidak_bisa_dianalisa' => count($tidak_bisa),
			'manual_tidak_terproses' => count($manual['unprocessed']),
			'online_tidak_terproses' => count($online['unprocessed']),
		),
	);
}

function penerimaan_kas_compare_jenis_definitions()
{
	$headers = penerimaan_kas_compare_standard_headers();
	return array(
		'data_cocok' => array('title' => '1. Data Manual dan Online Cocok', 'headers' => $headers, 'file_suffix' => 'Data_Cocok'),
		'data_manual' => array('title' => '2. Data Manual', 'headers' => $headers, 'file_suffix' => 'Data_Manual'),
		'data_online_cocok' => array('title' => '3. Data Online yang Cocok di Manual', 'headers' => $headers, 'file_suffix' => 'Data_Online_Cocok'),
		'manual_tidak_di_online' => array('title' => '4. Manual Tidak Ada di Online', 'headers' => $headers, 'file_suffix' => 'Manual_Tidak_Online'),
		'online_tidak_di_manual' => array('title' => '5. Online Tidak Ada di Manual', 'headers' => $headers, 'file_suffix' => 'Online_Tidak_Manual'),
		'tidak_bisa_dianalisa' => array('title' => '6. Tidak Bisa Dianalisa', 'headers' => $headers, 'file_suffix' => 'Tidak_Bisa_Dianalisa'),
		'manual_tidak_terproses' => array('title' => '7. Manual Tidak Terproses', 'headers' => $headers, 'file_suffix' => 'Manual_Tidak_Terproses'),
		'online_tidak_terproses' => array('title' => '8. Online Tidak Terproses', 'headers' => $headers, 'file_suffix' => 'Online_Tidak_Terproses'),
		'semua' => array('title' => 'Compare Lengkap Penerimaan Kas', 'headers' => $headers, 'file_suffix' => 'Compare_Lengkap'),
	);
}

function penerimaan_kas_compare_get_items_by_jenis($result, $jenis)
{
	$key_map = array(
		'data_cocok' => 'data_cocok',
		'data_manual' => 'data_manual',
		'data_online_cocok' => 'data_online_cocok',
		'manual_tidak_di_online' => 'manual_tidak_di_online',
		'online_tidak_di_manual' => 'online_tidak_di_manual',
		'tidak_bisa_dianalisa' => 'tidak_bisa_dianalisa',
		'manual_tidak_terproses' => 'manual_tidak_terproses',
		'online_tidak_terproses' => 'online_tidak_terproses',
	);
	if (!isset($key_map[$jenis])) {
		return array();
	}
	$k = $key_map[$jenis];
	return isset($result[$k]) ? $result[$k] : array();
}

function penerimaan_kas_compare_item_to_row_cells($item, $no)
{
	return array(
		$no,
		isset($item['tanggal']) ? $item['tanggal'] : '',
		isset($item['keterangan']) ? $item['keterangan'] : '',
		isset($item['debet']) ? $item['debet'] : '',
		isset($item['kredit']) ? $item['kredit'] : '',
		isset($item['catatan']) ? $item['catatan'] : '',
	);
}

function penerimaan_kas_compare_excel_block_headers()
{
	return penerimaan_kas_compare_standard_headers();
}

function penerimaan_kas_compare_excel_all_total_cols()
{
	return 35;
}

function penerimaan_kas_compare_excel_all_group_definitions()
{
	return array(
		array('title' => '1. DATA MANUAL', 'col_start' => 0, 'col_end' => 5),
		array('title' => '2. DATA ONLINE COCOK DI MANUAL', 'col_start' => 7, 'col_end' => 12),
		array('title' => '3. MANUAL TIDAK ADA DI ONLINE', 'col_start' => 14, 'col_end' => 19),
		array('title' => '4. MANUAL TIDAK TERPROSES', 'col_start' => 21, 'col_end' => 26),
		array('title' => '5. ONLINE TIDAK TERPROSES', 'col_start' => 28, 'col_end' => 33),
	);
}

function penerimaan_kas_compare_excel_all_headers()
{
	$block = penerimaan_kas_compare_excel_block_headers();
	return array_merge($block, array(''), $block, array(''), $block, array(''), $block, array(''), $block);
}

function penerimaan_kas_compare_excel_sheet_name_from_bulan($bulan)
{
	$bulan = trim((string) $bulan);
	if (!preg_match('/^(\d{4})-(\d{2})$/', $bulan, $m)) {
		return 'Compare Penerimaan Kas';
	}
	$tahun = (int) $m[1];
	$bulan_num = (int) $m[2];
	$nama_bulan = persediaan_compare_excel_all_bulan_nama_id();
	$label_bulan = isset($nama_bulan[$bulan_num]) ? $nama_bulan[$bulan_num] : ('Bulan ' . $bulan_num);
	return excel_sheet_safe_name('Compare Penerimaan Kas ' . $label_bulan . ' ' . $tahun);
}

function penerimaan_kas_compare_excel_items_to_cells($items)
{
	$rows = array();
	$no = 0;
	foreach ((array) $items as $item) {
		$no++;
		$rows[] = penerimaan_kas_compare_item_to_row_cells($item, $no);
	}
	return $rows;
}

function penerimaan_kas_compare_excel_all_empty_row()
{
	return array_fill(0, penerimaan_kas_compare_excel_all_total_cols(), '');
}

function penerimaan_kas_compare_excel_all_write_merged_rows($rowNum, $sections, $groups)
{
	$maxLen = 0;
	foreach ($sections as $sectionRows) {
		$maxLen = max($maxLen, count((array) $sectionRows));
	}
	for ($i = 0; $i < $maxLen; $i++) {
		$cells = penerimaan_kas_compare_excel_all_empty_row();
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

function penerimaan_kas_compare_excel_single_group($title)
{
	return array(
		array(
			'title' => strtoupper(trim((string) $title)),
			'col_start' => 0,
			'col_end' => 5,
		),
	);
}

function penerimaan_kas_compare_excel_write_styled_table($groupTitleRow, $headerRow, $dataStartRow, $groups, $headers, $items)
{
	$headerCells = array_fill(0, 6, '');
	foreach ((array) $headers as $idx => $label) {
		$headerCells[(int) $idx] = $label;
	}
	persediaan_rekonsiliasi_tx_write_group_titles($groupTitleRow, $groups);
	persediaan_rekonsiliasi_tx_write_headers_grouped($headerRow, $headerCells, $groups);
	$row = $dataStartRow;
	$no = 0;
	foreach ((array) $items as $item) {
		$no++;
		$rowCells = array_fill(0, 6, '');
		$cells = penerimaan_kas_compare_item_to_row_cells($item, $no);
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

function penerimaan_kas_compare_export_excel_all_output($CI, $bulan, $table = '', $result = null)
{
	@set_time_limit(600);
	@ini_set('memory_limit', '512M');
	if ($result === null) {
		$result = penerimaan_kas_compare_run($CI, $bulan, $table);
	}
	if (empty($result['ok'])) {
		xlsAddSheet('Compare Penerimaan Kas');
		xlsWriteLabel(0, 0, isset($result['message']) ? $result['message'] : 'Export Excel ALL gagal.');
		xlsMultiEOF();
		return;
	}

	$groups = penerimaan_kas_compare_excel_all_group_definitions();
	$headers = penerimaan_kas_compare_excel_all_headers();
	$groupTitleRow = 2;
	$headerRow = 3;
	$dataStartRow = 4;
	$stats = isset($result['stats']) ? $result['stats'] : array();

	xlsAddSheet(penerimaan_kas_compare_excel_sheet_name_from_bulan($bulan));
	xlsWriteLabelBold14(0, 0, 'Compare Jurnal Penerimaan Kas — Manual vs Online — Bulan ' . $result['bulan_label']);
	xlsWriteLabel(1, 0, 'Tabel manual: ' . $result['table']
		. ' | Dicetak: ' . date('d/m/Y H:i:s')
		. ' | Cocok: ' . (isset($stats['data_cocok']) ? (int) $stats['data_cocok'] : 0)
		. ' | Manual: ' . (isset($stats['data_manual']) ? (int) $stats['data_manual'] : 0)
		. ' | Online cocok: ' . (isset($stats['data_online_cocok']) ? (int) $stats['data_online_cocok'] : 0)
		. ' | Manual tidak di online: ' . (isset($stats['manual_tidak_di_online']) ? (int) $stats['manual_tidak_di_online'] : 0)
		. ' | Online tidak di manual: ' . (isset($stats['online_tidak_di_manual']) ? (int) $stats['online_tidak_di_manual'] : 0));

	persediaan_rekonsiliasi_tx_write_group_titles($groupTitleRow, $groups);
	persediaan_rekonsiliasi_tx_write_headers_grouped($headerRow, $headers, $groups);

	$sections = array(
		0 => penerimaan_kas_compare_excel_items_to_cells(isset($result['data_manual']) ? $result['data_manual'] : array()),
		7 => penerimaan_kas_compare_excel_items_to_cells(isset($result['data_online_cocok']) ? $result['data_online_cocok'] : array()),
		14 => penerimaan_kas_compare_excel_items_to_cells(isset($result['manual_tidak_di_online']) ? $result['manual_tidak_di_online'] : array()),
		21 => penerimaan_kas_compare_excel_items_to_cells(isset($result['manual_tidak_terproses']) ? $result['manual_tidak_terproses'] : array()),
		28 => penerimaan_kas_compare_excel_items_to_cells(isset($result['online_tidak_terproses']) ? $result['online_tidak_terproses'] : array()),
	);

	$lastRow = penerimaan_kas_compare_excel_all_write_merged_rows($dataStartRow, $sections, $groups);
	if ($lastRow < $dataStartRow) {
		$lastRow = $headerRow;
	}
	persediaan_rekonsiliasi_tx_finalize_group_borders($groupTitleRow, $lastRow, $groups);
	xlsMultiEOF();
}

function penerimaan_kas_compare_export_excel_output($CI, $bulan, $jenis, $table = '')
{
	@set_time_limit(600);
	@ini_set('memory_limit', '512M');
	$CI->load->helper(array('exportexcel', 'persediaan_display', 'pembelian_persediaan'));

	$defs = penerimaan_kas_compare_jenis_definitions();
	if (!isset($defs[$jenis])) {
		xlsBOF();
		xlsWriteLabel(0, 0, 'Jenis export compare tidak valid.');
		xlsEOF();
		return;
	}

	$result = penerimaan_kas_compare_run($CI, $bulan, $table);
	if (empty($result['ok'])) {
		xlsBOF();
		xlsWriteLabel(0, 0, isset($result['message']) ? $result['message'] : 'Compare gagal.');
		xlsEOF();
		return;
	}

	if ($jenis === 'semua') {
		penerimaan_kas_compare_export_excel_all_output($CI, $bulan, $table, $result);
		return;
	}

	$def = $defs[$jenis];
	$items = penerimaan_kas_compare_get_items_by_jenis($result, $jenis);
	$groups = penerimaan_kas_compare_excel_single_group($def['title']);

	xlsBOF();
	xlsWriteLabelBold14(0, 0, $def['title'] . ' — Bulan ' . $result['bulan_label']);
	xlsWriteLabel(1, 0, 'Tabel manual: ' . $result['table']);
	xlsWriteLabel(2, 0, 'Dicetak: ' . date('d/m/Y H:i:s') . ' | Jumlah baris: ' . count($items));
	penerimaan_kas_compare_excel_write_styled_table(3, 4, 5, $groups, $def['headers'], $items);
	xlsEOF();
}

function penerimaan_kas_compare_validate_csv_file($filepath)
{
	$validated = penjualan_jurnal_compare_validate_csv_file($filepath);
	if (empty($validated['ok'])) {
		return $validated;
	}
	return $validated;
}

function penerimaan_kas_compare_check_csv_table_name($CI, $original_filename, $bulan_key = '')
{
	return penjualan_jurnal_compare_check_csv_table_name($CI, $original_filename, $bulan_key);
}

function penerimaan_kas_compare_import_csv_to_db($CI, $filepath, $original_filename, $bulan_key = '', $bulan_num = 0, $tahun = 0)
{
	$file_label = trim((string) $original_filename);
	if ($file_label === '') {
		$file_label = basename((string) $filepath);
	}

	$validated = penerimaan_kas_compare_validate_csv_file($filepath);
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
	$column_map = penerimaan_kas_compare_build_column_map($db_columns_sanitized);
	$csv_has_id = penjualan_jurnal_compare_csv_has_id_column($raw_headers);

	$csv_col_index = array();
	foreach ($db_columns_sanitized as $idx => $col) {
		$csv_col_index[$col] = $idx;
	}

	$col_tanggal = !empty($column_map['tanggal']) ? $column_map['tanggal'] : 'tanggal';
	$col_keterangan = $column_map['keterangan'];
	$col_debet = !empty($column_map['debet']) ? $column_map['debet'] : 'debet';
	$col_kredit = !empty($column_map['kredit']) ? $column_map['kredit'] : 'kredit';

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

	$bulan_tahun_ref = penjualan_jurnal_compare_parse_bulan_tahun_from_import($bulan_key, $bulan_num, $tahun);
	$base_table = persediaan_compare_sanitize_table_name_from_csv($original_filename, $bulan_key);
	$resolved = persediaan_compare_resolve_unique_table_name($CI, $base_table);
	if (empty($resolved['ok'])) {
		fclose($handle);
		return array(
			'ok' => false,
			'stage' => 'nama_tabel',
			'message' => isset($resolved['message']) ? $resolved['message'] : "Nama tabel hasil import dari file `{$file_label}` tidak valid.",
			'file' => $file_label,
		);
	}

	$table = $resolved['table'];
	$table_suffix = isset($resolved['suffix']) ? (int) $resolved['suffix'] : 0;
	$table_base = isset($resolved['base']) ? $resolved['base'] : $base_table;
	$table_exists_before = $CI->db->table_exists($table_base);

	$field_defs = array('`id` INT(9) NOT NULL AUTO_INCREMENT');
	foreach ($db_columns as $col) {
		if ($col === $col_tanggal) {
			$field_defs[] = '`' . $col . '` DATE NULL';
		} elseif ($col === $col_debet || $col === $col_kredit) {
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
		foreach ($db_columns as $col) {
			$csv_idx = isset($csv_col_index[$col]) ? $csv_col_index[$col] : null;
			$data[$col] = ($csv_idx !== null && isset($row[$csv_idx])) ? trim((string) $row[$csv_idx]) : '';
		}

		$data[$col_tanggal] = penjualan_jurnal_compare_normalize_tanggal_csv_cell(
			isset($data[$col_tanggal]) ? $data[$col_tanggal] : '',
			$bulan_tahun_ref['month'],
			$bulan_tahun_ref['year']
		);

		if ($col_debet !== '') {
			$data[$col_debet] = penerimaan_kas_compare_normalize_jumlah(isset($data[$col_debet]) ? $data[$col_debet] : 0);
		}
		if ($col_kredit !== '') {
			$data[$col_kredit] = penerimaan_kas_compare_normalize_jumlah(isset($data[$col_kredit]) ? $data[$col_kredit] : 0);
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

	$id_info = $csv_has_id
		? "Kolom `id` dari CSV diabaikan — tabel memakai kolom `id` INT(9) AUTO_INCREMENT PRIMARY KEY baru.\n"
		: "Kolom `id` INT(9) AUTO_INCREMENT PRIMARY KEY ditambahkan otomatis karena tidak ada di file CSV.\n";

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
			. "4. Kolom debet dan kredit bertipe INT(9).\n"
			. "5. Data ter-upload: {$inserted} baris\n"
			. '6. Kolom tanggal dinormalisasi ke format DATE dengan bulan/tahun dari combobox: '
			. $bulan_tahun_ref['month'] . '/' . $bulan_tahun_ref['year']
			. ' — tanggal kosong menjadi tanggal akhir bulan: '
			. $bulan_tahun_ref['tanggal_akhir_display'] . ".\n"
			. 'Silahkan lanjut compare menggunakan tabel ini.',
	);
}

function penerimaan_kas_compare_load_import_helpers($CI)
{
	$CI->load->helper(array('pembelian_persediaan', 'penjualan_jurnal_compare', 'jurnal_kas_compare', 'penerimaan_kas_list'));
}

function penerimaan_kas_compare_validate_import_table($CI, $table)
{
	penerimaan_kas_compare_load_import_helpers($CI);

	$valid = jurnal_kas_compare_validate_import_table($CI, $table);
	if (empty($valid['ok'])) {
		return $valid;
	}

	$pl_col = persediaan_compare_pick_column($valid['fields'], array('pl', 'kode_pl', 'kode pl'));
	$valid['map']['pl'] = $pl_col;
	if ($pl_col !== null && $pl_col !== '') {
		$valid['mapped']['pl'] = $pl_col;
	} else {
		return array(
			'ok' => false,
			'message' => 'Kolom PL wajib ada untuk import jurnal penerimaan kas.',
			'missing_fields' => array('pl'),
			'fields' => $valid['fields'],
		);
	}

	return $valid;
}

function penerimaan_kas_compare_import_row_from_db($row, $map, $ref_month = 0, $ref_year = 0)
{
	$item = jurnal_kas_compare_import_row_from_db($row, $map, $ref_month, $ref_year);
	$item['pl'] = trim((string) (!empty($map['pl']) ? persediaan_compare_row_get($row, $map['pl']) : ''));

	return $item;
}

function penerimaan_kas_compare_is_import_row_saveable($row)
{
	$tanggal = pembelian_jurnal_compare_normalize_tanggal(isset($row['tanggal']) ? $row['tanggal'] : '');
	if ($tanggal === '' || $tanggal === '0000-00-00') {
		return false;
	}
	if (trim((string) (isset($row['keterangan']) ? $row['keterangan'] : '')) === '') {
		return false;
	}
	if (trim((string) (isset($row['pl']) ? $row['pl'] : '')) === '') {
		return false;
	}

	$deb = penerimaan_kas_compare_normalize_jumlah(isset($row['debet']) ? $row['debet'] : 0);
	$kre = penerimaan_kas_compare_normalize_jumlah(isset($row['kredit']) ? $row['kredit'] : 0);
	$rek = trim((string) (isset($row['kode_rekening']) ? $row['kode_rekening'] : ''));

	return ($deb > 0 || $kre > 0 || $rek !== '');
}

function penerimaan_kas_compare_make_duplicate_key($row)
{
	return pembelian_jurnal_compare_normalize_tanggal(isset($row['tanggal']) ? $row['tanggal'] : '')
		. '|' . trim((string) (isset($row['pl']) ? $row['pl'] : ''))
		. '|' . penerimaan_kas_compare_normalize_keterangan(isset($row['keterangan']) ? $row['keterangan'] : '')
		. '|' . penerimaan_kas_compare_normalize_jumlah(isset($row['debet']) ? $row['debet'] : 0)
		. '|' . penerimaan_kas_compare_normalize_jumlah(isset($row['kredit']) ? $row['kredit'] : 0);
}

function penerimaan_kas_compare_row_to_insert_data($item)
{
	$debet = penerimaan_kas_parse_amount(isset($item['debet']) ? $item['debet'] : 0);
	$kredit = penerimaan_kas_parse_amount(isset($item['kredit']) ? $item['kredit'] : 0);
	$rek = trim((string) (isset($item['kode_rekening']) ? $item['kode_rekening'] : ''));
	$jumlah = max($debet, $kredit);

	$data = array(
		'tanggal' => penerimaan_kas_format_tanggal_storage(isset($item['tanggal']) ? $item['tanggal'] : ''),
		'nomorbuktibkm' => trim((string) (isset($item['bukti']) ? $item['bukti'] : '')),
		'pl' => trim((string) (isset($item['pl']) ? $item['pl'] : '')),
		'keterangan' => substr(trim((string) (isset($item['keterangan']) ? $item['keterangan'] : '')), 0, 255),
		'debet_11101_kas_besar' => $debet > 0 ? penerimaan_kas_format_rupiah($debet, true) : '',
		'kredit_11301_pu_non_angsuran' => $kredit > 0 ? penerimaan_kas_format_rupiah($kredit, true) : '',
		'serba_serbi_rekening' => $rek,
		'serba_serbi_jumlah' => '',
	);

	if ($rek !== '' && $jumlah > 0) {
		$data['serba_serbi_jumlah'] = penerimaan_kas_format_rupiah($jumlah, true);
	}

	return $data;
}

function penerimaan_kas_compare_load_existing_keys($CI, $bulan)
{
	$range = persediaan_compare_bulan_to_date_range($bulan);
	if ($range === null) {
		return array();
	}

	$existing_keys = array();
	$rows = penerimaan_kas_fetch_records_in_range($CI, $range['tgl_awal'] . ' 00:00:00', $range['tgl_akhir'] . ' 23:59:59');

	foreach ((array) $rows as $er) {
		$debet = penerimaan_kas_parse_amount(isset($er->debet_11101_kas_besar) ? $er->debet_11101_kas_besar : 0);
		$kredit = penerimaan_kas_parse_amount(isset($er->kredit_11301_pu_non_angsuran) ? $er->kredit_11301_pu_non_angsuran : 0);
		$existing_keys[penerimaan_kas_compare_make_duplicate_key(array(
			'tanggal' => isset($er->tanggal) ? $er->tanggal : '',
			'pl' => isset($er->pl) ? $er->pl : '',
			'keterangan' => isset($er->keterangan) ? $er->keterangan : '',
			'debet' => $debet,
			'kredit' => $kredit,
		))] = true;
	}

	return $existing_keys;
}

function penerimaan_kas_compare_validate_table_for_import($CI, $table, $bulan)
{
	penerimaan_kas_compare_load_import_helpers($CI);

	if (!preg_match('/^\d{4}-\d{2}$/', (string) $bulan)) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');
	}

	$valid = penerimaan_kas_compare_validate_import_table($CI, $table);
	if (empty($valid['ok'])) {
		return array(
			'ok' => true,
			'eligible' => false,
			'import_enabled' => false,
			'bulan_match' => false,
			'message' => isset($valid['message']) ? $valid['message'] : 'Tabel tidak memenuhi syarat import.',
			'missing_fields' => isset($valid['missing_fields']) ? $valid['missing_fields'] : array(),
			'table' => $table,
			'bulan' => $bulan,
		);
	}

	$ref_year = (int) substr($bulan, 0, 4);
	$ref_month = (int) substr($bulan, 5, 2);
	$map = $valid['map'];
	$all_rows = $CI->db->query('SELECT * FROM `' . $table . '` ORDER BY id ASC')->result();

	$with_tanggal = 0;
	$in_bulan = 0;
	$out_bulan = 0;
	$empty_tanggal = 0;
	$saveable_in_bulan = 0;
	$invalid_in_bulan = 0;

	foreach ((array) $all_rows as $row) {
		$item = penerimaan_kas_compare_import_row_from_db($row, $map, $ref_month, $ref_year);
		$tanggal_norm = pembelian_jurnal_compare_normalize_tanggal($item['tanggal']);
		if ($tanggal_norm === '' || $tanggal_norm === '0000-00-00') {
			$empty_tanggal++;
			continue;
		}
		$with_tanggal++;
		if (jurnal_kas_compare_row_matches_bulan($tanggal_norm, $bulan)) {
			$in_bulan++;
			if (penerimaan_kas_compare_is_import_row_saveable($item)) {
				$saveable_in_bulan++;
			} else {
				$invalid_in_bulan++;
			}
		} else {
			$out_bulan++;
		}
	}

	$import_enabled = ($saveable_in_bulan > 0);
	$import_message = '';
	if ($import_enabled) {
		$import_message = 'Siap disimpan ke jurnal_penerimaan_kas: ' . $saveable_in_bulan . ' baris valid pada bulan terpilih.';
		if ($out_bulan > 0) {
			$import_message .= ' (' . $out_bulan . ' baris di luar bulan akan dilewati.)';
		}
		if ($invalid_in_bulan > 0) {
			$import_message .= ' (' . $invalid_in_bulan . ' baris bulan terpilih tidak valid.)';
		}
	} elseif ($in_bulan > 0) {
		$import_message = 'Ada ' . $in_bulan . ' baris pada bulan terpilih, tetapi tidak memenuhi syarat: tanggal, PL, keterangan, dan minimal salah satu debet/kredit/rekening.';
	} elseif ($out_bulan > 0) {
		$import_message = 'Tidak ada data dengan tanggal pada bulan terpilih. (' . $out_bulan . ' baris tanggalnya di bulan lain.)';
	} else {
		$import_message = 'Tidak ada data dengan tanggal valid pada tabel ini.';
	}

	return array(
		'ok' => true,
		'eligible' => true,
		'import_enabled' => $import_enabled,
		'bulan_match' => $import_enabled,
		'import_message' => $import_message,
		'message' => 'Tabel `' . $table . '` memenuhi syarat kolom import jurnal penerimaan kas.',
		'map' => $map,
		'mapped' => isset($valid['mapped']) ? $valid['mapped'] : array(),
		'table' => $table,
		'bulan' => $bulan,
		'stats' => array(
			'total_rows' => count($all_rows),
			'with_tanggal' => $with_tanggal,
			'in_bulan' => $in_bulan,
			'out_bulan' => $out_bulan,
			'empty_tanggal' => $empty_tanggal,
			'saveable_in_bulan' => $saveable_in_bulan,
			'invalid_in_bulan' => $invalid_in_bulan,
		),
	);
}

function penerimaan_kas_compare_load_table_detail_for_bulan($CI, $table, $bulan)
{
	penerimaan_kas_compare_load_import_helpers($CI);

	if (!preg_match('/^\d{4}-\d{2}$/', (string) $bulan)) {
		return array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).');
	}

	$valid = penerimaan_kas_compare_validate_import_table($CI, $table);
	if (empty($valid['ok'])) {
		return $valid;
	}

	$ref_year = (int) substr($bulan, 0, 4);
	$ref_month = (int) substr($bulan, 5, 2);
	$map = $valid['map'];
	$range = persediaan_compare_bulan_to_date_range($bulan);
	$all_rows = $CI->db->query('SELECT * FROM `' . $table . '` ORDER BY id ASC')->result();
	$items = array();
	$no = 0;

	foreach ((array) $all_rows as $row) {
		$item = penerimaan_kas_compare_import_row_from_db($row, $map, $ref_month, $ref_year);
		if (!jurnal_kas_compare_row_matches_bulan($item['tanggal'], $bulan)) {
			continue;
		}
		$no++;
		$debet = penerimaan_kas_compare_normalize_jumlah($item['debet']);
		$kredit = penerimaan_kas_compare_normalize_jumlah($item['kredit']);
		$items[] = array(
			'no' => $no,
			'tanggal' => pembelian_jurnal_compare_format_tanggal_display($item['tanggal']),
			'pl' => $item['pl'],
			'bukti' => $item['bukti'],
			'keterangan' => $item['keterangan'],
			'kode_rekening' => $item['kode_rekening'],
			'debet' => $debet > 0 ? penerimaan_kas_compare_format_jumlah_display($debet) : '',
			'kredit' => $kredit > 0 ? penerimaan_kas_compare_format_jumlah_display($kredit) : '',
		);
	}

	return array(
		'ok' => true,
		'headers' => array('No', 'Tanggal', 'PL', 'Bukti BKM', 'Keterangan', 'No. Rek', 'Debet', 'Kredit'),
		'rows' => $items,
		'table' => $table,
		'bulan' => $bulan,
		'bulan_label' => $range ? $range['bulan_label'] : $bulan,
		'total' => count($items),
	);
}

function penerimaan_kas_compare_import_to_jurnal_penerimaan_kas($CI, $table, $bulan)
{
	penerimaan_kas_compare_load_import_helpers($CI);

	$status = penerimaan_kas_compare_validate_table_for_import($CI, $table, $bulan);
	if (empty($status['ok'])) {
		return $status;
	}
	if (empty($status['eligible'])) {
		return array('ok' => false, 'message' => isset($status['message']) ? $status['message'] : 'Tabel tidak memenuhi syarat import.');
	}
	if (empty($status['import_enabled'])) {
		return array(
			'ok' => false,
			'message' => isset($status['import_message']) ? $status['import_message'] : 'Data tidak bisa dimasukkan ke jurnal penerimaan kas.',
		);
	}

	if (!$CI->db->table_exists('jurnal_penerimaan_kas')) {
		return array('ok' => false, 'message' => 'Tabel `jurnal_penerimaan_kas` tidak ditemukan di database.');
	}

	$CI->load->model('Jurnal_penerimaan_kas_model');
	$valid = penerimaan_kas_compare_validate_import_table($CI, $table);
	$map = $valid['map'];
	$ref_year = (int) substr($bulan, 0, 4);
	$ref_month = (int) substr($bulan, 5, 2);
	$all_rows = $CI->db->query('SELECT * FROM `' . $table . '` ORDER BY id ASC')->result();
	$inserted = 0;
	$skipped_out_bulan = 0;
	$skipped_invalid = 0;

	$CI->db->trans_start();
	foreach ((array) $all_rows as $row) {
		$item = penerimaan_kas_compare_import_row_from_db($row, $map, $ref_month, $ref_year);
		if (!jurnal_kas_compare_row_matches_bulan($item['tanggal'], $bulan)) {
			$skipped_out_bulan++;
			continue;
		}
		if (!penerimaan_kas_compare_is_import_row_saveable($item)) {
			$skipped_invalid++;
			continue;
		}

		$CI->Jurnal_penerimaan_kas_model->insert(penerimaan_kas_compare_row_to_insert_data($item));
		$inserted++;
	}
	$CI->db->trans_complete();

	if ($CI->db->trans_status() === FALSE) {
		return array('ok' => false, 'message' => 'Gagal menyimpan data ke jurnal_penerimaan_kas.');
	}

	$message = 'Berhasil menambahkan ' . $inserted . ' data ke jurnal_penerimaan_kas (semua baris valid disimpan apa adanya).';
	if ($skipped_out_bulan > 0) {
		$message .= ' ' . $skipped_out_bulan . ' baris di luar bulan terpilih tidak disimpan.';
	}
	if ($skipped_invalid > 0) {
		$message .= ' ' . $skipped_invalid . ' baris tidak valid (tanggal/PL/keterangan/debet-kredit kosong) tidak disimpan.';
	}

	return array(
		'ok' => true,
		'inserted' => $inserted,
		'skipped_out_bulan' => $skipped_out_bulan,
		'skipped_invalid' => $skipped_invalid,
		'skipped' => $skipped_out_bulan + $skipped_invalid,
		'message' => $message,
	);
}
