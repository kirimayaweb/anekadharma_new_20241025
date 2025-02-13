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
        <h2 style="margin-top:0px">Tbl_penyusutan Read</h2>
        <table class="table">
	    <tr><td>Uuid Penyusutan</td><td><?php echo $uuid_penyusutan; ?></td></tr>
	    <tr><td>Kelompok Harta</td><td><?php echo $kelompok_harta; ?></td></tr>
	    <tr><td>Tanggal Perolehan</td><td><?php echo $tanggal_perolehan; ?></td></tr>
	    <tr><td>Harga Perolehan</td><td><?php echo $harga_perolehan; ?></td></tr>
	    <tr><td>User</td><td><?php echo $user; ?></td></tr>
	    <tr><td>Armorst Penyusutan Thn Lalu</td><td><?php echo $armorst_penyusutan_thn_lalu; ?></td></tr>
	    <tr><td>Nilai Buku Thn Lalu</td><td><?php echo $nilai_buku_thn_lalu; ?></td></tr>
	    <tr><td>Penyusutan Bulan Ini</td><td><?php echo $penyusutan_bulan_ini; ?></td></tr>
	    <tr><td>Armorst Penyusutan Bulan Ini</td><td><?php echo $armorst_penyusutan_bulan_ini; ?></td></tr>
	    <tr><td>Nilai Buku Bulan Ini</td><td><?php echo $nilai_buku_bulan_ini; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('tbl_penyusutan') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>