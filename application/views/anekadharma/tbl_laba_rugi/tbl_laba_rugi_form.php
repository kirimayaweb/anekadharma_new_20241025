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
        <h2 style="margin-top:0px">Tbl_laba_rugi <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="datetime">Date Input <?php echo form_error('date_input') ?></label>
            <input type="text" class="form-control" name="date_input" id="date_input" placeholder="Date Input" value="<?php echo $date_input; ?>" />
        </div>
	    <div class="form-group">
            <label for="datetime">Date Transaksi <?php echo form_error('date_transaksi') ?></label>
            <input type="text" class="form-control" name="date_transaksi" id="date_transaksi" placeholder="Date Transaksi" value="<?php echo $date_transaksi; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Tahun Transaksi <?php echo form_error('tahun_transaksi') ?></label>
            <input type="text" class="form-control" name="tahun_transaksi" id="tahun_transaksi" placeholder="Tahun Transaksi" value="<?php echo $tahun_transaksi; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Bulan Transaksi <?php echo form_error('bulan_transaksi') ?></label>
            <input type="text" class="form-control" name="bulan_transaksi" id="bulan_transaksi" placeholder="Bulan Transaksi" value="<?php echo $bulan_transaksi; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Uuid Data Neraca <?php echo form_error('uuid_data_neraca') ?></label>
            <input type="text" class="form-control" name="uuid_data_neraca" id="uuid_data_neraca" placeholder="Uuid Data Neraca" value="<?php echo $uuid_data_neraca; ?>" />
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('tbl_laba_rugi') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>