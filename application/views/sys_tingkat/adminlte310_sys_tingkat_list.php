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
                            <div class="col-8">
                                <div class="row">
                                    <div class="col-12"> DATA TINGKAT</div>
                                </div>
                            </div>
                            <div class="col-2">

                                <!-- <select name="level_sekolah" id="level_sekolah" class="form-control select2" style="width: 200px; height: 10px;">
                                        <option value="">Pilih Tingkat</option>
                                        <option value="MA">MA</option>
                                        <option value="MTS">MTS</option>
                                        <option value="MI">MI</option>
                                        <option value="SMP">SMP</option>
                                        <option value="SD">SD</option>
                                    </select>
                                    <button type="submit" class="btn btn-danger"> Cari</button> -->
                            </div>
                            <!-- <div class="col-md-2  card-title"></div> -->
                            <div class="col-2">

                            </div>


                        </div>

                    </div>
                    <br />

                    <div class="row">
                        <div class="col-2"><?php //echo anchor(site_url('Tbl_sales/create'), '<i class="fa fa-wpforms" aria-hidden="true"></i> Tambah Data Sales', 'class="btn btn-danger btn-sm"');
                                            ?></div>
                        <div class="col-2" align="right">
                            <?php
                            // echo anchor(
                            //                                         site_url('tbl_stok_barang_detail/excel_stock/' . $tingkat_selected),
                            //                                         '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Ms Excel, REKAP CETAK :' .
                            //                                             $tingkat_selected . ' Tahun : ' . $_SESSION['thn_selected'] . ' Semester :' . $_SESSION['semester_selected'],
                            //                                         'class="btn btn-success btn-sm"'
                            //                                     ); 
                            ?>
                        </div>
                        <div class="col-2" align="left">
                            <?php
                            // echo anchor(site_url('tbl_stok_barang_detail/get_data_stock_rekap/' . $tingkat_selected), '<i class="fa fa-file-word-o" aria-hidden="true"></i> REKAP STOCK', 'class="btn btn-primary btn-sm"');
                            ?>
                        </div>
                        <div class="col-2" align="right">
                            <?php
                            // echo anchor(site_url('Trans_cetakinput/create_setting'), '<i class="fa fa-file-excel-o" aria-hidden="true"></i> PO Cetak', 'class="btn btn-success btn-sm"'); 
                            ?>
                        </div>
                        <div class="col-2" align="left">
                        <?php //echo anchor(site_url('Tbl_sales/kekurangankirim_global_ALL_by_tingkat'), '<i class="fa fa-wpforms" aria-hidden="true"></i> REKAP KEKURANGAN PENGIRIMAN', 'class="btn btn-warning btn-sm" target="_blank"');
                                            ?>
                        </div>
                        <div class="col-4"></div>

                    </div>

                    <div class="card-body">

                        <!-- <table id="example1" class="table table-bordered table-striped"> -->
                        <!-- <table id="example" class="display nowrap" style="width:100%"> -->
                        <table id="exampleFreeze" class="display nowrap" style="width:100%">
                            <thead>

                                <tr>
                                    <th style="text-align:center" width="10px">No</th>
                                    <!-- <th>Uuid Tingkat</th>
                                    <th>Tingkat System</th> -->
                                    <th>Tingkat</th>
                                    <th>Action</th>


                                </tr>
                            </thead>

                            <?php
                            
                                // print_r($tbl_sales_data);

                            ?>


                            <tbody>

                                <?php
                                foreach ($sys_tingkat_data as $sys_tingkat) {
                                ?>
                                    <tr>
                                            <td><?php echo ++$start ?></td>
                                            <!-- <td><?php //echo $sys_tingkat->uuid_tingkat ?></td>
                                            <td><?php //echo $sys_tingkat->tingkat_system ?></td> -->
                                            <td><?php echo $sys_tingkat->tingkat ?></td>
                                            <td style="text-align:center" width="140px">
                                            <?php 
                                            echo anchor(site_url('sys_tingkat/read/'.$sys_tingkat->id),'<i class="fa fa-eye"></i>',array('title'=>'detail','class'=>'btn btn-danger btn-sm')); 
                                            echo '  '; 
                                            echo anchor(site_url('sys_tingkat/update/'.$sys_tingkat->id),'<i class="fa fa-pencil-square-o"></i>',array('title'=>'edit','class'=>'btn btn-danger btn-sm')); 
                                            echo '  '; 
                                            echo anchor(site_url('sys_tingkat/delete/'.$sys_tingkat->id),'<i class="fa fa-trash-o"></i>','title="delete" class="btn btn-danger btn-sm" onclick="javasciprt: return confirm(\'Are You Sure ?\')"'); 
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

