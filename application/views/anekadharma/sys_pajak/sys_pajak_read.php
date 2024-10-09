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
        <h2 style="margin-top:0px">Sys_pajak Read</h2>
        <table class="table">
	    <tr><td>Uuid Var Pajak</td><td><?php echo $uuid_var_pajak; ?></td></tr>
	    <tr><td>Varaibel</td><td><?php echo $varaibel; ?></td></tr>
	    <tr><td>Nominal</td><td><?php echo $nominal; ?></td></tr>
	    <tr><td>Keterangan</td><td><?php echo $keterangan; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('sys_pajak') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>