<!doctype html>
<html>
    <head>
        <title>harviacode.com - codeigniter crud generator</title>
        <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>"/>
        <style>
            body{
                padding: 15px;
            }
        </style>
    </head>
    <body>
        <h2 style="margin-top:0px">Sys_bank <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	   
	    <div class="form-group">
            <label for="kode_bank">Kode Bank <?php echo form_error('kode_bank') ?></label>
            <textarea class="form-control" rows="3" name="kode_bank" id="kode_bank" placeholder="Kode Bank"><?php echo $kode_bank; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="nama_bank">Nama Bank <?php echo form_error('nama_bank') ?></label>
            <textarea class="form-control" rows="3" name="nama_bank" id="nama_bank" placeholder="Nama Bank"><?php echo $nama_bank; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="nmr_rekening">Nmr Rekening <?php echo form_error('nmr_rekening') ?></label>
            <textarea class="form-control" rows="3" name="nmr_rekening" id="nmr_rekening" placeholder="Nmr Rekening"><?php echo $nmr_rekening; ?></textarea>
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('sys_bank') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>