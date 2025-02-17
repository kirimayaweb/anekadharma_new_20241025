<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jurnal_kas extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Jurnal_kas_model');
        $this->load->library('form_validation');        
	$this->load->library('datatables');
    }

    public function index(){
        $Tbl_pembelian = $this->Tbl_pembelian_model->get_all();
		$start = 0;
		$data = array(
			'Tbl_pembelian_data' => $Tbl_pembelian,
			'start' => $start,
		);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_list_jurnal', $data);
    }

    public function index_server_side()
    {
        $this->load->view('anekadharma/jurnal_kas/jurnal_kas_list');
    } 
    
    public function json() {
        header('Content-Type: application/json');
        echo $this->Jurnal_kas_model->json();
    }

    public function read($id) 
    {
        $row = $this->Jurnal_kas_model->get_by_id($id);
        if ($row) {
            $data = array(
		'nomor' => $row->nomor,
		'tanggal' => $row->tanggal,
		'bukti' => $row->bukti,
		'keterangan' => $row->keterangan,
		'kode_rekening' => $row->kode_rekening,
		'debet' => $row->debet,
		'kredit' => $row->kredit,
	    );
            $this->load->view('jurnal_kas/jurnal_kas_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_kas'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('jurnal_kas/create_action'),
	    'nomor' => set_value('nomor'),
	    'tanggal' => set_value('tanggal'),
	    'bukti' => set_value('bukti'),
	    'keterangan' => set_value('keterangan'),
	    'kode_rekening' => set_value('kode_rekening'),
	    'debet' => set_value('debet'),
	    'kredit' => set_value('kredit'),
	);
        $this->load->view('jurnal_kas/jurnal_kas_form', $data);
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
		'keterangan' => $this->input->post('keterangan',TRUE),
		'kode_rekening' => $this->input->post('kode_rekening',TRUE),
		'debet' => $this->input->post('debet',TRUE),
		'kredit' => $this->input->post('kredit',TRUE),
	    );

            $this->Jurnal_kas_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('jurnal_kas'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Jurnal_kas_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('jurnal_kas/update_action'),
		'nomor' => set_value('nomor', $row->nomor),
		'tanggal' => set_value('tanggal', $row->tanggal),
		'bukti' => set_value('bukti', $row->bukti),
		'keterangan' => set_value('keterangan', $row->keterangan),
		'kode_rekening' => set_value('kode_rekening', $row->kode_rekening),
		'debet' => set_value('debet', $row->debet),
		'kredit' => set_value('kredit', $row->kredit),
	    );
            $this->load->view('jurnal_kas/jurnal_kas_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_kas'));
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
		'keterangan' => $this->input->post('keterangan',TRUE),
		'kode_rekening' => $this->input->post('kode_rekening',TRUE),
		'debet' => $this->input->post('debet',TRUE),
		'kredit' => $this->input->post('kredit',TRUE),
	    );

            $this->Jurnal_kas_model->update($this->input->post('nomor', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('jurnal_kas'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Jurnal_kas_model->get_by_id($id);

        if ($row) {
            $this->Jurnal_kas_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('jurnal_kas'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_kas'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('tanggal', 'tanggal', 'trim|required');
	$this->form_validation->set_rules('bukti', 'bukti', 'trim|required');
	$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');
	$this->form_validation->set_rules('kode_rekening', 'kode rekening', 'trim|required');
	$this->form_validation->set_rules('debet', 'debet', 'trim|required');
	$this->form_validation->set_rules('kredit', 'kredit', 'trim|required');

	$this->form_validation->set_rules('nomor', 'nomor', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "jurnal_kas.xls";
        $judul = "jurnal_kas";
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
	xlsWriteLabel($tablehead, $kolomhead++, "Keterangan");
	xlsWriteLabel($tablehead, $kolomhead++, "Kode Rekening");
	xlsWriteLabel($tablehead, $kolomhead++, "Debet");
	xlsWriteLabel($tablehead, $kolomhead++, "Kredit");

	foreach ($this->Jurnal_kas_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tanggal);
	    xlsWriteLabel($tablebody, $kolombody++, $data->bukti);
	    xlsWriteLabel($tablebody, $kolombody++, $data->keterangan);
	    xlsWriteNumber($tablebody, $kolombody++, $data->kode_rekening);
	    xlsWriteLabel($tablebody, $kolombody++, $data->debet);
	    xlsWriteLabel($tablebody, $kolombody++, $data->kredit);

	    $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

}

/* End of file Jurnal_kas.php */
/* Location: ./application/controllers/Jurnal_kas.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-12-01 11:32:21 */
/* http://harviacode.com */