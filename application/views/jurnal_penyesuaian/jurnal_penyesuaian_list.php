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
        <h2 style="margin-top:0px">Jurnal_penyesuaian List</h2>
        <div class="row" style="margin-bottom: 10px">
            <div class="col-md-4">
                <?php echo anchor(site_url('jurnal_penyesuaian/create'),'Create', 'class="btn btn-primary"'); ?>
            </div>
            <div class="col-md-4 text-center">
                <div style="margin-top: 8px" id="message">
                    <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
                </div>
            </div>
            <div class="col-md-1 text-right">
            </div>
            <div class="col-md-3 text-right">
                <form action="<?php echo site_url('jurnal_penyesuaian/index'); ?>" class="form-inline" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                        <span class="input-group-btn">
                            <?php 
                                if ($q <> '')
                                {
                                    ?>
                                    <a href="<?php echo site_url('jurnal_penyesuaian'); ?>" class="btn btn-default">Reset</a>
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
		<th>Uuid Jurnal Penyesuaian</th>
		<th>Kode Akun</th>
		<th>Tanggal</th>
		<th>Keterangan</th>
		<th>Kode Rekening</th>
		<th>Debet</th>
		<th>Kredit</th>
		<th>Action</th>
            </tr><?php
            foreach ($jurnal_penyesuaian_data as $jurnal_penyesuaian)
            {
                ?>
                <tr>
			<td width="80px"><?php echo ++$start ?></td>
			<td><?php echo $jurnal_penyesuaian->uuid_jurnal_penyesuaian ?></td>
			<td><?php echo $jurnal_penyesuaian->kode_akun ?></td>
			<td><?php echo $jurnal_penyesuaian->tanggal ?></td>
			<td><?php echo $jurnal_penyesuaian->keterangan ?></td>
			<td><?php echo $jurnal_penyesuaian->kode_rekening ?></td>
			<td><?php echo $jurnal_penyesuaian->debet ?></td>
			<td><?php echo $jurnal_penyesuaian->kredit ?></td>
			<td style="text-align:center" width="200px">
				<?php 
				echo anchor(site_url('jurnal_penyesuaian/read/'.$jurnal_penyesuaian->id),'Read'); 
				echo ' | '; 
				echo anchor(site_url('jurnal_penyesuaian/update/'.$jurnal_penyesuaian->id),'Update'); 
				echo ' | '; 
				echo anchor(site_url('jurnal_penyesuaian/delete/'.$jurnal_penyesuaian->id),'Delete','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'); 
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
		<?php echo anchor(site_url('jurnal_penyesuaian/excel'), 'Excel', 'class="btn btn-primary"'); ?>
	    </div>
            <div class="col-md-6 text-right">
                <?php echo $pagination ?>
            </div>
        </div>
    </body>
</html>