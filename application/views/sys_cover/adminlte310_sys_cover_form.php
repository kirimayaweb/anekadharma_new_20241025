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
						<h3 class="card-title">DATA COVER</h3>
					</div>
					<div class="card-body">
						<form action="<?php echo $action; ?>" method="post">
							<table class='table table-bordered>' <tr>





								<!-- <tr>
									<td align="center">QURDIS X </td>
									<td align="center"> <input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="qurdis10_exemplar" id="qurdis10_exemplar" placeholder="" value="" style="font-size:25px;font-weight: bold" ; /></td>
									<td align="center"><input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="qurdis10_harga" id="qurdis10_harga" placeholder="" value="" style="font-size:25px;font-weight: bold" ; /></td>
									<td align="center"><input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="qurdis10_jmlhal" id="qurdis10_jmlhal" placeholder="Jumlah Halaman" value="<?php echo $jumlah_halaman_trans_pemesanan; ?>" /></td>
									<td align="center"><input type="text" class="form-control" name="qurdis10_tipe" id="qurdis10_tipe" placeholder="Tipe" value="<?php echo $tipe_pesanan_trans_pemesanan; ?>" /></td>
									<td align="center"><input type="text" class="form-control" name="qurdis10_keterangan" id="qurdis10_keterangan" placeholder="Keterangan" value="" /></td>
								</tr> -->


								<!-- <td width='200'>Uuid Cover <?php //echo form_error('uuid_cover') ?></td>
								<td><input type="text" class="form-control" name="uuid_cover" id="uuid_cover" placeholder="Uuid Cover" value="<?php echo $uuid_cover; ?>" /></td>
								</tr>
								<tr>
									<td width='200'>Date Input <?php //echo form_error('date_input') ?></td>
									<td><input type="text" class="form-control" name="date_input" id="date_input" placeholder="Date Input" value="<?php echo $date_input; ?>" /></td>
								</tr>
								<tr>
									<td width='200'>Id User Input <?php //echo form_error('id_user_input') ?></td>
									<td><input type="text" class="form-control" name="id_user_input" id="id_user_input" placeholder="Id User Input" value="<?php echo $id_user_input; ?>" /></td>
								</tr> -->

								<tr>
									<td width='200'>Tingkat <?php echo form_error('tingkat') ?></td>
									<td>

										<!-- <input type="text" class="form-control" name="tingkat" id="tingkat" placeholder="Tingkat" value="<?php //echo $tingkat; ?>" /> -->
										<!-- <select name="tingkat" id="tingkat" class="form-control select2" style="width: 200px; height: 10px;">
                                            <option value="<?php //echo $tingkat; ?>"><?php //echo $tingkat; ?></option>

                                            <option value="MA">MA</option>
                                            <option value="MTS">MTS</option>
                                            <option value="MI">MI</option>
                                            <option value="SMP">SMP K13</option>
											<option value="PGSMP">PGSMP K13</option>
											<option value="SMPKUMER">SMP KUMER</option>
											<option value="PGSMPKUMER">PGSMP KUMER</option>
											<option value="SD">SD K13</option>
											<option value="PGSD">PGSD K13</option>
											<option value="SDKUMER">SD KUMER</option>
											<option value="PGSDKUMER">PGSD KUMER</option>
                                        </select> -->


										<select name="tingkat" id="tingkat" class="form-control select2" style="width: 200px; height: 60px;">
												<option value="">Pilih Tingkat</option>
												<?php																								
                                                    $sql = "select tingkat from  tbl_produk_mapel_referensi group by tingkat order by  tingkat asc";
                                                    foreach ($this->db->query($sql)->result() as $m) {
                                                        echo "<option value='$m->tingkat' ";echo ">  " . strtoupper($m->tingkat) . "</option>";
                                                    }
												?>
										</select>



									</td>
								</tr>

								<!-- <tr>
									<td width='200'>Tahun <?php //echo form_error('tahun') ?></td>
									<td><input type="text" class="form-control" name="tahun" id="tahun" placeholder="Tahun" value="<?php //echo $tahun; ?>" /></td>
								</tr>
								<tr>
									<td width='200'>Semester <?php //echo form_error('semester') ?></td>
									<td><input type="text" class="form-control" name="semester" id="semester" placeholder="Semester" value="<?php //echo $semester; ?>" /></td>
								</tr>
								<tr>
									<td width='200'>Halaman <?php //echo form_error('halaman') ?></td>
									<td><input type="text" class="form-control" name="halaman" id="halaman" placeholder="Halaman" value="<? //php echo $halaman; ?>" /></td>
								</tr> -->

								<tr>
									<td width='200'>Nama Cover <?php echo form_error('nama_cover') ?></td>
									<td> <textarea class="form-control" rows="3" name="nama_cover" id="nama_cover" placeholder="Nama Cover"><?php echo $nama_cover; ?></textarea></td>
								</tr>


								<!-- <tr>
									<td width='200'>Mapel <?php //echo form_error('mapel') ?></td>
									<td> <textarea class="form-control" rows="3" name="mapel" id="mapel" placeholder="Mapel"><?php //echo $mapel; ?></textarea></td>
								</tr>
								<tr>
									<td width='200'>Kelas <?php //echo form_error('kelas') ?></td>
									<td><input type="text" class="form-control" name="kelas" id="kelas" placeholder="Kelas" value="<?php //echo $kelas; ?>" /></td>
								</tr> -->

								<tr>
									<td width='200'>Keterangan <?php //echo form_error('keterangan') ?></td>
									<td> 
										<!-- <textarea class="form-control" rows="3" name="nama_cover" id="nama_cover" placeholder="Nama Cover"><?php //echo $nama_cover; ?></textarea> -->
										<select name="keterangan" id="keterangan" class="form-control select2" style="width: 200px; height: 10px;">
											<option value="<?php echo $keterangan; ?>"><?php echo $keterangan; ?></option>
											<option value=""></option>
											<option value="buku_lama">buku_lama</option>
										</select>
										dikosongkan jika untuk cover-cover buku baru ( PILIH buku_lama untuk BUKU LAMA )
									</td>
								</tr>

								<tr>
									<td></td>
									<td><input type="hidden" name="id" value="<?php echo $id; ?>" />
										<button type="submit" class="btn btn-danger"><i class="fa fa-floppy-o"></i> <?php echo $button ?></button>
										<a href="<?php echo site_url('sys_cover') ?>" class="btn btn-info"><i class="fa fa-sign-out"></i> Kembali</a>
									</td>
								</tr>















							</table>
						</form>

					</div>

					<!-- /.card-body -->
				</div>
			</div>

		</div>
	</section>


</div>

<!-- ============================= -->