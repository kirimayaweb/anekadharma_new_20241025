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
        <h2 style="margin-top:0px">Tbl_accounting_detail Read</h2>
        <table class="table">
	    <tr><td>Date Input</td><td><?php echo $date_input; ?></td></tr>
	    <tr><td>Date Transaksi</td><td><?php echo $date_transaksi; ?></td></tr>
	    <tr><td>Tahun Transaksi</td><td><?php echo $tahun_transaksi; ?></td></tr>
	    <tr><td>Bulan Transaksi</td><td><?php echo $bulan_transaksi; ?></td></tr>
	    <tr><td>Uuid Accounting</td><td><?php echo $uuid_accounting; ?></td></tr>
	    <tr><td>Uuid Group</td><td><?php echo $uuid_group; ?></td></tr>
	    <tr><td>Nama Group</td><td><?php echo $nama_group; ?></td></tr>
	    <tr><td>Detail Transaksi</td><td><?php echo $detail_transaksi; ?></td></tr>
	    <tr><td>Nominal Transaksi</td><td><?php echo $nominal_transaksi; ?></td></tr>
	    <tr><td>Keterangan</td><td><?php echo $keterangan; ?></td></tr>
	    <tr><td>Id Usr</td><td><?php echo $id_usr; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('tbl_accounting_detail') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>