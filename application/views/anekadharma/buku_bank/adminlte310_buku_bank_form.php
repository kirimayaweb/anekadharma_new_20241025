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
                                    <div class="col-12" text-align="center"> <strong>INPUT TRANSAKSI BANK</strong></div>
                                </div>


                            </div>
                            <div class="col-6">

                            </div>



                        </div>




                    </div>
                    <br />



                    <div class="card-body">
                        <div class="row">
                            <div class="col-2"></div>
                            <div class="col-7">
                                <form action="<?php echo $action; ?>" method="post">



                                    <table class='table table-bordered'>

                                        <div class="form-group">
                                            <label for="datetime">Tanggal <?php echo form_error('tgl_po') ?></label>
                                            <?php
                                            if ($tanggal) {
                                                $date_po_X = date("d-m-Y", strtotime($tanggal));
                                            } else {
                                                $date_po_X = date("d-m-Y");
                                            }
                                            ?>

                                            <div class="col-5">
                                                <div class="input-group date" id="tgl_po" name="tgl_po" data-target-input="nearest">
                                                    <input type="text" class="form-control datetimepicker-input" data-target="#tgl_po" id="tgl_po" name="tgl_po" value="<?php echo $date_po_X; ?>" required />
                                                    <div class="input-group-append" data-target="#tgl_po" data-toggle="datetimepicker">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>

                                                    </div>

                                                </div>

                                            </div>


                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-5">
                                                    <label for="varchar">Bank <?php echo form_error('bank') ?></label>
                                                    <input type="text" class="form-control" name="bank" id="bank" placeholder="Bank" value="<?php echo $bank; ?>" />
                                                </div>
                                                <div class="col-5">
                                                    <label for="varchar">Norek <?php echo form_error('norek') ?></label>
                                                    <input type="text" class="form-control" name="norek" id="norek" placeholder="Norek" value="<?php echo $norek; ?>" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">




                                                <div class="col-4">
                                                    <label for="int">Kode <?php echo form_error('kode') ?></label>
                                                    <!-- <input type="text" class="form-control" name="kode" id="kode" placeholder="Kode" value="<?php //echo $kode; 
                                                                                                                                                    ?>" /> -->

                                                    <select name="kode" id="kode" class="form-control select2" style="width: 100%; height: 40px;" required>

                                                        <?php

                                                        if ($kode) {
                                                            // Get Nama akun dari kode akun

                                                            $sql = "SELECT * FROM `sys_kode_akun` WHERE `kode_akun`='$kode'";
                                                            $Get_nama_akun = $this->db->query($sql)->row()->nama_akun

                                                        ?>
                                                            <option value="<?php echo $kode; ?>"><?php echo $kode . " ==> " . $Get_nama_akun; ?></option>
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <option value="">Pilih Kode Akun</option>
                                                        <?php
                                                        }
                                                        ?>


                                                        <?php

                                                        $sql = "select * from sys_kode_akun order by kode_akun ASC";


                                                        foreach ($this->db->query($sql)->result() as $m) {
                                                            // foreach ($data_produk as $m) {
                                                            echo "<option value='$m->kode_akun' ";
                                                            echo ">  " . strtoupper($m->kode_akun)  . " ==> " . strtoupper($m->nama_akun)  . "</option>";
                                                        }
                                                        ?>
                                                    </select>





                                                </div>



                                                <div class="col-8">
                                                    <label for="varchar">Keterangan <?php echo form_error('keterangan') ?></label>
                                                    <input type="text" class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan" value="<?php echo $keterangan; ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-5">
                                                    <label for="varchar">Debet <?php echo form_error('debet') ?></label>
                                                    <input type="text" class="form-control" name="debet" id="debet" placeholder="Debet" value="<?php echo $debet; ?>" />
                                                </div>
                                                <div class="col-5">
                                                    <label for="varchar">Kredit <?php echo form_error('kredit') ?></label>
                                                    <input type="text" class="form-control" name="kredit" id="kredit" placeholder="Kredit" value="<?php echo $kredit; ?>" />
                                                </div>
                                            </div>
                                        </div>


                                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                        <tr>
                                            <td colspan='2' align="center">
                                                <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                                                <a href="<?php echo site_url('bukubank') ?>" class="btn btn-default">Cancel</a>
                                            </td>
                                        </tr>

                                    </table>
                                </form>
                            </div>
                            <div class="col-5"></div>
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