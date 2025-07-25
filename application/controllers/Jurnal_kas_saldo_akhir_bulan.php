<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jurnal_kas_saldo_akhir_bulan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Jurnal_kas_saldo_akhir_bulan_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . 'jurnal_kas_saldo_akhir_bulan/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'jurnal_kas_saldo_akhir_bulan/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'jurnal_kas_saldo_akhir_bulan/index.html';
            $config['first_url'] = base_url() . 'jurnal_kas_saldo_akhir_bulan/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Jurnal_kas_saldo_akhir_bulan_model->total_rows($q);
        $jurnal_kas_saldo_akhir_bulan = $this->Jurnal_kas_saldo_akhir_bulan_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'jurnal_kas_saldo_akhir_bulan_data' => $jurnal_kas_saldo_akhir_bulan,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->load->view('jurnal_kas_saldo_akhir_bulan/jurnal_kas_saldo_akhir_bulan_list', $data);
    }

    public function read($id) 
    {
        $row = $this->Jurnal_kas_saldo_akhir_bulan_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'uuid_jurnal_kas_saldo_akhir_bulan' => $row->uuid_jurnal_kas_saldo_akhir_bulan,
		'kode_akun' => $row->kode_akun,
		'id_buku_besar' => $row->id_buku_besar,
		'tanggal' => $row->tanggal,
		'bukti' => $row->bukti,
		'pl' => $row->pl,
		'keterangan' => $row->keterangan,
		'kode_rekening' => $row->kode_rekening,
		'uuid_unit' => $row->uuid_unit,
		'kode_unit' => $row->kode_unit,
		'debet' => $row->debet,
		'kredit' => $row->kredit,
		'saldo' => $row->saldo,
	    );
            $this->load->view('jurnal_kas_saldo_akhir_bulan/jurnal_kas_saldo_akhir_bulan_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_kas_saldo_akhir_bulan'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('jurnal_kas_saldo_akhir_bulan/create_action'),
	    'id' => set_value('id'),
	    'uuid_jurnal_kas_saldo_akhir_bulan' => set_value('uuid_jurnal_kas_saldo_akhir_bulan'),
	    'kode_akun' => set_value('kode_akun'),
	    'id_buku_besar' => set_value('id_buku_besar'),
	    'tanggal' => set_value('tanggal'),
	    'bukti' => set_value('bukti'),
	    'pl' => set_value('pl'),
	    'keterangan' => set_value('keterangan'),
	    'kode_rekening' => set_value('kode_rekening'),
	    'uuid_unit' => set_value('uuid_unit'),
	    'kode_unit' => set_value('kode_unit'),
	    'debet' => set_value('debet'),
	    'kredit' => set_value('kredit'),
	    'saldo' => set_value('saldo'),
	);
        $this->load->view('jurnal_kas_saldo_akhir_bulan/jurnal_kas_saldo_akhir_bulan_form', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'uuid_jurnal_kas_saldo_akhir_bulan' => $this->input->post('uuid_jurnal_kas_saldo_akhir_bulan',TRUE),
		'kode_akun' => $this->input->post('kode_akun',TRUE),
		'id_buku_besar' => $this->input->post('id_buku_besar',TRUE),
		'tanggal' => $this->input->post('tanggal',TRUE),
		'bukti' => $this->input->post('bukti',TRUE),
		'pl' => $this->input->post('pl',TRUE),
		'keterangan' => $this->input->post('keterangan',TRUE),
		'kode_rekening' => $this->input->post('kode_rekening',TRUE),
		'uuid_unit' => $this->input->post('uuid_unit',TRUE),
		'kode_unit' => $this->input->post('kode_unit',TRUE),
		'debet' => $this->input->post('debet',TRUE),
		'kredit' => $this->input->post('kredit',TRUE),
		'saldo' => $this->input->post('saldo',TRUE),
	    );

            $this->Jurnal_kas_saldo_akhir_bulan_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('jurnal_kas_saldo_akhir_bulan'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Jurnal_kas_saldo_akhir_bulan_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('jurnal_kas_saldo_akhir_bulan/update_action'),
		'id' => set_value('id', $row->id),
		'uuid_jurnal_kas_saldo_akhir_bulan' => set_value('uuid_jurnal_kas_saldo_akhir_bulan', $row->uuid_jurnal_kas_saldo_akhir_bulan),
		'kode_akun' => set_value('kode_akun', $row->kode_akun),
		'id_buku_besar' => set_value('id_buku_besar', $row->id_buku_besar),
		'tanggal' => set_value('tanggal', $row->tanggal),
		'bukti' => set_value('bukti', $row->bukti),
		'pl' => set_value('pl', $row->pl),
		'keterangan' => set_value('keterangan', $row->keterangan),
		'kode_rekening' => set_value('kode_rekening', $row->kode_rekening),
		'uuid_unit' => set_value('uuid_unit', $row->uuid_unit),
		'kode_unit' => set_value('kode_unit', $row->kode_unit),
		'debet' => set_value('debet', $row->debet),
		'kredit' => set_value('kredit', $row->kredit),
		'saldo' => set_value('saldo', $row->saldo),
	    );
            $this->load->view('jurnal_kas_saldo_akhir_bulan/jurnal_kas_saldo_akhir_bulan_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_kas_saldo_akhir_bulan'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
		'uuid_jurnal_kas_saldo_akhir_bulan' => $this->input->post('uuid_jurnal_kas_saldo_akhir_bulan',TRUE),
		'kode_akun' => $this->input->post('kode_akun',TRUE),
		'id_buku_besar' => $this->input->post('id_buku_besar',TRUE),
		'tanggal' => $this->input->post('tanggal',TRUE),
		'bukti' => $this->input->post('bukti',TRUE),
		'pl' => $this->input->post('pl',TRUE),
		'keterangan' => $this->input->post('keterangan',TRUE),
		'kode_rekening' => $this->input->post('kode_rekening',TRUE),
		'uuid_unit' => $this->input->post('uuid_unit',TRUE),
		'kode_unit' => $this->input->post('kode_unit',TRUE),
		'debet' => $this->input->post('debet',TRUE),
		'kredit' => $this->input->post('kredit',TRUE),
		'saldo' => $this->input->post('saldo',TRUE),
	    );

            $this->Jurnal_kas_saldo_akhir_bulan_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('jurnal_kas_saldo_akhir_bulan'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Jurnal_kas_saldo_akhir_bulan_model->get_by_id($id);

        if ($row) {
            $this->Jurnal_kas_saldo_akhir_bulan_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('jurnal_kas_saldo_akhir_bulan'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_kas_saldo_akhir_bulan'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('uuid_jurnal_kas_saldo_akhir_bulan', 'uuid jurnal kas saldo akhir bulan', 'trim|required');
	$this->form_validation->set_rules('kode_akun', 'kode akun', 'trim|required');
	$this->form_validation->set_rules('id_buku_besar', 'id buku besar', 'trim|required');
	$this->form_validation->set_rules('tanggal', 'tanggal', 'trim|required');
	$this->form_validation->set_rules('bukti', 'bukti', 'trim|required');
	$this->form_validation->set_rules('pl', 'pl', 'trim|required');
	$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');
	$this->form_validation->set_rules('kode_rekening', 'kode rekening', 'trim|required');
	$this->form_validation->set_rules('uuid_unit', 'uuid unit', 'trim|required');
	$this->form_validation->set_rules('kode_unit', 'kode unit', 'trim|required');
	$this->form_validation->set_rules('debet', 'debet', 'trim|required|numeric');
	$this->form_validation->set_rules('kredit', 'kredit', 'trim|required|numeric');
	$this->form_validation->set_rules('saldo', 'saldo', 'trim|required|numeric');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Jurnal_kas_saldo_akhir_bulan.php */
/* Location: ./application/controllers/Jurnal_kas_saldo_akhir_bulan.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2025-07-25 12:28:21 */
/* http://harviacode.com */