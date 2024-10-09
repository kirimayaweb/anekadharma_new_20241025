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
        <h2 style="margin-top:0px">Tbl_gudang Read</h2>
        <table class="table">
	    <tr><td>Uuid Gudang</td><td><?php echo $uuid_gudang; ?></td></tr>
	    <tr><td>Date Input</td><td><?php echo $date_input; ?></td></tr>
	    <tr><td>Id User Input</td><td><?php echo $id_user_input; ?></td></tr>
	    <tr><td>Nama Gudang</td><td><?php echo $nama_gudang; ?></td></tr>
	    <tr><td>Alamat Gudang</td><td><?php echo $alamat_gudang; ?></td></tr>
	    <tr><td>Notelp Gudang</td><td><?php echo $notelp_gudang; ?></td></tr>
	    <tr><td>Keterangan</td><td><?php echo $keterangan; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('tbl_gudang') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>