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
        <h2>Tbl_company_cetak List</h2>
        <table class="word-table" style="margin-bottom: 10px">
            <tr>
                <th>No</th>
		<th>Uuid Company Cetak</th>
		<th>Date Input</th>
		<th>Id User Input</th>
		<th>Nama Perusahaan</th>
		<th>Alamat Perusahaan</th>
		<th>Notelp Perusahaan</th>
		<th>Keterangan</th>
		
            </tr><?php
            foreach ($tbl_company_cetak_data as $tbl_company_cetak)
            {
                ?>
                <tr>
		      <td><?php echo ++$start ?></td>
		      <td><?php echo $tbl_company_cetak->uuid_company_cetak ?></td>
		      <td><?php echo $tbl_company_cetak->date_input ?></td>
		      <td><?php echo $tbl_company_cetak->id_user_input ?></td>
		      <td><?php echo $tbl_company_cetak->nama_perusahaan ?></td>
		      <td><?php echo $tbl_company_cetak->alamat_perusahaan ?></td>
		      <td><?php echo $tbl_company_cetak->notelp_perusahaan ?></td>
		      <td><?php echo $tbl_company_cetak->keterangan ?></td>	
                </tr>
                <?php
            }
            ?>
        </table>
    </body>
</html>