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
        <h2 style="margin-top:0px">Jurnal_penerimaan_kas <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Tanggal <?php echo form_error('tanggal') ?></label>
            <input type="text" class="form-control" name="tanggal" id="tanggal" placeholder="Tanggal" value="<?php echo $tanggal; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Nomorbuktibkm <?php echo form_error('nomorbuktibkm') ?></label>
            <input type="text" class="form-control" name="nomorbuktibkm" id="nomorbuktibkm" placeholder="Nomorbuktibkm" value="<?php echo $nomorbuktibkm; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Pl <?php echo form_error('pl') ?></label>
            <input type="text" class="form-control" name="pl" id="pl" placeholder="Pl" value="<?php echo $pl; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Keterangan <?php echo form_error('keterangan') ?></label>
            <input type="text" class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan" value="<?php echo $keterangan; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Debet 11101 Kas Besar <?php echo form_error('debet_11101_kas_besar') ?></label>
            <input type="text" class="form-control" name="debet_11101_kas_besar" id="debet_11101_kas_besar" placeholder="Debet 11101 Kas Besar" value="<?php echo $debet_11101_kas_besar; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Kredit 11301 Pu Non Angsuran <?php echo form_error('kredit_11301_pu_non_angsuran') ?></label>
            <input type="text" class="form-control" name="kredit_11301_pu_non_angsuran" id="kredit_11301_pu_non_angsuran" placeholder="Kredit 11301 Pu Non Angsuran" value="<?php echo $kredit_11301_pu_non_angsuran; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Serba Serbi Rekening <?php echo form_error('serba_serbi_rekening') ?></label>
            <input type="text" class="form-control" name="serba_serbi_rekening" id="serba_serbi_rekening" placeholder="Serba Serbi Rekening" value="<?php echo $serba_serbi_rekening; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Serba Serbi Jumlah <?php echo form_error('serba_serbi_jumlah') ?></label>
            <input type="text" class="form-control" name="serba_serbi_jumlah" id="serba_serbi_jumlah" placeholder="Serba Serbi Jumlah" value="<?php echo $serba_serbi_jumlah; ?>" />
        </div>
	    <input type="hidden" name="nomor" value="<?php echo $nomor; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('jurnal_penerimaan_kas') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>