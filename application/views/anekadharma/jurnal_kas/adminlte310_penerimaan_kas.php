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
                                        <div class="col-12" text-align="center"> <strong>JURNAL PENERIMAAN KAS</strong></div>
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

                        <table id="example" class="table table-striped dt-responsive w-100 table-bordered display nowrap table-hover mb-0" style="width:100%">
                            <thead>

                                <!-- <tr>
                                    <th rowspan="3" style="text-align:left" width="10px">No</th>
                                    <th rowspan="3" style="text-align:left" width="10px">Tanggal</th>
                                    <th rowspan="3" style="text-align:center">Kode Akun</th>
                                    <th rowspan="3" style="text-align:center">No. Bukti BKM</th>
                                    <th rowspan="3" style="text-align:center">PL</th>
                                    <th rowspan="3" style="text-align:center">KETERANGAN</th>

                                    <th colspan="1" style="text-align:center">Debit</th>


                                    <th colspan="3" style="text-align:center">KREDIT</th>
                                <tr>
                                    <th rowspan="2" style="text-align:right">11101-Kas Besar</th>
                                    <th rowspan="2" style="text-align:center">11301-PU <br />Non Angsuran</th>
                                    <th colspan="2" style="text-align:center">Serba-Serbi</th>
                                </tr>
                                <tr>
                                    <th rowspan="3" style="text-align:left">Rek</th>
                                    <th style="text-align:center">Jumlah</th>
                                </tr> -->



                                <tr>

                                    <th rowspan="3" style="text-align:left" width="10px">No</th>
                                    <th rowspan="3" style="text-align:left" width="10px">Tanggal</th>
                                    <th rowspan="3" style="text-align:center">Kode Akun</th>
                                    <th rowspan="3" style="text-align:center">No. Bukti BKM</th>
                                    <th rowspan="3" style="text-align:center">PL</th>
                                    <th rowspan="3" style="text-align:center">KETERANGAN</th>
                                    <th colspan="1" style="text-align:center">DEBET</th>
                                    <th colspan="3" style="text-align:center">KREDIT</th>

                                </tr>
                                <tr>
                                    <th rowspan="2" style="text-align:center">11101-Kas Besar</th>
                                    <th rowspan="2" style="text-align:center">11301-PU Non Angsuran</th>
                                    <th colspan="2" style="text-align:right">Serba-Serbi</th>
                                </tr>
                                <tr>
                                    <th style="text-align:left">No. Rek</th>
                                    <th style="text-align:right">Jumlah</th>
                                </tr>





                            </thead>
                            <tbody>
                                <?php
                                $start = 0;
                                $TOTAL_debet_11101 = 0;
                                $TOTAL_kredit_11301 = 0;
                                $TOTAL_kredit_jumlah = 0;
                                $TOTAL_saldo = 0;

                                $TOTAL_debet_11101_SEMUA = 0;
                                $TOTAL_kredit_11301_SEMUA = 0;
                                $TOTAL_kredit_jumlah_SEMUA = 0;
                                $TOTAL_saldo_SEMUA = 0;

                                $PL_Data = 0;

                                foreach ($Data_kas as $list_data) {

                                ?>

                                    <?php
                                    // BARIS PERTAMA == ++START = 1
                                    $First_row= ++$start;
                                    if ($First_row == 1) {
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
                                                if ($list_data->kode_akun) {
                                                    echo $list_data->kode_akun;
                                                    echo "<br/>";
                                                    echo anchor(site_url('Jurnal_kas/ubah_kode_akun_penerimaan/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH KODE AKUN</i>', 'class="btn btn-warning btn-xs"');
                                                } else {
                                                    echo anchor(site_url('Jurnal_kas/ubah_kode_akun_penerimaan/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">INPUT KODE AKUN</i>', 'class="btn btn-danger btn-xs"');
                                                }
                                                ?>
                                            </td>

                                            <td><?php
                                                echo $list_data->bukti;
                                                ?>
                                            </td>
                                            <td><?php
                                                echo $list_data->pl;
                                                ?>
                                            </td>
                                            <td align="left">
                                                <?php
                                                echo $list_data->keterangan;
                                                ?>
                                            </td>

                                            <!-- Debet -->
                                            <td style="text-align:right">
                                                <?php
                                                if ($list_data->debet > 0) {
                                                    echo number_format($list_data->debet, 2, ',', '.');
                                                    $TOTAL_debet_11101 = $TOTAL_debet_11101 + $list_data->debet;
                                                    $TOTAL_debet_11101_SEMUA = $TOTAL_debet_11101_SEMUA + $list_data->debet;
                                                } else {
                                                    echo "";
                                                }
                                                ?>
                                            </td>

                                            <!-- Kredit -->
                                            <td style="text-align:right">
                                                <?php
                                                if ($list_data->kode_akun == "11301") {
                                                    echo number_format($list_data->debet, 2, ',', '.');
                                                    $TOTAL_kredit_11301 = $TOTAL_kredit_11301 + $list_data->debet;
                                                    $TOTAL_kredit_11301_SEMUA = $TOTAL_kredit_11301_SEMUA + $list_data->debet;
                                                } else {
                                                    echo "";
                                                }


                                                ?>


                                            </td>


                                            <!-- rekening -->
                                            <td align="left">
                                                <?php
                                                echo $list_data->kode_rekening;
                                                ?>
                                            </td>


                                            <td style="text-align:right">
                                                <?php
                                                if ($list_data->kode_akun <> "11301") {
                                                    echo number_format($list_data->debet, 2, ',', '.');
                                                    $TOTAL_kredit_jumlah = $TOTAL_kredit_jumlah + $list_data->debet;
                                                    $TOTAL_kredit_jumlah_SEMUA = $TOTAL_kredit_jumlah_SEMUA + $list_data->debet;
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

                                        // Baris 2 dst : cek apakah $PL_Data == $list_data->pl? jika ya, maka tampilkan baris baru , jika beda , maka tampilkan baris total pl lama 
                                        // dan buat baris baru lagi untuk data selanjutrnya

                                        if ($PL_Data == $list_data->pl) {
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
                                                    if ($list_data->kode_akun) {
                                                        echo $list_data->kode_akun;
                                                        echo "<br/>";
                                                        echo anchor(site_url('Jurnal_kas/ubah_kode_akun_penerimaan/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH KODE AKUN</i>', 'class="btn btn-warning btn-xs"');
                                                    } else {
                                                        echo anchor(site_url('Jurnal_kas/ubah_kode_akun_penerimaan/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">INPUT KODE AKUN</i>', 'class="btn btn-danger btn-xs"');
                                                    }
                                                    ?>
                                                </td>

                                                <td><?php
                                                    echo $list_data->bukti;
                                                    ?>
                                                </td>
                                                <td><?php
                                                    echo $list_data->pl;
                                                    ?>
                                                </td>
                                                <td align="left">
                                                    <?php
                                                    echo $list_data->keterangan;
                                                    ?>
                                                </td>

                                                <!-- Debet -->
                                                <td style="text-align:right">
                                                    <?php
                                                    if ($list_data->debet > 0) {
                                                        echo number_format($list_data->debet, 2, ',', '.');
                                                        $TOTAL_debet_11101 = $TOTAL_debet_11101 + $list_data->debet;
                                                        $TOTAL_debet_11101_SEMUA = $TOTAL_debet_11101_SEMUA + $list_data->debet;
                                                    } else {
                                                        echo "";
                                                    }
                                                    ?>
                                                </td>

                                                <!-- Kredit -->
                                                <td style="text-align:right">
                                                    <?php
                                                    if ($list_data->kode_akun == "11301") {
                                                        echo number_format($list_data->debet, 2, ',', '.');
                                                        $TOTAL_kredit_11301 = $TOTAL_kredit_11301 + $list_data->debet;
                                                        $TOTAL_kredit_11301_SEMUA = $TOTAL_kredit_11301_SEMUA + $list_data->debet;
                                                        // echo "<br/>";
                                                        // echo number_format($TOTAL_kredit_11301, 2, ',', '.');
                                                    } else {
                                                        echo "";
                                                    }


                                                    ?>


                                                </td>


                                                <!-- rekening -->
                                                <td align="left">
                                                    <?php
                                                    echo $list_data->kode_rekening;
                                                    ?>
                                                </td>


                                                <td style="text-align:right">
                                                    <?php
                                                    if ($list_data->kode_akun <> "11301") {
                                                        echo number_format($list_data->debet, 2, ',', '.');
                                                        $TOTAL_kredit_jumlah = $TOTAL_kredit_jumlah + $list_data->debet;
                                                        $TOTAL_kredit_jumlah_SEMUA = $TOTAL_kredit_jumlah_SEMUA + $list_data->debet;
                                                    } else {
                                                        echo "";
                                                    }

                                                    ?>
                                                </td>

                                            </tr>

                                        <?php
                                        } else {
                                            // PL beda   
                                            // 1. BARIS TOTAL PL SEBELUMNYA
                                        ?>

                                            <!-- TOTAL DARI KELOMPOK PL -->
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
                                                <td>
                                                    <?php
                                                    // if ($list_data->kode_akun) {
                                                    //     echo $list_data->kode_akun;
                                                    //     echo "<br/>";
                                                    //     echo anchor(site_url('Jurnal_kas/ubah_kode_akun_penerimaan/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH KODE AKUN</i>', 'class="btn btn-warning btn-xs"');
                                                    // } else {
                                                    //     echo anchor(site_url('Jurnal_kas/ubah_kode_akun_penerimaan/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">INPUT KODE AKUN</i>', 'class="btn btn-danger btn-xs"');
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

                                                <!-- Debet -->
                                                <td style="background-color:yellow;text-align:right">
                                                    <?php
                                                    // if ($list_data->debet > 0) {
                                                    echo "<font color='red'><strong>" . number_format($TOTAL_debet_11101, 2, ',', '.') . "</strong></font>";
                                                    // $TOTAL_debet_11101 = $TOTAL_debet_11101 + $list_data->debet;
                                                    // } else {
                                                    //     echo "";
                                                    // }
                                                    ?>
                                                </td>

                                                <!-- Kredit -->
                                                <td style="background-color:yellow;text-align:right">
                                                    <?php
                                                    // if ($list_data->kode_akun == "11301") {
                                                    echo "<font color='red'><strong>" . number_format($TOTAL_kredit_11301, 2, ',', '.') . "</strong></font>";
                                                    // $TOTAL_kredit_11301 = $TOTAL_kredit_11301 + $list_data->debet;
                                                    // } else {
                                                    //     echo "";
                                                    // }


                                                    ?>


                                                </td>


                                                <!-- rekening -->
                                                <td style="background-color:yellow;text-align:right">
                                                    <?php
                                                    // echo $list_data->kode_rekening;
                                                    ?>
                                                </td>


                                                <td style="background-color:yellow;text-align:right">
                                                    <?php
                                                    // if ($list_data->kode_akun <> "11301") {
                                                    echo "<font color='red'><strong>" . number_format($TOTAL_kredit_jumlah, 2, ',', '.') . "</strong></font>";
                                                    // $TOTAL_kredit_11301 = $TOTAL_kredit_11301 + $list_data->debet;
                                                    // } else {
                                                    //     echo "";
                                                    // }

                                                    ?>
                                                </td>

                                            </tr>
                                            <?php

                                            // 2. BARIS TOTAL PL BARU
                                            // menol kan total per kelompok $list_data->pl , karena sudah beda pl
                                            $TOTAL_debet_11101 = 0;
                                            $TOTAL_kredit_jumlah = 0;
                                            $TOTAL_kredit_11301 = 0;
                                            $TOTAL_saldo = 0;

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
                                                    if ($list_data->kode_akun) {
                                                        echo $list_data->kode_akun;
                                                        echo "<br/>";
                                                        echo anchor(site_url('Jurnal_kas/ubah_kode_akun_penerimaan/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH KODE AKUN</i>', 'class="btn btn-warning btn-xs"');
                                                    } else {
                                                        echo anchor(site_url('Jurnal_kas/ubah_kode_akun_penerimaan/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">INPUT KODE AKUN</i>', 'class="btn btn-danger btn-xs"');
                                                    }
                                                    ?>
                                                </td>

                                                <td><?php
                                                    echo $list_data->bukti;
                                                    ?>
                                                </td>
                                                <td><?php
                                                    echo $list_data->pl;
                                                    ?>
                                                </td>
                                                <td align="left">
                                                    <?php
                                                    echo $list_data->keterangan;
                                                    ?>
                                                </td>

                                                <!-- Debet -->
                                                <td style="text-align:right">
                                                    <?php
                                                    if ($list_data->debet > 0) {
                                                        echo number_format($list_data->debet, 2, ',', '.');
                                                        $TOTAL_debet_11101 = $TOTAL_debet_11101 + $list_data->debet;
                                                        $TOTAL_debet_11101_SEMUA = $TOTAL_debet_11101_SEMUA + $list_data->debet;
                                                    } else {
                                                        echo "";
                                                    }
                                                    ?>
                                                </td>

                                                <!-- Kredit -->
                                                <td style="text-align:right">
                                                    <?php
                                                    if ($list_data->kode_akun == "11301") {
                                                        echo number_format($list_data->debet, 2, ',', '.');
                                                        $TOTAL_kredit_11301 = $TOTAL_kredit_11301 + $list_data->debet;
                                                        $TOTAL_kredit_11301_SEMUA = $TOTAL_kredit_11301_SEMUA + $list_data->debet;
                                                    } else {
                                                        echo "";
                                                    }


                                                    ?>


                                                </td>


                                                <!-- rekening -->
                                                <td align="left">
                                                    <?php
                                                    echo $list_data->kode_rekening;
                                                    ?>
                                                </td>


                                                <td style="text-align:right">
                                                    <?php
                                                    if ($list_data->kode_akun <> "11301") {
                                                        echo number_format($list_data->debet, 2, ',', '.');
                                                        $TOTAL_kredit_jumlah = $TOTAL_kredit_jumlah + $list_data->debet;
                                                        $TOTAL_kredit_jumlah_SEMUA = $TOTAL_kredit_jumlah_SEMUA + $list_data->debet;
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
                                    // END OF FOR EACH $Data_kas 
                                }
                                ?>

                                <!-- BARIS TERAKHIR TOTAL DARI KELOMPOK PL -->
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
                                    <td>
                                        <?php
                                        // if ($list_data->kode_akun) {
                                        //     echo $list_data->kode_akun;
                                        //     echo "<br/>";
                                        //     echo anchor(site_url('Jurnal_kas/ubah_kode_akun_penerimaan/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH KODE AKUN</i>', 'class="btn btn-warning btn-xs"');
                                        // } else {
                                        //     echo anchor(site_url('Jurnal_kas/ubah_kode_akun_penerimaan/' . $list_data->uuid_jurnal_kas), '<i class="fa fa-pencil-square-o" aria-hidden="true">INPUT KODE AKUN</i>', 'class="btn btn-danger btn-xs"');
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

                                    <!-- Debet -->
                                    <td style="background-color:yellow;text-align:right">
                                        <?php
                                        // if ($list_data->debet > 0) {
                                        echo "<font color='red'><strong>" . number_format($TOTAL_debet_11101, 2, ',', '.') . "</strong></font>";
                                        // $TOTAL_debet_11101 = $TOTAL_debet_11101 + $list_data->debet;
                                        // } else {
                                        //     echo "";
                                        // }
                                        ?>
                                    </td>

                                    <!-- Kredit -->
                                    <td style="background-color:yellow;text-align:right">
                                        <?php
                                        // if ($list_data->kode_akun == "11301") {
                                        echo "<font color='red'><strong>" . number_format($TOTAL_kredit_11301, 2, ',', '.') . "</strong></font>";
                                        // $TOTAL_kredit_11301 = $TOTAL_kredit_11301 + $list_data->debet;
                                        // } else {
                                        //     echo "";
                                        // }


                                        ?>


                                    </td>


                                    <!-- rekening -->
                                    <td style="background-color:yellow;text-align:right">
                                        <?php
                                        // echo $list_data->kode_rekening;
                                        ?>
                                    </td>


                                    <td style="background-color:yellow;text-align:right">
                                        <?php
                                        // if ($list_data->kode_akun <> "11301") {
                                        echo "<font color='red'><strong>" . number_format($TOTAL_kredit_jumlah, 2, ',', '.') . "</strong></font>";
                                        // $TOTAL_kredit_11301 = $TOTAL_kredit_11301 + $list_data->debet;
                                        // } else {
                                        //     echo "";
                                        // }

                                        ?>
                                    </td>

                                </tr>



                            </tbody>

                            <!-- tfoot -->

                            <tfoot>
                                <tr>

                                    <th style="text-align:left" width="10px"></th>
                                    <th style="text-align:center"></th>
                                    <th style="text-align:center"></th>
                                    <th style="text-align:center"></th>
                                    <th style="text-align:center"></th>
                                    <th style="text-align:center"></th>
                                    <th style="text-align:right">
                                        <?php
                                        echo "<font color='blue'><strong>" . number_format($TOTAL_debet_11101_SEMUA, 2, ',', '.') . "</strong></font>";
                                        ?>
                                    </th> <!-- TOTAL DEBET -->
                                    <th style="text-align:right">
                                        <?php
                                        // echo number_format($TOTAL_kredit_11301_SEMUA, 2, ',', '.');
                                        ?>
                                    </th>
                                    <th style="text-align:center"></th>
                                    <th style="text-align:right">
                                        <?php
                                        echo "<font color='blue'><strong>" . number_format($TOTAL_kredit_jumlah_SEMUA + $TOTAL_kredit_11301_SEMUA, 2, ',', '.') . "</strong></font>";
                                        ?>
                                    </th> <!-- TOTAL JUMLAH -->

                                </tr>

                            </tfoot>



                            <!-- end of tfoot -->


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