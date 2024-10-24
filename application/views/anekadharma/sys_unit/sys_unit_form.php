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
        <h2 style="margin-top:0px">Sys_unit <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Uuid Unit <?php echo form_error('uuid_unit') ?></label>
            <input type="text" class="form-control" name="uuid_unit" id="uuid_unit" placeholder="Uuid Unit" value="<?php echo $uuid_unit; ?>" />
        </div>
	    <div class="form-group">
            <label for="kode_unit">Kode Unit <?php echo form_error('kode_unit') ?></label>
            <textarea class="form-control" rows="3" name="kode_unit" id="kode_unit" placeholder="Kode Unit"><?php echo $kode_unit; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="nama_unit">Nama Unit <?php echo form_error('nama_unit') ?></label>
            <textarea class="form-control" rows="3" name="nama_unit" id="nama_unit" placeholder="Nama Unit"><?php echo $nama_unit; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="keterangan">Keterangan <?php echo form_error('keterangan') ?></label>
            <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"><?php echo $keterangan; ?></textarea>
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('sys_unit') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>