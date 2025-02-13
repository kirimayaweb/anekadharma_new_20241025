<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_rekening_koran extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Tbl_rekening_koran_model');
        $this->load->library('form_validation');        
	$this->load->library('datatables');
    }

    public function index()
    {
        $this->load->view('anekadharma/tbl_rekening_koran/tbl_rekening_koran_list');
    } 
    
    public function json() {
        header('Content-Type: application/json');
        echo $this->Tbl_rekening_koran_model->json();
    }

    public function read($id) 
    {
        $row = $this->Tbl_rekening_koran_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'uuid_rek_koran' => $row->uuid_rek_koran,
		'tgl_transaksi' => $row->tgl_transaksi,
		'uraian' => $row->uraian,
		'chq' => $row->chq,
		'debet' => $row->debet,
		'kredit' => $row->kredit,
	    );
            $this->load->view('tbl_rekening_koran/tbl_rekening_koran_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_rekening_koran'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('tbl_rekening_koran/create_action'),
	    'id' => set_value('id'),
	    'uuid_rek_koran' => set_value('uuid_rek_koran'),
	    'tgl_transaksi' => set_value('tgl_transaksi'),
	    'uraian' => set_value('uraian'),
	    'chq' => set_value('chq'),
	    'debet' => set_value('debet'),
	    'kredit' => set_value('kredit'),
	);
        $this->load->view('tbl_rekening_koran/tbl_rekening_koran_form', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'uuid_rek_koran' => $this->input->post('uuid_rek_koran',TRUE),
		'tgl_transaksi' => $this->input->post('tgl_transaksi',TRUE),
		'uraian' => $this->input->post('uraian',TRUE),
		'chq' => $this->input->post('chq',TRUE),
		'debet' => $this->input->post('debet',TRUE),
		'kredit' => $this->input->post('kredit',TRUE),
	    );

            $this->Tbl_rekening_koran_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('tbl_rekening_koran'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Tbl_rekening_koran_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('tbl_rekening_koran/update_action'),
		'id' => set_value('id', $row->id),
		'uuid_rek_koran' => set_value('uuid_rek_koran', $row->uuid_rek_koran),
		'tgl_transaksi' => set_value('tgl_transaksi', $row->tgl_transaksi),
		'uraian' => set_value('uraian', $row->uraian),
		'chq' => set_value('chq', $row->chq),
		'debet' => set_value('debet', $row->debet),
		'kredit' => set_value('kredit', $row->kredit),
	    );
            $this->load->view('tbl_rekening_koran/tbl_rekening_koran_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_rekening_koran'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
		'uuid_rek_koran' => $this->input->post('uuid_rek_koran',TRUE),
		'tgl_transaksi' => $this->input->post('tgl_transaksi',TRUE),
		'uraian' => $this->input->post('uraian',TRUE),
		'chq' => $this->input->post('chq',TRUE),
		'debet' => $this->input->post('debet',TRUE),
		'kredit' => $this->input->post('kredit',TRUE),
	    );

            $this->Tbl_rekening_koran_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('tbl_rekening_koran'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Tbl_rekening_koran_model->get_by_id($id);

        if ($row) {
            $this->Tbl_rekening_koran_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('tbl_rekening_koran'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_rekening_koran'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('uuid_rek_koran', 'uuid rek koran', 'trim|required');
	$this->form_validation->set_rules('tgl_transaksi', 'tgl transaksi', 'trim|required');
	$this->form_validation->set_rules('uraian', 'uraian', 'trim|required');
	$this->form_validation->set_rules('chq', 'chq', 'trim|required|numeric');
	$this->form_validation->set_rules('debet', 'debet', 'trim|required|numeric');
	$this->form_validation->set_rules('kredit', 'kredit', 'trim|required|numeric');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "tbl_rekening_koran.xls";
        $judul = "tbl_rekening_koran";
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
	xlsWriteLabel($tablehead, $kolomhead++, "Uuid Rek Koran");
	xlsWriteLabel($tablehead, $kolomhead++, "Tgl Transaksi");
	xlsWriteLabel($tablehead, $kolomhead++, "Uraian");
	xlsWriteLabel($tablehead, $kolomhead++, "Chq");
	xlsWriteLabel($tablehead, $kolomhead++, "Debet");
	xlsWriteLabel($tablehead, $kolomhead++, "Kredit");

	foreach ($this->Tbl_rekening_koran_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
	    xlsWriteLabel($tablebody, $kolombody++, $data->uuid_rek_koran);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tgl_transaksi);
	    xlsWriteLabel($tablebody, $kolombody++, $data->uraian);
	    xlsWriteNumber($tablebody, $kolombody++, $data->chq);
	    xlsWriteNumber($tablebody, $kolombody++, $data->debet);
	    xlsWriteNumber($tablebody, $kolombody++, $data->kredit);

	    $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

}

/* End of file Tbl_rekening_koran.php */
/* Location: ./application/controllers/Tbl_rekening_koran.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2025-02-13 10:27:52 */
/* http://harviacode.com */