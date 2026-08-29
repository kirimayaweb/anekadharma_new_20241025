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
                                        <div class="col-12" text-align="center"> <strong>CEK DATA PENJUALAN</strong></div>
                                    </div>
                                    <div class="col-2" align="left">
                                        <?php //echo anchor(site_url('Tbl_kas_kecil/pemasukan_kas_kecil'), 'Pemasukan Data Kas', 'class="btn btn-danger"');
                                        ?>

                                    </div>
                                    <div class="col-2" align="left">

                                        <?php //echo anchor(site_url('Tbl_kas_kecil/pengeluaran_kas_kecil'), 'Pengeluaran Data Kas', 'class="btn btn-success"');
                                        ?>
                                    </div>
                                    <div class="col-6" align="right">

                                        <?php echo anchor(site_url('Cek_data_penjualan/excel'), 'Cetak ke Excel', 'class="btn btn-success" id="btn-kas-kecil-excel"'); ?>
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
<!-- SELECT j.uuid_persediaan AS uuid_tabel_penjualan, p.uuid_persediaan AS uuid_tabel_persediaan, CASE WHEN j.uuid_persediaan = p.uuid_persediaan THEN 'Sama' ELSE 'Beda' END AS kondisi, p.tanggal_beli, j.tgl_jual, j.nmrpesan, j.nmrkirim, j.unit, j.konsumen_nama, j.nama_barang, j.satuan, j.harga_satuan, j.jumlah, (j.harga_satuan* j.jumlah) as total_jual -->

                                                        <tr>
                                                            <th width="80px">No</th>
                                                            <th width="200px">Action</th>
                                                            <th>uuid_tabel_penjualan</th>
                                                            <th>uuid_tabel_persediaan <br/>(persediaan)</th>
                                                            <th>kondisi</th>
                                                            <th>tanggal_beli <br/>(persediaan)</th>
                                                            <th>tgl_jual</th>
                                                            <th>Nmr Pesan</th>
                                                            <th>nmrkirim</th>
                                                            <th>unit</th>
                                                            <th>konsumen_nama</th>
                                                            <th>nama_barang</th>
                                                            <th>satuan</th>
                                                            <th>harga_satuan</th>
                                                            <th>jumlah</th>
                                                            <th>total_jual</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $start = 0;
                                                        $get_saldo = 0;
                                                        $get_Total_debet = 0;
                                                        $get_Total_kredit = 0;

                                                        foreach ($data_cek_data_penjualan as $list_data) {
                                                        ?>
                                                            <tr>
                                                                <td style="text-align:center"><?php echo ++$start ?></td>
                                                                <td style="text-align:left">
                                                                    <?php
                                                                    
                                                                    echo anchor(site_url($list_data->id), '<i class="fa fa-trash-o">Hapus</i>', 'title="delete" class="btn btn-danger btn-sm kas-kecil-btn-action" onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
                                                                    ?>
                                                                </td>
                                                                <td style="text-align:center">
                                                                    <?php
                                                                    echo $list_data->uuid_tabel_penjualan;
                                                                    ?>
                                                                </td>
                                                                <td style="text-align:left"><?php echo $list_data->uuid_tabel_persediaan; ?> </td>
                                                                <td style="text-align:left"><?php echo $list_data->kondisi; ?> </td>
                                                                <td style="text-align:right">
                                                                    <?php
                                                                    echo $list_data->tanggal_beli;
                                                                    ?>
                                                                </td>
                                                                <td style="text-align:right">
                                                                    <?php
                                                                    echo $list_data->tgl_jual;
                                                                    ?>
                                                                </td>
                                                                <td style="text-align:right">
                                                                    <?php
                                                                    echo $list_data->nmrpesan;

                                                                    ?>
                                                                </td>
                                                              
                                                                <td style="text-align:right">
                                                                    <?php
                                                                    echo $list_data->nmrkirim;

                                                                    ?>
                                                                </td>
                                                              
                                                              
                                                                <td style="text-align:right">
                                                                    <?php
                                                                    echo $list_data->unit;

                                                                    ?>
                                                                </td>
                                                              
                                                              
                                                                <td style="text-align:right">
                                                                    <?php
                                                                    echo $list_data->konsumen_nama;

                                                                    ?>
                                                                </td>
                                                              
                                                              
                                                                <td style="text-align:right">
                                                                    <?php
                                                                    echo $list_data->nama_barang;

                                                                    ?>
                                                                </td>
                                                              
                                                              
                                                              
                                                                <td style="text-align:right">
                                                                    <?php
                                                                    echo $list_data->satuan;

                                                                    ?>
                                                                </td>
                                                              
                                                              
                                                                <td style="text-align:right">
                                                                    <?php
                                                                    echo $list_data->harga_satuan;

                                                                    ?>
                                                                </td>
                                                              
                                                              
                                                                <td style="text-align:right">
                                                                    <?php
                                                                    echo $list_data->jumlah;

                                                                    ?>
                                                                </td>
                                                              
                                                              
                                                                <td style="text-align:right">
                                                                    <?php
                                                                    echo $list_data->total_jual;

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