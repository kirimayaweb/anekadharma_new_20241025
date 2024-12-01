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
        <h2 style="margin-top:0px">Jurnal_pembelian <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Tanggal <?php echo form_error('tanggal') ?></label>
            <input type="text" class="form-control" name="tanggal" id="tanggal" placeholder="Tanggal" value="<?php echo $tanggal; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Unit <?php echo form_error('unit') ?></label>
            <input type="text" class="form-control" name="unit" id="unit" placeholder="Unit" value="<?php echo $unit; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Spop <?php echo form_error('spop') ?></label>
            <input type="text" class="form-control" name="spop" id="spop" placeholder="Spop" value="<?php echo $spop; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Pl <?php echo form_error('pl') ?></label>
            <input type="text" class="form-control" name="pl" id="pl" placeholder="Pl" value="<?php echo $pl; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Supplier <?php echo form_error('supplier') ?></label>
            <input type="text" class="form-control" name="supplier" id="supplier" placeholder="Supplier" value="<?php echo $supplier; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Norek <?php echo form_error('norek') ?></label>
            <input type="text" class="form-control" name="norek" id="norek" placeholder="Norek" value="<?php echo $norek; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Rekening <?php echo form_error('rekening') ?></label>
            <input type="text" class="form-control" name="rekening" id="rekening" placeholder="Rekening" value="<?php echo $rekening; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Jumlah <?php echo form_error('jumlah') ?></label>
            <input type="text" class="form-control" name="jumlah" id="jumlah" placeholder="Jumlah" value="<?php echo $jumlah; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Uu21101 <?php echo form_error('uu21101') ?></label>
            <input type="text" class="form-control" name="uu21101" id="uu21101" placeholder="Uu21101" value="<?php echo $uu21101; ?>" />
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('jurnal_pembelian') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>