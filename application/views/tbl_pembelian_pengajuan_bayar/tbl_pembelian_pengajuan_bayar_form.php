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
        <h2 style="margin-top:0px">Tbl_pembelian_pengajuan_bayar <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="int">Proses Input <?php echo form_error('proses_input') ?></label>
            <input type="text" class="form-control" name="proses_input" id="proses_input" placeholder="Proses Input" value="<?php echo $proses_input; ?>" />
        </div>
	    <div class="form-group">
            <label for="datetime">Date Input <?php echo form_error('date_input') ?></label>
            <input type="text" class="form-control" name="date_input" id="date_input" placeholder="Date Input" value="<?php echo $date_input; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Uuid Pengajuan Bayar <?php echo form_error('uuid_pengajuan_bayar') ?></label>
            <input type="text" class="form-control" name="uuid_pengajuan_bayar" id="uuid_pengajuan_bayar" placeholder="Uuid Pengajuan Bayar" value="<?php echo $uuid_pengajuan_bayar; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Uuid Spop <?php echo form_error('uuid_spop') ?></label>
            <input type="text" class="form-control" name="uuid_spop" id="uuid_spop" placeholder="Uuid Spop" value="<?php echo $uuid_spop; ?>" />
        </div>
	    <div class="form-group">
            <label for="datetime">Tgl Pengajuan <?php echo form_error('tgl_pengajuan') ?></label>
            <input type="text" class="form-control" name="tgl_pengajuan" id="tgl_pengajuan" placeholder="Tgl Pengajuan" value="<?php echo $tgl_pengajuan; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Nominal Pengajuan <?php echo form_error('nominal_pengajuan') ?></label>
            <input type="text" class="form-control" name="nominal_pengajuan" id="nominal_pengajuan" placeholder="Nominal Pengajuan" value="<?php echo $nominal_pengajuan; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Id Usr <?php echo form_error('id_usr') ?></label>
            <input type="text" class="form-control" name="id_usr" id="id_usr" placeholder="Id Usr" value="<?php echo $id_usr; ?>" />
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('tbl_pembelian_pengajuan_bayar') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>