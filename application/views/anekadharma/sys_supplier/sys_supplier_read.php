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
        <h2 style="margin-top:0px">Sys_supplier Read</h2>
        <table class="table">
	    <tr><td>Uuid Supplier</td><td><?php echo $uuid_supplier; ?></td></tr>
	    <tr><td>Kode Supplier</td><td><?php echo $kode_supplier; ?></td></tr>
	    <tr><td>Nama Supplier</td><td><?php echo $nama_supplier; ?></td></tr>
	    <tr><td>Nmr Kontak Supplier</td><td><?php echo $nmr_kontak_supplier; ?></td></tr>
	    <tr><td>Alamat Supplier</td><td><?php echo $alamat_supplier; ?></td></tr>
	    <tr><td>Keterangan</td><td><?php echo $keterangan; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('sys_supplier') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>