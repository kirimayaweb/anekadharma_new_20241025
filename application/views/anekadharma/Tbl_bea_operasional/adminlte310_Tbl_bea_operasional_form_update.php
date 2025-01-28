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
                                    <div class="col-12" text-align="center"> <strong>PEMBAYARAN OPERASIONAL</strong></div>
                                </div>


                            </div>
                            <div class="col-6">

                            </div>



                        </div>




                    </div>
                    <br />



                    <div class="card-body">

                        <form action="<?php echo $action; ?>" method="post">
                            <!-- <div class="form-group">
                                <label for="varchar">Uuid Kas Kecil <?php //echo form_error('uuid_kas_kecil') 
                                                                    ?></label>
                                <input type="text" class="form-control" name="uuid_kas_kecil" id="uuid_kas_kecil" placeholder="Uuid Kas Kecil" value="<?php //echo $uuid_kas_kecil; 
                                                                                                                                                        ?>" />
                            </div> -->







                            <?php

                            if ($Get_data_proses) {
                                // print_r("ada pilih data");
                            ?>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-3">
                                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-xl-select-unit">
                                                Pilih data pembelian
                                            </button>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-12">


                                            <!-- ================== -->

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
                            } elseif ($uuid_spop) {
                            ?>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-3">
                                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-xl-select-unit">
                                                Pilih data pembelian
                                            </button>
                                        </div>
                                    </div>
                                </div>


                                <?php

                                $sql = "SELECT 
                                        tbl_pembelian_a.id as id,
                                        tbl_pembelian_a.tgl_po as tanggal,
                                        tbl_pembelian_a.supplier_nama as nama_supplier,
                                        tbl_pembelian_a.uuid_spop as uuid_spop,
                                        tbl_pembelian_a.spop as spop,
                                        -- tbl_pembelian_a.jumlah as jumlah,
                                        -- tbl_pembelian_a.harga_satuan as harga_satuan,
                                        sum(tbl_pembelian_a.harga_total) as sum_harga_total,
                                        (tbl_pembelian_a.jumlah*tbl_pembelian_a.harga_satuan) as total_belanja,
                                        sys_supplier_a.nama_supplier as nama_supplier_1
                                    
                            
                                        FROM tbl_pembelian tbl_pembelian_a 
                            
                                        left join   sys_supplier  sys_supplier_a ON  sys_supplier_a.nama_supplier = tbl_pembelian_a.supplier_nama
                                        where tbl_pembelian_a.uuid_spop LIKE '$uuid_spop'
                                        group by tbl_pembelian_a.uuid_spop
                                        order by tbl_pembelian_a.spop ASC
                                        ";

                                $Get_data_proses = $this->db->query($sql)->row();

                                ?>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-12">


                                            <!-- ================== -->

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
                                        // echo $this->input->post('tanggal', TRUE);
                                        // echo "<br/>";
                                        if ($tanggal) {
                                            // echo "ada tanggal";
                                            // echo $this->input->post('tanggal', TRUE);
                                            // $get_tanggal = date("d-m-Y", strtotime($tanggal));

                                            if (date("Y", strtotime($tanggal)) < 2020) {
                                                $get_tanggal = date("Y-m-d H:i:s");
                                            } else {
                                                $get_tanggal = date("Y-m-d H:i:s", strtotime($tanggal));
                                            }

                                        } else {
                                            // echo "tidak ada";
                                            $get_tanggal = date("Y-m-d H:i:s");
                                        }
                                        // echo $get_tanggal;
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

                                        <?php

                                        if ($debet) {
                                        ?>
                                            <label for="double">Debet <?php echo form_error('debet') ?></label>
                                            <input type="text" class="form-control uang" name="debet" id="debet" placeholder="Debet" value="<?php echo $debet; ?>" style="font-size:1.5vw;font-weight: bold;text-align:right;color:black;" min="1" max="9999999999999" ; required />
                                        <?php
                                        } else {
                                        ?>
                                            <label for="double">Kredit <?php echo form_error('kredit') ?></label>
                                            <input type="text" class="form-control uang" name="kredit" id="kredit" placeholder="kredit" value="<?php echo $kredit; ?>" style="font-size:1.5vw;font-weight: bold;text-align:right;color:black;" min="1" max="9999999999999" ; required />
                                        <?php
                                        }

                                        ?>




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
                                        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                                        <input type="hidden" name="status_proses" id="status_proses" value="<?php if ($debet) {
                                                                                                                echo "debet";
                                                                                                            } else {
                                                                                                                echo "kredit";
                                                                                                            } ?>" />
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
                                                        <th>Status</th>

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
                                                                echo anchor(site_url('Tbl_kas_kecil/update/' . $uuid_kas_kecil . '/' . $list_data->uuid_spop), '<i class="fa fa-pencil-square-o">Proses Bayar</i>', array('title' => 'edit', 'class' => 'btn btn-warning btn-sm'));
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
                                                            <td>
                                                                <?php
                                                                if ($list_data->statuslu == "U") {
                                                                    echo "U";
                                                                } else {
                                                                    echo "L";
                                                                }
                                                                // echo nominal($list_data->statuslu); 
                                                                ?>
                                                            </td>

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