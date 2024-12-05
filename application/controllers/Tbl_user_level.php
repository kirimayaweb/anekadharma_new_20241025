<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_user_level extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Tbl_user_level_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    public function index()
    {
        $this->load->view('tbl_user_level/tbl_user_level_list');
    }

    public function json()
    {
        header('Content-Type: application/json');
        echo $this->Tbl_user_level_model->json();
    }

    public function read($id)
    {
        $row = $this->Tbl_user_level_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id_user_level' => $row->id_user_level,
                'nama_level' => $row->nama_level,
            );
            $this->load->view('tbl_user_level/tbl_user_level_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_user_level'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('tbl_user_level/create_action'),
            'id_user_level' => set_value('id_user_level'),
            'nama_level' => set_value('nama_level'),
        );
        $this->load->view('tbl_user_level/tbl_user_level_form', $data);
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

            $this->Tbl_user_level_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('tbl_user_level'));
        }
    }

    public function update($id)
    {
        $row = $this->Tbl_user_level_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('tbl_user_level/update_action'),
                'id_user_level' => set_value('id_user_level', $row->id_user_level),
                'nama_level' => set_value('nama_level', $row->nama_level),
            );
            $this->load->view('tbl_user_level/tbl_user_level_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_user_level'));
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

            $this->Tbl_user_level_model->update($this->input->post('id_user_level', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('tbl_user_level'));
        }
    }

    public function delete($id)
    {
        $row = $this->Tbl_user_level_model->get_by_id($id);

        if ($row) {
            $this->Tbl_user_level_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('tbl_user_level'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_user_level'));
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

        foreach ($this->Tbl_user_level_model->get_all() as $data) {
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
}

/* End of file Tbl_user_level.php */
/* Location: ./application/controllers/Tbl_user_level.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-11-03 10:25:39 */
/* http://harviacode.com */