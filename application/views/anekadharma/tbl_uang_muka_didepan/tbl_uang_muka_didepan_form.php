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
        <h2 style="margin-top:0px">Tbl_uang_muka_didepan <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Uuid Uang Muka Didepan <?php echo form_error('uuid_uang_muka_didepan') ?></label>
            <input type="text" class="form-control" name="uuid_uang_muka_didepan" id="uuid_uang_muka_didepan" placeholder="Uuid Uang Muka Didepan" value="<?php echo $uuid_uang_muka_didepan; ?>" />
        </div>
	    <div class="form-group">
            <label for="datetime">Tgl Transaksi <?php echo form_error('tgl_transaksi') ?></label>
            <input type="text" class="form-control" name="tgl_transaksi" id="tgl_transaksi" placeholder="Tgl Transaksi" value="<?php echo $tgl_transaksi; ?>" />
        </div>
	    <div class="form-group">
            <label for="kode">Kode <?php echo form_error('kode') ?></label>
            <textarea class="form-control" rows="3" name="kode" id="kode" placeholder="Kode"><?php echo $kode; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="dari">Dari <?php echo form_error('dari') ?></label>
            <textarea class="form-control" rows="3" name="dari" id="dari" placeholder="Dari"><?php echo $dari; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="uraian">Uraian <?php echo form_error('uraian') ?></label>
            <textarea class="form-control" rows="3" name="uraian" id="uraian" placeholder="Uraian"><?php echo $uraian; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="double">Nominal <?php echo form_error('nominal') ?></label>
            <input type="text" class="form-control" name="nominal" id="nominal" placeholder="Nominal" value="<?php echo $nominal; ?>" />
        </div>
	    <div class="form-group">
            <label for="bank">Bank <?php echo form_error('bank') ?></label>
            <textarea class="form-control" rows="3" name="bank" id="bank" placeholder="Bank"><?php echo $bank; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="nmr_rekening">Nmr Rekening <?php echo form_error('nmr_rekening') ?></label>
            <textarea class="form-control" rows="3" name="nmr_rekening" id="nmr_rekening" placeholder="Nmr Rekening"><?php echo $nmr_rekening; ?></textarea>
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('tbl_uang_muka_didepan') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>