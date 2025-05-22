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

        $Get_month_from_date = $month_selected;
        $Get_year_Tahun_ini = $year_selected;
        $Get_year_Setahun_lalu = date("Y", strtotime('-1 year'));
        // $futureDate=date('Y', strtotime('-1 year'));

        // if (date("Y", strtotime($date_awal)) < 2020) {
        //     $Get_date_awal = date("d-m-Y");
        // } else {
        //     $Get_date_awal = date("d-m-Y", strtotime($date_awal));
        // }

        // if (date("Y", strtotime($date_akhir)) < 2020) {
        //     $Get_date_akhir = date("d-m-Y");
        // } else {
        //     $Get_date_akhir = date("d-m-Y", strtotime($date_akhir));
        // }

        function bulan_teks($angka_bulan)
        {
            if ($angka_bulan == 1) {
                $bulan_teks = "Januari";
            } elseif ($angka_bulan == 2) {
                $bulan_teks = "Februari";
            } elseif ($angka_bulan == 3) {
                $bulan_teks = "Maret";
            } elseif ($angka_bulan == 4) {
                $bulan_teks = "April";
            } elseif ($angka_bulan == 5) {
                $bulan_teks = "Mei";
            } elseif ($angka_bulan == 6) {
                $bulan_teks = "Juni";
            } elseif ($angka_bulan == 7) {
                $bulan_teks = "Juli";
            } elseif ($angka_bulan == 8) {
                $bulan_teks = "Agustus";
            } elseif ($angka_bulan == 9) {
                $bulan_teks = "September";
            } elseif ($angka_bulan == 10) {
                $bulan_teks = "Oktober";
            } elseif ($angka_bulan == 11) {
                $bulan_teks = "November";
            } elseif ($angka_bulan == 12) {
                $bulan_teks = "Desember";
            } else {
                $bulan_teks = "";
            }
            return $bulan_teks;
        }

        ?>














        <div class="box box-warning box-solid">

            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">

                        <div class="row">
                            <div class="col-3">
                                <div class="row">
                                    <strong>NERACA SALDO <?php echo bulan_teks($Get_month_from_date) . " " . $Get_year_Tahun_ini ?></strong>




                                </div>
                            </div>

                            <div class="col-md-6">


                                <?php
                                // $action_cari_between_date = site_url('tbl_pembelian/cari_between_date');
                                $action_cari_between_date = site_url('Neraca_saldo/Cari_bulan_data');
                                ?>

                                <form action="<?php echo $action_cari_between_date; ?>" method="post">
                                    <div class="row">
                                        <div class="col-md-4" text-align="right" align="right">
                                            <input type="month" id="bulan_ns" name="bulan_ns">
                                        </div>
                                        <div class="col-md-2" text-align="left" align="left">
                                            <button type="submit" class="btn btn-danger btn-block btn-flat"><i class="fa fa-sign-in" aria-hidden="true"></i> Cari</button>
                                        </div>

                                    </div>
                                </form>




                            </div>



                        </div>

                        <!-- </form> -->



                    </div>




                    <div class="card-body">

                        <table id="example" class="table table-striped dt-responsive w-100 table-bordered display nowrap table-hover mb-0" style="width:100%">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="text-align:center" width="10px">No</th>
                                    <th rowspan="2" style="text-align:center" width="10px">Tanggal</th>
                                    <th rowspan="2" style="text-align:center">Kode Rek.</th>
                                    <th rowspan="2" style="text-align:center">Uraian</th>
                                    <th colspan="2" style="text-align:center">NERACA SALDO 1 <?php echo bulan_teks($Get_month_from_date) . " " . $Get_year_Setahun_lalu ?></th>
                                    <th colspan="2" style="text-align:center">PENYESUAIAN</th>
                                    <th colspan="2" style="text-align:center">NS SETELAH PENYESUAIAN</th>
                                    <th colspan="2" style="text-align:center">LABA/ RUGI</th>
                                </tr>
                                <tr>
                                    <th>debet</th>
                                    <th>kredit</th>
                                    <th>debet</th>
                                    <th>kredit</th>
                                    <th>debet</th>
                                    <th>kredit</th>
                                    <th>debet</th>
                                    <th>kredit</th>
                                </tr>


                            </thead>
                            <tbody>
                                <?php

                                // PEMBELIAN
                                $start = 0;
                                $TOTAL_DEBET = 0;
                                $TOTAL_KREDIT = 0;
                                $TOTAL_SALDO = 0;

                                foreach ($Data_Kode_Akun as $list_data) {

                                    $Get_Kode_akun = $list_data->kode_akun;


                                    // // GET KODE AKUN DARI TABEL PEMBELIAN : terbayar sebagai kredit / pengeluaran
                                    // $sql_pembelian = "SELECT sum(tbl_pembelian.jumlah*tbl_pembelian.harga_satuan) as kredit, tbl_pembelian.kode_akun as kode_akun
                                    // FROM tbl_pembelian    
                                    // WHERE tbl_pembelian.kode_akun='$Get_Kode_akun' AND tbl_pembelian.statuslu='L'
                                    // group BY tbl_pembelian.kode_akun";

                                    // // print_r($this->db->query($sql_pembelian)->result());
                                    // $Get_kode_akun_PEMBELIAN_kredit = $this->db->query($sql_pembelian)->row()->kredit;




                                    // END OF  Cek di tabel neraca_saldo : masing-masing kode_akun , jika belum ada maka insert dulu




                                    // PENJUALAN : 
                                    $Get_proses_bayar = "belum_bayar";
                                    $sql_pembelian = "SELECT sum(tbl_penjualan.jumlah*tbl_penjualan.harga_satuan) as kredit, tbl_penjualan.kode_akun as kode_akun
                                    FROM tbl_penjualan    
                                    WHERE tbl_penjualan.kode_akun='$Get_Kode_akun' AND tbl_penjualan.proses_bayar='$Get_proses_bayar'
                                    group BY tbl_penjualan.kode_akun";

                                    $Get_kode_akun_PENJUALAN_kredit = $this->db->query($sql_pembelian)->row()->kredit;



                                    // ======= CEK TABEL PEMBELIAN DAN PENJUALAN DENGAN KODE_AKUN YANG SESUAI, KEMUDIAN DI JUMLAHKAN ======

                                    // PEMBELIAN
                                    $sql_pembelian = "SELECT sum(`jumlah`*`harga_satuan`) as jumlah_pembelian_by_kode FROM `tbl_pembelian` WHERE `kode_akun`='$Get_Kode_akun' AND MONTH(`tgl_po`)=$Get_month_from_date AND YEAR(`tgl_po`)=$year_selected";

                                    $Get_data_KREDIT_PENYESUAIAN_dari_tbl_pembelian = $this->db->query($sql_pembelian)->row()->jumlah_pembelian_by_kode;

                                    // /PENJUALAN
                                    $sql_penjualan = "SELECT sum(`jumlah`*`harga_satuan`) as jumlah_penjualan_by_kode FROM `tbl_penjualan` WHERE `kode_akun`='$Get_Kode_akun' AND MONTH(`tgl_jual`)=$Get_month_from_date AND YEAR(`tgl_jual`)=$year_selected";

                                    $Get_data_DEBET_PENYESUAIAN_dari_tbl_penjualan = $this->db->query($sql_penjualan)->row()->jumlah_penjualan_by_kode;

                                    // print_r($this->db->query($sql_pembelian)->row()->jumlah_pembelian_by_kode);
                                    // print_r("<br/>");
                                    // print_r($this->db->query($sql_penjualan)->row()->jumlah_penjualan_by_kode);
                                    // print_r("<br/>");


                                    // TOTAL DARI SEMUA DATA PER KODE AKUN : Mendapatkan nominal data dan di simpan ke tabel neraca_saldo
                                    // $GET_jumlah_data_PER_KODE_AKUN = $this->db->query($sql_pembelian)->row()->jumlah_pembelian_by_kode + $this->db->query($sql_penjualan)->row()->jumlah_penjualan_by_kode;


                                    // print_r($GET_jumlah_data);
                                    // print_r("<br/>");
                                    // print_r("<br/>");
                                    // print_r("<br/>");

                                    // === end of CEK TABEL PEMBELIAN DAN PENJUALAN DENGAN KODE_AKUN YANG SESUAI, KEMUDIAN DI JUMLAHKAN ===


                                    // CEK DATA PENYESUAIAN PER MASING-MASING KODE AKUN

                                    $sql_penyesuaian = "SELECT sum(`debet`) as jumlah_debet, sum(`kredit`) as jumlah_kredit FROM `jurnal_penyesuaian` WHERE `kode_akun`='$Get_Kode_akun' AND MONTH(`tanggal`)=$Get_month_from_date AND YEAR(`tanggal`)=$year_selected";

                                    $Get_data_PENYESUAIAN = $this->db->query($sql_penyesuaian)->row();
                                    // if ($Get_Kode_akun == "11107") {


                                    //     print_r($Get_Kode_akun);
                                    //     print_r("<br/>");
                                    //     print_r($Get_data_PENYESUAIAN->jumlah_debet);
                                    //     print_r("<br/>");
                                    //     print_r($Get_data_PENYESUAIAN->jumlah_kredit);
                                    //     print_r("<br/>");
                                    // }
                                    // END OF CEK DATA PENYESUAIAN PER MASING-MASING KODE AKUN




                                    // Cek di tabel neraca_saldo : masing-masing kode_akun , jika belum ada maka insert dulu

                                    $sql_data = "SELECT * FROM `neraca_saldo` WHERE `kode_akun`='$Get_Kode_akun' AND MONTH(`tanggal`)=$Get_month_from_date";

                                    $Get_data_record = $this->db->query($sql_data);

                                    if ($Get_data_record->num_rows() > 0) {
                                        $RECORD_data_per_kode_akun_bulan_ini = $Get_data_record->row();

                                        // CEK FIELD DEBET PENYESUAIAN APAKAH SAMA DENGAN $GET_jumlah_data_PER_KODE_AKUN
                                        // if ($Get_data_record->row()->debet_penyesuaian <> $Get_data_DEBET_PENYESUAIAN_dari_tbl_penjualan) {

                                            // update record sesuaikan dengan $GET_jumlah_data_PER_KODE_AKUN
                                            $data = array(
                                                // 'uuid_kode_akun' => $this->input->post('uuid_kode_akun', TRUE),
                                                // 'kode_akun' => $this->input->post('kode_akun', TRUE),
                                                // 'nama_akun' => $this->input->post('nama_akun', TRUE),
                                                // 'uraian' => $this->input->post('uraian', TRUE),
                                                // 'group' => $this->input->post('group', TRUE),
                                                // 'debet_akhir_tahun_lalu' => $this->input->post('debet_akhir_tahun_lalu', TRUE),
                                                // 'kredit_akhir_tahun_lalu' => $this->input->post('kredit_akhir_tahun_lalu', TRUE),
                                                'debet_penyesuaian' => $Get_data_DEBET_PENYESUAIAN_dari_tbl_penjualan,
                                                'kredit_penyesuaian' => $Get_data_KREDIT_PENYESUAIAN_dari_tbl_pembelian,
                                                'debet_ns_setelah_penyesuaian' => $Get_data_DEBET_PENYESUAIAN_dari_tbl_penjualan + $Get_data_PENYESUAIAN->jumlah_debet,
                                                'kredit_ns_setelah_penyesuaian' => $Get_data_KREDIT_PENYESUAIAN_dari_tbl_pembelian + $Get_data_PENYESUAIAN->jumlah_kredit,
                                                // 'debet_laba_rugi' => $this->input->post('debet_laba_rugi', TRUE),
                                                // 'kreditdebet_laba_rugi' => $this->input->post('kreditdebet_laba_rugi', TRUE),
                                            );
                                            // $GET_id_record_neraca_saldo = $Get_data_record->row()->id;
                                            $this->Neraca_saldo_model->update($Get_data_record->row()->id, $data);
                                        // }

                                        // if ($Get_data_record->row()->kredit_penyesuaian <> $Get_data_KREDIT_PENYESUAIAN_dari_tbl_pembelian) {

                                        //     // update record sesuaikan dengan $GET_jumlah_data_PER_KODE_AKUN
                                        //     $data = array(
                                        //         // 'uuid_kode_akun' => $this->input->post('uuid_kode_akun', TRUE),
                                        //         // 'kode_akun' => $this->input->post('kode_akun', TRUE),
                                        //         // 'nama_akun' => $this->input->post('nama_akun', TRUE),
                                        //         // 'uraian' => $this->input->post('uraian', TRUE),
                                        //         // 'group' => $this->input->post('group', TRUE),
                                        //         // 'debet_akhir_tahun_lalu' => $this->input->post('debet_akhir_tahun_lalu', TRUE),
                                        //         // 'kredit_akhir_tahun_lalu' => $this->input->post('kredit_akhir_tahun_lalu', TRUE),
                                        //         // 'debet_penyesuaian' => $GET_jumlah_data_PER_KODE_AKUN,
                                        //         'kredit_penyesuaian' => $Get_data_KREDIT_PENYESUAIAN_dari_tbl_pembelian,
                                        //         // 'debet_ns_setelah_penyesuaian' => $this->input->post('debet_ns_setelah_penyesuaian', TRUE),
                                        //         'kredit_ns_setelah_penyesuaian' => $Get_data_KREDIT_PENYESUAIAN_dari_tbl_pembelian + $Get_data_PENYESUAIAN->jumlah_kredit,
                                        //         // 'debet_laba_rugi' => $this->input->post('debet_laba_rugi', TRUE),
                                        //         // 'kreditdebet_laba_rugi' => $this->input->post('kreditdebet_laba_rugi', TRUE),
                                        //     );
                                        //     // $GET_id_record_neraca_saldo = $Get_data_record->row()->id;
                                        //     $this->Neraca_saldo_model->update($Get_data_record->row()->id, $data);
                                        // }
                                    } else {
                                        // Proses insert record baru dengan kodeakun ini dan input data lengkap
                                        $data = array(
                                            // 'uuid_kode_akun' => $this->input->post('uuid_kode_akun', TRUE),
                                            'tanggal' => date("Y-$month_selected-1"),
                                            'kode_akun' => $Get_Kode_akun,
                                            'nama_akun' => $list_data->nama_akun,
                                            // 'uraian' => $this->input->post('uraian', TRUE),
                                            // 'group' => $this->input->post('group', TRUE),
                                            // 'debet_akhir_tahun_lalu' => $this->input->post('debet_akhir_tahun_lalu', TRUE),
                                            // 'kredit_akhir_tahun_lalu' => $this->input->post('kredit_akhir_tahun_lalu', TRUE),
                                            'debet_penyesuaian' => $Get_data_DEBET_PENYESUAIAN_dari_tbl_penjualan,
                                            'kredit_penyesuaian' => $Get_data_KREDIT_PENYESUAIAN_dari_tbl_pembelian,
                                            'debet_ns_setelah_penyesuaian' => $Get_data_DEBET_PENYESUAIAN_dari_tbl_penjualan + $Get_data_PENYESUAIAN->jumlah_debet,
                                            'kredit_ns_setelah_penyesuaian' => $Get_data_KREDIT_PENYESUAIAN_dari_tbl_pembelian + $Get_data_PENYESUAIAN->jumlah_kredit,
                                            // 'debet_laba_rugi' => $this->input->post('debet_laba_rugi', TRUE),
                                            // 'kreditdebet_laba_rugi' => $this->input->post('kreditdebet_laba_rugi', TRUE),
                                        );

                                        $this->Neraca_saldo_model->insert($data);
                                    }

                                    // print_r($Get_data_per_kode_akun_bulan_ini);

                                    // kemudian isi data per field
                                    $sql_data = "SELECT * FROM `neraca_saldo` WHERE `kode_akun`=$Get_Kode_akun AND MONTH(`tanggal`)=$Get_month_from_date";

                                    $Get_data_record = $this->db->query($sql_data);
                                    $Get_data_per_kode_akun_bulan_ini = $Get_data_record->row();

                                ?>
                                    <tr>
                                        <td align="left"><?php echo ++$start; ?></td>
                                        <td align="left"><?php echo $list_data->tanggal; ?></td>

                                        <td align="left">
                                            <?php
                                            echo $Get_Kode_akun;
                                            // . " id" . $Get_data_per_kode_akun_bulan_ini->id
                                            ?>
                                        </td>

                                        <!-- <td>Uraian</td> -->
                                        <td align="left">
                                            <?php
                                            // echo "uraian";
                                            echo $list_data->nama_akun;
                                            ?>
                                        </td>

                                        <!-- <td>debet_akhir_tahun_lalu</td> -->
                                        <td align="right">
                                            <?php
                                            // echo $Get_data_per_kode_akun_bulan_ini->debet_akhir_tahun_lalu;
                                            if ($Get_data_per_kode_akun_bulan_ini->debet_akhir_tahun_lalu > 0) {
                                                echo number_format($Get_data_per_kode_akun_bulan_ini->debet_akhir_tahun_lalu, 2, ',', '.');
                                                // echo $list_data->kode_akun;
                                                echo "<br/>";
                                            }



                                            ?>

                                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal_input_jurnal_penyesuaian">
                                                Input Debet <?php //echo $list_data->id 
                                                                ?>
                                            </button>

                                        </td>



                                        <!-- <td>kredit_akhir_tahun_lalu</td> -->
                                        <td align="right">
                                            <?php

                                            if ($Get_data_per_kode_akun_bulan_ini->kredit_akhir_tahun_lalu > 0) {
                                                // echo $Get_data_per_kode_akun_bulan_ini->kredit_akhir_tahun_lalu;
                                                echo number_format($Get_data_per_kode_akun_bulan_ini->kredit_akhir_tahun_lalu, 2, ',', '.');
                                                // echo $Get_kode_akun_PEMBELIAN_kredit;
                                                echo "<br/>";
                                            }

                                            ?>


                                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal_input_jurnal_penyesuaian">
                                                Input kredit <?php //echo $list_data->id 
                                                                ?>
                                            </button>
                                        </td>


                                        <!-- <td>debet_penyesuaian</td> -->
                                        <td align="right">
                                            <?php
                                            if ($Get_data_per_kode_akun_bulan_ini->debet_penyesuaian > 0) {
                                                // echo $Get_data_per_kode_akun_bulan_ini->debet_penyesuaian;
                                                echo number_format($Get_data_per_kode_akun_bulan_ini->debet_penyesuaian, 2, ',', '.');
                                                // echo $list_data->kode_akun;
                                                echo "<br/>";
                                            }
                                            // print_r($GET_jumlah_data_PER_KODE_AKUN);
                                            ?>
                                        </td>


                                        <!-- <td>kredit_penyesuaian</td> -->
                                        <td align="right">
                                            <?php
                                            if ($Get_data_per_kode_akun_bulan_ini->kredit_penyesuaian > 0) {
                                                // echo "kredit_penyesuaian";
                                                echo number_format($Get_data_per_kode_akun_bulan_ini->kredit_penyesuaian, 2, ',', '.');
                                            }

                                            ?>
                                        </td>

                                        <!-- <td>debet_ns_setelah_penyesuaian</td> -->
                                        <td align="right">
                                            <?php

                                            // $Get_data_PENYESUAIAN->jumlah_debet;
                                            if ($Get_data_per_kode_akun_bulan_ini->debet_ns_setelah_penyesuaian > 0) {
                                                // echo $Get_data_per_kode_akun_bulan_ini->debet_ns_setelah_penyesuaian;
                                                echo number_format($Get_data_per_kode_akun_bulan_ini->debet_ns_setelah_penyesuaian, 2, ',', '.');
                                                // echo $list_data->kode_akun;
                                            }

                                            ?>
                                        </td>


                                        <!-- <td>kredit_ns_setelah_penyesuaian</td> -->
                                        <td align="right">
                                            <?php
                                            if ($Get_data_per_kode_akun_bulan_ini->kredit_ns_setelah_penyesuaian > 0) {
                                                // echo $Get_data_per_kode_akun_bulan_ini->kredit_ns_setelah_penyesuaian;
                                                echo number_format($Get_data_per_kode_akun_bulan_ini->kredit_ns_setelah_penyesuaian, 2, ',', '.');
                                                // echo $list_data->kode_akun;
                                            }


                                            ?>
                                        </td>

                                        <!-- <td>debet_laba_rugi</td> -->
                                        <td align="right">
                                            <?php
                                            if ($Get_data_per_kode_akun_bulan_ini->debet_laba_rugi > 0) {
                                                // echo $Get_data_per_kode_akun_bulan_ini->debet_laba_rugi;
                                                echo number_format($Get_data_per_kode_akun_bulan_ini->debet_laba_rugi, 2, ',', '.');
                                                // echo $list_data->kode_akun;
                                            }

                                            ?>
                                        </td>


                                        <!-- <td>kreditdebet_laba_rugi</td> -->
                                        <td align="right">
                                            <?php
                                            if ($Get_data_per_kode_akun_bulan_ini->kreditdebet_laba_rugi > 0) {
                                                // echo $Get_data_per_kode_akun_bulan_ini->kreditdebet_laba_rugi;
                                                echo number_format($Get_data_per_kode_akun_bulan_ini->kreditdebet_laba_rugi, 2, ',', '.');
                                                // echo $list_data->kode_akun;
                                            }

                                            ?>
                                        </td>



                                    </tr>


                                <?php
                                }
                                ?>





                            </tbody>


                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </section>
</div>






<!-- MODAL -->

<!-- MODAL EXTRA LARGE UPDATE PER ID -->
<?php $action_simpan = "Simpan_input_data" ?>
<form action="<?php echo $action_simpan; ?>" method="post">
    <div class="modal fade" id="modal_input_jurnal_penyesuaian<?php //echo $list_data->id 
                                                                ?>">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">INPUT DATA PENYESUAIAN <?php //echo $list_data->id
                                                                    ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-group">



                        <form action="<?php echo $action; ?>" method="post">
                            <div class="row">
                                <!-- <div class="col-6"> -->
                                <div class="form-group">
                                    <label for="datetime">Tanggal <?php echo form_error('tgl_po') ?></label>
                                    <div class="col-3">
                                        <div class="input-group date" id="tgl_po" name="tgl_po" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input" data-target="#tgl_po" id="tgl_po" name="tgl_po" value="<?php echo $date_po_X; ?>" required />
                                            <div class="input-group-append" data-target="#tgl_po" data-toggle="datetimepicker">
                                                <div class="input-group-text">
                                                    <i class="fa fa-calendar"></i>
                                                </div>

                                            </div>

                                        </div>

                                    </div>
                                </div>
                                <!-- </dsiv> -->
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="kode_pl">Kode Rekening:</label>
                                        <div class="col-12">
                                            <input type="text" name="kode_rekening" id="kode_rekening" placeholder="kode_rekening" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-4">
                                    <label for="supplier_nama">Kode Akun : </strong></label>

                                    <select name="kode_akun" id="kode_akun" class="form-control select2" style="width: 100%; height: 40px;" required>

                                        <?php

                                        if ($get_kode_akun) {
                                            // Get Nama akun dari kode akun

                                            $sql = "SELECT * FROM `sys_kode_akun` WHERE `kode_akun`='$get_kode_akun'";
                                            $Get_nama_akun = $this->db->query($sql)->row()->nama_akun

                                        ?>
                                            <option value="<?php echo $get_kode_akun; ?>"><?php echo $get_kode_akun . " ==> " . $Get_nama_akun; ?></option>
                                        <?php
                                        } else {
                                        ?>
                                            <option value="">Pilih Kode Akun</option>
                                        <?php
                                        }
                                        ?>


                                        <?php

                                        $sql = "select * from sys_kode_akun order by kode_akun ASC";


                                        foreach ($this->db->query($sql)->result() as $m) {
                                            // foreach ($data_produk as $m) {
                                            echo "<option value='$m->kode_akun' ";
                                            echo ">  " . strtoupper($m->kode_akun)  . " ==> " . strtoupper($m->nama_akun)  . "</option>";
                                        }
                                        ?>
                                    </select>


                                </div>

                                <div class="col-4">

                                    <label for="kode_pl">Debet / Kredit</label>
                                    <select name="status_proses" id="status_proses" class="form-control select2" style="width: 100%; height: 80px;" required>

                                        <option value=""></option>
                                        <option value="debet">Debet</option>
                                        <option value="kredit">Kredit</option>


                                    </select>


                                </div>

                                <div class="col-4">
                                    <label for="kode_pl">Nominal </label>
                                    <input type="number" name="nominal_penyesuaian" id="nominal_penyesuaian" placeholder="nominal penyesuaian" class="form-control" required>
                                </div>


                            </div>
                            <div class="row">
                                <label for="kode_pl">Keterangan:</label>
                                <div class="col-12">
                                    <input type="text" name="keterangan" id="keterangan" placeholder="keterangan" class="form-control" required>
                                </div>
                            </div>




                        </form>


                    </div>


                </div>


                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <!-- <button type="button" class="btn btn-primary">Simpan</button> -->
                    <button type="submit" class="btn btn-primary">SIMPAN</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</form>
<!-- END OF MODAL EXTRA LARGE -->

<!-- END OF MODAL -->












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
            "scrollY": 600,
            "scrollX": true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#example9').DataTable({
            "scrollY": 1100,
            "scrollX": true
        });
    });
</script>