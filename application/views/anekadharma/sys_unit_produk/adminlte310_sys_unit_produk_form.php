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


        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="box box-warning box-solid">


                <div class="card card-primary">
                    <div class="card-header">
                        <div class="row">
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="row">
                                    <div class="col-12" text-align="center"> <strong>TAMBAH PRODUK : <?php echo $nama_unit; ?> </strong></div>
                                </div>


                            </div>
                            <div class="col-6">

                            </div>



                        </div>




                    </div>
                    <!-- <br /> -->



                    <div class="card-body">

                        <form action="<?php echo $action; ?>" method="post">
                            <!-- <div class="form-group">
                                <label for="kode_unit">Kode Unit <?php //echo form_error('kode_unit') 
                                                                    ?></label>
                                <textarea class="form-control" rows="3" name="kode_unit" id="kode_unit" placeholder="Kode Unit"><?php //echo $kode_unit; 
                                                                                                                                ?></textarea>
                            </div> -->
                            <div class="form-group">
                                <div class="row">

                                    <div class="col-4">
                                        <label for="keterangan">Tanggal <?php echo form_error('tgl_transaksi') ?></label>
                                        <div class="input-group date" id="tgl_transaksi" name="tgl_transaksi" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input" data-target="#tgl_transaksi" id="tgl_transaksi" name="tgl_transaksi" required />
                                            <div class="input-group-append" data-target="#tgl_transaksi" data-toggle="datetimepicker">
                                                <div class="input-group-text">
                                                    <i class="fa fa-calendar"></i>
                                                </div>

                                            </div>

                                        </div>
                                    </div>



                                    <div class="col-6">
                                        <label for="keterangan">Nama Produk <?php echo form_error('nama_barang') ?></label>
                                        <input class="form-control" rows="3" name="nama_barang" id="nama_barang" placeholder="nama_barang">
                                    </div>



                                </div>
                            </div>


                            <div class="form-group">
                                <div class="row">
                                    <div class="col-4">
                                        <label for="keterangan">Jumlah Produksi <?php echo form_error('jumlah_produksi') ?></label>
                                        <input class="form-control uang" rows="3" name="jumlah_produksi" id="jumlah_produksi" placeholder="jumlah_produksi">
                                    </div>
                                    <div class="col-4">
                                        <label for="keterangan">Satuan <?php echo form_error('satuan') ?></label>
                                        <input class="form-control" rows="3" name="satuan" id="satuan" placeholder="satuan">

                                    </div>
                                    <div class="col-4">
                                        <label for="keterangan">Harga Satuan <?php echo form_error('harga_satuan') ?></label>
                                        <input class="form-control uang" rows="3" name="harga_satuan" id="harga_satuan" placeholder="harga_satuan">

                                    </div>
                                </div>
                            </div>





                            <!-- detail Bahan Produksi -->
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

                            <div class="modal fade" id="modal-xl">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Pilih Barang</h4>
                                            <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button> -->
                                        </div>
                                        <div class="modal-body">

                                            <!-- ----------- -->

                                            <?php

                                            $sql_stock = "SELECT persediaan.id as id, persediaan.kode_barang as kode_barang, persediaan.namabarang as nama_barang_beli,persediaan.total_10 as jumlah_sediaan,  persediaan.hpp as harga_satuan_persediaan, persediaan.satuan as satuan_persediaan,
                                                    tbl_pembelian.uuid_pembelian as uuid_pembelian,tbl_pembelian.uraian as barang_beli, tbl_pembelian.jumlah as jumlah_belanja, tbl_pembelian.harga_satuan as harga_satuan_beli,  tbl_pembelian.tgl_po as tgl_po,tbl_pembelian.uuid_gudang as uuid_gudang, tbl_pembelian.nama_gudang as nama_gudang,  tbl_pembelian.satuan as satuan,
                                                    tbl_penjualan.nama_barang as barang_jual, tbl_penjualan.jumlah as jumlah_terjual
                                                    FROM persediaan  
                                                    left join tbl_pembelian ON persediaan.uuid_barang = tbl_pembelian.uuid_barang 
                                                    left join tbl_penjualan ON persediaan.uuid_barang = tbl_penjualan.uuid_barang  
                                                    WHERE (persediaan.uuid_barang, persediaan.tanggal) IN (SELECT persediaan.uuid_barang, Max(persediaan.tanggal) FROM persediaan GROUP BY persediaan.uuid_barang)  
                                                    ORDER BY persediaan.uuid_barang ASC";

                                            // print_r($this->db->query($sql_stock)->result());
                                            $Data_stock = $this->db->query($sql_stock)->result();


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

                                                            // $sql = "SELECT sum(`total_10`) as sisa_stock FROM `persediaan` WHERE `uuid_barang`='$list_data->uuid_barang' AND `tanggal`=(SELECT MAX(`tanggal`) FROM `persediaan` WHERE `uuid_barang`='$list_data->uuid_barang')";

                                                            // $data_barang_per_barang = $this->db->query($sql)->row();

                                                            // $sql = "SELECT sum(`jumlah`) as jumlah_jual FROM `tbl_penjualan` WHERE `uuid_barang`='$list_data->uuid_barang'";

                                                            // $data_barang_terjual = $this->db->query($sql)->row();

                                                        ?>
                                                            <trv>
                                                                <td><?php echo ++$start ?></td>
                                                                <!-- <td>
                                                                    <?php
                                                                    // echo date("d M Y", strtotime($list_data->tgl_po));
                                                                    ?>
                                                                </td> -->


                                                                <!-- <td><?php //echo $list_data->uuid_barang; 
                                                                            ?></td> -->

                                                                <td><?php echo $list_data->nama_barang_beli; ?></td>


                                                                <td><?php echo nominal($list_data->harga_satuan_persediaan); ?></td>
                                                                <td><?php echo $list_data->satuan_persediaan; ?></td>
                                                                <td>
                                                                    <?php
                                                                    // echo $list_data->total_10; 

                                                                    // echo $data_barang_per_barang->sisa_stock . " - " . $data_barang_terjual->jumlah_jual;

                                                                    $sisa_stock_data = $list_data->jumlah_sediaan + $list_data->jumlah_belanja - $list_data->jumlah_terjual ;

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

                                                                    <!-- <form action="<?php //echo $action_bahan . "/" . $list_data->id; ?>" method="post"> -->
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
                                                                                                    <input type="text" class="form-control" rows="3" name="nama_barang" id="nama_barang" placeholder="nama_barang" value="<?php echo $list_data->nama_barang_beli ?>" disabled>
                                                                                                </div>
                                                                                            </div>



                                                                                            <div class="row">
                                                                                                <div class="col-4">
                                                                                                    <label for="nmrpesan">Harga Satuan </label>
                                                                                                    <!-- <input type="text" class="form-control" rows="3" name="harga_satuan_beli" id="harga_satuan_beli" value="<?php echo nominal($list_data->harga_satuan_persediaan); ?>" placeholder="<?php echo nominal($list_data->harga_satuan_persediaan); ?>"> -->
                                                                                                </div>
                                                                                                <div class="col-4">
                                                                                                    <label style="color:red" for="nmrkirim">Jumlah Maks= <?php echo $sisa_stock_data  ?></label>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="row">


                                                                                                <div class="col-4">
                                                                                                    <input type="text" class="form-control" rows="3" name="harga_satuan_beli" id="harga_satuan_beli" value="<?php echo nominal($list_data->harga_satuan_persediaan); ?>" placeholder="<?php echo nominal($list_data->harga_satuan_persediaan); ?>">
                                                                                                </div>
                                                                                                <div class="col-4">

                                                                                                    <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" max="<?php echo $sisa_stock_data ?>">

                                                                                                </div>


                                                                                            </div>


                                                                                        </div>


                                                                                    </div>

                                                                                    <div class="modal-footer justify-content-between">

                                                                                        <!-- <input type="hidden" name="tgl_jual" id="tgl_jual" value="<?php //echo $tgl_jual_X; 
                                                                                                                                                        ?>" /> -->
                                                                                        <!-- <input type="hidden" name="uuid_konsumen" id="uuid_konsumen" value="<?php //echo $uuid_konsumen; 
                                                                                                                                                                    ?>" /> -->
                                                                                        <!-- <input type="hidden" name="nmrpesan" id="nmrpesan" value="<?php //echo $nmrpesan; 
                                                                                                                                                        ?>" /> -->
                                                                                        <!-- <input type="hidden" name="nmrkirim" id="nmrkirim" value="<?php //echo $nmrkirim; 
                                                                                                                                                        ?>" /> -->
                                                                                        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->

                                                                                        <button type="submit" class="btn btn-primary">SIMPAN</button>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- /.modal-content -->
                                                                            </div>

                                                                        </div>
                                                                    <!-- </form> -->

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



                            <input type="hidden" name="id" value="<?php echo $id; ?>" />
                            <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                            <a href="<?php echo site_url('sys_unit/detail_unit/' . $uuid_unit) ?>" class="btn btn-default">Cancel</a>
                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>

            </div>
        </div>
        <div class="col-md-1"></div>
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
            "scrollY": 400,
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