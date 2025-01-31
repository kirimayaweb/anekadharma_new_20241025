<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Persediaan extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_login();
		$this->load->model(array('Persediaan_model', 'Sys_nama_barang_model'));
		$this->load->library('form_validation');
		$this->load->library('datatables');
	}


	// 
	public function cek_barang_pembelian_tidakADA_di_persediaan()
	{
		$sql = "SELECT * FROM `tbl_pembelian` order by id";
		// $sql = "SELECT * FROM `persediaan_all` order by id";

		foreach ($this->db->query($sql)->result() as $m) {
			print_r($m->uuid_spop);
			print_r(" - ");
			print_r($m->spop);
			print_r(" - ");
			print_r($m->uraian);
			print_r(" - ");
			print_r($m->jumlah);
			print_r(" ----> ");

			// cek di persediaan
			// $sql = "SELECT `id`,`uuid_persediaan` FROM `persediaan` where `uuid_spop`='$m->uuid_spop' ";

			// $data_persediaan=$this->db->query($sql)->row();
			// print_r($data_persediaan->id);

			$this->db->where('uuid_spop', $m->uuid_spop);
			$data_persediaan = $this->db->get('persediaan');

			if ($data_persediaan->num_rows() > 0) {
				print_r("Ada data");
			} else {
				print_r("Tidak ada data");

				// PROSES INPUT DATA KE PERSEDIAAN

				$Total_Nilai_Persediaan = $m->jumlah * $m->harga_satuan;
				$data_persediaan = array(
					// 'id' => $this->input->post('id', TRUE),
					'tanggal' => $m->date_input,
					'tanggal_beli' => $m->tgl_po,
					// 'kode' => $this->input->post('kode', TRUE),
					'uuid_barang' => $m->uuid_barang,
					'namabarang' => $m->uraian,
					'satuan' => $m->satuan,
					'hpp' => $m->harga_satuan,
					'sa' => $m->jumlah,
					'uuid_spop' => $m->uuid_spop,
					'spop' => $m->spop,
					// 'beli' => $this->input->post('beli', TRUE),
					// 'tuj' => $this->input->post('tuj', TRUE),
					// 'tgl_keluar' => $this->input->post('tgl_keluar', TRUE),
					// 'sekret' => $this->input->post('sekret', TRUE),
					// 'cetak' => $this->input->post('cetak', TRUE),
					// 'grafikita' => $this->input->post('grafikita', TRUE),
					// 'dinas_umum' => $this->input->post('dinas_umum', TRUE),
					// 'atk_rsud' => $this->input->post('atk_rsud', TRUE),
					// 'ppbmp_kbs' => $this->input->post('ppbmp_kbs', TRUE),
					// 'kbs' => $this->input->post('kbs', TRUE),
					// 'ppbmp' => $this->input->post('ppbmp', TRUE),
					// 'medis' => $this->input->post('medis', TRUE),
					// 'siiplah_bosda' => $this->input->post('siiplah_bosda', TRUE),
					// 'sembako' => $this->input->post('sembako', TRUE),
					// 'fc_gose' => $this->input->post('fc_gose', TRUE),
					// 'fc_manding' => $this->input->post('fc_manding', TRUE),
					// 'fc_psamya' => $this->input->post('fc_psamya', TRUE),
					'total_10' => $m->jumlah,
					'nilai_persediaan' => $Total_Nilai_Persediaan,
				);

				$this->Persediaan_model->insert($data_persediaan);

				print_r($data_persediaan);
				// die;


			}
			print_r("<br/>");
			print_r("<br/>");
		}
	}

	// -------------- PROSES UPDATE DATA PERSEDIAAN (STOCK) -------------- 

	public function cek_nominal_persediaan()
	{
		$sql = "SELECT `id`,`uuid_persediaan`,`nilai_persediaan` FROM `persediaan`";

		$start = 0;
		foreach ($this->db->query($sql)->result() as $m) {
			print_r($m->id);
			print_r(" : ");
			print_r($m->nilai_persediaan);
			// print_r(" :=> ");
			// print_r($start);
			print_r(" :=====> ");
			$start = $start + $m->nilai_persediaan;
			print_r($start);
			print_r("<br/>");
		}
	}


	public function cek_uuid_persediaan_kosong()
	{
		$sql = "SELECT `id`,`uuid_persediaan`,`nilai_persediaan` FROM `persediaan` where `uuid_persediaan`='' ";

		$start = 0;
		foreach ($this->db->query($sql)->result() as $m) {

			$get_id = $m->id;
			print_r($m->id);
			print_r(" : ");
			print_r($m->nilai_persediaan);
			// print_r(" :=> ");
			// print_r($start);
			print_r(" :=====> ");
			$start = $start + $m->nilai_persediaan;
			print_r($start);
			print_r("<br/>");

			// id dengan uuid_persediaan = kosong di update di isi dengan uuid
			$sql_update_uuid_persediaan = "UPDATE `persediaan` SET `uuid_persediaan`=replace(uuid(),'-','') WHERE `id`='$get_id'";
			$this->db->query($sql_update_uuid_persediaan);


			print_r($get_id);
			print_r("====> isi : ");

			$sql_data_id = "SELECT `uuid_persediaan` FROM `persediaan` WHERE `id`='$get_id'";
			// $this->db->query($sql_data_id)->row();
			print_r($this->db->query($sql_data_id)->row()->uuid_persediaan);
			print_r("<br/>");

			usleep(500000);
		}
	}


	public function moving_persediaan_master_from_persediaan_update_tanggal_beli_by_spop()
	{
		$sql = "SELECT * FROM `persediaan_master_new` order by id";
		// $sql = "SELECT * FROM `persediaan_all` order by id";

		foreach ($this->db->query($sql)->result() as $m) {


			// print;
			// $date_persediaan = date("Y-m-d", strtotime($m->tanggal));


			$tahun_process = substr($m->tanggal, 6);

			print_r($tahun_process);
			print_r("<br/>");


			$bulan_process = substr($m->tanggal, 3, 2);

			print_r($bulan_process);
			print_r("<br/>");

			$day_process = substr($m->tanggal, 0, 2);

			print_r($day_process);
			print_r("<br/>");


			$date_process = $tahun_process . "-" . $bulan_process . "-" . $day_process;

			print_r($date_process);
			print_r("<br/>");

			$date_persediaan = date("Y-m-d", strtotime($date_process));

			print_r($date_persediaan);
			print_r("<br/>");



			if ($m->spop) {
				print_r("ada spop");
				print_r("<br/>");

				$date_beli_process = $tahun_process . "-" . $bulan_process . "-01";
				$date_beli = date("Y-m-d", strtotime($date_beli_process));
				print_r($date_beli);
				print_r("<br/>");
				print_r("<br/>");
				print_r("<br/>");


				$data = array(
					// 'id' => $this->input->post('id', TRUE),
					'tanggal' => $date_persediaan,
					'tanggal_new' => $date_persediaan,
					'kode' => $m->kode,
					'namabarang' => $m->namabarang,
					'satuan' => $m->satuan,
					'hpp' => $m->hpp,
					'sa' => $m->sa,
					'tanggal_beli' => $date_beli,
					'spop' => $m->spop,
					'beli' => $m->beli,
					'tuj' => $m->tuj,
					'tgl_keluar' => $m->tgl_keluar,
					'sekret' => $m->sekret,
					'cetak' => $m->cetak,
					'grafikita' => $m->grafikita,
					'dinas_umum' => $m->dinas_umum,
					'atk_rsud' => $m->atk_rsud,
					'ppbmp_kbs' => $m->ppbmp_kbs,
					'kbs' => $m->kbs,
					'ppbmp' => $m->ppbmp,
					'medis' => $m->medis,
					'siiplah_bosda' => $m->siiplah_bosda,
					'sembako' => $m->sembako,
					'fc_gose' => $m->fc_gose,
					'fc_manding' => $m->fc_manding,
					'fc_psamya' => $m->fc_psamya,
					'total_10' => $m->total_10,
					'nilai_persediaan' => $m->nilai_persediaan,
				);
			} else {


				$data = array(
					// 'id' => $this->input->post('id', TRUE),
					'tanggal' => $date_persediaan,
					'tanggal_new' => $date_persediaan,
					'kode' => $m->kode,
					'namabarang' => $m->namabarang,
					'satuan' => $m->satuan,
					'hpp' => $m->hpp,
					'sa' => $m->sa,
					'spop' => $m->spop,
					'beli' => $m->beli,
					'tuj' => $m->tuj,
					'tgl_keluar' => $m->tgl_keluar,
					'sekret' => $m->sekret,
					'cetak' => $m->cetak,
					'grafikita' => $m->grafikita,
					'dinas_umum' => $m->dinas_umum,
					'atk_rsud' => $m->atk_rsud,
					'ppbmp_kbs' => $m->ppbmp_kbs,
					'kbs' => $m->kbs,
					'ppbmp' => $m->ppbmp,
					'medis' => $m->medis,
					'siiplah_bosda' => $m->siiplah_bosda,
					'sembako' => $m->sembako,
					'fc_gose' => $m->fc_gose,
					'fc_manding' => $m->fc_manding,
					'fc_psamya' => $m->fc_psamya,
					'total_10' => $m->total_10,
					'nilai_persediaan' => $m->nilai_persediaan,
				);
			}


			$this->Persediaan_model->insert($data);
		}
	}


	public function Update_uuid_persediaan()
	{

		// Proses : update uuid_persediaan yang kosong karena import data dari csv dan belum ada record uuid_persediaan

		$sql = "SELECT `id`,`uuid_persediaan` FROM `persediaan` where `uuid_persediaan`='' ";

		foreach ($this->db->query($sql)->result() as $m) {
			$get_id = $m->id;



			$sql_update_uuid_persediaan = "UPDATE `persediaan` SET `uuid_persediaan`=replace(uuid(),'-','') WHERE `id`='$get_id'";
			$this->db->query($sql_update_uuid_persediaan);


			print_r($get_id);
			print_r("<br/>");

			$sql_data_id = "SELECT `uuid_persediaan` FROM `persediaan` WHERE `id`='$get_id'";
			// $this->db->query($sql_data_id)->row();
			print_r($this->db->query($sql_data_id)->row()->uuid_persediaan);



			usleep(500000);
		}
	}

	public function refresh_data_from_sys_data_barang()
	{

		$sql = "SELECT `namabarang`,`satuan` FROM `persediaan` WHERE `namabarang`<>'' GROUP by `namabarang`";

		foreach ($this->db->query($sql)->result() as $m) {

			$data_barang = $this->Sys_nama_barang_model->get_by_nama_barang($m->namabarang);

			// $sql = "UPDATE `persediaan` SET `uuid_barang`='$data_barang->uuid_barang',`kode_barang`='$data_barang->kode_barang' WHERE `namabarang`= '$m->namabarang'";

			// $this->db->set('uuid_persediaan', replace(uuid(),'-',''), true);
			$this->db->set('uuid_barang', $data_barang->uuid_barang, true);
			$this->db->set('kode_barang', $data_barang->kode_barang, true);
			$this->db->where('namabarang', $m->namabarang);
			$this->db->update('persediaan');
		}

		print_r("Selesai update uuid_barang dan kode_barang di tabel persediaan berdasarkan uuid_barang dan kode barang dari sys_data_barang");
	}

	public function cek_pembelian_persediaan()
	{
		$sql = "SELECT * FROM `tbl_pembelian` GROUP by `uuid_barang`";
		$start = 0;
		foreach ($this->db->query($sql)->result() as $list_data) {

			$GET_uuid_barang_cek = $list_data->uuid_barang;

			$this->db->where('uuid_barang', $GET_uuid_barang_cek);
			$get_data_persediaan = $this->db->get('persediaan');

			$data_persediaan = $get_data_persediaan->row();


			if ($get_data_persediaan->num_rows() > 0) {

				print_r($start++);
				print_r(" - PEMBELIAN: ");
				print_r($list_data->uuid_barang);
				print_r(" : ");
				print_r($list_data->uraian);
				print_r(" ----- ");
				print_r($data_persediaan->uuid_barang);
				print_r(" : ");
				print_r($data_persediaan->namabarang);

				// print_r("<br/>");
			} else {
				print_r($start++);
				print_r(" - PEMBELIAN: ");
				print_r($list_data->uuid_barang);
				print_r(" : ");
				print_r($list_data->uraian);
				print_r(" ----- TIDAK ADA ");

				// Simpan Ke persediaan
				if ($list_data->kode_barang) {
					$GET_kode_barang = $list_data->kode_barang;
				} else {
					// GENERATE KODE BARANG

					$teks = $list_data->kode_barang;

					$split = explode(' ', $teks);
					foreach ($split as $kata) {
						$get_kode_barang = $get_kode_barang . substr($kata, 0, 2);
					}

					// CEK KODE APAKAH SUDAH ADA, JIKA SUDAH ADA MAKA DITAMBAHKAN NOMOR
					// query chek sys_nama_barang
					$this->db->where('kode_barang', $get_kode_barang);
					$sys_nama_barang = $this->db->get('sys_nama_barang');

					if ($sys_nama_barang->num_rows() > 0) {
						// print_r ("Sudah ada ");
						$get_kode_barang = $get_kode_barang . "_" . $sys_nama_barang->num_rows();
					}
				}


				$data = array(
					// 'id' => $this->input->post('id', TRUE),
					'tanggal' => $list_data->tgl_po,
					'uuid_barang' => $list_data->uuid_barang,

					'kode' => $GET_kode_barang,
					'kode_barang' => $GET_kode_barang,

					'namabarang' => $list_data->uraian,
					'satuan' => $list_data->satuan,
					'hpp' => $list_data->harga_satuan,
					'sa' => $list_data->jumlah,
					'spop' => $list_data->spop,
					'beli' => $list_data->jumlah,
					'tuj' => $list_data->jumlah,
					// 'tgl_keluar' => $list_data->,
					// 'sekret' => $list_data->,
					// 'cetak' => $this->input->post('cetak', TRUE),
					// 'grafikita' => $this->input->post('grafikita', TRUE),
					// 'dinas_umum' => $this->input->post('dinas_umum', TRUE),
					// 'atk_rsud' => $this->input->post('atk_rsud', TRUE),
					// 'ppbmp_kbs' => $this->input->post('ppbmp_kbs', TRUE),
					// 'kbs' => $this->input->post('kbs', TRUE),
					// 'ppbmp' => $this->input->post('ppbmp', TRUE),
					// 'medis' => $this->input->post('medis', TRUE),
					// 'siiplah_bosda' => $this->input->post('siiplah_bosda', TRUE),
					// 'sembako' => $this->input->post('sembako', TRUE),
					// 'fc_gose' => $this->input->post('fc_gose', TRUE),
					// 'fc_manding' => $this->input->post('fc_manding', TRUE),
					// 'fc_psamya' => $this->input->post('fc_psamya', TRUE),
					'total_10' => $list_data->jumlah,
					'nilai_persediaan' => $list_data->jumlah * $list_data->harga_satuan,
				);
				$this->Persediaan_model->insert($data);

				// Simpan ke sys_nama_barang

				$data = array(
					// 'uuid_barang' => $this->input->post('uuid_barang',TRUE),
					'kode_barang' => $GET_kode_barang,
					'nama_barang' => $list_data->uraian,
					'satuan' => $list_data->satuan,
					'keterangan' => "Update dari pembelian",
				);

				$this->Sys_nama_barang_model->insert($data);
			}
			print_r("<br/>");
			print_r("<br/>");
		}
	}


	public function update_id_persediaan_pembelian_penjualan()
	{
		//  ------------------ PEMBELIAN ------------------
		$sql = "SELECT * FROM `tbl_pembelian` GROUP by `uuid_barang`";

		$start = 0;
		foreach ($this->db->query($sql)->result() as $list_data) {

			$GET_uuid_barang_cek = $list_data->uuid_barang;

			$this->db->where('uuid_barang', $GET_uuid_barang_cek);
			$get_data_persediaan = $this->db->get('persediaan');
			// $data_persediaan = $get_data_persediaan->row();



			// $data = array(

			// 	'id_persediaan_barang' => $get_data_persediaan->row()->id,
			// 	'uuid_persediaan' => $get_data_persediaan->row()->uuid_persediaan,

			// );

			print_r(++$start);
			print_r(" : ");
			print_r($list_data->uraian);
			print_r(" ==> ");
			print_r($get_data_persediaan->row()->id);
			print_r("<br/>");

			// print_r($data);
			// die;
			// $this->Tbl_pembelian_model->update($this->input->post('id', TRUE), $data);
			$id_persediaan_barang = 0;

			$this->db->set('id_persediaan_barang', $get_data_persediaan->row()->id, true);
			$this->db->set('uuid_persediaan', $get_data_persediaan->row()->uuid_persediaan, true);
			$this->db->where('uuid_barang', $list_data->uuid_barang);
			// $this->db->where('id_persediaan_barang', $id_persediaan_barang);
			$this->db->update('tbl_pembelian');
		}



		print_r("Penjualan");
		print_r("<br/>");
		print_r("<br/>");
		print_r("<br/>");
		print_r("<br/>");
		print_r("<br/>");


		$sql_tbl_penjualan = "SELECT * FROM `tbl_penjualan`  order by `id_persediaan_barang` ";

		$start = 0;
		foreach ($this->db->query($sql_tbl_penjualan)->result() as $list_data) {

			print_r("Data - ");
			print_r("<br/>");
			print_r($list_data->id_persediaan_barang);
			print_r("<br/>");

			if ($list_data->id_persediaan_barang == "" or $list_data->id_persediaan_barang == 0 or $list_data->id_persediaan_barang == null) {

				print_r("ada data id kosong");
				print_r("<br/>");
				$GET_uuid_barang_cek = $list_data->uuid_barang;

				$this->db->where('uuid_barang', $GET_uuid_barang_cek);
				$get_data_persediaan = $this->db->get('persediaan');


				print_r(++$start);
				print_r(" : ");
				print_r($list_data->nama_barang);
				print_r(" ==> ");
				print_r($get_data_persediaan->row()->id);
				print_r("<br/>");



				$this->db->set('id_persediaan_barang', $get_data_persediaan->row()->id, true);
				$this->db->set('uuid_persediaan', $get_data_persediaan->row()->uuid_persediaan, true);
				$this->db->where('uuid_barang', $list_data->uuid_barang);
				$this->db->update('tbl_penjualan');
			}
		}
	}




	// -------------- END OF PROSES PENGKONDISIAN DAN UPDATE DATA PERSEDIAAN (STOCK) -------------- 


	public function index()
	{
		// $this->load->view('persediaan/persediaan_list');

		$Persediaan = $this->Persediaan_model->get_all();
		// $start = 0;
		$data = array(
			'Persediaan_data' => $Persediaan,
			// 'start' => $start,
			'action_cari' => site_url('persediaan/search'),
		);

		// print_r($data);

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/persediaan/adminlte310_persediaan_list', $data);
	}

	public function search()
	{

		// print_r($this->input->post('bulan_persediaan', TRUE));
		// print_r("<br/>");

		$from_date = date("1/m/Y", strtotime($this->input->post('bulan_persediaan', TRUE)));
		$to_date = date("t/m/Y", strtotime($this->input->post('bulan_persediaan', TRUE)));
		// $to_date = '30/09/2024';

		// $sql = "SELECT * FROM persediaan WHERE tanggal >= '" . $from_date . "' AND tanggal <= '" . $to_date . "' ORDER by id DESC";
		$sql = "SELECT * FROM persediaan WHERE `persediaan`.`tanggal` LIKE '" . $to_date . "'  ORDER by id DESC";


		// tanggal >= '" . $from_date . "' AND tanggal <= '" . $to_date . "' ORDER by id DESC";

		// print_r($from_date);
		// print_r("<br/>");
		// print_r($to_date);
		// print_r("<br/>");
		// print_r($this->db->query($sql)->result());
		// print_r("<br/>");

		// $Persediaan = $this->Persediaan_model->get_all();
		$Persediaan = $this->db->query($sql)->result();

		// $start = 0;
		$data = array(
			'Persediaan_data' => $Persediaan,
			// 'start' => $start,
			'action_cari' => site_url('persediaan/search'),
		);

		// print_r($data);

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/persediaan/adminlte310_persediaan_list', $data);
	}

	public function json()
	{
		header('Content-Type: application/json');
		echo $this->Persediaan_model->json();
	}

	public function read($id)
	{
		$row = $this->Persediaan_model->get_by_id($id);
		if ($row) {
			$data = array(
				'id' => $row->id,
				'tanggal' => $row->tanggal,
				'kode' => $row->kode,
				'namabarang' => $row->namabarang,
				'satuan' => $row->satuan,
				'hpp' => $row->hpp,
				'sa' => $row->sa,
				'spop' => $row->spop,
				'beli' => $row->beli,
				'tuj' => $row->tuj,
				'tgl_keluar' => $row->tgl_keluar,
				'sekret' => $row->sekret,
				'cetak' => $row->cetak,
				'grafikita' => $row->grafikita,
				'dinas_umum' => $row->dinas_umum,
				'atk_rsud' => $row->atk_rsud,
				'ppbmp_kbs' => $row->ppbmp_kbs,
				'kbs' => $row->kbs,
				'ppbmp' => $row->ppbmp,
				'medis' => $row->medis,
				'siiplah_bosda' => $row->siiplah_bosda,
				'sembako' => $row->sembako,
				'fc_gose' => $row->fc_gose,
				'fc_manding' => $row->fc_manding,
				'fc_psamya' => $row->fc_psamya,
				'total_10' => $row->total_10,
				'nilai_persediaan' => $row->nilai_persediaan,
			);
			$this->load->view('persediaan/persediaan_read', $data);
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(site_url('persediaan'));
		}
	}

	public function create()
	{
		$data = array(
			'button' => 'Create',
			'action' => site_url('persediaan/create_action'),
			'id' => set_value('id'),
			'tanggal' => set_value('tanggal'),
			'kode' => set_value('kode'),
			'namabarang' => set_value('namabarang'),
			'satuan' => set_value('satuan'),
			'hpp' => set_value('hpp'),
			'sa' => set_value('sa'),
			'spop' => set_value('spop'),
			'beli' => set_value('beli'),
			'tuj' => set_value('tuj'),
			'tgl_keluar' => set_value('tgl_keluar'),
			'sekret' => set_value('sekret'),
			'cetak' => set_value('cetak'),
			'grafikita' => set_value('grafikita'),
			'dinas_umum' => set_value('dinas_umum'),
			'atk_rsud' => set_value('atk_rsud'),
			'ppbmp_kbs' => set_value('ppbmp_kbs'),
			'kbs' => set_value('kbs'),
			'ppbmp' => set_value('ppbmp'),
			'medis' => set_value('medis'),
			'siiplah_bosda' => set_value('siiplah_bosda'),
			'sembako' => set_value('sembako'),
			'fc_gose' => set_value('fc_gose'),
			'fc_manding' => set_value('fc_manding'),
			'fc_psamya' => set_value('fc_psamya'),
			'total_10' => set_value('total_10'),
			'nilai_persediaan' => set_value('nilai_persediaan'),
		);
		$this->load->view('persediaan/persediaan_form', $data);
	}

	public function create_action()
	{
		$this->_rules();

		if ($this->form_validation->run() == FALSE) {
			$this->create();
		} else {
			$data = array(
				'id' => $this->input->post('id', TRUE),
				'tanggal' => $this->input->post('tanggal', TRUE),
				'kode' => $this->input->post('kode', TRUE),
				'namabarang' => $this->input->post('namabarang', TRUE),
				'satuan' => $this->input->post('satuan', TRUE),
				'hpp' => $this->input->post('hpp', TRUE),
				'sa' => $this->input->post('sa', TRUE),
				'spop' => $this->input->post('spop', TRUE),
				'beli' => $this->input->post('beli', TRUE),
				'tuj' => $this->input->post('tuj', TRUE),
				'tgl_keluar' => $this->input->post('tgl_keluar', TRUE),
				'sekret' => $this->input->post('sekret', TRUE),
				'cetak' => $this->input->post('cetak', TRUE),
				'grafikita' => $this->input->post('grafikita', TRUE),
				'dinas_umum' => $this->input->post('dinas_umum', TRUE),
				'atk_rsud' => $this->input->post('atk_rsud', TRUE),
				'ppbmp_kbs' => $this->input->post('ppbmp_kbs', TRUE),
				'kbs' => $this->input->post('kbs', TRUE),
				'ppbmp' => $this->input->post('ppbmp', TRUE),
				'medis' => $this->input->post('medis', TRUE),
				'siiplah_bosda' => $this->input->post('siiplah_bosda', TRUE),
				'sembako' => $this->input->post('sembako', TRUE),
				'fc_gose' => $this->input->post('fc_gose', TRUE),
				'fc_manding' => $this->input->post('fc_manding', TRUE),
				'fc_psamya' => $this->input->post('fc_psamya', TRUE),
				'total_10' => $this->input->post('total_10', TRUE),
				'nilai_persediaan' => $this->input->post('nilai_persediaan', TRUE),
			);

			$this->Persediaan_model->insert($data);
			$this->session->set_flashdata('message', 'Create Record Success');
			redirect(site_url('persediaan'));
		}
	}

	public function update($id)
	{
		$row = $this->Persediaan_model->get_by_id($id);

		if ($row) {
			$data = array(
				'button' => 'Update',
				'action' => site_url('persediaan/update_action'),
				'id' => set_value('id', $row->id),
				'tanggal' => set_value('tanggal', $row->tanggal),
				'kode' => set_value('kode', $row->kode),
				'namabarang' => set_value('namabarang', $row->namabarang),
				'satuan' => set_value('satuan', $row->satuan),
				'hpp' => set_value('hpp', $row->hpp),
				'sa' => set_value('sa', $row->sa),
				'spop' => set_value('spop', $row->spop),
				'beli' => set_value('beli', $row->beli),
				'tuj' => set_value('tuj', $row->tuj),
				'tgl_keluar' => set_value('tgl_keluar', $row->tgl_keluar),
				'sekret' => set_value('sekret', $row->sekret),
				'cetak' => set_value('cetak', $row->cetak),
				'grafikita' => set_value('grafikita', $row->grafikita),
				'dinas_umum' => set_value('dinas_umum', $row->dinas_umum),
				'atk_rsud' => set_value('atk_rsud', $row->atk_rsud),
				'ppbmp_kbs' => set_value('ppbmp_kbs', $row->ppbmp_kbs),
				'kbs' => set_value('kbs', $row->kbs),
				'ppbmp' => set_value('ppbmp', $row->ppbmp),
				'medis' => set_value('medis', $row->medis),
				'siiplah_bosda' => set_value('siiplah_bosda', $row->siiplah_bosda),
				'sembako' => set_value('sembako', $row->sembako),
				'fc_gose' => set_value('fc_gose', $row->fc_gose),
				'fc_manding' => set_value('fc_manding', $row->fc_manding),
				'fc_psamya' => set_value('fc_psamya', $row->fc_psamya),
				'total_10' => set_value('total_10', $row->total_10),
				'nilai_persediaan' => set_value('nilai_persediaan', $row->nilai_persediaan),
			);
			$this->load->view('persediaan/persediaan_form', $data);
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(site_url('persediaan'));
		}
	}

	public function update_action()
	{
		$this->_rules();

		if ($this->form_validation->run() == FALSE) {
			$this->update($this->input->post('', TRUE));
		} else {
			$data = array(
				'id' => $this->input->post('id', TRUE),
				'tanggal' => $this->input->post('tanggal', TRUE),
				'kode' => $this->input->post('kode', TRUE),
				'namabarang' => $this->input->post('namabarang', TRUE),
				'satuan' => $this->input->post('satuan', TRUE),
				'hpp' => $this->input->post('hpp', TRUE),
				'sa' => $this->input->post('sa', TRUE),
				'spop' => $this->input->post('spop', TRUE),
				'beli' => $this->input->post('beli', TRUE),
				'tuj' => $this->input->post('tuj', TRUE),
				'tgl_keluar' => $this->input->post('tgl_keluar', TRUE),
				'sekret' => $this->input->post('sekret', TRUE),
				'cetak' => $this->input->post('cetak', TRUE),
				'grafikita' => $this->input->post('grafikita', TRUE),
				'dinas_umum' => $this->input->post('dinas_umum', TRUE),
				'atk_rsud' => $this->input->post('atk_rsud', TRUE),
				'ppbmp_kbs' => $this->input->post('ppbmp_kbs', TRUE),
				'kbs' => $this->input->post('kbs', TRUE),
				'ppbmp' => $this->input->post('ppbmp', TRUE),
				'medis' => $this->input->post('medis', TRUE),
				'siiplah_bosda' => $this->input->post('siiplah_bosda', TRUE),
				'sembako' => $this->input->post('sembako', TRUE),
				'fc_gose' => $this->input->post('fc_gose', TRUE),
				'fc_manding' => $this->input->post('fc_manding', TRUE),
				'fc_psamya' => $this->input->post('fc_psamya', TRUE),
				'total_10' => $this->input->post('total_10', TRUE),
				'nilai_persediaan' => $this->input->post('nilai_persediaan', TRUE),
			);

			$this->Persediaan_model->update($this->input->post('', TRUE), $data);
			$this->session->set_flashdata('message', 'Update Record Success');
			redirect(site_url('persediaan'));
		}
	}

	public function delete($id)
	{
		$row = $this->Persediaan_model->get_by_id($id);

		if ($row) {
			$this->Persediaan_model->delete($id);
			$this->session->set_flashdata('message', 'Delete Record Success');
			redirect(site_url('persediaan'));
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(site_url('persediaan'));
		}
	}

	public function _rules()
	{
		$this->form_validation->set_rules('id', 'id', 'trim|required');
		$this->form_validation->set_rules('tanggal', 'tanggal', 'trim|required');
		$this->form_validation->set_rules('kode', 'kode', 'trim|required');
		$this->form_validation->set_rules('namabarang', 'namabarang', 'trim|required');
		$this->form_validation->set_rules('satuan', 'satuan', 'trim|required');
		$this->form_validation->set_rules('hpp', 'hpp', 'trim|required');
		$this->form_validation->set_rules('sa', 'sa', 'trim|required');
		$this->form_validation->set_rules('spop', 'spop', 'trim|required');
		$this->form_validation->set_rules('beli', 'beli', 'trim|required');
		$this->form_validation->set_rules('tuj', 'tuj', 'trim|required');
		$this->form_validation->set_rules('tgl_keluar', 'tgl keluar', 'trim|required');
		$this->form_validation->set_rules('sekret', 'sekret', 'trim|required');
		$this->form_validation->set_rules('cetak', 'cetak', 'trim|required');
		$this->form_validation->set_rules('grafikita', 'grafikita', 'trim|required');
		$this->form_validation->set_rules('dinas_umum', 'dinas umum', 'trim|required');
		$this->form_validation->set_rules('atk_rsud', 'atk rsud', 'trim|required');
		$this->form_validation->set_rules('ppbmp_kbs', 'ppbmp kbs', 'trim|required');
		$this->form_validation->set_rules('kbs', 'kbs', 'trim|required');
		$this->form_validation->set_rules('ppbmp', 'ppbmp', 'trim|required');
		$this->form_validation->set_rules('medis', 'medis', 'trim|required');
		$this->form_validation->set_rules('siiplah_bosda', 'siiplah bosda', 'trim|required');
		$this->form_validation->set_rules('sembako', 'sembako', 'trim|required');
		$this->form_validation->set_rules('fc_gose', 'fc gose', 'trim|required');
		$this->form_validation->set_rules('fc_manding', 'fc manding', 'trim|required');
		$this->form_validation->set_rules('fc_psamya', 'fc psamya', 'trim|required');
		$this->form_validation->set_rules('total_10', 'total 10', 'trim|required');
		$this->form_validation->set_rules('nilai_persediaan', 'nilai persediaan', 'trim|required');

		$this->form_validation->set_rules('', '', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
	}

	public function excel()
	{
		$this->load->helper('exportexcel');
		$namaFile = "persediaan.xls";
		$judul = "persediaan";
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
		xlsWriteLabel($tablehead, $kolomhead++, "Id");
		xlsWriteLabel($tablehead, $kolomhead++, "Tanggal");
		xlsWriteLabel($tablehead, $kolomhead++, "Kode");
		xlsWriteLabel($tablehead, $kolomhead++, "Namabarang");
		xlsWriteLabel($tablehead, $kolomhead++, "Satuan");
		xlsWriteLabel($tablehead, $kolomhead++, "Hpp");
		xlsWriteLabel($tablehead, $kolomhead++, "Sa");
		xlsWriteLabel($tablehead, $kolomhead++, "Spop");
		xlsWriteLabel($tablehead, $kolomhead++, "Beli");
		xlsWriteLabel($tablehead, $kolomhead++, "Tuj");
		xlsWriteLabel($tablehead, $kolomhead++, "Tgl Keluar");
		xlsWriteLabel($tablehead, $kolomhead++, "Sekret");
		xlsWriteLabel($tablehead, $kolomhead++, "Cetak");
		xlsWriteLabel($tablehead, $kolomhead++, "Grafikita");
		xlsWriteLabel($tablehead, $kolomhead++, "Dinas Umum");
		xlsWriteLabel($tablehead, $kolomhead++, "Atk Rsud");
		xlsWriteLabel($tablehead, $kolomhead++, "Ppbmp Kbs");
		xlsWriteLabel($tablehead, $kolomhead++, "Kbs");
		xlsWriteLabel($tablehead, $kolomhead++, "Ppbmp");
		xlsWriteLabel($tablehead, $kolomhead++, "Medis");
		xlsWriteLabel($tablehead, $kolomhead++, "Siiplah Bosda");
		xlsWriteLabel($tablehead, $kolomhead++, "Sembako");
		xlsWriteLabel($tablehead, $kolomhead++, "Fc Gose");
		xlsWriteLabel($tablehead, $kolomhead++, "Fc Manding");
		xlsWriteLabel($tablehead, $kolomhead++, "Fc Psamya");
		xlsWriteLabel($tablehead, $kolomhead++, "Total 10");
		xlsWriteLabel($tablehead, $kolomhead++, "Nilai Persediaan");

		foreach ($this->Persediaan_model->get_all() as $data) {
			$kolombody = 0;

			//ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
			xlsWriteNumber($tablebody, $kolombody++, $nourut);
			xlsWriteNumber($tablebody, $kolombody++, $data->id);
			xlsWriteLabel($tablebody, $kolombody++, $data->tanggal);
			xlsWriteLabel($tablebody, $kolombody++, $data->kode);
			xlsWriteLabel($tablebody, $kolombody++, $data->namabarang);
			xlsWriteLabel($tablebody, $kolombody++, $data->satuan);
			xlsWriteLabel($tablebody, $kolombody++, $data->hpp);
			xlsWriteLabel($tablebody, $kolombody++, $data->sa);
			xlsWriteLabel($tablebody, $kolombody++, $data->spop);
			xlsWriteLabel($tablebody, $kolombody++, $data->beli);
			xlsWriteNumber($tablebody, $kolombody++, $data->tuj);
			xlsWriteLabel($tablebody, $kolombody++, $data->tgl_keluar);
			xlsWriteLabel($tablebody, $kolombody++, $data->sekret);
			xlsWriteLabel($tablebody, $kolombody++, $data->cetak);
			xlsWriteLabel($tablebody, $kolombody++, $data->grafikita);
			xlsWriteLabel($tablebody, $kolombody++, $data->dinas_umum);
			xlsWriteLabel($tablebody, $kolombody++, $data->atk_rsud);
			xlsWriteLabel($tablebody, $kolombody++, $data->ppbmp_kbs);
			xlsWriteLabel($tablebody, $kolombody++, $data->kbs);
			xlsWriteLabel($tablebody, $kolombody++, $data->ppbmp);
			xlsWriteLabel($tablebody, $kolombody++, $data->medis);
			xlsWriteLabel($tablebody, $kolombody++, $data->siiplah_bosda);
			xlsWriteLabel($tablebody, $kolombody++, $data->sembako);
			xlsWriteLabel($tablebody, $kolombody++, $data->fc_gose);
			xlsWriteLabel($tablebody, $kolombody++, $data->fc_manding);
			xlsWriteLabel($tablebody, $kolombody++, $data->fc_psamya);
			xlsWriteNumber($tablebody, $kolombody++, $data->total_10);
			xlsWriteNumber($tablebody, $kolombody++, $data->nilai_persediaan);

			$tablebody++;
			$nourut++;
		}

		xlsEOF();
		exit();
	}

	public function word()
	{
		header("Content-type: application/vnd.ms-word");
		header("Content-Disposition: attachment;Filename=persediaan.doc");

		$data = array(
			'persediaan_data' => $this->Persediaan_model->get_all(),
			'start' => 0
		);

		$this->load->view('persediaan/persediaan_doc', $data);
	}
}

/* End of file Persediaan.php */
/* Location: ./application/controllers/Persediaan.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-10-23 04:04:45 */
/* http://harviacode.com */