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
								<th class="neraca-col-label" style="font-size:0.550em;border:1px solid black;border-top:none;border-bottom:none;border-right:none;border-left:none;border-collapse:collapse;text-align:left;" colspan="250">
									<div class="neraca-label-wrap">
										<span class="neraca-label-text">Kas</span>
										<span class="neraca-label-setting">
											<button type="button" class="btn btn-warning btn-xs btn-neraca-get-kode-akun-form" data-field-neraca="kas"><i class="fa fa-cog"></i> Setting Kode Akun Kas</button>
										</span>
									</div>
								</th>
								<th class="neraca-col-rp" style="font-size:0.550em;border:1px solid black;border-top:none;border-bottom:none;border-right:none;border-left:none;border-collapse:collapse;" colspan="25">Rp.</th>
								<th class="neraca-col-input" style="font-size:0.550em;border:1px solid black;border-top:none;border-bottom:none;border-right:none;border-left:none;border-collapse:collapse;" colspan="195">
									<div class="neraca-calc-legacy" style="display:none;">
										<?php
										$sql = "SELECT * FROM `tbl_neraca_data` WHERE `tahun_transaksi`='$tahun_neraca' And `bulan_transaksi`='$bulan_transaksi' ";
										$GET_tbl_neraca_data_RECORD = $this->db->query($sql);
										if ($GET_tbl_neraca_data_RECORD->num_rows() > 0) {
											$GET_kas = $GET_tbl_neraca_data_RECORD->row()->kas;
										} else {
											$GET_kas = 0;
										}
										$jurnal_kas_kas = neraca_calc_jurnal_kas($this, 'kas', $tahun_neraca, $bulan_transaksi);
										$GET_debet_11101 = $jurnal_kas_kas['debet'];
										$GET_kredit_11101 = $jurnal_kas_kas['kredit'];
										$GET_TOTAL_KAS = $GET_kas + $GET_debet_11101 - $GET_kredit_11101;
										echo $GET_TOTAL_KAS;
										?>
									</div>
									<form action="<?php echo $action . '/kas'; ?>" method="post" class="neraca-kode-akun-form" data-field-neraca="kas">
										<input type="tel" name="input_box" id="input_box_kas" onchange="setTwoNumberDecimal" value="<?php
										echo number_format($data_tbl_neraca_data->kas, 2, ',', '.');
										$GET_TOTAL_AKTIVA_LANCAR = $GET_TOTAL_AKTIVA_LANCAR + $data_tbl_neraca_data->kas;
										?>" />
										<button type="submit" class="btn btn-success btn-xs">Simpan</button>
									</form>
								</th>
								<th class="neraca-col-center" style="font-size:0.550em;border:1px solid black;border-top:none;border-bottom:none;border-left:none;border-collapse:collapse;" colspan="5"></th>

								<!-- PASIVA  -->


								<th style="font-size:0.550em; text-align:left; width: 10px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;  border-collapse: collapse;" colspan="25" class="neraca-col-gap"></th>

								<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="250" class="neraca-col-label">Utang Usaha</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">



									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="sm-4">

											<?php

											// GET debet dari saldo akhir tahun sebelumnya
											$sql = "SELECT * FROM `tbl_neraca_data` WHERE `tahun_transaksi`='$tahun_neraca' And `bulan_transaksi`='$bulan_transaksi' ";
											$GET_tbl_neraca_data_RECORD = $this->db->query($sql);

											if ($GET_tbl_neraca_data_RECORD->num_rows() > 0) {

												$GET_utang_usaha = $GET_tbl_neraca_data_RECORD->row()->utang_usaha;
											} else {
												$GET_utang_usaha = 0;
											}

											// GET debet per kode akun di penerimaan kas


											// LOOPING sys_kode filter group: bank

											$jurnal_kas_utang_usaha = neraca_calc_jurnal_kas($this, 'utang_usaha', $tahun_neraca, $bulan_transaksi, 'utang_usaha');
											$TOTAL_DEBET_utang_usaha = $jurnal_kas_utang_usaha['debet'];
											$TOTAL_KREDIT_utang_usaha = $jurnal_kas_utang_usaha['kredit'];


											$GET_utang_usaha = $GET_utang_usaha + $TOTAL_DEBET_utang_usaha - $TOTAL_KREDIT_utang_usaha;

											echo  $GET_utang_usaha;

											?>

										</div>
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

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label">Bank</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">

									<!--  -->

									<!-- <input type="tel" pattern="[0-9(,)]{15}" name="bank" id="bank" onKeyPress="return goodchars(event,'0123456789.,-',this)" min="0" max="10" step="0,25" value="<?php //echo $data_tbl_neraca_data->bank; 
																																																		?>" style="font-size:1.1vw;font-weight: bold;text-align:right;color:black;" /> -->

									<div class="row">
										<div class="sm-4" align="left" style="color:#f9032f;text-align:left;">
											<?php

											// GET debet dari saldo akhir tahun sebelumnya
											$sql = "SELECT * FROM `tbl_neraca_data` WHERE `tahun_transaksi`='$tahun_neraca' And `bulan_transaksi`='$bulan_transaksi' ";
											$GET_tbl_neraca_data_RECORD = $this->db->query($sql);

											if ($GET_tbl_neraca_data_RECORD->num_rows() > 0) {
												// echo "Ada record";
												$GET_bank = $GET_tbl_neraca_data_RECORD->row()->bank;
											} else {
												$GET_bank = 0;
												// echo "Tidak ada record";
											}

											// GET debet per kode akun di penerimaan kas

											// LOOPING sys_kode filter group: bank

											$jurnal_kas_bank = neraca_calc_jurnal_kas($this, 'bank', $tahun_neraca, $bulan_transaksi, 'BANK');
											$TOTAL_DEBET_BANK = $jurnal_kas_bank['debet'];
											$TOTAL_KREDIT_BANK = $jurnal_kas_bank['kredit'];
											// die;



											// $Kode_akun = "11101";
											// // BULANAN
											// // $sql = "SELECT sum(`debet`) as debet_11101 FROM `jurnal_kas` WHERE year(`tanggal`)='$tahun_neraca' AND Month(`tanggal`)='$bulan_transaksi' AND `kode_akun`='$Kode_akun'";

											// // TAHUNAN
											// $sql = "SELECT sum(`debet`) as debet_11101 FROM `jurnal_kas` WHERE year(`tanggal`)='$tahun_neraca'  AND `kode_akun`='$Kode_akun'";
											// $GET_DATA_record_11101 = $this->db->query($sql);

											// if ($GET_DATA_record_11101->num_rows() > 0) {
											// 	// echo "Ada debet_11101";
											// 	// print_r($GET_DATA_record_11101->row());
											// 	$GET_debet_11101 = $GET_DATA_record_11101->row()->debet_11101;
											// 	// echo $GET_debet_11101;
											// } else {
											// 	$GET_debet_11101 = 0;
											// 	// echo "Tidak ada debet_11101";
											// }


											// // GET debet pengeluaran kas
											// $sql = "SELECT sum(`kredit`) as kredit_11101 FROM `jurnal_kas` WHERE year(`tanggal`)='$tahun_neraca'  AND `kode_akun`='$Kode_akun'";
											// $GET_DATA_record_kredit_11101 = $this->db->query($sql);

											// if ($GET_DATA_record_kredit_11101->num_rows() > 0) {
											// 	// echo "Ada debet_11101";
											// 	// print_r($GET_DATA_record_11101->row());
											// 	$GET_kredit_11101 = $GET_DATA_record_kredit_11101->row()->kredit_11101;
											// 	// echo $GET_kredit_11101;
											// } else {
											// 	$GET_kredit_11101 = 0;
											// 	// echo "Tidak ada debet_11101";
											// }

											// bank : 11105 s/d 11111  ( debet dari saldo akhir tahun sebelumnya + debet per kode akun di penerimaan kas - debet pengeluaran kas ).
											// echo $TOTAL_DEBET_BANK;

											$TOTAL_BANK = $GET_bank + $TOTAL_DEBET_BANK - $TOTAL_KREDIT_BANK;
											// echo "TOTAL Kas: " . $GET_kas ;
											// echo "TOTAL Kas: " . $GET_debet_11101;
											// echo "TOTAL Kas: " . $GET_kredit_11101;
											echo $TOTAL_BANK;

											?>

										</div>
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

								<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="250" class="neraca-col-label">Utang Pajak</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">

									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="sm-4">

											<?php

											// GET debet dari saldo akhir tahun sebelumnya
											$sql = "SELECT * FROM `tbl_neraca_data` WHERE `tahun_transaksi`='$tahun_neraca' And `bulan_transaksi`='$bulan_transaksi' ";
											$GET_tbl_neraca_data_RECORD = $this->db->query($sql);

											if ($GET_tbl_neraca_data_RECORD->num_rows() > 0) {

												$GET_utang_pajak = $GET_tbl_neraca_data_RECORD->row()->utang_pajak;
											} else {
												$GET_utang_pajak = 0;
											}

											// GET debet per kode akun di penerimaan kas


											// LOOPING sys_kode filter group: bank

											$jurnal_kas_utang_pajak = neraca_calc_jurnal_kas($this, 'utang_pajak', $tahun_neraca, $bulan_transaksi, 'utang_usaha');
											$TOTAL_DEBET_utang_pajak = $jurnal_kas_utang_pajak['debet'];
											$TOTAL_KREDIT_utang_pajak = $jurnal_kas_utang_pajak['kredit'];


											$GET_utang_pajak = $GET_utang_pajak + $TOTAL_DEBET_utang_pajak - $TOTAL_KREDIT_utang_pajak;

											// echo  $GET_utang_pajak;
											echo number_format($GET_utang_pajak, 2, ',', '.');

											?>

										</div>
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

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label">Piutang Usaha</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">

									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="sm-4">

											<?php

											// GET debet dari saldo akhir tahun sebelumnya
											$sql = "SELECT * FROM `tbl_neraca_data` WHERE `tahun_transaksi`='$tahun_neraca' And `bulan_transaksi`='$bulan_transaksi' ";
											$GET_tbl_neraca_data_RECORD = $this->db->query($sql);

											if ($GET_tbl_neraca_data_RECORD->num_rows() > 0) {
												// echo "Ada record";
												$GET_piutang_usaha = $GET_tbl_neraca_data_RECORD->row()->piutang_usaha;
											} else {
												$GET_piutang_usaha = 0;
												// echo "Tidak ada record";
											}

											// GET debet per kode akun di penerimaan kas


											// LOOPING sys_kode filter group: bank

											$jurnal_kas_piutang_usaha = neraca_calc_jurnal_kas($this, 'piutang_usaha', $tahun_neraca, $bulan_transaksi, 'PIUTANGUSAHA');
											$TOTAL_DEBET_PIUTANGUSAHA = $jurnal_kas_piutang_usaha['debet'];
											$TOTAL_KREDIT_PIUTANGUSAHA = $jurnal_kas_piutang_usaha['kredit'];
											// 1. kas : 11101  ( debet dari saldo akhir tahun sebelumnya + debet per kode akun di penerimaan kas - debet pengeluaran kas ).
											$GET_TOTAL_piutang_usaha = $GET_piutang_usaha + $TOTAL_DEBET_PIUTANGUSAHA - $TOTAL_KREDIT_PIUTANGUSAHA;
											// echo "TOTAL Kas: " . $GET_kas ;
											// echo "TOTAL Kas: " . $GET_debet_11101;
											// echo "TOTAL Kas: " . $GET_kredit_11101;
											echo  $GET_TOTAL_piutang_usaha;

											?>

										</div>
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

								<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="250" class="neraca-col-label">Utang Lain-lain</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">
									<strong>


										<div class="row" align="left" style="color:#f9032f;text-align:left;">
											<div class="sm-6">

												<?php

												// GET debet dari saldo akhir tahun sebelumnya
												$sql = "SELECT * FROM `tbl_neraca_data` WHERE `tahun_transaksi`='$tahun_neraca' And `bulan_transaksi`='$bulan_transaksi' ";
												$GET_tbl_neraca_data_RECORD = $this->db->query($sql);

												if ($GET_tbl_neraca_data_RECORD->num_rows() > 0) {

													$GET_utang_lain_lain = $GET_tbl_neraca_data_RECORD->row()->utang_lain_lain;
												} else {
													$GET_utang_lain_lain = 0;
												}

												// GET debet per kode akun di penerimaan kas


												// LOOPING sys_kode filter group: bank

												$jurnal_kas_utang_lain_lain = neraca_calc_jurnal_kas($this, 'utang_lain_lain', $tahun_neraca, $bulan_transaksi, 'utang_lain_lain');
												$TOTAL_DEBET_utang_lain_lain = $jurnal_kas_utang_lain_lain['debet'];
												$TOTAL_KREDIT_utang_lain_lain = $jurnal_kas_utang_lain_lain['kredit'];


												$GET_utang_lain_lain = $GET_utang_lain_lain + $TOTAL_DEBET_utang_lain_lain - $TOTAL_KREDIT_utang_lain_lain;

												echo $GET_utang_lain_lain;

												?>

											</div>
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

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label">Piutang Non Usaha</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">



									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="sm-4">

											<?php

											// GET debet dari saldo akhir tahun sebelumnya
											$sql = "SELECT * FROM `tbl_neraca_data` WHERE `tahun_transaksi`='$tahun_neraca' And `bulan_transaksi`='$bulan_transaksi' ";
											$GET_tbl_neraca_data_RECORD = $this->db->query($sql);

											if ($GET_tbl_neraca_data_RECORD->num_rows() > 0) {
												// echo "Ada record";
												$GET_piutang_non_usaha = $GET_tbl_neraca_data_RECORD->row()->piutang_non_usaha;
											} else {
												$GET_piutang_non_usaha = 0;
												// echo "Tidak ada record";
											}

											// GET debet per kode akun di penerimaan kas


											// LOOPING sys_kode filter group: bank

											$jurnal_kas_piutang_non_usaha = neraca_calc_jurnal_kas($this, 'piutang_non_usaha', $tahun_neraca, $bulan_transaksi, 'PIUTANGUSAHA');
											$TOTAL_DEBET_PIUTANGNONUSAHA = $jurnal_kas_piutang_non_usaha['debet'];
											$TOTAL_KREDIT_PIUTANGNONUSAHA = $jurnal_kas_piutang_non_usaha['kredit'];
											// 1. kas : 11101  ( debet dari saldo akhir tahun sebelumnya + debet per kode akun di penerimaan kas - debet pengeluaran kas ).
											$GET_TOTAL_piutang_non_usaha = $GET_piutang_non_usaha + $TOTAL_DEBET_PIUTANGNONUSAHA - $TOTAL_KREDIT_PIUTANGNONUSAHA;
											// echo "TOTAL Kas: " . $GET_kas ;
											// echo "TOTAL Kas: " . $GET_debet_11101;
											// echo "TOTAL Kas: " . $GET_kredit_11101;
											echo $GET_TOTAL_piutang_non_usaha;

											?>

										</div>
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

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label">Persediaan</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">



									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="sm-4">

											<?php

											// GET debet dari saldo akhir tahun sebelumnya
											$sql = "SELECT * FROM `tbl_neraca_data` WHERE `tahun_transaksi`='$tahun_neraca' And `bulan_transaksi`='$bulan_transaksi' ";
											$GET_tbl_neraca_data_RECORD = $this->db->query($sql);

											if ($GET_tbl_neraca_data_RECORD->num_rows() > 0) {
												// echo "Ada record";
												$GET_persediaan = $GET_tbl_neraca_data_RECORD->row()->persediaan;
											} else {
												$GET_persediaan = 0;
												// echo "Tidak ada record";
											}

											// GET debet per kode akun di penerimaan kas


											// LOOPING sys_kode filter group: bank

											$jurnal_kas_persediaan = neraca_calc_jurnal_kas($this, 'persediaan', $tahun_neraca, $bulan_transaksi, 'PERSEDIAAN');
											$TOTAL_DEBET_persediaan = $jurnal_kas_persediaan['debet'];
											$TOTAL_KREDIT_persediaan = $jurnal_kas_persediaan['kredit'];
											// 1. kas : 11101  ( debet dari saldo akhir tahun sebelumnya + debet per kode akun di penerimaan kas - debet pengeluaran kas ).
											$GET_persediaan = $GET_piutang_non_usaha + $TOTAL_DEBET_persediaan - $TOTAL_KREDIT_persediaan;
											// echo "TOTAL Kas: " . $GET_kas ;
											// echo "TOTAL Kas: " . $GET_debet_11101;
											// echo "TOTAL Kas: " . $GET_kredit_11101;
											echo  $GET_persediaan;

											?>

										</div>
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

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label">Uang Muka Pajak</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">



									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="sm-4">

											<?php

											// GET debet dari saldo akhir tahun sebelumnya
											$sql = "SELECT * FROM `tbl_neraca_data` WHERE `tahun_transaksi`='$tahun_neraca' And `bulan_transaksi`='$bulan_transaksi' ";
											$GET_tbl_neraca_data_RECORD = $this->db->query($sql);

											if ($GET_tbl_neraca_data_RECORD->num_rows() > 0) {
												// echo "Ada record";
												$GET_uang_muka_pajak = $GET_tbl_neraca_data_RECORD->row()->uang_muka_pajak;
											} else {
												$GET_uang_muka_pajak = 0;
												// echo "Tidak ada record";
											}

											// GET debet per kode akun di penerimaan kas


											// LOOPING sys_kode filter group: bank

											$jurnal_kas_uang_muka_pajak = neraca_calc_jurnal_kas($this, 'uang_muka_pajak', $tahun_neraca, $bulan_transaksi, 'uang_muka_pajak');
											$TOTAL_DEBET_uang_muka_pajak = $jurnal_kas_uang_muka_pajak['debet'];
											$TOTAL_KREDIT_uang_muka_pajak = $jurnal_kas_uang_muka_pajak['kredit'];
											// 1. kas : 11101  ( debet dari saldo akhir tahun sebelumnya + debet per kode akun di penerimaan kas - debet pengeluaran kas ).
											$GET_uang_muka_pajak = $GET_piutang_non_usaha + $TOTAL_DEBET_uang_muka_pajak - $TOTAL_KREDIT_uang_muka_pajak;
											// echo "TOTAL Kas: " . $GET_kas ;
											// echo "TOTAL Kas: " . $GET_debet_11101;
											// echo "TOTAL Kas: " . $GET_kredit_11101;
											// echo $GET_uang_muka_pajak;


											echo number_format($GET_uang_muka_pajak, 2, ',', '.');

											?>

										</div>
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

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label">Aktiva Tetap Berwujud</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">




									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="sm-4">

											<?php

											// GET debet dari saldo akhir tahun sebelumnya
											$sql = "SELECT * FROM `tbl_neraca_data` WHERE `tahun_transaksi`='$tahun_neraca' And `bulan_transaksi`='$bulan_transaksi' ";
											$GET_tbl_neraca_data_RECORD = $this->db->query($sql);

											if ($GET_tbl_neraca_data_RECORD->num_rows() > 0) {
												// echo "Ada record";
												$GET_aktiva_tetap_berwujud = $GET_tbl_neraca_data_RECORD->row()->aktiva_tetap_berwujud;
											} else {
												$GET_aktiva_tetap_berwujud = 0;
												// echo "Tidak ada record";
											}

											// GET debet per kode akun di penerimaan kas


											// LOOPING sys_kode filter group: bank

											$jurnal_kas_aktiva_tetap_berwujud = neraca_calc_jurnal_kas($this, 'aktiva_tetap_berwujud', $tahun_neraca, $bulan_transaksi, 'aktiva_tetap_berwujud');
											$TOTAL_DEBET_aktiva_tetap_berwujud = $jurnal_kas_aktiva_tetap_berwujud['debet'];
											$TOTAL_KREDIT_aktiva_tetap_berwujud = $jurnal_kas_aktiva_tetap_berwujud['kredit'];
											// 1. kas : 11101  ( debet dari saldo akhir tahun sebelumnya + debet per kode akun di penerimaan kas - debet pengeluaran kas ).
											$GET_aktiva_tetap_berwujud = $GET_aktiva_tetap_berwujud + $TOTAL_DEBET_aktiva_tetap_berwujud - $TOTAL_KREDIT_aktiva_tetap_berwujud;
											// echo "TOTAL Kas: " . $GET_kas ;
											// echo "TOTAL Kas: " . $GET_debet_11101;
											// echo "TOTAL Kas: " . $GET_kredit_11101;
											// echo  $GET_aktiva_tetap_berwujud;
											echo number_format($GET_aktiva_tetap_berwujud, 2, ',', '.');

											?>

										</div>
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

								<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="250" class="neraca-col-label">Utang Afiliasi</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;  border-top:none;border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; width: 150px;border: 1px solid black;border-top:none;border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">

									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="sm-4">

											<?php

											// GET debet dari saldo akhir tahun sebelumnya
											$sql = "SELECT * FROM `tbl_neraca_data` WHERE `tahun_transaksi`='$tahun_neraca' And `bulan_transaksi`='$bulan_transaksi' ";
											$GET_tbl_neraca_data_RECORD = $this->db->query($sql);

											if ($GET_tbl_neraca_data_RECORD->num_rows() > 0) {

												$GET_utang_afiliasi = $GET_tbl_neraca_data_RECORD->row()->utang_afiliasi;
											} else {
												$GET_utang_afiliasi = 0;
											}

											// GET debet per kode akun di penerimaan kas


											// LOOPING sys_kode filter group: bank

											$jurnal_kas_utang_afiliasi = neraca_calc_jurnal_kas($this, 'utang_afiliasi', $tahun_neraca, $bulan_transaksi, 'utang_afiliasi');
											$TOTAL_DEBET_utang_afiliasi = $jurnal_kas_utang_afiliasi['debet'];
											$TOTAL_KREDIT_utang_afiliasi = $jurnal_kas_utang_afiliasi['kredit'];


											$GET_utang_afiliasi = $GET_utang_afiliasi + $TOTAL_DEBET_utang_afiliasi - $TOTAL_KREDIT_utang_afiliasi;

											// echo  $GET_utang_afiliasi;

											echo number_format($GET_utang_afiliasi, 2, ',', '.');


											?>

										</div>
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

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label">Akumulasi Depresiasi ATB</th>


								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">



									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="sm-4">

											<?php

											// GET debet dari saldo akhir tahun sebelumnya
											$sql = "SELECT * FROM `tbl_neraca_data` WHERE `tahun_transaksi`='$tahun_neraca' And `bulan_transaksi`='$bulan_transaksi' ";
											$GET_tbl_neraca_data_RECORD = $this->db->query($sql);

											if ($GET_tbl_neraca_data_RECORD->num_rows() > 0) {
												// echo "Ada record";
												$GET_akumulasi_depresiasi_atb = $GET_tbl_neraca_data_RECORD->row()->akumulasi_depresiasi_atb;
											} else {
												$GET_akumulasi_depresiasi_atb = 0;
												// echo "Tidak ada record";
											}

											// GET debet per kode akun di penerimaan kas


											// LOOPING sys_kode filter group: bank

											$jurnal_kas_akumulasi = neraca_calc_jurnal_kas($this, 'akumulasi_depresiasi_atb', $tahun_neraca, $bulan_transaksi, 'akumulasi_depresiasi_atb');
											$TOTAL_DEBET_akumulasi_depresiasi_atb = $jurnal_kas_akumulasi['debet'];
											$TOTAL_KREDIT_akumulasi_depresiasi_atb = $jurnal_kas_akumulasi['kredit'];
											// 1. kas : 11101  ( debet dari saldo akhir tahun sebelumnya + debet per kode akun di penerimaan kas - debet pengeluaran kas ).
											$GET_akumulasi_depresiasi_atb = $GET_akumulasi_depresiasi_atb + $TOTAL_DEBET_akumulasi_depresiasi_atb - $TOTAL_KREDIT_akumulasi_depresiasi_atb;
											// echo "TOTAL Kas: " . $GET_kas ;
											// echo "TOTAL Kas: " . $GET_debet_11101;
											// echo "TOTAL Kas: " . $GET_kredit_11101;
											// echo  $GET_akumulasi_depresiasi_atb;

											echo number_format($GET_akumulasi_depresiasi_atb, 2, ',', '.');


											?>

										</div>
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

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label">Piutang Non Usaha Pihak Ketiga</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">


									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="sm-4">

											<?php

											// GET debet dari saldo akhir tahun sebelumnya
											$sql = "SELECT * FROM `tbl_neraca_data` WHERE `tahun_transaksi`='$tahun_neraca' And `bulan_transaksi`='$bulan_transaksi' ";
											$GET_tbl_neraca_data_RECORD = $this->db->query($sql);

											if ($GET_tbl_neraca_data_RECORD->num_rows() > 0) {
												// echo "Ada record";
												$GET_piutang_non_usaha_pihak_ketiga = $GET_tbl_neraca_data_RECORD->row()->piutang_non_usaha_pihak_ketiga;
											} else {
												$GET_piutang_non_usaha_pihak_ketiga = 0;
												// echo "Tidak ada record";
											}

											// GET debet per kode akun di penerimaan kas


											// LOOPING sys_kode filter group: bank

											$jurnal_kas_piutang_pihak_ketiga = neraca_calc_jurnal_kas($this, 'piutang_non_usaha_pihak_ketiga', $tahun_neraca, $bulan_transaksi, 'PIUTANGNONUSAHAPIHAKKETIGA');
											$TOTAL_DEBET_piutang_non_usaha_pihak_ketiga = $jurnal_kas_piutang_pihak_ketiga['debet'];
											$TOTAL_KREDIT_piutang_non_usaha_pihak_ketiga = $jurnal_kas_piutang_pihak_ketiga['kredit'];
											// 1. kas : 11101  ( debet dari saldo akhir tahun sebelumnya + debet per kode akun di penerimaan kas - debet pengeluaran kas ).
											$GET_piutang_non_usaha_pihak_ketiga = $GET_piutang_non_usaha_pihak_ketiga + $TOTAL_DEBET_piutang_non_usaha_pihak_ketiga - $TOTAL_KREDIT_piutang_non_usaha_pihak_ketiga;
											// echo "TOTAL Kas: " . $GET_kas ;
											// echo "TOTAL Kas: " . $GET_debet_11101;
											// echo "TOTAL Kas: " . $GET_kredit_11101;
											// echo  $GET_piutang_non_usaha_pihak_ketiga;
											echo number_format($GET_piutang_non_usaha_pihak_ketiga, 2, ',', '.');

											?>

										</div>
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

								<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="250" class="neraca-col-label">Modal Dasar dan Penyertaan</th>


								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">



									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="sm-4">

											<?php

											// GET debet dari saldo akhir tahun sebelumnya
											$sql = "SELECT * FROM `tbl_neraca_data` WHERE `tahun_transaksi`='$tahun_neraca' And `bulan_transaksi`='$bulan_transaksi' ";
											$GET_tbl_neraca_data_RECORD = $this->db->query($sql);

											if ($GET_tbl_neraca_data_RECORD->num_rows() > 0) {

												$GET_modal_dasar_dan_penyertaan = $GET_tbl_neraca_data_RECORD->row()->modal_dasar_dan_penyertaan;
											} else {
												$GET_modal_dasar_dan_penyertaan = 0;
											}

											// GET debet per kode akun di penerimaan kas


											// LOOPING sys_kode filter group: bank

											$jurnal_kas_modal = neraca_calc_jurnal_kas($this, 'modal_dasar_dan_penyertaan', $tahun_neraca, $bulan_transaksi, 'modal_dasar_dan_penyertaan');
											$TOTAL_DEBET_modal_dasar_dan_penyertaan = $jurnal_kas_modal['debet'];
											$TOTAL_KREDIT_modal_dasar_dan_penyertaan = $jurnal_kas_modal['kredit'];


											$GET_modal_dasar_dan_penyertaan = $GET_modal_dasar_dan_penyertaan + $TOTAL_DEBET_modal_dasar_dan_penyertaan - $TOTAL_KREDIT_modal_dasar_dan_penyertaan;

											// echo  $GET_modal_dasar_dan_penyertaan;

											echo number_format($GET_modal_dasar_dan_penyertaan, 2, ',', '.');

											?>

										</div>
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

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label">Piutang Non Usaha Radio</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">

									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="sm-4">

											<?php

											// GET debet dari saldo akhir tahun sebelumnya
											$sql = "SELECT * FROM `tbl_neraca_data` WHERE `tahun_transaksi`='$tahun_neraca' And `bulan_transaksi`='$bulan_transaksi' ";
											$GET_tbl_neraca_data_RECORD = $this->db->query($sql);

											if ($GET_tbl_neraca_data_RECORD->num_rows() > 0) {
												// echo "Ada record";
												$GET_PIUTANGNONUSAHARADIO = $GET_tbl_neraca_data_RECORD->row()->piutang_non_usaha_radio;
											} else {
												$GET_PIUTANGNONUSAHARADIO = 0;
												// echo "Tidak ada record";
											}

											// GET debet per kode akun di penerimaan kas


											// LOOPING sys_kode filter group: bank

											$jurnal_kas_piutang_radio = neraca_calc_jurnal_kas($this, 'piutang_non_usaha_radio', $tahun_neraca, $bulan_transaksi, 'PIUTANGNONUSAHARADIO');
											$TOTAL_DEBET_PIUTANGNONUSAHARADIO = $jurnal_kas_piutang_radio['debet'];
											$TOTAL_KREDIT_PIUTANGNONUSAHARADIO = $jurnal_kas_piutang_radio['kredit'];
											// 1. kas : 11101  ( debet dari saldo akhir tahun sebelumnya + debet per kode akun di penerimaan kas - debet pengeluaran kas ).
											$GET_PIUTANGNONUSAHARADIO = $GET_PIUTANGNONUSAHARADIO + $TOTAL_DEBET_PIUTANGNONUSAHARADIO - $TOTAL_KREDIT_PIUTANGNONUSAHARADIO;
											// echo "TOTAL Kas: " . $GET_kas ;
											// echo "TOTAL Kas: " . $GET_debet_11101;
											// echo "TOTAL Kas: " . $GET_kredit_11101;
											// echo  $GET_PIUTANGNONUSAHARADIO;

											echo number_format($GET_PIUTANGNONUSAHARADIO, 2, ',', '.');

											?>

										</div>
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

								<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="250" class="neraca-col-label">Cadangan Umum</th>


								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">


									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="sm-4">

											<?php

											// GET debet dari saldo akhir tahun sebelumnya
											$sql = "SELECT * FROM `tbl_neraca_data` WHERE `tahun_transaksi`='$tahun_neraca' And `bulan_transaksi`='$bulan_transaksi' ";
											$GET_tbl_neraca_data_RECORD = $this->db->query($sql);

											if ($GET_tbl_neraca_data_RECORD->num_rows() > 0) {

												$GET_cadangan_umum = $GET_tbl_neraca_data_RECORD->row()->cadangan_umum;
											} else {
												$GET_cadangan_umum = 0;
											}

											// GET debet per kode akun di penerimaan kas


											// LOOPING sys_kode filter group: bank

											$jurnal_kas_cadangan = neraca_calc_jurnal_kas($this, 'cadangan_umum', $tahun_neraca, $bulan_transaksi, 'cadangan_umum');
											$TOTAL_DEBET_cadangan_umum = $jurnal_kas_cadangan['debet'];
											$TOTAL_KREDIT_cadangan_umum = $jurnal_kas_cadangan['kredit'];


											$GET_cadangan_umum = $GET_cadangan_umum + $TOTAL_DEBET_cadangan_umum - $TOTAL_KREDIT_cadangan_umum;

											// echo  $GET_cadangan_umum;

											echo number_format($GET_cadangan_umum, 2, ',', '.');

											?>

										</div>
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

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label">ljpj-Taman Gedung Kesenian Gabusan</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">


									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="sm-4">

											<?php

											// GET debet dari saldo akhir tahun sebelumnya
											$sql = "SELECT * FROM `tbl_neraca_data` WHERE `tahun_transaksi`='$tahun_neraca' And `bulan_transaksi`='$bulan_transaksi' ";
											$GET_tbl_neraca_data_RECORD = $this->db->query($sql);

											if ($GET_tbl_neraca_data_RECORD->num_rows() > 0) {
												// echo "Ada record";
												$GET_ljpj_taman_gedung_kesenian_gabusan = $GET_tbl_neraca_data_RECORD->row()->ljpj_taman_gedung_kesenian_gabusan;
											} else {
												$GET_ljpj_taman_gedung_kesenian_gabusan = 0;
												// echo "Tidak ada record";
											}

											// GET debet per kode akun di penerimaan kas


											// LOOPING sys_kode filter group: bank

											$jurnal_kas_ljpj_taman = neraca_calc_jurnal_kas($this, 'ljpj_taman_gedung_kesenian_gabusan', $tahun_neraca, $bulan_transaksi, 'ljpj_taman_gedung_kesenian_gabusan');
											$TOTAL_DEBET_ljpj_taman_gedung_kesenian_gabusan = $jurnal_kas_ljpj_taman['debet'];
											$TOTAL_KREDIT_ljpj_taman_gedung_kesenian_gabusan = $jurnal_kas_ljpj_taman['kredit'];
											// 1. kas : 11101  ( debet dari saldo akhir tahun sebelumnya + debet per kode akun di penerimaan kas - debet pengeluaran kas ).
											$GET_ljpj_taman_gedung_kesenian_gabusan = $GET_ljpj_taman_gedung_kesenian_gabusan + $TOTAL_DEBET_ljpj_taman_gedung_kesenian_gabusan - $TOTAL_KREDIT_ljpj_taman_gedung_kesenian_gabusan;
											// echo "TOTAL Kas: " . $GET_kas ;
											// echo "TOTAL Kas: " . $GET_debet_11101;
											// echo "TOTAL Kas: " . $GET_kredit_11101;
											// echo  $GET_ljpj_taman_gedung_kesenian_gabusan;

											echo number_format($GET_ljpj_taman_gedung_kesenian_gabusan, 2, ',', '.');
											?>

										</div>
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

								<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="250" class="neraca-col-label">Laba BUMD (PAD)</th>


								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="5" class="neraca-col-center"></th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">

									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="sm-4">

											<?php

											// GET debet dari saldo akhir tahun sebelumnya
											$sql = "SELECT * FROM `tbl_neraca_data` WHERE `tahun_transaksi`='$tahun_neraca' And `bulan_transaksi`='$bulan_transaksi' ";
											$GET_tbl_neraca_data_RECORD = $this->db->query($sql);

											if ($GET_tbl_neraca_data_RECORD->num_rows() > 0) {

												$GET_laba_bumd_pad = $GET_tbl_neraca_data_RECORD->row()->laba_bumd_pad;
											} else {
												$GET_laba_bumd_pad = 0;
											}

											// GET debet per kode akun di penerimaan kas


											// LOOPING sys_kode filter group: bank

											$jurnal_kas_laba_bumd = neraca_calc_jurnal_kas($this, 'laba_bumd_pad', $tahun_neraca, $bulan_transaksi, 'laba_bumd_pad');
											$TOTAL_DEBET_laba_bumd_pad = $jurnal_kas_laba_bumd['debet'];
											$TOTAL_KREDIT_laba_bumd_pad = $jurnal_kas_laba_bumd['kredit'];


											$GET_laba_bumd_pad = $GET_laba_bumd_pad + $TOTAL_DEBET_laba_bumd_pad - $TOTAL_KREDIT_laba_bumd_pad;

											// echo  $GET_laba_bumd_pad;

											echo number_format($GET_laba_bumd_pad, 2, ',', '.');

											?>

										</div>
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

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label">ljpj-Kompleks Gedung Kesenian</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">


									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="sm-4">

											<?php

											// GET debet dari saldo akhir tahun sebelumnya
											$sql = "SELECT * FROM `tbl_neraca_data` WHERE `tahun_transaksi`='$tahun_neraca' And `bulan_transaksi`='$bulan_transaksi' ";
											$GET_tbl_neraca_data_RECORD = $this->db->query($sql);

											if ($GET_tbl_neraca_data_RECORD->num_rows() > 0) {
												// echo "Ada record";
												$GET_ljpj_kompleks_gedung_kesenian = $GET_tbl_neraca_data_RECORD->row()->ljpj_kompleks_gedung_kesenian;
											} else {
												$GET_ljpj_kompleks_gedung_kesenian = 0;
												// echo "Tidak ada record";
											}

											// GET debet per kode akun di penerimaan kas


											// LOOPING sys_kode filter group: bank

											$jurnal_kas_ljpj_kompleks = neraca_calc_jurnal_kas($this, 'ljpj_kompleks_gedung_kesenian', $tahun_neraca, $bulan_transaksi, 'ljpj_kompleks_gedung_kesenian');
											$TOTAL_DEBET_ljpj_kompleks_gedung_kesenian = $jurnal_kas_ljpj_kompleks['debet'];
											$TOTAL_KREDIT_ljpj_kompleks_gedung_kesenian = $jurnal_kas_ljpj_kompleks['kredit'];
											// 1. kas : 11101  ( debet dari saldo akhir tahun sebelumnya + debet per kode akun di penerimaan kas - debet pengeluaran kas ).
											$GET_ljpj_kompleks_gedung_kesenian = $GET_ljpj_kompleks_gedung_kesenian + $TOTAL_DEBET_ljpj_kompleks_gedung_kesenian - $TOTAL_KREDIT_ljpj_kompleks_gedung_kesenian;
											// echo "TOTAL Kas: " . $GET_kas ;
											// echo "TOTAL Kas: " . $GET_debet_11101;
											// echo "TOTAL Kas: " . $GET_kredit_11101;
											// echo  $GET_ljpj_kompleks_gedung_kesenian;

											echo number_format($GET_ljpj_kompleks_gedung_kesenian, 2, ',', '.');

											?>

										</div>
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

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label">ljpj-Radio</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">


									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="sm-4">

											<?php

											// GET debet dari saldo akhir tahun sebelumnya
											$sql = "SELECT * FROM `tbl_neraca_data` WHERE `tahun_transaksi`='$tahun_neraca' And `bulan_transaksi`='$bulan_transaksi' ";
											$GET_tbl_neraca_data_RECORD = $this->db->query($sql);

											if ($GET_tbl_neraca_data_RECORD->num_rows() > 0) {

												$GET_ljpj_radio = $GET_tbl_neraca_data_RECORD->row()->ljpj_radio;
											} else {
												$GET_ljpj_radio = 0;
											}

											// GET debet per kode akun di penerimaan kas


											// LOOPING sys_kode filter group: bank

											$jurnal_kas_ljpj_radio = neraca_calc_jurnal_kas($this, 'ljpj_radio', $tahun_neraca, $bulan_transaksi, 'ljpj_radio');
											$TOTAL_DEBET_ljpj_radio = $jurnal_kas_ljpj_radio['debet'];
											$TOTAL_KREDIT_ljpj_radio = $jurnal_kas_ljpj_radio['kredit'];


											$GET_ljpj_radio = $GET_ljpj_radio + $TOTAL_DEBET_ljpj_radio - $TOTAL_KREDIT_ljpj_radio;

											// echo  $GET_ljpj_radio;

											echo number_format($GET_ljpj_radio, 2, ',', '.');

											?>

										</div>
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

								<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="250" class="neraca-col-label">Laba (Rugi) Tahun Lalu</th>


								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">


									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="sm-4">

											<?php

											// GET debet dari saldo akhir tahun sebelumnya
											$sql = "SELECT * FROM `tbl_neraca_data` WHERE `tahun_transaksi`='$tahun_neraca' And `bulan_transaksi`='$bulan_transaksi' ";
											$GET_tbl_neraca_data_RECORD = $this->db->query($sql);

											if ($GET_tbl_neraca_data_RECORD->num_rows() > 0) {

												$GET_laba_rugi_tahun_lalu = $GET_tbl_neraca_data_RECORD->row()->laba_rugi_tahun_lalu;
											} else {
												$GET_laba_rugi_tahun_lalu = 0;
											}

											// GET debet per kode akun di penerimaan kas


											// LOOPING sys_kode filter group: bank

											$jurnal_kas_lrl = neraca_calc_jurnal_kas($this, 'laba_rugi_tahun_lalu', $tahun_neraca, $bulan_transaksi, 'laba_rugi_tahun_lalu');
											$TOTAL_DEBET_laba_rugi_tahun_lalu = $jurnal_kas_lrl['debet'];
											$TOTAL_KREDIT_laba_rugi_tahun_lalu = $jurnal_kas_lrl['kredit'];


											$GET_laba_rugi_tahun_lalu = $GET_laba_rugi_tahun_lalu + $TOTAL_DEBET_laba_rugi_tahun_lalu - $TOTAL_KREDIT_laba_rugi_tahun_lalu;

											// echo  $GET_laba_rugi_tahun_lalu;

											echo number_format($GET_laba_rugi_tahun_lalu, 2, ',', '.');

											?>

										</div>
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

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label">ljpj-Kerjasama Operasi Apotek Dharma Usaha</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">


									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="sm-4">

											<?php

											// GET debet dari saldo akhir tahun sebelumnya
											$sql = "SELECT * FROM `tbl_neraca_data` WHERE `tahun_transaksi`='$tahun_neraca' And `bulan_transaksi`='$bulan_transaksi' ";
											$GET_tbl_neraca_data_RECORD = $this->db->query($sql);

											if ($GET_tbl_neraca_data_RECORD->num_rows() > 0) {

												$GET_ljpj_kerjasama_operasi_apotek_dharma_usaha = $GET_tbl_neraca_data_RECORD->row()->ljpj_kerjasama_operasi_apotek_dharma_usaha;
											} else {
												$GET_ljpj_kerjasama_operasi_apotek_dharma_usaha = 0;
											}

											// GET debet per kode akun di penerimaan kas


											// LOOPING sys_kode filter group: bank

											$jurnal_kas_ljpj_apotek = neraca_calc_jurnal_kas($this, 'ljpj_kerjasama_operasi_apotek_dharma_usaha', $tahun_neraca, $bulan_transaksi, 'ljpj_kerjasama_operasi_apotek_dharma_usaha');
											$TOTAL_DEBET_ljpj_kerjasama_operasi_apotek_dharma_usaha = $jurnal_kas_ljpj_apotek['debet'];
											$TOTAL_KREDIT_ljpj_kerjasama_operasi_apotek_dharma_usaha = $jurnal_kas_ljpj_apotek['kredit'];


											$GET_ljpj_kerjasama_operasi_apotek_dharma_usaha = $GET_ljpj_kerjasama_operasi_apotek_dharma_usaha + $TOTAL_DEBET_ljpj_kerjasama_operasi_apotek_dharma_usaha - $TOTAL_KREDIT_ljpj_kerjasama_operasi_apotek_dharma_usaha;

											// echo $GET_ljpj_kerjasama_operasi_apotek_dharma_usaha;

echo number_format($GET_ljpj_kerjasama_operasi_apotek_dharma_usaha, 2, ',', '.');

											?>

										</div>
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

								<th style="font-size: 0.550em;text-align:left; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="250" class="neraca-col-label">Laba (Rugi) Tahun Berjalan</th>


								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black; border-top:none;border-bottom:none; border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">



									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="sm-4">

											<?php

											// GET debet dari saldo akhir tahun sebelumnya
											$sql = "SELECT * FROM `tbl_neraca_data` WHERE `tahun_transaksi`='$tahun_neraca' And `bulan_transaksi`='$bulan_transaksi' ";
											$GET_tbl_neraca_data_RECORD = $this->db->query($sql);

											if ($GET_tbl_neraca_data_RECORD->num_rows() > 0) {

												$GET_laba_rugi_tahun_berjalan = $GET_tbl_neraca_data_RECORD->row()->laba_rugi_tahun_berjalan;
											} else {
												$GET_laba_rugi_tahun_berjalan = 0;
											}

											// GET debet per kode akun di penerimaan kas


											// LOOPING sys_kode filter group: bank

											$jurnal_kas_lrb = neraca_calc_jurnal_kas($this, 'laba_rugi_tahun_berjalan', $tahun_neraca, $bulan_transaksi, 'laba_rugi_tahun_berjalan');
											$TOTAL_DEBET_laba_rugi_tahun_berjalan = $jurnal_kas_lrb['debet'];
											$TOTAL_KREDIT_laba_rugi_tahun_berjalan = $jurnal_kas_lrb['kredit'];


											$GET_laba_rugi_tahun_berjalan = $GET_laba_rugi_tahun_berjalan + $TOTAL_DEBET_laba_rugi_tahun_berjalan - $TOTAL_KREDIT_laba_rugi_tahun_berjalan;

											// echo  $GET_laba_rugi_tahun_berjalan;

echo number_format($GET_laba_rugi_tahun_berjalan, 2, ',', '.');

											?>

										</div>
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

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label">ljpj-Peternakan</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">


									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="sm-4">

											<?php

											// GET debet dari saldo akhir tahun sebelumnya
											$sql = "SELECT * FROM `tbl_neraca_data` WHERE `tahun_transaksi`='$tahun_neraca' And `bulan_transaksi`='$bulan_transaksi' ";
											$GET_tbl_neraca_data_RECORD = $this->db->query($sql);

											if ($GET_tbl_neraca_data_RECORD->num_rows() > 0) {

												$GET_ljpj_peternakan = $GET_tbl_neraca_data_RECORD->row()->ljpj_peternakan;
											} else {
												$GET_ljpj_peternakan = 0;
											}

											// GET debet per kode akun di penerimaan kas


											// LOOPING sys_kode filter group: bank

											$jurnal_kas_ljpj_peternakan = neraca_calc_jurnal_kas($this, 'ljpj_peternakan', $tahun_neraca, $bulan_transaksi, 'ljpj_peternakan');
											$TOTAL_DEBET_ljpj_peternakan = $jurnal_kas_ljpj_peternakan['debet'];
											$TOTAL_KREDIT_ljpj_peternakan = $jurnal_kas_ljpj_peternakan['kredit'];


											$GET_ljpj_peternakan = $GET_ljpj_peternakan + $TOTAL_DEBET_ljpj_peternakan - $TOTAL_KREDIT_ljpj_peternakan;

											// echo  $GET_ljpj_peternakan;

											echo number_format($GET_ljpj_peternakan, 2, ',', '.');


											?>

										</div>
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

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label">ljpj-Kerjasama ADWM</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">


									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="sm-4">

											<?php

											// GET debet dari saldo akhir tahun sebelumnya
											$sql = "SELECT * FROM `tbl_neraca_data` WHERE `tahun_transaksi`='$tahun_neraca' And `bulan_transaksi`='$bulan_transaksi' ";
											$GET_tbl_neraca_data_RECORD = $this->db->query($sql);

											if ($GET_tbl_neraca_data_RECORD->num_rows() > 0) {

												$GET_ljpj_kerjasama_adwm = $GET_tbl_neraca_data_RECORD->row()->ljpj_kerjasama_adwm;
											} else {
												$GET_ljpj_kerjasama_adwm = 0;
											}

											// GET debet per kode akun di penerimaan kas


											// LOOPING sys_kode filter group: bank

											$jurnal_kas_ljpj_adwm = neraca_calc_jurnal_kas($this, 'ljpj_kerjasama_adwm', $tahun_neraca, $bulan_transaksi, 'ljpj_kerjasama_adwm');
											$TOTAL_DEBET_ljpj_kerjasama_adwm = $jurnal_kas_ljpj_adwm['debet'];
											$TOTAL_KREDIT_ljpj_kerjasama_adwm = $jurnal_kas_ljpj_adwm['kredit'];


											$GET_ljpj_kerjasama_adwm = $GET_ljpj_kerjasama_adwm + $TOTAL_DEBET_ljpj_kerjasama_adwm - $TOTAL_KREDIT_ljpj_kerjasama_adwm;

											// echo  $GET_ljpj_kerjasama_adwm;

echo number_format($GET_ljpj_kerjasama_adwm, 2, ',', '.');

											?>

										</div>
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

								<th style="font-size: 0.550em; width: 300px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse; text-align:left;" colspan="250" class="neraca-col-label">ljpj-Kerjasama PDU Cabean Panggungharjo</th>

								<th style="font-size:0.550em; text-align:left; width: 20px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="25" class="neraca-col-rp">Rp.</th>

								<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 150px;border: 1px solid black;border-top:none;border-bottom:none;  border-right:none;border-left:none;  border-collapse: collapse;" colspan="195" class="neraca-col-input">


									<div class="row" align="left" style="color:#f9032f;text-align:left;">
										<div class="sm-4">

											<?php

											// GET debet dari saldo akhir tahun sebelumnya
											$sql = "SELECT * FROM `tbl_neraca_data` WHERE `tahun_transaksi`='$tahun_neraca' And `bulan_transaksi`='$bulan_transaksi' ";
											$GET_tbl_neraca_data_RECORD = $this->db->query($sql);

											if ($GET_tbl_neraca_data_RECORD->num_rows() > 0) {

												$GET_ljpj_kerjasama_pdu_cabean_panggungharjo = $GET_tbl_neraca_data_RECORD->row()->ljpj_kerjasama_pdu_cabean_panggungharjo;
											} else {
												$GET_ljpj_kerjasama_pdu_cabean_panggungharjo = 0;
											}

											// GET debet per kode akun di penerimaan kas


											// LOOPING sys_kode filter group: bank

											$jurnal_kas_ljpj_pdu = neraca_calc_jurnal_kas($this, 'ljpj_kerjasama_pdu_cabean_panggungharjo', $tahun_neraca, $bulan_transaksi, 'ljpj_kerjasama_pdu_cabean_panggungharjo');
											$TOTAL_DEBET_ljpj_kerjasama_pdu_cabean_panggungharjo = $jurnal_kas_ljpj_pdu['debet'];
											$TOTAL_KREDIT_ljpj_kerjasama_pdu_cabean_panggungharjo = $jurnal_kas_ljpj_pdu['kredit'];


											$GET_ljpj_kerjasama_pdu_cabean_panggungharjo = $GET_ljpj_kerjasama_pdu_cabean_panggungharjo + $TOTAL_DEBET_ljpj_kerjasama_pdu_cabean_panggungharjo - $TOTAL_KREDIT_ljpj_kerjasama_pdu_cabean_panggungharjo;

											// echo  $GET_ljpj_kerjasama_pdu_cabean_panggungharjo;

echo number_format($GET_ljpj_kerjasama_pdu_cabean_panggungharjo, 2, ',', '.');


											?>

										</div>
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

<?php $this->load->view('anekadharma/tbl_neraca_data/partials/modal_neraca_kode_akun'); ?>