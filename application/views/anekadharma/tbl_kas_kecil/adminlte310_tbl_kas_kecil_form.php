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
                                    <div class="col-12" text-align="center"> <strong>INPUT DATA KAS KECIL</strong></div>
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
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-3">
                                        <label for="date">Tanggal <?php echo form_error('tanggal') ?></label>
                                        
                                        <div class="input-group date" id="tanggal" name="tanggal" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#tgl_po" id="tgl_po" name="tanggal" required />
                                        <div class="input-group-append" data-target="#tgl_po" data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>

                                        </div>

                                    </div>
                                    </div>
                                    <div class="col-3">
                                        <label for="unit">Unit <?php echo form_error('unit') ?></label>
                                        <!-- <textarea class="form-control" rows="3" name="unit" id="unit" placeholder="Unit"><?php //echo $unit; ?></textarea> -->


                                        <select name="unit" id="unit" class="form-control select2" style="width: 100%; height: 40px;" required>
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
                                </div>
                            </div>
                            <!-- <div class="form-group">
                                <label for="unit">Unit <?php //echo form_error('unit') 
                                                        ?></label>
                                <textarea class="form-control" rows="3" name="unit" id="unit" placeholder="Unit"><?php //echo $unit; 
                                                                                                                    ?></textarea>
                            </div> -->
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-3">
                                        <label for="keterangan">Keterangan <?php echo form_error('keterangan') ?></label>
                                        <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"><?php echo $keterangan; ?></textarea>
                                    </div>
                                    <div class="col-3">
                                        <label for="double">Debet <?php echo form_error('debet') ?></label>
                                        <input type="text" class="form-control" name="debet" id="debet" placeholder="Debet" value="<?php echo $debet; ?>" />
                                    </div>
                                    <div class="col-3">
                                        <label for="double">Kredit <?php echo form_error('kredit') ?></label>
                                        <input type="text" class="form-control" name="kredit" id="kredit" placeholder="Kredit" value="<?php echo $kredit; ?>" />
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="form-group">
                                <label for="double">Debet <?php //echo form_error('debet') ?></label>
                                <input type="text" class="form-control" name="debet" id="debet" placeholder="Debet" value="<?php //echo $debet; ?>" />
                            </div>
                            <div class="form-group">
                                <label for="double">Kredit <?php //echo form_error('kredit') ?></label>
                                <input type="text" class="form-control" name="kredit" id="kredit" placeholder="Kredit" value="<?php //echo $kredit; ?>" />
                            </div> -->
                            <!-- <div class="form-group">
                                <label for="double">Saldo <?php //echo form_error('saldo') 
                                                            ?></label>
                                <input type="text" class="form-control" name="saldo" id="saldo" placeholder="Saldo" value="<?php //echo $saldo; 
                                                                                                                            ?>" />
                            </div>
                            <div class="form-group">
                                <label for="int">Id Usr <?php //echo form_error('id_usr') 
                                                        ?></label>
                                <input type="text" class="form-control" name="id_usr" id="id_usr" placeholder="Id Usr" value="<?php //echo $id_usr; 
                                                                                                                                ?>" />
                            </div> -->
                            <input type="hidden" name="id" value="<?php echo $id; ?>" />
                            <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                            <a href="<?php echo site_url('tbl_kas_kecil') ?>" class="btn btn-default">Cancel</a>
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