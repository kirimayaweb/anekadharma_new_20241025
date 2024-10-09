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
        <h2 style="margin-top:0px">Tbl_produk_mapel_referensi Read</h2>
        <table class="table">
	    <tr><td>Uuid Produk</td><td><?php echo $uuid_produk; ?></td></tr>
	    <tr><td>Kode Produk</td><td><?php echo $kode_produk; ?></td></tr>
	    <tr><td>Date Input</td><td><?php echo $date_input; ?></td></tr>
	    <tr><td>Id User Input</td><td><?php echo $id_user_input; ?></td></tr>
	    <tr><td>Tingkat</td><td><?php echo $tingkat; ?></td></tr>
	    <tr><td>Mapel</td><td><?php echo $mapel; ?></td></tr>
	    <tr><td>Kelas</td><td><?php echo $kelas; ?></td></tr>
	    <tr><td>Tahun</td><td><?php echo $tahun; ?></td></tr>
	    <tr><td>Semester</td><td><?php echo $semester; ?></td></tr>
	    <tr><td>Halaman</td><td><?php echo $halaman; ?></td></tr>
	    <tr><td>Uuid Cover Produk</td><td><?php echo $uuid_cover_produk; ?></td></tr>
	    <tr><td>Keterangan</td><td><?php echo $keterangan; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('tbl_produk_mapel_referensi') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>