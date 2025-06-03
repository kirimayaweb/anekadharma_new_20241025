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
                                    <div class="col-12" text-align="center"> Input Data Neraca Saldo Tahun Lalu </strong></div>
                                </div>


                            </div>







                        </div>
                        <div class="row">
                            <div class="col-6">
                                <?php //echo anchor(site_url('tbl_pembelian/create'), 'Input Pembelian (Belanja Perusahaan)', 'class="btn btn-danger"'); 
                                ?>
                            </div>
                            <div class="col-4">

                            </div>
                            <div class="col-2">
                                <?php //echo anchor(site_url('tbl_pembelian/excel'), 'Cetak ke Excel', 'class="btn btn-success"'); 
                                ?>
                            </div>



                        </div>



                    </div>
                    <br />



                    <div class="card-body">

                        <form action="<?php echo $action; ?>" method="post">
                            <div class="row">


                                <div class="col-2"></div>

                                <div class="col-6">
                                    <label for="kode_pl">Nominal <?php echo $debet_kredit . " , Kode Akun: " .  $kode_akun  . " , Bulan: " .  $bulan_proses . " , Tahun: " .  $tahun_proses; ?> </label>
                                    <input type="text" class="form-control" rows="1" name="nominal" id="nominal" placeholder="Nominal" value="" required>
                                </div>

                                <div class="col-2"></div>

                            </div>

                            <div class="row">

                                <div class="col-2"></div>
                                
                                <div class="col-4">

                                    <div class="modal-footer justify-content-between">
                                        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button> -->
                                        <a href="<?php echo site_url('neraca_saldo/') ?>" class="btn btn-success">Batal</a>
                                        <!-- <button type="button" class="btn btn-primary">Simpan</button> -->
                                        <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                                    </div>
                                </div>

                                <div class="col-2"></div>

                            </div>

                        </form>
                        <hr>


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