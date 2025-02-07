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
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-3">
                                        <div class="col-12" text-align="center"> <strong>PRODUKSI</strong></div>
                                    </div>
                                    <div class="col-6">
                                        <?php echo anchor(site_url('Sys_unit_produk/create_produksi'), 'Input Produksi', 'class="btn btn-danger"');
                                        ?>
                                        <!-- 
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-xl-select-unit">
                                            Input Produksi
                                        </button> -->
                                    </div>
                                </div>
                            </div>

                        </div>




                    </div>
                    <!-- <br /> -->



                    <div class="card-body">




                        <div class="row">
                            <div class="col-12 col-sm-12">
                                <div class="card card-primary card-tabs">

                                    <div class="card-body">
                                        <div class="tab-content" id="custom-tabs-one-tabContent">
                                            <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">

                                                <div class="row">
                                                    <!-- <div class="col-1"></div> -->
                                                    <div class="col-6">
                                                        <?php //echo anchor(site_url('Sys_unit_produk/create_unit/'.$uuid_unit_selected), 'Input Hasil / Produk Unit: ' . $nama_unit, 'class="btn btn-success"'); 
                                                        ?>
                                                    </div>
                                                </div>

                                                <table id="example" class="display nowrap" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="80px">No</th>
                                                            <th width="200px">Action</th>
                                                            <!-- <th>Uuid Unit</th> -->
                                                            <!-- <th>Kode Unit</th> -->

                                                            <th>Tgl Transaksi</th>
                                                            <th>Nama Unit</th>

                                                            <!-- <th>Uuid Barang</th> -->
                                                            <!-- <th>Kode Barang</th> -->
                                                            <th>Nama Barang</th>
                                                            <th>Jumlah Produksi</th>
                                                            <th>Satuan</th>
                                                            <th>Harga Satuan</th>


                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $start = 0;
                                                        $get_saldo = 0;
                                                        foreach ($Sys_unit_produk_data as $list_data) {

                                                        ?>
                                                            <tr>
                                                                <td style="text-align:center"><?php echo ++$start ?></td>
                                                                <td style="text-align:left">
                                                                    <?php

                                                                    $this->db->where('uuid_persediaan', $list_data->uuid_persediaan);
                                                                    $persediaan_nama_barang = $this->db->get('persediaan');

                                                                    echo anchor(site_url('Sys_unit_produk/update_produksi/' . $persediaan_nama_barang->row()->id), '<i class="fa fa-pencil-square-o">Ubah</i>', array('title' => 'edit', 'class' => 'btn btn-warning btn-sm'));
                                                                    // echo ' ';
                                                                    // echo anchor(site_url('Sys_unit_produk/delete/' . $list_data->id), '<i class="fa fa-trash-o">Hapus</i>', 'title="delete" class="btn btn-danger btn-sm" onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
                                                                    ?>
                                                                </td>


                                                                <td style="text-align:left"><?php echo $list_data->tgl_transaksi; ?> </td>
                                                                <td style="text-align:left"><?php echo $list_data->nama_unit; ?> </td>
                                                                <td style="text-align:left"><?php echo $list_data->nama_barang; ?> </td>
                                                                <td style="text-align:right"><?php echo $list_data->jumlah_produksi; ?> </td>
                                                                <td style="text-align:left"><?php echo $list_data->satuan; ?> </td>
                                                                <td style="text-align:right"><?php echo number_format($list_data->harga_satuan, 2, ',', '.') ; ?> </td>


                                                                <!-- `id`, `uuid_unit`, `kode_unit`, `nama_unit`, ``, `uuid_produk`, `kode_barang`, `nama_barang`, `jumlah_produksi`, `satuan`, `harga_satuan` -->

                                                            </tr>
                                                        <?php
                                                        }
                                                        ?>


                                                    </tbody>

                                                    <tfoot>
                                                        <tr>
                                                            <th width="80px"></th>
                                                            <!-- <th>Uuid Kas Kecil</th> -->
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th>SALDO</th>
                                                            <th><?php echo nominal($get_saldo); ?></th>
                                                            <!-- <th>Id Usr</th> -->
                                                            <th width="200px"></th>
                                                        </tr>

                                                    </tfoot>



                                                </table>


                                            </div>

                                        </div>
                                    </div>
                                    <!-- /.card -->
                                </div>
                            </div>

                        </div>



                    </div>
                    <!-- /.card-body -->






                </div>
            </div>





        </div>




    </section>
</div>




<!-- MODAL EXTRA LARGE -->

<div class="modal fade" id="modal-xl-select-unit">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Produk Barang</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <form action="<?php echo $action; ?>" method="post">



                        <div class="form-group">
                            <div class="row">
                                <div class="col-12">
                                    Silahkan isi nama produk baru jika belum nama produk di aplikasi, Kemudian klik Proses:
                                    <br />
                                </div>

                            </div>
                        </div>


                        <div class="form-group">
                            <div class="row">
                                <div class="col-4">
                                    <label for="keterangan">Tanggal Produksi <?php echo form_error('tgl_transaksi') ?></label>

                                    <div class="input-group date" id="tgl_transaksi" name="tgl_transaksi" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#tgl_transaksi" id="tgl_transaksi" name="tgl_transaksi" required />
                                        <div class="input-group-append" data-target="#tgl_transaksi" data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>

                                        </div>

                                    </div>
                                </div>



                            </div>
                        </div>



                        <div class="form-group">
                            <div class="row">

                                <div class="col-4">
                                    <label for="keterangan">Nama Produk Baru </label>

                                    <input class="form-control" rows="3" name="nama_barang" id="nama_barang" placeholder="nama_barang">

                                </div>

                                <div class="col-4">
                                    <label for="keterangan">Satuan <?php //echo form_error('satuan') 
                                                                    ?></label>
                                    <input class="form-control" rows="3" name="satuan" id="satuan" placeholder="satuan" required>

                                </div>
                                <div class="col-4">
                                    <label for="keterangan">Harga Satuan <?php //echo form_error('harga_satuan') 
                                                                            ?></label>
                                    <input class="form-control uang" rows="3" name="harga_satuan" id="harga_satuan" placeholder="harga_satuan" required>

                                </div>

                            </div>
                        </div>



                        <div class="form-group">
                            <div class="row">
                                <div class="col-4">
                                    <label for="konsumen_nama">Unit <?php echo form_error('konsumen_nama') ?></label>
                                    <select name="uuid_unit" id="uuid_unit" class="form-control select2" style="width: 100%; height: 40px;" required>
                                        <?php
                                        // if ($uuid_unit) {
                                        ?>
                                        <!-- <option value="<?php echo $uuid_unit; ?>"><?php echo $nama_unit; ?> </option> -->
                                        <?php
                                        // } else {
                                        ?>
                                        <option value="">Pilih Unit </option>
                                        <?php
                                        // }
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
                                    <input class="form-control uang" rows="3" name="jumlah_produksi" id="jumlah_produksi" placeholder="jumlah_produksi" required>
                                </div>

                            </div>
                        </div>


                        <div class="form-group">
                            <div class="row">
                                <div class="col-4">

                                </div>
                                <div class="col-4" align="center">

                                    <button type="submit" class="btn btn-primary">Simpan dan lanjut mengisi bahan-bahan produksi</button>

                                </div>
                                <div class="col-4">

                                </div>
                            </div>
                        </div>





                    </form>
                </div>


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
            "scrollY": 700,
            "scrollX": true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#example9').DataTable({
            "scrollY": 200,
            "scrollX": true
        });
    });
</script>