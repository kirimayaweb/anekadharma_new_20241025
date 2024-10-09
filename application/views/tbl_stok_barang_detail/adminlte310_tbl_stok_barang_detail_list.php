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

                                    <!-- <select name="level_sekolah" id="level_sekolah" class="form-control select2" style="width: 200px; height: 10px;">
                                        <option value="">Pilih Tingkat</option>
                                        <option value="MA">MA</option>
                                        <option value="MTS">MTS</option>
                                        <option value="MI">MI</option>
                                        <option value="SMP">SMP K13</option>
											<option value="PGSMP">PGSMP K13</option>
											<option value="SMPKUMER">SMP KUMER</option>
											<option value="PGSMPKUMER">PGSMP KUMER</option>
											<option value="SD">SD K13</option>
											<option value="PGSD">PGSD K13</option>
											<option value="SDKUMER">SD KUMER</option>
											<option value="PGSDKUMER">PGSD KUMER</option>
                                    </select> -->

                                    <select name="level_sekolah" id="level_sekolah" class="form-control select2" style="width: 100%; height: 40px;">
												<option value="">Pilih Tingkat</option>
												<?php																								
                                                    // $sql = "select tingkat from  tbl_produk_mapel_referensi group by tingkat order by  tingkat asc";
                                                    // foreach ($this->db->query($sql)->result() as $m) {
                                                    foreach ($this->Auto_load_model->cek_tingkat_by_user() as $m) {
                                                        echo "<option value='$m->tingkat' ";echo ">  " . strtoupper($m->tingkat) . "</option>";
                                                    }
												?>
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

                        <!-- TABLE CONTENT -->

                        <!-- <table id="example" class="display nowrap" style="width:100%"> -->
                        <!-- <table id="example9" class="display nowrap" style="width:100%"> -->
                        <table id="exampleFreeze" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="text-align:center;font-size:1vw">No</th>

                                    <th style="text-align:center;font-size:1vw">MAPEL</th>
                                    <th style="text-align:center;font-size:1vw">PO <br/> NASKAH</th>
                                    <?php
                                    // foreach ($cover_finishing_data as $tbl_stok_barang_detail_cover_list) {
                                    //     echo "<th style='text-align:center;font-size:1vw'> PO <br/>" . $tbl_stok_barang_detail_cover_list['nama_cover'] . "</th>";
                                    // }

                                    foreach($cover_finishing_data as $key => $value) {
                                        echo "<th style='text-align:center;font-size:1vw'> PO COVER <br/>" . $value . "</th>";
                                    }
                                    ?>


                                    <th style="text-align:center;font-size:1vw">TOTAL <br/> PO COVER</th>
                                    <th style="text-align:center;font-size:1vw">SELISIH <br/> ( NASKAH - PO COVER )</th>

                                    <?php
                                    // foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {
                                    //     echo "<th style='text-align:center;font-size:1vw'> Buku Jadi : " . $tbl_stok_barang_detail_cover_list->nama_cover . "</th>";
                                    // }

                                    foreach($cover_gudang_data as $key => $value) {
                                        echo "<th style='text-align:center;font-size:1vw'> PO <br/>" . $value . "</th>";
                                    }
                                    ?>
                                    
                                    <th style="text-align:center;font-size:1vw">TOTAL <br/> BUKU JADI</th>
                                    
                                    <?php
                                    // foreach ($tbl_stok_barang_detail_cover as $tbl_stok_barang_detail_cover_list) {
                                    //     echo "<th style='text-align:center;font-size:1vw'> BDP : " . $tbl_stok_barang_detail_cover_list->nama_cover . "</th>";
                                    // }

                                    foreach($data_BDP_cover as $key => $value) {
                                        echo "<th style='text-align:center;font-size:1vw'> BDP <br/>" . $value . "</th>";
                                    }

                                    ?>
                                    
                                    <th style="text-align:center;font-size:1vw">TOTAL <br/> BDP</th>
                                    
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

                                            // if(jumlah_halaman_stock)

                                            $TOTAL_NASKAH = $TOTAL_NASKAH + $tbl_stok_barang_detail->jumlah_naskah;
                                            ?>
                                        </td>

                                        <!-- COVER -->
                                        <?php
                                        $x_total = 0;
                                        // foreach ($cover_finishing_data as $tbl_stok_barang_detail_cover_list) {
                                        //     $x = $tbl_stok_barang_detail_cover_list->uuid_cover_produk_stock . "_FINISHING";
                                        //     $y = $tbl_stok_barang_detail_cover_list->nama_cover . "_FINISHING";

                                        //     echo "<td style='text-align:right;font-size:1vw' >" . nominal($tbl_stok_barang_detail->$y) . "</td>";
                                        //     $x_total = $x_total + $tbl_stok_barang_detail->$x;
                                        // }

                                        foreach($cover_finishing_data as $key => $value) {
                                            $x = $key . "_FINISHING";
                                            $y = $value. "_FINISHING";

                                            echo "<td style='text-align:right;font-size:1vw' >" . nominal($tbl_stok_barang_detail->$y) . "</td>";
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

                                        foreach($cover_gudang_data as $key => $value) {
                                            // echo "<th style='text-align:center;font-size:1vw'> PO <br/>" . $value . "</th>";


                                            $x_GUDANG = $value . "_GUDANG";
                                            $x_JUAL = $value . "_JUAL";
                                            $x_PENJUALAN_KREDIT = $value . "_PENJUALAN_KREDIT";
                                            $x_PENJUALAN_TUNAI = $value . "_PENJUALAN_TUNAI";
                                            $x_RETUR = $value . "_RETUR";

                                            
                                           
                                                    
                                                
                                                $sisa_STOCK = $tbl_stok_barang_detail->$x_GUDANG + $tbl_stok_barang_detail->$x_RETUR - ($tbl_stok_barang_detail->$x_PENJUALAN_KREDIT + $tbl_stok_barang_detail->$x_PENJUALAN_TUNAI ) ;

                                                echo "<td style='text-align:right;font-size:1vw'> " .   "gudang :<strong>" . nominal($tbl_stok_barang_detail->$x_GUDANG) . "</strong><br/> Retur :<strong>" . nominal($tbl_stok_barang_detail->$x_RETUR) . "</strong><br/> Jual :<strong>" . nominal($tbl_stok_barang_detail->$x_PENJUALAN_KREDIT) . "</strong><br/> Jual Tunai:<strong>" . nominal($tbl_stok_barang_detail->$x_PENJUALAN_TUNAI) . "</strong><br/>-----<br/> gudang+retur-jual-jual tunai :<br/><strong>" . nominal($sisa_STOCK) . "</td>";
                                            

                                            $x_totalBJ = $x_totalBJ + $sisa_STOCK;

                                        }

                                        ?>


                                        
                                        <td style="text-align:right;font-size:1vw"> <?php
                                                                                    echo  "<strong>" . nominal($x_totalBJ) . "</strong>";
                                                                                    $TOTAL_BUKUJADI = $TOTAL_BUKUJADI + $x_totalBJ;
                                                                                    ?>
                                        </td>

                                        <!-- BUKU DALAM PROSES -->
                                        <?php
                                        $x_totalBDP = 0;

                                        foreach($data_BDP_cover as $key => $value) {
                                            $x_FINISHING = $value . "_FINISHING";
                                            $x_GUDANG = $value . "_GUDANG";

                                            // $thn_sekarang = date('Y');
                                            // if (($tbl_stok_barang_detail->$x_FINISHING) <= 0) {
                                            //     $jmlh_bdp = $tbl_stok_barang_detail->$x_FINISHING;
                                            // } else {
                                            //     $jmlh_bdp = $tbl_stok_barang_detail->$x_FINISHING - $tbl_stok_barang_detail->$x_GUDANG;
                                            // }

                                            

                                            //check if cover = buku lama
                                            $this->db->where('uuid_cover', $key);
                                            $get_sys_cover = $this->db->get('sys_cover')->row_array();
                                            
                                            if($get_sys_cover['keterangan'] == "buku_lama" ){

                                                $jmlh_bdp = $tbl_stok_barang_detail->$x_FINISHING;
                                                if($jmlh_bdp > 0){
                                                    echo "<td style='text-align:right;font-size:1vw'>" . "Total per cover: <strong>" . nominal($tbl_stok_barang_detail->$x_FINISHING) . "</strong><br/> Gudang:<strong>" . nominal($tbl_stok_barang_detail->$x_GUDANG) . "</strong><br/>---------<br/> Total Di Gudang: <strong>" . nominal($tbl_stok_barang_detail->$x_GUDANG) . "</strong></td>";
                                                }else{
                                                    echo "<td style='text-align:right;font-size:1vw'>" . "Total per cover: <strong>" . nominal($tbl_stok_barang_detail->$x_FINISHING) . "</strong><br/> Gudang:<strong>" . nominal($tbl_stok_barang_detail->$x_GUDANG) . "</strong><br/>---------<br/> Total Di Gudang: <strong> " . nominal($tbl_stok_barang_detail->$x_GUDANG) . "</strong></td>";                                                
                                                }
                                            }else{
                                        
                                                $jmlh_bdp = $tbl_stok_barang_detail->$x_FINISHING - $tbl_stok_barang_detail->$x_GUDANG;
                                                if($jmlh_bdp > 0){
                                                    echo "<td style='text-align:right;font-size:1vw'>" . "Total per cover: <strong>" . nominal($tbl_stok_barang_detail->$x_FINISHING) . "</strong><br/> Gudang:<strong>" . nominal($tbl_stok_barang_detail->$x_GUDANG) . "</strong><br/>---------<br/> Total per cover - Di Gudang: <strong>" . nominal($jmlh_bdp) . "</strong></td>";
                                                }else{
                                                    echo "<td style='text-align:right;font-size:1vw'>" . "Total per cover: <strong>" . nominal($tbl_stok_barang_detail->$x_FINISHING) . "</strong><br/> Gudang:<strong>" . nominal($tbl_stok_barang_detail->$x_GUDANG) . "</strong><br/>---------<br/> Total per cover - Di Gudang: <strong style='color:red;'> " . nominal($jmlh_bdp) . "</strong></td>";                                                
                                                }

                                            }

                                            $x_totalBDP = $x_totalBDP + ($jmlh_bdp);

                                        }

                                        ?>


                                        <td style="text-align:right;font-size:1vw"> <?php
                                                                                    if($x_totalBDP > 0){
                                                                                        echo  nominal($x_totalBDP);
                                                                                    }else{
                                                                                        echo "<strong style='color:red;'>". nominal($x_totalBDP) ."</strong>";
                                                                                    }
                                                                                    $TOTAL_BUKUDALAMPROSES = $TOTAL_BUKUDALAMPROSES + $x_totalBDP;
                                                                                    ?>
                                        </td>
                                        <td></td>



                                    </tr>
                                <?php
                                }
                                ?>

                            <tfoot>
                                <!-- ================== 32 ===================================================================================================================================== -->
                                

                                <!-- ================== 64 ===================================================================================================================================== -->
                                <tr>
                                    <th style="text-align:center;font-size:1vw"  colspan="2">TOTAL 64</th>

                                        <?php 
                                            if($TOTAL_NASKAH_PER_halaman){
                                                $x=0;
                                                foreach($TOTAL_NASKAH_PER_halaman as $key => $value) {
                                                    $x_TOTAL_NASKAH_64 = "TOTAL_NASKAH_64";
                                                    if($key == $x_TOTAL_NASKAH_64){
                                                        echo "<th style='text-align:right;font-size:1vw'> " . nominal($value) . " </th>";
                                                        $x=1;
                                                    }
                                                  
                                                }
                                                if($x == 0)
                                                {
                                                    echo "<th style='text-align:right;font-size:1vw'> 0 </th>";
                                                }
                                            }else{
                                                echo "<th style='text-align:right;font-size:1vw'> 0 </th>";
                                            }
                                        ?>

                                    <!-- FINISHING PER COVER -->
                                    <?php
                                    $X_TOTALPO64 = 0;
                                    $TOTAL_MASUK_64 = 0;

                                    foreach($cover_finishing_data as $key => $value) {
                                        $X_uuid_cover = $key;
                                        foreach($data_TOTAL_cover_finishing as $key => $value) {
                                            $x_TOTAL_COVER_64 = "TOTAL_FINISHING_64_" . $X_uuid_cover;
                                            if($key == $x_TOTAL_COVER_64){
                                                echo "<th style='text-align:right;font-size:1vw'> " . nominal($value) . " </th>";
                                                $TOTAL_MASUK_64 = $TOTAL_MASUK_64 + ($value);
                                            }
                                        }
                                    }

                                    ?>

                                    <!-- TOTAL COVER / FINISHING -->
                                    <th style="text-align:right;font-size:1vw"><?php echo nominal($TOTAL_MASUK_64); ?></th>

                                    <!-- TOTAL SELISIH : COVER / FINISHING - PO CETAK  -->
                                    <th style="text-align:right;font-size:1vw">
                                    <?php
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

                                    foreach($cover_gudang_data as $key => $value) {

                                        $x_TOTAL_COVER_64 = $key . "_TOTAL_GUDANG_COVER_64";
                                        $x_TOTAL_RETUR_COVER_64 = $key . "_TOTAL_RETUR_COVER_64";

                                        $X_uuid_cover = $key;
                                        foreach($data_TOTAL_uuid_cover_gudang_halaman as $key => $value) {
                                            $x_TOTAL_COVER_GUDANG_64 = "TOTAL_GUDANG_64_" . $X_uuid_cover;
                                            if($key == $x_TOTAL_COVER_GUDANG_64){
                                                echo "<th style='text-align:right;font-size:1vw'> " . $value . " </th>";
                                                $TOTAL_MASUK_64 = $TOTAL_MASUK_64 + ($value) ;
                                            }
                                        }
                                    }
                                    ?>


                                    <!-- TOTAL BUKU JADI 64 -->
                                    <th style="text-align:right;font-size:1vw"><?php echo nominal($TOTAL_MASUK_64); ?></th>

                                    <!-- TOTAL PER COVER BDP 64 -->
                                    <?php
                                    

                                    foreach($data_BDP_cover as $key => $value) {

                                        $TOTAL_FINISHING_BDP_64 = 0;

                                        $x_TOTAL_FINISHING_COVER_64 = $key . "_TOTAL_FINISHING_COVER_64";
                                        $x_TOTAL_GUDANG_COVER_64 = $key . "_TOTAL_GUDANG_COVER_64";
                                        
                                        $X_uuid_cover = $key;
                                        foreach($data_TOTAL_cover_finishing as $key => $value) {
                                            $x_TOTAL_COVER_64 = "TOTAL_FINISHING_64_" . $X_uuid_cover;
                                            if($key == $x_TOTAL_COVER_64){
                                                $x_finishing = $value;
                                            }
                                        }

                                        $x_gudang=0;
                                        foreach($data_TOTAL_uuid_cover_gudang_halaman as $key => $value) {
                                            $x_TOTAL_COVER_GUDANG_64 = "TOTAL_GUDANG_64_" . $X_uuid_cover;
                                            if($key == $x_TOTAL_COVER_GUDANG_64){
                                                $x_gudang = $value;
                                            }
                                        }
                                        $TOTAL_FINISHING_BDP_64 = $TOTAL_FINISHING_BDP_64 + ($x_finishing - $x_gudang);                                                                                   


                                        if(($x_finishing-$x_gudang)>0){
                                            echo "<th style='text-align:right;font-size:1vw'> " . nominal($x_finishing-$x_gudang)  . " </th>";
                                        }else{
                                            echo "<th style='text-align:right;font-size:1vw;color:red;'> " . nominal($x_finishing-$x_gudang)  . " </th>";
                                        }
    
                                        // echo "<th style='text-align:right;font-size:1vw'> " . nominal($x_finishing-$x_gudang)  . " </th>";


                                    }

                                    ?>

                                    <!-- TOTAL FINISHING BDP 64 -->
                                    <th style="text-align:right;font-size:1vw">
                                        <?php 
                                            if($TOTAL_BUKUDALAMPROSES > 0){
                                                echo nominal($TOTAL_BUKUDALAMPROSES);
                                            }else{
                                                echo "<strong style='color:red;'>". nominal($TOTAL_BUKUDALAMPROSES) ."</strong>";
                                            }

                                        ?>
                                    </th>

                                    <!-- KETERANGAN -->
                                    <th style="text-align:center;font-size:1vw"></th>

                                </tr>


                                <!-- ==================== 96 ========================================================================================================= -->
                                
                                <tr>
                                    <th style="text-align:center;font-size:1vw"  colspan="2">TOTAL 96</th>

                                        <!-- //TOTAL NASKAH PER HALAMAN -->
                                        <?php 
                                            if($TOTAL_NASKAH_PER_halaman){
                                            $x=0;
                                            foreach($TOTAL_NASKAH_PER_halaman as $key => $value) {
                                                    $x_TOTAL_NASKAH_96 = "TOTAL_NASKAH_96";
                                                    if($key == $x_TOTAL_NASKAH_96){
                                                        echo "<th style='text-align:right;font-size:1vw'> " . nominal($value) . " </th>";
                                                        $x=1;
                                                    }
                                                    
                                                }
                                                if($x == 0)
                                                    {
                                                        echo "<th style='text-align:right;font-size:1vw'> 0 </th>";
                                                    }
                                            }else{
                                                echo "<th style='text-align:right;font-size:1vw'> 0 </th>";
                                            }
                                        ?>

                                    <!-- FINISHING PER COVER -->
                                    <?php
                                    $X_TOTALPO96 = 0;
                                    $TOTAL_MASUK_96 = 0;

                                    foreach($cover_finishing_data as $key => $value) {
                                        $X_uuid_cover = $key;
                                        $x=0;
                                        foreach($data_TOTAL_cover_finishing as $key => $value) {
                                            $x_TOTAL_COVER_96 = "TOTAL_FINISHING_96_" . $X_uuid_cover;
                                            if($key == $x_TOTAL_COVER_96){
                                                echo "<th style='text-align:right;font-size:1vw'> " . nominal($value) . " </th>";
                                                $TOTAL_MASUK_96 = $TOTAL_MASUK_96 + ($value);
                                                $x=1;
                                            }
                                        }
                                        if($x == 0)
                                        {
                                            echo "<th style='text-align:right;font-size:1vw'> 0 </th>";
                                        }
                                    }
                                    ?>

                                    <!-- TOTAL COVER / FINISHING -->
                                    <th style="text-align:right;font-size:1vw">
                                        <?php 
                                            echo nominal($TOTAL_MASUK_96); 
                                        ?>
                                    </th>                                    

                                    <!-- TOTAL SELISIH : COVER / FINISHING - PO CETAK  -->
                                    <th style="text-align:right;font-size:1vw">
                                    <?php
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
                                    $x_totalbkjdi96 = 0;
                                    $TOTAL_MASUK_96 = 0;

                                    foreach($cover_gudang_data as $key => $value) {
                                        $x_TOTAL_COVER_96 = $key . "_TOTAL_GUDANG_COVER_64";
                                        $x_TOTAL_RETUR_COVER_96 = $key . "_TOTAL_RETUR_COVER_64";

                                        $X_uuid_cover = $key;
                                        $x=0;
                                        foreach($data_TOTAL_uuid_cover_gudang_halaman as $key => $value) {
                                            $x_TOTAL_COVER_GUDANG_96 = "TOTAL_GUDANG_96_" . $X_uuid_cover;
                                            if($key == $x_TOTAL_COVER_GUDANG_96){
                                                echo "<th style='text-align:right;font-size:1vw'> " . $value . " </th>";
                                                $x=1;
                                                $TOTAL_MASUK_96 = $TOTAL_MASUK_96 + ($value) ;
                                            }
                                        }
                                        if($x == 0)
                                        {
                                            echo "<th style='text-align:right;font-size:1vw'> 0 </th>";
                                        }
                                    }
                                    ?>


                                    <!-- TOTAL BUKU JADI 96 -->
                                    <th style="text-align:right;font-size:1vw"><?php echo nominal($TOTAL_MASUK_96); ?></th>

                                    


                                    <!-- TOTAL PER COVER BDP 96 -->
                                    <?php
                                    
                                    $TOTAL_FINISHING_BDP_96 = 0;
                                    foreach($data_BDP_cover as $key => $value) {

                                        

                                        $x_TOTAL_FINISHING_COVER_96 = $key . "_TOTAL_FINISHING_COVER_96";
                                        $x_TOTAL_GUDANG_COVER_96 = $key . "_TOTAL_GUDANG_COVER_96";
                                        
                                        $X_uuid_cover = $key;
                                        foreach($data_TOTAL_cover_finishing as $key => $value) {
                                            $x_TOTAL_COVER_96 = "TOTAL_FINISHING_96_" . $X_uuid_cover;
                                            if($key == $x_TOTAL_COVER_96){
                                                $x_finishing = $value;
                                            }
                                        }

                                        $x_gudang=0;
                                        foreach($data_TOTAL_uuid_cover_gudang_halaman as $key => $value) {
                                            $x_TOTAL_COVER_GUDANG_96 = "TOTAL_GUDANG_96_" . $X_uuid_cover;
                                            if($key == $x_TOTAL_COVER_GUDANG_96){
                                                $x_gudang = $value;
                                            }
                                        }
                                        $TOTAL_FINISHING_BDP_96 = $TOTAL_FINISHING_BDP_96 + ($x_finishing - $x_gudang);                                                                                   
                                    



                                        if(($x_finishing-$x_gudang)>0){
                                            echo "<th style='text-align:right;font-size:1vw'> " . nominal($x_finishing-$x_gudang)  . " </th>";
                                        }else{
                                            echo "<th style='text-align:right;font-size:1vw;color:red;'> " . nominal($x_finishing-$x_gudang)  . " </th>";
                                        }
                                    
                                        // echo "<th style='text-align:right;font-size:1vw'> BDP_" . $x_finishing . " _ " . $x_gudang . " </th>";
                                    
                                    }

                                    
                                    ?>


                                    <!-- TOTAL FINISHING BDP 96 -->
                                    <th style="text-align:right;font-size:1vw">
                                        <?php 
                                            if($TOTAL_FINISHING_BDP_96 > 0){
                                                echo nominal($TOTAL_FINISHING_BDP_96);
                                            }else{
                                                echo "<strong style='color:red;'>". nominal($TOTAL_FINISHING_BDP_96) ."</strong>";
                                            }

                                        ?>
                                    </th>

                                    <!-- KETERANGAN -->
                                    <th style="text-align:center;font-size:1vw"></th>

                                </tr>



                            </tfoot>

                            </tbody>
                        </table>

                        <!-- END OF TABLE CONTENT -->

                    </div>

                    <!-- /.card-body -->
                </div>

            </div>

        </div>
    </section>
</div>

<!-- NEW TABEL -->

<!-- CSS -->

<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
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
            "scrollY": 600,
            "scrollX": true
        });
    });
</script>
 -->



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

