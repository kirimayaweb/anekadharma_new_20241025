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
            <div class="row">
                <!-- Riwayat transaksi penjualan  -->
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <div class="row">
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12" text-align="center" style="color:red;"> <strong>Form Bayar per Transaksi Penjualan </strong></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <br /> -->


                        <!-- Riwayat transaksi pembayaran  -->
                        <div class="card-body">

                            <form action="<?php echo $action_pertransaksi; ?>" method="post">

                                <?php if (!empty($Data_konsumen_proses_bayar)) { ?>

                                    <div class="row">
                                        <div class="col-12">


                                            <strong>PENJUALAN:</strong>
                                            <table id="tglSPOPFreeze2" class="display nowrap" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align:center" width="10px">No</th>
                                                        <th style="text-align:center" width="100px">Action</th>
                                                        <th style="text-align:center">total_nominal</th>
                                                        <th style="text-align:center">tgl_jual</th>
                                                        <th style="text-align:center">nmrpesan</th>
                                                        <th style="text-align:center">nmrkirim</th>
                                                        <th style="text-align:center">kode_barang</th>
                                                        <th style="text-align:center">nama_barang</th>
                                                        <th style="text-align:center">jumlah</th>
                                                        <th style="text-align:center">satuan</th>
                                                        <th style="text-align:center">harga_satuan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $start = 0;
                                                    $TOTAL_NOMINAL_TRANSAKSI_PILIH = 0;
                                                    foreach ($Data_konsumen_proses_bayar as $list_data) {

                                                    ?>

                                                        <tr>
                                                            <td><?php echo ++$start ?></td>

                                                            <td align="left">
                                                                <?php
                                                                // echo $list_data->nama_konsumen;
                                                                // echo "&nbsp &nbsp";
                                                                echo anchor(site_url('tbl_pembelian/batal_proses_bayar_pertransaksi/' . $list_data->uuid_konsumen . '/' . $list_data->uuid_penjualan_proses), '<i class="fa fa-pencil-square-o" aria-hidden="true">BATAL</i>', 'class="btn btn-warning btn-xs"');
                                                                ?>
                                                            </td>

                                                            <td align="right">
                                                                <?php
                                                                echo nominal($list_data->total_nominal);
                                                                $TOTAL_NOMINAL_TRANSAKSI_PILIH = $TOTAL_NOMINAL_TRANSAKSI_PILIH + $list_data->total_nominal;
                                                                ?>
                                                            </td>

                                                            <td align="left">
                                                                <?php
                                                                // echo $list_data->tgl_jual; 
                                                                echo date("d M Y", strtotime($list_data->tgl_jual));
                                                                ?>
                                                            </td>

                                                            <td align="left"><?php echo $list_data->nmrpesan; ?></td>
                                                            <td align="left"><?php echo $list_data->nmrkirim; ?></td>
                                                            <td align="left"><?php echo $list_data->kode_barang; ?></td>
                                                            <td align="left"><?php echo $list_data->nama_barang; ?></td>
                                                            <td align="right"><?php echo nominal($list_data->jumlah); ?></td>
                                                            <td align="left"><?php echo $list_data->satuan; ?></td>
                                                            <td align="right"><?php echo nominal($list_data->harga_satuan); ?></td>


                                                        </tr>
                                                    <?php
                                                    }
                                                    ?>
                                                </tbody>

                                                <tfoot>
                                                    <tr>
                                                        <th style="text-align:center" width="10px"></th>
                                                        <th style="text-align:right" width="100px"> TOTAL</th>
                                                        <th style="text-align:right"><?php echo nominal($TOTAL_NOMINAL_TRANSAKSI_PILIH); ?></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                </tfoot>

                                            </table>





                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <strong>ACCOUNTING: <?php echo number_format($GET_Data_konsumen_tagihan_accounting, 2, ',', '.');  ?></strong>
                                        </div>
                                    </div>


                                   <hr/>

                                        <div class="row" align="left">
                                            <div class="col-12">
                                                <!-- <div class="form-group"> -->
                                                    <label for="int" style="color:green">TOTAL TAGIHAN: <?php echo '<span style="color:red;"><strong> ' . number_format($GET_Data_konsumen_tagihan_accounting + $TOTAL_NOMINAL_TRANSAKSI_PILIH, 2, ',', '.') . '</strong></span>'; ?></label>
                                                    <!-- <div class="col-10" style="text-align:right;color:red">
                                                        
                                                    </div> -->

                                                <!-- </div> -->
                                            </div>
                                        </div>


                                        <div class="row">

                                            <div class="col-4">
                                                <div class="form-group">

                                                    <div class="col-12">
                                                        <label for="datetime">Tanggal Bayar: </label>
                                                        <div class="input-group date" id="tanggal_bayar_input" name="tanggal_bayar_input" data-target-input="nearest">

                                                            <input type="text" class="form-control datetimepicker-input" data-target="#tanggal_bayar_input" id="tanggal_bayar_input" name="tanggal_bayar_input" required />
                                                            <div class="input-group-append" data-target="#tanggal_bayar_input" data-toggle="datetimepicker">
                                                                <div class="input-group-text">
                                                                    <i class="fa fa-calendar"></i>
                                                                </div>

                                                            </div>

                                                        </div>
                                                    </div>


                                                </div>

                                            </div>


                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="int">Nomor Pembayaran: </label>
                                                    <div class="col-12">
                                                        <div class="input-group date" id="nomor_bayar_input_per_transaksi" name="nomor_bayar_input_per_transaksi" data-target-input="nearest">
                                                            <input type="text" class="form-control" name="nomor_bayar_input_per_transaksi" id="nomor_bayar_input_per_transaksi" placeholder="Nomor Pembayaran" value="" />
                                                        </div>
                                                    </div>



                                                </div>
                                            </div>


                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="int">KODE BANK: </label>
                                                    <div class="col-10" style="text-align:right">
                                                        <select name="uuid_kode_akun" id="uuid_kode_akun" class="form-control select2" style="width: 100%; height: 40px;" required>
                                                            <!-- <option value="<?php //echo $uuid_kode_akun; 
                                                                                ?>"><?php //echo $kode_akun . " - " . $nama_akun; 
                                                                                    ?></option> -->
                                                            <option value="">Pilih Kode Akun</option>
                                                            <?php

                                                            $sql = "select * from sys_kode_akun order by nama_akun ASC ";


                                                            foreach ($this->db->query($sql)->result() as $m) {
                                                                // foreach ($data_produk as $m) {
                                                                echo "<option value='$m->uuid_kode_akun' ";
                                                                echo ">  " . strtoupper($m->kode_akun) . " - " . strtoupper($m->nama_akun) . "</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>



                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div class="col-12" style="text-align:right">

                                                        <?php if ($start > 0) { //Jika ada data yang sedang di proses
                                                        ?>
                                                            <button type="submit" class="btn btn-success"><?php echo $button ?></button>
                                                        <?php
                                                        } ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                  

                                    <div class="row">

                                    </div>


                                <?php } ?>
                            </form>

                            <div class="col-12" text-align="center" style="color:green;font-size: 1.000em;"> Pembayaran Per transaksi barang <br />( klik tombol kuning: pilih bayar per barang pada tabel riwayat penjualan dibawah ) </div>
                        </div>


                        <!-- /.card-body -->
                    </div>
                </div>




                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <div class="row">
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12" text-align="center" style="color:red;"> <strong>Form Pembayaran Total Nominal </strong></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <br /> -->



                        <div class="card-body">

                            <form action="<?php echo $action_nominal; ?>" method="post">

                                <div class="col-12" text-align="center" style="color:green;font-size: 1.000em;"> Isikan Nominal Pembayaran dari konsumen, kemudian klik simpan </div>

                                <div class="form-group">

                                    <div class="row">

                                        <div class="col-3">
                                            <label for="datetime">Tanggal Bayar </label>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">

                                                <div class="col-12">

                                                    <div class="input-group date" id="tanggal_bayar_input" name="tanggal_bayar_input" data-target-input="nearest">

                                                        : &nbsp;&nbsp;&nbsp; <input type="text" class="form-control datetimepicker-input" data-target="#tanggal_bayar_input" id="tanggal_bayar_input" name="tanggal_bayar_input" required />
                                                        <div class="input-group-append" data-target="#tanggal_bayar_input" data-toggle="datetimepicker">
                                                            <div class="input-group-text">
                                                                <i class="fa fa-calendar"></i>
                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>


                                            </div>

                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-3">

                                            <label for="int">Nomor Pembayaran </label>

                                        </div>

                                        <div class="col-6">
                                            <div class="form-group">
                                                <!-- <div class="col-12">
                                                    : &nbsp;&nbsp;&nbsp; <input type="text" class="form-control" name="nomor_bayar_input" id="nomor_bayar_input" placeholder="Nomor Pembayaran" value="" />
                                                </div> -->


                                                <div class="col-12">
                                                    <div class="input-group date" id="nomor_bayar_input" name="nomor_bayar_input" data-target-input="nearest">
                                                        : &nbsp;&nbsp;&nbsp; <input type="text" class="form-control" name="nomor_bayar_input" id="nomor_bayar_input" placeholder="Nomor Pembayaran" value="" />
                                                    </div>
                                                </div>



                                            </div>
                                        </div>

                                    </div>



                                    <div class="row">

                                        <div class="col-3">
                                            <label for="datetime">KODE BANK </label>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">

                                                <div class="col-12">

                                                    <div class="input-group date" id="tanggal_bayar_input" name="tanggal_bayar_input" data-target-input="nearest">

                                                        : &nbsp;&nbsp;&nbsp; <select name="uuid_kode_akun_nominal" id="uuid_kode_akun_nominal" class="form-control select2" style="width: 90%; height: 40px;" required>
                                                            <!-- <option value="<?php //echo $uuid_kode_akun; 
                                                                                ?>"><?php //echo $kode_akun . " - " . $nama_akun; 
                                                                                    ?></option> -->
                                                            <option value="">Pilih Kode Akun</option>
                                                            <?php

                                                            $sql = "select * from sys_kode_akun order by nama_akun ASC ";


                                                            foreach ($this->db->query($sql)->result() as $m) {
                                                                // foreach ($data_produk as $m) {
                                                                echo "<option value='$m->uuid_kode_akun' ";
                                                                echo ">  " . strtoupper($m->kode_akun) . " - " . strtoupper($m->nama_akun) . "</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>


                                            </div>

                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-3">
                                            <label for="datetime">Nominal Bayar <?php echo form_error('nominal_bayar_input') ?></label>
                                        </div>
                                        <div class="col-6">
                                            <!-- <input type="text" class="form-control" name="nominal_bayar_input" id="nominal_bayar_input" placeholder="Nominal" value="" style="text-align:right;" /> -->

                                            <!-- <input type="number" min="0" oninput="this.value = Math.abs(this.value)" max="99999999" maxlength="1" pattern="^[0-9]$" class="form-control" name="nominal_bayar_input" id="nominal_bayar_input" placeholder="" value="" style="font-size:25px;font-weight: bold;text-align:right;"; /> -->




                                            <div class="form-group">
                                                <!-- <div class="col-12">
                                                    : &nbsp;&nbsp;&nbsp; <input type="text" class="form-control" name="nomor_bayar_input" id="nomor_bayar_input" placeholder="Nomor Pembayaran" value="" />
                                                </div> -->


                                                <div class="col-12">
                                                    <div class="input-group date" id="nominal_bayar_input" name="nominal_bayar_input" data-target-input="nearest">
                                                        : &nbsp;&nbsp;&nbsp;
                                                        <input type="text" class="form-control uang" onkeyup="sum();" name="nominal_bayar_input" id="nominal_bayar_input" placeholder="" value="" style="font-size:1.5vw;font-weight: bold;text-align:right;color:red;" ; />

                                                    </div>
                                                </div>



                                            </div>


                                        </div>

                                        <div class="col-2">
                                            <button type="submit" class="btn btn-success"><?php echo $button ?></button>
                                        </div>
                                    </div>

                                </div>

                            </form>




                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>


            </div>
        </div>




        <div class="box box-warning box-solid">
            <div class="row">
                <!-- Riwayat transaksi penjualan  -->
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <div class="row">
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12" text-align="center"> <strong>DATA TAGIHAN DAN FORM PEMBAYARAN: <?php echo '<span style="color:red;"> ' .  $nama_konsumen . ", " . $alamat_konsumen . '</span>' ?> </strong></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <br /> -->


                        <!-- Riwayat transaksi pembayaran  -->
                        <div class="card-body">

                            <table id="tglSPOPFreeze" class="display nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="text-align:center" width="10px">No</th>
                                        <th style="text-align:center" width="100px">Action</th>
                                        <th style="text-align:center">total_nominal</th>
                                        <th style="text-align:center">tgl_jual</th>
                                        <th style="text-align:center">nmrpesan</th>
                                        <th style="text-align:center">nmrkirim</th>

                                        <th style="text-align:center">kode_barang</th>
                                        <th style="text-align:center">nama_barang</th>
                                        <th style="text-align:center">jumlah</th>
                                        <th style="text-align:center">satuan</th>
                                        <th style="text-align:center">harga_satuan</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $start = 0;
                                    $TOTAL_NOMINAL_TAGIHAN_ALL = 0;
                                    foreach ($Data_konsumen_tagihan as $list_data) {

                                    ?>

                                        <tr>
                                            <td><?php echo ++$start ?></td>

                                            <td align="left">
                                                <?php

                                                echo anchor(site_url('tbl_pembelian/pilih_proses_bayar_pertransaksi/' . $list_data->uuid_konsumen . '/' . $list_data->uuid_penjualan_proses), '<i class="fa fa-pencil-square-o" aria-hidden="true">Pilih Bayar</i>', 'class="btn btn-warning btn-xs"');
                                                ?>
                                            </td>
                                            <td style="text-align:right">
                                                <?php
                                                echo nominal($list_data->total_nominal);
                                                $TOTAL_NOMINAL_TAGIHAN_ALL = $TOTAL_NOMINAL_TAGIHAN_ALL + $list_data->total_nominal;
                                                ?>
                                            </td>

                                            <td align="left">
                                                <?php

                                                echo date("d M Y", strtotime($list_data->tgl_jual));
                                                ?>
                                            </td>
                                            <td align="left"><?php echo $list_data->nmrpesan; ?></td>
                                            <td align="left"><?php echo $list_data->nmrkirim; ?></td>

                                            <td align="left"><?php echo $list_data->kode_barang; ?></td>
                                            <td align="left"><?php echo $list_data->nama_barang; ?></td>
                                            <td style="text-align:right"><?php echo nominal($list_data->jumlah); ?></td>
                                            <td align="left"><?php echo $list_data->satuan; ?></td>
                                            <td style="text-align:right"><?php echo nominal($list_data->harga_satuan); ?></td>


                                        </tr>
                                    <?php
                                    }
                                    ?>


                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="text-align:center" width="10px"></th>
                                        <th style="text-align:right" width="100px">TOTAL TAGIHAN</th>
                                        <th style="text-align:right"><?php echo nominal($TOTAL_NOMINAL_TAGIHAN_ALL); ?></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>

                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>


                                    </tr>
                                </tfoot>

                            </table>


                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <div class="row">
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12" text-align="center"> <strong>DATA RIWAYAT PEMBAYARAN: <?php echo  '<span style="color:red;"> ' . $nama_konsumen . ", " . $alamat_konsumen . '<span>' ?></strong></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <br /> -->



                        <div class="card-body">

                            <table id="tglSPOPFreeze1" class="display nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="text-align:center" width="10px">No</th>

                                        <th>tgl_bayar</th>
                                        <th>Nominal</th>
                                        <th>Nomor Bukti pembayaran</th>
                                        <!-- <th>konsumen_nama</th> -->
                                        <th>kode_barang</th>
                                        <th>nama_barang</th>
                                        <th>jumlah</th>
                                        <th>satuan</th>
                                        <th>harga_satuan</th>
                                        <th>total_nominal</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $start = 0;
                                    $total_nominal_ALL = 0;
                                    foreach ($Data_konsumen_pembayaran as $list_data) {

                                    ?>

                                        <tr>
                                            <td><?php echo ++$start ?></td>



                                            <td align="left">
                                                <?php
                                                // echo $list_data->tgl_jual; 
                                                echo date("d M Y", strtotime($list_data->tgl_bayar));
                                                ?>
                                            </td>
                                            <td align="right">
                                                <?php
                                                echo nominal($list_data->nominal_bayar);
                                                $total_nominal_ALL = $total_nominal_ALL + $list_data->nominal_bayar;
                                                ?>
                                            </td>
                                            <td align="left"><?php echo $list_data->nmr_bukti_bayar; ?></td>
                                            <td align="left"><?php echo $list_data->kode_barang; ?></td>
                                            <td align="left"><?php echo $list_data->nama_barang; ?></td>
                                            <td align="right"><?php echo nominal($list_data->jumlah); ?></td>
                                            <td align="right"><?php echo $list_data->satuan; ?></td>
                                            <td align="right"><?php echo nominal($list_data->harga_satuan); ?></td>
                                            <td align="right"><?php echo nominal($list_data->total_nominal); ?></td>

                                        </tr>
                                    <?php
                                    }
                                    ?>


                                </tbody>

                                <tfoot>
                                    <th style="text-align:center" width="10px"></th>
                                    <th style="text-align:right">TOTAL NOMINAL</th>
                                    <th style="text-align:right"><?php echo nominal($total_nominal_ALL); ?></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tfoot>

                            </table>


                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>


            </div>
        </div>



    </section>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
<style type="text/css">
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
</style>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#example').DataTable({
            "scrollY": 200,
            "scrollX": true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#example9').DataTable({
            "scrollY": 900,
            "scrollX": true
        });
    });
</script>