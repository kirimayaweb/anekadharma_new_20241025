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
        <h2>Sys_cover List</h2>
        <table class="word-table" style="margin-bottom: 10px">
            <tr>
                <th>No</th>
		<th>Uuid Cover</th>
		<th>Date Input</th>
		<th>Id User Input</th>
		<th>Tingkat</th>
		<th>Mapel</th>
		<th>Kelas</th>
		<th>Tahun</th>
		<th>Semester</th>
		<th>Halaman</th>
		<th>Nama Cover</th>
		<th>Keterangan</th>
		
            </tr><?php
            foreach ($sys_cover_data as $sys_cover)
            {
                ?>
                <tr>
		      <td><?php echo ++$start ?></td>
		      <td><?php echo $sys_cover->uuid_cover ?></td>
		      <td><?php echo $sys_cover->date_input ?></td>
		      <td><?php echo $sys_cover->id_user_input ?></td>
		      <td><?php echo $sys_cover->tingkat ?></td>
		      <td><?php echo $sys_cover->mapel ?></td>
		      <td><?php echo $sys_cover->kelas ?></td>
		      <td><?php echo $sys_cover->tahun ?></td>
		      <td><?php echo $sys_cover->semester ?></td>
		      <td><?php echo $sys_cover->halaman ?></td>
		      <td><?php echo $sys_cover->nama_cover ?></td>
		      <td><?php echo $sys_cover->keterangan ?></td>	
                </tr>
                <?php
            }
            ?>
        </table>
    </body>
</html>