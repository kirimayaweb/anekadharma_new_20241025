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
        <h2>Tbl_pasar_input_tagihan List</h2>
        <table class="word-table" style="margin-bottom: 10px">
            <tr>
                <th>No</th>
		<th>Kodepasar</th>
		<th>Idpedagang</th>
		<th>Koderetribusi</th>
		<th>Namatagihan</th>
		<th>Nominal</th>
		<th>Tgl Awal</th>
		<th>Tgl Akhir</th>
		<th>Tgl Aktif</th>
		<th>Tarifkelaspasar</th>
		<th>Denda</th>
		<th>Tagihan Kebersihan</th>
		<th>Tagihan Tera</th>
		<th>Tahun</th>
		<th>Bulan</th>
		<th>Status Kirim</th>
		
            </tr><?php
            foreach ($tbl_pasar_input_tagihan_data as $tbl_pasar_input_tagihan)
            {
                ?>
                <tr>
		      <td><?php echo ++$start ?></td>
		      <td><?php tampil($tbl_pasar_input_tagihan->kodepasar) ?></td>
		      <td><?php tampil($tbl_pasar_input_tagihan->idpedagang) ?></td>
		      <td><?php tampil($tbl_pasar_input_tagihan->koderetribusi) ?></td>
		      <td><?php tampil($tbl_pasar_input_tagihan->namatagihan) ?></td>
		      <td><?php tampil($tbl_pasar_input_tagihan->nominal) ?></td>
		      <td><?php tampil($tbl_pasar_input_tagihan->tgl_awal) ?></td>
		      <td><?php tampil($tbl_pasar_input_tagihan->tgl_akhir) ?></td>
		      <td><?php tampil($tbl_pasar_input_tagihan->tgl_aktif) ?></td>
		      <td><?php tampil($tbl_pasar_input_tagihan->tarifkelaspasar) ?></td>
		      <td><?php tampil($tbl_pasar_input_tagihan->denda) ?></td>
		      <td><?php tampil($tbl_pasar_input_tagihan->tagihan_kebersihan) ?></td>
		      <td><?php tampil($tbl_pasar_input_tagihan->tagihan_tera) ?></td>
		      <td><?php tampil($tbl_pasar_input_tagihan->tahun) ?></td>
		      <td><?php tampil($tbl_pasar_input_tagihan->bulan) ?></td>
		      <td><?php tampil($tbl_pasar_input_tagihan->status_kirim) ?></td>	
                </tr>
                <?php
            }
            ?>
        </table>
    </body>
</html>