<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jurnal_pembelian extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Jurnal_pembelian_model');
        $this->load->library('form_validation');        
	$this->load->library('datatables');
    }

    public function index()
    {
        $this->load->view('jurnal_pembelian/jurnal_pembelian_list');
    } 
    
    public function json() {
        header('Content-Type: application/json');
        echo $this->Jurnal_pembelian_model->json();
    }

    public function read($id) 
    {
        $row = $this->Jurnal_pembelian_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'tanggal' => $row->tanggal,
		'unit' => $row->unit,
		'spop' => $row->spop,
		'pl' => $row->pl,
		'supplier' => $row->supplier,
		'norek' => $row->norek,
		'rekening' => $row->rekening,
		'jumlah' => $row->jumlah,
		'uu21101' => $row->uu21101,
	    );
            $this->load->view('jurnal_pembelian/jurnal_pembelian_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_pembelian'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('jurnal_pembelian/create_action'),
	    'id' => set_value('id'),
	    'tanggal' => set_value('tanggal'),
	    'unit' => set_value('unit'),
	    'spop' => set_value('spop'),
	    'pl' => set_value('pl'),
	    'supplier' => set_value('supplier'),
	    'norek' => set_value('norek'),
	    'rekening' => set_value('rekening'),
	    'jumlah' => set_value('jumlah'),
	    'uu21101' => set_value('uu21101'),
	);
        $this->load->view('jurnal_pembelian/jurnal_pembelian_form', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'tanggal' => $this->input->post('tanggal',TRUE),
		'unit' => $this->input->post('unit',TRUE),
		'spop' => $this->input->post('spop',TRUE),
		'pl' => $this->input->post('pl',TRUE),
		'supplier' => $this->input->post('supplier',TRUE),
		'norek' => $this->input->post('norek',TRUE),
		'rekening' => $this->input->post('rekening',TRUE),
		'jumlah' => $this->input->post('jumlah',TRUE),
		'uu21101' => $this->input->post('uu21101',TRUE),
	    );

            $this->Jurnal_pembelian_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('jurnal_pembelian'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Jurnal_pembelian_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('jurnal_pembelian/update_action'),
		'id' => set_value('id', $row->id),
		'tanggal' => set_value('tanggal', $row->tanggal),
		'unit' => set_value('unit', $row->unit),
		'spop' => set_value('spop', $row->spop),
		'pl' => set_value('pl', $row->pl),
		'supplier' => set_value('supplier', $row->supplier),
		'norek' => set_value('norek', $row->norek),
		'rekening' => set_value('rekening', $row->rekening),
		'jumlah' => set_value('jumlah', $row->jumlah),
		'uu21101' => set_value('uu21101', $row->uu21101),
	    );
            $this->load->view('jurnal_pembelian/jurnal_pembelian_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_pembelian'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
		'tanggal' => $this->input->post('tanggal',TRUE),
		'unit' => $this->input->post('unit',TRUE),
		'spop' => $this->input->post('spop',TRUE),
		'pl' => $this->input->post('pl',TRUE),
		'supplier' => $this->input->post('supplier',TRUE),
		'norek' => $this->input->post('norek',TRUE),
		'rekening' => $this->input->post('rekening',TRUE),
		'jumlah' => $this->input->post('jumlah',TRUE),
		'uu21101' => $this->input->post('uu21101',TRUE),
	    );

            $this->Jurnal_pembelian_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('jurnal_pembelian'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Jurnal_pembelian_model->get_by_id($id);

        if ($row) {
            $this->Jurnal_pembelian_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('jurnal_pembelian'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_pembelian'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('tanggal', 'tanggal', 'trim|required');
	$this->form_validation->set_rules('unit', 'unit', 'trim|required');
	$this->form_validation->set_rules('spop', 'spop', 'trim|required');
	$this->form_validation->set_rules('pl', 'pl', 'trim|required');
	$this->form_validation->set_rules('supplier', 'supplier', 'trim|required');
	$this->form_validation->set_rules('norek', 'norek', 'trim|required');
	$this->form_validation->set_rules('rekening', 'rekening', 'trim|required');
	$this->form_validation->set_rules('jumlah', 'jumlah', 'trim|required');
	$this->form_validation->set_rules('uu21101', 'uu21101', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "jurnal_pembelian.xls";
        $judul = "jurnal_pembelian";
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
	xlsWriteLabel($tablehead, $kolomhead++, "Tanggal");
	xlsWriteLabel($tablehead, $kolomhead++, "Unit");
	xlsWriteLabel($tablehead, $kolomhead++, "Spop");
	xlsWriteLabel($tablehead, $kolomhead++, "Pl");
	xlsWriteLabel($tablehead, $kolomhead++, "Supplier");
	xlsWriteLabel($tablehead, $kolomhead++, "Norek");
	xlsWriteLabel($tablehead, $kolomhead++, "Rekening");
	xlsWriteLabel($tablehead, $kolomhead++, "Jumlah");
	xlsWriteLabel($tablehead, $kolomhead++, "Uu21101");

	foreach ($this->Jurnal_pembelian_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tanggal);
	    xlsWriteLabel($tablebody, $kolombody++, $data->unit);
	    xlsWriteLabel($tablebody, $kolombody++, $data->spop);
	    xlsWriteNumber($tablebody, $kolombody++, $data->pl);
	    xlsWriteLabel($tablebody, $kolombody++, $data->supplier);
	    xlsWriteNumber($tablebody, $kolombody++, $data->norek);
	    xlsWriteLabel($tablebody, $kolombody++, $data->rekening);
	    xlsWriteNumber($tablebody, $kolombody++, $data->jumlah);
	    xlsWriteNumber($tablebody, $kolombody++, $data->uu21101);

	    $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

}

/* End of file Jurnal_pembelian.php */
/* Location: ./application/controllers/Jurnal_pembelian.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-12-01 11:34:48 */
/* http://harviacode.com */