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
                                        <div class="col-12" text-align="center"> <strong>DATA UNIT</strong></div>
                                    </div>
                                    <div class="col-6">
                                        <?php echo anchor(site_url('Sys_unit/create'), 'Tambah Unit Baru', 'class="btn btn-danger"'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <form action="<?php echo $action; ?>" method="post">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="row">
                                                <select name="uuid_sys_unit" id="uuid_sys_unit" class="form-control select2" style="width: 100%; height: 40px;" required>

                                                    <?php
                                                    if ($uuid_unit_selected) {
                                                    ?>
                                                        <option value="<?php echo $uuid_unit_selected ?>"><?php echo $nama_unit ?></option>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <option value="">Pilih Unit</option>
                                                    <?php
                                                    }
                                                    ?>



                                                    <?php
                                                    $sql = "select * from sys_unit order by nama_unit";
                                                    foreach ($this->db->query($sql)->result() as $m) {
                                                        echo "<option value='$m->uuid_unit' ";
                                                        echo "> " . strtoupper($m->nama_unit) . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="row">
                                                <button type="submit" class="btn btn-danger"> Cari Detail Unit</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>




                    </div>
                    <!-- <br /> -->



                    <div class="card-body">



                        <!-- <br /> -->

                        <!-- ./row -->
                        <div class="row">
                            <div class="col-12">
                                <h4>Data Belanja dan Produksi Unit <?php echo $nama_unit; ?> <small></small></h4>
                            </div>
                        </div>
                        <!-- ./row -->
                        <div class="row">
                            <div class="col-12 col-sm-12">
                                <div class="card card-primary card-tabs">
                                    <div class="card-header p-0 pt-1">
                                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">PRODUK</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Pembelian / Belanja</a>
                                            </li>

                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content" id="custom-tabs-one-tabContent">
                                            <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">

                                                <div class="row">
                                                    <!-- <div class="col-1"></div> -->
                                                    <div class="col-6">
                                                        <?php echo anchor(site_url('Sys_unit_produk/create_unit/'.$uuid_unit_selected), 'Input Hasil / Produk Unit: ' . $nama_unit, 'class="btn btn-success"'); ?>
                                                    </div>
                                                </div>

                                                <table id="exampleFreeze" class="display nowrap" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th style="text-align:center" width="10px">No</th>
                                                            <th>tgl_transaksi</th>
                                                            <th>nama_barang</th>
                                                            <th>jumlah_produksi</th>
                                                            <th>Satuan</th>
                                                            <th>Harga Satuan</th>
                                                            <th>Total</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $start = 0;
                                                        $Total_produksi = 0;
                                                        foreach ($data_produk_per_unit as $list_data) {
                                                        ?>
                                                            <tr>
                                                                <td><?php echo ++$start ?></td>
                                                                <td><?php echo $list_data->tgl_transaksi ?></td>
                                                                <td style="text-align:right"><?php echo $list_data->nama_barang ?></td>
                                                                <td style="text-align:right"><?php echo $list_data->jumlah_produksi ?></td>
                                                                <td><?php echo $list_data->satuan ?></td>
                                                                <td style="text-align:right"><?php echo $list_data->harga_satuan ?></td>
                                                                <td style="text-align:right">
                                                                    <?php
                                                                    $total_beli = $list_data->jumlah_produksi * $list_data->harga_satuan;
                                                                    echo nominal($total_beli);
                                                                    $Total_produksi = $Total_produksi + $total_beli;
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                        }
                                                        ?>


                                                    </tbody>

                                                    <tfoot>
                                                        <tr>
                                                            <th width="10px"></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th style="text-align:right"><?php echo nominal($Total_produksi); ?></th>
                                                        </tr>

                                                    </tfoot>



                                                </table>


                                            </div>
                                            <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">

                                                <table id="example" class="display nowrap" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th style="text-align:center" width="10px">No</th>
                                                            <th>Nama Barang</th>
                                                            <th>Jumlah</th>
                                                            <th>Satuan</th>
                                                            <th>Harga Satuan</th>
                                                            <th>Total</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $start = 0;
                                                        $Total_pembelian = 0;
                                                        foreach ($data_unit_beli as $list_data) {
                                                        ?>
                                                            <tr>
                                                                <td><?php echo ++$start ?></td>
                                                                <td><?php echo $list_data->uraian ?></td>
                                                                <td style="text-align:right"><?php echo $list_data->jumlah ?></td>
                                                                <td><?php echo $list_data->satuan ?></td>
                                                                <td style="text-align:right"><?php echo $list_data->harga_satuan ?></td>
                                                                <td style="text-align:right">
                                                                    <?php
                                                                    $total_beli = $list_data->jumlah * $list_data->harga_satuan;
                                                                    echo nominal($total_beli);
                                                                    $Total_pembelian = $Total_pembelian + $total_beli;
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                        }
                                                        ?>


                                                    </tbody>

                                                    <tfoot>
                                                        <tr>
                                                            <th width="10px"></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th style="text-align:right"><?php echo nominal($Total_pembelian); ?></th>
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