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
        <h2 style="margin-top:0px">Jurnal_penyesuaian Read</h2>
        <table class="table">
	    <tr><td>Uuid Jurnal Penyesuaian</td><td><?php echo $uuid_jurnal_penyesuaian; ?></td></tr>
	    <tr><td>Kode Akun</td><td><?php echo $kode_akun; ?></td></tr>
	    <tr><td>Tanggal</td><td><?php echo $tanggal; ?></td></tr>
	    <tr><td>Keterangan</td><td><?php echo $keterangan; ?></td></tr>
	    <tr><td>Kode Rekening</td><td><?php echo $kode_rekening; ?></td></tr>
	    <tr><td>Debet</td><td><?php echo $debet; ?></td></tr>
	    <tr><td>Kredit</td><td><?php echo $kredit; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('jurnal_penyesuaian') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>