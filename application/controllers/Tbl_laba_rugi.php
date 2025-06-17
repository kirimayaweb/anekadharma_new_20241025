<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_laba_rugi extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Tbl_laba_rugi_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . 'tbl_laba_rugi/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'tbl_laba_rugi/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'tbl_laba_rugi/index.html';
            $config['first_url'] = base_url() . 'tbl_laba_rugi/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Tbl_laba_rugi_model->total_rows($q);
        $tbl_laba_rugi = $this->Tbl_laba_rugi_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'tbl_laba_rugi_data' => $tbl_laba_rugi,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->load->view('tbl_laba_rugi/tbl_laba_rugi_list', $data);
    }

    public function read($id) 
    {
        $row = $this->Tbl_laba_rugi_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'date_input' => $row->date_input,
		'date_transaksi' => $row->date_transaksi,
		'tahun_transaksi' => $row->tahun_transaksi,
		'bulan_transaksi' => $row->bulan_transaksi,
		'uuid_data_neraca' => $row->uuid_data_neraca,
	    );
            $this->load->view('tbl_laba_rugi/tbl_laba_rugi_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_laba_rugi'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('tbl_laba_rugi/create_action'),
	    'id' => set_value('id'),
	    'date_input' => set_value('date_input'),
	    'date_transaksi' => set_value('date_transaksi'),
	    'tahun_transaksi' => set_value('tahun_transaksi'),
	    'bulan_transaksi' => set_value('bulan_transaksi'),
	    'uuid_data_neraca' => set_value('uuid_data_neraca'),
	);
        $this->load->view('tbl_laba_rugi/tbl_laba_rugi_form', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'date_input' => $this->input->post('date_input',TRUE),
		'date_transaksi' => $this->input->post('date_transaksi',TRUE),
		'tahun_transaksi' => $this->input->post('tahun_transaksi',TRUE),
		'bulan_transaksi' => $this->input->post('bulan_transaksi',TRUE),
		'uuid_data_neraca' => $this->input->post('uuid_data_neraca',TRUE),
	    );

            $this->Tbl_laba_rugi_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('tbl_laba_rugi'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Tbl_laba_rugi_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('tbl_laba_rugi/update_action'),
		'id' => set_value('id', $row->id),
		'date_input' => set_value('date_input', $row->date_input),
		'date_transaksi' => set_value('date_transaksi', $row->date_transaksi),
		'tahun_transaksi' => set_value('tahun_transaksi', $row->tahun_transaksi),
		'bulan_transaksi' => set_value('bulan_transaksi', $row->bulan_transaksi),
		'uuid_data_neraca' => set_value('uuid_data_neraca', $row->uuid_data_neraca),
	    );
            $this->load->view('tbl_laba_rugi/tbl_laba_rugi_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_laba_rugi'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
		'date_input' => $this->input->post('date_input',TRUE),
		'date_transaksi' => $this->input->post('date_transaksi',TRUE),
		'tahun_transaksi' => $this->input->post('tahun_transaksi',TRUE),
		'bulan_transaksi' => $this->input->post('bulan_transaksi',TRUE),
		'uuid_data_neraca' => $this->input->post('uuid_data_neraca',TRUE),
	    );

            $this->Tbl_laba_rugi_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('tbl_laba_rugi'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Tbl_laba_rugi_model->get_by_id($id);

        if ($row) {
            $this->Tbl_laba_rugi_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('tbl_laba_rugi'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_laba_rugi'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('date_input', 'date input', 'trim|required');
	$this->form_validation->set_rules('date_transaksi', 'date transaksi', 'trim|required');
	$this->form_validation->set_rules('tahun_transaksi', 'tahun transaksi', 'trim|required');
	$this->form_validation->set_rules('bulan_transaksi', 'bulan transaksi', 'trim|required');
	$this->form_validation->set_rules('uuid_data_neraca', 'uuid data neraca', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Tbl_laba_rugi.php */
/* Location: ./application/controllers/Tbl_laba_rugi.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2025-06-17 02:28:51 */
/* http://harviacode.com */