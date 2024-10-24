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
        <h2 style="margin-top:0px">Sys_unit Read</h2>
        <table class="table">
	    <tr><td>Uuid Unit</td><td><?php echo $uuid_unit; ?></td></tr>
	    <tr><td>Kode Unit</td><td><?php echo $kode_unit; ?></td></tr>
	    <tr><td>Nama Unit</td><td><?php echo $nama_unit; ?></td></tr>
	    <tr><td>Keterangan</td><td><?php echo $keterangan; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('sys_unit') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>