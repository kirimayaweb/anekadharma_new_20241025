<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-warning box-solid">

                    <div class="box-header">
                        <h3 class="box-title">KELOLA DATA TBL_SALES</h3>
                    </div>

                    <div class="box-body">
                        <div class='row'>
                            <div class='col-md-9'>
                                <div style="padding-bottom: 10px;"'>
        <?php echo anchor(site_url('tbl_sales/create'), '<i class="fa fa-wpforms" aria-hidden="true"></i> Tambah Data Sales', 'class="btn btn-danger btn-sm"'); ?>
		<?php echo anchor(site_url('tbl_sales/excel'), '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Ms Excel', 'class="btn btn-success btn-sm"'); ?>
		<?php echo anchor(site_url('tbl_sales/word'), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Export Ms Word', 'class="btn btn-primary btn-sm"'); ?></div>
            </div>
            <div class=' col-md-3'>
                                    <form action="<?php echo site_url('tbl_sales/index'); ?>" class="form-inline" method="get">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                                            <span class="input-group-btn">
                                                <?php
                                                if ($q <> '') {
                                                ?>
                                                    <a href="<?php echo site_url('tbl_sales'); ?>" class="btn btn-default">Reset</a>
                                                <?php
                                                }
                                                ?>
                                                <button class="btn btn-primary" type="submit">Search</button>
                                            </span>
                                        </div>
                                    </form>
                                </div>
                            </div>


                            <!-- <div class="row" style="margin-bottom: 10px">
                                <div class="col-md-4 text-center">
                                    <div style="margin-top: 8px" id="message">
                                        <?php //echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; 
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-1 text-right">
                                </div>
                                <div class="col-md-3 text-right">

                                </div>
                            </div> -->
                            <table class="table table-bordered" style="margin-bottom: 10px">
                                <tr>
                                    <th style="text-align:center" width="10px">No</th>
                                    <th style="text-align:center" width="100px">Action</th>
                                    <th style="text-align:center" width="100px">Transaksi</th>
                                    <th style="text-align:center" width="100px">History</th>

                                    <th>Kode Sales<br />Nama Sales</th>
                                    <!-- <th>Nama Sales</th> -->
                                    <th>Tagihan </th>
                                    <!-- <th>Notelp Sales</th> -->
                                    <th>Ringkasan Tagihan </th>

                                </tr>
                                <?php
                                foreach ($tbl_sales_data as $tbl_sales) {
                                ?>
                                    <tr>
                                        <td><?php echo ++$start ?></td>

                                        <td>
                                            <?php
                                            echo anchor(site_url('tbl_sales/read/' . $tbl_sales->uuid_sales), '<i class="fa fa-eye" aria-hidden="true">DETAIL</i>', 'class="btn btn-danger btn-sm"');
                                            echo '  ';
                                            echo anchor(site_url('tbl_sales/update/' . $tbl_sales->uuid_sales), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH DATA</i>', 'class="btn btn-danger btn-sm"');
                                            // echo '  ';
                                            // echo anchor(site_url('tbl_sales/delete/' . $tbl_sales->id), '<i class="fa fa-trash-o" aria-hidden="true">HAPUS DATA</i>', 'class="btn btn-danger btn-sm" Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
                                            ?>
                                        </td>

                                        <td>
                                            <?php
                                            $uuid_stock = "kosong";
                                            echo anchor(site_url('Trans_penjualan/create_setting/' . $tbl_sales->uuid_sales), '<i class="fa fa-pencil-square-o" aria-hidden="true">PEMBELIAN</i>', 'class="btn btn-success btn-sm"');
                                            echo '  ';
                                            echo anchor(site_url('Trans_bayar/bayar_by_sales/' . $tbl_sales->uuid_sales), '<i class="fa fa-pencil-square-o" aria-hidden="true">PEMBAYARAN</i>', 'class="btn btn-primary btn-sm"');
                                            ?>
                                        </td>

                                        <td>
                                            <?php
                                            echo anchor(site_url('tbl_sales'), '<i class="fa fa-eye" aria-hidden="true">TAGIHAN</i>', 'class="btn btn-warning btn-sm"');
                                            echo '  ';
                                            echo anchor(site_url('tbl_sales'), '<i class="fa fa-pencil-square-o" aria-hidden="true">RETUR</i>', 'class="btn btn-warning btn-sm"');

                                            ?>
                                        </td>

                                        <td><?php
                                            echo $tbl_sales->kode_sales;
                                            echo "<br/>";
                                            echo "<strong>" . $tbl_sales->nama_sales . "</strong>";
                                            ?></td>

                                        <!-- <td><?php //echo $tbl_sales->nama_sales 
                                                    ?></td> -->


                                        <td><?php
                                            // echo $tbl_sales->alamat_sales;
                                            // echo "<br/>";
                                            // echo $tbl_sales->notelp_sales;
                                            ?></td>


                                        <!-- <td><?php //echo $tbl_sales->notelp_sales 
                                                    ?></td> -->



                                        <td><?php
                                            //echo $tbl_sales->keterangan 
                                            ?></td>

                                    </tr>
                                <?php
                                }
                                ?>
                            </table>



                            <!-- MESSAGE NOTIFIKASI -->
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
                            <!-- END OF MESSAGE NOTIFIKASI -->


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