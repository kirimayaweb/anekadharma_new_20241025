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
        <h2 style="margin-top:0px">Sys_konsumen Read</h2>
        <table class="table">
	    <tr><td>Uuid Konsumen</td><td><?php echo $uuid_konsumen; ?></td></tr>
	    <tr><td>Kode Konsumen</td><td><?php echo $kode_konsumen; ?></td></tr>
	    <tr><td>Nama Konsumen</td><td><?php echo $nama_konsumen; ?></td></tr>
	    <tr><td>Nmr Kontak Konsumen</td><td><?php echo $nmr_kontak_konsumen; ?></td></tr>
	    <tr><td>Alamat Konsumen</td><td><?php echo $alamat_konsumen; ?></td></tr>
	    <tr><td>Keterangan</td><td><?php echo $keterangan; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('sys_konsumen') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>