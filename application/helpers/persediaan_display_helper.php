<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Format & hitungan tampilan persediaan (list + export Excel).
 */

function persediaan_format_bulan_tahun($row, $bulan_filter = '')
{
	if (is_object($row) && !empty($row->tanggal_beli)) {
		$ts = strtotime($row->tanggal_beli);
		if ($ts !== false) {
			return date('m/Y', $ts);
		}
	}

	$bulan_filter = trim((string) $bulan_filter);
	if ($bulan_filter !== '' && preg_match('/^\d{4}-\d{2}$/', $bulan_filter)) {
		$ts = strtotime($bulan_filter . '-01');
		if ($ts !== false) {
			return date('m/Y', $ts);
		}
	}

	if (!is_object($row)) {
		return '';
	}

	$tanggal = trim((string) $row->tanggal);
	if ($tanggal === '') {
		return '';
	}

	if (preg_match('#^(\d{1,2})/(\d{1,2})/(\d{4})$#', $tanggal, $m)) {
		return sprintf('%02d/%s', (int) $m[2], $m[3]);
	}

	$ts = strtotime(str_replace('/', '-', $tanggal));
	if ($ts !== false) {
		return date('m/Y', $ts);
	}

	return $tanggal;
}

function persediaan_parse_angka($value)
{
	$v = trim((string) $value);
	if ($v === '' || $v === '-') {
		return 0;
	}
	$v = str_replace(',', '', $v);
	$v = preg_replace('/[^0-9.\-]/', '', $v);
	if ($v === '' || $v === '-') {
		return 0;
	}
	return (float) $v;
}

function persediaan_hitung_sisa_stock($row)
{
	$total_10 = persediaan_parse_angka(isset($row->total_10) ? $row->total_10 : 0);
	$penjualan = persediaan_parse_angka(isset($row->penjualan) ? $row->penjualan : 0);
	$pecah_satuan = persediaan_parse_angka(isset($row->pecah_satuan) ? $row->pecah_satuan : 0);
	$bahan_produksi = persediaan_parse_angka(isset($row->bahan_produksi) ? $row->bahan_produksi : 0);
	return $total_10 - ($penjualan + $pecah_satuan + $bahan_produksi);
}

function persediaan_export_headers()
{
	return array(
		'No',
		'Tanggal',
		'Kode',
		'Namabarang',
		'Satuan',
		'Hpp',
		'Sa',
		'Spop',
		'Beli',
		'Tuj',
		'Tgl Keluar',
		'Sekret',
		'Cetak',
		'Grafikita',
		'Dinas Umum',
		'Atk Rsud',
		'Ppbmp Kbs',
		'Kbs',
		'Ppbmp',
		'Medis',
		'Siiplah Bosda',
		'Sembako',
		'Fc Gose',
		'Fc Manding',
		'Fc Psamya',
		'Total 10',
		'Nilai Persediaan',
		'Terjual',
		'Jumlah Pecah Satuan',
		'Bahan Produksi',
		'Sisa / Stock',
	);
}

function persediaan_export_row_cells($row, $no, $bulan_filter = '')
{
	$penjualan = isset($row->penjualan) ? $row->penjualan : 0;
	$pecah_satuan = isset($row->pecah_satuan) ? $row->pecah_satuan : 0;
	$bahan_produksi = isset($row->bahan_produksi) ? $row->bahan_produksi : 0;
	$sisa = persediaan_hitung_sisa_stock($row);

	return array(
		$no,
		persediaan_format_bulan_tahun($row, $bulan_filter),
		isset($row->kode) ? $row->kode : '',
		isset($row->namabarang) ? $row->namabarang : '',
		isset($row->satuan) ? $row->satuan : '',
		isset($row->hpp) ? $row->hpp : '',
		isset($row->sa) ? $row->sa : '',
		isset($row->spop) ? $row->spop : '',
		isset($row->beli) ? $row->beli : '',
		isset($row->tuj) ? $row->tuj : '',
		isset($row->tgl_keluar) ? $row->tgl_keluar : '',
		isset($row->sekret) ? $row->sekret : '',
		isset($row->cetak) ? $row->cetak : '',
		isset($row->grafikita) ? $row->grafikita : '',
		isset($row->dinas_umum) ? $row->dinas_umum : '',
		isset($row->atk_rsud) ? $row->atk_rsud : '',
		isset($row->ppbmp_kbs) ? $row->ppbmp_kbs : '',
		isset($row->kbs) ? $row->kbs : '',
		isset($row->ppbmp) ? $row->ppbmp : '',
		isset($row->medis) ? $row->medis : '',
		isset($row->siiplah_bosda) ? $row->siiplah_bosda : '',
		isset($row->sembako) ? $row->sembako : '',
		isset($row->fc_gose) ? $row->fc_gose : '',
		isset($row->fc_manding) ? $row->fc_manding : '',
		isset($row->fc_psamya) ? $row->fc_psamya : '',
		isset($row->total_10) ? $row->total_10 : '',
		isset($row->nilai_persediaan) ? $row->nilai_persediaan : '',
		$penjualan,
		$pecah_satuan,
		$bahan_produksi,
		$sisa,
	);
}
