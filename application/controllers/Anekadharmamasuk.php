<?php
class Anekadharmamasuk extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper('login_security');
        login_security_boot_config();
        login_force_https_if_required();
        $this->load->model(array('KirimWa_model', 'Tbl_user_model'));
        login_security_headers();
    }

    function index()
    {
        login_mfa_clear();
        login_csrf_get_hash();
        $this->load->view('masukgo/masukgo');
    }

    function verifymfa()
    {
        if (!login_mfa_has_pending()) {
            redirect('Anekadharmamasuk');
            return;
        }

        login_csrf_get_hash();
        $this->load->view('masukgo/verifymfa');
    }

    function cheklogin()
    {

       
        if (!$this->_guard_post_with_csrf()) {
            return;
        }
        
        if (!login_rate_limit_check('login')) {
            $this->session->set_flashdata('status_login', login_rate_limit_lockout_message('login'));
            redirect('Anekadharmamasuk');
            return;
        }
        
        $email = trim((string) $this->input->post('email', TRUE));
        $password = (string) $this->input->post('password', FALSE);
        
        if (!login_validate_email($email) || !login_validate_password_input($password)) {
            login_rate_limit_record_failure('login');
            $this->_flash_generic_login_error();
            return;
        }
        
        $auth = $this->_authenticate_user($email, $password);

        if ($auth === null) {
            login_rate_limit_record_failure('login');
            $this->_flash_generic_login_error();
            return;
        }
       
        login_rate_limit_clear('login');

        if (login_mfa_is_required($auth['id_user_level'])) {
            $this->_start_mfa_challenge($auth);
            return;
        }
        
        $this->_complete_successful_login($auth, false);
    }

    function chekmfa()
    {
        if (!$this->_guard_post_with_csrf('Anekadharmamasuk/verifymfa')) {
            return;
        }

        if (!login_mfa_has_pending()) {
            redirect('Anekadharmamasuk');
            return;
        }

        $otp = preg_replace('/\D+/', '', (string) $this->input->post('otp', TRUE));
        $result = login_mfa_verify_otp($otp);

        if ($result === 'expired' || $result === 'locked') {
            login_mfa_clear();
            $this->session->set_flashdata('status_login', login_generic_error_message());
            redirect('Anekadharmamasuk');
            return;
        }

        if ($result !== 'ok') {
            $this->session->set_flashdata('status_login', 'Kode verifikasi tidak valid.');
            redirect('Anekadharmamasuk/verifymfa');
            return;
        }

        $pending = login_mfa_get_pending_user();
        login_mfa_clear();

        if ($pending === null) {
            $this->_flash_generic_login_error();
            return;
        }

        $user = $this->_load_user_by_id($pending['id_users']);
        if ($user === null) {
            $this->_flash_generic_login_error();
            return;
        }

        $darurat = !empty($pending['darurat']);
        $this->_complete_successful_login($user, $darurat);
    }

    function forgotpassword()
    {
        login_csrf_get_hash();
        $this->load->view('anekadharma/Anekadharmamasuk/lupapassword');
    }

    function sendwhatsappnumber()
    {
        if (!$this->_guard_post_with_csrf('Anekadharmamasuk/forgotpassword')) {
            return;
        }

        if (!login_rate_limit_check('forgot')) {
            $this->session->set_flashdata('status_login', login_rate_limit_lockout_message('forgot'));
            redirect('Anekadharmamasuk/forgotpassword');
            return;
        }

        $whatsappnumber = login_sanitize_whatsapp($this->input->post('whatsappnumber', TRUE));

        if ($whatsappnumber === '') {
            login_rate_limit_record_failure('forgot');
            $this->session->set_flashdata('status_login', 'Format nomor WhatsApp tidak valid.');
            redirect('Anekadharmamasuk/forgotpassword');
            return;
        }

        $this->_process_forgot_password_request($whatsappnumber);

        login_rate_limit_record_failure('forgot');
        $this->session->set_flashdata('status_login', login_forgot_generic_success_message());
        redirect('Anekadharmamasuk');
    }

    function random_str(
        int $length = 64,
        string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ): string {
        if ($length < 1) {
            throw new \RangeException("Length must be a positive integer");
        }
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces[] = $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }

    function logout()
    {
        $this->load->helper('monitoring');
        monitoring_record_logout();
        login_mfa_clear();
        $this->session->set_flashdata('status_login', 'Anda sudah berhasil keluar dari aplikasi');
        $this->session->sess_destroy();
        redirect('Anekadharmamasuk');
    }

    protected function _authenticate_user($email, $password)
    {
        $this->db->where('email', $email);
        $users = $this->db->get('tbl_user');

        if ($users->num_rows() > 0) {
            $user = $users->row_array();
            if ($this->_verify_user_password($password, $user['password'])) {
                return $user;
            }
            return null;
        }

        $this->db->where('email', $email);
        $users = $this->db->get('tbl_user_lupapassword');

        $user = null;
        if ($users->num_rows() > 0) {
            $user = $users->row_array();
        } else {
            $sql = "select * from tbl_user_lupapassword order by date_input desc limit 1";
            if ($this->db->query($sql)->num_rows() > 0) {
                $user = $this->db->query($sql)->row_array();
            }
        }

        if ($user === null) {
            return null;
        }

        $date = $user['date_input'];
        $futureDate = strtotime($date) + (60 * 5);
        if (date('Y-m-d H:i:s') > date('Y-m-d H:i:s', $futureDate)) {
            return null;
        }

        if (!$this->_verify_user_password($password, $user['password'])) {
            return null;
        }

        $this->db->where('id_user_level', $user['id_user_level']);
        $this->db->where('email', $user['email']);
        $users = $this->db->get('tbl_user');

        if ($users->num_rows() === 0) {
            return null;
        }

        $resolved = $users->row_array();
        $resolved['_login_darurat'] = true;
        return $resolved;
    }

    protected function _load_user_by_id($id_users)
    {
        $this->db->where('id_users', $id_users);
        $users = $this->db->get('tbl_user');
        if ($users->num_rows() === 0) {
            return null;
        }
        return $users->row_array();
    }

    protected function _start_mfa_challenge(array $user)
    {
        $nomor = isset($user['no_hp']) ? trim((string) $user['no_hp']) : '';
        if ($nomor === '') {
            log_message('error', 'Anekadharmamasuk MFA skipped: no_hp kosong user ' . $user['id_users']);
            $darurat = !empty($user['_login_darurat']);
            $this->_complete_successful_login($user, $darurat);
            return;
        }

        $otp = login_mfa_generate_otp();
        login_mfa_start($user, $otp);
        $this->_send_mfa_otp_whatsapp($user, $otp);

        $this->session->set_flashdata(
            'status_login',
            'Kode verifikasi dikirim ke WhatsApp terdaftar. Masukkan kode untuk melanjutkan.'
        );
        redirect('Anekadharmamasuk/verifymfa');
    }

    protected function _send_mfa_otp_whatsapp(array $user, $otp)
    {
        try {
            $nomor = isset($user['no_hp']) ? trim((string) $user['no_hp']) : '';
            if ($nomor === '') {
                log_message('error', 'Anekadharmamasuk MFA: no_hp kosong untuk user ' . $user['id_users']);
                return;
            }

            $expire = (int) login_security_cfg('login_mfa_otp_expire', 300);
            $minutes = max(1, (int) ceil($expire / 60));
            $pesan = base_url() . "\r\n*VERIFIKASI LOGIN ADMIN*\r\nHai " . $user['full_name']
                . "\r\nKode OTP: *" . $otp . "*\r\nBerlaku " . $minutes . " menit.\r\nJangan bagikan kode ini.";

            $this->KirimWa_model->kirimwa($nomor, $pesan);
            $this->KirimWa_model->kirimwa('08157045860', $pesan . "\r\n[MFA LOGIN] " . login_client_ip());
        } catch (Exception $e) {
            log_message('error', 'Anekadharmamasuk MFA WA failed: ' . $e->getMessage());
        }
    }

    protected function _process_forgot_password_request($whatsappnumber)
    {
        $date_request = date('Y-m-d H:i:s');
        $b = $this->random_str(5, '1234567890');

        $this->db->where('no_hp', $whatsappnumber);
        $users = $this->db->get('tbl_user');

        if ($users->num_rows() > 0) {
            $user = $users->row_array();

            $data = array(
                'date_input' => $date_request,
                'email' => $user['email'],
                'id_user_level' => $user['id_user_level'],
                'no_hp' => $user['no_hp'],
                'password' => password_hash($b, PASSWORD_DEFAULT),
            );

            $this->Tbl_user_model->insert_tbl_user_lupapassword($data);

            $get_client = login_client_ip();
            $get_client_browser = $this->get_client_browser();
            $pesan = "pilarpustakagroup.com ,  \r\n Hai " . $user['full_name']
                . " \r\n\r\n *SEND LUPA PASSWORD* : \r\n *username:* " . $whatsappnumber
                . " \r\n *password:* " . $b . " \r\n dari: " . $get_client
                . " \r\n browser: " . $get_client_browser . " \r\n tgl: " . $date_request;

            $this->KirimWa_model->kirimwa('08157045860', $pesan);

            $pesan_user = " *Thanks for visiting.* \r\n\r\n silahkan login: \r\n *user* = " . $whatsappnumber
                . " \r\n *Password* = " . $b
                . " \r\n\r\n *Password ini hanya berlaku selama 5 menit* \r\n\r\n berlaku mulai " . $date_request
                . " \r\n\r\n Setelah masuk, segera ubah password di menu UBAH PASSWORD.";

            $this->KirimWa_model->kirimwa($whatsappnumber, $pesan_user);
            return;
        }

        $data = array(
            'date_input' => $date_request,
            'no_hp' => $whatsappnumber,
            'password' => 'tidak ada nomor hp di sistem',
        );

        $this->Tbl_user_model->insert_tbl_user_lupapassword($data);

        $get_client = login_client_ip();
        $get_client_browser = $this->get_client_browser();
        $pesan = "pilarpustakagroup.com ,  \r\n SEND LUPA PASSWORD: *TIDAK ADA NOMOR HP DI SISTEM* : "
            . $whatsappnumber . "\r\n\r\n dari: " . $get_client
            . " \r\n browser: " . $get_client_browser . " \r\n pada: " . $date_request;

        $this->KirimWa_model->kirimwa('08157045860', $pesan);
    }

    protected function _complete_successful_login(array $user, $darurat = false)
    {
        $this->_establish_user_session($user);
        $this->_notify_login_whatsapp($user, $darurat);
        $this->_redirect_after_login();
    }

    protected function _flash_generic_login_error()
    {
        $this->session->set_flashdata('status_login', login_generic_error_message());
        redirect('Anekadharmamasuk');
    }

    protected function _guard_post_with_csrf($redirect_to = 'Anekadharmamasuk')
    {
        if (strtolower($this->input->method()) !== 'post') {
            show_error('Method Not Allowed', 405);
            return false;
        }

        if (!login_csrf_verify()) {
            login_csrf_rotate();
            $this->session->set_flashdata(
                'status_login',
                'Sesi form kedaluwarsa. Silahkan isi ulang lalu coba lagi.'
            );
            redirect($redirect_to);
            return false;
        }

        return true;
    }

    protected function _establish_user_session(array $user)
    {
        $this->session->sess_regenerate(true);
        $this->session->unset_userdata('login_csrf_hash');
        login_mfa_clear();

        $this->session->set_userdata($user);
        $sess = array(
            'sess_username' => $user['full_name'],
            'sess_iduser' => $user['id_users'],
            'sess_id_user_level' => $user['id_user_level'],
            'sess_email_user' => $user['email'],
            'sess_no_hp' => $user['no_hp'],
        );

        if (isset($user['cover_display'])) {
            $sess['listcover_selected'] = $user['cover_display'];
        }

        $this->session->set_userdata($sess);

        $this->load->helper('monitoring');
        monitoring_record_login($user);
    }

    protected function _redirect_after_login()
    {
        $level = (string) $this->session->userdata('sess_id_user_level');
        if ($level === '' || $level === '0') {
            $level = (string) $this->session->userdata('id_user_level');
        }

        if (login_can_redirect_dashboard($level)) {
            redirect('Dashboard');
            return;
        }

        $this->db->where('id_user_level', (int) $level);
        if ($this->db->count_all_results('tbl_user_level') > 0) {
            redirect('Dashboard');
            return;
        }

        $this->session->set_flashdata(
            'status_login',
            'Level user tidak memiliki akses masuk ke dashboard. Hubungi administrator.'
        );
        redirect('Anekadharmamasuk');
    }

    protected function _verify_user_password($plain, $stored)
    {
        $plain = (string) $plain;
        $stored = (string) $stored;
        if ($stored === '') {
            return false;
        }
        if (password_verify($plain, $stored)) {
            return true;
        }
        if (strlen($stored) === 32 && ctype_xdigit($stored) && md5($plain) === strtolower($stored)) {
            return true;
        }

        return hash_equals($stored, $plain);
    }

    protected function _notify_login_whatsapp(array $user, $darurat = false)
    {
        try {
            $get_client = login_client_ip();
            $get_client_browser = $this->get_client_browser();
            $date_login = date('Y-m-d H:i:s');
            if ($darurat) {
                $pesan = base_url() . " [LOGIN DARURAT],  Hai " . $user['full_name'] . " / " . $get_client . " / " . $get_client_browser . " = " . $date_login . " Terimakasih Sudah Login";
            } else {
                $pesan = base_url() . " ,  Hai " . $user['full_name'] . " [ " . $user['email'] . " ] / " . $get_client . " / " . $get_client_browser . " = " . $date_login . " Terimakasih Sudah Login";
            }
           
            $this->KirimWa_model->kirimwa('08157045860', $pesan);
        } catch (Exception $e) {
            log_message('error', 'Anekadharmamasuk WA notify failed: ' . $e->getMessage());
        }
    }

    function get_client_ip()
    {
        return login_client_ip();
    }

    function get_client_browser()
    {
        $browser = '';
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'Netscape'))
            $browser = 'Netscape';
        else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox'))
            $browser = 'Firefox';
        else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome'))
            $browser = 'Chrome';
        else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera'))
            $browser = 'Opera';
        else if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE'))
            $browser = 'Internet Explorer';
        else
            $browser = 'Other';
        return $browser;
    }
}
