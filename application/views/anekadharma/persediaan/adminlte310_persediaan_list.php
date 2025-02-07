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
                        <form action="<?php echo $action_cari; ?>" method="post">
                            <div class="row">
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="col-12" text-align="center"> <strong>DATA PERSEDIAAN</strong></div>
                                </div>
                                <!-- <div class="col-6"> -->
                                <?php //echo anchor(site_url('Persediaan/create'), 'Input Persediaan', 'class="btn btn-danger"');
                                ?>

                                <div class="col-4" text-align="left">

                                    <!-- <form action="/action_page.php"> -->
                                    <label for="bulan">BULAN :</label>
                                    <input type="month" id="bulan_persediaan" name="bulan_persediaan">
                                    <!-- <input type="submit"> -->
                                    <!-- </form> -->

                                </div>

                                <!-- </div> -->
                                <div class="col-2">
                                    <?php //echo anchor(site_url('Tbl_neraca_data/create'), 'Input Pembelian (Belanja Perusahaan)', 'class="btn btn-danger"');
                                    ?>
                                    <button type="submit" class="btn btn-danger"> Cari</button>
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
                        </form>


                    </div>
                    <br />



                    <div class="card-body">


                        <table id="example" class="table table-bordered" style="width:100%">
                            <!-- <table class="table table-bordered table-striped" id="mytable"> -->
                            <thead>
                                <tr>
                                    <th width="80px">No</th>
                                    <!-- <th>Id</th> -->
                                    <th>Tanggal</th>
                                    <th>Kode</th>
                                    <th>Namabarang</th>
                                    <th>Satuan</th>
                                    <th>Hpp</th>
                                    <th>Sa</th>
                                    <th>Spop</th>
                                    <th>Beli</th>
                                    <th>Tuj</th>
                                    <th>Tgl Keluar</th>
                                    <th>Sekret</th>
                                    <th>Cetak</th>
                                    <th>Grafikita</th>
                                    <th>Dinas Umum</th>
                                    <th>Atk Rsud</th>
                                    <th>Ppbmp Kbs</th>
                                    <th>Kbs</th>
                                    <th>Ppbmp</th>
                                    <th>Medis</th>
                                    <th>Siiplah Bosda</th>
                                    <th>Sembako</th>
                                    <th>Fc Gose</th>
                                    <th>Fc Manding</th>
                                    <th>Fc Psamya</th>
                                    <th>Total 10</th>
                                    <th>Nilai Persediaan</th>
                                    <th width="200px">Action</th>
                                </tr>

                            </thead>
                            <tbody>
                                <?php
                                $start = 0;
                                foreach ($Persediaan_data as $persediaan) {
                                ?>
                                    <tr>
                                        <td width="80px"><?php echo ++$start ?></td>
                                        <td><?php echo $persediaan->tanggal ?></td>
                                        <td><?php echo $persediaan->kode ?></td>
                                        <td><?php echo $persediaan->namabarang ?></td>
                                        <td><?php echo $persediaan->satuan ?></td>
                                        <td><?php echo $persediaan->hpp ?></td>
                                        <td><?php echo $persediaan->sa ?></td>
                                        <td><?php echo $persediaan->spop ?></td>
                                        <td><?php echo $persediaan->beli ?></td>
                                        <td><?php echo $persediaan->tuj ?></td>
                                        <td><?php echo $persediaan->tgl_keluar ?></td>
                                        <td><?php echo $persediaan->sekret ?></td>
                                        <td><?php echo $persediaan->cetak ?></td>
                                        <td><?php echo $persediaan->grafikita ?></td>
                                        <td><?php echo $persediaan->dinas_umum ?></td>
                                        <td><?php echo $persediaan->atk_rsud ?></td>
                                        <td><?php echo $persediaan->ppbmp_kbs ?></td>
                                        <td><?php echo $persediaan->kbs ?></td>
                                        <td><?php echo $persediaan->ppbmp ?></td>
                                        <td><?php echo $persediaan->medis ?></td>
                                        <td><?php echo $persediaan->siiplah_bosda ?></td>
                                        <td><?php echo $persediaan->sembako ?></td>
                                        <td><?php echo $persediaan->fc_gose ?></td>
                                        <td><?php echo $persediaan->fc_manding ?></td>
                                        <td><?php echo $persediaan->fc_psamya ?></td>
                                        <td><?php echo $persediaan->total_10 ?></td>
                                        <td><?php echo $persediaan->nilai_persediaan ?></td>
                                        <td width="200px">Action</td>
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
            "scrollY": 1000,
            "scrollX": true
        });
    });



    $(document).ready(function() {
        var table = $('#ExampleOnFile').DataTable({
            scrollX: "800",
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