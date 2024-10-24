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
        <h2>Persediaan List</h2>
        <table class="word-table" style="margin-bottom: 10px">
            <tr>
                <th>No</th>
		<th>Tanggal</th>
		<th> Kode</th>
		<th>Namabarang</th>
		<th>Sa</th>
		<th> Hpp</th>
		<th> Sa</th>
		<th>Spop</th>
		<th>Beli</th>
		<th> Tuj</th>
		<th>Tgl Keluar</th>
		<th>Sekret</th>
		<th>Cetak</th>
		<th>Grafikita</th>
		<th>Dinas Umum</th>
		<th>Atk Rsud</th>
		<th>Ppbmp Kbs</th>
		<th>Kbs</th>
		<th>Ppbmp</th>
		<th>Medis</th>
		<th>Siiplah Bosda</th>
		<th>Sembako</th>
		<th>Fc Gose</th>
		<th>Fc Manding</th>
		<th>Fc Psamya</th>
		<th>Total 10</th>
		<th> Nilai Persediaan</th>
		
            </tr><?php
            foreach ($persediaan_data as $persediaan)
            {
                ?>
                <tr>
		      <td><?php echo ++$start ?></td>
		      <td><?php echo $persediaan->tanggal ?></td>
		      <td><?php echo $persediaan-> kode ?></td>
		      <td><?php echo $persediaan->namabarang ?></td>
		      <td><?php echo $persediaan->sa ?></td>
		      <td><?php echo $persediaan-> hpp ?></td>
		      <td><?php echo $persediaan-> sa ?></td>
		      <td><?php echo $persediaan->spop ?></td>
		      <td><?php echo $persediaan->beli ?></td>
		      <td><?php echo $persediaan-> tuj ?></td>
		      <td><?php echo $persediaan->tgl_keluar ?></td>
		      <td><?php echo $persediaan->sekret ?></td>
		      <td><?php echo $persediaan->cetak ?></td>
		      <td><?php echo $persediaan->grafikita ?></td>
		      <td><?php echo $persediaan->dinas_umum ?></td>
		      <td><?php echo $persediaan->atk_rsud ?></td>
		      <td><?php echo $persediaan->ppbmp_kbs ?></td>
		      <td><?php echo $persediaan->kbs ?></td>
		      <td><?php echo $persediaan->ppbmp ?></td>
		      <td><?php echo $persediaan->medis ?></td>
		      <td><?php echo $persediaan->siiplah_bosda ?></td>
		      <td><?php echo $persediaan->sembako ?></td>
		      <td><?php echo $persediaan->fc_gose ?></td>
		      <td><?php echo $persediaan->fc_manding ?></td>
		      <td><?php echo $persediaan->fc_psamya ?></td>
		      <td><?php echo $persediaan->total_10 ?></td>
		      <td><?php echo $persediaan-> nilai_persediaan ?></td>	
                </tr>
                <?php
            }
            ?>
        </table>
    </body>
</html>