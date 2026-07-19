<div class="content-wrapper">
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

        $is_rekap_unit = ($field_rekap === 'unit');
        $is_rekap_konsumen = ($field_rekap === 'konsumen_nama' || $field_rekap === 'konsumen');
        $is_rekap_barang = ($field_rekap === 'nama_barang');
        $rekap_filter_options = isset($rekap_filter_options) && is_array($rekap_filter_options) ? $rekap_filter_options : array();
        $this->load->helper('pembelian_persediaan');
        if (!isset($action_ubah_per_id) || trim((string) $action_ubah_per_id) === '') {
            $action_ubah_per_id = site_url('tbl_penjualan/create_action_nmrkirim_update_per_id_penjualan/');
        }
        $rekap_aksi_opts = array(
            'action_ubah_per_id' => $action_ubah_per_id,
            'field_rekap' => $field_rekap,
            'filter_query_string' => $filter_qs,
            'tgl_awal_param' => $tgl_awal_tampil,
            'tgl_akhir_param' => $tgl_akhir_tampil,
        );
        ?>

        <div class="box box-warning box-solid">

            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header rekap-card-header-toolbar">
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
                                    <input type="text" class="form-control form-control-sm datetimepicker-input" data-target="#rekap_tgl_awal" id="rekap_input_tgl_awal" name="tgl_awal" value="<?php echo htmlspecialchars($Get_date_awal, ENT_QUOTES, 'UTF-8'); ?>" required autocomplete="off" />
                                    <div class="input-group-append" data-target="#rekap_tgl_awal" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                                <span class="rekap-sd">s/d</span>
                                <div class="input-group input-group-sm date rekap-tgl-input" id="rekap_tgl_akhir" data-target-input="nearest">
                                    <input type="text" class="form-control form-control-sm datetimepicker-input" data-target="#rekap_tgl_akhir" id="rekap_input_tgl_akhir" name="tgl_akhir" value="<?php echo htmlspecialchars($Get_date_akhir, ENT_QUOTES, 'UTF-8'); ?>" required autocomplete="off" />
                                    <div class="input-group-append" data-target="#rekap_tgl_akhir" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                                <span class="rekap-btn-filter-wrap">
                                    <a href="#" class="btn btn-warning btn-sm btn-rekap-jenis" data-field="nama_barang">Rekap Barang</a>
                                    <?php if ($is_rekap_barang) { ?>
                                    <select id="rekap-filter-barang" class="form-control form-control-sm rekap-filter-select" title="Filter nama barang">
                                        <option value="">— Semua Barang —</option>
                                        <?php foreach ($rekap_filter_options as $opt_barang) { ?>
                                        <option value="<?php echo htmlspecialchars($opt_barang, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($opt_barang, ENT_QUOTES, 'UTF-8'); ?></option>
                                        <?php } ?>
                                    </select>
                                    <?php } ?>
                                </span>
                                <span class="rekap-btn-filter-wrap">
                                    <a href="#" class="btn btn-warning btn-sm btn-rekap-jenis" data-field="konsumen_nama">Rekap Konsumen</a>
                                    <?php if ($is_rekap_konsumen) { ?>
                                    <select id="rekap-filter-konsumen" class="form-control form-control-sm rekap-filter-select" title="Filter konsumen">
                                        <option value="">— Semua Konsumen —</option>
                                        <?php foreach ($rekap_filter_options as $opt_konsumen) { ?>
                                        <option value="<?php echo htmlspecialchars($opt_konsumen, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($opt_konsumen, ENT_QUOTES, 'UTF-8'); ?></option>
                                        <?php } ?>
                                    </select>
                                    <?php } ?>
                                </span>
                                <span class="rekap-btn-filter-wrap">
                                    <a href="#" class="btn btn-warning btn-sm btn-rekap-jenis" data-field="unit">Rekap Unit</a>
                                    <?php if ($is_rekap_unit) { ?>
                                    <select id="rekap-filter-unit" class="form-control form-control-sm rekap-filter-select" title="Filter unit">
                                        <option value="">— Semua Unit —</option>
                                        <?php foreach ($rekap_filter_options as $opt_unit) { ?>
                                        <option value="<?php echo htmlspecialchars($opt_unit, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($opt_unit, ENT_QUOTES, 'UTF-8'); ?></option>
                                        <?php } ?>
                                    </select>
                                    <?php } ?>
                                </span>
                                <button type="button" class="btn btn-success btn-sm" onclick="cetakExcelRekapData(); return false;">
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Cetak ke Excel
                                </button>
                            </div>
                        </form>

                        <style>
                            .rekap-card-header-toolbar {
                                padding-top: 0.52rem;
                                padding-bottom: 0.52rem;
                                overflow: visible !important;
                                position: relative;
                                z-index: 40;
                            }
                            .rekap-toolbar-satu-baris {
                                width: 100%;
                                margin: 0;
                                overflow: visible;
                            }
                            .rekap-toolbar-inner {
                                display: flex;
                                flex-wrap: nowrap;
                                align-items: center;
                                justify-content: center;
                                gap: 6px;
                                width: 100%;
                                max-width: 100%;
                                overflow: visible;
                            }
                            .rekap-toolbar-inner .rekap-tgl-input {
                                position: relative;
                                z-index: 50;
                                width: 130px;
                                min-width: 130px;
                                flex: 0 0 auto;
                            }
                            .rekap-toolbar-inner .rekap-tgl-input .form-control {
                                background-color: #fff;
                                cursor: text;
                            }
                            .rekap-toolbar-inner .input-group-append [data-toggle="datetimepicker"] {
                                cursor: pointer;
                            }
                            .bootstrap-datetimepicker-widget,
                            .bootstrap-datetimepicker-widget.dropdown-menu {
                                z-index: 10060 !important;
                            }
                            .rekap-toolbar-inner .rekap-data-title {
                                font-size: 0.72rem;
                                font-weight: 700;
                                line-height: 1.2;
                                white-space: nowrap;
                                flex: 0 0 auto;
                                margin-right: 5px;
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
                            .rekap-toolbar-inner .rekap-btn-filter-wrap {
                                display: inline-flex;
                                align-items: center;
                                gap: 5px;
                                flex: 0 0 auto;
                            }
                            .rekap-toolbar-inner .rekap-filter-select {
                                width: min(200px, 22vw);
                                min-width: 140px;
                                max-width: 220px;
                                flex: 0 0 auto;
                                font-size: 0.84rem;
                                height: calc(1.5em + 0.42rem);
                                padding: 0.18rem 0.36rem;
                            }
                            #tglSPOPFreeze tr.rekap-baris-total td {
                                background-color: yellow !important;
                            }
                            #tglSPOPFreeze tr.rekap-baris-total td.rekap-nominal-total {
                                font-weight: bold;
                                font-size: 1.1em;
                            }
                            #tglSPOPFreeze th.rekap-col-aksi-header,
                            #tglSPOPFreeze td.rekap-col-aksi {
                                min-width: 118px;
                                white-space: nowrap;
                                text-align: center;
                                vertical-align: middle;
                            }
                            #tglSPOPFreeze td.rekap-col-aksi .btn {
                                margin: 1px 2px;
                            }
                        </style>




                    </div>




                    <div class="card-body">

                        <table id="tglSPOPFreeze" class="display nowrap" style="width:100%">
                            <thead>

                                <tr>
                                    <th>No</th>
                                    <th class="rekap-col-aksi-header">Action</th>

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
                                            <td></td>
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
                                            <td></td>





                                        </tr>

                                        <!-- CEK DATA GROUPING DATA BERDASARKAN NAMA UUID KONSUMEN -->
                                        <!-- BARIS KE 2 DATA PENJUALAN BERDASARKAN NAMA PENJUALAN -->


                                        <!-- Baris ke 2 untuk data penjualan dari group / kelompok pertama -->
                                        <tr>
                                            <td><?php echo ++$start; ?></td>
                                            <?php echo penjualan_render_rekap_aksi_cell($list_data_TRANSAKSI_BARANG, $rekap_aksi_opts); ?>
                                            <td></td>
                                            <td>
                                                <?php

                                                $this->db->where('id', $list_data_TRANSAKSI_BARANG->id_persediaan_barang);
                                                $Query_data_persediaan_barang = $this->db->get('persediaan');
                                                $Get_data_persediaan_barang = $Query_data_persediaan_barang->row();

                                                echo ($Get_data_persediaan_barang) ? $Get_data_persediaan_barang->spop : '';
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
                                                <?php echo penjualan_render_rekap_aksi_cell($list_data_TRANSAKSI_BARANG, $rekap_aksi_opts); ?>
                                                <td></td>
                                                <td>
                                                    <?php

                                                    $this->db->where('id', $list_data_TRANSAKSI_BARANG->id_persediaan_barang);
                                                    $Query_data_persediaan_barang = $this->db->get('persediaan');
                                                    $Get_data_persediaan_barang = $Query_data_persediaan_barang->row();

                                                    echo ($Get_data_persediaan_barang) ? $Get_data_persediaan_barang->spop : '';
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

                                            <tr class="rekap-baris-total">
                                                <td><?php echo ++$start; ?></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td align="right"><strong>TOTAL</strong></td>
                                                <td></td>
                                                <td class="rekap-nominal-total" align="right">
                                                    <?php
                                                    echo number_format($Total_jumlah_Barang, 2, ',', '.');
                                                    $Total_jumlah_Barang = 0; //RESET TOTAL JUMLAH BARANG UNTUK GROUP SELANJUTNYA
                                                    ?>
                                                </td>
                                                <td align="right"></td>
                                                <td class="rekap-nominal-total" align="right">
                                                    <?php
                                                    echo number_format($Total_Harga, 2, ',', '.');
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
                                                <td></td>
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
                                                <?php echo penjualan_render_rekap_aksi_cell($list_data_TRANSAKSI_BARANG, $rekap_aksi_opts); ?>
                                                <td></td>
                                                <td>
                                                    <?php

                                                    $this->db->where('id', $list_data_TRANSAKSI_BARANG->id_persediaan_barang);
                                                    $Query_data_persediaan_barang = $this->db->get('persediaan');
                                                    $Get_data_persediaan_barang = $Query_data_persediaan_barang->row();

                                                    echo ($Get_data_persediaan_barang) ? $Get_data_persediaan_barang->spop : '';
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
                                <tr class="rekap-baris-total">
                                    <td><?php echo ++$start; ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td align="right"><strong>TOTAL</strong></td>
                                    <td></td>
                                    <td class="rekap-nominal-total" align="right"><?php echo number_format($Total_jumlah_Barang, 2, ',', '.'); ?></td>
                                    <td align="right"></td>
                                    <td class="rekap-nominal-total" align="right"><?php echo number_format($Total_Harga, 2, ',', '.'); ?></td>
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

<?php $this->load->view('anekadharma/tbl_penjualan/_rekap_penjualan_modals_ubah'); ?>

<?php
$flash_rekap_ubah = $this->session->flashdata('message');
if (!empty($flash_rekap_ubah)) :
    ?>
<script>
(function() {
    function tampilSwalSuksesRekapUbah() {
        if (typeof Swal === 'undefined') {
            return;
        }
        Swal.fire({
            icon: 'success',
            title: 'Update Selesai',
            html: <?php echo json_encode(strip_tags((string) $flash_rekap_ubah), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
            timer: 2000,
            timerProgressBar: true,
            showConfirmButton: false,
            allowOutsideClick: true,
            customClass: {
                popup: 'penjualan-ubah-swal-success'
            }
        });
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', tampilSwalSuksesRekapUbah);
    } else {
        tampilSwalSuksesRekapUbah();
    }
})();
</script>
<?php endif; ?>

<script>
(function() {
    var baseRekapUrl = <?php echo json_encode(site_url('Tbl_penjualan/RekapData/')); ?>;
    var rekapFieldAktif = <?php echo json_encode($field_rekap); ?>;
    var rekapFilterSearchTerdaftar = false;
    window.rekapRowFilterIndex = {};

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
                try {
                    sessionStorage.removeItem('rekap_filter_' + rekapFieldAktif);
                } catch (eClear) { /* abaikan */ }
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

    function getSelectorFilterRekapAktif() {
        if (rekapFieldAktif === 'unit') {
            return '#rekap-filter-unit';
        }
        if (rekapFieldAktif === 'konsumen_nama' || rekapFieldAktif === 'konsumen') {
            return '#rekap-filter-konsumen';
        }
        if (rekapFieldAktif === 'nama_barang') {
            return '#rekap-filter-barang';
        }
        return null;
    }

    function simpanFilterRekapKeSession() {
        var selector = getSelectorFilterRekapAktif();
        if (!selector || !window.jQuery) {
            return;
        }
        var val = jQuery(selector).val() || '';
        try {
            sessionStorage.setItem('rekap_filter_' + rekapFieldAktif, val);
        } catch (eSession) { /* abaikan */ }
    }

    function muatFilterRekapDariSession() {
        var selector = getSelectorFilterRekapAktif();
        if (!selector || !window.jQuery) {
            return;
        }
        var $sel = jQuery(selector);
        if (!$sel.length) {
            return;
        }
        try {
            var saved = sessionStorage.getItem('rekap_filter_' + rekapFieldAktif);
            if (saved === null || saved === '') {
                $sel.val('');
                return;
            }
            var found = false;
            $sel.find('option').each(function() {
                if (normRekapKey(jQuery(this).val()) === normRekapKey(saved)) {
                    $sel.val(jQuery(this).val());
                    found = true;
                    return false;
                }
            });
            if (!found) {
                $sel.val('');
                sessionStorage.removeItem('rekap_filter_' + rekapFieldAktif);
            }
        } catch (eSession) { /* abaikan */ }
    }

    function initDatepickerRekapPenjualan() {
        if (!window.jQuery || typeof jQuery.fn.datetimepicker !== 'function') {
            return;
        }
        var $ = jQuery;
        var opsiPicker = {
            format: 'D-M-YYYY',
            useCurrent: false,
            allowInputToggle: true,
            widgetPositioning: {
                horizontal: 'left',
                vertical: 'bottom'
            }
        };

        ['#rekap_tgl_awal', '#rekap_tgl_akhir'].forEach(function(sel) {
            var $picker = $(sel);
            if (!$picker.length) {
                return;
            }
            if ($picker.data('DateTimePicker')) {
                try {
                    $picker.datetimepicker('destroy');
                } catch (eDestroy) { /* abaikan */ }
            }
            $picker.datetimepicker(opsiPicker);

            var valAwal = $.trim($picker.find('input').val() || '');
            if (valAwal && typeof moment !== 'undefined') {
                var mAwal = moment(valAwal, ['D-M-YYYY', 'DD-MM-YYYY', 'D-M-YY'], true);
                if (mAwal.isValid()) {
                    $picker.datetimepicker('date', mAwal);
                    $picker.find('input').val(mAwal.format('D-M-YYYY'));
                }
            }
        });

        $('#rekap_tgl_awal, #rekap_tgl_akhir')
            .off('change.datetimepicker.rekap hide.datetimepicker.rekap')
            .on('change.datetimepicker.rekap hide.datetimepicker.rekap', function() {
                updateHrefTombolRekap();
                simpanFilterRekapKeSession();
                submitCariRekapOtomatis();
            });

        $('#rekap_input_tgl_awal, #rekap_input_tgl_akhir')
            .off('change.rekapTgl blur.rekapTgl')
            .on('change.rekapTgl blur.rekapTgl', function() {
                updateHrefTombolRekap();
            });
    }

    updateHrefTombolRekap();

    function normalisasiTeksRekap(teks) {
        return String(teks || '').replace(/\s+/g, ' ').trim();
    }

    function teksDariDataTableCell(val) {
        if (val === null || val === undefined) {
            return '';
        }
        var s = String(val);
        if (s.indexOf('<') !== -1) {
            var tmp = document.createElement('div');
            tmp.innerHTML = s;
            s = tmp.textContent || tmp.innerText || s;
        }
        return normalisasiTeksRekap(s);
    }

    function normRekapKey(teks) {
        return normalisasiTeksRekap(teks).toUpperCase();
    }

    function rebuildRekapFilterIndex() {
        window.rekapRowFilterIndex = {};
        if (!window.jQuery || !jQuery.fn.DataTable || !jQuery.fn.DataTable.isDataTable('#tglSPOPFreeze')) {
            return;
        }

        var currentGroup = '';
        var table = jQuery('#tglSPOPFreeze').DataTable();

        table.rows().every(function() {
            var idx = this.index();
            var d = this.data();
            if (!d || !d.length) {
                return;
            }

            var col1 = teksDariDataTableCell(d[2]);
            var col5 = teksDariDataTableCell(d[6]);
            var col6 = teksDariDataTableCell(d[7]);
            var col7 = teksDariDataTableCell(d[8]);

            if (col1 !== '') {
                currentGroup = col1;
            }

            var unitVal = col5;
            var konsumenVal = col6;
            var barangVal = col7;

            if (rekapFieldAktif === 'unit') {
                if (unitVal === '' && currentGroup !== '') {
                    unitVal = currentGroup;
                }
            } else if (rekapFieldAktif === 'konsumen_nama' || rekapFieldAktif === 'konsumen') {
                if (konsumenVal === '' && currentGroup !== '') {
                    konsumenVal = currentGroup;
                }
            } else if (rekapFieldAktif === 'nama_barang') {
                if (barangVal === '' && currentGroup !== '') {
                    barangVal = currentGroup;
                }
            }

            window.rekapRowFilterIndex[idx] = {
                group: currentGroup,
                unit: unitVal,
                konsumen: konsumenVal,
                barang: barangVal
            };
        });
    }

    function getNilaiFilterRekapAktif() {
        if (rekapFieldAktif === 'unit') {
            var elUnit = document.getElementById('rekap-filter-unit');
            return elUnit ? normalisasiTeksRekap(elUnit.value) : '';
        }
        if (rekapFieldAktif === 'konsumen_nama' || rekapFieldAktif === 'konsumen') {
            var elKonsumen = document.getElementById('rekap-filter-konsumen');
            return elKonsumen ? normalisasiTeksRekap(elKonsumen.value) : '';
        }
        if (rekapFieldAktif === 'nama_barang') {
            var elBarang = document.getElementById('rekap-filter-barang');
            return elBarang ? normalisasiTeksRekap(elBarang.value) : '';
        }
        return '';
    }

    function barisRekapCocokFilterIndex(dataIndex, filterVal) {
        if (!filterVal) {
            return true;
        }

        var row = window.rekapRowFilterIndex[dataIndex];
        if (!row) {
            return true;
        }

        var fv = normRekapKey(filterVal);

        if (rekapFieldAktif === 'unit') {
            return normRekapKey(row.unit) === fv || normRekapKey(row.group) === fv;
        }
        if (rekapFieldAktif === 'konsumen_nama' || rekapFieldAktif === 'konsumen') {
            return normRekapKey(row.konsumen) === fv || normRekapKey(row.group) === fv;
        }
        if (rekapFieldAktif === 'nama_barang') {
            return normRekapKey(row.barang) === fv || normRekapKey(row.group) === fv;
        }
        return true;
    }

    function daftarRekapFilterSearch() {
        if (rekapFilterSearchTerdaftar || !window.jQuery || !jQuery.fn.dataTable) {
            return;
        }
        rekapFilterSearchTerdaftar = true;
        jQuery.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            var tableId = settings.nTable ? settings.nTable.getAttribute('id') : '';
            if (tableId !== 'tglSPOPFreeze') {
                return true;
            }
            var filterVal = getNilaiFilterRekapAktif();
            if (!filterVal) {
                return true;
            }
            return barisRekapCocokFilterIndex(dataIndex, filterVal);
        });
    }

    function refreshDataTableRekapFilter() {
        rebuildRekapFilterIndex();
        if (!window.jQuery || !jQuery.fn.DataTable || !jQuery.fn.DataTable.isDataTable('#tglSPOPFreeze')) {
            return;
        }
        jQuery('#tglSPOPFreeze').DataTable().draw();
    }

    function initRekapFilterCombobox() {
        daftarRekapFilterSearch();
        muatFilterRekapDariSession();
        rebuildRekapFilterIndex();

        var selector = getSelectorFilterRekapAktif();
        if (selector && window.jQuery) {
            jQuery(document)
                .off('change.rekapFilter', selector)
                .on('change.rekapFilter', selector, function() {
                    simpanFilterRekapKeSession();
                    refreshDataTableRekapFilter();
                });
        }
    }

    function initRekapHalaman() {
        initDatepickerRekapPenjualan();
        initRekapFilterCombobox();
        refreshDataTableRekapFilter();
    }

    if (window.jQuery) {
        jQuery(window).on('load', function() {
            window.setTimeout(initRekapHalaman, 200);
        });
    } else {
        window.addEventListener('load', function() {
            window.setTimeout(initRekapHalaman, 200);
        });
    }
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
