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
        <h2 style="margin-top:0px">Sys_kas_nominal <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="datetime">Tgl Input <?php echo form_error('tgl_input') ?></label>
            <input type="text" class="form-control" name="tgl_input" id="tgl_input" placeholder="Tgl Input" value="<?php echo $tgl_input; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Uuid Kas Nominal <?php echo form_error('uuid_kas_nominal') ?></label>
            <input type="text" class="form-control" name="uuid_kas_nominal" id="uuid_kas_nominal" placeholder="Uuid Kas Nominal" value="<?php echo $uuid_kas_nominal; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Total Kas Nominal <?php echo form_error('total_kas_nominal') ?></label>
            <input type="text" class="form-control" name="total_kas_nominal" id="total_kas_nominal" placeholder="Total Kas Nominal" value="<?php echo $total_kas_nominal; ?>" />
        </div>
	    <div class="form-group">
            <label for="kode_kas_nominal">Kode Kas Nominal <?php echo form_error('kode_kas_nominal') ?></label>
            <textarea class="form-control" rows="3" name="kode_kas_nominal" id="kode_kas_nominal" placeholder="Kode Kas Nominal"><?php echo $kode_kas_nominal; ?></textarea>
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('sys_kas_nominal') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>