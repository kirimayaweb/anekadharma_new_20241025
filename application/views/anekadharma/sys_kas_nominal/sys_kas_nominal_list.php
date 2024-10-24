<!doctype html>
<html>
    <head>
        <title>harviacode.com - codeigniter crud generator</title>
        <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>"/>
        <style>
            body{
                padding: 15px;
            }
        </style>
    </head>
    <body>
        <h2 style="margin-top:0px">Sys_kas_nominal List</h2>
        <div class="row" style="margin-bottom: 10px">
            <div class="col-md-4">
                <?php echo anchor(site_url('sys_kas_nominal/create'),'Create', 'class="btn btn-primary"'); ?>
            </div>
            <div class="col-md-4 text-center">
                <div style="margin-top: 8px" id="message">
                    <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
                </div>
            </div>
            <div class="col-md-1 text-right">
            </div>
            <div class="col-md-3 text-right">
                <form action="<?php echo site_url('sys_kas_nominal/index'); ?>" class="form-inline" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                        <span class="input-group-btn">
                            <?php 
                                if ($q <> '')
                                {
                                    ?>
                                    <a href="<?php echo site_url('sys_kas_nominal'); ?>" class="btn btn-default">Reset</a>
                                    <?php
                                }
                            ?>
                          <button class="btn btn-primary" type="submit">Search</button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
        <table class="table table-bordered" style="margin-bottom: 10px">
            <tr>
                <th>No</th>
		<th>Tgl Input</th>
		<th>Uuid Kas Nominal</th>
		<th>Total Kas Nominal</th>
		<th>Kode Kas Nominal</th>
		<th>Action</th>
            </tr><?php
            foreach ($sys_kas_nominal_data as $sys_kas_nominal)
            {
                ?>
                <tr>
			<td width="80px"><?php echo ++$start ?></td>
			<td><?php echo $sys_kas_nominal->tgl_input ?></td>
			<td><?php echo $sys_kas_nominal->uuid_kas_nominal ?></td>
			<td><?php echo $sys_kas_nominal->total_kas_nominal ?></td>
			<td><?php echo $sys_kas_nominal->kode_kas_nominal ?></td>
			<td style="text-align:center" width="200px">
				<?php 
				echo anchor(site_url('sys_kas_nominal/read/'.$sys_kas_nominal->id),'Read'); 
				echo ' | '; 
				echo anchor(site_url('sys_kas_nominal/update/'.$sys_kas_nominal->id),'Update'); 
				echo ' | '; 
				echo anchor(site_url('sys_kas_nominal/delete/'.$sys_kas_nominal->id),'Delete','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'); 
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
		<?php echo anchor(site_url('sys_kas_nominal/excel'), 'Excel', 'class="btn btn-primary"'); ?>
	    </div>
            <div class="col-md-6 text-right">
                <?php echo $pagination ?>
            </div>
        </div>
    </body>
</html>