<link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
<style type="text/css">
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
</style>

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
        // echo $date_awal; 
        // echo "<br/>";

        $Get_month_from_date = $month_selected;
        $Get_year_Tahun_ini = $year_selected;
        $Get_year_Setahun_lalu = date("Y", strtotime('-1 year'));




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

        ?>


        <div class="box box-warning box-solid">

            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">

                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-4" align="left">
                                        <div class="col-12" text-align="center"> <strong>JURNAL PEMBELIAN</strong> <?php echo bulan_teks($Get_month_from_date) . " " . $Get_year_Tahun_ini ?></div>
                                    </div>

                                    <div class="col-6" align="left">




                                        <?php
                                        $action_cari_between_date = site_url('Tbl_pembelian/jurnal_pembelian2');
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
                                    <div class="col-2" align="right">

                                        <?php //echo anchor(site_url('jurnal_kas/excel'), 'Cetak ke Excel', 'class="btn btn-success"'); 
                                        ?>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>



                    <div class="card-body">

                        <!-- <table id="tglSPOPFreeze" class="table table-striped dt-responsive w-100 table-bordered display nowrap table-hover mb-0" style="width:100%"> -->
                        <table id="tglSPOPFreeze" class="display nowrap" style="width:100%">
                            <thead>
                                <!-- <tr>

                                    <th rowspan="3" style="text-align:left" width="10px">Tanggal</th>
                                    <th rowspan="3" style="text-align:center">Kode Akun</th>
                                    <th rowspan="3" style="text-align:center">No. Bukti BKM</th>
                                    <th rowspan="3" style="text-align:center">PL</th>
                                    <th rowspan="3" style="text-align:center">KETERANGAN</th>

                                    <th colspan="3" style="text-align:center">Debet</th>


                                    <th colspan="1" style="text-align:center">Kredit</th>
                                </tr>
                                <tr>
                                    <th rowspan="2" style="text-align:center">21101-UU Dagang</th>
                                    <th colspan="2" style="text-align:center">Serba-Serbi</th>
                                    <th rowspan="2" style="text-align:right">11101-Kas Besar</th>
                                </tr>


                                <tr>
                                    <th rowspan="2" style="text-align:left">No. Rek</th>
                                    <th rowspan="2" style="text-align:right">Jumlah</th>

                                </tr>
                                 -->
                                <!-- ============================================ -->

                                <tr>

                                    <th rowspan="2" style="text-align:left" width="10px">No</th>
                                    <th rowspan="2" style="text-align:left" width="10px">TANGGAL</th>
                                    <th rowspan="2" style="text-align:center">No. SPOP</th>
                                    <!-- <th rowspan="3" style="text-align:center">VVVVVVV</th> -->
                                    <th rowspan="2" style="text-align:center">PL</th>
                                    <th rowspan="2" style="text-align:center">SUPPLIER</th>

                                    <th colspan="3" style="text-align:center">DEBET</th>
                                    <th colspan="1" style="text-align:center">KREDIT</th>

                                </tr>
                                <tr>
                                    <th style="text-align:left">No. Rek</th>
                                    <th style="text-align:left">Rekening</th>
                                    <th style="text-align:right">Jumlah</th>
                                    <th style="text-align:center">21101-UU</th>
                                </tr>
                                <!-- <tr>
                                    <th style="text-align:left">21101-UU</th>
                                    <th style="text-align:right">Jumlah</th>
                                </tr> -->

                            </thead>
                            <tbody>
                                <?php
                                $start = 0;
                                $TOTAL_21101 = 0;
                                $TOTAL_debet_jumlah = 0;
                                $TOTAL_debet_ALL = 0;
                                $TOTAL_kredit_21101 = 0;
                                $TOTAL_kredit_ALL = 0;


                                $TOTAL_saldo = 0;
                                $GET_KODE_PL = "";

                                foreach ($Buku_besar_DATA_data as $list_data) {




                                    // BARIS KE 1 ----- PERTAMA

                                    if ($list_data->kode_akun <> 21101) {

                                        // CEK dengan SPOP yang sama
                                        // // $Get_kode_akun = 21101;
                                        // $this->db->where('kode_akun', $list_data->kode_akun);
                                        // // $this->db->where('uuid_spop', $list_data->uuid_spop);
                                        // $GET_debet_buku_besar_by_kode_akun_by_spop = $this->db->get('sys_kode_akun')->row()->debet;


                                        $Get_kode_akun = 21101;
                                        $this->db->where('kode_akun', $Get_kode_akun);
                                        $this->db->where('uuid_spop', $list_data->uuid_spop);
                                        $GET_DATA_kode_akun_kredit = $this->db->get('buku_besar')->row()->kredit;


                                        if ($start <= 0) {
                                            // $start=++$start;
                                ?>
                                            <!-- BARIS KE 1 -->
                                            <tr>
                                                <td align="left">
                                                    <?php
                                                    echo ++$start;
                                                    ?>
                                                </td>

                                                <td align="left">
                                                    <?php
                                                    echo date("d-m-Y", strtotime($list_data->tanggal));
                                                    ?>
                                                </td>

                                                <!-- SPOP -->
                                                <td align="left">
                                                    <?php
                                                    echo $list_data->spop;
                                                    ?>
                                                </td>

                                                <td align="left">
                                                    <?php
                                                    echo $list_data->pl;
                                                    ?>
                                                </td>

                                                <!-- SUPPLIER -->
                                                <td align="left">
                                                    <?php
                                                    echo $list_data->supplier;
                                                    ?>
                                                </td>

                                                <!-- Kode Akun -->
                                                <td align="left">
                                                    <?php
                                                    echo $list_data->kode_akun;
                                                    ?>
                                                </td>

                                                <!-- Kode Akun -->
                                                <td align="left">
                                                    <?php
                                                    echo $list_data->nama_akun;
                                                    ?>
                                                </td>

                                                <!-- Jumlah Debet -->
                                                <td align="right">
                                                    <?php
                                                    // echo $list_data->debet;
                                                    echo "<font color='black'>" . number_format($list_data->debet, 2, ',', '.') . "</font>";
                                                    $TOTAL_debet_jumlah = $TOTAL_debet_jumlah + $list_data->debet;
                                                    ?>
                                                </td>


                                                <!-- Jumlah Kredit -->
                                                <td align="right">
                                                    <?php
                                                    // echo $GET_DATA_kode_akun_kredit;
                                                    echo "<font color='black'>" . number_format($GET_DATA_kode_akun_kredit, 2, ',', '.') . "</font>";
                                                    $TOTAL_kredit_21101 = $TOTAL_kredit_21101 + $GET_DATA_kode_akun_kredit;
                                                    ?>
                                                </td>



                                            </tr>

                                            <?php
                                            $GET_KODE_PL = $list_data->pl; // UNTUK PENGELOMPOKAN PL , DAN MENJUMLAHKAN PER PL

                                            // <!-- END OF BARIS KE 1 -->

                                        } else {

                                            // BARIS KE 2 -------- KEDUA
                                            // CEK PL , JIKA SAMA MAKA TAMPILKAN LIST DATA , JIKA PL BEDA ==> BUAT BARIS TOTAL PER PL DAN LIST DATA PL BARU

                                            if ($list_data->pl == $GET_KODE_PL) {
                                                // PL SAMA ==> MAKA LIST DATA BARU

                                            ?>


                                                <?php
                                                if ($list_data->kode_akun <> 21101) {
                                                ?>
                                                    <tr>
                                                        <td align="left">
                                                            <?php
                                                            echo ++$start;
                                                            ?>
                                                        </td>

                                                        <td align="left">
                                                            <?php
                                                            echo date("d-m-Y", strtotime($list_data->tanggal));
                                                            ?>
                                                        </td>

                                                        <!-- SPOP -->
                                                        <td align="left">
                                                            <?php
                                                            echo $list_data->spop;
                                                            ?>
                                                        </td>

                                                        <td align="left">
                                                            <?php
                                                            echo $list_data->pl;
                                                            ?>
                                                        </td>

                                                        <!-- SUPPLIER -->
                                                        <td align="left">
                                                            <?php
                                                            echo $list_data->supplier;
                                                            ?>
                                                        </td>

                                                        <!-- Kode Akun -->
                                                        <td align="left">
                                                            <?php
                                                            echo $list_data->kode_akun;
                                                            ?>
                                                        </td>

                                                        <!-- Kode Akun -->
                                                        <td align="left">
                                                            <?php
                                                            echo $list_data->nama_akun;
                                                            ?>
                                                        </td>

                                                        <!-- Jumlah Debet -->
                                                        <td align="right">
                                                            <?php
                                                            // echo $list_data->debet;
                                                            echo "<font color='black'>" . number_format($list_data->debet, 2, ',', '.') . "</font>";
                                                            $TOTAL_debet_jumlah = $TOTAL_debet_jumlah + $list_data->debet;
                                                            ?>
                                                        </td>


                                                        <!-- Jumlah Kredit -->
                                                        <td align="right">
                                                            <?php
                                                            // echo $GET_DATA_kode_akun_kredit;
                                                            echo "<font color='black'>" . number_format($GET_DATA_kode_akun_kredit, 2, ',', '.') . "</font>";
                                                            $TOTAL_kredit_21101 = $TOTAL_kredit_21101 + $GET_DATA_kode_akun_kredit;
                                                            ?>
                                                        </td>



                                                    </tr>

                                                <?php
                                                } //if ($list_data->kode_akun <> 21101)

                                                ?>


                                            <?php
                                            } else {
                                                // PL BEDA ==> BUAT BARIS TOTAL PER PL DAN LIST DATA BARU DENGAN PL BERBEDA/SELANJUTNYA

                                                // BARIS TOTAL =================
                                            ?>

                                                <tr style="background-color:yellow;">
                                                    <td align="left">
                                                        <?php
                                                        echo ++$start;
                                                        ?>
                                                    </td>

                                                    <td align="left">
                                                        <?php
                                                        // echo "TOTAL";
                                                        ?>
                                                    </td>

                                                    <!-- SPOP -->
                                                    <td align="left">
                                                        <?php
                                                        // echo $list_data->spop;
                                                        ?>
                                                    </td>

                                                    <td align="left">
                                                        <?php
                                                        // echo $list_data->pl;
                                                        ?>
                                                    </td>

                                                    <!-- SUPPLIER -->
                                                    <td align="left">
                                                        <?php
                                                        // echo $list_data->supplier;
                                                        if ($GET_KODE_PL) {
                                                            $this->db->where('kode_pl', $GET_KODE_PL);
                                                            $GET_DATA_nama_PL = $this->db->get('sys_kode_pl')->row()->nama_akun;

                                                            echo "Total Pembelian Unit " . $GET_DATA_nama_PL;
                                                        } else {
                                                            echo "";
                                                        }
                                                        ?>
                                                    </td>

                                                    <!-- Kode Akun -->
                                                    <td align="left">
                                                        <?php
                                                        // echo $list_data->kode_akun;
                                                        ?>
                                                    </td>

                                                    <!-- Kode Akun -->
                                                    <td align="left">
                                                        <?php
                                                        // echo $list_data->nama_akun;
                                                        // echo "TOTAL";
                                                        ?>
                                                    </td>

                                                    <!-- Jumlah Debet -->
                                                    <td align="right">

                                                        <?php
                                                        // echo $TOTAL_debet_jumlah;
                                                        // $TOTAL_debet_jumlah = $TOTAL_debet_jumlah + $list_data->debet;
                                                        echo "<font color='blue'><strong>" . number_format($TOTAL_debet_jumlah, 2, ',', '.') . "</strong></font>";

                                                        $TOTAL_debet_ALL = $TOTAL_debet_ALL + $TOTAL_debet_jumlah;

                                                        ?>

                                                    </td>


                                                    <!-- Jumlah Kredit -->
                                                    <td align="right">

                                                        <?php
                                                        // echo $TOTAL_kredit_21101;
                                                        // $TOTAL_kredit_21101 = $TOTAL_kredit_21101 + $GET_DATA_kode_akun_kredit;
                                                        echo "<font color='blue'><strong>" . number_format($TOTAL_kredit_21101, 2, ',', '.') . "</strong></font>";

                                                        $TOTAL_kredit_ALL = $TOTAL_kredit_ALL + $TOTAL_kredit_21101;

                                                        ?>

                                                    </td>



                                                </tr>
                                                <?php
                                                $TOTAL_debet_jumlah = 0;
                                                $TOTAL_kredit_21101 = 0;

                                                ?>
                                                <!-- // END OF BARIS TOTAL ================= -->



                                                <!-- ---------------------------------------------------------------------------------------------- -->


                                                <!-- // BARIS DATA PL BARU -->
                                                <tr>
                                                    <td align="left">
                                                        <?php
                                                        echo ++$start;
                                                        ?>
                                                    </td>

                                                    <td align="left">
                                                        <?php
                                                        echo date("d-m-Y", strtotime($list_data->tanggal));
                                                        ?>
                                                    </td>

                                                    <!-- SPOP -->
                                                    <td align="left">
                                                        <?php
                                                        echo $list_data->spop;
                                                        ?>
                                                    </td>

                                                    <td align="left">
                                                        <?php
                                                        echo $list_data->pl;
                                                        ?>
                                                    </td>

                                                    <!-- SUPPLIER -->
                                                    <td align="left">
                                                        <?php
                                                        echo $list_data->supplier;
                                                        ?>
                                                    </td>

                                                    <!-- Kode Akun -->
                                                    <td align="left">
                                                        <?php
                                                        echo $list_data->kode_akun;
                                                        ?>
                                                    </td>

                                                    <!-- Kode Akun -->
                                                    <td align="left">
                                                        <?php
                                                        echo $list_data->nama_akun;
                                                        ?>
                                                    </td>

                                                    <!-- Jumlah Debet -->
                                                    <td align="right">
                                                        <?php
                                                        // echo $list_data->debet;
                                                        echo "<font color='black'>" . number_format($list_data->debet, 2, ',', '.') . "</font>";
                                                        $TOTAL_debet_jumlah = $TOTAL_debet_jumlah + $list_data->debet;
                                                        ?>
                                                    </td>


                                                    <!-- Jumlah Kredit -->
                                                    <td align="right">
                                                        <?php
                                                        // echo $GET_DATA_kode_akun_kredit;
                                                        echo "<font color='black'>" . number_format($GET_DATA_kode_akun_kredit, 2, ',', '.') . "</font>";
                                                        $TOTAL_kredit_21101 = $TOTAL_kredit_21101 + $GET_DATA_kode_akun_kredit;
                                                        ?>
                                                    </td>



                                                </tr>

                                                <?php
                                                $GET_KODE_PL = $list_data->pl; // UNTUK PENGELOMPOKAN PL , DAN MENJUMLAHKAN PER PL
                                                ?>
                                                <!-- // END OF BARIS DATA PL BARU -->
                                <?php
                                            }
                                        }
                                    }
                                }
                                ?>

                                <!-- BARIS TOTAL TERAKHIR -->
                                <tr style="background-color:yellow;">
                                    <td align="left">
                                        <?php
                                        echo ++$start;
                                        ?>
                                    </td>

                                    <td align="left">
                                        <?php
                                        // echo "TOTAL";
                                        ?>
                                    </td>

                                    <!-- SPOP -->
                                    <td align="left">
                                        <?php
                                        // echo $list_data->spop;
                                        ?>
                                    </td>

                                    <td align="left">
                                        <?php
                                        // echo $list_data->pl;
                                        ?>
                                    </td>

                                    <!-- SUPPLIER -->
                                    <td align="left">
                                        <?php
                                        // echo $list_data->supplier;
                                        $this->db->where('kode_pl', $GET_KODE_PL);
                                        $GET_DATA_nama_PL = $this->db->get('sys_kode_pl')->row()->keterangan;

                                        echo "Total Pembelian Unit " . $GET_DATA_nama_PL;
                                        ?>
                                    </td>

                                    <!-- Kode Akun -->
                                    <td align="left">
                                        <?php
                                        // echo $list_data->kode_akun;
                                        ?>
                                    </td>

                                    <!-- Kode Akun -->
                                    <td align="left">
                                        <?php
                                        // echo $list_data->nama_akun;
                                        // echo "TOTAL";
                                        ?>
                                    </td>

                                    <!-- Jumlah Debet -->
                                    <td align="right">

                                        <?php
                                        // echo $TOTAL_debet_jumlah;
                                        // $TOTAL_debet_jumlah = $TOTAL_debet_jumlah + $list_data->debet;
                                        echo "<font color='blue'><strong>" . number_format($TOTAL_debet_jumlah, 2, ',', '.') . "</strong></font>";

                                        $TOTAL_debet_ALL = $TOTAL_debet_ALL + $TOTAL_debet_jumlah;

                                        ?>

                                    </td>


                                    <!-- Jumlah Kredit -->
                                    <td align="right">

                                        <?php
                                        // echo $TOTAL_kredit_21101;
                                        // $TOTAL_kredit_21101 = $TOTAL_kredit_21101 + $GET_DATA_kode_akun_kredit;
                                        echo "<font color='blue'><strong>" . number_format($TOTAL_kredit_21101, 2, ',', '.') . "</strong></font>";

                                        $TOTAL_kredit_ALL = $TOTAL_kredit_ALL + $TOTAL_kredit_21101;

                                        ?>

                                    </td>



                                </tr>
                                <!-- END OF BARIS TOTAL TERAKHIR -->

                            </tbody>


                            <tfoot>
                                <tr>

                                    <th style="text-align:left" width="10px"></th>
                                    <th style="text-align:center"></th>
                                    <th style="text-align:center"></th>
                                    <!-- <th style="text-align:center"></th> -->
                                    <th style="text-align:center"></th>
                                    <th style="text-align:center"></th>


                                    <th style="text-align:right">
                                        <?php
                                        //echo "<font color='blue'><strong>" . number_format($TOTAL_21101_SEMUA + $TOTAL_debet_jumlah_SEMUA, 2, ',', '.') . "</strong></font>";
                                        ?>
                                    </th> <!-- TOTAL DEBET -->
                                    <th style="text-align:right">
                                        <?php
                                        // echo number_format($TOTAL_kredit_11301_SEMUA, 2, ',', '.');
                                        ?>
                                    </th>

                                    <!-- TOTAL DEBET JUMLAH -->
                                    <th style="text-align:right">
                                        <?php
                                        echo "<font color='blue'><strong>" . number_format($TOTAL_debet_ALL, 2, ',', '.') . "</strong></font>";
                                        ?>
                                    </th>
                                    <th style="text-align:right">
                                        <?php
                                        echo "<font color='blue'><strong>" . number_format($TOTAL_kredit_ALL, 2, ',', '.') . "</strong></font>";
                                        ?>
                                    </th> <!-- TOTAL JUMLAH -->

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
            "scrollY": 450,
            "scrollX": true
        });
    });
</script>