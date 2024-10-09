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
						<h3 class="card-title">TAMBAH DATA SALES : </h3>
					</div>
					<div class="card-body">

						<form action="<?php echo $action; ?>" method="post">



							<table class='table table-bordered>' <tr>


								<!-- <tr>
									<td width='200'>Kode Sales <?php //echo form_error('kode_sales') 
																?></td>
									<td> <textarea class="form-control" rows="3" name="kode_sales" id="kode_sales" placeholder="kode sales"><?php //echo $kode_sales; 
																																			?></textarea></td>
								</tr> -->
								<!-- <tr>
									<td width='200'>KODE SALES <?php //echo form_error('kode_sales') 
																?></td>
									<td> <textarea class="form-control" rows="3" name="kode_sales" id="kode_sales" placeholder="Kode Sales"><?php ///echo $kode_sales; 
																																			?></textarea></td>
								</tr> -->
								<tr>
									<td width='200'>Nama Sales <?php echo form_error('nama_sales') ?></td>
									<td> <textarea class="form-control" rows="3" name="nama_sales" id="nama_sales" placeholder="Nama Sales"><?php echo $nama_sales; ?></textarea></td>
								</tr>

								<tr>
									<td width='200'>Alamat Sales <?php echo form_error('alamat_sales') ?></td>
									<td> <textarea class="form-control" rows="3" name="alamat_sales" id="alamat_sales" placeholder="Alamat Sales"><?php echo $alamat_sales; ?></textarea></td>
								</tr>

								<tr>
									<td width='200'>Notelp Sales <?php echo form_error('notelp_sales') ?></td>
									<td> <textarea class="form-control" rows="3" name="notelp_sales" id="notelp_sales" placeholder="Notelp Sales"><?php echo $notelp_sales; ?></textarea></td>
								</tr>

								<tr>
									<td width='200'>Keterangan <?php echo form_error('keterangan') ?></td>
									<td> <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"><?php echo $keterangan; ?></textarea></td>
								</tr>


								<tr>
									<td width='200'>Sales Rekomendasi (opsional)</td>
									<td>
										<!-- <form action="<?php //echo $action_kode_sales; ?>" method="post"> -->
																		
										<div class="col-sm-5 card-title">
											<select name="uuid_sales_rekomendasi" id="uuid_sales_rekomendasi" class="form-control select2" style="width: 100%; height: 40px;">
												<option value="<?php echo $uuid_sales_selected ?>">
													<?php 
														if ($uuid_sales_selected) { echo $nama_sales_selected; } else { echo "Pilih Sales Rekomendasi"; } ?></option> <?php $sql = "select * from tbl_sales order by  nama_sales ";
															foreach ($this->db->query($sql)->result() as $m) {
																echo "<option value='$m->uuid_sales' ";
																echo "> " . strtoupper($m->nama_sales) . "</option>";
																}
													?>
											</select>                                                                                                                    
										</div>
										
										<!-- </form> -->
									</td>
								</tr>

								<tr>
									<td></td>
									<td><input type="hidden" name="id" value="<?php echo $id; ?>" />
										<button type="submit" class="btn btn-danger"><i class="fa fa-floppy-o"></i> <?php echo $button ?></button>
										<a href="<?php echo site_url('tbl_sales') ?>" class="btn btn-info"><i class="fa fa-sign-out"></i> Kembali</a>
									</td>
								</tr>
								
								


							</table>



						</form>



					</div>

					<!-- /.card-body -->
				</div>
				<!-- /.card -->

				<!-- iCheck -->

				<!-- /.card -->

				<!-- Bootstrap Switch -->

				<!-- /.card -->
			</div>

		</div>
	</section>
</div>