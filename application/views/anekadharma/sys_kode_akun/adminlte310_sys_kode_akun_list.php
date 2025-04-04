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
                                <div class="col-12" text-align="center"> <strong>DATA KODE AKUN</strong></div>
                            </div>
                            <div class="col-6">
                                <?php echo anchor(site_url('Sys_kode_akun/create'), 'Input Kode Akun', 'class="btn btn-danger"');
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
                        <table id="ExampleOnFile" class="table table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <!-- <th>Uuid Kode Akun</th> -->
                                    <th>Kode Akun</th>
                                    <th>Nama Akun</th>
                                    <th>Group</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $start = 0;
                                foreach ($sys_kode_akun_data as $sys_kode_akun) {
                                ?>
                                    <tr>
                                        <td width="80px"><?php echo ++$start ?></td>
                                        <!-- <td><?php //echo $sys_kode_akun->uuid_kode_akun 
                                                    ?></td> -->
                                        <td><?php echo $sys_kode_akun->kode_akun ?></td>
                                        <td><?php echo $sys_kode_akun->nama_akun ?></td>
                                        <td><?php echo $sys_kode_akun->group ?></td>
                                        <td style="text-align:center" width="200px">
                                            <?php
                                            // echo anchor(site_url('sys_kode_akun/read/' . $sys_kode_akun->id), 'Read');
                                            // echo ' | ';
                                            echo anchor(site_url('sys_kode_akun/update/' . $sys_kode_akun->id), 'Update');
                                            // echo ' | ';
                                            // echo anchor(site_url('sys_kode_akun/delete/' . $sys_kode_akun->id), 'Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
                                            ?>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            <tbody>
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