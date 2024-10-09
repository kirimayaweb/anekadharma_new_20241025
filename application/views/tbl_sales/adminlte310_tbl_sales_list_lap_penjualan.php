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
                            <div class="col-8">
                                <div class="row">
                                    <div class="col-12"> DATA TAGIHAN : <?php echo $nama_sales_selected; ?></div>
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

                    <div class="card-body">
                        <!-- DATA PENJUALAN -->

                        <!-- <table id="pengirimantableX" class="table table-bordered table-striped"> -->
                        <table id="exampleFreeze" class="table table-bordered table-striped">
                            <thead>

                                <tr>
                                    <th style="text-align:center" width="3vw">No</th>
                                    <!-- <th style="text-align:center" width="100px">Action</th> -->
                                    <th style="text-align:center" width="10vw">TINGKAT</th>
                                    <th style="text-align:center" width="7vw">JMLH HAL.</th>
                                    <th style="text-align:center" width="10vw">PENJUALAN</th>
                                    <th style="text-align:center" width="20vw">HARGA PER EKSEMPLAR</th>
                                    <th style="text-align:center" width="12vw">TOTAL HARGA</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php
                                $start = 0;

                                // print_r($data_penjualan);
                                $x_pesan_penjualan = "";
                                foreach ($data_penjualan as $data_penjualan_list) {
                                    // $get_tingkat = $data_tingkat_LIST->tingkat_produk;
                                ?>
                                    <tr>
                                        <td style="font-size:0.9vw" align="center"><?php echo ++$start ?></td>
                                        <td style="font-size:0.9vw" align="left">
                                            <?php
                                            echo "<strong>" . $data_penjualan_list->title_nama_tingkat . "</strong>";
                                            if ($data_penjualan_list->penjualan >= 1) {
                                                $x_pesan_penjualan = $x_pesan_penjualan . "*" . $data_penjualan_list->title_nama_tingkat . "*" . " \r\n ";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            echo "<strong>" . $data_penjualan_list->title_jumlah_halaman . "</strong>";
                                            
                                            if ($data_penjualan_list->penjualan >= 1) {
                                                $x_pesan_penjualan = $x_pesan_penjualan . "Hal *" . $data_penjualan_list->title_jumlah_halaman . "*" . " \r\n ";
                                            }
                                            ?>
                                        </td>
                                        <td style="font-size:0.9vw" align="right">

                                            <?php
                                            echo nominal($data_penjualan_list->penjualan) ." exemplar" ;
                                            if ($data_penjualan_list->penjualan >= 1) {
                                                $x_penjualan = $data_penjualan_list->penjualan;
                                                // $x_pesan_penjualan = $x_pesan_penjualan . nominal($x_penjualan) . " Exemplar X \r\n ";
                                                $x_pesan_penjualan = $x_pesan_penjualan . nominal($x_penjualan) . " exp. X ";
                                            }
                                            ?>
                                        </td>
                                        <td style="font-size:0.9vw" align="right">

                                            <form id="kirim_pesan" action="<?php echo base_url('index.php/Trans_penjualan/update_harga_from_laporan/' . $uuid_sales_selected . '/' . $data_penjualan_list->level_tingkat . '/' . $data_penjualan_list->status . '/' . $data_penjualan_list->title_jumlah_halaman); ?>" method="post">
                                                <?php
                                                $x = $data_penjualan_list->harga_satuan;
                                                $x = nominal($x);
                                                ?>
                                                <div class="row">
                                                    <div class="col-8">
                                                        
                                                        <input type="number" max="9999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="input_total_harga_kelompok_mapel" id="input_total_harga_kelompok_mapel" placeholder="" value="<?php echo $data_penjualan_list->harga_satuan; ?>" style="font-size:0.8vw;font-weight: bold;align='right';" align="right" ; />
                                                    </div>
                                                    <div class="col-4" align="left">
                                                        <input type="submit" value="Update Harga" />
                                                    </div>
                                                </div>





                                            </form>

                                            <?php
                                            $total_per_mapel=0;
                                            if ($data_penjualan_list->penjualan >= 1) {
                                                $x_harga = $data_penjualan_list->harga;
                                                //$x_pesan_penjualan = $x_pesan_penjualan . nominal($x_harga) . " \r\n\r\n ";
                                                // $total_per_mapel=;
                                            }
                                            $total_per_mapel = $data_penjualan_list->penjualan * $data_penjualan_list->harga_satuan;
                                            if($data_penjualan_list->harga_satuan){
                                                $x_pesan_penjualan = $x_pesan_penjualan . " Rp." . nominal($data_penjualan_list->harga_satuan) . " = Rp." . nominal($total_per_mapel) . " \r\n\r\n ";
                                            }
                                            
                                            ?>
                                        </td>
                                        <td style="font-size:0.9vw" align="right">
                                            <?php
                                            echo nominal($data_penjualan_list->penjualan * $data_penjualan_list->harga_satuan);

                                            ?>
                                        </td>


                                    </tr>
                                <?php



                                }
                                ?>

                            </tbody>


                        </table>
                        <?php
                        // $getdatatagihan = $this->Trans_penjualan_detail_model->total_tagihan_by_uuid($tbl_sales->uuid_sales);

                        $Total_nominal = 0;
                        foreach ($this->Trans_penjualan_detail_model->total_tagihan_by_uuid($uuid_sales_selected) as $data_sales_list) {
                            $Total_nominal = $Total_nominal + $data_sales_list['TotalPerMapel'];
                        }
                        echo "<br/>";
                        echo "<h4><strong>  TOTAL PENJUALAN : " . nominal($Total_nominal) . "</strong></h4>";
                        ?>

                    </div>
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
                                        // foreach ($data_retur as $data_retur_list) {
                                        foreach ($data_retur as $key => $value) {
                                            // $get_tingkat = $data_tingkat_LIST->tingkat_produk;
                                        ?>
                                            <tr>
                                                <td style="font-size:0.9vw" align="center"><?php echo "RETUR " . ++$start ?></td>
                                                <td style="font-size:0.9vw" align="left">
                                                    <?php
                                                    // $DATE_DATA = date('Y-m-d', strtotime('0 day', strtotime($data_retur_list->date_retur)));
                                                    // echo "<strong>" . $DATE_DATA . "</strong>";
                                                    echo "<strong>" . $key . "</strong>";
                                                    ?>
                                                </td>
                                                <td style="font-size:0.9vw" align="right">
                                                    <?php
                                                    // echo "<strong>" . nominal($data_retur_list->exemplar_retur * $data_retur_list->harga_jual_pesanan) . "</strong>";
                                                    echo "<strong>" . nominal($value) . "</strong>";
                                                    
                                                    // $x_harga_retur = $x_harga_retur + ($data_retur_list->exemplar_retur * $data_retur_list->harga_jual_pesanan);
                                                    // $x_detail_retur = $x_detail_retur . $start . ". Tgl. " . $DATE_DATA . " = " . nominal($data_retur_list->exemplar_retur * $data_retur_list->harga_jual_pesanan) . " \r\n ";
                                                    $x_harga_retur = $x_harga_retur + ($value);
                                                    $x_detail_retur = $x_detail_retur . $start . ". Tgl. " . $key . " = " . nominal($value) . " \r\n ";
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

                                                    $x_detail_angsuran = $x_detail_angsuran . $start . ". Tgl. " . $DATE_DATA_ANGSURAN . " = " . nominal($data_pembayaran_list->nominal_bayar) . "\r\n" ;
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
                            <div class="card-body" style="font-size:35px">
                                <?php
                                $sisaTagihan = 0;
                                echo "<strong> Sisa Tagihan : </strong>(" . nominal($Total_nominal) . " - " . nominal($x_harga_retur) . "-" . nominal($x_pembayaran_total) . ") = <strong>" . nominal(($Total_nominal - $x_harga_retur) - $x_pembayaran_total) . "</strong>";
                                $sisaTagihan = ($Total_nominal - $x_harga_retur) - $x_pembayaran_total;
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
                            $tahun_selected = $_SESSION['thn_selected'];
                            $tahun_selected_plus_1 = $_SESSION['thn_selected']+1;

                            $semester_selected = $_SESSION['semester_selected'];
                            $tgl_sekarang = date('Y-m-d H:i:s');

                            if($semester_selected==1){
                                $nama_semester="Ganjil";
                            }else{
                                $nama_semester="Genap";
                            }

                            //$pesan_wa = "*Laporan pengambilan lks* \r\n *Bpk/Ibu* *" . $nama_sales_selected . "* \r\n Di " . $alamat_sales_selected . " \r\n Semester " . $nama_semester . " " . $tahun_selected ."-". $tahun_selected_plus_1 . " \r\n per " . $tgl_sekarang . " \r\n\r\n\r\n " . $x_pesan_penjualan . "\r\n *Total =* *Rp. " . nominal($Total_nominal) . "* \r\n\r\n\r\n *Retur =* *" . nominal($x_harga_retur) . "* \r\n " . $x_detail_retur . " \r\n *Total Tagihan=* *Rp. " . nominal($Total_nominal-$x_harga_retur) ."* \r\n\r\n\r\n *Titipan dana =* *" . nominal($x_pembayaran_total) . "* \r\n " . $x_detail_angsuran . "\r\n\r\n\r\n *Sisa Tagihan =* *" . nominal($sisaTagihan) . "*";
                            $pesan_wa = "*Laporan pengambilan lks* \r\n *Bpk/Ibu* *" . $nama_sales_selected . "* \r\n Di " . $alamat_sales_selected . " \r\n Semester " . $nama_semester . " " . $tahun_selected ."-". $tahun_selected_plus_1 . " \r\n per " . $tgl_sekarang . " \r\n\r\n " . $x_pesan_penjualan . "\r\n *Total =* *Rp. " . nominal($Total_nominal) . "* \r\n\r\n Retur =  \r\n " . $x_detail_retur . " \r\n *Total Tagihan=* *Rp. " . nominal($Total_nominal-$x_harga_retur) ."* \r\n\r\n\r\n Titipan dana = \r\n " . $x_detail_angsuran . " \r\n *Total Titipan dana=* *" . nominal($x_pembayaran_total) ."* \r\n\r\n\r\n *Sisa Tagihan =* *" . nominal($sisaTagihan) . "*";

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



                            $halaman_pengirim = "Tbl_sales/kirim_wa_lap_penjualan/" . $uuid_sales_selected;
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


