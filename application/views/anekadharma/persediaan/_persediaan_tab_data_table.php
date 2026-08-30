<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

$Persediaan_rows = isset($Persediaan_rows) && is_array($Persediaan_rows) ? $Persediaan_rows : array();
$table_id = isset($table_id) ? (string) $table_id : 'table-persediaan';
$bulan_tampil = isset($bulan_tampil) ? (string) $bulan_tampil : date('Y-m');
$tab_mode = isset($tab_mode) ? (string) $tab_mode : 'barang';
$is_jasa_tab = (strtolower(trim($tab_mode)) === 'jasa');
$is_draft_referensi_tab = (strtolower(trim($tab_mode)) === 'draft_referensi');
$show_id_column = (!$is_jasa_tab && !$is_draft_referensi_tab);
$show_keluar_nominal_columns = $show_id_column;
$col_offset = $show_id_column ? 1 : 0;
$show_keluar_columns = persediaan_tab_data_show_keluar_columns($is_draft_referensi_tab ? 'barang' : $tab_mode);
$nama_barang_header = persediaan_tab_data_nama_barang_header($is_draft_referensi_tab ? 'barang' : $tab_mode);
$fixed_left_columns = persediaan_tab_data_fixed_left_columns() + $col_offset;
$nama_col_index = 2 + $col_offset;

$persediaan_fields_tgl_total = persediaan_list_fields_tgl_keluar_sampai_total_10();
$money_col_indexes = persediaan_tab_data_money_column_indexes();
if ($show_id_column) {
	$money_col_indexes = array_map(function ($i) {
		return (int) $i + 1;
	}, $money_col_indexes);
}
if ($show_keluar_nominal_columns) {
	$money_col_indexes[] = persediaan_list_col_index_terjual_nominal() + $col_offset;
	$money_col_indexes[] = persediaan_list_col_index_pecah_satuan_nominal(null, true) + $col_offset;
	$money_col_indexes[] = persediaan_list_col_index_bahan_produksi_nominal(null, true) + $col_offset;
	$money_col_indexes = array_values(array_unique(array_map('intval', $money_col_indexes)));
}
$total_total_10 = 0;
$total_nilai_persediaan = 0;
$total_sa = 0;
$total_sa_nominal = 0;
$total_beli = 0;
$total_beli_nominal = 0;
$total_nominal_unit = array();
$total_qty_unit = array();
foreach (persediaan_list_unit_columns() as $uf_total) {
	$total_nominal_unit[$uf_total] = 0;
	$total_qty_unit[$uf_total] = 0;
}
$total_terjual = 0;
$total_pecah_satuan = 0;
$total_bahan_produksi = 0;
$total_terjual_nominal = 0;
$total_pecah_nominal = 0;
$total_bahan_nominal = 0;
$excel_jenis = isset($excel_jenis) ? trim((string) $excel_jenis) : '';
?>
<?php if ($excel_jenis !== '') {
	$this->load->view('anekadharma/persediaan/_gen_proses_excel_btn', array(
		'excel_jenis' => $excel_jenis,
		'excel_title' => isset($excel_title) ? $excel_title : 'Export datatable persediaan ke Excel',
	));
} ?>
<div class="persediaan-tab-dt-wrap">
<table id="<?php echo htmlspecialchars($table_id, ENT_QUOTES, 'UTF-8'); ?>" class="table table-bordered table-striped persediaan-tab-dt<?php echo $is_jasa_tab ? ' persediaan-jasa-dt' : ''; ?>" style="width:100%;font-size:16px;" data-money-cols="<?php echo htmlspecialchars(json_encode(array_values($money_col_indexes)), ENT_QUOTES, 'UTF-8'); ?>" data-fixed-left="<?php echo (int) $fixed_left_columns; ?>" data-order-col="<?php echo (int) $nama_col_index; ?>">
	<thead>
		<tr>
			<th width="50px">No</th>
			<?php if ($show_id_column) : ?><th width="70px">ID</th><?php endif; ?>
			<th>Tanggal</th>
			<th><?php echo htmlspecialchars($nama_barang_header, ENT_QUOTES, 'UTF-8'); ?></th>
			<th>Satuan</th>
			<th class="text-right persediaan-col-money">Hpp</th>
			<th>Sa</th>
			<th class="text-right persediaan-col-money">Sa. Nominal</th>
			<th>Spop</th>
			<th>Beli</th>
			<th class="text-right persediaan-col-money">Beli Nmnl</th>
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
			<?php if ($show_keluar_nominal_columns) { ?>
			<th class="text-right persediaan-col-money">Nominal Terjual</th>
			<?php } ?>
			<th>Jumlah Pecah Satuan</th>
			<?php if ($show_keluar_nominal_columns) { ?>
			<th class="text-right persediaan-col-money">Nominal Pecah Satuan</th>
			<?php } ?>
			<th>Bahan Produksi</th>
			<?php if ($show_keluar_nominal_columns) { ?>
			<th class="text-right persediaan-col-money">Nominal Bahan Produksi</th>
			<?php } ?>
			<?php } ?>
		</tr>
	</thead>
	<tbody>
		<?php
		$start = 0;
		foreach ($Persediaan_rows as $persediaan) {
			if ($is_draft_referensi_tab) {
				// History referensi: tampilkan nilai tersimpan apa adanya (jangan pakai rumus kalkulasi).
				$total_10_row = persediaan_parse_angka(isset($persediaan->total_10) ? $persediaan->total_10 : 0);
				$nilai_persediaan_row = persediaan_parse_angka(isset($persediaan->nilai_persediaan) ? $persediaan->nilai_persediaan : 0);
			} else {
				$total_10_row = persediaan_hitung_total_10_kalkulasi($persediaan);
				$nilai_persediaan_row = persediaan_hitung_nilai_persediaan_row($persediaan);
			}
			$total_total_10 += $total_10_row;
			$total_nilai_persediaan += $nilai_persediaan_row;
			$total_sa += persediaan_parse_angka(isset($persediaan->sa) ? $persediaan->sa : 0);
			$total_sa_nominal += persediaan_hitung_sa_nominal_row($persediaan);
			$total_beli += persediaan_parse_angka(isset($persediaan->beli) ? $persediaan->beli : 0);
			$total_beli_nominal += persediaan_hitung_beli_nominal_row($persediaan);
			foreach (persediaan_list_unit_columns() as $uf_total) {
				$total_nominal_unit[$uf_total] += persediaan_hitung_kolom_nominal_row($persediaan, $uf_total);
				$total_qty_unit[$uf_total] += persediaan_parse_angka(persediaan_row_get($persediaan, $uf_total));
			}
			if ($show_keluar_columns) {
				$total_terjual += persediaan_parse_angka(isset($persediaan->penjualan) ? $persediaan->penjualan : 0);
				$total_pecah_satuan += persediaan_parse_angka(isset($persediaan->pecah_satuan) ? $persediaan->pecah_satuan : 0);
				$total_bahan_produksi += persediaan_parse_angka(isset($persediaan->bahan_produksi) ? $persediaan->bahan_produksi : 0);
				if ($show_keluar_nominal_columns) {
					$total_terjual_nominal += persediaan_hitung_kolom_nominal_row($persediaan, 'penjualan');
					$total_pecah_nominal += persediaan_hitung_kolom_nominal_row($persediaan, 'pecah_satuan');
					$total_bahan_nominal += persediaan_hitung_kolom_nominal_row($persediaan, 'bahan_produksi');
				}
			}
		?>
			<tr>
				<td><?php echo ++$start ?></td>
				<?php if ($show_id_column) : ?>
					<td><?php echo (int) $persediaan->id; ?></td>
				<?php endif; ?>
				<td class="<?php echo $is_jasa_tab ? 'persediaan-jasa-col-tanggal' : ''; ?>">
					<div class="persediaan-tanggal-text"><?php echo persediaan_format_bulan_tahun($persediaan, $bulan_tampil); ?></div>
					<?php if ($is_jasa_tab) {
						$jasa_nama = isset($persediaan->namabarang) ? (string) $persediaan->namabarang : '';
					?>
					<div class="persediaan-jasa-row-aksi mt-1">
						<button type="button" class="btn btn-warning btn-xs btn-ubah-persediaan-jasa-row" data-id="<?php echo (int) $persediaan->id; ?>" data-namabarang="<?php echo htmlspecialchars($jasa_nama, ENT_QUOTES, 'UTF-8'); ?>" title="Ubah data jasa">
							<i class="fas fa-edit"></i> Ubah
						</button>
						<button type="button" class="btn btn-danger btn-xs btn-hapus-persediaan-jasa-row" data-id="<?php echo (int) $persediaan->id; ?>" data-namabarang="<?php echo htmlspecialchars($jasa_nama, ENT_QUOTES, 'UTF-8'); ?>" title="Hapus data jasa">
							<i class="fas fa-trash"></i> Hapus
						</button>
					</div>
					<?php } ?>
				</td>
				<td><?php echo $persediaan->namabarang ?></td>
				<td><?php echo $persediaan->satuan ?></td>
				<td class="text-right persediaan-col-money"><?php echo persediaan_tampil_hpp_row($persediaan); ?></td>
				<td><?php echo $persediaan->sa ?></td>
				<td class="text-right persediaan-col-money"><?php echo persediaan_tampil_sa_nominal_row($persediaan); ?></td>
				<td><?php echo $persediaan->spop ?></td>
				<td><?php echo $persediaan->beli ?></td>
				<td class="text-right persediaan-col-money"><?php echo persediaan_tampil_beli_nominal_row($persediaan); ?></td>
				<td><?php echo $persediaan->tuj ?></td>
				<?php foreach ($persediaan_fields_tgl_total as $field_tgl_total) { ?>
					<td><?php
						if ($field_tgl_total === 'total_10') {
							if ($is_draft_referensi_tab) {
								echo persediaan_format_angka_tampil($total_10_row);
							} else {
								echo persediaan_tampil_total_10_net_row($persediaan);
							}
						} else {
							echo persediaan_row_get($persediaan, $field_tgl_total);
						}
					?></td>
					<?php if (persediaan_field_has_nominal_column($field_tgl_total)) { ?>
						<td class="text-right persediaan-col-money"><?php echo persediaan_tampil_kolom_nominal_row($persediaan, $field_tgl_total); ?></td>
					<?php } ?>
				<?php } ?>
				<td class="text-right persediaan-col-money"><?php
					if ($is_draft_referensi_tab) {
						echo persediaan_format_rupiah_tampil($nilai_persediaan_row, true);
					} else {
						echo persediaan_tampil_nilai_persediaan_row($persediaan);
					}
				?></td>
				<?php if ($show_keluar_columns) { ?>
				<td><?php echo isset($persediaan->penjualan) ? $persediaan->penjualan : 0 ?></td>
				<?php if ($show_keluar_nominal_columns) { ?>
				<td class="text-right persediaan-col-money"><?php echo persediaan_tampil_kolom_nominal_row($persediaan, 'penjualan'); ?></td>
				<?php } ?>
				<td><?php echo isset($persediaan->pecah_satuan) ? $persediaan->pecah_satuan : 0 ?></td>
				<?php if ($show_keluar_nominal_columns) { ?>
				<td class="text-right persediaan-col-money"><?php echo persediaan_tampil_kolom_nominal_row($persediaan, 'pecah_satuan'); ?></td>
				<?php } ?>
				<td><?php echo isset($persediaan->bahan_produksi) ? $persediaan->bahan_produksi : 0 ?></td>
				<?php if ($show_keluar_nominal_columns) { ?>
				<td class="text-right persediaan-col-money"><?php echo persediaan_tampil_kolom_nominal_row($persediaan, 'bahan_produksi'); ?></td>
				<?php } ?>
				<?php } ?>
			</tr>
		<?php } ?>
	</tbody>
	<tfoot>
		<tr>
			<?php
			$footer_cells = persediaan_datatable_footer_cells($total_total_10, $total_nilai_persediaan, $total_nominal_unit, null, $show_keluar_columns, $total_sa, $total_beli, $total_sa_nominal, $total_beli_nominal, $total_terjual, $total_pecah_satuan, $total_bahan_produksi, $total_qty_unit, $show_keluar_nominal_columns, $total_terjual_nominal, $total_pecah_nominal, $total_bahan_nominal);
			if ($show_id_column) {
				$shifted_footer = array();
				foreach ($footer_cells as $idx => $val) {
					$shifted_footer[($idx >= 1) ? ($idx + 1) : $idx] = $val;
				}
				$shifted_footer[1] = '';
				ksort($shifted_footer);
				$footer_cells = $shifted_footer;
			}
			$idx_col_shift = $show_id_column ? 1 : 0;
			$idx_foot_total_10 = persediaan_list_col_index_total_10() + $idx_col_shift;
			$idx_foot_nilai = persediaan_list_col_index_nilai_persediaan() + $idx_col_shift;
			$idx_foot_sa = persediaan_list_col_index_sa() + $idx_col_shift;
			$idx_foot_sa_nominal = persediaan_list_col_index_sa_nominal() + $idx_col_shift;
			$idx_foot_beli = persediaan_list_col_index_beli() + $idx_col_shift;
			$idx_foot_beli_nominal = persediaan_list_col_index_beli_nominal() + $idx_col_shift;
			$idx_foot_terjual = persediaan_list_col_index_terjual() + $idx_col_shift;
			$idx_foot_terjual_nominal = persediaan_list_col_index_terjual_nominal() + $idx_col_shift;
			$idx_foot_pecah_satuan = persediaan_list_col_index_pecah_satuan(null, $show_keluar_nominal_columns) + $idx_col_shift;
			$idx_foot_pecah_satuan_nominal = persediaan_list_col_index_pecah_satuan_nominal(null, $show_keluar_nominal_columns) + $idx_col_shift;
			$idx_foot_bahan_produksi = persediaan_list_col_index_bahan_produksi(null, $show_keluar_nominal_columns) + $idx_col_shift;
			$idx_foot_bahan_produksi_nominal = persediaan_list_col_index_bahan_produksi_nominal(null, $show_keluar_nominal_columns) + $idx_col_shift;
			$idx_foot_nominal = array();
			foreach (persediaan_list_unit_columns() as $uf_foot) {
				if (persediaan_field_has_nominal_column($uf_foot)) {
					$idx_foot_nominal[] = persediaan_list_col_index_unit_nominal($uf_foot) + $idx_col_shift;
				}
			}
			$idx_foot_unit_qty = array();
			$scan_foot = persediaan_list_prefix_column_count() + $idx_col_shift;
			foreach (persediaan_list_fields_tgl_keluar_sampai_total_10() as $f_scan) {
				if ($f_scan === 'tgl_keluar') {
					$scan_foot++;
					continue;
				}
				if ($f_scan === 'total_10') {
					$scan_foot++;
					continue;
				}
				$idx_foot_unit_qty[] = $scan_foot;
				$scan_foot++;
				if (persediaan_field_has_nominal_column($f_scan)) {
					$scan_foot++;
				}
			}
			foreach ($footer_cells as $col_foot => $foot_val) {
				$foot_val = (string) $foot_val;
				$cls = '';
				if ($foot_val === 'Total') {
					$cls = ' persediaan-foot-total-label';
				} elseif ($foot_val !== '' && (
					$col_foot === $idx_foot_total_10
					|| $col_foot === $idx_foot_sa
					|| $col_foot === $idx_foot_sa_nominal
					|| $col_foot === $idx_foot_beli
					|| $col_foot === $idx_foot_beli_nominal
					|| $col_foot === $idx_foot_terjual
					|| ($show_keluar_nominal_columns && $col_foot === $idx_foot_terjual_nominal)
					|| $col_foot === $idx_foot_pecah_satuan
					|| ($show_keluar_nominal_columns && $col_foot === $idx_foot_pecah_satuan_nominal)
					|| $col_foot === $idx_foot_bahan_produksi
					|| ($show_keluar_nominal_columns && $col_foot === $idx_foot_bahan_produksi_nominal)
					|| persediaan_tab_data_is_money_column($col_foot)
					|| in_array($col_foot, $idx_foot_nominal, true)
					|| in_array($col_foot, $idx_foot_unit_qty, true)
				)) {
					$cls = ' persediaan-foot-num';
				}
				if (persediaan_tab_data_is_money_column($col_foot) && $foot_val !== '' && $foot_val !== 'Total') {
					$cls .= ' persediaan-col-money';
				} elseif ($show_keluar_nominal_columns && $foot_val !== '' && $foot_val !== 'Total' && (
					$col_foot === $idx_foot_terjual_nominal
					|| $col_foot === $idx_foot_pecah_satuan_nominal
					|| $col_foot === $idx_foot_bahan_produksi_nominal
				)) {
					$cls .= ' persediaan-col-money';
				}
				echo '<th class="' . trim($cls) . '">' . htmlspecialchars($foot_val, ENT_QUOTES, 'UTF-8') . '</th>';
			}
			?>
		</tr>
	</tfoot>
</table>
</div>
