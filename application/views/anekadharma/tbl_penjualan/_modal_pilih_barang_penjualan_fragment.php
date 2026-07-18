<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$this->load->helper('pembelian_persediaan');

if (!isset($Data_stock)) {
	$Data_stock = array();
}
if (!isset($tgl_jual_X)) {
	$tgl_jual_X = isset($tgl_jual) ? penjualan_format_tgl_jual_tampil($tgl_jual) : date('d-m-Y');
}
if (!isset($uuid_penjualan)) {
	$uuid_penjualan = '';
}
if (!isset($action)) {
	$action = site_url('tbl_penjualan/create_action_simpan_barang/');
}
if (!isset($fragment_part)) {
	$fragment_part = 'tbody';
}
if (!isset($penjualan_kolom_unit)) {
	$penjualan_kolom_unit = penjualan_resolve_kolom_persediaan_unit($this, isset($uuid_unit) ? $uuid_unit : '');
}
if (!isset($penjualan_label_unit)) {
	$penjualan_label_unit = $penjualan_kolom_unit
		? penjualan_get_label_kolom_unit($penjualan_kolom_unit)
		: '';
}

$start = 0;
$ada_baris = false;

foreach ($Data_stock as $list_data) {
	if (empty($list_data->id) || trim((string) $list_data->nama_barang_beli) === '') {
		continue;
	}
	if (isset($list_data->kategori_barang) && penjualan_is_kategori_jasa($list_data->kategori_barang)) {
		continue;
	}

	$sisa_stock_data = penjualan_get_sisa_stock_penjualan($list_data, $penjualan_kolom_unit);
	$nilai_unit = $penjualan_kolom_unit
		? (int) floor(penjualan_get_nilai_kolom_unit($list_data, $penjualan_kolom_unit))
		: 0;
	$bisa_pilih = ($sisa_stock_data > 0);

	$ada_baris = true;
	$no = ++$start;
	$harga_fmt = number_format((float) $list_data->harga_satuan_persediaan, 2, ',', '.');
	$tgl_po_fmt = '';
	if (!empty($list_data->tanggal_beli) && $list_data->tanggal_beli !== '0000-00-00') {
		$tgl_po_fmt = date('d M Y', strtotime($list_data->tanggal_beli));
	} elseif (!empty($list_data->tanggal)) {
		$ts_tgl = pembelian_parse_tanggal_po($list_data->tanggal);
		if ($ts_tgl !== false) {
			$tgl_po_fmt = date('d M Y', $ts_tgl);
		}
	}
	$form_action = !empty($uuid_penjualan)
		? $action . $uuid_penjualan . '/' . $list_data->id
		: $action . 'new/' . $list_data->id;
	$btn_class = $bisa_pilih ? 'btn-success' : 'btn-secondary';
	$btn_disabled = $bisa_pilih ? '' : ' disabled';
	$label_info_jumlah = 'Jumlah Maks= ' . (int) $sisa_stock_data;
	if ($penjualan_label_unit !== '') {
		$label_info_jumlah .= ' | Unit ' . $penjualan_label_unit . ': ' . (int) $nilai_unit;
	}

	if ($fragment_part === 'tbody') {
		?>
		<tr>
			<td align="right"><?php echo $no; ?></td>
			<td align="right">
				<button type="button" class="btn <?php echo $btn_class; ?> btn-xs" data-toggle="modal" data-target="#modal-xl_1_<?php echo (int) $list_data->id; ?>"<?php echo $btn_disabled; ?>>PILIH BARANG</button>
			</td>
			<td><?php echo htmlspecialchars($tgl_po_fmt, ENT_QUOTES, 'UTF-8'); ?></td>
			<td align="left"><?php echo htmlspecialchars($list_data->spop, ENT_QUOTES, 'UTF-8'); ?></td>
			<td align="left"><?php echo htmlspecialchars(isset($list_data->kategori_barang) ? $list_data->kategori_barang : '', ENT_QUOTES, 'UTF-8'); ?></td>
			<td align="left"><?php echo htmlspecialchars($list_data->nama_barang_beli, ENT_QUOTES, 'UTF-8'); ?></td>
			<td align="right"><?php echo $harga_fmt; ?></td>
			<td align="left"><?php echo htmlspecialchars($list_data->satuan_persediaan, ENT_QUOTES, 'UTF-8'); ?></td>
			<td align="right"><?php echo nominal($sisa_stock_data); ?><?php if ($penjualan_label_unit !== '') { ?><br><small class="text-muted"><?php echo htmlspecialchars($penjualan_label_unit, ENT_QUOTES, 'UTF-8'); ?>: <?php echo nominal($nilai_unit); ?></small><?php } ?></td>
			<td align="left">
				<button type="button" class="btn <?php echo $btn_class; ?> btn-xs" data-toggle="modal" data-target="#modal-xl_1_<?php echo (int) $list_data->id; ?>"<?php echo $btn_disabled; ?>>PILIH BARANG</button>
			</td>
		</tr>
		<?php
	} elseif ($bisa_pilih) {
		?>
		<div class="modal fade" id="modal-xl_1_<?php echo (int) $list_data->id; ?>" tabindex="-1">
			<div class="modal-dialog modal-lg modal-isi-jumlah-barang">
				<div class="modal-content">
					<form class="form-simpan-jumlah-barang-penjualan" action="<?php echo htmlspecialchars($form_action, ENT_QUOTES, 'UTF-8'); ?>" method="post">
						<div class="modal-header">
							<h4 class="modal-title">Isi Jumlah Barang</h4>
							<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<label>Barang</label>
								<input type="text" class="form-control" value="<?php echo htmlspecialchars($list_data->nama_barang_beli, ENT_QUOTES, 'UTF-8'); ?>" disabled>
							</div>
							<div class="row">
								<div class="col-md-5 col-12">
									<div class="form-group mb-md-0">
										<label>Harga Satuan</label>
										<input type="text" class="form-control" name="harga_satuan_beli" value="<?php echo $harga_fmt; ?>">
									</div>
								</div>
								<div class="col-md-7 col-12">
									<div class="form-group mb-0">
										<label class="penjualan-label-info-jumlah d-block" for="jumlah_barang_<?php echo (int) $list_data->id; ?>" title="<?php echo htmlspecialchars($label_info_jumlah, ENT_QUOTES, 'UTF-8'); ?>">
											<?php echo htmlspecialchars($label_info_jumlah, ENT_QUOTES, 'UTF-8'); ?>
										</label>
										<input type="number" class="form-control" id="jumlah_barang_<?php echo (int) $list_data->id; ?>" name="jumlah" min="1" max="<?php echo (int) $sisa_stock_data; ?>" placeholder="Isi jumlah barang" required>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer justify-content-between">
							<input type="hidden" name="ajax" value="1">
							<input type="hidden" name="tgl_jual" value="<?php echo htmlspecialchars($tgl_jual_X, ENT_QUOTES, 'UTF-8'); ?>">
							<input type="hidden" name="uuid_unit" value="<?php echo htmlspecialchars(isset($uuid_unit) ? $uuid_unit : '', ENT_QUOTES, 'UTF-8'); ?>">
							<input type="hidden" name="uuid_konsumen" value="<?php echo htmlspecialchars(isset($uuid_konsumen) ? $uuid_konsumen : '', ENT_QUOTES, 'UTF-8'); ?>">
							<input type="hidden" name="uuid_persediaan" value="<?php echo htmlspecialchars(isset($list_data->uuid_persediaan) ? $list_data->uuid_persediaan : '', ENT_QUOTES, 'UTF-8'); ?>">
							<input type="hidden" name="id_persediaan_barang" value="<?php echo (int) $list_data->id; ?>">
							<input type="hidden" name="uuid_penjualan" value="<?php echo htmlspecialchars($uuid_penjualan !== '' ? $uuid_penjualan : 'new', ENT_QUOTES, 'UTF-8'); ?>">
							<input type="hidden" name="uuid_penjualan_proses" value="<?php echo htmlspecialchars($uuid_penjualan !== '' ? $uuid_penjualan : 'new', ENT_QUOTES, 'UTF-8'); ?>">
							<input type="hidden" name="nmrpesan" value="<?php echo htmlspecialchars(isset($nmrpesan) ? $nmrpesan : '', ENT_QUOTES, 'UTF-8'); ?>">
							<input type="hidden" name="nmrkirim" value="<?php echo htmlspecialchars(isset($nmrkirim) ? $nmrkirim : '', ENT_QUOTES, 'UTF-8'); ?>">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary btn-simpan-jumlah-barang">SIMPAN</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<?php
	}
}

if ($fragment_part === 'tbody' && !$ada_baris) {
	echo '<tr><td colspan="10" class="text-center text-muted">Tidak ada barang persediaan pada bulan ini (sesuai Tgl Jual).</td></tr>';
}
