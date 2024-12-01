<!doctype html>
<html>
    <head>
        <title>aneka dharma</title>
        <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>"/>
        <style>
            body{
                padding: 15px;
            }
        </style>
    </head>
    <body>
        <h2 style="margin-top:0px">Jurnal_umum Read</h2>
        <table class="table">
	    <tr><td>Tanggal</td><td><?php echo $tanggal; ?></td></tr>
	    <tr><td>Bukti</td><td><?php echo $bukti; ?></td></tr>
	    <tr><td>Pl</td><td><?php echo $pl; ?></td></tr>
	    <tr><td>Ref</td><td><?php echo $ref; ?></td></tr>
	    <tr><td>Uraian Kode Rekening</td><td><?php echo $uraian_kode_rekening; ?></td></tr>
	    <tr><td>Rekening</td><td><?php echo $rekening; ?></td></tr>
	    <tr><td>Debit</td><td><?php echo $debit; ?></td></tr>
	    <tr><td>Kredit</td><td><?php echo $kredit; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('jurnal_umum') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>