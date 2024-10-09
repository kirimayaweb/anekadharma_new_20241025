<!-- Main content -->
<section class='content'>
  <div class='row'>
    <div class='col-xs-12'>
      <div class='box'>
        <div class='box-header btn-success' align="center">

          <h3 class='box-title'>
            <strong>

              <?php
              if ($button == "SIMPAN") {
                echo "TAMBAH DATA PEDAGANG";
              } elseif ($button == "Update") {
                echo "UPDATE DATA PEDAGANG";
              } else {
                echo "TAMBAH DATA PEDAGANG";
              }
              ?>
            </strong>
          </h3>
        </div>

        <div class='box box-primary'>

          <div class="container">
            <form action="<?php echo $action; ?>" method="post">
              <table class='table table-bordered'>

                <br>
                <div class="col-md-12">
                  <div class="box-body">
                    <div class="row">

                      <label class="col-sm-3 pull-left" align="right"> <b> <u> PHOTO </u> </b></label>

                      <div class="col-md-6 left">

                        <div class="row">
                          <div class="col-md-4 right">
                            <img src="<?= base_url('images_pedagang/' . $nik . '.jpg') ?>" style="width: 250px;height: 150px">
                          </div>

                          <div class="col-md-6 left col-md-offset-2">

                            <div class="row">
                              <div class="col-md-12 center col-md-offset-1 ">
                                <u><b> UPDATE POTO : </b> </u>

                              </div>
                            </div>
                            <br>


                            <div class="row">
                              <div class="col-md-12 center  col-md-offset-1">
                                <?php //echo $error;
                                ?>
                                <?php echo form_open_multipart('index.php/Tbl_identitas_pengguna/upload_poto_pedagang/' . $nik); ?>
                                <input type="file" name="userfile" size="20" /> </input>
                                <br>
                                <?php
                                if ($button == 'Update') { ?>
                                  <input type="submit" value="Proses Kirim Poto" />

                                <?php } else { ?>
                                  <input type="submit" value="Proses Kirim Poto" disabled />
                                <?php } ?>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>
  </div>





  <div class="col-md-12 center">


  </div>
  </div>

  <div class="col-md-4 center">
    <div class="box-body box-profile">
      <h3 class="profile-username text-center"><?php //echo $nip_detail['nama']; 
                                                ?></h3>
      <p class="text-muted text-center"> <?php //echo $nip_detail['nip']; 
                                          ?></p>
    </div>
  </div>

  </div>
  </div>
  </div>




  <!-- <div class="col-sm-12"> -->
  <div class="box-body">



    <div class="row">
      <label class="col-sm-2 pull-left" align="left">NIK <?php echo form_error('nik')  ?></label>
      <div class="col-sm-3 ">
        <input type="text" class="form-control" name="nik" id="nik" placeholder="NIK" value="<?php echo $nik; ?>" style="width: 100%; height: 40px" />

        <?php
        if (isset($_SESSION['pesan_error_NIK'])) {
          echo $_SESSION['pesan_error_NIK'];
          unset($_SESSION['pesan_error_NIK']);
        }
        ?>
      </div>
      <!--
                                    <label class="col-sm-2 pull-left" align="right">Id pedagang <?php //echo form_error('idpedagang') 
                                                                                                ?></label>
                                    <div class="col-sm-4">  
                                      <input type="text" class="form-control" name="idpedagang" id="idpedagang" placeholder="idpedagang" value="<?php echo $idpedagang; ?>" style="width: 100%; height: 40px"/>
                                    </div>-->
      <label class="col-sm-2 pull-left col-sm-offset-1" align="left">Nama <?php echo form_error('nama') ?></label>
      <div class="col-sm-3">
        <!-- <textarea class="form-control" rows="3" name="nama" id="nama" placeholder="Nama" style="width: 100%; height: 40px"><?php //echo $nama; 
                                                                                                                                ?></textarea> -->
        <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama" value="<?php echo $nama; ?>" style="width: 100%; height: 40px" />
      </div>
    </div>

  </div>

  <div class="box-body">
    <div class="row">
      <label class="col-sm-2 pull-left " align="left">Tempat Lahir <?php echo form_error('tempat_lahir') ?></label>
      <div class="col-sm-3">
        <!-- <textarea class="form-control" rows="3" name="nama" id="nama" placeholder="Nama" style="width: 100%; height: 40px"><?php //echo $nama; 
                                                                                                                                ?></textarea> -->
        <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" placeholder="Tempat Lahir" value="<?php echo $tempat_lahir; ?>" style="width: 100%; height: 40px" />
      </div>

      <label class="col-sm-2 pull-left col-sm-offset-1" align="left">Tanggal Lahir <?php echo form_error('tgl_lahir') ?></label>
      <div class="col-sm-3">
        <!-- <textarea class="form-control" rows="3" name="tgl_lahir" id="tgl_lahir" placeholder="tgl_lahir" style="width: 100%; height: 40px"><?php //echo $tgl_lahir; 
                                                                                                                                                ?></textarea> -->
        <input type="date" class="form-control" name="tgl_lahir" id="tgl_lahir" placeholder="Tanggal Lahir" value="<?php echo $tgl_lahir; ?>" style="width: 100%; height: 40px" />
      </div>

    </div>
  </div>

  <div class="box-body">
    <div class="row">

      <label class="col-sm-2 pull-left" align="left">Alamat <?php echo form_error('alamat') ?></label>
      <div class="col-sm-3">
        <!-- <textarea class="form-control" rows="3" name="alamat" id="alamat" placeholder="Alamat" style="width: 100%; height: 40px"><?php //echo $alamat; 
                                                                                                                                      ?></textarea> -->
        <input type="text" class="form-control" name="alamat" id="alamat" placeholder="Alamat" value="<?php echo $alamat; ?>" style="width: 100%; height: 40px" />
      </div>

    </div>
  </div>

  <div class="box-body">
    <div class="row">
      <label class="col-sm-2 pull-left " align="left">Desa <?php echo form_error('desa') ?></label>
      <div class="col-sm-3">
        <input type="text" class="form-control" name="desa" id="desa" placeholder="Desa" value="<?php echo $desa; ?>" style="width: 100%; height: 40px" />
      </div>

      <label class="col-sm-2 pull-left col-sm-offset-1" align="left">Kecamatan <?php echo form_error('kecamatan') ?></label>
      <div class="col-sm-3">
        <!-- <textarea class="form-control" rows="3" name="kecamatan" id="kecamatan" placeholder="kecamatan" style="width: 100%; height: 40px"><?php //echo $kecamatan; 
                                                                                                                                                ?></textarea> -->
        <input type="text" class="form-control" name="kecamatan" id="kecamatan" placeholder="Kecamatan" value="<?php echo $kecamatan; ?>" style="width: 100%; height: 40px" />
      </div>

    </div>
  </div>

  <div class="box-body">
    <div class="row">
      <label class="col-sm-2 pull-left" align="left">Kabupaten <?php echo form_error('kabupaten') ?></label>
      <div class="col-sm-3">
        <input type="text" class="form-control" name="kabupaten" id="kabupaten" placeholder="Kabupaten" value="<?php echo $kabupaten; ?>" style="width: 100%; height: 40px" />
      </div>

      <label class="col-sm-2 pull-left col-sm-offset-1" align="left">Propinsi <?php echo form_error('propinsi') ?></label>
      <div class="col-sm-3">
        <!-- <textarea class="form-control" rows="3" name="propinsi" id="propinsi" placeholder="propinsi" style="width: 100%; height: 40px"><?php //echo $propinsi; 
                                                                                                                                            ?></textarea> -->
        <input type="text" class="form-control" name="propinsi" id="propinsi" placeholder="Propinsi" value="<?php echo $propinsi; ?>" style="width: 100%; height: 40px" />
      </div>

    </div>
  </div>

  <div class="box-body">
    <div class="row">
      <label class="col-sm-2 pull-left" align="left">Jenis Kelamin <?php echo form_error('jeniskelamin') ?></label>
      <div class="col-sm-3">
        <!-- <textarea class="form-control" rows="3" name="jeniskelamin" id="jeniskelamin" placeholder="Jeniskelamin" style="width: 100%; height: 40px"><?php //echo $jeniskelamin; 
                                                                                                                                                        ?></textarea> -->
        <!-- <input type="text" class="form-control" name="jeniskelamin" id="jeniskelamin" placeholder="jeniskelamin" value="<?php echo $jeniskelamin; ?>" style="width: 100%; height: 40px"/>     -->
        <select name="jeniskelamin" id="jeniskelamin" class="form-control select2" style="width: 100%; height: 40px;">
          <option value="LAKI-LAKI">Laki-laki</option>
          <option value="PEREMPUAN">Perempuan</option>
        </select>


      </div>

      <label class="col-sm-2 pull-left col-sm-offset-1" align="left">Status <?php echo form_error('status') ?></label>
      <div class="col-sm-3">
        <!-- <textarea class="form-control" rows="3" name="status" id="status" placeholder="Status" style="width: 100%; height: 40px"><?php //echo $status; 
                                                                                                                                      ?></textarea> -->
        <input type="text" class="form-control" name="status" id="status" placeholder="Status" value="<?php echo $status; ?>" style="width: 100%; height: 40px" />
      </div>
    </div>
  </div>

  <div class="box-body">
    <div class="row">
      <label class="col-sm-2 pull-left" align="left">Handpone <?php echo form_error('no_hp') ?></label>
      <div class="col-sm-3">
        <!-- <textarea class="form-control" rows="3" name="no_hp" id="no_hp" placeholder="No Hp" style="width: 100%; height: 40px"><?php //echo $no_hp; 
                                                                                                                                    ?></textarea> -->
        <input type="text" class="form-control" name="no_hp" id="no_hp" placeholder="Handphone" value="<?php echo $no_hp; ?>" style="width: 100%; height: 40px" />
      </div>

      <label class="col-sm-2 pull-left col-sm-offset-1" align="left">NPWP <?php echo form_error('npwp') ?></label>
      <div class="col-sm-3">
        <!--<textarea class="form-control" rows="3" name="status" id="status" placeholder="Status" style="width: 100%; height: 40px"><?php //echo $status; 
                                                                                                                                      ?></textarea>-->
        <input type="text" class="form-control" name="npwp" id="npwp" placeholder="NPWP" value="<?php echo $npwp; ?>" style="width: 100%; height: 40px" />
      </div>
    </div>
  </div>
  </div>
  <input type="hidden" name="id" value="<?php echo $id; ?>" />
  <tr>
    <td colspan='12' align="center"><button type="submit" class="btn btn-primary"><?php echo $button ?></button>
      <a href="<?php echo site_url('tbl_identitas_pengguna') ?>" class="btn btn-default">Cancel</a>
    </td>
  </tr>

  </table>

  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

  </form>
  </div>
  </div><!-- /.box-body -->
  </div><!-- /.box -->
  </div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->