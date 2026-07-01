<head>
	<style>
		@page {
			margin: 10mm;
		}

		body {
			margin: 0;
			padding: 0;
			font-family: Arial, Helvetica, sans-serif;
			font-size: 11pt;
		}

		#customers {
			font-family: Arial, Helvetica, sans-serif;
			border-collapse: collapse;
			width: 100%;
			table-layout: fixed;
		}

		#customers td,
		#customers th {
			border: 0px solid #ddd;
			padding: 5px 4px;
			font-size: 11pt;
		}

		#customers tr:nth-child(even) {
			background-color: #f2f2f2;
		}

		#customers tr:hover {
			background-color: #ddd;
		}

		#customers th {
			padding-top: 3px;
			padding-bottom: 3px;
			background-color: white;
			color: black;
			font-weight: normal;
		}

		#customers .judul th {
			font-size: 14pt;
			font-weight: bold;
		}

		/* Border tabel disembunyikan */
		#customers tr.row-box th,
		#customers tr.row-box-full th {
			border: none !important;
		}

		#customers .col-label {
			text-align: left;
			padding-left: 8px;
			width: 72%;
		}

		#customers .col-label.indent {
			padding-left: 32px;
		}

		#customers .col-rp {
			text-align: left;
			width: 8%;
			white-space: nowrap;
		}

		#customers .col-nominal {
			text-align: right;
			padding-right: 8px;
			width: 20%;
			white-space: nowrap;
		}

		#customers .col-label.label-bold {
			font-weight: bold;
		}

		/* Highlight kolom Rp. + nominal pada baris penting */
		#customers tr.row-highlight-nominal th.col-rp,
		#customers tr.row-highlight-nominal th.col-nominal {
			border-left: none !important;
			border-right: none !important;
			border-top: 1px solid black !important;
			border-bottom: 3px double black !important;
		}

		/* Baris PAJAK: tanpa garis atas, garis bawah tunggal */
		#customers tr.row-highlight-pajak th.col-rp,
		#customers tr.row-highlight-pajak th.col-nominal {
			border-left: none !important;
			border-right: none !important;
			border-top: none !important;
			border-bottom: 1px solid black !important;
		}

		#customers .judul-row th {
			text-align: center;
			font-size: 14pt;
			font-weight: bold;
			border: none;
		}

		#ttd-footer {
			width: 100%;
			border-collapse: collapse;
			table-layout: fixed;
			margin-top: 8px;
		}

		#ttd-footer th {
			border: none;
			font-weight: normal;
			font-size: 11pt;
			padding: 4px 0;
		}

		#ttd-footer .ttd-spacer-left {
			width: 32%;
		}

		#ttd-footer .ttd-spacer-mid {
			width: 18%;
		}

		#ttd-footer .ttd-col-right {
			width: 50%;
			text-align: center;
			white-space: nowrap;
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



	<table id="customers" width="100%">
		<colgroup>
			<col style="width:72%">
			<col style="width:8%">
			<col style="width:20%">
		</colgroup>

		<!-- HEADER -->
		<tr class="judul-row">
			<th colspan="3"><strong>PERUMDA ANEKA DHARMA KABUPATEN BANTUL</strong></th>
		</tr>
		<tr class="judul-row">
			<th colspan="3"><strong>LAPORAN LABA - RUGI</strong></th>
		</tr>
		<tr class="judul-row">
			<th colspan="3">
				<strong>Per
					<?php
					function bulan_teks($angka_bulan)
					{
						if ($angka_bulan == 1) {
							$bulan_teks = "Januari";
						} elseif ($angka_bulan == 2) {
							$bulan_teks = "Februari";
						} elseif ($angka_bulan == 3) {
							$bulan_teks = "Maret";
						} elseif ($angka_bulan == 4) {
							$bulan_teks = "April";
						} elseif ($angka_bulan == 5) {
							$bulan_teks = "Mei";
						} elseif ($angka_bulan == 6) {
							$bulan_teks = "Juni";
						} elseif ($angka_bulan == 7) {
							$bulan_teks = "Juli";
						} elseif ($angka_bulan == 8) {
							$bulan_teks = "Agustus";
						} elseif ($angka_bulan == 9) {
							$bulan_teks = "September";
						} elseif ($angka_bulan == 10) {
							$bulan_teks = "Oktober";
						} elseif ($angka_bulan == 11) {
							$bulan_teks = "November";
						} elseif ($angka_bulan == 12) {
							$bulan_teks = "Desember";
						} else {
							$bulan_teks = "";
						}
						return $bulan_teks;
					}

					if ($bulan_laba_rugi > 0) {
						echo " Bulan " . bulan_teks($bulan_laba_rugi) . " Tahun " . $tahun_laba_rugi;
					} else {
						echo " Tahun " . $tahun_laba_rugi;
					}
					?>
				</strong>
			</th>
		</tr>

		<!-- Kotak pembatas atas data -->
		<tr class="row-box-full">
			<th colspan="3">&nbsp;</th>
		</tr>

		<tr class="row-box">
			<th class="col-label label-bold">PENJUALAN</th>
			<th class="col-rp">Rp.</th>
			<th class="col-nominal"><?php echo number_format($data_tbl_laba_rugi->penjualan, 2, ',', '.'); ?></th>
		</tr>

		<tr class="row-box">
			<th class="col-label label-bold">BEBAN POKOK PENJUALAN</th>
			<th class="col-rp">Rp.</th>
			<th class="col-nominal"><?php echo number_format($data_tbl_laba_rugi->beban_pokok_penjualan, 2, ',', '.'); ?></th>
		</tr>

		<tr class="row-box row-highlight-nominal">
			<th class="col-label label-bold">LABA RUGI BRUTO</th>
			<th class="col-rp">Rp.</th>
			<th class="col-nominal"><?php echo number_format($data_tbl_laba_rugi->penjualan - $data_tbl_laba_rugi->beban_pokok_penjualan, 2, ',', '.'); ?></th>
		</tr>

		<tr class="row-box">
			<th class="col-label">&nbsp;</th>
			<th class="col-rp"></th>
			<th class="col-nominal"></th>
		</tr>

		<tr class="row-box">
			<th class="col-label label-bold">BEBAN OPERASIONAL</th>
			<th class="col-rp"></th>
			<th class="col-nominal"></th>
		</tr>

		<tr class="row-box">
			<th class="col-label indent">Beban Operasional Promosi</th>
			<th class="col-rp">Rp.</th>
			<th class="col-nominal"><?php echo number_format($data_tbl_laba_rugi->beban_operasional_promosi, 2, ',', '.'); ?></th>
		</tr>

		<tr class="row-box">
			<th class="col-label indent">Beban Perjalanan Dinas</th>
			<th class="col-rp">Rp.</th>
			<th class="col-nominal"><?php echo number_format($data_tbl_laba_rugi->beban_perjalanan_dinas, 2, ',', '.'); ?></th>
		</tr>

		<tr class="row-box">
			<th class="col-label indent">Beban Transportasi</th>
			<th class="col-rp">Rp.</th>
			<th class="col-nominal"><?php echo number_format($data_tbl_laba_rugi->beban_transportasi, 2, ',', '.'); ?></th>
		</tr>

		<tr class="row-box">
			<th class="col-label indent">Beban Pemeliharaan</th>
			<th class="col-rp">Rp.</th>
			<th class="col-nominal"><?php echo number_format($data_tbl_laba_rugi->beban_pemeliharaan, 2, ',', '.'); ?></th>
		</tr>

		<tr class="row-box">
			<th class="col-label indent">Total Beban Operaisonal umum</th>
			<th class="col-rp">Rp.</th>
			<th class="col-nominal"><?php echo number_format($data_tbl_laba_rugi->total_beban_operasional_umum, 2, ',', '.'); ?></th>
		</tr>

		<tr class="row-box row-highlight-nominal">
			<th class="col-label">Total Beban Operasional</th>
			<th class="col-rp">Rp.</th>
			<th class="col-nominal"><?php
				echo number_format(
					$data_tbl_laba_rugi->beban_depresiasi_dan_amortisasi + $data_tbl_laba_rugi->beban_operasional_karyawan + $data_tbl_laba_rugi->beban_operasional_promosi + $data_tbl_laba_rugi->beban_perjalanan_dinas + $data_tbl_laba_rugi->beban_transportasi + $data_tbl_laba_rugi->beban_pemeliharaan + $data_tbl_laba_rugi->total_beban_operasional_umum,
					2, ',', '.'
				);
			?></th>
		</tr>

		<tr class="row-box row-highlight-nominal">
			<th class="col-label label-bold">Laba / Rugi Operasional</th>
			<th class="col-rp">Rp.</th>
			<th class="col-nominal"><?php
				echo number_format(
					($data_tbl_laba_rugi->penjualan - $data_tbl_laba_rugi->beban_pokok_penjualan) - ($data_tbl_laba_rugi->beban_depresiasi_dan_amortisasi + $data_tbl_laba_rugi->beban_operasional_karyawan + $data_tbl_laba_rugi->beban_operasional_promosi + $data_tbl_laba_rugi->beban_perjalanan_dinas + $data_tbl_laba_rugi->beban_transportasi + $data_tbl_laba_rugi->beban_pemeliharaan + $data_tbl_laba_rugi->total_beban_operasional_umum),
					2, ',', '.'
				);
				$GET_Labar_rugi_operasional = ($data_tbl_laba_rugi->penjualan - $data_tbl_laba_rugi->beban_pokok_penjualan) - ($data_tbl_laba_rugi->beban_depresiasi_dan_amortisasi + $data_tbl_laba_rugi->beban_operasional_karyawan + $data_tbl_laba_rugi->beban_operasional_promosi + $data_tbl_laba_rugi->beban_perjalanan_dinas + $data_tbl_laba_rugi->beban_transportasi + $data_tbl_laba_rugi->beban_pemeliharaan + $data_tbl_laba_rugi->total_beban_operasional_umum);
			?></th>
		</tr>

		<tr class="row-box">
			<th class="col-label">&nbsp;</th>
			<th class="col-rp"></th>
			<th class="col-nominal"></th>
		</tr>

		<tr class="row-box">
			<th class="col-label indent">Pendapatan Bunga Bank</th>
			<th class="col-rp">Rp.</th>
			<th class="col-nominal"><?php echo number_format($data_tbl_laba_rugi->pendapatan_bunga_bank, 2, ',', '.'); ?></th>
		</tr>

		<tr class="row-box">
			<th class="col-label indent">Pendapatan Rupa-Rupa</th>
			<th class="col-rp">Rp.</th>
			<th class="col-nominal"><?php echo number_format($data_tbl_laba_rugi->pendapatan_rupa_rupa, 2, ',', '.'); ?></th>
		</tr>

		<tr class="row-box row-highlight-nominal">
			<th class="col-label indent label-bold">Total Pendapatan Lain-lain</th>
			<th class="col-rp">Rp.</th>
			<th class="col-nominal"><?php
				echo number_format($data_tbl_laba_rugi->pendapatan_bunga_bank + $data_tbl_laba_rugi->pendapatan_rupa_rupa, 2, ',', '.');
				$GET_Total_Pendapatan_Lain_Lain = $data_tbl_laba_rugi->pendapatan_bunga_bank + $data_tbl_laba_rugi->pendapatan_rupa_rupa;
			?></th>
		</tr>

		<tr class="row-box">
			<th class="col-label">&nbsp;</th>
			<th class="col-rp"></th>
			<th class="col-nominal"></th>
		</tr>

		<tr class="row-box">
			<th class="col-label indent">Pendapatan Bunga Bank</th>
			<th class="col-rp">Rp.</th>
			<th class="col-nominal"><?php echo number_format($data_tbl_laba_rugi->beban_bunga_dan_adm_bank, 2, ',', '.'); ?></th>
		</tr>

		<tr class="row-box">
			<th class="col-label indent">Beban Rupa-Rupa</th>
			<th class="col-rp">Rp.</th>
			<th class="col-nominal"><?php echo number_format($data_tbl_laba_rugi->beban_rupa_rupa, 2, ',', '.'); ?></th>
		</tr>

		<tr class="row-box row-highlight-nominal">
			<th class="col-label indent label-bold">Total beban Lain-Lain</th>
			<th class="col-rp">Rp.</th>
			<th class="col-nominal"><?php
				echo number_format($data_tbl_laba_rugi->beban_bunga_dan_adm_bank + $data_tbl_laba_rugi->beban_rupa_rupa, 2, ',', '.');
				$GET_Total_beban_lain_lain = $data_tbl_laba_rugi->beban_bunga_dan_adm_bank + $data_tbl_laba_rugi->beban_rupa_rupa;
			?></th>
		</tr>

		<tr class="row-box">
			<th class="col-label">&nbsp;</th>
			<th class="col-rp"></th>
			<th class="col-nominal"></th>
		</tr>

		<tr class="row-box">
			<th class="col-label label-bold">LABA RUGI SEBELUM PAJAK</th>
			<th class="col-rp">Rp.</th>
			<th class="col-nominal"><?php
				echo number_format($GET_Labar_rugi_operasional + $GET_Total_Pendapatan_Lain_Lain - $GET_Total_beban_lain_lain, 2, ',', '.');
				$GET_Laba_rugi_sebelum_pajak = $GET_Labar_rugi_operasional + $GET_Total_Pendapatan_Lain_Lain - $GET_Total_beban_lain_lain;
			?></th>
		</tr>

		<tr class="row-box row-highlight-pajak">
			<th class="col-label label-bold">PAJAK</th>
			<th class="col-rp">Rp.</th>
			<th class="col-nominal"><?php echo number_format($data_tbl_laba_rugi->pajak, 2, ',', '.'); ?></th>
		</tr>

		<tr class="row-box">
			<th class="col-label label-bold">LABA RUGI SETELAH PAJAK</th>
			<th class="col-rp">Rp.</th>
			<th class="col-nominal"><?php echo number_format($GET_Laba_rugi_sebelum_pajak - $data_tbl_laba_rugi->pajak, 2, ',', '.'); ?></th>
		</tr>

		<!-- Kotak pembatas bawah data -->
		<tr class="row-box-full">
			<th colspan="3">&nbsp;</th>
		</tr>

	</table>

	<!-- Tanda tangan - tabel terpisah, 3 kolom, teks di kolom kanan -->
	<?php
	$ttd_tanggal = 'Bantul, ';
	if ($bulan_laba_rugi > 0) {
		$ttd_tanggal .= 'Bulan ' . bulan_teks($bulan_laba_rugi) . ' Tahun ' . $tahun_laba_rugi;
	} else {
		$ttd_tanggal .= 'Tahun ' . $tahun_laba_rugi;
	}
	?>
	<table id="ttd-footer" width="100%">
		<colgroup>
			<col style="width:32%">
			<col style="width:18%">
			<col style="width:50%">
		</colgroup>
		<tr>
			<th class="ttd-spacer-left"></th>
			<th class="ttd-spacer-mid"></th>
			<th class="ttd-col-right"><?php echo $ttd_tanggal; ?></th>
		</tr>
		<tr>
			<th class="ttd-spacer-left"></th>
			<th class="ttd-spacer-mid"></th>
			<th class="ttd-col-right">Perusahaan Umum Daerah Aneka Dharma</th>
		</tr>
		<tr>
			<th class="ttd-spacer-left"></th>
			<th class="ttd-spacer-mid"></th>
			<th class="ttd-col-right">Kabupaten Bantul</th>
		</tr>
		<tr>
			<th class="ttd-spacer-left"></th>
			<th class="ttd-spacer-mid"></th>
			<th class="ttd-col-right">Direktur</th>
		</tr>
		<tr>
			<th class="ttd-spacer-left"></th>
			<th class="ttd-spacer-mid"></th>
			<th class="ttd-col-right"><br><br></th>
		</tr>
		<tr>
			<th class="ttd-spacer-left"></th>
			<th class="ttd-spacer-mid"></th>
			<th class="ttd-col-right"><br><br></th>
		</tr>
		<tr>
			<th class="ttd-spacer-left"></th>
			<th class="ttd-spacer-mid"></th>
			<th class="ttd-col-right">Yuli Budi Sasangka,ST</th>
		</tr>
	</table>

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
