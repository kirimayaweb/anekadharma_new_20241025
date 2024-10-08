<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Tbl_pembelian extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model(array('Tbl_pembelian_model', 'Tbl_penjualan_model', 'Tbl_pembelian_pengajuan_bayar_model', 'User_model', 'Sys_bank_model'));
		$this->load->library('form_validation');
		$this->load->library('datatables');
		$this->load->library('Pdf');
		$this->load->helper(array('nominal'));
	}

	public function index_BU()
	{
		$this->load->view('anekadharma/tbl_pembelian/tbl_pembelian_list');
	}

	public function json()
	{
		header('Content-Type: application/json');
		echo $this->Tbl_pembelian_model->json();
	}

	public function index()
	{
		$Tbl_pembelian = $this->Tbl_pembelian_model->get_all();
		$start = 0;
		$data = array(
			'Tbl_pembelian_data' => $Tbl_pembelian,
			'start' => $start,
		);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_list', $data);
	}

	public function stock()
	{
		$Data_stock = $this->Tbl_pembelian_model->stock();
		$data = array(
			'Data_stock' => $Data_stock,
		);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/stock/adminlte310_stock_barang', $data);
	}

	public function create_pembayaran($uuid_spop = null)
	{


		$row_per_uuid_spop = $this->Tbl_pembelian_model->get_by_uuid_spop($uuid_spop);
		$RESULT_per_uuid_spop = $this->Tbl_pembelian_model->get_by_uuid_spop_ALL_result($uuid_spop);


		// die;

		$data = array(
			'data_ALL_per_SPOP' => $RESULT_per_uuid_spop,
			'button' => 'Simpan',
			'action' => site_url('tbl_pembelian/create_pembayaran_action/' . $uuid_spop),
			'id' => set_value('id'),
			'tgl_po' => $row_per_uuid_spop->tgl_po,
			// 'nmrsj' => $row_per_uuid_spop->nmrsj,
			'nmrfakturkwitansi' => $row_per_uuid_spop->nmrfakturkwitansi,
			// 'nmrbpb' => $row_per_uuid_spop->nmrbpb,
			'uuid_spop' => $row_per_uuid_spop->uuid_spop,
			'spop' => $row_per_uuid_spop->spop,
			'supplier_kode' => $row_per_uuid_spop->supplier_kode,
			'supplier_nama' => $row_per_uuid_spop->supplier_nama,
			// 'uraian' => $row_per_uuid_spop->uraian,
			// 'jumlah' => $row_per_uuid_spop->jumlah,
			// 'satuan' => $row_per_uuid_spop->satuan,
			'uuid_konsumen' => $row_per_uuid_spop->uuid_konsumen,
			'konsumen' => $row_per_uuid_spop->konsumen,
			// 'harga_satuan' => $row_per_uuid_spop->harga_satuan,
			// 'harga_total' => $row_per_uuid_spop->harga_total,
			'statuslu' => $row_per_uuid_spop->statuslu,
			'kas_bank' => $row_per_uuid_spop->kas_bank,
			// 'tgl_bayar' => $row_per_uuid_spop->tgl_bayar,
			// 'id_usr' => $row_per_uuid_spop->,
		);

		// print_r($data);
		// die;


		// $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_form_spop_pengajuan_bayar', $data);


		// direktur
		$id_user_level = 999;
		$row_per_direktur = $this->User_model->get_by_id_user_level($id_user_level);

		// kabagkeuangan
		$id_user_level = 888;
		$row_per_kabagkeuangan = $this->User_model->get_by_id_user_level($id_user_level);

		// kasirpembelian
		$id_user_level = 777;
		$row_per_kasirpembelian = $this->User_model->get_by_id_user_level($id_user_level);


		// open form pengajuan INPUT
		$row_per_uuid_spop = $this->Tbl_pembelian_model->get_by_uuid_spop($uuid_spop);
		$RESULT_per_uuid_spop = $this->Tbl_pembelian_model->get_by_uuid_spop_ALL_result($uuid_spop);


		$RESULT_get_total_nominal_per_spop = $this->Tbl_pembelian_model->get_total_nominal_per_spop($uuid_spop);

		$x_total = 0;
		foreach ($RESULT_get_total_nominal_per_spop as $list_data) {
			$x_total = $x_total + $list_data->total_pembelian;
		}

		$data = array(
			'data_ALL_per_SPOP' => $RESULT_per_uuid_spop,
			'button' => 'Simpan',
			'action' => site_url('tbl_pembelian/create_pembayaran_action/' . $uuid_spop),
			'id' => set_value('id'),
			'tgl_po' => $row_per_uuid_spop->tgl_po,
			'nmrfakturkwitansi' => $row_per_uuid_spop->nmrfakturkwitansi,
			'uuid_spop' => $row_per_uuid_spop->uuid_spop,
			'spop' => $row_per_uuid_spop->spop,
			'nominal_pengajuan' => preg_replace("/[^0-9]/", "", $x_total),
			'supplier_kode' => $row_per_uuid_spop->supplier_kode,
			'supplier_nama' => $row_per_uuid_spop->supplier_nama,
			'uuid_konsumen' => $row_per_uuid_spop->uuid_konsumen,
			'konsumen' => $row_per_uuid_spop->konsumen,
			'statuslu' => $row_per_uuid_spop->statuslu,
			'kas_bank' => $row_per_uuid_spop->kas_bank,
			'nama_direktur' => $row_per_direktur->full_name,
			'nama_kabagkeuangan' => $row_per_kabagkeuangan->full_name,
			'nama_kasirpemebelian' => $row_per_kasirpembelian->full_name,
		);

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_cetak_pengajuan_pembayaran', $data);
	}

	public function create_pembayaran_action($uuid_spop = null)
	{

		$row_per_uuid_bank = $this->Sys_bank_model->get_by_uuid_bank($this->input->post('uuid_bank', TRUE));


		if (date("Y", strtotime($this->input->post('tgl_permohonan', TRUE))) < 2020) {
			$date_tgl_permohonan = date("Y-m-d H:i:s");
		} else {
			$date_tgl_permohonan = date("Y-m-d H:i:s", strtotime($this->input->post('tgl_permohonan', TRUE)));
		}

		if (date("Y", strtotime($this->input->post('tgl_jatuh_tempo', TRUE))) < 2020) {
			$date_tgl_jatuh_tempo = date("Y-m-d H:i:s");
		} else {
			$date_tgl_jatuh_tempo = date("Y-m-d H:i:s", strtotime($this->input->post('tgl_jatuh_tempo', TRUE)));
		}

		if (date("Y", strtotime($this->input->post('tgl_nomor_bkk', TRUE))) < 2020) {
			$date_tgl_nomor_bkk = date("Y-m-d H:i:s");
		} else {
			$date_tgl_nomor_bkk = date("Y-m-d H:i:s", strtotime($this->input->post('tgl_nomor_bkk', TRUE)));
		}


		$data = array(
			'uuid_spop' => $uuid_spop,
			'date_input' => date("Y-m-d H:i:s"),
			'tgl_pengajuan' => date("Y-m-d H:i:s"),
			'supplier_nama' => $this->input->post('supplier_nama', TRUE),


			'nominal_pengajuan' => preg_replace("/[^0-9]/", "", $this->input->post('jumlah_nominal', TRUE)),
			'nomor_permohonan' => $this->input->post('nomor_permohonan', TRUE),
			'jumlah_nominal' => $this->input->post('jumlah_nominal', TRUE),
			'tgl_permohonan' => $date_tgl_permohonan,
			'terbilang' => $this->input->post('terbilang', TRUE),
			'keterangan' => $this->input->post('keterangan', TRUE),
			'tgl_jatuh_tempo' => $date_tgl_jatuh_tempo,
			'nomor_faktur' => $this->input->post('nomor_faktur', TRUE),
			'uuid_bank' => $this->input->post('uuid_bank', TRUE),
			'nama_bank' => $row_per_uuid_bank->nama_bank,
			'nomor_rekening' => $this->input->post('nomor_rekening', TRUE),
			'atas_nama_rekening' => $this->input->post('atas_nama_rekening', TRUE),
			'nomor_bkk' => $this->input->post('nomor_bkk', TRUE),
			'tgl_nomor_bkk' => $date_tgl_nomor_bkk,
			'bank_checkbox' => $this->input->post('bank_checkbox', TRUE),
			'kas_checkbox' => $this->input->post('kas_checkbox', TRUE),
			'account' => $this->input->post('account', TRUE),
			'nomor_cek_giro' => $this->input->post('nomor_cek_giro', TRUE),
			'nama_direktur' => $this->input->post('nama_direktur', TRUE),
			'nama_kabagkeuangan' => $this->input->post('nama_kabagkeuangan', TRUE),
			'nama_kasirpemebelian' => $this->input->post('nama_kasirpemebelian', TRUE),
		);

		// print_r($data);
		// die;

		$uuid_pengajuan_bayar_terproses = $this->Tbl_pembelian_pengajuan_bayar_model->insert($data);

		// print_r("uuid_pengajuan_bayar_terproses : ");
		// print_r($uuid_pengajuan_bayar_terproses);
		// die;

		redirect(site_url('tbl_pembelian/success_pengajuan/' . $uuid_pengajuan_bayar_terproses));
	}

	public function success_pengajuan($uuid_pengajuan_bayar_terproses)
	{

		$row_per_uuid_pengajuan_bayar_terproses = $this->Tbl_pembelian_pengajuan_bayar_model->get_by_uuid_pengajuan_bayar($uuid_pengajuan_bayar_terproses);

		$uuid_spop = $row_per_uuid_pengajuan_bayar_terproses->uuid_spop;

		$row_per_uuid_spop = $this->Tbl_pembelian_model->get_by_uuid_spop($uuid_spop);
		$RESULT_per_uuid_spop = $this->Tbl_pembelian_model->get_by_uuid_spop_ALL_result($uuid_spop);

		// die;

		$data = array(
			'data_ALL_per_SPOP' => $RESULT_per_uuid_spop,
			'button' => 'Simpan',
			'action' => site_url('tbl_pembelian/create_pembayaran_action/' . $uuid_spop),
			'id' => set_value('id'),
			'tgl_po' => $row_per_uuid_spop->tgl_po,
			'nmrfakturkwitansi' => $row_per_uuid_spop->nmrfakturkwitansi,
			'uuid_spop' => $row_per_uuid_spop->uuid_spop,
			'spop' => $row_per_uuid_spop->spop,
			'supplier_kode' => $row_per_uuid_spop->supplier_kode,
			'supplier_nama' => $row_per_uuid_spop->supplier_nama,
			'uuid_konsumen' => $row_per_uuid_spop->uuid_konsumen,
			'konsumen' => $row_per_uuid_spop->konsumen,
			'statuslu' => $row_per_uuid_spop->statuslu,
			'kas_bank' => $row_per_uuid_spop->kas_bank,

			'uuid_pengajuan_bayar_terproses' => $uuid_pengajuan_bayar_terproses,


			'tgl_pengajuan' => $row_per_uuid_pengajuan_bayar_terproses->tgl_pengajuan,
			'supplier_nama' => $row_per_uuid_pengajuan_bayar_terproses->supplier_nama,
			'nominal_pengajuan' => $row_per_uuid_pengajuan_bayar_terproses->nominal_pengajuan,
			'nomor_permohonan' => $row_per_uuid_pengajuan_bayar_terproses->nomor_permohonan,
			'jumlah_nominal' => $row_per_uuid_pengajuan_bayar_terproses->jumlah_nominal,
			'tgl_permohonan' => $row_per_uuid_pengajuan_bayar_terproses->tgl_permohonan,
			'terbilang' => $row_per_uuid_pengajuan_bayar_terproses->terbilang,
			'keterangan' => $row_per_uuid_pengajuan_bayar_terproses->keterangan,
			'tgl_jatuh_tempo' => $row_per_uuid_pengajuan_bayar_terproses->tgl_jatuh_tempo,
			'nomor_faktur' => $row_per_uuid_pengajuan_bayar_terproses->nomor_faktur,
			'nama_bank' => $row_per_uuid_pengajuan_bayar_terproses->nama_bank,
			'nomor_rekening' => $row_per_uuid_pengajuan_bayar_terproses->nomor_rekening,
			'atas_nama_rekening' => $row_per_uuid_pengajuan_bayar_terproses->atas_nama_rekening,
			'nomor_bkk' => $row_per_uuid_pengajuan_bayar_terproses->nomor_bkk,
			'tgl_nomor_bkk' => $row_per_uuid_pengajuan_bayar_terproses->tgl_nomor_bkk,
			'bank_checkbox' => $row_per_uuid_pengajuan_bayar_terproses->bank_checkbox,
			'kas_checkbox' => $row_per_uuid_pengajuan_bayar_terproses->kas_checkbox,
			'account' => $row_per_uuid_pengajuan_bayar_terproses->account,
			'nomor_cek_giro' => $row_per_uuid_pengajuan_bayar_terproses->nomor_cek_giro,
			'nama_direktur' => $row_per_uuid_pengajuan_bayar_terproses->nama_direktur,
			'nama_kabagkeuangan' => $row_per_uuid_pengajuan_bayar_terproses->nama_kabagkeuangan,
			'nama_kasirpemebelian' => $row_per_uuid_pengajuan_bayar_terproses->nama_kasirpemebelian,

		);

		// print_r($data);
		// die;

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_cetak_pengajuan_pembayaran_view', $data);
	}


	public function cetak_pengajuan_bayar_per_spop($uuid_pengajuan_bayar_terproses = null)
	{



		$row_per_uuid_pengajuan_bayar_terproses = $this->Tbl_pembelian_pengajuan_bayar_model->get_by_uuid_pengajuan_bayar($uuid_pengajuan_bayar_terproses);

		$uuid_spop = $row_per_uuid_pengajuan_bayar_terproses->uuid_spop;

		$row_per_uuid_spop = $this->Tbl_pembelian_model->get_by_uuid_spop($uuid_spop);
		$RESULT_per_uuid_spop = $this->Tbl_pembelian_model->get_by_uuid_spop_ALL_result($uuid_spop);

		// die;


		// 2.a. PERSIAPAN LIBRARY
		$this->load->library('PdfGenerator');

		// 2.b. PERSIAPAN DATA

		$data = array(
			'data_ALL_per_SPOP' => $RESULT_per_uuid_spop,
			'button' => 'Simpan',
			'action' => site_url('tbl_pembelian/create_pembayaran_action/' . $uuid_spop),
			'id' => set_value('id'),
			'tgl_po' => $row_per_uuid_spop->tgl_po,
			'nmrfakturkwitansi' => $row_per_uuid_spop->nmrfakturkwitansi,
			'uuid_spop' => $row_per_uuid_spop->uuid_spop,
			'spop' => $row_per_uuid_spop->spop,
			'supplier_kode' => $row_per_uuid_spop->supplier_kode,
			'supplier_nama' => $row_per_uuid_spop->supplier_nama,
			'uuid_konsumen' => $row_per_uuid_spop->uuid_konsumen,
			'konsumen' => $row_per_uuid_spop->konsumen,
			'statuslu' => $row_per_uuid_spop->statuslu,
			'kas_bank' => $row_per_uuid_spop->kas_bank,


			'tgl_pengajuan' => $row_per_uuid_pengajuan_bayar_terproses->tgl_pengajuan,
			'supplier_nama' => $row_per_uuid_pengajuan_bayar_terproses->supplier_nama,
			'nominal_pengajuan' => $row_per_uuid_pengajuan_bayar_terproses->nominal_pengajuan,
			'nomor_permohonan' => $row_per_uuid_pengajuan_bayar_terproses->nomor_permohonan,
			'jumlah_nominal' => $row_per_uuid_pengajuan_bayar_terproses->jumlah_nominal,
			'tgl_permohonan' => $row_per_uuid_pengajuan_bayar_terproses->tgl_permohonan,
			'terbilang' => $row_per_uuid_pengajuan_bayar_terproses->terbilang,
			'keterangan' => $row_per_uuid_pengajuan_bayar_terproses->keterangan,
			'tgl_jatuh_tempo' => $row_per_uuid_pengajuan_bayar_terproses->tgl_jatuh_tempo,
			'nomor_faktur' => $row_per_uuid_pengajuan_bayar_terproses->nomor_faktur,
			'nama_bank' => $row_per_uuid_pengajuan_bayar_terproses->nama_bank,
			'nomor_rekening' => $row_per_uuid_pengajuan_bayar_terproses->nomor_rekening,
			'atas_nama_rekening' => $row_per_uuid_pengajuan_bayar_terproses->atas_nama_rekening,
			'nomor_bkk' => $row_per_uuid_pengajuan_bayar_terproses->nomor_bkk,
			'tgl_nomor_bkk' => $row_per_uuid_pengajuan_bayar_terproses->tgl_nomor_bkk,
			'bank_checkbox' => $row_per_uuid_pengajuan_bayar_terproses->bank_checkbox,
			'kas_checkbox' => $row_per_uuid_pengajuan_bayar_terproses->kas_checkbox,
			'account' => $row_per_uuid_pengajuan_bayar_terproses->account,
			'nomor_cek_giro' => $row_per_uuid_pengajuan_bayar_terproses->nomor_cek_giro,
			'nama_direktur' => $row_per_uuid_pengajuan_bayar_terproses->nama_direktur,
			'nama_kabagkeuangan' => $row_per_uuid_pengajuan_bayar_terproses->nama_kabagkeuangan,
			'nama_kasirpemebelian' => $row_per_uuid_pengajuan_bayar_terproses->nama_kasirpemebelian,

		);


		// 2.C. MENAMPILKAN FILE DATA
		// $data = array_merge($data);
		$html = $this->load->view('anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_cetak_pengajuan_pembayaran_print.php', $data, true);

		// 2.d. CONVERT TAMPILAN FILE DATA MENJADI FILE PDF
		$this->pdf->loadHtml($html);
		$this->pdf->render();

		$this->pdf->stream("NOTA_PENJUALAN.pdf", array("Attachment" => 0));
	}


	public function pembayaran()
	{
		// Data pembayaran KE supplier
		// $Data_supplier_tagihan = $this->Tbl_pembelian_model->supplier_tagihan();
		// $Data_supplier_tagihan = $this->Tbl_pembelian_pengajuan_bayar_model->get_all();


		$sql = "SELECT tbl_pembelian_a.uuid_spop as uuid_spop, 
        tbl_pembelian_a.spop as spop,
        tbl_pembelian_a.jumlah as jumlah,
        tbl_pembelian_a.harga_satuan as harga_satuan,
        sum(tbl_pembelian_a.harga_total) as total_pembelian,
        tbl_pembelian_a.supplier_nama as supplier_nama,
        tbl_pembelian_pengajuan_bayar_a.uuid_pengajuan_bayar as uuid_pengajuan_bayar,
        tbl_pembelian_pengajuan_bayar_a.nominal_pengajuan as nominal_pengajuan


        FROM tbl_pembelian tbl_pembelian_a 
		
		left join   tbl_pembelian_pengajuan_bayar  tbl_pembelian_pengajuan_bayar_a ON  tbl_pembelian_pengajuan_bayar_a.uuid_spop = tbl_pembelian_a.uuid_spop

		group by tbl_pembelian_a.uuid_spop,tbl_pembelian_pengajuan_bayar_a.uuid_pengajuan_bayar
        ";

		// return $this->db->query($sql)->result();

		// print_r($this->db->query($sql)->result());
		

		$Data_supplier_tagihan =$this->db->query($sql)->result();
	

		$Data_konsumen_tagihan = $this->Tbl_penjualan_model->konsumen_tagihan();
		// print_r($Data_konsumen_tagihan);
		// die;

		$data = array(
			'Data_supplier_tagihan' => $Data_supplier_tagihan,
			'Data_konsumen_tagihan' => $Data_konsumen_tagihan,
		);

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/pembayaran/adminlte310_pembayaran_list', $data);
	}

	public function read($id)
	{
		$row = $this->Tbl_pembelian_model->get_by_id($id);
		if ($row) {
			$data = array(
				'id' => $row->id,
				'tgl_po' => $row->tgl_po,
				'nmrsj' => $row->nmrsj,
				'nmrfakturkwitansi' => $row->nmrfakturkwitansi,
				'nmrbpb' => $row->nmrbpb,
				'spop' => $row->spop,
				'supplier_kode' => $row->supplier_kode,
				'supplier_nama' => $row->supplier_nama,
				'uraian' => $row->uraian,
				'jumlah' => $row->jumlah,
				'satuan' => $row->satuan,
				'konsumen' => $row->konsumen,
				'harga_satuan' => $row->harga_satuan,
				'harga_total' => $row->harga_total,
				'statuslu' => $row->statuslu,
				'kas_bank' => $row->kas_bank,
				'tgl_bayar' => $row->tgl_bayar,
				'id_usr' => $row->id_usr,
			);
			$this->load->view('anekadharma/tbl_pembelian/tbl_pembelian_read', $data);
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(site_url('tbl_pembelian'));
		}
	}

	public function create()
	{
		$data = array(
			'button' => 'Simpan',
			'action' => site_url('tbl_pembelian/create_action_uuid_spop'),
			'id' => set_value('id'),
			'tgl_po' => set_value('tgl_po'),
			'nmrsj' => set_value('nmrsj'),
			'nmrfakturkwitansi' => set_value('nmrfakturkwitansi'),
			'nmrbpb' => set_value('nmrbpb'),
			'spop' => set_value('spop'),
			'supplier_kode' => set_value('supplier_kode'),
			'supplier_nama' => set_value('supplier_nama'),
			'uraian' => set_value('uraian'),
			'jumlah' => set_value('jumlah'),
			'satuan' => set_value('satuan'),
			'konsumen' => set_value('konsumen'),
			'harga_satuan' => set_value('harga_satuan'),
			'harga_total' => set_value('harga_total'),
			'statuslu' => set_value('statuslu'),
			'kas_bank' => set_value('kas_bank'),
			'tgl_bayar' => set_value('tgl_bayar'),
			'id_usr' => set_value('id_usr'),
		);
		// $this->load->view('anekadharma/tbl_pembelian/tbl_pembelian_form', $data);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_form', $data);
	}


	public function create_action()
	{
		$this->_rules();

		if (($this->form_validation->run() == FALSE) or is_null($this->input->post('uuid_supplier', TRUE))  or is_null($this->input->post('uuid_konsumen', TRUE))) {
			$this->create();
		} else {


			// print_r($_POST["uraian"]);
			// print_r("<br/>");

			// GET SPOP
			$sql = "SELECT MAX(`spop`) as maxSPOP FROM `tbl_pembelian`";
			$get_maxSPOP = $this->db->query($sql)->row()->maxSPOP;

			$get_maxSPOP = $get_maxSPOP + 1;


			// DATE PO

			if (date("Y", strtotime($this->input->post('tgl_po', TRUE))) < 2020) {
				// print_r("Tahun kurang dari 2020");
				$date_po = date("Y-m-d H:i:s");
			} else {
				// print_r("Tahun lebih dari 2020");
				$date_po = date("Y-m-d H:i:s", strtotime($this->input->post('tgl_po', TRUE)));
			}


			// GET SUPPLIER DATA
			$get_uuid_supplier = $this->input->post('uuid_supplier', TRUE);
			$sql_uuid_supplier = "SELECT * FROM `sys_supplier` WHERE `uuid_supplier`='$get_uuid_supplier'";
			$get_kode_supplier = $this->db->query($sql_uuid_supplier)->row()->kode_supplier;
			$get_nama_supplier = $this->db->query($sql_uuid_supplier)->row()->nama_supplier;


			// GET KONSUMEN DATA
			$GET_uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
			// $sql_uuid_konsumen = "SELECT * FROM `sys_konsumen` WHERE `uuid_konsumen`='$GET_uuid_konsumen'";
			$sql_uuid_konsumen = "SELECT * FROM `sys_unit` WHERE `uuid_unit`='$GET_uuid_konsumen'";
			$get_kode_konsumen = $this->db->query($sql_uuid_konsumen)->row()->kode_unit;
			$get_nama_konsumen = $this->db->query($sql_uuid_konsumen)->row()->nama_unit;




			// print_r(date("Y-m-d H:i:s", strtotime($this->input->post('tgl_po', TRUE))) );
			// die;

			if (isset($_POST["uraian"])) {
				$hoby = $_POST["uraian"];
				reset($hoby);
				while (list($key, $value) = each($hoby)) {
					$uraian   = $_POST["uraian"][$key];
					$jumlah     = $_POST["jumlah"][$key];
					$satuan     = $_POST["satuan"][$key];
					$hargasatuan     = $_POST["hargasatuan"][$key];

					// $sql_hoby   = "INSERT INTO tbl_hoby(rincian_hoby,jenis_hoby,id_karyawan)
					//   VALUES('$rincian_hoby','$jenis_hoby','$id_karyawan')";
					// $hasil_hoby = mysql_query($sql_hoby);

					$data = array(
						'date_input' => date("Y-m-d H:i:s"),
						'tgl_po' => $date_po,
						'nmrsj' => $this->input->post('nmrsj', TRUE),
						'nmrfakturkwitansi' => $this->input->post('nmrfakturkwitansi', TRUE),
						'nmrbpb' => $this->input->post('nmrbpb', TRUE),
						'spop' => $get_maxSPOP,
						// 'supplier_kode' => $get_kode_supplier,
						'supplier_nama' => $get_nama_supplier,
						'uraian' => $uraian,
						'jumlah' => $jumlah,
						'satuan' => $satuan,
						'konsumen' => $get_nama_konsumen,
						'harga_satuan' => $hargasatuan,
						// 'harga_total' => $this->input->post('harga_total', TRUE),
						'statuslu' => $this->input->post('statuslu', TRUE),
						'kas_bank' => $this->input->post('kas_bank', TRUE),
						// 'tgl_bayar' => $this->input->post('tgl_bayar', TRUE),
						'id_usr' => 1,
					);

					$this->Tbl_pembelian_model->insert($data);
				}
			}



			$this->session->set_flashdata('message', 'Create Record Success');
			redirect(site_url('tbl_pembelian'));
		}
	}



	public function create_add_uraian($uuid_spop = null)
	{


		$row_per_uuid_spop = $this->Tbl_pembelian_model->get_by_uuid_spop($uuid_spop);
		$RESULT_per_uuid_spop = $this->Tbl_pembelian_model->get_by_uuid_spop_ALL_result($uuid_spop);


		// $jumlah_x = preg_replace("/[^0-9]/", "", $this->input->post('jumlah', TRUE));
		// $harga_satuan_x = preg_replace("/[^0-9]/", "", $this->input->post('harga_satuan', TRUE));
		$TOTAL_X = $row_per_uuid_spop->jumlah * $row_per_uuid_spop->harga_satuan;

		$data = array(
			'data_ALL_per_SPOP' => $RESULT_per_uuid_spop,
			'button' => 'Simpan',
			'action' => site_url('tbl_pembelian/create_action_uuid_spop/' . $uuid_spop),
			'id' => set_value('id'),
			'tgl_po' => $row_per_uuid_spop->tgl_po,
			// 'nmrsj' => $row_per_uuid_spop->nmrsj,
			'nmrfakturkwitansi' => $row_per_uuid_spop->nmrfakturkwitansi,
			// 'nmrbpb' => $row_per_uuid_spop->nmrbpb,
			'uuid_spop' => $row_per_uuid_spop->uuid_spop,
			'spop' => $row_per_uuid_spop->spop,
			'supplier_kode' => $row_per_uuid_spop->supplier_kode,
			'supplier_nama' => $row_per_uuid_spop->supplier_nama,
			// 'uraian' => $row_per_uuid_spop->uraian,
			// 'jumlah' => $row_per_uuid_spop->jumlah,
			// 'satuan' => $row_per_uuid_spop->satuan,
			'uuid_konsumen' => $row_per_uuid_spop->uuid_konsumen,
			'konsumen' => $row_per_uuid_spop->konsumen,
			// 'harga_satuan' => $row_per_uuid_spop->harga_satuan,
			'harga_total' => $TOTAL_X,
			'statuslu' => $row_per_uuid_spop->statuslu,
			'kas_bank' => $row_per_uuid_spop->kas_bank,
			// 'tgl_bayar' => $row_per_uuid_spop->tgl_bayar,
			// 'id_usr' => $row_per_uuid_spop->,
		);

		// print_r($data);
		// die;

		// $this->load->view('anekadharma/tbl_pembelian/tbl_pembelian_form', $data);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_form_spop_ready', $data);
	}

	public function create_action_uuid_spop($uuid_spop = null)
	{

		if ($uuid_spop) {
			// print_r("ada SPOP");
			// die;

			$row_per_uuid_spop = $this->Tbl_pembelian_model->get_by_uuid_spop($uuid_spop);

			// GET KONSUMEN DATA
			$GET_uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
			// $sql_uuid_konsumen = "SELECT * FROM `sys_konsumen` WHERE `uuid_konsumen`='$GET_uuid_konsumen'";

			$sql_uuid_konsumen = "SELECT * FROM `sys_unit` WHERE `uuid_unit`='$GET_uuid_konsumen'";
			$get_kode_konsumen = $this->db->query($sql_uuid_konsumen)->row()->kode_unit;
			// $get_nama_konsumen = $this->db->query($sql_uuid_konsumen)->row()->nama_unit;

			// print_r($sql_uuid_konsumen);
			// die;

			$get_nama_konsumen = $this->db->query($sql_uuid_konsumen)->row()->nama_unit;


			// GET BARANG DATA
			$GET_uuid_barang = $this->input->post('uuid_barang', TRUE);
			$sql_uuid_barang = "SELECT * FROM `sys_nama_barang` WHERE `uuid_barang`='$GET_uuid_barang'";
			// $get_kode_konsumen = $this->db->query($sql_uuid_konsumen)->row()->kode_konsumen;
			$get_nama_barang = $this->db->query($sql_uuid_barang)->row()->nama_barang;

			$jumlah_x = preg_replace("/[^0-9]/", "", $this->input->post('jumlah', TRUE));
			$harga_satuan_x = preg_replace("/[^0-9]/", "", $this->input->post('harga_satuan', TRUE));
			$TOTAL_X = $jumlah_x * $harga_satuan_x;


			$data = array(
				'date_input' => $row_per_uuid_spop->date_input,

				'tgl_po' => $row_per_uuid_spop->tgl_po,

				// 'nmrsj' => $this->input->post('nmrsj', TRUE),

				'nmrfakturkwitansi' => $row_per_uuid_spop->nmrfakturkwitansi,

				// 'nmrbpb' => $this->input->post('nmrbpb', TRUE),

				'uuid_spop' => $row_per_uuid_spop->uuid_spop,
				'spop' => $row_per_uuid_spop->spop,

				// 'supplier_kode' => $get_kode_supplier,
				'uuid_supplier' => $row_per_uuid_spop->uuid_supplier,
				'supplier_nama' => $row_per_uuid_spop->supplier_nama,


				'statuslu' => $row_per_uuid_spop->statuslu,
				'kas_bank' => $row_per_uuid_spop->kas_bank,


				'uuid_barang' => $this->input->post('uuid_barang', TRUE),
				'uraian' => $get_nama_barang,

				'jumlah' => $jumlah_x,
				'satuan' => $this->input->post('satuan', TRUE),
				'harga_satuan' => $harga_satuan_x,

				'uuid_konsumen' => $this->input->post('uuid_konsumen', TRUE),
				'konsumen' => $get_nama_konsumen,

				'harga_total' => $TOTAL_X,

				// 'tgl_bayar' => $this->input->post('tgl_bayar', TRUE),
				'id_usr' => 1,
			);
			// print_r($data);
			// die;

			$this->Tbl_pembelian_model->insert($data); // insert untuk data lanjutan uuid_spop sudah ada
			$get_uuid_spop_generating = $uuid_spop;
		} else {





			// print_r("Tidak ada SPOP");
			// print_r("<br/>");
			// print_r($this->input->post('uuid_barang', TRUE));
			// print_r("<br/>");
			// // Generate uuid_spop
			// die;

			// DATE PO

			if (date("Y", strtotime($this->input->post('tgl_po', TRUE))) < 2020) {
				// print_r("Tahun kurang dari 2020");
				$date_po = date("Y-m-d H:i:s");
			} else {
				// print_r("Tahun lebih dari 2020");
				$date_po = date("Y-m-d H:i:s", strtotime($this->input->post('tgl_po', TRUE)));
			}

			// GET SUPPLIER DATA
			$get_uuid_supplier = $this->input->post('uuid_supplier', TRUE);
			$sql_uuid_supplier = "SELECT * FROM `sys_supplier` WHERE `uuid_supplier`='$get_uuid_supplier'";
			$get_kode_supplier = $this->db->query($sql_uuid_supplier)->row()->kode_supplier;
			$get_nama_supplier = $this->db->query($sql_uuid_supplier)->row()->nama_supplier;

			// GET KONSUMEN DATA
			$GET_uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
			// $sql_uuid_konsumen = "SELECT * FROM `sys_konsumen` WHERE `uuid_konsumen`='$GET_uuid_konsumen'";

			$sql_uuid_konsumen = "SELECT * FROM `sys_unit` WHERE `uuid_unit`='$GET_uuid_konsumen'";
			$get_kode_konsumen = $this->db->query($sql_uuid_konsumen)->row()->kode_unit;
			$get_nama_konsumen = $this->db->query($sql_uuid_konsumen)->row()->nama_unit;
			
			// $get_kode_konsumen = $this->db->query($sql_uuid_konsumen)->row()->kode_konsumen;
			// $get_nama_konsumen = $this->db->query($sql_uuid_konsumen)->row()->nama_konsumen;


			// GET BARANG DATA
			$GET_uuid_barang = $this->input->post('uuid_barang', TRUE);
			$sql_uuid_barang = "SELECT * FROM `sys_nama_barang` WHERE `uuid_barang`='$GET_uuid_barang'";
			// $get_kode_konsumen = $this->db->query($sql_uuid_konsumen)->row()->kode_konsumen;
			$get_nama_barang = $this->db->query($sql_uuid_barang)->row()->nama_barang;

			// print_r($GET_uuid_barang);
			// print_r("<br/>");
			$jumlah_x = preg_replace("/[^0-9]/", "", $this->input->post('jumlah', TRUE));
			$harga_satuan_x = preg_replace("/[^0-9]/", "", $this->input->post('harga_satuan', TRUE));
			$TOTAL_X = $jumlah_x * $harga_satuan_x;

			$data = array(
				'date_input' => date("Y-m-d H:i:s"),

				'tgl_po' => $date_po,

				// 'nmrsj' => $this->input->post('nmrsj', TRUE),

				'nmrfakturkwitansi' => $this->input->post('nmrfakturkwitansi', TRUE),

				// 'nmrbpb' => $this->input->post('nmrbpb', TRUE),

				'spop' => $this->input->post('spop', TRUE),

				// 'supplier_kode' => $get_kode_supplier,
				'uuid_supplier' => $this->input->post('uuid_supplier', TRUE),
				'supplier_nama' => $get_nama_supplier,

				'nmrfakturkwitansi' => $this->input->post('nmrfakturkwitansi', TRUE),

				'uuid_barang' => $this->input->post('uuid_barang', TRUE),
				'uraian' => $get_nama_barang,

				'jumlah' => $this->input->post('jumlah', TRUE),
				'satuan' => $this->input->post('satuan', TRUE),
				'harga_satuan' => $this->input->post('harga_satuan', TRUE),

				'uuid_konsumen' => $this->input->post('uuid_konsumen', TRUE),
				'konsumen' => $get_nama_konsumen,

				'harga_total' => $TOTAL_X,
				'statuslu' => $this->input->post('statuslu', TRUE),
				'kas_bank' => $this->input->post('kas_bank', TRUE),
				// 'tgl_bayar' => $this->input->post('tgl_bayar', TRUE),
				'id_usr' => 1,
			);


			$get_uuid_spop_generating = $this->Tbl_pembelian_model->insert_spop($data);
			// print_r("<br/>");
			// print_r($get_uuid_spop_generating);
			// print_r("<br/>");
			// // print_r("Selesai");
			// print_r("Selesai");
		}


		redirect(site_url('tbl_pembelian/create_add_uraian/' . $get_uuid_spop_generating));
	}

	public function simpan_data_spop($uuid_spop = null)
	{

		// 1. UPDATE DATA
		$data = array(
			'proses_input' => 1,
		);

		$this->Tbl_pembelian_model->update_proses_per_spop($uuid_spop, $data);

		// redirect("https://google.com", ['target' => '_blank']);
		redirect(site_url('tbl_pembelian'));






		// die;


		// 2. proses cetak form , di tab baru




		// echo "
		// <script>
		// 	window.open('https://duckduckgo.com/?t=ffab', '_blank');
		// </script>
		// ";

	}


	public function cetak_belanja_per_spop($uuid_spop = null)
	{

		// 2.a. PERSIAPAN LIBRARY
		$this->load->library('PdfGenerator');

		// 2.b. PERSIAPAN DATA
		$data = array(
			'data_belanja' => $this->Tbl_pembelian_model->get_by_spop($uuid_spop, $data),
		);

		// 2.C. MENAMPILKAN FILE DATA
		// $data = array_merge($data);
		$html = $this->load->view('anekadharma/tbl_pembelian/adminlte310_cetak_pembelian.php', $data, true);

		// 2.d. CONVERT TAMPILAN FILE DATA MENJADI FILE PDF
		$this->pdf->loadHtml($html);
		$this->pdf->render();

		$this->pdf->stream("NOTA_PENJUALAN.pdf", array("Attachment" => 0));
	}

	public function update($id)
	{
		$row = $this->Tbl_pembelian_model->get_by_id($id);

		if ($row) {
			$data = array(
				'button' => 'Update',
				'action' => site_url('tbl_pembelian/update_action'),
				'id' => set_value('id', $row->id),
				'tgl_po' => set_value('tgl_po', $row->tgl_po),
				'nmrsj' => set_value('nmrsj', $row->nmrsj),
				'nmrfakturkwitansi' => set_value('nmrfakturkwitansi', $row->nmrfakturkwitansi),
				'nmrbpb' => set_value('nmrbpb', $row->nmrbpb),
				'spop' => set_value('spop', $row->spop),
				'supplier_kode' => set_value('supplier_kode', $row->supplier_kode),
				'supplier_nama' => set_value('supplier_nama', $row->supplier_nama),
				'uraian' => set_value('uraian', $row->uraian),
				'jumlah' => set_value('jumlah', $row->jumlah),
				'satuan' => set_value('satuan', $row->satuan),
				'konsumen' => set_value('konsumen', $row->konsumen),
				'harga_satuan' => set_value('harga_satuan', $row->harga_satuan),
				'harga_total' => set_value('harga_total', $row->harga_total),
				'statuslu' => set_value('statuslu', $row->statuslu),
				'kas_bank' => set_value('kas_bank', $row->kas_bank),
				'tgl_bayar' => set_value('tgl_bayar', $row->tgl_bayar),
				'id_usr' => set_value('id_usr', $row->id_usr),
			);
			$this->load->view('anekadharma/tbl_pembelian/tbl_pembelian_form', $data);
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(site_url('tbl_pembelian'));
		}
	}

	public function update_per_spop($spop)
	{
		// $result = $this->Tbl_pembelian_model->get_by_spop($spop);
		// print_r($result);
		// die;
		$Tbl_pembelian = $this->Tbl_pembelian_model->get_by_spop($spop);
		$start=0;
		$data = array(
			'Tbl_pembelian_data' => $Tbl_pembelian,
			'start' => $start,
		);

		// 		print_r($data);
		// 		print_r("<br/>");
		// 		print_r("<br/>");
		// die;

		// $this->template->load('anekadharma/adminlte310_anekadharma', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_list_per_spop', $data);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_list_per_spop', $data);
	}

	public function update_action()
	{
		$this->_rules();

		if ($this->form_validation->run() == FALSE) {
			$this->update($this->input->post('id', TRUE));
		} else {
			$data = array(
				'tgl_po' => $this->input->post('tgl_po', TRUE),
				'nmrsj' => $this->input->post('nmrsj', TRUE),
				'nmrfakturkwitansi' => $this->input->post('nmrfakturkwitansi', TRUE),
				'nmrbpb' => $this->input->post('nmrbpb', TRUE),
				'spop' => $this->input->post('spop', TRUE),
				'supplier_kode' => $this->input->post('supplier_kode', TRUE),
				'supplier_nama' => $this->input->post('supplier_nama', TRUE),
				'uraian' => $this->input->post('uraian', TRUE),
				'jumlah' => $this->input->post('jumlah', TRUE),
				'satuan' => $this->input->post('satuan', TRUE),
				'konsumen' => $this->input->post('konsumen', TRUE),
				'harga_satuan' => $this->input->post('harga_satuan', TRUE),
				'harga_total' => $this->input->post('harga_total', TRUE),
				'statuslu' => $this->input->post('statuslu', TRUE),
				'kas_bank' => $this->input->post('kas_bank', TRUE),
				'tgl_bayar' => $this->input->post('tgl_bayar', TRUE),
				'id_usr' => $this->input->post('id_usr', TRUE),
			);

			$this->Tbl_pembelian_model->update($this->input->post('id', TRUE), $data);
			$this->session->set_flashdata('message', 'Update Record Success');
			redirect(site_url('tbl_pembelian'));
		}
	}

	public function delete($id)
	{
		$row = $this->Tbl_pembelian_model->get_by_id($id);

		if ($row) {
			$this->Tbl_pembelian_model->delete($id);
			$this->session->set_flashdata('message', 'Delete Record Success');
			redirect(site_url('tbl_pembelian'));
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(site_url('tbl_pembelian'));
		}
	}

	public function _rules()
	{
		// $this->form_validation->set_rules('tgl_po', 'tgl po', 'trim|required');
		// // $this->form_validation->set_rules('nmrsj', 'nmrsj', 'trim|required');
		// $this->form_validation->set_rules('nmrfakturkwitansi', 'nmrfakturkwitansi', 'trim|required');
		// $this->form_validation->set_rules('nmrbpb', 'nmrbpb', 'trim|required');
		$this->form_validation->set_rules('spop', 'spop', 'trim|required');
		$this->form_validation->set_rules('uuid_supplier', 'supplier', 'trim|required');
		// $this->form_validation->set_rules('supplier_nama', 'supplier nama', 'trim|required');
		// $this->form_validation->set_rules('uraian', 'uraian', 'trim|required');
		// $this->form_validation->set_rules('jumlah', 'jumlah', 'trim|required');
		// $this->form_validation->set_rules('satuan', 'satuan', 'trim|required');
		// // $this->form_validation->set_rules('konsumen', 'konsumen', 'trim|required');
		// $this->form_validation->set_rules('harga_satuan', 'harga satuan', 'trim|required|numeric');
		// // $this->form_validation->set_rules('harga_total', 'harga total', 'trim|required|numeric');
		// $this->form_validation->set_rules('statuslu', 'statuslu', 'trim|required');
		// // $this->form_validation->set_rules('kas_bank', 'kas bank', 'trim|required');
		// // $this->form_validation->set_rules('tgl_bayar', 'tgl bayar', 'trim|required');
		// // $this->form_validation->set_rules('id_usr', 'id usr', 'trim|required');

		$this->form_validation->set_rules('id', 'id', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
	}

	public function excel()
	{
		$this->load->helper('exportexcel');
		$namaFile = "tbl_pembelian.xls";
		$judul = "tbl_pembelian";
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
		xlsWriteLabel($tablehead, $kolomhead++, "Tgl Po");
		xlsWriteLabel($tablehead, $kolomhead++, "Nmrsj");
		xlsWriteLabel($tablehead, $kolomhead++, "Nmrfakturkwitansi");
		xlsWriteLabel($tablehead, $kolomhead++, "Nmrbpb");
		xlsWriteLabel($tablehead, $kolomhead++, "Spop");
		xlsWriteLabel($tablehead, $kolomhead++, "Supplier Kode");
		xlsWriteLabel($tablehead, $kolomhead++, "Supplier Nama");
		xlsWriteLabel($tablehead, $kolomhead++, "Uraian");
		xlsWriteLabel($tablehead, $kolomhead++, "Jumlah");
		xlsWriteLabel($tablehead, $kolomhead++, "Satuan");
		xlsWriteLabel($tablehead, $kolomhead++, "Konsumen");
		xlsWriteLabel($tablehead, $kolomhead++, "Harga Satuan");
		xlsWriteLabel($tablehead, $kolomhead++, "Harga Total");
		xlsWriteLabel($tablehead, $kolomhead++, "Statuslu");
		xlsWriteLabel($tablehead, $kolomhead++, "Kas Bank");
		xlsWriteLabel($tablehead, $kolomhead++, "Tgl Bayar");
		xlsWriteLabel($tablehead, $kolomhead++, "Id Usr");

		foreach ($this->Tbl_pembelian_model->get_all() as $data) {
			$kolombody = 0;

			//ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
			xlsWriteNumber($tablebody, $kolombody++, $nourut);
			xlsWriteLabel($tablebody, $kolombody++, $data->tgl_po);
			xlsWriteLabel($tablebody, $kolombody++, $data->nmrsj);
			xlsWriteLabel($tablebody, $kolombody++, $data->nmrfakturkwitansi);
			xlsWriteNumber($tablebody, $kolombody++, $data->nmrbpb);
			xlsWriteNumber($tablebody, $kolombody++, $data->spop);
			xlsWriteNumber($tablebody, $kolombody++, $data->supplier_kode);
			xlsWriteLabel($tablebody, $kolombody++, $data->supplier_nama);
			xlsWriteLabel($tablebody, $kolombody++, $data->uraian);
			xlsWriteNumber($tablebody, $kolombody++, $data->jumlah);
			xlsWriteLabel($tablebody, $kolombody++, $data->satuan);
			xlsWriteLabel($tablebody, $kolombody++, $data->konsumen);
			xlsWriteNumber($tablebody, $kolombody++, $data->harga_satuan);
			xlsWriteNumber($tablebody, $kolombody++, $data->harga_total);
			xlsWriteLabel($tablebody, $kolombody++, $data->statuslu);
			xlsWriteLabel($tablebody, $kolombody++, $data->kas_bank);
			xlsWriteLabel($tablebody, $kolombody++, $data->tgl_bayar);
			xlsWriteNumber($tablebody, $kolombody++, $data->id_usr);

			$tablebody++;
			$nourut++;
		}

		xlsEOF();
		exit();
	}

	public function unit()
	{
		$Data_stock = $this->Tbl_pembelian_model->stock();
		$data = array(
			'Data_stock' => $Data_stock,
		);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/stock/adminlte310_stock_barang', $data);
	}



}

/* End of file Tbl_pembelian.php */
/* Location: ./application/controllers/Tbl_pembelian.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-09-03 14:57:23 */
/* http://harviacode.com */