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
                                            Input Transaksi
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
                                            <div class="col-6">
                                                <label for="nmrsj">Tanggal </label>
                                                <!-- <input type="text" class="form-control" rows="3" name="date_transaksi" id="date_transaksi" placeholder="date_transaksi" required> -->

                                                <input type="text" class="form-control datetimepicker-input" data-target="#date_transaksi" id="date_transaksi" name="date_transaksi" required />
                                                <div class="input-group-append" data-target="#date_transaksi" data-toggle="datetimepicker">
                                                    <div class="input-group-text">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>

                                                </div>

                                            </div>

                                            <div class="col-6">
                                                <label for="nmrfakturkwitansi">Group</label>
                                                <!-- <input type="text" class="form-control" rows="3" name="uuid_group" id="uuid_group" placeholder="uuid_group"> -->

                                                <select name="uuid_group" id="uuid_group" class="form-control select2" style="width: 100%; height: 40px;" required>
                                                    <option value="">Pilih Group</option>
                                                    <?php

                                                    $sql = "select * from tbl_accounting_group  order by  nama_group ASC ";


                                                    foreach ($this->db->query($sql)->result() as $m) {
                                                        // foreach ($data_produk as $m) {
                                                        echo "<option value='$m->uuid_group' ";
                                                        echo ">  " . strtoupper($m->nama_group) . "</option>";
                                                    }
                                                    ?>
                                                </select>

                                            </div>


                                        </div>

                                    </div>



                                    <div class="form-group">

                                        <div class="row">
                                            <div class="col-6">
                                                <label for="nmrfakturkwitansi">Transaksi</label>
                                                <input type="text" class="form-control" rows="3" name="detail_transaksi" id="detail_transaksi" placeholder="detail_transaksi">
                                            </div>

                                            <div class="col-6">
                                                <label for="nmrfakturkwitansi">Nominal</label>
                                                <input type="text" class="form-control" rows="3" name="nominal_transaksi" id="nominal_transaksi" placeholder="nominal_transaksi">
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