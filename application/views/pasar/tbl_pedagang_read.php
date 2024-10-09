
        <!-- Main content -->
        <section class='content'>
          <div class='row'>
            <div class='col-xs-12'>
              <div class='box'>
                <div class='box-header'>
                <h3 class='box-title'>Tbl_pedagang Read</h3>
        <table class="table table-bordered">
	    <tr><td>Nik</td><td><?php tampil($nik); ?></td></tr>
	    <tr><td>Idpedagang</td><td><?php tampil($idpedagang); ?></td></tr>
	    <tr><td>Nama</td><td><?php tampil($nama); ?></td></tr>
	    <tr><td>Alamat</td><td><?php tampil($alamat); ?></td></tr>
	    <tr><td>Jeniskelamin</td><td><?php tampil($jeniskelamin); ?></td></tr>
	    <tr><td>Status</td><td><?php tampil($status); ?></td></tr>
	    <tr><td>No Hp</td><td><?php tampil($no_hp); ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('tbl_pedagang') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->