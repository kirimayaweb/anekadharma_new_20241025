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



        <div class="box box-warning box-solid">

            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">

                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-4" align="left">
                                        <div class="col-12" text-align="center"> <strong>JURNAL PENGELUARAN KAS</strong></div>
                                    </div>
                                    <div class="col-2" align="left">
                                        <?php //echo anchor(site_url('jurnal_kas/pemasukan_kas'), 'Pemasukan Kas', 'class="btn btn-danger"');
                                        ?>

                                    </div>
                                    <div class="col-2" align="left">

                                        <?php //echo anchor(site_url('jurnal_kas/pengeluaran_kas'), 'Pengeluaran Kas', 'class="btn btn-success"');
                                        ?>
                                    </div>
                                    <div class="col-4" align="right">

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

                                    <th rowspan="3" style="text-align:left" width="10px">No</th>
                                    <th rowspan="3" style="text-align:left" width="10px">Tanggal</th>
                                    <th rowspan="3" style="text-align:center">Kode Akun</th>
                                    <th rowspan="3" style="text-align:center">No. Bukti BKM</th>
                                    <th rowspan="3" style="text-align:center">PL</th>
                                    <th rowspan="3" style="text-align:center">KETERANGAN</th>
                                    <th colspan="3" style="text-align:center">DEBET</th>
                                    <th colspan="1" style="text-align:center">KREDIT</th>

                                </tr>
                                <tr>
                                    <th rowspan="2" style="text-align:center">21101-UU Dagang</th>
                                    <th colspan="2" style="text-align:center">serba-serbi</th>
                                    <th rowspan="2" style="text-align:right">11101-Kas Besar</th>
                                </tr>
                                <tr>
                                    <th style="text-align:left">No. Rek</th>
                                    <th style="text-align:right">Jumlah</th>
                                </tr>

                            </thead>
                            <tbody>
                                <?php
                                $start = 0;
                                $TOTAL_21101 = 0;
                                $TOTAL_debet_jumlah = 0;
                                $TOTAL_kredit_11101 = 0;
                                $TOTAL_21101_SEMUA = 0;
                                $TOTAL_debet_jumlah_SEMUA = 0;
                                $TOTAL_kredit_11101_SEMUA = 0;
                                $TOTAL_saldo = 0;
                                foreach ($Data_kas as $list_data) {

                                ?>

                                    <?php
                                    // Baris pertama start=1
                                    $First_row = ++$start;
                                    if ($First_row == 1) {
                                        // Baris pertama
                                    ?>


                                        <tr>
                                            <td>
                                                <?php
                                                echo $First_row;
                                                ?>
                                            </td>

                                            <td>
                                                <?php
                                                echo date("d-m-Y", strtotime($list_data->tanggal));
                                                ?>
                                            </td>

                                            <!-- Kode Akun -->
                                            <td><?php
                                                // echo "Kode Akun";
                                                if ($list_data->kode_akun) {
                                                    echo $list_data->kode_akun;
                                                    echo "<br/>";
                                                    echo anchor(site_url('Jurnal_kas/ubah_kode_akun_pengeluaran/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH KODE AKUN</i>', 'class="btn btn-warning btn-xs"');
                                                } else {
                                                    echo anchor(site_url('Jurnal_kas/ubah_kode_akun_pengeluaran/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">INPUT KODE AKUN</i>', 'class="btn btn-danger btn-xs"');
                                                }
                                                ?>
                                            </td>

                                            <td>
                                                <?php
                                                echo $list_data->bukti;
                                                ?>
                                            </td>

                                            <td>
                                                <?php
                                                echo $list_data->pl;
                                                ?>
                                            </td>

                                            <td align="left">
                                                <?php
                                                echo $list_data->keterangan;
                                                ?>
                                            </td>


                                            <!-- 21101-UU Dagang -->
                                            <td style="text-align:right">
                                                <?php
                                                if ($list_data->kode_akun == "21101") {
                                                    echo number_format($list_data->kredit, 2, ',', '.');
                                                    $TOTAL_21101 = $TOTAL_21101 + $list_data->kredit;
                                                    $TOTAL_21101_SEMUA = $TOTAL_21101_SEMUA + $list_data->kredit;
                                                } else {
                                                    echo "";
                                                }
                                                ?>
                                            </td>

                                            <!-- Serbi-Serbi No. Rek -->
                                            <td>
                                                <?php
                                                // echo "no rek";
                                                ?>
                                            </td>

                                            <!-- Serbi-Serbi Jumlah -->
                                            <td style="text-align:right">
                                                <?php
                                                if ($list_data->kode_akun == "21101") {
                                                    echo "";
                                                } else {
                                                    echo number_format($list_data->kredit, 2, ',', '.');
                                                    $TOTAL_debet_jumlah = $TOTAL_debet_jumlah + $list_data->kredit;
                                                    $TOTAL_debet_jumlah_SEMUA = $TOTAL_debet_jumlah_SEMUA + $list_data->kredit;
                                                }

                                                ?>
                                            </td>

                                            <!-- Kredit 11101-Kas Besar -->
                                            <td style="text-align:right">
                                                <?php
                                                if ($list_data->kredit > 0) {
                                                    echo number_format($list_data->kredit, 2, ',', '.');
                                                    $TOTAL_kredit_11101 = $TOTAL_kredit_11101 + $list_data->kredit;
                                                    $TOTAL_kredit_11101_SEMUA = $TOTAL_kredit_11101_SEMUA + $list_data->kredit;
                                                } else {
                                                    echo "";
                                                }
                                                ?>
                                            </td>


                                        </tr>


                                        <?php

                                        // Simpan data pl untuk referensi baris selanjutnya
                                        $PL_Data = $list_data->pl;
                                    } else {

                                        // Cek apakah pl sama ?
                                        if ($PL_Data == $list_data->pl) {
                                            // Jika sama maka tampilkan list data

                                        ?>
                                            <!-- BARIS  2 DST YANG MASIH PL SAMA -->
                                            <tr>
                                                <td>
                                                    <?php
                                                    echo ++$start;
                                                    ?>
                                                </td>

                                                <td>
                                                    <?php
                                                    echo date("d-m-Y", strtotime($list_data->tanggal));
                                                    ?>
                                                </td>

                                                <!-- Kode Akun -->
                                                <td><?php
                                                    // echo "Kode Akun";
                                                    if ($list_data->kode_akun) {
                                                        echo $list_data->kode_akun;
                                                        echo "<br/>";
                                                        echo anchor(site_url('Jurnal_kas/ubah_kode_akun_pengeluaran/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH KODE AKUN</i>', 'class="btn btn-warning btn-xs"');
                                                    } else {
                                                        echo anchor(site_url('Jurnal_kas/ubah_kode_akun_pengeluaran/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">INPUT KODE AKUN</i>', 'class="btn btn-danger btn-xs"');
                                                    }
                                                    ?>
                                                </td>

                                                <td>
                                                    <?php
                                                    echo $list_data->bukti;
                                                    ?>
                                                </td>

                                                <td>
                                                    <?php
                                                    echo $list_data->pl;
                                                    ?>
                                                </td>

                                                <td align="left">
                                                    <?php
                                                    echo $list_data->keterangan;
                                                    ?>
                                                </td>


                                                <!-- 21101-UU Dagang -->
                                                <td style="text-align:right">
                                                    <?php
                                                    if ($list_data->kode_akun == "21101") {
                                                        echo number_format($list_data->kredit, 2, ',', '.');
                                                        $TOTAL_21101 = $TOTAL_21101 + $list_data->kredit;
                                                        $TOTAL_21101_SEMUA = $TOTAL_21101_SEMUA + $list_data->kredit;
                                                    } else {
                                                        echo "";
                                                    }
                                                    ?>
                                                </td>

                                                <!-- Serbi-Serbi No. Rek -->
                                                <td>
                                                    <?php
                                                    // echo "no rek";
                                                    ?>
                                                </td>

                                                <!-- Serbi-Serbi Jumlah -->
                                                <td style="text-align:right">
                                                    <?php
                                                    if ($list_data->kode_akun == "21101") {
                                                        echo "";
                                                    } else {
                                                        echo number_format($list_data->kredit, 2, ',', '.');
                                                        $TOTAL_debet_jumlah = $TOTAL_debet_jumlah + $list_data->kredit;
                                                        $TOTAL_debet_jumlah_SEMUA = $TOTAL_debet_jumlah_SEMUA + $list_data->kredit;
                                                    }

                                                    ?>
                                                </td>

                                                <!-- Kredit 11101-Kas Besar -->
                                                <td style="text-align:right">
                                                    <?php
                                                    if ($list_data->kredit > 0) {
                                                        echo number_format($list_data->kredit, 2, ',', '.');
                                                        $TOTAL_kredit_11101 = $TOTAL_kredit_11101 + $list_data->kredit;
                                                        $TOTAL_kredit_11101_SEMUA = $TOTAL_kredit_11101_SEMUA + $list_data->kredit;
                                                    } else {
                                                        echo "";
                                                    }
                                                    ?>
                                                </td>


                                            </tr>



                                        <?php

                                        } else {
                                            // Jika berbeda maka tampilkan jumlah dari pl lama dan buat baris baru list data

                                            // TOTAL PER PEL
                                        ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                    echo ++$start;
                                                    ?>
                                                </td>

                                                <td>
                                                    <?php
                                                    // echo date("d-m-Y", strtotime($list_data->tanggal));
                                                    ?>
                                                </td>

                                                <!-- Kode Akun -->
                                                <td><?php
                                                    // // echo "Kode Akun";
                                                    // if ($list_data->kode_akun) {
                                                    //     echo $list_data->kode_akun;
                                                    //     echo "<br/>";
                                                    //     echo anchor(site_url('Jurnal_kas/ubah_kode_akun_pengeluaran/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH KODE AKUN</i>', 'class="btn btn-warning btn-xs"');
                                                    // } else {
                                                    //     echo anchor(site_url('Jurnal_kas/ubah_kode_akun_pengeluaran/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">INPUT KODE AKUN</i>', 'class="btn btn-danger btn-xs"');
                                                    // }
                                                    ?>
                                                </td>

                                                <td>
                                                    <?php
                                                    // echo $list_data->bukti;
                                                    ?>
                                                </td>

                                                <td>
                                                    <?php
                                                    // echo $list_data->pl;
                                                    ?>
                                                </td>

                                                <td align="left">
                                                    <?php
                                                    // echo $list_data->keterangan;
                                                    ?>
                                                </td>


                                                <!-- 21101-UU Dagang -->
                                                <td style="background-color:yellow;text-align:right">
                                                    <?php
                                                    // if ($list_data->kode_akun == "21101") {
                                                    echo "<font color='red'><strong>" . number_format($TOTAL_21101, 2, ',', '.') . "</strong></font>";
                                                    // $TOTAL_21101 = $TOTAL_21101 + $list_data->kredit;
                                                    // $TOTAL_21101_SEMUA = $TOTAL_21101_SEMUA + $list_data->kredit;
                                                    // } else {
                                                    //     echo "";
                                                    // }
                                                    ?>
                                                </td>

                                                <!-- Serbi-Serbi No. Rek -->
                                                <td>
                                                    <?php
                                                    // echo "no rek";
                                                    ?>
                                                </td>

                                                <!-- Serbi-Serbi Jumlah -->
                                                <td style="background-color:yellow;text-align:right">
                                                    <?php
                                                    // if ($list_data->kode_akun == "21101") {
                                                    //     echo "";
                                                    // } else {
                                                    echo "<font color='red'><strong>" . number_format($TOTAL_debet_jumlah, 2, ',', '.') . "</strong></font>";
                                                    //     $TOTAL_debet_jumlah = $TOTAL_debet_jumlah + $list_data->kredit;
                                                    //     $TOTAL_debet_jumlah_SEMUA = $TOTAL_debet_jumlah_SEMUA + $list_data->kredit;
                                                    // }

                                                    ?>
                                                </td>

                                                <!-- Kredit 11101-Kas Besar -->
                                                <td style="background-color:yellow;text-align:right">
                                                    <?php
                                                    // if ($list_data->kredit > 0) {
                                                    echo "<font color='red'><strong>" . number_format($TOTAL_kredit_11101, 2, ',', '.') . "</strong></font>";
                                                    //     $TOTAL_kredit_11101 = $TOTAL_kredit_11101 + $list_data->kredit;
                                                    //     $TOTAL_kredit_11101_SEMUA = $TOTAL_kredit_11101_SEMUA + $list_data->kredit;
                                                    // } else {
                                                    //     echo "";
                                                    // }
                                                    ?>
                                                </td>


                                            </tr>

                                            <?php
                                            $TOTAL_21101_SEMUA = 0;
                                            $TOTAL_debet_jumlah_SEMUA = 0;
                                            $TOTAL_kredit_11101_SEMUA = 0;

                                            // BARIS DATA BARU DENGAN PL dari awal
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                    echo ++$start;
                                                    ?>
                                                </td>

                                                <td>
                                                    <?php
                                                    echo date("d-m-Y", strtotime($list_data->tanggal));
                                                    ?>
                                                </td>

                                                <!-- Kode Akun -->
                                                <td><?php
                                                    // echo "Kode Akun";
                                                    if ($list_data->kode_akun) {
                                                        echo $list_data->kode_akun;
                                                        echo "<br/>";
                                                        echo anchor(site_url('Jurnal_kas/ubah_kode_akun_pengeluaran/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH KODE AKUN</i>', 'class="btn btn-warning btn-xs"');
                                                    } else {
                                                        echo anchor(site_url('Jurnal_kas/ubah_kode_akun_pengeluaran/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">INPUT KODE AKUN</i>', 'class="btn btn-danger btn-xs"');
                                                    }
                                                    ?>
                                                </td>

                                                <td>
                                                    <?php
                                                    echo $list_data->bukti;
                                                    ?>
                                                </td>

                                                <td>
                                                    <?php
                                                    echo $list_data->pl;
                                                    ?>
                                                </td>

                                                <td align="left">
                                                    <?php
                                                    echo $list_data->keterangan;
                                                    ?>
                                                </td>


                                                <!-- 21101-UU Dagang -->
                                                <td style="text-align:right">
                                                    <?php
                                                    if ($list_data->kode_akun == "21101") {
                                                        echo number_format($list_data->kredit, 2, ',', '.');
                                                        $TOTAL_21101 = $TOTAL_21101 + $list_data->kredit;
                                                        $TOTAL_21101_SEMUA = $TOTAL_21101_SEMUA + $list_data->kredit;
                                                    } else {
                                                        echo "";
                                                    }
                                                    ?>
                                                </td>

                                                <!-- Serbi-Serbi No. Rek -->
                                                <td>
                                                    <?php
                                                    // echo "no rek";
                                                    ?>
                                                </td>

                                                <!-- Serbi-Serbi Jumlah -->
                                                <td style="text-align:right">
                                                    <?php
                                                    if ($list_data->kode_akun == "21101") {
                                                        echo "";
                                                    } else {
                                                        echo number_format($list_data->kredit, 2, ',', '.');
                                                        $TOTAL_debet_jumlah = $TOTAL_debet_jumlah + $list_data->kredit;
                                                        $TOTAL_debet_jumlah_SEMUA = $TOTAL_debet_jumlah_SEMUA + $list_data->kredit;
                                                    }

                                                    ?>
                                                </td>

                                                <!-- Kredit 11101-Kas Besar -->
                                                <td style="text-align:right">
                                                    <?php
                                                    if ($list_data->kredit > 0) {
                                                        echo number_format($list_data->kredit, 2, ',', '.');
                                                        $TOTAL_kredit_11101 = $TOTAL_kredit_11101 + $list_data->kredit;
                                                        $TOTAL_kredit_11101_SEMUA = $TOTAL_kredit_11101_SEMUA + $list_data->kredit;
                                                    } else {
                                                        echo "";
                                                    }
                                                    ?>
                                                </td>


                                            </tr>

                                    <?php
                                        }
                                    }

                                    ?>

                                <?php
                                }
                                ?>


<!-- TOTAL BARIS terakhir -->
<tr>
                                                <td>
                                                    <?php
                                                    echo ++$start;
                                                    ?>
                                                </td>

                                                <td>
                                                    <?php
                                                    // echo date("d-m-Y", strtotime($list_data->tanggal));
                                                    ?>
                                                </td>

                                                <!-- Kode Akun -->
                                                <td><?php
                                                    // // echo "Kode Akun";
                                                    // if ($list_data->kode_akun) {
                                                    //     echo $list_data->kode_akun;
                                                    //     echo "<br/>";
                                                    //     echo anchor(site_url('Jurnal_kas/ubah_kode_akun_pengeluaran/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH KODE AKUN</i>', 'class="btn btn-warning btn-xs"');
                                                    // } else {
                                                    //     echo anchor(site_url('Jurnal_kas/ubah_kode_akun_pengeluaran/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">INPUT KODE AKUN</i>', 'class="btn btn-danger btn-xs"');
                                                    // }
                                                    ?>
                                                </td>

                                                <td>
                                                    <?php
                                                    // echo $list_data->bukti;
                                                    ?>
                                                </td>

                                                <td>
                                                    <?php
                                                    // echo $list_data->pl;
                                                    ?>
                                                </td>

                                                <td align="left">
                                                    <?php
                                                    // echo $list_data->keterangan;
                                                    ?>
                                                </td>


                                                <!-- 21101-UU Dagang -->
                                                <td style="background-color:yellow;text-align:right">
                                                    <?php
                                                    // if ($list_data->kode_akun == "21101") {
                                                    echo "<font color='red'><strong>" . number_format($TOTAL_21101, 2, ',', '.') . "</strong></font>";
                                                    // $TOTAL_21101 = $TOTAL_21101 + $list_data->kredit;
                                                    // $TOTAL_21101_SEMUA = $TOTAL_21101_SEMUA + $list_data->kredit;
                                                    // } else {
                                                    //     echo "";
                                                    // }
                                                    ?>
                                                </td>

                                                <!-- Serbi-Serbi No. Rek -->
                                                <td>
                                                    <?php
                                                    // echo "no rek";
                                                    ?>
                                                </td>

                                                <!-- Serbi-Serbi Jumlah -->
                                                <td style="background-color:yellow;text-align:right">
                                                    <?php
                                                    // if ($list_data->kode_akun == "21101") {
                                                    //     echo "";
                                                    // } else {
                                                    echo "<font color='red'><strong>" . number_format($TOTAL_debet_jumlah, 2, ',', '.') . "</strong></font>";
                                                    //     $TOTAL_debet_jumlah = $TOTAL_debet_jumlah + $list_data->kredit;
                                                    //     $TOTAL_debet_jumlah_SEMUA = $TOTAL_debet_jumlah_SEMUA + $list_data->kredit;
                                                    // }

                                                    ?>
                                                </td>

                                                <!-- Kredit 11101-Kas Besar -->
                                                <td style="background-color:yellow;text-align:right">
                                                    <?php
                                                    // if ($list_data->kredit > 0) {
                                                    echo "<font color='red'><strong>" . number_format($TOTAL_kredit_11101, 2, ',', '.') . "</strong></font>";
                                                    //     $TOTAL_kredit_11101 = $TOTAL_kredit_11101 + $list_data->kredit;
                                                    //     $TOTAL_kredit_11101_SEMUA = $TOTAL_kredit_11101_SEMUA + $list_data->kredit;
                                                    // } else {
                                                    //     echo "";
                                                    // }
                                                    ?>
                                                </td>


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