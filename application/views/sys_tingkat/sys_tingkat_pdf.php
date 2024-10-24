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
        <h2>Sys_tingkat List</h2>
        <table class="word-table" style="margin-bottom: 10px">
            <tr>
                <th>No</th>
		<th>Uuid Tingkat</th>
		<th>Tingkat System</th>
		<th>Tingkat</th>
		
            </tr><?php
            foreach ($sys_tingkat_data as $sys_tingkat)
            {
                ?>
                <tr>
		      <td><?php echo ++$start ?></td>
		      <td><?php echo $sys_tingkat->uuid_tingkat ?></td>
		      <td><?php echo $sys_tingkat->tingkat_system ?></td>
		      <td><?php echo $sys_tingkat->tingkat ?></td>	
                </tr>
                <?php
            }
            ?>
        </table>
    </body>
</html>