<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sys_unit_produk_bahan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Sys_unit_produk_bahan_model');
        $this->load->library('form_validation');        
	$this->load->library('datatables');
    }

    public function index()
    {
        $this->load->view('sys_unit_produk_bahan/sys_unit_produk_bahan_list');
    } 
    
    public function json() {
        header('Content-Type: application/json');
        echo $this->Sys_unit_produk_bahan_model->json();
    }

    public function read($id) 
    {
        $row = $this->Sys_unit_produk_bahan_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'uuid_unit' => $row->uuid_unit,
		'uuid_persediaan' => $row->uuid_persediaan,
		'kode_unit' => $row->kode_unit,
		'nama_unit' => $row->nama_unit,
		'tgl_transaksi' => $row->tgl_transaksi,
		'uuid_produk' => $row->uuid_produk,
		'kode_barang_bahan' => $row->kode_barang_bahan,
		'nama_barang_bahan' => $row->nama_barang_bahan,
		'jumlah_bahan' => $row->jumlah_bahan,
		'satuan_bahan' => $row->satuan_bahan,
		'harga_satuan_bahan' => $row->harga_satuan_bahan,
	    );
            $this->load->view('sys_unit_produk_bahan/sys_unit_produk_bahan_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_unit_produk_bahan'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('sys_unit_produk_bahan/create_action'),
	    'id' => set_value('id'),
	    'uuid_unit' => set_value('uuid_unit'),
	    'uuid_persediaan' => set_value('uuid_persediaan'),
	    'kode_unit' => set_value('kode_unit'),
	    'nama_unit' => set_value('nama_unit'),
	    'tgl_transaksi' => set_value('tgl_transaksi'),
	    'uuid_produk' => set_value('uuid_produk'),
	    'kode_barang_bahan' => set_value('kode_barang_bahan'),
	    'nama_barang_bahan' => set_value('nama_barang_bahan'),
	    'jumlah_bahan' => set_value('jumlah_bahan'),
	    'satuan_bahan' => set_value('satuan_bahan'),
	    'harga_satuan_bahan' => set_value('harga_satuan_bahan'),
	);
        $this->load->view('sys_unit_produk_bahan/sys_unit_produk_bahan_form', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'uuid_unit' => $this->input->post('uuid_unit',TRUE),
		'uuid_persediaan' => $this->input->post('uuid_persediaan',TRUE),
		'kode_unit' => $this->input->post('kode_unit',TRUE),
		'nama_unit' => $this->input->post('nama_unit',TRUE),
		'tgl_transaksi' => $this->input->post('tgl_transaksi',TRUE),
		'uuid_produk' => $this->input->post('uuid_produk',TRUE),
		'kode_barang_bahan' => $this->input->post('kode_barang_bahan',TRUE),
		'nama_barang_bahan' => $this->input->post('nama_barang_bahan',TRUE),
		'jumlah_bahan' => $this->input->post('jumlah_bahan',TRUE),
		'satuan_bahan' => $this->input->post('satuan_bahan',TRUE),
		'harga_satuan_bahan' => $this->input->post('harga_satuan_bahan',TRUE),
	    );

            $this->Sys_unit_produk_bahan_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('sys_unit_produk_bahan'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Sys_unit_produk_bahan_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('sys_unit_produk_bahan/update_action'),
		'id' => set_value('id', $row->id),
		'uuid_unit' => set_value('uuid_unit', $row->uuid_unit),
		'uuid_persediaan' => set_value('uuid_persediaan', $row->uuid_persediaan),
		'kode_unit' => set_value('kode_unit', $row->kode_unit),
		'nama_unit' => set_value('nama_unit', $row->nama_unit),
		'tgl_transaksi' => set_value('tgl_transaksi', $row->tgl_transaksi),
		'uuid_produk' => set_value('uuid_produk', $row->uuid_produk),
		'kode_barang_bahan' => set_value('kode_barang_bahan', $row->kode_barang_bahan),
		'nama_barang_bahan' => set_value('nama_barang_bahan', $row->nama_barang_bahan),
		'jumlah_bahan' => set_value('jumlah_bahan', $row->jumlah_bahan),
		'satuan_bahan' => set_value('satuan_bahan', $row->satuan_bahan),
		'harga_satuan_bahan' => set_value('harga_satuan_bahan', $row->harga_satuan_bahan),
	    );
            $this->load->view('sys_unit_produk_bahan/sys_unit_produk_bahan_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_unit_produk_bahan'));
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
		'uuid_persediaan' => $this->input->post('uuid_persediaan',TRUE),
		'kode_unit' => $this->input->post('kode_unit',TRUE),
		'nama_unit' => $this->input->post('nama_unit',TRUE),
		'tgl_transaksi' => $this->input->post('tgl_transaksi',TRUE),
		'uuid_produk' => $this->input->post('uuid_produk',TRUE),
		'kode_barang_bahan' => $this->input->post('kode_barang_bahan',TRUE),
		'nama_barang_bahan' => $this->input->post('nama_barang_bahan',TRUE),
		'jumlah_bahan' => $this->input->post('jumlah_bahan',TRUE),
		'satuan_bahan' => $this->input->post('satuan_bahan',TRUE),
		'harga_satuan_bahan' => $this->input->post('harga_satuan_bahan',TRUE),
	    );

            $this->Sys_unit_produk_bahan_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('sys_unit_produk_bahan'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Sys_unit_produk_bahan_model->get_by_id($id);

        if ($row) {
            $this->Sys_unit_produk_bahan_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('sys_unit_produk_bahan'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_unit_produk_bahan'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('uuid_unit', 'uuid unit', 'trim|required');
	$this->form_validation->set_rules('uuid_persediaan', 'uuid persediaan', 'trim|required');
	$this->form_validation->set_rules('kode_unit', 'kode unit', 'trim|required');
	$this->form_validation->set_rules('nama_unit', 'nama unit', 'trim|required');
	$this->form_validation->set_rules('tgl_transaksi', 'tgl transaksi', 'trim|required');
	$this->form_validation->set_rules('uuid_produk', 'uuid produk', 'trim|required');
	$this->form_validation->set_rules('kode_barang_bahan', 'kode barang bahan', 'trim|required');
	$this->form_validation->set_rules('nama_barang_bahan', 'nama barang bahan', 'trim|required');
	$this->form_validation->set_rules('jumlah_bahan', 'jumlah bahan', 'trim|required|numeric');
	$this->form_validation->set_rules('satuan_bahan', 'satuan bahan', 'trim|required');
	$this->form_validation->set_rules('harga_satuan_bahan', 'harga satuan bahan', 'trim|required|numeric');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "sys_unit_produk_bahan.xls";
        $judul = "sys_unit_produk_bahan";
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
	xlsWriteLabel($tablehead, $kolomhead++, "Uuid Persediaan");
	xlsWriteLabel($tablehead, $kolomhead++, "Kode Unit");
	xlsWriteLabel($tablehead, $kolomhead++, "Nama Unit");
	xlsWriteLabel($tablehead, $kolomhead++, "Tgl Transaksi");
	xlsWriteLabel($tablehead, $kolomhead++, "Uuid Produk");
	xlsWriteLabel($tablehead, $kolomhead++, "Kode Barang Bahan");
	xlsWriteLabel($tablehead, $kolomhead++, "Nama Barang Bahan");
	xlsWriteLabel($tablehead, $kolomhead++, "Jumlah Bahan");
	xlsWriteLabel($tablehead, $kolomhead++, "Satuan Bahan");
	xlsWriteLabel($tablehead, $kolomhead++, "Harga Satuan Bahan");

	foreach ($this->Sys_unit_produk_bahan_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
	    xlsWriteLabel($tablebody, $kolombody++, $data->uuid_unit);
	    xlsWriteLabel($tablebody, $kolombody++, $data->uuid_persediaan);
	    xlsWriteLabel($tablebody, $kolombody++, $data->kode_unit);
	    xlsWriteLabel($tablebody, $kolombody++, $data->nama_unit);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tgl_transaksi);
	    xlsWriteLabel($tablebody, $kolombody++, $data->uuid_produk);
	    xlsWriteLabel($tablebody, $kolombody++, $data->kode_barang_bahan);
	    xlsWriteLabel($tablebody, $kolombody++, $data->nama_barang_bahan);
	    xlsWriteNumber($tablebody, $kolombody++, $data->jumlah_bahan);
	    xlsWriteLabel($tablebody, $kolombody++, $data->satuan_bahan);
	    xlsWriteNumber($tablebody, $kolombody++, $data->harga_satuan_bahan);

	    $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

}

/* End of file Sys_unit_produk_bahan.php */
/* Location: ./application/controllers/Sys_unit_produk_bahan.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2025-01-16 00:08:29 */
/* http://harviacode.com */