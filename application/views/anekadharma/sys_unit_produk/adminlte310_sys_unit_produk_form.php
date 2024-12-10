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


        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="box box-warning box-solid">


                <div class="card card-primary">
                    <div class="card-header">
                        <div class="row">
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="row">
                                    <div class="col-12" text-align="center"> <strong>TAMBAH PRODUK : <?php echo $nama_unit; ?> </strong></div>
                                </div>


                            </div>
                            <div class="col-6">

                            </div>



                        </div>




                    </div>
                    <!-- <br /> -->



                    <div class="card-body">

                        <form action="<?php echo $action; ?>" method="post">
                            <!-- <div class="form-group">
                                <label for="kode_unit">Kode Unit <?php //echo form_error('kode_unit') 
                                                                    ?></label>
                                <textarea class="form-control" rows="3" name="kode_unit" id="kode_unit" placeholder="Kode Unit"><?php //echo $kode_unit; 
                                                                                                                                ?></textarea>
                            </div> -->
                            <div class="form-group">
                                <div class="row">

                                    <div class="col-4">
                                        <label for="keterangan">Tanggal <?php echo form_error('tgl_transaksi') ?></label>
                                        <div class="input-group date" id="tgl_transaksi" name="tgl_transaksi" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input" data-target="#tgl_transaksi" id="tgl_transaksi" name="tgl_transaksi" required />
                                            <div class="input-group-append" data-target="#tgl_transaksi" data-toggle="datetimepicker">
                                                <div class="input-group-text">
                                                    <i class="fa fa-calendar"></i>
                                                </div>

                                            </div>

                                        </div>
                                    </div>



                                    <div class="col-6">
                                        <label for="keterangan">Nama Barang <?php echo form_error('nama_barang') ?></label>
                                        <input class="form-control" rows="3" name="nama_barang" id="nama_barang" placeholder="nama_barang">
                                    </div>



                                </div>
                            </div>


                            <div class="form-group">
                                <div class="row">
                                    <div class="col-4">
                                        <label for="keterangan">Jumlah Produksi <?php echo form_error('jumlah_produksi') ?></label>
                                        <input class="form-control uang" rows="3" name="jumlah_produksi" id="jumlah_produksi" placeholder="jumlah_produksi">
                                    </div>
                                    <div class="col-4">
                                        <label for="keterangan">Satuan <?php echo form_error('satuan') ?></label>
                                        <input class="form-control" rows="3" name="satuan" id="satuan" placeholder="satuan">

                                    </div>
                                    <div class="col-4">
                                        <label for="keterangan">Harga Satuan <?php echo form_error('harga_satuan') ?></label>
                                        <input class="form-control uang" rows="3" name="harga_satuan" id="harga_satuan" placeholder="harga_satuan">

                                    </div>
                                </div>
                            </div>





<!--  -->



                            <input type="hidden" name="id" value="<?php echo $id; ?>" />
                            <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                            <a href="<?php echo site_url('sys_unit/detail_unit/' . $uuid_unit) ?>" class="btn btn-default">Cancel</a>
                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>

            </div>
        </div>
        <div class="col-md-1"></div>
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