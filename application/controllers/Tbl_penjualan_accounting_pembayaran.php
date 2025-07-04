<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_penjualan_accounting_pembayaran extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Tbl_penjualan_accounting_pembayaran_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . 'tbl_penjualan_accounting_pembayaran/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'tbl_penjualan_accounting_pembayaran/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'tbl_penjualan_accounting_pembayaran/index.html';
            $config['first_url'] = base_url() . 'tbl_penjualan_accounting_pembayaran/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Tbl_penjualan_accounting_pembayaran_model->total_rows($q);
        $tbl_penjualan_accounting_pembayaran = $this->Tbl_penjualan_accounting_pembayaran_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'tbl_penjualan_accounting_pembayaran_data' => $tbl_penjualan_accounting_pembayaran,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->load->view('tbl_penjualan_accounting_pembayaran/tbl_penjualan_accounting_pembayaran_list', $data);
    }

    public function read($id) 
    {
        $row = $this->Tbl_penjualan_accounting_pembayaran_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'uuid_pembayaran' => $row->uuid_pembayaran,
		'tgl_bayar' => $row->tgl_bayar,
		'nominal_bayar' => $row->nominal_bayar,
		'nmr_bukti_bayar' => $row->nmr_bukti_bayar,
		'uuid_penjualan' => $row->uuid_penjualan,
		'uuid_barang' => $row->uuid_barang,
		'tgl_input' => $row->tgl_input,
		'tgl_jual' => $row->tgl_jual,
		'nmrpesan' => $row->nmrpesan,
		'nmrkirim' => $row->nmrkirim,
		'uuid_konsumen' => $row->uuid_konsumen,
		'konsumen_id' => $row->konsumen_id,
		'konsumen_nama' => $row->konsumen_nama,
		'kode_barang' => $row->kode_barang,
		'nama_barang' => $row->nama_barang,
		'uuid_unit' => $row->uuid_unit,
		'unit' => $row->unit,
		'satuan' => $row->satuan,
		'harga_satuan' => $row->harga_satuan,
		'jumlah' => $row->jumlah,
		'total_nominal' => $row->total_nominal,
		'umpphpsl22' => $row->umpphpsl22,
		'piutang' => $row->piutang,
		'penjualandpp' => $row->penjualandpp,
		'utangppn' => $row->utangppn,
		'uuid_kode_akun' => $row->uuid_kode_akun,
		'kode_akun' => $row->kode_akun,
		'nama_akun' => $row->nama_akun,
		'id_usr' => $row->id_usr,
	    );
            $this->load->view('tbl_penjualan_accounting_pembayaran/tbl_penjualan_accounting_pembayaran_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_penjualan_accounting_pembayaran'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('tbl_penjualan_accounting_pembayaran/create_action'),
	    'id' => set_value('id'),
	    'uuid_pembayaran' => set_value('uuid_pembayaran'),
	    'tgl_bayar' => set_value('tgl_bayar'),
	    'nominal_bayar' => set_value('nominal_bayar'),
	    'nmr_bukti_bayar' => set_value('nmr_bukti_bayar'),
	    'uuid_penjualan' => set_value('uuid_penjualan'),
	    'uuid_barang' => set_value('uuid_barang'),
	    'tgl_input' => set_value('tgl_input'),
	    'tgl_jual' => set_value('tgl_jual'),
	    'nmrpesan' => set_value('nmrpesan'),
	    'nmrkirim' => set_value('nmrkirim'),
	    'uuid_konsumen' => set_value('uuid_konsumen'),
	    'konsumen_id' => set_value('konsumen_id'),
	    'konsumen_nama' => set_value('konsumen_nama'),
	    'kode_barang' => set_value('kode_barang'),
	    'nama_barang' => set_value('nama_barang'),
	    'uuid_unit' => set_value('uuid_unit'),
	    'unit' => set_value('unit'),
	    'satuan' => set_value('satuan'),
	    'harga_satuan' => set_value('harga_satuan'),
	    'jumlah' => set_value('jumlah'),
	    'total_nominal' => set_value('total_nominal'),
	    'umpphpsl22' => set_value('umpphpsl22'),
	    'piutang' => set_value('piutang'),
	    'penjualandpp' => set_value('penjualandpp'),
	    'utangppn' => set_value('utangppn'),
	    'uuid_kode_akun' => set_value('uuid_kode_akun'),
	    'kode_akun' => set_value('kode_akun'),
	    'nama_akun' => set_value('nama_akun'),
	    'id_usr' => set_value('id_usr'),
	);
        $this->load->view('tbl_penjualan_accounting_pembayaran/tbl_penjualan_accounting_pembayaran_form', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'uuid_pembayaran' => $this->input->post('uuid_pembayaran',TRUE),
		'tgl_bayar' => $this->input->post('tgl_bayar',TRUE),
		'nominal_bayar' => $this->input->post('nominal_bayar',TRUE),
		'nmr_bukti_bayar' => $this->input->post('nmr_bukti_bayar',TRUE),
		'uuid_penjualan' => $this->input->post('uuid_penjualan',TRUE),
		'uuid_barang' => $this->input->post('uuid_barang',TRUE),
		'tgl_input' => $this->input->post('tgl_input',TRUE),
		'tgl_jual' => $this->input->post('tgl_jual',TRUE),
		'nmrpesan' => $this->input->post('nmrpesan',TRUE),
		'nmrkirim' => $this->input->post('nmrkirim',TRUE),
		'uuid_konsumen' => $this->input->post('uuid_konsumen',TRUE),
		'konsumen_id' => $this->input->post('konsumen_id',TRUE),
		'konsumen_nama' => $this->input->post('konsumen_nama',TRUE),
		'kode_barang' => $this->input->post('kode_barang',TRUE),
		'nama_barang' => $this->input->post('nama_barang',TRUE),
		'uuid_unit' => $this->input->post('uuid_unit',TRUE),
		'unit' => $this->input->post('unit',TRUE),
		'satuan' => $this->input->post('satuan',TRUE),
		'harga_satuan' => $this->input->post('harga_satuan',TRUE),
		'jumlah' => $this->input->post('jumlah',TRUE),
		'total_nominal' => $this->input->post('total_nominal',TRUE),
		'umpphpsl22' => $this->input->post('umpphpsl22',TRUE),
		'piutang' => $this->input->post('piutang',TRUE),
		'penjualandpp' => $this->input->post('penjualandpp',TRUE),
		'utangppn' => $this->input->post('utangppn',TRUE),
		'uuid_kode_akun' => $this->input->post('uuid_kode_akun',TRUE),
		'kode_akun' => $this->input->post('kode_akun',TRUE),
		'nama_akun' => $this->input->post('nama_akun',TRUE),
		'id_usr' => $this->input->post('id_usr',TRUE),
	    );

            $this->Tbl_penjualan_accounting_pembayaran_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('tbl_penjualan_accounting_pembayaran'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Tbl_penjualan_accounting_pembayaran_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('tbl_penjualan_accounting_pembayaran/update_action'),
		'id' => set_value('id', $row->id),
		'uuid_pembayaran' => set_value('uuid_pembayaran', $row->uuid_pembayaran),
		'tgl_bayar' => set_value('tgl_bayar', $row->tgl_bayar),
		'nominal_bayar' => set_value('nominal_bayar', $row->nominal_bayar),
		'nmr_bukti_bayar' => set_value('nmr_bukti_bayar', $row->nmr_bukti_bayar),
		'uuid_penjualan' => set_value('uuid_penjualan', $row->uuid_penjualan),
		'uuid_barang' => set_value('uuid_barang', $row->uuid_barang),
		'tgl_input' => set_value('tgl_input', $row->tgl_input),
		'tgl_jual' => set_value('tgl_jual', $row->tgl_jual),
		'nmrpesan' => set_value('nmrpesan', $row->nmrpesan),
		'nmrkirim' => set_value('nmrkirim', $row->nmrkirim),
		'uuid_konsumen' => set_value('uuid_konsumen', $row->uuid_konsumen),
		'konsumen_id' => set_value('konsumen_id', $row->konsumen_id),
		'konsumen_nama' => set_value('konsumen_nama', $row->konsumen_nama),
		'kode_barang' => set_value('kode_barang', $row->kode_barang),
		'nama_barang' => set_value('nama_barang', $row->nama_barang),
		'uuid_unit' => set_value('uuid_unit', $row->uuid_unit),
		'unit' => set_value('unit', $row->unit),
		'satuan' => set_value('satuan', $row->satuan),
		'harga_satuan' => set_value('harga_satuan', $row->harga_satuan),
		'jumlah' => set_value('jumlah', $row->jumlah),
		'total_nominal' => set_value('total_nominal', $row->total_nominal),
		'umpphpsl22' => set_value('umpphpsl22', $row->umpphpsl22),
		'piutang' => set_value('piutang', $row->piutang),
		'penjualandpp' => set_value('penjualandpp', $row->penjualandpp),
		'utangppn' => set_value('utangppn', $row->utangppn),
		'uuid_kode_akun' => set_value('uuid_kode_akun', $row->uuid_kode_akun),
		'kode_akun' => set_value('kode_akun', $row->kode_akun),
		'nama_akun' => set_value('nama_akun', $row->nama_akun),
		'id_usr' => set_value('id_usr', $row->id_usr),
	    );
            $this->load->view('tbl_penjualan_accounting_pembayaran/tbl_penjualan_accounting_pembayaran_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_penjualan_accounting_pembayaran'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
		'uuid_pembayaran' => $this->input->post('uuid_pembayaran',TRUE),
		'tgl_bayar' => $this->input->post('tgl_bayar',TRUE),
		'nominal_bayar' => $this->input->post('nominal_bayar',TRUE),
		'nmr_bukti_bayar' => $this->input->post('nmr_bukti_bayar',TRUE),
		'uuid_penjualan' => $this->input->post('uuid_penjualan',TRUE),
		'uuid_barang' => $this->input->post('uuid_barang',TRUE),
		'tgl_input' => $this->input->post('tgl_input',TRUE),
		'tgl_jual' => $this->input->post('tgl_jual',TRUE),
		'nmrpesan' => $this->input->post('nmrpesan',TRUE),
		'nmrkirim' => $this->input->post('nmrkirim',TRUE),
		'uuid_konsumen' => $this->input->post('uuid_konsumen',TRUE),
		'konsumen_id' => $this->input->post('konsumen_id',TRUE),
		'konsumen_nama' => $this->input->post('konsumen_nama',TRUE),
		'kode_barang' => $this->input->post('kode_barang',TRUE),
		'nama_barang' => $this->input->post('nama_barang',TRUE),
		'uuid_unit' => $this->input->post('uuid_unit',TRUE),
		'unit' => $this->input->post('unit',TRUE),
		'satuan' => $this->input->post('satuan',TRUE),
		'harga_satuan' => $this->input->post('harga_satuan',TRUE),
		'jumlah' => $this->input->post('jumlah',TRUE),
		'total_nominal' => $this->input->post('total_nominal',TRUE),
		'umpphpsl22' => $this->input->post('umpphpsl22',TRUE),
		'piutang' => $this->input->post('piutang',TRUE),
		'penjualandpp' => $this->input->post('penjualandpp',TRUE),
		'utangppn' => $this->input->post('utangppn',TRUE),
		'uuid_kode_akun' => $this->input->post('uuid_kode_akun',TRUE),
		'kode_akun' => $this->input->post('kode_akun',TRUE),
		'nama_akun' => $this->input->post('nama_akun',TRUE),
		'id_usr' => $this->input->post('id_usr',TRUE),
	    );

            $this->Tbl_penjualan_accounting_pembayaran_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('tbl_penjualan_accounting_pembayaran'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Tbl_penjualan_accounting_pembayaran_model->get_by_id($id);

        if ($row) {
            $this->Tbl_penjualan_accounting_pembayaran_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('tbl_penjualan_accounting_pembayaran'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_penjualan_accounting_pembayaran'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('uuid_pembayaran', 'uuid pembayaran', 'trim|required');
	$this->form_validation->set_rules('tgl_bayar', 'tgl bayar', 'trim|required');
	$this->form_validation->set_rules('nominal_bayar', 'nominal bayar', 'trim|required|numeric');
	$this->form_validation->set_rules('nmr_bukti_bayar', 'nmr bukti bayar', 'trim|required');
	$this->form_validation->set_rules('uuid_penjualan', 'uuid penjualan', 'trim|required');
	$this->form_validation->set_rules('uuid_barang', 'uuid barang', 'trim|required');
	$this->form_validation->set_rules('tgl_input', 'tgl input', 'trim|required');
	$this->form_validation->set_rules('tgl_jual', 'tgl jual', 'trim|required');
	$this->form_validation->set_rules('nmrpesan', 'nmrpesan', 'trim|required');
	$this->form_validation->set_rules('nmrkirim', 'nmrkirim', 'trim|required');
	$this->form_validation->set_rules('uuid_konsumen', 'uuid konsumen', 'trim|required');
	$this->form_validation->set_rules('konsumen_id', 'konsumen id', 'trim|required');
	$this->form_validation->set_rules('konsumen_nama', 'konsumen nama', 'trim|required');
	$this->form_validation->set_rules('kode_barang', 'kode barang', 'trim|required');
	$this->form_validation->set_rules('nama_barang', 'nama barang', 'trim|required');
	$this->form_validation->set_rules('uuid_unit', 'uuid unit', 'trim|required');
	$this->form_validation->set_rules('unit', 'unit', 'trim|required');
	$this->form_validation->set_rules('satuan', 'satuan', 'trim|required');
	$this->form_validation->set_rules('harga_satuan', 'harga satuan', 'trim|required|numeric');
	$this->form_validation->set_rules('jumlah', 'jumlah', 'trim|required|numeric');
	$this->form_validation->set_rules('total_nominal', 'total nominal', 'trim|required|numeric');
	$this->form_validation->set_rules('umpphpsl22', 'umpphpsl22', 'trim|required|numeric');
	$this->form_validation->set_rules('piutang', 'piutang', 'trim|required|numeric');
	$this->form_validation->set_rules('penjualandpp', 'penjualandpp', 'trim|required|numeric');
	$this->form_validation->set_rules('utangppn', 'utangppn', 'trim|required|numeric');
	$this->form_validation->set_rules('uuid_kode_akun', 'uuid kode akun', 'trim|required');
	$this->form_validation->set_rules('kode_akun', 'kode akun', 'trim|required');
	$this->form_validation->set_rules('nama_akun', 'nama akun', 'trim|required');
	$this->form_validation->set_rules('id_usr', 'id usr', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Tbl_penjualan_accounting_pembayaran.php */
/* Location: ./application/controllers/Tbl_penjualan_accounting_pembayaran.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2025-06-22 12:39:28 */
/* http://harviacode.com */