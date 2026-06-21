<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function jurnal_penyesuaian_bulan_teks($angka_bulan)
{
	$angka_bulan = (int) $angka_bulan;
	$nama = array(
		1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
		5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
		9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
	);
	return isset($nama[$angka_bulan]) ? $nama[$angka_bulan] : '';
}

function jurnal_penyesuaian_format_rupiah($value, $always_show = false)
{
	if ($value === null || $value === '') {
		return '';
	}
	if (!$always_show && (float) $value == 0) {
		return '';
	}
	return number_format((float) $value, 2, ',', '.');
}

function jurnal_penyesuaian_format_tanggal_display($tanggal)
{
	$tanggal = trim((string) $tanggal);
	if ($tanggal === '' || $tanggal === '0000-00-00') {
		return '';
	}
	$ts = strtotime($tanggal);
	return $ts ? date('d-m-Y', $ts) : $tanggal;
}

function jurnal_penyesuaian_parse_date_input($value, $fallback = '')
{
	$value = trim((string) $value);
	if ($value === '') {
		return $fallback;
	}
	$ts = strtotime($value);
	if ($ts === false) {
		return $fallback;
	}
	return date('Y-m-d', $ts);
}

function jurnal_penyesuaian_row_to_list_item($row, $no)
{
	$debet = (float) (isset($row->debet) ? $row->debet : 0);
	$kredit = (float) (isset($row->kredit) ? $row->kredit : 0);

	return array(
		'no' => $no,
		'id' => isset($row->id) ? $row->id : '',
		'tanggal' => jurnal_penyesuaian_format_tanggal_display(isset($row->tanggal) ? $row->tanggal : ''),
		'bukti' => isset($row->bukti) ? $row->bukti : '',
		'pl' => isset($row->pl) ? $row->pl : '',
		'keterangan' => isset($row->keterangan) ? $row->keterangan : '',
		'kode_rekening' => isset($row->kode_rekening) ? $row->kode_rekening : '',
		'kode_akun' => isset($row->kode_akun) ? $row->kode_akun : '',
		'debet' => $debet > 0 ? $debet : null,
		'kredit' => $kredit > 0 ? $kredit : null,
		'debet_display' => jurnal_penyesuaian_format_rupiah($debet),
		'kredit_display' => jurnal_penyesuaian_format_rupiah($kredit),
	);
}

function jurnal_penyesuaian_compute_list_data_by_date_range($CI, $date_awal, $date_akhir)
{
	$date_awal = jurnal_penyesuaian_parse_date_input($date_awal, date('Y-m-01'));
	$date_akhir = jurnal_penyesuaian_parse_date_input($date_akhir, date('Y-m-t'));
	if ($date_awal > $date_akhir) {
		$tmp = $date_awal;
		$date_awal = $date_akhir;
		$date_akhir = $tmp;
	}

	$sql = 'SELECT * FROM `jurnal_penyesuaian` WHERE DATE(`tanggal`) BETWEEN ? AND ? ORDER BY `tanggal`, `id`';
	$rows_db = $CI->db->query($sql, array($date_awal, $date_akhir))->result();

	$rows = array();
	$no = 0;
	$total_debet = 0.0;
	$total_kredit = 0.0;

	foreach ($rows_db as $row) {
		$no++;
		$item = jurnal_penyesuaian_row_to_list_item($row, $no);
		if ($item['debet'] !== null) {
			$total_debet += (float) $item['debet'];
		}
		if ($item['kredit'] !== null) {
			$total_kredit += (float) $item['kredit'];
		}
		$rows[] = $item;
	}

	return array(
		'Data_kas' => $rows,
		'Total_debet' => $total_debet,
		'Total_kredit' => $total_kredit,
		'total_rows' => count($rows),
		'date_awal' => $date_awal,
		'date_akhir' => $date_akhir,
		'periode_label' => jurnal_penyesuaian_format_tanggal_display($date_awal) . ' s/d ' . jurnal_penyesuaian_format_tanggal_display($date_akhir),
		'month_selected' => (int) date('m', strtotime($date_awal)),
		'year_selected' => (int) date('Y', strtotime($date_awal)),
	);
}

function jurnal_penyesuaian_export_excel_list_output($CI, $date_awal = null, $date_akhir = null)
{
	@set_time_limit(600);
	$CI->load->helper('exportexcel');

	if ($date_awal === null) {
		$bulan_ns = trim((string) $CI->input->post('bulan_ns', TRUE));
		if (preg_match('/^(\d{4})-(\d{2})$/', $bulan_ns, $m)) {
			$date_awal = sprintf('%04d-%02d-01', (int) $m[1], (int) $m[2]);
			$date_akhir = date('Y-m-t', strtotime($date_awal));
		} else {
			$date_awal = $CI->input->post('tgl_awal', TRUE);
			$date_akhir = $CI->input->post('tgl_akhir', TRUE);
		}
	} elseif ($date_akhir === null) {
		$date_akhir = $CI->input->post('tgl_akhir', TRUE);
	}

	$data = jurnal_penyesuaian_compute_list_data_by_date_range($CI, $date_awal, $date_akhir);
	$namaFile = 'Jurnal_Penyesuaian_' . date('Y-m-d_H-i-s') . '.xlsx';
	excel_prepare_download($namaFile);

	$styleHeader = 4;
	$styleBorder = 3;
	$styleRight = 8;
	$styleFooter = 6;
	$styleLeft = 7;

	xlsBOF();

	xlsWriteLabelBold14(0, 0, 'JURNAL PENYESUAIAN');
	xlsWriteLabel(1, 0, 'Periode: ' . $data['periode_label']
		. ' | Dicetak: ' . date('d/m/Y H:i:s')
		. ' | Total baris: ' . (int) $data['total_rows']);

	$headerRow = 3;
	$headers = array('No', 'Tanggal', 'Bukti', 'PL', 'Keterangan', 'Kode Rek.', 'Kode Akun', 'Debet', 'Kredit');
	foreach ($headers as $col => $label) {
		xlsWriteCellStyle($headerRow, $col, $label, $styleHeader);
	}

	$rowNum = 4;
	foreach ($data['Data_kas'] as $item) {
		xlsWriteCellStyle($rowNum, 0, $item['no'], $styleBorder);
		xlsWriteCellStyle($rowNum, 1, $item['tanggal'], $styleLeft);
		xlsWriteCellStyle($rowNum, 2, $item['bukti'], $styleLeft);
		xlsWriteCellStyle($rowNum, 3, $item['pl'], $styleLeft);
		xlsWriteCellStyle($rowNum, 4, $item['keterangan'], $styleLeft);
		xlsWriteCellStyle($rowNum, 5, $item['kode_rekening'], $styleLeft);
		xlsWriteCellStyle($rowNum, 6, $item['kode_akun'], $styleLeft);
		if ($item['debet'] !== null) {
			xlsWriteCellStyle($rowNum, 7, jurnal_penyesuaian_format_rupiah($item['debet']), $styleRight);
		} else {
			xlsWriteCellStyle($rowNum, 7, '', $styleBorder);
		}
		if ($item['kredit'] !== null) {
			xlsWriteCellStyle($rowNum, 8, jurnal_penyesuaian_format_rupiah($item['kredit']), $styleRight);
		} else {
			xlsWriteCellStyle($rowNum, 8, '', $styleBorder);
		}
		$rowNum++;
	}

	xlsAddMerge($rowNum, 0, $rowNum, 6);
	xlsWriteCellStyle($rowNum, 0, 'JUMLAH DEBET / KREDIT', $styleFooter);
	for ($c = 1; $c <= 6; $c++) {
		xlsEnsureCellStyle($rowNum, $c, $styleFooter, '');
	}
	xlsWriteCellStyle($rowNum, 7, jurnal_penyesuaian_format_rupiah($data['Total_debet'], true), $styleFooter);
	xlsWriteCellStyle($rowNum, 8, jurnal_penyesuaian_format_rupiah($data['Total_kredit'], true), $styleFooter);

	xlsEOF();
}

function jurnal_penyesuaian_export_excel_table_preview_output($CI, $table)
{
	@set_time_limit(600);
	$CI->load->helper(array('exportexcel', 'pembelian_persediaan'));

	$preview = persediaan_compare_preview_table_data($CI, $table, 50000);
	if (empty($preview['ok'])) {
		xlsBOF();
		xlsWriteLabel(0, 0, isset($preview['message']) ? $preview['message'] : 'Export gagal.');
		xlsEOF();
		return;
	}

	$namaFile = 'Preview_' . $table . '_' . date('Y-m-d_H-i-s') . '.xlsx';
	excel_prepare_download($namaFile);

	$styleHeader = 4;
	$styleLeft = 7;

	xlsBOF();
	xlsWriteLabelBold14(0, 0, 'Detail Tabel: ' . $table);
	xlsWriteLabel(1, 0, 'Dicetak: ' . date('d/m/Y H:i:s'));

	$cols = isset($preview['columns']) ? $preview['columns'] : array();
	$headerRow = 3;
	foreach ($cols as $i => $col) {
		xlsWriteCellStyle($headerRow, $i, $col, $styleHeader);
	}

	$rowNum = 4;
	foreach ((array) (isset($preview['rows']) ? $preview['rows'] : array()) as $row) {
		foreach ($cols as $i => $col) {
			$val = isset($row[$col]) ? $row[$col] : '';
			xlsWriteCellStyle($rowNum, $i, $val, $styleLeft);
		}
		$rowNum++;
	}

	xlsEOF();
}
