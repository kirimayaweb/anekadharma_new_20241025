<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_accounting_group extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Tbl_accounting_group_model');
        $this->load->library('form_validation');        
	$this->load->library('datatables');
    }

    public function index()
    {
    
        $data_group = $this->Tbl_accounting_group_model->get_all();

        $data = array(
			'data_group' => $data_group,
			// 'start' => $start,
		);

        // $this->load->view('tbl_accounting_group/tbl_accounting_group_list');

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_accounting_group/adminlte310_group_list', $data);
	

    } 
    
    public function json() {
        header('Content-Type: application/json');
        echo $this->Tbl_accounting_group_model->json();
    }

    public function read($id) 
    {
        $row = $this->Tbl_accounting_group_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'uuid_group' => $row->uuid_group,
		'nama_group' => $row->nama_group,
		'keterangan' => $row->keterangan,
		'id_usr' => $row->id_usr,
	    );
            $this->load->view('tbl_accounting_group/tbl_accounting_group_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_accounting_group'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Simpan',
            'action' => site_url('tbl_accounting_group/create_action'),
	    'id' => set_value('id'),
	    'uuid_group' => set_value('uuid_group'),
	    'nama_group' => set_value('nama_group'),
	    'keterangan' => set_value('keterangan'),
	    'id_usr' => set_value('id_usr'),
	);
        // $this->load->view('tbl_accounting_group/tbl_accounting_group_form', $data);
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_accounting_group/adminlte310_group_form', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		// 'uuid_group' => $this->input->post('uuid_group',TRUE),
		'nama_group' => $this->input->post('nama_group',TRUE),
		'keterangan' => $this->input->post('keterangan',TRUE),
		'id_usr' => $this->input->post('id_usr',TRUE),
	    );

            $this->Tbl_accounting_group_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('tbl_accounting_group'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Tbl_accounting_group_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('tbl_accounting_group/update_action'),
		'id' => set_value('id', $row->id),
		'uuid_group' => set_value('uuid_group', $row->uuid_group),
		'nama_group' => set_value('nama_group', $row->nama_group),
		'keterangan' => set_value('keterangan', $row->keterangan),
		'id_usr' => set_value('id_usr', $row->id_usr),
	    );
            $this->load->view('tbl_accounting_group/tbl_accounting_group_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_accounting_group'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
		// 'uuid_group' => $this->input->post('uuid_group',TRUE),
		'nama_group' => $this->input->post('nama_group',TRUE),
		'keterangan' => $this->input->post('keterangan',TRUE),
		'id_usr' => $this->input->post('id_usr',TRUE),
	    );

            $this->Tbl_accounting_group_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('tbl_accounting_group'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Tbl_accounting_group_model->get_by_id($id);

        if ($row) {
            $this->Tbl_accounting_group_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('tbl_accounting_group'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_accounting_group'));
        }
    }

    public function _rules() 
    {
	// $this->form_validation->set_rules('uuid_group', 'uuid group', 'trim|required');
	$this->form_validation->set_rules('nama_group', 'nama group', 'trim|required');
	// $this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');
	// $this->form_validation->set_rules('id_usr', 'id usr', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "tbl_accounting_group.xls";
        $judul = "tbl_accounting_group";
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
	xlsWriteLabel($tablehead, $kolomhead++, "Uuid Group");
	xlsWriteLabel($tablehead, $kolomhead++, "Nama Group");
	xlsWriteLabel($tablehead, $kolomhead++, "Keterangan");
	xlsWriteLabel($tablehead, $kolomhead++, "Id Usr");

	foreach ($this->Tbl_accounting_group_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
	    xlsWriteLabel($tablebody, $kolombody++, $data->uuid_group);
	    xlsWriteLabel($tablebody, $kolombody++, $data->nama_group);
	    xlsWriteLabel($tablebody, $kolombody++, $data->keterangan);
	    xlsWriteNumber($tablebody, $kolombody++, $data->id_usr);

	    $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

}

/* End of file Tbl_accounting_group.php */
/* Location: ./application/controllers/Tbl_accounting_group.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-09-24 15:01:18 */
/* http://harviacode.com */