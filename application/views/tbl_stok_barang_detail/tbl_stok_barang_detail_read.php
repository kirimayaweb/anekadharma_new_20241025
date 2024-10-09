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
        <h2 style="margin-top:0px">Tbl_stok_barang_detail Read</h2>
        <table class="table">
	    <tr><td>Uuid Stock</td><td><?php echo $uuid_stock; ?></td></tr>
	    <tr><td>Date Input</td><td><?php echo $date_input; ?></td></tr>
	    <tr><td>Id Trans Cetak</td><td><?php echo $id_trans_cetak; ?></td></tr>
	    <tr><td>Uuid Trans Cetak</td><td><?php echo $uuid_trans_cetak; ?></td></tr>
	    <tr><td>Id User Input</td><td><?php echo $id_user_input; ?></td></tr>
	    <tr><td>Id Tbl Produk</td><td><?php echo $id_tbl_produk; ?></td></tr>
	    <tr><td>Status Produk</td><td><?php echo $status_produk; ?></td></tr>
	    <tr><td>Id Tbl Company Cetak</td><td><?php echo $id_tbl_company_cetak; ?></td></tr>
	    <tr><td>Id User Processing Cetak</td><td><?php echo $id_user_processing_cetak; ?></td></tr>
	    <tr><td>Id Cover</td><td><?php echo $id_cover; ?></td></tr>
	    <tr><td>Date Mulai Cetak</td><td><?php echo $date_mulai_cetak; ?></td></tr>
	    <tr><td>Date Selesai Cetak</td><td><?php echo $date_selesai_cetak; ?></td></tr>
	    <tr><td>Id Tbl Finishing</td><td><?php echo $id_tbl_finishing; ?></td></tr>
	    <tr><td>Id User Processing Finishing</td><td><?php echo $id_user_processing_finishing; ?></td></tr>
	    <tr><td>Date Mulai Finishing</td><td><?php echo $date_mulai_finishing; ?></td></tr>
	    <tr><td>Date Selesai Finishing</td><td><?php echo $date_selesai_finishing; ?></td></tr>
	    <tr><td>Id Gudang</td><td><?php echo $id_gudang; ?></td></tr>
	    <tr><td>Id User Processing Gudang</td><td><?php echo $id_user_processing_gudang; ?></td></tr>
	    <tr><td>Date Masuk Gudang</td><td><?php echo $date_masuk_gudang; ?></td></tr>
	    <tr><td>Date Keluar Gudang</td><td><?php echo $date_keluar_gudang; ?></td></tr>
	    <tr><td>Jumlah Produk</td><td><?php echo $jumlah_produk; ?></td></tr>
	    <tr><td>Halaman</td><td><?php echo $halaman; ?></td></tr>
	    <tr><td>Keterangan</td><td><?php echo $keterangan; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('tbl_stok_barang_detail') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>