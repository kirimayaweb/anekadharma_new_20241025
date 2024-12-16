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
                <div class="card card-primary">
                    <div class="card-header">
                        <div class="row">
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="row">
                                    <div class="col-12" text-align="center"> <strong>Ubah Data Penjualan</strong></div>
                                </div>


                            </div>
                            <div class="col-6">

                            </div>



                        </div>




                    </div>
                   


                    <div class="card-body">

                        Untuk proses perubahan nama barang penjualan dan yang lain , caranya:
                        <br />
                        1. Silahkan Ubah nama barang penjualan terlebih dahulu, jika akan ada perubahan nama barang penjualan.
                        <br />
                        2. baru kemudian merubah nilai yang lain.
                        <hr/>

                        <form action="<?php echo $action; ?>" method="post">

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-4">
                                        <label for="nmrpesan">Tanggal Jual <?php echo form_error('tgl_jual') ?></label>
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




                                    <div class="col-4">
                                        <label for="nmrpesan">Nomor Pesan <?php echo form_error('nmrpesan') ?></label>
                                        <input type="text" class="form-control" rows="3" name="nmrpesan" id="nmrpesan" placeholder="nmrpesan" value="<?php echo $nmrpesan; ?>" required>
                                    </div>

                                    <div class="col-4">
                                        <label for="nmrkirim">Nomor Kirim <?php echo form_error('nmrkirim') ?></label>
                                        <input type="text" class="form-control" rows="3" name="nmrkirim" id="nmrkirim" placeholder="nmrkirim" value="<?php echo $nmrkirim; ?>" required>
                                    </div>



                                </div>

                            </div>


                            <div class="form-group">
                                <div class="row">


                                    <div class="col-4">

                                        <label for="nama_barang">Nama Barang <?php echo form_error('nama_barang') ?></label>

                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-xl-select-unit">
                                            Ubah Barang Penjualan
                                        </button>
                                        <input type="hidden" name="uuid_barang" id="uuid_barang" value="<?php echo $uuid_barang; ?>" />
                                        <input type="hidden" name="id_persediaan_barang" id="id_persediaan_barang" value="<?php echo $id_persediaan_barang; ?>" />
                                        <input type="text" class="form-control" rows="3" name="nama_barang" id="nama_barang" placeholder="nama barang" value="<?php echo $nama_barang; ?>" required>


                                    </div>

                                    <div class="col-4">

                                        <label for="satuan">Satuan <?php echo form_error('satuan') ?></label>

                                        <input type="text" class="form-control" rows="3" name="satuan" id="satuan" placeholder="satuan" value="<?php echo $satuan; ?>" required disabled>


                                    </div>
                                    <div class="col-4">

                                        <label for="double">Harga Satuan <?php echo form_error('harga_satuan') ?></label>
                                        <input type="text" class="form-control" name="harga_satuan" id="harga_satuan" placeholder="Harga Satuan" value="<?php echo $harga_satuan; ?>" disabled />


                                    </div>


                                </div>

                            </div>



                            <div class="form-group">
                                <div class="row">

                                    <div class="col-4">

                                        <?php


                                        $data_konsumen = $this->Sys_konsumen_model->get_by_uuid_konsumen($uuid_konsumen);


                                        if (empty($data_konsumen)) {
                                            $data_konsumen = $this->Sys_unit_model->get_by_uuid_unit($uuid_konsumen);

                                            $data_nama_konsumen = $data_konsumen->nama_unit;
                                        } else {
                                            $data_nama_konsumen = $data_konsumen->nama_konsumen;
                                        }

                                        ?>

                                        <label for="konsumen_nama">Konsumen <?php echo form_error('konsumen_nama') ?></label>
                                        <select name="uuid_konsumen" id="uuid_konsumen" class="form-control select2" style="width: 100%; height: 40px;">
                                            <option value="<?php echo $uuid_konsumen; ?>"><?php echo $data_nama_konsumen; ?></option>
                                            <?php

                                            $sql = "select * from sys_unit order by nama_unit ASC ";
                                            foreach ($this->db->query($sql)->result() as $m) {
                                                echo "<option value='$m->uuid_unit' ";
                                                echo ">  " . strtoupper($m->nama_unit)  . "  ==> [UNIT] </option>";
                                            }

                                            $sql = "select * from sys_konsumen order by nama_konsumen ASC ";
                                            foreach ($this->db->query($sql)->result() as $m) {
                                                echo "<option value='$m->uuid_konsumen' ";
                                                echo ">  " . strtoupper($m->nama_konsumen) . " <strong> ==> (" . strtoupper($m->kelompok_dipersediaan) . ")</strong> " . strtoupper($m->alamat_konsumen) . "</option>";
                                            }
                                            ?>
                                        </select>

                                    </div>

                                    <div class="col-4">
                                        <label for="double">Jumlah <?php echo form_error('jumlah') ?></label>
                                        <input type="text" class="form-control" name="jumlah" id="jumlah" placeholder="Jumlah" value="<?php echo $jumlah; ?>" />

                                    </div>

                                    <div class="col-4">

                                    </div>


                                </div>
                            </div>



                            <!-- MODAL EXTRA LARGE -->
                            <!-- <form action="<?php //echo $action; 
                                                ?>" method="post"> -->

                            <div class="modal fade" id="modal-xl-select-unit">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Data Barang</h4>

                                        </div>

                                        <div class="modal-body">

                                            <div class="card-body">

                                                <?php

                                                $sql_stock = "SELECT persediaan.id as id_persediaan_barang, persediaan.kode_barang as kode_barang, persediaan.namabarang as nama_barang_beli,persediaan.total_10 as jumlah_sediaan,  persediaan.hpp as harga_satuan_persediaan,
                                                                                tbl_pembelian.uuid_pembelian as uuid_pembelian,tbl_pembelian.uraian as barang_beli, tbl_pembelian.jumlah as jumlah_belanja, tbl_pembelian.harga_satuan as harga_satuan_beli,  tbl_pembelian.tgl_po as tgl_po,tbl_pembelian.uuid_gudang as uuid_gudang, tbl_pembelian.nama_gudang as nama_gudang,  tbl_pembelian.satuan as satuan,
                                                                            tbl_penjualan.nama_barang as barang_jual, tbl_penjualan.jumlah as jumlah_terjual
                                                                            FROM persediaan  
                                                                            left join tbl_pembelian ON persediaan.uuid_barang = tbl_pembelian.uuid_barang 
                                                                            left join tbl_penjualan ON persediaan.uuid_barang = tbl_penjualan.uuid_barang  
                                                                            WHERE (persediaan.uuid_barang, persediaan.tanggal) IN (SELECT persediaan.uuid_barang, Max(persediaan.tanggal) FROM persediaan GROUP BY persediaan.uuid_barang)  
                                                                            ORDER BY persediaan.uuid_barang ASC";

                                                $Data_stock = $this->db->query($sql_stock)->result();

                                                ?>




                                                <table id="example" class="display nowrap" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th style="text-align:center" width="10px">No</th>
                                                            <th>Action</th>

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
                                                                    echo anchor(site_url('tbl_penjualan/update_action_pilih_barang/' . $uuid_penjualan_proses . "/Update_barang/" . $list_data->id_persediaan_barang), '<i class="fa fa-pencil-square-o" aria-hidden="true">Pilih Barang</i>', 'class="btn btn-warning btn-xs"  ');


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
                                                                        echo nominal($list_data->harga_satuan_persediaan);
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
                                            </div>

                                        </div>

                                        <div class="modal-footer">



                                            <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button> -->
                                            <!-- <button type="button" class="btn btn-primary">Simpan</button> -->
                                            <!-- <button type="submit" class="btn btn-primary">Proses</button> -->

                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- </form> -->

                            <!-- END OF MODAL EXTRA LARGE -->




                            <input type="hidden" name="id" value="<?php echo $id; ?>" />
                            <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                            <a href="<?php echo site_url('tbl_kas_kecil') ?>" class="btn btn-default">Cancel</a>
                        </form>
                    </div>
                    <!-- /.card-body -->
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
            "scrollY": 350,
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