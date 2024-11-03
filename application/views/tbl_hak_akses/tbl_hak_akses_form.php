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
        <h2 style="margin-top:0px">Tbl_hak_akses <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="int">Id User <?php echo form_error('id_user') ?></label>
            <input type="text" class="form-control" name="id_user" id="id_user" placeholder="Id User" value="<?php echo $id_user; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Id User Level <?php echo form_error('id_user_level') ?></label>
            <input type="text" class="form-control" name="id_user_level" id="id_user_level" placeholder="Id User Level" value="<?php echo $id_user_level; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Main Menu <?php echo form_error('main_menu') ?></label>
            <input type="text" class="form-control" name="main_menu" id="main_menu" placeholder="Main Menu" value="<?php echo $main_menu; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Id Menu <?php echo form_error('id_menu') ?></label>
            <input type="text" class="form-control" name="id_menu" id="id_menu" placeholder="Id Menu" value="<?php echo $id_menu; ?>" />
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('tbl_hak_akses') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>