<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-warning box-solid">

                    <div class="box-header">
                        <h3 class="box-title">LAPORAN</h3>
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
                                                <li class="active"><a href="#tab_1" data-toggle="tab">Tahunan (Penjualan)</a></li>
                                                <li><a href="#tab_2" data-toggle="tab">Stock</a></li>
                                                <li><a href="#tab_3" data-toggle="tab">Sales</a></li>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="tab_1">


                                                    <table class="table table-striped table-bordered" style="margin-bottom: 10px" id="mytable">
                                                        <tr>
                                                        <th>No</th>
                                                        <th>Tahun<br/>Semester</th>
                                                        <th>Level<br/>Mapel</th>
                                                        <th>Stock<br/>Penjualan<br/>Retur</th>
                                                        </tr>
                                                        <?php
                                                        $start = 0;
                                                        // foreach ($tbl_sales_data as $tbl_sales) {
                                                        ?>
                                                        <tr>
                                                            <td width="10px"><?php echo ++$start ?></td>
                                                            <td><?php //echo $tbl_sales->nama_sales 
                                                                ?></td>
                                                            <td><?php //echo $tbl_sales->alamat_sales 
                                                                ?></td>
                                                            <td><?php //echo $tbl_sales->notelp_sales 
                                                                ?></td>
                                                            <td><?php //echo $tbl_sales->keterangan 
                                                                ?></td>
                                                        </tr>
                                                            <?php
                                                            // }
                                                            ?>
                                                    </table>



                                                </div>
                                                <div class="tab-pane" id="tab_2">

                                                        <table class="table table-bordered" style="margin-bottom: 10px">
                                                        <tr>
                                                        <th>No</th>
                                                        <!-- <th>Stock</th> -->
                                                        <th>Level<br/>Mapel</th>
                                                        <th>Stock<br/>Penjualan<br/>Retur</th>
                                                        </tr>
                                                        <?php
                                                        $start = 0;
                                                        // foreach ($tbl_sales_data as $tbl_sales) {
                                                        ?>
                                                        <tr>
                                                            <td width="10px"><?php echo ++$start ?></td>

                                                            <td><?php //echo $tbl_sales->alamat_sales 
                                                                ?></td>
                                                            <td><?php //echo $tbl_sales->notelp_sales 
                                                                ?></td>
                                                            <td><?php //echo $tbl_sales->keterangan 
                                                                ?></td>
                                                        </tr>
                                                            <?php
                                                            // }
                                                            ?>
                                                        </table>

                                                </div>
                                    <div class="tab-pane" id="tab_3">
                                        <table class="table table-bordered" style="margin-bottom: 10px">
                                            <tr>
                                    <th style="text-align:center" width="10px">No</th>
                                    <!-- <th style="text-align:center" width="100px">Action</th> -->
                                    <!-- <th style="text-align:center" width="100px">Transaksi</th> -->
                                    <!-- <th style="text-align:center" width="100px">History</th> -->
                                    <th>Kode Sales<br />Nama Sales</th>
                                    <th>Alamat Sales</th>
                                    <th>Keterangan</th>
                                            </tr>
                                            <?php
                                            $start = 0;
                                            foreach ($tbl_sales_data as $tbl_sales) {
                                            ?>
                                    <tr>
                                        <td><?php echo ++$start ?></td>

                                        <!-- <td>
                                            <?php
                                                //echo anchor(site_url('tbl_sales/read/' . $tbl_sales->uuid_sales), '<i class="fa fa-eye" aria-hidden="true">DETAIL</i>', 'class="btn btn-danger btn-sm"');
                                                //echo '  ';
                                                //echo anchor(site_url('tbl_sales/update/' . $tbl_sales->uuid_sales), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH DATA</i>', 'class="btn btn-danger btn-sm"');
                                            ?>
                                        </td>

                                        <td>
                                            <?php
                                                $uuid_stock = "kosong";
                                                //echo anchor(site_url('Trans_penjualan/create/' . $uuid_stock . '/' . $tbl_sales->uuid_sales), '<i class="fa fa-pencil-square-o" aria-hidden="true">PEMBELIAN</i>', 'class="btn btn-success btn-sm"');
                                                //echo '  ';
                                                //echo anchor(site_url('#/' . $tbl_sales->uuid_sales), '<i class="fa fa-pencil-square-o" aria-hidden="true">PEMBAYARAN</i>', 'class="btn btn-primary btn-sm"');
                                            ?>
                                        </td>

                                        <td>
                                            <?php
                                                //echo anchor(site_url('#/' . $tbl_sales->uuid_sales), '<i class="fa fa-eye" aria-hidden="true">TAGIHAN</i>', 'class="btn btn-warning btn-sm"');
                                                //echo '  ';
                                                //echo anchor(site_url('#/' . $tbl_sales->uuid_sales), '<i class="fa fa-pencil-square-o" aria-hidden="true">RETUR</i>', 'class="btn btn-warning btn-sm"');

                                            ?>
                                        </td> -->

                                        <td><?php
                                                echo $tbl_sales->kode_sales;
                                                echo "<br/>";
                                                echo "<strong>" . $tbl_sales->nama_sales . "</strong>";
                                            ?></td>
                                        <td><?php
                                                echo $tbl_sales->alamat_sales;
                                                echo "<br/>";
                                                echo $tbl_sales->notelp_sales;
                                            ?></td>
                                        <td><?php echo $tbl_sales->keterangan ?></td>

                                    </tr>
                                <?php
                                            }
                                ?>
                                        </table>

 
                                    </div>
                                </div>
                            </div>
                        </div>
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