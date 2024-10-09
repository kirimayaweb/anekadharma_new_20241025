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
                                    REKAP STOCK = <?php echo "<strong>";
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
                                                                site_url('tbl_stok_barang_detail/excel_stock_rekap/' . $tingkat_selected),
                                                                '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Ms Excel, REKAP STOCK :' .
                                                                    $tingkat_selected . ' Tahun : ' . $_SESSION['thn_selected'] . ' Semester :' . $_SESSION['semester_selected'],
                                                                'class="btn btn-success btn-sm"'
                                                            ); ?></div>
                        <div class="col-2" align="left"><?php
                                                        echo anchor(site_url('tbl_stok_barang_detail/get_data_stock/' . $tingkat_selected), '<i class="fa fa-file-word-o" aria-hidden="true"></i> REKAP CETAK', 'class="btn btn-block btn-danger btn-sm"');
                                                        ?>
                        </div>
                        <div class="col-2" align="right"><?php echo anchor(site_url('Trans_cetakinput/create_setting'), '<i class="fa fa-file-excel-o" aria-hidden="true"></i> PO Cetak', 'class="btn btn-success btn-sm"'); ?></div>
                        <div class="col-2" align="left"><?php echo anchor(site_url('Trans_cetakinput/create_setting'), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Finishing', 'class="btn btn-primary btn-sm"'); ?></div>
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
                                    //STOCK MASUK BARU PER COVER DI GUDANG
                                    $urutlama = 0;
                                    foreach ($cover_gudang_data as $key => $value) {

                                        $this->db->where('uuid_cover', $key);
                                        $get_sys_cover = $this->db->get('sys_cover')->row_array();
                                        
                                        if($get_sys_cover['keterangan'] <> "buku_lama" ){
                                            echo "<th style='text-align:center;font-size:1vw'> STOCK MASUK : <br/>" . $value . "<br/>[BARU] </th>";
                                        }
                                                                            
                                    }
                                    ?>

                                    <!-- <th style="text-align:center;font-size:1vw">RETUR <br /> [BARU] </th> -->

                                    <?php 
                                    foreach ($cover_jual_retur_data as $key => $value) {

                                        $this->db->where('uuid_cover', $key);
                                        $get_sys_cover = $this->db->get('sys_cover')->row_array();
                                        
                                        if($get_sys_cover['keterangan'] <> "buku_lama" ){
                                            echo "<th style='text-align:center;font-size:1vw'> RETUR : <br/>" . $value . "<br/>[BARU] </th>";
                                        }
                                                                            
                                    }
                                    ?>
                                    
                                    
                                    <th style="text-align:center;font-size:1vw">TOTAL STOCK MASUK <br /> [BARU] </th>

                                    <?php  
                                    // foreach ($tbl_gudang_buku_lama as $tbl_gudang_buku_lama_list) {
                                        ?>
                                        <!-- <th style="text-align:center;font-size:1vw"> STOCK MASUK : <br/> <?php //echo $tbl_gudang_buku_lama_list->nama_cover ?> <br /> [LAMA] </th> -->

                                    <?php
                                    // }

                                    //STOCK MASUK LAMA PER COVER DI GUDANG
                                    $urutlama = 0;
                                    foreach ($cover_gudang_data as $key => $value) {

                                        $this->db->where('uuid_cover', $key);
                                        $get_sys_cover = $this->db->get('sys_cover')->row_array();
                                        
                                        if($get_sys_cover['keterangan'] == "buku_lama" ){
                                            echo "<th style='text-align:center;font-size:1vw'> STOCK MASUK : <br/>" . $value . "<br/>[LAMA] </th>";
                                        }
                                                                            
                                    }
                                    ?>

                                    <!-- <th style="text-align:center;font-size:1vw">RETUR <br /> [LAMA] </th> -->

                                    <?php 
                                    foreach ($cover_jual_retur_data as $key => $value) {

                                        $this->db->where('uuid_cover', $key);
                                        $get_sys_cover = $this->db->get('sys_cover')->row_array();
                                        
                                        if($get_sys_cover['keterangan'] == "buku_lama" ){
                                            echo "<th style='text-align:center;font-size:1vw'> RETUR : <br/>" . $value . "<br/>[LAMA] </th>";
                                        }
                                                                            
                                    }
                                    ?>



                                    <th style="text-align:center;font-size:1vw">TOTAL STOCK MASUK <br /> [LAMA] </th>

                                    <?php
                                    //STOCK KELUAR BARU
                                    $urutlama = 0;
                                    foreach ($cover_jual as $key=>$value) {

                                        
                                       
                                        $this->db->where('uuid_cover', $key);
                                        $get_sys_cover = $this->db->get('sys_cover')->row_array();
                                        
                                        if($get_sys_cover['keterangan'] <> "buku_lama" ){
                                            echo "<th style='text-align:center;font-size:1vw'> STOCK KELUAR : <br/>" . $value . "<br/>[BARU] </th>";
                                        }

                                    }
                                    ?>

                                    <th style="text-align:center;font-size:1vw">TOTAL STOCK KELUAR <br /> [BARU] </th>


                                    <?php
                                    //STOCK KELUAR LAMA
                                    $urutlama = 0;
                                    foreach ($cover_jual as $key=>$value) {
                                                                               
                                        $this->db->where('uuid_cover', $key);
                                        $get_sys_cover = $this->db->get('sys_cover')->row_array();
                                        
                                        if($get_sys_cover['keterangan'] == "buku_lama" ){
                                            echo "<th style='text-align:center;font-size:1vw'> STOCK KELUAR : <br/>" . $value . "<br/>[LAMA] </th>";
                                        }

                                    }
                                    ?>


                                    <th style="text-align:center;font-size:1vw">TOTAL STOCK KELUAR <br /> [LAMA] </th>

                                    <!-- <th style="text-align:center;font-size:1vw">SISA STOCK <br /> [BARU] <br/>  -->
                                    <?php
                                    $X = $_SESSION['thn_selected']+1;
                                    echo "<th style='text-align:right;font-size:1vw'> SISA STOCK <br /> [BARU] <br/> " .
                                                anchor(site_url('Tbl_stok_barang_detail/get_data_stock_rekap/'.$tingkat_selected.'/sisa_stock_baru'), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Pindah ke tahun = '.$X , 'class="btn btn-primary btn-sm"')  . "</th>";
                                    ?>
                                                <!-- </th> -->


                                    <!-- <th style="text-align:center;font-size:1vw">SISA STOCK <br /> [LAMA] </th> -->
                                    <?php
                                    $X = $_SESSION['thn_selected']+1;
                                    echo "<th style='text-align:right;font-size:1vw'> SISA STOCK <br /> [LAMA] <br/> " .
                                                anchor(site_url('#'), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Pindah ke tahun = '.$X , 'class="btn btn-primary btn-sm"')  . "</th>";
                                                ?>

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

                                foreach ($tbl_stok_barang_detail_data as $tbl_stok_barang_detail) {
                                ?>
                                    <tr>
                                        <td style="text-align:center;font-size:1vw" width="10px"><?php echo ++$start ?></td>

                                        <td style="text-align:left;font-size:1vw">
                                            <?php
                                            echo $tbl_stok_barang_detail->mapel_produk_stock;
                                            // echo "<br/>";
                                            // $x="3dbe5ce92d8d11ec810d0242ac1b0003_96_37baf18b265011ecbfeec43772d488d7_GUDANG";
                                            // echo $tbl_stok_barang_detail->$x;

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


                                        //DATA STOCK MASUK BARU PER COVER
                                        $total_masuk_permapel_baru=0;
                                        foreach ($cover_gudang_data as $key=>$value) {

                                            $this->db->where('uuid_cover', $key);
                                            $get_sys_cover = $this->db->get('sys_cover')->row_array();
                                            
                                            if($get_sys_cover['keterangan'] <> "buku_lama" ){
                                                                                            
                                                $x = $tbl_stok_barang_detail->uuid_produk . "_" . $tbl_stok_barang_detail->jumlah_halaman_stock . "_" . $key . "_GUDANG";

                                                if ($tbl_stok_barang_detail->$x) {

                                                    echo "<td style='text-align:right;font-size:1vw'>"  . nominal($tbl_stok_barang_detail->$x) . "</td>";
                                                    $total_masuk_permapel_baru=$total_masuk_permapel_baru+$tbl_stok_barang_detail->$x;

                                                } else {
                                                    echo "<td style='text-align:right;font-size:1vw'>  " . nominal(0) . "</td>";
                                                }
                                            }

                                        }



                                        ?>

                                        <!-- RETUR BARU -->                                       
                                        <?php
                                        $total_retur_permapel_baru=0;
                                        foreach ($cover_jual_retur_data as $key => $value) {

                                                $this->db->where('uuid_cover', $key);
                                                $get_sys_cover = $this->db->get('sys_cover')->row_array();
                                                
                                                if($get_sys_cover['keterangan'] <> "buku_lama" ){
                                                                                                
                                                    $x = $tbl_stok_barang_detail->uuid_produk . "_" . $tbl_stok_barang_detail->jumlah_halaman_stock . "_" . $key . "_RETUR";

                                                    if ($tbl_stok_barang_detail->$x) {

                                                        echo "<td style='text-align:right;font-size:1vw'>  " . nominal($tbl_stok_barang_detail->$x) . "</td>";
                                                        $total_retur_permapel_baru=$total_retur_permapel_baru+$tbl_stok_barang_detail->$x;

                                                    } else {
                                                        echo "<td style='text-align:right;font-size:1vw'>  " . nominal(0) . "</td>";
                                                    }
                                                }

                                            }

                                        ?>



                                     
                                        <!-- TOTAL STOCK MASUK BARU -->
                                        <td style="text-align:right;font-size:1vw"> <?php
                                                                                    echo "Total masuk baru: <strong>" . nominal($total_masuk_permapel_baru)."</strong>";
                                                                                    echo "<br/>";
                                                                                    echo "Total Retur: <strong>" . nominal($total_retur_permapel_baru) . "</strong>";
                                                                                    echo "<br/>------<br/>";
                                                                                    echo  "<strong>" . nominal($total_masuk_permapel_baru + $total_retur_permapel_baru) . "</strong>";
                                                                                    
                                                                                    $TOTAL_STOCK_MASUK_BARU = $total_masuk_permapel_baru + $total_retur_permapel_baru;
                                                                                    
                                                                                    ?>
                                        </td>
                                        
                                        <?php
                                        //DATA STOCK MASUK LAMA PER COVER
                                        $Data_masuk_lama=0;
                                        foreach ($cover_gudang_data as $key=>$value) {

                                            $this->db->where('uuid_cover', $key);
                                            $get_sys_cover = $this->db->get('sys_cover')->row_array();
                                            
                                            if($get_sys_cover['keterangan'] == "buku_lama" ){
                                                                                            
                                                $x = $tbl_stok_barang_detail->uuid_produk . "_" . $tbl_stok_barang_detail->jumlah_halaman_stock . "_" . $key . "_GUDANG";

                                                if ($tbl_stok_barang_detail->$x) {

                                                    echo "<td style='text-align:right;font-size:1vw'>"  . nominal($tbl_stok_barang_detail->$x) . "</td>";
                                                    $Data_masuk_lama=$Data_masuk_lama+$tbl_stok_barang_detail->$x;

                                                } else {
                                                    echo "<td style='text-align:right;font-size:1vw'>  " . nominal(0) . "</td>";
                                                }
                                            }

                                        }



                                        ?>

                                        <!-- RETUR LAMA -->                                       
                                        <?php
                                        $Data_retur_lama=0;
                                        foreach ($cover_jual_retur_data as $key => $value) {

                                                $this->db->where('uuid_cover', $key);
                                                $get_sys_cover = $this->db->get('sys_cover')->row_array();
                                                
                                                if($get_sys_cover['keterangan'] == "buku_lama" ){
                                                                                                
                                                    $x = $tbl_stok_barang_detail->uuid_produk . "_" . $tbl_stok_barang_detail->jumlah_halaman_stock . "_" . $key . "_RETUR";

                                                    if ($tbl_stok_barang_detail->$x) {

                                                        echo "<td style='text-align:right;font-size:1vw'>  " . nominal($tbl_stok_barang_detail->$x) . "</td>";
                                                        $Data_retur_lama=$Data_retur_lama+$tbl_stok_barang_detail->$x;

                                                    } else {
                                                        echo "<td style='text-align:right;font-size:1vw'>  " . nominal(0) . "</td>";
                                                    }
                                                }

                                            }

                                        ?>




                                        <!-- TOTAL STOCK MASUK LAMA -->
                                        <td style="text-align:right;font-size:1vw"> <?php
                                                                                    echo "Total masuk lama: <strong>" . nominal($Data_masuk_lama)."</strong>";
                                                                                    echo "<br/>";
                                                                                    echo "Total Retur: <strong>" . nominal($Data_retur_lama) . "</strong>";
                                                                                    echo "<br/>------<br/>";
                                                                                    echo  "<strong>" . nominal($Data_masuk_lama + $Data_retur_lama) . "</strong>";
                                                                                    
                                                                                    $TOTAL_STOCK_MASUK_LAMA = $Data_masuk_lama + $Data_retur_lama;
                                                                                    
                                                                                    ?>
                                        </td>


                                        <!-- STOCK KELUAR PER COVER -->
                                        <?php
                                        $x_totalStockKeluar_JUAL_LAMA = 0;
                                        $x_totalStockKeluar_JUAL_BARU = 0;
                                        $x_totalStockKeluar = 0;

                                        $urutlama = 0;
                                        $X_TOTAL_STOCK_KELUAR_64 = 0;
                                        $X_TOTAL_STOCK_KELUAR_96 = 0;
                                        $Data_penjualan_baru=0;
                                        foreach ($cover_jual as $key => $value) {

                                                $this->db->where('uuid_cover', $key);
                                                $get_sys_cover = $this->db->get('sys_cover')->row_array();
                                                
                                                if($get_sys_cover['keterangan'] <> "buku_lama" ){
                                                                                                
                                                    $x_PENJUALAN_KREDIT = $tbl_stok_barang_detail->uuid_produk . "_" . $tbl_stok_barang_detail->jumlah_halaman_stock . "_" . $key . "_PENJUALAN_KREDIT";
                                                    $x_PENJUALAN_TUNAI = $tbl_stok_barang_detail->uuid_produk . "_" . $tbl_stok_barang_detail->jumlah_halaman_stock . "_" . $key . "_PENJUALAN_TUNAI";

                                                    $x=$tbl_stok_barang_detail->$x_PENJUALAN_KREDIT + $tbl_stok_barang_detail->$x_PENJUALAN_TUNAI;
                                                    if ($x) {

                                                        echo "<td style='text-align:right;font-size:1vw'>  " . nominal($x) . "</td>";
                                                        $Data_penjualan_baru=$Data_penjualan_baru + $x;

                                                    } else {
                                                        echo "<td style='text-align:right;font-size:1vw'>  " . nominal(0) . "</td>";
                                                    }
                                                }

                                            }

                                        ?>

                                        <!-- TOTAL STOCK KELUAR BARU -->
                                        <td style="text-align:right;font-size:1vw"> <?php
                                                                                    echo "<strong>" . nominal($Data_penjualan_baru) . "</strong>" ;
                                                                                    $TOTAL_STOCK_KELUAR_BARU = $Data_penjualan_baru;
                                                                                    ?>
                                        </td>



                                         <!-- STOCK KELUAR PER COVER LAMA-->
                                         <?php
                                        $x_totalStockKeluar_JUAL_LAMA = 0;
                                        $x_totalStockKeluar_JUAL_BARU = 0;
                                        $x_totalStockKeluar = 0;

                                        $urutlama = 0;
                                        $X_TOTAL_STOCK_KELUAR_64 = 0;
                                        $X_TOTAL_STOCK_KELUAR_96 = 0;
                                        
                                        $Data_penjualan_lama=0;
                                        foreach ($cover_jual as $key => $value) {

                                                $this->db->where('uuid_cover', $key);
                                                $get_sys_cover = $this->db->get('sys_cover')->row_array();
                                                
                                                if($get_sys_cover['keterangan'] == "buku_lama" ){
                                                                                                
                                                    $x_PENJUALAN_KREDIT = $tbl_stok_barang_detail->uuid_produk . "_" . $tbl_stok_barang_detail->jumlah_halaman_stock . "_" . $key . "_PENJUALAN_KREDIT";
                                                    $x_PENJUALAN_TUNAI = $tbl_stok_barang_detail->uuid_produk . "_" . $tbl_stok_barang_detail->jumlah_halaman_stock . "_" . $key . "_PENJUALAN_TUNAI";

                                                    $x=$tbl_stok_barang_detail->$x_PENJUALAN_KREDIT + $tbl_stok_barang_detail->$x_PENJUALAN_TUNAI;
                                                    if ($x) {

                                                        echo "<td style='text-align:right;font-size:1vw'>  " . nominal($x) . "</td>";
                                                        $Data_penjualan_lama=$Data_penjualan_lama + $x;

                                                    } else {
                                                        echo "<td style='text-align:right;font-size:1vw'>  " . nominal(0) . "</td>";
                                                    }
                                                }

                                            }

                                
                                        ?>

                                        <!-- TOTAL STOCK KELUAR LAMA -->
                                        <td style="text-align:right;font-size:1vw"> <?php
                                                                                    if ($Data_penjualan_lama) {
                                                                                        echo "<strong>" . nominal($Data_penjualan_lama) . "</strong>";
                                                                                        $TOTAL_STOCK_KELUAR_LAMA = $Data_penjualan_lama;
                                                                                    } else {
                                                                                        echo "<strong>" . nominal(0) . "</strong>";
                                                                                    }
                                                                                    ?>
                                        </td>


                                        <!-- SISA STOCK BARU -->
                                        <td style="text-align:right;font-size:1vw">
                                            <?php

                                            echo  nominal( $TOTAL_STOCK_MASUK_BARU - $TOTAL_STOCK_KELUAR_BARU );
                                            ?>

                                        </td>

                                        <!-- SISA STOCK LAMA -->
                                        <td style="text-align:right;font-size:1vw">
                                            <?php
                                            // echo  nominal((($tbl_stok_barang_detail->$x_GUDANG_LAMA_64 + $tbl_stok_barang_detail->$x_RETUR_LAMA_64) + ($tbl_stok_barang_detail->$x_GUDANG_LAMA_96 + $tbl_stok_barang_detail->$x_RETUR_LAMA_96)) - (($tbl_stok_barang_detail->$x_JUAL_LAMA_64 - $tbl_stok_barang_detail->$x_RETUR_LAMA_64) + ($tbl_stok_barang_detail->$x_JUAL_LAMA_96 - $tbl_stok_barang_detail->$x_RETUR_LAMA_96)));
                                            echo  nominal( $TOTAL_STOCK_MASUK_LAMA - $TOTAL_STOCK_KELUAR_LAMA );

                                            ?>

                                        </td>





                                    </tr>
                                <?php
                                }
                                ?>

                            </tbody>

                            <tfoot>
                                <tr>
                                    <!-- ========================  64  ==================== -->
                                    <th style="text-align:center;font-size:1vw" colspan="2"> TOTAL 64</th>

                                    <!-- <th style="text-align:center;font-size:1vw">MAPEL</th> -->

                                    <?php
                                    $urutlama = 0;
                                    $TOTAL_MASUK_64 = 0;
                                    $TOTAL_RETUR_64 = 0;
                                    
                                    //DATA STOCK MASUK BARU PER COVER
                                    $total_masuk_permapel_baru_64=0;
                                    foreach ($cover_gudang_data as $key=>$value) {

                                        $this->db->where('uuid_cover', $key);
                                        $get_sys_cover = $this->db->get('sys_cover')->row_array();
                                        
                                        if($get_sys_cover['keterangan'] <> "buku_lama" ){
                                                                                        
                                            // $x = $tbl_stok_barang_detail->uuid_produk . "_" . $tbl_stok_barang_detail->jumlah_halaman_stock . "_" . $key . "_GUDANG";

                                            $z = "TOTAL_GUDANG_64_" .  $key;
                                            // $x=;

                                            if ($TOTAL_uuid_cover_gudang_halaman->$z) {

                                                echo "<th style='text-align:right;font-size:1vw'> ". nominal($TOTAL_uuid_cover_gudang_halaman->$z) ."</th>";
                                                $total_masuk_permapel_baru_64=$total_masuk_permapel_baru_64+$TOTAL_uuid_cover_gudang_halaman->$z;

                                            } else {
                                                echo "<th style='text-align:right;font-size:1vw'>  ". nominal(0) ." </th>";
                                            }
                                        }

                                    }
                                    ?>
                                    <!-- RETUR <br /> [BARU] -->
                                    <!-- <th style="text-align:right;font-size:1vw"> <?php //echo nominal($TOTAL_RETUR_BARU_64) ?></th> -->
                                    <?php
                                            $total_retur_permapel_baru_64=0;
                                            foreach ($cover_jual_retur_data as $key => $value) {

                                                    $this->db->where('uuid_cover', $key);
                                                    $get_sys_cover = $this->db->get('sys_cover')->row_array();
                                                    
                                                    if($get_sys_cover['keterangan'] <> "buku_lama" ){
                                                                                                    
                                                        //$x = $tbl_stok_barang_detail->uuid_produk . "_" . $tbl_stok_barang_detail->jumlah_halaman_stock . "_" . $key . "_RETUR";
                                                        $z = "TOTAL_RETUR_64_" .  $key;

                                                        if ($TOTAL_uuid_cover_retur_halaman->$z) {

                                                            echo "<th style='text-align:right;font-size:1vw'>  ". nominal($TOTAL_uuid_cover_retur_halaman->$z) ." </th>";
                                                            $total_retur_permapel_baru_64=$total_retur_permapel_baru_64+$TOTAL_uuid_cover_retur_halaman->$z;

                                                        } else {
                                                            echo "<th style='text-align:right;font-size:1vw'>  ". nominal(0) ." </th>";
                                                        }
                                                    }

                                                }

                                            ?>


                                    <!-- RETUR [LAMA] -->
                                    <!-- <th style="text-align:right;font-size:1vw"> <?php //echo nominal($TOTAL_RETUR_LAMA_64) ?></th> -->
                                    
                                    <!-- TOTAL STOCK MASUK <br /> [BARU] -->
                                    <th style="text-align:right;font-size:1vw"> 
                                        <?php 
                                            echo nominal($total_masuk_permapel_baru_64 + $total_retur_permapel_baru_64); 
                                            $TOTAL_STOCK_MASUK_BARU_64=$total_masuk_permapel_baru_64 + $total_retur_permapel_baru_64;
                                        ?> 
                                    </th>

                                    <?php
                                    //DATA STOCK MASUK LAMA PER COVER
                                    $total_masuk_permapel_lama_64=0;
                                    foreach ($cover_gudang_data as $key=>$value) {

                                        $this->db->where('uuid_cover', $key);
                                        $get_sys_cover = $this->db->get('sys_cover')->row_array();
                                        
                                        if($get_sys_cover['keterangan'] == "buku_lama" ){
                                                                                        
                                            // $x = $tbl_stok_barang_detail->uuid_produk . "_" . $tbl_stok_barang_detail->jumlah_halaman_stock . "_" . $key . "_GUDANG";

                                            $z = "TOTAL_GUDANG_64_" .  $key;
                                            // $x=;

                                            if ($TOTAL_uuid_cover_gudang_halaman->$z) {

                                                echo "<th style='text-align:right;font-size:1vw'> ". nominal($TOTAL_uuid_cover_gudang_halaman->$z) ."</th>";
                                                $total_masuk_permapel_lama_64=$total_masuk_permapel_lama_64+$TOTAL_uuid_cover_gudang_halaman->$z;

                                            } else {
                                                echo "<th style='text-align:right;font-size:1vw'>  ". nominal(0) ." </th>";
                                            }
                                        }

                                    }
                                    ?>
                                    <!-- RETUR <br /> [LAMA] -->
                                    
                                    <?php
                                            $total_retur_permapel_lama_64=0;
                                            foreach ($cover_jual_retur_data as $key => $value) {

                                                    $this->db->where('uuid_cover', $key);
                                                    $get_sys_cover = $this->db->get('sys_cover')->row_array();
                                                    
                                                    if($get_sys_cover['keterangan'] == "buku_lama" ){
                                                                                                    
                                                        //$x = $tbl_stok_barang_detail->uuid_produk . "_" . $tbl_stok_barang_detail->jumlah_halaman_stock . "_" . $key . "_RETUR";
                                                        $z = "TOTAL_RETUR_64_" .  $key;

                                                        if ($TOTAL_uuid_cover_retur_halaman->$z) {

                                                            echo "<th style='text-align:right;font-size:1vw'>  ". nominal($TOTAL_uuid_cover_retur_halaman->$z) ." </th>";
                                                            $total_retur_permapel_lama_64=$total_retur_permapel_lama_64+$TOTAL_uuid_cover_retur_halaman->$z;

                                                        } else {
                                                            echo "<th style='text-align:right;font-size:1vw'>  ". nominal(0) ." </th>";
                                                        }
                                                    }

                                                }

                                            ?>


                                    <!-- TOTAL STOCK MASUK <br /> [LAMA] -->
                                    <th style="text-align:right;font-size:1vw"> 
                                        <?php  
                                            echo nominal($total_masuk_permapel_lama_64 + $total_retur_permapel_lama_64); 
                                            $TOTAL_STOCK_MASUK_LAMA_64=$total_masuk_permapel_lama_64 + $total_retur_permapel_lama_64;
                                        ?> 
                                    </th>

                                   
                                
                                        <!-- STOCK KELUAR PER COVER -->
                                        <?php
                                            $x_totalStockKeluar_JUAL_LAMA = 0;
                                            $x_totalStockKeluar_JUAL_BARU = 0;
                                            $x_totalStockKeluar = 0;

                                            $urutlama = 0;
                                            $X_TOTAL_STOCK_KELUAR_64 = 0;
                                            $X_TOTAL_STOCK_KELUAR_96 = 0;
                                            $Data_penjualan_baru_64=0;
                                            foreach ($cover_jual as $key => $value) {

                                                    $this->db->where('uuid_cover', $key);
                                                    $get_sys_cover = $this->db->get('sys_cover')->row_array();
                                                    
                                                    if($get_sys_cover['keterangan'] <> "buku_lama" ){
                                                                                                    
                                                        // $x_PENJUALAN_KREDIT = $tbl_stok_barang_detail->uuid_produk . "_" . $tbl_stok_barang_detail->jumlah_halaman_stock . "_" . $key . "_PENJUALAN_KREDIT";
                                                        // $x_PENJUALAN_TUNAI = $tbl_stok_barang_detail->uuid_produk . "_" . $tbl_stok_barang_detail->jumlah_halaman_stock . "_" . $key . "_PENJUALAN_TUNAI";

                                                        // $x=$tbl_stok_barang_detail->$x_PENJUALAN_KREDIT + $tbl_stok_barang_detail->$x_PENJUALAN_TUNAI;

                                                        $W = "TOTAL_PENJUALAN_KREDIT_64_" .  $key;
                                                        $Z = "TOTAL_PENJUALAN_TUNAI_64_" .  $key;
                                                       
                                                        $x=$TOTAL_uuid_cover_penjualan_kredit_halaman->$W + $TOTAL_uuid_cover_penjualan_tunai_halaman->$Z;

                                                        if ($x) {

                                                            echo "<td style='text-align:right;font-size:1vw'>  " . nominal($x) . "</td>";
                                                            $Data_penjualan_baru_64=$Data_penjualan_baru_64 + $x;

                                                        } else {
                                                            echo "<td style='text-align:right;font-size:1vw'>  " . nominal(0) . "</td>";
                                                        }
                                                    }

                                                }

                                        ?>

                                    <!-- TOTAL STOCK KELUAR <br /> [BARU] -->
                                    <th style="text-align:right;font-size:1vw"> 
                                        <?php 
                                            echo nominal($Data_penjualan_baru_64); 
                                        ?> 
                                    </th>


                                    <!-- STOCK KELUAR PER COVER LAMA-->
                                    <?php
                                            $x_totalStockKeluar_JUAL_LAMA = 0;
                                            $x_totalStockKeluar_JUAL_BARU = 0;
                                            $x_totalStockKeluar = 0;

                                            $urutlama = 0;
                                            $X_TOTAL_STOCK_KELUAR_64 = 0;
                                            $X_TOTAL_STOCK_KELUAR_96 = 0;
                                            
                                            $Data_penjualan_lama_64=0;
                                            foreach ($cover_jual as $key => $value) {

                                                    $this->db->where('uuid_cover', $key);
                                                    $get_sys_cover = $this->db->get('sys_cover')->row_array();
                                                    
                                                    if($get_sys_cover['keterangan'] == "buku_lama" ){
                                                                                                    
                                                        // $x_PENJUALAN_KREDIT = $tbl_stok_barang_detail->uuid_produk . "_" . $tbl_stok_barang_detail->jumlah_halaman_stock . "_" . $key . "_PENJUALAN_KREDIT";
                                                        // $x_PENJUALAN_TUNAI = $tbl_stok_barang_detail->uuid_produk . "_" . $tbl_stok_barang_detail->jumlah_halaman_stock . "_" . $key . "_PENJUALAN_TUNAI";

                                                        // $x=$tbl_stok_barang_detail->$x_PENJUALAN_KREDIT + $tbl_stok_barang_detail->$x_PENJUALAN_TUNAI;
                                                        
                                                        $W = "TOTAL_PENJUALAN_KREDIT_64_" .  $key;
                                                        $Z = "TOTAL_PENJUALAN_TUNAI_64_" .  $key;
                                                       
                                                        $Y=$TOTAL_uuid_cover_penjualan_kredit_halaman->$W + $TOTAL_uuid_cover_penjualan_tunai_halaman->$Z;

                                                        if ($Y) {

                                                            echo "<td style='text-align:right;font-size:1vw'>  " . nominal($Y) . "</td>";
                                                            $Data_penjualan_lama_64=$Data_penjualan_lama_64 + $Y;

                                                        } else {
                                                            echo "<td style='text-align:right;font-size:1vw'>  " . nominal(0) . "</td>";
                                                        }
                                                    }

                                                }

                                    
                                    ?>


                                    <!-- TOTAL STOCK KELUAR <br /> [LAMA] -->
                                    <th style="text-align:right;font-size:1vw"> <?php echo nominal($Data_penjualan_lama_64); ?> </th>


                                    <!-- SISA STOCK <br /> [BARU] -->
                                    <th style="text-align:right;font-size:1vw"> <?php echo nominal($TOTAL_STOCK_MASUK_BARU_64 - $Data_penjualan_baru_64 ) ?> </th>
                                    <!-- SISA STOCK <br /> [LAMA] -->
                                    <th style="text-align:right;font-size:1vw"> <?php echo nominal($TOTAL_STOCK_MASUK_LAMA_64 - $Data_penjualan_lama_64) ?> </th>

                                </tr>



                                <!-- =============================== 96 =========================== -->

                           


                                <tr>
                                        <!-- ========================  64  ==================== -->
                                        <th style="text-align:center;font-size:1vw" colspan="2"> TOTAL 96</th>

                                        <!-- <th style="text-align:center;font-size:1vw">MAPEL</th> -->

                                        <?php
                                        $urutlama = 0;
                                        $TOTAL_MASUK_96 = 0;
                                        $TOTAL_RETUR_96 = 0;
                                        
                                        //DATA STOCK MASUK BARU PER COVER
                                        $total_masuk_permapel_baru_96=0;
                                        foreach ($cover_gudang_data as $key=>$value) {

                                            $this->db->where('uuid_cover', $key);
                                            $get_sys_cover = $this->db->get('sys_cover')->row_array();
                                            
                                            if($get_sys_cover['keterangan'] <> "buku_lama" ){
                                                                                            
                                                // $x = $tbl_stok_barang_detail->uuid_produk . "_" . $tbl_stok_barang_detail->jumlah_halaman_stock . "_" . $key . "_GUDANG";

                                                $z = "TOTAL_GUDANG_96_" .  $key;
                                                // $x=;

                                                if ($TOTAL_uuid_cover_gudang_halaman->$z) {

                                                    echo "<th style='text-align:right;font-size:1vw'> ". nominal($TOTAL_uuid_cover_gudang_halaman->$z) ."</th>";
                                                    $total_masuk_permapel_baru_96=$total_masuk_permapel_baru_96+$TOTAL_uuid_cover_gudang_halaman->$z;

                                                } else {
                                                    echo "<th style='text-align:right;font-size:1vw'>  ". nominal(0) ." </th>";
                                                }
                                            }

                                        }
                                        ?>
                                        <!-- RETUR <br /> [BARU] -->
                                        <!-- <th style="text-align:right;font-size:1vw"> <?php //echo nominal($TOTAL_RETUR_BARU_64) ?></th> -->
                                        <?php
                                                $total_retur_permapel_baru_96=0;
                                                foreach ($cover_jual_retur_data as $key => $value) {

                                                        $this->db->where('uuid_cover', $key);
                                                        $get_sys_cover = $this->db->get('sys_cover')->row_array();
                                                        
                                                        if($get_sys_cover['keterangan'] <> "buku_lama" ){
                                                                                                        
                                                            //$x = $tbl_stok_barang_detail->uuid_produk . "_" . $tbl_stok_barang_detail->jumlah_halaman_stock . "_" . $key . "_RETUR";
                                                            $z = "TOTAL_RETUR_96_" .  $key;

                                                            if ($TOTAL_uuid_cover_retur_halaman->$z) {

                                                                echo "<th style='text-align:right;font-size:1vw'>  ". nominal($TOTAL_uuid_cover_retur_halaman->$z) ." </th>";
                                                                $total_retur_permapel_baru_96=$total_retur_permapel_baru_96+$TOTAL_uuid_cover_retur_halaman->$z;

                                                            } else {
                                                                echo "<th style='text-align:right;font-size:1vw'>  ". nominal(0) ." </th>";
                                                            }
                                                        }

                                                    }

                                                ?>


                                        <!-- RETUR [LAMA] -->
                                        <!-- <th style="text-align:right;font-size:1vw"> <?php //echo nominal($TOTAL_RETUR_LAMA_64) ?></th> -->
                                        
                                        <!-- TOTAL STOCK MASUK <br /> [BARU] -->
                                        <th style="text-align:right;font-size:1vw"> 
                                            <?php 
                                                echo nominal($total_masuk_permapel_baru_96 + $total_retur_permapel_baru_96); 
                                                $TOTAL_STOCK_MASUK_BARU_96=$total_masuk_permapel_baru_96 + $total_retur_permapel_baru_96;
                                            ?> 
                                        </th>

                                        <?php
                                        //DATA STOCK MASUK LAMA PER COVER
                                        $total_masuk_permapel_lama_96=0;
                                        foreach ($cover_gudang_data as $key=>$value) {

                                            $this->db->where('uuid_cover', $key);
                                            $get_sys_cover = $this->db->get('sys_cover')->row_array();
                                            
                                            if($get_sys_cover['keterangan'] == "buku_lama" ){
                                                                                            
                                                // $x = $tbl_stok_barang_detail->uuid_produk . "_" . $tbl_stok_barang_detail->jumlah_halaman_stock . "_" . $key . "_GUDANG";

                                                $z = "TOTAL_GUDANG_96_" .  $key;
                                                // $x=;

                                                if ($TOTAL_uuid_cover_gudang_halaman->$z) {

                                                    echo "<th style='text-align:right;font-size:1vw'> ". nominal($TOTAL_uuid_cover_gudang_halaman->$z) ."</th>";
                                                    $total_masuk_permapel_lama_96=$total_masuk_permapel_lama_96+$TOTAL_uuid_cover_gudang_halaman->$z;

                                                } else {
                                                    echo "<th style='text-align:right;font-size:1vw'>  ". nominal(0) ." </th>";
                                                }
                                            }

                                        }
                                        ?>
                                        <!-- RETUR <br /> [LAMA] -->
                                        
                                        <?php
                                                $total_retur_permapel_lama_96=0;
                                                foreach ($cover_jual_retur_data as $key => $value) {

                                                        $this->db->where('uuid_cover', $key);
                                                        $get_sys_cover = $this->db->get('sys_cover')->row_array();
                                                        
                                                        if($get_sys_cover['keterangan'] == "buku_lama" ){
                                                                                                        
                                                            //$x = $tbl_stok_barang_detail->uuid_produk . "_" . $tbl_stok_barang_detail->jumlah_halaman_stock . "_" . $key . "_RETUR";
                                                            $z = "TOTAL_RETUR_96_" .  $key;

                                                            if ($TOTAL_uuid_cover_retur_halaman->$z) {

                                                                echo "<th style='text-align:right;font-size:1vw'>  ". nominal($TOTAL_uuid_cover_retur_halaman->$z) ." </th>";
                                                                $total_retur_permapel_lama_96=$total_retur_permapel_lama_96+$TOTAL_uuid_cover_retur_halaman->$z;

                                                            } else {
                                                                echo "<th style='text-align:right;font-size:1vw'>  ". nominal(0) ." </th>";
                                                            }
                                                        }

                                                    }

                                                ?>


                                        <!-- TOTAL STOCK MASUK <br /> [LAMA] -->
                                        <th style="text-align:right;font-size:1vw"> 
                                            <?php  
                                                echo nominal($total_masuk_permapel_lama_96 + $total_retur_permapel_lama_96); 
                                                $TOTAL_STOCK_MASUK_LAMA_96=$total_masuk_permapel_lama_96 + $total_retur_permapel_lama_96;
                                            ?> 
                                        </th>

                                    
                                    
                                            <!-- STOCK KELUAR PER COVER -->
                                            <?php
                                                $x_totalStockKeluar_JUAL_LAMA = 0;
                                                $x_totalStockKeluar_JUAL_BARU = 0;
                                                $x_totalStockKeluar = 0;

                                                $urutlama = 0;
                                                $X_TOTAL_STOCK_KELUAR_96 = 0;
                                                $X_TOTAL_STOCK_KELUAR_96 = 0;
                                                $Data_penjualan_baru_96=0;
                                                foreach ($cover_jual as $key => $value) {

                                                        $this->db->where('uuid_cover', $key);
                                                        $get_sys_cover = $this->db->get('sys_cover')->row_array();
                                                        
                                                        if($get_sys_cover['keterangan'] <> "buku_lama" ){
                                                                                                        
                                                            // $x_PENJUALAN_KREDIT = $tbl_stok_barang_detail->uuid_produk . "_" . $tbl_stok_barang_detail->jumlah_halaman_stock . "_" . $key . "_PENJUALAN_KREDIT";
                                                            // $x_PENJUALAN_TUNAI = $tbl_stok_barang_detail->uuid_produk . "_" . $tbl_stok_barang_detail->jumlah_halaman_stock . "_" . $key . "_PENJUALAN_TUNAI";

                                                            // $x=$tbl_stok_barang_detail->$x_PENJUALAN_KREDIT + $tbl_stok_barang_detail->$x_PENJUALAN_TUNAI;

                                                            $W = "TOTAL_PENJUALAN_KREDIT_96_" .  $key;
                                                            $Z = "TOTAL_PENJUALAN_TUNAI_96_" .  $key;
                                                        
                                                            $x=$TOTAL_uuid_cover_penjualan_kredit_halaman->$W + $TOTAL_uuid_cover_penjualan_tunai_halaman->$Z;

                                                            if ($x) {

                                                                echo "<td style='text-align:right;font-size:1vw'>  " . nominal($x) . "</td>";
                                                                $Data_penjualan_baru_96=$Data_penjualan_baru_96 + $x;

                                                            } else {
                                                                echo "<td style='text-align:right;font-size:1vw'>  " . nominal(0) . "</td>";
                                                            }
                                                        }

                                                    }

                                            ?>

                                        <!-- TOTAL STOCK KELUAR <br /> [BARU] -->
                                        <th style="text-align:right;font-size:1vw"> 
                                            <?php 
                                                echo nominal($Data_penjualan_baru_96); 
                                            ?> 
                                        </th>


                                        <!-- STOCK KELUAR PER COVER LAMA-->
                                        <?php
                                                $x_totalStockKeluar_JUAL_LAMA = 0;
                                                $x_totalStockKeluar_JUAL_BARU = 0;
                                                $x_totalStockKeluar = 0;

                                                $urutlama = 0;
                                                $X_TOTAL_STOCK_KELUAR_96 = 0;
                                                $X_TOTAL_STOCK_KELUAR_96 = 0;
                                                
                                                $Data_penjualan_lama_96=0;
                                                foreach ($cover_jual as $key => $value) {

                                                        $this->db->where('uuid_cover', $key);
                                                        $get_sys_cover = $this->db->get('sys_cover')->row_array();
                                                        
                                                        if($get_sys_cover['keterangan'] == "buku_lama" ){
                                                                                                        
                                                            // $x_PENJUALAN_KREDIT = $tbl_stok_barang_detail->uuid_produk . "_" . $tbl_stok_barang_detail->jumlah_halaman_stock . "_" . $key . "_PENJUALAN_KREDIT";
                                                            // $x_PENJUALAN_TUNAI = $tbl_stok_barang_detail->uuid_produk . "_" . $tbl_stok_barang_detail->jumlah_halaman_stock . "_" . $key . "_PENJUALAN_TUNAI";

                                                            // $x=$tbl_stok_barang_detail->$x_PENJUALAN_KREDIT + $tbl_stok_barang_detail->$x_PENJUALAN_TUNAI;
                                                            
                                                            $W = "TOTAL_PENJUALAN_KREDIT_96_" .  $key;
                                                            $Z = "TOTAL_PENJUALAN_TUNAI_96_" .  $key;
                                                        
                                                            $Y=$TOTAL_uuid_cover_penjualan_kredit_halaman->$W + $TOTAL_uuid_cover_penjualan_tunai_halaman->$Z;

                                                            if ($Y) {

                                                                echo "<td style='text-align:right;font-size:1vw'>  " . nominal($Y) . "</td>";
                                                                $Data_penjualan_lama_96=$Data_penjualan_lama_96 + $Y;

                                                            } else {
                                                                echo "<td style='text-align:right;font-size:1vw'>  " . nominal(0) . "</td>";
                                                            }
                                                        }

                                                    }

                                        
                                        ?>


                                        <!-- TOTAL STOCK KELUAR <br /> [LAMA] -->
                                        <th style="text-align:right;font-size:1vw"> <?php echo nominal($Data_penjualan_lama_96); ?> </th>


                                        <!-- SISA STOCK <br /> [BARU] -->
                                        <th style="text-align:right;font-size:1vw"> <?php echo nominal($TOTAL_STOCK_MASUK_BARU_96 - $Data_penjualan_baru_96 ) ?> </th>
                                        <!-- SISA STOCK <br /> [LAMA] -->
                                        <th style="text-align:right;font-size:1vw"> <?php echo nominal($TOTAL_STOCK_MASUK_LAMA_96 - $Data_penjualan_lama_96) ?> </th>

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

