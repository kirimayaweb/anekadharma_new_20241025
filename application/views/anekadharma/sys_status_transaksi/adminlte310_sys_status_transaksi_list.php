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
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="col-12" text-align="center"> <strong>DATA STATUS TRANSAKSI</strong></div>
                                        <?php echo anchor(site_url('Sys_status_transaksi/create'), 'Tambah Status Baru', 'class="btn btn-danger"'); 
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

                                                <div class="row">
                                                    <!-- <div class="col-1"></div> -->
                                                    <div class="col-6">
                                                        <?php //echo anchor(site_url('Sys_unit_produk/create_unit/'.$uuid_unit_selected), 'Input Hasil / Produk Unit: ' . $nama_unit, 'class="btn btn-success"'); 
                                                        ?>
                                                    </div>
                                                </div>

                                                <table id="example" class="display nowrap" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th style="text-align:center" width="80px">No</th>
                                                            <!-- <th>Uuid Gudang</th> -->
                                                            <th>Status</th>
                                                            

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $start = 0;

                                                        foreach ($sys_status_transaksi_data as $list_data) {
                                                        ?>
                                                            <tr>
                                                                <td style="text-align:center"><?php echo ++$start ?></td>
                                                                <!-- <td style="text-align:center"><?php //echo $list_data->uuid_gudang 
                                                                                                    ?></td> -->
                                                                <td style="text-align:left"><?php echo $list_data->status ?> </td>

                                                            </tr>
                                                        <?php
                                                        }
                                                        ?>


                                                    </tbody>

                                                    <!-- <tfoot>
                                                        <tr>
                                                            <th width="10px"></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th style="text-align:right"></th>
                                                        </tr>

                                                    </tfoot> -->



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