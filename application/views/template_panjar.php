<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>PANJAR</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="<?php echo base_url() ?>assets_panjar/adminlte241/bower_components/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets_panjar/adminlte241/bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets_panjar/adminlte241/bower_components/Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets_panjar/adminlte241/bower_components/bootstrap-daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets_panjar/adminlte241/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets_panjar/adminlte241/plugins/iCheck/all.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets_panjar/adminlte241/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets_panjar/adminlte241/plugins/timepicker/bootstrap-timepicker.min.css">

    <link rel="stylesheet" href="<?php echo base_url() ?>assets_panjar/adminlte241/bower_components/select2/dist/css/select2.css">

    <link rel="stylesheet" href="<?php echo base_url() ?>assets_panjar/adminlte241/dist/css/AdminLTE.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets_panjar/adminlte241/dist/css/skins/_all-skins.min.css">

    <link rel="stylesheet" href="<?php echo base_url() ?>template/plugins/datatables/dataTables.bootstrap.css">

</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <header class="main-header">
            <a href="#" class="logo">
                <span class="logo-mini">panjar</span>
                <span class="logo-lg"><b>PANJAR</b></span>
            </a>
            <nav class="navbar navbar-static-top" role="navigation">
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">

                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="<?php echo base_url() ?>template/dist/img/BenderaIndonesiaNew.ico" class="user-image" alt="User Image">
                                <?php echo $username; ?>
                                <small>

                                </small>

                            </a>
                            <ul class="dropdown-menu">
                                <li class="user-header">
                                    <img src="<?php echo base_url() ?>template/dist/img/BenderaIndonesiaNew.ico" class="img-circle" alt="User Image">
                                    <p>
                                        <?php echo $username; ?>
                                        <small><?php

                                                if ($company == 'administrator') {
                                                    echo "Administrator";
                                                } elseif ($company == 'admin') {
                                                    echo "Admin";
                                                }
                                                ?>
                                    </p>
                                </li>

                                <li class="user-footer">
                                    <div class="pull-left">
                                        <?php
                                        echo anchor(site_url('users/update/' . $id), '<i class="fa fa-pencil-square-o">Profile</i>', array('title' => 'edit', 'class' => 'btn btn-block btn-danger btn-sm'));
                                        ?>
                                    </div>


                                    <div class="pull-left">
                                        <?php
                                        echo anchor(site_url('Panjar_db_backup/backup_all_db'), '<i class="fa fa-check-circle">Backup (tahun baru)</i>', array('title' => 'edit', 'class' => 'btn btn-block btn-success btn-sm'));
                                        ?>
                                    </div>



                                    <div class="pull-right">
                                        <?php
                                        echo anchor('Logout', 'Sign out', array('class' => 'btn btn-default btn-flat'));
                                        ?>

                                    </div>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </div>
            </nav>
        </header>
        <aside class="main-sidebar">
            <section class="sidebar">
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="<?php echo base_url() ?>template/dist/img/BenderaIndonesiaNew.ico" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p><?php echo $username; ?></p>
                        <a href=""><i class="fa fa-circle text-success"></i>
                            <?php
                            if ($company == 'administrator') {
                                echo "Administrator";
                            } elseif ($company == 'admin') {
                                echo "Admin";
                            }

                            ?>
                        </a>
                    </div>
                </div>
                <hr>

                <ul class="sidebar-menu">
                    <?php


                    if ($company == 'administrator') {
                        $leveldb = 'admin';
                    } elseif ($company == 'admin') {
                        $leveldb = 'admin';
                    }

                    ?>

                    <?php
                    $menu = $this->db->get_where('menu_racode', array('is_parent' => 0, 'is_active' => 1, 'level' => $leveldb));
                    foreach ($menu->result() as $m) {
                        $submenu = $this->db->get_where('menu_racode', array('is_parent' => $m->id, 'is_active' => 1, 'level' => $leveldb));
                        if ($submenu->num_rows() > 0) {
                            echo "<li class='treeview'>
                                    " . anchor('#',  "<i class='$m->icon'></i>" . strtoupper($m->name) . ' <i class="fa fa-angle-left pull-right"></i>') . "
                                        <ul class='treeview-menu'>";
                            foreach ($submenu->result() as $s) {
                                echo "<li>" . anchor($s->link, "<i class='$s->icon'></i> <span>" . strtoupper($s->name)) . "</span></li>";
                            }
                            echo "</ul>
                                    </li>";
                        } else {
                            echo "<li>" . anchor($m->link, "<i class='$m->icon'></i> <span>" . strtoupper($m->name)) . "</span></li>";
                        }
                    }
                    ?>

                </ul>
            </section>
        </aside>
        <div class="content-wrapper">
            <section class="content-header">
                <h1>
                    <div class='row' align="text-center">
                        <div class="col-sm-1">
                        </div>
                        <div class="col-sm-10" align="text-center">
                            <strong align="text-center">SISTEM INFORMASI UANG PANJAR SEKRETARIAT DPRD KAB. BANTUL</strong>
                        </div>
                        <div class="col-sm-1">
                        </div>
                    </div>
                </h1>
            </section>


            <?php
            echo $contents;
            ?>



        </div><!-- /.content-wrapper -->
        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>PANJAR</b> 2018
            </div>
            <strong>Copyright &copy; 2018 <a href="#">PANJAR</a>.</strong> All rights reserved.
        </footer>

        <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->

    <script src="<?php echo base_url() ?>assets_panjar/adminlte241/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo base_url() ?>assets_panjar/adminlte241/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url() ?>assets_panjar/adminlte241/bower_components/select2/dist/js/select2.full.min.js"></script>
    <script src="<?php echo base_url() ?>assets_panjar/adminlte241/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="<?php echo base_url() ?>assets_panjar/adminlte241/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="<?php echo base_url() ?>assets_panjar/adminlte241/plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <script src="<?php echo base_url() ?>assets_panjar/adminlte241/bower_components/moment/min/moment.min.js"></script>
    <script src="<?php echo base_url() ?>assets_panjar/adminlte241/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="<?php echo base_url() ?>assets_panjar/adminlte241/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo base_url() ?>assets_panjar/adminlte241/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
    <script src="<?php echo base_url() ?>assets_panjar/adminlte241/plugins/timepicker/bootstrap-timepicker.min.js"></script>
    <script src="<?php echo base_url() ?>assets_panjar/adminlte241/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <script src="<?php echo base_url() ?>assets_panjar/adminlte241/plugins/iCheck/icheck.min.js"></script>
    <script src="<?php echo base_url() ?>assets_panjar/adminlte241/bower_components/fastclick/lib/fastclick.js"></script>
    <script src="<?php echo base_url() ?>assets_panjar/adminlte241/dist/js/adminlte.min.js"></script>
    <script src="<?php echo base_url() ?>assets_panjar/adminlte241/dist/js/demo.js"></script>

    <script src="<?php echo base_url() ?>template/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url() ?>template/plugins/datatables/dataTables.bootstrap.min.js"></script>

    <script>
        $(function() {
            $("#example1").DataTable();
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false
            });
        });
    </script>




    <script>
        $(function() {
            $('.select2').select2()
            $('#datemask').inputmask('dd/mm/yyyy', {
                'placeholder': 'dd/mm/yyyy'
            })
            $('#datemask2').inputmask('mm/dd/yyyy', {
                'placeholder': 'mm/dd/yyyy'
            })
            $('[data-mask]').inputmask()
            $('#reservation').daterangepicker()
            $('#reservationtime').daterangepicker({
                timePicker: true,
                timePickerIncrement: 30,
                format: 'MM/DD/YYYY h:mm A'
            })
            $('#daterange-btn').daterangepicker({
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    startDate: moment().subtract(29, 'days'),
                    endDate: moment()
                },
                function(start, end) {
                    $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
                }
            )
            $('#datepicker').datepicker({
                autoclose: true
            })
            $('#datepicker1').datepicker({
                autoclose: true
            })
            $('#datepicker2').datepicker({
                autoclose: true
            })
            $('#tgl_panjar').datepicker({
                autoclose: true
            })
            $('#tgl_selesai').datepicker({
                autoclose: true
            })
            $('#tgl_awal').datepicker({
                autoclose: true
            })
            $('#tgl_akhir').datepicker({
                autoclose: true
            })
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            })
            $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
                checkboxClass: 'icheckbox_minimal-red',
                radioClass: 'iradio_minimal-red'
            })
            $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            })
            $('.my-colorpicker1').colorpicker()
            $('.my-colorpicker2').colorpicker()
            $('.timepicker').timepicker({
                showInputs: false
            })
        })
    </script>

</body>

</html>