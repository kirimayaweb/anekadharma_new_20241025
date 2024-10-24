<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sys_status_transaksi extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Sys_status_transaksi_model');
        $this->load->library('form_validation');
    }

    public function indexXXXX()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        if ($q <> '') {
            $config['base_url'] = base_url() . 'sys_status_transaksi/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'sys_status_transaksi/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'sys_status_transaksi/index.html';
            $config['first_url'] = base_url() . 'sys_status_transaksi/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Sys_status_transaksi_model->total_rows($q);
        $sys_status_transaksi = $this->Sys_status_transaksi_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'sys_status_transaksi_data' => $sys_status_transaksi,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->load->view('sys_status_transaksi/sys_status_transaksi_list', $data);
    }


    public function index()
    {
        // $Tbl_pembelian = $this->Tbl_pembelian_model->get_all();
        $sys_status_transaksi = $this->Sys_status_transaksi_model->get_all();
        $start = 0;
        $data = array(
            'sys_status_transaksi_data' => $sys_status_transaksi,
            'start' => $start,
        );
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_status_transaksi/adminlte310_sys_status_transaksi_list', $data);
    }


    public function read($id)
    {
        $row = $this->Sys_status_transaksi_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'uuid_status_transaksi' => $row->uuid_status_transaksi,
                'status' => $row->status,
                'keterangan' => $row->keterangan,
            );
            $this->load->view('sys_status_transaksi/sys_status_transaksi_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_status_transaksi'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('sys_status_transaksi/create_action'),
            'id' => set_value('id'),
            'uuid_status_transaksi' => set_value('uuid_status_transaksi'),
            'status' => set_value('status'),
            'keterangan' => set_value('keterangan'),
        );
        // $this->load->view('sys_status_transaksi/sys_status_transaksi_form', $data);

        
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_status_transaksi/adminlte310_sys_status_transaksi_form', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                // 'uuid_status_transaksi' => $this->input->post('uuid_status_transaksi', TRUE),
                'status' => $this->input->post('status', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
            );

            $this->Sys_status_transaksi_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('sys_status_transaksi'));
        }
    }

    public function update($id)
    {
        $row = $this->Sys_status_transaksi_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('sys_status_transaksi/update_action'),
                'id' => set_value('id', $row->id),
                'uuid_status_transaksi' => set_value('uuid_status_transaksi', $row->uuid_status_transaksi),
                'status' => set_value('status', $row->status),
                'keterangan' => set_value('keterangan', $row->keterangan),
            );
            $this->load->view('sys_status_transaksi/sys_status_transaksi_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_status_transaksi'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                // 'uuid_status_transaksi' => $this->input->post('uuid_status_transaksi', TRUE),
                'status' => $this->input->post('status', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
            );

            $this->Sys_status_transaksi_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('sys_status_transaksi'));
        }
    }

    public function delete($id)
    {
        $row = $this->Sys_status_transaksi_model->get_by_id($id);

        if ($row) {
            $this->Sys_status_transaksi_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('sys_status_transaksi'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_status_transaksi'));
        }
    }

    public function _rules()
    {
        // $this->form_validation->set_rules('uuid_status_transaksi', 'uuid status transaksi', 'trim|required');
        $this->form_validation->set_rules('status', 'status', 'trim|required');
        // $this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
}

/* End of file Sys_status_transaksi.php */
/* Location: ./application/controllers/Sys_status_transaksi.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-10-12 10:19:18 */
/* http://harviacode.com */