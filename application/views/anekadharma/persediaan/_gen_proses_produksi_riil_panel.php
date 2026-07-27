<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

$rows = isset($rows) && is_array($rows) ? $rows : array();
$riil_summary = isset($riil_summary) && is_array($riil_summary) ? $riil_summary : array();
$table_id = isset($table_id) ? (string) $table_id : 'table-gen-proses-produksi-riil';
$excel_jenis = isset($excel_jenis) ? trim((string) $excel_jenis) : 'proses_produksi_riil';
$empty_msg = 'Tidak ada data produksi riil pada bulan ini (sumber: sys_unit_produk).';

$sum_jumlah = isset($riil_summary['sum_jumlah_produksi']) ? (float) $riil_summary['sum_jumlah_produksi'] : 0.0;
$sum_nominal = isset($riil_summary['sum_nominal_produk']) ? (float) $riil_summary['sum_nominal_produk'] : 0.0;
$sum_bahan = isset($riil_summary['sum_harga_bahan']) ? (float) $riil_summary['sum_harga_bahan'] : 0.0;
$sum_margin = isset($riil_summary['sum_margin']) ? (float) $riil_summary['sum_margin'] : ($sum_nominal - $sum_bahan);

if (!function_exists('persediaan_gen_proses_pembelian_format_tgl')) {
	$CI = function_exists('get_instance') ? get_instance() : null;
	if ($CI) {
		$CI->load->helper('persediaan_display');
	}
}

// No, Tgl, SPOP, Unit, Kode, Nama, Qty, Satuan, Harga Jual, Nominal Jual, Jml Bahan, Harga Bahan, Margin, Bahan
$col_jumlah = 6;
$col_nominal = 9;
$col_bahan = 11;
$col_margin = 12;
$col_count = 14;
$no = 0;
?>
<?php if ($excel_jenis !== '') {
	$this->load->view('anekadharma/persediaan/_gen_proses_excel_btn', array(
		'excel_jenis' => $excel_jenis,
		'excel_title' => 'Cetak Data Produksi Riil ke Excel',
	));
} ?>

<div class="gen-prod-riil-summary row mb-3">
	<div class="col-md-4 mb-2">
		<div class="gen-prod-riil-stat gen-prod-riil-stat-produk">
			<span class="gen-prod-riil-stat-label"><i class="fas fa-tags mr-1"></i>Σ Nominal / Harga Jual</span>
			<strong class="gen-prod-riil-stat-val"><?php echo persediaan_gen_proses_pembelian_format_nominal($sum_nominal); ?></strong>
		</div>
	</div>
	<div class="col-md-4 mb-2">
		<div class="gen-prod-riil-stat gen-prod-riil-stat-bahan">
			<span class="gen-prod-riil-stat-label"><i class="fas fa-cubes mr-1"></i>Σ Harga Bahan</span>
			<strong class="gen-prod-riil-stat-val"><?php echo persediaan_gen_proses_pembelian_format_nominal($sum_bahan); ?></strong>
		</div>
	</div>
	<div class="col-md-4 mb-2">
		<div class="gen-prod-riil-stat gen-prod-riil-stat-margin">
			<span class="gen-prod-riil-stat-label"><i class="fas fa-chart-line mr-1"></i>Σ Margin (Jual − Bahan)</span>
			<strong class="gen-prod-riil-stat-val"><?php echo persediaan_gen_proses_pembelian_format_nominal($sum_margin); ?></strong>
		</div>
	</div>
</div>

<p class="small text-muted mb-2">
	Data produk dari <code>sys_unit_produk</code> (sama halaman Sys Unit Produk).
	<strong>Margin = Nominal jual − Total harga bahan</strong>.
</p>

<div class="gen-proses-produksi-dt-wrap persediaan-tab-dt-wrap">
	<table id="<?php echo htmlspecialchars($table_id, ENT_QUOTES, 'UTF-8'); ?>"
		class="table table-bordered table-striped table-sm gen-proses-produksi-dt gen-prod-riil-dt display nowrap"
		style="width:100%"
		data-empty-msg="<?php echo htmlspecialchars($empty_msg, ENT_QUOTES, 'UTF-8'); ?>"
		data-col-jumlah="<?php echo (int) $col_jumlah; ?>"
		data-col-nominal="<?php echo (int) $col_nominal; ?>"
		data-col-bahan="<?php echo (int) $col_bahan; ?>"
		data-col-margin="<?php echo (int) $col_margin; ?>"
		data-col-count="<?php echo (int) $col_count; ?>">
		<thead>
			<tr>
				<th>No</th>
				<th>Tgl Transaksi</th>
				<th>SPOP</th>
				<th>Nama Unit</th>
				<th>Kode</th>
				<th>Nama Produk</th>
				<th class="text-right">Qty</th>
				<th>Satuan</th>
				<th class="text-right">Harga Jual</th>
				<th class="text-right">Nominal Jual</th>
				<th class="text-center">Jml Bahan</th>
				<th class="text-right">Total Harga Bahan</th>
				<th class="text-right">Margin</th>
				<th>Bahan Digunakan</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($rows as $row) {
				$no++;
				$jumlah = isset($row->jumlah_produksi) ? (float) $row->jumlah_produksi : 0.0;
				$harga = isset($row->harga_produk) ? (float) $row->harga_produk : (isset($row->harga_satuan) ? (float) $row->harga_satuan : 0.0);
				$nominal = isset($row->nominal_produk) ? (float) $row->nominal_produk : ($jumlah * $harga);
				$bahan = isset($row->total_harga_bahan) ? (float) $row->total_harga_bahan : 0.0;
				$margin = isset($row->total_margin) ? (float) $row->total_margin : ($nominal - $bahan);
				$margin_cls = $margin >= 0 ? 'text-success' : 'text-danger';
			?>
			<tr>
				<td><?php echo (int) $no; ?></td>
				<td><?php echo persediaan_gen_proses_pembelian_format_tgl(isset($row->tgl_transaksi) ? $row->tgl_transaksi : ''); ?></td>
				<td><?php echo htmlspecialchars(isset($row->spop) ? (string) $row->spop : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars(isset($row->nama_unit) ? (string) $row->nama_unit : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars(isset($row->kode_barang) ? (string) $row->kode_barang : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td>
					<strong><?php echo htmlspecialchars(isset($row->nama_produk) ? (string) $row->nama_produk : (isset($row->nama_barang) ? (string) $row->nama_barang : ''), ENT_QUOTES, 'UTF-8'); ?></strong>
				</td>
				<td class="text-right" data-order="<?php echo htmlspecialchars((string) $jumlah, ENT_QUOTES, 'UTF-8'); ?>"><?php echo persediaan_gen_proses_pembelian_format_jumlah($jumlah); ?></td>
				<td><?php echo htmlspecialchars(isset($row->satuan) ? (string) $row->satuan : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td class="text-right" data-order="<?php echo htmlspecialchars((string) $harga, ENT_QUOTES, 'UTF-8'); ?>"><?php echo persediaan_gen_proses_pembelian_format_nominal($harga); ?></td>
				<td class="text-right font-weight-bold" data-order="<?php echo htmlspecialchars((string) $nominal, ENT_QUOTES, 'UTF-8'); ?>"><?php echo persediaan_gen_proses_pembelian_format_nominal($nominal); ?></td>
				<td class="text-center">
					<?php if ((int) $row->count_bahan > 0) { ?>
						<span class="badge badge-info"><?php echo (int) $row->count_bahan; ?></span>
					<?php } else { ?>
						<span class="badge badge-secondary">0</span>
					<?php } ?>
				</td>
				<td class="text-right" data-order="<?php echo htmlspecialchars((string) $bahan, ENT_QUOTES, 'UTF-8'); ?>"><?php echo persediaan_gen_proses_pembelian_format_nominal($bahan); ?></td>
				<td class="text-right font-weight-bold <?php echo $margin_cls; ?>" data-order="<?php echo htmlspecialchars((string) $margin, ENT_QUOTES, 'UTF-8'); ?>" title="Nominal jual − Total harga bahan">
					<?php echo persediaan_gen_proses_pembelian_format_nominal($margin); ?>
				</td>
				<td><small class="text-muted"><?php echo htmlspecialchars(isset($row->bahan_ringkas) ? (string) $row->bahan_ringkas : '—', ENT_QUOTES, 'UTF-8'); ?></small></td>
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
					} elseif ($c === $col_nominal) {
						echo '<th class="text-right font-weight-bold">' . persediaan_gen_proses_pembelian_format_nominal($sum_nominal) . '</th>';
					} elseif ($c === $col_bahan) {
						echo '<th class="text-right font-weight-bold">' . persediaan_gen_proses_pembelian_format_nominal($sum_bahan) . '</th>';
					} elseif ($c === $col_margin) {
						echo '<th class="text-right font-weight-bold">' . persediaan_gen_proses_pembelian_format_nominal($sum_margin) . '</th>';
					} else {
						echo '<th></th>';
					}
				} ?>
			</tr>
		</tfoot>
	</table>
</div>
