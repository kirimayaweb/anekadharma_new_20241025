<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Update heartbeat kehadiran user (login / sedang bekerja) setiap request terautentikasi.
 */
function monitoring_presence_heartbeat()
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

    if (!function_exists('is_logged_in')) {
        $CI->load->helper('racode');
    }

    if (!is_logged_in()) {
        return;
    }

    $CI->load->helper('monitoring');
    monitoring_heartbeat();
}
