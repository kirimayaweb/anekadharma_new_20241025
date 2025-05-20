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
        <h2 style="margin-top:0px">Jurnal_penyesuaian <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Uuid Jurnal Penyesuaian <?php echo form_error('uuid_jurnal_penyesuaian') ?></label>
            <input type="text" class="form-control" name="uuid_jurnal_penyesuaian" id="uuid_jurnal_penyesuaian" placeholder="Uuid Jurnal Penyesuaian" value="<?php echo $uuid_jurnal_penyesuaian; ?>" />
        </div>
	    <div class="form-group">
            <label for="kode_akun">Kode Akun <?php echo form_error('kode_akun') ?></label>
            <textarea class="form-control" rows="3" name="kode_akun" id="kode_akun" placeholder="Kode Akun"><?php echo $kode_akun; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="datetime">Tanggal <?php echo form_error('tanggal') ?></label>
            <input type="text" class="form-control" name="tanggal" id="tanggal" placeholder="Tanggal" value="<?php echo $tanggal; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Keterangan <?php echo form_error('keterangan') ?></label>
            <input type="text" class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan" value="<?php echo $keterangan; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Kode Rekening <?php echo form_error('kode_rekening') ?></label>
            <input type="text" class="form-control" name="kode_rekening" id="kode_rekening" placeholder="Kode Rekening" value="<?php echo $kode_rekening; ?>" />
        </div>
	    <div class="form-group">
            <label for="decimal">Debet <?php echo form_error('debet') ?></label>
            <input type="text" class="form-control" name="debet" id="debet" placeholder="Debet" value="<?php echo $debet; ?>" />
        </div>
	    <div class="form-group">
            <label for="decimal">Kredit <?php echo form_error('kredit') ?></label>
            <input type="text" class="form-control" name="kredit" id="kredit" placeholder="Kredit" value="<?php echo $kredit; ?>" />
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('jurnal_penyesuaian') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>