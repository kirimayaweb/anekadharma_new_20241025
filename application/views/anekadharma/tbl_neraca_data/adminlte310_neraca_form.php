<script language="javascript">
	function getkey(e) {
		if (window.event)
			return window.event.keyCode;
		else if (e)
			return e.which;
		else
			return null;
	}

	function goodchars(e, goods, field) {
		var key, keychar;
		key = getkey(e);
		if (key == null) return true;

		keychar = String.fromCharCode(key);
		keychar = keychar.toLowerCase();
		goods = goods.toLowerCase();

		// check goodkeys
		if (goods.indexOf(keychar) != -1)
			return true;
		// control keys
		if (key == null || key == 0 || key == 8 || key == 9 || key == 27)
			return true;

		if (key == 13) {
			var i;
			for (i = 0; i < field.form.elements.length; i++)
				if (field == field.form.elements[i])
					break;
			i = (i + 1) % field.form.elements.length;
			field.form.elements[i].focus();
			return false;
		};



		// else return false
		return false;
	}
</script>


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




		<?php
		$TOTAL_AKIVA_LANCAR = 0;
		$Total_Aktiva_Tetap_Bersih = 0;
		$Aktiva_Lain_Lain = 0;
		$TOTAL_Utang_Lancar = 0;
		$TOTAL_Utang_Jangka_Panjang = 0;
		$TOTAL_Modal_dan_Laba_ditahan = 0;

		?>

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
										<strong>NERACA</strong>
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

										<!-- <input type="text" style="text-align:right;" name="kas" id="kas" placeholder="kas" value="<?php
																																		//if (isset($data_detail->kas)) { echo nominal($data_detail->kas); $TOTAL_AKIVA_LANCAR = $TOTAL_AKIVA_LANCAR + $data_detail->kas;} 
																																		?>"> -->



										<!-- <script type="text/javascript">
										function checkInput(e, chars, field) {
											let teks = field.value;

											let teksSplit = teks.split("");
											let teksOke = [];

											for (let i = 0; i < teksSplit.length; i++) {
												if (chars.indexOf(teksSplit[i]) != -1) {
													teksOke.push(teksSplit[i]);
												}
											}

											field.value = teksOke.join("");
										}
									</script> -->

										<!-- <input type="number"  class="form-control" onkeyup="sum_total_aktiva_lancar();" name="kas" id="kas" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php //if (isset($data_detail->kas)) { echo nominal($data_detail->kas); $TOTAL_AKIVA_LANCAR = $TOTAL_AKIVA_LANCAR + $data_detail->kas; } 
																																																											?>" ; /> -->


										<!-- <input type="tel" pattern="[0-9(,)]{15}" class="form-control" onkeyup="TEST_sum_total_aktiva_lancar();" name="kas" id="kas" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php //if (isset($data_detail->kas)) {
																																																																	//echo nominal($data_detail->kas);
																																																																	//$TOTAL_AKIVA_LANCAR = $TOTAL_AKIVA_LANCAR + $data_detail->kas;
																																																																	//} 
																																																																	?>" ; /> -->



										<!-- <input type="tel" pattern="[0-9(,)]{15}" onkeyup="TEST_sum_total_aktiva_lancar();" name="kas" id="kas" onKeyPress="return goodchars(event,'0123456789.,-',this)" min="0" max="10" step="0,25" value="" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" /> -->

										<input type="tel" pattern="[0-9(,)]{15}" onkeyup="sum_total_aktiva_lancar();" name="kas" id="kas" onKeyPress="return goodchars(event,'0123456789.,-',this)" min="0" max="10" step="0,25" value="" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />


									</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-left:none;  border-collapse: collapse;" colspan="20"></th>

									<!-- PASIVA  -->


									<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

									<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="300">Utang Usaha</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

									<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">

										<!-- <input type="text" style="text-align:right;" name="utang_usaha" id="utang_usaha" placeholder="utang usaha" value="<?php
																																								// if (isset($data_detail->utang_usaha)) {
																																								// 	echo nominal($data_detail->utang_usaha);
																																								// 	$TOTAL_Utang_Lancar = $TOTAL_Utang_Lancar + $data_detail->utang_usaha;
																																								// } 
																																								?>"> -->


										<input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="utang_usaha" id="utang_usaha" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php if (isset($data_detail->utang_usaha)) {
																																																															echo nominal($data_detail->utang_usaha);
																																																															$TOTAL_Utang_Lancar = $TOTAL_Utang_Lancar + $data_detail->utang_usaha;
																																																														} ?>" ; />

									</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th>

								</tr>





								<tr>
									<!-- AKTIVA -->


									<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

									<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="300">Bank</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

									<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">

										<!-- <input type="text" style="text-align:right;" name="bank" id="bank" placeholder="bank" value="<?php
																																			// if (isset($data_detail->bank)) {
																																			// 																									echo nominal($data_detail->bank);
																																			// 																									$TOTAL_AKIVA_LANCAR = $TOTAL_AKIVA_LANCAR + $data_detail->bank;
																																			// 																								} 
																																			?>"> -->


										<!-- <input type="tel" pattern="[0-9()]{15}" class="form-control" onkeyup="TEST_sum_total_aktiva_lancar();" name="bank" id="bank" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php //if (isset($data_detail->bank)) {
																																																																	//echo nominal($data_detail->bank);
																																																																	//$TOTAL_AKIVA_LANCAR = $TOTAL_AKIVA_LANCAR + $data_detail->bank;
																																																																	//} 
																																																																	?>" ; /> -->

										<input type="tel" pattern="[0-9(,)]{15}" onkeyup="TEST_sum_total_aktiva_lancar();" name="bank" id="bank" onKeyPress="return goodchars(event,'0123456789.,-',this)" min="0" max="10" step="0,25" value="" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />


									</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

									<!-- PASIVA  -->


									<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

									<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="300">Utang Pajak</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

									<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">


										<!-- <input type="text" style="text-align:right;" name="utang_pajak" id="utang_pajak" placeholder="utang pajak" value="<?php
																																								//  (isset($data_detail->utang_pajak)) {
																																								// 																														echo nominal($data_detail->utang_pajak);
																																								// 																														$TOTAL_Utang_Lancar = $TOTAL_Utang_Lancar + $data_detail->utang_pajak;
																																								// 																													} 
																																								?>"> -->


										<input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="utang_pajak" id="utang_pajak" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php if (isset($data_detail->utang_pajak)) {
																																																															echo nominal($data_detail->utang_pajak);
																																																															$TOTAL_Utang_Lancar = $TOTAL_Utang_Lancar + $data_detail->utang_pajak;
																																																														} ?>" ; />

									</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th>

								</tr>



								<tr>
									<!-- AKTIVA -->


									<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

									<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="300">Piutang Usaha</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

									<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">


										<!-- <input type="text" style="text-align:right;" name="piutang_usaha" id="piutang_usaha" placeholder="piutang usaha" value="<?php
																																										// if (isset($data_detail->piutang_usaha)) {
																																										// 																															echo nominal($data_detail->piutang_usaha);
																																										// 																															$TOTAL_AKIVA_LANCAR = $TOTAL_AKIVA_LANCAR + $data_detail->piutang_usaha;
																																										// 																														} 
																																										?>"> -->


										<input type="text" class="form-control uang" onkeyup="sum_total_aktiva_lancar();" name="piutang_usaha" id="piutang_usaha" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php if (isset($data_detail->piutang_usaha)) {
																																																																echo nominal($data_detail->piutang_usaha);
																																																																$TOTAL_AKIVA_LANCAR = $TOTAL_AKIVA_LANCAR + $data_detail->piutang_usaha;
																																																															} ?>" ; />





									</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

									<!-- PASIVA  -->


									<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

									<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="300">Utang Lain-lain</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

									<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
										<strong>

											<!-- <input type="text" style="text-align:right;" name="utang_lain_lain" id="utang_lain_lain" placeholder="utang lain-lain" value="<?php
																																												// if (isset($data_detail->utang_lain_lain)) {
																																												// 																																	echo nominal($data_detail->utang_lain_lain);
																																												// 																																	$TOTAL_Utang_Lancar = $TOTAL_Utang_Lancar + $data_detail->utang_lain_lain;
																																												// 																																}  
																																												?>"> -->


											<input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="utang_lain_lain" id="utang_lain_lain" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php if (isset($data_detail->utang_lain_lain)) {
																																																																		echo nominal($data_detail->utang_lain_lain);
																																																																		$TOTAL_Utang_Lancar = $TOTAL_Utang_Lancar + $data_detail->utang_lain_lain;
																																																																	} ?>" ; />

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

										<!-- <input type="text" style="text-align:right;" name="piutang_non_usaha" id="piutang_non_usaha" placeholder="piutang non usaha" value="<?php
																																													// if (isset($data_detail->piutang_non_usaha)) {
																																													// 																																		echo nominal($data_detail->piutang_non_usaha);
																																													// 																																		$TOTAL_AKIVA_LANCAR = $TOTAL_AKIVA_LANCAR + $data_detail->piutang_non_usaha;
																																													// 																																	} 
																																													?>"> -->


										<input type="text" class="form-control uang" onkeyup="sum_total_aktiva_lancar();" name="piutang_non_usaha" id="piutang_non_usaha" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php if (isset($data_detail->piutang_non_usaha)) {
																																																																		echo nominal($data_detail->piutang_non_usaha);
																																																																		$TOTAL_AKIVA_LANCAR = $TOTAL_AKIVA_LANCAR + $data_detail->piutang_non_usaha;
																																																																	} ?>" ; />

									</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

									<!-- PASIVA  -->


									<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

									<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="300"></th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

									<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
										<?php //echo nominal($TOTAL_Utang_Lancar) 
										?>

										<input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="total_utang_lancar" id="total_utang_lancar" placeholder="" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" value="<?php
																																																																			if (isset($TOTAL_Utang_Lancar)) {

																																																																				echo $TOTAL_Utang_Lancar;

																																																																				// $Total_Aktiva_Tetap_Bersih = $Total_Aktiva_Tetap_Bersih + $data_detail->uang_muka_pajak; 

																																																																			}
																																																																			?>>" ; />


									</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th>

								</tr>







								<tr>
									<!-- AKTIVA -->


									<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

									<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="300">Persediaan</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

									<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">

										<!-- <input type="text" style="text-align:right;" name="persediaan" id="persediaan" placeholder="persediaan" value="<?php
																																							// if (isset($data_detail->persediaan)) {
																																							// 																													echo nominal($data_detail->persediaan);
																																							// 																													$TOTAL_AKIVA_LANCAR = $TOTAL_AKIVA_LANCAR + $data_detail->persediaan;
																																							// 																												} 
																																							?>"> -->

										<input type="text" class="form-control uang" onkeyup="sum_total_aktiva_lancar();" name="persediaan" id="persediaan" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php
																																																														if (isset($data_detail->persediaan)) {
																																																															echo nominal($data_detail->persediaan);
																																																															$TOTAL_AKIVA_LANCAR = $TOTAL_AKIVA_LANCAR + $data_detail->persediaan;
																																																														} ?>>" ; />

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

										<!-- <input type="text" style="text-align:right;" name="uang_muka_pajak" id="uang_muka_pajak" placeholder="uang muka pajak" value="<?php
																																											// if (isset($data_detail->uang_muka_pajak)) {
																																											// 																																	echo nominal($data_detail->uang_muka_pajak);
																																											// 																																	$TOTAL_AKIVA_LANCAR = $TOTAL_AKIVA_LANCAR + $data_detail->uang_muka_pajak;
																																											// 																																} 
																																											?>"> -->

										<input type="text" class="form-control uang" onkeyup="sum_total_aktiva_lancar();" name="uang_muka_pajak" id="uang_muka_pajak" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php
																																																																if (isset($data_detail->uang_muka_pajak)) {
																																																																	echo nominal($data_detail->uang_muka_pajak);
																																																																	$TOTAL_AKIVA_LANCAR = $TOTAL_AKIVA_LANCAR + $data_detail->uang_muka_pajak;
																																																																} ?>>" ; />


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

									<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
										<strong><?php //echo nominal($TOTAL_AKIVA_LANCAR) 
												?></strong>

										<!-- <input type="text" class="form-control uang" onkeyup="sum();" name="total_aktiva_lancar" id="total_aktiva_lancar" placeholder="" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" value="<?php //if (isset($TOTAL_AKIVA_LANCAR)) { if (isset($data_detail->uang_muka_pajak)) { $TOTAL_AKIVA_LANCAR = $TOTAL_AKIVA_LANCAR + $data_detail->uang_muka_pajak; echo nominal($TOTAL_AKIVA_LANCAR); } } elseif (isset($data_detail->uang_muka_pajak)) { $TOTAL_AKIVA_LANCAR = $data_detail->uang_muka_pajak; echo nominal($TOTAL_AKIVA_LANCAR);}	
																																																																?>>" ; /> -->

										<input type="tel" pattern="[0-9(,)]{15}" name="total_aktiva_lancar" id="total_aktiva_lancar" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="0,00" style="font-size:1.1vw;font-weight: bold;text-align:right;color:red;" disabled />


									</th>

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

										<!-- <input type="text" style="text-align:right;" name="aktiva_tetap" id="aktiva_tetap" placeholder="aktiva tetap" value="<?php
																																									// if (isset($data_detail->aktiva_tetap)) {
																																									// 																															echo nominal($data_detail->aktiva_tetap);
																																									// 																														} 
																																									?>"> -->


										<input type="text" class="form-control uang" onkeyup="sum_total_aktiva_lancar();" name="aktiva_tetap" id="aktiva_tetap" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php if (isset($data_detail->aktiva_tetap)) {
																																																																echo nominal($data_detail->aktiva_tetap);
																																																															} ?>>" ; />

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
										<!-- <input type="text" style="text-align:right;" name="aktiva_tetap_berwujud" id="aktiva_tetap_berwujud" placeholder="aktiva tetap berwujud" value="<?php
																																																// if (isset($data_detail->aktiva_tetap_berwujud)) {
																																																// 																																					echo nominal($data_detail->aktiva_tetap_berwujud);
																																																// 																																					$Total_Aktiva_Tetap_Bersih = $Total_Aktiva_Tetap_Bersih + $data_detail->aktiva_tetap_berwujud;
																																																// 																																				} 
																																																?>"> -->


										<input type="text" class="form-control uang" onkeyup="sum_total_aktiva_lancar();" name="aktiva_tetap_berwujud" id="aktiva_tetap_berwujud" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php if (isset($data_detail->aktiva_tetap_berwujud)) {
																																																																				echo nominal($data_detail->aktiva_tetap_berwujud);
																																																																				$Total_Aktiva_Tetap_Bersih = $Total_Aktiva_Tetap_Bersih + $data_detail->aktiva_tetap_berwujud;
																																																																			} ?>>" ; />


									</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

									<!-- PASIVA  -->


									<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

									<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="300">Utang Afiliasi</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-top:none;border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

									<th style="font-size:0.550em; text-align:right; width: 150px;border: 1px solid black;border-top:none;border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">

										<!-- <input type="text" style="text-align:right;" name="utang_afiliasi" id="utang_afiliasi" placeholder="utang afiliasi" value="<?php //if (isset($data_detail->utang_afiliasi)) { echo nominal($data_detail->utang_afiliasi); $TOTAL_Utang_Jangka_Panjang = $TOTAL_Utang_Jangka_Panjang + $data_detail->utang_afiliasi;} 
																																										?>"> -->

										<input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="utang_afiliasi" id="utang_afiliasi" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php if (isset($data_detail->utang_afiliasi)) {
																																																																	echo nominal($data_detail->utang_afiliasi);
																																																																	$TOTAL_Utang_Jangka_Panjang = $TOTAL_Utang_Jangka_Panjang + $data_detail->utang_afiliasi;
																																																																} ?>>" ; />


									</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th>

								</tr>





								<tr>
									<!-- AKTIVA -->

									<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

									<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="300">Akumulasi Depresiasi ATB</th>


									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

									<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">

										<!-- <input type="text" style="text-align:right;" name="akumulasi_depresiasi_atb" id="akumulasi_depresiasi_atb" placeholder="akumulasi depresiasi atb" value="<?php
																																																		// if (isset($data_detail->utang_afiliasi)) {
																																																		// 																																								echo nominal($data_detail->akumulasi_depresiasi_atb);
																																																		// 																																								$Total_Aktiva_Tetap_Bersih = $Total_Aktiva_Tetap_Bersih + $data_detail->akumulasi_depresiasi_atb;
																																																		// 																																							} 
																																																		?>"> -->


										<input type="text" class="form-control uang" onkeyup="sum_total_aktiva_lancar();" name="akumulasi_depresiasi_atb" id="akumulasi_depresiasi_atb" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php if (isset($data_detail->utang_afiliasi)) {
																																																																						echo nominal($data_detail->akumulasi_depresiasi_atb);
																																																																						$Total_Aktiva_Tetap_Bersih = $Total_Aktiva_Tetap_Bersih + $data_detail->akumulasi_depresiasi_atb;
																																																																					} ?>>" ; />

									</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

									<!-- PASIVA  -->


									<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

									<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="300">Total Utang</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

									<th style="font-size:0.550em; text-align:right; width: 150px;border: 1px solid black;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
										<strong>
											<?php //echo nominal($TOTAL_Utang_Lancar + $TOTAL_Utang_Jangka_Panjang) 
											?>
										</strong>

										<input type="text" class="form-control uang" onkeyup="sum_total_aktiva_lancar();" name="TOTAL_Utang_Lancar_dan_jangka_panjang" id="TOTAL_Utang_Lancar_dan_jangka_panjang" placeholder="" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" value="<?php
																																																																												if (isset($Total_Aktiva_Tetap_Bersih)) {

																																																																													echo $Total_Aktiva_Tetap_Bersih;

																																																																													// $Total_Aktiva_Tetap_Bersih = $Total_Aktiva_Tetap_Bersih + $data_detail->uang_muka_pajak; 

																																																																												}
																																																																												?>>" ; />


									</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none;  border-top:none;border-bottom:none;border-collapse: collapse;" colspan="20"></th>

								</tr>





								<tr>
									<!-- AKTIVA -->


									<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

									<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="300">Total Aktiva Tetap Bersih</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

									<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-right:none;border-left:none;  border-collapse: collapse;" colspan="150"><?php //echo nominal($Total_Aktiva_Tetap_Bersih) 
																																																											?>

										<input type="text" class="form-control uang" onkeyup="sum_total_aktiva_lancar();" name="total_aktiva_tetap_bersih" id="total_aktiva_tetap_bersih" placeholder="" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" value="<?php
																																																																						if (isset($Total_Aktiva_Tetap_Bersih)) {

																																																																							echo $Total_Aktiva_Tetap_Bersih;

																																																																							// $Total_Aktiva_Tetap_Bersih = $Total_Aktiva_Tetap_Bersih + $data_detail->uang_muka_pajak; 

																																																																						}
																																																																						?>>" ; />

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


										<!-- <input type="text" style="text-align:right;" name="piutang_non_usaha_pihak_ketiga" id="piutang_non_usaha_pihak_ketiga" placeholder="piutang non usaha pihak ketiga" value="<?php
																																																						// if (isset($data_detail->piutang_non_usaha_pihak_ketiga)) {
																																																						// 																																												echo nominal($data_detail->piutang_non_usaha_pihak_ketiga);
																																																						// 																																												$Aktiva_Lain_Lain = $Aktiva_Lain_Lain + $data_detail->piutang_non_usaha_pihak_ketiga;
																																																						// 																																											}
																																																						?>"> -->

										<input type="text" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" class="form-control uang" onkeyup="sum_total_aktiva_lancar();" name="piutang_non_usaha_pihak_ketiga" id="piutang_non_usaha_pihak_ketiga" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php if (isset($data_detail->piutang_non_usaha_pihak_ketiga)) {
																																																																																										echo nominal($data_detail->piutang_non_usaha_pihak_ketiga);
																																																																																										$Aktiva_Lain_Lain = $Aktiva_Lain_Lain + $data_detail->piutang_non_usaha_pihak_ketiga;
																																																																																									} ?>>" ; />

									</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

									<!-- PASIVA  -->


									<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

									<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="300">Modal Dasar dan Penyertaan</th>


									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

									<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">

										<input type="text" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="modal_dasar_dan_penyertaan" id="modal_dasar_dan_penyertaan" placeholder="Modal Dasar dan Penyertaan" value="<?php if (isset($data_detail->modal_dasar_dan_penyertaan)) {
																																																																													echo nominal($data_detail->modal_dasar_dan_penyertaan);
																																																																													$TOTAL_Modal_dan_Laba_ditahan = $TOTAL_Modal_dan_Laba_ditahan + $data_detail->modal_dasar_dan_penyertaan;
																																																																												} ?>">
									</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th>

								</tr>







								<tr>
									<!-- AKTIVA -->


									<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

									<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="300">Piutang Non Usaha Radio</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

									<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
										<input type="text" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" class="form-control uang" onkeyup="sum_total_aktiva_lancar();" name="piutang_non_usaha_radio" id="piutang_non_usaha_radio" placeholder="piutang non usaha radio" value="<?php if (isset($data_detail->piutang_non_usaha_radio)) {
																																																																											echo nominal($data_detail->piutang_non_usaha_radio);
																																																																											$Aktiva_Lain_Lain = $Aktiva_Lain_Lain + $data_detail->piutang_non_usaha_radio;
																																																																										} ?>">
									</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

									<!-- PASIVA  -->


									<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

									<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="300">Cadangan Umum</th>


									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

									<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">

										<input type="text" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="cadangan_umum" id="cadangan_umum" placeholder="Cadangan Umum" value="<?php if (isset($data_detail->cadangan_umum)) {
																																																																			echo nominal($data_detail->cadangan_umum);
																																																																			$TOTAL_Modal_dan_Laba_ditahan = $TOTAL_Modal_dan_Laba_ditahan + $data_detail->cadangan_umum;
																																																																		} ?>">
									</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th>

								</tr>






								<tr>
									<!-- AKTIVA -->


									<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

									<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="300">ljpj-Taman Gedung Kesenian Gabusan</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

									<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
										<input type="text" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" class="form-control uang" onkeyup="sum_total_aktiva_lancar();" name="ljpj_taman_gedung_kesenian_gabusan" id="ljpj_taman_gedung_kesenian_gabusan" placeholder="ljpj taman gedung kesenian gabusan" value="<?php if (isset($data_detail->ljpj_taman_gedung_kesenian_gabusan)) {
																																																																																			echo nominal($data_detail->ljpj_taman_gedung_kesenian_gabusan);
																																																																																			$Aktiva_Lain_Lain = $Aktiva_Lain_Lain + $data_detail->ljpj_taman_gedung_kesenian_gabusan;
																																																																																		} ?>">
									</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

									<!-- PASIVA  -->


									<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

									<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="300">Laba BUMD (PAD)</th>


									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

									<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
										<input type="text" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="laba_bumd_pad" id="laba_bumd_pad" placeholder="Laba BUMD (PAD)" value="<?php if (isset($data_detail->laba_bumd_pad)) {
																																																																				echo nominal($data_detail->laba_bumd_pad);
																																																																				$TOTAL_Modal_dan_Laba_ditahan = $TOTAL_Modal_dan_Laba_ditahan + $data_detail->laba_bumd_pad;
																																																																			} ?>">
									</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th>

								</tr>


								<tr>
									<!-- AKTIVA -->


									<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

									<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="300">ljpj-Kompleks Gedung Kesenian</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

									<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
										<input type="text" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" class="form-control uang" onkeyup="sum_total_aktiva_lancar();" name="ljpj_kompleks_gedung_kesenian" id="ljpj_kompleks_gedung_kesenian" placeholder="ljpj kompleks gedung kesenian" value="<?php if (isset($data_detail->ljpj_kompleks_gedung_kesenian)) {
																																																																																echo nominal($data_detail->ljpj_kompleks_gedung_kesenian);
																																																																																$Aktiva_Lain_Lain = $Aktiva_Lain_Lain + $data_detail->ljpj_kompleks_gedung_kesenian;
																																																																															} ?>">
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
										<input type="text" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" class="form-control uang" onkeyup="sum_total_aktiva_lancar();" name="ljpj_radio" id="ljpj_radio" placeholder="ljpj radio" value="<?php if (isset($data_detail->ljpj_radio)) {
																																																																	echo nominal($data_detail->ljpj_radio);
																																																																	$Aktiva_Lain_Lain = $Aktiva_Lain_Lain + $data_detail->ljpj_radio;
																																																																} ?>">
									</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

									<!-- PASIVA  -->


									<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

									<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="300">Laba (Rugi) Tahun Lalu</th>


									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

									<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">

										<input type="text" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="laba_rugi_tahun_lalu" id="laba_rugi_tahun_lalu" placeholder="laba-rugi tahun lalu" value="<?php if (isset($data_detail->laba_rugi_tahun_lalu)) {
																																																																									echo nominal($data_detail->laba_rugi_tahun_lalu);
																																																																									$TOTAL_Modal_dan_Laba_ditahan = $TOTAL_Modal_dan_Laba_ditahan + $data_detail->laba_rugi_tahun_lalu;
																																																																								} ?>">
									</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th>

								</tr>






								<tr>
									<!-- AKTIVA -->


									<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

									<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="300">ljpj-Kerjasama Operasi Apotek Dharma Usaha</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

									<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
										<input type="text" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" class="form-control uang" onkeyup="sum_total_aktiva_lancar();" name="ljpj_kerjasama_operasi_apotek_dharma_usaha" id="ljpj_kerjasama_operasi_apotek_dharma_usaha" placeholder="ljpj kerjasama operasi apotek dharma usaha" value="<?php if (isset($data_detail->ljpj_kerjasama_operasi_apotek_dharma_usaha)) {
																																																																																									echo nominal($data_detail->ljpj_kerjasama_operasi_apotek_dharma_usaha);
																																																																																									$Aktiva_Lain_Lain = $Aktiva_Lain_Lain + $data_detail->ljpj_kerjasama_operasi_apotek_dharma_usaha;
																																																																																								} ?>">
									</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

									<!-- PASIVA  -->


									<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

									<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="300">Laba (Rugi) Tahun Berjalan</th>


									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

									<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">

										<input type="text" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="laba_rugi_tahun_berjalan" id="laba_rugi_tahun_berjalan" placeholder="laba-rugi tahun berjalan" value="<?php if (isset($data_detail->laba_rugi_tahun_berjalan)) {
																																																																												echo nominal($data_detail->laba_rugi_tahun_berjalan);
																																																																												$TOTAL_Modal_dan_Laba_ditahan = $TOTAL_Modal_dan_Laba_ditahan + $data_detail->laba_rugi_tahun_berjalan;
																																																																											} ?>">
									</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="20"></th>

								</tr>







								<tr>
									<!-- AKTIVA -->


									<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

									<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="300">ljpj-Peternakan</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

									<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
										<input type="text" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" class="form-control uang" onkeyup="sum_total_aktiva_lancar();" name="ljpj_peternakan" id="ljpj_peternakan" placeholder="ljpj peternakan" value="<?php if (isset($data_detail->ljpj_peternakan)) {
																																																																					echo nominal($data_detail->ljpj_peternakan);
																																																																					$Aktiva_Lain_Lain = $Aktiva_Lain_Lain + $data_detail->ljpj_peternakan;
																																																																				} ?>">
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
										<input type="text" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" class="form-control uang" onkeyup="sum_total_aktiva_lancar();" name="ljpj_kerjasama_adwm" id="ljpj_kerjasama_adwm" placeholder="ljpj kerjasama adwm" value="<?php if (isset($data_detail->ljpj_kerjasama_adwm)) {
																																																																								echo nominal($data_detail->ljpj_kerjasama_adwm);
																																																																								$Aktiva_Lain_Lain = $Aktiva_Lain_Lain + $data_detail->ljpj_kerjasama_adwm;
																																																																							} ?>">
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
										<input type="text" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" class="form-control uang" onkeyup="sum_total_aktiva_lancar();" name="ljpj_kerjasama_pdu_cabean_panggungharjo" id="ljpj_kerjasama_pdu_cabean_panggungharjo" placeholder="ljpj kerjasama pdu cabean panggungharjo" value="<?php if (isset($data_detail->ljpj_kerjasama_pdu_cabean_panggungharjo)) {
																																																																																							echo nominal($data_detail->ljpj_kerjasama_pdu_cabean_panggungharjo);
																																																																																							$Aktiva_Lain_Lain = $Aktiva_Lain_Lain + $data_detail->ljpj_kerjasama_pdu_cabean_panggungharjo;
																																																																																						} ?>">
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

									<th style="font-size:0.550em; text-align:right;  width: 150px;border: 1px solid black; border-right:none;border-left:none;  border-collapse: collapse;" colspan="150"><strong><?php //echo nominal($Aktiva_Lain_Lain) 
																																																					?></strong>

										<input type="text" class="form-control uang" onkeyup="sum_total_aktiva_lancar();" name="total_aktiva_lain_lain" id="total_aktiva_lain_lain" placeholder="" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" value="<?php
																																																																					if (isset($Aktiva_Lain_Lain)) {

																																																																						echo $Aktiva_Lain_Lain;

																																																																						// $Total_Aktiva_Tetap_Bersih = $Total_Aktiva_Tetap_Bersih + $data_detail->uang_muka_pajak; 

																																																																					}
																																																																					?>>" ; />


									</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>

									<!-- PASIVA  -->


									<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="10"></th>

									<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="300"></th>


									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

									<th style="font-size:0.550em; text-align:right; width: 150px;border: 1px solid black; border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
										<?php
										//echo nominal($TOTAL_Modal_dan_Laba_ditahan); 
										?>

										<input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="total_modal_dan_laba_ditahan" id="total_modal_dan_laba_ditahan" placeholder="" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" value="<?php
																																																																								if (isset($TOTAL_Modal_dan_Laba_ditahan)) {

																																																																									echo $TOTAL_Modal_dan_Laba_ditahan;

																																																																									// $Total_Aktiva_Tetap_Bersih = $Total_Aktiva_Tetap_Bersih + $data_detail->uang_muka_pajak; 

																																																																								}
																																																																								?>>" ; />

									</th>

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

									<th style="font-size:0.550em; text-align:right; border-top:none;width: 150px;border: 1px solid black;border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">

										<input type="text" class="form-control uang" onkeyup="sum();" name="total_aktiva" id="total_aktiva" placeholder="" value="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" ; />

									</th>

									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-bottom:none;border-top:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20"></th>




									<!-- PASIVA  -->
									<th style="font-size: 0.550em;text-align:left; width: 310px;border: 1px solid black;border-bottom:none;border-top:none; border-right:none;  border-collapse: collapse;" colspan="310"><strong>TOTAL PASIVA</strong></th>

									<!-- <th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="10"></th> -->




									<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-right:none;border-left:none;  border-collapse: collapse;" colspan="20">Rp.</th>

									<th style="font-size:0.550em; text-align:right; width: 150px;border: 1px solid black; border-right:none;border-left:none;  border-collapse: collapse;" colspan="150">
										<strong>
											<?php
											//echo nominal($TOTAL_Utang_Lancar + $TOTAL_Utang_Jangka_Panjang + $TOTAL_Modal_dan_Laba_ditahan); 
											?>
										</strong>

										<input type="text" class="form-control uang" name="TOTAL_PASIVA" id="TOTAL_PASIVA" placeholder="" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" value="<?php
																																																							if (isset($TOTAL_Utang_Lancar)) {

																																																								echo nominal($TOTAL_Utang_Lancar + $TOTAL_Utang_Jangka_Panjang + $TOTAL_Modal_dan_Laba_ditahan);

																																																								// $Total_Aktiva_Tetap_Bersih = $Total_Aktiva_Tetap_Bersih + $data_detail->uang_muka_pajak; 

																																																							}
																																																							?>>" ; />

									</th>

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



						</div>
						<div class="col-1"></div>
					</div>

				</div>


			</div>

		</div>








		<!-- <div class="card card-success"> -->
		<div class="card-header">

			<!-- <div class="row"> -->
			<!-- <div class="col-12" text-align="center"> <strong><label for="nmrsj">Jumlah Pembayaran </label></strong></div> -->




			<div class="form-group">

				<div class="row">
					<div class="col-4" align="left">
						<!-- <a href="<?php //echo site_url('tbl_pembelian/') 
										?>" class="btn btn-primary">Lanjut Transaksi</a> -->
						<!-- <a href="<?php //echo site_url('tbl_pembelian') 
										?>" class="btn btn-default">Cancel</a> -->
						<!-- <input type="hidden" id="tahun_transaksi" name="tahun_transaksi" value="<?php echo $tahun_neraca; ?>" /> -->
						<input type="hidden" id="tahun_transaksi" name="tahun_transaksi" value="<?php echo $tahun_neraca; ?>" />
						<input type="hidden" id="bulan_transaksi" name="bulan_transaksi" value="<?php echo $bulan_transaksi; ?>" />
					</div>

					<div class="col-4">
						<button type="submit" class="btn btn-primary"><?php echo $button; ?></button>

						<?php
						if ($button == "Update") {
						?>
							<a href="<?php echo site_url('tbl_neraca_data/neraca_cetak/' . $uuid_data_neraca)
										?>" class="btn btn-success" target="_blank">Cetak Neraca (PDF)</a>
						<?php } ?>



					</div>
					<div class="col-4"></div>
				</div>

			</div>


			<!-- </div> -->

		</div>
	</div>


</form>



<div class="form-group">

	<div class="row">
		<div class="col-4"></div>
		<div class="col-4">
			<button onclick="history.back()">&#8592; Back</button>
		</div>
		<div class="col-4"></div>
	</div>
</div>



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




<script>
	function format_rupiah(angka) {


		var number_string = angka.replace(/[^,\d]/g, '').toString(),
			split = number_string.split(','),
			sisa = split[0].length % 3,
			rupiah = split[0].substr(0, sisa),
			ribuan = split[0].substr(sisa).match(/\d{3}/gi);

		// alert(split);
		// alert(sisa);
		// alert(rupiah);
		// alert("ribuan: " + ribuan);

		// tambahkan titik jika yang di input sudah menjadi angka ribuan
		if (ribuan) {
			separator = sisa ? '.' : '';
			rupiah += separator + ribuan.join('.');

		}

		return rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
		// return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');


	}
</script>

<!-- <script src="<?php //echo base_url() 
					?>assets/js_calc/big.js"></script> -->


<script>
	function TEST_sum_total_aktiva_lancar() {

		$va_kas = document.getElementById('kas').value;

		var huruf = document.getElementById('kas').value;
		var cek_decimal = huruf.split(",");

		Xcv = cek_decimal[0];

		const str = Xcv;

		const substr = '-';

		if (str.includes(substr) == true) {
			alert("ada -");
			var var_minus = "-";
		} else {
			alert("Tidak ada -");
			var var_minus = "";
		}

		Ycv = Xcv.replace(/[^0-9]/g, '');
		alert(Ycv);



		if (!isNaN(Ycv)) {
			alert(var_minus);
			alert("tidak kosong : " + Ycv);
			// alert(cek_decimal[1]);


			// Xcv = cek_decimal[0].replace(/,/g, "");

			// alert("replace , : " + Xcv);


			// Xcv = Xcv.replace(/./g, "");
			// alert("replace . : " + Xcv);

			var var_rupiah_kas = format_rupiah(Ycv);
			// alert(var_rupiah_kas);
			var rupiah_format = var_rupiah_kas.replace(/,/g, ".");
			// alert(rupiah_format);
			document.getElementById('kas').value = var_minus + rupiah_format;

			// const str = Xcv;
			// alert(str);

			// const substr = '.';

			// if (str.includes(substr) == true) {
			// 	alert('ada');
			// 	// Xcv = str.replace(/./g, "");
			// 	var res = str.replace(".", "");
			// 	alert(res);
			// 	var var_rupiah_kas = format_rupiah(res);
			// 	alert(var_rupiah_kas);
			// 	var rupiah_format = var_rupiah_kas.replace(/,/g, ".");
			// 	alert(rupiah_format);
			// 	document.getElementById('kas').value = rupiah_format;
			// } else {
			// 	alert('tidak ada');
			// }

			// console.log(str.includes(substr));
			// The output akan menjadi true

			// var var_rupiah_kas = format_rupiah(Xcv);
			// alert(var_rupiah_kas);
			// alert(var_rupiah_kas.replace(/,/g, "."));

			// // alert(var_rupiah_kas.replace(/,/g, ".") + "," + cek_decimal[0]);
			// alert("-----" + var_rupiah_kas.replace(/,/g, "."));
			// xqwerty = var_rupiah_kas.replace(/,/g, ".");
			// alert("====" + xqwerty);

			// // var result_total_aktiva_tetap_bersih = parseFloat($xqwerty);
			// // alert(">>>>>>" + result_total_aktiva_tetap_bersih);

			// document.getElementById('kas').value = xqwerty;
		}

		// if (!isNaN(cek_decimal[0])) {
		// 	alert(var_rupiah_kas.replace(/,/g, ".") + "," + cek_decimal[0]);
		// 	document.getElementById('kas').value = var_rupiah_kas.replace(/,/g, ".") + "," + cek_decimal[0];
		// } else {
		// 	alert(var_rupiah_kas.replace(/,/g, "."));
		// 	document.getElementById('kas').value = var_rupiah_kas.replace(/,/g, ".");
		// }


		let text = document.getElementById('kas').value;
		const myArray = text.split(",");
		// alert(myArray);

		// $va_kas = document.getElementById('kas').value.replace(",", '.');
		// alert($va_kas);

		$va_kas = parseFloat($va_kas);
		// alert($va_kas);

		var va_kas = $va_kas.toFixed(2);
		// alert(va_kas);
		// alert($va_kas);

		// document.getElementById('kas').value = format_rupiah(va_kas);



		$va_bank = document.getElementById('bank').value.replace(",", '.');
		// alert($va_bank);

		$jumlah = parseFloat($va_kas) + parseFloat($va_bank);
		var jumlah = $jumlah.toFixed(2);

		// alert($jumlah.toFixed(2));


		if (!isNaN(jumlah)) {
			document.getElementById('total_aktiva_lancar').value = format_rupiah(jumlah);


		}

		// FORMAT RUPIAH


	}
</script>

<script>
	function sum_total_aktiva_lancar() {

		if (!isNaN(parseInt(document.getElementById('kas').value.replace(/[^0-9]/g, '')))) {

			var huruf = document.getElementById('kas').value;
			var cek_decimal = huruf.split(",");

			// alert(cek_decimal[0]);
			// alert(cek_decimal[1]);


			koma_belakang = cek_decimal[1];


			// alert(koma_belakang);
			Xcv = cek_decimal[0];
			const str = Xcv;
			const substr = '-';

			if (str.includes(substr) == true) {
				// alert("ada -");
				var var_minus = "-";
			} else {
				// alert("Tidak ada -");
				var var_minus = "";
			}

			Ycv = Xcv.replace(/[^0-9]/g, '');
			// alert(Ycv);


			if (!isNaN(Ycv)) {
				// alert(var_minus);
				// alert("tidak kosong : " + Ycv);

				var var_rupiah_kas = format_rupiah(Ycv);
				// alert(var_rupiah_kas);
				var rupiah_format = var_rupiah_kas.replace(/,/g, ".");
				// alert(rupiah_format);

				if (!isNaN(koma_belakang)) {
					document.getElementById('kas').value = var_minus + rupiah_format + "," + koma_belakang;



					alert(koma_belakang.length);

					// if ((koma_belakang.length) === 1) {
						// koma_belakang = koma_belakang;
						alert("koma" + koma_belakang);
						var va_kas = var_minus + rupiah_format + "," + koma_belakang;

						alert("parsefloat" + parseFloat(koma_belakang / 10));
						alert(parseInt(va_kas));

						var cv = (parseInt(va_kas) + parseFloat(koma_belakang / 10)).toFixed(2);
						alert("var:" + cv);
						cb = parseFloat(cv * 10) + parseFloat(cv * 10);
						alert(cb);
						cd = parseFloat(cb / 10);
						alert("total =" + cd);



					// } elseif ((koma_belakang.length) > 1) {
					// 	var va_kas = var_minus + rupiah_format + "," + koma_belakang;

					// 	alert("parsefloat" + parseFloat(koma_belakang / 100));
					// 	alert(parseInt(va_kas));

					// 	var cv = (parseInt(va_kas) + parseFloat(koma_belakang / 100)).toFixed(2);
					// 	alert("var:" + cv);
					// 	cb = parseFloat(cv * 100) + parseFloat(cv * 100);
					// 	alert(cb);
					// 	cd = parseFloat(cb / 100);
					// 	alert("total =" + cd);

					// }



				} else {
					document.getElementById('kas').value = var_minus + rupiah_format;
					$va_kas = var_minus + rupiah_format;
				}


			}


			// ==================================
			// $va_kas = parseInt(document.getElementById('kas').value.replace(/[^0-9-]/g, ''));

			// $va_kas = 0;
			va_test = parseFloat($va_kas).toFixed(2) + parseFloat($va_kas).toFixed(2);
			alert("VAS KAS" + $va_kas);
			alert("VAS va_test" + va_test);
			// alert("VAS==" + $va_kas + $va_test);






			// const va_kas_new = parseFloat($va_kas);
			// const va_kas_new1 = parseFloat($va_kas);

			// alert(va_kas_new + va_kas_new1);





		} else {
			$va_kas = 0;
		}

		if (!isNaN(parseInt(document.getElementById('bank').value.replace(/[^0-9]/g, '')))) {
			$va_bank = parseInt(document.getElementById('bank').value.replace(/[^0-9]/g, ''));
		} else {
			$va_bank = 0;
		}

		if (!isNaN(parseInt(document.getElementById('piutang_usaha').value.replace(/[^0-9]/g, '')))) {
			$va_piutang_usaha = parseInt(document.getElementById('piutang_usaha').value.replace(/[^0-9]/g, ''));
		} else {
			$va_piutang_usaha = 0;
		}

		if (!isNaN(parseInt(document.getElementById('piutang_non_usaha').value.replace(/[^0-9]/g, '')))) {
			$va_piutang_non_usaha = parseInt(document.getElementById('piutang_non_usaha').value.replace(/[^0-9]/g, ''));
		} else {
			$va_piutang_non_usaha = 0;
		}

		if (!isNaN(parseInt(document.getElementById('persediaan').value.replace(/[^0-9]/g, '')))) {
			$va_persediaan = parseInt(document.getElementById('persediaan').value.replace(/[^0-9]/g, ''));
		} else {
			$va_persediaan = 0;
		}

		if (!isNaN(parseInt(document.getElementById('uang_muka_pajak').value.replace(/[^0-9]/g, '')))) {
			$va_uang_muka_pajak = parseInt(document.getElementById('uang_muka_pajak').value.replace(/[^0-9]/g, ''));
		} else {
			$va_uang_muka_pajak = 0;
		}

		var result_total_aktiva_lancar = parseInt($va_kas) + parseInt($va_bank) + parseInt($va_piutang_usaha) + parseInt($va_piutang_non_usaha) + parseInt($va_persediaan) + parseInt($va_uang_muka_pajak);

		if (!isNaN(result_total_aktiva_lancar)) {
			document.getElementById('total_aktiva_lancar').value = result_total_aktiva_lancar;
		}



		//

		if (!isNaN(parseInt(document.getElementById('aktiva_tetap').value.replace(/[^0-9]/g, '')))) {
			$va_aktiva_tetap = parseInt(document.getElementById('aktiva_tetap').value.replace(/[^0-9]/g, ''));
		} else {
			$va_aktiva_tetap = 0;
		}


		if (!isNaN(parseInt(document.getElementById('aktiva_tetap_berwujud').value.replace(/[^0-9]/g, '')))) {
			$va_aktiva_tetap_berwujud = parseInt(document.getElementById('aktiva_tetap_berwujud').value.replace(/[^0-9]/g, ''));
		} else {
			$va_aktiva_tetap_berwujud = 0;
		}

		if (!isNaN(parseInt(document.getElementById('akumulasi_depresiasi_atb').value.replace(/[^0-9]/g, '')))) {
			$va_akumulasi_depresiasi_atb = parseInt(document.getElementById('akumulasi_depresiasi_atb').value.replace(/[^0-9]/g, ''));
		} else {
			$va_akumulasi_depresiasi_atb = 0;
		}



		var result_total_aktiva_tetap_bersih = parseInt($va_aktiva_tetap) + parseInt($va_aktiva_tetap_berwujud) + parseInt($va_akumulasi_depresiasi_atb);

		if (!isNaN(result_total_aktiva_tetap_bersih)) {
			document.getElementById('total_aktiva_tetap_bersih').value = result_total_aktiva_tetap_bersih;
		}



		//
		if (!isNaN(parseInt(document.getElementById('piutang_non_usaha_pihak_ketiga').value.replace(/[^0-9]/g, '')))) {
			$va_piutang_non_usaha_pihak_ketiga = parseInt(document.getElementById('piutang_non_usaha_pihak_ketiga').value.replace(/[^0-9]/g, ''));
		} else {
			$va_piutang_non_usaha_pihak_ketiga = 0;
		}

		if (!isNaN(parseInt(document.getElementById('piutang_non_usaha_radio').value.replace(/[^0-9]/g, '')))) {
			$va_piutang_non_usaha_radio = parseInt(document.getElementById('piutang_non_usaha_radio').value.replace(/[^0-9]/g, ''));
		} else {
			$va_piutang_non_usaha_radio = 0;
		}

		if (!isNaN(parseInt(document.getElementById('ljpj_taman_gedung_kesenian_gabusan').value.replace(/[^0-9]/g, '')))) {
			$va_ljpj_taman_gedung_kesenian_gabusan = parseInt(document.getElementById('ljpj_taman_gedung_kesenian_gabusan').value.replace(/[^0-9]/g, ''));
		} else {
			$va_ljpj_taman_gedung_kesenian_gabusan = 0;
		}


		if (!isNaN(parseInt(document.getElementById('ljpj_kompleks_gedung_kesenian').value.replace(/[^0-9]/g, '')))) {
			$va_ljpj_kompleks_gedung_kesenian = parseInt(document.getElementById('ljpj_kompleks_gedung_kesenian').value.replace(/[^0-9]/g, ''));
		} else {
			$va_ljpj_kompleks_gedung_kesenian = 0;
		}


		if (!isNaN(parseInt(document.getElementById('ljpj_radio').value.replace(/[^0-9]/g, '')))) {
			$va_ljpj_radio = parseInt(document.getElementById('ljpj_radio').value.replace(/[^0-9]/g, ''));
		} else {
			$va_ljpj_radio = 0;
		}


		if (!isNaN(parseInt(document.getElementById('ljpj_kerjasama_operasi_apotek_dharma_usaha').value.replace(/[^0-9]/g, '')))) {
			$va_ljpj_kerjasama_operasi_apotek_dharma_usaha = parseInt(document.getElementById('ljpj_kerjasama_operasi_apotek_dharma_usaha').value.replace(/[^0-9]/g, ''));
		} else {
			$va_ljpj_kerjasama_operasi_apotek_dharma_usaha = 0;
		}

		if (!isNaN(parseInt(document.getElementById('ljpj_peternakan').value.replace(/[^0-9]/g, '')))) {
			$va_ljpj_peternakan = parseInt(document.getElementById('ljpj_peternakan').value.replace(/[^0-9]/g, ''));
		} else {
			$va_ljpj_peternakan = 0;
		}

		if (!isNaN(parseInt(document.getElementById('ljpj_kerjasama_adwm').value.replace(/[^0-9]/g, '')))) {
			$va_ljpj_kerjasama_adwm = parseInt(document.getElementById('ljpj_kerjasama_adwm').value.replace(/[^0-9]/g, ''));
		} else {
			$va_ljpj_kerjasama_adwm = 0;
		}


		if (!isNaN(parseInt(document.getElementById('ljpj_kerjasama_pdu_cabean_panggungharjo').value.replace(/[^0-9]/g, '')))) {
			$va_ljpj_kerjasama_pdu_cabean_panggungharjo = parseInt(document.getElementById('ljpj_kerjasama_pdu_cabean_panggungharjo').value.replace(/[^0-9]/g, ''));
		} else {
			$va_ljpj_kerjasama_pdu_cabean_panggungharjo = 0;
		}




		var result_total_aktiva_lain_lain = parseInt($va_piutang_non_usaha_pihak_ketiga) + parseInt($va_piutang_non_usaha_radio) + parseInt($va_ljpj_taman_gedung_kesenian_gabusan) + parseInt($va_ljpj_kompleks_gedung_kesenian) + parseInt($va_ljpj_radio) + parseInt($va_ljpj_kerjasama_operasi_apotek_dharma_usaha) + parseInt($va_ljpj_peternakan) + parseInt($va_ljpj_kerjasama_adwm) + parseInt($va_ljpj_kerjasama_pdu_cabean_panggungharjo);

		if (!isNaN(result_total_aktiva_lain_lain)) {
			// document.getElementById('total_aktiva_lain_lain').value = result_total_aktiva_lain_lain;
			document.getElementById('total_aktiva_lain_lain').value = preg_replace('/[^\d-]+/', '', $result_total_aktiva_lain_lain);;
		}



		// TOTAL_AKTIVA
		if (!isNaN(result_total_aktiva_lancar)) {
			document.getElementById('total_aktiva').value = result_total_aktiva_lancar + result_total_aktiva_tetap_bersih + result_total_aktiva_lain_lain;
		}


	}
</script>



<script>
	function sum_total_utang_lancar() {

		if (!isNaN(parseInt(document.getElementById('utang_usaha').value.replace(/[^0-9]/g, '')))) {
			$va_utang_usaha = parseInt(document.getElementById('utang_usaha').value.replace(/[^0-9]/g, ''));
		} else {
			$va_utang_usaha = 0;
		}

		if (!isNaN(parseInt(document.getElementById('utang_pajak').value.replace(/[^0-9]/g, '')))) {
			$va_utang_pajak = parseInt(document.getElementById('utang_pajak').value.replace(/[^0-9]/g, ''));
		} else {
			$va_utang_pajak = 0;
		}

		if (!isNaN(parseInt(document.getElementById('utang_lain_lain').value.replace(/[^0-9]/g, '')))) {
			$va_utang_lain_lain = parseInt(document.getElementById('utang_lain_lain').value.replace(/[^0-9]/g, ''));
		} else {
			$va_utang_lain_lain = 0;
		}



		var result = parseInt($va_utang_usaha) + parseInt($va_utang_pajak) + parseInt($va_utang_lain_lain);

		if (!isNaN(result)) {
			document.getElementById('total_utang_lancar').value = result;
		}




		if (!isNaN(parseInt(document.getElementById('utang_afiliasi').value.replace(/[^0-9]/g, '')))) {
			$va_utang_afiliasi = parseInt(document.getElementById('utang_afiliasi').value.replace(/[^0-9]/g, ''));
		} else {
			$va_utang_afiliasi = 0;
		}

		var result_utang = parseInt($va_utang_afiliasi);

		if (!isNaN(result)) {
			document.getElementById('TOTAL_Utang_Lancar_dan_jangka_panjang').value = result + result_utang;
		}


		// Modal dan Laba ditahan
		if (!isNaN(parseInt(document.getElementById('modal_dasar_dan_penyertaan').value.replace(/[^0-9]/g, '')))) {
			$va_modal_dasar_dan_penyertaan = parseInt(document.getElementById('modal_dasar_dan_penyertaan').value.replace(/[^0-9]/g, ''));
		} else {
			$va_modal_dasar_dan_penyertaan = 0;
		}


		if (!isNaN(parseInt(document.getElementById('cadangan_umum').value.replace(/[^0-9]/g, '')))) {
			$va_cadangan_umum = parseInt(document.getElementById('cadangan_umum').value.replace(/[^0-9]/g, ''));
		} else {
			$va_cadangan_umum = 0;
		}


		if (!isNaN(parseInt(document.getElementById('laba_bumd_pad').value.replace(/[^0-9]/g, '')))) {
			$va_laba_bumd_pad = parseInt(document.getElementById('laba_bumd_pad').value.replace(/[^0-9]/g, ''));
		} else {
			$va_laba_bumd_pad = 0;
		}

		if (!isNaN(parseInt(document.getElementById('laba_rugi_tahun_lalu').value.replace(/[^0-9]/g, '')))) {
			$va_laba_rugi_tahun_lalu = parseInt(document.getElementById('laba_rugi_tahun_lalu').value.replace(/[^0-9]/g, ''));
		} else {
			$va_laba_rugi_tahun_lalu = 0;
		}

		if (!isNaN(parseInt(document.getElementById('laba_rugi_tahun_berjalan').value.replace(/[^0-9]/g, '')))) {
			$va_laba_rugi_tahun_berjalan = parseInt(document.getElementById('laba_rugi_tahun_berjalan').value.replace(/[^0-9]/g, ''));
		} else {
			$va_laba_rugi_tahun_berjalan = 0;
		}


		var result_total_modal_dan_laba_ditahan = parseInt($va_modal_dasar_dan_penyertaan) + parseInt($va_cadangan_umum) + parseInt($va_laba_bumd_pad) + parseInt($va_laba_rugi_tahun_lalu) + parseInt($va_laba_rugi_tahun_berjalan);

		if (!isNaN(result_total_modal_dan_laba_ditahan)) {
			document.getElementById('total_modal_dan_laba_ditahan').value = result_total_modal_dan_laba_ditahan;
		}


		// TOTAL_PASIVA
		if (!isNaN(result)) {
			document.getElementById('TOTAL_PASIVA').value = result + result_utang + result_total_modal_dan_laba_ditahan;
		}



	}
</script>