<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-warning box-solid">
                    <div class="box-header">
                        <h3 class="box-title">DASHBOARD </h3>

                    </div>

                    <br />
                    <form action="<?php echo $action; ?>" method="post">


                        <div class="box-body">







                            <?php

                            if (
                                $this->session->userdata('id_user_level') == 1 //superadmin
                                or
                                $this->session->userdata('id_user_level') == 2 //admin
                                or
                                $this->session->userdata('id_user_level') == 3 //manager
                                or
                                // $this->session->userdata('id_user_level') == 4 //produksi
                                // or 
                                $this->session->userdata('id_user_level') == 444 //pembelian
                                or
                                // $this->session->userdata('id_user_level') == 5 //gudang
                                // or 
                                // $this->session->userdata('id_user_level') == 555 //penjualan
                                // or 
                                // $this->session->userdata('id_user_level') == 6 //user
                                // or 
                                // $this->session->userdata('id_user_level') == 7 //kasir
                                // or 
                                // $this->session->userdata('id_user_level') == 8 //.........
                                // or 
                                $this->session->userdata('id_user_level') == 9 //accounting
                                or
                                $this->session->userdata('id_user_level') == 99 //administrator
                                or
                                // $this->session->userdata('id_user_level') == 777 ////accounting
                                // or 
                                // $this->session->userdata('id_user_level') == 888 //kabagkeuangan
                                // or 
                                $this->session->userdata('id_user_level') == 999 //direktur
                            ) {

                            ?>

                                <!-- Baris pembelian dan penjualan -->

                                <div class="row">
                                    <div class="col-12" style="text-align:center">
                                        <strong>PEMBELIAN</strong>
                                    </div>

                                </div>

                                <div class="row">



                                    <!-- HIDE UNTUK SEMUA ICON -->

                                    <div class="col-lg-3 col-xs-6">
                                        <a href="tbl_pembelian">
                                            <div class="small-box bg-green">
                                                <div class="inner">
                                                    <h3 style="font-size:25px;font-weight: bold;color:#FFFFFF">PEMBELIAN</h3>
                                                    <p style="color:#171313">Belanja Perusahaan</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-stats-bars"></i>
                                                </div>
                                                <a href="tbl_pembelian" class="small-box-footer">Pembelian <i class="fa fa-arrow-circle-right"></i></a>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-lg-3 col-xs-6">
                                        <a href="Tbl_pembelian/stock">
                                            <div class="small-box bg-yellow">
                                                <div class="inner">
                                                    <h3 style="font-size:25px;font-weight: bold;color:#FFFFFF">STOCK / PERSEDIAAN</h3>
                                                    <p style="color:#171313">Persediaan Barang</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-person-add"></i>
                                                </div>
                                                <a href="Tbl_pembelian/stock" class="small-box-footer">STOCK / PERSEDIAAN <i class="fa fa-arrow-circle-right"></i></a>
                                            </div>
                                        </a>
                                    </div>
                                    <!-- END OF HIDE UNTUK SEMUA ICON -->



                                    <div class="col-lg-3 col-xs-6">
                                        <a href="Tbl_pembelian/pembayaran_ke_supplier">
                                            <div class="small-box bg-blue">
                                                <div class="inner">
                                                    <h3 style="font-size:25px;font-weight: bold;color:#FFFFFF">PEMBAYARAN KE SUPPLIER</h3>
                                                    <p style="color:#171313">PEMBAYARAN</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-person-add"></i>
                                                </div>
                                                <a href="Tbl_pembelian/pembayaran_ke_supplier" class="small-box-footer">PEMBAYARAN KE SUPPLIER <i class="fa fa-arrow-circle-right"></i></a>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-lg-3 col-xs-6">

                                    </div>


                                </div>
                                <!-- end of Baris pembelian dan penjualan -->


                                <!-- ----------------------------------------------------- -->
                            <?php } ?>


                            <?php

                            if (
                                $this->session->userdata('id_user_level') == 1 //superadmin
                                or
                                $this->session->userdata('id_user_level') == 2 //admin
                                or
                                $this->session->userdata('id_user_level') == 3 //manager
                                or
                                // $this->session->userdata('id_user_level') == 4 //produksi
                                // or 
                                // $this->session->userdata('id_user_level') == 444 //pembelian
                                // or 
                                // // $this->session->userdata('id_user_level') == 5 //gudang
                                // or 
                                $this->session->userdata('id_user_level') == 555 //penjualan
                                or
                                // $this->session->userdata('id_user_level') == 6 //user
                                // or 
                                // $this->session->userdata('id_user_level') == 7 //kasir
                                // or 
                                // $this->session->userdata('id_user_level') == 8 //.........
                                // or 
                                $this->session->userdata('id_user_level') == 9 //accounting
                                or
                                $this->session->userdata('id_user_level') == 99 //administrator
                                or
                                // $this->session->userdata('id_user_level') == 777 ////accounting
                                // or 
                                // $this->session->userdata('id_user_level') == 888 //kabagkeuangan
                                // or 
                                $this->session->userdata('id_user_level') == 999 //direktur
                            ) {

                            ?>


                                <!-- Baris penjualan -->
                                <div class="row">
                                    <div class="col-12" style="text-align:center">
                                        <strong>PENJUALAN</strong>
                                    </div>

                                </div>

                                <div class="row">


                                    <!-- HIDE UNTUK SEMUA ICON -->

                                    <div class="col-lg-3 col-xs-6">
                                        <a href="Tbl_pembelian/stock">
                                            <div class="small-box bg-yellow">
                                                <div class="inner">
                                                    <h3 style="font-size:25px;font-weight: bold;color:#FFFFFF">STOCK / PERSEDIAAN</h3>
                                                    <p style="color:#171313">Persediaan Barang</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-person-add"></i>
                                                </div>
                                                <a href="Tbl_pembelian/stock" class="small-box-footer">STOCK / PERSEDIAAN <i class="fa fa-arrow-circle-right"></i></a>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-lg-3 col-xs-6">
                                        <a href="tbl_penjualan">
                                            <div class="small-box bg-green">
                                                <div class="inner">
                                                    <h3 style="font-size:25px;font-weight: bold;color:#FFFFFF">PENJUALAN</h3>
                                                    <p style="color:#171313">Penjualan ke customer</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-person-add"></i>
                                                </div>
                                                <a href="tbl_penjualan" class="small-box-footer">Penjualan ke customer <i class="fa fa-arrow-circle-right"></i></a>
                                            </div>
                                        </a>
                                    </div>
                                    <!-- END OF HIDE UNTUK SEMUA ICON -->


                                    <div class="col-lg-3 col-xs-6">
                                        <a href="Tbl_pembelian/pembayaran_dari_konsumen">
                                            <div class="small-box bg-orange">
                                                <div class="inner">
                                                    <h3 style="font-size:25px;font-weight: bold;color:#FFFFFF">PEMBAYARAN DARI KONSUMEN</h3>
                                                    <p style="color:#171313">PEMBAYARAN</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-person-add"></i>
                                                </div>
                                                <a href="Tbl_pembelian/pembayaran_dari_konsumen" class="small-box-footer">PEMBAYARAN DARI KONSUMEN <i class="fa fa-arrow-circle-right"></i></a>
                                            </div>
                                        </a>
                                    </div>


                                    <div class="col-lg-3 col-xs-6">
                                        <a href="Sys_unit_produk">
                                            <div class="small-box bg-teal">
                                                <div class="inner">
                                                    <h3 style="font-size:25px;font-weight: bold;color:#FFFFFF">INPUT PRODUK</h3>
                                                    <p style="color:#171313">Input Produk</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-person-add"></i>
                                                </div>
                                                <a href="Sys_unit_produk" class="small-box-footer">INPUT PRODUK<i class="fa fa-arrow-circle-right"></i></a>
                                            </div>
                                        </a>
                                    </div>

                                </div>
                                <!-- end of Baris penjualan -->
                                <div class="row">


                                    <!-- HIDE UNTUK SEMUA ICON -->

                                    <div class="col-lg-3 col-xs-6">
                                        <a href="Tbl_pembelian/pecah_satuan">
                                            <div class="small-box bg-pink">
                                                <div class="inner">
                                                    <h3 style="font-size:25px;font-weight: bold;color:#FFFFFF">PECAH SATUAN</h3>
                                                    <p style="color:#171313">Pecah Satuan</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-person-add"></i>
                                                </div>
                                                <a href="Tbl_pembelian/pecah_satuan" class="small-box-footer">PECAH SATUAN<i class="fa fa-arrow-circle-right"></i></a>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-lg-3 col-xs-6">
                                        <a href="Tbl_penjualan/RekapData/unit">
                                            <div class="small-box bg-purple">
                                                <div class="inner">
                                                    <h3 style="font-size:25px;font-weight: bold;color:#FFFFFF">REKAP PENJUALAN PER UNIT</h3>
                                                    <p style="color:#171313">Rekap Penjualan Per Unit</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-person-add"></i>
                                                </div>
                                                <a href="Tbl_penjualan/RekapData/unit" class="small-box-footer">REKAP PENJUALAN PER UNIT<i class="fa fa-arrow-circle-right"></i></a>
                                            </div>
                                        </a>
                                    </div>
                                    <!-- END OF HIDE UNTUK SEMUA ICON -->


                                    <div class="col-lg-3 col-xs-6">
                                        <a href="Tbl_penjualan/RekapData/konsumen_nama">
                                            <div class="small-box bg-gray">
                                                <div class="inner">
                                                    <h3 style="font-size:25px;font-weight: bold;color:#FFFFFF">REKAP PENJUALAN PER KONSUMEN</h3>
                                                    <p style="color:#171313">Rekap Penjualan Per Konsumen</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-person-add"></i>
                                                </div>
                                                <a href="Tbl_penjualan/RekapData/konsumen_nama" class="small-box-footer">REKAP PENJUALAN PER KONSUMEN<i class="fa fa-arrow-circle-right"></i></a>
                                            </div>
                                        </a>
                                    </div>


                                    <div class="col-lg-3 col-xs-6">
                                        <a href="Tbl_penjualan/RekapData/nama_barang">
                                            <div class="small-box bg-yellow">
                                                <div class="inner">
                                                    <h3 style="font-size:25px;font-weight: bold;color:#FFFFFF">REKAP PENJUALAN PER BARANG</h3>
                                                    <p style="color:#171313">Rekap Penjualan Per Barang</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-person-add"></i>
                                                </div>
                                                <a href="Tbl_penjualan/RekapData/nama_barang" class="small-box-footer">REKAP PENJUALAN PER BARANG<i class="fa fa-arrow-circle-right"></i></a>
                                            </div>
                                        </a>
                                    </div>

                                </div>
                                <!-- end of Baris penjualan -->


                            <?php } ?>





                            <?php

                            if (
                                $this->session->userdata('id_user_level') == 1 //superadmin
                                or
                                $this->session->userdata('id_user_level') == 2 //admin
                                or
                                $this->session->userdata('id_user_level') == 3 //manager
                                or
                                // $this->session->userdata('id_user_level') == 4 //produksi
                                // or 
                                // $this->session->userdata('id_user_level') == 444 //pembelian
                                // or 
                                // // $this->session->userdata('id_user_level') == 5 //gudang
                                // or 
                                // $this->session->userdata('id_user_level') == 555 //penjualan
                                // or 
                                // $this->session->userdata('id_user_level') == 6 //user
                                // or 
                                // $this->session->userdata('id_user_level') == 7 //kasir
                                // or 
                                // $this->session->userdata('id_user_level') == 8 //.........
                                // or 
                                $this->session->userdata('id_user_level') == 9 //accounting
                                or
                                $this->session->userdata('id_user_level') == 99 //administrator
                                or
                                $this->session->userdata('id_user_level') == 777 ////accounting
                                or
                                $this->session->userdata('id_user_level') == 888 //kabagkeuangan
                                or
                                $this->session->userdata('id_user_level') == 999 //direktur
                            ) {

                            ?>


                                <!-- Baris JURNAL -->
                                <div class="row">
                                    <div class="col-12" style="text-align:center">
                                        <strong>JURNAL</strong>
                                    </div>

                                </div>

                                <div class="row">


                                    <!-- HIDE UNTUK SEMUA ICON -->



                                    <div class="col-lg-3 col-xs-6">
                                        <a href="Tbl_pembelian/jurnal_pembelian2">
                                            <div class="small-box bg-green">
                                                <div class="inner">
                                                    <h3 style="font-size:25px;font-weight: bold;color:#FFFFFF">JURNAL PEMBELIAN</h3>
                                                    <p style="color:#171313">Jurnal Pembelian</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-person-add"></i>
                                                </div>
                                                <a href="Tbl_pembelian/jurnal_pembelian2" class="small-box-footer">Jurnal Pembelian <i class="fa fa-arrow-circle-right"></i></a>
                                            </div>
                                        </a>
                                    </div>
                                    <!-- END OF HIDE UNTUK SEMUA ICON -->

                                    <div class="col-lg-3 col-xs-6">
                                        <a href="Tbl_penjualan/jurnal_penjualan2">
                                            <div class="small-box bg-yellow">
                                                <div class="inner">
                                                    <h3 style="font-size:25px;font-weight: bold;color:#FFFFFF">JURNAL PENJUALAN</h3>
                                                    <p style="color:#171313">Jurnal Penjualan</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-stats-bars"></i>
                                                </div>
                                                <a href="Tbl_penjualan/jurnal_penjualan2" class="small-box-footer">Pembelian <i class="fa fa-arrow-circle-right"></i></a>
                                            </div>
                                        </a>
                                    </div>



                                    <div class="col-lg-3 col-xs-6">
                                        <a href="Tbl_pembelian/setting_kode_akun_pembelian2">
                                            <div class="small-box bg-blue">
                                                <div class="inner">
                                                    <h3 style="font-size:25px;font-weight: bold;color:#FFFFFF">SETTING KODE PEMBELIAN</h3>
                                                    <p style="color:#171313">Setting kode akun pembelian</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-person-add"></i>
                                                </div>
                                                <a href="Tbl_pembelian/setting_kode_akun_pembelian2" class="small-box-footer">SETTING KODE AKUN PEMBELIAN <i class="fa fa-arrow-circle-right"></i></a>
                                            </div>
                                        </a>
                                    </div>


                                    <div class="col-lg-3 col-xs-6">
                                        <a href="Tbl_penjualan/setting_kode_akun_penjualan2">
                                            <div class="small-box bg-red">
                                                <div class="inner">
                                                    <h3 style="font-size:25px;font-weight: bold;color:#FFFFFF">SETTING KODE PENJUALAN</h3>
                                                    <p style="color:#171313">Setting kode akun penjualan</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-person-add"></i>
                                                </div>
                                                <a href="Tbl_penjualan/setting_kode_akun_penjualan2" class="small-box-footer">SETTING KODE AKUN PENJUALAN <i class="fa fa-arrow-circle-right"></i></a>
                                            </div>
                                        </a>
                                    </div>


                                </div>
                                <!-- end of Baris penjualan -->

                            <?php } ?>




                            <?php

                            if (
                                $this->session->userdata('id_user_level') == 1 //superadmin
                                or
                                $this->session->userdata('id_user_level') == 2 //admin
                                or
                                $this->session->userdata('id_user_level') == 3 //manager
                                or
                                // $this->session->userdata('id_user_level') == 4 //produksi
                                // or 
                                // $this->session->userdata('id_user_level') == 444 //pembelian
                                // or 
                                // // $this->session->userdata('id_user_level') == 5 //gudang
                                // or 
                                // $this->session->userdata('id_user_level') == 555 //penjualan
                                // or 
                                // $this->session->userdata('id_user_level') == 6 //user
                                // or 
                                // $this->session->userdata('id_user_level') == 7 //kasir
                                // or 
                                // $this->session->userdata('id_user_level') == 8 //.........
                                // or 
                                $this->session->userdata('id_user_level') == 9 //accounting
                                or
                                $this->session->userdata('id_user_level') == 99 //administrator
                                or
                                $this->session->userdata('id_user_level') == 777 ////accounting
                                or
                                $this->session->userdata('id_user_level') == 888 //kabagkeuangan
                                or
                                $this->session->userdata('id_user_level') == 999 //direktur
                            ) {

                            ?>

                                <!-- Baris JURNAL -->
                                <div class="row">
                                    <div class="col-12" style="text-align:center">
                                        <strong>ACCOUNTING</strong>
                                    </div>

                                </div>

                                <div class="row">


                                    <!-- HIDE UNTUK SEMUA ICON -->


                                    <div class="col-lg-3 col-xs-6">
                                        <a href="Buku_besar">
                                            <div class="small-box bg-green">
                                                <div class="inner">
                                                    <h3 style="font-size:25px;font-weight: bold;color:#FFFFFF">BUKU BESAR</h3>
                                                    <p style="color:#171313">Buku Besar</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-person-add"></i>
                                                </div>
                                                <a href="Buku_besar" class="small-box-footer">Buku Besar<i class="fa fa-arrow-circle-right"></i></a>
                                            </div>
                                        </a>
                                    </div>
                                    <!-- END OF HIDE UNTUK SEMUA ICON -->


                                    <div class="col-lg-3 col-xs-6">
                                        <a href="neraca_saldo">
                                            <div class="small-box bg-blue">
                                                <div class="inner">
                                                    <h3 style="font-size:25px;font-weight: bold;color:#FFFFFF">NERACA SALDO</h3>
                                                    <p style="color:#171313">Neraca Saldo</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-person-add"></i>
                                                </div>
                                                <a href="neraca_saldo" class="small-box-footer">Neraca Saldo<i class="fa fa-arrow-circle-right"></i></a>
                                            </div>
                                        </a>
                                    </div>


                                    <div class="col-lg-3 col-xs-6">
                                        <a href="Tbl_kas_kecil">
                                            <div class="small-box bg-yellow">
                                                <div class="inner">
                                                    <h3 style="font-size:25px;font-weight: bold;color:#FFFFFF">KAS KECIL</h3>
                                                    <p style="color:#171313">Kas Kecil</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-person-add"></i>
                                                </div>
                                                <a href="Tbl_kas_kecil" class="small-box-footer">Kas Kecil<i class="fa fa-arrow-circle-right"></i></a>
                                            </div>
                                        </a>
                                    </div>


                                    <div class="col-lg-3 col-xs-6">
                                        <a href="Bukubank">
                                            <div class="small-box bg-red">
                                                <div class="inner">
                                                    <h3 style="font-size:25px;font-weight: bold;color:#FFFFFF">BUKU BANK</h3>
                                                    <p style="color:#171313">Buku Bank</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-person-add"></i>
                                                </div>
                                                <a href="Bukubank" class="small-box-footer">Buku Bank<i class="fa fa-arrow-circle-right"></i></a>
                                            </div>
                                        </a>
                                    </div>




                                </div>
                                <!-- end of Baris penjualan -->

                            <?php } ?>



                            <?php

                            if (
                                $this->session->userdata('id_user_level') == 1 //superadmin
                                or
                                $this->session->userdata('id_user_level') == 2 //admin
                                or
                                $this->session->userdata('id_user_level') == 3 //manager
                                or
                                // $this->session->userdata('id_user_level') == 4 //produksi
                                // or 
                                // $this->session->userdata('id_user_level') == 444 //pembelian
                                // or 
                                // // $this->session->userdata('id_user_level') == 5 //gudang
                                // or 
                                // $this->session->userdata('id_user_level') == 555 //penjualan
                                // or 
                                $this->session->userdata('id_user_level') == 6 //user
                                or
                                // $this->session->userdata('id_user_level') == 7 //kasir
                                // or 
                                // $this->session->userdata('id_user_level') == 8 //.........
                                // or 
                                $this->session->userdata('id_user_level') == 9 //accounting
                                or
                                $this->session->userdata('id_user_level') == 99 //administrator
                                or
                                $this->session->userdata('id_user_level') == 777 ////accounting
                                or
                                $this->session->userdata('id_user_level') == 888 //kabagkeuangan
                                or
                                $this->session->userdata('id_user_level') == 999 //direktur
                            ) {

                            ?>


                                <?php

                                function bulan_teks($angka_bulan)
                                {
                                    if ($angka_bulan == 1) {
                                        $bulan_teks = "Januari";
                                    } elseif ($angka_bulan == 2) {
                                        $bulan_teks = "Februari";
                                    } elseif ($angka_bulan == 3) {
                                        $bulan_teks = "Maret";
                                    } elseif ($angka_bulan == 4) {
                                        $bulan_teks = "April";
                                    } elseif ($angka_bulan == 5) {
                                        $bulan_teks = "Mei";
                                    } elseif ($angka_bulan == 6) {
                                        $bulan_teks = "Juni";
                                    } elseif ($angka_bulan == 7) {
                                        $bulan_teks = "Juli";
                                    } elseif ($angka_bulan == 8) {
                                        $bulan_teks = "Agustus";
                                    } elseif ($angka_bulan == 9) {
                                        $bulan_teks = "September";
                                    } elseif ($angka_bulan == 10) {
                                        $bulan_teks = "Oktober";
                                    } elseif ($angka_bulan == 11) {
                                        $bulan_teks = "November";
                                    } elseif ($angka_bulan == 12) {
                                        $bulan_teks = "Desember";
                                    } else {
                                        $bulan_teks = "";
                                    }
                                    return $bulan_teks;
                                }

                                ?>


                                <!-- Baris JURNAL -->
                                <div class="row">
                                    <div class="col-12" style="text-align:center">
                                        <strong>LAPORAN</strong>
                                    </div>

                                </div>

                                <div class="row">


                                    <!-- HIDE UNTUK SEMUA ICON -->


                                    <div class="col-lg-6 col-xs-12">
                                        <div class="card card-primary">

                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col-12">

                                                        <form action="<?php echo $action_input_labarugi_baru_bulanan; ?>" method="post">
                                                            <div class="row">
                                                                <?php
                                                                if ($status_laporan == "bukan_laporan") {
                                                                ?>
                                                                    <div class="col-5" text-align="right"> <strong>INPUT LABA-RUGI BULANAN:</strong></div>
                                                                <?php
                                                                } else {
                                                                ?>
                                                                    <div class="col-5" text-align="right"> <strong>LABA-RUGI BULANAN</strong></div>

                                                                <?php
                                                                }
                                                                ?>
                                                                <div class="col-4" text-align="left">
                                                                    <?php
                                                                    if ($status_laporan == "bukan_laporan") {
                                                                    ?>
                                                                        <div class="col-4" text-align="left">

                                                                            <!-- <form action="/action_page.php"> -->
                                                                            <!-- <label for="bulan">BULAN :</label> -->
                                                                            <input type="month" id="bulan_neraca" name="bulan_neraca">
                                                                            <!-- <input type="submit"> -->
                                                                            <!-- </form> -->

                                                                        </div>
                                                                    <?php
                                                                    }
                                                                    ?>

                                                                </div>
                                                                <div class="col-3" text-align="right">

                                                                    <?php //echo anchor(site_url('Sys_supplier/stock/'), 'CARI', 'class="btn btn-danger"');
                                                                    if ($status_laporan == "bukan_laporan") {
                                                                    ?>

                                                                        <button type="submit" class="btn btn-danger">Tambah</button>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>

                                                        </form>

                                                    </div>
                                                </div>
                                            </div>


                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12">

                                                        <table id="example_labarugi" class="display nowrap" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th style="text-align:center" width="10px">No</th>
                                                                    <th>Tahun</th>
                                                                    <th>Bulan</th>
                                                                    <th>Action</th>

                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php



                                                                $start = 0;
                                                                foreach ($Tbl_BULAN_labarugi_data as $list_data) {
                                                                    // if ($list_data->bulan_transaksi > 0) {
                                                                ?>

                                                                    <tr>

                                                                        <td><?php echo ++$start ?></td>

                                                                        <td align="left"><?php echo $list_data->tahun_neraca; ?></td>
                                                                        <td align="left"><?php echo $list_data->bulan_neraca . " (" . bulan_teks($list_data->bulan_neraca) . ")"; ?></td>
                                                                        <td align="left">
                                                                            <?php

                                                                            // if ($status_laporan == "bukan_laporan") {

                                                                            // $this->session->userdata('id_user_level') == 1 //superadmin
                                                                            // $this->session->userdata('id_user_level') == 2 //admin
                                                                            // $this->session->userdata('id_user_level') == 888 //kabagkeuangan

                                                                            // if ($this->session->userdata('id_user_level') == 1 or $this->session->userdata('id_user_level') == 2 or $this->session->userdata('id_user_level') == 888) {

                                                                            //     echo anchor(site_url('Tbl_laba_rugi/labarugi_form/' . $list_data->uuid_data_neraca), '<i class="fa fa-pencil-square-o" aria-hidden="true">Update Data</i>', 'class="btn btn-warning btn-xs"');
                                                                            // }

                                                                            if ($this->session->userdata('id_user_level') == 1 or $this->session->userdata('id_user_level') == 2 or $this->session->userdata('id_user_level') == 9) {

                                                                                echo anchor(site_url('Tbl_laba_rugi/labarugi_form/' . $list_data->tahun_neraca . '/' . $list_data->bulan_neraca), '<i class="fa fa-pencil-square-o" aria-hidden="true">Update Data</i>', 'class="btn btn-warning btn-xs"');
                                                                            }



                                                                            $sql = "SELECT * FROM `tbl_laba_rugi` WHERE `tahun_transaksi`='$list_data->tahun_neraca' And `bulan_transaksi`='$list_data->bulan_neraca' ";

                                                                            $GET_tbl_labarugi_data_RECORD = $this->db->query($sql);

                                                                            if ($GET_tbl_labarugi_data_RECORD->num_rows() > 0) {
                                                                                echo anchor(site_url('Tbl_laba_rugi/labarugi_print/' . $list_data->tahun_neraca . '/' . $list_data->bulan_neraca), '<i class="fa fa-pencil-square-o" aria-hidden="true">Cetak Laba-Rugi</i>', 'class="btn btn-success btn-xs" target="_blank"');
                                                                            }

                                                                            ?>
                                                                        </td>

                                                                    </tr>
                                                                <?php
                                                                    // }
                                                                }
                                                                ?>


                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <!-- END OF HIDE UNTUK SEMUA ICON -->


                                    <div class="col-lg-6 col-xs-12">
                                        <div class="card card-primary">

                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col-12">

                                                        <form action="<?php echo $action_input_neraca_baru_bulanan; ?>" method="post">
                                                            <div class="row">
                                                                <?php
                                                                if ($status_laporan == "bukan_laporan") {
                                                                ?>
                                                                    <div class="col-5" text-align="right"> <strong>INPUT NERACA BULANAN:</strong></div>
                                                                <?php
                                                                } else {
                                                                ?>
                                                                    <div class="col-5" text-align="right"> <strong>NERACA BULANAN</strong></div>

                                                                <?php
                                                                }
                                                                ?>
                                                                <div class="col-4" text-align="left">
                                                                    <?php
                                                                    if ($status_laporan == "bukan_laporan") {
                                                                    ?>
                                                                        <div class="col-4" text-align="left">

                                                                            <!-- <form action="/action_page.php"> -->
                                                                            <!-- <label for="bulan">BULAN :</label> -->
                                                                            <input type="month" id="bulan_neraca" name="bulan_neraca">
                                                                            <!-- <input type="submit"> -->
                                                                            <!-- </form> -->

                                                                        </div>
                                                                    <?php
                                                                    }
                                                                    ?>

                                                                </div>
                                                                <div class="col-3" text-align="right">

                                                                    <?php //echo anchor(site_url('Sys_supplier/stock/'), 'CARI', 'class="btn btn-danger"');
                                                                    if ($status_laporan == "bukan_laporan") {
                                                                    ?>

                                                                        <button type="submit" class="btn btn-danger">Tambah</button>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>

                                                        </form>

                                                    </div>
                                                </div>
                                            </div>


                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12">

                                                        <table id="example" class="display nowrap" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th style="text-align:center" width="10px">No</th>
                                                                    <th>Tahun</th>
                                                                    <th>Bulan</th>
                                                                    <th>Action</th>

                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php



                                                                $start = 0;
                                                                foreach ($Tbl_BULAN_neraca_data as $list_data) {
                                                                    // if ($list_data->bulan_transaksi > 0) {
                                                                ?>

                                                                    <tr>

                                                                        <td><?php echo ++$start ?></td>

                                                                        <td align="left"><?php echo $list_data->tahun_neraca; ?></td>
                                                                        <td align="left"><?php echo $list_data->bulan_neraca . " (" . bulan_teks($list_data->bulan_neraca) . ")"; ?></td>
                                                                        <td align="left">
                                                                            <?php

                                                                            // if ($status_laporan == "bukan_laporan") {

                                                                            // $this->session->userdata('id_user_level') == 1 //superadmin
                                                                            // $this->session->userdata('id_user_level') == 2 //admin
                                                                            // $this->session->userdata('id_user_level') == 888 //kabagkeuangan

                                                                            // if ($this->session->userdata('id_user_level') == 1 or $this->session->userdata('id_user_level') == 2 or $this->session->userdata('id_user_level') == 888) {

                                                                            //     echo anchor(site_url('Tbl_neraca_data/neraca_form/' . $list_data->uuid_data_neraca), '<i class="fa fa-pencil-square-o" aria-hidden="true">Update Data</i>', 'class="btn btn-warning btn-xs"');
                                                                            // }

                                                                            if ($this->session->userdata('id_user_level') == 1 or $this->session->userdata('id_user_level') == 2 or $this->session->userdata('id_user_level') == 9) {

                                                                                echo anchor(site_url('Tbl_neraca_data/neraca_form/' . $list_data->tahun_neraca . '/' . $list_data->bulan_neraca), '<i class="fa fa-pencil-square-o" aria-hidden="true">Update Data</i>', 'class="btn btn-warning btn-xs"');
                                                                            }



                                                                            $sql = "SELECT * FROM `tbl_neraca_data` WHERE `tahun_transaksi`='$list_data->tahun_neraca' And `bulan_transaksi`='$list_data->bulan_neraca' ";

                                                                            $GET_tbl_neraca_data_RECORD = $this->db->query($sql);

                                                                            if ($GET_tbl_neraca_data_RECORD->num_rows() > 0) {
                                                                                echo anchor(site_url('Tbl_neraca_data/neraca_cetak/' . $list_data->tahun_neraca . '/' . $list_data->bulan_neraca), '<i class="fa fa-pencil-square-o" aria-hidden="true">Cetak Neraca</i>', 'class="btn btn-success btn-xs" target="_blank"');
                                                                            }

                                                                            ?>
                                                                        </td>

                                                                    </tr>
                                                                <?php
                                                                    // }
                                                                }
                                                                ?>


                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>




                                </div>
                                <!-- end of Baris penjualan -->
                            <?php } ?>










                        </div>


                    </form>
                </div>



            </div>
        </div>


    </section>
</div>



<script>
    $(document).ready(function() {
        $('#example_labarugi').DataTable({
            "scrollY": 900,
            "scrollX": true
        });
    });
    $(document).ready(function() {
        $('#example_neraca').DataTable({
            "scrollY": 900,
            "scrollX": true
        });
    });
</script>