<div class="content-wrapper">

	<section class="content">
		<div class="box box-warning box-solid">
			<div class="box-header with-border">
				<h3 class="box-title">INPUT DATA TBL_STOK_BARANG</h3>
			</div>
			<form action="<?php echo $action; ?>" method="post">

				<table class='table table-bordered>' <tr>
					<!-- <td width='200'>Uuid Stock <?php //echo form_error('uuid_stock') 
													?></td>
					<td><input type="text" class="form-control" name="uuid_stock" id="uuid_stock" placeholder="Uuid Stock" value="<?php //echo $uuid_stock; 
																																	?>" /></td>
					</tr>
					<tr>
						<td width='200'>Date Input <?php //echo form_error('date_input') 
													?></td>
						<td><input type="text" class="form-control" name="date_input" id="date_input" placeholder="Date Input" value="<?php //echo $date_input; 
																																		?>" /></td>
					</tr> -->

					<!-- <tr>
						<td width='200'>Id User Input <?php //echo form_error('id_user_input') 
														?></td>
						<td><input type="text" class="form-control" name="id_user_input" id="id_user_input" placeholder="Id User Input" value="<?php //echo $id_user_input; 
																																				?>" /></td>
					</tr> -->
					<tr>
						<td width='200'>Id Tbl Produk <?php echo form_error('id_tbl_produk') ?></td>
						<td><input type="text" class="form-control" name="id_tbl_produk" id="id_tbl_produk" placeholder="Id Tbl Produk" value="<?php echo $id_tbl_produk; ?>" /></td>
					</tr>
					<tr>
						<td width='200'>Id Tbl Company Cetak <?php echo form_error('id_tbl_company_cetak') ?></td>
						<td><input type="text" class="form-control" name="id_tbl_company_cetak" id="id_tbl_company_cetak" placeholder="Id Tbl Company Cetak" value="<?php echo $id_tbl_company_cetak; ?>" /></td>
					</tr>
					<tr>
						<td width='200'>Id Tbl Finishing <?php echo form_error('id_tbl_finishing') ?></td>
						<td><input type="text" class="form-control" name="id_tbl_finishing" id="id_tbl_finishing" placeholder="Id Tbl Finishing" value="<?php echo $id_tbl_finishing; ?>" /></td>
					</tr>
					<tr>
						<td width='200'>Id Cover <?php echo form_error('id_cover') ?></td>
						<td><input type="text" class="form-control" name="id_cover" id="id_cover" placeholder="Id Cover" value="<?php echo $id_cover; ?>" /></td>
					</tr>
					<tr>
						<td width='200'>Jumlah Produk <?php echo form_error('jumlah_produk') ?></td>
						<td><input type="text" class="form-control" name="jumlah_produk" id="jumlah_produk" placeholder="Jumlah Produk" value="<?php echo $jumlah_produk; ?>" /></td>
					</tr>
					<tr>
						<td width='200'>Halaman <?php echo form_error('halaman') ?></td>
						<td><input type="text" class="form-control" name="halaman" id="halaman" placeholder="Halaman" value="<?php echo $halaman; ?>" /></td>
					</tr>

					<tr>
						<td width='200'>Keterangan <?php echo form_error('keterangan') ?></td>
						<td> <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"><?php echo $keterangan; ?></textarea></td>
					</tr>
					<tr>
						<td></td>
						<td><input type="hidden" name="id_stock" value="<?php echo $id_stock; ?>" />
							<button type="submit" class="btn btn-danger"><i class="fa fa-floppy-o"></i> <?php echo $button ?></button>
							<a href="<?php echo site_url('tbl_stok_barang') ?>" class="btn btn-info"><i class="fa fa-sign-out"></i> Kembali</a>
						</td>
					</tr>
				</table>
			</form>
		</div>
</div>
</div>