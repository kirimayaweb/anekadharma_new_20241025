<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jurnal_umum extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Jurnal_umum_model');
        $this->load->library('form_validation');        
	$this->load->library('datatables');
    }

    public function index()
    {
        $this->load->view('jurnal_umum/jurnal_umum_list');
    } 
    
    public function json() {
        header('Content-Type: application/json');
        echo $this->Jurnal_umum_model->json();
    }

    public function read($id) 
    {
        $row = $this->Jurnal_umum_model->get_by_id($id);
        if ($row) {
            $data = array(
		'nomor' => $row->nomor,
		'tanggal' => $row->tanggal,
		'bukti' => $row->bukti,
		'pl' => $row->pl,
		'ref' => $row->ref,
		'uraian_kode_rekening' => $row->uraian_kode_rekening,
		'rekening' => $row->rekening,
		'debit' => $row->debit,
		'kredit' => $row->kredit,
	    );
            $this->load->view('jurnal_umum/jurnal_umum_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_umum'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('jurnal_umum/create_action'),
	    'nomor' => set_value('nomor'),
	    'tanggal' => set_value('tanggal'),
	    'bukti' => set_value('bukti'),
	    'pl' => set_value('pl'),
	    'ref' => set_value('ref'),
	    'uraian_kode_rekening' => set_value('uraian_kode_rekening'),
	    'rekening' => set_value('rekening'),
	    'debit' => set_value('debit'),
	    'kredit' => set_value('kredit'),
	);
        $this->load->view('jurnal_umum/jurnal_umum_form', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'tanggal' => $this->input->post('tanggal',TRUE),
		'bukti' => $this->input->post('bukti',TRUE),
		'pl' => $this->input->post('pl',TRUE),
		'ref' => $this->input->post('ref',TRUE),
		'uraian_kode_rekening' => $this->input->post('uraian_kode_rekening',TRUE),
		'rekening' => $this->input->post('rekening',TRUE),
		'debit' => $this->input->post('debit',TRUE),
		'kredit' => $this->input->post('kredit',TRUE),
	    );

            $this->Jurnal_umum_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('jurnal_umum'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Jurnal_umum_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('jurnal_umum/update_action'),
		'nomor' => set_value('nomor', $row->nomor),
		'tanggal' => set_value('tanggal', $row->tanggal),
		'bukti' => set_value('bukti', $row->bukti),
		'pl' => set_value('pl', $row->pl),
		'ref' => set_value('ref', $row->ref),
		'uraian_kode_rekening' => set_value('uraian_kode_rekening', $row->uraian_kode_rekening),
		'rekening' => set_value('rekening', $row->rekening),
		'debit' => set_value('debit', $row->debit),
		'kredit' => set_value('kredit', $row->kredit),
	    );
            $this->load->view('jurnal_umum/jurnal_umum_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_umum'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('nomor', TRUE));
        } else {
            $data = array(
		'tanggal' => $this->input->post('tanggal',TRUE),
		'bukti' => $this->input->post('bukti',TRUE),
		'pl' => $this->input->post('pl',TRUE),
		'ref' => $this->input->post('ref',TRUE),
		'uraian_kode_rekening' => $this->input->post('uraian_kode_rekening',TRUE),
		'rekening' => $this->input->post('rekening',TRUE),
		'debit' => $this->input->post('debit',TRUE),
		'kredit' => $this->input->post('kredit',TRUE),
	    );

            $this->Jurnal_umum_model->update($this->input->post('nomor', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('jurnal_umum'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Jurnal_umum_model->get_by_id($id);

        if ($row) {
            $this->Jurnal_umum_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('jurnal_umum'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_umum'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('tanggal', 'tanggal', 'trim|required');
	$this->form_validation->set_rules('bukti', 'bukti', 'trim|required');
	$this->form_validation->set_rules('pl', 'pl', 'trim|required');
	$this->form_validation->set_rules('ref', 'ref', 'trim|required');
	$this->form_validation->set_rules('uraian_kode_rekening', 'uraian kode rekening', 'trim|required');
	$this->form_validation->set_rules('rekening', 'rekening', 'trim|required');
	$this->form_validation->set_rules('debit', 'debit', 'trim|required');
	$this->form_validation->set_rules('kredit', 'kredit', 'trim|required');

	$this->form_validation->set_rules('nomor', 'nomor', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "jurnal_umum.xls";
        $judul = "jurnal_umum";
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
	xlsWriteLabel($tablehead, $kolomhead++, "Bukti");
	xlsWriteLabel($tablehead, $kolomhead++, "Pl");
	xlsWriteLabel($tablehead, $kolomhead++, "Ref");
	xlsWriteLabel($tablehead, $kolomhead++, "Uraian Kode Rekening");
	xlsWriteLabel($tablehead, $kolomhead++, "Rekening");
	xlsWriteLabel($tablehead, $kolomhead++, "Debit");
	xlsWriteLabel($tablehead, $kolomhead++, "Kredit");

	foreach ($this->Jurnal_umum_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tanggal);
	    xlsWriteLabel($tablebody, $kolombody++, $data->bukti);
	    xlsWriteNumber($tablebody, $kolombody++, $data->pl);
	    xlsWriteLabel($tablebody, $kolombody++, $data->ref);
	    xlsWriteNumber($tablebody, $kolombody++, $data->uraian_kode_rekening);
	    xlsWriteLabel($tablebody, $kolombody++, $data->rekening);
	    xlsWriteLabel($tablebody, $kolombody++, $data->debit);
	    xlsWriteLabel($tablebody, $kolombody++, $data->kredit);

	    $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

}

/* End of file Jurnal_umum.php */
/* Location: ./application/controllers/Jurnal_umum.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-12-01 11:40:37 */
/* http://harviacode.com */