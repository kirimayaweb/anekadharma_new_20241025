<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jurnal_okt extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Jurnal_okt_model');
        $this->load->library('form_validation');        
	$this->load->library('datatables');
    }

    public function index()
    {
        // $this->load->view('jurnal_okt/jurnal_okt_list');

        $data_jurnal = $this->Jurnal_okt_model->get_all();
        $data = array(
            'data_jurnal' => $data_jurnal,
            'action' => site_url('jurnal_okt/create_action'),
        );

        // print_r($data);
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/jurnal_okt/adminlte310_jurnal_list', $data);
  
    } 
    
    public function json() {
        header('Content-Type: application/json');
        echo $this->Jurnal_okt_model->json();
    }

    public function read($id) 
    {
        $row = $this->Jurnal_okt_model->get_by_id($id);
        if ($row) {
            $data = array(
		'Tgl_date' => $row->Tgl_date,
		'tgl' => $row->tgl,
		'nmr_spop' => $row->nmr_spop,
		'pl' => $row->pl,
		'kode_unit' => $row->kode_unit,
		'unit' => $row->unit,
		'supplier' => $row->supplier,
		'nmr_rek' => $row->nmr_rek,
		'rekening' => $row->rekening,
		' jumlah' => $row-> jumlah,
		'uu' => $row->uu,
	    );
            $this->load->view('jurnal_okt/jurnal_okt_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_okt'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('jurnal_okt/create_action'),
	    'Tgl_date' => set_value('Tgl_date'),
	    'tgl' => set_value('tgl'),
	    'nmr_spop' => set_value('nmr_spop'),
	    'pl' => set_value('pl'),
	    'kode_unit' => set_value('kode_unit'),
	    'unit' => set_value('unit'),
	    'supplier' => set_value('supplier'),
	    'nmr_rek' => set_value('nmr_rek'),
	    'rekening' => set_value('rekening'),
	    ' jumlah' => set_value(' jumlah'),
	    'uu' => set_value('uu'),
	);
        $this->load->view('jurnal_okt/jurnal_okt_form', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'Tgl_date' => $this->input->post('Tgl_date',TRUE),
		'tgl' => $this->input->post('tgl',TRUE),
		'nmr_spop' => $this->input->post('nmr_spop',TRUE),
		'pl' => $this->input->post('pl',TRUE),
		'kode_unit' => $this->input->post('kode_unit',TRUE),
		'unit' => $this->input->post('unit',TRUE),
		'supplier' => $this->input->post('supplier',TRUE),
		'nmr_rek' => $this->input->post('nmr_rek',TRUE),
		'rekening' => $this->input->post('rekening',TRUE),
		' jumlah' => $this->input->post(' jumlah',TRUE),
		'uu' => $this->input->post('uu',TRUE),
	    );

            $this->Jurnal_okt_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('jurnal_okt'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Jurnal_okt_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('jurnal_okt/update_action'),
		'Tgl_date' => set_value('Tgl_date', $row->Tgl_date),
		'tgl' => set_value('tgl', $row->tgl),
		'nmr_spop' => set_value('nmr_spop', $row->nmr_spop),
		'pl' => set_value('pl', $row->pl),
		'kode_unit' => set_value('kode_unit', $row->kode_unit),
		'unit' => set_value('unit', $row->unit),
		'supplier' => set_value('supplier', $row->supplier),
		'nmr_rek' => set_value('nmr_rek', $row->nmr_rek),
		'rekening' => set_value('rekening', $row->rekening),
		' jumlah' => set_value(' jumlah', $row-> jumlah),
		'uu' => set_value('uu', $row->uu),
	    );
            $this->load->view('jurnal_okt/jurnal_okt_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_okt'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('', TRUE));
        } else {
            $data = array(
		'Tgl_date' => $this->input->post('Tgl_date',TRUE),
		'tgl' => $this->input->post('tgl',TRUE),
		'nmr_spop' => $this->input->post('nmr_spop',TRUE),
		'pl' => $this->input->post('pl',TRUE),
		'kode_unit' => $this->input->post('kode_unit',TRUE),
		'unit' => $this->input->post('unit',TRUE),
		'supplier' => $this->input->post('supplier',TRUE),
		'nmr_rek' => $this->input->post('nmr_rek',TRUE),
		'rekening' => $this->input->post('rekening',TRUE),
		' jumlah' => $this->input->post(' jumlah',TRUE),
		'uu' => $this->input->post('uu',TRUE),
	    );

            $this->Jurnal_okt_model->update($this->input->post('', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('jurnal_okt'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Jurnal_okt_model->get_by_id($id);

        if ($row) {
            $this->Jurnal_okt_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('jurnal_okt'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_okt'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('Tgl_date', 'tgl date', 'trim|required');
	$this->form_validation->set_rules('tgl', 'tgl', 'trim|required');
	$this->form_validation->set_rules('nmr_spop', 'nmr spop', 'trim|required');
	$this->form_validation->set_rules('pl', 'pl', 'trim|required');
	$this->form_validation->set_rules('kode_unit', 'kode unit', 'trim|required');
	$this->form_validation->set_rules('unit', 'unit', 'trim|required');
	$this->form_validation->set_rules('supplier', 'supplier', 'trim|required');
	$this->form_validation->set_rules('nmr_rek', 'nmr rek', 'trim|required');
	$this->form_validation->set_rules('rekening', 'rekening', 'trim|required');
	$this->form_validation->set_rules(' jumlah', ' jumlah', 'trim|required');
	$this->form_validation->set_rules('uu', 'uu', 'trim|required');

	$this->form_validation->set_rules('', '', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "jurnal_okt.xls";
        $judul = "jurnal_okt";
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
	xlsWriteLabel($tablehead, $kolomhead++, "Tgl Date");
	xlsWriteLabel($tablehead, $kolomhead++, "Tgl");
	xlsWriteLabel($tablehead, $kolomhead++, "Nmr Spop");
	xlsWriteLabel($tablehead, $kolomhead++, "Pl");
	xlsWriteLabel($tablehead, $kolomhead++, "Kode Unit");
	xlsWriteLabel($tablehead, $kolomhead++, "Unit");
	xlsWriteLabel($tablehead, $kolomhead++, "Supplier");
	xlsWriteLabel($tablehead, $kolomhead++, "Nmr Rek");
	xlsWriteLabel($tablehead, $kolomhead++, "Rekening");
	xlsWriteLabel($tablehead, $kolomhead++, " Jumlah");
	xlsWriteLabel($tablehead, $kolomhead++, "Uu");

	foreach ($this->Jurnal_okt_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
	    xlsWriteLabel($tablebody, $kolombody++, $data->Tgl_date);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tgl);
	    xlsWriteNumber($tablebody, $kolombody++, $data->nmr_spop);
	    xlsWriteNumber($tablebody, $kolombody++, $data->pl);
	    xlsWriteNumber($tablebody, $kolombody++, $data->kode_unit);
	    xlsWriteLabel($tablebody, $kolombody++, $data->unit);
	    xlsWriteLabel($tablebody, $kolombody++, $data->supplier);
	    xlsWriteNumber($tablebody, $kolombody++, $data->nmr_rek);
	    xlsWriteLabel($tablebody, $kolombody++, $data->rekening);
	    xlsWriteNumber($tablebody, $kolombody++, $data-> jumlah);
	    xlsWriteNumber($tablebody, $kolombody++, $data->uu);

	    $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

}

/* End of file Jurnal_okt.php */
/* Location: ./application/controllers/Jurnal_okt.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-10-11 15:17:54 */
/* http://harviacode.com */