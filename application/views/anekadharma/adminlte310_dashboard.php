<div>

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
                        <h3 class="card-title">DASHBOARD.</h3>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo $action; ?>" method="post">

                  


                            <br />

                            <div class="box-body">
                                <div class="row">

                                    <?php if ($this->session->userdata('sess_username') <> "manager") {  ?>
                                        <div class="col-lg-3 col-xs-6">
                                            <a href="<?php echo $link_penjualan; ?>">
                                                <div class="small-box bg-info">
                                                    <a href="<?php echo $link_penjualan; ?>">
                                                        <div class=" inner">
                                                            <h3 style="font-size:25px;font-weight: bold;color:#FFFFFF"> PENJUALAN</h3>
                                                            <p style="color:#171313"> Transaksi baru</p>
                                                        </div>
                                                        <div class="icon">
                                                            <i class="ion ion-bag"></i>
                                                        </div>
                                                        <a href="<?php echo $link_penjualan; ?>" class="small-box-footer">Masuk Ke Form Penjualan <i class="fa fa-arrow-circle-right"></i></a>
                                                    </a>
                                                </div>
                                            </a>
                                        </div>





                                        <div class="col-lg-3 col-xs-6">
                                            <a href="Trans_bayar/proses_bayar">
                                                <div class="small-box bg-green">
                                                    <div class="inner">
                                                        <h3 style="font-size:25px;font-weight: bold;color:#FFFFFF">PEMBAYARAN</h3>
                                                        <p style="color:#171313">Proses Bayar / Angsuran</p>
                                                    </div>
                                                    <div class="icon">
                                                        <i class="ion ion-stats-bars"></i>
                                                    </div>
                                                    <a href="Trans_bayar/proses_bayar" class="small-box-footer">Proses Bayar <i class="fa fa-arrow-circle-right"></i></a>
                                                </div>
                                            </a>
                                        </div>

                                    <?php } ?>


                                    <div class="col-lg-3 col-xs-6">
                                        <a href="Trans_cetakinput/create_setting">
                                            <div class="small-box bg-yellow">
                                                <div class="inner">
                                                    <h3 style="font-size:25px;font-weight: bold;color:#FFFFFF">PO CETAK [NASKAH]</h3>
                                                    <p style="color:#171313">Transaksi Cetak Baru</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-person-add"></i>
                                                </div>
                                                <a href="Trans_cetakinput/create_setting" class="small-box-footer">Proses Cetak <i class="fa fa-arrow-circle-right"></i></a>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-3 col-xs-6">
                                        <a href="Trans_pemesanan/create_setting">
                                            <div class="small-box bg-red">
                                                <div class="inner">
                                                    <h3 style="font-size:25px;font-weight: bold;color:#FFFFFF">PEMESANAN</h3>
                                                    <p style="color:#171313">Ringkasan data belum terkirim</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-pie-graph"></i>
                                                </div>
                                                <a href="Trans_pemesanan/create_setting" class="small-box-footer">Detail Pesanan <i class="fa fa-arrow-circle-right"></i></a>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="box-body">
                                <div class="row">

                                    <div class="col-lg-3 col-xs-6">
                                        <a href="#">
                                            <div class="small-box bg-info">
                                                <a href="Trans_finishing/create_setting">
                                                    <div class=" inner">
                                                        <h3 style="font-size:25px;font-weight: bold;color:#FFFFFF"> FINISHING [INPUT COVER]</h3>
                                                        <p style="color:#171313"> Transaksi baru</p>
                                                    </div>
                                                    <div class="icon">
                                                        <i class="ion ion-bag"></i>
                                                    </div>
                                                    <a href="Trans_finishing/create_setting" class="small-box-footer">PROSES KE FINISHING <i class="fa fa-arrow-circle-right"></i></a>
                                                </a>
                                            </div>
                                        </a>
                                    </div>


                                    <div class="col-lg-3 col-xs-6">
                                        <a href="Tbl_sales">
                                            <div class="small-box bg-green">
                                                <div class="inner">
                                                    <h4 style="font-size:25px;font-weight: bold;color:#FFFFFF">SALES</h4>
                                                    <p style="color:#171313">CEK DATA TRANSAKSI SALES</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-stats-bars"></i>
                                                </div>
                                                <a href="Tbl_sales" class="small-box-footer">CEK SALES <i class="fa fa-arrow-circle-right"></i></a>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-3 col-xs-6">
                                        <a href="Trans_pengiriman">
                                            <div class="small-box bg-yellow">
                                                <div class="inner">
                                                    <h4 style="font-size:25px;font-weight: bold;color:#FFFFFF">KEKURANGAN PENGIRIMAN</h4>
                                                    <p style="color:#171313">CEK DATA PENGIRIMAN</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-person-add"></i>
                                                </div>
                                                <a href="Trans_pengiriman" class="small-box-footer">CEK DATA PENGIRIMAN <i class="fa fa-arrow-circle-right"></i></a>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-3 col-xs-6">
                                        <!-- <a href="Tbl_stok_barang_detail">
                                    <div class="small-box bg-red">
                                        <div class="inner">
                                            <h3 style="color:#FFFFFF">STOCK</h3>
                                            <p style="color:#FFFFFF">Ringkasan data Stock</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-pie-graph"></i>
                                        </div>
                                        <a href="Tbl_stok_barang_detail" class="small-box-footer">Detail Stock <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </a> -->
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>

                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

                <!-- iCheck -->

                <!-- /.card -->

                <!-- Bootstrap Switch -->

                <!-- /.card -->
            </div>

        </div>
    </section>
</div>