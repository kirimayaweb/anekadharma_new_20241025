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

                                <div class="col-12 card-title">

                                    <!-- <h3 class="card-title"> -->
                                    SISA STOCK = <?php echo "<strong>";
                                                    if (is_null($tingkat_selected)) {
                                                        echo "ALL [ ";
                                                    } else {
                                                        echo $tingkat_selected . " [ ";
                                                    }
                                                    echo $_SESSION['thn_selected'] . " / " . $_SESSION['semester_selected'] . " ]</strong>";

                                                    $x_thn=$_SESSION['thn_selected'];
                                                    ?>

                                                    <!-- SIMPAN KE TAHUN <?php  //print_r($x_thn+1 . " , semester = " . $_SESSION['semester_selected'] ); ?> -->

                                    <!-- </h3> -->
                                </div>
                       

                            </form>
                        </div>

                    </div>
                    <br />

                    <div class="row">
                        <div class="col-2"><?php //echo anchor(site_url('Trans_cetakinput/create_setting'), '<i class="fa fa-wpforms" aria-hidden="true"></i> Tambah Data STOCK', 'class="btn btn-danger btn-sm"'); 
                                            ?></div>
                        <div class="col-2" align="right">
                            <?php //echo anchor(
                                                                // site_url('tbl_stok_barang_detail/excel_stock_rekap/' . $tingkat_selected),
                                                                // '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Ms Excel, REKAP STOCK :' .
                                                                    // $tingkat_selected . ' Tahun : ' . $_SESSION['thn_selected'] . ' Semester :' . $_SESSION['semester_selected'],
                                                                // 'class="btn btn-success btn-sm"'
                                                            // ); ?>
                                                            </div>
                        <!-- <div class="col-2" align="left"><?php 
                              //                          echo anchor(site_url('tbl_stok_barang_detail/get_data_stock/' . $tingkat_selected), '<i class="fa fa-file-word-o" aria-hidden="true"></i> REKAP CETAK', 'class="btn btn-block btn-danger btn-sm"');
                                                        ?>
                        </div>-->
                        <!-- <div class="col-2" align="right"><?php //echo anchor(site_url('Trans_cetakinput/create_setting'), '<i class="fa fa-file-excel-o" aria-hidden="true"></i> PO Cetak', 'class="btn btn-success btn-sm"'); ?></div> -->
                        <!-- <div class="col-2" align="left"><?php //echo anchor(site_url('Trans_cetakinput/create_setting'), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Finishing', 'class="btn btn-primary btn-sm"'); ?></div> -->
                        <div class="col-4"></div>

                    </div>

                    <div class="card-body">

                        <!-- <table id="example1" class="table table-bordered table-striped"> -->
                        <!-- <table id="example9" class="display nowrap" style="width:100%"> -->
                        <table id="exampleFreeze" class="display nowrap" style="width:100%">
                            <thead>

                                <tr style="font-size:1vw;font-weight: bold">
                                    <th style="text-align:center;font-size:1vw">No</th>

                                    <th style="text-align:center;font-size:1vw">MAPEL</th>

                                    <?php
                                    $urutlama = 0;
                                    $X = $_SESSION['thn_selected']+1;
                                    foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {

                                        echo "<th style='text-align:center;font-size:1vw'> SISA STOCK : <br/>" . $tbl_stok_barang_detail_cover_list->nama_cover . "<br/>[BARU]<br/>" . anchor(site_url('Tbl_stok_barang_detail/get_data_stock_rekap/'.$tingkat_selected.'/sisa_stock_baru/'.$tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock), '<i class="fa fa-file-word-o" aria-hidden="true"></i> SIMPAN ke tahun = '.$X , 'class="btn btn-primary btn-sm"') . " </th>";
                                        // if ($urutlama == 0) {
                                        //     echo "<th style='text-align:center;font-size:1vw'> STOCK MASUK : <br/>" . $tbl_stok_barang_detail_cover_list->nama_cover . "<br/>[LAMA] </th>";
                                        //     ++$urutlama;
                                        // }
                                    }
                                    ?>

                                    <?php echo "<th style='text-align:center;font-size:1vw'> RETUR : <br/>   <br/>[BARU]<br/>" . anchor(site_url('Tbl_stok_barang_detail/get_data_stock_rekap/'.$tingkat_selected.'/sisa_stock_baru/'), '<i class="fa fa-file-word-o" aria-hidden="true"></i> SIMPAN ke tahun = '.$X , 'class="btn btn-primary btn-sm"') . " </th>"; ?>
                                    


                                </tr>
                            </thead>

                            <tbody>

                                <?php
                                $start = 0;
                                $TOTAL_STOCK_MASUK_BARU = 0;
                                $TOTAL_STOCK_MASUK_LAMA = 0;
                                $TOTAL_STOCK_KELUAR_BARU = 0;
                                $TOTAL_STOCK_KELUAR_LAMA = 0;
                                $SISA_STOCK_KELUAR_BARU = 0;
                                $SISA_STOCK_KELUAR_LAMA = 0;

                                $X_TOTAL_GUDANG_LAMA_64 = 0;
                                $X_TOTAL_GUDANG_LAMA_96 = 0;
                                $X_TOTAL_JUAL_LAMA = 0;

                                $X_TOTAL_STOCK_MASUK_64 = 0;
                                $X_TOTAL_STOCK_MASUK_96 = 0;

                                $X_TOTAL_STOCK_KELUAR_64 = 0;
                                $X_TOTAL_STOCK_KELUAR_96 = 0;

                                $X_TOTAL_STOCK_KELUAR_LAMA_64 = 0;
                                $X_TOTAL_STOCK_KELUAR_LAMA_96 = 0;

                                $TOTAL_RETUR_BARU_64 = 0;
                                $TOTAL_RETUR_BARU_96 = 0;
                                $TOTAL_RETUR_LAMA_64 = 0;
                                $TOTAL_RETUR_LAMA_96 = 0;

                                foreach ($tbl_stok_barang_detail_data as $tbl_stok_barang_detail) 
                                {
                                ?>
                                    <tr>
                                        <td style="text-align:center;font-size:1vw" width="10px"><?php echo ++$start ?></td>

                                        <td style="text-align:left;font-size:1vw">
                                            <?php
                                            echo $tbl_stok_barang_detail->mapel;

                                            ?>
                                        </td>




                                        <?php
                                        $x_totalStockMasuk_LAMA = 0;
                                        $x_totalStockMasuk_BARU = 0;
                                        $x_totalStockMasuk = 0;

                                        $x_LAMA = $tbl_stok_barang_detail_cover_list->nama_cover . "_LAMA";
                                        $x_BARU = $tbl_stok_barang_detail_cover_list->nama_cover . "_BARU";
                                        $x_RETUR_LAMA = $tbl_stok_barang_detail_cover_list->nama_cover . "_RETUR_LAMA";
                                        $x_RETUR_BARU = $tbl_stok_barang_detail_cover_list->nama_cover . "_RETUR_BARU";


                                        $x_GUDANG_BARU_64 = $tbl_stok_barang_detail_cover_list->nama_cover . "_" . $tbl_stok_barang_detail->uuid_produk . "_GUDANG_BARU_64";
                                        $x_RETUR_BARU_64 = $tbl_stok_barang_detail_cover_list->nama_cover . "_" . $tbl_stok_barang_detail->uuid_produk . "_RETUR_BARU_64";
                                        $x_JUAL_BARU_64 = $tbl_stok_barang_detail_cover_list->nama_cover . "_" . $tbl_stok_barang_detail->uuid_produk . "_JUAL_BARU_64";

                                        $x_GUDANG_BARU_96 = $tbl_stok_barang_detail_cover_list->nama_cover . "_" . $tbl_stok_barang_detail->uuid_produk . "_GUDANG_BARU_96";
                                        $x_RETUR_BARU_96 = $tbl_stok_barang_detail_cover_list->nama_cover . "_" . $tbl_stok_barang_detail->uuid_produk . "_RETUR_BARU_96";
                                        $x_JUAL_BARU_96 = $tbl_stok_barang_detail_cover_list->nama_cover . "_" . $tbl_stok_barang_detail->uuid_produk . "_JUAL_BARU_96";

                                        $x_GUDANG_LAMA_64 = $tbl_stok_barang_detail->uuid_produk . "_GUDANG_LAMA_64";
                                        $x_RETUR_LAMA_64 = $tbl_stok_barang_detail->uuid_produk . "_RETUR_LAMA_64";
                                        $x_JUAL_LAMA_64 = $tbl_stok_barang_detail->uuid_produk . "_JUAL_LAMA_64";

                                        $x_GUDANG_LAMA_96 = $tbl_stok_barang_detail->uuid_produk . "_GUDANG_LAMA_96";
                                        $x_RETUR_LAMA_96 = $tbl_stok_barang_detail->uuid_produk . "_RETUR_LAMA_96";
                                        $x_JUAL_LAMA_96 = $tbl_stok_barang_detail->uuid_produk . "_JUAL_LAMA_96";

                                        $urutlama = 0;

                                        $X_TOTAL_STOCK_MASUK = 0;

                                        $TOTAL_RETUR_MAPEL_BARU_64 = 0;
                                        $TOTAL_RETUR_MAPEL_BARU_96 = 0;

                                        $TOTAL_RETUR_MAPEL_LAMA_64 = 0;
                                        $TOTAL_RETUR_MAPEL_LAMA_96 = 0;


                                        $x_totalStockKeluar_JUAL_LAMA = 0;
                                        $x_totalStockKeluar_JUAL_BARU = 0;
                                        $x_totalStockKeluar = 0;

                                        $urutlama = 0;
                                        $X_TOTAL_STOCK_KELUAR_64 = 0;
                                        $X_TOTAL_STOCK_KELUAR_96 = 0;

                                        foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {


                                            $x_GUDANG_BARU_64 = $tbl_stok_barang_detail_cover_list->nama_cover . "_" . $tbl_stok_barang_detail->uuid_produk . "_GUDANG_BARU_64";
                                            $x_RETUR_BARU_64 = $tbl_stok_barang_detail_cover_list->nama_cover . "_" . $tbl_stok_barang_detail->uuid_produk . "_RETUR_BARU_64";



                                            $x_JUAL_BARU_64 = $tbl_stok_barang_detail_cover_list->nama_cover . "_" . $tbl_stok_barang_detail->uuid_produk . "_JUAL_BARU_64";

                                            $x_GUDANG_BARU_96 = $tbl_stok_barang_detail_cover_list->nama_cover . "_" . $tbl_stok_barang_detail->uuid_produk . "_GUDANG_BARU_96";
                                            $x_RETUR_BARU_96 = $tbl_stok_barang_detail_cover_list->nama_cover . "_" . $tbl_stok_barang_detail->uuid_produk . "_RETUR_BARU_96";
                                            $x_JUAL_BARU_96 = $tbl_stok_barang_detail_cover_list->nama_cover . "_" . $tbl_stok_barang_detail->uuid_produk . "_JUAL_BARU_96";

                                           

                                            $x_GUDANG_LAMA_96 = $tbl_stok_barang_detail->uuid_produk . "_" . $tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock . "_GUDANG_LAMA_96";
                                            $x_RETUR_LAMA_96 = $tbl_stok_barang_detail->uuid_produk . "_RETUR_LAMA_96";
                                            $x_JUAL_LAMA_96 = $tbl_stok_barang_detail->uuid_produk . "_JUAL_LAMA_96";

                                            if (!empty($tbl_stok_barang_detail->$x_GUDANG_BARU_64) or !empty($tbl_stok_barang_detail->$x_GUDANG_BARU_96)) {
                                                
                                                // echo "<td style='text-align:right;font-size:1vw'>" . nominal(($tbl_stok_barang_detail->$x_GUDANG_BARU_64) + ($tbl_stok_barang_detail->$x_GUDANG_BARU_96 )) . "</td>";
                                                
                                                $X_TOTAL_STOCK_MASUK = $X_TOTAL_STOCK_MASUK + (($tbl_stok_barang_detail->$x_GUDANG_BARU_64) + ($tbl_stok_barang_detail->$x_GUDANG_BARU_96));
                                                $X_TOTAL_STOCK_MASUK_64 = $X_TOTAL_STOCK_MASUK_64 + ($tbl_stok_barang_detail->$x_GUDANG_BARU_64);
                                                $X_TOTAL_STOCK_MASUK_96 = $X_TOTAL_STOCK_MASUK_96 + ($tbl_stok_barang_detail->$x_GUDANG_BARU_96);
                                            } else {
                                                // echo "<td style='text-align:right;font-size:1vw'> " . nominal(0) . "</td>";
                                            }



                                            if (!empty($tbl_stok_barang_detail->$x_RETUR_BARU_64)) {
                                                $TOTAL_RETUR_MAPEL_BARU_64 = $TOTAL_RETUR_MAPEL_BARU_64 + $tbl_stok_barang_detail->$x_RETUR_BARU_64;
                                            } else {
                                                $TOTAL_RETUR_MAPEL_BARU_64 = $TOTAL_RETUR_MAPEL_BARU_64 + 0;
                                            }


                                            if (!empty($tbl_stok_barang_detail->$x_RETUR_BARU_96)) {
                                                $TOTAL_RETUR_MAPEL_BARU_96 = $TOTAL_RETUR_MAPEL_BARU_96 + $tbl_stok_barang_detail->$x_RETUR_BARU_96;
                                            } else {
                                                $TOTAL_RETUR_MAPEL_BARU_96 = $TOTAL_RETUR_MAPEL_BARU_96 + 0;
                                            }



                                            if (!empty($tbl_stok_barang_detail->$x_RETUR_LAMA_64)) {
                                                $TOTAL_RETUR_MAPEL_LAMA_64 = $TOTAL_RETUR_MAPEL_LAMA_64 + $tbl_stok_barang_detail->$x_RETUR_LAMA_64;
                                            } else {
                                                $TOTAL_RETUR_MAPEL_LAMA_64 = $TOTAL_RETUR_MAPEL_LAMA_64 + 0;
                                            }


                                            if (!empty($tbl_stok_barang_detail->$x_RETUR_LAMA_96)) {
                                                $TOTAL_RETUR_MAPEL_LAMA_96 = $TOTAL_RETUR_MAPEL_LAMA_96 + $tbl_stok_barang_detail->$x_RETUR_LAMA_96;
                                            } else {
                                                $TOTAL_RETUR_MAPEL_LAMA_96 = $TOTAL_RETUR_MAPEL_LAMA_96 = 0;
                                            }





                                            //STOCK KELUAR
                                            $x_LAMA = $tbl_stok_barang_detail_cover_list->nama_cover . "_LAMA";
                                            $x_BARU = $tbl_stok_barang_detail_cover_list->nama_cover . "_BARU";
                                            $x_RETUR_LAMA = $tbl_stok_barang_detail_cover_list->nama_cover . "_RETUR_LAMA";
                                            $x_RETUR_BARU = $tbl_stok_barang_detail_cover_list->nama_cover . "_RETUR_BARU";


                                            $x_GUDANG_BARU_64 = $tbl_stok_barang_detail_cover_list->nama_cover . "_" . $tbl_stok_barang_detail->uuid_produk . "_GUDANG_BARU_64";
                                            $x_RETUR_BARU_64 = $tbl_stok_barang_detail_cover_list->nama_cover . "_" . $tbl_stok_barang_detail->uuid_produk . "_RETUR_BARU_64";
                                            $x_JUAL_BARU_64 = $tbl_stok_barang_detail_cover_list->nama_cover . "_" . $tbl_stok_barang_detail->uuid_produk . "_JUAL_BARU_64";

                                            $x_GUDANG_BARU_96 = $tbl_stok_barang_detail_cover_list->nama_cover . "_" . $tbl_stok_barang_detail->uuid_produk . "_GUDANG_BARU_96";
                                            $x_RETUR_BARU_96 = $tbl_stok_barang_detail_cover_list->nama_cover . "_" . $tbl_stok_barang_detail->uuid_produk . "_RETUR_BARU_96";
                                            $x_JUAL_BARU_96 = $tbl_stok_barang_detail_cover_list->nama_cover . "_" . $tbl_stok_barang_detail->uuid_produk . "_JUAL_BARU_96";

                                            $x_GUDANG_LAMA_64 = $tbl_stok_barang_detail->uuid_produk . "_GUDANG_LAMA_64";
                                            $x_RETUR_LAMA_64 = $tbl_stok_barang_detail->uuid_produk . "_RETUR_LAMA_64";
                                            $x_JUAL_LAMA_64 = $tbl_stok_barang_detail->uuid_produk . "_JUAL_LAMA_64";

                                            $x_GUDANG_LAMA_96 = $tbl_stok_barang_detail->uuid_produk . "_GUDANG_LAMA_96";
                                            $x_RETUR_LAMA_96 = $tbl_stok_barang_detail->uuid_produk . "_RETUR_LAMA_96";
                                            $x_JUAL_LAMA_96 = $tbl_stok_barang_detail->uuid_produk . "_JUAL_LAMA_96";

                                            if (!empty($tbl_stok_barang_detail->$x_JUAL_BARU_64) or !empty($tbl_stok_barang_detail->$x_JUAL_BARU_96)) {
                                                
                                                echo "<td style='text-align:right;font-size:1vw'>" . nominal( (($tbl_stok_barang_detail->$x_GUDANG_BARU_64) + ($tbl_stok_barang_detail->$x_GUDANG_BARU_96 )) - ($tbl_stok_barang_detail->$x_JUAL_BARU_64 + $tbl_stok_barang_detail->$x_JUAL_BARU_96)) . "</td>";
                                                
                                                
                                                
                                                $X_TOTAL_STOCK_KELUAR_64 = $X_TOTAL_STOCK_KELUAR_64 + $tbl_stok_barang_detail->$x_JUAL_BARU_64;
                                                $X_TOTAL_STOCK_KELUAR_96 = $X_TOTAL_STOCK_KELUAR_96 + $tbl_stok_barang_detail->$x_JUAL_BARU_96;
                                            } else {
                                                echo "<td style='text-align:right;font-size:1vw'>" . nominal(0) . "</td>";
                                            }





                                        }



                                        ?>

                                        <!-- RETUR BARU -->
                                        <td style="text-align:right;font-size:1vw"> <?php
                                                                                    echo  nominal($TOTAL_RETUR_MAPEL_BARU_64 + $TOTAL_RETUR_MAPEL_BARU_96);
                                                                                    $TOTAL_RETUR_BARU_64 = $TOTAL_RETUR_BARU_64 + ($TOTAL_RETUR_MAPEL_BARU_64);
                                                                                    $TOTAL_RETUR_BARU_96 = $TOTAL_RETUR_BARU_96 + ($TOTAL_RETUR_MAPEL_BARU_96);
                                                                                    ?>
                                        </td>
                                        




                                    </tr>
                                <?php
                                }
                                ?>

                            </tbody>

                            <tfoot>
                                <!-- ========================  64  ==================== -->
                                <th style="text-align:center;font-size:1vw" colspan="2"> TOTAL 64</th>

                                <!-- <th style="text-align:center;font-size:1vw">MAPEL</th> -->

                                <?php
                                $urutlama = 0;
                                $TOTAL_MASUK_64 = 0;
                                $TOTAL_RETUR_64 = 0;
                                foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {
                                    $x_TOTAL_COVER_64 = $tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock . "_TOTAL_GUDANG_COVER_64";
                                    $x_TOTAL_RETUR_COVER_64 = $tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock . "_TOTAL_RETUR_COVER_64";


                                    foreach ($total_per_cover as $total_per_cover_list) {
                                        // print_r($total_per_cover_list);
                                        if (!empty($total_per_cover_list->$x_TOTAL_COVER_64)) {
                                            // DENGAN RETUR
                                            // echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_COVER_64) + ($total_per_cover_list->$x_TOTAL_RETUR_COVER_64)) . " </th>";
                                            echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_COVER_64)) . " </th>";
                                            // $TOTAL_MASUK_64 = $TOTAL_MASUK_64 + ($total_per_cover_list->$x_TOTAL_COVER_64) + ($total_per_cover_list->$x_TOTAL_RETUR_COVER_64);
                                            $TOTAL_MASUK_64 = $TOTAL_MASUK_64 + ($total_per_cover_list->$x_TOTAL_COVER_64);
                                            $TOTAL_RETUR_64 = $TOTAL_RETUR_64 + ($total_per_cover_list->$x_TOTAL_RETUR_COVER_64);
                                            // TANPA RETUR
                                            // echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_COVER_64)) . " </th>";
                                            // $TOTAL_MASUK_64 = $TOTAL_MASUK_64 + ($total_per_cover_list->$x_TOTAL_COVER_64);
                                        } else {
                                            echo "<th style='text-align:right;font-size:1vw'> " . 0 . " </th>";
                                        }
                                    }


                                    // if ($urutlama == 0) {
                                    //     echo "<th style='text-align:center;font-size:1vw'> STOCK MASUK : <br/>" . $tbl_stok_barang_detail_cover_list->nama_cover . "<br/>[LAMA] </th>";
                                    //     ++$urutlama;
                                    // }
                                }
                                ?>
                                <!-- RETUR <br /> [BARU] -->
                                <th style="text-align:right;font-size:1vw"> <?php echo nominal($TOTAL_RETUR_BARU_64) ?></th>
                                
                               

                                </tr>


                                <!-- ========================  96  ==================== -->

                                <th style="text-align:center;font-size:1vw" colspan="2"> TOTAL 96</th>

                                <!-- <th style="text-align:center;font-size:1vw">MAPEL</th> -->

                                <?php
                                $urutlama = 0;
                                $TOTAL_MASUK_96 = 0;
                                $TOTAL_RETUR_96 = 0;
                                foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {
                                    $x_TOTAL_COVER_96 = $tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock . "_TOTAL_GUDANG_COVER_96";
                                    $x_TOTAL_RETUR_COVER_96 = $tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock . "_TOTAL_RETUR_COVER_96";


                                    foreach ($total_per_cover as $total_per_cover_list) {
                                        // print_r($total_per_cover_list);
                                        if (!empty($total_per_cover_list->$x_TOTAL_COVER_96)) {
                                            // DENGAN RETUR
                                            // echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_COVER_96) + ($total_per_cover_list->$x_TOTAL_RETUR_COVER_96)) . " </th>";
                                            echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_COVER_96)) . " </th>";
                                            $TOTAL_MASUK_96 = $TOTAL_MASUK_96 + ($total_per_cover_list->$x_TOTAL_COVER_96);
                                            $TOTAL_RETUR_96 = $TOTAL_RETUR_96 + ($total_per_cover_list->$x_TOTAL_RETUR_COVER_96);
                                            // TANPA RETUR
                                            // echo "<th style='text-align:right;font-size:1vw'> " . nominal(($total_per_cover_list->$x_TOTAL_COVER_64)) . " </th>";
                                            // $TOTAL_MASUK_64 = $TOTAL_MASUK_64 + ($total_per_cover_list->$x_TOTAL_COVER_64);
                                        } else {
                                            echo "<th style='text-align:right;font-size:1vw'> " . 0 . " </th>";
                                        }
                                    }


                                    // if ($urutlama == 0) {
                                    //     echo "<th style='text-align:center;font-size:1vw'> STOCK MASUK : <br/>" . $tbl_stok_barang_detail_cover_list->nama_cover . "<br/>[LAMA] </th>";
                                    //     ++$urutlama;
                                    // }
                                }
                                ?>
                                <!-- RETUR [BARU] -->
                                <th style="text-align:right;font-size:1vw"><?php echo nominal($TOTAL_RETUR_BARU_96) ?> </th>
                                

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






<link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
<style type="text/css">
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
</style>





<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#example').DataTable({
            "scrollY": 500,
            "scrollX": true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#example9').DataTable({
            "scrollY": 500,
            "scrollX": true
        });
    });
</script>