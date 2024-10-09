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
                <div class="card card-warning">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-8">
                                <div class="row">
                                    <div class="col-12"> <?php echo $title; ?> : <strong> <?php echo $nama_sales_selected; //echo $uuid_sales_selected; 
                                                                                            ?> </strong></div>
                                </div>
                            </div>
                            <div class="col-2">

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
                            <div class="col-2">

                            </div>


                        </div>

                    </div>
                    <br />

                    <div class="row">
                        <div class="col-2"><?php
                                            // echo anchor(site_url('Trans_penjualan/sett_input_penjualan/' . $uuid_sales_selected), '<i class="fa fa-wpforms" aria-hidden="true"></i> Tambah Data PENJUALAN', 'class="btn btn-danger btn-sm"');
                                            // echo anchor(site_url('tbl_sales/lap_penjualan/' . $uuid_sales_selected), '<i class="fa fa-pencil-square-o" aria-hidden="true">PENJUALAN</i>', 'class="btn btn-success btn-sm"');
                                            ?></div>
                        <div class="col-2" align="right">
                            <?php
                            // echo anchor(
                            //                                         site_url('tbl_stok_barang_detail/excel_stock/' . $tingkat_selected),
                            //                                         '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Ms Excel, REKAP CETAK :' .
                            //                                             $tingkat_selected . ' Tahun : ' . $_SESSION['thn_selected'] . ' Semester :' . $_SESSION['semester_selected'],
                            //                                         'class="btn btn-success btn-sm"'
                            //                                     ); 
                            ?>
                        </div>
                        <div class="col-2" align="left">
                            <?php
                            // echo anchor(site_url('tbl_stok_barang_detail/get_data_stock_rekap/' . $tingkat_selected), '<i class="fa fa-file-word-o" aria-hidden="true"></i> REKAP STOCK', 'class="btn btn-primary btn-sm"');
                            ?>
                        </div>
                        <div class="col-2" align="right">
                            <?php
                            // echo anchor(site_url('Trans_cetakinput/create_setting'), '<i class="fa fa-file-excel-o" aria-hidden="true"></i> PO Cetak', 'class="btn btn-success btn-sm"'); 
                            ?>
                        </div>
                        <div class="col-2" align="left">
                            <?php
                            // echo anchor(site_url('Trans_cetakinput/create_setting'), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Finishing', 'class="btn btn-primary btn-sm"'); 
                            ?>
                        </div>
                        <div class="col-4"></div>

                    </div>


                    <hr>

                    <!-- LOOPING TABEL MAPEL , MAPEL BERDASARKAN TINGKAT & TAHUN & SEMESTER-->

                    <!-- TAMPILAN INPUT FORM -->


                    <?php

                    // $sqlX = "SELECT sum(exemplar_pesanan) as total FROM `trans_penjualan_detail` WHERE `tingkat_pesanan`='MA' AND `uuid_sales`='a5e00168dd3b11ebab780242ac120004' AND `tahun_pesanan`=2023 AND `semester_pesanan`=1  AND `jumlah_halaman_pesanan`=64 ";

                    // $user = $this->db->query($sqlX)->row_array();

                    // print_r($user['total']);
                    // print_r("<br/>");
                    // // print_r($user['harga_jual']);
                    // // print_r("<br/>");
                    // print_r($_SESSION['thn_selected']);
                    // print_r("<br/>");



                    // print_r($x_tahun_session);



                    ?>


                    <!-- <form action="<?php //echo base_url('index.php/Trans_penjualan/update_harga_from_laporan_dynamic/' . $uuid_sales_selected . '/' . $data_penjualan_list->level_tingkat . '/' . $data_penjualan_list->status . '/' . $data_penjualan_list->title_jumlah_halaman); 
                                        ?>" method="post"> -->
                    <form id="update_data" action="<?php echo base_url('index.php/Trans_penjualan/update_harga_from_laporan_dynamic/' . $uuid_sales_selected); ?>" method="post">

                        <table class='table table-bordered>'>

                            <div class="card-header">
                                <tr>
                                    <th width='10' align="center">No.</th>
                                    <th width='20' align="center">TINGKAT</th>
                                    <th width='15' align="center">JMLH. Halaman</th>
                                    <th width='20' align="center">PENJUALAN</th>
                                    <th width='40' align="center">HARGA PER EKSEMPLAR</th>
                                    <th width='60' align="center">TOTAL HARGA</th>
                                </tr>
                            </div>
                            <?php


                            $uuid_sales_process = $uuid_sales_selected;
                            $tahun_process = $_SESSION['thn_selected'];
                            $semester_process = $_SESSION['semester_selected'];

                            $tahun_selected = $_SESSION['thn_selected'];
                            $tahun_selected_plus_1 = $_SESSION['thn_selected'] + 1;

                            $semester_selected = $_SESSION['semester_selected'];


                            $start = 0;
                            // $jumlah_kelompok=0;
                            $x_tahun_session = $_SESSION['thn_selected'];
                            $x_semester_session = $_SESSION['semester_selected'];

                            $TOTAL_Nominal = 0;
                            $x_pesan_penjualan = "";

                            // DATA KELOMPOK MAPEL
                            // $sql = "select tingkat from tbl_produk_mapel_referensi group by tingkat order by tingkat ASC";
                            $sql = "select * from tbl_produk_mapel_referensi group by tingkat order by urutan_tagihan ASC";

                            foreach ($this->db->query($sql)->result() as $m) {

                                // CEK TINGKAT BERDASARKAN ID_USERS
                                // foreach ($this->Auto_load_model->cek_tingkat_by_user() as $m) {
                                //     // echo "<option value='$m->tingkat' ";echo ">  " . strtoupper($m->tingkat) . "</option>";
                                // }

                                $this->db->where('id_users', $this->session->userdata('sess_iduser'));
                                $this->db->where('tingkat', $m->tingkat);
                                $users = $this->db->get('tbl_tingkat_by_user');

                                if (($users->row()->status_tampil == "TAMPIL") and $m->urutan_tagihan > 0) {

                                    // CEK COVER BUKU LAMA
                                    $buku_lama = "buku_lama";
                                    $this->db->where('tingkat', $m->tingkat);
                                    $this->db->where('keterangan',  $buku_lama);
                                    $get_uuid_buku_lama = $this->db->get('sys_cover');

                                    if ($get_uuid_buku_lama->num_rows() > 0) {
                                        $get_uuid_cover_buku_lama = $get_uuid_buku_lama->row_array()['uuid_cover'];
                                    }


                                    $tingkat_process = $m->tingkat;

                                    // APAKAH ADA DATA PENJUALAN  ?
                                    $SQLpenjualan_SALES_TINGKAT = "SELECT `uuid_sales`,`tahun_pesanan`,`semester_pesanan`,`tingkat_pesanan`,`jumlah_halaman_pesanan`,`uuid_cover_produk`,SUM(`exemplar_pesanan`) as total_jual,`harga_jual_pesanan` FROM `trans_penjualan_detail` WHERE `uuid_sales`='$uuid_sales_process' AND `tahun_pesanan`='$tahun_process' AND `semester_pesanan`=$semester_process AND`tingkat_pesanan`='$tingkat_process' GROUP BY `jumlah_halaman_pesanan`";

                                    $get_SQLpenjualan_SALES_TINGKAT_GROUP_BY_jumlah_halaman_pesanan = $this->db->query($SQLpenjualan_SALES_TINGKAT);

                                    $get_tingkat = $m->tingkat;
                                    // $get_tingkat_jmlh_halaman = $get_data_persales_per_tingkat_list->jumlah_halaman_pesanan;

                                    $get_tingkat_baru = $m->tingkat . " BARU";
                                    $get_tingkat_baru_input_harga = $m->tingkat . "_BARU_HARGA";

                                    // print_r($get_SQLpenjualan_SALES_TINGKAT_GROUP_BY_jumlah_halaman_pesanan->result());
                                    // print_r("<br/>");
                                    // print_r("<br/>");
                                    // print_r("<br/>");


                                    if ($get_SQLpenjualan_SALES_TINGKAT_GROUP_BY_jumlah_halaman_pesanan->num_rows() > 0) {

                                        // BARU berdasarkan cover dengan keterangan <> buku_lama
                                        $query_detail_penjualan_SALES_TINGKAT_BARU = "SELECT `uuid_sales`,`tahun_pesanan`,`semester_pesanan`,`tingkat_pesanan`,`jumlah_halaman_pesanan`,`uuid_cover_produk`,SUM(`exemplar_pesanan`) as total_jual,`harga_jual_pesanan` FROM `trans_penjualan_detail` WHERE `uuid_sales`='$uuid_sales_process' AND `tahun_pesanan`='$tahun_process' AND `semester_pesanan`=$semester_process AND`tingkat_pesanan`='$tingkat_process' AND `uuid_cover_produk` NOT LIKE '$get_uuid_cover_buku_lama' GROUP BY `jumlah_halaman_pesanan`";

                                        $sql_get_detail_penjualan_SALES_TINGKAT_BARU = $this->db->query($query_detail_penjualan_SALES_TINGKAT_BARU);

                                        // Apakah ada data penjualan BARU ?
                                        if ($this->db->query($query_detail_penjualan_SALES_TINGKAT_BARU)->num_rows()) {

                                            foreach ($sql_get_detail_penjualan_SALES_TINGKAT_BARU->result() as $sql_get_detail_penjualan_SALES_TINGKAT_BARU_list) {
                            ?>

                                                <!-- BARU ADA DATA PENJUALAN -->
                                                <tr>
                                                    <td align="center"><?php
                                                                        $get_urutan_kelompok = ++$start;
                                                                        echo $get_urutan_kelompok;  ?></td>
                                                    <td align="left">
                                                        <?php
                                                        echo $m->tingkat . " BARU";
                                                        ?>
                                                    </td>
                                                    <td align="center">
                                                        <?php 
                                                    
                                                        $jmlh_halaman_process=$sql_get_detail_penjualan_SALES_TINGKAT_BARU_list->jumlah_halaman_pesanan;
                                                        echo $jmlh_halaman_process; 
                                                        ?>
                                                    </td>

                                                    <td align="right" style="color:red;">
                                                        <?php

                                                        // BARU // BARU  =========================================   64   ====================================

                                                        $tingkat_BARU = $m->tingkat . " BARU";

                                                        // //PENJUALAN Gabungan + looping DOWNLINE
                                                        // $get_data_penjualan_by_sales_baru = $this->Trans_penjualan_detail_model->total_penjualan_by_uuid_sales_by_tingkat_BARU($uuid_sales_selected, $m->tingkat, $get_data_persales_per_tingkat_list->jumlah_halaman_pesanan);

                                                        // $get_data_penjualan_by_sales_baru_harga_satuan = $this->Trans_penjualan_detail_model->total_penjualan_by_uuid_sales_by_tingkat_BARU_harga_satuan($uuid_sales_selected, $m->tingkat, $get_data_persales_per_tingkat_list->jumlah_halaman_pesanan);

                                                        if (!empty($sql_get_detail_penjualan_SALES_TINGKAT_BARU_list->total_jual) or !is_null($sql_get_detail_penjualan_SALES_TINGKAT_BARU_list->total_jual)) {
                                                            $total_jual_baru = $sql_get_detail_penjualan_SALES_TINGKAT_BARU_list->total_jual;
                                                            $harga_exemplar_baru = $sql_get_detail_penjualan_SALES_TINGKAT_BARU_list->harga_jual_pesanan;
                                                        } else {
                                                            $total_jual_baru = 0;
                                                            $harga_exemplar_baru = 0;
                                                        }

                                                        ?>

                                                        <input type="text" class="form-control uang" name="jmlh_penjualan_<?php echo $get_urutan_kelompok; ?>" id="jmlh_penjualan_<?php echo $get_urutan_kelompok; ?>" placeholder="" value="<?php echo $total_jual_baru ?>" style="font-size:0.9vw;font-weight: bold" disabled />

                                                    </td>

                                                    <!-- //harga baru -->
                                                    <td align="right">
                                                        <?php
                                                        if ($total_jual_baru == 0) {
                                                        ?>
                                                            <input type="text" class="form-control uang" onkeyup="sum();" name="harga_penjualan_<?php echo $get_urutan_kelompok; ?>" id="harga_penjualan_<?php echo $get_urutan_kelompok; ?>" placeholder="" value="" style="font-size:0.9vw;font-weight: bold" />
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <!-- Isi data harga -->
                                                            <!-- id = "harga_penjualan_[Nomor urutan]" : untuk kendali currency di javascript , name:"uuid_sales_tahun_semester_tingkat_BARU" : kirim / post ke controller update -->
                                                            <input type="text" class="form-control uang" onkeyup="sum();" id="harga_penjualan_<?php echo $get_urutan_kelompok; ?>" name="harga_penjualan_<?php echo $uuid_sales_process . "_" . $tahun_process . "_" . $semester_process . "_" . $tingkat_process . "_" . $jmlh_halaman_process  . "_BARU"; ?>" placeholder="" value="<?php echo nominal($harga_exemplar_baru) ?>" style="font-size:0.9vw;font-weight: bold" />
                                                        <?php
                                                        }


                                                        if (($total_jual_baru > 0) and ($harga_exemplar_baru > 0)) {
                                                            $total_harga_jual_baru = $total_jual_baru * $harga_exemplar_baru;
                                                        } else {
                                                            $total_harga_jual_baru = 0;
                                                        }

                                                        // PESAN WA
                                                        $pesan_penjualan_PROCESS_LOOP = $tingkat_BARU . " \r\n ( Hal *" . $sql_get_detail_penjualan_SALES_TINGKAT_BARU_list->jumlah_halaman_pesanan . "*) : \r\n" . nominal($total_jual_baru) . " exp. X Rp. " . nominal($harga_exemplar_baru) . " = Rp. " . nominal($total_jual_baru * $harga_exemplar_baru);

                                                        //  $x_pesan_penjualan = $x_pesan_penjualan . "Hal *" . $get_tingkat_jmlh_halaman . "*" . " \r\n ";

                                                        $x_pesan_penjualan = $x_pesan_penjualan . $pesan_penjualan_PROCESS_LOOP . " \r\n\r\n ";


                                                        $TOTAL_Nominal = $TOTAL_Nominal + $total_harga_jual_baru;
                                                        // print_r($TOTAL_Nominal);
                                                        ?>

                                                    </td>

                                                    <!-- TOTAL PENJUALAN BARU -->
                                                    <td align="right" style="color:red;">
                                                        <input type="text" class="form-control uang" name="total_penjualan_<?php echo $get_urutan_kelompok; ?>" id="total_penjualan_<?php echo $get_urutan_kelompok; ?>" placeholder="" value="<?php echo nominal($total_harga_jual_baru); ?>" style="font-size:0.9vw;font-weight: bold; align:right" />

                                                    </td>

                                                </tr>

                                        <?php
                                            }
                                        }
                                    } else {

                                        ?>

                                        <!-- BARU TIDAK ADA PENJUALAN -->
                                        <tr>
                                            <td align="center"><?php
                                                                $get_urutan_kelompok = ++$start;
                                                                echo $get_urutan_kelompok;  ?></td>
                                            <td align="left">
                                                <?php
                                                echo $m->tingkat . " BARU";
                                                ?>
                                            </td>
                                            <td align="center"><?php echo $sql_get_detail_penjualan_SALES_TINGKAT_LAMA_list->jumlah_halaman_pesanan; ?></td>

                                            <td align="right" style="color:red;">
                                                <?php
                                                $tingkat_LAMA = $m->tingkat . " BARU";
                                                ?>

                                                <input type="text" class="form-control uang" name="jmlh_penjualan_<?php echo $get_urutan_kelompok; ?>" id="jmlh_penjualan_<?php echo $get_urutan_kelompok; ?>" placeholder="" value="" style="font-size:0.9vw;font-weight: bold"  disabled/>

                                            </td>

                                            <!-- //harga lama -->
                                            <td align="right">

                                                <input type="text" class="form-control uang" onkeyup="sum();" name="harga_penjualan_<?php echo $get_urutan_kelompok; ?>" id="harga_penjualan_<?php echo $get_urutan_kelompok; ?>" placeholder="" value="" style="font-size:0.9vw;font-weight: bold"  />

                                                <?php
                                                // print_r($x_pesan_penjualan);
                                                ?>

                                            </td>

                                            <!-- TOTAL PENJUALAN BARU -->
                                            <td align="right" style="color:red;">
                                                <input type="text" class="form-control uang" name="total_penjualan_<?php echo $get_urutan_kelompok; ?>" id="total_penjualan_<?php echo $get_urutan_kelompok; ?>" placeholder="" value="" style="font-size:0.9vw;font-weight: bold; align:right" />

                                            </td>

                                        </tr>

                                    <?php


                                    }


                                    ?>

                                    <!-- --------------------------------------------------------LAMA---------------------------------------- -->
                                    <?php

                                    // LAMA
                                    $query_detail_penjualan_SALES_TINGKAT_LAMA = "SELECT `uuid_sales`,`tahun_pesanan`,`semester_pesanan`,`tingkat_pesanan`,`jumlah_halaman_pesanan`,`uuid_cover_produk`,SUM(`exemplar_pesanan`) as total_jual,`harga_jual_pesanan` FROM `trans_penjualan_detail` WHERE `uuid_sales`='$uuid_sales_process' AND `tahun_pesanan`='$tahun_process' AND `semester_pesanan`=$semester_process AND`tingkat_pesanan`='$tingkat_process' AND `uuid_cover_produk` LIKE '$get_uuid_cover_buku_lama' GROUP BY `jumlah_halaman_pesanan`";

                                    $sql_get_detail_penjualan_SALES_TINGKAT_LAMA = $this->db->query($query_detail_penjualan_SALES_TINGKAT_LAMA);


                                    if ($this->db->query($query_detail_penjualan_SALES_TINGKAT_LAMA)->num_rows() > 0) {



                                        foreach ($sql_get_detail_penjualan_SALES_TINGKAT_LAMA->result() as $sql_get_detail_penjualan_SALES_TINGKAT_LAMA_list) {
                                    ?>
                                            <!-- LAMA ADA PENJUALAN -->
                                            <tr>
                                                <td align="center"><?php
                                                                    $get_urutan_kelompok = ++$start;
                                                                    echo $get_urutan_kelompok;  ?></td>
                                                <td align="left">
                                                    <?php
                                                    echo $m->tingkat . " LAMA";
                                                    ?>
                                                </td>
                                                <td align="center"><?php 
                                                $jmlh_halaman_process=$sql_get_detail_penjualan_SALES_TINGKAT_LAMA_list->jumlah_halaman_pesanan;
                                                echo $sql_get_detail_penjualan_SALES_TINGKAT_LAMA_list->jumlah_halaman_pesanan; ?></td>

                                                <td align="right" style="color:red;">
                                                    <?php

                                                    $tingkat_LAMA = $m->tingkat . " LAMA";

                                                    if (!empty($sql_get_detail_penjualan_SALES_TINGKAT_LAMA_list->total_jual) or !is_null($sql_get_detail_penjualan_SALES_TINGKAT_LAMA_list->total_jual)) {
                                                        $total_jual_lama = $sql_get_detail_penjualan_SALES_TINGKAT_LAMA_list->total_jual;
                                                        $harga_exemplar_lama = $sql_get_detail_penjualan_SALES_TINGKAT_LAMA_list->harga_jual_pesanan;
                                                    } else {
                                                        $total_jual_lama = 0;
                                                        $harga_exemplar_lama = 0;
                                                    }

                                                    ?>

                                                    <input type="text" class="form-control uang" name="jmlh_penjualan_<?php echo $get_urutan_kelompok; ?>" id="jmlh_penjualan_<?php echo $get_urutan_kelompok; ?>" placeholder="" value="<?php echo $total_jual_lama ?>" style="font-size:0.9vw;font-weight: bold" disabled />

                                                </td>

                                                <!-- //harga lama -->
                                                <td align="right">
                                                    <?php
                                                    if ($total_jual_lama == 0) {
                                                    ?>
                                                        <input type="text" class="form-control uang" onkeyup="sum();" name="harga_penjualan_<?php echo $get_urutan_kelompok; ?>" id="harga_penjualan_<?php echo $get_urutan_kelompok; ?>" placeholder="" value="" style="font-size:0.9vw;font-weight: bold" />
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <!-- id = "harga_penjualan_[Nomor urutan]" : untuk kendali currency di javascript , name:"uuid_sales_tahun_semester_tingkat_lama" : kirim / post ke controller update -->
                                                        <input type="text" class="form-control uang" onkeyup="sum();" id="harga_penjualan_<?php echo $get_urutan_kelompok; ?>" name="harga_penjualan_<?php echo $uuid_sales_process . "_" . $tahun_process . "_" . $semester_process . "_" . $tingkat_process .  "_" . $jmlh_halaman_process  . "_LAMA"; ?>" placeholder="" value="<?php echo nominal($harga_exemplar_lama) ?>" style="font-size:0.9vw;font-weight: bold" />
                                                    <?php
                                                    }


                                                    if (($total_jual_lama > 0) and ($harga_exemplar_lama > 0)) {
                                                        $total_harga_jual_lama = $total_jual_lama * $harga_exemplar_lama;
                                                    } else {
                                                        $total_harga_jual_lama = 0;
                                                    }


                                                    // PESAN WA
                                                    $pesan_penjualan_PROCESS_LOOP = $tingkat_LAMA . " \r\n ( Hal *" . $sql_get_detail_penjualan_SALES_TINGKAT_LAMA_list->jumlah_halaman_pesanan . "*) : \r\n" . nominal($total_jual_lama) . " exp. X Rp. " . nominal($harga_exemplar_lama) . " = Rp. " . nominal($total_jual_lama * $harga_exemplar_lama);

                                                    $x_pesan_penjualan = $x_pesan_penjualan . $pesan_penjualan_PROCESS_LOOP . " \r\n\r\n ";

                                                    $TOTAL_Nominal = $TOTAL_Nominal + $total_harga_jual_lama;
                                                    // print_r($TOTAL_Nominal);
                                                    ?>

                                                </td>

                                                <!-- TOTAL PENJUALAN BARU -->
                                                <td align="right" style="color:red;">
                                                    <input type="text" class="form-control uang" name="total_penjualan_<?php echo $get_urutan_kelompok; ?>" id="total_penjualan_<?php echo $get_urutan_kelompok; ?>" placeholder="" value="<?php echo nominal($total_harga_jual_lama); ?>" style="font-size:0.9vw;font-weight: bold; align:right" />

                                                </td>

                                            </tr>

                                        <?php
                                        }
                                    } else {

                                        ?>

                                        <!-- LAMA TIDAK ADA PENJUALAN -->
                                        <tr>
                                            <td align="center"><?php
                                                                $get_urutan_kelompok = ++$start;
                                                                echo $get_urutan_kelompok;  ?></td>
                                            <td align="left">
                                                <?php
                                                echo $m->tingkat . " LAMA";
                                                ?>
                                            </td>
                                            <td align="center"><?php echo $sql_get_detail_penjualan_SALES_TINGKAT_LAMA_list->jumlah_halaman_pesanan; ?></td>

                                            <td align="right" style="color:red;">
                                                <?php
                                                $tingkat_LAMA = $m->tingkat . " LAMA";
                                                ?>

                                                <input type="text" class="form-control uang" name="jmlh_penjualan_<?php echo $get_urutan_kelompok; ?>" id="jmlh_penjualan_<?php echo $get_urutan_kelompok; ?>" placeholder="" value="" style="font-size:0.9vw;font-weight: bold" disabled />

                                            </td>

                                            <!-- //harga lama -->
                                            <td align="right">

                                                <input type="text" class="form-control uang" onkeyup="sum();" name="harga_penjualan_<?php echo $get_urutan_kelompok; ?>" id="harga_penjualan_<?php echo $get_urutan_kelompok; ?>" placeholder="" value="" style="font-size:0.9vw;font-weight: bold"  />

                                                <?php
                                                // print_r($x_pesan_penjualan);
                                                ?>

                                            </td>

                                            <!-- TOTAL PENJUALAN BARU -->
                                            <td align="right" style="color:red;">
                                                <input type="text" class="form-control uang" name="total_penjualan_<?php echo $get_urutan_kelompok; ?>" id="total_penjualan_<?php echo $get_urutan_kelompok; ?>" placeholder="" value="" style="font-size:0.9vw;font-weight: bold; align:right" />

                                            </td>

                                        </tr>


                                    <?php

                                    }
                                    ?>




                            <?php
                                    // }


                                } // end of status = TAMPIL

                            }
                            ?>





                            <tr>
                                <td width='10' align="center">

                                    <input type="hidden" class="form-control uang" name="jumlah_start" id="jumlah_start" placeholder="" value="<?php print_r($start); ?>" style="font-size:0.9vw;font-weight: bold" />

                                </td>
                                <td width='20' align="center"></td>
                                <td width='15' align="center"></td>
                                <td width='20' align="center"></td>
                                <td width='40' align="center">
                                    <button type="submit" class="btn btn-outline-info btn-block btn-flat">
                                        <i class="fa fa-book"></i> <?php echo "SIMPAN"; //echo $button 
                                                                    ?>
                                    </button>
                                </td>
                                <td width='60' align="center"></td>
                            </tr>

                        </table>

                    </form>


                    <hr>




                    <?php
                    $TOTAL_Nominal = $TOTAL_Nominal;
                    // foreach ($this->Trans_penjualan_detail_model->total_tagihan_by_uuid($uuid_sales_selected) as $data_sales_list) {
                    //     $TOTAL_Nominal = $TOTAL_Nominal + $data_sales_list['TotalPerMapel'];
                    // }
                    echo "<br/>";
                    echo "<h4><strong>  TOTAL PENJUALAN : " . nominal($TOTAL_Nominal) . "</strong></h4>";
                    ?>
                    <hr>
                    <div class="row">

                        <!-- DATA RETUR -->
                        <div class="col-6">


                            <div class="card-body">
                                RETUR
                                <table id="returtable" class="table table-bordered table-striped">
                                    <thead>

                                        <tr>
                                            <!-- <th style="text-align:center" width="5vw">No</th> -->
                                            <!-- <th style="text-align:center" width="100px">Action</th> -->
                                            <th style="text-align:center" width="8vw">RETUR</th>
                                            <!-- <th style="text-align:center" width="8vw">TINGKAT</th> -->
                                            <th style="text-align:center" width="8vw">TANGGAL</th>
                                            <th style="text-align:center" width="10vw">Rp.</th>
                                            <!-- <th style="text-align:center">TOTAL HARGA</th> -->
                                        </tr>
                                    </thead>

                                    <tbody>

                                        <?php
                                        $start = 0;

                                        // print_r($data_penjualan);
                                        $x_harga_retur = 0;
                                        $x_detail_retur = "";



                                        $sql = "SELECT 
                                        trans_retur_detail_a.tingkat_pesanan as tingkat_pesanan,
                                        trans_retur_detail_a.date_input as date_retur,
                                        trans_retur_detail_a.uuid_sales as uuid_sales_retur,
                                        sum(trans_retur_detail_a.exemplar_pesanan) as exemplar_retur,
                                        trans_retur_detail_a.harga_jual_pesanan as harga_jual_pesanan
                                
                                        FROM trans_retur_detail trans_retur_detail_a
                                
                                        where trans_retur_detail_a.uuid_sales = '$uuid_sales_selected' AND trans_retur_detail_a.tahun_pesanan = '$tahun_selected' AND trans_retur_detail_a.semester_pesanan = '$semester_selected' 
                                
                                        -- group by trans_retur_detail_a.date_input
                                        group by CAST(trans_retur_detail_a.date_input AS DATE) ,trans_retur_detail_a.harga_jual_pesanan
                                        -- group by trans_retur_detail_a.tingkat_pesanan,trans_retur_detail_a.jumlah_halaman 
                                
                                        order by trans_retur_detail_a.date_input ASC
                                        ";

                                        // return $this->db->query($sql)->result();

                                        // print_r("<br/>");
                                        // print_r("<br/>");
                                        // print_r("<br/>");
                                        // print_r($this->db->query($sql)->result());
                                        // print_r("<br/>");
                                        // print_r("<br/>");
                                        // print_r("<br/>");





                                        // foreach ($data_retur as $key => $value) {
                                        foreach ($this->db->query($sql)->result() as $retur_list) {

                                            $this->db->where('id_users', $this->session->userdata('sess_iduser'));
                                            $this->db->where('tingkat', $retur_list->tingkat_pesanan);
                                            $users = $this->db->get('tbl_tingkat_by_user');

                                            if (($users->row()->status_tampil == "TAMPIL")) {
                                        ?>
                                                <tr>
                                                    <td style="font-size:0.9vw" align="center"><?php echo "RETUR " . ++$start ?></td>
                                                    <!-- <td style="font-size:0.9vw" align="center"><?php //echo $retur_list->tingkat_pesanan; ?></td> -->
                                                    <td style="font-size:0.9vw" align="left">
                                                        <?php
                                                        // $DATE_DATA = date('Y-m-d', strtotime('0 day', strtotime($data_retur_list->date_retur)));
                                                        // echo "<strong>" . $DATE_DATA . "</strong>";
                                                        // echo "<strong>" . $key . "</strong>";
                                                        echo $retur_list->date_retur;
                                                        ?>
                                                    </td>
                                                    
                                                    <td style="font-size:0.9vw" align="right">
                                                        <?php
                                                        // // echo "<strong>" . nominal($data_retur_list->exemplar_retur * $data_retur_list->harga_jual_pesanan) . "</strong>";
                                                        // echo "<strong>" . nominal($value) . "</strong>";

                                                        // // $x_harga_retur = $x_harga_retur + ($data_retur_list->exemplar_retur * $data_retur_list->harga_jual_pesanan);
                                                        // // $x_detail_retur = $x_detail_retur . $start . ". Tgl. " . $DATE_DATA . " = " . nominal($data_retur_list->exemplar_retur * $data_retur_list->harga_jual_pesanan) . " \r\n ";

                                                        echo "<strong>" . nominal($retur_list->harga_jual_pesanan) . "</strong>";


                                                        // $x_harga_retur = $x_harga_retur + ($value);
                                                        // $x_detail_retur = $x_detail_retur . $start . ". Tgl. " . $key . " = " . nominal($value) . " \r\n ";

                                                        $x_harga_retur = $x_harga_retur + $retur_list->harga_jual_pesanan;
                                                        $x_detail_retur = $x_detail_retur . $start . ". Tgl. " . $retur_list->date_retur . " = " . nominal($retur_list->harga_jual_pesanan) . " \r\n ";
                                                        ?>
                                                    </td>





                                                </tr>
                                        <?php
                                            }
                                        }
                                        ?>

                                    </tbody>


                                </table>

                            </div>
                        </div>

                        <!-- TABEL ANGSURAN / PEMBAYARAN -->
                        <div class="col-6">
                            <div class="card-body">
                                ANGSURAN
                                <table id="angsurantable" class="table table-bordered table-striped">
                                    <thead>

                                        <tr>
                                            <th style="text-align:center" width="10vw">Angsuran</th>
                                            <!-- <th style="text-align:center" width="100px">Action</th> -->
                                            <th style="text-align:center" width="10vw">Tanggal</th>
                                            <th style="text-align:center" width="10vw">NOMINAL</th>
                                            <!-- <th style="text-align:center">HARGA</th> -->
                                            <!-- <th style="text-align:center">TOTAL HARGA</th> -->
                                        </tr>
                                    </thead>

                                    <tbody>

                                        <?php
                                        $start = 0;

                                        // print_r($data_penjualan);
                                        $x_pembayaran_total = 0;
                                        $x_detail_angsuran = "";
                                        foreach ($data_pembayaran as $data_pembayaran_list) {
                                            // $get_tingkat = $data_tingkat_LIST->tingkat_produk;
                                        ?>
                                            <tr>
                                                <td style="font-size:0.9vw" align="center"><?php echo "Angsuran " . ++$start ?></td>
                                                <td style="font-size:0.9vw" align="left">
                                                    <?php
                                                    $DATE_DATA_ANGSURAN = date('Y-m-d', strtotime('0 day', strtotime($data_pembayaran_list->date_bayar)));

                                                    echo "<strong>" . $DATE_DATA_ANGSURAN . "</strong>";
                                                    ?>
                                                </td>
                                                <td style="font-size:0.9vw" align="right">
                                                    <?php
                                                    echo nominal($data_pembayaran_list->nominal_bayar);
                                                    $x_pembayaran_total = $x_pembayaran_total + $data_pembayaran_list->nominal_bayar;

                                                    $x_detail_angsuran = $x_detail_angsuran . $start . ". Tgl. " . $DATE_DATA_ANGSURAN . " = " . nominal($data_pembayaran_list->nominal_bayar) . "\r\n";
                                                    ?>
                                                </td>



                                            </tr>
                                        <?php
                                        }
                                        ?>

                                    </tbody>


                                </table>

                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="row">
                        <!-- <div class="card-body"> -->
                        <div class="col-6">
                            <div class="card-body" style="font-size:1vw">
                                Total Retur : <?php echo "<strong>" . nominal($x_harga_retur) . "</strong>"; ?>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card-body" style="font-size:1vw">
                                Total Angsuran : <?php echo "<strong>" . nominal($x_pembayaran_total) . "</strong>"; ?>
                            </div>
                        </div>
                        <!-- </div> -->
                    </div>
                    <hr>
                    <div class="row">
                        <!-- <div class="card-body"> -->
                        <div class="col-12">
                            <div class="card-body" style="font-size:2vw">
                                <?php
                                $sisaTagihan = 0;
                                echo "<strong> Sisa Tagihan : </strong>(" . nominal($TOTAL_Nominal) . " - " . nominal($x_harga_retur) . "-" . nominal($x_pembayaran_total) . ") = <strong>" . nominal(($TOTAL_Nominal - $x_harga_retur) - $x_pembayaran_total) . "</strong>";
                                $sisaTagihan = ($TOTAL_Nominal - $x_harga_retur) - $x_pembayaran_total;
                                ?>
                            </div>
                        </div>
                        <!-- </div> -->
                    </div>
                    <hr>
                    <div class="row" align="center">
                        <!-- <div class="card-body"> -->
                        <div class="col-4">
                            <div class="card-body">
                                <?php
                                //echo anchor(site_url('#'), '<i class="fa fa-wpforms" aria-hidden="true"></i> Cetak', 'class="btn btn-success btn-sm"');
                                ?>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card-body">
                                <?php
                                echo anchor(site_url('Tbl_sales/excel_lap_penjualan/' . $uuid_sales_selected), '<i class="fa fa-wpforms" aria-hidden="true"></i> Cetak Ke Excel', 'class="btn btn-success btn-sm"');
                                ?>
                            </div>
                        </div>
                        <div class="col-4">
                            <?php

                            $tgl_sekarang = date('Y-m-d H:i:s');

                            if ($semester_selected == 1) {
                                $nama_semester = "Ganjil";
                            } else {
                                $nama_semester = "Genap";
                            }

                            //$pesan_wa = "*Laporan pengambilan lks* \r\n *Bpk/Ibu* *" . $nama_sales_selected . "* \r\n Di " . $alamat_sales_selected . " \r\n Semester " . $nama_semester . " " . $tahun_selected ."-". $tahun_selected_plus_1 . " \r\n per " . $tgl_sekarang . " \r\n\r\n\r\n " . $x_pesan_penjualan . "\r\n *Total =* *Rp. " . nominal($TOTAL_Nominal) . "* \r\n\r\n\r\n *Retur =* *" . nominal($x_harga_retur) . "* \r\n " . $x_detail_retur . " \r\n *Total Tagihan=* *Rp. " . nominal($TOTAL_Nominal-$x_harga_retur) ."* \r\n\r\n\r\n *Titipan dana =* *" . nominal($x_pembayaran_total) . "* \r\n " . $x_detail_angsuran . "\r\n\r\n\r\n *Sisa Tagihan =* *" . nominal($sisaTagihan) . "*";
                            $pesan_wa = "*Laporan pengambilan lks* \r\n *Bpk/Ibu* *" . $nama_sales_selected . "* \r\n Semester " . $nama_semester . " " . $tahun_selected . "-" . $tahun_selected_plus_1 . " \r\n per " . $tgl_sekarang . " \r\n\r\n " . $x_pesan_penjualan . "\r\n *Total =* *Rp. " . nominal($TOTAL_Nominal) . "* \r\n\r\n Retur =  \r\n " . $x_detail_retur . " \r\n *Total retur=* *Rp. " . nominal($x_harga_retur) . "* \r\n\r\n *Total Tagihan=* *Rp. " . nominal($TOTAL_Nominal - $x_harga_retur) . "* \r\n\r\n\r\n Titipan dana = \r\n " . $x_detail_angsuran . " \r\n *Total Titipan dana=* *" . nominal($x_pembayaran_total) . "* \r\n\r\n\r\n *Sisa Tagihan =* *" . nominal($sisaTagihan) . "*";

                            // $pesanwa_new = "My title \r\n\r\n My description and link";
                            // $nomorwa = "08157045860";
                            // $curl = curl_init();

                            // curl_setopt_array($curl, array(
                            //     CURLOPT_URL => "https://fonnte.com/api/send_message.php",
                            //     CURLOPT_RETURNTRANSFER => true,
                            //     CURLOPT_ENCODING => "",
                            //     CURLOPT_MAXREDIRS => 10,
                            //     CURLOPT_TIMEOUT => 0,
                            //     CURLOPT_FOLLOWLOCATION => true,
                            //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            //     CURLOPT_CUSTOMREQUEST => "POST",
                            //     CURLOPT_POSTFIELDS => array('phone' => $nomorwa, 'type' => 'text', 'text' => $pesan_wa, 'delay' => '1', 'delay_req' => '1', 'schedule' => '0'),
                            //     CURLOPT_HTTPHEADER => array(
                            //         "Authorization: K1mzoiD4FwEVub8duMKY"
                            //     ),
                            // ));

                            // $response = curl_exec($curl);

                            // curl_close($curl);
                            // echo $response;



                            $halaman_pengirim = "Tbl_sales/kirim_wa_lap_penjualan_dynamic/" . $uuid_sales_selected;
                            ?>


                            <?php echo form_open($halaman_pengirim); ?>
                            <div class="card-body">
                                <div class="form-group has-feedback">
                                    <input type="no_hp" class="form-control" name="no_hp" placeholder="no hp">
                                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                                </div>
                                <!-- echo anchor(site_url('Tbl_sales/kirim_wa_lap_penjualan/'.), '<i class="fa fa-wpforms" aria-hidden="true"></i>Kirim Ke Whatsapp', 'class="btn btn-success btn-sm"'); -->
                                <?php

                                ?>
                                <input type="hidden" name="pesan_text_wa" value="<?php echo $pesan_wa; ?>" />
                                <input type="hidden" name="uuid_sales" value="<?php echo $uuid_sales_selected; ?>" />
                                <div class="row">
                                    <div class="col-xs-4">
                                        <button type="submit" class="btn btn-danger btn-block btn-flat"><i class="fa fa-sign-in" aria-hidden="true"></i> Kirim ke Whatsapp</button>
                                    </div>

                                </div>

                            </div>
                            </form>
                        </div>
                        <!-- </div> -->
                    </div>

                    <hr>
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
        $('#pengirimantable').DataTable({
            "scrollY": 450,
            "scrollX": true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#returtable').DataTable({
            "scrollY": 250,
            "scrollX": true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#angsurantable').DataTable({
            "scrollY": 250,
            "scrollX": true
        });
    });
</script>



<script>
    function sum() {

        // $x=0;
        // alert($x);

        // get jumlah kelompok mapel
        $get_start_jumlah_kelompok = parseInt(document.getElementById('jumlah_start').value.replace(/[^0-9]/g, ''));
        // alert($get_start_jumlah_kelompok);


        let i = 1;
        while (i < $get_start_jumlah_kelompok + 1) {
            $x_harga_penjualan_baru = "harga_penjualan_" + i;
            $x_jmlh_penjualan_baru = "jmlh_penjualan_" + i;
            $x_total_penjualan_baru = "total_penjualan_" + i;

            let get_harga_penjualan_baru = document.getElementById($x_harga_penjualan_baru).value;
            let get_jmlh_penjualan_baru = document.getElementById($x_jmlh_penjualan_baru).value;

            var harga_penjualan_baru_Value = document.getElementById($x_harga_penjualan_baru).value.replace(/[^0-9]/g, '');
            var jmlh_penjualan_baru_Value = document.getElementById($x_jmlh_penjualan_baru).value.replace(/[^0-9]/g, '');
            var total_harga_per_kelompok = parseInt(harga_penjualan_baru_Value) * parseInt(jmlh_penjualan_baru_Value);

            if (!isNaN(total_harga_per_kelompok)) {
                document.getElementById($x_total_penjualan_baru).value = total_harga_per_kelompok;
            }

            // // DATA LAMA
            // $x_harga_penjualan_lama = "harga_penjualan_lama_" + i;
            // $x_jmlh_penjualan_lama = "jmlh_penjualan_lama_" + i;
            // $x_total_penjualan_lama = "total_penjualan_lama_" + i;

            // var harga_penjualan_lama_Value = document.getElementById($x_harga_penjualan_lama).value.replace(/[^0-9]/g, '');
            // var jmlh_penjualan_lama_Value = document.getElementById($x_jmlh_penjualan_lama).value.replace(/[^0-9]/g, '');

            // var total_harga_per_kelompok_LAMA = parseInt(harga_penjualan_lama_Value) * parseInt(jmlh_penjualan_lama_Value) ;
            //         // alert(total_harga_per_kelompok);
            //         // alert("lanjut");


            // if (!isNaN(total_harga_per_kelompok_LAMA)) {
            //         document.getElementById($x_total_penjualan_lama).value = total_harga_per_kelompok_LAMA;
            //     // alert(document.getElementById($x_total_penjualan_baru).value);s
            // }

            i++;

        }


    }
</script>