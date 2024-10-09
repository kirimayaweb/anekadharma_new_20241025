<section class='content'>
  <div class='row'>
    <div class='col-xs-12'>
      <div class='box'>
        <div class='box-header btn-success' align=" center">

          <h3 class='box-title'> <strong> DATA PASAR </strong></h3>
        </div>

        <!-- <div class='box box-primary'> -->
        <form action="<?php echo $action; ?>" method="post">
          <table class='table table-bordered'>

            <br><br>
            <div class="box-body">
              <div class="row">
                <label class="col-sm-2 pull-left" align="left">Kode Pasar </label>
                <div class="col-sm-3">
                  <select name="kode_pasar" id="kode_pasar" class="form-control select2" style="width: 100%; height: 40px;">
                    <option value="<?php echo $kode_pasar; ?>"><?php echo $kode_pasar; ?></option>
                    <?php
                    $namapasar = $this->db->get('sys_pasar');
                    foreach ($namapasar->result() as $nama_pasar) {
                      echo "<option value='$nama_pasar->kode_pasar' ";
                      echo ">" .  strtoupper($nama_pasar->kode_pasar) . " = " .  strtoupper($nama_pasar->nama_pasar) . " (Tipe = " .  strtoupper($nama_pasar->tipe_pasar) . ")</option>";
                    }
                    ?>
                  </select>
                  <?php echo "<br/>";
                  echo form_error('kode_pasar'); ?>
                </div>
                <!-- <label class="col-sm-2 pull-left" align="right">Tipe Pasar (1 Digit) </label> -->



              </div>
            </div>


            <div class="box-body">
              <div class="row">

                <label class="col-sm-2 pull-left" align="left">Jenis Ruang (1 Digit)</label>

                <div class="col-sm-3">

                  <select name="jenis_ruang" id="jenis_ruang" class="form-control select2" style="width: 100%; height: 40px;">
                    <option value="<?php echo $jenis_ruang; ?>"><?php echo $jenis_ruang; ?></option>
                    <?php
                    $sys_pasar_jenis_ruang = $this->db->get('sys_pasar_jenis_ruang');
                    foreach ($sys_pasar_jenis_ruang->result() as $sys_pasar_jenis_ruang_detail) {
                      echo "<option value='$sys_pasar_jenis_ruang_detail->jenis_ruang' ";
                      echo ">" .  strtoupper($sys_pasar_jenis_ruang_detail->jenis_ruang) . " : " .  strtoupper($sys_pasar_jenis_ruang_detail->keterangan) . "</option>";
                    }
                    ?>
                  </select>
                  <?php echo "<br/>";
                  echo form_error('jenis_ruang'); ?>
                </div>
              </div>
            </div>


            <div class="box-body">
              <div class="row">

                <label class="col-sm-2 pull-left" align="left">Blok (2 Digit)</label>
                <div class="col-sm-3">
                  <select name="blok" id="blok" class="form-control select2" style="width: 100%; height: 40px;">
                    <option value="<?php echo $blok; ?>"><?php echo $blok; ?></option>
                    <?php
                    $sys_pasar_blok = $this->db->get('sys_pasar_blok');
                    foreach ($sys_pasar_blok->result() as $sys_pasar_blok_detail) {
                      echo "<option value='$sys_pasar_blok_detail->blok' ";
                      echo ">" .  strtoupper($sys_pasar_blok_detail->blok) . " : " .  strtoupper($sys_pasar_blok_detail->keterangan) . "</option>";
                    }
                    ?>
                  </select>
                  <?php echo "<br/>";
                  echo form_error('blok'); ?>
                </div>
              </div>
            </div>

            <div class="box-body">
              <div class="row">
                <label class="col-sm-2 pull-left" align="left">No. Urut Dasaran (3 Digit)</label>
                <div class="col-sm-3">
                  <input type="number" max="999" maxlength="3" pattern="^[0-9]$" class="form-control" name="nmr_urut_dasaran" id="nmr_urut_dasaran" placeholder="Nomor Urut Dasaran" value="<?php echo $nmr_urut_dasaran; ?>" style="width: 100%; height: 40px" />
                  <?php echo form_error('nmr_urut_dasaran'); ?>
                  <?php echo "<font color='red'>Harus di isi 3 digit ( 3 angka )  </font>" ?>
                </div>

                <!-- <div class="row">
                  <label class="col-sm-2 pull-left col-sm-offset-1" align="left">Tarif kelas pasar ( / Hari ) </label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="tarifkelaspasar" id="tarifkelaspasar" placeholder="Tarif Kelas Pasar" value="<?php echo $tarifkelaspasar; ?>" style="width: 100%; height: 40px" />
                    <?php //echo form_error('tarifkelaspasar') 
                    ?>
                  </div>
                </div> -->
              </div>
            </div>

            <hr>

            <div class="box-body">
              <div class="row">

                <!-- <div class="row"> -->
                <label class="col-sm-2 pull-left " align="left">Tarif kelas pasar ( / Hari ) </label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" name="tarifkelaspasar" id="tarifkelaspasar" placeholder="Tarif Kelas Pasar" value="<?php echo $tarifkelaspasar; ?>" style="width: 100%; height: 40px" />
                  <?php echo form_error('tarifkelaspasar') ?>
                  <?php echo "<font color='red'>Nominal tarif /hari  </font>" ?>
                </div>
                <!-- </div> -->




                <div class="row">
                  <label class="col-sm-2 pull-left col-sm-offset-1" align="left">Jumlah hari aktif (/bulan) </label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="jumlah_hari_aktif" id="jumlah_hari_aktif" placeholder="Jumlah Hari Aktif Selama Sebulan" value="<?php echo $jumlah_hari_aktif; ?>" style="width: 100%; height: 40px" />
                    <?php echo form_error('jumlah_hari_aktif') ?>
                    <?php echo "<font color='red'>Jumlah penggunaan selama 1 bulan ( /bulan )  </font>" ?>
                  </div>
                </div>

              </div>


            </div>





            <div class="box-body">
              <div class="row">
                <label class="col-sm-2 pull-left" align="left">Panjang </label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" name="panjang" id="panjang" placeholder="Panjang" value="<?php echo $panjang; ?>" style="width: 100%; height: 40px" />
                  <?php echo form_error('panjang') ?>
                  <?php echo "<font color='red'>Gunakan . ( titik ) sebagai pengganti , ( koma ) untuk penulisan ukuran </font>" ?>
                </div>

                <div class="row">
                  <label class="col-sm-2 pull-left col-sm-offset-1" align="left">Lebar </label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" name="lebar" id="lebar" placeholder="Lebar" value="<?php echo $lebar; ?>" style="width: 100%; height: 40px" />
                    <?php echo form_error('lebar') ?>
                    <?php echo "<font color='red'>Gunakan . ( titik ) sebagai pengganti , ( koma ) untuk penulisan ukuran </font>" ?>
                  </div>
                </div>
              </div>
            </div>

      </div>

      <div class="box-body">
        <div class="row">


          <label class="col-sm-2 pull-left" align="left">Denda </label>

          <div class="col-sm-3">
            <input type="text" class="form-control" name="denda" id="denda" placeholder="Denda 2% / Bulan" value="<?php echo $denda; ?>" style="width: 100%; height: 40px" />
            <?php echo "<font color='red'>opsional : jika dikosongkan , maka akan di hitung 2% dari (Tarif/hari) x Panjang x Lebar</font>" ?>
          </div>

          <!-- <div class="row">

            <label class="col-sm-2 pull-left col-sm-offset-1" align="left">Denda </label>

            <div class="col-sm-3">
              <input type="text" class="form-control" name="denda" id="denda" placeholder="Denda 2% / Bulan" value="<?php echo $denda; ?>" style="width: 100%; height: 40px" />
              <?php //echo "<font color='red'>opsional : jika dikosongkan , maka akan di hitung 2% dari (Tarif/hari) x Panjang x Lebar</font>" 
              ?>
            </div>


          </div> -->
        </div>


        <div class="box-body">
          <div class="row">
            <label class="col-sm-2 pull-left" align="left">Rekening Kebersihan / Sampah </label>
            <div class="col-sm-3">
              <input type="text" class="form-control" name="rekkebersihan" id="rekkebersihan" placeholder="Rekening Kebersihan/ Sampah" value="<?php echo $rekkebersihan; ?>" style="width: 100%; height: 40px" />
              <?php echo form_error('rekkebersihan') ?>
            </div>

            <div class="row">
              <label class="col-sm-2 pull-left col-sm-offset-1" align="left">Tarif kebersihan/ Sampah </label>
              <div class="col-sm-3">
                <input type="text" class="form-control" name="tarifkebersihan" id="tarifkebersihan" placeholder="Tarif Kebersihan/ Sampah" value="<?php echo $tarifkebersihan; ?>" style="width: 100%; height: 40px" />
                <?php echo form_error('tarifkebersihan') ?>
              </div>
            </div>
          </div>
        </div>

        <div class="box-body">

        </div>


        <?php if ($idpenyewa) { ?>

          <hr>
          <div class="box-body">
            <div class="row">
              <label class="col-sm-2 pull-left" align="left">Pedagang <?php echo form_error('idpenyewa') ?></label>
              <div class="col-sm-3">
                <select name="idpenyewa" id="idpenyewa" class="form-control select2" style="width: 100%; height: 40px;">

                  <?php

                  echo "<option value='$idpenyewa' ";
                  echo ">" . strtoupper($idpenyewa) . " | " .  strtoupper(stripslashes($nama)) . "</option>";

                  $cek = $this->session->userdata('company');
                  $kodepasar = $this->session->userdata('kodepasar');

                  if ($kodepasar == 99) {
                    $tbl_identitas_pedagang_pasar = $this->db->get('tbl_identitas_pedagang_pasar');
                  } else {
                    $tbl_identitas_pedagang_pasar = $this->db->get_Where('tbl_identitas_pedagang_pasar', array('kodepasar' => $kodepasar));
                  }
                  foreach ($tbl_identitas_pedagang_pasar->result() as $m) {
                    echo "<option value='$m->nik' ";
                    echo ">" . strtoupper($m->nik) . " | " .  strtoupper(stripslashes($m->nama)) . "</option>";
                  }
                  ?>
                </select>
              </div>


              <label class="col-sm-2 pull-left col-sm-offset-1" align="left">Tanggal Mulai <?php echo form_error('tglmulai') ?></label>
              <div class="col-sm-3">
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="tglmulai" name="tglmulai" value="<?php $date = date_create($tglmulai);
                                                                                                          echo date_format($date, "Y/m/d H:i:s"); ?>">
                </div>

              </div>
            </div>
          </div>

          <div class="box-body">
            <div class="row">
              <label class="col-sm-2 pull-left" align="left">Jenis Usaha <?php echo form_error('jenis_usaha') ?></label>
              <div class="col-sm-3">
                <input type="text" class="form-control" name="jenis_usaha" id="jenis_usaha" placeholder="jenis_usaha" value="<?php echo $jenis_usaha; ?>" style="width: 100%; height: 40px" />
              </div>


              <label class="col-sm-2 pull-left col-sm-offset-1" align="left">Tanggal Akhir <?php echo form_error('tglakhir') ?></label>
              <div class="col-sm-3">
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="tglakhir" name="tglakhir" value="<?php $date = date_create($tglakhir);
                                                                                                          echo date_format($date, "Y/m/d H:i:s"); ?>">
                </div>
              </div>
            </div>
          </div>
      </div>

      <div class="box-body">
        <div class="row">

        </div>
      </div>


    <?php } ?>

    <br><br><br>

    <input type="hidden" name="id" value="<?php echo $id; ?>" />
    <tr>
      <td colspan='12' align='center'><button type="submit" class="btn btn-primary"><?php echo $button ?></button>
        <a href="<?php echo site_url('tbl_pasar') ?>" class="btn btn-default">BATAL</a>
      </td>
    </tr>

    </table>

    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    </form>
    </div><!-- /.box-body -->
  </div><!-- /.box -->

  </div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content