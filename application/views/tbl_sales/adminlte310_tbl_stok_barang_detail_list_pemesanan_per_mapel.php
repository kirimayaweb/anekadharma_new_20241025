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
                        <div class="row">

                            <!-- <form action="<?php //echo $action; 
                                                ?>" method="post"> -->

                            <div class="col-8 card-title">

                                <!-- <h3 class="card-title"> -->
                                DATA PEMESANAN PER MAPEL : <?php
                                                            echo "<strong>" . $nama_sales_selected . "</strong>";
                                                            ?>

                                <!-- </h3> -->
                            </div>
                            <div class="col-2 card-title">

                                <!-- <select name="level_sekolah" id="level_sekolah" class="form-control select2" style="width: 200px; height: 10px;">
                                        <option value="">Pilih Tingkat</option>
                                        <option value="MA">MA</option>
                                        <option value="MTS">MTS</option>
                                        <option value="MI">MI</option>
                                        <option value="SMP">SMP</option>
                                        <option value="SD">SD</option>
                                    </select>
                                    <button type="submit" class="btn btn-danger"> Cari</button> -->
                            </div>
                            <!-- <div class="col-md-2  card-title"></div> -->
                            <div class="col-2  card-title">

                            </div>

                            <!-- </form> -->
                        </div>

                    </div>
                    <br />

                    <div class="row">
                        <div class="col-2"><?php
                                            // echo anchor(site_url('Trans_penjualan/sett_input_penjualan'), '<i class="fa fa-wpforms" aria-hidden="true"></i> Tambah Data PENJUALAN', 'class="btn btn-danger btn-sm"'); 
                                            ?></div>
                        <div class="col-2" align="right"><?php
                                                            // echo anchor(site_url('Trans_penjualan/excel_penjualan/' . $tingkat_selected), '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Ms Excel, REKAP PENJUALAN :' .
                                                            // $tingkat_selected . ' Tahun : ' . $_SESSION['thn_selected'] . ' Semester :' . $_SESSION['semester_selected'], 'class="btn btn-success btn-sm"'); 
                                                            ?></div>

                        <!-- <div class="col-2" align="left"><?php //echo anchor(site_url('tbl_stok_barang_detail/word'), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Export Ms Word', 'class="btn btn-primary btn-sm"'); 
                                                                ?></div> -->
                        <!-- <div class="col-2" align="right"><?php //echo anchor(site_url('#'), '<i class="fa fa-file-excel-o" aria-hidden="true"></i> PO Cetak', 'class="btn btn-success btn-sm"'); 
                                                                ?></div> -->
                        <!-- <div class="col-2" align="left"><?php //echo anchor(site_url('#'), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Finishing', 'class="btn btn-primary btn-sm"'); 
                                                                ?></div> -->
                        <!-- <div class="col-4"></div> -->

                    </div>

                    <div class="card-body">

                        <!-- <table id="example1" class="table table-bordered table-striped"> -->
                        <table id="exampleFreeze" class="table table-bordered table-striped">
                            <thead>

                                <tr>
                                    <th style="font-size:0.7vw" width="10px">No</th>

                                    <!-- <th style="font-size:0.7vw" width="110px">KELAS</th> -->
                                    <th style="font-size:0.7vw" width="110px">MAPEL</th>
                                    <th style="font-size:0.7vw">JUMLAH PEMESANAN</th>

                                </tr>
                            </thead>

                            <tbody>

                                <?php
                                $start = 0;
                                $TOTAL_PEMESANAN_64 = 0;
                                $TOTAL_PEMESANAN_96 = 0;

                                foreach ($mapel_list as $mapel_list_list) {
                                ?>
                                    <tr>
                                        <td style="font-size:0.7vw" width="10px"> <?php echo ++$start ?> </td>
                                        <!-- <td style="font-size:0.7vw" width="10px"> <?php //echo $mapel_list_list->kelas_produk; 
                                                                                        ?> </td> -->
                                        <td style="font-size:0.7vw" width="10px"> <?php echo $mapel_list_list->mapel_produk; ?></td>
                                        <!-- <td style="font-size:0.7vw" width="10px"> </td> -->

                                        <?php
                                        $total_mapel = 0;
                                        foreach ($data_pemesanan_per_mapel as $data_pemesanan_per_mapel_list) {

                                            $X_PEMESANAN_64 = $mapel_list_list->uuid_produk . "_TOTAL_PEMESANAN_64";
                                            $X_PEMESANAN_96 = $mapel_list_list->uuid_produk . "_TOTAL_PEMESANAN_96";

                                            if (!empty($data_pemesanan_per_mapel_list->$X_PEMESANAN_64)  or !empty($data_pemesanan_per_mapel_list->$X_PEMESANAN_96)) {

                                                echo "<td style='text-align:right;font-size:0.7vw'>" . nominal(($data_pemesanan_per_mapel_list->$X_PEMESANAN_64) + ($data_pemesanan_per_mapel_list->$X_PEMESANAN_96)) . "</td>";
                                                $TOTAL_PEMESANAN_64 = $TOTAL_PEMESANAN_64 + $data_pemesanan_per_mapel_list->$X_PEMESANAN_64;
                                                $TOTAL_PEMESANAN_96 = $TOTAL_PEMESANAN_96 + $data_pemesanan_per_mapel_list->$X_PEMESANAN_96;
                                            } else {
                                                echo "<td style='text-align:right;font-size:0.7vw'>" . 0 . "</td>";
                                            }
                                        }
                                        ?>

                                    </tr>
                                <?php
                                }
                                ?>

                            </tbody>


                            <tfoot>

                                <tr>
                                    <th style="text-align:center;font-size:0.7vw" width="110px" colspan="2">TOTAL 64</th>
                                    <th style="text-align:right;font-size:0.7vw"><?php echo nominal($TOTAL_PEMESANAN_64) ?></th>

                                </tr>
                                <tr>
                                    <th style="text-align:center;font-size:0.7vw" width="110px" colspan="2">TOTAL 96</th>
                                    <th style="text-align:right;font-size:0.7vw"><?php echo nominal($TOTAL_PEMESANAN_96) ?></th>

                                </tr>
                            </tfoot>
                        </table>
                        <input type="Button" value="<<Kembali" onclick="history.back()">

                    </div>
                    <div>

                    </div>

                    <!-- /.card-body -->
                </div>

            </div>

        </div>
    </section>
</div>


