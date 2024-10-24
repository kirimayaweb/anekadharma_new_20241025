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
        <h2 style="margin-top:0px">Tbl_neraca_data List</h2>
        <div class="row" style="margin-bottom: 10px">
            <div class="col-md-4">
                <?php echo anchor(site_url('tbl_neraca_data/create'),'Create', 'class="btn btn-primary"'); ?>
            </div>
            <div class="col-md-4 text-center">
                <div style="margin-top: 8px" id="message">
                    <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
                </div>
            </div>
            <div class="col-md-1 text-right">
            </div>
            <div class="col-md-3 text-right">
                <form action="<?php echo site_url('tbl_neraca_data/index'); ?>" class="form-inline" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                        <span class="input-group-btn">
                            <?php 
                                if ($q <> '')
                                {
                                    ?>
                                    <a href="<?php echo site_url('tbl_neraca_data'); ?>" class="btn btn-default">Reset</a>
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
		<th>Date Input</th>
		<th>Date Transaksi</th>
		<th>Tahun Transaksi</th>
		<th>Bulan Transaksi</th>
		<th>Uuid Data Neraca</th>
		<th>Kas</th>
		<th>Bank</th>
		<th>Piutang Usaha</th>
		<th>Piutang Non Usaha</th>
		<th>Persediaan</th>
		<th>Uang Muka Pajak</th>
		<th>Aktiva Tetap</th>
		<th>Aktiva Tetap Berwujud</th>
		<th>Akumulasi Depresiasi Atb</th>
		<th>Piutang Non Usaha Pihak Ketiga</th>
		<th>Piutang Non Usaha Radio</th>
		<th>Ljpj-taman Gedung Kesenian Gabusan</th>
		<th>Ljpj Kompleks Gedung Kesenian</th>
		<th>Ljpj Radio</th>
		<th>Ljpj Kerjasama Operasi Apotek Dharma Usaha</th>
		<th>Ljpj Peternakan</th>
		<th>Ljpj Kerjasama Adwm</th>
		<th>Ljpj Kerjasama Pdu Cabean Panggungharjo</th>
		<th>Utang Usaha</th>
		<th>Utang Pajak</th>
		<th>Utang Lain Lain</th>
		<th>Utang Afiliasi</th>
		<th>Modal Dasar Dan Penyertaan</th>
		<th>Cadangan Umum</th>
		<th>Laba Bumd Pad</th>
		<th>Laba Rugi Tahun Lalu</th>
		<th>Laba Rugi Tahun Berjalan</th>
		<th>Action</th>
            </tr><?php
            foreach ($tbl_neraca_data_data as $tbl_neraca_data)
            {
                ?>
                <tr>
			<td width="80px"><?php echo ++$start ?></td>
			<td><?php echo $tbl_neraca_data->date_input ?></td>
			<td><?php echo $tbl_neraca_data->date_transaksi ?></td>
			<td><?php echo $tbl_neraca_data->tahun_transaksi ?></td>
			<td><?php echo $tbl_neraca_data->bulan_transaksi ?></td>
			<td><?php echo $tbl_neraca_data->uuid_data_neraca ?></td>
			<td><?php echo $tbl_neraca_data->kas ?></td>
			<td><?php echo $tbl_neraca_data->bank ?></td>
			<td><?php echo $tbl_neraca_data->piutang_usaha ?></td>
			<td><?php echo $tbl_neraca_data->piutang_non_usaha ?></td>
			<td><?php echo $tbl_neraca_data->persediaan ?></td>
			<td><?php echo $tbl_neraca_data->uang_muka_pajak ?></td>
			<td><?php echo $tbl_neraca_data->aktiva_tetap ?></td>
			<td><?php echo $tbl_neraca_data->aktiva_tetap_berwujud ?></td>
			<td><?php echo $tbl_neraca_data->akumulasi_depresiasi_atb ?></td>
			<td><?php echo $tbl_neraca_data->piutang_non_usaha_pihak_ketiga ?></td>
			<td><?php echo $tbl_neraca_data->piutang_non_usaha_radio ?></td>
			<td><?php echo $tbl_neraca_data->ljpj_taman_gedung_kesenian_gabusan ?></td>
			<td><?php echo $tbl_neraca_data->ljpj_kompleks_gedung_kesenian ?></td>
			<td><?php echo $tbl_neraca_data->ljpj_radio ?></td>
			<td><?php echo $tbl_neraca_data->ljpj_kerjasama_operasi_apotek_dharma_usaha ?></td>
			<td><?php echo $tbl_neraca_data->ljpj_peternakan ?></td>
			<td><?php echo $tbl_neraca_data->ljpj_kerjasama_adwm ?></td>
			<td><?php echo $tbl_neraca_data->ljpj_kerjasama_pdu_cabean_panggungharjo ?></td>
			<td><?php echo $tbl_neraca_data->utang_usaha ?></td>
			<td><?php echo $tbl_neraca_data->utang_pajak ?></td>
			<td><?php echo $tbl_neraca_data->utang_lain_lain ?></td>
			<td><?php echo $tbl_neraca_data->utang_afiliasi ?></td>
			<td><?php echo $tbl_neraca_data->modal_dasar_dan_penyertaan ?></td>
			<td><?php echo $tbl_neraca_data->cadangan_umum ?></td>
			<td><?php echo $tbl_neraca_data->laba_bumd_pad ?></td>
			<td><?php echo $tbl_neraca_data->laba_rugi_tahun_lalu ?></td>
			<td><?php echo $tbl_neraca_data->laba_rugi_tahun_berjalan ?></td>
			<td style="text-align:center" width="200px">
				<?php 
				echo anchor(site_url('tbl_neraca_data/read/'.$tbl_neraca_data->id),'Read'); 
				echo ' | '; 
				echo anchor(site_url('tbl_neraca_data/update/'.$tbl_neraca_data->id),'Update'); 
				echo ' | '; 
				echo anchor(site_url('tbl_neraca_data/delete/'.$tbl_neraca_data->id),'Delete','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'); 
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
		<?php echo anchor(site_url('tbl_neraca_data/excel'), 'Excel', 'class="btn btn-primary"'); ?>
	    </div>
            <div class="col-md-6 text-right">
                <?php echo $pagination ?>
            </div>
        </div>
    </body>
</html>