<head>
	<style>
		/* Margin halaman: atas, kanan, kiri 0.5cm (layar + cetak). Bawah layar 1em; cetak bawah 1cm. */
		body {
			margin: 0.5cm 0.5cm 1em 0.5cm;
		}

		@page {
			margin-top: 0.5cm;
			margin-right: 0.5cm;
			margin-left: 0.5cm;
			margin-bottom: 1cm;
		}

		@media print {
			body {
				margin: 0;
			}
		}

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

		#customers tr:nth-child(even) {
			background-color: #f2f2f2;
		}

		#customers tr:hover {
			background-color: #ddd;
		}

		#customers th {
			padding-top: 1px;
			padding-bottom: 1px;
			/* text-align: left; */
			background-color: white;
			color: black;
		}

		#cetak-konsumen-nama {
			display: inline-block;
			max-width: none;
			white-space: nowrap;
			vertical-align: bottom;
			line-height: 1.2;
		}

		th#cetak-th-konsumen-nama {
			overflow: visible;
		}
	</style>
</head>


<?php
$TOTAL_AKIVA_LANCAR = 0;
$Total_Aktiva_Tetap_Bersih = 0;
$Aktiva_Lain_Lain = 0;
$TOTAL_Utang_Lancar = 0;
$TOTAL_Utang_Jangka_Panjang = 0;
$TOTAL_Modal_dan_Laba_ditahan = 0;

?>

<body>



	<table id="customers">




		<!-- BARIS KE 1 -->
		<tr>
			<th style="font-size: 0.75em;text-align:left; width: 100px;border: 1px solid black;  border-bottom:none; border-top:none;  border-left:none;border-right:none;  border-collapse: collapse;" colspan="100"></th>

			<th style="border: 1px solid black;  border-top:none;border-bottom:none;border-right:none;border-left:none;  border-collapse: collapse; font-size: 1.250em;font-family: Times New Roman; text-align:left; width: 900px;" colspan="900">
				<!-- PERUMDA -->

			</th>
		</tr>

		<tr>
			<th style="font-size: 0.75em;text-align:left; width: 100px; border: 1px solid black;  border-bottom:none; border-top:none;  border-left:none;border-right:none;  border-collapse: collapse;" colspan="100"></th>

			<th style="border: 1px solid black;  border-top:none;border-bottom:none;border-right:none;border-left:none;  border-collapse: collapse; font-size: 2.250em; font-family: Arial; text-align:left; width: 900px;" colspan="900">
				<!-- <strong>ANEKA DHARMA</strong> -->

			</th>
		</tr>









		<tr>

		

			<th style="font-size: 0.75em;text-align:center; width: 675px;border: 1px solid black;  border-bottom:none; border-top:none;  border-left:none;border-right:none; border-collapse: collapse;" colspan="575">
				<!-- <strong>TERUS BERGERAK MAJU BERSAMA </strong> -->
			</th>

			<!-- <th style="font-size: 0.75em;text-align:left; width: 375px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="375"><strong>NAMA BARANG</strong></th> -->


			<!-- <th style="font-size: 0.75em;text-align:left; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong>JUMLAH</strong></th> -->

			<th style="font-size: 0.75em;text-align:left; width: 125px;border: 1px solid black; border-bottom:none; border-top:none;  border-left:none;border-right:none;   border-collapse: collapse;" colspan="150"><strong>NO. PSN </strong></th>

			<!-- <th style="font-size: 0.75em;text-align:left; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong></strong></th> -->

			<th style="font-size: 0.75em;text-align:left; width: 200px;border: 1px solid black; border-bottom:none; border-top:none;  border-left:none;border-right:none;   border-collapse: collapse;" colspan="375"><strong><?php echo " : " .  $nmr_pesan_selected; ?></strong></th>


		</tr>


		<tr>

			<!-- <th style="font-size: 0.75em;text-align:left; width: 100px;border: 1px solid black;  border-bottom:none; border-top:none;  border-left:none;border-right:none;  border-collapse: collapse;" colspan="100"><strong></strong></th> -->

			<th style="font-size: 0.75em;text-align:left; border: 1px solid black;  border-bottom:none; border-top:none;  border-left:none;border-right:none; border-collapse: collapse; width: 775px;" colspan="575"><i>
					<!-- <strong>Jl. Jendral Sudirman No. 36 Bantul Yogyakarta </strong> -->
				</i></th>

			<!-- <th style="font-size: 0.75em;text-align:left; width: 375px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="375"><strong>NAMA BARANG</strong></th> -->


			<!-- <th style="font-size: 0.75em;text-align:left; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong>JUMLAH</strong></th> -->

			<th style="font-size: 0.75em;text-align:left; width: 125px;border: 1px solid black; border-bottom:none; border-top:none;  border-left:none;border-right:none;   border-collapse: collapse;" colspan="150"><strong>TANGGAL </strong></th>

			<!-- <th style="font-size: 0.75em;text-align:left; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong></strong></th> -->

			<th style="font-size: 0.75em;text-align:left; width: 200px;border: 1px solid black; border-bottom:none; border-top:none;  border-left:none;border-right:none;   border-collapse: collapse;" colspan="375"><strong><?php echo " : " .  $tgl_jual_selected; ?></strong></th>


		</tr>



		<tr>

			<!-- <th style="font-size: 0.75em;text-align:left; width: 100px;border: 1px solid black;  border-bottom:none; border-top:none;  border-left:none;border-right:none;  border-collapse: collapse;" colspan="100"><strong></strong></th> -->

			<th style="font-size: 0.75em;text-align:left; border: 1px solid black;  border-bottom:none; border-top:none;  border-left:none;border-right:none; border-collapse: collapse; width: 500;" colspan="575"> <i>
					<!-- <strong>0274 367123  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 08112657123 </strong> -->
				</i></th>

			<!-- <th style="font-size: 0.75em;text-align:left; width: 375px;border: 1px solid black; border-bottom:none; border-top:none;  border-left:none;border-right:none;   border-right:none;  border-collapse: collapse;" colspan="375"><i><strong>08112657123</strong></i></th> -->


			<!-- <th style="font-size: 0.75em;text-align:left; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong>JUMLAH</strong></th> -->

			<th style="font-size: 0.75em;text-align:left; width: 200px;border: 1px solid black; border-bottom:none; border-top:none;  border-left:none;border-right:none;   border-collapse: collapse;" colspan="150"><strong>KEPADA YTH. </strong></th>

			<!-- <th style="font-size: 0.75em;text-align:left; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong></strong></th> -->

			<th id="cetak-th-konsumen-nama" style="font-size: 0.75em;text-align:left; width: 400px;border: 1px solid black; border-bottom:none; border-top:none;  border-left:none;border-right:none;   border-collapse: collapse;" colspan="375"><strong><span id="cetak-konsumen-nama"><?php echo " : " . $konsumen_nama_selected; ?></span></strong></th>


		</tr>

		<tr>

			<th style="font-size: 0.75em;text-align:left; border: 1px solid black;  border-bottom:none; border-top:none;  border-left:none;border-right:none; border-collapse: collapse; width: 775;" colspan="575"> <i>
					<!-- <strong>0274 367123  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 08112657123 </strong> -->
				</i></th>

			<th colspan="525" style="font-size: 0.75em; text-align: left; border: 0.1px solid black; padding: 6px 8px; vertical-align: top;">
				<strong>PERUMDAM TIRTA<br>
				PROJOTAMANSARI</strong>
			</th>
		</tr>

		<tr>
			<th colspan="1100" style="font-size: 0.95em; text-align: center; border: none; padding: 8px 0 10px 0; vertical-align: middle;">
				<strong>FAKTUR PENJUALAN</strong>
			</th>
		</tr>

		<tr>

			<th style="font-size: 0.75em;text-align:center; width: 28px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="40"><strong>NO</strong></th>

			<th style="font-size: 0.75em;text-align:center; width: 100px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="100"><strong>KODE</strong></th>

			<th style="font-size: 0.75em;text-align:center; width: 310px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="310"><strong>NAMA BARANG</strong></th>


			<th style="font-size: 0.75em;text-align:center; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong>JUMLAH</strong></th>

			<th style="font-size: 0.75em;text-align:center; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong>SAT.</strong></th>

			<th style="font-size: 0.75em;text-align:center; width: 225px;border: 1px solid black;    border-collapse: collapse;" colspan="225"><strong>HARGA</strong></th>

			<th style="font-size: 0.75em;text-align:center; width: 225px;border: 1px solid black;    border-collapse: collapse;" colspan="225"><strong>SUB TOTAL</strong></th>


		</tr>



		<!-- LOOPING DATA -->
		<?php
		$start = 0;
		$x_total = 0;
		$TOTAL_PENJUALAN = 0;

		foreach ($data_penjualan as $list_data) {


		?>
			<tr>

				<th style="font-size: 0.75em;text-align:left; width: 28px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="40"><strong><?php echo ++$start ?></strong></th>

				<th style="font-size: 0.75em;text-align:left; width: 100px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="100"><strong>
						<?php
						if ($list_data->kode_barang) {
							echo $list_data->kode_barang;
						} else {
							// echo $list_data->uuid_barang;
							// $gh = $this->Sys_nama_barang_model->get_by_uuid_barang($list_data->uuid_barang);
							// echo $gh->kode_barang;


							$uuidbarang_selected = $list_data->uuid_barang;

							$this->db->where('uuid_barang', $uuidbarang_selected);
							$data_barang = $this->db->get('sys_nama_barang');

							if ($data_barang->num_rows() > 0) {

								$data_barang = $data_barang->row_array();
								echo $data_barang['kode_barang'];
							}
						}

						?>
					</strong></th>

				<th style="font-size: 0.75em;text-align:left; width: 310px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="310"><strong><?php echo $list_data->nama_barang; ?></strong></th>


				<th style="font-size: 0.75em;text-align:right; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong><?php echo nominal($list_data->jumlah); ?></strong></th>

				<th style="font-size: 0.75em;text-align:center; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong><?php echo $list_data->satuan; ?></strong></th>

				<th style="font-size: 0.75em;text-align:right; width: 225px;border: 1px solid black;    border-collapse: collapse;" colspan="225"><strong><?php echo nominal($list_data->harga_satuan); ?></strong></th>

				<th style="font-size: 0.75em;text-align:right; width: 225px;border: 1px solid black;    border-collapse: collapse;" colspan="225"><strong>
						<?php
						$x_total = $list_data->jumlah * $list_data->harga_satuan;
						echo nominal($x_total);

						$TOTAL_PENJUALAN = $TOTAL_PENJUALAN + $x_total;
						?>

					</strong></th>


			</tr>

		<?php
		}
		/* Minimal 10 baris isi tabel: jika record < 10, tambah baris kosong (border tetap) */
		$min_data_rows = 10;
		for ($pad_row = $start; $pad_row < $min_data_rows; $pad_row++) {
		?>
			<tr>
				<th style="font-size: 0.75em;text-align:left; width: 28px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="40">&nbsp;</th>
				<th style="font-size: 0.75em;text-align:left; width: 100px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="100">&nbsp;</th>
				<th style="font-size: 0.75em;text-align:left; width: 310px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="310">&nbsp;</th>
				<th style="font-size: 0.75em;text-align:right; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100">&nbsp;</th>
				<th style="font-size: 0.75em;text-align:center; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100">&nbsp;</th>
				<th style="font-size: 0.75em;text-align:right; width: 225px;border: 1px solid black;    border-collapse: collapse;" colspan="225">&nbsp;</th>
				<th style="font-size: 0.75em;text-align:right; width: 225px;border: 1px solid black;    border-collapse: collapse;" colspan="225">&nbsp;</th>
			</tr>
		<?php
		}
		?>
		<!-- END OF LOOPING DATA -->




		<!-- BARIS TOTAL -->

		<tr>

			<th style="font-size: 0.5em;text-align:left; text-transform: capitalize; width: 650px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="650">Terbilang: <strong><i> <?php echo terbilang($TOTAL_PENJUALAN); ?> </i></strong></th>

			<!-- <th style="font-size: 0.75em;text-align:left; width: 100px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="100"><strong>KODE</strong></th>

			<th style="font-size: 0.75em;text-align:left; width: 375px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="375"><strong>NAMA BARANG</strong></th>


			<th style="font-size: 0.75em;text-align:left; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong>JUMLAH</strong></th>
			
			<th style="font-size: 0.75em;text-align:left; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong>SAT.</strong></th>-->

			<th style="font-size: 0.5em;text-align:left; width: 225px;border: 1px solid black;    border-collapse: collapse;" colspan="225"><strong>JUMLAH</strong></th>

			<th style="font-size: 0.5em;font-style: italic;text-align:right; width: 225px;border: 1px solid black;    border-collapse: collapse;" colspan="225"><strong><?php echo nominal($TOTAL_PENJUALAN); ?></strong></th>


		</tr>



		<!-- TANDA TANGAN — jarak vertikal 0.5 cm antara baris total dan blok Penerima -->
		<tr>
			<th colspan="1100" style="height: 0.5cm; padding: 0; margin: 0; border: none; font-weight: normal; background: white; line-height: 0;">&nbsp;</th>
		</tr>
		<tr>
			<!-- Sel selaras header: NO(40)+KODE(100)+NAMA(310)+JML(100)+SAT(100) | HARGA+SUB(450); tanpa border (di bawah tabel utama) -->
			<th style="font-size: 0.75em;text-align:left; width: 28px;border: none; font-weight: normal;" colspan="40">&nbsp;</th>
			<th style="font-size: 0.75em;text-align:left; width: 100px;border: none; font-weight: normal;" colspan="100">&nbsp;</th>
			<th style="font-size: 0.75em;text-align:center; width: 310px;border: none;" colspan="310"><strong>Penerima </strong></th>
			<th style="font-size: 0.75em;text-align:right; width: 100px;border: none; font-weight: normal;" colspan="100">&nbsp;</th>
			<th style="font-size: 0.75em;text-align:center; width: 100px;border: none; font-weight: normal;" colspan="100">&nbsp;</th>
			<th style="font-size: 0.75em;text-align:center; width: 450px;border: none;" colspan="450"><strong>Hormat Kami</strong></th>
		</tr>

		<tr>
			<th colspan="1100" style="height: 2cm; padding: 0; margin: 0; border: none; font-weight: normal; background: white; line-height: 0;">&nbsp;</th>
		</tr>

	
		<tr>
			<th style="font-size: 0.75em;text-align:left; width: 28px;border: none; font-weight: normal;" colspan="40">&nbsp;</th>
			<th style="font-size: 0.75em;text-align:left; width: 100px;border: none; font-weight: normal;" colspan="100">&nbsp;</th>
			<th style="font-size: 0.75em;text-align:center; width: 310px;border: none;" colspan="310"><strong>(<span style="display:inline-block;width:6cm;min-width:6cm;">&nbsp;</span>)</strong></th>
			<th style="font-size: 0.75em;text-align:right; width: 100px;border: none; font-weight: normal;" colspan="100">&nbsp;</th>
			<th style="font-size: 0.75em;text-align:center; width: 100px;border: none; font-weight: normal;" colspan="100">&nbsp;</th>
			<th style="font-size: 0.75em;text-align:right; width: 450px;border: none;" colspan="450"><strong style="display:block;width:100%;text-align:right;"><span style="display:inline-flex;align-items:baseline;white-space:nowrap;"><span>(</span><span style="display:inline-block;width:6cm;min-width:6cm;">&nbsp;</span><span>)</span></span></strong></th>
		</tr>





	</table>

	<script>
	(function () {
		function cellContentWidth(th) {
			var cs = window.getComputedStyle(th);
			var pl = parseFloat(cs.paddingLeft) || 0;
			var pr = parseFloat(cs.paddingRight) || 0;
			var w = th.clientWidth - pl - pr;
			if (w < 4) {
				var r = th.getBoundingClientRect();
				w = r.width - pl - pr;
			}
			return Math.max(8, w - 2);
		}

		function fitKonsumenNama() {
			var span = document.getElementById('cetak-konsumen-nama');
			if (!span) return;
			var th = span.closest('th');
			if (!th) return;

			var avail = cellContentWidth(th);

			span.style.whiteSpace = 'nowrap';
			span.style.display = 'inline-block';
			span.style.maxWidth = 'none';
			span.style.width = 'auto';
			span.style.transform = '';
			span.style.transformOrigin = '';
			span.style.fontSize = '';
			void span.offsetWidth;

			var maxPx = parseFloat(window.getComputedStyle(span).fontSize);
			if (!maxPx || isNaN(maxPx)) maxPx = 12;

			function setFont(px) {
				span.style.fontSize = Math.max(0.5, px) + 'px';
				void span.offsetWidth;
			}

			function fits() {
				return span.scrollWidth <= avail + 0.75;
			}

			setFont(maxPx);
			if (fits()) return;

			var minPx = 3;
			var lo = minPx;
			var hi = maxPx;
			var i;
			for (i = 0; i < 40 && hi - lo > 0.04; i++) {
				var mid = (lo + hi) * 0.5;
				setFont(mid);
				if (fits()) lo = mid;
				else hi = mid;
			}
			setFont(lo);

			for (i = 0; i < 80 && lo + 0.05 <= maxPx; i++) {
				var tryPx = lo + 0.05;
				setFont(tryPx);
				if (!fits()) {
					setFont(lo);
					break;
				}
				lo = tryPx;
			}

			if (!fits()) {
				setFont(minPx);
			}

			void span.offsetWidth;
			if (!fits() && avail > 0 && span.scrollWidth > 0) {
				var s = avail / span.scrollWidth;
				if (s < 1 && s > 0.05) {
					span.style.transform = 'scaleX(' + s + ')';
					span.style.transformOrigin = 'left center';
				}
			}
		}

		function scheduleFit() {
			requestAnimationFrame(function () {
				requestAnimationFrame(fitKonsumenNama);
			});
		}

		function beforePrintFit() {
			setTimeout(fitKonsumenNama, 0);
		}

		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', scheduleFit);
		} else {
			scheduleFit();
		}
		window.addEventListener('load', scheduleFit);
		window.addEventListener('beforeprint', beforePrintFit);
		window.addEventListener('resize', scheduleFit);
	})();
	</script>

</body>



<?php

function penyebut($nilai)
{
	$nilai = abs($nilai);
	$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
	$temp = "";
	if ($nilai < 12) {
		$temp = " " . $huruf[$nilai];
	} else if ($nilai < 20) {
		$temp = penyebut($nilai - 10) . " belas";
	} else if ($nilai < 100) {
		$temp = penyebut($nilai / 10) . " puluh" . penyebut($nilai % 10);
	} else if ($nilai < 200) {
		$temp = " seratus" . penyebut($nilai - 100);
	} else if ($nilai < 1000) {
		$temp = penyebut($nilai / 100) . " ratus" . penyebut($nilai % 100);
	} else if ($nilai < 2000) {
		$temp = " seribu" . penyebut($nilai - 1000);
	} else if ($nilai < 1000000) {
		$temp = penyebut($nilai / 1000) . " ribu" . penyebut($nilai % 1000);
	} else if ($nilai < 1000000000) {
		$temp = penyebut($nilai / 1000000) . " juta" . penyebut($nilai % 1000000);
	} else if ($nilai < 1000000000000) {
		$temp = penyebut($nilai / 1000000000) . " milyar" . penyebut(fmod($nilai, 1000000000));
	} else if ($nilai < 1000000000000000) {
		$temp = penyebut($nilai / 1000000000000) . " trilyun" . penyebut(fmod($nilai, 1000000000000));
	}
	return $temp;
}

function terbilang($nilai)
{
	if ($nilai < 0) {
		$hasil = "minus " . trim(penyebut($nilai));
	} else {
		$hasil = trim(penyebut($nilai));
	}
	return $hasil;
}


// $angka = 1530093;
// echo terbilang($angka);

?>