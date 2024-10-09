<!doctype html>
<html>
    <head>
        <title>harviacode.com - codeigniter crud generator</title>
        <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>"/>
        <style>
            .word-table {
                border:1px solid black !important; 
                border-collapse: collapse !important;
                width: 100%;
            }
            .word-table tr th, .word-table tr td{
                border:1px solid black !important; 
                padding: 5px 10px;
            }
        </style>
    </head>
    <body>
        <h2>Tbl_produk List</h2>
        <table class="word-table" style="margin-bottom: 10px">
            <tr>
                <th>No</th>
		<th>Uuid Produk</th>
		<th>Date Input</th>
		<th>Tingkat</th>
		<th>Kelas</th>
		<th>Tahun</th>
		<th>Semester</th>
		<th>Mapel</th>
		<th>Halaman</th>
		<th>Keterangan</th>
		
            </tr><?php
            foreach ($tbl_produk_data as $tbl_produk)
            {
                ?>
                <tr>
		      <td><?php echo ++$start ?></td>
		      <td><?php echo $tbl_produk->uuid_produk ?></td>
		      <td><?php echo $tbl_produk->date_input ?></td>
		      <td><?php echo $tbl_produk->tingkat ?></td>
		      <td><?php echo $tbl_produk->kelas ?></td>
		      <td><?php echo $tbl_produk->tahun ?></td>
		      <td><?php echo $tbl_produk->semester ?></td>
		      <td><?php echo $tbl_produk->mapel ?></td>
		      <td><?php echo $tbl_produk->halaman ?></td>
		      <td><?php echo $tbl_produk->keterangan ?></td>	
                </tr>
                <?php
            }
            ?>
        </table>
    </body>
</html>