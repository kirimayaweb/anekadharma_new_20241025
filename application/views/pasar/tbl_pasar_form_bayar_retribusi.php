
        <section class='content'>
          <div class='row'>
            <div class='col-xs-12'>
              <div class='box'>
                <div class='box-header'>
                
                  <h3 class='box-title'>BAYAR RETRIBUSI</h3>
                      <div class='box box-primary'>
                        <form action="<?php echo $action; ?>" method="post"><table class='table table-bordered'>
						
              <div class="box-body">
                <div class="row">                           
                    <label class="col-sm-2 pull-left" align="right">Kode Retribusi </label>
                    <label class="col-sm-2 pull-left" align="right"> : <?php tampil($koderetribusi); ?></label>
                  </div>
                </div>						
						
             <div class="box-body">
                <div class="row">
                  <label class="col-sm-2 pull-left" align="right">Namapasar </label>
                  <label class="col-sm-2 pull-left" align="right"> : <?php tampil($namapasar); ?></label>                            
                    <label class="col-sm-2 pull-left" align="right">Kode Pasar</label>
                    <label class="col-sm-2 pull-left" align="right"> : <?php tampil($kode); ?></label>
                  <label class="col-sm-2 pull-left" align="right">Kodekios</label>
                  <label class="col-sm-2 pull-left" align="right"> : <?php tampil($kodekios); ?></label>
                  </div>
                </div>						

				<div class="box-body">
                <div class="row">				
                    <label class="col-sm-2 pull-left" align="right">Kodelos</label>
                    <label class="col-sm-2 pull-left" align="right"> : <?php tampil($kodelos); ?></label>
                  <label class="col-sm-2 pull-left" align="right">Kodearahan</label>
                  <label class="col-sm-2 pull-left" align="right"> : <?php tampil($kodearahan); ?></label>
                  </div>
                </div>   
              <div class="box-body">
                <div class="row">                            
                    <label class="col-sm-2 pull-left" align="right">Panjang</label>
                    <label class="col-sm-2 pull-left" align="right"> : <?php tampil($panjang); ?></label>
                      <label class="col-sm-2 pull-left" align="right">Lebar</label>
                      <label class="col-sm-2 pull-left" align="right"> : <?php tampil($lebar); ?></label>
                  </div>
                </div>    

				<div class="box-body">
                <div class="row">
				
                    <label class="col-sm-2 pull-left" align="right">Nomor kios</label>
                    <label class="col-sm-2 pull-left" align="right"> : <?php tampil($nokios); ?></label>
                    <!--<div class="col-sm-4">  
                      <input type="text" class="form-control" name="nokios" id="nokios" placeholder="nokios" value="<?php echo $nokios; ?>" style="width: 100%; height: 40px" disabled/>
                    </div>-->
					
                  <label class="col-sm-2 pull-left" align="right">Kelaspasar</label>
                  <label class="col-sm-2 pull-left" align="right"> : <?php tampil($kelaspasar); ?></label>
                    <!--<div class="col-sm-4">  
                      <input type="text" class="form-control" name="kelaspasar" id="kelaspasar" placeholder="kelaspasar" value="<?php echo $kelaspasar; ?>" style="width: 100%; height: 40px" disabled/>
                    </div>-->
                            
                    <label class="col-sm-2 pull-left" align="right">Tarifkelaspasar</label>
                    <label class="col-sm-2 pull-left" align="right"> : <?php tampil($tarifkelaspasar); ?></label>
                    <!--<div class="col-sm-4">  
                      <input type="text" class="form-control" name="tarifkelaspasar" id="tarifkelaspasar" placeholder="tarifkelaspasar" value="<?php echo $tarifkelaspasar; ?>" style="width: 100%; height: 40px" disabled/>
                    </div>-->
					
					
					
                  </div>
                </div>   

              <div class="box-body">
                <div class="row">
                  <label class="col-sm-2 pull-left" align="right">id pedagang</label>
                  <label class="col-sm-2 pull-left" align="right"> : <?php tampil($idpenyewa); ?></label>
                    <!--<div class="col-sm-4">  
                  <select name="idpenyewa" id="idpenyewa" class="form-control select2" style="width: 100%; height: 40px;" disabled>
                    <?php
   /*                  $tbl_identitas_pengguna = $this->db->get('tbl_identitas_pengguna');
                    foreach ($tbl_identitas_pengguna->result() as $m){
                        echo "<option value='$m->idpedagang' ";
                        echo">". strtoupper($m->idpedagang) ." | ".  strtoupper($m->nama)."</option>";
                    }*/ 
                    ?>
                </select>
-->
                    <label class="col-sm-2 pull-left" align="right">tglmulai</label>
                    <label class="col-sm-2 pull-left" align="right"> : <?php tampil($tglmulai); ?></label>
                    <!--<div class="col-sm-4">  
                      <input type="text" class="form-control" name="tglmulai" id="tglmulai" placeholder="tglmulai" value="<?php echo $tglmulai; ?>" style="width: 100%; height: 40px"  disabled/>
                    </div>				-->
                  <label class="col-sm-2 pull-left" align="right">tglakhir</label>
                  <label class="col-sm-2 pull-left" align="right"> : <?php tampil($tglakhir); ?></label>
                    <!--<div class="col-sm-4">  
                      <input type="text" class="form-control" name="tglakhir" id="tglakhir" placeholder="tglakhir" value="<?php echo $tglakhir; ?>" style="width: 100%; height: 40px" disabled/>
                    </div>-->
                    </div>                          
                  </div>
                <!--</div>   -->

<!--              <div class="box-body">
                <div class="row">

                  </div>
                </div>  --> 


              <div class="box-body">
                <div class="row">
                  <label class="col-sm-2 pull-left" align="right">BAYAR</label>
                    <div class="col-sm-4">  
                      <input type="text" class="form-control" name="bayar" id="bayar" placeholder="bayar" value="" style="width: 100%; height: 40px"/>
                    </div>
                  <label class="col-sm-2 pull-left" align="right">NAMA TAGIHAN</label>
                    <div class="col-sm-4">  
                      <input type="text" class="form-control" name="namatagihan" id="namatagihan" placeholder="nama tagihan" value="" style="width: 100%; height: 40px"/>
                    </div>
                  </div>
                </div>   

              <div class="box-body">
                <div class="row">
                  <label class="col-sm-2 pull-left" align="right">Tgl Awal</label>
                    <div class="col-sm-4">  
                      <input type="text" class="form-control pull-right" id="tgl_awal" name="tgl_awal" value="">
                    </div>
                  <label class="col-sm-2 pull-left" align="right">Tgl Akhir</label>
                    <div class="col-sm-4">  
                      <input type="text" class="form-control pull-right" id="tgl_akhir" name="tgl_akhir" value="">
                    </div>
                  </div>
                </div>   




                	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 



                	    <tr>
						
						<td colspan='12' align="center">
						<button type="submit" class="btn btn-primary"><?php echo $button ?></button>
						
                	    <a href="<?php echo site_url('index.php/Tbl_pasar/tagihan_pasar') ?>" class="btn btn-default">Batal</a>
                	    
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