<head>
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
			<th style="border: 1px solid black;  border-top:none;border-bottom:none;border-right:none;border-left:none;  border-collapse: collapse; font-size:1vw;text-align:center; width: 1000px;" colspan="1000">
				<strong>PERUMDA ANEKA DHARMA KABUPATEN BANTUL</strong>
			</th>
		</tr>

		<tr>
			<th style="border: 1px solid black;  border-top:none;border-bottom:none;border-right:none;border-left:none;  border-collapse: collapse; font-size:1vw;text-align:center; width: 1000px;" colspan="1000">
				<strong>LAPORAN LABA - RUGI</strong>
			</th>
		</tr>

		<tr>
			<th style="border: 1px solid black;  border-top:none;border-bottom:none;border-right:none;border-left:none;  border-collapse: collapse; font-size:1vw;text-align:center; width: 1000px;" colspan="1000">
				<!-- <strong>Per Tanggal 31 Juli 2024</strong> -->

				<strong>Per
					<?php
					// echo  date('d F Y'); 
					//echo  date('d F Y'); 
					// echo $tahun_neraca;
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






		<tr>

			<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400">PENJUALAN</th>

			<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
			<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 200px;" colspan="200"><?php
																																// echo $data_tbl_laba_rugi->penjualan 
																																echo number_format($data_tbl_laba_rugi->penjualan, 2, ',', '.');
																																?></th>
			<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

		</tr>





		<tr>

			<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400">BEBAN POKOK PENJUALAN</th>

			<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
			<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
			<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200"><?php
																							// echo $data_tbl_laba_rugi->beban_pokok_penjualan 
																							echo number_format($data_tbl_laba_rugi->beban_pokok_penjualan, 2, ',', '.');
																							?></th>
			<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

		</tr>





		<tr>

			<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 400px;" colspan="400">LABA RUGI BRUTO</th>

			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
			<th style="font-size:0.550em; text-align:left; border: 1px solid black; border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
			<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse; width: 200px;" colspan="200"><?php
																																													// echo str_replace('.', ',', $data_tbl_laba_rugi->penjualan - $data_tbl_laba_rugi->beban_pokok_penjualan); 
																																													echo number_format($data_tbl_laba_rugi->penjualan - $data_tbl_laba_rugi->beban_pokok_penjualan, 2, ',', '.');
																																													?></th>
			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 20px;" colspan="20"></th>

		</tr>



		<!-- buat jarak dengan baris atasnya -->
		<tr>

			<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 400px;" colspan="400"></th>

			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
			<th style="font-size:0.550em; text-align:left; border: 1px solid black; border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>
			<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse; width: 200px;" colspan="200"></th>
			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 20px;" colspan="20"></th>

		</tr>





		<tr>

			<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400">BEBAN OPERASIONAL</th>

			<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
			<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>
			<th style="font-size:0.550em; text-align:right;border-top:none;border-bottom:none;  width: 200px;" colspan="200"></th>
			<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

		</tr>





		<tr>

			<th style="font-size: 0.550em;text-align:left; width: 75px;" colspan="75"></th>
			<th style="font-size: 0.550em;text-align:left; width: 325px;" colspan="325">Beban Operasional Promosi</th>

			<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
			<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
			<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200"><?php
																							// echo $data_tbl_laba_rugi->beban_operasional_promosi 
																							echo number_format($data_tbl_laba_rugi->beban_operasional_promosi, 2, ',', '.');
																							?></th>
			<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

		</tr>







		<tr>

			<th style="font-size: 0.550em;text-align:left; width: 75px;" colspan="75"></th>
			<th style="font-size: 0.550em;text-align:left; width: 325px;" colspan="325">Beban Perjalanan Dinas</th>

			<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
			<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
			<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200"><?php
																							// echo $data_tbl_laba_rugi->beban_perjalanan_dinas 
																							echo number_format($data_tbl_laba_rugi->beban_perjalanan_dinas, 2, ',', '.');
																							?></th>
			<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

		</tr>



		<tr>

			<th style="font-size: 0.550em;text-align:left; width: 75px;" colspan="75"></th>
			<th style="font-size: 0.550em;text-align:left; width: 325px;" colspan="325">Beban Transportasi</th>

			<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
			<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
			<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200"><?php
																							// echo $data_tbl_laba_rugi->beban_transportasi; 
																							echo number_format($data_tbl_laba_rugi->beban_transportasi, 2, ',', '.');
																							?></th>
			<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

		</tr>





		<tr>

			<th style="font-size: 0.550em;text-align:left; width: 75px;" colspan="75"></th>
			<th style="font-size: 0.550em;text-align:left; width: 325px;" colspan="325">Beban Pemeliharaan</th>

			<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
			<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
			<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200"><?php
																							// echo $data_tbl_laba_rugi->beban_pemeliharaan; 
																							echo number_format($data_tbl_laba_rugi->beban_pemeliharaan, 2, ',', '.');
																							?></th>
			<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

		</tr>








		<tr>

			<th style="font-size: 0.550em;text-align:left; width: 75px;" colspan="75"></th>
			<th style="font-size: 0.550em;text-align:left; width: 325px;" colspan="325">Total Beban Operaisonal umum</th>

			<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
			<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
			<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200"><?php
																							// echo $data_tbl_laba_rugi->total_beban_operasional_umum; 
																							echo number_format($data_tbl_laba_rugi->total_beban_operasional_umum, 2, ',', '.');
																							?></th>
			<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

		</tr>







		<tr>

			<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 400px;" colspan="400">Total Beban Operasional</th>

			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
			<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200"><?php

																																													// echo str_replace('.', ',', $data_tbl_laba_rugi->beban_depresiasi_dan_amortisasi + $data_tbl_laba_rugi->beban_operasional_karyawan + $data_tbl_laba_rugi->beban_operasional_promosi + $data_tbl_laba_rugi->beban_perjalanan_dinas + $data_tbl_laba_rugi->beban_transportasi + $data_tbl_laba_rugi->beban_pemeliharaan + $data_tbl_laba_rugi->total_beban_operasional_umum); 

																																													echo number_format($data_tbl_laba_rugi->beban_depresiasi_dan_amortisasi + $data_tbl_laba_rugi->beban_operasional_karyawan + $data_tbl_laba_rugi->beban_operasional_promosi + $data_tbl_laba_rugi->beban_perjalanan_dinas + $data_tbl_laba_rugi->beban_transportasi + $data_tbl_laba_rugi->beban_pemeliharaan + $data_tbl_laba_rugi->total_beban_operasional_umum, 2, ',', '.');

																																													?></th>
			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

		</tr>



		<tr>

			<th style="font-size: 0.550em;text-align:left;border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 400px;" colspan="400">Laba / Rugi Operasional</th>

			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
			<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200"><?php

																																													// echo str_replace('.', ',', ($data_tbl_laba_rugi->penjualan - $data_tbl_laba_rugi->beban_pokok_penjualan) - ($data_tbl_laba_rugi->beban_depresiasi_dan_amortisasi + $data_tbl_laba_rugi->beban_operasional_karyawan + $data_tbl_laba_rugi->beban_operasional_promosi + $data_tbl_laba_rugi->beban_perjalanan_dinas + $data_tbl_laba_rugi->beban_transportasi + $data_tbl_laba_rugi->beban_pemeliharaan + $data_tbl_laba_rugi->total_beban_operasional_umum));


																																													echo number_format(($data_tbl_laba_rugi->penjualan - $data_tbl_laba_rugi->beban_pokok_penjualan) - ($data_tbl_laba_rugi->beban_depresiasi_dan_amortisasi + $data_tbl_laba_rugi->beban_operasional_karyawan + $data_tbl_laba_rugi->beban_operasional_promosi + $data_tbl_laba_rugi->beban_perjalanan_dinas + $data_tbl_laba_rugi->beban_transportasi + $data_tbl_laba_rugi->beban_pemeliharaan + $data_tbl_laba_rugi->total_beban_operasional_umum), 2, ',', '.');
																																													$GET_Labar_rugi_operasional = ($data_tbl_laba_rugi->penjualan - $data_tbl_laba_rugi->beban_pokok_penjualan) - ($data_tbl_laba_rugi->beban_depresiasi_dan_amortisasi + $data_tbl_laba_rugi->beban_operasional_karyawan + $data_tbl_laba_rugi->beban_operasional_promosi + $data_tbl_laba_rugi->beban_perjalanan_dinas + $data_tbl_laba_rugi->beban_transportasi + $data_tbl_laba_rugi->beban_pemeliharaan + $data_tbl_laba_rugi->total_beban_operasional_umum);
																																													?></th>
			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

		</tr>


		<!-- Garis double : bprder-top : 1px -->
		<tr>

			<th style="font-size: 0.550em;text-align:left;border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 400px;" colspan="400"></th>

			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>
			<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200"></th>
			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

		</tr>



		<tr>

			<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 75px;" colspan="75"></th>
			<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 325px;" colspan="325">Pendapatan Bunga Bank</th>

			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
			<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200"><?php

																																																						// echo $data_tbl_laba_rugi->pendapatan_bunga_bank; 

																																																						echo number_format($data_tbl_laba_rugi->pendapatan_bunga_bank, 2, ',', '.');

																																																						?></th>
			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

		</tr>





		<tr>

			<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 75px;" colspan="75"></th>
			<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 325px;" colspan="325">Pendapatan Rupa-Rupa</th>

			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
			<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200"><?php
																																																						// echo $data_tbl_laba_rugi->pendapatan_rupa_rupa; 
																																																						echo number_format($data_tbl_laba_rugi->pendapatan_rupa_rupa, 2, ',', '.');
																																																						?></th>
			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

		</tr>




		<tr>

			<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 75px;" colspan="75"></th>
			<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 325px;" colspan="325"><strong>Total Pendapatan Lain-lain</strong></th>

			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
			<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200"><?php

																																																						// echo str_replace('.', ',', $data_tbl_laba_rugi->pendapatan_bunga_bank - $data_tbl_laba_rugi->pendapatan_rupa_rupa);

																																																						echo number_format($data_tbl_laba_rugi->pendapatan_bunga_bank + $data_tbl_laba_rugi->pendapatan_rupa_rupa, 2, ',', '.');

																																																						$GET_Total_Pendapatan_Lain_Lain = $data_tbl_laba_rugi->pendapatan_bunga_bank + $data_tbl_laba_rugi->pendapatan_rupa_rupa;
																																																						?></th>
			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

		</tr>



		<!-- Garis double : bprder-top : 1px -->
		<tr>

			<th style="font-size: 0.550em;text-align:left;border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 400px;" colspan="400"></th>

			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>
			<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200"></th>
			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

		</tr>



		<tr>

			<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 75px;" colspan="75"></th>
			<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 325px;" colspan="325">Pendapatan Bunga Bank</th>

			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
			<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200"><?php

																																																						// echo $data_tbl_laba_rugi->beban_bunga_dan_adm_bank; 

																																																						echo number_format($data_tbl_laba_rugi->beban_bunga_dan_adm_bank, 2, ',', '.');

																																																						?></th>
			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

		</tr>





		<tr>

			<th style="font-size: 0.550em;text-align:left;border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 75px;" colspan="75"></th>
			<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 325px;" colspan="325">Beban Rupa-Rupa</th>

			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
			<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200"><?php

																																																						// echo $data_tbl_laba_rugi->beban_rupa_rupa; 

																																																						echo number_format($data_tbl_laba_rugi->beban_rupa_rupa, 2, ',', '.');
																																																						?></th>
			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

		</tr>





		<tr>

			<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 75px;" colspan="75"></th>
			<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 325px;" colspan="325"><strong>Total beban Lain-Lain</strong></th>

			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
			<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200"><?php

																																													// echo str_replace('.', ',', $data_tbl_laba_rugi->beban_bunga_dan_adm_bank + $data_tbl_laba_rugi->beban_rupa_rupa);

																																													echo number_format($data_tbl_laba_rugi->beban_bunga_dan_adm_bank + $data_tbl_laba_rugi->beban_rupa_rupa, 2, ',', '.');
																																													$GET_Total_beban_lain_lain = $data_tbl_laba_rugi->beban_bunga_dan_adm_bank + $data_tbl_laba_rugi->beban_rupa_rupa;
																																													?></th>
			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

		</tr>





		<!-- Garis double : bprder-top : 1px -->
		<tr>

			<th style="font-size: 0.550em;text-align:left;border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 400px;" colspan="400"></th>

			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>
			<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200"></th>
			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

		</tr>





		<tr>

			<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400">LABA RUGI SEBELUM PAJAK</th>

			<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
			<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
			<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200"><?php

																							// echo str_replace('.', ',', $GET_Labar_rugi_operasional + $GET_Total_Pendapatan_Lain_Lain - $GET_Total_beban_lain_lain);

																							echo number_format($GET_Labar_rugi_operasional + $GET_Total_Pendapatan_Lain_Lain - $GET_Total_beban_lain_lain, 2, ',', '.');

																							$GET_Laba_rugi_sebelum_pajak = $GET_Labar_rugi_operasional + $GET_Total_Pendapatan_Lain_Lain - $GET_Total_beban_lain_lain;
																							?></th>
			<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

		</tr>





		<tr>

			<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400">PAJAK</th>

			<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
			<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
			<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200"><?php

																							// echo $data_tbl_laba_rugi->pajak; 


																							echo number_format($data_tbl_laba_rugi->pajak, 2, ',', '.');

																							?></th>
			<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

		</tr>





		<tr>

			<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 400px;" colspan="400">LABA RUGI SETELAH PAJAK</th>

			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 10px;" colspan="10"></th>
			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 20px;" colspan="20">Rp.</th>
			<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 200px;" colspan="200"><?php

																																																		// echo str_replace('.', ',', $GET_Laba_rugi_sebelum_pajak - $data_tbl_laba_rugi->pajak); 

																																																echo number_format($GET_Laba_rugi_sebelum_pajak - $data_tbl_laba_rugi->pajak, 2, ',', '.');

																																																		?></th>
			<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 20px;" colspan="20"></th>

		</tr>




		<tr>

			<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400"></th>

			<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
			<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
			<th style="font-size:0.550em; text-align:center; width: 240px;" colspan="240">Bantul,
				<strong>
					<?php

					if ($bulan_laba_rugi > 0) {
						echo " Bulan " . bulan_teks($bulan_laba_rugi) . " Tahun " . $tahun_laba_rugi;
					} else {
						echo " Tahun " . $tahun_laba_rugi;
					}


					?>
				</strong>
			</th>
			<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

		</tr>



		<tr>

			<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400"></th>

			<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
			<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
			<th style="font-size:0.550em; text-align:center; width: 240px;" colspan="240">Perusahaan Umum Daerah Aneka Dharma</th>
			<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

		</tr>



		<tr>

			<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400"></th>

			<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
			<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
			<th style="font-size:0.550em; text-align:center; width: 240px;" colspan="240">Kabupaten Bantul</th>
			<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

		</tr>




		<tr>

			<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400"></th>

			<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
			<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
			<th style="font-size:0.550em; text-align:center; width: 240px;" colspan="240">Direktur</th>
			<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

		</tr>

		<br />
		<br />



		<tr>

			<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400"></th>

			<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
			<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
			<th style="font-size:0.550em; text-align:center; width: 240px;" colspan="240">Yuli Budi Sasangka,ST</th>
			<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

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