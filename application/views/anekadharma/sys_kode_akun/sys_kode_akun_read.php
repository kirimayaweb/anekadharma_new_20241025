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
        <h2 style="margin-top:0px">Sys_kode_akun Read</h2>
        <table class="table">
	    <tr><td>Uuid Kode Akun</td><td><?php echo $uuid_kode_akun; ?></td></tr>
	    <tr><td>Kode Akun</td><td><?php echo $kode_akun; ?></td></tr>
	    <tr><td>Nama Akun</td><td><?php echo $nama_akun; ?></td></tr>
	    <tr><td>Group</td><td><?php echo $group; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('sys_kode_akun') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>