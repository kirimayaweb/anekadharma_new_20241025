<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

$rows = isset($rows) && is_array($rows) ? $rows : array();
$table_id = isset($table_id) ? (string) $table_id : 'table-gen-proses-produksi-bahan-real';
$empty_msg = isset($empty_msg) ? (string) $empty_msg : 'Tidak ada data bahan real produk pada bulan ini.';
$excel_jenis = isset($excel_jenis) ? trim((string) $excel_jenis) : 'proses_produksi_bahan_real';
$no = 0;
$sum_jumlah_produk = 0.0;
$sum_nominal_produk = 0.0;
$sum_jumlah_bahan = 0.0;
$sum_nominal_bahan = 0.0;
$sum_bahan_real = 0.0;

if (!function_exists('persediaan_gen_proses_pembelian_format_tgl')) {
	$CI = function_exists('get_instance') ? get_instance() : null;
	if ($CI) {
		$CI->load->helper('persediaan_display');
	}
}

$col_jumlah_produk = 6;
$col_nominal_produk = 8;
$col_jumlah_bahan = 9;
$col_nominal_bahan = 10;
$col_bahan_real = 11;
$col_count = 13;
?>
<?php if ($excel_jenis !== '') {
	$this->load->view('anekadharma/persediaan/_gen_proses_excel_btn', array(
		'excel_jenis' => $excel_jenis,
		'excel_title' => isset($excel_title) ? $excel_title : 'Export datatable bahan real produk ke Excel',
	));
} ?>
<div class="gen-proses-produksi-dt-wrap persediaan-tab-dt-wrap">
	<table id="<?php echo htmlspecialchars($table_id, ENT_QUOTES, 'UTF-8'); ?>" class="table table-bordered table-striped table-sm gen-proses-produksi-dt display nowrap" style="width:100%"
		data-empty-msg="<?php echo htmlspecialchars($empty_msg, ENT_QUOTES, 'UTF-8'); ?>"
		data-col-jumlah="<?php echo (int) $col_jumlah_produk; ?>"
		data-col-total-nominal="<?php echo (int) $col_bahan_real; ?>"
		data-col-count="<?php echo (int) $col_count; ?>">
		<thead>
			<tr>
				<th>No</th>
				<th>Tgl Transaksi</th>
				<th>SPOP</th>
				<th>Nama Unit</th>
				<th>Kode Barang</th>
				<th>Nama Produk</th>
				<th>Jumlah Produksi</th>
				<th>Harga Jual</th>
				<th>Nominal Produk</th>
				<th>Σ Jumlah Bahan</th>
				<th>Σ Nominal Bahan</th>
				<th>Bahan Real (Produk−Bahan)</th>
				<th>Jml Baris Bahan</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($rows as $row) {
				$no++;
				$jumlah = isset($row->jumlah_produksi_num) ? $row->jumlah_produksi_num : (isset($row->jumlah_produksi) ? $row->jumlah_produksi : 0);
				$harga = isset($row->harga_satuan_num) ? $row->harga_satuan_num : (isset($row->harga_satuan) ? $row->harga_satuan : 0);
				$nominal_produk = isset($row->nominal_produk) ? (float) $row->nominal_produk : ((float) persediaan_parse_angka($jumlah) * (float) persediaan_parse_angka($harga));
				$total_jb = isset($row->total_jumlah_bahan) ? (float) $row->total_jumlah_bahan : 0.0;
				$total_nb = isset($row->total_nominal_bahan) ? (float) $row->total_nominal_bahan : 0.0;
				$bahan_real = isset($row->bahan_real) ? (float) $row->bahan_real : ($nominal_produk - $total_nb);
				$count_bahan = isset($row->count_bahan) ? (int) $row->count_bahan : 0;
				$sum_jumlah_produk += (float) persediaan_parse_angka($jumlah);
				$sum_nominal_produk += $nominal_produk;
				$sum_jumlah_bahan += $total_jb;
				$sum_nominal_bahan += $total_nb;
				$sum_bahan_real += $bahan_real;
				$spop = isset($row->spop_tampil) ? (string) $row->spop_tampil : '';
			?>
			<tr>
				<td><?php echo (int) $no; ?></td>
				<td><?php echo persediaan_gen_proses_pembelian_format_tgl(isset($row->tgl_transaksi) ? $row->tgl_transaksi : ''); ?></td>
				<td><?php echo htmlspecialchars($spop, ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars(isset($row->nama_unit) ? (string) $row->nama_unit : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars(isset($row->kode_barang) ? (string) $row->kode_barang : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars(isset($row->nama_barang) ? (string) $row->nama_barang : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td class="text-right" data-order="<?php echo htmlspecialchars((string) persediaan_parse_angka($jumlah), ENT_QUOTES, 'UTF-8'); ?>"><?php echo persediaan_gen_proses_pembelian_format_jumlah($jumlah); ?></td>
				<td class="text-right" data-order="<?php echo htmlspecialchars((string) persediaan_parse_angka($harga), ENT_QUOTES, 'UTF-8'); ?>"><?php echo persediaan_gen_proses_pembelian_format_nominal($harga); ?></td>
				<td class="text-right" data-order="<?php echo htmlspecialchars((string) $nominal_produk, ENT_QUOTES, 'UTF-8'); ?>"><?php echo persediaan_gen_proses_pembelian_format_nominal($nominal_produk); ?></td>
				<td class="text-right" data-order="<?php echo htmlspecialchars((string) $total_jb, ENT_QUOTES, 'UTF-8'); ?>"><?php echo persediaan_gen_proses_pembelian_format_jumlah($total_jb); ?></td>
				<td class="text-right" data-order="<?php echo htmlspecialchars((string) $total_nb, ENT_QUOTES, 'UTF-8'); ?>"><?php echo persediaan_gen_proses_pembelian_format_nominal($total_nb); ?></td>
				<td class="text-right font-weight-bold <?php echo $bahan_real < 0 ? 'text-danger' : 'text-success'; ?>" data-order="<?php echo htmlspecialchars((string) $bahan_real, ENT_QUOTES, 'UTF-8'); ?>"><?php echo persediaan_gen_proses_pembelian_format_nominal($bahan_real); ?></td>
				<td class="text-center"><?php echo (int) $count_bahan; ?></td>
			</tr>
			<?php } ?>
		</tbody>
		<tfoot class="gen-proses-dt-tfoot">
			<tr>
				<?php for ($c = 0; $c < $col_count; $c++) {
					if ($c === 0) {
						echo '<th class="font-weight-bold">TOTAL</th>';
					} elseif ($c === $col_jumlah_produk) {
						echo '<th class="text-right font-weight-bold">' . persediaan_gen_proses_pembelian_format_jumlah($sum_jumlah_produk) . '</th>';
					} elseif ($c === $col_nominal_produk) {
						echo '<th class="text-right font-weight-bold">' . persediaan_gen_proses_pembelian_format_nominal($sum_nominal_produk) . '</th>';
					} elseif ($c === $col_jumlah_bahan) {
						echo '<th class="text-right font-weight-bold">' . persediaan_gen_proses_pembelian_format_jumlah($sum_jumlah_bahan) . '</th>';
					} elseif ($c === $col_nominal_bahan) {
						echo '<th class="text-right font-weight-bold">' . persediaan_gen_proses_pembelian_format_nominal($sum_nominal_bahan) . '</th>';
					} elseif ($c === $col_bahan_real) {
						echo '<th class="text-right font-weight-bold">' . persediaan_gen_proses_pembelian_format_nominal($sum_bahan_real) . '</th>';
					} else {
						echo '<th></th>';
					}
				} ?>
			</tr>
		</tfoot>
	</table>
</div>
