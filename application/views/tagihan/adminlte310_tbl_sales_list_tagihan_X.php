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
                                    <div class="col-12"> DATA TAGIHAN PER SALES </div>
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
                        <!-- <div class="col-2"><?php //echo anchor(site_url('Tbl_sales/create'), '<i class="fa fa-wpforms" aria-hidden="true"></i> Tambah Data Sales', 'class="btn btn-danger btn-sm"'); -->
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
                            <?php
                            // echo anchor(site_url('Trans_cetakinput/create_setting'), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Finishing', 'class="btn btn-primary btn-sm"'); 
                            ?>
                        </div>
                        <div class="col-4"></div>

                    </div>

                    <div class="card-body">

                        <!-- <table id="example1" class="table table-bordered table-striped"> -->
                        <table id="example" class="display nowrap" style="width:100%">
                            <thead>

                                <tr>
                                    <th style="text-align:center" width="10px">No</th>
                                    <!-- <th style="text-align:center" width="100px">Action</th> -->
                                    <!-- <th style="text-align:center" width="100px">RIWAYAT</th> -->
                                    <th style="text-align:center" width="100px">Cek Tagihan</th>
                                    <!-- <th style="text-align:center" width="100px">Tagihan</th> -->
                                    <!-- <th style="text-align:center" width="100px">Retur / Pemesanan</th> -->
                                    <!-- <th style="text-align:center" width="100px">Pemesanan</th> -->
                                    <!-- <th style="text-align:center" width="100px">Kekurangan Pengiriman</th> -->

                                    <th>Kode Sales<br />Nama Sales</th>
                                    <!-- <th>Nama Sales</th> -->
                                    <!-- <th align="center">Tagihan </th> -->
                                    <!-- <th>Notelp Sales</th> -->
                                    <!-- <th>Ringkasan Tagihan </th> -->


                                </tr>
                            </thead>

                            <tbody>

                                <?php
                                foreach ($tbl_sales_data as $tbl_sales) {
                                ?>
                                    <tr>
                                        <td><?php echo ++$start ?></td>

                                        <!-- <td>
                                            <?php
                                            // echo anchor(site_url('tbl_sales/riwayat_per_sales/' . $tbl_sales->uuid_sales), '<i class="fa fa-eye" aria-hidden="true">RIWAYAT</i>', 'class="btn btn-success btn-sm"');
                                            // echo "<br/>";
                                            // echo anchor(site_url('tbl_sales/update_by_uuid/' . $tbl_sales->uuid_sales), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH DATA</i>', 'class="btn btn-danger btn-sm"');
                                            ?>
                                        </td> -->
                                        <!-- <td>
                                            <?php
                                            // echo anchor(site_url('tbl_sales/read/' . $tbl_sales->uuid_sales), '<i class="fa fa-eye" aria-hidden="true">RIWAYAT</i>', 'class="btn btn-success btn-sm"');
                                            ?>
                                        </td> -->

                                        <td>
                                            <?php
                                            $uuid_stock = "kosong";
                                            // echo anchor(site_url('tbl_sales/pemesanan_by_sales/' . $tbl_sales->uuid_sales), '<i class="fa fa-eye" aria-hidden="true">PEMESANAN</i>', 'class="btn btn-warning btn-sm"');

                                            // if(){
                                            if ($this->session->userdata('sess_username') == "admin" or $this->session->userdata('sess_username') == "administrator") {
                                                echo anchor(site_url('Trans_bayar/bayar_by_sales/' . $tbl_sales->uuid_sales), '<i class="fa fa-pencil-square-o" aria-hidden="true">PEMBAYARAN</i>', 'class="btn btn-success btn-sm"');
                                                echo "<br/>";
                                                echo anchor(site_url('tbl_sales/lap_penjualan/' . $tbl_sales->uuid_sales), '<i class="fa fa-pencil-square-o" aria-hidden="true">CEK TAGIHAN</i>', 'class="btn btn-danger btn-sm"');
                                            }

                                            ?>
                                        </td>

                                        <!-- <td>
                                            <?php
                                            // echo anchor(site_url('Trans_retur/create_setting/' . $tbl_sales->uuid_sales), '<i class="fa fa-pencil-square-o" aria-hidden="true">RETUR</i>', 'class="btn btn-success btn-sm"');
                                            // if ($this->session->userdata('sess_username') == "admin") {
                                            //     echo "<br/>";
                                            //     // echo anchor(site_url('Trans_bayar/bayar_by_sales/' . $tbl_sales->uuid_sales), '<i class="fa fa-pencil-square-o" aria-hidden="true">TAGIHAN</i>', 'class="btn btn-primary btn-sm"');
                                            //     echo anchor(site_url('Trans_bayar/bayar_by_sales/' . $tbl_sales->uuid_sales), '<i class="fa fa-pencil-square-o" aria-hidden="true">PEMBAYARAN</i>', 'class="btn btn-danger btn-sm"');
                                            // }

                                            ?>
                                        </td> -->

                                        <!-- <td>
                                            <?php
                                            // echo anchor(site_url('tbl_sales/riwayat_pemesanan_by_sales/' . $tbl_sales->uuid_sales), '<i class="fa fa-eye" aria-hidden="true">KEKURANGAN PENGIRIMAN</i>', 'class="btn btn-success btn-sm"');
                                            ?>
                                        </td> -->

                                        <td><?php
                                            echo $tbl_sales->kode_sales;
                                            echo "<br/>";
                                            echo "<strong>" . $tbl_sales->nama_sales . "</strong>";
                                            ?></td>

                                        <!-- <td align="right"><?php
                                                                // $getdatatagihan = $this->Trans_penjualan_detail_model->total_tagihan_by_uuid($tbl_sales->uuid_sales);

                                                                // $Total_nominal = 0;
                                                                // foreach ($this->Trans_penjualan_detail_model->total_tagihan_by_uuid($tbl_sales->uuid_sales) as $data_sales_list) {
                                                                //     $Total_nominal = $Total_nominal + $data_sales_list['TotalPerMapel'];
                                                                // }
                                                                // echo "<strong>" . nominal($Total_nominal) . "</strong>";
                                                                ?>
                                                            </td> -->




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