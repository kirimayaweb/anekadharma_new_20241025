<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Email yang boleh membuka halaman Monitoring_system.
 */
function monitoring_allowed_viewer_emails()
{
    return array(
        'admin.id@gmail.com',
        'iwanesia.id@gmail.com',
    );
}

function monitoring_current_user_email()
{
    $ci = get_instance();
    $candidates = array(
        $ci->session->userdata('sess_email_user'),
        $ci->session->userdata('email'),
    );

    foreach ($candidates as $raw) {
        $email = strtolower(trim((string) $raw));
        if ($email !== '' && strpos($email, '@') !== false) {
            return $email;
        }
    }

    return '';
}

function monitoring_is_viewer_allowed()
{
    $email = monitoring_current_user_email();
    if ($email === '') {
        return false;
    }

    return in_array($email, monitoring_allowed_viewer_emails(), true);
}

function monitoring_working_threshold_seconds()
{
    return 300; // 5 menit = dianggap sedang bekerja
}

function monitoring_client_ip()
{
    $ci = get_instance();
    return $ci->input->ip_address();
}

function monitoring_client_user_agent()
{
    $ci = get_instance();
    $ua = $ci->input->user_agent();
    return $ua ? substr((string) $ua, 0, 500) : '';
}

function monitoring_parse_browser_label($user_agent)
{
    $ua = strtolower((string) $user_agent);
    if ($ua === '') {
        return 'Tidak diketahui';
    }
    if (strpos($ua, 'edg/') !== false || strpos($ua, 'edge') !== false) {
        return 'Microsoft Edge';
    }
    if (strpos($ua, 'chrome') !== false && strpos($ua, 'chromium') === false) {
        return 'Google Chrome';
    }
    if (strpos($ua, 'firefox') !== false) {
        return 'Mozilla Firefox';
    }
    if (strpos($ua, 'safari') !== false && strpos($ua, 'chrome') === false) {
        return 'Safari';
    }
    if (strpos($ua, 'opera') !== false || strpos($ua, 'opr/') !== false) {
        return 'Opera';
    }

    return 'Browser lain';
}

function monitoring_parse_device_label($user_agent)
{
    $ua = strtolower((string) $user_agent);
    if ($ua === '') {
        return 'Tidak diketahui';
    }
    if (strpos($ua, 'mobile') !== false || strpos($ua, 'android') !== false || strpos($ua, 'iphone') !== false) {
        return 'Mobile';
    }
    if (strpos($ua, 'tablet') !== false || strpos($ua, 'ipad') !== false) {
        return 'Tablet';
    }
    if (strpos($ua, 'windows') !== false) {
        return 'Windows PC';
    }
    if (strpos($ua, 'macintosh') !== false || strpos($ua, 'mac os') !== false) {
        return 'Mac';
    }
    if (strpos($ua, 'linux') !== false) {
        return 'Linux PC';
    }

    return 'Desktop / lainnya';
}

function monitoring_activity_label($row, $threshold_seconds = null)
{
    if ($threshold_seconds === null) {
        $threshold_seconds = monitoring_working_threshold_seconds();
    }

    if (!$row || (isset($row->status) && $row->status === 'offline')) {
        return 'Offline';
    }

    $last_seen = isset($row->last_seen_at) ? strtotime($row->last_seen_at) : 0;
    if ($last_seen <= 0) {
        return 'Offline';
    }

    $diff = time() - $last_seen;
    if ($diff <= $threshold_seconds) {
        return 'Sedang bekerja';
    }

    return 'Login (idle)';
}

function monitoring_activity_badge_class($label)
{
    switch ($label) {
        case 'Sedang bekerja':
            return 'badge-success';
        case 'Login (idle)':
            return 'badge-warning';
        default:
            return 'badge-secondary';
    }
}

function monitoring_record_login(array $user)
{
    $ci = get_instance();
    $ci->load->model('Monitoring_system_model');

    $session_id = session_id();
    if ($session_id === '') {
        return;
    }

    $ua = monitoring_client_user_agent();
    $ci->Monitoring_system_model->upsert_presence(array(
        'session_id' => $session_id,
        'id_users' => isset($user['id_users']) ? (int) $user['id_users'] : null,
        'email' => isset($user['email']) ? strtolower(trim($user['email'])) : monitoring_current_user_email(),
        'full_name' => isset($user['full_name']) ? $user['full_name'] : '',
        'ip_address' => monitoring_client_ip(),
        'user_agent' => $ua,
        'browser_label' => monitoring_parse_browser_label($ua),
        'device_label' => monitoring_parse_device_label($ua),
        'last_controller' => 'Anekadharmamasuk',
        'last_method' => 'login',
        'last_uri' => 'login',
        'login_at' => date('Y-m-d H:i:s'),
        'last_seen_at' => date('Y-m-d H:i:s'),
        'logout_at' => null,
        'status' => 'online',
    ));
}

function monitoring_record_logout()
{
    $ci = get_instance();
    $session_id = session_id();
    if ($session_id === '') {
        return;
    }

    $ci->load->model('Monitoring_system_model');
    $ci->Monitoring_system_model->mark_offline_by_session($session_id);
}

function monitoring_heartbeat()
{
    if (!is_logged_in()) {
        return;
    }

    $ci = get_instance();
    $session_id = session_id();
    if ($session_id === '') {
        return;
    }

    $class = strtolower((string) $ci->router->fetch_class());
    $method = strtolower((string) $ci->router->fetch_method());
    if ($class === 'monitoring_system' && $method === 'ajax_data') {
        return;
    }

    $ci->load->model('Monitoring_system_model');

    $ua = monitoring_client_user_agent();
    $uri = (string) $ci->uri->uri_string();
    if (strlen($uri) > 500) {
        $uri = substr($uri, 0, 500);
    }

    $ci->Monitoring_system_model->upsert_presence(array(
        'session_id' => $session_id,
        'id_users' => (int) $ci->session->userdata('sess_iduser'),
        'email' => monitoring_current_user_email(),
        'full_name' => (string) $ci->session->userdata('sess_username'),
        'ip_address' => monitoring_client_ip(),
        'user_agent' => $ua,
        'browser_label' => monitoring_parse_browser_label($ua),
        'device_label' => monitoring_parse_device_label($ua),
        'last_controller' => $class,
        'last_method' => $method,
        'last_uri' => $uri,
        'last_seen_at' => date('Y-m-d H:i:s'),
        'status' => 'online',
    ));
}
