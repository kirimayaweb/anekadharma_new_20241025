<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_pembelian_pecah_satuan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Tbl_pembelian_pecah_satuan_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . 'tbl_pembelian_pecah_satuan/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'tbl_pembelian_pecah_satuan/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'tbl_pembelian_pecah_satuan/index.html';
            $config['first_url'] = base_url() . 'tbl_pembelian_pecah_satuan/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Tbl_pembelian_pecah_satuan_model->total_rows($q);
        $tbl_pembelian_pecah_satuan = $this->Tbl_pembelian_pecah_satuan_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'tbl_pembelian_pecah_satuan_data' => $tbl_pembelian_pecah_satuan,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->load->view('tbl_pembelian_pecah_satuan/tbl_pembelian_pecah_satuan_list', $data);
    }

    public function read($id) 
    {
        $row = $this->Tbl_pembelian_pecah_satuan_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'proses_input' => $row->proses_input,
		'date_input' => $row->date_input,
		'uuid_pembelian' => $row->uuid_pembelian,
		'uuid_barang' => $row->uuid_barang,
		'tgl_po' => $row->tgl_po,
		'nmrsj' => $row->nmrsj,
		'nmrfakturkwitansi' => $row->nmrfakturkwitansi,
		'nmrbpb' => $row->nmrbpb,
		'uuid_spop' => $row->uuid_spop,
		'spop' => $row->spop,
		'status_spop' => $row->status_spop,
		'uuid_supplier' => $row->uuid_supplier,
		'supplier_kode' => $row->supplier_kode,
		'supplier_nama' => $row->supplier_nama,
		'kode_barang' => $row->kode_barang,
		'uraian' => $row->uraian,
		'jumlah' => $row->jumlah,
		'satuan' => $row->satuan,
		'uuid_konsumen' => $row->uuid_konsumen,
		'konsumen' => $row->konsumen,
		'uuid_gudang' => $row->uuid_gudang,
		'nama_gudang' => $row->nama_gudang,
		'harga_satuan' => $row->harga_satuan,
		'harga_total' => $row->harga_total,
		'statuslu' => $row->statuslu,
		'kas_bank' => $row->kas_bank,
		'tgl_bayar' => $row->tgl_bayar,
		'id_usr' => $row->id_usr,
		'tgl_pengajuan_1' => $row->tgl_pengajuan_1,
		'nominal_pengajuan_1' => $row->nominal_pengajuan_1,
		'tgl_pengajuan_2' => $row->tgl_pengajuan_2,
		'nominal_pengajuan_2' => $row->nominal_pengajuan_2,
		'uuid_gudang_baru' => $row->uuid_gudang_baru,
		'kode_barang_baru' => $row->kode_barang_baru,
		'nama_barang_baru' => $row->nama_barang_baru,
		'nominal_bayar_input' => $row->nominal_bayar_input,
		'satuan_barang_baru' => $row->satuan_barang_baru,
		'harga_satuan_barang_baru' => $row->harga_satuan_barang_baru,
	    );
            $this->load->view('tbl_pembelian_pecah_satuan/tbl_pembelian_pecah_satuan_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_pembelian_pecah_satuan'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('tbl_pembelian_pecah_satuan/create_action'),
	    'id' => set_value('id'),
	    'proses_input' => set_value('proses_input'),
	    'date_input' => set_value('date_input'),
	    'uuid_pembelian' => set_value('uuid_pembelian'),
	    'uuid_barang' => set_value('uuid_barang'),
	    'tgl_po' => set_value('tgl_po'),
	    'nmrsj' => set_value('nmrsj'),
	    'nmrfakturkwitansi' => set_value('nmrfakturkwitansi'),
	    'nmrbpb' => set_value('nmrbpb'),
	    'uuid_spop' => set_value('uuid_spop'),
	    'spop' => set_value('spop'),
	    'status_spop' => set_value('status_spop'),
	    'uuid_supplier' => set_value('uuid_supplier'),
	    'supplier_kode' => set_value('supplier_kode'),
	    'supplier_nama' => set_value('supplier_nama'),
	    'kode_barang' => set_value('kode_barang'),
	    'uraian' => set_value('uraian'),
	    'jumlah' => set_value('jumlah'),
	    'satuan' => set_value('satuan'),
	    'uuid_konsumen' => set_value('uuid_konsumen'),
	    'konsumen' => set_value('konsumen'),
	    'uuid_gudang' => set_value('uuid_gudang'),
	    'nama_gudang' => set_value('nama_gudang'),
	    'harga_satuan' => set_value('harga_satuan'),
	    'harga_total' => set_value('harga_total'),
	    'statuslu' => set_value('statuslu'),
	    'kas_bank' => set_value('kas_bank'),
	    'tgl_bayar' => set_value('tgl_bayar'),
	    'id_usr' => set_value('id_usr'),
	    'tgl_pengajuan_1' => set_value('tgl_pengajuan_1'),
	    'nominal_pengajuan_1' => set_value('nominal_pengajuan_1'),
	    'tgl_pengajuan_2' => set_value('tgl_pengajuan_2'),
	    'nominal_pengajuan_2' => set_value('nominal_pengajuan_2'),
	    'uuid_gudang_baru' => set_value('uuid_gudang_baru'),
	    'kode_barang_baru' => set_value('kode_barang_baru'),
	    'nama_barang_baru' => set_value('nama_barang_baru'),
	    'nominal_bayar_input' => set_value('nominal_bayar_input'),
	    'satuan_barang_baru' => set_value('satuan_barang_baru'),
	    'harga_satuan_barang_baru' => set_value('harga_satuan_barang_baru'),
	);
        $this->load->view('tbl_pembelian_pecah_satuan/tbl_pembelian_pecah_satuan_form', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'proses_input' => $this->input->post('proses_input',TRUE),
		'date_input' => $this->input->post('date_input',TRUE),
		'uuid_pembelian' => $this->input->post('uuid_pembelian',TRUE),
		'uuid_barang' => $this->input->post('uuid_barang',TRUE),
		'tgl_po' => $this->input->post('tgl_po',TRUE),
		'nmrsj' => $this->input->post('nmrsj',TRUE),
		'nmrfakturkwitansi' => $this->input->post('nmrfakturkwitansi',TRUE),
		'nmrbpb' => $this->input->post('nmrbpb',TRUE),
		'uuid_spop' => $this->input->post('uuid_spop',TRUE),
		'spop' => $this->input->post('spop',TRUE),
		'status_spop' => $this->input->post('status_spop',TRUE),
		'uuid_supplier' => $this->input->post('uuid_supplier',TRUE),
		'supplier_kode' => $this->input->post('supplier_kode',TRUE),
		'supplier_nama' => $this->input->post('supplier_nama',TRUE),
		'kode_barang' => $this->input->post('kode_barang',TRUE),
		'uraian' => $this->input->post('uraian',TRUE),
		'jumlah' => $this->input->post('jumlah',TRUE),
		'satuan' => $this->input->post('satuan',TRUE),
		'uuid_konsumen' => $this->input->post('uuid_konsumen',TRUE),
		'konsumen' => $this->input->post('konsumen',TRUE),
		'uuid_gudang' => $this->input->post('uuid_gudang',TRUE),
		'nama_gudang' => $this->input->post('nama_gudang',TRUE),
		'harga_satuan' => $this->input->post('harga_satuan',TRUE),
		'harga_total' => $this->input->post('harga_total',TRUE),
		'statuslu' => $this->input->post('statuslu',TRUE),
		'kas_bank' => $this->input->post('kas_bank',TRUE),
		'tgl_bayar' => $this->input->post('tgl_bayar',TRUE),
		'id_usr' => $this->input->post('id_usr',TRUE),
		'tgl_pengajuan_1' => $this->input->post('tgl_pengajuan_1',TRUE),
		'nominal_pengajuan_1' => $this->input->post('nominal_pengajuan_1',TRUE),
		'tgl_pengajuan_2' => $this->input->post('tgl_pengajuan_2',TRUE),
		'nominal_pengajuan_2' => $this->input->post('nominal_pengajuan_2',TRUE),
		'uuid_gudang_baru' => $this->input->post('uuid_gudang_baru',TRUE),
		'kode_barang_baru' => $this->input->post('kode_barang_baru',TRUE),
		'nama_barang_baru' => $this->input->post('nama_barang_baru',TRUE),
		'nominal_bayar_input' => $this->input->post('nominal_bayar_input',TRUE),
		'satuan_barang_baru' => $this->input->post('satuan_barang_baru',TRUE),
		'harga_satuan_barang_baru' => $this->input->post('harga_satuan_barang_baru',TRUE),
	    );

            $this->Tbl_pembelian_pecah_satuan_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('tbl_pembelian_pecah_satuan'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Tbl_pembelian_pecah_satuan_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('tbl_pembelian_pecah_satuan/update_action'),
		'id' => set_value('id', $row->id),
		'proses_input' => set_value('proses_input', $row->proses_input),
		'date_input' => set_value('date_input', $row->date_input),
		'uuid_pembelian' => set_value('uuid_pembelian', $row->uuid_pembelian),
		'uuid_barang' => set_value('uuid_barang', $row->uuid_barang),
		'tgl_po' => set_value('tgl_po', $row->tgl_po),
		'nmrsj' => set_value('nmrsj', $row->nmrsj),
		'nmrfakturkwitansi' => set_value('nmrfakturkwitansi', $row->nmrfakturkwitansi),
		'nmrbpb' => set_value('nmrbpb', $row->nmrbpb),
		'uuid_spop' => set_value('uuid_spop', $row->uuid_spop),
		'spop' => set_value('spop', $row->spop),
		'status_spop' => set_value('status_spop', $row->status_spop),
		'uuid_supplier' => set_value('uuid_supplier', $row->uuid_supplier),
		'supplier_kode' => set_value('supplier_kode', $row->supplier_kode),
		'supplier_nama' => set_value('supplier_nama', $row->supplier_nama),
		'kode_barang' => set_value('kode_barang', $row->kode_barang),
		'uraian' => set_value('uraian', $row->uraian),
		'jumlah' => set_value('jumlah', $row->jumlah),
		'satuan' => set_value('satuan', $row->satuan),
		'uuid_konsumen' => set_value('uuid_konsumen', $row->uuid_konsumen),
		'konsumen' => set_value('konsumen', $row->konsumen),
		'uuid_gudang' => set_value('uuid_gudang', $row->uuid_gudang),
		'nama_gudang' => set_value('nama_gudang', $row->nama_gudang),
		'harga_satuan' => set_value('harga_satuan', $row->harga_satuan),
		'harga_total' => set_value('harga_total', $row->harga_total),
		'statuslu' => set_value('statuslu', $row->statuslu),
		'kas_bank' => set_value('kas_bank', $row->kas_bank),
		'tgl_bayar' => set_value('tgl_bayar', $row->tgl_bayar),
		'id_usr' => set_value('id_usr', $row->id_usr),
		'tgl_pengajuan_1' => set_value('tgl_pengajuan_1', $row->tgl_pengajuan_1),
		'nominal_pengajuan_1' => set_value('nominal_pengajuan_1', $row->nominal_pengajuan_1),
		'tgl_pengajuan_2' => set_value('tgl_pengajuan_2', $row->tgl_pengajuan_2),
		'nominal_pengajuan_2' => set_value('nominal_pengajuan_2', $row->nominal_pengajuan_2),
		'uuid_gudang_baru' => set_value('uuid_gudang_baru', $row->uuid_gudang_baru),
		'kode_barang_baru' => set_value('kode_barang_baru', $row->kode_barang_baru),
		'nama_barang_baru' => set_value('nama_barang_baru', $row->nama_barang_baru),
		'nominal_bayar_input' => set_value('nominal_bayar_input', $row->nominal_bayar_input),
		'satuan_barang_baru' => set_value('satuan_barang_baru', $row->satuan_barang_baru),
		'harga_satuan_barang_baru' => set_value('harga_satuan_barang_baru', $row->harga_satuan_barang_baru),
	    );
            $this->load->view('tbl_pembelian_pecah_satuan/tbl_pembelian_pecah_satuan_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_pembelian_pecah_satuan'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
		'proses_input' => $this->input->post('proses_input',TRUE),
		'date_input' => $this->input->post('date_input',TRUE),
		'uuid_pembelian' => $this->input->post('uuid_pembelian',TRUE),
		'uuid_barang' => $this->input->post('uuid_barang',TRUE),
		'tgl_po' => $this->input->post('tgl_po',TRUE),
		'nmrsj' => $this->input->post('nmrsj',TRUE),
		'nmrfakturkwitansi' => $this->input->post('nmrfakturkwitansi',TRUE),
		'nmrbpb' => $this->input->post('nmrbpb',TRUE),
		'uuid_spop' => $this->input->post('uuid_spop',TRUE),
		'spop' => $this->input->post('spop',TRUE),
		'status_spop' => $this->input->post('status_spop',TRUE),
		'uuid_supplier' => $this->input->post('uuid_supplier',TRUE),
		'supplier_kode' => $this->input->post('supplier_kode',TRUE),
		'supplier_nama' => $this->input->post('supplier_nama',TRUE),
		'kode_barang' => $this->input->post('kode_barang',TRUE),
		'uraian' => $this->input->post('uraian',TRUE),
		'jumlah' => $this->input->post('jumlah',TRUE),
		'satuan' => $this->input->post('satuan',TRUE),
		'uuid_konsumen' => $this->input->post('uuid_konsumen',TRUE),
		'konsumen' => $this->input->post('konsumen',TRUE),
		'uuid_gudang' => $this->input->post('uuid_gudang',TRUE),
		'nama_gudang' => $this->input->post('nama_gudang',TRUE),
		'harga_satuan' => $this->input->post('harga_satuan',TRUE),
		'harga_total' => $this->input->post('harga_total',TRUE),
		'statuslu' => $this->input->post('statuslu',TRUE),
		'kas_bank' => $this->input->post('kas_bank',TRUE),
		'tgl_bayar' => $this->input->post('tgl_bayar',TRUE),
		'id_usr' => $this->input->post('id_usr',TRUE),
		'tgl_pengajuan_1' => $this->input->post('tgl_pengajuan_1',TRUE),
		'nominal_pengajuan_1' => $this->input->post('nominal_pengajuan_1',TRUE),
		'tgl_pengajuan_2' => $this->input->post('tgl_pengajuan_2',TRUE),
		'nominal_pengajuan_2' => $this->input->post('nominal_pengajuan_2',TRUE),
		'uuid_gudang_baru' => $this->input->post('uuid_gudang_baru',TRUE),
		'kode_barang_baru' => $this->input->post('kode_barang_baru',TRUE),
		'nama_barang_baru' => $this->input->post('nama_barang_baru',TRUE),
		'nominal_bayar_input' => $this->input->post('nominal_bayar_input',TRUE),
		'satuan_barang_baru' => $this->input->post('satuan_barang_baru',TRUE),
		'harga_satuan_barang_baru' => $this->input->post('harga_satuan_barang_baru',TRUE),
	    );

            $this->Tbl_pembelian_pecah_satuan_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('tbl_pembelian_pecah_satuan'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Tbl_pembelian_pecah_satuan_model->get_by_id($id);

        if ($row) {
            $this->Tbl_pembelian_pecah_satuan_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('tbl_pembelian_pecah_satuan'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_pembelian_pecah_satuan'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('proses_input', 'proses input', 'trim|required');
	$this->form_validation->set_rules('date_input', 'date input', 'trim|required');
	$this->form_validation->set_rules('uuid_pembelian', 'uuid pembelian', 'trim|required');
	$this->form_validation->set_rules('uuid_barang', 'uuid barang', 'trim|required');
	$this->form_validation->set_rules('tgl_po', 'tgl po', 'trim|required');
	$this->form_validation->set_rules('nmrsj', 'nmrsj', 'trim|required');
	$this->form_validation->set_rules('nmrfakturkwitansi', 'nmrfakturkwitansi', 'trim|required');
	$this->form_validation->set_rules('nmrbpb', 'nmrbpb', 'trim|required');
	$this->form_validation->set_rules('uuid_spop', 'uuid spop', 'trim|required');
	$this->form_validation->set_rules('spop', 'spop', 'trim|required');
	$this->form_validation->set_rules('status_spop', 'status spop', 'trim|required');
	$this->form_validation->set_rules('uuid_supplier', 'uuid supplier', 'trim|required');
	$this->form_validation->set_rules('supplier_kode', 'supplier kode', 'trim|required');
	$this->form_validation->set_rules('supplier_nama', 'supplier nama', 'trim|required');
	$this->form_validation->set_rules('kode_barang', 'kode barang', 'trim|required');
	$this->form_validation->set_rules('uraian', 'uraian', 'trim|required');
	$this->form_validation->set_rules('jumlah', 'jumlah', 'trim|required');
	$this->form_validation->set_rules('satuan', 'satuan', 'trim|required');
	$this->form_validation->set_rules('uuid_konsumen', 'uuid konsumen', 'trim|required');
	$this->form_validation->set_rules('konsumen', 'konsumen', 'trim|required');
	$this->form_validation->set_rules('uuid_gudang', 'uuid gudang', 'trim|required');
	$this->form_validation->set_rules('nama_gudang', 'nama gudang', 'trim|required');
	$this->form_validation->set_rules('harga_satuan', 'harga satuan', 'trim|required|numeric');
	$this->form_validation->set_rules('harga_total', 'harga total', 'trim|required|numeric');
	$this->form_validation->set_rules('statuslu', 'statuslu', 'trim|required');
	$this->form_validation->set_rules('kas_bank', 'kas bank', 'trim|required');
	$this->form_validation->set_rules('tgl_bayar', 'tgl bayar', 'trim|required');
	$this->form_validation->set_rules('id_usr', 'id usr', 'trim|required');
	$this->form_validation->set_rules('tgl_pengajuan_1', 'tgl pengajuan 1', 'trim|required');
	$this->form_validation->set_rules('nominal_pengajuan_1', 'nominal pengajuan 1', 'trim|required|numeric');
	$this->form_validation->set_rules('tgl_pengajuan_2', 'tgl pengajuan 2', 'trim|required');
	$this->form_validation->set_rules('nominal_pengajuan_2', 'nominal pengajuan 2', 'trim|required|numeric');
	$this->form_validation->set_rules('uuid_gudang_baru', 'uuid gudang baru', 'trim|required');
	$this->form_validation->set_rules('kode_barang_baru', 'kode barang baru', 'trim|required');
	$this->form_validation->set_rules('nama_barang_baru', 'nama barang baru', 'trim|required');
	$this->form_validation->set_rules('nominal_bayar_input', 'nominal bayar input', 'trim|required|numeric');
	$this->form_validation->set_rules('satuan_barang_baru', 'satuan barang baru', 'trim|required');
	$this->form_validation->set_rules('harga_satuan_barang_baru', 'harga satuan barang baru', 'trim|required|numeric');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "tbl_pembelian_pecah_satuan.xls";
        $judul = "tbl_pembelian_pecah_satuan";
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
	xlsWriteLabel($tablehead, $kolomhead++, "Proses Input");
	xlsWriteLabel($tablehead, $kolomhead++, "Date Input");
	xlsWriteLabel($tablehead, $kolomhead++, "Uuid Pembelian");
	xlsWriteLabel($tablehead, $kolomhead++, "Uuid Barang");
	xlsWriteLabel($tablehead, $kolomhead++, "Tgl Po");
	xlsWriteLabel($tablehead, $kolomhead++, "Nmrsj");
	xlsWriteLabel($tablehead, $kolomhead++, "Nmrfakturkwitansi");
	xlsWriteLabel($tablehead, $kolomhead++, "Nmrbpb");
	xlsWriteLabel($tablehead, $kolomhead++, "Uuid Spop");
	xlsWriteLabel($tablehead, $kolomhead++, "Spop");
	xlsWriteLabel($tablehead, $kolomhead++, "Status Spop");
	xlsWriteLabel($tablehead, $kolomhead++, "Uuid Supplier");
	xlsWriteLabel($tablehead, $kolomhead++, "Supplier Kode");
	xlsWriteLabel($tablehead, $kolomhead++, "Supplier Nama");
	xlsWriteLabel($tablehead, $kolomhead++, "Kode Barang");
	xlsWriteLabel($tablehead, $kolomhead++, "Uraian");
	xlsWriteLabel($tablehead, $kolomhead++, "Jumlah");
	xlsWriteLabel($tablehead, $kolomhead++, "Satuan");
	xlsWriteLabel($tablehead, $kolomhead++, "Uuid Konsumen");
	xlsWriteLabel($tablehead, $kolomhead++, "Konsumen");
	xlsWriteLabel($tablehead, $kolomhead++, "Uuid Gudang");
	xlsWriteLabel($tablehead, $kolomhead++, "Nama Gudang");
	xlsWriteLabel($tablehead, $kolomhead++, "Harga Satuan");
	xlsWriteLabel($tablehead, $kolomhead++, "Harga Total");
	xlsWriteLabel($tablehead, $kolomhead++, "Statuslu");
	xlsWriteLabel($tablehead, $kolomhead++, "Kas Bank");
	xlsWriteLabel($tablehead, $kolomhead++, "Tgl Bayar");
	xlsWriteLabel($tablehead, $kolomhead++, "Id Usr");
	xlsWriteLabel($tablehead, $kolomhead++, "Tgl Pengajuan 1");
	xlsWriteLabel($tablehead, $kolomhead++, "Nominal Pengajuan 1");
	xlsWriteLabel($tablehead, $kolomhead++, "Tgl Pengajuan 2");
	xlsWriteLabel($tablehead, $kolomhead++, "Nominal Pengajuan 2");
	xlsWriteLabel($tablehead, $kolomhead++, "Uuid Gudang Baru");
	xlsWriteLabel($tablehead, $kolomhead++, "Kode Barang Baru");
	xlsWriteLabel($tablehead, $kolomhead++, "Nama Barang Baru");
	xlsWriteLabel($tablehead, $kolomhead++, "Nominal Bayar Input");
	xlsWriteLabel($tablehead, $kolomhead++, "Satuan Barang Baru");
	xlsWriteLabel($tablehead, $kolomhead++, "Harga Satuan Barang Baru");

	foreach ($this->Tbl_pembelian_pecah_satuan_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
	    xlsWriteNumber($tablebody, $kolombody++, $data->proses_input);
	    xlsWriteLabel($tablebody, $kolombody++, $data->date_input);
	    xlsWriteLabel($tablebody, $kolombody++, $data->uuid_pembelian);
	    xlsWriteLabel($tablebody, $kolombody++, $data->uuid_barang);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tgl_po);
	    xlsWriteLabel($tablebody, $kolombody++, $data->nmrsj);
	    xlsWriteLabel($tablebody, $kolombody++, $data->nmrfakturkwitansi);
	    xlsWriteLabel($tablebody, $kolombody++, $data->nmrbpb);
	    xlsWriteLabel($tablebody, $kolombody++, $data->uuid_spop);
	    xlsWriteLabel($tablebody, $kolombody++, $data->spop);
	    xlsWriteLabel($tablebody, $kolombody++, $data->status_spop);
	    xlsWriteLabel($tablebody, $kolombody++, $data->uuid_supplier);
	    xlsWriteLabel($tablebody, $kolombody++, $data->supplier_kode);
	    xlsWriteLabel($tablebody, $kolombody++, $data->supplier_nama);
	    xlsWriteLabel($tablebody, $kolombody++, $data->kode_barang);
	    xlsWriteLabel($tablebody, $kolombody++, $data->uraian);
	    xlsWriteNumber($tablebody, $kolombody++, $data->jumlah);
	    xlsWriteLabel($tablebody, $kolombody++, $data->satuan);
	    xlsWriteLabel($tablebody, $kolombody++, $data->uuid_konsumen);
	    xlsWriteLabel($tablebody, $kolombody++, $data->konsumen);
	    xlsWriteLabel($tablebody, $kolombody++, $data->uuid_gudang);
	    xlsWriteLabel($tablebody, $kolombody++, $data->nama_gudang);
	    xlsWriteNumber($tablebody, $kolombody++, $data->harga_satuan);
	    xlsWriteNumber($tablebody, $kolombody++, $data->harga_total);
	    xlsWriteLabel($tablebody, $kolombody++, $data->statuslu);
	    xlsWriteLabel($tablebody, $kolombody++, $data->kas_bank);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tgl_bayar);
	    xlsWriteNumber($tablebody, $kolombody++, $data->id_usr);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tgl_pengajuan_1);
	    xlsWriteNumber($tablebody, $kolombody++, $data->nominal_pengajuan_1);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tgl_pengajuan_2);
	    xlsWriteNumber($tablebody, $kolombody++, $data->nominal_pengajuan_2);
	    xlsWriteLabel($tablebody, $kolombody++, $data->uuid_gudang_baru);
	    xlsWriteLabel($tablebody, $kolombody++, $data->kode_barang_baru);
	    xlsWriteLabel($tablebody, $kolombody++, $data->nama_barang_baru);
	    xlsWriteNumber($tablebody, $kolombody++, $data->nominal_bayar_input);
	    xlsWriteLabel($tablebody, $kolombody++, $data->satuan_barang_baru);
	    xlsWriteNumber($tablebody, $kolombody++, $data->harga_satuan_barang_baru);

	    $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

}

/* End of file Tbl_pembelian_pecah_satuan.php */
/* Location: ./application/controllers/Tbl_pembelian_pecah_satuan.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-10-20 11:34:58 */
/* http://harviacode.com */