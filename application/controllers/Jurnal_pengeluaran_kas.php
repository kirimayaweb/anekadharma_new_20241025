<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jurnal_pengeluaran_kas extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Jurnal_pengeluaran_kas_model');
        $this->load->library('form_validation');        
	$this->load->library('datatables');
    }

    public function index()
    {
        $this->load->view('anekadharma/jurnal_pengeluaran_kas/jurnal_pengeluaran_kas_list');
    } 
    
    public function json() {
        header('Content-Type: application/json');
        echo $this->Jurnal_pengeluaran_kas_model->json();
    }

    public function read($id) 
    {
        $row = $this->Jurnal_pengeluaran_kas_model->get_by_id($id);
        if ($row) {
            $data = array(
		'nomor' => $row->nomor,
		'tanggal' => $row->tanggal,
		'nomor_bukti_bkk' => $row->nomor_bukti_bkk,
		'pl' => $row->pl,
		'keterangan' => $row->keterangan,
		'debet_21101uu_dagang' => $row->debet_21101uu_dagang,
		'serba-serbi_nomor_rekening' => $row->serba-serbi_nomor_rekening,
		'serba_serbi_jumlah' => $row->serba_serbi_jumlah,
		'kredit_11101_kas_besar' => $row->kredit_11101_kas_besar,
	    );
            $this->load->view('jurnal_pengeluaran_kas/jurnal_pengeluaran_kas_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_pengeluaran_kas'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('jurnal_pengeluaran_kas/create_action'),
	    'nomor' => set_value('nomor'),
	    'tanggal' => set_value('tanggal'),
	    'nomor_bukti_bkk' => set_value('nomor_bukti_bkk'),
	    'pl' => set_value('pl'),
	    'keterangan' => set_value('keterangan'),
	    'debet_21101uu_dagang' => set_value('debet_21101uu_dagang'),
	    'serba-serbi_nomor_rekening' => set_value('serba-serbi_nomor_rekening'),
	    'serba_serbi_jumlah' => set_value('serba_serbi_jumlah'),
	    'kredit_11101_kas_besar' => set_value('kredit_11101_kas_besar'),
	);
        $this->load->view('jurnal_pengeluaran_kas/jurnal_pengeluaran_kas_form', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'tanggal' => $this->input->post('tanggal',TRUE),
		'nomor_bukti_bkk' => $this->input->post('nomor_bukti_bkk',TRUE),
		'pl' => $this->input->post('pl',TRUE),
		'keterangan' => $this->input->post('keterangan',TRUE),
		'debet_21101uu_dagang' => $this->input->post('debet_21101uu_dagang',TRUE),
		'serba-serbi_nomor_rekening' => $this->input->post('serba-serbi_nomor_rekening',TRUE),
		'serba_serbi_jumlah' => $this->input->post('serba_serbi_jumlah',TRUE),
		'kredit_11101_kas_besar' => $this->input->post('kredit_11101_kas_besar',TRUE),
	    );

            $this->Jurnal_pengeluaran_kas_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('jurnal_pengeluaran_kas'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Jurnal_pengeluaran_kas_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('jurnal_pengeluaran_kas/update_action'),
		'nomor' => set_value('nomor', $row->nomor),
		'tanggal' => set_value('tanggal', $row->tanggal),
		'nomor_bukti_bkk' => set_value('nomor_bukti_bkk', $row->nomor_bukti_bkk),
		'pl' => set_value('pl', $row->pl),
		'keterangan' => set_value('keterangan', $row->keterangan),
		'debet_21101uu_dagang' => set_value('debet_21101uu_dagang', $row->debet_21101uu_dagang),
		'serba-serbi_nomor_rekening' => set_value('serba-serbi_nomor_rekening', $row->serba-serbi_nomor_rekening),
		'serba_serbi_jumlah' => set_value('serba_serbi_jumlah', $row->serba_serbi_jumlah),
		'kredit_11101_kas_besar' => set_value('kredit_11101_kas_besar', $row->kredit_11101_kas_besar),
	    );
            $this->load->view('jurnal_pengeluaran_kas/jurnal_pengeluaran_kas_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_pengeluaran_kas'));
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
		'nomor_bukti_bkk' => $this->input->post('nomor_bukti_bkk',TRUE),
		'pl' => $this->input->post('pl',TRUE),
		'keterangan' => $this->input->post('keterangan',TRUE),
		'debet_21101uu_dagang' => $this->input->post('debet_21101uu_dagang',TRUE),
		'serba-serbi_nomor_rekening' => $this->input->post('serba-serbi_nomor_rekening',TRUE),
		'serba_serbi_jumlah' => $this->input->post('serba_serbi_jumlah',TRUE),
		'kredit_11101_kas_besar' => $this->input->post('kredit_11101_kas_besar',TRUE),
	    );

            $this->Jurnal_pengeluaran_kas_model->update($this->input->post('nomor', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('jurnal_pengeluaran_kas'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Jurnal_pengeluaran_kas_model->get_by_id($id);

        if ($row) {
            $this->Jurnal_pengeluaran_kas_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('jurnal_pengeluaran_kas'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_pengeluaran_kas'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('tanggal', 'tanggal', 'trim|required');
	$this->form_validation->set_rules('nomor_bukti_bkk', 'nomor bukti bkk', 'trim|required');
	$this->form_validation->set_rules('pl', 'pl', 'trim|required');
	$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');
	$this->form_validation->set_rules('debet_21101uu_dagang', 'debet 21101uu dagang', 'trim|required');
	$this->form_validation->set_rules('serba-serbi_nomor_rekening', 'serba-serbi nomor rekening', 'trim|required');
	$this->form_validation->set_rules('serba_serbi_jumlah', 'serba serbi jumlah', 'trim|required');
	$this->form_validation->set_rules('kredit_11101_kas_besar', 'kredit 11101 kas besar', 'trim|required');

	$this->form_validation->set_rules('nomor', 'nomor', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "jurnal_pengeluaran_kas.xls";
        $judul = "jurnal_pengeluaran_kas";
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
	xlsWriteLabel($tablehead, $kolomhead++, "Nomor Bukti Bkk");
	xlsWriteLabel($tablehead, $kolomhead++, "Pl");
	xlsWriteLabel($tablehead, $kolomhead++, "Keterangan");
	xlsWriteLabel($tablehead, $kolomhead++, "Debet 21101uu Dagang");
	xlsWriteLabel($tablehead, $kolomhead++, "Serba-serbi Nomor Rekening");
	xlsWriteLabel($tablehead, $kolomhead++, "Serba Serbi Jumlah");
	xlsWriteLabel($tablehead, $kolomhead++, "Kredit 11101 Kas Besar");

	foreach ($this->Jurnal_pengeluaran_kas_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tanggal);
	    xlsWriteLabel($tablebody, $kolombody++, $data->nomor_bukti_bkk);
	    xlsWriteLabel($tablebody, $kolombody++, $data->pl);
	    xlsWriteLabel($tablebody, $kolombody++, $data->keterangan);
	    xlsWriteLabel($tablebody, $kolombody++, $data->debet_21101uu_dagang);
	    xlsWriteLabel($tablebody, $kolombody++, $data->serba-serbi_nomor_rekening);
	    xlsWriteLabel($tablebody, $kolombody++, $data->serba_serbi_jumlah);
	    xlsWriteLabel($tablebody, $kolombody++, $data->kredit_11101_kas_besar);

	    $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

}

/* End of file Jurnal_pengeluaran_kas.php */
/* Location: ./application/controllers/Jurnal_pengeluaran_kas.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-12-01 11:39:19 */
/* http://harviacode.com */