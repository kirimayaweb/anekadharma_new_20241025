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
                        <h3 class="card-title">INPUT DATA GUDANG</h3>
                    </div>
                    <div class="card-body">


                        <form action="<?php echo $action; ?>" method="post">
                            <table class='table table-bordered>' <tr>




                                <tr>
                                    <td width='200'>Nama Gudang </td>
                                    <td> <input class="form-control" rows="3" name="nama_gudang" id="nama_gudang" placeholder="Nama Gudang"><?php echo $nama_gudang; ?></input>
                                        <?php echo form_error('nama_gudang') ?></td>
                                </tr>

                                <tr>
                                    <td width='200'>Alamat Gudang </td>
                                    <td> <textarea class="form-control" rows="3" name="alamat_gudang" id="alamat_gudang" placeholder="Alamat Gudang"><?php echo $alamat_gudang; ?></textarea>
                                        <?php echo form_error('alamat_gudang') ?></td>
                                </tr>

                                <tr>
                                    <td width='200'>Notelp Gudang </td>
                                    <td> <input class="form-control" rows="3" name="notelp_gudang" id="notelp_gudang" placeholder="Notelp Gudang"><?php echo $notelp_gudang; ?></input>
                                        <?php echo form_error('notelp_gudang') ?></td>
                                </tr>

                                <tr>
                                    <td width='200'>Keterangan </td>
                                    <td> <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"><?php echo $keterangan; ?></textarea>
                                        <?php echo form_error('keterangan') ?></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input type="hidden" name="id" value="<?php echo $id; ?>" />
                                        <button type="submit" class="btn btn-danger"><i class="fa fa-floppy-o"></i> <?php echo $button ?></button>
                                        <a href="<?php echo site_url('tbl_gudang') ?>" class="btn btn-info"><i class="fa fa-sign-out"></i> Kembali</a>
                                    </td>
                                </tr>


                            </table>
                        </form>

                    </div>

                    <!-- /.card-body -->
                </div>

            </div>

        </div>
    </section>

</div>