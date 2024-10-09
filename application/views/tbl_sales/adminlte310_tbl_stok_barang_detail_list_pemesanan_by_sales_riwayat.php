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

                            <form action="<?php //echo $action;  
                                            ?>" method="post">

                                <div class="col-8 card-title">

                                    <!-- <h3 class="card-title"> -->
                                    KEKURANGAN PENGIRIMAN : <?php
                                                            echo $nama_sales_selected;
                                                            echo "<strong>";
                                                            // if (is_null($tingkat_selected)) {
                                                            //     echo "ALL [ ";
                                                            // } else {
                                                            //     echo $tingkat_selected . " [ ";
                                                            // }
                                                            echo "  [" . $_SESSION['thn_selected'] . " / " . $_SESSION['semester_selected'] . " ]</strong>";
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

                            </form>
                        </div>

                    </div>
                    <br />

                    <div class="row">
                        <div class="col-2"><?php
                                            // echo anchor(site_url('Trans_pemesanan/create_setting/' . $uuid_sales_selected), '<i class="fa fa-wpforms" aria-hidden="true"></i> Tambah Data PEMESANAN', 'class="btn btn-danger btn-sm"');
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

                        <!-- <table id="example" class="table table-bordered table-striped"> -->
                        <table id="exampleFreeze" class="table table-bordered table-striped">
                            <thead>

                                <tr>
                                    <th style="font-size:0.7vw" width="10px">No</th>

                                    <th style="font-size:0.7vw" width="110px">JENIS PESANAN</th>

                                    <?php
                                    foreach ($tbl_stok_barang_detail_cover_pemesanan as $tbl_stok_barang_detail_cover_pemesanan_list) {
                                        echo "<th style='text-align:right;font-size:0.7vw'>" . $tbl_stok_barang_detail_cover_pemesanan_list->nama_cover . "</th>";
                                    }
                                    ?>
                                    <th style="font-size:0.7vw">JUMLAH TOTAL</th>
                                    <th style="font-size:0.7vw">PESANAN</th>

                                    <?php
                                    foreach ($tbl_stok_barang_detail_cover_penjualan as $tbl_stok_barang_detail_cover_penjualan_list) {
                                        echo "<th style='text-align:right;font-size:0.7vw'>" . $tbl_stok_barang_detail_cover_penjualan_list->nama_cover . "</th>";
                                    }
                                    ?>

                                    <th style="font-size:0.7vw">JUMLAH TERKIRIM</th>
                                    <th style="font-size:0.7vw">BELUM TERKIRIM</th>


                                    <th style="font-size:0.7vw">TOTAL PENJUALAN</th>
                                    <?php
                                    foreach ($tbl_stok_barang_detail_cover_retur as $tbl_stok_barang_detail_cover_retur_list) {
                                        echo "<th style='text-align:right;font-size:0.7vw'>" . $tbl_stok_barang_detail_cover_retur_list->nama_cover . "</th>";
                                    }
                                    ?>
                                    <th style="font-size:0.7vw">TOTAL RETUR</th>
                                    <th style="font-size:0.7vw">TOTAL <br /> PENJUALAN BERSIH</th>


                                </tr>
                            </thead>

                            <tbody>

                                <?php
                                $start = 0;
                                $TOTAL_JUMLAH_pemesanan_64 = 0;
                                $TOTAL_JUMLAH_pemesanan_96 = 0;
                                $TOTAL_JUMLAH_penjualan_64 = 0;
                                $TOTAL_JUMLAH_penjualan_96 = 0;

                                $TOTAL_Belum_Terkirim_64 = 0;
                                $TOTAL_Belum_Terkirim_96 = 0;

                                $TOTAL_retur_64 = 0;
                                $TOTAL_retur_96 = 0;

                                $TOTAL_PENJUALAN_BERSIH_64 = 0;
                                $TOTAL_PENJUALAN_BERSIH_96 = 0;
                                foreach ($tbl_stok_barang_detail_data as $tbl_stok_barang_detail) {
                                ?>
                                    <tr>
                                        <td style="font-size:0.7vw" width="10px"><?php echo ++$start ?></td>
                                        <td style="font-size:0.7vw" width="10px"> <?php echo $tbl_stok_barang_detail->tingkat_name; ?> </td>

                                        <?php
                                        $total_mapel_pemesanan = 0;
                                        $total_mapel_pemesanan_64 = 0;
                                        $total_mapel_pemesanan_96 = 0;
                                        foreach ($tbl_stok_barang_detail_cover_pemesanan as $tbl_stok_barang_detail_cover_pemesanan_list) {

                                            $X_PEMESANAN_64 = $tbl_stok_barang_detail_cover_pemesanan_list->nama_cover . "_PEMESANAN_64_" . $tbl_stok_barang_detail->tingkat_LIST;
                                            $X_PEMESANAN_96 = $tbl_stok_barang_detail_cover_pemesanan_list->nama_cover . "_PEMESANAN_96_" . $tbl_stok_barang_detail->tingkat_LIST;


                                            if (!empty($tbl_stok_barang_detail->$X_PEMESANAN_64)  or !empty($tbl_stok_barang_detail->$X_PEMESANAN_96)) {
                                                echo "<th style='text-align:right;font-size:0.7vw'>" . nominal($tbl_stok_barang_detail->$X_PEMESANAN_64 + $tbl_stok_barang_detail->$X_PEMESANAN_96) . "</th>";
                                                $total_mapel_pemesanan = $total_mapel_pemesanan + ($tbl_stok_barang_detail->$X_PEMESANAN_64 + $tbl_stok_barang_detail->$X_PEMESANAN_96);
                                                $total_mapel_pemesanan_64 = $total_mapel_pemesanan_64 + ($tbl_stok_barang_detail->$X_PEMESANAN_64);
                                                $total_mapel_pemesanan_96 = $total_mapel_pemesanan_96 + ($tbl_stok_barang_detail->$X_PEMESANAN_96);
                                            } else {
                                                echo "<th style='text-align:right;font-size:0.7vw'></th>";
                                            }
                                        }

                                        ?>

                                        <!-- JUMLAH TOTAL -->
                                        <td style="font-size:0.7vw" align="right">
                                            <?php
                                            echo nominal($total_mapel_pemesanan);
                                            $TOTAL_JUMLAH_pemesanan_64 = $TOTAL_JUMLAH_pemesanan_64 + $total_mapel_pemesanan_64;
                                            $TOTAL_JUMLAH_pemesanan_96 = $TOTAL_JUMLAH_pemesanan_96 + $total_mapel_pemesanan_96;
                                            ?>
                                        </td>

                                        <!-- PESANAN -->
                                        <td style="font-size:0.7vw" align="right"></td>

                                        <?php
                                        $total_mapel_penjualan_64 = 0;
                                        $total_mapel_penjualan_96 = 0;
                                        foreach ($tbl_stok_barang_detail_cover_penjualan as $tbl_stok_barang_detail_cover_penjualan_list) {
                                            $X_PENJUALAN_64 = $tbl_stok_barang_detail_cover_penjualan_list->nama_cover . "_PENJUALAN_64_" . $tbl_stok_barang_detail->tingkat_LIST;
                                            $X_PENJUALAN_96 = $tbl_stok_barang_detail_cover_penjualan_list->nama_cover . "_PENJUALAN_96_" . $tbl_stok_barang_detail->tingkat_LIST;


                                            if (!empty($tbl_stok_barang_detail->$X_PENJUALAN_64)  or !empty($tbl_stok_barang_detail->$X_PENJUALAN_96)) {
                                                echo "<th style='text-align:right;font-size:0.7vw'>" . nominal($tbl_stok_barang_detail->$X_PENJUALAN_64 + $tbl_stok_barang_detail->$X_PENJUALAN_96) . "</th>";
                                                $total_mapel_penjualan_64 = $total_mapel_penjualan_64 + $tbl_stok_barang_detail->$X_PENJUALAN_64;
                                                $total_mapel_penjualan_96 = $total_mapel_penjualan_96 + $tbl_stok_barang_detail->$X_PENJUALAN_96;
                                            } else {
                                                echo "<th style='text-align:right;font-size:0.7vw'></th>";
                                            }
                                        }
                                        ?>

                                        <!-- JUMLAH TERKIRIM -->
                                        <td style="font-size:0.7vw" align="right"><?php
                                                                                    echo nominal($total_mapel_penjualan_64 + $total_mapel_penjualan_96);
                                                                                    $TOTAL_JUMLAH_penjualan_64 = $TOTAL_JUMLAH_penjualan_64 + $total_mapel_penjualan_64;
                                                                                    $TOTAL_JUMLAH_penjualan_96 = $TOTAL_JUMLAH_penjualan_96 + $total_mapel_penjualan_96;
                                                                                    ?></td>

                                        <!-- BELUM TERKIRIM -->
                                        <td style="font-size:0.7vw" align="right"><?php
                                                                                    echo nominal(($total_mapel_pemesanan_64 + $total_mapel_pemesanan_96) - ($total_mapel_penjualan_64 + $total_mapel_penjualan_96));
                                                                                    $TOTAL_Belum_Terkirim_64 = $TOTAL_Belum_Terkirim_64 + ($total_mapel_pemesanan_64 - $total_mapel_penjualan_64);
                                                                                    $TOTAL_Belum_Terkirim_96 = $TOTAL_Belum_Terkirim_96 + ($total_mapel_pemesanan_96 - $total_mapel_penjualan_96);
                                                                                    ?></td>

                                        <!-- TOTAL PENJUALAN -->
                                        <td style="font-size:0.7vw" align="right"><?php
                                                                                    echo nominal($total_mapel_penjualan_64 + $total_mapel_penjualan_96);
                                                                                    $TOTAL_JUMLAH_penjualan_64 = $TOTAL_JUMLAH_penjualan_64 + $total_mapel_penjualan_64;
                                                                                    $TOTAL_JUMLAH_penjualan_96 = $TOTAL_JUMLAH_penjualan_96 + $total_mapel_penjualan_96;
                                                                                    ?></td>


                                        <?php
                                        $total_mapel_retur_64 = 0;
                                        $total_mapel_retur_96 = 0;
                                        foreach ($tbl_stok_barang_detail_cover_retur as $tbl_stok_barang_detail_cover_retur_list) {
                                            $X_RETUR_64 = $tbl_stok_barang_detail_cover_pemesanan_list->nama_cover . "_RETUR_64_" . $tbl_stok_barang_detail->tingkat_LIST;
                                            $X_RETUR_96 = $tbl_stok_barang_detail_cover_pemesanan_list->nama_cover . "_RETUR_96_" . $tbl_stok_barang_detail->tingkat_LIST;

                                            if (!empty($tbl_stok_barang_detail->$X_RETUR_64)  or !empty($tbl_stok_barang_detail->$X_RETUR_96)) {
                                                echo "<th style='text-align:right;font-size:0.7vw'>" . nominal($tbl_stok_barang_detail->$X_RETUR_64 + $tbl_stok_barang_detail->$X_RETUR_96) . "</th>";
                                                $total_mapel_retur_64 = $total_mapel_retur_64 + $tbl_stok_barang_detail->$X_RETUR_64;
                                                $total_mapel_retur_96 = $total_mapel_retur_96 + $tbl_stok_barang_detail->$X_RETUR_96;
                                            } else {
                                                echo "<th style='text-align:right;font-size:0.7vw'></th>";
                                            }
                                        }
                                        ?>


                                        <!-- TOTAL RETUR -->
                                        <td style="font-size:0.7vw" align="right"><?php
                                                                                    echo nominal($total_mapel_retur_64 + $total_mapel_retur_96);
                                                                                    $TOTAL_retur_64 = $TOTAL_retur_64 + $total_mapel_retur_64;
                                                                                    $TOTAL_retur_96 = $TOTAL_retur_96 + $total_mapel_retur_96;
                                                                                    ?></td>

                                        <!-- TOTAL PENJUALAN BERSIH -->
                                        <td style="font-size:0.7vw" align="right"><?php
                                                                                    echo nominal(($total_mapel_penjualan_64 + $total_mapel_penjualan_96) - ($total_mapel_retur_64 + $total_mapel_retur_96));
                                                                                    $TOTAL_PENJUALAN_BERSIH_64 = $TOTAL_PENJUALAN_BERSIH_64 + (($total_mapel_penjualan_64) - ($total_mapel_retur_64));
                                                                                    $TOTAL_PENJUALAN_BERSIH_96 = $TOTAL_PENJUALAN_BERSIH_96 + (($total_mapel_penjualan_96) - ($total_mapel_retur_96));
                                                                                    ?></td>

                                    </tr>
                                <?php
                                }
                                ?>

                            </tbody>


                            <tfoot>
                                <tr>
                                    <!-- <th style="font-size:0.7vw" width="10px">No</th> -->

                                    <th style="text-align:center;font-size:0.7vw" colspan="2">TOTAL 64</th>

                                    <?php
                                    foreach ($tbl_stok_barang_detail_cover_pemesanan as $tbl_stok_barang_detail_cover_pemesanan_list) {

                                        $x_TOTAL_PEMESANAN_COVER_64 = $tbl_stok_barang_detail_cover_pemesanan_list->uuid_cover_produk . "_TOTAL_PEMESANAN_COVER_64";

                                        foreach ($total_per_cover as $total_per_cover_list) {
                                            if (!empty($total_per_cover_list->$x_TOTAL_PEMESANAN_COVER_64)) {
                                                echo "<th style='text-align:right;font-size:0.7vw'>" . nominal($total_per_cover_list->$x_TOTAL_PEMESANAN_COVER_64) . "</th>";
                                            } else {
                                                echo "<th style='text-align:right;font-size:0.7vw'>0</th>";
                                            }
                                        }
                                    }
                                    ?>
                                    <th style="text-align:right;font-size:0.7vw"><?php echo nominal($TOTAL_JUMLAH_pemesanan_64) ?></th>
                                    <th style="text-align:right;font-size:0.7vw"></th>

                                    <?php
                                    foreach ($tbl_stok_barang_detail_cover_penjualan as $tbl_stok_barang_detail_cover_penjualan_list) {
                                        $x_TOTAL_PENJUALAN_COVER_64 = $tbl_stok_barang_detail_cover_penjualan_list->uuid_cover_produk . "_TOTAL_PENJUALAN_COVER_64";

                                        foreach ($total_per_cover_penjualan as $total_per_cover_penjualan_list) {
                                            if (!empty($total_per_cover_penjualan_list->$x_TOTAL_PENJUALAN_COVER_64)) {
                                                echo "<th style='text-align:right;font-size:0.7vw'>" . nominal($total_per_cover_penjualan_list->$x_TOTAL_PENJUALAN_COVER_64) . "</th>";
                                            } else {
                                                echo "<th style='text-align:right;font-size:0.7vw'>0</th>";
                                            }
                                        }
                                    }
                                    ?>

                                    <th style="text-align:right;font-size:0.7vw"><?php echo nominal($TOTAL_JUMLAH_penjualan_64) ?></th>
                                    <th style="text-align:right;font-size:0.7vw"><?php echo nominal($TOTAL_Belum_Terkirim_64) ?></th>


                                    <th style="text-align:right;font-size:0.7vw"><?php echo nominal($TOTAL_JUMLAH_penjualan_64) ?></th>
                                    <?php
                                    foreach ($tbl_stok_barang_detail_cover_retur as $tbl_stok_barang_detail_cover_retur_list) {
                                        $x_TOTAL_RETUR_COVER_64 = $tbl_stok_barang_detail_cover_retur_list->uuid_cover_produk . "_TOTAL_RETUR_COVER_64";

                                        foreach ($total_per_cover_retur as $total_per_cover_retur_list) {
                                            if (!empty($total_per_cover_retur_list->$x_TOTAL_RETUR_COVER_64)) {
                                                echo "<th style='text-align:right;font-size:0.7vw'>" . nominal($total_per_cover_retur_list->$x_TOTAL_RETUR_COVER_64) . "</th>";
                                            } else {
                                                echo "<th style='text-align:right;font-size:0.7vw'>0</th>";
                                            }
                                        }
                                    }
                                    ?>
                                    <th style="text-align:right;font-size:0.7vw"><?php echo nominal($TOTAL_retur_64) ?></th>
                                    <th style="text-align:right;font-size:0.7vw"><?php echo nominal($TOTAL_PENJUALAN_BERSIH_64) ?></th>


                                </tr>

                                <!-- ======================================== TOTAL 96 ============================================================== -->



                                <tr>
                                    <!-- <th style="font-size:0.7vw" width="10px">No</th> -->

                                    <th style="text-align:center;font-size:0.7vw" colspan="2">TOTAL 96</th>

                                    <?php
                                    foreach ($tbl_stok_barang_detail_cover_pemesanan as $tbl_stok_barang_detail_cover_pemesanan_list) {

                                        $x_TOTAL_PEMESANAN_COVER_96 = $tbl_stok_barang_detail_cover_pemesanan_list->uuid_cover_produk . "_TOTAL_PEMESANAN_COVER_96";

                                        foreach ($total_per_cover as $total_per_cover_list) {
                                            if (!empty($total_per_cover_list->$x_TOTAL_PEMESANAN_COVER_96)) {
                                                echo "<th style='text-align:right;font-size:0.7vw'>" . nominal($total_per_cover_list->$x_TOTAL_PEMESANAN_COVER_96) . "</th>";
                                            } else {
                                                echo "<th style='text-align:right;font-size:0.7vw'>0</th>";
                                            }
                                        }
                                    }
                                    ?>
                                    <th style="text-align:right;font-size:0.7vw"><?php echo nominal($TOTAL_JUMLAH_pemesanan_96) ?></th>
                                    <th style="text-align:right;font-size:0.7vw"></th>

                                    <?php
                                    foreach ($tbl_stok_barang_detail_cover_penjualan as $tbl_stok_barang_detail_cover_penjualan_list) {
                                        $x_TOTAL_PENJUALAN_COVER_96 = $tbl_stok_barang_detail_cover_penjualan_list->uuid_cover_produk . "_TOTAL_PENJUALAN_COVER_96";

                                        foreach ($total_per_cover_penjualan as $total_per_cover_penjualan_list) {
                                            if (!empty($total_per_cover_penjualan_list->$x_TOTAL_PENJUALAN_COVER_96)) {
                                                echo "<th style='text-align:right;font-size:0.7vw'>" . nominal($total_per_cover_penjualan_list->$x_TOTAL_PENJUALAN_COVER_96) . "</th>";
                                            } else {
                                                echo "<th style='text-align:right;font-size:0.7vw'>0</th>";
                                            }
                                        }
                                    }
                                    ?>

                                    <th style="text-align:right;font-size:0.7vw"><?php echo nominal($TOTAL_JUMLAH_penjualan_96) ?></th>
                                    <th style="text-align:right;font-size:0.7vw"><?php echo nominal($TOTAL_Belum_Terkirim_96) ?></th>


                                    <th style="text-align:right;font-size:0.7vw"><?php echo nominal($TOTAL_JUMLAH_penjualan_96) ?></th>
                                    <?php
                                    foreach ($tbl_stok_barang_detail_cover_retur as $tbl_stok_barang_detail_cover_retur_list) {
                                        $x_TOTAL_RETUR_COVER_96 = $tbl_stok_barang_detail_cover_retur_list->uuid_cover_produk . "_TOTAL_RETUR_COVER_96";

                                        foreach ($total_per_cover_retur as $total_per_cover_retur_list) {
                                            if (!empty($total_per_cover_retur_list->$x_TOTAL_RETUR_COVER_96)) {
                                                echo "<th style='text-align:right;font-size:0.7vw'>" . nominal($total_per_cover_retur_list->$x_TOTAL_RETUR_COVER_96) . "</th>";
                                            } else {
                                                echo "<th style='text-align:right;font-size:0.7vw'>0</th>";
                                            }
                                        }
                                    }
                                    ?>
                                    <th style="text-align:right;font-size:0.7vw"><?php echo nominal($TOTAL_retur_96) ?></th>
                                    <th style="text-align:right;font-size:0.7vw"><?php echo nominal($TOTAL_PENJUALAN_BERSIH_96) ?></th>


                                </tr>








                            </tfoot>
                        </table>


                    </div>

                    <!-- /.card-body -->
                </div>

            </div>

        </div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#example').DataTable({
            "scrollY": 450,
            "scrollX": true
        });
    });
</script>

