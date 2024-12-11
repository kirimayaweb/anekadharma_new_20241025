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

            <div class="col-md-12">
                <div class="card card-warning">
                    <div class="card-header">
                        <div class="row">
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="row">
                                    <div class="col-12" text-align="center"> <strong>
                                            Input Penjualan
                                        </strong></div>

                                </div>


                            </div>
                            <div class="col-6">

                            </div>



                        </div>



                    </div>
                    <br />



                    <div class="card-body">


                        <!-- <form action="<?php //echo $action; 
                                            ?>" method="post"> -->





                        <div class="form-group">
                            <label for="datetime">Tgl Jual <?php echo form_error('tgl_jual') ?></label>
                            <div class="col-4">
                                <?php
                                $tgl_jual_X = date("d-m-Y", strtotime($tgl_jual));
                                ?>
                                <!-- <input type="text" class="form-control" name="tgl_jual" id="tgl_jual" placeholder="Tgl Po" value="<?php echo $tgl_jual; ?>" /> -->
                                <div class="input-group date" id="tgl_jual" name="tgl_jual" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#tgl_jual" id="tgl_jual" name="tgl_jual" value="<?php echo $tgl_jual_X; ?>" required />
                                    <div class="input-group-append" data-target="#tgl_jual" data-toggle="datetimepicker">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>

                                    </div>

                                </div>
                            </div>
                            <!-- <div class="col-12">
                                    Jika tanggal tidak di pilih, maka akan di isi = tanggal saat ini secara otomatis oleh sistem

                                </div> -->

                        </div>


                        <div class="form-group">
                            <div class="row">
                                <div class="col-4">
                                    <label for="konsumen_nama">Konsumen <?php echo form_error('konsumen_nama') ?></label>
                                    <select name="uuid_konsumen" id="uuid_konsumen" class="form-control select2" style="width: 100%; height: 40px;" required>
                                        <option value="<?php echo $uuid_konsumen ?>"><?php echo $nama_konsumen ?></option>
                                        <?php

                                        // Data Unit
                                        $sql = "select * from sys_unit order by nama_unit ASC ";
                                        foreach ($this->db->query($sql)->result() as $m) {
                                            echo "<option value='$m->uuid_unit' ";
                                            echo ">  " . strtoupper($m->nama_unit)  . "  ==> [UNIT] </option>";
                                        }
                                        // Data Sys_konsumen
                                        $sql = "select * from sys_konsumen order by nama_konsumen ASC ";
                                        foreach ($this->db->query($sql)->result() as $m) {
                                            echo "<option value='$m->uuid_konsumen' ";
                                            echo ">  " . strtoupper($m->nama_konsumen) . strtoupper($m->nmr_kontak_konsumen) . strtoupper($m->alamat_konsumen) . "</option>";
                                        }
                                        ?>
                                    </select>


                                </div>

                                <div class="col-4">
                                    <label for="nmrpesan">Nomor Pesan <?php echo form_error('nmrpesan') ?></label>
                                    <input type="text" class="form-control" rows="3" name="nmrpesan" id="nmrpesan" value="<?php echo $nmrpesan ?>" placeholder="nmrpesan">
                                </div>

                                <div class="col-4">
                                    <label for="nmrkirim">Nomor Kirim <?php echo form_error('nmrkirim') ?></label>
                                    <input type="text" class="form-control" rows="3" name="nmrkirim" id="nmrkirim" value="<?php echo $nmrkirim ?>" placeholder="nmrkirim">
                                </div>


                            </div>

                        </div>



                        <div class="card card-success">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-12" text-align="center"> <strong>Detail Barang</strong> <button type="button" class="btn btn-warning btn-lg" data-toggle="modal" data-target="#modal-xl">
                                            Input Detail Barang
                                        </button></div>

                                </div>
                            </div>
                            <div class="card-body">

                                <?php

                                if (isset($data_penjualan_per_uuid_penjualan)) {
                                ?>

                                    <table id="example1" class="display nowrap" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th style="text-align:center">No</th>
                                                <th style="text-align:left">Tgl Jual</th>
                                                <th style="text-align:center">Nama Barang</th>
                                                <!-- <th style="text-align:center">Unit</th> -->

                                                <th style="text-align:center">Satuan</th>
                                                <th style="text-align:right">Jumlah</th>
                                                <th style="text-align:right">Harga Satuan</th>
                                                <th style="text-align:right">Total</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php

                                            $start = 0;
                                            foreach ($data_penjualan_per_uuid_penjualan as $list_data) {

                                            ?>
                                                <tr>
                                                    <td><?php echo ++$start ?></td>
                                                    <td>
                                                        <?php
                                                        // echo $list_data->tgl_po; 

                                                        echo date("d M Y", strtotime($list_data->tgl_jual));

                                                        ?>
                                                    </td>
                                                    <!-- <td><?php //echo $list_data->konsumen_nama; 
                                                                ?></td> -->
                                                    <td><?php echo $list_data->nama_barang; ?></td>
                                                    <!-- <td><?php //echo $list_data->unit; 
                                                                ?></td> -->

                                                    <td style="text-align:center"><?php echo $list_data->satuan; ?></td>
                                                    <td style="text-align:right"><?php echo nominal($list_data->jumlah); ?></td>
                                                    <td style="text-align:right"><?php echo nominal($list_data->harga_satuan); ?></td>
                                                    <td style="text-align:right"><?php echo nominal($list_data->jumlah * $list_data->harga_satuan); ?></td>

                                                </tr>



                                            <?php
                                            }
                                            ?>


                                        </tbody>



                                    </table>


                                <?php
                                }
                                ?>



                            </div>
                        </div>

                        <!-- /.modal -->

                        <div class="modal fade" id="modal-xl">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Pilih Barang</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">

                                        <!-- ----------- -->

                                        <?php

                                        // $Data_stock = $this->Tbl_pembelian_model->stock();

                                        // DATA PERSEDIAAN BERDASARKAN BULAN LALU

                                        // $Data_stock = $this->Persediaan_model->get_by_persediaan_month($tgl_jual);

                                        $sql_data = "SELECT id,tanggal_beli,spop,uuid_barang,namabarang,hpp,satuan,sa,total_10 FROM persediaan WHERE spop <>'' AND (namabarang, tanggal_beli) IN (SELECT namabarang, Max(tanggal_beli) FROM persediaan GROUP BY namabarang)";

                                        // $sql = "SELECT * FROM tbl_pembelian WHERE id=$id_proses";
                                        $Data_stock = $this->db->query($sql_data)->result();

                                        // $Data_stock = $this->Persediaan_model->get_by_persediaan_month($tgl_jual);


                                        // print_r($Data_stock);

                                        ?>
                                        <div class="card-body">

                                            <table id="example" class="display nowrap" style="width:100%">
                                                <!-- <table id="example" class="display nowrap" style="width:100%"> -->
                                                <thead>
                                                    <tr>
                                                        <th style="text-align:center">No</th>
                                                        <!-- <th style="text-align:center">Tgl Beli</th> -->
                                                        <!-- <th style="text-align:center">UUID barang</th> -->
                                                        <th style="text-align:center">Nama barang</th>
                                                        <th style="text-align:right">Harga satuan</th>
                                                        <th style="text-align:right">satuan</th>
                                                        <th style="text-align:center">Sisa Stock</th>
                                                        <th style="text-align:left">Pilih</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php

                                                    $start = 0;
                                                    foreach ($Data_stock as $list_data) {

                                                        // CEK TOTAL STOCK TERSISA
                                                        // if (($list_data->jumlah_belanja - $list_data->jumlah_terjual) > 0) {  //HIDE TOTAL SISA STOCK = 0;

                                                        // SISA STOCK ==> ek di tabel penjualan jumlah terjual dan dibandingkan dengan total jumlah pembelian

                                                        // SELECT sum(`total_10`) FROM `persediaan` WHERE `uuid_barang`='190ef5ddb24011ef85130021ccc9061e' AND `tanggal`=(SELECT MAX(`tanggal`) FROM `persediaan` WHERE `uuid_barang`='190ef5ddb24011ef85130021ccc9061e');


                                                        $sql = "SELECT sum(`total_10`) as sisa_stock FROM `persediaan` WHERE `uuid_barang`='$list_data->uuid_barang' AND `tanggal`=(SELECT MAX(`tanggal`) FROM `persediaan` WHERE `uuid_barang`='$list_data->uuid_barang')";

                                                        $data_barang_per_barang = $this->db->query($sql)->row();

                                                        // print_r($data_barang_per_barang->sisa_stock);
                                                        // print_r("<br/>");


                                                        // Total jumlah penjualan per uuid_barang
                                                        // SELECT sum(`jumlah`) as jumlah_jual FROM `tbl_penjualan` WHERE `uuid_barang`='158d764eb49a11ef8d550021ccc9061e';

                                                        $sql = "SELECT sum(`jumlah`) as jumlah_jual FROM `tbl_penjualan` WHERE `uuid_barang`='$list_data->uuid_barang'";

                                                        $data_barang_terjual = $this->db->query($sql)->row();

                                                        // print_r($data_barang_terjual->jumlah_jual);
                                                        // print_r("<br/>");
                                                        // print_r("<br/>");
                                                        // print_r("<br/>");
                                                        // print_r("<br/>");


                                                    ?>
                                                        <tr>
                                                            <td><?php echo ++$start ?></td>
                                                            <!-- <td>
                                                                    <?php
                                                                    // echo date("d M Y", strtotime($list_data->tgl_po));
                                                                    ?>
                                                                </td> -->


                                                            <!-- <td><?php //echo $list_data->uuid_barang; 
                                                                        ?></td> -->

                                                            <td><?php echo $list_data->namabarang; ?></td>


                                                            <td><?php echo nominal($list_data->hpp); ?></td>
                                                            <td><?php echo $list_data->satuan; ?></td>
                                                            <td>
                                                                <?php
                                                                // echo $list_data->total_10; 

                                                                // echo $data_barang_per_barang->sisa_stock . " - " . $data_barang_terjual->jumlah_jual;

                                                                if ($data_barang_terjual->jumlah_jual) {
                                                                    // echo nominal($data_barang_per_barang->sisa_stock - $data_barang_terjual->jumlah_jual);

                                                                    $sisa_stock_data = $data_barang_per_barang->sisa_stock - $data_barang_terjual->jumlah_jual;
                                                                } else {
                                                                    // echo nominal($data_barang_per_barang->sisa_stock);

                                                                    $sisa_stock_data = $data_barang_per_barang->sisa_stock;
                                                                }
                                                                echo nominal($sisa_stock_data);


                                                                ?>
                                                            </td>


                                                            <!-- <td><?php //echo nominal($list_data->jumlah_belanja - $list_data->jumlah_terjual); 
                                                                        ?></td> -->
                                                            <td>
                                                                <?php
                                                                // echo anchor(site_url('tbl_pembelian/update_per_spop/'), '<i class="fa fa-pencil-square-o" aria-hidden="true">PILIH</i>', 'class="btn btn-warning btn-xs"');


                                                                if ($sisa_stock_data > 0) {
                                                                ?>
                                                                    <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-id="<?php echo $list_data->id; ?>" data-target="#modal-xl_1_<?php echo $list_data->id; ?>">
                                                                        PILIH BARANG
                                                                    </button>
                                                                <?php
                                                                } else {
                                                                ?>
                                                                    <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-id="<?php echo $list_data->id; ?>" data-target="#modal-xl_1_<?php echo $list_data->id; ?>">
                                                                        PILIH BARANG
                                                                    </button>
                                                                <?php
                                                                }
                                                                ?>







                                                                <?php
                                                                if (!empty($uuid_penjualan)) {
                                                                    // print_r("ADa uuid penjualan");
                                                                ?>
                                                                    <form action="<?php echo $action . $uuid_penjualan . "/" . $list_data->id; ?>" method="post">
                                                                    <?php
                                                                } else {
                                                                    // print_r("TIDAK ADa uuid penjualan");
                                                                    ?>
                                                                        <form action="<?php echo $action . "new/" . $list_data->id; ?>" method="post">
                                                                        <?php
                                                                    }
                                                                        ?>

                                                                        <div class="modal fade" id="modal-xl_1_<?php echo $list_data->id; ?>">
                                                                            <div class="modal-dialog modal-xl">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h4 class="modal-title">Isi Jumlah Barang & pilih Unit</h4>
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                            <span aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="modal-body">

                                                                                        <div class="form-group">
                                                                                            <div class="row">
                                                                                                <div class="col-12">
                                                                                                    <label for="konsumen_nama">Barang </label>
                                                                                                    <input type="text" class="form-control" rows="3" name="nama_barang" id="nama_barang" placeholder="nama_barang" value="<?php echo $list_data->namabarang ?>" disabled>
                                                                                                </div>
                                                                                            </div>



                                                                                            <div class="row">
                                                                                                <div class="col-4">
                                                                                                    <label for="nmrpesan">Harga Satuan </label>
                                                                                                    <!-- <input type="text" class="form-control" rows="3" name="harga_satuan_beli" id="harga_satuan_beli" value="<?php echo nominal($list_data->hpp); ?>" placeholder="<?php echo nominal($list_data->hpp); ?>"> -->
                                                                                                </div>
                                                                                                <div class="col-4">
                                                                                                    <label style="color:red" for="nmrkirim">Jumlah Maks= <?php echo $list_data->total_10 ?></label>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="row">


                                                                                                <div class="col-4">
                                                                                                    <input type="text" class="form-control" rows="3" name="harga_satuan_beli" id="harga_satuan_beli" value="<?php echo nominal($list_data->hpp); ?>" placeholder="<?php echo nominal($list_data->hpp); ?>">
                                                                                                </div>
                                                                                                <div class="col-4">
                                                                                                    <!-- <input type="text" class="form-control" rows="3" name="jumlah" id="jumlah" min="1" max="5" placeholder="jumlah"> -->
                                                                                                    <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" max="<?php echo $list_data->total_10 ?>">

                                                                                                </div>


                                                                                            </div>


                                                                                            <!-- 
                                                                                            <div class="row">
                                                                                                <div class="col-6">
                                                                                                    <label for="konsumen_nama">Konsumen </label>
                                                                                                    <select name="uuid_unit" id="uuid_unit" class="form-control select2" style="width: 100%; height: 60px;" required>
                                                                                                        <option value="">Pilih Unit</option>
                                                                                                        <?php

                                                                                                        // $sql = "select * from sys_konsumen order by nama_konsumen ASC ";
                                                                                                        // foreach ($this->db->query($sql)->result() as $m) {
                                                                                                        //     echo "<option value='$m->uuid_konsumen' ";
                                                                                                        //     echo ">  " . strtoupper($m->nama_konsumen)  . "  [" . strtoupper($m->kelompok_dipersediaan) . "]</option>";
                                                                                                        // }
                                                                                                        ?>
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div> -->


                                                                                        </div>


                                                                                    </div>
                                                                                    <div class="modal-footer justify-content-between">
                                                                                        <input type="hidden" name="tgl_jual" id="tgl_jual" value="<?php echo $tgl_jual_X; ?>" />
                                                                                        <input type="hidden" name="uuid_konsumen" id="uuid_konsumen" value="<?php echo $uuid_konsumen; ?>" />
                                                                                        <input type="hidden" name="nmrpesan" id="nmrpesan" value="<?php echo $nmrpesan; ?>" />
                                                                                        <input type="hidden" name="nmrkirim" id="nmrkirim" value="<?php echo $nmrkirim; ?>" />
                                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                                                                                        <button type="submit" class="btn btn-primary">SIMPAN</button>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- /.modal-content -->
                                                                            </div>

                                                                        </div>
                                                                        </form>

                                                            </td>

                                                        </tr>
                                                    <?php
                                                        // }
                                                    }
                                                    ?>


                                                </tbody>



                                            </table>
                                        </div>
                                        <!-- ----------- -->



                                    </div>

                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>

                        <!-- /.modal -->




                        <!-- <input type="hidden" name="id" value="<?php //echo $id; 
                                                                    ?>" /> -->



                        <!-- <button type="submit" class="btn btn-primary"><?php //echo $button 
                                                                            ?></button> -->
                        <a href="<?php echo site_url('tbl_penjualan') ?>" class="btn btn-default">Simpan</a>
                        <?php
                        if (isset($uuid_penjualan)) {
                        ?>

                            <a href="<?php echo site_url('tbl_penjualan/cetak_penjualan_per_uuid_penjualan/' . $uuid_penjualan) ?>" class="btn btn-primary" target="_blank">Cetak Penjualan</a>

                        <?php
                        }
                        ?>

                        <!-- <a href="<?php //echo site_url('tbl_penjualan') 
                                        ?>" class="btn btn-default">Cancel</a> -->
                        <!-- </form> -->


                    </div>

                    <!-- /.card-body -->
                </div>

            </div>

        </div>
    </section>
</div>





<!-- ============== -->




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
            "scrollY": 300,
            "scrollX": true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#example99').DataTable({
            "scrollY": 500,
            "scrollX": true
        });
    });
</script>