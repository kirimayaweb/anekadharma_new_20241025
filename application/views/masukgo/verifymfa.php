<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Verifikasi Login - AnekaDharmaApps</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/adminlte/bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b>Verifikasi</b> Admin</a>
        </div>
        <div class="login-box-body">
            <?php
            $this->load->helper('login_security');
            $message = login_flash_message('Masukkan kode OTP yang dikirim ke WhatsApp terdaftar Anda.');
            ?>
            <p class="login-box-msg"><?php echo html_escape($message); ?></p>
            <?php echo form_open('Anekadharmamasuk/chekmfa'); ?>
            <?php echo login_csrf_field(); ?>
            <div class="form-group has-feedback">
                <input type="text" class="form-control text-center" name="otp" placeholder="Kode OTP (6 digit)" inputmode="numeric" maxlength="6" autocomplete="one-time-code" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-danger btn-block btn-flat"><i class="fa fa-shield" aria-hidden="true"></i> Verifikasi</button>
                </div>
            </div>
            </form>
            <p class="text-center" style="margin-top:12px;">
                <?php echo anchor('Anekadharmamasuk', 'Kembali ke login'); ?>
            </p>
        </div>
    </div>
</body>

</html>
