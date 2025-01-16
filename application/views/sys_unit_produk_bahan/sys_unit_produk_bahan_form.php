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
        <h2 style="margin-top:0px">Sys_unit_produk_bahan <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Uuid Unit <?php echo form_error('uuid_unit') ?></label>
            <input type="text" class="form-control" name="uuid_unit" id="uuid_unit" placeholder="Uuid Unit" value="<?php echo $uuid_unit; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Uuid Persediaan <?php echo form_error('uuid_persediaan') ?></label>
            <input type="text" class="form-control" name="uuid_persediaan" id="uuid_persediaan" placeholder="Uuid Persediaan" value="<?php echo $uuid_persediaan; ?>" />
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
            <label for="varchar">Uuid Produk <?php echo form_error('uuid_produk') ?></label>
            <input type="text" class="form-control" name="uuid_produk" id="uuid_produk" placeholder="Uuid Produk" value="<?php echo $uuid_produk; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Kode Barang Bahan <?php echo form_error('kode_barang_bahan') ?></label>
            <input type="text" class="form-control" name="kode_barang_bahan" id="kode_barang_bahan" placeholder="Kode Barang Bahan" value="<?php echo $kode_barang_bahan; ?>" />
        </div>
	    <div class="form-group">
            <label for="nama_barang_bahan">Nama Barang Bahan <?php echo form_error('nama_barang_bahan') ?></label>
            <textarea class="form-control" rows="3" name="nama_barang_bahan" id="nama_barang_bahan" placeholder="Nama Barang Bahan"><?php echo $nama_barang_bahan; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="double">Jumlah Bahan <?php echo form_error('jumlah_bahan') ?></label>
            <input type="text" class="form-control" name="jumlah_bahan" id="jumlah_bahan" placeholder="Jumlah Bahan" value="<?php echo $jumlah_bahan; ?>" />
        </div>
	    <div class="form-group">
            <label for="satuan_bahan">Satuan Bahan <?php echo form_error('satuan_bahan') ?></label>
            <textarea class="form-control" rows="3" name="satuan_bahan" id="satuan_bahan" placeholder="Satuan Bahan"><?php echo $satuan_bahan; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="double">Harga Satuan Bahan <?php echo form_error('harga_satuan_bahan') ?></label>
            <input type="text" class="form-control" name="harga_satuan_bahan" id="harga_satuan_bahan" placeholder="Harga Satuan Bahan" value="<?php echo $harga_satuan_bahan; ?>" />
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('sys_unit_produk_bahan') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>