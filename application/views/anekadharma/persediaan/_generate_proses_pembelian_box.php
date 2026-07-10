<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

$rekap = isset($rekap) && is_array($rekap) ? $rekap : array();
$bulan_target_label = isset($bulan_target_label) ? (string) $bulan_target_label : '';
$rows_pembelian_barang = isset($rows_pembelian_barang) && is_array($rows_pembelian_barang) ? $rows_pembelian_barang : array();
$rows_pembelian_jasa = isset($rows_pembelian_jasa) && is_array($rows_pembelian_jasa) ? $rows_pembelian_jasa : array();

$barang_ok = !empty($rekap['barang_ok']);
$jasa_ok = !empty($rekap['jasa_ok']);
$barang_badge = $barang_ok ? 'badge-success' : 'badge-danger';
$jasa_badge = $jasa_ok ? 'badge-success' : 'badge-danger';
?>
<div class="gen-proses-pembelian-box">
	<div class="gen-proses-rekap-panel mb-4">
		<div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
			<h5 class="gen-proses-title mb-2 mb-md-0">
				<i class="fas fa-shopping-cart text-primary mr-2"></i>Verifikasi Data Pembelian
				<span class="badge badge-primary ml-2"><?php echo htmlspecialchars($bulan_target_label, ENT_QUOTES, 'UTF-8'); ?></span>
			</h5>
			<p class="text-muted small mb-0 w-100 mt-1">
				Verifikasi <strong>setelah</strong> data <code>tbl_pembelian</code> / <code>tbl_pembelian_jasa</code> dimasukkan ke persediaan —
				terpisah dari verifikasi copy persediaan di box atas.
			</p>
			<div class="gen-proses-rekap-badges">
				<span class="badge <?php echo $barang_badge; ?> px-3 py-2 mr-1 mb-1">
					<i class="fas <?php echo $barang_ok ? 'fa-check-circle' : 'fa-times-circle'; ?> mr-1"></i>
					Barang <?php echo $barang_ok ? 'SESUAI' : 'BELUM SESUAI'; ?>
				</span>
				<span class="badge <?php echo $jasa_badge; ?> px-3 py-2 mb-1">
					<i class="fas <?php echo $jasa_ok ? 'fa-check-circle' : 'fa-times-circle'; ?> mr-1"></i>
					Jasa <?php echo $jasa_ok ? 'SESUAI' : 'BELUM SESUAI'; ?>
				</span>
			</div>
		</div>

		<div class="row gen-proses-hero-stats mb-3">
			<div class="col-md-6 mb-3">
				<div class="gen-proses-hero-card gen-proses-hero-qty <?php echo $barang_ok ? 'is-ok' : 'is-fail'; ?>">
					<div class="gen-proses-hero-label">
						<i class="fas fa-box mr-1"></i> Pembelian Barang
						<?php if ($barang_ok) { ?><i class="fas fa-check-circle text-success gen-proses-hero-check ml-1"></i><?php } ?>
					</div>
					<ul class="gen-proses-check-values mb-2">
						<li>Record <code>tbl_pembelian</code>: <strong><?php echo (int) (isset($rekap['count_barang']) ? $rekap['count_barang'] : 0); ?></strong></li>
						<li>Σ <code>jumlah</code> pembelian barang: <strong><?php echo htmlspecialchars(isset($rekap['sum_jumlah_barang_fmt']) ? $rekap['sum_jumlah_barang_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong></li>
						<li>Σ <code>beli</code> persediaan (kategori barang): <strong><?php echo htmlspecialchars(isset($rekap['sum_beli_barang_fmt']) ? $rekap['sum_beli_barang_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong></li>
					</ul>
					<div class="gen-proses-hero-flow">
						<div class="gen-proses-hero-item">
							<span class="gen-proses-hero-key">tbl_pembelian<br/><small class="text-muted">Σ jumlah</small></span>
							<strong class="gen-proses-hero-val"><?php echo htmlspecialchars(isset($rekap['sum_jumlah_barang_fmt']) ? $rekap['sum_jumlah_barang_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong>
						</div>
						<div class="gen-proses-hero-eq">=</div>
						<div class="gen-proses-hero-item">
							<span class="gen-proses-hero-key">persediaan<br/><small class="text-muted">Σ beli barang</small></span>
							<strong class="gen-proses-hero-val"><?php echo htmlspecialchars(isset($rekap['sum_beli_barang_fmt']) ? $rekap['sum_beli_barang_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong>
						</div>
					</div>
					<p class="gen-proses-hero-note mb-0">
						<?php if ($barang_ok) { ?>
							<i class="fas fa-check text-success mr-1"></i>Total jumlah pembelian barang sama dengan total beli persediaan kategori <strong>barang</strong>.
						<?php } else { ?>
							<i class="fas fa-exclamation-triangle text-warning mr-1"></i>Total jumlah pembelian barang belum sama dengan Σ beli persediaan kategori barang.
						<?php } ?>
					</p>
				</div>
			</div>
			<div class="col-md-6 mb-3">
				<div class="gen-proses-hero-card gen-proses-hero-nom <?php echo $jasa_ok ? 'is-ok' : 'is-fail'; ?>">
					<div class="gen-proses-hero-label">
						<i class="fas fa-concierge-bell mr-1"></i> Pembelian Jasa
						<?php if ($jasa_ok) { ?><i class="fas fa-check-circle text-success gen-proses-hero-check ml-1"></i><?php } ?>
					</div>
					<ul class="gen-proses-check-values mb-2">
						<li>Record <code>tbl_pembelian_jasa</code>: <strong><?php echo (int) (isset($rekap['count_jasa']) ? $rekap['count_jasa'] : 0); ?></strong></li>
						<li>Σ <code>jumlah</code> pembelian jasa: <strong><?php echo htmlspecialchars(isset($rekap['sum_jumlah_jasa_fmt']) ? $rekap['sum_jumlah_jasa_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong></li>
						<li>Σ <code>beli</code> persediaan (kategori jasa): <strong><?php echo htmlspecialchars(isset($rekap['sum_beli_jasa_fmt']) ? $rekap['sum_beli_jasa_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong></li>
					</ul>
					<div class="gen-proses-hero-flow">
						<div class="gen-proses-hero-item">
							<span class="gen-proses-hero-key">tbl_pembelian_jasa<br/><small class="text-muted">Σ jumlah</small></span>
							<strong class="gen-proses-hero-val"><?php echo htmlspecialchars(isset($rekap['sum_jumlah_jasa_fmt']) ? $rekap['sum_jumlah_jasa_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong>
						</div>
						<div class="gen-proses-hero-eq">=</div>
						<div class="gen-proses-hero-item">
							<span class="gen-proses-hero-key">persediaan<br/><small class="text-muted">Σ beli jasa</small></span>
							<strong class="gen-proses-hero-val"><?php echo htmlspecialchars(isset($rekap['sum_beli_jasa_fmt']) ? $rekap['sum_beli_jasa_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong>
						</div>
					</div>
					<p class="gen-proses-hero-note mb-0">
						<?php if ($jasa_ok) { ?>
							<i class="fas fa-check text-success mr-1"></i>Total jumlah pembelian jasa sama dengan total beli persediaan kategori <strong>jasa</strong>.
						<?php } else { ?>
							<i class="fas fa-exclamation-triangle text-warning mr-1"></i>Total jumlah pembelian jasa belum sama dengan Σ beli persediaan kategori jasa.
						<?php } ?>
					</p>
				</div>
			</div>
		</div>
	</div>

	<ul class="nav nav-pills gen-proses-subtabs mb-2" role="tablist">
		<li class="nav-item">
			<a class="nav-link active" data-toggle="pill" href="#gen-proses-pembelian-barang" role="tab">
				Barang <span class="badge badge-primary"><?php echo (int) count($rows_pembelian_barang); ?></span>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggle="pill" href="#gen-proses-pembelian-jasa" role="tab">
				Jasa <span class="badge badge-info"><?php echo (int) count($rows_pembelian_jasa); ?></span>
			</a>
		</li>
	</ul>
	<div class="tab-content gen-proses-tab-content">
		<div class="tab-pane fade show active" id="gen-proses-pembelian-barang" role="tabpanel">
			<?php $this->load->view('anekadharma/persediaan/_gen_proses_pembelian_table', array(
				'rows' => $rows_pembelian_barang,
				'table_id' => 'table-gen-proses-pembelian-barang',
				'tab_mode' => 'barang',
			)); ?>
		</div>
		<div class="tab-pane fade" id="gen-proses-pembelian-jasa" role="tabpanel">
			<?php $this->load->view('anekadharma/persediaan/_gen_proses_pembelian_table', array(
				'rows' => $rows_pembelian_jasa,
				'table_id' => 'table-gen-proses-pembelian-jasa',
				'tab_mode' => 'jasa',
			)); ?>
		</div>
	</div>
</div>
