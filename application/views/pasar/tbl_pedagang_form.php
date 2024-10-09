
        <section class='content'>
          <div class='row'>
            <div class='col-xs-12'>
              <div class='box'>
                <div class='box-header'>
                
                  <h3 class='box-title'>IDENTITAS DATA PEDAGANG</h3>
                      <div class='box box-primary'>
        <form action="<?php echo $action; ?>" method="post">
          <table class='table table-bordered'>
    
              <div class="box-body">
                <div class="row">
                  <label class="col-sm-2 pull-left" align="right">Nik <?php echo form_error('nik') ?></label>
                    <div class="col-sm-4">  
                      <input type="text" class="form-control" name="nik" id="nik" placeholder="Nik" value="<?php tampil($nik); ?>" style="width: 100%; height: 40px"/>
                    </div>
                            
                    <label class="col-sm-2 pull-left" align="right">Id pedagang <?php echo form_error('idpedagang') ?></label>
                    <div class="col-sm-4">  
                      <!-- <textarea class="form-control" rows="3" name="idpedagang" id="idpedagang" placeholder="Idpedagang" style="width: 100%; height: 40px"><?php echo $idpedagang; ?></textarea> -->
                      <input type="text" class="form-control" name="idpedagang" id="idpedagang" placeholder="idpedagang" value="<?php tampil($idpedagang); ?>" style="width: 100%; height: 40px"/>
                    </div>
                  </div>
                </div>    
        
              <div class="box-body">
                <div class="row">
                  <label class="col-sm-2 pull-left" align="right">Nama <?php echo form_error('nama') ?></label>
                    <div class="col-sm-4">  
                      <!-- <textarea class="form-control" rows="3" name="nama" id="nama" placeholder="Nama" style="width: 100%; height: 40px"><?php //echo $nama; ?></textarea> -->
                      <input type="text" class="form-control" name="nama" id="nama" placeholder="nama" value="<?php tampil($nama); ?>" style="width: 100%; height: 40px"/>                      
                    </div>
                            
                    <label class="col-sm-2 pull-left" align="right">Alamat <?php echo form_error('alamat') ?></label>
                    <div class="col-sm-4">  
                      <!-- <textarea class="form-control" rows="3" name="alamat" id="alamat" placeholder="Alamat" style="width: 100%; height: 40px"><?php //echo $alamat; ?></textarea> -->
                      <input type="text" class="form-control" name="alamat" id="alamat" placeholder="alamat" value="<?php tampil($alamat); ?>" style="width: 100%; height: 40px"/>                                            
                    </div>
                  </div>
                </div>    
    
              <div class="box-body">
                <div class="row">
                  <label class="col-sm-2 pull-left" align="right">Jenis kelamin <?php echo form_error('jeniskelamin') ?></label>
                    <div class="col-sm-4">  
                      <!-- <textarea class="form-control" rows="3" name="jeniskelamin" id="jeniskelamin" placeholder="Jeniskelamin" style="width: 100%; height: 40px"><?php //echo $jeniskelamin; ?></textarea> -->
                      <!-- <input type="text" class="form-control" name="jeniskelamin" id="jeniskelamin" placeholder="jeniskelamin" value="<?php echo $jeniskelamin; ?>" style="width: 100%; height: 40px"/>     -->
                        <select name="jeniskelamin" id="jeniskelamin" class="form-control select2" style="width: 100%; height: 40px;">
                          <option value="LAKI-LAKI">LAKI - LAKI</option>
                          <option value="PEREMPUAN">PEREMPUAN</option>
                        </select>


                    </div>
                            
                    <label class="col-sm-2 pull-left" align="right">Status <?php echo form_error('status') ?></label>
                    <div class="col-sm-4">  
                      <!-- <textarea class="form-control" rows="3" name="status" id="status" placeholder="Status" style="width: 100%; height: 40px"><?php //echo $status; ?></textarea> -->
                      <input type="text" class="form-control" name="status" id="status" placeholder="status" value="<?php tampil($status); ?>" style="width: 100%; height: 40px"/>                      
                    </div>
                  </div>
                </div>    
    
              <div class="box-body">
                <div class="row">
                  <label class="col-sm-2 pull-left" align="right">No Hp <?php echo form_error('no_hp') ?></label>
                    <div class="col-sm-4">  
                      <!-- <textarea class="form-control" rows="3" name="no_hp" id="no_hp" placeholder="No Hp" style="width: 100%; height: 40px"><?php //echo $no_hp; ?></textarea> -->
                      <input type="text" class="form-control" name="no_hp" id="no_hp" placeholder="no_hp" value="<?php tampil($no_hp); ?>" style="width: 100%; height: 40px"/>                      
                    </div>
                            
                    <!--<label class="col-sm-2 pull-left" align="right">Status <?php //echo form_error('status') ?></label>-->
                    <div class="col-sm-4">  
                      <!--<textarea class="form-control" rows="3" name="status" id="status" placeholder="Status" style="width: 100%; height: 40px"><?php //echo $status; ?></textarea>-->
                    </div>
                  </div>
                </div>    
    
    
    
      <input type="hidden" name="id" value="<?php echo $id; ?>" />
      <tr>
          <td colspan='12' align="center"><button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
            <a href="<?php echo site_url('index.php/tbl_pedagang') ?>" class="btn btn-default">BATAL</a>
          </td>
      </tr>
  
    </table>
  

<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
  </form>
                      </div><!-- /.box-body -->
                </div><!-- /.box -->
              </div><!-- /.col -->
            </div><!-- /.row -->
          </div><!-- /.row -->
        </section><!-- /.content