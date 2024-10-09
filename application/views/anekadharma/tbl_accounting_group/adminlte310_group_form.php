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
                <div class="card card-warning">
                    <div class="card-header">
                        <div class="row">
                        </div>
                        <div class="row">
                            <div class="col-4">
                            </div>
                            <div class="col-4">
                                <div class="row">
                                    <div class="col-12" text-align="center"> <strong>
                                            Input Group Transaksi
                                        </strong></div>
                                </div>
                            </div>
                            <div class="col-4">
                            </div>
                        </div>



                    </div>
                    <!-- <br /> -->

                    <div class="row">
                        <div class="col-2"></div>
                        <div class="col-8">
                            <div class="card-body">


                                <form action="<?php echo $action; ?>" method="post">





                                    <div class="form-group">

                                        <div class="row">
                                            <div class="col-12">
                                                <label for="nmrsj">Group </label>
                                                <input type="text" class="form-control" rows="3" name="nama_group" id="nama_group" placeholder="Nama Group" required>
                                            </div>


                                        </div>

                                    </div>



                                    <div class="form-group">

                                        <div class="row">
                                            <div class="col-12">
                                                <label for="nmrfakturkwitansi">Keterangan</label>
                                                <input type="text" class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="keterangan">
                                            </div>
                                        </div>
                                    </div>



                                    <br />

                                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                    <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                                    <a href="<?php echo site_url('Tbl_accounting_group') ?>" class="btn btn-default">Cancel</a>
                                </form>


                            </div>
                        </div>
                    </div>
                    <div class="col-2"></div>
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
            "scrollY": 500,
            "scrollX": true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#example9').DataTable({
            "scrollY": 500,
            "scrollX": true
        });
    });
</script>