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
                            <?php
                            if (!empty($_SESSION['jenis_data_selected'])) {
                                if ($_SESSION['jenis_data_selected'] == "BARU") {
                                    $action = site_url('Tbl_sales/riwayat_per_sales_baru/');
                                    $action_kirim = $action . $uuid_sales_selected;
                                } else {
                                    $action = site_url('Tbl_sales/riwayat_per_sales_lama/');
                                    $action_kirim = $action . $uuid_sales_selected;
                                }
                            } else {
                                $action = site_url('Tbl_sales/riwayat_per_sales/');
                                $action_kirim = $action . $uuid_sales_selected;
                            }


                            $action_jenis = $action_data . $uuid_sales_selected;
                            ?>


                            <div class="col-12 card-title">
                                <div class="row">
                            
                                    <div class="col-sm-3" align="left">
                                        RIWAYAT TRANSAKSI : <br/>
                                        <?php
                                            echo "<strong>";
                                            if (is_null($data_tingkat)) {
                                                echo "ALL [ ";
                                            } else {
                                                echo $data_tingkat;
                                            }
                                                // echo "<br/>";
                                                echo "  [" . $_SESSION['thn_selected'] . " / " . $_SESSION['semester_selected'] . " ]";

                                                if (!empty($_SESSION['jenis_data_selected'])) {
                                                    echo " " . $_SESSION['jenis_data_selected'];

                                                }

                                                echo "</strong>";
                                        ?>
                                    </div>
                                
                                    <div class="col-sm-9"  align="left">
                                        <?php 
                                            $action_kode_sales = site_url('tbl_sales/riwayat_per_sales_new/' ); 
                                        ?>
                                        
                                        <form action="<?php echo $action_kode_sales; ?>" method="post">
                                                                
                                            <div class="col-sm-5 card-title">
                                                <select name="uuid_sales" id="uuid_sales" class="form-control select2" style="width: 100%; height: 40px;">
                    
                                                    <option value="<?php echo $uuid_sales_selected ?>"><?php if ($uuid_sales_selected) { echo $nama_sales_selected; } else { echo "Pilih Sales / Customer"; } ?></option> <?php $sql = "select * from tbl_sales order by  nama_sales ";
                                                                        foreach ($this->db->query($sql)->result() as $m) {
                                                                            echo "<option value='$m->uuid_sales' ";
                                                                            echo "> " . strtoupper($m->nama_sales) . "</option>";
                                                                        }
                                                                        ?>
                                                </select>                                                                                                                    
                                            </div>

                                            <div class="col-sm-2 card-title">

                                                <select name="level_sekolah" id="level_sekolah" class="form-control select2" style="width: 100%; height: 40px;">
                                                    <?php
                                                        if (!empty($_SESSION['level_sekolah_selected'])) {
                                                        ?>
                                                            <option value="<?php echo $_SESSION['level_sekolah_selected']; ?>"><?php echo $_SESSION['level_sekolah_selected']; ?></option>
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <option value="">Pilih Tingkat</option>
                                                        <?php
                                                        }

                                                        $sql = "select tingkat from  tbl_produk_mapel_referensi group by tingkat order by  tingkat asc";
                                                        foreach ($this->db->query($sql)->result() as $m) {
                                                            echo "<option value='$m->tingkat' ";echo ">  " . strtoupper($m->tingkat) . "</option>";
                                                        }
                                                        ?>

                                                </select>

                                            </div>

                                            <div class="col-sm-2 card-title">
                                                
                                                <select name="jenis_data" id="jenis_data" class="form-control select2" style="width: 100%; height: 40px;">
                                                    <?php
                                                    if (!empty($_SESSION['jenis_data_selected'])) {
                                                    ?>
                                                        <option value="<?php echo $_SESSION['jenis_data_selected']; ?>"><?php 
                                                            if($_SESSION['jenis_data_selected'] == "buku_lama"){
                                                                echo "LAMA";
                                                            }else{
                                                                echo strtoupper($_SESSION['jenis_data_selected']);
                                                            }
                                                        ?></option>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <option value="">Pilih Data</option>
                                                    <?php
                                                    }
                                                    ?>
                                                    <option value="baru">BARU</option>
                                                    <option value="buku_lama">LAMA</option>
                                                </select>

                                            </div>

                                        

                                            <div class="col-sm-2 card-title">
                                                <button type="submit" class="btn btn-danger"><i class="fa fa-floppy-o"></i> <?php echo "CARI DATA" ?></button>
                                            </div>
                                            

                                        </form>   

                                    </div>                                                                            

                                                                   
                                </div>
                                
                            </div>

                        </div>

                    </div>
                    <br />


                    <!-- PINDAHAN COMBO -->
                    
                    <div class="row">

                 
                    


                    </div>

                    <!-- END OF PINDAHAN COMBO -->


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

                        <!-- TABLE CONTENT -->

                        <!-- <table id="example" class="display nowrap" style="width:100%"> -->
                        <!-- <table id="example9" class="display nowrap" style="width:100%"> -->
                        <table id="exampleFreeze" class="display nowrap" style="width:100%">
                            <thead>

                                <tr>
                                    <th style="font-size:0.7vw" width="10px">No</th>

                                    <th style="font-size:0.7vw" width="110px">MAPEL</th>

                                    <!-- RIWAYAT PEMESANAN -->
                                    <?php
                                    // if (!empty($RIWAYAT_PEMESANAN)) {
                                    //     foreach ($RIWAYAT_PEMESANAN as $RIWAYAT_PEMESANAN_list) {

                                    //         $this->db->where('uuid_cover', $RIWAYAT_PEMESANAN_list->uuid_cover);
                                    //         $get_sys_cover = $this->db->get('sys_cover')->row_array();
                                            
                                    //         if($jenis_data_selected_X == "BARU" AND $get_sys_cover['keterangan'] <> "buku_lama" )
                                    //         {

                                    //             $DATE_DATA = date('Y-m-d', strtotime('0 day', strtotime($RIWAYAT_PEMESANAN_list->date_input)));
                                                
                                    //             echo "<th style='text-align:right;font-size:0.7vw'> PEMESANAN: " . $get_sys_cover['nama_cover'] . "<br/>" . $DATE_DATA . "<br/> " .
                                    //                 anchor(site_url('Trans_pemesanan/update_pemesanan/' . $uuid_sales_selected . "/" . $RIWAYAT_PEMESANAN_list->uuid_trans_pemesanan . "/" . $RIWAYAT_PEMESANAN_list->date_input), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Ubah Data Pesanan', 'class="btn btn-primary btn-sm"')  . "</th>";

                                    //         }elseif($jenis_data_selected_X == "LAMA" AND $get_sys_cover['keterangan'] == "buku_lama" ){

                                    //             $DATE_DATA = date('Y-m-d', strtotime('0 day', strtotime($RIWAYAT_PEMESANAN_list->date_input)));
                                            
                                    //             echo "<th style='text-align:right;font-size:0.7vw'> PEMESANAN: " . $get_sys_cover['nama_cover'] . "<br/>" . $DATE_DATA . "<br/> " .
                                    //                 anchor(site_url('Trans_pemesanan/update_pemesanan/' . $uuid_sales_selected . "/" . $RIWAYAT_PEMESANAN_list->uuid_trans_pemesanan . "/" . $RIWAYAT_PEMESANAN_list->date_input), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Ubah Data Pesanan', 'class="btn btn-primary btn-sm"')  . "</th>";
    
                                    //         }
                                    //     }
                                    // }
                                    ?>

                                    <th style="font-size:0.7vw" align="right">TOTAL PESANAN</th>

                                    <!-- PENJUALAN -->
                                    <?php


                                    // $uuid_sales="731f5119dd3f11ebab780242ac120004"; //iwan sukoharjo
                                    // $tingkat_sklh="SD";
                                    // $status_jenis_buku="buku_lama";
                                    $RIWAYAT_PENJUALAN = $this->Trans_penjualan_detail_model->join_penjualan_sys_cover($uuid_sales_selected,$_SESSION['level_sekolah_selected'],$_SESSION['jenis_data_selected']);

                                        // print_r("RIWAYAT_PENJUALAN");
                                        // print_r("<br/>");
                                        // print_r($RIWAYAT_PENJUALAN);
                                        // die;

                                    if (!empty($RIWAYAT_PENJUALAN)) {
                                        foreach ($RIWAYAT_PENJUALAN as $RIWAYAT_PENJUALAN_list) {
                                            $DATE_DATA = date('Y-m-d H:i:s', strtotime('0 day', strtotime($RIWAYAT_PENJUALAN_list->date_input)));

                                            $this->db->where('uuid_cover', $RIWAYAT_PENJUALAN_list->uuid_cover);
                                            $get_sys_cover = $this->db->get('sys_cover')->row_array();
                                            
                                            if($jenis_data_selected_X == "BARU" AND $get_sys_cover['keterangan'] <> "buku_lama" ){
                                            
                                                echo "<th style='text-align:right;font-size:0.7vw'> PENJUALAN: " . $get_sys_cover['nama_cover']  ."<br/>" . $DATE_DATA . "<br/>"
                                                    . anchor(site_url('trans_penjualan/update_penjualan/' . $uuid_sales_selected . "/" . $RIWAYAT_PENJUALAN_list->uuid_trans_penjualan . "/" . $RIWAYAT_PENJUALAN_list->date_input), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Ubah Data Penjualan', 'class="btn btn-primary btn-sm" target="_blank"')  . "<br/>" . anchor(site_url('trans_penjualan/cetak_pdf_DYNAMIC/' . $RIWAYAT_PENJUALAN_list->uuid_trans_penjualan), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Cetak Nota (Pdf) ', 'class="btn btn-success btn-sm" target="_blank"') . "</th>";
                                                "</th>";
                                            
                                            }elseif($jenis_data_selected_X == "LAMA" AND $get_sys_cover['keterangan'] == "buku_lama" ){

                                                    echo "<th style='text-align:right;font-size:0.7vw'> PENJUALAN: " . $get_sys_cover['nama_cover']  ."<br/>" . $DATE_DATA . "<br/>"
                                                    . anchor(site_url('trans_penjualan/update_penjualan/' . $uuid_sales_selected . "/" . $RIWAYAT_PENJUALAN_list->uuid_trans_penjualan . "/" . $RIWAYAT_PENJUALAN_list->date_input), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Ubah Data Penjualan', 'class="btn btn-primary btn-sm" target="_blank"')  . "<br/>" . anchor(site_url('trans_penjualan/cetak_pdf_DYNAMIC/' . $RIWAYAT_PENJUALAN_list->uuid_trans_penjualan), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Cetak Nota (Pdf) ', 'class="btn btn-success btn-sm" target="_blank"') . "</th>";
                                                "</th>";
                                            
                                            }
                                        }
                                    }
                                    ?>


                                    <!-- PENJUALAN TUNAI-->
                                    <?php
                                    // if (!empty($RIWAYAT_PENJUALAN_TUNAI)) {
                                    //     foreach ($RIWAYAT_PENJUALAN_TUNAI as $RIWAYAT_PENJUALAN_TUNAI_list) {
                                    //         $DATE_DATA = date('Y-m-d H:i', strtotime('0 day', strtotime($RIWAYAT_PENJUALAN_TUNAI_list->date_input)));

                                    //         $this->db->where('uuid_cover', $RIWAYAT_PENJUALAN_TUNAI_list->uuid_cover);
                                    //         $get_sys_cover = $this->db->get('sys_cover')->row_array();
                                            
                                    //         if($jenis_data_selected_X == "BARU" AND $get_sys_cover['keterangan'] <> "buku_lama" ){
                                           
                                    //             echo "<th style='text-align:right;font-size:0.7vw'> PENJUALAN TUNAI: " . $get_sys_cover['nama_cover']  ."<br/>" . $DATE_DATA . "<br/>"
                                    //                 . anchor(site_url('trans_penjualan_tunai/update_penjualan/' . $uuid_sales_selected . "/" . $RIWAYAT_PENJUALAN_TUNAI_list->uuid_trans_penjualan . "/" . $RIWAYAT_PENJUALAN_TUNAI_list->date_input), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Ubah Data Penjualan', 'class="btn btn-primary btn-sm"')  . "<br/>" . anchor(site_url('trans_penjualan_tunai/cetak_pdf_DYNAMIC/' . $RIWAYAT_PENJUALAN_TUNAI_list->uuid_trans_penjualan), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Cetak Nota (Pdf) ', 'class="btn btn-success btn-sm" target="_blank"') . "</th>";
                                    //             "</th>";
                                            
                                    //         }elseif($jenis_data_selected_X == "LAMA" AND $get_sys_cover['keterangan'] == "buku_lama" ){

                                    //                 echo "<th style='text-align:right;font-size:0.7vw'> PENJUALAN TUNAI: " . $get_sys_cover['nama_cover']  ."<br/>" . $DATE_DATA . "<br/>"
                                    //                 . anchor(site_url('trans_penjualan_tunai/update_penjualan/' . $uuid_sales_selected . "/" . $RIWAYAT_PENJUALAN_TUNAI_list->uuid_trans_penjualan . "/" . $RIWAYAT_PENJUALAN_TUNAI_list->date_input), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Ubah Data Penjualan', 'class="btn btn-primary btn-sm"')  . "<br/>" . anchor(site_url('trans_penjualan_tunai/cetak_pdf_DYNAMIC/' . $RIWAYAT_PENJUALAN_TUNAI_list->uuid_trans_penjualan), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Cetak Nota (Pdf) ', 'class="btn btn-success btn-sm" target="_blank"') . "</th>";
                                    //             "</th>";
                                            
                                    //         }
                                    //     }
                                    // }
                                    ?>

                                    <th style="font-size:0.7vw">TOTAL <br> PENJUALAN TUNAI </th>


                                    <th style="font-size:0.7vw"><strong>TOTAL PENJUALAN</strong></th>
                                    <th style="font-size:0.7vw;color:red">KEKURANGAN <br/> PENGIRIMAN</th>

                                    <!-- RETUR -->
                                    <?php
                                    // if (!empty($RIWAYAT_RETUR)) {
                                    //     foreach ($RIWAYAT_RETUR as $RIWAYAT_RETUR_list) {

                                    //         $this->db->where('uuid_cover', $RIWAYAT_RETUR_list->uuid_cover);
                                    //         $get_sys_cover = $this->db->get('sys_cover')->row_array();
                                            
                                    //         if($jenis_data_selected_X == "BARU" AND $get_sys_cover['keterangan'] <> "buku_lama" )
                                    //         {

                                    //             $DATE_DATA = date('Y-m-d', strtotime('0 day', strtotime($RIWAYAT_RETUR_list->date_input)));
                                    //             echo "<th style='text-align:right;font-size:0.7vw'> RETUR: ". $get_sys_cover['nama_cover'] ."<br/>" . $DATE_DATA  . "<br/>" . anchor(site_url('trans_retur/update_retur_DYNAMIC/' . $uuid_sales_selected . "/" . $RIWAYAT_RETUR_list->uuid_trans_retur), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Ubah Data Retur', 'class="btn btn-primary btn-sm"') . "</th>";

                                    //         }elseif($jenis_data_selected_X == "LAMA" AND $get_sys_cover['keterangan'] == "buku_lama" ){

                                    //             $DATE_DATA = date('Y-m-d', strtotime('0 day', strtotime($RIWAYAT_RETUR_list->date_input)));
                                    //             echo "<th style='text-align:right;font-size:0.7vw'> RETUR: ". $get_sys_cover['nama_cover'] ."<br/>" . $DATE_DATA  . "<br/>" . anchor(site_url('trans_retur/update_retur_DYNAMIC/' . $uuid_sales_selected . "/" . $RIWAYAT_RETUR_list->uuid_trans_retur . "/" . $RIWAYAT_RETUR_list->date_input), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Ubah Data Retur', 'class="btn btn-primary btn-sm"') . "</th>";
                                                
                                    //         }


                                    //     }
                                    // }
                                    ?>
                                    <th style="font-size:0.7vw">TOTAL RETUR</th>




                                    <th style="font-size:0.7vw">TOTAL PENJUALAN BERSIH</th>

                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $start = 0;
                                $total_mapel = 0;
                                $total_PENJUALAN_ALL = 0;


                                $TOTAL_PENJUALAN_64 = 0;
                                $TOTAL_PENJUALAN_96 = 0;

                                $TOTAL_PENJUALAN_TUNAI_64 = 0;
                                $TOTAL_PENJUALAN_TUNAI_96 = 0;

                                $TOTAL_PESANAN_64 = 0;
                                $TOTAL_PESANAN_96 = 0;

                                $TOTAL_RETUR_64 = 0;
                                $TOTAL_RETUR_96 = 0;

                                $TOTAL_PENJUALAN_BERSIH_64 = 0;
                                $TOTAL_PENJUALAN_BERSIH_96 = 0;


                                    // GET DATA MAPEL
                                        // if ($tingkat_selected == "SD" or $tingkat_selected == "PGSD") {
                                        //     $cek_data_produk = $this->Tbl_produk_mapel_referensi_model->get_all_data_produk_mapel_reference_by_tingkat_by_semester($tingkat_selected, $_SESSION['semester_selected']);
                                        // } else {
                                        //     $cek_data_produk = $this->Tbl_produk_mapel_referensi_model->get_all_data_produk_mapel_reference_by_tingkat($tingkat_selected);
                                            
                                        // }
                                        $cek_data_produk = $this->Tbl_produk_mapel_referensi_model->get_all_data_produk_mapel_reference_by_tingkat_tahun_semester($data_tingkat);

                                        // print_r($cek_data_produk);
                                        
                                        $data_array_simpan_PENJUALAN_PER_MAPEL = array();
                                        $data_ALL_per_MAPEL = array();

                                       

                                foreach ($cek_data_produk as $cek_data_produk_list) {
                                ?>
                                    <tr>
                                        <td style="font-size:0.7vw" width="10px"><?php echo ++$start ?></td>
                                        <td style="font-size:0.7vw" width="10px"> <?php echo $cek_data_produk_list->mapel_produk; ?> </td>

                                        <!-- PEMESANAN PER TANGGAL INPUT -->
                                        <?php
                                        $total_PERMAPEL_PESANAN_64 = 0;
                                        $total_PERMAPEL_PESANAN_96 = 0;
                                        // if (!empty($RIWAYAT_PEMESANAN)) {
                                        //     foreach ($RIWAYAT_PEMESANAN as $RIWAYAT_PEMESANAN_list) {

                                        //         $this->db->where('uuid_cover', $RIWAYAT_PEMESANAN_list->uuid_cover);
                                        //         $get_sys_cover = $this->db->get('sys_cover')->row_array();
                                                
                                        //         if($jenis_data_selected_X == "BARU" AND $get_sys_cover['keterangan'] <> "buku_lama" )
                                        //         {

                                        //             $x_pemesanan_64 = $RIWAYAT_PEMESANAN_list->date_input . "_PESANAN_64";
                                        //             $x_pemesanan_96 = $RIWAYAT_PEMESANAN_list->date_input . "_PESANAN_96";
                                        //             if (!empty($data_RIWAYAT_list->$x_pemesanan_64)  or !empty($data_RIWAYAT_list->$x_pemesanan_96)) {
                                        //                 echo "<td style='text-align:right;font-size:0.7vw'>" . nominal($data_RIWAYAT_list->$x_pemesanan_64 + $data_RIWAYAT_list->$x_pemesanan_96) . "</td>";
                                        //                 $total_PERMAPEL_PESANAN_64 = $total_PERMAPEL_PESANAN_64 + ($data_RIWAYAT_list->$x_pemesanan_64);
                                        //                 $total_PERMAPEL_PESANAN_96 = $total_PERMAPEL_PESANAN_96 + ($data_RIWAYAT_list->$x_pemesanan_96);
                                        //             } else {
                                        //                 echo "<td style='text-align:right;font-size:0.7vw'>" . 0 . "</td>";
                                        //             }

                                        //         }elseif($jenis_data_selected_X == "LAMA" AND $get_sys_cover['keterangan'] == "buku_lama" ){

                                        //             $x_pemesanan_64 = $RIWAYAT_PEMESANAN_list->date_input . "_PESANAN_64";
                                        //             $x_pemesanan_96 = $RIWAYAT_PEMESANAN_list->date_input . "_PESANAN_96";
                                        //             if (!empty($data_RIWAYAT_list->$x_pemesanan_64)  or !empty($data_RIWAYAT_list->$x_pemesanan_96)) {
                                        //                 echo "<td style='text-align:right;font-size:0.7vw'>" . nominal($data_RIWAYAT_list->$x_pemesanan_64 + $data_RIWAYAT_list->$x_pemesanan_96) . "</td>";
                                        //                 $total_PERMAPEL_PESANAN_64 = $total_PERMAPEL_PESANAN_64 + ($data_RIWAYAT_list->$x_pemesanan_64);
                                        //                 $total_PERMAPEL_PESANAN_96 = $total_PERMAPEL_PESANAN_96 + ($data_RIWAYAT_list->$x_pemesanan_96);
                                        //             } else {
                                        //                 echo "<td style='text-align:right;font-size:0.7vw'>" . 0 . "</td>";
                                        //             }

                                        //         }



                                        //     }
                                        // }
                                        ?>

                                        <!-- PESANAN -->
                                        <td style="text-align:right;font-size:0.7vw" width="10px"> <?php
                                                                                                    //echo nominal($total_PERMAPEL_PESANAN_64 + $total_PERMAPEL_PESANAN_96);
                                                                                                    //$TOTAL_PESANAN_64 = $TOTAL_PESANAN_64 + $total_PERMAPEL_PESANAN_64;
                                                                                                    // $TOTAL_PESANAN_96 = $TOTAL_PESANAN_96 + $total_PERMAPEL_PESANAN_96;
                                                                                                    ?> </td>

                                        <!-- PENJUALAN PER TANGGAL INPUT -->
                                        <?php
                                        // $RIWAYAT_PENJUALAN = $this->Trans_penjualan_detail_model->join_penjualan_sys_cover($uuid_sales_selected,$_SESSION['level_sekolah_selected'],$_SESSION['jenis_data_selected']);


                                        // foreach ($RIWAYAT_PENJUALAN as $RIWAYAT_PENJUALAN_list) {
                                        //      $ $RIWAYAT_PENJUALAN_list->uuid_trans_penjualan . "_64" = 0;
                                        //      print_r($RIWAYAT_PENJUALAN_list->uuid_trans_penjualan . "_64");
                                        //      print_r("<br/>");

                                        //      $data_PESANAN_64 = $data_pemesanan_DETAIL_by_sales_by_pesanan_by_mapel_ALL->date_input . "_PESANAN_64";
                                        //      $datainsert_jumlah_pesanan_64 = array($data_PESANAN_64 => $data_pemesanan_DETAIL_by_sales_by_pesanan_by_mapel_ALL->jumlah_pesanan,);

                                             
                                        // }

                                        $total_PERMAPEL_PENJUALAN_64 = 0;
                                        $total_PERMAPEL_PENJUALAN_96 = 0;

                                        $data_array_set = "";
                                        $datainsert_jumlah_PENJUALAN_64 = array();
                                        $datainsert_jumlah_PENJUALAN_96 = array();
                                        
                                        if (!empty($RIWAYAT_PENJUALAN)) {
                                            foreach ($RIWAYAT_PENJUALAN as $RIWAYAT_PENJUALAN_list) {

                                                $get_data_exemplar = $this->Trans_penjualan_detail_model->get_penjualan_detail_by_uuid_penjualan_and_uuid_produk($RIWAYAT_PENJUALAN_list->uuid_trans_penjualan, $cek_data_produk_list->uuid_produk);

                                                // print_r("<br/>");
                                                // print_r("------------------------------");
                                                // print_r("<br/>");
                                                // print_r($get_data_exemplar);
                                                // print_r("<br/>");

                                                if($get_data_exemplar->exemplar_pesanan > 0){

                                                        // echo "isi";
                                                


                                                    if ($cek_data_produk_list->halaman_produk == 64){

                                                        $data_PENJUALAN_64 = $RIWAYAT_PENJUALAN_list->uuid_trans_penjualan . "_PENJUALAN_64";
                                                        $datainsert_jumlah_PENJUALAN_64 = array($data_PENJUALAN_64 => $get_data_exemplar->exemplar_pesanan,);

                                                        $data_PENJUALAN_96 = $RIWAYAT_PENJUALAN_list->uuid_trans_penjualan . "_PENJUALAN_96";
                                                        $datainsert_jumlah_PENJUALAN_96 = array($data_PENJUALAN_96 => 0,);
        
                                                        // $X64 = $get_data_exemplar->exemplar_pesanan;
                                                        // $X96 = 0;

                                                    }else{
                                                        
                                                        $data_PENJUALAN_64 = $RIWAYAT_PENJUALAN_list->uuid_trans_penjualan . "_PENJUALAN_64";
                                                        $datainsert_jumlah_PENJUALAN_64 = array($data_PENJUALAN_64 => 0,);

                                                        $data_PENJUALAN_96 = $RIWAYAT_PENJUALAN_list->uuid_trans_penjualan . "_PENJUALAN_96";
                                                        $datainsert_jumlah_PENJUALAN_96 = array($data_PENJUALAN_96 => $get_data_exemplar->exemplar_pesanan,);

                                                        // $X64 = 0;
                                                        // $X96 = $get_data_exemplar->exemplar_pesanan;
                                                    }

                                                    echo "<td style='text-align:right;font-size:0.7vw'>" . nominal($get_data_exemplar->exemplar_pesanan) . "</td>";
                                                }else{
                                                    // echo "kosong";
                                                    $data_PENJUALAN_64 = $RIWAYAT_PENJUALAN_list->uuid_trans_penjualan . "_PENJUALAN_64";
                                                    $data_PENJUALAN_96 = $RIWAYAT_PENJUALAN_list->uuid_trans_penjualan . "_PENJUALAN_96";
                                                    $datainsert_jumlah_PENJUALAN_64 = array($data_PENJUALAN_64 => 0,);
                                                    $datainsert_jumlah_PENJUALAN_96 = array($data_PENJUALAN_96 => 0,);
                                                    echo "<td style='text-align:right;font-size:0.7vw'>0</td>";
                                                }
                                                // print_r("<br/>");
                                                // print_r("+++++++++++++++++++++++++++++++++");
                                                // print_r("<br/>");
                                                // print_r("<br/>");
                                                // print_r("<br/>");







                                                if (empty($data_array_set)) {
                                                    $data_array_set = array(
                                                        'mapel_list' => $cek_data_produk_list->mapel_produk,
                                                    );
                                                    $data_array_simpan_PENJUALAN_PER_MAPEL = array_merge($data_array_set, $datainsert_jumlah_PENJUALAN_64, $datainsert_jumlah_PENJUALAN_96);
                                                } else {
                                                    $data_array_simpan_PENJUALAN_PER_MAPEL = array_merge($data_array_simpan_PENJUALAN_PER_MAPEL, $datainsert_jumlah_PENJUALAN_64, $datainsert_jumlah_PENJUALAN_96);
                                                }

                                                
                                            }
                                        } 
                                        
                                        // echo $datainsert_jumlah_PENJUALAN_64;

                               
                                       
                                        ?>



                                        <!-- PENJUALAN TUNAI PER TANGGAL INPUT -->
                                        <?php
                                        // $total_PERMAPEL_PENJUALAN_TUNAI_64 = 0;
                                        // $total_PERMAPEL_PENJUALAN_TUNAI_96 = 0;
                                        // if (!empty($RIWAYAT_PENJUALAN_TUNAI)) {
                                        //     foreach ($RIWAYAT_PENJUALAN_TUNAI as $RIWAYAT_PENJUALAN_TUNAI_list) {


                                        //         $this->db->where('uuid_cover', $RIWAYAT_PENJUALAN_TUNAI_list->uuid_cover);
                                        //         $get_sys_cover = $this->db->get('sys_cover')->row_array();
                                                
                                        //         if($jenis_data_selected_X == "BARU" AND $get_sys_cover['keterangan'] <> "buku_lama" )
                                        //         {

                                        //             $x_penjualan_64 = $RIWAYAT_PENJUALAN_TUNAI_list->date_input . "_PENJUALAN_TUNAI_64";
                                        //             $x_penjualan_96 = $RIWAYAT_PENJUALAN_TUNAI_list->date_input . "_PENJUALAN_TUNAI_96";
                                        //             if (!empty($data_RIWAYAT_list->$x_penjualan_64) or !empty($data_RIWAYAT_list->$x_penjualan_96)) {
                                                        
                                        //                 echo "<td style='text-align:right;font-size:0.7vw'>" . nominal($data_RIWAYAT_list->$x_penjualan_64 + $data_RIWAYAT_list->$x_penjualan_96) . "</td>";
    
                                        //                 $total_PERMAPEL_PENJUALAN_TUNAI_64 = $total_PERMAPEL_PENJUALAN_TUNAI_64 + ($data_RIWAYAT_list->$x_penjualan_64);
    
                                        //                 $total_PERMAPEL_PENJUALAN_TUNAI_96 = $total_PERMAPEL_PENJUALAN_TUNAI_96 + ($data_RIWAYAT_list->$x_penjualan_96);
                                        //             } 
                                        //             else {
                                        //                 echo "<td style='text-align:right;font-size:0.7vw'> 0 </td>";
                                        //             }

                                        //         }elseif($jenis_data_selected_X == "LAMA" AND $get_sys_cover['keterangan'] == "buku_lama" ){
                                                    
                                        //             $x_penjualan_64 = $RIWAYAT_PENJUALAN_TUNAI_list->date_input . "_PENJUALAN_64";
                                        //             $x_penjualan_96 = $RIWAYAT_PENJUALAN_TUNAI_list->date_input . "_PENJUALAN_96";
                                        //             if (!empty($data_RIWAYAT_list->$x_penjualan_64) or !empty($data_RIWAYAT_list->$x_penjualan_96)) {
                                                        
                                        //                 echo "<td style='text-align:right;font-size:0.7vw'> " . nominal($data_RIWAYAT_list->$x_penjualan_64 + $data_RIWAYAT_list->$x_penjualan_96) . "</td>";
    
                                        //                 $total_PERMAPEL_PENJUALAN_TUNAI_64 = $total_PERMAPEL_PENJUALAN_TUNAI_64 + ($data_RIWAYAT_list->$x_penjualan_64);
    
                                        //                 $total_PERMAPEL_PENJUALAN_TUNAI_96 = $total_PERMAPEL_PENJUALAN_TUNAI_96 + ($data_RIWAYAT_list->$x_penjualan_96);
                                        //             } 
                                        //             else {
                                        //                 echo "<td style='text-align:right;font-size:0.7vw'> 0 </td>";
                                        //             }

                                        //         }
                                                

                                                
                                        //     }
                                        // } 
                                       
                                        ?>

                                        <!-- PENJUALAN TUNAI TOTAL -->
                                        <td style="text-align:right;font-size:0.7vw" width="10px"> <?php
                                                                                                    // echo nominal($total_PERMAPEL_PENJUALAN_TUNAI_64 + $total_PERMAPEL_PENJUALAN_TUNAI_96);
                                                                                                    
                                                                                                    // $TOTAL_PENJUALAN_TUNAI_64 = $TOTAL_PENJUALAN_TUNAI_64 + $total_PERMAPEL_PENJUALAN_TUNAI_64;

                                                                                                    // $TOTAL_PENJUALAN_TUNAI_96 = $TOTAL_PENJUALAN_TUNAI_96 + $total_PERMAPEL_PENJUALAN_TUNAI_96;
                                                                                                    ?>
                                        </td>


                                        <!-- PENJUALAN TOTAL -->
                                        <td style="text-align:right;font-size:0.7vw" width="10px"> <?php
                                                                                                    // echo "<strong>" . nominal($total_PERMAPEL_PENJUALAN_64 + $total_PERMAPEL_PENJUALAN_96 + $total_PERMAPEL_PENJUALAN_TUNAI_64 + $total_PERMAPEL_PENJUALAN_TUNAI_96) . "</strong>" ;
                                                                                                    
                                                                                                    // $TOTAL_PENJUALAN_64 = $TOTAL_PENJUALAN_64 + $total_PERMAPEL_PENJUALAN_64;
                                                                                                    // $TOTAL_PENJUALAN_96 = $TOTAL_PENJUALAN_96 + $total_PERMAPEL_PENJUALAN_96;
                                                                                                    ?>
                                        </td>

                                        <!-- KEKURANGAN PENGIRIMAN TOTAL -->
                                        <td style="text-align:right;font-size:0.7vw;color:red" width="10px"> <?php //echo nominal(($total_PERMAPEL_PESANAN_64 + $total_PERMAPEL_PESANAN_96) - ($total_PERMAPEL_PENJUALAN_64 + $total_PERMAPEL_PENJUALAN_96)); ?> </td>

                                        <!-- RETUR PER TANGGAL INPUT-->
                                        <?php
                                        // $total_permapel_RETUR_64 = 0;
                                        // $total_permapel_RETUR_96 = 0;
                                        // if (!empty($RIWAYAT_RETUR)) {
                                        //     foreach ($RIWAYAT_RETUR as $RIWAYAT_RETUR_list) {


                                        //         $this->db->where('uuid_cover', $RIWAYAT_RETUR_list->uuid_cover);
                                        //         $get_sys_cover = $this->db->get('sys_cover')->row_array();
                                                
                                        //         if($jenis_data_selected_X == "BARU" AND $get_sys_cover['keterangan'] <> "buku_lama" )
                                        //         {

                                        //             $x_retur_64 = $RIWAYAT_RETUR_list->date_input . "_RETUR_64";
                                        //             $x_retur_96 = $RIWAYAT_RETUR_list->date_input . "_RETUR_96";
                                        //             if (!empty($data_RIWAYAT_list->$x_retur_64) or !empty($data_RIWAYAT_list->$x_retur_96)) {
                                                        
                                        //                 echo "<th style='text-align:right;font-size:0.7vw'>" . nominal($data_RIWAYAT_list->$x_retur_64 + $data_RIWAYAT_list->$x_retur_96) . "</th>";
                                        //                 $total_permapel_RETUR_64 = $total_permapel_RETUR_64 + ($data_RIWAYAT_list->$x_retur_64);
                                        //                 $total_permapel_RETUR_96 = $total_permapel_RETUR_96 + ($data_RIWAYAT_list->$x_retur_96);
                                        //             } else {
                                        //                 echo "<th style='text-align:right;font-size:0.7vw'>" . 0 . "</th>";
                                        //             }


                                        //         }elseif($jenis_data_selected_X == "LAMA" AND $get_sys_cover['keterangan'] == "buku_lama" ){

                                        //             $x_retur_64 = $RIWAYAT_RETUR_list->date_input . "_RETUR_64";
                                        //             $x_retur_96 = $RIWAYAT_RETUR_list->date_input . "_RETUR_96";
                                        //             if (!empty($data_RIWAYAT_list->$x_retur_64) or !empty($data_RIWAYAT_list->$x_retur_96)) {
                                                        
                                        //                 echo "<th style='text-align:right;font-size:0.7vw'>" . nominal($data_RIWAYAT_list->$x_retur_64 + $data_RIWAYAT_list->$x_retur_96) . "</th>";
                                        //                 $total_permapel_RETUR_64 = $total_permapel_RETUR_64 + ($data_RIWAYAT_list->$x_retur_64);
                                        //                 $total_permapel_RETUR_96 = $total_permapel_RETUR_96 + ($data_RIWAYAT_list->$x_retur_96);
                                        //             } else {
                                        //                 echo "<th style='text-align:right;font-size:0.7vw'>" . 0 . "</th>";
                                        //             }

                                        //         }


                                        //     }
                                        // } else {
                                        //     $total_RETUR = 0;
                                        // }
                                        ?>

                                        <!-- RETUR TOTAL -->
                                        <td style="text-align:right;font-size:0.7vw" width="10px"> <?php
                                                                                                    //echo nominal($total_permapel_RETUR_64 + $total_permapel_RETUR_96);
                                                                                                    // $TOTAL_RETUR_64 = $TOTAL_RETUR_64 + $total_permapel_RETUR_64;
                                                                                                    // $TOTAL_RETUR_96 = $TOTAL_RETUR_96 + $total_permapel_RETUR_96;
                                                                                                    ?> </td>


                                        

                                        <!-- TOTAL PENJUALAN BERSIH -->
                                        <td style="text-align:right;font-size:0.7vw" width="10px"> <?php
                                                                                                    // echo nominal(($total_PERMAPEL_PENJUALAN_64 + $total_PERMAPEL_PENJUALAN_96) - ($total_permapel_RETUR_64 + $total_permapel_RETUR_96) + ($total_PERMAPEL_PENJUALAN_TUNAI_64 + $total_PERMAPEL_PENJUALAN_TUNAI_96));

                                                                                                    // $TOTAL_PENJUALAN_BERSIH_64 = $TOTAL_PENJUALAN_BERSIH_64 + ($total_PERMAPEL_PENJUALAN_64 - $total_permapel_RETUR_64 + $TOTAL_PENJUALAN_TUNAI_64);

                                                                                                    // $TOTAL_PENJUALAN_BERSIH_96 = $TOTAL_PENJUALAN_BERSIH_96 + ($total_PERMAPEL_PENJUALAN_96 - $total_permapel_RETUR_96 + $TOTAL_PENJUALAN_TUNAI_96);
                                                                                                    ?> </td>

                                    </tr>
                                    <?php





                                        if (!empty($data_array_simpan_PENJUALAN_PER_MAPEL)) {
                                            if (!empty($data_ALL_per_MAPEL)) {
                                                $data_array_simpan_PENJUALAN_PER_MAPEL = array($data_array_simpan_PENJUALAN_PER_MAPEL);
                                                $data_ALL_per_MAPEL = array_merge($data_ALL_per_MAPEL, $data_array_simpan_PENJUALAN_PER_MAPEL);
                                            } else {
                                                $data_ALL_per_MAPEL = array($data_array_simpan_PENJUALAN_PER_MAPEL);
                                            }
                                        } else {
                                            $data_ALL_per_MAPEL = array($data_array_simpan_PENJUALAN_PER_MAPEL);
                                        }
        
        
        
                                        // print_r("<br/>");
                                        // print_r("+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++");
                                        // print_r("<br/>");
                                        // print_r("data_ALL_per_MAPEL");
                                        // print_r("<br/>");
                                        // print_r($data_ALL_per_MAPEL);
                                        // print_r("<br/>");
                                        // print_r("====================================================================================");
                                        // print_r("<br/>");
                                   

                                } // LOOP PRODUK
   




                                ?>

                         
                            </tbody>

                                <?php 
                                    // print_r("data_array_simpan_PENJUALAN_PER_MAPEL");
                                    // // print_r("<br/>");
                                    // // print_r($data_array_simpan_PENJUALAN_PER_MAPEL);
                                    // // print_r("<br/>");
                                    // print_r("<br/>");
                                    // print_r("<br/>");
                                    // print_r("data_ALL_per_MAPEL");
                                    // print_r("<br/>");
                                    // print_r($data_ALL_per_MAPEL);
                                    // print_r("<br/>");
                                    // print_r("<br/>");
                                    // print_r("<br/>");
                                    // // $x=json_decode(json_encode($data_ALL_per_MAPEL), FALSE);
                                    // // print_r($x['818efa40392411eebb24507b9d61b5e4_PENJUALAN_96']);
                                    // print_r("<br/>");

       

                                ?>



                            <!-- TFOOT -->

                           
                            <!-- /TFOOT -->


                        </table>

                     


                        <?php
                            print_r($waktu_buka);
                            print_r("<br/>");
                            print_r("TABEL TERBUKA = ");
                            print_r(date("H:i:s"));
                            // print_r("<br/>");
                        ?>

                        <!-- END OF TABLE CONTENT -->

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
            "scrollY": 400,
            "scrollX": true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#example9').DataTable({
            "scrollY": 400,
            "scrollX": true
        });
    });
</script>


