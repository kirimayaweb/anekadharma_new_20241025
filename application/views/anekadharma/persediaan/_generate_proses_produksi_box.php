<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

$rekap = isset($rekap) && is_array($rekap) ? $rekap : array();
$bulan_target_label = isset($bulan_target_label) ? (string) $bulan_target_label : '';
$rows_unit_produk = isset($rows_unit_produk) && is_array($rows_unit_produk) ? $rows_unit_produk : array();

$produksi_ok = !empty($rekap['produksi_ok']);
$produksi_badge = $produksi_ok ? 'badge-success' : 'badge-danger';
?>
<div class="gen-proses-produksi-box">
	<div class="gen-proses-rekap-panel mb-4">
		<div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
			<h5 class="gen-proses-title mb-2 mb-md-0">
				<i class="fas fa-industry text-warning mr-2"></i>Verifikasi Data Produksi
				<span class="badge badge-warning ml-2"><?php echo htmlspecialchars($bulan_target_label, ENT_QUOTES, 'UTF-8'); ?></span>
			</h5>
			<p class="text-muted small mb-0 w-100 mt-1">
				Verifikasi <strong>setelah</strong> data <code>sys_unit_produk</code> dimasukkan ke persediaan
				(<code>jumlah_produksi</code> → <code>beli</code>) — terpisah dari verifikasi pembelian di box atas.
			</p>
			<div class="gen-proses-rekap-badges">
				<span class="badge <?php echo $produksi_badge; ?> px-3 py-2 mb-1">
					<i class="fas <?php echo $produksi_ok ? 'fa-check-circle' : 'fa-times-circle'; ?> mr-1"></i>
					Produksi <?php echo $produksi_ok ? 'SESUAI' : 'BELUM SESUAI'; ?>
				</span>
			</div>
		</div>

		<div class="row gen-proses-hero-stats mb-3">
			<div class="col-md-12 mb-3">
				<div class="gen-proses-hero-card gen-proses-hero-qty <?php echo $produksi_ok ? 'is-ok' : 'is-fail'; ?>">
					<div class="gen-proses-hero-label">
						<i class="fas fa-cogs mr-1"></i> Produk Jadi (sys_unit_produk)
						<?php if ($produksi_ok) { ?><i class="fas fa-check-circle text-success gen-proses-hero-check ml-1"></i><?php } ?>
					</div>
					<ul class="gen-proses-check-values mb-2">
						<li>Record <code>sys_unit_produk</code>: <strong><?php echo (int) (isset($rekap['count_unit_produk']) ? $rekap['count_unit_produk'] : 0); ?></strong></li>
						<li>Σ <code>jumlah_produksi</code>: <strong><?php echo htmlspecialchars(isset($rekap['sum_jumlah_produksi_fmt']) ? $rekap['sum_jumlah_produksi_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong></li>
						<li>Σ <code>beli</code> persediaan (hasil produksi): <strong><?php echo htmlspecialchars(isset($rekap['sum_beli_persediaan_fmt']) ? $rekap['sum_beli_persediaan_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong></li>
					</ul>
					<div class="gen-proses-hero-flow">
						<div class="gen-proses-hero-item">
							<span class="gen-proses-hero-key">sys_unit_produk<br/><small class="text-muted">Σ jumlah_produksi</small></span>
							<strong class="gen-proses-hero-val"><?php echo htmlspecialchars(isset($rekap['sum_jumlah_produksi_fmt']) ? $rekap['sum_jumlah_produksi_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong>
						</div>
						<div class="gen-proses-hero-eq">=</div>
						<div class="gen-proses-hero-item">
							<span class="gen-proses-hero-key">persediaan<br/><small class="text-muted">Σ beli produksi</small></span>
							<strong class="gen-proses-hero-val"><?php echo htmlspecialchars(isset($rekap['sum_beli_persediaan_fmt']) ? $rekap['sum_beli_persediaan_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong>
						</div>
					</div>
					<p class="gen-proses-hero-note mb-0">
						<?php if ($produksi_ok) { ?>
							<i class="fas fa-check text-success mr-1"></i>Total jumlah produksi sama dengan total beli persediaan dari proses <strong>sys_unit_produk</strong>.
						<?php } else { ?>
							<i class="fas fa-exclamation-triangle text-warning mr-1"></i>Total jumlah produksi belum sama dengan Σ beli persediaan hasil produksi.
						<?php } ?>
					</p>
				</div>
			</div>
		</div>
	</div>

	<?php $this->load->view('anekadharma/persediaan/_gen_proses_produksi_table', array(
		'rows' => $rows_unit_produk,
		'table_id' => 'table-gen-proses-produksi',
	)); ?>
</div>
