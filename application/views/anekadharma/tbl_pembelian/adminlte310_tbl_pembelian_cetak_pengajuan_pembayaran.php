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
					color: black;
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
									<!-- <th style="font-size:0.550em; width=60px"></th> -->
									<th style="font-size:1vw;text-align:center; width: 1000px;border: 1px solid black;border-collapse: collapse;" colspan="1000">
										<strong><u>PERMOHONAN PEMBAYARAN</u></strong>
									</th>

								</tr>






								<!-- baris 2 -->
								<tr style="border: 1px solid black; border-top: none; border-bottom: none; border-collapse: collapse;">
									<th style="font-size: 0.550em;text-align:left; width: 150px;" colspan="150">DIBAYARKAN KEPADA</th>
									<th style="font-size:0.550em; text-align:left; width: 2px" colspan="2">:</th>
									<th style="font-size:0.550em; text-align:left; width: 248px;" colspan="348">
										<input type="text" name="supplier_nama" id="supplier_nama" placeholder="Nama Supplier" value="<?php echo $supplier_nama; ?>">
									</th>
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x3</th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<th style="font-size:0.550em; text-align:left; width: 100px;" colspan="100">NO.</th>
									<th style="font-size:0.550em; text-align:left; width: 400px;" colspan="400">
										<input type="text" name="nomor_permohonan" id="nomor_permohonan" placeholder="Nomor Permohonan" value="" required>
									</th>
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="98"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
								</tr>


								<?php

								// $jumlah_nominal = 1530093;
								// echo terbilang($angka);

								?>



								<tr style="border: 1px solid black; border-top: none; border-bottom: none; border-collapse: collapse;">

									<th style="font-size:0.550em; text-align:left; width: 150px;" colspan="150">JUMLAH</th>

									<th style="font-size:0.550em; text-align:left; width: 2px" colspan="2">:</th>

									<th style="font-size:0.550em; text-align:left; width: 348px;" colspan="348">
										<input type="text" style="font-size:1.2em;" class="form-control" name="jumlah_nominal" id="jumlah_nominal" placeholder="Nomor Permohonan" value="<?php 
										echo number_format($nominal_pengajuan, 2, ',', '.') ;										
										?>">
									</th>

									<th style="font-size:0.550em; text-align:left; width: 100px;" colspan="100">TANGGAL PEMBAYARAN</th>

									<th style="font-size:0.550em; text-align:left; width: 400px;" colspan="400">
										<input type="text" class="form-control datetimepicker-input" data-target="#tgl_pembayaran" id="tgl_pembayaran" name="tgl_pembayaran" required />
										<div class="input-group-append" data-target="#tgl_pembayaran" data-toggle="datetimepicker">
											<div class="input-group-text">
												<i class="fa fa-calendar"></i>
											</div>

										</div>
									</th>

									<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="98"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x6</th> -->

								</tr>

								<tr style="border: 1px solid black; border-top: none; border-bottom: none; border-collapse: collapse;">
									<th style="font-size:0.550em; text-align:left; width: 150px;" colspan="150">TERBILANG</th>
									<th style="font-size:0.550em; text-align:left; width: 2px" colspan="2"></th>
									<th style="font-size:0.550em; text-align:left; width: 348px;" colspan="848"><input type="text" name="terbilang" id="terbilang" placeholder="Terbilang" value="<?php echo terbilang($nominal_pengajuan); ?>"></th>
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">TANGGAL</th> -->
									<!-- <th style="font-size:0.550em; width: 2px;" colspan="2">:</th> -->
									<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="500"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x5</th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x6</th> -->
								</tr>

								<tr style="border: 1px solid black; border-top: none; border-bottom: none; border-collapse: collapse;">
									<!-- <th style="font-size:0.550em; width=60px"></th> -->
									<th style="font-size:1vw;text-align:center; width: 1000px;height:25px" colspan="1000" align="center">
										<strong></strong>
									</th>
								</tr>

								<tr style="border: 1px solid black; border-bottom: none; border-collapse: collapse;">
									<th style="font-size:0.550em; text-align:left; width: 150px;" colspan="150">KETERANGAN</th>
									<th style="font-size:0.550em; text-align:left; width: 2px" colspan="2">:</th>
									<th style="font-size:0.550em; text-align:left; width: 348px;" colspan="450">
										<input type="text" name="keterangan" id="keterangan" placeholder="keterangan" value="">
									</th>
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
									<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
									<th style="font-size:0.550em; text-align:left; width: 100px;" colspan="100">JATUH TEMPO</th>
									<th style="font-size:0.550em; width: 100px;" colspan="298">
										<input type="text" class="form-control datetimepicker-input" data-target="#tgl_jatuh_tempo" id="tgl_jatuh_tempo" name="tgl_jatuh_tempo" required />
										<div class="input-group-append" data-target="#tgl_jatuh_tempo" data-toggle="datetimepicker">
											<div class="input-group-text">
												<i class="fa fa-calendar"></i>
											</div>

										</div>
									</th>
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x5</th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x6</th> -->
								</tr>


								<tr style="border: 1px solid black; border-top: none; border-bottom: none; border-collapse: collapse;">

									<th style="font-size:0.550em; text-align:left; width: 150px;" colspan="150">No. Faktur</th>
									<th style="font-size:0.550em; text-align:left; width: 2px" colspan="2">:</th>
									<th style="font-size:0.550em; text-align:left; width: 348px;" colspan="450">
										<input type="text" name="nomor_faktur" id="nomor_faktur" placeholder="nomor_faktur" value="">
									</th>


									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
									<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
									<th style="font-size:0.550em; text-align:left; width: 100px;" colspan="398">ditransfer ke Rek.
										<select name="uuid_bank" id="uuid_bank" class="form-control select2" style="width: 80%; height: 5px;" required>
											<option value="">Pilih Bank</option>
											<?php

											$sql = "select * from sys_bank  order by  nama_bank ASC ";


											foreach ($this->db->query($sql)->result() as $m) {
												// foreach ($data_produk as $m) {
												echo "<option value='$m->uuid_bank' ";
												echo ">  " . strtoupper($m->nama_bank)  . "</option>";
											}
											?>
										</select>
									</th>
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="298"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x5</th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x6</th> -->
								</tr>



								<tr style="border: 1px solid black; border-top: none; border-bottom: none; border-collapse: collapse;">
									<th style="font-size:0.550em; width: 602px;" colspan="602"></th>
									<!-- <th style="font-size:0.550em; width: 2px" colspan="2">:</th> -->
									<!-- <th style="font-size:0.550em; width: 348px;" colspan="450"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
									<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
									<th style="font-size:0.550em; text-align:left; width: 100px;" colspan="398">
										<input type="text" name="nomor_rekening" id="nomor_rekening" placeholder="Nomor rekening" value="" required>
									</th>
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="298"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x5</th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x6</th> -->
								</tr>



								<tr style="border: 1px solid black; border-top: none; border-bottom: none; border-collapse: collapse;">
									<th style="font-size:0.550em; width: 302px;" colspan="302"></th>
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<th style="font-size:0.550em; width: 100px;" colspan="100"></th>
									<th style="font-size:0.550em; width: 100px;" colspan="100"></th>
									<th style="font-size:0.550em; width: 100px;" colspan="100"></th>


									<th style="font-size:0.550em; text-align:left; width: 100px;" colspan="398">a.n
										<input type="text" name="atas_nama_rekening" id="atas_nama_rekening" placeholder="Atas Nama" value="" required>
									</th>
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="298"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x5</th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x6</th> -->
								</tr>

								<tr style="border: 1px solid black; border-top: none;  border-collapse: collapse;">
									<!-- <th style="font-size:0.550em; width=60px"></th> -->
									<th style="font-size:1vw;text-align:center; width: 1000px;height:25px" colspan="1000" align="center">
										<strong></strong>
									</th>
								</tr>



								<tr style="border: 1px solid black; border-top: none; border-bottom: none; border-collapse: collapse;">
									<th style="font-size:0.550em; text-align:left; width: 150px;" colspan="150">SPOP No.</th>
									<th style="font-size:0.550em; text-align:left; width: 2px" colspan="2">:</th>
									<th style="font-size:0.550em; text-align:left; width: 348px;" colspan="348"><?php echo $spop; ?></th>
									<th style="font-size:0.550em; text-align:left; width: 100px;" colspan="100">BKK NO.</th>
									<th style="font-size:0.550em; text-align:left; width: 100px;" colspan="100">
										<input type="text" name="nomor_bkk" id="nomor_bkk" placeholder="Nomor BKK" value="" required>
									</th>
									<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="98"></th> -->
									<th style="font-size:0.550em; text-align:left; width: 100px;" colspan="100">TANGGAL BKK</th>
									<th style="font-size:0.550em; text-align:left; width: 200px;" colspan="200">
										<input type="text" class="form-control datetimepicker-input" data-target="#tgl_nomor_bkk" id="tgl_nomor_bkk" name="tgl_nomor_bkk" required />
										<div class="input-group-append" data-target="#tgl_nomor_bkk" data-toggle="datetimepicker">
											<div class="input-group-text">
												<i class="fa fa-calendar"></i>
											</div>

										</div>
									</th>
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x5</th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x6</th> -->
								</tr>


								<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
								<script type="text/javascript">
									// the selector will match all input controls of type :checkbox
									// and attach a click event handler 
									$("input:checkbox").on('click', function() {
										// in the handler, 'this' refers to the box clicked on
										var $box = $(this);
										if ($box.is(":checked")) {
											// the name of the box is retrieved using the .attr() method
											// as it is assumed and expected to be immutable
											var group = "input:checkbox[name='" + $box.attr("name") + "']";
											// the checked state of the group/box on the other hand will change
											// and the current value is retrieved using .prop() method
											$(group).prop("checked", false);
											$box.prop("checked", true);
										} else {
											$box.prop("checked", false);
										}
									});
								</script>



								<tr style="border: 1px solid black; border-top: none; border-bottom: none; border-collapse: collapse;">
									<th style="font-size:0.550em; text-align:left; width: 150px;" colspan="150">TANGGAL</th>
									<th style="font-size:0.550em; text-align:left; width: 2px" colspan="2">:</th>
									<th style="font-size:0.550em; text-align:left; width: 348px;" colspan="348"><?php echo $tgl_po; ?></th>
									<th style="font-size:0.550em; text-align:left; width: 100px;" colspan="102">
										BANK &ensp;
										<!-- ( <input type="checkbox" class="radio" value="1" name="bank_checkbox" id="bank_checkbox" />) -->
										<select name="uuid_bank_bkk" id="uuid_bank_bkk" class="form-control select2" style="width: 80%;">
											<option value="">Pilih Bank </option>
											<?php

											$sql = "select * from sys_bank  order by  nama_bank ASC ";


											foreach ($this->db->query($sql)->result() as $m) {
												// foreach ($data_produk as $m) {
												echo "<option value='$m->uuid_bank' ";
												echo ">  " . strtoupper($m->nama_bank)  . "</option>";
											}
											?>
										</select>
									</th>
									<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
									<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
									<th style="font-size:0.550em; text-align:left; width: 398px;" colspan="398">
										KAS &ensp; (<input type="checkbox" class="radio" value="1" name="kas_checkbox" id="kas_checkbox" />)


									</th>
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 200px;" colspan="200">:</th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x5</th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x6</th> -->
								</tr>



								<tr style="border: 1px solid black; border-top: none; border-bottom: none; border-collapse: collapse;">
									<th style="font-size:0.550em; text-align:left; width: 150px;" colspan="150"></th>
									<th style="font-size:0.550em; text-align:left; width: 2px" colspan="2">:</th>
									<th style="font-size:0.550em; text-align:left; width: 348px;" colspan="348"></th>
									<th style="font-size:0.550em; text-align:left; width: 100px;" colspan="102">
										<input type="text" name="nomor_rekening_bkk" id="nomor_rekening_bkk" placeholder="Nomor rekening" value="">
									</th>
									<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
									<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
									<th style="font-size:0.550em; text-align:left; width: 398px;" colspan="398">
										<!-- KAS &ensp; (<input type="checkbox" class="radio" value="1" name="kas_checkbox" id="kas_checkbox" />) -->


									</th>
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 200px;" colspan="200">:</th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x5</th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x6</th> -->
								</tr>



								<tr style="border: 1px solid black; border-top: none; border-bottom: none; border-collapse: collapse;">
									<th style="font-size:0.550em; text-align:left; width: 150px;" colspan="150"></th>
									<th style="font-size:0.550em; text-align:left; width: 2px" colspan="2">:</th>
									<th style="font-size:0.550em; text-align:left; width: 348px;" colspan="348"></th>
									<th style="font-size:0.550em; text-align:left; width: 100px;" colspan="102">
										<input type="text" name="atas_nama_rekening_bkk" id="atas_nama_rekening_bkk" placeholder="Atas Nama" value="">
									</th>
									<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
									<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
									<th style="font-size:0.550em; text-align:left; width: 398px;" colspan="398">
										<!-- KAS &ensp; (<input type="checkbox" class="radio" value="1" name="kas_checkbox" id="kas_checkbox" />) -->


									</th>
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 200px;" colspan="200">:</th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x5</th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x6</th> -->
								</tr>






								<script type="text/javascript">
									$('input[type="checkbox"]').on('change', function() {
										$('input[type="checkbox"]').not(this).prop('checked', false);
									});
								</script>


								<tr style="border: 1px solid black; border-top: none; border-collapse: collapse;">
									<th style="font-size:0.550em; text-align:left; width: 150px;" colspan="150">ACCOUNT</th>
									<th style="font-size:0.550em; text-align:left; width: 2px" colspan="2">:</th>
									<th style="font-size:0.550em; text-align:left; width: 300px;" colspan="300">
										<!-- <input type="text" name="account" id="account" placeholder="Nama Unit" value="" required> -->
										<select name="uuid_unit" id="uuid_unit" class="form-control select2" style="width: 80%; height: 3px;" required>
											<option value="<?php //echo $uuid_konsumen 
															?>"><?php //echo $nama_konsumen 
																?></option>
											<?php

											// Data Unit
											$sql = "select * from sys_unit order by nama_unit ASC ";
											foreach ($this->db->query($sql)->result() as $m) {
												echo "<option value='$m->uuid_unit' ";
												echo ">  " . strtoupper($m->nama_unit)  . "</option>";
											}

											?>
										</select>
									</th>
									<th style="font-size:0.550em; text-align:left; width: 50px" colspan="50">:</th>
									<th style="font-size:0.550em; text-align:left; width: 100px;" colspan="100">CEK/GIRO NO</th>
									<th style="font-size:0.550em; text-align:left; width: 400px;" colspan="400">
										<input type="text" name="nomor_cek_giro" id="nomor_cek_giro" placeholder="Nomor CEK/GIRO" value="" required>
									</th>
									<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="398">KAS &ensp; (&ensp;&ensp;)</th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 200px;" colspan="200">:</th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x5</th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x6</th> -->
								</tr>


								<tr style="border: 1px solid black; border-top: none; border-bottom: none; border-collapse: collapse;">
									<th style="font-size:0.550em;text-align:center; width: 602px;border: 1px solid black; border-bottom: none; border-collapse: collapse;" colspan="602">PERSETUJUAN</th>
									<!-- <th style="font-size:0.550em; width: 2px" colspan="2"></th> -->
									<!-- <th style="font-size:0.550em; width: 348px;" colspan="348"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
									<th style="font-size:0.550em; text-align:center;width: 398px;border: 1px solid black; border-bottom: none; border-collapse: collapse;" colspan="398">PEMOHON</th>
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 200px;" colspan="200">:</th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x5</th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x6</th> -->
								</tr>

								<tr style="border: 1px solid black; border-top: none; border-bottom: none; border-collapse: collapse;">
									<th style="font-size:0.550em;text-align:center; width: 602px;height: 50px;border: 1px solid black; border-top: none;border-bottom: none; border-collapse: collapse;" colspan="602"></th>
									<!-- <th style="font-size:0.550em; width: 2px" colspan="2"></th> -->
									<!-- <th style="font-size:0.550em; width: 348px;" colspan="348"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
									<th style="font-size:0.550em; text-align:center;width: 398px;height: 50px;border: 1px solid black;border-top: none;border-bottom: none;  border-collapse: collapse;" colspan="398"></th>
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 200px;" colspan="200">:</th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x5</th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x6</th> -->
								</tr>




								<tr style="border: 1px solid black; border-top: none; border-bottom: none; border-collapse: collapse;text-align:center">
									<th style="font-size:0.550em; text-align:center;width: 300px;border: 1px solid black; border-top: none; border-bottom: none;border-right: none; border-collapse: collapse;" colspan="300">
										<u>
											<input type="text" name="nama_direktur" id="nama_direktur" placeholder="Yuli Budi Sasangka,ST" value="<?php echo $nama_direktur; ?>" required>
										</u>
									</th>
									<!-- <th style="font-size:0.550em; width: 2px" colspan="2"></th> -->
									<!-- <th style="font-size:0.550em; width: 148px;" colspan="148"></th> -->
									<!-- <th style="font-size:0.550em; width: 200px;" colspan="200"></th> -->
									<th style="font-size:0.550em; text-align:center;width: 302px;border: 1px solid black; border-top: none; border-bottom: none;border-left: none; border-collapse: collapse;" colspan="302">
										<u>
											<input type="text" name="nama_kabagkeuangan" id="nama_kabagkeuangan" placeholder="Sulistyowati,SE" value="<?php echo $nama_kabagkeuangan; ?>" required>
										</u>
									</th>
									<!-- <th style="font-size:0.550em; width: 400px;" colspan="400"></th> -->
									<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
									<th style="font-size:0.550em; text-align:center;width: 398px;border: 1px solid black; border-top: none; border-bottom: none; border-collapse: collapse;" colspan="398">
										<u><input type="text" name="nama_kasirpemebelian" id="nama_kasirpemebelian" placeholder="Patra Jatmika" value="<?php echo $nama_kasirpemebelian; ?>" required></u>
									</th>
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 200px;" colspan="200">:</th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x5</th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x6</th> -->
								</tr>


								<tr style="border: 1px solid black; border-top: none; border-collapse: collapse;">
									<th style="font-size:0.550em; text-align:center;width: 300px;border: 1px solid black; border-top: none; border-bottom: none;border-right: none; border-collapse: collapse;" colspan="300">(DIREKTUR)</th>
									<!-- <th style="font-size:0.550em; width: 2px" colspan="2"></th> -->
									<!-- <th style="font-size:0.550em; width: 148px;" colspan="148"></th> -->
									<!-- <th style="font-size:0.550em; width: 200px;" colspan="200"></th> -->
									<th style="font-size:0.550em; text-align:center;width: 302px;border: 1px solid black; border-top: none; border-bottom: none;border-left: none; border-collapse: collapse;" colspan="302">(KA. BAG. KEUANGAN)</th>
									<!-- <th style="font-size:0.550em; width: 400px;" colspan="400"></th> -->
									<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
									<th style="font-size:0.550em; text-align:center;width: 398px;border: 1px solid black; border-top: none; border-bottom: none; border-collapse: collapse;" colspan="398">(KASIR & PEMBELIAN)</th>
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 200px;" colspan="200">:</th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x5</th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
									<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x6</th> -->
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
							<?php
							if ($from_pembelian_page == "Pembelian") {
								// echo $from_pembelian_page;
							?>
								<a href="<?php echo site_url('Tbl_pembelian') ?>" class="btn btn-default">Cancel </a>
							<?php
							} else {
								// echo $from_pembelian_page;
							?>
								<a href="<?php echo site_url('Tbl_pembelian/pembayaran_ke_supplier') ?>" class="btn btn-default">Cancel</a>
							<?php
							}
							?>

						</div>

						<div class="col-4">


							<!-- <a href="<?php //echo site_url('tbl_pembelian/cetak_pengajuan_bayar_per_spop/' . $uuid_spop) 
											?>" class="btn btn-success" target="_blank">Cetak Pengajuan Pembayaran (PDF)</a> -->

							<button type="submit" class="btn btn-primary">SIMPAN</button>

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