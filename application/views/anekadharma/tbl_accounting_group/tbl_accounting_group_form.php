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
        <h2 style="margin-top:0px">Tbl_accounting_group <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Uuid Group <?php echo form_error('uuid_group') ?></label>
            <input type="text" class="form-control" name="uuid_group" id="uuid_group" placeholder="Uuid Group" value="<?php echo $uuid_group; ?>" />
        </div>
	    <div class="form-group">
            <label for="nama_group">Nama Group <?php echo form_error('nama_group') ?></label>
            <textarea class="form-control" rows="3" name="nama_group" id="nama_group" placeholder="Nama Group"><?php echo $nama_group; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="keterangan">Keterangan <?php echo form_error('keterangan') ?></label>
            <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"><?php echo $keterangan; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="int">Id Usr <?php echo form_error('id_usr') ?></label>
            <input type="text" class="form-control" name="id_usr" id="id_usr" placeholder="Id Usr" value="<?php echo $id_usr; ?>" />
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('tbl_accounting_group') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>