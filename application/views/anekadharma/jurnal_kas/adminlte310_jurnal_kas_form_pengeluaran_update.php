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
                                    <div class="col-12" text-align="center"> <strong>UPDATE PENGELUARAN KAS</strong></div>
                                </div>


                            </div>
                            <div class="col-6">

                            </div>



                        </div>




                    </div>
                    <br />



                    <div class="card-body">

                        <form action="<?php echo $action; ?>" method="post">

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-3">
                                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-xl-select-unit">
                                            Pilih data pembelian
                                            <?php //echo $Get_spop_lama; 
                                            ?>
                                        </button>
                                    </div>
                                </div>
                            </div>


                            <?php

                            if ($Get_data_proses) {
                                // print_r("ada pilih data");
                            ?>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-12">


                                            <!-- ================== -->
                                            <?php
                                            if ($Get_spop_lama) {


                                            ?>
                                                Mengganti SPOP lama yang sudah terbayarkan lunas:
                                                <div class="col-12 col-sm-12 col-md-12">
                                                    <div class="info-box bg-danger mb-12">
                                                        <span class="info-box-icon bg-warning elevation-1" width="200px"><i class="fas fa-shopping-cart"></i></span>

                                                        <div class="info-box-content">
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-3">
                                                                        <span class="info-box-number"><?php echo "SPOP: " . ($Get_spop_lama->spop); ?></span>
                                                                    </div>

                                                                    <div class="col-3">
                                                                        <span class="info-box-text"><?php echo "Supplier: " . ($Get_spop_lama->nama_supplier); ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <div class="row">


                                                                    <div class="col-3">
                                                                        <span class="info-box-text"><?php echo "Tanggal: " . ($Get_spop_lama->tanggal); ?></span>
                                                                    </div>

                                                                    <div class="col-3">
                                                                        <span class="info-box-number"><?php echo "TOTAL TAGIHAN: " . (nominal($Get_spop_lama->sum_harga_total)); ?></span>
                                                                    </div>

                                                                </div>
                                                            </div>



                                                        </div>
                                                        <!-- /.info-box-content -->
                                                    </div>
                                                    <!-- /.info-box -->
                                                </div>

                                                Diganti:
                                            <?php
                                            }
                                            ?>
                                            <div class="col-12 col-sm-12 col-md-12">
                                                <div class="info-box bg-success mb-12">
                                                    <span class="info-box-icon bg-warning elevation-1" width="200px"><i class="fas fa-shopping-cart"></i></span>

                                                    <div class="info-box-content">
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-3">
                                                                    <span class="info-box-number"><?php echo "SPOP: " . ($Get_data_proses->spop); ?></span>
                                                                </div>

                                                                <div class="col-3">
                                                                    <span class="info-box-text"><?php echo "Supplier: " . ($Get_data_proses->nama_supplier); ?></span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="row">


                                                                <div class="col-3">
                                                                    <span class="info-box-text"><?php echo "Tanggal: " . ($Get_data_proses->tanggal); ?></span>
                                                                </div>

                                                                <div class="col-3">
                                                                    <span class="info-box-number"><?php echo "TOTAL TAGIHAN: " . (nominal($Get_data_proses->sum_harga_total)); ?></span>
                                                                </div>

                                                            </div>
                                                        </div>



                                                    </div>
                                                    <!-- /.info-box-content -->
                                                </div>
                                                <!-- /.info-box -->
                                            </div>

                                            <!-- ================= -->

                                        </div>
                                    </div>
                                </div>

                            <?php
                            }

                            ?>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-3">
                                        <label for="date">Tanggal <?php echo form_error('tanggal') ?></label>


                                        <?php

                                        if ($tanggal) {
                                            $get_tanggal = date("d-m-Y", strtotime($tanggal));
                                        } else {
                                            $get_tanggal = date("d-m-Y");
                                        }

                                        ?>

                                        <div class="input-group date" id="tanggal" name="tanggal" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input" data-target="#tgl_po" id="tgl_po" name="tanggal" value="<?php echo date("d-m-Y", strtotime($get_tanggal)); ?>" required />
                                            <div class="input-group-append" data-target="#tgl_po" data-toggle="datetimepicker">
                                                <div class="input-group-text">
                                                    <i class="fa fa-calendar"></i>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <label for="unit">Unit <?php echo form_error('unit') ?></label>
                                        <!-- <textarea class="form-control" rows="3" name="unit" id="unit" placeholder="Unit"><?php //echo $unit; 
                                                                                                                                ?></textarea> -->


                                        <select name="unit" id="unit" class="form-control select2" style="width: 100%; height: 40px;" required>

                                            <?php

                                            if ($uuid_unit) {
                                            ?>
                                                <option value="<?php echo $uuid_unit; ?>"><?php echo $unit; ?></option>
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
                                    <div class="col-3">
                                        <label for="double">Kredit <?php echo form_error('kredit') ?></label>
                                        <input type="text" class="form-control uang" name="kredit" id="kredit" placeholder="kredit" value="<?php echo $kredit; ?>" style="font-size:1.5vw;font-weight: bold;text-align:right;color:black;" min="1" max="9999999999999" ; required />

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-3">
                                    </div>
                                    <div class="col-9">
                                        <label for="keterangan">Keterangan <?php echo form_error('keterangan') ?></label>
                                        <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"><?php echo $keterangan; ?></textarea>
                                    </div>


                                </div>
                            </div>



                            <div class="form-group">
                                <div class="row">
                                    <div class="col-3">
                                    </div>

                                    <div class="col-6">
                                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                        <button type="submit" class="btn btn-success"><?php echo $button ?></button>
                                        <a href="<?php echo site_url('tbl_kas_kecil') ?>" class="btn btn-default">Cancel</a>

                                    </div>

                                    <div class="col-3">
                                    </div>

                                </div>
                            </div>



                            <!-- MODAL EXTRA LARGE -->

                            <div class="modal fade" id="modal-xl-select-unit">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Data Pembelian</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body">



                                            <table id="example9" class="display nowrap" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th width="80px">No</th>
                                                        <th width="200px">Action</th>
                                                        <th>Tanggal</th>
                                                        <!-- <th>Unit</th> -->
                                                        <th>Spop</th>
                                                        <!-- <th>Pl</th> -->
                                                        <th>Supplier</th>
                                                        <!-- <th>Norek</th>
                                                        <th>Rekening</th> -->
                                                        <th>Jumlah</th>
                                                        <!-- <th>Uu21101</th> -->

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php

                                                    $start = 0;
                                                    foreach ($Tbl_pembelian_data as $list_data) {

                                                    ?>
                                                        <tr>
                                                            <td width="80px"><?php echo ++$start ?></td>
                                                            <td width="200px">
                                                                <?php
                                                                echo anchor(site_url('Tbl_kas_kecil/pengeluaran_kas_kecil_update/' . $id . '/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o">Proses Bayar</i>', array('title' => 'edit', 'class' => 'btn btn-warning btn-sm'));
                                                                ?>
                                                            </td>
                                                            <td><?php echo date("d-m-Y", strtotime($list_data->tanggal)); ?></td>
                                                            <!-- <td>Unit</td> -->
                                                            <td><?php echo $list_data->spop; ?></td>
                                                            <!-- <td>Pl</td> -->
                                                            <td><?php echo $list_data->nama_supplier_1; ?></td>
                                                            <!-- <td>Norek</td>
                                                            <td>Rekening</td> -->
                                                            <td><?php echo nominal($list_data->sum_harga_total); ?></td>
                                                            <!-- <td>Uu21101</td> -->

                                                        </tr>

                                                    <?php

                                                    }

                                                    ?>

                                                </tbody>


                                            </table>


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
            "scrollY": 900,
            "scrollX": true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#example9').DataTable({
            "scrollY": 250,
            "scrollX": true
        });
    });
</script>