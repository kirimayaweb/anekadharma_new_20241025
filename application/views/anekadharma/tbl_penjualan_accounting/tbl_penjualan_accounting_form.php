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
        <h2 style="margin-top:0px">Tbl_penjualan_accounting <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Uuid Penjualan Proses <?php echo form_error('uuid_penjualan_proses') ?></label>
            <input type="text" class="form-control" name="uuid_penjualan_proses" id="uuid_penjualan_proses" placeholder="Uuid Penjualan Proses" value="<?php echo $uuid_penjualan_proses; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Uuid Penjualan <?php echo form_error('uuid_penjualan') ?></label>
            <input type="text" class="form-control" name="uuid_penjualan" id="uuid_penjualan" placeholder="Uuid Penjualan" value="<?php echo $uuid_penjualan; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Uuid Persediaan <?php echo form_error('uuid_persediaan') ?></label>
            <input type="text" class="form-control" name="uuid_persediaan" id="uuid_persediaan" placeholder="Uuid Persediaan" value="<?php echo $uuid_persediaan; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Id Persediaan Barang <?php echo form_error('id_persediaan_barang') ?></label>
            <input type="text" class="form-control" name="id_persediaan_barang" id="id_persediaan_barang" placeholder="Id Persediaan Barang" value="<?php echo $id_persediaan_barang; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Uuid Barang <?php echo form_error('uuid_barang') ?></label>
            <input type="text" class="form-control" name="uuid_barang" id="uuid_barang" placeholder="Uuid Barang" value="<?php echo $uuid_barang; ?>" />
        </div>
	    <div class="form-group">
            <label for="datetime">Tgl Input <?php echo form_error('tgl_input') ?></label>
            <input type="text" class="form-control" name="tgl_input" id="tgl_input" placeholder="Tgl Input" value="<?php echo $tgl_input; ?>" />
        </div>
	    <div class="form-group">
            <label for="datetime">Tgl Jual <?php echo form_error('tgl_jual') ?></label>
            <input type="text" class="form-control" name="tgl_jual" id="tgl_jual" placeholder="Tgl Jual" value="<?php echo $tgl_jual; ?>" />
        </div>
	    <div class="form-group">
            <label for="nmrpesan">Nmrpesan <?php echo form_error('nmrpesan') ?></label>
            <textarea class="form-control" rows="3" name="nmrpesan" id="nmrpesan" placeholder="Nmrpesan"><?php echo $nmrpesan; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="nmrkirim">Nmrkirim <?php echo form_error('nmrkirim') ?></label>
            <textarea class="form-control" rows="3" name="nmrkirim" id="nmrkirim" placeholder="Nmrkirim"><?php echo $nmrkirim; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="varchar">Uuid Konsumen <?php echo form_error('uuid_konsumen') ?></label>
            <input type="text" class="form-control" name="uuid_konsumen" id="uuid_konsumen" placeholder="Uuid Konsumen" value="<?php echo $uuid_konsumen; ?>" />
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
            <label for="kode_barang">Kode Barang <?php echo form_error('kode_barang') ?></label>
            <textarea class="form-control" rows="3" name="kode_barang" id="kode_barang" placeholder="Kode Barang"><?php echo $kode_barang; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="nama_barang">Nama Barang <?php echo form_error('nama_barang') ?></label>
            <textarea class="form-control" rows="3" name="nama_barang" id="nama_barang" placeholder="Nama Barang"><?php echo $nama_barang; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="varchar">Uuid Unit <?php echo form_error('uuid_unit') ?></label>
            <input type="text" class="form-control" name="uuid_unit" id="uuid_unit" placeholder="Uuid Unit" value="<?php echo $uuid_unit; ?>" />
        </div>
	    <div class="form-group">
            <label for="unit">Unit <?php echo form_error('unit') ?></label>
            <textarea class="form-control" rows="3" name="unit" id="unit" placeholder="Unit"><?php echo $unit; ?></textarea>
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
            <label for="double">Total Nominal <?php echo form_error('total_nominal') ?></label>
            <input type="text" class="form-control" name="total_nominal" id="total_nominal" placeholder="Total Nominal" value="<?php echo $total_nominal; ?>" />
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
            <label for="int">Cetak Bukti Penjualan <?php echo form_error('cetak_bukti_penjualan') ?></label>
            <input type="text" class="form-control" name="cetak_bukti_penjualan" id="cetak_bukti_penjualan" placeholder="Cetak Bukti Penjualan" value="<?php echo $cetak_bukti_penjualan; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Id Usr <?php echo form_error('id_usr') ?></label>
            <input type="text" class="form-control" name="id_usr" id="id_usr" placeholder="Id Usr" value="<?php echo $id_usr; ?>" />
        </div>
	    <div class="form-group">
            <label for="proses_bayar">Proses Bayar <?php echo form_error('proses_bayar') ?></label>
            <textarea class="form-control" rows="3" name="proses_bayar" id="proses_bayar" placeholder="Proses Bayar"><?php echo $proses_bayar; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="datetime">Tgl Bayar Input <?php echo form_error('tgl_bayar_input') ?></label>
            <input type="text" class="form-control" name="tgl_bayar_input" id="tgl_bayar_input" placeholder="Tgl Bayar Input" value="<?php echo $tgl_bayar_input; ?>" />
        </div>
	    <div class="form-group">
            <label for="datetime">Tgl Bayar <?php echo form_error('tgl_bayar') ?></label>
            <input type="text" class="form-control" name="tgl_bayar" id="tgl_bayar" placeholder="Tgl Bayar" value="<?php echo $tgl_bayar; ?>" />
        </div>
	    <div class="form-group">
            <label for="nmr_bukti_bayar">Nmr Bukti Bayar <?php echo form_error('nmr_bukti_bayar') ?></label>
            <textarea class="form-control" rows="3" name="nmr_bukti_bayar" id="nmr_bukti_bayar" placeholder="Nmr Bukti Bayar"><?php echo $nmr_bukti_bayar; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="kode_akun">Kode Akun <?php echo form_error('kode_akun') ?></label>
            <textarea class="form-control" rows="3" name="kode_akun" id="kode_akun" placeholder="Kode Akun"><?php echo $kode_akun; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="proses_transaksi">Proses Transaksi <?php echo form_error('proses_transaksi') ?></label>
            <textarea class="form-control" rows="3" name="proses_transaksi" id="proses_transaksi" placeholder="Proses Transaksi"><?php echo $proses_transaksi; ?></textarea>
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('tbl_penjualan_accounting') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>