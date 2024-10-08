<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sys_tingkat extends CI_Controller
{
    
        
    function __construct()
    {
        parent::__construct();
        $this->load->model('Sys_tingkat_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $sys_tingkat = $this->Sys_tingkat_model->get_all();

        $data = array(
            'sys_tingkat_data' => $sys_tingkat
        );

        // $this->template->load('template','sys_tingkat/sys_tingkat_list', $data);
        $this->template->load('template/adminlte310', 'sys_tingkat/adminlte310_sys_tingkat_list', $data);
    }

    public function read($id) 
    {
        $row = $this->Sys_tingkat_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'uuid_tingkat' => $row->uuid_tingkat,
		'tingkat_system' => $row->tingkat_system,
		'tingkat' => $row->tingkat,
	    );
            $this->template->load('template','sys_tingkat/sys_tingkat_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_tingkat'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('sys_tingkat/create_action'),
	    'id' => set_value('id'),
	    'uuid_tingkat' => set_value('uuid_tingkat'),
	    'tingkat_system' => set_value('tingkat_system'),
	    'tingkat' => set_value('tingkat'),
	);
        $this->template->load('template','sys_tingkat/sys_tingkat_form', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'uuid_tingkat' => $this->input->post('uuid_tingkat',TRUE),
		'tingkat_system' => $this->input->post('tingkat_system',TRUE),
		'tingkat' => $this->input->post('tingkat',TRUE),
	    );

            $this->Sys_tingkat_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('sys_tingkat'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Sys_tingkat_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('sys_tingkat/update_action'),
		'id' => set_value('id', $row->id),
		'uuid_tingkat' => set_value('uuid_tingkat', $row->uuid_tingkat),
		'tingkat_system' => set_value('tingkat_system', $row->tingkat_system),
		'tingkat' => set_value('tingkat', $row->tingkat),
	    );
            $this->template->load('template','sys_tingkat/sys_tingkat_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_tingkat'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
		'uuid_tingkat' => $this->input->post('uuid_tingkat',TRUE),
		'tingkat_system' => $this->input->post('tingkat_system',TRUE),
		'tingkat' => $this->input->post('tingkat',TRUE),
	    );

            $this->Sys_tingkat_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('sys_tingkat'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Sys_tingkat_model->get_by_id($id);

        if ($row) {
            $this->Sys_tingkat_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('sys_tingkat'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_tingkat'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('uuid_tingkat', 'uuid tingkat', 'trim|required');
	$this->form_validation->set_rules('tingkat_system', 'tingkat system', 'trim|required');
	$this->form_validation->set_rules('tingkat', 'tingkat', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "sys_tingkat.xls";
        $judul = "sys_tingkat";
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
	xlsWriteLabel($tablehead, $kolomhead++, "Uuid Tingkat");
	xlsWriteLabel($tablehead, $kolomhead++, "Tingkat System");
	xlsWriteLabel($tablehead, $kolomhead++, "Tingkat");

	foreach ($this->Sys_tingkat_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
	    xlsWriteLabel($tablebody, $kolombody++, $data->uuid_tingkat);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tingkat_system);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tingkat);

	    $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

}

/* End of file Sys_tingkat.php */
/* Location: ./application/controllers/Sys_tingkat.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-04-02 08:50:30 */
/* http://harviacode.com */