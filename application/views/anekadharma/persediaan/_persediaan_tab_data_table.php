<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

$Persediaan_rows = isset($Persediaan_rows) && is_array($Persediaan_rows) ? $Persediaan_rows : array();
$table_id = isset($table_id) ? (string) $table_id : 'table-persediaan';
$bulan_tampil = isset($bulan_tampil) ? (string) $bulan_tampil : date('Y-m');
$tab_mode = isset($tab_mode) ? (string) $tab_mode : 'barang';
$show_keluar_columns = persediaan_tab_data_show_keluar_columns($tab_mode);
$nama_barang_header = persediaan_tab_data_nama_barang_header($tab_mode);
$fixed_left_columns = persediaan_tab_data_fixed_left_columns();
$nama_col_index = 2;

$persediaan_fields_tgl_total = persediaan_list_fields_tgl_keluar_sampai_total_10();
$money_col_indexes = persediaan_tab_data_money_column_indexes();
$total_total_10 = 0;
$total_nilai_persediaan = 0;
$total_nominal_unit = array();
foreach (persediaan_list_unit_columns() as $uf_total) {
	$total_nominal_unit[$uf_total] = 0;
}
?>
<div class="persediaan-tab-dt-wrap">
<table id="<?php echo htmlspecialchars($table_id, ENT_QUOTES, 'UTF-8'); ?>" class="table table-bordered table-striped persediaan-tab-dt" style="width:100%;font-size:15px;" data-money-cols="<?php echo htmlspecialchars(json_encode(array_values($money_col_indexes)), ENT_QUOTES, 'UTF-8'); ?>" data-fixed-left="<?php echo (int) $fixed_left_columns; ?>" data-order-col="<?php echo (int) $nama_col_index; ?>">
	<thead>
		<tr>
			<th width="50px">No</th>
			<th>Tanggal</th>
			<th><?php echo htmlspecialchars($nama_barang_header, ENT_QUOTES, 'UTF-8'); ?></th>
			<th>Satuan</th>
			<th class="text-right persediaan-col-money">Hpp</th>
			<th>Sa</th>
			<th>Spop</th>
			<th>Beli</th>
			<th>Tuj</th>
			<?php foreach ($persediaan_fields_tgl_total as $field_tgl_total) { ?>
				<th><?php echo htmlspecialchars(persediaan_field_label($field_tgl_total), ENT_QUOTES, 'UTF-8'); ?></th>
				<?php if (persediaan_field_has_nominal_column($field_tgl_total)) { ?>
					<th class="text-right persediaan-col-money"><?php echo htmlspecialchars(persediaan_field_nominal_header_label($field_tgl_total), ENT_QUOTES, 'UTF-8'); ?></th>
				<?php } ?>
			<?php } ?>
			<th class="text-right persediaan-col-money">Nilai Persediaan</th>
			<?php if ($show_keluar_columns) { ?>
			<th>Terjual</th>
			<th>Jumlah Pecah Satuan</th>
			<th>Bahan Produksi</th>
			<?php } ?>
		</tr>
	</thead>
	<tbody>
		<?php
		$start = 0;
		foreach ($Persediaan_rows as $persediaan) {
			$total_10_row = persediaan_hitung_total_10_net($persediaan);
			$nilai_persediaan_row = persediaan_hitung_nilai_persediaan_row($persediaan);
			$total_total_10 += $total_10_row;
			$total_nilai_persediaan += $nilai_persediaan_row;
			foreach (persediaan_list_unit_columns() as $uf_total) {
				$total_nominal_unit[$uf_total] += persediaan_hitung_kolom_nominal_row($persediaan, $uf_total);
			}
		?>
			<tr>
				<td><?php echo ++$start ?></td>
				<td><?php echo persediaan_format_bulan_tahun($persediaan, $bulan_tampil); ?></td>
				<td><?php echo $persediaan->namabarang ?></td>
				<td><?php echo $persediaan->satuan ?></td>
				<td class="text-right persediaan-col-money"><?php echo persediaan_tampil_hpp_row($persediaan); ?></td>
				<td><?php echo $persediaan->sa ?></td>
				<td><?php echo $persediaan->spop ?></td>
				<td><?php echo $persediaan->beli ?></td>
				<td><?php echo $persediaan->tuj ?></td>
				<?php foreach ($persediaan_fields_tgl_total as $field_tgl_total) { ?>
					<td><?php
						if ($field_tgl_total === 'total_10') {
							echo persediaan_tampil_total_10_net_row($persediaan);
						} else {
							echo persediaan_row_get($persediaan, $field_tgl_total);
						}
					?></td>
					<?php if (persediaan_field_has_nominal_column($field_tgl_total)) { ?>
						<td class="text-right persediaan-col-money"><?php echo persediaan_tampil_kolom_nominal_row($persediaan, $field_tgl_total); ?></td>
					<?php } ?>
				<?php } ?>
				<td class="text-right persediaan-col-money"><?php echo persediaan_tampil_nilai_persediaan_row($persediaan); ?></td>
				<?php if ($show_keluar_columns) { ?>
				<td><?php echo isset($persediaan->penjualan) ? $persediaan->penjualan : 0 ?></td>
				<td><?php echo isset($persediaan->pecah_satuan) ? $persediaan->pecah_satuan : 0 ?></td>
				<td><?php echo isset($persediaan->bahan_produksi) ? $persediaan->bahan_produksi : 0 ?></td>
				<?php } ?>
			</tr>
		<?php } ?>
	</tbody>
	<tfoot>
		<tr>
			<?php
			$footer_cells = persediaan_datatable_footer_cells($total_total_10, $total_nilai_persediaan, $total_nominal_unit, null, $show_keluar_columns);
			$idx_foot_total_10 = persediaan_list_col_index_total_10();
			$idx_foot_nilai = persediaan_list_col_index_nilai_persediaan();
			$idx_foot_nominal = array();
			foreach (persediaan_list_unit_columns() as $uf_foot) {
				if (persediaan_field_has_nominal_column($uf_foot)) {
					$idx_foot_nominal[] = persediaan_list_col_index_unit_nominal($uf_foot);
				}
			}
			foreach ($footer_cells as $col_foot => $foot_val) {
				$foot_val = (string) $foot_val;
				$cls = '';
				if ($foot_val === 'Total') {
					$cls = ' persediaan-foot-total-label';
				} elseif ($foot_val !== '' && (
					$col_foot === $idx_foot_total_10
					|| persediaan_tab_data_is_money_column($col_foot)
					|| in_array($col_foot, $idx_foot_nominal, true)
				)) {
					$cls = ' persediaan-foot-num';
				}
				if (persediaan_tab_data_is_money_column($col_foot) && $foot_val !== '' && $foot_val !== 'Total') {
					$cls .= ' persediaan-col-money';
				}
				echo '<th class="' . trim($cls) . '">' . htmlspecialchars($foot_val, ENT_QUOTES, 'UTF-8') . '</th>';
			}
			?>
		</tr>
	</tfoot>
</table>
</div>
