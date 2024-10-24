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
        <h2 style="margin-top:0px">Tbl_pembelian_pecah_satuan Read</h2>
        <table class="table">
	    <tr><td>Proses Input</td><td><?php echo $proses_input; ?></td></tr>
	    <tr><td>Date Input</td><td><?php echo $date_input; ?></td></tr>
	    <tr><td>Uuid Pembelian</td><td><?php echo $uuid_pembelian; ?></td></tr>
	    <tr><td>Uuid Barang</td><td><?php echo $uuid_barang; ?></td></tr>
	    <tr><td>Tgl Po</td><td><?php echo $tgl_po; ?></td></tr>
	    <tr><td>Nmrsj</td><td><?php echo $nmrsj; ?></td></tr>
	    <tr><td>Nmrfakturkwitansi</td><td><?php echo $nmrfakturkwitansi; ?></td></tr>
	    <tr><td>Nmrbpb</td><td><?php echo $nmrbpb; ?></td></tr>
	    <tr><td>Uuid Spop</td><td><?php echo $uuid_spop; ?></td></tr>
	    <tr><td>Spop</td><td><?php echo $spop; ?></td></tr>
	    <tr><td>Status Spop</td><td><?php echo $status_spop; ?></td></tr>
	    <tr><td>Uuid Supplier</td><td><?php echo $uuid_supplier; ?></td></tr>
	    <tr><td>Supplier Kode</td><td><?php echo $supplier_kode; ?></td></tr>
	    <tr><td>Supplier Nama</td><td><?php echo $supplier_nama; ?></td></tr>
	    <tr><td>Kode Barang</td><td><?php echo $kode_barang; ?></td></tr>
	    <tr><td>Uraian</td><td><?php echo $uraian; ?></td></tr>
	    <tr><td>Jumlah</td><td><?php echo $jumlah; ?></td></tr>
	    <tr><td>Satuan</td><td><?php echo $satuan; ?></td></tr>
	    <tr><td>Uuid Konsumen</td><td><?php echo $uuid_konsumen; ?></td></tr>
	    <tr><td>Konsumen</td><td><?php echo $konsumen; ?></td></tr>
	    <tr><td>Uuid Gudang</td><td><?php echo $uuid_gudang; ?></td></tr>
	    <tr><td>Nama Gudang</td><td><?php echo $nama_gudang; ?></td></tr>
	    <tr><td>Harga Satuan</td><td><?php echo $harga_satuan; ?></td></tr>
	    <tr><td>Harga Total</td><td><?php echo $harga_total; ?></td></tr>
	    <tr><td>Statuslu</td><td><?php echo $statuslu; ?></td></tr>
	    <tr><td>Kas Bank</td><td><?php echo $kas_bank; ?></td></tr>
	    <tr><td>Tgl Bayar</td><td><?php echo $tgl_bayar; ?></td></tr>
	    <tr><td>Id Usr</td><td><?php echo $id_usr; ?></td></tr>
	    <tr><td>Tgl Pengajuan 1</td><td><?php echo $tgl_pengajuan_1; ?></td></tr>
	    <tr><td>Nominal Pengajuan 1</td><td><?php echo $nominal_pengajuan_1; ?></td></tr>
	    <tr><td>Tgl Pengajuan 2</td><td><?php echo $tgl_pengajuan_2; ?></td></tr>
	    <tr><td>Nominal Pengajuan 2</td><td><?php echo $nominal_pengajuan_2; ?></td></tr>
	    <tr><td>Uuid Gudang Baru</td><td><?php echo $uuid_gudang_baru; ?></td></tr>
	    <tr><td>Kode Barang Baru</td><td><?php echo $kode_barang_baru; ?></td></tr>
	    <tr><td>Nama Barang Baru</td><td><?php echo $nama_barang_baru; ?></td></tr>
	    <tr><td>Nominal Bayar Input</td><td><?php echo $nominal_bayar_input; ?></td></tr>
	    <tr><td>Satuan Barang Baru</td><td><?php echo $satuan_barang_baru; ?></td></tr>
	    <tr><td>Harga Satuan Barang Baru</td><td><?php echo $harga_satuan_barang_baru; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('tbl_pembelian_pecah_satuan') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>