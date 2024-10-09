<!-- Main content -->
<section class='content'>
  <div class='row'>
    <div class='col-xs-12 '>
      <div class='box'>
        <div class='box-header btn-success' align="center">

          <h3 class='box-title'> <strong><?php echo $titleform ?> </strong></h3>
        </div>
        <div class='box box-primary'>
          <form action="<?php echo $action; ?>" method="post">
            <table class='table table-bordered'>
              <br><br>

              <div class="box-body">
                <div class="row">

                  <label class="col-sm-2 pull-left" align="left">Kode Pasar</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="kodepasar" id="kodepasar" placeholder="Kode Pasar" value="<?php tampil($kodepasar); ?>" disabled />
                  </div>

                  <label class="col-sm-2 pull-left col-sm-offset-1" align="left">Tgl Akhir</label>
                  <div class="col-sm-2">
                    <input type="text" class="form-control" name="tgl_akhir" id="tgl_akhir" placeholder="tgl akhir" value="<?php tampil($tgl_akhir); ?>" disabled />
                  </div>
                </div>
              </div>

              <div class="box-body">
                <div class="row">
                  <label class="col-sm-2 pull-left" align="left">NIK</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="nik" id="nik" placeholder="NIK" value="<?php tampil($nik); ?>" disabled />
                  </div>

                  <label class="col-sm-2 pull-left col-sm-offset-1" align="left">Tgl Awal</label>
                  <div class="col-sm-2">
                    <input type="text" class="form-control" name="tgl_awal" id="tgl_awal" placeholder="Tgl Awal" value="<?php tampil($tgl_awal); ?>" disabled />
                  </div>
                </div>
              </div>

              <div class="box-body">
                <div class="row">
                  <label class="col-sm-2 pull-left " align="left">Kode Retribusi</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="koderetribusi" id="koderetribusi" placeholder="Kode Retribusi" value="<?php tampil($koderetribusi); ?>" disabled />
                  </div>

                  <label class="col-sm-2 pull-left col-sm-offset-1" align="left">Tgl Aktif</label>

                  <div class="col-sm-2">
                    <input type="text" class="form-control" name="tgl_aktif" id="tgl_aktif" placeholder="Tgl Aktif" value="<?php tampil($tgl_aktif); ?>" disabled />
                  </div>


                </div>



              </div>
        </div>+

        <div class="box-body">
          <div class="row">
            <label class="col-sm-2 pull-left" align="left">Tarif Kelas Pasar</label>
            <div class="col-sm-3">
              <input type="text" class="form-control" name="tarifkelaspasar" id="tarifkelaspasar" placeholder="Tarif Kelas pasar" value="<?php echo $tarifkelaspasar; ?>" disabled />
            </div>

          </div>
        </div>

        <div class="box-body">
          <div class="row">
            <label class="col-sm-2 pull-left" align="left">Tagihan Kebersihan</label>
            <div class="col-sm-3">
              <input type="text" class="form-control" name="tarifkebersihan" id="tarifkebersihan" placeholder="Tarif Kebersihan" value="<?php echo $tarifkebersihan; ?>" disabled />
            </div>

          </div>
        </div>




        <div class="box-body">
          <div class="row">
            <label class="col-sm-2 pull-left" align="left">Jumlah Hari</label>
            <div class="col-sm-3 pull-left" align="right">

              <input type="text" class="form-control" name="jumlah_hari_aktif" id="jumlah_hari_aktif" placeholder="Jumlah Hari" value="<?php echo $jumlah_hari_aktif; ?>" align="right" />
            </div>


          </div>
        </div>



        <div class="box-body">
          <div class="row">





            <label class="col-sm-2 pull-left" align="right"></label>
            <label class="col-sm-2 pull-left" align="right"></label>
            <label class="col-sm-2 pull-left" align="right"></label>

          </div>
        </div>


      </div>
    </div>


    <div class="box-body">
      <div class="row">




        <label class="col-sm-2 pull-left" align="right"></label>
        <label class="col-sm-2 pull-left" align="right"></label>
        <label class="col-sm-2 pull-left" align="right"></label>

      </div>
    </div>


    <div class="box-body">
      <div class="row">




        <div class="col-sm-2">
        </div>

        <div class="col-sm-2">
        </div>

        <div class="col-sm-2">
        </div>

      </div>
    </div>
    <br><br><br>

    <input type="hidden" name="id" value="<?php echo $id; ?>" />
    <tr>
      <td colspan='12' align="center"><button type="submit" class="btn btn-primary"><?php echo $button ?></button>
        <a href="<?php echo site_url('tbl_pasar_input_tagihan') ?>" class="btn btn-default">Cancel</a></td>
    </tr>

    </table>

    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    </form>
  </div><!-- /.box-body -->
  </div><!-- /.box -->
  </div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->