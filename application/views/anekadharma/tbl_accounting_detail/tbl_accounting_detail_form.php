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
        <h2 style="margin-top:0px">Tbl_accounting_detail <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="datetime">Date Input <?php echo form_error('date_input') ?></label>
            <input type="text" class="form-control" name="date_input" id="date_input" placeholder="Date Input" value="<?php echo $date_input; ?>" />
        </div>
	    <div class="form-group">
            <label for="datetime">Date Transaksi <?php echo form_error('date_transaksi') ?></label>
            <input type="text" class="form-control" name="date_transaksi" id="date_transaksi" placeholder="Date Transaksi" value="<?php echo $date_transaksi; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Tahun Transaksi <?php echo form_error('tahun_transaksi') ?></label>
            <input type="text" class="form-control" name="tahun_transaksi" id="tahun_transaksi" placeholder="Tahun Transaksi" value="<?php echo $tahun_transaksi; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Bulan Transaksi <?php echo form_error('bulan_transaksi') ?></label>
            <input type="text" class="form-control" name="bulan_transaksi" id="bulan_transaksi" placeholder="Bulan Transaksi" value="<?php echo $bulan_transaksi; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Uuid Accounting <?php echo form_error('uuid_accounting') ?></label>
            <input type="text" class="form-control" name="uuid_accounting" id="uuid_accounting" placeholder="Uuid Accounting" value="<?php echo $uuid_accounting; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Uuid Group <?php echo form_error('uuid_group') ?></label>
            <input type="text" class="form-control" name="uuid_group" id="uuid_group" placeholder="Uuid Group" value="<?php echo $uuid_group; ?>" />
        </div>
	    <div class="form-group">
            <label for="nama_group">Nama Group <?php echo form_error('nama_group') ?></label>
            <textarea class="form-control" rows="3" name="nama_group" id="nama_group" placeholder="Nama Group"><?php echo $nama_group; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="detail_transaksi">Detail Transaksi <?php echo form_error('detail_transaksi') ?></label>
            <textarea class="form-control" rows="3" name="detail_transaksi" id="detail_transaksi" placeholder="Detail Transaksi"><?php echo $detail_transaksi; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="double">Nominal Transaksi <?php echo form_error('nominal_transaksi') ?></label>
            <input type="text" class="form-control" name="nominal_transaksi" id="nominal_transaksi" placeholder="Nominal Transaksi" value="<?php echo $nominal_transaksi; ?>" />
        </div>
	    <div class="form-group">
            <label for="keterangan">Keterangan <?php echo form_error('keterangan') ?></label>
            <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"><?php echo $keterangan; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="int">Id Usr <?php echo form_error('id_usr') ?></label>
            <input type="text" class="form-control" name="id_usr" id="id_usr" placeholder="Id Usr" value="<?php echo $id_usr; ?>" />
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('tbl_accounting_detail') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>