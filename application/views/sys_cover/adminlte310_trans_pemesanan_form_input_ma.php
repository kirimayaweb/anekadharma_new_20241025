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
						<h3 class="card-title">SETTING PEMESANAN</h3>
					</div>
					<div class="card-body">
						<form action="<?php echo $action; ?>" method="post">
							<table class='table table-bordered>' <tr>




								<tr>
									<td colspan="6" align="center">NOTA PEMESANAN</td>
								</tr>



								<tr>
									<td colspan="6" align="center">SEMESTER <?php
																			if ($semester_pesanan_trans_pemesanan == 1) {
																				echo "GANJIL";
																			?>

											<input type="hidden" name="semester_pesanan_trans_pemesanan" value="<?php echo $semester_pesanan_trans_pemesanan; ?>" />
										<?php
																			} else {
																				echo "GENAP";
										?>
											<input type="hidden" name="semester_pesanan_trans_pemesanan" value="<?php echo $semester_pesanan_trans_pemesanan; ?>" />
										<?php
																			}


										?> TAHUN <?php echo $tahun_pesanan_trans_pemesanan . "/" . ($tahun_pesanan_trans_pemesanan + 1); ?>


										<input type="hidden" name="tahun_pesanan_trans_pemesanan" value="<?php echo $tahun_pesanan_trans_pemesanan; ?>" />

									</td>

								</tr>


								<tr>
									<td align="left">NAMA CUSTOMER</td>
									<td colspan="3" align="left">
										<?php
										if ($uuid_sales) {
											$this->db->where('uuid_sales', $uuid_sales);
											$data_sales = $this->db->get('tbl_sales')->row();

											print_r($data_sales->nama_sales);
											// print_r(", ( Alamat: ");
											// print_r($data_sales->alamat_sales);
											// print_r(", no. telp :");
											// print_r($data_sales->notelp_sales);
											// print_r(")");
										?>
											<input type="hidden" name="uuid_sales" value="<?php echo $uuid_sales; ?>" />
										<?php
										} else {
										?>
											<select name="uuid_sales" id="uuid_sales" class="form-control select2" style="width: 100%; height: 40px;">
												<option value="">Pilih Sales</option>
												<?php
												$sql = "select * from tbl_sales order by  nama_sales asc";
												foreach ($this->db->query($sql)->result() as $m) {
													echo "<option value='$m->uuid_sales' ";
													echo ">  " . strtoupper($m->nama_sales) . " | Alamat : " . strtoupper($m->alamat_sales) . "</option>";
												}
												?>
											</select>
										<?php
										}
										?>
										<?php echo form_error('uuid_sales') ?>
									</td>
									</td>
									<!-- <td align="center"></td> -->
									<!-- <td align="center"></td> -->
									<td align="left">TANGGAL</td>
									<td align="left"><input type="text" class="form-control" name="tgl_transaksi" id="tgl_transaksi" placeholder="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>" /></td>
								</tr>


								<tr>
									<td align="center">ALAMAT</td>
									<td colspan="3" align="left">


										<?php
										if ($uuid_sales) {
											$this->db->where('uuid_sales', $uuid_sales);
											$data_sales = $this->db->get('tbl_sales')->row();


											print_r($data_sales->alamat_sales);

										?>
											<input type="hidden" name="uuid_sales" value="<?php echo $uuid_sales; ?>" />
										<?php
										} else {
										?>
											<select name="uuid_sales" id="uuid_sales" class="form-control select2" style="width: 100%; height: 40px;">
												<option value="">Pilih Sales</option>
												<?php
												$sql = "select * from tbl_sales order by  nama_sales asc";
												foreach ($this->db->query($sql)->result() as $m) {
													echo "<option value='$m->uuid_sales' ";
													echo ">  " . strtoupper($m->nama_sales) . " | Alamat : " . strtoupper($m->alamat_sales) . "</option>";
												}
												?>
											</select>
										<?php
										}
										?>
										<?php echo form_error('uuid_sales') ?>



										<input type="hidden" name="tingkat_pesanan_trans_pemesanan" value="<?php echo $tingkat_pesanan_trans_pemesanan; ?>" />

									</td>

									<td align="left">JENIS BUKU</td>
									<td align="left">


										<?php
										if ($uuid_cover_trans_pemesanan) {
											$this->db->where('uuid_cover', $uuid_cover_trans_pemesanan);
											$data_cover = $this->db->get('sys_cover')->row();

											print_r($data_cover->nama_cover);
										?>
											<input type="hidden" name="uuid_cover" value="<?php echo $uuid_cover_trans_pemesanan; ?>" />
										<?php
										} else {
										?>




											<select name="uuid_cover" id="uuid_cover" class="form-control select2" style="width: 100%; height: 40px;">
												<option value="">Pilih cover</option>
												<?php
												$sql = "select * from sys_cover order by  nama_cover asc";
												foreach ($this->db->query($sql)->result() as $m) {
													echo "<option value='$m->uuid_cover' ";
													echo ">  " . strtoupper($m->nama_cover) . "</option>";
												}
												?>
											</select>

										<?php
										}
										?>

									</td>
								</tr>



								<tr>
									<td align="center"></td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="center"></td>
								</tr>


								<tr>
									<td align="center">


										<?php
										if ($tipe_pesanan_trans_pemesanan) {

										?>
											<input type="hidden" name="tipe_pesanan_trans_pemesanan" value="<?php echo $tipe_pesanan_trans_pemesanan; ?>" />
										<?php

										}
										?>
										<?php
										if ($jumlah_halaman_trans_pemesanan) {

										?>
											<input type="hidden" name="jumlah_halaman_trans_pemesanan" value="<?php echo $jumlah_halaman_trans_pemesanan; ?>" />
										<?php

										}
										?>

									</td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="center"></td>
								</tr>



								<tr>
									<td width='20' align="center">MAPEL</td>
									<td width='10' align="center">EXEMPLAR</td>
									<td width='20' align="center">HARGA</td>
									<td width='10' align="center">JML HAL.</td>
									<td width='5' align="center">TIPE</td>
									<td width='400' align="center">KETERANGAN</td>
								</tr>



								<tr>
									<td align="center">QURDIS X </td>
									<td align="center"> <input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="qurdis10_exemplar" id="qurdis10_exemplar" placeholder="" value="" style="font-size:25px;font-weight: bold" ; /></td>
									<td align="center"><input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="qurdis10_harga" id="qurdis10_harga" placeholder="" value="" style="font-size:25px;font-weight: bold" ; /></td>
									<td align="center"><input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="qurdis10_jmlhal" id="qurdis10_jmlhal" placeholder="Jumlah Halaman" value="<?php echo $jumlah_halaman_trans_pemesanan; ?>" /></td>
									<td align="center"><input type="text" class="form-control" name="qurdis10_tipe" id="qurdis10_tipe" placeholder="Tipe" value="<?php echo $tipe_pesanan_trans_pemesanan; ?>" /></td>
									<td align="center"><input type="text" class="form-control" name="qurdis10_keterangan" id="qurdis10_keterangan" placeholder="Keterangan" value="" /></td>
								</tr>




								<tr>
									<td align="center">QURDIS XI </td>
									<td align="center"> <input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="qurdis11_exemplar" id="qurdis11_exemplar" placeholder="" value="" style="font-size:25px;font-weight: bold" ; /></td>
									<td align="center"><input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="qurdis11_harga" id="qurdis11_harga" placeholder="" value="" style="font-size:25px;font-weight: bold" ; /></td>
									<td align="center"><input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="qurdis11_jmlhal" id="qurdis11_jmlhal" placeholder="Jumlah Halaman" value="<?php echo $jumlah_halaman_trans_pemesanan; ?>" /></td>
									<td align="center"><input type="text" class="form-control" name="qurdis11_tipe" id="qurdis11_tipe" placeholder="Tipe" value="<?php echo $tipe_pesanan_trans_pemesanan; ?>" /></td>
									<td align="center"><input type="text" class="form-control" name="qurdis11_keterangan" id="qurdis11_keterangan" placeholder="Keterangan" value="" /></td>
								</tr>


								<tr>
									<td align="center">QURDIS XII </td>
									<td align="center"> <input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="qurdis12_exemplar" id="qurdis12_exemplar" placeholder="" value="" style="font-size:25px;font-weight: bold" ; /></td>
									<td align="center"><input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="qurdis12_harga" id="qurdis12_harga" placeholder="" value="" style="font-size:25px;font-weight: bold" ; /></td>
									<td align="center"><input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="qurdis12_jmlhal" id="qurdis12_jmlhal" placeholder="Jumlah Halaman" value="<?php echo $jumlah_halaman_trans_pemesanan; ?>" /></td>
									<td align="center"><input type="text" class="form-control" name="qurdis12_tipe" id="qurdis12_tipe" placeholder="Tipe" value="<?php echo $tipe_pesanan_trans_pemesanan; ?>" /></td>
									<td align="center"><input type="text" class="form-control" name="qurdis12_keterangan" id="qurdis12_keterangan" placeholder="Keterangan" value="" /></td>
								</tr>

								<tr>
									<td align="center">FIQIH X </td>
									<td align="center"> <input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="fiqih10_exemplar" id="fiqih10_exemplar" placeholder="" value="" style="font-size:25px;font-weight: bold" ; /></td>
									<td align="center"><input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="fiqih10_harga" id="fiqih10_harga" placeholder="" value="" style="font-size:25px;font-weight: bold" ; /></td>
									<td align="center"><input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="fiqih10_jmlhal" id="fiqih10_jmlhal" placeholder="Jumlah Halaman" value="<?php echo $jumlah_halaman_trans_pemesanan; ?>" /></td>
									<td align="center"><input type="text" class="form-control" name="fiqih10_tipe" id="fiqih10_tipe" placeholder="Tipe" value="<?php echo $tipe_pesanan_trans_pemesanan; ?>" /></td>
									<td align="center"><input type="text" class="form-control" name="fiqih10_keterangan" id="fiqih10_keterangan" placeholder="Keterangan" value="" /></td>
								</tr>

								<tr>
									<td align="center">FIQIH XI </td>
									<td align="center"> <input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="fiqih11_exemplar" id="fiqih11_exemplar" placeholder="" value="" style="font-size:25px;font-weight: bold" ; /></td>
									<td align="center"><input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="fiqih11_harga" id="fiqih11_harga" placeholder="" value="" style="font-size:25px;font-weight: bold" ; /></td>
									<td align="center"><input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="fiqih11_jmlhal" id="fiqih11_jmlhal" placeholder="Jumlah Halaman" value="<?php echo $jumlah_halaman_trans_pemesanan; ?>" /></td>
									<td align="center"><input type="text" class="form-control" name="fiqih11_tipe" id="fiqih11_tipe" placeholder="Tipe" value="<?php echo $tipe_pesanan_trans_pemesanan; ?>" /></td>
									<td align="center"><input type="text" class="form-control" name="fiqih11_keterangan" id="fiqih11_keterangan" placeholder="Keterangan" value="" /></td>
								</tr>

								<tr>
									<td align="center">FIQIH XII </td>
									<td align="center"> <input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="fiqih12_exemplar" id="fiqih12_exemplar" placeholder="" value="" style="font-size:25px;font-weight: bold" ; /></td>
									<td align="center"><input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="fiqih12_harga" id="fiqih12_harga" placeholder="" value="" style="font-size:25px;font-weight: bold" ; /></td>
									<td align="center"><input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="fiqih12_jmlhal" id="fiqih12_jmlhal" placeholder="Jumlah Halaman" value="<?php echo $jumlah_halaman_trans_pemesanan; ?>" /></td>
									<td align="center"><input type="text" class="form-control" name="fiqih12_tipe" id="fiqih12_tipe" placeholder="Tipe" value="<?php echo $tipe_pesanan_trans_pemesanan; ?>" /></td>
									<td align="center"><input type="text" class="form-control" name="fiqih12_keterangan" id="fiqih12_keterangan" placeholder="Keterangan" value="" /></td>
								</tr>

								<tr>
									<td align="center">ARAB X </td>
									<td align="center"> <input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="arab10_exemplar" id="arab10_exemplar" placeholder="" value="" style="font-size:25px;font-weight: bold" ; /></td>
									<td align="center"><input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="arab10_harga" id="arab10_harga" placeholder="" value="" style="font-size:25px;font-weight: bold" ; /></td>
									<td align="center"><input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="arab10_jmlhal" id="arab10_jmlhal" placeholder="Jumlah Halaman" value="<?php echo $jumlah_halaman_trans_pemesanan; ?>" /></td>
									<td align="center"><input type="text" class="form-control" name="arab10_tipe" id="arab10_tipe" placeholder="Tipe" value="<?php echo $tipe_pesanan_trans_pemesanan; ?>" /></td>
									<td align="center"><input type="text" class="form-control" name="arab10_keterangan" id="arab10_keterangan" placeholder="Keterangan" value="" /></td>
								</tr>

								<tr>
									<td align="center">ARAB XI </td>
									<td align="center"> <input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="arab11_exemplar" id="arab11_exemplar" placeholder="" value="" style="font-size:25px;font-weight: bold" ; /></td>
									<td align="center"><input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="arab11_harga" id="arab11_harga" placeholder="" value="" style="font-size:25px;font-weight: bold" ; /></td>
									<td align="center"><input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="arab11_jmlhal" id="arab11_jmlhal" placeholder="Jumlah Halaman" value="<?php echo $jumlah_halaman_trans_pemesanan; ?>" /></td>
									<td align="center"><input type="text" class="form-control" name="arab11_tipe" id="arab11_tipe" placeholder="Tipe" value="<?php echo $tipe_pesanan_trans_pemesanan; ?>" /></td>
									<td align="center"><input type="text" class="form-control" name="arab11_keterangan" id="arab11_keterangan" placeholder="Keterangan" value="" /></td>
								</tr>

								<tr>
									<td align="center">ARAB XII </td>
									<td align="center"> <input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="arab12_exemplar" id="arab12_exemplar" placeholder="" value="" style="font-size:25px;font-weight: bold" ; /></td>
									<td align="center"><input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="arab12_harga" id="arab12_harga" placeholder="" value="" style="font-size:25px;font-weight: bold" ; /></td>
									<td align="center"><input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="arab12_jmlhal" id="arab12_jmlhal" placeholder="Jumlah Halaman" value="<?php echo $jumlah_halaman_trans_pemesanan; ?>" /></td>
									<td align="center"><input type="text" class="form-control" name="arab12_tipe" id="arab12_tipe" placeholder="Tipe" value="<?php echo $tipe_pesanan_trans_pemesanan; ?>" /></td>
									<td align="center"><input type="text" class="form-control" name="arab12_keterangan" id="arab12_keterangan" placeholder="Keterangan" value="" /></td>
								</tr>






								<tr>
									<td align="center">AQIDAH X </td>
									<td align="center"> <input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="aqidah10_exemplar" id="aqidah10_exemplar" placeholder="" value="" style="font-size:25px;font-weight: bold" ; /></td>
									<td align="center"><input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="aqidah10_harga" id="aqidah10_harga" placeholder="" value="" style="font-size:25px;font-weight: bold" ; /></td>
									<td align="center"><input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="aqidah10_jmlhal" id="aqidah10_jmlhal" placeholder="Jumlah Halaman" value="<?php echo $jumlah_halaman_trans_pemesanan; ?>" /></td>
									<td align="center"><input type="text" class="form-control" name="aqidah10_tipe" id="aqidah10_tipe" placeholder="Tipe" value="<?php echo $tipe_pesanan_trans_pemesanan; ?>" /></td>
									<td align="center"><input type="text" class="form-control" name="aqidah10_keterangan" id="aqidah10_keterangan" placeholder="Keterangan" value="" /></td>
								</tr>

								<tr>
									<td align="center">AQIDAH XI </td>
									<td align="center"> <input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="aqidah11_exemplar" id="aqidah11_exemplar" placeholder="" value="" style="font-size:25px;font-weight: bold" ; /></td>
									<td align="center"><input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="aqidah11_harga" id="aqidah11_harga" placeholder="" value="" style="font-size:25px;font-weight: bold" ; /></td>
									<td align="center"><input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="aqidah11_jmlhal" id="aqidah11_jmlhal" placeholder="Jumlah Halaman" value="<?php echo $jumlah_halaman_trans_pemesanan; ?>" /></td>
									<td align="center"><input type="text" class="form-control" name="aqidah11_tipe" id="aqidah11_tipe" placeholder="Tipe" value="<?php echo $tipe_pesanan_trans_pemesanan; ?>" /></td>
									<td align="center"><input type="text" class="form-control" name="aqidah11_keterangan" id="aqidah11_keterangan" placeholder="Keterangan" value="" /></td>
								</tr>

								<tr>
									<td align="center">AQIDAH XII </td>
									<td align="center"> <input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="aqidah12_exemplar" id="aqidah12_exemplar" placeholder="" value="" style="font-size:25px;font-weight: bold" ; /></td>
									<td align="center"><input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="aqidah12_harga" id="aqidah12_harga" placeholder="" value="" style="font-size:25px;font-weight: bold" ; /></td>
									<td align="center"><input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="aqidah12_jmlhal" id="aqidah12_jmlhal" placeholder="Jumlah Halaman" value="<?php echo $jumlah_halaman_trans_pemesanan; ?>" /></td>
									<td align="center"><input type="text" class="form-control" name="aqidah12_tipe" id="aqidah12_tipe" placeholder="Tipe" value="<?php echo $tipe_pesanan_trans_pemesanan; ?>" /></td>
									<td align="center"><input type="text" class="form-control" name="aqidah12_keterangan" id="aqidah12_keterangan" placeholder="Keterangan" value="" /></td>
								</tr>


								<tr>
									<td align="center">SKI X </td>
									<td align="center"> <input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="ski10_exemplar" id="ski10_exemplar" placeholder="" value="" style="font-size:25px;font-weight: bold" ; /></td>
									<td align="center"><input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="ski10_harga" id="ski10_harga" placeholder="" value="" style="font-size:25px;font-weight: bold" ; /></td>
									<td align="center"><input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="ski10_jmlhal" id="ski10_jmlhal" placeholder="Jumlah Halaman" value="<?php echo $jumlah_halaman_trans_pemesanan; ?>" /></td>
									<td align="center"><input type="text" class="form-control" name="ski10_tipe" id="ski10_tipe" placeholder="Tipe" value="<?php echo $tipe_pesanan_trans_pemesanan; ?>" /></td>
									<td align="center"><input type="text" class="form-control" name="ski10_keterangan" id="ski10_keterangan" placeholder="Keterangan" value="" /></td>
								</tr>

								<tr>
									<td align="center">SKI XI </td>
									<td align="center"> <input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="ski11_exemplar" id="ski11_exemplar" placeholder="" value="" style="font-size:25px;font-weight: bold" ; /></td>
									<td align="center"><input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="ski11_harga" id="ski11_harga" placeholder="" value="" style="font-size:25px;font-weight: bold" ; /></td>
									<td align="center"><input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="ski11_jmlhal" id="ski11_jmlhal" placeholder="Jumlah Halaman" value="<?php echo $jumlah_halaman_trans_pemesanan; ?>" /></td>
									<td align="center"><input type="text" class="form-control" name="ski11_tipe" id="ski11_tipe" placeholder="Tipe" value="<?php echo $tipe_pesanan_trans_pemesanan; ?>" /></td>
									<td align="center"><input type="text" class="form-control" name="ski11_keterangan" id="ski11_keterangan" placeholder="Keterangan" value="" /></td>
								</tr>

								<tr>
									<td align="center">SKI XII </td>
									<td align="center"> <input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="ski12_exemplar" id="ski12_exemplar" placeholder="" value="" style="font-size:25px;font-weight: bold" ; /></td>
									<td align="center"><input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="ski12_harga" id="ski12_harga" placeholder="" value="" style="font-size:25px;font-weight: bold" ; /></td>
									<td align="center"><input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="ski12_jmlhal" id="ski12_jmlhal" placeholder="Jumlah Halaman" value="<?php echo $jumlah_halaman_trans_pemesanan; ?>" /></td>
									<td align="center"><input type="text" class="form-control" name="ski12_tipe" id="ski12_tipe" placeholder="Tipe" value="<?php echo $tipe_pesanan_trans_pemesanan; ?>" /></td>
									<td align="center"><input type="text" class="form-control" name="ski12_keterangan" id="ski12_keterangan" placeholder="Keterangan" value="" /></td>
								</tr>






								<tr>
									<td align="center">KODE PENJUALAN</td>
									<td align="center"><input type="number" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="kode_pemesanan" id="kode_pemesanan" placeholder="kode pemesanan" value="<?php echo $kode_pemesanan; ?>" /></td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="center"></td>
								</tr>






								<tr>
									<td align="center"><a href="<?php echo site_url('trans_pemesanan') ?>" class="btn btn-info"><i class="fa fa-sign-out"></i>
											Kembali < /a>
									</td>
									<td align="center">
										<button type="submit" class="btn btn-outline-info btn-block btn-flat">
											<i class="fa fa-book"></i> <?php echo $button ?>
										</button>

									</td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="center"></td>
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