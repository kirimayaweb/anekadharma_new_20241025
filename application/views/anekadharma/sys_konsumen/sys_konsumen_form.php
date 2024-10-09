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
        <h2 style="margin-top:0px">Sys_konsumen <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Uuid Konsumen <?php echo form_error('uuid_konsumen') ?></label>
            <input type="text" class="form-control" name="uuid_konsumen" id="uuid_konsumen" placeholder="Uuid Konsumen" value="<?php echo $uuid_konsumen; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Kode Konsumen <?php echo form_error('kode_konsumen') ?></label>
            <input type="text" class="form-control" name="kode_konsumen" id="kode_konsumen" placeholder="Kode Konsumen" value="<?php echo $kode_konsumen; ?>" />
        </div>
	    <div class="form-group">
            <label for="nama_konsumen">Nama Konsumen <?php echo form_error('nama_konsumen') ?></label>
            <textarea class="form-control" rows="3" name="nama_konsumen" id="nama_konsumen" placeholder="Nama Konsumen"><?php echo $nama_konsumen; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="nmr_kontak_konsumen">Nmr Kontak Konsumen <?php echo form_error('nmr_kontak_konsumen') ?></label>
            <textarea class="form-control" rows="3" name="nmr_kontak_konsumen" id="nmr_kontak_konsumen" placeholder="Nmr Kontak Konsumen"><?php echo $nmr_kontak_konsumen; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="alamat_konsumen">Alamat Konsumen <?php echo form_error('alamat_konsumen') ?></label>
            <textarea class="form-control" rows="3" name="alamat_konsumen" id="alamat_konsumen" placeholder="Alamat Konsumen"><?php echo $alamat_konsumen; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="keterangan">Keterangan <?php echo form_error('keterangan') ?></label>
            <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"><?php echo $keterangan; ?></textarea>
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('sys_konsumen') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>