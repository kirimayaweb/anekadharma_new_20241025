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
        <h2>Tbl_nama_pasar List</h2>
        <table class="word-table" style="margin-bottom: 10px">
            <tr>
                <th>No</th>
		<th>Kode</th>
		<th>Nama Pasar</th>
		
            </tr><?php
            foreach ($tbl_nama_pasar_data as $tbl_nama_pasar)
            {
                ?>
                <tr>
		      <td><?php echo ++$start ?></td>
		      <td><?php tampil($tbl_nama_pasar->kode) ?></td>
		      <td><?php tampil($tbl_nama_pasar->nama_pasar) ?></td>	
                </tr>
                <?php
            }
            ?>
        </table>
    </body>
</html>