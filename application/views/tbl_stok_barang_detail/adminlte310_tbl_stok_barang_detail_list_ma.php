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
                        <h3 class="card-title">REKAP DATA STOK MA</h3>
                    </div>
                    <br />

                    <div class="row">
                        <div class="col-2"><?php echo anchor(site_url('trans_cetak/create'), '<i class="fa fa-wpforms" aria-hidden="true"></i> Tambah Data STOCK', 'class="btn btn-danger btn-sm"'); ?></div>
                        <div class="col-2" align="right"><?php echo anchor(site_url('tbl_stok_barang_detail/excel'), '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Ms Excel', 'class="btn btn-success btn-sm"'); ?></div>
                        <div class="col-2" align="left"><?php echo anchor(site_url('tbl_stok_barang_detail/word'), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Export Ms Word', 'class="btn btn-primary btn-sm"'); ?></div>
                        <div class="col-2" align="right"><?php echo anchor(site_url('#'), '<i class="fa fa-file-excel-o" aria-hidden="true"></i> PO Cetak', 'class="btn btn-success btn-sm"'); ?></div>
                        <div class="col-2" align="left"><?php echo anchor(site_url('#'), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Finishing', 'class="btn btn-primary btn-sm"'); ?></div>
                        <div class="col-4"></div>

                    </div>

                    <div class="card-body">

                        <!-- <table id="example1" class="table table-bordered table-striped"> -->
                        <table id="exampleFreeze" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th style="text-align:center" width="50px">KODE STOCK</th>
                                    <!-- <th style="text-align:center" width="100px">Proses Transaksi</th> -->
                                    <th style="text-align:center" width="200px">Produk</th>
                                    <th style="text-align:center" width="100px">Sisa Stock</th>

                                    <th style="text-align:center" width="100px">PO Cetak</th>
                                    <th style="text-align:center" width="100px">Pemesanan</th>
                                    <th style="text-align:center" width="100px">Terjual</th>
                                    <th style="text-align:center" width="100px">Retur</th>



                                </tr>
                            </thead>

                            <tbody>

                                <?php
                                $start = 0;
                                foreach ($tbl_stok_barang_detail_data as $tbl_stok_barang_detail) {
                                ?>
                                    <tr style="font-size:1vw;font-weight: bold">
                                        <td width="10px"><?php echo ++$start ?></td>
                                        <td>
                                            <img src="<?php echo site_url(); ?>/Barcode/set_barcode/<?php echo $tbl_stok_barang_detail->kode_stock; ?>">
                                        </td>

                                        <td>
                                            <?php
                                            echo "Mapel : " . $tbl_stok_barang_detail->mapel_produk_stock;
                                            echo "<br/>";
                                            echo "Tingkat : " . $tbl_stok_barang_detail->tingkat_produk_stock;
                                            echo "<br/>";
                                            echo "Kelas : " . $tbl_stok_barang_detail->kelas_produk_stock;
                                            echo "<br/>";
                                            echo "Tahun : " . $tbl_stok_barang_detail->tahun_produk_stock;
                                            echo "<br/>";
                                            echo "Semester : " . $tbl_stok_barang_detail->semester_produk_stock;
                                            echo "<br/>";
                                            echo "Halaman : " . $tbl_stok_barang_detail->jumlah_halaman_stock;
                                            echo "<br/>";
                                            if ($tbl_stok_barang_detail->uuid_cover_produk_stock == "naskah") {
                                                echo "<strong>NASKAH</strong>";
                                            } else {
                                                echo "Cover : " . $tbl_stok_barang_detail->nama_cover;
                                            }
                                            ?>
                                        </td>

                                        <td align="right">
                                            <?php
                                            if ($tbl_stok_barang_detail->jumlah_exemplar_cetak_stock) {

                                                echo nominal(((($tbl_stok_barang_detail->jumlah_exemplar_cetak_stock) + $tbl_stok_barang_detail->retur_stock) - ($tbl_stok_barang_detail->jumlah_exemplar_jual_stock)));
                                            } else {
                                                echo "-";
                                            }

                                            ?>
                                        </td>

                                        <td align="right">
                                            <?php
                                            if ($tbl_stok_barang_detail->jumlah_exemplar_cetak_stock) {
                                                echo nominal($tbl_stok_barang_detail->jumlah_exemplar_cetak_stock);
                                            } else {
                                                echo "-";
                                            }

                                            ?>
                                        </td>
                                        <td align="right">
                                            <?php
                                            if ($tbl_stok_barang_detail->jumlah_exemplar_pemesanan_stock) {
                                                echo nominal($tbl_stok_barang_detail->jumlah_exemplar_pemesanan_stock);
                                            } else {
                                                echo "-";
                                            }

                                            ?>
                                        </td>
                                        <td align="right">
                                            <?php
                                            if ($tbl_stok_barang_detail->jumlah_exemplar_jual_stock) {
                                                echo  nominal($tbl_stok_barang_detail->jumlah_exemplar_jual_stock);
                                            } else {
                                                echo "-";
                                            }

                                            ?>
                                        </td>
                                        <td align="right">
                                            <?php
                                            if ($tbl_stok_barang_detail->retur_stock) {
                                                echo nominal($tbl_stok_barang_detail->retur_stock);
                                            } else {
                                                echo "-";
                                            }

                                            ?>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>

                            </tbody>


                            <!-- <tfoot>
                                    <tr>
                                        <th>Rendering engine</th>
                                        <th>Browser</th>
                                        <th>Platform(s)</th>
                                        <th>Engine version</th>
                                        <th>CSS grade</th>
                                    </tr>
                                </tfoot> -->
                        </table>


                    </div>

                    <!-- /.card-body -->
                </div>

            </div>

        </div>
    </section>
</div>