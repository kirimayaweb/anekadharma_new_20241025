<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

$rows = isset($rows) && is_array($rows) ? $rows : array();
$table_id = isset($table_id) ? (string) $table_id : 'table-gen-proses-produksi-bahan-tidak-ada';
$empty_msg = isset($empty_msg) ? (string) $empty_msg : 'Semua record bahan produksi sudah cocok dengan persediaan bulan target.';
$excel_jenis = isset($excel_jenis) ? trim((string) $excel_jenis) : 'proses_produksi_bahan_tidak_ada';
$no = 0;
$sum_jumlah = 0.0;

if (!function_exists('persediaan_gen_proses_pembelian_format_tgl')) {
	$CI = function_exists('get_instance') ? get_instance() : null;
	if ($CI) {
		$CI->load->helper('persediaan_display');
	}
}
?>
<?php if ($excel_jenis !== '') {
	$this->load->view('anekadharma/persediaan/_gen_proses_excel_btn', array(
		'excel_jenis' => $excel_jenis,
		'excel_title' => isset($excel_title) ? $excel_title : 'Export bahan produksi tidak ada di persediaan',
	));
} ?>
<div class="alert alert-warning small py-2 mb-2">
	Record di bawah ada di <strong>sys_unit_produk_bahan</strong> bulan target, tetapi tidak ditemukan di tabel <strong>persediaan</strong>
	(cocokkan: <code>uuid_persediaan_bahan</code> atau <code>nama_barang_bahan + satuan_bahan + harga_satuan_bahan</code> = <code>namabarang + satuan + hpp</code>).
</div>
<div class="gen-proses-produksi-dt-wrap persediaan-tab-dt-wrap">
	<table id="<?php echo htmlspecialchars($table_id, ENT_QUOTES, 'UTF-8'); ?>" class="table table-bordered table-striped table-sm gen-proses-produksi-dt display nowrap" style="width:100%"
		data-empty-msg="<?php echo htmlspecialchars($empty_msg, ENT_QUOTES, 'UTF-8'); ?>">
		<thead>
			<tr>
				<th>No</th>
				<th>Tgl Transaksi</th>
				<th>Nama Unit</th>
				<th>Nama Produk</th>
				<th>Nama Bahan (sys_unit_produk_bahan)</th>
				<th>Satuan Bahan</th>
				<th>HPP Bahan</th>
				<th>Jumlah Bahan</th>
				<th>UUID Persediaan Bahan</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($rows as $row) {
				$no++;
				$jumlah = isset($row->jumlah_bahan_num) ? $row->jumlah_bahan_num : (isset($row->jumlah_bahan) ? $row->jumlah_bahan : 0);
				$sum_jumlah += (float) persediaan_parse_angka($jumlah);
			?>
			<tr>
				<td><?php echo (int) $no; ?></td>
				<td><?php echo persediaan_gen_proses_pembelian_format_tgl(isset($row->tgl_transaksi) ? $row->tgl_transaksi : ''); ?></td>
				<td><?php echo htmlspecialchars(isset($row->nama_unit) ? (string) $row->nama_unit : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars(isset($row->nama_produk) ? (string) $row->nama_produk : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars(isset($row->nama_barang_bahan) ? (string) $row->nama_barang_bahan : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars(isset($row->satuan_bahan) ? (string) $row->satuan_bahan : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td class="text-right"><?php echo persediaan_gen_proses_pembelian_format_nominal(isset($row->harga_satuan_bahan) ? $row->harga_satuan_bahan : 0); ?></td>
				<td class="text-right"><?php echo persediaan_gen_proses_pembelian_format_jumlah($jumlah); ?></td>
				<td><small><?php echo htmlspecialchars(isset($row->uuid_persediaan_bahan) ? (string) $row->uuid_persediaan_bahan : (isset($row->uuid_persediaan) ? (string) $row->uuid_persediaan : ''), ENT_QUOTES, 'UTF-8'); ?></small></td>
			</tr>
			<?php } ?>
		</tbody>
		<tfoot class="gen-proses-dt-tfoot">
			<tr>
				<th class="font-weight-bold">TOTAL</th>
				<th colspan="6"></th>
				<th class="text-right font-weight-bold"><?php echo persediaan_gen_proses_pembelian_format_jumlah($sum_jumlah); ?></th>
				<th></th>
			</tr>
		</tfoot>
	</table>
</div>
