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

		#customers col.cetak-col-no {
			width: 3ch;
		}

		#customers col.cetak-col-deskripsi {
			width: 50%;
		}

		#customers col.cetak-col-unit {
			width: 16.666%;
		}

		#customers col.cetak-col-harga {
			width: 16.667%;
		}

		#customers col.cetak-col-jumlah {
			width: 16.667%;
		}

		#customers tr.cetak-barang > th.cetak-col-no-cell {
			width: 3ch;
			max-width: 3ch;
			min-width: 3ch;
			text-align: center;
			white-space: nowrap;
			box-sizing: border-box;
		}

		#customers tr.cetak-barang > th.cetak-col-deskripsi-cell {
			text-align: left;
		}

		#customers tr.cetak-barang > th.cetak-col-unit-cell,
		#customers tr.cetak-barang > th.cetak-col-harga-cell,
		#customers tr.cetak-barang > th.cetak-col-jumlah-cell {
			white-space: nowrap;
			box-sizing: border-box;
		}

		#customers tr.cetak-barang > th.cetak-col-unit-cell {
			text-align: center;
		}

		#customers tr.cetak-barang > th.cetak-col-harga-cell,
		#customers tr.cetak-barang > th.cetak-col-jumlah-cell {
			text-align: right;
		}

		#customers tr.cetak-barang.cetak-row-miko > th {
			padding-top: 3px;
			padding-bottom: 3px;
			line-height: 1.12;
			vertical-align: middle;
		}

		/* Grid faktur: semua sel Courier New 0.9em (header, data, terbilang, total) — dot matrix */
		#customers tr.cetak-barang th {
			font-family: "Courier New", Courier, monospace;
			font-size: 0.9em;
			font-variant-numeric: tabular-nums;
			line-height: 1.12;
			padding: 3px 4px;
			vertical-align: middle;
			background-color: #fff;
			border-color: #000;
			border-style: solid;
			-webkit-print-color-adjust: exact;
			print-color-adjust: exact;
		}

		/* Kolom numerik (unit, harga, jumlah) */
		#customers tr.cetak-barang > th:nth-child(3),
		#customers tr.cetak-barang > th:nth-child(4),
		#customers tr.cetak-barang > th:nth-child(5) {
			width: auto;
			max-width: none;
			padding-left: 1mm;
			padding-right: 1mm;
			box-sizing: border-box;
		}

		/* Header kolom: bold, rata tengah */
		#customers tr.cetak-barang-header th {
			font-weight: bold;
			text-align: center;
		}

		#customers tr:nth-child(even) {
			background-color: #f2f2f2;
		}

		#customers tr:hover {
			background-color: #ddd;
		}

		/* Grid faktur: selalu putih (strip abu mengganggu cetak dot matrix) */
		#customers tr.cetak-barang {
			background-color: #fff !important;
		}

		#customers th {
			padding-top: 1px;
			padding-bottom: 1px;
			/* text-align: left; */
			background-color: white;
			color: black;
		}

		#customers tr.cetak-barang > th {
			padding-top: 3px;
			padding-bottom: 3px;
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

		/* Blok tanda tangan Penerima: geser ke kiri; "Penerima" tepat di tengah ruang antara ( dan ) */
		#customers th.cetak-penerima-tanda-wrap {
			text-align: left;
			vertical-align: middle;
			padding-left: 2px;
			padding-right: 2px;
		}

		#customers table.cetak-penerima-tanda-tabel {
			border-collapse: collapse;
			margin: 0;
			margin-right: auto;
			border: none;
		}

		#customers table.cetak-penerima-tanda-tabel td {
			border: none;
			padding: 0 1px;
			vertical-align: middle;
			background: transparent;
		}

		#customers table.cetak-penerima-tanda-tabel td.cetak-paren-gap {
			width: 6cm;
			min-width: 6cm;
			text-align: center;
			box-sizing: border-box;
		}

		#customers span.cetak-paren-invis {
			color: transparent;
		}

		/* Kotak gabungan KEPADA YTH + PERUMDAM TIRTA / PROJOTAMANSARI */
		#customers th.cetak-box-kepada-perumdam {
			font-size: 0.75em;
			text-align: left;
			border: 1px solid black;
			padding: 6px 8px;
			vertical-align: top;
		}

		#customers th.cetak-box-kepada-perumdam .cetak-kepada-yth-baris {
			margin-bottom: 6px;
		}

		@media print {
			body {
				margin: 0;
			}

			#customers tr:nth-child(even) th,
			#customers tr:nth-child(even) td {
				background-color: #fff !important;
			}

			#customers tr:hover th,
			#customers tr:hover td {
				background-color: #fff !important;
			}

			#customers tr.cetak-barang th {
				font-family: "Courier New", Courier, monospace !important;
				font-size: 0.9em !important;
				font-variant-numeric: tabular-nums !important;
				line-height: 1.12 !important;
				padding: 2px 3px !important;
				border-color: #000 !important;
			}

			#customers tr.cetak-barang > th {
				padding-top: 2px !important;
				padding-bottom: 2px !important;
			}

			#customers tr.cetak-barang > th:nth-child(5) {
				padding-left: 1mm !important;
				padding-right: 1mm !important;
			}
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

/**
 * Nama di blok Kepada Yth.: suku kata 1â€“2 utuh; dari suku kata ke-3 hanya huruf pertama (huruf besar).
 * Contoh: "RSUD PANEMBAHAN SENOPATI" â†’ "RSUD PANEMBAHAN S"
 * "RSUD PANEMBAHAN SENOPATI BANTUL PROJO TAMANSARI" â†’ "RSUD PANEMBAHAN S B P T"
 */
if (!function_exists('format_kepada_yth_nama_cetak')) {
	function format_kepada_yth_nama_cetak($nama)
	{
		if (!is_string($nama)) {
			$nama = (string) $nama;
		}
		$nama = trim(preg_replace('/\s+/u', ' ', $nama));
		if ($nama === '') {
			return '';
		}
		$parts = preg_split('/\s+/u', $nama, -1, PREG_SPLIT_NO_EMPTY);
		if (count($parts) <= 2) {
			return $nama;
		}
		$out = array($parts[0]);
		$out[] = function_exists('mb_strtoupper') ? mb_strtoupper($parts[1], 'UTF-8') : strtoupper($parts[1]);
		$n = count($parts);
		for ($i = 2; $i < $n; $i++) {
			$w = $parts[$i];
			if ($w === '') {
				continue;
			}
			$first = function_exists('mb_substr') ? mb_substr($w, 0, 1, 'UTF-8') : substr($w, 0, 1);
			if ($first !== '') {
				$out[] = function_exists('mb_strtoupper') ? mb_strtoupper($first, 'UTF-8') : strtoupper($first);
			}
		}
		return implode(' ', $out);
	}
}

$konsumen_nama_kepada_yth = format_kepada_yth_nama_cetak(isset($konsumen_nama_selected) ? $konsumen_nama_selected : '');

?>

<body>



	<table id="customers">
		<colgroup>
			<col class="cetak-col-no">
			<col class="cetak-col-deskripsi">
			<col class="cetak-col-unit">
			<col class="cetak-col-harga">
			<col class="cetak-col-jumlah">
		</colgroup>

		<!-- BARIS KE 1 -->
		<tr>
			<th colspan="5" style="font-size: 0.75em; text-align: left; border: none; border-collapse: collapse; padding: 3px 0;">
				<strong>Kepada : </strong><?php echo htmlspecialchars(trim((string) (isset($konsumen_nama_selected) ? $konsumen_nama_selected : '')), ENT_QUOTES, 'UTF-8'); ?>
			</th>
		</tr>

		<!-- BARIS KE 2 -->
		<tr>
			<th colspan="3" style="font-size: 0.75em; text-align: left; border: none; border-collapse: collapse; padding: 3px 0;">
				<strong>Alamat : </strong><?php echo htmlspecialchars(trim((string) (isset($konsumen_alamat_selected) ? $konsumen_alamat_selected : '')), ENT_QUOTES, 'UTF-8'); ?>
			</th>
			<th colspan="2" style="font-size: 0.75em; text-align: left; border: none; border-collapse: collapse; padding: 3px 0; white-space: nowrap;">
				<strong>Nomor Pesan : </strong><?php echo htmlspecialchars(trim((string) (isset($nmr_pesan_selected) ? $nmr_pesan_selected : '')), ENT_QUOTES, 'UTF-8'); ?>
			</th>
		</tr>









		<!-- BARIS KE 3 -->
		<tr>
			<th colspan="5" style="font-size: 0.95em; text-align: center; border: none; padding: 4px 0 5px 0; vertical-align: middle;">
				<strong>NOTA PEMBAYARAN</strong>
			</th>
		</tr>

		<!-- BARIS KE 5 — header data -->
		<tr class="cetak-barang cetak-barang-header">
			<th class="cetak-col-no-cell" style="border: 1px solid black; border-right: none; border-collapse: collapse;">No.</th>
			<th class="cetak-col-deskripsi-cell" style="border: 1px solid black; border-right: none; border-collapse: collapse;">Deskripsi</th>
			<th class="cetak-col-unit-cell" style="border: 1px solid black; border-right: none; border-collapse: collapse;">Unit (Org)</th>
			<th class="cetak-col-harga-cell" style="border: 1px solid black; border-right: none; border-collapse: collapse;">Harga Satuan</th>
			<th class="cetak-col-jumlah-cell" style="border: 1px solid black; border-collapse: collapse;">Jumlah</th>
		</tr>

		<!-- BARIS KE 6+ — data penjualan (minimal 5 baris) -->
		<?php
		$start = 0;
		$TOTAL_PENJUALAN = 0;
		$min_data_rows = 5;

		foreach ($data_penjualan as $list_data) {
			$x_total = $list_data->jumlah * $list_data->harga_satuan;
			$TOTAL_PENJUALAN += $x_total;
		?>
			<tr class="cetak-barang">
				<th class="cetak-col-no-cell" style="text-align:center; border: 1px solid black; border-right:none; border-collapse: collapse;"><strong><?php echo ++$start; ?></strong></th>
				<th class="cetak-col-deskripsi-cell" style="text-align:left; border: 1px solid black; border-right:none; border-collapse: collapse;"><strong><?php echo $list_data->nama_barang; ?></strong></th>
				<th class="cetak-col-unit-cell" style="text-align:center; border: 1px solid black; border-right:none; border-collapse: collapse;"><strong><?php echo nominal($list_data->jumlah) . ' ' . $list_data->satuan; ?></strong></th>
				<th class="cetak-col-harga-cell" style="text-align:right; border: 1px solid black; border-right:none; border-collapse: collapse;"><strong><?php echo nominal($list_data->harga_satuan); ?></strong></th>
				<th class="cetak-col-jumlah-cell" style="text-align:right; border: 1px solid black; border-collapse: collapse;"><strong><?php echo nominal($x_total); ?></strong></th>
			</tr>
		<?php
		}
		for ($pad_row = $start; $pad_row < $min_data_rows; $pad_row++) {
		?>
			<tr class="cetak-barang">
				<th class="cetak-col-no-cell" style="text-align:center; border: 1px solid black; border-right:none; border-collapse: collapse;">&nbsp;</th>
				<th class="cetak-col-deskripsi-cell" style="text-align:left; border: 1px solid black; border-right:none; border-collapse: collapse;">&nbsp;</th>
				<th class="cetak-col-unit-cell" style="text-align:center; border: 1px solid black; border-right:none; border-collapse: collapse;">&nbsp;</th>
				<th class="cetak-col-harga-cell" style="text-align:right; border: 1px solid black; border-right:none; border-collapse: collapse;">&nbsp;</th>
				<th class="cetak-col-jumlah-cell" style="text-align:right; border: 1px solid black; border-collapse: collapse;">&nbsp;</th>
			</tr>
		<?php
		}
		$PPN_NOMINAL = 28000;
		$FEE_ADMIN_NOMINAL = 10000;
		$DIBAYAR = $TOTAL_PENJUALAN - $PPN_NOMINAL - $FEE_ADMIN_NOMINAL;
		$terbilang_dibayar = ucwords(terbilang((int) round($DIBAYAR))) . ' Rupiah';
		$cetak_border_no_tb = 'border-left: 1px solid black; border-right: 1px solid black; border-top: none; border-bottom: none; border-collapse: collapse;';
		?>

		<!-- Baris kosong -->
		<tr class="cetak-barang">
			<th class="cetak-col-no-cell" style="border: 1px solid black; border-right:none; border-collapse: collapse;">&nbsp;</th>
			<th class="cetak-col-deskripsi-cell" style="border: 1px solid black; border-right:none; border-collapse: collapse;">&nbsp;</th>
			<th class="cetak-col-unit-cell" style="border: 1px solid black; border-right:none; border-collapse: collapse;">&nbsp;</th>
			<th class="cetak-col-harga-cell" style="border: 1px solid black; border-right:none; border-collapse: collapse;">&nbsp;</th>
			<th class="cetak-col-jumlah-cell" style="border: 1px solid black; border-collapse: collapse;">&nbsp;</th>
		</tr>

		<!-- Baris total -->
		<tr class="cetak-barang">
			<th class="cetak-col-no-cell" style="border: 1px solid black; border-right:none; border-collapse: collapse;">&nbsp;</th>
			<th class="cetak-col-deskripsi-cell" style="border: 1px solid black; border-right:none; border-collapse: collapse;">&nbsp;</th>
			<th colspan="2" class="cetak-col-unit-cell" style="text-align:center; border: 1px solid black; border-right:none; border-collapse: collapse;"><strong>Total</strong></th>
			<th class="cetak-col-jumlah-cell" style="text-align:right; border: 1px solid black; border-collapse: collapse;"><strong><?php echo nominal($TOTAL_PENJUALAN); ?></strong></th>
		</tr>

		<!-- Baris PPN -->
		<tr class="cetak-barang">
			<th class="cetak-col-no-cell" style="border: 1px solid black; border-right:none; border-collapse: collapse;">&nbsp;</th>
			<th class="cetak-col-deskripsi-cell" style="border: 1px solid black; border-right:none; border-collapse: collapse;">&nbsp;</th>
			<th colspan="2" class="cetak-col-unit-cell" style="text-align:center; border: 1px solid black; border-right:none; border-collapse: collapse;"><strong>PPN</strong></th>
			<th class="cetak-col-jumlah-cell" style="text-align:right; border: 1px solid black; border-collapse: collapse;"><strong><?php echo nominal($PPN_NOMINAL); ?></strong></th>
		</tr>

		<!-- Baris Fee Admin -->
		<tr class="cetak-barang">
			<th class="cetak-col-no-cell" style="border: 1px solid black; border-right:none; border-collapse: collapse;">&nbsp;</th>
			<th class="cetak-col-deskripsi-cell" style="border: 1px solid black; border-right:none; border-collapse: collapse;">&nbsp;</th>
			<th colspan="2" class="cetak-col-unit-cell" style="text-align:center; border: 1px solid black; border-right:none; border-collapse: collapse;"><strong>Fee Admin</strong></th>
			<th class="cetak-col-jumlah-cell" style="text-align:right; border: 1px solid black; border-collapse: collapse;"><strong><?php echo nominal($FEE_ADMIN_NOMINAL); ?></strong></th>
		</tr>

		<!-- Baris Terbilang & Dibayar -->
		<tr class="cetak-barang">
			<th class="cetak-col-no-cell" style="border: 1px solid black; border-right:none; border-collapse: collapse;">&nbsp;</th>
			<th class="cetak-col-deskripsi-cell" style="border: 1px solid black; border-right:none; border-collapse: collapse;"><strong><em>Terbilang :</em></strong></th>
			<th colspan="2" class="cetak-col-unit-cell" style="text-align:center; border: 1px solid black; border-right:none; border-collapse: collapse;"><strong>Dibayar</strong></th>
			<th class="cetak-col-jumlah-cell" style="text-align:right; border: 1px solid black; border-collapse: collapse;"><strong><?php echo nominal($DIBAYAR); ?></strong></th>
		</tr>

		<!-- Baris terbilang nominal & tanggal bayar -->
		<tr class="cetak-barang">
			<th class="cetak-col-no-cell" style="border: 1px solid black; border-right:none; border-top:none; border-collapse: collapse;">&nbsp;</th>
			<th class="cetak-col-deskripsi-cell" style="text-align:left; border: 1px solid black; border-right:none; border-top:none; border-collapse: collapse; text-transform: capitalize;"><em><?php echo htmlspecialchars($terbilang_dibayar, ENT_QUOTES, 'UTF-8'); ?></em></th>
			<th colspan="3" class="cetak-col-unit-cell" style="text-align:left; border: 1px solid black; border-top:none; border-collapse: collapse; text-decoration: none;"><span style="text-decoration: none;"><?php echo htmlspecialchars(trim((string) (isset($tgl_bayar_selected) ? $tgl_bayar_selected : '')), ENT_QUOTES, 'UTF-8'); ?></span></th>
		</tr>

		<!-- Baris Catatan -->
		<tr class="cetak-barang">
			<th class="cetak-col-no-cell" style="border: 1px solid black; border-right:none; border-top:none; border-bottom:none; border-collapse: collapse;">&nbsp;</th>
			<th class="cetak-col-deskripsi-cell" style="text-align:left; border: 1px solid black; border-right:none; border-top:none; border-bottom:none; border-collapse: collapse;"><strong>Catatan:</strong></th>
			<th colspan="3" style="<?php echo $cetak_border_no_tb; ?>">&nbsp;</th>
		</tr>

		<!-- Baris Transfer Bank -->
		<tr class="cetak-barang">
			<th class="cetak-col-no-cell" style="border: 1px solid black; border-right:none; border-top:none; border-bottom:none; border-collapse: collapse;">&nbsp;</th>
			<th class="cetak-col-deskripsi-cell" style="text-align:left; vertical-align: top; border: 1px solid black; border-right:none; border-top:none; border-bottom:none; border-collapse: collapse; line-height: 1.35;">
				Tranfer Bank ke Rek. BPD DIY<br>
				004.111.000511<br>
				a.n Perumda Aneka Dharma
			</th>
			<th colspan="3" style="<?php echo $cetak_border_no_tb; ?>">&nbsp;</th>
		</tr>

		<!-- Baris tanda tangan Miko -->
		<tr class="cetak-barang cetak-row-miko">
			<th class="cetak-col-no-cell" style="border: 1px solid black; border-right:none; border-collapse: collapse;">&nbsp;</th>
			<th class="cetak-col-deskripsi-cell" style="border: 1px solid black; border-right:none; border-collapse: collapse;">&nbsp;</th>
			<th colspan="3" style="text-align:center; border: 1px solid black; border-collapse: collapse;"><strong>( Miko )</strong></th>
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



