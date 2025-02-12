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
        <h2 style="margin-top:0px">Neraca_saldo Read</h2>
        <table class="table">
	    <tr><td>Uuid Kode Akun</td><td><?php echo $uuid_kode_akun; ?></td></tr>
	    <tr><td>Kode Akun</td><td><?php echo $kode_akun; ?></td></tr>
	    <tr><td>Nama Akun</td><td><?php echo $nama_akun; ?></td></tr>
	    <tr><td>Uraian</td><td><?php echo $uraian; ?></td></tr>
	    <tr><td>Group</td><td><?php echo $group; ?></td></tr>
	    <tr><td>Debet Akhir Tahun Lalu</td><td><?php echo $debet_akhir_tahun_lalu; ?></td></tr>
	    <tr><td>Kredit Akhir Tahun Lalu</td><td><?php echo $kredit_akhir_tahun_lalu; ?></td></tr>
	    <tr><td>Debet Penyesuaian</td><td><?php echo $debet_penyesuaian; ?></td></tr>
	    <tr><td>Kredit Penyesuaian</td><td><?php echo $kredit_penyesuaian; ?></td></tr>
	    <tr><td>Debet Ns Setelah Penyesuaian</td><td><?php echo $debet_ns_setelah_penyesuaian; ?></td></tr>
	    <tr><td>Kredit Ns Setelah Penyesuaian</td><td><?php echo $kredit_ns_setelah_penyesuaian; ?></td></tr>
	    <tr><td>Debet Laba Rugi</td><td><?php echo $debet_laba_rugi; ?></td></tr>
	    <tr><td>Kreditdebet Laba Rugi</td><td><?php echo $kreditdebet_laba_rugi; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('neraca_saldo') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>