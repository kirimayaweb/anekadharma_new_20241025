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
                        <h3 class="card-title">DATA CETAK</h3>
                    </div>
                    <br />

                    <div class="row">
                        <div class="col-2">
                            <?php echo anchor(site_url('Tbl_company_cetak/create'), '<i class="fa fa-wpforms" aria-hidden="true"></i> Tambah Perusahaan Cetak', 'class="btn btn-danger btn-sm"'); ?>

                        </div>
                        <div class="col-10" align="right"></div>

                    </div>

                    <div class="card-body">

                        <table id="exampleFreeze" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <!-- <th>Uuid Company Cetak</th> -->
                                    <!-- <th>Date Input</th> -->
                                    <!-- <th>Id User Input</th> -->
                                    <th>Nama Perusahaan</th>
                                    <th>Alamat Perusahaan</th>
                                    <th>Notelp Perusahaan</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $start = 0;
                                foreach ($tbl_company_cetak_data as $tbl_company_cetak) {
                                ?>
                                    <tr>
                                        <td width="10px"><?php echo ++$start ?></td>
                                        <!-- <td><?php //echo $tbl_company_cetak->uuid_company_cetak 
                                                    ?></td> -->
                                        <!-- <td><?php //echo $tbl_company_cetak->date_input 
                                                    ?></td> -->
                                        <!-- <td><?php //echo $tbl_company_cetak->id_user_input 
                                                    ?></td> -->
                                        <td><?php echo $tbl_company_cetak->nama_perusahaan ?></td>
                                        <td><?php echo $tbl_company_cetak->alamat_perusahaan ?></td>
                                        <td><?php echo $tbl_company_cetak->notelp_perusahaan ?></td>
                                        <td><?php echo $tbl_company_cetak->keterangan ?></td>
                                        <td style="text-align:center" width="200px">
                                            <?php
                                            echo anchor(site_url('tbl_company_cetak/read/' . $tbl_company_cetak->id), '<i class="fa fa-eye" aria-hidden="true"></i>', 'class="btn btn-danger btn-sm"');
                                            echo '  ';
                                            echo anchor(site_url('tbl_company_cetak/update/' . $tbl_company_cetak->id), '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', 'class="btn btn-danger btn-sm"');
                                            echo '  ';
                                            echo anchor(site_url('tbl_company_cetak/delete/' . $tbl_company_cetak->id), '<i class="fa fa-trash-o" aria-hidden="true"></i>', 'class="btn btn-danger btn-sm" Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
                                            ?>
                                        </td>
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

