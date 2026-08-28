<?php
/**
 * Tab Belum ke Persediaan — 3 sub-tab, masing-masing 1 datatable.
 */
if (!isset($Tbl_penjualan_data_belum_persediaan) || !is_array($Tbl_penjualan_data_belum_persediaan)) {
	$Tbl_penjualan_data_belum_persediaan = isset($Tbl_penjualan_tab_data) && is_array($Tbl_penjualan_tab_data)
		? $Tbl_penjualan_tab_data
		: array();
}
if (!isset($Tbl_penjualan_data_persediaan_manual) || !is_array($Tbl_penjualan_data_persediaan_manual)) {
	$Tbl_penjualan_data_persediaan_manual = array();
}
if (!isset($Tbl_penjualan_data_persediaan_otomatis) || !is_array($Tbl_penjualan_data_persediaan_otomatis)) {
	$Tbl_penjualan_data_persediaan_otomatis = array();
}
if (!isset($penjualan_count_belum_persediaan)) {
	$penjualan_count_belum_persediaan = count($Tbl_penjualan_data_belum_persediaan);
}
if (!isset($penjualan_count_persediaan_manual)) {
	$penjualan_count_persediaan_manual = count($Tbl_penjualan_data_persediaan_manual);
}
if (!isset($penjualan_count_persediaan_otomatis)) {
	$penjualan_count_persediaan_otomatis = count($Tbl_penjualan_data_persediaan_otomatis);
}

$persediaan_subtab_ns = isset($persediaan_subtab_ns) ? trim((string) $persediaan_subtab_ns) : 'penjualan';
if ($persediaan_subtab_ns === '') {
	$persediaan_subtab_ns = 'penjualan';
}
$persediaan_table_prefix = isset($persediaan_table_prefix) ? trim((string) $persediaan_table_prefix) : '';
if ($persediaan_subtab_ns === 'penjualan') {
	$subtabs_ul_id = 'penjualan-persediaan-subtabs';
	$subtabs_content_id = 'penjualan-persediaan-subtabs-content';
	$subtab_id_prefix = 'subtab-persediaan-';
} else {
	$subtabs_ul_id = $persediaan_subtab_ns . '-persediaan-subtabs';
	$subtabs_content_id = $persediaan_subtab_ns . '-persediaan-subtabs-content';
	$subtab_id_prefix = $persediaan_subtab_ns . '-persediaan-';
}

$persediaan_sections = array(
	array(
		'key' => 'belum',
		'tab_id' => $subtab_id_prefix . 'belum',
		'link_id' => $subtab_id_prefix . 'belum-link',
		'title' => 'Belum Terverifikasi',
		'badge_class' => 'badge-warning',
		'table_id' => 'tglSPOPFreeze' . $persediaan_table_prefix . 'BelumPersediaan',
		'data' => $Tbl_penjualan_data_belum_persediaan,
		'count' => (int) $penjualan_count_belum_persediaan,
		'show_referensi' => true,
		'excel_btn_id' => 'btn-cetak-excel-' . $persediaan_subtab_ns . '-belum-persediaan',
		'excel_filename' => ($persediaan_table_prefix !== '' ? 'Penjualan_Jasa_' : 'Penjualan_') . 'Belum_Verifikasi_Persediaan',
		'status_badge_class' => 'badge-danger',
		'active' => true,
	),
	array(
		'key' => 'manual',
		'tab_id' => $subtab_id_prefix . 'manual',
		'link_id' => $subtab_id_prefix . 'manual-link',
		'title' => 'Terverifikasi Manual',
		'badge_class' => 'badge-info',
		'table_id' => 'tglSPOPFreeze' . $persediaan_table_prefix . 'PersediaanManual',
		'data' => $Tbl_penjualan_data_persediaan_manual,
		'count' => (int) $penjualan_count_persediaan_manual,
		'show_referensi' => false,
		'excel_btn_id' => 'btn-cetak-excel-' . $persediaan_subtab_ns . '-persediaan-manual',
		'excel_filename' => ($persediaan_table_prefix !== '' ? 'Penjualan_Jasa_' : 'Penjualan_') . 'Terverifikasi_Manual_Persediaan',
		'status_badge_class' => 'badge-info',
		'active' => false,
	),
	array(
		'key' => 'otomatis',
		'tab_id' => $subtab_id_prefix . 'otomatis',
		'link_id' => $subtab_id_prefix . 'otomatis-link',
		'title' => 'Verifikasi Otomatis',
		'badge_class' => 'badge-success',
		'table_id' => 'tglSPOPFreeze' . $persediaan_table_prefix . 'PersediaanOtomatis',
		'data' => $Tbl_penjualan_data_persediaan_otomatis,
		'count' => (int) $penjualan_count_persediaan_otomatis,
		'show_referensi' => false,
		'excel_btn_id' => 'btn-cetak-excel-' . $persediaan_subtab_ns . '-persediaan-otomatis',
		'excel_filename' => ($persediaan_table_prefix !== '' ? 'Penjualan_Jasa_' : 'Penjualan_') . 'Verifikasi_Otomatis_Persediaan',
		'status_badge_class' => 'badge-success',
		'active' => false,
	),
);
?>
<ul class="nav nav-pills mb-3" id="<?php echo htmlspecialchars($subtabs_ul_id, ENT_QUOTES, 'UTF-8'); ?>" role="tablist">
	<?php foreach ($persediaan_sections as $sec) :
		$nav_active = !empty($sec['active']) ? ' active' : '';
	?>
		<li class="nav-item">
			<a class="nav-link<?php echo $nav_active; ?>" id="<?php echo htmlspecialchars($sec['link_id'], ENT_QUOTES, 'UTF-8'); ?>"
				data-toggle="tab" href="#<?php echo htmlspecialchars($sec['tab_id'], ENT_QUOTES, 'UTF-8'); ?>" role="tab"
				data-table-id="<?php echo htmlspecialchars($sec['table_id'], ENT_QUOTES, 'UTF-8'); ?>">
				<?php echo htmlspecialchars($sec['title'], ENT_QUOTES, 'UTF-8'); ?>
				<span class="badge <?php echo htmlspecialchars($sec['badge_class'], ENT_QUOTES, 'UTF-8'); ?> ml-1"><?php echo (int) $sec['count']; ?></span>
			</a>
		</li>
	<?php endforeach; ?>
</ul>

<div class="tab-content" id="<?php echo htmlspecialchars($subtabs_content_id, ENT_QUOTES, 'UTF-8'); ?>">
	<?php foreach ($persediaan_sections as $sec) :
		$pane_active = !empty($sec['active']) ? ' show active' : '';
	?>
		<div class="tab-pane fade<?php echo $pane_active; ?>" id="<?php echo htmlspecialchars($sec['tab_id'], ENT_QUOTES, 'UTF-8'); ?>" role="tabpanel">
			<div class="d-flex justify-content-end mb-2">
				<button type="button" class="btn btn-sm btn-success btn-cetak-excel-persediaan-section"
					id="<?php echo htmlspecialchars($sec['excel_btn_id'], ENT_QUOTES, 'UTF-8'); ?>"
					data-table="#<?php echo htmlspecialchars($sec['table_id'], ENT_QUOTES, 'UTF-8'); ?>"
					data-filename="<?php echo htmlspecialchars($sec['excel_filename'], ENT_QUOTES, 'UTF-8'); ?>">
					<i class="fas fa-file-excel mr-1"></i> Cetak ke Excel
				</button>
			</div>
			<div class="table-responsive">
				<table id="<?php echo htmlspecialchars($sec['table_id'], ENT_QUOTES, 'UTF-8'); ?>"
					class="table table-sm table-bordered table-striped display nowrap penjualan-persediaan-dt-table"
					style="width:100%;">
					<thead>
						<tr>
							<th>No</th>
							<?php if (!empty($sec['show_referensi'])) : ?><th>Referensi</th><?php endif; ?>
							<th>Tgl Jual</th>
							<th>Nama Barang</th>
							<th>Satuan</th>
							<th style="text-align:right">Jumlah</th>
							<th style="text-align:right">Harga Satuan</th>
							<th style="text-align:right">Harga Total</th>
							<th>Konsumen</th>
							<th>Unit</th>
							<th>Status</th>
							<th>Keterangan</th>
							<th>UUID Persediaan</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if (!function_exists('persediaan_parse_angka')) {
							$CI_pj = function_exists('get_instance') ? get_instance() : null;
							if ($CI_pj) {
								$CI_pj->load->helper('persediaan_display');
							}
						}
						$no = 0;
						$sec_total_jumlah = 0.0;
						$sec_total_harga = 0.0;
						foreach ($sec['data'] as $row) :
							$no++;
							$id_pj = isset($row->id_penjualan) ? (int) $row->id_penjualan : (isset($row->id) ? (int) $row->id : 0);
							$tgl = isset($row->tgl_jual_display) ? $row->tgl_jual_display : (isset($row->tgl_jual) ? date('Y-m-d', strtotime($row->tgl_jual)) : '');
							$nama = isset($row->nama_barang) ? (string) $row->nama_barang : '';
							$satuan = isset($row->satuan) ? (string) $row->satuan : '';
							$jumlah_raw = isset($row->jumlah) ? $row->jumlah : (isset($row->jumlah_display) ? $row->jumlah_display : 0);
							$jumlah_num = function_exists('persediaan_parse_angka')
								? persediaan_parse_angka($jumlah_raw)
								: (float) $jumlah_raw;
							$harga_satuan_num = isset($row->harga_satuan) ? (float) $row->harga_satuan : 0.0;
							$harga_total_num = $jumlah_num * $harga_satuan_num;
							$sec_total_jumlah += $jumlah_num;
							$sec_total_harga += $harga_total_num;
							$jumlah = isset($row->jumlah_display) ? $row->jumlah_display : (string) $jumlah_raw;
							if (!isset($row->jumlah_display)) {
								$jumlah = (abs($jumlah_num - round($jumlah_num)) < 0.0001)
									? (string) (int) round($jumlah_num)
									: number_format($jumlah_num, 2, ',', '.');
							}
							$harga_satuan_tampil = number_format($harga_satuan_num, 2, ',', '.');
							$harga_total_tampil = number_format($harga_total_num, 2, ',', '.');
							$konsumen = isset($row->konsumen_nama) ? (string) $row->konsumen_nama : '';
							$unit = isset($row->unit) ? (string) $row->unit : '';
							$uuid = isset($row->uuid_persediaan) ? (string) $row->uuid_persediaan : '';
							$ket = isset($row->keterangan) ? (string) $row->keterangan : '';
							$status = isset($row->status) ? (string) $row->status : '';
						?>
							<tr data-penjualan-id="<?php echo (int) $id_pj; ?>">
								<td><?php echo (int) (isset($row->no) ? $row->no : $no); ?></td>
								<?php if (!empty($sec['show_referensi'])) : ?>
									<td>
										<?php if ($id_pj > 0) : ?>
											<button type="button" class="btn btn-xs btn-info btn-pj-referensi-persediaan" data-id-penjualan="<?php echo (int) $id_pj; ?>"
												data-nama-barang="<?php echo htmlspecialchars($nama, ENT_QUOTES, 'UTF-8'); ?>"
												data-satuan="<?php echo htmlspecialchars($satuan, ENT_QUOTES, 'UTF-8'); ?>"
												data-jumlah="<?php echo htmlspecialchars((string) $jumlah, ENT_QUOTES, 'UTF-8'); ?>"
												data-konsumen="<?php echo htmlspecialchars($konsumen, ENT_QUOTES, 'UTF-8'); ?>"
												data-unit="<?php echo htmlspecialchars($unit, ENT_QUOTES, 'UTF-8'); ?>">
												Referensi
											</button>
										<?php endif; ?>
									</td>
								<?php endif; ?>
								<td><?php echo htmlspecialchars((string) $tgl, ENT_QUOTES, 'UTF-8'); ?></td>
								<td><?php echo htmlspecialchars($nama, ENT_QUOTES, 'UTF-8'); ?></td>
								<td><?php echo htmlspecialchars($satuan, ENT_QUOTES, 'UTF-8'); ?></td>
								<td align="right" class="pj-persediaan-col-jumlah" data-num="<?php echo htmlspecialchars((string) $jumlah_num, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars((string) $jumlah, ENT_QUOTES, 'UTF-8'); ?></td>
								<td align="right"><?php echo htmlspecialchars($harga_satuan_tampil, ENT_QUOTES, 'UTF-8'); ?></td>
								<td align="right" class="pj-persediaan-col-harga-total" data-num="<?php echo htmlspecialchars((string) $harga_total_num, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($harga_total_tampil, ENT_QUOTES, 'UTF-8'); ?></td>
								<td><?php echo htmlspecialchars($konsumen, ENT_QUOTES, 'UTF-8'); ?></td>
								<td><?php echo htmlspecialchars($unit, ENT_QUOTES, 'UTF-8'); ?></td>
								<td><span class="badge <?php echo htmlspecialchars($sec['status_badge_class'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($status, ENT_QUOTES, 'UTF-8'); ?></span></td>
								<td><small><?php echo htmlspecialchars($ket, ENT_QUOTES, 'UTF-8'); ?></small></td>
								<td><small><?php echo htmlspecialchars($uuid, ENT_QUOTES, 'UTF-8'); ?></small></td>
							</tr>
						<?php endforeach; ?>
						<?php
						$sec_total_jumlah_tampil = (abs($sec_total_jumlah - round($sec_total_jumlah)) < 0.0001)
							? number_format($sec_total_jumlah, 0, ',', '.')
							: number_format($sec_total_jumlah, 2, ',', '.');
						$sec_total_harga_tampil = number_format($sec_total_harga, 2, ',', '.');
						?>
					</tbody>
					<tfoot>
						<tr class="pj-persediaan-total-row">
							<th>TOTAL</th>
							<?php if (!empty($sec['show_referensi'])) : ?><th></th><?php endif; ?>
							<th></th>
							<th></th>
							<th></th>
							<th style="text-align:right" class="pj-persediaan-foot-jumlah"><?php echo htmlspecialchars($sec_total_jumlah_tampil, ENT_QUOTES, 'UTF-8'); ?></th>
							<th></th>
							<th style="text-align:right" class="pj-persediaan-foot-harga-total"><?php echo htmlspecialchars($sec_total_harga_tampil, ENT_QUOTES, 'UTF-8'); ?></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	<?php endforeach; ?>
</div>
