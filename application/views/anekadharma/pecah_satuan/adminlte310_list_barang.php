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
                                    <div class="col-12" text-align="center"> <strong>PECAH SATUAN</strong></div>
                                </div>


                            </div>

                            <div class="col-4">
                                <form action="<?php echo $action_cari_gudang; ?>" method="post">
                                    <div class="row">
                                        <div class="col-4" text-align="right"> 
                                            <!-- <strong>GUDANG</strong> -->
                                        </div>
                                        <div class="col-6" text-align="left">

                                            <!-- <label for="konsumen_nama">Unit </label> -->
                                            <!-- <select name="uuid_gudang" id="uuid_gudang" class="form-control select2" style="width: 100%; height: 60px;" required>
                                                <option value="">Pilih Gudang</option>
                                                <option value="semua">TAMPIL SEMUA</option>
                                                <?php

                                                // $sql = "select * from sys_gudang order by nama_gudang ASC ";
                                                // foreach ($this->db->query($sql)->result() as $m) {
                                                //     echo "<option value='$m->uuid_gudang' ";
                                                //     echo ">  " . strtoupper($m->kode_gudang)  . " | " . strtoupper($m->nama_gudang)  . "</option>";
                                                // }
                                                ?>
                                            </select> -->

                                        </div>
                                        <div class="col-2" text-align="right">

                                        
                                            <!-- <button type="submit" class="btn btn-danger"> Cari</button> -->

                                        </div>
                                    </div>

                                </form>
                            </div>

                            <div class="col-4">

                            </div>



                        </div>




                    </div>
                    <!-- <br /> -->



                    <div class="card-body">
                        Pilih Salah Satu produk yang akan di pecah satuanya dengan klik nama barang atau klik tombol pecah satuan. <br/>
                        - DATA PERSEDIAAN (STOCK BARANG)
                        <table id="example" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="text-align:center" width="10px">No</th>
                                    <!-- <th style="text-align:center" width="100px">Action</th> -->
                                    <th>Action</th>
                                    <th>Tanggal</th>

                                    <!-- <th>Gudang</th> -->
                                    <th>SPOP</th>
                                    <th>nama barang</th>
                                    <th>harga satuan</th>
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

                                    // if ($list_data->uuid_barang) {



                                    // $get_uuid_persediaan = $list_data->uuid_persediaan;

                                    // $sql_penjualan_per_uuid_persediaan = "SELECT `uuid_persediaan`,`uuid_barang`,sum(`jumlah`) as jumlah_per_uuid_persediaan FROM `tbl_penjualan` WHERE `uuid_persediaan`='$get_uuid_persediaan' GROUP by `uuid_persediaan`;";

                                    // // print_r($this->db->query($sql_penjualan_per_uuid_persediaan)->row());

                                    // if ($this->db->query($sql_penjualan_per_uuid_persediaan)->num_rows() > 0) {
                                    //     $Get_data_Rows = $this->db->query($sql_penjualan_per_uuid_persediaan)->row();
                                    //     $Jumlah_penjualan_per_uuid_persediaan = $Get_data_Rows->jumlah_per_uuid_persediaan + $list_data->pecah_satuan;
                                    // } else {
                                    //     $Jumlah_penjualan_per_uuid_persediaan = 0 + $list_data->pecah_satuan;
                                    // }

                                    $Jumlah_penjualan_per_uuid_persediaan = $list_data->penjualan + $list_data->pecah_satuan + $list_data->bahan_produksi;



                                ?>
                                    <tr>
                                        <td style="text-align:center"><?php echo ++$start ?></td>
                                        <td style="text-align:center">
                                            <?php
                                            echo anchor(site_url('tbl_pembelian/pecah_satuan_proses/' . $list_data->uuid_persediaan), '<i class="fa fa-pencil-square-o">Pilih Buat Satuan Baru </i>', array('title' => 'Pilih Buat Satuan Baru ', 'class' => 'btn btn-success btn-sm'));

                                            // Cek apakah UUID_barang ada di tbl_pembelian_pecah_satuan , jika ada ==> tambahkan tombol rollback
                                            // echo "<br/>";
                                            // echo $list_data->uuid_barang;
                                            // echo "<br/>";
                                            // echo $list_data->uuid_barang_pecah;

                                            $this->db->where('uuid_barang_baru', $list_data->uuid_barang);
                                            $get_data_pecah_satuan = $this->db->get('tbl_pembelian_pecah_satuan');
                                    
                                            if ($get_data_pecah_satuan->num_rows() > 0) {

                                            // if( $list_data->uuid_barang_baru){
                                                // echo "<br/>";
                                                // echo $get_data_pecah_satuan->row()->uuid_barang_baru;
                                                echo "<br/>";
                                                echo anchor(site_url('tbl_pembelian/rollback_satuan_proses/' . $list_data->uuid_persediaan), '<i class="fa fa-pencil-square-o">Mengembalikan ke Satuan Awal </i>', array('title' => 'Pilih Buat Satuan Baru ', 'class' => 'btn btn-danger btn-sm'));
                                            }
                                            ?>
                                        </td>
                                        <td style="text-align:left"><?php echo date("d-m-Y", strtotime($list_data->tanggal_beli_persediaan)); ?></td>

                                        <!-- Gudang -->
                                        <!-- <td style="text-align:left;text-transform: uppercase;">
                                            <?php

                                            //echo anchor(site_url('tbl_pembelian/pecah_satuan_proses/' . $list_data->uuid_persediaan), '<i class="fa fa-pencil-square-o" aria-hidden="true">' . $list_data->nama_gudang . '</i>', 'class=""');

                                            ?>

                                            </td> -->


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
                                                echo nominal($list_data->harga_satuan_persediaan);
                                                $X_harga_satuan = $list_data->harga_satuan_persediaan;
                                            } else {
                                                echo "0";
                                                $X_harga_satuan = 0;
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
                                        <td style="text-align:right">
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
                                        </td>

                                        <!-- Jumlah penjualan -->
                                        <td style="text-align:right">
                                            <?php
                                            // echo $this->db->query($sql_penjualan_per_uuid_persediaan)->num_rows();
                                            // echo "<br/>";
                                            echo nominal($Jumlah_penjualan_per_uuid_persediaan);
                                            // DATA PENJUALAN PER SPOP
                                            // if ($list_data->jumlah_terjual and $list_data->jumlah_terjual > 0) {
                                            //     echo nominal($list_data->jumlah_terjual);
                                            //     $x_jumlah_terjual = $list_data->jumlah_terjual;
                                            // } else {
                                            //     echo "0";
                                            //     $x_jumlah_terjual = 0;
                                            // }

                                            ?>
                                        </td>

                                        <!-- Sisa stock -->
                                        <td style="text-align:right">
                                            <?php
                                            // echo nominal($stock_persediaan + $x_jumlah_belanja - $x_jumlah_terjual); 
                                            if ($Jumlah_penjualan_per_uuid_persediaan > 0) {
                                                if ($list_data->jumlah_sediaan > 0) {
                                                    echo nominal($list_data->jumlah_sediaan - $Jumlah_penjualan_per_uuid_persediaan);
                                                } else {
                                                    echo "0";
                                                }
                                            } else {
                                                if ($list_data->jumlah_sediaan > 0) {
                                                    echo nominal($list_data->jumlah_sediaan);
                                                } else {
                                                    echo "0";
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
                                            $TOTAL_NILAI_PERSEDIAAN = $TOTAL_NILAI_PERSEDIAAN + $list_data->nilai_persediaan;
                                            echo nominal($list_data->nilai_persediaan);
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
                                    // }
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
            "scrollY": 900,
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