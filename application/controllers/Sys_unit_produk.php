<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sys_unit_produk extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array('Sys_unit_produk_model','Sys_unit_model'));
        $this->load->library('form_validation');        
	$this->load->library('datatables');
    }

    public function index()
    {
        $this->load->view('sys_unit_produk/sys_unit_produk_list');
    } 
    
    public function json() {
        header('Content-Type: application/json');
        echo $this->Sys_unit_produk_model->json();
    }

    public function read($id) 
    {
        $row = $this->Sys_unit_produk_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'uuid_unit' => $row->uuid_unit,
		'kode_unit' => $row->kode_unit,
		'nama_unit' => $row->nama_unit,
		'tgl_transaksi' => $row->tgl_transaksi,
		'uuid_barang' => $row->uuid_barang,
		'kode_barang' => $row->kode_barang,
		'nama_barang' => $row->nama_barang,
		'jumlah_produksi' => $row->jumlah_produksi,
		'satuan' => $row->satuan,
		'harga_satuan' => $row->harga_satuan,
	    );
            $this->load->view('sys_unit_produk/sys_unit_produk_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_unit_produk'));
        }
    }




    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('sys_unit_produk/create_action'),
	    'id' => set_value('id'),
	    'uuid_unit' => set_value('uuid_unit'),
	    'kode_unit' => set_value('kode_unit'),
	    'nama_unit' => set_value('nama_unit'),
	    'tgl_transaksi' => set_value('tgl_transaksi'),
	    'uuid_barang' => set_value('uuid_barang'),
	    'kode_barang' => set_value('kode_barang'),
	    'nama_barang' => set_value('nama_barang'),
	    'jumlah_produksi' => set_value('jumlah_produksi'),
	    'satuan' => set_value('satuan'),
	    'harga_satuan' => set_value('harga_satuan'),
	);
        $this->load->view('sys_unit_produk/sys_unit_produk_form', $data);
        
    }
    

    public function create_unit($uuid_unit_selected) 
    {

        $data_unit = $this->Sys_unit_model->get_by_uuid_unit($uuid_unit_selected);

        $data = array(
            'button' => 'Simpan',
            'action' => site_url('sys_unit_produk/create_action_unit/'.$uuid_unit_selected),
	    'id' => set_value('id'),
	    'uuid_unit' => $uuid_unit_selected,
	    'kode_unit' => set_value('kode_unit'),
	    'nama_unit' => $data_unit->nama_unit,
	    'tgl_transaksi' => set_value('tgl_transaksi'),
	    'uuid_barang' => set_value('uuid_barang'),
	    'kode_barang' => set_value('kode_barang'),
	    'nama_barang' => set_value('nama_barang'),
	    'jumlah_produksi' => set_value('jumlah_produksi'),
	    'satuan' => set_value('satuan'),
	    'harga_satuan' => set_value('harga_satuan'),
	);
        // $this->load->view('sys_unit_produk/sys_unit_produk_form', $data);
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_unit_produk/adminlte310_sys_unit_produk_form', $data);
    }
    



    public function create_action_unit($uuid_unit_selected) 
    {


        
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {

            $data_unit = $this->Sys_unit_model->get_by_uuid_unit($uuid_unit_selected);

            $data = array(
		'uuid_unit' => $uuid_unit_selected,
		// 'kode_unit' => $this->input->post('kode_unit',TRUE),
		'nama_unit' => $data_unit->nama_unit,
		'tgl_transaksi' => $this->input->post('tgl_transaksi',TRUE),
		// 'uuid_barang' => $this->input->post('uuid_barang',TRUE),
		// 'kode_barang' => $this->input->post('kode_barang',TRUE),
		'nama_barang' => $this->input->post('nama_barang',TRUE),
		'jumlah_produksi' => $this->input->post('jumlah_produksi',TRUE),
		'satuan' => $this->input->post('satuan',TRUE),
		'harga_satuan' => $this->input->post('harga_satuan',TRUE),
	    );

            $this->Sys_unit_produk_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('sys_unit/detail_unit/'.$uuid_unit_selected));
        }
    }
    

    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'uuid_unit' => $this->input->post('uuid_unit',TRUE),
		'kode_unit' => $this->input->post('kode_unit',TRUE),
		'nama_unit' => $this->input->post('nama_unit',TRUE),
		'tgl_transaksi' => $this->input->post('tgl_transaksi',TRUE),
		'uuid_barang' => $this->input->post('uuid_barang',TRUE),
		'kode_barang' => $this->input->post('kode_barang',TRUE),
		'nama_barang' => $this->input->post('nama_barang',TRUE),
		'jumlah_produksi' => $this->input->post('jumlah_produksi',TRUE),
		'satuan' => $this->input->post('satuan',TRUE),
		'harga_satuan' => $this->input->post('harga_satuan',TRUE),
	    );

            $this->Sys_unit_produk_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('sys_unit_produk'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Sys_unit_produk_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('sys_unit_produk/update_action'),
		'id' => set_value('id', $row->id),
		'uuid_unit' => set_value('uuid_unit', $row->uuid_unit),
		'kode_unit' => set_value('kode_unit', $row->kode_unit),
		'nama_unit' => set_value('nama_unit', $row->nama_unit),
		'tgl_transaksi' => set_value('tgl_transaksi', $row->tgl_transaksi),
		'uuid_barang' => set_value('uuid_barang', $row->uuid_barang),
		'kode_barang' => set_value('kode_barang', $row->kode_barang),
		'nama_barang' => set_value('nama_barang', $row->nama_barang),
		'jumlah_produksi' => set_value('jumlah_produksi', $row->jumlah_produksi),
		'satuan' => set_value('satuan', $row->satuan),
		'harga_satuan' => set_value('harga_satuan', $row->harga_satuan),
	    );
            $this->load->view('sys_unit_produk/sys_unit_produk_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_unit_produk'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
		'uuid_unit' => $this->input->post('uuid_unit',TRUE),
		'kode_unit' => $this->input->post('kode_unit',TRUE),
		'nama_unit' => $this->input->post('nama_unit',TRUE),
		'tgl_transaksi' => $this->input->post('tgl_transaksi',TRUE),
		'uuid_barang' => $this->input->post('uuid_barang',TRUE),
		'kode_barang' => $this->input->post('kode_barang',TRUE),
		'nama_barang' => $this->input->post('nama_barang',TRUE),
		'jumlah_produksi' => $this->input->post('jumlah_produksi',TRUE),
		'satuan' => $this->input->post('satuan',TRUE),
		'harga_satuan' => $this->input->post('harga_satuan',TRUE),
	    );

            $this->Sys_unit_produk_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('sys_unit_produk'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Sys_unit_produk_model->get_by_id($id);

        if ($row) {
            $this->Sys_unit_produk_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('sys_unit_produk'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_unit_produk'));
        }
    }

    public function _rules() 
    {
	// $this->form_validation->set_rules('uuid_unit', 'uuid unit', 'trim|required');
	// $this->form_validation->set_rules('kode_unit', 'kode unit', 'trim|required');
	// $this->form_validation->set_rules('nama_unit', 'nama unit', 'trim|required');
	$this->form_validation->set_rules('tgl_transaksi', 'tgl transaksi', 'trim|required');
	// $this->form_validation->set_rules('uuid_barang', 'uuid barang', 'trim|required');
	// $this->form_validation->set_rules('kode_barang', 'kode barang', 'trim|required');
	$this->form_validation->set_rules('nama_barang', 'nama barang', 'trim|required');
	$this->form_validation->set_rules('jumlah_produksi', 'jumlah produksi', 'trim|required|numeric');
	$this->form_validation->set_rules('satuan', 'satuan', 'trim|required');
	$this->form_validation->set_rules('harga_satuan', 'harga satuan', 'trim|required|numeric');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "sys_unit_produk.xls";
        $judul = "sys_unit_produk";
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
	xlsWriteLabel($tablehead, $kolomhead++, "Uuid Unit");
	xlsWriteLabel($tablehead, $kolomhead++, "Kode Unit");
	xlsWriteLabel($tablehead, $kolomhead++, "Nama Unit");
	xlsWriteLabel($tablehead, $kolomhead++, "Tgl Transaksi");
	xlsWriteLabel($tablehead, $kolomhead++, "Uuid Barang");
	xlsWriteLabel($tablehead, $kolomhead++, "Kode Barang");
	xlsWriteLabel($tablehead, $kolomhead++, "Nama Barang");
	xlsWriteLabel($tablehead, $kolomhead++, "Jumlah Produksi");
	xlsWriteLabel($tablehead, $kolomhead++, "Satuan");
	xlsWriteLabel($tablehead, $kolomhead++, "Harga Satuan");

	foreach ($this->Sys_unit_produk_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
	    xlsWriteLabel($tablebody, $kolombody++, $data->uuid_unit);
	    xlsWriteLabel($tablebody, $kolombody++, $data->kode_unit);
	    xlsWriteLabel($tablebody, $kolombody++, $data->nama_unit);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tgl_transaksi);
	    xlsWriteLabel($tablebody, $kolombody++, $data->uuid_barang);
	    xlsWriteNumber($tablebody, $kolombody++, $data->kode_barang);
	    xlsWriteLabel($tablebody, $kolombody++, $data->nama_barang);
	    xlsWriteNumber($tablebody, $kolombody++, $data->jumlah_produksi);
	    xlsWriteLabel($tablebody, $kolombody++, $data->satuan);
	    xlsWriteNumber($tablebody, $kolombody++, $data->harga_satuan);

	    $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

}

/* End of file Sys_unit_produk.php */
/* Location: ./application/controllers/Sys_unit_produk.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-09-30 05:21:50 */
/* http://harviacode.com */