

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



        ?>



        <div class="box box-warning box-solid">

            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">

                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-2" align="left">
                                        <div class="col-12" text-align="center"> <strong>JURNAL KAS</strong></div>
                                    </div>
                                    <div class="col-3" align="left">
                                        
                                        <?php echo anchor(site_url('jurnal_kas/pemasukan_kas'), 'Pemasukan Kas', 'class="btn btn-danger"');
                                        ?>

                                        <?php echo anchor(site_url('jurnal_kas/pengeluaran_kas'), 'Pengeluaran Kas', 'class="btn btn-success"');
                                        ?>

                                    </div>
                                    <!-- <div class="col-2" align="left">

                                        <?php //echo anchor(site_url('jurnal_kas/pengeluaran_kas'), 'Pengeluaran Kas', 'class="btn btn-success"');
                                        ?>
                                    </div> -->

                                    <div class="col-6" align="right">
                                    
                                        <?php
                                        $action_cari_between_date = site_url('Jurnal_kas/cari_between_date');
                                        ?>

                                        <form action="<?php echo $action_cari_between_date; ?>" method="post">
                                            <div class="row">

                                                <div class="col-md-1" text-align="right" align="right"></div>

                                                <div class="col-md-3" text-align="right">
                                                    <div class="input-group date" id="tgl_awal" name="tgl_awal" data-target-input="nearest">
                                                        <input type="text" class="form-control datetimepicker-input" data-target="#tgl_awal" id="tgl_awal" name="tgl_awal" value="<?php echo $Get_date_awal; ?>" required />
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
                                                        <input type="text" class="form-control datetimepicker-input" data-target="#tgl_akhir" id="tgl_akhir" name="tgl_akhir" value="<?php echo $Get_date_akhir; ?>" required />
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

                                    </div>

                                    <div class="col-1" align="right">

                                        <?php echo anchor(site_url('jurnal_kas/excel'), 'Cetak', 'class="btn btn-success"'); ?>
                                    </div>


                                </div>
                            </div>

                        </div>

                    </div>



                    <div class="card-body">

                        <table id="example" class="table table-striped dt-responsive w-100 table-bordered display nowrap table-hover mb-0" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="text-align:left" width="10px">No</th>
                                    <th style="text-align:left">Tanggal</th>
                                    <th style="text-align:left">Bukti</th>
                                    <th style="text-align:left">PL</th>
                                    <th style="text-align:left">Keterangan</th>
                                    <th style="text-align:left">Kode Rekening</th>
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

                                            if ($list_data->debet > 0) {
                                                // Ubah debet
                                                echo anchor(site_url('Jurnal_kas/pemasukan_kas_update/' . $list_data->id), '<i class="fa fa-pencil-square-o">Ubah</i>', array('title' => 'edit', 'class' => 'btn btn-warning btn-sm'));
                                            } else {
                                                // Ubah Kredit
                                                echo anchor(site_url('Jurnal_kas/pengeluaran_kas_update/' . $list_data->id), '<i class="fa fa-pencil-square-o">Ubah</i>', array('title' => 'edit', 'class' => 'btn btn-warning btn-sm'));
                                            }

                                            echo ' ';
                                            echo anchor(site_url('jurnal_kas/delete/' . $list_data->id), '<i class="fa fa-trash-o">Hapus</i>', 'title="delete" class="btn btn-danger btn-sm" onclick="javasciprt: return confirm(\'Anda Yakin akan menghapus data ini ?\')"');



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
                                        <td align="left">
                                            <?php
                                            echo $list_data->kode_rekening;
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
                                        <!-- <td>
                                            <?php
                                            // echo number_format($TOTAL_debet - $TOTAL_kredit, 2, ',', '.');
                                            ?>
                                        </td> -->

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
                                    <th colspan="6" style="text-align:right"> JUMLAH DEBET / KREDIT </th>
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
                                    <th colspan="6" style="text-align:right">Saldo akhir Kas Bulan September </th>
                                    <th style="text-align:right">
                                        <?php
                                        // echo number_format($TOTAL_debet, 2, ',', '.');
                                        ?>
                                    </th>
                                    <th style="text-align:right">
                                        <?php
                                        echo number_format($TOTAL_debet - $TOTAL_kredit, 2, ',', '.');
                                        $SALDO_AKHIR = $TOTAL_debet - $TOTAL_kredit;
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
                                    <th colspan="6" style="text-align:right">JUMLAH SEIMBANG </th>
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
            "scrollY": 500,
            "scrollX": true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#example9').DataTable({
            "scrollY": 500,
            "scrollX": true
        });
    });
</script>