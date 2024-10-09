<!doctype html>
<html>
    <head>
        <title>harviacode.com - codeigniter crud generator</title>
        <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>"/>
        <style>
            .word-table {
                border:1px solid black !important; 
                border-collapse: collapse !important;
                width: 100%;
            }
            .word-table tr th, .word-table tr td{
                border:1px solid black !important; 
                padding: 5px 10px;
            }
        </style>
    </head>
    <body>
        <h2>Tbl_stok_barang List</h2>
        <table class="word-table" style="margin-bottom: 10px">
            <tr>
                <th>No</th>
		<th>Uuid Stock</th>
		<th>Date Input</th>
		<th>Id User Input</th>
		<th>Id Tbl Produk</th>
		<th>Id Tbl Company Cetak</th>
		<th>Id Tbl Finishing</th>
		<th>Id Cover</th>
		<th>Jumlah Produk</th>
		<th>Halaman</th>
		<th>Keterangan</th>
		
            </tr><?php
            foreach ($tbl_stok_barang_data as $tbl_stok_barang)
            {
                ?>
                <tr>
		      <td><?php echo ++$start ?></td>
		      <td><?php echo $tbl_stok_barang->uuid_stock ?></td>
		      <td><?php echo $tbl_stok_barang->date_input ?></td>
		      <td><?php echo $tbl_stok_barang->id_user_input ?></td>
		      <td><?php echo $tbl_stok_barang->id_tbl_produk ?></td>
		      <td><?php echo $tbl_stok_barang->id_tbl_company_cetak ?></td>
		      <td><?php echo $tbl_stok_barang->id_tbl_finishing ?></td>
		      <td><?php echo $tbl_stok_barang->id_cover ?></td>
		      <td><?php echo $tbl_stok_barang->jumlah_produk ?></td>
		      <td><?php echo $tbl_stok_barang->halaman ?></td>
		      <td><?php echo $tbl_stok_barang->keterangan ?></td>	
                </tr>
                <?php
            }
            ?>
        </table>
    </body>
</html>