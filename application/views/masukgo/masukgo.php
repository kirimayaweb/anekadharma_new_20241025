<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AnekaDharmaApps</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/adminlte/bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/adminlte/bower_components/Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/adminlte/plugins/iCheck/square/blue.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <!-- sweetalert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- //sweetalert -->

</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b>Aneka Dharma</b> Apps</a>
        </div>
        <div class="login-box-body">
            <?php
            $this->load->helper('login_security');
            $message = login_flash_message('Silahkan login untuk masuk ke aplikasi');
            ?>
            <p class="login-box-msg"><?php echo html_escape($message); ?></p>
            <?php echo form_open('Anekadharmamasuk/cheklogin'); ?>
            <?php echo login_csrf_field(); ?>
            <div class="form-group has-feedback">
                <input type="email" class="form-control" name="email" placeholder="Email" maxlength="190" autocomplete="username" required>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="password" placeholder="Password" maxlength="128" autocomplete="current-password" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-danger btn-block btn-flat"><i class="fa fa-sign-in" aria-hidden="true"></i> Masuk</button>
                </div>
                <div class="col-xs-6">
                    <?php echo anchor('Anekadharmamasuk/forgotpassword', '<i class="fa fa-eye-slash" aria-hidden="true"></i> Lupa Password', array('class' => 'btn btn-primary btn-block btn-flat')); ?>
                </div>
            </div>
            </form>
        </div>



    </div>
    <script src="<?php echo base_url(); ?>assets/adminlte/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>/assets/adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
    <script>
        $(function() {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%'
            });
        });
        // Hapus cache lokal hasil Generate & Recalculate saat masuk halaman login
        (function() {
            try {
                var prefix = 'genProsesVerify_v1_';
                var idxKey = 'genProsesVerify_v1_index';
                var keys = [];
                try {
                    var idxRaw = localStorage.getItem(idxKey);
                    if (idxRaw) {
                        keys = JSON.parse(idxRaw) || [];
                    }
                } catch (eIdx) {}
                if (!keys.length) {
                    for (var i = localStorage.length - 1; i >= 0; i--) {
                        var k = localStorage.key(i);
                        if (k && k.indexOf(prefix) === 0) {
                            keys.push(k);
                        }
                    }
                }
                keys.forEach(function(k) {
                    try { localStorage.removeItem(k); } catch (eR) {}
                });
                localStorage.removeItem(idxKey);
            } catch (eClear) {}
        })();
    </script>
</body>

</html>