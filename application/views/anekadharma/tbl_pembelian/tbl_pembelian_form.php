<!doctype html>
<html>

<head>
    <title>harviacode.com - codeigniter crud generator</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" />
    <style>
        body {
            padding: 15px;
        }
    </style>
</head>

<body>
    <h2 style="margin-top:0px">Tbl_pembelian <?php echo $button ?></h2>
    <form action="<?php echo $action; ?>" method="post">
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
            <label for="int">Nmrbpb <?php echo form_error('nmrbpb') ?></label>
            <input type="text" class="form-control" name="nmrbpb" id="nmrbpb" placeholder="Nmrbpb" value="<?php echo $nmrbpb; ?>" />
        </div>
        <div class="form-group">
            <label for="int">Spop <?php echo form_error('spop') ?></label>
            <input type="text" class="form-control" name="spop" id="spop" placeholder="Spop" value="<?php echo $spop; ?>" />
        </div>
        <div class="form-group">
            <label for="int">Supplier Kode <?php echo form_error('supplier_kode') ?></label>
            <input type="text" class="form-control" name="supplier_kode" id="supplier_kode" placeholder="Supplier Kode" value="<?php echo $supplier_kode; ?>" />
        </div>
        <div class="form-group">
            <label for="supplier_nama">Supplier Nama <?php echo form_error('supplier_nama') ?></label>
            <textarea class="form-control" rows="3" name="supplier_nama" id="supplier_nama" placeholder="Supplier Nama"><?php echo $supplier_nama; ?></textarea>
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
            <label for="konsumen">Konsumen <?php echo form_error('konsumen') ?></label>
            <textarea class="form-control" rows="3" name="konsumen" id="konsumen" placeholder="Konsumen"><?php echo $konsumen; ?></textarea>
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
        <input type="hidden" name="id" value="<?php echo $id; ?>" />
        <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
        <a href="<?php echo site_url('tbl_pembelian') ?>" class="btn btn-default">Cancel</a>
    </form>
</body>

</html>