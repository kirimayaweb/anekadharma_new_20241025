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
        <h2 style="margin-top:0px">Jurnal_kas_saldo_akhir_bulan List</h2>
        <div class="row" style="margin-bottom: 10px">
            <div class="col-md-4">
                <?php echo anchor(site_url('jurnal_kas_saldo_akhir_bulan/create'),'Create', 'class="btn btn-primary"'); ?>
            </div>
            <div class="col-md-4 text-center">
                <div style="margin-top: 8px" id="message">
                    <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
                </div>
            </div>
            <div class="col-md-1 text-right">
            </div>
            <div class="col-md-3 text-right">
                <form action="<?php echo site_url('jurnal_kas_saldo_akhir_bulan/index'); ?>" class="form-inline" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                        <span class="input-group-btn">
                            <?php 
                                if ($q <> '')
                                {
                                    ?>
                                    <a href="<?php echo site_url('jurnal_kas_saldo_akhir_bulan'); ?>" class="btn btn-default">Reset</a>
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
		<th>Uuid Jurnal Kas Saldo Akhir Bulan</th>
		<th>Kode Akun</th>
		<th>Id Buku Besar</th>
		<th>Tanggal</th>
		<th>Bukti</th>
		<th>Pl</th>
		<th>Keterangan</th>
		<th>Kode Rekening</th>
		<th>Uuid Unit</th>
		<th>Kode Unit</th>
		<th>Debet</th>
		<th>Kredit</th>
		<th>Saldo</th>
		<th>Action</th>
            </tr><?php
            foreach ($jurnal_kas_saldo_akhir_bulan_data as $jurnal_kas_saldo_akhir_bulan)
            {
                ?>
                <tr>
			<td width="80px"><?php echo ++$start ?></td>
			<td><?php echo $jurnal_kas_saldo_akhir_bulan->uuid_jurnal_kas_saldo_akhir_bulan ?></td>
			<td><?php echo $jurnal_kas_saldo_akhir_bulan->kode_akun ?></td>
			<td><?php echo $jurnal_kas_saldo_akhir_bulan->id_buku_besar ?></td>
			<td><?php echo $jurnal_kas_saldo_akhir_bulan->tanggal ?></td>
			<td><?php echo $jurnal_kas_saldo_akhir_bulan->bukti ?></td>
			<td><?php echo $jurnal_kas_saldo_akhir_bulan->pl ?></td>
			<td><?php echo $jurnal_kas_saldo_akhir_bulan->keterangan ?></td>
			<td><?php echo $jurnal_kas_saldo_akhir_bulan->kode_rekening ?></td>
			<td><?php echo $jurnal_kas_saldo_akhir_bulan->uuid_unit ?></td>
			<td><?php echo $jurnal_kas_saldo_akhir_bulan->kode_unit ?></td>
			<td><?php echo $jurnal_kas_saldo_akhir_bulan->debet ?></td>
			<td><?php echo $jurnal_kas_saldo_akhir_bulan->kredit ?></td>
			<td><?php echo $jurnal_kas_saldo_akhir_bulan->saldo ?></td>
			<td style="text-align:center" width="200px">
				<?php 
				echo anchor(site_url('jurnal_kas_saldo_akhir_bulan/read/'.$jurnal_kas_saldo_akhir_bulan->id),'Read'); 
				echo ' | '; 
				echo anchor(site_url('jurnal_kas_saldo_akhir_bulan/update/'.$jurnal_kas_saldo_akhir_bulan->id),'Update'); 
				echo ' | '; 
				echo anchor(site_url('jurnal_kas_saldo_akhir_bulan/delete/'.$jurnal_kas_saldo_akhir_bulan->id),'Delete','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'); 
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