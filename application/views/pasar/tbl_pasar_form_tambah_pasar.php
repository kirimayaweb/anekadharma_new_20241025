
        <section class='content'>
          <div class='row'>
            <div class='col-xs-12'>
              <div class='box'>
                <div class='box-header'>
                
                  <h3 class='box-title'>TAMBAH DATA PASAR</h3>
                      <div class='box box-primary'>
                        <form action="<?php echo $action; ?>" method="post"><table class='table table-bordered'>
						
              <div class="box-body">
                <div class="row">
                    <label class="col-sm-2 pull-left" align="right">Kode Retribusi <?php echo form_error('kode') ?></label>
                    <div class="col-sm-4">  
                      <!-- <textarea class="form-control" rows="3" name="" id="kode" placeholder="Kode" style="width: 100%; height: 40px"><?php //echo $kode; ?></textarea> -->
                      <input type="text" class="form-control" name="kode" id="kode" placeholder="kode" value="<?php echo $kode; ?>" style="width: 100%; height: 40px"/>                      
                    </div>
                  </div>
                </div>            
            
                <div class="box-body">
                <div class="row">
                  <label class="col-sm-2 pull-left" align="right">Namapasar <?php echo form_error('namapasar') ?></label>
                    <div class="col-sm-4">  
                      <!-- <textarea class="form-control" rows="3" name="" id="namapasar" placeholder="Namapasar" style="width: 100%; height: 40px"><?php //echo $namapasar; ?></textarea> -->
                      <input type="text" class="form-control" name="namapasar" id="namapasar" placeholder="namapasar" value="<?php echo $namapasar; ?>" style="width: 100%; height: 40px"/>    
                    </div>

                  <label class="col-sm-2 pull-left" align="right">Kodekios <?php echo form_error('kodekios') ?></label>
                    <div class="col-sm-4">  
                      <!-- <textarea class="form-control" rows="3" name="" id="kodekios" placeholder="Kodekios"><?php echo $kodekios; ?></textarea> -->
                      <input type="text" class="form-control" name="kodekios" id="kodekios" placeholder="kodekios" value="<?php echo $kodekios; ?>" style="width: 100%; height: 40px"/>
                    </div>
                            

                  </div>
                </div>						
						
						
						
              <div class="box-body">
                <div class="row">

                    <label class="col-sm-2 pull-left" align="right">Kodelos <?php echo form_error('kodelos') ?></label>
                    <div class="col-sm-4">  
                      <!-- <textarea class="form-control" rows="3" name="" id="kodelos" placeholder="Kodelos"><?php echo $kodelos; ?></textarea> -->
                      <input type="text" class="form-control" name="kodelos" id="kodelos" placeholder="kodelos" value="<?php echo $kodelos; ?>" style="width: 100%; height: 40px"/>
                    </div>

                  <label class="col-sm-2 pull-left" align="right">Kodearahan <?php echo form_error('kodearahan') ?></label>
                    <div class="col-sm-4">  
                      <input type="text" class="form-control" name="kodearahan" id="kodearahan" placeholder="kodearahan" value="<?php echo $kodearahan; ?>" style="width: 100%; height: 40px"/>
                    </div>

                  </div>
                </div>						
<!-- 
              <div class="box-body">
                <div class="row">

                            
                    <label class="col-sm-2 pull-left" align="right">Panjang <?php echo form_error('panjang') ?></label>
                    <div class="col-sm-4">  
                      <input type="text" class="form-control" name="panjang" id="panjang" placeholder="panjang" value="<?php echo $panjang; ?>" style="width: 100%; height: 40px"/>
                    </div>
                  </div>
                </div>  
 -->

                 
              <div class="box-body">
                <div class="row">
                            
                    <label class="col-sm-2 pull-left" align="right">Panjang <?php echo form_error('panjang') ?></label>
                    <div class="col-sm-1">  
                      <input type="text" class="form-control" name="panjang" id="panjang" placeholder="panjang" value="<?php tampil($panjang); ?>" style="width: 100%; height: 40px"/>
                    </div>

                      <label class="col-sm-1 pull-left" align="right">Lebar <?php echo form_error('lebar') ?></label>
                    <div class="col-sm-1">  
                      <input type="text" class="form-control" name="lebar" id="lebar" placeholder="lebar" value="<?php tampil($lebar); ?>" style="width: 100%; height: 40px"/>
                    </div>
                    <div class="col-sm-7"></div>
                  </div>
                </div>    

              <div class="box-body">
                <div class="row">                            
                    <label class="col-sm-2 pull-left" align="right">Nokios <?php echo form_error('nokios') ?></label>
                    <div class="col-sm-4">  
                      <input type="text" class="form-control" name="nokios" id="nokios" placeholder="nokios" value="<?php tampil($nokios); ?>" style="width: 100%; height: 40px"/>
                    </div>
                    <label class="col-sm-2 pull-left" align="right"></label>
                    <div class="col-sm-4">  
                      <!-- <input type="text" class="form-control" name="lebar" id="lebar" placeholder="lebar" value="<?php echo $lebar; ?>" style="width: 100%; height: 40px"/> -->
                    </div>

                  </div>
                </div>   

              <div class="box-body">
                <div class="row">
                  <label class="col-sm-2 pull-left" align="right">Kelaspasar <?php echo form_error('kelaspasar') ?></label>
                    <div class="col-sm-4">  
                      <input type="text" class="form-control" name="kelaspasar" id="kelaspasar" placeholder="kelaspasar" value="<?php tampil($kelaspasar); ?>" style="width: 100%; height: 40px"/>
                    </div>
                            
                    <label class="col-sm-2 pull-left" align="right">Tarifkelaspasar <?php echo form_error('tarifkelaspasar') ?></label>
                    <div class="col-sm-4">  
                      <input type="text" class="form-control" name="tarifkelaspasar" id="tarifkelaspasar" placeholder="tarifkelaspasar" value="<?php tampil($tarifkelaspasar); ?>" style="width: 100%; height: 40px"/>
                    </div>
                  </div>
                </div>   

<!--               <div class="box-body">
                <div class="row">
                  <label class="col-sm-2 pull-left" align="right">idpenyewa <?php //echo form_error('idpenyewa') ?></label>
                    <div class="col-sm-4">  
                  <select name="idpenyewa" id="idpenyewa" class="form-control select2" style="width: 100%; height: 40px;">

                    <?php
                    // $tbl_identitas_pengguna = $this->db->get('tbl_identitas_pengguna');
                    // foreach ($tbl_identitas_pengguna->result() as $m){
                    //     echo "<option value='$m->idpedagang' ";
                    //     // echo $m->id==$is_parent?'selected':'';
                    //     echo">". strtoupper($m->idpedagang) ." | ".  strtoupper($m->nama)."</option>";
                    // }
                    ?>
                </select>


                    </div>
                            
                    <label class="col-sm-2 pull-left" align="right">tglmulai <?php //echo form_error('tglmulai') ?></label>
                    <div class="col-sm-4">  
                      <input type="text" class="form-control" name="tglmulai" id="tglmulai" placeholder="tglmulai" value="<?php //echo $tglmulai; ?>" style="width: 100%; height: 40px"/>
                    </div>
                  </div>
                </div>   --> 

<!--               <div class="box-body">
                <div class="row">
                  <label class="col-sm-2 pull-left" align="right">tglakhir <?php //echo form_error('tglakhir') ?></label>
                    <div class="col-sm-4">  
                      <input type="text" class="form-control" name="tglakhir" id="tglakhir" placeholder="tglakhir" value="<?php //echo $tglakhir; ?>" style="width: 100%; height: 40px"/>
                    </div>
                          
                  </div>
                </div>   
 -->



                	    <input type="hidden" name="kodepasar" value="<?php echo $kodepasar; ?>" /> 
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