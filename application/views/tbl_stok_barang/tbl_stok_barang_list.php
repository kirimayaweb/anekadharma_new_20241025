<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-warning box-solid">

                    <div class="box-header">
                        <h3 class="box-title">KELOLA DATA TBL_STOK_barang</h3>
                    </div>

                    <div class="box-body">
                        <div class='row'>
                            <div class='col-md-9'>
                                <div style="padding-bottom: 10px;"'>
        <?php echo anchor(site_url('tbl_stok_barang/create'), '<i class="fa fa-wpforms" aria-hidden="true"></i> Tambah Data', 'class="btn btn-danger btn-sm"'); ?>
		<?php echo anchor(site_url('tbl_stok_barang/excel'), '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Ms Excel', 'class="btn btn-success btn-sm"'); ?>
		<?php echo anchor(site_url('tbl_stok_barang/word'), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Export Ms Word', 'class="btn btn-primary btn-sm"'); ?></div>
            </div>
            <div class=' col-md-3'>
                                    <form action="<?php echo site_url('tbl_stok_barang/index'); ?>" class="form-inline" method="get">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                                            <span class="input-group-btn">
                                                <?php
                                                if ($q <> '') {
                                                ?>
                                                    <a href="<?php echo site_url('tbl_stok_barang'); ?>" class="btn btn-default">Reset</a>
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
                            <table class="table table-bordered" style="margin-bottom: 10px">
                                <tr>
                                    <th>No</th>
                                    <!-- <th>Id Stock</th> -->
                                    <!-- <th>Uuid Stock</th> -->
                                    <!-- <th>Date Input</th> -->
                                    <!-- <th>Id User Input</th> -->
                                    <th>Id Tbl Produk</th>
                                    <th>Id Tbl Company Cetak</th>
                                    <th>Id Tbl Finishing</th>
                                    <th>Id Cover</th>
                                    <th>Jumlah Produk</th>
                                    <th>Halaman</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </tr><?php
                                        foreach ($tbl_stok_barang_data as $tbl_stok_barang) {
                                        ?>
                                    <tr>
                                        <td width="10px"><?php echo ++$start ?></td>
                                        <!-- <td><?php //echo $tbl_stok_barang->id_stock 
                                                    ?></td> -->
                                        <!-- <td><?php //echo $tbl_stok_barang->uuid_stock 
                                                    ?></td> -->
                                        <!-- <td><?php //echo $tbl_stok_barang->date_input 
                                                    ?></td> -->
                                        <!-- <td><?php //echo $tbl_stok_barang->id_user_input 
                                                    ?></td> -->
                                        <td><?php echo $tbl_stok_barang->id_tbl_produk ?></td>
                                        <td><?php echo $tbl_stok_barang->id_tbl_company_cetak ?></td>
                                        <td><?php echo $tbl_stok_barang->id_tbl_finishing ?></td>
                                        <td><?php echo $tbl_stok_barang->id_cover ?></td>
                                        <td><?php echo $tbl_stok_barang->jumlah_produk ?></td>
                                        <td><?php echo $tbl_stok_barang->halaman ?></td>
                                        <td><?php echo $tbl_stok_barang->keterangan ?></td>
                                        <td style="text-align:center" width="200px">
                                            <?php
                                            echo anchor(site_url('tbl_stok_barang/read/' . $tbl_stok_barang->id_stock), '<i class="fa fa-eye" aria-hidden="true"></i>', 'class="btn btn-danger btn-sm"');
                                            echo '  ';
                                            echo anchor(site_url('tbl_stok_barang/update/' . $tbl_stok_barang->id_stock), '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', 'class="btn btn-danger btn-sm"');
                                            echo '  ';
                                            echo anchor(site_url('tbl_stok_barang/delete/' . $tbl_stok_barang->id_stock), '<i class="fa fa-trash-o" aria-hidden="true"></i>', 'class="btn btn-danger btn-sm" Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
                                            ?>
                                        </td>
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