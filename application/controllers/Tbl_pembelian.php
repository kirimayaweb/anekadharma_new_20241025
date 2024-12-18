<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Tbl_pembelian extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_login();
		$this->load->model(array('Tbl_pembelian_model', 'Tbl_penjualan_model', 'Tbl_pembelian_pengajuan_bayar_model', 'User_model', 'Sys_bank_model', 'Sys_status_transaksi_model', 'Tbl_penjualan_pembayaran_model', 'Tbl_pembelian_pecah_satuan_model', 'Sys_nama_barang_model'));
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

	public function stock($uuid_gudang = null)
	{

		// $uuid_gudang="cd64c3af883c11ef9d7f0021ccc9061e";

		// $sql_stock = "SELECT persediaan.namabarang as nama_barang_beli,persediaan.total_10 as jumlah_sediaan, 
		// tbl_pembelian.uraian as barang_beli, tbl_pembelian.jumlah as jumlah_belanja, tbl_pembelian.harga_satuan as harga_satuan_beli, tbl_pembelian.tgl_po as tgl_po,tbl_pembelian.uuid_gudang as uuid_gudang, tbl_pembelian.nama_gudang as nama_gudang, tbl_pembelian.satuan as satuan,
		// tbl_penjualan.nama_barang as barang_jual, tbl_penjualan.jumlah as jumlah_terjual
		//                     FROM persediaan  
		//                    	left join tbl_pembelian ON persediaan.uuid_barang = tbl_pembelian.uuid_barang 
		//                     left join tbl_penjualan ON persediaan.uuid_barang = tbl_penjualan.uuid_barang  
		// 					WHERE tbl_pembelian.uuid_gudang='$uuid_gudang' AND (persediaan.uuid_barang, persediaan.tanggal) IN (SELECT persediaan.uuid_barang, Max(persediaan.tanggal) FROM persediaan GROUP BY persediaan.uuid_barang)  
		//                     ORDER BY persediaan.uuid_barang ASC";
		// print_r($this->db->query($sql_stock)->result());


		// die;

		if (null !== ($this->input->post('uuid_gudang', TRUE))) {
			if ($this->input->post('uuid_gudang', TRUE) == "semua") {

				// print_r("IF SEMUA");
				//Pilih combobox dengan pilihan semua
				// $Data_stock = $this->Tbl_pembelian_model->stock();

				$sql_stock = "SELECT persediaan.kode_barang as kode_barang, persediaan.namabarang as nama_barang_beli,persediaan.total_10 as jumlah_sediaan,  persediaan.hpp as harga_satuan_persediaan,
								tbl_pembelian.uuid_pembelian as uuid_pembelian,tbl_pembelian.uraian as barang_beli, tbl_pembelian.jumlah as jumlah_belanja, tbl_pembelian.harga_satuan as harga_satuan_beli,  tbl_pembelian.tgl_po as tgl_po,tbl_pembelian.uuid_gudang as uuid_gudang, tbl_pembelian.nama_gudang as nama_gudang,  tbl_pembelian.satuan as satuan,
							tbl_penjualan.nama_barang as barang_jual, tbl_penjualan.jumlah as jumlah_terjual
                            FROM persediaan  
                           	left join tbl_pembelian ON persediaan.uuid_barang = tbl_pembelian.uuid_barang 
                            left join tbl_penjualan ON persediaan.uuid_barang = tbl_penjualan.uuid_barang  
							WHERE (persediaan.uuid_barang, persediaan.tanggal) IN (SELECT persediaan.uuid_barang, Max(persediaan.tanggal) FROM persediaan GROUP BY persediaan.uuid_barang)  
                            ORDER BY persediaan.uuid_barang ASC";

				// print_r($this->db->query($sql_stock)->result());
				$Data_stock = $this->db->query($sql_stock)->result();
			} else {
				// print_r("IF GUDANG");
				$uuid_gudang = $this->input->post('uuid_gudang', TRUE);
				// $Data_stock = $this->Tbl_pembelian_model->stock_by_gudang($uuid_gudang);
				$sql_stock = "SELECT persediaan.kode_barang as kode_barang, persediaan.namabarang as nama_barang_beli,persediaan.total_10 as jumlah_sediaan, persediaan.hpp as harga_satuan_persediaan,
								tbl_pembelian.uuid_pembelian as uuid_pembelian,tbl_pembelian.uraian as barang_beli, tbl_pembelian.jumlah as jumlah_belanja, tbl_pembelian.harga_satuan as harga_satuan_beli, tbl_pembelian.tgl_po as tgl_po,tbl_pembelian.uuid_gudang as uuid_gudang, tbl_pembelian.nama_gudang as nama_gudang,tbl_pembelian.satuan as satuan,
							tbl_penjualan.nama_barang as barang_jual, tbl_penjualan.jumlah as jumlah_terjual
							FROM persediaan  
			   				left join tbl_pembelian ON persediaan.uuid_barang = tbl_pembelian.uuid_barang 
							left join tbl_penjualan ON persediaan.uuid_barang = tbl_penjualan.uuid_barang  
							WHERE tbl_pembelian.uuid_gudang='$uuid_gudang' AND (persediaan.uuid_barang, persediaan.tanggal) IN (SELECT persediaan.uuid_barang, Max(persediaan.tanggal) FROM persediaan GROUP BY persediaan.uuid_barang)  
							ORDER BY persediaan.uuid_barang ASC";

				// print_r($this->db->query($sql_stock)->result());
				$Data_stock = $this->db->query($sql_stock)->result();
			}
		} else {
			// $Data_stock = $this->Tbl_pembelian_model->stock();
			// print_r("ELSE SEMUA");
			$sql_stock = "SELECT persediaan.kode_barang as kode_barang, persediaan.namabarang as nama_barang_beli,persediaan.total_10 as jumlah_sediaan, persediaan.hpp as harga_satuan_persediaan,
						tbl_pembelian.uuid_pembelian as uuid_pembelian,tbl_pembelian.uraian as barang_beli, tbl_pembelian.jumlah as jumlah_belanja, tbl_pembelian.harga_satuan as harga_satuan_beli, tbl_pembelian.tgl_po as tgl_po,tbl_pembelian.uuid_gudang as uuid_gudang, tbl_pembelian.nama_gudang as nama_gudang,tbl_pembelian.satuan as satuan,
					tbl_penjualan.nama_barang as barang_jual, tbl_penjualan.jumlah as jumlah_terjual
					FROM persediaan  
					left join tbl_pembelian ON persediaan.uuid_barang = tbl_pembelian.uuid_barang 
					left join tbl_penjualan ON persediaan.uuid_barang = tbl_penjualan.uuid_barang  
					WHERE (persediaan.uuid_barang, persediaan.tanggal) IN (SELECT persediaan.uuid_barang, Max(persediaan.tanggal) FROM persediaan GROUP BY persediaan.uuid_barang)  
					ORDER BY persediaan.uuid_barang ASC";

			// print_r($this->db->query($sql_stock)->result());
			$Data_stock = $this->db->query($sql_stock)->result();
		}






		if (isset($uuid_gudang)) {
			// $Data_stock = $this->Tbl_pembelian_model->stock_by_gudang($uuid_gudang);
			// print_r("NON  IF GUDANG");
			$uuid_gudang = $this->input->post('uuid_gudang', TRUE);
			// $Data_stock = $this->Tbl_pembelian_model->stock_by_gudang($uuid_gudang);
			$sql_stock = "SELECT persediaan.kode_barang as kode_barang, persediaan.namabarang as nama_barang_beli,persediaan.total_10 as jumlah_sediaan, persediaan.hpp as harga_satuan_persediaan,
							tbl_pembelian.uuid_pembelian as uuid_pembelian,tbl_pembelian.uraian as barang_beli, tbl_pembelian.jumlah as jumlah_belanja, tbl_pembelian.harga_satuan as harga_satuan_beli, tbl_pembelian.tgl_po as tgl_po,tbl_pembelian.uuid_gudang as uuid_gudang, tbl_pembelian.nama_gudang as nama_gudang,tbl_pembelian.satuan as satuan,
						tbl_penjualan.nama_barang as barang_jual, tbl_penjualan.jumlah as jumlah_terjual
						FROM persediaan  
						   left join tbl_pembelian ON persediaan.uuid_barang = tbl_pembelian.uuid_barang 
						left join tbl_penjualan ON persediaan.uuid_barang = tbl_penjualan.uuid_barang  
						WHERE tbl_pembelian.uuid_gudang='$uuid_gudang' AND (persediaan.uuid_barang, persediaan.tanggal) IN (SELECT persediaan.uuid_barang, Max(persediaan.tanggal) FROM persediaan GROUP BY persediaan.uuid_barang)  
						ORDER BY persediaan.uuid_barang ASC";

			// print_r($this->db->query($sql_stock)->result());
			$Data_stock = $this->db->query($sql_stock)->result();
		} else {
			// $Data_stock = $this->Tbl_pembelian_model->stock();
			// print_r("NON  IF SEMUA");
			$sql_stock = "SELECT persediaan.kode_barang as kode_barang, persediaan.namabarang as nama_barang_beli,persediaan.total_10 as jumlah_sediaan, persediaan.hpp as harga_satuan_persediaan,
						tbl_pembelian.uuid_pembelian as uuid_pembelian,tbl_pembelian.uraian as barang_beli, tbl_pembelian.jumlah as jumlah_belanja, tbl_pembelian.harga_satuan as harga_satuan_beli, tbl_pembelian.tgl_po as tgl_po, tbl_pembelian.uuid_gudang as uuid_gudang, tbl_pembelian.nama_gudang as nama_gudang,tbl_pembelian.satuan as satuan,
					tbl_penjualan.nama_barang as barang_jual, tbl_penjualan.jumlah as jumlah_terjual
					FROM persediaan  
					left join tbl_pembelian ON persediaan.uuid_barang = tbl_pembelian.uuid_barang 
					left join tbl_penjualan ON persediaan.uuid_barang = tbl_penjualan.uuid_barang  
					WHERE (persediaan.uuid_barang, persediaan.tanggal) IN (SELECT persediaan.uuid_barang, Max(persediaan.tanggal) FROM persediaan GROUP BY persediaan.uuid_barang)  
					ORDER BY persediaan.uuid_barang ASC";

			// print_r($this->db->query($sql_stock)->result());
			$Data_stock = $this->db->query($sql_stock)->result();
		}

		$data = array(
			'action_cari_gudang' => site_url('Tbl_pembelian/stock'),
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


		$Data_supplier_tagihan = $this->db->query($sql)->result();


		$Data_konsumen_tagihan = $this->Tbl_penjualan_model->konsumen_tagihan();
		// print_r($Data_konsumen_tagihan);
		// die;

		$data = array(
			'Data_supplier_tagihan' => $Data_supplier_tagihan,
			'Data_konsumen_tagihan' => $Data_konsumen_tagihan,
		);

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/pembayaran/adminlte310_pembayaran_list', $data);
	}


	public function pembayaran_ke_supplier()
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


		$Data_supplier_tagihan = $this->db->query($sql)->result();


		$Data_konsumen_tagihan = $this->Tbl_penjualan_model->konsumen_tagihan();
		// print_r($Data_konsumen_tagihan);
		// die;

		$data = array(
			'Data_supplier_tagihan' => $Data_supplier_tagihan,
			// 'Data_konsumen_tagihan' => $Data_konsumen_tagihan,
		);

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/pembayaran/adminlte310_pembayaran_list_ke_supplier', $data);
	}



	public function pembayaran_dari_konsumen()
	{


		$sql = "SELECT SUM(tbl_penjualan_a.jumlah * tbl_penjualan_a.harga_satuan) as total_belanja, 
		tbl_penjualan_a.uuid_konsumen as uuid_konsumen, 
		tbl_penjualan_a.konsumen_nama as nama_konsumen

		-- tbl_penjualan_pembayaran_b.tgl_bayar as tgl_bayar,		
		-- tbl_penjualan_pembayaran_b.nmr_bukti_bayar as nmr_bukti_bayar,
		-- tbl_penjualan_pembayaran_b.nominal_bayar as nominal_bayar
		
		FROM tbl_penjualan tbl_penjualan_a

		-- left join   tbl_penjualan_pembayaran  tbl_penjualan_pembayaran_b ON  tbl_penjualan_pembayaran_b.uuid_konsumen = tbl_penjualan_a.uuid_konsumen
		
		-- WHERE uuid_konsumen
		GROUP BY tbl_penjualan_a.uuid_konsumen";

		$Data_konsumen_tagihan = $this->db->query($sql)->result();

		// print_r($Data_konsumen_tagihan);
		// die;

		$data = array(
			// 'Data_supplier_tagihan' => $Data_supplier_tagihan,
			'Data_konsumen_tagihan' => $Data_konsumen_tagihan,
		);
		// print_r($data);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/pembayaran/adminlte310_pembayaran_list_dari_konsumen', $data);
	}


	public function tagihan_per_uuid_konsumen($uuid_konsumen = null)
	{

		// RIWAYAT TRANSAKSI PENJUALAN
		$bayar_text = "bayar";
		$proses_text = "proses";
		// or `proses_bayar`<>'proses' or `proses_bayar`<>'$bayar_text'
		$sql = "SELECT * FROM `tbl_penjualan` WHERE `uuid_konsumen`='$uuid_konsumen' and `proses_bayar` <> '$proses_text' and `proses_bayar` <> '$bayar_text' ";
		$Data_konsumen_tagihan = $this->db->query($sql)->result();

		// RIWAYAT PEMBAYARAN
		$sql = "SELECT * FROM `tbl_penjualan_pembayaran` WHERE `uuid_konsumen`='$uuid_konsumen'";
		$Data_konsumen_pembayaran = $this->db->query($sql)->result();

		// RIWAYAT TRANSAKSI PENJUALAN
		$sql = "SELECT * FROM `tbl_penjualan` WHERE `uuid_konsumen`='$uuid_konsumen' and `proses_bayar`='proses'";
		$Data_konsumen_proses_bayar = $this->db->query($sql)->result();

		// DATA KONSUMEN
		$sql = "SELECT kode_konsumen,nama_konsumen,nmr_kontak_konsumen,alamat_konsumen FROM sys_konsumen WHERE uuid_konsumen='$uuid_konsumen'";

		$Data_konsumen = $this->db->query($sql)->row();

		$data = array(
			'button' => 'Simpan',
			'action_nominal' => site_url('tbl_pembelian/create_pembayaran_per_uuid_konsumen_by_nominal_action/' . $uuid_konsumen),
			'action_pertransaksi' => site_url('tbl_pembelian/create_pembayaran_per_uuid_konsumen_by_transaksi_action/' . $uuid_konsumen),
			'nominal_bayar_input' => set_value('nominal_bayar_input'),

			'Data_konsumen_proses_bayar' => $Data_konsumen_proses_bayar,
			'Data_konsumen_tagihan' => $Data_konsumen_tagihan,
			'Data_konsumen_pembayaran' => $Data_konsumen_pembayaran,

			'kode_konsumen' => $Data_konsumen->kode_konsumen,
			'nama_konsumen' => $Data_konsumen->nama_konsumen,
			'nmr_kontak_konsumen' => $Data_konsumen->nmr_kontak_konsumen,
			'alamat_konsumen' => $Data_konsumen->alamat_konsumen,
			'uuid_konsumen' => $uuid_konsumen,
		);


		// print_r($Data_konsumen_tagihan);

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/pembayaran/adminlte310_pembayaran_form_per_uuid_konsumen', $data);
	}


	public function create_pembayaran_per_uuid_konsumen_by_nominal_action($uuid_konsumen = null)
	{

		$data = array(
			'tgl_input' => date("Y-m-d H:i:s"),
			'tgl_bayar' => date("Y-m-d H:i:s", strtotime($this->input->post('tanggal_bayar_input', TRUE))),
			'nominal_bayar' => preg_replace("/[^0-9]/", "", $this->input->post('nominal_bayar_input', TRUE)),
			'nmr_bukti_bayar' => $this->input->post('nomor_bayar_input', TRUE),
			'uuid_konsumen' => $uuid_konsumen,
		);

		$this->Tbl_penjualan_pembayaran_model->insert($data);

		redirect(site_url('tbl_pembelian/pembayaran_dari_konsumen'));
	}

	public function create_pembayaran_per_uuid_konsumen_by_transaksi_action($uuid_konsumen = null)
	{


		$sql = "select * from `tbl_penjualan` where  `uuid_konsumen`='$uuid_konsumen' and `proses_bayar`='proses'";
		foreach ($this->db->query($sql)->result() as $list_data) {

			// print_r($list_data);
			// die;
			// Copy record dari tbl_penjualan ke tbl_penjualan_bayar
			$data = array(
				'tgl_input' => date("Y-m-d H:i:s"),
				'tgl_bayar' => date("Y-m-d H:i:s", strtotime($this->input->post('tanggal_bayar_input', TRUE))),
				// 'nominal_bayar' => preg_replace("/[^0-9]/", "", $this->input->post('nominal_bayar_input', TRUE)),
				'nmr_bukti_bayar' => $this->input->post('nomor_bayar_input_per_transaksi', TRUE),
				'uuid_konsumen' => $uuid_konsumen,
				// ALL FIELD FROM tbl_penjualan

				// 'tgl_bayar' => $list_data->tgl_bayar,
				'nominal_bayar' => $list_data->jumlah * $list_data->harga_satuan,
				// 'nmr_bukti_bayar' => $list_data->nmr_bukti_bayar,
				'uuid_penjualan' => $list_data->uuid_penjualan,
				'uuid_barang' => $list_data->uuid_barang,
				'tgl_input' => $list_data->tgl_input,
				'tgl_jual' => $list_data->tgl_jual,
				'nmrpesan' => $list_data->nmrpesan,
				'nmrkirim' => $list_data->nmrkirim,
				'konsumen_id' => $list_data->konsumen_id,
				'konsumen_nama' => $list_data->konsumen_nama,
				'kode_barang' => $list_data->kode_barang,
				'nama_barang' => $list_data->nama_barang,
				'uuid_unit' => $list_data->uuid_unit,
				'unit' => $list_data->unit,
				'satuan' => $list_data->satuan,
				'harga_satuan' => $list_data->harga_satuan,
				'jumlah' => $list_data->jumlah,
				'total_nominal' => $list_data->total_nominal,
				'umpphpsl22' => $list_data->umpphpsl22,
				'piutang' => $list_data->piutang,
				'penjualandpp' => $list_data->penjualandpp,
				'utangppn' => $list_data->utangppn,

			);

			$this->Tbl_penjualan_pembayaran_model->insert($data);


			// Refresh / update data tbl_penjualan = update proses_bayar=bayar ,tgl-input, tgl_bayar dan nomor bukti bayar
			$data = array(
				'proses_bayar' => "bayar",
				'tgl_bayar_input' => date("Y-m-d H:i:s"),
				'tgl_bayar' => date("Y-m-d H:i:s", strtotime($this->input->post('tanggal_bayar_input', TRUE))),
				'nmr_bukti_bayar' => $this->input->post('nomor_bayar_input_per_transaksi', TRUE),
			);

			$this->Tbl_penjualan_model->update($list_data->id, $data);
		}

		// $sql = "UPDATE `tbl_penjualan` SET `proses_bayar`='bayar',`tgl_bayar_input`='',`proses_bayar`='bayar' WHERE `uuid_konsumen`='$uuid_konsumen' and `proses_bayar`='proses' ";
		// $this->db->query($sql);

		redirect(site_url('tbl_pembelian/tagihan_per_uuid_konsumen/' . $uuid_konsumen));
	}

	public function pilih_uuid_penjualan_proses_bayar($uuid_konsumen = null)
	{

		print_r("pilih_uuid_penjualan_proses_bayar");
		print_r("<br/>");
		print_r($uuid_konsumen);
		print_r("<br/>");
		// print_r($this->input->post('nominal_bayar_input', TRUE));
		// print_r("<br/>");
		die;
	}

	public function pilih_proses_bayar_pertransaksi($uuid_konsumen_selected = null, $uuid_penjualan_proses_selected = null)
	{

		$sql = "UPDATE `tbl_penjualan` SET `proses_bayar`='proses' WHERE `uuid_penjualan_proses`='$uuid_penjualan_proses_selected'";
		$this->db->query($sql);
		redirect(site_url('tbl_pembelian/tagihan_per_uuid_konsumen/' . $uuid_konsumen_selected));
	}

	public function batal_proses_bayar_pertransaksi($uuid_konsumen_selected = null, $uuid_penjualan_proses_selected = null)
	{

		$sql = "UPDATE `tbl_penjualan` SET `proses_bayar`='NULL' WHERE `uuid_penjualan_proses`='$uuid_penjualan_proses_selected'";
		$this->db->query($sql);
		redirect(site_url('tbl_pembelian/tagihan_per_uuid_konsumen/' . $uuid_konsumen_selected));
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
			'uuid_gudang' => set_value('uuid_gudang'),
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


			// GET GUDANG DATA
			$GET_uuid_gudang = $this->input->post('uuid_gudang', TRUE);
			$sql_uuid_gudang = "SELECT * FROM `sys_gudang` WHERE `uuid_gudang`='$GET_uuid_gudang'";
			$get_kode_gudang = $this->db->query($sql_uuid_gudang)->row()->kode_gudang;
			$get_nama_gudang = $this->db->query($sql_uuid_gudang)->row()->nama_gudang;




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
						'uuid_gudang' => $this->input->post('uuid_gudang', TRUE),
						'nama_gudang' => $get_nama_gudang,
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

		// print_r($RESULT_per_uuid_spop);

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
		// print_r("create_add_uraian");
		// print_r("<br/>");
		// die;

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
			$get_kode_barang = $this->db->query($sql_uuid_barang)->row()->kode_barang;
			$get_nama_barang = $this->db->query($sql_uuid_barang)->row()->nama_barang;

			$jumlah_x = preg_replace("/[^0-9]/", "", $this->input->post('jumlah', TRUE));
			$harga_satuan_x = preg_replace("/[^0-9]/", "", $this->input->post('harga_satuan', TRUE));
			$TOTAL_X = $jumlah_x * $harga_satuan_x;

			// GET GUDANG DATA
			$GET_uuid_gudang = $this->input->post('uuid_gudang', TRUE);
			$sql_uuid_gudang = "SELECT * FROM `sys_gudang` WHERE `uuid_gudang`='$GET_uuid_gudang'";
			$get_kode_gudang = $this->db->query($sql_uuid_gudang)->row()->kode_gudang;
			$get_nama_gudang = $this->db->query($sql_uuid_gudang)->row()->nama_gudang;



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
				'kode_barang' => $get_kode_barang,
				'uraian' => $get_nama_barang,

				'jumlah' => $jumlah_x,
				'satuan' => $this->input->post('satuan', TRUE),
				'harga_satuan' => $harga_satuan_x,

				'uuid_konsumen' => $this->input->post('uuid_konsumen', TRUE),
				'konsumen' => $get_nama_konsumen,

				'harga_total' => $TOTAL_X,
				'uuid_gudang' => $this->input->post('uuid_gudang', TRUE),
				'nama_gudang' => $get_nama_gudang,
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
			// print_r($this->input->post('uuid_supplier', TRUE));
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
			$get_kode_barang = $this->db->query($sql_uuid_barang)->row()->kode_barang;
			$get_nama_barang = $this->db->query($sql_uuid_barang)->row()->nama_barang;

			// print_r($GET_uuid_barang);
			// print_r("<br/>");
			$jumlah_x = preg_replace("/[^0-9]/", "", $this->input->post('jumlah', TRUE));
			$harga_satuan_x = preg_replace("/[^0-9]/", "", $this->input->post('harga_satuan', TRUE));
			$TOTAL_X = $jumlah_x * $harga_satuan_x;


			// GET GUDANG DATA
			$GET_uuid_gudang = $this->input->post('uuid_gudang', TRUE);
			$sql_uuid_gudang = "SELECT * FROM `sys_gudang` WHERE `uuid_gudang`='$GET_uuid_gudang'";
			$get_kode_gudang = $this->db->query($sql_uuid_gudang)->row()->kode_gudang;
			$get_nama_gudang = $this->db->query($sql_uuid_gudang)->row()->nama_gudang;



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
				'kode_barang' => $get_kode_barang,
				'uraian' => $get_nama_barang,

				'jumlah' => $this->input->post('jumlah', TRUE),
				'satuan' => $this->input->post('satuan', TRUE),
				'harga_satuan' => $this->input->post('harga_satuan', TRUE),

				'uuid_konsumen' => $this->input->post('uuid_konsumen', TRUE),
				'konsumen' => $get_nama_konsumen,

				'harga_total' => $TOTAL_X,
				'statuslu' => $this->input->post('statuslu', TRUE),
				'kas_bank' => $this->input->post('kas_bank', TRUE),
				'uuid_gudang' => $this->input->post('uuid_gudang', TRUE),
				'nama_gudang' => $get_nama_gudang,
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

	public function update_per_spop($uuid_spop)
	{


		$data_per_uuidspop = $this->Tbl_pembelian_model->get_by_uuid_spop($uuid_spop);
		// print_r($data_per_uuidspop->spop);
		// die;

		$Tbl_pembelian = $this->Tbl_pembelian_model->get_by_spop($data_per_uuidspop->spop);
		$start = 0;
		$data = array(

			'Tbl_pembelian_data' => $Tbl_pembelian,
			'start' => $start,
		);

		// print_r($data);
		// 		print_r("<br/>");
		// 		print_r("<br/>");
		// die;

		// $this->template->load('anekadharma/adminlte310_anekadharma', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_list_per_spop', $data);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_list_per_spop', $data);
	}


	public function update_per_id($uuid_pembelian)
	{


		// $data_per_uuidspop = $this->Tbl_pembelian_model->get_by_id($uuid_spop);
		// print_r($data_per_uuidspop->spop);
		// die;

		// print_r($uuid_pembelian);
		// print_r("<br/>");

		$get_id_pembelian = $this->Tbl_pembelian_model->get_by_uuid_pembelian($uuid_pembelian);
		// print_r($get_id_pembelian->id);
		// print_r("<br/>");

		$Tbl_pembelian = $this->Tbl_pembelian_model->get_by_id($get_id_pembelian->id);
		// print_r($Tbl_pembelian);
		// print_r("<br/>");

		$start = 0;
		$data = array(
			'button' => 'Update',
			'action' => site_url('tbl_pembelian/update_action'),
			'Tbl_pembelian_data' => $Tbl_pembelian,
			'start' => $start,
			'id' => $get_id_pembelian->id,
			'tgl_po' => date($Tbl_pembelian->tgl_po),
			'uuid_supplier' => $Tbl_pembelian->uuid_supplier,
			'supplier_nama' => $Tbl_pembelian->supplier_nama,
			'statuslu' => $Tbl_pembelian->statuslu,
			'kas_bank' => $Tbl_pembelian->kas_bank,
			'spop' => $Tbl_pembelian->spop,
			'nmrfakturkwitansi' => $Tbl_pembelian->nmrfakturkwitansi,
			'uuid_gudang' => $Tbl_pembelian->uuid_gudang,
			'nama_gudang' => $Tbl_pembelian->nama_gudang,

			'uraian' => $Tbl_pembelian->uraian,
			'uuid_barang' => $Tbl_pembelian->uuid_barang,
			'kode_barang' => $Tbl_pembelian->kode_barang,

			'jumlah' => $Tbl_pembelian->jumlah,
			'satuan' => $Tbl_pembelian->satuan,
			'harga_satuan' => $Tbl_pembelian->harga_satuan,

		);
		// print_r($data);



		// $this->template->load('anekadharma/adminlte310_anekadharma', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_list_per_spop', $data);

		// $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_list_per_spop', $data);

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_form_per_id', $data);
	}



	public function update_action()
	{

		// print_r($this->input->post('id', TRUE));
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");

		$this->_rules();

		if ($this->form_validation->run() == FALSE) {
			$this->update($this->input->post('id', TRUE));
		} else {

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

			// // GET KONSUMEN DATA
			// $GET_uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);

			// $sql_uuid_konsumen = "SELECT * FROM `sys_unit` WHERE `uuid_unit`='$GET_uuid_konsumen'";
			// $get_kode_konsumen = $this->db->query($sql_uuid_konsumen)->row()->kode_unit;
			// $get_nama_konsumen = $this->db->query($sql_uuid_konsumen)->row()->nama_unit;


			// $this->db->where('uuid_konsumen', $this->input->post('uuid_konsumen', TRUE));
			// //$this->db->where('password',  $test);
			// $sys_konsumen_data = $this->db->get('sys_konsumen');

			// if ($sys_konsumen_data->num_rows() > 0) {
			// 	// Konsumen dari sys_konsumen
			// 	$get_uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
			// 	$sql_uuid_konsumen = "SELECT * FROM `sys_konsumen` WHERE `uuid_konsumen`='$get_uuid_konsumen'";
			// 	// $get_kode_konsumen = $this->db->query($sql_uuid_konsumen)->row()->kode_konsumen;
			// 	$get_nama_konsumen = $this->db->query($sql_uuid_konsumen)->row()->nama_konsumen;
			// } else {
			// 	// Konsumen dari unit

			// 	// $uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
			// 	// $data_konsumen = $this->Sys_unit_model->get_by_uuid_unit($uuid_konsumen);
			// 	// $data_nama_konsumen = $data_konsumen->nama_unit;


			// 	$get_uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
			// 	$sql_uuid_konsumen = "SELECT * FROM `sys_unit` WHERE `uuid_unit`='$get_uuid_konsumen'";
			// 	// $get_kode_konsumen = $this->db->query($sql_uuid_konsumen)->row()->kode_konsumen;
			// 	$get_nama_konsumen = $this->db->query($sql_uuid_konsumen)->row()->nama_unit;
			// }

			// print_r($get_nama_konsumen);
			// die;










			// GET BARANG DATA
			$GET_uuid_barang = $this->input->post('uuid_barang', TRUE);
			$sql_uuid_barang = "SELECT * FROM `sys_nama_barang` WHERE `uuid_barang`='$GET_uuid_barang'";
			$get_kode_barang = $this->db->query($sql_uuid_barang)->row()->kode_barang;
			$get_nama_barang = $this->db->query($sql_uuid_barang)->row()->nama_barang;

			// print_r($GET_uuid_barang);
			// print_r("<br/>");
			$jumlah_x = preg_replace("/[^0-9]/", "", $this->input->post('jumlah', TRUE));
			$harga_satuan_x = preg_replace("/[^0-9]/", "", $this->input->post('harga_satuan', TRUE));
			$TOTAL_X = $jumlah_x * $harga_satuan_x;


			// GET GUDANG DATA
			$GET_uuid_gudang = $this->input->post('uuid_gudang', TRUE);
			$sql_uuid_gudang = "SELECT * FROM `sys_gudang` WHERE `uuid_gudang`='$GET_uuid_gudang'";
			$get_kode_gudang = $this->db->query($sql_uuid_gudang)->row()->kode_gudang;
			$get_nama_gudang = $this->db->query($sql_uuid_gudang)->row()->nama_gudang;




			// $data = array(
			// 	'tgl_po' => $this->input->post('tgl_po', TRUE),
			// 	'nmrsj' => $this->input->post('nmrsj', TRUE),
			// 	'nmrfakturkwitansi' => $this->input->post('nmrfakturkwitansi', TRUE),
			// 	'nmrbpb' => $this->input->post('nmrbpb', TRUE),
			// 	'spop' => $this->input->post('spop', TRUE),
			// 	'supplier_kode' => $this->input->post('supplier_kode', TRUE),
			// 	'supplier_nama' => $this->input->post('supplier_nama', TRUE),
			// 	'uraian' => $this->input->post('uraian', TRUE),
			// 	'jumlah' => $this->input->post('jumlah', TRUE),
			// 	'satuan' => $this->input->post('satuan', TRUE),
			// 	'konsumen' => $this->input->post('konsumen', TRUE),
			// 	'harga_satuan' => $this->input->post('harga_satuan', TRUE),
			// 	'harga_total' => $this->input->post('harga_total', TRUE),
			// 	'statuslu' => $this->input->post('statuslu', TRUE),
			// 	'kas_bank' => $this->input->post('kas_bank', TRUE),
			// 	'tgl_bayar' => $this->input->post('tgl_bayar', TRUE),
			// 	'id_usr' => $this->input->post('id_usr', TRUE),
			// );



			$data = array(
				// 'date_input' => date("Y-m-d H:i:s"),

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
				'kode_barang' => $get_kode_barang,
				'uraian' => $get_nama_barang,

				'jumlah' => $this->input->post('jumlah', TRUE),
				'satuan' => $this->input->post('satuan', TRUE),
				'harga_satuan' => $this->input->post('harga_satuan', TRUE),

				'uuid_konsumen' => $this->input->post('uuid_konsumen', TRUE),
				// 'konsumen' => $get_nama_konsumen,

				'harga_total' => $TOTAL_X,
				'statuslu' => $this->input->post('statuslu', TRUE),
				'kas_bank' => $this->input->post('kas_bank', TRUE),
				'uuid_gudang' => $this->input->post('uuid_gudang', TRUE),
				'nama_gudang' => $get_nama_gudang,
				// 'id_usr' => 1,
			);



			// print_r($data);
			// die;
			$this->Tbl_pembelian_model->update($this->input->post('id', TRUE), $data);
			$this->session->set_flashdata('message', 'Update Record Success');
			redirect(site_url('tbl_pembelian'));
		}
	}


	public function update_status_per_spop($uuid_spop = null)
	{

		$data_per_uuidspop = $this->Tbl_pembelian_model->get_by_uuid_spop($uuid_spop);

		// $Tbl_pembelian = $this->Tbl_pembelian_model->get_by_spop($spop);
		$Tbl_pembelian = $this->Tbl_pembelian_model->get_by_spop($data_per_uuidspop->spop);

		// SELECT `status_spop` FROM `tbl_pembelian` WHERE `uuid_spop`="53d056417ed111ef95300021ccc9061e";



		$start = 0;
		$data = array(
			'Tbl_pembelian_data' => $Tbl_pembelian,
			'spop' => $data_per_uuidspop->spop,
			'action' => site_url('tbl_pembelian/update_status_per_spop_action/' . $uuid_spop),
			'button' => 'Simpan',
			'start' => $start,
		);
		// print_r($data);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_list_per_spop_status', $data);
	}



	public function update_status_per_spop_action($uuid_spop)
	{

		$data_status = $this->Sys_status_transaksi_model->get_by_uuid_status_transaksi($this->input->post('uuid_status_transaksi', TRUE));

		// print_r($data_status->status);

		// print_r("update_status_per_spop_action");
		// print_r("<br/>");
		// print_r($uuid_spop);
		// print_r("<br/>");
		// print_r($this->input->post('uuid_status_transaksi', TRUE));
		// print_r("<br/>");
		// die;

		// $this->_rules();

		// if ($this->form_validation->run() == FALSE) {
		// 	$this->update($this->input->post('id', TRUE));
		// } else {
		$data = array(
			'status_spop' => $data_status->status,
		);

		$this->Tbl_pembelian_model->update_status_per_spop($uuid_spop, $data);
		$this->session->set_flashdata('message', 'Update Record Success');
		redirect(site_url('tbl_pembelian'));
		// }
	}



	public function delete_per_spop($uuid_spop)
	{
		// $row = $this->Tbl_pembelian_model->get_by_id($uuid_spop);
		$data_per_uuidspop = $this->Tbl_pembelian_model->get_by_uuid_spop($uuid_spop);

		if ($data_per_uuidspop) {
			$this->Tbl_pembelian_model->delete($uuid_spop);
			$this->session->set_flashdata('message', 'Delete Record Success');
			redirect(site_url('tbl_pembelian'));
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
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

	public function delete_by_uuid_pembelian($uuid_pembelian)
	{


		$get_id_pembelian = $this->Tbl_pembelian_model->get_by_uuid_pembelian($uuid_pembelian);

		$row = $this->Tbl_pembelian_model->get_by_id($get_id_pembelian->id);

		if ($row) {
			$this->Tbl_pembelian_model->delete($get_id_pembelian->id);
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

	public function pecah_satuan($uuid_pembelian = null)
	{

		$Data_Barang = $this->Tbl_pembelian_model->get_by_uuid_pembelian($uuid_pembelian);

		$data = array(
			'Data_Barang' => $Data_Barang,
			'action' => site_url('tbl_pembelian/pecah_satuan_action/' . $uuid_pembelian),
			'button' => 'Simpan',
			'uuid_pembelian' => $uuid_pembelian,
			'uuid_barang' => $Data_Barang->uuid_barang,
			'tgl_po' => $Data_Barang->tgl_po,
			'uuid_spop' => $Data_Barang->uuid_spop,
			'kode_barang' => $Data_Barang->kode_barang,
			'nama_barang' => $Data_Barang->uraian,
			'jumlah' => $Data_Barang->jumlah,
			'satuan' => $Data_Barang->satuan,
			'uuid_gudang' => $Data_Barang->uuid_gudang,
			'nama_gudang' => $Data_Barang->nama_gudang,
			'harga_satuan' => $Data_Barang->harga_satuan,
			'uuid_barang' => $Data_Barang->uuid_barang,
		);

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_form_pecah_satuan_barang', $data);
	}

	public function pecah_satuan_action($uuid_pembelian)
	{


		$jumlah_proses = preg_replace("/[^0-9]/", "", $this->input->post('jumlah_barang_baru', TRUE));
		$harga_satuan_proses = preg_replace("/[^0-9]/", "", $this->input->post('harga_satuan_barang_baru', TRUE));

		$Data_Barang = $this->Tbl_pembelian_model->get_by_uuid_pembelian($uuid_pembelian);

		$data = array(

			'proses_input' => date("Y-m-d H:i:s"),
			'uuid_pembelian' => $Data_Barang->uuid_pembelian,
			'uuid_barang' => $Data_Barang->uuid_barang,
			'tgl_po' => $Data_Barang->tgl_po,
			'nmrsj' => $Data_Barang->nmrsj,
			'nmrfakturkwitansi' => $Data_Barang->nmrfakturkwitansi,
			'nmrbpb' => $Data_Barang->nmrbpb,
			'uuid_spop' => $Data_Barang->uuid_spop,
			'spop' => $Data_Barang->spop,
			'status_spop' => $Data_Barang->status_spop,
			'uuid_supplier' => $Data_Barang->uuid_supplier,
			'supplier_kode' => $Data_Barang->supplier_kode,
			'supplier_nama' => $Data_Barang->supplier_nama,
			'kode_barang' => $Data_Barang->kode_barang,
			'uraian' => $Data_Barang->uraian,
			'jumlah' => $Data_Barang->jumlah,
			'satuan' => $Data_Barang->satuan,
			'uuid_konsumen' => $Data_Barang->uuid_konsumen,
			'konsumen' => $Data_Barang->konsumen,
			'uuid_gudang' => $Data_Barang->uuid_gudang,
			'nama_gudang' => $Data_Barang->nama_gudang,
			'harga_satuan' => $Data_Barang->harga_satuan,
			'uuid_gudang_baru' => $this->input->post('uuid_gudang', TRUE),
			'kode_barang_baru' => $this->input->post('kode_barang_baru', TRUE),
			'nama_barang_baru' => $this->input->post('nama_barang_baru', TRUE),
			'jumlah_barang_baru' => preg_replace("/[^0-9]/", "", $this->input->post('jumlah_barang_baru', TRUE)),
			'satuan_barang_baru' => $this->input->post('satuan_barang_baru', TRUE),
			'harga_satuan_barang_baru' => preg_replace("/[^0-9]/", "", $this->input->post('harga_satuan_barang_baru', TRUE)),

		);

		// print_r($data);
		// die;

		$this->Tbl_pembelian_pecah_satuan_model->insert($data);

		// GET GUDANG DATA
		$GET_uuid_gudang = $this->input->post('uuid_gudang', TRUE);
		$sql_uuid_gudang = "SELECT * FROM `sys_gudang` WHERE `uuid_gudang`='$GET_uuid_gudang'";
		$get_kode_gudang = $this->db->query($sql_uuid_gudang)->row()->kode_gudang;
		$get_nama_gudang = $this->db->query($sql_uuid_gudang)->row()->nama_gudang;


		// Proses simpan ke tbl_penjualan dari barang asli sesuai jumlah yang di pecah : ditambah info status
		$data = array(
			'tgl_input' => date("Y-m-d H:i:s"),
			'tgl_jual' => date("Y-m-d H:i:s"),
			// 'uuid_penjualan' => "new",
			'nmrpesan' => "pecah satuan",
			'nmrkirim' => "pecah satuan",
			'uuid_konsumen' => $GET_uuid_gudang,
			'konsumen_nama' => $get_nama_gudang,
			'uuid_barang' => $Data_Barang->uuid_barang,
			'kode_barang' => $Data_Barang->kode_barang,
			'nama_barang' => $Data_Barang->uraian,
			// 'uuid_unit' => "pecah satuan",
			'unit' => "pecah satuan",
			'jumlah' => preg_replace("/[^0-9]/", "", $this->input->post('jumlah_barang_baru', TRUE)),
			'satuan' => $this->input->post('satuan_barang_baru', TRUE),
			'harga_satuan' => preg_replace("/[^0-9]/", "", $this->input->post('harga_satuan_barang_baru', TRUE)),
			'total_nominal' =>  $jumlah_proses * $harga_satuan_proses,
			'id_usr' => 1,
		);

		$this->Tbl_penjualan_model->insert_new($data);


		// Insert nama barang baru ke sys_nama_barang dan mendapatkan uuid_barang
		$data = array(
			// 'uuid_barang' => $this->input->post('uuid_barang',TRUE),
			'kode_barang' => $this->input->post('kode_barang_baru', TRUE),
			'nama_barang' => $this->input->post('nama_barang_baru', TRUE),
			'satuan' => $this->input->post('satuan_barang_baru', TRUE),
			// 'keterangan' => $this->input->post('keterangan',TRUE),
		);

		$uuid_barang_baru = $this->Sys_nama_barang_model->insert_dari_pecah_satuan($data);



		// Proses simpan ke tbl_pembelian menjadi barang baru



		$data = array(
			'date_input' => date("Y-m-d H:i:s"),

			'tgl_po' => date("Y-m-d H:i:s"),

			// 'nmrsj' => $this->input->post('nmrsj', TRUE),

			// 'nmrfakturkwitansi' => "update satuan",

			// 'nmrbpb' => $this->input->post('nmrbpb', TRUE),

			'spop' => "update satuan",

			// 'supplier_kode' => $get_kode_supplier,
			'uuid_supplier' => $Data_Barang->uuid_gudang,
			'supplier_nama' => $Data_Barang->nama_gudang,

			'nmrfakturkwitansi' => "update satuan",

			'uuid_barang' => $uuid_barang_baru,
			'kode_barang' => $this->input->post('kode_barang_baru', TRUE),
			'uraian' => $this->input->post('nama_barang_baru', TRUE),

			'jumlah' => preg_replace("/[^0-9]/", "", $this->input->post('jumlah_barang_baru', TRUE)),
			'satuan' => $this->input->post('satuan_barang_baru', TRUE),
			'harga_satuan' => preg_replace("/[^0-9]/", "", $this->input->post('harga_satuan_barang_baru', TRUE)),

			'uuid_konsumen' => $this->input->post('uuid_gudang', TRUE),
			'konsumen' => $get_nama_gudang,

			'harga_total' =>  $jumlah_proses * $harga_satuan_proses,
			// 'statuslu' => $this->input->post('statuslu', TRUE),
			// 'kas_bank' => $this->input->post('kas_bank', TRUE),
			'uuid_gudang' => $this->input->post('uuid_gudang', TRUE),
			'nama_gudang' => $get_nama_gudang,
			// 'id_usr' => 1,
		);


		$this->Tbl_pembelian_model->insert_spop($data);

		// die;
		redirect(site_url('tbl_pembelian/stock'));
	}



	public function jurnal_pembelian()
	{
		$Tbl_pembelian = $this->Tbl_pembelian_model->get_all();
		$start = 0;
		$data = array(
			'Tbl_pembelian_data' => $Tbl_pembelian,
			'start' => $start,
			'action_by_bulan' => site_url('tbl_pembelian/jurnal_pembelian_per_bulan'),
		);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_jurnal_pembelian', $data);
	}


	public function jurnal_pembelian_per_bulan()
	{
		// print_r("jurnal_pembelian_per_bulan");
		// print_r("<br/>");
		// print_r($this->input->post('bulan_pembelian', TRUE));
		// print_r("<br/>");
		// print_r(date("Y-m-1", strtotime($this->input->post('bulan_pembelian', TRUE))));
		// print_r("<br/>");
		// print_r(date("Y-m-t", strtotime($this->input->post('bulan_pembelian', TRUE))));


		// print_r("<br/>");

		$from_date = date("Y-m-1", strtotime($this->input->post('bulan_pembelian', TRUE)));
		$to_date = date("Y-m-t", strtotime($this->input->post('bulan_pembelian', TRUE)));

		$sql = "SELECT * FROM tbl_pembelian WHERE tgl_po >= '" . $from_date . "' AND tgl_po <= '" . $to_date . "' ORDER by id DESC";

		// $this->db->query($sql)->result();
		// print_r($this->db->query($sql)->result());

		// die;

		// $this->input->post('bulan_pembelian', TRUE)

		$Tbl_pembelian = $this->db->query($sql)->result();
		$start = 0;
		$data = array(
			'Tbl_pembelian_data' => $Tbl_pembelian,
			'start' => $start,
			'action_by_bulan' => site_url('tbl_pembelian/jurnal_pembelian_per_bulan'),
		);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_jurnal_pembelian', $data);
	}


	public function kas_kecil()
	{


		$sql = "SELECT * FROM tbl_pembelian where kas_bank='kas' union ALL SELECT * FROM tbl_pembelian_kas_kecil ";

		// 		print_r($this->db->query($sql)->result());
		// die;



		$Tbl_pembelian = $this->db->query($sql)->result();
		$start = 0;
		$data = array(
			'bulan_data' => date("Y-m-d"),
			'Tbl_pembelian_data' => $Tbl_pembelian,
			'start' => $start,
			'action_by_bulan' => site_url('tbl_pembelian/kas_kecil_per_bulan'),
		);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_kas_kecil', $data);
	}


	public function kas_kecil_per_bulan()
	{
		// print_r("jurnal_pembelian_per_bulan");
		// print_r("<br/>");
		// print_r($this->input->post('bulan_pembelian', TRUE));
		// print_r("<br/>");
		// print_r(date("Y-m-1", strtotime($this->input->post('bulan_pembelian', TRUE))));
		// print_r("<br/>");
		// print_r(date("Y-m-t", strtotime($this->input->post('bulan_pembelian', TRUE))));


		// print_r("<br/>");

		$from_date = date("Y-m-1", strtotime($this->input->post('bulan_pembelian', TRUE)));
		$to_date = date("Y-m-t", strtotime($this->input->post('bulan_pembelian', TRUE)));

		// $sql = "SELECT * FROM tbl_pembelian WHERE tgl_po >= '" . $from_date . "' AND tgl_po <= '" . $to_date . "' ORDER by id DESC";

		$sql = "SELECT * FROM tbl_pembelian where kas_bank='kas' and tgl_po >= '" . $from_date . "' AND tgl_po <= '" . $to_date . "' UNION ALL SELECT * FROM tbl_pembelian_kas_kecil where  tgl_po >= '" . $from_date . "' AND tgl_po <= '" . $to_date . "'";

		$Tbl_pembelian = $this->db->query($sql)->result();
		$start = 0;
		$data = array(
			'bulan_data' => $this->input->post('bulan_pembelian', TRUE),
			'Tbl_pembelian_data' => $Tbl_pembelian,
			'start' => $start,
			'action_by_bulan' => site_url('tbl_pembelian/kas_kecil_per_bulan'),
		);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_kas_kecil', $data);
	}

	public function buku_kas()
	{

		$from_date = date("Y-m-1", strtotime(date("Y-m-d")));
		$to_date = date("Y-m-t", strtotime(date("Y-m-d")));


		$sql = "SELECT tgl_po as tgl_transaksi, uraian as nama_barang,proses_transaksi,kode_akun,jumlah,harga_satuan FROM tbl_pembelian where tgl_po >= '" . $from_date . "' AND tgl_po <= '" . $to_date . "' UNION ALL SELECT tgl_jual as tgl_transaksi, nama_barang as nama_barang,proses_transaksi,kode_akun,jumlah,harga_satuan FROM tbl_penjualan  where  tgl_jual >= '" . $from_date . "' AND tgl_jual <= '" . $to_date . "'  order by tgl_transaksi";

		$buku_kas_data = $this->db->query($sql)->result();

		// print_r($buku_kas_data);
		// die;

		$data = array(
			'buku_kas_data' => $buku_kas_data,
			'action_by_bulan' => site_url('tbl_pembelian/buku_kas_per_bulan'),
		);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/bukukas/adminlte310_buku_kas_list', $data);
	}
	public function buku_kas_per_bulan()
	{

		$from_date = date("Y-m-1", strtotime($this->input->post('bulan_pembelian', TRUE)));
		$to_date = date("Y-m-t", strtotime($this->input->post('bulan_pembelian', TRUE)));



		$sql = "SELECT tgl_po as tgl_transaksi, uraian as nama_barang,proses_transaksi FROM tbl_pembelian where tgl_po >= '" . $from_date . "' AND tgl_po <= '" . $to_date . "' UNION ALL SELECT tgl_jual as tgl_transaksi, nama_barang as nama_barang,proses_transaksi FROM tbl_penjualan  where  tgl_jual >= '" . $from_date . "' AND tgl_jual <= '" . $to_date . "'  order by tgl_transaksi";

		$buku_kas_data = $this->db->query($sql)->result();

		// print_r($buku_kas_data);
		// die;

		$data = array(
			'buku_kas_data' => $buku_kas_data,
			'action_by_bulan' => site_url('tbl_pembelian/buku_kas_per_bulan'),
		);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/bukukas/adminlte310_buku_kas_list', $data);
	}
}

/* End of file Tbl_pembelian.php */
/* Location: ./application/controllers/Tbl_pembelian.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-09-03 14:57:23 */
/* http://harviacode.com */