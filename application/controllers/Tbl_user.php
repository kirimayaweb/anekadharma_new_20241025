<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_user extends CI_Controller
{

    public $table_tbl_tingkat_by_user = 'tbl_tingkat_by_user';
    public $table_tbl_user = 'tbl_user';
    public $email = 'email';


    function __construct()
    {
        parent::__construct();
        // is_login();
        $this->load->model(array('Tbl_user_model', 'Sys_tingkat_model', 'Menu_model', 'Tbl_hak_akses_model'));
        // $this->load->model(array('Tbl_user_model','Trans_cetak_model', 'Tbl_stok_barang_detail_model', 'Trans_cetakinput_detail_model', 'Tbl_produk_model', 'Tbl_produk_mapel_referensi_model', 'General_login'));
        $this->load->library('form_validation');
    }

    public function indexXXXX()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->uri->segment(3));

        if ($q <> '') {
            $config['base_url'] = base_url() . '.php/c_url/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'index.php/tbl_user/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'index.php/tbl_user/index/';
            $config['first_url'] = base_url() . 'index.php/tbl_user/index/';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = FALSE;
        $config['total_rows'] = $this->Tbl_user_model->total_rows($q);
        $tbl_user = $this->Tbl_user_model->get_limit_data($config['per_page'], $start, $q);
        $config['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
        $config['full_tag_close'] = '</ul>';
        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'tbl_user_data' => $tbl_user,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        // $this->template->load('template','tbl_user/tbl_user_list', $data);
        // $this->template->load('template/adminlte310', 'tbl_user/tbl_user_list', $data);
        $this->template->load('template/adminlte310', 'tbl_user/adminlte310_tbl_user_list', $data);
    }

    public function index()
    {


        $sess_id_user_level_active = $this->session->userdata('sess_id_user_level');

        // print_r($sess_id_user_level_active);
        // print_r("<br/>");
        // die;

        if ($sess_id_user_level_active == "1" or $sess_id_user_level_active == "2") {
            $data_user = $this->Tbl_user_model->get_all();

            // print_r("admin");
            // print_r("<br/>");
            // print_r($data_user);
            // print_r("<br/>");
    
        } else {
            $sess_iduser_active = $this->session->userdata('sess_iduser');
            $data_user = $this->Tbl_user_model->get_by_id_result($sess_iduser_active);

            // print_r("BUKAN admin");
            // print_r("<br/>");
            // print_r($sess_iduser_active);
            // print_r("<br/>");
            // print_r($data_user);
            // print_r("<br/>");

        }



        $data = array(
            'data_user' => $data_user,
            'action' => site_url('sys_unit/cari_unit'),
        );

        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_user/adminlte310_tbl_user_list', $data);
    }


    public function read($id)
    {
        $row = $this->Tbl_user_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id_users' => $row->id_users,
                'uuid_users' => $row->uuid_users,
                'date_input' => $row->date_input,
                'full_name' => $row->full_name,
                'email' => $row->email,
                'password' => $row->password,
                'images' => $row->images,
                'id_user_level' => $row->id_user_level,
                'is_aktif' => $row->is_aktif,
            );
            $this->template->load('template', 'tbl_user/tbl_user_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_user'));
        }
    }

    public function create()
    {
        $menu = $this->Menu_model->get_all();
        $data = array(
            'button' => 'Simpan',
            'action' => site_url('tbl_user/create_action'),
            'id_users' => set_value('id_users'),
            'uuid_users' => set_value('uuid_users'),
            'date_input' => set_value('date_input'),
            'full_name' => set_value('full_name'),
            'email' => set_value('email'),
            'password' => set_value('password'),
            'images' => set_value('images'),
            'no_hp' => set_value('no_hp'),
            'id_user_level' => set_value('id_user_level'),
            'is_aktif' => set_value('is_aktif'),
            'is_update' => "FALSE",
            'menu_data' => $menu,
        );

        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_user/adminlte310_tbl_user_form', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {

            //Check apakah username sudah ada ? , jika sudah ada maka kembali ke halaman create()

            $email      = $this->input->post('email');
            // query chek users
            $this->db->where('email', $email);
            //$this->db->where('password',  $test);
            $users = $this->db->get('tbl_user');

            if ($users->num_rows() > 0) {

                $x_error = "Email sudah ada ada dalam sistem";
                $this->session->set_flashdata('ada_email_sama', $x_error);

                echo '<script type="text/javascript">window.location.href = "javascript:history.go(-1)";</script>';
            } else {

                if ($this->input->post('id_user_level', TRUE) == 1 or $this->input->post('id_user_level', TRUE) == 2) {
                    $status_tagihan_x = 1;

                    // SIMPAN KE TABEL TBL_USER , dengan status tagihan 0 , 1 dst
                    $data = array(
                        'date_input' => date('Y-m-d H:i:s'),
                        'full_name' => $this->input->post('full_name', TRUE),
                        'email' => $this->input->post('email', TRUE),
                        'password' => password_hash($this->input->post('password', TRUE), PASSWORD_DEFAULT),
                        'no_hp' => $this->input->post('no_hp', TRUE),
                        'id_user_level' => $this->input->post('id_user_level', TRUE),
                        // 'status_tagihan' => $status_tagihan_x,
                        'is_aktif' => $this->input->post('is_aktif', TRUE),
                    );
                } else {
                    // SIMPAN KE TABEL TBL_USER  --------->>> TANPA STATUS TAGIHAN (KASIR DAN GUES)
                    $data = array(
                        'date_input' => date('Y-m-d H:i:s'),
                        'full_name' => $this->input->post('full_name', TRUE),
                        'email' => $this->input->post('email', TRUE),
                        'password' => password_hash($this->input->post('password', TRUE), PASSWORD_DEFAULT),
                        'no_hp' => $this->input->post('no_hp', TRUE),
                        'id_user_level' => $this->input->post('id_user_level', TRUE),
                        // 'status_tagihan' => $status_tagihan_x,
                        'is_aktif' => $this->input->post('is_aktif', TRUE),
                    );
                }



                // print_r($data);
                // die;

                $this->Tbl_user_model->insert($data);
                $this->session->set_flashdata('message', 'Create Record Success 2');



                redirect(site_url('Tbl_user'));
            }
        }
    }

    public function update_menu_per_user($id_user = null, $id_menu = null, $kondisi_menu = null)
    {

        $row = $this->Tbl_user_model->get_by_id($id_user);

        // if($id_user  == $id_user_active){}

        // $id_user_active = $this->session->userdata('sess_iduser');

        if ($row) {

            // Cek di tbl_hak_akses apakah ada menu, jika tidak ada maka insert
            // dan
            // jika ada maka update menjadi $kondisi_menu

            // Get Main menu berdasarkan id_menu
            $this->db->where('id',  $id_menu);
            $get_main_menu = $this->db->get('menu')->row();

            // Get id_user_level berdasarkan id_user
            $this->db->where('id_users',  $id_user);
            $get_user_level = $this->db->get('tbl_user')->row();

            $this->db->where('id_menu', $id_menu);
            $this->db->where('id_user',  $id_user);
            $list_menu_hak_akses = $this->db->get('tbl_hak_akses');
            // $list_menu_hak_akses_cek = $this->db->get('tbl_hak_akses')->row();

            if ($list_menu_hak_akses->num_rows() > 0) {
                // update

                $data = array(
                    'id_user' => $id_user,
                    'id_user_level' => $get_user_level->id_user_level,
                    'main_menu' => 0,
                    'id_menu' => $id_menu,
                );

                $this->Tbl_hak_akses_model->update($list_menu_hak_akses->row()->id, $data);
            } else {
                // insert

                $data = array(
                    'id_user' => $id_user,
                    'id_user_level' => $get_user_level->id_user_level,
                    'main_menu' => $get_main_menu->is_parent,
                    'id_menu' => $id_menu,
                );

                // print_r($data);
                // die;

                $this->Tbl_hak_akses_model->insert($data);
            }
        }
        // die;
        redirect('Tbl_user/update/' . $id_user);
    }


    public function update($id)
    {
        $row = $this->Tbl_user_model->get_by_id($id);





        if ($row) {

            $menu = $this->Menu_model->get_all();

            $data = array(
                'button' => 'Update',
                'action' => site_url('tbl_user/update_action'),
                'id_users' => set_value('id_users', $row->id_users),
                'uuid_users' => set_value('uuid_users', $row->uuid_users),
                'date_input' => set_value('date_input', $row->date_input),
                'full_name' => set_value('full_name', $row->full_name),
                'email' => set_value('email', $row->email),
                'password' => set_value('password', $row->password),
                'images' => set_value('images', $row->images),
                'no_hp' => set_value('no_hp', $row->no_hp),
                'id_user_level' => set_value('id_user_level', $row->id_user_level),
                'is_aktif' => set_value('is_aktif', $row->is_aktif),
                // 'sys_tingkat_data' => $sys_tingkat,
                'is_update' => "TRUE",
                'menu_data' => $menu,
            );



            // $this->template->load('template/adminlte310', 'tbl_user/adminlte310_tbl_user_form', $data);
            $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_user/adminlte310_tbl_user_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('Tbl_user'));
        }
    }

    public function update_action()
    {
        $this->_rules_update();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_users', TRUE));
        } else {


            if ($this->input->post('id_user_level', TRUE) == 1 or $this->input->post('id_user_level', TRUE) == 2) {
                $status_tagihan_x = 1;
            } else {
                $status_tagihan_x = 0;
            }

            if ($this->input->post('password', TRUE)) {
                // print_r(" Ada Password");
                $data = array(
                    'uuid_users' => $this->input->post('uuid_users', TRUE),
                    'date_input' => $this->input->post('date_input', TRUE),
                    'full_name' => $this->input->post('full_name', TRUE),
                    'email' => $this->input->post('email', TRUE),
                    'password' => password_hash($this->input->post('password', TRUE), PASSWORD_DEFAULT),
                    'images' => $this->input->post('images', TRUE),
                    'no_hp' => $this->input->post('no_hp', TRUE),
                    'id_user_level' => $this->input->post('id_user_level', TRUE),
                    'status_tagihan' => $status_tagihan_x,
                    'is_aktif' => $this->input->post('is_aktif', TRUE),
                );
            } else {
                // print_r("TIDAK Ada Password");
                $data = array(
                    'uuid_users' => $this->input->post('uuid_users', TRUE),
                    'date_input' => $this->input->post('date_input', TRUE),
                    'full_name' => $this->input->post('full_name', TRUE),
                    'email' => $this->input->post('email', TRUE),
                    // 'password' => password_hash($this->input->post('password', TRUE), PASSWORD_DEFAULT),
                    'images' => $this->input->post('images', TRUE),
                    'no_hp' => $this->input->post('no_hp', TRUE),
                    'id_user_level' => $this->input->post('id_user_level', TRUE),
                    'status_tagihan' => $status_tagihan_x,
                    'is_aktif' => $this->input->post('is_aktif', TRUE),
                );
            }


            $this->Tbl_user_model->update($this->input->post('id_users', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('tbl_user'));
        }
    }

    public function update_tingkat_user($id_users = null, $uuid_tingkat = null, $statusTAMPIL = null)
    {

        // print_r($id_users);
        // print_r("<br/>");
        // print_r($uuid_tingkat);
        // print_r("<br/>");
        // print_r($statusTAMPIL);
        // print_r("<br/>");


        $this->db->where('id_users', $id_users);
        $this->db->where('uuid_tingkat', $uuid_tingkat);
        $get_tingkat_users = $this->db->get('tbl_tingkat_by_user');

        if ($get_tingkat_users->num_rows() > 0) {
            //proses update
            if ($statusTAMPIL == "TAMPIL") {
                $data = array(
                    'status_tampil' => "TIDAKTAMPIL",
                );
            } else {
                $data = array(
                    'status_tampil' => "TAMPIL",
                );
            }
            $this->Tbl_user_model->update_user_by_tingkat($id_users, $uuid_tingkat, $data);
        } else {
            // echo anchor(site_url('tbl_user/update_tingkat_user/' . $id_users. '/' . $sys_tingkat->uuid_tingkat . '/TIDAKTAMPIL'), '<i class="fa fa-pencil-square-o" aria-hidden="true">TIDAK TAMPIL</i>', 'class="btn btn-danger btn-sm"');
            // input record berdasar id_users dan uuid_tingkat

            // get tingkat-system & tingkat
            $this->db->where('uuid_tingkat', $uuid_tingkat);
            $get_tingkat = $this->db->get('sys_tingkat');


            $data = array(
                'id_users' => $id_users,
                'uuid_tingkat' => $uuid_tingkat,
                'status_tampil' => "TAMPIL",
                'tingkat_system' => $get_tingkat->row()->tingkat_system,
                'tingkat' => $get_tingkat->row()->tingkat,
            );
            $this->Tbl_user_model->insert_user_by_tingkat($data);
        }
        redirect(site_url('tbl_user/update/' . $id_users));
    }

    public function delete($id)
    {
        $row = $this->Tbl_user_model->get_by_id($id);

        if ($row) {
            $this->Tbl_user_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('tbl_user'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_user'));
        }
    }

    public function _rules()
    {
        // $this->form_validation->set_rules('uuid_users', 'uuid users', 'trim|required');
        // $this->form_validation->set_rules('date_input', 'date input', 'trim|required');
        $this->form_validation->set_rules('full_name', 'full name', 'trim|required');
        $this->form_validation->set_rules('email', 'email', 'trim|required');
        $this->form_validation->set_rules('password', 'password', 'trim|required');
        // $this->form_validation->set_rules('images', 'images', 'trim|required');
        $this->form_validation->set_rules('id_user_level', 'id user level', 'trim|required');
        $this->form_validation->set_rules('is_aktif', 'is aktif', 'trim|required');

        $this->form_validation->set_rules('id_users', 'id_users', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
    public function _rules_update()
    {
        // $this->form_validation->set_rules('uuid_users', 'uuid users', 'trim|required');
        // $this->form_validation->set_rules('date_input', 'date input', 'trim|required');
        $this->form_validation->set_rules('full_name', 'full name', 'trim|required');
        $this->form_validation->set_rules('email', 'email', 'trim|required');
        // $this->form_validation->set_rules('password', 'password', 'trim|required');
        // $this->form_validation->set_rules('images', 'images', 'trim|required');
        $this->form_validation->set_rules('id_user_level', 'id user level', 'trim|required');
        $this->form_validation->set_rules('is_aktif', 'is aktif', 'trim|required');

        $this->form_validation->set_rules('id_users', 'id_users', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "tbl_user.xls";
        $judul = "tbl_user";
        $tablehead = 0;
        $tablebody = 1;
        $nourut = 1;
        //penulisan header
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=" . $namaFile . "");
        header("Content-Transfer-Encoding: binary ");

        xlsBOF();

        $kolomhead = 0;
        xlsWriteLabel($tablehead, $kolomhead++, "No");
        xlsWriteLabel($tablehead, $kolomhead++, "Uuid Users");
        xlsWriteLabel($tablehead, $kolomhead++, "Date Input");
        xlsWriteLabel($tablehead, $kolomhead++, "Full Name");
        xlsWriteLabel($tablehead, $kolomhead++, "Email");
        xlsWriteLabel($tablehead, $kolomhead++, "Password");
        xlsWriteLabel($tablehead, $kolomhead++, "Images");
        xlsWriteLabel($tablehead, $kolomhead++, "Id User Level");
        xlsWriteLabel($tablehead, $kolomhead++, "Is Aktif");

        foreach ($this->Tbl_user_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->uuid_users);
            xlsWriteLabel($tablebody, $kolombody++, $data->date_input);
            xlsWriteLabel($tablebody, $kolombody++, $data->full_name);
            xlsWriteLabel($tablebody, $kolombody++, $data->email);
            xlsWriteLabel($tablebody, $kolombody++, $data->password);
            xlsWriteLabel($tablebody, $kolombody++, $data->images);
            xlsWriteLabel($tablebody, $kolombody++, $data->id_user_level);
            xlsWriteLabel($tablebody, $kolombody++, $data->is_aktif);

            $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

    public function setting_user()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->uri->segment(3));

        if ($q <> '') {
            $config['base_url'] = base_url() . '.php/c_url/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'index.php/tbl_user/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'index.php/tbl_user/index/';
            $config['first_url'] = base_url() . 'index.php/tbl_user/index/';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = FALSE;
        $config['total_rows'] = $this->Tbl_user_model->total_rows($q);
        $tbl_user = $this->Tbl_user_model->get_data_role_admin($config['per_page'], $start, $q);
        $config['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
        $config['full_tag_close'] = '</ul>';
        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'tbl_user_data' => $tbl_user,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );

        $this->template->load('template/adminlte310', 'tbl_user/adminlte310_tbl_user_setting_admin', $data);
    }

    public function setting_admin_tagihan($id_user = null)
    {
        $row = $this->Tbl_user_model->get_by_id($id_user);

        if ($row) {

            if ($row->status_tagihan == 1) {
                $data = array(
                    'status_tagihan' => 0,
                );
            } else {
                $data = array(
                    'status_tagihan' => 1,
                );
            }
            $this->Tbl_user_model->update($id_user, $data);
        }


        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->uri->segment(3));

        if ($q <> '') {
            $config['base_url'] = base_url() . '.php/c_url/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'index.php/tbl_user/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'index.php/tbl_user/index/';
            $config['first_url'] = base_url() . 'index.php/tbl_user/index/';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = FALSE;
        $config['total_rows'] = $this->Tbl_user_model->total_rows($q);
        $tbl_user = $this->Tbl_user_model->get_data_role_admin($config['per_page'], $start, $q);
        $config['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
        $config['full_tag_close'] = '</ul>';
        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'tbl_user_data' => $tbl_user,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );

        $this->template->load('template/adminlte310', 'tbl_user/adminlte310_tbl_user_setting_admin', $data);
    }
}

/* End of file Tbl_user.php */
/* Location: ./application/controllers/Tbl_user.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2022-04-03 02:32:50 */
/* http://harviacode.com */