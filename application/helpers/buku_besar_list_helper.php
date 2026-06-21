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

function buku_besar_compute_list_data($CI, $month, $year, $uuid_kode_akun = '')
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
	$sql = 'SELECT * FROM `buku_besar` WHERE MONTH(`tanggal`) = ? AND YEAR(`tanggal`) = ?'
		. $filter['filter_sql']
		. ' ORDER BY `tanggal`, `id` ASC';

	$params = array_merge(array($month, $year), $filter['filter_params']);
	$rows_db = $CI->db->query($sql, $params)->result();

	$rows = array();
	$no = 0;
	$total_debet = 0.0;
	$total_kredit = 0.0;

	foreach ($rows_db as $row) {
		if (empty($row->kode_akun)) {
			continue;
		}
		$no++;
		$debet = (float) (isset($row->debet) ? $row->debet : 0);
		$kredit = (float) (isset($row->kredit) ? $row->kredit : 0);
		$total_debet += $debet;
		$total_kredit += $kredit;

		$nama_akun = isset($row->nama_akun) ? trim((string) $row->nama_akun) : '';
		if ($nama_akun === '') {
			$akun = $CI->db->where('kode_akun', $row->kode_akun)->get('sys_kode_akun')->row();
			$nama_akun = $akun ? $akun->nama_akun : '';
		}

		$rows[] = array(
			'no' => $no,
			'tanggal' => buku_besar_format_tanggal_display($row->tanggal),
			'pl' => isset($row->pl) ? $row->pl : '',
			'kode' => isset($row->kode) ? $row->kode : '',
			'kode_akun' => $row->kode_akun,
			'nama_akun' => $nama_akun,
			'debet' => $debet,
			'kredit' => $kredit,
			'debet_display' => buku_besar_format_rupiah($debet),
			'kredit_display' => buku_besar_format_rupiah($kredit),
		);
	}

	return array(
		'data_Buku_besar' => $rows,
		'total_debet' => $total_debet,
		'total_kredit' => $total_kredit,
		'total_rows' => count($rows),
		'month' => $month,
		'year' => $year,
		'bulan_ns_value' => sprintf('%04d-%02d', $year, $month),
		'bulan_label' => buku_besar_bulan_teks($month) . ' ' . $year,
		'uuid_kode_akun' => $filter['uuid_kode_akun'],
		'kode_akun' => $filter['kode_akun'],
		'nama_akun' => $filter['nama_akun'],
	);
}

function buku_besar_excel_block_stride()
{
	return 5;
}

function buku_besar_excel_block_width()
{
	return 3;
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

function buku_besar_excel_load_akun_blocks($CI, $month, $year)
{
	$akun_list = $CI->db->order_by('kode_akun', 'ASC')->get('sys_kode_akun')->result();
	$blocks = array();
	$max_data_rows = 0;

	foreach ((array) $akun_list as $akun) {
		$sql = 'SELECT `kode`, `debet`, `kredit` FROM `buku_besar`'
			. ' WHERE MONTH(`tanggal`) = ? AND YEAR(`tanggal`) = ? AND `kode_akun` = ?'
			. ' ORDER BY `tanggal`, `id` ASC';
		$rows = $CI->db->query($sql, array((int) $month, (int) $year, $akun->kode_akun))->result();

		$data_rows = array();
		foreach ((array) $rows as $row) {
			$debet = (float) (isset($row->debet) ? $row->debet : 0);
			$kredit = (float) (isset($row->kredit) ? $row->kredit : 0);
			$data_rows[] = array(
				'kode' => isset($row->kode) ? trim((string) $row->kode) : '',
				'debet' => buku_besar_format_rupiah($debet),
				'kredit' => buku_besar_format_rupiah($kredit),
			);
		}

		$max_data_rows = max($max_data_rows, count($data_rows));
		$blocks[] = array(
			'kode_akun' => $akun->kode_akun,
			'nama_akun' => $akun->nama_akun,
			'rows' => $data_rows,
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
		$widths[] = 14;
		$widths[] = 16;
		$widths[] = 16;
		if ($i < $block_count - 1) {
			$widths[] = 2;
			$widths[] = 2;
		}
	}

	if (count($widths) === 0) {
		$widths = array(14, 16, 16);
	}

	return $widths;
}

function buku_besar_excel_write_per_akun_layout($blocks, $max_data_rows)
{
	$style_title_bb = 2;
	$style_kode_akun = 4;
	$style_nama_akun = 6;
	$style_kode_cell = 7;
	$style_amount_cell = 8;
	$header_rows = 4;
	$data_start_row = $header_rows;
	$last_row = $data_start_row + max(0, (int) $max_data_rows) - 1;
	if ($max_data_rows <= 0) {
		$last_row = $header_rows - 1;
	}

	foreach ($blocks as $block_index => $block) {
		$col_start = buku_besar_excel_block_col_start($block_index);

		buku_besar_excel_write_block_merged_row(0, $col_start, 'BUKU BESAR', $style_title_bb);
		buku_besar_excel_write_block_merged_row(2, $col_start, $block['kode_akun'], $style_kode_akun);
		buku_besar_excel_write_block_merged_row(3, $col_start, $block['nama_akun'], $style_nama_akun);

		foreach ($block['rows'] as $offset => $item) {
			$row = $data_start_row + (int) $offset;
			xlsWriteCellStyle($row, $col_start, $item['kode'], $style_kode_cell);
			xlsWriteCellStyle($row, $col_start + 1, $item['debet'], $style_amount_cell);
			xlsWriteCellStyle($row, $col_start + 2, $item['kredit'], $style_amount_cell);
		}

		if ($last_row >= 0) {
			buku_besar_excel_apply_block_border(0, max($last_row, 3), $col_start);
		}
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

	$loaded = buku_besar_excel_load_akun_blocks($CI, $month, $year);
	$blocks = isset($loaded['blocks']) ? $loaded['blocks'] : array();
	$max_data_rows = isset($loaded['max_data_rows']) ? (int) $loaded['max_data_rows'] : 0;

	$namaFile = 'Buku_Besar_' . str_pad((string) $month, 2, '0', STR_PAD_LEFT) . '_' . $year . '_' . date('Y-m-d_H-i-s') . '.xlsx';
	excel_prepare_download($namaFile);

	xlsBOF();
	xlsSetColumnWidths(buku_besar_excel_build_column_widths(count($blocks)));
	buku_besar_excel_write_per_akun_layout($blocks, $max_data_rows);
	xlsEOF();
}
