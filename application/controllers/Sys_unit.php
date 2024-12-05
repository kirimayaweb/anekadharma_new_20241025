<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sys_unit extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model(array('Sys_unit_model', 'Tbl_pembelian_model','Sys_unit_produk_model'));
        $this->load->library('form_validation');
        $this->load->library('datatables');
        $this->load->library('Pdf');
        $this->load->helper(array('nominal'));
    }

    public function index()
    {

        $data_unit = $this->Sys_unit_model->get_all();
        $data = array(
            'data_unit' => $data_unit,
            'action' => site_url('sys_unit/cari_unit'),
        );


        // $this->load->view('sys_unit/sys_unit_list');
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_unit/adminlte310_sys_unit_list_ALL', $data);
    }

    public function cari_unit($uuid_unit_selected = null)
    {
        // print_r($this->input->post('uuid_sys_unit', TRUE));
        // print_r("<br/>");
        $uuid_unit_selected = $this->input->post('uuid_sys_unit', TRUE);
        // print_r($uuid_unit_selected);
        // print_r("<br/>");


        // die;

        if ($uuid_unit_selected) {
            redirect(site_url('sys_unit/detail_unit/' . $uuid_unit_selected));
        } else {
            redirect(site_url('sys_unit'));
        }
    }

    public function detail_unit($uuid_unit_selected = null)
    {
        // print_r("detail_unit");
        // print_r("<br/>");
        // print_r($uuid_unit_selected);
        // print_r("<br/>");

        
        $data_pembelian_per_unit = $this->Tbl_pembelian_model->get_by_uuid_konsumen($uuid_unit_selected);
        
        $data_unit = $this->Sys_unit_model->get_by_uuid_unit($uuid_unit_selected);
        
        $data_produk_per_unit = $this->Sys_unit_produk_model->get_by_uuid_unit($uuid_unit_selected);

        // print_r($data_pembelian_per_unit);





        $data = array(
            'data_produk_per_unit' => $data_produk_per_unit,
            'data_unit_beli' => $data_pembelian_per_unit,
            'uuid_unit_selected' => $uuid_unit_selected,
            'nama_unit' => $data_unit->nama_unit,
            'action' => site_url('sys_unit/cari_unit'),
        );


        // $this->load->view('sys_unit/sys_unit_list');
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_unit/adminlte310_sys_unit_list', $data);
    }


    public function json()
    {
        header('Content-Type: application/json');
        echo $this->Sys_unit_model->json();
    }

    public function read($id)
    {
        $row = $this->Sys_unit_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'uuid_unit' => $row->uuid_unit,
                'kode_unit' => $row->kode_unit,
                'nama_unit' => $row->nama_unit,
                'keterangan' => $row->keterangan,
            );
            $this->load->view('sys_unit/sys_unit_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_unit'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('sys_unit/create_action'),
            'id' => set_value('id'),
            'uuid_unit' => set_value('uuid_unit'),
            'kode_unit' => set_value('kode_unit'),
            'nama_unit' => set_value('nama_unit'),
            'keterangan' => set_value('keterangan'),
        );
        // $this->load->view('sys_unit/sys_unit_form', $data);
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_unit/adminlte310_sys_unit_form', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                // 'uuid_unit' => $this->input->post('uuid_unit',TRUE),
                'kode_unit' => $this->input->post('kode_unit', TRUE),
                'nama_unit' => $this->input->post('nama_unit', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
            );

            $this->Sys_unit_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('sys_unit'));
        }
    }

    public function update($id)
    {
        $row = $this->Sys_unit_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('sys_unit/update_action'),
                'id' => set_value('id', $row->id),
                'uuid_unit' => set_value('uuid_unit', $row->uuid_unit),
                'kode_unit' => set_value('kode_unit', $row->kode_unit),
                'nama_unit' => set_value('nama_unit', $row->nama_unit),
                'keterangan' => set_value('keterangan', $row->keterangan),
            );
            $this->load->view('sys_unit/sys_unit_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_unit'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'uuid_unit' => $this->input->post('uuid_unit', TRUE),
                'kode_unit' => $this->input->post('kode_unit', TRUE),
                'nama_unit' => $this->input->post('nama_unit', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
            );

            $this->Sys_unit_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('sys_unit'));
        }
    }

    public function delete($id)
    {
        $row = $this->Sys_unit_model->get_by_id($id);

        if ($row) {
            $this->Sys_unit_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('sys_unit'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_unit'));
        }
    }

    public function _rules()
    {
        // $this->form_validation->set_rules('uuid_unit', 'uuid unit', 'trim|required');
        // $this->form_validation->set_rules('kode_unit', 'kode unit', 'trim|required');
        $this->form_validation->set_rules('nama_unit', 'nama unit', 'trim|required');
        // $this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "sys_unit.xls";
        $judul = "sys_unit";
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
        xlsWriteLabel($tablehead, $kolomhead++, "Uuid Unit");
        xlsWriteLabel($tablehead, $kolomhead++, "Kode Unit");
        xlsWriteLabel($tablehead, $kolomhead++, "Nama Unit");
        xlsWriteLabel($tablehead, $kolomhead++, "Keterangan");

        foreach ($this->Sys_unit_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->uuid_unit);
            xlsWriteLabel($tablebody, $kolombody++, $data->kode_unit);
            xlsWriteLabel($tablebody, $kolombody++, $data->nama_unit);
            xlsWriteLabel($tablebody, $kolombody++, $data->keterangan);

            $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }
}

/* End of file Sys_unit.php */
/* Location: ./application/controllers/Sys_unit.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-09-16 14:10:02 */
/* http://harviacode.com */