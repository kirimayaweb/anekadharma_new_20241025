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
        <h2 style="margin-top:0px">Tbl_laba_rugi Read</h2>
        <table class="table">
	    <tr><td>Date Input</td><td><?php echo $date_input; ?></td></tr>
	    <tr><td>Date Transaksi</td><td><?php echo $date_transaksi; ?></td></tr>
	    <tr><td>Tahun Transaksi</td><td><?php echo $tahun_transaksi; ?></td></tr>
	    <tr><td>Bulan Transaksi</td><td><?php echo $bulan_transaksi; ?></td></tr>
	    <tr><td>Uuid Data Neraca</td><td><?php echo $uuid_data_neraca; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('tbl_laba_rugi') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>