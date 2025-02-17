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
        <h2 style="margin-top:0px">Jurnal_pengeluaran_kas <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Tanggal <?php echo form_error('tanggal') ?></label>
            <input type="text" class="form-control" name="tanggal" id="tanggal" placeholder="Tanggal" value="<?php echo $tanggal; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Nomor Bukti Bkk <?php echo form_error('nomor_bukti_bkk') ?></label>
            <input type="text" class="form-control" name="nomor_bukti_bkk" id="nomor_bukti_bkk" placeholder="Nomor Bukti Bkk" value="<?php echo $nomor_bukti_bkk; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Pl <?php echo form_error('pl') ?></label>
            <input type="text" class="form-control" name="pl" id="pl" placeholder="Pl" value="<?php echo $pl; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Keterangan <?php echo form_error('keterangan') ?></label>
            <input type="text" class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan" value="<?php echo $keterangan; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Debet 21101uu Dagang <?php echo form_error('debet_21101uu_dagang') ?></label>
            <input type="text" class="form-control" name="debet_21101uu_dagang" id="debet_21101uu_dagang" placeholder="Debet 21101uu Dagang" value="<?php echo $debet_21101uu_dagang; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Serba-serbi Nomor Rekening <?php echo form_error('serba-serbi_nomor_rekening') ?></label>
            <input type="text" class="form-control" name="serba-serbi_nomor_rekening" id="serba-serbi_nomor_rekening" placeholder="Serba-serbi Nomor Rekening" value="<?php echo $serba-serbi_nomor_rekening; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Serba Serbi Jumlah <?php echo form_error('serba_serbi_jumlah') ?></label>
            <input type="text" class="form-control" name="serba_serbi_jumlah" id="serba_serbi_jumlah" placeholder="Serba Serbi Jumlah" value="<?php echo $serba_serbi_jumlah; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Kredit 11101 Kas Besar <?php echo form_error('kredit_11101_kas_besar') ?></label>
            <input type="text" class="form-control" name="kredit_11101_kas_besar" id="kredit_11101_kas_besar" placeholder="Kredit 11101 Kas Besar" value="<?php echo $kredit_11101_kas_besar; ?>" />
        </div>
	    <input type="hidden" name="nomor" value="<?php echo $nomor; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('jurnal_pengeluaran_kas') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>