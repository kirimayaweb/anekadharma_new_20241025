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
                                    <div class="col-12" text-align="center"> Group Data</div>
                                </div>


                            </div>
                            <div class="col-6">

                            </div>



                        </div>
                        <div class="row">
                            <div class="col-6">
                                <?php echo anchor(site_url('Tbl_accounting_group/create'), 'Input Group Laporan baru', 'class="btn btn-danger"'); ?>
                            </div>
                            <div class="col-4">

                            </div>
                            <div class="col-2">
                                <?php //echo anchor(site_url('tbl_pembelian/excel'), 'Cetak ke Excel', 'class="btn btn-success"'); ?>
                            </div>



                        </div>



                    </div>
                    <br />



                    <div class="card-body">

                        <table id="exampleFreeze" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="text-align:center" width="10px">No</th>
                                   
                                    <th>Group Laporan</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                           $start=0;
                                foreach ($data_group as $list_data) {
                                    
                                        ?>
                                        
                                    <tr>
                                        
                                        <td align="left"><?php echo ++$start ?></td>
                                        <td align="left"><?php echo $list_data->nama_group; ?></td>
                                        <td align="left"><?php echo $list_data->status; ?></td>
                                        <td align="left"><?php echo $list_data->keterangan; ?></td>
                                        
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