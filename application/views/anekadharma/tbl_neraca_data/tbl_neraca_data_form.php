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
        <h2 style="margin-top:0px">Tbl_neraca_data <?php echo $button ?></h2>
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
            <label for="varchar">Uuid Nama Data <?php echo form_error('uuid_nama_data') ?></label>
            <input type="text" class="form-control" name="uuid_nama_data" id="uuid_nama_data" placeholder="Uuid Nama Data" value="<?php echo $uuid_nama_data; ?>" />
        </div>
	    <div class="form-group">
            <label for="nama_data">Nama Data <?php echo form_error('nama_data') ?></label>
            <textarea class="form-control" rows="3" name="nama_data" id="nama_data" placeholder="Nama Data"><?php echo $nama_data; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="double">Nominal Data <?php echo form_error('nominal_data') ?></label>
            <input type="text" class="form-control" name="nominal_data" id="nominal_data" placeholder="Nominal Data" value="<?php echo $nominal_data; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Id Usr <?php echo form_error('id_usr') ?></label>
            <input type="text" class="form-control" name="id_usr" id="id_usr" placeholder="Id Usr" value="<?php echo $id_usr; ?>" />
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('tbl_neraca_data') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>