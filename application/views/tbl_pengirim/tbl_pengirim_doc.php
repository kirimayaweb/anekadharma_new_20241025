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
        <h2>Tbl_pengirim List</h2>
        <table class="word-table" style="margin-bottom: 10px">
            <tr>
                <th>No</th>
		<th>Id</th>
		<th>Uuid Pengirim</th>
		<th>Date Input</th>
		<th>Id User Input</th>
		<th>Nama Pengirim</th>
		<th>Alamat Pengirim</th>
		<th>Notelp Pengirim</th>
		<th>Keterangan</th>
		
            </tr><?php
            foreach ($tbl_pengirim_data as $tbl_pengirim)
            {
                ?>
                <tr>
		      <td><?php echo ++$start ?></td>
		      <td><?php echo $tbl_pengirim->id ?></td>
		      <td><?php echo $tbl_pengirim->uuid_pengirim ?></td>
		      <td><?php echo $tbl_pengirim->date_input ?></td>
		      <td><?php echo $tbl_pengirim->id_user_input ?></td>
		      <td><?php echo $tbl_pengirim->nama_pengirim ?></td>
		      <td><?php echo $tbl_pengirim->alamat_pengirim ?></td>
		      <td><?php echo $tbl_pengirim->notelp_pengirim ?></td>
		      <td><?php echo $tbl_pengirim->keterangan ?></td>	
                </tr>
                <?php
            }
            ?>
        </table>
    </body>
</html>