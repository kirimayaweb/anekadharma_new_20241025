<div class="content-wrapper">

	<section class="content">
		<div class="box box-warning box-solid">
			<div class="box-header with-border">
				<h3 class="box-title">INPUT DATA USER</h3>
			</div>
			<form action="<?php echo $action; ?>" method="post">

				<table class='table table-bordered>'>
					<tr>
					<td width='200'>NickName <?php echo form_error('full_name') ?></td>
					<td>
						<input type="text" class="form-control" name="full_name" id="full_name" placeholder="Full Name" value="<?php echo $full_name; ?>" /></td>
					</tr>
					<tr>
						<td width='200'>Email (Username) <?php echo form_error('email') ?></td>
						<td>
							<input type="text" class="form-control" name="email" id="email" placeholder="Email" value="<?php echo $email; ?>" />
							Pastikan di isi dengan ....... @gmail.com / ....@xxxxxx.com
						
						


						<?php 
						
							// print_r($this->session->flashdata('ada_email_sama'));

						if ($this->session->flashdata('ada_email_sama')) 
						{ 	
							echo "<br/>";
							echo "<strong style=color:red;> " . json_encode($this->session->flashdata('ada_email_sama')) . "</strong>" ;
                    
							}
							unset($_SESSION['ada_email_sama']);
							?>

						</td>

					</tr>
					<tr>
						<td width='200'>Password <?php echo form_error('password') ?></td>
						<td><input type="password" class="form-control" name="password" id="password" placeholder="Password" value="<?php echo $password; ?>" /></td>
					</tr>
					<tr>
					<td width='200'>No. HP <?php echo form_error('no_hp') ?></td>
					<td>
						<input type="text" class="form-control" name="no_hp" id="no_hp" placeholder="No.HP" value="<?php echo $no_hp; ?>" /></td>
					</tr>

					<tr>
						<td width='200'>Level <?php echo form_error('id_user_level') ?></td>
						<td> <?php echo form_dropdown('id_user_level', array('2' => 'MANAGER', '3' => 'GUDANG', '4' => 'USER', '5' => 'SALES', '6' => 'CUSTOMER'), $id_user_level, array('class' => 'form-control')); ?></td>
					</tr>
					<tr>
						<td width='200'>Status Aktif <?php echo form_error('is_aktif') ?></td>
						<td><?php echo form_dropdown('is_aktif', array('y' => 'AKTIF', 'n' => 'TIDAK AKTIF'), $is_aktif, array('class' => 'form-control')); ?></td>
					</tr>
					<tr>
						<td></td>
						<td><input type="hidden" name="id_users" value="<?php echo $id_users; ?>" />
							<button type="submit" class="btn btn-danger"><i class="fa fa-floppy-o"></i> <?php echo $button ?></button>
							<a href="<?php echo site_url('tbl_user') ?>" class="btn btn-info"><i class="fa fa-sign-out"></i> Kembali</a>
						</td>
					</tr>
				</table>
			</form>
		</div>
</div>
</div>