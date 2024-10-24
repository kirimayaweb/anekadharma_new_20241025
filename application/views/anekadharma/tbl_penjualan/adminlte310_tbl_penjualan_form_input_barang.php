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
                                            Input Barang
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
                                                <th style="text-align:center">Unit</th>
                                                <th style="text-align:right">Jumlah</th>
                                                <th style="text-align:center">Satuan</th>
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
                                                    <td><?php echo $list_data->unit; ?></td>
                                                    <td style="text-align:right"><?php echo nominal($list_data->jumlah); ?></td>
                                                    <td style="text-align:center"><?php echo $list_data->satuan; ?></td>
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
                                        $Data_stock = $this->Tbl_pembelian_model->stock();



                                        // print_r($Data_stock);

                                        ?>
                                        <div class="card-body">

                                            <table id="example" class="display nowrap" style="width:100%">
                                                <!-- <table id="example" class="display nowrap" style="width:100%"> -->
                                                <thead>
                                                    <tr>
                                                        <th style="text-align:center">No</th>
                                                        <th style="text-align:center">Tgl Beli</th>
                                                        <th style="text-align:center">nama_barang_beli</th>
                                                        <th style="text-align:center">harga_satuan_beli</th>
                                                        <th style="text-align:center">Sisa Stock</th>
                                                        <th style="text-align:center">pilih</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php

                                                    $start = 0;
                                                    foreach ($Data_stock as $list_data) {

                                                        if (($list_data->jumlah_belanja - $list_data->jumlah_terjual) > 0) {  //HIDE TOTAL SISA STOCK = 0;
                                                    ?>
                                                            <tr>
                                                                <td><?php echo ++$start ?></td>
                                                                <td>
                                                                    <?php
                                                                    // echo $list_data->tgl_po; 

                                                                    echo date("d M Y", strtotime($list_data->tgl_po));

                                                                    ?>
                                                                </td>
                                                                <td><?php echo $list_data->nama_barang_beli; ?></td>
                                                                <td><?php echo nominal($list_data->harga_satuan_beli); ?></td>
                                                                <td><?php echo nominal($list_data->jumlah_belanja - $list_data->jumlah_terjual); ?></td>
                                                                <td>
                                                                    <?php
                                                                    // echo anchor(site_url('tbl_pembelian/update_per_spop/'), '<i class="fa fa-pencil-square-o" aria-hidden="true">PILIH</i>', 'class="btn btn-warning btn-xs"');
                                                                    ?>



                                                                    <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-id="<?php echo $list_data->id_pembelian; ?>" data-target="#modal-xl_1_<?php echo $list_data->id_pembelian; ?>">
                                                                        PILIH BARANG
                                                                    </button>



                                                                    <?php
                                                                    if (!empty($uuid_penjualan)) {
                                                                        // print_r("ADa uuid penjualan");
                                                                    ?>
                                                                        <form action="<?php echo $action . $uuid_penjualan . "/" . $list_data->id_pembelian; ?>" method="post">
                                                                        <?php
                                                                    } else {
                                                                        // print_r("TIDAK ADa uuid penjualan");
                                                                        ?>
                                                                            <form action="<?php echo $action . "new/" . $list_data->id_pembelian; ?>" method="post">
                                                                            <?php
                                                                        }
                                                                            ?>

                                                                            <div class="modal fade" id="modal-xl_1_<?php echo $list_data->id_pembelian; ?>">
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
                                                                                                    <div class="col-12">
                                                                                                        <label for="nmrpesan">Harga Satuan </label>
                                                                                                        <input type="text" class="form-control" rows="3" name="harga_satuan_beli" id="harga_satuan_beli" value="<?php echo nominal($list_data->harga_satuan_beli); ?>" placeholder="<?php echo nominal($list_data->harga_satuan_beli); ?>">
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="row">


                                                                                                    <div class="col-12">
                                                                                                        <label for="nmrkirim">Jumlah </label>
                                                                                                        <input type="text" class="form-control" rows="3" name="jumlah" id="jumlah" min="1" max="5" placeholder="jumlah">
                                                                                                    </div>


                                                                                                </div>



                                                                                                <div class="row">
                                                                                                    <div class="col-6">
                                                                                                        <label for="konsumen_nama">Unit </label>
                                                                                                        <select name="uuid_unit" id="uuid_unit" class="form-control select2" style="width: 100%; height: 60px;" required>
                                                                                                            <option value="">Pilih Unit</option>
                                                                                                            <?php

                                                                                                            $sql = "select * from sys_unit order by nama_unit ASC ";
                                                                                                            foreach ($this->db->query($sql)->result() as $m) {
                                                                                                                echo "<option value='$m->uuid_unit' ";
                                                                                                                echo ">  " . strtoupper($m->nama_unit)  . "</option>";
                                                                                                            }
                                                                                                            ?>
                                                                                                        </select>
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