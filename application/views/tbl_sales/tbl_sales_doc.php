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
        <h2>Tbl_sales List</h2>
        <table class="word-table" style="margin-bottom: 10px">
            <tr>
                <th>No</th>
		<th>Uuid Sales</th>
		<th>Date Input</th>
		<th>Id User Input</th>
		<th>Nama Sales</th>
		<th>Alamat Sales</th>
		<th>Notelp Sales</th>
		<th>Keterangan</th>
		
            </tr><?php
            foreach ($tbl_sales_data as $tbl_sales)
            {
                ?>
                <tr>
		      <td><?php echo ++$start ?></td>
		      <td><?php echo $tbl_sales->uuid_sales ?></td>
		      <td><?php echo $tbl_sales->date_input ?></td>
		      <td><?php echo $tbl_sales->id_user_input ?></td>
		      <td><?php echo $tbl_sales->nama_sales ?></td>
		      <td><?php echo $tbl_sales->alamat_sales ?></td>
		      <td><?php echo $tbl_sales->notelp_sales ?></td>
		      <td><?php echo $tbl_sales->keterangan ?></td>	
                </tr>
                <?php
            }
            ?>
        </table>
    </body>
</html>