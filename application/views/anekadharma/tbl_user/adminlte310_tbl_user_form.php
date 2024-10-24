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

                            <form action="<?php //echo $action; 
                                            ?>" method="post">

                                <div class="col-8 card-title">

                                    <!-- <h3 class="card-title"> -->
                                    DATA USER
                                    <!-- </h3> -->
                                </div>
                                <div class="col-2 card-title">


                                </div>
                                <!-- <div class="col-md-2  card-title"></div> -->
                                <div class="col-2  card-title">

                                </div>

                            </form>
                        </div>

                    </div>
                    <br />

                    <div class="row">
                        <div class="col-6">
                            <form action="<?php echo $action; ?>" method="post">
                                <table class='table table-bordered>'>
                                    <tr>
                                        <td width='200'>NickName <?php echo form_error('full_name') ?></td>
                                        <td>
                                            <input type="text" class="form-control" name="full_name" id="full_name" placeholder="Full Name" value="<?php echo $full_name; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width='200'>Email (Username) <?php echo form_error('email') ?></td>
                                        <td>
                                            <input type="text" class="form-control" name="email" id="email" placeholder="Email" value="<?php echo $email; ?>" />
                                            Pastikan di isi dengan ....... @gmail.com / ....@xxxxxx.com




                                            <?php

                                            // print_r($this->session->flashdata('ada_email_sama'));

                                            if ($this->session->flashdata('ada_email_sama')) {
                                                echo "<br/>";
                                                echo "<strong style=color:red;> " . json_encode($this->session->flashdata('ada_email_sama')) . "</strong>";
                                            }
                                            unset($_SESSION['ada_email_sama']);
                                            ?>

                                        </td>

                                    </tr>
                                    <tr>
                                        <td width='200'>Password </td>
                                        <td>
                                            <input type="password" class="form-control" name="password" id="password" placeholder="Password" value="<?php //echo $password; ?>" />
                                            <?php echo "<strong>" . form_error('password') ."</strong>" ?>
                                            
                                            <?php 
                                            if($is_update=="TRUE"){
                                                echo "<strong style=color:red;> PASSWORD Dikosongkan , jika tidak ingin mengubah password </strong>";
                                            }
                                             ?>
                                        </td>
                                        
                                    </tr>
                                    <tr>
                                        <td width='200'>No. HP <?php echo form_error('no_hp') ?></td>
                                        <td>
                                            <input type="text" class="form-control" name="no_hp" id="no_hp" placeholder="No.HP" value="<?php echo $no_hp; ?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td width='200'>Level <?php echo form_error('id_user_level') ?></td>
                                        <td> <?php echo form_dropdown('id_user_level', array('2' => 'ADMIN', '3' => 'MANAGER', '4' => 'PRODUKSI', '5' => 'GUDANG', '7' => 'KASIR', '6' => 'SALES'), $id_user_level, array('class' => 'form-control')); ?></td>
                                    </tr>
                                    <tr>
                                        <td width='200'>Status Aktif <?php echo form_error('is_aktif') ?></td>
                                        <td><?php echo form_dropdown('is_aktif', array('y' => 'AKTIF', 'n' => 'TIDAK AKTIF'), $is_aktif, array('class' => 'form-control')); ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><input type="hidden" name="id_users" value="<?php echo $id_users; ?>" />
                                            <button type="submit" class="btn btn-danger"><i class="fa fa-floppy-o"></i> <?php echo $button ?></button>
                                            <a href="<?php echo site_url('tbl_user') ?>" class="btn btn-info"><i class="fa fa-sign-out"></i> Kembali</a>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>

                        <div class="col-6">
                            <!-- tabel data tingkat -->

                            <div class="card-body">

                                <!-- <table id="example1" class="table table-bordered table-striped"> -->
                                <!-- <table id="example" class="display nowrap" style="width:100%"> -->
                                <table id="exampletingkat" class="display nowrap" style="width:100%">
                                    <thead>

                                        <tr>
                                            <th style="text-align:center" width="10px">No</th>
                                            <!-- <th>Uuid Tingkat</th>
                                            <th>Tingkat System</th> -->

                                            <th>Tingkat terpilih yang Akif untuk user : </th>
                                            <th>Tingkat</th>



                                        </tr>
                                    </thead>

                                    <?php

                                    // print_r($tbl_sales_data);

                                    ?>


                                    <tbody>

                                        <?php
                                        $start = 0;
                                        foreach ($sys_tingkat_data as $sys_tingkat) {
                                        ?>
                                            <tr>
                                                <td><?php echo ++$start ?></td>
                                                <!-- <td><?php //echo $sys_tingkat->uuid_tingkat 
                                                            ?></td>
                                                    <td><?php //echo $sys_tingkat->tingkat_system 
                                                        ?></td> -->


                                                <td style="text-align:center" width="140px">
                                                    <?php
                                                    $this->db->where('id_users', $id_users);
                                                    $this->db->where('uuid_tingkat', $sys_tingkat->uuid_tingkat);
                                                    $get_tingkat_users = $this->db->get('tbl_tingkat_by_user');

                                                    if ($get_tingkat_users->num_rows() > 0) {

                                                        if ($get_tingkat_users->row()->status_tampil == "TAMPIL") {
                                                            echo anchor(site_url('tbl_user/update_tingkat_user/' . $id_users . '/' . $sys_tingkat->uuid_tingkat . '/TAMPIL'), '<i class="fa fa-pencil-square-o" aria-hidden="true">TAMPIL</i>', 'class="btn btn-success btn-sm"');
                                                            echo "<br/>";
                                                        } else {
                                                            echo anchor(site_url('tbl_user/update_tingkat_user/' . $id_users . '/' . $sys_tingkat->uuid_tingkat . '/TIDAKTAMPIL'), '<i class="fa fa-pencil-square-o" aria-hidden="true">TIDAK TAMPIL</i>', 'class="btn btn-danger btn-sm"');
                                                        }
                                                    } else {
                                                        echo anchor(site_url('tbl_user/update_tingkat_user/' . $id_users . '/' . $sys_tingkat->uuid_tingkat . '/TAMPIL'), '<i class="fa fa-pencil-square-o" aria-hidden="true">TAMPIL</i>', 'class="btn btn-success btn-sm"');
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php echo $sys_tingkat->tingkat ?></td>

                                            </tr>
                                        <?php
                                        }
                                        ?>

                                    </tbody>


                                </table>


                            </div>


                        </div>


                    </div>

                    <!-- <div class="row"> -->




                    <!-- </div> -->

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
        $('#exampletingkat').DataTable({
            "scrollY": 500,
            "scrollX": true
        });
    });
</script>