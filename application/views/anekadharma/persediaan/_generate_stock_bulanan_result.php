<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

$bulan_target_label = isset($bulan_target_label) ? (string) $bulan_target_label : '';
$bulan_sumber_label = isset($bulan_sumber_label) ? (string) $bulan_sumber_label : '';
$tabel_sumber = isset($tabel_sumber) ? (string) $tabel_sumber : 'persediaan';
$source_rows = isset($source_rows) && is_array($source_rows) ? $source_rows : array();
$purchase_rows = isset($purchase_rows) && is_array($purchase_rows) ? $purchase_rows : array();
$purchase_matched = isset($purchase_matched) && is_array($purchase_matched) ? $purchase_matched : array();
$purchase_new = isset($purchase_new) && is_array($purchase_new) ? $purchase_new : array();
$unit_fields = persediaan_list_unit_columns($this);
$totals = array(
	'sa' => 0,
	'beli' => 0,
	'tuj' => 0,
	'total_10' => 0,
	'nilai_persediaan' => 0,
);
foreach ($unit_fields as $unit_field) {
	$totals[$unit_field] = 0;
}
foreach ($source_rows as $row) {
	foreach ($totals as $field => $sum) {
		$totals[$field] += persediaan_parse_angka(isset($row->{$field}) ? $row->{$field} : 0);
	}
}

$purchase_table = function ($rows, $table_id, $empty_text) {
	?>
	<div class="table-responsive">
		<table id="<?php echo htmlspecialchars($table_id, ENT_QUOTES, 'UTF-8'); ?>" class="table table-bordered table-striped table-sm gen-stock-bulanan-dt" style="width:100%;" data-order-col="4" data-fixed-left="0" data-money-cols="[6]">
			<thead>
				<tr>
					<th>No</th>
					<th>ID Pembelian</th>
					<th>UUID Persediaan</th>
					<th>Tgl PO</th>
					<th>Uraian</th>
					<th>Satuan</th>
					<th>Harga Satuan</th>
					<th>Jumlah</th>
					<th>Aksi</th>
					<th>ID Stock</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($rows as $index => $row) { ?>
					<tr>
						<td><?php echo (int) ($index + 1); ?></td>
						<td><?php echo (int) $row['id']; ?></td>
						<td><?php echo htmlspecialchars($row['uuid_persediaan'], ENT_QUOTES, 'UTF-8'); ?></td>
						<td><?php echo htmlspecialchars($row['tgl_po'], ENT_QUOTES, 'UTF-8'); ?></td>
						<td><?php echo htmlspecialchars($row['uraian'], ENT_QUOTES, 'UTF-8'); ?></td>
						<td><?php echo htmlspecialchars($row['satuan'], ENT_QUOTES, 'UTF-8'); ?></td>
						<td class="text-right"><?php echo htmlspecialchars(persediaan_format_rupiah_tampil($row['harga_satuan']), ENT_QUOTES, 'UTF-8'); ?></td>
						<td class="text-right"><?php echo htmlspecialchars(persediaan_format_angka_tampil($row['jumlah']), ENT_QUOTES, 'UTF-8'); ?></td>
						<td><?php echo htmlspecialchars($row['aksi'], ENT_QUOTES, 'UTF-8'); ?></td>
						<td><?php echo (int) $row['id_stock']; ?></td>
					</tr>
				<?php } ?>
				<?php if (empty($rows)) { ?>
					<tr><td colspan="10" class="text-center text-muted"><?php echo htmlspecialchars($empty_text, ENT_QUOTES, 'UTF-8'); ?></td></tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<?php
};
?>
<div class="gen-stock-bulanan-result">
	<div class="alert alert-success">
		<strong>Generate stok bulanan selesai — <?php echo htmlspecialchars($bulan_target_label, ENT_QUOTES, 'UTF-8'); ?>.</strong>
		<br/>Dihapus: <?php echo (int) $count_deleted; ?> record target;
		disalin dari <?php echo htmlspecialchars($tabel_sumber, ENT_QUOTES, 'UTF-8'); ?>
		bulan <?php echo htmlspecialchars($bulan_sumber_label, ENT_QUOTES, 'UTF-8'); ?>:
		<?php echo (int) $count_copied; ?> record;
		pembelian diproses: <?php echo (int) $count_purchases; ?> record
		(<?php echo (int) $count_purchase_updated; ?> cocok/update,
		<?php echo (int) $count_purchase_inserted; ?> record baru).
		<br/><strong>Proses Generate saat ini mencakup penyalinan stock dan pembelian bulan target.</strong>
	</div>

	<section class="mb-4">
		<h5>1. Persediaan stock bulanan — hasil copy bulan sebelumnya</h5>
		<p class="small text-muted mb-2">
			Sumber: <code><?php echo htmlspecialchars($tabel_sumber, ENT_QUOTES, 'UTF-8'); ?></code>
			(<?php echo htmlspecialchars($bulan_sumber_label, ENT_QUOTES, 'UTF-8'); ?>),
			hanya record dengan <code>total_10 &gt; 0</code>. Nilai ini merupakan stock awal sebelum pembelian bulan target.
		</p>
		<div class="table-responsive">
			<table id="table-stock-bulanan-sumber" class="table table-bordered table-striped table-sm persediaan-tab-dt gen-stock-bulanan-dt" style="width:100%;" data-money-cols="[]" data-fixed-left="0" data-order-col="3">
				<thead><tr>
					<th>No</th><th>ID Stock</th><th>UUID Persediaan</th><th>Nama Barang</th><th>Satuan</th>
					<th>HPP</th><th>SA</th><th>Beli</th><th>Tuj</th>
					<?php foreach ($unit_fields as $unit_field) { ?><th><?php echo htmlspecialchars(persediaan_field_label($unit_field), ENT_QUOTES, 'UTF-8'); ?></th><?php } ?>
					<th>Total 10</th><th>Nilai Persediaan</th>
				</tr></thead>
				<tbody>
					<?php foreach ($source_rows as $index => $row) { ?>
						<tr>
							<td><?php echo (int) ($index + 1); ?></td>
							<td><?php echo (int) $row->id; ?></td>
							<td><?php echo htmlspecialchars(isset($row->uuid_persediaan) ? (string) $row->uuid_persediaan : '', ENT_QUOTES, 'UTF-8'); ?></td>
							<td><?php echo htmlspecialchars(isset($row->namabarang) ? (string) $row->namabarang : '', ENT_QUOTES, 'UTF-8'); ?></td>
							<td><?php echo htmlspecialchars(isset($row->satuan) ? (string) $row->satuan : '', ENT_QUOTES, 'UTF-8'); ?></td>
							<td class="text-right"><?php echo htmlspecialchars(persediaan_format_rupiah_tampil(isset($row->hpp) ? $row->hpp : 0), ENT_QUOTES, 'UTF-8'); ?></td>
							<td class="text-right"><?php echo htmlspecialchars(persediaan_format_angka_tampil(isset($row->sa) ? $row->sa : 0), ENT_QUOTES, 'UTF-8'); ?></td>
							<td class="text-right"><?php echo htmlspecialchars(persediaan_format_angka_tampil(isset($row->beli) ? $row->beli : 0), ENT_QUOTES, 'UTF-8'); ?></td>
							<td class="text-right"><?php echo htmlspecialchars(persediaan_format_angka_tampil(isset($row->tuj) ? $row->tuj : 0), ENT_QUOTES, 'UTF-8'); ?></td>
							<?php foreach ($unit_fields as $unit_field) { ?>
								<td class="text-right"><?php echo htmlspecialchars(persediaan_format_angka_tampil(isset($row->{$unit_field}) ? $row->{$unit_field} : 0), ENT_QUOTES, 'UTF-8'); ?></td>
							<?php } ?>
							<td class="text-right"><?php echo htmlspecialchars(persediaan_format_angka_tampil(isset($row->total_10) ? $row->total_10 : 0), ENT_QUOTES, 'UTF-8'); ?></td>
							<td class="text-right"><?php echo htmlspecialchars(persediaan_format_rupiah_tampil(isset($row->nilai_persediaan) ? $row->nilai_persediaan : 0), ENT_QUOTES, 'UTF-8'); ?></td>
						</tr>
					<?php } ?>
				</tbody>
				<tfoot><tr>
					<th colspan="6">TOTAL</th>
					<th><?php echo htmlspecialchars(persediaan_format_angka_tampil($totals['sa']), ENT_QUOTES, 'UTF-8'); ?></th>
					<th><?php echo htmlspecialchars(persediaan_format_angka_tampil($totals['beli']), ENT_QUOTES, 'UTF-8'); ?></th>
					<th><?php echo htmlspecialchars(persediaan_format_angka_tampil($totals['tuj']), ENT_QUOTES, 'UTF-8'); ?></th>
					<?php foreach ($unit_fields as $unit_field) { ?><th><?php echo htmlspecialchars(persediaan_format_angka_tampil($totals[$unit_field]), ENT_QUOTES, 'UTF-8'); ?></th><?php } ?>
					<th><?php echo htmlspecialchars(persediaan_format_angka_tampil($totals['total_10']), ENT_QUOTES, 'UTF-8'); ?></th>
					<th><?php echo htmlspecialchars(persediaan_format_rupiah_tampil($totals['nilai_persediaan']), ENT_QUOTES, 'UTF-8'); ?></th>
				</tr></tfoot>
			</table>
		</div>
	</section>

	<section class="mb-4">
		<h5>2. Semua record pembelian bulan target</h5>
		<?php $purchase_table($purchase_rows, 'table-stock-bulanan-pembelian', 'Tidak ada record pembelian bulan target.'); ?>
	</section>
	<section class="mb-4">
		<h5>3. Pembelian dengan UUID persediaan cocok — record stock diperbarui</h5>
		<p class="small text-muted">Jumlah pembelian ditambahkan ke <code>beli</code> dan <code>total_10</code> pada record stock yang memiliki UUID sama.</p>
		<?php $purchase_table($purchase_matched, 'table-stock-bulanan-pembelian-update', 'Tidak ada pembelian yang cocok dengan UUID persediaan hasil copy.'); ?>
	</section>
	<section>
		<h5>4. Pembelian tanpa UUID cocok — dibuat sebagai record stock baru</h5>
		<p class="small text-muted">Jika UUID pembelian kosong, sistem membuat UUID baru untuk record stock. Jika UUID berisi tetapi tidak ditemukan pada stock awal, record baru menggunakan UUID pembelian.</p>
		<?php $purchase_table($purchase_new, 'table-stock-bulanan-pembelian-baru', 'Tidak ada pembelian yang perlu dibuat sebagai record baru.'); ?>
	</section>
</div>
