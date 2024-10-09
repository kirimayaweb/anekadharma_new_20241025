<div class="content-wrapper">
    
    <section class="content">
        <div class="box box-warning box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">DATA COVER</h3>
            </div>
            <form action="<?php echo $action; ?>" method="post">
            
<table class='table table-bordered>' <tr>
			<td width='200'>Tingkat <?php echo form_error('tingkat') ?></td>
			<td>
				<!-- <input type="text" class="form-control" name="tingkat" id="tingkat" placeholder="Tingkat" value="<?php echo $tingkat; ?>" /> -->
				<select name="tingkat" id="tingkat" class="form-control select2" style="width: 200px; height: 10px;">
                                            <option value="<?php echo $tingkat; ?>"><?php echo $tingkat; ?></option>

                                            <option value="MA">MA</option>
                                            <option value="MTS">MTS</option>
                                            <option value="MI">MI</option>
                                            <option value="SMP">SMP K13</option>
											<option value="PGSMP">PGSMP K13</option>
											<option value="SMPKUMER">SMP KUMER</option>
											<option value="PGSMPKUMER">PGSMP KUMER</option>
											<option value="SD">SD K13</option>
											<option value="PGSD">PGSD K13</option>
											<option value="SDKUMER">SD KUMER</option>
											<option value="PGSDKUMER">PGSD KUMER</option>
                                        </select>

			</td>
		</tr>
	    
	    
        <tr>
			<td>Nama Cover <?php echo form_error('nama_cover') ?></td>
			<td> 
				<textarea class="form-control" rows="1" name="nama_cover" id="nama_cover" placeholder="Nama Cover"><?php echo $nama_cover; ?></textarea>
			</td>
		</tr>
	    
        <tr>
			<td width='200'>Keterangan <?php echo form_error('keterangan') ?></td>
			<td> 
				<!-- <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"><?php //echo $keterangan; ?></textarea> -->
				<select name="keterangan" id="keterangan" class="form-control select2" style="width: 200px; height: 10px;">
                    <option value="<?php echo $keterangan; ?>"><?php echo $keterangan; ?></option>
                    <option value="buku_lama">buku_lama</option>
                </select>
				dikosongkan jika untuk cover-cover buku baru ( PILIH buku_lama untuk BUKU LAMA )
			</td>

		</tr>
	    <tr>
			<td></td><td><input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-danger"><i class="fa fa-floppy-o"></i> <?php echo $button ?></button> 
	    <a href="<?php echo site_url('sys_cover') ?>" class="btn btn-info"><i class="fa fa-sign-out"></i> Kembali</a></td></tr>
	</table></form>        </div>
</div>
</div>