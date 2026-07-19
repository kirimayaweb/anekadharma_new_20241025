<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

$rows = isset($rows) && is_array($rows) ? $rows : array();
$margin_summary = isset($margin_summary) && is_array($margin_summary) ? $margin_summary : array();
$table_id = isset($table_id) ? (string) $table_id : 'table-gen-proses-produksi-margin';
$excel_jenis = isset($excel_jenis) ? trim((string) $excel_jenis) : 'proses_produksi_margin';
$empty_msg = 'Tidak ada data margin bahan / pengurangan persediaan pada bulan ini.';

$sum_jumlah = isset($margin_summary['sum_jumlah']) ? (float) $margin_summary['sum_jumlah'] : 0.0;
$sum_pengurangan = isset($margin_summary['sum_pengurangan']) ? (float) $margin_summary['sum_pengurangan'] : 0.0;
$no = 0;

if (!function_exists('persediaan_gen_proses_pembelian_format_tgl')) {
	$CI = function_exists('get_instance') ? get_instance() : null;
	if ($CI) {
		$CI->load->helper('persediaan_display');
	}
}

$col_jumlah = 5;
$col_pengurangan = 7;
$col_count = 11;
?>
<?php if ($excel_jenis !== '') {
	$this->load->view('anekadharma/persediaan/_gen_proses_excel_btn', array(
		'excel_jenis' => $excel_jenis,
		'excel_title' => 'Cetak Margin Produk / Pengurangan Persediaan ke Excel',
	));
} ?>

<div class="gen-prod-margin-summary row mb-3">
	<div class="col-md-6 mb-2">
		<div class="gen-prod-riil-stat gen-prod-riil-stat-bahan">
			<span class="gen-prod-riil-stat-label"><i class="fas fa-minus-circle mr-1"></i>Σ Jumlah Bahan Digunakan</span>
			<strong class="gen-prod-riil-stat-val"><?php echo persediaan_gen_proses_pembelian_format_jumlah($sum_jumlah); ?></strong>
		</div>
	</div>
	<div class="col-md-6 mb-2">
		<div class="gen-prod-riil-stat gen-prod-riil-stat-margin">
			<span class="gen-prod-riil-stat-label"><i class="fas fa-warehouse mr-1"></i>Σ Pengurangan Nilai Persediaan</span>
			<strong class="gen-prod-riil-stat-val"><?php echo persediaan_gen_proses_pembelian_format_nominal($sum_pengurangan); ?></strong>
		</div>
	</div>
</div>

<p class="small text-muted mb-2">
	Harga satuan diambil dari <code>persediaan.hpp</code> (harga asli) via
	<code>uuid_persediaan_bahan</code>. Total pengurangan = jumlah bahan × harga satuan asli persediaan.
</p>

<div class="gen-proses-produksi-dt-wrap persediaan-tab-dt-wrap">
	<table id="<?php echo htmlspecialchars($table_id, ENT_QUOTES, 'UTF-8'); ?>" class="table table-bordered table-striped table-sm gen-proses-produksi-dt display nowrap" style="width:100%"
		data-empty-msg="<?php echo htmlspecialchars($empty_msg, ENT_QUOTES, 'UTF-8'); ?>"
		data-col-jumlah="<?php echo (int) $col_jumlah; ?>"
		data-col-total-nominal="<?php echo (int) $col_pengurangan; ?>"
		data-col-count="<?php echo (int) $col_count; ?>">
		<thead>
			<tr>
				<th>No</th>
				<th>Kode Bahan</th>
				<th>Nama Bahan</th>
				<th>UUID Persediaan</th>
				<th>Satuan</th>
				<th class="text-right">Jumlah Digunakan</th>
				<th class="text-right">Harga Satuan Asli</th>
				<th class="text-right">Total Pengurangan</th>
				<th class="text-right">Stok Persediaan (total_10)</th>
				<th>Produk Terkait</th>
				<th>Match</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($rows as $row) {
				$no++;
			?>
			<tr>
				<td><?php echo (int) $no; ?></td>
				<td><?php echo htmlspecialchars(isset($row->kode_barang_bahan) ? (string) $row->kode_barang_bahan : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo htmlspecialchars(isset($row->nama_bahan) ? (string) $row->nama_bahan : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td><small><?php echo htmlspecialchars(isset($row->uuid_persediaan_bahan) ? (string) $row->uuid_persediaan_bahan : '', ENT_QUOTES, 'UTF-8'); ?></small></td>
				<td><?php echo htmlspecialchars(isset($row->satuan) ? (string) $row->satuan : '', ENT_QUOTES, 'UTF-8'); ?></td>
				<td class="text-right" data-order="<?php echo htmlspecialchars((string) (float) $row->jumlah_digunakan, ENT_QUOTES, 'UTF-8'); ?>"><?php echo persediaan_gen_proses_pembelian_format_jumlah($row->jumlah_digunakan); ?></td>
				<td class="text-right" data-order="<?php echo htmlspecialchars((string) (float) $row->harga_satuan_asli, ENT_QUOTES, 'UTF-8'); ?>"><?php echo persediaan_gen_proses_pembelian_format_nominal($row->harga_satuan_asli); ?></td>
				<td class="text-right font-weight-bold text-danger" data-order="<?php echo htmlspecialchars((string) (float) $row->total_pengurangan, ENT_QUOTES, 'UTF-8'); ?>"><?php echo persediaan_gen_proses_pembelian_format_nominal($row->total_pengurangan); ?></td>
				<td class="text-right" data-order="<?php echo htmlspecialchars((string) (float) $row->total_10_persediaan, ENT_QUOTES, 'UTF-8'); ?>"><?php echo persediaan_gen_proses_pembelian_format_jumlah($row->total_10_persediaan); ?></td>
				<td><small><?php echo htmlspecialchars(isset($row->produk_terkait) ? (string) $row->produk_terkait : '', ENT_QUOTES, 'UTF-8'); ?></small></td>
				<td class="text-center">
					<?php if (!empty($row->match_persediaan)) { ?>
						<span class="badge badge-success">Ada</span>
					<?php } else { ?>
						<span class="badge badge-warning">Tidak</span>
					<?php } ?>
				</td>
			</tr>
			<?php } ?>
		</tbody>
		<tfoot class="gen-proses-dt-tfoot">
			<tr>
				<?php for ($c = 0; $c < $col_count; $c++) {
					if ($c === 0) {
						echo '<th class="font-weight-bold">TOTAL</th>';
					} elseif ($c === $col_jumlah) {
						echo '<th class="text-right font-weight-bold">' . persediaan_gen_proses_pembelian_format_jumlah($sum_jumlah) . '</th>';
					} elseif ($c === $col_pengurangan) {
						echo '<th class="text-right font-weight-bold">' . persediaan_gen_proses_pembelian_format_nominal($sum_pengurangan) . '</th>';
					} else {
						echo '<th></th>';
					}
				} ?>
			</tr>
		</tfoot>
	</table>
</div>
