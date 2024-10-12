<div class="content-wrapper">



    <section class="content">



        <div class="box box-warning box-solid">

            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-12">
                                <form action="<?php //echo $action; ?>" method="post">

                                    <div class="col-8 card-title">
                                        SETTING ADMIN
                                    </div>
                                    <div class="col-2 card-title">

                                        
                                    </div>
                                    <!-- <div class="col-md-2  card-title"></div> -->
                                    <div class="col-2  card-title">

                                    </div>

                                </form>
                            </div>
                        </div>

                    </div>
             
                </div>

            </div>

        </div>


        <div class="col-md-12">
            <div class="card card-primary">

                    <div class="card card-primary">
                        <!-- <div class="card-header"> -->
                        <!-- <div class="row"> -->
                        <div class="card-body">
                           
                           <table id="dttable1" class="display nowrap" style="width:100%">
                                <tr>
                                    <th>No</th>
                                    <th>Action</th>
                                    
                                    <th>Email</th>
                                    <th>Level</th>
                                     <th>Nickname</th>
                                    <th>No Hp</th>
                                    <!--<th>Id User Level</th>
                                    <th>Is Aktif</th> -->

                                </tr><?php
                                        foreach ($tbl_user_data as $tbl_user) {
                                            if ($tbl_user->email == "iwanesia.id@gmail.com" or $tbl_user->email == "iwanesia.manager@gmail.com") {
                                            } else {
                                        ?>
                                        <tr>
                                            <td width="10px"><?php echo ++$start ?></td>
                                            <td style="text-align:center" width="200px">
                                                <?php
                                                if($tbl_user->status_tagihan==1){
                                                    echo anchor(site_url('tbl_user/setting_admin_tagihan/' . $tbl_user->id_users), '<i class="fa fa-pencil-square-o" aria-hidden="true">NON AKTIFKAN TAGIHAN</i>', 'class="btn btn-success btn-sm"');
                                                    echo "<br/>";
                                                    echo "<strong>Tagihan Sedang Aktif</strong>";
                                                }else{
                                                    echo anchor(site_url('tbl_user/setting_admin_tagihan/' . $tbl_user->id_users), '<i class="fa fa-pencil-square-o" aria-hidden="true">AKTIFKAN TAGIHAN</i>', 'class="btn btn-danger btn-sm"');
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $tbl_user->email ?></td>
                                            <td><?php echo $tbl_user->full_name ?></td>                                           
                                            <td><?php echo $tbl_user->nickname ?></td>
                                            <td><?php echo $tbl_user->no_hp ?></td>

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

