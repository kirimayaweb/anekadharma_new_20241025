<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Guard otomatis: semua controller wajib login kecuali daftar publik (auth_public.php).
 */
function auth_controller_guard()
{
    $CI = &get_instance();
    $CI->config->load('auth_public', true);

    $public = $CI->config->item('auth_public_controllers', 'auth_public');
    if (!is_array($public)) {
        $public = array();
    }

    $class = strtolower((string) $CI->router->fetch_class());

    if (in_array($class, $public, true)) {
        return;
    }

    if ($class === 'create_password_hash' && ENVIRONMENT === 'production') {
        show_404();
        exit;
    }

    is_login();
}
