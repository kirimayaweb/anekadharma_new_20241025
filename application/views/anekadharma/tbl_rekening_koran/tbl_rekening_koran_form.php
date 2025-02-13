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
        <h2 style="margin-top:0px">Tbl_rekening_koran <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Uuid Rek Koran <?php echo form_error('uuid_rek_koran') ?></label>
            <input type="text" class="form-control" name="uuid_rek_koran" id="uuid_rek_koran" placeholder="Uuid Rek Koran" value="<?php echo $uuid_rek_koran; ?>" />
        </div>
	    <div class="form-group">
            <label for="datetime">Tgl Transaksi <?php echo form_error('tgl_transaksi') ?></label>
            <input type="text" class="form-control" name="tgl_transaksi" id="tgl_transaksi" placeholder="Tgl Transaksi" value="<?php echo $tgl_transaksi; ?>" />
        </div>
	    <div class="form-group">
            <label for="uraian">Uraian <?php echo form_error('uraian') ?></label>
            <textarea class="form-control" rows="3" name="uraian" id="uraian" placeholder="Uraian"><?php echo $uraian; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="decimal">Chq <?php echo form_error('chq') ?></label>
            <input type="text" class="form-control" name="chq" id="chq" placeholder="Chq" value="<?php echo $chq; ?>" />
        </div>
	    <div class="form-group">
            <label for="decimal">Debet <?php echo form_error('debet') ?></label>
            <input type="text" class="form-control" name="debet" id="debet" placeholder="Debet" value="<?php echo $debet; ?>" />
        </div>
	    <div class="form-group">
            <label for="decimal">Kredit <?php echo form_error('kredit') ?></label>
            <input type="text" class="form-control" name="kredit" id="kredit" placeholder="Kredit" value="<?php echo $kredit; ?>" />
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('tbl_rekening_koran') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>