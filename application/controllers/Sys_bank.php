<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sys_bank extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Sys_bank_model');
        $this->load->library('form_validation');
    }

    public function indexbu()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        if ($q <> '') {
            $config['base_url'] = base_url() . 'sys_bank/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'sys_bank/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'sys_bank/index.html';
            $config['first_url'] = base_url() . 'sys_bank/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Sys_bank_model->total_rows($q);
        $sys_bank = $this->Sys_bank_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'sys_bank_data' => $sys_bank,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->load->view('sys_bank/sys_bank_list', $data);
    }

    public function index() {
        $sys_bank_data = $this->Sys_bank_model->get_all();
        // $start = 0;
        $data = array(
            'sys_bank_data' => $sys_bank_data,
            // 'start' => $start,
            // 'status_laporan' => $status_laporan,
        );
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_bank/adminlte310_sys_bank_list', $data);
   
    }


    public function read($id)
    {
        $row = $this->Sys_bank_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'uuid_bank' => $row->uuid_bank,
                'kode_bank' => $row->kode_bank,
                'nama_bank' => $row->nama_bank,
                'nmr_rekening' => $row->nmr_rekening,
            );
            $this->load->view('sys_bank/sys_bank_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_bank'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'SIMPAN',
            'action' => site_url('sys_bank/create_action'),
            'id' => set_value('id'),
            'uuid_bank' => set_value('uuid_bank'),
            'kode_bank' => set_value('kode_bank'),
            'nama_bank' => set_value('nama_bank'),
            'nmr_rekening' => set_value('nmr_rekening'),
        );
        // $this->load->view('sys_bank/sys_bank_form', $data);
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_bank/adminlte310_sys_bank_form', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                // 'uuid_bank' => $this->input->post('uuid_bank', TRUE),
                'kode_bank' => $this->input->post('kode_bank', TRUE),
                'nama_bank' => $this->input->post('nama_bank', TRUE),
                'nmr_rekening' => $this->input->post('nmr_rekening', TRUE),
            );

            $this->Sys_bank_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('sys_bank'));
        }
    }

    public function update($id)
    {
        $row = $this->Sys_bank_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('sys_bank/update_action'),
                'id' => set_value('id', $row->id),
                'uuid_bank' => set_value('uuid_bank', $row->uuid_bank),
                'kode_bank' => set_value('kode_bank', $row->kode_bank),
                'nama_bank' => set_value('nama_bank', $row->nama_bank),
                'nmr_rekening' => set_value('nmr_rekening', $row->nmr_rekening),
            );
            // $this->load->view('sys_bank/sys_bank_form', $data);
            $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_bank/adminlte310_sys_bank_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_bank'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                // 'uuid_bank' => $this->input->post('uuid_bank', TRUE),
                'kode_bank' => $this->input->post('kode_bank', TRUE),
                'nama_bank' => $this->input->post('nama_bank', TRUE),
                'nmr_rekening' => $this->input->post('nmr_rekening', TRUE),
            );

            $this->Sys_bank_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('sys_bank'));
        }
    }

    public function delete($id)
    {
        $row = $this->Sys_bank_model->get_by_id($id);

        if ($row) {
            $this->Sys_bank_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('sys_bank'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_bank'));
        }
    }

    public function _rules()
    {
        // $this->form_validation->set_rules('uuid_bank', 'uuid bank', 'trim|required');
        $this->form_validation->set_rules('kode_bank', 'kode bank', 'trim|required');
        // $this->form_validation->set_rules('nama_bank', 'nama bank', 'trim|required');
        // $this->form_validation->set_rules('nmr_rekening', 'nmr rekening', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
}

/* End of file Sys_bank.php */
/* Location: ./application/controllers/Sys_bank.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-10-21 15:18:03 */
/* http://harviacode.com */