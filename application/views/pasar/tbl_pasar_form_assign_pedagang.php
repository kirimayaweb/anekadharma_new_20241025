
        <section class='content'>
          <div class='row'>
            <div class='col-xs-12'>
              <div class='box'>
                <div class='box-header'>
                
                  <h3 class='box-title'>ASSIGN DATA PEDAGANG KE KIOS TERPILIH</h3>
                      <div class='box box-primary'>
                        <form action="<?php echo $action; ?>" method="post"><table class='table table-bordered'>
						
              <div class="box-body">
                <div class="row">
                  <label class="col-sm-2 pull-left" align="right">Namapasar <?php echo form_error('namapasar') ?></label>
                    <div class="col-sm-4">  
                      <!-- <textarea class="form-control" rows="3" name="" id="namapasar" placeholder="Namapasar" style="width: 100%; height: 40px"><?php //echo $namapasar; ?></textarea> -->
                      <input type="text" class="form-control" name="namapasar" id="namapasar" placeholder="namapasar" value="<?php echo $namapasar; ?>" style="width: 100%; height: 40px" disabled/>    
                    </div>
                            
                    <label class="col-sm-2 pull-left" align="right">Kode Retribusi <?php echo form_error('koderetribusi') ?></label>
                    <div class="col-sm-4">  
                      <!-- <textarea class="form-control" rows="3" name="" id="kode" placeholder="Kode" style="width: 100%; height: 40px"><?php //echo $kode; ?></textarea> -->
                      <input type="text" class="form-control" name="koderetribusi" id="koderetribusi" placeholder="koderetribusi" value="<?php echo $koderetribusi; ?>" style="width: 100%; height: 40px" disabled/>                      
                    </div>
                  </div>
                </div>						
						
						
						
              <div class="box-body">
                <div class="row">
                  <label class="col-sm-2 pull-left" align="right">kode pasar <?php echo form_error('kodepasar') ?></label>
                    <div class="col-sm-4">  
                      <!-- <textarea class="form-control" rows="3" name="" id="kodekios" placeholder="Kodekios"><?php echo $kodekios; ?></textarea> -->
                      <input type="text" class="form-control" name="kodepasar" id="kodepasar" placeholder="kodepasar" value="<?php echo $kodepasar; ?>" style="width: 100%; height: 40px" disabled/>
                    </div>
                            
                    <label class="col-sm-2 pull-left" align="right">tipe pasar <?php echo form_error('tipe_pasar') ?></label>
                    <div class="col-sm-4">  
                      <!-- <textarea class="form-control" rows="3" name="" id="kodelos" placeholder="Kodelos"><?php echo $kodelos; ?></textarea> -->
                      <input type="text" class="form-control" name="tipe_pasar" id="tipe_pasar" placeholder="tipe_pasar" value="<?php echo $tipe_pasar; ?>" style="width: 100%; height: 40px" disabled/>
                    </div>
                  </div>
                </div>						

              <div class="box-body">
                <div class="row">
                  <label class="col-sm-2 pull-left" align="right">jenis ruang <?php echo form_error('jenis_ruang') ?></label>
                    <div class="col-sm-4">  
                      <input type="text" class="form-control" name="jenis_ruang" id="jenis_ruang" placeholder="jenis_ruang" value="<?php echo $jenis_ruang; ?>" style="width: 100%; height: 40px" disabled/>
                    </div>
                            
                    <!-- <label class="col-sm-2 pull-left" align="right">Panjang <?php echo form_error('panjang') ?></label> -->
                    <div class="col-sm-4">  
                      <!-- <input type="text" class="form-control" name="panjang" id="panjang" placeholder="panjang" value="<?php echo $panjang; ?>" style="width: 100%; height: 40px"/> -->
                    </div>
                  </div>
                </div>   
              <div class="box-body">
                <div class="row">
                            
                    <label class="col-sm-2 pull-left" align="right">blok <?php echo form_error('blok') ?></label>
                    <div class="col-sm-1">  
                      <input type="text" class="form-control" name="blok" id="blok" placeholder="blok" value="<?php echo $blok; ?>" style="width: 100%; height: 40px" disabled/>
                    </div>

                      <label class="col-sm-1 pull-left" align="right">nmr urut <?php echo form_error('nmr_urut_dasaran') ?></label>
                    <div class="col-sm-1">  
                      <input type="text" class="form-control" name="nmr_urut_dasaran" id="nmr_urut_dasaran" placeholder="nmr_urut_dasaran" value="<?php echo $nmr_urut_dasaran; ?>" style="width: 100%; height: 40px" disabled/>
                    </div>
                    <div class="col-sm-7"></div>
                  </div>
                </div>    

    

              <div class="box-body">
                <div class="row">
                  <!--<label class="col-sm-2 pull-left" align="right">Kelaspasar <?php echo form_error('kelaspasar') ?></label>
                    <div class="col-sm-4">  
                      <input type="text" class="form-control" name="kelaspasar" id="kelaspasar" placeholder="kelaspasar" value="<?php echo $kelaspasar; ?>" style="width: 100%; height: 40px" disabled/>
                    </div>-->
                            
                    <label class="col-sm-2 pull-left" align="right">Tarifkelaspasar <?php echo form_error('tarifkelaspasar') ?></label>
                    <div class="col-sm-4">  
                      <input type="text" class="form-control" name="tarifkelaspasar" id="tarifkelaspasar" placeholder="tarifkelaspasar" value="<?php echo $tarifkelaspasar; ?>" style="width: 100%; height: 40px" disabled/>
                    </div>
                  </div>
                </div>   

              <div class="box-body">
                <div class="row">
                  <label class="col-sm-2 pull-left" align="right">pedagang <?php echo form_error('idpenyewa') ?></label>
                    <div class="col-sm-4">  
                  <select name="idpenyewa" id="idpenyewa" class="form-control select2" style="width: 100%; height: 40px;">
                    <?php
              					$cek = $this->session->userdata('company'); 
              					$kodepasar=$this->session->userdata('kodepasar');

                        if ($kodepasar==99){
                            $tbl_identitas_pengguna = $this->db->get('tbl_identitas_pedagang_pasar');
                        }else{
                          $tbl_identitas_pengguna = $this->db->get_Where('tbl_identitas_pedagang_pasar', array('kodepasar'=>$kodepasar));
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
                                  <input type="text" class="form-control pull-right" id="tglmulai" name="tglmulai" value="">
                                </div>
                    </div>
                  <label class="col-sm-2 pull-left" align="right">tglakhir <?php echo form_error('tglakhir') ?></label>
                    <div class="col-sm-4">  
                                <div class="input-group date">
                                  <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                  </div>
                                  <input type="text" class="form-control pull-right" id="tglakhir" name="tglakhir" value="">
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


                	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
                	    <tr><td colspan='12' align="center"><button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
                	    <a href="<?php echo site_url('index.php/tbl_pasar') ?>" class="btn btn-default">Batal</a></td></tr>
                	
                    </table>


<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  </form>
                    </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content