<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jurnal_penjualan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Jurnal_penjualan_model');
        $this->load->library('form_validation');        
	$this->load->library('datatables');
    }

    public function index()
    {
        $this->load->view('jurnal_penjualan/jurnal_penjualan_list');
    } 
    
    public function json() {
        header('Content-Type: application/json');
        echo $this->Jurnal_penjualan_model->json();
    }

    public function read($id) 
    {
        $row = $this->Jurnal_penjualan_model->get_by_id($id);
        if ($row) {
            $data = array(
		'nomor' => $row->nomor,
		'tanggal' => $row->tanggal,
		'bukti' => $row->bukti,
		'pl' => $row->pl,
		'ref' => $row->ref,
		'rekening' => $row->rekening,
		'keterangan' => $row->keterangan,
		'debet' => $row->debet,
		'kredit' => $row->kredit,
	    );
            $this->load->view('jurnal_penjualan/jurnal_penjualan_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_penjualan'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('jurnal_penjualan/create_action'),
	    'nomor' => set_value('nomor'),
	    'tanggal' => set_value('tanggal'),
	    'bukti' => set_value('bukti'),
	    'pl' => set_value('pl'),
	    'ref' => set_value('ref'),
	    'rekening' => set_value('rekening'),
	    'keterangan' => set_value('keterangan'),
	    'debet' => set_value('debet'),
	    'kredit' => set_value('kredit'),
	);
        $this->load->view('jurnal_penjualan/jurnal_penjualan_form', $data);
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
		'rekening' => $this->input->post('rekening',TRUE),
		'keterangan' => $this->input->post('keterangan',TRUE),
		'debet' => $this->input->post('debet',TRUE),
		'kredit' => $this->input->post('kredit',TRUE),
	    );

            $this->Jurnal_penjualan_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('jurnal_penjualan'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Jurnal_penjualan_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('jurnal_penjualan/update_action'),
		'nomor' => set_value('nomor', $row->nomor),
		'tanggal' => set_value('tanggal', $row->tanggal),
		'bukti' => set_value('bukti', $row->bukti),
		'pl' => set_value('pl', $row->pl),
		'ref' => set_value('ref', $row->ref),
		'rekening' => set_value('rekening', $row->rekening),
		'keterangan' => set_value('keterangan', $row->keterangan),
		'debet' => set_value('debet', $row->debet),
		'kredit' => set_value('kredit', $row->kredit),
	    );
            $this->load->view('jurnal_penjualan/jurnal_penjualan_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_penjualan'));
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
		'rekening' => $this->input->post('rekening',TRUE),
		'keterangan' => $this->input->post('keterangan',TRUE),
		'debet' => $this->input->post('debet',TRUE),
		'kredit' => $this->input->post('kredit',TRUE),
	    );

            $this->Jurnal_penjualan_model->update($this->input->post('nomor', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('jurnal_penjualan'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Jurnal_penjualan_model->get_by_id($id);

        if ($row) {
            $this->Jurnal_penjualan_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('jurnal_penjualan'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_penjualan'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('tanggal', 'tanggal', 'trim|required');
	$this->form_validation->set_rules('bukti', 'bukti', 'trim|required');
	$this->form_validation->set_rules('pl', 'pl', 'trim|required');
	$this->form_validation->set_rules('ref', 'ref', 'trim|required');
	$this->form_validation->set_rules('rekening', 'rekening', 'trim|required');
	$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');
	$this->form_validation->set_rules('debet', 'debet', 'trim|required');
	$this->form_validation->set_rules('kredit', 'kredit', 'trim|required');

	$this->form_validation->set_rules('nomor', 'nomor', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "jurnal_penjualan.xls";
        $judul = "jurnal_penjualan";
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
	xlsWriteLabel($tablehead, $kolomhead++, "Rekening");
	xlsWriteLabel($tablehead, $kolomhead++, "Keterangan");
	xlsWriteLabel($tablehead, $kolomhead++, "Debet");
	xlsWriteLabel($tablehead, $kolomhead++, "Kredit");

	foreach ($this->Jurnal_penjualan_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tanggal);
	    xlsWriteLabel($tablebody, $kolombody++, $data->bukti);
	    xlsWriteLabel($tablebody, $kolombody++, $data->pl);
	    xlsWriteLabel($tablebody, $kolombody++, $data->ref);
	    xlsWriteNumber($tablebody, $kolombody++, $data->rekening);
	    xlsWriteLabel($tablebody, $kolombody++, $data->keterangan);
	    xlsWriteLabel($tablebody, $kolombody++, $data->debet);
	    xlsWriteLabel($tablebody, $kolombody++, $data->kredit);

	    $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

}

/* End of file Jurnal_penjualan.php */
/* Location: ./application/controllers/Jurnal_penjualan.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-12-01 11:40:32 */
/* http://harviacode.com */