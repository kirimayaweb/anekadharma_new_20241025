
        <!-- Main content -->
        <section class='content'>
          <div class='row'>
            <div class='col-xs-12'>
              <div class='box'>
                <div class='box-header'>
                <h3 class='box-title'>Tbl_nama_pasar Read</h3>
        <table class="table table-bordered">
	    <tr><td>Kode</td><td><?php tampil($kode); ?></td></tr>
	    <tr><td>Nama Pasar</td><td><?php tampil($nama_pasar); ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('tbl_nama_pasar') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->