<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Userlevel extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('User_level_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    public function index()
    {
        $this->template->load('template', 'userlevel/tbl_user_level_list');
    }

    public function json()
    {
        header('Content-Type: application/json');
        echo $this->User_level_model->json();
    }

    public function read($id)
    {
        $row = $this->User_level_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id_user_level' => $row->id_user_level,
                'nama_level' => $row->nama_level,
            );
            $this->template->load('template', 'userlevel/tbl_user_level_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('userlevel'));
        }
    }

    function akses()
    {
        $data['level'] = $this->db->get_where('tbl_user_level', array('id_user_level' =>  $this->uri->segment(3)))->row_array();
        $data['menu'] = $this->db->get('tbl_menu')->result();
        $this->template->load('template', 'userlevel/akses', $data);
    }

    function kasi_akses_ajax()
    {
        $id_menu        = $_GET['id_menu'];
        $id_user_level  = $_GET['level'];
        // chek data
        $params = array('id_menu' => $id_menu, 'id_user_level' => $id_user_level);
        $akses = $this->db->get_where('tbl_hak_akses', $params);
        if ($akses->num_rows() < 1) {
            // insert data baru
            $this->db->insert('tbl_hak_akses', $params);
        } else {
            $this->db->where('id_menu', $id_menu);
            $this->db->where('id_user_level', $id_user_level);
            $this->db->delete('tbl_hak_akses');
        }
    }

    public function akses_keuangan($id_user_level = null)
    {
        $id_user_level = (int) $id_user_level;
        $level = $this->db->get_where('tbl_user_level', array('id_user_level' => $id_user_level))->row_array();
        if (!$level) {
            $this->session->set_flashdata('message', 'Level user tidak ditemukan.');
            redirect(site_url('userlevel'));
        }

        $status = hak_akses_keuangan_level_status($this, $id_user_level);
        $groups = array();
        foreach (hak_akses_keuangan_main_menu_ids() as $parent_id) {
            $parent = $this->db->get_where('menu', array('id' => $parent_id))->row_array();
            $items = array();
            foreach (hak_akses_keuangan_submenu_rows($this) as $sub) {
                if ((int) $sub->is_parent !== (int) $parent_id) {
                    continue;
                }
                $items[] = array(
                    'id' => $sub->id,
                    'name' => $sub->name,
                    'link' => $sub->link,
                    'granted' => hak_akses_keuangan_has_level_grant($this, $id_user_level, $sub->id),
                );
            }
            $groups[] = array(
                'parent_id' => $parent_id,
                'parent_name' => $parent ? $parent['name'] : ('Menu #' . $parent_id),
                'items' => $items,
            );
        }

        $data = array(
            'level' => $level,
            'status' => $status,
            'groups' => $groups,
            'is_keuangan_level' => hak_akses_is_keuangan_level($id_user_level),
        );
        $this->template->load('template', 'userlevel/akses_keuangan', $data);
    }

    public function grant_keuangan_action($id_user_level = null)
    {
        $id_user_level = (int) $id_user_level;
        $granted = hak_akses_keuangan_grant_level($this, $id_user_level);
        $synced = hak_akses_keuangan_sync_all_users_by_level($this, $id_user_level);
        $this->session->set_flashdata(
            'message',
            'Paket Keuangan diberikan: ' . $granted . ' menu level, ' . $synced . ' sinkron ke user.'
        );
        redirect(site_url('userlevel/akses_keuangan/' . $id_user_level));
    }

    public function revoke_keuangan_action($id_user_level = null)
    {
        $id_user_level = (int) $id_user_level;
        $removed = hak_akses_keuangan_revoke_level($this, $id_user_level);
        $this->session->set_flashdata('message', 'Paket Keuangan dicabut: ' . $removed . ' hak akses level.');
        redirect(site_url('userlevel/akses_keuangan/' . $id_user_level));
    }

    public function sync_keuangan_users_action($id_user_level = null)
    {
        $id_user_level = (int) $id_user_level;
        $synced = hak_akses_keuangan_sync_all_users_by_level($this, $id_user_level);
        $this->session->set_flashdata('message', 'Sinkron paket keuangan ke user: ' . $synced . ' menu.');
        redirect(site_url('userlevel/akses_keuangan/' . $id_user_level));
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('userlevel/create_action'),
            'id_user_level' => set_value('id_user_level'),
            'nama_level' => set_value('nama_level'),
        );
        $this->template->load('template', 'userlevel/tbl_user_level_form', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'nama_level' => $this->input->post('nama_level', TRUE),
            );

            $this->User_level_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('userlevel'));
        }
    }

    public function update($id)
    {
        $row = $this->User_level_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('userlevel/update_action'),
                'id_user_level' => set_value('id_user_level', $row->id_user_level),
                'nama_level' => set_value('nama_level', $row->nama_level),
            );
            $this->template->load('template', 'userlevel/tbl_user_level_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('userlevel'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_user_level', TRUE));
        } else {
            $data = array(
                'nama_level' => $this->input->post('nama_level', TRUE),
            );

            $this->User_level_model->update($this->input->post('id_user_level', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('userlevel'));
        }
    }

    public function delete($id)
    {
        $row = $this->User_level_model->get_by_id($id);

        if ($row) {
            $this->User_level_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('userlevel'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('userlevel'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('nama_level', 'nama level', 'trim|required');

        $this->form_validation->set_rules('id_user_level', 'id_user_level', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "tbl_user_level.xls";
        $judul = "tbl_user_level";
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
        xlsWriteLabel($tablehead, $kolomhead++, "Nama Level");

        foreach ($this->User_level_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->nama_level);

            $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

    public function word()
    {
        header("Content-type: application/vnd.ms-word");
        header("Content-Disposition: attachment;Filename=tbl_user_level.doc");

        $data = array(
            'tbl_user_level_data' => $this->User_level_model->get_all(),
            'start' => 0
        );

        $this->load->view('userlevel/tbl_user_level_doc', $data);
    }
}
