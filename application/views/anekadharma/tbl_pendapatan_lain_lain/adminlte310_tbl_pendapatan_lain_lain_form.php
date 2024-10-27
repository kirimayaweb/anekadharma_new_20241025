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
                                    <div class="col-12" text-align="center"> <strong>INPUT PENDAPATAN LAIN-LAIN</strong></div>
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
                                    <div class="col-6">
                                        <label for="datetime">Tgl Transaksi <?php echo form_error('tgl_transaksi') ?></label>
                                        <!-- <input type="text" class="form-control" name="tgl_transaksi" id="tgl_transaksi" placeholder="Tgl Transaksi" value="<?php //echo $tgl_transaksi; 
                                                                                                                                                                ?>" /> -->

                                        <div class="input-group date" id="tgl_transaksi" name="tgl_transaksi" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input" data-target="#tgl_transaksi" id="tgl_transaksi" name="tgl_transaksi" required />
                                            <div class="input-group-append" data-target="#tgl_transaksi" data-toggle="datetimepicker">
                                                <div class="input-group-text">
                                                    <i class="fa fa-calendar"></i>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label for="kode">Kode <?php echo form_error('kode') ?></label>
                                        <input class="form-control" rows="3" name="kode" id="kode" placeholder="Kode"><?php echo $kode; ?>
                                    </div>
                                    <div class="col-2">
                                        <?php //echo anchor(site_url('Tbl_neraca_data/excel'), 'Cetak ke Excel', 'class="btn btn-success"'); 
                                        ?>
                                    </div>



                                </div>


                            </div>


                            <div class="form-group">


                                <div class="row">
                                    <div class="col-6">
                                        <label for="dari">Dari <?php echo form_error('dari') ?></label>
                                        <input class="form-control" rows="3" name="dari" id="dari" placeholder="Dari"><?php echo $dari; ?></input>
                                    </div>
                                    <div class="col-6">
                                        <label for="uraian">Uraian <?php echo form_error('uraian') ?></label>
                                        <input class="form-control" rows="3" name="uraian" id="uraian" placeholder="Uraian"><?php echo $uraian; ?></input>
                                    </div>

                                </div>

                            </div>



                            <div class="form-group">

                                <div class="row">
                                    <div class="col-4">
                                        <label for="double">Nominal <?php echo form_error('nominal') ?></label>
                                        <input type="text" class="form-control uang" name="nominal" id="nominal" placeholder="Nominal" value="<?php echo $nominal; ?>" />
                                    </div>
                                    <div class="col-4">
                                        <label for="bank">Bank <?php echo form_error('bank') ?></label>
                                        <input class="form-control" rows="3" name="bank" id="bank" placeholder="Bank"><?php echo $bank; ?></input>
                                    </div>
                                    <div class="col-4">
                                        <label for="nmr_rekening">Nmr Rekening <?php echo form_error('nmr_rekening') ?></label>
                                        <input class="form-control" rows="3" name="nmr_rekening" id="nmr_rekening" placeholder="Nmr Rekening"><?php echo $nmr_rekening; ?></input>
                                    </div>



                                </div>

                            </div>


                            <div class="row">
                                <div class="col-3">

                                </div>
                                <div class="col-6">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                    <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                                    <a href="<?php echo site_url('sys_kode_akun') ?>" class="btn btn-default">Cancel</a>
                                </div>
                                <div class="col-3">
                                    <?php //echo anchor(site_url('Tbl_neraca_data/excel'), 'Cetak ke Excel', 'class="btn btn-success"'); 
                                    ?>
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