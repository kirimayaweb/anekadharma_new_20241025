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
                                    <div class="col-12" text-align="center"> <strong>INPUT KODE AKUN</strong></div>
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
                                <label for="kode_akun">Kode Akun <?php echo form_error('kode_akun') ?></label>
                                <!-- <textarea class="form-control" rows="3" name="kode_akun" id="kode_akun" placeholder="Kode Akun"><?php //echo $kode_akun; ?></textarea> -->
                            
                                <input type="text" class="form-control" name="kode_akun" id="kode_akun" placeholder="Kode Akun" value="<?php echo $kode_akun; ?>">

                            
                            </div>
                            <div class="form-group">
                                <label for="nama_akun">Nama Akun <?php echo form_error('nama_akun') ?></label>
                                <!-- <textarea class="form-control" rows="3" name="nama_akun" id="nama_akun" placeholder="Nama Akun"><?php //echo $nama_akun; ?></textarea> -->

                                <input type="text" class="form-control" name="nama_akun" id="nama_akun" placeholder="Nama Akun" value="<?php echo $nama_akun; ?>">

                            </div>
                            <div class="form-group">
                                <label for="group">Group <?php echo form_error('group') ?></label>
                                <!-- <textarea class="form-control" rows="3" name="group" id="group" placeholder="Group"><?php echo $group; ?></textarea> -->

                                <input type="text" class="form-control" name="group" id="group" placeholder="Group" value="<?php echo $group; ?>">

                            </div>
                            <input type="hidden" name="id" value="<?php echo $id; ?>" />
                            <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                            <a href="<?php echo site_url('sys_kode_akun') ?>" class="btn btn-default">Cancel</a>
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