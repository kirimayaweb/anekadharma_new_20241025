<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

$rekap = isset($rekap) && is_array($rekap) ? $rekap : array();
$bulan_target_label = isset($bulan_target_label) ? (string) $bulan_target_label : '';
$groups_produksi_riil = isset($groups_produksi_riil) && is_array($groups_produksi_riil) ? $groups_produksi_riil : array();
$riil_summary = isset($riil_summary) && is_array($riil_summary) ? $riil_summary : array();
$rows_margin_produk = isset($rows_margin_produk) && is_array($rows_margin_produk) ? $rows_margin_produk : array();
$margin_summary = isset($margin_summary) && is_array($margin_summary) ? $margin_summary : array();

$produksi_ok = !empty($rekap['produksi_ok']);
$produksi_badge = $produksi_ok ? 'badge-success' : 'badge-danger';
$count_produk = (int) (isset($rekap['count_unit_produk']) ? $rekap['count_unit_produk'] : count($groups_produksi_riil));
$count_bahan = (int) (isset($rekap['count_bahan']) ? $rekap['count_bahan'] : 0);
$count_margin = count($rows_margin_produk);
?>
<div class="gen-proses-produksi-box">
	<div class="gen-proses-rekap-panel mb-4">
		<div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
			<h5 class="gen-proses-title mb-2 mb-md-0">
				<i class="fas fa-industry text-warning mr-2"></i>Proses Produksi
				<span class="badge badge-warning ml-2"><?php echo htmlspecialchars($bulan_target_label, ENT_QUOTES, 'UTF-8'); ?></span>
			</h5>
			<p class="text-muted small mb-0 w-100 mt-1">
				Detail produksi riil (produk + bahan + margin) dan pengurangan nilai persediaan bahan.
				Harga bahan dari <code>persediaan.hpp</code> via <code>uuid_persediaan_bahan</code>.
			</p>
			<div class="gen-proses-rekap-badges">
				<span class="badge <?php echo $produksi_badge; ?> px-3 py-2 mb-1 mr-1">
					<i class="fas <?php echo $produksi_ok ? 'fa-check-circle' : 'fa-times-circle'; ?> mr-1"></i>
					Produksi <?php echo $produksi_ok ? 'SESUAI' : 'BELUM SESUAI'; ?>
				</span>
				<span class="badge badge-warning px-3 py-2 mb-1 mr-1">
					Produk: <?php echo $count_produk; ?>
				</span>
				<span class="badge badge-secondary px-3 py-2 mb-1 mr-1">
					Bahan: <?php echo $count_bahan; ?>
				</span>
				<span class="badge badge-info px-3 py-2 mb-1">
					Margin Bahan: <?php echo $count_margin; ?>
				</span>
			</div>
		</div>

		<div class="row gen-proses-hero-stats mb-3">
			<div class="col-md-6 mb-3">
				<div class="gen-proses-hero-card gen-proses-hero-qty <?php echo $produksi_ok ? 'is-ok' : 'is-fail'; ?>">
					<div class="gen-proses-hero-label">
						<i class="fas fa-cogs mr-1"></i> Verifikasi Produk Jadi
						<?php if ($produksi_ok) { ?><i class="fas fa-check-circle text-success gen-proses-hero-check ml-1"></i><?php } ?>
					</div>
					<ul class="gen-proses-check-values mb-2">
						<li>Record produk: <strong><?php echo $count_produk; ?></strong></li>
						<li>Σ jumlah produksi: <strong><?php echo htmlspecialchars(isset($rekap['sum_jumlah_produksi_fmt']) ? $rekap['sum_jumlah_produksi_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong></li>
						<li>Σ beli persediaan: <strong><?php echo htmlspecialchars(isset($rekap['sum_beli_persediaan_fmt']) ? $rekap['sum_beli_persediaan_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong></li>
						<li>Σ margin riil: <strong><?php echo htmlspecialchars(isset($rekap['sum_margin_riil_fmt']) ? $rekap['sum_margin_riil_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong></li>
					</ul>
					<div class="gen-proses-hero-flow">
						<div class="gen-proses-hero-item">
							<span class="gen-proses-hero-key">Σ jumlah_produksi</span>
							<strong class="gen-proses-hero-val"><?php echo htmlspecialchars(isset($rekap['sum_jumlah_produksi_fmt']) ? $rekap['sum_jumlah_produksi_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong>
						</div>
						<div class="gen-proses-hero-eq">=</div>
						<div class="gen-proses-hero-item">
							<span class="gen-proses-hero-key">Σ beli produksi</span>
							<strong class="gen-proses-hero-val"><?php echo htmlspecialchars(isset($rekap['sum_beli_persediaan_fmt']) ? $rekap['sum_beli_persediaan_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 mb-3">
				<div class="gen-proses-hero-card gen-proses-hero-qty is-ok">
					<div class="gen-proses-hero-label">
						<i class="fas fa-warehouse mr-1"></i> Pengurangan Persediaan Bahan
					</div>
					<ul class="gen-proses-check-values mb-2">
						<li>Record bahan: <strong><?php echo $count_bahan; ?></strong></li>
						<li>Σ jumlah bahan: <strong><?php echo htmlspecialchars(isset($rekap['sum_jumlah_bahan_fmt']) ? $rekap['sum_jumlah_bahan_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong></li>
						<li>Σ nominal bahan: <strong><?php echo htmlspecialchars(isset($rekap['sum_nominal_bahan_fmt']) ? $rekap['sum_nominal_bahan_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong></li>
						<li>Σ pengurangan nilai: <strong><?php echo htmlspecialchars(isset($rekap['sum_pengurangan_persediaan_fmt']) ? $rekap['sum_pengurangan_persediaan_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong></li>
					</ul>
					<p class="gen-proses-hero-note mb-0 small">
						Tab <strong>Margin Produk</strong> menampilkan agregasi bahan dengan harga satuan asli dari persediaan.
					</p>
				</div>
			</div>
		</div>
	</div>

	<ul class="nav nav-pills gen-proses-produksi-tabs mb-3" id="gen-proses-produksi-tabs" role="tablist">
		<li class="nav-item">
			<a class="nav-link active" id="gen-prod-tab-riil" data-toggle="pill" href="#gen-prod-pane-riil" role="tab">
				<i class="fas fa-boxes text-warning mr-1"></i>Data Produksi Riil
				<span class="badge badge-warning ml-1"><?php echo count($groups_produksi_riil); ?></span>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" id="gen-prod-tab-margin" data-toggle="pill" href="#gen-prod-pane-margin" role="tab">
				<i class="fas fa-chart-pie text-info mr-1"></i>Margin Produk
				<span class="badge badge-info ml-1"><?php echo $count_margin; ?></span>
			</a>
		</li>
	</ul>

	<div class="tab-content gen-proses-produksi-tab-content" id="gen-proses-produksi-tab-content">
		<div class="tab-pane fade show active" id="gen-prod-pane-riil" role="tabpanel">
			<?php $this->load->view('anekadharma/persediaan/_gen_proses_produksi_riil_panel', array(
				'groups' => $groups_produksi_riil,
				'riil_summary' => $riil_summary,
				'excel_jenis' => 'proses_produksi_riil',
			)); ?>
		</div>
		<div class="tab-pane fade" id="gen-prod-pane-margin" role="tabpanel">
			<?php $this->load->view('anekadharma/persediaan/_gen_proses_produksi_margin_table', array(
				'rows' => $rows_margin_produk,
				'margin_summary' => $margin_summary,
				'table_id' => 'table-gen-proses-produksi-margin',
				'excel_jenis' => 'proses_produksi_margin',
			)); ?>
		</div>
	</div>
</div>
