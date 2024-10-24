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
        <h2 style="margin-top:0px">Sys_kode_akun <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Uuid Kode Akun <?php echo form_error('uuid_kode_akun') ?></label>
            <input type="text" class="form-control" name="uuid_kode_akun" id="uuid_kode_akun" placeholder="Uuid Kode Akun" value="<?php echo $uuid_kode_akun; ?>" />
        </div>
	    <div class="form-group">
            <label for="kode_akun">Kode Akun <?php echo form_error('kode_akun') ?></label>
            <textarea class="form-control" rows="3" name="kode_akun" id="kode_akun" placeholder="Kode Akun"><?php echo $kode_akun; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="nama_akun">Nama Akun <?php echo form_error('nama_akun') ?></label>
            <textarea class="form-control" rows="3" name="nama_akun" id="nama_akun" placeholder="Nama Akun"><?php echo $nama_akun; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="group">Group <?php echo form_error('group') ?></label>
            <textarea class="form-control" rows="3" name="group" id="group" placeholder="Group"><?php echo $group; ?></textarea>
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('sys_kode_akun') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>