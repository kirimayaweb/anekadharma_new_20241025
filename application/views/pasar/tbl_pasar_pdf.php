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
        <h2>Tbl_pasar List</h2>
        <table class="word-table" style="margin-bottom: 10px">
            <tr>
                <th>No</th>
		<th>Koderetribusi</th>
		<th>Namapasar</th>
		<th>Kode</th>
		<th>Kodekios</th>
		<th>Kodelos</th>
		<th>Kodearahan</th>
		<th>Panjang</th>
		<th>Lebar</th>
		<th>Nokios</th>
		<th>Kelaspasar</th>
		<th>Tarifkelaspasar</th>
		<th>Idpenyewa</th>
		<th>Tglmulai</th>
		<th>Tglakhir</th>
		
            </tr><?php
            foreach ($tbl_pasar_data as $tbl_pasar)
            {
                ?>
                <tr>
		      <td><?php echo ++$start ?></td>
		      <td><?php tampil($tbl_pasar->koderetribusi) ?></td>
		      <td><?php tampil($tbl_pasar->namapasar) ?></td>
		      <td><?php tampil($tbl_pasar->kode) ?></td>
		      <td><?php tampil($tbl_pasar->kodekios) ?></td>
		      <td><?php tampil($tbl_pasar->kodelos) ?></td>
		      <td><?php tampil($tbl_pasar->kodearahan) ?></td>
		      <td><?php tampil($tbl_pasar->panjang) ?></td>
		      <td><?php tampil($tbl_pasar->lebar) ?></td>
		      <td><?php tampil($tbl_pasar->nokios) ?></td>
		      <td><?php tampil($tbl_pasar->kelaspasar) ?></td>
		      <td><?php tampil($tbl_pasar->tarifkelaspasar) ?></td>
		      <td><?php tampil($tbl_pasar->idpenyewa) ?></td>
		      <td><?php tampil($tbl_pasar->tglmulai) ?></td>
		      <td><?php tampil($tbl_pasar->tglakhir) ?></td>	
                </tr>
                <?php
            }
            ?>
        </table>
    </body>
</html>