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

                            <div class="col-3" text-align="center">
                                <strong>KAS KECIL: <?php echo date("F Y", strtotime($bulan_data))  ?></strong>
                            </div>

                            <div class="col-8">
                                <form action="<?php echo $action_by_bulan; ?>" method="post">
                                    <div class="row">
                                        <div class="col-1" text-align="right"> <strong> </strong></div>
                                        <div class="col-4" text-align="left">

                                            <!-- <form action="/action_page.php"> -->
                                                <label for="bulan">BULAN :</label>
                                                <input type="month" id="bulan_pembelian" name="bulan_pembelian">
                                                <!-- <input type="submit"> -->
                                            <!-- </form> -->

                                        </div>
                                        <div class="col-2" text-align="right">

                                            <?php //echo anchor(site_url('Sys_supplier/stock/'), 'CARI', 'class="btn btn-danger"');
                                            ?>

                                            <button type="submit" class="btn btn-danger"> Cari</button>

                                        </div>
                                    </div>

                                </form>
                            </div>


                        </div>




                    </div>
                    <div class="row">
                        <div class="col-6">
                            <?php //echo anchor(site_url('tbl_pembelian/create'), 'Input Pembelian (Belanja Perusahaan)', 'class="btn btn-danger"'); 
                            ?>
                        </div>
                        <div class="col-4">

                        </div>
                        <div class="col-2">
                            <?php //echo anchor(site_url('tbl_pembelian/excel'), 'Cetak ke Excel', 'class="btn btn-success"'); 
                            ?>
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

                                $list_spop_status_lu = $list_data->statuslu;
                            ?>

                                <tr>

                                    <td><?php echo ++$start ?></td>
                                    <td><?php
                                        echo date("d M Y", strtotime($list_data->tgl_po));
                                        // echo " ";
                                        // echo anchor(site_url('tbl_pembelian/update_per_spop/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">UPDATE</i>', 'class="btn btn-warning btn-xs"');

                                        ?>


                                    </td>
                                    <td align="center">
                                        <?php
                                        echo $list_data->spop;
                                        // $x_button = $x_button + 1;
                                        // echo "  ";
                                        // if ($list_data->status_spop) {
                                        //     echo anchor(site_url('tbl_pembelian/update_status_per_spop/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">' . $list_data->status_spop . '</i>', 'class="btn btn-success btn-xs"');
                                        // } else {
                                        //     echo anchor(site_url('tbl_pembelian/update_status_per_spop/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">STATUS</i>', 'class="btn btn-danger btn-xs"');
                                        // }

                                        ?>
                                    </td>

                                    <!-- <td align="center"><?php //echo $list_data->nmrsj; 
                                                            ?></td> -->
                                    <td align="center"><?php echo $list_data->nmrfakturkwitansi; ?></td>
                                    <!-- <td align="center"><?php //echo $list_data->nmrbpb; 
                                                            ?></td> -->

                                    <td align="left"><?php echo $list_data->supplier_nama; ?></td>




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
                                    <td align="center"><?php echo $list_data->kas_bank; ?></td>
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