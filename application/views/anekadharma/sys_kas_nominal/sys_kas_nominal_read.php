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
        <h2 style="margin-top:0px">Sys_kas_nominal Read</h2>
        <table class="table">
	    <tr><td>Tgl Input</td><td><?php echo $tgl_input; ?></td></tr>
	    <tr><td>Uuid Kas Nominal</td><td><?php echo $uuid_kas_nominal; ?></td></tr>
	    <tr><td>Total Kas Nominal</td><td><?php echo $total_kas_nominal; ?></td></tr>
	    <tr><td>Kode Kas Nominal</td><td><?php echo $kode_kas_nominal; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('sys_kas_nominal') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>