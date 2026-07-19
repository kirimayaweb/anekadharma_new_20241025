<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

$rekap = isset($rekap) && is_array($rekap) ? $rekap : array();
$bulan_target_label = isset($bulan_target_label) ? (string) $bulan_target_label : '';
$rows_unit_produk = isset($rows_unit_produk) && is_array($rows_unit_produk) ? $rows_unit_produk : array();
$rows_bahan_real = isset($rows_bahan_real) && is_array($rows_bahan_real) ? $rows_bahan_real : array();
$rows_bahan = isset($rows_bahan) && is_array($rows_bahan) ? $rows_bahan : array();

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
				Cek data di <code>sys_unit_produk</code> dan <code>sys_unit_produk_bahan</code>.
				Verifikasi <strong>setelah</strong> produk jadi dimasukkan ke persediaan
				(<code>jumlah_produksi</code> → <code>beli</code>). Bahan diambil dari
				<code>persediaan.uuid_persediaan</code> berdasarkan <code>uuid_persediaan_bahan</code>.
			</p>
			<div class="gen-proses-rekap-badges">
				<span class="badge <?php echo $produksi_badge; ?> px-3 py-2 mb-1 mr-1">
					<i class="fas <?php echo $produksi_ok ? 'fa-check-circle' : 'fa-times-circle'; ?> mr-1"></i>
					Produksi <?php echo $produksi_ok ? 'SESUAI' : 'BELUM SESUAI'; ?>
				</span>
				<span class="badge badge-info px-3 py-2 mb-1 mr-1">
					Produk: <?php echo (int) (isset($rekap['count_unit_produk']) ? $rekap['count_unit_produk'] : count($rows_unit_produk)); ?>
				</span>
				<span class="badge badge-secondary px-3 py-2 mb-1">
					Bahan: <?php echo (int) (isset($rekap['count_bahan']) ? $rekap['count_bahan'] : count($rows_bahan)); ?>
				</span>
			</div>
		</div>

		<div class="row gen-proses-hero-stats mb-3">
			<div class="col-md-6 mb-3">
				<div class="gen-proses-hero-card gen-proses-hero-qty <?php echo $produksi_ok ? 'is-ok' : 'is-fail'; ?>">
					<div class="gen-proses-hero-label">
						<i class="fas fa-cogs mr-1"></i> Produk Jadi (sys_unit_produk)
						<?php if ($produksi_ok) { ?><i class="fas fa-check-circle text-success gen-proses-hero-check ml-1"></i><?php } ?>
					</div>
					<ul class="gen-proses-check-values mb-2">
						<li>Record <code>sys_unit_produk</code>: <strong><?php echo (int) (isset($rekap['count_unit_produk']) ? $rekap['count_unit_produk'] : 0); ?></strong></li>
						<li>Σ <code>jumlah_produksi</code>: <strong><?php echo htmlspecialchars(isset($rekap['sum_jumlah_produksi_fmt']) ? $rekap['sum_jumlah_produksi_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong></li>
						<li>Σ <code>beli</code> persediaan (hasil produksi): <strong><?php echo htmlspecialchars(isset($rekap['sum_beli_persediaan_fmt']) ? $rekap['sum_beli_persediaan_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong></li>
						<li>Σ nominal harga jual: <strong><?php echo htmlspecialchars(isset($rekap['sum_nominal_produk_fmt']) ? $rekap['sum_nominal_produk_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong></li>
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
			<div class="col-md-6 mb-3">
				<div class="gen-proses-hero-card gen-proses-hero-qty is-ok">
					<div class="gen-proses-hero-label">
						<i class="fas fa-cubes mr-1"></i> Bahan Produk (sys_unit_produk_bahan)
					</div>
					<ul class="gen-proses-check-values mb-2">
						<li>Record <code>sys_unit_produk_bahan</code>: <strong><?php echo (int) (isset($rekap['count_bahan']) ? $rekap['count_bahan'] : 0); ?></strong></li>
						<li>Σ jumlah bahan: <strong><?php echo htmlspecialchars(isset($rekap['sum_jumlah_bahan_fmt']) ? $rekap['sum_jumlah_bahan_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong></li>
						<li>Σ nominal bahan (dari persediaan): <strong><?php echo htmlspecialchars(isset($rekap['sum_nominal_bahan_fmt']) ? $rekap['sum_nominal_bahan_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong></li>
						<li>Σ bahan real (produk − bahan): <strong><?php echo htmlspecialchars(isset($rekap['sum_bahan_real_fmt']) ? $rekap['sum_bahan_real_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong></li>
					</ul>
					<p class="gen-proses-hero-note mb-0 small">
						Harga bahan diambil dari <code>persediaan.hpp</code> via <code>uuid_persediaan_bahan</code>.
						Jika tidak ketemu, fallback ke <code>harga_satuan_bahan</code> di tabel bahan.
					</p>
				</div>
			</div>
		</div>
	</div>

	<ul class="nav nav-pills gen-proses-produksi-tabs mb-3" id="gen-proses-produksi-tabs" role="tablist">
		<li class="nav-item">
			<a class="nav-link active" id="gen-prod-tab-produk" data-toggle="pill" href="#gen-prod-pane-produk" role="tab">
				<i class="fas fa-box-open text-warning mr-1"></i>Data Produk
				<span class="badge badge-warning ml-1"><?php echo count($rows_unit_produk); ?></span>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" id="gen-prod-tab-bahan-real" data-toggle="pill" href="#gen-prod-pane-bahan-real" role="tab">
				<i class="fas fa-balance-scale text-success mr-1"></i>Data Bahan Real Produk
				<span class="badge badge-success ml-1"><?php echo count($rows_bahan_real); ?></span>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" id="gen-prod-tab-bahan" data-toggle="pill" href="#gen-prod-pane-bahan" role="tab">
				<i class="fas fa-layer-group text-info mr-1"></i>Data Bahan Produk
				<span class="badge badge-info ml-1"><?php echo count($rows_bahan); ?></span>
			</a>
		</li>
	</ul>

	<div class="tab-content gen-proses-produksi-tab-content" id="gen-proses-produksi-tab-content">
		<div class="tab-pane fade show active" id="gen-prod-pane-produk" role="tabpanel">
			<?php $this->load->view('anekadharma/persediaan/_gen_proses_produksi_table', array(
				'rows' => $rows_unit_produk,
				'table_id' => 'table-gen-proses-produksi',
				'excel_jenis' => 'proses_produksi',
				'excel_title' => 'Cetak Data Produk (sys_unit_produk) ke Excel',
			)); ?>
		</div>
		<div class="tab-pane fade" id="gen-prod-pane-bahan-real" role="tabpanel">
			<p class="small text-muted mb-2">
				<strong>Bahan Real</strong> = Nominal harga jual produk − total nominal bahan
				(bahan dari <code>persediaan</code> berdasarkan <code>uuid_persediaan_bahan</code>).
			</p>
			<?php $this->load->view('anekadharma/persediaan/_gen_proses_produksi_bahan_real_table', array(
				'rows' => $rows_bahan_real,
				'table_id' => 'table-gen-proses-produksi-bahan-real',
				'excel_jenis' => 'proses_produksi_bahan_real',
			)); ?>
		</div>
		<div class="tab-pane fade" id="gen-prod-pane-bahan" role="tabpanel">
			<p class="small text-muted mb-2">
				Detail bahan dari <code>sys_unit_produk_bahan</code>. Harga &amp; satuan diprioritaskan dari
				<code>persediaan</code> (match <code>uuid_persediaan</code> = <code>uuid_persediaan_bahan</code>).
			</p>
			<?php $this->load->view('anekadharma/persediaan/_gen_proses_produksi_bahan_table', array(
				'rows' => $rows_bahan,
				'table_id' => 'table-gen-proses-produksi-bahan',
				'excel_jenis' => 'proses_produksi_bahan',
			)); ?>
		</div>
	</div>
</div>
