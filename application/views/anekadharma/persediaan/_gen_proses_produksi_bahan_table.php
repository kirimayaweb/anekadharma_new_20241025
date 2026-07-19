<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

$rows = isset($rows) && is_array($rows) ? $rows : array();
$table_id = isset($table_id) ? (string) $table_id : 'table-gen-proses-produksi-bahan';
$empty_msg = isset($empty_msg) ? (string) $empty_msg : 'Tidak ada data bahan produk pada bulan ini.';
$excel_jenis = isset($excel_jenis) ? trim((string) $excel_jenis) : 'proses_produksi_bahan';
$no = 0;
$sum_jumlah = 0.0;
$sum_total_nominal = 0.0;

if (!function_exists('persediaan_gen_proses_pembelian_format_tgl')) {
	$CI = function_exists('get_instance') ? get_instance() : null;
	if ($CI) {
		$CI->load->helper('persediaan_display');
	}
}

$col_jumlah = 7;
$col_total_nominal = 10;
$col_count = 13;
?>
<?php if ($excel_jenis !== '') {
	$this->load->view('anekadharma/persediaan/_gen_proses_excel_btn', array(
		'excel_jenis' => $excel_jenis,
		'excel_title' => isset($excel_title) ? $excel_title : 'Export datatable bahan produk ke Excel',
	));
} ?>
<div class="gen-proses-produksi-dt-wrap persediaan-tab-dt-wrap">
	<table id="<?php echo htmlspecialchars($table_id, ENT_QUOTES, 'UTF-8'); ?>" class="table table-bordered table-striped table-sm gen-proses-produksi-dt display nowrap" style="width:100%"
		data-empty-msg="<?php echo htmlspecialchars($empty_msg, ENT_QUOTES, 'UTF-8'); ?>"
		data-col-jumlah="<?php echo (int) $col_jumlah; ?>"
		data-col-total-nominal="<?php echo (int) $col_total_nominal; ?>"
		data-col-count="<?php echo (int) $col_count; ?>">
		<thead>
			<tr>
				<th>No</th>
				<th>Tgl Transaksi</th>
				<th>Nama Unit</th>
				<th>Nama Produk</th>
				<th>Kode Bahan</th>
				<th>Nama Bahan</th>
				<th>UUID Persediaan Bahan</th>
				<th>Jumlah Bahan</th>
				<th>Satuan</th>
				<th>Harga Satuan (Persediaan)</th>
				<th>Total Nominal</th>
				<th>Sumber Harga</th>
				<th>Match Persediaan</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($rows as $row) {
				$no++;
				$jumlah = isset($row->jumlah_bahan_num) ? $row->jumlah_bahan_num : (isset($row->jumlah_bahan) ? $row->jumlah_bahan : 0);
				$harga = isset($row->harga_satuan_persediaan) ? $row->harga_satuan_persediaan : (isset($row->harga_satuan_bahan) ? $row->harga_satuan_bahan : 0);
				$total_nominal = isset($row->total_nominal_bahan) ? (float) $row->total_nominal_bahan : ((float) persediaan_parse_angka($jumlah) * (float) persediaan_parse_angka($harga));
				$sum_jumlah += (float) persediaan_parse_angka($jumlah);
				$sum_total_nominal += $total_nominal;
				$match = !empty($row->match_persediaan);
			?>
			<tr>
				<td><?php echo (int) $no; ?></td>
				<td><?php echo persediaan_gen_proses_pembelian_format_tgl(isset($row->tgl_transaksi) ? $row->tgl_transaksi : ''); ?></td>
				<td><?php echo htmlspecialchars(isset($row->nama_unit) ? (string) $row->nama_unit : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars(isset($row->nama_produk) ? (string) $row->nama_produk : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars(isset($row->kode_barang_bahan) ? (string) $row->kode_barang_bahan : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars(isset($row->nama_bahan_tampil) ? (string) $row->nama_bahan_tampil : (isset($row->nama_barang_bahan) ? (string) $row->nama_barang_bahan : ''), ENT_QUOTES, 'UTF-8'); ?></td>
				<td><small><?php echo htmlspecialchars(isset($row->uuid_persediaan_bahan) ? (string) $row->uuid_persediaan_bahan : '', ENT_QUOTES, 'UTF-8'); ?></small></td>
				<td class="text-right" data-order="<?php echo htmlspecialchars((string) persediaan_parse_angka($jumlah), ENT_QUOTES, 'UTF-8'); ?>"><?php echo persediaan_gen_proses_pembelian_format_jumlah($jumlah); ?></td>
				<td><?php echo htmlspecialchars(isset($row->satuan_tampil) ? (string) $row->satuan_tampil : (isset($row->satuan_bahan) ? (string) $row->satuan_bahan : ''), ENT_QUOTES, 'UTF-8'); ?></td>
				<td class="text-right" data-order="<?php echo htmlspecialchars((string) persediaan_parse_angka($harga), ENT_QUOTES, 'UTF-8'); ?>"><?php echo persediaan_gen_proses_pembelian_format_nominal($harga); ?></td>
				<td class="text-right" data-order="<?php echo htmlspecialchars((string) $total_nominal, ENT_QUOTES, 'UTF-8'); ?>"><?php echo persediaan_gen_proses_pembelian_format_nominal($total_nominal); ?></td>
				<td><?php echo htmlspecialchars(isset($row->harga_sumber) ? (string) $row->harga_sumber : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td class="text-center">
					<?php if ($match) { ?>
						<span class="badge badge-success">Ada</span>
					<?php } else { ?>
						<span class="badge badge-warning">Tidak ada</span>
					<?php } ?>
				</td>
			</tr>
			<?php } ?>
		</tbody>
		<tfoot class="gen-proses-dt-tfoot">
			<tr>
				<?php for ($c = 0; $c < $col_count; $c++) {
					if ($c === 0) {
						echo '<th class="font-weight-bold">TOTAL</th>';
					} elseif ($c === $col_jumlah) {
						echo '<th class="text-right font-weight-bold">' . persediaan_gen_proses_pembelian_format_jumlah($sum_jumlah) . '</th>';
					} elseif ($c === $col_total_nominal) {
						echo '<th class="text-right font-weight-bold">' . persediaan_gen_proses_pembelian_format_nominal($sum_total_nominal) . '</th>';
					} else {
						echo '<th></th>';
					}
				} ?>
			</tr>
		</tfoot>
	</table>
</div>
