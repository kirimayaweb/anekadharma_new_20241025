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


        <!-- <div class="col-md-1"></div> -->
        <div class="col-md-12">
            <div class="box box-warning box-solid">


                <div class="card card-primary">
                    <div class="card-header">
                        <div class="row">
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="row">
                                    <div class="col-12" text-align="center"> <strong>TAMBAH PRODUK : <?php //echo $nama_unit; 
                                                                                                        ?> </strong></div>
                                </div>


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
                                        <label for="keterangan">Nama Produk <?php echo form_error('nama_barang') ?></label>
                                        <input type="hidden" name="id_persediaan_barang" id="id_persediaan_barang" value="<?php echo $id_persediaan_barang; ?>" />
                                        <input type="hidden" name="uuid_barang" id="uuid_barang" value="<?php echo $uuid_barang; ?>" />
                                        <input class="form-control" rows="3" name="nama_barang" id="nama_barang" placeholder="nama_barang" value="<?php echo $nama_barang; ?>" required>
                                    </div>

                                    <div class="col-4">
                                        <label for="keterangan">Satuan <?php echo form_error('satuan') ?></label>
                                        <input class="form-control" rows="3" name="satuan" id="satuan" placeholder="satuan" value="<?php echo $satuan; ?>" required>

                                    </div>
                                    <div class="col-4">
                                        <label for="keterangan">Harga Satuan <?php echo form_error('harga_satuan') ?></label>
                                        <input class="form-control uang" rows="3" name="harga_satuan" id="harga_satuan" placeholder="harga_satuan" value="<?php echo $harga_satuan; ?>" required>

                                    </div>

                                </div>
                            </div>




                            <div class="form-group">
                                <div class="row">

                                    <div class="col-4">
                                        <label for="keterangan">Tanggal <?php echo form_error('tgl_transaksi') ?></label>


                                        <?php
                                        if ($tgl_transaksi) {
                                            if (date("Y", strtotime($tgl_transaksi)) < 2020) {
                                                $tgl_transaksi_X = date("d-m-Y H:i:s");
                                            } else {
                                                $tgl_transaksi_X = date("d-m-Y H:i:s", strtotime($tgl_transaksi));
                                            }
                                            // $tgl_transaksi_X = date("d-m-Y", strtotime($tgl_transaksi));
                                        } else {
                                            $tgl_transaksi_X = date("d-m-Y");
                                        }
                                        // echo $tgl_transaksi;
                                        ?>



                                        <div class="input-group date" id="tgl_transaksi" name="tgl_transaksi" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input" data-target="#tgl_transaksi" id="tgl_transaksi" name="tgl_transaksi" value="<?php echo $tgl_transaksi_X; ?>" required />
                                            <div class="input-group-append" data-target="#tgl_transaksi" data-toggle="datetimepicker">
                                                <div class="input-group-text">
                                                    <i class="fa fa-calendar"></i>
                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <label for="konsumen_nama">Unit <?php echo form_error('konsumen_nama') ?></label>
                                        <select name="uuid_unit" id="uuid_unit" class="form-control select2" style="width: 100%; height: 40px;" required>
                                            <?php
                                            if ($uuid_unit) {
                                            ?>
                                                <option value="<?php echo $uuid_unit; ?>"><?php echo $nama_unit; ?> </option>
                                            <?php
                                            } else {
                                            ?>
                                                <option value="">Pilih Unit </option>
                                            <?php
                                            }
                                            ?>

                                            <?php

                                            // $sql = "select * from sys_konsumen order by nama_konsumen ASC ";
                                            $sql = "select * from sys_unit order by nama_unit ASC ";
                                            foreach ($this->db->query($sql)->result() as $m) {
                                                echo "<option value='$m->uuid_unit' ";
                                                echo ">  " . strtoupper($m->nama_unit) .  "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="col-4">
                                        <label for="keterangan">Jumlah Produksi <?php echo form_error('jumlah_produksi') ?></label>
                                        <input class="form-control uang" rows="3" name="jumlah_produksi" id="jumlah_produksi" placeholder="jumlah_produksi" value="<?php echo $jumlah_produksi; ?>" required>
                                    </div>


                                </div>
                            </div>



                            <!-- MODAL EXTRA LARGE -->

                            <div class="modal fade" id="modal-xl-select-unit">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Pilih Bahan Produksi</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <!-- <div class="card-body"> -->
                                               
                                                <?php
                                                $sql_stock = "SELECT persediaan.id as id_persediaan_barang, 
                                                persediaan.kode_barang as kode_barang, 
                                                persediaan.namabarang as nama_barang_beli,
                                                persediaan.tanggal_beli as tanggal_beli,
                                                persediaan.total_10 as jumlah_sediaan,  
                                                persediaan.hpp as harga_satuan_persediaan,
                                                tbl_pembelian.uuid_pembelian as uuid_pembelian,
                                                tbl_pembelian.uraian as barang_beli, 
                                                tbl_pembelian.jumlah as jumlah_belanja, 
                                                tbl_pembelian.harga_satuan as harga_satuan_beli,  
                                                tbl_pembelian.tgl_po as tgl_po,
                                                tbl_pembelian.uuid_gudang as uuid_gudang, 
                                                tbl_pembelian.nama_gudang as nama_gudang,  
                                                tbl_pembelian.satuan as satuan,
                                                tbl_penjualan.nama_barang as barang_jual, 
                                                tbl_penjualan.jumlah as jumlah_terjual
                                                FROM persediaan  
                                                left join tbl_pembelian ON persediaan.uuid_barang = tbl_pembelian.uuid_barang 
                                                left join tbl_penjualan ON persediaan.uuid_barang = tbl_penjualan.uuid_barang  
                                                WHERE (persediaan.uuid_barang, persediaan.tanggal) IN (SELECT persediaan.uuid_barang, Max(persediaan.tanggal) FROM persediaan 
                                                GROUP BY persediaan.uuid_barang)  
                                                ORDER BY persediaan.uuid_barang ASC";

                                                $Data_stock = $this->db->query($sql_stock)->result();

                                                ?>




                                                <table id="example9" class="display nowrap" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th style="text-align:center" width="10px">No</th>
                                                            <th>Action</th>
                                                            <th>Tanggal Masuk</th>

                                                            <th>nama barang <br />beli</th>
                                                            <th>harga satuan <br />beli</th>
                                                            <th>satuan</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $compare_spop = 0;
                                                        $Total_per_SPOP = 0;
                                                        $TOTAL_LUNAS = 0;
                                                        $TOTAL_HUTANG = 0;
                                                        $start = 0;
                                                        $TOTAL_PERSEDIAAN = 0;
                                                        foreach ($Data_stock as $list_data) {


                                                        ?>
                                                            <tr>
                                                                <td style="text-align:center"><?php echo ++$start ?></td>
                                                            
                                                                <td style="text-align:left">
                                                                    <?php
                                                                    echo anchor(site_url('Sys_unit_produk/create_produksi/' . $list_data->id_persediaan_barang), '<i class="fa fa-pencil-square-o" aria-hidden="true">Pilih Barang</i>', 'class="btn btn-warning btn-xs"  ');


                                                                    ?>
                                                                </td>

                                                                <td style="text-align:left">
                                                                    <?php 
                                                                    // echo $list_data->tanggal_beli; 
                                                                    echo date("d-m-Y", strtotime($list_data->tanggal_beli));
                                                                    ?>
                                                                    </td>

                                                                <td style="text-align:left">
                                                                    <?php

                                                                    echo $list_data->nama_barang_beli;

                                                                    ?>
                                                                </td>

                                                                <td style="text-align:right">
                                                                    <?php

                                                                    if (!empty($list_data->harga_satuan_persediaan)) {
                                                                        // echo nominal($list_data->harga_satuan_persediaan);
                                                                        echo number_format($list_data->harga_satuan_persediaan, 2, ',', '.');
                                                                        $X_harga_satuan = $list_data->harga_satuan_persediaan;
                                                                    } else {
                                                                        echo "0";
                                                                        $X_harga_satuan = 0;
                                                                    }

                                                                    ?>
                                                                </td>

                                                                <td style="text-align:center"><?php echo $list_data->satuan; ?></td>

                                                            </tr>

                                                        <?php

                                                        }
                                                        ?>


                                                    </tbody>



                                                </table>
                                            <!-- </div> -->

                                            <!-- End of Tabel data -->




                                        </div>

                                        <div class="modal-footer">

                                            <!-- <button type="submit" class="btn btn-primary">Proses</button> -->

                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>


                            <!-- END OF MODAL EXTRA LARGE -->







                            <div class="form-group">
                                <div class="row" align="center">
                                    <div class="col-4"></div>
                                    <div class="col-4">
                                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                        <!-- <button type="submit" class="btn btn-primary"><?php //echo $button 
                                                                                            ?></button> -->

                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-xl-select-unit">
                                            Input Bahan
                                        </button>

                                        <a href="<?php echo site_url('sys_unit/detail_unit/' . $uuid_unit) ?>" class="btn btn-default">Cancel</a>
                                    </div>
                                    <div class="col-4"></div>
                                </div>
                            </div>

                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>

            </div>
        </div>
        <!-- <div class="col-md-1"></div> -->
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
            "scrollY": 350,
            "scrollX": true
        });
    });
</script>