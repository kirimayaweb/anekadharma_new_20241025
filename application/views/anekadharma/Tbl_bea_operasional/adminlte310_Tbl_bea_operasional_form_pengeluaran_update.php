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
                                    <div class="col-12" text-align="center"> <strong>UPDATE PEMBAYARAN OPERASIONAL</strong></div>
                                </div>


                            </div>
                            <div class="col-6">

                            </div>



                        </div>




                    </div>
                    <br />



                    <div class="card-body">

                        <form action="<?php echo $action; ?>" method="post">
<!-- 
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-3">
                                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-xl-select-unit">
                                            Pilih data pembelian
                                          
                                        </button>
                                    </div>
                                </div>
                            </div> -->


                           
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
                                        <input type="text" class="form-control" name="kredit" id="kredit" placeholder="kredit" value="<?php echo $kredit; ?>" style="font-size:1.5vw;font-weight: bold;text-align:right;color:black;" min="1" max="9999999999999" ; required />

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
                                        <a href="<?php echo site_url('Tbl_bea_operasional') ?>" class="btn btn-default">Cancel</a>

                                    </div>

                                    <div class="col-3">
                                    </div>

                                </div>
                            </div>





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