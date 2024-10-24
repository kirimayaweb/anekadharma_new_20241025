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
        <h2 style="margin-top:0px">Tbl_neraca_data Read</h2>
        <table class="table">
	    <tr><td>Date Input</td><td><?php echo $date_input; ?></td></tr>
	    <tr><td>Date Transaksi</td><td><?php echo $date_transaksi; ?></td></tr>
	    <tr><td>Tahun Transaksi</td><td><?php echo $tahun_transaksi; ?></td></tr>
	    <tr><td>Bulan Transaksi</td><td><?php echo $bulan_transaksi; ?></td></tr>
	    <tr><td>Uuid Nama Data</td><td><?php echo $uuid_nama_data; ?></td></tr>
	    <tr><td>Nama Data</td><td><?php echo $nama_data; ?></td></tr>
	    <tr><td>Nominal Data</td><td><?php echo $nominal_data; ?></td></tr>
	    <tr><td>Id Usr</td><td><?php echo $id_usr; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('tbl_neraca_data') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>