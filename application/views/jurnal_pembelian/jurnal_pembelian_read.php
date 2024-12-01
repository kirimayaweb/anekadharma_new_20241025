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
        <h2 style="margin-top:0px">Jurnal_pembelian Read</h2>
        <table class="table">
	    <tr><td>Tanggal</td><td><?php echo $tanggal; ?></td></tr>
	    <tr><td>Unit</td><td><?php echo $unit; ?></td></tr>
	    <tr><td>Spop</td><td><?php echo $spop; ?></td></tr>
	    <tr><td>Pl</td><td><?php echo $pl; ?></td></tr>
	    <tr><td>Supplier</td><td><?php echo $supplier; ?></td></tr>
	    <tr><td>Norek</td><td><?php echo $norek; ?></td></tr>
	    <tr><td>Rekening</td><td><?php echo $rekening; ?></td></tr>
	    <tr><td>Jumlah</td><td><?php echo $jumlah; ?></td></tr>
	    <tr><td>Uu21101</td><td><?php echo $uu21101; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('jurnal_pembelian') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>