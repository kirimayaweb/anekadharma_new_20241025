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
                                    <div class="col-12" text-align="center"> <strong>DATA PEMBELIAN</strong></div>
                                </div>


                            </div>
                            <div class="col-6">

                            </div>



                        </div>
                        <div class="row">
                            <div class="col-6">
                                <?php echo anchor(site_url('tbl_pembelian/create'), 'Input Pembelian (Belanja Perusahaan)', 'class="btn btn-danger"'); ?>
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
                                    <th style="text-align:center" width="10px">No</th>
                                    <th style="text-align:center">Tgl Po</th>
                                    <th style="text-align:center">Spop</th>
                                    <th style="text-align:center">No. faktur/ kwitansi</th>
                                    <th style="text-align:center">Supplier</th>
                                    <th style="text-align:center">Kode Barang</th>
                                    <th style="text-align:center">Nama Barang</th>
                                    <th style="text-align:center">Jumlah</th>
                                    <th style="text-align:center">Satuan</th>
                                    <th style="text-align:center">Konsumen</th>
                                    <th style="text-align:center">Harga Satuan</th>
                                    <th style="text-align:center">Harga Total</th>
                                    <th style="text-align:center">Statuslu</th>
                                    <th style="text-align:center">Kas / Bank</th>
                                    <th style="text-align:center">Tgl Bayar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $compare_spop = 0;
                                $compare_uuid_spop = 0;
                                $Total_per_SPOP = 0;
                                $TOTAL_LUNAS = 0;
                                $TOTAL_HUTANG = 0;
                                $list_spop_status_lu = "";
                                $x_button = 0;
                                foreach ($Tbl_pembelian_data as $list_data) {

                                    $list_spop_status_lu = $list_data->statuslu; // untuk cek kondisi di baris terakhir (SPOP)
                                    if (($compare_spop <> $list_data->spop) and ($start >= 1)) {
                                        // Buat 1 baris untuk total dan background = KUNING
                                ?>
                                        <tr>
                                            <td><?php echo ++$start ?></td>
                                            <td>
                                                <?php



                                                // echo "baris x";
                                                // if ($x_button == 1) {
                                                // echo anchor(site_url('tbl_pembelian/update_per_spop/' . $list_data->spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH</i>', 'class="btn btn-warning btn-xs"');

                                                // echo anchor(site_url('tbl_pembelian/delete_per_spop/' . $list_data->spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">HAPUS</i>', 'class="btn btn-danger btn-xs"');
                                                // }
                                                ?>

                                            </td>
                                            <td></td>
                                            <td></td>
                                            <!-- <td></td> -->
                                            <!-- <td></td> -->
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td style="background-color:yellow;" align="right"> <?php echo "<font color='red'><strong>" . nominal($Total_per_SPOP) . "</strong></font>" ?> </td>
                                            <td>
                                                <?php

                                                // if ($list_data->statuslu == "U") {
                                                //     echo anchor(site_url('tbl_pembelian/create_pembayaran/' . $compare_uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">Pengajuan Pembayaran </i>', 'class="btn btn-warning btn-xs"');
                                                // }







                                                $result_pengajuan_by_uuid_spop = $this->Tbl_pembelian_pengajuan_bayar_model->get_by_uuid_spop($compare_uuid_spop);

                                                $TOTAL_Nominal_pengajuan = $this->Tbl_pembelian_pengajuan_bayar_model->get_sumNominal_by_uuid_spop($compare_uuid_spop)->total_pengajuan;
                                                //                                         print_r("spop : ");
                                                //                                         print_r($compare_uuid_spop);
                                                //                                         print_r(" : ");
                                                // print_r($result_pengajuan_by_uuid_spop);
                                                if ($result_pengajuan_by_uuid_spop) {
                                                    $startx = 0;
                                                    $total_nominal_pengajuan = 0;
                                                    foreach ($result_pengajuan_by_uuid_spop as $list_data_pengajuan) {
                                                        // echo $list_data_pengajuan->uuid_pengajuan_bayar;
                                                        echo anchor(site_url('tbl_pembelian/cetak_pengajuan_bayar_per_spop/' . $list_data_pengajuan->uuid_pengajuan_bayar), '<i class="fa fa-pencil-square-o" aria-hidden="true">Cetak Pengajuan ' . ++$startx . '</i>', 'class="btn btn-success btn-xs" target="_blank"');

                                                        $total_nominal_pengajuan = $total_nominal_pengajuan + $list_data_pengajuan->nominal_pengajuan;
                                                    }
                                                    // echo $TOTAL_Nominal_pengajuan;
                                                    // echo " : ";
                                                    // echo $Total_per_SPOP;
                                                    // echo " : ";
                                                    if ($TOTAL_Nominal_pengajuan < $Total_per_SPOP) {

                                                        if ($list_data->statuslu == "Hutang"  or $list_data->statuslu == "U") {
                                                            echo anchor(site_url('tbl_pembelian/create_pembayaran/' . $compare_uuid_spop . '/pembelian'), '<i class="fa fa-pencil-square-o" aria-hidden="true">Pengajuan Pembayaran B</i>', 'class="btn btn-warning btn-xs"');
                                                        }else{
                                                            echo "-";
                                                    
                                                        }
                                                    }
                                                } else {
                                                    // if ($total_nominal_pengajuan < $Total_per_SPOP) {

                                                    if ($list_data->statuslu == "Hutang"  or $list_data->statuslu == "U") {
                                                        echo anchor(site_url('tbl_pembelian/create_pembayaran/' . $compare_uuid_spop . '/pembelian'), '<i class="fa fa-pencil-square-o" aria-hidden="true">Pengajuan Pembayaran A </i>', 'class="btn btn-warning btn-xs"');
                                                        echo " - ";
                                                        echo $list_data->statuslu;
                                                    }else{
                                                        echo "-";
                                                    }
                                                    // }else{
                                                    //     echo anchor(site_url('tbl_pembelian/create_pembayaran/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">Pengajuan Pembayaran</i>', 'class="btn btn-warning btn-xs" disabled');
                                                    // }

                                                }




                                                ?>
                                            </td>
                                            <td></td>
                                            <td></td>

                                        </tr>
                                    <?php
                                    }
                                    ?>
                                    <tr>
                                        <?php
                                        if ($compare_spop == $list_data->spop) {
                                        ?>
                                            <td><?php echo ++$start ?></td>
                                            <td>

                                                <?php

                                                // echo $list_data->spop;


                                                if (($compare_spop == $list_data->spop) and $x_button == 1) {
                                                    // echo anchor(site_url('tbl_pembelian/update_per_spop/' . $list_data->spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH</i>', 'class="btn btn-warning btn-xs"');

                                                    // echo anchor(site_url('tbl_pembelian/delete_per_spop/' . $list_data->spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">HAPUS</i>', 'class="btn btn-danger btn-xs"');
                                                    $x_button_show = 1;
                                                    $x_button = $x_button + 1;

                                                    // echo "jghjghjghhhhh";
                                                } else {
                                                    // echo "oooooooooooo";
                                                    echo date("d M Y", strtotime($list_data->tgl_po));
                                                    echo anchor(site_url('tbl_pembelian/update_per_id/' . $list_data->uuid_pembelian), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH</i>', 'class="btn btn-warning btn-xs"');

                                                    echo anchor(site_url('tbl_pembelian/delete_by_uuid_pembelian/' . $list_data->uuid_pembelian), '<i class="fa fa-pencil-square-o" aria-hidden="true">HAPUS</i>', 'class="btn btn-danger btn-xs"');
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $list_data->spop; ?></td>
                                            <td align="center"><?php echo $list_data->nmrfakturkwitansi; ?></td>


                                            <td align="left"><?php echo $list_data->supplier_nama; ?></td>
                                            <!-- <td></td>
                                            <td></td> -->
                                        <?php
                                        } else {
                                            // SPOP baru , me NOL kan total SPOP
                                            $Total_per_SPOP = 0;
                                            $x_button = 0;
                                            $x_button_show = 0;
                                        ?>
                                            <td><?php echo ++$start ?></td>
                                            <td><?php
                                                echo date("d M Y", strtotime($list_data->tgl_po));
                                                echo " ";

                                                echo anchor(site_url('tbl_pembelian/update_per_id/' . $list_data->uuid_pembelian), '<i class="fa fa-pencil-square-o" aria-hidden="true">UPDATE</i>', 'class="btn btn-warning btn-xs"');

                                                echo anchor(site_url('tbl_pembelian/delete_by_uuid_pembelian/' . $list_data->uuid_pembelian), '<i class="fa fa-pencil-square-o" aria-hidden="true">HAPUS</i>', 'class="btn btn-danger btn-xs"');

                                                //echo " ";
                                                //echo anchor(site_url('tbl_pembelian/delete_per_spop/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">HAPUS</i>', 'class="btn btn-danger btn-xs" disabled');
                                                //echo " ";

                                                ?>


                                            </td>
                                            <td align="center">
                                                <?php
                                                echo $list_data->spop;
                                                $x_button = $x_button + 1;
                                                // echo $x_button;
                                                echo "  ";
                                                if ($list_data->status_spop) {
                                                    // echo $list_data->status_spop;
                                                    echo anchor(site_url('tbl_pembelian/update_status_per_spop/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">' . $list_data->status_spop . '</i>', 'class="btn btn-success btn-xs"');
                                                } else {
                                                    echo anchor(site_url('tbl_pembelian/update_status_per_spop/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">STATUS</i>', 'class="btn btn-danger btn-xs"');
                                                }

                                                ?>
                                            </td>

                                            <!-- <td align="center"><?php //echo $list_data->nmrsj; 
                                                                    ?></td> -->
                                            <td align="center"><?php echo $list_data->nmrfakturkwitansi; ?></td>
                                            <!-- <td align="center"><?php //echo $list_data->nmrbpb; 
                                                                    ?></td> -->

                                            <td align="left"><?php echo $list_data->supplier_nama; ?></td>
                                        <?php
                                        }
                                        ?>



                                        <td align="center"><?php echo $list_data->kode_barang; ?></td>
                                        <td align="left"><?php echo $list_data->uraian; ?></td>
                                        <td align="right"><?php echo nominal($list_data->jumlah); ?></td>
                                        <td align="left"><?php echo $list_data->satuan; ?></td>
                                        <td align="left"><?php echo $list_data->konsumen; ?></td>
                                        <td align="right"><?php echo nominal($list_data->harga_satuan); ?></td>
                                        <td align="right">
                                            <?php
                                            $total_per_uraian = $list_data->jumlah * $list_data->harga_satuan;

                                            echo nominal($total_per_uraian);

                                            $Total_per_SPOP = $Total_per_SPOP + $total_per_uraian;


                                            ?>
                                        </td>
                                        <td align="center">
                                            <?php
                                            if ($list_data->statuslu == "U") {
                                                echo "<font color='red'>" . $list_data->statuslu . "</font>";
                                                $TOTAL_HUTANG = $TOTAL_HUTANG + $total_per_uraian;
                                            } else {
                                                echo $list_data->statuslu;
                                                $TOTAL_LUNAS = $TOTAL_LUNAS + $total_per_uraian;
                                            }


                                            ?>
                                        </td>

                                        <td align="center">
                                            <?php
                                            // echo $list_data->kas_bank;

                                            if ($list_data->statuslu == "Lunas"  or $list_data->statuslu == "L") {
                                                echo $list_data->kas_bank;
                                            }

                                            ?>
                                        </td>

                                        <td align="center">
                                            <?php


                                            if (date("Y", strtotime($this->input->post('tgl_po', TRUE))) < 2020) {
                                                echo "";
                                            } else {
                                                echo $list_data->tgl_bayar;
                                            }

                                            ?>
                                        </td>
                                        <?php
                                        $compare_spop = $list_data->spop;
                                        $compare_uuid_spop = $list_data->uuid_spop;
                                        ?>
                                    </tr>
                                <?php
                                }
                                ?>

                                <!-- TOTAL SPOP AKHIR -->
                                <tr>
                                    <td><?php echo ++$start ?></td>
                                    <td>
                                        <?php
                                        // if ($x_button == 1) {
                                        //     echo anchor(site_url('tbl_pembelian/update_per_spop/' . $list_data->spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH</i>', 'class="btn btn-warning btn-xs"');

                                        //     echo anchor(site_url('tbl_pembelian/delete_per_spop/' . $list_data->spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">HAPUS</i>', 'class="btn btn-danger btn-xs"');
                                        // }
                                        ?>
                                    </td>
                                    <!-- <td></td> -->
                                    <!-- <td></td> -->
                                    <td><?php //list total per spop 
                                        ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="background-color:yellow;" align="right"> <?php echo "<font color='red'><strong>" . nominal($Total_per_SPOP) . "</strong></font>" ?> </td>
                                    <td>
                                        <?php
                                        // if ($list_spop_status_lu == "U") {
                                        //     echo anchor(site_url('tbl_pembelian/create_pembayaran/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">Pengajuan Pembayaran</i>', 'class="btn btn-warning btn-xs"');
                                        // }


                                        $result_pengajuan_by_uuid_spop = $this->Tbl_pembelian_pengajuan_bayar_model->get_by_uuid_spop($list_data->uuid_spop);

                                        $TOTAL_Nominal_pengajuan = $this->Tbl_pembelian_pengajuan_bayar_model->get_sumNominal_by_uuid_spop($list_data->uuid_spop)->total_pengajuan;

                                        if ($result_pengajuan_by_uuid_spop) {
                                            $startx = 0;
                                            $total_nominal_pengajuan = 0;
                                            foreach ($result_pengajuan_by_uuid_spop as $list_data_pengajuan) {
                                                // echo $list_data_pengajuan->uuid_pengajuan_bayar;
                                                echo anchor(site_url('tbl_pembelian/cetak_pengajuan_bayar_per_spop/' . $list_data_pengajuan->uuid_pengajuan_bayar), '<i class="fa fa-pencil-square-o" aria-hidden="true">Cetak Pengajuan ' . ++$startx . '</i>', 'class="btn btn-success btn-xs" target="_blank"');

                                                $total_nominal_pengajuan = $total_nominal_pengajuan + $list_data_pengajuan->nominal_pengajuan;
                                            }
                                            // echo $TOTAL_Nominal_pengajuan;
                                            // echo " : ";
                                            // echo $Total_per_SPOP;
                                            // echo " : ";
                                            if ($TOTAL_Nominal_pengajuan < $Total_per_SPOP) {
                                                if ($list_data->statuslu == "Hutang"  or $list_data->statuslu == "U") {
                                                    echo anchor(site_url('tbl_pembelian/create_pembayaran/' . $compare_uuid_spop . '/pembelian'), '<i class="fa fa-pencil-square-o" aria-hidden="true">Pengajuan Pembayaran Y</i>', 'class="btn btn-warning btn-xs"');
                                                }
                                            }
                                        } else {
                                            // if ($total_nominal_pengajuan < $Total_per_SPOP) {


                                            if ($list_data->statuslu == "Hutang"  or $list_data->statuslu == "U") {

                                                echo anchor(site_url('tbl_pembelian/create_pembayaran/' . $compare_uuid_spop . '/pembelian'), '<i class="fa fa-pencil-square-o" aria-hidden="true">Pengajuan Pembayaran X</i>', 'class="btn btn-warning btn-xs"');
                                            }

                                            // }else{
                                            //     echo anchor(site_url('tbl_pembelian/create_pembayaran/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">Pengajuan Pembayaran</i>', 'class="btn btn-warning btn-xs" disabled');
                                            // }

                                        }

                                        ?>
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <!-- <th></th> -->
                                    <!-- <th></th> -->
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th style="text-align:right">TOTAL LUNAS</th>
                                    <th style="text-align:right"><?php echo nominal($TOTAL_LUNAS); ?></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <!-- <th></th> -->
                                    <!-- <th></th> -->
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th style="text-align:right"><?php echo "<font color='red'>TOTAL HUTANG</font>"; ?></th>
                                    <th style="text-align:right"><?php echo "<font color='red'>" . nominal($TOTAL_HUTANG) . "</font>"; ?></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
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