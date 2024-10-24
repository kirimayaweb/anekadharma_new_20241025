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
        <h2 style="margin-top:0px">Tbl_pembelian_pecah_satuan List</h2>
        <div class="row" style="margin-bottom: 10px">
            <div class="col-md-4">
                <?php echo anchor(site_url('tbl_pembelian_pecah_satuan/create'),'Create', 'class="btn btn-primary"'); ?>
            </div>
            <div class="col-md-4 text-center">
                <div style="margin-top: 8px" id="message">
                    <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
                </div>
            </div>
            <div class="col-md-1 text-right">
            </div>
            <div class="col-md-3 text-right">
                <form action="<?php echo site_url('tbl_pembelian_pecah_satuan/index'); ?>" class="form-inline" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                        <span class="input-group-btn">
                            <?php 
                                if ($q <> '')
                                {
                                    ?>
                                    <a href="<?php echo site_url('tbl_pembelian_pecah_satuan'); ?>" class="btn btn-default">Reset</a>
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
		<th>Uuid Pembelian</th>
		<th>Uuid Barang</th>
		<th>Tgl Po</th>
		<th>Nmrsj</th>
		<th>Nmrfakturkwitansi</th>
		<th>Nmrbpb</th>
		<th>Uuid Spop</th>
		<th>Spop</th>
		<th>Status Spop</th>
		<th>Uuid Supplier</th>
		<th>Supplier Kode</th>
		<th>Supplier Nama</th>
		<th>Kode Barang</th>
		<th>Uraian</th>
		<th>Jumlah</th>
		<th>Satuan</th>
		<th>Uuid Konsumen</th>
		<th>Konsumen</th>
		<th>Uuid Gudang</th>
		<th>Nama Gudang</th>
		<th>Harga Satuan</th>
		<th>Harga Total</th>
		<th>Statuslu</th>
		<th>Kas Bank</th>
		<th>Tgl Bayar</th>
		<th>Id Usr</th>
		<th>Tgl Pengajuan 1</th>
		<th>Nominal Pengajuan 1</th>
		<th>Tgl Pengajuan 2</th>
		<th>Nominal Pengajuan 2</th>
		<th>Uuid Gudang Baru</th>
		<th>Kode Barang Baru</th>
		<th>Nama Barang Baru</th>
		<th>Nominal Bayar Input</th>
		<th>Satuan Barang Baru</th>
		<th>Harga Satuan Barang Baru</th>
		<th>Action</th>
            </tr><?php
            foreach ($tbl_pembelian_pecah_satuan_data as $tbl_pembelian_pecah_satuan)
            {
                ?>
                <tr>
			<td width="80px"><?php echo ++$start ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->proses_input ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->date_input ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->uuid_pembelian ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->uuid_barang ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->tgl_po ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->nmrsj ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->nmrfakturkwitansi ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->nmrbpb ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->uuid_spop ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->spop ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->status_spop ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->uuid_supplier ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->supplier_kode ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->supplier_nama ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->kode_barang ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->uraian ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->jumlah ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->satuan ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->uuid_konsumen ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->konsumen ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->uuid_gudang ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->nama_gudang ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->harga_satuan ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->harga_total ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->statuslu ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->kas_bank ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->tgl_bayar ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->id_usr ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->tgl_pengajuan_1 ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->nominal_pengajuan_1 ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->tgl_pengajuan_2 ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->nominal_pengajuan_2 ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->uuid_gudang_baru ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->kode_barang_baru ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->nama_barang_baru ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->nominal_bayar_input ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->satuan_barang_baru ?></td>
			<td><?php echo $tbl_pembelian_pecah_satuan->harga_satuan_barang_baru ?></td>
			<td style="text-align:center" width="200px">
				<?php 
				echo anchor(site_url('tbl_pembelian_pecah_satuan/read/'.$tbl_pembelian_pecah_satuan->id),'Read'); 
				echo ' | '; 
				echo anchor(site_url('tbl_pembelian_pecah_satuan/update/'.$tbl_pembelian_pecah_satuan->id),'Update'); 
				echo ' | '; 
				echo anchor(site_url('tbl_pembelian_pecah_satuan/delete/'.$tbl_pembelian_pecah_satuan->id),'Delete','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'); 
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
		<?php echo anchor(site_url('tbl_pembelian_pecah_satuan/excel'), 'Excel', 'class="btn btn-primary"'); ?>
	    </div>
            <div class="col-md-6 text-right">
                <?php echo $pagination ?>
            </div>
        </div>
    </body>
</html>