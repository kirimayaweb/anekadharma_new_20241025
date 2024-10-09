            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>IDENTITAS PEDAGANG</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">IDENTITAS PEDAGANG</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <!-- <h3 class="card-title">DataTable with minimal features & hover style</h3> -->
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th style="text-align:center" width="30px">NO</th>
                                                <th style="text-align:center">AKSI</th>
                                                <th style="text-align:center">NIK</th>
                                                <!--<th>Idpedagang</th>-->
                                                <th style="text-align:center">NAMA</th>
                                                <th style="text-align:center">ALAMAT</th>
                                                <th style="text-align:center">PEDUKUHAN</th>
                                                <th style="text-align:center">DESA</th>
                                                <th style="text-align:center">KECAMATAN</th>
                                                <th style="text-align:center">KABUPATEN</th>
                                                <th style="text-align:center">JENIS KELAMIN</th>
                                                <th style="text-align:center">STATUS</th>
                                                <th style="text-align:center">NO HP</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $start = 0;
                                            foreach ($tbl_identitas_pengguna_data as $tbl_identitas_pengguna) {
                                            ?>
                                                <tr>
                                                    <td><?php echo ++$start ?></td>
                                                    <td style="text-align:center" width="80px">
                                                        <?php
                                                        // echo anchor(site_url('index.php/tbl_identitas_pengguna/read/'.$tbl_identitas_pengguna->id),'<i class="fa fa-eye"></i>',array('title'=>'detail','class'=>'btn btn-danger btn-sm')); 
                                                        // echo '  '; 
                                                        echo anchor(site_url('index.php/tbl_identitas_pengguna/update/' . $tbl_identitas_pengguna->id), '<i class="fa fa-pencil-square-o">Ubah Data</i>', array('title' => 'edit', 'class' => 'btn btn-danger btn-sm'));
                                                        // echo '  '; 
                                                        // echo anchor(site_url('index.php/tbl_identitas_pengguna/delete/'.$tbl_identitas_pengguna->id),'<i class="fa fa-trash-o"></i>','title="delete" class="btn btn-danger btn-sm" onclick="javasciprt: return confirm(\'Are You Sure ?\')"'); 
                                                        ?>
                                                    </td>
                                                    <td><?php tampil($tbl_identitas_pengguna->nik) ?></td>
                                                    <!--<td><?php //echo $tbl_identitas_pengguna->idpedagang 
                                                            ?></td>-->
                                                    <td><?php tampil($tbl_identitas_pengguna->nama) ?></td>
                                                    <td><?php tampil($tbl_identitas_pengguna->alamat) ?></td>
                                                    <td><?php
                                                        tampil($tbl_identitas_pengguna->padukuhan);
                                                        echo ", ";
                                                        tampil($tbl_identitas_pengguna->rt);
                                                        ?></td>
                                                    <td><?php tampil($tbl_identitas_pengguna->desa) ?></td>
                                                    <td><?php tampil($tbl_identitas_pengguna->kecamatan) ?></td>
                                                    <td><?php tampil($tbl_identitas_pengguna->kabupaten) ?></td>
                                                    <td><?php tampil($tbl_identitas_pengguna->jeniskelamin) ?></td>
                                                    <td><?php tampil($tbl_identitas_pengguna->status) ?></td>
                                                    <td><?php tampil($tbl_identitas_pengguna->no_hp) ?></td>

                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                        <!-- <tfoot>
                                            <tr>
                                                <th style="text-align:center" width="30px">NO</th>
                                                <th style="text-align:center">AKSI</th>
                                                <th style="text-align:center">NIK</th>

                                                <th style="text-align:center">NAMA</th>
                                                <th style="text-align:center">ALAMAT</th>
                                                <th style="text-align:center">PEDUKUHAN</th>
                                                <th style="text-align:center">DESA</th>
                                                <th style="text-align:center">KECAMATAN</th>
                                                <th style="text-align:center">KABUPATEN</th>
                                                <th style="text-align:center">JENIS KELAMIN</th>
                                                <th style="text-align:center">STATUS</th>
                                                <th style="text-align:center">NO HP</th>
                                            </tr>
                                        </tfoot> -->
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>

                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- /.content -->