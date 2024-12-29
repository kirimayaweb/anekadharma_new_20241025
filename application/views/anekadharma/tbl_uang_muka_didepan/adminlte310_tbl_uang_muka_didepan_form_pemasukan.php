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
                                    <div class="col-12" text-align="center"> <strong>pemasukan_kas_kecil_action DATA KAS KECIL</strong></div>
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
                                <label for="datetime">Tgl Transaksi <?php //echo form_error('tgl_transaksi') 
                                                                    ?></label>
                                <input type="text" class="form-control" name="tgl_transaksi" id="tgl_transaksi" placeholder="Tgl Transaksi" value="<?php //echo $tgl_transaksi; 
                                                                                                                                                    ?>" />
                            </div> -->


                            <!-- ============================ -->

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-3">
                                        <label for="date">Tanggal <?php echo form_error('tgl_transaksi') ?></label>


                                        <?php

                                        if ($this->input->post('tgl_transaksi', TRUE)) {
                                            $get_tanggal = date("d-m-Y", strtotime($tgl_transaksi));
                                        } else {
                                            $get_tanggal = date("Y-m-d H:i:s");
                                        }

                                        ?>

                                        <div class="input-group date" id="tgl_transaksi" name="tgl_transaksi" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input" data-target="#tgl_po" id="tgl_po" name="tgl_transaksi" value="<?php echo date("d-m-Y", strtotime($get_tanggal)); ?>" required />
                                            <div class="input-group-append" data-target="#tgl_po" data-toggle="datetimepicker">
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
                                    <div class="col-3">
                                        <label for="keterangan">dari <?php echo form_error('dari') ?></label>
                                        <input type="text" class="form-control" name="dari" id="dari" placeholder="dari" value="<?php echo $dari; ?>" required />
                                    </div>
                                    <div class="col-3">
                                        <label for="keterangan">Kode <?php echo form_error('kode') ?></label>
                                        <input type="text" class="form-control" name="kode" id="kode" placeholder="kode" value="<?php echo $kode; ?>" required />
                                    </div>
                                    <div class="col-6">

                                        <label for="uraian">Uraian <?php echo form_error('uraian') ?></label>
                                        <textarea class="form-control" rows="3" name="uraian" id="uraian" placeholder="Uraian"><?php echo $uraian; ?></textarea>


                                    </div>


                                </div>
                            </div>



                            <div class="form-group">
                                <div class="row">
                                    <div class="col-4">
                                        <label for="double">Nominal <?php echo form_error('nominal') ?></label>
                                        <input type="text" class="form-control uang" name="nominal" id="nominal" placeholder="nominal" value="<?php echo $nominal; ?>" style="font-size:1.5vw;font-weight: bold;text-align:right;color:black;" min="1" max="9999999999999" ; required />
                                    </div>

                                    <div class="col-4">
                                        <label for="double">Bank <?php echo form_error('bank') ?></label>
                                        <input type="text" class="form-control" name="bank" id="bank" placeholder="bank" value="<?php echo $bank; ?>" required />
                                    </div>

                                    <div class="col-4">
                                        <label for="double">Nomor Rekening <?php echo form_error('nmr_rekening') ?></label>
                                        <input type="text" class="form-control" name="nmr_rekening" id="nmr_rekening" placeholder="nmr_rekening" value="<?php echo $nmr_rekening; ?>" required />
                                    </div>
                                </div>



                            </div>



                            <!-- ============================ -->



                            <!-- 

                            <div class="form-group">
                                <label for="kode">Kode <?php //echo form_error('kode') 
                                                        ?></label>
                                <textarea class="form-control" rows="3" name="kode" id="kode" placeholder="Kode"><?php //echo $kode; 
                                                                                                                    ?></textarea>
                            </div> -->
                            <!-- <div class="form-group">
                                <label for="dari">Dari <?php //echo form_error('dari') 
                                                        ?></label>
                                <textarea class="form-control" rows="3" name="dari" id="dari" placeholder="Dari"><?php //echo $dari; 
                                                                                                                    ?></textarea>
                            </div> -->
                            <!-- <div class="form-group">
                                <label for="uraian">Uraian <?php //echo form_error('uraian') 
                                                            ?></label>
                                <textarea class="form-control" rows="3" name="uraian" id="uraian" placeholder="Uraian"><?php //echo $uraian; 
                                                                                                                        ?></textarea>
                            </div> -->
                            <!-- <div class="form-group">
                                <label for="double">Nominal <?php //echo form_error('nominal') 
                                                            ?></label>
                                <input type="text" class="form-control" name="nominal" id="nominal" placeholder="Nominal" value="<?php //echo $nominal; 
                                                                                                                                    ?>" />
                            </div> -->
                            <!-- <div class="form-group">
                                <label for="bank">Bank <?php //echo form_error('bank') 
                                                        ?></label>
                                <textarea class="form-control" rows="3" name="bank" id="bank" placeholder="Bank"><?php //echo $bank; 
                                                                                                                    ?></textarea>
                            </div> -->
                            <!-- <div class="form-group">
                                <label for="nmr_rekening">Nmr Rekening <?php //echo form_error('nmr_rekening') 
                                                                        ?></label>
                                <textarea class="form-control" rows="3" name="nmr_rekening" id="nmr_rekening" placeholder="Nmr Rekening"><?php //echo $nmr_rekening; 
                                                                                                                                            ?></textarea>
                            </div>
                             -->

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-5"></div>
                                    <div class="col-2">
                                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                        <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                                        <a href="<?php echo site_url('tbl_uang_muka_didepan') ?>" class="btn btn-default">Cancel</a>

                                    </div>
                                    <div class="col-5"></div>

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
            "scrollY": 900,
            "scrollX": true
        });
    });
</script>