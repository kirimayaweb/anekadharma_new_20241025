<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

$rows = isset($rows) && is_array($rows) ? $rows : array();
$table_id = isset($table_id) ? (string) $table_id : 'table-gen-proses-pecah-satuan';
$empty_msg = isset($empty_msg) ? (string) $empty_msg : 'Tidak ada data pecah satuan.';
$bulan_target = isset($bulan_target) ? (string) $bulan_target : '';
$no = 0;

if (!function_exists('persediaan_gen_proses_pembelian_format_tgl')) {
	$CI = function_exists('get_instance') ? get_instance() : null;
	if ($CI) {
		$CI->load->helper('persediaan_display');
	}
}

if (!function_exists('persediaan_gen_proses_pecah_status_badge')) {
	function persediaan_gen_proses_pecah_status_badge($label, $kategori)
	{
		$kategori = (string) $kategori;
		if ($kategori === 'update') {
			return '<span class="badge badge-success">' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</span>';
		}
		if ($kategori === 'partial') {
			return '<span class="badge badge-warning">' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</span>';
		}
		if ($kategori === 'skip') {
			return '<span class="badge badge-secondary">' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</span>';
		}
		return '<span class="badge badge-danger">' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</span>';
	}
}
?>
<div class="gen-proses-pecah-dt-wrap persediaan-tab-dt-wrap">
	<table id="<?php echo htmlspecialchars($table_id, ENT_QUOTES, 'UTF-8'); ?>" class="table table-bordered table-striped table-sm gen-proses-pecah-dt display nowrap" style="width:100%">
		<thead>
			<tr>
				<th>No</th>
				<th>Action</th>
				<th>Status</th>
				<th>ID</th>
				<th>Uraian Sumber</th>
				<th>Satuan</th>
				<th>HPP</th>
				<th>Jml Pecah</th>
				<th>Nama Baru</th>
				<th>Satuan Baru</th>
				<th>HPP Baru</th>
				<th>Jml Baru</th>
				<th>ID Pers. Sumber</th>
				<th>ID Pers. Target</th>
				<th>Masalah</th>
			</tr>
		</thead>
		<tbody>
			<?php if (empty($rows)) { ?>
			<tr><td colspan="15" class="text-muted text-center small"><?php echo htmlspecialchars($empty_msg, ENT_QUOTES, 'UTF-8'); ?></td></tr>
			<?php } else { foreach ($rows as $row) {
				$no++;
				$status_label = isset($row->status_label) ? (string) $row->status_label : '';
				$status_kategori = isset($row->status_kategori) ? (string) $row->status_kategori : '';
				$can_process = ($status_kategori === 'gagal');
				$id_pecah = isset($row->id) ? (int) $row->id : 0;
			?>
			<tr>
				<td><?php echo (int) $no; ?></td>
				<td>
					<?php if ($can_process && $id_pecah > 0) { ?>
						<button
							type="button"
							class="btn btn-xs btn-warning btn-gen-pecah-proses"
							data-id-pecah-satuan="<?php echo $id_pecah; ?>"
							data-bulan="<?php echo htmlspecialchars($bulan_target, ENT_QUOTES, 'UTF-8'); ?>"
							data-uraian="<?php echo htmlspecialchars(isset($row->uraian) ? (string) $row->uraian : '', ENT_QUOTES, 'UTF-8'); ?>"
							data-satuan="<?php echo htmlspecialchars(isset($row->satuan) ? (string) $row->satuan : '', ENT_QUOTES, 'UTF-8'); ?>"
							data-hpp="<?php echo htmlspecialchars(isset($row->harga_satuan) ? (string) $row->harga_satuan : '', ENT_QUOTES, 'UTF-8'); ?>"
							data-jumlah="<?php echo htmlspecialchars(isset($row->jumlah) ? (string) $row->jumlah : '', ENT_QUOTES, 'UTF-8'); ?>"
							data-masalah="<?php echo htmlspecialchars(isset($row->status_keterangan) ? (string) $row->status_keterangan : '', ENT_QUOTES, 'UTF-8'); ?>"
						>
							<i class="fas fa-cogs"></i> Proses Record
						</button>
					<?php } else { ?>
						<span class="text-muted small">-</span>
					<?php } ?>
				</td>
				<td><?php echo persediaan_gen_proses_pecah_status_badge($status_label, $status_kategori); ?></td>
				<td><?php echo (int) (isset($row->id) ? $row->id : 0); ?></td>
				<td><?php echo htmlspecialchars(isset($row->uraian) ? (string) $row->uraian : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars(isset($row->satuan) ? (string) $row->satuan : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td class="text-right"><?php echo persediaan_gen_proses_pembelian_format_nominal(isset($row->harga_satuan) ? $row->harga_satuan : 0); ?></td>
				<td class="text-right"><?php echo persediaan_gen_proses_pembelian_format_jumlah(isset($row->jumlah) ? $row->jumlah : 0); ?></td>
				<td><?php echo htmlspecialchars(isset($row->nama_barang_baru) ? (string) $row->nama_barang_baru : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars(isset($row->satuan_barang_baru) ? (string) $row->satuan_barang_baru : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td class="text-right"><?php echo persediaan_gen_proses_pembelian_format_nominal(isset($row->harga_satuan_barang_baru) ? $row->harga_satuan_barang_baru : 0); ?></td>
				<td class="text-right"><?php echo persediaan_gen_proses_pembelian_format_jumlah(isset($row->jumlah_barang_baru) ? $row->jumlah_barang_baru : 0); ?></td>
				<td><?php echo isset($row->id_persediaan_sumber) ? (int) $row->id_persediaan_sumber : '—'; ?></td>
				<td><?php echo isset($row->id_persediaan_target) ? (int) $row->id_persediaan_target : '—'; ?></td>
				<td class="small"><?php echo htmlspecialchars(isset($row->status_keterangan) ? (string) $row->status_keterangan : '', ENT_QUOTES, 'UTF-8'); ?></td>
			</tr>
			<?php } } ?>
		</tbody>
	</table>
</div>
