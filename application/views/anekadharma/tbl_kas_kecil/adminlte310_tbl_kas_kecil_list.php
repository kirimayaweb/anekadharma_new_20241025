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
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-2" align="left">
                                        <div class="col-12" text-align="center"> <strong>KAS KECIL</strong></div>
                                    </div>
                                    <div class="col-2" align="left">
                                        <?php echo anchor(site_url('Tbl_kas_kecil/pemasukan_kas_kecil'), 'Pemasukan Data Kas', 'class="btn btn-danger"');
                                        ?>

                                    </div>
                                    <div class="col-2" align="left">

                                        <?php echo anchor(site_url('Tbl_kas_kecil/pengeluaran_kas_kecil'), 'Pengeluaran Data Kas', 'class="btn btn-success"');
                                        ?>
                                    </div>
                                    <div class="col-6" align="right">

                                        <?php echo anchor(site_url('Tbl_kas_kecil/excel'), 'Cetak ke Excel', 'class="btn btn-success" id="btn-kas-kecil-excel"'); ?>
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

                                                <div class="kas-kecil-dt-wrap">
                                                <table id="kas-kecil-table" class="display nowrap kas-kecil-dt-table" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="80px">No</th>
                                                            <th width="200px">Action</th>
                                                            <th>Tanggal</th>
                                                            <th>Unit</th>
                                                            <th>Keterangan</th>
                                                            <th>Debet</th>
                                                            <th>Kredit</th>
                                                            <th>Saldo</th>
                                                            <!-- <th>Id Usr</th> -->

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $start = 0;
                                                        $get_saldo = 0;
                                                        $get_Total_debet = 0;
                                                        $get_Total_kredit = 0;

                                                        foreach ($Tbl_kas_kecil_data as $list_data) {
                                                        ?>
                                                            <tr>
                                                                <td style="text-align:center"><?php echo ++$start ?></td>
                                                                <td style="text-align:left">
                                                                    <?php
                                                                    if ($list_data->debet > 0) {
                                                                        echo anchor(site_url('Tbl_kas_kecil/pengeluaran_kas_kecil_update/' . $list_data->id), '<i class="fa fa-pencil-square-o">Ubah</i>', array('title' => 'edit', 'class' => 'btn btn-warning btn-sm kas-kecil-btn-action'));
                                                                    } else {
                                                                        if ($list_data->uuid_spop) {
                                                                            echo anchor(site_url('Tbl_kas_kecil/pengeluaran_kas_kecil_update/' . $list_data->id .'/'. $list_data->uuid_spop), '<i class="fa fa-pencil-square-o">Ubah</i>', array('title' => 'edit', 'class' => 'btn btn-warning btn-sm kas-kecil-btn-action'));
                                                                        } else {
                                                                            echo anchor(site_url('Tbl_kas_kecil/pengeluaran_kas_kecil_update/' . $list_data->id), '<i class="fa fa-pencil-square-o">Ubah</i>', array('title' => 'edit', 'class' => 'btn btn-warning btn-sm kas-kecil-btn-action'));
                                                                        }
                                                                    }
                                                                    echo ' ';
                                                                    echo anchor(site_url('Tbl_kas_kecil/delete/' . $list_data->id), '<i class="fa fa-trash-o">Hapus</i>', 'title="delete" class="btn btn-danger btn-sm kas-kecil-btn-action" onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
                                                                    ?>
                                                                </td>
                                                                <td style="text-align:center">
                                                                    <?php
                                                                    echo date("d-m-Y", strtotime($list_data->tanggal));
                                                                    ?>
                                                                </td>
                                                                <td style="text-align:left"><?php echo $list_data->unit; ?> </td>
                                                                <td style="text-align:left"><?php echo $list_data->keterangan; ?> </td>
                                                                <td style="text-align:right">
                                                                    <?php
                                                                    // echo nominal($list_data->debet);
                                                                    echo number_format($list_data->debet, 2, ',', '.');
                                                                    $get_Total_debet = $get_Total_debet + $list_data->debet;
                                                                    ?>
                                                                </td>
                                                                <td style="text-align:right">
                                                                    <?php
                                                                    // echo nominal($list_data->kredit);
                                                                    echo number_format($list_data->kredit, 2, ',', '.');
                                                                    $get_Total_kredit = $get_Total_kredit + $list_data->kredit;
                                                                    ?>
                                                                </td>
                                                                <td style="text-align:right">
                                                                    <?php
                                                                    if ($get_saldo == 0) {
                                                                        // echo nominal($list_data->debet - $list_data->kredit);
                                                                        $get_saldo = $list_data->debet - $list_data->kredit;
                                                                    } else {
                                                                        // echo nominal($get_saldo + $list_data->debet - $list_data->kredit);
                                                                        $get_saldo = $get_saldo + $list_data->debet - $list_data->kredit;
                                                                    }
                                                                    // echo nominal($get_saldo);
                                                                    echo number_format($get_saldo, 2, ',', '.');
                                                                    ?>
                                                                </td>


                                                            </tr>
                                                        <?php
                                                        }
                                                        ?>


                                                    </tbody>

                                                    <tfoot>
                                                        <tr>
                                                            <th width="80px"></th>
                                                            <!-- <th>Uuid Kas Kecil</th> -->
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th style="font-size:1.5vw;font-weight: bold;text-align:right;color:black;">TOTAL</th>
                                                            <th style="font-size:1.5vw;font-weight: bold;text-align:right;color:black;"><?php echo number_format($get_Total_debet, 2, ',', '.'); ?></th>
                                                            <th style="font-size:1.5vw;font-weight: bold;text-align:right;color:black;"><?php echo number_format($get_Total_kredit, 2, ',', '.'); ?></th>
                                                            <th style="font-size:1.5vw;font-weight: bold;text-align:right;color:black;"><?php echo number_format($get_saldo, 2, ',', '.'); ?></th>
                                                            <!-- <th>Id Usr</th> -->
                                                            <!-- <th width="200px"></th> -->
                                                        </tr>

                                                    </tfoot>



                                                </table>
                                                </div>


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
    /* Kas Kecil DataTable — border kuning tua hanya di luar wrapper */
    .kas-kecil-dt-wrap {
        border: 1px solid #d4a017;
        border-radius: 4px;
        padding: 8px;
        background: #fff;
        overflow-x: auto;
    }
    .kas-kecil-dt-wrap .dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
    .kas-kecil-dt-table {
        margin-bottom: 0 !important;
        table-layout: auto;
        width: 100% !important;
    }
    .kas-kecil-dt-table thead th,
    .kas-kecil-dt-table tbody td,
    .kas-kecil-dt-table tfoot th {
        border: 1px solid #dee2e6 !important;
        vertical-align: middle;
        font-size: 15px;
        padding: 7px 9px;
    }
    .kas-kecil-dt-table .kas-kecil-btn-action {
        padding: 0.15rem 0.38rem;
        font-size: 0.66rem;
        line-height: 1.25;
    }
    .kas-kecil-dt-table .kas-kecil-btn-action i {
        font-size: 0.95em;
    }
    .kas-kecil-dt-table thead th {
        background: #e8f5e9;
        font-weight: 600;
        text-align: center;
        white-space: nowrap;
        line-height: 1.35;
        border-bottom: 1px solid #dee2e6 !important;
    }
    .kas-kecil-dt-table tbody td {
        background: #fff;
        word-wrap: break-word;
    }
    .kas-kecil-dt-table tbody tr:hover td {
        background: #f8f9fa;
    }
    .kas-kecil-dt-table tfoot th {
        background: #f8f9fa;
        font-weight: 700;
        border-top: 1px solid #dee2e6 !important;
    }
    .kas-kecil-dt-wrap table.dataTable thead .sorting:before,
    .kas-kecil-dt-wrap table.dataTable thead .sorting:after,
    .kas-kecil-dt-wrap table.dataTable thead .sorting_asc:before,
    .kas-kecil-dt-wrap table.dataTable thead .sorting_asc:after,
    .kas-kecil-dt-wrap table.dataTable thead .sorting_desc:before,
    .kas-kecil-dt-wrap table.dataTable thead .sorting_desc:after {
        display: none !important;
    }
    .kas-kecil-dt-wrap table.dataTable thead th.sorting,
    .kas-kecil-dt-wrap table.dataTable thead th.sorting_asc,
    .kas-kecil-dt-wrap table.dataTable thead th.sorting_desc {
        background-image: none !important;
        padding-right: 8px !important;
    }
</style>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#kas-kecil-table').DataTable({
            scrollY: 700,
            scrollX: true,
            order: [],
            paging: true,
            searching: true,
            info: true
        });
    });
</script>