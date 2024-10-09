<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>PILARPUSTAKAGROUP</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <!--         
		<link rel="stylesheet" href="<?php echo base_url() ?>template_retri_pilar/bootstrap/css/bootstrap.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>template_retri_pilar/font-awesome-4.4.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>template_retri_pilar/ionicons-2.0.1/css/ionicons.min.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>template_retri_pilar/plugins/datatables/dataTables.bootstrap.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>template_retri_pilar/plugins/select2/select2.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>template_retri_pilar/dist/css/AdminLTE.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>template_retri_pilar/dist/css/skins/_all-skins.css">
 -->

  <!-- ========================= -->

  <link rel="stylesheet" href="<?php echo base_url() ?>assets_retripasar_lte241/adminlte241/bower_components/bootstrap/dist/css/bootstrap.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets_retripasar_lte241/adminlte241/bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets_retripasar_lte241/adminlte241/bower_components/Ionicons/css/ionicons.min.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets_retripasar_lte241/adminlte241/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets_retripasar_lte241/adminlte241/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets_retripasar_lte241/adminlte241/plugins/iCheck/all.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets_retripasar_lte241/adminlte241/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets_retripasar_lte241/adminlte241/plugins/timepicker/bootstrap-timepicker.min.css">
  <!-- <link rel="stylesheet" href="<?php echo base_url() ?>assets/adminlte241/bower_components/select2/dist/css/select2.min.css"> -->
  <link rel="stylesheet" href="<?php echo base_url() ?>assets_retripasar_lte241/adminlte241/bower_components/select2/dist/css/select2.css">
  <!-- <link rel="stylesheet" href="<?php echo base_url() ?>assets/adminlte241/dist/css/AdminLTE.min.css"> -->
  <link rel="stylesheet" href="<?php echo base_url() ?>assets_retripasar_lte241/adminlte241/dist/css/AdminLTE.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets_retripasar_lte241/adminlte241/dist/css/skins/_all-skins.css">

  <link rel="stylesheet" href="<?php echo base_url() ?>template_retri_pilar/plugins/datatables/dataTables.bootstrap.css">
  <!-- ========================= -->

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

  <script src="https://cdn.lr-ingest.io/LogRocket.min.js" crossorigin="anonymous"></script>
  <script>
    window.LogRocket && window.LogRocket.init('wcwttj/eretribusi');
    window.LogRocket.identify("ok user", {
      name: "pasar",
      ret_name: "pasar"
    });
  </script>


</head>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">

    <header class="main-header">
      <!-- Logo -->
      <a href="index2.html" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>PILARPUSTAKAGROUP</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>PILARPUSTAKAGROUP</b></span>
      </a>
      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </a>
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">



            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="<?php echo base_url() ?>template_retri_pilar/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
                <span class="hidden-xs"><?php tampil($username); ?></span>
              </a>
              <ul class="dropdown-menu">
                <!-- User image -->
                <li class="user-header">
                  <img src="<?php echo base_url() ?>template_retri_pilar/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                  <p>
                    <?php tampil($username); ?>
                    <small><?php tampil($company); ?></small>
                  </p>
                </li>

                <li class="user-footer">
                  <div class="pull-left">
                    <a href="#" class="btn btn-default btn-flat">Profile</a>
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
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
          <div class="pull-left image">
            <img src="<?php echo base_url() ?>template_retri_pilar/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
          </div>
          <div class="pull-left info">
            <p><?php tampil($username); ?></p>
            <a href="#"><i class="fa fa-circle text-success"></i> <?php tampil($company); ?></a>
          </div>
        </div>

        <hr>

        <ul class="sidebar-menu">

          <?php


          $company = 'admin';
          $leveldb = 'admin';


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
          } elseif ($company == 'adminrusunawa') {
            $leveldb = 'adminrusunawa';
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
            if ($submenu->num_rows() > 0) {
              echo "<li class='treeview'>" . anchor('#', "<i class='$m->icon'></i>" . strtoupper($m->name) . ' <i class="fa fa-angle-left pull-right"></i>') . "<ul class='treeview-menu'>";
              foreach ($submenu->result() as $s) {
                echo "<li>" . anchor($s->link, "<i class='$s->icon'></i> <span>" . strtoupper($s->name)) . "</span></li>";
              }
              echo "</ul></li>";
            } else {
              echo "<li>" . anchor($m->link, "<i class='$m->icon'></i> <span>" . strtoupper($m->name)) . "</span></li>";
            }
          }


          ?>



        </ul>
      </section>
      <!-- /.sidebar -->
    </aside>

    <div class="content-wrapper">

      <?php
      echo $contents;
      ?>



    </div><!-- /.content-wrapper -->
    <footer class="main-footer">

      <strong>Copyright &copy; PILARPUSTAKAGROUP.</strong> All rights reserved.
    </footer>


    <div class="control-sidebar-bg"></div>
  </div><!-- ./wrapper -->









  <script src="<?php echo base_url() ?>assets_retripasar_lte241/adminlte241/bower_components/jquery/dist/jquery.min.js"></script>
  <script src="<?php echo base_url() ?>assets_retripasar_lte241/adminlte241/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="<?php echo base_url() ?>assets_retripasar_lte241/adminlte241/bower_components/select2/dist/js/select2.full.min.js"></script>
  <script src="<?php echo base_url() ?>assets_retripasar_lte241/adminlte241/plugins/input-mask/jquery.inputmask.js"></script>
  <script src="<?php echo base_url() ?>assets_retripasar_lte241/adminlte241/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
  <script src="<?php echo base_url() ?>assets_retripasar_lte241/adminlte241/plugins/input-mask/jquery.inputmask.extensions.js"></script>
  <script src="<?php echo base_url() ?>assets_retripasar_lte241/adminlte241/bower_components/moment/min/moment.min.js"></script>
  <script src="<?php echo base_url() ?>assets_retripasar_lte241/adminlte241/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
  <script src="<?php echo base_url() ?>assets_retripasar_lte241/adminlte241/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
  <script src="<?php echo base_url() ?>assets_retripasar_lte241/adminlte241/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
  <script src="<?php echo base_url() ?>assets_retripasar_lte241/adminlte241/plugins/timepicker/bootstrap-timepicker.min.js"></script>
  <script src="<?php echo base_url() ?>assets_retripasar_lte241/adminlte241/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
  <script src="<?php echo base_url() ?>assets_retripasar_lte241/adminlte241/plugins/iCheck/icheck.min.js"></script>
  <script src="<?php echo base_url() ?>assets_retripasar_lte241/adminlte241/bower_components/fastclick/lib/fastclick.js"></script>
  <script src="<?php echo base_url() ?>assets_retripasar_lte241/adminlte241/dist/js/adminlte.min.js"></script>
  <script src="<?php echo base_url() ?>assets_retripasar_lte241/adminlte241/dist/js/demo.js"></script>


  <script src="<?php echo base_url() ?>template_retri_pilar/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="<?php echo base_url() ?>template_retri_pilar/plugins/datatables/dataTables.bootstrap.min.js"></script>

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
      //Initialize Select2 Elements
      $('.select2').select2()

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

      //Date range picker
      $('#reservation').daterangepicker()
      //Date range picker with time picker
      $('#reservationtime').daterangepicker({
        timePicker: true,
        timePickerIncrement: 30,
        format: 'MM/DD/YYYY h:mm A'
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
          $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
        }
      )

      //Date picker
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
      $('#tgl_persetujuan').datepicker({
        autoclose: true
      })
      $('#tgl_expired').datepicker({
        autoclose: true
      })
      $('#tglmulai').datepicker({
        autoclose: true
      })
      $('#tglakhir').datepicker({
        autoclose: true
      })

      //iCheck for checkbox and radio inputs
      $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
      })
      //Red color scheme for iCheck
      $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
        checkboxClass: 'icheckbox_minimal-red',
        radioClass: 'iradio_minimal-red'
      })
      //Flat red color scheme for iCheck
      $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
      })

      //Colorpicker
      $('.my-colorpicker1').colorpicker()
      //color picker with addon
      $('.my-colorpicker2').colorpicker()

      //Timepicker
      $('.timepicker').timepicker({
        showInputs: false
      })
    })
  </script>


</body>

</html>