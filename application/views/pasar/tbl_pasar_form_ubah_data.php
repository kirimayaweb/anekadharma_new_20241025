
        <section class='content'>
          <div class='row'>
            <div class='col-xs-12'>
              <div class='box'>
                <div class='box-header'>
                
                  <h3 class='box-title'>DATA PASAR</h3>
                      <div class='box box-primary'>
        <form action="<?php echo $action; ?>" method="post"><table class='table table-bordered'>


              <div class="box-body">
                <div class="row">				
          					<label class="col-sm-4 pull-left" align="right">Kode Pasar </label>
          					<!-- <label class="col-sm-2 pull-left" align="right">Tipe Pasar (1 Digit) </label> -->
          					<label class="col-sm-2 pull-left" align="right">Jenis Ruang (1 Digit)</label>
          					<label class="col-sm-2 pull-left" align="right">Blok (1 Digit)</label>
          					<label class="col-sm-4 pull-left" align="right">No. Urut Dasaran (3 Digit)</label>
                  </div>
                </div>

				
              <div class="box-body">
                <div class="row">
					
                    <div class="col-sm-4">  
                         <select name="kode_pasar" id="kode_pasar" class="form-control select2" style="width: 100%; height: 40px;">
                						 <option value="<?php echo $kode_pasar; ?>"><?php echo $kode_pasar; ?></option>
                							<?php
                								$namapasar = $this->db->get('sys_pasar');
                									foreach ($namapasar->result() as $nama_pasar){
                												echo "<option value='$nama_pasar->kode_pasar' ";
                												echo ">".  strtoupper($nama_pasar->kode_pasar)." = ".  strtoupper($nama_pasar->nama_pasar)." (Tipe = ".  strtoupper($nama_pasar->tipe_pasar).")</option>";
                											}
                							?>
                         </select>		
                         <?php echo "<br/>";echo form_error('kode_pasar'); ?>			  
                    </div>

                    <div class="col-sm-2">  

                         <select name="jenis_ruang" id="jenis_ruang" class="form-control select2" style="width: 100%; height: 40px;">
                             <option value="<?php echo $jenis_ruang; ?>"><?php echo $jenis_ruang; ?></option>
                              <?php
                                $sys_pasar_jenis_ruang = $this->db->get('sys_pasar_jenis_ruang');
                                  foreach ($sys_pasar_jenis_ruang->result() as $sys_pasar_jenis_ruang_detail){
                                        echo "<option value='$sys_pasar_jenis_ruang_detail->jenis_ruang' ";
                                        echo ">".  strtoupper($sys_pasar_jenis_ruang_detail->keterangan)."</option>";
                                      }
                              ?>
                         </select>  
                          <?php echo "<br/>";echo form_error('jenis_ruang'); ?>  
                    </div>
					
                    <div class="col-sm-2">  
                         <select name="blok" id="blok" class="form-control select2" style="width: 100%; height: 40px;">
                             <option value="<?php echo $blok; ?>"><?php echo $blok; ?></option>
                              <?php
                                $sys_pasar_blok = $this->db->get('sys_pasar_blok');
                                  foreach ($sys_pasar_blok->result() as $sys_pasar_blok_detail){
                                        echo "<option value='$sys_pasar_blok_detail->blok' ";
                                        echo ">".  strtoupper($sys_pasar_blok_detail->keterangan)."</option>";
                                      }
                              ?>
                         </select> 
                          <?php echo "<br/>";echo form_error('blok'); ?>  
                    </div>

                    <div class="col-sm-4">  
                      <input type="number" max="999" maxlength="3" pattern="^[0-9]$" class="form-control" name="nmr_urut_dasaran" id="nmr_urut_dasaran" placeholder="nomor urut dasaran" value="<?php echo $nmr_urut_dasaran; ?>" style="width: 100%; height: 40px" />   
                      <?php echo form_error('nmr_urut_dasaran'); ?>                     
                    </div>
					
                  </div>
                </div>

            <div class="box-body">
                <div class="row">
					<label class="col-sm-2 pull-left" align="right">Tarif kelas pasar ( /hari ) </label>
					

                    <label class="col-sm-2 pull-left" align="right">Panjang </label>         
                    <label class="col-sm-2 pull-left" align="right">Lebar </label>

                    <label class="col-sm-2 pull-left" align="right">Denda ( /hari )</label>
                    <label class="col-sm-2 pull-left" align="right"></label>
                  </div>
                </div>   
 
            <div class="box-body">
                <div class="row">
		           <div class="col-sm-2">  
                      <input type="text" class="form-control" name="tarifkelaspasar" id="tarifkelaspasar" placeholder="tarifkelaspasar" value="<?php echo $tarifkelaspasar; ?>" style="width: 100%; height: 40px"/>
                      <?php echo form_error('tarifkelaspasar') ?>    
                    </div>			                
                    <div class="col-sm-2">  
                      <input type="text" class="form-control" name="panjang" id="panjang" placeholder="panjang" value="<?php echo $panjang; ?>" style="width: 100%; height: 40px"/> 
                      <?php echo form_error('panjang') ?>   
                    </div>
                    <div class="col-sm-2">  
                      <input type="text" class="form-control" name="lebar" id="lebar" placeholder="lebar" value="<?php echo $lebar; ?>" style="width: 100%; height: 40px" />  
                      <?php echo form_error('lebar') ?>                    
                    </div>
                    <div class="col-sm-2">  
                      <input type="text" class="form-control" name="denda" id="denda" placeholder="denda 2% / bulan" value="<?php echo $denda; ?>" style="width: 100%; height: 40px"/> 
                      <?php echo "opsional : jika dikosongkan , maka akan di hitung 2% dari (Tarif/hari) x Panjang x Lebar" ?>   
                    </div>          
          


                    <div class="col-sm-2">  
                      <!-- <input type="text" class="form-control" name="rektera" id="rektera" placeholder="rektera" value="<?php echo $rektera; ?>" style="width: 100%; height: 40px"/>     -->
                    </div>          

                </div>
           </div>                      

                

             <div class="box-body">
                <div class="row">	

                    <label class="col-sm-2 pull-left" align="right">Rekening kebersihan / sampah </label>
                    <label class="col-sm-2 pull-left" align="right">Tarif kebersihan / sampah </label>

                </div>
             </div>                      
                        
             <div class="box-body">
              <div class="row"> 
                <div class="col-sm-2">  
                  <input type="text" class="form-control" name="rekkebersihan" id="rekkebersihan" placeholder="rekkebersihan" value="<?php echo $rekkebersihan; ?>" style="width: 100%; height: 40px"/> 
                  <?php echo form_error('rekkebersihan') ?>   
                </div>

                <div class="col-sm-2">  
                  <input type="text" class="form-control" name="tarifkebersihan" id="tarifkebersihan" placeholder="tarif kebersihan" value="<?php echo $tarifkebersihan; ?>" style="width: 100%; height: 40px"/> 
                  <?php echo form_error('tarifkebersihan') ?>      
                </div>
              </div>
             </div>                      
 


<?php if($idpenyewa){ ?>

<hr>
              <div class="box-body">
                <div class="row">
                  <label class="col-sm-2 pull-left" align="right">pedagang <?php echo form_error('idpenyewa') ?></label>
                    <div class="col-sm-4">  
                  <select name="idpenyewa" id="idpenyewa" class="form-control select2" style="width: 100%; height: 40px;">
                    <?php
                        $cek = $this->session->userdata('company'); 
                        $kodepasar=$this->session->userdata('kodepasar');

                        if ($kodepasar==99){
                            $tbl_identitas_pengguna = $this->db->get('tbl_identitas_pengguna');
                        }else{
                          $tbl_identitas_pengguna = $this->db->get_Where('tbl_identitas_pengguna', array('kodepasar'=>$kodepasar));
                        }
                          foreach ($tbl_identitas_pengguna->result() as $m)
                          {
                              echo "<option value='$m->nik' ";
                              echo">". strtoupper($m->nik) ." | ".  strtoupper($m->nama)."</option>";
                          }
                    ?>
                </select>
                    </div>
                            

                  </div>
                </div>   

              <div class="box-body">
                <div class="row">

                    <label class="col-sm-2 pull-left" align="right">tglmulai <?php echo form_error('tglmulai') ?></label>
                    <div class="col-sm-4">  
                                <div class="input-group date">
                                  <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                  </div>
                                  <input type="text" class="form-control pull-right" id="tglmulai" name="tglmulai" value="<?php $date=date_create($tglmulai);
echo date_format($date,"Y/m/d H:i:s");?>">
                                </div>
                    </div>
                  <label class="col-sm-2 pull-left" align="right">tglakhir <?php echo form_error('tglakhir') ?></label>
                    <div class="col-sm-4">  
                                <div class="input-group date">
                                  <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                  </div>
                                  <input type="text" class="form-control pull-right" id="tglakhir" name="tglakhir" value="<?php $date=date_create($tglakhir);
echo date_format($date,"Y/m/d H:i:s"); ?>">
                                </div>
                    </div>
                  </div>
                </div>   

              <div class="box-body">
                <div class="row">
                    <label class="col-sm-2 pull-left" align="right">Jenis Usaha <?php echo form_error('jenis_usaha') ?></label>
                    <div class="col-sm-4">  
                      <input type="text" class="form-control" name="jenis_usaha" id="jenis_usaha" placeholder="jenis_usaha" value="<?php echo $jenis_usaha; ?>" style="width: 100%; height: 40px"/>
                    </div>
                  </div>
                </div> 


<?php } ?>






	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <tr><td colspan='12' align='center'><button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('tbl_pasar') ?>" class="btn btn-default">BATAL</a></td></tr>
	
    </table>
<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
  </form>
    </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content