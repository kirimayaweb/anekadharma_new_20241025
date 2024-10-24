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
        <h2 style="margin-top:0px">Persediaan <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Tanggal <?php echo form_error('tanggal') ?></label>
            <input type="text" class="form-control" name="tanggal" id="tanggal" placeholder="Tanggal" value="<?php echo $tanggal; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar"> Kode <?php echo form_error(' kode') ?></label>
            <input type="text" class="form-control" name=" kode" id=" kode" placeholder=" Kode" value="<?php echo $ kode; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Namabarang <?php echo form_error('namabarang') ?></label>
            <input type="text" class="form-control" name="namabarang" id="namabarang" placeholder="Namabarang" value="<?php echo $namabarang; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Sa <?php echo form_error('sa') ?></label>
            <input type="text" class="form-control" name="sa" id="sa" placeholder="Sa" value="<?php echo $sa; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar"> Hpp <?php echo form_error(' hpp') ?></label>
            <input type="text" class="form-control" name=" hpp" id=" hpp" placeholder=" Hpp" value="<?php echo $ hpp; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar"> Sa <?php echo form_error(' sa') ?></label>
            <input type="text" class="form-control" name=" sa" id=" sa" placeholder=" Sa" value="<?php echo $ sa; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Spop <?php echo form_error('spop') ?></label>
            <input type="text" class="form-control" name="spop" id="spop" placeholder="Spop" value="<?php echo $spop; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Beli <?php echo form_error('beli') ?></label>
            <input type="text" class="form-control" name="beli" id="beli" placeholder="Beli" value="<?php echo $beli; ?>" />
        </div>
	    <div class="form-group">
            <label for="int"> Tuj <?php echo form_error(' tuj') ?></label>
            <input type="text" class="form-control" name=" tuj" id=" tuj" placeholder=" Tuj" value="<?php echo $ tuj; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Tgl Keluar <?php echo form_error('tgl_keluar') ?></label>
            <input type="text" class="form-control" name="tgl_keluar" id="tgl_keluar" placeholder="Tgl Keluar" value="<?php echo $tgl_keluar; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Sekret <?php echo form_error('sekret') ?></label>
            <input type="text" class="form-control" name="sekret" id="sekret" placeholder="Sekret" value="<?php echo $sekret; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Cetak <?php echo form_error('cetak') ?></label>
            <input type="text" class="form-control" name="cetak" id="cetak" placeholder="Cetak" value="<?php echo $cetak; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Grafikita <?php echo form_error('grafikita') ?></label>
            <input type="text" class="form-control" name="grafikita" id="grafikita" placeholder="Grafikita" value="<?php echo $grafikita; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Dinas Umum <?php echo form_error('dinas_umum') ?></label>
            <input type="text" class="form-control" name="dinas_umum" id="dinas_umum" placeholder="Dinas Umum" value="<?php echo $dinas_umum; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Atk Rsud <?php echo form_error('atk_rsud') ?></label>
            <input type="text" class="form-control" name="atk_rsud" id="atk_rsud" placeholder="Atk Rsud" value="<?php echo $atk_rsud; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Ppbmp Kbs <?php echo form_error('ppbmp_kbs') ?></label>
            <input type="text" class="form-control" name="ppbmp_kbs" id="ppbmp_kbs" placeholder="Ppbmp Kbs" value="<?php echo $ppbmp_kbs; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Kbs <?php echo form_error('kbs') ?></label>
            <input type="text" class="form-control" name="kbs" id="kbs" placeholder="Kbs" value="<?php echo $kbs; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Ppbmp <?php echo form_error('ppbmp') ?></label>
            <input type="text" class="form-control" name="ppbmp" id="ppbmp" placeholder="Ppbmp" value="<?php echo $ppbmp; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Medis <?php echo form_error('medis') ?></label>
            <input type="text" class="form-control" name="medis" id="medis" placeholder="Medis" value="<?php echo $medis; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Siiplah Bosda <?php echo form_error('siiplah_bosda') ?></label>
            <input type="text" class="form-control" name="siiplah_bosda" id="siiplah_bosda" placeholder="Siiplah Bosda" value="<?php echo $siiplah_bosda; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Sembako <?php echo form_error('sembako') ?></label>
            <input type="text" class="form-control" name="sembako" id="sembako" placeholder="Sembako" value="<?php echo $sembako; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Fc Gose <?php echo form_error('fc_gose') ?></label>
            <input type="text" class="form-control" name="fc_gose" id="fc_gose" placeholder="Fc Gose" value="<?php echo $fc_gose; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Fc Manding <?php echo form_error('fc_manding') ?></label>
            <input type="text" class="form-control" name="fc_manding" id="fc_manding" placeholder="Fc Manding" value="<?php echo $fc_manding; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Fc Psamya <?php echo form_error('fc_psamya') ?></label>
            <input type="text" class="form-control" name="fc_psamya" id="fc_psamya" placeholder="Fc Psamya" value="<?php echo $fc_psamya; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Total 10 <?php echo form_error('total_10') ?></label>
            <input type="text" class="form-control" name="total_10" id="total_10" placeholder="Total 10" value="<?php echo $total_10; ?>" />
        </div>
	    <div class="form-group">
            <label for="int"> Nilai Persediaan <?php echo form_error(' nilai_persediaan') ?></label>
            <input type="text" class="form-control" name=" nilai_persediaan" id=" nilai_persediaan" placeholder=" Nilai Persediaan" value="<?php echo $ nilai_persediaan; ?>" />
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('persediaan') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>