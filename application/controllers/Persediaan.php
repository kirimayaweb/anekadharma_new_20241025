<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Persediaan extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_login();
		$this->load->model(array('Persediaan_model', 'Sys_nama_barang_model', 'Sys_konsumen_model'));
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
			print_r(" - SPOP:");
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
			$this->db->where('uuid_barang', $m->uuid_barang);
			$data_persediaan = $this->db->get('persediaan');

			if ($data_persediaan->num_rows() > 0) {
				print_r("Ada data");
			} else {
				print_r("Tidak ada data");
				print_r("<br/>");

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
		$bulan = date('Y-m');
		$Persediaan = $this->get_persediaan_by_bulan($bulan);
		$data = $this->get_persediaan_list_view_data($bulan, $Persediaan);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/persediaan/adminlte310_persediaan_list', $data);
	}

	public function search()
	{
		// Input <input type="month" name="bulan_persediaan"> mengirim YYYY-MM.
		$bulan = trim((string) $this->input->post('bulan_persediaan', TRUE));
		$Persediaan = $this->get_persediaan_by_bulan($bulan);
		$data = $this->get_persediaan_list_view_data($bulan, $Persediaan);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/persediaan/adminlte310_persediaan_list', $data);
	}

	private function get_persediaan_list_view_data($bulan, $Persediaan)
	{
		$this->load->helper('persediaan_display');

		$ts_gen_default = strtotime('+1 month', strtotime(date('Y-m-01')));
		if ($ts_gen_default === false) {
			$ts_gen_default = time();
		}

		return array(
			'Persediaan_data' => $Persediaan,
			'action_cari' => site_url('persediaan/search'),
			'bulan_persediaan_selected' => $bulan,
			'url_rekap_ajax' => site_url('Persediaan/ajax_rekap_bulan'),
			'url_rekap_sync_step' => site_url('Persediaan/ajax_rekap_sync_step'),
			'url_rekap_excel' => site_url('Persediaan/excel_rekap'),
			'url_tambah_persediaan' => site_url('Persediaan/ajax_tambah_persediaan'),
			'url_cek_generate_persediaan' => site_url('Persediaan/ajax_cek_generate_persediaan_bulan'),
			'url_analisa_generate_persediaan' => site_url('Persediaan/ajax_analisa_generate_persediaan_bulan'),
			'url_generate_persediaan_base' => site_url('Persediaan/GENERATE_PERSEDIAN_BULAN'),
			'gen_bulan_default' => (int) date('n', $ts_gen_default),
			'gen_tahun_default' => (int) date('Y', $ts_gen_default),
			'gen_tahun_min' => 2020,
			'gen_tahun_max' => (int) date('Y') + 2,
			'can_generate_persediaan' => $this->persediaan_user_can_generate(),
			'rekap_total_steps' => $this->get_rekap_total_steps(),
		);
	}

	/**
	 * Generate persediaan: id_user_level 1 (admin), 2, atau 99 (administrator);
	 * fallback nama_level di tbl_user_level = admin / administrator.
	 */
	private function persediaan_user_can_generate()
	{
		$level = trim((string) $this->session->userdata('sess_id_user_level'));
		if (in_array($level, array('1', '2', '99'), true)) {
			return true;
		}

		if ($level !== '' && $this->db->table_exists('tbl_user_level')) {
			$row = $this->db->where('id_user_level', $level)->limit(1)->get('tbl_user_level')->row();
			if (!empty($row->nama_level)) {
				$nama = strtolower(trim((string) $row->nama_level));
				if (in_array($nama, array('admin', 'administrator'), true)) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * AJAX: cek apakah bulan target sudah punya data persediaan (untuk tab Generate).
	 */
	public function ajax_cek_generate_persediaan_bulan()
	{
		$this->output->set_content_type('application/json');

		if (!$this->persediaan_user_can_generate()) {
			echo json_encode(array(
				'ok' => true,
				'can_generate' => false,
				'user_can_generate' => false,
				'sudah_ada_data' => false,
				'message' => 'Tombol generate hanya untuk user <strong>Admin</strong> / <strong>Administrator</strong> (id_user_level <strong>1</strong>, <strong>2</strong>, atau <strong>99</strong>).',
			));
			return;
		}

		$bulan_target = trim((string) $this->input->get_post('bulan', TRUE));
		if (!preg_match('/^\d{4}-\d{2}$/', $bulan_target)) {
			echo json_encode(array(
				'ok' => false,
				'message' => 'Format bulan tidak valid. Gunakan YYYY-MM.',
			));
			return;
		}

		$ts_target = strtotime($bulan_target . '-01');
		if ($ts_target === false) {
			echo json_encode(array('ok' => false, 'message' => 'Bulan target tidak valid.'));
			return;
		}

		$tanggal_beli_target = date('Y-m-01', $ts_target);
		$tanggal_beli_sumber = date('Y-m-01', strtotime('-1 month', $ts_target));
		$bulan_sumber = date('Y-m', strtotime('-1 month', $ts_target));

		$count_target = $this->persediaan_count_by_tanggal_beli($tanggal_beli_target);
		$count_sumber = $this->persediaan_count_by_tanggal_beli($tanggal_beli_sumber);
		$sudah_ada = ($count_target > 0);
		$can_generate = ($count_sumber > 0);

		$message = '';
		if ($count_sumber === 0) {
			$message = 'Tidak ada data sumber bulan ' . date('m/Y', strtotime($bulan_sumber . '-01'))
				. ' (tanggal_beli = ' . $tanggal_beli_sumber . '). Isi dulu persediaan bulan sebelumnya.';
		} elseif ($sudah_ada) {
			$message = 'Bulan target sudah ada <strong>' . $count_target . ' record</strong>. Generate akan: '
				. '(1) update <strong>beli</strong> record yang sudah ada dari <code>tbl_pembelian</code> / '
				. '<code>tbl_pembelian_jasa</code> (by <code>uuid_persediaan</code>, beli=0 jika tidak ada pembelian), '
				. '(2) menambah record baru dari bulan sumber untuk barang yang belum ada.';
		} else {
			$message = 'Siap generate: salin ' . $count_sumber . ' record dari bulan '
				. date('m/Y', strtotime($bulan_sumber . '-01')) . ' ke bulan '
				. date('m/Y', $ts_target) . '.';
		}

		echo json_encode(array(
			'ok' => true,
			'bulan_target' => $bulan_target,
			'bulan_sumber' => $bulan_sumber,
			'tanggal_beli_target' => $tanggal_beli_target,
			'tanggal_beli_sumber' => $tanggal_beli_sumber,
			'count_target' => $count_target,
			'count_sumber' => $count_sumber,
			'sudah_ada_data' => $sudah_ada,
			'can_generate' => $can_generate,
			'user_can_generate' => true,
			'url_generate' => site_url('Persediaan/GENERATE_PERSEDIAN_BULAN/' . $bulan_target),
			'message' => $message,
		));
	}

	/**
	 * AJAX: analisa sebelum generate (duplikat uuid_barang, estimasi insert/update).
	 */
	public function ajax_analisa_generate_persediaan_bulan()
	{
		$this->output->set_content_type('application/json');

		if (!$this->persediaan_user_can_generate()) {
			echo json_encode(array(
				'ok' => false,
				'message' => 'Analisa generate hanya untuk Admin / Administrator (id_user_level 1, 2, atau 99).',
			));
			return;
		}

		$bulan_target = trim((string) $this->input->get_post('bulan', TRUE));
		if (!preg_match('/^\d{4}-\d{2}$/', $bulan_target)) {
			echo json_encode(array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).'));
			return;
		}

		$ctx = $this->get_generate_persediaan_context($bulan_target);
		if (!$ctx['ok']) {
			echo json_encode(array('ok' => false, 'message' => $ctx['message']));
			return;
		}

		$analisa = $this->analisa_generate_persediaan_bulan($ctx);
		$analisa['ok'] = true;
		$analisa['bulan_target'] = $ctx['bulan_target'];
		$analisa['bulan_sumber'] = date('Y-m', strtotime($ctx['tanggal_beli_sumber']));
		$analisa['tanggal_beli_target'] = $ctx['tanggal_beli_target'];
		$analisa['tanggal_beli_sumber'] = $ctx['tanggal_beli_sumber'];
		$analisa['bulan_target_label'] = date('m/Y', strtotime($ctx['tanggal_beli_target']));
		$analisa['bulan_sumber_label'] = date('m/Y', strtotime($ctx['tanggal_beli_sumber']));
		$analisa['url_generate'] = site_url('Persediaan/GENERATE_PERSEDIAN_BULAN/' . $ctx['bulan_target']);

		echo json_encode($analisa);
	}

	/**
	 * Simulasi & statistik duplikat uuid_barang sebelum generate.
	 */
	private function analisa_generate_persediaan_bulan($ctx)
	{
		$tanggal_beli_target = $ctx['tanggal_beli_target'];
		$tanggal_beli_sumber = $ctx['tanggal_beli_sumber'];

		$total_sumber = (int) $ctx['total_sumber'];
		$total_target = $this->persediaan_count_by_tanggal_beli($tanggal_beli_target);

		$row_dup_uuid = $this->db->query(
			"SELECT TRIM(COALESCE(`uuid_barang`, '')) AS uuid_barang,
				COUNT(*) AS jumlah
			FROM `persediaan`
			WHERE `tanggal_beli` = ?
			AND TRIM(COALESCE(`uuid_barang`, '')) <> ''
			GROUP BY TRIM(COALESCE(`uuid_barang`, ''))
			HAVING COUNT(*) > 1
			ORDER BY jumlah DESC, uuid_barang ASC",
			array($tanggal_beli_sumber)
		)->result();

		$grup_duplikat_uuid_barang = count($row_dup_uuid);
		$baris_duplikat_uuid_barang = 0;
		$daftar_duplikat = array();
		foreach ($row_dup_uuid as $d) {
			$extra = (int) $d->jumlah - 1;
			$baris_duplikat_uuid_barang += $extra;
			if (count($daftar_duplikat) < 15) {
				$daftar_duplikat[] = array(
					'uuid_barang' => $d->uuid_barang,
					'jumlah' => (int) $d->jumlah,
					'baris_tambahan' => $extra,
				);
			}
		}

		$row_dup_uuid_target = $this->db->query(
			"SELECT TRIM(COALESCE(`uuid_barang`, '')) AS uuid_barang,
				COUNT(*) AS jumlah
			FROM `persediaan`
			WHERE `tanggal_beli` = ?
			AND TRIM(COALESCE(`uuid_barang`, '')) <> ''
			GROUP BY TRIM(COALESCE(`uuid_barang`, ''))
			HAVING COUNT(*) > 1
			ORDER BY jumlah DESC",
			array($tanggal_beli_target)
		)->result();

		$grup_duplikat_uuid_target = count($row_dup_uuid_target);
		$baris_duplikat_uuid_target = 0;
		foreach ($row_dup_uuid_target as $d) {
			$baris_duplikat_uuid_target += ((int) $d->jumlah - 1);
		}

		$rows_sumber = $this->db->query(
			"SELECT * FROM `persediaan` WHERE `tanggal_beli`=? ORDER BY `id` ASC",
			array($tanggal_beli_sumber)
		)->result();

		$estimasi_kosong_uuid = 0;
		foreach ($rows_sumber as $row) {
			if (trim((string) $row->uuid_barang) === '') {
				$estimasi_kosong_uuid++;
			}
		}

		$estimasi_insert = $total_sumber;
		$estimasi_update = 0;

		$penjelasan = 'Semua ' . $total_sumber . ' record bulan sumber akan di-<strong>INSERT</strong> ke bulan target '
			. '(data persediaan bulan target yang lama akan dikosongkan terlebih dahulu). '
			. 'Ada di tbl_pembelian/jasa tidak menghalangi copy — hanya mengisi kolom <strong>beli</strong>. '
			. 'Total akhir bulan target = ' . $total_sumber . ' (sama dengan bulan sumber).';
		if ($total_target > 0) {
			$penjelasan .= ' Saat ini bulan target sudah ada <strong>' . $total_target . ' record</strong> — akan diganti saat generate.';
		}

		$analisa_uuid_kosong = $this->analisa_uuid_barang_kosong_generate($ctx);
		if ($analisa_uuid_kosong['total_kosong_sumber'] > 0) {
			$penjelasan .= ' <strong>' . $analisa_uuid_kosong['total_kosong_sumber']
				. ' record</strong> tanpa <code>uuid_barang</code> di bulan sumber akan diberi <strong>uuid baru unik</strong> '
				. '(berbeda tiap baris) otomatis sebelum disalin ke bulan target.';
		}

		return array(
			'total_sumber' => $total_sumber,
			'total_target' => $total_target,
			'estimasi_insert' => $estimasi_insert,
			'estimasi_update' => $estimasi_update,
			'estimasi_duplikat_sumber' => 0,
			'estimasi_kosong_uuid_barang' => $estimasi_kosong_uuid,
			'estimasi_tidak_insert_baru' => 0,
			'selisih_sumber_minus_insert' => 0,
			'estimasi_total_target_setelah' => $total_sumber,
			'akan_reset_bulan_target' => true,
			'grup_duplikat_uuid_barang_sumber' => $grup_duplikat_uuid_barang,
			'baris_duplikat_uuid_barang_sumber' => $baris_duplikat_uuid_barang,
			'grup_duplikat_uuid_barang_target' => $grup_duplikat_uuid_target,
			'baris_duplikat_uuid_barang_target' => $baris_duplikat_uuid_target,
			'daftar_duplikat_uuid_barang' => $daftar_duplikat,
			'uuid_barang_kosong' => $analisa_uuid_kosong,
			'penjelasan' => $penjelasan,
			'perlu_konfirmasi' => (
				$grup_duplikat_uuid_barang > 0
				|| $total_target > 0
				|| $analisa_uuid_kosong['total_kosong_sumber'] > 0
			),
		);
	}

	/**
	 * AJAX: tambah record persediaan manual (nama, satuan, hpp) untuk bulan terpilih.
	 */
	public function ajax_tambah_persediaan()
	{
		if (!$this->input->is_ajax_request()) {
			show_404();
			return;
		}

		header('Content-Type: application/json; charset=UTF-8');

		$this->load->helper('pembelian_persediaan');

		$bulan = trim((string) $this->input->post('bulan_persediaan', TRUE));
		if ($bulan === '') {
			$bulan = trim((string) $this->input->get('bulan_persediaan', TRUE));
		}
		if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
			echo json_encode(array(
				'ok' => false,
				'success' => false,
				'message' => 'Format bulan tidak valid. Pilih bulan di filter Data Persediaan.',
			));
			return;
		}

		$namabarang = pembelian_normalize_nama_barang($this->input->post('namabarang', TRUE));
		$satuan = trim((string) $this->input->post('satuan', TRUE));
		$hpp_raw = trim((string) $this->input->post('harga_satuan', TRUE));
		$hpp = preg_replace('/[^0-9]/', '', str_replace('.', '', $hpp_raw));
		if ($hpp === '') {
			$hpp = '0';
		}

		if ($namabarang === '') {
			echo json_encode(array(
				'ok' => false,
				'success' => false,
				'message' => 'Nama barang / jasa wajib diisi.',
			));
			return;
		}

		if ($satuan === '') {
			echo json_encode(array(
				'ok' => false,
				'success' => false,
				'message' => 'Satuan wajib diisi.',
			));
			return;
		}

		$tanggal_beli = $this->get_tanggal_rekap_dari_bulan($bulan);
		$bulan_label = date('m/Y', strtotime($tanggal_beli));
		$tanggal_po_cek = date('j-n-Y', strtotime($tanggal_beli));

		$existing = pembelian_find_barang_by_nama($this, $namabarang, $tanggal_po_cek);
		if ($existing) {
			echo json_encode(array(
				'ok' => false,
				'success' => false,
				'duplicate' => true,
				'message' => 'Nama barang / jasa sudah ada di persediaan bulan ' . $bulan_label . '.',
			));
			return;
		}

		$uuid_barang_baru = str_replace('-', '', $this->db->query("SELECT REPLACE(UUID(),'-','') AS u")->row()->u);

		$data_persediaan = array(
			'tanggal' => date('Y-m-d H:i:s'),
			'tanggal_beli' => $tanggal_beli,
			'uuid_barang' => $uuid_barang_baru,
			'kode' => '',
			'namabarang' => $namabarang,
			'satuan' => $satuan,
			'hpp' => $hpp,
			'sa' => 0,
			'beli' => 0,
			'total_10' => 0,
			'nilai_persediaan' => 0,
		);

		$id_persediaan = $this->Persediaan_model->insert_produk_baru($data_persediaan);

		if (!$id_persediaan) {
			echo json_encode(array(
				'ok' => false,
				'success' => false,
				'message' => 'Gagal menyimpan data persediaan.',
			));
			return;
		}

		echo json_encode(array(
			'ok' => true,
			'success' => true,
			'message' => 'Persediaan berhasil ditambahkan untuk bulan ' . $bulan_label
				. ' (tanggal beli: ' . $tanggal_beli . ').',
			'id' => (int) $id_persediaan,
			'bulan' => $bulan,
			'tanggal_beli' => $tanggal_beli,
		));
	}

	private function get_rekap_total_steps()
	{
		return 7 + count($this->get_rekap_breakdown_config());
	}

	private function parse_bulan_rekap_input()
	{
		$bulan = trim((string) $this->input->post('bulan_persediaan', TRUE));
		if ($bulan === '') {
			$bulan = trim((string) $this->input->get('bulan_persediaan', TRUE));
		}
		if ($bulan === '') {
			$bulan = date('Y-m');
		}
		if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
			return array('ok' => false, 'message' => 'Format bulan tidak valid.');
		}
		return array('ok' => true, 'bulan' => $bulan);
	}

	/**
	 * AJAX: muat data rekap (tanpa rekalkulasi).
	 */
	public function ajax_rekap_bulan()
	{
		$this->load->helper('persediaan_display');
		$parsed = $this->parse_bulan_rekap_input();
		if (!$parsed['ok']) {
			header('Content-Type: application/json; charset=UTF-8');
			echo json_encode($parsed);
			return;
		}

		$bulan = $parsed['bulan'];
		$hasil_rekap = $this->get_persediaan_rekap_rows($bulan);

		header('Content-Type: application/json; charset=UTF-8');
		echo json_encode(array(
			'ok' => true,
			'bulan' => $bulan,
			'tanggal_rekap' => $this->get_tanggal_rekap_dari_bulan($bulan),
			'items' => $hasil_rekap['items'],
			'total_detail' => $hasil_rekap['total_detail'],
			'total_detail_tampil' => $hasil_rekap['total_detail_tampil'],
		));
	}

	/**
	 * AJAX: satu langkah rekalkulasi rekap (step 1–21) untuk progress bar.
	 */
	public function ajax_rekap_sync_step()
	{
		$this->load->helper('persediaan_display');
		$parsed = $this->parse_bulan_rekap_input();
		if (!$parsed['ok']) {
			header('Content-Type: application/json; charset=UTF-8');
			echo json_encode($parsed);
			return;
		}

		$step = (int) $this->input->post('step', TRUE);
		$total_steps = $this->get_rekap_total_steps();
		if ($step < 1 || $step > $total_steps) {
			header('Content-Type: application/json; charset=UTF-8');
			echo json_encode(array('ok' => false, 'message' => 'Langkah rekalkulasi tidak valid.'));
			return;
		}

		$hasil = $this->sync_persediaan_rekap_step($parsed['bulan'], $step);
		header('Content-Type: application/json; charset=UTF-8');
		echo json_encode($hasil);
	}

	private function get_tanggal_rekap_dari_bulan($bulan)
	{
		$ts = strtotime($bulan . '-01');
		if ($ts === false) {
			return date('Y-m-01');
		}
		return date('Y-m-01', $ts);
	}

	private function get_urutan_nama_rekap()
	{
		$utama = array(
			'Sediaan Awal', 'Pembelian PU', 'Pembelian Cetak', 'Pembelian Grafikita',
			'TUJ', 'Sediaan Akhir', 'BPP',
		);
		foreach ($this->get_rekap_breakdown_config() as $cfg) {
			$utama[] = $cfg['nama'];
		}
		return $utama;
	}

	/**
	 * Record 8–21: nama tampilan + kolom persediaan (jumlah kolom bulan terpilih).
	 */
	private function get_rekap_breakdown_config()
	{
		return array(
			array('nama' => '(Cetak)', 'kolom' => 'cetak'),
			array('nama' => '(Cetak Grafikita)', 'kolom' => 'grafikita'),
			array('nama' => '(Sekret)', 'kolom' => 'sekret'),
			array('nama' => '(medis)', 'kolom' => 'medis'),
			array('nama' => '(PPBMP )', 'kolom' => 'ppbmp'),
			array('nama' => '(Dinas & Umum )', 'kolom' => 'dinas_umum'),
			array('nama' => '( Rsud )', 'kolom' => 'atk_rsud'),
			array('nama' => '( Siiplah&Bosda)', 'kolom' => 'siiplah_bosda'),
			array('nama' => '(Alat dan Bahan KBS)', 'kolom' => 'ppbmp_kbs'),
			array('nama' => '(KBS PPBMP)', 'kolom' => 'ppbmp_kbs'),
			array('nama' => '(Sembako)', 'kolom' => 'sembako'),
			array('nama' => '(P.samya) FC', 'kolom' => 'fc_psamya'),
			array('nama' => '(Gose) FC', 'kolom' => 'fc_gose'),
			array('nama' => '(Manding) FC', 'kolom' => 'fc_manding'),
		);
	}

	private function get_persediaan_rekap_rows($bulan)
	{
		$this->load->helper('persediaan_display');
		$tanggal_rekap = $this->get_tanggal_rekap_dari_bulan($bulan);
		$urutan = $this->get_urutan_nama_rekap();
		$order_sql = implode(',', array_map(function ($n) {
			return $this->db->escape($n);
		}, $urutan));

		$list = $this->db->query(
			"SELECT `nama_rekap`, `nominal` FROM `persediaan_rekap_view`
			WHERE `tanggal_rekap`=?
			ORDER BY FIELD(`nama_rekap`, " . $order_sql . "), `id` ASC",
			array($tanggal_rekap)
		)->result();

		$nama_breakdown = array();
		foreach ($this->get_rekap_breakdown_config() as $cfg) {
			$nama_breakdown[] = $cfg['nama'];
		}

		$items = array();
		$no = 1;
		$total_detail = 0;
		foreach ($list as $row) {
			$nominal_angka = persediaan_parse_angka($row->nominal);
			if (in_array($row->nama_rekap, $nama_breakdown, true)) {
				$total_detail += $nominal_angka;
			}
			$items[] = array(
				'nomor' => $no++,
				'deskripsi' => $row->nama_rekap,
				'nominal' => $nominal_angka,
				'nominal_tampil' => number_format($nominal_angka, 0, ',', '.'),
				'is_breakdown' => in_array($row->nama_rekap, $nama_breakdown, true),
			);
		}

		return array(
			'items' => $items,
			'total_detail' => $total_detail,
			'total_detail_tampil' => number_format($total_detail, 0, ',', '.'),
		);
	}

	/**
	 * Jumlah Σ(kolom) untuk semua record persediaan pada tanggal_beli tertentu.
	 */
	private function sum_persediaan_kolom($tanggal_beli, $nama_kolom)
	{
		$this->load->helper('persediaan_display');
		$allowed = array(
			'cetak', 'grafikita', 'sekret', 'medis', 'ppbmp', 'dinas_umum', 'atk_rsud',
			'siiplah_bosda', 'ppbmp_kbs', 'kbs', 'sembako', 'fc_psamya', 'fc_gose', 'fc_manding',
		);
		if (!in_array($nama_kolom, $allowed, true)) {
			return 0;
		}

		$rows = $this->db->query(
			"SELECT `" . $nama_kolom . "` FROM `persediaan` WHERE `tanggal_beli`=?",
			array($tanggal_beli)
		)->result();

		$total = 0;
		foreach ($rows as $r) {
			$total += persediaan_parse_angka($r->{$nama_kolom});
		}
		return $total;
	}

	private function tally_rekap_upsert(&$insert_count, &$update_count, $aksi)
	{
		if ($aksi === 'insert') {
			$insert_count++;
		} elseif ($aksi === 'update') {
			$update_count++;
		}
	}

	/**
	 * Jumlah Σ(kolom × hpp) untuk semua record persediaan pada tanggal_beli tertentu.
	 */
	private function sum_persediaan_kolom_kali_hpp($tanggal_beli, $nama_kolom)
	{
		$this->load->helper('persediaan_display');
		$allowed = array('dinas_umum', 'cetak', 'grafikita', 'tuj');
		if (!in_array($nama_kolom, $allowed, true)) {
			return 0;
		}

		$rows = $this->db->query(
			"SELECT `" . $nama_kolom . "`, `hpp` FROM `persediaan` WHERE `tanggal_beli`=?",
			array($tanggal_beli)
		)->result();

		$total = 0;
		foreach ($rows as $r) {
			$total += persediaan_parse_angka($r->{$nama_kolom}) * persediaan_parse_angka($r->hpp);
		}
		return $total;
	}

	/**
	 * Total nilai_persediaan bulan terpilih (Σ nilai_persediaan × hpp per record).
	 */
	private function sum_persediaan_nilai_kali_hpp($tanggal_beli)
	{
		$this->load->helper('persediaan_display');
		$rows = $this->db->query(
			"SELECT `nilai_persediaan`, `hpp` FROM `persediaan` WHERE `tanggal_beli`=?",
			array($tanggal_beli)
		)->result();

		$total = 0;
		foreach ($rows as $r) {
			$total += persediaan_parse_angka($r->nilai_persediaan) * persediaan_parse_angka($r->hpp);
		}
		return $total;
	}

	/**
	 * Total nilai_persediaan bulan terpilih (tanpa dikali hpp lagi).
	 */
	private function sum_persediaan_nilai_persediaan($tanggal_beli)
	{
		$this->load->helper('persediaan_display');
		$rows = $this->db->query(
			"SELECT `nilai_persediaan` FROM `persediaan` WHERE `tanggal_beli`=?",
			array($tanggal_beli)
		)->result();

		$total = 0;
		foreach ($rows as $r) {
			$total += persediaan_parse_angka($r->nilai_persediaan);
		}
		return $total;
	}

	private function upsert_persediaan_rekap_baris($tanggal_rekap, $nama_rekap, $nominal, $keterangan, &$next_id)
	{
		$existing = $this->db->query(
			"SELECT `id` FROM `persediaan_rekap_view` WHERE `tanggal_rekap`=? AND `nama_rekap`=? LIMIT 1",
			array($tanggal_rekap, $nama_rekap)
		)->row();

		$nominal_tampil = $this->format_angka_persediaan($nominal);

		if ($existing) {
			$this->db->where('id', $existing->id);
			$this->db->update('persediaan_rekap_view', array(
				'nominal' => $nominal_tampil,
				'keterangan' => $keterangan,
			));
			return 'update';
		}

		$data_insert = array(
			'id' => $next_id++,
			'tanggal_rekap' => $tanggal_rekap,
			'nama_rekap' => $nama_rekap,
			'nominal' => $nominal_tampil,
			'keterangan' => $keterangan,
		);
		$this->db->set('uuid_persediaan_rekap_view', "replace(uuid(),'-','')", FALSE);
		$this->db->insert('persediaan_rekap_view', $data_insert);
		return 'insert';
	}

	private function get_next_id_persediaan_rekap_view()
	{
		$row_max = $this->db->query("SELECT MAX(`id`) AS max_id FROM `persediaan_rekap_view`")->row();
		return $row_max && $row_max->max_id ? ((int) $row_max->max_id + 1) : 1;
	}

	private function get_nominal_rekap_baris($tanggal_rekap, $nama_rekap)
	{
		$this->load->helper('persediaan_display');
		$row = $this->db->query(
			"SELECT `nominal` FROM `persediaan_rekap_view` WHERE `tanggal_rekap`=? AND `nama_rekap`=? LIMIT 1",
			array($tanggal_rekap, $nama_rekap)
		)->row();
		if (!$row) {
			return 0;
		}
		return persediaan_parse_angka($row->nominal);
	}

	/**
	 * Satu langkah rekalkulasi rekap (untuk progress AJAX).
	 */
	private function sync_persediaan_rekap_step($bulan, $step)
	{
		$this->load->helper('persediaan_display');

		$tanggal_rekap = $this->get_tanggal_rekap_dari_bulan($bulan);
		$tanggal_beli_bulan_ini = $tanggal_rekap;
		$tanggal_beli_bulan_lalu = date('Y-m-01', strtotime('-1 month', strtotime($tanggal_rekap)));
		$next_id = $this->get_next_id_persediaan_rekap_view();
		$total_steps = $this->get_rekap_total_steps();

		$nominal = 0;
		$nama_rekap = '';
		$keterangan = '';
		$info_proses = '';

		if ($step === 1) {
			$nama_rekap = 'Sediaan Awal';
			$nominal = $this->sum_persediaan_nilai_persediaan($tanggal_beli_bulan_lalu);
			$keterangan = 'Rekalkulasi: total nilai_persediaan bulan ' . $tanggal_beli_bulan_lalu;
			$info_proses = 'Menghitung total nilai persediaan bulan sebelumnya';
		} elseif ($step === 2) {
			$nama_rekap = 'Pembelian PU';
			$nominal = $this->sum_persediaan_kolom_kali_hpp($tanggal_beli_bulan_ini, 'dinas_umum');
			$keterangan = 'Rekalkulasi: sum(dinas_umum * hpp) bulan ' . $tanggal_beli_bulan_ini;
			$info_proses = 'Menghitung sum(dinas_umum × hpp)';
		} elseif ($step === 3) {
			$nama_rekap = 'Pembelian Cetak';
			$nominal = $this->sum_persediaan_kolom_kali_hpp($tanggal_beli_bulan_ini, 'cetak');
			$keterangan = 'Rekalkulasi: sum(cetak * hpp) bulan ' . $tanggal_beli_bulan_ini;
			$info_proses = 'Menghitung sum(cetak × hpp)';
		} elseif ($step === 4) {
			$nama_rekap = 'Pembelian Grafikita';
			$nominal = $this->sum_persediaan_kolom_kali_hpp($tanggal_beli_bulan_ini, 'grafikita');
			$keterangan = 'Rekalkulasi: sum(grafikita * hpp) bulan ' . $tanggal_beli_bulan_ini;
			$info_proses = 'Menghitung sum(grafikita × hpp)';
		} elseif ($step === 5) {
			$nama_rekap = 'TUJ';
			$sa = $this->get_nominal_rekap_baris($tanggal_rekap, 'Sediaan Awal');
			$pu = $this->get_nominal_rekap_baris($tanggal_rekap, 'Pembelian PU');
			$cetak = $this->get_nominal_rekap_baris($tanggal_rekap, 'Pembelian Cetak');
			$graf = $this->get_nominal_rekap_baris($tanggal_rekap, 'Pembelian Grafikita');
			$nominal = $sa + $cetak + $pu + $graf;
			$keterangan = 'Rekalkulasi: Sediaan Awal + Pembelian Cetak + Pembelian PU + Pembelian Grafikita';
			$info_proses = 'Menjumlahkan Sediaan Awal + Pembelian Cetak + PU + Grafikita';
		} elseif ($step === 6) {
			$nama_rekap = 'Sediaan Akhir';
			$nominal = $this->sum_persediaan_nilai_persediaan($tanggal_beli_bulan_ini);
			$keterangan = 'Rekalkulasi: total nilai_persediaan bulan ' . $tanggal_beli_bulan_ini;
			$info_proses = 'Menghitung total nilai persediaan bulan terpilih';
		} elseif ($step === 7) {
			$nama_rekap = 'BPP';
			$tuj = $this->get_nominal_rekap_baris($tanggal_rekap, 'TUJ');
			$akhir = $this->get_nominal_rekap_baris($tanggal_rekap, 'Sediaan Akhir');
			$nominal = $tuj - $akhir;
			$keterangan = 'Rekalkulasi: TUJ - Sediaan Akhir';
			$info_proses = 'Menghitung TUJ dikurangi Sediaan Akhir';
		} else {
			$idx = $step - 8;
			$breakdown = $this->get_rekap_breakdown_config();
			if (!isset($breakdown[$idx])) {
				return array('ok' => false, 'message' => 'Langkah rekalkulasi tidak ditemukan.');
			}
			$cfg = $breakdown[$idx];
			$nama_rekap = $cfg['nama'];
			$nominal = $this->sum_persediaan_kolom($tanggal_beli_bulan_ini, $cfg['kolom']);
			$keterangan = 'Rekalkulasi: sum(' . $cfg['kolom'] . ') bulan ' . $tanggal_beli_bulan_ini;
			$info_proses = 'Menghitung sum(' . $cfg['kolom'] . ')';
		}

		$aksi = $this->upsert_persediaan_rekap_baris($tanggal_rekap, $nama_rekap, $nominal, $keterangan, $next_id);

		return array(
			'ok' => true,
			'bulan' => $bulan,
			'tanggal_rekap' => $tanggal_rekap,
			'step' => $step,
			'total_steps' => $total_steps,
			'nama_rekap' => $nama_rekap,
			'nominal' => $nominal,
			'nominal_tampil' => number_format($nominal, 0, ',', '.'),
			'aksi' => $aksi,
			'info_proses' => $info_proses,
			'message' => 'Record ' . $step . '/' . $total_steps . ': ' . $nama_rekap . ' — ' . ($aksi === 'insert' ? 'ditambahkan' : 'diperbarui'),
			'done' => ($step >= $total_steps),
		);
	}

	/**
	 * Rekalkulasi rekap penuh (semua langkah).
	 */
	private function sync_persediaan_rekap_data($bulan)
	{
		$insert_count = 0;
		$update_count = 0;
		$total_steps = $this->get_rekap_total_steps();

		for ($step = 1; $step <= $total_steps; $step++) {
			$hasil = $this->sync_persediaan_rekap_step($bulan, $step);
			if (empty($hasil['ok'])) {
				continue;
			}
			if (isset($hasil['aksi']) && $hasil['aksi'] === 'insert') {
				$insert_count++;
			} elseif (isset($hasil['aksi']) && $hasil['aksi'] === 'update') {
				$update_count++;
			}
		}

		$hasil_rekap = $this->get_persediaan_rekap_rows($bulan);

		return array(
			'insert' => $insert_count,
			'update' => $update_count,
			'total_detail' => $hasil_rekap['total_detail'],
		);
	}

	public function cetak_pdf()
	{
		$bulan = trim((string) $this->input->post('bulan_persediaan', TRUE));
		if ($bulan === '') {
			$bulan = date('Y-m');
		}
		$Persediaan = $this->get_persediaan_by_bulan($bulan);

		$this->load->helper('persediaan_display');

		$data = array(
			'persediaan_data' => $Persediaan,
			'start' => 0,
			'bulan_persediaan_selected' => $bulan,
		);

		@ini_set('memory_limit', '1024M');
		@set_time_limit(300);
		$this->load->library('pdf');
		$this->pdf->setPaper('A3', 'landscape');
		$waktu_klik = date('Y-m-d_H-i-s');
		$this->pdf->filename = 'Persediaan_' . $bulan . '_' . $waktu_klik . '.pdf';
		$this->pdf->load_view('anekadharma/persediaan/persediaan_pdf', $data);
	}

	public function recalculate_data_persediaan()
	{
		@set_time_limit(0);
		@ini_set('memory_limit', '1024M');

		header('Content-Type: text/html; charset=UTF-8');
		echo '<!doctype html><html><head><meta charset="utf-8"><title>Recalculate UUID Persediaan</title></head><body>';
		echo '<h3>Proses Recalculate Data Persediaan</h3>';
		echo '<p>Update field berikut di tabel <strong>persediaan</strong> dari <strong>persediaan_lama</strong> (filter: namabarang + satuan + hpp sama):</p>';
		echo '<p>uuid_persediaan, uuid_spop, uuid_gudang, nama_gudang, uuid_barang, kode_barang</p>';
		echo '<ul>';
		echo '<li><strong>MATCH</strong> = copy data dari persediaan_lama</li>';
		echo '<li><strong>MATCH + AUTO UUID</strong> = record cocok tapi uuid_persediaan lama kosong, uuid_persediaan dibuat otomatis (field lain tetap dicopy)</li>';
		echo '<li><strong>TIDAK MATCH</strong> = tidak ada di persediaan_lama, uuid_persediaan dibuat otomatis, field uuid lain dikosongkan</li>';
		echo '</ul>';
		echo '<hr>';

		$sql_persediaan = "SELECT `id`,`namabarang`,`satuan`,`hpp` FROM `persediaan` ORDER BY `id` ASC";
		$list_persediaan = $this->db->query($sql_persediaan)->result();

		$total_data = count($list_persediaan);
		$total_match = 0;
		$total_match_auto_uuid = 0;
		$total_tidak_match = 0;
		$total_update = 0;

		echo 'Total data persediaan diproses: ' . $total_data . '<br/><br/>';

		foreach ($list_persediaan as $row) {
			$nama = trim((string) $row->namabarang);
			$satuan = trim((string) $row->satuan);
			$hpp = trim((string) $row->hpp);

			$sql_lama = "SELECT `uuid_persediaan`,`uuid_spop`,`uuid_gudang`,`nama_gudang`,`uuid_barang`,`kode_barang`
				FROM `persediaan_lama`
				WHERE TRIM(`namabarang`)=? AND TRIM(`satuan`)=?
				AND CAST(REPLACE(TRIM(`hpp`),',','') AS DECIMAL(18,2))=CAST(REPLACE(?,',','') AS DECIMAL(18,2))
				ORDER BY `id` ASC LIMIT 1";
			$data_lama = $this->db->query($sql_lama, array($nama, $satuan, $hpp))->row();

			$this->db->where('id', $row->id);

			if ($data_lama) {
				$this->db->set('uuid_spop', $data_lama->uuid_spop);
				$this->db->set('uuid_gudang', $data_lama->uuid_gudang);
				$this->db->set('nama_gudang', $data_lama->nama_gudang);
				$this->db->set('uuid_barang', $data_lama->uuid_barang);
				$this->db->set('kode_barang', $data_lama->kode_barang);

				if (!empty($data_lama->uuid_persediaan)) {
					$status = 'MATCH';
					$total_match++;
					$this->db->set('uuid_persediaan', $data_lama->uuid_persediaan);
				} else {
					$status = 'MATCH + AUTO UUID';
					$total_match_auto_uuid++;
					$this->db->set('uuid_persediaan', "replace(uuid(),'-','')", FALSE);
				}
			} else {
				$status = 'TIDAK MATCH';
				$total_tidak_match++;
				$this->db->set('uuid_persediaan', "replace(uuid(),'-','')", FALSE);
				$this->db->set('uuid_spop', '');
				$this->db->set('uuid_gudang', '');
				$this->db->set('nama_gudang', '');
				$this->db->set('uuid_barang', '');
				$this->db->set('kode_barang', '');
			}

			$this->db->update('persediaan');
			$total_update++;

			$row_updated = $this->db->query(
				"SELECT `uuid_persediaan`,`uuid_spop`,`uuid_gudang`,`nama_gudang`,`uuid_barang`,`kode_barang`
				FROM `persediaan` WHERE `id`=? LIMIT 1",
				array($row->id)
			)->row();

			echo 'ID: ' . $row->id . ' | ' . $status
				. ' | NAMABARANG: ' . htmlspecialchars($nama)
				. ' | SATUAN: ' . htmlspecialchars($satuan)
				. ' | HPP: ' . $hpp
				. ' | uuid_persediaan: ' . ($row_updated ? $row_updated->uuid_persediaan : '')
				. ' | uuid_spop: ' . ($row_updated ? $row_updated->uuid_spop : '')
				. ' | uuid_gudang: ' . ($row_updated ? $row_updated->uuid_gudang : '')
				. ' | nama_gudang: ' . ($row_updated ? htmlspecialchars($row_updated->nama_gudang) : '')
				. ' | uuid_barang: ' . ($row_updated ? $row_updated->uuid_barang : '')
				. ' | kode_barang: ' . ($row_updated ? $row_updated->kode_barang : '')
				. '<br/>';
			@ob_flush();
			@flush();
		}

		echo '<hr>';
		echo '<strong>SELESAI PROSES</strong><br/>';
		echo 'Total diproses: ' . $total_data . '<br/>';
		echo 'Total match (copy semua dari persediaan_lama): ' . $total_match . '<br/>';
		echo 'Total match + auto uuid (cocok tapi uuid lama kosong): ' . $total_match_auto_uuid . '<br/>';
		echo 'Total tidak match (uuid baru + field uuid lain dikosongkan): ' . $total_tidak_match . '<br/>';
		echo 'Total update: ' . $total_update . '<br/><br/>';
		echo 'Ringkasan (print_r):<br/>';
		print_r(array(
			'total_diproses' => $total_data,
			'total_match' => $total_match,
			'total_match_auto_uuid' => $total_match_auto_uuid,
			'total_tidak_match' => $total_tidak_match,
			'total_update' => $total_update,
		));
		echo '<br/><a href="' . site_url('persediaan') . '">Kembali ke Data Persediaan</a>';
		echo '</body></html>';
	}

	/**
	 * Generate data persediaan bulan baru dari salinan bulan sebelumnya.
	 * Contoh: GENERATE_PERSEDIAN_BULAN/2026-01 => copy dari tanggal_beli 2025-12-01 ke 2026-01-01
	 * sa = total_10 - penjualan - pecah_satuan - bahan_produksi (bulan sumber).
	 * beli record baru = 0. total_10 = sa + beli. nilai_persediaan = total_10 * hpp. tuj = sa + beli.
	 * Kolom setelah tuj sampai sebelum total_10 = 0 (tidak disalin dari bulan sumber).
	 * Satu record sumber = satu record target (INSERT). Hanya UPDATE jika baris target sudah
	 * merupakan salinan baris sumber yang sama (uuid_persediaan / nama+satuan+hpp). Ada di
	 * tbl_pembelian/jasa hanya mengisi beli, tidak menghalangi copy.
	 * Tampilan: SweetAlert animasi 5 record terakhir, lalu tabel lengkap di halaman.
	 * AJAX batch: ?ajax=1&offset=0&limit=25
	 */
	public function GENERATE_PERSEDIAN_BULAN($bulan_target = '')
	{
		@set_time_limit(0);
		@ini_set('memory_limit', '1024M');

		if (!$this->persediaan_user_can_generate()) {
			header('Content-Type: text/html; charset=UTF-8');
			echo '<!doctype html><html><head><meta charset="utf-8"><title>Akses ditolak</title></head><body>';
			echo '<p style="color:red;">Generate persediaan hanya untuk user Admin / Administrator (id_user_level 1, 2, atau 99).</p>';
			echo '<p><a href="' . site_url('persediaan') . '">Kembali ke Data Persediaan</a></p>';
			echo '</body></html>';
			return;
		}

		$bulan_target = trim((string) $bulan_target);
		$ctx = $this->get_generate_persediaan_context($bulan_target);

		if ($this->input->get('ajax') === '1') {
			header('Content-Type: application/json; charset=UTF-8');
			if (!$ctx['ok']) {
				echo json_encode(array('ok' => false, 'message' => $ctx['message']));
				return;
			}
			$offset = max(0, (int) $this->input->get('offset'));
			$limit = (int) $this->input->get('limit');
			if ($limit < 1 || $limit > 100) {
				$limit = 25;
			}
			echo json_encode($this->generate_persediaan_bulan_batch($ctx, $offset, $limit));
			return;
		}

		if (!$ctx['ok']) {
			header('Content-Type: text/html; charset=UTF-8');
			echo '<!doctype html><html><head><meta charset="utf-8"><title>Generate Persediaan Bulan</title></head><body>';
			echo '<p style="color:red;">' . htmlspecialchars($ctx['message']) . '</p>';
			echo '<p>Contoh: <a href="' . site_url('Persediaan/GENERATE_PERSEDIAN_BULAN/2026-01') . '">'
				. site_url('Persediaan/GENERATE_PERSEDIAN_BULAN/2026-01') . '</a></p>';
			echo '</body></html>';
			return;
		}

		// Setiap buka halaman generate = proses baru (reset target + salin ulang dari awal)
		$this->session->unset_userdata('gen_pers_' . $ctx['bulan_target']);
		$this->session->unset_userdata('gen_pers_dedup_' . $ctx['bulan_target']);
		$this->session->unset_userdata('gen_pers_reset_' . $ctx['bulan_target']);
		$this->session->unset_userdata('gen_pers_fixuuid_' . $ctx['bulan_target']);

		$data_view = array(
			'bulan_target' => $ctx['bulan_target'],
			'tanggal_beli_target' => $ctx['tanggal_beli_target'],
			'tanggal_beli_sumber' => $ctx['tanggal_beli_sumber'],
			'total_sumber' => $ctx['total_sumber'],
			'ajax_url' => site_url('Persediaan/GENERATE_PERSEDIAN_BULAN/' . $ctx['bulan_target']),
		);
		$this->load->view('anekadharma/persediaan/generate_persediaan_bulan_process', $data_view);
	}

	private function get_generate_persediaan_context($bulan_target)
	{
		if (!preg_match('/^\d{4}-\d{2}$/', $bulan_target)) {
			return array('ok' => false, 'message' => 'Format bulan tidak valid. Gunakan YYYY-MM (contoh: 2026-01).');
		}

		$ts_target = strtotime($bulan_target . '-01');
		if ($ts_target === false) {
			return array('ok' => false, 'message' => 'Bulan target tidak valid.');
		}

		$tanggal_beli_target = date('Y-m-01', $ts_target);
		$tanggal_beli_sumber = date('Y-m-01', strtotime('-1 month', $ts_target));
		$tanggal_tampilan_target = date('d/m/Y', $ts_target);

		$total_sumber = $this->persediaan_count_by_tanggal_beli($tanggal_beli_sumber);
		$total_target = $this->persediaan_count_by_tanggal_beli($tanggal_beli_target);

		if ($total_sumber === 0) {
			return array(
				'ok' => false,
				'message' => 'Tidak ada data sumber dengan tanggal_beli = ' . $tanggal_beli_sumber,
			);
		}

		return array(
			'ok' => true,
			'bulan_target' => $bulan_target,
			'tanggal_beli_target' => $tanggal_beli_target,
			'tanggal_beli_sumber' => $tanggal_beli_sumber,
			'tanggal_tampilan_target' => $tanggal_tampilan_target,
			'tgl_po_awal' => $tanggal_beli_target,
			'tgl_po_akhir' => date('Y-m-t', $ts_target),
			'total_sumber' => $total_sumber,
			'total_target' => $total_target,
		);
	}

	private function generate_persediaan_bulan_batch($ctx, $offset, $limit)
	{
		if (!$this->persediaan_user_can_generate()) {
			return array('ok' => false, 'message' => 'Akses ditolak. Hanya Admin / Administrator.');
		}

		$total_sumber = $ctx['total_sumber'];
		$tanggal_beli_target = $ctx['tanggal_beli_target'];
		$tanggal_beli_sumber = $ctx['tanggal_beli_sumber'];
		$tanggal_tampilan_target = $ctx['tanggal_tampilan_target'];

		$session_key = 'gen_pers_' . $ctx['bulan_target'];
		$fixuuid_session_key = 'gen_pers_fixuuid_' . $ctx['bulan_target'];
		$dedup_session_key = 'gen_pers_dedup_' . $ctx['bulan_target'];
		$reset_session_key = 'gen_pers_reset_' . $ctx['bulan_target'];
		$state = $this->session->userdata($session_key);
		$fixuuid_info = null;
		$dedup_info = null;
		$reset_info = null;

		if ($offset === 0 || !is_array($state)) {
			$row_max = $this->db->query("SELECT MAX(`id`) AS max_id FROM `persediaan`")->row();
			$state = array(
				'next_id' => $row_max && $row_max->max_id ? ((int) $row_max->max_id + 1) : 1,
				'total_insert' => 0,
				'total_update' => 0,
				'total_skip' => 0,
				'fixuuid' => null,
				'dedup' => null,
				'reset_target' => null,
			);

			if (!$this->session->userdata($fixuuid_session_key)) {
				$fixuuid_info = $this->generate_perbaiki_uuid_barang_kosong_sumber($ctx);
				$state['fixuuid'] = $fixuuid_info;
				$this->session->set_userdata($fixuuid_session_key, 1);
			}

			if (!$this->session->userdata($dedup_session_key)) {
				$dedup_info = $this->generate_perbaiki_duplikat_uuid_barang_sumber($ctx);
				$state['dedup'] = $dedup_info;
				$this->session->set_userdata($dedup_session_key, 1);
			}

			if (!$this->session->userdata($reset_session_key)) {
				$reset_info = array(
					'tanggal_beli' => $tanggal_beli_target,
					'dihapus' => $this->generate_kosongkan_bulan_target($tanggal_beli_target),
				);
				$state['reset_target'] = $reset_info;
				$this->session->set_userdata($reset_session_key, 1);
			}
		}

		$sql_batch = "SELECT * FROM `persediaan` WHERE `tanggal_beli`=? ORDER BY `id` ASC LIMIT " . (int) $limit . " OFFSET " . (int) $offset;
		$list_batch = $this->db->query($sql_batch, array($tanggal_beli_sumber))->result();

		$next_id = (int) $state['next_id'];
		$batch_items = array();
		$batch_insert = 0;
		$batch_update = 0;
		$batch_skip = 0;

		foreach ($list_batch as $row) {
			$item = $this->proses_satu_record_generate_persediaan($row, $ctx, $next_id);
			$batch_items[] = $item;

			if ($item['aksi'] === 'INSERT') {
				$batch_insert++;
			} elseif ($item['aksi'] === 'UPDATE') {
				$batch_update++;
			} else {
				$batch_skip++;
			}
		}

		$offset_selesai = $offset + count($list_batch);
		$done = ($offset_selesai >= $total_sumber);

		$state['next_id'] = $next_id;
		$state['total_insert'] += $batch_insert;
		$state['total_update'] += $batch_update;
		$state['total_skip'] += $batch_skip;

		$last_five = count($batch_items) > 5
			? array_slice($batch_items, -5)
			: $batch_items;

		$summary = null;
		if ($done) {
			$summary = array(
				'bulan_target' => $ctx['bulan_target'],
				'tanggal_beli_target' => $ctx['tanggal_beli_target'],
				'tanggal_beli_sumber' => $ctx['tanggal_beli_sumber'],
				'total_sumber' => $total_sumber,
				'total_insert' => (int) $state['total_insert'],
				'total_update' => (int) $state['total_update'],
				'total_skip' => (int) $state['total_skip'],
				'fixuuid' => isset($state['fixuuid']) ? $state['fixuuid'] : null,
				'dedup' => isset($state['dedup']) ? $state['dedup'] : null,
				'reset_target' => isset($state['reset_target']) ? $state['reset_target'] : null,
				'total_target_akhir' => $this->persediaan_count_by_tanggal_beli($tanggal_beli_target),
				'uuid_kosong_target_akhir' => $this->persediaan_count_uuid_barang_kosong($tanggal_beli_target),
			);
			$this->session->unset_userdata($session_key);
			$this->session->unset_userdata($fixuuid_session_key);
			$this->session->unset_userdata($dedup_session_key);
			$this->session->unset_userdata($reset_session_key);
		} else {
			$this->session->set_userdata($session_key, $state);
		}

		return array(
			'ok' => true,
			'done' => $done,
			'offset_selesai' => $offset_selesai,
			'total_sumber' => $total_sumber,
			'batch_insert' => $batch_insert,
			'batch_update' => $batch_update,
			'batch_skip' => $batch_skip,
			'fixuuid' => ($offset === 0 && $fixuuid_info !== null) ? $fixuuid_info : null,
			'dedup' => ($offset === 0 && $dedup_info !== null) ? $dedup_info : null,
			'reset_target' => ($offset === 0 && $reset_info !== null) ? $reset_info : null,
			'items' => $batch_items,
			'last_five' => $last_five,
			'summary' => $summary,
		);
	}

	/**
	 * UUID baru yang belum dipakai di seluruh tabel persediaan.
	 */
	private function generate_buat_uuid_unik_persediaan($kolom)
	{
		$kolom = ($kolom === 'uuid_barang') ? 'uuid_barang' : 'uuid_persediaan';
		$max_try = 30;

		for ($i = 0; $i < $max_try; $i++) {
			$row = $this->db->query('SELECT REPLACE(UUID(), \'-\', \'\') AS u')->row();
			$uuid = $row ? trim((string) $row->u) : '';
			if ($uuid === '') {
				continue;
			}

			$cek = $this->db->query(
				"SELECT `id` FROM `persediaan` WHERE TRIM(COALESCE(`{$kolom}`, '')) = ? LIMIT 1",
				array($uuid)
			)->row();

			if (!$cek) {
				return $uuid;
			}
		}

		return str_replace('.', '', uniqid('', true)) . dechex(mt_rand(0, 0xffffff));
	}

	/**
	 * Cocokkan baris persediaan dengan pembelian/jasa (namabarang=uraian, satuan, hpp) di rentang tgl_po.
	 */
	private function generate_row_cocok_pembelian_bulan($row, $tgl_awal, $tgl_akhir)
	{
		$nama = trim((string) $row->namabarang);
		$satuan = trim((string) $row->satuan);
		$hpp = trim((string) $row->hpp);
		$uuid_barang = trim((string) $row->uuid_barang);
		$uuid_persediaan = trim((string) $row->uuid_persediaan);

		if ($nama === '' || $satuan === '') {
			return false;
		}

		if ($this->generate_hitung_pembelian_barang_bulan(
			'tbl_pembelian',
			$tgl_awal,
			$tgl_akhir,
			$nama,
			$satuan,
			$hpp,
			$uuid_barang,
			$uuid_persediaan
		) > 0) {
			return true;
		}

		return $this->generate_hitung_pembelian_barang_bulan(
			'tbl_pembelian_jasa',
			$tgl_awal,
			$tgl_akhir,
			$nama,
			$satuan,
			$hpp,
			$uuid_barang,
			$uuid_persediaan
		) > 0;
	}

	private function generate_hitung_pembelian_barang_bulan(
		$tabel,
		$tgl_awal,
		$tgl_akhir,
		$nama,
		$satuan,
		$hpp,
		$uuid_barang,
		$uuid_persediaan
	) {
		if (!$this->db->table_exists($tabel)) {
			return 0;
		}

		$params = array($tgl_awal, $tgl_akhir, $nama, $satuan, $hpp);
		$link = array();

		if ($uuid_barang !== '') {
			$link[] = 'TRIM(COALESCE(`uuid_barang`, \'\')) = ?';
			$params[] = $uuid_barang;
		}
		if ($uuid_persediaan !== '') {
			$link[] = 'TRIM(COALESCE(`uuid_persediaan`, \'\')) = ?';
			$params[] = $uuid_persediaan;
		}

		$link_sql = !empty($link) ? ' AND (' . implode(' OR ', $link) . ')' : '';

		$sql = "SELECT COUNT(*) AS jml FROM `{$tabel}`
			WHERE `tgl_po` IS NOT NULL AND `tgl_po` <> '0000-00-00'
			AND DATE(`tgl_po`) >= ? AND DATE(`tgl_po`) <= ?
			AND TRIM(COALESCE(`uraian`, '')) = ?
			AND TRIM(COALESCE(`satuan`, '')) = ?
			AND CAST(REPLACE(TRIM(`harga_satuan`), ',', '') AS DECIMAL(18,2)) = CAST(REPLACE(?, ',', '') AS DECIMAL(18,2))"
			. $link_sql;

		$row = $this->db->query($sql, $params)->row();
		if ($row && (int) $row->jml > 0) {
			return (int) $row->jml;
		}

		// Tanpa syarat uuid — hanya nama, satuan, hpp
		$sql2 = "SELECT COUNT(*) AS jml FROM `{$tabel}`
			WHERE `tgl_po` IS NOT NULL AND `tgl_po` <> '0000-00-00'
			AND DATE(`tgl_po`) >= ? AND DATE(`tgl_po`) <= ?
			AND TRIM(COALESCE(`uraian`, '')) = ?
			AND TRIM(COALESCE(`satuan`, '')) = ?
			AND CAST(REPLACE(TRIM(`harga_satuan`), ',', '') AS DECIMAL(18,2)) = CAST(REPLACE(?, ',', '') AS DECIMAL(18,2))";

		$row2 = $this->db->query($sql2, array($tgl_awal, $tgl_akhir, $nama, $satuan, $hpp))->row();
		return $row2 ? (int) $row2->jml : 0;
	}

	/**
	 * Perbaiki uuid_barang ganda di bulan sumber: yang cocok pembelian (nama+satuan+hpp) tetap;
	 * sisanya dapat uuid_barang & uuid_persediaan baru (unik di persediaan).
	 */
	private function generate_perbaiki_duplikat_uuid_barang_sumber($ctx)
	{
		$tanggal_beli_sumber = $ctx['tanggal_beli_sumber'];
		$tgl_awal = $ctx['tanggal_beli_sumber'];
		$tgl_akhir_sumber = date('Y-m-t', strtotime($tanggal_beli_sumber));
		$tgl_akhir = isset($ctx['tgl_po_akhir']) ? $ctx['tgl_po_akhir'] : $tgl_akhir_sumber;

		$grup_rows = $this->db->query(
			"SELECT TRIM(COALESCE(`uuid_barang`, '')) AS uuid_barang
			FROM `persediaan`
			WHERE `tanggal_beli` = ?
			AND TRIM(COALESCE(`uuid_barang`, '')) <> ''
			GROUP BY TRIM(COALESCE(`uuid_barang`, ''))
			HAVING COUNT(*) > 1",
			array($tanggal_beli_sumber)
		)->result();

		$hasil = array(
			'grup_duplikat' => count($grup_rows),
			'record_diperbaiki' => 0,
			'record_tetap' => 0,
			'detail' => array(),
		);

		foreach ($grup_rows as $g) {
			$uuid_barang_lama = trim((string) $g->uuid_barang);
			$list = $this->db->query(
				"SELECT * FROM `persediaan`
				WHERE `tanggal_beli` = ?
				AND TRIM(COALESCE(`uuid_barang`, '')) = ?
				ORDER BY `id` ASC",
				array($tanggal_beli_sumber, $uuid_barang_lama)
			)->result();

			if (count($list) < 2) {
				continue;
			}

			$keeper_id = null;
			foreach ($list as $row) {
				if ($this->generate_row_cocok_pembelian_bulan($row, $tgl_awal, $tgl_akhir)) {
					$keeper_id = (int) $row->id;
					break;
				}
			}
			if ($keeper_id === null) {
				$keeper_id = (int) $list[0]->id;
			}

			foreach ($list as $row) {
				if ((int) $row->id === $keeper_id) {
					$hasil['record_tetap']++;
					continue;
				}

				$new_uuid_barang = $this->generate_buat_uuid_unik_persediaan('uuid_barang');
				$new_uuid_persediaan = $this->generate_buat_uuid_unik_persediaan('uuid_persediaan');

				$this->db->where('id', (int) $row->id);
				$this->db->update('persediaan', array(
					'uuid_barang' => $new_uuid_barang,
					'uuid_persediaan' => $new_uuid_persediaan,
				));

				$hasil['record_diperbaiki']++;
				if (count($hasil['detail']) < 20) {
					$hasil['detail'][] = array(
						'id' => (int) $row->id,
						'namabarang' => $row->namabarang,
						'uuid_barang_lama' => $uuid_barang_lama,
						'uuid_barang_baru' => $new_uuid_barang,
						'uuid_persediaan_baru' => $new_uuid_persediaan,
					);
				}
			}
		}

		$hasil['pesan'] = 'Perbaikan uuid_barang ganda: ' . $hasil['grup_duplikat'] . ' grup, '
			. $hasil['record_diperbaiki'] . ' record diubah (uuid baru), '
			. $hasil['record_tetap'] . ' record tetap (cocok pembelian/jasa: nama+satuan+hpp).';

		return $hasil;
	}

	/**
	 * Analisa record bulan sumber yang uuid_barang kosong + penyebab (sebelum klik generate).
	 */
	private function analisa_uuid_barang_kosong_generate($ctx)
	{
		$tanggal_beli_sumber = $ctx['tanggal_beli_sumber'];
		$rows = $this->db->query(
			"SELECT `id`,`namabarang`,`satuan`,`hpp`,`kode_barang`,`kode`,`uuid_persediaan`
			FROM `persediaan`
			WHERE `tanggal_beli` = ?
			AND TRIM(COALESCE(`uuid_barang`, '')) = ''
			ORDER BY `id` ASC",
			array($tanggal_beli_sumber)
		)->result();

		$rekap_map = array();
		$daftar_sample = array();

		foreach ($rows as $row) {
			$diag = $this->diagnosa_penyebab_uuid_barang_kosong($row, $ctx);
			$kode = $diag['kode'];
			if (!isset($rekap_map[$kode])) {
				$rekap_map[$kode] = array(
					'kode' => $kode,
					'label' => $diag['label'],
					'jumlah' => 0,
					'saran' => $diag['saran'],
				);
			}
			$rekap_map[$kode]['jumlah']++;

			if (count($daftar_sample) < 25) {
				$daftar_sample[] = array(
					'id' => (int) $row->id,
					'namabarang' => trim((string) $row->namabarang),
					'satuan' => trim((string) $row->satuan),
					'hpp' => trim((string) $row->hpp),
					'kode_barang' => trim((string) $row->kode_barang),
					'penyebab_kode' => $kode,
					'penyebab' => $diag['label'],
					'detail' => $diag['detail'],
					'saran' => $diag['saran'],
				);
			}
		}

		$rekap_penyebab = array_values($rekap_map);
		usort($rekap_penyebab, function ($a, $b) {
			return $b['jumlah'] - $a['jumlah'];
		});

		$total = count($rows);
		$penjelasan = ($total === 0)
			? 'Semua record bulan sumber sudah memiliki uuid_barang.'
			: 'Ditemukan ' . $total . ' record tanpa uuid_barang. Saat generate, masing-masing akan mendapat uuid_barang baru yang unik di bulan sumber, lalu disalin ke bulan target.';

		return array(
			'total_kosong_sumber' => $total,
			'rekap_penyebab' => $rekap_penyebab,
			'daftar_sample' => $daftar_sample,
			'penjelasan' => $penjelasan,
			'akan_perbaiki_otomatis' => ($total > 0),
		);
	}

	/**
	 * Penyebab uuid_barang kosong pada satu baris persediaan bulan sumber.
	 */
	private function diagnosa_penyebab_uuid_barang_kosong($row, $ctx)
	{
		$nama = trim((string) $row->namabarang);
		$satuan = trim((string) $row->satuan);
		$kode_barang = trim((string) $row->kode_barang);
		$tgl_awal = isset($ctx['tanggal_beli_sumber']) ? $ctx['tanggal_beli_sumber'] : '';
		$tgl_akhir = isset($ctx['tgl_po_akhir'])
			? $ctx['tgl_po_akhir']
			: date('Y-m-t', strtotime(isset($ctx['tanggal_beli_target']) ? $ctx['tanggal_beli_target'] : $tgl_awal));

		if ($nama === '') {
			return array(
				'kode' => 'nama_kosong',
				'label' => 'Nama barang kosong',
				'detail' => 'Field namabarang kosong sehingga tidak bisa dicocokkan ke master barang (sys_nama_barang).',
				'saran' => 'Lengkapi nama barang di persediaan bulan sumber, lalu generate ulang.',
			);
		}

		$master = $this->generate_cari_master_sys_nama_barang($nama, $satuan);
		if (!$master) {
			return array(
				'kode' => 'master_tidak_ditemukan',
				'label' => 'Tidak ada di master (sys_nama_barang)',
				'detail' => 'Nama "' . $nama . '"' . ($satuan !== '' ? ' / satuan ' . $satuan : '') . ' tidak ditemukan di tabel sys_nama_barang.',
				'saran' => 'Tambahkan barang ke master sys_nama_barang atau biarkan generate membuat uuid_barang baru otomatis.',
			);
		}

		$uuid_master = trim((string) $master->uuid_barang);
		if ($uuid_master === '') {
			return array(
				'kode' => 'master_tanpa_uuid',
				'label' => 'Master ada, uuid_barang master kosong',
				'detail' => 'Record master sys_nama_barang untuk "' . $nama . '" ada tetapi kolom uuid_barang di master masih kosong.',
				'saran' => 'Isi uuid_barang di sys_nama_barang, atau generate akan membuat uuid baru untuk baris persediaan ini.',
			);
		}

		$uuid_pembelian = $this->generate_cari_uuid_barang_dari_pembelian_row($row, $tgl_awal, $tgl_akhir);
		if ($uuid_pembelian !== '') {
			return array(
				'kode' => 'pembelian_punya_uuid',
				'label' => 'Pembelian sudah punya uuid, persediaan kosong',
				'detail' => 'Di tbl_pembelian/jasa ditemukan uuid_barang=' . $uuid_pembelian
					. ' untuk barang ini, tetapi persediaan bulan sumber belum terisi.',
				'saran' => 'Generate akan membuat uuid_barang baru unik (tidak menyalin dari pembelian agar tiap baris persediaan unik).',
			);
		}

		if ($kode_barang === '') {
			return array(
				'kode' => 'import_manual',
				'label' => 'Input manual / import (kode_barang kosong)',
				'detail' => 'kode_barang kosong; umum pada data import CSV atau input manual tanpa sinkron master.',
				'saran' => 'Jalankan sinkron dari sys_nama_barang (menu refresh) atau lanjutkan generate (uuid baru otomatis).',
			);
		}

		return array(
			'kode' => 'belum_sinkron',
			'label' => 'Belum disinkronkan ke persediaan',
			'detail' => 'Master sys_nama_barang punya uuid_barang, tetapi baris persediaan bulan sumber belum di-update (belum sinkron).',
			'saran' => 'Generate akan membuat uuid_barang baru unik per baris sebelum disalin.',
		);
	}

	private function generate_cari_master_sys_nama_barang($nama, $satuan = '')
	{
		if (!$this->db->table_exists('sys_nama_barang')) {
			return null;
		}

		$row = $this->db->query(
			"SELECT `uuid_barang`,`kode_barang`,`nama_barang`,`satuan`
			FROM `sys_nama_barang`
			WHERE TRIM(COALESCE(`nama_barang`, '')) = ?
			LIMIT 1",
			array($nama)
		)->row();

		if ($row) {
			return $row;
		}

		if ($satuan === '') {
			return null;
		}

		return $this->db->query(
			"SELECT `uuid_barang`,`kode_barang`,`nama_barang`,`satuan`
			FROM `sys_nama_barang`
			WHERE TRIM(COALESCE(`nama_barang`, '')) = ?
			AND TRIM(COALESCE(`satuan`, '')) = ?
			LIMIT 1",
			array($nama, $satuan)
		)->row();
	}

	private function generate_cari_uuid_barang_dari_pembelian_row($row, $tgl_awal, $tgl_akhir)
	{
		$nama = trim((string) $row->namabarang);
		$satuan = trim((string) $row->satuan);
		$hpp = trim((string) $row->hpp);
		if ($nama === '' || $satuan === '') {
			return '';
		}

		foreach (array('tbl_pembelian', 'tbl_pembelian_jasa') as $tabel) {
			if (!$this->db->table_exists($tabel)) {
				continue;
			}

			$sql = "SELECT TRIM(COALESCE(`uuid_barang`, '')) AS uuid_barang
				FROM `{$tabel}`
				WHERE STR_TO_DATE(`tgl_po`, '%e-%c-%Y') BETWEEN ? AND ?
				AND TRIM(COALESCE(`uraian`, '')) = ?
				AND TRIM(COALESCE(`satuan`, '')) = ?
				AND CAST(REPLACE(TRIM(`harga_satuan`), ',', '') AS DECIMAL(18,2)) = CAST(REPLACE(?, ',', '') AS DECIMAL(18,2))
				AND TRIM(COALESCE(`uuid_barang`, '')) <> ''
				ORDER BY `id` DESC
				LIMIT 1";
			$found = $this->db->query($sql, array($tgl_awal, $tgl_akhir, $nama, $satuan, $hpp))->row();
			if ($found && trim((string) $found->uuid_barang) !== '') {
				return trim((string) $found->uuid_barang);
			}
		}

		return '';
	}

	/**
	 * Isi uuid_barang kosong di bulan sumber — tiap baris dapat uuid unik sebelum disalin.
	 */
	private function generate_perbaiki_uuid_barang_kosong_sumber($ctx)
	{
		$tanggal_beli_sumber = $ctx['tanggal_beli_sumber'];
		$rows = $this->db->query(
			"SELECT `id`,`namabarang`,`satuan`,`hpp`,`kode_barang`,`kode`,`uuid_persediaan`
			FROM `persediaan`
			WHERE `tanggal_beli` = ?
			AND TRIM(COALESCE(`uuid_barang`, '')) = ''
			ORDER BY `id` ASC",
			array($tanggal_beli_sumber)
		)->result();

		$hasil = array(
			'record_kosong' => count($rows),
			'record_diperbaiki' => 0,
			'rekap_penyebab' => array(),
			'detail' => array(),
			'pesan' => '',
		);

		$rekap_map = array();

		foreach ($rows as $row) {
			$diag = $this->diagnosa_penyebab_uuid_barang_kosong($row, $ctx);
			$kode_penyebab = $diag['kode'];
			if (!isset($rekap_map[$kode_penyebab])) {
				$rekap_map[$kode_penyebab] = array(
					'kode' => $kode_penyebab,
					'label' => $diag['label'],
					'jumlah' => 0,
				);
			}
			$rekap_map[$kode_penyebab]['jumlah']++;

			$uuid_baru = $this->generate_buat_uuid_unik_persediaan('uuid_barang');
			$update = array('uuid_barang' => $uuid_baru);

			$master = $this->generate_cari_master_sys_nama_barang(
				trim((string) $row->namabarang),
				trim((string) $row->satuan)
			);
			if ($master && trim((string) $row->kode_barang) === '') {
				$kode_master = trim((string) $master->kode_barang);
				if ($kode_master !== '') {
					$update['kode_barang'] = $kode_master;
				}
			}

			$this->db->where('id', (int) $row->id);
			$this->db->update('persediaan', $update);
			$hasil['record_diperbaiki']++;

			if (count($hasil['detail']) < 25) {
				$hasil['detail'][] = array(
					'id' => (int) $row->id,
					'namabarang' => trim((string) $row->namabarang),
					'satuan' => trim((string) $row->satuan),
					'hpp' => trim((string) $row->hpp),
					'penyebab' => $diag['label'],
					'penyebab_kode' => $kode_penyebab,
					'uuid_barang_baru' => $uuid_baru,
				);
			}
		}

		$hasil['rekap_penyebab'] = array_values($rekap_map);
		usort($hasil['rekap_penyebab'], function ($a, $b) {
			return $b['jumlah'] - $a['jumlah'];
		});

		$hasil['pesan'] = 'Perbaikan uuid_barang kosong (bulan sumber): '
			. $hasil['record_kosong'] . ' record ditemukan, '
			. $hasil['record_diperbaiki'] . ' record diberi uuid_barang baru (unik per baris).';

		return $hasil;
	}

	/**
	 * Kosongkan semua persediaan bulan target sebelum salin ulang (agar jumlah = bulan sumber).
	 */
	private function generate_kosongkan_bulan_target($tanggal_beli_target)
	{
		$count = $this->persediaan_count_by_tanggal_beli($tanggal_beli_target);
		if ($count > 0) {
			$this->db->where('tanggal_beli', $tanggal_beli_target);
			$this->db->delete('persediaan');
		}

		return (int) $count;
	}

	/**
	 * @deprecated Tidak dipakai — generate selalu INSERT semua baris sumber.
	 */
	private function generate_cari_persediaan_target($tanggal_beli_target, $row)
	{
		$uuid_persediaan = trim((string) $row->uuid_persediaan);
		$nama = trim((string) $row->namabarang);
		$satuan = trim((string) $row->satuan);
		$hpp = trim((string) $row->hpp);

		if ($uuid_persediaan !== '') {
			$found = $this->db->query(
				"SELECT * FROM `persediaan`
				WHERE `tanggal_beli` = ?
				AND (
					TRIM(COALESCE(`uuid_persediaan`, '')) = ?
					OR TRIM(COALESCE(`uuid_persediaan_lama`, '')) = ?
				)
				LIMIT 1",
				array($tanggal_beli_target, $uuid_persediaan, $uuid_persediaan)
			)->row();
			if ($found) {
				return $found;
			}
		}

		if ($nama !== '' && $satuan !== '') {
			return $this->db->query(
				"SELECT * FROM `persediaan`
				WHERE `tanggal_beli` = ?
				AND TRIM(`namabarang`) = ?
				AND TRIM(`satuan`) = ?
				AND CAST(REPLACE(TRIM(`hpp`), ',', '') AS DECIMAL(18,2)) = CAST(REPLACE(?, ',', '') AS DECIMAL(18,2))
				LIMIT 1",
				array($tanggal_beli_target, $nama, $satuan, $hpp)
			)->row();
		}

		return null;
	}

	/**
	 * Hitung beli dari tbl_pembelian / tbl_pembelian_jasa (uuid di pembelian tidak menghalangi copy).
	 */
	private function generate_hitung_beli_dari_pembelian_for_row($row, $tgl_awal, $tgl_akhir)
	{
		$uuid_barang = trim((string) $row->uuid_barang);
		$uuid_persediaan = trim((string) $row->uuid_persediaan);
		$nama = trim((string) $row->namabarang);

		$jumlah = 0;
		if ($uuid_persediaan !== '') {
			$jumlah = $this->generate_sum_jumlah_pembelian_by_uuid_persediaan($tgl_awal, $tgl_akhir, $uuid_persediaan);
		}

		if ($jumlah <= 0 && $uuid_barang !== '') {
			$jumlah = $this->generate_sum_jumlah_tbl_pembelian_bulan(
				$tgl_awal,
				$tgl_akhir,
				$uuid_barang,
				$uuid_persediaan,
				$nama
			);
		}

		if ($jumlah <= 0 && $uuid_barang !== '') {
			$jumlah = $this->generate_sum_jumlah_tbl_pembelian_jasa_bulan($tgl_awal, $tgl_akhir, $uuid_barang);
		}

		if ($jumlah <= 0 && $uuid_persediaan !== '' && $this->db->field_exists('uuid_persediaan', 'tbl_pembelian_jasa')) {
			$jumlah = $this->generate_sum_jumlah_pembelian_jasa_by_uuid_persediaan($tgl_awal, $tgl_akhir, $uuid_persediaan);
		}

		return max(0, (int) $jumlah);
	}

	/**
	 * Total jumlah pembelian barang (tbl_pembelian) di bulan target.
	 */
	private function generate_sum_jumlah_tbl_pembelian_bulan($tgl_awal, $tgl_akhir, $uuid_barang, $uuid_persediaan, $nama_barang)
	{
		if (!$this->db->table_exists('tbl_pembelian')) {
			return 0;
		}

		$uuid_barang = trim((string) $uuid_barang);
		$uuid_persediaan = trim((string) $uuid_persediaan);
		$nama_barang = trim((string) $nama_barang);
		$parts = array();
		$params = array($tgl_awal, $tgl_akhir);

		if ($uuid_barang !== '') {
			$parts[] = 'TRIM(COALESCE(`uuid_barang`,\'\')) = ?';
			$params[] = $uuid_barang;
		}
		if ($uuid_persediaan !== '') {
			$parts[] = 'TRIM(COALESCE(`uuid_persediaan`,\'\')) = ?';
			$params[] = $uuid_persediaan;
		}
		if ($nama_barang !== '') {
			$parts[] = 'TRIM(COALESCE(`uraian`,\'\')) = ?';
			$params[] = $nama_barang;
		}

		if (empty($parts)) {
			return 0;
		}

		$sql = 'SELECT COALESCE(SUM(CAST(`jumlah` AS SIGNED)), 0) AS jml FROM `tbl_pembelian`
			WHERE `tgl_po` IS NOT NULL AND `tgl_po` <> \'0000-00-00\'
			AND DATE(`tgl_po`) >= ? AND DATE(`tgl_po`) <= ?
			AND (' . implode(' OR ', $parts) . ')';

		$row = $this->db->query($sql, $params)->row();
		return $row ? (int) $row->jml : 0;
	}

	/**
	 * Total jumlah pembelian jasa (tbl_pembelian_jasa) di bulan target — by uuid_barang.
	 */
	private function generate_sum_jumlah_tbl_pembelian_jasa_bulan($tgl_awal, $tgl_akhir, $uuid_barang)
	{
		$uuid_barang = trim((string) $uuid_barang);
		if ($uuid_barang === '' || !$this->db->table_exists('tbl_pembelian_jasa')) {
			return 0;
		}

		$sql = 'SELECT COALESCE(SUM(CAST(`jumlah` AS SIGNED)), 0) AS jml FROM `tbl_pembelian_jasa`
			WHERE `tgl_po` IS NOT NULL AND `tgl_po` <> \'0000-00-00\'
			AND DATE(`tgl_po`) >= ? AND DATE(`tgl_po`) <= ?
			AND TRIM(COALESCE(`uuid_barang`,\'\')) = ?';

		$row = $this->db->query($sql, array($tgl_awal, $tgl_akhir, $uuid_barang))->row();
		return $row ? (int) $row->jml : 0;
	}

	/**
	 * Total jumlah pembelian barang by uuid_persediaan (tbl_pembelian) di bulan target.
	 */
	private function generate_sum_jumlah_pembelian_by_uuid_persediaan($tgl_awal, $tgl_akhir, $uuid_persediaan)
	{
		$uuid_persediaan = trim((string) $uuid_persediaan);
		if ($uuid_persediaan === '' || !$this->db->table_exists('tbl_pembelian')) {
			return 0;
		}

		$sql = 'SELECT COALESCE(SUM(CAST(`jumlah` AS SIGNED)), 0) AS jml FROM `tbl_pembelian`
			WHERE `tgl_po` IS NOT NULL AND `tgl_po` <> \'0000-00-00\'
			AND DATE(`tgl_po`) >= ? AND DATE(`tgl_po`) <= ?
			AND TRIM(COALESCE(`uuid_persediaan`,\'\')) = ?';

		$row = $this->db->query($sql, array($tgl_awal, $tgl_akhir, $uuid_persediaan))->row();
		return $row ? (int) $row->jml : 0;
	}

	/**
	 * Total jumlah pembelian jasa by uuid_persediaan (tbl_pembelian_jasa) di bulan target.
	 */
	private function generate_sum_jumlah_pembelian_jasa_by_uuid_persediaan($tgl_awal, $tgl_akhir, $uuid_persediaan)
	{
		$uuid_persediaan = trim((string) $uuid_persediaan);
		if ($uuid_persediaan === '' || !$this->db->table_exists('tbl_pembelian_jasa')) {
			return 0;
		}

		if (!$this->db->field_exists('uuid_persediaan', 'tbl_pembelian_jasa')) {
			return 0;
		}

		$sql = 'SELECT COALESCE(SUM(CAST(`jumlah` AS SIGNED)), 0) AS jml FROM `tbl_pembelian_jasa`
			WHERE `tgl_po` IS NOT NULL AND `tgl_po` <> \'0000-00-00\'
			AND DATE(`tgl_po`) >= ? AND DATE(`tgl_po`) <= ?
			AND TRIM(COALESCE(`uuid_persediaan`,\'\')) = ?';

		$row = $this->db->query($sql, array($tgl_awal, $tgl_akhir, $uuid_persediaan))->row();
		return $row ? (int) $row->jml : 0;
	}

	/**
	 * Hitung SA dari sisa stock bulan sumber (total_10 - penjualan - pecah - produksi).
	 */
	private function generate_hitung_sa_dari_bulan_sumber($row)
	{
		$total_10_sumber = $this->parse_angka_persediaan($row->total_10);
		$penjualan_sumber = $this->parse_angka_persediaan($row->penjualan);
		$pecah_satuan_sumber = $this->parse_angka_persediaan($row->pecah_satuan);
		$bahan_produksi_sumber = $this->parse_angka_persediaan($row->bahan_produksi);

		return $total_10_sumber - $penjualan_sumber - $pecah_satuan_sumber - $bahan_produksi_sumber;
	}

	/**
	 * Update beli / total_10 / nilai_persediaan / tuj (SA tetap) pada record persediaan bulan target.
	 */
	private function generate_update_persediaan_beli($existing, $beli_angka, $keterangan_extra = '')
	{
		$beli_angka = max(0, (int) $beli_angka);
		$sa_angka = $this->parse_angka_persediaan($existing->sa);
		$hpp_angka = $this->parse_angka_persediaan($existing->hpp);
		$total_10_baru = $sa_angka + $beli_angka;
		$nilai_persediaan_baru = $total_10_baru * $hpp_angka;

		$sa_tampil = $this->format_angka_persediaan($sa_angka);
		$beli_tampil = $this->format_angka_persediaan($beli_angka);
		$total_10_tampil = $this->format_angka_persediaan($total_10_baru);
		$nilai_persediaan_tampil = $this->format_angka_persediaan($nilai_persediaan_baru);
		$tuj_tampil = $total_10_tampil;

		$this->db->where('id', (int) $existing->id);
		$this->db->update('persediaan', array(
			'beli' => $beli_tampil,
			'total_10' => $total_10_tampil,
			'nilai_persediaan' => $nilai_persediaan_tampil,
			'tuj' => $tuj_tampil,
		));

		$keterangan = 'UPDATE beli dari pembelian/generate'
			. ($keterangan_extra !== '' ? ' | ' . $keterangan_extra : '')
			. ' | sa=' . $sa_tampil . ' (tetap)'
			. ' | beli=' . $beli_tampil
			. ' | total_10=' . $total_10_tampil . ' (sa+beli)'
			. ' | nilai_persediaan=' . $nilai_persediaan_tampil;

		return array(
			'aksi' => 'UPDATE',
			'id' => (int) $existing->id,
			'uuid_persediaan' => $existing->uuid_persediaan,
			'namabarang' => $existing->namabarang,
			'satuan' => $existing->satuan,
			'hpp' => $existing->hpp,
			'sa' => $sa_tampil,
			'beli' => $beli_tampil,
			'total_10' => $total_10_tampil,
			'nilai_persediaan' => $nilai_persediaan_tampil,
			'tuj' => $tuj_tampil,
			'keterangan' => $keterangan,
		);
	}

	private function proses_satu_record_generate_persediaan($row, $ctx, &$next_id)
	{
		$tanggal_beli_target = $ctx['tanggal_beli_target'];
		$tanggal_tampilan_target = $ctx['tanggal_tampilan_target'];
		$tgl_po_awal = isset($ctx['tgl_po_awal']) ? $ctx['tgl_po_awal'] : $tanggal_beli_target;
		$tgl_po_akhir = isset($ctx['tgl_po_akhir']) ? $ctx['tgl_po_akhir'] : $tanggal_beli_target;

		$nama = trim((string) $row->namabarang);
		$satuan = trim((string) $row->satuan);
		$hpp = trim((string) $row->hpp);
		$total_10_sumber = $this->parse_angka_persediaan($row->total_10);
		$penjualan_sumber = $this->parse_angka_persediaan($row->penjualan);
		$pecah_satuan_sumber = $this->parse_angka_persediaan($row->pecah_satuan);
		$bahan_produksi_sumber = $this->parse_angka_persediaan($row->bahan_produksi);
		// sa = total_10 - penjualan - pecah_satuan - bahan_produksi (bulan sumber)
		$sa_baru = $total_10_sumber - $penjualan_sumber - $pecah_satuan_sumber - $bahan_produksi_sumber;

		// beli dari pembelian bulan target (uuid_barang di pembelian tetap di-copy / INSERT)
		$beli_angka = $this->generate_hitung_beli_dari_pembelian_for_row($row, $tgl_po_awal, $tgl_po_akhir);
		$beli_tampil = $this->format_angka_persediaan($beli_angka);

		$hpp_angka = $this->parse_angka_persediaan($row->hpp);

		// total_10 = sa + beli (bulan generate); nilai_persediaan = total_10 * hpp
		$total_10_baru = $sa_baru + $beli_angka;
		$nilai_persediaan_baru = $total_10_baru * $hpp_angka;

		$sa_tampil = $this->format_angka_persediaan($sa_baru);
		$total_10_tampil = $this->format_angka_persediaan($total_10_baru);
		$nilai_persediaan_tampil = $this->format_angka_persediaan($nilai_persediaan_baru);

		$tuj_baru = $total_10_baru;
		$tuj_tampil = $total_10_tampil;

		$id_baru = $next_id++;
		$data_insert = array(
			'id' => $id_baru,
			'uuid_persediaan_lama' => $row->uuid_persediaan,
			'uuid_spop' => $row->uuid_spop,
			'uuid_gudang' => $row->uuid_gudang,
			'nama_gudang' => $row->nama_gudang,
			'uuid_barang' => $row->uuid_barang,
			'kode_barang' => $row->kode_barang,
			'tanggal_beli' => $tanggal_beli_target,
			'tanggal' => $tanggal_tampilan_target,
			'kode' => $row->kode,
			'kategori' => isset($row->kategori) ? $row->kategori : null,
			'namabarang' => $row->namabarang,
			'satuan' => $row->satuan,
			'hpp' => $row->hpp,
			'sa' => $sa_tampil,
			'spop' => $row->spop,
			'beli' => $beli_tampil,
			'tuj' => $tuj_tampil,
			// Setelah tuj sampai sebelum total_10: tidak copy bulan sumber, nilai 0
			'tgl_keluar' => '0',
			'sekret' => '0',
			'cetak' => '0',
			'grafikita' => '0',
			'dinas_umum' => '0',
			'atk_rsud' => '0',
			'ppbmp_kbs' => '0',
			'kbs' => '0',
			'ppbmp' => '0',
			'medis' => '0',
			'siiplah_bosda' => '0',
			'sembako' => '0',
			'fc_gose' => '0',
			'fc_manding' => '0',
			'fc_psamya' => '0',
			'kop_mp' => '0',
			'pu_outsor' => '0',
			'total_10' => $total_10_tampil,
			'nilai_persediaan' => $nilai_persediaan_tampil,
			'penjualan' => 0,
			'pecah_satuan' => 0,
			'bahan_produksi' => 0,
		);

		$this->db->set('uuid_persediaan', "replace(uuid(),'-','')", FALSE);
		$this->db->insert('persediaan', $data_insert);

		$new_row = $this->db->query(
			"SELECT `id`,`uuid_persediaan`,`uuid_persediaan_lama` FROM `persediaan` WHERE `id`=? LIMIT 1",
			array($id_baru)
		)->row();

		$keterangan = 'uuid baru: ' . ($new_row ? $new_row->uuid_persediaan : '')
			. ' | sa=' . $sa_tampil . ' (sumber: total_10 ' . $total_10_sumber
			. ' - penj ' . $penjualan_sumber . ' - pecah ' . $pecah_satuan_sumber
			. ' - prod ' . $bahan_produksi_sumber . ')'
			. ' | beli=' . $beli_tampil . ' (pembelian/jasa)'
			. ' | total_10=' . $total_10_tampil . ' (sa+beli)'
			. ' | nilai_persediaan=' . $nilai_persediaan_tampil . ' (total_10*hpp)'
			. ' | tuj=' . $tuj_tampil
			. ' | distribusi (tgl_keluar..fc_psamya)=0';

		return array(
			'aksi' => 'INSERT',
			'id' => $id_baru,
			'uuid_persediaan' => $new_row ? $new_row->uuid_persediaan : '',
			'namabarang' => $nama,
			'satuan' => $satuan,
			'hpp' => $hpp,
			'sa' => $sa_tampil,
			'beli' => $beli_tampil,
			'total_10' => $total_10_tampil,
			'nilai_persediaan' => $nilai_persediaan_tampil,
			'tuj' => $tuj_tampil,
			'keterangan' => $keterangan,
		);
	}

	private function parse_angka_persediaan($value)
	{
		$v = trim((string) $value);
		if ($v === '' || $v === '-') {
			return 0;
		}
		$v = str_replace(',', '', $v);
		$v = preg_replace('/[^0-9.\-]/', '', $v);
		if ($v === '' || $v === '-') {
			return 0;
		}
		return (float) $v;
	}

	private function format_angka_persediaan($value)
	{
		if ($value === '' || $value === null) {
			return '0';
		}
		if (is_numeric($value) && floor((float) $value) == (float) $value) {
			return (string) (int) $value;
		}
		return (string) $value;
	}

	private function persediaan_count_by_tanggal_beli($tanggal_beli)
	{
		$row_cnt = $this->db->query(
			"SELECT COUNT(*) AS jml FROM `persediaan` WHERE `tanggal_beli`=?",
			array($tanggal_beli)
		)->row();

		return $row_cnt ? (int) $row_cnt->jml : 0;
	}

	private function persediaan_count_uuid_barang_kosong($tanggal_beli)
	{
		$row_cnt = $this->db->query(
			"SELECT COUNT(*) AS jml FROM `persediaan`
			WHERE `tanggal_beli` = ?
			AND TRIM(COALESCE(`uuid_barang`, '')) = ''",
			array($tanggal_beli)
		)->row();

		return $row_cnt ? (int) $row_cnt->jml : 0;
	}

	private function get_persediaan_by_bulan($bulan)
	{
		$bulan = trim((string) $bulan);
		if ($bulan === '') {
			return $this->Persediaan_model->get_all();
		}

		$ts = strtotime($bulan . '-01');
		if ($ts === false) {
			return $this->Persediaan_model->get_by_year_month($bulan);
		}

		$tanggal_beli = date('Y-m-01', $ts);
		$rows = $this->db->query(
			"SELECT * FROM `persediaan` WHERE `tanggal_beli`=? ORDER BY `id` DESC",
			array($tanggal_beli)
		)->result();

		if (count($rows) > 0) {
			return $rows;
		}

		return $this->Persediaan_model->get_by_year_month($bulan);
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

	public function excel_rekap()
	{
		$bulan = trim((string) $this->input->post('bulan_persediaan', TRUE));
		if ($bulan === '') {
			$bulan = date('Y-m');
		}

		$this->load->helper(array('exportexcel', 'persediaan_display'));
		$hasil_rekap = $this->get_persediaan_rekap_rows($bulan);

		$bagian_bulan = ($bulan !== '') ? $bulan : 'semua';
		$waktu_klik = date('Y-m-d_H-i-s');
		$waktu_cetak_tampil = date('d/m/Y H:i:s');
		$namaFile = 'Rekap_Persediaan_' . $bagian_bulan . '_' . $waktu_klik . '.xlsx';

		excel_prepare_download($namaFile);
		xlsBOF();

		xlsWriteLabelBold14(0, 0, 'di cetak pada : ' . $waktu_cetak_tampil);
		xlsWriteLabelBold14(1, 0, 'REKAP PERSEDIAAN — Bulan: ' . $bagian_bulan);

		$tablehead = 3;
		$tablebody = 4;
		xlsWriteLabel($tablehead, 0, 'Nomor');
		xlsWriteLabel($tablehead, 1, 'Deskripsi');
		xlsWriteLabel($tablehead, 2, 'Nominal', 'right');

		$row = $tablebody;
		foreach ($hasil_rekap['items'] as $it) {
			xlsWriteLabel($row, 0, (string) $it['nomor']);
			xlsWriteLabel($row, 1, (string) $it['deskripsi']);
			xlsWriteLabel($row, 2, (string) $it['nominal_tampil'], 'right');
			$row++;
		}

		xlsWriteLabel($row, 0, '');
		xlsWriteLabel($row, 1, 'Total (baris 8–21)', 'right');
		xlsWriteLabel($row, 2, (string) $hasil_rekap['total_detail_tampil'], 'right');

		xlsEOF();
		exit();
	}

	public function excel()
	{
		$bulan = trim((string) $this->input->post('bulan_persediaan', TRUE));
		if ($bulan === '') {
			$bulan = date('Y-m');
		}
		$Persediaan = $this->get_persediaan_by_bulan($bulan);

		$this->load->helper(array('exportexcel', 'persediaan_display'));

		$bagian_bulan = ($bulan !== '') ? $bulan : 'semua';
		$waktu_klik = date('Y-m-d_H-i-s');
		$waktu_cetak_tampil = date('d/m/Y H:i:s');
		$namaFile = 'Persediaan_' . $bagian_bulan . '_' . $waktu_klik . '.xlsx';
		$tablehead = 1;
		$tablebody = 2;
		$nourut = 1;
		$total_nilai_persediaan = 0;

		excel_prepare_download($namaFile);
		xlsBOF();

		xlsWriteLabelBold14(0, 0, 'di cetak pada : ' . $waktu_cetak_tampil);

		$kolomhead = 0;
		foreach (persediaan_export_headers() as $header) {
			xlsWriteLabel($tablehead, $kolomhead++, $header);
		}

		foreach ($Persediaan as $data) {
			$total_nilai_persediaan += persediaan_parse_angka(isset($data->nilai_persediaan) ? $data->nilai_persediaan : 0);
			$cells = persediaan_export_row_cells($data, $nourut, $bulan);
			$kolombody = 0;
			foreach ($cells as $cell) {
				xlsWriteLabel($tablebody, $kolombody++, $cell);
			}
			$tablebody++;
			$nourut++;
		}

		// Baris footer sama seperti datatable: Total Nilai Persediaan
		$kolomfoot = 0;
		while ($kolomfoot < 25) {
			xlsWriteLabel($tablebody, $kolomfoot++, '');
		}
		xlsWriteLabel($tablebody, $kolomfoot++, 'Total Nilai Persediaan', 'right');
		xlsWriteLabel($tablebody, $kolomfoot++, number_format($total_nilai_persediaan, 0, ',', '.'), 'right');
		xlsWriteLabel($tablebody, $kolomfoot++, '');
		xlsWriteLabel($tablebody, $kolomfoot++, '');
		xlsWriteLabel($tablebody, $kolomfoot++, '');
		xlsWriteLabel($tablebody, $kolomfoot++, '');

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