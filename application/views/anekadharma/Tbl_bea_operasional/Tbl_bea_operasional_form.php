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
    <h2 style="margin-top:0px">Tbl_kas_kecil <?php echo $button ?></h2>
    <form action="<?php echo $action; ?>" method="post">
        <div class="form-group">
            <label for="varchar">Uuid Kas Kecil <?php echo form_error('uuid_kas_kecil') ?></label>
            <input type="text" class="form-control" name="uuid_kas_kecil" id="uuid_kas_kecil" placeholder="Uuid Kas Kecil" value="<?php echo $uuid_kas_kecil; ?>" />
        </div>
        <div class="form-group">
            <label for="date">Tanggal <?php echo form_error('tanggal') ?></label>
            <input type="text" class="form-control" name="tanggal" id="tanggal" placeholder="Tanggal" value="<?php echo $tanggal; ?>" />
        </div>
        <div class="form-group">
            <label for="unit">Unit <?php echo form_error('unit') ?></label>
            <textarea class="form-control" rows="3" name="unit" id="unit" placeholder="Unit"><?php echo $unit; ?></textarea>
        </div>
        <div class="form-group">
            <label for="keterangan">Keterangan <?php echo form_error('keterangan') ?></label>
            <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"><?php echo $keterangan; ?></textarea>
        </div>
        <div class="form-group">
            <label for="double">Debet <?php echo form_error('debet') ?></label>
            <input type="text" class="form-control" name="debet" id="debet" placeholder="Debet" value="<?php echo $debet; ?>" />
        </div>
        <div class="form-group">
            <label for="double">Kredit <?php echo form_error('kredit') ?></label>
            <input type="text" class="form-control" name="kredit" id="kredit" placeholder="Kredit" value="<?php echo $kredit; ?>" />
        </div>
        <div class="form-group">
            <label for="double">Saldo <?php echo form_error('saldo') ?></label>
            <input type="text" class="form-control" name="saldo" id="saldo" placeholder="Saldo" value="<?php echo $saldo; ?>" />
        </div>
        <div class="form-group">
            <label for="int">Id Usr <?php echo form_error('id_usr') ?></label>
            <input type="text" class="form-control" name="id_usr" id="id_usr" placeholder="Id Usr" value="<?php echo $id_usr; ?>" />
        </div>
        <input type="hidden" name="id" value="<?php echo $id; ?>" />
        <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
        <a href="<?php echo site_url('tbl_kas_kecil') ?>" class="btn btn-default">Cancel</a>
    </form>
</body>

</html>