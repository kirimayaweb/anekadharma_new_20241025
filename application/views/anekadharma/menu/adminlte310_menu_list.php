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
                                <div class="col-12" text-align="center"> <strong>LIST MENU</strong></div>
                            </div>
                            <div class="col-6">
                                <?php echo anchor(site_url('Menu/create'), 'Input Menu Baru', 'class="btn btn-danger"');
                                ?>
                            </div>
                            <div class="col-2">
                                <?php //echo anchor(site_url('Tbl_neraca_data/create'), 'Input Pembelian (Belanja Perusahaan)', 'class="btn btn-danger"');
                                ?>
                            </div>



                        </div>
                        <div class="row">
                            <div class="col-6">

                            </div>
                            <div class="col-4">

                            </div>
                            <div class="col-2">
                                <?php //echo anchor(site_url('Tbl_neraca_data/excel'), 'Cetak ke Excel', 'class="btn btn-success"'); 
                                ?>
                            </div>



                        </div>



                    </div>
                    <br />



                    <div class="card-body">


                        <!-- <table class="table table-bordered" style="margin-bottom: 10px"> -->
                        <!-- <table id="example9" class="table table-bordered" style="width:100%"> -->
                        <table id="ExampleOnFile" class="table table-bordered" style="width:100%">
                            <!-- <table class="table table-bordered table-striped" id="mytable"> -->
                            <thead>
                                <tr>
                                    <th width="10px">No</th>
                                    <th>Nama Menu</th>
                                    <th>Link</th>
                                    <th width="30">Icon</th>
                                    <th>Aktif</th>
                                    <th>Parent</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $start = 0;
                                foreach ($menu_data as $menu) {
                                    $active = $menu->is_active == 1 ? 'AKTIF' : 'TIDAK AKTIF';
                                    $parent = $menu->is_parent > 1 ? 'MAINMENU' : 'SUBMENU'
                                ?>
                                    <tr>
                                        <td><?php echo ++$start ?></td>
                                        <td><?php echo $menu->name ?></td>
                                        <td><?php echo $menu->link ?></td>
                                        <td><i class='<?php echo $menu->icon ?>'></i></td>
                                        <td><?php echo $active ?></td>
                                        <td><?php echo $parent ?></td>
                                        <td style="text-align:center" width="140px">
                                            <?php
                                            echo anchor(site_url('menu/read/' . $menu->id), '<i class="fa fa-eye"></i>', array('title' => 'detail', 'class' => 'btn btn-danger btn-sm'));
                                            echo '  ';
                                            echo anchor(site_url('menu/update/' . $menu->id), '<i class="fa fa-pencil-square-o"></i>', array('title' => 'edit', 'class' => 'btn btn-danger btn-sm'));
                                            echo '  ';
                                            echo anchor(site_url('menu/delete/' . $menu->id), '<i class="fa fa-trash-o"></i>', 'title="delete" class="btn btn-danger btn-sm" onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
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
            "scrollY": 250,
            "scrollX": true
        });
    });



    $(document).ready(function() {
        var table = $('#ExampleOnFile').DataTable({
            scrollX: true,
            scrollY: "400px",
            scrollCollapse: true,
            paging: true,
            // columnDefs: [
            //     { orderable: false, targets: 0 },
            //      { orderable: false, targets: -1 }
            //  ],
            //  ordering: [[ 1, 'asc' ]],
            // colReorder: {
            //     fixedColumnsLeft: 1,
            //      fixedColumnsRight: 1
            // }
        });

        new $.fn.dataTable.FixedColumns(table, {
            leftColumns: 3,
            // rightColumns: 1
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