<div class="content-wrapper">
    
    <section class="content">
        <div class="box box-warning box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">INPUT DATA TBL_PENGIRIM</h3>
            </div>
            <form action="<?php echo $action; ?>" method="post">
            
<table class='table table-bordered>'        

	    <tr><td width='200'>Id <?php echo form_error('id') ?></td><td><input type="text" class="form-control" name="id" id="id" placeholder="Id" value="<?php echo $id; ?>" /></td></tr>
	    <tr><td width='200'>Uuid Pengirim <?php echo form_error('uuid_pengirim') ?></td><td><input type="text" class="form-control" name="uuid_pengirim" id="uuid_pengirim" placeholder="Uuid Pengirim" value="<?php echo $uuid_pengirim; ?>" /></td></tr>
	    <tr><td width='200'>Date Input <?php echo form_error('date_input') ?></td><td><input type="text" class="form-control" name="date_input" id="date_input" placeholder="Date Input" value="<?php echo $date_input; ?>" /></td></tr>
	    <tr><td width='200'>Id User Input <?php echo form_error('id_user_input') ?></td><td><input type="text" class="form-control" name="id_user_input" id="id_user_input" placeholder="Id User Input" value="<?php echo $id_user_input; ?>" /></td></tr>
	    
        <tr><td width='200'>Nama Pengirim <?php echo form_error('nama_pengirim') ?></td><td> <textarea class="form-control" rows="3" name="nama_pengirim" id="nama_pengirim" placeholder="Nama Pengirim"><?php echo $nama_pengirim; ?></textarea></td></tr>
	    
        <tr><td width='200'>Alamat Pengirim <?php echo form_error('alamat_pengirim') ?></td><td> <textarea class="form-control" rows="3" name="alamat_pengirim" id="alamat_pengirim" placeholder="Alamat Pengirim"><?php echo $alamat_pengirim; ?></textarea></td></tr>
	    
        <tr><td width='200'>Notelp Pengirim <?php echo form_error('notelp_pengirim') ?></td><td> <textarea class="form-control" rows="3" name="notelp_pengirim" id="notelp_pengirim" placeholder="Notelp Pengirim"><?php echo $notelp_pengirim; ?></textarea></td></tr>
	    
        <tr><td width='200'>Keterangan <?php echo form_error('keterangan') ?></td><td> <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"><?php echo $keterangan; ?></textarea></td></tr>
	    <tr><td></td><td><input type="hidden" name="" value="<?php echo $; ?>" /> 
	    <button type="submit" class="btn btn-danger"><i class="fa fa-floppy-o"></i> <?php echo $button ?></button> 
	    <a href="<?php echo site_url('tbl_pengirim') ?>" class="btn btn-info"><i class="fa fa-sign-out"></i> Kembali</a></td></tr>
	</table></form>        </div>
</div>
</div>