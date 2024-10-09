<!doctype html>
<html>

<head>
    <title>VARIABEL PAJAK</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" />
    <style>
        body {
            padding: 15px;
        }
    </style>
</head>

<body>
    <!-- <h2 style="margin-top:0px">Sys_pajak <?php //echo $button 
                                                ?></h2> -->
    <!-- <form action="<?php //echo $action; 
                        ?>" method="post"> -->


    <div class="form-group">
        <div class="row">

            <?php

            foreach ($Data_var_pajak as $list_data) {

            ?>


                <div class="form-group">
                    <div class="row">
                        <div class="col-4">
                            <label for="double"><?php echo $list_data->varaibel; ?> </label>
                            <input type="text" class="form-control" name="nominal" id="nominal" placeholder="Nominal" value="<?php echo $list_data->nominal; ?>" />
                        </div>
                    </div>
                </div>


            <?php
            }
            ?>

        </div>
    </div>
    <!-- <div class="form-group">
        <label for="keterangan">Keterangan <?php //echo form_error('keterangan') 
                                            ?></label>
        <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"><?php //echo $keterangan; 
                                                                                                            ?></textarea>
    </div> -->

    <!-- <input type="hidden" name="id" value="<?php //echo $id; 
                                                ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php //echo $button 
                                                        ?></button>  -->
    <!-- <a href="<?php //echo site_url('sys_pajak') 
                    ?>" class="btn btn-default">Cancel</a> -->
    <!-- </form> -->

</body>

</html>