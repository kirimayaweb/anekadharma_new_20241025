<div class="content-wrapper">

<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>



    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <!-- <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard v1</li> -->
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section class="content">
        <div class="box box-warning box-solid">

            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">


                        <div class="row">
                            <div class="col-3"><h3 class="card-title">DATA MAPEL : <?php 
                            
                            if($get_data_tingkat_selected){
                                echo $get_data_tingkat_selected; 
                            }else{
                                echo "Tingkat belum di pilih";
                            }
                            ?> </h3></div>
                            <div class="col-3">

                                <form action="<?php 
                                                    $actionUP = site_url('tbl_produk_mapel_referensi/data_mapel/' ); 

                                                    echo $actionUP; 
                                                ?>" method="post">
                                        
                                    

                                        <select name="level_sekolah" id="level_sekolah" class="form-control select2" style="width: 100%; height: 40px;">
                                                <option value="">Pilih Tingkat</option>
                                                <?php																								
                                                    // $sql = "select tingkat from  tbl_produk_mapel_referensi group by tingkat order by  tingkat asc";
                                                    // foreach ($this->db->query($sql)->result() as $m) {
                                                    //     echo "<option value='$m->tingkat' ";echo ">  " . strtoupper($m->tingkat) . "</option>";
                                                    // }

                                                    foreach ($this->Auto_load_model->cek_tingkat_by_user() as $m) {
                                                        echo "<option value='$m->tingkat' ";echo ">  " . strtoupper($m->tingkat) . "</option>";
                                                    }

                                                ?>
                                        </select>

                                        <button type="submit" class="btn btn-danger"> Cari</button>
                                
                                </form>

                            </div>
                            <div class="col-3">
                                
                            </div>


                        </div>

                    </div>
                    <br />

                    <div class="row">
                        <div class="col-2" align="right">
                            <?php echo anchor(site_url('Tbl_produk_mapel_referensi/create'), '<i class="fa fa-wpforms" aria-hidden="true"></i> Tambah Data Mapel', 'class="btn btn-danger btn-sm"'); ?>
                        </div>
                        <div class="col-2" align="left">
                            <!-- <form action="<?php 
                                    // $action_TAMBAH_TINGKAT="tambah_tingkat";
                                    // echo $action_TAMBAH_TINGKAT; 
                                    ?>" method="post">
								    <button type="submit" class="btn btn-danger"><i class="fa fa-floppy-o"></i> Tambah Tingkat</button>
							</form> -->

                            <?php echo anchor(site_url('Tbl_produk_mapel_referensi/tambah_tingkat'), '<i class="fa fa-wpforms" aria-hidden="true"></i> Tambah Tingkat/Kelompok', 'class="btn btn-danger btn-sm"'); ?>
                        </div>
                        <div class="col-2"></div>
                        <div class="col-6"></div>
                    </div>

                    <div class="card-body">

                        <!-- tabel data -->
                            
                            <div class="row">
                                <div class="col-2">
                                    <h2>DATA MAPEL : </h2>
                                </div>
                                <div class="col-2">
                                    
                                </div>
                                <div class="col-2"></div>
                                <div class="col-6"></div>
                            </div>

                            <table>
                                <!-- HEAD -->
                                <tr>
                                    <th style="text-align:center;font-size:1vw" width="10px">No. Urutan</th>
                                    <th style="text-align:center;font-size:1vw" width="60px">Action</th>
                                    <th width="300px">Tingkat</th>
                                    <th>Mapel [Kelas] </th>
                                    <th>Halaman</th>
                                    <th style="text-align:center;font-size:1vw" width="200px">Update</th>
                                </tr>
                                <!-- END HEAD -->

                                <?php
                                    $start = 0;
                                    foreach ($tbl_produk_mapel_referensi_data as $tbl_produk_mapel_referensi_data_list) {
                                ?>
                                <!-- CONTENT -->
                                <tr>
                                    <td><?php echo ++$start; ?></td>
                                    <td>
                                        <?php 


                                            

                                            if($start==1){echo "-";}else{
                                        ?>
                                            <form action="<?php 
                                                                $actionUP = site_url('tbl_produk_mapel_referensi/update_nomor_urut_NAIK/'. $tbl_produk_mapel_referensi_data_list->tingkat . '/' . $tbl_produk_mapel_referensi_data_list->id .'/'. $get_ID_Atas);
                                                            
                                                                echo $actionUP; 
                                                            ?>" method="post">
                                                <button type="submit" class="btn btn-outline-info btn-block btn-flat">
                                                    <i class=""></i> Up
                                                </button>
                                            </form>    
                                        <?php

                                        
                                            } 

                                            //dapatkan id setelah terjadi looping dari sejak awal list data
                                            $get_ID_Atas = $tbl_produk_mapel_referensi_data_list->id;
                                        ?>
                                        
                                            <form action="<?php 
                                                                $actionUP = site_url('tbl_produk_mapel_referensi/update_nomor_urut_TURUN/'. $tbl_produk_mapel_referensi_data_list->tingkat . '/' .$tbl_produk_mapel_referensi_data_list->id);
                                                            
                                                                echo $actionUP; 
                                                            ?>" method="post">
                                                <button type="submit" class="btn btn-outline-info btn-block btn-flat">
                                                    <i class=""></i> Down
                                                </button>
                                            </form>

                                    
                                    </td>
                                
                                    <td><?php echo $tbl_produk_mapel_referensi_data_list->tingkat ?></td>
                                    <td><?php echo $tbl_produk_mapel_referensi_data_list->mapel_display ?></td>
                                    <td><?php echo $tbl_produk_mapel_referensi_data_list->halaman ?></td>

                                    <td>
                                        <?php 
                                  

                                        echo anchor(site_url('tbl_produk_mapel_referensi/update/'.$tbl_produk_mapel_referensi_data_list->id),'<i class="fa fa-pencil-square-o" aria-hidden="true">Ubah</i>','class="btn btn-warning btn-sm"');
                                        echo "<br/>";
                                        // echo anchor(site_url('tbl_produk_mapel_referensi/delete/'.$tbl_produk_mapel_referensi_data_list->id),'<i class="fa fa-pencil-square-o" aria-hidden="true">Hapus</i>','class="btn btn-danger btn-sm"');
                                        echo anchor(site_url('#'),'<i class="fa fa-pencil-square-o" aria-hidden="true">Hapus Mapel</i>','class="btn btn-danger btn-sm"');
                                        
                                        ?> 
                                    </td>
                                </tr>
                                <!-- END CONTENT -->
                                <?php 
                                    } 
                                ?>

                            </table>
                        <!-- tabel data -->

                    </div>

                </div>

            </div>

        </div>
    </section>


        <!-- ============================================================================= -->




</div>

