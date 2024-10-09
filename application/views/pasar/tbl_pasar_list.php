<!-- Main content -->
<section class='content'>

	<div class='col-xs-12'>
		<div class='box'>
			<div class='box-header btn-success' align="center">
				<!-- <div class='col-xs-2'> -->
				<h3 class='box-title'> <strong> LIST DATA PASAR </strong></h3>
			</div>
		</div>

		<div class='col-xs-12' align="center">


			<?php
			$cek = $this->session->userdata('company');
			if ($cek == 'administrator'  or $cek == 'admin'  or $cek == 'adminretribusi'  or $cek == 'adminretribusi'  or $cek == 'adminpasar' or $cek == 'pasar') {
				echo anchor('tbl_pasar/create/', 'Tambah Data Pasar(KIOS)', array('class' => 'btn btn-danger btn-sm'));
			}
			?>



			<select name="combo_nama_pasar" id="combo_nama_pasar" class="form-control select2" style="width: 50%; height: 40px;">
				<?php



				if ($cek == 'pasar') {
					$kodepasar = $this->session->userdata('kodepasar');
					// $sql = "select tbl_pasar.*,tbl_identitas_pedagang_pasar.nama from tbl_pasar left join tbl_identitas_pedagang_pasar on tbl_identitas_pedagang_pasar.nik=tbl_pasar.nik where tbl_pasar.kodepasar=$kodepasar";

					$namapasar = $this->db->get_where('sys_pasar', array('kode_pasar' => $kodepasar));
				} elseif ($cek == 'lurahpasar') {
					$kodepasar = $this->session->userdata('kodepasar');
					// $sql = "select tbl_pasar.*,tbl_identitas_pedagang_pasar.nama from tbl_pasar left join tbl_identitas_pedagang_pasar on tbl_identitas_pedagang_pasar.nik=tbl_pasar.nik where tbl_pasar.kodepasar=$kodepasar";
					$namapasar = $this->db->get_where('sys_pasar', array('kode_pasar' => $kodepasar));
				} else {
					$namapasar = $this->db->get('sys_pasar');
				}



				foreach ($namapasar->result() as $nama_pasar) {
					echo "<option value='$nama_pasar->kode_pasar' ";
					echo ">" . strtoupper($nama_pasar->kode_pasar) . " = " . strtoupper($nama_pasar->nama_pasar) . " (Tipe = " . strtoupper($nama_pasar->tipe_pasar) . ")</option>";
				}
				?>
			</select>
			<?php echo anchor('/tbl_pasar', 'Filter Pasar', array('class' => 'btn btn-success btn-sm')); ?>
		</div> <br> <br>
	</div><!-- /.box-header --> <br>
	<div class='box-body'>
		<table class="table table-bordered table-striped" id="mytable">
			<thead>
				<tr>
					<th style="text-align:center" width="80px">NO</th>

					<?php
					if ($cek == 'administrator'  or $cek == 'admin'  or $cek == 'adminretribusi'  or $cek == 'adminretribusi'  or $cek == 'adminpasar' or $cek == 'pasar') {
					?>
						<th style="text-align:center" width="40px">AKSI</th>
					<?php
					}
					?>


					<th style="text-align:center" width="80px">NIK
						<hr /> NAMA
					</th>
					<th style="text-align:center"> KODE RETRIBUSI</th>
					<th style="text-align:center">JENIS USAHA
						<hr /> STATUS
					</th>
					<th style="text-align:center">NAMA PASAR</th>
					<th width="180px">TGL MULAI /
						<hr />AKHIR
					</th>
					<th style="text-align:center" width="180px">TARIF
						<hr /> TARIF x P x L
					</th>
					<th style="text-align:center">DENDA /HARI</th>
					<th style="text-align:center">KODE PASAR</th>
					<th style="text-align:center">TIPE PASAR</th>
					<th style="text-align:center">JENIS RUANG</th>
					<th style="text-align:center">BLOK</th>
					<th style="text-align:center">NOMOR URUT DASARAN</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$start = 0;
				foreach ($tbl_pasar_data as $tbl_pasar) {
				?>
					<tr>
						<td><?php echo ++$start ?></td>


						<?php
						if ($cek == 'administrator'  or $cek == 'admin'  or $cek == 'adminretribusi'  or $cek == 'adminretribusi' or $cek == 'adminpasar' or $cek == 'pasar') {
						?>
							<td style="text-align:center" width="40px" top="50%" position="absolute">
								<?php
								if (strtotime($tbl_pasar->tglakhir) > strtotime('now')) {
									echo anchor(site_url('tbl_pasar/update_data_kios_pedagang/' . $tbl_pasar->id), '<i class="fa fa-pencil-square-o">Ubah Data</i>', array('title' => 'edit', 'class' => 'btn btn-block btn-warning btn-sm'));
								} else {
									echo anchor(site_url('tbl_pasar/update/' . $tbl_pasar->id), '<i class="fa fa-pencil-square-o">Ubah Data KIOS</i>', array('title' => 'edit', 'class' => 'btn btn-block btn-success btn-sm'));
								}
								?>
							</td>

						<?php
						}
						?>




						<td style="text-align:center" width="80px">
							<?php
							if (strtotime($tbl_pasar->tglakhir) > strtotime('now')) {
								echo $tbl_pasar->nik;
								echo "<br/>";
								echo "<strong>";
								tampil(stripslashes($tbl_pasar->nama));
								echo "</strong>";
								echo "<br/>";
								// echo anchor(site_url('index.php/tbl_pasar/word_skhp/'.$tbl_pasar->id),'<i class="fa fa-pencil-square-o">CETAK SKHP</i>',array('title'=>'edit','class'=>'btn btn-block btn-success btn-sm'));
								// echo anchor(site_url('index.php/tbl_pasar/skhp_word_controller/' . $tbl_pasar->koderetribusi), '<i class="fa fa-pencil-square-o">CETAK SKHP</i>', array('title' => 'edit', 'class' => 'btn btn-block btn-success btn-sm'));
							} else {
								echo anchor(site_url('index.php/tbl_pasar/Assign_pedagang/' . $tbl_pasar->id), ' <i class="fa fa-file-excel-o"></i> HABIS/Aktivasi Sewa', 'class="btn btn-primary btn-sm"');
							}

							?>
						</td>

						<td><?php
							echo $tbl_pasar->koderetribusi;
							if (strtotime($tbl_pasar->tglakhir) > strtotime('now')) {
								echo "<br/>";
								echo anchor(site_url('index.php/Tbl_pasar/cetak_skhp_dompdf/' . $tbl_pasar->koderetribusi), '<i class="fa fa-pencil-square-o">CETAK SKHP PDF</i>', array('title' => 'edit', 'class' => 'btn btn-block btn-primary btn-sm', 'target' => '_blank'));
								echo anchor(site_url('index.php/tbl_pasar/skhp_word_controller/' . $tbl_pasar->koderetribusi), '<i class="fa fa-pencil-square-o">CETAK SKHP WORD</i>', array('title' => 'edit', 'class' => 'btn btn-block btn-success btn-sm'));
							}

							?></td>
						<td>
							<?php

							?>


							<?php
							if (strtotime($tbl_pasar->tglakhir) > strtotime('now')) {
								tampil($tbl_pasar->jenis_usaha);
								echo "<br/>";
								// echo anchor(site_url('index.php/Tbl_pasar'), '<i class="fa fa-pencil-square-o">MANGKRAK</i>', array('title' => 'edit', 'class' => 'btn btn-block btn-warning btn-sm'));
							}


							?>

						</td>

						<td><?php tampil($tbl_pasar->namapasar) ?></td>

						<td><?php tampil($tbl_pasar->tglmulai) ?><br /><?php tampil($tbl_pasar->tglakhir) ?></td>
						<!--<td><?php //echo $tbl_pasar->tglakhir
								?></td>-->

						<td width="180px" align="right">
							<?php
							echo "<strong>";
							tampil(nominal($tbl_pasar->tarifkelaspasar * $tbl_pasar->panjang * $tbl_pasar->lebar));
							echo "</strong>";
							echo "<br/>";
							echo "( ";
							tampil($tbl_pasar->tarifkelaspasar);
							echo " x ";
							tampil($tbl_pasar->panjang);
							echo  " x ";
							tampil($tbl_pasar->lebar);
							echo " )";
							?>

						</td>



						<td><?php tampil($tbl_pasar->denda) ?></td>
						<td><?php tampil($tbl_pasar->kodepasar) ?></td>
						<td><?php tampil($tbl_pasar->tipe_pasar) ?></td>
						<td><?php tampil($tbl_pasar->jenis_ruang) ?></td>
						<td><?php tampil($tbl_pasar->blok) ?></td>
						<td><?php tampil($tbl_pasar->nmr_urut_dasaran) ?></td>
						<!-- <td><?php echo $tbl_pasar->panjang ?></td>
<td><?php echo $tbl_pasar->lebar ?></td> -->

					</tr>
				<?php
				}
				?>
			</tbody>
		</table>

		<script src="<?php echo base_url('assets/js/jquery-1.11.2.min.js') ?>"></script>
		<script src="<?php echo base_url('assets/datatables/jquery.dataTables.js') ?>"></script>
		<script src="<?php echo base_url('assets/datatables/dataTables.bootstrap.js') ?>"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$("#mytable").dataTable({
					scrollY: 500,
					scrollX: true
				});
			});
		</script>
		<!-- /.box-body -->
		<!-- /.box -->
		<!-- /.col -->

</section><!-- /.content -->