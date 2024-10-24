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
        <h2 style="margin-top:0px">Tbl_penjualan <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="datetime">Tgl Input <?php echo form_error('tgl_input') ?></label>
            <input type="text" class="form-control" name="tgl_input" id="tgl_input" placeholder="Tgl Input" value="<?php echo $tgl_input; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Nmrpesan <?php echo form_error('nmrpesan') ?></label>
            <input type="text" class="form-control" name="nmrpesan" id="nmrpesan" placeholder="Nmrpesan" value="<?php echo $nmrpesan; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Nmrkirim <?php echo form_error('nmrkirim') ?></label>
            <input type="text" class="form-control" name="nmrkirim" id="nmrkirim" placeholder="Nmrkirim" value="<?php echo $nmrkirim; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Konsumen Id <?php echo form_error('konsumen_id') ?></label>
            <input type="text" class="form-control" name="konsumen_id" id="konsumen_id" placeholder="Konsumen Id" value="<?php echo $konsumen_id; ?>" />
        </div>
	    <div class="form-group">
            <label for="konsumen_nama">Konsumen Nama <?php echo form_error('konsumen_nama') ?></label>
            <textarea class="form-control" rows="3" name="konsumen_nama" id="konsumen_nama" placeholder="Konsumen Nama"><?php echo $konsumen_nama; ?></textarea>
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
            <label for="int">Unit <?php echo form_error('unit') ?></label>
            <input type="text" class="form-control" name="unit" id="unit" placeholder="Unit" value="<?php echo $unit; ?>" />
        </div>
	    <div class="form-group">
            <label for="satuan">Satuan <?php echo form_error('satuan') ?></label>
            <textarea class="form-control" rows="3" name="satuan" id="satuan" placeholder="Satuan"><?php echo $satuan; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="double">Harga Satuan <?php echo form_error('harga_satuan') ?></label>
            <input type="text" class="form-control" name="harga_satuan" id="harga_satuan" placeholder="Harga Satuan" value="<?php echo $harga_satuan; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Jumlah <?php echo form_error('jumlah') ?></label>
            <input type="text" class="form-control" name="jumlah" id="jumlah" placeholder="Jumlah" value="<?php echo $jumlah; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Umpphpsl22 <?php echo form_error('umpphpsl22') ?></label>
            <input type="text" class="form-control" name="umpphpsl22" id="umpphpsl22" placeholder="Umpphpsl22" value="<?php echo $umpphpsl22; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Piutang <?php echo form_error('piutang') ?></label>
            <input type="text" class="form-control" name="piutang" id="piutang" placeholder="Piutang" value="<?php echo $piutang; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Penjualandpp <?php echo form_error('penjualandpp') ?></label>
            <input type="text" class="form-control" name="penjualandpp" id="penjualandpp" placeholder="Penjualandpp" value="<?php echo $penjualandpp; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Utangppn <?php echo form_error('utangppn') ?></label>
            <input type="text" class="form-control" name="utangppn" id="utangppn" placeholder="Utangppn" value="<?php echo $utangppn; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Id Usr <?php echo form_error('id_usr') ?></label>
            <input type="text" class="form-control" name="id_usr" id="id_usr" placeholder="Id Usr" value="<?php echo $id_usr; ?>" />
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('tbl_penjualan') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>