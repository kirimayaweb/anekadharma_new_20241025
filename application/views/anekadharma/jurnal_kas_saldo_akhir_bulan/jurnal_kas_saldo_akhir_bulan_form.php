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
        <h2 style="margin-top:0px">Jurnal_kas_saldo_akhir_bulan <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Uuid Jurnal Kas Saldo Akhir Bulan <?php echo form_error('uuid_jurnal_kas_saldo_akhir_bulan') ?></label>
            <input type="text" class="form-control" name="uuid_jurnal_kas_saldo_akhir_bulan" id="uuid_jurnal_kas_saldo_akhir_bulan" placeholder="Uuid Jurnal Kas Saldo Akhir Bulan" value="<?php echo $uuid_jurnal_kas_saldo_akhir_bulan; ?>" />
        </div>
	    <div class="form-group">
            <label for="kode_akun">Kode Akun <?php echo form_error('kode_akun') ?></label>
            <textarea class="form-control" rows="3" name="kode_akun" id="kode_akun" placeholder="Kode Akun"><?php echo $kode_akun; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="int">Id Buku Besar <?php echo form_error('id_buku_besar') ?></label>
            <input type="text" class="form-control" name="id_buku_besar" id="id_buku_besar" placeholder="Id Buku Besar" value="<?php echo $id_buku_besar; ?>" />
        </div>
	    <div class="form-group">
            <label for="date">Tanggal <?php echo form_error('tanggal') ?></label>
            <input type="text" class="form-control" name="tanggal" id="tanggal" placeholder="Tanggal" value="<?php echo $tanggal; ?>" />
        </div>
	    <div class="form-group">
            <label for="bukti">Bukti <?php echo form_error('bukti') ?></label>
            <textarea class="form-control" rows="3" name="bukti" id="bukti" placeholder="Bukti"><?php echo $bukti; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="pl">Pl <?php echo form_error('pl') ?></label>
            <textarea class="form-control" rows="3" name="pl" id="pl" placeholder="Pl"><?php echo $pl; ?></textarea>
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
            <label for="varchar">Uuid Unit <?php echo form_error('uuid_unit') ?></label>
            <input type="text" class="form-control" name="uuid_unit" id="uuid_unit" placeholder="Uuid Unit" value="<?php echo $uuid_unit; ?>" />
        </div>
	    <div class="form-group">
            <label for="kode_unit">Kode Unit <?php echo form_error('kode_unit') ?></label>
            <textarea class="form-control" rows="3" name="kode_unit" id="kode_unit" placeholder="Kode Unit"><?php echo $kode_unit; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="decimal">Debet <?php echo form_error('debet') ?></label>
            <input type="text" class="form-control" name="debet" id="debet" placeholder="Debet" value="<?php echo $debet; ?>" />
        </div>
	    <div class="form-group">
            <label for="decimal">Kredit <?php echo form_error('kredit') ?></label>
            <input type="text" class="form-control" name="kredit" id="kredit" placeholder="Kredit" value="<?php echo $kredit; ?>" />
        </div>
	    <div class="form-group">
            <label for="decimal">Saldo <?php echo form_error('saldo') ?></label>
            <input type="text" class="form-control" name="saldo" id="saldo" placeholder="Saldo" value="<?php echo $saldo; ?>" />
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('jurnal_kas_saldo_akhir_bulan') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>