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
                                        <?php //echo anchor(site_url('Sys_unit_produk/create'), 'Tambah Produksi', 'class="btn btn-danger"');
                                        ?>

                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-xl-select-unit">
                                            Input Produksi
                                        </button>
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
                                                                    echo anchor(site_url('Tbl_kas_kecil/update/' . $list_data->id), '<i class="fa fa-pencil-square-o">Ubah</i>', array('title' => 'edit', 'class' => 'btn btn-warning btn-sm'));
                                                                    echo ' ';
                                                                    echo anchor(site_url('Tbl_kas_kecil/delete/' . $list_data->id), '<i class="fa fa-trash-o">Hapus</i>', 'title="delete" class="btn btn-danger btn-sm" onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
                                                                    ?>
                                                                </td>


                                                                <td style="text-align:left"><?php echo $list_data->tgl_transaksi; ?> </td>
                                                                <td style="text-align:left"><?php echo $list_data->nama_unit; ?> </td>
                                                                <td style="text-align:left"><?php echo $list_data->nama_barang; ?> </td>
                                                                <td style="text-align:right"><?php echo $list_data->jumlah_produksi; ?> </td>
                                                                <td style="text-align:left"><?php echo $list_data->satuan; ?> </td>
                                                                <td style="text-align:right"><?php echo $list_data->harga_satuan; ?> </td>


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
<form action="<?php echo $action; ?>" method="post">
    <div class="modal fade" id="modal-xl-select-unit">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Unit Produksi</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">


                        <div class="row">

                            <div class="col-4">
                            </div>

                            <div class="col-4">
                                <label for="konsumen_nama">Unit <?php echo form_error('konsumen_nama') ?></label>
                                <select name="uuid_unit" id="uuid_unit" class="form-control select2" style="width: 100%; height: 40px;" required>
                                    <option value="">Pilih Unit </option>
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
                            </div>
                        </div>




                    </div>

                </div>

                <div class="modal-footer">



                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button> -->
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
        $('#example9').DataTable({
            "scrollY": 900,
            "scrollX": true
        });
    });
</script>