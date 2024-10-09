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
						<h3 class="card-title">TAMBAH TINGKAT </h3>
					</div>

					<div class="row">

						<div class="col-sm-6" align="right">

							<div class="card-body">

								<div class="box box-success box-solid">

									<form action="<?php echo $action; ?>" method="post">
										<div class="row">
											<div class="col-sm-1"></div>
											<div class="col-sm-2" align="left">
												Tingkat
											</div>
											<div class="col-sm-3" align="left">
												<input type="text" class="form-control" name="tingkat" id="tingkat" placeholder="Tingkat" value="<?php //echo $tingkat; ?>" />

												<!-- <input type="Button" value="Kembali" onclick="history.back()"> -->
												<button type="submit" class="btn btn-danger"><i class="fa fa-floppy-o"></i> <?php echo $button ?></button>
											</div>
											<div class="col-sm-6">
											<?php
												// $sql = "select tingkat from  tbl_produk_mapel_referensi group by tingkat order by  tingkat asc";
												// 			foreach ($this->db->query($sql)->result() as $m) {
												// 				echo $m->tingkat;
												// 				echo "<br/>";
												// 			}
															?>
											</div>
										</div>
									</form>
								</div>

							</div>

						</div>

						<!-- KOLOM KANAN -->
						<div class="col-sm-3" align="left">

						</div>

						<div class="col-sm-3" align="left"></div>
						<div class="col-sm-3" align="left"></div>

					</div>

					


					

					<!-- /.card-body -->
				</div>

			</div>

		</div>
	</section>
</div>