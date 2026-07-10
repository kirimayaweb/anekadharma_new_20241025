<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

$rekap = isset($rekap) && is_array($rekap) ? $rekap : array();
$bulan_sumber_label = isset($bulan_sumber_label) ? (string) $bulan_sumber_label : '';
$bulan_target_label = isset($bulan_target_label) ? (string) $bulan_target_label : '';
$bulan_target = isset($bulan_target) ? (string) $bulan_target : '';
$bulan_sumber = isset($bulan_sumber) ? (string) $bulan_sumber : '';

$rows_sumber_barang = isset($rows_sumber_barang) && is_array($rows_sumber_barang) ? $rows_sumber_barang : array();
$rows_sumber_jasa = isset($rows_sumber_jasa) && is_array($rows_sumber_jasa) ? $rows_sumber_jasa : array();
$rows_target_barang = isset($rows_target_barang) && is_array($rows_target_barang) ? $rows_target_barang : array();
$rows_target_jasa = isset($rows_target_jasa) && is_array($rows_target_jasa) ? $rows_target_jasa : array();
$count_target_copy = isset($count_target_copy) ? (int) $count_target_copy : (int) (isset($rekap['count_target']) ? $rekap['count_target'] : 0);
$copy_verified_at = isset($copy_verified_at) ? trim((string) $copy_verified_at) : '';
$rekap_from_snapshot = !empty($rekap_from_snapshot);

$qty_ok = !empty($rekap['qty_ok']);
$nominal_ok = !empty($rekap['nominal_ok']);
$qty_icon = $qty_ok ? 'fa-check-circle text-success' : 'fa-times-circle text-danger';
$nom_icon = $nominal_ok ? 'fa-check-circle text-success' : 'fa-times-circle text-danger';
$qty_badge = $qty_ok ? 'badge-success' : 'badge-danger';
$nom_badge = $nominal_ok ? 'badge-success' : 'badge-danger';
$qty_label = $qty_ok ? 'Total Jumlah SESUAI' : 'Total Jumlah BELUM SESUAI';
$nom_label = $nominal_ok ? 'Total Nominal SESUAI' : 'Total Nominal BELUM SESUAI';
?>
<div class="gen-proses-persediaan-box">
	<div class="gen-proses-rekap-panel mb-4">
		<div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
			<h5 class="gen-proses-title mb-2 mb-md-0">
				<i class="fas fa-clipboard-check text-success mr-2"></i>Rekap Verifikasi Copy Persediaan
			</h5>
			<p class="text-muted small mb-0 w-100 mt-1">
				Verifikasi <strong>snapshot saat copy</strong> persediaan dari bulan sebelumnya —
				<em>sebelum</em> proses pembelian, pembelian jasa, produksi, dan penjualan.
				<?php if ($rekap_from_snapshot && $copy_verified_at !== '') { ?>
					<br/><span class="text-info"><i class="fas fa-clock mr-1"></i>Snapshot tersimpan: <strong><?php echo htmlspecialchars($copy_verified_at, ENT_QUOTES, 'UTF-8'); ?></strong></span>
				<?php } elseif (!$rekap_from_snapshot) { ?>
					<br/><span class="text-warning"><i class="fas fa-exclamation-triangle mr-1"></i>Snapshot copy belum tersedia — angka rekap dihitung ulang dari data saat ini (bisa tidak akurat setelah penjualan). Jalankan ulang generate untuk snapshot yang benar.</span>
				<?php } ?>
			</p>
			<div class="gen-proses-rekap-badges">
				<span class="badge <?php echo $qty_badge; ?> px-3 py-2 mr-1 mb-1">
					<i class="fas <?php echo $qty_icon; ?> mr-1"></i><?php echo htmlspecialchars($qty_label, ENT_QUOTES, 'UTF-8'); ?>
				</span>
				<span class="badge <?php echo $nom_badge; ?> px-3 py-2 mb-1">
					<i class="fas <?php echo $nom_icon; ?> mr-1"></i><?php echo htmlspecialchars($nom_label, ENT_QUOTES, 'UTF-8'); ?>
				</span>
			</div>
		</div>

		<div class="row gen-proses-hero-stats mb-3">
			<div class="col-md-6 mb-3">
				<div class="gen-proses-hero-card gen-proses-hero-qty <?php echo $qty_ok ? 'is-ok' : 'is-fail'; ?>">
					<div class="gen-proses-hero-label">
						<i class="fas fa-cubes mr-1"></i> Total Jumlah (Qty)
						<?php if ($qty_ok) { ?>
							<i class="fas fa-check-circle text-success gen-proses-hero-check ml-1" title="Qty sesuai"></i>
						<?php } ?>
					</div>
					<div class="gen-proses-hero-flow">
						<div class="gen-proses-hero-item">
							<span class="gen-proses-hero-key">Total_10<br/><small class="text-muted">bulan lalu</small></span>
							<strong class="gen-proses-hero-val"><?php echo htmlspecialchars(isset($rekap['sum_total10_sumber_fmt']) ? $rekap['sum_total10_sumber_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong>
						</div>
						<div class="gen-proses-hero-eq" title="Harus sama">=</div>
						<div class="gen-proses-hero-item">
							<span class="gen-proses-hero-key">SA<br/><small class="text-muted">bulan ini</small></span>
							<strong class="gen-proses-hero-val"><?php echo htmlspecialchars(isset($rekap['sum_sa_target_fmt']) ? $rekap['sum_sa_target_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong>
						</div>
						<div class="gen-proses-hero-eq" title="Harus sama">=</div>
						<div class="gen-proses-hero-item">
							<span class="gen-proses-hero-key">Total_10<br/><small class="text-muted">bulan ini</small></span>
							<strong class="gen-proses-hero-val"><?php echo htmlspecialchars(isset($rekap['sum_total10_target_fmt']) ? $rekap['sum_total10_target_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong>
						</div>
					</div>
					<p class="gen-proses-hero-note mb-0">
						<?php if ($qty_ok) { ?>
							<i class="fas fa-check text-success mr-1"></i>
							Σ <code>total_10</code> bulan lalu = Σ <code>sa</code> bulan ini = Σ <code>total_10</code> bulan ini — stock awal copy sesuai prosedur.
						<?php } else { ?>
							<i class="fas fa-exclamation-triangle text-warning mr-1"></i>
							Ketiga total jumlah di atas belum sama. Periksa datatable di bawah.
						<?php } ?>
					</p>
				</div>
			</div>
			<div class="col-md-6 mb-3">
				<div class="gen-proses-hero-card gen-proses-hero-nom <?php echo $nominal_ok ? 'is-ok' : 'is-fail'; ?>">
					<div class="gen-proses-hero-label">
						<i class="fas fa-money-bill-wave mr-1"></i> Total Nominal (Rp)
						<?php if ($nominal_ok) { ?>
							<i class="fas fa-check-circle text-success gen-proses-hero-check ml-1" title="Nominal sesuai"></i>
						<?php } ?>
					</div>
					<div class="gen-proses-hero-flow">
						<div class="gen-proses-hero-item">
							<span class="gen-proses-hero-key">Total_10 Nmnl<br/><small class="text-muted">bulan lalu</small></span>
							<strong class="gen-proses-hero-val gen-proses-hero-rp"><?php echo htmlspecialchars(isset($rekap['nom_total10_sumber_fmt']) ? $rekap['nom_total10_sumber_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong>
						</div>
						<div class="gen-proses-hero-eq" title="Harus sama">=</div>
						<div class="gen-proses-hero-item">
							<span class="gen-proses-hero-key">SA Nmnl<br/><small class="text-muted">bulan ini</small></span>
							<strong class="gen-proses-hero-val gen-proses-hero-rp"><?php echo htmlspecialchars(isset($rekap['nom_sa_target_fmt']) ? $rekap['nom_sa_target_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong>
						</div>
						<div class="gen-proses-hero-eq" title="Harus sama">=</div>
						<div class="gen-proses-hero-item">
							<span class="gen-proses-hero-key">Total_10 Nmnl<br/><small class="text-muted">bulan ini</small></span>
							<strong class="gen-proses-hero-val gen-proses-hero-rp"><?php echo htmlspecialchars(isset($rekap['nom_total10_target_fmt']) ? $rekap['nom_total10_target_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong>
						</div>
					</div>
					<p class="gen-proses-hero-note mb-0">
						<?php if ($nominal_ok) { ?>
							<i class="fas fa-check text-success mr-1"></i>
							Nominal <code>total_10×hpp</code> bulan lalu = nominal <code>sa×hpp</code> bulan ini = nominal <code>total_10×hpp</code> bulan ini.
						<?php } else { ?>
							<i class="fas fa-exclamation-triangle text-warning mr-1"></i>
							Ketiga total nominal di atas belum sama. Periksa HPP dan kolom SA / Total_10.
						<?php } ?>
					</p>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-6 mb-3">
				<div class="gen-proses-check-card <?php echo $qty_ok ? 'is-ok' : 'is-fail'; ?>">
					<div class="gen-proses-check-head">
						<span class="gen-proses-check-icon <?php echo $qty_ok ? 'text-success' : 'text-danger'; ?>">
							<i class="fas <?php echo $qty_ok ? 'fa-check-circle' : 'fa-times-circle'; ?>"></i>
						</span>
						<strong class="<?php echo $qty_ok ? 'text-success' : 'text-danger'; ?>">
							<?php echo $qty_ok ? 'Cek Jumlah — OK' : 'Cek Jumlah — GAGAL'; ?>
						</strong>
					</div>
					<ul class="gen-proses-check-values mb-0">
						<li>Total_10 bulan lalu (<?php echo htmlspecialchars($bulan_sumber_label, ENT_QUOTES, 'UTF-8'); ?>): <strong><?php echo htmlspecialchars(isset($rekap['sum_total10_sumber_fmt']) ? $rekap['sum_total10_sumber_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong></li>
						<li>SA bulan ini (<?php echo htmlspecialchars($bulan_target_label, ENT_QUOTES, 'UTF-8'); ?>): <strong><?php echo htmlspecialchars(isset($rekap['sum_sa_target_fmt']) ? $rekap['sum_sa_target_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong></li>
						<li>Total_10 bulan ini: <strong><?php echo htmlspecialchars(isset($rekap['sum_total10_target_fmt']) ? $rekap['sum_total10_target_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong></li>
					</ul>
				</div>
			</div>
			<div class="col-lg-6 mb-3">
				<div class="gen-proses-check-card <?php echo $nominal_ok ? 'is-ok' : 'is-fail'; ?>">
					<div class="gen-proses-check-head">
						<span class="gen-proses-check-icon <?php echo $nominal_ok ? 'text-success' : 'text-danger'; ?>">
							<i class="fas <?php echo $nominal_ok ? 'fa-check-circle' : 'fa-times-circle'; ?>"></i>
						</span>
						<strong class="<?php echo $nominal_ok ? 'text-success' : 'text-danger'; ?>">
							<?php echo $nominal_ok ? 'Cek Nominal — OK' : 'Cek Nominal — GAGAL'; ?>
						</strong>
					</div>
					<ul class="gen-proses-check-values mb-0">
						<li>Total_10 nominal bulan lalu: <strong><?php echo htmlspecialchars(isset($rekap['nom_total10_sumber_fmt']) ? $rekap['nom_total10_sumber_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong></li>
						<li>SA nominal bulan ini: <strong><?php echo htmlspecialchars(isset($rekap['nom_sa_target_fmt']) ? $rekap['nom_sa_target_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong></li>
						<li>Total_10 nominal bulan ini: <strong><?php echo htmlspecialchars(isset($rekap['nom_total10_target_fmt']) ? $rekap['nom_total10_target_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="gen-proses-section gen-proses-section-sumber mb-4">
		<div class="gen-proses-section-head">
			<h5 class="gen-proses-section-title mb-1">
				<i class="fas fa-history text-info mr-2"></i>Persediaan Bulan Sebelumnya
				<span class="badge badge-info ml-2"><?php echo htmlspecialchars($bulan_sumber_label, ENT_QUOTES, 'UTF-8'); ?></span>
			</h5>
			<p class="text-muted small mb-0">Data sumber sebelum proses copy — <?php echo (int) count($rows_sumber_barang); ?> barang, <?php echo (int) count($rows_sumber_jasa); ?> jasa (<?php echo (int) (isset($rekap['count_sumber']) ? $rekap['count_sumber'] : 0); ?> record).</p>
		</div>
		<ul class="nav nav-pills gen-proses-subtabs mb-2" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" data-toggle="pill" href="#gen-proses-sumber-barang" role="tab">
					Barang <span class="badge badge-primary"><?php echo (int) count($rows_sumber_barang); ?></span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="pill" href="#gen-proses-sumber-jasa" role="tab">
					Jasa <span class="badge badge-info"><?php echo (int) count($rows_sumber_jasa); ?></span>
				</a>
			</li>
		</ul>
		<div class="tab-content gen-proses-tab-content">
			<div class="tab-pane fade show active" id="gen-proses-sumber-barang" role="tabpanel">
				<?php $this->load->view('anekadharma/persediaan/_persediaan_tab_data_table', array(
					'Persediaan_rows' => $rows_sumber_barang,
					'table_id' => 'table-gen-proses-sumber-barang',
					'bulan_tampil' => $bulan_sumber,
					'tab_mode' => 'barang',
				)); ?>
			</div>
			<div class="tab-pane fade" id="gen-proses-sumber-jasa" role="tabpanel">
				<?php $this->load->view('anekadharma/persediaan/_persediaan_tab_data_table', array(
					'Persediaan_rows' => $rows_sumber_jasa,
					'table_id' => 'table-gen-proses-sumber-jasa',
					'bulan_tampil' => $bulan_sumber,
					'tab_mode' => 'jasa',
				)); ?>
			</div>
		</div>
	</div>

	<div class="gen-proses-section gen-proses-section-target">
		<div class="gen-proses-section-head">
			<h5 class="gen-proses-section-title mb-1">
				<i class="fas fa-calendar-check text-success mr-2"></i>Persediaan Bulan Target — Hasil Copy Saja
				<span class="badge badge-success ml-2"><?php echo htmlspecialchars($bulan_target_label, ENT_QUOTES, 'UTF-8'); ?></span>
			</h5>
			<p class="text-muted small mb-0">Record hasil copy dari bulan sebelumnya (belum termasuk insert pembelian) — <?php echo (int) count($rows_target_barang); ?> barang, <?php echo (int) count($rows_target_jasa); ?> jasa<?php if (!empty($count_target_copy)) { ?> (<?php echo (int) $count_target_copy; ?> record copy)<?php } ?>.</p>
		</div>
		<ul class="nav nav-pills gen-proses-subtabs mb-2" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" data-toggle="pill" href="#gen-proses-target-barang" role="tab">
					Barang <span class="badge badge-primary"><?php echo (int) count($rows_target_barang); ?></span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="pill" href="#gen-proses-target-jasa" role="tab">
					Jasa <span class="badge badge-info"><?php echo (int) count($rows_target_jasa); ?></span>
				</a>
			</li>
		</ul>
		<div class="tab-content gen-proses-tab-content">
			<div class="tab-pane fade show active" id="gen-proses-target-barang" role="tabpanel">
				<?php $this->load->view('anekadharma/persediaan/_persediaan_tab_data_table', array(
					'Persediaan_rows' => $rows_target_barang,
					'table_id' => 'table-gen-proses-target-barang',
					'bulan_tampil' => $bulan_target,
					'tab_mode' => 'barang',
				)); ?>
			</div>
			<div class="tab-pane fade" id="gen-proses-target-jasa" role="tabpanel">
				<?php $this->load->view('anekadharma/persediaan/_persediaan_tab_data_table', array(
					'Persediaan_rows' => $rows_target_jasa,
					'table_id' => 'table-gen-proses-target-jasa',
					'bulan_tampil' => $bulan_target,
					'tab_mode' => 'jasa',
				)); ?>
			</div>
		</div>
	</div>
</div>
