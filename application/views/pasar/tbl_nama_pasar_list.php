
        <!-- Main content -->
        <section class='content'>
          <div class='row'>
            <div class='col-xs-12'>
              <div class='box'>
                <div class='box-header'>
                  <h3 class='box-title'>TBL_NAMA_PASAR LIST <?php echo anchor('tbl_nama_pasar/create/','Create',array('class'=>'btn btn-danger btn-sm'));?>
		<?php echo anchor(site_url('tbl_nama_pasar/excel'), ' <i class="fa fa-file-excel-o"></i> Excel', 'class="btn btn-primary btn-sm"'); ?>
		<?php echo anchor(site_url('tbl_nama_pasar/word'), '<i class="fa fa-file-word-o"></i> Word', 'class="btn btn-primary btn-sm"'); ?>
		<!-- <?php //echo anchor(site_url('tbl_nama_pasar/pdf'), '<i class="fa fa-file-pdf-o"></i> PDF', 'class="btn btn-primary btn-sm"'); ?></h3> -->
                </div><!-- /.box-header -->
                <div class='box-body'>
        <table class="table table-bordered table-striped" id="mytable">
            <thead>
                <tr>
                    <th width="80px">No</th>
            <th>Action</th>                    
		    <th>Kode</th>
		    <th>Nama Pasar</th>
                </tr>
            </thead>
	    <tbody>
            <?php
            $start = 0;
            foreach ($tbl_nama_pasar_data as $tbl_nama_pasar)
            {
                ?>
                <tr>
		    <td><?php echo ++$start ?></td>
            <td style="text-align:center" width="140px">
            <?php 
            echo anchor(site_url('tbl_nama_pasar/read/'.$tbl_nama_pasar->id),'<i class="fa fa-eye"></i>',array('title'=>'detail','class'=>'btn btn-danger btn-sm')); 
            echo '  '; 
            echo anchor(site_url('tbl_nama_pasar/update/'.$tbl_nama_pasar->id),'<i class="fa fa-pencil-square-o"></i>',array('title'=>'edit','class'=>'btn btn-danger btn-sm')); 
            echo '  '; 
            echo anchor(site_url('tbl_nama_pasar/delete/'.$tbl_nama_pasar->id),'<i class="fa fa-trash-o"></i>','title="delete" class="btn btn-danger btn-sm" onclick="javasciprt: return confirm(\'Are You Sure ?\')"'); 
            ?>
            </td>            
		    <td><?php tampil($tbl_nama_pasar->kode) ?></td>
		    <td><?php tampil($tbl_nama_pasar->nama_pasar) ?></td>

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
                $("#mytable").dataTable();
            });
        </script>
                    </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->