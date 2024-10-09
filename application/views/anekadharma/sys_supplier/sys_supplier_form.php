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
        <h2 style="margin-top:0px">Sys_supplier <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Uuid Supplier <?php echo form_error('uuid_supplier') ?></label>
            <input type="text" class="form-control" name="uuid_supplier" id="uuid_supplier" placeholder="Uuid Supplier" value="<?php echo $uuid_supplier; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Kode Supplier <?php echo form_error('kode_supplier') ?></label>
            <input type="text" class="form-control" name="kode_supplier" id="kode_supplier" placeholder="Kode Supplier" value="<?php echo $kode_supplier; ?>" />
        </div>
	    <div class="form-group">
            <label for="nama_supplier">Nama Supplier <?php echo form_error('nama_supplier') ?></label>
            <textarea class="form-control" rows="3" name="nama_supplier" id="nama_supplier" placeholder="Nama Supplier"><?php echo $nama_supplier; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="nmr_kontak_supplier">Nmr Kontak Supplier <?php echo form_error('nmr_kontak_supplier') ?></label>
            <textarea class="form-control" rows="3" name="nmr_kontak_supplier" id="nmr_kontak_supplier" placeholder="Nmr Kontak Supplier"><?php echo $nmr_kontak_supplier; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="alamat_supplier">Alamat Supplier <?php echo form_error('alamat_supplier') ?></label>
            <textarea class="form-control" rows="3" name="alamat_supplier" id="alamat_supplier" placeholder="Alamat Supplier"><?php echo $alamat_supplier; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="keterangan">Keterangan <?php echo form_error('keterangan') ?></label>
            <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"><?php echo $keterangan; ?></textarea>
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('sys_supplier') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>