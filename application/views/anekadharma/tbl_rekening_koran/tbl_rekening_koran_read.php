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
        <h2 style="margin-top:0px">Tbl_rekening_koran Read</h2>
        <table class="table">
	    <tr><td>Uuid Rek Koran</td><td><?php echo $uuid_rek_koran; ?></td></tr>
	    <tr><td>Tgl Transaksi</td><td><?php echo $tgl_transaksi; ?></td></tr>
	    <tr><td>Uraian</td><td><?php echo $uraian; ?></td></tr>
	    <tr><td>Chq</td><td><?php echo $chq; ?></td></tr>
	    <tr><td>Debet</td><td><?php echo $debet; ?></td></tr>
	    <tr><td>Kredit</td><td><?php echo $kredit; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('tbl_rekening_koran') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>