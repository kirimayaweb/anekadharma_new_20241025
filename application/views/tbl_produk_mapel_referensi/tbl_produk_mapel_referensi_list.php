<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-warning box-solid">
    
                    <div class="box-header">
                        <h3 class="box-title">KELOLA DATA TBL_PRODUK_MAPEL_REFERENSI</h3>
                    </div>
        
        <div class="box-body">
            <div class='row'>
            <div class='col-md-9'>
            <div style="padding-bottom: 10px;"'>
        <?php echo anchor(site_url('tbl_produk_mapel_referensi/create'), '<i class="fa fa-wpforms" aria-hidden="true"></i> Tambah Data', 'class="btn btn-danger btn-sm"'); ?>
		<?php echo anchor(site_url('tbl_produk_mapel_referensi/excel'), '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Ms Excel', 'class="btn btn-success btn-sm"'); ?></div>
            </div>
            <div class='col-md-3'>
            <form action="<?php echo site_url('tbl_produk_mapel_referensi/index'); ?>" class="form-inline" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                        <span class="input-group-btn">
                            <?php 
                                if ($q <> '')
                                {
                                    ?>
                                    <a href="<?php echo site_url('tbl_produk_mapel_referensi'); ?>" class="btn btn-default">Reset</a>
                                    <?php
                                }
                            ?>
                          <button class="btn btn-primary" type="submit">Search</button>
                        </span>
                    </div>
                </form>
            </div>
            </div>
        
   
        <div class="row" style="margin-bottom: 10px">
            <div class="col-md-4 text-center">
                <div style="margin-top: 8px" id="message">
                    <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
                </div>
            </div>
            <div class="col-md-1 text-right">
            </div>
            <div class="col-md-3 text-right">
                
            </div>
        </div>
        <table class="table table-bordered" style="margin-bottom: 10px">
            <tr>
                <th>No</th>
		<th>Uuid Produk</th>
		<th>Kode Produk</th>
		<th>Date Input</th>
		<th>Id User Input</th>
		<th>Tingkat</th>
		<th>Mapel</th>
		<th>Kelas</th>
		<th>Tahun</th>
		<th>Semester</th>
		<th>Halaman</th>
		<th>Uuid Cover Produk</th>
		<th>Keterangan</th>
		<th>Action</th>
            </tr><?php
            foreach ($tbl_produk_mapel_referensi_data as $tbl_produk_mapel_referensi)
            {
                ?>
                <tr>
			<td width="10px"><?php echo ++$start ?></td>
			<td><?php echo $tbl_produk_mapel_referensi->uuid_produk ?></td>
			<td><?php echo $tbl_produk_mapel_referensi->kode_produk ?></td>
			<td><?php echo $tbl_produk_mapel_referensi->date_input ?></td>
			<td><?php echo $tbl_produk_mapel_referensi->id_user_input ?></td>
			<td><?php echo $tbl_produk_mapel_referensi->tingkat ?></td>
			<td><?php echo $tbl_produk_mapel_referensi->mapel ?></td>
			<td><?php echo $tbl_produk_mapel_referensi->kelas ?></td>
			<td><?php echo $tbl_produk_mapel_referensi->tahun ?></td>
			<td><?php echo $tbl_produk_mapel_referensi->semester ?></td>
			<td><?php echo $tbl_produk_mapel_referensi->halaman ?></td>
			<td><?php echo $tbl_produk_mapel_referensi->uuid_cover_produk ?></td>
			<td><?php echo $tbl_produk_mapel_referensi->keterangan ?></td>
			<td style="text-align:center" width="200px">
				<?php 
				echo anchor(site_url('tbl_produk_mapel_referensi/read/'.$tbl_produk_mapel_referensi->id),'<i class="fa fa-eye" aria-hidden="true"></i>','class="btn btn-danger btn-sm"'); 
				echo '  '; 
				echo anchor(site_url('tbl_produk_mapel_referensi/update/'.$tbl_produk_mapel_referensi->id),'<i class="fa fa-pencil-square-o" aria-hidden="true"></i>','class="btn btn-danger btn-sm"'); 
				echo '  '; 
				echo anchor(site_url('tbl_produk_mapel_referensi/delete/'.$tbl_produk_mapel_referensi->id),'<i class="fa fa-trash-o" aria-hidden="true"></i>','class="btn btn-danger btn-sm" Delete','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'); 
				?>
			</td>
		</tr>
                <?php
            }
            ?>
        </table>
        <div class="row">
            <div class="col-md-6">
                
	    </div>
            <div class="col-md-6 text-right">
                <?php echo $pagination ?>
            </div>
        </div>
        </div>
                    </div>
            </div>
            </div>
    </section>
</div>