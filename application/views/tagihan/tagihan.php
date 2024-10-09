<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-warning box-solid">

                    <div class="box-header">
                        <h3 class="box-title">TAGIHAN</h3>
                    </div>

                    <div class="box-body">
                        <div class='row'>
                            <div class='col-md-12'>
                                <div style="padding-bottom: 10px;"'>
                                        <?php //echo anchor(site_url('sys_cover/create'), '<i class="fa fa-wpforms" aria-hidden="true"></i> Tambah Data', 'class="btn btn-danger btn-sm"'); 
                                        ?>
		                                <?php //echo anchor(site_url('sys_cover/excel'), '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Ms Excel', 'class="btn btn-success btn-sm"'); 
                                        ?>
		                                <?php //echo anchor(site_url('sys_cover/word'), '<i class="fa fa-file-word-o" aria-hidden="true"></i> Export Ms Word', 'class="btn btn-primary btn-sm"'); 
                                        ?>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="nav-tabs-custom">
                                            <ul class="nav nav-tabs">
                                                <li class="active"><a href="#tab_1" data-toggle="tab">Tab 1</a></li>
                                                <li><a href="#tab_2" data-toggle="tab">Tab 2</a></li>
                                                <li><a href="#tab_3" data-toggle="tab">Tab 3</a></li>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="tab_1">


                                                    <table class="table table-striped table-bordered" style="margin-bottom: 10px" id="mytable">
                                                        <tr>
                                                            <th>No</th>
                                                            <!-- <th>Uuid Produk</th> -->
                                                            <!-- <th>Date Input</th> -->
                                                            <th>Tingkat</th>
                                                            <th>Kelas</th>
                                                            <th>Tahun</th>
                                                            <th>Semester</th>
                                                            <th>Mapel</th>
                                                            <th>Halaman</th>
                                                            <th>Keterangan</th>
                                                            <th>Action</th>
                                                        </tr><?php
                                                                foreach ($tbl_produk_data as $tbl_produk) {
                                                                ?>
                                                            <tr>
                                                                <td width="10px"><?php echo ++$start ?></td>
                                                                <!-- <td><?php //echo $tbl_produk->uuid_produk 
                                                                            ?></td> -->
                                                                <!-- <td><?php //echo $tbl_produk->date_input 
                                                                            ?></td> -->
                                                                <td><?php echo $tbl_produk->tingkat ?></td>
                                                                <td><?php echo $tbl_produk->kelas ?></td>
                                                                <td><?php echo $tbl_produk->tahun ?></td>
                                                                <td><?php echo $tbl_produk->semester ?></td>
                                                                <td><?php echo $tbl_produk->mapel ?></td>
                                                                <td><?php echo $tbl_produk->halaman ?></td>
                                                                <td><?php echo $tbl_produk->keterangan ?></td>
                                                                <td style="text-align:center" width="200px">
                                                                    <?php
                                                                    echo anchor(site_url('tbl_produk/read/' . $tbl_produk->id), '<i class="fa fa-eye" aria-hidden="true"></i>', 'class="btn btn-danger btn-sm"');
                                                                    echo '  ';
                                                                    echo anchor(site_url('tbl_produk/update/' . $tbl_produk->id), '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', 'class="btn btn-danger btn-sm"');
                                                                    echo '  ';
                                                                    echo anchor(site_url('tbl_produk/delete/' . $tbl_produk->id), '<i class="fa fa-trash-o" aria-hidden="true"></i>', 'class="btn btn-danger btn-sm" Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                                }
                                                        ?>
                                                    </table>



                                                </div>
                                                <div class="tab-pane" id="tab_2">

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

                                    </div>
                                    <div class="tab-pane" id="tab_3">
                                    <table class="table table-bordered" style="margin-bottom: 10px">
                                    <tr>
                                    <th>No</th>
                                    <!-- <th>Uuid Sales</th> -->
                                    <!-- <th>Date Input</th> -->
                                    <!-- <th>Id User Input</th> -->
                                    <th>Nama Sales</th>
                                    <th>Alamat Sales</th>
                                    <th>Notelp Sales</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                    </tr><?php
                                            foreach ($tbl_sales_data as $tbl_sales) {
                                            ?>
                                    <tr>
                                        <td width="10px"><?php echo ++$start ?></td>
                                        <!-- <td><?php //echo $tbl_sales->uuid_sales 
                                                    ?></td> -->
                                        <!-- <td><?php //echo $tbl_sales->date_input 
                                                    ?></td> -->
                                        <!-- <td><?php //echo $tbl_sales->id_user_input 
                                                    ?></td> -->
                                        <td><?php echo $tbl_sales->nama_sales ?></td>
                                        <td><?php echo $tbl_sales->alamat_sales ?></td>
                                        <td><?php echo $tbl_sales->notelp_sales ?></td>
                                        <td><?php echo $tbl_sales->keterangan ?></td>
                                        <td style="text-align:center" width="200px">
                                            <?php
                                                echo anchor(site_url('tbl_sales/read/' . $tbl_sales->id), '<i class="fa fa-eye" aria-hidden="true"></i>', 'class="btn btn-danger btn-sm"');
                                                echo '  ';
                                                echo anchor(site_url('tbl_sales/update/' . $tbl_sales->id), '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', 'class="btn btn-danger btn-sm"');
                                                echo '  ';
                                                echo anchor(site_url('tbl_sales/delete/' . $tbl_sales->id), '<i class="fa fa-trash-o" aria-hidden="true"></i>', 'class="btn btn-danger btn-sm" Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                            }
                                    ?>
                                    </table>

 
                                    </div>
                                    <!-- /.tab-pane -->
                                </div>
                                <!-- /.tab-content -->
                            </div>
                            <!-- nav-tabs-custom -->
                        </div>
                        <!-- /.col -->


                        <!-- /.col -->
                    </div>


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
              scrollY: 550,
              scrollX: true
            });
          });
        </script>