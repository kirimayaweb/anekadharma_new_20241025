<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sys_supplier extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Sys_supplier_model');
        $this->load->library('form_validation');
    }

    public function indexXXXX()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . 'sys_supplier/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'sys_supplier/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'sys_supplier/index.html';
            $config['first_url'] = base_url() . 'sys_supplier/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Sys_supplier_model->total_rows($q);
        $sys_supplier = $this->Sys_supplier_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'sys_supplier_data' => $sys_supplier,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        // $this->load->view('sys_supplier/sys_supplier_list', $data);
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_supplier/sys_supplier_list', $data);
    }

    public function index(){
        $data_supplier = $this->Sys_supplier_model->get_all();

        $data = array(
            'data_supplier' => $data_supplier,
            'action' => site_url('Sys_supplier/cari_unit'),
        );




        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_supplier/adminlte310_sys_supplier_list', $data);
    }

    public function read($id) 
    {
        $row = $this->Sys_supplier_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'uuid_supplier' => $row->uuid_supplier,
		'kode_supplier' => $row->kode_supplier,
		'nama_supplier' => $row->nama_supplier,
		'nmr_kontak_supplier' => $row->nmr_kontak_supplier,
		'alamat_supplier' => $row->alamat_supplier,
		'keterangan' => $row->keterangan,
	    );
            $this->load->view('sys_supplier/sys_supplier_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_supplier'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Simpan',
            'action' => site_url('sys_supplier/create_action'),
	    'id' => set_value('id'),
	    'uuid_supplier' => set_value('uuid_supplier'),
	    'kode_supplier' => set_value('kode_supplier'),
	    'nama_supplier' => set_value('nama_supplier'),
	    'nmr_kontak_supplier' => set_value('nmr_kontak_supplier'),
	    'alamat_supplier' => set_value('alamat_supplier'),
	    'keterangan' => set_value('keterangan'),
	);

        // $this->load->view('sys_supplier/sys_supplier_form', $data);
    
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_supplier/adminlte310_sys_supplier_form', $data);
   
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		// 'uuid_supplier' => $this->input->post('uuid_supplier',TRUE),
		'kode_supplier' => $this->input->post('kode_supplier',TRUE),
		'nama_supplier' => $this->input->post('nama_supplier',TRUE),
		'nmr_kontak_supplier' => $this->input->post('nmr_kontak_supplier',TRUE),
		'alamat_supplier' => $this->input->post('alamat_supplier',TRUE),
		'keterangan' => $this->input->post('keterangan',TRUE),
	    );

            $this->Sys_supplier_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('sys_supplier'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Sys_supplier_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('sys_supplier/update_action'),
		'id' => set_value('id', $row->id),
		'uuid_supplier' => set_value('uuid_supplier', $row->uuid_supplier),
		'kode_supplier' => set_value('kode_supplier', $row->kode_supplier),
		'nama_supplier' => set_value('nama_supplier', $row->nama_supplier),
		'nmr_kontak_supplier' => set_value('nmr_kontak_supplier', $row->nmr_kontak_supplier),
		'alamat_supplier' => set_value('alamat_supplier', $row->alamat_supplier),
		'keterangan' => set_value('keterangan', $row->keterangan),
	    );
            $this->load->view('sys_supplier/sys_supplier_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_supplier'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
		// 'uuid_supplier' => $this->input->post('uuid_supplier',TRUE),
		'kode_supplier' => $this->input->post('kode_supplier',TRUE),
		'nama_supplier' => $this->input->post('nama_supplier',TRUE),
		'nmr_kontak_supplier' => $this->input->post('nmr_kontak_supplier',TRUE),
		'alamat_supplier' => $this->input->post('alamat_supplier',TRUE),
		'keterangan' => $this->input->post('keterangan',TRUE),
	    );

            $this->Sys_supplier_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('sys_supplier'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Sys_supplier_model->get_by_id($id);

        if ($row) {
            $this->Sys_supplier_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('sys_supplier'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_supplier'));
        }
    }

    public function _rules() 
    {
	// $this->form_validation->set_rules('uuid_supplier', 'uuid supplier', 'trim|required');
	// $this->form_validation->set_rules('kode_supplier', 'kode supplier', 'trim|required');
	$this->form_validation->set_rules('nama_supplier', 'nama supplier', 'trim|required');
	// $this->form_validation->set_rules('nmr_kontak_supplier', 'nmr kontak supplier', 'trim|required');
	// $this->form_validation->set_rules('alamat_supplier', 'alamat supplier', 'trim|required');
	// $this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "sys_supplier.xls";
        $judul = "sys_supplier";
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
	xlsWriteLabel($tablehead, $kolomhead++, "Uuid Supplier");
	xlsWriteLabel($tablehead, $kolomhead++, "Kode Supplier");
	xlsWriteLabel($tablehead, $kolomhead++, "Nama Supplier");
	xlsWriteLabel($tablehead, $kolomhead++, "Nmr Kontak Supplier");
	xlsWriteLabel($tablehead, $kolomhead++, "Alamat Supplier");
	xlsWriteLabel($tablehead, $kolomhead++, "Keterangan");

	foreach ($this->Sys_supplier_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
	    xlsWriteLabel($tablebody, $kolombody++, $data->uuid_supplier);
	    xlsWriteNumber($tablebody, $kolombody++, $data->kode_supplier);
	    xlsWriteLabel($tablebody, $kolombody++, $data->nama_supplier);
	    xlsWriteLabel($tablebody, $kolombody++, $data->nmr_kontak_supplier);
	    xlsWriteLabel($tablebody, $kolombody++, $data->alamat_supplier);
	    xlsWriteLabel($tablebody, $kolombody++, $data->keterangan);

	    $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

}

/* End of file Sys_supplier.php */
/* Location: ./application/controllers/Sys_supplier.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-09-05 09:25:41 */
/* http://harviacode.com */