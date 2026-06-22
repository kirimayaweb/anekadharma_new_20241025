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
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-3" align="left">
                                        <div class="col-12" text-align="center"> <strong>DATA UANG MUKA DI DEPAN</strong></div>
                                    </div>
                                    <div class="col-9" align="left">
                                        <?php echo anchor(site_url('Tbl_uang_muka_didepan/pemasukan_uang_muka_didepan'), 'Pemasukan Uang Muka', 'class="btn btn-danger"');
                                        ?>
                                    </div>
                                </div>
                            </div>

                        </div>




                    </div>
                    <!-- <br /> -->



                    <div class="card-body">




                        <div class="row">
                            <div class="col-12 col-sm-12">
                                <div class="card card-primary card-tabs">

                                    <div class="card-body">
                                        <div class="tab-content" id="custom-tabs-one-tabContent">
                                            <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">

                                                <?php
                                                $url_excel = isset($url_uang_muka_didepan_excel)
                                                    ? $url_uang_muka_didepan_excel
                                                    : site_url('Tbl_uang_muka_didepan/excel');
                                                ?>
                                                <div class="row mb-3">
                                                    <div class="col-12 text-right">
                                                        <a href="<?php echo htmlspecialchars($url_excel, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-success" id="btn-uang-muka-didepan-excel">
                                                            <i class="fa fa-file-excel-o"></i> Cetak ke Excel
                                                        </a>
                                                    </div>
                                                </div>

                                                <table id="example" class="display nowrap" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <!-- <th>Uuid Uang Muka Didepan</th> -->
                                                            <th>Tgl Transaksi</th>
                                                            <th>Action</th>
                                                            <!-- <th>Kode</th> -->
                                                            <th>Dari</th>
                                                            <th>Uraian</th>
                                                            <th>Nominal</th>
                                                            <th>Bank</th>
                                                            <th>Nmr Rekening</th>


                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php

                                                        $start = 0;
                                                        $TotalNominal=0;
                                                        foreach ($tbl_uang_muka_didepan_data as $tbl_uang_muka_didepan) {
                                                        ?>
                                                            <tr>
                                                                <td width="80px"><?php echo ++$start ?></td>
                                                                <!-- <td><?php //echo $tbl_uang_muka_didepan->uuid_uang_muka_didepan 
                                                                            ?></td> -->
                                                                <td><?php echo date("d-m-Y", strtotime($tbl_uang_muka_didepan->tgl_transaksi)); ?></td>

                                                                <td style="text-align:center" width="200px">
                                                                    <?php
                                                                    // echo anchor(site_url('tbl_uang_muka_didepan/read/' . $tbl_uang_muka_didepan->id), 'Read');
                                                                    // echo ' | ';
                                                                    // echo anchor(site_url('tbl_uang_muka_didepan/update/' . $tbl_uang_muka_didepan->id), 'Update');
                                                                    // echo ' | ';
                                                                    echo anchor(site_url('tbl_uang_muka_didepan/delete/' . $tbl_uang_muka_didepan->id), 'Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
                                                                    ?>
                                                                </td>


                                                                <!-- <td><?php //echo $tbl_uang_muka_didepan->kode 
                                                                            ?></td> -->
                                                                <td><?php echo $tbl_uang_muka_didepan->dari ?></td>
                                                                <td><?php echo $tbl_uang_muka_didepan->uraian ?></td>
                                                                <td style="text-align:right">
                                                                    <?php 
                                                                    echo nominal($tbl_uang_muka_didepan->nominal); 
                                                                    $TotalNominal=$TotalNominal+$tbl_uang_muka_didepan->nominal;
                                                                    ?></td>
                                                                <td><?php echo $tbl_uang_muka_didepan->bank ?></td>
                                                                <td><?php echo $tbl_uang_muka_didepan->nmr_rekening ?></td>

                                                            </tr>
                                                        <?php
                                                        }
                                                        ?>


                                                    </tbody>

                                                    <tfoot>
                                                        <tr>
                                                            <th></th>
                                                            <!-- <th>Uuid Uang Muka Didepan</th> -->
                                                            <th></th>
                                                            <!-- <th>Kode</th> -->
                                                            <th></th>
                                                            <th></th>
                                                            <th>TOTAL</th>
                                                            <th style="text-align:right"><?php echo nominal($TotalNominal); ?></th>
                                                            <!-- <th></th> -->
                                                            <th></th>
                                                            <th></th>
                                                        </tr>

                                                    </tfoot>



                                                </table>


                                            </div>

                                        </div>
                                    </div>
                                    <!-- /.card -->
                                </div>
                            </div>

                        </div>



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