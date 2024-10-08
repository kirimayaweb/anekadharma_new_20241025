<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_pengirim extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Tbl_pengirim_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->uri->segment(3));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . '.php/c_url/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'index.php/tbl_pengirim/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'index.php/tbl_pengirim/index/';
            $config['first_url'] = base_url() . 'index.php/tbl_pengirim/index/';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = FALSE;
        $config['total_rows'] = $this->Tbl_pengirim_model->total_rows($q);
        $tbl_pengirim = $this->Tbl_pengirim_model->get_limit_data($config['per_page'], $start, $q);
        $config['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
        $config['full_tag_close'] = '</ul>';
        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'tbl_pengirim_data' => $tbl_pengirim,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->template->load('template','tbl_pengirim/tbl_pengirim_list', $data);
    }

    public function read($id) 
    {
        $row = $this->Tbl_pengirim_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'uuid_pengirim' => $row->uuid_pengirim,
		'date_input' => $row->date_input,
		'id_user_input' => $row->id_user_input,
		'nama_pengirim' => $row->nama_pengirim,
		'alamat_pengirim' => $row->alamat_pengirim,
		'notelp_pengirim' => $row->notelp_pengirim,
		'keterangan' => $row->keterangan,
	    );
            $this->template->load('template','tbl_pengirim/tbl_pengirim_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_pengirim'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('tbl_pengirim/create_action'),
	    'id' => set_value('id'),
	    'uuid_pengirim' => set_value('uuid_pengirim'),
	    'date_input' => set_value('date_input'),
	    'id_user_input' => set_value('id_user_input'),
	    'nama_pengirim' => set_value('nama_pengirim'),
	    'alamat_pengirim' => set_value('alamat_pengirim'),
	    'notelp_pengirim' => set_value('notelp_pengirim'),
	    'keterangan' => set_value('keterangan'),
	);
        $this->template->load('template','tbl_pengirim/tbl_pengirim_form', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'id' => $this->input->post('id',TRUE),
		'uuid_pengirim' => $this->input->post('uuid_pengirim',TRUE),
		'date_input' => $this->input->post('date_input',TRUE),
		'id_user_input' => $this->input->post('id_user_input',TRUE),
		'nama_pengirim' => $this->input->post('nama_pengirim',TRUE),
		'alamat_pengirim' => $this->input->post('alamat_pengirim',TRUE),
		'notelp_pengirim' => $this->input->post('notelp_pengirim',TRUE),
		'keterangan' => $this->input->post('keterangan',TRUE),
	    );

            $this->Tbl_pengirim_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success 2');
            redirect(site_url('tbl_pengirim'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Tbl_pengirim_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('tbl_pengirim/update_action'),
		'id' => set_value('id', $row->id),
		'uuid_pengirim' => set_value('uuid_pengirim', $row->uuid_pengirim),
		'date_input' => set_value('date_input', $row->date_input),
		'id_user_input' => set_value('id_user_input', $row->id_user_input),
		'nama_pengirim' => set_value('nama_pengirim', $row->nama_pengirim),
		'alamat_pengirim' => set_value('alamat_pengirim', $row->alamat_pengirim),
		'notelp_pengirim' => set_value('notelp_pengirim', $row->notelp_pengirim),
		'keterangan' => set_value('keterangan', $row->keterangan),
	    );
            $this->template->load('template','tbl_pengirim/tbl_pengirim_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_pengirim'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('', TRUE));
        } else {
            $data = array(
		'id' => $this->input->post('id',TRUE),
		'uuid_pengirim' => $this->input->post('uuid_pengirim',TRUE),
		'date_input' => $this->input->post('date_input',TRUE),
		'id_user_input' => $this->input->post('id_user_input',TRUE),
		'nama_pengirim' => $this->input->post('nama_pengirim',TRUE),
		'alamat_pengirim' => $this->input->post('alamat_pengirim',TRUE),
		'notelp_pengirim' => $this->input->post('notelp_pengirim',TRUE),
		'keterangan' => $this->input->post('keterangan',TRUE),
	    );

            $this->Tbl_pengirim_model->update($this->input->post('', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('tbl_pengirim'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Tbl_pengirim_model->get_by_id($id);

        if ($row) {
            $this->Tbl_pengirim_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('tbl_pengirim'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_pengirim'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('id', 'id', 'trim|required');
	$this->form_validation->set_rules('uuid_pengirim', 'uuid pengirim', 'trim|required');
	$this->form_validation->set_rules('date_input', 'date input', 'trim|required');
	$this->form_validation->set_rules('id_user_input', 'id user input', 'trim|required');
	$this->form_validation->set_rules('nama_pengirim', 'nama pengirim', 'trim|required');
	$this->form_validation->set_rules('alamat_pengirim', 'alamat pengirim', 'trim|required');
	$this->form_validation->set_rules('notelp_pengirim', 'notelp pengirim', 'trim|required');
	$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

	$this->form_validation->set_rules('', '', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Tbl_pengirim.php */
/* Location: ./application/controllers/Tbl_pengirim.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2021-07-03 04:09:43 */
/* http://harviacode.com */