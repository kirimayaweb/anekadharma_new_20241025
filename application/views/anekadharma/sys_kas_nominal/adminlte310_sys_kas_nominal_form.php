<?php
if ($this->session->userdata('sess_berhasil_simpan') == 1) {
    // print_r("sess_berhasil_simpan = 1");
    // $x_sess_berhasil_simpan=$this->session->userdata('sess_berhasil_simpan');
    // $x_kode_kas_nominal=$this->session->userdata('kode_kas_nominal');
    // $x_total_kas_nominal=nominal($this->session->userdata('total_kas_nominal'));
    
    
    // print_r("FORM");
    // print_r("<br/>");
    // print_r(nominal($this->session->userdata('sess_total_kas_nominal')));
    // print_r("<br/>");
    // print_r($items = strval(nominal($this->session->userdata('total_kas_nominal'))));

    // $x="TOTAL: " . $this->session->userdata('sess_total_kas_nominal');

    
?>
    <script>
        Swal.fire({
            title: "Berhasil Update Nominal Kas!",
            text: <?php 
            print_r($this->session->userdata('sess_total_kas_nominal')) ; 
            // echo "<br/>";
            // echo $_SESSION['sess_id_user_level'];
            ?>,
            icon: "success"
        });
    </script>
<?php

unset($_SESSION['sess_berhasil_simpan']);
unset($_SESSION['sess_total_kas_nominal']);


// print_r($_SESSION['sess_id_user_level']);
}
?>


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
                                            Nominal Kas
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
                            <div class="row">

                                <?php

                                foreach ($Sys_kas_nominal_data as $list_data) {
                                    $uuid_kas_nominal_selected=$list_data->uuid_kas_nominal;
                                ?>
                                    <form action="Sys_kas_nominal/update_action_by_id/<?php echo $list_data->uuid_kas_nominal; ?>" method="post">

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-4" text-align="right">
                                                    <label for="double" text-align="right"><?php echo $list_data->kode_kas_nominal; ?> </label>
                                                </div>

                                                <div class="col-4">
                                                    <!-- <input type="text" class="form-control" name="nominal" id="nominal" placeholder="Nominal" value="<?php //echo $list_data->total_kas_nominal; 
                                                                                                                                                            ?>" /> -->

                                                    <input type="text" class="form-control uang" onkeyup="sum();" name="total_kas_nominal" id="total_kas_nominal" placeholder="" value="<?php echo $list_data->total_kas_nominal; ?>" style="font-size:1.5vw;font-weight: bold;text-align:right;color:red;" ; />

                                                </div>
                                                <div class="col-4">
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </div>
                                        </div>

                                    </form>

                                <?php
                                }
                                ?>

                            </div>
                        </div>


                    </div>


                </div>

            </div>

        </div>
    </section>
</div>