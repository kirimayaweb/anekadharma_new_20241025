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
        <h2 style="margin-top:0px">Sys_gudang Read</h2>
        <table class="table">
	    <tr><td>Uuid Gudang</td><td><?php echo $uuid_gudang; ?></td></tr>
	    <tr><td>Kode Gudang</td><td><?php echo $kode_gudang; ?></td></tr>
	    <tr><td>Nama Gudang</td><td><?php echo $nama_gudang; ?></td></tr>
	    <tr><td>Alamat</td><td><?php echo $alamat; ?></td></tr>
	    <tr><td>Keterangan</td><td><?php echo $keterangan; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('sys_gudang') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>