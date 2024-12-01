<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jurnal_penerimaan_kas extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Jurnal_penerimaan_kas_model');
        $this->load->library('form_validation');        
	$this->load->library('datatables');
    }

    public function index()
    {
        $this->load->view('jurnal_penerimaan_kas/jurnal_penerimaan_kas_list');
    } 
    
    public function json() {
        header('Content-Type: application/json');
        echo $this->Jurnal_penerimaan_kas_model->json();
    }

    public function read($id) 
    {
        $row = $this->Jurnal_penerimaan_kas_model->get_by_id($id);
        if ($row) {
            $data = array(
		'nomor' => $row->nomor,
		'tanggal' => $row->tanggal,
		'nomorbuktibkm' => $row->nomorbuktibkm,
		'pl' => $row->pl,
		'keterangan' => $row->keterangan,
		'debet_11101_kas_besar' => $row->debet_11101_kas_besar,
		'kredit_11301_pu_non_angsuran' => $row->kredit_11301_pu_non_angsuran,
		'serba_serbi_rekening' => $row->serba_serbi_rekening,
		'serba_serbi_jumlah' => $row->serba_serbi_jumlah,
	    );
            $this->load->view('jurnal_penerimaan_kas/jurnal_penerimaan_kas_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_penerimaan_kas'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('jurnal_penerimaan_kas/create_action'),
	    'nomor' => set_value('nomor'),
	    'tanggal' => set_value('tanggal'),
	    'nomorbuktibkm' => set_value('nomorbuktibkm'),
	    'pl' => set_value('pl'),
	    'keterangan' => set_value('keterangan'),
	    'debet_11101_kas_besar' => set_value('debet_11101_kas_besar'),
	    'kredit_11301_pu_non_angsuran' => set_value('kredit_11301_pu_non_angsuran'),
	    'serba_serbi_rekening' => set_value('serba_serbi_rekening'),
	    'serba_serbi_jumlah' => set_value('serba_serbi_jumlah'),
	);
        $this->load->view('jurnal_penerimaan_kas/jurnal_penerimaan_kas_form', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'tanggal' => $this->input->post('tanggal',TRUE),
		'nomorbuktibkm' => $this->input->post('nomorbuktibkm',TRUE),
		'pl' => $this->input->post('pl',TRUE),
		'keterangan' => $this->input->post('keterangan',TRUE),
		'debet_11101_kas_besar' => $this->input->post('debet_11101_kas_besar',TRUE),
		'kredit_11301_pu_non_angsuran' => $this->input->post('kredit_11301_pu_non_angsuran',TRUE),
		'serba_serbi_rekening' => $this->input->post('serba_serbi_rekening',TRUE),
		'serba_serbi_jumlah' => $this->input->post('serba_serbi_jumlah',TRUE),
	    );

            $this->Jurnal_penerimaan_kas_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('jurnal_penerimaan_kas'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Jurnal_penerimaan_kas_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('jurnal_penerimaan_kas/update_action'),
		'nomor' => set_value('nomor', $row->nomor),
		'tanggal' => set_value('tanggal', $row->tanggal),
		'nomorbuktibkm' => set_value('nomorbuktibkm', $row->nomorbuktibkm),
		'pl' => set_value('pl', $row->pl),
		'keterangan' => set_value('keterangan', $row->keterangan),
		'debet_11101_kas_besar' => set_value('debet_11101_kas_besar', $row->debet_11101_kas_besar),
		'kredit_11301_pu_non_angsuran' => set_value('kredit_11301_pu_non_angsuran', $row->kredit_11301_pu_non_angsuran),
		'serba_serbi_rekening' => set_value('serba_serbi_rekening', $row->serba_serbi_rekening),
		'serba_serbi_jumlah' => set_value('serba_serbi_jumlah', $row->serba_serbi_jumlah),
	    );
            $this->load->view('jurnal_penerimaan_kas/jurnal_penerimaan_kas_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_penerimaan_kas'));
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
		'nomorbuktibkm' => $this->input->post('nomorbuktibkm',TRUE),
		'pl' => $this->input->post('pl',TRUE),
		'keterangan' => $this->input->post('keterangan',TRUE),
		'debet_11101_kas_besar' => $this->input->post('debet_11101_kas_besar',TRUE),
		'kredit_11301_pu_non_angsuran' => $this->input->post('kredit_11301_pu_non_angsuran',TRUE),
		'serba_serbi_rekening' => $this->input->post('serba_serbi_rekening',TRUE),
		'serba_serbi_jumlah' => $this->input->post('serba_serbi_jumlah',TRUE),
	    );

            $this->Jurnal_penerimaan_kas_model->update($this->input->post('nomor', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('jurnal_penerimaan_kas'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Jurnal_penerimaan_kas_model->get_by_id($id);

        if ($row) {
            $this->Jurnal_penerimaan_kas_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('jurnal_penerimaan_kas'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_penerimaan_kas'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('tanggal', 'tanggal', 'trim|required');
	$this->form_validation->set_rules('nomorbuktibkm', 'nomorbuktibkm', 'trim|required');
	$this->form_validation->set_rules('pl', 'pl', 'trim|required');
	$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');
	$this->form_validation->set_rules('debet_11101_kas_besar', 'debet 11101 kas besar', 'trim|required');
	$this->form_validation->set_rules('kredit_11301_pu_non_angsuran', 'kredit 11301 pu non angsuran', 'trim|required');
	$this->form_validation->set_rules('serba_serbi_rekening', 'serba serbi rekening', 'trim|required');
	$this->form_validation->set_rules('serba_serbi_jumlah', 'serba serbi jumlah', 'trim|required');

	$this->form_validation->set_rules('nomor', 'nomor', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "jurnal_penerimaan_kas.xls";
        $judul = "jurnal_penerimaan_kas";
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
	xlsWriteLabel($tablehead, $kolomhead++, "Nomorbuktibkm");
	xlsWriteLabel($tablehead, $kolomhead++, "Pl");
	xlsWriteLabel($tablehead, $kolomhead++, "Keterangan");
	xlsWriteLabel($tablehead, $kolomhead++, "Debet 11101 Kas Besar");
	xlsWriteLabel($tablehead, $kolomhead++, "Kredit 11301 Pu Non Angsuran");
	xlsWriteLabel($tablehead, $kolomhead++, "Serba Serbi Rekening");
	xlsWriteLabel($tablehead, $kolomhead++, "Serba Serbi Jumlah");

	foreach ($this->Jurnal_penerimaan_kas_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tanggal);
	    xlsWriteLabel($tablebody, $kolombody++, $data->nomorbuktibkm);
	    xlsWriteNumber($tablebody, $kolombody++, $data->pl);
	    xlsWriteLabel($tablebody, $kolombody++, $data->keterangan);
	    xlsWriteLabel($tablebody, $kolombody++, $data->debet_11101_kas_besar);
	    xlsWriteLabel($tablebody, $kolombody++, $data->kredit_11301_pu_non_angsuran);
	    xlsWriteLabel($tablebody, $kolombody++, $data->serba_serbi_rekening);
	    xlsWriteLabel($tablebody, $kolombody++, $data->serba_serbi_jumlah);

	    $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

}

/* End of file Jurnal_penerimaan_kas.php */
/* Location: ./application/controllers/Jurnal_penerimaan_kas.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-12-01 11:36:49 */
/* http://harviacode.com */