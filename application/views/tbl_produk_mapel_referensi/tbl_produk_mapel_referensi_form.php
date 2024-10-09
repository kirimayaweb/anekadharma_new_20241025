<div class="content-wrapper">
    
    <section class="content">
        <div class="box box-warning box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">INPUT DATA TBL_PRODUK_MAPEL_REFERENSI</h3>
            </div>
            <form action="<?php echo $action; ?>" method="post">
            
<table class='table table-bordered>'        

	    <tr><td width='200'>Uuid Produk <?php echo form_error('uuid_produk') ?></td><td><input type="text" class="form-control" name="uuid_produk" id="uuid_produk" placeholder="Uuid Produk" value="<?php echo $uuid_produk; ?>" /></td></tr>
	    
        <tr><td width='200'>Kode Produk <?php echo form_error('kode_produk') ?></td><td> <textarea class="form-control" rows="3" name="kode_produk" id="kode_produk" placeholder="Kode Produk"><?php echo $kode_produk; ?></textarea></td></tr>
	    <tr><td width='200'>Date Input <?php echo form_error('date_input') ?></td><td><input type="text" class="form-control" name="date_input" id="date_input" placeholder="Date Input" value="<?php echo $date_input; ?>" /></td></tr>
	    <tr><td width='200'>Id User Input <?php echo form_error('id_user_input') ?></td><td><input type="text" class="form-control" name="id_user_input" id="id_user_input" placeholder="Id User Input" value="<?php echo $id_user_input; ?>" /></td></tr>
	    <tr><td width='200'>Tingkat <?php echo form_error('tingkat') ?></td><td><input type="text" class="form-control" name="tingkat" id="tingkat" placeholder="Tingkat" value="<?php echo $tingkat; ?>" /></td></tr>
	    
        <tr><td width='200'>Mapel <?php echo form_error('mapel') ?></td><td> <textarea class="form-control" rows="3" name="mapel" id="mapel" placeholder="Mapel"><?php echo $mapel; ?></textarea></td></tr>
	    <tr><td width='200'>Kelas <?php echo form_error('kelas') ?></td><td><input type="text" class="form-control" name="kelas" id="kelas" placeholder="Kelas" value="<?php echo $kelas; ?>" /></td></tr>
	    <tr><td width='200'>Tahun <?php echo form_error('tahun') ?></td><td><input type="text" class="form-control" name="tahun" id="tahun" placeholder="Tahun" value="<?php echo $tahun; ?>" /></td></tr>
	    <tr><td width='200'>Semester <?php echo form_error('semester') ?></td><td><input type="text" class="form-control" name="semester" id="semester" placeholder="Semester" value="<?php echo $semester; ?>" /></td></tr>
	    <tr><td width='200'>Halaman <?php echo form_error('halaman') ?></td><td><input type="text" class="form-control" name="halaman" id="halaman" placeholder="Halaman" value="<?php echo $halaman; ?>" /></td></tr>
	    <tr><td width='200'>Uuid Cover Produk <?php echo form_error('uuid_cover_produk') ?></td><td><input type="text" class="form-control" name="uuid_cover_produk" id="uuid_cover_produk" placeholder="Uuid Cover Produk" value="<?php echo $uuid_cover_produk; ?>" /></td></tr>
	    
        <tr><td width='200'>Keterangan <?php echo form_error('keterangan') ?></td><td> <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"><?php echo $keterangan; ?></textarea></td></tr>
	    <tr><td></td><td><input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-danger"><i class="fa fa-floppy-o"></i> <?php echo $button ?></button> 
	    <a href="<?php echo site_url('tbl_produk_mapel_referensi') ?>" class="btn btn-info"><i class="fa fa-sign-out"></i> Kembali</a></td></tr>
	</table></form>        </div>
</div>
</div>