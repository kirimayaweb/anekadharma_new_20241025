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
						<h3 class="card-title">TAMBAH MAPEL </h3>
					</div>

					<div class="row">

						<div class="col-sm-9" align="right">

							<div class="card-body">

								<div class="box box-success box-solid">

									<form action="<?php echo $action; ?>" method="post">
										<div class="row"  align="left">
											<!-- <div class="col-sm-1"></div> -->
											<div class="col-sm-2" align="left">
												Mapel
											</div>
											<div class="col-sm-6" align="left">
												<input type="text" class="form-control" name="new_mapel" id="new_mapel" placeholder="Nama Mapel" value="<?php //echo $tingkat; ?>" />
												
												<!-- <input type="Button" value="Kembali" onclick="history.back()"> -->
												<button type="submit" class="btn btn-danger"><i class="fa fa-floppy-o"></i> <?php echo $button ?></button>
												
											</div>
											<br/>
											NOTE:
											<br/>
												Pembuatan nama mapel tidak disertai Tingkat dan Kelas, cukup nama mapel saja. <br/>
												==> tingkat dan kelas akan di setting saat pengisian mapel masing-masing tingkat dan kelas

										</div>
									</form>
								</div>

							</div>

						</div>

						<!-- KOLOM KANAN -->
						<div class="col-sm-3" align="left">

						</div>

					</div>

					


					

					<!-- /.card-body -->
				</div>

			</div>

		</div>
	</section>
</div>