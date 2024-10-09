
        <!-- Main content -->
        <section class='content'>
          <div class='row'>
            <div class='col-xs-12'>
              <div class='box'>
                <div class='box-header'>
                  <h3 class='box-title'>SKHP FORMS
                    <!-- <?php //echo anchor('tbl_pasar/create/','Tambah Data Pasar(KIOS)',array('class'=>'btn btn-danger btn-sm'));?>
		<?php //echo anchor(site_url('tbl_pasar/excel'), ' <i class="fa fa-file-excel-o"></i> Excel', 'class="btn btn-primary btn-sm"'); ?>
		<?php //echo anchor(site_url('tbl_pasar/word'), '<i class="fa fa-file-word-o"></i> Word', 'class="btn btn-primary btn-sm"'); ?>
		 --><!-- <?php //echo anchor(site_url('tbl_pasar/pdf'), '<i class="fa fa-file-pdf-o"></i> PDF', 'class="btn btn-primary btn-sm"'); ?></h3> -->
                </div><!-- /.box-header -->
                <div class='box-body'>
        <table class="table table-bordered table-striped" id="mytable">
            <thead>
                <tr>
                    <th width="80px">No</th>
					<th style="text-align:center" width="40px">Action</th>
					<th style="text-align:center" width="40px">CETAK SKHP</th>
					<th style="text-align:center" width="40px">NIK</th>                    
					<th>Kode retribusi</th>

					<th width="250px">Tgl Mulai /<br/>Akhir</th>
					<!--<th>Tglakhir</th>-->

					<th>Tarifkelaspasar</th>
					
					<th>Nama pasar</th>
					
					<th>kode pasar</th>
					<th>tipe pasar</th>
					<th>jenis ruang</th>
					<th>blok</th>
					<th>nmr urut dasaran</th>
					

					<th>Panjang</th>
					<th>Lebar</th>

                </tr>
            </thead>
	    <tbody>
            <?php
            $start = 0;
            foreach ($tbl_pasar_data as $tbl_pasar)
            {
                ?>
                <tr>
						<td><?php echo ++$start ?></td>						
						<td style="text-align:center" width="40px">
						<?php
							if( strtotime($tbl_pasar->tglakhir) > strtotime('now') ){
								echo anchor(site_url('tbl_pasar/update/'.$tbl_pasar->id),'<i class="fa fa-pencil-square-o">Ubah Data</i>',array('title'=>'edit','class'=>'btn btn-danger btn-sm'));
							}else{                        
								echo anchor(site_url('tbl_pasar/update/'.$tbl_pasar->id),'<i class="fa fa-pencil-square-o">Ubah Data</i>',array('title'=>'edit','class'=>'btn btn-block btn-success btn-sm'));
								}
						?>
						</td>	
						
						<td style="text-align:center" width="40px">
						<?php
							// if( strtotime($tbl_pasar->tglakhir) > strtotime('now') ){
							// 	echo anchor(site_url('tbl_pasar/update/'.$tbl_pasar->id),'<i class="fa fa-pencil-square-o">Ubah Data</i>',array('title'=>'edit','class'=>'btn btn-danger btn-sm'));
							// }else{                        
								echo anchor(site_url('index.php/tbl_pasar/word_skhp/'.$tbl_pasar->id),'<i class="fa fa-pencil-square-o">CETAK SKHP</i>',array('title'=>'edit','class'=>'btn btn-block btn-success btn-sm'));
								// }
						?>
						</td>	
						
						
						<td style="text-align:center" width="40px">
							<?php 
								if( strtotime($tbl_pasar->tglakhir) > strtotime('now') ){echo $tbl_pasar->nik;}else{
									echo anchor(site_url('index.php/tbl_pasar/Assign_pedagang/'.$tbl_pasar->id), ' <i class="fa fa-file-excel-o"></i> HABIS/Aktivasi Sewa', 'class="btn btn-primary btn-sm"');
								}

							?>		    	
						</td>	 
						
						<td><?php tampil($tbl_pasar->koderetribusi); ?></td>
						<td style="text-align:center" width="250px"><?php tampil($tbl_pasar->tglmulai); ?><br/><?php 
						tampil($tbl_pasar->tglakhir) ;
						?></td>
						<!--<td><?php //echo $tbl_pasar->tglakhir ?></td>-->

						<td><?php tampil($tbl_pasar->tarifkelaspasar) ?></td>						

						
						<td><?php tampil($tbl_pasar->namapasar) ?></td>
						<td><?php tampil($tbl_pasar->kodepasar) ?></td>
						<td><?php tampil($tbl_pasar->tipe_pasar) ?></td>
						<td><?php tampil($tbl_pasar->jenis_ruang) ?></td>
						<td><?php tampil($tbl_pasar->blok) ?></td>
						<td><?php tampil($tbl_pasar->nmr_urut_dasaran) ?></td>
						<td><?php tampil($tbl_pasar->panjang) ?></td>
						<td><?php tampil($tbl_pasar->lebar) ?></td>

						</tr>
					<?php
				}
				?>
            </tbody>
        </table>
        <script src="<?php echo base_url('assets/js/jquery-1.11.2.min.js') ?>"></script>
        <script src="<?php echo base_url('assets/datatables/jquery.dataTables.js') ?>"></script>
        <script src="<?php echo base_url('assets/datatables/dataTables.bootstrap.js') ?>"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#mytable").dataTable({scrollY: 350,scrollX: true});
            });
        </script>
                    </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->