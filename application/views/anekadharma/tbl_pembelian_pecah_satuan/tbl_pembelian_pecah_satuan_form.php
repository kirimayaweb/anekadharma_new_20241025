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
        <h2 style="margin-top:0px">Tbl_pembelian_pecah_satuan <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="int">Proses Input <?php echo form_error('proses_input') ?></label>
            <input type="text" class="form-control" name="proses_input" id="proses_input" placeholder="Proses Input" value="<?php echo $proses_input; ?>" />
        </div>
	    <div class="form-group">
            <label for="datetime">Date Input <?php echo form_error('date_input') ?></label>
            <input type="text" class="form-control" name="date_input" id="date_input" placeholder="Date Input" value="<?php echo $date_input; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Uuid Pembelian <?php echo form_error('uuid_pembelian') ?></label>
            <input type="text" class="form-control" name="uuid_pembelian" id="uuid_pembelian" placeholder="Uuid Pembelian" value="<?php echo $uuid_pembelian; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Uuid Barang <?php echo form_error('uuid_barang') ?></label>
            <input type="text" class="form-control" name="uuid_barang" id="uuid_barang" placeholder="Uuid Barang" value="<?php echo $uuid_barang; ?>" />
        </div>
	    <div class="form-group">
            <label for="datetime">Tgl Po <?php echo form_error('tgl_po') ?></label>
            <input type="text" class="form-control" name="tgl_po" id="tgl_po" placeholder="Tgl Po" value="<?php echo $tgl_po; ?>" />
        </div>
	    <div class="form-group">
            <label for="nmrsj">Nmrsj <?php echo form_error('nmrsj') ?></label>
            <textarea class="form-control" rows="3" name="nmrsj" id="nmrsj" placeholder="Nmrsj"><?php echo $nmrsj; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="nmrfakturkwitansi">Nmrfakturkwitansi <?php echo form_error('nmrfakturkwitansi') ?></label>
            <textarea class="form-control" rows="3" name="nmrfakturkwitansi" id="nmrfakturkwitansi" placeholder="Nmrfakturkwitansi"><?php echo $nmrfakturkwitansi; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="nmrbpb">Nmrbpb <?php echo form_error('nmrbpb') ?></label>
            <textarea class="form-control" rows="3" name="nmrbpb" id="nmrbpb" placeholder="Nmrbpb"><?php echo $nmrbpb; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="varchar">Uuid Spop <?php echo form_error('uuid_spop') ?></label>
            <input type="text" class="form-control" name="uuid_spop" id="uuid_spop" placeholder="Uuid Spop" value="<?php echo $uuid_spop; ?>" />
        </div>
	    <div class="form-group">
            <label for="spop">Spop <?php echo form_error('spop') ?></label>
            <textarea class="form-control" rows="3" name="spop" id="spop" placeholder="Spop"><?php echo $spop; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="status_spop">Status Spop <?php echo form_error('status_spop') ?></label>
            <textarea class="form-control" rows="3" name="status_spop" id="status_spop" placeholder="Status Spop"><?php echo $status_spop; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="varchar">Uuid Supplier <?php echo form_error('uuid_supplier') ?></label>
            <input type="text" class="form-control" name="uuid_supplier" id="uuid_supplier" placeholder="Uuid Supplier" value="<?php echo $uuid_supplier; ?>" />
        </div>
	    <div class="form-group">
            <label for="supplier_kode">Supplier Kode <?php echo form_error('supplier_kode') ?></label>
            <textarea class="form-control" rows="3" name="supplier_kode" id="supplier_kode" placeholder="Supplier Kode"><?php echo $supplier_kode; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="supplier_nama">Supplier Nama <?php echo form_error('supplier_nama') ?></label>
            <textarea class="form-control" rows="3" name="supplier_nama" id="supplier_nama" placeholder="Supplier Nama"><?php echo $supplier_nama; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="kode_barang">Kode Barang <?php echo form_error('kode_barang') ?></label>
            <textarea class="form-control" rows="3" name="kode_barang" id="kode_barang" placeholder="Kode Barang"><?php echo $kode_barang; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="uraian">Uraian <?php echo form_error('uraian') ?></label>
            <textarea class="form-control" rows="3" name="uraian" id="uraian" placeholder="Uraian"><?php echo $uraian; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="int">Jumlah <?php echo form_error('jumlah') ?></label>
            <input type="text" class="form-control" name="jumlah" id="jumlah" placeholder="Jumlah" value="<?php echo $jumlah; ?>" />
        </div>
	    <div class="form-group">
            <label for="satuan">Satuan <?php echo form_error('satuan') ?></label>
            <textarea class="form-control" rows="3" name="satuan" id="satuan" placeholder="Satuan"><?php echo $satuan; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="varchar">Uuid Konsumen <?php echo form_error('uuid_konsumen') ?></label>
            <input type="text" class="form-control" name="uuid_konsumen" id="uuid_konsumen" placeholder="Uuid Konsumen" value="<?php echo $uuid_konsumen; ?>" />
        </div>
	    <div class="form-group">
            <label for="konsumen">Konsumen <?php echo form_error('konsumen') ?></label>
            <textarea class="form-control" rows="3" name="konsumen" id="konsumen" placeholder="Konsumen"><?php echo $konsumen; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="varchar">Uuid Gudang <?php echo form_error('uuid_gudang') ?></label>
            <input type="text" class="form-control" name="uuid_gudang" id="uuid_gudang" placeholder="Uuid Gudang" value="<?php echo $uuid_gudang; ?>" />
        </div>
	    <div class="form-group">
            <label for="nama_gudang">Nama Gudang <?php echo form_error('nama_gudang') ?></label>
            <textarea class="form-control" rows="3" name="nama_gudang" id="nama_gudang" placeholder="Nama Gudang"><?php echo $nama_gudang; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="double">Harga Satuan <?php echo form_error('harga_satuan') ?></label>
            <input type="text" class="form-control" name="harga_satuan" id="harga_satuan" placeholder="Harga Satuan" value="<?php echo $harga_satuan; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Harga Total <?php echo form_error('harga_total') ?></label>
            <input type="text" class="form-control" name="harga_total" id="harga_total" placeholder="Harga Total" value="<?php echo $harga_total; ?>" />
        </div>
	    <div class="form-group">
            <label for="statuslu">Statuslu <?php echo form_error('statuslu') ?></label>
            <textarea class="form-control" rows="3" name="statuslu" id="statuslu" placeholder="Statuslu"><?php echo $statuslu; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="kas_bank">Kas Bank <?php echo form_error('kas_bank') ?></label>
            <textarea class="form-control" rows="3" name="kas_bank" id="kas_bank" placeholder="Kas Bank"><?php echo $kas_bank; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="datetime">Tgl Bayar <?php echo form_error('tgl_bayar') ?></label>
            <input type="text" class="form-control" name="tgl_bayar" id="tgl_bayar" placeholder="Tgl Bayar" value="<?php echo $tgl_bayar; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Id Usr <?php echo form_error('id_usr') ?></label>
            <input type="text" class="form-control" name="id_usr" id="id_usr" placeholder="Id Usr" value="<?php echo $id_usr; ?>" />
        </div>
	    <div class="form-group">
            <label for="datetime">Tgl Pengajuan 1 <?php echo form_error('tgl_pengajuan_1') ?></label>
            <input type="text" class="form-control" name="tgl_pengajuan_1" id="tgl_pengajuan_1" placeholder="Tgl Pengajuan 1" value="<?php echo $tgl_pengajuan_1; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Nominal Pengajuan 1 <?php echo form_error('nominal_pengajuan_1') ?></label>
            <input type="text" class="form-control" name="nominal_pengajuan_1" id="nominal_pengajuan_1" placeholder="Nominal Pengajuan 1" value="<?php echo $nominal_pengajuan_1; ?>" />
        </div>
	    <div class="form-group">
            <label for="datetime">Tgl Pengajuan 2 <?php echo form_error('tgl_pengajuan_2') ?></label>
            <input type="text" class="form-control" name="tgl_pengajuan_2" id="tgl_pengajuan_2" placeholder="Tgl Pengajuan 2" value="<?php echo $tgl_pengajuan_2; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Nominal Pengajuan 2 <?php echo form_error('nominal_pengajuan_2') ?></label>
            <input type="text" class="form-control" name="nominal_pengajuan_2" id="nominal_pengajuan_2" placeholder="Nominal Pengajuan 2" value="<?php echo $nominal_pengajuan_2; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Uuid Gudang Baru <?php echo form_error('uuid_gudang_baru') ?></label>
            <input type="text" class="form-control" name="uuid_gudang_baru" id="uuid_gudang_baru" placeholder="Uuid Gudang Baru" value="<?php echo $uuid_gudang_baru; ?>" />
        </div>
	    <div class="form-group">
            <label for="kode_barang_baru">Kode Barang Baru <?php echo form_error('kode_barang_baru') ?></label>
            <textarea class="form-control" rows="3" name="kode_barang_baru" id="kode_barang_baru" placeholder="Kode Barang Baru"><?php echo $kode_barang_baru; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="nama_barang_baru">Nama Barang Baru <?php echo form_error('nama_barang_baru') ?></label>
            <textarea class="form-control" rows="3" name="nama_barang_baru" id="nama_barang_baru" placeholder="Nama Barang Baru"><?php echo $nama_barang_baru; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="double">Nominal Bayar Input <?php echo form_error('nominal_bayar_input') ?></label>
            <input type="text" class="form-control" name="nominal_bayar_input" id="nominal_bayar_input" placeholder="Nominal Bayar Input" value="<?php echo $nominal_bayar_input; ?>" />
        </div>
	    <div class="form-group">
            <label for="satuan_barang_baru">Satuan Barang Baru <?php echo form_error('satuan_barang_baru') ?></label>
            <textarea class="form-control" rows="3" name="satuan_barang_baru" id="satuan_barang_baru" placeholder="Satuan Barang Baru"><?php echo $satuan_barang_baru; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="double">Harga Satuan Barang Baru <?php echo form_error('harga_satuan_barang_baru') ?></label>
            <input type="text" class="form-control" name="harga_satuan_barang_baru" id="harga_satuan_barang_baru" placeholder="Harga Satuan Barang Baru" value="<?php echo $harga_satuan_barang_baru; ?>" />
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('tbl_pembelian_pecah_satuan') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>