
        <!-- Main content -->
        <section class='content'>
          <div class='row'>
            <div class='col-xs-12'>
              <div class='box'>
                <div class='box-header'>
                  <h3 class='box-title'>LIST DATA PEDAGANG <?php echo anchor('tbl_pedagang/create/','TAMBAH DATA PEDAGANG',array('class'=>'btn btn-danger btn-sm'));?>
		<?php echo anchor(site_url('index.php/tbl_pedagang/excel'), ' <i class="fa fa-file-excel-o"></i> Excel', 'class="btn btn-primary btn-sm"'); ?>
		<!-- <?php echo anchor(site_url('tbl_pedagang/pdf'), '<i class="fa fa-file-pdf-o"></i> PDF', 'class="btn btn-primary btn-sm"'); ?></h3> -->
                </div><!-- /.box-header -->
                <div class='box-body'>
        <table class="table table-bordered table-striped" id="mytable">
            <thead>
                <tr>
                    <th width="80px">No</th>
            <th>Action</th>
		    <th>Nik</th>
		    <th>Idpedagang</th>
		    <th>Nama</th>
		    <th>Alamat</th>
		    <th>Jeniskelamin</th>
		    <th>Status</th>
		    <th>No Hp</th>
		    
                </tr>
            </thead>
	    <tbody>
            <?php
            $start = 0;
            foreach ($tbl_pedagang_data as $tbl_pedagang)
            {
                ?>
                <tr>
		    <td><?php echo ++$start ?></td>
            <td style="text-align:center" width="140px">
            <?php 
            // echo anchor(site_url('tbl_pedagang/read/'.$tbl_pedagang->id),'<i class="fa fa-eye"></i>',array('title'=>'detail','class'=>'btn btn-danger btn-sm')); 
            // echo '  '; 
            echo anchor(site_url('tbl_pedagang/update/'.$tbl_pedagang->id),'<i class="fa fa-pencil-square-o"></i>',array('title'=>'edit','class'=>'btn btn-danger btn-sm')); 
            echo '  '; 
            echo anchor(site_url('tbl_pedagang/delete/'.$tbl_pedagang->id),'<i class="fa fa-trash-o"></i>','title="delete" class="btn btn-danger btn-sm" onclick="javasciprt: return confirm(\'Anda yakin akan menghapus data ?\')"'); 
            ?>
            </td>            
		    <td><?php tampil($tbl_pedagang->nik) ?></td>
		    <td><?php tampil($tbl_pedagang->idpedagang) ?></td>
		    <td><?php tampil($tbl_pedagang->nama) ?></td>
		    <td><?php tampil($tbl_pedagang->alamat) ?></td>
		    <td><?php tampil($tbl_pedagang->jeniskelamin) ?></td>
		    <td><?php tampil($tbl_pedagang->status) ?></td>
		    <td><?php tampil($tbl_pedagang->no_hp) ?></td>
		    
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
                $("#mytable").dataTable({scrollY: 250,scrollX: true});
            });
        </script>
                    </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->