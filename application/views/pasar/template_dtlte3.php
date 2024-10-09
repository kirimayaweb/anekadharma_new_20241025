<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-RETRIBUSI</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/pasarlte/plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/pasarlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/pasarlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/pasarlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/pasarlte/dist/css/adminlte.min.css">


    <script>
        window.LogRocket && window.LogRocket.init('wcwttj/eretribusi');
        window.LogRocket.identify("ok user");
    </script>



</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <!-- <a href="<?php //echo base_url() 
                                    ?>assets/pasarlte/index3.html" class="nav-link">Home</a> -->
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <!-- <a href="#" class="nav-link">Contact</a> -->
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">

            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="<?php echo base_url() ?>assets/TEMPLATE/PASARLTE310/index3.html" class="brand-link">
                <img src="<?php echo base_url() ?>assets/TEMPLATE/PASARLTE310/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">Ret. Pasar</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="<?php echo base_url() ?>assets/TEMPLATE/PASARLTE310/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">
                            <p>
                                <?php tampil($username); ?>
                                <br />
                                <small><?php tampil($company); ?></small>
                            </p>
                        </a>
                    </div>
                </div>


                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
                 with font-awesome or any other icon font library -->



















                        <li class="nav-item">
                            <a href="<?php echo base_url(); ?>index.php/tbl_identitas_pengguna/identitas" class="nav-link active">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                        </li>


                        <?php

                        if ($company == 'administrator') {
                            $leveldb = 'admin';
                        } elseif ($company == 'admin') {
                            $leveldb = 'admin';
                        } elseif ($company == 'opd') {
                            // $leveldb='opd';
                            if ($jabatanopd == 'user') {
                                $leveldb = 'opd';
                            } elseif ($jabatanopd == 'kadis') {
                                $leveldb = 'kadis';
                            }
                        } elseif ($company == 'cs') {
                            $leveldb = 'cs';
                        } elseif ($company == 'adminretribusi') {
                            $leveldb = 'adminretribusi';
                        } elseif ($company == 'adminpasar') {
                            $leveldb = 'adminpasar';
                        } elseif ($company == 'pasar') {
                            $leveldb = 'pasar';
                        } elseif ($company == 'retribusi') {
                            $leveldb = 'retribusi';
                        } elseif ($company == 'tagihan') {
                            $leveldb = 'tagihan';
                        } elseif ($company == 'pedagang') {
                            $leveldb = 'pedagang';
                        } elseif ($company == 'lurahpasar') {
                            $leveldb = 'lurahpasar';
                        } else {
                            header("location:" . base_url());
                        }




                        $menu = $this->db->get_where('perijinan_menu', array('is_parent' => 0, 'is_active' => 1, 'level' => $leveldb));
                        foreach ($menu->result() as $m) {
                            $submenu = $this->db->get_where('perijinan_menu', array('is_parent' => $m->id, 'is_active' => 1, 'level' => $leveldb));

                            echo "
            <li class='nav-item'>
              <a href=" . base_url($m->link)  . " class='nav-link'>
                <i class='nav-icon fas fa-tachometer-alt'></i>
                <p>"
                                . strtoupper($m->name) .
                                "<i class='right fas fa-angle-left'></i>
                </p>
              </a>
            </li>";


                            //  SUB MENU
                            // if ($submenu->num_rows() > 0) {
                            //   echo "<li class='treeview'>" . anchor('#', "<i class='$m->icon'></i>" . strtoupper($m->name) . ' <i class="fa fa-angle-left pull-right"></i>') . "<ul class='treeview-menu'>";
                            //   foreach ($submenu->result() as $s) {
                            //     echo "<li>" . anchor($s->link, "<i class='$s->icon'></i> <span>" . strtoupper($s->name)) . "</span></li>";
                            //   }
                            //   echo "</ul></li>";
                            // } else {
                            //   echo "<li>" . anchor($m->link, "<i class='$m->icon'></i> <span>" . strtoupper($m->name)) . "</span></li>";
                            // }



                        }


                        ?>



                        <li class="nav-item">
                            <a href="<?php echo base_url(); ?>index.php/Logout" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>LOG OUT</p>
                            </a>
                        </li>





                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <!-- Content -->
            <?php
            echo $contents;
            ?>
            <!-- end of Content -->

        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong>Copyright &copy; E-RETRIBUSI.</strong> All rights reserved.
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="<?php echo base_url() ?>assets/pasarlte/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?php echo base_url() ?>assets/pasarlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables  & Plugins -->
    <script src="<?php echo base_url() ?>assets/pasarlte/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url() ?>assets/pasarlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?php echo base_url() ?>assets/pasarlte/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?php echo base_url() ?>assets/pasarlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="<?php echo base_url() ?>assets/pasarlte/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo base_url() ?>assets/pasarlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="<?php echo base_url() ?>assets/pasarlte/plugins/jszip/jszip.min.js"></script>
    <script src="<?php echo base_url() ?>assets/pasarlte/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="<?php echo base_url() ?>assets/pasarlte/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="<?php echo base_url() ?>assets/pasarlte/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="<?php echo base_url() ?>assets/pasarlte/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="<?php echo base_url() ?>assets/pasarlte/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url() ?>assets/pasarlte/dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="<?php echo base_url() ?>assets/pasarlte/dist/js/demo.js"></script>
    <!-- Page specific script -->
    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
</body>

</html>