<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0"> </h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<!-- <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard v1</li> -->
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>

	<section class="content">
		<div class="box box-warning box-solid">

			<div class="col-md-12">
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title">INPUT DATA MAPEL</h3>
					</div>

					<div class="row">

						<div class="col-sm-6" align="right">

							<div class="card-body">

								<div class="box box-success box-solid">
									<!-- <div class="box-header with-border">
										<h3 class="box-title">BERDASARKAN NAMA SALES</h3>
									</div> -->
									<!-- <br /> -->

									<form action="<?php echo $action; ?>" method="post">
										<div class="row">
											<div class="col-sm-1"></div>
											<div class="col-sm-2" align="left">
												Tingkat
											</div>
											<div class="col-sm-3" align="left">
												<!-- <input type="text" class="form-control" name="tingkat" id="tingkat" placeholder="Tingkat" value="<?php //echo $tingkat; ?>" /> -->


												<select name="tingkat" id="tingkat" class="form-control select2" style="width: 100%; height: 40px;">
														<?php 
															if($tingkat){
														?>
															<option value="<?php echo $tingkat ?>"><?php echo $tingkat; ?></option>
														<?php
															}else{
														?>
															<option value="">Pilih Tingkat</option>
														<?php
															} 																																					
															$sql = "select tingkat_system,tingkat from  sys_tingkat group by tingkat order by  tingkat asc";
															foreach ($this->db->query($sql)->result() as $m) {
																echo "<option value='$m->tingkat_system' ";echo ">  " . strtoupper($m->tingkat) . "</option>";
															}
														?>
												</select>
												<?php echo form_error('tingkat') ?>

											</div>
											<div class="col-sm-1"></div>
										</div>
										<br />
										<div class="row">
											<div class="col-sm-1"></div>
											<div class="col-sm-2" align="left">
												Kelas
											</div>
											<div class="col-sm-3" align="left">
												<!-- <input type="text" class="form-control" name="kelas" id="kelas" placeholder="Kelas" value="<?php //echo $kelas; ?>" /> -->



												<select name="kelas" id="kelas" class="form-control select2" style="width: 100%; height: 40px;">


														<?php 
															if($kelas){
														?>
															<option value="<?php echo $kelas ?>"><?php echo $kelas; ?></option>
														<?php
															}else{
														?>
															<option value="">Pilih kelas</option>
														<?php
															} 
														?>

														
														<option value="1">1</option>
														<option value="2">2</option>
														<option value="3">3</option>
														<option value="4">4</option>
														<option value="5">5</option>
														<option value="6">6</option>
														<option value="7">7</option>
														<option value="8">8</option>
														<option value="9">9</option>
														<option value="10">10</option>
														<option value="11">11</option>
														<option value="12">12</option>
													
												</select>
												<?php echo form_error('kelas') ?>

											</div>
											<div class="col-sm-1"></div>
										</div>
										<br />
										<div class="row">
											<div class="col-sm-1"></div>
											<div class="col-sm-2" align="left">
												Semester
											</div>
											<div class="col-sm-8" align="left">
												<!-- <input type="text" class="form-control" name="semester" id="semester" placeholder="Semester" value="<?php //echo $semester; ?>" /> -->
															<?php 
															$semester_selected = $_SESSION['semester_selected'];
															?>
												<select name="semester" id="semester" class="form-control select2" style="width: 100%; height: 40px;">
														<option value="<?php echo $semester_selected; ?>"><?php echo $semester_selected; ?></option>
														<option value="1">1</option>
														<option value="2">2</option>
													
												</select>
												<?php echo form_error('semester') ?>

											</div>
											<div class="col-sm-1"></div>
										</div>
										<br />
										
										<div class="row">
											<div class="col-sm-1"></div>
											<div class="col-sm-2" align="left">
												Nama Mapel
											</div>
											<div class="col-sm-8" align="left">
												
												<!-- <input type="text" class="form-control" name="mapel" id="mapel" placeholder="mapel" value="<?php //echo $mapel; ?>" /> -->


												<select name="mapel" id="mapel" class="form-control select2" style="width: 100%; height: 40px;">

														<?php 
															if($mapel_detail){
														?>
															<option value="<?php echo $mapel_detail ?>"><?php echo $mapel_detail; ?></option>
														<?php
															}else{
														?>
															<option value="">Pilih mapel</option>
														<?php
															} 	
															
															//CEK APAKAH ADA NAMA MAPEL DI SYS_MAPEL YANG status_di_tbl_produk_mapel_referensi = 0
												
															$get_status_di_tbl_produk_mapel_referensi="";
															$this->db->where('status_di_tbl_produk_mapel_referensi', $get_status_di_tbl_produk_mapel_referensi);
															//$this->db->where('password',  $test);
															$status_mapel = $this->db->get('sys_mapel');
											
															if ($status_mapel->num_rows() > 0) {
																// print_r("Ada mapel di sys_mapel belum terpakai");

																//List mapel yang belum terpakai
																$sql = "SELECT mapel FROM sys_mapel where status_di_tbl_produk_mapel_referensi <> 1  order by mapel ASC";
																foreach ($this->db->query($sql)->result() as $m) {
																	echo "<option value='$m->mapel' ";echo ">  " . strtoupper($m->mapel) . "</option>";
																}

																// semua list mapel dari tabel tbl_produk_mapel_referensi
																$sql = "SELECT mapel_detail FROM tbl_produk_mapel_referensi group by mapel_detail order by mapel_detail ASC";
																foreach ($this->db->query($sql)->result() as $m) {
																	echo "<option value='$m->mapel_detail' ";echo ">  " . strtoupper($m->mapel_detail) . "</option>";
																}

											
															}else{
																// print_r("Semua mapel di sys_mapel sudah terpakai");


																$sql = "SELECT mapel_detail FROM tbl_produk_mapel_referensi group by mapel_detail order by mapel_detail ASC";
																foreach ($this->db->query($sql)->result() as $m) {
																	echo "<option value='$m->mapel_detail' ";echo ">  " . strtoupper($m->mapel_detail) . "</option>";
																}

															}
															
											

															
														?>
												</select>
												<?php echo form_error('mapel') ?>

											</div>
											<div class="col-sm-1"></div>
										</div>
										<br />
										<div class="row">
											<div class="col-sm-1"></div>
											<div class="col-sm-2" align="left">
												Halaman
											</div>
											<div class="col-sm-8" align="left">

												<input type="text" class="form-control" name="halaman" id="halaman" placeholder="halaman" value="<?php echo $halaman; ?>" />
												<?php echo form_error('halaman') ?>
											</div>
											<div class="col-sm-1"></div>
										</div>
										<br />
										<div class="row">
											<div class="col-sm-1"></div>
											<div class="col-sm-2" align="left">
											</div>
											<div class="col-sm-2" align="left">
												<input type="Button" value="Kembali" onclick="history.back()">
												<button type="submit" class="btn btn-danger"><i class="fa fa-floppy-o"></i> <?php echo $button ?></button>
											</div>
											<div class="col-sm-2" align="left">

											</div>
											<div class="col-sm-4" align="left">

											</div>
											<div class="col-sm-1"></div>
										</div>
										<br />
									</form>
								</div>

							</div>

						</div>

						<!-- KOLOM KANAN -->
						<div class="col-sm-6" align="left">

							<div class="card-body">

								<div class="box box-success box-solid">
									
									
										<div class="row">
											<div class="col-sm-1"></div>
											<div class="col-sm-11" align="left">
												<form action="<?php 
																	$action_TAMBAH_TINGKAT="tambah_tingkat";
																	
																	echo $action_TAMBAH_TINGKAT; 
																?>" method="post">
														<button type="submit" class="btn btn-danger"><i class="fa fa-floppy-o"></i> Tambah Tingkat</button>
												</form>
												Menambahkan Tingkat, jika di combo pilihan tingkat belum tersedia.
											</div>
											<!-- <div class="col-sm-3" align="left"></div>
											<div class="col-sm-1"></div> -->
										</div>
										<br />
										<div class="row">
											<div class="col-sm-1"></div>
											<div class="col-sm-11" align="left">
												<form action="<?php 
																		$action_TAMBAH_MAPEL="tambah_mapel";
																		
																		echo $action_TAMBAH_MAPEL; 
																	?>" method="post">
															<button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Tambah Mapel</button>
												</form>
												Menambahkan nama mapel, jika di combo pilihan mapel belum tersedia.
											</div>
											<!-- <div class="col-sm-3" align="left"></div>
											<div class="col-sm-1"></div> -->
										</div>
										<br />
										<div class="row">
											<div class="col-sm-1"></div>
											<div class="col-sm-2" align="left"></div>
											<div class="col-sm-8" align="left"></div>
											<div class="col-sm-1"></div>
										</div>
										<br />
										
										<div class="row">
											<div class="col-sm-1"></div>
											<div class="col-sm-2" align="left"></div>
											<div class="col-sm-8" align="left"></div>
											<div class="col-sm-1"></div>
										</div>
										<br />
										<div class="row">
											<div class="col-sm-1"></div>
											<div class="col-sm-2" align="left"></div>
											<div class="col-sm-8" align="left"></div>
											<div class="col-sm-1"></div>
										</div>
										<br />
										<div class="row">
											<div class="col-sm-1"></div>
											<div class="col-sm-2" align="left"></div>
											<div class="col-sm-2" align="left"></div>
											<div class="col-sm-2" align="left"></div>
											<div class="col-sm-4" align="left"></div>
											<div class="col-sm-1"></div>
										</div>
										<br />
									
								</div>

							</div>

						</div>

						<!-- <div class="col-sm-3" align="left"></div> -->
						<!-- <div class="col-sm-3" align="left"></div> -->

					</div>

					


					

					<!-- /.card-body -->
				</div>

			</div>

		</div>
	</section>
</div>