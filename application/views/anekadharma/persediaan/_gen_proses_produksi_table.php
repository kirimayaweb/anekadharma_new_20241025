<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

$rows = isset($rows) && is_array($rows) ? $rows : array();
$table_id = isset($table_id) ? (string) $table_id : 'table-gen-proses-produksi';
$empty_msg = isset($empty_msg) ? (string) $empty_msg : 'Tidak ada data produksi pada bulan ini.';
$no = 0;
$sum_jumlah = 0.0;
$sum_total_nominal = 0.0;

if (!function_exists('persediaan_gen_proses_pembelian_format_tgl')) {
	$CI = function_exists('get_instance') ? get_instance() : null;
	if ($CI) {
		$CI->load->helper('persediaan_display');
	}
}

$col_jumlah = 6;
$col_total_nominal = 9;
$col_count = 11;
$excel_jenis = isset($excel_jenis) ? trim((string) $excel_jenis) : '';
?>
<?php if ($excel_jenis !== '') {
	$this->load->view('anekadharma/persediaan/_gen_proses_excel_btn', array(
		'excel_jenis' => $excel_jenis,
		'excel_title' => isset($excel_title) ? $excel_title : 'Export datatable produksi ke Excel',
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
				<th>SPOP</th>
				<th>Nama Unit</th>
				<th>Kode Barang</th>
				<th>Nama Barang</th>
				<th>Jumlah Produksi</th>
				<th>Satuan</th>
				<th>Harga Satuan</th>
				<th>Total Nominal</th>
				<th>Keterangan</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($rows as $row) {
				$no++;
				$jumlah = isset($row->jumlah_produksi) ? $row->jumlah_produksi : 0;
				$harga_satuan = isset($row->harga_satuan) ? $row->harga_satuan : 0;
				$total_nominal = (float) persediaan_parse_angka($jumlah) * (float) persediaan_parse_angka($harga_satuan);
				$sum_jumlah += (float) persediaan_parse_angka($jumlah);
				$sum_total_nominal += $total_nominal;
				$spop_tampil = '';
				if (function_exists('persediaan_gen_v2_resolve_spop_unit_produk_row')) {
					$CI_tbl = function_exists('get_instance') ? get_instance() : null;
					if ($CI_tbl) {
						$CI_tbl->load->helper('pembelian_persediaan');
						$spop_tampil = persediaan_gen_v2_resolve_spop_unit_produk_row($CI_tbl, $row);
					}
				}
			?>
			<tr>
				<td><?php echo (int) $no; ?></td>
				<td><?php echo persediaan_gen_proses_pembelian_format_tgl(isset($row->tgl_transaksi) ? $row->tgl_transaksi : ''); ?></td>
				<td><?php echo htmlspecialchars($spop_tampil, ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars(isset($row->nama_unit) ? (string) $row->nama_unit : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars(isset($row->kode_barang) ? (string) $row->kode_barang : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars(isset($row->nama_barang) ? (string) $row->nama_barang : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td class="text-right" data-order="<?php echo htmlspecialchars((string) persediaan_parse_angka($jumlah), ENT_QUOTES, 'UTF-8'); ?>"><?php echo persediaan_gen_proses_pembelian_format_jumlah($jumlah); ?></td>
				<td><?php echo htmlspecialchars(isset($row->satuan) ? (string) $row->satuan : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td class="text-right" data-order="<?php echo htmlspecialchars((string) persediaan_parse_angka($harga_satuan), ENT_QUOTES, 'UTF-8'); ?>"><?php echo persediaan_gen_proses_pembelian_format_nominal($harga_satuan); ?></td>
				<td class="text-right" data-order="<?php echo htmlspecialchars((string) $total_nominal, ENT_QUOTES, 'UTF-8'); ?>"><?php echo persediaan_gen_proses_pembelian_format_nominal($total_nominal); ?></td>
				<td><?php echo htmlspecialchars(isset($row->keterangan) ? (string) $row->keterangan : '', ENT_QUOTES, 'UTF-8'); ?></td>
			</tr>
			<?php } ?>
		</tbody>
		<tfoot class="gen-proses-dt-tfoot">
			<tr>
				<?php for ($c = 0; $c < $col_count; $c++) {
					if ($c === $col_jumlah) {
						echo '<th class="text-right font-weight-bold">' . persediaan_gen_proses_pembelian_format_jumlah($sum_jumlah) . '</th>';
					} elseif ($c === $col_total_nominal) {
						echo '<th class="text-right font-weight-bold">' . persediaan_gen_proses_pembelian_format_nominal($sum_total_nominal) . '</th>';
					} elseif ($c === 0) {
						echo '<th class="font-weight-bold">TOTAL</th>';
					} else {
						echo '<th></th>';
					}
				} ?>
			</tr>
		</tfoot>
	</table>
</div>
