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
        <h2 style="margin-top:0px">Sys_unit_produk_bahan Read</h2>
        <table class="table">
	    <tr><td>Uuid Unit</td><td><?php echo $uuid_unit; ?></td></tr>
	    <tr><td>Uuid Persediaan</td><td><?php echo $uuid_persediaan; ?></td></tr>
	    <tr><td>Kode Unit</td><td><?php echo $kode_unit; ?></td></tr>
	    <tr><td>Nama Unit</td><td><?php echo $nama_unit; ?></td></tr>
	    <tr><td>Tgl Transaksi</td><td><?php echo $tgl_transaksi; ?></td></tr>
	    <tr><td>Uuid Produk</td><td><?php echo $uuid_produk; ?></td></tr>
	    <tr><td>Kode Barang Bahan</td><td><?php echo $kode_barang_bahan; ?></td></tr>
	    <tr><td>Nama Barang Bahan</td><td><?php echo $nama_barang_bahan; ?></td></tr>
	    <tr><td>Jumlah Bahan</td><td><?php echo $jumlah_bahan; ?></td></tr>
	    <tr><td>Satuan Bahan</td><td><?php echo $satuan_bahan; ?></td></tr>
	    <tr><td>Harga Satuan Bahan</td><td><?php echo $harga_satuan_bahan; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('sys_unit_produk_bahan') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>