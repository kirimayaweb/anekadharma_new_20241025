<div class="content-wrapper">



    <section class="content">






        <div class="col-md-12">
            <!-- <div class="card card-primary"> -->
            <div class="card card-primary">
                <div class="card-header">
                    <div class="row">
                        <div class="col-4" align="left"> DATA USER 
                            <?php 
                            
                            $sess_id_user_level_active = $this->session->userdata('sess_id_user_level');


                            if ($sess_id_user_level_active == "1" or $sess_id_user_level_active == "2") {
                                echo anchor(site_url('tbl_user/create'), '<i class="fa fa-wpforms" aria-hidden="true"></i> Tambah Data User Baru', 'class="btn btn-danger btn-sm"'); 
                            }
                            
                            
                            
                            
                            ?>
                        </div>
                        <div class="col-8" align="right">
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary">
                            <!-- <div class="card-header"> -->
                            <!-- <div class="row"> -->
                            <div class="card-body">

                                <!-- <table id="dttable1" class="display nowrap" style="width:100%"> -->
                                <table id="example" class="table table-bordered table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Action</th>
                                            <th>Level</th>
                                            <th>Full Name</th>
                                            <th>No. Hp</th>
                                            <th>Email</th>
                                            <th>Is Aktif</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $start = 0;
                                        foreach ($data_user as $list_data) {
                                            if ($list_data->email == "iwanesia.id@gmail.com" or $list_data->email == "iwanesia.manager@gmail.com") {
                                            } elseif (($list_data->id_user_level == 99) and  ($this->session->userdata('id_user_level') <> 99)) {
                                            } elseif (($list_data->id_user_level == 1) and  ($this->session->userdata('id_user_level') == 1) and ($list_data->email <> $this->session->userdata('email'))) {
                                            } else {
                                        ?>

                                                <tr>
                                                    <td width="10px"><?php echo ++$start ?></td>
                                                    <td style="text-align:center" width="200px">
                                                        <?php
                                                        echo anchor(site_url('Tbl_user/update/' . $list_data->id_users), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH DATA</i>', 'class="btn btn-warning btn-sm"');
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        print_r($this->Auto_load_model->get_level_name($list_data->id_user_level)->nama_level);
                                                        ?>
                                                    </td>
                                                    <td><?php echo $list_data->full_name ?></td>
                                                    <td><?php echo $list_data->no_hp ?></td>
                                                    <td><?php echo $list_data->email ?></td>
                                                    <td><?php echo $list_data->is_aktif ?></td>
                                                </tr>

                                        <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- </div> -->
                            <!-- </div> -->
                        </div>
                    </div>
                </div>
            </div>




            <!-- </div> -->
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