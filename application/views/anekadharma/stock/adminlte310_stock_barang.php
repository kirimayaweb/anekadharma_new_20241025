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
                                    <div class="col-12" text-align="center"> <strong>DATA STOCK BARANG</strong></div>
                                </div>


                            </div>

                            <div class="col-4">
                                <form action="<?php echo $action_cari_gudang; ?>" method="post">
                                    <div class="row">
                                        <div class="col-4" text-align="right"> <strong>GUDANG</strong></div>
                                        <div class="col-6" text-align="left">

                                            <!-- <label for="konsumen_nama">Unit </label> -->
                                            <select name="uuid_gudang" id="uuid_gudang" class="form-control select2" style="width: 100%; height: 60px;" required>
                                                <option value="">Pilih Gudang</option>
                                                <option value="semua">TAMPIL SEMUA</option>
                                                <?php

                                                $sql = "select * from sys_gudang order by nama_gudang ASC ";
                                                foreach ($this->db->query($sql)->result() as $m) {
                                                    echo "<option value='$m->uuid_gudang' ";
                                                    echo ">  " . strtoupper($m->kode_gudang)  . " | " . strtoupper($m->nama_gudang)  . "</option>";
                                                }
                                                ?>
                                            </select>

                                        </div>
                                        <div class="col-2" text-align="right">

                                            <?php //echo anchor(site_url('Sys_supplier/stock/'), 'CARI', 'class="btn btn-danger"');
                                            ?>

                                            <button type="submit" class="btn btn-danger"> Cari</button>

                                        </div>
                                    </div>

                                </form>
                            </div>

                            <div class="col-4">

                            </div>



                        </div>
                        <div class="row">
                            <div class="col-6">
                                <?php //echo anchor(site_url('tbl_pembelian/create'), 'Input Pembelian (Belanja Perusahaan)', 'class="btn btn-danger"'); 
                                ?>
                            </div>
                            <div class="col-4">

                            </div>
                            <div class="col-2">
                                <?php //echo anchor(site_url('tbl_pembelian/excel'), 'Cetak ke Excel', 'class="btn btn-success"'); 
                                ?>
                            </div>



                        </div>



                    </div>
                    <!-- <br /> -->



                    <div class="card-body">

                        <table id="example" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="text-align:center" width="10px">No</th>
                                    <!-- <th style="text-align:center" width="100px">Action</th> -->
                                    <th>Tgl Po</th>
                                    <th>Gudang</th>
                                    <th>SPOP</th>
                                    <th>Nama Barang </th>
                                    <th>Harga Satuan</th>
                                    <th>Satuan</th>
                                    <th>Persediaan</th>
                                    <!-- <th>jumlah <br />beli</th> -->

                                    <!-- <th>nama_barang_jual</th> -->
                                    <th style="text-align:right">Terjual</th>
                                    <th style="text-align:right">Pecah Satuan</th>
                                    <th style="text-align:right">Bahan Produksi</th>
                                    <!-- <th>harga_satuan_jual</th> -->
                                    <!-- <th>margin</th> -->
                                    <th>Sisa <br />Stock</th>
                                    <!-- <th>Nominal Stock</th> -->
                                    <th>Nominal <br />Persediaan</th>
                                    <!-- <th>Total Persediaan</th> -->

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $compare_spop = 0;
                                $Total_per_SPOP = 0;
                                $TOTAL_LUNAS = 0;
                                $TOTAL_HUTANG = 0;
                                $start = 0;
                                $TOTAL_PERSEDIAAN = 0;
                                $TOTAL_NILAI_PERSEDIAAN = 0;
                                $TOTAL_PERSEDIAAN_X = 0;
                                $Sisa_STOCK = 0;
                                foreach ($Data_stock as $list_data) {

                                    // if (($list_data->jumlah_belanja - $list_data->jumlah_terjual) > 0) { //HIDE SISA STOCK =0;

                                    // Cek data penjualan berdasarkan uuid_persediaan
                                    // --> data pembelian sudah masuk ke spop persediaan (jika barang baru, maka menjadi uuid_persediaan baru dengan nama barang yang sama)

                                    // $get_uuid_persediaan = $list_data->uuid_persediaan;

                                    // $sql_penjualan_per_uuid_persediaan = "SELECT `uuid_persediaan`,`uuid_barang`,sum(`jumlah`) as jumlah_per_uuid_persediaan FROM `tbl_penjualan` WHERE `uuid_persediaan`='$get_uuid_persediaan' GROUP by `uuid_persediaan`;";

                                    // print_r($this->db->query($sql_penjualan_per_uuid_persediaan)->row());

                                    // JUMLAH PENJUALAN + JUMLAH PECAH SATUAN + JUMLAH BAHAN PRODUKSI

                                    // if ($this->db->query($sql_penjualan_per_uuid_persediaan)->num_rows() > 0) {
                                    //     $Get_data_Rows = $this->db->query($sql_penjualan_per_uuid_persediaan)->row();
                                    //     $Jumlah_penjualan_per_uuid_persediaan = $Get_data_Rows->jumlah_per_uuid_persediaan + $list_data->pecah_satuan + $list_data->bahan_produksi;
                                    // } else {
                                    //     $Jumlah_penjualan_per_uuid_persediaan = 0 + $list_data->pecah_satuan + $list_data->bahan_produksi;
                                    // }

                                    $Jumlah_penjualan_per_uuid_persediaan = $list_data->penjualan + $list_data->pecah_satuan + $list_data->bahan_produksi;






                                ?>
                                    <tr>
                                        <!-- nomor -->
                                        <td style="text-align:center"><?php echo ++$start ?></td>

                                        <!-- Tanggal beli persediaan : tgl po -->
                                        <td style="text-align:left"><?php echo date("d-m-Y", strtotime($list_data->tanggal_beli_persediaan)); ?></td>

                                        <!-- nama gudang -->
                                        <td style="text-align:left;text-transform: uppercase;">
                                            <?php

                                            // echo anchor(site_url('tbl_pembelian/pecah_satuan/' . $list_data->uuid_pembelian), '<i class="fa fa-pencil-square-o" aria-hidden="true">' . $list_data->nama_gudang . '</i>', 'class=""');

                                            ?>

                                        </td>
                                        <td style="text-align:left;text-transform: uppercase;">
                                            <?php

                                            echo $list_data->spop;
                                            ?>

                                        </td>

                                        <!-- Nama Barang -->
                                        <td style="text-align:left">
                                            <?php


                                            // echo anchor(site_url('tbl_pembelian/pecah_satuan/' . $list_data->uuid_pembelian), '<i class="fa fa-pencil-square-o" aria-hidden="true">' . $list_data->nama_barang_beli . '</i>', 'class=""');

                                            echo $list_data->nama_barang_persediaan;

                                            ?>
                                        </td>


                                        <!-- Harga Satuan  -->

                                        <td style="text-align:right">
                                            <?php

                                            // if ($list_data->harga_satuan_persediaan and $list_data->harga_satuan_persediaan > 0) {
                                            if (!empty($list_data->harga_satuan_persediaan)) {
                                                // echo nominal($list_data->harga_satuan_persediaan);
                                                $X_harga_satuan = $list_data->harga_satuan_persediaan;
                                                // echo "<br>";
                                                echo number_format($list_data->harga_satuan_persediaan, 2, ',', '.');
                                                // echo "<br>";
                                                // echo number_format($list_data->harga_satuan_persediaan, 0, ',', '.');
                                                // echo "<br>";
                                                // echo nominal($list_data->harga_satuan_persediaan);

                                                $X_harga_satuan_X = number_format($list_data->harga_satuan_persediaan, 0, ',', '.');

                                                // $X_harga_satuan = number_format($list_data->harga_satuan_persediaan, 2, ',', '.');
                                                // echo $X_harga_satuan;
                                            } else {
                                                echo "0";
                                                $X_harga_satuan = 0;
                                                $X_harga_satuan_X =0;
                                            }

                                            ?>
                                        </td>


                                        <!-- satuan -->
                                        <td style="text-align:center"><?php echo $list_data->satuan; ?></td>

                                        <!-- nominal Persediaan -->
                                        <td style="text-align:right">
                                            <?php
                                            if ($list_data->jumlah_sediaan and $list_data->jumlah_sediaan > 0) {
                                                echo nominal($list_data->jumlah_sediaan);
                                                $stock_persediaan = $list_data->jumlah_sediaan;
                                            } else {
                                                echo "0";
                                                $stock_persediaan = 0;
                                            }
                                            ?>
                                        </td>

                                        <!-- Jumlah belanja/beli -->
                                        <!-- <td style="text-align:right">
                                            <?php

                                            // DATA SPOP DENGAN BELANJA BARU
                                            // if ($list_data->jumlah_belanja and $list_data->jumlah_belanja > 0) {
                                            //     echo nominal($list_data->jumlah_belanja);
                                            //     $x_jumlah_belanja = $list_data->jumlah_belanja;
                                            // } else {
                                            //     echo "0";
                                            //     $x_jumlah_belanja = 0;
                                            // }


                                            ?>
                                        </td> -->

                                        <!-- Jumlah penjualan -->
                                        <td style="text-align:right">
                                            <?php

                                            // echo nominal($Jumlah_penjualan_per_uuid_persediaan);
                                            echo nominal($list_data->penjualan);


                                            ?>
                                        </td>

                                        <!-- Jumlah Pecah Satuan -->
                                        <td style="text-align:right">
                                            <?php

                                            // echo nominal($Jumlah_penjualan_per_uuid_persediaan);
                                            echo nominal($list_data->pecah_satuan);
                                            


                                            ?>
                                        </td>

                                        <!-- Jumlah penjualan -->
                                        <td style="text-align:right">
                                            <?php

                                            // echo nominal($Jumlah_penjualan_per_uuid_persediaan);
                                            echo nominal($list_data->bahan_produksi);

                                            ?>
                                        </td>

                                        <!-- Sisa stock -->
                                        <td style="text-align:right">
                                            <?php
                                            // echo nominal($stock_persediaan + $x_jumlah_belanja - $x_jumlah_terjual); 
                                            if ($Jumlah_penjualan_per_uuid_persediaan > 0) {
                                                if ($list_data->jumlah_sediaan > 0) {
                                                    echo nominal($list_data->jumlah_sediaan - $Jumlah_penjualan_per_uuid_persediaan);
                                                    $Sisa_STOCK = $list_data->jumlah_sediaan - $Jumlah_penjualan_per_uuid_persediaan;
                                                } else {
                                                    echo "0";
                                                    $Sisa_STOCK = 0;
                                                }
                                            } else {
                                                if ($list_data->jumlah_sediaan > 0) {
                                                    echo nominal($list_data->jumlah_sediaan);
                                                    $Sisa_STOCK = $list_data->jumlah_sediaan;
                                                } else {
                                                    echo "0";
                                                    $Sisa_STOCK = 0;
                                                }
                                            }

                                            ?>
                                        </td>
                                        <!-- 
                                        <td style="text-align:right">
                                            <?php

                                            // echo nominal(($stock_persediaan + $x_jumlah_belanja - $x_jumlah_terjual) * $X_harga_satuan);

                                            // $TOTAL_PERSEDIAAN = $TOTAL_PERSEDIAAN + (($stock_persediaan + $x_jumlah_belanja - $x_jumlah_terjual) * $X_harga_satuan);
                                            ?>
                                        </td> -->

                                        <!-- NOMINAL PERSEDIAAN -->
                                        <td style="text-align:right">

                                            <?php

                                            if ($Sisa_STOCK > 0) {
                                                // echo $X_harga_satuan_X;
                                                // echo "<br/>";
                                                // echo $Sisa_STOCK;
                                                // echo "<br/>";
                                                // echo $list_data->harga_satuan_persediaan;
                                                // echo "<br/>";
                                                // // $GET_NominalBarang = $Sisa_STOCK * $list_data->harga_satuan_persediaan;
                                                // // $GET_NominalBarang = $Sisa_STOCK * $X_harga_satuan_X;

                                                // $X_harga_satuan_X = number_format($X_harga_satuan_X, 0, ',', '.');
                                                // echo $X_harga_satuan_X;
                                                // echo "<br/>";

                                                // $X_LIST_harga_satuan_X = number_format($list_data->harga_satuan_persediaan, 0, ',', '.');
                                                // $X_LIST_harga_satuan_X = number_format($X_LIST_harga_satuan_X, 0, '.', '');
                                                // echo $X_LIST_harga_satuan_X;
                                                // echo "<br/>";


                                                $X_Sisa_STOCK_X = number_format($Sisa_STOCK, 0, ',', '.');


                                                // $GET_NominalBarang = $X_Sisa_STOCK_X * $X_LIST_harga_satuan_X;
                                                // echo $X_Sisa_STOCK_X;
                                                // echo "<br/>";
                                                // echo number_format($GET_NominalBarang, 0, ',', '.');
                                                // echo "<br/>";


                                                
                                                $GET_NominalBarang_X = $X_Sisa_STOCK_X * $list_data->harga_satuan_persediaan;
                                                // echo $GET_NominalBarang_X;
                                                // echo "<br/>";
                                                echo number_format($GET_NominalBarang_X, 2, ',', '.');
                                                echo "<br/>";

                                                

                                                $TOTAL_NILAI_PERSEDIAAN = $TOTAL_NILAI_PERSEDIAAN + $GET_NominalBarang_X;
                                            } else {
                                                echo "0";
                                            }
                                            ?>

                                        </td>

                                        <!-- TOTAL PERSEDIAAN -->
                                        <!-- <td style="text-align:right">

                                            <?php
                                            // $TOTAL_PERSEDIAAN_X = $TOTAL_PERSEDIAAN_X + $list_data->nilai_persediaan;
                                            // echo nominal($TOTAL_PERSEDIAAN_X);
                                            ?>

                                        </td> -->

                                    </tr>

                                <?php
                                    // } //if (($list_data->jumlah_belanja - $list_data->jumlah_terjual) > 0)
                                }
                                ?>


                            </tbody>

                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <!-- <th></th> -->
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>TOTAL PERSEDIAAN</th>
                                    <!-- <th style="text-align:right"><?php //echo nominal($TOTAL_PERSEDIAAN); 
                                                                        ?></th> -->
                                    <th style="text-align:right"><?php echo nominal($TOTAL_NILAI_PERSEDIAAN); ?></th>
                                    <!-- <th style="text-align:right"><?php //echo nominal($TOTAL_PERSEDIAAN_X); 
                                                                        ?></th> -->

                                </tr>
                            </tfoot>

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
            "scrollY": 600,
            "scrollX": true
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