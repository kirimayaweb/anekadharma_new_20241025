<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sys_gudang extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Sys_gudang_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    public function index()
    {
        // $this->load->view('sys_gudang/sys_gudang_list');

        $data_gudang = $this->Sys_gudang_model->get_all();

        $data = array(
            'data_gudang' => $data_gudang,
            'action' => site_url('sys_unit/cari_unit'),
        );




        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_gudang/adminlte310_gudang_list', $data);
    }

    public function json()
    {
        header('Content-Type: application/json');
        echo $this->Sys_gudang_model->json();
    }

    public function read($id)
    {
        $row = $this->Sys_gudang_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'uuid_gudang' => $row->uuid_gudang,
                'kode_gudang' => $row->kode_gudang,
                'nama_gudang' => $row->nama_gudang,
                'alamat' => $row->alamat,
                'keterangan' => $row->keterangan,
            );
            $this->load->view('sys_gudang/sys_gudang_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_gudang'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('sys_gudang/create_action'),
            'id' => set_value('id'),
            'uuid_gudang' => set_value('uuid_gudang'),
            'kode_gudang' => set_value('kode_gudang'),
            'nama_gudang' => set_value('nama_gudang'),
            'alamat' => set_value('alamat'),
            'keterangan' => set_value('keterangan'),
        );
        // $this->load->view('sys_gudang/sys_gudang_form', $data);
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_gudang/adminlte310_gudang_form', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                // 'uuid_gudang' => $this->input->post('uuid_gudang',TRUE),
                'kode_gudang' => $this->input->post('kode_gudang', TRUE),
                'nama_gudang' => $this->input->post('nama_gudang', TRUE),
                'alamat' => $this->input->post('alamat', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
            );

            $this->Sys_gudang_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('sys_gudang'));
        }
    }

    public function update($id)
    {
        $row = $this->Sys_gudang_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('sys_gudang/update_action'),
                'id' => set_value('id', $row->id),
                'uuid_gudang' => set_value('uuid_gudang', $row->uuid_gudang),
                'kode_gudang' => set_value('kode_gudang', $row->kode_gudang),
                'nama_gudang' => set_value('nama_gudang', $row->nama_gudang),
                'alamat' => set_value('alamat', $row->alamat),
                'keterangan' => set_value('keterangan', $row->keterangan),
            );
            $this->load->view('sys_gudang/sys_gudang_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_gudang'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                // 'uuid_gudang' => $this->input->post('uuid_gudang',TRUE),
                'kode_gudang' => $this->input->post('kode_gudang', TRUE),
                'nama_gudang' => $this->input->post('nama_gudang', TRUE),
                'alamat' => $this->input->post('alamat', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
            );

            $this->Sys_gudang_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('sys_gudang'));
        }
    }

    public function delete($id)
    {
        $row = $this->Sys_gudang_model->get_by_id($id);

        if ($row) {
            $this->Sys_gudang_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('sys_gudang'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_gudang'));
        }
    }

    public function _rules()
    {
        // $this->form_validation->set_rules('uuid_gudang', 'uuid gudang', 'trim|required');
        $this->form_validation->set_rules('kode_gudang', 'kode gudang', 'trim|required');
        $this->form_validation->set_rules('nama_gudang', 'nama gudang', 'trim|required');
        // $this->form_validation->set_rules('alamat', 'alamat', 'trim|required');
        // $this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "sys_gudang.xls";
        $judul = "sys_gudang";
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
        xlsWriteLabel($tablehead, $kolomhead++, "Uuid Gudang");
        xlsWriteLabel($tablehead, $kolomhead++, "Kode Gudang");
        xlsWriteLabel($tablehead, $kolomhead++, "Nama Gudang");
        xlsWriteLabel($tablehead, $kolomhead++, "Alamat");
        xlsWriteLabel($tablehead, $kolomhead++, "Keterangan");

        foreach ($this->Sys_gudang_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->uuid_gudang);
            xlsWriteLabel($tablebody, $kolombody++, $data->kode_gudang);
            xlsWriteLabel($tablebody, $kolombody++, $data->nama_gudang);
            xlsWriteLabel($tablebody, $kolombody++, $data->alamat);
            xlsWriteLabel($tablebody, $kolombody++, $data->keterangan);

            $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }
}

/* End of file Sys_gudang.php */
/* Location: ./application/controllers/Sys_gudang.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-10-12 03:26:42 */
/* http://harviacode.com */