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
        <h2 style="margin-top:0px">Sys_unit_produk <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Uuid Unit <?php echo form_error('uuid_unit') ?></label>
            <input type="text" class="form-control" name="uuid_unit" id="uuid_unit" placeholder="Uuid Unit" value="<?php echo $uuid_unit; ?>" />
        </div>
	    <div class="form-group">
            <label for="kode_unit">Kode Unit <?php echo form_error('kode_unit') ?></label>
            <textarea class="form-control" rows="3" name="kode_unit" id="kode_unit" placeholder="Kode Unit"><?php echo $kode_unit; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="nama_unit">Nama Unit <?php echo form_error('nama_unit') ?></label>
            <textarea class="form-control" rows="3" name="nama_unit" id="nama_unit" placeholder="Nama Unit"><?php echo $nama_unit; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="datetime">Tgl Transaksi <?php echo form_error('tgl_transaksi') ?></label>
            <input type="text" class="form-control" name="tgl_transaksi" id="tgl_transaksi" placeholder="Tgl Transaksi" value="<?php echo $tgl_transaksi; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Uuid Barang <?php echo form_error('uuid_barang') ?></label>
            <input type="text" class="form-control" name="uuid_barang" id="uuid_barang" placeholder="Uuid Barang" value="<?php echo $uuid_barang; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Kode Barang <?php echo form_error('kode_barang') ?></label>
            <input type="text" class="form-control" name="kode_barang" id="kode_barang" placeholder="Kode Barang" value="<?php echo $kode_barang; ?>" />
        </div>
	    <div class="form-group">
            <label for="nama_barang">Nama Barang <?php echo form_error('nama_barang') ?></label>
            <textarea class="form-control" rows="3" name="nama_barang" id="nama_barang" placeholder="Nama Barang"><?php echo $nama_barang; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="double">Jumlah Produksi <?php echo form_error('jumlah_produksi') ?></label>
            <input type="text" class="form-control" name="jumlah_produksi" id="jumlah_produksi" placeholder="Jumlah Produksi" value="<?php echo $jumlah_produksi; ?>" />
        </div>
	    <div class="form-group">
            <label for="satuan">Satuan <?php echo form_error('satuan') ?></label>
            <textarea class="form-control" rows="3" name="satuan" id="satuan" placeholder="Satuan"><?php echo $satuan; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="double">Harga Satuan <?php echo form_error('harga_satuan') ?></label>
            <input type="text" class="form-control" name="harga_satuan" id="harga_satuan" placeholder="Harga Satuan" value="<?php echo $harga_satuan; ?>" />
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('sys_unit_produk') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>