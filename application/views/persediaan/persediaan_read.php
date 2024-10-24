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
        <h2 style="margin-top:0px">Persediaan Read</h2>
        <table class="table">
	    <tr><td>Id</td><td><?php echo $id; ?></td></tr>
	    <tr><td>Tanggal</td><td><?php echo $tanggal; ?></td></tr>
	    <tr><td>Kode</td><td><?php echo $kode; ?></td></tr>
	    <tr><td>Namabarang</td><td><?php echo $namabarang; ?></td></tr>
	    <tr><td>Satuan</td><td><?php echo $satuan; ?></td></tr>
	    <tr><td>Hpp</td><td><?php echo $hpp; ?></td></tr>
	    <tr><td>Sa</td><td><?php echo $sa; ?></td></tr>
	    <tr><td>Spop</td><td><?php echo $spop; ?></td></tr>
	    <tr><td>Beli</td><td><?php echo $beli; ?></td></tr>
	    <tr><td>Tuj</td><td><?php echo $tuj; ?></td></tr>
	    <tr><td>Tgl Keluar</td><td><?php echo $tgl_keluar; ?></td></tr>
	    <tr><td>Sekret</td><td><?php echo $sekret; ?></td></tr>
	    <tr><td>Cetak</td><td><?php echo $cetak; ?></td></tr>
	    <tr><td>Grafikita</td><td><?php echo $grafikita; ?></td></tr>
	    <tr><td>Dinas Umum</td><td><?php echo $dinas_umum; ?></td></tr>
	    <tr><td>Atk Rsud</td><td><?php echo $atk_rsud; ?></td></tr>
	    <tr><td>Ppbmp Kbs</td><td><?php echo $ppbmp_kbs; ?></td></tr>
	    <tr><td>Kbs</td><td><?php echo $kbs; ?></td></tr>
	    <tr><td>Ppbmp</td><td><?php echo $ppbmp; ?></td></tr>
	    <tr><td>Medis</td><td><?php echo $medis; ?></td></tr>
	    <tr><td>Siiplah Bosda</td><td><?php echo $siiplah_bosda; ?></td></tr>
	    <tr><td>Sembako</td><td><?php echo $sembako; ?></td></tr>
	    <tr><td>Fc Gose</td><td><?php echo $fc_gose; ?></td></tr>
	    <tr><td>Fc Manding</td><td><?php echo $fc_manding; ?></td></tr>
	    <tr><td>Fc Psamya</td><td><?php echo $fc_psamya; ?></td></tr>
	    <tr><td>Total 10</td><td><?php echo $total_10; ?></td></tr>
	    <tr><td>Nilai Persediaan</td><td><?php echo $nilai_persediaan; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('persediaan') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>