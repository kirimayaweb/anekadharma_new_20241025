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
                                    <div class="col-5" text-align="center"> <strong>BUKU BANK</strong></div>
                                    <div class="col-7" text-align="center"> <strong><?php //echo anchor(site_url('tbl_penjualan/create'), 'Input PENJUALAN BARU', 'class="btn btn-danger"'); ?></strong></div>

                                </div>


                            </div>
                            <div class="col-4">

                            </div>

                            <div class="col-2">
                                <?php //echo anchor(site_url('tbl_penjualan/RekapPenjualanPerBarang'), 'Rekap Penjualan', 'class="btn btn-success"'); ?>
                            </div>

                            <div class="col-2">
                                <?php //echo anchor(site_url('tbl_penjualan/excel'), 'Cetak ke Excel', 'class="btn btn-success"'); ?>
                            </div>


                        </div>




                    </div>




                    <div class="card-body">

                        <table id="tglSPOPFreeze" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="text-align:center" width="10px">No</th>
                                    <!-- <th style="text-align:center" width="100px">Action</th> -->
                                    <th rowspan="2">Tanggal</th>

                                    <th colspan="2" style="text-align:center">rekening</th>

                                    <th rowspan="2">keterangan</th>
                                    <th rowspan="2">kode</th>
                                    <th rowspan="2">debet</th>
                                    <th rowspan="2">kredit</th>
                                    <th rowspan="2">saldo</th>




                                <tr>
                                    <th>Bank</th>
                                    <th>Nomor rekening</th>
                                </tr>

                                <!-- -------------- -->


                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                $start = 0;
                                foreach ($data_buku_bank as $list_data) {
                                ?>
                                    <tr>
                                        <td><?php echo ++$start; ?></td>
                                        <td>tanggal</td>
                                        <td>bank</td>
                                        <td>nomor rek</td>
                                        <td>Keterangan</td>
                                        <td>Kode</td>
                                        <td>Debet</td>
                                        <td>Kredit</td>
                                        <td>Saldo</td>


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
            "scrollY": 1100,
            "scrollX": true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#example9').DataTable({
            "scrollY": 1100,
            "scrollX": true
        });
    });
</script>