<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ANEKA DHARMA</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/AdminLTE310/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/AdminLTE310/dist/css/adminlte.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/AdminLTE310/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/AdminLTE310/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/AdminLTE310/dist/css/adminlte.min.css">
  <!-- END OFF DATATABLES -->

  <!-- ANIMATION BUTTON -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/animatebutton/style.css">


  <!-- CHAIN COMBO -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <!-- END OF CHAIN COMBO -->


  <!-- fixed column -->
  <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.2.1/css/fixedColumns.bootstrap5.min.css">
  <!-- end of fixed column -->

  <!-- SELECT2 && DATEPICKER -->
  <!-- daterange picker -->
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/AdminLTE310/plugins/daterangepicker/daterangepicker.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/AdminLTE310/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/AdminLTE310/plugins/select2/css/select2.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/AdminLTE310/dist/css/adminlte.min.css">
  <!-- END OFF SELECT2 && DATEPICKER -->


  <!-- sweetalert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- //sweetalert -->




</head>




<body class="hold-transition sidebar-collapse layout-top-nav">
  <div class="wrapper">

    <!-- Navbar -->
    <!-- <nav class="main-header navbar navbar-expand-md navbar-light navbar-white"> -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <div class="container">
        <a href="#" class="navbar-brand">
          <img src="<?php echo base_url() ?>assets/AdminLTE310/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
          <span class="brand-text font-weight-light">ANEKA DHARMA</span>
        </a>

        <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse order-3" id="navbarCollapse">
          <!-- Left navbar links -->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>


            <?php
            $id_user_active = $this->session->userdata('sess_iduser');

            $sql_menu = "select * from menu where is_active='1' and is_parent=0";
            $main_menu = $this->db->query($sql_menu)->result();

            // print_r($main_menu);

            // print_r("<br/>");
            // print_r("<br/>");
            // print_r("<br/>");

            foreach ($main_menu as $menu) {

              //  cek di tbl_hak_akses : adakah main_menu = menu->id ?
              // jika ada : tampilkan main menu dan looping sub_menu yang ditampilkan

              $this->db->where('main_menu', $menu->id);
              $this->db->where('id_user',  $id_user_active);
              $list_menu_hak_akses = $this->db->get('tbl_hak_akses');

              if ($list_menu_hak_akses->num_rows() > 0) {

                // detail MAIN menu
                $this->db->where('id', $menu->id);
                $MAIN_menu = $this->db->get('menu')->row_array();

            ?>

                <li class="nav-item dropdown">
                  <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle"><?php echo $MAIN_menu['name']; ?></a>

                  <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">

                    <?php
                    foreach ($list_menu_hak_akses->result() as $menu_list) {

                      // detail menu
                      $this->db->where('id', $menu_list->id_menu);
                      $detail_menu = $this->db->get('menu')->row_array();
                    ?>

                      <li><a tabindex="-1" href="<?php echo base_url() ?>index.php<?php echo $detail_menu['link']; ?>" class="dropdown-item"><?php echo $detail_menu['name']; ?></a></li>


                    <?php
                    }

                    ?>




                  </ul>



                </li>

            <?php

              }
            }

            ?>



<!-- 
            <li class="nav-item dropdown">
              <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Accounting</a>
              <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                <li><a href="<?php //echo base_url() ?>index.php" class="dropdown-item"> </a></li>
                <li><a href="<?php //echo base_url() ?>index.php" class="dropdown-item"> </a></li>

                <li><a href="<?php //echo base_url() ?>index.php" class="dropdown-item"> </a></li>
                <li><a href="<?php //echo base_url() ?>index.php" class="dropdown-item"></a></li>

                <li><a href="<?php //echo base_url() ?>index.php" class="dropdown-item" target="_blank"></a></li>

                <li><a href="<?php //echo base_url() ?>index.php" class="dropdown-item"> </a></li>
                <li><a href="<?php //echo base_url() ?>index.php" class="dropdown-item"> </a></li>
                <li><a href="<?php //echo base_url() ?>index.php" class="dropdown-item"> </a></li>

                <li><a href="<?php //echo base_url() ?>index.php" class="dropdown-item"> </a></li>
                <li><a href="<?php //echo base_url() ?>index.php" class="dropdown-item"></a></li>

                <li class="dropdown-submenu dropdown-hover">
                  <a id="dropdownSubMenu2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">Setting</a>
                  <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
                    <li><a href="<?php //echo base_url() ?>index.php/Sys_kas_nominal" class="dropdown-item">Kas nominal (maksimal saldo kas) </a></li>

                    <li><a href="<?php //echo base_url() ?>index.php/Tbl_accounting_group" class="dropdown-item">Group Transaksi</a></li>
                    <li><a href="<?php //echo base_url() ?>index.php/Tbl_accounting_detail" class="dropdown-item">Detail Transaksi</a></li>
                </li>
              </ul>
            </li> -->


          <!-- </ul>
          </li> -->
<!-- 
          <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Laporan</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
              <li><a href="<?php //echo base_url() ?>index.php" class="dropdown-item"> </a></li>
              <li><a href="<?php //echo base_url() ?>index.php" class="dropdown-item"></a></li>
              <li><a href="<?php //echo base_url() ?>index.php" class="dropdown-item" target="_blank"></a></li>

            </ul>
          </li> -->

          <li class="nav-item">
            <a href="<?php echo base_url() ?>index.php/Anekadharmamasuk/logout" class="nav-link">LOGOUT</a>
          </li>


          </ul>


        </div>


      </div>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="#" class="brand-link">
        <img src="<?php echo base_url() ?>assets/AdminLTE310/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">ANEKA DHARMA</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="<?php echo base_url() ?>assets/AdminLTE310/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="#" class="d-block">
              <?php 
              
              $sess_id_user_level_active = $this->session->userdata('sess_id_user_level');

              $this->db->where('id_user_level', $sess_id_user_level_active);
              $get_tbl_user_level = $this->db->get('tbl_user_level');
              $data_get_tbl_user_level = $get_tbl_user_level->row_array();

              print_r($data_get_tbl_user_level['nama_level']);

              ?>
          </a>
          </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
          <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-sidebar">
                <i class="fas fa-search fa-fw"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
           

            <li class="nav-item menu-open">
              <a href="<?php echo base_url() ?>index.php/Anekadharmamasuk/logout" class="nav-link active">
                <i class="nav-icon fas fa-copy"></i>
                <p>
                  LOGOUT
                  <i class="fas fa-angle-left right"></i>
                  <span class="badge badge-info right">6</span>
                </p>
              </a>

            </li>



          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>




    <?php
    echo $contents;
    ?>

    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer">
      <!-- To the right -->
      <div class="float-right d-none d-sm-inline">
        <b>Version</b> 1.0.1 [beta]
      </div>
      <!-- Default to the left -->
      <strong>Copyright &copy; 2024 <a href="#">ANEKA DHARMA</a>.</strong> All rights reserved.

    </footer>
  </div>
  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->






  <!-- DATETABLES -->


  <!-- jQuery -->
  <!-- <script src="<?php //echo base_url() 
                    ?>assets/AdminLTE310/plugins/jquery/jquery.min.js"></script> -->

  <!-- JAQUERY CHAIN COMBOBOX -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
  <!-- END OF JAQUERY CHAIN COMBOBOX -->




  <!-- Bootstrap 4 -->
  <script src="<?php echo base_url() ?>assets/AdminLTE310/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- DataTables  & Plugins -->
  <script src="<?php echo base_url() ?>assets/AdminLTE310/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="<?php echo base_url() ?>assets/AdminLTE310/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="<?php echo base_url() ?>assets/AdminLTE310/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="<?php echo base_url() ?>assets/AdminLTE310/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
  <script src="<?php echo base_url() ?>assets/AdminLTE310/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
  <script src="<?php echo base_url() ?>assets/AdminLTE310/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
  <!-- <script src="<?php echo base_url() ?>assets/AdminLTE310/plugins/jszip/jszip.min.js"></script> -->

  <!-- PDF PRINT -->
  <script src="<?php echo base_url() ?>assets/AdminLTE310/plugins/pdfmake/pdfmake.min.js"></script>
  <script src="<?php echo base_url() ?>assets/AdminLTE310/plugins/pdfmake/vfs_fonts.js"></script>

  <script src="<?php echo base_url() ?>assets/AdminLTE310/plugins/datatables-buttons/js/buttons.html5.min.js"></script>

  <!-- PRINT BUTTON -->
  <script src="<?php echo base_url() ?>assets/AdminLTE310/plugins/datatables-buttons/js/buttons.print.min.js"></script>

  <!-- TAMPIL BUTTON ABOVE DATATABLES -->
  <script src="<?php echo base_url() ?>assets/AdminLTE310/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

  <!-- AdminLTE App -->
  <!-- <script src="<?php echo base_url() ?>assets/AdminLTE310/dist/js/adminlte.min.js"></script> -->
  <!-- AdminLTE for demo purposes -->
  <script src="<?php echo base_url() ?>assets/AdminLTE310/dist/js/demo.js"></script>
  <!-- Page specific script -->
  <!-- END OFF DATATABLES -->


  <!-- fixed column -->
  <script src="https://cdn.datatables.net/fixedcolumns/4.2.1/js/dataTables.fixedColumns.min.js"></script>
  <!-- end of fixed column -->


  <!-- SELECT2 & DATEPICKER -->
  <!-- Select2 -->
  <script src="<?php echo base_url() ?>assets/AdminLTE310/plugins/select2/js/select2.full.min.js"></script>
  <!-- InputMask -->
  <script src="<?php echo base_url() ?>assets/AdminLTE310/plugins/moment/moment.min.js"></script>
  <script src="<?php echo base_url() ?>assets/AdminLTE310/plugins/inputmask/jquery.inputmask.min.js"></script>
  <!-- date-range-picker -->
  <script src="<?php echo base_url() ?>assets/AdminLTE310/plugins/daterangepicker/daterangepicker.js"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="<?php echo base_url() ?>assets/AdminLTE310/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
  <!-- AdminLTE App -->
  <script src="<?php echo base_url() ?>assets/AdminLTE310/dist/js/adminlte.min.js"></script>

  <!-- ANIMATION BUTTON -->
  <script src="<?php echo base_url(); ?>/assets/animatebutton/script.js"></script>

  <!-- DATATABLES -->


  <script src="<?php echo base_url() ?>jquery/jQuery-Mask-Plugin-master/dist/jquery.mask.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {

      // Format mata uang.
      $('.uang').mask('000.000.000.000.000', {
        reverse: true
      });

    })
  </script>


  <script>
    $(function() {
      $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        // "width": 100 % ,
        // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        "buttons": ["print"]
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

    // $(function () {
    //   //Initialize Select2 Elements
    $('.select2').select2()

    // //   //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', {
      'placeholder': 'dd/mm/yyyy'
    })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', {
      'placeholder': 'mm/dd/yyyy'
    })
    //Money Euro
    $('[data-mask]').inputmask()

    //   //Date picker
    $('#reservationdate').datetimepicker({
      format: 'L'
    });

    $('#tgl_transaksi').datetimepicker({
      format: 'D-M-YYYY'
    });


    // aneka dharma
    $('#tgl_po').datetimepicker({
      format: 'D-M-YYYY'
    });

    $('#tgl_permohonan').datetimepicker({
      format: 'D-M-YYYY'
    });

    $('#tgl_jatuh_tempo').datetimepicker({
      format: 'D-M-YYYY'
    });

    $('#tgl_nomor_bkk').datetimepicker({
      format: 'D-M-YYYY'
    });
    $('#date_transaksi').datetimepicker({
      format: 'D-M-YYYY'
    });
    $('#tanggal_bayar_input').datetimepicker({
      format: 'D-M-YYYY'
    });



    $('#tgl_jual').datetimepicker({
      format: 'D-M-YYYY'
    });
    //   //Date and time picker
    $('#reservationdatetime').datetimepicker({
      icons: {
        time: 'far fa-clock'
      }
    });

    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({
      timePicker: true,
      timePickerIncrement: 30,
      locale: {
        format: 'MM/DD/YYYY hh:mm A'
      }
    })
    //Date range as a button
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
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )

    //Timepicker
    $('#timepicker').datetimepicker({
      format: 'LT'
    })

    // DropzoneJS Demo Code End
  </script>
  <!-- END OFF SELECT2 && DATEPICKER -->



  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
  <style type="text/css">
    div.dataTables_wrapper {
      width: 100%;
      margin: 0 auto;
    }
  </style>

  <script>
    // BOOTSTRAP 3
    // $(document).ready(function() {
    //     var table = $('#example').DataTable( {
    //         scrollY:        "300px",
    //         scrollX:        true,
    //         scrollCollapse: true,
    //         paging:         false,
    //         fixedColumns:   true
    //     } );
    // } );


    // BOOTSTRAP 5
    // $(document).ready(function() {
    //     var table = $('#example').DataTable( {
    //         scrollY:        "300px",
    //         scrollX:        true,
    //         scrollCollapse: true,
    //         paging:         true,
    //         leftColumns:2,
    //         fixedColumns:   true
    //     } );
    // } );

    $(document).ready(function() {
      var table = $('#tglSPOPFreeze').DataTable({
        scrollX: true,
        scrollY: "400px",
        scrollCollapse: true,
        paging: true,
        // columnDefs: [
        //     { orderable: false, targets: 0 },
        //      { orderable: false, targets: -1 }
        //  ],
        //  ordering: [[ 1, 'asc' ]],
        // colReorder: {
        //     fixedColumnsLeft: 1,
        //      fixedColumnsRight: 1
        // }
      });

      new $.fn.dataTable.FixedColumns(table, {
        leftColumns: 3,
        // rightColumns: 1
      });
    });

    $(document).ready(function() {
      var table = $('#tglSPOPFreeze1').DataTable({
        scrollX: true,
        scrollY: "400px",
        scrollCollapse: true,
        paging: true,
        // columnDefs: [
        //     { orderable: false, targets: 0 },
        //      { orderable: false, targets: -1 }
        //  ],
        //  ordering: [[ 1, 'asc' ]],
        // colReorder: {
        //     fixedColumnsLeft: 1,
        //      fixedColumnsRight: 1
        // }
      });

      new $.fn.dataTable.FixedColumns(table, {
        leftColumns: 3,
        // rightColumns: 1
      });
    });

    $(document).ready(function() {
      var table = $('#tglSPOPFreeze2').DataTable({
        scrollX: true,
        scrollY: "400px",
        scrollCollapse: true,
        paging: true,
        // columnDefs: [
        //     { orderable: false, targets: 0 },
        //      { orderable: false, targets: -1 }
        //  ],
        //  ordering: [[ 1, 'asc' ]],
        // colReorder: {
        //     fixedColumnsLeft: 1,
        //      fixedColumnsRight: 1
        // }
      });

      new $.fn.dataTable.FixedColumns(table, {
        leftColumns: 3,
        // rightColumns: 1
      });
    });

    $(document).ready(function() {
      var table = $('#exampleFreeze').DataTable({
        scrollX: true,
        scrollY: "400px",
        scrollCollapse: true,
        paging: true,
        // columnDefs: [
        //     { orderable: false, targets: 0 },
        //      { orderable: false, targets: -1 }
        //  ],
        //  ordering: [[ 1, 'asc' ]],
        // colReorder: {
        //     fixedColumnsLeft: 1,
        //      fixedColumnsRight: 1
        // }
      });

      new $.fn.dataTable.FixedColumns(table, {
        leftColumns: 3,
        // rightColumns: 1
      });
    });

    $(document).ready(function() {
      var table = $('#examplepenjualanlist').DataTable({
        scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: true,
        // columnDefs: [
        //     { orderable: false, targets: 0 },
        //      { orderable: false, targets: -1 }
        //  ],
        //  ordering: [[ 1, 'asc' ]],
        // colReorder: {
        //     fixedColumnsLeft: 1,
        //      fixedColumnsRight: 1
        // }
      });

      new $.fn.dataTable.FixedColumns(table, {
        leftColumns: 3,
        // rightColumns: 1
      });
    });






    $(document).ready(function() {
      var table = $('#exampleFreeze1').DataTable({
        scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: true,
        // columnDefs: [
        //     { orderable: false, targets: 0 },
        //      { orderable: false, targets: -1 }
        //  ],
        //  ordering: [[ 1, 'asc' ]],
        // colReorder: {
        //     fixedColumnsLeft: 1,
        //      fixedColumnsRight: 1
        // }
      });

      new $.fn.dataTable.FixedColumns(table, {
        leftColumns: 2,
        // rightColumns: 1
      });
    });


    $(document).ready(function() {
      var table = $('#exampleFreeze2').DataTable({
        scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: true,
        // columnDefs: [
        //     { orderable: false, targets: 0 },
        //      { orderable: false, targets: -1 }
        //  ],
        //  ordering: [[ 1, 'asc' ]],
        // colReorder: {
        //     fixedColumnsLeft: 1,
        //      fixedColumnsRight: 1
        // }
      });

      new $.fn.dataTable.FixedColumns(table, {
        leftColumns: 2,
        // rightColumns: 1
      });
    });


    $(document).ready(function() {
      var table = $('#exampleFreeze3').DataTable({
        scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: true,
        // columnDefs: [
        //     { orderable: false, targets: 0 },
        //      { orderable: false, targets: -1 }
        //  ],
        //  ordering: [[ 1, 'asc' ]],
        // colReorder: {
        //     fixedColumnsLeft: 1,
        //      fixedColumnsRight: 1
        // }
      });

      new $.fn.dataTable.FixedColumns(table, {
        leftColumns: 2,
        // rightColumns: 1
      });
    });


    $(document).ready(function() {
      var table = $('#exampleFreeze4').DataTable({
        scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: true,
        // columnDefs: [
        //     { orderable: false, targets: 0 },
        //      { orderable: false, targets: -1 }
        //  ],
        //  ordering: [[ 1, 'asc' ]],
        // colReorder: {
        //     fixedColumnsLeft: 1,
        //      fixedColumnsRight: 1
        // }
      });

      new $.fn.dataTable.FixedColumns(table, {
        leftColumns: 2,
        // rightColumns: 1
      });
    });


    $(document).ready(function() {
      var table = $('#exampleFreeze5').DataTable({
        scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: true,
        // columnDefs: [
        //     { orderable: false, targets: 0 },
        //      { orderable: false, targets: -1 }
        //  ],
        //  ordering: [[ 1, 'asc' ]],
        // colReorder: {
        //     fixedColumnsLeft: 1,
        //      fixedColumnsRight: 1
        // }
      });

      new $.fn.dataTable.FixedColumns(table, {
        leftColumns: 2,
        // rightColumns: 1
      });
    });


    $(document).ready(function() {
      var table = $('#exampleFreeze6').DataTable({
        scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: true,
        // columnDefs: [
        //     { orderable: false, targets: 0 },
        //      { orderable: false, targets: -1 }
        //  ],
        //  ordering: [[ 1, 'asc' ]],
        // colReorder: {
        //     fixedColumnsLeft: 1,
        //      fixedColumnsRight: 1
        // }
      });

      new $.fn.dataTable.FixedColumns(table, {
        leftColumns: 2,
        // rightColumns: 1
      });
    });


    $(document).ready(function() {
      var table = $('#exampleFreeze7').DataTable({
        scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: true,
        // columnDefs: [
        //     { orderable: false, targets: 0 },
        //      { orderable: false, targets: -1 }
        //  ],
        //  ordering: [[ 1, 'asc' ]],
        // colReorder: {
        //     fixedColumnsLeft: 1,
        //      fixedColumnsRight: 1
        // }
      });

      new $.fn.dataTable.FixedColumns(table, {
        leftColumns: 2,
        // rightColumns: 1
      });
    });


    $(document).ready(function() {
      var table = $('#exampleFreeze8').DataTable({
        scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: true,
        // columnDefs: [
        //     { orderable: false, targets: 0 },
        //      { orderable: false, targets: -1 }
        //  ],
        //  ordering: [[ 1, 'asc' ]],
        // colReorder: {
        //     fixedColumnsLeft: 1,
        //      fixedColumnsRight: 1
        // }
      });

      new $.fn.dataTable.FixedColumns(table, {
        leftColumns: 2,
        // rightColumns: 1
      });
    });


    $(document).ready(function() {
      var table = $('#exampleFreeze9').DataTable({
        scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: true,
        // columnDefs: [
        //     { orderable: false, targets: 0 },
        //      { orderable: false, targets: -1 }
        //  ],
        //  ordering: [[ 1, 'asc' ]],
        // colReorder: {
        //     fixedColumnsLeft: 1,
        //      fixedColumnsRight: 1
        // }
      });

      new $.fn.dataTable.FixedColumns(table, {
        leftColumns: 2,
        // rightColumns: 1
      });
    });


    $(document).ready(function() {
      var table = $('#exampleFreeze10').DataTable({
        scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: true,
        // columnDefs: [
        //     { orderable: false, targets: 0 },
        //      { orderable: false, targets: -1 }
        //  ],
        //  ordering: [[ 1, 'asc' ]],
        // colReorder: {
        //     fixedColumnsLeft: 1,
        //      fixedColumnsRight: 1
        // }
      });

      new $.fn.dataTable.FixedColumns(table, {
        leftColumns: 2,
        // rightColumns: 1
      });
    });
  </script>








</body>

</html>