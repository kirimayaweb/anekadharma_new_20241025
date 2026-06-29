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

                                $this->load->helper('dashboard');

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

                                    <?php
                                    $this->load->view('anekadharma/dashboard/partials/laporan_bulan_datatable', array(
                                        'table_id' => 'example_labarugi',
                                        'card_title' => 'LABA-RUGI BULANAN',
                                        'card_icon' => 'fa-chart-line',
                                        'theme_class' => 'dashboard-dt-theme-green',
                                        'rows' => $Tbl_BULAN_labarugi_data,
                                        'can_edit' => !empty($can_edit_laporan_dashboard),
                                        'can_publish' => !empty($can_edit_laporan_dashboard),
                                        'report_type' => 'laba_rugi',
                                        'publish_ajax_url' => site_url('Dashboard/ajax_laporan_publish_toggle'),
                                        'update_label' => 'Update',
                                        'cetak_label' => 'Cetak',
                                        'col_class' => 'col-lg-6 col-xs-12',
                                        'yellow_border' => true,
                                    ));

                                    $this->load->view('anekadharma/dashboard/partials/laporan_bulan_datatable', array(
                                        'table_id' => 'example_neraca',
                                        'card_title' => 'NERACA BULANAN',
                                        'card_icon' => 'fa-balance-scale',
                                        'theme_class' => 'dashboard-dt-theme-purple',
                                        'rows' => $Tbl_BULAN_neraca_data,
                                        'can_edit' => !empty($can_edit_laporan_dashboard),
                                        'can_publish' => !empty($can_edit_laporan_dashboard),
                                        'report_type' => 'neraca',
                                        'publish_ajax_url' => site_url('Dashboard/ajax_laporan_publish_toggle'),
                                        'update_label' => 'Update',
                                        'cetak_label' => 'Cetak',
                                        'col_class' => 'col-lg-6 col-xs-12',
                                        'yellow_border' => true,
                                    ));
                                    ?>

                                </div>


                                <div class="row">

                                    <?php
                                    $this->load->view('anekadharma/dashboard/partials/laporan_bulan_datatable', array(
                                        'table_id' => 'example_jurnal_kas',
                                        'card_title' => 'JURNAL KAS',
                                        'card_icon' => 'fa-cash-register',
                                        'theme_class' => 'dashboard-dt-theme-blue',
                                        'rows' => $Data_group_by_month_Jurnal_kas,
                                        'can_edit' => !empty($can_edit_jurnal_kas_dashboard),
                                        'update_label' => 'Update',
                                        'cetak_label' => 'Cetak',
                                        'col_class' => 'col-lg-10 offset-lg-1 col-xs-12',
                                    ));
                                    ?>

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
    function initDashboardLaporanDt(selector) {
        if (!$.fn.DataTable) {
            return;
        }
        $(selector).each(function() {
            var $table = $(this);
            if ($.fn.DataTable.isDataTable($table)) {
                return;
            }
            var dt = $table.DataTable({
                dom: '<"dashboard-dt-toolbar"lpf>rt<"dashboard-dt-footer"i>',
                scrollY: '320px',
                scrollX: true,
                scrollCollapse: true,
                autoWidth: false,
                pageLength: 12,
                lengthMenu: [[6, 12, 24, -1], [6, 12, 24, 'Semua']],
                order: [[2, 'desc']],
                columnDefs: [
                    { orderable: false, targets: [0, 3] },
                    { searchable: false, targets: [0, 3] }
                ],
                language: {
                    search: 'Cari:',
                    searchPlaceholder: 'Tahun / bulan...',
                    lengthMenu: 'Tampilkan _MENU_',
                    info: 'Menampilkan _START_–_END_ dari _TOTAL_ bulan',
                    infoEmpty: 'Belum ada bulan',
                    infoFiltered: '(filter dari _MAX_ bulan)',
                    zeroRecords: 'Tidak ditemukan',
                    paginate: {
                        first: 'Awal',
                        last: 'Akhir',
                        next: '&rsaquo;',
                        previous: '&lsaquo;'
                    }
                },
                drawCallback: function() {
                    var api = this.api();
                    var start = api.page.info().start;
                    api.column(0, { page: 'current' }).nodes().each(function(cell, i) {
                        cell.innerHTML = '<span class="dashboard-dt-no-index">' + (start + i + 1) + '</span>';
                    });
                }
            });
            dt.draw(false);
        });
    }

    function dashboardLaporanCetakUrl(reportType, year, month) {
        if (reportType === 'laba_rugi') {
            return '<?php echo site_url('Tbl_laba_rugi/labarugi_print'); ?>/' + year + '/' + month;
        }
        if (reportType === 'neraca') {
            return '<?php echo site_url('Tbl_neraca_data/neraca_cetak'); ?>/' + year + '/' + month;
        }
        return '';
    }

    function dashboardSyncPublishRowActions($table, year, month, isPublished, cetakLabel) {
        var reportType = $table.attr('data-publish-report-type') || '';
        var cetakUrl = isPublished ? dashboardLaporanCetakUrl(reportType, year, month) : '';
        var label = cetakLabel || $table.attr('data-cetak-label') || 'Cetak';
        var $scope = $table.closest('.dashboard-dt-card');
        if (!$scope.length) {
            $scope = $table.closest('.dataTables_wrapper');
        }
        if (!$scope.length) {
            $scope = $table;
        }

        $scope.find('tr[data-year="' + year + '"][data-month="' + month + '"]').each(function() {
            var $row = $(this);
            var $actions = $row.find('td.dashboard-dt-actions');
            var hasData = $row.attr('data-has-data') === '1';

            $row.attr('data-published', isPublished ? '1' : '0');

            if (isPublished) {
                $actions.find('.dashboard-dt-btn-publish').hide();
                $actions.find('.dashboard-dt-btn-cancel-publish').show();
            } else {
                $actions.find('.dashboard-dt-btn-cancel-publish').hide();
                $actions.find('.dashboard-dt-btn-publish').show().prop('disabled', !hasData);
            }

            var $cetak = $actions.find('.dashboard-dt-btn-cetak');
            if (isPublished && cetakUrl) {
                if ($cetak.is('span')) {
                    $cetak.replaceWith(
                        '<a href="' + cetakUrl + '" class="btn btn-dt-cetak btn-sm dashboard-dt-btn-cetak" target="_blank"><i class="fa fa-print"></i> ' + label + '</a>'
                    );
                } else {
                    $cetak.attr('href', cetakUrl).removeClass('dashboard-dt-btn-cetak-off dashboard-dt-btn-cetak-placeholder');
                }
            } else {
                if ($cetak.is('a')) {
                    $cetak.replaceWith(
                        '<span class="btn btn-dt-cetak btn-sm dashboard-dt-btn-cetak dashboard-dt-btn-cetak-placeholder dashboard-dt-btn-cetak-off"><i class="fa fa-print"></i> ' + label + '</span>'
                    );
                } else {
                    $cetak.addClass('dashboard-dt-btn-cetak-off dashboard-dt-btn-cetak-placeholder');
                }
            }
        });
    }

    $(document).ready(function() {
        initDashboardLaporanDt('.dashboard-laporan-dt');

        $(document).on('click', '.dashboard-dt-btn-publish, .dashboard-dt-btn-cancel-publish', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var $btn = $(this);
            if ($btn.prop('disabled')) {
                return;
            }

            var $table = $btn.closest('table.dashboard-laporan-dt');
            if (!$table.length || !$table.attr('data-publish-ajax-url')) {
                return;
            }

            var ajaxUrl = $table.attr('data-publish-ajax-url');
            var reportType = $table.attr('data-publish-report-type');
            var $row = $btn.closest('tr');
            var year = parseInt($row.attr('data-year'), 10);
            var month = parseInt($row.attr('data-month'), 10);
            var action = $btn.hasClass('dashboard-dt-btn-publish') ? 'publish' : 'cancel';
            var willPublish = action === 'publish';

            $btn.prop('disabled', true);

            // Update tombol langsung (optimistic UI)
            dashboardSyncPublishRowActions($table, year, month, willPublish);

            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                dataType: 'json',
                data: {
                    report_type: reportType,
                    year: year,
                    month: month,
                    action: action
                }
            }).done(function(res) {
                if (!res || !res.ok) {
                    dashboardSyncPublishRowActions($table, year, month, !willPublish);
                    alert((res && res.message) ? res.message : 'Gagal memproses publish.');
                    return;
                }

                dashboardSyncPublishRowActions($table, year, month, !!res.is_published);
            }).fail(function(xhr) {
                dashboardSyncPublishRowActions($table, year, month, !willPublish);
                var msg = 'Gagal menghubungi server.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                alert(msg);
            }).always(function() {
                var $scope = $table.closest('.dashboard-dt-card');
                if (!$scope.length) {
                    $scope = $table;
                }
                $scope.find('tr[data-year="' + year + '"][data-month="' + month + '"] .dashboard-dt-btn-publish, tr[data-year="' + year + '"][data-month="' + month + '"] .dashboard-dt-btn-cancel-publish').prop('disabled', false);
            });
        });
    });
</script>

<style>
    .dashboard-dt-card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(0, 60, 120, 0.1) !important;
        margin-bottom: 18px;
    }

    .dashboard-dt-yellow-border {
        border: 2px solid #ffe566 !important;
        box-shadow:
            0 0 0 1px #fffbea,
            0 0 12px rgba(255, 214, 0, 0.35),
            0 8px 24px rgba(255, 193, 7, 0.18) !important;
        background: linear-gradient(180deg, #fffef8 0%, #ffffff 100%);
    }

    .dashboard-dt-yellow-border .dashboard-dt-wrap {
        border: 1px solid #fff0a8;
        border-radius: 10px;
        background: #fffef5;
        padding: 4px;
    }

    .dashboard-dt-yellow-border .dashboard-dt-table {
        border: 1px solid #ffe999;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
    }

    .dashboard-dt-yellow-border .dashboard-dt-table thead th {
        border-bottom: 2px solid #ffe566 !important;
    }

    .dashboard-dt-yellow-border .dashboard-dt-table tbody td {
        border-top: 1px solid #fff4c2 !important;
    }

    .dashboard-dt-yellow-border .dashboard-dt-card-header {
        border-bottom: 2px solid #ffe566 !important;
    }

    .dashboard-dt-card-header {
        color: #fff;
        border-bottom: none;
        padding: 14px 20px;
    }

    .dashboard-dt-card-header .card-title {
        color: #fff;
        font-weight: 700;
        letter-spacing: 0.4px;
        font-size: 1.05rem;
    }

    .dashboard-dt-theme-green-card .dashboard-dt-card-header {
        background: linear-gradient(135deg, #28a745 0%, #1e7e34 55%, #155724 100%);
    }

    .dashboard-dt-theme-purple-card .dashboard-dt-card-header {
        background: linear-gradient(135deg, #6f42c1 0%, #5a32a3 55%, #432681 100%);
    }

    .dashboard-dt-theme-blue-card .dashboard-dt-card-header {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 55%, #004085 100%);
    }

    .dashboard-dt-theme-green-card .dashboard-dt-table thead th {
        border-bottom-color: #28a745 !important;
    }

    .dashboard-dt-theme-green-card .dashboard-dt-table tbody tr:hover {
        box-shadow: inset 3px 0 0 #28a745;
    }

    .dashboard-dt-theme-purple-card .dashboard-dt-table thead th {
        border-bottom-color: #6f42c1 !important;
    }

    .dashboard-dt-theme-purple-card .dashboard-dt-table tbody tr:hover {
        box-shadow: inset 3px 0 0 #6f42c1;
    }

    .dashboard-dt-theme-blue-card .dashboard-dt-table thead th {
        border-bottom-color: #007bff !important;
    }

    .dashboard-dt-theme-blue-card .dashboard-dt-table tbody tr:hover {
        box-shadow: inset 3px 0 0 #007bff;
    }

    .dashboard-dt-title-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        margin-right: 10px;
        background: rgba(255, 255, 255, 0.18);
        border-radius: 8px;
        vertical-align: middle;
    }

    .dashboard-dt-card-body {
        padding: 18px 20px 14px;
        background: #fafbfd;
    }

    .dashboard-dt-wrap {
        width: 100%;
        background: #fff;
        border-radius: 10px;
        border: 1px solid #e3eaf3;
        padding: 12px;
    }

    .dashboard-dt-table {
        border-collapse: separate;
        border-spacing: 0;
    }

    .dashboard-dt-table thead th {
        background: linear-gradient(180deg, #f0f4f8 0%, #e2e8f0 100%);
        color: #1e3a5f;
        font-weight: 700;
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        vertical-align: middle;
        white-space: nowrap;
        border-bottom: 2px solid #007bff !important;
        padding: 12px 14px !important;
    }

    .dashboard-dt-table tbody td {
        vertical-align: middle;
        padding: 11px 14px !important;
        border-top: 1px solid #edf2f7;
        font-size: 0.92rem;
    }

    .dashboard-dt-table tbody tr {
        transition: background-color 0.18s ease, box-shadow 0.18s ease;
    }

    .dashboard-dt-table tbody tr:hover {
        background-color: #f0f7ff !important;
    }

    .dashboard-dt-row-current {
        background: linear-gradient(90deg, #e3f2fd 0%, #f8fbff 100%) !important;
        box-shadow: inset 4px 0 0 #17a2b8;
    }

    .dashboard-dt-row-current:hover {
        background: linear-gradient(90deg, #d6ebfa 0%, #eef6ff 100%) !important;
    }

    .dashboard-dt-no-index {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 28px;
        height: 28px;
        background: #e9ecef;
        color: #495057;
        font-weight: 700;
        border-radius: 50%;
        font-size: 0.82rem;
    }

    .dashboard-dt-row-current .dashboard-dt-no-index {
        background: #17a2b8;
        color: #fff;
    }

    .dashboard-dt-year-badge {
        display: inline-block;
        padding: 4px 12px;
        background: #e8f0fe;
        color: #1a56db;
        font-weight: 700;
        border-radius: 20px;
        font-size: 0.88rem;
    }

    .dashboard-dt-bulan-cell {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .dashboard-dt-bulan-nama {
        font-weight: 700;
        color: #212529;
        font-size: 0.95rem;
    }

    .dashboard-dt-bulan-num {
        display: inline-block;
        padding: 2px 8px;
        background: #f1f3f5;
        color: #6c757d;
        border-radius: 4px;
        font-size: 0.78rem;
        font-weight: 600;
    }

    .dashboard-dt-badge-current {
        background: #17a2b8;
        color: #fff;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 4px 10px;
    }

    .dashboard-dt-actions {
        white-space: nowrap;
    }

    .dashboard-dt-actions-triple {
        min-width: 300px;
    }

    .dashboard-dt-actions-triple .btn-dt-update,
    .dashboard-dt-actions-triple .btn-dt-publish,
    .dashboard-dt-actions-triple .btn-dt-cancel-publish,
    .dashboard-dt-actions-triple .btn-dt-cetak {
        min-width: 88px;
    }

    .dashboard-dt-btn-cetak-off {
        opacity: 0.42;
        filter: grayscale(0.35);
        cursor: not-allowed;
        pointer-events: none;
    }

    .dashboard-dt-btn-cetak-placeholder {
        border: none;
    }

    .dashboard-dt-actions .btn {
        min-width: 92px;
        margin: 2px 3px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.82rem;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .dashboard-dt-actions .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    .btn-dt-update {
        background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        border: none;
        color: #212529;
    }

    .btn-dt-update:hover {
        background: linear-gradient(135deg, #ffcd39 0%, #ffc107 100%);
        color: #212529;
    }

    .btn-dt-cetak {
        background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        border: none;
        color: #fff;
    }

    .btn-dt-cetak:hover {
        background: linear-gradient(135deg, #34ce57 0%, #28a745 100%);
        color: #fff;
    }

    .btn-dt-publish {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        border: none;
        color: #fff;
    }

    .btn-dt-publish:hover {
        background: linear-gradient(135deg, #1fc8e3 0%, #17a2b8 100%);
        color: #fff;
    }

    .btn-dt-cancel-publish {
        background: linear-gradient(135deg, #dc3545 0%, #bd2130 100%);
        border: none;
        color: #fff;
    }

    .btn-dt-cancel-publish:hover {
        background: linear-gradient(135deg, #e4606d 0%, #dc3545 100%);
        color: #fff;
    }

    .dashboard-dt-no-action {
        color: #adb5bd;
        font-size: 1.1rem;
    }

    .dt-col-no { width: 56px; }
    .dt-col-tahun { width: 90px; }
    .dt-col-action { min-width: 320px; }

    .dashboard-dt-toolbar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: flex-start;
        gap: 12px 20px;
        margin-bottom: 14px;
        padding: 10px 14px;
        background: linear-gradient(180deg, #f8fafc 0%, #eef3f9 100%);
        border: 1px solid #d8e2ef;
        border-radius: 8px;
    }

    .dashboard-dt-toolbar .dataTables_length,
    .dashboard-dt-toolbar .dataTables_filter,
    .dashboard-dt-toolbar .dataTables_paginate {
        float: none !important;
        text-align: left !important;
        margin: 0 !important;
    }

    .dashboard-dt-toolbar .dataTables_length select {
        border-radius: 6px;
        border-color: #ced4da;
        padding: 3px 8px;
    }

    .dashboard-dt-toolbar .dataTables_filter input {
        min-width: 200px;
        margin-left: 6px;
        border-radius: 20px;
        border: 1px solid #ced4da;
        padding: 5px 14px;
    }

    .dashboard-dt-toolbar .dataTables_filter input:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.15rem rgba(0, 123, 255, 0.2);
        outline: none;
    }

    .dashboard-dt-toolbar .paginate_button {
        border-radius: 6px !important;
        margin: 0 2px;
    }

    .dashboard-dt-footer {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: flex-start;
        gap: 8px;
        margin-top: 12px;
        padding-top: 8px;
        border-top: 1px solid #e9ecef;
        color: #6c757d;
        font-size: 0.88rem;
    }

    .dashboard-dt-footer .dataTables_info {
        float: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    div.dataTables_wrapper div.dataTables_scrollHead table.dataTable {
        margin-bottom: 0 !important;
    }

    div.dataTables_scrollBody {
        border-bottom: 1px solid #e3eaf3 !important;
    }
</style>