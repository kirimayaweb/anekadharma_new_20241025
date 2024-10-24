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
        <h2 style="margin-top:0px">Jurnal_202210okt <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="int">Id X <?php echo form_error('id_x') ?></label>
            <input type="text" class="form-control" name="id_x" id="id_x" placeholder="Id X" value="<?php echo $id_x; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Tanggal Date <?php echo form_error('Tanggal_date') ?></label>
            <input type="text" class="form-control" name="Tanggal_date" id="Tanggal_date" placeholder="Tanggal Date" value="<?php echo $Tanggal_date; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Tgl <?php echo form_error('tgl') ?></label>
            <input type="text" class="form-control" name="tgl" id="tgl" placeholder="Tgl" value="<?php echo $tgl; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Nmr Spop <?php echo form_error('nmr_spop') ?></label>
            <input type="text" class="form-control" name="nmr_spop" id="nmr_spop" placeholder="Nmr Spop" value="<?php echo $nmr_spop; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Pl <?php echo form_error('pl') ?></label>
            <input type="text" class="form-control" name="pl" id="pl" placeholder="Pl" value="<?php echo $pl; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Kode Unit <?php echo form_error('kode_unit') ?></label>
            <input type="text" class="form-control" name="kode_unit" id="kode_unit" placeholder="Kode Unit" value="<?php echo $kode_unit; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Unit <?php echo form_error('unit') ?></label>
            <input type="text" class="form-control" name="unit" id="unit" placeholder="Unit" value="<?php echo $unit; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Supplier <?php echo form_error('supplier') ?></label>
            <input type="text" class="form-control" name="supplier" id="supplier" placeholder="Supplier" value="<?php echo $supplier; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Nmr Rek <?php echo form_error('nmr_rek') ?></label>
            <input type="text" class="form-control" name="nmr_rek" id="nmr_rek" placeholder="Nmr Rek" value="<?php echo $nmr_rek; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Rekening <?php echo form_error('rekening') ?></label>
            <input type="text" class="form-control" name="rekening" id="rekening" placeholder="Rekening" value="<?php echo $rekening; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar"> Jumlah <?php echo form_error(' jumlah') ?></label>
            <input type="text" class="form-control" name=" jumlah" id=" jumlah" placeholder=" Jumlah" value="<?php echo $ jumlah; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Uu <?php echo form_error('uu') ?></label>
            <input type="text" class="form-control" name="uu" id="uu" placeholder="Uu" value="<?php echo $uu; ?>" />
        </div>
	    <input type="hidden" name="" value="<?php echo $; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('jurnal_202210okt') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>