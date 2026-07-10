<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

$rows = isset($rows) && is_array($rows) ? $rows : array();
$table_id = isset($table_id) ? (string) $table_id : 'table-gen-proses-penjualan';
$empty_msg = isset($empty_msg) ? (string) $empty_msg : 'Tidak ada data penjualan.';
$no = 0;
$sum_jumlah = 0.0;
$sum_total_harga = 0.0;

if (!function_exists('persediaan_gen_proses_pembelian_format_tgl')) {
	$CI = function_exists('get_instance') ? get_instance() : null;
	if ($CI) {
		$CI->load->helper('persediaan_display');
	}
}

if (!function_exists('persediaan_gen_proses_penjualan_status_badge')) {
	function persediaan_gen_proses_penjualan_status_badge($label, $kategori)
	{
		$kategori = (string) $kategori;
		if ($kategori === 'masuk') {
			return '<span class="badge badge-success">' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</span>';
		}
		if ($kategori === 'manual') {
			return '<span class="badge badge-warning">' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</span>';
		}
		if ($kategori === 'skip') {
			return '<span class="badge badge-secondary">' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</span>';
		}
		return '<span class="badge badge-danger">' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</span>';
	}
}

$col_jumlah = 9;
$col_total_harga = 10;
$col_count = 14;
?>
<div class="gen-proses-penjualan-dt-wrap persediaan-tab-dt-wrap">
	<table id="<?php echo htmlspecialchars($table_id, ENT_QUOTES, 'UTF-8'); ?>" class="table table-bordered table-striped table-sm gen-proses-penjualan-dt display nowrap" style="width:100%"
		data-empty-msg="<?php echo htmlspecialchars($empty_msg, ENT_QUOTES, 'UTF-8'); ?>"
		data-col-jumlah="<?php echo (int) $col_jumlah; ?>"
		data-col-total-harga="<?php echo (int) $col_total_harga; ?>"
		data-col-count="<?php echo (int) $col_count; ?>">
		<thead>
			<tr>
				<th>No</th>
				<th>Status</th>
				<th>ID</th>
				<th>Tgl Jual</th>
				<th>UUID Persediaan</th>
				<th>Nama Barang</th>
				<th>Satuan</th>
				<th>Unit</th>
				<th>Harga Satuan</th>
				<th>Jumlah</th>
				<th>Total Harga</th>
				<th>ID Persediaan</th>
				<th>Match Via</th>
				<th>Keterangan</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($rows as $row) {
				$no++;
				$status_label = isset($row->status_label) ? (string) $row->status_label : '';
				$status_kategori = isset($row->status_kategori) ? (string) $row->status_kategori : '';
				$jumlah = isset($row->jumlah) ? $row->jumlah : 0;
				$harga = isset($row->harga_satuan) ? $row->harga_satuan : 0;
				$total_harga = (float) persediaan_parse_angka($jumlah) * (float) persediaan_parse_angka($harga);
				$sum_jumlah += (float) persediaan_parse_angka($jumlah);
				$sum_total_harga += $total_harga;
			?>
			<tr>
				<td><?php echo (int) $no; ?></td>
				<td><?php echo persediaan_gen_proses_penjualan_status_badge($status_label, $status_kategori); ?></td>
				<td><?php echo (int) (isset($row->id) ? $row->id : 0); ?></td>
				<td><?php echo persediaan_gen_proses_pembelian_format_tgl(isset($row->tgl_jual) ? $row->tgl_jual : ''); ?></td>
				<td><code class="small"><?php echo htmlspecialchars(isset($row->uuid_persediaan) ? (string) $row->uuid_persediaan : '', ENT_QUOTES, 'UTF-8'); ?></code></td>
				<td><?php echo htmlspecialchars(isset($row->nama_barang) ? (string) $row->nama_barang : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars(isset($row->satuan) ? (string) $row->satuan : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars(isset($row->unit) ? (string) $row->unit : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td class="text-right" data-order="<?php echo htmlspecialchars((string) persediaan_parse_angka($harga), ENT_QUOTES, 'UTF-8'); ?>"><?php echo persediaan_gen_proses_pembelian_format_nominal($harga); ?></td>
				<td class="text-right" data-order="<?php echo htmlspecialchars((string) persediaan_parse_angka($jumlah), ENT_QUOTES, 'UTF-8'); ?>"><?php echo persediaan_gen_proses_pembelian_format_jumlah($jumlah); ?></td>
				<td class="text-right" data-order="<?php echo htmlspecialchars((string) $total_harga, ENT_QUOTES, 'UTF-8'); ?>"><?php echo persediaan_gen_proses_pembelian_format_nominal($total_harga); ?></td>
				<td><?php echo isset($row->id_persediaan_match) ? (int) $row->id_persediaan_match : '—'; ?></td>
				<td><?php echo htmlspecialchars(isset($row->match_via) ? (string) $row->match_via : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td class="small"><?php echo htmlspecialchars(isset($row->status_keterangan) ? (string) $row->status_keterangan : '', ENT_QUOTES, 'UTF-8'); ?></td>
			</tr>
			<?php } ?>
		</tbody>
		<tfoot class="gen-proses-dt-tfoot">
			<tr>
				<?php for ($c = 0; $c < $col_count; $c++) {
					if ($c === $col_jumlah) {
						echo '<th class="text-right font-weight-bold">' . persediaan_gen_proses_pembelian_format_jumlah($sum_jumlah) . '</th>';
					} elseif ($c === $col_total_harga) {
						echo '<th class="text-right font-weight-bold">' . persediaan_gen_proses_pembelian_format_nominal($sum_total_harga) . '</th>';
					} elseif ($c === 0) {
						echo '<th class="font-weight-bold">TOTAL</th>';
					} else {
						echo '<th></th>';
					}
				} ?>
			</tr>
		</tfoot>
	</table>
</div>
