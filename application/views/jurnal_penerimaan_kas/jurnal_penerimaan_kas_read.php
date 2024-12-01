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
        <h2 style="margin-top:0px">Jurnal_penerimaan_kas Read</h2>
        <table class="table">
	    <tr><td>Tanggal</td><td><?php echo $tanggal; ?></td></tr>
	    <tr><td>Nomorbuktibkm</td><td><?php echo $nomorbuktibkm; ?></td></tr>
	    <tr><td>Pl</td><td><?php echo $pl; ?></td></tr>
	    <tr><td>Keterangan</td><td><?php echo $keterangan; ?></td></tr>
	    <tr><td>Debet 11101 Kas Besar</td><td><?php echo $debet_11101_kas_besar; ?></td></tr>
	    <tr><td>Kredit 11301 Pu Non Angsuran</td><td><?php echo $kredit_11301_pu_non_angsuran; ?></td></tr>
	    <tr><td>Serba Serbi Rekening</td><td><?php echo $serba_serbi_rekening; ?></td></tr>
	    <tr><td>Serba Serbi Jumlah</td><td><?php echo $serba_serbi_jumlah; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('jurnal_penerimaan_kas') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>