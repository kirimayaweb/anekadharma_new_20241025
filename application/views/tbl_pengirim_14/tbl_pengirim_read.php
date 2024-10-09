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
        <h2 style="margin-top:0px">Tbl_pengirim Read</h2>
        <table class="table">
	    <tr><td>Uuid Pengirim</td><td><?php echo $uuid_pengirim; ?></td></tr>
	    <tr><td>Kode Pengirim</td><td><?php echo $kode_pengirim; ?></td></tr>
	    <tr><td>Date Input</td><td><?php echo $date_input; ?></td></tr>
	    <tr><td>Id User Input</td><td><?php echo $id_user_input; ?></td></tr>
	    <tr><td>Nama Pengirim</td><td><?php echo $nama_pengirim; ?></td></tr>
	    <tr><td>Alamat Pengirim</td><td><?php echo $alamat_pengirim; ?></td></tr>
	    <tr><td>Notelp Pengirim</td><td><?php echo $notelp_pengirim; ?></td></tr>
	    <tr><td>Keterangan</td><td><?php echo $keterangan; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('tbl_pengirim_14') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>