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

                            <form action="<?php //echo $action; ?>" method="post">

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
                        <div class="col-2">
                        <?php echo anchor(site_url('tbl_user/create'), '<i class="fa fa-wpforms" aria-hidden="true"></i> Tambah Data', 'class="btn btn-danger btn-sm"'); ?>
                    </div>
                        <div class="col-2" align="right">
                        </div>

                    </div>

                    <!-- <div class="row"> -->

                    


                    <!-- </div> -->

                    <!-- /.card-body -->
                </div>

            </div>

        </div>


        <div class="col-md-12">
            <div class="card card-primary">

                    <div class="card card-primary">
                        <!-- <div class="card-header"> -->
                        <!-- <div class="row"> -->
                        <div class="card-body">
                           
                           <!-- <table id="dttable1" class="display nowrap" style="width:100%"> -->
                           <table id="exampleFreeze" class="display nowrap" style="width:100%">
                                <tr>
                                    <th>No</th>
                                    <th>Action</th>
                                    <!-- <th>Uuid Users</th> -->
                                    <!-- <th>Date Input</th> -->
                                    <th>Level</th>
                                    <th>Full Name</th>
                                    <th>No. Hp</th>
                                    <th>Email</th>
                                    <!-- <th>Password</th>
                                    <th>Images</th> -->
                                    
                                    <th>Is Aktif</th>

                                </tr><?php
                                        foreach ($tbl_user_data as $tbl_user) {
                                            if ($tbl_user->email == "iwanesia.id@gmail.com" or $tbl_user->email == "iwanesia.manager@gmail.com") {
                                            } elseif(($tbl_user->id_user_level==99) and  ($this->session->userdata('id_user_level')<>99) ){
                                            } elseif(($tbl_user->id_user_level==1) and  ($this->session->userdata('id_user_level')==1) and ($tbl_user->email <> $this->session->userdata('email')) ){
                                            
                                            }else{
                                            ?>

                                            <tr>
                                                <td width="10px"><?php echo ++$start ?></td>
                                                <td style="text-align:center" width="200px">
                                                    <?php
                                                    // echo anchor(site_url('tbl_user/read/' . $tbl_user->id_users), '<i class="fa fa-eye" aria-hidden="true"></i>', 'class="btn btn-danger btn-sm"');
                                                    // echo '  ';
                                                    echo anchor(site_url('tbl_user/update/' . $tbl_user->id_users), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH DATA</i>', 'class="btn btn-warning btn-sm"');
                                                    // echo '  ';
                                                    // echo anchor(site_url('tbl_user/delete/' . $tbl_user->id_users), '<i class="fa fa-trash-o" aria-hidden="true">HAPUS USER</i>', 'class="btn btn-danger btn-sm" Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
                                                    ?>
                                                </td>
                                                
                                                <!-- <td><?php //echo $tbl_user->uuid_users 
                                                            ?></td> -->
                                                <!-- <td><?php //echo $tbl_user->date_input ?></td> -->
                                                
                                                <td>
                                                    <?php 
                                                        // echo $tbl_user->id_user_level;
                                                        print_r($this->Auto_load_model->get_level_name($tbl_user->id_user_level)->nama_level);
                                                    ?>
                                                </td>

                                                <td><?php echo $tbl_user->full_name ?></td>
                                                <td><?php echo $tbl_user->no_hp ?></td>
                                                <td><?php echo $tbl_user->email ?></td>
                                                <!-- <td><?php //echo $tbl_user->password ?></td> -->
                                                <!-- <td><?php //echo $tbl_user->images ?></td> -->
                                                
                                                <td><?php echo $tbl_user->is_aktif ?></td>

                                            </tr>

                                        <?php
                                                }
                                            }
                                ?>
                            </table>
                        </div>

                        <!-- </div> -->
                        <!-- </div> -->
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

<script>
    $(document).ready(function() {
        $('#dttable1').DataTable({
            "scrollY": 500,
            "scrollX": true
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#dttable2').DataTable({
            "scrollY": 500,
            "scrollX": true
        });
    });
</script>

