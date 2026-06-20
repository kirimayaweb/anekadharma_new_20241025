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
        $Get_month_from_date_lalu = $month_selected - 1;
        $Get_year_Tahun_ini = $year_selected;
        // $Get_year_Setahun_lalu = date("Y", strtotime('-1 year'));
        $Get_year_Setahun_lalu = $year_selected-1;


        // echo $date_awal; 
        // echo "<br/>";

        if (date("Y", strtotime($date_awal)) < 2020) {
            $Get_date_awal = date("d-m-Y");
        } else {
            $Get_date_awal = date("d-m-Y", strtotime($date_awal));
        }

        // echo $Get_date_awal;
        // echo "<br/>";
        // echo "<br/>";


        // echo $date_akhir; 
        // echo "<br/>";

        if (date("Y", strtotime($date_akhir)) < 2020) {
            $Get_date_akhir = date("d-m-Y");
        } else {
            $Get_date_akhir = date("d-m-Y", strtotime($date_akhir));
        }

        // echo $Get_date_akhir;
        // echo "<br/>";
        // echo "<br/>";

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

        if (!isset($compare_bulan_num)) {
            $compare_bulan_num = (int) date('m');
        }
        if (!isset($compare_tahun_num)) {
            $compare_tahun_num = (int) date('Y');
        }
        if (!isset($nama_bulan_id) || !is_array($nama_bulan_id)) {
            $nama_bulan_id = array(
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
            );
        }
        if (!isset($gen_tahun_min)) {
            $gen_tahun_min = 2019;
        }
        if (!isset($gen_tahun_max)) {
            $gen_tahun_max = (int) date('Y') + 1;
        }
        if (!isset($active_tab)) {
            $active_tab = 'data';
        }
        $tab_data_active = ($active_tab !== 'compare');
        $tab_compare_active = ($active_tab === 'compare');
        $url_compare_jurnal_kas_run = isset($url_compare_jurnal_kas_run)
            ? $url_compare_jurnal_kas_run
            : site_url('Jurnal_kas/ajax_compare_jurnal_kas_manual_online');
        $url_compare_jurnal_kas_excel = isset($url_compare_jurnal_kas_excel)
            ? $url_compare_jurnal_kas_excel
            : site_url('Jurnal_kas/excel_compare_jurnal_kas_manual_online');
        $url_compare_jurnal_kas_import_csv = isset($url_compare_jurnal_kas_import_csv)
            ? $url_compare_jurnal_kas_import_csv
            : site_url('Jurnal_kas/ajax_compare_import_csv_jurnal_kas');
        $url_compare_jurnal_kas_tabel_list = isset($url_compare_jurnal_kas_tabel_list)
            ? $url_compare_jurnal_kas_tabel_list
            : site_url('Jurnal_kas/ajax_compare_tabel_list_jurnal_kas');
        $url_compare_jurnal_kas_tabel_preview = isset($url_compare_jurnal_kas_tabel_preview)
            ? $url_compare_jurnal_kas_tabel_preview
            : site_url('Jurnal_kas/ajax_compare_tabel_preview_jurnal_kas');

        ?>



        <div class="box box-warning box-solid">

            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">

                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-3" align="left">
                                        <div class="col-12" text-align="center"> <strong>JURNAL KAS</strong> <?php echo bulan_teks($Get_month_from_date) . " " . $Get_year_Tahun_ini ?> </div>
                                    </div>
                                    <div class="col-2" align="left">



                                        <?php
                                        if ($this->session->userdata('id_user_level') == 1 or $this->session->userdata('id_user_level') == 2 or $this->session->userdata('id_user_level') == 9) {
                                            echo anchor(site_url('jurnal_kas/pemasukan_kas'), 'INPUT DATA', 'class="btn btn-danger"');
                                        }

                                        ?>

                                        <?php //echo anchor(site_url('jurnal_kas/pengeluaran_kas'), 'Kredit ( BKK )', 'class="btn btn-success"');
                                        ?>

                                    </div>
                                    <!-- <div class="col-2" align="left">

                                        <?php //echo anchor(site_url('jurnal_kas/pengeluaran_kas'), 'Pengeluaran Kas', 'class="btn btn-success"');
                                        ?>
                                    </div> -->

                                    <div class="col-6" align="right">

                                        <!-- <?php
                                                // $action_cari_between_date = site_url('Jurnal_kas/cari_between_date');
                                                ?>

                                        <form action="<?php //echo $action_cari_between_date; 
                                                        ?>" method="post">
                                            <div class="row">

                                                <div class="col-md-1" text-align="right" align="right"></div>

                                                <div class="col-md-3" text-align="right">
                                                    <div class="input-group date" id="tgl_awal" name="tgl_awal" data-target-input="nearest">
                                                        <input type="text" class="form-control datetimepicker-input" data-target="#tgl_awal" id="tgl_awal" name="tgl_awal" value="<?php //echo $Get_date_awal; 
                                                                                                                                                                                    ?>" required />
                                                        <div class="input-group-append" data-target="#tgl_awal" data-toggle="datetimepicker">
                                                            <div class="input-group-text">
                                                                <i class="fa fa-calendar"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-1" text-align="center" align="center">s/d</div>

                                                <div class="col-md-3" text-align="left" align="left">
                                                    <div class="input-group date" id="tgl_akhir" name="tgl_akhir" data-target-input="nearest">
                                                        <input type="text" class="form-control datetimepicker-input" data-target="#tgl_akhir" id="tgl_akhir" name="tgl_akhir" value="<?php //echo $Get_date_akhir; 
                                                                                                                                                                                        ?>" required />
                                                        <div class="input-group-append" data-target="#tgl_akhir" data-toggle="datetimepicker">
                                                            <div class="input-group-text">
                                                                <i class="fa fa-calendar"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-2" text-align="left" align="left">
                                                    <strong>
                                                        <button type="submit" class="btn btn-danger btn-block btn-flat"><i class="fa fa-sign-in" aria-hidden="true"></i> Cari</button>
                                                    </strong>
                                                </div>

                                            </div>
                                        </form>


                                        <br /> -->


                                        <?php
                                        $action_cari_between_date = site_url('Jurnal_kas/cari_between_date');
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

                                    <div class="col-1" align="right">
                                        <?php echo anchor(site_url('jurnal_kas/excel/' . $Get_year_Tahun_ini . '/' . $Get_month_from_date), 'Cetak', 'class="btn btn-success"'); ?>
                                    </div>


                                </div>
                            </div>

                        </div>

                    </div>



                    <div class="card-body">

                        <ul class="nav nav-tabs jurnal-kas-tabs" id="jurnal-kas-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link<?php echo $tab_data_active ? ' active' : ''; ?>" id="tab-jurnal-kas-data" data-toggle="pill" href="#panel-jurnal-kas-data" role="tab" aria-controls="panel-jurnal-kas-data" aria-selected="<?php echo $tab_data_active ? 'true' : 'false'; ?>">Data Jurnal Kas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link<?php echo $tab_compare_active ? ' active' : ''; ?>" id="tab-compare-jurnal-kas" data-toggle="pill" href="#panel-compare-jurnal-kas" role="tab" aria-controls="panel-compare-jurnal-kas" aria-selected="<?php echo $tab_compare_active ? 'true' : 'false'; ?>">Compare Data Manual - Online</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="jurnal-kas-tabs-content">
                            <div class="tab-pane fade<?php echo $tab_data_active ? ' show active' : ''; ?>" id="panel-jurnal-kas-data" role="tabpanel" aria-labelledby="tab-jurnal-kas-data">

                        <!-- <table id="example" class="table table-striped dt-responsive w-100 table-bordered display nowrap table-hover mb-0" style="width:100%"> -->
                        <table id="tglSPOPFreeze" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="text-align:left" width="10px">No</th>
                                    <th style="text-align:left">Tanggal</th>
                                    <th style="text-align:left">Bukti</th>
                                    <!-- <th style="text-align:left">PL</th> -->
                                    <th style="text-align:left">Keterangan</th>
                                    <th style="text-align:left">Kode</th>
                                    <th style="text-align:right">debet</th>
                                    <th style="text-align:right">Kredit</th>
                                    <!-- <th style="text-align:center">Action</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $start = 0;
                                $TOTAL_debet = 0;
                                $TOTAL_kredit = 0;
                                $TOTAL_saldo = 0;



                                ?>


                                <!-- // LIST SALDO BULAN LALU -->

                                <tr>

                                    <!-- TANGGAL -->
                                    <td><?php
                                        echo ++$start;
                                        ?>
                                    </td>

                                    <!-- TANGGAL -->
                                    <td>
                                        <?php
                                        // echo date("d-m-Y", strtotime($list_data->tanggal));
                                        // echo "<br/>";

                                        // echo anchor(site_url('Jurnal_kas/pemasukan_kas_update/' . $list_data->id), '<i class="fa fa-pencil-square-o">Ubah</i>', array('title' => 'edit', 'class' => 'btn btn-warning btn-sm'));

                                        // echo ' ';
                                        // echo anchor(site_url('jurnal_kas/delete/' . $list_data->id), '<i class="fa fa-trash-o">Hapus</i>', 'title="delete" class="btn btn-danger btn-sm" onclick="javasciprt: return confirm(\'Anda Yakin akan menghapus data ini ?\')"');

                                        ?>
                                    </td>

                                    <!-- BUKTI -->
                                    <td><?php
                                        // echo $list_data->bukti;
                                        ?>
                                    </td>

                                    <!-- KETERANGAN -->
                                    <td align="left">
                                        <?php
                                        // echo $list_data->keterangan;
                                        if ($Get_month_from_date > 1) {
                                            // $Get_month_from_date = $Get_month_from_date_lalu;
                                            echo "Saldo akhir bulan: " . bulan_teks($Get_month_from_date_lalu) . " " . $Get_year_Tahun_ini;


                                            // GET NOMINAL SALDO DARI TABEL SALDO AKHIR BULAN KEMARIN
                                            $Get_bulan_saldo = date("$Get_year_Tahun_ini-$Get_month_from_date_lalu-01");

                                            $this->db->where('tanggal', $Get_bulan_saldo);
                                            $GET_jurnal_kas_saldo_akhir_bulan = $this->db->get('jurnal_kas_saldo_akhir_bulan');

                                            if ($GET_jurnal_kas_saldo_akhir_bulan->num_rows() > 0) {
                                                // echo $GET_jurnal_kas_saldo_akhir_bulan->row()->saldo;
                                                $SALDO_AKHIR_BULAN_LALU = $GET_jurnal_kas_saldo_akhir_bulan->row()->saldo;
                                            } else {
                                                // echo "0";
                                                $SALDO_AKHIR_BULAN_LALU = 0;
                                            }
                                        } else {

                                            echo "Saldo akhir bulan: Desember " . $Get_year_Setahun_lalu;

                                            // GET NOMINAL SALDO DARI TABEL SALDO AKHIR BULAN DESEMBER KEMARIN
                                            $Get_bulan_saldo = date("$Get_year_Setahun_lalu-12-01");

                                            $this->db->where('tanggal', $Get_bulan_saldo);
                                            $GET_jurnal_kas_saldo_akhir_bulan = $this->db->get('jurnal_kas_saldo_akhir_bulan');

                                            if ($GET_jurnal_kas_saldo_akhir_bulan->num_rows() > 0) {
                                                // echo $GET_jurnal_kas_saldo_akhir_bulan->row()->saldo;
                                                $SALDO_AKHIR_BULAN_LALU = $GET_jurnal_kas_saldo_akhir_bulan->row()->saldo;
                                            } else {
                                                // echo "0";
                                                $SALDO_AKHIR_BULAN_LALU = 0;
                                            }
                                        }

                                        ?>
                                    </td>

                                    <td align="left">
                                        <?php
                                        // echo $list_data->kode_unit;
                                        ?>
                                    </td>

                                    <!-- Debet -->
                                    <td style="text-align:right">
                                        <?php
                                        // if ($list_data->debet > 0) {
                                        //     echo number_format($list_data->debet, 2, ',', '.');
                                        //     $TOTAL_debet = $TOTAL_debet + $list_data->debet;
                                        // } else {
                                        //     echo "";
                                        // }
                                        if ($SALDO_AKHIR_BULAN_LALU > 0) {
                                            echo number_format($SALDO_AKHIR_BULAN_LALU, 2, ',', '.');
                                            $TOTAL_debet = $TOTAL_debet + $SALDO_AKHIR_BULAN_LALU;
                                        }

                                        ?>
                                    </td>

                                    <!-- Kredit -->
                                    <td style="text-align:right">
                                        <?php
                                        // if ($list_data->kredit > 0) {
                                        //     echo number_format($list_data->kredit, 2, ',', '.');
                                        //     $TOTAL_kredit = $TOTAL_kredit + $list_data->kredit;
                                        // } else {
                                        //     echo "";
                                        // }
                                        if ($SALDO_AKHIR_BULAN_LALU < 1) {
                                            echo number_format($SALDO_AKHIR_BULAN_LALU, 2, ',', '.');
                                            $TOTAL_kredit = $TOTAL_kredit + $SALDO_AKHIR_BULAN_LALU;
                                        }
                                        ?>
                                    </td>

                                </tr>

                                <!-- // END OF LIST SALDO BULAN LALU -->


                                <?php


                                foreach ($Data_kas as $list_data) {
                                    // [0] => stdClass Object ( [nomor] => 4280 [tanggal] => 30/09/2024 [bukti] => BKK [keterangan] => Biaya PU/ATK : Putro Bengkel (Pembayaran SPOP No 558 Tgl 30/09/2024) [kode_rekening] => 4 [debet] => [kredit] => 1.750.000,00 )
                                ?>

                                    <tr>
                                        <td><?php
                                            echo ++$start;
                                            ?></td>
                                        <td>
                                            <?php
                                            echo date("d-m-Y", strtotime($list_data->tanggal));
                                            echo "<br/>";

                                            // if ($list_data->debet > 0) {
                                            //     // Ubah debet
                                            echo anchor(site_url('Jurnal_kas/pemasukan_kas_update/' . $list_data->id), '<i class="fa fa-pencil-square-o">Ubah</i>', array('title' => 'edit', 'class' => 'btn btn-warning btn-sm'));
                                            // } else {
                                            //     // Ubah Kredit
                                            //     echo anchor(site_url('Jurnal_kas/pengeluaran_kas_update/' . $list_data->id), '<i class="fa fa-pencil-square-o">Ubah</i>', array('title' => 'edit', 'class' => 'btn btn-warning btn-sm'));
                                            // }

                                            echo ' ';
                                            echo anchor(site_url('jurnal_kas/delete/' . $list_data->id), '<i class="fa fa-trash-o">Hapus</i>', 'title="delete" class="btn btn-danger btn-sm" onclick="javasciprt: return confirm(\'Anda Yakin akan menghapus data ini ?\')"');



                                            ?>
                                        </td>

                                        <td><?php
                                            echo $list_data->bukti;
                                            ?>
                                        </td>

                                        <td align="left">
                                            <?php
                                            echo $list_data->keterangan;
                                            ?>
                                        </td>

                                        <td align="left">
                                            <?php
                                            echo $list_data->kode_unit;
                                            ?>
                                        </td>

                                        <!-- Debet -->
                                        <td style="text-align:right">
                                            <?php
                                            if ($list_data->debet > 0) {
                                                echo number_format($list_data->debet, 2, ',', '.');
                                                $TOTAL_debet = $TOTAL_debet + $list_data->debet;
                                            } else {
                                                echo "";
                                            }
                                            ?>
                                        </td>

                                        <!-- Kredit -->
                                        <td style="text-align:right">
                                            <?php
                                            if ($list_data->kredit > 0) {
                                                echo number_format($list_data->kredit, 2, ',', '.');
                                                $TOTAL_kredit = $TOTAL_kredit + $list_data->kredit;
                                            } else {
                                                echo "";
                                            }

                                            ?>
                                        </td>


                                    </tr>

                                <?php
                                }
                                ?>

                            </tbody>

                            <!-- tfoot -->

                            <tfoot>

                                <!-- JUMLAH DEBET / KREDIT -->
                                <tr>
                                    <!-- <th style="text-align:center"></th>
                                    <th style="text-align:center"></th>
                                    <th style="text-align:center"></th>
                                    <th style="text-align:center"></th>
                                    <th style="text-align:center"></th> -->
                                    <th colspan="5" style="text-align:right"> JUMLAH DEBET / KREDIT </th>
                                    <th style="text-align:right">
                                        <?php
                                        echo number_format($TOTAL_debet, 2, ',', '.');
                                        ?>
                                    </th>
                                    <th style="text-align:right">
                                        <?php
                                        echo number_format($TOTAL_kredit, 2, ',', '.');
                                        ?>
                                    </th>
                                    <!-- <th style="text-align:center">Action</th> -->

                                </tr>

                                <!-- Saldo akhir Kas Bulan September -->
                                <tr>
                                    <!-- <th style="text-align:center"></th>
                                    <th style="text-align:center"></th>
                                    <th style="text-align:center"></th>
                                    <th style="text-align:center"></th>
                                    <th style="text-align:center"></th> -->
                                    <th colspan="5" style="text-align:right">Saldo akhir Kas Bulan
                                        <?php echo bulan_teks($Get_month_from_date) ?>
                                    </th>
                                    <th style="text-align:right">
                                        <?php
                                        // echo number_format($TOTAL_debet, 2, ',', '.');
                                        ?>
                                    </th>
                                    <th style="text-align:right">
                                        <?php
                                        echo number_format($TOTAL_debet - $TOTAL_kredit, 2, ',', '.');
                                        $SALDO_AKHIR = $TOTAL_debet - $TOTAL_kredit;



                                        // SIMPAN KE TABEL jurnal_kas_saldo_akhir_bulan SALDO BULAN TERPILIH

                                        $Get_bulan_saldo = date("$Get_year_Tahun_ini-$Get_month_from_date-01");

                                        $this->db->where('tanggal', $Get_bulan_saldo);
                                        $GET_jurnal_kas_saldo_akhir_bulan = $this->db->get('jurnal_kas_saldo_akhir_bulan');

                                        if ($GET_jurnal_kas_saldo_akhir_bulan->num_rows() > 0) {
                                            // print_r("<br/>");
                                            // print_r("ada data");
                                            // print_r("<br/>");
                                            // print_r($GET_jurnal_kas_saldo_akhir_bulan->row()->id);

                                            $data = array(
                                                'saldo' => $SALDO_AKHIR,
                                            );

                                            $this->Jurnal_kas_saldo_akhir_bulan_model->update($GET_jurnal_kas_saldo_akhir_bulan->row()->id, $data);
                                        } else {
                                            $data = array(
                                                'tanggal' => $Get_bulan_saldo,
                                                'saldo' => $SALDO_AKHIR,
                                            );

                                            $this->Jurnal_kas_saldo_akhir_bulan_model->insert($data);
                                        }

                                        ?>
                                    </th>
                                    <!-- <th style="text-align:center">Action</th> -->

                                </tr>

                                <!-- JUMLAH SEIMBANG -->
                                <tr>
                                    <!-- <th style="text-align:center"></th>
                                    <th style="text-align:center"></th>
                                    <th style="text-align:center"></th>
                                    <th style="text-align:center"></th>
                                    <th style="text-align:center"></th> -->
                                    <th colspan="5" style="text-align:right">JUMLAH SEIMBANG </th>
                                    <th style="text-align:right">
                                        <?php

                                        if ($SALDO_AKHIR >= 0) {
                                            echo number_format($TOTAL_debet, 2, ',', '.');
                                        } else {
                                            echo number_format($SALDO_AKHIR, 2, ',', '.');
                                        }

                                        ?>

                                    </th>
                                    <th style="text-align:right">
                                        <?php

                                        if ($SALDO_AKHIR >= 0) {
                                            echo number_format($TOTAL_debet, 2, ',', '.');
                                        } else {
                                            echo number_format($SALDO_AKHIR, 2, ',', '.');
                                        }


                                        ?>

                                    </th>
                                    <!-- <th style="text-align:center">Action</th> -->

                                </tr>

                            </tfoot>



                            <!-- end of tfoot -->


                        </table>
                            </div><!-- /.tab-pane data jurnal kas -->

                            <?php
                            $compare_jurnal_kas_sections = array(
                                array('jenis' => 'data_manual', 'num' => '1', 'label' => 'Data Manual', 'subtitle' => 'Tabel CSV / database terpilih', 'badge' => 'compare-jurnal-kas-badge-manual', 'table' => 'table-compare-jurnal-kas-manual', 'theme' => 'primary', 'icon' => 'fa-database', 'col' => 'col-lg-6'),
                                array('jenis' => 'data_online', 'num' => '2', 'label' => 'Data Online', 'subtitle' => 'Semua data jurnal_kas bulan terpilih', 'badge' => 'compare-jurnal-kas-badge-online', 'table' => 'table-compare-jurnal-kas-online', 'theme' => 'info', 'icon' => 'fa-cloud', 'col' => 'col-lg-6'),
                                array('jenis' => 'data_cocok', 'num' => '3', 'label' => 'Data Cocok (Manual & Online)', 'subtitle' => 'Tanggal, bukti, kode rekening, keterangan, debet, kredit sama', 'badge' => 'compare-jurnal-kas-badge-cocok', 'table' => 'table-compare-jurnal-kas-cocok', 'theme' => 'success', 'icon' => 'fa-check-circle', 'col' => 'col-lg-6'),
                                array('jenis' => 'manual_tidak_di_online', 'num' => '4', 'label' => 'Manual Tidak Ada di Online', 'subtitle' => 'Tidak cocok / tidak ditemukan di jurnal_kas', 'badge' => 'compare-jurnal-kas-badge-manual-miss', 'table' => 'table-compare-jurnal-kas-manual-miss', 'theme' => 'warning', 'icon' => 'fa-exclamation-triangle', 'col' => 'col-lg-6'),
                                array('jenis' => 'online_tidak_di_manual', 'num' => '5', 'label' => 'Online Tidak Ada di Manual', 'subtitle' => 'Ada di jurnal_kas, tidak cocok di manual', 'badge' => 'compare-jurnal-kas-badge-online-miss', 'table' => 'table-compare-jurnal-kas-online-miss', 'theme' => 'cyan', 'icon' => 'fa-exchange-alt', 'col' => 'col-lg-12'),
                            );
                            ?>

                            <div class="tab-pane fade<?php echo $tab_compare_active ? ' show active' : ''; ?>" id="panel-compare-jurnal-kas" role="tabpanel" aria-labelledby="tab-compare-jurnal-kas">

                                <div class="row mb-2">
                                    <div class="col-md-12">
                                        <small class="text-muted d-block mb-2">
                                            Bandingkan data jurnal kas online (<strong>jurnal_kas</strong>)
                                            dengan tabel manual hasil upload CSV.
                                            Kolom CSV minimal: <strong>tanggal, bukti, keterangan, kode_rekening, debet, kredit</strong>.
                                            Pilih file CSV — tabel database akan langsung dibuat otomatis.
                                        </small>
                                        <label for="compare_jurnal_kas_csv_file" class="mb-1">Pilih file CSV (upload ke database menjadi tabel data)</label>
                                        <div class="d-flex flex-wrap align-items-end compare-csv-upload-row mb-3">
                                            <div class="custom-file custom-file-sm mb-0 compare-csv-file-wrap">
                                                <input type="file" class="custom-file-input" id="compare_jurnal_kas_csv_file" accept=".csv,text/csv">
                                                <label class="custom-file-label" for="compare_jurnal_kas_csv_file" data-browse="Pilih">Cari / pilih file CSV...</label>
                                            </div>
                                        </div>
                                        <div id="compare-jurnal-kas-csv-upload-info" class="alert alert-light border py-2 d-none mb-3">
                                            <div class="small mb-1"><span class="text-muted">File:</span> <strong id="compare-jurnal-kas-csv-filename">—</strong></div>
                                            <div class="small mb-1"><span class="text-muted">Tabel DB:</span> <strong id="compare-jurnal-kas-csv-tablename" class="text-primary">—</strong> <span class="text-muted" id="compare-jurnal-kas-csv-rowcount"></span></div>
                                            <button type="button" id="btn-compare-jurnal-kas-csv-cek-data" class="btn btn-outline-info btn-sm"><i class="fas fa-table"></i> Detail Tabel</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-end compare-toolbar-row flex-wrap">
                                    <div class="col-auto mb-2">
                                        <label for="compare_bulan_jurnal_kas" class="small mb-1">Bulan</label>
                                        <select id="compare_bulan_jurnal_kas" class="form-control form-control-sm compare-toolbar-control">
                                            <?php foreach ($nama_bulan_id as $num => $label_bulan) { ?>
                                                <option value="<?php echo (int) $num; ?>"<?php echo ((int) $num === (int) $compare_bulan_num) ? ' selected' : ''; ?>><?php echo htmlspecialchars($label_bulan, ENT_QUOTES, 'UTF-8'); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-auto mb-2">
                                        <label for="compare_tahun_jurnal_kas" class="small mb-1">Tahun</label>
                                        <select id="compare_tahun_jurnal_kas" class="form-control form-control-sm compare-toolbar-control">
                                            <?php for ($th = $gen_tahun_max; $th >= $gen_tahun_min; $th--) { ?>
                                                <option value="<?php echo (int) $th; ?>"<?php echo ((int) $th === (int) $compare_tahun_num) ? ' selected' : ''; ?>><?php echo (int) $th; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-auto mb-2">
                                        <label for="compare_tabel_jurnal_kas" class="small mb-1">Pilih tabel database</label>
                                        <select id="compare_tabel_jurnal_kas" class="form-control form-control-sm compare-toolbar-control compare-toolbar-tabel">
                                            <option value="">— Muat daftar tabel —</option>
                                        </select>
                                    </div>
                                    <div class="col-auto mb-2">
                                        <label class="small mb-1 d-block">&nbsp;</label>
                                        <div class="d-flex flex-wrap align-items-center">
                                            <button type="button" id="btn-compare-jurnal-kas" class="btn btn-info btn-sm d-none"><i class="fas fa-columns"></i> Compare</button>
                                            <button type="button" id="btn-compare-jurnal-kas-excel-all" class="btn btn-success btn-sm d-none ml-2"><i class="fa fa-file-excel-o"></i> Cetak ke Excel</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-secondary py-2" id="compare-jurnal-kas-info-ringkas">
                                    <strong>Bulan:</strong> <span id="compare-jurnal-kas-label-bulan">—</span>
                                    &nbsp;|&nbsp; <strong>Tabel manual:</strong> <span id="compare-jurnal-kas-label-tabel">—</span>
                                    &nbsp;|&nbsp; <strong>Manual:</strong> <span id="compare-jurnal-kas-count-manual">—</span>
                                    &nbsp;|&nbsp; <strong>Online:</strong> <span id="compare-jurnal-kas-count-online">—</span>
                                    &nbsp;|&nbsp; <strong>Cocok:</strong> <span id="compare-jurnal-kas-count-cocok">—</span>
                                    &nbsp;|&nbsp; <strong>Manual tidak di online:</strong> <span id="compare-jurnal-kas-count-manual-miss">—</span>
                                    &nbsp;|&nbsp; <strong>Online tidak di manual:</strong> <span id="compare-jurnal-kas-count-online-miss">—</span>
                                </div>
                                <div class="alert alert-info py-2 mb-3" id="compare-jurnal-kas-status">
                                    Pilih file CSV, bulan, tahun, dan tabel manual — klik <strong>Compare</strong>. Setelah selesai, tombol <strong>Cetak ke Excel</strong> dan tabel hasil akan muncul.
                                </div>
                                <div class="alert alert-warning py-2 mb-3 d-none" id="compare-jurnal-kas-field-info"></div>
                                <div class="alert alert-warning py-2 mb-3 d-none" id="compare-jurnal-kas-warnings"></div>

                                <div id="compare-jurnal-kas-results-panel" class="d-none">
                                    <h5 class="mb-3 text-primary"><i class="fas fa-chart-bar"></i> Hasil Komparasi Jurnal Kas</h5>
                                    <div class="row">
                                    <?php foreach ($compare_jurnal_kas_sections as $sec) { ?>
                                    <div class="<?php echo $sec['col']; ?> mb-3">
                                        <div class="compare-jurnal-kas-section-card compare-theme-<?php echo $sec['theme']; ?>">
                                            <div class="compare-jurnal-kas-section-header">
                                                <div class="compare-jurnal-kas-section-title">
                                                    <span class="compare-section-num"><?php echo $sec['num']; ?></span>
                                                    <i class="fas <?php echo $sec['icon']; ?> compare-section-icon"></i>
                                                    <div>
                                                        <div class="compare-section-label"><?php echo htmlspecialchars($sec['label'], ENT_QUOTES, 'UTF-8'); ?></div>
                                                        <div class="compare-section-subtitle"><?php echo htmlspecialchars($sec['subtitle'], ENT_QUOTES, 'UTF-8'); ?></div>
                                                    </div>
                                                </div>
                                                <div class="compare-jurnal-kas-section-actions">
                                                    <span id="<?php echo $sec['badge']; ?>" class="badge compare-section-badge">0</span>
                                                </div>
                                            </div>
                                            <div class="compare-dt-wrap">
                                                <table id="<?php echo $sec['table']; ?>" class="table table-bordered table-sm compare-dt compare-jurnal-kas-dt" style="width:100%;">
                                                    <thead><tr><th>No</th><th>Tanggal</th><th>Bukti</th><th>Kode Rek</th><th>Keterangan</th><th>Debet</th><th>Kredit</th><th>Catatan</th></tr></thead>
                                                    <tbody></tbody>
                                                    <tfoot><tr class="compare-dt-total-row"><th colspan="5" class="text-right">Total</th><th class="compare-total-debet text-right">—</th><th class="compare-total-kredit text-right">—</th><th></th></tr></tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    </div>
                                </div>

                                <div class="modal fade" id="modal-compare-jurnal-kas-csv-preview" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document" style="max-width:95%;">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white py-2">
                                                <h5 class="modal-title">Detail Tabel CSV</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <div class="modal-body pb-2">
                                                <p class="text-muted small mb-2" id="compare-jurnal-kas-csv-preview-meta">Memuat...</p>
                                                <table id="table-compare-jurnal-kas-csv-preview" class="table table-bordered table-striped table-sm" style="width:100%;font-size:12px;">
                                                    <thead><tr></tr></thead><tbody></tbody>
                                                </table>
                                            </div>
                                            <div class="modal-footer py-2"><button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Tutup</button></div>
                                        </div>
                                    </div>
                                </div>

                            </div><!-- /.tab-pane compare -->
                        </div><!-- /.tab-content -->
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </section>
</div>

<style type="text/css">
    div.dataTables_wrapper { width: 100%; margin: 0 auto; }
    .nav-tabs.jurnal-kas-tabs { border-bottom: 2px solid #dc3545; margin-bottom: 15px; }
    .nav-tabs.jurnal-kas-tabs .nav-link { background: #fff; border: 2px solid #dc3545; border-bottom: none; color: #888; margin-right: 4px; border-radius: 4px 4px 0 0; opacity: .75; }
    .nav-tabs.jurnal-kas-tabs .nav-link.active { background: #007bff; color: #000; font-weight: bold; opacity: 1; }
    .compare-toolbar-row .compare-toolbar-control { width: 110px; min-width: 110px; }
    #compare_tahun_jurnal_kas.compare-toolbar-control { width: 88px; min-width: 88px; }
    #compare_tabel_jurnal_kas.compare-toolbar-tabel { width: 360px; min-width: 270px; max-width: 480px; }
    .compare-csv-file-wrap { max-width: 520px; min-width: 280px; flex: 0 1 520px; }
    #compare-jurnal-kas-results-panel { margin-top: 8px; animation: compareJurnalKasFadeIn .35s ease; }
    @keyframes compareJurnalKasFadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    .compare-jurnal-kas-section-card { border-radius: 10px; border: 1px solid #dee2e6; background: #fff; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.05); display: flex; flex-direction: column; height: 100%; }
    .compare-jurnal-kas-section-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; padding: 10px 14px; border-bottom: 1px solid rgba(0,0,0,.08); }
    .compare-jurnal-kas-section-title { display: flex; align-items: center; gap: 10px; }
    .compare-section-num { display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background: rgba(0,0,0,.08); font-weight: 700; font-size: 12px; }
    .compare-section-label { font-weight: 700; font-size: 14px; line-height: 1.2; }
    .compare-section-subtitle { font-size: 11px; color: #6c757d; }
    .compare-section-badge { font-size: 12px; margin-right: 6px; }
    .compare-theme-primary .compare-jurnal-kas-section-header { background: linear-gradient(90deg, #e7f1ff, #fff); border-left: 4px solid #007bff; }
    .compare-theme-info .compare-jurnal-kas-section-header { background: linear-gradient(90deg, #e8f7fa, #fff); border-left: 4px solid #17a2b8; }
    .compare-theme-success .compare-jurnal-kas-section-header { background: linear-gradient(90deg, #e8f5e9, #fff); border-left: 4px solid #28a745; }
    .compare-theme-warning .compare-jurnal-kas-section-header { background: linear-gradient(90deg, #fff8e1, #fff); border-left: 4px solid #ffc107; }
    .compare-theme-cyan .compare-jurnal-kas-section-header { background: linear-gradient(90deg, #e0f7fa, #fff); border-left: 4px solid #17a2b8; }
    .compare-dt-wrap .dataTables_wrapper { font-size: 13px; }
    .compare-dt-wrap table.dataTable thead th { background: #f8f9fa; font-size: 12px; white-space: nowrap; }
    .compare-dt-wrap table.dataTable tbody td { font-size: 12px; padding: 6px 8px; vertical-align: middle; }
    .compare-dt-wrap .text-amount-debet { color: #155724; font-weight: 600; }
    .compare-dt-wrap .text-amount-kredit { color: #0c5460; font-weight: 600; }
    .compare-dt-wrap .text-catatan { font-size: 11px; color: #856404; }
    .compare-dt-total-row th { background: #fff3cd !important; font-weight: 700; }
</style>

<script>
window.addEventListener('load', function() {
    if (!window.jQuery || !jQuery.fn.DataTable) {
        console.error('Compare Jurnal Kas: jQuery/DataTables belum dimuat.');
        return;
    }
    var urlRun = <?php echo json_encode($url_compare_jurnal_kas_run); ?>;
    var urlExcel = <?php echo json_encode($url_compare_jurnal_kas_excel); ?>;
    var urlImport = <?php echo json_encode($url_compare_jurnal_kas_import_csv); ?>;
    var urlList = <?php echo json_encode($url_compare_jurnal_kas_tabel_list); ?>;
    var urlPreview = <?php echo json_encode($url_compare_jurnal_kas_tabel_preview); ?>;
    var lastResult = null, dtMap = {}, tablesLoaded = false, csvBusy = false, csvLast = null;

    function bulanKey() {
        var b = parseInt(jQuery('#compare_bulan_jurnal_kas').val(), 10);
        var t = parseInt(jQuery('#compare_tahun_jurnal_kas').val(), 10);
        if (!b || !t) return '';
        return t + '-' + String(b).padStart(2, '0');
    }
    function parseAmt(v) {
        if (v == null || v === '') return 0;
        var s = String(v);
        if (s.indexOf('<') >= 0) s = jQuery('<div>').html(s).text();
        s = s.replace(/\./g, '').replace(',', '.').replace(/[^0-9.-]/g, '');
        var n = parseFloat(s); return isNaN(n) ? 0 : n;
    }
    function fmtAmtCell(v, type) {
        var n = parseAmt(v);
        if (!v || n === 0) return '<span class="text-amount text-amount-empty">—</span>';
        return '<span class="text-amount text-amount-' + type + '">' + jQuery('<span>').text(String(v)).html() + '</span>';
    }
    function setStatus(type, html) {
        var $el = jQuery('#compare-jurnal-kas-status');
        $el.removeClass('alert-info alert-success alert-danger alert-warning');
        $el.addClass(type === 'success' ? 'alert-success' : (type === 'danger' ? 'alert-danger' : (type === 'warning' ? 'alert-warning' : 'alert-info')));
        $el.html(html);
    }
    function toggleBtns() {
        var show = bulanKey() !== '' && (jQuery('#compare_tabel_jurnal_kas').val() || '') !== '';
        jQuery('#btn-compare-jurnal-kas').toggleClass('d-none', !show);
        if (!show) jQuery('#btn-compare-jurnal-kas-excel-all').addClass('d-none');
    }
    function buildRows(items) {
        return (items || []).map(function(it, i) {
            return [
                i + 1,
                it.tanggal || '',
                it.bukti ? jQuery('<span>').text(it.bukti).html() : '',
                it.kode_rekening ? jQuery('<span>').text(it.kode_rekening).html() : '',
                it.keterangan ? '<span class="text-ket">' + jQuery('<span>').text(it.keterangan).html() + '</span>' : '',
                fmtAmtCell(it.debet, 'debet'),
                fmtAmtCell(it.kredit, 'kredit'),
                it.catatan ? '<span class="text-catatan">' + jQuery('<span>').text(it.catatan).html() + '</span>' : ''
            ];
        });
    }
    function renderTable(sel, items) {
        var $t = jQuery(sel); if (!$t.length) return;
        items = items || [];
        if (jQuery.fn.DataTable.isDataTable($t)) $t.DataTable().clear().destroy();
        $t.find('tbody').empty();
        var dt = $t.DataTable({
            data: buildRows(items), paging: true, searching: true, ordering: true, info: true,
            scrollX: true, scrollY: '300px', scrollCollapse: true, pageLength: 25,
            order: [[1, 'asc']], autoWidth: false,
            language: { url: '//cdn.datatables.net/plug-ins/1.11.4/i18n/id.json', emptyTable: 'Tidak ada data' },
            drawCallback: function() {
                var td = 0, tk = 0;
                items.forEach(function(it) { td += parseAmt(it.debet); tk += parseAmt(it.kredit); });
                $t.find('.compare-total-debet').text(td > 0 ? td.toLocaleString('id-ID') : '—');
                $t.find('.compare-total-kredit').text(tk > 0 ? tk.toLocaleString('id-ID') : '—');
            }
        });
        dtMap[sel] = dt;
    }
    function renderAll(res) {
        renderTable('#table-compare-jurnal-kas-manual', res.data_manual);
        renderTable('#table-compare-jurnal-kas-online', res.data_online);
        renderTable('#table-compare-jurnal-kas-cocok', res.data_cocok);
        renderTable('#table-compare-jurnal-kas-manual-miss', res.manual_tidak_di_online);
        renderTable('#table-compare-jurnal-kas-online-miss', res.online_tidak_di_manual);
    }
    function formatFieldValidation(fv) {
        if (!fv) return '';
        var parts = [];
        if (fv.manual) {
            if (fv.manual.mapped && typeof fv.manual.mapped === 'object') {
                var mapped = [];
                jQuery.each(fv.manual.mapped, function(k, v) { mapped.push(k + ' → ' + v); });
                if (mapped.length) parts.push('<strong>Tabel manual:</strong> ' + mapped.join(', '));
            }
            if (fv.manual.missing_fields && fv.manual.missing_fields.length) {
                parts.push('<strong>Manual kolom tidak ada:</strong> ' + fv.manual.missing_fields.join(', '));
            }
        }
        if (fv.online) {
            if (fv.online.mapped && typeof fv.online.mapped === 'object') {
                var mappedO = [];
                jQuery.each(fv.online.mapped, function(k, v) { mappedO.push(k + ' → ' + v); });
                if (mappedO.length) parts.push('<strong>Tabel online (jurnal_kas):</strong> ' + mappedO.join(', '));
            }
            if (fv.online.missing_fields && fv.online.missing_fields.length) {
                parts.push('<strong>Online kolom tidak ada:</strong> ' + fv.online.missing_fields.join(', '));
            }
        }
        return parts.join('<br/>');
    }
    function showWarnings(warnings) {
        var $w = jQuery('#compare-jurnal-kas-warnings');
        if (!warnings || !warnings.length) { $w.addClass('d-none').empty(); return; }
        $w.removeClass('d-none').html('<strong><i class="fas fa-exclamation-triangle"></i> Peringatan:</strong><ul class="mb-0 pl-3">'
            + warnings.map(function(w) { return '<li>' + jQuery('<span>').text(w).html() + '</li>'; }).join('') + '</ul>');
    }
    function showFieldInfo(fv) {
        var html = formatFieldValidation(fv);
        var $f = jQuery('#compare-jurnal-kas-field-info');
        if (!html) { $f.addClass('d-none').empty(); return; }
        $f.removeClass('d-none').html('<strong><i class="fas fa-info-circle"></i> Mapping kolom compare:</strong><br/>' + html);
    }
    function updateInfo(res) {
        res = res || lastResult || {};
        var s = res.stats || {};
        jQuery('#compare-jurnal-kas-label-bulan').text(res.bulan_label || bulanKey() || '—');
        jQuery('#compare-jurnal-kas-label-tabel').text(res.table || jQuery('#compare_tabel_jurnal_kas').val() || '—');
        jQuery('#compare-jurnal-kas-count-manual').text(s.data_manual != null ? s.data_manual : '—');
        jQuery('#compare-jurnal-kas-count-online').text(s.data_online != null ? s.data_online : '—');
        jQuery('#compare-jurnal-kas-count-cocok').text(s.data_cocok != null ? s.data_cocok : '—');
        jQuery('#compare-jurnal-kas-count-manual-miss').text(s.manual_tidak_di_online != null ? s.manual_tidak_di_online : '—');
        jQuery('#compare-jurnal-kas-count-online-miss').text(s.online_tidak_di_manual != null ? s.online_tidak_di_manual : '—');
        jQuery('#compare-jurnal-kas-badge-manual').text(s.data_manual || 0);
        jQuery('#compare-jurnal-kas-badge-online').text(s.data_online || 0);
        jQuery('#compare-jurnal-kas-badge-cocok').text(s.data_cocok || 0);
        jQuery('#compare-jurnal-kas-badge-manual-miss').text(s.manual_tidak_di_online || 0);
        jQuery('#compare-jurnal-kas-badge-online-miss').text(s.online_tidak_di_manual || 0);
        showFieldInfo(res.field_validation || null);
        showWarnings(res.warnings || []);
    }
    function loadTableList(force, selectTable) {
        if (tablesLoaded && !force) {
            if (selectTable) jQuery('#compare_tabel_jurnal_kas').val(selectTable);
            toggleBtns(); return;
        }
        jQuery.ajax({ url: urlList, type: 'POST', dataType: 'json' }).done(function(res) {
            if (!res || !res.ok) { setStatus('danger', (res && res.message) || 'Gagal memuat daftar tabel.'); return; }
            var $sel = jQuery('#compare_tabel_jurnal_kas');
            var cur = selectTable || $sel.val();
            $sel.find('option:not(:first)').remove();
            (res.tables || []).forEach(function(tbl) { $sel.append(jQuery('<option>', { value: tbl, text: tbl })); });
            if (cur) $sel.val(cur);
            tablesLoaded = true;
        }).always(toggleBtns);
    }
    function runCompare() {
        var bk = bulanKey(), tbl = jQuery('#compare_tabel_jurnal_kas').val() || '';
        if (!bk || !tbl) { alert('Pilih bulan, tahun, dan tabel database.'); return; }
        if (typeof Swal !== 'undefined') {
            Swal.fire({ title: 'Memproses Compare...', html: 'Membandingkan data manual vs online jurnal_kas', allowOutsideClick: false, didOpen: function() { Swal.showLoading(); } });
        }
        setStatus('info', '<i class="fas fa-spinner fa-spin"></i> Membandingkan...');
        jQuery('#compare-jurnal-kas-results-panel').addClass('d-none');
        jQuery('#btn-compare-jurnal-kas-excel-all').addClass('d-none');
        jQuery.ajax({
            url: urlRun, type: 'POST', dataType: 'json',
            data: { bulan: bk, bulan_num: jQuery('#compare_bulan_jurnal_kas').val(), tahun: jQuery('#compare_tahun_jurnal_kas').val(), tabel: tbl }
        }).done(function(res) {
            if (typeof Swal !== 'undefined') Swal.close();
            if (!res || !res.ok) {
                var msg = (res && res.message) || 'Compare gagal.';
                if (res && res.field_validation) {
                    showFieldInfo(res.field_validation);
                    var fvHtml = formatFieldValidation(res.field_validation);
                    if (fvHtml) msg += '<br/><br/>' + fvHtml;
                }
                setStatus('danger', msg);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Compare Gagal', html: msg });
                }
                return;
            }
            lastResult = res; renderAll(res); updateInfo(res);
            jQuery('#compare-jurnal-kas-results-panel').removeClass('d-none');
            jQuery('#btn-compare-jurnal-kas-excel-all').removeClass('d-none');
            var okMsg = '<i class="fas fa-check-circle"></i> Compare selesai. Manual: ' + (res.stats.data_manual || 0)
                + ' baris, Online: ' + (res.stats.data_online || 0) + ' baris, Cocok: ' + (res.stats.data_cocok || 0) + '.';
            if (res.stats.manual_unprocessed > 0 || res.stats.online_unprocessed > 0) {
                okMsg += ' (Manual tidak terproses: ' + (res.stats.manual_unprocessed || 0)
                    + ', Online tidak terproses: ' + (res.stats.online_unprocessed || 0) + ')';
            }
            setStatus('success', okMsg);
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'success', title: 'Compare Selesai', text: 'Data berhasil dibandingkan. Tombol Cetak ke Excel sudah tersedia.', timer: 2500, showConfirmButton: false });
            }
            jQuery('html, body').animate({ scrollTop: jQuery('#compare-jurnal-kas-results-panel').offset().top - 80 }, 400);
        }).fail(function() {
            if (typeof Swal !== 'undefined') Swal.close();
            setStatus('danger', 'Tidak dapat menghubungi server.');
        });
    }
    function exportExcel() {
        var bk = bulanKey(), tbl = jQuery('#compare_tabel_jurnal_kas').val() || '';
        if (!bk || !tbl) { alert('Pilih bulan, tahun, dan tabel.'); return; }
        var f = jQuery('<form method="post" target="_blank"></form>');
        f.attr('action', urlExcel);
        f.append(jQuery('<input type="hidden" name="bulan">').val(bk));
        f.append(jQuery('<input type="hidden" name="bulan_num">').val(jQuery('#compare_bulan_jurnal_kas').val()));
        f.append(jQuery('<input type="hidden" name="tahun">').val(jQuery('#compare_tahun_jurnal_kas').val()));
        f.append(jQuery('<input type="hidden" name="tabel">').val(tbl));
        jQuery('body').append(f); f.submit(); f.remove();
    }
    function importCsv(file) {
        if (!file || csvBusy) return;
        if ((file.name || '').split('.').pop().toLowerCase() !== 'csv') {
            if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Format Salah', text: 'File harus berformat .csv' });
            else alert('File harus .csv');
            return;
        }
        csvBusy = true;
        jQuery('#compare_jurnal_kas_csv_file').prop('disabled', true);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Memproses CSV...',
                html: 'Menyimpan file <strong>' + jQuery('<span>').text(file.name).html() + '</strong> ke database sebagai tabel baru.<br/><small>Membuat tabel, kolom id AUTO_INCREMENT, normalisasi tanggal/debet/kredit...</small>',
                allowOutsideClick: false,
                didOpen: function() { Swal.showLoading(); }
            });
        }
        var ref = { bulan: parseInt(jQuery('#compare_bulan_jurnal_kas').val(), 10), tahun: parseInt(jQuery('#compare_tahun_jurnal_kas').val(), 10) };
        var fd = new FormData();
        fd.append('csv_file', file);
        fd.append('bulan_num', ref.bulan); fd.append('tahun', ref.tahun);
        fd.append('bulan', ref.tahun + '-' + String(ref.bulan).padStart(2, '0'));
        jQuery.ajax({ url: urlImport, type: 'POST', data: fd, processData: false, contentType: false, dataType: 'json' })
        .done(function(res) {
            csvBusy = false;
            jQuery('#compare_jurnal_kas_csv_file').prop('disabled', false).val('');
            jQuery('#compare_jurnal_kas_csv_file').next('.custom-file-label').text('Cari / pilih file CSV...');
            if (!res || !res.ok) {
                if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Import Gagal', html: (res && res.message) ? String(res.message).replace(/\n/g, '<br/>') : 'Import gagal.' });
                else setStatus('danger', (res && res.message) ? String(res.message).replace(/\n/g, '<br/>') : 'Import gagal.');
                return;
            }
            csvLast = res;
            jQuery('#compare-jurnal-kas-csv-filename').text(res.file || '—');
            jQuery('#compare-jurnal-kas-csv-tablename').text(res.table || '—');
            jQuery('#compare-jurnal-kas-csv-rowcount').text(res.rows ? (' (' + res.rows + ' baris)') : '');
            jQuery('#compare-jurnal-kas-csv-upload-info').removeClass('d-none');
            loadTableList(true, res.table);
            setStatus('success', 'Tabel <strong>' + (res.table || '') + '</strong> berhasil dibuat (' + (res.rows || 0) + ' baris).');
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Import CSV Berhasil',
                    html: 'Tabel <strong>' + (res.table || '') + '</strong> dibuat dengan <strong>' + (res.rows || 0) + '</strong> baris.<br/>Silakan klik <strong>Compare</strong>.',
                    confirmButtonText: 'OK'
                });
            }
        }).fail(function() {
            csvBusy = false;
            jQuery('#compare_jurnal_kas_csv_file').prop('disabled', false);
            if (typeof Swal !== 'undefined') Swal.fire({ icon: 'error', title: 'Import Gagal', text: 'Tidak dapat menghubungi server.' });
            else setStatus('danger', 'Import CSV gagal.');
        });
    }
    jQuery('#compare_jurnal_kas_csv_file').on('change', function() {
        var f = this.files && this.files[0];
        if (f) { jQuery(this).next('.custom-file-label').text(f.name); importCsv(f); }
    });
    jQuery('#compare_bulan_jurnal_kas, #compare_tahun_jurnal_kas, #compare_tabel_jurnal_kas').on('change', toggleBtns);
    jQuery('#btn-compare-jurnal-kas').on('click', runCompare);
    jQuery('#btn-compare-jurnal-kas-excel-all').on('click', exportExcel);
    jQuery('#tab-compare-jurnal-kas').on('shown.bs.tab', function() { loadTableList(false); });
    jQuery('#btn-compare-jurnal-kas-csv-cek-data').on('click', function() {
        var tbl = (csvLast && csvLast.table) || jQuery('#compare_tabel_jurnal_kas').val();
        if (!tbl) { alert('Belum ada tabel.'); return; }
        jQuery('#compare-jurnal-kas-csv-preview-meta').text('Tabel: ' + tbl);
        jQuery('#modal-compare-jurnal-kas-csv-preview').modal('show');
        jQuery.ajax({ url: urlPreview, type: 'POST', dataType: 'json', data: { tabel: tbl, limit: 500 } })
        .done(function(res) {
            if (!res || !res.ok) { jQuery('#compare-jurnal-kas-csv-preview-meta').text((res && res.message) || 'Gagal preview.'); return; }
            var cols = res.columns || [];
            var $thead = jQuery('#table-compare-jurnal-kas-csv-preview thead tr').empty();
            cols.forEach(function(c) { $thead.append(jQuery('<th>').text(c)); });
            var rows = (res.rows || []).map(function(r) { return cols.map(function(c) { return r[c] != null ? r[c] : ''; }); });
            var $t = jQuery('#table-compare-jurnal-kas-csv-preview');
            if (jQuery.fn.DataTable.isDataTable($t)) $t.DataTable().destroy();
            $t.DataTable({ data: rows, scrollX: true, pageLength: 25 });
        });
    });
    if (jQuery('#tab-compare-jurnal-kas').hasClass('active')) loadTableList(false);
    toggleBtns();
});
</script>