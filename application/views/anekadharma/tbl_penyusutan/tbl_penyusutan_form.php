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
        <h2 style="margin-top:0px">Tbl_penyusutan <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Uuid Penyusutan <?php echo form_error('uuid_penyusutan') ?></label>
            <input type="text" class="form-control" name="uuid_penyusutan" id="uuid_penyusutan" placeholder="Uuid Penyusutan" value="<?php echo $uuid_penyusutan; ?>" />
        </div>
	    <div class="form-group">
            <label for="kelompok_harta">Kelompok Harta <?php echo form_error('kelompok_harta') ?></label>
            <textarea class="form-control" rows="3" name="kelompok_harta" id="kelompok_harta" placeholder="Kelompok Harta"><?php echo $kelompok_harta; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="datetime">Tanggal Perolehan <?php echo form_error('tanggal_perolehan') ?></label>
            <input type="text" class="form-control" name="tanggal_perolehan" id="tanggal_perolehan" placeholder="Tanggal Perolehan" value="<?php echo $tanggal_perolehan; ?>" />
        </div>
	    <div class="form-group">
            <label for="decimal">Harga Perolehan <?php echo form_error('harga_perolehan') ?></label>
            <input type="text" class="form-control" name="harga_perolehan" id="harga_perolehan" placeholder="Harga Perolehan" value="<?php echo $harga_perolehan; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">User <?php echo form_error('user') ?></label>
            <input type="text" class="form-control" name="user" id="user" placeholder="User" value="<?php echo $user; ?>" />
        </div>
	    <div class="form-group">
            <label for="decimal">Armorst Penyusutan Thn Lalu <?php echo form_error('armorst_penyusutan_thn_lalu') ?></label>
            <input type="text" class="form-control" name="armorst_penyusutan_thn_lalu" id="armorst_penyusutan_thn_lalu" placeholder="Armorst Penyusutan Thn Lalu" value="<?php echo $armorst_penyusutan_thn_lalu; ?>" />
        </div>
	    <div class="form-group">
            <label for="decimal">Nilai Buku Thn Lalu <?php echo form_error('nilai_buku_thn_lalu') ?></label>
            <input type="text" class="form-control" name="nilai_buku_thn_lalu" id="nilai_buku_thn_lalu" placeholder="Nilai Buku Thn Lalu" value="<?php echo $nilai_buku_thn_lalu; ?>" />
        </div>
	    <div class="form-group">
            <label for="decimal">Penyusutan Bulan Ini <?php echo form_error('penyusutan_bulan_ini') ?></label>
            <input type="text" class="form-control" name="penyusutan_bulan_ini" id="penyusutan_bulan_ini" placeholder="Penyusutan Bulan Ini" value="<?php echo $penyusutan_bulan_ini; ?>" />
        </div>
	    <div class="form-group">
            <label for="decimal">Armorst Penyusutan Bulan Ini <?php echo form_error('armorst_penyusutan_bulan_ini') ?></label>
            <input type="text" class="form-control" name="armorst_penyusutan_bulan_ini" id="armorst_penyusutan_bulan_ini" placeholder="Armorst Penyusutan Bulan Ini" value="<?php echo $armorst_penyusutan_bulan_ini; ?>" />
        </div>
	    <div class="form-group">
            <label for="decimal">Nilai Buku Bulan Ini <?php echo form_error('nilai_buku_bulan_ini') ?></label>
            <input type="text" class="form-control" name="nilai_buku_bulan_ini" id="nilai_buku_bulan_ini" placeholder="Nilai Buku Bulan Ini" value="<?php echo $nilai_buku_bulan_ini; ?>" />
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('tbl_penyusutan') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>