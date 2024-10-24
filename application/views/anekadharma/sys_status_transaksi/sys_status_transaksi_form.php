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
        <h2 style="margin-top:0px">Sys_status_transaksi <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Uuid Status Transaksi <?php echo form_error('uuid_status_transaksi') ?></label>
            <input type="text" class="form-control" name="uuid_status_transaksi" id="uuid_status_transaksi" placeholder="Uuid Status Transaksi" value="<?php echo $uuid_status_transaksi; ?>" />
        </div>
	    <div class="form-group">
            <label for="status">Status <?php echo form_error('status') ?></label>
            <textarea class="form-control" rows="3" name="status" id="status" placeholder="Status"><?php echo $status; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="keterangan">Keterangan <?php echo form_error('keterangan') ?></label>
            <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"><?php echo $keterangan; ?></textarea>
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('sys_status_transaksi') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>