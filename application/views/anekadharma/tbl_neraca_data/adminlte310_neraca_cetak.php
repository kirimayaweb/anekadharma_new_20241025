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
				<strong>NERACA</strong>
			</th>
		</tr>

		<tr>
			<th style="border: 1px solid black;  border-top:none;border-bottom:none;border-right:none;border-left:none;  border-collapse: collapse; font-size:1vw;text-align:center; width: 1000px;" colspan="1000">
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
					
					if ($bulan_neraca > 0) {
						echo " Bulan " . bulan_teks($bulan_neraca) . " Tahun " . $tahun_neraca;
					} else {
						echo " Tahun " . $tahun_neraca;
					}


					?></strong>
			</th>
		</tr>

		<tr>
			<th style="border: 1px solid black;    border-collapse: collapse; font-size:1vw;text-align:center; width: 1000px;" colspan="1000">
				<strong></strong>
			</th>
		</tr>






		<tr>
			<!-- AKTIVA -->
			<th style="font-size: 0.550em;text-align:left; width: 500px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="500"><strong>AKTIVA</strong></th>
			<!-- <th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th> -->
			<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
			<!-- <th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 200px;" colspan="200"><?php //echo nominal(234323432) 
																																	?></th> -->
			<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

			<!-- PASIVA  -->
			<th style="font-size: 0.550em;text-align:left; width: 500px;border: 1px solid black;    border-collapse: collapse;" colspan="500"><strong>PASIVA</strong></th>
			<!-- <th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th> -->
			<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
			<!-- <th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 200px;" colspan="200"><?php //echo nominal(234323432) 
																																	?></th> -->
			<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

		</tr>


		<tr>
			<!-- AKTIVA -->
			<th style="font-size: 0.550em;text-align:left; width: 500px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="500"><strong>Aktiva Lancar</strong></th>

			<!-- <th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="10"></th> -->

			<!-- <th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th> -->

			<!-- <th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150"></th> -->

			<!-- <th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th> -->

			<!-- PASIVA  -->
			<th style="font-size: 0.550em;text-align:left; width: 500px;border: 1px solid black;border-top:none;border-bottom:none;   border-collapse: collapse;" colspan="500"><strong>Utang Lancar</strong></th>

			<!-- <th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="10"></th> -->

			<!-- <th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th> -->

			<!-- <th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150"></th> -->

			<!-- <th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th> -->

		</tr>


		<tr>
			<!-- AKTIVA -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="300">Kas</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
				<?php if (isset($data_tbl_neraca_data->kas)) {
					// echo nominal($data_tbl_neraca_data->kas);
					echo number_format($data_tbl_neraca_data->kas, 2, ',', '.');
					$TOTAL_AKIVA_LANCAR = $TOTAL_AKIVA_LANCAR + $data_tbl_neraca_data->kas;
				} ?>
			</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<!-- PASIVA  -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="300">Utang Usaha</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
				<?php
				if (isset($data_tbl_neraca_data->utang_usaha)) {
					// echo nominal($data_tbl_neraca_data->utang_usaha);
					echo number_format($data_tbl_neraca_data->utang_usaha, 2, ',', '.');
					$TOTAL_Utang_Lancar = $TOTAL_Utang_Lancar + $data_tbl_neraca_data->utang_usaha;
				} ?>
			</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th>

		</tr>





		<tr>
			<!-- AKTIVA -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="300">Bank</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
				<?php if (isset($data_tbl_neraca_data->bank)) {
					// echo nominal($data_tbl_neraca_data->bank);
					echo number_format($data_tbl_neraca_data->bank, 2, ',', '.');
					$TOTAL_AKIVA_LANCAR = $TOTAL_AKIVA_LANCAR + $data_tbl_neraca_data->bank;
				} ?>
			</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<!-- PASIVA  -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="300">Utang Pajak</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
				<?php if (isset($data_tbl_neraca_data->utang_pajak)) {
					// echo nominal($data_tbl_neraca_data->utang_pajak);
					echo number_format($data_tbl_neraca_data->utang_pajak, 2, ',', '.');
					$TOTAL_Utang_Lancar = $TOTAL_Utang_Lancar + $data_tbl_neraca_data->utang_pajak;
				} ?>
			</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th>

		</tr>



		<tr>
			<!-- AKTIVA -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="300">Piutang Usaha</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
				<?php if (isset($data_tbl_neraca_data->piutang_usaha)) {
					// echo nominal($data_tbl_neraca_data->piutang_usaha);
					echo number_format($data_tbl_neraca_data->piutang_usaha, 2, ',', '.');
					$TOTAL_AKIVA_LANCAR = $TOTAL_AKIVA_LANCAR + $data_tbl_neraca_data->piutang_usaha;
				} ?>
			</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<!-- PASIVA  -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="300">Utang Lain-lain</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
				<strong>
					<?php if (isset($data_tbl_neraca_data->utang_lain_lain)) {
						// echo nominal($data_tbl_neraca_data->utang_lain_lain);
						echo number_format($data_tbl_neraca_data->utang_lain_lain, 2, ',', '.');
						$TOTAL_Utang_Lancar = $TOTAL_Utang_Lancar + $data_tbl_neraca_data->utang_lain_lain;
					}  ?>
				</strong>
			</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th>

		</tr>





		<tr>
			<!-- AKTIVA -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="300">Piutang Non Usaha</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
				<?php if (isset($data_tbl_neraca_data->piutang_non_usaha)) {
					// echo nominal($data_tbl_neraca_data->piutang_non_usaha);
					echo number_format($data_tbl_neraca_data->piutang_non_usaha, 2, ',', '.');
					$TOTAL_AKIVA_LANCAR = $TOTAL_AKIVA_LANCAR + $data_tbl_neraca_data->piutang_non_usaha;
				} ?>
			</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<!-- PASIVA  -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="300"></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150"><?php 
			// echo nominal($TOTAL_Utang_Lancar) 
			echo number_format($TOTAL_Utang_Lancar, 2, ',', '.');
			?></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th>

		</tr>







		<tr>
			<!-- AKTIVA -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="300">Persediaan</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
				<?php if (isset($data_tbl_neraca_data->persediaan)) {
					// echo nominal($data_tbl_neraca_data->persediaan);
					echo number_format($data_tbl_neraca_data->persediaan, 2, ',', '.');
					$TOTAL_AKIVA_LANCAR = $TOTAL_AKIVA_LANCAR + $data_tbl_neraca_data->persediaan;
				} ?>
			</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<!-- PASIVA  -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="300"></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150"></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th>

		</tr>




		<tr>
			<!-- AKTIVA -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="300">Uang Muka Pajak</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
				<?php if (isset($data_tbl_neraca_data->uang_muka_pajak)) {
					// echo nominal($data_tbl_neraca_data->uang_muka_pajak);
					echo number_format($data_tbl_neraca_data->uang_muka_pajak, 2, ',', '.');
					$TOTAL_AKIVA_LANCAR = $TOTAL_AKIVA_LANCAR + $data_tbl_neraca_data->uang_muka_pajak;
				} ?>
			</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<!-- PASIVA  -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="300"></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150"></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th>

		</tr>





		<tr>
			<!-- AKTIVA -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="300">Total Aktiva Lancar</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150"><strong><?php 
			// echo nominal($TOTAL_AKIVA_LANCAR) 
			echo number_format($TOTAL_AKIVA_LANCAR, 2, ',', '.');
			?></strong></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<!-- PASIVA  -->
			<th style="font-size: 0.550em;text-align:left; width: 310px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="310"><strong>Utang Jangka Panjang</strong></th>

			<!-- <th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="10"></th> -->



			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150"></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th>

		</tr>


		<tr>
			<!-- AKTIVA -->


			<th style="font-size:0.550em; text-align:left; width: 10px;height: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="300"></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-right:none;border-left:none;border-top:none;border-bottom:none;  border-collapse: collapse;" colspan="20"></th>

			<th style="font-size:0.550em; text-align:right;  width: 150px;border: 1px solid black;border-right:none;border-left:none;border-top:none;border-bottom:none;  border-collapse: collapse;" colspan="150"></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<!-- PASIVA  -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="300"></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150"></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th>

		</tr>



		<tr>
			<!-- AKTIVA -->
			<th style="font-size: 0.550em; width: 310px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse; text-align:left;" colspan="310"><strong>Aktiva Tetap</strong></th>

			<!-- <th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="10"></th> -->



			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
				<?php if (isset($data_tbl_neraca_data->aktiva_tetap)) {
					// echo nominal($data_tbl_neraca_data->aktiva_tetap);
					echo number_format($data_tbl_neraca_data->aktiva_tetap, 2, ',', '.');
				} ?>
			</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<!-- PASIVA  -->


			<th style="font-size: 0.550em;text-align:left; width: 310px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="310"></th>

			<!-- <th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th> -->


			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<th style="font-size:0.550em; text-align:right; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150"></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th>

		</tr>






		<tr>
			<!-- AKTIVA -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="300">Aktiva Tetap Berwujud</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
				<?php if (isset($data_tbl_neraca_data->aktiva_tetap_berwujud)) {
					// echo nominal($data_tbl_neraca_data->aktiva_tetap_berwujud);
					echo number_format($data_tbl_neraca_data->aktiva_tetap_berwujud, 2, ',', '.');
					$Total_Aktiva_Tetap_Bersih = $Total_Aktiva_Tetap_Bersih + $data_tbl_neraca_data->aktiva_tetap_berwujud;
				} ?>
			</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<!-- PASIVA  -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="300">Utang Afiliasi</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-top:none;border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

			<th style="font-size:0.550em; text-align:right; width: 150px;border: 1px solid black;border-top:none;border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
				<?php if (isset($data_tbl_neraca_data->utang_afiliasi)) {
					// echo nominal($data_tbl_neraca_data->utang_afiliasi);
					echo number_format($data_tbl_neraca_data->utang_afiliasi, 2, ',', '.');
					$TOTAL_Utang_Jangka_Panjang = $TOTAL_Utang_Jangka_Panjang + $data_tbl_neraca_data->utang_afiliasi;
				} ?>
			</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th>

		</tr>





		<tr>
			<!-- AKTIVA -->

			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="300">Akumulasi Depresiasi ATB</th>


			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
				<?php if (isset($data_tbl_neraca_data->utang_afiliasi)) {
					// echo nominal($data_tbl_neraca_data->akumulasi_depresiasi_atb);
					echo number_format($data_tbl_neraca_data->akumulasi_depresiasi_atb, 2, ',', '.');
					$Total_Aktiva_Tetap_Bersih = $Total_Aktiva_Tetap_Bersih + $data_tbl_neraca_data->akumulasi_depresiasi_atb;
				} ?>
			</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<!-- PASIVA  -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="300">Total Utang</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

			<th style="font-size:0.550em; text-align:right; width: 150px;border: 1px solid black;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150"><strong><?php 
			// echo nominal($TOTAL_Utang_Lancar + $TOTAL_Utang_Jangka_Panjang) 
			echo number_format(($TOTAL_Utang_Lancar + $TOTAL_Utang_Jangka_Panjang), 2, ',', '.');
			?></strong></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none;  border-top:none;border-bottom:none;border-collapse: collapse;" colspan="20"></th>

		</tr>





		<tr>
			<!-- AKTIVA -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="300">Total Aktiva Tetap Bersih</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-right:none;border-left:none;  border-collapse: collapse;" colspan="150"><?php 
			// echo nominal($Total_Aktiva_Tetap_Bersih) 
			echo number_format($Total_Aktiva_Tetap_Bersih, 2, ',', '.');
			?></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<!-- PASIVA  -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="300"></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150"></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th>

		</tr>




		<tr>
			<!-- AKTIVA -->


			<th style="font-size:0.550em; text-align:left; width: 10px;height: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="300"></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-right:none;border-left:none;border-top:none;border-bottom:none;  border-collapse: collapse;" colspan="20"></th>

			<th style="font-size:0.550em; text-align:right;  width: 150px;border: 1px solid black;border-right:none;border-left:none;border-top:none;border-bottom:none;  border-collapse: collapse;" colspan="150"></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<!-- PASIVA  -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="300"></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150"></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th>

		</tr>









		<tr>
			<!-- AKTIVA -->


			<th style="font-size: 0.550em; width: 310px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;    border-collapse: collapse; text-align:left;" colspan="310"><strong>Aktiva Lain-Lain</strong></th>

			<!-- <th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none; border-collapse: collapse;" colspan="10"></th> -->



			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<th style="font-size:0.550em; text-align:right;  width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150"></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<!-- PASIVA  -->



			<th style="font-size: 0.550em;text-align:left; width: 310px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;   border-collapse: collapse;" colspan="310"><strong>Modal dan Laba ditahan</strong></th>


			<!-- <th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none; border-collapse: collapse;" colspan="10"></th> -->


			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150"></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th>

		</tr>






		<tr>
			<!-- AKTIVA -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="300">Piutang Non Usaha Pihak Ketiga</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
				<?php if (isset($data_tbl_neraca_data->piutang_non_usaha_pihak_ketiga)) {
					// echo nominal($data_tbl_neraca_data->piutang_non_usaha_pihak_ketiga);
					echo number_format($data_tbl_neraca_data->piutang_non_usaha_pihak_ketiga, 2, ',', '.');
					$Aktiva_Lain_Lain = $Aktiva_Lain_Lain + $data_tbl_neraca_data->piutang_non_usaha_pihak_ketiga;
				} ?>
			</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<!-- PASIVA  -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="300">Modal Dasar dan Penyertaan</th>


			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
				<?php if (isset($data_tbl_neraca_data->modal_dasar_dan_penyertaan)) {
					// echo nominal($data_tbl_neraca_data->modal_dasar_dan_penyertaan);
					echo number_format($data_tbl_neraca_data->modal_dasar_dan_penyertaan, 2, ',', '.');
					$TOTAL_Modal_dan_Laba_ditahan = $TOTAL_Modal_dan_Laba_ditahan + $data_tbl_neraca_data->modal_dasar_dan_penyertaan;
				} ?>
			</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th>

		</tr>







		<tr>
			<!-- AKTIVA -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="300">Piutang Non Usaha Radio</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
				<?php if (isset($data_tbl_neraca_data->piutang_non_usaha_radio)) {
					// echo nominal($data_tbl_neraca_data->piutang_non_usaha_radio);
					echo number_format($data_tbl_neraca_data->piutang_non_usaha_radio, 2, ',', '.');
					$Aktiva_Lain_Lain = $Aktiva_Lain_Lain + $data_tbl_neraca_data->piutang_non_usaha_radio;
				} ?>
			</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<!-- PASIVA  -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="300">Cadangan Umum</th>


			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
				<?php if (isset($data_tbl_neraca_data->cadangan_umum)) {
					// echo nominal($data_tbl_neraca_data->cadangan_umum);
					echo number_format($data_tbl_neraca_data->cadangan_umum, 2, ',', '.');
					$TOTAL_Modal_dan_Laba_ditahan = $TOTAL_Modal_dan_Laba_ditahan + $data_tbl_neraca_data->cadangan_umum;
				} ?>
			</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th>

		</tr>






		<tr>
			<!-- AKTIVA -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="300">ljpj-Taman Gedung Kesenian Gabusan</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
				<?php if (isset($data_tbl_neraca_data->ljpj_taman_gedung_kesenian_gabusan)) {
					// echo nominal($data_tbl_neraca_data->ljpj_taman_gedung_kesenian_gabusan);
					echo number_format($data_tbl_neraca_data->ljpj_taman_gedung_kesenian_gabusan, 2, ',', '.');
					$Aktiva_Lain_Lain = $Aktiva_Lain_Lain + $data_tbl_neraca_data->ljpj_taman_gedung_kesenian_gabusan;
				} ?>
			</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<!-- PASIVA  -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="300">Laba BUMD (PAD)</th>


			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
				<?php if (isset($data_tbl_neraca_data->laba_bumd_pad)) {
					// echo nominal($data_tbl_neraca_data->laba_bumd_pad);
					echo number_format($data_tbl_neraca_data->laba_bumd_pad, 2, ',', '.');
					$TOTAL_Modal_dan_Laba_ditahan = $TOTAL_Modal_dan_Laba_ditahan + $data_tbl_neraca_data->laba_bumd_pad;
				} ?>
			</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th>

		</tr>


		<tr>
			<!-- AKTIVA -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="300">ljpj-Kompleks Gedung Kesenian</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
				<?php if (isset($data_tbl_neraca_data->ljpj_kompleks_gedung_kesenian)) {
					// echo nominal($data_tbl_neraca_data->ljpj_kompleks_gedung_kesenian);
					echo number_format($data_tbl_neraca_data->ljpj_kompleks_gedung_kesenian, 2, ',', '.');
					$Aktiva_Lain_Lain = $Aktiva_Lain_Lain + $data_tbl_neraca_data->ljpj_kompleks_gedung_kesenian;
				} ?>
			</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<!-- PASIVA  -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="300"></th>


			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150"></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th>

		</tr>










		<tr>
			<!-- AKTIVA -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="300">ljpj-Radio</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
				<?php if (isset($data_tbl_neraca_data->ljpj_radio)) {
					// echo nominal($data_tbl_neraca_data->ljpj_radio);
					echo number_format($data_tbl_neraca_data->ljpj_radio, 2, ',', '.');
					$Aktiva_Lain_Lain = $Aktiva_Lain_Lain + $data_tbl_neraca_data->ljpj_radio;
				} ?>
			</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<!-- PASIVA  -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="300">Laba (Rugi) Tahun Lalu</th>


			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
				<?php if (isset($data_tbl_neraca_data->laba_rugi_tahun_lalu)) {
					// echo nominal($data_tbl_neraca_data->laba_rugi_tahun_lalu);
					echo number_format($data_tbl_neraca_data->laba_rugi_tahun_lalu, 2, ',', '.');
					$TOTAL_Modal_dan_Laba_ditahan = $TOTAL_Modal_dan_Laba_ditahan + $data_tbl_neraca_data->laba_rugi_tahun_lalu;
				} ?>
			</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th>

		</tr>






		<tr>
			<!-- AKTIVA -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="300">ljpj-Kerjasama Operasi Apotek Dharma Usaha</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
				<?php if (isset($data_tbl_neraca_data->ljpj_kerjasama_operasi_apotek_dharma_usaha)) {
					// echo nominal($data_tbl_neraca_data->ljpj_kerjasama_operasi_apotek_dharma_usaha);
					echo number_format($data_tbl_neraca_data->ljpj_kerjasama_operasi_apotek_dharma_usaha, 2, ',', '.');
					$Aktiva_Lain_Lain = $Aktiva_Lain_Lain + $data_tbl_neraca_data->ljpj_kerjasama_operasi_apotek_dharma_usaha;
				} ?>
			</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<!-- PASIVA  -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="300">Laba (Rugi) Tahun Berjalan</th>


			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
				<?php if (isset($data_tbl_neraca_data->laba_rugi_tahun_berjalan)) {
					// echo nominal($data_tbl_neraca_data->laba_rugi_tahun_berjalan);
					echo number_format($data_tbl_neraca_data->laba_rugi_tahun_berjalan, 2, ',', '.');
					$TOTAL_Modal_dan_Laba_ditahan = $TOTAL_Modal_dan_Laba_ditahan + $data_tbl_neraca_data->laba_rugi_tahun_berjalan;
				} ?>
			</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th>

		</tr>







		<tr>
			<!-- AKTIVA -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="300">ljpj-Peternakan</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
				<?php if (isset($data_tbl_neraca_data->ljpj_peternakan)) {
					// echo nominal($data_tbl_neraca_data->ljpj_peternakan);
					echo number_format($data_tbl_neraca_data->ljpj_peternakan, 2, ',', '.');
					$Aktiva_Lain_Lain = $Aktiva_Lain_Lain + $data_tbl_neraca_data->ljpj_peternakan;
				} ?>
			</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<!-- PASIVA  -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="300"></th>


			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150"></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th>

		</tr>


		<tr>
			<!-- AKTIVA -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="300">ljpj-Kerjasama ADWM</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
				<?php if (isset($data_tbl_neraca_data->ljpj_kerjasama_adwm)) {
					// echo nominal($data_tbl_neraca_data->ljpj_kerjasama_adwm);
					echo number_format($data_tbl_neraca_data->ljpj_kerjasama_adwm, 2, ',', '.');
					$Aktiva_Lain_Lain = $Aktiva_Lain_Lain + $data_tbl_neraca_data->ljpj_kerjasama_adwm;
				} ?>
			</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<!-- PASIVA  -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="300"></th>


			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150"></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th>

		</tr>




		<tr>
			<!-- AKTIVA -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="300">ljpj-Kerjasama PDU Cabean Panggungharjo</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
				<?php if (isset($data_tbl_neraca_data->ljpj_kerjasama_pdu_cabean_panggungharjo)) {
					// echo nominal($data_tbl_neraca_data->ljpj_kerjasama_pdu_cabean_panggungharjo);
					echo number_format($data_tbl_neraca_data->ljpj_kerjasama_pdu_cabean_panggungharjo, 2, ',', '.');
					$Aktiva_Lain_Lain = $Aktiva_Lain_Lain + $data_tbl_neraca_data->ljpj_kerjasama_pdu_cabean_panggungharjo;
				} ?>
			</th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<!-- PASIVA  -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="300"></th>


			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150"></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th>

		</tr>




		<tr>
			<!-- AKTIVA -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="300"></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

			<th style="font-size:0.550em; text-align:right;  width: 150px;border: 1px solid black; border-right:none;border-left:none;  border-collapse: collapse;" colspan="150"><strong><?php 
			// echo nominal($Aktiva_Lain_Lain) 
			echo number_format($Aktiva_Lain_Lain, 2, ',', '.');
			?></strong></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<!-- PASIVA  -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="300"></th>


			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

			<th style="font-size:0.550em; text-align:right; width: 150px;border: 1px solid black; border-right:none;border-left:none;  border-collapse: collapse;" colspan="150"><?php 
			// echo nominal($TOTAL_Modal_dan_Laba_ditahan) 
			echo number_format($TOTAL_Modal_dan_Laba_ditahan, 2, ',', '.');
			?></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th>

		</tr>





		<tr>
			<!-- AKTIVA -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="300"></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<th style="font-size:0.550em; text-align:right;  width: 150px;border: 1px solid black; border-right:none;border-left:none;  border-collapse: collapse;" colspan="150"></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<!-- PASIVA  -->


			<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

			<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="300"></th>


			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<th style="font-size:0.550em; text-align:right; width: 150px;border: 1px solid black; border-right:none;border-left:none;  border-collapse: collapse;" colspan="150"></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th>

		</tr>









		<tr>
			<!-- AKTIVA -->

			<th style="font-size: 0.550em; width: 310px;border: 1px solid black;border-top:none; border-bottom:none;border-right:none;  border-collapse: collapse; text-align:left;" colspan="310"><strong>TOTAL AKTIVA</strong></th>

			<!-- <th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="10"></th> -->



			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

			<th style="font-size:0.550em; text-align:right; border-top:none;width: 150px;border: 1px solid black;border-right:none;border-left:none;  border-collapse: collapse;" colspan="150"><strong><?php 
			// echo nominal($TOTAL_AKIVA_LANCAR + $Total_Aktiva_Tetap_Bersih + $Aktiva_Lain_Lain) 
			echo number_format($TOTAL_AKIVA_LANCAR + $Total_Aktiva_Tetap_Bersih + $Aktiva_Lain_Lain, 2, ',', '.');
			?></strong></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-bottom:none;border-top:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>




			<!-- PASIVA  -->
			<th style="font-size: 0.550em;text-align:left; width: 310px;border: 1px solid black;border-bottom:none;border-top:none; border-right:none;  border-collapse: collapse;" colspan="310"><strong>TOTAL PASIVA</strong></th>

			<!-- <th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="10"></th> -->




			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

			<th style="font-size:0.550em; text-align:right; width: 150px;border: 1px solid black; border-right:none;border-left:none;  border-collapse: collapse;" colspan="150"><strong><?php 
			// echo nominal($TOTAL_Utang_Lancar + $TOTAL_Utang_Jangka_Panjang + $TOTAL_Modal_dan_Laba_ditahan) 
			echo number_format(($TOTAL_Utang_Lancar + $TOTAL_Utang_Jangka_Panjang + $TOTAL_Modal_dan_Laba_ditahan), 2, ',', '.');
			?></strong></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-bottom:none;  border-left:none; border-top:none;border-collapse: collapse;" colspan="20"></th>

		</tr>










		<tr>
			<!-- AKTIVA -->

			<th style="font-size: 0.550em; width: 310px;border: 1px solid black;border-top:none;border-bottom:none; border-right:none;  border-collapse: collapse; text-align:left;" colspan="310"><strong></strong></th>

			<!-- <th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="10"></th> -->



			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<th style="font-size:0.550em; text-align:right; border-top:none;width: 150px;border: 1px solid black;border-right:none;border-left:none;  border-collapse: collapse;" colspan="150"></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none; border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>




			<!-- PASIVA  -->
			<th style="font-size: 0.550em;text-align:left; width: 310px;border: 1px solid black;border-top:none;border-bottom:none; border-right:none;  border-collapse: collapse;" colspan="310"></th>

			<!-- <th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="10"></th> -->




			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<th style="font-size:0.550em; text-align:right; width: 150px;border: 1px solid black; border-right:none;border-left:none;  border-collapse: collapse;" colspan="150"></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th>

		</tr>





		<!-- Jarak dengan garis kotak paling bawah -->

		<tr>
			<!-- AKTIVA -->

			<th style="font-size: 0.550em; width: 310px;height: 10px; border: 1px solid black;border-top:none; border-right:none;  border-collapse: collapse; text-align:left;" colspan="310"><strong></strong></th>

			<!-- <th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="10"></th> -->



			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<th style="font-size:0.550em; text-align:right; border-top:none;width: 150px;border: 1px solid black;border-right:none;border-left:none;  border-collapse: collapse;" colspan="150"></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>




			<!-- PASIVA  -->
			<th style="font-size: 0.550em;text-align:left; width: 310px;border: 1px solid black;border-top:none; border-right:none;  border-collapse: collapse;" colspan="310"></th>

			<!-- <th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="10"></th> -->




			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

			<th style="font-size:0.550em; text-align:right; width: 150px;border: 1px solid black; border-right:none;border-left:none;  border-collapse: collapse;" colspan="150"></th>

			<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-collapse: collapse;" colspan="20"></th>

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