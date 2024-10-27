<!-- Main content -->
<section class='content'>
  <div class='row'>
    <div class='col-xs-12'>
      <!-- <div class='box'> -->
      <div class="box box-warning box-solid">
        <div class='box-header'>
          <h3 class='box-title'>MENU LIST<?php echo anchor('menu/create/', 'Create', array('class' => 'btn btn-danger btn-sm')); ?>
            <?php echo anchor(site_url('menu/excel'), ' <i class="fa fa-file-excel-o"></i> Excel', 'class="btn btn-primary btn-sm"'); ?>
            <?php echo anchor(site_url('menu/word'), '<i class="fa fa-file-word-o"></i> Word', 'class="btn btn-primary btn-sm"'); ?>
            <?php echo anchor(site_url('menu/pdf'), '<i class="fa fa-file-pdf-o"></i> PDF', 'class="btn btn-primary btn-sm"'); ?></h3>
        </div><!-- /.box-header -->
        <div class='box-body'>
          


        <!-- ----------table -->


        <div class="box box-warning box-solid">

            <div class="col-md-12">
                   
                    <div class="row">
                        <div class="col-6">
                            <div class="card card-primary">

                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-12">

                                            <form action="<?php echo $action_input_neraca_baru; ?>" method="post">
                                                <div class="row">
                                                    <!-- <div class="col-5" text-align="right"> <strong>INPUT NERACA TAHUNAN:</strong></div> -->
                                                    <div class="col-4" text-align="left">

                                                        <?php
                                                        $date_input = date("Y") + 1;
                                                        $year_10tahun_before = date("Y") - 10;


                                                        ?>

                                                        <!-- <label for="konsumen_nama">Unit </label> -->
                                                       

                                                    </div>
                                                    <div class="col-3" text-align="right">

                                                        <?php //echo anchor(site_url('Sys_supplier/stock/'), 'CARI', 'class="btn btn-danger"');
                                                        ?>

                                                        <button type="submit" class="btn btn-danger">Tambah</button>

                                                    </div>
                                                </div>

                                            </form>

                                        </div>
                                    </div>
                                </div>



                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
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
                                    </div>
                                </div>

                            </div>


                        </div>


                        <div class="col-6">
                            <div class="card card-primary">

                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-12">

                                            <form action="<?php echo $action_input_neraca_baru; ?>" method="post">
                                                <div class="row">
                                                    <div class="col-5" text-align="right"> <strong>INPUT NERACA BULANAN:</strong></div>
                                                    <div class="col-4" text-align="left">
                                                        <div class="col-4" text-align="left">

                                                            <!-- <form action="/action_page.php"> -->
                                                            <!-- <label for="bulan">BULAN :</label> -->
                                                            <input type="month" id="bulan_neraca" name="bulan_neraca">
                                                            <!-- <input type="submit"> -->
                                                            <!-- </form> -->

                                                        </div>

                                                    </div>
                                                    <div class="col-3" text-align="right">

                                                        <?php //echo anchor(site_url('Sys_supplier/stock/'), 'CARI', 'class="btn btn-danger"');
                                                        ?>

                                                        <button type="submit" class="btn btn-danger">Tambah</button>

                                                    </div>
                                                </div>

                                            </form>

                                        </div>
                                    </div>
                                </div>


                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">

                                            <table id="example" class="display nowrap" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align:center" width="10px">No</th>
                                                        <th>Tahun</th>
                                                        <th>Bulan</th>
                                                        <th>Action</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php

                                                    function bulan_teks($angka_bulan)
                                                    {
                                                        if ($angka_bulan == 1) {
                                                            $bulan_teks = "Januari";
                                                        } elseif ($angka_bulan == 2) {
                                                            $bulan_teks = "Februari";
                                                        } elseif ($angka_bulan == 3) {
                                                            $bulan_teks = "Maret";
                                                        } elseif ($angka_bulan == 4) {
                                                            $bulan_teks = "April";
                                                        } elseif ($angka_bulan == 5) {
                                                            $bulan_teks = "Mei";
                                                        } elseif ($angka_bulan == 6) {
                                                            $bulan_teks = "Juni";
                                                        } elseif ($angka_bulan == 7) {
                                                            $bulan_teks = "Juli";
                                                        } elseif ($angka_bulan == 8) {
                                                            $bulan_teks = "Agustus";
                                                        } elseif ($angka_bulan == 9) {
                                                            $bulan_teks = "September";
                                                        } elseif ($angka_bulan == 10) {
                                                            $bulan_teks = "Oktober";
                                                        } elseif ($angka_bulan == 11) {
                                                            $bulan_teks = "November";
                                                        } elseif ($angka_bulan == 12) {
                                                            $bulan_teks = "Desember";
                                                        } else {
                                                            $bulan_teks = "";
                                                        }
                                                        return $bulan_teks;
                                                    }

                                                    $start=0;
                                                    foreach ($Tbl_neraca_data as $list_data) {
                                                    ?>

                                                        <tr>

                                                            <td><?php echo ++$start ?></td>

                                                            <td align="left"><?php echo $list_data->tahun_transaksi; ?></td>
                                                            <td align="left"><?php echo $list_data->bulan_transaksi . " (" . bulan_teks($list_data->bulan_transaksi) . ")"; ?></td>
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
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

            </div>
        </div>

        <!-- ----------table -->



          <script src="<?php echo base_url('assets/js/jquery-1.11.2.min.js') ?>"></script>
          <script src="<?php echo base_url('assets/datatables/jquery.dataTables.js') ?>"></script>
          <script src="<?php echo base_url('assets/datatables/dataTables.bootstrap.js') ?>"></script>
          <script type="text/javascript">
            $(document).ready(function() {
              $("#mytable").dataTable();
            });
          </script>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->



<!-- =========================================================================== -->




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