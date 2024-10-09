 	<style>

			.coulmnloginbox{

				/*background: #ecf0f5;*/

			}

			.loginbox{

				margin: 10px auto;

				/*width: 250px;*/

				position: relative;

				border: 2px solid #CACACC;

				background: #ffffff;

			}

			.loginTitle {

			  font-family: Arial;

			  font-style: Bold;

			  font-size: 30px;

			  margin: 0;

			  line-height: 2;

			  background-color: #55d268;

			}

			.btnLogin {

				font color:#ff00ff;

			  font-family: Arial;

			  font-style: Bold;

			  font-size: 20px;

				

				

			  border-radius: 5px;

			  -webkit-box-shadow: none;

			  box-shadow: none;

			  border: 6px solid transparent;				

				

			  background-color: #2C87C7;

			  border-color: #2C87C7;

			  

			}

			body{

				background-color:#ffffff;

			}

		</style>



 <body>







<?php
	// error_reporting(0);
	$sess_detailnik = $this->session->userdata('sess_detailnik');
?>



 

 <div class="row ">
 	<div class="col-sm-3 coulmnloginbox">
 	</div>
 	<div class="col-sm-6 coulmnloginbox">

			<div class="loginbox">

                <!--<div class="box-header with-border">-->

				<div class="loginTitle" align="center">

                  <strong>Identitas Pendaftar</strong>

                </div><!-- /.box-header -->



				<form action="<?php echo $action_nik; ?>" method="post">
					<div class="box-body">
						<div class="row">
							<label class="col-sm-3 pull-left" align="right">NIK</label>
							<div class="col-sm-5"><input type="text" class="form-control" name="nik" id="nik" placeholder="NIK"><?php 
							tampil(form_error('nik'))?></div>							
							<div class="col-sm-4"><button type="" name="button_cek_nik" class="btnLogin col-sm-12">CEK NIK</button>
							</div>
						</div>
                    </div>	
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
				</form>

				<form action="<?php echo $action_nik; ?>" method="post">			
				<input type="hidden" class="form-control" name="nik" id="nik" placeholder="nik"  value="<?php if(isset($sess_detailnik)){echo ($sess_detailnik['nik']);}else{echo "";}; ?>" >
                	<div class="box-body">
						<div class="row">
							<label class="col-sm-3 pull-left" align="right">Nama</label>
							<div class="col-sm-9"><input type="text" class="form-control" name="nama" id="nama" placeholder="Nama"  value="<?php if(isset($sess_detailnik)){tampil(($sess_detailnik['nama']));} ?>" ><?php tampil(form_error('nama'))?></div>
						</div>
                    </div>	



                   <div class="box-body">

						<div class="row">

							<label class="col-sm-3 pull-left" align="right">Tempat Lahir</label>

							<div class="col-sm-9"><input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" placeholder="tempat_lahir" value="<?php if(isset($sess_detailnik)){tampil(($sess_detailnik['tempatlahir']));} ?>" ><?php 
							tampil(form_error('tempat_lahir'))?></div>

						</div>

                    </div>	

                  <div class="box-body">

						<div class="row">

							<label class="col-sm-3 pull-left" align="right">Tanggal Lahir</label>

							<div class="col-sm-9"><input type="date" class="form-control" name="tgl_lahir" id="tgl_lahir" placeholder="tgl_lahir" value="<?php if(isset($sess_detailnik)){tampil(($sess_detailnik['tanggallahir']));} ?>"><?php tampil(form_error('tgl_lahir'))?></div>

						</div>

                    </div>	



                 <div class="box-body">

						<div class="row">

							<label class="col-sm-3 pull-left" align="right">Jenis Kelamin</label>

							<div class="col-sm-9">

								<!-- <input type="text" class="form-control" name="jenis_kelamin" placeholder="jenis kelamin"> -->

								  <select name="jenis_kelamin" id="jenis_kelamin">

								    <option value="<?php if(isset($sess_detailnik)){tampil(($sess_detailnik['tanggallahir']));} ?>"><?php  if(isset($sess_detailnik)){tampil(($sess_detailnik['jenis_klmin']));} ?></option>

								    <option value="laki-laki">Laki-Laki</option>

								    <option value="Perempuan">Perempuan</option>

								  </select>

								  <?php tampil(form_error('jenis_kelamin'))?>

							</div>

						</div>

                    </div>	



               <div class="box-body">

						<div class="row">

							<label class="col-sm-3 pull-left" align="right">alamat</label>

							<div class="col-sm-9"><input type="text" class="form-control" name="alamat" id="alamat" placeholder="alamat" value="<?php if(isset($sess_detailnik)){tampil(($sess_detailnik['alamat']));} ?>"><?php tampil(form_error('alamat'))?></div>

						</div>

                    </div>	



              <div class="box-body">

						<div class="row">

							<label class="col-sm-3 pull-left" align="right">Agama</label>

							<div class="col-sm-9">

								<!-- <input type="text" class="form-control" name="agama" placeholder="agama"> -->

								<select name="agama" id="agama">

								    <option value="<?php if(isset($sess_detailnik)){tampil(($sess_detailnik['agama']));} ?>"><?php if(isset($sess_detailnik)){tampil(($sess_detailnik['agama']));} ?></option>

								    <option value="islam">islam</option>

								    <option value="kristen">kristen</option>

								    <option value="katolik">katolik</option>

								    <option value="hindhu">hindhu</option>

								    <option value="konghucu">konghucu</option>

								  </select>

								<?php tampil(form_error('agama'))?></div>

						</div>

                    </div>	





             <div class="box-body">

						<div class="row">

							<label class="col-sm-3 pull-left" align="right">status perkawinan</label>

							<div class="col-sm-9"><input type="text" class="form-control" name="statusperkawinan" id="statusperkawinan" placeholder="statusperkawinan" value="<?php if(isset($sess_detailnik)){tampil(($sess_detailnik['status_kawin']));} ?>"><?php tampil(form_error('statusperkawinan'))?></div>

						</div>

                    </div>	

					            <div class="box-body">

									<div class="row">

										<label class="col-sm-3 pull-left" align="right">pekerjaan</label>

										<div class="col-sm-9"><input type="text" class="form-control" name="pekerjaan" id="pekerjaan" placeholder="pekerjaan" value="<?php if(isset($sess_detailnik)){tampil(($sess_detailnik['jenis_pkrjn']));} ?>"><?php tampil(form_error('pekerjaan'))?></div>

									</div>

			                    </div>	



			           <div class="box-body">

									<div class="row">

										<label class="col-sm-3 pull-left" align="right">kewarganegaraan</label>

										<div class="col-sm-9"><input type="text" class="form-control" name="kewarganegaraan" id="kewarganegaraan" placeholder="kewarganegaraan" ><?php tampil(form_error('kewarganegaraan'))?></div>

									</div>

			                    </div>	



			          <div class="box-body">

									<div class="row">

										<label class="col-sm-3 pull-left" align="right">No tlp</label>

										<div class="col-sm-9"><input type="text" class="form-control" name="no_tlp" id="no_tlp" placeholder="no_tlp"><?php tampil(form_error('no_tlp'))?></div>

									</div>

			                    </div>	

			</div>

	</div>
 	
 	<div class="col-sm-3 coulmnloginbox">
 	</div>

 </div>

		<div class="row ">

				<div class="col-sm-1 coulmnloginbox">		

				</div>

				<div class="col-sm-10 coulmnloginbox">

								<div class="box-body">

										<button type="submit" name="daftar" class="btnLogin col-sm-12">Daftar</button>				 			

								</div><!-- /.box-footer -->

				</div>

				<div class="col-sm-1 coulmnloginbox">

				</div>

		</div>	 
<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
 </form>









   </body>