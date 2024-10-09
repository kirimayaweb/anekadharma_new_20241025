
        <!-- Main content -->
        <section class='content'>
          <div class='row'>
            <div class='col-xs-12'>
              <div class='box'>
                <div class='box-header'>
                <h3 class='box-title'>Tbl_pasar_input_tagihan Read</h3>
        <table class="table table-bordered">
	    <tr><td>Kodepasar</td><td><?php tampil($kodepasar); ?></td></tr>
	    <tr><td>Idpedagang</td><td><?php tampil($idpedagang); ?></td></tr>
	    <tr><td>Koderetribusi</td><td><?php tampil($koderetribusi); ?></td></tr>
	    <tr><td>Namatagihan</td><td><?php tampil($namatagihan); ?></td></tr>
	    <tr><td>Nominal</td><td><?php tampil($nominal); ?></td></tr>
	    <tr><td>Tgl Awal</td><td><?php tampil($tgl_awal); ?></td></tr>
	    <tr><td>Tgl Akhir</td><td><?php tampil($tgl_akhir); ?></td></tr>
	    <tr><td>Tgl Aktif</td><td><?php tampil($tgl_aktif); ?></td></tr>
	    <tr><td>Tarifkelaspasar</td><td><?php tampil($tarifkelaspasar); ?></td></tr>
	    <tr><td>Denda</td><td><?php tampil($denda); ?></td></tr>
	    <tr><td>Tagihan Kebersihan</td><td><?php tampil($tagihan_kebersihan); ?></td></tr>
	    <tr><td>Tagihan Tera</td><td><?php tampil($tagihan_tera); ?></td></tr>
	    <tr><td>Tahun</td><td><?php tampil($tahun); ?></td></tr>
	    <tr><td>Bulan</td><td><?php tampil($bulan); ?></td></tr>
	    <tr><td>Status Kirim</td><td><?php tampil($status_kirim); ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('tbl_pasar_input_tagihan') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->