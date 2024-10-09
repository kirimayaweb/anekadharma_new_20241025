<!-- Main content -->
<section class='content'>
  <div class='row'>
    <div class='col-xs-12'>
      <div class='box'>
        <div class='box-header'>
          <h3 class='box-title'>Tbl_identitas_pengguna Read</h3>
          <table class="table table-bordered">
            <tr style="text-align:center">
              <td>NIK</td>
              <td><?php tampil($nik); ?></td>
            </tr>
            <tr style="text-align:center">
              <td>ID PEDAGANG</td>
              <td><?php tampil($idpedagang); ?></td>
            </tr>
            <tr style="text-align:center">
              <td>NAMA</td>
              <td><?php tampil($nama); ?></td>
            </tr>
            <td>ALAMAT</td>
            <tr style="text-align:center">
              <td><?php tampil($alamat); ?></td>
            </tr>
            <tr style="text-align:center">
              <td>JENIS KELAMIN</td>
              <td><?php tampil($jeniskelamin); ?></td>
            </tr>
            <tr style="text-align:center">
              <td>STATUS</td>
              <td><?php tampil($status); ?></td>
            </tr>
            <tr style="text-align:center">
              <td>NO HANDPHONE</td>
              <td><?php tampil($no_hp); ?></td>
            </tr>
            <tr>
              <td></td>
              <td><a href="<?php echo site_url('tbl_identitas_pengguna') ?>" class="btn btn-default">Cancel</a></td>
            </tr>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->