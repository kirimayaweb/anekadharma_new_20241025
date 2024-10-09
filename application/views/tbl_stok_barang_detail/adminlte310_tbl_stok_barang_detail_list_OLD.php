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

                            <form action="<?php echo $action; ?>" method="post">

                                <div class="col-8 card-title">

                                    <!-- <h3 class="card-title"> -->
                                    MANAJEMEN DATA STOK = <?php echo "<strong>";
                                                            if (is_null($tingkat_selected)) {
                                                                echo "ALL [ ";
                                                            } else {
                                                                echo $tingkat_selected . " [ ";
                                                            }
                                                            echo $_SESSION['thn_selected'] . " / " . $_SESSION['semester_selected'] . " ]</strong>";
                                                            ?>

                                    <!-- </h3> -->
                                </div>
                                <div class="col-2 card-title">

                                    <select name="level_sekolah" id="level_sekolah" class="form-control select2" style="width: 200px; height: 10px;">
                                        <option value="">Pilih Tingkat</option>
                                        <option value="MA">MA</option>
                                        <option value="MTS">MTS</option>
                                        <option value="MI">MI</option>
                                        <option value="SMP">SMP</option>
                                        <option value="PGSMP">PG SMP</option>
                                        <option value="SD">SD</option>
                                        <option value="PGSD">PG SD</option>
                                    </select>
                                    <button type="submit" class="btn btn-danger"> Cari</button>
                                </div>
                                <!-- <div class="col-md-2  card-title"></div> -->
                                <div class="col-2  card-title">

                                </div>

                            </form>
                        </div>

                    </div>
                    <br />

                    <div class="row">
                        <div class="col-2"><?php //echo anchor(site_url('Trans_cetakinput/create_setting'), '<i class="fa fa-wpforms" aria-hidden="true"></i> Tambah Data STOCK', 'class="btn btn-danger btn-sm"'); 
                                            ?></div>
                        <div class="col-2" align="right"><?php echo anchor(
                                                                site_url('tbl_stok_barang_detail/excel_stock/' . $tingkat_selected),
                                                                '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Ms Excel, REKAP CETAK :' .
                                                                    $tingkat_selected . ' Tahun : ' . $_SESSION['thn_selected'] . ' Semester :' . $_SESSION['semester_selected'],
                                                                'class="btn btn-success btn-sm"'
                                                            ); ?></div>
                        <div class="col-2" align="left"><?php
                                                        echo anchor(site_url('tbl_stok_barang_detail/get_data_stock_rekap/' . $tingkat_selected), '<i class="fa fa-file-word-o" aria-hidden="true"></i> REKAP STOCK', 'class="btn btn-primary btn-sm"');
                                                        ?>
                        </div>
                        <div class="col-2" align="right"><?php echo anchor(site_url('Trans_cetakinput/create_setting'), '<i class="fa fa-file-excel-o" aria-hidden="true"></i> PO Cetak', 'class="btn btn-success btn-sm"'); ?></div>
                        <div class="col-2" align="left"><?php echo anchor(site_url('Trans_cetakinput/create_setting'), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Finishing', 'class="btn btn-primary btn-sm"'); ?></div>
                        <div class="col-4"></div>

                    </div>

                    <div class="card-body">

                        <table id="example1" class="table table-bordered table-striped">
                            <thead>

                                <tr>
                                    <th style="text-align:center;font-size:1vw">No</th>

                                    <th style="text-align:center;font-size:1vw">MAPEL</th>
                                    <th style="text-align:center;font-size:1vw">PO NASKAH</th>
                                    <?php
                                    foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {
                                        echo "<th style='text-align:center;font-size:1vw'> PO <br/>" . $tbl_stok_barang_detail_cover_list->nama_cover . "</th>";
                                    }
                                    ?>


                                    <th style="text-align:center;font-size:1vw">TOTAL PO COVER</th>
                                    <th style="text-align:center;font-size:1vw">SELISIH ( NASKAH - PO COVER )</th>

                                    <?php
                                    foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {
                                        echo "<th style='text-align:center;font-size:1vw'> Buku Jadi : " . $tbl_stok_barang_detail_cover_list->nama_cover . "</th>";
                                    }
                                    ?>
                                    <th style="text-align:center;font-size:1vw">TOTAL BUKU JADI</th>
                                    <?php
                                    foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {
                                        echo "<th style='text-align:center;font-size:1vw'> BDP : " . $tbl_stok_barang_detail_cover_list->nama_cover . "</th>";
                                    }
                                    ?>
                                    <th style="text-align:center;font-size:1vw">TOTAL BDP</th>
                                    <th style="text-align:center;font-size:1vw">KETERANGAN</th>


                                </tr>
                            </thead>

                            <tbody>

                                <?php
                                $start = 0;
                                $TOTAL_NASKAH = 0;
                                $TOTAL_cover = 0;
                                $TOTAL_SELISIH = 0;
                                $TOTAL_BUKUJADI = 0;
                                $TOTAL_BUKUDALAMPROSES = 0;
                                foreach ($tbl_stok_barang_detail_data as $tbl_stok_barang_detail) {
                                ?>
                                    <tr>
                                        <td style="text-align:center;font-size:1vw" width="10px"><?php echo ++$start ?></td>

                                        <td style="text-align:left;font-size:1vw">
                                            <?php
                                            echo $tbl_stok_barang_detail->mapel_produk_stock;
                                            ?>
                                        </td>

                                        <!-- NASKAH -->
                                        <td style="text-align:right;font-size:1vw">
                                            <?php
                                            if ($tbl_stok_barang_detail->jumlah_naskah) {

                                                echo nominal($tbl_stok_barang_detail->jumlah_naskah);
                                            } else {
                                                echo "-";
                                            }
                                            $TOTAL_NASKAH = $TOTAL_NASKAH + $tbl_stok_barang_detail->jumlah_naskah;
                                            ?>
                                        </td>

                                        <!-- COVER -->
                                        <?php
                                        $x_total = 0;
                                        foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {
                                            $x = $tbl_stok_barang_detail_cover_list->nama_cover;

                                            echo "<td style='text-align:right;font-size:1vw' >" . nominal($tbl_stok_barang_detail->$x) . "</td>";
                                            $x_total = $x_total + $tbl_stok_barang_detail->$x;
                                        }
                                        ?>

                                        <!-- TOTAL COVER -->
                                        <td style="text-align:right;font-size:1vw">
                                            <?php
                                            echo  nominal($x_total);
                                            $TOTAL_cover = $TOTAL_cover + $x_total;
                                            ?>
                                        </td>

                                        <!-- SELISIH -->
                                        <td style="text-align:right;font-size:1vw">
                                            <?php

                                            if (($tbl_stok_barang_detail->jumlah_naskah) - $x_total >= 0) {
                                                echo nominal(($tbl_stok_barang_detail->jumlah_naskah) - $x_total);
                                            } else {
                                            ?>
                                                <p style="color:red;"><?php echo "<strong>" . nominal(($tbl_stok_barang_detail->jumlah_naskah) - $x_total) . "</strong>"; ?></p>
                                            <?php
                                            }
                                            $TOTAL_SELISIH = $TOTAL_SELISIH + (($tbl_stok_barang_detail->jumlah_naskah) - $x_total);
                                            ?>


                                        </td>


                                        <!-- BUKU JADI -->
                                        <?php
                                        $x_totalBJ = 0;
                                        foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {

                                            $x_GUDANG = $tbl_stok_barang_detail_cover_list->nama_cover . "_GUDANG";
                                            $x_JUAL = $tbl_stok_barang_detail_cover_list->nama_cover . "_JUAL";
                                            $x_RETUR = $tbl_stok_barang_detail_cover_list->nama_cover . "_RETUR";

                                            $sisa_STOCK = $tbl_stok_barang_detail->$x_GUDANG - $tbl_stok_barang_detail->$x_JUAL + $tbl_stok_barang_detail->$x_RETUR;

                                            echo "<td style='text-align:right;font-size:1vw'> " . "gudang :<strong>" . nominal($tbl_stok_barang_detail->$x_GUDANG) . "</strong><br/> Jual :<strong>" . nominal($tbl_stok_barang_detail->$x_JUAL) . "</strong><br/> Retur :<strong>" . nominal($tbl_stok_barang_detail->$x_RETUR) . "</strong><br/>-----<br/> gudang+retur-jual :<br/><strong>" . nominal($sisa_STOCK) . "</td>";

                                            $x_totalBJ = $x_totalBJ + $sisa_STOCK;
                                        }
                                        ?>
                                        <td style="text-align:right;font-size:1vw"> <?php
                                                                                    echo  nominal($x_totalBJ);
                                                                                    $TOTAL_BUKUJADI = $TOTAL_BUKUJADI + $x_totalBJ;
                                                                                    ?>
                                        </td>

                                        <!-- BUKU DALAM PROSES -->
                                        <?php
                                        $x_totalBDP = 0;
                                        foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {

                                            $xbdp = $tbl_stok_barang_detail_cover_list->nama_cover;
                                            $x_GUDANG = $tbl_stok_barang_detail_cover_list->nama_cover . "_GUDANG";

                                            $thn_sekarang = date('Y');
                                            // if (($_SESSION['thn_selected'] < $thn_sekarang) and ($tbl_stok_barang_detail->$xbdp) <= 0) {
                                            if (($tbl_stok_barang_detail->$xbdp) <= 0) {
                                                $jmlh_bdp = $tbl_stok_barang_detail->$xbdp;
                                            } else {
                                                $jmlh_bdp = $tbl_stok_barang_detail->$xbdp - $tbl_stok_barang_detail->$x_GUDANG;
                                            }

                                            echo "<td style='text-align:right;font-size:1vw'>" . "Total per cover: <strong>" . nominal($tbl_stok_barang_detail->$xbdp) . "</strong><br/> Gudang:<strong>" . nominal($tbl_stok_barang_detail->$x_GUDANG) . "</strong><br/>---------<br/> Total per cover - Di Gudang: <strong>" . nominal($jmlh_bdp) . "</strong></td>";

                                            $x_totalBDP = $x_totalBDP + ($jmlh_bdp);
                                        }
                                        ?>
                                        <td style="text-align:right;font-size:1vw"> <?php
                                                                                    echo  nominal($x_totalBDP);
                                                                                    $TOTAL_BUKUDALAMPROSES = $TOTAL_BUKUDALAMPROSES + $x_totalBDP;
                                                                                    ?>
                                        </td>
                                        <td></td>



                                    </tr>
                                <?php
                                }
                                ?>

                            </tbody>


                            <tfoot>
                                <!-- ================== 64 ===================================================================================================================================== -->
                                <tr>
                                    <th style="text-align:center;font-size:1vw" colspan="2">TOTAL 64</th>
                                    <th style="text-align:right;font-size:1vw"><?php echo nominal($TOTAL_PO_64_NASKAH);
                                                                                ?></th>


                                    <!-- FINISHING PER COVER -->
                                    <?php
                                    $X_TOTALPO64 = 0;
                                    $TOTAL_MASUK_64 = 0;
                                    foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {
                                        // $x = $tbl_stok_barang_detail_cover_list->nama_cover . "_TOTAL_PO_64";
                                        // foreach ($total_per_cover as $key => $value) {
                                        //     if ($key == $x) {
                                        //         echo "<th style='text-align:right;font-size:1vw'> " . nominal($value) . " </th>";
                                        //         $X_TOTALPO64 = $X_TOTALPO64 + $value;
                                        //     }
                                        // }

                                        $x_TOTAL_COVER_64 = $tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock . "_TOTAL_FINISHING_COVER_64";
                                        // $x_TOTAL_RETUR_COVER_64 = $tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock . "_TOTAL_RETUR_COVER_64";


                                        foreach ($total_per_cover as $total_per_cover_list) {
                                            // print_r($total_per_cover_list);
                                            if (!empty($total_per_cover_list->$x_TOTAL_COVER_64)) {
                                                // DENGAN RETUR
                                                // echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_COVER_64) + ($total_per_cover_list->$x_TOTAL_RETUR_COVER_64)) . " </th>";
                                                echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_COVER_64)) . " </th>";
                                                // $TOTAL_MASUK_64 = $TOTAL_MASUK_64 + ($total_per_cover_list->$x_TOTAL_COVER_64) + ($total_per_cover_list->$x_TOTAL_RETUR_COVER_64);
                                                $TOTAL_MASUK_64 = $TOTAL_MASUK_64 + ($total_per_cover_list->$x_TOTAL_COVER_64);
                                                // TANPA RETUR
                                                // echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_COVER_64)) . " </th>";
                                                // $TOTAL_MASUK_64 = $TOTAL_MASUK_64 + ($total_per_cover_list->$x_TOTAL_COVER_64);
                                            } else {
                                                echo "<th style='text-align:right;font-size:1vw'> " . 0 . " </th>";
                                            }
                                        }
                                    }
                                    ?>

                                    <!-- TOTAL COVER / FINISHING -->
                                    <th style="text-align:right;font-size:1vw"><?php echo nominal($TOTAL_MASUK_64); ?></th>

                                    <!-- TOTAL SELISIH : COVER / FINISHING - PO CETAK  -->
                                    <th style="text-align:right;font-size:1vw"><?php
                                                                                // echo nominal($TOTAL_PO_64_NASKAH - $X_TOTALPO64);


                                                                                if (($TOTAL_PO_64_NASKAH - $TOTAL_MASUK_64) >= 0) {
                                                                                    echo nominal($TOTAL_PO_64_NASKAH - $TOTAL_MASUK_64);
                                                                                } else {
                                                                                ?>
                                            <p style="color:red;"><?php echo "<strong>" . nominal($TOTAL_PO_64_NASKAH - $TOTAL_MASUK_64) . "</strong>"; ?></p>
                                        <?php
                                                                                }


                                        ?>
                                    </th>


                                    <!-- BUKU JADI PER COVER 64 -->
                                    <?php
                                    $x_totalbkjdi64 = 0;
                                    $TOTAL_MASUK_64 = 0;
                                    foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {
                                        // $x = $tbl_stok_barang_detail_cover_list->nama_cover . "_TOTAL_BkJADI_64";

                                        // foreach ($total_per_cover as $key => $value) {
                                        //     if ($key == $x) {
                                        //         echo "<th style='text-align:right;font-size:1vw'> " . nominal($value) . " </th>";
                                        //         $x_totalbkjdi64 = $x_totalbkjdi64 + $value;
                                        //     }
                                        // }


                                        $x_TOTAL_COVER_64 = $tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock . "_TOTAL_GUDANG_COVER_64";
                                        $x_TOTAL_RETUR_COVER_64 = $tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock . "_TOTAL_RETUR_COVER_64";


                                        foreach ($total_per_cover as $total_per_cover_list) {
                                            // print_r($total_per_cover_list);
                                            if (!empty($total_per_cover_list->$x_TOTAL_COVER_64)) {
                                                // DENGAN RETUR
                                                echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_COVER_64) + ($total_per_cover_list->$x_TOTAL_RETUR_COVER_64)) . " </th>";
                                                $TOTAL_MASUK_64 = $TOTAL_MASUK_64 + ($total_per_cover_list->$x_TOTAL_COVER_64) + ($total_per_cover_list->$x_TOTAL_RETUR_COVER_64);
                                                // TANPA RETUR
                                                // echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_COVER_64)) . " </th>";
                                                // $TOTAL_MASUK_64 = $TOTAL_MASUK_64 + ($total_per_cover_list->$x_TOTAL_COVER_64);
                                            } else {
                                                echo "<th style='text-align:right;font-size:1vw'> " . 0 . " </th>";
                                            }
                                        }
                                    }
                                    ?>


                                    <!-- TOTAL BUKU JADI 64 -->
                                    <th style="text-align:right;font-size:1vw"><?php echo nominal($TOTAL_MASUK_64); ?></th>

                                    <!-- TOTAL PER COVER BDP 64 -->
                                    <?php
                                    $TOTAL_FINISHING_BDP_64 = 0;
                                    foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {
                                        // $x = $tbl_stok_barang_detail_cover_list->nama_cover . "_TOTAL_BDP_64";
                                        // foreach ($total_per_cover as $key => $value) {
                                        //     if ($key == $x) {
                                        //         echo "<th style='text-align:right;font-size:1vw'> " . nominal($value) . " </th>";
                                        //     }
                                        // }

                                        $x_TOTAL_FINISHING_COVER_64 = $tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock . "_TOTAL_FINISHING_COVER_64";
                                        // $x_TOTAL_RETUR_COVER_64 = $tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock . "_TOTAL_RETUR_COVER_64";
                                        $x_TOTAL_COVER_64 = $tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock . "_TOTAL_GUDANG_COVER_64";

                                        foreach ($total_per_cover as $total_per_cover_list) {
                                            // print_r($total_per_cover_list);
                                            if (!empty($total_per_cover_list->$x_TOTAL_FINISHING_COVER_64)) {
                                                // DENGAN RETUR
                                                echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_FINISHING_COVER_64) - ($total_per_cover_list->$x_TOTAL_COVER_64)) . " </th>";
                                                $TOTAL_FINISHING_BDP_64 = $TOTAL_FINISHING_BDP_64 + (($total_per_cover_list->$x_TOTAL_FINISHING_COVER_64) - ($total_per_cover_list->$x_TOTAL_COVER_64));
                                                // TANPA RETUR
                                                // echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_COVER_64)) . " </th>";
                                                // $TOTAL_MASUK_64 = $TOTAL_MASUK_64 + ($total_per_cover_list->$x_TOTAL_COVER_64);
                                            } else {
                                                echo "<th style='text-align:right;font-size:1vw'> " . 0 . " </th>";
                                            }
                                        }
                                    }
                                    ?>

                                    <!-- TOTAL FINISHING BDP 64 -->
                                    <th style="text-align:right;font-size:1vw"><?php echo nominal($TOTAL_FINISHING_BDP_64); ?></th>

                                    <!-- KETERANGAN -->
                                    <th style="text-align:center;font-size:1vw"></th>


                                </tr>


                                <!-- ====================96 ========================================================================================================================================== -->
                                <tr>
                                    <th style="text-align:center;font-size:1vw" colspan="2">TOTAL 96</th>
                                    <th style="text-align:right;font-size:1vw"><?php echo nominal($TOTAL_PO_96_NASKAH);
                                                                                ?></th>
                                    <?php
                                    $X_TOTALPO96 = 0;
                                    $TOTAL_MASUK_96 = 0;
                                    foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {
                                        // $x = $tbl_stok_barang_detail_cover_list->nama_cover . "_TOTAL_PO_96";

                                        // foreach ($total_per_cover as $key => $value) {
                                        //     if ($key == $x) {
                                        //         echo "<th style='text-align:right;font-size:1vw'> " . nominal($value) . " </th>";
                                        //         $X_TOTALPO96 = $X_TOTALPO96 + $value;
                                        //     }
                                        // }

                                        $x_TOTAL_COVER_96 = $tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock . "_TOTAL_FINISHING_COVER_96";
                                        // $x_TOTAL_RETUR_COVER_96 = $tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock . "_TOTAL_RETUR_COVER_96";


                                        foreach ($total_per_cover as $total_per_cover_list) {
                                            // print_r($total_per_cover_list);
                                            if (!empty($total_per_cover_list->$x_TOTAL_COVER_96)) {
                                                // DENGAN RETUR
                                                // echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_COVER_96) + ($total_per_cover_list->$x_TOTAL_RETUR_COVER_96)) . " </th>";
                                                echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_COVER_96)) . " </th>";
                                                // $TOTAL_MASUK_96 = $TOTAL_MASUK_96 + ($total_per_cover_list->$x_TOTAL_COVER_96) + ($total_per_cover_list->$x_TOTAL_RETUR_COVER_96);
                                                $TOTAL_MASUK_96 = $TOTAL_MASUK_96 + ($total_per_cover_list->$x_TOTAL_COVER_96);
                                                // TANPA RETUR
                                                // echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_COVER_64)) . " </th>";
                                                // $TOTAL_MASUK_64 = $TOTAL_MASUK_64 + ($total_per_cover_list->$x_TOTAL_COVER_64);
                                            } else {
                                                echo "<th style='text-align:right;font-size:1vw'> " . 0 . " </th>";
                                            }
                                        }
                                    }
                                    ?>

                                    <!-- TOTAL MASUK -->
                                    <th style="text-align:right;font-size:1vw"><?php echo nominal($TOTAL_MASUK_96); ?></th>

                                    <!-- SELISIH TOTAL : NASKAH 96 - COVER 96   -->
                                    <th style="text-align:right;font-size:1vw"><?php
                                                                                // echo nominal($TOTAL_PO_96_NASKAH - $X_TOTALPO96);

                                                                                if (($TOTAL_PO_96_NASKAH - $TOTAL_MASUK_96) >= 0) {
                                                                                    echo nominal($TOTAL_PO_96_NASKAH - $TOTAL_MASUK_96);
                                                                                } else {
                                                                                ?>
                                            <p style="color:red;"><?php echo "<strong>" . nominal($TOTAL_PO_96_NASKAH - $TOTAL_MASUK_96) . "</strong>"; ?></p>
                                        <?php
                                                                                }


                                        ?>


                                    </th>

                                    <!-- BUKU JADI PER COVER 96 -->
                                    <?php
                                    $x_totalbkjadi = 0;
                                    $TOTAL_MASUK_96 = 0;
                                    foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {
                                        // $x = $tbl_stok_barang_detail_cover_list->nama_cover . "_TOTAL_BkJADI_96";

                                        // foreach ($total_per_cover as $key => $value) {
                                        //     if ($key == $x) {
                                        //         echo "<th style='text-align:right;font-size:1vw'> " . nominal($value) . " </th>";
                                        //         $x_totalbkjadi = $x_totalbkjadi + $value;
                                        //     }
                                        // }


                                        $x_TOTAL_COVER_96 = $tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock . "_TOTAL_GUDANG_COVER_96";
                                        $x_TOTAL_RETUR_COVER_96 = $tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock . "_TOTAL_RETUR_COVER_96";


                                        foreach ($total_per_cover as $total_per_cover_list) {
                                            // print_r($total_per_cover_list);
                                            if (!empty($total_per_cover_list->$x_TOTAL_COVER_96)) {
                                                // DENGAN RETUR
                                                echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_COVER_96) + ($total_per_cover_list->$x_TOTAL_RETUR_COVER_96)) . " </th>";
                                                // echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_COVER_96)) . " </th>";
                                                $TOTAL_MASUK_96 = $TOTAL_MASUK_96 + ($total_per_cover_list->$x_TOTAL_COVER_96) + ($total_per_cover_list->$x_TOTAL_RETUR_COVER_96);
                                                // $TOTAL_MASUK_96 = $TOTAL_MASUK_96 + ($total_per_cover_list->$x_TOTAL_COVER_96);
                                                // TANPA RETUR
                                                // echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_COVER_64)) . " </th>";
                                                // $TOTAL_MASUK_64 = $TOTAL_MASUK_64 + ($total_per_cover_list->$x_TOTAL_COVER_64);
                                            } else {
                                                echo "<th style='text-align:right;font-size:1vw'> " . 0 . " </th>";
                                            }
                                        }
                                    }
                                    ?>

                                    <!-- TOTAL BUKU JADI 96 -->
                                    <th style="text-align:right;font-size:1vw"><?php echo nominal($TOTAL_MASUK_96);
                                                                                ?></th>
                                    <?php
                                    $x_bdp96 = 0;
                                    $TOTAL_FINISHING_BDP_96 = 0;
                                    foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {
                                        // $x = $tbl_stok_barang_detail_cover_list->nama_cover . "_TOTAL_BDP_96";

                                        // foreach ($total_per_cover as $key => $value) {
                                        //     if ($key == $x) {
                                        //         echo "<th style='text-align:right;font-size:1vw'> " . nominal($value) . " </th>";
                                        //         $x_bdp96 = $x_bdp96 + $value;
                                        //     }
                                        // }

                                        $x_TOTAL_FINISHING_COVER_96 = $tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock . "_TOTAL_FINISHING_COVER_96";
                                        // $x_TOTAL_RETUR_COVER_64 = $tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock . "_TOTAL_RETUR_COVER_64";
                                        $x_TOTAL_COVER_96 = $tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock . "_TOTAL_GUDANG_COVER_96";


                                        foreach ($total_per_cover as $total_per_cover_list) {
                                            // print_r($total_per_cover_list);
                                            if (!empty($total_per_cover_list->$x_TOTAL_FINISHING_COVER_96)) {
                                                // DENGAN RETUR
                                                echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_FINISHING_COVER_96) - ($total_per_cover_list->$x_TOTAL_COVER_96)) . " </th>";
                                                $TOTAL_FINISHING_BDP_96 = $TOTAL_FINISHING_BDP_96 + (($total_per_cover_list->$x_TOTAL_FINISHING_COVER_96) - ($total_per_cover_list->$x_TOTAL_COVER_96));
                                                // TANPA RETUR
                                                // echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_COVER_64)) . " </th>";
                                                // $TOTAL_MASUK_64 = $TOTAL_MASUK_64 + ($total_per_cover_list->$x_TOTAL_COVER_64);
                                            } else {
                                                echo "<th style='text-align:right;font-size:1vw'> " . 0 . " </th>";
                                            }
                                        }
                                    }
                                    ?>
                                    <th style="text-align:right;font-size:1vw"><?php echo nominal($TOTAL_FINISHING_BDP_96);
                                                                                ?></th>
                                    <th style="text-align:center;font-size:1vw"></th>


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


<!-- NEW TABEL -->

<!-- CSS -->

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
<style type="text/css">
    div.dataTables_wrapper {
        width: 800px;
        margin: 0 auto;
    }
</style>





<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#example').DataTable({
            "scrollY": 600,
            "scrollX": true
        });
    });
</script>




<table id="example" class="display nowrap" style="width:100%">
    <thead>
        <tr>
            <th style="text-align:center;font-size:1vw">No</th>

            <th style="text-align:center;font-size:1vw">MAPEL</th>
            <th style="text-align:center;font-size:1vw">PO NASKAH</th>
            <?php
            foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {
                echo "<th style='text-align:center;font-size:1vw'> PO <br/>" . $tbl_stok_barang_detail_cover_list->nama_cover . "</th>";
            }
            ?>


            <th style="text-align:center;font-size:1vw">TOTAL PO COVER</th>
            <th style="text-align:center;font-size:1vw">SELISIH ( NASKAH - PO COVER )</th>

            <?php
            foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {
                echo "<th style='text-align:center;font-size:1vw'> Buku Jadi : " . $tbl_stok_barang_detail_cover_list->nama_cover . "</th>";
            }
            ?>
            <th style="text-align:center;font-size:1vw">TOTAL BUKU JADI</th>
            <?php
            foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {
                echo "<th style='text-align:center;font-size:1vw'> BDP : " . $tbl_stok_barang_detail_cover_list->nama_cover . "</th>";
            }
            ?>
            <th style="text-align:center;font-size:1vw">TOTAL BDP</th>
            <th style="text-align:center;font-size:1vw">KETERANGAN</th>

        </tr>
    </thead>
    <tbody>
        <?php
        $start = 0;
        $TOTAL_NASKAH = 0;
        $TOTAL_cover = 0;
        $TOTAL_SELISIH = 0;
        $TOTAL_BUKUJADI = 0;
        $TOTAL_BUKUDALAMPROSES = 0;
        foreach ($tbl_stok_barang_detail_data as $tbl_stok_barang_detail) {
        ?>
            <tr>
                <td style="text-align:center;font-size:1vw" width="10px"><?php echo ++$start ?></td>

                <td style="text-align:left;font-size:1vw">
                    <?php
                    echo $tbl_stok_barang_detail->mapel_produk_stock;
                    ?>
                </td>

                <!-- NASKAH -->
                <td style="text-align:right;font-size:1vw">
                    <?php
                    if ($tbl_stok_barang_detail->jumlah_naskah) {

                        echo nominal($tbl_stok_barang_detail->jumlah_naskah);
                    } else {
                        echo "-";
                    }
                    $TOTAL_NASKAH = $TOTAL_NASKAH + $tbl_stok_barang_detail->jumlah_naskah;
                    ?>
                </td>

                <!-- COVER -->
                <?php
                $x_total = 0;
                foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {
                    $x = $tbl_stok_barang_detail_cover_list->nama_cover;

                    echo "<td style='text-align:right;font-size:1vw' >" . nominal($tbl_stok_barang_detail->$x) . "</td>";
                    $x_total = $x_total + $tbl_stok_barang_detail->$x;
                }
                ?>

                <!-- TOTAL COVER -->
                <td style="text-align:right;font-size:1vw">
                    <?php
                    echo  nominal($x_total);
                    $TOTAL_cover = $TOTAL_cover + $x_total;
                    ?>
                </td>

                <!-- SELISIH -->
                <td style="text-align:right;font-size:1vw">
                    <?php

                    if (($tbl_stok_barang_detail->jumlah_naskah) - $x_total >= 0) {
                        echo nominal(($tbl_stok_barang_detail->jumlah_naskah) - $x_total);
                    } else {
                    ?>
                        <p style="color:red;"><?php echo "<strong>" . nominal(($tbl_stok_barang_detail->jumlah_naskah) - $x_total) . "</strong>"; ?></p>
                    <?php
                    }
                    $TOTAL_SELISIH = $TOTAL_SELISIH + (($tbl_stok_barang_detail->jumlah_naskah) - $x_total);
                    ?>


                </td>


                <!-- BUKU JADI -->
                <?php
                $x_totalBJ = 0;
                foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {

                    $x_GUDANG = $tbl_stok_barang_detail_cover_list->nama_cover . "_GUDANG";
                    $x_JUAL = $tbl_stok_barang_detail_cover_list->nama_cover . "_JUAL";
                    $x_RETUR = $tbl_stok_barang_detail_cover_list->nama_cover . "_RETUR";

                    $sisa_STOCK = $tbl_stok_barang_detail->$x_GUDANG - $tbl_stok_barang_detail->$x_JUAL + $tbl_stok_barang_detail->$x_RETUR;

                    echo "<td style='text-align:right;font-size:1vw'> " . "gudang :<strong>" . nominal($tbl_stok_barang_detail->$x_GUDANG) . "</strong><br/> Jual :<strong>" . nominal($tbl_stok_barang_detail->$x_JUAL) . "</strong><br/> Retur :<strong>" . nominal($tbl_stok_barang_detail->$x_RETUR) . "</strong><br/>-----<br/> gudang+retur-jual :<br/><strong>" . nominal($sisa_STOCK) . "</td>";

                    $x_totalBJ = $x_totalBJ + $sisa_STOCK;
                }
                ?>
                <td style="text-align:right;font-size:1vw"> <?php
                                                            echo  nominal($x_totalBJ);
                                                            $TOTAL_BUKUJADI = $TOTAL_BUKUJADI + $x_totalBJ;
                                                            ?>
                </td>

                <!-- BUKU DALAM PROSES -->
                <?php
                $x_totalBDP = 0;
                foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {

                    $xbdp = $tbl_stok_barang_detail_cover_list->nama_cover;
                    $x_GUDANG = $tbl_stok_barang_detail_cover_list->nama_cover . "_GUDANG";

                    $thn_sekarang = date('Y');
                    // if (($_SESSION['thn_selected'] < $thn_sekarang) and ($tbl_stok_barang_detail->$xbdp) <= 0) {
                    if (($tbl_stok_barang_detail->$xbdp) <= 0) {
                        $jmlh_bdp = $tbl_stok_barang_detail->$xbdp;
                    } else {
                        $jmlh_bdp = $tbl_stok_barang_detail->$xbdp - $tbl_stok_barang_detail->$x_GUDANG;
                    }

                    echo "<td style='text-align:right;font-size:1vw'>" . "Total per cover: <strong>" . nominal($tbl_stok_barang_detail->$xbdp) . "</strong><br/> Gudang:<strong>" . nominal($tbl_stok_barang_detail->$x_GUDANG) . "</strong><br/>---------<br/> Total per cover - Di Gudang: <strong>" . nominal($jmlh_bdp) . "</strong></td>";

                    $x_totalBDP = $x_totalBDP + ($jmlh_bdp);
                }
                ?>
                <td style="text-align:right;font-size:1vw"> <?php
                                                            echo  nominal($x_totalBDP);
                                                            $TOTAL_BUKUDALAMPROSES = $TOTAL_BUKUDALAMPROSES + $x_totalBDP;
                                                            ?>
                </td>
                <td></td>



            </tr>
        <?php
        }
        ?>

    <tfoot>
        <!-- ================== 64 ===================================================================================================================================== -->
        <tr>
            <th style="text-align:center;font-size:1vw" colspan="2">TOTAL 64</th>
            <th style="text-align:right;font-size:1vw"><?php echo nominal($TOTAL_PO_64_NASKAH);
                                                        ?></th>


            <!-- FINISHING PER COVER -->
            <?php
            $X_TOTALPO64 = 0;
            $TOTAL_MASUK_64 = 0;
            foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {
                // $x = $tbl_stok_barang_detail_cover_list->nama_cover . "_TOTAL_PO_64";
                // foreach ($total_per_cover as $key => $value) {
                //     if ($key == $x) {
                //         echo "<th style='text-align:right;font-size:1vw'> " . nominal($value) . " </th>";
                //         $X_TOTALPO64 = $X_TOTALPO64 + $value;
                //     }
                // }

                $x_TOTAL_COVER_64 = $tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock . "_TOTAL_FINISHING_COVER_64";
                // $x_TOTAL_RETUR_COVER_64 = $tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock . "_TOTAL_RETUR_COVER_64";


                foreach ($total_per_cover as $total_per_cover_list) {
                    // print_r($total_per_cover_list);
                    if (!empty($total_per_cover_list->$x_TOTAL_COVER_64)) {
                        // DENGAN RETUR
                        // echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_COVER_64) + ($total_per_cover_list->$x_TOTAL_RETUR_COVER_64)) . " </th>";
                        echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_COVER_64)) . " </th>";
                        // $TOTAL_MASUK_64 = $TOTAL_MASUK_64 + ($total_per_cover_list->$x_TOTAL_COVER_64) + ($total_per_cover_list->$x_TOTAL_RETUR_COVER_64);
                        $TOTAL_MASUK_64 = $TOTAL_MASUK_64 + ($total_per_cover_list->$x_TOTAL_COVER_64);
                        // TANPA RETUR
                        // echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_COVER_64)) . " </th>";
                        // $TOTAL_MASUK_64 = $TOTAL_MASUK_64 + ($total_per_cover_list->$x_TOTAL_COVER_64);
                    } else {
                        echo "<th style='text-align:right;font-size:1vw'> " . 0 . " </th>";
                    }
                }
            }
            ?>

            <!-- TOTAL COVER / FINISHING -->
            <th style="text-align:right;font-size:1vw"><?php echo nominal($TOTAL_MASUK_64); ?></th>

            <!-- TOTAL SELISIH : COVER / FINISHING - PO CETAK  -->
            <th style="text-align:right;font-size:1vw"><?php
                                                        // echo nominal($TOTAL_PO_64_NASKAH - $X_TOTALPO64);


                                                        if (($TOTAL_PO_64_NASKAH - $TOTAL_MASUK_64) >= 0) {
                                                            echo nominal($TOTAL_PO_64_NASKAH - $TOTAL_MASUK_64);
                                                        } else {
                                                        ?>
                    <p style="color:red;"><?php echo "<strong>" . nominal($TOTAL_PO_64_NASKAH - $TOTAL_MASUK_64) . "</strong>"; ?></p>
                <?php
                                                        }


                ?>
            </th>


            <!-- BUKU JADI PER COVER 64 -->
            <?php
            $x_totalbkjdi64 = 0;
            $TOTAL_MASUK_64 = 0;
            foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {
                // $x = $tbl_stok_barang_detail_cover_list->nama_cover . "_TOTAL_BkJADI_64";

                // foreach ($total_per_cover as $key => $value) {
                //     if ($key == $x) {
                //         echo "<th style='text-align:right;font-size:1vw'> " . nominal($value) . " </th>";
                //         $x_totalbkjdi64 = $x_totalbkjdi64 + $value;
                //     }
                // }


                $x_TOTAL_COVER_64 = $tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock . "_TOTAL_GUDANG_COVER_64";
                $x_TOTAL_RETUR_COVER_64 = $tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock . "_TOTAL_RETUR_COVER_64";


                foreach ($total_per_cover as $total_per_cover_list) {
                    // print_r($total_per_cover_list);
                    if (!empty($total_per_cover_list->$x_TOTAL_COVER_64)) {
                        // DENGAN RETUR
                        echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_COVER_64) + ($total_per_cover_list->$x_TOTAL_RETUR_COVER_64)) . " </th>";
                        $TOTAL_MASUK_64 = $TOTAL_MASUK_64 + ($total_per_cover_list->$x_TOTAL_COVER_64) + ($total_per_cover_list->$x_TOTAL_RETUR_COVER_64);
                        // TANPA RETUR
                        // echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_COVER_64)) . " </th>";
                        // $TOTAL_MASUK_64 = $TOTAL_MASUK_64 + ($total_per_cover_list->$x_TOTAL_COVER_64);
                    } else {
                        echo "<th style='text-align:right;font-size:1vw'> " . 0 . " </th>";
                    }
                }
            }
            ?>


            <!-- TOTAL BUKU JADI 64 -->
            <th style="text-align:right;font-size:1vw"><?php echo nominal($TOTAL_MASUK_64); ?></th>

            <!-- TOTAL PER COVER BDP 64 -->
            <?php
            $TOTAL_FINISHING_BDP_64 = 0;
            foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {
                // $x = $tbl_stok_barang_detail_cover_list->nama_cover . "_TOTAL_BDP_64";
                // foreach ($total_per_cover as $key => $value) {
                //     if ($key == $x) {
                //         echo "<th style='text-align:right;font-size:1vw'> " . nominal($value) . " </th>";
                //     }
                // }

                $x_TOTAL_FINISHING_COVER_64 = $tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock . "_TOTAL_FINISHING_COVER_64";
                // $x_TOTAL_RETUR_COVER_64 = $tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock . "_TOTAL_RETUR_COVER_64";
                $x_TOTAL_COVER_64 = $tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock . "_TOTAL_GUDANG_COVER_64";

                foreach ($total_per_cover as $total_per_cover_list) {
                    // print_r($total_per_cover_list);
                    if (!empty($total_per_cover_list->$x_TOTAL_FINISHING_COVER_64)) {
                        // DENGAN RETUR
                        echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_FINISHING_COVER_64) - ($total_per_cover_list->$x_TOTAL_COVER_64)) . " </th>";
                        $TOTAL_FINISHING_BDP_64 = $TOTAL_FINISHING_BDP_64 + (($total_per_cover_list->$x_TOTAL_FINISHING_COVER_64) - ($total_per_cover_list->$x_TOTAL_COVER_64));
                        // TANPA RETUR
                        // echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_COVER_64)) . " </th>";
                        // $TOTAL_MASUK_64 = $TOTAL_MASUK_64 + ($total_per_cover_list->$x_TOTAL_COVER_64);
                    } else {
                        echo "<th style='text-align:right;font-size:1vw'> " . 0 . " </th>";
                    }
                }
            }
            ?>

            <!-- TOTAL FINISHING BDP 64 -->
            <th style="text-align:right;font-size:1vw"><?php echo nominal($TOTAL_FINISHING_BDP_64); ?></th>

            <!-- KETERANGAN -->
            <th style="text-align:center;font-size:1vw"></th>


        </tr>


        <!-- ====================96 ========================================================================================================================================== -->
        <tr>
            <th style="text-align:center;font-size:1vw" colspan="2">TOTAL 96</th>
            <th style="text-align:right;font-size:1vw"><?php echo nominal($TOTAL_PO_96_NASKAH);
                                                        ?></th>
            <?php
            $X_TOTALPO96 = 0;
            $TOTAL_MASUK_96 = 0;
            foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {
                // $x = $tbl_stok_barang_detail_cover_list->nama_cover . "_TOTAL_PO_96";

                // foreach ($total_per_cover as $key => $value) {
                //     if ($key == $x) {
                //         echo "<th style='text-align:right;font-size:1vw'> " . nominal($value) . " </th>";
                //         $X_TOTALPO96 = $X_TOTALPO96 + $value;
                //     }
                // }

                $x_TOTAL_COVER_96 = $tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock . "_TOTAL_FINISHING_COVER_96";
                // $x_TOTAL_RETUR_COVER_96 = $tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock . "_TOTAL_RETUR_COVER_96";


                foreach ($total_per_cover as $total_per_cover_list) {
                    // print_r($total_per_cover_list);
                    if (!empty($total_per_cover_list->$x_TOTAL_COVER_96)) {
                        // DENGAN RETUR
                        // echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_COVER_96) + ($total_per_cover_list->$x_TOTAL_RETUR_COVER_96)) . " </th>";
                        echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_COVER_96)) . " </th>";
                        // $TOTAL_MASUK_96 = $TOTAL_MASUK_96 + ($total_per_cover_list->$x_TOTAL_COVER_96) + ($total_per_cover_list->$x_TOTAL_RETUR_COVER_96);
                        $TOTAL_MASUK_96 = $TOTAL_MASUK_96 + ($total_per_cover_list->$x_TOTAL_COVER_96);
                        // TANPA RETUR
                        // echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_COVER_64)) . " </th>";
                        // $TOTAL_MASUK_64 = $TOTAL_MASUK_64 + ($total_per_cover_list->$x_TOTAL_COVER_64);
                    } else {
                        echo "<th style='text-align:right;font-size:1vw'> " . 0 . " </th>";
                    }
                }
            }
            ?>

            <!-- TOTAL MASUK -->
            <th style="text-align:right;font-size:1vw"><?php echo nominal($TOTAL_MASUK_96); ?></th>

            <!-- SELISIH TOTAL : NASKAH 96 - COVER 96   -->
            <th style="text-align:right;font-size:1vw"><?php
                                                        // echo nominal($TOTAL_PO_96_NASKAH - $X_TOTALPO96);

                                                        if (($TOTAL_PO_96_NASKAH - $TOTAL_MASUK_96) >= 0) {
                                                            echo nominal($TOTAL_PO_96_NASKAH - $TOTAL_MASUK_96);
                                                        } else {
                                                        ?>
                    <p style="color:red;"><?php echo "<strong>" . nominal($TOTAL_PO_96_NASKAH - $TOTAL_MASUK_96) . "</strong>"; ?></p>
                <?php
                                                        }


                ?>


            </th>

            <!-- BUKU JADI PER COVER 96 -->
            <?php
            $x_totalbkjadi = 0;
            $TOTAL_MASUK_96 = 0;
            foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {
                // $x = $tbl_stok_barang_detail_cover_list->nama_cover . "_TOTAL_BkJADI_96";

                // foreach ($total_per_cover as $key => $value) {
                //     if ($key == $x) {
                //         echo "<th style='text-align:right;font-size:1vw'> " . nominal($value) . " </th>";
                //         $x_totalbkjadi = $x_totalbkjadi + $value;
                //     }
                // }


                $x_TOTAL_COVER_96 = $tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock . "_TOTAL_GUDANG_COVER_96";
                $x_TOTAL_RETUR_COVER_96 = $tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock . "_TOTAL_RETUR_COVER_96";


                foreach ($total_per_cover as $total_per_cover_list) {
                    // print_r($total_per_cover_list);
                    if (!empty($total_per_cover_list->$x_TOTAL_COVER_96)) {
                        // DENGAN RETUR
                        echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_COVER_96) + ($total_per_cover_list->$x_TOTAL_RETUR_COVER_96)) . " </th>";
                        // echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_COVER_96)) . " </th>";
                        $TOTAL_MASUK_96 = $TOTAL_MASUK_96 + ($total_per_cover_list->$x_TOTAL_COVER_96) + ($total_per_cover_list->$x_TOTAL_RETUR_COVER_96);
                        // $TOTAL_MASUK_96 = $TOTAL_MASUK_96 + ($total_per_cover_list->$x_TOTAL_COVER_96);
                        // TANPA RETUR
                        // echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_COVER_64)) . " </th>";
                        // $TOTAL_MASUK_64 = $TOTAL_MASUK_64 + ($total_per_cover_list->$x_TOTAL_COVER_64);
                    } else {
                        echo "<th style='text-align:right;font-size:1vw'> " . 0 . " </th>";
                    }
                }
            }
            ?>

            <!-- TOTAL BUKU JADI 96 -->
            <th style="text-align:right;font-size:1vw"><?php echo nominal($TOTAL_MASUK_96);
                                                        ?></th>
            <?php
            $x_bdp96 = 0;
            $TOTAL_FINISHING_BDP_96 = 0;
            foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {
                // $x = $tbl_stok_barang_detail_cover_list->nama_cover . "_TOTAL_BDP_96";

                // foreach ($total_per_cover as $key => $value) {
                //     if ($key == $x) {
                //         echo "<th style='text-align:right;font-size:1vw'> " . nominal($value) . " </th>";
                //         $x_bdp96 = $x_bdp96 + $value;
                //     }
                // }

                $x_TOTAL_FINISHING_COVER_96 = $tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock . "_TOTAL_FINISHING_COVER_96";
                // $x_TOTAL_RETUR_COVER_64 = $tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock . "_TOTAL_RETUR_COVER_64";
                $x_TOTAL_COVER_96 = $tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock . "_TOTAL_GUDANG_COVER_96";


                foreach ($total_per_cover as $total_per_cover_list) {
                    // print_r($total_per_cover_list);
                    if (!empty($total_per_cover_list->$x_TOTAL_FINISHING_COVER_96)) {
                        // DENGAN RETUR
                        echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_FINISHING_COVER_96) - ($total_per_cover_list->$x_TOTAL_COVER_96)) . " </th>";
                        $TOTAL_FINISHING_BDP_96 = $TOTAL_FINISHING_BDP_96 + (($total_per_cover_list->$x_TOTAL_FINISHING_COVER_96) - ($total_per_cover_list->$x_TOTAL_COVER_96));
                        // TANPA RETUR
                        // echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_COVER_64)) . " </th>";
                        // $TOTAL_MASUK_64 = $TOTAL_MASUK_64 + ($total_per_cover_list->$x_TOTAL_COVER_64);
                    } else {
                        echo "<th style='text-align:right;font-size:1vw'> " . 0 . " </th>";
                    }
                }
            }
            ?>
            <th style="text-align:right;font-size:1vw"><?php echo nominal($TOTAL_FINISHING_BDP_96);
                                                        ?></th>
            <th style="text-align:center;font-size:1vw"></th>


        </tr>
    </tfoot>

    </tbody>
</table>