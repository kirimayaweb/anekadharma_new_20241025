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
        <h2 style="margin-top:0px">Tbl_pembelian_pengajuan_bayar List</h2>
        <div class="row" style="margin-bottom: 10px">
            <div class="col-md-4">
                <?php echo anchor(site_url('tbl_pembelian_pengajuan_bayar/create'),'Create', 'class="btn btn-primary"'); ?>
            </div>
            <div class="col-md-4 text-center">
                <div style="margin-top: 8px" id="message">
                    <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
                </div>
            </div>
            <div class="col-md-1 text-right">
            </div>
            <div class="col-md-3 text-right">
                <form action="<?php echo site_url('tbl_pembelian_pengajuan_bayar/index'); ?>" class="form-inline" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                        <span class="input-group-btn">
                            <?php 
                                if ($q <> '')
                                {
                                    ?>
                                    <a href="<?php echo site_url('tbl_pembelian_pengajuan_bayar'); ?>" class="btn btn-default">Reset</a>
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
		<th>Proses Input</th>
		<th>Date Input</th>
		<th>Uuid Pengajuan Bayar</th>
		<th>Uuid Spop</th>
		<th>Tgl Pengajuan</th>
		<th>Nominal Pengajuan</th>
		<th>Id Usr</th>
		<th>Action</th>
            </tr><?php
            foreach ($tbl_pembelian_pengajuan_bayar_data as $tbl_pembelian_pengajuan_bayar)
            {
                ?>
                <tr>
			<td width="80px"><?php echo ++$start ?></td>
			<td><?php echo $tbl_pembelian_pengajuan_bayar->proses_input ?></td>
			<td><?php echo $tbl_pembelian_pengajuan_bayar->date_input ?></td>
			<td><?php echo $tbl_pembelian_pengajuan_bayar->uuid_pengajuan_bayar ?></td>
			<td><?php echo $tbl_pembelian_pengajuan_bayar->uuid_spop ?></td>
			<td><?php echo $tbl_pembelian_pengajuan_bayar->tgl_pengajuan ?></td>
			<td><?php echo $tbl_pembelian_pengajuan_bayar->nominal_pengajuan ?></td>
			<td><?php echo $tbl_pembelian_pengajuan_bayar->id_usr ?></td>
			<td style="text-align:center" width="200px">
				<?php 
				echo anchor(site_url('tbl_pembelian_pengajuan_bayar/read/'.$tbl_pembelian_pengajuan_bayar->id),'Read'); 
				echo ' | '; 
				echo anchor(site_url('tbl_pembelian_pengajuan_bayar/update/'.$tbl_pembelian_pengajuan_bayar->id),'Update'); 
				echo ' | '; 
				echo anchor(site_url('tbl_pembelian_pengajuan_bayar/delete/'.$tbl_pembelian_pengajuan_bayar->id),'Delete','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'); 
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
		<?php echo anchor(site_url('tbl_pembelian_pengajuan_bayar/excel'), 'Excel', 'class="btn btn-primary"'); ?>
	    </div>
            <div class="col-md-6 text-right">
                <?php echo $pagination ?>
            </div>
        </div>
    </body>
</html>