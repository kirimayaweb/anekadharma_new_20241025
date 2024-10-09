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
                        <h3 class="card-title">UBAH PASSWORD</h3>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo $action; ?>" method="post">
                            <?php
                            if (isset($_SESSION['pesan_berhasil_ubah_password'])) {
                                print_r($_SESSION['pesan_berhasil_ubah_password']);
                                unset($_SESSION['pesan_berhasil_ubah_password']);
                            }
                            ?>
                            <div class="row">
                                <!-- <div class="col-sm-1"></div> -->
                                <div class="col-sm-2">
                                    username:
                                    <input type="text" class="form-control" name="username" id="username" placeholder="Username" value="<?php $username; ?>" />
                                </div>

                                <div class="col-sm-2">
                                    Password:
                                    <input type="password" class="form-control" name="password" id="password" placeholder="" value="" />
                                    <br>
                                    <input type="checkbox" onclick="myFunction()">Tampilkan Password
                                </div>

                                <!-- <div class="col-sm-2">
                                    No. HP:
                                    <input type="text" class="form-control" name="no_hp" id="no_hp" placeholder="Nomor HP" value="<?php //$no_hp; ?>" />
                                </div> -->

                                <div class="col-sm-2"><br /> <button type="submit" class="btn btn-danger"><i class="fa fa-floppy-o"></i> <?php echo $button ?></button></div>
                            </div>


                        </form>

                        <form action="<?php echo $action_no_hp; ?>" method="post">
                            <?php
                            // if (isset($_SESSION['pesan_berhasil_ubah_password'])) {
                            //     print_r($_SESSION['pesan_berhasil_ubah_password']);
                            //     unset($_SESSION['pesan_berhasil_ubah_password']);
                            // }
                            ?>
                            <div class="row">
                                <!-- <div class="col-sm-1"></div> -->
                                <div class="col-sm-2">
                                    <!-- username:
                                    <input type="text" class="form-control" name="username" id="username" placeholder="Username" value="<?php $username; ?>" /> -->
                                </div>

                                <!-- <div class="col-sm-2">
                                    Password:
                                    <input type="password" class="form-control" name="password" id="password" placeholder="" value="" />
                                    <br>
                                    <input type="checkbox" onclick="myFunction()">Tampilkan Password
                                </div> -->

                                <div class="col-sm-2">
                                    No. HP:
                                    <input type="text" class="form-control" name="no_hp" id="no_hp" placeholder="<?php $no_hp; ?>" value="<?php $no_hp; ?>" />
                                </div>

                                <div class="col-sm-2"><br /> <button type="submit" class="btn btn-danger"><i class="fa fa-floppy-o"></i> <?php echo $button_no_hp ?></button></div>
                            </div>


                        </form>
                    </div>

                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

                <!-- iCheck -->

                <!-- /.card -->

                <!-- Bootstrap Switch -->

                <!-- /.card -->
            </div>

        </div>
    </section>
</div>


<script>
    function myFunction() {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>