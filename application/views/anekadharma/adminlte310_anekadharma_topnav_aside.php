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

  <style>
    /* Top-nav only: tanpa sidebar kiri */
    body.layout-top-nav .main-sidebar,
    body.layout-top-nav .control-sidebar {
      display: none !important;
    }

    body.layout-top-nav .content-wrapper,
    body.layout-top-nav .main-footer {
      margin-left: 0 !important;
    }

    body.layout-top-nav .main-header {
      margin-left: 0 !important;
    }

    body.layout-top-nav .main-header .navbar-nav .nav-item.nav-publikasi-wrap {
      margin-right: 10px;
      align-self: center;
    }

    body.layout-top-nav .main-header .navbar-nav .nav-link.nav-publikasi {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 7px;
      min-height: 40px;
      min-width: 40px;
      padding: 8px 18px;
      margin: 6px 0;
      font-weight: 600;
      line-height: 1.2;
      color: #fff !important;
      background: linear-gradient(145deg, #1a8cff 0%, #0066ff 55%, #0050d4 100%);
      border: 1px solid rgba(255, 235, 100, 0.95);
      border-radius: 999px;
      box-shadow:
        0 0 0 1px #ffeb3b,
        0 0 10px rgba(255, 214, 0, 0.35),
        0 2px 10px rgba(0, 102, 255, 0.45);
      transition: background 0.28s ease, border-color 0.28s ease, box-shadow 0.28s ease, transform 0.2s ease;
    }

    body.layout-top-nav .main-header .navbar-nav .nav-link.nav-publikasi i {
      color: #ffd700;
      font-size: 1.1rem;
      filter: drop-shadow(0 0 3px rgba(255, 215, 0, 0.75));
      transition: color 0.28s ease, transform 0.28s ease, filter 0.28s ease;
    }

    body.layout-top-nav .main-header .navbar-nav .nav-link.nav-publikasi span {
      color: #fff !important;
      font-weight: 600;
      transition: color 0.28s ease;
    }

    body.layout-top-nav .main-header .navbar-nav .nav-link.nav-publikasi:hover,
    body.layout-top-nav .main-header .navbar-nav .nav-link.nav-publikasi:focus {
      color: #fff !important;
      background: linear-gradient(145deg, #33a0ff 0%, #0088ff 50%, #0066ff 100%);
      border-color: #fff59d;
      box-shadow:
        0 0 0 1px #ffee58,
        0 0 0 2px rgba(255, 235, 59, 0.55),
        0 0 14px rgba(255, 214, 0, 0.55),
        0 4px 18px rgba(0, 120, 255, 0.6);
      transform: translateY(-1px) scale(1.03);
      outline: none;
    }

    body.layout-top-nav .main-header .navbar-nav .nav-link.nav-publikasi:hover i,
    body.layout-top-nav .main-header .navbar-nav .nav-link.nav-publikasi:focus i {
      color: #ffec80;
      filter: drop-shadow(0 0 6px rgba(255, 223, 64, 1)) drop-shadow(0 0 2px rgba(255, 255, 255, 0.5));
      transform: scale(1.1);
    }

    body.layout-top-nav .main-header .navbar-nav .nav-link.nav-publikasi:hover span,
    body.layout-top-nav .main-header .navbar-nav .nav-link.nav-publikasi:focus span {
      color: #fff !important;
    }

    @media (max-width: 575.98px) {
      body.layout-top-nav .main-header .navbar-nav .nav-link.nav-publikasi {
        padding: 8px 12px;
      }
    }
  </style>
</head>




<body class="hold-transition layout-top-nav">
  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <div class="container-fluid px-3">

        <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse order-3" id="navbarCollapse">
          <ul class="navbar-nav flex-wrap">
            <?php
            $this->load->helper('cms');
            $publikasi_url = function_exists('cms_public_url')
              ? cms_public_url()
              : rtrim(base_url(), '/') . '/publikasi';
            ?>
            <li class="nav-item nav-publikasi-wrap">
              <a href="<?php echo htmlspecialchars($publikasi_url, ENT_QUOTES, 'UTF-8'); ?>"
                class="nav-link nav-publikasi"
                target="_blank"
                rel="noopener noreferrer"
                title="Buka halaman Publikasi di tab baru">
                <i class="fas fa-newspaper" aria-hidden="true"></i>
                <span class="d-none d-sm-inline">Publikasi</span>
              </a>
            </li>

            <li class="nav-item d-none d-sm-inline-block">
              <a href="<?php echo base_url("index.php/Dashboard")  ?>" class="nav-link">Dashboard</a>
            </li>

            <?php
            $this->load->helper('hak_akses_keuangan');

            $id_user_active = $this->session->userdata('sess_iduser');
            if ($id_user_active === null || $id_user_active === '') {
              $id_user_active = $this->session->userdata('id_users');
            }
            $id_user_level_active = $this->session->userdata('sess_id_user_level');
            if ($id_user_level_active === null || $id_user_level_active === '') {
              $id_user_level_active = $this->session->userdata('id_user_level');
            }
            $id_user_level_int = (int) $id_user_level_active;
            $is_admin_topnav = in_array($id_user_level_int, array(1, 2, 99), true);

            $sql_menu = "select * from menu where is_active='1' and is_parent=0 order by id asc";
            $main_menu = $this->db->query($sql_menu)->result();

            foreach ($main_menu as $menu) {
              $topnav_submenus = array();

              if ($is_admin_topnav) {
                $this->db->where('is_parent', (int) $menu->id);
                $this->db->where('is_active', '1');
                $this->db->order_by('id', 'ASC');
                $topnav_submenus = $this->db->get('menu')->result();
              } elseif (function_exists('hak_akses_topnav_submenu_rows')) {
                $topnav_submenus = hak_akses_topnav_submenu_rows(
                  $this,
                  $menu->id,
                  $id_user_active,
                  $id_user_level_active
                );
              } else {
                $this->db->where('main_menu', (int) $menu->id);
                $this->db->group_start();
                $this->db->where('id_user', (int) $id_user_active);
                $this->db->or_group_start();
                $this->db->where('id_user', 0);
                $this->db->where('id_user_level', $id_user_level_int);
                $this->db->group_end();
                $this->db->group_end();
                $list_menu_hak_akses = $this->db->get('tbl_hak_akses');
                foreach ($list_menu_hak_akses->result() as $menu_list) {
                  $this->db->where('id', (int) $menu_list->id_menu);
                  $this->db->where('is_active', '1');
                  $detail = $this->db->get('menu')->row();
                  if ($detail) {
                    $topnav_submenus[] = $detail;
                  }
                }
              }

              if (!empty($topnav_submenus)) {
                $MAIN_menu = array(
                  'name' => $menu->name,
                );

            ?>

                <li class="nav-item dropdown">
                  <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle"><?php echo htmlspecialchars($MAIN_menu['name'], ENT_QUOTES, 'UTF-8'); ?></a>

                  <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">

                    <?php
                    foreach ($topnav_submenus as $detail_menu_row) {
                      $detail_menu = is_array($detail_menu_row)
                        ? $detail_menu_row
                        : (array) $detail_menu_row;
                      if (empty($detail_menu['link'])) {
                        continue;
                      }
                    ?>

                      <li>
                        <a tabindex="-1" href="<?php echo base_url() ?>index.php<?php echo $detail_menu['link']; ?>" class="dropdown-item"><?php echo htmlspecialchars($detail_menu['name'], ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                      </li>


                    <?php
                    }

                    ?>




                  </ul>



                </li>

            <?php

              }
            }

            ?>
            <li class="nav-item">
              <a href="<?php echo base_url() ?>index.php/Anekadharmamasuk/logout" class="nav-link">LOGOUT</a>
            </li>


          </ul>


        </div>


      </div>
    </nav>
    <!-- /.navbar -->

    <?php
    echo $contents;
    ?>

    <!-- /.content-wrapper -->

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
  <?php if (!empty($page_footer_scripts)) { echo $page_footer_scripts; } ?>
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
    $('#tgl_awal').datetimepicker({
      format: 'D-M-YYYY'
    });
    $('#tgl_akhir').datetimepicker({
      format: 'D-M-YYYY'
    });
    $('#rekap_tgl_awal').datetimepicker({
      format: 'D-M-YYYY'
    });
    $('#rekap_tgl_akhir').datetimepicker({
      format: 'D-M-YYYY'
    });
    $('#tgl_po').datetimepicker({
      format: 'D-M-YYYY'
    });

    $('#tgl_permohonan').datetimepicker({
      format: 'D-M-YYYY'
    });

    $('#tgl_pembayaran').datetimepicker({
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
      format: 'DD-MM-YYYY',
      useCurrent: false
    });
    $('#dt_tgl_jual, #dt_tgl_jual_penjualan').datetimepicker({
      format: 'DD-MM-YYYY',
      useCurrent: false,
      allowInputToggle: true
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
      if ($('#tglSPOPFreeze').length && $.fn.DataTable) {
        var table = $('#tglSPOPFreeze').DataTable({
          scrollX: true,
          scrollY: "700px",
          scrollCollapse: true,
          paging: true,
        });

        if ($.fn.dataTable && $.fn.dataTable.FixedColumns) {
          new $.fn.dataTable.FixedColumns(table, {
            leftColumns: 3,
          });
        }
      }
    });

    $(document).ready(function() {
      if (!$('#tglSPOPFreeze1').length || !$.fn.DataTable) return;
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

      if ($.fn.dataTable && $.fn.dataTable.FixedColumns) {
        new $.fn.dataTable.FixedColumns(table, {
          leftColumns: 3,
        });
      }
    });

    $(document).ready(function() {
      if (!$('#tglSPOPFreeze2').length || !$.fn.DataTable) return;
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

      if ($.fn.dataTable && $.fn.dataTable.FixedColumns) {
        new $.fn.dataTable.FixedColumns(table, {
          leftColumns: 3,
        });
      }
    });

    $(document).ready(function() {
      if (!$('#exampleFreeze').length || !$.fn.DataTable) return;
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