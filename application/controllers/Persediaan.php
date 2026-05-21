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

		return array(
			'Persediaan_data' => $Persediaan,
			'action_cari' => site_url('persediaan/search'),
			'bulan_persediaan_selected' => $bulan,
			'url_rekap_ajax' => site_url('Persediaan/ajax_rekap_bulan'),
			'url_rekap_sync_step' => site_url('Persediaan/ajax_rekap_sync_step'),
			'url_rekap_excel' => site_url('Persediaan/excel_rekap'),
			'rekap_total_steps' => $this->get_rekap_total_steps(),
		);
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
	 * Record yang sudah ada (tanggal_beli + namabarang + satuan + hpp) tidak diubah.
	 * Tampilan: SweetAlert animasi 5 record terakhir, lalu tabel lengkap di halaman.
	 * AJAX batch: ?ajax=1&offset=0&limit=25
	 */
	public function GENERATE_PERSEDIAN_BULAN($bulan_target = '')
	{
		@set_time_limit(0);
		@ini_set('memory_limit', '1024M');

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

		$row_cnt = $this->db->query(
			"SELECT COUNT(*) AS jml FROM `persediaan` WHERE `tanggal_beli`=?",
			array($tanggal_beli_sumber)
		)->row();
		$total_sumber = $row_cnt ? (int) $row_cnt->jml : 0;

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
			'total_sumber' => $total_sumber,
		);
	}

	private function generate_persediaan_bulan_batch($ctx, $offset, $limit)
	{
		$total_sumber = $ctx['total_sumber'];
		$tanggal_beli_target = $ctx['tanggal_beli_target'];
		$tanggal_beli_sumber = $ctx['tanggal_beli_sumber'];
		$tanggal_tampilan_target = $ctx['tanggal_tampilan_target'];

		$session_key = 'gen_pers_' . $ctx['bulan_target'];
		$state = $this->session->userdata($session_key);

		if ($offset === 0 || !is_array($state)) {
			$row_max = $this->db->query("SELECT MAX(`id`) AS max_id FROM `persediaan`")->row();
			$state = array(
				'next_id' => $row_max && $row_max->max_id ? ((int) $row_max->max_id + 1) : 1,
				'total_insert' => 0,
				'total_skip' => 0,
			);
		}

		$sql_batch = "SELECT * FROM `persediaan` WHERE `tanggal_beli`=? ORDER BY `id` ASC LIMIT " . (int) $limit . " OFFSET " . (int) $offset;
		$list_batch = $this->db->query($sql_batch, array($tanggal_beli_sumber))->result();

		$next_id = (int) $state['next_id'];
		$batch_items = array();
		$batch_insert = 0;
		$batch_skip = 0;

		foreach ($list_batch as $row) {
			$item = $this->proses_satu_record_generate_persediaan(
				$row,
				$tanggal_beli_target,
				$tanggal_tampilan_target,
				$next_id
			);
			$batch_items[] = $item;

			if ($item['aksi'] === 'INSERT') {
				$batch_insert++;
			} else {
				$batch_skip++;
			}
		}

		$offset_selesai = $offset + count($list_batch);
		$done = ($offset_selesai >= $total_sumber);

		$state['next_id'] = $next_id;
		$state['total_insert'] += $batch_insert;
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
				'total_skip' => (int) $state['total_skip'],
			);
			$this->session->unset_userdata($session_key);
		} else {
			$this->session->set_userdata($session_key, $state);
		}

		return array(
			'ok' => true,
			'done' => $done,
			'offset_selesai' => $offset_selesai,
			'total_sumber' => $total_sumber,
			'batch_insert' => $batch_insert,
			'batch_skip' => $batch_skip,
			'items' => $batch_items,
			'last_five' => $last_five,
			'summary' => $summary,
		);
	}

	private function proses_satu_record_generate_persediaan($row, $tanggal_beli_target, $tanggal_tampilan_target, &$next_id)
	{
		$nama = trim((string) $row->namabarang);
		$satuan = trim((string) $row->satuan);
		$hpp = trim((string) $row->hpp);

		$sql_cek = "SELECT `id`,`uuid_persediaan` FROM `persediaan`
			WHERE `tanggal_beli`=?
			AND TRIM(`namabarang`)=? AND TRIM(`satuan`)=?
			AND CAST(REPLACE(TRIM(`hpp`),',','') AS DECIMAL(18,2))=CAST(REPLACE(?,',','') AS DECIMAL(18,2))
			LIMIT 1";
		$existing = $this->db->query($sql_cek, array($tanggal_beli_target, $nama, $satuan, $hpp))->row();

		if ($existing) {
			return array(
				'aksi' => 'SKIP',
				'id' => $existing->id,
				'uuid_persediaan' => $existing->uuid_persediaan,
				'namabarang' => $nama,
				'satuan' => $satuan,
				'hpp' => $hpp,
				'sa' => '',
				'beli' => '',
				'tuj' => '',
				'keterangan' => 'Record sudah ada (tanggal_beli + namabarang + satuan + hpp sama) — tidak diubah',
			);
		}

		$total_10_sumber = $this->parse_angka_persediaan($row->total_10);
		$penjualan_sumber = $this->parse_angka_persediaan($row->penjualan);
		$pecah_satuan_sumber = $this->parse_angka_persediaan($row->pecah_satuan);
		$bahan_produksi_sumber = $this->parse_angka_persediaan($row->bahan_produksi);
		// sa = total_10 - penjualan - pecah_satuan - bahan_produksi (bulan sumber)
		$sa_baru = $total_10_sumber - $penjualan_sumber - $pecah_satuan_sumber - $bahan_produksi_sumber;

		// beli bulan generate tidak dari bulan sumber; record baru beli = 0
		$beli_tampil = '0';
		$beli_angka = 0;

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
			. ' | beli=' . $beli_tampil
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