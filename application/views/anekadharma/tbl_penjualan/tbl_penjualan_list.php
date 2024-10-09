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
        <h2 style="margin-top:0px">Tbl_penjualan List</h2>
        <div class="row" style="margin-bottom: 10px">
            <div class="col-md-4">
                <?php echo anchor(site_url('tbl_penjualan/create'),'Create', 'class="btn btn-primary"'); ?>
            </div>
            <div class="col-md-4 text-center">
                <div style="margin-top: 8px" id="message">
                    <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
                </div>
            </div>
            <div class="col-md-1 text-right">
            </div>
            <div class="col-md-3 text-right">
                <form action="<?php echo site_url('tbl_penjualan/index'); ?>" class="form-inline" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                        <span class="input-group-btn">
                            <?php 
                                if ($q <> '')
                                {
                                    ?>
                                    <a href="<?php echo site_url('tbl_penjualan'); ?>" class="btn btn-default">Reset</a>
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
		<th>Nmrpesan</th>
		<th>Nmrkirim</th>
		<th>Konsumen Id</th>
		<th>Konsumen Nama</th>
		<th>Kode Barang</th>
		<th>Nama Barang</th>
		<th>Unit</th>
		<th>Satuan</th>
		<th>Harga Satuan</th>
		<th>Jumlah</th>
		<th>Umpphpsl22</th>
		<th>Piutang</th>
		<th>Penjualandpp</th>
		<th>Utangppn</th>
		<th>Id Usr</th>
		<th>Action</th>
            </tr><?php
            foreach ($tbl_penjualan_data as $tbl_penjualan)
            {
                ?>
                <tr>
			<td width="80px"><?php echo ++$start ?></td>
			<td><?php echo $tbl_penjualan->tgl_input ?></td>
			<td><?php echo $tbl_penjualan->nmrpesan ?></td>
			<td><?php echo $tbl_penjualan->nmrkirim ?></td>
			<td><?php echo $tbl_penjualan->konsumen_id ?></td>
			<td><?php echo $tbl_penjualan->konsumen_nama ?></td>
			<td><?php echo $tbl_penjualan->kode_barang ?></td>
			<td><?php echo $tbl_penjualan->nama_barang ?></td>
			<td><?php echo $tbl_penjualan->unit ?></td>
			<td><?php echo $tbl_penjualan->satuan ?></td>
			<td><?php echo $tbl_penjualan->harga_satuan ?></td>
			<td><?php echo $tbl_penjualan->jumlah ?></td>
			<td><?php echo $tbl_penjualan->umpphpsl22 ?></td>
			<td><?php echo $tbl_penjualan->piutang ?></td>
			<td><?php echo $tbl_penjualan->penjualandpp ?></td>
			<td><?php echo $tbl_penjualan->utangppn ?></td>
			<td><?php echo $tbl_penjualan->id_usr ?></td>
			<td style="text-align:center" width="200px">
				<?php 
				echo anchor(site_url('tbl_penjualan/read/'.$tbl_penjualan->id),'Read'); 
				echo ' | '; 
				echo anchor(site_url('tbl_penjualan/update/'.$tbl_penjualan->id),'Update'); 
				echo ' | '; 
				echo anchor(site_url('tbl_penjualan/delete/'.$tbl_penjualan->id),'Delete','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'); 
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
		<?php echo anchor(site_url('tbl_penjualan/excel'), 'Excel', 'class="btn btn-primary"'); ?>
	    </div>
            <div class="col-md-6 text-right">
                <?php echo $pagination ?>
            </div>
        </div>
    </body>
</html>