<form action="<?php echo $action; ?>" method="post" id="form_cetak_pembayaran_jasa">
	<input type="hidden" name="open_print" id="open_print" value="0">
	<input type="hidden" name="total_penjualan" id="total_penjualan" value="<?php echo (int) $total_penjualan; ?>">
	<input type="hidden" name="jumlah_ppn" id="jumlah_ppn_hidden" value="<?php echo htmlspecialchars($jumlah_ppn, ENT_QUOTES, 'UTF-8'); ?>">
	<input type="hidden" name="dibayar" id="dibayar_hidden" value="<?php echo (int) $dibayar; ?>">
	<input type="hidden" name="terbilang" id="terbilang_hidden" value="<?php echo htmlspecialchars($terbilang_dibayar, ENT_QUOTES, 'UTF-8'); ?>">

	<div class="content-wrapper">
		<section class="content">
			<div class="card">
				<div class="card-header">
					<h3 class="card-title">Input Cetak Pembayaran Jasa</h3>
				</div>
				<div class="card-body">
					<?php if ($this->session->flashdata('message')) { ?>
						<div class="alert alert-success"><?php echo $this->session->flashdata('message'); ?></div>
					<?php } ?>
					<style>
						#customers {
							font-family: Arial, Helvetica, sans-serif;
							border-collapse: collapse;
							width: 100%;
						}

						#customers td,
						#customers th {
							border: 0px solid #ddd;
							padding: 3px;
						}

						#customers col.cetak-col-no { width: 3ch; }
						#customers col.cetak-col-deskripsi { width: 50%; }
						#customers col.cetak-col-unit { width: 16.666%; }
						#customers col.cetak-col-harga { width: 16.667%; }
						#customers col.cetak-col-jumlah { width: 16.667%; }

						#customers tr.cetak-barang > th {
							font-family: "Courier New", Courier, monospace;
							font-size: 0.9em;
							padding: 3px 4px;
							vertical-align: middle;
							background-color: #fff;
							border-color: #000;
							border-style: solid;
						}

						#customers tr.cetak-barang-header th {
							font-weight: bold;
							text-align: center;
						}

						.cetak-input-inline {
							display: inline-block;
							vertical-align: middle;
							border: 1px solid #999;
							background: #fffde7;
							box-sizing: border-box;
						}

						.cetak-input-ppn {
							width: 100px;
							min-width: 100px;
							height: 34px;
							padding: 4px 10px;
							font-size: 1.15em;
							font-weight: bold;
							text-align: right;
						}

						.cetak-input-fee {
							width: 170px;
							min-width: 170px;
							height: 36px;
							padding: 4px 12px;
							font-size: 1.2em;
							font-weight: bold;
							text-align: right;
							letter-spacing: 0.02em;
						}

						.cetak-label-persen {
							font-size: 1.1em;
							margin-left: 2px;
						}

						.cetak-readonly-nominal {
							font-weight: bold;
						}

						.cetak-btn-group-tengah {
							display: inline-flex;
							flex-wrap: wrap;
							align-items: center;
							justify-content: center;
							gap: 8px;
						}

						.cetak-btn-group-tengah .btn {
							min-width: 140px;
						}
					</style>

					<table id="customers">
						<colgroup>
							<col class="cetak-col-no">
							<col class="cetak-col-deskripsi">
							<col class="cetak-col-unit">
							<col class="cetak-col-harga">
							<col class="cetak-col-jumlah">
						</colgroup>

						<tr>
							<th colspan="5" style="font-size: 0.75em; text-align: left; border: none; padding: 3px 0;">
								<strong>Kepada : </strong><?php echo htmlspecialchars(trim((string) $konsumen_nama_selected), ENT_QUOTES, 'UTF-8'); ?>
							</th>
						</tr>

						<tr>
							<th colspan="3" style="font-size: 0.75em; text-align: left; border: none; padding: 3px 0;">
								<strong>Alamat : </strong><?php echo htmlspecialchars(trim((string) $konsumen_alamat_selected), ENT_QUOTES, 'UTF-8'); ?>
							</th>
							<th colspan="2" style="font-size: 0.75em; text-align: left; border: none; padding: 3px 0; white-space: nowrap;">
								<strong>Nomor Pesan : </strong><?php echo htmlspecialchars(trim((string) $nmr_pesan_selected), ENT_QUOTES, 'UTF-8'); ?>
							</th>
						</tr>

						<tr>
							<th colspan="5" style="font-size: 0.95em; text-align: center; border: none; padding: 4px 0 5px 0;">
								<strong>NOTA PEMBAYARAN</strong>
							</th>
						</tr>

						<tr class="cetak-barang cetak-barang-header">
							<th style="border: 1px solid black; border-right: none;">No.</th>
							<th style="border: 1px solid black; border-right: none; text-align: left;">Deskripsi</th>
							<th style="border: 1px solid black; border-right: none;">Unit (Org)</th>
							<th style="border: 1px solid black; border-right: none;">Harga Satuan</th>
							<th style="border: 1px solid black;">Jumlah</th>
						</tr>

						<?php
						$start = 0;
						$min_data_rows = 5;
						foreach ($data_penjualan as $list_data) {
							$x_total = $list_data->jumlah * $list_data->harga_satuan;
						?>
							<tr class="cetak-barang">
								<th style="text-align:center; border: 1px solid black; border-right:none;"><strong><?php echo ++$start; ?></strong></th>
								<th style="text-align:left; border: 1px solid black; border-right:none;"><strong><?php echo $list_data->nama_barang; ?></strong></th>
								<th style="text-align:center; border: 1px solid black; border-right:none;"><strong><?php echo nominal($list_data->jumlah) . ' ' . $list_data->satuan; ?></strong></th>
								<th style="text-align:right; border: 1px solid black; border-right:none;"><strong><?php echo nominal($list_data->harga_satuan); ?></strong></th>
								<th style="text-align:right; border: 1px solid black;"><strong><?php echo nominal($x_total); ?></strong></th>
							</tr>
						<?php
						}
						for ($pad_row = $start; $pad_row < $min_data_rows; $pad_row++) {
						?>
							<tr class="cetak-barang">
								<th style="border: 1px solid black; border-right:none;">&nbsp;</th>
								<th style="border: 1px solid black; border-right:none;">&nbsp;</th>
								<th style="border: 1px solid black; border-right:none;">&nbsp;</th>
								<th style="border: 1px solid black; border-right:none;">&nbsp;</th>
								<th style="border: 1px solid black;">&nbsp;</th>
							</tr>
						<?php } ?>

						<tr class="cetak-barang">
							<th style="border: 1px solid black; border-right:none;">&nbsp;</th>
							<th style="border: 1px solid black; border-right:none;">&nbsp;</th>
							<th style="border: 1px solid black; border-right:none;">&nbsp;</th>
							<th style="border: 1px solid black; border-right:none;">&nbsp;</th>
							<th style="border: 1px solid black;">&nbsp;</th>
						</tr>

						<tr class="cetak-barang">
							<th style="border: 1px solid black; border-right:none;">&nbsp;</th>
							<th style="border: 1px solid black; border-right:none;">&nbsp;</th>
							<th colspan="2" style="text-align:center; border: 1px solid black; border-right:none;"><strong>Total</strong></th>
							<th style="text-align:right; border: 1px solid black;"><strong id="display_total" class="cetak-readonly-nominal"><?php echo nominal($total_penjualan); ?></strong></th>
						</tr>

						<tr class="cetak-barang">
							<th style="border: 1px solid black; border-right:none;">&nbsp;</th>
							<th style="border: 1px solid black; border-right:none;">&nbsp;</th>
							<th colspan="2" style="text-align:center; border: 1px solid black; border-right:none;">
								<strong>PPN</strong>
								<input type="text" name="prosentase_ppn" id="prosentase_ppn" class="cetak-input-inline cetak-input-ppn" value="<?php echo htmlspecialchars($prosentase_ppn, ENT_QUOTES, 'UTF-8'); ?>" placeholder="2" inputmode="decimal" autocomplete="off">
								<strong class="cetak-label-persen">%</strong>
							</th>
							<th style="text-align:right; border: 1px solid black;">
								<strong id="display_jumlah_ppn" class="cetak-readonly-nominal"><?php echo nominal($jumlah_ppn); ?></strong>
							</th>
						</tr>

						<tr class="cetak-barang">
							<th style="border: 1px solid black; border-right:none;">&nbsp;</th>
							<th style="border: 1px solid black; border-right:none;">&nbsp;</th>
							<th colspan="2" style="text-align:center; border: 1px solid black; border-right:none;">
								<strong>Fee Admin</strong>
								<input type="text" name="fee_admin" id="fee_admin" class="cetak-input-inline cetak-input-fee" value="<?php echo htmlspecialchars($fee_admin_display, ENT_QUOTES, 'UTF-8'); ?>" placeholder="0" inputmode="numeric" autocomplete="off">
							</th>
							<th style="text-align:right; border: 1px solid black;">
								<strong id="display_fee_admin" class="cetak-readonly-nominal"><?php echo nominal($fee_admin); ?></strong>
							</th>
						</tr>

						<tr class="cetak-barang">
							<th style="border: 1px solid black; border-right:none;">&nbsp;</th>
							<th style="border: 1px solid black; border-right:none;"><strong><em>Terbilang :</em></strong></th>
							<th colspan="2" style="text-align:center; border: 1px solid black; border-right:none;"><strong>Dibayar</strong></th>
							<th style="text-align:right; border: 1px solid black;">
								<strong id="display_dibayar" class="cetak-readonly-nominal"><?php echo nominal($dibayar); ?></strong>
							</th>
						</tr>

						<tr class="cetak-barang">
							<th style="border: 1px solid black; border-right:none; border-top:none;">&nbsp;</th>
							<th style="text-align:left; border: 1px solid black; border-right:none; border-top:none; text-transform: capitalize;">
								<em id="display_terbilang"><?php echo htmlspecialchars($terbilang_dibayar, ENT_QUOTES, 'UTF-8'); ?></em>
							</th>
							<th colspan="3" style="text-align:left; border: 1px solid black; border-top:none;">
								<?php echo htmlspecialchars(trim((string) $tgl_bayar_selected), ENT_QUOTES, 'UTF-8'); ?>
							</th>
						</tr>

						<tr class="cetak-barang">
							<th style="border: 1px solid black; border-right:none; border-top:none; border-bottom:none;">&nbsp;</th>
							<th style="text-align:left; border: 1px solid black; border-right:none; border-top:none; border-bottom:none;"><strong>Catatan:</strong></th>
							<th colspan="3" style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</th>
						</tr>

						<tr class="cetak-barang">
							<th style="border: 1px solid black; border-right:none; border-top:none; border-bottom:none;">&nbsp;</th>
							<th style="text-align:left; vertical-align: top; border: 1px solid black; border-right:none; border-top:none; border-bottom:none; line-height: 1.35;">
								Tranfer Bank ke Rek. BPD DIY<br>
								004.111.000511<br>
								a.n Perumda Aneka Dharma
							</th>
							<th colspan="3" style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</th>
						</tr>

						<tr class="cetak-barang">
							<th style="border: 1px solid black; border-right:none;">&nbsp;</th>
							<th style="border: 1px solid black; border-right:none;">&nbsp;</th>
							<th colspan="3" style="text-align:center; border: 1px solid black;"><strong>( Miko )</strong></th>
						</tr>
					</table>

					<?php
					$btn_simpan_class = !empty($is_saved) ? 'btn-warning' : 'btn-primary';
					$btn_simpan_text = !empty($is_saved) ? 'Tersimpan' : 'Simpan';
					$btn_simpan_cetak_class = !empty($is_siap_cetak) ? 'btn-warning' : 'btn-success';
					$btn_simpan_cetak_text = !empty($is_siap_cetak) ? 'Tersimpan dan siap cetak' : 'Simpan &amp; Cetak';
					$pdf_url_btn = !empty($pdf_url) ? $pdf_url : '';
					?>

					<div class="row mt-4 align-items-center">
						<div class="col-md-3 col-sm-12 mb-2">
							<a href="<?php echo site_url('tbl_penjualan_jasa'); ?>" class="btn btn-default">Kembali</a>
						</div>
						<div class="col-md-9 col-sm-12 text-center mb-2">
							<div class="cetak-btn-group-tengah">
								<button type="submit" class="btn <?php echo $btn_simpan_class; ?>" id="btn_simpan"><?php echo $btn_simpan_text; ?></button>
								<button type="button" class="btn <?php echo $btn_simpan_cetak_class; ?>" id="btn_simpan_cetak"><?php echo $btn_simpan_cetak_text; ?></button>
								<a href="<?php echo htmlspecialchars($pdf_url_btn, ENT_QUOTES, 'UTF-8'); ?>"
									class="btn btn-info<?php echo empty($pdf_url_btn) ? ' disabled' : ''; ?>"
									id="btn_cetak_pdf"
									target="_blank"
									<?php echo empty($pdf_url_btn) ? 'aria-disabled="true" tabindex="-1"' : ''; ?>>Cetak PDF</a>
							</div>
						</div>
					</div>

				</div>
			</div>
		</section>
	</div>
</form>

<script>
(function () {
	function parseAngka(val) {
		if (val === null || val === undefined) return 0;
		var s = String(val).replace(/\./g, '').replace(/,/g, '.').replace(/[^\d.-]/g, '');
		var n = parseFloat(s);
		return isNaN(n) ? 0 : n;
	}

	function parsePersentase(val) {
		if (val === null || val === undefined) return 0;
		var s = String(val).trim().replace(',', '.');
		s = s.replace(/[^\d.]/g, '');
		var dot = s.indexOf('.');
		if (dot >= 0) {
			s = s.slice(0, dot + 1) + s.slice(dot + 1).replace(/\./g, '');
		}
		var n = parseFloat(s);
		return isNaN(n) ? 0 : n;
	}

	function formatNominal(angka) {
		var n = Math.round(angka);
		return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
	}

	function sanitizePersentaseInput(el) {
		var v = String(el.value).replace(/[^\d.,]/g, '');
		var out = '';
		var sepUsed = false;
		for (var i = 0; i < v.length; i++) {
			var ch = v.charAt(i);
			if (ch === '.' || ch === ',') {
				if (!sepUsed) {
					out += ch;
					sepUsed = true;
				}
			} else {
				out += ch;
			}
		}
		el.value = out;
	}

	function formatFeeAdminInput(el) {
		var digits = String(el.value).replace(/\D/g, '');
		if (digits === '') {
			el.value = '';
			return;
		}
		var num = parseInt(digits, 10);
		if (isNaN(num) || num < 0) {
			el.value = '';
			return;
		}
		el.value = formatNominal(num);
	}

	function penyebut(nilai) {
		nilai = Math.abs(Math.floor(nilai));
		var huruf = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];
		var temp = '';
		if (nilai < 12) {
			temp = ' ' + huruf[nilai];
		} else if (nilai < 20) {
			temp = penyebut(nilai - 10) + ' belas';
		} else if (nilai < 100) {
			temp = penyebut(Math.floor(nilai / 10)) + ' puluh' + penyebut(nilai % 10);
		} else if (nilai < 200) {
			temp = ' seratus' + penyebut(nilai - 100);
		} else if (nilai < 1000) {
			temp = penyebut(Math.floor(nilai / 100)) + ' ratus' + penyebut(nilai % 100);
		} else if (nilai < 2000) {
			temp = ' seribu' + penyebut(nilai - 1000);
		} else if (nilai < 1000000) {
			temp = penyebut(Math.floor(nilai / 1000)) + ' ribu' + penyebut(nilai % 1000);
		} else if (nilai < 1000000000) {
			temp = penyebut(Math.floor(nilai / 1000000)) + ' juta' + penyebut(nilai % 1000000);
		} else if (nilai < 1000000000000) {
			temp = penyebut(Math.floor(nilai / 1000000000)) + ' milyar' + penyebut(nilai % 1000000000);
		}
		return temp;
	}

	function terbilang(nilai) {
		nilai = Math.round(nilai);
		if (nilai < 0) {
			return 'minus ' + penyebut(nilai).trim();
		}
		return penyebut(nilai).trim();
	}

	function hitungUlang() {
		var total = parseAngka(document.getElementById('total_penjualan').value);
		var prosentase = parsePersentase(document.getElementById('prosentase_ppn').value);
		var feeAdmin = parseAngka(document.getElementById('fee_admin').value);

		var jumlahPpn = 0;
		if (prosentase > 0) {
			jumlahPpn = Math.round((total * prosentase) / 100);
		}

		var dibayar = total + jumlahPpn + feeAdmin;
		var terbilangText = '';
		if (dibayar > 0) {
			terbilangText = terbilang(dibayar).replace(/\b\w/g, function (c) { return c.toUpperCase(); }) + ' Rupiah';
		}

		document.getElementById('display_jumlah_ppn').textContent = formatNominal(jumlahPpn);
		document.getElementById('display_fee_admin').textContent = formatNominal(feeAdmin);
		document.getElementById('display_dibayar').textContent = formatNominal(dibayar);
		document.getElementById('display_terbilang').textContent = terbilangText;

		document.getElementById('jumlah_ppn_hidden').value = jumlahPpn;
		document.getElementById('dibayar_hidden').value = dibayar;
		document.getElementById('terbilang_hidden').value = terbilangText;
	}

	var prosentaseEl = document.getElementById('prosentase_ppn');
	var feeAdminEl = document.getElementById('fee_admin');
	if (prosentaseEl) {
		prosentaseEl.addEventListener('input', function () {
			sanitizePersentaseInput(this);
			hitungUlang();
		});
	}
	if (feeAdminEl) {
		feeAdminEl.addEventListener('input', function () {
			formatFeeAdminInput(this);
			hitungUlang();
		});
		feeAdminEl.addEventListener('blur', function () {
			formatFeeAdminInput(this);
			hitungUlang();
		});
	}

	document.getElementById('btn_simpan_cetak').addEventListener('click', function () {
		document.getElementById('open_print').value = '1';
		document.getElementById('form_cetak_pembayaran_jasa').submit();
	});

	document.getElementById('btn_simpan').addEventListener('click', function () {
		document.getElementById('open_print').value = '0';
	});

	function setTombolTersimpan(mode) {
		var btnSimpan = document.getElementById('btn_simpan');
		var btnSimpanCetak = document.getElementById('btn_simpan_cetak');
		var btnCetakPdf = document.getElementById('btn_cetak_pdf');

		btnSimpan.classList.remove('btn-primary', 'btn-success');
		btnSimpan.classList.add('btn-warning');
		btnSimpan.textContent = 'Tersimpan';

		if (mode === 'simpan_cetak') {
			btnSimpanCetak.classList.remove('btn-success', 'btn-primary');
			btnSimpanCetak.classList.add('btn-warning');
			btnSimpanCetak.textContent = 'Tersimpan dan siap cetak';
		}
	}

	<?php if (!empty($is_saved)) { ?>
	setTombolTersimpan(<?php echo json_encode(!empty($is_siap_cetak) ? 'simpan_cetak' : 'simpan'); ?>);
	<?php } ?>

	<?php if (!empty($auto_open_pdf) && !empty($pdf_url)) { ?>
	window.open(<?php echo json_encode($pdf_url); ?>, '_blank');
	<?php } ?>

	hitungUlang();
})();
</script>
