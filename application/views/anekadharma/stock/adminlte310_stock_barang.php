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
                                    <!-- <th>kode <br />Barang</th> -->
                                    <th>nama barang <br />beli</th>
                                    <th>harga satuan <br />beli</th>
                                    <th>satuan</th>
                                    <th>Persediaan</th>
                                    <th>jumlah <br />beli</th>

                                    <!-- <th>nama_barang_jual</th> -->
                                    <th>jumlah <br />terjual</th>
                                    <!-- <th>harga_satuan_jual</th> -->
                                    <!-- <th>margin</th> -->
                                    <th>Sisa <br />Stock</th>
                                    <th>Nominal Stock</th>

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
                                foreach ($Data_stock as $list_data) {

                                    // if (($list_data->jumlah_belanja - $list_data->jumlah_terjual) > 0) { //HIDE SISA STOCK =0;


                                ?>
                                    <tr>
                                        <td style="text-align:center"><?php echo ++$start ?></td>
                                        <td style="text-align:left"><?php echo date("Y-m-d", strtotime($list_data->tgl_po)); ?></td>
                                        <td style="text-align:left;text-transform: uppercase;">
                                            <?php
                                            // echo $list_data->nama_gudang;

                                            echo anchor(site_url('tbl_pembelian/pecah_satuan/' . $list_data->uuid_pembelian), '<i class="fa fa-pencil-square-o" aria-hidden="true">' . $list_data->nama_gudang . '</i>', 'class=""');

                                            ?>

                                        </td>


                                        <!-- // echo $list_data->kode_barang;  -->
                                        <!-- <td style="text-align:left">
                                            <?php
                                            

                                            // echo anchor(site_url('tbl_pembelian/pecah_satuan/' . $list_data->uuid_pembelian), '<i class="fa fa-pencil-square-o" aria-hidden="true">' . $list_data->kode_barang . '</i>', 'class=""');
                                            ?>
                                        </td> -->

                                        <td style="text-align:left">
                                            <?php
                                            // echo $list_data->nama_barang_beli; 

                                            echo anchor(site_url('tbl_pembelian/pecah_satuan/' . $list_data->uuid_pembelian), '<i class="fa fa-pencil-square-o" aria-hidden="true">' . $list_data->nama_barang_beli . '</i>', 'class=""');

                                            ?>
                                        </td>


                                        <!-- Harga Satuan  -->
                                        <!-- <td style="text-align:right"><?php //echo nominal($list_data->harga_satuan_beli); 
                                                                            ?></td> -->
                                        <td style="text-align:right">
                                            <?php

                                            // if ($list_data->harga_satuan_persediaan and $list_data->harga_satuan_persediaan > 0) {
                                            if (!empty($list_data->harga_satuan_persediaan)) {
                                                echo nominal($list_data->harga_satuan_persediaan);
                                                $X_harga_satuan = $list_data->harga_satuan_persediaan;
                                            } else {
                                                echo "0";
                                                $X_harga_satuan = 0;
                                            }

                                            ?>
                                        </td>



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
                                        <td style="text-align:right">
                                            <?php

                                            if ($list_data->jumlah_belanja and $list_data->jumlah_belanja > 0) {
                                                echo nominal($list_data->jumlah_belanja);
                                                $x_jumlah_belanja=$list_data->jumlah_belanja;
                                            } else {
                                                echo "0";
                                                $x_jumlah_belanja=0;
                                            }


                                            ?>
                                        </td>

                                        <!-- Jumlah penjualan -->
                                        <td style="text-align:rirightght">
                                            <?php
                                            if ($list_data->jumlah_terjual and $list_data->jumlah_terjual > 0) {
                                                echo nominal($list_data->jumlah_terjual);
                                                $x_jumlah_terjual=$list_data->jumlah_terjual;
                                            } else {
                                                echo "0";
                                                $x_jumlah_terjual=0;
                                            }

                                            ?>
                                        </td>

                                        <!-- Sisa stock -->
                                        <td style="text-align:right"><?php echo nominal($stock_persediaan + $x_jumlah_belanja - $x_jumlah_terjual); ?></td>

                                        <td style="text-align:right">
                                            <?php
                                            // echo $stock_persediaan;
                                            // echo "<br/>";
                                            // echo $x_jumlah_belanja;
                                            // echo "<br/>";
                                            // echo $x_jumlah_terjual;
                                            // echo "<br/>";
                                            // echo $X_harga_satuan;
                                            // echo "<br/>";

                                            echo nominal(($stock_persediaan + $x_jumlah_belanja - $x_jumlah_terjual) * $X_harga_satuan);

                                            $TOTAL_PERSEDIAAN = $TOTAL_PERSEDIAAN + (($stock_persediaan + $x_jumlah_belanja - $x_jumlah_terjual) * $X_harga_satuan);
                                            ?>
                                        </td>


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
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>TOTAL</th>
                                    <th style="text-align:right"><?php echo nominal($TOTAL_PERSEDIAAN); ?></th>

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
            "scrollY": 300,
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