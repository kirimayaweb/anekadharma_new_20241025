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
        <h2 style="margin-top:0px">Buku_besar Read</h2>
        <table class="table">
	    <tr><td>Uuid Buku Besar</td><td><?php echo $uuid_buku_besar; ?></td></tr>
	    <tr><td>Kode Akun</td><td><?php echo $kode_akun; ?></td></tr>
	    <tr><td>Keterangan</td><td><?php echo $keterangan; ?></td></tr>
	    <tr><td>Kode</td><td><?php echo $kode; ?></td></tr>
	    <tr><td>Debet</td><td><?php echo $debet; ?></td></tr>
	    <tr><td>Kredit</td><td><?php echo $kredit; ?></td></tr>
	    <tr><td>Saldo</td><td><?php echo $saldo; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('buku_besar') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>