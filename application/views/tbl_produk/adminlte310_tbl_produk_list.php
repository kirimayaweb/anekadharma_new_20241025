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
                        <h3 class="card-title">DATA PRODUK</h3>
                    </div>
                    <br />

                    <div class="row">
                        <div class="col-2"><?php echo anchor(site_url('tbl_produk/create'), '<i class="fa fa-wpforms" aria-hidden="true"></i> Tambah Data Produk', 'class="btn btn-danger btn-sm"'); ?></div>
                        <div class="col-2"><?php echo anchor(site_url('tbl_produk/excel'), '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Ms Excel', 'class="btn btn-success btn-sm"'); ?></div>
                        <div class="col-2"><?php echo anchor(site_url('tbl_produk/word'), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Export Ms Word', 'class="btn btn-primary btn-sm"'); ?></div>
                        <div class="col-6"></div>
                    </div>

                    <div class="card-body">


                        <!-- <table id="example1" class="table table-bordered table-striped"> -->
                        <table id="exampleFreeze" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th style="text-align:center" width="150px">Action</th>
                                    <th>Proses Transaksi</th>
                                    <th>Kode Produk</th>
                                    <th>Tingkat</th>
                                    <th>Kelas</th>
                                    <th>Mapel</th>
                                    <th>Semester</th>
                                    <th>Halaman</th>
                                    <!-- <th>Keterangan</th> -->
                                </tr>
                            </thead>

                            <tbody>

                                <?php
                                $start = 0;
                                foreach ($tbl_produk_data as $tbl_produk) {
                                ?>
                                    <tr>
                                        <td width="10px"><?php echo ++$start ?></td>
                                        <td style="text-align:center">
                                            <?php
                                            echo anchor(site_url('tbl_produk/read/' . $tbl_produk->uuid_produk), '<i class="fa fa-eye" aria-hidden="true">Detail</i>', 'class="btn btn-success btn-sm"');
                                            echo '  ';
                                            echo anchor(site_url('tbl_produk/update/' . $tbl_produk->uuid_produk), '<i class="fa fa-pencil-square-o" aria-hidden="true">Ubah Data</i>', 'class="btn btn-warning btn-sm"');
                                            echo '  ';
                                            echo anchor(site_url('tbl_produk/delete/' . $tbl_produk->uuid_produk), '<i class="fa fa-trash-o" aria-hidden="true">Hapus Data</i>', 'class="btn btn-danger btn-sm" Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
                                            ?>
                                        </td>
                                        <td style="text-align:center">
                                            <?php

                                            echo anchor(site_url('Trans_cetak/create/' . $tbl_produk->uuid_produk), '<i class="fa fa-pencil-square-o" aria-hidden="true"> PESAN CETAK </i>', 'class="btn btn-success btn-sm"');
                                            echo "<br/>";
                                            if (($tbl_produk->uuid_cover_produk) == "naskah") {
                                                echo "Naskah";
                                            } else {
                                                echo "Cover";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php //echo $tbl_produk->tingkat 
                                            ?>
                                            <img src="<?php echo site_url(); ?>/Barcode/set_barcode/<?php echo $tbl_produk->kode_produk_produk; ?>">
                                        </td>
                                        <td><?php echo $tbl_produk->tingkat_produk ?></td>
                                        <td><?php echo $tbl_produk->kelas_produk ?></td>
                                        <td><?php echo $tbl_produk->mapel_produk ?></td>
                                        <td><?php echo $tbl_produk->semester_produk ?></td>
                                        <td><?php echo $tbl_produk->halaman_produk ?></td>
                                        <!-- <td><?php //echo $tbl_produk->keterangan 
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


