<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model(array('User_model', 'Tbl_user_model'));
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    public function index()
    {



        $_SESSION['thn_selected'] = $tahun_selected;
        $_SESSION['semester_selected'] = $semester_selected;
        // $_SESSION['listcover_selected'] = $listcover_selected;


        if ($laporan_status) {
            $status_laporan = $laporan_status;
        } else {
            $status_laporan = "laporan";
        }


        // LABA-RUGI

        $sql = "SELECT year(`tgl_po`) as tahun_neraca FROM `tbl_pembelian` GROUP by year(`tgl_po`) order by  `tgl_po` DESC";

        $tahun_labarugi_data = $this->db->query($sql)->result();

        $sql = "SELECT year(`tgl_po`) as tahun_neraca, month(`tgl_po`) as bulan_neraca FROM `tbl_pembelian` GROUP by year(`tgl_po`), month(`tgl_po`) order by `tgl_po` DESC;";

        $bulan_neraca_labarugi_data = $this->db->query($sql)->result();



        // NERACA

        $sql = "SELECT year(`tgl_po`) as tahun_neraca FROM `tbl_pembelian` GROUP by year(`tgl_po`) order by  `tgl_po` DESC";

        $tahun_neraca_data = $this->db->query($sql)->result();


        $sql = "SELECT year(`tgl_po`) as tahun_neraca, month(`tgl_po`) as bulan_neraca FROM `tbl_pembelian` GROUP by year(`tgl_po`), month(`tgl_po`) order by `tgl_po` DESC;";

        $bulan_neraca_neraca_data = $this->db->query($sql)->result();

        // print_r($Tbl_neraca_data);
        // print_r("<br/>");
        // print_r("<br/>");

        $start = 0;





        $data = array(
            // 'button' => 'Refresh Tahun dan Semester',
            'action' => site_url('Dashboard'),
            // 'date_sekarang' => $date_sekarang,
            'thn_sekarang' => $_SESSION['thn_selected'],
            'semester_sekarang' => $_SESSION['semester_selected'],
            // 'listcover_sekarang' => $_SESSION['listcover_selected'],
            // 'link_penjualan' => 'Tbl_penjualan/',



            // Laba-rugi
            'Tbl_TAHUN_labarugi_data' => $tahun_labarugi_data,
            'Tbl_BULAN_labarugi_data' => $bulan_neraca_labarugi_data,
            'start' => $start,
            'status_laporan' => $status_laporan,
            'action_input_labarugi_baru' => site_url('Tbl_laba_rugi/labarugi_form_input/'),
            'action_input_labarugi_baru_bulanan' => site_url('Tbl_laba_rugi/labarugi_form_input_bulanan/'),


            // NERACA
            'Tbl_TAHUN_neraca_data' => $tahun_neraca_data,
            'Tbl_BULAN_neraca_data' => $bulan_neraca_neraca_data,
            'start' => $start,
            'status_laporan' => $status_laporan,
            'action_input_neraca_baru' => site_url('Tbl_neraca_data/neraca_form_input/'),
            'action_input_neraca_baru_bulanan' => site_url('Tbl_neraca_data/neraca_form_input_bulanan/'),


        );

        // $this->template->load('template', 'dashboard/dashboard', $data);
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/dashboard/dashboard', $data);
    }


    public function from_login()
    {

        print_r($this->session->userdata('sess_username'));

        // SETTING TAHUN DAN SEMESTER

        $this->session->sess_destroy();
        unset($_SESSION['semester_selected']);
        unset($_SESSION['thn_selected']);

        if (isset($_SESSION['semester_selected']) or isset($_SESSION['thn_selected'])) {

            $tahun_selected = $_SESSION['thn_selected'];
            if (isset($_SESSION['semester_selected'])) {
                $semester_selected = $_SESSION['semester_selected'];
            } else {
                $date_sekarang = date('Y-m-d H:i:s');
                $variabel_date_awal_selected = date(
                    'Y-m-d',
                    strtotime('+0 month', strtotime($date_sekarang))
                );
                $tahun_selected = date('Y', strtotime('+0 month', strtotime($variabel_date_awal_selected)));
                $data_month = date('m', strtotime('+0 month', strtotime($variabel_date_awal_selected)));

                if (($data_month < 3)) {
                    $semester_selected = 2;
                    $tahun_selected = date('Y', strtotime('-1 year', strtotime($variabel_date_awal_selected)));
                } elseif (($data_month > 8)) {
                    $semester_selected = 2;
                    $tahun_selected = date('Y', strtotime('+0 month', strtotime($variabel_date_awal_selected)));
                } else {
                    $semester_selected = 1;
                    $tahun_selected = date('Y', strtotime('+0 month', strtotime($variabel_date_awal_selected)));
                }
            }
        } else {

            $date_sekarang = date('Y-m-d H:i:s');
            $variabel_date_awal_selected = date('Y-m-d', strtotime('+0 month', strtotime($date_sekarang)));
            $tahun_selected = date('Y', strtotime('+0 month', strtotime($variabel_date_awal_selected)));
            $data_month = date('m', strtotime('+0 month', strtotime($variabel_date_awal_selected)));

            if (($data_month < 3)) {
                $semester_selected = 2;
                $tahun_selected = date('Y', strtotime('-1 year', strtotime($variabel_date_awal_selected)));
            } elseif (($data_month > 8)) {
                $semester_selected = 2;
                $tahun_selected = date('Y', strtotime('+0 month', strtotime($variabel_date_awal_selected)));
            } else {
                $semester_selected = 1;
                $tahun_selected = date('Y', strtotime('+0 month', strtotime($variabel_date_awal_selected)));
            }
        }

        $_SESSION['thn_selected'] = $tahun_selected;
        $_SESSION['semester_selected'] = $semester_selected;

        $data = array(
            'button' => 'Refresh Tahun dan Semester',
            'action' => site_url('Dashboard'),
            // 'date_sekarang' => $date_sekarang,
            'thn_sekarang' => $_SESSION['thn_selected'],
            'semester_sekarang' => $_SESSION['semester_selected'],
            'link_penjualan' => 'Trans_penjualan/create_setting',
        );
        // $this->template->load('template', 'dashboard/dashboard', $data);
        print_r($data);
        print_r("<br/>");
        print_r($this->session->userdata('sess_username'));
        $this->template->load('template/adminlte310', 'dashboard/adminlte310_dashboard', $data);
    }


    public function ubah_password()
    {

        $cek_data_stock = $this->db->get_where('tbl_user', array('id_users' => $this->session->userdata('sess_iduser')))->row();

        $data = array(
            'button' => 'Ubah Password',
            'button_no_hp' => 'Simpan Nomor Hp',
            'action' => site_url('Dashboard/ubah_password_action'),
            'action_no_hp' => site_url('Dashboard/no_hp_action'),
            'id' => $cek_data_stock->id_users,
            'username' => $cek_data_stock->email,
            'no_hp' => $cek_data_stock->no_hp,
            // 'password' => $cek_data_stock->email,
        );

        $this->template->load('template/adminlte310', 'dashboard/adminlte310_ubah_password', $data);
    }


    public function ubah_password_action()
    {

        $cek_data_stock = $this->db->get_where('tbl_user', array('id_users' => $this->session->userdata('sess_iduser')))->row();

        $data = array(
            'password' => password_hash($this->input->post('password', TRUE), PASSWORD_DEFAULT),
        );

        $this->Tbl_user_model->update($cek_data_stock->id_users, $data);


        $data = array(
            'button' => 'Ubah Password',
            'button_no_hp' => 'Simpan Nomor Hp',
            'action' => site_url('Dashboard/ubah_password_action'),
            'action_no_hp' => site_url('Dashboard/no_hp_action'),
            'id' => $cek_data_stock->id_users,
            'username' => $cek_data_stock->email,
            'no_hp' => $cek_data_stock->no_hp,
            // 'password' => $cek_data_stock->email,
        );

        $this->template->load('template/adminlte310', 'dashboard/adminlte310_ubah_password', $data);
    }

    public function no_hp_action()
    {

        $cek_data_stock = $this->db->get_where('tbl_user', array('id_users' => $this->session->userdata('sess_iduser')))->row();

        $data = array(
            'no_hp' => $this->input->post('no_hp', TRUE),
        );

        $this->Tbl_user_model->update($cek_data_stock->id_users, $data);

        $cek_data_stock = $this->db->get_where('tbl_user', array('id_users' => $this->session->userdata('sess_iduser')))->row();

        // print_r($cek_data_stock);


        $data = array(
            'button' => 'Ubah Password',
            'button_no_hp' => 'Simpan Nomor Hp',
            'action' => site_url('Dashboard/ubah_password_action'),
            'action_no_hp' => site_url('Dashboard/no_hp_action'),
            'id' => $cek_data_stock->id_users,
            'username' => $cek_data_stock->email,
            'no_hp' => $cek_data_stock->no_hp,
            // 'password' => $cek_data_stock->email,
        );

        $this->template->load('template/adminlte310', 'dashboard/adminlte310_ubah_password', $data);
    }

    public function setting_box()
    {
        $tahun_selected = $this->input->post('tahun_setting', TRUE);
        $semester_selectedX = $this->input->post('semester_selected', TRUE);
        // $date_sekarang = date('Y-m-d H:i:s');
        // $variabel_date_awal_selected = date('Y-m-d', strtotime('+0 month', strtotime($date_sekarang)));
        // $data_year = date('Y', strtotime('+0 month', strtotime($variabel_date_awal_selected)));
        // $data_month = date('m', strtotime('+0 month', strtotime($variabel_date_awal_selected)));
        $_SESSION['thn_selected'] = $tahun_selected;
        $_SESSION['semester_selected'] = $semester_selectedX;

        $data = array(
            'button' => 'Refresh Tahun dan Semester',
            'action' => site_url('Dashboard/setting_box'),
            // 'date_sekarang' => $date_sekarang,
            'thn_sekarang' => $tahun_selected,
            'semester_sekarang' => $semester_selectedX,
            'link_penjualan' => 'Trans_penjualan/create_setting',
        );
        $this->template->load('template', 'dashboard/dashboard', $data);
    }


    public function join_cetak_finishing_gudang() //TRY auto realtime data update on single table auto refresh data
    {
        $query = $this->db->query("SELECT trans_cetak.*, trans_finishing.date_pesan_finishing,trans_gudang.date_pesan_gudang
    FROM trans_cetak 
    JOIN trans_finishing ON trans_cetak.uuid_trans_cetak=trans_finishing.uuid_trans_cetak
    JOIN trans_gudang ON trans_cetak.uuid_trans_cetak=trans_gudang.uuid_trans_cetak
    ");
        print_r($query->result());
        die;
        return $query->result();
    }

    public function dashboard_summary()
    {

        $this->template->load('template/template310_dttables', 'dashboard/dashboard');
    }

    public function user_tdefault()
    {
        $sql = "SELECT * FROM tbl_user";
        $data_user = $this->db->query($sql)->result();
        $data = array(
            // 'username'  => $this->session->userdata('username'),
            // 'company'    => $this->session->userdata('company'),
            // 'id_users'    => $this->session->userdata('id_users'),
            'tbl_penyewa_data' => $data_user
        );


        $this->template->load('template/template_default', 'user/tbl_user_list_dttables', $data);
    }

    public function user_tmptdttablesdefault()
    {
        $sql = "SELECT * FROM tbl_user";
        $data_user = $this->db->query($sql)->result();
        $data = array(
            // 'username'  => $this->session->userdata('username'),
            // 'company'    => $this->session->userdata('company'),
            // 'id_users'    => $this->session->userdata('id_users'),
            'tbl_penyewa_data' => $data_user
        );


        $this->template->load('template/template_default', 'user/tbl_user_list_dttables_def', $data);
    }

    public function user_template3()
    {
        $sql = "SELECT * FROM tbl_user";
        $data_user = $this->db->query($sql)->result();
        $data = array(
            // 'username'  => $this->session->userdata('username'),
            // 'company'    => $this->session->userdata('company'),
            // 'id_users'    => $this->session->userdata('id_users'),
            'tbl_penyewa_data' => $data_user
        );
        $this->template->load('template/template310_dttables', 'user/tbl_user_list_dttables', $data);
    }


    public function cek_data($tingkat = null)
    {
        $this->Modul_data_model->get_ALL_DATA_ARRAY($tingkat);
    }
}
