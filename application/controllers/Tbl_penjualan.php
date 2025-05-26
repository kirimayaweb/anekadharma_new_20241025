<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Tbl_penjualan extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_login();
		$this->load->model(array('Tbl_penjualan_model', 'Tbl_pembelian_model', 'Sys_konsumen_model', 'Sys_unit_model', 'Sys_nama_barang_model', 'Persediaan_model', 'Buku_besar_model'));
		$this->load->library('form_validation');
		$this->load->library('form_validation');
		$this->load->library('datatables');
		$this->load->library('Pdf');
		$this->load->helper(array('nominal'));
		// $this->load->helper('number');
	}






	public function index()
	{



		$Get_date_awal = date("Y-m-1 00:00:00");
		// print_r($Get_date_awal);
		// print_r("<br/>");


		$Get_date_akhir = date("Y-m-t 00:00:00"); // TANGGAL AKHIR BULAN -t
		// print_r($Get_date_akhir);
		// print_r("<br/>");

		// die;



		$sql = "SELECT * FROM `tbl_penjualan` WHERE `tgl_jual` between '$Get_date_awal' and '$Get_date_akhir' ORDER BY `tgl_jual`,`nmrkirim`,`id`";
		// print_r($this->db->query($sql)->result());
		// die;

		// $Tbl_pembelian = $this->Tbl_pembelian_model->get_all();
		// $Tbl_pembelian = $this->db->query($sql)->result();


		// $Tbl_penjualan = $this->Tbl_penjualan_model->get_all_group_by_tgl_jual_nmrpesan_nmr_kirim();
		// $start = 0;
		// print_r($Tbl_penjualan);
		// print_r("<br/>");
		// print_r("<br/>");

		$data = array(
			// 'Tbl_penjualan_data' => $Tbl_penjualan,
			'Tbl_penjualan_data' => $this->db->query($sql)->result(),
			// 'q' => $q,
			// 'pagination' => $this->pagination->create_links(),
			// 'total_rows' => $config['total_rows'],
			// 'start' => $start,
			'date_awal' => $Get_date_awal,
			'date_akhir' => $Get_date_akhir,
		);


		// $this->load->view('anekadharma/tbl_penjualan/tbl_penjualan_list', $data);		
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_list', $data);
	}

	public function cari_between_date()
	{

		if (date("Y", strtotime($this->input->post('tgl_awal', TRUE))) < 2020) {
			// $Get_date_awal = date("Y-m-d 00:00:00");
			$Get_date_awal = date('Y-m-d', strtotime('-1 day'));
		} else {
			$Get_date_awal = date("Y-m-d 23:59:59", strtotime($this->input->post('tgl_awal', TRUE)));
		}

		// $Get_date_awal_proses =  date('Y-m-d', strtotime($Get_date_awal. ' - 1 day'));


		if (date("Y", strtotime($this->input->post('tgl_akhir', TRUE))) < 2020) {
			$Get_date_akhir = date("Y-m-d 00:00:00");
		} else {
			$Get_date_akhir = date("Y-m-d 23:59:59", strtotime($this->input->post('tgl_akhir', TRUE)));
		}

		$sql = "SELECT * FROM `tbl_penjualan` WHERE `tgl_jual` between '$Get_date_awal' and '$Get_date_akhir' ORDER BY `tgl_jual`,`nmrkirim`,`id`";

		$data = array(
			// 'Tbl_penjualan_data' => $Tbl_penjualan,
			'Tbl_penjualan_data' => $this->db->query($sql)->result(),
			// 'q' => $q,
			// 'pagination' => $this->pagination->create_links(),
			// 'total_rows' => $config['total_rows'],
			// 'start' => $start,
			'date_awal' => $Get_date_awal,
			'date_akhir' => $Get_date_akhir,
		);


		// $this->load->view('anekadharma/tbl_penjualan/tbl_penjualan_list', $data);		
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_list', $data);
	}


	public function RekapPenjualanPerBarang()
	{
		// $sql_stock = "SELECT persediaan.id as id_persediaan_barang, persediaan.uuid_barang as uuid_barang, persediaan.kode_barang as kode_barang, persediaan.namabarang as nama_barang_beli,persediaan.total_10 as jumlah_sediaan,  persediaan.hpp as harga_satuan_persediaan,  persediaan.satuan as satuan,
		// 	-- 	tbl_pembelian.uuid_pembelian as uuid_pembelian,tbl_pembelian.uraian as barang_beli, tbl_pembelian.jumlah as jumlah_belanja, tbl_pembelian.harga_satuan as harga_satuan_beli,  tbl_pembelian.tgl_po as tgl_po,tbl_pembelian.uuid_gudang as uuid_gudang, tbl_pembelian.nama_gudang as nama_gudang,  tbl_pembelian.satuan as satuan,
		// 	-- tbl_penjualan.nama_barang as barang_jual, tbl_penjualan.jumlah as jumlah_terjual
		// 	persediaan.penjualan as penjualan
		// 	FROM persediaan  
		// 	-- left join tbl_pembelian ON persediaan.uuid_barang = tbl_pembelian.uuid_barang 
		// 	-- left join tbl_penjualan ON persediaan.uuid_barang = tbl_penjualan.uuid_barang  
		// 	Full Outer Join tbl_penjualan ON persediaan.uuid_barang = tbl_penjualan.uuid_barang  
		// 	-- WHERE (persediaan.uuid_barang, persediaan.tanggal) IN (SELECT persediaan.uuid_barang, Max(persediaan.tanggal) FROM persediaan 

		// 	-- GROUP BY persediaan.uuid_barang)  

		// 	ORDER BY persediaan.namabarang ASC";


		$sql_persediaan = "SELECT persediaan.id as id,
								persediaan.uuid_persediaan as uuid_persediaan,
								persediaan.namabarang as namabarang_persediaan, 
								persediaan.sa as saldo_awal_persediaan, 
								persediaan.spop as spop, 

		 						tbl_penjualan.id_persediaan_barang as id_persediaan_barang,
		 						tbl_penjualan.tgl_jual as tgl_jual_penjualan,
		 						tbl_penjualan.nmrkirim as nmrkirim_penjualan,
		 						tbl_penjualan.uuid_konsumen as uuid_konsumen_penjualan,
		 						tbl_penjualan.konsumen_nama as konsumen_nama_penjualan,
		 						tbl_penjualan.nama_barang as nama_barang_penjualan,
		 						tbl_penjualan.jumlah as jumlah_penjualan,
		 						tbl_penjualan.harga_satuan as harga_satuan_penjualan,
		 						tbl_penjualan.uuid_persediaan as uuid_persediaan_penjualan
							FROM persediaan
							right JOIN  tbl_penjualan ON persediaan.id= tbl_penjualan.id_persediaan_barang
							-- group by persediaan.namabarang, tbl_penjualan.nama_barang
							ORDER BY tbl_penjualan.tgl_jual DESC, tbl_penjualan.nama_barang ASC, tbl_penjualan.nmrkirim DESC;";

		// print_r($this->db->query($sql_persediaan)->num_rows());
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");

		$data_penjualan_per_barang = $this->db->query($sql_persediaan)->result();
		// print_r($this->db->query($sql_persediaan)->result());
		// die;


		$start = 0;
		// print_r($Tbl_pembelian);
		// print_r("<br/>");
		// print_r("<br/>");

		$data = array(
			'Tbl_penjualan_data' => $this->db->query($sql_persediaan)->result(),
			// 'q' => $q,
			// 'pagination' => $this->pagination->create_links(),
			// 'total_rows' => $config['total_rows'],
			'start' => $start,

			// 'action_cari_konsumen' => site_url('tbl_penjualan/rekap'),
			// 'data_selection' => $data_selection,
			// 'nama_konsumen_selection' => $nama_konsumen_selection,
		);


		// $this->load->view('anekadharma/tbl_penjualan/tbl_penjualan_list', $data);		
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_list_rekap', $data);
	}

	public function RekapPenjualanPerKonsumen()
	{

		$sql_persediaan = "SELECT 
		-- persediaan.id as id,
		-- 						persediaan.uuid_persediaan as uuid_persediaan,
		-- 						persediaan.namabarang as namabarang_persediaan, 
		-- 						persediaan.sa as saldo_awal_persediaan, 
		-- 						persediaan.spop as spop, 

		 						tbl_penjualan.id_persediaan_barang as id_persediaan_barang,
		 						tbl_penjualan.tgl_jual as tgl_jual_penjualan,
		 						tbl_penjualan.nmrkirim as nmrkirim_penjualan,
		 						tbl_penjualan.uuid_konsumen as uuid_konsumen_penjualan,
		 						tbl_penjualan.konsumen_nama as konsumen_nama_penjualan,
		 						tbl_penjualan.nama_barang as nama_barang_penjualan,
		 						tbl_penjualan.jumlah as jumlah_penjualan,
		 						tbl_penjualan.harga_satuan as harga_satuan_penjualan,
		 						tbl_penjualan.uuid_persediaan as uuid_persediaan_penjualan
							FROM tbl_penjualan
							-- right JOIN  tbl_penjualan ON persediaan.id= tbl_penjualan.id_persediaan_barang
							GROUP BY tbl_penjualan.uuid_konsumen
							ORDER BY tbl_penjualan.konsumen_nama ASC;";

		// $data_penjualan_per_barang = $this->db->query($sql_persediaan)->result();

		$data = array(
			'Tbl_penjualan_data' => $this->db->query($sql_persediaan)->result(),
		);

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_list_rekap_per_konsumen', $data);
	}

	public function RekapData($field_rekap = null)
	{
		// $field_rekap="konsumen_nama";
		// print_r($field_rekap);



		if ($field_rekap == "unit") {
			$sql_penjualan = "SELECT * FROM tbl_penjualan ORDER BY tbl_penjualan.$field_rekap ASC, tbl_penjualan.tgl_jual, tbl_penjualan.nmrkirim;";
		} elseif ($field_rekap == "konsumen_nama" or $field_rekap == "konsumen") {
			$field_rekap = "konsumen_nama";
			$sql_penjualan = "SELECT * FROM tbl_penjualan ORDER BY tbl_penjualan.$field_rekap ASC, tbl_penjualan.tgl_jual, tbl_penjualan.nmrkirim;";
		} elseif ($field_rekap == "nama_barang") {
			$sql_penjualan = "SELECT * FROM tbl_penjualan ORDER BY tbl_penjualan.$field_rekap ASC, tbl_penjualan.tgl_jual, tbl_penjualan.nmrkirim;";
		} else {
			$field_rekap = "unit";
			$sql_penjualan = "SELECT * FROM tbl_penjualan ORDER BY tbl_penjualan.$field_rekap ASC, tbl_penjualan.tgl_jual, tbl_penjualan.nmrkirim;";
		}



		// $data_penjualan_per_barang = $this->db->query($sql_persediaan)->result();

		$data = array(
			'Tbl_penjualan_data' => $this->db->query($sql_penjualan)->result(),
			'field_rekap' => $field_rekap,
		);

		// print_r($data);
		// die;

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_list_rekap_data', $data);
	}

	public function bayar()
	{

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/pembayaran/bayar_form');
	}

	public function accounting()
	{

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/accounting/accounting_form');
	}


	public function bukukas()
	{

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/bukukas/bukukas_form');
	}

	public function kaskecil()
	{

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/kaskecil/kaskecil_form');
	}

	public function neraca()
	{

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/neraca/neraca_form');
	}

	public function labarugi()
	{

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/labarugi/labarugi_form');
	}

	public function read($id)
	{
		$row = $this->Tbl_penjualan_model->get_by_id($id);
		if ($row) {
			$data = array(
				'id' => $row->id,
				'tgl_input' => $row->tgl_input,
				'nmrpesan' => $row->nmrpesan,
				'nmrkirim' => $row->nmrkirim,
				'konsumen_id' => $row->konsumen_id,
				'konsumen_nama' => $row->konsumen_nama,
				'kode_barang' => $row->kode_barang,
				'nama_barang' => $row->nama_barang,
				'unit' => $row->unit,
				'satuan' => $row->satuan,
				'harga_satuan' => $row->harga_satuan,
				'jumlah' => $row->jumlah,
				'umpphpsl22' => $row->umpphpsl22,
				'piutang' => $row->piutang,
				'penjualandpp' => $row->penjualandpp,
				'utangppn' => $row->utangppn,
				'id_usr' => $row->id_usr,
			);
			$this->load->view('anekadharma/tbl_penjualan/tbl_penjualan_read', $data);
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(site_url('tbl_penjualan'));
		}
	}

	public function create()
	{
		$data = array(
			'button' => 'Simpan',
			'action' => site_url('tbl_penjualan/create_action'),
			'id' => set_value('id'),
			'tgl_input' => set_value('tgl_input'),
			'tgl_jual' => set_value('tgl_jual'),
			'nmrpesan' => set_value('nmrpesan'),
			'nmrkirim' => set_value('nmrkirim'),
			'konsumen_id' => set_value('konsumen_id'),
			'konsumen_nama' => set_value('konsumen_nama'),
			'kode_barang' => set_value('kode_barang'),
			'nama_barang' => set_value('nama_barang'),
			'unit' => set_value('unit'),
			'satuan' => set_value('satuan'),
			'harga_satuan' => set_value('harga_satuan'),
			'jumlah' => set_value('jumlah'),
			'umpphpsl22' => set_value('umpphpsl22'),
			'piutang' => set_value('piutang'),
			'penjualandpp' => set_value('penjualandpp'),
			'utangppn' => set_value('utangppn'),
			'id_usr' => set_value('id_usr'),
		);

		// $this->load->view('anekadharma/tbl_penjualan/tbl_penjualan_form', $data);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_form', $data);
	}

	public function create_action()
	{
		$this->_rules();

		if ($this->form_validation->run() == FALSE) {
			$this->create();
		} else {


			// GET KONSUMEN DATA


			// =================

			// unIT
			$this->db->where('uuid_unit', $this->input->post('uuid_unit', TRUE));
			$sys_unit_data = $this->db->get('sys_unit');

			// print_r($sys_unit_data);
			// print_r("<br/>");

			if ($sys_unit_data->num_rows() > 0) {

				$Get_unit_data = $sys_unit_data->row_array();

				$Get_uuid_unit = $this->input->post('uuid_unit', TRUE);
				$Get_kode_unit = $Get_unit_data['kode_unit'];
				$Get_nama_unit = $Get_unit_data['nama_unit'];
			}

			// print_r($Get_kode_unit);
			// print_r("<br/>");
			// print_r($Get_nama_unit);
			// print_r("<br/>");



			// KONSUMEN
			$this->db->where('uuid_konsumen', $this->input->post('uuid_konsumen', TRUE));
			$sys_konsumen_data = $this->db->get('sys_konsumen');

			if ($sys_konsumen_data->num_rows() > 0) {
				// Konsumen dari sys_konsumen
				$get_uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
				$sql_uuid_konsumen = "SELECT * FROM `sys_konsumen` WHERE `uuid_konsumen`='$get_uuid_konsumen'";
				// $get_kode_konsumen = $this->db->query($sql_uuid_konsumen)->row()->kode_konsumen;
				$get_nama_konsumen = $this->db->query($sql_uuid_konsumen)->row()->nama_konsumen;
			} else {
				// Konsumen dari unit

				// $uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
				// $data_konsumen = $this->Sys_unit_model->get_by_uuid_unit($uuid_konsumen);
				// $data_nama_konsumen = $data_konsumen->nama_unit;


				$get_uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
				$sql_uuid_konsumen = "SELECT * FROM `sys_unit` WHERE `uuid_unit`='$get_uuid_konsumen'";
				// $get_kode_konsumen = $this->db->query($sql_uuid_konsumen)->row()->kode_konsumen;
				$get_nama_konsumen = $this->db->query($sql_uuid_konsumen)->row()->nama_unit;
			}







			if (date("Y", strtotime($this->input->post('tgl_jual', TRUE))) < 2020) {
				// print_r("Tahun kurang dari 2020");
				$date_tgl_jual = date("Y-m-d H:i:s");
			} else {
				// print_r("Tahun lebih dari 2020");
				$date_tgl_jual = date("Y-m-d H:i:s", strtotime($this->input->post('tgl_jual', TRUE)));
			}


			if (isset($_POST["namabarang"])) {
				$loopdata = $_POST["namabarang"];
				reset($loopdata);
				while (list($key, $value) = each($loopdata)) {
					$namabarang   = $_POST["namabarang"][$key];
					$unit     = $_POST["unit"][$key];
					$satuan     = $_POST["satuan"][$key];
					$hargasatuan     = $_POST["hargasatuan"][$key];

					// $sql_loopdata   = "INSERT INTO tbl_loopdata(rincian_loopdata,jenis_loopdata,id_karyawan)
					//   VALUES('$rincian_loopdata','$jenis_loopdata','$id_karyawan')";
					// $hasil_loopdata = mysql_query($sql_loopdata);

					$data = array(
						'tgl_input' => date("Y-m-d H:i:s"),
						'tgl_jual' => $date_tgl_jual,
						'nmrpesan' => $this->input->post('nmrpesan', TRUE),
						'nmrkirim' => $this->input->post('nmrkirim', TRUE),
						'uuid_konsumen' => $get_uuid_konsumen,
						'konsumen_nama' => $get_nama_konsumen,
						'nama_barang' => $namabarang,
						'uuid_unit' => $Get_uuid_unit,
						'unit' => $Get_nama_unit,
						'satuan' => $satuan,

						'harga_satuan' => preg_replace("/[^0-9]/", "", $hargasatuan),
						'id_usr' => 1,
					);

					// print_r($data);
					// // print_r("<br/>");
					// // print_r("<br/>");
					// die;
					$this->Tbl_penjualan_model->insert($data);
				}
			}



			// $data = array(
			// 	'tgl_jual' => $this->input->post('tgl_jual', TRUE),
			// 	'nmrpesan' => $this->input->post('nmrpesan', TRUE),
			// 	'nmrkirim' => $this->input->post('nmrkirim', TRUE),
			// 	'uuid_konsumen' => $get_uuid_konsumen,
			// 	'konsumen_nama' => $get_nama_konsumen,
			// 	// 'kode_barang' => $this->input->post('kode_barang', TRUE),
			// 	'nama_barang' => $this->input->post('nama_barang', TRUE),
			// 	'unit' => $this->input->post('unit', TRUE),
			// 	'satuan' => $this->input->post('satuan', TRUE),
			// 	'harga_satuan' => $this->input->post('harga_satuan', TRUE),
			// 	'jumlah' => $this->input->post('jumlah', TRUE),
			// 	'umpphpsl22' => $this->input->post('umpphpsl22', TRUE),
			// 	'piutang' => $this->input->post('piutang', TRUE),
			// 	'penjualandpp' => $this->input->post('penjualandpp', TRUE),
			// 	'utangppn' => $this->input->post('utangppn', TRUE),
			// 	'id_usr' => $this->input->post('id_usr', TRUE),
			// );







			$this->session->set_flashdata('message', 'Create Record Success');
			redirect(site_url('tbl_penjualan'));
		}
	}

	public function create_action_inisiasi($id_proses = null)
	{

		// unIT
		$this->db->where('uuid_unit', $this->input->post('uuid_unit', TRUE));
		$sys_unit_data = $this->db->get('sys_unit');

		// print_r($sys_unit_data);
		// print_r("<br/>");

		if ($sys_unit_data->num_rows() > 0) {

			$Get_unit_data = $sys_unit_data->row_array();

			$Get_UUID_unit = $this->input->post('uuid_unit', TRUE);
			$Get_kode_unit = $Get_unit_data['kode_unit'];
			$Get_nama_unit = $Get_unit_data['nama_unit'];
		}

		// KONSUMEN
		$this->db->where('uuid_konsumen', $this->input->post('uuid_konsumen', TRUE));
		//$this->db->where('password',  $test);
		$sys_konsumen_data = $this->db->get('sys_konsumen');

		if ($sys_konsumen_data->num_rows() > 0) {
			// Konsumen dari sys_konsumen
			$uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
			$data_konsumen = $this->Sys_konsumen_model->get_by_uuid_konsumen($uuid_konsumen);
			$data_nama_konsumen = $data_konsumen->nama_konsumen;
		} else {
			// Konsumen dari unit

			$uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
			$data_konsumen = $this->Sys_unit_model->get_by_uuid_unit($uuid_konsumen);
			$data_nama_konsumen = $data_konsumen->nama_unit;
		}

		$data = array(
			'button' => 'Simpan',
			'button_detail_nomor_kirim' => 'Simpan Perubahan Detail Nomor Kirim',
			'action' => site_url('tbl_penjualan/create_action_simpan_barang/'),
			'tgl_jual' => $this->input->post('tgl_jual', TRUE),
			'nmrpesan' => $this->input->post('nmrpesan', TRUE),
			'nmrkirim' => $this->input->post('nmrkirim', TRUE),
			'uuid_konsumen' => $uuid_konsumen,
			'nama_konsumen' => $data_nama_konsumen,
			'uuid_unit' => $Get_UUID_unit,
			'unit' => $Get_nama_unit,
		);
		// 		print_r($data);
		// die;
		// $this->load->view('anekadharma/tbl_penjualan/tbl_penjualan_form', $data);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_form_input_barang', $data);
	}


	public function create_action_simpan_barang($uuid_penjualan = null, $id_persediaan_barang = null)
	{

		// AMBIL DATA DARI PERSEDIAAN
		$sql = "SELECT * FROM `persediaan` WHERE `id`='$id_persediaan_barang'";
		$data_barang = $this->db->query($sql)->row();

		// unIT
		$this->db->where('uuid_unit', $this->input->post('uuid_unit', TRUE));
		$sys_unit_data = $this->db->get('sys_unit');

		// print_r($sys_unit_data);
		// print_r("<br/>");

		if ($sys_unit_data->num_rows() > 0) {

			$Get_unit_data = $sys_unit_data->row_array();

			$Get_uuid_unit = $this->input->post('uuid_unit', TRUE);
			$Get_kode_unit = $Get_unit_data['kode_unit'];
			$Get_nama_unit = $Get_unit_data['nama_unit'];
		}

		// 		print_r($Get_uuid_unit);
		// 		print_r("<br/>");
		// 		print_r($Get_nama_unit);
		// 		print_r("<br/>");
		// die;
		// KONSUMEN
		$uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
		$data_konsumen = $this->Sys_konsumen_model->get_by_uuid_konsumen($uuid_konsumen);


		if (empty($data_konsumen)) {
			$data_konsumen = $this->Sys_unit_model->get_by_uuid_unit($uuid_konsumen);
			$data_nama_konsumen = $data_konsumen->nama_unit;
		} else {
			$data_nama_konsumen = $data_konsumen->nama_konsumen;
		}

		// =========SIMPAN DATA==================
		$tgl_jual_X = date("Y-m-d", strtotime($this->input->post('tgl_jual', TRUE)));


		if ($uuid_penjualan == "new") {
			// print_r("NEW");
			$data = array(
				'tgl_input' => date("Y-m-d H:i:s"),
				'tgl_jual' => $tgl_jual_X,
				// 'uuid_penjualan' => "new",
				'nmrpesan' => $this->input->post('nmrpesan', TRUE),
				'nmrkirim' => $this->input->post('nmrkirim', TRUE),
				'uuid_unit' => $Get_uuid_unit,
				'unit' => $Get_nama_unit,
				'uuid_konsumen' => $uuid_konsumen,
				'konsumen_nama' => $data_nama_konsumen,
				// 'uuid_barang' => $data_barang->uuid_pembelian, //uuid_barang berdasarkan uuid_pembelian karena beda harga (barang sama, waktu beda belanja harga beda)
				'uuid_persediaan' => $data_barang->uuid_persediaan,
				'id_persediaan_barang' => $id_persediaan_barang, //uuid_barang berdasarkan uuid_pembelian karena beda harga (barang sama, waktu beda belanja harga beda)
				'uuid_barang' => $data_barang->uuid_barang, //uuid_barang berdasarkan uuid_pembelian karena beda harga (barang sama, waktu beda belanja harga beda)
				'kode_barang' => $data_barang->kode_barang,
				// 'nama_barang' => $data_barang->uraian,
				'nama_barang' => $data_barang->namabarang,
				// 'uuid_unit' => $uuid_unit_selected,
				// 'unit' => $data_nama_unit,
				'proses_bayar' => "belum_bayar",
				'jumlah' => preg_replace("/[^0-9]/", "", $this->input->post('jumlah', TRUE)),
				'satuan' => $data_barang->satuan,
				'harga_satuan' => str_replace(",", ".", str_replace(".", "", $this->input->post('harga_satuan_beli', TRUE))),
				'total_nominal' =>  preg_replace("/[^0-9]/", "", $this->input->post('jumlah', TRUE)) * str_replace(",", ".", str_replace(".", "", $this->input->post('harga_satuan_beli', TRUE))),
				'id_usr' => 1,
			);



			$uuid_penjualan = $this->Tbl_penjualan_model->insert_new($data);
			// print_r('create_action_simpan_barang NEW');
			// print_r("<br/>");
			// print_r("<br/>");
			// print_r($data);
			// die;
			// print_r($uuid_penjualan);
		} else {
			// print_r("BUKAN NEW");




			$data = array(
				'tgl_input' => date("Y-m-d H:i:s"),

				'tgl_jual' => $tgl_jual_X,
				'uuid_penjualan' => $uuid_penjualan,
				'nmrpesan' => $this->input->post('nmrpesan', TRUE),
				'nmrkirim' => $this->input->post('nmrkirim', TRUE),
				'uuid_unit' => $Get_uuid_unit,
				'unit' => $Get_nama_unit,
				'uuid_konsumen' => $uuid_konsumen,
				'konsumen_nama' => $data_nama_konsumen,
				// 'uuid_barang' => $data_barang->uuid_pembelian, //uuid_barang berdasarkan uuid_pembelian karena beda harga (barang sama, waktu beda belanja harga beda)
				'uuid_persediaan' => $data_barang->uuid_persediaan,
				'id_persediaan_barang' => $id_persediaan_barang,
				'uuid_barang' => $data_barang->uuid_barang, //uuid_barang berdasarkan uuid_pembelian karena beda harga (barang sama, waktu beda belanja harga beda)
				'kode_barang' => $data_barang->kode_barang,
				// 'nama_barang' => $data_barang->uraian,
				'nama_barang' => $data_barang->namabarang,
				// 'uuid_unit' => $uuid_unit_selected,
				// 'unit' => $data_nama_unit,
				'proses_bayar' => "belum_bayar",
				'jumlah' => preg_replace("/[^0-9]/", "", $this->input->post('jumlah', TRUE)),
				'satuan' => $data_barang->satuan,
				'harga_satuan' => str_replace(",", ".", str_replace(".", "", $this->input->post('harga_satuan_beli', TRUE))),
				'total_nominal' =>  preg_replace("/[^0-9]/", "", $this->input->post('jumlah', TRUE)) * str_replace(",", ".", str_replace(".", "", $this->input->post('harga_satuan_beli', TRUE))),
				'id_usr' => 1,
			);

			// print_r("Barang baru");
			// print_r("<br/>");
			// print_r($data);
			// die;

			$this->Tbl_penjualan_model->insert_add_barang($data);
		}

		// die;
		// =========SIMPAN DATA==================


		// update field penjualan di tabel persediaan: dapatkan total penjualan, kemudian update penjualan field + penjulan baru
		// $this->db->where('email', $email);
		// $users = $this->db->get('persediaan');
		// print_r("jumlah penjualan di persediaan");
		// print_r("<br/>");
		// print_r($data_barang->penjualan);


		$Total_penjualan = $data_barang->penjualan + preg_replace("/[^0-9]/", "", $this->input->post('jumlah', TRUE));
		// print_r($Total_penjualan);



		$sql_stock = "UPDATE `persediaan` SET `penjualan`='$Total_penjualan' WHERE `id`='$id_persediaan_barang'";

		$this->db->query($sql_stock);


		// die;

		// redirect("kasir_penjualan/".$uuid_penjualan);
		// redirect(site_url('tbl_penjualan/kasir_penjualan/' . $uuid_penjualan . '/' . $tgl_jual_X . '/' . $this->input->post('nmrkirim', TRUE)));
		redirect(site_url('tbl_penjualan/kasir_penjualan/' . $uuid_penjualan));
	}

	// public function kasir_penjualan($uuid_penjualan, $tgl_jual, $nmrkirim)
	public function kasir_penjualan($uuid_penjualan)
	{

		// Get tgl_jual dan nmrkirim dari uuid_penjualan

		$data_penjualan_per_uuid_penjualan = $this->Tbl_penjualan_model->get_ROW_by_uuid_penjualan_first_row($uuid_penjualan);

		// print_r($data_penjualan_per_uuid_penjualan);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");

		// print_r($data_penjualan_per_uuid_penjualan->tgl_jual);
		// print_r("<br/>");

		$tgl_jual_X = date("Y-m-d", strtotime($data_penjualan_per_uuid_penjualan->tgl_jual));

		// print_r($tgl_jual_X);
		// print_r("<br/>");
		// print_r($data_penjualan_per_uuid_penjualan->nmrkirim);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");
		// die;

		// --------------TAMPILKAN DATA INPUT PENJUALAN SESUAI UUID_NOMOR PESAN yang barusan di inputkan ----------------------
		// $data_penjualan_per_uuid_penjualan = $this->Tbl_penjualan_model->get_all_by_tgl_jual_nmrkirim($tgl_jual_X, $data_penjualan_per_uuid_penjualan->nmrkirim);
		$data_penjualan_per_uuid_penjualan = $this->Tbl_penjualan_model->get_all_by_uuid_penjualan($uuid_penjualan);

		// print_r($data_penjualan_per_uuid_penjualan);
		// print_r("<br/>");
		// // print_r("<br/>");
		// print_r("<br/>");


		// $data_penjualan_per_uuid_penjualan_first_row = $this->Tbl_penjualan_model->get_all_by_tgl_jual_nmrkirim_first_row($tgl_jual_X, $data_penjualan_per_uuid_penjualan->nmrkirim);
		$data_penjualan_per_uuid_penjualan_first_row = $this->Tbl_penjualan_model->get_all_by_uuid_penjualan_first_row($uuid_penjualan);

		// print_r($data_penjualan_per_uuid_penjualan_first_row);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");
		// die;

		$data = array(
			'data_penjualan_per_uuid_penjualan' => $data_penjualan_per_uuid_penjualan,
			'button' => 'Simpan',
			'button_detail_nomor_kirim' => 'Simpan Perubahan Detail Nomor Kirim',
			'action' => site_url('tbl_penjualan/create_action_simpan_barang/'),
			'tgl_jual' => $data_penjualan_per_uuid_penjualan_first_row->tgl_jual,
			'nmrpesan' => $data_penjualan_per_uuid_penjualan_first_row->nmrpesan,
			'nmrkirim' => $data_penjualan_per_uuid_penjualan_first_row->nmrkirim,
			'uuid_unit' => $data_penjualan_per_uuid_penjualan_first_row->uuid_unit,
			'unit' => $data_penjualan_per_uuid_penjualan_first_row->unit,
			'uuid_konsumen' => $data_penjualan_per_uuid_penjualan_first_row->uuid_konsumen,
			'nama_konsumen' => $data_penjualan_per_uuid_penjualan_first_row->konsumen_nama,
			'uuid_penjualan' => $uuid_penjualan,
			'action_ubah_per_id' => site_url('tbl_penjualan/create_action_nmrkirim_update_per_id_penjualan/'),
			'action_ubah_detail_nomor_kirim' => site_url('tbl_penjualan/action_ubah_detail_nomor_kirim/' . $data_penjualan_per_uuid_penjualan_first_row->nmrkirim . '/' . $uuid_penjualan),
		);

		// print_r($data);
		// die;

		// $this->load->view('anekadharma/tbl_penjualan/tbl_penjualan_form', $data);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_form_input_barang', $data);
	}

	public function action_ubah_detail_nomor_kirim($NomorKirim = null, $uuid_penjualan = null)
	{

		if (date("Y", strtotime($this->input->post('tgl_jual', TRUE))) < 2020) {
			// print_r("Tahun kurang dari 2020");
			$date_jual = date("Y-m-d H:i:s");
		} else {
			// print_r("Tahun lebih dari 2020");
			$date_jual = date("Y-m-d H:i:s", strtotime($this->input->post('tgl_jual', TRUE)));
		}

		// $Get_uuid_penjualan = ;
		$NomorKirim_baru = $this->input->post('nmrkirim', TRUE);
		$NomorPesan_baru = $this->input->post('nmrpesan', TRUE);
		$GET_uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
		// $GET_uuid_unit = $this->input->post('uuid_unit', TRUE);

		// unIT
		$this->db->where('uuid_unit', $this->input->post('uuid_unit', TRUE));
		$sys_unit_data = $this->db->get('sys_unit');

		// print_r($sys_unit_data);
		// print_r("<br/>");

		if ($sys_unit_data->num_rows() > 0) {

			$Get_unit_data = $sys_unit_data->row_array();

			$Get_uuid_unit = $this->input->post('uuid_unit', TRUE);
			$Get_kode_unit = $Get_unit_data['kode_unit'];
			$Get_nama_unit = $Get_unit_data['nama_unit'];
		}




		$this->db->where('uuid_unit', $GET_uuid_konsumen);
		$GET_sys_unit = $this->db->get('sys_unit');
		if ($GET_sys_unit->num_rows() > 0) {
			$GET_DATA_sys_unit = $GET_sys_unit->row_array();

			// $kode_konsumen = $GET_DATA_sys_unit['kode_unit'],
			$nama_konsumen = $GET_DATA_sys_unit['nama_unit'];
		}

		$this->db->where('uuid_konsumen', $GET_uuid_konsumen);
		$GET_sys_konsumen = $this->db->get('sys_konsumen');
		if ($GET_sys_konsumen->num_rows() > 0) {
			$GET_DATA_sys_konsumen = $GET_sys_konsumen->row_array();

			// $kode_konsumen = $GET_DATA_sys_konsumen['kode_konsumen'],
			$nama_konsumen = $GET_DATA_sys_konsumen['nama_konsumen'];
		}


		// $sql_update_penjualan_by_uuid_penjualan = "UPDATE `tbl_penjualan` SET `nmrkirim`=$NomorKirim_baru , `tgl_jual`=$date_jual , `nmrpesan`=$NomorPesan_baru, `uuid_konsumen`=$GET_uuid_konsumen, `konsumen_nama`=$nama_konsumen  WHERE `uuid_penjualan`='$uuid_penjualan'";

		$sql_update_penjualan_by_uuid_penjualan = "UPDATE `tbl_penjualan` SET `tgl_jual`='$date_jual' , `nmrkirim`='$NomorKirim_baru', `nmrpesan`='$NomorPesan_baru', `uuid_konsumen`='$GET_uuid_konsumen', `konsumen_nama`='$nama_konsumen', `uuid_unit`='$Get_uuid_unit', `unit`='$Get_nama_unit'  WHERE `uuid_penjualan`='$uuid_penjualan'";

		$this->db->query($sql_update_penjualan_by_uuid_penjualan);

		// print_r($sql_update_penjualan_by_uuid_penjualan);
		// die;

		redirect(site_url('Tbl_penjualan/'));
	}


	public function create_action_nmrkirim_update_per_id_penjualan($id)
	{

		// print_r("create_action_nmrkirim_update_per_id_penjualan");
		// print_r("<br/>");
		// print_r($id);
		// print_r("<br/>");
		// print_r($this->input->post('uuid_konsumen', TRUE));
		// print_r("<br/>");
		// print_r($this->input->post('uuid_barang', TRUE));
		// print_r("<br/>");
		// print_r($this->input->post('satuan', TRUE));
		// print_r("<br/>");
		// print_r($this->input->post('jumlah', TRUE));
		// print_r("<br/>");
		// print_r($this->input->post('uuid_gudang', TRUE));
		// print_r("<br/>");
		// print_r($this->input->post('harga_satuan', TRUE));
		// print_r("<br/>");
		// die;

		// Get uuid_spop from id
		$sql_data_penjualan = "SELECT * FROM `tbl_penjualan` WHERE `id`='$id'";
		// $get_uuid_spop = $this->db->query($sql_data_penjualan)->row()->uuid_spop;
		$Get_uuid_penjualan = $this->db->query($sql_data_penjualan)->row()->uuid_penjualan;
		$get_id_persediaan = $this->db->query($sql_data_penjualan)->row()->id_persediaan_barang;
		$get_jmlh_penjualan_awal = $this->db->query($sql_data_penjualan)->row()->jumlah;

		// print_r($this->db->query($sql_data_pembelian)->row());
		// print_r("<br/>");

		// print_r($this->db->query($sql_data_pembelian)->row()->uuid_persediaan);
		// print_r("<br/>");

		// die;
		// print_r($get_uuid_spop);

		// die;

		// Data Persediaan berdasarkan uuid_persediaan
		$sql_data_persediaan_by_uuid_persediaan = "SELECT * FROM `persediaan` WHERE `id`='$get_id_persediaan'";
		$get_jmlh_penjualan_dipersediaan = $this->db->query($sql_data_persediaan_by_uuid_persediaan)->row()->penjualan;
		$get_jmlh_STOCK_dipersediaan = $this->db->query($sql_data_persediaan_by_uuid_persediaan)->row()->total_10;








		// $row_per_uuid_spop = $this->Tbl_pembelian_model->get_by_uuid_spop($uuid_spop);

		// GET KONSUMEN DATA
		// $GET_uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);

		// $sql_uuid_konsumen = "SELECT * FROM `sys_unit` WHERE `uuid_unit`='$GET_uuid_konsumen'";
		// $get_kode_konsumen = $this->db->query($sql_uuid_konsumen)->row()->kode_unit;
		// $get_nama_konsumen = $this->db->query($sql_uuid_konsumen)->row()->nama_unit;

		// GET BARANG DATA
		$GET_uuid_barang = $this->input->post('uuid_barang', TRUE);
		$sql_uuid_barang = "SELECT * FROM `sys_nama_barang` WHERE `uuid_barang`='$GET_uuid_barang'";
		// $get_kode_barang = $this->db->query($sql_uuid_barang)->row()->kode_barang;
		// $get_nama_barang = $this->db->query($sql_uuid_barang)->row()->nama_barang;

		$jumlah_Jual_ubah = preg_replace("/[^0-9]/", "", $this->input->post('jumlah', TRUE));


		// print_r("jumlah jual awal:");
		// print_r($get_jmlh_penjualan_awal);
		// print_r("<br/>");
		// print_r($jumlah_Jual_ubah);
		// print_r("<br/>");

		// $harga_satuan_tanpa_titik = str_replace('.', '', $this->input->post('harga_satuan', TRUE)); // menghilangkan titik		
		// $harga_satuan_x = str_replace(',', '.', $harga_satuan_tanpa_titik); // mengganti koma dengan titik
		// Masukkan data ke database

		$harga_satuan_x = str_replace(",", ".", str_replace(".", "", $this->input->post('harga_satuan', TRUE))); // menghilangkan titik dan mengubah koma menjadi titik agar bisa masuk ke type data decimal di mysql

		// print_r($this->input->post('harga_satuan', TRUE));
		// print_r("<br/>");
		// print_r($harga_satuan_x);
		// die;

		$TOTAL_X = $jumlah_Jual_ubah * $harga_satuan_x;

		// UPDATE DATA BARANG
		$data = array(
			// 'date_input' => date("Y-m-d H:i:s"),
			'jumlah' => $jumlah_Jual_ubah,
			'harga_satuan' => $harga_satuan_x,
			'total_nominal' => $TOTAL_X,
		);

		// print_r($id);
		// print_r("<br/>");
		// print_r($data);
		// die;

		$this->Tbl_penjualan_model->update($id, $data);

		//KONTROL INI BELUM ADA:
		// NOTE : HARUS CEK FIELD PENJUALAN , JIKA SUDAH ADA PROSES PENJUALAN, MAKA TIDAK BOLEH MENGUBAH NAMA BARANG, HANYA BISA MENGUBAH HPP DAN JUMLAH BELI (JUMLAH BELI HARUS LEBIH DARI TOTAL JUMLAH TERJUAL)

		// UPDATE DATA DI PERSEDIAAN berdasarkan id persediaan atau uuid_persediaan


		// fiel penjualan tabel persediaan: Kalkulasi total penjualan - jumlah jual awal kemudian total penjualan + jumlah jual akhir

		$Total_jual_perubahan = ($get_jmlh_penjualan_dipersediaan - $get_jmlh_penjualan_awal) + $jumlah_Jual_ubah;

		// print_r($Total_jual_perubahan);
		// print_r("<br/>");

		$Update_data_persediaan = array(
			'penjualan' => $Total_jual_perubahan,
		);

		$this->Persediaan_model->update($get_id_persediaan, $Update_data_persediaan);

		// print_r("persediaan");
		// print_r("<br/>");
		// print_r($Update_data_persediaan);
		// die;

		redirect(site_url('Tbl_penjualan/kasir_penjualan/' . $Get_uuid_penjualan));
	}


	// public function cetak_penjualan_per_uuid_penjualan($uuid_penjualan = null, $date_tgl_jual = null, $nmrkirim = null)
	public function cetak_penjualan_per_uuid_penjualan($uuid_penjualan = null)
	{

		// 2.a. PERSIAPAN LIBRARY
		// $this->load->library('PdfGenerator');



		// $data_master_penjualan_per_uuidpenjualan = $this->Tbl_penjualan_model->get_all_by_uuid_penjualan_first_row($uuid_penjualan);
		// $data_master_penjualan_per_uuidpenjualan = $this->Tbl_penjualan_model->get_all_by_uuid_penjualan_tgl_jual_nmrkirim_first_row($uuid_penjualan, $date_tgl_jual, $nmrkirim);
		$data_master_penjualan_per_uuidpenjualan = $this->Tbl_penjualan_model->get_all_by_uuid_penjualan_first_row($uuid_penjualan);

		// print_r($data_master_penjualan_per_uuidpenjualan);
		// print_r($data_master_penjualan_per_uuidpenjualan->nmrpesan);
		// print_r(date("d M Y", strtotime($data_master_penjualan_per_uuidpenjualan->tgl_jual)));
		// print_r($data_master_penjualan_per_uuidpenjualan->konsumen_nama);
		// die;


		// $date_po = date("d M Y", strtotime($data_master_penjualan_per_uuidpenjualan->tgl_jual));
		// die;

		// 2.b. PERSIAPAN DATA
		$data = array(
			'data_penjualan' => $this->Tbl_penjualan_model->get_all_by_uuid_penjualan($uuid_penjualan),
			'nmr_pesan_selected' => $data_master_penjualan_per_uuidpenjualan->nmrpesan,
			'tgl_jual_selected' => date("d M Y", strtotime($data_master_penjualan_per_uuidpenjualan->tgl_jual)),
			'konsumen_nama_selected' => $data_master_penjualan_per_uuidpenjualan->konsumen_nama,
		);



		// 2.C. MENAMPILKAN FILE DATA
		// $data = array_merge($data);
		$html = $this->load->view('anekadharma/tbl_penjualan/adminlte310_cetak_penjualan.php', $data, true);

		// 2.d. CONVERT TAMPILAN FILE DATA MENJADI FILE PDF
		$this->pdf->loadHtml($html);
		$this->pdf->render();

		$this->pdf->stream("NOTA_PENJUALAN.pdf", array("Attachment" => 0));
	}



	public function update($id)
	{
		$row = $this->Tbl_penjualan_model->get_by_id($id);

		if ($row) {
			$data = array(
				'button' => 'Update',
				'action' => site_url('tbl_penjualan/update_action'),
				'id' => set_value('id', $row->id),
				'tgl_input' => set_value('tgl_input', $row->tgl_input),
				'nmrpesan' => set_value('nmrpesan', $row->nmrpesan),
				'nmrkirim' => set_value('nmrkirim', $row->nmrkirim),
				'konsumen_id' => set_value('konsumen_id', $row->konsumen_id),
				'konsumen_nama' => set_value('konsumen_nama', $row->konsumen_nama),
				'kode_barang' => set_value('kode_barang', $row->kode_barang),
				'nama_barang' => set_value('nama_barang', $row->nama_barang),
				'unit' => set_value('unit', $row->unit),
				'satuan' => set_value('satuan', $row->satuan),
				'harga_satuan' => set_value('harga_satuan', $row->harga_satuan),
				'jumlah' => set_value('jumlah', $row->jumlah),
				'umpphpsl22' => set_value('umpphpsl22', $row->umpphpsl22),
				'piutang' => set_value('piutang', $row->piutang),
				'penjualandpp' => set_value('penjualandpp', $row->penjualandpp),
				'utangppn' => set_value('utangppn', $row->utangppn),
				'id_usr' => set_value('id_usr', $row->id_usr),
			);

			// $this->load->view('anekadharma/tbl_penjualan/tbl_penjualan_form', $data);
			$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_form_update', $data);
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(site_url('tbl_penjualan'));
		}
	}

	public function update_penjualan($uuid_penjualan_proses)
	{




		$row = $this->Tbl_penjualan_model->get_all_by_uuid_penjualan_proses($uuid_penjualan_proses);

		if ($row) {
			$data = array(
				'button' => 'Update',
				'uuid_penjualan_proses' => $uuid_penjualan_proses,
				'action' => site_url('Tbl_penjualan/update_action_proses/' . $uuid_penjualan_proses),
				'id' => set_value('id', $row->id),
				'tgl_input' => set_value('tgl_input', $row->tgl_input),
				'tgl_jual' => set_value('tgl_jual', $row->tgl_jual),
				'nmrpesan' => set_value('nmrpesan', $row->nmrpesan),
				'nmrkirim' => set_value('nmrkirim', $row->nmrkirim),

				'konsumen_id' => set_value('konsumen_id', $row->konsumen_id),
				'uuid_konsumen' => set_value('uuid_konsumen', $row->uuid_konsumen),
				'konsumen_nama' => set_value('konsumen_nama', $row->konsumen_nama),

				'id_persediaan_barang' => set_value('id_persediaan_barang', $row->id_persediaan_barang),
				'uuid_barang' => set_value('uuid_barang', $row->uuid_barang),
				'kode_barang' => set_value('kode_barang', $row->kode_barang),
				'nama_barang' => set_value('nama_barang', $row->nama_barang),
				'unit' => set_value('unit', $row->unit),
				'satuan' => set_value('satuan', $row->satuan),
				'harga_satuan' => set_value('harga_satuan', $row->harga_satuan),
				'jumlah' => set_value('jumlah', $row->jumlah),
				'umpphpsl22' => set_value('umpphpsl22', $row->umpphpsl22),
				'piutang' => set_value('piutang', $row->piutang),
				'penjualandpp' => set_value('penjualandpp', $row->penjualandpp),
				'utangppn' => set_value('utangppn', $row->utangppn),
				'id_usr' => set_value('id_usr', $row->id_usr),
				// 'Data_stock' => $Data_stock,
			);

			// Update Persediaan Field penjualan : uuid_proses lama di kurangi , uuid_proses baru di tambah

			// $this->load->view('anekadharma/tbl_penjualan/tbl_penjualan_form', $data);
			$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_form_update_new', $data);
		} else {

			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(site_url('tbl_penjualan'));
		}
	}

	public function update_action_pilih_barang($uuid_penjualan_proses = null, $proses_cek = null, $id_persediaan_barang = null)
	{

		// print_r($id);
		// print_r("<br/>");
		// print_r("<br/>");

		$row_barang = $this->Persediaan_model->get_by_id($id_persediaan_barang);

		// print_r($row_barang);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r($uuid_penjualan_proses);
		// print_r("<br/>");
		// print_r($proses_cek);
		// print_r("<br/>");
		// print_r($id);
		// print_r("<br/>");
		// print_r("update_action_prosesX");



		$row = $this->Tbl_penjualan_model->get_all_by_uuid_penjualan_proses($uuid_penjualan_proses);


		// print_r($row);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");

		if ($row) {
			$data = array(
				'button' => 'Update',
				'uuid_penjualan_proses' => $uuid_penjualan_proses,
				'action' => site_url('tbl_penjualan/update_action_proses/' . $uuid_penjualan_proses . '/'),
				'id' => set_value('id', $row->id),
				'tgl_input' => set_value('tgl_input', $row->tgl_input),
				'tgl_jual' => set_value('tgl_jual', $row->tgl_jual),
				'nmrpesan' => set_value('nmrpesan', $row->nmrpesan),
				'nmrkirim' => set_value('nmrkirim', $row->nmrkirim),
				'uuid_konsumen' => set_value('uuid_konsumen', $row->uuid_konsumen),
				'konsumen_id' => set_value('konsumen_id', $row->konsumen_id),
				'konsumen_nama' => set_value('konsumen_nama', $row->konsumen_nama),

				'id_persediaan_barang' => $id_persediaan_barang,
				'uuid_barang' => set_value('uuid_barang', $row_barang->uuid_barang),
				'kode_barang' => set_value('kode_barang', $row_barang->kode_barang),
				'nama_barang' => set_value('nama_barang', $row_barang->namabarang),
				'satuan' => set_value('satuan', $row_barang->satuan),
				'harga_satuan' => set_value('harga_satuan', $row_barang->hpp),

				'unit' => set_value('unit', $row->unit),


				'jumlah' => set_value('jumlah', $row->jumlah),
				'umpphpsl22' => set_value('umpphpsl22', $row->umpphpsl22),
				'piutang' => set_value('piutang', $row->piutang),
				'penjualandpp' => set_value('penjualandpp', $row->penjualandpp),
				'utangppn' => set_value('utangppn', $row->utangppn),
				'id_usr' => set_value('id_usr', $row->id_usr),
				// 'Data_stock' => $Data_stock,
			);


			// print_r($id);
			// print_r("<br/>");
			// print_r("<br/>");
			// print_r($data);
			// die;

			// $this->load->view('anekadharma/tbl_penjualan/tbl_penjualan_form', $data);
			$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_form_update_new', $data);
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(site_url('tbl_penjualan'));
		}
	}

	public function update_action_proses($uuid_penjualan_proses = null)
	{


		// $row = $this->Tbl_penjualan_model->get_all_by_uuid_penjualan_proses($uuid_penjualan_proses);

		$tgl_jual_X = date("Y-m-d", strtotime($this->input->post('tgl_jual', TRUE)));

		$uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
		$data_konsumen = $this->Sys_konsumen_model->get_by_uuid_konsumen($uuid_konsumen);


		if (empty($data_konsumen)) {
			$data_konsumen = $this->Sys_unit_model->get_by_uuid_unit($uuid_konsumen);
			$data_id_konsumen = $data_konsumen->id;
			$data_nama_konsumen = $data_konsumen->nama_unit;
		} else {
			$data_id_konsumen = $data_konsumen->id;
			$data_nama_konsumen = $data_konsumen->nama_konsumen;
		}

		if (empty($this->input->post('id_persediaan_barang', TRUE)) or $this->input->post('id_persediaan_barang', TRUE) == 0) {

			$get_uuid_barang = $this->input->post('uuid_barang', TRUE);

			$sql_stock = "SELECT persediaan.id as id_persediaan_barang, persediaan.uuid_barang as uuid_barang, persediaan.kode_barang as kode_barang, persediaan.namabarang as nama_barang_beli,persediaan.total_10 as jumlah_sediaan,  persediaan.hpp as harga_satuan_persediaan,  persediaan.satuan as satuan
			-- 	tbl_pembelian.uuid_pembelian as uuid_pembelian,tbl_pembelian.uraian as barang_beli, tbl_pembelian.jumlah as jumlah_belanja, tbl_pembelian.harga_satuan as harga_satuan_beli,  tbl_pembelian.tgl_po as tgl_po,tbl_pembelian.uuid_gudang as uuid_gudang, tbl_pembelian.nama_gudang as nama_gudang,  tbl_pembelian.satuan as satuan,
			-- tbl_penjualan.nama_barang as barang_jual, tbl_penjualan.jumlah as jumlah_terjual
			FROM persediaan  
			-- left join tbl_pembelian ON persediaan.uuid_barang = tbl_pembelian.uuid_barang 
			-- left join tbl_penjualan ON persediaan.uuid_barang = tbl_penjualan.uuid_barang  
			WHERE (persediaan.uuid_barang, persediaan.tanggal) IN (SELECT persediaan.uuid_barang, Max(persediaan.tanggal) FROM persediaan GROUP BY persediaan.uuid_barang)  AND persediaan.uuid_barang='$get_uuid_barang'
			ORDER BY persediaan.uuid_barang ASC";

			$data_barang = $this->db->query($sql_stock)->row();
		} else {
			$get_id_persediaan_barang = $this->input->post('id_persediaan_barang', TRUE);

			$sql_stock = "SELECT persediaan.id as id_persediaan_barang, persediaan.uuid_barang as uuid_barang, persediaan.kode_barang as kode_barang, persediaan.namabarang as nama_barang_beli,persediaan.total_10 as jumlah_sediaan,  persediaan.hpp as harga_satuan_persediaan,  persediaan.satuan as satuan
			-- 	tbl_pembelian.uuid_pembelian as uuid_pembelian,tbl_pembelian.uraian as barang_beli, tbl_pembelian.jumlah as jumlah_belanja, tbl_pembelian.harga_satuan as harga_satuan_beli,  tbl_pembelian.tgl_po as tgl_po,tbl_pembelian.uuid_gudang as uuid_gudang, tbl_pembelian.nama_gudang as nama_gudang,  tbl_pembelian.satuan as satuan,
			-- tbl_penjualan.nama_barang as barang_jual, tbl_penjualan.jumlah as jumlah_terjual
			FROM persediaan  
			-- left join tbl_pembelian ON persediaan.uuid_barang = tbl_pembelian.uuid_barang 
			-- left join tbl_penjualan ON persediaan.uuid_barang = tbl_penjualan.uuid_barang  
			WHERE (persediaan.uuid_barang, persediaan.tanggal) IN (SELECT persediaan.uuid_barang, Max(persediaan.tanggal) FROM persediaan GROUP BY persediaan.uuid_barang)  AND persediaan.id='$get_id_persediaan_barang'
			ORDER BY persediaan.uuid_barang ASC";

			$data_barang = $this->db->query($sql_stock)->row();
		}





		$data = array(
			'tgl_input' => date("Y-m-d H:i:s"),
			'tgl_jual' => $tgl_jual_X,
			'nmrpesan' => $this->input->post('nmrpesan', TRUE),
			'nmrkirim' => $this->input->post('nmrkirim', TRUE),
			'uuid_konsumen' => $this->input->post('uuid_konsumen', TRUE),
			'konsumen_id' => $data_id_konsumen,
			'konsumen_nama' => $data_nama_konsumen,


			'id_persediaan_barang' => $data_barang->id_persediaan_barang,
			'uuid_barang' => $data_barang->uuid_barang,
			'kode_barang' => $data_barang->kode_barang,
			'nama_barang' => $data_barang->nama_barang_beli,

			// 'unit' => $this->input->post('unit', TRUE),
			'satuan' => $data_barang->satuan,
			'harga_satuan' => $data_barang->harga_satuan_persediaan,


			'jumlah' => $this->input->post('jumlah', TRUE),



			// 'umpphpsl22' => $this->input->post('umpphpsl22', TRUE),
			// 'piutang' => $this->input->post('piutang', TRUE),
			// 'penjualandpp' => $this->input->post('penjualandpp', TRUE),
			// 'utangppn' => $this->input->post('utangppn', TRUE),
			// 'id_usr' => $this->input->post('id_usr', TRUE),







		);

		// print_r($data);
		// print_r("update");
		// die;
		$this->Tbl_penjualan_model->update_proses($uuid_penjualan_proses, $data);
		$this->session->set_flashdata('message', 'Update Record Success');
		redirect(site_url('tbl_penjualan'));
		// }
		// }
	}


	public function update_action()
	{
		$this->_rules();

		if ($this->form_validation->run() == FALSE) {
			$this->update($this->input->post('id', TRUE));
		} else {
			$data = array(
				'tgl_input' => $this->input->post('tgl_input', TRUE),
				'nmrpesan' => $this->input->post('nmrpesan', TRUE),
				'nmrkirim' => $this->input->post('nmrkirim', TRUE),
				'konsumen_id' => $this->input->post('konsumen_id', TRUE),
				'konsumen_nama' => $this->input->post('konsumen_nama', TRUE),
				'kode_barang' => $this->input->post('kode_barang', TRUE),
				'nama_barang' => $this->input->post('nama_barang', TRUE),
				'unit' => $this->input->post('unit', TRUE),
				'satuan' => $this->input->post('satuan', TRUE),
				'harga_satuan' => $this->input->post('harga_satuan', TRUE),
				'jumlah' => $this->input->post('jumlah', TRUE),
				'umpphpsl22' => $this->input->post('umpphpsl22', TRUE),
				'piutang' => $this->input->post('piutang', TRUE),
				'penjualandpp' => $this->input->post('penjualandpp', TRUE),
				'utangppn' => $this->input->post('utangppn', TRUE),
				'id_usr' => $this->input->post('id_usr', TRUE),
			);

			$this->Tbl_penjualan_model->update($this->input->post('id', TRUE), $data);
			$this->session->set_flashdata('message', 'Update Record Success');
			redirect(site_url('tbl_penjualan'));
		}
	}

	public function delete($id = null, $uuid_penjualan = null)
	{

		// print_r($id);
		// print_r("<br/>");
		// print_r($uuid_penjualan);
		// print_r("<br/>");
		// die;

		$row = $this->Tbl_penjualan_model->get_by_id($id);

		if ($row) {


			// Get data penjualan berdasarkan uuid_penjualan , mengurangi jumlah field penjualan di tabel persediaan berdasarkan uuid_penjualan

			print_r($row->uuid_persediaan);
			print_r("<br/>");
			print_r($row->jumlah);
			print_r("<br/>");
			print_r($row->id_persediaan_barang);
			$Get_id_persediaan_barang = $row->id_persediaan_barang;
			print_r("<br/>");
			print_r($Get_id_persediaan_barang);

			// Cek nominal penjualan di tabel persediaan berdasarkan id_persediaan_barang

			$row_data_persediaan = $this->Persediaan_model->get_by_id($Get_id_persediaan_barang);

			print_r("<br/>");
			print_r($row_data_persediaan->penjualan);
			print_r("<br/>");

			$Get_total_penjualan_by_id_persediaan = $row_data_persediaan->penjualan;

			if ($Get_total_penjualan_by_id_persediaan > 0 and $Get_total_penjualan_by_id_persediaan > $row->jumlah) {
				print_r("Bisa hapus / kurangi");

				$Get_total_penjualan_after_hapus = $Get_total_penjualan_by_id_persediaan - $row->jumlah;

				print_r($Get_total_penjualan_by_id_persediaan);
				print_r("<br/>");
				print_r($row->jumlah);
				print_r("<br/>");
				print_r($Get_total_penjualan_after_hapus);
				print_r("<br/>");


				// Update field penjualan di tabel persediaan berdasarkan id persediaan
				// $sql_update_uuid_persediaan = "UPDATE `persediaan` SET `penjualan`=$Get_total_penjualan_after_hapus WHERE `id`='$Get_id_persediaan_barang'";
				// $this->db->query($sql_update_uuid_persediaan);

				$data = array(

					'penjualan' => $Get_total_penjualan_after_hapus,
				);

				// print_r($data);
				// print_r("update");
				// die;
				$this->Persediaan_model->update($Get_id_persediaan_barang, $data);



				// $row_persediaan = $this->Persediaan_model->get_by_id($id);

				// print_r($row_persediaan);
				// die;



			} else {
				// print_r("Buat fieldnya jadi 0");
			}

			// die;




			// Hapus record di tabel penjualan
			$this->Tbl_penjualan_model->delete($id);
			$this->session->set_flashdata('message', 'Delete Record Success');


			// die;

			redirect(site_url('Tbl_penjualan/kasir_penjualan/' . $uuid_penjualan));
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(site_url('tbl_penjualan'));
		}
	}

	public function _rules()
	{
		// $this->form_validation->set_rules('tgl_input', 'tgl input', 'trim|required');
		// $this->form_validation->set_rules('nmrpesan', 'nmrpesan', 'trim|required');
		// $this->form_validation->set_rules('nmrkirim', 'nmrkirim', 'trim|required');
		$this->form_validation->set_rules('uuid_konsumen', 'uuid_konsumen id', 'trim|required');
		// $this->form_validation->set_rules('konsumen_nama', 'konsumen nama', 'trim|required');
		// $this->form_validation->set_rules('kode_barang', 'kode barang', 'trim|required');
		// $this->form_validation->set_rules('nama_barang', 'nama barang', 'trim|required');
		// $this->form_validation->set_rules('unit', 'unit', 'trim|required');
		// $this->form_validation->set_rules('satuan', 'satuan', 'trim|required');
		// $this->form_validation->set_rules('harga_satuan', 'harga satuan', 'trim|required|numeric');
		// $this->form_validation->set_rules('jumlah', 'jumlah', 'trim|required|numeric');
		// $this->form_validation->set_rules('umpphpsl22', 'umpphpsl22', 'trim|required|numeric');
		// $this->form_validation->set_rules('piutang', 'piutang', 'trim|required|numeric');
		// $this->form_validation->set_rules('penjualandpp', 'penjualandpp', 'trim|required|numeric');
		// $this->form_validation->set_rules('utangppn', 'utangppn', 'trim|required|numeric');
		// $this->form_validation->set_rules('id_usr', 'id usr', 'trim|required');

		$this->form_validation->set_rules('id', 'id', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
	}

	public function excel()
	{
		$tgl_jual_Now = date("Y-m-d H:i:sa");
		$this->load->helper('exportexcel');
		$namaFile = "tbl_penjualan_" . $tgl_jual_Now . ".xls";
		$judul = "Data Penjualan";
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
		xlsWriteLabel($tablehead, $kolomhead++, "Tgl Input");
		xlsWriteLabel($tablehead, $kolomhead++, "Nmrpesan");
		xlsWriteLabel($tablehead, $kolomhead++, "Nmrkirim");
		// xlsWriteLabel($tablehead, $kolomhead++, "Konsumen Id");
		xlsWriteLabel($tablehead, $kolomhead++, "Konsumen Nama");
		xlsWriteLabel($tablehead, $kolomhead++, "Kode Barang");
		xlsWriteLabel($tablehead, $kolomhead++, "Nama Barang");
		xlsWriteLabel($tablehead, $kolomhead++, "Unit");
		xlsWriteLabel($tablehead, $kolomhead++, "Satuan");
		xlsWriteLabel($tablehead, $kolomhead++, "Harga Satuan");
		xlsWriteLabel($tablehead, $kolomhead++, "Jumlah");
		xlsWriteLabel($tablehead, $kolomhead++, "Umpphpsl22");
		xlsWriteLabel($tablehead, $kolomhead++, "Piutang");
		xlsWriteLabel($tablehead, $kolomhead++, "Penjualandpp");
		xlsWriteLabel($tablehead, $kolomhead++, "Utangppn");
		// xlsWriteLabel($tablehead, $kolomhead++, "Id Usr");

		foreach ($this->Tbl_penjualan_model->get_all() as $data) {
			$kolombody = 0;

			//ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
			xlsWriteNumber($tablebody, $kolombody++, $nourut);
			xlsWriteLabel($tablebody, $kolombody++, $data->tgl_input);
			xlsWriteLabel($tablebody, $kolombody++, $data->nmrpesan);
			xlsWriteLabel($tablebody, $kolombody++, $data->nmrkirim);
			// xlsWriteNumber($tablebody, $kolombody++, $data->konsumen_id);
			xlsWriteLabel($tablebody, $kolombody++, $data->konsumen_nama);
			xlsWriteLabel($tablebody, $kolombody++, $data->kode_barang);
			xlsWriteLabel($tablebody, $kolombody++, $data->nama_barang);
			xlsWriteNumber($tablebody, $kolombody++, $data->unit);
			xlsWriteLabel($tablebody, $kolombody++, $data->satuan);
			xlsWriteNumber($tablebody, $kolombody++, $data->harga_satuan);
			xlsWriteNumber($tablebody, $kolombody++, $data->jumlah);

			// xlsWriteNumber($tablebody, $kolombody++, $data->umpphpsl22);
			// xlsWriteNumber($tablebody, $kolombody++, $data->piutang);
			// xlsWriteNumber($tablebody, $kolombody++, $data->penjualandpp);
			// xlsWriteNumber($tablebody, $kolombody++, $data->utangppn);

			// xlsWriteNumber($tablebody, $kolombody++, $data->id_usr);

			$tablebody++;
			$nourut++;
		}

		xlsEOF();
		exit();
	}

	public function excel_rekap_unit()
	{
		$tgl_jual_Now = date("Y-m-d H:i:sa");
		$this->load->helper('exportexcel');
		$namaFile = "Rekap_penjualan_per_Unit_" . $tgl_jual_Now . ".xls";
		$judul = "Data Rekap Penjualan Per Unit";
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
		xlsWriteLabel($tablehead, $kolomhead++, "Tgl Input");
		xlsWriteLabel($tablehead, $kolomhead++, "Unit");
		xlsWriteLabel($tablehead, $kolomhead++, "Nmrpesan");
		xlsWriteLabel($tablehead, $kolomhead++, "Nmrkirim");
		// xlsWriteLabel($tablehead, $kolomhead++, "Konsumen Id");
		xlsWriteLabel($tablehead, $kolomhead++, "Konsumen Nama");
		xlsWriteLabel($tablehead, $kolomhead++, "Kode Barang");
		xlsWriteLabel($tablehead, $kolomhead++, "Nama Barang");
		xlsWriteLabel($tablehead, $kolomhead++, "Satuan");
		xlsWriteLabel($tablehead, $kolomhead++, "Harga Satuan");
		xlsWriteLabel($tablehead, $kolomhead++, "Jumlah");
		// xlsWriteLabel($tablehead, $kolomhead++, "Umpphpsl22");
		// xlsWriteLabel($tablehead, $kolomhead++, "Piutang");
		// xlsWriteLabel($tablehead, $kolomhead++, "Penjualandpp");
		// xlsWriteLabel($tablehead, $kolomhead++, "Utangppn");
		// xlsWriteLabel($tablehead, $kolomhead++, "Id Usr");

		foreach ($this->Tbl_penjualan_model->get_all_order_by_unit() as $data) {
			$kolombody = 0;

			//ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
			xlsWriteNumber($tablebody, $kolombody++, $nourut);
			xlsWriteLabel($tablebody, $kolombody++, $data->tgl_input);
			xlsWriteLabel($tablebody, $kolombody++, $data->unit);
			xlsWriteLabel($tablebody, $kolombody++, $data->nmrpesan);
			xlsWriteLabel($tablebody, $kolombody++, $data->nmrkirim);
			// xlsWriteNumber($tablebody, $kolombody++, $data->konsumen_id);
			xlsWriteLabel($tablebody, $kolombody++, $data->konsumen_nama);
			xlsWriteLabel($tablebody, $kolombody++, $data->kode_barang);
			xlsWriteLabel($tablebody, $kolombody++, $data->nama_barang);
			xlsWriteLabel($tablebody, $kolombody++, $data->satuan);
			xlsWriteNumber($tablebody, $kolombody++, $data->harga_satuan);
			xlsWriteNumber($tablebody, $kolombody++, $data->jumlah);
			// xlsWriteNumber($tablebody, $kolombody++, $data->id_usr);
			$tablebody++;
			$nourut++;
		}

		xlsEOF();
		exit();
	}

	public function excel_rekap_konsumen()
	{
		$tgl_jual_Now = date("Y-m-d H:i:sa");
		$this->load->helper('exportexcel');
		$namaFile = "Rekap_penjualan_per_konsumen_" . $tgl_jual_Now . ".xls";
		$judul = "Data Rekap Penjualan Per konsumen";
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
		xlsWriteLabel($tablehead, $kolomhead++, "Tgl Input");
		xlsWriteLabel($tablehead, $kolomhead++, "Konsumen Nama");
		xlsWriteLabel($tablehead, $kolomhead++, "Unit");
		xlsWriteLabel($tablehead, $kolomhead++, "Nmrpesan");
		xlsWriteLabel($tablehead, $kolomhead++, "Nmrkirim");
		// xlsWriteLabel($tablehead, $kolomhead++, "Konsumen Id");
		xlsWriteLabel($tablehead, $kolomhead++, "Kode Barang");
		xlsWriteLabel($tablehead, $kolomhead++, "Nama Barang");
		xlsWriteLabel($tablehead, $kolomhead++, "Satuan");
		xlsWriteLabel($tablehead, $kolomhead++, "Harga Satuan");
		xlsWriteLabel($tablehead, $kolomhead++, "Jumlah");
		// xlsWriteLabel($tablehead, $kolomhead++, "Umpphpsl22");
		// xlsWriteLabel($tablehead, $kolomhead++, "Piutang");
		// xlsWriteLabel($tablehead, $kolomhead++, "Penjualandpp");
		// xlsWriteLabel($tablehead, $kolomhead++, "Utangppn");
		// xlsWriteLabel($tablehead, $kolomhead++, "Id Usr");

		foreach ($this->Tbl_penjualan_model->get_all_order_by_konsumen_nama() as $data) {
			$kolombody = 0;

			//ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
			xlsWriteNumber($tablebody, $kolombody++, $nourut);
			xlsWriteLabel($tablebody, $kolombody++, $data->tgl_input);
			xlsWriteLabel($tablebody, $kolombody++, $data->konsumen_nama);
			xlsWriteLabel($tablebody, $kolombody++, $data->unit);
			xlsWriteLabel($tablebody, $kolombody++, $data->nmrpesan);
			xlsWriteLabel($tablebody, $kolombody++, $data->nmrkirim);
			// xlsWriteNumber($tablebody, $kolombody++, $data->konsumen_id);
			xlsWriteLabel($tablebody, $kolombody++, $data->kode_barang);
			xlsWriteLabel($tablebody, $kolombody++, $data->nama_barang);
			xlsWriteLabel($tablebody, $kolombody++, $data->satuan);
			xlsWriteNumber($tablebody, $kolombody++, $data->harga_satuan);
			xlsWriteNumber($tablebody, $kolombody++, $data->jumlah);
			// xlsWriteNumber($tablebody, $kolombody++, $data->id_usr);
			$tablebody++;
			$nourut++;
		}

		xlsEOF();
		exit();
	}


	public function excel_rekap_barang()
	{
		$tgl_jual_Now = date("Y-m-d H:i:sa");
		$this->load->helper('exportexcel');
		$namaFile = "Rekap_penjualan_per_barang_" . $tgl_jual_Now . ".xls";
		$judul = "Data Rekap Penjualan Per barang";
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
		xlsWriteLabel($tablehead, $kolomhead++, "Tgl Input");
		xlsWriteLabel($tablehead, $kolomhead++, "Kode Barang");
		xlsWriteLabel($tablehead, $kolomhead++, "Nama Barang");
		xlsWriteLabel($tablehead, $kolomhead++, "Konsumen Nama");
		xlsWriteLabel($tablehead, $kolomhead++, "Unit");
		xlsWriteLabel($tablehead, $kolomhead++, "Nmrpesan");
		xlsWriteLabel($tablehead, $kolomhead++, "Nmrkirim");
		// xlsWriteLabel($tablehead, $kolomhead++, "Konsumen Id");
		xlsWriteLabel($tablehead, $kolomhead++, "Satuan");
		xlsWriteLabel($tablehead, $kolomhead++, "Harga Satuan");
		xlsWriteLabel($tablehead, $kolomhead++, "Jumlah");
		// xlsWriteLabel($tablehead, $kolomhead++, "Umpphpsl22");
		// xlsWriteLabel($tablehead, $kolomhead++, "Piutang");
		// xlsWriteLabel($tablehead, $kolomhead++, "Penjualandpp");
		// xlsWriteLabel($tablehead, $kolomhead++, "Utangppn");
		// xlsWriteLabel($tablehead, $kolomhead++, "Id Usr");

		foreach ($this->Tbl_penjualan_model->get_all_order_by_barang() as $data) {
			$kolombody = 0;

			//ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
			xlsWriteNumber($tablebody, $kolombody++, $nourut);
			xlsWriteLabel($tablebody, $kolombody++, $data->tgl_input);
			xlsWriteLabel($tablebody, $kolombody++, $data->kode_barang);
			xlsWriteLabel($tablebody, $kolombody++, $data->nama_barang);
			xlsWriteLabel($tablebody, $kolombody++, $data->konsumen_nama);
			xlsWriteLabel($tablebody, $kolombody++, $data->unit);
			xlsWriteLabel($tablebody, $kolombody++, $data->nmrpesan);
			xlsWriteLabel($tablebody, $kolombody++, $data->nmrkirim);
			// xlsWriteNumber($tablebody, $kolombody++, $data->konsumen_id);
			xlsWriteLabel($tablebody, $kolombody++, $data->satuan);
			xlsWriteNumber($tablebody, $kolombody++, $data->harga_satuan);
			xlsWriteNumber($tablebody, $kolombody++, $data->jumlah);
			// xlsWriteNumber($tablebody, $kolombody++, $data->id_usr);
			$tablebody++;
			$nourut++;
		}

		xlsEOF();
		exit();
	}


	public function rekap($uuid_konsumen = null)
	{
		// print_r("rekap");
		// print_r("<br/>");
		// print_r($uuid_konsumen);
		// die;

		if (isset($uuid_konsumen)) {
			if ($uuid_konsumen == "semua") {
				$Tbl_penjualan = $this->Tbl_penjualan_model->get_all();
				$data_selection = "semua";
				$nama_konsumen_selection = "semua";
			} else {
				$Tbl_penjualan = $this->Tbl_penjualan_model->get_all_by_uuid_konsumen($uuid_konsumen);
				$data_selection = $uuid_konsumen;

				$sql_uuid_konsumen = "SELECT * FROM `sys_konsumen` WHERE `uuid_konsumen`='$uuid_konsumen'";
				$nama_konsumen_selection = $this->db->query($sql_uuid_konsumen)->row()->nama_konsumen;

				// $nama_konsumen_selection = $get_nama_konsumen;
			}
		} else {
			if ($this->input->post('uuid_konsumen', TRUE) == null) {
				$Tbl_penjualan = $this->Tbl_penjualan_model->get_all();
				$data_selection = "semua";
				$nama_konsumen_selection = "semua";
			} else {
				redirect(site_url('tbl_penjualan/rekap/' . $this->input->post('uuid_konsumen', TRUE)));
			}
		}




		$start = 0;
		// print_r($Tbl_pembelian);
		// print_r("<br/>");
		// print_r("<br/>");

		$data = array(
			'Tbl_penjualan_data' => $Tbl_penjualan,
			// 'q' => $q,
			// 'pagination' => $this->pagination->create_links(),
			// 'total_rows' => $config['total_rows'],
			'start' => $start,

			'action_cari_konsumen' => site_url('tbl_penjualan/rekap'),
			'data_selection' => $data_selection,
			'nama_konsumen_selection' => $nama_konsumen_selection,
		);


		// $this->load->view('anekadharma/tbl_penjualan/tbl_penjualan_list', $data);		
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_list_rekap', $data);
	}



	public function jurnal_penjualan()
	{

		$Tbl_penjualan = $this->Tbl_penjualan_model->get_all_group_by_tgl_jual_nmrpesan_nmr_kirim();



		$data = array(
			'Tbl_penjualan_data' => $Tbl_penjualan,
			// 'q' => $q,
			// 'pagination' => $this->pagination->create_links(),
			// 'total_rows' => $config['total_rows'],
		);


		// $this->load->view('anekadharma/tbl_penjualan/tbl_penjualan_list', $data);		
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_list_jurnal', $data);
	}


	public function input_kode_akun($nmrkirim = null)
	{

		// Update field kode_akun by spop ==> open form input kode akun

		$data_per_nmrkirim = $this->Tbl_penjualan_model->get_all_by_nmr_kirim($nmrkirim);

		// // $Tbl_pembelian = $this->Tbl_pembelian_model->get_by_spop($spop);
		// $Tbl_pembelian = $this->Tbl_penjualan_model->get_by_spop($data_per_uuidspop->spop);

		// // SELECT `status_spop` FROM `tbl_pembelian` WHERE `uuid_spop`="53d056417ed111ef95300021ccc9061e";



		// $start = 0;
		$data = array(
			'Tbl_penjualan_data' => $data_per_nmrkirim,
			'nmrkirim' => $nmrkirim,
			'action' => site_url('Tbl_penjualan/update_kode_akun/' . $nmrkirim),
			'button' => 'Simpan Kode AKun',
			// 'start' => $start,
		);
		// print_r($data);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_list_per_nmrkirim', $data);
	}

	public function update_kode_akun($nmrkirim = null, $Tgl_JUAL = null)
	{

		// print_r($this->input->post('kode_akun', TRUE));
		// print_r("<br/>");


		// ================ NOTE SETTING KODE AKUN UNTUK TRANSAKSI PENJUALAN =========

		// dan 
		// untuk penjualan otomatis 2 data di buku besar:
		// 1. kode akun terpilih ( 11101, 41101 dan 21201 ) masuk kredit
		// 2. 11301 ( debet )

		// ================ END OF NOTE SETTING KODE AKUN UNTUK TRANSAKSI PENJUALAN =========

		// Cek data di buku_besar
		$data_Penjualan_by_nmr_kirim_tgl_jual = $this->Tbl_penjualan_model->get_all_by_nmr_kirim_TGL_JUAL($nmrkirim, $Tgl_JUAL);

		// print_r($data_Pembelian_by_uuid_spop);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");

		// GET id_buku_besar , jika belum ada maka insert , jika sudah ada maka update di 

		$GET_TOTAL_PENJUALAN = 0;
		$GET_tanggal_PENJUALAN = null;
		$GET_nmrkirim_PENJUALAN = null;
		foreach ($data_Penjualan_by_nmr_kirim_tgl_jual as $list_data) {


			// print_r($list_data->tgl_jual);
			// print_r("<br/>");
			// if ($GET_tanggal_PENJUALAN OR $GET_tanggal_PENJUALAN <> null) {
			// } else {
			// $GET_tanggal_PENJUALAN = $list_data->tgl_jual;
			// }

			$GET_tanggal_PENJUALAN = date("Y-m-d H:i:s", strtotime($list_data->tgl_jual, TRUE));

			// print_r($GET_tanggal_PENJUALAN);
			// print_r("<br/>");


			if ($GET_nmrkirim_PENJUALAN or $GET_nmrkirim_PENJUALAN <> null) {
			} else {
				$GET_nmrkirim_PENJUALAN = $list_data->nmrkirim;
			}


			$Harga_TOtal = $list_data->jumlah * $list_data->harga_satuan;
			$GET_TOTAL_PENJUALAN = $GET_TOTAL_PENJUALAN + $Harga_TOtal;

			if ($GET_ID_buku_besar) {
			} else {
				$GET_ID_buku_besar = $list_data->id_buku_besar;
			}
		}



		if ($list_data->id_buku_besar or $list_data->id_buku_besar > 0) {
			// print_r("ada ID");
			// proses update di tabel buku besar

			$data = array(
				// 'uuid_buku_besar' => $this->input->post('uuid_buku_besar', TRUE),
				'tanggal' => $GET_tanggal_PENJUALAN,
				'kode_akun' => $this->input->post('kode_akun', TRUE),
				'keterangan' => "Penjualan Nomor Kirim" . $GET_nmrkirim_PENJUALAN . " " . $this->input->post('kode_bb', TRUE),
				'pl' => $this->input->post('kode_pl', TRUE),
				'kode' => $this->input->post('kode_bb', TRUE),
				'debet' => $GET_TOTAL_PENJUALAN,
				// 'kredit' => $GET_TOTAL_PENJUALAN,
				// 'saldo' => $this->input->post('saldo', TRUE),
			);
			$this->Buku_besar_model->update($GET_ID_buku_besar, $data);
		} else {
			// print_r("TIDAK ADA ada ID");
			// Insert data baru di tabel buku besar
			$data = array(
				// 'uuid_buku_besar' => $this->input->post('uuid_buku_besar', TRUE),
				'tanggal' => $GET_tanggal_PENJUALAN,
				'kode_akun' => $this->input->post('kode_akun', TRUE),
				'keterangan' => "Penjualan Nomor Kirim" . $GET_nmrkirim_PENJUALAN . " " . $this->input->post('kode_bb', TRUE),
				'pl' => $this->input->post('kode_pl', TRUE),
				'kode' => $this->input->post('kode_bb', TRUE),
				'debet' => $GET_TOTAL_PENJUALAN,
				// 'kredit' => $GET_TOTAL_PENJUALAN,
				// 'saldo' => $this->input->post('saldo', TRUE),
			);

			$GET_id_buku_besar = $this->Buku_besar_model->insert($data);
		}



		$data = array(
			'kode_akun' => $this->input->post('kode_akun', TRUE),
			'kode_pl' => $this->input->post('kode_pl', TRUE),
			'kode_bb' => $this->input->post('kode_bb', TRUE),
			'id_buku_besar' => $GET_id_buku_besar,
		);

		// print_r($data);
		// die;

		$this->Tbl_penjualan_model->update_by_nmrkirim($nmrkirim, $data);

		redirect(site_url('Tbl_penjualan/jurnal_penjualan/'));
	}


	public function ubah_kode_akun($nmrkirim = null)
	{

		$data_per_nmrkirim = $this->Tbl_penjualan_model->get_all_by_nmr_kirim($nmrkirim);

		$sql = "SELECT `nmrkirim`,`kode_akun`,`kode_pl`,`kode_bb` FROM `tbl_penjualan` WHERE `nmrkirim`='$nmrkirim' GROUP by `nmrkirim`,`kode_akun`";

		// $this->db->query($sql)->result();
		// print_r($this->db->query($sql)->row()->kode_akun);

		$get_kode_akun = $this->db->query($sql)->row()->kode_akun;
		$get_kode_pl = $this->db->query($sql)->row()->kode_pl;
		$get_kode_bb = $this->db->query($sql)->row()->kode_bb;
		// die;

		// $start = 0;
		$data = array(
			'Tbl_penjualan_data' => $data_per_nmrkirim,
			'nmrkirim' => $nmrkirim,
			'action' => site_url('Tbl_penjualan/update_kode_akun/' . $nmrkirim),
			'button' => 'Update Kode AKun',
			// 'start' => $start,
			'get_kode_akun' => $get_kode_akun,
			'get_kode_pl' => $get_kode_pl,
			'get_kode_bb' => $get_kode_bb,
		);
		// print_r($data);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/Tbl_penjualan/adminlte310_tbl_penjualan_list_per_nmrkirim', $data);
	}
}

/* End of file Tbl_penjualan.php */
/* Location: ./application/controllers/Tbl_penjualan.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-09-03 14:59:44 */
/* http://harviacode.com */