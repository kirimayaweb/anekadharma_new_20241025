<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

$rekap = isset($rekap) && is_array($rekap) ? $rekap : array();
$bulan_target_label = isset($bulan_target_label) ? (string) $bulan_target_label : '';
$rows_produksi_riil = isset($rows_produksi_riil) && is_array($rows_produksi_riil) ? $rows_produksi_riil : array();
if (empty($rows_produksi_riil) && isset($groups_produksi_riil) && is_array($groups_produksi_riil)) {
	$rows_produksi_riil = $groups_produksi_riil;
}
$riil_summary = isset($riil_summary) && is_array($riil_summary) ? $riil_summary : array();

$produksi_ok = !empty($rekap['produksi_ok']);
$produksi_badge = $produksi_ok ? 'badge-success' : 'badge-danger';
$count_produk = (int) (isset($rekap['count_unit_produk']) ? $rekap['count_unit_produk'] : count($rows_produksi_riil));
$count_bahan = (int) (isset($rekap['count_bahan']) ? $rekap['count_bahan'] : 0);
$count_bahan_tidak_ada = (int) (isset($rekap['count_bahan_tidak_ada']) ? $rekap['count_bahan_tidak_ada'] : 0);
$count_bahan_update = (int) (isset($rekap['count_bahan_update']) ? $rekap['count_bahan_update'] : 0);
$rows_bahan_update = isset($rows_bahan_update) && is_array($rows_bahan_update) ? $rows_bahan_update : array();
$rows_bahan_tidak_ada = isset($rows_bahan_tidak_ada) && is_array($rows_bahan_tidak_ada) ? $rows_bahan_tidak_ada : array();
$sum_bahan_produksi_pers_fmt = isset($rekap['sum_bahan_produksi_persediaan_fmt']) ? $rekap['sum_bahan_produksi_persediaan_fmt'] : '0';

$sum_nominal_fmt = isset($rekap['sum_nominal_produk_riil_fmt'])
	? $rekap['sum_nominal_produk_riil_fmt']
	: (isset($riil_summary['sum_nominal_produk']) ? persediaan_format_angka_tampil($riil_summary['sum_nominal_produk']) : '0');
$sum_bahan_fmt = isset($rekap['sum_harga_bahan_riil_fmt'])
	? $rekap['sum_harga_bahan_riil_fmt']
	: (isset($riil_summary['sum_harga_bahan']) ? persediaan_format_angka_tampil($riil_summary['sum_harga_bahan']) : '0');
$sum_margin_fmt = isset($rekap['sum_margin_riil_fmt'])
	? $rekap['sum_margin_riil_fmt']
	: (isset($riil_summary['sum_margin']) ? persediaan_format_angka_tampil($riil_summary['sum_margin']) : '0');
?>
<div class="gen-proses-produksi-box">
	<div class="gen-proses-rekap-panel mb-4">
		<div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
			<h5 class="gen-proses-title mb-2 mb-md-0">
				<i class="fas fa-industry text-warning mr-2"></i>Proses Produksi
				<span class="badge badge-warning ml-2"><?php echo htmlspecialchars($bulan_target_label, ENT_QUOTES, 'UTF-8'); ?></span>
			</h5>
			<p class="text-muted small mb-0 w-100 mt-1">
				Verifikasi produk jadi vs persediaan, plus ringkasan produksi riil (harga jual, harga bahan, margin).
				Sumber produk sama dengan halaman <strong>Sys Unit Produk</strong> untuk bulan yang sama.
			</p>
			<div class="gen-proses-rekap-badges">
				<span class="badge <?php echo $produksi_badge; ?> px-3 py-2 mb-1 mr-1">
					<i class="fas <?php echo $produksi_ok ? 'fa-check-circle' : 'fa-times-circle'; ?> mr-1"></i>
					Produksi <?php echo $produksi_ok ? 'SESUAI' : 'BELUM SESUAI'; ?>
				</span>
				<span class="badge badge-warning px-3 py-2 mb-1 mr-1">
					Produk: <?php echo $count_produk; ?>
				</span>
				<span class="badge badge-secondary px-3 py-2 mb-1">
					Bahan: <?php echo $count_bahan; ?>
				</span>
				<span class="badge badge-success px-3 py-2 mb-1">
					Update bahan: <?php echo $count_bahan_update; ?>
				</span>
				<?php if ($count_bahan_tidak_ada > 0) { ?>
				<span class="badge badge-danger px-3 py-2 mb-1">
					Tidak ada di persediaan: <?php echo $count_bahan_tidak_ada; ?>
				</span>
				<?php } ?>
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
						<li>Σ margin (jual − bahan): <strong><?php echo htmlspecialchars($sum_margin_fmt, ENT_QUOTES, 'UTF-8'); ?></strong></li>
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
						<i class="fas fa-balance-scale mr-1"></i> Ringkasan Margin Produksi
					</div>
					<ul class="gen-proses-check-values mb-2">
						<li>Σ harga jual / nominal: <strong><?php echo htmlspecialchars($sum_nominal_fmt, ENT_QUOTES, 'UTF-8'); ?></strong></li>
						<li>Σ harga bahan: <strong><?php echo htmlspecialchars($sum_bahan_fmt, ENT_QUOTES, 'UTF-8'); ?></strong></li>
						<li>Σ margin: <strong><?php echo htmlspecialchars($sum_margin_fmt, ENT_QUOTES, 'UTF-8'); ?></strong></li>
						<li>Record bahan: <strong><?php echo $count_bahan; ?></strong></li>
						<li>Σ bahan_produksi persediaan: <strong><?php echo htmlspecialchars($sum_bahan_produksi_pers_fmt, ENT_QUOTES, 'UTF-8'); ?></strong></li>
						<li>Cocok / tidak cocok: <strong><?php echo $count_bahan_update; ?></strong> / <strong class="<?php echo $count_bahan_tidak_ada > 0 ? 'text-danger' : ''; ?>"><?php echo $count_bahan_tidak_ada; ?></strong></li>
					</ul>
					<p class="gen-proses-hero-note mb-0 small">
						<strong>Margin = Harga jual − Harga bahan</strong> (bukan harga satuan × jumlah bahan).
					</p>
				</div>
			</div>
		</div>
	</div>

	<ul class="nav nav-pills gen-proses-produksi-tabs mb-3" id="gen-proses-produksi-tabs" role="tablist">
		<li class="nav-item">
			<a class="nav-link active" id="gen-prod-tab-riil" data-toggle="pill" href="#gen-prod-pane-riil" role="tab">
				<i class="fas fa-boxes text-warning mr-1"></i>Data Produksi Riil
				<span class="badge badge-warning ml-1"><?php echo count($rows_produksi_riil); ?></span>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" id="gen-prod-tab-bahan-update" data-toggle="pill" href="#gen-prod-pane-bahan-update" role="tab">
				<i class="fas fa-check-circle text-success mr-1"></i>Update Bahan Produksi
				<span class="badge badge-success ml-1"><?php echo $count_bahan_update; ?></span>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" id="gen-prod-tab-bahan-tidak-ada" data-toggle="pill" href="#gen-prod-pane-bahan-tidak-ada" role="tab">
				<i class="fas fa-exclamation-triangle text-danger mr-1"></i>Record Bahan Produksi Tidak Ada di Persediaan
				<span class="badge badge-danger ml-1"><?php echo $count_bahan_tidak_ada; ?></span>
			</a>
		</li>
	</ul>

	<div class="tab-content gen-proses-produksi-tab-content" id="gen-proses-produksi-tab-content">
		<div class="tab-pane fade show active" id="gen-prod-pane-riil" role="tabpanel">
			<?php $this->load->view('anekadharma/persediaan/_gen_proses_produksi_riil_panel', array(
				'rows' => $rows_produksi_riil,
				'riil_summary' => $riil_summary,
				'table_id' => 'table-gen-proses-produksi-riil',
				'excel_jenis' => 'proses_produksi_riil',
			)); ?>
		</div>
		<div class="tab-pane fade" id="gen-prod-pane-bahan-update" role="tabpanel">
			<?php $this->load->view('anekadharma/persediaan/_gen_proses_produksi_bahan_update_table', array(
				'rows' => $rows_bahan_update,
				'table_id' => 'table-gen-proses-produksi-bahan-update',
				'excel_jenis' => 'proses_produksi_bahan_update',
			)); ?>
		</div>
		<div class="tab-pane fade" id="gen-prod-pane-bahan-tidak-ada" role="tabpanel">
			<?php $this->load->view('anekadharma/persediaan/_gen_proses_produksi_bahan_tidak_ada_table', array(
				'rows' => $rows_bahan_tidak_ada,
				'table_id' => 'table-gen-proses-produksi-bahan-tidak-ada',
				'excel_jenis' => 'proses_produksi_bahan_tidak_ada',
			)); ?>
		</div>
	</div>
</div>
