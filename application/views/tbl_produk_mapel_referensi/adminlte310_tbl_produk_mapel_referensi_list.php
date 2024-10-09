<div class="content-wrapper">


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
                        <h3 class="card-title">DATA MAPEL</h3>
                    </div>
                    <br />

                    <div class="row">
                        <div class="col-2">
                            <?php echo anchor(site_url('Tbl_produk_mapel_referensi/create'), '<i class="fa fa-wpforms" aria-hidden="true"></i> Tambah Data Mapel', 'class="btn btn-danger btn-sm"'); ?>
                        </div>
                        <div class="col-2">
                            <?php //echo anchor(site_url('trans_bayar/word'), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Export Ms Word', 'class="btn btn-primary btn-sm"'); 
                            ?>
                        </div>
                        <div class="col-2"></div>
                        <div class="col-6"></div>
                    </div>


                    <div class="card-body">



                        <!-- <table id="example1" class="table table-bordered table-striped"> -->
                        <table id="exampleFreeze" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Action</th>
                                    <!-- <th>Action</th> -->
                                    <!-- <th>Uuid Produk</th>
                                    <th>Kode Produk</th>
                                    <th>Date Input</th> -->
                                    <!-- <th>Id User Input</th> -->
                                    <th>Tingkat</th>
                                    <th>Mapel</th>
                                    <!-- <th>Kelas</th>
                                    <th>Tahun</th>
                                    <th>Semester</th> -->
                                    <th>Halaman</th>
                                    <!-- <th>Uuid Cover Produk</th> -->
                                    

                                </tr>
                            </thead>

                            <tbody>

                                <?php
                                $start = 0;
                                foreach ($tbl_produk_mapel_referensi_data as $tbl_produk_mapel_referensi_data_list) {
                                ?>
                                    <tr>
                                        <td width="10px"><?php echo ++$start ?></td>

                                        <td style="text-align:center" width="100px">
                                            <?php 
                                            //echo anchor(site_url('tbl_produk_mapel_referensi/read/'.$tbl_produk_mapel_referensi->id),'<i class="fa fa-eye" aria-hidden="true"></i>','class="btn btn-danger btn-sm"'); 
                                            //echo '  '; 
                                            echo anchor(site_url('tbl_produk_mapel_referensi/update/'.$tbl_produk_mapel_referensi_data_list->id),'<i class="fa fa-pencil-square-o" aria-hidden="true">Ubah</i>','class="btn btn-warning btn-sm"'); 
                                            //echo '  '; 
                                            //echo anchor(site_url('tbl_produk_mapel_referensi/delete/'.$tbl_produk_mapel_referensi->id),'<i class="fa fa-trash-o" aria-hidden="true"></i>','class="btn btn-danger btn-sm" Delete','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'); 
                                            ?>
                                        </td>

                                        <!-- <td style="text-align:center" width="200px"> -->
                                        <?php

                                        // echo anchor(site_url('tbl_produk_mapel_referensi/update_by_id/' . $tbl_produk_mapel_referensi_data_list->id), '<i class="fa fa-pencil-square-o" aria-hidden="true">Ubah Data</i>', 'class="btn btn-warning btn-sm"');
                                        // echo '  ';
                                        // echo anchor(site_url('tbl_produk_mapel_referensi/delete/' . $tbl_produk_mapel_referensi_data_list->id), '<i class="fa fa-trash-o" aria-hidden="true">Hapus Data</i>', 'class="btn btn-danger btn-sm" Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');


                                        ?>
                                        <!-- </td> -->


                                        <!-- <td><?php //echo $tbl_produk_mapel_referensi_data_list->uuid_produk 
                                                    ?></td>
                                        <td><?php //echo $tbl_produk_mapel_referensi_data_list->kode_produk 
                                            ?></td>
                                        <td><?php //echo $tbl_produk_mapel_referensi_data_list->date_input 
                                            ?></td> -->
                                        <!-- <td><?php //echo $tbl_produk_mapel_referensi_data_list->id_user_input 
                                                    ?></td> -->
                                        <td><?php echo $tbl_produk_mapel_referensi_data_list->tingkat ?></td>
                                        <td><?php echo $tbl_produk_mapel_referensi_data_list->mapel_display ?></td>
                                        <!-- <td><?php //echo $tbl_produk_mapel_referensi_data_list->kelas 
                                                    ?></td> -->
                                        <!-- <td><?php //echo $tbl_produk_mapel_referensi_data_list->tahun 
                                                    ?></td> -->
                                        <!-- <td><?php //echo $tbl_produk_mapel_referensi_data_list->semester 
                                                    ?></td> -->
                                        <td><?php echo $tbl_produk_mapel_referensi_data_list->halaman ?></td>
                                        <!-- <td><?php //echo $tbl_produk_mapel_referensi_data_list->uuid_cover_produk 
                                                    ?></td> -->
                                        <!-- <td><?php //echo $tbl_produk_mapel_referensi_data_list->keterangan 
                                                    ?></td> -->
                          
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>


                            <!-- <tfoot>
                                    <tr>
                                        <th>Rendering engine</th>
                                        <th>Browser</th>
                                        <th>Platform(s)</th>
                                        <th>Engine version</th>
                                        <th>CSS grade</th>
                                    </tr>
                                </tfoot> -->
                        </table>

                    </div>

                    <!-- /.card-body -->
                </div>

            </div>

        </div>
    </section>
</div>

