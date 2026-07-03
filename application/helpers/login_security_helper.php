<?php
defined('BASEPATH') or exit('No direct script access allowed');

function login_security_cfg($key, $default = null)
{
    $CI = &get_instance();
    if (!isset($CI->config)) {
        return $default;
    }
    $value = $CI->config->item($key, 'login_security');
    return ($value === null) ? $default : $value;
}

function login_security_boot_config()
{
    $CI = &get_instance();
    $CI->config->load('login_security', true);
}

function login_csrf_token_name()
{
    return 'login_csrf_token';
}

function login_csrf_get_hash()
{
    $CI = &get_instance();
    $hash = (string) $CI->session->userdata('login_csrf_hash');

    if ($hash === '') {
        $hash = bin2hex(random_bytes(32));
        $CI->session->set_userdata('login_csrf_hash', $hash);
    }

    return $hash;
}

function login_csrf_field()
{
    $name = login_csrf_token_name();
    $hash = login_csrf_get_hash();

    return '<input type="hidden" name="' . html_escape($name) . '" value="' . html_escape($hash) . '">';
}

function login_csrf_verify()
{
    $CI = &get_instance();
    $name = login_csrf_token_name();
    $session_hash = (string) $CI->session->userdata('login_csrf_hash');
    $posted_hash = (string) $CI->input->post($name);

    if ($session_hash === '' || $posted_hash === '') {
        return false;
    }

    return hash_equals($session_hash, $posted_hash);
}

function login_csrf_rotate()
{
    $CI = &get_instance();
    $CI->session->set_userdata('login_csrf_hash', bin2hex(random_bytes(32)));
}

function login_security_headers()
{
    if (headers_sent()) {
        return;
    }

    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('X-XSS-Protection: 1; mode=block');
    header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
}

function login_flash_message($default = '')
{
    $CI = &get_instance();
    $message = $CI->session->flashdata('status_login');

    if ($message === null || $message === '') {
        return $default;
    }

    return (string) $message;
}

function login_generic_error_message()
{
    return (string) login_security_cfg(
        'login_generic_error',
        'Kredensial tidak valid. Silahkan coba lagi.'
    );
}

function login_forgot_generic_success_message()
{
    return (string) login_security_cfg(
        'login_forgot_generic_success',
        'Jika nomor terdaftar, instruksi reset akan dikirim ke WhatsApp Anda.'
    );
}

function login_sanitize_whatsapp($raw)
{
    $number = preg_replace('/\D+/', '', trim((string) $raw));

    if ($number === '' || !preg_match('/^0\d{9,14}$/', $number)) {
        return '';
    }

    return $number;
}

function login_validate_email($email)
{
    $email = trim((string) $email);
    $max = (int) login_security_cfg('login_email_max_length', 190);

    if ($email === '' || strlen($email) > $max) {
        return false;
    }

    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function login_validate_password_input($password)
{
    $password = (string) $password;
    $max = (int) login_security_cfg('login_password_max_length', 128);

    if ($password === '' || strlen($password) > $max) {
        return false;
    }

    return strpos($password, "\0") === false;
}

function login_client_ip()
{
    $CI = &get_instance();
    return (string) $CI->input->ip_address();
}

function login_is_https_request()
{
    if (!empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off') {
        return true;
    }

    if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower((string) $_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https') {
        return true;
    }

    if (!empty($_SERVER['SERVER_PORT']) && (string) $_SERVER['SERVER_PORT'] === '443') {
        return true;
    }

    return false;
}

function login_is_local_host()
{
    $host = isset($_SERVER['HTTP_HOST']) ? strtolower((string) $_SERVER['HTTP_HOST']) : '';
    $host = preg_replace('/:\d+$/', '', $host);
    $skip = login_security_cfg('login_https_skip_hosts', array('localhost', '127.0.0.1'));

    return in_array($host, (array) $skip, true);
}

function login_force_https_if_required()
{
    if (!login_security_cfg('login_force_https', false)) {
        return;
    }

    if (login_is_local_host() || login_is_https_request()) {
        return;
    }

    if (headers_sent()) {
        return;
    }

    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
    $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
    $target = 'https://' . $host . $uri;

    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $target);
    exit;
}

function login_rate_limit_bucket($scope)
{
    $ip = login_client_ip();
    return APPPATH . 'cache/login_rl_' . $scope . '_' . md5($ip) . '.json';
}

function login_rate_limit_read($scope)
{
    $file = login_rate_limit_bucket($scope);
    if (!is_file($file)) {
        return array('attempts' => 0, 'locked_until' => 0);
    }

    $raw = @file_get_contents($file);
    if ($raw === false) {
        return array('attempts' => 0, 'locked_until' => 0);
    }

    $data = json_decode($raw, true);
    if (!is_array($data)) {
        return array('attempts' => 0, 'locked_until' => 0);
    }

    return array(
        'attempts' => isset($data['attempts']) ? (int) $data['attempts'] : 0,
        'locked_until' => isset($data['locked_until']) ? (int) $data['locked_until'] : 0,
    );
}

function login_rate_limit_write($scope, array $data)
{
    $file = login_rate_limit_bucket($scope);
    $dir = dirname($file);
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }

    @file_put_contents($file, json_encode($data), LOCK_EX);
}

function login_rate_limit_is_locked($scope)
{
    $data = login_rate_limit_read($scope);
    return ($data['locked_until'] > time());
}

function login_rate_limit_lockout_message($scope)
{
    $data = login_rate_limit_read($scope);
    $remaining = max(0, $data['locked_until'] - time());
    $minutes = (int) ceil($remaining / 60);

    if ($scope === 'forgot') {
        return 'Terlalu banyak permintaan reset. Coba lagi sekitar ' . $minutes . ' menit.';
    }

    return 'Terlalu banyak percobaan login. Coba lagi sekitar ' . $minutes . ' menit.';
}

function login_rate_limit_check($scope = 'login')
{
    if (login_rate_limit_is_locked($scope)) {
        return false;
    }

    return true;
}

function login_rate_limit_record_failure($scope = 'login')
{
    $max = ($scope === 'forgot')
        ? (int) login_security_cfg('login_forgot_max_attempts', 5)
        : (int) login_security_cfg('login_max_attempts', 10);

    $lockout = ($scope === 'forgot')
        ? (int) login_security_cfg('login_forgot_lockout_minutes', 30)
        : (int) login_security_cfg('login_lockout_minutes', 15);

    $data = login_rate_limit_read($scope);

    if ($data['locked_until'] > time()) {
        return;
    }

    $data['attempts'] = (int) $data['attempts'] + 1;

    if ($data['attempts'] >= $max) {
        $data['locked_until'] = time() + ($lockout * 60);
        $data['attempts'] = 0;
    }

    login_rate_limit_write($scope, $data);
}

function login_rate_limit_clear($scope = 'login')
{
    $file = login_rate_limit_bucket($scope);
    if (is_file($file)) {
        @unlink($file);
    }
}

function login_mfa_is_required($user_level)
{
    $levels = login_security_cfg('login_mfa_levels', array('1', '99'));
    return in_array((string) $user_level, (array) $levels, true);
}

function login_mfa_start(array $user, $otp_plain)
{
    $CI = &get_instance();
    $expire = (int) login_security_cfg('login_mfa_otp_expire', 300);

    $CI->session->set_userdata(array(
        'login_mfa_pending' => array(
            'id_users' => $user['id_users'],
            'id_user_level' => $user['id_user_level'],
            'email' => $user['email'],
            'no_hp' => isset($user['no_hp']) ? $user['no_hp'] : '',
            'full_name' => isset($user['full_name']) ? $user['full_name'] : '',
            'cover_display' => isset($user['cover_display']) ? $user['cover_display'] : null,
            'darurat' => !empty($user['_login_darurat']),
        ),
        'login_mfa_otp_hash' => password_hash((string) $otp_plain, PASSWORD_DEFAULT),
        'login_mfa_expires' => time() + $expire,
        'login_mfa_attempts' => 0,
    ));
}

function login_mfa_verify_otp($otp_plain)
{
    $CI = &get_instance();
    $hash = (string) $CI->session->userdata('login_mfa_otp_hash');
    $expires = (int) $CI->session->userdata('login_mfa_expires');
    $attempts = (int) $CI->session->userdata('login_mfa_attempts');

    if ($hash === '' || $expires < time()) {
        return 'expired';
    }

    if ($attempts >= 5) {
        return 'locked';
    }

    if (!password_verify((string) $otp_plain, $hash)) {
        $CI->session->set_userdata('login_mfa_attempts', $attempts + 1);
        return 'invalid';
    }

    return 'ok';
}

function login_mfa_get_pending_user()
{
    $CI = &get_instance();
    $pending = $CI->session->userdata('login_mfa_pending');
    return is_array($pending) ? $pending : null;
}

function login_mfa_clear()
{
    $CI = &get_instance();
    $CI->session->unset_userdata(array(
        'login_mfa_pending',
        'login_mfa_otp_hash',
        'login_mfa_expires',
        'login_mfa_attempts',
    ));
}

function login_mfa_has_pending()
{
    return login_mfa_get_pending_user() !== null;
}

function login_mfa_generate_otp()
{
    $length = (int) login_security_cfg('login_mfa_otp_length', 6);
    $max = (int) str_repeat('9', max(1, $length));
    $min = (int) str_pad('1', $length, '0');

    return (string) random_int($min, $max);
}

/**
 * Level user yang diizinkan redirect ke Dashboard setelah login sukses.
 */
function login_dashboard_allowed_level_ids()
{
    $levels = array('1', '2', '3', '4', '6', '7', '9', '99', '444', '555', '777', '999');

    if (function_exists('hak_akses_keuangan_level_ids')) {
        foreach (hak_akses_keuangan_level_ids() as $level_id) {
            $levels[] = (string) $level_id;
        }
    } else {
        $levels[] = '666';
        $levels[] = '888';
    }

    return array_values(array_unique($levels));
}

function login_can_redirect_dashboard($user_level)
{
    return in_array((string) $user_level, login_dashboard_allowed_level_ids(), true);
}

/**
 * Level admin yang boleh melewati cek tbl_hak_akses di is_login().
 */
function login_admin_bypass_level_ids()
{
    $levels = login_security_cfg('login_admin_bypass_levels', array('1', '2', '99'));
    if (!is_array($levels)) {
        $levels = array('1', '2', '99');
    }

    return array_map('strval', $levels);
}

function login_is_admin_level($user_level)
{
    return in_array((string) $user_level, login_admin_bypass_level_ids(), true);
}
