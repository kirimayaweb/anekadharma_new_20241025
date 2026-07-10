<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

$rekap = isset($rekap) && is_array($rekap) ? $rekap : array();
$bulan_sumber_label = isset($bulan_sumber_label) ? (string) $bulan_sumber_label : '';
$bulan_target_label = isset($bulan_target_label) ? (string) $bulan_target_label : '';
$bulan_target = isset($bulan_target) ? (string) $bulan_target : '';

$rows_target_barang = isset($rows_target_barang) && is_array($rows_target_barang) ? $rows_target_barang : array();
$rows_target_jasa = isset($rows_target_jasa) && is_array($rows_target_jasa) ? $rows_target_jasa : array();
$count_target_all = isset($count_target_all) ? (int) $count_target_all : (int) (isset($rekap['count_target']) ? $rekap['count_target'] : 0);

$rekap_pembelian = isset($rekap['rekap_pembelian']) && is_array($rekap['rekap_pembelian']) ? $rekap['rekap_pembelian'] : array();
$rekap_produksi = isset($rekap['rekap_produksi']) && is_array($rekap['rekap_produksi']) ? $rekap['rekap_produksi'] : array();
$rekap_penjualan = isset($rekap['rekap_penjualan']) && is_array($rekap['rekap_penjualan']) ? $rekap['rekap_penjualan'] : array();

$all_ok = !empty($rekap['all_ok']);
$sa_ok = !empty($rekap['sa_ok']);
$saldo_ok = !empty($rekap['saldo_ok']);
$sa_nominal_ok = !empty($rekap['sa_nominal_ok']);
$saldo_nominal_ok = !empty($rekap['saldo_nominal_ok']);
$pembelian_ok = !empty($rekap['pembelian_ok']);
$produksi_ok = !empty($rekap['produksi_ok']);
$penjualan_ok = !empty($rekap['penjualan_ok']);

$all_badge = $all_ok ? 'badge-success' : 'badge-danger';
$all_icon = $all_ok ? 'fa-check-circle' : 'fa-times-circle';
$all_label = $all_ok ? 'Verifikasi Lengkap OK' : 'Verifikasi Lengkap BELUM SESUAI';
?>
<div class="gen-proses-persediaan-full-box">
	<div class="gen-proses-rekap-panel mb-4">
		<div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
			<h5 class="gen-proses-title mb-2 mb-md-0">
				<i class="fas fa-clipboard-list text-purple mr-2"></i>Rekap Verifikasi Persediaan — Setelah Semua Proses
			</h5>
			<p class="text-muted small mb-0 w-100 mt-1">
				Verifikasi persediaan bulan target <strong>setelah</strong> copy, pembelian, pembelian jasa, produksi, dan penjualan diproses.
				Data diambil langsung dari database saat ini.
			</p>
			<div class="gen-proses-rekap-badges">
				<span class="badge <?php echo $all_badge; ?> px-3 py-2 mb-1">
					<i class="fas <?php echo $all_icon; ?> mr-1"></i><?php echo htmlspecialchars($all_label, ENT_QUOTES, 'UTF-8'); ?>
				</span>
			</div>
		</div>

		<div class="row gen-proses-hero-stats mb-3">
			<div class="col-md-6 mb-3">
				<div class="gen-proses-hero-card gen-proses-hero-qty <?php echo $sa_ok ? 'is-ok' : 'is-fail'; ?>">
					<div class="gen-proses-hero-label">
						<i class="fas fa-cubes mr-1"></i> Stock Awal (Qty)
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
					</div>
					<p class="gen-proses-hero-note mb-0">
						<?php if ($sa_ok) { ?>
							<i class="fas fa-check text-success mr-1"></i>Stock awal copy masih sesuai (Σ total_10 bulan lalu = Σ SA bulan ini).
						<?php } else { ?>
							<i class="fas fa-exclamation-triangle text-warning mr-1"></i>Stock awal tidak sesuai — periksa kolom SA vs copy.
						<?php } ?>
					</p>
				</div>
			</div>
			<div class="col-md-6 mb-3">
				<div class="gen-proses-hero-card gen-proses-hero-nom <?php echo $sa_nominal_ok ? 'is-ok' : 'is-fail'; ?>">
					<div class="gen-proses-hero-label">
						<i class="fas fa-money-bill-wave mr-1"></i> Stock Awal (Nominal)
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
					</div>
					<p class="gen-proses-hero-note mb-0">
						<?php if ($sa_nominal_ok) { ?>
							<i class="fas fa-check text-success mr-1"></i>Nominal stock awal sesuai.
						<?php } else { ?>
							<i class="fas fa-exclamation-triangle text-warning mr-1"></i>Nominal stock awal belum sesuai.
						<?php } ?>
					</p>
				</div>
			</div>
		</div>

		<div class="row gen-proses-hero-stats mb-3">
			<div class="col-md-12 mb-3">
				<div class="gen-proses-hero-card gen-proses-hero-qty <?php echo $saldo_ok ? 'is-ok' : 'is-fail'; ?>">
					<div class="gen-proses-hero-label">
						<i class="fas fa-balance-scale mr-1"></i> Saldo Akhir (Qty) — SA + Beli − Penjualan − Pecah − Bahan = Total_10
					</div>
					<div class="gen-proses-hero-flow gen-proses-hero-flow-wrap">
						<div class="gen-proses-hero-item">
							<span class="gen-proses-hero-key">SA</span>
							<strong class="gen-proses-hero-val"><?php echo htmlspecialchars(isset($rekap['sum_sa_target_fmt']) ? $rekap['sum_sa_target_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong>
						</div>
						<div class="gen-proses-hero-eq">+</div>
						<div class="gen-proses-hero-item">
							<span class="gen-proses-hero-key">Beli</span>
							<strong class="gen-proses-hero-val"><?php echo htmlspecialchars(isset($rekap['sum_beli_fmt']) ? $rekap['sum_beli_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong>
						</div>
						<div class="gen-proses-hero-eq">−</div>
						<div class="gen-proses-hero-item">
							<span class="gen-proses-hero-key">Penjualan</span>
							<strong class="gen-proses-hero-val"><?php echo htmlspecialchars(isset($rekap['sum_penjualan_fmt']) ? $rekap['sum_penjualan_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong>
						</div>
						<div class="gen-proses-hero-eq">−</div>
						<div class="gen-proses-hero-item">
							<span class="gen-proses-hero-key">Pecah</span>
							<strong class="gen-proses-hero-val"><?php echo htmlspecialchars(isset($rekap['sum_pecah_satuan_fmt']) ? $rekap['sum_pecah_satuan_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong>
						</div>
						<div class="gen-proses-hero-eq">−</div>
						<div class="gen-proses-hero-item">
							<span class="gen-proses-hero-key">Bahan Prod.</span>
							<strong class="gen-proses-hero-val"><?php echo htmlspecialchars(isset($rekap['sum_bahan_produksi_fmt']) ? $rekap['sum_bahan_produksi_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong>
						</div>
						<div class="gen-proses-hero-eq">=</div>
						<div class="gen-proses-hero-item">
							<span class="gen-proses-hero-key">Total_10<br/><small class="text-muted">aktual</small></span>
							<strong class="gen-proses-hero-val"><?php echo htmlspecialchars(isset($rekap['sum_total10_target_fmt']) ? $rekap['sum_total10_target_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong>
						</div>
					</div>
					<p class="gen-proses-hero-note mb-0">
						<?php if ($saldo_ok) { ?>
							<i class="fas fa-check text-success mr-1"></i>Saldo akhir sesuai — diharapkan <strong><?php echo htmlspecialchars(isset($rekap['expected_total10_fmt']) ? $rekap['expected_total10_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong>.
						<?php } else { ?>
							<i class="fas fa-exclamation-triangle text-warning mr-1"></i>Saldo tidak balance — diharapkan <strong><?php echo htmlspecialchars(isset($rekap['expected_total10_fmt']) ? $rekap['expected_total10_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong>, aktual <strong><?php echo htmlspecialchars(isset($rekap['sum_total10_target_fmt']) ? $rekap['sum_total10_target_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong>.
						<?php } ?>
					</p>
				</div>
			</div>
		</div>

		<div class="row mb-3">
			<div class="col-md-4 mb-3">
				<div class="gen-proses-check-card <?php echo $pembelian_ok ? 'is-ok' : 'is-fail'; ?>">
					<div class="gen-proses-check-head">
						<span class="gen-proses-check-icon <?php echo $pembelian_ok ? 'text-success' : 'text-danger'; ?>">
							<i class="fas <?php echo $pembelian_ok ? 'fa-check-circle' : 'fa-times-circle'; ?>"></i>
						</span>
						<strong>Pembelian — <?php echo $pembelian_ok ? 'OK' : 'GAGAL'; ?></strong>
					</div>
					<ul class="gen-proses-check-values mb-0 small">
						<li>Barang: jumlah tbl_pembelian <strong><?php echo htmlspecialchars(isset($rekap_pembelian['sum_jumlah_barang_fmt']) ? $rekap_pembelian['sum_jumlah_barang_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong> = beli persediaan <strong><?php echo htmlspecialchars(isset($rekap_pembelian['sum_beli_barang_fmt']) ? $rekap_pembelian['sum_beli_barang_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong></li>
						<li>Jasa: jumlah tbl_pembelian_jasa <strong><?php echo htmlspecialchars(isset($rekap_pembelian['sum_jumlah_jasa_fmt']) ? $rekap_pembelian['sum_jumlah_jasa_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong> = beli persediaan <strong><?php echo htmlspecialchars(isset($rekap_pembelian['sum_beli_jasa_fmt']) ? $rekap_pembelian['sum_beli_jasa_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong></li>
					</ul>
				</div>
			</div>
			<div class="col-md-4 mb-3">
				<div class="gen-proses-check-card <?php echo $produksi_ok ? 'is-ok' : 'is-fail'; ?>">
					<div class="gen-proses-check-head">
						<span class="gen-proses-check-icon <?php echo $produksi_ok ? 'text-success' : 'text-danger'; ?>">
							<i class="fas <?php echo $produksi_ok ? 'fa-check-circle' : 'fa-times-circle'; ?>"></i>
						</span>
						<strong>Produksi — <?php echo $produksi_ok ? 'OK' : 'GAGAL'; ?></strong>
					</div>
					<ul class="gen-proses-check-values mb-0 small">
						<li>Jumlah produksi <strong><?php echo htmlspecialchars(isset($rekap_produksi['sum_jumlah_produksi_fmt']) ? $rekap_produksi['sum_jumlah_produksi_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong> = beli persediaan unit <strong><?php echo htmlspecialchars(isset($rekap_produksi['sum_beli_persediaan_fmt']) ? $rekap_produksi['sum_beli_persediaan_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong></li>
					</ul>
				</div>
			</div>
			<div class="col-md-4 mb-3">
				<div class="gen-proses-check-card <?php echo $penjualan_ok ? 'is-ok' : 'is-fail'; ?>">
					<div class="gen-proses-check-head">
						<span class="gen-proses-check-icon <?php echo $penjualan_ok ? 'text-success' : 'text-danger'; ?>">
							<i class="fas <?php echo $penjualan_ok ? 'fa-check-circle' : 'fa-times-circle'; ?>"></i>
						</span>
						<strong>Penjualan — <?php echo $penjualan_ok ? 'OK' : 'GAGAL'; ?></strong>
					</div>
					<ul class="gen-proses-check-values mb-0 small">
						<li>Jumlah tbl_penjualan <strong><?php echo htmlspecialchars(isset($rekap_penjualan['sum_jumlah_penjualan_fmt']) ? $rekap_penjualan['sum_jumlah_penjualan_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong> = kolom penjualan persediaan <strong><?php echo htmlspecialchars(isset($rekap_penjualan['sum_penjualan_persediaan_fmt']) ? $rekap_penjualan['sum_penjualan_persediaan_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="gen-proses-section gen-proses-section-target">
		<div class="gen-proses-section-head">
			<h5 class="gen-proses-section-title mb-1">
				<i class="fas fa-database text-purple mr-2"></i>Persediaan Bulan Target — Semua Record
				<span class="badge badge-secondary ml-2"><?php echo htmlspecialchars($bulan_target_label, ENT_QUOTES, 'UTF-8'); ?></span>
			</h5>
			<p class="text-muted small mb-0">Seluruh record persediaan bulan target setelah semua proses — <?php echo (int) count($rows_target_barang); ?> barang, <?php echo (int) count($rows_target_jasa); ?> jasa<?php if ($count_target_all > 0) { ?> (<?php echo (int) $count_target_all; ?> record)<?php } ?>.</p>
		</div>
		<ul class="nav nav-pills gen-proses-subtabs mb-2" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" data-toggle="pill" href="#gen-proses-full-target-barang" role="tab">
					Barang <span class="badge badge-primary"><?php echo (int) count($rows_target_barang); ?></span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="pill" href="#gen-proses-full-target-jasa" role="tab">
					Jasa <span class="badge badge-info"><?php echo (int) count($rows_target_jasa); ?></span>
				</a>
			</li>
		</ul>
		<div class="tab-content gen-proses-tab-content">
			<div class="tab-pane fade show active" id="gen-proses-full-target-barang" role="tabpanel">
				<?php $this->load->view('anekadharma/persediaan/_persediaan_tab_data_table', array(
					'Persediaan_rows' => $rows_target_barang,
					'table_id' => 'table-gen-proses-full-target-barang',
					'bulan_tampil' => $bulan_target,
					'tab_mode' => 'barang',
				)); ?>
			</div>
			<div class="tab-pane fade" id="gen-proses-full-target-jasa" role="tabpanel">
				<?php $this->load->view('anekadharma/persediaan/_persediaan_tab_data_table', array(
					'Persediaan_rows' => $rows_target_jasa,
					'table_id' => 'table-gen-proses-full-target-jasa',
					'bulan_tampil' => $bulan_target,
					'tab_mode' => 'jasa',
				)); ?>
			</div>
		</div>
	</div>
</div>
