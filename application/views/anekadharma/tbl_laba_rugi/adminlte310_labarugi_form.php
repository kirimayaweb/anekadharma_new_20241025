<form action="<?php echo $action; ?>" method="post">


	<div class="content-wrapper">

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


			<style>
				input[type=text] {
					width: 90%;
					padding: 12px 16px;
					height: 12px;
					margin: 4px 0;
					box-sizing: border-box;
					border: none;
					background-color: #3CBC8D;
					color: white;
				}
			</style>

		</head>

		<!-- <body> -->






		<div class="card-header">

			<div class="row">
				<!-- <div class="col-12" text-align="center"> <strong><label for="nmrsj">Jumlah Pembayaran </label></strong></div> -->




				<div class="form-group">

					<div class="row">
						<div class="col-1">
							<!-- <a href="<?php //echo site_url('tbl_pembelian/') 
											?>" class="btn btn-primary">Lanjut Transaksi</a> -->
						</div>

						<div class="col-8">


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


											if ($bulan_transaksi > 0) {
												echo " " . bulan_teks($bulan_transaksi);
											} else {
											}

											?>
											Tahun <?php
													// echo  date('d F Y'); 
													//echo  date('d F Y'); 
													echo $tahun_neraca;
													?></strong>


									</th>

								</tr>


								<!-- Untuk setting form pertama agar form kedua/penjualan bisa terbaca di controller -->
								<form action="<?php echo $action . '/penjualanX'; ?>" method="post">

									<!-- <input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" /> -->

									<!-- <button type="submit" class="btn btn-success btn-xs">Simpan </button> -->
								</form>
								<!-- //Untuk setting form pertama agar form kedua/penjualan bisa terbaca di controller -->



								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400">PENJUALAN</th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 200px;" colspan="200">

										<form action="<?php echo $action . '/penjualan'; ?>" method="post">

											<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php echo str_replace('.', ',', $data_tbl_laba_rugi->penjualan);  ?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

											<button type="submit" class="btn btn-success btn-xs">Simpan </button>
										</form>

									</th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

								</tr>





								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400">BEBAN POKOK PENJUALAN</th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200">

										<form action="<?php echo $action . '/beban_pokok_penjualan'; ?>" method="post">

											<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php echo str_replace('.', ',', $data_tbl_laba_rugi->beban_pokok_penjualan) ; ?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

											<button type="submit" class="btn btn-success btn-xs">Simpan </button>
										</form>


									</th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

								</tr>





								<tr>

									<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 400px;" colspan="400">LABA RUGI BRUTO</th>

									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black; border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse; width: 200px;" colspan="200">

										<!-- <form action="<?php //echo $action . '/laba_rugi_bruto'; ?>" method="post"> -->

											<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php echo str_replace('.', ',', $data_tbl_laba_rugi->penjualan - $data_tbl_laba_rugi->beban_pokok_penjualan) ; ?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:red;" />

											<!-- <button type="submit" class="btn btn-success btn-xs">Simpan </button>
										</form> -->


									</th>
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
									<th style="font-size: 0.550em;text-align:left; width: 325px;" colspan="325">Beban Depresiasi dan Amortisasi</th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200">

										<form action="<?php echo $action . '/beban_depresiasi_dan_amortisasi'; ?>" method="post">

											<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php echo str_replace('.', ',', $data_tbl_laba_rugi->beban_depresiasi_dan_amortisasi); ?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

											<button type="submit" class="btn btn-success btn-xs">Simpan </button>
										</form>

									</th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

								</tr>

								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 75px;" colspan="75"></th>
									<th style="font-size: 0.550em;text-align:left; width: 325px;" colspan="325">Beban Operasional Karyawan</th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200">

										<form action="<?php echo $action . '/beban_operasional_karyawan'; ?>" method="post">

											<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php echo str_replace('.', ',', $data_tbl_laba_rugi->beban_operasional_karyawan); ?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

											<button type="submit" class="btn btn-success btn-xs">Simpan </button>
										</form>

									</th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

								</tr>

								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 75px;" colspan="75"></th>
									<th style="font-size: 0.550em;text-align:left; width: 325px;" colspan="325">Beban Operasional Promosi</th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200">

										<form action="<?php echo $action . '/beban_operasional_promosi'; ?>" method="post">

											<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php echo str_replace('.', ',', $data_tbl_laba_rugi->beban_operasional_promosi); ?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

											<button type="submit" class="btn btn-success btn-xs">Simpan </button>
										</form>

									</th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

								</tr>







								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 75px;" colspan="75"></th>
									<th style="font-size: 0.550em;text-align:left; width: 325px;" colspan="325">Beban Perjalanan Dinas</th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200">

										<form action="<?php echo $action . '/beban_perjalanan_dinas'; ?>" method="post">

											<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php echo str_replace('.', ',', $data_tbl_laba_rugi->beban_perjalanan_dinas); ?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

											<button type="submit" class="btn btn-success btn-xs">Simpan </button>
										</form>

									</th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

								</tr>



								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 75px;" colspan="75"></th>
									<th style="font-size: 0.550em;text-align:left; width: 325px;" colspan="325">Beban Transportasi</th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200">

										<form action="<?php echo $action . '/beban_transportasi'; ?>" method="post">

											<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php echo str_replace('.', ',', $data_tbl_laba_rugi->beban_transportasi); ?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

											<button type="submit" class="btn btn-success btn-xs">Simpan </button>
										</form>

									</th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

								</tr>





								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 75px;" colspan="75"></th>
									<th style="font-size: 0.550em;text-align:left; width: 325px;" colspan="325">Beban Pemeliharaan</th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200">


										<form action="<?php echo $action . '/beban_pemeliharaan'; ?>" method="post">

											<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php echo str_replace('.', ',', $data_tbl_laba_rugi->beban_pemeliharaan); ?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

											<button type="submit" class="btn btn-success btn-xs">Simpan </button>
										</form>

									</th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

								</tr>








								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 75px;" colspan="75"></th>
									<th style="font-size: 0.550em;text-align:left; width: 325px;" colspan="325">Total Beban Operaisonal umum</th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200">


										<form action="<?php echo $action . '/total_beban_operasional_umum'; ?>" method="post">

											<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php echo str_replace('.', ',', $data_tbl_laba_rugi->total_beban_operasional_umum); ?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

											<button type="submit" class="btn btn-success btn-xs">Simpan </button>
										</form>

									</th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

								</tr>







								<tr>

									<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 400px;" colspan="400">Total Beban Operasional</th>

									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200">

										<!-- <form action="<?php //echo $action . '/utang_lain_lain'; 
															?>" method="post"> -->

										<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php echo str_replace('.', ',', $data_tbl_laba_rugi->beban_depresiasi_dan_amortisasi+$data_tbl_laba_rugi->beban_operasional_karyawan+$data_tbl_laba_rugi->beban_operasional_promosi+$data_tbl_laba_rugi->beban_perjalanan_dinas+$data_tbl_laba_rugi->beban_transportasi+$data_tbl_laba_rugi->beban_pemeliharaan+$data_tbl_laba_rugi->total_beban_operasional_umum); ?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:red;" />

										<!-- <button type="submit" class="btn btn-success btn-xs">Simpan </button> -->
										<!-- </form> -->

									</th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

								</tr>



								<tr>

									<th style="font-size: 0.550em;text-align:left;border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 400px;" colspan="400">Laba / Rugi Operasional</th>

									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200">

										<!-- <form action="<?php //echo $action . '/utang_lain_lain'; 
															?>" method="post"> -->

										<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php 
										
										echo str_replace('.', ',',($data_tbl_laba_rugi->penjualan - $data_tbl_laba_rugi->beban_pokok_penjualan)-( $data_tbl_laba_rugi->beban_depresiasi_dan_amortisasi+$data_tbl_laba_rugi->beban_operasional_karyawan+$data_tbl_laba_rugi->beban_operasional_promosi+$data_tbl_laba_rugi->beban_perjalanan_dinas+$data_tbl_laba_rugi->beban_transportasi+$data_tbl_laba_rugi->beban_pemeliharaan+$data_tbl_laba_rugi->total_beban_operasional_umum)); 
										
										$GET_Labar_rugi_operasional=($data_tbl_laba_rugi->penjualan - $data_tbl_laba_rugi->beban_pokok_penjualan)-( $data_tbl_laba_rugi->beban_depresiasi_dan_amortisasi+$data_tbl_laba_rugi->beban_operasional_karyawan+$data_tbl_laba_rugi->beban_operasional_promosi+$data_tbl_laba_rugi->beban_perjalanan_dinas+$data_tbl_laba_rugi->beban_transportasi+$data_tbl_laba_rugi->beban_pemeliharaan+$data_tbl_laba_rugi->total_beban_operasional_umum);
										?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:red;" />

										<!-- <button type="submit" class="btn btn-success btn-xs">Simpan </button> -->
										<!-- </form> -->

									</th>
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
									<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200">

										<form action="<?php echo $action . '/pendapatan_bunga_bank'; ?>" method="post">

											<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php echo str_replace('.', ',', $data_tbl_laba_rugi->pendapatan_bunga_bank); ?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

											<button type="submit" class="btn btn-success btn-xs">Simpan </button>
										</form>


									</th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

								</tr>





								<tr>

									<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 75px;" colspan="75"></th>
									<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 325px;" colspan="325">Pendapatan Rupa-Rupa</th>

									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200">

										<form action="<?php echo $action . '/pendapatan_rupa_rupa'; ?>" method="post">

											<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php echo str_replace('.', ',', $data_tbl_laba_rugi->pendapatan_rupa_rupa); ?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

											<button type="submit" class="btn btn-success btn-xs">Simpan </button>
										</form>


									</th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

								</tr>




								<tr>

									<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 75px;" colspan="75"></th>
									<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 325px;" colspan="325"><strong>Total Pendapatan Lain-lain</strong></th>

									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200">

										<!-- <form action="<?php //echo $action . '/utang_lain_lain'; 
															?>" method="post"> -->

										<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php 
										
										echo str_replace('.', ',', $data_tbl_laba_rugi->pendapatan_bunga_bank - $data_tbl_laba_rugi->pendapatan_rupa_rupa); 
										
										$GET_Total_Pendapatan_Lain_Lain=$data_tbl_laba_rugi->pendapatan_bunga_bank - $data_tbl_laba_rugi->pendapatan_rupa_rupa;
										?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:red;" />

										<!-- <button type="submit" class="btn btn-success btn-xs">Simpan </button> -->
										<!-- </form> -->

									</th>
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
									<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 325px;" colspan="325">Beban Bunga dan ADM Bank</th>

									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200">

										<form action="<?php echo $action . '/beban_bunga_dan_adm_bank'; ?>" method="post">

											<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php echo str_replace('.', ',', $data_tbl_laba_rugi->beban_bunga_dan_adm_bank); ?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

											<button type="submit" class="btn btn-success btn-xs">Simpan </button>
										</form>

									</th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

								</tr>





								<tr>

									<th style="font-size: 0.550em;text-align:left;border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 75px;" colspan="75"></th>
									<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 325px;" colspan="325">Beban Rupa-Rupa</th>

									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200">

										<form action="<?php echo $action . '/beban_rupa_rupa'; ?>" method="post">

											<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php echo str_replace('.', ',', $data_tbl_laba_rugi->beban_rupa_rupa); ?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

											<button type="submit" class="btn btn-success btn-xs">Simpan </button>
										</form>

									</th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

								</tr>





								<tr>

									<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 75px;" colspan="75"></th>
									<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 325px;" colspan="325"><strong>Total beban Lain-Lain</strong></th>

									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200">

										<!-- <form action="<?php //echo $action . '/utang_lain_lain'; 
															?>" method="post"> -->

										<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php 
										
										echo str_replace('.', ',', $data_tbl_laba_rugi->beban_bunga_dan_adm_bank + $data_tbl_laba_rugi->beban_rupa_rupa); 
										
										$GET_Total_beban_lain_lain=$data_tbl_laba_rugi->beban_bunga_dan_adm_bank + $data_tbl_laba_rugi->beban_rupa_rupa;
										?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:red;" />

										<!-- <button type="submit" class="btn btn-success btn-xs">Simpan </button> -->
										<!-- </form> -->

									</th>
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
									<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200">

										<!-- <form action="<?php //echo $action . '/utang_lain_lain'; 
															?>" method="post"> -->

										<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php 
										
										echo str_replace('.', ',', $GET_Labar_rugi_operasional + $GET_Total_Pendapatan_Lain_Lain - $GET_Total_beban_lain_lain); 
										
										$GET_Laba_rugi_sebelum_pajak=$GET_Labar_rugi_operasional + $GET_Total_Pendapatan_Lain_Lain - $GET_Total_beban_lain_lain;
										?> " style="font-size:1.1vw;font-weight: bold;text-align:right;color:red;" />

										<!-- <button type="submit" class="btn btn-success btn-xs">Simpan </button> -->
										<!-- </form> -->

									</th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

								</tr>





								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400">PAJAK</th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200">

										<form action="<?php echo $action . '/pajak'; ?>" method="post">

											<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php echo str_replace('.', ',', $data_tbl_laba_rugi->pajak); ?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

											<button type="submit" class="btn btn-success btn-xs">Simpan </button>
										</form>

									</th>
									<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

								</tr>





								<tr>

									<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 400px;" colspan="400">LABA RUGI SETELAH PAJAK</th>

									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 10px;" colspan="10"></th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 20px;" colspan="20">Rp.</th>
									<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 200px;" colspan="200">

										<!-- <form action="<?php //echo $action . '/utang_lain_lain'; 
															?>" method="post"> -->

										<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php echo str_replace('.', ',', $GET_Laba_rugi_sebelum_pajak - $data_tbl_laba_rugi->pajak );?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:red;" />

										<!-- <button type="submit" class="btn btn-success btn-xs">Simpan </button> -->
										<!-- </form> -->

									</th>
									<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 20px;" colspan="20"></th>

								</tr>




								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400"></th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
									<th style="font-size:0.550em; text-align:center; width: 240px;" colspan="240">Bantul,
										<?php

										if ($bulan_transaksi > 0) {
											echo " " . bulan_teks($bulan_transaksi);
										} else {
										}

										echo " " . $tahun_neraca;
										?></strong>


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



								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400"></th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
									<th style="font-size:0.550em; text-align:center; width: 240px;" colspan="240"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

								</tr>


								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400"></th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
									<th style="font-size:0.550em; text-align:center; width: 240px;" colspan="240"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

								</tr>


								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400"></th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
									<th style="font-size:0.550em; text-align:center; width: 240px;" colspan="240"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

								</tr>


								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400"></th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
									<th style="font-size:0.550em; text-align:center; width: 240px;" colspan="240"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

								</tr>


								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400"></th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
									<th style="font-size:0.550em; text-align:center; width: 240px;" colspan="240"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

								</tr>


								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400"></th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
									<th style="font-size:0.550em; text-align:center; width: 240px;" colspan="240"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

								</tr>


								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400"></th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
									<th style="font-size:0.550em; text-align:center; width: 240px;" colspan="240"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

								</tr>


								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400"></th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
									<th style="font-size:0.550em; text-align:center; width: 240px;" colspan="240"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

								</tr>


								<tr>

									<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400"></th>

									<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
									<th style="font-size:0.550em; text-align:center; width: 240px;" colspan="240">Yuli Budi Sasangka,ST</th>
									<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

								</tr>





							</table>



						</div>
						<div class="col-1"></div>
					</div>

				</div>


			</div>

		</div>








		<!-- <div class="card card-success"> -->
		<div class="card-header">

			<div class="row">
				<!-- <div class="col-12" text-align="center"> <strong><label for="nmrsj">Jumlah Pembayaran </label></strong></div> -->




				<div class="form-group">

					<div class="row">
						<div class="col-4">
							<!-- <a href="<?php //echo site_url('tbl_pembelian/') 
											?>" class="btn btn-primary">Lanjut Transaksi</a> -->
							<!-- <a href="<?php //echo site_url('tbl_pembelian') 
											?>" class="btn btn-default">Cancel</a> -->
						</div>

						<div class="col-4">


							<!-- <a href="<?php //echo site_url('tbl_pembelian/cetak_pengajuan_bayar_per_spop/' . $uuid_pengajuan_bayar_terproses)
											?>" class="btn btn-success" target="_blank">Cetak Pengajuan Pembayaran (PDF)</a>  -->

							<!-- <button type="submit" class="btn btn-primary">SIMPAN</button> -->

						</div>
						<div class="col-4"></div>
					</div>

				</div>


			</div>

		</div>
	</div>


</form>

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


<!-- </body> -->