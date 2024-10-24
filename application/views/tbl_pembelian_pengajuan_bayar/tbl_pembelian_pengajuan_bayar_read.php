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
        <h2 style="margin-top:0px">Tbl_pembelian_pengajuan_bayar Read</h2>
        <table class="table">
	    <tr><td>Proses Input</td><td><?php echo $proses_input; ?></td></tr>
	    <tr><td>Date Input</td><td><?php echo $date_input; ?></td></tr>
	    <tr><td>Uuid Pengajuan Bayar</td><td><?php echo $uuid_pengajuan_bayar; ?></td></tr>
	    <tr><td>Uuid Spop</td><td><?php echo $uuid_spop; ?></td></tr>
	    <tr><td>Tgl Pengajuan</td><td><?php echo $tgl_pengajuan; ?></td></tr>
	    <tr><td>Nominal Pengajuan</td><td><?php echo $nominal_pengajuan; ?></td></tr>
	    <tr><td>Id Usr</td><td><?php echo $id_usr; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('tbl_pembelian_pengajuan_bayar') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>