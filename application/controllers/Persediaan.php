<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Persediaan extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_login();
		$this->load->model(array('Persediaan_model', 'Sys_konsumen_model'));
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
		$bulan = trim((string) $this->input->get('bulan_persediaan', TRUE));
		if ($bulan === '') {
			$bulan = date('Y-m');
		}
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
			'url_recalculate_persediaan' => site_url('Persediaan/recalculate_data_persediaan'),
			'url_analisa_recalculate_persediaan' => site_url('Persediaan/ajax_analisa_recalculate_persediaan'),
			'url_recalculate_persediaan_batch' => site_url('Persediaan/ajax_recalculate_persediaan_batch'),
			'url_generate_recalculate_batch' => site_url('Persediaan/ajax_generate_recalculate_batch'),
			'url_load_gen_recalc_history' => site_url('Persediaan/ajax_load_gen_recalc_history'),
			'url_excel_gen_recalc' => site_url('Persediaan/excel_gen_recalc'),
			'url_excel_rekonsiliasi_transaksi' => site_url('Persediaan/excel_rekonsiliasi_transaksi'),
			'url_recalculate_excel' => site_url('Persediaan/excel_recalculate'),
			'url_excel_persediaan' => site_url('Persediaan/excel'),
			'url_compare_tabel_list' => site_url('Persediaan/ajax_compare_tabel_list'),
			'url_compare_tabel_run' => site_url('Persediaan/ajax_compare_tabel_run'),
			'url_compare_tabel_excel' => site_url('Persediaan/excel_compare_tabel'),
			'url_compare_tabel_excel_all' => site_url('Persediaan/excel_compare_tabel_all'),
			'url_compare_import_csv' => site_url('Persediaan/ajax_compare_import_csv'),
			'url_compare_tabel_preview' => site_url('Persediaan/ajax_compare_tabel_preview'),
			'gen_bulan_default' => (int) date('n', $ts_gen_default),
			'gen_tahun_default' => (int) date('Y', $ts_gen_default),
			'gen_tahun_min' => 2020,
			'gen_tahun_max' => (int) date('Y') + 2,
			'can_generate_persediaan' => $this->persediaan_user_can_generate(),
			'can_compare_persediaan' => $this->persediaan_user_can_compare(),
			'rekap_total_steps' => $this->get_rekap_total_steps(),
		);
	}

	/**
	 * Email login yang boleh Generate & Recalculate serta Compare (tab Persediaan).
	 */
	private function persediaan_allowed_restricted_emails()
	{
		return array(
			'admin.id@gmail.com',
			'admin.id@gmailc.om',
			'iwanesia.id@gmail.com',
		);
	}

	private function persediaan_current_user_email()
	{
		$candidates = array(
			$this->session->userdata('sess_email_user'),
			$this->session->userdata('email'),
			$this->session->userdata('sess_username'),
		);

		foreach ($candidates as $raw) {
			$email = strtolower(trim((string) $raw));
			if ($email === '') {
				continue;
			}
			if (strpos($email, '@') !== false) {
				return $email;
			}
		}

		return '';
	}

	/**
	 * Apakah bulan target boleh di-generate/recalculate tanpa data bulan sumber.
	 */
	private function persediaan_target_can_proceed_without_source($bulan_target, $tanggal_beli_target, $tgl_awal, $tgl_akhir)
	{
		$this->load->helper('pembelian_persediaan');

		if ($this->persediaan_count_by_tanggal_beli($tanggal_beli_target) > 0) {
			return true;
		}

		$ctx = persediaan_recalculate_full_context($this, $bulan_target);
		if (!empty($ctx['ok']) && !empty($ctx['can_proceed'])) {
			return true;
		}

		return false;
	}

	/**
	 * Tab Generate & Recalculate: hanya email terdaftar di persediaan_allowed_restricted_emails().
	 */
	private function persediaan_user_can_generate()
	{
		$email = $this->persediaan_current_user_email();
		if ($email === '') {
			return false;
		}

		return in_array($email, $this->persediaan_allowed_restricted_emails(), true);
	}

	/**
	 * Tab Compare Data Manual — Online: whitelist sama dengan generate.
	 */
	private function persediaan_user_can_compare()
	{
		return $this->persediaan_user_can_generate();
	}

	private function persediaan_restricted_access_message($action_label = 'fitur ini')
	{
		return $action_label . ' hanya untuk user <strong>admin.id@gmail.com</strong> dan <strong>iwanesia.id@gmail.com</strong>.';
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
				'message' => $this->persediaan_restricted_access_message('Tombol Generate &amp; Recalculate'),
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
		$count_sumber_all = $this->persediaan_count_by_tanggal_beli($tanggal_beli_sumber);
		$count_sumber = $this->persediaan_count_sumber_layak_generate($tanggal_beli_sumber);
		$sudah_ada = ($count_target > 0);
		$tgl_awal = $tanggal_beli_target;
		$tgl_akhir = date('Y-m-t', $ts_target);
		$can_recalc_only = $this->persediaan_target_can_proceed_without_source(
			$bulan_target,
			$tanggal_beli_target,
			$tgl_awal,
			$tgl_akhir
		);
		$can_generate = ($count_sumber_all > 0) || $can_recalc_only;

		$message = '';
		if ($count_sumber_all === 0 && !$can_recalc_only) {
			$message = 'Tidak ada data sumber bulan ' . date('m/Y', strtotime($bulan_sumber . '-01'))
				. ' (tanggal_beli = ' . $tanggal_beli_sumber . ') dan belum ada data/transaksi di bulan target. '
				. 'Isi dulu persediaan bulan sebelumnya atau pastikan ada pembelian/penjualan/produksi di bulan target.';
		} elseif ($count_sumber_all === 0 && $can_recalc_only) {
			$message = 'Bulan sumber kosong — siap <strong>Recalculate</strong> bulan target '
				. date('m/Y', $ts_target) . ' dari data persediaan/transaksi yang ada '
				. '(pembelian, penjualan, produksi, pecah satuan).';
		} elseif ($sudah_ada) {
			$message = 'Bulan target sudah ada <strong>' . $count_target . ' record</strong>. Generate & Recalculate akan: '
				. '(1) hapus baris target sa=0 &amp; total_10=0, '
				. '(2) salin/update <strong>' . $count_sumber . '</strong> record sumber (total_10 &gt;= 1), '
				. '(3) proses pembelian bulan ini → insert baru / update <strong>beli</strong>.';
		} else {
			$message = 'Siap Generate & Recalculate: salin/update <strong>' . $count_sumber . '</strong> record dari bulan '
				. date('m/Y', strtotime($bulan_sumber . '-01')) . ' (hanya total_10 &gt;= 1, dari ' . $count_sumber_all . ' record sumber) ke bulan '
				. date('m/Y', $ts_target) . ', lalu proses pembelian (record baru → insert persediaan).';
		}

		echo json_encode(array(
			'ok' => true,
			'bulan_target' => $bulan_target,
			'bulan_sumber' => $bulan_sumber,
			'tanggal_beli_target' => $tanggal_beli_target,
			'tanggal_beli_sumber' => $tanggal_beli_sumber,
			'count_target' => $count_target,
			'count_sumber' => $count_sumber,
			'count_sumber_all' => $count_sumber_all,
			'count_sumber_skip_total10' => max(0, $count_sumber_all - $count_sumber),
			'sudah_ada_data' => $sudah_ada,
			'can_generate' => $can_generate,
			'can_recalc_only' => ($count_sumber_all === 0 && $can_recalc_only),
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
				'message' => $this->persediaan_restricted_access_message('Analisa generate'),
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

		$penjelasan = 'Semua ' . $total_sumber . ' record bulan sumber (total_10 &gt;= 1) akan di-<strong>INSERT/UPDATE</strong> ke bulan target. '
			. 'Baris dengan total_10 &lt; 1, kosong, atau "-" tidak disalin. Baris target sa=0 &amp; beli=0 &amp; total_10=0 dihapus. '
			. 'Disalin: <strong>uuid_barang, namabarang, satuan, hpp</strong>; '
			. '<strong>sa</strong> dan <strong>total_10</strong> = saldo akhir bulan sumber (nilai field total_10). '
			. '<strong>beli</strong> dan <strong>penjualan</strong> = 0 (diisi lewat proses pembelian/penjualan).';
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
		$this->load->helper(array('persediaan_display', 'pembelian_persediaan'));
		try {
			$parsed = $this->parse_bulan_rekap_input();
			if (!$parsed['ok']) {
				persediaan_ajax_json_output($this, $parsed);
				return;
			}

			$bulan = $parsed['bulan'];
			$hasil_rekap = persediaan_rekap_run_silent_db($this, function () use ($bulan) {
				return $this->get_persediaan_rekap_rows($bulan);
			});

			persediaan_ajax_json_output($this, array(
				'ok' => true,
				'bulan' => $bulan,
				'tanggal_rekap' => $this->get_tanggal_rekap_dari_bulan($bulan),
				'items' => $hasil_rekap['items'],
				'total_detail' => $hasil_rekap['total_detail'],
				'total_detail_tampil' => $hasil_rekap['total_detail_tampil'],
			));
		} catch (Exception $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Gagal memuat rekap: ' . $e->getMessage()));
		} catch (Throwable $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Gagal memuat rekap: ' . $e->getMessage()));
		}
	}

	/**
	 * AJAX: satu langkah rekalkulasi rekap (step 1–21) untuk progress bar.
	 */
	public function ajax_rekap_sync_step()
	{
		$this->load->helper(array('persediaan_display', 'pembelian_persediaan'));
		$step_post = (int) $this->input->post('step', TRUE);
		try {
			$parsed = $this->parse_bulan_rekap_input();
			if (!$parsed['ok']) {
				persediaan_ajax_json_output($this, $parsed);
				return;
			}

			$total_steps = $this->get_rekap_total_steps();
			if ($step_post < 1 || $step_post > $total_steps) {
				persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Langkah rekalkulasi tidak valid.'));
				return;
			}

			$hasil = persediaan_rekap_run_silent_db($this, function () use ($parsed, $step_post) {
				return $this->sync_persediaan_rekap_step($parsed['bulan'], $step_post);
			});
			persediaan_ajax_json_output($this, $hasil);
		} catch (Exception $e) {
			persediaan_ajax_json_output($this, array(
				'ok' => false,
				'message' => 'Rekalkulasi rekap gagal: ' . $e->getMessage(),
				'step' => $step_post,
			));
		} catch (Throwable $e) {
			persediaan_ajax_json_output($this, array(
				'ok' => false,
				'message' => 'Rekalkulasi rekap gagal: ' . $e->getMessage(),
				'step' => $step_post,
			));
		}
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
		if (!$this->db->table_exists('persediaan_rekap_view')) {
			throw new Exception('Tabel persediaan_rekap_view tidak ditemukan di database.');
		}

		$tanggal_rekap = $this->get_tanggal_rekap_dari_bulan($bulan);
		$urutan = $this->get_urutan_nama_rekap();
		$order_sql = implode(',', array_map(function ($n) {
			return $this->db->escape($n);
		}, $urutan));

		$list = persediaan_rekap_db_query(
			$this,
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
		$db_col = persediaan_resolve_db_field_name($this, $nama_kolom);
		if (!$this->db->field_exists($db_col, 'persediaan')) {
			return 0;
		}

		$rows = persediaan_rekap_db_query(
			$this,
			"SELECT `" . $db_col . "` AS val FROM `persediaan` WHERE `tanggal_beli`=?",
			array($tanggal_beli)
		)->result();

		$total = 0;
		foreach ($rows as $r) {
			$total += persediaan_parse_angka($r->val);
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
		$db_col = persediaan_resolve_db_field_name($this, $nama_kolom);
		if (!$this->db->field_exists($db_col, 'persediaan')) {
			return 0;
		}

		$rows = persediaan_rekap_db_query(
			$this,
			"SELECT `" . $db_col . "` AS val, `hpp` FROM `persediaan` WHERE `tanggal_beli`=?",
			array($tanggal_beli)
		)->result();

		$total = 0;
		foreach ($rows as $r) {
			$total += persediaan_parse_angka($r->val) * persediaan_parse_angka($r->hpp);
		}
		return $total;
	}

	/**
	 * Total nilai_persediaan bulan terpilih (Σ nilai_persediaan × hpp per record).
	 */
	private function sum_persediaan_nilai_kali_hpp($tanggal_beli)
	{
		$this->load->helper('persediaan_display');
		if (!$this->db->field_exists('nilai_persediaan', 'persediaan')) {
			return 0;
		}
		$rows = persediaan_rekap_db_query(
			$this,
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
		if (!$this->db->field_exists('nilai_persediaan', 'persediaan')) {
			return 0;
		}
		$rows = persediaan_rekap_db_query(
			$this,
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
		$this->load->helper('persediaan_display');

		if (!$this->db->table_exists('persediaan_rekap_view')) {
			throw new Exception('Tabel persediaan_rekap_view tidak ditemukan di database server.');
		}

		$view_fields = persediaan_rekap_view_list_fields($this);

		$existing = persediaan_rekap_db_query(
			$this,
			"SELECT `id` FROM `persediaan_rekap_view` WHERE `tanggal_rekap`=? AND `nama_rekap`=? LIMIT 1",
			array($tanggal_rekap, $nama_rekap)
		)->row();

		$nominal_tampil = $this->format_angka_persediaan($nominal);

		if ($existing) {
			$upd = array('nominal' => $nominal_tampil);
			if (in_array('keterangan', $view_fields, true)) {
				$upd['keterangan'] = $keterangan;
			}
			$this->db->where('id', (int) $existing->id);
			if (!$this->db->update('persediaan_rekap_view', $upd)) {
				throw new Exception(persediaan_rekap_db_error_message($this, 'Update baris rekap "' . $nama_rekap . '" gagal'));
			}
			return 'update';
		}

		$data_insert = array(
			'tanggal_rekap' => $tanggal_rekap,
			'nama_rekap' => $nama_rekap,
			'nominal' => $nominal_tampil,
		);
		if (in_array('keterangan', $view_fields, true)) {
			$data_insert['keterangan'] = $keterangan;
		}

		if (!persediaan_rekap_view_uses_auto_increment_id($this) && in_array('id', $view_fields, true)) {
			$data_insert['id'] = $next_id++;
		}

		$uuid_col = persediaan_rekap_view_uuid_column($this);
		if ($uuid_col) {
			$this->db->set($uuid_col, "REPLACE(UUID(),'-','')", false);
		}

		if (!$this->db->insert('persediaan_rekap_view', $data_insert)) {
			throw new Exception(persediaan_rekap_db_error_message($this, 'Insert baris rekap "' . $nama_rekap . '" gagal'));
		}
		return 'insert';
	}

	private function get_next_id_persediaan_rekap_view()
	{
		$this->load->helper('persediaan_display');
		if (persediaan_rekap_view_uses_auto_increment_id($this)) {
			return 0;
		}
		$row_max = persediaan_rekap_db_query($this, "SELECT MAX(`id`) AS max_id FROM `persediaan_rekap_view`")->row();
		return $row_max && $row_max->max_id ? ((int) $row_max->max_id + 1) : 1;
	}

	private function get_nominal_rekap_baris($tanggal_rekap, $nama_rekap)
	{
		$this->load->helper('persediaan_display');
		$row = persediaan_rekap_db_query(
			$this,
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
			$db_kolom = persediaan_resolve_db_field_name($this, $cfg['kolom']);
			$keterangan = 'Rekalkulasi: sum(' . $db_kolom . ') bulan ' . $tanggal_beli_bulan_ini;
			$info_proses = 'Menghitung sum(' . $db_kolom . ')';
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

	public function ajax_analisa_recalculate_persediaan()
	{
		$this->load->helper(array('pembelian_persediaan', 'persediaan_display'));

		try {
			$bulan = trim((string) $this->input->get_post('bulan', TRUE));
			if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
				persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).'));
				return;
			}

			$analisa = persediaan_recalculate_full_analisa($this, $bulan);
			persediaan_ajax_json_output($this, $analisa);
		} catch (Exception $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Error: ' . $e->getMessage()));
		} catch (Throwable $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Error: ' . $e->getMessage()));
		}
	}

	/**
	 * AJAX batch recalculate pembelian (beli) + penjualan → persediaan (tab Recalculate).
	 */
	public function ajax_recalculate_persediaan_batch()
	{
		@set_time_limit(0);
		@ini_set('memory_limit', '512M');
		@ignore_user_abort(true);

		$this->load->helper(array('pembelian_persediaan', 'persediaan_display'));

		try {
			$bulan = trim((string) $this->input->get_post('bulan', TRUE));
			if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
				persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).'));
				return;
			}

			$offset = max(0, (int) $this->input->get_post('offset', TRUE));
			$limit = (int) $this->input->get_post('limit', TRUE);
			$start = ($this->input->get_post('start', TRUE) === '1');
			if ($limit < 1 || $limit > 100) {
				$limit = 40;
			}

			$db_debug = $this->db->db_debug;
			$this->db->db_debug = false;

			$result = persediaan_recalculate_full_batch($this, $bulan, $offset, $limit, $start);

			$this->db->db_debug = $db_debug;

			persediaan_ajax_json_output($this, $result);
		} catch (Exception $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Error: ' . $e->getMessage()));
		} catch (Throwable $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Error: ' . $e->getMessage()));
		}
	}

	/**
	 * AJAX batch: Generate dari bulan sebelumnya + recalculate beli dari pembelian (tab Generate).
	 */
	public function ajax_generate_recalculate_batch()
	{
		@set_time_limit(0);
		@ini_set('memory_limit', '512M');
		@ignore_user_abort(true);

		$this->load->helper(array('pembelian_persediaan', 'persediaan_display'));

		try {
			if (!$this->persediaan_user_can_generate()) {
				persediaan_ajax_json_output($this, array(
					'ok' => false,
					'message' => $this->persediaan_restricted_access_message('Generate &amp; Recalculate'),
				));
				return;
			}

			$bulan = trim((string) $this->input->get_post('bulan', TRUE));
			if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
				persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).'));
				return;
			}

			$offset = max(0, (int) $this->input->get_post('offset', TRUE));
			$limit = (int) $this->input->get_post('limit', TRUE);
			$start = ($this->input->get_post('start', TRUE) === '1');
			if ($limit < 1 || $limit > 50) {
				$limit = 30;
			}

			$db_debug = $this->db->db_debug;
			$this->db->db_debug = false;

			$result = persediaan_generate_recalculate_batch($this, $bulan, $offset, $limit, $start);

			$this->db->db_debug = $db_debug;

			persediaan_ajax_json_output($this, $result);
		} catch (Exception $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Error: ' . $e->getMessage()));
		} catch (Throwable $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Error: ' . $e->getMessage()));
		}
	}

	/**
	 * AJAX: muat history Generate & Recalculate terakhir untuk bulan target (tab Generate).
	 */
	public function ajax_load_gen_recalc_history()
	{
		$this->load->helper(array('pembelian_persediaan', 'persediaan_display'));

		try {
			if (!$this->persediaan_user_can_generate()) {
				persediaan_ajax_json_output($this, array(
					'ok' => false,
					'message' => $this->persediaan_restricted_access_message('History generate'),
				));
				return;
			}

			$bulan = trim((string) $this->input->get_post('bulan', TRUE));
			if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
				persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).'));
				return;
			}

			$result = persediaan_gen_recalc_history_load($this, $bulan);
			persediaan_ajax_json_output($this, $result);
		} catch (Exception $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Error: ' . $e->getMessage()));
		} catch (Throwable $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Error: ' . $e->getMessage()));
		}
	}

	/**
	 * Export Excel hasil Generate & Recalculate (per jenis tabel atau semua sheet).
	 * POST: bulan (YYYY-MM), jenis (opsional: persediaan_all|generate_update|...| kosong = semua)
	 */
	public function excel_gen_recalc()
	{
		$this->load->helper(array('exportexcel', 'pembelian_persediaan', 'persediaan_display'));

		if (!$this->persediaan_user_can_generate()) {
			show_error(strip_tags($this->persediaan_restricted_access_message('Export Excel generate')), 403);
			return;
		}

		$bulan = trim((string) $this->input->post('bulan', TRUE));
		if ($bulan === '') {
			$bulan = trim((string) $this->input->post('bulan_persediaan', TRUE));
		}
		if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
			show_error('Format bulan tidak valid (YYYY-MM).', 400);
			return;
		}

		$jenis = trim((string) $this->input->post('jenis', TRUE));
		$allowed = array_keys(persediaan_gen_recalc_jenis_definitions());
		if ($jenis !== '' && !in_array($jenis, $allowed, true)) {
			show_error('Jenis export tidak valid.', 400);
			return;
		}

		$suffix = ($jenis !== '') ? '_' . $jenis : '_Semua';
		$namaFile = 'Generate_Recalculate_' . $bulan . $suffix . '_' . date('Y-m-d_H-i-s') . '.xlsx';
		excel_prepare_download($namaFile);
		persediaan_gen_recalc_export_excel_output($this, $bulan, $jenis !== '' ? $jenis : null);
		exit();
	}

	/**
	 * Export Excel rekonsiliasi transaksi (persediaan + pembelian + penjualan + produksi + pecah satuan).
	 */
	public function excel_rekonsiliasi_transaksi()
	{
		$this->load->helper(array('exportexcel', 'pembelian_persediaan', 'persediaan_display'));

		if (!$this->persediaan_user_can_generate()) {
			show_error(strip_tags($this->persediaan_restricted_access_message('Export Excel rekonsiliasi transaksi')), 403);
			return;
		}

		$bulan = trim((string) $this->input->post('bulan', TRUE));
		if ($bulan === '') {
			$bulan = trim((string) $this->input->post('bulan_persediaan', TRUE));
		}
		if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
			show_error('Format bulan tidak valid (YYYY-MM).', 400);
			return;
		}

		$namaFile = 'Rekonsiliasi_Transaksi_' . $bulan . '_' . date('Y-m-d_H-i-s') . '.xlsx';
		excel_prepare_download($namaFile);
		persediaan_export_rekonsiliasi_transaksi_excel_output($this, $bulan);
		exit();
	}

	/**
	 * Export Excel multi-sheet untuk tab Recalculate (persediaan, pembelian, pembelian jasa, penjualan).
	 */
	public function excel_recalculate()
	{
		$bulan = trim((string) $this->input->post('bulan', TRUE));
		if ($bulan === '') {
			$bulan = trim((string) $this->input->post('bulan_persediaan', TRUE));
		}
		if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
			show_error('Format bulan tidak valid (YYYY-MM).', 400);
			return;
		}

		$this->load->helper(array('exportexcel', 'persediaan_display', 'pembelian_persediaan'));

		$namaFile = 'Recalculate_Persediaan_' . $bulan . '_' . date('Y-m-d_H-i-s') . '.xlsx';
		excel_prepare_download($namaFile);
		persediaan_export_recalculate_excel_output($this, $bulan);
		exit();
	}

	/**
	 * AJAX: import file CSV manual → tabel database baru (tab Compare Data Manual — Online).
	 */
	public function ajax_compare_import_csv()
	{
		@set_time_limit(0);
		@ini_set('memory_limit', '512M');
		$this->load->helper('pembelian_persediaan');

		if (!$this->persediaan_user_can_compare()) {
			persediaan_ajax_json_output($this, array(
				'ok' => false,
				'message' => strip_tags($this->persediaan_restricted_access_message('Import CSV compare')),
			));
			return;
		}

		if (empty($_FILES['csv_file']) || !is_uploaded_file($_FILES['csv_file']['tmp_name'])) {
			persediaan_ajax_json_output($this, array(
				'ok' => false,
				'message' => 'Pilih file CSV terlebih dahulu.',
			));
			return;
		}

		$original_name = trim((string) $_FILES['csv_file']['name']);
		$ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
		if ($ext !== 'csv') {
			persediaan_ajax_json_output($this, array(
				'ok' => false,
				'message' => 'File harus berformat .csv',
			));
			return;
		}

		$bulan = $this->_compare_tabel_bulan_from_post();
		$result = persediaan_compare_import_csv_to_db(
			$this,
			$_FILES['csv_file']['tmp_name'],
			$original_name,
			$bulan
		);

		persediaan_ajax_json_output($this, $result);
	}

	/**
	 * AJAX: preview data tabel hasil import CSV (tab Compare Data Manual — Online).
	 */
	public function ajax_compare_tabel_preview()
	{
		@set_time_limit(0);
		@ini_set('memory_limit', '512M');
		$this->load->helper('pembelian_persediaan');

		if (!$this->persediaan_user_can_compare()) {
			persediaan_ajax_json_output($this, array(
				'ok' => false,
				'message' => strip_tags($this->persediaan_restricted_access_message('Preview tabel compare')),
			));
			return;
		}

		$table = trim((string) $this->input->post('tabel', TRUE));
		if ($table === '') {
			persediaan_ajax_json_output($this, array(
				'ok' => false,
				'message' => 'Nama tabel belum dipilih.',
			));
			return;
		}

		$limit = (int) $this->input->post('limit', TRUE);
		persediaan_ajax_json_output($this, persediaan_compare_preview_table_data($this, $table, $limit));
	}

	/**
	 * AJAX: daftar semua tabel database untuk Compare Tabel.
	 */
	public function ajax_compare_tabel_list()
	{
		$this->load->helper('pembelian_persediaan');

		if (!$this->persediaan_user_can_compare()) {
			persediaan_ajax_json_output($this, array(
				'ok' => false,
				'message' => strip_tags($this->persediaan_restricted_access_message('Compare')),
			));
			return;
		}

		persediaan_ajax_json_output($this, array(
			'ok' => true,
			'tables' => persediaan_compare_list_db_tables($this),
		));
	}

	/**
	 * AJAX: bandingkan persediaan bulan terpilih vs tabel manual.
	 */
	public function ajax_compare_tabel_run()
	{
		$this->load->helper('pembelian_persediaan');

		if (!$this->persediaan_user_can_compare()) {
			persediaan_ajax_json_output($this, array(
				'ok' => false,
				'message' => strip_tags($this->persediaan_restricted_access_message('Compare')),
			));
			return;
		}

		$bulan = trim((string) $this->input->post('bulan', TRUE));
		if ($bulan === '') {
			$bulan = $this->_compare_tabel_bulan_from_post();
		}
		$table = trim((string) $this->input->post('tabel', TRUE));

		if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
			persediaan_ajax_json_output($this, array(
				'ok' => false,
				'message' => 'Pilih bulan dan tahun yang valid.',
			));
			return;
		}

		if ($table === '') {
			persediaan_ajax_json_output($this, array(
				'ok' => false,
				'message' => 'Pilih tabel yang akan dibandingkan.',
			));
			return;
		}

		$tables = persediaan_compare_list_db_tables($this);
		if (!in_array($table, $tables, true)) {
			persediaan_ajax_json_output($this, array(
				'ok' => false,
				'message' => 'Tabel tidak valid atau tidak ditemukan.',
			));
			return;
		}

		persediaan_ajax_json_output($this, persediaan_compare_run($this, $bulan, $table));
	}

	/**
	 * Export Excel hasil compare persediaan vs tabel manual.
	 */
	public function excel_compare_tabel()
	{
		$this->load->helper(array('exportexcel', 'pembelian_persediaan', 'persediaan_display'));

		if (!$this->persediaan_user_can_compare()) {
			show_error(strip_tags($this->persediaan_restricted_access_message('Export Excel compare')), 403);
			return;
		}

		$bulan = trim((string) $this->input->post('bulan', TRUE));
		if ($bulan === '') {
			$bulan = $this->_compare_tabel_bulan_from_post();
		}
		$table = trim((string) $this->input->post('tabel', TRUE));

		if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
			show_error('Format bulan tidak valid (YYYY-MM).', 400);
			return;
		}
		if ($table === '' || !persediaan_compare_is_valid_table_name($table)) {
			show_error('Tabel tidak valid.', 400);
			return;
		}

		$tables = persediaan_compare_list_db_tables($this);
		if (!in_array($table, $tables, true)) {
			show_error('Tabel tidak ditemukan.', 404);
			return;
		}

		$jenis = trim((string) $this->input->post('jenis', TRUE));
		$allowed = array_keys(persediaan_compare_jenis_definitions());
		if ($jenis === '' || !in_array($jenis, $allowed, true)) {
			show_error('Jenis export compare tidak valid.', 400);
			return;
		}

		$defs = persediaan_compare_jenis_definitions();
		$suffix = isset($defs[$jenis]['file_suffix']) ? $defs[$jenis]['file_suffix'] : $jenis;
		$namaFile = 'Compare_' . $suffix . '_' . $bulan . '_vs_' . $table . '_' . date('Y-m-d_H-i-s') . '.xlsx';
		excel_prepare_download($namaFile);
		persediaan_compare_export_excel_output($this, $bulan, $table, $jenis);
		exit();
	}

	/**
	 * Export Excel ALL — manual + persediaan + pembelian + penjualan + produksi + pecah satuan (tab Compare).
	 */
	public function excel_compare_tabel_all()
	{
		@set_time_limit(600);
		@ini_set('memory_limit', '512M');

		$this->load->helper(array('exportexcel', 'pembelian_persediaan', 'persediaan_display'));

		if (!$this->persediaan_user_can_compare()) {
			show_error(strip_tags($this->persediaan_restricted_access_message('Export Excel ALL compare')), 403);
			return;
		}

		$bulan = trim((string) $this->input->post('bulan', TRUE));
		if ($bulan === '') {
			$bulan = $this->_compare_tabel_bulan_from_post();
		}
		$table = trim((string) $this->input->post('tabel', TRUE));

		if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
			show_error('Format bulan tidak valid (YYYY-MM).', 400);
			return;
		}
		if ($table === '' || !persediaan_compare_is_valid_table_name($table)) {
			show_error('Tabel tidak valid.', 400);
			return;
		}

		$tables = persediaan_compare_list_db_tables($this);
		if (!in_array($table, $tables, true)) {
			show_error('Tabel tidak ditemukan.', 404);
			return;
		}

		$namaFile = 'Compare_Excel_ALL_' . $bulan . '_vs_' . $table . '_' . date('Y-m-d_H-i-s') . '.xlsx';
		excel_prepare_download($namaFile);
		persediaan_compare_export_excel_all_output($this, $bulan, $table);
		exit();
	}

	private function _compare_tabel_bulan_from_post()
	{
		$bulan_num = (int) $this->input->post('bulan_num', TRUE);
		$tahun = (int) $this->input->post('tahun', TRUE);
		if ($bulan_num >= 1 && $bulan_num <= 12 && $tahun >= 2000) {
			return $tahun . '-' . str_pad((string) $bulan_num, 2, '0', STR_PAD_LEFT);
		}
		return '';
	}

	public function recalculate_data_persediaan($bulan = '')
	{
		@set_time_limit(0);
		@ini_set('memory_limit', '1024M');
		$this->load->helper(array('pembelian_persediaan', 'persediaan_display'));

		$bulan = trim((string) $bulan);
		if ($bulan === '') {
			$bulan = trim((string) $this->input->get_post('bulan', TRUE));
		}
		if ($bulan === '' && $this->input->post('bulan_persediaan')) {
			$bulan = trim((string) $this->input->post('bulan_persediaan', TRUE));
		}

		if ($this->input->get('ajax') === '1') {
			header('Content-Type: application/json; charset=UTF-8');
			if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
				echo json_encode(array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).'));
				return;
			}
			$offset = max(0, (int) $this->input->get('offset'));
			$limit = (int) $this->input->get('limit');
			if ($limit < 1 || $limit > 100) {
				$limit = 50;
			}
			echo json_encode(persediaan_recalculate_penjualan_batch($this, $bulan, $offset, $limit));
			return;
		}

		if (preg_match('/^\d{4}-\d{2}$/', $bulan)) {
			$ctx = persediaan_recalculate_penjualan_context($this, $bulan);
			if (!$ctx['ok']) {
				header('Content-Type: text/html; charset=UTF-8');
				echo '<!doctype html><html><head><meta charset="utf-8"><title>Recalculate Persediaan</title></head><body>';
				echo '<p style="color:red;">' . htmlspecialchars($ctx['message']) . '</p>';
				echo '<p><a href="' . site_url('Persediaan/recalculate_data_persediaan') . '">Kembali</a></p>';
				echo '</body></html>';
				return;
			}

			$this->session->unset_userdata('recalc_penj_reset_' . $bulan);
			$this->session->unset_userdata('recalc_penj_stats_' . $bulan);

			$data_view = array(
				'bulan' => $ctx['bulan'],
				'bulan_label' => $ctx['bulan_label'],
				'tanggal_beli' => $ctx['tanggal_beli'],
				'tgl_awal' => $ctx['tgl_awal'],
				'tgl_akhir' => $ctx['tgl_akhir'],
				'total_persediaan' => $ctx['total_persediaan'],
				'total_penjualan' => $ctx['total_penjualan'],
				'ajax_url' => site_url('Persediaan/recalculate_data_persediaan/' . $ctx['bulan']),
			);
			$this->load->view('anekadharma/persediaan/recalculate_persediaan_penjualan_process', $data_view);
			return;
		}

		$bulan_default = date('Y-m');
		if ($this->input->post('bulan_persediaan')) {
			$bulan_default = trim((string) $this->input->post('bulan_persediaan', TRUE));
		}

		header('Content-Type: text/html; charset=UTF-8');
		echo '<!doctype html><html><head><meta charset="utf-8"><title>Recalculate Penjualan → Persediaan</title>';
		echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"></head><body class="p-4">';
		echo '<div class="container" style="max-width:720px;">';
		echo '<h3>Recalculate Data Persediaan dari Penjualan</h3>';
		echo '<p>Membaca <strong>tbl_penjualan</strong> bulan terpilih, lalu mengisi ulang kolom <strong>penjualan</strong> '
			. 'dan kolom <strong>unit</strong> (sekret, cetak, medis, …) di tabel <strong>persediaan</strong>.</p>';
		echo '<ul class="small text-muted">';
		echo '<li>Cocokkan barang: <code>id_persediaan_barang</code> → <code>uuid_persediaan</code> → <code>uuid_barang</code> → nama+satuan+hpp</li>';
		echo '<li>Harus cocok <strong>satuan</strong> dan <strong>harga_satuan</strong> (penjualan) = <strong>hpp</strong> (persediaan)</li>';
		echo '<li>Unit penjualan (<code>uuid_unit</code>) → kolom unit persediaan via <code>sys_unit</code></li>';
		echo '<li>Semua kolom penjualan/unit bulan tersebut di-reset ke 0 dulu, lalu dihitung ulang</li>';
		echo '</ul>';
		echo '<form method="post" action="' . site_url('Persediaan/recalculate_data_persediaan') . '">';
		echo '<div class="form-group"><label>Bulan / Tahun</label>';
		echo '<input type="month" name="bulan_persediaan" class="form-control" style="max-width:220px;" value="' . htmlspecialchars($bulan_default) . '" required></div>';
		echo '<button type="submit" class="btn btn-primary">Mulai Recalculate</button> ';
		echo '<a href="' . site_url('persediaan') . '" class="btn btn-secondary">Kembali</a>';
		echo '</form></div></body></html>';
	}

	/**
	 * Generate data persediaan bulan baru dari salinan bulan sebelumnya.
	 * Contoh: GENERATE_PERSEDIAN_BULAN/2026-01 => copy dari tanggal_beli 2025-12-01 ke 2026-01-01
	 * Salin dari bulan sumber: uuid_barang, namabarang, satuan, hpp.
	 * Baru: uuid_persediaan, id (auto increment), tanggal_beli = tgl 1 bulan target.
	 * sa = total_10 (nilai field total_10 bulan sumber); total_10 = sa (sama saat generate).
	 * beli, penjualan, distribusi unit = 0 — diisi/ dihitung ulang di Recalculate (pembelian & penjualan).
	 * Field lain (gudang, spop, kode, dll.) = 0/kosong, tidak disalin dari bulan sumber.
	 * Satu record sumber = satu INSERT ke bulan target.
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
			echo '<p style="color:red;">' . htmlspecialchars(strip_tags($this->persediaan_restricted_access_message('Generate persediaan'))) . '</p>';
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
			try {
				$result = $this->generate_persediaan_bulan_batch($ctx, $offset, $limit);
				echo json_encode($result);
			} catch (Exception $e) {
				echo json_encode(array(
					'ok' => false,
					'message' => 'Generate gagal: ' . $e->getMessage(),
				));
			}
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

		$total_sumber_all = $this->persediaan_count_by_tanggal_beli($tanggal_beli_sumber);
		$total_sumber = $this->persediaan_count_sumber_layak_generate($tanggal_beli_sumber);
		$total_target = $this->persediaan_count_by_tanggal_beli($tanggal_beli_target);

		if ($total_sumber_all === 0) {
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
			'total_sumber_all' => $total_sumber_all,
			'total_target' => $total_target,
		);
	}

	private function generate_persediaan_bulan_batch($ctx, $offset, $limit)
	{
		if (!$this->persediaan_user_can_generate()) {
			return array('ok' => false, 'message' => strip_tags($this->persediaan_restricted_access_message('Generate persediaan')));
		}

		$total_sumber = $ctx['total_sumber'];
		$total_sumber_all = isset($ctx['total_sumber_all']) ? (int) $ctx['total_sumber_all'] : $total_sumber;
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

		$this->load->helper('pembelian_persediaan');
		$sql_batch = "SELECT * FROM `persediaan` WHERE `tanggal_beli`=?"
			. " ORDER BY `id` ASC LIMIT " . (int) $limit . " OFFSET " . (int) $offset;
		$list_batch = $this->db->query($sql_batch, array($tanggal_beli_sumber))->result();

		$next_id = (int) $state['next_id'];
		$batch_items = array();
		$batch_insert = 0;
		$batch_update = 0;
		$batch_skip = 0;

		foreach ($list_batch as $row) {
			$item = $this->proses_satu_record_generate_persediaan($row, $ctx, $next_id);
			if ($item['aksi'] === 'SKIP') {
				$batch_skip++;
				continue;
			}
			$batch_items[] = $item;

			if ($item['aksi'] === 'INSERT') {
				$batch_insert++;
			} elseif ($item['aksi'] === 'UPDATE') {
				$batch_update++;
			}
		}

		$offset_selesai = $offset + count($list_batch);
		$done = ($total_sumber_all === 0 || $offset_selesai >= $total_sumber_all);

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
	 * Saldo awal generate dari record sumber (total_10, fallback sa, fallback beli).
	 */
	private function generate_hitung_sa_dari_bulan_sumber($row)
	{
		$this->load->helper('pembelian_persediaan');
		return persediaan_generate_recalculate_hitung_sa_dari_sumber($row);
	}

	/**
	 * Update beli / total_10 / nilai_persediaan / tuj (SA tetap) pada record persediaan bulan target.
	 */
	private function generate_update_persediaan_beli($existing, $beli_angka, $keterangan_extra = '')
	{
		$beli_angka = max(0, (int) $beli_angka);
		$sa_angka = $this->parse_angka_persediaan($existing->sa);
		$hpp_angka = $this->parse_angka_persediaan($existing->hpp);
		$beli_lama = max(0, (int) floor($this->parse_angka_persediaan($existing->beli)));
		$beli_baru = $beli_lama + $beli_angka;
		$total_10_lama = max(0, (int) floor($this->parse_angka_persediaan($existing->total_10)));
		$total_10_baru = $total_10_lama + $beli_angka;
		$nilai_persediaan_baru = $total_10_baru * $hpp_angka;

		$sa_tampil = $this->format_angka_persediaan($sa_angka);
		$beli_tampil = $this->format_angka_persediaan($beli_baru);
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
			. ' | total_10=' . $total_10_tampil . ' (total_10+' . $beli_angka . ')'
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
		$this->load->helper('persediaan_display');

		$tanggal_beli_target = $ctx['tanggal_beli_target'];
		$tanggal_tampilan_target = $ctx['tanggal_tampilan_target'];

		$nama = trim((string) $row->namabarang);
		$satuan = trim((string) $row->satuan);
		$hpp = trim((string) $row->hpp);
		$uuid_barang = trim((string) $row->uuid_barang);

		// Saldo awal bulan baru dari record sumber; total_10 target = sa saat generate.
		$this->load->helper('pembelian_persediaan');
		if (!persediaan_generate_recalculate_sumber_layak_generate($row)) {
			return array(
				'aksi' => 'SKIP',
				'id' => isset($row->id) ? (int) $row->id : 0,
				'namabarang' => $nama,
				'satuan' => $satuan,
				'hpp' => $hpp,
				'total_10' => isset($row->total_10) ? $row->total_10 : '',
				'keterangan' => 'Lewati: total_10 < 1 / kosong / "-" di bulan sumber — tidak di-copy ke bulan target',
			);
		}

		$total_10_sumber = $this->generate_hitung_sa_dari_bulan_sumber($row);
		$sa_baru = $total_10_sumber;
		$beli_angka = 0;
		$total_10_baru = $sa_baru;
		$hpp_angka = $this->parse_angka_persediaan($hpp);
		$nilai_persediaan_baru = $total_10_baru * $hpp_angka;

		$sa_tampil = $this->format_angka_persediaan($sa_baru);
		$beli_tampil = $this->format_angka_persediaan($beli_angka);
		$total_10_tampil = $this->format_angka_persediaan($total_10_baru);
		$nilai_persediaan_tampil = $this->format_angka_persediaan($nilai_persediaan_baru);
		$tuj_tampil = '0';

		$id_baru = $next_id++;
		$data_insert = array(
			'id' => $id_baru,
			'uuid_persediaan_lama' => '',
			'uuid_spop' => '',
			'uuid_gudang' => '',
			'nama_gudang' => '',
			'uuid_barang' => $uuid_barang,
			'kode_barang' => '',
			'tanggal_beli' => $tanggal_beli_target,
			'tanggal' => $tanggal_tampilan_target,
			'kode' => '',
			'kategori' => '',
			'namabarang' => $nama,
			'satuan' => $satuan,
			'hpp' => $hpp,
			'sa' => $sa_tampil,
			'spop' => '0',
			'beli' => $beli_tampil,
			'tuj' => $tuj_tampil,
		);
		$data_insert = array_merge($data_insert, persediaan_generate_distribusi_nol_fields());
		$data_insert['total_10'] = $total_10_tampil;
		$data_insert['nilai_persediaan'] = $nilai_persediaan_tampil;
		$data_insert['penjualan'] = '0';
		$data_insert['pecah_satuan'] = '0';
		$data_insert['bahan_produksi'] = '0';

		$this->db->set('uuid_persediaan', "replace(uuid(),'-','')", FALSE);
		if (!$this->db->insert('persediaan', $data_insert)) {
			$db_err = $this->db->error();
			$pesan_db = isset($db_err['message']) ? trim((string) $db_err['message']) : 'Gagal insert persediaan.';
			throw new Exception($pesan_db);
		}

		$new_row = $this->db->query(
			"SELECT `id`,`uuid_persediaan` FROM `persediaan` WHERE `id`=? LIMIT 1",
			array($id_baru)
		)->row();

		$keterangan = 'uuid baru: ' . ($new_row ? $new_row->uuid_persediaan : '')
			. ' | salin: uuid_barang, namabarang, satuan, hpp'
			. ' | sa=' . $sa_tampil . ' & total_10=' . $total_10_tampil
			. ' (dari total_10 sumber ' . $this->format_angka_persediaan($total_10_sumber) . ')'
			. ' | beli=0, penjualan=0 (recalculate nanti)'
			. ' | nilai_persediaan=' . $nilai_persediaan_tampil
			. ' | field lain=0/kosong';

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

	/**
	 * Jumlah record bulan sumber yang layak di-generate (total_10 >= 1).
	 */
	private function persediaan_count_sumber_layak_generate($tanggal_beli_sumber)
	{
		$this->load->helper('pembelian_persediaan');
		$row_cnt = $this->db->query(
			"SELECT COUNT(*) AS jml FROM `persediaan` WHERE `tanggal_beli` = ?"
			. persediaan_generate_recalculate_sql_filter_total10_positif(),
			array($tanggal_beli_sumber)
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
		$this->load->helper(array('pembelian_persediaan', 'persediaan_display'));
		$bulan = trim((string) $bulan);
		$rows = array();

		if ($bulan === '') {
			$rows = $this->Persediaan_model->get_all();
		} else {
			$ts = strtotime($bulan . '-01');
			if ($ts === false) {
				$rows = $this->Persediaan_model->get_by_year_month($bulan);
			} else {
				$tanggal_beli = date('Y-m-01', $ts);
				$rows = $this->db->query(
					"SELECT * FROM `persediaan`
					WHERE (
						`tanggal_beli` = ?
						OR DATE_FORMAT(`tanggal_beli`, '%Y-%m') = ?
						OR (
							(`tanggal_beli` IS NULL OR `tanggal_beli` = '0000-00-00' OR TRIM(`tanggal_beli`) = '')
							AND (
								DATE_FORMAT(STR_TO_DATE(`tanggal`, '%Y-%m-%d %H:%i:%s'), '%Y-%m') = ?
								OR DATE_FORMAT(STR_TO_DATE(`tanggal`, '%Y-%m-%d'), '%Y-%m') = ?
							)
						)
					)
					ORDER BY `namabarang` ASC, `id` ASC",
					array($tanggal_beli, $bulan, $bulan, $bulan)
				)->result();

				if (count($rows) === 0) {
					$rows = $this->Persediaan_model->get_by_year_month($bulan);
				}
			}
		}

		$rows = persediaan_export_sort_rows_by_namabarang($rows, 'namabarang');

		return persediaan_filter_rows_tab_data($rows);
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
		$this->load->helper(array('exportexcel', 'persediaan_display', 'pembelian_persediaan'));
		$Persediaan = $this->get_persediaan_by_bulan($bulan);

		$bagian_bulan = ($bulan !== '') ? $bulan : 'semua';
		$waktu_klik = date('Y-m-d_H-i-s');
		$waktu_cetak_tampil = date('d/m/Y H:i:s');
		$namaFile = 'Persediaan_' . $bagian_bulan . '_' . $waktu_klik . '.xlsx';
		$tablehead = 1;
		$tablebody = 2;
		$nourut = 1;
		$total_total_10 = 0;
		$total_nilai_persediaan = 0;
		$totals_nominal_unit = array();
		foreach (persediaan_list_unit_columns($this) as $uf_excel) {
			$totals_nominal_unit[$uf_excel] = 0;
		}

		excel_prepare_download($namaFile);
		xlsBOF();

		$col_types = persediaan_export_column_types($this);

		xlsWriteLabelBold14(0, 0, 'di cetak pada : ' . $waktu_cetak_tampil);

		$kolomhead = 0;
		foreach (persediaan_export_headers($this) as $header) {
			xlsWriteLabel($tablehead, $kolomhead++, $header);
		}

		foreach ($Persediaan as $data) {
			$total_total_10 += persediaan_hitung_total_10_net($data);
			$total_nilai_persediaan += persediaan_hitung_nilai_persediaan_row($data);
			foreach (persediaan_list_unit_columns($this) as $uf_excel) {
				$totals_nominal_unit[$uf_excel] += persediaan_hitung_kolom_nominal_row($data, $uf_excel);
			}
			$cells = persediaan_export_row_cells($data, $nourut, $bulan, $this);
			$kolombody = 0;
			foreach ($cells as $cell) {
				persediaan_export_write_cell($tablebody, $kolombody++, $cell, $col_types);
			}
			$tablebody++;
			$nourut++;
		}

		$footer_cells = persediaan_export_footer_cells($total_total_10, $total_nilai_persediaan, $totals_nominal_unit, $this);
		$kolomfoot = 0;
		foreach ($footer_cells as $cell) {
			persediaan_export_write_cell($tablebody, $kolomfoot++, $cell, $col_types);
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

	// ---------- AJAX modul Pembelian (create) — sumber data tabel persediaan ----------

	public function pembelian_modal_form()
	{
		if (!$this->input->is_ajax_request()) {
			show_404();
			return;
		}

		$data = array(
			'button' => 'Simpan',
			'action' => site_url('persediaan/create_action_ajax'),
			'id' => set_value('id'),
			'kode_barang' => set_value('kode_barang'),
			'nama_barang' => set_value('nama_barang'),
			'satuan' => set_value('satuan'),
			'keterangan' => set_value('keterangan'),
			'kategori' => set_value('kategori'),
			'kategori_barang_options' => $this->_get_kategori_barang_options_pembelian(),
		);

		$this->load->view('anekadharma/sys_nama_barang/sys_nama_barang_form_pembelian_modal', $data);
	}

	public function list_barang_combobox_modal_ajax()
	{
		$this->load->helper('pembelian_persediaan');

		$rows = pembelian_get_barang_combobox_modal_rows($this);
		foreach ($rows as $row) {
			$row->label_barang = pembelian_format_barang_combobox_label(
				isset($row->nama_barang) ? $row->nama_barang : '',
				isset($row->satuan) ? $row->satuan : '',
				isset($row->harga_satuan) ? $row->harga_satuan : ''
			);
		}

		header('Content-Type: application/json');
		echo json_encode(array(
			'success' => true,
			'data' => $rows,
			'total' => count($rows),
		));
	}

	public function cek_nama_barang_persediaan_ajax()
	{
		if (!$this->input->is_ajax_request()) {
			show_404();
			return;
		}

		header('Content-Type: application/json');

		$nama_barang = trim((string) $this->input->get_post('nama_barang', TRUE));
		if ($nama_barang === '') {
			echo json_encode(array(
				'success' => false,
				'message' => 'Nama barang wajib diisi.'
			));
			return;
		}

		$this->load->helper('pembelian_persediaan');

		$tanggal_po = trim((string) $this->input->get_post('tanggal_po', TRUE));
		if ($tanggal_po !== '') {
			$tgl = pembelian_sync_filter_bulan_from_tanggal_po($this, $tanggal_po);
		} else {
			$tgl = pembelian_get_filter_tanggal($this);
		}

		$nama_norm = pembelian_normalize_nama_barang($nama_barang);
		$di_bulan = pembelian_find_barang_by_nama($this, $nama_norm, $tanggal_po !== '' ? $tanggal_po : null);
		$semua_bulan = pembelian_find_barang_referensi_persediaan($this, $nama_norm);

		$rows = array();
		$bulan_pilih = $tgl['bulan_label'];
		foreach ($semua_bulan as $row) {
			$rows[] = array(
				'id' => isset($row->id) ? (int) $row->id : 0,
				'uuid_barang' => isset($row->uuid_barang) ? $row->uuid_barang : '',
				'kode_barang' => isset($row->kode_barang) ? $row->kode_barang : '',
				'nama_barang' => isset($row->nama_barang) ? $row->nama_barang : '',
				'satuan' => isset($row->satuan) ? $row->satuan : '',
				'harga_satuan' => isset($row->harga_satuan) ? $row->harga_satuan : '',
				'tanggal_beli' => isset($row->tanggal_beli) ? $row->tanggal_beli : '',
				'bulan_label' => isset($row->bulan_label) ? $row->bulan_label : '',
			);
		}

		$rows_lain_bulan = array();
		foreach ($rows as $r) {
			if (!isset($r['bulan_label']) || $r['bulan_label'] !== $bulan_pilih) {
				$rows_lain_bulan[] = $r;
			}
		}

		$exists_in_month = ($di_bulan !== null);
		if (!$exists_in_month && count($rows) > 0 && count($rows_lain_bulan) === 0) {
			$exists_in_month = true;
		}

		$rows_tampil = $rows_lain_bulan;
		$show_referensi_modal = (!$exists_in_month && count($rows_tampil) > 0);

		echo json_encode(array(
			'success' => true,
			'exists_in_month' => $exists_in_month,
			'exists_in_system' => (count($rows) > 0),
			'show_referensi_modal' => $show_referensi_modal,
			'bulan_label' => $tgl['bulan_label'],
			'data_in_month' => $di_bulan ? array(
				'id' => isset($di_bulan->id) ? (int) $di_bulan->id : 0,
				'uuid_barang' => $di_bulan->uuid_barang,
				'kode_barang' => isset($di_bulan->kode_barang) ? $di_bulan->kode_barang : '',
				'nama_barang' => $di_bulan->nama_barang,
				'satuan' => $di_bulan->satuan,
				'harga_satuan' => $di_bulan->harga_satuan,
				'tanggal_beli' => isset($di_bulan->tanggal_beli) ? $di_bulan->tanggal_beli : '',
			) : null,
			'data' => $rows_tampil,
			'total_referensi' => count($rows_tampil),
			'message' => ($exists_in_month)
				? 'Nama barang sudah ada di persediaan bulan ' . $tgl['bulan_label'] . '.'
				: (($show_referensi_modal)
					? 'Nama barang sudah ada di sistem (bulan lain). Pilih dan gunakan salah satu referensi di daftar, atau lanjutkan isian manual.'
					: 'Nama barang belum ada di sistem (bulan ' . $tgl['bulan_label'] . ').'),
		));
	}

	public function create_action_ajax()
	{
		if (!$this->input->is_ajax_request()) {
			show_404();
			return;
		}

		header('Content-Type: application/json');

		$this->load->helper('pembelian_persediaan');

		$tanggal_po = trim((string) $this->input->post('tanggal_po', TRUE));
		if ($tanggal_po === '') {
			echo json_encode(array(
				'success' => false,
				'message' => 'Silakan pilih Tgl PO di halaman pembelian terlebih dahulu (datepicker tanggal PO).'
			));
			return;
		}

		if (pembelian_parse_tanggal_po($tanggal_po) === false) {
			echo json_encode(array(
				'success' => false,
				'message' => 'Format Tgl PO tidak valid. Silakan pilih tanggal dari datepicker.'
			));
			return;
		}

		$tgl = pembelian_sync_filter_bulan_from_tanggal_po($this, $tanggal_po);

		$nama_barang = pembelian_normalize_nama_barang($this->input->post('nama_barang', TRUE));
		$kategori = trim((string) $this->input->post('kategori', TRUE));
		$kode_barang = pembelian_kode_barang_opsional($nama_barang, $this->input->post('kode_barang', TRUE));

		if ($nama_barang === '') {
			echo json_encode(array(
				'success' => false,
				'message' => 'Nama barang wajib diisi.'
			));
			return;
		}

		$uuid_barang_baru = str_replace('-', '', $this->db->query("SELECT REPLACE(UUID(),'-','') AS u")->row()->u);
		$hpp_raw = trim((string) $this->input->post('harga_satuan', TRUE));
		$hpp_baru = preg_replace('/[^0-9]/', '', str_replace('.', '', $hpp_raw));
		if ($hpp_baru === '') {
			$hpp_baru = '0';
		}

		$data_persediaan = array(
			'tanggal' => date('Y-m-d H:i:s'),
			'tanggal_beli' => $tgl['awal_bulan'],
			'uuid_barang' => $uuid_barang_baru,
			'kode' => $kode_barang,
			'namabarang' => $nama_barang,
			'satuan' => $this->input->post('satuan', TRUE),
			'hpp' => $hpp_baru !== '' ? $hpp_baru : 0,
			'sa' => 0,
			'beli' => 0,
			'total_10' => 0,
			'nilai_persediaan' => 0,
		);
		if ($kategori !== '' && $this->db->field_exists('kategori', 'persediaan')) {
			$data_persediaan['kategori'] = $kategori;
		}

		$id_persediaan = $this->Persediaan_model->insert_produk_baru($data_persediaan);
		$row_persediaan = $this->Persediaan_model->get_by_id($id_persediaan);

		$row = (object) array(
			'uuid_barang' => $row_persediaan && !empty($row_persediaan->uuid_barang) ? $row_persediaan->uuid_barang : ($row_persediaan ? $row_persediaan->uuid_persediaan : $uuid_barang_baru),
			'kode_barang' => $kode_barang,
			'nama_barang' => $nama_barang,
			'satuan' => $this->input->post('satuan', TRUE),
			'kategori' => $kategori,
			'harga_satuan' => $hpp_baru,
		);

		echo json_encode(array(
			'success' => true,
			'message' => 'Barang berhasil ditambahkan ke persediaan bulan ' . $tgl['bulan_label']
				. ' (tanggal beli: ' . $tgl['awal_bulan'] . ').',
			'bulan_label' => $tgl['bulan_label'],
			'tanggal_beli' => $tgl['awal_bulan'],
			'data' => $row
		));
	}

	public function add_kategori_ajax()
	{
		if (!$this->input->is_ajax_request()) {
			show_404();
			return;
		}

		header('Content-Type: application/json');

		$kategori = trim((string) $this->input->post('kategori', TRUE));
		if ($kategori === '') {
			echo json_encode(array('success' => false, 'message' => 'Kategori wajib diisi.'));
			return;
		}

		$existing = $this->_find_kategori_existing_pembelian($kategori);
		if ($existing) {
			$kategoriTersimpan = isset($existing->kategori) ? trim($existing->kategori) : $kategori;
			echo json_encode(array(
				'success' => false,
				'exists' => true,
				'duplicate' => true,
				'message' => 'Kategori sudah ada di sistem, silahkan digunakan.',
				'data' => array('kategori' => $kategoriTersimpan)
			));
			return;
		}

		if ($this->db->table_exists('sys_kategori_barang')) {
			$this->db->set('uuid_kategori', "replace(uuid(),'-','')", FALSE);
			$this->db->set('kategori', $kategori);
			$this->db->insert('sys_kategori_barang');
			$id = $this->db->insert_id();
			$newRow = $this->db->select('id, uuid_kategori, kategori')->where('id', $id)->get('sys_kategori_barang')->row();
			echo json_encode(array(
				'success' => true,
				'message' => 'Kategori berhasil disimpan dan siap digunakan.',
				'data' => array(
					'kategori' => $newRow ? $newRow->kategori : $kategori,
					'uuid_kategori' => $newRow && isset($newRow->uuid_kategori) ? $newRow->uuid_kategori : null
				)
			));
			return;
		}

		if ($this->db->field_exists('kategori', 'persediaan')) {
			echo json_encode(array(
				'success' => true,
				'message' => 'Kategori siap digunakan. Simpan data barang untuk menyimpan ke persediaan.',
				'data' => array('kategori' => $kategori)
			));
			return;
		}

		echo json_encode(array('success' => false, 'message' => 'Tabel kategori tidak tersedia di sistem.'));
	}

	private function _get_kategori_barang_options_pembelian()
	{
		if ($this->db->table_exists('sys_kategori_barang')) {
			return $this->db->select('id, uuid_kategori, kategori')
				->from('sys_kategori_barang')
				->where('TRIM(kategori) <>', '')
				->order_by('kategori', 'ASC')
				->get()
				->result();
		}
		if ($this->db->field_exists('kategori', 'persediaan')) {
			return $this->db->query(
				"SELECT `kategori` FROM `persediaan` WHERE `kategori` IS NOT NULL AND TRIM(`kategori`) <> '' GROUP BY `kategori` ORDER BY `kategori` ASC"
			)->result();
		}

		return array();
	}

	private function _find_kategori_existing_pembelian($kategori)
	{
		$kategoriKey = strtolower(trim($kategori));
		if ($kategoriKey === '') {
			return null;
		}

		if ($this->db->table_exists('sys_kategori_barang')) {
			$row = $this->db->query(
				"SELECT `id`, `uuid_kategori`, `kategori` FROM `sys_kategori_barang` WHERE TRIM(`kategori`) <> '' AND LOWER(TRIM(`kategori`)) = ? LIMIT 1",
				array($kategoriKey)
			)->row();
			if ($row) {
				return $row;
			}
		}

		if ($this->db->field_exists('kategori', 'persediaan')) {
			$row = $this->db->query(
				"SELECT `kategori` FROM `persediaan` WHERE TRIM(`kategori`) <> '' AND LOWER(TRIM(`kategori`)) = ? LIMIT 1",
				array($kategoriKey)
			)->row();
			if ($row) {
				return $row;
			}
		}

		return null;
	}
}

/* End of file Persediaan.php */
/* Location: ./application/controllers/Persediaan.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-10-23 04:04:45 */
/* http://harviacode.com */