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
        <h2 style="margin-top:0px">Tbl_pembelian Read</h2>
        <table class="table">
	    <tr><td>Tgl Po</td><td><?php echo $tgl_po; ?></td></tr>
	    <tr><td>Nmrsj</td><td><?php echo $nmrsj; ?></td></tr>
	    <tr><td>Nmrfakturkwitansi</td><td><?php echo $nmrfakturkwitansi; ?></td></tr>
	    <tr><td>Nmrbpb</td><td><?php echo $nmrbpb; ?></td></tr>
	    <tr><td>Spop</td><td><?php echo $spop; ?></td></tr>
	    <tr><td>Supplier Kode</td><td><?php echo $supplier_kode; ?></td></tr>
	    <tr><td>Supplier Nama</td><td><?php echo $supplier_nama; ?></td></tr>
	    <tr><td>Uraian</td><td><?php echo $uraian; ?></td></tr>
	    <tr><td>Jumlah</td><td><?php echo $jumlah; ?></td></tr>
	    <tr><td>Satuan</td><td><?php echo $satuan; ?></td></tr>
	    <tr><td>Konsumen</td><td><?php echo $konsumen; ?></td></tr>
	    <tr><td>Harga Satuan</td><td><?php echo $harga_satuan; ?></td></tr>
	    <tr><td>Harga Total</td><td><?php echo $harga_total; ?></td></tr>
	    <tr><td>Statuslu</td><td><?php echo $statuslu; ?></td></tr>
	    <tr><td>Kas Bank</td><td><?php echo $kas_bank; ?></td></tr>
	    <tr><td>Tgl Bayar</td><td><?php echo $tgl_bayar; ?></td></tr>
	    <tr><td>Id Usr</td><td><?php echo $id_usr; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('tbl_pembelian') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>