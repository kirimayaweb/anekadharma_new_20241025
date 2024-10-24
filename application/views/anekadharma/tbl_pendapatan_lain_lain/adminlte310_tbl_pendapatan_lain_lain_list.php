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
                                <div class="col-12" text-align="center"> <strong>PENDAPATAN LAIN-LAIN</strong></div>
                            </div>
                            <div class="col-6">
                                <?php echo anchor(site_url('Tbl_pendapatan_lain_lain/create'), 'Input Pendapatan Lain-Lain', 'class="btn btn-danger"');
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


                        <!-- <table id="ExampleOnFile" class="table table-bordered" style="width:100%"> -->
                        <table class="table table-bordered" style="margin-bottom: 10px">
                            <tr>
                                <th>No</th>
                                <th>Uuid Pendapatan Lain Lain</th>
                                <th>Tgl Transaksi</th>
                                <th>Kode</th>
                                <th>Dari</th>
                                <th>Uraian</th>
                                <th>Nominal</th>
                                <th>Bank</th>
                                <th>Nmr Rekening</th>
                                <th>Action</th>
                            </tr><?php
                                    foreach ($pendapatan_lain_lain_data as $tbl_pendapatan_lain_lain) {
                                    ?>
                                <tr>
                                    <td width="80px"><?php echo ++$start ?></td>
                                    <td><?php echo $tbl_pendapatan_lain_lain->uuid_pendapatan_lain_lain ?></td>
                                    <td><?php echo $tbl_pendapatan_lain_lain->tgl_transaksi ?></td>
                                    <td><?php echo $tbl_pendapatan_lain_lain->kode ?></td>
                                    <td><?php echo $tbl_pendapatan_lain_lain->dari ?></td>
                                    <td><?php echo $tbl_pendapatan_lain_lain->uraian ?></td>
                                    <td><?php echo $tbl_pendapatan_lain_lain->nominal ?></td>
                                    <td><?php echo $tbl_pendapatan_lain_lain->bank ?></td>
                                    <td><?php echo $tbl_pendapatan_lain_lain->nmr_rekening ?></td>
                                    <td style="text-align:center" width="200px">
                                        <?php
                                        echo anchor(site_url('tbl_pendapatan_lain_lain/read/' . $tbl_pendapatan_lain_lain->id), 'Read');
                                        echo ' | ';
                                        echo anchor(site_url('tbl_pendapatan_lain_lain/update/' . $tbl_pendapatan_lain_lain->id), 'Update');
                                        echo ' | ';
                                        echo anchor(site_url('tbl_pendapatan_lain_lain/delete/' . $tbl_pendapatan_lain_lain->id), 'Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
                                        ?>
                                    </td>
                                </tr>
                            <?php
                                    }
                            ?>
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