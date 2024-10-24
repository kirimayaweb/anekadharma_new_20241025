<!doctype html>
<html>
    <head>
        <title>aneka dharma</title>
        <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>"/>
        <style>
            body{
                padding: 15px;
            }
        </style>
    </head>
    <body>
        <h2 style="margin-top:0px">Data Status Transaksi</h2>
    
        <table class="table table-bordered" style="margin-bottom: 10px">
            <tr>
                <th>No</th>
		<th>Uuid Status Transaksi</th>
		<th>Status</th>
		<th>Keterangan</th>
		<th>Action</th>
            </tr><?php
            foreach ($sys_status_transaksi_data as $list_data)
            {
                ?>
                <tr>
			<td width="80px"><?php echo ++$start ?></td>
			<td><?php echo $list_data->uuid_status_transaksi ?></td>
			<td><?php echo $list_data->status ?></td>
			<td><?php echo $list_data->keterangan ?></td>
			<td style="text-align:center" width="200px">
				<?php 
				echo anchor(site_url('sys_status_transaksi/read/'.$list_data->id),'Read'); 
				echo ' | '; 
				echo anchor(site_url('sys_status_transaksi/update/'.$list_data->id),'Update'); 
				echo ' | '; 
				echo anchor(site_url('sys_status_transaksi/delete/'.$list_data->id),'Delete','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'); 
				?>
			</td>
		</tr>
                <?php
            }
            ?>
        </table>
        <div class="row">
            <div class="col-md-6">
                <a href="#" class="btn btn-primary">Total Record : <?php echo $total_rows ?></a>
	    </div>
            <div class="col-md-6 text-right">
                <?php echo $pagination ?>
            </div>
        </div>
    </body>
</html>