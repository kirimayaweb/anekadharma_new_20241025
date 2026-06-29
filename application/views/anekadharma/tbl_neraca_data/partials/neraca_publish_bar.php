<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$this->load->helper(array('dashboard', 'dashboard_laporan_publish'));

$neraca_can_publish = isset($neraca_can_publish)
	? (bool) $neraca_can_publish
	: dashboard_user_can_update_laporan_bulanan($this);

$neraca_show_publish_bar = !empty($bulan_transaksi)
	&& (int) $bulan_transaksi > 0
	&& $neraca_can_publish;

if ($neraca_show_publish_bar) {
	if (!isset($neraca_is_published)) {
		$neraca_is_published = dashboard_laporan_is_published($this, 'neraca', $tahun_neraca, $bulan_transaksi);
	}

	if (!isset($neraca_has_record)) {
		$neraca_has_record = isset($data_tbl_neraca_data);
		if (!$neraca_has_record) {
			$neraca_has_record = dashboard_laporan_has_saved_data($this, 'tbl_neraca_data', $tahun_neraca, $bulan_transaksi);
		}
	}
}
?>
<?php if ($neraca_show_publish_bar) { ?>
<div class="card card-outline card-danger shadow-sm mb-3 neraca-publish-bar" style="position: sticky; top: 0; z-index: 1030; background: #fff;">
	<div class="card-body py-3">
		<div class="text-center">
			<?php if ($this->session->flashdata('message')) { ?>
				<div class="alert alert-info alert-dismissible fade show" role="alert" style="max-width: 520px; margin: 0 auto 12px;">
					<?php echo $this->session->flashdata('message'); ?>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
			<?php } ?>
			<?php if (!empty($neraca_is_published)) { ?>
				<form action="<?php echo site_url('Tbl_neraca_data/publish_neraca/' . $tahun_neraca . '/' . $bulan_transaksi); ?>" method="post" style="display: inline;">
					<input type="hidden" name="action" value="cancel">
					<button type="submit" class="btn" style="background-color: #28a745; border-color: #28a745; color: #fff; min-width: 220px; padding: 12px 24px; font-weight: bold;">
						<i class="fa fa-ban"></i> Cancel Publish
					</button>
				</form>
			<?php } else { ?>
				<form action="<?php echo site_url('Tbl_neraca_data/publish_neraca/' . $tahun_neraca . '/' . $bulan_transaksi); ?>" method="post" style="display: inline;">
					<input type="hidden" name="action" value="publish">
					<button type="submit" class="btn" style="background-color: #dc3545; border-color: #dc3545; color: #fff; min-width: 220px; padding: 12px 24px; font-weight: bold;" <?php echo !empty($neraca_has_record) ? '' : 'disabled'; ?>>
						<i class="fa fa-upload"></i> Publish
					</button>
				</form>
				<?php if (empty($neraca_has_record)) { ?>
					<p class="text-muted small mt-2 mb-0">Simpan data terlebih dahulu sebelum publish.</p>
				<?php } ?>
			<?php } ?>
		</div>
	</div>
</div>
<?php } ?>
