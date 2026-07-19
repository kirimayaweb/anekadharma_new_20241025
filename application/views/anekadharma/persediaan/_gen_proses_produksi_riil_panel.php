<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

$groups = isset($groups) && is_array($groups) ? $groups : array();
$riil_summary = isset($riil_summary) && is_array($riil_summary) ? $riil_summary : array();
$excel_jenis = isset($excel_jenis) ? trim((string) $excel_jenis) : 'proses_produksi_riil';

$sum_produk = isset($riil_summary['sum_nominal_produk']) ? (float) $riil_summary['sum_nominal_produk'] : 0.0;
$sum_bahan = isset($riil_summary['sum_harga_bahan']) ? (float) $riil_summary['sum_harga_bahan'] : 0.0;
$sum_margin = isset($riil_summary['sum_margin']) ? (float) $riil_summary['sum_margin'] : 0.0;

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
		'excel_title' => 'Cetak Data Produksi Riil ke Excel',
	));
} ?>

<div class="gen-prod-riil-summary row mb-3">
	<div class="col-md-4 mb-2">
		<div class="gen-prod-riil-stat gen-prod-riil-stat-produk">
			<span class="gen-prod-riil-stat-label"><i class="fas fa-tags mr-1"></i>Σ Nominal Produk</span>
			<strong class="gen-prod-riil-stat-val"><?php echo persediaan_gen_proses_pembelian_format_nominal($sum_produk); ?></strong>
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
			<span class="gen-prod-riil-stat-label"><i class="fas fa-chart-line mr-1"></i>Σ Margin</span>
			<strong class="gen-prod-riil-stat-val"><?php echo persediaan_gen_proses_pembelian_format_nominal($sum_margin); ?></strong>
		</div>
	</div>
</div>

<?php if (empty($groups)) { ?>
	<div class="alert alert-light border text-muted text-center py-4 mb-0">
		<i class="fas fa-info-circle mr-1"></i>Tidak ada data produksi riil pada bulan ini.
	</div>
<?php } else { ?>
	<div class="gen-prod-riil-list">
		<?php
		$idx = 0;
		foreach ($groups as $g) {
			$idx++;
			$bahan = isset($g->bahan) && is_array($g->bahan) ? $g->bahan : array();
			$margin_class = ((float) $g->total_margin >= 0) ? 'is-plus' : 'is-minus';
		?>
		<div class="gen-prod-riil-card card mb-3 shadow-sm">
			<div class="card-header gen-prod-riil-card-head">
				<div class="d-flex flex-wrap align-items-start justify-content-between">
					<div>
						<span class="badge badge-dark mr-2">#<?php echo (int) $idx; ?></span>
						<strong class="gen-prod-riil-name"><?php echo htmlspecialchars($g->nama_produk, ENT_QUOTES, 'UTF-8'); ?></strong>
						<span class="badge badge-warning ml-2"><?php echo htmlspecialchars($g->satuan, ENT_QUOTES, 'UTF-8'); ?></span>
						<div class="small text-white-50 mt-1">
							<i class="far fa-calendar-alt mr-1"></i><?php echo persediaan_gen_proses_pembelian_format_tgl($g->tgl_transaksi); ?>
							<?php if ($g->spop !== '') { ?> · SPOP: <strong><?php echo htmlspecialchars($g->spop, ENT_QUOTES, 'UTF-8'); ?></strong><?php } ?>
							<?php if ($g->nama_unit !== '') { ?> · Unit: <?php echo htmlspecialchars($g->nama_unit, ENT_QUOTES, 'UTF-8'); ?><?php } ?>
							<?php if ($g->kode_barang !== '') { ?> · Kode: <?php echo htmlspecialchars($g->kode_barang, ENT_QUOTES, 'UTF-8'); ?><?php } ?>
						</div>
					</div>
					<div class="gen-prod-riil-head-metrics text-right">
						<div class="gen-prod-riil-metric">
							<span>Qty Produksi</span>
							<strong><?php echo persediaan_gen_proses_pembelian_format_jumlah($g->jumlah_produksi); ?></strong>
						</div>
						<div class="gen-prod-riil-metric">
							<span>Harga Produk</span>
							<strong><?php echo persediaan_gen_proses_pembelian_format_nominal($g->harga_produk); ?></strong>
						</div>
						<div class="gen-prod-riil-metric">
							<span>Nominal Produk</span>
							<strong><?php echo persediaan_gen_proses_pembelian_format_nominal($g->nominal_produk); ?></strong>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body p-0">
				<?php if (empty($bahan)) { ?>
					<p class="text-muted small text-center py-3 mb-0">Belum ada bahan terhubung untuk produk ini.</p>
				<?php } else { ?>
					<div class="table-responsive">
						<table class="table table-sm table-hover mb-0 gen-prod-riil-bahan-table">
							<thead>
								<tr>
									<th>No</th>
									<th>Kode Bahan</th>
									<th>Nama Bahan</th>
									<th class="text-right">Jumlah</th>
									<th>Satuan</th>
									<th class="text-right">Harga Satuan Bahan</th>
									<th class="text-right">Total Harga Bahan</th>
									<th class="text-right">Harga Margin</th>
									<th>Sumber</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($bahan as $b) {
									$mcls = ((float) $b->harga_margin >= 0) ? 'text-success' : 'text-danger';
								?>
								<tr>
									<td><?php echo (int) $b->no; ?></td>
									<td><?php echo htmlspecialchars($b->kode_barang_bahan, ENT_QUOTES, 'UTF-8'); ?></td>
									<td>
										<?php echo htmlspecialchars($b->nama_bahan, ENT_QUOTES, 'UTF-8'); ?>
										<?php if (empty($b->match_persediaan)) { ?>
											<span class="badge badge-warning badge-sm ml-1">tanpa persediaan</span>
										<?php } ?>
									</td>
									<td class="text-right"><?php echo persediaan_gen_proses_pembelian_format_jumlah($b->jumlah_bahan); ?></td>
									<td><?php echo htmlspecialchars($b->satuan, ENT_QUOTES, 'UTF-8'); ?></td>
									<td class="text-right"><?php echo persediaan_gen_proses_pembelian_format_nominal($b->harga_satuan_bahan); ?></td>
									<td class="text-right font-weight-bold"><?php echo persediaan_gen_proses_pembelian_format_nominal($b->total_harga_bahan); ?></td>
									<td class="text-right font-weight-bold <?php echo $mcls; ?>" title="(Harga produk − Harga satuan bahan) × Jumlah bahan">
										<?php echo persediaan_gen_proses_pembelian_format_nominal($b->harga_margin); ?>
									</td>
									<td><small class="text-muted"><?php echo htmlspecialchars($b->harga_sumber, ENT_QUOTES, 'UTF-8'); ?></small></td>
								</tr>
								<?php } ?>
							</tbody>
							<tfoot>
								<tr class="gen-prod-riil-total-row <?php echo $margin_class; ?>">
									<th colspan="3">TOTAL PRODUK — <?php echo htmlspecialchars($g->nama_produk, ENT_QUOTES, 'UTF-8'); ?></th>
									<th class="text-right"><?php echo persediaan_gen_proses_pembelian_format_jumlah($g->total_jumlah_bahan); ?></th>
									<th></th>
									<th></th>
									<th class="text-right"><?php echo persediaan_gen_proses_pembelian_format_nominal($g->total_harga_bahan); ?></th>
									<th class="text-right"><?php echo persediaan_gen_proses_pembelian_format_nominal($g->total_margin); ?></th>
									<th><span class="badge badge-light"><?php echo (int) $g->count_bahan; ?> bahan</span></th>
								</tr>
							</tfoot>
						</table>
					</div>
					<div class="gen-prod-riil-formula px-3 py-2 small text-muted">
						<i class="fas fa-info-circle mr-1"></i>
						Margin baris = (<strong>Harga produk</strong> − <strong>Harga satuan bahan</strong>) × <strong>Jumlah bahan</strong>.
						Total margin produk = Σ margin baris bahan.
					</div>
				<?php } ?>
			</div>
		</div>
		<?php } ?>
	</div>
<?php } ?>
