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
        <h2 style="margin-top:0px">Sys_status_transaksi Read</h2>
        <table class="table">
	    <tr><td>Uuid Status Transaksi</td><td><?php echo $uuid_status_transaksi; ?></td></tr>
	    <tr><td>Status</td><td><?php echo $status; ?></td></tr>
	    <tr><td>Keterangan</td><td><?php echo $keterangan; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('sys_status_transaksi') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>