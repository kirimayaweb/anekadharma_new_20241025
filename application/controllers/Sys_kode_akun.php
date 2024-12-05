<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sys_kode_akun extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Sys_kode_akun_model');
        $this->load->library('form_validation');
    }

    public function indexBU()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        if ($q <> '') {
            $config['base_url'] = base_url() . 'sys_kode_akun/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'sys_kode_akun/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'sys_kode_akun/index.html';
            $config['first_url'] = base_url() . 'sys_kode_akun/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Sys_kode_akun_model->total_rows($q);
        $sys_kode_akun = $this->Sys_kode_akun_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'sys_kode_akun_data' => $sys_kode_akun,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->load->view('sys_kode_akun/sys_kode_akun_list', $data);
    }

    public function index()
    {
        $sys_kode_akun = $this->Sys_kode_akun_model->get_all();
        // $start = 0;
        $data = array(
            'sys_kode_akun_data' => $sys_kode_akun,
            // 'start' => $start,
            // 'status_laporan' => $status_laporan,
        );
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_kode_akun/adminlte310_sys_kode_akun_list', $data);
    }



    public function read($id)
    {
        $row = $this->Sys_kode_akun_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'uuid_kode_akun' => $row->uuid_kode_akun,
                'kode_akun' => $row->kode_akun,
                'nama_akun' => $row->nama_akun,
                'group' => $row->group,
            );
            $this->load->view('sys_kode_akun/sys_kode_akun_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_kode_akun'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'SIMPAN',
            'action' => site_url('sys_kode_akun/create_action'),
            'id' => set_value('id'),
            'uuid_kode_akun' => set_value('uuid_kode_akun'),
            'kode_akun' => set_value('kode_akun'),
            'nama_akun' => set_value('nama_akun'),
            'group' => set_value('group'),
        );
        // $this->load->view('sys_kode_akun/sys_kode_akun_form', $data);
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_kode_akun/adminlte310_sys_kode_akun_form', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                // 'uuid_kode_akun' => $this->input->post('uuid_kode_akun', TRUE),
                'kode_akun' => $this->input->post('kode_akun', TRUE),
                'nama_akun' => $this->input->post('nama_akun', TRUE),
                'group' => $this->input->post('group', TRUE),
            );

            $this->Sys_kode_akun_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('sys_kode_akun'));
        }
    }

    public function update($id)
    {
        $row = $this->Sys_kode_akun_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('sys_kode_akun/update_action'),
                'id' => set_value('id', $row->id),
                'uuid_kode_akun' => set_value('uuid_kode_akun', $row->uuid_kode_akun),
                'kode_akun' => set_value('kode_akun', $row->kode_akun),
                'nama_akun' => set_value('nama_akun', $row->nama_akun),
                'group' => set_value('group', $row->group),
            );
            // $this->load->view('sys_kode_akun/sys_kode_akun_form', $data);
            $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_kode_akun/adminlte310_sys_kode_akun_form', $data);

        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_kode_akun'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                // 'uuid_kode_akun' => $this->input->post('uuid_kode_akun', TRUE),
                'kode_akun' => $this->input->post('kode_akun', TRUE),
                'nama_akun' => $this->input->post('nama_akun', TRUE),
                'group' => $this->input->post('group', TRUE),
            );

            $this->Sys_kode_akun_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('sys_kode_akun'));
        }
    }

    public function delete($id)
    {
        $row = $this->Sys_kode_akun_model->get_by_id($id);

        if ($row) {
            $this->Sys_kode_akun_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('sys_kode_akun'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_kode_akun'));
        }
    }

    public function _rules()
    {
        // $this->form_validation->set_rules('uuid_kode_akun', 'uuid kode akun', 'trim|required');
        $this->form_validation->set_rules('kode_akun', 'kode akun', 'trim|required');
        // $this->form_validation->set_rules('nama_akun', 'nama akun', 'trim|required');
        // $this->form_validation->set_rules('group', 'group', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
}

/* End of file Sys_kode_akun.php */
/* Location: ./application/controllers/Sys_kode_akun.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-10-21 14:26:01 */
/* http://harviacode.com */