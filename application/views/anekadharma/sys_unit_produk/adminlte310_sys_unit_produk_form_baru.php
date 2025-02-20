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
                                    <div class="col-12" text-align="center"> <strong> <?php //echo $nama_unit; 
                                                                                        ?> </strong></div>

                                    <?php
                                    if ($id_persediaan_barang) {
                                        $this->db->where('id', $id_persediaan_barang);
                                        $persediaan_nama_barang = $this->db->get('persediaan');

                                        echo " <p style='font-size:1em;color:#c50d0d;font-weight:bold;'> NAMA PRODUK: " . $persediaan_nama_barang->row()->namabarang . "</p>";
                                    } else {
                                        echo "<p style='font-size:1em;color:#c50d0d;font-weight:bold;'> BELUM SETTING NAMA PRODUK </p>";
                                    }
                                    ?>
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





                            <!-- <div class="form-group">
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
                            </div> -->


                            <div class="card card-success">
                                <div class="card-header">

                                    <div class="row">
                                        <div class="col-2" text-align="center"> <strong>Detail Bahan-bahan: </strong>
                                        </div>



                                        <div class="col-3" text-align="left">
                                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-xl-select-unit">
                                                Input Bahan
                                            </button>
                                        </div>


                                    </div>

                                </div>


                                <?php

                                // print_r($data_bahan_produk_unit);

                                ?>

                                <div class="card-body">

                                    <table id="tglSPOPFreeze" class="display nowrap" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th style="text-align:left" width="30px">No</th>
                                                <th style="text-align:left">Action</th>

                                                <th style="text-align:left">Gudang</th>
                                                <th style="text-align:left">Nama Barang</th>
                                                <th style="text-align:center">Jumlah</th>
                                                <th style="text-align:center">Satuan</th>
                                                <!-- <th>Konsumen</th> -->
                                                <th style="text-align:right">Harga Satuan</th>
                                                <th style="text-align:right">Harga Total</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $compare_spop = 0;
                                            $Total_per_SPOP = 0;
                                            $TOTAL_LUNAS = 0;
                                            $TOTAL_HUTANG = 0;
                                            $TOTAL_HARGA = 0;
                                            $start = 0;
                                            $jumlah_barang = 0;
                                            foreach ($data_bahan_produk_unit as $list_data) {

                                            ?>


                                                <tr>

                                                    <td style="text-align:center"><?php echo ++$start ?></td>

                                                    <td align="left">

                                                        <!-- <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#modal-xl-input-barang_<?php //echo $list_data->id 
                                                                                                                                                                            ?>">
                                                            UBAH <?php //echo $list_data->id 
                                                                    ?>
                                                        </button> -->

                                                        <?php
                                                        // echo anchor(site_url('tbl_pembelian/create_add_uraian_update/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH</i>', 'class="btn btn-warning btn-xs"');

                                                        // echo anchor(site_url('tbl_pembelian/delete_by_uuid_pembelian_from_per_spop_update/' . $list_data->uuid_pembelian . '/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o" aria-hidden="true">HAPUS</i>', 'class="btn btn-danger btn-xs"');

                                                        echo anchor(site_url('Sys_unit_produk_bahan/delete/' . $list_data->id . '/Sys_unit_produk/create_produksi/' . $id_persediaan_barang), '<i class="fa fa-trash-o" aria-hidden="true">Hapus Bahan</i>', 'class="btn btn-danger btn-sm" Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');

                                                        ?>



                                                    </td>
                                                    <td align="left"><?php //echo $list_data->nama_gudang; 
                                                                        ?></td>
                                                    <td align="left"><?php echo $list_data->nama_barang_bahan; ?></td>
                                                    <td align="center">
                                                        <?php
                                                        // echo nominal($list_data->jumlah);                                                         
                                                        echo number_format($list_data->jumlah_bahan, 0, ',', '.');
                                                        $jumlah_barang = $jumlah_barang + $list_data->jumlah_bahan;

                                                        ?>
                                                    </td>
                                                    <td align="center"><?php echo $list_data->satuan_bahan; ?></td>

                                                    <td align="right">
                                                        <?php
                                                        // echo nominal($list_data->harga_satuan); 
                                                        echo number_format($list_data->harga_satuan_bahan, 2, ',', '.');
                                                        ?>
                                                    </td>
                                                    <td align="right">
                                                        <?php
                                                        $total_per_uraian = $list_data->jumlah_bahan * $list_data->harga_satuan_bahan;

                                                        // echo nominal($total_per_uraian);

                                                        echo number_format($total_per_uraian, 2, ',', '.');

                                                        $TOTAL_HARGA = $TOTAL_HARGA + $total_per_uraian;


                                                        ?>
                                                    </td>

                                                </tr>





                                            <?php
                                            }
                                            ?>

                                        </tbody>

                                        <tfoot>
                                            <tr>
                                                <th style="text-align:left" width="30px"></th>
                                                <th style="text-align:left"></th>
                                                <th style="text-align:left"></th>
                                                <th style="text-align:right">TOTAL</th>
                                                <th style="text-align:center"><?php
                                                                                // echo $jumlah_barang; 
                                                                                echo number_format($jumlah_barang, 0, ',', '.');
                                                                                ?></th>
                                                <th style="text-align:center">

                                                </th>
                                                <th style="text-align:right"></th>
                                                <th style="text-align:right">
                                                    <?php
                                                    // echo $Total_per_SPOP; 
                                                    echo number_format($TOTAL_HARGA, 2, ',', '.');
                                                    ?>
                                                </th>
                                            </tr>
                                        </tfoot>


                                    </table>
                                </div>


                                <!-- <div class="card-body">

                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-xl-input-barang">
                                    Tambah Barang
                                </button>

                            </div> -->




                                <div class="form-group">
                                    <div class="row" align="center">
                                        <div class="col-4"></div>
                                        <div class="col-4">
                                            <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                            <!-- <button type="submit" class="btn btn-primary"><?php //echo $button 
                                                                                                ?></button> -->

                                            <!-- <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-xl-select-unit">
                                            Input Bahan
                                        </button> -->
                                            <?php
                                            if ($id_persediaan_barang) {
                                            ?>
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-xl-input_nama_produk_baru">
                                                    <?php echo $button_data_produk; ?>
                                                </button>
                                            <?php
                                            }
                                            ?>





                                        </div>
                                        <div class="col-4"></div>
                                    </div>
                                    <div class="row" align="center">
                                        <div class="col-4"></div>
                                        <div class="col-4">
                                            <input type="hidden" name="id" value="<?php echo $id; ?>" />


                                            <a href="<?php echo site_url('Sys_unit_produk') ?>" class="btn btn-success">Kembali ke data produk</a>





                                        </div>
                                        <div class="col-4"></div>
                                    </div>
                                </div>



                            </div>


                        </form>



                        <!-- TAMBAH BARANG MODAL EXTRA LARGE -->
                        <form action="<?php echo $action_simpan_bahan; ?>" method="post">
                            <div class="modal fade" id="modal-xl-select-unit_bu">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Input Bahan Produksi</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="form-group">


                                                <div class="row">
                                                    <div class="col-4">
                                                        <label for="uuid_persediaan">Bahan <?php echo form_error('uuid_persediaan') ?></label>

                                                        <select name="uuid_persediaan" id="uuid_persediaan" class="form-control select2" style="width: 100%; height: 80px;" required>
                                                            <option value="">Pilih Bahan</option>
                                                            <?php

                                                            // $sql = "SELECT `uuid_barang`,`kode_barang`,`nama_barang` FROM `sys_nama_barang` ORDER by `nama_barang` ASC";
                                                            $sql = "SELECT `uuid_persediaan`,`uuid_barang`,`kode_barang`,`namabarang`,`spop` FROM `persediaan` WHERE `namabarang`<>'' GROUP by `namabarang`,`spop`,`satuan`";
                                                            foreach ($this->db->query($sql)->result() as $m) {
                                                                echo "<option value='$m->uuid_persediaan' ";
                                                                echo ">  " . strtoupper($m->namabarang) . " --> SPOP: " . $m->spop . "</option>";
                                                            }
                                                            ?>
                                                        </select>

                                                        <div class="row">
                                                            <div class="col-8">
                                                                <?php //echo anchor(site_url('sys_nama_barang/create/pembelian'), 'Input Barang Baru', 'class="btn btn-block btn-danger"'); 
                                                                ?>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="col-4">
                                                        <label for="jumlah">Jumlah <?php //echo form_error('nmrpesan') 
                                                                                    ?></label>
                                                        <!-- <input type="text" class="form-control" rows="3" name="jumlah" id="jumlah" placeholder="Jumlah" required> -->
                                                        <input type="text" name="jumlah" id="jumlah" placeholder="Jumlah" class="form-control" required>
                                                    </div>

                                                </div>



                                            </div>

                                        </div>

                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                            <!-- <button type="button" class="btn btn-primary">Simpan</button> -->
                                            <button type="submit" class="btn btn-primary">Proses</button>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                        </form>
                        <!-- END OF MODAL EXTRA LARGE -->






                    </div>
                    <!-- /.card-body -->
                </div>

            </div>
        </div>
        <!-- <div class="col-md-1"></div> -->
    </section>

</div>













<!-- MODAL TABEL DATA PERSEDIAAN DAN SUB MODAL UNTUK INPUT JUMLAH STOCK YANG DI GUNAKAN -->
<!-- /.modal -->

<div class="modal fade" id="modal-xl-select-unit">
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

                // $sql_data = "SELECT id,tanggal_beli,spop,uuid_barang,namabarang,hpp,satuan,sa,total_10 FROM persediaan WHERE spop <>'' AND (namabarang, tanggal_beli) IN (SELECT namabarang, Max(tanggal_beli) FROM persediaan GROUP BY namabarang)";

                // // $sql = "SELECT * FROM tbl_pembelian WHERE id=$id_proses";
                // $Data_stock = $this->db->query($sql_data)->result();


                $sql_stock = "SELECT persediaan.id as id, 
                                        persediaan.uuid_persediaan as uuid_persediaan, 
                                        persediaan.tanggal_beli as tanggal_beli, 
                                        persediaan.uuid_spop as uuid_spop, 
                                        persediaan.spop as spop, 
                                        persediaan.uuid_barang as uuid_barang, 
                                        persediaan.kode_barang as kode_barang, 
                                        persediaan.namabarang as nama_barang_beli,
                                        persediaan.total_10 as jumlah_sediaan,  
                                        persediaan.hpp as harga_satuan_persediaan, 
                                        persediaan.satuan as satuan_persediaan,
                                        persediaan.pecah_satuan as pecah_satuan_persediaan,
                                        persediaan.bahan_produksi as bahan_produksi,
                                                -- tbl_pembelian.uuid_pembelian as uuid_pembelian,
                                                -- tbl_pembelian.uraian as barang_beli, 
                                                -- tbl_pembelian.jumlah as jumlah_belanja, 
                                                -- tbl_pembelian.harga_satuan as harga_satuan_beli,  
                                                -- tbl_pembelian.tgl_po as tgl_po,
                                                -- tbl_pembelian.uuid_gudang as uuid_gudang, 
                                                -- tbl_pembelian.nama_gudang as nama_gudang,  
                                                -- tbl_pembelian.satuan as satuan,
                                                -- tbl_penjualan.nama_barang as barang_jual, 
                                                -- tbl_penjualan.jumlah as jumlah_terjual,
                                                persediaan.penjualan as penjualan
                                                FROM persediaan  
                                                -- left join tbl_pembelian ON persediaan.uuid_barang = tbl_pembelian.uuid_barang 
                                                -- left join tbl_penjualan ON persediaan.uuid_barang = tbl_penjualan.uuid_barang  
                                                -- WHERE (persediaan.uuid_barang, persediaan.tanggal) IN (SELECT persediaan.uuid_barang, Max(persediaan.tanggal) FROM persediaan GROUP BY persediaan.uuid_barang)  
                                                ORDER BY persediaan.uuid_barang ASC";

                // print_r($this->db->query($sql_stock)->result());
                $Data_stock = $this->db->query($sql_stock)->result();



                // $Data_stock = $this->Persediaan_model->get_by_persediaan_month($tgl_jual);


                // print_r($Data_stock);

                ?>
                <div class="card-body">

                    <table id="example" class="display nowrap" style="width:100%">
                        <!-- <table id="example" class="display nowrap" style="width:100%"> -->
                        <thead>
                            <tr>
                                <th style="text-align:center">No</th>
                                <th style="text-align:center">Pilih</th>
                                <th style="text-align:center">Tgl PO</th>
                                <th style="text-align:center">SPOP</th>
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


                                if ($list_data->uuid_barang) { //ada data barang

                                    $sisa_stock_data = $list_data->jumlah_sediaan - ($list_data->penjualan + $list_data->pecah_satuan_persediaan + $list_data->bahan_produksi);

                                    if ($sisa_stock_data > 0) { // stock lebih dari 0
                            ?>
                                        <tr>
                                            <td align="right"><?php echo ++$start ?></td>
                                            <td align="right">
                                                <?php
                                                if ($sisa_stock_data > 0) {
                                                ?>
                                                    <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-id="<?php echo $list_data->id; ?>" data-target="#modal-xl_1_<?php echo $list_data->id; ?>">
                                                        PILIH BARANG <?php //echo $list_data->id
                                                                        ?>
                                                    </button>
                                                <?php
                                                } else {
                                                ?>
                                                    <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-id="<?php echo $list_data->id; ?>" data-target="#modal-xl_1_<?php echo $list_data->id; ?>">
                                                        PILIH BARANG <?php //echo $list_data->id
                                                                        ?>
                                                    </button>
                                                <?php
                                                }
                                                ?>


                                            </td>
                                            <td>
                                                <?php
                                                echo date("d M Y", strtotime($list_data->tanggal_beli));
                                                ?>
                                            </td>


                                            <td align="left"><?php echo $list_data->spop; ?></td>

                                            <td align="left"><?php echo $list_data->nama_barang_beli; ?></td>


                                            <td align="right">
                                                <?php
                                                // echo nominal($list_data->harga_satuan_persediaan); 
                                                echo number_format($list_data->harga_satuan_persediaan, 2, ',', '.');
                                                ?>
                                            </td>
                                            <td align="left"><?php echo $list_data->satuan_persediaan; ?></td>
                                            <td align="right">
                                                <?php

                                                // if ($data_barang_terjual->jumlah_jual) {

                                                //     $sisa_stock_data = $data_barang_per_barang->jumlah_sediaan - $data_barang_terjual->jumlah_jual;
                                                // } else {

                                                //     $sisa_stock_data = $data_barang_per_barang->jumlah_sediaan;
                                                // }


                                                // echo $list_data->jumlah_sediaan;
                                                // echo "<br/>";

                                                // echo $list_data->jumlah_belanja;
                                                // echo "<br/>";

                                                // echo $list_data->jumlah_terjual;
                                                // echo "<br/>";


                                                // if ($list_data->jumlah_sediaan) {
                                                //     $get_jumlah_sediaan = $list_data->jumlah_sediaan;
                                                // } else {
                                                //     $get_jumlah_sediaan = 0;
                                                // }

                                                // if ($list_data->jumlah_belanja) {
                                                //     $get_jumlah_belanja = $list_data->jumlah_belanja;
                                                // } else {
                                                //     $get_jumlah_belanja = 0;
                                                // }

                                                // if ($list_data->jumlah_terjual) {
                                                //     $get_jumlah_terjual = $list_data->jumlah_terjual;
                                                // } else {
                                                //     $get_jumlah_terjual = 0;
                                                // }

                                                echo nominal($sisa_stock_data);


                                                ?>
                                            </td>


                                            <!-- <td><?php //echo nominal($list_data->jumlah_belanja - $list_data->jumlah_terjual); 
                                                        ?></td> -->
                                            <td align="left">
                                                <?php
                                                // echo anchor(site_url('tbl_pembelian/update_per_spop/'), '<i class="fa fa-pencil-square-o" aria-hidden="true">PILIH</i>', 'class="btn btn-warning btn-xs"');


                                                if ($sisa_stock_data > 0) {
                                                ?>
                                                    <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-id="<?php echo $list_data->id; ?>" data-target="#modal-xl_1_<?php echo $list_data->id; ?>">
                                                        PILIH BARANG <?php //echo $list_data->id
                                                                        ?>
                                                    </button>
                                                <?php
                                                } else {
                                                ?>
                                                    <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-id="<?php echo $list_data->id; ?>" data-target="#modal-xl_1_<?php echo $list_data->id; ?>">
                                                        PILIH BARANG <?php //echo $list_data->id
                                                                        ?>
                                                    </button>
                                                <?php
                                                }
                                                ?>







                                                <?php
                                                // if (!empty($uuid_penjualan)) {
                                                // print_r("ADa uuid penjualan");
                                                ?>
                                                <!-- <form action="<?php //echo $action . $uuid_penjualan . "/" . $list_data->id; 
                                                                    ?>" method="post"> -->
                                                <?php
                                                // } else {
                                                // print_r("TIDAK ADa uuid penjualan");
                                                ?>
                                                <!-- <form action="<?php //echo $action . "new/" . $list_data->id; 
                                                                    ?>" method="post"> -->
                                                <?php
                                                // }
                                                ?>



                                                <form action="<?php echo $action_simpan_bahan ; ?>" method="post">
                                                    <div class="modal fade" id="modal-xl_1_<?php echo $list_data->id; ?>">
                                                        <div class="modal-dialog modal-xl">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Isi Jumlah Bahan</h4>
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

                                                                                
                                                                                <!-- <input type="text" class="form-control" rows="3" name="harga_satuan_beli" id="harga_satuan_beli" value="<?php
                                                                                                                                                                                                // echo nominal($list_data->harga_satuan_persediaan); 
                                                                                                                                                                                                echo number_format($list_data->harga_satuan_persediaan, 2, ',', '.');
                                                                                                                                                                                                ?>" placeholder="<?php
                                                                                                                                                                                                                            // echo nominal($list_data->harga_satuan_persediaan); 
                                                                                                                                                                                                                            echo number_format($list_data->harga_satuan_persediaan, 2, ',', '.');
                                                                                                                                                                                                                            ?>"> -->
                                                                            </div>
                                                                            <div class="col-4">
                                                                                <label style="color:red" for="nmrkirim">Jumlah Maks= <?php echo $sisa_stock_data ?></label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">


                                                                            <div class="col-4">
                                                                                <input type="text" class="form-control" rows="3" name="harga_satuan_beli" id="harga_satuan_beli" value="<?php
                                                                                                                                                                                        // echo nominal($list_data->harga_satuan_persediaan); 
                                                                                                                                                                                        echo number_format($list_data->harga_satuan_persediaan, 2, ',', '.');
                                                                                                                                                                                        ?>" placeholder="<?php
                                                                                                                                                                                                                    // echo nominal($list_data->harga_satuan_persediaan); 
                                                                                                                                                                                                                    echo number_format($list_data->harga_satuan_persediaan, 2, ',', '.');
                                                                                                                                                                                                                    ?>">
                                                                            </div>
                                                                            <div class="col-4">
                                                                                <!-- <input type="text" class="form-control" rows="3" name="jumlah" id="jumlah" min="1" max="5" placeholder="jumlah"> -->
                                                                                <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" max="<?php echo $sisa_stock_data ?>">

                                                                            </div>

                                                                        </div>

                                                                    </div>


                                                                </div>

                                                                <div class="modal-footer justify-content-between">

                                                                    <input type="hidden" name="uuid_persediaan" id="uuid_persediaan" value="<?php echo $list_data->uuid_persediaan; ?>" />
                                                                    <input type="hidden" name="id_persediaan" id="id_persediaan" value="<?php echo $list_data->id; ?>" />
<!--                                                                     
                                                                    <input type="hidden" name="tgl_jual" id="tgl_jual" value="<?php //echo $tgl_jual_X; ?>" />

                                                                    <input type="hidden" name="uuid_konsumen" id="uuid_konsumen" value="<?php //echo $uuid_konsumen; ?>" />

                                                                    <input type="hidden" name="nmrpesan" id="nmrpesan" value="<?php //echo $nmrpesan; ?>" />

                                                                    <input type="hidden" name="nmrkirim" id="nmrkirim" value="<?php //echo $nmrkirim; ?>" /> -->

                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->

                                                                    <button type="submit" class="btn btn-primary">PROSES SIMPAN BAHAN</button>

                                                                </div>

                                                            </div>
                                                            <!-- /.modal-content -->
                                                        </div>

                                                    </div>
                                                </form>

                                            </td>

                                        </tr>
                            <?php
                                    }
                                }
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

<!-- ============== -->




<?php

foreach ($data_penjualan_per_uuid_penjualan as $list_data) {
?>
    <!-- MODAL EXTRA LARGE UPDATE PER ID -->
    <form action="<?php echo $action_ubah_per_id . $list_data->id; ?>" method="post">
        <div class="modal fade" id="modal-xl-input-barang_<?php echo $list_data->id ?>">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Update Barang <?php echo $list_data->id
                                                                ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <?php
                    // echo $action_ubah_per_id . $list_data->id;

                    $row_data_barang_jual = $this->Tbl_penjualan_model->get_by_id($list_data->id);

                    // cek data stock persediaan dengan filter by id_persediaan_barang dari tabel penjualan


                    $get_data_Persediaan_by_id = $this->Persediaan_model->get_by_id($row_data_barang_jual->id_persediaan_barang);

                    // Jumlah stock dikurangi , sudah terjual yang dikurang barang terjual di id penjualan ini
                    $Get_stock_di_persediaan = $get_data_Persediaan_by_id->total_10 - ($get_data_Persediaan_by_id->penjualan - $row_data_barang_jual->jumlah);

                    // print_r($row_data_barang_jual);

                    // `id`, `uuid_penjualan_proses`, `uuid_penjualan`, `uuid_persediaan`, `id_persediaan_barang`, `uuid_barang`, `tgl_input`, `tgl_jual`, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, ``, `umpphpsl22`, `piutang`, `penjualandpp`, `utangppn`, `cetak_bukti_penjualan`, `id_usr`, ``, ``, ``, ``, ``, ``


                    ?>

                    <div class="modal-body">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-12">
                                    <label for="konsumen_nama">Barang</label>
                                    <input type="text" class="form-control" rows="3" name="nama_barang" id="nama_barang" placeholder="nama_barang" value="<?php echo $row_data_barang_jual->nama_barang ?>" disabled>
                                </div>
                            </div>



                            <div class="row">
                                <div class="col-4">
                                    <label for="nmrpesan">Harga Satuan </label>
                                    <!-- <input type="text" class="form-control" rows="3" name="harga_satuan_beli" id="harga_satuan_beli" value="<?php // echo number_format($row_data_barang_jual->harga_satuan, 2, ',', '.'); 
                                                                                                                                                    ?>" placeholder="<?php // echo nominal($list_data->harga_satuan_persediaan);  echo number_format($list_data->harga_satuan_persediaan, 2, ',', '.'); 
                                                                                                                                                                        ?>"> -->
                                </div>
                                <div class="col-4">
                                    <label style="color:red" for="nmrkirim">Jumlah Maks= <?php echo $Get_stock_di_persediaan; ?></label>
                                </div>
                            </div>
                            <div class="row">


                                <div class="col-4">
                                    <input type="text" class="form-control" rows="3" name="harga_satuan" id="harga_satuan" value="
                                    <?php echo number_format($row_data_barang_jual->harga_satuan, 2, ',', '.'); ?>" placeholder="<?php echo number_format($row_data_barang_jual->harga_satuan, 2, ',', '.'); ?>">
                                </div>
                                <div class="col-4">
                                    <!-- <input type="text" class="form-control" rows="3" name="jumlah" id="jumlah" min="1" max="5" placeholder="jumlah"> -->
                                    <input type="number" class="form-control" id="jumlah" name="jumlah" value="<?php echo $row_data_barang_jual->jumlah; ?>" min="1" max="<?php echo $Get_stock_di_persediaan ?>">

                                </div>

                            </div>

                        </div>


                    </div>


                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <!-- <button type="button" class="btn btn-primary">Simpan</button> -->
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </form>
    <!-- END OF MODAL EXTRA LARGE -->
<?php
}
?>


<!-- END OF MODAL TABEL DATA PERSEDIAAN DAN SUB MODAL UNTUK INPUT JUMLAH STOCK YANG DI GUNAKAN -->


<!-- MODAL FORM INPUT NAMA PRODUK BARU -->
<?php
if ($id_persediaan_barang) {
?>
    <form action="<?php echo $action_simpan_nama_produk_baru . $id_persediaan_barang; ?>" method="post">
    <?php
} else {
    ?>
        <form action="<?php echo $action_simpan_nama_produk_baru; ?>" method="post">
        <?php
    }
        ?>

        <div class="modal fade" id="modal-xl-input_nama_produk_baru">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Input Nama Produk <?php //echo $action_simpan_nama_produk_baru; 
                                                                    ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">







                        <div class="form-group">
                            <div class="row">

                                <div class="col-4">
                                    <label for="keterangan">Tanggal<?php echo form_error('tgl_transaksi') ?></label>


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
                                    <label for="konsumen_nama">Unit <?php //echo form_error('konsumen_nama') 
                                                                    ?></label>
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


                        <div class="form-group">
                            <div class="row">
                                <div class="col-4">
                                    <label for="keterangan">Nama Produk <?php echo form_error('nama_barang') ?></label>
                                    <input type="hidden" name="id_persediaan_barang" id="id_persediaan_barang" value="<?php echo $id_persediaan_barang; ?>" />
                                    <input type="hidden" name="uuid_barang" id="uuid_barang" value="<?php echo $uuid_barang; ?>" />
                                    <input class="form-control" rows="3" name="nama_barang" id="nama_barang" placeholder="nama_barang" value="<?php echo $nama_barang; ?>" required>
                                </div>

                                <div class="col-3">
                                    <label for="keterangan">Satuan <?php echo form_error('satuan') ?></label>
                                    <input class="form-control" rows="3" name="satuan" id="satuan" placeholder="satuan" value="<?php echo $satuan; ?>" required>

                                </div>
                                <div class="col-5">
                                    <label for="keterangan">Harga Satuan </label>

                                    <input class="form-control uang" rows="3" name="harga_satuan" id="harga_satuan" placeholder="harga_satuan" value="<?php echo number_format($harga_satuan, 0, ',', '.'); ?>" required>

                                    <?php echo "<p style='font-size:0.75em;color:#c50d0d;font-weight:bold;'> Total Harga Bahan: " . number_format($TOTAL_HARGA, 0, ',', '.') . "</p>" ?>

                                </div>

                            </div>
                        </div>


                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <label for="keterangan">Keterangan </label>

                                    <input type="text" class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="keterangan" value="<?php echo $keterangan;
                                                                                                                                                        ?>">
                                </div>



                            </div>
                        </div>



                    </div>

                    <div class="modal-footer justify-content-between">


                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">SIMPAN PRODUK</button>

                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        </form>
        <!-- END OF MODAL FORM INPUT NAMA PRODUK BARU -->













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