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
        <h2 style="margin-top:0px">Jurnal_penjualan Read</h2>
        <table class="table">
	    <tr><td>Tanggal</td><td><?php echo $tanggal; ?></td></tr>
	    <tr><td>Bukti</td><td><?php echo $bukti; ?></td></tr>
	    <tr><td>Pl</td><td><?php echo $pl; ?></td></tr>
	    <tr><td>Ref</td><td><?php echo $ref; ?></td></tr>
	    <tr><td>Rekening</td><td><?php echo $rekening; ?></td></tr>
	    <tr><td>Keterangan</td><td><?php echo $keterangan; ?></td></tr>
	    <tr><td>Debet</td><td><?php echo $debet; ?></td></tr>
	    <tr><td>Kredit</td><td><?php echo $kredit; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('jurnal_penjualan') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>