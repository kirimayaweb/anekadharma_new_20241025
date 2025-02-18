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
                                                <th style="text-align:center">Update</th>
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
                                            $get_jumlah_barang = 0;
                                            $get_total_harga = 0;
                                            foreach ($data_penjualan_per_uuid_penjualan as $list_data) {

                                            ?>
                                                <tr>
                                                    <td><?php echo ++$start; ?></td>

                                                    <!-- Ubah dan hapus -->
                                                    <td>
                                                        <!-- <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-xl-update_<?php //echo $list_data->id; 
                                                                                                                                                                    ?>">
                                                            Ubah Barang <?php //echo $list_data->id; 
                                                                        ?>
                                                        </button> -->
                                                        <?php
                                                        echo anchor(site_url('tbl_penjualan/delete/' . $list_data->id . '/' . $list_data->uuid_penjualan), 'Hapus DATA', 'onclick="javascript: return confirm(\'Anda Yakin akan Menghapus Penjualan Barang ini ?\')"');

                                                        // echo anchor(site_url('tbl_penjualan/delete/' . $list_data->id . '/' . $list_data->uuid_penjualan), 'onclick="javascript: return confirm(\'Anda Yakin akan Menghapus Penjualan Barang ini ?\')"', '<i class="btn btn-outline-info btn-block btn-flat" aria-hidden="true">Hapus</i>', 'class="btn btn-block btn-flat"  ');

                                                        ?>


                                                        <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#modal-xl-input-barang_<?php echo $list_data->id ?>">
                                                            UBAH <?php //echo $list_data->id 
                                                                    ?>
                                                        </button>

                                                        <!-- <button type="button"  class="btn btn-outline-info btn-block btn-flat"> <i class="fa fa-book"></i> <?php // $button; 
                                                                                                                                                                ?></button> -->
                                                    </td>


                                                    <td>
                                                        <?php
                                                        // echo $list_data->tgl_po; 

                                                        echo date("d M Y", strtotime($list_data->tgl_jual));

                                                        ?>
                                                    </td>



                                                    <td><?php echo $list_data->nama_barang; ?></td>
                                                    <!-- <td><?php //echo $list_data->unit; 
                                                                ?></td> -->

                                                    <td style="text-align:center"><?php echo $list_data->satuan; ?></td>
                                                    <td style="text-align:right">
                                                        <?php
                                                        echo nominal($list_data->jumlah);
                                                        $get_jumlah_barang = $get_jumlah_barang + $list_data->jumlah;
                                                        ?>
                                                    </td>
                                                    <td style="text-align:right">
                                                        <?php
                                                        // echo nominal($list_data->harga_satuan); 
                                                        // echo "<br/>";
                                                        echo number_format($list_data->harga_satuan, 2, ',', '.');
                                                        ?>
                                                    </td>
                                                    <td style="text-align:right">
                                                        <?php
                                                        // echo nominal($list_data->jumlah * $list_data->harga_satuan);
                                                        echo number_format($list_data->jumlah * $list_data->harga_satuan, 2, ',', '.');
                                                        $get_total_harga = $get_total_harga + ($list_data->jumlah * $list_data->harga_satuan);
                                                        ?>
                                                    </td>









                                                </tr>



                                            <?php
                                            }
                                            ?>


                                        </tbody>


                                        <tfoot>
                                            <tr>
                                                <th style="text-align:center"></th>
                                                <th style="text-align:left"></th>
                                                <th style="text-align:center"></th>
                                                <th style="text-align:center"></th>

                                                <th style="text-align:center"></th>
                                                <th style="text-align:right"><?php echo nominal($get_jumlah_barang);  ?></th>
                                                <th style="text-align:right"></th>
                                                <th style="text-align:right">
                                                    <?php
                                                    // echo nominal($get_total_harga); 
                                                    echo number_format($get_total_harga, 2, ',', '.');
                                                    ?>
                                                </th>

                                            </tr>
                                        </tfoot>



                                    </table>


                                <?php
                                }
                                ?>



                            </div>
                        </div>







                        <!-- <input type="hidden" name="id" value="<?php //echo $id; 
                                                                    ?>" /> -->



                        <!-- <button type="submit" class="btn btn-primary"><?php //echo $button 
                                                                            ?></button> -->
                        <a href="<?php echo site_url('tbl_penjualan') ?>" class="btn btn-default">Kembali ke Halaman Data Penjualan</a>
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

                // $sql_data = "SELECT id,tanggal_beli,spop,uuid_barang,namabarang,hpp,satuan,sa,total_10 FROM persediaan WHERE spop <>'' AND (namabarang, tanggal_beli) IN (SELECT namabarang, Max(tanggal_beli) FROM persediaan GROUP BY namabarang)";

                // // $sql = "SELECT * FROM tbl_pembelian WHERE id=$id_proses";
                // $Data_stock = $this->db->query($sql_data)->result();


                $sql_stock = "SELECT persediaan.id as id, 
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

                                    $sisa_stock_data = $list_data->jumlah_sediaan - ($list_data->penjualan + $list_data->pecah_satuan_persediaan);

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
            "scrollY": 375,
            "scrollX": true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#example99').DataTable({
            "scrollY": 600,
            "scrollX": true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#example1000').DataTable({
            "scrollY": 600,
            "scrollX": true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#example1000_wrapper').DataTable({
            "scrollY": 500,
            "scrollX": true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#example1001_wrapper').DataTable({
            "scrollY": 500,
            "scrollX": true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#example1002_wrapper').DataTable({
            "scrollY": 500,
            "scrollX": true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#example1003_wrapper').DataTable({
            "scrollY": 500,
            "scrollX": true
        });
    });
</script>