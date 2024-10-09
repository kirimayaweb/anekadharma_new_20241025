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
                        <div class="row">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-2">
                                Tahun
                                <select name="tahun_setting" id="tahun_setting" class="form-control select2" style="width: 100%; height: 40px;" onchange="getSelectValue();">
                                    <option value="<?php if ($thn_sekarang) {
                                                        echo $thn_sekarang;
                                                    } ?>"><?php if ($thn_sekarang) {
                                                                echo $thn_sekarang . "/" . ($thn_sekarang + 1);
                                                            } ?></option>
                                    <option value="2017">2017/2018</option>
                                    <option value="2018">2018/2019</option>
                                    <option value="2019">2019/2020</option>
                                    <option value="2020">2020/2021</option>
                                    <option value="2021">2021/2022</option>
                                    <option value="2021">2021/2022</option>
                                    <option value="2022">2022/2023</option>
                                    <option value="2023">2023/2024</option>
                                    <option value="2024">2024/2025</option>
                                    <option value="2025">2025/2026</option>
                                </select>
                            </div>




                            <script>
                                function getSelectValue() {
                                    var selectedValue = document.getElementById("tahun_setting").value;
                                    // alert(selectedValue);
                                    // "$_SESSION['thn_selected']" = document.getElementById("tahun_setting").value;

                                }
                                getSelectValue();
                            </script>

                            <!-- <script>
                                        var p1 = "success";
                                    </script> -->

                            <?php
                            // $_SESSION['thn_selected'] = "<script>document.writeln(selectedValue);</script>";
                            ?>


                            <!-- 
                                <select name="fruits" id="fruits" onclick="javascript:GetComboBoxValue();">
                                    <option value="Apple">Apple</option>
                                    <option value="Apricot">Apricot</option>
                                    <option value="Asparagus">Asparagus</option>
                                    <option value="Fig">Fig</option>
                                    <option value="Avocado">Avocado</option>
                                    <option value="Banana">Banana</option>
                                    <option value="Clementine">Clementine</option>
                                    <option value="Honeydew melon">Honeydew melon</option>
                                </select>

                                <button onclick="javascript:GetComboBoxValue();">Validate</button>

                                <script>
                                    function GetComboBoxValue() {
                                        var CmbObject = document.getElementById("fruits")
                                        var fruitName = CmbObject.options[CmbObject.selectedIndex].value;
                                        alert('Your selcted fruit is - ' + fruitName);
                                    }
                                </script> -->



                            <!-- REFRESH COMBO BOX CHAIN -->
                            <!-- <script>
                                    function tampilkan() {
                                        var nama_kota = document.getElementById("form1").kategori.value;
                                        if (nama_kota == "makanan") {
                                            document.getElementById("tampil").innerHTML = "<option value='Nasi Goreng'>Nasi Goreng</option><option value='Bakso'>Bakso</option>";
                                        } else if (nama_kota == "minuman") {
                                            document.getElementById("tampil").innerHTML = "<option value='Teh'>Teh</option><option value='Copy'>Copy</option>";
                                        }
                                    }
                                </script>

                                <h2>Suckittrees.com</h2>
                                <form id="form1" name="form1" onsubmit="return false">
                                    <label>Pilih Kategori: </label>
                                    <select id="kategori" name="kategori" onchange="tampilkan()">
                                        <option value="makanan">makanan</option>
                                        <option value="minuman">minuman</option>
                                    </select>
                                    <br /><br />
                                    <label>Pilih Sub Kategori: </label>
                                    <select id="tampil" name="tampil">
                                    </select>
                                </form> -->
                            <!-- REFRESH COMBO BOX CHAIN -->




                            <div class="col-sm-2">
                                Semester
                                <select name="semester_selected" id="semester_selected" class="form-control select2" style="width: 100%; height: 40px;">
                                    <option value="<?php if ($semester_sekarang) {
                                                        echo $semester_sekarang;
                                                    } ?>"><?php if ($semester_sekarang) {
                                                                echo $semester_sekarang;
                                                            } ?></option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </div>
                            <div class="col-sm-2"><br /> <button type="submit" class="btn btn-danger"><i class="fa fa-floppy-o"></i> <?php echo $button ?></button></div>
                        </div>

                        <br />

                        <div class="box-body">
                            <div class="row">

                                <div class="col-lg-3 col-xs-6">
                                    <a href="<?php echo $link_penjualan; ?>">
                                        <div class="small-box bg-aqua">
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
                                <div class="col-lg-3 col-xs-6">
                                    <a href="trans_cetak/create">
                                        <div class="small-box bg-yellow">
                                            <div class="inner">
                                                <h3 style="font-size:25px;font-weight: bold;color:#FFFFFF">PEMESANAN/PO</h3>
                                                <p style="color:#171313">Transaksi Cetak Baru</p>
                                            </div>
                                            <div class="icon">
                                                <i class="ion ion-person-add"></i>
                                            </div>
                                            <a href="trans_cetak/create" class="small-box-footer">Proses Cetak <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-lg-3 col-xs-6">
                                    <a href="#">
                                        <div class="small-box bg-red">
                                            <div class="inner">
                                                <h3 style="font-size:25px;font-weight: bold;color:#FFFFFF">PEMESANAN</h3>
                                                <p style="color:#171313">Ringkasan data belum terkirim</p>
                                            </div>
                                            <div class="icon">
                                                <i class="ion ion-pie-graph"></i>
                                            </div>
                                            <a href="#" class="small-box-footer">Detail Pesanan <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="box-body">
                            <div class="row">

                                <div class="col-lg-3 col-xs-6">
                                    <!-- <a href="Trans_penjualan/create_setting">
                                    <div class="small-box bg-aqua">
                                        <a href="Trans_penjualan/create_setting">
                                            <div class=" inner">
                                                <h3 style="color:#FFFFFF"><strong> PENJUALAN</strong></h3>
                                                <p style="color:#FFFFFF"> Transaksi baru</p>
                                            </div>
                                            <div class="icon">
                                                <i class="ion ion-bag"></i>
                                            </div>
                                            <a href="Trans_penjualan/create_setting" class="small-box-footer">Masuk Ke Form Penjualan <i class="fa fa-arrow-circle-right"></i></a>
                                        </a>
                                    </div>
                                </a> -->
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



            </div>
        </div>


    </section>
</div>