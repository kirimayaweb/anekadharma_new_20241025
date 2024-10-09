
        <!-- Main content -->
        <section class='content'>
          <div class='row'>
            <div class='col-xs-12'>
              <div class='box'>
                <div class='box-header'>
                <h3 class='box-title'>Tbl_pasar Read</h3>
        <table class="table table-bordered">
	    <tr><td>Koderetribusi</td><td><?php tampil($koderetribusi); ?></td></tr>
	    <tr><td>Namapasar</td><td><?php tampil($namapasar); ?></td></tr>
	    <tr><td>Kode</td><td><?php tampil($kode); ?></td></tr>
	    <tr><td>Kodekios</td><td><?php tampil($kodekios); ?></td></tr>
	    <tr><td>Kodelos</td><td><?php tampil($kodelos); ?></td></tr>
	    <tr><td>Kodearahan</td><td><?php tampil($kodearahan); ?></td></tr>
	    <tr><td>Panjang</td><td><?php tampil($panjang); ?></td></tr>
	    <tr><td>Lebar</td><td><?php tampil($lebar); ?></td></tr>
	    <tr><td>Nokios</td><td><?php tampil($nokios); ?></td></tr>
	    <tr><td>Kelaspasar</td><td><?php tampil($kelaspasar); ?></td></tr>
	    <tr><td>Tarifkelaspasar</td><td><?php tampil($tarifkelaspasar); ?></td></tr>
	    <tr><td>Idpenyewa</td><td><?php tampil($idpenyewa); ?></td></tr>
	    <tr><td>Tglmulai</td><td><?php tampil($tglmulai); ?></td></tr>
	    <tr><td>Tglakhir</td><td><?php tampil($tglakhir); ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('tbl_pasar') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->