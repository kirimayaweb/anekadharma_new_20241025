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
                <div class="card card-warning">
                    <div class="card-header">
                        <div class="row">
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12" text-align="center"> <strong>DATA BELANJA (PEMBAYARAN KE SUPPLIER)</strong></div>
                                </div>


                            </div>



                        </div>



                    </div>




                    <div class="card-body">

                        <table id="tglSPOPFreeze" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="text-align:center" width="10px">No</th>
                                    <th>Tanggal</th>
                                    <th>SPOP</th>
                                    <th>Nama SUPPLIER</th>
                                    <th>Jumlah Tagihan</th>
                                    <th>Transfer</th>
                                    <th>Kas Kecil</th>
                                    <th>Kekurangan</th>
                                    <!-- <th>Kas/Bank</th> -->
                                    <th>Action</th>

                                </tr>
                            </thead>



                            <?php

                            ?>
                            <tbody>
                                <?php
                                $start = 0;
                                $Total_pembelian = 0;
                                $Total_pembayaran = 0;
                                $Total_kekurangan = 0;
                                $TOTAL_BAYAR_TRANSFER = 0;
                                $TOTAL_BAYAR_KAS_KECIL = 0;
                                foreach ($Data_supplier_tagihan as $list_data) {

                                    $data_pembelian_per_spop = $this->Tbl_pembelian_model->get_by_uuid_spop($list_data->uuid_spop);

                                    // print_r($data_pembelian_per_spop);

                                    // PEMBAYARAN TRANSFER PER UID_SPOP
                                    $this->db->where('uuid_spop', $list_data->uuid_spop);
                                    $Query_data_pengajuan_bayar_by_uuid_spop = $this->db->get('tbl_pembelian_pengajuan_bayar');
                                    $Get_data_TERBAYAR_VIA_TRANSFER_by_uuid_spop = $Query_data_pengajuan_bayar_by_uuid_spop->result();


                                    // PEMBAYARAN KAS PER UUID_SPOP

                                    $this->db->where('uuid_spop', $list_data->uuid_spop);
                                    $Query_data_KAS_KECIL_by_uuid_spop = $this->db->get('tbl_kas_kecil');
                                    $Get_data_TERBAYAR_VIA_KAS_ECIL_by_uuid_spop = $Query_data_KAS_KECIL_by_uuid_spop->result();


                                ?>

                                    <tr>
                                        <td><?php echo ++$start ?></td>
                                        <td><?php echo date("d M Y", strtotime($data_pembelian_per_spop->tgl_po));  ?></td>
                                        <td><?php echo $data_pembelian_per_spop->spop ?></td>
                                        <td align="left"><?php echo $list_data->supplier_nama; ?></td>

                                        <!-- KOLOM PEMBELIAN -->
                                        <td align="right">
                                            <?php



                                            //   if ($list_data->nominal_pengajuan > 0) {
                                            //                 echo nominal($list_data->nominal_pengajuan);
                                            //                 $Total_pembelian = $Total_pembelian + $list_data->nominal_pengajuan;
                                            //             } else {
                                            echo nominal($list_data->total_pembelian);
                                            $Total_pembelian = $Total_pembelian + $list_data->total_pembelian;
                                            // }
                                            // echo "<br/>";
                                            // echo $Total_pembelian;

                                            ?>
                                        </td>


                                        <!-- KOLOM PEMBAYARAN TRANSFER -->

                                        <!-- CEK DATA PEMBAYARAN DI TABEL KAS KECIL, PEMBAYARAN PEMBELIAN, JIKA JUMLAH = TOTAL PEMBELIAN MAKA = LUNAS 
                                         
                                        ==> JUMLAH TOTAL PEMBAYARAN:

                                        
                                        -->

                                        <?php

                                        $sql_pembayaran = "SELECT `uuid_spop`, sum(`nominal_pengajuan`) as total_sudah_terbayar FROM `tbl_pembelian_pengajuan_bayar` WHERE `uuid_spop`='$uuid_spop' GROUP by `uuid_spop`";

                                        $Data_Pembayaran_uuid_spop = $this->db->query($sql_pembayaran)->row();

                                        ?>

                                        <td align="right">
                                            <?php
                                            // if ($list_data->statuslu == "U") {
                                            //     // echo nominal($list_data->nominal_pengajuan);

                                            //     // cek di tabel pengajuan bayar , apakah ada proses pembayaran dari fiel tanggal bayar

                                            //     // print_r($Query_data_pengajuan_bayar_by_uuid_spop->num_rows());

                                            //     if ($Query_data_pengajuan_bayar_by_uuid_spop->num_rows() > 0) {

                                            //         foreach ($Query_data_pengajuan_bayar_by_uuid_spop->result() as $list_data_pengajuan_bayar) {
                                            //             $Total_pengajuan_bayar = 0;
                                            //             if ($list_data_pengajuan_bayar->tgl_pembayaran) {
                                            //                 // echo "ada pembayaran";
                                            //                 // echo "<br/>";
                                            //                 $Total_pengajuan_bayar = $Total_pengajuan_bayar + $list_data_pengajuan_bayar->nominal_pengajuan;

                                            //                 echo $Total_pengajuan_bayar;
                                            //             }
                                            //         }
                                            //     }


                                            //     // echo number_format($list_data->nominal_pengajuan, 2, ',', '.');
                                            //     // $Total_pembayaran = $Total_pembayaran + $Total_pengajuan_bayar;

                                            // } elseif ($list_data->statuslu == "L" and $list_data->kas_bank == "kas") {

                                            //     // echo nominal($list_data->total_pembelian);
                                            //     // if ($list_data->nominal_pengajuan > 0) {
                                            //     //     echo number_format($list_data->nominal_pengajuan, 2, ',', '.');
                                            //     //     $Total_pembayaran = $Total_pembayaran + $list_data->nominal_pengajuan;
                                            //     // } else {
                                            //     echo number_format($list_data->total_pembelian, 2, ',', '.');
                                            //     $Total_pembayaran = $Total_pembayaran + $list_data->total_pembelian;
                                            //     // }

                                            // } elseif ($list_data->statuslu == "L" and $list_data->kas_bank == "bank") {

                                            //     // echo nominal($list_data->total_pembelian);
                                            //     // if ($list_data->nominal_pengajuan > 0) {
                                            //     //     echo number_format($list_data->nominal_pengajuan, 2, ',', '.');
                                            //     //     $Total_pembayaran = $Total_pembayaran + $list_data->nominal_pengajuan;
                                            //     // } else {
                                            //     echo number_format($list_data->total_pembelian, 2, ',', '.');
                                            //     $Total_pembayaran = $Total_pembayaran + $list_data->total_pembelian;
                                            //     // }

                                            // } elseif ($list_data->statuslu == "L") {

                                            //     // echo nominal($list_data->total_pembelian);
                                            //     // if ($list_data->nominal_pengajuan > 0) {
                                            //     //     echo number_format($list_data->nominal_pengajuan, 2, ',', '.');
                                            //     //     $Total_pembayaran = $Total_pembayaran + $list_data->nominal_pengajuan;
                                            //     // } else {
                                            //     echo number_format($list_data->total_pembelian, 2, ',', '.');
                                            //     $Total_pembayaran = $Total_pembayaran + $list_data->total_pembelian;
                                            //     // }
                                            // }

                                            // NOTE:
                                            //  L KAS ==> DARI TABEL KAS KECIL
                                            //  L BANK ?  ==> DARI FORM MANA ?
                                            // L ??? tanpa kas/bank
                                            $start_TRANSFER = 0;
                                            $uuid_SPOP_TRANSFER = 0;
                                            foreach ($Get_data_TERBAYAR_VIA_TRANSFER_by_uuid_spop as $list_data_TRANSFER) {
                                                if ($start_TRANSFER > 0) {
                                                    echo "<br/>";
                                                }

                                                // echo $list_data_TRANSFER->nominal_pengajuan;
                                                echo number_format($list_data_TRANSFER->nominal_pengajuan, 2, ',', '.');

                                                $uuid_SPOP_TRANSFER = $uuid_SPOP_TRANSFER + $list_data_TRANSFER->nominal_pengajuan;
                                                $TOTAL_BAYAR_TRANSFER = $TOTAL_BAYAR_TRANSFER + $list_data_TRANSFER->nominal_pengajuan;

                                                ++$start_TRANSFER;
                                            }


                                            ?>
                                        </td>

                                        <!-- Pembayaran kas kecil -->
                                        <td align="right">



                                            <?php
                                            $start_KAS_KECIL = 0;
                                            $UUID_SPOP_KAS_KECIL = 0;
                                            foreach ($Get_data_TERBAYAR_VIA_KAS_ECIL_by_uuid_spop as $list_data_KAS_KECIL) {
                                                if ($start_KAS_KECIL > 0) {
                                                    echo "<br/>";
                                                }

                                                // echo $list_data_KAS_KECIL->kredit;
                                                echo number_format($list_data_KAS_KECIL->kredit, 2, ',', '.');

                                                $UUID_SPOP_KAS_KECIL = $UUID_SPOP_KAS_KECIL + $list_data_KAS_KECIL->kredit;
                                                $TOTAL_BAYAR_KAS_KECIL = $TOTAL_BAYAR_KAS_KECIL + $list_data_KAS_KECIL->kredit;

                                                ++$start_KAS_KECIL;
                                            }

                                            ?>

                                        </td>

                                        <!-- KOLOM HUTANG -->
                                        <td align="right">
                                            <?php

                                            // if ($list_data->statuslu == "U") {

                                            //     // echo nominal($list_data->total_pembelian);
                                            //     // echo "&nbsp &nbsp";

                                            //     $TOTAL_Nominal_pengajuan = $this->Tbl_pembelian_pengajuan_bayar_model->get_sumNominal_by_uuid_spop($list_data->uuid_spop)->total_pengajuan;

                                            //     // echo $TOTAL_Nominal_pengajuan;
                                            //     // if($list_data->nominal_pengajuan > 0){

                                            //     if ($list_data->nominal_pengajuan > 0) {
                                            //         // echo anchor(site_url('tbl_pembelian/cetak_pengajuan_bayar_per_spop/' . $list_data->uuid_pengajuan_bayar), '<i class="fa fa-pencil-square-o" aria-hidden="true">CETAK PENGAJUAN</i>', 'class="btn btn-success btn-xs" target="_blank"');
                                            //         if ($TOTAL_Nominal_pengajuan < $list_data->total_pembelian) {
                                            //             // echo "&nbsp &nbsp";
                                            //             // echo $TOTAL_Nominal_pengajuan;
                                            //             // echo "&nbsp &nbsp";
                                            //             // echo $list_data->total_pembelian;
                                            //             // echo anchor(site_url('tbl_pembelian/create_pembayaran/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">Buat Pengajuan Pembayaran</i>', 'class="btn btn-warning btn-xs"');
                                            //             // echo "&nbsp &nbsp";
                                            //             // echo "-" . nominal($list_data->total_pembelian - $TOTAL_Nominal_pengajuan);
                                            //             echo "<font color='red'> -" .  number_format($list_data->total_pembelian - $TOTAL_Nominal_pengajuan, 2, ',', '.') . "</font>";

                                            //             // $Total_kekurangan = $Total_kekurangan + ($list_data->total_pembelian - $TOTAL_Nominal_pengajuan);
                                            //         }
                                            //     } else {
                                            //         // echo anchor(site_url('tbl_pembelian/create_pembayaran/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">Buat Pengajuan Pembayaran</i>', 'class="btn btn-warning btn-xs"');
                                            //         // echo "&nbsp &nbsp";
                                            //         echo "<font color='red'> -" .  number_format($list_data->total_pembelian - $TOTAL_Nominal_pengajuan, 2, ',', '.') . "</font>";

                                            //         // $Total_kekurangan = $Total_kekurangan + $list_data->total_pembelian - $TOTAL_Nominal_pengajuan;
                                            //     }
                                            // } else {
                                            //     echo "LUNAS";
                                            // }

                                            $GET_KEKURANGAN = $list_data->total_pembelian - ($uuid_SPOP_TRANSFER + $UUID_SPOP_KAS_KECIL);
                                            if ($GET_KEKURANGAN > 0) {
                                                // echo $GET_KEKURANGAN;
                                                echo "<font color='red'> -" .  number_format($GET_KEKURANGAN, 2, ',', '.') . "</font>";
                                                $Total_kekurangan = $Total_kekurangan + $GET_KEKURANGAN;
                                                // echo $Total_kekurangan;
                                            } else {
                                                echo "LUNAS";
                                            }
                                            ?>
                                        </td>

                                        <!-- KOLOM STATUS KAS/BANK  -->
                                        <!-- <td align="left">
                                            <?php
                                            // echo $list_data->kas_bank;
                                            ?>
                                        </td> -->

                                        <!-- KOLOM CETAK PENGAJUAN -->
                                        <td align="right">
                                            <?php

                                            // if ($list_data->statuslu == "U") {


                                            //     if ($Query_data_pengajuan_bayar_by_uuid_spop->num_rows() > 0) {

                                            //         foreach ($Query_data_pengajuan_bayar_by_uuid_spop->result() as $list_data_pengajuan_bayar) {
                                            //             $Total_pengajuan_bayar = 0;
                                            //             if ($list_data_pengajuan_bayar->tgl_pembayaran) {
                                            //                 // echo "ada cetak pengajuan";
                                            //                 // echo "<br/>";
                                            //                 $Total_pengajuan_bayar = $Total_pengajuan_bayar + $list_data_pengajuan_bayar->nominal_pengajuan;

                                            //                 // echo $Total_pengajuan_bayar;
                                            //                 // echo "<br/>";

                                            //                 echo anchor(site_url('tbl_pembelian/cetak_pengajuan_bayar_per_spop/' . $list_data->uuid_pengajuan_bayar), '<i class="fa fa-pencil-square-o" aria-hidden="true">CETAK PENGAJUAN ' . number_format($Total_pengajuan_bayar, 2, ',', '.') . '</i>', 'class="btn btn-success btn-xs" target="_blank"');
                                            //             }
                                            //         }
                                            //     }




                                            //     if ($list_data->nominal_pengajuan > 0) {
                                            //         echo anchor(site_url('tbl_pembelian/cetak_pengajuan_bayar_per_spop/' . $list_data->uuid_pengajuan_bayar), '<i class="fa fa-pencil-square-o" aria-hidden="true">CETAK PENGAJUAN</i>', 'class="btn btn-success btn-xs" target="_blank"');
                                            //         if ($TOTAL_Nominal_pengajuan < $list_data->total_pembelian) {
                                            //             echo anchor(site_url('tbl_pembelian/create_pembayaran/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">Buat Pengajuan Pembayaran</i>', 'class="btn btn-warning btn-xs"');
                                            //         }
                                            //     } else {
                                            //         echo anchor(site_url('tbl_pembelian/create_pembayaran/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">Buat Pengajuan Pembayaran</i>', 'class="btn btn-warning btn-xs"');
                                            //     }
                                            // }


                                            if ($GET_KEKURANGAN > 0) {


                                                if ($Query_data_pengajuan_bayar_by_uuid_spop->num_rows() > 0) {

                                                    foreach ($Query_data_pengajuan_bayar_by_uuid_spop->result() as $list_data_pengajuan_bayar) {
                                                        $Total_pengajuan_bayar = 0;
                                                        if ($list_data_pengajuan_bayar->tgl_pembayaran) {
                                                            // echo "ada cetak pengajuan";
                                                            // echo "<br/>";
                                                            $Total_pengajuan_bayar = $Total_pengajuan_bayar + $list_data_pengajuan_bayar->nominal_pengajuan;

                                                            // echo $Total_pengajuan_bayar;
                                                            // echo "<br/>";

                                                            echo anchor(site_url('tbl_pembelian/cetak_pengajuan_bayar_per_spop/' . $list_data_pengajuan_bayar->uuid_pengajuan_bayar), '<i class="fa fa-pencil-square-o" aria-hidden="true">CETAK PENGAJUAN ' . number_format($Total_pengajuan_bayar, 2, ',', '.') . '</i>', 'class="btn btn-success btn-xs" target="_blank"');
                                                        }
                                                    }
                                                }

                                                if ($list_data->uuid_spop) {
                                                    echo anchor(site_url('tbl_pembelian/create_pembayaran/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">Buat Pengajuan Pembayaran</i>', 'class="btn btn-warning btn-xs" target="_blank"');
                                                }

                                                // echo $list_data->nominal_pengajuan;

                                                // if ($list_data->nominal_pengajuan > 0) {

                                                //     // echo anchor(site_url('tbl_pembelian/cetak_pengajuan_bayar_per_spop/' . $list_data->uuid_pengajuan_bayar), '<i class="fa fa-pencil-square-o" aria-hidden="true">CETAK PENGAJUAN</i>', 'class="btn btn-success btn-xs" target="_blank"');

                                                //     if ($TOTAL_Nominal_pengajuan < $list_data->total_pembelian) {
                                                //         echo anchor(site_url('tbl_pembelian/create_pembayaran/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">Buat Pengajuan Pembayaran X</i>', 'class="btn btn-warning btn-xs" target="_blank"');
                                                //     }
                                                // } else {
                                                //     echo anchor(site_url('tbl_pembelian/create_pembayaran/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">Buat Pengajuan Pembayaran YY</i>', 'class="btn btn-warning btn-xs" target="_blank"');
                                                // }
                                            }



                                            ?>
                                        </td>


                                    </tr>
                                <?php
                                }
                                ?>


                            </tbody>

                            <tfoot>
                                <tr>
                                    <th style="text-align:center" width="10px"></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th style="text-align:right"><?php echo number_format($Total_pembelian, 2, ',', '.'); ?> </th>
                                    <th style="text-align:right"><?php echo number_format($TOTAL_BAYAR_TRANSFER, 2, ',', '.'); ?></th>
                                    <th style="text-align:right"><?php echo number_format($TOTAL_BAYAR_KAS_KECIL, 2, ',', '.'); ?></th>
                                    <th style="text-align:right"><?php echo "<font color='red'> -" .  number_format($Total_kekurangan, 2, ',', '.') . "</font>"; ?></th>
                                    <!-- <th style="text-align:right"></th> -->
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
            "scrollY": 300,
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