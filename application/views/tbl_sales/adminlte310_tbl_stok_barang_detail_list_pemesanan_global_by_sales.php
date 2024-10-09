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
                                DATA PEMESANAN : <?php
                                                    echo $nama_sales_selected;
                                                    // echo "<strong>";
                                                    //                     if (is_null($tingkat_selected)) {
                                                    //                         echo "ALL [ ";
                                                    //                     } else {
                                                    //                         echo $tingkat_selected . " [ ";
                                                    //                     }
                                                    //                     echo $_SESSION['thn_selected'] . " / " . $_SESSION['semester_selected'] . " ]</strong>";
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
                                            echo anchor(site_url('Trans_pemesanan/create_setting/' . $uuid_sales_selected), '<i class="fa fa-wpforms" aria-hidden="true"></i> Tambah Data PEMESANAN', 'class="btn btn-danger btn-sm"');
                                            ?>
                        </div>

                    </div>
                    <div class="card-body">

                        <!-- <table id="example1" class="table table-bordered table-striped"> -->
                        <table id="exampleFreeze" class="table table-bordered table-striped" style="width:100%">
                            <thead>

                                <tr>
                                    <th style="font-size:0.7vw" width="10px">No</th>

                                    <th style="font-size:0.7vw" width="110px">JENIS PESANAN</th>

                                    <!-- PEMESANAN PER COVER -->
                                    <?php
                                    foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {
                                        echo "<th style='text-align:right;font-size:0.7vw'>" . $tbl_stok_barang_detail_cover_list->nama_cover . "</th>";
                                    }
                                    ?>
                                    <th style="font-size:0.7vw">TOTAL PESAN</th>

                                    <!-- TERKIRIM -->
                                    <?php
                                    foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {
                                        echo "<th style='text-align:right;font-size:0.7vw'>" . $tbl_stok_barang_detail_cover_list->nama_cover . "</th>";
                                    }
                                    ?>
                                    <th style="font-size:0.7vw">TOTAL KIRIM</th>

                                    <!-- KEKURANGAN -->
                                    <?php
                                    foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {
                                        echo "<th style='text-align:right;font-size:0.7vw'>" . $tbl_stok_barang_detail_cover_list->nama_cover . "</th>";
                                    }
                                    ?>
                                    <th style="font-size:0.7vw">BELUM TERKIRIM</th>

                                    <th style="font-size:0.7vw" >DETAIL</th>


                                </tr>
                            </thead>

                            <tbody>

                                <?php
                                $start = 0;
                                $total_PEMESANAN_PER_mapel = 0;
                                $total_PENJUALAN_PER_mapel = 0;
                                foreach ($tbl_stok_barang_detail_data as $tbl_stok_barang_detail) {
                                ?>
                                    <tr>
                                        <td style="font-size:0.7vw" width="10px"><?php echo ++$start ?></td>
                                        <td style="font-size:0.7vw" width="10px"> <?php echo $tbl_stok_barang_detail->tingkat_name; ?> </td>

                                        <!-- PEMESANAN PER COVER -->
                                        <?php
                                        $total_PEMESANAN_PER_mapel = 0;
                                        foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {

                                            $X_COVER = $tbl_stok_barang_detail_cover_list->uuid_cover_produk . "_PEMESANAN_BARU_" . $tbl_stok_barang_detail->tingkat;
                                            echo "<td style='text-align:right;font-size:0.7vw'>" . nominal($tbl_stok_barang_detail->$X_COVER) . "</td>";
                                            $total_PEMESANAN_PER_mapel = $total_PEMESANAN_PER_mapel + $tbl_stok_barang_detail->$X_COVER;
                                        }
                                        ?>
                                        <!-- END OF PEMESANAN PER COVER -->

                                        <!-- TOTAL PEMESANAN -->
                                        <td style="font-size:0.7vw" align="right"><?php echo nominal($total_PEMESANAN_PER_mapel); ?></td>

                                        <!-- <td style="font-size:0.7vw" align="right"><?php //echo anchor(site_url('Tbl_sales/detail_pemesanan_by_sales_by_tingkat/' . $uuid_sales_selected . '/' . $tbl_stok_barang_detail->tingkat), '<i class="fa fa-pencil-square-o" aria-hidden="true">DETAIL</i>', 'class="btn btn-success btn-sm"'); ?></td> -->


                                        <!-- PENGIRIMAN PER COVER -->
                                        <?php
                                        $total_PENJUALAN_PER_mapel = 0;
                                        foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {

                                            $X_COVER = $tbl_stok_barang_detail_cover_list->uuid_cover_produk . "_PENJUALAN_BARU_" . $tbl_stok_barang_detail->tingkat;
                                            echo "<td style='text-align:right;font-size:0.7vw'>" . nominal($tbl_stok_barang_detail->$X_COVER) . "</td>";
                                            $total_PENJUALAN_PER_mapel = $total_PENJUALAN_PER_mapel + $tbl_stok_barang_detail->$X_COVER;
                                        }
                                        ?>

                                        <!-- TOTAL PENGIRIMAN -->
                                        <td style="font-size:0.7vw" align="right"><?php echo nominal($total_PENJUALAN_PER_mapel); ?></td>

                                        <!-- KEKURANGAN PER COVER -->
                                        <?php
                                        $total_KEKURANGAN_PER_mapel = 0;
                                        foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {

                                            $X_COVER = $tbl_stok_barang_detail_cover_list->uuid_cover_produk . "_KEKURANGAN_BARU_" . $tbl_stok_barang_detail->tingkat;
                                            echo "<td style='text-align:right;font-size:0.7vw'>" . nominal($tbl_stok_barang_detail->$X_COVER) . "</td>";
                                            $total_KEKURANGAN_PER_mapel = $total_KEKURANGAN_PER_mapel + $tbl_stok_barang_detail->$X_COVER;
                                        }
                                        ?>

                                        <!-- TOTAL KEKURANGAN -->
                                        <!-- <td style="font-size:0.7vw" align="right"><?php //echo nominal($total_KEKURANGAN_PER_mapel); ?></td> -->
                                        <td style="font-size:0.7vw" align="right"><?php echo nominal($total_PENJUALAN_PER_mapel-$total_PEMESANAN_PER_mapel); ?></td>

                                        <!-- 
                                        <td style="font-size:0.7vw" align="right"><?php
                                                                                    // if (!empty($tbl_stok_barang_detail->total_penjualan_baru)) {
                                                                                    //     // echo nominal($tbl_stok_barang_detail->total_penjualan_baru);
                                                                                    //     $xz_total_penjualan_baru = $tbl_stok_barang_detail->total_penjualan_baru;
                                                                                    // } else {
                                                                                    //     $xz_total_penjualan_baru = 0;
                                                                                    // }

                                                                                    // if (!empty($tbl_stok_barang_detail->total_penjualan_lama)) {
                                                                                    //     // echo nominal($tbl_stok_barang_detail->total_penjualan_baru);
                                                                                    //     $xz_total_penjualan_lama = $tbl_stok_barang_detail->total_penjualan_lama;
                                                                                    // } else {
                                                                                    //     $xz_total_penjualan_lama = 0;
                                                                                    // }
                                                                                    // echo nominal($xz_total_penjualan_baru + $xz_total_penjualan_lama);

                                                                                    ?></td> -->




                                        <!-- <td style="font-size:0.7vw" align="right"><?php
                                                                                    // echo nominal($total_mapel - ($xz_total_penjualan_baru + $xz_total_penjualan_lama));
                                                                                    ?></td> -->



                                        <td style="font-size:0.7vw" align="right"><?php
                                                                                    echo anchor(site_url('Tbl_sales/detail_kurang_kirim_by_sales_by_tingkat/' . $uuid_sales_selected . '/' . $tbl_stok_barang_detail->tingkat), '<i class="fa fa-pencil-square-o" aria-hidden="true">DETAIL</i>', 'class="btn btn-primary btn-sm" target="_blank"');
                                                                                    ?></td>

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
    </section>
</div>


