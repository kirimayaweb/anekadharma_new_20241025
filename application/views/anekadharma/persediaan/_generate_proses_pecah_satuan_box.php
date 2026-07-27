<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

$rekap = isset($rekap) && is_array($rekap) ? $rekap : array();
$bulan_target_label = isset($bulan_target_label) ? (string) $bulan_target_label : '';
$rows_all = isset($rows_all) && is_array($rows_all) ? $rows_all : array();
$rows_update = isset($rows_update) && is_array($rows_update) ? $rows_update : array();
$rows_gagal = isset($rows_gagal) && is_array($rows_gagal) ? $rows_gagal : array();

$pecah_ok = !empty($rekap['pecah_ok']);
$pecah_badge = $pecah_ok ? 'badge-success' : 'badge-warning';
?>
<div class="gen-proses-pecah-satuan-box">
	<div class="gen-proses-rekap-panel mb-4">
		<div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
			<h5 class="gen-proses-title mb-2 mb-md-0">
				<i class="fas fa-cut text-purple mr-2"></i>Verifikasi Pecah Satuan
				<span class="badge badge-secondary ml-2"><?php echo htmlspecialchars($bulan_target_label, ENT_QUOTES, 'UTF-8'); ?></span>
			</h5>
			<p class="text-muted small mb-0 w-100 mt-1">
				Data dari <strong>tbl_pembelian_pecah_satuan</strong> bulan target — update kolom <code>pecah_satuan</code> &amp; <code>total_10</code> sumber,
				tambah <code>sa</code>/<code>total_10</code> target (nama+satuan+hpp barang baru).
			</p>
			<div class="gen-proses-rekap-badges">
				<span class="badge <?php echo $pecah_badge; ?> px-3 py-2 mr-1 mb-1">
					<i class="fas <?php echo $pecah_ok ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> mr-1"></i>
					Pecah Satuan <?php echo $pecah_ok ? 'SESUAI' : 'PERLU CEK'; ?>
				</span>
				<span class="badge badge-info px-3 py-2 mr-1 mb-1">Total: <?php echo (int) (isset($rekap['count_pecah_satuan']) ? $rekap['count_pecah_satuan'] : count($rows_all)); ?></span>
				<span class="badge badge-success px-3 py-2 mr-1 mb-1">Berhasil Update: <?php echo (int) (isset($rekap['count_update']) ? $rekap['count_update'] : count($rows_update)); ?></span>
				<span class="badge badge-danger px-3 py-2 mb-1">Gagal Update: <?php echo (int) (isset($rekap['count_tidak_cocok']) ? $rekap['count_tidak_cocok'] : count($rows_gagal)); ?></span>
			</div>
		</div>
	</div>

	<ul class="nav nav-pills gen-proses-pecah-tabs mb-3" role="tablist">
		<li class="nav-item">
			<a class="nav-link active" data-toggle="pill" href="#gen-pecah-pane-all" role="tab">
				Semua <span class="badge badge-secondary ml-1"><?php echo count($rows_all); ?></span>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggle="pill" href="#gen-pecah-pane-update" role="tab">
				Berhasil Update <span class="badge badge-success ml-1"><?php echo count($rows_update); ?></span>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggle="pill" href="#gen-pecah-pane-gagal" role="tab">
				Gagal Update <span class="badge badge-danger ml-1"><?php echo count($rows_gagal); ?></span>
			</a>
		</li>
	</ul>

	<div class="tab-content">
		<div class="tab-pane fade show active" id="gen-pecah-pane-all" role="tabpanel">
			<?php $this->load->view('anekadharma/persediaan/_gen_proses_pecah_satuan_table', array(
				'rows' => $rows_all,
				'table_id' => 'table-gen-proses-pecah-all',
				'bulan_target' => isset($bulan_target) ? $bulan_target : '',
				'empty_msg' => 'Tidak ada record pecah satuan bulan ini.',
			)); ?>
		</div>
		<div class="tab-pane fade" id="gen-pecah-pane-update" role="tabpanel">
			<?php $this->load->view('anekadharma/persediaan/_gen_proses_pecah_satuan_table', array(
				'rows' => $rows_update,
				'table_id' => 'table-gen-proses-pecah-update',
				'bulan_target' => isset($bulan_target) ? $bulan_target : '',
				'empty_msg' => 'Belum ada pecah satuan yang cocok di persediaan.',
			)); ?>
		</div>
		<div class="tab-pane fade" id="gen-pecah-pane-gagal" role="tabpanel">
			<?php $this->load->view('anekadharma/persediaan/_gen_proses_pecah_satuan_table', array(
				'rows' => $rows_gagal,
				'table_id' => 'table-gen-proses-pecah-gagal',
				'bulan_target' => isset($bulan_target) ? $bulan_target : '',
				'empty_msg' => 'Semua pecah satuan sudah cocok atau dilewati.',
			)); ?>
		</div>
	</div>
</div>
