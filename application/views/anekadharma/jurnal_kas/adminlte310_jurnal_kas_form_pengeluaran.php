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
                                    <div class="col-12" text-align="center"> <strong>Pengeluaran Jurnal Kas</strong></div>
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
                                    <div class="col-2">
                                        <label for="date">Tanggal <?php echo form_error('tanggal') ?></label>

                                        <?php

                                        if ($this->input->post('tanggal', TRUE)) {
                                            $get_tanggal=date("d-m-Y", strtotime($tanggal));
                                        } else {
                                            $get_tanggal = date("Y-m-d H:i:s");
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
                                
                                    <div class="col-2">
                                        <label for="bukti">Bukti <?php echo form_error('bukti') ?></label>
                                        <input type="text" class="form-control" rows="3" name="bukti" id="bukti" placeholder="bukti" value="<?php echo $bukti; ?>">
                                    </div>
                                
                                    <div class="col-2">
                                        <label for="pl">PL <?php echo form_error('pl') ?></label>
                                        <input type="text" class="form-control" rows="3" name="pl" id="pl" placeholder="PL" value="<?php echo $pl; ?>" >
                                    </div>
                                
                                    <div class="col-3">
                                        <label for="kredit">kredit <?php echo form_error('kredit') ?></label>
                                        <input type="text" class="form-control" name="kredit" id="kredit" placeholder="kredit" value="<?php echo $kredit; ?>" style="font-size:1.5vw;font-weight: bold;text-align:right;color:black;" min="1" max="9999999999999" ; required/>

                                    </div>
                                    <div class="col-3">
                                        <label for="kode_rekening">Kode rekening <?php echo form_error('kode_rekening') ?></label>
                                        <input type="text" class="form-control" rows="3" name="kode_rekening" id="kode_rekening" placeholder="kode_rekening" value="<?php echo $kode_rekening; ?>">
                                    </div>
                                </div>
                            </div>
                          
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-2">
                                    </div>
                                    <div class="col-10">
                                        <label for="keterangan">Keterangan <?php echo form_error('keterangan') ?></label>
                                        <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"><?php echo $keterangan; ?></textarea>
                                    </div>
                                </div>
                            </div>
                           
                            <div class="form-group">

                                <div class="row">

                                    <div class="col-3">
                                    </div>

                                    <div class="col-3">

                                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                        <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                                        <a href="<?php echo site_url('jurnal_kas') ?>" class="btn btn-default">Cancel</a>

                                    </div>

                                    <div class="col-6">
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
            "scrollY": 900,
            "scrollX": true
        });
    });
</script>