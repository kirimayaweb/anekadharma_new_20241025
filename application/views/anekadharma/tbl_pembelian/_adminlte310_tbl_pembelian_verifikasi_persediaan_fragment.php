<?php
/**
 * Tab Verifikasi Persediaan Pembelian — 3 sub-tab (Belum / Manual / Otomatis).
 */
if (!isset($Tbl_pembelian_data_belum_persediaan) || !is_array($Tbl_pembelian_data_belum_persediaan)) {
	$Tbl_pembelian_data_belum_persediaan = array();
}
if (!isset($Tbl_pembelian_data_persediaan_manual) || !is_array($Tbl_pembelian_data_persediaan_manual)) {
	$Tbl_pembelian_data_persediaan_manual = array();
}
if (!isset($Tbl_pembelian_data_persediaan_otomatis) || !is_array($Tbl_pembelian_data_persediaan_otomatis)) {
	$Tbl_pembelian_data_persediaan_otomatis = array();
}
if (!isset($pembelian_count_belum_persediaan)) {
	$pembelian_count_belum_persediaan = count($Tbl_pembelian_data_belum_persediaan);
}
if (!isset($pembelian_count_persediaan_manual)) {
	$pembelian_count_persediaan_manual = count($Tbl_pembelian_data_persediaan_manual);
}
if (!isset($pembelian_count_persediaan_otomatis)) {
	$pembelian_count_persediaan_otomatis = count($Tbl_pembelian_data_persediaan_otomatis);
}

$pembelian_verifikasi_ns = isset($pembelian_verifikasi_ns) ? trim((string) $pembelian_verifikasi_ns) : 'pembelian';
if ($pembelian_verifikasi_ns === '') {
	$pembelian_verifikasi_ns = 'pembelian';
}
$pembelian_table_prefix = isset($pembelian_table_prefix) ? trim((string) $pembelian_table_prefix) : '';
$is_jasa = ($pembelian_verifikasi_ns === 'pembelian-jasa' || $pembelian_table_prefix !== '');

$subtabs_ul_id = $pembelian_verifikasi_ns . '-persediaan-subtabs';
$subtabs_content_id = $pembelian_verifikasi_ns . '-persediaan-subtabs-content';
$subtab_id_prefix = $pembelian_verifikasi_ns . '-persediaan-';

$label_modul = $is_jasa ? 'Pembelian Jasa' : 'Pembelian';

$persediaan_sections = array(
	array(
		'key' => 'belum',
		'tab_id' => $subtab_id_prefix . 'belum',
		'link_id' => $subtab_id_prefix . 'belum-link',
		'title' => 'Belum Terverifikasi',
		'badge_class' => 'badge-warning',
		'table_id' => 'tglSPOPFreeze' . $pembelian_table_prefix . 'BelumPersediaan',
		'data' => $Tbl_pembelian_data_belum_persediaan,
		'count' => (int) $pembelian_count_belum_persediaan,
		'show_referensi' => true,
		'excel_btn_id' => 'btn-cetak-excel-' . $pembelian_verifikasi_ns . '-belum-persediaan',
		'excel_filename' => $label_modul . '_Belum_Verifikasi_Persediaan',
		'status_badge_class' => 'badge-danger',
		'active' => true,
	),
	array(
		'key' => 'manual',
		'tab_id' => $subtab_id_prefix . 'manual',
		'link_id' => $subtab_id_prefix . 'manual-link',
		'title' => 'Terverifikasi Manual',
		'badge_class' => 'badge-info',
		'table_id' => 'tglSPOPFreeze' . $pembelian_table_prefix . 'PersediaanManual',
		'data' => $Tbl_pembelian_data_persediaan_manual,
		'count' => (int) $pembelian_count_persediaan_manual,
		'show_referensi' => false,
		'excel_btn_id' => 'btn-cetak-excel-' . $pembelian_verifikasi_ns . '-persediaan-manual',
		'excel_filename' => $label_modul . '_Terverifikasi_Manual_Persediaan',
		'status_badge_class' => 'badge-info',
		'active' => false,
	),
	array(
		'key' => 'otomatis',
		'tab_id' => $subtab_id_prefix . 'otomatis',
		'link_id' => $subtab_id_prefix . 'otomatis-link',
		'title' => 'Verifikasi Otomatis',
		'badge_class' => 'badge-success',
		'table_id' => 'tglSPOPFreeze' . $pembelian_table_prefix . 'PersediaanOtomatis',
		'data' => $Tbl_pembelian_data_persediaan_otomatis,
		'count' => (int) $pembelian_count_persediaan_otomatis,
		'show_referensi' => false,
		'excel_btn_id' => 'btn-cetak-excel-' . $pembelian_verifikasi_ns . '-persediaan-otomatis',
		'excel_filename' => $label_modul . '_Verifikasi_Otomatis_Persediaan',
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
				<button type="button" class="btn btn-sm btn-success btn-cetak-excel-pembelian-persediaan-section"
					id="<?php echo htmlspecialchars($sec['excel_btn_id'], ENT_QUOTES, 'UTF-8'); ?>"
					data-table="#<?php echo htmlspecialchars($sec['table_id'], ENT_QUOTES, 'UTF-8'); ?>"
					data-filename="<?php echo htmlspecialchars($sec['excel_filename'], ENT_QUOTES, 'UTF-8'); ?>">
					<i class="fas fa-file-excel mr-1"></i> Cetak ke Excel
				</button>
			</div>
			<div class="table-responsive">
				<table id="<?php echo htmlspecialchars($sec['table_id'], ENT_QUOTES, 'UTF-8'); ?>"
					class="table table-sm table-bordered table-striped display nowrap pembelian-persediaan-dt-table"
					style="width:100%;">
					<thead>
						<tr>
							<th>No</th>
							<?php if (!empty($sec['show_referensi'])) : ?><th>Referensi</th><?php endif; ?>
							<th>Tgl PO</th>
							<th>SPOP</th>
							<th>Uraian</th>
							<th>Satuan</th>
							<th style="text-align:right">Jumlah</th>
							<th style="text-align:right">Harga Satuan</th>
							<th style="text-align:right">Harga Total</th>
							<th>Supplier</th>
							<th>Status</th>
							<th>Keterangan</th>
							<th>UUID Persediaan</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if (!function_exists('persediaan_parse_angka')) {
							$CI_pem = function_exists('get_instance') ? get_instance() : null;
							if ($CI_pem) {
								$CI_pem->load->helper('persediaan_display');
							}
						}
						$no = 0;
						$sec_total_jumlah = 0.0;
						$sec_total_harga = 0.0;
						foreach ($sec['data'] as $row) :
							$no++;
							$id_pem = isset($row->id_pembelian) ? (int) $row->id_pembelian : (isset($row->id) ? (int) $row->id : 0);
							$tgl = isset($row->tgl_po_display) ? $row->tgl_po_display : (isset($row->tgl_po) ? date('Y-m-d', strtotime($row->tgl_po)) : '');
							$spop = isset($row->spop_display) ? $row->spop_display : (isset($row->spop) ? (string) $row->spop : '');
							$uraian = isset($row->uraian_display) ? $row->uraian_display : (isset($row->uraian) ? (string) $row->uraian : '');
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
							$harga_satuan_tampil = isset($row->harga_satuan_display)
								? $row->harga_satuan_display
								: number_format($harga_satuan_num, 2, ',', '.');
							$harga_total_tampil = isset($row->harga_total_display)
								? $row->harga_total_display
								: number_format($harga_total_num, 2, ',', '.');
							$supplier = isset($row->supplier_nama_display) ? $row->supplier_nama_display : (isset($row->supplier_nama) ? (string) $row->supplier_nama : '');
							$uuid = isset($row->uuid_persediaan) ? (string) $row->uuid_persediaan : '';
							$ket = isset($row->keterangan) ? (string) $row->keterangan : '';
							$status = isset($row->status) ? (string) $row->status : '';
						?>
							<tr data-pembelian-id="<?php echo (int) $id_pem; ?>">
								<td><?php echo (int) (isset($row->no) ? $row->no : $no); ?></td>
								<?php if (!empty($sec['show_referensi'])) : ?>
									<td>
										<?php if ($id_pem > 0) : ?>
											<button type="button" class="btn btn-xs btn-info btn-pem-referensi-persediaan" data-id-pembelian="<?php echo (int) $id_pem; ?>"
												data-uraian="<?php echo htmlspecialchars($uraian, ENT_QUOTES, 'UTF-8'); ?>"
												data-satuan="<?php echo htmlspecialchars($satuan, ENT_QUOTES, 'UTF-8'); ?>"
												data-jumlah="<?php echo htmlspecialchars((string) $jumlah, ENT_QUOTES, 'UTF-8'); ?>"
												data-spop="<?php echo htmlspecialchars($spop, ENT_QUOTES, 'UTF-8'); ?>"
												data-supplier="<?php echo htmlspecialchars($supplier, ENT_QUOTES, 'UTF-8'); ?>">
												Referensi
											</button>
										<?php endif; ?>
									</td>
								<?php endif; ?>
								<td><?php echo htmlspecialchars((string) $tgl, ENT_QUOTES, 'UTF-8'); ?></td>
								<td><?php echo htmlspecialchars($spop, ENT_QUOTES, 'UTF-8'); ?></td>
								<td><?php echo htmlspecialchars($uraian, ENT_QUOTES, 'UTF-8'); ?></td>
								<td><?php echo htmlspecialchars($satuan, ENT_QUOTES, 'UTF-8'); ?></td>
								<td align="right" class="pem-persediaan-col-jumlah" data-num="<?php echo htmlspecialchars((string) $jumlah_num, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars((string) $jumlah, ENT_QUOTES, 'UTF-8'); ?></td>
								<td align="right"><?php echo htmlspecialchars($harga_satuan_tampil, ENT_QUOTES, 'UTF-8'); ?></td>
								<td align="right" class="pem-persediaan-col-harga-total" data-num="<?php echo htmlspecialchars((string) $harga_total_num, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($harga_total_tampil, ENT_QUOTES, 'UTF-8'); ?></td>
								<td><?php echo htmlspecialchars($supplier, ENT_QUOTES, 'UTF-8'); ?></td>
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
						<tr class="pem-persediaan-total-row">
							<th>TOTAL</th>
							<?php if (!empty($sec['show_referensi'])) : ?><th></th><?php endif; ?>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th style="text-align:right" class="pem-persediaan-foot-jumlah"><?php echo htmlspecialchars($sec_total_jumlah_tampil, ENT_QUOTES, 'UTF-8'); ?></th>
							<th></th>
							<th style="text-align:right" class="pem-persediaan-foot-harga-total"><?php echo htmlspecialchars($sec_total_harga_tampil, ENT_QUOTES, 'UTF-8'); ?></th>
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
