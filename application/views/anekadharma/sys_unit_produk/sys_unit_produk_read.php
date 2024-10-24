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
        <h2 style="margin-top:0px">Sys_unit_produk Read</h2>
        <table class="table">
	    <tr><td>Uuid Unit</td><td><?php echo $uuid_unit; ?></td></tr>
	    <tr><td>Kode Unit</td><td><?php echo $kode_unit; ?></td></tr>
	    <tr><td>Nama Unit</td><td><?php echo $nama_unit; ?></td></tr>
	    <tr><td>Tgl Transaksi</td><td><?php echo $tgl_transaksi; ?></td></tr>
	    <tr><td>Uuid Barang</td><td><?php echo $uuid_barang; ?></td></tr>
	    <tr><td>Kode Barang</td><td><?php echo $kode_barang; ?></td></tr>
	    <tr><td>Nama Barang</td><td><?php echo $nama_barang; ?></td></tr>
	    <tr><td>Jumlah Produksi</td><td><?php echo $jumlah_produksi; ?></td></tr>
	    <tr><td>Satuan</td><td><?php echo $satuan; ?></td></tr>
	    <tr><td>Harga Satuan</td><td><?php echo $harga_satuan; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('sys_unit_produk') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>