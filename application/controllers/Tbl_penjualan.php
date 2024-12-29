<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Tbl_penjualan extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_login();
		$this->load->model(array('Tbl_penjualan_model', 'Tbl_pembelian_model', 'Sys_konsumen_model', 'Sys_unit_model', 'Sys_nama_barang_model', 'Persediaan_model'));
		$this->load->library('form_validation');
		$this->load->library('form_validation');
		$this->load->library('datatables');
		$this->load->library('Pdf');
		$this->load->helper(array('nominal'));
		// $this->load->helper('number');
	}






	public function index()
	{


		$Tbl_penjualan = $this->Tbl_penjualan_model->get_all_group_by_tgl_jual_nmrpesan_nmr_kirim();
		$start = 0;
		// print_r($Tbl_penjualan);
		// print_r("<br/>");
		// print_r("<br/>");

		$data = array(
			'Tbl_penjualan_data' => $Tbl_penjualan,
			// 'q' => $q,
			// 'pagination' => $this->pagination->create_links(),
			// 'total_rows' => $config['total_rows'],
			'start' => $start,
		);


		// $this->load->view('anekadharma/tbl_penjualan/tbl_penjualan_list', $data);		
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_list', $data);
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

			$this->db->where('uuid_konsumen', $this->input->post('uuid_konsumen', TRUE));
			//$this->db->where('password',  $test);
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
						'unit' => $unit,
						'satuan' => $satuan,

						'harga_satuan' => preg_replace("/[^0-9]/", "", $hargasatuan),
						'id_usr' => 1,
					);

					// print_r($data);
					// print_r("<br/>");
					// print_r("<br/>");

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

		// $Data_stock = $this->Persediaan_model->get_all();
		// print_r($Data_stock);
		// die;

		// print_r($id_proses);
		// print_r("<br/>");
		// print_r($this->input->post('tgl_jual', TRUE));
		// print_r("<br/>");
		// print_r($this->input->post('uuid_konsumen', TRUE));
		// print_r("<br/>");
		// print_r($this->input->post('nmrpesan', TRUE));
		// print_r("<br/>");
		// print_r($this->input->post('nmrkirim', TRUE));

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
			'action' => site_url('tbl_penjualan/create_action_simpan_barang/'),
			'tgl_jual' => $this->input->post('tgl_jual', TRUE),
			'nmrpesan' => $this->input->post('nmrpesan', TRUE),
			'nmrkirim' => $this->input->post('nmrkirim', TRUE),
			'uuid_konsumen' => $uuid_konsumen,
			'nama_konsumen' => $data_nama_konsumen,

		);
		// print_r($data);

		// $this->load->view('anekadharma/tbl_penjualan/tbl_penjualan_form', $data);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_form_input_barang', $data);
	}


	public function create_action_simpan_barang($uuid_penjualan = null, $id_persediaan_barang = null)
	{

		// print_r("create_action_simpan_barang");
		// print_r("<br/>");
		// print_r($uuid_penjualan);
		// print_r("<br/>");
		// print_r($id_persediaan_barang);
		// print_r("<br/>");
		// die;

		// print_r("create_action_simpan_barang");
		// die;
		// print_r("<br/>");

		// print_r("tgl_jual : ");
		// print_r($this->input->post('tgl_jual', TRUE));
		// print_r("<br/>");
		// print_r("uuid_konsumen : ");
		// print_r($this->input->post('uuid_konsumen', TRUE));
		// print_r("<br/>");
		// print_r("nmrpesan : ");
		// print_r($this->input->post('nmrpesan', TRUE));
		// print_r("<br/>");
		// print_r("nmrkirim : ");
		// print_r($this->input->post('nmrkirim', TRUE));
		// print_r("<br/>");
		// print_r("id_pembelian_barang : ");
		// print_r($id_proses);
		// print_r("<br/>");
		// print_r("harga_satuan_beli : ");
		// print_r($this->input->post('harga_satuan_beli', TRUE));
		// print_r("<br/>");
		// print_r("jumlah : ");
		// print_r($this->input->post('jumlah', TRUE));
		// print_r("<br/>");
		// print_r("<br/>");

		// $x_1 = $id_proses;

		// AMBIL DATA DARI PEMBELIAN
		// $sql = "SELECT * FROM `tbl_pembelian` WHERE `id`='$id_proses'";
		// $data_barang = $this->db->query($sql)->row();

		// AMBIL DATA DARI PERSEDIAAN
		$sql = "SELECT * FROM `persediaan` WHERE `id`='$id_persediaan_barang'";
		$data_barang = $this->db->query($sql)->row();


		// print_r($data_barang);
		// print_r("<br/>");
		// print_r("<br/>");
		// die;



		// print_r($data_barang);
		// die;

		// [uuid_pembelian] => 5d4b4221756411ef88650021ccc9061e [uuid_barang] => ae37d726715911ef9fe90021ccc906hh [tgl_po] => 2024-09-01 00:00:00 [nmrsj] => [nmrfakturkwitansi] => 1 [nmrbpb] => [uuid_spop] => 54548eeb756411ef88650021ccc9061e [spop] => 1 [uuid_supplier] => 458c5d176b2311ef80a80021ccc9061e [supplier_kode] => [supplier_nama] => Supplier 1 [uraian] => Buku [jumlah] => 2 [satuan] => rim [uuid_konsumen] => b728e22d6b5811ef80a80021ccc9061e [konsumen] => pj-atk [harga_satuan] => 100000 [harga_total] => 0 [statuslu] => L [kas_bank] => kas [tgl_bayar] => 0000-00-00 00:00:00 [id_usr] => 1 )

		// print_r($data_barang->id);
		// print_r("<br/>");
		// print_r($data_barang->uuid_barang);
		// print_r("<br/>");
		// print_r($data_barang->uraian);
		// print_r("<br/>");
		// print_r($data_barang->satuan);
		// print_r("<br/>");


		// die;

		$uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
		$data_konsumen = $this->Sys_konsumen_model->get_by_uuid_konsumen($uuid_konsumen);


		if (empty($data_konsumen)) {
			$data_konsumen = $this->Sys_unit_model->get_by_uuid_unit($uuid_konsumen);
			$data_nama_konsumen = $data_konsumen->nama_unit;
		} else {
			$data_nama_konsumen = $data_konsumen->nama_konsumen;
		}

		// print_r($uuid_konsumen);
		// print_r("<br/>");
		// print_r($data_konsumen);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r($data_nama_konsumen);
		// print_r("<br/>");
		// print_r("<br/>");

		// die;

		// $uuid_unit_selected = $this->input->post('uuid_unit', TRUE);
		// $data_unit = $this->Sys_unit_model->get_by_uuid_unit($uuid_unit_selected);
		// $data_nama_unit = $data_unit->nama_unit;

		// print_r($data_nama_unit);
		// print_r("<br/>");

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
				'uuid_konsumen' => $uuid_konsumen,
				'konsumen_nama' => $data_nama_konsumen,
				// 'uuid_barang' => $data_barang->uuid_pembelian, //uuid_barang berdasarkan uuid_pembelian karena beda harga (barang sama, waktu beda belanja harga beda)
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
				'harga_satuan' => preg_replace("/[^0-9]/", "", $this->input->post('harga_satuan_beli', TRUE)),
				'total_nominal' =>  preg_replace("/[^0-9]/", "", $this->input->post('jumlah', TRUE)) * preg_replace("/[^0-9]/", "", $this->input->post('harga_satuan_beli', TRUE)),
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
				'uuid_konsumen' => $uuid_konsumen,
				'konsumen_nama' => $data_nama_konsumen,
				// 'uuid_barang' => $data_barang->uuid_pembelian, //uuid_barang berdasarkan uuid_pembelian karena beda harga (barang sama, waktu beda belanja harga beda)
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
				'harga_satuan' => preg_replace("/[^0-9]/", "", $this->input->post('harga_satuan_beli', TRUE)),
				'total_nominal' =>  preg_replace("/[^0-9]/", "", $this->input->post('jumlah', TRUE)) * preg_replace("/[^0-9]/", "", $this->input->post('harga_satuan_beli', TRUE)),
				'id_usr' => 1,
			);
			$this->Tbl_penjualan_model->insert_add_barang($data);
		}

		// die;
		// =========SIMPAN DATA==================


		// redirect("kasir_penjualan/".$uuid_penjualan);
		redirect(site_url('tbl_penjualan/kasir_penjualan/' . $uuid_penjualan . '/' . $tgl_jual_X . '/' . $this->input->post('nmrkirim', TRUE) ));
	}

	public function kasir_penjualan($uuid_penjualan, $tgl_jual, $nmrkirim)
	{

		// --------------TAMPILKAN DATA INPUT PENJUALAN SESUAI UUID_NOMOR PESAN yang barusan di inputkan ----------------------
		$data_penjualan_per_uuid_penjualan = $this->Tbl_penjualan_model->get_all_by_tgl_jual_nmrkirim($tgl_jual, $nmrkirim);

		// print_r($data_penjualan_per_uuid_penjualan);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");

		$data_penjualan_per_uuid_penjualan_first_row = $this->Tbl_penjualan_model->get_all_by_tgl_jual_nmrkirim_first_row($tgl_jual, $nmrkirim);

		

		$data = array(
			'data_penjualan_per_uuid_penjualan' => $data_penjualan_per_uuid_penjualan,
			'button' => 'Simpan',
			'action' => site_url('tbl_penjualan/create_action_simpan_barang/'),
			'tgl_jual' => $data_penjualan_per_uuid_penjualan_first_row->tgl_jual,
			'nmrpesan' => $data_penjualan_per_uuid_penjualan_first_row->nmrpesan,
			'nmrkirim' => $data_penjualan_per_uuid_penjualan_first_row->nmrkirim,
			'uuid_konsumen' => $data_penjualan_per_uuid_penjualan_first_row->uuid_konsumen,
			'nama_konsumen' => $data_penjualan_per_uuid_penjualan_first_row->konsumen_nama,
			'uuid_penjualan' => $uuid_penjualan,

		);

		// print_r($data);
		// die;

		// $this->load->view('anekadharma/tbl_penjualan/tbl_penjualan_form', $data);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_form_input_barang', $data);
	}


	public function cetak_penjualan_per_uuid_penjualan($uuid_penjualan = null,$date_tgl_jual=null,$nmrkirim=null)
	{

		// 2.a. PERSIAPAN LIBRARY
		// $this->load->library('PdfGenerator');


		// $data_master_penjualan_per_uuidpenjualan = $this->Tbl_penjualan_model->get_all_by_uuid_penjualan_first_row($uuid_penjualan);
		$data_master_penjualan_per_uuidpenjualan = $this->Tbl_penjualan_model->get_all_by_uuid_penjualan_tgl_jual_nmrkirim_first_row($uuid_penjualan,$date_tgl_jual,$nmrkirim);

		// print_r($data_master_penjualan_per_uuidpenjualan);		
		// print_r($data_master_penjualan_per_uuidpenjualan->nmrpesan);
		// print_r(date("d M Y", strtotime($data_master_penjualan_per_uuidpenjualan->tgl_jual)));
		// print_r($data_master_penjualan_per_uuidpenjualan->konsumen_nama);
		// die;


		// $date_po = date("d M Y", strtotime($data_master_penjualan_per_uuidpenjualan->tgl_jual));
		// die;

		// 2.b. PERSIAPAN DATA
		$data = array(
			'data_penjualan' => $this->Tbl_penjualan_model->get_all_by_tgl_jual_nmrkirim($date_tgl_jual,$nmrkirim),
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

	public function delete($id)
	{
		$row = $this->Tbl_penjualan_model->get_by_id($id);

		if ($row) {
			$this->Tbl_penjualan_model->delete($id);
			$this->session->set_flashdata('message', 'Delete Record Success');
			redirect(site_url('tbl_penjualan'));
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
}

/* End of file Tbl_penjualan.php */
/* Location: ./application/controllers/Tbl_penjualan.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-09-03 14:59:44 */
/* http://harviacode.com */