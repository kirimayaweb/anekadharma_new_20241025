
        <!-- Main content -->
        <section class='content'>
          <div class='row'>
            <div class='col-xs-12'>
              <div class='box'>
                <div class='box-header'>
                <h3 class='box-title'>Tbl_pembayaran_pasar Read</h3>
        <table class="table table-bordered">
	    <tr><td>Kodepasar</td><td><?php tampil($kodepasar); ?></td></tr>
	    <tr><td>Idpedagang</td><td><?php tampil($idpedagang); ?></td></tr>
	    <tr><td>Koderetribusi</td><td><?php tampil($koderetribusi); ?></td></tr>
	    <tr><td>Namatagihan</td><td><?php tampil($namatagihan); ?></td></tr>
	    <tr><td>Nominal</td><td><?php tampil($nominal); ?></td></tr>
	    <tr><td>Tglbayar</td><td><?php tampil($tglbayar); ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('tbl_pembayaran_pasar') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->