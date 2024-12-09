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
                                    <div class="col-12" text-align="center"> <strong>DATA PENJUALAN</strong></div>
                                </div>


                            </div>
                            <div class="col-6">

                            </div>



                        </div>
                        <div class="row">
                            <div class="col-6">
                                <?php echo anchor(site_url('tbl_penjualan/create'), 'Input PENJUALAN', 'class="btn btn-danger"'); ?>
                            </div>
                            <div class="col-4">

                            </div>
                            <div class="col-2">
                                <?php echo anchor(site_url('tbl_pembelian/excel'), 'Cetak ke Excel', 'class="btn btn-success"'); ?>
                            </div>



                        </div>



                    </div>
                    <br />



                    <div class="card-body">

                        <table id="tglSPOPFreeze" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="text-align:center" width="10px">No</th>
                                    <!-- <th style="text-align:center" width="100px">Action</th> -->
                                    <th rowspan="2">Tgl Jual</th>
                                    <th rowspan="2">nmrpesan</th>
                                    <th rowspan="2">nmrkirim</th>
                                    <th rowspan="2">Konsumen</th>
                                    <th rowspan="2">Kode</th>
                                    <th rowspan="2">Nama Barang</th>
                                    <th rowspan="2">Unit</th>
                                    <th rowspan="2">Satuan</th>
                                    <th rowspan="2">Harga Satuan</th>
                                    <th rowspan="2">Jumlah</th>

                                    <!-- Colspan -->
                                    <th colspan="2" style="text-align:center">Debit</th>
                                    <th colspan="2" style="text-align:center">Kredit</th>



                                <tr>
                                    <th>UM PPH PSL 22</th>
                                    <th>Piutang</th>
                                    <th>Penjualan DPP</th>
                                    <th>Utang PPN</th>
                                </tr>

                                <!-- -------------- -->


                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $compare_nmr_kirim = 0;
                                $Total_Jumlah_per_nmrkirim = 0;
                                $Total_UMPPHPSL22_per_nmrkirim = 0;
                                $Total_piutang_per_nmrkirim = 0;
                                $Total_penjualandpp_per_nmrkirim = 0;
                                $Total_utangppn_per_nmrkirim = 0;

                                $TOTAL_ALL_JUMLAH = 0;
                                $TOTAL_ALL_UMPPHPSL22 = 0;
                                $TOTAL_ALL_piutang = 0;
                                $TOTAL_ALL_penjualandpp = 0;
                                $TOTAL_ALL_utangppn = 0;
                                foreach ($Tbl_penjualan_data as $list_data) {
                                    if (($compare_nmr_kirim <> $list_data->nmrkirim) and ($start >= 1)) {
                                        // Buat 1 baris untuk total dan background = KUNING
                                ?>
                                        <tr>
                                            <td><?php echo ++$start ?></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>


                                            <td style="background-color:yellow;" align="right"> <?php echo "<font color='red'><strong>" . nominal($Total_Jumlah_per_nmrkirim) . "</strong></font>" ?> </td>
                                            <td style="background-color:yellow;" align="right"> <?php echo "<font color='red'><strong>" . nominal($Total_UMPPHPSL22_per_nmrkirim) . "</strong></font>" ?> </td>
                                            <td style="background-color:yellow;" align="right"> <?php echo "<font color='red'><strong>" . nominal($Total_piutang_per_nmrkirim) . "</strong></font>" ?> </td>
                                            <td style="background-color:yellow;" align="right"> <?php echo "<font color='red'><strong>" . nominal($Total_penjualandpp_per_nmrkirim) . "</strong></font>" ?> </td>
                                            <td style="background-color:yellow;" align="right"> <?php echo "<font color='red'><strong>" . nominal($Total_utangppn_per_nmrkirim) . "</strong></font>" ?> </td>



                                        </tr>
                                    <?php
                                    }
                                    ?>
                                    <tr>
                                        <?php
                                        if ($compare_nmr_kirim == $list_data->nmrkirim) {
                                        ?>
                                            <td><?php echo ++$start ?></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td> <?php echo $list_data->konsumen_nama; ?> </td>
                                          
                                        <?php
                                        } else {
                                            // nmrkirim baru , me NOL kan total nmrkirim
                                            $Total_Jumlah_per_nmrkirim = 0;
                                            $Total_UMPPHPSL22_per_nmrkirim = 0;
                                            $Total_piutang_per_nmrkirim = 0;
                                            $Total_penjualandpp_per_nmrkirim = 0;
                                            $Total_utangppn_per_nmrkirim = 0;
                                        ?>
                                            <td><?php echo ++$start ?></td>
                                            <td>
                                                <?php 
                                                echo date("d M Y", strtotime($list_data->tgl_jual));
                                                echo " "; 
                                                echo anchor(site_url('tbl_penjualan/cetak_penjualan_per_uuid_penjualan/' . $list_data->uuid_penjualan), '<i class="fa fa-pencil-square-o" aria-hidden="true">Cetak Penjualan</i>', 'class="btn btn-success btn-xs"  target="_blank"');

                                           

                                                ?>
                                            </td>
                                            <td align="center"><?php echo $list_data->nmrpesan; ?></td>
                                            <td align="center"><?php echo $list_data->nmrkirim; ?></td>
                                            <td align="center"><?php echo $list_data->konsumen_nama; ?></td>

                                        <?php
                                        }
                                        ?>


                                        <td align="left"><?php echo $list_data->kode_barang; ?></td>
                                        <td align="left"><?php echo $list_data->nama_barang; ?></td>
                                        <td align="left"><?php echo $list_data->unit; ?></td>
                                        <td align="left"><?php echo $list_data->satuan; ?></td>
                                        <td align="right"><?php echo $list_data->harga_satuan; ?></td>


                                        <td align="right">
                                            <?php

                                            $jumlah_per_nmrkirim = $list_data->jumlah * $list_data->harga_satuan;

                                            echo nominal($jumlah_per_nmrkirim);

                                            $Total_Jumlah_per_nmrkirim = $Total_Jumlah_per_nmrkirim + $jumlah_per_nmrkirim;
                                            $TOTAL_ALL_JUMLAH = $TOTAL_ALL_JUMLAH + $jumlah_per_nmrkirim;

                                            // umpphpsl22
                                            $x_var_umpphpsl22 = 1.351351;
                                            $umpphpsl22_per_nmrkirim = ($jumlah_per_nmrkirim * $x_var_umpphpsl22) / 100;
                                            $Total_UMPPHPSL22_per_nmrkirim = $Total_UMPPHPSL22_per_nmrkirim + $umpphpsl22_per_nmrkirim;
                                            $TOTAL_ALL_UMPPHPSL22 = $TOTAL_ALL_UMPPHPSL22 + $umpphpsl22_per_nmrkirim;

                                            $x_piutang_percentage = 11.261261;
                                            $piutang_per_nmrkirim = ($jumlah_per_nmrkirim - (($jumlah_per_nmrkirim * $x_piutang_percentage) / 100));
                                            $Total_piutang_per_nmrkirim = $Total_piutang_per_nmrkirim + $piutang_per_nmrkirim;
                                            $TOTAL_ALL_piutang = $TOTAL_ALL_piutang + $piutang_per_nmrkirim;

                                            $x_penjualandpp_percentage = 90.090090;
                                            $penjualandpp_per_nmrkirim = ($jumlah_per_nmrkirim * $x_penjualandpp_percentage) / 100;
                                            $Total_penjualandpp_per_nmrkirim = $Total_penjualandpp_per_nmrkirim + $penjualandpp_per_nmrkirim;
                                            $TOTAL_ALL_penjualandpp = $TOTAL_ALL_penjualandpp + $penjualandpp_per_nmrkirim;


                                            $x_utangppn_percentage = 9.909910;
                                            $utangppn_per_nmrkirim = ($jumlah_per_nmrkirim * $x_utangppn_percentage) / 100;
                                            $Total_utangppn_per_nmrkirim = $Total_utangppn_per_nmrkirim + $utangppn_per_nmrkirim;
                                            $TOTAL_ALL_utangppn = $TOTAL_ALL_utangppn + $utangppn_per_nmrkirim;

                                            ?>

                                        </td>

                                        <td align="right"> <?php echo nominal($umpphpsl22_per_nmrkirim); ?> </td>

                                        <td align="right">
                                            <?php
                                            echo nominal($piutang_per_nmrkirim);
                                            ?></td>

                                        <td align="right">
                                            <?php
                                            echo nominal($penjualandpp_per_nmrkirim);
                                            ?>
                                        </td>
                                        <td align="right">
                                            <?php
                                            echo nominal($utangppn_per_nmrkirim);
                                            ?>
                                        </td>


                                        <?php
                                        $compare_nmr_kirim = $list_data->nmrkirim;
                                        ?>
                                    </tr>
                                <?php
                                }
                                ?>

                                <!-- TOTAL nmrkirim AKHIR -->
                                <tr>
                                    <td><?php echo ++$start ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>

                                    <td style="background-color:yellow;" align="right"> <?php echo "<font color='red'><strong>" . nominal($Total_Jumlah_per_nmrkirim) . "</strong></font>" ?> </td>
                                    <td style="background-color:yellow;" align="right"> <?php echo "<font color='red'><strong>" . nominal($Total_UMPPHPSL22_per_nmrkirim) . "</strong></font>" ?> </td>
                                    <td style="background-color:yellow;" align="right"> <?php echo "<font color='red'><strong>" . nominal($Total_piutang_per_nmrkirim) . "</strong></font>" ?> </td>
                                    <td style="background-color:yellow;" align="right"> <?php echo "<font color='red'><strong>" . nominal($Total_penjualandpp_per_nmrkirim) . "</strong></font>" ?> </td>
                                    <td style="background-color:yellow;" align="right"> <?php echo "<font color='red'><strong>" . nominal($Total_utangppn_per_nmrkirim) . "</strong></font>" ?> </td>



                                </tr>
                            </tbody>

                            <tfoot>

                                <tr>
                                    <th>No</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th style="text-align:right">TOTAL</th>
                                    <th style="text-align:right"><?php echo nominal($TOTAL_ALL_JUMLAH); ?></th>
                                    <th style="text-align:right"><?php echo nominal($TOTAL_ALL_UMPPHPSL22); ?></th>
                                    <th style="text-align:right"><?php echo nominal($TOTAL_ALL_piutang); ?></th>
                                    <th style="text-align:right"><?php echo nominal($TOTAL_ALL_penjualandpp); ?></th>
                                    <th style="text-align:right"><?php echo nominal($TOTAL_ALL_utangppn); ?></th>

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