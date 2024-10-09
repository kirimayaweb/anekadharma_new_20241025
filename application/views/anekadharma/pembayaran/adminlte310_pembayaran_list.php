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
                                    <th>SPOP</th>
                                    <th>Nama SUPPLIER</th>
                                    <th>Jumlah Tagihan</th>
                                    <th>Pembayaran</th>
                                    <th>Action</th>

                                </tr>
                            </thead>



                            <?php

                            ?>
                            <tbody>
                                <?php
                                $start = 0;
                                foreach ($Data_supplier_tagihan as $list_data) {
                                    $data_pembelian_per_spop = $this->Tbl_pembelian_model->get_by_uuid_spop($list_data->uuid_spop);
                                ?>

                                    <tr>
                                        <td><?php echo ++$start ?></td>
                                        <td><?php echo $data_pembelian_per_spop->spop ?></td>
                                        <td align="left"><?php echo $list_data->supplier_nama; ?></td>

                                        <td align="left">
                                            <?php
                                            
                                            echo nominal($list_data->total_pembelian);
                                            // echo "&nbsp &nbsp";

                                           

                                            ?>
                                        </td>

                                        <td align="right">
                                            <?php
                                            echo nominal($list_data->nominal_pengajuan);
                                            ?>
                                        </td>

                                        <td align="left">
                                            <?php
                                            
                                            // echo nominal($list_data->total_pembelian);
                                            // echo "&nbsp &nbsp";

                                            $TOTAL_Nominal_pengajuan = $this->Tbl_pembelian_pengajuan_bayar_model->get_sumNominal_by_uuid_spop($list_data->uuid_spop)->total_pengajuan;
                                            // echo $TOTAL_Nominal_pengajuan;
                                            // if($list_data->nominal_pengajuan > 0){
                                            if ($list_data->nominal_pengajuan > 0) {
                                                echo anchor(site_url('tbl_pembelian/cetak_pengajuan_bayar_per_spop/' . $list_data->uuid_pengajuan_bayar), '<i class="fa fa-pencil-square-o" aria-hidden="true">CETAK PENGAJUAN</i>', 'class="btn btn-success btn-xs" target="_blank"');
                                                if ($TOTAL_Nominal_pengajuan < $list_data->total_pembelian) {
                                                    // echo "&nbsp &nbsp";
                                                    // echo $TOTAL_Nominal_pengajuan;
                                                    // echo "&nbsp &nbsp";
                                                    // echo $list_data->total_pembelian;
                                                    echo anchor(site_url('tbl_pembelian/create_pembayaran/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">Buat Pengajuan Pembayaran</i>', 'class="btn btn-warning btn-xs"');
                                                    echo "&nbsp &nbsp";
                                                    // echo "-" . nominal($list_data->total_pembelian - $TOTAL_Nominal_pengajuan);
                                                    echo "<font color='red'> -". nominal($list_data->total_pembelian - $TOTAL_Nominal_pengajuan) ."</font>";
                                                    
                                                }
                                            } else {
                                                echo anchor(site_url('tbl_pembelian/create_pembayaran/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">Buat Pengajuan Pembayaran</i>', 'class="btn btn-warning btn-xs"');
                                                echo "&nbsp &nbsp";
                                                echo "<font color='red'> -". nominal($list_data->total_pembelian - $TOTAL_Nominal_pengajuan) ."</font>";
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

        <hr>

        <div class="box box-warning box-solid">

            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <div class="row">
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="row">
                                    <div class="col-12" text-align="center"> <strong>DATA PENJUALAN KE KONSUMEN</strong></div>
                                </div>


                            </div>
                            <div class="col-6">

                            </div>



                        </div>



                    </div>
                    <br />



                    <div class="card-body">

                        <table id="example" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="text-align:center" width="10px">No</th>
                                    <!-- <th style="text-align:center" width="100px">Action</th> -->
                                    <th>Nama KONSUMEN/UNIT</th>
                                    <th>Total Belanja</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $start = 0;
                                foreach ($Data_konsumen_tagihan as $list_data) {

                                ?>

                                    <tr>
                                        <td><?php echo ++$start ?></td>

                                        <td align="left">
                                            <?php
                                            echo $list_data->nama_konsumen;
                                            echo "&nbsp &nbsp";
                                            echo anchor(site_url('tbl_pembelian/pembayaran'), '<i class="fa fa-pencil-square-o" aria-hidden="true">PEMBAYARAN</i>', 'class="btn btn-warning btn-xs"');
                                            ?></td>
                                        <!-- <td align="left"><?php //echo $list_data->jumlah_belanja; 
                                                                ?></td> -->
                                        <td align="right"><?php echo nominal($list_data->total_belanja); ?></td>


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