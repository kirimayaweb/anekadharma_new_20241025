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
        <h2>Tbl_pedagang List</h2>
        <table class="word-table" style="margin-bottom: 10px">
            <tr>
                <th>No</th>
		<th>Nik</th>
		<th>Idpedagang</th>
		<th>Nama</th>
		<th>Alamat</th>
		<th>Jeniskelamin</th>
		<th>Status</th>
		<th>No Hp</th>
		
            </tr><?php
            foreach ($tbl_pedagang_data as $tbl_pedagang)
            {
                ?>
                <tr>
		      <td><?php echo ++$start ?></td>
		      <td><?php tampil($tbl_pedagang->nik) ?></td>
		      <td><?php tampil($tbl_pedagang->idpedagang) ?></td>
		      <td><?php tampil($tbl_pedagang->nama) ?></td>
		      <td><?php tampil($tbl_pedagang->alamat) ?></td>
		      <td><?php tampil($tbl_pedagang->jeniskelamin) ?></td>
		      <td><?php tampil($tbl_pedagang->status) ?></td>
		      <td><?php tampil($tbl_pedagang->no_hp) ?></td>	
                </tr>
                <?php
            }
            ?>
        </table>
    </body>
</html>