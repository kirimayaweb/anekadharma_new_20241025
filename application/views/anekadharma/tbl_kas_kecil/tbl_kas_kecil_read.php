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
        <h2 style="margin-top:0px">Tbl_kas_kecil Read</h2>
        <table class="table">
	    <tr><td>Uuid Kas Kecil</td><td><?php echo $uuid_kas_kecil; ?></td></tr>
	    <tr><td>Tanggal</td><td><?php echo $tanggal; ?></td></tr>
	    <tr><td>Unit</td><td><?php echo $unit; ?></td></tr>
	    <tr><td>Keterangan</td><td><?php echo $keterangan; ?></td></tr>
	    <tr><td>Debet</td><td><?php echo $debet; ?></td></tr>
	    <tr><td>Kredit</td><td><?php echo $kredit; ?></td></tr>
	    <tr><td>Saldo</td><td><?php echo $saldo; ?></td></tr>
	    <tr><td>Id Usr</td><td><?php echo $id_usr; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('tbl_kas_kecil') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>