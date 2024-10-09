<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-warning box-solid">

                    <div class="box-header">
                        <h3 class="box-title">MANAJEMEN DATA STOK</h3>
                    </div>

                    <div class="box-body">
                        <div class='row'>
                            <div class='col-md-9'>
                                <div style="padding-bottom: 10px;"'>
        <?php echo anchor(site_url('trans_cetak/create'), '<i class="fa fa-wpforms" aria-hidden="true"></i> Tambah Data STOCK', 'class="btn btn-danger btn-sm"'); ?>
		<?php echo anchor(site_url('tbl_stok_barang_detail/excel'), '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Ms Excel', 'class="btn btn-success btn-sm"'); ?>
		<?php echo anchor(site_url('tbl_stok_barang_detail/word'), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Export Ms Word', 'class="btn btn-primary btn-sm"'); ?></div>
            </div>
            <div class=' col-md-3'>
                                    <form action="<?php echo site_url('tbl_stok_barang_detail/index'); ?>" class="form-inline" method="get">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                                            <span class="input-group-btn">
                                                <?php
                                                if ($q <> '') {
                                                ?>
                                                    <a href="<?php echo site_url('tbl_stok_barang_detail'); ?>" class="btn btn-default">Reset</a>
                                                <?php
                                                }
                                                ?>
                                                <button class="btn btn-primary" type="submit">Search</button>
                                            </span>
                                        </div>
                                    </form>
                                </div>
                            </div>


                            <div class="row" style="margin-bottom: 10px">
                                <div class="col-md-4 text-center">
                                    <div style="margin-top: 8px" id="message">
                                        <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
                                    </div>
                                </div>
                                <div class="col-md-1 text-right">
                                </div>
                                <div class="col-md-3 text-right">

                                </div>
                            </div>
                            <table class="table table-bordered table-striped" id="mytable">
                                <tr>
                                    <th>No</th>
                                    <!-- <th>Action</th> -->
                                    <!-- <th>Uuid Stock</th> -->
                                    <th style="text-align:center" width="50px">KODE STOCK</th>
                                    <th style="text-align:center" width="100px">Proses Transaksi</th>
                                    <!-- <th>Id Trans Cetak</th> -->
                                    <!-- <th>Uuid Trans Cetak</th> -->
                                    <!-- <th>Id User Input</th> -->
                                    <th style="text-align:center" width="200px">Produk</th>
                                    <th style="text-align:center" width="200px">Jumlah Total(Sisa)<br />Jumlah Cetak <br />penjualan<br />Retur</th>
                                    <!-- <th>Status Produk</th> -->
                                    <th>Percetakan</th>
                                    <!-- <th>Id User Processing Cetak</th> -->
                                    <!-- <th>Id Cover</th> -->
                                    <th>Mulai Cetak</th>
                                    <th>Selesai Cetak</th>
                                    <!-- <th>Id Tbl Finishing</th> -->
                                    <!-- <th>Id User Processing Finishing</th> -->
                                    <th>Finishing</th>
                                    <th>Mulai Finishing</th>
                                    <th>Selesai Finishing</th>
                                    <!-- <th>Id Gudang</th> -->
                                    <!-- <th>Id User Processing Gudang</th> -->
                                    <th>Masuk Gudang</th>
                                    <th>Keluar Gudang</th>
                                    <!-- <th>Jumlah Produk</th>
                                    <th>Halaman</th> -->
                                    <th>Keterangan</th>

                                </tr><?php
                                        foreach ($tbl_stok_barang_detail_data as $tbl_stok_barang_detail) {
                                        ?>
                                    <tr>
                                        <td width="10px"><?php echo ++$start ?></td>
                                        <td>
                                            <?php //echo $tbl_stok_barang_detail->date_pesan_cetak 
                                            ?>
                                            <img src="<?php echo site_url(); ?>/Barcode/set_barcode/<?php echo $tbl_stok_barang_detail->kode_stock; ?>">
                                        </td>
                                        <!-- <td>
                                            <?php
                                            // echo anchor(site_url('tbl_stok_barang_detail/read/' . $tbl_stok_barang_detail->id_stock), '<i class="fa fa-eye" aria-hidden="true">Detail</i>', 'class="btn btn-success btn-sm"');
                                            // echo '  ';
                                            // echo anchor(site_url('tbl_stok_barang_detail/update/' . $tbl_stok_barang_detail->id_stock), '<i class="fa fa-pencil-square-o" aria-hidden="true">Ubah Data</i>', 'class="btn btn-warning btn-sm"');
                                            ?>
                                        </td> -->



                                        <td><?php
                                            if (($tbl_stok_barang_detail->jumlah_cetak - $tbl_stok_barang_detail->jumlah_trans_penjualan) > 0) {


                                                if ($tbl_stok_barang_detail->date_selesai_cetak_stock) {
                                                    // echo "Selesai cetak :" . $tbl_stok_barang_detail->date_selesai_cetak_stock;
                                                    if ($tbl_stok_barang_detail->date_selesai_finishing_stock) {
                                                        // echo $tbl_stok_barang_detail->date_selesai_finishing_stock;
                                                        if ($tbl_stok_barang_detail->date_masuk_gudang) {
                                                            echo "Posisi Di Gudang";
                                                            echo "<br/>";
                                                            echo anchor(site_url('Trans_penjualan/create/' . $tbl_stok_barang_detail->uuid_stock), '<i class="fa fa-pencil-square-o" aria-hidden="true"> Kirim ke Pembeli </i>', 'class="btn btn-success btn-sm"');
                                                        } else {
                                                            echo "---";
                                                        }
                                                    } else {
                                                        if ($tbl_stok_barang_detail->date_selesai_cetak_stock) {
                                                            echo "Proses Finishing";
                                                            print_r("<br/>");
                                                            echo anchor(site_url('Trans_gudang/create/' . $tbl_stok_barang_detail->uuid_trans_finishing), '<i class="fa fa-database" aria-hidden="true"> Kirim ke gudang </i>', 'class="btn btn-warning btn-sm"');
                                                            echo "<br/>";

                                                            echo anchor(site_url('Trans_penjualan/create/' . $tbl_stok_barang_detail->uuid_stock), '<i class="fa fa-pencil-square-o" aria-hidden="true"> Kirim ke Pembeli </i>', 'class="btn btn-success btn-sm"');
                                                        } else {
                                                            echo "---";
                                                        }
                                                    }
                                                } else {
                                                    echo "Proses Cetak";
                                                    echo "<br/>";
                                                    echo anchor(site_url('Trans_finishing/create/' . $tbl_stok_barang_detail->uuid_trans_cetak), '<i class="fa fa-pencil-square-o" aria-hidden="true">Proses Cetak Selesai (Finishing)</i>', 'class="btn btn-warning btn-sm"');
                                                }
                                            } else {
                                                echo "<strong> HABIS </strong>";
                                            }
                                            ?></td>





                                        <td><?php
                                            echo "MAPEL : " . $tbl_stok_barang_detail->mapel_produk;
                                            echo "<br/>";
                                            echo "TINGKAT : " . $tbl_stok_barang_detail->tingkat_produk;
                                            echo "<br/>";
                                            echo "KELAS : " . $tbl_stok_barang_detail->kelas_produk;
                                            echo "<br/>";
                                            echo "SEMESTER : " . $tbl_stok_barang_detail->semester_produk;
                                            echo "<br/>";
                                            echo "JUMLAH : " . $tbl_stok_barang_detail->jumlah_cetak;
                                            echo "<br/>";
                                            echo "Halaman : " . $tbl_stok_barang_detail->halaman_cetak;
                                            ?></td>

                                        <td>
                                            <?php
                                            echo "<strong> Sisa : " . ($tbl_stok_barang_detail->jumlah_cetak - $tbl_stok_barang_detail->jumlah_trans_penjualan) . "</strong>";
                                            echo "<br/>";
                                            echo "Jumlah Cetak : " . $tbl_stok_barang_detail->jumlah_cetak;
                                            echo "<br/>";
                                            echo "penjualan : " . $tbl_stok_barang_detail->jumlah_trans_penjualan;
                                            echo "<br/>";
                                            echo "retur :";
                                            echo "<br/>";
                                            ?>
                                        </td>
                                        <!-- <td><?php //echo $tbl_stok_barang_detail->kelas_produk 
                                                    ?></td> -->
                                        <td><?php
                                            echo $tbl_stok_barang_detail->nama_perusahaan_cetak;
                                            echo "<br/>";
                                            echo "Alamat : " . $tbl_stok_barang_detail->alamat_perusahaan_cetak;

                                            ?></td>
                                        <!-- <td><?php //echo $tbl_stok_barang_detail->id_user_processing_cetak 
                                                    ?></td> -->
                                        <!-- <td><?php //echo $tbl_stok_barang_detail->id_cover 
                                                    ?></td> -->
                                        <td><?php echo $tbl_stok_barang_detail->date_pesan_cetak ?></td>
                                        <td><?php
                                            if ($tbl_stok_barang_detail->date_selesai_cetak_stock) {
                                                echo $tbl_stok_barang_detail->date_selesai_cetak_stock;
                                            } else {
                                                echo "Proses Cetak";
                                                echo "<br/>";
                                                echo anchor(site_url('Trans_finishing/create/' . $tbl_stok_barang_detail->uuid_trans_cetak), '<i class="fa fa-pencil-square-o" aria-hidden="true">Proses Cetak Selesai (Finishing)</i>', 'class="btn btn-warning btn-sm"');
                                            }

                                            ?></td>
                                        <!-- <td><?php //echo $tbl_stok_barang_detail->id_tbl_finishing 
                                                    ?></td> -->
                                        <!-- <td><?php //echo $tbl_stok_barang_detail->id_user_processing_finishing 
                                                    ?></td> -->
                                        <td><?php
                                            if ($tbl_stok_barang_detail->date_pesan_finishing) {
                                                echo $tbl_stok_barang_detail->nama_perusahaan_finishing;
                                                echo "<br/>";
                                                echo "Alamat :" . $tbl_stok_barang_detail->alamat_perusahaan_finishing;
                                            } else {
                                                echo "---";
                                            }

                                            ?></td>
                                        <td><?php echo $tbl_stok_barang_detail->date_pesan_finishing ?></td>
                                        <td><?php

                                            if ($tbl_stok_barang_detail->date_selesai_finishing_stock) {
                                                echo $tbl_stok_barang_detail->date_selesai_finishing_stock;
                                            } else {
                                                if ($tbl_stok_barang_detail->date_selesai_cetak_stock) {
                                                    echo "Proses Finishing";
                                                    print_r("<br/>");
                                                    echo anchor(site_url('Trans_gudang/create/' . $tbl_stok_barang_detail->uuid_trans_finishing), '<i class="fa fa-database" aria-hidden="true"> Kirim ke gudang </i>', 'class="btn btn-warning btn-sm"');
                                                    echo "<br/>";
                                                    echo anchor(site_url('Trans_penjualan/create/' . $tbl_stok_barang_detail->uuid_stock), '<i class="fa fa-pencil-square-o" aria-hidden="true"> Kirim ke Pembeli </i>', 'class="btn btn-success btn-sm"');
                                                } else {
                                                    echo "---";
                                                }
                                            }

                                            ?></td>
                                        <!-- <td><?php //echo $tbl_stok_barang_detail->id_gudang 
                                                    ?></td> -->
                                        <!-- <td><?php //echo $tbl_stok_barang_detail->id_user_processing_gudang 
                                                    ?></td> -->
                                        <td><?php
                                            // echo $tbl_stok_barang_detail->date_masuk_gudang


                                            if ($tbl_stok_barang_detail->date_masuk_gudang) {
                                                // echo anchor(site_url('#' . $tbl_stok_barang_detail->uuid_trans_finishing), '<i class="fa fa-pencil-square-o" aria-hidden="true"> Kirim ke Pembeli </i>', 'class="btn btn-warning btn-sm"');
                                                echo $tbl_stok_barang_detail->date_masuk_gudang;
                                            } else {
                                                echo "---";
                                            }

                                            ?></td>
                                        <td><?php
                                            if ($tbl_stok_barang_detail->date_keluar_gudang) {
                                                echo $tbl_stok_barang_detail->date_keluar_gudang;
                                            } else {
                                                echo "---";
                                            }
                                            ?></td>
                                        <!-- <td><?php //echo $tbl_stok_barang_detail->jumlah_cetak 
                                                    ?></td>
                                        <td><?php //echo $tbl_stok_barang_detail->halaman_cetak 
                                            ?></td> -->
                                        <td><?php echo $tbl_stok_barang_detail->keterangan_stock ?></td>

                                    </tr>
                                <?php
                                        }
                                ?>
                            </table>
                            <div class="row">
                                <div class="col-md-6">

                                </div>
                                <div class="col-md-6 text-right">
                                    <?php echo $pagination ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#mytable").dataTable({
            scrollY: 400,
            scrollX: true
        });
    });
</script>