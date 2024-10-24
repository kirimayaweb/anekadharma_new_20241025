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
                                    <div class="col-12" text-align="center"> <strong>DATA NERACA</strong></div>
                                </div>


                            </div>
                            <div class="col-6">
                                <form action="<?php echo $action_input_neraca_baru; ?>" method="post">
                                    <div class="row">
                                        <div class="col-4" text-align="right"> <strong>INPUT NERACA BARU</strong></div>
                                        <div class="col-6" text-align="left">

                                            <?php
                                            $date_input = date("Y") + 1;
                                            $year_10tahun_before = date("Y") - 10;


                                            ?>

                                            <!-- <label for="konsumen_nama">Unit </label> -->
                                            <select name="tahun_neraca" id="tahun_neraca" class="form-control select2" style="width: 100%; height: 60px;" required>
                                                <option value="">Pilih Input Tahun </option>
                                                <!-- <option value="semua">TAMPIL SEMUA</option> -->
                                                <?php

                                                // $sql = "select tahun_transaksi from tbl_neraca_data order by tahun_transaksi ASC ";
                                                // foreach ($this->db->query($sql)->result() as $m) {
                                                //     echo "<option value='$m->tahun_transaksi' ";
                                                //     echo ">  " . strtoupper($m->tahun_transaksi) . "</option>";
                                                // }


                                                while ($year_10tahun_before < $date_input) {
                                                    // echo $i;
                                                ?>
                                                    <option value="<?php echo $year_10tahun_before; ?>"> <?php echo $year_10tahun_before; ?> </option>
                                                <?php
                                                    $year_10tahun_before++;
                                                }

                                                ?>
                                            </select>

                                        </div>
                                        <div class="col-2" text-align="right">

                                            <?php //echo anchor(site_url('Sys_supplier/stock/'), 'CARI', 'class="btn btn-danger"');
                                            ?>

                                            <button type="submit" class="btn btn-danger">Input</button>

                                        </div>
                                    </div>

                                </form>
                            </div>



                        </div>
                        <div class="row">
                            <div class="col-6">
                                <?php //echo anchor(site_url('Tbl_neraca_data/create'), 'Input Pembelian (Belanja Perusahaan)', 'class="btn btn-danger"'); 
                                ?>
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

                        <table id="ExampleOnFile" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="text-align:center" width="10px">No</th>
                                    <th>Tahun</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                foreach ($Tbl_neraca_data as $list_data) {
                                ?>

                                    <tr>

                                        <td><?php echo ++$start ?></td>

                                        <td align="left"><?php echo $list_data->tahun_transaksi; ?></td>
                                        <td align="left">
                                            <?php
                                            if ($status_laporan == "bukan_laporan") {
                                                echo anchor(site_url('Tbl_neraca_data/neraca_form/' . $list_data->uuid_data_neraca), '<i class="fa fa-pencil-square-o" aria-hidden="true">Update Data</i>', 'class="btn btn-warning btn-xs"');
                                            }

                                            echo anchor(site_url('Tbl_neraca_data/neraca_cetak/' . $list_data->uuid_data_neraca), '<i class="fa fa-pencil-square-o" aria-hidden="true">Cetak Neraca</i>', 'class="btn btn-success btn-xs" target="_blank"');

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