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
        <h2 style="margin-top:0px">Tbl_uang_muka_didepan Read</h2>
        <table class="table">
	    <tr><td>Uuid Uang Muka Didepan</td><td><?php echo $uuid_uang_muka_didepan; ?></td></tr>
	    <tr><td>Tgl Transaksi</td><td><?php echo $tgl_transaksi; ?></td></tr>
	    <tr><td>Kode</td><td><?php echo $kode; ?></td></tr>
	    <tr><td>Dari</td><td><?php echo $dari; ?></td></tr>
	    <tr><td>Uraian</td><td><?php echo $uraian; ?></td></tr>
	    <tr><td>Nominal</td><td><?php echo $nominal; ?></td></tr>
	    <tr><td>Bank</td><td><?php echo $bank; ?></td></tr>
	    <tr><td>Nmr Rekening</td><td><?php echo $nmr_rekening; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('tbl_uang_muka_didepan') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>