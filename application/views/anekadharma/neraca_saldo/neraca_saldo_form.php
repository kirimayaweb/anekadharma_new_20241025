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
        <h2 style="margin-top:0px">Neraca_saldo <?php echo $button ?></h2>
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
            <label for="uraian">Uraian <?php echo form_error('uraian') ?></label>
            <textarea class="form-control" rows="3" name="uraian" id="uraian" placeholder="Uraian"><?php echo $uraian; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="group">Group <?php echo form_error('group') ?></label>
            <textarea class="form-control" rows="3" name="group" id="group" placeholder="Group"><?php echo $group; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="decimal">Debet Akhir Tahun Lalu <?php echo form_error('debet_akhir_tahun_lalu') ?></label>
            <input type="text" class="form-control" name="debet_akhir_tahun_lalu" id="debet_akhir_tahun_lalu" placeholder="Debet Akhir Tahun Lalu" value="<?php echo $debet_akhir_tahun_lalu; ?>" />
        </div>
	    <div class="form-group">
            <label for="decimal">Kredit Akhir Tahun Lalu <?php echo form_error('kredit_akhir_tahun_lalu') ?></label>
            <input type="text" class="form-control" name="kredit_akhir_tahun_lalu" id="kredit_akhir_tahun_lalu" placeholder="Kredit Akhir Tahun Lalu" value="<?php echo $kredit_akhir_tahun_lalu; ?>" />
        </div>
	    <div class="form-group">
            <label for="decimal">Debet Penyesuaian <?php echo form_error('debet_penyesuaian') ?></label>
            <input type="text" class="form-control" name="debet_penyesuaian" id="debet_penyesuaian" placeholder="Debet Penyesuaian" value="<?php echo $debet_penyesuaian; ?>" />
        </div>
	    <div class="form-group">
            <label for="decimal">Kredit Penyesuaian <?php echo form_error('kredit_penyesuaian') ?></label>
            <input type="text" class="form-control" name="kredit_penyesuaian" id="kredit_penyesuaian" placeholder="Kredit Penyesuaian" value="<?php echo $kredit_penyesuaian; ?>" />
        </div>
	    <div class="form-group">
            <label for="decimal">Debet Ns Setelah Penyesuaian <?php echo form_error('debet_ns_setelah_penyesuaian') ?></label>
            <input type="text" class="form-control" name="debet_ns_setelah_penyesuaian" id="debet_ns_setelah_penyesuaian" placeholder="Debet Ns Setelah Penyesuaian" value="<?php echo $debet_ns_setelah_penyesuaian; ?>" />
        </div>
	    <div class="form-group">
            <label for="decimal">Kredit Ns Setelah Penyesuaian <?php echo form_error('kredit_ns_setelah_penyesuaian') ?></label>
            <input type="text" class="form-control" name="kredit_ns_setelah_penyesuaian" id="kredit_ns_setelah_penyesuaian" placeholder="Kredit Ns Setelah Penyesuaian" value="<?php echo $kredit_ns_setelah_penyesuaian; ?>" />
        </div>
	    <div class="form-group">
            <label for="decimal">Debet Laba Rugi <?php echo form_error('debet_laba_rugi') ?></label>
            <input type="text" class="form-control" name="debet_laba_rugi" id="debet_laba_rugi" placeholder="Debet Laba Rugi" value="<?php echo $debet_laba_rugi; ?>" />
        </div>
	    <div class="form-group">
            <label for="decimal">Kreditdebet Laba Rugi <?php echo form_error('kreditdebet_laba_rugi') ?></label>
            <input type="text" class="form-control" name="kreditdebet_laba_rugi" id="kreditdebet_laba_rugi" placeholder="Kreditdebet Laba Rugi" value="<?php echo $kreditdebet_laba_rugi; ?>" />
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('neraca_saldo') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>