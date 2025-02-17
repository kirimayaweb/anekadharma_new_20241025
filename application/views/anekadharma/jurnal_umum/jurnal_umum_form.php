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
        <h2 style="margin-top:0px">Jurnal_umum <?php echo $button ?></h2>
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
            <label for="int">Pl <?php echo form_error('pl') ?></label>
            <input type="text" class="form-control" name="pl" id="pl" placeholder="Pl" value="<?php echo $pl; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Ref <?php echo form_error('ref') ?></label>
            <input type="text" class="form-control" name="ref" id="ref" placeholder="Ref" value="<?php echo $ref; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Uraian Kode Rekening <?php echo form_error('uraian_kode_rekening') ?></label>
            <input type="text" class="form-control" name="uraian_kode_rekening" id="uraian_kode_rekening" placeholder="Uraian Kode Rekening" value="<?php echo $uraian_kode_rekening; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Rekening <?php echo form_error('rekening') ?></label>
            <input type="text" class="form-control" name="rekening" id="rekening" placeholder="Rekening" value="<?php echo $rekening; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Debit <?php echo form_error('debit') ?></label>
            <input type="text" class="form-control" name="debit" id="debit" placeholder="Debit" value="<?php echo $debit; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Kredit <?php echo form_error('kredit') ?></label>
            <input type="text" class="form-control" name="kredit" id="kredit" placeholder="Kredit" value="<?php echo $kredit; ?>" />
        </div>
	    <input type="hidden" name="nomor" value="<?php echo $nomor; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('jurnal_umum') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>