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
        <h2 style="margin-top:0px">Jurnal_kas_saldo_akhir_bulan Read</h2>
        <table class="table">
	    <tr><td>Uuid Jurnal Kas Saldo Akhir Bulan</td><td><?php echo $uuid_jurnal_kas_saldo_akhir_bulan; ?></td></tr>
	    <tr><td>Kode Akun</td><td><?php echo $kode_akun; ?></td></tr>
	    <tr><td>Id Buku Besar</td><td><?php echo $id_buku_besar; ?></td></tr>
	    <tr><td>Tanggal</td><td><?php echo $tanggal; ?></td></tr>
	    <tr><td>Bukti</td><td><?php echo $bukti; ?></td></tr>
	    <tr><td>Pl</td><td><?php echo $pl; ?></td></tr>
	    <tr><td>Keterangan</td><td><?php echo $keterangan; ?></td></tr>
	    <tr><td>Kode Rekening</td><td><?php echo $kode_rekening; ?></td></tr>
	    <tr><td>Uuid Unit</td><td><?php echo $uuid_unit; ?></td></tr>
	    <tr><td>Kode Unit</td><td><?php echo $kode_unit; ?></td></tr>
	    <tr><td>Debet</td><td><?php echo $debet; ?></td></tr>
	    <tr><td>Kredit</td><td><?php echo $kredit; ?></td></tr>
	    <tr><td>Saldo</td><td><?php echo $saldo; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('jurnal_kas_saldo_akhir_bulan') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>