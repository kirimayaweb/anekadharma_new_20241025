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
        <h2>Tbl_stok_barang_detail List</h2>
        <table class="word-table" style="margin-bottom: 10px">
            <tr>
                <th>No</th>
		<th>Uuid Stock</th>
		<th>Date Input</th>
		<th>Id Trans Cetak</th>
		<th>Uuid Trans Cetak</th>
		<th>Id User Input</th>
		<th>Id Tbl Produk</th>
		<th>Status Produk</th>
		<th>Id Tbl Company Cetak</th>
		<th>Id User Processing Cetak</th>
		<th>Id Cover</th>
		<th>Date Mulai Cetak</th>
		<th>Date Selesai Cetak</th>
		<th>Id Tbl Finishing</th>
		<th>Id User Processing Finishing</th>
		<th>Date Mulai Finishing</th>
		<th>Date Selesai Finishing</th>
		<th>Id Gudang</th>
		<th>Id User Processing Gudang</th>
		<th>Date Masuk Gudang</th>
		<th>Date Keluar Gudang</th>
		<th>Jumlah Produk</th>
		<th>Halaman</th>
		<th>Keterangan</th>
		
            </tr><?php
            foreach ($tbl_stok_barang_detail_data as $tbl_stok_barang_detail)
            {
                ?>
                <tr>
		      <td><?php echo ++$start ?></td>
		      <td><?php echo $tbl_stok_barang_detail->uuid_stock ?></td>
		      <td><?php echo $tbl_stok_barang_detail->date_input ?></td>
		      <td><?php echo $tbl_stok_barang_detail->id_trans_cetak ?></td>
		      <td><?php echo $tbl_stok_barang_detail->uuid_trans_cetak ?></td>
		      <td><?php echo $tbl_stok_barang_detail->id_user_input ?></td>
		      <td><?php echo $tbl_stok_barang_detail->id_tbl_produk ?></td>
		      <td><?php echo $tbl_stok_barang_detail->status_produk ?></td>
		      <td><?php echo $tbl_stok_barang_detail->id_tbl_company_cetak ?></td>
		      <td><?php echo $tbl_stok_barang_detail->id_user_processing_cetak ?></td>
		      <td><?php echo $tbl_stok_barang_detail->id_cover ?></td>
		      <td><?php echo $tbl_stok_barang_detail->date_mulai_cetak ?></td>
		      <td><?php echo $tbl_stok_barang_detail->date_selesai_cetak ?></td>
		      <td><?php echo $tbl_stok_barang_detail->id_tbl_finishing ?></td>
		      <td><?php echo $tbl_stok_barang_detail->id_user_processing_finishing ?></td>
		      <td><?php echo $tbl_stok_barang_detail->date_mulai_finishing ?></td>
		      <td><?php echo $tbl_stok_barang_detail->date_selesai_finishing ?></td>
		      <td><?php echo $tbl_stok_barang_detail->id_gudang ?></td>
		      <td><?php echo $tbl_stok_barang_detail->id_user_processing_gudang ?></td>
		      <td><?php echo $tbl_stok_barang_detail->date_masuk_gudang ?></td>
		      <td><?php echo $tbl_stok_barang_detail->date_keluar_gudang ?></td>
		      <td><?php echo $tbl_stok_barang_detail->jumlah_produk ?></td>
		      <td><?php echo $tbl_stok_barang_detail->halaman ?></td>
		      <td><?php echo $tbl_stok_barang_detail->keterangan ?></td>	
                </tr>
                <?php
            }
            ?>
        </table>
    </body>
</html>