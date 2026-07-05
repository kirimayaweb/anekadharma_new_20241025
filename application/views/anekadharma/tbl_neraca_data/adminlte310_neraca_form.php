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





<div class="content-wrapper neraca-form-fullpage">

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
			.neraca-form-fullpage {
				max-width: 100%;
			}

			.neraca-form-fullpage .card-header .form-group > .row {
				margin-left: 0;
				margin-right: 0;
			}

			.neraca-form-fullpage .card-header .form-group > .row > [class*="col-"] {
				padding-left: 6px;
				padding-right: 6px;
			}

			#customers.neraca-form-table {
				width: 100% !important;
				max-width: 100%;
				table-layout: fixed;
			}

			#customers.neraca-form-table th[colspan="1000"] {
				width: 100% !important;
			}

			#customers.neraca-form-table th[colspan="500"] {
				width: 50% !important;
			}

			/* Layout 5 kolom per sisi: 5% | 50% | 5% | 39% | 1% */
			#customers.neraca-form-table th.neraca-col-gap {
				width: 5% !important;
				padding: 0 !important;
				border: none !important;
			}

			#customers.neraca-form-table th.neraca-col-label,
			#customers.neraca-form-table th[colspan="250"].neraca-col-label {
				width: 50% !important;
				padding-left: 6px !important;
				padding-right: 4px !important;
				white-space: normal;
				line-height: 1.35;
				vertical-align: middle;
				text-align: left !important;
				overflow: hidden;
				max-width: 50%;
				font-size: 1.1rem !important;
				font-weight: bold;
			}

			/* Keterangan + tombol setting inline: "Kas [tombol]" lalu kolom Rp. */
			.neraca-label-wrap {
				display: inline-flex;
				flex-direction: row;
				flex-wrap: wrap;
				align-items: center;
				justify-content: flex-start;
				gap: 0 8px;
				width: auto;
				max-width: 100%;
				vertical-align: middle;
			}

			.neraca-label-text {
				display: inline-block;
				vertical-align: middle;
				text-align: left;
				white-space: nowrap;
				font-weight: bold;
				font-size: 1.1rem !important;
				line-height: 1.35;
			}

			.neraca-label-setting {
				display: inline-block;
				vertical-align: middle;
				text-align: left;
				white-space: nowrap;
				flex: 0 0 auto;
			}

			.neraca-label-setting .btn-neraca-get-kode-akun-form {
				font-size: 0.68rem;
				padding: 3px 6px;
				line-height: 1.25;
				white-space: nowrap;
				margin: 0 !important;
				float: none !important;
				display: inline-block;
				vertical-align: middle;
				position: static !important;
			}

			/* Tombol setting hanya di kolom keterangan, bukan di kolom input */
			#customers.neraca-form-table th.neraca-col-input .btn-neraca-get-kode-akun-form,
			#customers.neraca-form-table th.neraca-col-input form.neraca-kode-akun-form > .btn-neraca-get-kode-akun-form {
				display: none !important;
			}

			#customers.neraca-form-table th.neraca-col-rp {
				width: 5% !important;
				padding-left: 2px !important;
				padding-right: 2px !important;
				white-space: nowrap;
				vertical-align: middle;
				text-align: left !important;
			}

			#customers.neraca-form-table th.neraca-col-input,
			#customers.neraca-form-table th[colspan="195"].neraca-col-input {
				width: 39% !important;
				padding: 2px 4px !important;
				vertical-align: middle;
				overflow: visible;
			}

			#customers.neraca-form-table th.neraca-col-center {
				width: 1% !important;
				padding: 0 !important;
				border: none !important;
				overflow: hidden;
			}

			#customers.neraca-form-table tr:has(> th.neraca-col-input) > th.neraca-col-input {
				padding-top: 1.5mm !important;
				padding-bottom: 1.5mm !important;
			}

			.neraca-field-block {
				width: 100%;
				max-width: 100%;
				margin-bottom: 3mm;
				padding-bottom: 0;
				box-sizing: border-box;
			}

			#customers.neraca-form-table th.neraca-col-input.neraca-layout-done > .row,
			#customers.neraca-form-table th.neraca-col-input.neraca-layout-done > .row .sm-4 {
				display: none !important;
			}

			#customers.neraca-form-table tr:has(> th.neraca-col-input) > th.neraca-col-input {
				padding-top: 1.5mm !important;
				padding-bottom: 1.5mm !important;
			}

			.neraca-row-calc,
			.neraca-row-input {
				display: table;
				width: 100%;
				table-layout: fixed;
				border-collapse: collapse;
				border-spacing: 0;
				margin: 0 0 3px 0;
				padding: 0;
			}

			.neraca-row-calc .neraca-col-nominal,
			.neraca-row-input .neraca-col-input-wrap,
			.neraca-row-input .neraca-col-simpan {
				display: table-cell;
				vertical-align: middle;
				padding: 0;
				box-sizing: border-box;
			}

			/* Baris 1 kolom 4: nominal penuh (2 sub-kolom digabung) */
			.neraca-row-calc .neraca-col-nominal {
				width: 100%;
				text-align: right;
				padding-right: 2px;
				overflow: visible;
			}

			/* Baris 2 kolom 4: input 70% | simpan 30% */
			.neraca-row-input .neraca-col-input-wrap {
				width: 70%;
				text-align: left;
				padding-right: 4px;
			}

			.neraca-row-input .neraca-col-simpan {
				width: 30%;
				text-align: right;
				white-space: nowrap;
				padding-left: 4px;
			}

			.neraca-row-calc .neraca-calc-display {
				display: block;
				width: 100%;
				text-align: right;
				font-weight: bold;
				font-size: 0.86rem;
				line-height: 1.35;
				white-space: nowrap;
				overflow: visible;
				text-overflow: clip;
				padding: 0;
				margin: 0;
				font-variant-numeric: tabular-nums;
			}

			.neraca-row-input .neraca-col-input-wrap input[type="tel"],
			.neraca-row-input .neraca-col-input-wrap input[type="text"] {
				display: block;
				width: 100% !important;
				min-width: 0 !important;
				max-width: 100% !important;
				box-sizing: border-box;
			}

			.neraca-row-input .neraca-col-simpan .btn-success {
				font-size: 0.74rem;
				padding: 4px 10px;
				white-space: nowrap;
				margin: 0 !important;
				float: none !important;
				display: inline-block;
				vertical-align: middle;
			}

			#customers.neraca-form-table .row {
				margin-left: 0;
				margin-right: 0;
				width: 100%;
			}

			#customers.neraca-form-table .sm-4 {
				width: 100%;
				max-width: 100%;
				padding: 0;
			}

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

			form.neraca-kode-akun-form {
				display: block;
				width: 100%;
				max-width: 100%;
				margin: 0;
			}

			form.neraca-kode-akun-form .neraca-row-input .neraca-col-input-wrap input[type="tel"],
			form.neraca-kode-akun-form .neraca-row-input .neraca-col-input-wrap input[type="text"],
			form.neraca-kode-akun-form .neraca-col-input-wrap input[name="input_box"],
			#customers.neraca-form-table .neraca-row-input .neraca-col-input-wrap input[type="tel"] {
				display: block;
				width: 100% !important;
				min-width: 0 !important;
				max-width: 100% !important;
				padding: 6px 6px !important;
				height: auto !important;
				margin: 0 !important;
				box-sizing: border-box;
				font-size: 0.86rem !important;
				font-weight: bold;
				font-variant-numeric: tabular-nums;
				text-align: right;
				overflow: visible;
			}

			.neraca-field-block .btn,
			form.neraca-kode-akun-form .btn {
				position: static !important;
				float: none !important;
			}

			form.neraca-kode-akun-form .neraca-row-input .neraca-col-right .btn-success,
			#customers.neraca-form-table .neraca-row-input .neraca-col-right .btn-success {
				white-space: nowrap;
				padding: 3px 8px;
				font-size: 0.75rem;
				margin: 0;
			}

			#customers.neraca-form-table > tbody > tr > th > input[type="tel"],
			#customers.neraca-form-table > tr > th > input[type="tel"] {
				width: 100% !important;
				min-width: 24ch !important;
				max-width: 100% !important;
				box-sizing: border-box;
				font-size: 0.86rem !important;
				font-weight: bold;
				font-variant-numeric: tabular-nums;
				text-align: right;
				padding: 6px 8px !important;
			}

			.neraca-nominal-match {
				color: #28a745 !important;
				font-weight: bold !important;
			}

			.neraca-nominal-mismatch {
				color: #f9032f !important;
				font-weight: bold !important;
			}

			.neraca-calc-display {
				font-weight: bold;
			}
		</style>

	</head>

	<!-- <body> -->




	<?php
	$this->load->helper('neraca_kode_akun');
	$TOTAL_AKIVA_LANCAR = 0;
	$Total_Aktiva_Tetap_Bersih = 0;
	$Aktiva_Lain_Lain = 0;
	$TOTAL_Utang_Lancar = 0;
	$TOTAL_Utang_Jangka_Panjang = 0;
	$TOTAL_Modal_dan_Laba_ditahan = 0;

	$TOTAL_UTANG_LANCAR = 0;
	$TOTAL_UTANG_LANCAR_PLUS_AFILIASI = 0;
	$TOTAL_MODAL_DAN_LABA_DITAHAN = 0;

	?>

	<?php $this->load->view('anekadharma/tbl_neraca_data/partials/neraca_publish_bar'); ?>
	<?php $this->load->helper('neraca_kode_akun'); ?>

	<div class="card-header">

		<div class="row">
			<!-- <div class="col-12" text-align="center"> <strong><label for="nmrsj">Jumlah Pembayaran </label></strong></div> -->




			<div class="form-group">

				<div class="row">
					<div class="col-12">


						<table id="customers" class="neraca-form-table">




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

							<?php

							$GET_TOTAL_AKTIVA_LANCAR = 0;
							$GET_Total_AKTIVA_TETAP_BERSIH = 0;
							$GET_AKTIVA_LAIN_LAIN = 0;

							?>




							<tr>
								<!-- AKTIVA -->
								<th style="font-size: 0.550em;text-align:left; width: 500px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="500"><strong>AKTIVA</strong></th>
								<!-- <th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th> -->
								<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="25" class="neraca-col-rp">Rp.</th> -->
								<!-- <th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 200px;" colspan="200"><?php //echo nominal(234323432) 
																																						?></th> -->
								<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

								<!-- PASIVA  -->
								<th style="font-size: 0.550em;text-align:left; width: 500px;border: 1px solid black;    border-collapse: collapse;" colspan="500"><strong>PASIVA</strong></th>
								<!-- <th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th> -->
								<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="25" class="neraca-col-rp">Rp.</th> -->
								<!-- <th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 200px;" colspan="200"><?php //echo nominal(234323432) 
																																						?></th> -->
								<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

							</tr>


							<tr>
								<!-- AKTIVA -->
								<th style="font-size: 0.550em;text-align:left; width: 500px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="500"><strong>Aktiva Lancar</strong></th>

								<!-- <th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th> -->

								<!-- <th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th> -->

								<!-- <th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input"></th> -->

								<!-- <th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th> -->

								<!-- PASIVA  -->
								<th style="font-size: 0.550em;text-align:left; width: 500px;border: 1px solid black;border-top:none;border-bottom:none;   border-collapse: collapse;" colspan="500"><strong>Utang Lancar</strong></th>

								<!-- <th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th> -->

								<!-- <th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th> -->

								<!-- <th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input"></th> -->

								<!-- <th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="5" class="neraca-col-center"></th> -->

							</tr>


							<tr>
								<!-- AKTIVA -->
								<th class="neraca-col-gap" style="font-size:0.550em;border:1px solid black;border-top:none;border-bottom:none;border-right:none;border-collapse:collapse;" colspan="25"></th>
								<th class="neraca-col-label" style="font-size:0.550em;border:1px solid black;border-top:none;border-bottom:none;border-right:none;border-left:none;border-collapse:collapse;text-align:left;" colspan="250"><?php echo neraca_render_label_keterangan('kas'); ?></th>
								<th class="neraca-col-rp" style="font-size:0.550em;border:1px solid black;border-top:none;border-bottom:none;border-right:none;border-left:none;border-collapse:collapse;" colspan="25">Rp.</th>
								<th class="neraca-col-input" style="font-size:0.550em;border:1px solid black;border-top:none;border-bottom:none;border-right:none;border-left:none;border-collapse:collapse;" colspan="195">
									<?php echo neraca_render_calc_legacy('kas', isset($neraca_system_totals) ? $neraca_system_totals : array()); ?>
									<form action="<?php echo $action . '/kas'; ?>" method="post" class="neraca-kode-akun-form" data-field-neraca="kas">
										<input type="tel" name="input_box" id="input_box_kas" onchange="setTwoNumberDecimal" value="<?php
										echo neraca_field_input_value($data_tbl_neraca_data, 'kas');
										$GET_TOTAL_AKTIVA_LANCAR = $GET_TOTAL_AKTIVA_LANCAR + $data_tbl_neraca_data->kas;
										?>" />
										<button type="submit" class="btn btn-success btn-xs">Simpan</button>
									</form>
								</th>
								<th class="neraca-col-center" style="font-size:0.550em;border:1px solid black;border-top:none;border-bottom:none;border-left:none;border-collapse:collapse;" colspan="5"></th>

								<!-- PASIVA  -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="250" class="neraca-col-label"><?php echo neraca_render_label_keterangan('utang_usaha'); ?></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">



									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="neraca-calc-legacy" style="display:none;"><?php echo neraca_system_total_value('utang_usaha', isset($neraca_system_totals) ? $neraca_system_totals : array()); ?></div>
										<div class="sm-4">


											<!-- <input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="utang_usaha" id="utang_usaha" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php //echo $data_tbl_neraca_data->utang_usaha; 
																																																																?>" ; /> -->

											<form action="<?php echo $action . '/utang_usaha'; ?>" method="post" class="neraca-kode-akun-form" data-field-neraca="utang_usaha">

												<!-- <input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="input_box" id="input_box" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:black;" value="<?php //echo $data_tbl_neraca_data->utang_usaha; 
																																																																	?>" width: 50px; /> -->


												<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																															// echo str_replace('.', ',', $data_tbl_neraca_data->utang_usaha);

																																															echo number_format($data_tbl_neraca_data->utang_usaha, 2, ',', '.');

																																															$TOTAL_UTANG_LANCAR = $TOTAL_UTANG_LANCAR + $data_tbl_neraca_data->utang_usaha;
																																															?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

																																	<button type="button" class="btn btn-warning btn-xs btn-neraca-get-kode-akun-form" style="white-space:nowrap;margin-left:2px;" onclick="return (window.neracaOpenSettingKodeAkun ? neracaOpenSettingKodeAkun(this) : false);"><i class="fa fa-cog"></i> Setting Kode Akun</button>
																					<button type="submit" class="btn btn-success btn-xs">Simpan </button>
											</form>
										</div>
									</div>

								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

							</tr>





							<tr>
								<!-- AKTIVA -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label"><?php echo neraca_render_label_keterangan('bank'); ?></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">

									<!--  -->

									<!-- <input type="tel" pattern="[0-9(,)]{15}" name="bank" id="bank" onKeyPress="return goodchars(event,'0123456789.,-',this)" min="0" max="10" step="0,25" value="<?php //echo $data_tbl_neraca_data->bank; 
																																																		?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" /> -->

									<div class="row">
										<div class="neraca-calc-legacy" style="display:none;"><?php echo neraca_system_total_value('bank', isset($neraca_system_totals) ? $neraca_system_totals : array()); ?></div>
										<div class="sm-4">
											<form action="<?php echo $action . '/bank'; ?>" method="post" class="neraca-kode-akun-form" data-field-neraca="bank">
												<!-- 
											<input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="input_box" id="input_box" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:black;" value="<?php //echo $data_tbl_neraca_data->bank;
																																																															?>" width: 50px; /> -->


												<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																															// echo str_replace('.', ',', $data_tbl_neraca_data->bank);

																																															echo number_format($data_tbl_neraca_data->bank, 2, ',', '.');
																																															$GET_TOTAL_AKTIVA_LANCAR = $GET_TOTAL_AKTIVA_LANCAR + $data_tbl_neraca_data->bank;
																																															?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />


																																	<button type="button" class="btn btn-warning btn-xs btn-neraca-get-kode-akun-form" style="white-space:nowrap;margin-left:2px;" onclick="return (window.neracaOpenSettingKodeAkun ? neracaOpenSettingKodeAkun(this) : false);"><i class="fa fa-cog"></i> Setting Kode Akun</button>
																					<button type="submit" class="btn btn-success btn-xs">Simpan </button>
											</form>

										</div>
									</div>



								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<!-- PASIVA  -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="250" class="neraca-col-label"><?php echo neraca_render_label_keterangan('utang_pajak'); ?></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">

									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="neraca-calc-legacy" style="display:none;"><?php echo neraca_system_total_value('utang_pajak', isset($neraca_system_totals) ? $neraca_system_totals : array()); ?></div>
										<div class="sm-4">


											<form action="<?php echo $action . '/utang_pajak'; ?>" method="post" class="neraca-kode-akun-form" data-field-neraca="utang_pajak">

												<!-- <input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="input_box" id="input_box" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php //echo $data_tbl_neraca_data->utang_pajak;
																																																																?>" width: 50px; /> -->


												<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																															// echo str_replace('.', ',', $data_tbl_neraca_data->utang_pajak);

																																															echo number_format($data_tbl_neraca_data->utang_pajak, 2, ',', '.');
																																															$TOTAL_UTANG_LANCAR = $TOTAL_UTANG_LANCAR + $data_tbl_neraca_data->utang_pajak;
																																															?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

																																	<button type="button" class="btn btn-warning btn-xs btn-neraca-get-kode-akun-form" style="white-space:nowrap;margin-left:2px;" onclick="return (window.neracaOpenSettingKodeAkun ? neracaOpenSettingKodeAkun(this) : false);"><i class="fa fa-cog"></i> Setting Kode Akun</button>
																					<button type="submit" class="btn btn-success btn-xs">Simpan </button>
											</form>
										</div>
									</div>

								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

							</tr>



							<tr>
								<!-- AKTIVA -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label"><?php echo neraca_render_label_keterangan('piutang_usaha'); ?></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">

									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="neraca-calc-legacy" style="display:none;"><?php echo neraca_system_total_value('piutang_usaha', isset($neraca_system_totals) ? $neraca_system_totals : array()); ?></div>
										<div class="sm-4">

											<form action="<?php echo $action . '/piutang_usaha'; ?>" method="post" class="neraca-kode-akun-form" data-field-neraca="piutang_usaha">
												<!-- 													
												<input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="input_box" id="input_box" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:black;" value="<?php //echo $data_tbl_neraca_data->piutang_usaha;
																																																																?>" width: 50px; /> -->


												<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																															// echo str_replace('.', ',', $data_tbl_neraca_data->piutang_usaha);

																																															echo number_format($data_tbl_neraca_data->piutang_usaha, 2, ',', '.');
																																															$GET_TOTAL_AKTIVA_LANCAR = $GET_TOTAL_AKTIVA_LANCAR + $data_tbl_neraca_data->piutang_usaha;
																																															?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

																																	<button type="button" class="btn btn-warning btn-xs btn-neraca-get-kode-akun-form" style="white-space:nowrap;margin-left:2px;" onclick="return (window.neracaOpenSettingKodeAkun ? neracaOpenSettingKodeAkun(this) : false);"><i class="fa fa-cog"></i> Setting Kode Akun</button>
																					<button type="submit" class="btn btn-success btn-xs">Simpan </button>
											</form>

										</div>
									</div>

								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<!-- PASIVA  -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="250" class="neraca-col-label"><?php echo neraca_render_label_keterangan('utang_lain_lain'); ?></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">
									<strong>


										<div class="row" align="left" style="color:#f9032f;text-align:left;">
											<div class="neraca-calc-legacy" style="display:none;"><?php echo neraca_system_total_value('utang_lain_lain', isset($neraca_system_totals) ? $neraca_system_totals : array()); ?></div>
											<div class="sm-4">


												<form action="<?php echo $action . '/utang_lain_lain'; ?>" method="post" class="neraca-kode-akun-form" data-field-neraca="utang_lain_lain">

													<!-- <input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="input_box" id="input_box" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php //echo $data_tbl_neraca_data->utang_lain_lain; 
																																																																	?>" width: 50px; /> -->


													<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																																// echo str_replace('.', ',', $data_tbl_neraca_data->utang_lain_lain);

																																																echo number_format($data_tbl_neraca_data->utang_lain_lain, 2, ',', '.');
																																																$TOTAL_UTANG_LANCAR = $TOTAL_UTANG_LANCAR + $data_tbl_neraca_data->utang_lain_lain;
																																																?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />



																																		<button type="button" class="btn btn-warning btn-xs btn-neraca-get-kode-akun-form" style="white-space:nowrap;margin-left:2px;" onclick="return (window.neracaOpenSettingKodeAkun ? neracaOpenSettingKodeAkun(this) : false);"><i class="fa fa-cog"></i> Setting Kode Akun</button>
																					<button type="submit" class="btn btn-success btn-xs">Simpan </button>
												</form>
											</div>
										</div>

									</strong>
								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

							</tr>





							<tr>
								<!-- AKTIVA -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label"><?php echo neraca_render_label_keterangan('piutang_non_usaha'); ?></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">



									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="neraca-calc-legacy" style="display:none;"><?php echo neraca_system_total_value('piutang_non_usaha', isset($neraca_system_totals) ? $neraca_system_totals : array()); ?></div>
										<div class="sm-4">
											<form action="<?php echo $action . '/piutang_non_usaha'; ?>" method="post" class="neraca-kode-akun-form" data-field-neraca="piutang_non_usaha">
												<!-- 													
												<input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="input_box" id="input_box" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php //echo $data_tbl_neraca_data->piutang_non_usaha; 
																																																															?>" width: 60px; /> -->



												<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																															// echo str_replace('.', ',', $data_tbl_neraca_data->piutang_non_usaha);

																																															echo number_format($data_tbl_neraca_data->piutang_non_usaha, 2, ',', '.');
																																															$GET_TOTAL_AKTIVA_LANCAR = $GET_TOTAL_AKTIVA_LANCAR + $data_tbl_neraca_data->piutang_non_usaha;
																																															?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />


																																	<button type="button" class="btn btn-warning btn-xs btn-neraca-get-kode-akun-form" style="white-space:nowrap;margin-left:2px;" onclick="return (window.neracaOpenSettingKodeAkun ? neracaOpenSettingKodeAkun(this) : false);"><i class="fa fa-cog"></i> Setting Kode Akun</button>
																					<button type="submit" class="btn btn-success btn-xs">Simpan </button>
											</form>
										</div>
									</div>
								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<!-- PASIVA  -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="250" class="neraca-col-label"></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">



									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="sm-4">
										</div>
										<div class="row" align="right">
											<div class="sm-12">


												<!-- <form action="<?php echo $action . '/piutang_non_usaha'; ?>" method="post" class="neraca-kode-akun-form" data-field-neraca="piutang_non_usaha"> -->

												<!-- <input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="input_box" id="input_box" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php //echo $data_tbl_neraca_data->piutang_non_usaha;
																																																																?>" width: 50px; /> -->

												<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php

																																															// echo $TOTAL_UTANG_LANCAR; 
																																															echo number_format($TOTAL_UTANG_LANCAR, 2, ',', '.');

																																															?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:red;" />


												<!-- 																					<button type="button" class="btn btn-warning btn-xs btn-neraca-get-kode-akun-form" style="white-space:nowrap;margin-left:2px;" onclick="return (window.neracaOpenSettingKodeAkun ? neracaOpenSettingKodeAkun(this) : false);"><i class="fa fa-cog"></i> Setting Kode Akun</button>
																					<button type="submit" class="btn btn-success btn-xs">Simpan </button>
												</form> -->
											</div>
										</div>
									</div>

								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

							</tr>







							<tr>
								<!-- AKTIVA -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label"><?php echo neraca_render_label_keterangan('persediaan'); ?></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">



									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="neraca-calc-legacy" style="display:none;"><?php echo neraca_system_total_value('persediaan', isset($neraca_system_totals) ? $neraca_system_totals : array()); ?></div>
										<div class="sm-4">



											<!-- <input type="text" class="form-control uang" onkeyup="sum_total_aktiva_lancar();" name="persediaan" id="persediaan" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php //echo $data_tbl_neraca_data->persediaan; 
																																																																?>" ; /> -->


											<form action="<?php echo $action . '/persediaan'; ?>" method="post" class="neraca-kode-akun-form" data-field-neraca="persediaan">
												<!-- <input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="input_box" id="input_box" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php //echo $data_tbl_neraca_data->persediaan;
																																																																?>" width: 50px; /> -->


												<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																															// echo str_replace('.', ',', $data_tbl_neraca_data->persediaan);

																																															echo number_format($data_tbl_neraca_data->persediaan, 2, ',', '.');
																																															$GET_TOTAL_AKTIVA_LANCAR = $GET_TOTAL_AKTIVA_LANCAR + $data_tbl_neraca_data->persediaan;
																																															?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />


																																	<button type="button" class="btn btn-warning btn-xs btn-neraca-get-kode-akun-form" style="white-space:nowrap;margin-left:2px;" onclick="return (window.neracaOpenSettingKodeAkun ? neracaOpenSettingKodeAkun(this) : false);"><i class="fa fa-cog"></i> Setting Kode Akun</button>
																					<button type="submit" class="btn btn-success btn-xs">Simpan </button>
											</form>
										</div>
									</div>



								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<!-- PASIVA  -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="250" class="neraca-col-label"></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input"></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

							</tr>




							<tr>
								<!-- AKTIVA -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label"><?php echo neraca_render_label_keterangan('uang_muka_pajak'); ?></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">



									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="neraca-calc-legacy" style="display:none;"><?php echo neraca_system_total_value('uang_muka_pajak', isset($neraca_system_totals) ? $neraca_system_totals : array()); ?></div>
										<div class="sm-4">



											<!-- 
												<input type="text" class="form-control uang" onkeyup="sum_total_aktiva_lancar();" name="uang_muka_pajak" id="uang_muka_pajak" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php //echo $data_tbl_neraca_data->uang_muka_pajak; 
																																																																		?>" ; /> -->



											<form action="<?php echo $action . '/uang_muka_pajak'; ?>" method="post" class="neraca-kode-akun-form" data-field-neraca="uang_muka_pajak">

												<!-- <input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="input_box" id="input_box" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php //echo $data_tbl_neraca_data->uang_muka_pajak; 
																																																																?>" width: 50px; /> -->


												<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																															// echo str_replace('.', ',', $data_tbl_neraca_data->uang_muka_pajak);

																																															echo number_format($data_tbl_neraca_data->uang_muka_pajak, 2, ',', '.');
																																															$GET_TOTAL_AKTIVA_LANCAR = $GET_TOTAL_AKTIVA_LANCAR + $data_tbl_neraca_data->uang_muka_pajak;
																																															?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

																																	<button type="button" class="btn btn-warning btn-xs btn-neraca-get-kode-akun-form" style="white-space:nowrap;margin-left:2px;" onclick="return (window.neracaOpenSettingKodeAkun ? neracaOpenSettingKodeAkun(this) : false);"><i class="fa fa-cog"></i> Setting Kode Akun</button>
																					<button type="submit" class="btn btn-success btn-xs">Simpan </button>
											</form>
										</div>
									</div>
								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<!-- PASIVA  -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="250" class="neraca-col-label"></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input"></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

							</tr>





							<tr>
								<!-- AKTIVA -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label">Total Aktiva Lancar</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">
									<strong>

										<input type="tel" pattern="[0-9(,)]{15}" name="total_aktiva_lancar" id="total_aktiva_lancar" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																																		// echo $GET_TOTAL_AKTIVA_LANCAR; 

																																																		echo number_format($GET_TOTAL_AKTIVA_LANCAR, 2, ',', '.');
																																																		?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:red;" disabled />


								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<!-- PASIVA  -->
								<th style="font-size: 0.550em;text-align:left; width: 310px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="310"><strong>Utang Jangka Panjang</strong></th>



								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input"></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

							</tr>


							<tr>
								<!-- AKTIVA -->


								<th style="font-size:0.550em; text-align:left; width: 10px;height: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label"></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-right:none;border-left:none;border-top:none;border-bottom:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<th style="font-size:0.550em; text-align:right;  width: 150px;border: 1px solid black;border-right:none;border-left:none;border-top:none;border-bottom:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input"></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<!-- PASIVA  -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="250" class="neraca-col-label"></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input"></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

							</tr>



							<tr>
								<!-- AKTIVA -->
								<th style="font-size: 0.550em; width: 310px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse; text-align:left;" colspan="310"><strong>Aktiva Tetap</strong></th>


								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">


									<!-- <input type="text" class="form-control uang" onkeyup="sum_total_aktiva_lancar();" name="aktiva_tetap" id="aktiva_tetap" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php //echo $data_tbl_neraca_data->aktiva_tetap; 
																																																															?>" ; /> -->


									<!-- <form action="<?php //echo $action . '/aktiva_tetap'; 
														?>" method="post"> -->

									<!-- <input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="input_box" id="input_box" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php //echo $data_tbl_neraca_data->aktiva_tetap; 
																																																													?>" width: 50px; /> -->

									<!-- <input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																														// echo str_replace('.', ',', $data_tbl_neraca_data->aktiva_tetap); 
																																														// $GET_Total_AKTIVA_TETAP_BERSIH=$GET_Total_AKTIVA_TETAP_BERSIH+$data_tbl_neraca_data->aktiva_tetap;
																																														?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />
 -->

									<!-- 																					<button type="button" class="btn btn-warning btn-xs btn-neraca-get-kode-akun-form" style="white-space:nowrap;margin-left:2px;" onclick="return (window.neracaOpenSettingKodeAkun ? neracaOpenSettingKodeAkun(this) : false);"><i class="fa fa-cog"></i> Setting Kode Akun</button>
																					<button type="submit" class="btn btn-success btn-xs">Simpan </button> -->
									<!-- </form> -->


								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<!-- PASIVA  -->


								<th style="font-size: 0.550em;text-align:left; width: 310px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="310"></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<th style="font-size:0.550em; text-align:right; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input"></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

							</tr>






							<tr>
								<!-- AKTIVA -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label"><?php echo neraca_render_label_keterangan('aktiva_tetap_berwujud'); ?></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">




									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="neraca-calc-legacy" style="display:none;"><?php echo neraca_system_total_value('aktiva_tetap_berwujud', isset($neraca_system_totals) ? $neraca_system_totals : array()); ?></div>
										<div class="sm-4">


											<!-- 
												<input type="text" class="form-control uang" onkeyup="sum_total_aktiva_lancar();" name="aktiva_tetap_berwujud" id="aktiva_tetap_berwujud" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php //echo $data_tbl_neraca_data->aktiva_tetap_berwujud; 
																																																																					?>" ; /> -->


											<form action="<?php echo $action . '/aktiva_tetap_berwujud'; ?>" method="post" class="neraca-kode-akun-form" data-field-neraca="aktiva_tetap_berwujud">

												<!-- <input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="input_box" id="input_box" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php //echo $data_tbl_neraca_data->aktiva_tetap_berwujud;
																																																																?>" width: 50px; /> -->


												<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																															// echo str_replace('.', ',', $data_tbl_neraca_data->aktiva_tetap_berwujud);

																																															echo number_format($data_tbl_neraca_data->aktiva_tetap_berwujud, 2, ',', '.');

																																															$GET_Total_AKTIVA_TETAP_BERSIH = $GET_Total_AKTIVA_TETAP_BERSIH + $data_tbl_neraca_data->aktiva_tetap_berwujud;
																																															?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />


																																	<button type="button" class="btn btn-warning btn-xs btn-neraca-get-kode-akun-form" style="white-space:nowrap;margin-left:2px;" onclick="return (window.neracaOpenSettingKodeAkun ? neracaOpenSettingKodeAkun(this) : false);"><i class="fa fa-cog"></i> Setting Kode Akun</button>
																					<button type="submit" class="btn btn-success btn-xs">Simpan </button>
											</form>

										</div>
									</div>
								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<!-- PASIVA  -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="250" class="neraca-col-label"><?php echo neraca_render_label_keterangan('utang_afiliasi'); ?></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-top:none;border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; width: 150px;border: 1px solid black;border-top:none;border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">

									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="neraca-calc-legacy" style="display:none;"><?php echo neraca_system_total_value('utang_afiliasi', isset($neraca_system_totals) ? $neraca_system_totals : array()); ?></div>
										<div class="sm-4">


											<form action="<?php echo $action . '/utang_afiliasi'; ?>" method="post" class="neraca-kode-akun-form" data-field-neraca="utang_afiliasi">

												<!-- <input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="input_box" id="input_box" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php //echo $data_tbl_neraca_data->utang_afiliasi;
																																																																?>" width: 50px; /> -->

												<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																															// echo str_replace('.', ',', $data_tbl_neraca_data->utang_afiliasi);

																																															echo number_format($data_tbl_neraca_data->utang_afiliasi, 2, ',', '.');
																																															$TOTAL_UTANG_LANCAR_PLUS_AFILIASI = $TOTAL_UTANG_LANCAR + $data_tbl_neraca_data->utang_afiliasi;
																																															?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />


																																	<button type="button" class="btn btn-warning btn-xs btn-neraca-get-kode-akun-form" style="white-space:nowrap;margin-left:2px;" onclick="return (window.neracaOpenSettingKodeAkun ? neracaOpenSettingKodeAkun(this) : false);"><i class="fa fa-cog"></i> Setting Kode Akun</button>
																					<button type="submit" class="btn btn-success btn-xs">Simpan </button>
											</form>
										</div>
									</div>
								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

							</tr>





							<tr>
								<!-- AKTIVA -->

								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label"><?php echo neraca_render_label_keterangan('akumulasi_depresiasi_atb'); ?></th>


								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">



									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="neraca-calc-legacy" style="display:none;"><?php echo neraca_system_total_value('akumulasi_depresiasi_atb', isset($neraca_system_totals) ? $neraca_system_totals : array()); ?></div>
										<div class="sm-4">



											<!-- 
														<input type="text" class="form-control uang" onkeyup="sum_total_aktiva_lancar();" name="akumulasi_depresiasi_atb" id="akumulasi_depresiasi_atb" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php //echo $data_tbl_neraca_data->akumulasi_depresiasi_atb; 
																																																																									?>" ; /> -->



											<form action="<?php echo $action . '/akumulasi_depresiasi_atb'; ?>" method="post" class="neraca-kode-akun-form" data-field-neraca="akumulasi_depresiasi_atb">

												<!-- <input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="input_box" id="input_box" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php //echo $data_tbl_neraca_data->akumulasi_depresiasi_atb; 
																																																																?>" width: 50px; /> -->


												<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																															// echo str_replace('.', ',', $data_tbl_neraca_data->akumulasi_depresiasi_atb);

																																															echo number_format($data_tbl_neraca_data->akumulasi_depresiasi_atb, 2, ',', '.');
																																															$GET_Total_AKTIVA_TETAP_BERSIH = $GET_Total_AKTIVA_TETAP_BERSIH + $data_tbl_neraca_data->akumulasi_depresiasi_atb;
																																															?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />


																																	<button type="button" class="btn btn-warning btn-xs btn-neraca-get-kode-akun-form" style="white-space:nowrap;margin-left:2px;" onclick="return (window.neracaOpenSettingKodeAkun ? neracaOpenSettingKodeAkun(this) : false);"><i class="fa fa-cog"></i> Setting Kode Akun</button>
																					<button type="submit" class="btn btn-success btn-xs">Simpan </button>
											</form>
										</div>
									</div>
								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<!-- PASIVA  -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="250" class="neraca-col-label">Total Utang</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; width: 150px;border: 1px solid black;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">
									<strong>
										<?php //echo nominal($TOTAL_Utang_Lancar + $TOTAL_Utang_Jangka_Panjang) 
										?>
									</strong>

									<!-- <input type="text" class="form-control uang" onkeyup="sum_total_aktiva_lancar();" name="TOTAL_Utang_Lancar_dan_jangka_panjang" id="TOTAL_Utang_Lancar_dan_jangka_panjang" placeholder="" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" value="" ; /> -->

									<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																												// echo $TOTAL_UTANG_LANCAR_PLUS_AFILIASI; 
																																												echo number_format($TOTAL_UTANG_LANCAR_PLUS_AFILIASI, 2, ',', '.');
																																												?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:red;" />

								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none;  border-top:none;border-bottom:none;border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

							</tr>





							<tr>
								<!-- AKTIVA -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label">Total Aktiva Tetap Bersih</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input"><?php //echo nominal($Total_Aktiva_Tetap_Bersih) 
																																																										?>

									<!-- <input type="text" class="form-control uang" onkeyup="sum_total_aktiva_lancar();" name="total_aktiva_tetap_bersih" id="total_aktiva_tetap_bersih" placeholder="" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" value="" ; /> -->

									<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																												// echo $GET_Total_AKTIVA_TETAP_BERSIH; 
																																												echo number_format($GET_Total_AKTIVA_TETAP_BERSIH, 2, ',', '.');
																																												?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:red;" />


								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<!-- PASIVA  -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="250" class="neraca-col-label"></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input"></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

							</tr>




							<tr>
								<!-- AKTIVA -->


								<th style="font-size:0.550em; text-align:left; width: 10px;height: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label"></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-right:none;border-left:none;border-top:none;border-bottom:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<th style="font-size:0.550em; text-align:right;  width: 150px;border: 1px solid black;border-right:none;border-left:none;border-top:none;border-bottom:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input"></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<!-- PASIVA  -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="250" class="neraca-col-label"></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input"></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

							</tr>









							<tr>
								<!-- AKTIVA -->


								<th style="font-size: 0.550em; width: 310px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;    border-collapse: collapse; text-align:left;" colspan="310"><strong>Aktiva Lain-Lain</strong></th>

								<!-- <th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none; border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th> -->



								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<th style="font-size:0.550em; text-align:right;  width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input"></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<!-- PASIVA  -->



								<th style="font-size: 0.550em;text-align:left; width: 310px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;   border-collapse: collapse;" colspan="310"><strong>Modal dan Laba ditahan</strong></th>


								<!-- <th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none; border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th> -->


								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input"></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

							</tr>






							<tr>
								<!-- AKTIVA -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label"><?php echo neraca_render_label_keterangan('piutang_non_usaha_pihak_ketiga'); ?></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">


									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="neraca-calc-legacy" style="display:none;"><?php echo neraca_system_total_value('piutang_non_usaha_pihak_ketiga', isset($neraca_system_totals) ? $neraca_system_totals : array()); ?></div>
										<div class="sm-4">



											<!-- 
													<input type="text" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" class="form-control uang" onkeyup="sum_total_aktiva_lancar();" name="piutang_non_usaha_pihak_ketiga" id="piutang_non_usaha_pihak_ketiga" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php //echo $data_tbl_neraca_data->piutang_non_usaha_pihak_ketiga;
																																																																																												?>" ; /> -->


											<form action="<?php echo $action . '/piutang_non_usaha_pihak_ketiga'; ?>" method="post" class="neraca-kode-akun-form" data-field-neraca="piutang_non_usaha_pihak_ketiga">

												<!-- <input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="input_box" id="input_box" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php //echo $data_tbl_neraca_data->piutang_non_usaha_pihak_ketiga; 
																																																																?>" width: 50px; /> -->


												<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																															// echo str_replace('.', ',', $data_tbl_neraca_data->piutang_non_usaha_pihak_ketiga);

																																															echo number_format($data_tbl_neraca_data->piutang_non_usaha_pihak_ketiga, 2, ',', '.');

																																															$GET_AKTIVA_LAIN_LAIN = $GET_AKTIVA_LAIN_LAIN + $data_tbl_neraca_data->piutang_non_usaha_pihak_ketiga;
																																															?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />


																																	<button type="button" class="btn btn-warning btn-xs btn-neraca-get-kode-akun-form" style="white-space:nowrap;margin-left:2px;" onclick="return (window.neracaOpenSettingKodeAkun ? neracaOpenSettingKodeAkun(this) : false);"><i class="fa fa-cog"></i> Setting Kode Akun</button>
																					<button type="submit" class="btn btn-success btn-xs">Simpan </button>
											</form>
										</div>
									</div>

								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<!-- PASIVA  -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="250" class="neraca-col-label"><?php echo neraca_render_label_keterangan('modal_dasar_dan_penyertaan'); ?></th>


								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">



									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="neraca-calc-legacy" style="display:none;"><?php echo neraca_system_total_value('modal_dasar_dan_penyertaan', isset($neraca_system_totals) ? $neraca_system_totals : array()); ?></div>
										<div class="sm-4">



											<form action="<?php echo $action . '/modal_dasar_dan_penyertaan'; ?>" method="post" class="neraca-kode-akun-form" data-field-neraca="modal_dasar_dan_penyertaan">

												<!-- <input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="input_box" id="input_box" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php //echo $data_tbl_neraca_data->modal_dasar_dan_penyertaan; 
																																																																?>" width: 50px; /> -->


												<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																															// echo str_replace('.', ',', $data_tbl_neraca_data->modal_dasar_dan_penyertaan);

																																															echo number_format($data_tbl_neraca_data->modal_dasar_dan_penyertaan, 2, ',', '.');
																																															$TOTAL_MODAL_DAN_LABA_DITAHAN = $TOTAL_MODAL_DAN_LABA_DITAHAN + $data_tbl_neraca_data->modal_dasar_dan_penyertaan;
																																															?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />


																																	<button type="button" class="btn btn-warning btn-xs btn-neraca-get-kode-akun-form" style="white-space:nowrap;margin-left:2px;" onclick="return (window.neracaOpenSettingKodeAkun ? neracaOpenSettingKodeAkun(this) : false);"><i class="fa fa-cog"></i> Setting Kode Akun</button>
																					<button type="submit" class="btn btn-success btn-xs">Simpan </button>
											</form>
										</div>
									</div>

								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

							</tr>







							<tr>
								<!-- AKTIVA -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label"><?php echo neraca_render_label_keterangan('piutang_non_usaha_radio'); ?></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">

									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="neraca-calc-legacy" style="display:none;"><?php echo neraca_system_total_value('piutang_non_usaha_radio', isset($neraca_system_totals) ? $neraca_system_totals : array()); ?></div>
										<div class="sm-4">

											<!-- 
												<input type="text" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" class="form-control uang" onkeyup="sum_total_aktiva_lancar();" name="piutang_non_usaha_radio" id="piutang_non_usaha_radio" placeholder="piutang non usaha radio" value="<?php //echo $data_tbl_neraca_data->piutang_non_usaha_radio;
																																																																												?>"> -->


											<form action="<?php echo $action . '/piutang_non_usaha_radio'; ?>" method="post" class="neraca-kode-akun-form" data-field-neraca="piutang_non_usaha_radio">

												<!-- <input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="input_box" id="input_box" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php //echo $data_tbl_neraca_data->piutang_non_usaha_radio; 
																																																																?>" width: 50px; /> -->


												<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																															// echo str_replace('.', ',', $data_tbl_neraca_data->piutang_non_usaha_radio);

																																															echo number_format($data_tbl_neraca_data->piutang_non_usaha_radio, 2, ',', '.');
																																															$GET_AKTIVA_LAIN_LAIN = $GET_AKTIVA_LAIN_LAIN + $data_tbl_neraca_data->piutang_non_usaha_radio;
																																															?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />


																																	<button type="button" class="btn btn-warning btn-xs btn-neraca-get-kode-akun-form" style="white-space:nowrap;margin-left:2px;" onclick="return (window.neracaOpenSettingKodeAkun ? neracaOpenSettingKodeAkun(this) : false);"><i class="fa fa-cog"></i> Setting Kode Akun</button>
																					<button type="submit" class="btn btn-success btn-xs">Simpan </button>
											</form>

										</div>
									</div>

								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<!-- PASIVA  -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="250" class="neraca-col-label"><?php echo neraca_render_label_keterangan('cadangan_umum'); ?></th>


								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">


									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="neraca-calc-legacy" style="display:none;"><?php echo neraca_system_total_value('cadangan_umum', isset($neraca_system_totals) ? $neraca_system_totals : array()); ?></div>
										<div class="sm-4">


											<form action="<?php echo $action . '/cadangan_umum'; ?>" method="post" class="neraca-kode-akun-form" data-field-neraca="cadangan_umum">

												<!-- <input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="input_box" id="input_box" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php //echo $data_tbl_neraca_data->cadangan_umum; 
																																																																?>" width: 50px; /> -->


												<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																															// echo str_replace('.', ',', $data_tbl_neraca_data->cadangan_umum);

																																															echo number_format($data_tbl_neraca_data->cadangan_umum, 2, ',', '.');
																																															$TOTAL_MODAL_DAN_LABA_DITAHAN = $TOTAL_MODAL_DAN_LABA_DITAHAN + $data_tbl_neraca_data->cadangan_umum;
																																															?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

																																	<button type="button" class="btn btn-warning btn-xs btn-neraca-get-kode-akun-form" style="white-space:nowrap;margin-left:2px;" onclick="return (window.neracaOpenSettingKodeAkun ? neracaOpenSettingKodeAkun(this) : false);"><i class="fa fa-cog"></i> Setting Kode Akun</button>
																					<button type="submit" class="btn btn-success btn-xs">Simpan </button>
											</form>
										</div>
									</div>
								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

							</tr>






							<tr>
								<!-- AKTIVA -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label"><?php echo neraca_render_label_keterangan('ljpj_taman_gedung_kesenian_gabusan', 'ljpj-Taman Gedung Kesenian Gabusan'); ?></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">


									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="neraca-calc-legacy" style="display:none;"><?php echo neraca_system_total_value('ljpj_taman_gedung_kesenian_gabusan', isset($neraca_system_totals) ? $neraca_system_totals : array()); ?></div>
										<div class="sm-4">
											<!-- 
														<input type="text" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" class="form-control uang" onkeyup="sum_total_aktiva_lancar();" name="ljpj_taman_gedung_kesenian_gabusan" id="ljpj_taman_gedung_kesenian_gabusan" placeholder="ljpj taman gedung kesenian gabusan" value="<?php //echo $data_tbl_neraca_data->ljpj_taman_gedung_kesenian_gabusan;
																																																																																						?>">
													-->

											<form action="<?php echo $action . '/ljpj_taman_gedung_kesenian_gabusan'; ?>" method="post" class="neraca-kode-akun-form" data-field-neraca="ljpj_taman_gedung_kesenian_gabusan">

												<!-- <input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="input_box" id="input_box" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php //echo $data_tbl_neraca_data->ljpj_taman_gedung_kesenian_gabusan; 
																																																																?>" width: 50px; /> -->



												<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																															// echo str_replace('.', ',', $data_tbl_neraca_data->ljpj_taman_gedung_kesenian_gabusan);

																																															echo number_format($data_tbl_neraca_data->ljpj_taman_gedung_kesenian_gabusan, 2, ',', '.');
																																															$GET_AKTIVA_LAIN_LAIN = $GET_AKTIVA_LAIN_LAIN + $data_tbl_neraca_data->ljpj_taman_gedung_kesenian_gabusan;
																																															?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

																																	<button type="button" class="btn btn-warning btn-xs btn-neraca-get-kode-akun-form" style="white-space:nowrap;margin-left:2px;" onclick="return (window.neracaOpenSettingKodeAkun ? neracaOpenSettingKodeAkun(this) : false);"><i class="fa fa-cog"></i> Setting Kode Akun</button>
																					<button type="submit" class="btn btn-success btn-xs">Simpan </button>
											</form>
										</div>
									</div>

								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<!-- PASIVA  -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="250" class="neraca-col-label"><?php echo neraca_render_label_keterangan('laba_bumd_pad', 'Laba BUMD (PAD)'); ?></th>


								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">

									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="neraca-calc-legacy" style="display:none;"><?php echo neraca_system_total_value('laba_bumd_pad', isset($neraca_system_totals) ? $neraca_system_totals : array()); ?></div>
										<div class="sm-4">


											<form action="<?php echo $action . '/laba_bumd_pad'; ?>" method="post" class="neraca-kode-akun-form" data-field-neraca="laba_bumd_pad">

												<!-- <input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="input_box" id="input_box" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php //echo $data_tbl_neraca_data->laba_bumd_pad; 
																																																																?>" width: 50px; /> -->



												<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																															// echo str_replace('.', ',', $data_tbl_neraca_data->laba_bumd_pad);

																																															echo number_format($data_tbl_neraca_data->laba_bumd_pad, 2, ',', '.');
																																															$TOTAL_MODAL_DAN_LABA_DITAHAN = $TOTAL_MODAL_DAN_LABA_DITAHAN + $data_tbl_neraca_data->laba_bumd_pad;
																																															?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

																																	<button type="button" class="btn btn-warning btn-xs btn-neraca-get-kode-akun-form" style="white-space:nowrap;margin-left:2px;" onclick="return (window.neracaOpenSettingKodeAkun ? neracaOpenSettingKodeAkun(this) : false);"><i class="fa fa-cog"></i> Setting Kode Akun</button>
																					<button type="submit" class="btn btn-success btn-xs">Simpan </button>
											</form>
										</div>
									</div>
								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

							</tr>


							<tr>
								<!-- AKTIVA -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label"><?php echo neraca_render_label_keterangan('ljpj_kompleks_gedung_kesenian', 'ljpj-Kompleks Gedung Kesenian'); ?></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">


									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="neraca-calc-legacy" style="display:none;"><?php echo neraca_system_total_value('ljpj_kompleks_gedung_kesenian', isset($neraca_system_totals) ? $neraca_system_totals : array()); ?></div>
										<div class="sm-4">

											<form action="<?php echo $action . '/ljpj_kompleks_gedung_kesenian'; ?>" method="post" class="neraca-kode-akun-form" data-field-neraca="ljpj_kompleks_gedung_kesenian">

												<!-- <input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="input_box" id="input_box" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php //echo $data_tbl_neraca_data->ljpj_kompleks_gedung_kesenian; 
																																																																?>" width: 50px; /> -->



												<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																															// echo str_replace('.', ',', $data_tbl_neraca_data->ljpj_kompleks_gedung_kesenian);

																																															echo number_format($data_tbl_neraca_data->ljpj_kompleks_gedung_kesenian, 2, ',', '.');
																																															$GET_AKTIVA_LAIN_LAIN = $GET_AKTIVA_LAIN_LAIN + $data_tbl_neraca_data->ljpj_kompleks_gedung_kesenian;
																																															?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

																																	<button type="button" class="btn btn-warning btn-xs btn-neraca-get-kode-akun-form" style="white-space:nowrap;margin-left:2px;" onclick="return (window.neracaOpenSettingKodeAkun ? neracaOpenSettingKodeAkun(this) : false);"><i class="fa fa-cog"></i> Setting Kode Akun</button>
																					<button type="submit" class="btn btn-success btn-xs">Simpan </button>
											</form>
										</div>
									</div>

								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<!-- PASIVA  -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="250" class="neraca-col-label"></th>


								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input"></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

							</tr>










							<tr>
								<!-- AKTIVA -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label"><?php echo neraca_render_label_keterangan('ljpj_radio', 'ljpj-Radio'); ?></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">


									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="neraca-calc-legacy" style="display:none;"><?php echo neraca_system_total_value('ljpj_radio', isset($neraca_system_totals) ? $neraca_system_totals : array()); ?></div>
										<div class="sm-4">

											<form action="<?php echo $action . '/ljpj_radio'; ?>" method="post" class="neraca-kode-akun-form" data-field-neraca="ljpj_radio">

												<!-- <input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="input_box" id="input_box" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php //echo $data_tbl_neraca_data->ljpj_radio; 
																																																																?>" width: 50px; /> -->


												<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																															// echo str_replace('.', ',', $data_tbl_neraca_data->ljpj_radio);

																																															echo number_format($data_tbl_neraca_data->ljpj_radio, 2, ',', '.');
																																															$GET_AKTIVA_LAIN_LAIN = $GET_AKTIVA_LAIN_LAIN + $data_tbl_neraca_data->ljpj_radio;
																																															?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />


																																	<button type="button" class="btn btn-warning btn-xs btn-neraca-get-kode-akun-form" style="white-space:nowrap;margin-left:2px;" onclick="return (window.neracaOpenSettingKodeAkun ? neracaOpenSettingKodeAkun(this) : false);"><i class="fa fa-cog"></i> Setting Kode Akun</button>
																					<button type="submit" class="btn btn-success btn-xs">Simpan </button>
											</form>
										</div>
									</div>
								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<!-- PASIVA  -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="250" class="neraca-col-label"><?php echo neraca_render_label_keterangan('laba_rugi_tahun_lalu', 'Laba (Rugi) Tahun Lalu'); ?></th>


								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">


									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="neraca-calc-legacy" style="display:none;"><?php echo neraca_system_total_value('laba_rugi_tahun_lalu', isset($neraca_system_totals) ? $neraca_system_totals : array()); ?></div>
										<div class="sm-4">



											<form action="<?php echo $action . '/laba_rugi_tahun_lalu'; ?>" method="post" class="neraca-kode-akun-form" data-field-neraca="laba_rugi_tahun_lalu">

												<!-- <input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="input_box" id="input_box" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php //echo $data_tbl_neraca_data->laba_rugi_tahun_lalu; 
																																																																?>" width: 50px; /> -->

												<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																															// echo str_replace('.', ',', $data_tbl_neraca_data->laba_rugi_tahun_lalu);
																										
												echo number_format($data_tbl_neraca_data->laba_rugi_tahun_lalu, 2, ',', '.');																										
																																															$TOTAL_MODAL_DAN_LABA_DITAHAN = $TOTAL_MODAL_DAN_LABA_DITAHAN + $data_tbl_neraca_data->laba_rugi_tahun_lalu;
																																															?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

																																	<button type="button" class="btn btn-warning btn-xs btn-neraca-get-kode-akun-form" style="white-space:nowrap;margin-left:2px;" onclick="return (window.neracaOpenSettingKodeAkun ? neracaOpenSettingKodeAkun(this) : false);"><i class="fa fa-cog"></i> Setting Kode Akun</button>
																					<button type="submit" class="btn btn-success btn-xs">Simpan </button>
											</form>
										</div>
									</div>
								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

							</tr>






							<tr>
								<!-- AKTIVA -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label"><?php echo neraca_render_label_keterangan('ljpj_kerjasama_operasi_apotek_dharma_usaha', 'ljpj-Kerjasama Operasi Apotek Dharma Usaha'); ?></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">


									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="neraca-calc-legacy" style="display:none;"><?php echo neraca_system_total_value('ljpj_kerjasama_operasi_apotek_dharma_usaha', isset($neraca_system_totals) ? $neraca_system_totals : array()); ?></div>
										<div class="sm-4">


											<form action="<?php echo $action . '/ljpj_kerjasama_operasi_apotek_dharma_usaha'; ?>" method="post" class="neraca-kode-akun-form" data-field-neraca="ljpj_kerjasama_operasi_apotek_dharma_usaha">



												<!-- <input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="input_box" id="input_box" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php //echo $data_tbl_neraca_data->ljpj_kerjasama_operasi_apotek_dharma_usaha; 
																																																																?>" width: 50px; /> -->


												<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																															// echo str_replace('.', ',', $data_tbl_neraca_data->ljpj_kerjasama_operasi_apotek_dharma_usaha);
																				
												echo number_format($data_tbl_neraca_data->ljpj_kerjasama_operasi_apotek_dharma_usaha, 2, ',', '.');
																																															$GET_AKTIVA_LAIN_LAIN = $GET_AKTIVA_LAIN_LAIN + $data_tbl_neraca_data->ljpj_kerjasama_operasi_apotek_dharma_usaha;
																																															?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

																																	<button type="button" class="btn btn-warning btn-xs btn-neraca-get-kode-akun-form" style="white-space:nowrap;margin-left:2px;" onclick="return (window.neracaOpenSettingKodeAkun ? neracaOpenSettingKodeAkun(this) : false);"><i class="fa fa-cog"></i> Setting Kode Akun</button>
																					<button type="submit" class="btn btn-success btn-xs">Simpan </button>
											</form>
										</div>
									</div>
								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<!-- PASIVA  -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="250" class="neraca-col-label"><?php echo neraca_render_label_keterangan('laba_rugi_tahun_berjalan', 'Laba (Rugi) Tahun Berjalan'); ?></th>


								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">



									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="neraca-calc-legacy" style="display:none;"><?php echo neraca_system_total_value('laba_rugi_tahun_berjalan', isset($neraca_system_totals) ? $neraca_system_totals : array()); ?></div>
										<div class="sm-4">




											<form action="<?php echo $action . '/laba_rugi_tahun_berjalan'; ?>" method="post" class="neraca-kode-akun-form" data-field-neraca="laba_rugi_tahun_berjalan">

												<!-- <input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="input_box" id="input_box" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php //echo $data_tbl_neraca_data->laba_rugi_tahun_berjalan; 
																																																																?>" width: 50px; />
												 -->

												<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																															// echo str_replace('.', ',', $data_tbl_neraca_data->laba_rugi_tahun_berjalan);
																
												echo number_format($data_tbl_neraca_data->laba_rugi_tahun_berjalan, 2, ',', '.');																
																																															$TOTAL_MODAL_DAN_LABA_DITAHAN = $TOTAL_MODAL_DAN_LABA_DITAHAN + $data_tbl_neraca_data->laba_rugi_tahun_berjalan;
																																															?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />



																																	<button type="button" class="btn btn-warning btn-xs btn-neraca-get-kode-akun-form" style="white-space:nowrap;margin-left:2px;" onclick="return (window.neracaOpenSettingKodeAkun ? neracaOpenSettingKodeAkun(this) : false);"><i class="fa fa-cog"></i> Setting Kode Akun</button>
																					<button type="submit" class="btn btn-success btn-xs">Simpan </button>
											</form>
										</div>
									</div>

								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

							</tr>







							<tr>
								<!-- AKTIVA -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label"><?php echo neraca_render_label_keterangan('ljpj_peternakan', 'ljpj-Peternakan'); ?></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">


									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="neraca-calc-legacy" style="display:none;"><?php echo neraca_system_total_value('ljpj_peternakan', isset($neraca_system_totals) ? $neraca_system_totals : array()); ?></div>
										<div class="sm-4">


											<form action="<?php echo $action . '/ljpj_peternakan'; ?>" method="post" class="neraca-kode-akun-form" data-field-neraca="ljpj_peternakan">

												<!-- <input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="input_box" id="input_box" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php //echo $data_tbl_neraca_data->ljpj_peternakan; 
																																																																?>" width: 50px; /> -->


												<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																															// echo str_replace('.', ',', $data_tbl_neraca_data->ljpj_peternakan);
																								
												echo number_format($data_tbl_neraca_data->ljpj_peternakan, 2, ',', '.');												

																																															$GET_AKTIVA_LAIN_LAIN = $GET_AKTIVA_LAIN_LAIN + $data_tbl_neraca_data->ljpj_peternakan;
																																															?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />

																																	<button type="button" class="btn btn-warning btn-xs btn-neraca-get-kode-akun-form" style="white-space:nowrap;margin-left:2px;" onclick="return (window.neracaOpenSettingKodeAkun ? neracaOpenSettingKodeAkun(this) : false);"><i class="fa fa-cog"></i> Setting Kode Akun</button>
																					<button type="submit" class="btn btn-success btn-xs">Simpan </button>
											</form>
										</div>
									</div>

								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<!-- PASIVA  -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="250" class="neraca-col-label"></th>


								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input"></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

							</tr>


							<tr>
								<!-- AKTIVA -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label"><?php echo neraca_render_label_keterangan('ljpj_kerjasama_adwm', 'ljpj-Kerjasama ADWM'); ?></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">


									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="neraca-calc-legacy" style="display:none;"><?php echo neraca_system_total_value('ljpj_kerjasama_adwm', isset($neraca_system_totals) ? $neraca_system_totals : array()); ?></div>
										<div class="sm-4">

											<form action="<?php echo $action . '/ljpj_kerjasama_adwm'; ?>" method="post" class="neraca-kode-akun-form" data-field-neraca="ljpj_kerjasama_adwm">


												<!-- <input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="input_box" id="input_box" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php //echo $data_tbl_neraca_data->ljpj_kerjasama_adwm; 
																																																																?>" width: 50px; /> -->



												<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																															// echo str_replace('.', ',', $data_tbl_neraca_data->ljpj_kerjasama_adwm);
														
														echo number_format($data_tbl_neraca_data->ljpj_kerjasama_adwm, 2, ',', '.');
																																															$GET_AKTIVA_LAIN_LAIN = $GET_AKTIVA_LAIN_LAIN + $data_tbl_neraca_data->ljpj_kerjasama_adwm;
																																															?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />


																																	<button type="button" class="btn btn-warning btn-xs btn-neraca-get-kode-akun-form" style="white-space:nowrap;margin-left:2px;" onclick="return (window.neracaOpenSettingKodeAkun ? neracaOpenSettingKodeAkun(this) : false);"><i class="fa fa-cog"></i> Setting Kode Akun</button>
																					<button type="submit" class="btn btn-success btn-xs">Simpan </button>
											</form>
										</div>
									</div>
								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<!-- PASIVA  -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="250" class="neraca-col-label"></th>


								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input"></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

							</tr>




							<tr>
								<!-- AKTIVA -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label"><?php echo neraca_render_label_keterangan('ljpj_kerjasama_pdu_cabean_panggungharjo', 'ljpj-Kerjasama PDU Cabean Panggungharjo'); ?></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">


									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="neraca-calc-legacy" style="display:none;"><?php echo neraca_system_total_value('ljpj_kerjasama_pdu_cabean_panggungharjo', isset($neraca_system_totals) ? $neraca_system_totals : array()); ?></div>
										<div class="sm-4">


											<form action="<?php echo $action . '/ljpj_kerjasama_pdu_cabean_panggungharjo'; ?>" method="post" class="neraca-kode-akun-form" data-field-neraca="ljpj_kerjasama_pdu_cabean_panggungharjo">

												<!-- <input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="input_box" id="input_box" placeholder="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" value="<?php //echo $data_tbl_neraca_data->ljpj_kerjasama_pdu_cabean_panggungharjo; 
																																																																?>" width: 50px; />
													 -->


												<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
																																															// echo str_replace('.', ',', $data_tbl_neraca_data->ljpj_kerjasama_pdu_cabean_panggungharjo);
																			
echo number_format($data_tbl_neraca_data->ljpj_kerjasama_pdu_cabean_panggungharjo, 2, ',', '.');																			
																																															$GET_AKTIVA_LAIN_LAIN = $GET_AKTIVA_LAIN_LAIN + $data_tbl_neraca_data->ljpj_kerjasama_pdu_cabean_panggungharjo;
																																															?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />


																																	<button type="button" class="btn btn-warning btn-xs btn-neraca-get-kode-akun-form" style="white-space:nowrap;margin-left:2px;" onclick="return (window.neracaOpenSettingKodeAkun ? neracaOpenSettingKodeAkun(this) : false);"><i class="fa fa-cog"></i> Setting Kode Akun</button>
																					<button type="submit" class="btn btn-success btn-xs">Simpan </button>
											</form>
										</div>
									</div>

								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<!-- PASIVA  -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="250" class="neraca-col-label"></th>


								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input"></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

							</tr>




							<tr>
								<!-- AKTIVA -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label"><?php echo neraca_render_label_keterangan('aset_lain_lain', 'Aset Lain-lain'); ?></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">


									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="neraca-calc-legacy" style="display:none;"><?php echo neraca_system_total_value('aset_lain_lain', isset($neraca_system_totals) ? $neraca_system_totals : array()); ?></div>
										<div class="sm-4">


											<form action="<?php echo $action . '/aset_lain_lain'; ?>" method="post" class="neraca-kode-akun-form" data-field-neraca="aset_lain_lain">

												<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="aset_lain_lain" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php
echo number_format(isset($data_tbl_neraca_data->aset_lain_lain) ? $data_tbl_neraca_data->aset_lain_lain : 0, 2, ',', '.');
																																															$GET_AKTIVA_LAIN_LAIN = $GET_AKTIVA_LAIN_LAIN + (isset($data_tbl_neraca_data->aset_lain_lain) ? $data_tbl_neraca_data->aset_lain_lain : 0);
																																															?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" />


																																	<button type="button" class="btn btn-warning btn-xs btn-neraca-get-kode-akun-form" style="white-space:nowrap;margin-left:2px;" onclick="return (window.neracaOpenSettingKodeAkun ? neracaOpenSettingKodeAkun(this) : false);"><i class="fa fa-cog"></i> Setting Kode Akun</button>
																					<button type="submit" class="btn btn-success btn-xs">Simpan </button>
											</form>
										</div>
									</div>

								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<!-- PASIVA  -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="250" class="neraca-col-label"></th>


								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input"></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

							</tr>




							<tr>
								<!-- AKTIVA -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label"></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right;  width: 150px;border: 1px solid black; border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input"><strong><?php //echo nominal($Aktiva_Lain_Lain) 
																																																				?></strong>

									<!-- <input type="text" class="form-control uang" onkeyup="sum_total_aktiva_lancar();" name="total_aktiva_lain_lain" id="total_aktiva_lain_lain" placeholder="" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" value="" ; /> -->

									<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php 
									// echo $GET_AKTIVA_LAIN_LAIN; 
									echo number_format($GET_AKTIVA_LAIN_LAIN, 2, ',', '.');
									?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:red;" />

								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<!-- PASIVA  -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="250" class="neraca-col-label"></th>


								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; width: 150px;border: 1px solid black; border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">

									<!-- <input type="text" class="form-control uang" onkeyup="sum_total_utang_lancar();" name="total_modal_dan_laba_ditahan" id="total_modal_dan_laba_ditahan" placeholder="" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" value="" ; /> -->

									<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php 
									// echo $TOTAL_MODAL_DAN_LABA_DITAHAN; 
									echo number_format($TOTAL_MODAL_DAN_LABA_DITAHAN, 2, ',', '.');
									?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:red;" />


								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

							</tr>





							<tr>
								<!-- AKTIVA -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label"></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<th style="font-size:0.550em; text-align:right;  width: 150px;border: 1px solid black; border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input"></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<!-- PASIVA  -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="250" class="neraca-col-label"></th>


								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<th style="font-size:0.550em; text-align:right; width: 150px;border: 1px solid black; border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input"></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

							</tr>









							<tr>
								<!-- AKTIVA -->

								<th style="font-size: 0.550em; width: 310px;border: 1px solid black;border-top:none; border-bottom:none;border-right:none;  border-collapse: collapse; text-align:left;" colspan="310"><strong>TOTAL AKTIVA</strong></th>

								<!-- <th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th> -->



								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;width: 150px;border: 1px solid black;border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">

									<!-- <input type="text" class="form-control uang" onkeyup="sum();" name="total_aktiva" id="total_aktiva" placeholder="" value="" style="font-size:1vw;font-weight: bold;text-align:right;color:red;" ; /> -->


									<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php 
									// echo $GET_TOTAL_AKTIVA_LANCAR + $GET_Total_AKTIVA_TETAP_BERSIH + $GET_AKTIVA_LAIN_LAIN; 
									echo number_format($GET_TOTAL_AKTIVA_LANCAR + $GET_Total_AKTIVA_TETAP_BERSIH + $GET_AKTIVA_LAIN_LAIN, 2, ',', '.');
									?>" style="font-size:1.5vw;font-weight: bold;text-align:right;color:red;" />

								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-bottom:none;border-top:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>




								<!-- PASIVA  -->
								<th style="font-size: 0.550em;text-align:left; width: 310px;border: 1px solid black;border-bottom:none;border-top:none; border-right:none;  border-collapse: collapse;" colspan="310"><strong>TOTAL PASIVA</strong></th>

								<!-- <th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th> -->




								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; width: 150px;border: 1px solid black; border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">


									<!-- <input type="text" class="form-control uang" name="TOTAL_PASIVA" id="TOTAL_PASIVA" placeholder="" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" value="" ; /> -->

									<input type="tel" pattern="[0-9(,)]{15}" name="input_box" id="input_box" onchange="setTwoNumberDecimal" min="0" max="10" step="0,25" value="<?php 
									// echo 	$TOTAL_UTANG_LANCAR_PLUS_AFILIASI + $TOTAL_MODAL_DAN_LABA_DITAHAN; 
									echo number_format($TOTAL_UTANG_LANCAR_PLUS_AFILIASI + $TOTAL_MODAL_DAN_LABA_DITAHAN, 2, ',', '.');
									?>" style="font-size:1.5vw;font-weight: bold;text-align:right;color:red;" />

								</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-bottom:none;  border-left:none; border-top:none;border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

							</tr>










							<tr>
								<!-- AKTIVA -->

								<th style="font-size: 0.550em; width: 310px;border: 1px solid black;border-top:none;border-bottom:none; border-right:none;  border-collapse: collapse; text-align:left;" colspan="310"><strong></strong></th>

								<!-- <th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th> -->



								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<th style="font-size:0.550em; text-align:right; border-top:none;width: 150px;border: 1px solid black;border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input"></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none; border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>




								<!-- PASIVA  -->
								<th style="font-size: 0.550em;text-align:left; width: 310px;border: 1px solid black;border-top:none;border-bottom:none; border-right:none;  border-collapse: collapse;" colspan="310"></th>

								<!-- <th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th> -->




								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<th style="font-size:0.550em; text-align:right; width: 150px;border: 1px solid black; border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input"></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-bottom:none; border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

							</tr>

							<!-- Jarak dengan garis kotak paling bawah -->

							<tr>
								<!-- AKTIVA -->

								<th style="font-size: 0.550em; width: 310px;height: 10px; border: 1px solid black;border-top:none; border-right:none;  border-collapse: collapse; text-align:left;" colspan="310"><strong></strong></th>

								<!-- <th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th> -->

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<th style="font-size:0.550em; text-align:right; border-top:none;width: 150px;border: 1px solid black;border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input"></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<!-- PASIVA  -->
								<th style="font-size: 0.550em;text-align:left; width: 310px;border: 1px solid black;border-top:none; border-right:none;  border-collapse: collapse;" colspan="310"></th>

								<!-- <th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th> -->

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<th style="font-size:0.550em; text-align:right; width: 150px;border: 1px solid black; border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input"></th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-left:none; border-top:none;border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

							</tr>

						</table>
					</div>
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
					<!-- <button type="submit" class="btn btn-primary"><?php //echo $button; 
																		?></button> -->

					<?php
					if ($button == "Update") {
						$this->load->helper('dashboard_laporan_publish');
						if (!empty($bulan_transaksi) && (int) $bulan_transaksi > 0 && dashboard_laporan_is_published($this, 'neraca', $tahun_neraca, $bulan_transaksi)) {
					?>
						<a href="<?php echo site_url('tbl_neraca_data/neraca_cetak/' . $tahun_neraca . '/' . $bulan_transaksi)
									?>" class="btn btn-success" target="_blank">Cetak Neraca (PDF)</a>
					<?php
						}
					} ?>



				</div>
				<div class="col-4"></div>
			</div>

		</div>


		<!-- </div> -->

	</div>
</div>






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

			// var huruf = document.getElementById('kas').value;
			// var cek_decimal = huruf.split(",");


			// koma_belakang = cek_decimal[1];

			// Xcv = cek_decimal[0];
			// const str = Xcv;
			// const substr = '-';

			// if (str.includes(substr) == true) {
			// 	var var_minus = "-";
			// } else {
			// 	var var_minus = "";
			// }

			// Ycv = Xcv.replace(/[^0-9]/g, '');


			// if (!isNaN(Ycv)) {

			// 	var var_rupiah_kas = format_rupiah(Ycv);
			// 	var rupiah_format = var_rupiah_kas.replace(/,/g, ".");

			// 	if (!isNaN(koma_belakang)) {
			// 		document.getElementById('kas').value = var_minus + rupiah_format + "," + koma_belakang;



			// 		alert(koma_belakang.length);

			// 			alert("koma" + koma_belakang);
			// 			var va_kas = var_minus + rupiah_format + "," + koma_belakang;

			// 			alert("parsefloat" + parseFloat(koma_belakang / 10));
			// 			alert(parseInt(va_kas));

			// 			var cv = (parseInt(va_kas) + parseFloat(koma_belakang / 10)).toFixed(2);
			// 			alert("var:" + cv);
			// 			cb = parseFloat(cv * 10) + parseFloat(cv * 10);
			// 			alert(cb);
			// 			cd = parseFloat(cb / 10);
			// 			alert("total =" + cd);



			// 	} else {
			// 		document.getElementById('kas').value = var_minus + rupiah_format;
			// 		$va_kas = var_minus + rupiah_format;
			// 	}


			// }


			// ==================================

			// va_test = parseFloat($va_kas).toFixed(2) + parseFloat($va_kas).toFixed(2);
			// alert("VAS KAS" + $va_kas);
			// alert("VAS va_test" + va_test);

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

		if (!isNaN(parseInt(document.getElementById('aset_lain_lain').value.replace(/[^0-9]/g, '')))) {
			$va_aset_lain_lain = parseInt(document.getElementById('aset_lain_lain').value.replace(/[^0-9]/g, ''));
		} else {
			$va_aset_lain_lain = 0;
		}




		var result_total_aktiva_lain_lain = parseInt($va_piutang_non_usaha_pihak_ketiga) + parseInt($va_piutang_non_usaha_radio) + parseInt($va_ljpj_taman_gedung_kesenian_gabusan) + parseInt($va_ljpj_kompleks_gedung_kesenian) + parseInt($va_ljpj_radio) + parseInt($va_ljpj_kerjasama_operasi_apotek_dharma_usaha) + parseInt($va_ljpj_peternakan) + parseInt($va_ljpj_kerjasama_adwm) + parseInt($va_ljpj_kerjasama_pdu_cabean_panggungharjo) + parseInt($va_aset_lain_lain);

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

<?php $this->load->view('anekadharma/tbl_neraca_data/partials/modal_neraca_kode_akun'); ?>