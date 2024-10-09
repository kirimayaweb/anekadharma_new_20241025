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
                                            Variabel Perhitungan
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

                                foreach ($Data_var_pajak as $list_data) {

                                ?>
                                    <form action="Sys_pajak/update_action_by_id/ <?php echo $list_data->id; 
                                                    ?>" method="post">

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-4" text-align="Right"><label for="double"><?php echo $list_data->varaibel; ?> </label></div>
                                                <div class="col-4">
                                                    <input type="text" class="form-control" name="nominal" id="nominal" placeholder="Nominal" value="<?php echo $list_data->nominal; ?>" />
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