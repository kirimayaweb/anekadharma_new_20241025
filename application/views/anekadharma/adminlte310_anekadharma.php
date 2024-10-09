<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ANEKA DHARMA</title>


  <!-- DATATABLE -->
  <!-- Google Font: Source Sans Pro -->
  <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"> -->
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/AdminLTE310/plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/AdminLTE310/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/AdminLTE310/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <!-- <link rel="stylesheet" href="<?php echo base_url() ?>assets/AdminLTE310/plugins/datatables-buttons/css/buttons.bootstrap4.min.css"> -->
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
  <!-- Google Font: Source Sans Pro -->
  <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"> -->
  <!-- Font Awesome -->
  <!-- <link rel="stylesheet" href="<?php echo base_url() ?>assets/AdminLTE310/plugins/fontawesome-free/css/all.min.css"> -->
  <!-- daterange picker -->
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/AdminLTE310/plugins/daterangepicker/daterangepicker.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <!-- <link rel="stylesheet" href="<?php echo base_url() ?>assets/AdminLTE310/plugins/icheck-bootstrap/icheck-bootstrap.min.css"> -->
  <!-- Bootstrap Color Picker -->
  <!-- <link rel="stylesheet" href="<?php echo base_url() ?>assets/AdminLTE310/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css"> -->
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/AdminLTE310/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/AdminLTE310/plugins/select2/css/select2.min.css">
  <!-- <link rel="stylesheet" href="<?php echo base_url() ?>assets/AdminLTE310/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css"> -->
  <!-- Bootstrap4 Duallistbox -->
  <!-- <link rel="stylesheet" href="<?php echo base_url() ?>assets/AdminLTE310/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css"> -->
  <!-- BS Stepper -->
  <!-- <link rel="stylesheet" href="<?php echo base_url() ?>assets/AdminLTE310/plugins/bs-stepper/css/bs-stepper.min.css"> -->
  <!-- dropzonejs -->
  <!-- <link rel="stylesheet" href="<?php echo base_url() ?>assets/AdminLTE310/plugins/dropzone/min/dropzone.min.css"> -->
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/AdminLTE310/dist/css/adminlte.min.css">
  <!-- END OFF SELECT2 && DATEPICKER -->






</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- <div class="container"> -->
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item" style="font-size:0.7vw">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <!-- <a href="<?php echo base_url() ?>assets/AdminLTE310/index3.html" class="nav-link">Home</a> -->
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <!-- <a href="#" class="nav-link">Contact</a> -->
        </li>
      </ul>

      <i style=color:red;font-size:20px;>SEMESTER:

        <?php

        if (isset($_SESSION['semester_selected']) or isset($_SESSION['thn_selected'])) {
          $tahun_selected = $_SESSION['thn_selected'];
          if (isset($_SESSION['semester_selected'])) {
            $semester_selected = $_SESSION['semester_selected'];
          } else {
            $date_sekarang = date('Y-m-d H:i:s');
            $variabel_date_awal_selected = date('Y-m-d', strtotime('+0 month', strtotime($date_sekarang)));
            $tahun_selected = date('Y', strtotime('+0 month', strtotime($variabel_date_awal_selected)));
            $data_month = date('m', strtotime('+0 month', strtotime($variabel_date_awal_selected)));
            if (($data_month < 3)) {
              $semester_selected = 2;
              $tahun_selected = date('Y', strtotime('-1 year', strtotime($variabel_date_awal_selected)));
            } elseif (($data_month > 8)) {
              $semester_selected = 2;
              $tahun_selected = date('Y', strtotime('+0 month', strtotime($variabel_date_awal_selected)));
            } else {
              $semester_selected = 1;
              $tahun_selected = date('Y', strtotime('+0 month', strtotime($variabel_date_awal_selected)));
            }
          }
        } else {
          $date_sekarang = date('Y-m-d H:i:s');
          $variabel_date_awal_selected = date('Y-m-d', strtotime('+0 month', strtotime($date_sekarang)));
          $tahun_selected = date('Y', strtotime('+0 month', strtotime($variabel_date_awal_selected)));
          $data_month = date('m', strtotime('+0 month', strtotime($variabel_date_awal_selected)));
          if (($data_month < 3)) {
            $semester_selected = 2;
            $tahun_selected = date('Y', strtotime('-1 year', strtotime($variabel_date_awal_selected)));
          } elseif (($data_month > 8)) {
            $semester_selected = 2;
            $tahun_selected = date('Y', strtotime('+0 month', strtotime($variabel_date_awal_selected)));
          } else {
            $semester_selected = 1;
            $tahun_selected = date('Y', strtotime('+0 month', strtotime($variabel_date_awal_selected)));
          }
        }
        echo  "<strong>" . $semester_selected . "</strong>";

        ?>
        <!-- <i class="right fas fa-angle-left"></i> -->
      </i>




      <div class="collapse navbar-collapse order-3" id="navbarCollapse">
        <!-- Left navbar links -->

        <!-- </div> -->

        <?php
        if (($this->session->userdata('sess_id_user_level') == 99) or ($this->session->userdata('sess_id_user_level') == 1)  or ($this->session->userdata('sess_id_user_level') == 2)) {
        ?>

          <!-- CONFIG -->
          <ul class="navbar-nav">
            <li class="nav-item dropdown">
              <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">CONFIG </a>
              <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                <li>
                  <a href="<?php echo base_url() ?>index.php/Tbl_user" class="dropdown-item">Account User [DATA USER]</a>
                </li>
              </ul>
            </li>
          </ul>

          <!-- LAPORAN -->
          <ul class="navbar-nav">
            <li class="nav-item dropdown">
              <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Laporan</a>
              <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                <li><a href="<?php echo base_url() ?>index.php/Rekap_per_sales" class="dropdown-item">Rekap Per Sales</a></li>
                <li><a href="<?php echo base_url() ?>index.php/Rekap_per_semester" class="dropdown-item">Rekap Per Semester</a></li>
                <li><a href="#" class="dropdown-item">Group Sales</a></li>
              </ul>
            </li>
          </ul>

        <?php

        } else {

        ?>

          <!-- Left navbar links -->
          <ul class="navbar-nav">
            <li class="nav-item dropdown">
              <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Laporan</a>
              <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                <li><a href="<?php echo base_url() ?>index.php/Rekap_per_sales" class="dropdown-item">Rekap Per Sales</a></li>
                <li><a href="<?php echo base_url() ?>index.php/Rekap_per_semester" class="dropdown-item">Rekap Per Semester</a></li>
                <li><a href="#" class="dropdown-item">Group Sales</a></li>
              </ul>
            </li>
          </ul>


        <?php
        }
        ?>


        <ul class="navbar-nav">
          <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">DATA MAPEL</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
              <li><a href="<?php echo base_url() ?>index.php/Tbl_produk_mapel_referensi" class="dropdown-item">DATA MAPEL</a></li>
            </ul>
          </li>
        </ul>

        <ul class="navbar-nav">
          <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">COVER</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
              <li><a href="<?php echo base_url() ?>index.php/sys_cover" class="dropdown-item">COVER</a></li>
            </ul>
          </li>
        </ul>

        <ul class="navbar-nav">
          <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">UBAH PASSWORD</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
              <li><a href="<?php echo base_url() ?>index.php/Dashboard/ubah_password" class="dropdown-item">UBAH PASSWORD</a></li>
            </ul>
          </li>
        </ul>

        <ul class="navbar-nav">
          <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Logout</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
              <li><a href="<?php echo base_url() ?>index.php/masuk/logout" class="dropdown-item">Log out</a></li>
            </ul>
          </li>
        </ul>


        <?php
        if ($this->session->userdata('sess_email_user') == "iwanesia.id@gmail.com") {
        ?>

          <ul class="navbar-nav">
            <li class="nav-item dropdown">
              <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">REFRESH DATA</a>
              <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                <li><a href="<?php echo base_url() ?>index.php/tbl_sales/riwayat_per_sales3view/731f5119dd3f11ebab780242ac120004" class="dropdown-item">Riwayat Versi 2024 july</a></li>

                <!-- <li><a href="<?php //echo base_url() 
                                  ?>index.php/Trans_pesan_jual_retur_pivot/Refresh_data_sales/" class="dropdown-item">REFRESH DATA PIVOT</a></li> -->

                <li><a href="<?php echo base_url() ?>index.php/Trans_pesan_jual_retur_pivot/Refresh_data_sales_ALL/" class="dropdown-item">REFRESH DATA PIVOT ALL SALES</a></li>
              </ul>
            </li>
          </ul>


        <?php
        }
        ?>



      </div>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <li class="nav-item" style="font-size:0.7vw">
          <a class="nav-link" data-widget="navbar-search" href="#" role="button">
            <i class="fas fa-search"></i>
          </a>
          <div class="navbar-search-block">
            <form class="form-inline">
              <div class="input-group input-group-sm">
                <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                  <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                  </button>
                  <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
            </form>
          </div>
        </li>

        <!-- Messages Dropdown Menu -->


      </ul>
      <!-- </div> -->
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <!-- <a href="<?php echo base_url() ?>assets/AdminLTE310/index3.html" class="brand-link"> -->
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
            <a href="#" class="d-block"><?php
                                        print_r($this->session->userdata('sess_username'));
                                        print_r("<br/>");
                                        print_r("[");
                                        print_r($this->session->userdata('sess_email_user'));
                                        print_r("]");
                                        ?></a>
          </div>
        </div>


        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

            <!-- Add icons to the links using the .nav-icon class
                 with font-awesome or any other icon font library -->
            <?php

            // print_r($this->session->userdata('sess_username'));

            // if($cek = $this->session->userdata('company')=="admin")

            //ADMIN

            if ($this->session->userdata('sess_id_user_level') == 99) {
            ?>
              <li class="nav-item" style="font-size:0.7vw">
                <a href="<?php echo base_url(); ?>index.php/Dashboard" class="nav-link">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p style="font-size:0.7vw">
                    Dashboard
                  </p>
                </a>
              </li>




              <hr>


              <li class="nav-item" style="font-size:0.7vw">
                <a href="<?php echo base_url(); ?>index.php/Tbl_user" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>DATA USER</p>
                </a>
              </li>




            <?php
            } elseif ($this->session->userdata('sess_id_user_level') == 1 or $this->session->userdata('sess_id_user_level') == 2) {
            ?>


              <li class="nav-item" style="font-size:0.7vw">
                <a href="<?php echo base_url(); ?>index.php/Dashboard" class="nav-link">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p style="font-size:0.7vw">
                    Dashboard
                  </p>
                </a>
              </li>


              <hr>
              <?php
              // $get_id = $this->session->userdata('sess_iduser');

              // $sql = "select * from tbl_user where id_users=$get_id";
              // $user = $this->db->query($sql)->row_array();

              // if ($user['status_tagihan'] == 1) {
              ?>

              <!-- <li class="nav-item" style="font-size:0.7vw">
                  <a href="<?php //echo base_url() ?>index.php/Tagihan" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>TAGIHAN</p>
                  </a>
                </li>
                <hr> -->

              <?php
              // } 
              ?>





              <li class="nav-item" style="font-size:0.7vw">
                <a href="<?php echo base_url(); ?>index.php/Tbl_user" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>DATA USER</p>
                </a>
              </li>




            <?php
              //MANAGER
            } elseif ($this->session->userdata('sess_id_user_level') == 2 or $this->session->userdata('sess_id_user_level') == 7) {

            ?>


              <li class="nav-item" style="font-size:0.7vw">
                <a href="<?php echo base_url(); ?>index.php/Dashboard" class="nav-link">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p style="font-size:0.7vw">
                    Dashboard
                  </p>
                </a>
              </li>




            <?php
              //USER
            } elseif ($this->session->userdata('sess_id_user_level') == 6) { //======== USER ==============
            ?>


              <li class="nav-item" style="font-size:0.7vw">
                <a href="<?php echo base_url(); ?>index.php/Dashboard" class="nav-link">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p style="font-size:0.7vw">
                    Dashboard
                  </p>
                </a>
              </li>


            <?php
              //NOT LOGIN
            } else {
              header("location:" . base_url());
            }

            ?>








            <li class="nav-item" style="font-size:0.7vw">
              <a href="<?php echo base_url(); ?>index.php/Dashboard/ubah_password" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>UBAH PASSWORD</p>
              </a>
            </li>



            <li class="nav-item" style="font-size:0.7vw">
              <a href="<?php echo base_url(); ?>index.php/masuk/logout" class="nav-link">
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

    <!-- DATA TABLES -->

    <?php
    echo $contents;
    ?>

    <!-- END OFF DATATABLES -->


    <!-- /.content-wrapper -->
    <footer class="main-footer">
      <div class="float-right d-none d-sm-block">
        <b>Version</b> 1.0.1 [beta]
      </div>
      <strong>Copyright &copy; 2024 <a href="#">ANEKA DHARMA</a>.</strong> All rights reserved.
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->



  <!-- DATETABLES -->


  <!-- jQuery -->
  <!-- <script src="<?php echo base_url() ?>assets/AdminLTE310/plugins/jquery/jquery.min.js"></script> -->

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
  <!-- <script src="<?php echo base_url() ?>assets/AdminLTE310/dist/js/demo.js"></script> -->
  <!-- Page specific script -->
  <!-- END OFF DATATABLES -->


  <!-- fixed column -->
  <script src="https://cdn.datatables.net/fixedcolumns/4.2.1/js/dataTables.fixedColumns.min.js"></script>
  <!-- end of fixed column -->


  <!-- SELECT2 & DATEPICKER -->
  <!-- jQuery -->
  <!-- <script src="<?php echo base_url() ?>assets/AdminLTE310/plugins/jquery/jquery.min.js"></script> -->
  <!-- Bootstrap 4 -->
  <!-- <script src="<?php echo base_url() ?>assets/AdminLTE310/plugins/bootstrap/js/bootstrap.bundle.min.js"></script> -->
  <!-- Select2 -->
  <script src="<?php echo base_url() ?>assets/AdminLTE310/plugins/select2/js/select2.full.min.js"></script>
  <!-- Bootstrap4 Duallistbox -->
  <!-- <script src="<?php echo base_url() ?>assets/AdminLTE310/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script> -->
  <!-- InputMask -->
  <script src="<?php echo base_url() ?>assets/AdminLTE310/plugins/moment/moment.min.js"></script>
  <script src="<?php echo base_url() ?>assets/AdminLTE310/plugins/inputmask/jquery.inputmask.min.js"></script>
  <!-- date-range-picker -->
  <script src="<?php echo base_url() ?>assets/AdminLTE310/plugins/daterangepicker/daterangepicker.js"></script>
  <!-- bootstrap color picker -->
  <!-- <script src="<?php echo base_url() ?>assets/AdminLTE310/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script> -->
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="<?php echo base_url() ?>assets/AdminLTE310/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
  <!-- Bootstrap Switch -->
  <!-- <script src="<?php echo base_url() ?>assets/AdminLTE310/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script> -->
  <!-- BS-Stepper -->
  <!-- <script src="<?php echo base_url() ?>assets/AdminLTE310/plugins/bs-stepper/js/bs-stepper.min.js"></script> -->
  <!-- dropzonejs -->
  <!-- <script src="<?php echo base_url() ?>assets/AdminLTE310/plugins/dropzone/min/dropzone.min.js"></script> -->
  <!-- AdminLTE App -->
  <script src="<?php echo base_url() ?>assets/AdminLTE310/dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <!-- <script src="<?php echo base_url() ?>assets/AdminLTE310/dist/js/demo.js"></script> -->
  <!-- Page specific script -->
  <!-- END OFF SELECT2 & DATEPICKER -->

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

    $('#tgl_po').datetimepicker({
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

    //   //Bootstrap Duallistbox
    //   $('.duallistbox').bootstrapDualListbox()

    //   //Colorpicker
    //   $('.my-colorpicker1').colorpicker()
    //   //color picker with addon
    //   $('.my-colorpicker2').colorpicker()

    //   $('.my-colorpicker2').on('colorpickerChange', function (event) {
    //     $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
    //   })

    //   $("input[data-bootstrap-switch]").each(function () {
    //     $(this).bootstrapSwitch('state', $(this).prop('checked'));
    //   })

    // })
    // // BS-Stepper Init
    // document.addEventListener('DOMContentLoaded', function () {
    //   window.stepper = new Stepper(document.querySelector('.bs-stepper'))
    // })

    // // DropzoneJS Demo Code Start
    // Dropzone.autoDiscover = false

    // // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
    // var previewNode = document.querySelector("#template")
    // previewNode.id = ""
    // var previewTemplate = previewNode.parentNode.innerHTML
    // previewNode.parentNode.removeChild(previewNode)

    // var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
    //   url: "/target-url", // Set the url
    //   thumbnailWidth: 80,
    //   thumbnailHeight: 80,
    //   parallelUploads: 20,
    //   previewTemplate: previewTemplate,
    //   autoQueue: false, // Make sure the files aren't queued until manually added
    //   previewsContainer: "#previews", // Define the container to display the previews
    //   clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
    // })


    // DropzoneJS Demo Code End
  </script>
  <!-- END OFF SELECT2 && DATEPICKER -->





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
      var table = $('#exampleFreeze').DataTable({
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