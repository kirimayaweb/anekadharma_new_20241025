<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

$rows = isset($rows) && is_array($rows) ? $rows : array();
$table_id = isset($table_id) ? (string) $table_id : 'table-gen-proses-pembelian';
$tab_mode = isset($tab_mode) ? strtolower(trim((string) $tab_mode)) : 'barang';
$show_kategori = ($tab_mode !== 'jasa');
$empty_msg = isset($empty_msg) ? (string) $empty_msg : 'Tidak ada data pembelian pada bulan ini.';
$no = 0;
$sum_jumlah = 0.0;
$sum_harga_total = 0.0;

if (!function_exists('persediaan_gen_proses_pembelian_format_tgl')) {
	$CI = function_exists('get_instance') ? get_instance() : null;
	if ($CI) {
		$CI->load->helper('persediaan_display');
	}
}

$col_jumlah = $show_kategori ? 8 : 7;
$col_harga_total = $show_kategori ? 12 : 11;
$col_count = $show_kategori ? 16 : 15;
?>
<div class="gen-proses-pembelian-dt-wrap persediaan-tab-dt-wrap">
	<table id="<?php echo htmlspecialchars($table_id, ENT_QUOTES, 'UTF-8'); ?>" class="table table-bordered table-striped table-sm gen-proses-pembelian-dt display nowrap" style="width:100%"
		data-empty-msg="<?php echo htmlspecialchars($empty_msg, ENT_QUOTES, 'UTF-8'); ?>"
		data-col-jumlah="<?php echo (int) $col_jumlah; ?>"
		data-col-harga-total="<?php echo (int) $col_harga_total; ?>"
		data-col-count="<?php echo (int) $col_count; ?>">
		<thead>
			<tr>
				<th>No</th>
				<th>Tgl Po</th>
				<th>Spop</th>
				<?php if ($show_kategori) { ?><th>Kategori</th><?php } ?>
				<th>No. Faktur/Kwitansi</th>
				<th>Supplier</th>
				<th>Kode Barang</th>
				<th>Nama Barang</th>
				<th>Jumlah</th>
				<th>Satuan</th>
				<th>Konsumen</th>
				<th>Harga Satuan</th>
				<th>Harga Total</th>
				<th>Statuslu</th>
				<th>Kas/Bank</th>
				<th>Tgl Bayar</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($rows as $row) {
				$no++;
				$harga_satuan = isset($row->harga_satuan) ? $row->harga_satuan : 0;
				$jumlah = isset($row->jumlah) ? $row->jumlah : 0;
				$harga_total = isset($row->harga_total) ? $row->harga_total : ((float) persediaan_parse_angka($jumlah) * (float) persediaan_parse_angka($harga_satuan));
				$sum_jumlah += (float) persediaan_parse_angka($jumlah);
				$sum_harga_total += (float) persediaan_parse_angka($harga_total);
			?>
			<tr>
				<td><?php echo (int) $no; ?></td>
				<td><?php echo persediaan_gen_proses_pembelian_format_tgl(isset($row->tgl_po) ? $row->tgl_po : ''); ?></td>
				<td><?php echo htmlspecialchars(isset($row->spop) ? (string) $row->spop : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<?php if ($show_kategori) { ?>
					<td><?php echo htmlspecialchars(isset($row->kategori) ? (string) $row->kategori : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<?php } ?>
				<td><?php echo htmlspecialchars(isset($row->nmrfakturkwitansi) ? (string) $row->nmrfakturkwitansi : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars(isset($row->supplier_nama) ? (string) $row->supplier_nama : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars(isset($row->kode_barang) ? (string) $row->kode_barang : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars(isset($row->uraian) ? (string) $row->uraian : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td class="text-right" data-order="<?php echo htmlspecialchars((string) persediaan_parse_angka($jumlah), ENT_QUOTES, 'UTF-8'); ?>"><?php echo persediaan_gen_proses_pembelian_format_jumlah($jumlah); ?></td>
				<td><?php echo htmlspecialchars(isset($row->satuan) ? (string) $row->satuan : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars(isset($row->konsumen) ? (string) $row->konsumen : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td class="text-right" data-order="<?php echo htmlspecialchars((string) persediaan_parse_angka($harga_satuan), ENT_QUOTES, 'UTF-8'); ?>"><?php echo persediaan_gen_proses_pembelian_format_nominal($harga_satuan); ?></td>
				<td class="text-right" data-order="<?php echo htmlspecialchars((string) persediaan_parse_angka($harga_total), ENT_QUOTES, 'UTF-8'); ?>"><?php echo persediaan_gen_proses_pembelian_format_nominal($harga_total); ?></td>
				<td><?php echo htmlspecialchars(isset($row->statuslu) ? (string) $row->statuslu : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars(isset($row->kas_bank) ? (string) $row->kas_bank : (isset($row->kasbank) ? (string) $row->kasbank : ''), ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo persediaan_gen_proses_pembelian_format_tgl(isset($row->tgl_bayar) ? $row->tgl_bayar : ''); ?></td>
			</tr>
			<?php } ?>
		</tbody>
		<tfoot class="gen-proses-dt-tfoot">
			<tr>
				<?php for ($c = 0; $c < $col_count; $c++) {
					if ($c === $col_jumlah) {
						echo '<th class="text-right font-weight-bold">' . persediaan_gen_proses_pembelian_format_jumlah($sum_jumlah) . '</th>';
					} elseif ($c === $col_harga_total) {
						echo '<th class="text-right font-weight-bold">' . persediaan_gen_proses_pembelian_format_nominal($sum_harga_total) . '</th>';
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
