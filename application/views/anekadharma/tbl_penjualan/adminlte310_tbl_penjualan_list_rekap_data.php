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
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="row">
                                    <div class="col-12" text-align="center"> <strong>REKAP DATA <?php //echo $field_rekap; 
                                                                                                ?></strong></div>
                                </div>


                            </div>


                            <div class="col-6">
                                <?php echo anchor(site_url('tbl_penjualan/RekapPenjualanPerBarang'), 'Rekap Penjualan Per Barang', 'class="btn btn-success"'); ?>
                            </div>


                            <div class="col-2">
                                <?php //echo anchor(site_url('tbl_penjualan/excel'), 'Cetak ke Excel', 'class="btn btn-success"'); 
                                ?>
                            </div>



                        </div>




                    </div>




                    <div class="card-body">

                        <table id="tglSPOPFreeze" class="display nowrap" style="width:100%">
                            <thead>

                                <tr>
                                    <th>No</th>

                                    <th>
                                        <?php 
                                        
                                        if ($field_rekap == "unit") {
                                            // $field_rekap_loop = $list_data_TRANSAKSI_BARANG->unit;
                                            echo "UNIT"; 
                                        } elseif ($field_rekap == "konsumen_nama" or $field_rekap == "konsumen") {
                                            // $field_rekap_loop = $list_data_TRANSAKSI_BARANG->konsumen_nama;
                                            echo "KONSUMEN";
                                        } elseif ($field_rekap == "nama_barang") {
                                            // $field_rekap_loop = $list_data_TRANSAKSI_BARANG->nama_barang;
                                            echo "NAMA BARANG";
                                        } else {
                                            // $field_rekap_loop = $list_data_TRANSAKSI_BARANG->unit;
                                            echo "UNIT";
                                        }
                                    
                                    ?>
                                    </th>
                                    <th>SPOP</th>
                                    <th>Tanggal<br /> Jual</th>

                                    <th>Nomor<br /> Kirim</th>
                                    <th>Unit</th>
                                    <th>Konsumen</th>
                                    <th>Nama Barang <br /> Penjualan</th>
                                    <th>Jumlah</th>
                                    <th>Harga<br /> Satuan</th>
                                    <th>TOTAL</th>
                                </tr>

                            </thead>
                            <tbody>
                                <?php
                                $start = 0;
                                $field_rekap_loop = "";
                                $Total_jumlah_Barang = 0;
                                foreach ($Tbl_penjualan_data as $list_data_TRANSAKSI_BARANG) {

                                    // get data penjualan filter uuid_barang dan spop
                                    if ($start == 0) {
                                        // $x=$list_data_TRANSAKSI_BARANG;
                                        // $get_field = $x."->".$field_rekap;

                                        if ($field_rekap == "unit") {
                                            $field_rekap_loop = $list_data_TRANSAKSI_BARANG->unit;
                                        } elseif ($field_rekap == "konsumen_nama" or $field_rekap == "konsumen") {
                                            $field_rekap_loop = $list_data_TRANSAKSI_BARANG->konsumen_nama;
                                        } elseif ($field_rekap == "nama_barang") {
                                            $field_rekap_loop = $list_data_TRANSAKSI_BARANG->nama_barang;
                                        } else {
                                            $field_rekap_loop = $list_data_TRANSAKSI_BARANG->unit;
                                        }

                                ?>
                                        <!-- BARIS KE 1 HANYA MENAMPILKAN DATA NAMA KONSUMEN -->
                                        <tr>
                                            <td><?php echo ++$start; ?></td>
                                            <td>
                                                <?php
                                                // echo $field_rekap;
                                                // echo "<br/>";
                                                // echo $get_field;
                                                // echo "<br/>";
                                                echo $field_rekap_loop;
                                                // echo $list_data_TRANSAKSI_BARANG->konsumen_nama;
                                                // $field_rekap_loop = $list_data_TRANSAKSI_BARANG->konsumen_nama;
                                                ?>
                                            </td>
                                            <td></td><!-- SPOP list nama barang persediaan-->

                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>





                                        </tr>

                                        <!-- CEK DATA GROUPING DATA BERDASARKAN NAMA UUID KONSUMEN -->
                                        <!-- BARIS KE 2 DATA PENJUALAN BERDASARKAN NAMA PENJUALAN -->


                                        <!-- Baris ke 2 untuk data penjualan dari group / kelompok pertama -->
                                        <tr>
                                            <td><?php echo ++$start; ?></td>
                                            <td></td>
                                            <td>
                                                <?php

                                                $this->db->where('id', $list_data_TRANSAKSI_BARANG->id_persediaan_barang);
                                                $Query_data_persediaan_barang = $this->db->get('persediaan');
                                                $Get_data_persediaan_barang = $Query_data_persediaan_barang->row();

                                                // echo "nomor spop : "; 
                                                // if ($Get_data_persediaan_barang->spop) {
                                                //     echo "SPOP: ";
                                                // }
                                                echo $Get_data_persediaan_barang->spop;
                                                ?>
                                            </td> <!-- SPOP X data isi nomor spop dari nama barang pertama -->

                                            <td>
                                                <?php

                                                echo date("d-m-Y", strtotime($list_data_TRANSAKSI_BARANG->tgl_jual));
                                                ?>
                                            </td>
                                            <td><?php echo $list_data_TRANSAKSI_BARANG->nmrkirim; ?></td>
                                            <td><?php echo $list_data_TRANSAKSI_BARANG->unit; ?></td>
                                            <td>
                                                <?php
                                                echo $list_data_TRANSAKSI_BARANG->konsumen_nama;
                                                $Nama_Konsumen = $list_data_TRANSAKSI_BARANG->konsumen_nama;
                                                ?>
                                            </td>
                                            <td><?php echo $list_data_TRANSAKSI_BARANG->nama_barang; ?></td>
                                            <td align="right">
                                                <?php
                                                echo $list_data_TRANSAKSI_BARANG->jumlah;
                                                $Total_jumlah_Barang = $Total_jumlah_Barang + $list_data_TRANSAKSI_BARANG->jumlah;
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php
                                                echo number_format($list_data_TRANSAKSI_BARANG->harga_satuan, 2, ',', '.');
                                                ?>
                                            </td>
                                            <td align="right">

                                                <?php
                                                echo number_format($list_data_TRANSAKSI_BARANG->jumlah * $list_data_TRANSAKSI_BARANG->harga_satuan, 2, ',', '.');

                                                $Total_Harga = $Total_Harga + ($list_data_TRANSAKSI_BARANG->jumlah * $list_data_TRANSAKSI_BARANG->harga_satuan);
                                                ?>
                                            </td>





                                        </tr>



                                        <?php
                                    } else {
                                        // -------------- BARIS KE 3  DAN SETERUSNYA ------------

                                        // 1. cek dulu : cek group nya $field_rekap == $list_data_TRANSAKSI_BARANG->field_rekap ?
                                        //2. jika sama, maka list data barang,
                                        //3. jika beda, maka tampilkan :
                                        //     a. 1 baris total $field_rekap lama
                                        //     b. 1 baris nama group $field_rekap baru
                                        //     c. 1 baris data nama barang baru dari $field_rekap baru



                                        if ($field_rekap_loop == $list_data_TRANSAKSI_BARANG->$field_rekap) {
                                        ?>

                                            <!-- Baris GROUP SAMA -> LIST NAMA BARANG -->
                                            <tr>
                                                <td><?php echo ++$start; ?></td>
                                                <td></td>
                                                <td>
                                                    <?php

                                                    $this->db->where('id', $list_data_TRANSAKSI_BARANG->id_persediaan_barang);
                                                    $Query_data_persediaan_barang = $this->db->get('persediaan');
                                                    $Get_data_persediaan_barang = $Query_data_persediaan_barang->row();

                                                    // echo "nomor spop : "; 
                                                    // if ($Get_data_persediaan_barang->spop) {
                                                    //     echo "SPOP: ";
                                                    // }
                                                    echo $Get_data_persediaan_barang->spop;
                                                    ?>
                                                </td> <!-- SPOP X data isi nomor spop dari nama barang pertama -->

                                                <td>
                                                    <?php
                                                    //echo "Tanggal";
                                                    //                                    //               echo "<br/>";
                                                    //                                      //            echo $list_data_TRANSAKSI_BARANG->tgl_jual;
                                                    //                                        //         echo "<br/>";
                                                    echo date("d-m-Y", strtotime($list_data_TRANSAKSI_BARANG->tgl_jual));
                                                    ?>
                                                </td>
                                                <td><?php echo $list_data_TRANSAKSI_BARANG->nmrkirim; ?></td>
                                                <td><?php echo $list_data_TRANSAKSI_BARANG->unit; ?></td>
                                                <td>
                                                    <?php
                                                    echo $list_data_TRANSAKSI_BARANG->konsumen_nama;
                                                    $Nama_Konsumen = $list_data_TRANSAKSI_BARANG->konsumen_nama;
                                                    ?>
                                                </td>
                                                <td><?php echo $list_data_TRANSAKSI_BARANG->nama_barang; ?></td>
                                                <td align="right">
                                                    <?php
                                                    echo $list_data_TRANSAKSI_BARANG->jumlah;
                                                    $Total_jumlah_Barang = $Total_jumlah_Barang + $list_data_TRANSAKSI_BARANG->jumlah;
                                                    ?>
                                                </td>
                                                <td align="right">
                                                    <?php
                                                    echo number_format($list_data_TRANSAKSI_BARANG->harga_satuan, 2, ',', '.');
                                                    ?>
                                                </td>
                                                <td align="right">

                                                    <?php
                                                    echo number_format($list_data_TRANSAKSI_BARANG->jumlah * $list_data_TRANSAKSI_BARANG->harga_satuan, 2, ',', '.');

                                                    $Total_Harga = $Total_Harga + ($list_data_TRANSAKSI_BARANG->jumlah * $list_data_TRANSAKSI_BARANG->harga_satuan);
                                                    ?>
                                                </td>





                                            </tr>


                                        <?php
                                        } else {
                                            // GROUP BARU :

                                            //     a. 1 baris total $field_rekap lama
                                            //     b. 1 baris nama group $field_rekap baru
                                            //     c. 1 baris data nama barang baru dari $field_rekap baru
                                        ?>


                                            <!-- BARIS TOTAL GROUP LAMA -->

                                            <tr>
                                                <td><?php echo ++$start; ?></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>
                                                    <?php
                                                    echo $Nama_Konsumen;
                                                    ?>
                                                </td>
                                                <td></td>
                                                <td align="right">
                                                    <?php
                                                    echo number_format($Total_jumlah_Barang, 2, ',', '.');
                                                    // echo $list_data_TRANSAKSI_BARANG->jumlah;
                                                    // $Total_jumlah_Barang = $Total_jumlah_Barang + $list_data_TRANSAKSI_BARANG->jumlah;

                                                    $Total_jumlah_Barang = 0; //RESET TOTAL JUMLAH BARANG UNTUK GROUP SELANJUTNYA
                                                    ?>
                                                </td>
                                                <td align="right">
                                                    <?php
                                                    // echo number_format($list_data_TRANSAKSI_BARANG->harga_satuan, 2, ',', '.');
                                                    ?>
                                                </td>
                                                <td align="right">

                                                    <?php
                                                    echo number_format($Total_Harga, 2, ',', '.');

                                                    // echo number_format($list_data_TRANSAKSI_BARANG->jumlah * $list_data_TRANSAKSI_BARANG->harga_satuan, 2, ',', '.');

                                                    // $Total_Harga = $Total_Harga + ($list_data_TRANSAKSI_BARANG->jumlah * $list_data_TRANSAKSI_BARANG->harga_satuan);

                                                    $Total_Harga = 0; // RESET TOTAL JUMLAH HARGA , SETELAH TOTAL HARGA PER GROUP

                                                    ?>
                                                </td>





                                            </tr>


                                            <!-- END OF BARIS TOTAL GROUP LAMA -->

                                            <?php
                                            if ($field_rekap == "unit") {
                                                $field_rekap_loop = $list_data_TRANSAKSI_BARANG->unit;
                                            } elseif ($field_rekap == "konsumen_nama" or $field_rekap == "konsumen") {
                                                $field_rekap_loop = $list_data_TRANSAKSI_BARANG->konsumen_nama;
                                            } elseif ($field_rekap == "nama_barang") {
                                                $field_rekap_loop = $list_data_TRANSAKSI_BARANG->nama_barang;
                                            } else {
                                                $field_rekap_loop = $list_data_TRANSAKSI_BARANG->unit;
                                            }
                                            ?>


                                            <!-- BARIS NAMA GROUP BARU -->
                                            <tr>
                                                <td><?php echo ++$start; ?></td>
                                                <td>
                                                    <?php
                                                    echo $field_rekap_loop;
                                                    // echo $list_data_TRANSAKSI_BARANG->konsumen_nama;;
                                                    // $field_rekap_loop = $list_data_TRANSAKSI_BARANG->konsumen_nama;
                                                    ?>
                                                </td>
                                                <td></td><!-- SPOP list nama barang persediaan-->

                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>





                                            </tr>
                                            <!-- BARIS TRANSAKSI BARANG -->
                                            <tr>
                                                <td><?php echo ++$start; ?></td>
                                                <td></td>
                                                <td>
                                                    <?php

                                                    $this->db->where('id', $list_data_TRANSAKSI_BARANG->id_persediaan_barang);
                                                    $Query_data_persediaan_barang = $this->db->get('persediaan');
                                                    $Get_data_persediaan_barang = $Query_data_persediaan_barang->row();

                                                    // echo "nomor spop : "; 
                                                    // if ($Get_data_persediaan_barang->spop) {
                                                    //     echo "SPOP: ";
                                                    // }
                                                    echo $Get_data_persediaan_barang->spop;
                                                    ?>
                                                </td> <!-- SPOP X data isi nomor spop dari nama barang pertama -->

                                                <td>
                                                    <?php
                                                    //echo "Tanggal";
                                                    //echo "<br/>";
                                                    //echo $list_data_TRANSAKSI_BARANG->tgl_jual;
                                                    //echo "<br/>";
                                                    echo date("d-m-Y", strtotime($list_data_TRANSAKSI_BARANG->tgl_jual));
                                                    ?>
                                                </td>
                                                <td><?php echo $list_data_TRANSAKSI_BARANG->nmrkirim; ?></td>
                                                <td><?php echo $list_data_TRANSAKSI_BARANG->unit; ?></td>
                                                <td>
                                                    <?php
                                                    echo $list_data_TRANSAKSI_BARANG->konsumen_nama;
                                                    $Nama_Konsumen = $list_data_TRANSAKSI_BARANG->konsumen_nama;
                                                    ?>
                                                </td>
                                                <td><?php echo $list_data_TRANSAKSI_BARANG->nama_barang; ?></td>
                                                <td align="right">
                                                    <?php
                                                    echo $list_data_TRANSAKSI_BARANG->jumlah;
                                                    $Total_jumlah_Barang = $Total_jumlah_Barang + $list_data_TRANSAKSI_BARANG->jumlah;
                                                    ?>
                                                </td>
                                                <td align="right">
                                                    <?php
                                                    echo number_format($list_data_TRANSAKSI_BARANG->harga_satuan, 2, ',', '.');
                                                    ?>
                                                </td>
                                                <td align="right">

                                                    <?php
                                                    echo number_format($list_data_TRANSAKSI_BARANG->jumlah * $list_data_TRANSAKSI_BARANG->harga_satuan, 2, ',', '.');

                                                    $Total_Harga = $Total_Harga + ($list_data_TRANSAKSI_BARANG->jumlah * $list_data_TRANSAKSI_BARANG->harga_satuan);
                                                    ?>
                                                </td>





                                            </tr>

                                            <!-- END OF BARIS NAMA GROUP BARU -->
                                        <?php
                                        }

                                        ?>


                                <?php

                                    }
                                }



                                ?>


                                <!-- 1 BARIS TOTAL $field_rekap TERAKHIR -->
                                <tr>
                                    <td><?php echo ++$start; ?></td>
                                    <td><?php //echo $list_data_TRANSAKSI_BARANG->namabarang_persediaan; 
                                        echo "TOTAL"
                                        ?></td>
                                    <td></td> <!-- SPOP B total nama barang ke 2 dst -->

                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <?php
                                        //echo $Nama_Konsumen;
                                        echo "TOTAL"
                                        ?>
                                    </td>
                                    <td style="background-color:yellow;" align="right">TOTAL</td>
                                    <td style="background-color:yellow;" align="right"><?php //echo "<font color='red'><strong>" . number_format($Total_jumlah_Barang, 0, ',', '.') . "</strong>"; 
                                                                                        ?></td>
                                    <td style="background-color:yellow;"></td>
                                    <td style="background-color:yellow;" align="right">
                                        <?php
                                        // echo "Total 4";
                                        //echo "<font color='red'><strong>" . number_format($Total_Harga, 2, ',', '.') . "</strong>"; 
                                        ?></td>
                                </tr>






                            </tbody>



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
            "scrollY": 900,
            "scrollX": true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#example9').DataTable({
            "scrollY": 900,
            "scrollX": true
        });
    });
</script>