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
                        <h3 class="card-title">DATA GUDANG</h3>
                    </div>
                    <br />



                    <div class="card-body">

                        <div class="col-2"><?php echo anchor(site_url('Tbl_gudang/create'), '<i class="fa fa-wpforms" aria-hidden="true"></i> Tambah Data Gudang', 'class="btn btn-danger btn-sm"'); ?></div>

                        <!-- <table id="example1" class="table table-bordered table-striped"> -->
                        <table id="exampleFreeze3" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <!-- <th>Uuid Gudang</th> -->
                                    <!-- <th>Date Input</th> -->
                                    <!-- <th>Id User Input</th> -->
                                    <th>Nama Gudang</th>
                                    <th>Alamat Gudang</th>
                                    <th>Notelp Gudang</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $start = 0;
                                foreach ($tbl_gudang_data as $tbl_gudang) {
                                ?>
                                    <tr>
                                        <td width="10px"><?php echo ++$start ?></td>
                                        <!-- <td><?php //echo $tbl_gudang->uuid_gudang 
                                                    ?></td> -->
                                        <!-- <td><?php //echo $tbl_gudang->date_input 
                                                    ?></td> -->
                                        <!-- <td><?php //echo $tbl_gudang->id_user_input 
                                                    ?></td> -->
                                        <td><?php echo $tbl_gudang->nama_gudang ?></td>
                                        <td><?php echo $tbl_gudang->alamat_gudang ?></td>
                                        <td><?php echo $tbl_gudang->notelp_gudang ?></td>
                                        <td><?php echo $tbl_gudang->keterangan ?></td>
                                        <td style="text-align:center" width="200px">
                                            <?php
                                            echo anchor(site_url('tbl_gudang/read/' . $tbl_gudang->id), '<i class="fa fa-eye" aria-hidden="true"></i>', 'class="btn btn-danger btn-sm"');
                                            echo '  ';
                                            echo anchor(site_url('tbl_gudang/update/' . $tbl_gudang->id), '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', 'class="btn btn-danger btn-sm"');
                                            // echo '  ';
                                            // echo anchor(site_url('tbl_gudang/delete/' . $tbl_gudang->id), '<i class="fa fa-trash-o" aria-hidden="true"></i>', 'class="btn btn-danger btn-sm" Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
                                            ?>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>


                            <!-- <tfoot>
                                    <tr>
                                        <th>Rendering engine</th>
                                        <th>Browser</th>
                                        <th>Platform(s)</th>
                                        <th>Engine version</th>
                                        <th>CSS grade</th>
                                    </tr>
                                </tfoot> -->
                        </table>


                    </div>

                    <!-- /.card-body -->
                </div>

            </div>

        </div>
    </section>
</div>

<!-- =============== -->




<script>

    // BOOTSTRAP 3
    // $(document).ready(function() {
    //     var table = $('#example').DataTable( {
    //         scrollY:        "300px",
    //         scrollX:        true,
    //         scrollCollapse: true,
    //         paging:         false,
    //         fixedColumns:   true
    //     } );
    // } );


    // BOOTSTRAP 5
    // $(document).ready(function() {
    //     var table = $('#example').DataTable( {
    //         scrollY:        "300px",
    //         scrollX:        true,
    //         scrollCollapse: true,
    //         paging:         true,
    //         leftColumns:2,
    //         fixedColumns:   true
    //     } );
    // } );

    $(document).ready(function() {
        var table = $('#exampleFreeze3').DataTable( {
            scrollX: true,
            scrollY:"300px",
            scrollCollapse: true,
            paging:true,
        // columnDefs: [
        //     { orderable: false, targets: 0 },
        //      { orderable: false, targets: -1 }
        //  ],
        //  ordering: [[ 1, 'asc' ]],
        // colReorder: {
        //     fixedColumnsLeft: 1,
        //      fixedColumnsRight: 1
        // }
        } );

        new $.fn.dataTable.FixedColumns( table, {
            leftColumns: 3,
            // rightColumns: 1
        } );
    } );

</script>