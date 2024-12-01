<!doctype html>
<html>
    <head>
        <title>aneka dharma</title>
        <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>"/>
        <style>
            body{
                padding: 15px;
            }
        </style>
    </head>
    <body>
        <h2 style="margin-top:0px">Jurnal_kas <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Tanggal <?php echo form_error('tanggal') ?></label>
            <input type="text" class="form-control" name="tanggal" id="tanggal" placeholder="Tanggal" value="<?php echo $tanggal; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Bukti <?php echo form_error('bukti') ?></label>
            <input type="text" class="form-control" name="bukti" id="bukti" placeholder="Bukti" value="<?php echo $bukti; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Keterangan <?php echo form_error('keterangan') ?></label>
            <input type="text" class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan" value="<?php echo $keterangan; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Kode Rekening <?php echo form_error('kode_rekening') ?></label>
            <input type="text" class="form-control" name="kode_rekening" id="kode_rekening" placeholder="Kode Rekening" value="<?php echo $kode_rekening; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Debet <?php echo form_error('debet') ?></label>
            <input type="text" class="form-control" name="debet" id="debet" placeholder="Debet" value="<?php echo $debet; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Kredit <?php echo form_error('kredit') ?></label>
            <input type="text" class="form-control" name="kredit" id="kredit" placeholder="Kredit" value="<?php echo $kredit; ?>" />
        </div>
	    <input type="hidden" name="nomor" value="<?php echo $nomor; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('jurnal_kas') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>