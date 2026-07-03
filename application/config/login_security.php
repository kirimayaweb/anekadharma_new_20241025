<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Keamanan Login AnekaDharma — konfigurasi tingkat lanjut
|--------------------------------------------------------------------------
*/

$config['login_force_https'] = (ENVIRONMENT === 'production');
$config['login_https_skip_hosts'] = array('localhost', '127.0.0.1');

$config['login_max_attempts'] = 10;
$config['login_lockout_minutes'] = 15;

$config['login_forgot_max_attempts'] = 5;
$config['login_forgot_lockout_minutes'] = 30;

$config['login_mfa_levels'] = array('1', '99');

/*
| Level admin yang boleh akses semua modul di tbl_menu tanpa cek tbl_hak_akses.
| Sesuai konvensi aplikasi (Super Admin, Admin, administrator).
*/
$config['login_admin_bypass_levels'] = array('1', '2', '99');
$config['login_mfa_otp_length'] = 6;
$config['login_mfa_otp_expire'] = 300;

$config['login_generic_error'] = 'Kredensial tidak valid. Silahkan coba lagi.';
$config['login_forgot_generic_success'] = 'Jika nomor terdaftar, instruksi reset akan dikirim ke WhatsApp Anda.';

$config['login_email_max_length'] = 190;
$config['login_password_max_length'] = 128;
