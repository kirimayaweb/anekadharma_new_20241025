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
        <h2>Tbl_pembayaran_pasar List</h2>
        <table class="word-table" style="margin-bottom: 10px">
            <tr>
                <th>No</th>
		<th>Kodepasar</th>
		<th>Idpedagang</th>
		<th>Koderetribusi</th>
		<th>Namatagihan</th>
		<th>Nominal</th>
		<th>Tglbayar</th>
		
            </tr><?php
            foreach ($tbl_pembayaran_pasar_data as $tbl_pembayaran_pasar)
            {
                ?>
                <tr>
		      <td><?php echo ++$start ?></td>
		      <td><?php tampil($tbl_pembayaran_pasar->kodepasar) ?></td>
		      <td><?php tampil($tbl_pembayaran_pasar->idpedagang) ?></td>
		      <td><?php tampil($tbl_pembayaran_pasar->koderetribusi) ?></td>
		      <td><?php tampil($tbl_pembayaran_pasar->namatagihan) ?></td>
		      <td><?php tampil($tbl_pembayaran_pasar->nominal) ?></td>
		      <td><?php tampil($tbl_pembayaran_pasar->tglbayar) ?></td>	
                </tr>
                <?php
            }
            ?>
        </table>
    </body>
</html>