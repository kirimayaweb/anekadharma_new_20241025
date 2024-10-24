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
        <h2 style="margin-top:0px">Sys_gudang <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Uuid Gudang <?php echo form_error('uuid_gudang') ?></label>
            <input type="text" class="form-control" name="uuid_gudang" id="uuid_gudang" placeholder="Uuid Gudang" value="<?php echo $uuid_gudang; ?>" />
        </div>
	    <div class="form-group">
            <label for="kode_gudang">Kode Gudang <?php echo form_error('kode_gudang') ?></label>
            <textarea class="form-control" rows="3" name="kode_gudang" id="kode_gudang" placeholder="Kode Gudang"><?php echo $kode_gudang; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="nama_gudang">Nama Gudang <?php echo form_error('nama_gudang') ?></label>
            <textarea class="form-control" rows="3" name="nama_gudang" id="nama_gudang" placeholder="Nama Gudang"><?php echo $nama_gudang; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="alamat">Alamat <?php echo form_error('alamat') ?></label>
            <textarea class="form-control" rows="3" name="alamat" id="alamat" placeholder="Alamat"><?php echo $alamat; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="keterangan">Keterangan <?php echo form_error('keterangan') ?></label>
            <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"><?php echo $keterangan; ?></textarea>
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('sys_gudang') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>