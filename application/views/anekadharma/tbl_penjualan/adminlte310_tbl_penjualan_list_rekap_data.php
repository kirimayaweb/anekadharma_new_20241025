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

        <?php
        $filter_qs = isset($filter_query_string) ? $filter_query_string : '';
        if ($filter_qs === '' && !empty($tgl_awal_param) && !empty($tgl_akhir_param)) {
            $filter_qs = '?tgl_awal=' . rawurlencode($tgl_awal_param) . '&tgl_akhir=' . rawurlencode($tgl_akhir_param);
        }
        $tgl_awal_tampil = isset($tgl_awal_param) ? $tgl_awal_param : '';
        $tgl_akhir_tampil = isset($tgl_akhir_param) ? $tgl_akhir_param : '';

        if (!empty($date_awal)) {
            if (date('Y', strtotime($date_awal)) < 2020) {
                $Get_date_awal = date('d-m-Y');
            } else {
                $Get_date_awal = date('d-m-Y', strtotime($date_awal));
            }
        } else {
            $Get_date_awal = $tgl_awal_tampil;
        }

        if (!empty($date_akhir)) {
            if (date('Y', strtotime($date_akhir)) < 2020) {
                $Get_date_akhir = date('d-m-Y');
            } else {
                $Get_date_akhir = date('d-m-Y', strtotime($date_akhir));
            }
        } else {
            $Get_date_akhir = $tgl_akhir_tampil;
        }

        $action_cari_rekap = site_url('Tbl_penjualan/RekapData/' . $field_rekap);
        ?>

        <div class="box box-warning box-solid">

            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <form id="form-cari-rekap-penjualan" class="rekap-toolbar-satu-baris" action="<?php echo $action_cari_rekap; ?>" method="get">
                            <input type="hidden" id="rekap-field-export" value="<?php echo htmlspecialchars($field_rekap, ENT_QUOTES, 'UTF-8'); ?>" />
                            <div class="rekap-toolbar-inner">
                                <strong class="rekap-data-title">
                                    REKAP DATA
                                    <?php
                                    if ($field_rekap == 'unit') {
                                        echo 'UNIT';
                                    } elseif ($field_rekap == 'konsumen_nama' or $field_rekap == 'konsumen') {
                                        echo 'KONSUMEN';
                                    } elseif ($field_rekap == 'nama_barang') {
                                        echo 'NAMA BARANG';
                                    } else {
                                        echo 'UNIT';
                                    }
                                    ?>
                                </strong>
                                <div class="input-group input-group-sm date rekap-tgl-input" id="rekap_tgl_awal" data-target-input="nearest">
                                    <input type="text" class="form-control form-control-sm datetimepicker-input" data-target="#rekap_tgl_awal" name="tgl_awal" value="<?php echo htmlspecialchars($Get_date_awal, ENT_QUOTES, 'UTF-8'); ?>" required />
                                    <div class="input-group-append" data-target="#rekap_tgl_awal" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                                <span class="rekap-sd">s/d</span>
                                <div class="input-group input-group-sm date rekap-tgl-input" id="rekap_tgl_akhir" data-target-input="nearest">
                                    <input type="text" class="form-control form-control-sm datetimepicker-input" data-target="#rekap_tgl_akhir" name="tgl_akhir" value="<?php echo htmlspecialchars($Get_date_akhir, ENT_QUOTES, 'UTF-8'); ?>" required />
                                    <div class="input-group-append" data-target="#rekap_tgl_akhir" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                                <a href="#" class="btn btn-warning btn-sm btn-rekap-jenis" data-field="nama_barang">Rekap Barang</a>
                                <a href="#" class="btn btn-warning btn-sm btn-rekap-jenis" data-field="konsumen_nama">Rekap Konsumen</a>
                                <a href="#" class="btn btn-warning btn-sm btn-rekap-jenis" data-field="unit">Rekap Unit</a>
                                <button type="button" class="btn btn-success btn-sm" onclick="cetakExcelRekapData(); return false;">
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Cetak ke Excel
                                </button>
                            </div>
                        </form>

                        <style>
                            .card-header {
                                padding-top: 0.52rem;
                                padding-bottom: 0.52rem;
                            }
                            .rekap-toolbar-satu-baris {
                                width: 100%;
                                margin: 0;
                            }
                            .rekap-toolbar-inner {
                                display: flex;
                                flex-wrap: nowrap;
                                align-items: center;
                                justify-content: center;
                                gap: 6px;
                                width: 100%;
                                max-width: 100%;
                                overflow-x: hidden;
                            }
                            .rekap-toolbar-inner .rekap-data-title {
                                font-size: 0.72rem;
                                font-weight: 700;
                                line-height: 1.2;
                                white-space: nowrap;
                                flex: 0 0 auto;
                                margin-right: 5px;
                            }
                            .rekap-toolbar-inner .rekap-tgl-input {
                                width: 130px;
                                min-width: 130px;
                                flex: 0 0 auto;
                            }
                            .rekap-toolbar-inner .form-control-sm {
                                font-size: 0.9rem;
                                padding: 0.18rem 0.36rem;
                                height: calc(1.5em + 0.42rem);
                            }
                            .rekap-toolbar-inner .input-group-text {
                                font-size: 0.84rem;
                                padding: 0.18rem 0.36rem;
                            }
                            .rekap-toolbar-inner .rekap-sd {
                                font-size: 0.9rem;
                                line-height: 1;
                                flex: 0 0 auto;
                                white-space: nowrap;
                            }
                            .rekap-toolbar-inner .btn-sm {
                                font-size: 0.84rem;
                                padding: 0.22rem 0.48rem;
                                line-height: 1.25;
                                white-space: nowrap;
                                flex: 0 0 auto;
                            }
                        </style>




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
                                $Total_Harga = 0;
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

<script>
(function() {
    var baseRekapUrl = <?php echo json_encode(site_url('Tbl_penjualan/RekapData/')); ?>;

    function getTanggalFilterRekap() {
        var tglAwal = document.querySelector('#form-cari-rekap-penjualan input[name="tgl_awal"]');
        var tglAkhir = document.querySelector('#form-cari-rekap-penjualan input[name="tgl_akhir"]');
        return {
            awal: tglAwal ? tglAwal.value : '',
            akhir: tglAkhir ? tglAkhir.value : ''
        };
    }

    function buildRekapUrl(field) {
        var tgl = getTanggalFilterRekap();
        var url = baseRekapUrl + field;
        if (tgl.awal && tgl.akhir) {
            url += '?tgl_awal=' + encodeURIComponent(tgl.awal) + '&tgl_akhir=' + encodeURIComponent(tgl.akhir);
        }
        return url;
    }

    document.querySelectorAll('.btn-rekap-jenis').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var field = btn.getAttribute('data-field');
            if (!field) {
                return;
            }
            var tgl = getTanggalFilterRekap();
            if (!tgl.awal || !tgl.akhir) {
                alert('Pilih tanggal awal dan tanggal akhir terlebih dahulu.');
                return;
            }
            window.location.href = buildRekapUrl(field);
        });
    });

    var submitTimer = null;

    function submitCariRekapOtomatis() {
        clearTimeout(submitTimer);
        submitTimer = setTimeout(function() {
            var form = document.getElementById('form-cari-rekap-penjualan');
            var tgl = getTanggalFilterRekap();
            if (form && tgl.awal && tgl.akhir) {
                form.submit();
            }
        }, 400);
    }

    function updateHrefTombolRekap() {
        document.querySelectorAll('.btn-rekap-jenis').forEach(function(btn) {
            var field = btn.getAttribute('data-field');
            if (field) {
                btn.href = buildRekapUrl(field);
            }
        });
    }

    if (window.jQuery) {
        jQuery('#rekap_tgl_awal, #rekap_tgl_akhir').on('change.datetimepicker hide.datetimepicker', function() {
            updateHrefTombolRekap();
            submitCariRekapOtomatis();
        });
    }

    updateHrefTombolRekap();
})();

function isDataTableRekapAktif() {
    return !!(window.jQuery && jQuery.fn.DataTable && jQuery.fn.DataTable.isDataTable('#tglSPOPFreeze'));
}

function teksSelDariTd(td) {
    if (!td) {
        return '';
    }
    return (td.innerText || td.textContent || '').replace(/\s+/g, ' ').trim();
}

function kumpulkanHeaderRekapDariTabel() {
    var headers = [];
    var headerRow = document.querySelector('#tglSPOPFreeze thead tr');
    if (!headerRow) {
        return headers;
    }
    headerRow.querySelectorAll('th').forEach(function(th) {
        headers.push(teksSelDariTd(th));
    });
    return headers;
}

function kumpulkanBarisRekapDariDataTable() {
    var rows = [];
    if (!isDataTableRekapAktif()) {
        return rows;
    }
    var table = jQuery('#tglSPOPFreeze').DataTable();
    var nodes = table.rows({ search: 'applied', order: 'applied', page: 'all' }).nodes();
    for (var i = 0; i < nodes.length; i++) {
        var tr = nodes[i];
        if (!tr) {
            continue;
        }
        var cells = [];
        tr.querySelectorAll('td').forEach(function(td) {
            cells.push(teksSelDariTd(td));
        });
        if (cells.length) {
            rows.push(cells);
        }
    }
    return rows;
}

function kumpulkanBarisRekapDariDom() {
    var rows = [];
    var tbody = document.querySelector('#tglSPOPFreeze tbody');
    if (!tbody) {
        return rows;
    }
    tbody.querySelectorAll('tr').forEach(function(tr) {
        var style = window.getComputedStyle(tr);
        if (style.display === 'none') {
            return;
        }
        var cells = [];
        tr.querySelectorAll('td').forEach(function(td) {
            cells.push(teksSelDariTd(td));
        });
        if (cells.length) {
            rows.push(cells);
        }
    });
    return rows;
}

function cetakExcelRekapData() {
    var rows = kumpulkanBarisRekapDariDataTable();
    if (!rows.length) {
        rows = kumpulkanBarisRekapDariDom();
    }
    if (!rows.length) {
        alert('Tidak ada data rekap untuk diekspor. Periksa filter/search DataTable atau rentang tanggal.');
        return;
    }

    var headers = kumpulkanHeaderRekapDariTabel();
    var fieldEl = document.getElementById('rekap-field-export');
    var fieldRekap = fieldEl ? fieldEl.value : 'rekap';
    var tgl = {
        awal: '',
        akhir: ''
    };
    var tglAwalEl = document.querySelector('#form-cari-rekap-penjualan input[name="tgl_awal"]');
    var tglAkhirEl = document.querySelector('#form-cari-rekap-penjualan input[name="tgl_akhir"]');
    if (tglAwalEl) {
        tgl.awal = tglAwalEl.value;
    }
    if (tglAkhirEl) {
        tgl.akhir = tglAkhirEl.value;
    }

    var form = document.createElement('form');
    form.method = 'POST';
    form.action = <?php echo json_encode(site_url('Tbl_penjualan/excel_rekap_data')); ?>;
    form.style.display = 'none';

    function addHidden(name, value) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value;
        form.appendChild(input);
    }

    addHidden('from_datatable', '1');
    addHidden('field_rekap', fieldRekap);
    addHidden('export_rows', JSON.stringify(rows));
    addHidden('export_headers', JSON.stringify(headers));
    addHidden('tgl_awal', tgl.awal);
    addHidden('tgl_akhir', tgl.akhir);

    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}
</script>
