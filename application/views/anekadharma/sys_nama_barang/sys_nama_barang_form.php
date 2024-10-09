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
                                <div class="row">
                                    <div class="col-12" text-align="center"> <strong>
                                            Input Data Barang
                                        </strong></div>

                                </div>


                            </div>
                            <div class="col-6">

                            </div>



                        </div>



                    </div>
                    <br />



                    <div class="card-body">


                        <div class="form-group">


                            <!--  -->

                            <!-- <h2 style="margin-top:0px">Sys_nama_barang <?php //echo $button ?></h2> -->
                            <form action="<?php echo $action; ?>" method="post">
                                <!-- <div class="form-group">
                                    <label for="varchar">Uuid Barang <?php //echo form_error('uuid_barang') ?></label>
                                    <input type="text" class="form-control" name="uuid_barang" id="uuid_barang" placeholder="Uuid Barang" value="<?php echo $uuid_barang; ?>" />
                                </div> -->
                                <div class="form-group">
                                    <label for="varchar">Kode Barang <?php echo form_error('kode_barang') ?></label>
                                    <input type="text" class="form-control" name="kode_barang" id="kode_barang" placeholder="Kode Barang" value="<?php echo $kode_barang; ?>" />
                                </div>
                                <div class="form-group">
                                    <label for="nama_barang">Nama Barang <?php echo form_error('nama_barang') ?></label>
                                    <textarea class="form-control" rows="3" name="nama_barang" id="nama_barang" placeholder="Nama Barang"><?php echo $nama_barang; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="satuan">Satuan <?php echo form_error('satuan') ?></label>
                                    <textarea class="form-control" rows="3" name="satuan" id="satuan" placeholder="Satuan"><?php echo $satuan; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="keterangan">Keterangan <?php echo form_error('keterangan') ?></label>
                                    <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"><?php echo $keterangan; ?></textarea>
                                </div>
                                <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                                <a href="<?php echo site_url('sys_nama_barang') ?>" class="btn btn-default">Cancel</a>
                            </form>


                            <!--  -->



                        </div>
                    </div>




                </div>
            </div>
        </div>