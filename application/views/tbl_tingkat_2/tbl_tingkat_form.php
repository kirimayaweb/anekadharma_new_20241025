<div class="content-wrapper">
    
    <section class="content">
        <div class="box box-warning box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">INPUT DATA TBL_TINGKAT</h3>
            </div>
            <form action="<?php echo $action; ?>" method="post">
            
<table class='table table-bordered>'        

	    <tr><td width='200'>Id <?php echo form_error('id') ?></td><td><input type="text" class="form-control" name="id" id="id" placeholder="Id" value="<?php echo $id; ?>" /></td></tr>
	    <tr><td width='200'>Uuid Tingkat <?php echo form_error('uuid_tingkat') ?></td><td><input type="text" class="form-control" name="uuid_tingkat" id="uuid_tingkat" placeholder="Uuid Tingkat" value="<?php echo $uuid_tingkat; ?>" /></td></tr>
	    
        <tr><td width='200'>Kode Tingkat <?php echo form_error('kode_tingkat') ?></td><td> <textarea class="form-control" rows="3" name="kode_tingkat" id="kode_tingkat" placeholder="Kode Tingkat"><?php echo $kode_tingkat; ?></textarea></td></tr>
	    <tr><td width='200'>Date Input <?php echo form_error('date_input') ?></td><td><input type="text" class="form-control" name="date_input" id="date_input" placeholder="Date Input" value="<?php echo $date_input; ?>" /></td></tr>
	    <tr><td width='200'>Id User Input <?php echo form_error('id_user_input') ?></td><td><input type="text" class="form-control" name="id_user_input" id="id_user_input" placeholder="Id User Input" value="<?php echo $id_user_input; ?>" /></td></tr>
	    <tr><td width='200'>Tingkat <?php echo form_error('tingkat') ?></td><td><input type="text" class="form-control" name="tingkat" id="tingkat" placeholder="Tingkat" value="<?php echo $tingkat; ?>" /></td></tr>
	    
        <tr><td width='200'>Keterangan <?php echo form_error('keterangan') ?></td><td> <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"><?php echo $keterangan; ?></textarea></td></tr>
	    <tr><td></td><td><input type="hidden" name="" value="<?php echo $; ?>" /> 
	    <button type="submit" class="btn btn-danger"><i class="fa fa-floppy-o"></i> <?php echo $button ?></button> 
	    <a href="<?php echo site_url('tbl_tingkat_2') ?>" class="btn btn-info"><i class="fa fa-sign-out"></i> Kembali</a></td></tr>
	</table></form>        </div>
</div>
</div>