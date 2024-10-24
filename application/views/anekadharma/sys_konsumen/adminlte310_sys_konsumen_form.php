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
                                    <div class="col-12" text-align="center"> <strong>INPUT KONSUMEN BARU</strong></div>
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
                                <label for="kode_konsumen">kode_konsumen <?php echo form_error('kode_konsumen') ?></label>
                                <textarea class="form-control" rows="3" name="kode_konsumen" id="kode_konsumen" placeholder="kode_konsumen"><?php echo $kode_konsumen; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="nama_konsumen">nama_konsumen <?php echo form_error('nama_konsumen') ?></label>
                                <textarea class="form-control" rows="3" name="nama_konsumen" id="nama_konsumen" placeholder="nama_konsumen"><?php echo $nama_konsumen; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="nmr_kontak_konsumen">nmr_kontak_konsumen <?php echo form_error('nmr_kontak_konsumen') ?></label>
                                <textarea class="form-control" rows="3" name="nmr_kontak_konsumen" id="nmr_kontak_konsumen" placeholder="nmr_kontak_konsumen"><?php echo $nmr_kontak_konsumen; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="alamat_konsumen">alamat_konsumen <?php echo form_error('alamat_konsumen') ?></label>
                                <textarea class="form-control" rows="3" name="alamat_konsumen" id="alamat_konsumen" placeholder="alamat_konsumen"><?php echo $alamat_konsumen; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="keterangan">keterangan <?php echo form_error('keterangan') ?></label>
                                <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"><?php echo $keterangan; ?></textarea>
                            </div>
                            <input type="hidden" name="id" value="<?php echo $id; ?>" />
                            <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                            <a href="<?php echo site_url('sys_unit') ?>" class="btn btn-default">Cancel</a>
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