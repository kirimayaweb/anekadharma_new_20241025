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
                                            <input type="text" class="form-control" name="full_name" id="full_name" placeholder="Full Name" value="
                                            <?php
                                            if ($is_update == "TRUE") {
                                                echo $full_name;
                                            }
                                            ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width='200'>Email (Username) <?php echo form_error('email') ?></td>
                                        <td>
                                            <input type="text" class="form-control" name="email" id="email" placeholder="Email" value="<?php echo $email; ?>" />
                                            Pastikan di isi format email : [akun] @gmail.com / xxxxx @ xxxxxx.com




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
                                            <input type="password" class="form-control" name="password" id="password" placeholder="Password" value="<?php //echo $password; 
                                                                                                                                                    ?>" />
                                            <?php echo "<strong>" . form_error('password') . "</strong>" ?>

                                            <?php
                                            if ($is_update == "TRUE") {
                                                echo "<strong style=color:red;> PASSWORD Dikosongkan , jika tidak ingin mengubah password </strong>";
                                            }
                                            ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td width='200'>No. HP <?php echo form_error('no_hp') ?></td>
                                        <td>
                                            <input type="text" class="form-control" name="no_hp" id="no_hp" placeholder="No.HP" value="<?php
                                                                                                                                        if ($is_update == "TRUE") {
                                                                                                                                            echo $no_hp;
                                                                                                                                        }
                                                                                                                                        ?>" />
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

                            <div class="card card-primary">
                                <div class="card-header">

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="col-12 card-title">

                                                <!-- <h3 class="card-title"> -->
                                                MENU USER : Setting menu untuk user: <?php
                                                                                        if ($is_update == "TRUE") {
                                                                                            echo $full_name;
                                                                                        }
                                                                                        ?>
                                                <!-- </h3> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <!-- <div class="row"></div> -->
                                <div class="row">
                                    <div class="col-12">
                                        <table id="example" class="table table-bordered" style="width:100%">
                                            <!-- <table class="table table-bordered table-striped" id="mytable"> -->
                                            <thead>
                                                <tr>
                                                    <th width="10px">No</th>
                                                    <th>Nama Menu</th>
                                                    <!-- <th>Link</th> -->
                                                    <!-- <th width="30">Icon</th> -->
                                                    <th>Aktif</th>
                                                    <th>Parent</th>
                                                    <th>action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $start = 0;
                                                foreach ($menu_data as $menu) {
                                                    $active = $menu->is_active == 1 ? 'AKTIF' : 'TIDAK AKTIF';
                                                    $parent = $menu->is_parent > 1 ? 'MAINMENU' : 'SUBMENU'
                                                ?>
                                                    <tr>
                                                        <td><?php echo ++$start ?></td>
                                                        <td><?php echo $menu->name ?></td>
                                                        <!-- <td><?php //echo $menu->link 
                                                                    ?></td> -->
                                                        <!-- <td><i class='<?php //echo $menu->icon 
                                                                            ?>'></i></td> -->
                                                        <td><?php echo $active ?></td>
                                                        <td><?php echo $parent ?></td>
                                                        <td style="text-align:center" width="140px">
                                                            <?php

                                                            // cek di tbl_hak_akses apakah menu->id ada berdasarkan id_user
                                                            $id_user_active = $this->session->userdata('sess_iduser');

                                                            $this->db->where('id_menu', $menu->id);
                                                            $this->db->where('id_user',  $id_user_active);
                                                            $list_menu_hak_akses = $this->db->get('tbl_hak_akses');

                                                            if ($list_menu_hak_akses->num_rows() > 0) {
                                                                echo anchor(site_url('Tbl_user/update_menu_per_user/' . $id_user_active . '/' . $menu->id  .'/0'), '<i class="fa fa-eye">Tampil</i>', array('title' => 'edit', 'class' => 'btn btn-success btn-sm'));
                                                            } else {
                                                                echo anchor(site_url('Tbl_user/update_menu_per_user/' . $id_user_active .'/'. $menu->id .'/0'), '<i class="fa fa-eye">Tidak Tampil</i>', array('title' => 'edit', 'class' => 'btn btn-danger btn-sm'));
                                                            }


                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
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
        $('#example').DataTable({
            "scrollY": 300,
            "scrollX": true
        });
    });
</script>