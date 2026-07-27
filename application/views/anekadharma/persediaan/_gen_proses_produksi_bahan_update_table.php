<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

$rows = isset($rows) && is_array($rows) ? $rows : array();
$table_id = isset($table_id) ? (string) $table_id : 'table-gen-proses-produksi-bahan-update';
$empty_msg = isset($empty_msg) ? (string) $empty_msg : 'Belum ada bahan produksi yang terhubung ke persediaan bulan target.';
$excel_jenis = isset($excel_jenis) ? trim((string) $excel_jenis) : 'proses_produksi_bahan_update';
$no = 0;
$sum_jumlah = 0.0;
$sum_bahan_produksi = 0.0;

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
		'excel_title' => isset($excel_title) ? $excel_title : 'Export update bahan produksi ke Excel',
	));
} ?>
<div class="alert alert-success small py-2 mb-2">
	Record bahan produksi yang <strong>cocok</strong> dengan persediaan bulan target.
	Kolom <strong>Bahan Produksi (Persediaan)</strong> menampilkan nilai field <code>bahan_produksi</code> setelah proses Generate &amp; Recalculate.
</div>
<div class="gen-proses-produksi-dt-wrap persediaan-tab-dt-wrap">
	<table id="<?php echo htmlspecialchars($table_id, ENT_QUOTES, 'UTF-8'); ?>" class="table table-bordered table-striped table-sm gen-proses-produksi-dt display nowrap" style="width:100%"
		data-empty-msg="<?php echo htmlspecialchars($empty_msg, ENT_QUOTES, 'UTF-8'); ?>">
		<thead>
			<tr>
				<th>No</th>
				<th>ID Persediaan</th>
				<th>Nama Bahan</th>
				<th>Satuan</th>
				<th>HPP</th>
				<th>Jumlah Bahan (Produksi)</th>
				<th>Bahan Produksi (Persediaan)</th>
				<th>Total_10 (Persediaan)</th>
				<th>Match</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($rows as $row) {
				$no++;
				$jumlah = isset($row->jumlah_bahan_num) ? $row->jumlah_bahan_num : (isset($row->jumlah_bahan) ? $row->jumlah_bahan : 0);
				$bahan_prod = isset($row->bahan_produksi_persediaan) ? $row->bahan_produksi_persediaan : 0;
				$total10 = isset($row->total_10_persediaan) ? $row->total_10_persediaan : 0;
				$sum_jumlah += (float) persediaan_parse_angka($jumlah);
				$sum_bahan_produksi += (float) persediaan_parse_angka($bahan_prod);
			?>
			<tr>
				<td><?php echo (int) $no; ?></td>
				<td><?php echo (int) (isset($row->id_persediaan_bahan) ? $row->id_persediaan_bahan : 0); ?></td>
				<td><?php echo htmlspecialchars(isset($row->nama_bahan_tampil) ? (string) $row->nama_bahan_tampil : (isset($row->nama_barang_bahan) ? (string) $row->nama_barang_bahan : ''), ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars(isset($row->satuan_tampil) ? (string) $row->satuan_tampil : (isset($row->satuan_bahan) ? (string) $row->satuan_bahan : ''), ENT_QUOTES, 'UTF-8'); ?></td>
				<td class="text-right"><?php echo persediaan_gen_proses_pembelian_format_nominal(isset($row->harga_satuan_persediaan) ? $row->harga_satuan_persediaan : (isset($row->harga_satuan_bahan) ? $row->harga_satuan_bahan : 0)); ?></td>
				<td class="text-right"><?php echo persediaan_gen_proses_pembelian_format_jumlah($jumlah); ?></td>
				<td class="text-right font-weight-bold text-primary"><?php echo persediaan_gen_proses_pembelian_format_jumlah($bahan_prod); ?></td>
				<td class="text-right"><?php echo persediaan_gen_proses_pembelian_format_jumlah($total10); ?></td>
				<td class="text-center"><span class="badge badge-success">Cocok</span></td>
			</tr>
			<?php } ?>
		</tbody>
		<tfoot class="gen-proses-dt-tfoot">
			<tr>
				<th class="font-weight-bold">TOTAL</th>
				<th colspan="4"></th>
				<th class="text-right font-weight-bold"><?php echo persediaan_gen_proses_pembelian_format_jumlah($sum_jumlah); ?></th>
				<th class="text-right font-weight-bold"><?php echo persediaan_gen_proses_pembelian_format_jumlah($sum_bahan_produksi); ?></th>
				<th colspan="2"></th>
			</tr>
		</tfoot>
	</table>
</div>
