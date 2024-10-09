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
        <h2 style="margin-top:0px">Tbl_company_finishing Read</h2>
        <table class="table">
	    <tr><td>Uuid Company Finishing</td><td><?php echo $uuid_company_finishing; ?></td></tr>
	    <tr><td>Date Input</td><td><?php echo $date_input; ?></td></tr>
	    <tr><td>Id User Input</td><td><?php echo $id_user_input; ?></td></tr>
	    <tr><td>Nama Perusahaan</td><td><?php echo $nama_perusahaan; ?></td></tr>
	    <tr><td>Alamat Perusahaan</td><td><?php echo $alamat_perusahaan; ?></td></tr>
	    <tr><td>Notelp Perusahaan</td><td><?php echo $notelp_perusahaan; ?></td></tr>
	    <tr><td>Keterangan</td><td><?php echo $keterangan; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('tbl_company_finishing') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>