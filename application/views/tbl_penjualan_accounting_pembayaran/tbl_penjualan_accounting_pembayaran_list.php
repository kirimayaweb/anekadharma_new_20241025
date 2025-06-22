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
        <h2 style="margin-top:0px">Tbl_penjualan_accounting_pembayaran List</h2>
        <div class="row" style="margin-bottom: 10px">
            <div class="col-md-4">
                <?php echo anchor(site_url('tbl_penjualan_accounting_pembayaran/create'),'Create', 'class="btn btn-primary"'); ?>
            </div>
            <div class="col-md-4 text-center">
                <div style="margin-top: 8px" id="message">
                    <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
                </div>
            </div>
            <div class="col-md-1 text-right">
            </div>
            <div class="col-md-3 text-right">
                <form action="<?php echo site_url('tbl_penjualan_accounting_pembayaran/index'); ?>" class="form-inline" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                        <span class="input-group-btn">
                            <?php 
                                if ($q <> '')
                                {
                                    ?>
                                    <a href="<?php echo site_url('tbl_penjualan_accounting_pembayaran'); ?>" class="btn btn-default">Reset</a>
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
		<th>Uuid Pembayaran</th>
		<th>Tgl Bayar</th>
		<th>Nominal Bayar</th>
		<th>Nmr Bukti Bayar</th>
		<th>Uuid Penjualan</th>
		<th>Uuid Barang</th>
		<th>Tgl Input</th>
		<th>Tgl Jual</th>
		<th>Nmrpesan</th>
		<th>Nmrkirim</th>
		<th>Uuid Konsumen</th>
		<th>Konsumen Id</th>
		<th>Konsumen Nama</th>
		<th>Kode Barang</th>
		<th>Nama Barang</th>
		<th>Uuid Unit</th>
		<th>Unit</th>
		<th>Satuan</th>
		<th>Harga Satuan</th>
		<th>Jumlah</th>
		<th>Total Nominal</th>
		<th>Umpphpsl22</th>
		<th>Piutang</th>
		<th>Penjualandpp</th>
		<th>Utangppn</th>
		<th>Uuid Kode Akun</th>
		<th>Kode Akun</th>
		<th>Nama Akun</th>
		<th>Id Usr</th>
		<th>Action</th>
            </tr><?php
            foreach ($tbl_penjualan_accounting_pembayaran_data as $tbl_penjualan_accounting_pembayaran)
            {
                ?>
                <tr>
			<td width="80px"><?php echo ++$start ?></td>
			<td><?php echo $tbl_penjualan_accounting_pembayaran->uuid_pembayaran ?></td>
			<td><?php echo $tbl_penjualan_accounting_pembayaran->tgl_bayar ?></td>
			<td><?php echo $tbl_penjualan_accounting_pembayaran->nominal_bayar ?></td>
			<td><?php echo $tbl_penjualan_accounting_pembayaran->nmr_bukti_bayar ?></td>
			<td><?php echo $tbl_penjualan_accounting_pembayaran->uuid_penjualan ?></td>
			<td><?php echo $tbl_penjualan_accounting_pembayaran->uuid_barang ?></td>
			<td><?php echo $tbl_penjualan_accounting_pembayaran->tgl_input ?></td>
			<td><?php echo $tbl_penjualan_accounting_pembayaran->tgl_jual ?></td>
			<td><?php echo $tbl_penjualan_accounting_pembayaran->nmrpesan ?></td>
			<td><?php echo $tbl_penjualan_accounting_pembayaran->nmrkirim ?></td>
			<td><?php echo $tbl_penjualan_accounting_pembayaran->uuid_konsumen ?></td>
			<td><?php echo $tbl_penjualan_accounting_pembayaran->konsumen_id ?></td>
			<td><?php echo $tbl_penjualan_accounting_pembayaran->konsumen_nama ?></td>
			<td><?php echo $tbl_penjualan_accounting_pembayaran->kode_barang ?></td>
			<td><?php echo $tbl_penjualan_accounting_pembayaran->nama_barang ?></td>
			<td><?php echo $tbl_penjualan_accounting_pembayaran->uuid_unit ?></td>
			<td><?php echo $tbl_penjualan_accounting_pembayaran->unit ?></td>
			<td><?php echo $tbl_penjualan_accounting_pembayaran->satuan ?></td>
			<td><?php echo $tbl_penjualan_accounting_pembayaran->harga_satuan ?></td>
			<td><?php echo $tbl_penjualan_accounting_pembayaran->jumlah ?></td>
			<td><?php echo $tbl_penjualan_accounting_pembayaran->total_nominal ?></td>
			<td><?php echo $tbl_penjualan_accounting_pembayaran->umpphpsl22 ?></td>
			<td><?php echo $tbl_penjualan_accounting_pembayaran->piutang ?></td>
			<td><?php echo $tbl_penjualan_accounting_pembayaran->penjualandpp ?></td>
			<td><?php echo $tbl_penjualan_accounting_pembayaran->utangppn ?></td>
			<td><?php echo $tbl_penjualan_accounting_pembayaran->uuid_kode_akun ?></td>
			<td><?php echo $tbl_penjualan_accounting_pembayaran->kode_akun ?></td>
			<td><?php echo $tbl_penjualan_accounting_pembayaran->nama_akun ?></td>
			<td><?php echo $tbl_penjualan_accounting_pembayaran->id_usr ?></td>
			<td style="text-align:center" width="200px">
				<?php 
				echo anchor(site_url('tbl_penjualan_accounting_pembayaran/read/'.$tbl_penjualan_accounting_pembayaran->id),'Read'); 
				echo ' | '; 
				echo anchor(site_url('tbl_penjualan_accounting_pembayaran/update/'.$tbl_penjualan_accounting_pembayaran->id),'Update'); 
				echo ' | '; 
				echo anchor(site_url('tbl_penjualan_accounting_pembayaran/delete/'.$tbl_penjualan_accounting_pembayaran->id),'Delete','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'); 
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