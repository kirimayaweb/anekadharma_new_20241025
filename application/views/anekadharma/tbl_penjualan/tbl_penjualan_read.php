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
        <h2 style="margin-top:0px">Tbl_penjualan Read</h2>
        <table class="table">
	    <tr><td>Tgl Input</td><td><?php echo $tgl_input; ?></td></tr>
	    <tr><td>Nmrpesan</td><td><?php echo $nmrpesan; ?></td></tr>
	    <tr><td>Nmrkirim</td><td><?php echo $nmrkirim; ?></td></tr>
	    <tr><td>Konsumen Id</td><td><?php echo $konsumen_id; ?></td></tr>
	    <tr><td>Konsumen Nama</td><td><?php echo $konsumen_nama; ?></td></tr>
	    <tr><td>Kode Barang</td><td><?php echo $kode_barang; ?></td></tr>
	    <tr><td>Nama Barang</td><td><?php echo $nama_barang; ?></td></tr>
	    <tr><td>Unit</td><td><?php echo $unit; ?></td></tr>
	    <tr><td>Satuan</td><td><?php echo $satuan; ?></td></tr>
	    <tr><td>Harga Satuan</td><td><?php echo $harga_satuan; ?></td></tr>
	    <tr><td>Jumlah</td><td><?php echo $jumlah; ?></td></tr>
	    <tr><td>Umpphpsl22</td><td><?php echo $umpphpsl22; ?></td></tr>
	    <tr><td>Piutang</td><td><?php echo $piutang; ?></td></tr>
	    <tr><td>Penjualandpp</td><td><?php echo $penjualandpp; ?></td></tr>
	    <tr><td>Utangppn</td><td><?php echo $utangppn; ?></td></tr>
	    <tr><td>Id Usr</td><td><?php echo $id_usr; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('tbl_penjualan') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>