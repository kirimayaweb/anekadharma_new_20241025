<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

$rekap = isset($rekap) && is_array($rekap) ? $rekap : array();
$bulan_target_label = isset($bulan_target_label) ? (string) $bulan_target_label : '';
$rows_masuk = isset($rows_masuk) && is_array($rows_masuk) ? $rows_masuk : array();
$rows_tidak_masuk = isset($rows_tidak_masuk) && is_array($rows_tidak_masuk) ? $rows_tidak_masuk : array();
$rows_manual = isset($rows_manual) && is_array($rows_manual) ? $rows_manual : array();

$penjualan_ok = !empty($rekap['penjualan_ok']);
$penjualan_badge = $penjualan_ok ? 'badge-success' : 'badge-warning';
$has_masalah = ((int) (isset($rekap['count_tidak_masuk']) ? $rekap['count_tidak_masuk'] : 0) > 0)
	|| ((int) (isset($rekap['count_manual']) ? $rekap['count_manual'] : 0) > 0);
?>
<div class="gen-proses-penjualan-box">
	<div class="gen-proses-rekap-panel mb-4">
		<div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
			<h5 class="gen-proses-title mb-2 mb-md-0">
				<i class="fas fa-cash-register text-info mr-2"></i>Verifikasi Data Penjualan
				<span class="badge badge-info ml-2"><?php echo htmlspecialchars($bulan_target_label, ENT_QUOTES, 'UTF-8'); ?></span>
			</h5>
			<p class="text-muted small mb-0 w-100 mt-1">
				Verifikasi <strong>setelah</strong> data <code>tbl_penjualan</code> diproses ke persediaan
				(<code>jumlah</code> → kolom <code>unit</code> sesuai nama unit + field <code>penjualan</code>) —
				terpisah dari verifikasi produksi di box atas.
			</p>
			<div class="gen-proses-rekap-badges">
				<span class="badge <?php echo $penjualan_badge; ?> px-3 py-2 mr-1 mb-1">
					<i class="fas <?php echo $penjualan_ok ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> mr-1"></i>
					Total Penjualan <?php echo $penjualan_ok ? 'SESUAI' : 'BELUM SESUAI'; ?>
				</span>
				<span class="badge badge-success px-3 py-2 mr-1 mb-1">
					<i class="fas fa-check mr-1"></i>Masuk: <?php echo (int) (isset($rekap['count_masuk']) ? $rekap['count_masuk'] : 0); ?>
				</span>
				<span class="badge badge-danger px-3 py-2 mr-1 mb-1">
					<i class="fas fa-times mr-1"></i>Tidak Masuk: <?php echo (int) (isset($rekap['count_tidak_masuk']) ? $rekap['count_tidak_masuk'] : 0); ?>
				</span>
				<span class="badge badge-warning px-3 py-2 mb-1">
					<i class="fas fa-user-check mr-1"></i>Manual: <?php echo (int) (isset($rekap['count_manual']) ? $rekap['count_manual'] : 0); ?>
				</span>
			</div>
		</div>

		<div class="row gen-proses-hero-stats mb-3">
			<div class="col-md-4 mb-3">
				<div class="gen-proses-hero-card gen-proses-hero-qty <?php echo $penjualan_ok ? 'is-ok' : 'is-fail'; ?>">
					<div class="gen-proses-hero-label">
						<i class="fas fa-balance-scale mr-1"></i> Rekap Total
					</div>
					<ul class="gen-proses-check-values mb-2">
						<li>Record <code>tbl_penjualan</code>: <strong><?php echo (int) (isset($rekap['count_penjualan']) ? $rekap['count_penjualan'] : 0); ?></strong></li>
						<li>Σ <code>jumlah</code> penjualan: <strong><?php echo htmlspecialchars(isset($rekap['sum_jumlah_penjualan_fmt']) ? $rekap['sum_jumlah_penjualan_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong></li>
						<li>Σ <code>penjualan</code> persediaan: <strong><?php echo htmlspecialchars(isset($rekap['sum_penjualan_persediaan_fmt']) ? $rekap['sum_penjualan_persediaan_fmt'] : '0', ENT_QUOTES, 'UTF-8'); ?></strong></li>
					</ul>
					<p class="gen-proses-hero-note mb-0">
						<?php if ($penjualan_ok) { ?>
							<i class="fas fa-check text-success mr-1"></i>Total jumlah penjualan sama dengan Σ penjualan di persediaan bulan ini.
						<?php } else { ?>
							<i class="fas fa-exclamation-triangle text-warning mr-1"></i>Total jumlah penjualan belum sama dengan Σ penjualan persediaan — cek tab Tidak Masuk / Manual.
						<?php } ?>
					</p>
				</div>
			</div>
			<div class="col-md-4 mb-3">
				<div class="gen-proses-hero-card is-ok">
					<div class="gen-proses-hero-label text-success">
						<i class="fas fa-arrow-circle-right mr-1"></i> Masuk Persediaan
					</div>
					<p class="mb-2 display-4 text-success font-weight-bold"><?php echo (int) (isset($rekap['count_masuk']) ? $rekap['count_masuk'] : 0); ?></p>
					<p class="gen-proses-hero-note mb-0 small">
						Record penjualan yang <strong>uuid_persediaan</strong>, <strong>nama_barang</strong>, dan <strong>satuan</strong> cocok dengan persediaan bulan target — nilai masuk ke <code>penjualan</code> dan kolom <code>unit</code>.
					</p>
				</div>
			</div>
			<div class="col-md-4 mb-3">
				<div class="gen-proses-hero-card <?php echo $has_masalah ? 'is-fail' : 'is-ok'; ?>">
					<div class="gen-proses-hero-label <?php echo $has_masalah ? 'text-danger' : 'text-muted'; ?>">
						<i class="fas fa-exclamation-triangle mr-1"></i> Perlu Perhatian
					</div>
					<ul class="gen-proses-check-values mb-2">
						<li class="text-danger">Tidak masuk persediaan: <strong><?php echo (int) (isset($rekap['count_tidak_masuk']) ? $rekap['count_tidak_masuk'] : 0); ?></strong></li>
						<li class="text-warning">Verifikasi manual admin: <strong><?php echo (int) (isset($rekap['count_manual']) ? $rekap['count_manual'] : 0); ?></strong></li>
						<li class="text-muted">Lewati (jumlah 0): <strong><?php echo (int) (isset($rekap['count_skip']) ? $rekap['count_skip'] : 0); ?></strong></li>
					</ul>
					<p class="gen-proses-hero-note mb-0 small">
						Manual = uuid ada di <code>tbl_pembelian</code> / bulan lain / nama tidak cocok — admin perlu cek apakah bisa dimasukkan ke persediaan.
					</p>
				</div>
			</div>
		</div>
	</div>

	<ul class="nav nav-pills gen-proses-penjualan-tabs mb-3" role="tablist">
		<li class="nav-item">
			<a class="nav-link active" id="gen-pj-tab-masuk" data-toggle="pill" href="#gen-pj-pane-masuk" role="tab">
				<i class="fas fa-check-circle text-success mr-1"></i>Masuk Persediaan
				<span class="badge badge-success ml-1"><?php echo count($rows_masuk); ?></span>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" id="gen-pj-tab-tidak" data-toggle="pill" href="#gen-pj-pane-tidak" role="tab">
				<i class="fas fa-times-circle text-danger mr-1"></i>Tidak Masuk
				<span class="badge badge-danger ml-1"><?php echo count($rows_tidak_masuk); ?></span>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" id="gen-pj-tab-manual" data-toggle="pill" href="#gen-pj-pane-manual" role="tab">
				<i class="fas fa-user-cog text-warning mr-1"></i>Verifikasi Manual
				<span class="badge badge-warning ml-1"><?php echo count($rows_manual); ?></span>
			</a>
		</li>
	</ul>

	<div class="tab-content gen-proses-penjualan-tab-content">
		<div class="tab-pane fade show active" id="gen-pj-pane-masuk" role="tabpanel">
			<?php $this->load->view('anekadharma/persediaan/_gen_proses_penjualan_table', array(
				'rows' => $rows_masuk,
				'table_id' => 'table-gen-proses-penjualan-masuk',
				'empty_msg' => 'Tidak ada record penjualan yang masuk ke persediaan.',
				'excel_jenis' => 'proses_penjualan_masuk',
			)); ?>
		</div>
		<div class="tab-pane fade" id="gen-pj-pane-tidak" role="tabpanel">
			<?php $this->load->view('anekadharma/persediaan/_gen_proses_penjualan_table', array(
				'rows' => $rows_tidak_masuk,
				'table_id' => 'table-gen-proses-penjualan-tidak',
				'empty_msg' => 'Semua record penjualan sudah masuk atau perlu verifikasi manual.',
				'excel_jenis' => 'proses_penjualan_tidak',
			)); ?>
		</div>
		<div class="tab-pane fade" id="gen-pj-pane-manual" role="tabpanel">
			<div class="alert alert-warning small mb-3">
				<i class="fas fa-info-circle mr-1"></i>
				Record berikut memiliki <strong>uuid_persediaan</strong> di <code>tbl_pembelian</code> / <code>tbl_pembelian_jasa</code>
				atau di persediaan bulan lain, tetapi belum cocok di persediaan bulan target.
				Admin perlu cek: apakah bisa dimasukkan ke persediaan, atau perbaiki data persediaan / pembelian / penjualan.
			</div>
			<?php $this->load->view('anekadharma/persediaan/_gen_proses_penjualan_table', array(
				'rows' => $rows_manual,
				'table_id' => 'table-gen-proses-penjualan-manual',
				'empty_msg' => 'Tidak ada record yang perlu verifikasi manual.',
				'excel_jenis' => 'proses_penjualan_manual',
			)); ?>
		</div>
	</div>
</div>
