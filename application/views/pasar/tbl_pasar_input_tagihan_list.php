<!-- Main content -->
<section class='content'>
	<div class='row'>
		<div class='col-xs-12'>
			<div class='box'>
				<div class='box-header btn-success' align="center">

					<div class='col-xs-12'>
						<h3 class='box-title'> <strong> LIST DATA INPUT TAGIHAN </strong></h3>
					</div>
				</div>
			</div>
			<div class='col-xs-12' align="center">

				<select name="combo_nama_pasar" id="combo_nama_pasar" class="form-control select2" style="width: 50%; height: 40px;">
					<?php



					$cek = $this->session->userdata('company');

					if ($cek == 'pasar') {
						$kodepasar = $this->session->userdata('kodepasar');
						$namapasar = $this->db->get_where('sys_pasar', array('kode_pasar' => $kodepasar));
					} elseif ($cek == 'lurahpasar') {
						$kodepasar = $this->session->userdata('kodepasar');
						$namapasar = $this->db->get_where('sys_pasar', array('kode_pasar' => $kodepasar));
					} else {
						$namapasar = $this->db->get('sys_pasar');
					}


					foreach ($namapasar->result() as $nama_pasar) {
						echo "<option value='$nama_pasar->kode_pasar' ";
						echo ">" .  strtoupper($nama_pasar->kode_pasar) . " = " .  strtoupper($nama_pasar->nama_pasar) . " (Tipe = " .  strtoupper($nama_pasar->tipe_pasar) . ")</option>";
					}
					?>
				</select>
				<?php echo anchor('/Tbl_pasar_input_tagihan', 'Filter Pasar', array('class' => 'btn btn-success btn-sm')); ?>
			</div>
		</div>

		<br> <!-- /.box-header -->
		<div class='box-body'>
			<table class="table table-bordered table-striped" id="mytable">
				<thead>
					<tr align="center">
						<th style="text-align:center" width="40px">NO</th>

						<?php
						if ($cek == 'administrator'  or $cek == 'admin'  or $cek == 'adminretribusi'  or $cek == 'adminretribusi'  or $cek == 'adminpasar') {
						?>
							<th style="text-align:center" width="100px">PROSES INPUT</th>
						<?php
						}
						?>


						<th style="text-align:center" width="180px">NIK
							<hr> NAMA
						</th>
						<th style="text-align:center"><strong>KODE RETRIBUSI</strong>
							<hr> PASAR
						</th>
						<th style="text-align:center">TGL AKTIF
							<hr> TGL AWAL / TGL AKHIR
						</th>
						</th>
						<th style="text-align:center">JUMLAH HARI</th>
						<th style="text-align:center">TARIF /HARI</th>
						<th style="text-align:center">TAGIHAN KEBERSIHAN</th>
						<th style="text-align:center">TOTAL TAGIHAN</th>
						<th style="text-align:center">KIRIM KE BPD</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$start = 0;
					foreach ($tbl_pasar_input_tagihan_data as $tbl_pasar_input_tagihan) {
					?>
						<tr>
							<td width="40px"><?php echo ++$start ?></td>

							<?php
							if ($cek == 'administrator'  or $cek == 'admin'  or $cek == 'adminretribusi'  or $cek == 'adminretribusi' or $cek == 'adminpasar') {
							?>
								<td style="text-align:center" width="100px">
									<?php
									echo anchor(site_url('tbl_pasar_input_tagihan/update/' . $tbl_pasar_input_tagihan->id), '<i class="fa fa-pencil-square-o">Input DATA</i>', array('title' => 'edit', 'class' => 'btn btn-danger btn-sm'));
									?>
								</td>

							<?php
							}
							?>

							<td width="180px">
								<?php
								tampil($tbl_pasar_input_tagihan->nik);
								echo "<br/>";
								echo "<strong>";
								tampil(stripslashes($tbl_pasar_input_tagihan->nama));
								echo "</strong>";
								?>
							</td>


							<?php if ($tbl_pasar_input_tagihan->jumlah_hari_aktif < 1) { ?>
								<td><?php
									echo "<font color='red'><strong>";
									tampil($tbl_pasar_input_tagihan->koderetribusi);
									echo "</strong></font>";
									echo "<br/>";
									echo "<font color='red'>";
									tampil($tbl_pasar_input_tagihan->posisi);
									echo "</font>";
									?></td>

							<?php } else { ?>
								<td font color="black"><?php
														echo "<strong>";
														tampil($tbl_pasar_input_tagihan->koderetribusi);
														echo "</strong>";
														echo "<br/>";
														echo "<font color='red'>";
														tampil($tbl_pasar_input_tagihan->posisi);
														echo "</font>";
														?></td>
							<?php }  ?>




							<?php if ($tbl_pasar_input_tagihan->jumlah_hari_aktif < 1) { ?>
								<td><?php
									echo "<font color='red'>";
									tampil($tbl_pasar_input_tagihan->tgl_aktif);
									echo "</font>";
									echo "<br/>";
									echo "<font color='red'>";
									tampil($tbl_pasar_input_tagihan->tgl_awal);
									echo " / ";
									tampil($tbl_pasar_input_tagihan->tgl_akhir);
									echo "</font>";


									?></td>

							<?php } else { ?>
								<td><?php
									echo "<font color='black'>";
									tampil($tbl_pasar_input_tagihan->tgl_aktif);
									echo "</font>";
									echo "<br/>";
									echo "<font color='red'>";
									tampil($tbl_pasar_input_tagihan->tgl_awal);
									echo " / ";
									tampil($tbl_pasar_input_tagihan->tgl_akhir);
									echo "</font>";
									?></td>
							<?php }  ?>



							<!-- <td align="right"><?php echo nominal($tbl_pasar_input_tagihan->jumlah_hari) ?></td> -->
							<?php if ($tbl_pasar_input_tagihan->jumlah_hari_aktif < 1) { ?>
								<td><?php
									echo "<font color='red'>";
									tampil(nominal($tbl_pasar_input_tagihan->jumlah_hari_aktif));
									echo "</font>";
									?></td>

							<?php } else { ?>
								<td><?php
									echo "<font color='black'>";
									tampil(nominal($tbl_pasar_input_tagihan->jumlah_hari_aktif));
									echo "</font>";
									?></td>
							<?php }  ?>

							<?php if ($tbl_pasar_input_tagihan->jumlah_hari_aktif < 1) { ?>
								<!-- <td><?php echo "<font color='red'>" . nominal($tbl_pasar_input_tagihan->jumlah_hari) . "</font>" ?></td> -->
								<td align="right"><?php
													echo "<font color='red'><strong>";
													tampil(nominal($tbl_pasar_input_tagihan->tarifkelaspasar * $tbl_pasar_input_tagihan->panjang * $tbl_pasar_input_tagihan->lebar));
													echo "</strong></font>";
													echo "<br/>";
													echo "<font color='red'><strong>";
													tampil(nominal($tbl_pasar_input_tagihan->tarifkelaspasar) . " x " . nominal($tbl_pasar_input_tagihan->panjang) . " x " . nominal($tbl_pasar_input_tagihan->lebar));
													echo "</strong></font>";
													?></td>
							<?php } else { ?>
								<!-- <td><?php echo "<font color='black'>" . nominal($tbl_pasar_input_tagihan->jumlah_hari) . "</font>" ?></td> -->
								<td align="right"><?php
													echo "<font color='black'><strong>";
													tampil(nominal($tbl_pasar_input_tagihan->tarifkelaspasar * $tbl_pasar_input_tagihan->panjang * $tbl_pasar_input_tagihan->lebar));
													echo "</strong></font>";
													echo "<br/>";
													echo "<font color='black'><strong>";
													tampil(nominal($tbl_pasar_input_tagihan->tarifkelaspasar) . " x " . nominal($tbl_pasar_input_tagihan->panjang) . " x " . nominal($tbl_pasar_input_tagihan->lebar));
													echo "</strong></font>";
													?></td>
							<?php }  ?>
							<!-- <td align="right"><?php echo nominal($tbl_pasar_input_tagihan->tarifkebersihan) ?></td> -->
							<?php if ($tbl_pasar_input_tagihan->jumlah_hari_aktif < 1) { ?>
								<td><?php
									echo "<font color='red'>";
									tampil(nominal($tbl_pasar_input_tagihan->tarifkebersihan));
									echo "</font>";
									?></td>

							<?php } else { ?>
								<td><?php
									echo "<font color='black'>";
									tampil(nominal($tbl_pasar_input_tagihan->tarifkebersihan));
									echo "</font>" ?></td>
							<?php }  ?>
							<!-- <td align="right"><?php echo "<strong>" . nominal($tbl_pasar_input_tagihan->jumlah_hari * $tbl_pasar_input_tagihan->tarifkelaspasar + $tbl_pasar_input_tagihan->tarifkebersihan) . "</strong>" ?></td> -->
							<?php if ($tbl_pasar_input_tagihan->jumlah_hari_aktif < 1) { ?>
								<td><?php
									echo "<font color='red'><strong>";
									tampil(nominal($tbl_pasar_input_tagihan->jumlah_hari_aktif * $tbl_pasar_input_tagihan->tarifkelaspasar * $tbl_pasar_input_tagihan->panjang * $tbl_pasar_input_tagihan->lebar));
									echo "</strong></font>";
									?></td>
							<?php } else { ?>
								<td><?php
									echo "<font color='black'><strong>";
									tampil(nominal($tbl_pasar_input_tagihan->jumlah_hari_aktif * $tbl_pasar_input_tagihan->tarifkelaspasar * $tbl_pasar_input_tagihan->panjang * $tbl_pasar_input_tagihan->lebar + $tbl_pasar_input_tagihan->tarifkebersihan));
									echo "</strong></font>";
									?></td>
							<?php }  ?>
							<td style="text-align:center" width="140px">
								<?php
								if ($tbl_pasar_input_tagihan->status_kirim == 'terbayar') {
									echo "TERBAYAR";
								} elseif ($tbl_pasar_input_tagihan->status_kirim == 'kirim_bpd') {
									echo anchor(site_url('tbl_pasar_input_tagihan/kirim_bpd/' . $tbl_pasar_input_tagihan->id), '<i class="fa fa-pencil-square-o">KIRIM ke BPD  =></i>', array('title' => 'edit', 'class' => 'btn btn-block btn-danger'));
								} else {
									if ($tbl_pasar_input_tagihan->jumlah_hari_aktif < 1) {
										echo "<button type='button' class='btn btn-block btn-default'>BELUM BISA DIKIRIM</button>";
									} else {
										echo anchor(site_url('tbl_pasar_input_tagihan/kirim_bpd/' . $tbl_pasar_input_tagihan->id), '<i class="fa fa-pencil-square-o">KIRIM ke BPD  =></i>', array('title' => 'edit', 'class' => 'btn btn-block btn-success'));
									}
								}
								?>
							</td>
						</tr>
					<?php
					}
					?>
				</tbody>
			</table>

			<br><br><br>
			<div class="row">
				<div class="col-sm-8"> </div>
				<div class="col-sm-4">
					<?php
					if ($tbl_pasar_input_tagihan_data) {
						echo anchor(site_url('tbl_pasar_input_tagihan/kirim_bpd_ALL/' . $tbl_pasar_input_tagihan->id), '<i class="fa fa-pencil-square-o">KIRIM SEMUA DATA TAGIHAN ke BPD  =></i>', array('title' => 'edit', 'class' => 'btn btn-block btn-success'));
					} ?>
				</div>
			</div>

			<script src="<?php echo base_url('assets/js/jquery-1.11.2.min.js') ?>"></script>
			<script src="<?php echo base_url('assets/datatables/jquery.dataTables.js') ?>"></script>
			<script src="<?php echo base_url('assets/datatables/dataTables.bootstrap.js') ?>"></script>
			<script type="text/javascript">
				$(document).ready(function() {
					$("#mytable").dataTable({
						scrollY: 400,
						scrollX: true
					});
				});
			</script>
		</div>
		<!-- </div>/.box-body -->
		<!-- </div>/.box -->
		<!-- </div>/.col -->
	</div><!-- /.row -->
</section><!-- /.content -->