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
		$this->load->helper('pembelian_persediaan');
	}

	// CEK UUID-PERSEIDAAN SAMA ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

	// PERSEDIAAN + PEMBELIAN ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	// SELECT p.id AS id_persediaan, p.uuid_persediaan, p.uuid_spop, p.tanggal_beli, p.namabarang, p.satuan AS satuan_persediaan, p.spop AS spop_persediaan, b.id AS id_pembelian, b.uuid_pembelian, b.tgl_po, b.uraian, b.satuan AS satuan_pembelian, b.harga_satuan FROM `persediaan` p INNER JOIN `tbl_pembelian` b ON p.uuid_persediaan = b.uuid_persediaan WHERE p.tanggal_beli LIKE '2026-01%' AND b.tgl_po LIKE '2026-01%';


	// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


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
		$this->load->helper(array('persediaan_display', 'pembelian_persediaan'));
		persediaan_history_generate_ensure_tables($this);
		generate_hasil_datatable_ensure_tables($this);

		$ts_gen_default = strtotime('+1 month', strtotime(date('Y-m-01')));
		if ($ts_gen_default === false) {
			$ts_gen_default = time();
		}

		return array(
			'Persediaan_data' => $Persediaan,
			'action_cari' => site_url('persediaan/search'),
			'bulan_persediaan_selected' => $bulan,
			'url_rekap_ajax' => site_url('Persediaan/ajax_rekap_bulan'),
			'url_rekap_ajax_serverside' => site_url('Persediaan/ajax_rekap_bulan_serverside'),
			'url_rekap_sync_step' => site_url('Persediaan/ajax_rekap_sync_step'),
			'url_rekap_excel' => site_url('Persediaan/excel_rekap'),
			'url_rekap_detail_produksi' => site_url('Persediaan/ajax_rekap_detail_produksi'),
			'url_rekap_detail_produksi_serverside' => site_url('Persediaan/ajax_rekap_detail_produksi_serverside'),
			'url_rekap_detail_produksi_excel' => site_url('Persediaan/excel_rekap_detail_produksi'),
			'url_rekap_detail_pecah_satuan' => site_url('Persediaan/ajax_rekap_detail_pecah_satuan'),
			'url_rekap_detail_pecah_satuan_serverside' => site_url('Persediaan/ajax_rekap_detail_pecah_satuan_serverside'),
			'url_rekap_detail_pecah_satuan_excel' => site_url('Persediaan/excel_rekap_detail_pecah_satuan'),
			'url_rekap_detail_pembelian_barang' => site_url('Persediaan/ajax_rekap_detail_pembelian_barang'),
			'url_rekap_detail_pembelian_barang_serverside' => site_url('Persediaan/ajax_rekap_detail_pembelian_barang_serverside'),
			'url_rekap_detail_pembelian_barang_excel' => site_url('Persediaan/excel_rekap_detail_pembelian_barang'),
			'url_rekap_detail_pembelian_jasa_serverside' => site_url('Persediaan/ajax_rekap_detail_pembelian_jasa_serverside'),
			'url_rekap_detail_pembelian_jasa_excel' => site_url('Persediaan/excel_rekap_detail_pembelian_jasa'),
			'url_rekap_detail_pembelian_jasa' => site_url('Persediaan/ajax_rekap_detail_pembelian_jasa'),
			'url_rekap_detail_penjualan_barang_serverside' => site_url('Persediaan/ajax_rekap_detail_penjualan_barang_serverside'),
			'url_rekap_detail_penjualan_barang_excel' => site_url('Persediaan/excel_rekap_detail_penjualan_barang'),
			'url_rekap_detail_penjualan_barang' => site_url('Persediaan/ajax_rekap_detail_penjualan_barang'),
			'url_rekap_detail_penjualan_jasa_serverside' => site_url('Persediaan/ajax_rekap_detail_penjualan_jasa_serverside'),
			'url_rekap_detail_penjualan_jasa_excel' => site_url('Persediaan/excel_rekap_detail_penjualan_jasa'),
			'url_rekap_detail_penjualan_jasa' => site_url('Persediaan/ajax_rekap_detail_penjualan_jasa'),
			'url_tambah_persediaan' => site_url('Persediaan/ajax_tambah_persediaan'),
			'url_get_persediaan_jasa' => site_url('Persediaan/ajax_get_persediaan_jasa'),
			'url_update_persediaan_jasa' => site_url('Persediaan/ajax_update_persediaan_jasa'),
			'url_hapus_persediaan_jasa' => site_url('Persediaan/ajax_hapus_persediaan_jasa'),
			'url_cek_generate_persediaan' => site_url('Persediaan/ajax_cek_generate_persediaan_bulan'),
			'url_analisa_generate_persediaan' => site_url('Persediaan/ajax_analisa_generate_persediaan_bulan'),
			'url_generate_persediaan_base' => site_url('Persediaan/GENERATE_PERSEDIAN_BULAN'),
			'url_recalculate_persediaan' => site_url('Persediaan/recalculate_data_persediaan'),
			'url_analisa_recalculate_persediaan' => site_url('Persediaan/ajax_analisa_recalculate_persediaan'),
			'url_recalculate_persediaan_batch' => site_url('Persediaan/ajax_recalculate_persediaan_batch'),
			'url_generate_recalculate_batch' => site_url('Persediaan/ajax_generate_recalculate_batch'),
			'url_generate_copy_bulan_sebelumnya' => site_url('Persediaan/ajax_generate_copy_bulan_sebelumnya'),
			'url_generate_penjualan_referensi_list' => site_url('Persediaan/ajax_generate_penjualan_referensi_list'),
			'url_generate_penjualan_refered' => site_url('Persediaan/ajax_generate_penjualan_refered'),
			'url_generate_proses_persediaan_view' => site_url('Persediaan/ajax_generate_proses_persediaan_view'),
			'url_generate_proses_pembelian_view' => site_url('Persediaan/ajax_generate_proses_pembelian_view'),
			'url_generate_proses_produksi_view' => site_url('Persediaan/ajax_generate_proses_produksi_view'),
			'url_generate_proses_pecah_satuan_view' => site_url('Persediaan/ajax_generate_proses_pecah_satuan_view'),
			'url_gen_pecah_cari_persediaan_sumber' => site_url('Persediaan/ajax_gen_pecah_cari_persediaan_sumber'),
			'url_gen_pecah_proses_record' => site_url('Persediaan/ajax_gen_pecah_proses_record'),
			'url_generate_proses_penjualan_view' => site_url('Persediaan/ajax_generate_proses_penjualan_view'),
			'url_generate_proses_persediaan_full_view' => site_url('Persediaan/ajax_generate_proses_persediaan_full_view'),
			'url_gen_penjualan_cari_persediaan_mirip' => site_url('Persediaan/ajax_gen_penjualan_cari_persediaan_mirip'),
			'url_gen_penjualan_apply_persediaan' => site_url('Persediaan/ajax_gen_penjualan_apply_persediaan'),
			'url_gen_penjualan_penyesuaian_pecah' => site_url('Persediaan/ajax_gen_penjualan_penyesuaian_pecah'),
			'url_gen_penjualan_penyesuaian_produksi' => site_url('Persediaan/ajax_gen_penjualan_penyesuaian_produksi'),
			'url_excel_generate_proses' => site_url('Persediaan/excel_generate_proses'),
			'url_load_gen_recalc_history' => site_url('Persediaan/ajax_load_gen_recalc_history'),
			'url_gen_recalc_summary_tables' => site_url('Persediaan/ajax_gen_recalc_summary_tables'),
			'url_gen_recalc_extra_tables' => site_url('Persediaan/ajax_gen_recalc_extra_tables'),
			'url_gen_recalc_gagal_preview_persediaan' => site_url('Persediaan/ajax_gen_recalc_gagal_preview_persediaan'),
			'url_gen_recalc_gagal_save_persediaan' => site_url('Persediaan/ajax_gen_recalc_gagal_save_persediaan'),
			'url_gen_recalc_sync_gagal_snapshot' => site_url('Persediaan/ajax_gen_recalc_sync_gagal_snapshot'),
			'url_list_history_generate' => site_url('Persediaan/ajax_list_history_generate'),
			'url_load_history_generate' => site_url('Persediaan/ajax_load_history_generate'),
			'url_excel_gen_recalc_summary' => site_url('Persediaan/excel_gen_recalc_summary'),
			'url_excel_gen_recalc' => site_url('Persediaan/excel_gen_recalc'),
			'url_excel_rekonsiliasi_transaksi' => site_url('Persediaan/excel_rekonsiliasi_transaksi'),
			'url_recalculate_excel' => site_url('Persediaan/excel_recalculate'),
			'url_excel_persediaan' => site_url('Persediaan/excel'),
			'url_excel_draft_bulan_referensi' => site_url('Persediaan/excel_draft_bulan_referensi'),
			'url_compare_tabel_list' => site_url('Persediaan/ajax_compare_tabel_list'),
			'url_compare_tabel_run' => site_url('Persediaan/ajax_compare_tabel_run'),
			'url_compare_tabel_excel' => site_url('Persediaan/excel_compare_tabel'),
			'url_compare_tabel_excel_all' => site_url('Persediaan/excel_compare_tabel_all'),
			'url_compare_import_csv' => site_url('Persediaan/ajax_compare_import_csv'),
			'url_compare_tabel_preview' => site_url('Persediaan/ajax_compare_tabel_preview'),
			'url_compare_insert_to_persediaan' => site_url('Persediaan/ajax_compare_insert_to_persediaan'),
			'url_compare_check_insert_eligible' => site_url('Persediaan/ajax_compare_check_insert_persediaan_eligible'),
			'gen_bulan_default' => (int) date('n', $ts_gen_default),
			'gen_tahun_default' => (int) date('Y', $ts_gen_default),
			'gen_tahun_min' => 2020,
			'gen_tahun_max' => (int) date('Y') + 2,
			'can_generate_persediaan' => $this->persediaan_user_can_generate(),
			'can_compare_persediaan' => $this->persediaan_user_can_compare(),
			'rekap_total_steps' => $this->get_rekap_total_steps(),
			'Persediaan_data_draft_referensi' => $this->get_persediaan_draft_bulan_referensi_by_target($bulan),
			'draft_referensi_bulan_sumber' => $this->get_persediaan_draft_bulan_referensi_sumber_label($bulan),
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

		$this->load->helper('pembelian_persediaan');
		$count_pembelian_barang = persediaan_gen_v2_count_pembelian_bulan($this, 'tbl_pembelian', $tgl_awal, $tgl_akhir);
		$count_pembelian_jasa = persediaan_gen_v2_count_pembelian_bulan($this, 'tbl_pembelian_jasa', $tgl_awal, $tgl_akhir);
		$show_pembelian_proses_view = ($count_target > 0 || $count_pembelian_barang > 0 || $count_pembelian_jasa > 0);
		$count_unit_produk = persediaan_gen_v2_count_unit_produk_bulan($this, $tgl_awal, $tgl_akhir);
		$show_produksi_proses_view = ($count_target > 0 || $count_unit_produk > 0);
		$count_penjualan = persediaan_gen_v2_count_penjualan_bulan($this, $tgl_awal, $tgl_akhir);
		$show_penjualan_proses_view = ($count_target > 0 || $count_penjualan > 0);

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
			'show_proses_view' => ($count_target > 0 && $count_sumber_all > 0),
			'show_pembelian_proses_view' => $show_pembelian_proses_view,
			'count_pembelian_barang' => $count_pembelian_barang,
			'count_pembelian_jasa' => $count_pembelian_jasa,
			'show_produksi_proses_view' => $show_produksi_proses_view,
			'count_unit_produk' => $count_unit_produk,
			'show_penjualan_proses_view' => $show_penjualan_proses_view,
			'count_penjualan' => $count_penjualan,
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

	/**
	 * AJAX: ambil satu record persediaan jasa untuk form ubah.
	 */
	public function ajax_get_persediaan_jasa()
	{
		if (!$this->input->is_ajax_request()) {
			show_404();
			return;
		}

		header('Content-Type: application/json; charset=UTF-8');
		$this->load->helper(array('pembelian_persediaan', 'persediaan_display'));

		$id = (int) $this->input->post('id', TRUE);
		if ($id <= 0) {
			$id = (int) $this->input->get('id', TRUE);
		}
		if ($id <= 0) {
			echo json_encode(array('ok' => false, 'message' => 'ID persediaan tidak valid.'));
			return;
		}

		$row = $this->Persediaan_model->get_by_id($id);
		if (!$row) {
			echo json_encode(array('ok' => false, 'message' => 'Data persediaan tidak ditemukan.'));
			return;
		}

		if (!persediaan_row_is_kategori_jasa($row)) {
			echo json_encode(array('ok' => false, 'message' => 'Record bukan kategori jasa.'));
			return;
		}

		echo json_encode(array(
			'ok' => true,
			'data' => array(
				'id' => (int) $row->id,
				'namabarang' => isset($row->namabarang) ? $row->namabarang : '',
				'satuan' => isset($row->satuan) ? $row->satuan : '',
				'hpp' => isset($row->hpp) ? $row->hpp : '',
				'sa' => isset($row->sa) ? $row->sa : '',
				'spop' => isset($row->spop) ? $row->spop : '',
				'beli' => isset($row->beli) ? $row->beli : '',
				'tuj' => isset($row->tuj) ? $row->tuj : '',
				'total_10' => isset($row->total_10) ? $row->total_10 : '',
				'nilai_persediaan' => isset($row->nilai_persediaan) ? $row->nilai_persediaan : '',
				'tanggal_beli' => isset($row->tanggal_beli) ? $row->tanggal_beli : '',
			),
		));
	}

	/**
	 * AJAX: ubah record persediaan jasa (field terbatas).
	 */
	public function ajax_update_persediaan_jasa()
	{
		if (!$this->input->is_ajax_request()) {
			show_404();
			return;
		}

		header('Content-Type: application/json; charset=UTF-8');
		$this->load->helper(array('pembelian_persediaan', 'persediaan_display'));

		$id = (int) $this->input->post('id', TRUE);
		if ($id <= 0) {
			echo json_encode(array('ok' => false, 'message' => 'ID persediaan tidak valid.'));
			return;
		}

		$row = $this->Persediaan_model->get_by_id($id);
		if (!$row) {
			echo json_encode(array('ok' => false, 'message' => 'Data persediaan tidak ditemukan.'));
			return;
		}

		if (!persediaan_row_is_kategori_jasa($row)) {
			echo json_encode(array('ok' => false, 'message' => 'Record bukan kategori jasa.'));
			return;
		}

		$namabarang = pembelian_normalize_nama_barang($this->input->post('namabarang', TRUE));
		$satuan = trim((string) $this->input->post('satuan', TRUE));
		if ($namabarang === '') {
			echo json_encode(array('ok' => false, 'message' => 'Nama jasa wajib diisi.'));
			return;
		}
		if ($satuan === '') {
			echo json_encode(array('ok' => false, 'message' => 'Satuan wajib diisi.'));
			return;
		}

		$fields_num = array('hpp', 'sa', 'beli', 'tuj', 'total_10', 'nilai_persediaan');
		$update = array(
			'namabarang' => $namabarang,
			'satuan' => $satuan,
			'spop' => trim((string) $this->input->post('spop', TRUE)),
		);

		foreach ($fields_num as $field) {
			$raw = trim((string) $this->input->post($field, TRUE));
			if ($raw === '' || $raw === '-') {
				$update[$field] = '0';
				continue;
			}
			if (in_array($field, array('hpp', 'nilai_persediaan'), true)) {
				$clean = preg_replace('/[^0-9]/', '', str_replace('.', '', $raw));
				$update[$field] = $clean !== '' ? $clean : '0';
			} else {
				$val = (int) floor(persediaan_parse_angka($raw));
				$update[$field] = (string) max(0, $val);
			}
		}

		if ($this->db->field_exists('kategori', 'persediaan')) {
			$update['kategori'] = 'jasa';
		}

		$this->db->where('id', $id);
		if (!$this->db->update('persediaan', $update)) {
			echo json_encode(array('ok' => false, 'message' => 'Gagal mengubah data persediaan.'));
			return;
		}

		echo json_encode(array(
			'ok' => true,
			'success' => true,
			'message' => 'Data jasa berhasil diubah.',
			'id' => $id,
			'namabarang' => $namabarang,
		));
	}

	/**
	 * AJAX: hapus record persediaan jasa.
	 */
	public function ajax_hapus_persediaan_jasa()
	{
		if (!$this->input->is_ajax_request()) {
			show_404();
			return;
		}

		header('Content-Type: application/json; charset=UTF-8');
		$this->load->helper('pembelian_persediaan');

		$id = (int) $this->input->post('id', TRUE);
		if ($id <= 0) {
			echo json_encode(array('ok' => false, 'message' => 'ID persediaan tidak valid.'));
			return;
		}

		$row = $this->Persediaan_model->get_by_id($id);
		if (!$row) {
			echo json_encode(array('ok' => false, 'message' => 'Data persediaan tidak ditemukan.'));
			return;
		}

		if (!persediaan_row_is_kategori_jasa($row)) {
			echo json_encode(array('ok' => false, 'message' => 'Record bukan kategori jasa.'));
			return;
		}

		if (!$this->Persediaan_model->delete($id)) {
			echo json_encode(array('ok' => false, 'message' => 'Gagal menghapus data persediaan.'));
			return;
		}

		echo json_encode(array(
			'ok' => true,
			'success' => true,
			'message' => 'Data jasa berhasil dihapus.',
			'id' => $id,
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

	public function ajax_rekap_bulan_serverside()
	{
		$this->load->helper(array('persediaan_display', 'pembelian_persediaan'));
		try {
			$parsed = $this->parse_bulan_rekap_input();
			if (!$parsed['ok']) {
				$this->datatables_json_output(array(
					'draw' => 0,
					'recordsTotal' => 0,
					'recordsFiltered' => 0,
					'data' => array(),
					'ok' => false,
					'message' => isset($parsed['message']) ? $parsed['message'] : 'Bulan rekap tidak valid.',
				));
				return;
			}

			$bulan = $parsed['bulan'];
			$hasil_rekap = persediaan_rekap_run_silent_db($this, function () use ($bulan) {
				return $this->get_persediaan_rekap_rows($bulan);
			});
			$rows = isset($hasil_rekap['items']) && is_array($hasil_rekap['items']) ? $hasil_rekap['items'] : array();
			$request = $this->get_datatables_request(0);
			$filtered = $this->datatables_slice_rows(
				$rows,
				array('nomor', 'deskripsi', 'nominal_tampil'),
				array(
					0 => array('field' => 'nomor', 'numeric' => true),
					1 => array('field' => 'deskripsi', 'numeric' => false),
					2 => array('field' => 'nominal', 'numeric' => true),
				),
				$request
			);

			$this->datatables_json_output(array(
				'draw' => $request['draw'],
				'recordsTotal' => $filtered['recordsTotal'],
				'recordsFiltered' => $filtered['recordsFiltered'],
				'data' => array_values($filtered['rows']),
				'ok' => true,
				'bulan' => $bulan,
				'tanggal_rekap' => $this->get_tanggal_rekap_dari_bulan($bulan),
				'total_detail' => isset($hasil_rekap['total_detail']) ? $hasil_rekap['total_detail'] : 0,
				'total_detail_tampil' => isset($hasil_rekap['total_detail_tampil']) ? $hasil_rekap['total_detail_tampil'] : '0',
			));
		} catch (Exception $e) {
			$this->datatables_json_output(array('draw' => 0, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => array(), 'ok' => false, 'message' => 'Gagal memuat rekap: ' . $e->getMessage()));
		} catch (Throwable $e) {
			$this->datatables_json_output(array('draw' => 0, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => array(), 'ok' => false, 'message' => 'Gagal memuat rekap: ' . $e->getMessage()));
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

	/**
	 * Parse bulan → tanggal awal & akhir bulan untuk filter detail.
	 */
	private function get_bulan_date_range($bulan)
	{
		$tgl_awal = $bulan . '-01';
		$ts = strtotime($tgl_awal);
		if ($ts === false) {
			$tgl_awal = date('Y-m-01');
			$ts = strtotime($tgl_awal);
		}
		$tgl_akhir = date('Y-m-t', $ts);
		$tgl_awal_next = date('Y-m-01', strtotime('+1 month', $ts));
		return array('awal' => $tgl_awal, 'akhir' => $tgl_akhir, 'awal_next' => $tgl_awal_next);
	}

	private function get_persediaan_uuid_lookup_by_bulan($bulan)
	{
		$lookup = array();
		if (!$this->db->table_exists('persediaan') || !$this->db->field_exists('uuid_persediaan', 'persediaan')) {
			return $lookup;
		}

		$rows = $this->db->query(
			"SELECT TRIM(COALESCE(`uuid_persediaan`, '')) AS `uuid_persediaan`
			 FROM `persediaan`
			 WHERE LEFT(COALESCE(`tanggal_beli`, ''), 7) = ?
			   AND TRIM(COALESCE(`uuid_persediaan`, '')) <> ''",
			array($bulan)
		)->result_array();

		foreach ($rows as $row) {
			$uuid = strtolower(trim((string) (isset($row['uuid_persediaan']) ? $row['uuid_persediaan'] : '')));
			if ($uuid !== '') {
				$lookup[$uuid] = true;
			}
		}

		return $lookup;
	}

	private function get_datatables_request($default_order_col = 0)
	{
		$draw = (int) $this->input->post('draw', TRUE);
		$start = max(0, (int) $this->input->post('start', TRUE));
		$length = (int) $this->input->post('length', TRUE);
		if ($length < 0) {
			$length = 0;
		}
		$search = $this->input->post('search', TRUE);
		$search_value = '';
		if (is_array($search) && isset($search['value'])) {
			$search_value = trim((string) $search['value']);
		} else {
			$search_value = trim((string) $search);
		}
		$order = $this->input->post('order', TRUE);
		$order_col = $default_order_col;
		$order_dir = 'asc';
		if (is_array($order) && isset($order[0])) {
			$order0 = $order[0];
			if (isset($order0['column'])) {
				$order_col = (int) $order0['column'];
			}
			if (isset($order0['dir']) && strtolower((string) $order0['dir']) === 'desc') {
				$order_dir = 'desc';
			}
		}
		return array(
			'draw' => $draw,
			'start' => $start,
			'length' => $length,
			'search' => $search_value,
			'order_col' => $order_col,
			'order_dir' => $order_dir,
		);
	}

	private function datatables_row_matches_search($row, $search, $fields)
	{
		if ($search === '') {
			return true;
		}
		$haystack = array();
		foreach ($fields as $field) {
			if (is_array($field)) {
				$field_name = isset($field['field']) ? $field['field'] : '';
			} else {
				$field_name = (string) $field;
			}
			if ($field_name === '') {
				continue;
			}
			$value = isset($row[$field_name]) ? $row[$field_name] : '';
			$haystack[] = strtolower((string) $value);
		}
		return strpos(implode(' ', $haystack), strtolower($search)) !== false;
	}

	private function datatables_sort_rows(array $rows, $order_map, $order_col, $order_dir)
	{
		if (!isset($order_map[$order_col])) {
			$order_col = 0;
		}
		$sort_cfg = $order_map[$order_col];
		usort($rows, function ($a, $b) use ($sort_cfg, $order_dir) {
			$field = isset($sort_cfg['field']) ? $sort_cfg['field'] : '';
			$numeric = !empty($sort_cfg['numeric']);
			$va = isset($a[$field]) ? $a[$field] : '';
			$vb = isset($b[$field]) ? $b[$field] : '';
			if ($numeric) {
				$va = (float) $va;
				$vb = (float) $vb;
			} else {
				$va = strtolower((string) $va);
				$vb = strtolower((string) $vb);
			}
			if ($va == $vb) {
				return 0;
			}
			$result = ($va < $vb) ? -1 : 1;
			return ($order_dir === 'desc') ? -$result : $result;
		});
		return $rows;
	}

	private function datatables_slice_rows(array $rows, array $search_fields, array $order_map, array $request)
	{
		$records_total = count($rows);
		$filtered = array();
		foreach ($rows as $row) {
			if ($this->datatables_row_matches_search($row, $request['search'], $search_fields)) {
				$filtered[] = $row;
			}
		}
		$records_filtered = count($filtered);
		$filtered = $this->datatables_sort_rows($filtered, $order_map, $request['order_col'], $request['order_dir']);
		if ($request['length'] > 0) {
			$filtered = array_slice($filtered, $request['start'], $request['length']);
		}
		return array(
			'recordsTotal' => $records_total,
			'recordsFiltered' => $records_filtered,
			'rows' => $filtered,
		);
	}

	private function datatables_json_output(array $payload)
	{
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($payload);
		exit();
	}

	/**
	 * AJAX: detail produksi (sys_unit_produk) untuk bulan terpilih.
	 */
	public function ajax_rekap_detail_produksi()
	{
		$this->load->helper('persediaan_display');
		try {
			$parsed = $this->parse_bulan_rekap_input();
			if (!$parsed['ok']) {
				persediaan_ajax_json_output($this, $parsed);
				return;
			}
			$bulan = $parsed['bulan'];
			$range = $this->get_bulan_date_range($bulan);

			if (!$this->db->table_exists('sys_unit_produk')) {
				persediaan_ajax_json_output($this, array('ok' => true, 'bulan' => $bulan, 'rows' => array(), 'grand_total' => 0, 'grand_total_tampil' => '0'));
				return;
			}

			$has_uuid_persediaan = $this->db->field_exists('uuid_persediaan', 'sys_unit_produk');
			$persediaan_uuid_lookup = $this->get_persediaan_uuid_lookup_by_bulan($bulan);
			$select_uuid = $has_uuid_persediaan ? ', `uuid_persediaan`' : '';

			$rows = $this->db->query(
				"SELECT `tgl_transaksi`, `kode_unit`, `nama_unit`, `nama_barang`, `jumlah_produksi`, `satuan`, `harga_satuan`" . $select_uuid . "
				 FROM `sys_unit_produk`
				 WHERE `tgl_transaksi` >= ? AND `tgl_transaksi` < ?
				 ORDER BY `tgl_transaksi` ASC, `nama_unit` ASC, `nama_barang` ASC",
				array($range['awal'], $range['awal_next'])
			)->result();

			$out = array();
			$no = 1;
			$grand_total = 0;
			foreach ($rows as $r) {
				$qty   = persediaan_parse_angka(isset($r->jumlah_produksi) ? $r->jumlah_produksi : 0);
				$harga = persediaan_parse_angka(isset($r->harga_satuan) ? $r->harga_satuan : 0);
				$total = $qty * $harga;
				$grand_total += $total;
				$uuid_persediaan = $has_uuid_persediaan ? trim((string) (isset($r->uuid_persediaan) ? $r->uuid_persediaan : '')) : '';
				$uuid_key = strtolower($uuid_persediaan);
				$sudah_tersimpan = ($uuid_key !== '' && isset($persediaan_uuid_lookup[$uuid_key]));
				$status_html = $sudah_tersimpan
					? '<span class="badge badge-success">Sudah tersimpan</span>'
					: '<button type="button" class="btn btn-warning btn-xs btn-belum-tersimpan-persediaan" data-uuid-persediaan="' . htmlspecialchars($uuid_persediaan, ENT_QUOTES, 'UTF-8') . '">Belum tersimpan di data persediaan</button>';
				$out[] = array(
					'no' => $no++,
					'tgl' => isset($r->tgl_transaksi) ? $r->tgl_transaksi : '',
					'status_persediaan' => $sudah_tersimpan ? 'Sudah tersimpan' : 'Belum tersimpan di data persediaan',
					'status_persediaan_html' => $status_html,
					'uuid_persediaan' => $uuid_persediaan,
					'kode_unit' => isset($r->kode_unit) ? $r->kode_unit : '',
					'nama_unit' => isset($r->nama_unit) ? $r->nama_unit : '',
					'nama_barang' => isset($r->nama_barang) ? $r->nama_barang : '',
					'jumlah' => $qty,
					'jumlah_tampil' => number_format($qty, 0, ',', '.'),
					'satuan' => isset($r->satuan) ? $r->satuan : '',
					'harga_satuan' => $harga,
					'harga_satuan_tampil' => number_format($harga, 0, ',', '.'),
					'total' => $total,
					'total_tampil' => number_format($total, 0, ',', '.'),
				);
			}
			persediaan_ajax_json_output($this, array('ok' => true, 'bulan' => $bulan, 'rows' => $out, 'grand_total' => $grand_total, 'grand_total_tampil' => number_format($grand_total, 0, ',', '.')));
		} catch (Exception $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Gagal memuat detail produksi: ' . $e->getMessage()));
		} catch (Throwable $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Gagal memuat detail produksi: ' . $e->getMessage()));
		}
	}

	public function ajax_rekap_detail_produksi_serverside()
	{
		$this->load->helper('persediaan_display');
		try {
			$parsed = $this->parse_bulan_rekap_input();
			if (!$parsed['ok']) {
				$this->datatables_json_output(array(
					'draw' => 0,
					'recordsTotal' => 0,
					'recordsFiltered' => 0,
					'data' => array(),
					'ok' => false,
					'message' => isset($parsed['message']) ? $parsed['message'] : 'Bulan rekap tidak valid.',
				));
				return;
			}

			$bulan = $parsed['bulan'];
			$range = $this->get_bulan_date_range($bulan);
			if (!$this->db->table_exists('sys_unit_produk')) {
				$this->datatables_json_output(array('draw' => 0, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => array(), 'ok' => true, 'bulan' => $bulan, 'grand_total' => 0, 'grand_total_tampil' => '0'));
				return;
			}

			$has_uuid_persediaan = $this->db->field_exists('uuid_persediaan', 'sys_unit_produk');
			$persediaan_uuid_lookup = $this->get_persediaan_uuid_lookup_by_bulan($bulan);
			$select_uuid = $has_uuid_persediaan ? ', `uuid_persediaan`' : '';

			$rows = $this->db->query(
				"SELECT `tgl_transaksi`, `kode_unit`, `nama_unit`, `nama_barang`, `jumlah_produksi`, `satuan`, `harga_satuan`" . $select_uuid . "
				 FROM `sys_unit_produk`
				 WHERE `tgl_transaksi` >= ? AND `tgl_transaksi` < ?
				 ORDER BY `tgl_transaksi` ASC, `nama_unit` ASC, `nama_barang` ASC",
				array($range['awal'], $range['awal_next'])
			)->result_array();

			$mapped = array();
			$grand_total = 0;
			foreach ($rows as $idx => $row) {
				$qty = persediaan_parse_angka(isset($row['jumlah_produksi']) ? $row['jumlah_produksi'] : 0);
				$harga = persediaan_parse_angka(isset($row['harga_satuan']) ? $row['harga_satuan'] : 0);
				$total = $qty * $harga;
				$uuid_persediaan = $has_uuid_persediaan ? trim((string) (isset($row['uuid_persediaan']) ? $row['uuid_persediaan'] : '')) : '';
				$uuid_key = strtolower($uuid_persediaan);
				$sudah_tersimpan = ($uuid_key !== '' && isset($persediaan_uuid_lookup[$uuid_key]));
				$status_html = $sudah_tersimpan
					? '<span class="badge badge-success">Sudah tersimpan</span>'
					: '<button type="button" class="btn btn-warning btn-xs btn-belum-tersimpan-persediaan" data-uuid-persediaan="' . htmlspecialchars($uuid_persediaan, ENT_QUOTES, 'UTF-8') . '">Belum tersimpan di data persediaan</button>';
				$grand_total += $total;
				$mapped[] = array(
					'no' => $idx + 1,
					'status_persediaan' => $sudah_tersimpan ? 'Sudah tersimpan' : 'Belum tersimpan di data persediaan',
					'status_persediaan_html' => $status_html,
					'uuid_persediaan' => $uuid_persediaan,
					'tgl' => isset($row['tgl_transaksi']) ? $row['tgl_transaksi'] : '',
					'kode_unit' => isset($row['kode_unit']) ? $row['kode_unit'] : '',
					'nama_unit' => isset($row['nama_unit']) ? $row['nama_unit'] : '',
					'nama_barang' => isset($row['nama_barang']) ? $row['nama_barang'] : '',
					'jumlah' => $qty,
					'jumlah_tampil' => number_format($qty, 0, ',', '.'),
					'satuan' => isset($row['satuan']) ? $row['satuan'] : '',
					'harga_satuan' => $harga,
					'harga_satuan_tampil' => number_format($harga, 0, ',', '.'),
					'total' => $total,
					'total_tampil' => number_format($total, 0, ',', '.'),
				);
			}

			$request = $this->get_datatables_request(1);
			$filtered = $this->datatables_slice_rows(
				$mapped,
				array('status_persediaan', 'tgl', 'nama_unit', 'nama_barang', 'jumlah_tampil', 'satuan', 'harga_satuan_tampil', 'total_tampil'),
				array(
					0 => array('field' => 'no', 'numeric' => true),
					1 => array('field' => 'status_persediaan', 'numeric' => false),
					2 => array('field' => 'tgl', 'numeric' => false),
					3 => array('field' => 'nama_unit', 'numeric' => false),
					4 => array('field' => 'nama_barang', 'numeric' => false),
					5 => array('field' => 'jumlah', 'numeric' => true),
					6 => array('field' => 'satuan', 'numeric' => false),
					7 => array('field' => 'harga_satuan', 'numeric' => true),
					8 => array('field' => 'total', 'numeric' => true),
				),
				$request
			);

			$this->datatables_json_output(array(
				'draw' => $request['draw'],
				'recordsTotal' => $filtered['recordsTotal'],
				'recordsFiltered' => $filtered['recordsFiltered'],
				'data' => array_values($filtered['rows']),
				'ok' => true,
				'bulan' => $bulan,
				'grand_total' => $grand_total,
				'grand_total_tampil' => number_format($grand_total, 0, ',', '.'),
			));
		} catch (Exception $e) {
			$this->datatables_json_output(array('draw' => 0, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => array(), 'ok' => false, 'message' => 'Gagal memuat detail produksi: ' . $e->getMessage()));
		} catch (Throwable $e) {
			$this->datatables_json_output(array('draw' => 0, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => array(), 'ok' => false, 'message' => 'Gagal memuat detail produksi: ' . $e->getMessage()));
		}
	}

	public function excel_rekap_detail_produksi()
	{
		$bulan = trim((string) $this->input->post('bulan_persediaan', TRUE));
		if ($bulan === '') {
			$bulan = date('Y-m');
		}

		$this->load->helper('persediaan_display');
		$parsed = $this->parse_bulan_rekap_input();
		if (!$parsed['ok']) {
			show_error(isset($parsed['message']) ? $parsed['message'] : 'Bulan rekap tidak valid.', 400);
			return;
		}

		$bulan = $parsed['bulan'];
		$range = $this->get_bulan_date_range($bulan);
		$rows = array();
		$grand_total = 0;
		if ($this->db->table_exists('sys_unit_produk')) {
			$rows = $this->db->query(
				"SELECT `tgl_transaksi`, `kode_unit`, `nama_unit`, `nama_barang`, `jumlah_produksi`, `satuan`, `harga_satuan`
				 FROM `sys_unit_produk`
				 WHERE `tgl_transaksi` >= ? AND `tgl_transaksi` < ?
				 ORDER BY `tgl_transaksi` ASC, `nama_unit` ASC, `nama_barang` ASC",
				array($range['awal'], $range['awal_next'])
			)->result_array();
		}

		$waktu_klik = date('Y-m-d_H-i-s');
		$namaFile = 'Detail_Produksi_Persediaan_' . $bulan . '_' . $waktu_klik . '.xls';
		header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
		header('Content-Disposition: attachment; filename="' . $namaFile . '"');
		header('Pragma: no-cache');
		header('Expires: 0');

		echo '<html><head><meta charset="UTF-8"><style>';
		echo 'body{font-family:Arial,sans-serif;font-size:12px;color:#222;}';
		echo 'table{border-collapse:collapse;width:100%;margin-top:10px;}';
		echo 'th,td{border:1px solid #c9d2dc;padding:6px 8px;vertical-align:top;}';
		echo 'th{background:#0d6efd;color:#fff;font-weight:bold;text-align:center;}';
		echo 'tr:nth-child(even) td{background:#f8fbff;}';
		echo '.text-right{text-align:right;}';
		echo '.title{font-size:16px;font-weight:bold;margin-bottom:4px;}';
		echo '.subtitle{color:#555;margin-bottom:8px;}';
		echo '.total-row td{font-weight:bold;background:#eef4ff;}';
		echo '</style></head><body>';
		echo '<div class="title">Detail Produksi Persediaan</div>';
		echo '<div class="subtitle">Bulan: ' . htmlspecialchars($bulan, ENT_QUOTES, 'UTF-8') . '</div>';
		echo '<table>';
		echo '<thead><tr>';
		echo '<th>No</th><th>Tanggal</th><th>Nama Unit</th><th>Nama Barang</th><th class="text-right">Jumlah</th><th>Satuan</th><th class="text-right">Harga Satuan</th><th class="text-right">Total</th>';
		echo '</tr></thead><tbody>';
		$no = 0;
		foreach ($rows as $row) {
			$qty = persediaan_parse_angka(isset($row['jumlah_produksi']) ? $row['jumlah_produksi'] : 0);
			$harga = persediaan_parse_angka(isset($row['harga_satuan']) ? $row['harga_satuan'] : 0);
			$total = $qty * $harga;
			$grand_total += $total;
			$no++;
			echo '<tr>';
			echo '<td>' . $no . '</td>';
			echo '<td>' . htmlspecialchars(isset($row['tgl_transaksi']) ? $row['tgl_transaksi'] : '', ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td>' . htmlspecialchars(isset($row['nama_unit']) ? $row['nama_unit'] : '', ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td>' . htmlspecialchars(isset($row['nama_barang']) ? $row['nama_barang'] : '', ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td class="text-right">' . htmlspecialchars(number_format($qty, 0, ',', '.'), ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td>' . htmlspecialchars(isset($row['satuan']) ? $row['satuan'] : '', ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td class="text-right">' . htmlspecialchars(number_format($harga, 0, ',', '.'), ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td class="text-right">' . htmlspecialchars(number_format($total, 0, ',', '.'), ENT_QUOTES, 'UTF-8') . '</td>';
			echo '</tr>';
		}
		echo '</tbody>';
		echo '<tfoot><tr class="total-row">';
		echo '<td colspan="7" class="text-right">Grand Total</td>';
		echo '<td class="text-right">' . htmlspecialchars(number_format($grand_total, 0, ',', '.'), ENT_QUOTES, 'UTF-8') . '</td>';
		echo '</tr></tfoot>';
		echo '</table>';
		echo '</body></html>';
		exit();
	}

	/**
	 * AJAX: detail pecah satuan (tbl_pembelian_pecah_satuan) untuk bulan terpilih.
	 */
	public function ajax_rekap_detail_pecah_satuan()
	{
		$this->load->helper('persediaan_display');
		try {
			$parsed = $this->parse_bulan_rekap_input();
			if (!$parsed['ok']) {
				persediaan_ajax_json_output($this, $parsed);
				return;
			}
			$bulan = $parsed['bulan'];
			$range = $this->get_bulan_date_range($bulan);

			if (!$this->db->table_exists('tbl_pembelian_pecah_satuan')) {
				persediaan_ajax_json_output($this, array('ok' => true, 'bulan' => $bulan, 'rows' => array(), 'grand_total' => 0, 'grand_total_tampil' => '0'));
				return;
			}

			$rows = $this->db->query(
				"SELECT `tgl_po`, `spop`, `uraian`, `jumlah`, `satuan`, `harga_satuan`, `nama_barang_baru`, `satuan_barang_baru`, `harga_satuan_barang_baru`, `supplier_nama`
				 FROM `tbl_pembelian_pecah_satuan`
				 WHERE `tgl_po` BETWEEN ? AND ?
				 ORDER BY `tgl_po` ASC, `spop` ASC, `id` ASC",
				array($range['awal'], $range['akhir'])
			)->result();

			$out = array();
			$no = 1;
			$grand_total = 0;
			foreach ($rows as $r) {
				$qty   = persediaan_parse_angka(isset($r->jumlah) ? $r->jumlah : 0);
				$harga = persediaan_parse_angka(isset($r->harga_satuan) ? $r->harga_satuan : 0);
				$total = $qty * $harga;
				$grand_total += $total;
				$out[] = array(
					'no' => $no++,
					'tgl' => isset($r->tgl_po) ? $r->tgl_po : '',
					'spop' => isset($r->spop) ? $r->spop : '',
					'uraian' => isset($r->uraian) ? $r->uraian : '',
					'jumlah' => $qty,
					'jumlah_tampil' => number_format($qty, 0, ',', '.'),
					'satuan' => isset($r->satuan) ? $r->satuan : '',
					'harga_satuan' => $harga,
					'harga_satuan_tampil' => number_format($harga, 0, ',', '.'),
					'total' => $total,
					'total_tampil' => number_format($total, 0, ',', '.'),
					'nama_barang_baru' => isset($r->nama_barang_baru) ? $r->nama_barang_baru : '',
					'satuan_barang_baru' => isset($r->satuan_barang_baru) ? $r->satuan_barang_baru : '',
					'harga_satuan_barang_baru' => number_format(persediaan_parse_angka(isset($r->harga_satuan_barang_baru) ? $r->harga_satuan_barang_baru : 0), 0, ',', '.'),
					'supplier_nama' => isset($r->supplier_nama) ? $r->supplier_nama : '',
				);
			}
			persediaan_ajax_json_output($this, array('ok' => true, 'bulan' => $bulan, 'rows' => $out, 'grand_total' => $grand_total, 'grand_total_tampil' => number_format($grand_total, 0, ',', '.')));
		} catch (Exception $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Gagal memuat detail pecah satuan: ' . $e->getMessage()));
		} catch (Throwable $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Gagal memuat detail pecah satuan: ' . $e->getMessage()));
		}
	}

	public function ajax_rekap_detail_pecah_satuan_serverside()
	{
		$this->load->helper('persediaan_display');
		try {
			$parsed = $this->parse_bulan_rekap_input();
			if (!$parsed['ok']) {
				$this->datatables_json_output(array(
					'draw' => 0,
					'recordsTotal' => 0,
					'recordsFiltered' => 0,
					'data' => array(),
					'ok' => false,
					'message' => isset($parsed['message']) ? $parsed['message'] : 'Bulan rekap tidak valid.',
				));
				return;
			}

			$bulan = $parsed['bulan'];
			$range = $this->get_bulan_date_range($bulan);
			if (!$this->db->table_exists('tbl_pembelian_pecah_satuan')) {
				$this->datatables_json_output(array('draw' => 0, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => array(), 'ok' => true, 'bulan' => $bulan, 'grand_total' => 0, 'grand_total_tampil' => '0'));
				return;
			}

			$rows = $this->db->query(
				"SELECT `tgl_po`, `spop`, `uraian`, `jumlah`, `satuan`, `harga_satuan`, `nama_barang_baru`, `satuan_barang_baru`, `harga_satuan_barang_baru`, `supplier_nama`
				 FROM `tbl_pembelian_pecah_satuan`
				 WHERE `tgl_po` BETWEEN ? AND ?
				 ORDER BY `tgl_po` ASC, `spop` ASC, `id` ASC",
				array($range['awal'], $range['akhir'])
			)->result_array();

			$mapped = array();
			$grand_total = 0;
			foreach ($rows as $idx => $row) {
				$qty = persediaan_parse_angka(isset($row['jumlah']) ? $row['jumlah'] : 0);
				$harga = persediaan_parse_angka(isset($row['harga_satuan']) ? $row['harga_satuan'] : 0);
				$total = $qty * $harga;
				$harga_baru = persediaan_parse_angka(isset($row['harga_satuan_barang_baru']) ? $row['harga_satuan_barang_baru'] : 0);
				$grand_total += $total;
				$mapped[] = array(
					'no' => $idx + 1,
					'tgl' => isset($row['tgl_po']) ? $row['tgl_po'] : '',
					'spop' => isset($row['spop']) ? $row['spop'] : '',
					'uraian' => isset($row['uraian']) ? $row['uraian'] : '',
					'jumlah' => $qty,
					'jumlah_tampil' => number_format($qty, 0, ',', '.'),
					'satuan' => isset($row['satuan']) ? $row['satuan'] : '',
					'harga_satuan' => $harga,
					'harga_satuan_tampil' => number_format($harga, 0, ',', '.'),
					'total' => $total,
					'total_tampil' => number_format($total, 0, ',', '.'),
					'nama_barang_baru' => isset($row['nama_barang_baru']) ? $row['nama_barang_baru'] : '',
					'satuan_barang_baru' => isset($row['satuan_barang_baru']) ? $row['satuan_barang_baru'] : '',
					'harga_satuan_barang_baru' => $harga_baru,
					'harga_satuan_barang_baru_tampil' => number_format($harga_baru, 0, ',', '.'),
				);
			}

			$request = $this->get_datatables_request(1);
			$filtered = $this->datatables_slice_rows(
				$mapped,
				array('tgl', 'spop', 'uraian', 'jumlah_tampil', 'satuan', 'harga_satuan_tampil', 'total_tampil', 'nama_barang_baru', 'satuan_barang_baru', 'harga_satuan_barang_baru_tampil'),
				array(
					0 => array('field' => 'no', 'numeric' => true),
					1 => array('field' => 'tgl', 'numeric' => false),
					2 => array('field' => 'spop', 'numeric' => false),
					3 => array('field' => 'uraian', 'numeric' => false),
					4 => array('field' => 'jumlah', 'numeric' => true),
					5 => array('field' => 'satuan', 'numeric' => false),
					6 => array('field' => 'harga_satuan', 'numeric' => true),
					7 => array('field' => 'total', 'numeric' => true),
					8 => array('field' => 'nama_barang_baru', 'numeric' => false),
					9 => array('field' => 'satuan_barang_baru', 'numeric' => false),
					10 => array('field' => 'harga_satuan_barang_baru', 'numeric' => true),
				),
				$request
			);

			$this->datatables_json_output(array(
				'draw' => $request['draw'],
				'recordsTotal' => $filtered['recordsTotal'],
				'recordsFiltered' => $filtered['recordsFiltered'],
				'data' => array_values($filtered['rows']),
				'ok' => true,
				'bulan' => $bulan,
				'grand_total' => $grand_total,
				'grand_total_tampil' => number_format($grand_total, 0, ',', '.'),
			));
		} catch (Exception $e) {
			$this->datatables_json_output(array('draw' => 0, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => array(), 'ok' => false, 'message' => 'Gagal memuat detail pecah satuan: ' . $e->getMessage()));
		} catch (Throwable $e) {
			$this->datatables_json_output(array('draw' => 0, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => array(), 'ok' => false, 'message' => 'Gagal memuat detail pecah satuan: ' . $e->getMessage()));
		}
	}

	public function excel_rekap_detail_pecah_satuan()
	{
		$bulan = trim((string) $this->input->post('bulan_persediaan', TRUE));
		if ($bulan === '') {
			$bulan = date('Y-m');
		}

		$this->load->helper('persediaan_display');
		$parsed = $this->parse_bulan_rekap_input();
		if (!$parsed['ok']) {
			show_error(isset($parsed['message']) ? $parsed['message'] : 'Bulan rekap tidak valid.', 400);
			return;
		}

		$bulan = $parsed['bulan'];
		$range = $this->get_bulan_date_range($bulan);
		$rows = array();
		$grand_total = 0;
		if ($this->db->table_exists('tbl_pembelian_pecah_satuan')) {
			$rows = $this->db->query(
				"SELECT `tgl_po`, `spop`, `uraian`, `jumlah`, `satuan`, `harga_satuan`, `nama_barang_baru`, `satuan_barang_baru`, `harga_satuan_barang_baru`
				 FROM `tbl_pembelian_pecah_satuan`
				 WHERE `tgl_po` BETWEEN ? AND ?
				 ORDER BY `tgl_po` ASC, `spop` ASC, `id` ASC",
				array($range['awal'], $range['akhir'])
			)->result_array();
		}

		$waktu_klik = date('Y-m-d_H-i-s');
		$namaFile = 'Detail_Pecah_Satuan_Persediaan_' . $bulan . '_' . $waktu_klik . '.xls';
		header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
		header('Content-Disposition: attachment; filename="' . $namaFile . '"');
		header('Pragma: no-cache');
		header('Expires: 0');

		echo '<html><head><meta charset="UTF-8"><style>';
		echo 'body{font-family:Arial,sans-serif;font-size:12px;color:#222;}';
		echo 'table{border-collapse:collapse;width:100%;margin-top:10px;}';
		echo 'th,td{border:1px solid #c9d2dc;padding:6px 8px;vertical-align:top;}';
		echo 'th{background:#198754;color:#fff;font-weight:bold;text-align:center;}';
		echo 'tr:nth-child(even) td{background:#f6fffa;}';
		echo '.text-right{text-align:right;}';
		echo '.title{font-size:16px;font-weight:bold;margin-bottom:4px;}';
		echo '.subtitle{color:#555;margin-bottom:8px;}';
		echo '.total-row td{font-weight:bold;background:#e9f8f0;}';
		echo '</style></head><body>';
		echo '<div class="title">Detail Pecah Satuan Persediaan</div>';
		echo '<div class="subtitle">Bulan: ' . htmlspecialchars($bulan, ENT_QUOTES, 'UTF-8') . '</div>';
		echo '<table>';
		echo '<thead><tr>';
		echo '<th>No</th><th>Tanggal</th><th>SPOP</th><th>Uraian Asal</th><th class="text-right">Jumlah</th><th>Satuan</th><th class="text-right">HPP</th><th class="text-right">Total</th><th>Barang Baru</th><th>Satuan Baru</th><th class="text-right">HPP Baru</th>';
		echo '</tr></thead><tbody>';
		$no = 0;
		foreach ($rows as $row) {
			$qty = persediaan_parse_angka(isset($row['jumlah']) ? $row['jumlah'] : 0);
			$harga = persediaan_parse_angka(isset($row['harga_satuan']) ? $row['harga_satuan'] : 0);
			$total = $qty * $harga;
			$harga_baru = persediaan_parse_angka(isset($row['harga_satuan_barang_baru']) ? $row['harga_satuan_barang_baru'] : 0);
			$grand_total += $total;
			$no++;
			echo '<tr>';
			echo '<td>' . $no . '</td>';
			echo '<td>' . htmlspecialchars(isset($row['tgl_po']) ? $row['tgl_po'] : '', ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td>' . htmlspecialchars(isset($row['spop']) ? $row['spop'] : '', ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td>' . htmlspecialchars(isset($row['uraian']) ? $row['uraian'] : '', ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td class="text-right">' . htmlspecialchars(number_format($qty, 0, ',', '.'), ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td>' . htmlspecialchars(isset($row['satuan']) ? $row['satuan'] : '', ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td class="text-right">' . htmlspecialchars(number_format($harga, 0, ',', '.'), ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td class="text-right">' . htmlspecialchars(number_format($total, 0, ',', '.'), ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td>' . htmlspecialchars(isset($row['nama_barang_baru']) ? $row['nama_barang_baru'] : '', ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td>' . htmlspecialchars(isset($row['satuan_barang_baru']) ? $row['satuan_barang_baru'] : '', ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td class="text-right">' . htmlspecialchars(number_format($harga_baru, 0, ',', '.'), ENT_QUOTES, 'UTF-8') . '</td>';
			echo '</tr>';
		}
		echo '</tbody>';
		echo '<tfoot><tr class="total-row">';
		echo '<td colspan="7" class="text-right">Grand Total</td>';
		echo '<td class="text-right">' . htmlspecialchars(number_format($grand_total, 0, ',', '.'), ENT_QUOTES, 'UTF-8') . '</td>';
		echo '<td colspan="3"></td>';
		echo '</tr></tfoot>';
		echo '</table>';
		echo '</body></html>';
		exit();
	}

	/**
	 * AJAX: detail pembelian barang (tbl_pembelian) untuk bulan terpilih.
	 */
	public function ajax_rekap_detail_pembelian_barang()
	{
		$this->load->helper('persediaan_display');
		try {
			$parsed = $this->parse_bulan_rekap_input();
			if (!$parsed['ok']) {
				persediaan_ajax_json_output($this, $parsed);
				return;
			}
			$bulan = $parsed['bulan'];
			$range = $this->get_bulan_date_range($bulan);

			if (!$this->db->table_exists('tbl_pembelian')) {
				persediaan_ajax_json_output($this, array('ok' => true, 'bulan' => $bulan, 'rows' => array(), 'grand_total' => 0, 'grand_total_tampil' => '0'));
				return;
			}

			$rows = $this->db->query(
				"SELECT `tgl_po`, `spop`, `uraian`, `jumlah`, `satuan`, `harga_satuan`, `supplier_nama`, `konsumen`
				 FROM `tbl_pembelian`
				 WHERE `tgl_po` BETWEEN ? AND ?
				 ORDER BY `tgl_po` ASC, `spop` ASC, `id` ASC",
				array($range['awal'], $range['akhir'])
			)->result();

			$out = array();
			$no = 1;
			$grand_total = 0;
			foreach ($rows as $r) {
				$qty   = persediaan_parse_angka(isset($r->jumlah) ? $r->jumlah : 0);
				$harga = persediaan_parse_angka(isset($r->harga_satuan) ? $r->harga_satuan : 0);
				$total = $qty * $harga;
				$grand_total += $total;
				$out[] = array(
					'no' => $no++,
					'tgl' => isset($r->tgl_po) ? $r->tgl_po : '',
					'spop' => isset($r->spop) ? $r->spop : '',
					'uraian' => isset($r->uraian) ? $r->uraian : '',
					'jumlah' => $qty,
					'jumlah_tampil' => number_format($qty, 0, ',', '.'),
					'satuan' => isset($r->satuan) ? $r->satuan : '',
					'harga_satuan' => $harga,
					'harga_satuan_tampil' => number_format($harga, 0, ',', '.'),
					'total' => $total,
					'total_tampil' => number_format($total, 0, ',', '.'),
					'supplier_nama' => isset($r->supplier_nama) ? $r->supplier_nama : '',
					'konsumen' => isset($r->konsumen) ? $r->konsumen : '',
				);
			}
			persediaan_ajax_json_output($this, array('ok' => true, 'bulan' => $bulan, 'rows' => $out, 'grand_total' => $grand_total, 'grand_total_tampil' => number_format($grand_total, 0, ',', '.')));
		} catch (Exception $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Gagal memuat detail pembelian barang: ' . $e->getMessage()));
		} catch (Throwable $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Gagal memuat detail pembelian barang: ' . $e->getMessage()));
		}
	}

	public function ajax_rekap_detail_pembelian_barang_serverside()
	{
		$this->load->helper('persediaan_display');
		try {
			$parsed = $this->parse_bulan_rekap_input();
			if (!$parsed['ok']) {
				$this->datatables_json_output(array(
					'draw' => 0,
					'recordsTotal' => 0,
					'recordsFiltered' => 0,
					'data' => array(),
					'ok' => false,
					'message' => isset($parsed['message']) ? $parsed['message'] : 'Bulan rekap tidak valid.',
				));
				return;
			}

			$bulan = $parsed['bulan'];
			$range = $this->get_bulan_date_range($bulan);
			if (!$this->db->table_exists('tbl_pembelian')) {
				$this->datatables_json_output(array('draw' => 0, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => array(), 'ok' => true, 'bulan' => $bulan, 'grand_total' => 0, 'grand_total_tampil' => '0'));
				return;
			}

			$rows = $this->db->query(
				"SELECT `tgl_po`, `spop`, `uraian`, `jumlah`, `satuan`, `harga_satuan`, `supplier_nama`, `konsumen`
				 FROM `tbl_pembelian`
				 WHERE `tgl_po` BETWEEN ? AND ?
				 ORDER BY `tgl_po` ASC, `spop` ASC, `id` ASC",
				array($range['awal'], $range['akhir'])
			)->result_array();

			$mapped = array();
			$grand_total = 0;
			foreach ($rows as $idx => $row) {
				$qty = persediaan_parse_angka(isset($row['jumlah']) ? $row['jumlah'] : 0);
				$harga = persediaan_parse_angka(isset($row['harga_satuan']) ? $row['harga_satuan'] : 0);
				$total = $qty * $harga;
				$grand_total += $total;
				$mapped[] = array(
					'no' => $idx + 1,
					'tgl' => isset($row['tgl_po']) ? $row['tgl_po'] : '',
					'spop' => isset($row['spop']) ? $row['spop'] : '',
					'uraian' => isset($row['uraian']) ? $row['uraian'] : '',
					'jumlah' => $qty,
					'jumlah_tampil' => number_format($qty, 0, ',', '.'),
					'satuan' => isset($row['satuan']) ? $row['satuan'] : '',
					'harga_satuan' => $harga,
					'harga_satuan_tampil' => number_format($harga, 0, ',', '.'),
					'total' => $total,
					'total_tampil' => number_format($total, 0, ',', '.'),
					'supplier_nama' => isset($row['supplier_nama']) ? $row['supplier_nama'] : '',
					'konsumen' => isset($row['konsumen']) ? $row['konsumen'] : '',
				);
			}

			$request = $this->get_datatables_request(1);
			$filtered = $this->datatables_slice_rows(
				$mapped,
				array('tgl', 'spop', 'uraian', 'jumlah_tampil', 'satuan', 'harga_satuan_tampil', 'total_tampil', 'supplier_nama', 'konsumen'),
				array(
					0 => array('field' => 'no', 'numeric' => true),
					1 => array('field' => 'tgl', 'numeric' => false),
					2 => array('field' => 'spop', 'numeric' => false),
					3 => array('field' => 'uraian', 'numeric' => false),
					4 => array('field' => 'jumlah', 'numeric' => true),
					5 => array('field' => 'satuan', 'numeric' => false),
					6 => array('field' => 'harga_satuan', 'numeric' => true),
					7 => array('field' => 'total', 'numeric' => true),
					8 => array('field' => 'supplier_nama', 'numeric' => false),
					9 => array('field' => 'konsumen', 'numeric' => false),
				),
				$request
			);

			$this->datatables_json_output(array(
				'draw' => $request['draw'],
				'recordsTotal' => $filtered['recordsTotal'],
				'recordsFiltered' => $filtered['recordsFiltered'],
				'data' => array_values($filtered['rows']),
				'ok' => true,
				'bulan' => $bulan,
				'grand_total' => $grand_total,
				'grand_total_tampil' => number_format($grand_total, 0, ',', '.'),
			));
		} catch (Exception $e) {
			$this->datatables_json_output(array('draw' => 0, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => array(), 'ok' => false, 'message' => 'Gagal memuat detail pembelian barang: ' . $e->getMessage()));
		} catch (Throwable $e) {
			$this->datatables_json_output(array('draw' => 0, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => array(), 'ok' => false, 'message' => 'Gagal memuat detail pembelian barang: ' . $e->getMessage()));
		}
	}

	public function excel_rekap_detail_pembelian_barang()
	{
		$bulan = trim((string) $this->input->post('bulan_persediaan', TRUE));
		if ($bulan === '') {
			$bulan = date('Y-m');
		}

		$this->load->helper('persediaan_display');
		$parsed = $this->parse_bulan_rekap_input();
		if (!$parsed['ok']) {
			show_error(isset($parsed['message']) ? $parsed['message'] : 'Bulan rekap tidak valid.', 400);
			return;
		}

		$bulan = $parsed['bulan'];
		$range = $this->get_bulan_date_range($bulan);
		$rows = array();
		$grand_total = 0;
		if ($this->db->table_exists('tbl_pembelian')) {
			$rows = $this->db->query(
				"SELECT `tgl_po`, `spop`, `uraian`, `jumlah`, `satuan`, `harga_satuan`, `supplier_nama`, `konsumen`
				 FROM `tbl_pembelian`
				 WHERE `tgl_po` BETWEEN ? AND ?
				 ORDER BY `tgl_po` ASC, `spop` ASC, `id` ASC",
				array($range['awal'], $range['akhir'])
			)->result_array();
		}

		$waktu_klik = date('Y-m-d_H-i-s');
		$namaFile = 'Detail_Pembelian_Barang_Persediaan_' . $bulan . '_' . $waktu_klik . '.xls';
		header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
		header('Content-Disposition: attachment; filename="' . $namaFile . '"');
		header('Pragma: no-cache');
		header('Expires: 0');

		echo '<html><head><meta charset="UTF-8"><style>';
		echo 'body{font-family:Arial,sans-serif;font-size:12px;color:#222;}';
		echo 'table{border-collapse:collapse;width:100%;margin-top:10px;}';
		echo 'th,td{border:1px solid #c9d2dc;padding:6px 8px;vertical-align:top;}';
		echo 'th{background:#0d6efd;color:#fff;font-weight:bold;text-align:center;}';
		echo 'tr:nth-child(even) td{background:#f8fbff;}';
		echo '.text-right{text-align:right;}';
		echo '.title{font-size:16px;font-weight:bold;margin-bottom:4px;}';
		echo '.subtitle{color:#555;margin-bottom:8px;}';
		echo '.total-row td{font-weight:bold;background:#eef4ff;}';
		echo '</style></head><body>';
		echo '<div class="title">Detail Pembelian Barang Persediaan</div>';
		echo '<div class="subtitle">Bulan: ' . htmlspecialchars($bulan, ENT_QUOTES, 'UTF-8') . '</div>';
		echo '<table>';
		echo '<thead><tr>';
		echo '<th>No</th><th>Tanggal</th><th>SPOP</th><th>Uraian</th><th class="text-right">Jumlah</th><th>Satuan</th><th class="text-right">Harga Satuan</th><th class="text-right">Total</th><th>Supplier</th><th>Konsumen/Unit</th>';
		echo '</tr></thead><tbody>';
		$no = 0;
		foreach ($rows as $row) {
			$qty = persediaan_parse_angka(isset($row['jumlah']) ? $row['jumlah'] : 0);
			$harga = persediaan_parse_angka(isset($row['harga_satuan']) ? $row['harga_satuan'] : 0);
			$total = $qty * $harga;
			$grand_total += $total;
			$no++;
			echo '<tr>';
			echo '<td>' . $no . '</td>';
			echo '<td>' . htmlspecialchars(isset($row['tgl_po']) ? $row['tgl_po'] : '', ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td>' . htmlspecialchars(isset($row['spop']) ? $row['spop'] : '', ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td>' . htmlspecialchars(isset($row['uraian']) ? $row['uraian'] : '', ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td class="text-right">' . htmlspecialchars(number_format($qty, 0, ',', '.'), ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td>' . htmlspecialchars(isset($row['satuan']) ? $row['satuan'] : '', ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td class="text-right">' . htmlspecialchars(number_format($harga, 0, ',', '.'), ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td class="text-right">' . htmlspecialchars(number_format($total, 0, ',', '.'), ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td>' . htmlspecialchars(isset($row['supplier_nama']) ? $row['supplier_nama'] : '', ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td>' . htmlspecialchars(isset($row['konsumen']) ? $row['konsumen'] : '', ENT_QUOTES, 'UTF-8') . '</td>';
			echo '</tr>';
		}
		echo '</tbody>';
		echo '<tfoot><tr class="total-row">';
		echo '<td colspan="7" class="text-right">Grand Total</td>';
		echo '<td class="text-right">' . htmlspecialchars(number_format($grand_total, 0, ',', '.'), ENT_QUOTES, 'UTF-8') . '</td>';
		echo '<td colspan="2"></td>';
		echo '</tr></tfoot>';
		echo '</table>';
		echo '</body></html>';
		exit();
	}

	/**
	 * AJAX: detail pembelian jasa (tbl_pembelian_jasa) untuk bulan terpilih.
	 */
	public function ajax_rekap_detail_pembelian_jasa()
	{
		$this->load->helper('persediaan_display');
		try {
			$parsed = $this->parse_bulan_rekap_input();
			if (!$parsed['ok']) {
				persediaan_ajax_json_output($this, $parsed);
				return;
			}
			$bulan = $parsed['bulan'];
			$range = $this->get_bulan_date_range($bulan);

			if (!$this->db->table_exists('tbl_pembelian_jasa')) {
				persediaan_ajax_json_output($this, array('ok' => true, 'bulan' => $bulan, 'rows' => array(), 'grand_total' => 0, 'grand_total_tampil' => '0'));
				return;
			}

			$rows = $this->db->query(
				"SELECT `tgl_po`, `spop`, `uraian`, `jumlah`, `satuan`, `harga_satuan`, `supplier_nama`, `konsumen`
				 FROM `tbl_pembelian_jasa`
				 WHERE `tgl_po` BETWEEN ? AND ?
				 ORDER BY `tgl_po` ASC, `spop` ASC, `id` ASC",
				array($range['awal'], $range['akhir'])
			)->result();

			$out = array();
			$no = 1;
			$grand_total = 0;
			foreach ($rows as $r) {
				$qty   = persediaan_parse_angka(isset($r->jumlah) ? $r->jumlah : 0);
				$harga = persediaan_parse_angka(isset($r->harga_satuan) ? $r->harga_satuan : 0);
				$total = $qty * $harga;
				$grand_total += $total;
				$out[] = array(
					'no' => $no++,
					'tgl' => isset($r->tgl_po) ? $r->tgl_po : '',
					'spop' => isset($r->spop) ? $r->spop : '',
					'uraian' => isset($r->uraian) ? $r->uraian : '',
					'jumlah' => $qty,
					'jumlah_tampil' => number_format($qty, 0, ',', '.'),
					'satuan' => isset($r->satuan) ? $r->satuan : '',
					'harga_satuan' => $harga,
					'harga_satuan_tampil' => number_format($harga, 0, ',', '.'),
					'total' => $total,
					'total_tampil' => number_format($total, 0, ',', '.'),
					'supplier_nama' => isset($r->supplier_nama) ? $r->supplier_nama : '',
					'konsumen' => isset($r->konsumen) ? $r->konsumen : '',
				);
			}
			persediaan_ajax_json_output($this, array('ok' => true, 'bulan' => $bulan, 'rows' => $out, 'grand_total' => $grand_total, 'grand_total_tampil' => number_format($grand_total, 0, ',', '.')));
		} catch (Exception $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Gagal memuat detail pembelian jasa: ' . $e->getMessage()));
		} catch (Throwable $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Gagal memuat detail pembelian jasa: ' . $e->getMessage()));
		}
	}

	/**
	 * AJAX: detail penjualan barang (tbl_penjualan WHERE barang_jasa != 'jasa') untuk bulan terpilih.
	 */
	public function ajax_rekap_detail_penjualan_barang()
	{
		$this->load->helper('persediaan_display');
		try {
			$parsed = $this->parse_bulan_rekap_input();
			if (!$parsed['ok']) {
				persediaan_ajax_json_output($this, $parsed);
				return;
			}
			$bulan = $parsed['bulan'];
			$range = $this->get_bulan_date_range($bulan);

			if (!$this->db->table_exists('tbl_penjualan')) {
				persediaan_ajax_json_output($this, array('ok' => true, 'bulan' => $bulan, 'rows' => array(), 'grand_total' => 0, 'grand_total_tampil' => '0'));
				return;
			}

			$kolom_barang_jasa = $this->db->field_exists('barang_jasa', 'tbl_penjualan');
			if ($kolom_barang_jasa) {
				$rows = $this->db->query(
					"SELECT `tgl_jual`, `nmrkirim`, `nama_barang`, `jumlah`, `satuan`, `harga_satuan`, `konsumen_nama`
					 FROM `tbl_penjualan`
					 WHERE `tgl_jual` BETWEEN ? AND ? AND (`barang_jasa` IS NULL OR `barang_jasa` <> 'jasa')
					 ORDER BY `tgl_jual` ASC, `nmrkirim` ASC, `id` ASC",
					array($range['awal'], $range['akhir'])
				)->result();
			} else {
				$rows = $this->db->query(
					"SELECT `tgl_jual`, `nmrkirim`, `nama_barang`, `jumlah`, `satuan`, `harga_satuan`, `konsumen_nama`
					 FROM `tbl_penjualan`
					 WHERE `tgl_jual` BETWEEN ? AND ?
					 ORDER BY `tgl_jual` ASC, `nmrkirim` ASC, `id` ASC",
					array($range['awal'], $range['akhir'])
				)->result();
			}

			$out = array();
			$no = 1;
			$grand_total = 0;
			foreach ($rows as $r) {
				$qty   = persediaan_parse_angka(isset($r->jumlah) ? $r->jumlah : 0);
				$harga = persediaan_parse_angka(isset($r->harga_satuan) ? $r->harga_satuan : 0);
				$total = $qty * $harga;
				$grand_total += $total;
				$out[] = array(
					'no' => $no++,
					'tgl' => isset($r->tgl_jual) ? $r->tgl_jual : '',
					'nmrkirim' => isset($r->nmrkirim) ? $r->nmrkirim : '',
					'nama_barang' => isset($r->nama_barang) ? $r->nama_barang : '',
					'konsumen_nama' => isset($r->konsumen_nama) ? $r->konsumen_nama : '',
					'jumlah' => $qty,
					'jumlah_tampil' => number_format($qty, 0, ',', '.'),
					'satuan' => isset($r->satuan) ? $r->satuan : '',
					'harga_satuan' => $harga,
					'harga_satuan_tampil' => number_format($harga, 0, ',', '.'),
					'total' => $total,
					'total_tampil' => number_format($total, 0, ',', '.'),
				);
			}
			persediaan_ajax_json_output($this, array('ok' => true, 'bulan' => $bulan, 'rows' => $out, 'grand_total' => $grand_total, 'grand_total_tampil' => number_format($grand_total, 0, ',', '.')));
		} catch (Exception $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Gagal memuat detail penjualan barang: ' . $e->getMessage()));
		} catch (Throwable $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Gagal memuat detail penjualan barang: ' . $e->getMessage()));
		}
	}

	/**
	 * AJAX: detail penjualan jasa (tbl_penjualan WHERE barang_jasa = 'jasa') untuk bulan terpilih.
	 */
	public function ajax_rekap_detail_penjualan_jasa()
	{
		$this->load->helper('persediaan_display');
		try {
			$parsed = $this->parse_bulan_rekap_input();
			if (!$parsed['ok']) {
				persediaan_ajax_json_output($this, $parsed);
				return;
			}
			$bulan = $parsed['bulan'];
			$range = $this->get_bulan_date_range($bulan);

			if (!$this->db->table_exists('tbl_penjualan')) {
				persediaan_ajax_json_output($this, array('ok' => true, 'bulan' => $bulan, 'rows' => array(), 'grand_total' => 0, 'grand_total_tampil' => '0'));
				return;
			}

			$kolom_barang_jasa = $this->db->field_exists('barang_jasa', 'tbl_penjualan');
			if ($kolom_barang_jasa) {
				$rows = $this->db->query(
					"SELECT `tgl_jual`, `nmrkirim`, `nama_barang`, `jumlah`, `satuan`, `harga_satuan`, `konsumen_nama`
					 FROM `tbl_penjualan`
					 WHERE `tgl_jual` BETWEEN ? AND ? AND `barang_jasa` = 'jasa'
					 ORDER BY `tgl_jual` ASC, `nmrkirim` ASC, `id` ASC",
					array($range['awal'], $range['akhir'])
				)->result();
			} else {
				$rows = array();
			}

			$out = array();
			$no = 1;
			$grand_total = 0;
			foreach ($rows as $r) {
				$qty   = persediaan_parse_angka(isset($r->jumlah) ? $r->jumlah : 0);
				$harga = persediaan_parse_angka(isset($r->harga_satuan) ? $r->harga_satuan : 0);
				$total = $qty * $harga;
				$grand_total += $total;
				$out[] = array(
					'no' => $no++,
					'tgl' => isset($r->tgl_jual) ? $r->tgl_jual : '',
					'nmrkirim' => isset($r->nmrkirim) ? $r->nmrkirim : '',
					'nama_barang' => isset($r->nama_barang) ? $r->nama_barang : '',
					'konsumen_nama' => isset($r->konsumen_nama) ? $r->konsumen_nama : '',
					'jumlah' => $qty,
					'jumlah_tampil' => number_format($qty, 0, ',', '.'),
					'satuan' => isset($r->satuan) ? $r->satuan : '',
					'harga_satuan' => $harga,
					'harga_satuan_tampil' => number_format($harga, 0, ',', '.'),
					'total' => $total,
					'total_tampil' => number_format($total, 0, ',', '.'),
				);
			}
			persediaan_ajax_json_output($this, array('ok' => true, 'bulan' => $bulan, 'rows' => $out, 'grand_total' => $grand_total, 'grand_total_tampil' => number_format($grand_total, 0, ',', '.')));
		} catch (Exception $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Gagal memuat detail penjualan jasa: ' . $e->getMessage()));
		} catch (Throwable $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Gagal memuat detail penjualan jasa: ' . $e->getMessage()));
		}
	}

	public function ajax_rekap_detail_pembelian_jasa_serverside()
	{
		$this->load->helper('persediaan_display');
		try {
			$parsed = $this->parse_bulan_rekap_input();
			if (!$parsed['ok']) {
				$this->datatables_json_output(array(
					'draw' => 0,
					'recordsTotal' => 0,
					'recordsFiltered' => 0,
					'data' => array(),
					'ok' => false,
					'message' => isset($parsed['message']) ? $parsed['message'] : 'Bulan rekap tidak valid.',
				));
				return;
			}

			$bulan = $parsed['bulan'];
			$range = $this->get_bulan_date_range($bulan);
			if (!$this->db->table_exists('tbl_pembelian_jasa')) {
				$this->datatables_json_output(array('draw' => 0, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => array(), 'ok' => true, 'bulan' => $bulan, 'grand_total' => 0, 'grand_total_tampil' => '0'));
				return;
			}

			$rows = $this->db->query(
				"SELECT `tgl_po`, `spop`, `uraian`, `jumlah`, `satuan`, `harga_satuan`, `supplier_nama`, `konsumen`
				 FROM `tbl_pembelian_jasa`
				 WHERE `tgl_po` BETWEEN ? AND ?
				 ORDER BY `tgl_po` ASC, `spop` ASC, `id` ASC",
				array($range['awal'], $range['akhir'])
			)->result_array();

			$mapped = array();
			$grand_total = 0;
			foreach ($rows as $idx => $row) {
				$qty = persediaan_parse_angka(isset($row['jumlah']) ? $row['jumlah'] : 0);
				$harga = persediaan_parse_angka(isset($row['harga_satuan']) ? $row['harga_satuan'] : 0);
				$total = $qty * $harga;
				$grand_total += $total;
				$mapped[] = array(
					'no' => $idx + 1,
					'tgl' => isset($row['tgl_po']) ? $row['tgl_po'] : '',
					'spop' => isset($row['spop']) ? $row['spop'] : '',
					'uraian' => isset($row['uraian']) ? $row['uraian'] : '',
					'jumlah' => $qty,
					'jumlah_tampil' => number_format($qty, 0, ',', '.'),
					'satuan' => isset($row['satuan']) ? $row['satuan'] : '',
					'harga_satuan' => $harga,
					'harga_satuan_tampil' => number_format($harga, 0, ',', '.'),
					'total' => $total,
					'total_tampil' => number_format($total, 0, ',', '.'),
					'supplier_nama' => isset($row['supplier_nama']) ? $row['supplier_nama'] : '',
					'konsumen' => isset($row['konsumen']) ? $row['konsumen'] : '',
				);
			}

			$request = $this->get_datatables_request(1);
			$filtered = $this->datatables_slice_rows(
				$mapped,
				array('tgl', 'spop', 'uraian', 'jumlah_tampil', 'satuan', 'harga_satuan_tampil', 'total_tampil', 'supplier_nama', 'konsumen'),
				array(
					0 => array('field' => 'no', 'numeric' => true),
					1 => array('field' => 'tgl', 'numeric' => false),
					2 => array('field' => 'spop', 'numeric' => false),
					3 => array('field' => 'uraian', 'numeric' => false),
					4 => array('field' => 'jumlah', 'numeric' => true),
					5 => array('field' => 'satuan', 'numeric' => false),
					6 => array('field' => 'harga_satuan', 'numeric' => true),
					7 => array('field' => 'total', 'numeric' => true),
					8 => array('field' => 'supplier_nama', 'numeric' => false),
					9 => array('field' => 'konsumen', 'numeric' => false),
				),
				$request
			);

			$this->datatables_json_output(array(
				'draw' => $request['draw'],
				'recordsTotal' => $filtered['recordsTotal'],
				'recordsFiltered' => $filtered['recordsFiltered'],
				'data' => array_values($filtered['rows']),
				'ok' => true,
				'bulan' => $bulan,
				'grand_total' => $grand_total,
				'grand_total_tampil' => number_format($grand_total, 0, ',', '.'),
			));
		} catch (Exception $e) {
			$this->datatables_json_output(array('draw' => 0, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => array(), 'ok' => false, 'message' => 'Gagal memuat detail pembelian jasa: ' . $e->getMessage()));
		} catch (Throwable $e) {
			$this->datatables_json_output(array('draw' => 0, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => array(), 'ok' => false, 'message' => 'Gagal memuat detail pembelian jasa: ' . $e->getMessage()));
		}
	}

	public function excel_rekap_detail_pembelian_jasa()
	{
		$bulan = trim((string) $this->input->post('bulan_persediaan', TRUE));
		if ($bulan === '') {
			$bulan = date('Y-m');
		}

		$this->load->helper('persediaan_display');
		$parsed = $this->parse_bulan_rekap_input();
		if (!$parsed['ok']) {
			show_error(isset($parsed['message']) ? $parsed['message'] : 'Bulan rekap tidak valid.', 400);
			return;
		}

		$bulan = $parsed['bulan'];
		$range = $this->get_bulan_date_range($bulan);
		$rows = array();
		$grand_total = 0;
		if ($this->db->table_exists('tbl_pembelian_jasa')) {
			$rows = $this->db->query(
				"SELECT `tgl_po`, `spop`, `uraian`, `jumlah`, `satuan`, `harga_satuan`, `supplier_nama`, `konsumen`
				 FROM `tbl_pembelian_jasa`
				 WHERE `tgl_po` BETWEEN ? AND ?
				 ORDER BY `tgl_po` ASC, `spop` ASC, `id` ASC",
				array($range['awal'], $range['akhir'])
			)->result_array();
		}

		$waktu_klik = date('Y-m-d_H-i-s');
		$namaFile = 'Detail_Pembelian_Jasa_Persediaan_' . $bulan . '_' . $waktu_klik . '.xls';
		header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
		header('Content-Disposition: attachment; filename="' . $namaFile . '"');
		header('Pragma: no-cache');
		header('Expires: 0');

		echo '<html><head><meta charset="UTF-8"><style>';
		echo 'body{font-family:Arial,sans-serif;font-size:12px;color:#222;}';
		echo 'table{border-collapse:collapse;width:100%;margin-top:10px;}';
		echo 'th,td{border:1px solid #c9d2dc;padding:6px 8px;vertical-align:top;}';
		echo 'th{background:#0d6efd;color:#fff;font-weight:bold;text-align:center;}';
		echo 'tr:nth-child(even) td{background:#f8fbff;}';
		echo '.text-right{text-align:right;}';
		echo '.title{font-size:16px;font-weight:bold;margin-bottom:4px;}';
		echo '.subtitle{color:#555;margin-bottom:8px;}';
		echo '.total-row td{font-weight:bold;background:#eef4ff;}';
		echo '</style></head><body>';
		echo '<div class="title">Detail Pembelian Jasa Persediaan</div>';
		echo '<div class="subtitle">Bulan: ' . htmlspecialchars($bulan, ENT_QUOTES, 'UTF-8') . '</div>';
		echo '<table>';
		echo '<thead><tr>';
		echo '<th>No</th><th>Tanggal</th><th>SPOP</th><th>Uraian</th><th class="text-right">Jumlah</th><th>Satuan</th><th class="text-right">Harga Satuan</th><th class="text-right">Total</th><th>Supplier</th><th>Konsumen/Unit</th>';
		echo '</tr></thead><tbody>';
		$no = 0;
		foreach ($rows as $row) {
			$qty = persediaan_parse_angka(isset($row['jumlah']) ? $row['jumlah'] : 0);
			$harga = persediaan_parse_angka(isset($row['harga_satuan']) ? $row['harga_satuan'] : 0);
			$total = $qty * $harga;
			$grand_total += $total;
			$no++;
			echo '<tr>';
			echo '<td>' . $no . '</td>';
			echo '<td>' . htmlspecialchars(isset($row['tgl_po']) ? $row['tgl_po'] : '', ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td>' . htmlspecialchars(isset($row['spop']) ? $row['spop'] : '', ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td>' . htmlspecialchars(isset($row['uraian']) ? $row['uraian'] : '', ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td class="text-right">' . htmlspecialchars(number_format($qty, 0, ',', '.'), ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td>' . htmlspecialchars(isset($row['satuan']) ? $row['satuan'] : '', ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td class="text-right">' . htmlspecialchars(number_format($harga, 0, ',', '.'), ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td class="text-right">' . htmlspecialchars(number_format($total, 0, ',', '.'), ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td>' . htmlspecialchars(isset($row['supplier_nama']) ? $row['supplier_nama'] : '', ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td>' . htmlspecialchars(isset($row['konsumen']) ? $row['konsumen'] : '', ENT_QUOTES, 'UTF-8') . '</td>';
			echo '</tr>';
		}
		echo '</tbody>';
		echo '<tfoot><tr class="total-row">';
		echo '<td colspan="7" class="text-right">Grand Total</td>';
		echo '<td class="text-right">' . htmlspecialchars(number_format($grand_total, 0, ',', '.'), ENT_QUOTES, 'UTF-8') . '</td>';
		echo '<td colspan="2"></td>';
		echo '</tr></tfoot>';
		echo '</table>';
		echo '</body></html>';
		exit();
	}

	public function ajax_rekap_detail_penjualan_barang_serverside()
	{
		$this->load->helper('persediaan_display');
		try {
			$parsed = $this->parse_bulan_rekap_input();
			if (!$parsed['ok']) {
				$this->datatables_json_output(array(
					'draw' => 0,
					'recordsTotal' => 0,
					'recordsFiltered' => 0,
					'data' => array(),
					'ok' => false,
					'message' => isset($parsed['message']) ? $parsed['message'] : 'Bulan rekap tidak valid.',
				));
				return;
			}

			$bulan = $parsed['bulan'];
			$range = $this->get_bulan_date_range($bulan);
			if (!$this->db->table_exists('tbl_penjualan')) {
				$this->datatables_json_output(array('draw' => 0, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => array(), 'ok' => true, 'bulan' => $bulan, 'grand_total' => 0, 'grand_total_tampil' => '0'));
				return;
			}

			$kolom_barang_jasa = $this->db->field_exists('barang_jasa', 'tbl_penjualan');
			if ($kolom_barang_jasa) {
				$rows = $this->db->query(
					"SELECT `tgl_jual`, `nmrkirim`, `nama_barang`, `jumlah`, `satuan`, `harga_satuan`, `konsumen_nama`
					 FROM `tbl_penjualan`
					 WHERE `tgl_jual` BETWEEN ? AND ? AND (`barang_jasa` IS NULL OR `barang_jasa` <> 'jasa')
					 ORDER BY `tgl_jual` ASC, `nmrkirim` ASC, `id` ASC",
					array($range['awal'], $range['akhir'])
				)->result_array();
			} else {
				$rows = $this->db->query(
					"SELECT `tgl_jual`, `nmrkirim`, `nama_barang`, `jumlah`, `satuan`, `harga_satuan`, `konsumen_nama`
					 FROM `tbl_penjualan`
					 WHERE `tgl_jual` BETWEEN ? AND ?
					 ORDER BY `tgl_jual` ASC, `nmrkirim` ASC, `id` ASC",
					array($range['awal'], $range['akhir'])
				)->result_array();
			}

			$mapped = array();
			$grand_total = 0;
			foreach ($rows as $idx => $row) {
				$qty = persediaan_parse_angka(isset($row['jumlah']) ? $row['jumlah'] : 0);
				$harga = persediaan_parse_angka(isset($row['harga_satuan']) ? $row['harga_satuan'] : 0);
				$total = $qty * $harga;
				$grand_total += $total;
				$mapped[] = array(
					'no' => $idx + 1,
					'tgl' => isset($row['tgl_jual']) ? $row['tgl_jual'] : '',
					'nmrkirim' => isset($row['nmrkirim']) ? $row['nmrkirim'] : '',
					'nama_barang' => isset($row['nama_barang']) ? $row['nama_barang'] : '',
					'konsumen_nama' => isset($row['konsumen_nama']) ? $row['konsumen_nama'] : '',
					'jumlah' => $qty,
					'jumlah_tampil' => number_format($qty, 0, ',', '.'),
					'satuan' => isset($row['satuan']) ? $row['satuan'] : '',
					'harga_satuan' => $harga,
					'harga_satuan_tampil' => number_format($harga, 0, ',', '.'),
					'total' => $total,
					'total_tampil' => number_format($total, 0, ',', '.'),
				);
			}

			$request = $this->get_datatables_request(1);
			$filtered = $this->datatables_slice_rows(
				$mapped,
				array('tgl', 'nmrkirim', 'nama_barang', 'konsumen_nama', 'jumlah_tampil', 'satuan', 'harga_satuan_tampil', 'total_tampil'),
				array(
					0 => array('field' => 'no', 'numeric' => true),
					1 => array('field' => 'tgl', 'numeric' => false),
					2 => array('field' => 'nmrkirim', 'numeric' => false),
					3 => array('field' => 'nama_barang', 'numeric' => false),
					4 => array('field' => 'konsumen_nama', 'numeric' => false),
					5 => array('field' => 'jumlah', 'numeric' => true),
					6 => array('field' => 'satuan', 'numeric' => false),
					7 => array('field' => 'harga_satuan', 'numeric' => true),
					8 => array('field' => 'total', 'numeric' => true),
				),
				$request
			);

			$this->datatables_json_output(array(
				'draw' => $request['draw'],
				'recordsTotal' => $filtered['recordsTotal'],
				'recordsFiltered' => $filtered['recordsFiltered'],
				'data' => array_values($filtered['rows']),
				'ok' => true,
				'bulan' => $bulan,
				'grand_total' => $grand_total,
				'grand_total_tampil' => number_format($grand_total, 0, ',', '.'),
			));
		} catch (Exception $e) {
			$this->datatables_json_output(array('draw' => 0, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => array(), 'ok' => false, 'message' => 'Gagal memuat detail penjualan barang: ' . $e->getMessage()));
		} catch (Throwable $e) {
			$this->datatables_json_output(array('draw' => 0, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => array(), 'ok' => false, 'message' => 'Gagal memuat detail penjualan barang: ' . $e->getMessage()));
		}
	}

	public function excel_rekap_detail_penjualan_barang()
	{
		$bulan = trim((string) $this->input->post('bulan_persediaan', TRUE));
		if ($bulan === '') {
			$bulan = date('Y-m');
		}

		$this->load->helper('persediaan_display');
		$parsed = $this->parse_bulan_rekap_input();
		if (!$parsed['ok']) {
			show_error(isset($parsed['message']) ? $parsed['message'] : 'Bulan rekap tidak valid.', 400);
			return;
		}

		$bulan = $parsed['bulan'];
		$range = $this->get_bulan_date_range($bulan);
		$rows = array();
		$grand_total = 0;
		if ($this->db->table_exists('tbl_penjualan')) {
			$kolom_barang_jasa = $this->db->field_exists('barang_jasa', 'tbl_penjualan');
			if ($kolom_barang_jasa) {
				$rows = $this->db->query(
					"SELECT `tgl_jual`, `nmrkirim`, `nama_barang`, `jumlah`, `satuan`, `harga_satuan`, `konsumen_nama`
					 FROM `tbl_penjualan`
					 WHERE `tgl_jual` BETWEEN ? AND ? AND (`barang_jasa` IS NULL OR `barang_jasa` <> 'jasa')
					 ORDER BY `tgl_jual` ASC, `nmrkirim` ASC, `id` ASC",
					array($range['awal'], $range['akhir'])
				)->result_array();
			} else {
				$rows = $this->db->query(
					"SELECT `tgl_jual`, `nmrkirim`, `nama_barang`, `jumlah`, `satuan`, `harga_satuan`, `konsumen_nama`
					 FROM `tbl_penjualan`
					 WHERE `tgl_jual` BETWEEN ? AND ?
					 ORDER BY `tgl_jual` ASC, `nmrkirim` ASC, `id` ASC",
					array($range['awal'], $range['akhir'])
				)->result_array();
			}
		}

		$waktu_klik = date('Y-m-d_H-i-s');
		$namaFile = 'Detail_Penjualan_Barang_Persediaan_' . $bulan . '_' . $waktu_klik . '.xls';
		header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
		header('Content-Disposition: attachment; filename="' . $namaFile . '"');
		header('Pragma: no-cache');
		header('Expires: 0');

		echo '<html><head><meta charset="UTF-8"><style>';
		echo 'body{font-family:Arial,sans-serif;font-size:12px;color:#222;}';
		echo 'table{border-collapse:collapse;width:100%;margin-top:10px;}';
		echo 'th,td{border:1px solid #c9d2dc;padding:6px 8px;vertical-align:top;}';
		echo 'th{background:#0d6efd;color:#fff;font-weight:bold;text-align:center;}';
		echo 'tr:nth-child(even) td{background:#f8fbff;}';
		echo '.text-right{text-align:right;}';
		echo '.title{font-size:16px;font-weight:bold;margin-bottom:4px;}';
		echo '.subtitle{color:#555;margin-bottom:8px;}';
		echo '.total-row td{font-weight:bold;background:#eef4ff;}';
		echo '</style></head><body>';
		echo '<div class="title">Detail Penjualan Barang Persediaan</div>';
		echo '<div class="subtitle">Bulan: ' . htmlspecialchars($bulan, ENT_QUOTES, 'UTF-8') . '</div>';
		echo '<table>';
		echo '<thead><tr>';
		echo '<th>No</th><th>Tanggal</th><th>No. Kirim</th><th>Nama Barang</th><th>Konsumen</th><th class="text-right">Jumlah</th><th>Satuan</th><th class="text-right">Harga Satuan</th><th class="text-right">Total</th>';
		echo '</tr></thead><tbody>';
		$no = 0;
		foreach ($rows as $row) {
			$qty = persediaan_parse_angka(isset($row['jumlah']) ? $row['jumlah'] : 0);
			$harga = persediaan_parse_angka(isset($row['harga_satuan']) ? $row['harga_satuan'] : 0);
			$total = $qty * $harga;
			$grand_total += $total;
			$no++;
			echo '<tr>';
			echo '<td>' . $no . '</td>';
			echo '<td>' . htmlspecialchars(isset($row['tgl_jual']) ? $row['tgl_jual'] : '', ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td>' . htmlspecialchars(isset($row['nmrkirim']) ? $row['nmrkirim'] : '', ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td>' . htmlspecialchars(isset($row['nama_barang']) ? $row['nama_barang'] : '', ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td>' . htmlspecialchars(isset($row['konsumen_nama']) ? $row['konsumen_nama'] : '', ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td class="text-right">' . htmlspecialchars(number_format($qty, 0, ',', '.'), ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td>' . htmlspecialchars(isset($row['satuan']) ? $row['satuan'] : '', ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td class="text-right">' . htmlspecialchars(number_format($harga, 0, ',', '.'), ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td class="text-right">' . htmlspecialchars(number_format($total, 0, ',', '.'), ENT_QUOTES, 'UTF-8') . '</td>';
			echo '</tr>';
		}
		echo '</tbody>';
		echo '<tfoot><tr class="total-row">';
		echo '<td colspan="8" class="text-right">Grand Total</td>';
		echo '<td class="text-right">' . htmlspecialchars(number_format($grand_total, 0, ',', '.'), ENT_QUOTES, 'UTF-8') . '</td>';
		echo '</tr></tfoot>';
		echo '</table>';
		echo '</body></html>';
		exit();
	}

	public function ajax_rekap_detail_penjualan_jasa_serverside()
	{
		$this->load->helper('persediaan_display');
		try {
			$parsed = $this->parse_bulan_rekap_input();
			if (!$parsed['ok']) {
				$this->datatables_json_output(array(
					'draw' => 0,
					'recordsTotal' => 0,
					'recordsFiltered' => 0,
					'data' => array(),
					'ok' => false,
					'message' => isset($parsed['message']) ? $parsed['message'] : 'Bulan rekap tidak valid.',
				));
				return;
			}

			$bulan = $parsed['bulan'];
			$range = $this->get_bulan_date_range($bulan);
			if (!$this->db->table_exists('tbl_penjualan')) {
				$this->datatables_json_output(array('draw' => 0, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => array(), 'ok' => true, 'bulan' => $bulan, 'grand_total' => 0, 'grand_total_tampil' => '0'));
				return;
			}

			$kolom_barang_jasa = $this->db->field_exists('barang_jasa', 'tbl_penjualan');
			if ($kolom_barang_jasa) {
				$rows = $this->db->query(
					"SELECT `tgl_jual`, `nmrkirim`, `nama_barang`, `jumlah`, `satuan`, `harga_satuan`, `konsumen_nama`
					 FROM `tbl_penjualan`
					 WHERE `tgl_jual` BETWEEN ? AND ? AND `barang_jasa` = 'jasa'
					 ORDER BY `tgl_jual` ASC, `nmrkirim` ASC, `id` ASC",
					array($range['awal'], $range['akhir'])
				)->result_array();
			} else {
				$rows = array();
			}

			$mapped = array();
			$grand_total = 0;
			foreach ($rows as $idx => $row) {
				$qty = persediaan_parse_angka(isset($row['jumlah']) ? $row['jumlah'] : 0);
				$harga = persediaan_parse_angka(isset($row['harga_satuan']) ? $row['harga_satuan'] : 0);
				$total = $qty * $harga;
				$grand_total += $total;
				$mapped[] = array(
					'no' => $idx + 1,
					'tgl' => isset($row['tgl_jual']) ? $row['tgl_jual'] : '',
					'nmrkirim' => isset($row['nmrkirim']) ? $row['nmrkirim'] : '',
					'nama_barang' => isset($row['nama_barang']) ? $row['nama_barang'] : '',
					'konsumen_nama' => isset($row['konsumen_nama']) ? $row['konsumen_nama'] : '',
					'jumlah' => $qty,
					'jumlah_tampil' => number_format($qty, 0, ',', '.'),
					'satuan' => isset($row['satuan']) ? $row['satuan'] : '',
					'harga_satuan' => $harga,
					'harga_satuan_tampil' => number_format($harga, 0, ',', '.'),
					'total' => $total,
					'total_tampil' => number_format($total, 0, ',', '.'),
				);
			}

			$request = $this->get_datatables_request(1);
			$filtered = $this->datatables_slice_rows(
				$mapped,
				array('tgl', 'nmrkirim', 'nama_barang', 'konsumen_nama', 'jumlah_tampil', 'satuan', 'harga_satuan_tampil', 'total_tampil'),
				array(
					0 => array('field' => 'no', 'numeric' => true),
					1 => array('field' => 'tgl', 'numeric' => false),
					2 => array('field' => 'nmrkirim', 'numeric' => false),
					3 => array('field' => 'nama_barang', 'numeric' => false),
					4 => array('field' => 'konsumen_nama', 'numeric' => false),
					5 => array('field' => 'jumlah', 'numeric' => true),
					6 => array('field' => 'satuan', 'numeric' => false),
					7 => array('field' => 'harga_satuan', 'numeric' => true),
					8 => array('field' => 'total', 'numeric' => true),
				),
				$request
			);

			$this->datatables_json_output(array(
				'draw' => $request['draw'],
				'recordsTotal' => $filtered['recordsTotal'],
				'recordsFiltered' => $filtered['recordsFiltered'],
				'data' => array_values($filtered['rows']),
				'ok' => true,
				'bulan' => $bulan,
				'grand_total' => $grand_total,
				'grand_total_tampil' => number_format($grand_total, 0, ',', '.'),
			));
		} catch (Exception $e) {
			$this->datatables_json_output(array('draw' => 0, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => array(), 'ok' => false, 'message' => 'Gagal memuat detail penjualan jasa: ' . $e->getMessage()));
		} catch (Throwable $e) {
			$this->datatables_json_output(array('draw' => 0, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => array(), 'ok' => false, 'message' => 'Gagal memuat detail penjualan jasa: ' . $e->getMessage()));
		}
	}

	public function excel_rekap_detail_penjualan_jasa()
	{
		$bulan = trim((string) $this->input->post('bulan_persediaan', TRUE));
		if ($bulan === '') {
			$bulan = date('Y-m');
		}

		$this->load->helper('persediaan_display');
		$parsed = $this->parse_bulan_rekap_input();
		if (!$parsed['ok']) {
			show_error(isset($parsed['message']) ? $parsed['message'] : 'Bulan rekap tidak valid.', 400);
			return;
		}

		$bulan = $parsed['bulan'];
		$range = $this->get_bulan_date_range($bulan);
		$rows = array();
		$grand_total = 0;
		if ($this->db->table_exists('tbl_penjualan') && $this->db->field_exists('barang_jasa', 'tbl_penjualan')) {
			$rows = $this->db->query(
				"SELECT `tgl_jual`, `nmrkirim`, `nama_barang`, `jumlah`, `satuan`, `harga_satuan`, `konsumen_nama`
				 FROM `tbl_penjualan`
				 WHERE `tgl_jual` BETWEEN ? AND ? AND `barang_jasa` = 'jasa'
				 ORDER BY `tgl_jual` ASC, `nmrkirim` ASC, `id` ASC",
				array($range['awal'], $range['akhir'])
			)->result_array();
		}

		$waktu_klik = date('Y-m-d_H-i-s');
		$namaFile = 'Detail_Penjualan_Jasa_Persediaan_' . $bulan . '_' . $waktu_klik . '.xls';
		header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
		header('Content-Disposition: attachment; filename="' . $namaFile . '"');
		header('Pragma: no-cache');
		header('Expires: 0');

		echo '<html><head><meta charset="UTF-8"><style>';
		echo 'body{font-family:Arial,sans-serif;font-size:12px;color:#222;}';
		echo 'table{border-collapse:collapse;width:100%;margin-top:10px;}';
		echo 'th,td{border:1px solid #c9d2dc;padding:6px 8px;vertical-align:top;}';
		echo 'th{background:#0d6efd;color:#fff;font-weight:bold;text-align:center;}';
		echo 'tr:nth-child(even) td{background:#f8fbff;}';
		echo '.text-right{text-align:right;}';
		echo '.title{font-size:16px;font-weight:bold;margin-bottom:4px;}';
		echo '.subtitle{color:#555;margin-bottom:8px;}';
		echo '.total-row td{font-weight:bold;background:#eef4ff;}';
		echo '</style></head><body>';
		echo '<div class="title">Detail Penjualan Jasa Persediaan</div>';
		echo '<div class="subtitle">Bulan: ' . htmlspecialchars($bulan, ENT_QUOTES, 'UTF-8') . '</div>';
		echo '<table>';
		echo '<thead><tr>';
		echo '<th>No</th><th>Tanggal</th><th>No. Kirim</th><th>Nama Barang/Jasa</th><th>Konsumen</th><th class="text-right">Jumlah</th><th>Satuan</th><th class="text-right">Harga Satuan</th><th class="text-right">Total</th>';
		echo '</tr></thead><tbody>';
		$no = 0;
		foreach ($rows as $row) {
			$qty = persediaan_parse_angka(isset($row['jumlah']) ? $row['jumlah'] : 0);
			$harga = persediaan_parse_angka(isset($row['harga_satuan']) ? $row['harga_satuan'] : 0);
			$total = $qty * $harga;
			$grand_total += $total;
			$no++;
			echo '<tr>';
			echo '<td>' . $no . '</td>';
			echo '<td>' . htmlspecialchars(isset($row['tgl_jual']) ? $row['tgl_jual'] : '', ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td>' . htmlspecialchars(isset($row['nmrkirim']) ? $row['nmrkirim'] : '', ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td>' . htmlspecialchars(isset($row['nama_barang']) ? $row['nama_barang'] : '', ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td>' . htmlspecialchars(isset($row['konsumen_nama']) ? $row['konsumen_nama'] : '', ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td class="text-right">' . htmlspecialchars(number_format($qty, 0, ',', '.'), ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td>' . htmlspecialchars(isset($row['satuan']) ? $row['satuan'] : '', ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td class="text-right">' . htmlspecialchars(number_format($harga, 0, ',', '.'), ENT_QUOTES, 'UTF-8') . '</td>';
			echo '<td class="text-right">' . htmlspecialchars(number_format($total, 0, ',', '.'), ENT_QUOTES, 'UTF-8') . '</td>';
			echo '</tr>';
		}
		echo '</tbody>';
		echo '<tfoot><tr class="total-row">';
		echo '<td colspan="8" class="text-right">Grand Total</td>';
		echo '<td class="text-right">' . htmlspecialchars(number_format($grand_total, 0, ',', '.'), ENT_QUOTES, 'UTF-8') . '</td>';
		echo '</tr></tfoot>';
		echo '</table>';
		echo '</body></html>';
		exit();
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
			'Sediaan Awal',
			'Pembelian PU',
			'Pembelian Cetak',
			'Pembelian Grafikita',
			'TUJ',
			'Sediaan Akhir',
			'BPP',
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
		@ini_set('memory_limit', '1024M');
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
			$limit = persediaan_generate_recalculate_normalize_batch_limit(
				(int) $this->input->get_post('limit', TRUE)
			);
			$start = ($this->input->get_post('start', TRUE) === '1');

			$db_debug = $this->db->db_debug;
			$this->db->db_debug = false;

			$result = persediaan_generate_recalculate_batch($this, $bulan, $offset, $limit, $start);
			$result = persediaan_generate_recalculate_batch_compact_items($result, true);

			$this->db->db_debug = $db_debug;

			persediaan_ajax_json_output($this, $result);
		} catch (Exception $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Error: ' . $e->getMessage()));
		} catch (Throwable $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Error: ' . $e->getMessage()));
		}
	}

	/**
	 * Fase awal Generate & Recalculate (untuk verifikasi):
	 * 1) hapus persediaan bulan/tahun terpilih (tanggal_beli)
	 * 2) perbaiki total_10 bulan sebelumnya, lalu copy record total_10 > 0 ke bulan target
	 * 3) kembalikan data bulan sebelumnya untuk DataTable
	 */
	public function ajax_generate_copy_bulan_sebelumnya()
	{
		@set_time_limit(0);
		@ini_set('memory_limit', '1024M');
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
				persediaan_ajax_json_output($this, array(
					'ok' => false,
					'message' => 'Format bulan tidak valid (YYYY-MM).',
				));
				return;
			}

			persediaan_history_generate_ensure_tables($this);
			generate_hasil_datatable_ensure_tables($this);

			$db_debug = $this->db->db_debug;
			$this->db->db_debug = false;
			$result = $this->proses_generate_copy_bulan_sebelumnya($bulan);
			$this->db->db_debug = $db_debug;

			persediaan_ajax_json_output($this, $result);
		} catch (Exception $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Error: ' . $e->getMessage()));
		} catch (Throwable $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Error: ' . $e->getMessage()));
		}
	}

	public function ajax_generate_penjualan_referensi_list()
	{
		$this->load->helper(array('pembelian_persediaan', 'persediaan_display'));

		if (!$this->persediaan_user_can_generate()) {
			persediaan_ajax_json_output($this, array(
				'ok' => false,
				'message' => $this->persediaan_restricted_access_message('Referensi penjualan'),
			));
			return;
		}

		$bulan = trim((string) $this->input->get_post('bulan', TRUE));
		if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).'));
			return;
		}

		$ts = strtotime($bulan . '-01');
		if ($ts === false) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Bulan tidak valid.'));
			return;
		}
		$tahun = (int) date('Y', $ts);
		$bulan_num = (int) date('m', $ts);

		$rows = $this->db->query(
			"SELECT * FROM `persediaan`
			 WHERE YEAR(`tanggal_beli`) = ? AND MONTH(`tanggal_beli`) = ?
			 ORDER BY `namabarang` ASC, `satuan` ASC, `id` ASC",
			array($tahun, $bulan_num)
		)->result();

		$out = array();
		foreach ($rows as $r) {
			$out[] = array(
				'id' => (int) $r->id,
				'namabarang' => isset($r->namabarang) ? (string) $r->namabarang : '',
				'satuan' => isset($r->satuan) ? (string) $r->satuan : '',
				'hpp' => isset($r->hpp) ? (string) $r->hpp : '',
				'sa' => isset($r->sa) ? (string) $r->sa : '',
				'beli' => isset($r->beli) ? (string) $r->beli : '',
				'penjualan' => isset($r->penjualan) ? (string) $r->penjualan : '',
				'total_10' => isset($r->total_10) ? (string) $r->total_10 : '',
				'uuid_persediaan' => isset($r->uuid_persediaan) ? (string) $r->uuid_persediaan : '',
			);
		}

		persediaan_ajax_json_output($this, array(
			'ok' => true,
			'rows' => $out,
			'count' => count($out),
			'bulan' => $bulan,
		));
	}

	public function ajax_generate_penjualan_refered()
	{
		$this->load->helper(array('pembelian_persediaan', 'persediaan_display'));

		if (!$this->persediaan_user_can_generate()) {
			persediaan_ajax_json_output($this, array(
				'ok' => false,
				'message' => $this->persediaan_restricted_access_message('Referensi penjualan'),
			));
			return;
		}

		if (strtolower($this->input->method()) !== 'post') {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Method tidak valid.'));
			return;
		}

		$bulan = trim((string) $this->input->post('bulan', TRUE));
		if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).'));
			return;
		}

		$id_penjualan = (int) $this->input->post('id_penjualan', TRUE);
		$id_persediaan = (int) $this->input->post('id_persediaan', TRUE);
		$result = persediaan_gen_v2_apply_penjualan_ke_persediaan($this, $bulan, $id_penjualan, $id_persediaan);
		persediaan_ajax_json_output($this, $result);
	}

	/**
	 * Hapus bulan target → (opsional) perbaiki total_10 sumber → copy record total_10 > 0.
	 *
	 * Khusus target Januari 2026 (sumber Desember 2025 = data dasar):
	 * - TIDAK menghitung ulang total_10 dengan rumus sa+beli-(penjualan+pecah+bahan)
	 * - Copy paste apa adanya jika total_10 > 0
	 * - tanggal_beli pada record baru = tanggal 1 bulan/tahun terpilih (2026-01-01)
	 * - field tanggal (tanggal_persediaan) = tanggal 1 bulan/tahun terpilih
	 *
	 * Untuk target setelah Januari 2026: tetap koreksi total_10 dengan rumus, lalu copy.
	 */
	private function proses_generate_copy_bulan_sebelumnya($bulan_target)
	{
		$ts_target = strtotime($bulan_target . '-01');
		if ($ts_target === false) {
			return array('ok' => false, 'message' => 'Bulan target tidak valid.');
		}

		$tanggal_beli_target = date('Y-m-01', $ts_target);
		$tahun_target = (int) date('Y', $ts_target);
		$bulan_target_num = (int) date('m', $ts_target);
		$ts_sumber = strtotime('-1 month', $ts_target);
		$tanggal_beli_sumber = date('Y-m-01', $ts_sumber);
		$tahun_sumber = (int) date('Y', $ts_sumber);
		$bulan_sumber_num = (int) date('m', $ts_sumber);
		$bulan_sumber_key = date('Y-m', $ts_sumber);
		$tanggal_tampil_target = date('d/m/Y', $ts_target);

		// Mode data dasar: generate Januari 2026 dari Desember 2025
		$is_mode_data_dasar_des2025 = ($bulan_target === '2026-01');

		$nama_bulan = array(
			1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
			5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
			9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
		);
		$label_sumber = (isset($nama_bulan[$bulan_sumber_num]) ? $nama_bulan[$bulan_sumber_num] : $bulan_sumber_num)
			. ' ' . $tahun_sumber;
		$label_target = (isset($nama_bulan[$bulan_target_num]) ? $nama_bulan[$bulan_target_num] : $bulan_target_num)
			. ' ' . $tahun_target;

		// 1) Hapus data persediaan bulan & tahun terpilih (berdasarkan tanggal_beli)
		$deleted = $this->generate_hapus_persediaan_bulan_tahun($tahun_target, $bulan_target_num);

		// Ambil semua record bulan sebelumnya (referensi tanggal_beli)
		$rows_sumber = $this->db->query(
			"SELECT * FROM `persediaan`
			WHERE YEAR(`tanggal_beli`) = ?
			  AND MONTH(`tanggal_beli`) = ?
			ORDER BY `id` ASC",
			array($tahun_sumber, $bulan_sumber_num)
		)->result();

		if (empty($rows_sumber)) {
			return array(
				'ok' => false,
				'message' => 'Tidak ada data persediaan di bulan sebelumnya (' . $label_sumber . ').',
				'deleted' => $deleted,
				'bulan_target' => $bulan_target,
				'bulan_sumber' => $bulan_sumber_key,
				'label_sumber' => $label_sumber,
				'label_target' => $label_target,
				'mode_data_dasar' => $is_mode_data_dasar_des2025,
				'rows' => array(),
			);
		}

		$row_max = $this->db->query("SELECT MAX(`id`) AS max_id FROM `persediaan`")->row();
		$next_id = ($row_max && $row_max->max_id) ? ((int) $row_max->max_id + 1) : 1;
		$fields = $this->db->list_fields('persediaan');
		$has_tgl_persediaan = in_array('tgl_persediaan', $fields, true);

		$updated_total10 = 0;
		$copied = 0;
		$skipped = 0;
		$rows_preview = array();
		$no = 0;

		foreach ($rows_sumber as $row) {
			$total_10_db = $this->parse_angka_persediaan(isset($row->total_10) ? $row->total_10 : 0);
			$ket_fix = '';
			$total_10_hitung = $total_10_db;
			$total_10_final = $total_10_db;

			if ($is_mode_data_dasar_des2025) {
				// Desember 2025 = data dasar: JANGAN hitung ulang / update total_10.
				// Copy paste apa adanya jika total_10 > 0.
				$total_10_final = $total_10_db;
				$total_10_hitung = $total_10_db;
				$ket_fix = 'mode data dasar DES 2025: copy apa adanya (tanpa rumus)';
			} else {
				$sa = $this->parse_angka_persediaan(isset($row->sa) ? $row->sa : 0);
				$beli = $this->parse_angka_persediaan(isset($row->beli) ? $row->beli : 0);
				$penjualan = $this->parse_angka_persediaan(isset($row->penjualan) ? $row->penjualan : 0);
				$pecah_satuan = $this->parse_angka_persediaan(isset($row->pecah_satuan) ? $row->pecah_satuan : 0);
				$bahan_produksi = $this->parse_angka_persediaan(isset($row->bahan_produksi) ? $row->bahan_produksi : 0);

				// total_10 = sa + beli - (penjualan + pecah_satuan + bahan_produksi)
				$total_10_hitung = $sa + $beli - ($penjualan + $pecah_satuan + $bahan_produksi);
				$total_10_final = $total_10_db;

				if ($total_10_hitung > 0) {
					if (abs($total_10_hitung - $total_10_db) > 0.0001) {
						$total_10_tampil = $this->format_angka_persediaan($total_10_hitung);
						$this->db->where('id', (int) $row->id);
						$this->db->update('persediaan', array('total_10' => $total_10_tampil));
						$row->total_10 = $total_10_tampil;
						$total_10_final = $total_10_hitung;
						$updated_total10++;
						$ket_fix = 'total_10 dikoreksi: ' . $this->format_angka_persediaan($total_10_db)
							. ' → ' . $total_10_tampil;
					} else {
						$total_10_final = $total_10_hitung;
					}
				}
			}

			$no++;
			$will_copy = ($total_10_final > 0);
			$rows_preview[] = array(
				'no' => $no,
				'id' => isset($row->id) ? (int) $row->id : 0,
				'uuid_persediaan' => isset($row->uuid_persediaan) ? (string) $row->uuid_persediaan : '',
				'namabarang' => isset($row->namabarang) ? (string) $row->namabarang : '',
				'satuan' => isset($row->satuan) ? (string) $row->satuan : '',
				'hpp' => isset($row->hpp) ? (string) $row->hpp : '',
				'sa' => isset($row->sa) ? (string) $row->sa : '0',
				'beli' => isset($row->beli) ? (string) $row->beli : '0',
				'penjualan' => isset($row->penjualan) ? (string) $row->penjualan : '0',
				'pecah_satuan' => isset($row->pecah_satuan) ? (string) $row->pecah_satuan : '0',
				'bahan_produksi' => isset($row->bahan_produksi) ? (string) $row->bahan_produksi : '0',
				'total_10_lama' => $this->format_angka_persediaan($total_10_db),
				'total_10_hitung' => $this->format_angka_persediaan($total_10_hitung),
				'total_10' => $this->format_angka_persediaan($total_10_final),
				'tanggal_beli' => isset($row->tanggal_beli) ? (string) $row->tanggal_beli : '',
				'status_copy' => $will_copy ? 'COPIED' : 'SKIP',
				'keterangan' => $ket_fix,
			);

			// Copy hanya jika total_10 > 0
			if (!$will_copy) {
				$skipped++;
				continue;
			}

			$data_insert = array();
			foreach ($fields as $field) {
				if ($field === 'id') {
					continue;
				}
				$data_insert[$field] = isset($row->$field) ? $row->$field : null;
			}

			$data_insert['id'] = $next_id++;
			$data_insert['total_10'] = $this->format_angka_persediaan($total_10_final);

			$tgl_sumber = isset($row->tanggal_beli) ? trim((string) $row->tanggal_beli) : '';
			if ($tgl_sumber === '' || $tgl_sumber === '0000-00-00' || $tgl_sumber === '0000-00-00 00:00:00') {
				$tgl_sumber = $tanggal_beli_sumber;
			}

			if ($is_mode_data_dasar_des2025) {
				// Sumber diambil dari record tanggal_beli bulan referensi (DES 2025).
				// Record baru untuk Januari 2026:
				// - tanggal_beli awal = tanggal 1 bulan/tahun terpilih (nanti bisa diganti tgl_po saat match pembelian)
				// - tgl_persediaan = tanggal 1 bulan/tahun terpilih
				$data_insert['tanggal_beli'] = $tanggal_beli_target;
				if ($has_tgl_persediaan) {
					$data_insert['tgl_persediaan'] = $tanggal_beli_target;
				}
				if (array_key_exists('tanggal', $data_insert)) {
					$data_insert['tanggal'] = $tanggal_tampil_target;
				}
			} else {
				$data_insert['tanggal_beli'] = $tanggal_beli_target;
				if ($has_tgl_persediaan) {
					$data_insert['tgl_persediaan'] = $tanggal_beli_target;
				}
				if (array_key_exists('tanggal', $data_insert)) {
					$data_insert['tanggal'] = $tanggal_tampil_target;
				}
			}

			if (!$this->db->insert('persediaan', $data_insert)) {
				$db_err = $this->db->error();
				$pesan_db = isset($db_err['message']) ? trim((string) $db_err['message']) : 'Gagal insert persediaan.';
				throw new Exception($pesan_db . ' (sumber id=' . (isset($row->id) ? $row->id : '?') . ')');
			}
			$copied++;
		}

		$mode_msg = $is_mode_data_dasar_des2025
			? 'Mode data dasar DES 2025→JAN 2026 (copy apa adanya, tanpa rumus). '
			: 'Mode normal (koreksi total_10 dengan rumus). ';

		$draft_saved = $this->simpan_persediaan_draft_bulan_referensi(
			$bulan_target,
			$bulan_sumber_key,
			$rows_sumber,
			$rows_preview
		);

		$pembelian_apply = $this->proses_generate_apply_pembelian_ke_beli($bulan_target);
		$produksi_apply = $this->proses_generate_apply_produksi_unit_produk($bulan_target);
		$bahan_apply = $this->proses_generate_apply_bahan_produksi($bulan_target);
		$pecah_apply = $this->proses_generate_apply_pecah_satuan($bulan_target);
		$penjualan_apply = $this->proses_generate_apply_penjualan($bulan_target);

		$rekon = $this->rekon_nilai_persediaan_sumber_vs_target(
			$tahun_sumber,
			$bulan_sumber_num,
			$tahun_target,
			$bulan_target_num,
			$is_mode_data_dasar_des2025
		);
		if (!empty($rekon['masalah']) && is_array($rekon['masalah']) && count($rekon['masalah']) > 300) {
			$rekon['masalah'] = array_slice($rekon['masalah'], 0, 300);
			$rekon['masalah_truncated'] = true;
		}

		$rekon_ok = !empty($rekon['ok']);
		$msg_rekon = '';
		if ($rekon_ok) {
			$msg_rekon = ' Rekon nilai_persediaan: SAMA (sumber '
				. $this->format_angka_persediaan(isset($rekon['sum_nilai_sumber']) ? $rekon['sum_nilai_sumber'] : 0)
				. ' = target '
				. $this->format_angka_persediaan(isset($rekon['sum_nilai_target']) ? $rekon['sum_nilai_target'] : 0)
				. ').';
		} else {
			$msg_rekon = ' PERINGATAN REKON: nilai_persediaan BERBEDA. Selisih '
				. $this->format_angka_persediaan(isset($rekon['selisih_nilai']) ? $rekon['selisih_nilai'] : 0)
				. ' | sumber='
				. $this->format_angka_persediaan(isset($rekon['sum_nilai_sumber']) ? $rekon['sum_nilai_sumber'] : 0)
				. ' | target='
				. $this->format_angka_persediaan(isset($rekon['sum_nilai_target']) ? $rekon['sum_nilai_target'] : 0)
				. ' | baris bermasalah='
				. (isset($rekon['count_masalah']) ? (int) $rekon['count_masalah'] : 0)
				. '.';
		}

		$msg_beli = ' Pembelian: cocok='
			. (int) (isset($pembelian_apply['matched_count']) ? $pembelian_apply['matched_count'] : 0)
			. ' (fase1='
			. (int) (isset($pembelian_apply['matched_fase1']) ? $pembelian_apply['matched_fase1'] : 0)
			. ', fase2 sync uuid='
			. (int) (isset($pembelian_apply['matched_fase2']) ? $pembelian_apply['matched_fase2'] : 0)
			. ', fase3 insert='
			. (int) (isset($pembelian_apply['inserted_fase3']) ? $pembelian_apply['inserted_fase3'] : 0)
			. '), update beli='
			. (int) (isset($pembelian_apply['updated_beli']) ? $pembelian_apply['updated_beli'] : 0)
			. ', total_10=sa+beli='
			. (int) (isset($pembelian_apply['updated_total10_beli']) ? $pembelian_apply['updated_total10_beli'] : 0)
			. ', uuid disamakan='
			. (int) (isset($pembelian_apply['uuid_synced']) ? $pembelian_apply['uuid_synced'] : 0)
			. ', belum ada='
			. (int) (isset($pembelian_apply['unmatched_count']) ? $pembelian_apply['unmatched_count'] : 0)
			. ' | SUM beli persediaan='
			. $this->format_angka_persediaan(isset($pembelian_apply['sum_beli_persediaan']) ? $pembelian_apply['sum_beli_persediaan'] : 0)
			. ' vs SUM jumlah pembelian='
			. $this->format_angka_persediaan(isset($pembelian_apply['sum_jumlah_pembelian']) ? $pembelian_apply['sum_jumlah_pembelian'] : 0)
			. (!empty($pembelian_apply['total_beli_ok']) ? ' (SAMA)' : ' (BEDA)')
			. '.';

		$msg_produksi = ' Produksi: insert='
			. (int) (isset($produksi_apply['inserted']) ? $produksi_apply['inserted'] : 0)
			. ', skip='
			. (int) (isset($produksi_apply['skipped']) ? $produksi_apply['skipped'] : 0)
			. ', total record sys_unit_produk='
			. (int) (isset($produksi_apply['total_sumber']) ? $produksi_apply['total_sumber'] : 0)
			. '. Bahan produksi: update='
			. (int) (isset($bahan_apply['updated']) ? $bahan_apply['updated'] : 0)
			. ', unmatched='
			. (int) (isset($bahan_apply['unmatched_count']) ? $bahan_apply['unmatched_count'] : 0)
			. '. Pecah satuan: update='
			. (int) (isset($pecah_apply['updated']) ? $pecah_apply['updated'] : 0)
			. ', unmatched='
			. (int) (isset($pecah_apply['unmatched_count']) ? $pecah_apply['unmatched_count'] : 0)
			. '. Penjualan: terproses='
			. (int) (isset($penjualan_apply['matched_count']) ? $penjualan_apply['matched_count'] : 0)
			. ', belum terproses='
			. (int) (isset($penjualan_apply['unmatched_count']) ? $penjualan_apply['unmatched_count'] : 0)
			. ', update persediaan='
			. (int) (isset($penjualan_apply['updated']) ? $penjualan_apply['updated'] : 0)
			. '.';

		$pembelian_matched = isset($pembelian_apply['matched']) ? $pembelian_apply['matched'] : array();
		$pembelian_unmatched = isset($pembelian_apply['unmatched']) ? $pembelian_apply['unmatched'] : array();
		if (isset($pembelian_apply['matched'])) {
			unset($pembelian_apply['matched']);
		}
		if (isset($pembelian_apply['unmatched'])) {
			unset($pembelian_apply['unmatched']);
		}

		$penjualan_matched = isset($penjualan_apply['matched']) ? $penjualan_apply['matched'] : array();
		$penjualan_unmatched = isset($penjualan_apply['unmatched']) ? $penjualan_apply['unmatched'] : array();
		if (isset($penjualan_apply['matched'])) {
			unset($penjualan_apply['matched']);
		}
		if (isset($penjualan_apply['unmatched'])) {
			unset($penjualan_apply['unmatched']);
		}

		$result = array(
			'ok' => true,
			'rekon_ok' => $rekon_ok,
			'message' => $mode_msg . 'Fase copy selesai. Hapus target: ' . $deleted
				. ', koreksi total_10: ' . $updated_total10
				. ', copy: ' . $copied
				. ', skip (total_10<=0): ' . $skipped
				. ', history draft: ' . (int) $draft_saved . '.'
				. $msg_beli
				. $msg_produksi
				. $msg_rekon,
			'deleted' => $deleted,
			'updated_total10' => $updated_total10,
			'copied' => $copied,
			'skipped' => $skipped,
			'draft_saved' => (int) $draft_saved,
			'total_sumber' => count($rows_sumber),
			'bulan_target' => $bulan_target,
			'bulan_sumber' => $bulan_sumber_key,
			'label_sumber' => $label_sumber,
			'label_target' => $label_target,
			'tanggal_beli_target' => $tanggal_beli_target,
			'tanggal_beli_sumber' => $tanggal_beli_sumber,
			'mode_data_dasar' => $is_mode_data_dasar_des2025,
			'rekon' => $rekon,
			'rows' => $rows_preview,
			'pembelian_apply' => $pembelian_apply,
			'pembelian_matched' => $pembelian_matched,
			'pembelian_unmatched' => $pembelian_unmatched,
			'produksi_apply' => $produksi_apply,
			'produksi_rows' => isset($produksi_apply['rows']) ? $produksi_apply['rows'] : array(),
			'bahan_produksi_apply' => $bahan_apply,
			'bahan_produksi_rows' => isset($bahan_apply['rows']) ? $bahan_apply['rows'] : array(),
			'pecah_satuan_apply' => $pecah_apply,
			'pecah_satuan_rows' => isset($pecah_apply['rows']) ? $pecah_apply['rows'] : array(),
			'penjualan_apply' => $penjualan_apply,
			'penjualan_matched' => $penjualan_matched,
			'penjualan_unmatched' => $penjualan_unmatched,
		);

		$history_id = 0;
		$save_meta = array();
		try {
			$history_id = persediaan_history_generate_save_v2_snapshot($this, $bulan_target, $result, $save_meta);
		} catch (Throwable $eSaveHist) {
			log_message('error', 'save_v2_snapshot: ' . $eSaveHist->getMessage());
			$history_id = 0;
			$save_meta = array('message' => $eSaveHist->getMessage());
		}
		if ($history_id > 0) {
			$result['history_id'] = $history_id;
			$result['history_saved'] = true;
			$result['history_source'] = 'database';
			if (!empty($save_meta['id_generate_run'])) {
				$result['id_generate_run'] = (int) $save_meta['id_generate_run'];
			}
			if (!empty($save_meta['row_counts']) && is_array($save_meta['row_counts'])) {
				$result['generate_row_counts'] = $save_meta['row_counts'];
			}
		} else {
			$result['history_saved'] = false;
			$result['history_save_message'] = !empty($save_meta['message'])
				? $save_meta['message']
				: 'Snapshot history tidak tersimpan ke database. Hubungi admin atau coba generate ulang.';
		}

		return $result;
	}

	/**
	 * Setelah copy: isi field beli di persediaan target dari tbl_pembelian + tbl_pembelian_jasa.
	 *
	 * Fase 1 — match 4 field: uuid_persediaan + uraian=namabarang + satuan + harga_satuan=hpp
	 *           → beli = SUM(jumlah), total_10 = sa+beli, tanggal_beli=tgl_po, tgl_persediaan=tgl 1
	 * Fase 2 — sisa unmatched: cocokkan uraian+satuan+harga_satuan (SA ada), sync uuid, update sama
	 * Fase 3 — sisa unmatched: INSERT record persediaan baru (beli=jumlah, tanggal_beli=tgl_po)
	 *           → datatable "belum ada" harus 0
	 */
	private function proses_generate_apply_pembelian_ke_beli($bulan_target)
	{
		$ts = strtotime($bulan_target . '-01');
		if ($ts === false) {
			return array(
				'ok' => false,
				'matched' => array(),
				'unmatched' => array(),
				'matched_count' => 0,
				'unmatched_count' => 0,
				'updated_beli' => 0,
				'reset_beli' => 0,
				'total_pembelian' => 0,
				'uuid_synced' => 0,
				'matched_fase1' => 0,
				'matched_fase2' => 0,
				'inserted_fase3' => 0,
				'sum_jumlah_pembelian' => 0,
				'sum_beli_persediaan' => 0,
				'total_beli_ok' => false,
			);
		}

		$tahun = (int) date('Y', $ts);
		$bulan = (int) date('m', $ts);
		$tgl_awal = date('Y-m-01', $ts);
		$tgl_akhir = date('Y-m-t', $ts);
		$tanggal_tampil_bulan = date('d/m/Y', $ts);
		$has_tgl_persediaan = $this->db->field_exists('tgl_persediaan', 'persediaan');
		$has_tanggal = $this->db->field_exists('tanggal', 'persediaan');

		$persediaan_rows = $this->db->query(
			"SELECT `id`, `uuid_persediaan`, `namabarang`, `satuan`, `hpp`, `beli`, `sa`, `tanggal_beli`
				" . ($has_tgl_persediaan ? ', `tgl_persediaan`' : '') . "
			 FROM `persediaan`
			 WHERE (" . ($has_tgl_persediaan ? "`tgl_persediaan` = ? OR " : '') . "
			       (YEAR(`tanggal_beli`) = ? AND MONTH(`tanggal_beli`) = ?))
			 ORDER BY `id` ASC",
			$has_tgl_persediaan
				? array($tgl_awal, $tahun, $bulan)
				: array($tahun, $bulan)
		)->result();

		$index = array();
		$index_nama = array();
		$index_nama_with_sa = array();
		$by_id = array();
		foreach ($persediaan_rows as $p) {
			$pid = (int) $p->id;
			$by_id[$pid] = $p;

			$key = $this->generate_match_key_pembelian_persediaan(
				isset($p->uuid_persediaan) ? $p->uuid_persediaan : '',
				isset($p->namabarang) ? $p->namabarang : '',
				isset($p->satuan) ? $p->satuan : '',
				isset($p->hpp) ? $p->hpp : ''
			);
			if ($key !== '' && !isset($index[$key])) {
				$index[$key] = $pid;
			}

			$key3 = $this->generate_match_key_nama_satuan_hpp(
				isset($p->namabarang) ? $p->namabarang : '',
				isset($p->satuan) ? $p->satuan : '',
				isset($p->hpp) ? $p->hpp : ''
			);
			if ($key3 === '') {
				continue;
			}
			if (!isset($index_nama[$key3])) {
				$index_nama[$key3] = $pid;
			}
			$sa_val = $this->parse_angka_persediaan(isset($p->sa) ? $p->sa : 0);
			if ($sa_val > 0 && !isset($index_nama_with_sa[$key3])) {
				$index_nama_with_sa[$key3] = $pid;
			}
		}

		// Pastikan tgl_persediaan = tanggal 1 bulan target pada record hasil copy
		if ($has_tgl_persediaan && !empty($persediaan_rows)) {
			$this->db->query(
				"UPDATE `persediaan`
				 SET `tgl_persediaan` = ?
				 WHERE (`tgl_persediaan` IS NULL OR `tgl_persediaan` = '0000-00-00' OR `tgl_persediaan` <> ?)
				   AND YEAR(`tanggal_beli`) = ? AND MONTH(`tanggal_beli`) = ?",
				array($tgl_awal, $tgl_awal, $tahun, $bulan)
			);
		}

		// Reset beli target dulu (beli bulan ini = dari pembelian), lalu isi dari match.
		$reset_beli = 0;
		if (!empty($persediaan_rows)) {
			if ($has_tgl_persediaan) {
				$this->db->query(
					"UPDATE `persediaan` SET `beli` = '0' WHERE `tgl_persediaan` = ?",
					array($tgl_awal)
				);
			} else {
				$this->db->query(
					"UPDATE `persediaan`
					 SET `beli` = '0'
					 WHERE YEAR(`tanggal_beli`) = ? AND MONTH(`tanggal_beli`) = ?",
					array($tahun, $bulan)
				);
			}
			$reset_beli = count($persediaan_rows);
		}

		$agg_beli = array();
		$agg_tgl_po = array();
		$matched = array();
		$unmatched = array();
		$total_pembelian = 0;
		$sum_jumlah_pembelian = 0.0;
		$no_m = 0;
		$matched_fase1 = 0;

		foreach (array('tbl_pembelian', 'tbl_pembelian_jasa') as $tabel) {
			if (!$this->db->table_exists($tabel)) {
				continue;
			}

			$has_uuid = $this->db->field_exists('uuid_persediaan', $tabel);
			$select_uuid = $has_uuid
				? 'TRIM(COALESCE(`uuid_persediaan`, \'\')) AS uuid_persediaan'
				: '\'\' AS uuid_persediaan';
			$has_uuid_barang = $this->db->field_exists('uuid_barang', $tabel);
			$select_uuid_barang = $has_uuid_barang
				? 'TRIM(COALESCE(`uuid_barang`, \'\')) AS uuid_barang'
				: '\'\' AS uuid_barang';
			$has_uuid_spop = $this->db->field_exists('uuid_spop', $tabel);
			$select_uuid_spop = $has_uuid_spop
				? 'TRIM(COALESCE(`uuid_spop`, \'\')) AS uuid_spop'
				: '\'\' AS uuid_spop';

			$has_kategori = $this->db->field_exists('kategori', $tabel);
			$select_kategori = $has_kategori
				? 'TRIM(COALESCE(`kategori`, \'\')) AS kategori'
				: (($tabel === 'tbl_pembelian_jasa') ? '\'Jasa\' AS kategori' : '\'Barang\' AS kategori');

			$rows = $this->db->query(
				"SELECT `id`, `tgl_po`, `spop`, `uraian`, `jumlah`, `satuan`, `harga_satuan`, `harga_total`,
					`supplier_nama`, `kode_barang`, `konsumen`, `statuslu`, `nmrfakturkwitansi`,
					{$select_uuid}, {$select_uuid_barang}, {$select_uuid_spop}, {$select_kategori}
				 FROM `{$tabel}`
				 WHERE `tgl_po` IS NOT NULL AND `tgl_po` <> '0000-00-00'
				   AND DATE(`tgl_po`) >= ? AND DATE(`tgl_po`) <= ?
				 ORDER BY `tgl_po` ASC, `spop` ASC, `id` ASC",
				array($tgl_awal, $tgl_akhir)
			)->result();

			foreach ($rows as $r) {
				$total_pembelian++;
				$uuid = isset($r->uuid_persediaan) ? trim((string) $r->uuid_persediaan) : '';
				$uraian = isset($r->uraian) ? trim((string) $r->uraian) : '';
				$satuan = isset($r->satuan) ? trim((string) $r->satuan) : '';
				$harga = isset($r->harga_satuan) ? $r->harga_satuan : 0;
				$jumlah = $this->parse_angka_persediaan(isset($r->jumlah) ? $r->jumlah : 0);
				$sum_jumlah_pembelian += $jumlah;
				$harga_total = isset($r->harga_total) ? $r->harga_total : ($jumlah * $this->parse_angka_persediaan($harga));
				$tgl_po_norm = $this->normalize_tanggal_po_persediaan(isset($r->tgl_po) ? $r->tgl_po : '');

				$key = $this->generate_match_key_pembelian_persediaan($uuid, $uraian, $satuan, $harga);
				$base = array(
					'sumber_tabel' => $tabel,
					'id_pembelian' => isset($r->id) ? (int) $r->id : 0,
					'tgl_po' => isset($r->tgl_po) ? (string) $r->tgl_po : '',
					'tgl_po_norm' => $tgl_po_norm,
					'spop' => isset($r->spop) ? (string) $r->spop : '',
					'kategori' => isset($r->kategori) ? (string) $r->kategori : '',
					'nmrfakturkwitansi' => isset($r->nmrfakturkwitansi) ? (string) $r->nmrfakturkwitansi : '',
					'supplier_nama' => isset($r->supplier_nama) ? (string) $r->supplier_nama : '',
					'kode_barang' => isset($r->kode_barang) ? (string) $r->kode_barang : '',
					'uraian' => $uraian,
					'jumlah' => $this->format_angka_persediaan($jumlah),
					'satuan' => $satuan,
					'konsumen' => isset($r->konsumen) ? (string) $r->konsumen : '',
					'harga_satuan' => isset($r->harga_satuan) ? (string) $r->harga_satuan : '',
					'harga_total' => $this->format_angka_persediaan($this->parse_angka_persediaan($harga_total)),
					'statuslu' => isset($r->statuslu) ? (string) $r->statuslu : '',
					'uuid_persediaan' => $uuid,
					'uuid_barang' => isset($r->uuid_barang) ? trim((string) $r->uuid_barang) : '',
					'uuid_spop' => isset($r->uuid_spop) ? trim((string) $r->uuid_spop) : '',
				);

				if ($key !== '' && isset($index[$key])) {
					$pid = (int) $index[$key];
					if (!isset($agg_beli[$pid])) {
						$agg_beli[$pid] = 0.0;
					}
					$agg_beli[$pid] += $jumlah;
					if ($tgl_po_norm !== '') {
						$agg_tgl_po[$pid] = $tgl_po_norm;
					}

					$no_m++;
					$matched_fase1++;
					$pers = isset($by_id[$pid]) ? $by_id[$pid] : null;
					$base['no'] = $no_m;
					$base['status'] = 'MATCH';
					$base['match_fase'] = 1;
					$base['id_persediaan'] = $pid;
					$base['namabarang_persediaan'] = $pers ? (string) $pers->namabarang : '';
					$base['hpp_persediaan'] = $pers ? (string) $pers->hpp : '';
					$base['beli_baru'] = $this->format_angka_persediaan($agg_beli[$pid]);
					$base['keterangan'] = 'Cocok uuid+uraian+satuan+hpp; tanggal_beli=tgl_po';
					$matched[] = $base;
				} else {
					$base['no'] = 0;
					$base['status'] = 'BELUM ADA';
					$base['match_fase'] = 0;
					$base['id_persediaan'] = 0;
					$base['namabarang_persediaan'] = '';
					$base['hpp_persediaan'] = '';
					$base['beli_baru'] = '';
					$base['keterangan'] = ($uuid === '')
						? 'uuid_persediaan kosong / tidak cocok dengan uuid+nama+satuan+hpp persediaan'
						: 'uuid+uraian+satuan+harga_satuan tidak cocok dengan record persediaan target';
					$unmatched[] = $base;
				}
			}
		}

		// Fase 2: unmatched → cocokkan uraian+satuan+hpp (prioritas SA>0), sync uuid, update beli + tanggal
		$uuid_synced = 0;
		$matched_fase2 = 0;
		$still_unmatched = array();

		foreach ($unmatched as $urow) {
			$uuid = isset($urow['uuid_persediaan']) ? trim((string) $urow['uuid_persediaan']) : '';
			$uraian = isset($urow['uraian']) ? trim((string) $urow['uraian']) : '';
			$satuan = isset($urow['satuan']) ? trim((string) $urow['satuan']) : '';
			$harga = isset($urow['harga_satuan']) ? $urow['harga_satuan'] : 0;
			$jumlah = $this->parse_angka_persediaan(isset($urow['jumlah']) ? $urow['jumlah'] : 0);
			$tgl_po_norm = isset($urow['tgl_po_norm']) ? trim((string) $urow['tgl_po_norm']) : '';
			if ($tgl_po_norm === '') {
				$tgl_po_norm = $this->normalize_tanggal_po_persediaan(isset($urow['tgl_po']) ? $urow['tgl_po'] : '');
			}
			$key3 = $this->generate_match_key_nama_satuan_hpp($uraian, $satuan, $harga);

			$pid = 0;
			if ($key3 !== '') {
				if (isset($index_nama_with_sa[$key3])) {
					$pid = (int) $index_nama_with_sa[$key3];
				} elseif (isset($index_nama[$key3])) {
					$pid = (int) $index_nama[$key3];
				}
			}

			if ($pid > 0 && $uuid !== '') {
				$pers = isset($by_id[$pid]) ? $by_id[$pid] : null;
				$uuid_lama = $pers ? trim((string) $pers->uuid_persediaan) : '';

				if (strtolower($uuid_lama) !== strtolower($uuid)) {
					$this->db->where('id', $pid);
					$this->db->update('persediaan', array('uuid_persediaan' => $uuid));
					$uuid_synced++;
					if ($pers) {
						$pers->uuid_persediaan = $uuid;
						$by_id[$pid] = $pers;
					}
				}

				$key_baru = $this->generate_match_key_pembelian_persediaan($uuid, $uraian, $satuan, $harga);
				if ($key_baru !== '') {
					$index[$key_baru] = $pid;
				}

				if (!isset($agg_beli[$pid])) {
					$agg_beli[$pid] = 0.0;
				}
				$agg_beli[$pid] += $jumlah;
				if ($tgl_po_norm !== '') {
					$agg_tgl_po[$pid] = $tgl_po_norm;
				}

				$no_m++;
				$matched_fase2++;
				$urow['no'] = $no_m;
				$urow['status'] = 'MATCH_SYNC_UUID';
				$urow['match_fase'] = 2;
				$urow['id_persediaan'] = $pid;
				$urow['namabarang_persediaan'] = $pers ? (string) $pers->namabarang : $uraian;
				$urow['hpp_persediaan'] = $pers ? (string) $pers->hpp : (string) $harga;
				$urow['beli_baru'] = $this->format_angka_persediaan($agg_beli[$pid]);
				$urow['uuid_persediaan_lama'] = $uuid_lama;
				$urow['keterangan'] = 'Fase2: cocok uraian+satuan+hpp; uuid disamakan; tanggal_beli=tgl_po';
				$matched[] = $urow;
			} else {
				$still_unmatched[] = $urow;
			}
		}

		// Apply beli + total_10 + tanggal pada record match fase1/2
		$updated_beli = 0;
		$updated_total10 = 0;
		foreach ($agg_beli as $pid => $sum_jumlah) {
			$beli_tampil = $this->format_angka_persediaan($sum_jumlah);
			$pers = isset($by_id[$pid]) ? $by_id[$pid] : null;
			$sa_val = $pers
				? $this->parse_angka_persediaan(isset($pers->sa) ? $pers->sa : 0)
				: 0.0;
			if (!$pers) {
				$row_fresh = $this->db->select('sa')->where('id', (int) $pid)->limit(1)->get('persediaan')->row();
				if ($row_fresh) {
					$sa_val = $this->parse_angka_persediaan(isset($row_fresh->sa) ? $row_fresh->sa : 0);
				}
			}
			$total_10_baru = $sa_val + (float) $sum_jumlah;
			$total_10_tampil = $this->format_angka_persediaan($total_10_baru);

			$data_upd = array(
				'beli' => $beli_tampil,
				'total_10' => $total_10_tampil,
			);
			if ($has_tgl_persediaan) {
				$data_upd['tgl_persediaan'] = $tgl_awal;
			}
			if (!empty($agg_tgl_po[$pid])) {
				$data_upd['tanggal_beli'] = $agg_tgl_po[$pid];
				if ($has_tanggal) {
					$data_upd['tanggal'] = date('d/m/Y', strtotime($agg_tgl_po[$pid]));
				}
			} elseif ($has_tanggal) {
				$data_upd['tanggal'] = $tanggal_tampil_bulan;
			}

			$this->db->where('id', (int) $pid);
			$this->db->update('persediaan', $data_upd);
			$updated_beli++;
			$updated_total10++;

			foreach ($matched as $mi => $mrow) {
				if ((int) $mrow['id_persediaan'] === (int) $pid) {
					$matched[$mi]['beli_baru'] = $beli_tampil;
					$matched[$mi]['total_10_baru'] = $total_10_tampil;
					$matched[$mi]['sa'] = $this->format_angka_persediaan($sa_val);
					$matched[$mi]['tanggal_beli_baru'] = isset($data_upd['tanggal_beli']) ? $data_upd['tanggal_beli'] : '';
					$matched[$mi]['tgl_persediaan_baru'] = $tgl_awal;
				}
			}
		}

		// Fase 3: insert record baru dari sisa unmatched → datatable "belum ada" jadi 0
		$inserted_fase3 = 0;
		$row_max = $this->db->query("SELECT MAX(`id`) AS max_id FROM `persediaan`")->row();
		$next_id = ($row_max && $row_max->max_id) ? ((int) $row_max->max_id + 1) : 1;
		$unmatched = array();

		foreach ($still_unmatched as $urow) {
			$uraian = isset($urow['uraian']) ? trim((string) $urow['uraian']) : '';
			$satuan = isset($urow['satuan']) ? trim((string) $urow['satuan']) : '';
			$jumlah = $this->parse_angka_persediaan(isset($urow['jumlah']) ? $urow['jumlah'] : 0);
			$harga = isset($urow['harga_satuan']) ? $urow['harga_satuan'] : 0;
			$hpp_n = $this->parse_angka_persediaan($harga);
			$uuid = isset($urow['uuid_persediaan']) ? trim((string) $urow['uuid_persediaan']) : '';
			$tgl_po_norm = isset($urow['tgl_po_norm']) ? trim((string) $urow['tgl_po_norm']) : '';
			if ($tgl_po_norm === '') {
				$tgl_po_norm = $this->normalize_tanggal_po_persediaan(isset($urow['tgl_po']) ? $urow['tgl_po'] : '');
			}
			if ($tgl_po_norm === '') {
				$tgl_po_norm = $tgl_awal;
			}

			if ($uraian === '' || $satuan === '' || $jumlah <= 0) {
				$urow['no'] = count($unmatched) + 1;
				$urow['status'] = 'BELUM ADA';
				$urow['keterangan'] = 'Skip insert: uraian/satuan kosong atau jumlah <= 0';
				$unmatched[] = $urow;
				continue;
			}

			$beli_tampil = $this->format_angka_persediaan($jumlah);
			$total_10_tampil = $beli_tampil; // sa=0 untuk record baru
			$nilai_tampil = $this->format_angka_persediaan($jumlah * $hpp_n);
			$kategori = (isset($urow['sumber_tabel']) && $urow['sumber_tabel'] === 'tbl_pembelian_jasa')
				? 'jasa'
				: 'barang';

			$new_id = $next_id++;
			$data_insert = array(
				'id' => $new_id,
				'tanggal_beli' => $tgl_po_norm,
				'namabarang' => $uraian,
				'satuan' => $satuan,
				'hpp' => $this->format_angka_persediaan($hpp_n),
				'sa' => '0',
				'beli' => $beli_tampil,
				'penjualan' => 0,
				'pecah_satuan' => 0,
				'bahan_produksi' => 0,
				'total_10' => $total_10_tampil,
				'nilai_persediaan' => $nilai_tampil,
				'tuj' => $total_10_tampil,
				'spop' => isset($urow['spop']) ? (string) $urow['spop'] : '0',
			);
			if ($has_tgl_persediaan) {
				$data_insert['tgl_persediaan'] = $tgl_awal;
			}
			if ($has_tanggal) {
				$data_insert['tanggal'] = date('d/m/Y', strtotime($tgl_po_norm));
			}
			if ($this->db->field_exists('kategori', 'persediaan')) {
				$data_insert['kategori'] = $kategori;
			}
			if ($this->db->field_exists('kode_barang', 'persediaan') && !empty($urow['kode_barang'])) {
				$data_insert['kode_barang'] = (string) $urow['kode_barang'];
			}
			if ($this->db->field_exists('uuid_barang', 'persediaan') && !empty($urow['uuid_barang'])) {
				$data_insert['uuid_barang'] = (string) $urow['uuid_barang'];
			}
			if ($this->db->field_exists('uuid_spop', 'persediaan') && !empty($urow['uuid_spop'])) {
				$data_insert['uuid_spop'] = (string) $urow['uuid_spop'];
			}
			if ($this->db->field_exists('uuid_persediaan_lama', 'persediaan')) {
				$data_insert['uuid_persediaan_lama'] = 'gen_pembelian:' . (int) (isset($urow['id_pembelian']) ? $urow['id_pembelian'] : 0);
			}

			$db_debug = $this->db->db_debug;
			$this->db->db_debug = false;
			if ($uuid !== '' && $this->db->field_exists('uuid_persediaan', 'persediaan')) {
				$data_insert['uuid_persediaan'] = $uuid;
				$insert_ok = $this->db->insert('persediaan', $data_insert);
			} else {
				if ($this->db->field_exists('uuid_persediaan', 'persediaan')) {
					unset($data_insert['uuid_persediaan']);
				}
				$this->db->set('uuid_persediaan', "replace(uuid(),'-','')", FALSE);
				$insert_ok = $this->db->insert('persediaan', $data_insert);
			}
			$this->db->db_debug = $db_debug;

			if (!$insert_ok) {
				$db_err = $this->db->error();
				$urow['no'] = count($unmatched) + 1;
				$urow['status'] = 'BELUM ADA';
				$urow['keterangan'] = 'Gagal insert: ' . (isset($db_err['message']) ? $db_err['message'] : 'error DB');
				$unmatched[] = $urow;
				continue;
			}

			if ($uuid === '' && $this->db->field_exists('uuid_persediaan', 'persediaan')) {
				$row_uuid = $this->db->select('uuid_persediaan')->where('id', $new_id)->limit(1)->get('persediaan')->row();
				$uuid = $row_uuid ? trim((string) $row_uuid->uuid_persediaan) : '';
			}

			$inserted_fase3++;
			$updated_beli++;
			$no_m++;
			$urow['no'] = $no_m;
			$urow['status'] = 'INSERT_BARU';
			$urow['match_fase'] = 3;
			$urow['id_persediaan'] = $new_id;
			$urow['uuid_persediaan'] = $uuid;
			$urow['namabarang_persediaan'] = $uraian;
			$urow['hpp_persediaan'] = $this->format_angka_persediaan($hpp_n);
			$urow['beli_baru'] = $beli_tampil;
			$urow['total_10_baru'] = $total_10_tampil;
			$urow['sa'] = '0';
			$urow['tanggal_beli_baru'] = $tgl_po_norm;
			$urow['tgl_persediaan_baru'] = $tgl_awal;
			$urow['keterangan'] = 'Fase3: insert persediaan baru; tanggal_beli=tgl_po; tgl_persediaan=tgl 1 bulan';
			$matched[] = $urow;
		}

		foreach ($matched as $mi => $mrow) {
			$matched[$mi]['no'] = $mi + 1;
		}

		// Verifikasi total beli persediaan (bulan target via tgl_persediaan/tanggal_beli) = total jumlah pembelian
		if ($has_tgl_persediaan) {
			$row_sum_beli = $this->db->query(
				"SELECT COALESCE(SUM(CAST(REPLACE(REPLACE(TRIM(COALESCE(`beli`,'0')), ',', ''), ' ', '') AS DECIMAL(20,4))), 0) AS total_beli
				 FROM `persediaan` WHERE `tgl_persediaan` = ?",
				array($tgl_awal)
			)->row();
		} else {
			$row_sum_beli = $this->db->query(
				"SELECT COALESCE(SUM(CAST(REPLACE(REPLACE(TRIM(COALESCE(`beli`,'0')), ',', ''), ' ', '') AS DECIMAL(20,4))), 0) AS total_beli
				 FROM `persediaan`
				 WHERE YEAR(`tanggal_beli`) = ? AND MONTH(`tanggal_beli`) = ?",
				array($tahun, $bulan)
			)->row();
		}
		$sum_beli_persediaan = $row_sum_beli ? (float) $row_sum_beli->total_beli : 0.0;
		$total_beli_ok = abs($sum_beli_persediaan - $sum_jumlah_pembelian) < 0.0001;

		return array(
			'ok' => true,
			'matched' => $matched,
			'unmatched' => $unmatched,
			'matched_count' => count($matched),
			'unmatched_count' => count($unmatched),
			'updated_beli' => $updated_beli,
			'updated_total10_beli' => $updated_total10,
			'reset_beli' => $reset_beli,
			'total_pembelian' => $total_pembelian,
			'uuid_synced' => $uuid_synced,
			'matched_fase1' => $matched_fase1,
			'matched_fase2' => $matched_fase2,
			'inserted_fase3' => $inserted_fase3,
			'sum_jumlah_pembelian' => $sum_jumlah_pembelian,
			'sum_beli_persediaan' => $sum_beli_persediaan,
			'total_beli_ok' => $total_beli_ok,
			'tgl_awal' => $tgl_awal,
			'tgl_akhir' => $tgl_akhir,
		);
	}

	/**
	 * Normalisasi tgl_po → Y-m-d.
	 */
	private function normalize_tanggal_po_persediaan($tgl_po)
	{
		$t = trim((string) $tgl_po);
		if ($t === '' || $t === '0000-00-00' || $t === '0000-00-00 00:00:00') {
			return '';
		}
		$ts = strtotime($t);
		if ($ts === false) {
			return '';
		}
		return date('Y-m-d', $ts);
	}

	/**
	 * Kunci match pembelian ↔ persediaan: uuid_persediaan + nama + satuan + hpp (angka).
	 */
	private function generate_match_key_pembelian_persediaan($uuid, $nama, $satuan, $hpp)
	{
		$uuid = strtolower(trim((string) $uuid));
		$nama = strtolower(trim((string) $nama));
		$satuan = strtolower(trim((string) $satuan));
		if ($uuid === '' || $nama === '' || $satuan === '') {
			return '';
		}
		$hpp_n = $this->parse_angka_persediaan($hpp);
		return $uuid . '|' . $nama . '|' . $satuan . '|' . number_format($hpp_n, 4, '.', '');
	}

	/**
	 * Kunci match sekunder: nama/uraian + satuan + hpp (tanpa uuid).
	 */
	private function generate_match_key_nama_satuan_hpp($nama, $satuan, $hpp)
	{
		$nama = strtolower(trim((string) $nama));
		$satuan = strtolower(trim((string) $satuan));
		if ($nama === '' || $satuan === '') {
			return '';
		}
		$hpp_n = $this->parse_angka_persediaan($hpp);
		return $nama . '|' . $satuan . '|' . number_format($hpp_n, 4, '.', '');
	}

	/**
	 * Insert produk jadi dari sys_unit_produk (bulan target) sebagai record baru persediaan.
	 * Mapping: uuid_persediaan, tanggal_beli=tgl_transaksi, uuid_barang=uuid_produk,
	 * namabarang=nama_barang, sa=jumlah_produksi, satuan, hpp=harga_satuan,
	 * total_10=sa, nilai_persediaan=total_10*hpp, tgl_persediaan=tgl 1 bulan.
	 */
	private function proses_generate_apply_produksi_unit_produk($bulan_target)
	{
		$ts = strtotime($bulan_target . '-01');
		if ($ts === false) {
			return array(
				'ok' => false,
				'rows' => array(),
				'inserted' => 0,
				'skipped' => 0,
				'total_sumber' => 0,
			);
		}

		if (!$this->db->table_exists('sys_unit_produk')) {
			return array(
				'ok' => false,
				'message' => 'Tabel sys_unit_produk tidak tersedia.',
				'rows' => array(),
				'inserted' => 0,
				'skipped' => 0,
				'total_sumber' => 0,
			);
		}

		$tgl_awal = date('Y-m-01', $ts);
		$tgl_akhir = date('Y-m-t', $ts);
		$has_tgl_persediaan = $this->db->field_exists('tgl_persediaan', 'persediaan');
		$has_tanggal = $this->db->field_exists('tanggal', 'persediaan');
		$has_uuid_barang = $this->db->field_exists('uuid_barang', 'persediaan');
		$has_kode_barang = $this->db->field_exists('kode_barang', 'persediaan');
		$has_kategori = $this->db->field_exists('kategori', 'persediaan');

		$select_uuid_p = $this->db->field_exists('uuid_persediaan', 'sys_unit_produk')
			? 'TRIM(COALESCE(`uuid_persediaan`, \'\')) AS uuid_persediaan'
			: '\'\' AS uuid_persediaan';
		$select_uuid_prod = $this->db->field_exists('uuid_produk', 'sys_unit_produk')
			? 'TRIM(COALESCE(`uuid_produk`, \'\')) AS uuid_produk'
			: '\'\' AS uuid_produk';
		$select_kode = $this->db->field_exists('kode_barang', 'sys_unit_produk')
			? 'TRIM(COALESCE(`kode_barang`, \'\')) AS kode_barang'
			: '\'\' AS kode_barang';
		$select_nama_unit = $this->db->field_exists('nama_unit', 'sys_unit_produk')
			? 'TRIM(COALESCE(`nama_unit`, \'\')) AS nama_unit'
			: '\'\' AS nama_unit';

		$sumber_rows = $this->db->query(
			"SELECT `id`, `tgl_transaksi`, `nama_barang`, `jumlah_produksi`, `satuan`, `harga_satuan`,
				{$select_uuid_p}, {$select_uuid_prod}, {$select_kode}, {$select_nama_unit}
			 FROM `sys_unit_produk`
			 WHERE `tgl_transaksi` IS NOT NULL AND `tgl_transaksi` <> '0000-00-00'
			   AND DATE(`tgl_transaksi`) >= ? AND DATE(`tgl_transaksi`) <= ?
			 ORDER BY `tgl_transaksi` ASC, `id` ASC",
			array($tgl_awal, $tgl_akhir)
		)->result();

		$row_max = $this->db->query("SELECT MAX(`id`) AS max_id FROM `persediaan`")->row();
		$next_id = ($row_max && $row_max->max_id) ? ((int) $row_max->max_id + 1) : 1;

		$inserted = 0;
		$skipped = 0;
		$rows_out = array();
		$no = 0;

		foreach ($sumber_rows as $r) {
			$nama = isset($r->nama_barang) ? trim((string) $r->nama_barang) : '';
			$satuan = isset($r->satuan) ? trim((string) $r->satuan) : '';
			$sa = $this->parse_angka_persediaan(isset($r->jumlah_produksi) ? $r->jumlah_produksi : 0);
			$hpp = $this->parse_angka_persediaan(isset($r->harga_satuan) ? $r->harga_satuan : 0);
			$tgl_beli = $this->normalize_tanggal_po_persediaan(isset($r->tgl_transaksi) ? $r->tgl_transaksi : '');
			if ($tgl_beli === '') {
				$tgl_beli = $tgl_awal;
			}

			if ($nama === '' || $satuan === '' || $sa <= 0) {
				$skipped++;
				continue;
			}

			// Sesuaikan panjang kolom DB (namabarang varchar(109), satuan varchar(5))
			if (function_exists('mb_substr')) {
				$nama = mb_substr($nama, 0, 109);
				$satuan = mb_substr($satuan, 0, 5);
			} else {
				$nama = substr($nama, 0, 109);
				$satuan = substr($satuan, 0, 5);
			}

			$total_10 = $sa;
			$nilai = $total_10 * $hpp;
			$uuid = isset($r->uuid_persediaan) ? trim((string) $r->uuid_persediaan) : '';
			$uuid_barang = isset($r->uuid_produk) ? trim((string) $r->uuid_produk) : '';
			$sa_t = $this->format_angka_persediaan($sa);
			$total_t = $this->format_angka_persediaan($total_10);
			$nilai_t = $this->format_angka_persediaan($nilai);
			$hpp_t = $this->format_angka_persediaan($hpp);

			$new_id = $next_id++;
			$data_insert = array(
				'id' => $new_id,
				'tanggal_beli' => $tgl_beli,
				'namabarang' => $nama,
				'satuan' => $satuan,
				'hpp' => $hpp_t,
				'sa' => $sa_t,
				'beli' => '0',
				'penjualan' => 0,
				'pecah_satuan' => 0,
				'bahan_produksi' => 0,
				'total_10' => $total_t,
				'nilai_persediaan' => $nilai_t,
				'tuj' => $total_t,
				'spop' => '0',
			);
			if ($has_tgl_persediaan) {
				$data_insert['tgl_persediaan'] = $tgl_awal;
			}
			if ($has_tanggal) {
				$data_insert['tanggal'] = date('d/m/Y', strtotime($tgl_beli));
			}
			if ($has_uuid_barang && $uuid_barang !== '') {
				$data_insert['uuid_barang'] = $uuid_barang;
			}
			if ($has_kode_barang) {
				$data_insert['kode_barang'] = 'produksi';
			}
			if ($has_kategori) {
				$data_insert['kategori'] = 'barang';
			}
			if ($this->db->field_exists('uuid_persediaan_lama', 'persediaan')) {
				$data_insert['uuid_persediaan_lama'] = 'gen_produksi:' . (int) (isset($r->id) ? $r->id : 0);
			}

			$db_debug = $this->db->db_debug;
			$this->db->db_debug = false;
			if ($uuid !== '' && $this->db->field_exists('uuid_persediaan', 'persediaan')) {
				$data_insert['uuid_persediaan'] = $uuid;
				$insert_ok = $this->db->insert('persediaan', $data_insert);
			} else {
				if ($this->db->field_exists('uuid_persediaan', 'persediaan')) {
					unset($data_insert['uuid_persediaan']);
				}
				$this->db->set('uuid_persediaan', "replace(uuid(),'-','')", FALSE);
				$insert_ok = $this->db->insert('persediaan', $data_insert);
			}
			$this->db->db_debug = $db_debug;

			if (!$insert_ok) {
				$skipped++;
				continue;
			}

			if ($uuid === '' && $this->db->field_exists('uuid_persediaan', 'persediaan')) {
				$row_uuid = $this->db->select('uuid_persediaan')->where('id', $new_id)->limit(1)->get('persediaan')->row();
				$uuid = $row_uuid ? trim((string) $row_uuid->uuid_persediaan) : '';
			}

			$inserted++;
			$no++;
			$rows_out[] = array(
				'no' => $no,
				'id_persediaan' => $new_id,
				'id_unit_produk' => isset($r->id) ? (int) $r->id : 0,
				'tgl_transaksi' => isset($r->tgl_transaksi) ? (string) $r->tgl_transaksi : '',
				'tanggal_beli' => $tgl_beli,
				'uuid_persediaan' => $uuid,
				'uuid_barang' => $uuid_barang,
				'namabarang' => $nama,
				'satuan' => $satuan,
				'hpp' => $hpp_t,
				'sa' => $sa_t,
				'total_10' => $total_t,
				'nilai_persediaan' => $nilai_t,
				'nama_unit' => isset($r->nama_unit) ? (string) $r->nama_unit : '',
				'status' => 'INSERT',
				'keterangan' => 'Produksi → persediaan baru (sa=jumlah_produksi, total_10=sa)',
			);
		}

		return array(
			'ok' => true,
			'rows' => $rows_out,
			'inserted' => $inserted,
			'skipped' => $skipped,
			'total_sumber' => count($sumber_rows),
			'tgl_awal' => $tgl_awal,
			'tgl_akhir' => $tgl_akhir,
		);
	}

	/**
	 * Update bahan_produksi dari sys_unit_produk_bahan (bulan target).
	 * Match uuid_persediaan → bahan_produksi = SUM(jumlah_bahan), total_10 = total_10 - SUM(jumlah_bahan).
	 */
	private function proses_generate_apply_bahan_produksi($bulan_target)
	{
		$ts = strtotime($bulan_target . '-01');
		if ($ts === false) {
			return array(
				'ok' => false,
				'rows' => array(),
				'updated' => 0,
				'unmatched_count' => 0,
				'total_sumber' => 0,
			);
		}

		if (!$this->db->table_exists('sys_unit_produk_bahan')) {
			return array(
				'ok' => false,
				'message' => 'Tabel sys_unit_produk_bahan tidak tersedia.',
				'rows' => array(),
				'updated' => 0,
				'unmatched_count' => 0,
				'total_sumber' => 0,
			);
		}

		$tahun = (int) date('Y', $ts);
		$bulan = (int) date('n', $ts);
		$tgl_awal = date('Y-m-01', $ts);
		$tgl_akhir = date('Y-m-t', $ts);

		$sumber_rows = $this->db->query(
			"SELECT `id`, `tgl_transaksi`, `uuid_persediaan`, `nama_barang_bahan`, `jumlah_bahan`,
				`satuan_bahan`, `harga_satuan_bahan`, `nama_unit`, `kode_barang_bahan`
			 FROM `sys_unit_produk_bahan`
			 WHERE `tgl_transaksi` IS NOT NULL AND `tgl_transaksi` <> '0000-00-00'
			   AND DATE(`tgl_transaksi`) >= ? AND DATE(`tgl_transaksi`) <= ?
			 ORDER BY `tgl_transaksi` ASC, `id` ASC",
			array($tgl_awal, $tgl_akhir)
		)->result();

		// Index persediaan target bulan by uuid
		$pers_rows = $this->db->query(
			"SELECT `id`, `uuid_persediaan`, `namabarang`, `satuan`, `hpp`, `total_10`, `bahan_produksi`
			 FROM `persediaan`
			 WHERE YEAR(`tanggal_beli`) = ? AND MONTH(`tanggal_beli`) = ?",
			array($tahun, $bulan)
		)->result();
		$by_uuid = array();
		foreach ($pers_rows as $prow) {
			$u = trim((string) (isset($prow->uuid_persediaan) ? $prow->uuid_persediaan : ''));
			if ($u === '') {
				continue;
			}
			if (!isset($by_uuid[$u])) {
				$by_uuid[$u] = array();
			}
			$by_uuid[$u][] = $prow;
		}

		// Aggregate jumlah_bahan per uuid_persediaan
		$agg = array(); // uuid => sum jumlah
		$detail_by_uuid = array();
		foreach ($sumber_rows as $r) {
			$uuid = isset($r->uuid_persediaan) ? trim((string) $r->uuid_persediaan) : '';
			$jumlah = $this->parse_angka_persediaan(isset($r->jumlah_bahan) ? $r->jumlah_bahan : 0);
			if ($uuid === '' || $jumlah <= 0) {
				continue;
			}
			if (!isset($agg[$uuid])) {
				$agg[$uuid] = 0.0;
				$detail_by_uuid[$uuid] = array();
			}
			$agg[$uuid] += $jumlah;
			$detail_by_uuid[$uuid][] = $r;
		}

		$updated = 0;
		$rows_out = array();
		$unmatched = 0;
		$no = 0;

		foreach ($agg as $uuid => $sum_jumlah) {
			$pers = null;
			if (isset($by_uuid[$uuid]) && count($by_uuid[$uuid]) > 0) {
				$pers = $by_uuid[$uuid][0];
			}

			$details = isset($detail_by_uuid[$uuid]) ? $detail_by_uuid[$uuid] : array();
			$total_lama = 0.0;
			$total_baru = 0.0;
			$bahan_t = '';
			$total_t = '';
			if ($pers) {
				$total_lama = $this->parse_angka_persediaan(isset($pers->total_10) ? $pers->total_10 : 0);
				$total_baru = $total_lama - $sum_jumlah;
				$bahan_t = $this->format_angka_persediaan($sum_jumlah);
				$total_t = $this->format_angka_persediaan($total_baru);
				$this->db->where('id', (int) $pers->id);
				$this->db->update('persediaan', array(
					'bahan_produksi' => $sum_jumlah,
					'total_10' => $total_t,
				));
				$updated++;
			}

			foreach ($details as $r) {
				$no++;
				$jumlah_row = $this->parse_angka_persediaan(isset($r->jumlah_bahan) ? $r->jumlah_bahan : 0);
				if (!$pers) {
					$unmatched++;
					$rows_out[] = array(
						'no' => $no,
						'id_bahan' => isset($r->id) ? (int) $r->id : 0,
						'tgl_transaksi' => isset($r->tgl_transaksi) ? (string) $r->tgl_transaksi : '',
						'nama_barang_bahan' => isset($r->nama_barang_bahan) ? (string) $r->nama_barang_bahan : '',
						'satuan_bahan' => isset($r->satuan_bahan) ? (string) $r->satuan_bahan : '',
						'jumlah_bahan' => $this->format_angka_persediaan($jumlah_row),
						'uuid_persediaan' => $uuid,
						'id_persediaan' => '',
						'namabarang' => '',
						'bahan_produksi' => '',
						'total_10_lama' => '',
						'total_10' => '',
						'status' => 'UNMATCHED',
						'keterangan' => 'uuid_persediaan tidak ditemukan di persediaan bulan target',
					);
					continue;
				}

				$rows_out[] = array(
					'no' => $no,
					'id_bahan' => isset($r->id) ? (int) $r->id : 0,
					'tgl_transaksi' => isset($r->tgl_transaksi) ? (string) $r->tgl_transaksi : '',
					'nama_barang_bahan' => isset($r->nama_barang_bahan) ? (string) $r->nama_barang_bahan : '',
					'satuan_bahan' => isset($r->satuan_bahan) ? (string) $r->satuan_bahan : '',
					'jumlah_bahan' => $this->format_angka_persediaan($jumlah_row),
					'uuid_persediaan' => $uuid,
					'id_persediaan' => (int) $pers->id,
					'namabarang' => isset($pers->namabarang) ? (string) $pers->namabarang : '',
					'bahan_produksi' => $bahan_t,
					'total_10_lama' => $this->format_angka_persediaan($total_lama),
					'total_10' => $total_t,
					'status' => 'UPDATED',
					'keterangan' => 'bahan_produksi=SUM(jumlah_bahan), total_10=total_10-SUM(jumlah_bahan)',
				);
			}
		}

		return array(
			'ok' => true,
			'rows' => $rows_out,
			'updated' => $updated,
			'unmatched_count' => $unmatched,
			'total_sumber' => count($sumber_rows),
			'tgl_awal' => $tgl_awal,
			'tgl_akhir' => $tgl_akhir,
		);
	}

	/**
	 * Update pecah_satuan dari tbl_pembelian_pecah_satuan (bulan target).
	 * Match uuid_persediaan → pecah_satuan = SUM(jumlah), total_10 = total_10 - SUM(jumlah).
	 */
	private function proses_generate_apply_pecah_satuan($bulan_target)
	{
		$ts = strtotime($bulan_target . '-01');
		if ($ts === false) {
			return array(
				'ok' => false,
				'rows' => array(),
				'updated' => 0,
				'unmatched_count' => 0,
				'total_sumber' => 0,
			);
		}

		if (!$this->db->table_exists('tbl_pembelian_pecah_satuan')) {
			return array(
				'ok' => false,
				'message' => 'Tabel tbl_pembelian_pecah_satuan tidak tersedia.',
				'rows' => array(),
				'updated' => 0,
				'unmatched_count' => 0,
				'total_sumber' => 0,
			);
		}

		$tahun = (int) date('Y', $ts);
		$bulan = (int) date('n', $ts);
		$tgl_awal = date('Y-m-01', $ts);
		$tgl_akhir = date('Y-m-t', $ts);

		$sumber_rows = $this->db->query(
			"SELECT `id`, `tgl_po`, `uuid_persediaan`, `uraian`, `jumlah`, `satuan`, `spop`,
				`harga_satuan`, `nama_barang_baru`, `jumlah_barang_baru`, `satuan_barang_baru`
			 FROM `tbl_pembelian_pecah_satuan`
			 WHERE `tgl_po` IS NOT NULL AND `tgl_po` <> '0000-00-00'
			   AND DATE(`tgl_po`) >= ? AND DATE(`tgl_po`) <= ?
			 ORDER BY `tgl_po` ASC, `id` ASC",
			array($tgl_awal, $tgl_akhir)
		)->result();

		$pers_rows = $this->db->query(
			"SELECT `id`, `uuid_persediaan`, `namabarang`, `satuan`, `hpp`, `total_10`, `pecah_satuan`
			 FROM `persediaan`
			 WHERE YEAR(`tanggal_beli`) = ? AND MONTH(`tanggal_beli`) = ?",
			array($tahun, $bulan)
		)->result();
		$by_uuid = array();
		foreach ($pers_rows as $prow) {
			$u = trim((string) (isset($prow->uuid_persediaan) ? $prow->uuid_persediaan : ''));
			if ($u === '') {
				continue;
			}
			if (!isset($by_uuid[$u])) {
				$by_uuid[$u] = array();
			}
			$by_uuid[$u][] = $prow;
		}

		$agg = array();
		$detail_by_uuid = array();
		foreach ($sumber_rows as $r) {
			$uuid = isset($r->uuid_persediaan) ? trim((string) $r->uuid_persediaan) : '';
			$jumlah = $this->parse_angka_persediaan(isset($r->jumlah) ? $r->jumlah : 0);
			if ($uuid === '' || $jumlah <= 0) {
				continue;
			}
			if (!isset($agg[$uuid])) {
				$agg[$uuid] = 0.0;
				$detail_by_uuid[$uuid] = array();
			}
			$agg[$uuid] += $jumlah;
			$detail_by_uuid[$uuid][] = $r;
		}

		$updated = 0;
		$rows_out = array();
		$unmatched = 0;
		$no = 0;

		foreach ($agg as $uuid => $sum_jumlah) {
			$pers = null;
			if (isset($by_uuid[$uuid]) && count($by_uuid[$uuid]) > 0) {
				$pers = $by_uuid[$uuid][0];
			}

			$details = isset($detail_by_uuid[$uuid]) ? $detail_by_uuid[$uuid] : array();
			$total_lama = 0.0;
			$total_baru = 0.0;
			$pecah_t = '';
			$total_t = '';
			if ($pers) {
				$total_lama = $this->parse_angka_persediaan(isset($pers->total_10) ? $pers->total_10 : 0);
				$total_baru = $total_lama - $sum_jumlah;
				$pecah_t = $this->format_angka_persediaan($sum_jumlah);
				$total_t = $this->format_angka_persediaan($total_baru);
				$this->db->where('id', (int) $pers->id);
				$this->db->update('persediaan', array(
					'pecah_satuan' => $sum_jumlah,
					'total_10' => $total_t,
				));
				$updated++;
			}

			foreach ($details as $r) {
				$no++;
				$jumlah_row = $this->parse_angka_persediaan(isset($r->jumlah) ? $r->jumlah : 0);
				if (!$pers) {
					$unmatched++;
					$rows_out[] = array(
						'no' => $no,
						'id_pecah' => isset($r->id) ? (int) $r->id : 0,
						'tgl_po' => isset($r->tgl_po) ? (string) $r->tgl_po : '',
						'uraian' => isset($r->uraian) ? (string) $r->uraian : '',
						'satuan' => isset($r->satuan) ? (string) $r->satuan : '',
						'jumlah' => $this->format_angka_persediaan($jumlah_row),
						'spop' => isset($r->spop) ? (string) $r->spop : '',
						'uuid_persediaan' => $uuid,
						'id_persediaan' => '',
						'namabarang' => '',
						'pecah_satuan' => '',
						'total_10_lama' => '',
						'total_10' => '',
						'status' => 'UNMATCHED',
						'keterangan' => 'uuid_persediaan tidak ditemukan di persediaan bulan target',
					);
					continue;
				}

				$rows_out[] = array(
					'no' => $no,
					'id_pecah' => isset($r->id) ? (int) $r->id : 0,
					'tgl_po' => isset($r->tgl_po) ? (string) $r->tgl_po : '',
					'uraian' => isset($r->uraian) ? (string) $r->uraian : '',
					'satuan' => isset($r->satuan) ? (string) $r->satuan : '',
					'jumlah' => $this->format_angka_persediaan($jumlah_row),
					'spop' => isset($r->spop) ? (string) $r->spop : '',
					'uuid_persediaan' => $uuid,
					'id_persediaan' => (int) $pers->id,
					'namabarang' => isset($pers->namabarang) ? (string) $pers->namabarang : '',
					'pecah_satuan' => $pecah_t,
					'total_10_lama' => $this->format_angka_persediaan($total_lama),
					'total_10' => $total_t,
					'status' => 'UPDATED',
					'keterangan' => 'pecah_satuan=SUM(jumlah), total_10=total_10-SUM(jumlah)',
				);
			}
		}

		return array(
			'ok' => true,
			'rows' => $rows_out,
			'updated' => $updated,
			'unmatched_count' => $unmatched,
			'total_sumber' => count($sumber_rows),
			'tgl_awal' => $tgl_awal,
			'tgl_akhir' => $tgl_akhir,
		);
	}

	/**
	 * Update penjualan dari tbl_penjualan (bulan target via tgl_jual).
	 * Match uuid_persediaan → jumlah ke kolom unit (konsumen_nama/unit),
	 * penjualan = penjualan + jumlah, total_10 = total_10 - jumlah.
	 */
	private function proses_generate_apply_penjualan($bulan_target)
	{
		$ts = strtotime($bulan_target . '-01');
		if ($ts === false) {
			return array(
				'ok' => false,
				'matched' => array(),
				'unmatched' => array(),
				'matched_count' => 0,
				'unmatched_count' => 0,
				'updated' => 0,
				'total_sumber' => 0,
			);
		}

		if (!$this->db->table_exists('tbl_penjualan')) {
			return array(
				'ok' => false,
				'message' => 'Tabel tbl_penjualan tidak tersedia.',
				'matched' => array(),
				'unmatched' => array(),
				'matched_count' => 0,
				'unmatched_count' => 0,
				'updated' => 0,
				'total_sumber' => 0,
			);
		}

		$this->load->helper(array('pembelian_persediaan', 'persediaan_display'));

		$tahun = (int) date('Y', $ts);
		$bulan = (int) date('n', $ts);
		$tgl_awal = date('Y-m-01', $ts);
		$tgl_akhir = date('Y-m-t', $ts);

		$sumber_rows = $this->db->query(
			"SELECT `id`, `tgl_jual`, `uuid_persediaan`, `uuid_unit`, `unit`, `uuid_konsumen`,
				`konsumen_nama`, `nama_barang`, `jumlah`, `satuan`, `nmrkirim`, `nmrpesan`
			 FROM `tbl_penjualan`
			 WHERE `tgl_jual` IS NOT NULL AND `tgl_jual` <> '0000-00-00'
			   AND DATE(`tgl_jual`) >= ? AND DATE(`tgl_jual`) <= ?
			 ORDER BY `tgl_jual` ASC, `id` ASC",
			array($tgl_awal, $tgl_akhir)
		)->result();

		$pers_rows = $this->db->query(
			"SELECT * FROM `persediaan`
			 WHERE YEAR(`tanggal_beli`) = ? AND MONTH(`tanggal_beli`) = ?",
			array($tahun, $bulan)
		)->result();
		$by_uuid = array();
		$by_id = array();
		$by_nama_satuan = array();
		foreach ($pers_rows as $prow) {
			$by_id[(int) $prow->id] = $prow;
			$u = trim((string) (isset($prow->uuid_persediaan) ? $prow->uuid_persediaan : ''));
			if ($u !== '') {
				if (!isset($by_uuid[$u])) {
					$by_uuid[$u] = array();
				}
				$by_uuid[$u][] = $prow;
			}
			$ns = $this->generate_nama_satuan_key(
				isset($prow->namabarang) ? $prow->namabarang : '',
				isset($prow->satuan) ? $prow->satuan : ''
			);
			if ($ns !== '') {
				if (!isset($by_nama_satuan[$ns])) {
					$by_nama_satuan[$ns] = array();
				}
				$by_nama_satuan[$ns][] = $prow;
			}
		}

		$agg = array();
		$matched = array();
		$pending = array();
		$no_m = 0;

		foreach ($sumber_rows as $r) {
			$uuid = isset($r->uuid_persediaan) ? trim((string) $r->uuid_persediaan) : '';
			$jumlah = $this->parse_angka_persediaan(isset($r->jumlah) ? $r->jumlah : 0);
			$konsumen = isset($r->konsumen_nama) ? trim((string) $r->konsumen_nama) : '';
			$kolom_unit = $this->generate_resolve_kolom_unit_dari_penjualan($r);

			$base = array(
				'tgl_jual' => isset($r->tgl_jual) ? (string) $r->tgl_jual : '',
				'nmrkirim' => isset($r->nmrkirim) ? (string) $r->nmrkirim : '',
				'nmrpesan' => isset($r->nmrpesan) ? (string) $r->nmrpesan : '',
				'nama_barang' => isset($r->nama_barang) ? (string) $r->nama_barang : '',
				'satuan' => isset($r->satuan) ? (string) $r->satuan : '',
				'jumlah' => $this->format_angka_persediaan($jumlah),
				'konsumen_nama' => $konsumen,
				'unit' => isset($r->unit) ? (string) $r->unit : '',
				'kolom_unit' => $kolom_unit ? $kolom_unit : '',
				'uuid_persediaan' => $uuid,
				'id_penjualan' => isset($r->id) ? (int) $r->id : 0,
			);

			$pers = null;
			if ($uuid !== '' && isset($by_uuid[$uuid]) && count($by_uuid[$uuid]) > 0) {
				$pers = $by_uuid[$uuid][0];
			}

			if (!$pers || $jumlah <= 0) {
				$base['keterangan'] = ($jumlah <= 0)
					? 'jumlah <= 0'
					: (($uuid === '')
						? 'uuid_persediaan kosong'
						: 'uuid_persediaan tidak ditemukan di persediaan bulan target');
				$pending[] = $base;
				continue;
			}

			$pid = (int) $pers->id;
			if (!isset($agg[$pid])) {
				$agg[$pid] = array(
					'jumlah' => 0.0,
					'units' => array(),
					'penjualan_lama' => $this->parse_angka_persediaan(isset($pers->penjualan) ? $pers->penjualan : 0),
					'total_10_lama' => $this->parse_angka_persediaan(isset($pers->total_10) ? $pers->total_10 : 0),
				);
			}
			$agg[$pid]['jumlah'] += $jumlah;
			if ($kolom_unit !== '' && $this->db->field_exists($kolom_unit, 'persediaan')) {
				if (!isset($agg[$pid]['units'][$kolom_unit])) {
					$agg[$pid]['units'][$kolom_unit] = $this->parse_angka_persediaan(
						isset($pers->{$kolom_unit}) ? $pers->{$kolom_unit} : 0
					);
				}
				$agg[$pid]['units'][$kolom_unit] += $jumlah;
			}

			$no_m++;
			$base['no'] = $no_m;
			$base['status'] = 'UPDATED';
			$base['id_persediaan'] = $pid;
			$base['namabarang'] = isset($pers->namabarang) ? (string) $pers->namabarang : '';
			$base['keterangan'] = $kolom_unit !== ''
				? 'jumlah → ' . $kolom_unit . '; penjualan+=jumlah; total_10-=jumlah'
				: 'penjualan+=jumlah; total_10-=jumlah (kolom unit tidak terpetakan)';
			$matched[] = $base;
		}

		$updated = 0;
		foreach ($agg as $pid => $info) {
			$pers = isset($by_id[$pid]) ? $by_id[$pid] : null;
			if (!$pers) {
				continue;
			}
			$penjualan_baru = $info['penjualan_lama'] + $info['jumlah'];
			$total_baru = $info['total_10_lama'] - $info['jumlah'];
			$data_upd = array(
				'penjualan' => (int) round($penjualan_baru),
				'total_10' => $this->format_angka_persediaan($total_baru),
			);
			foreach ($info['units'] as $kolom => $nilai) {
				$nilai_t = (abs($nilai - round($nilai)) < 0.0001)
					? (string) (int) round($nilai)
					: $this->format_angka_persediaan($nilai);
				$data_upd[$kolom] = $nilai_t;
			}
			$this->db->where('id', (int) $pid);
			$this->db->update('persediaan', $data_upd);
			$updated++;

			foreach ($matched as $mi => $mrow) {
				if ((int) $mrow['id_persediaan'] === (int) $pid) {
					$matched[$mi]['penjualan'] = $this->format_angka_persediaan($penjualan_baru);
					$matched[$mi]['total_10'] = $this->format_angka_persediaan($total_baru);
					$matched[$mi]['nilai_kolom'] = isset($mrow['kolom_unit']) && $mrow['kolom_unit'] !== ''
						&& isset($info['units'][$mrow['kolom_unit']])
						? $this->format_angka_persediaan($info['units'][$mrow['kolom_unit']])
						: '';
				}
			}
		}

		$unmatched = array();
		$no_u = 0;
		foreach ($pending as $base) {
			$jumlah = $this->parse_angka_persediaan(isset($base['jumlah']) ? $base['jumlah'] : 0);
			$kolom_unit = isset($base['kolom_unit']) ? (string) $base['kolom_unit'] : '';
			if ($jumlah <= 0) {
				$no_u++;
				$base['no'] = $no_u;
				$base['status'] = 'UNMATCHED';
				$base['id_persediaan'] = '';
				$base['penjualan'] = '';
				$base['total_10'] = '';
				$unmatched[] = $base;
				continue;
			}

			$ns = $this->generate_nama_satuan_key(
				isset($base['nama_barang']) ? $base['nama_barang'] : '',
				isset($base['satuan']) ? $base['satuan'] : ''
			);
			$pers = null;
			if ($ns !== '' && isset($by_nama_satuan[$ns]) && count($by_nama_satuan[$ns]) > 0) {
				$pers = $by_nama_satuan[$ns][0];
			}

			if (!$pers) {
				$no_u++;
				$base['no'] = $no_u;
				$base['status'] = 'UNMATCHED';
				$base['id_persediaan'] = '';
				$base['penjualan'] = '';
				$base['total_10'] = '';
				$base['keterangan'] = (isset($base['keterangan']) ? $base['keterangan'] . '; ' : '')
					. 'nama_barang+satuan tidak ditemukan di persediaan bulan target';
				$unmatched[] = $base;
				continue;
			}

			$applied = $this->generate_apply_penjualan_qty_ke_persediaan_row((int) $pers->id, $jumlah, $kolom_unit);
			if (!$applied) {
				$no_u++;
				$base['no'] = $no_u;
				$base['status'] = 'UNMATCHED';
				$base['id_persediaan'] = '';
				$unmatched[] = $base;
				continue;
			}

			$updated++;
			$no_m++;
			$base['no'] = $no_m;
			$base['status'] = 'UPDATED';
			$base['id_persediaan'] = (int) $applied['id_persediaan'];
			$base['penjualan'] = $applied['penjualan'];
			$base['total_10'] = $applied['total_10'];
			$base['namabarang'] = isset($pers->namabarang) ? (string) $pers->namabarang : '';
			$base['keterangan'] = 'match nama_barang+satuan; jumlah → ' . ($kolom_unit !== '' ? $kolom_unit : 'tanpa kolom unit')
				. '; penjualan+=jumlah; total_10-=jumlah';
			$matched[] = $base;
		}

		return array(
			'ok' => true,
			'matched' => $matched,
			'unmatched' => $unmatched,
			'matched_count' => count($matched),
			'unmatched_count' => count($unmatched),
			'updated' => $updated,
			'total_sumber' => count($sumber_rows),
			'tgl_awal' => $tgl_awal,
			'tgl_akhir' => $tgl_akhir,
		);
	}

	private function generate_nama_satuan_key($nama, $satuan)
	{
		$n = strtolower(preg_replace('/\s+/', ' ', trim((string) $nama)));
		$s = strtolower(preg_replace('/\s+/', ' ', trim((string) $satuan)));
		if ($n === '' || $s === '') {
			return '';
		}
		return $n . '|' . $s;
	}

	/**
	 * Jumlah penjualan ke kolom unit (konsumen), penjualan+=jumlah, total_10-=jumlah (stok keluar).
	 */
	private function generate_apply_penjualan_qty_ke_persediaan_row($id_persediaan, $jumlah, $kolom_unit)
	{
		$id_persediaan = (int) $id_persediaan;
		$pers = $this->db->where('id', $id_persediaan)->limit(1)->get('persediaan')->row();
		if (!$pers) {
			return null;
		}
		$jumlah = (float) $jumlah;
		if ($jumlah <= 0) {
			return null;
		}
		$penjualan_baru = $this->parse_angka_persediaan(isset($pers->penjualan) ? $pers->penjualan : 0) + $jumlah;
		$total_baru = $this->parse_angka_persediaan(isset($pers->total_10) ? $pers->total_10 : 0) - $jumlah;
		$data_upd = array(
			'penjualan' => (int) round($penjualan_baru),
			'total_10' => $this->format_angka_persediaan($total_baru),
		);
		$kolom_unit = trim((string) $kolom_unit);
		if ($kolom_unit !== '' && $this->db->field_exists($kolom_unit, 'persediaan')) {
			$nilai = $this->parse_angka_persediaan(isset($pers->{$kolom_unit}) ? $pers->{$kolom_unit} : 0) + $jumlah;
			$data_upd[$kolom_unit] = (abs($nilai - round($nilai)) < 0.0001)
				? (string) (int) round($nilai)
				: $this->format_angka_persediaan($nilai);
		}
		$this->db->where('id', $id_persediaan);
		$this->db->update('persediaan', $data_upd);

		return array(
			'id_persediaan' => $id_persediaan,
			'penjualan' => $this->format_angka_persediaan($penjualan_baru),
			'total_10' => $this->format_angka_persediaan($total_baru),
			'kolom_unit' => $kolom_unit,
		);
	}

	/**
	 * Petakan unit/konsumen penjualan ke nama kolom persediaan.
	 */
	private function generate_resolve_kolom_unit_dari_penjualan($row_penjualan)
	{
		$this->load->helper(array('pembelian_persediaan', 'persediaan_display'));
		$kolom = penjualan_resolve_kolom_persediaan_unit_dari_penjualan($this, $row_penjualan, true);
		if ($kolom) {
			return persediaan_resolve_db_field_name($this, $kolom);
		}

		$kelompok = '';
		$uuid_k = isset($row_penjualan->uuid_konsumen) ? trim((string) $row_penjualan->uuid_konsumen) : '';
		$nama_k = isset($row_penjualan->konsumen_nama) ? trim((string) $row_penjualan->konsumen_nama) : '';
		if ($this->db->table_exists('sys_konsumen')) {
			if ($uuid_k !== '') {
				$row_k = $this->db->select('kelompok_dipersediaan')
					->where('uuid_konsumen', $uuid_k)
					->limit(1)
					->get('sys_konsumen')
					->row();
				if ($row_k && isset($row_k->kelompok_dipersediaan)) {
					$kelompok = trim((string) $row_k->kelompok_dipersediaan);
				}
			}
			if ($kelompok === '' && $nama_k !== '') {
				$row_k = $this->db->query(
					"SELECT `kelompok_dipersediaan` FROM `sys_konsumen`
					 WHERE LOWER(TRIM(COALESCE(`nama_konsumen`, ''))) = LOWER(?)
					 LIMIT 1",
					array($nama_k)
				)->row();
				if ($row_k && isset($row_k->kelompok_dipersediaan)) {
					$kelompok = trim((string) $row_k->kelompok_dipersediaan);
				}
			}
		}

		foreach (array($kelompok, $nama_k) as $txt) {
			if ($txt === '') {
				continue;
			}
			$kolom_txt = persediaan_resolve_unit_column_from_text($this, $txt);
			if ($kolom_txt) {
				return persediaan_resolve_db_field_name($this, $kolom_txt);
			}
		}

		return '';
	}

	/**
	 * Rekonsiliasi total nilai_persediaan & total_10 sumber vs target setelah generate.
	 * Mode data dasar: bandingkan semua record sumber total_10>0 terhadap target (match uuid_persediaan).
	 */
	private function rekon_nilai_persediaan_sumber_vs_target(
		$tahun_sumber,
		$bulan_sumber,
		$tahun_target,
		$bulan_target,
		$only_total10_gt0 = true
	) {
		$tahun_sumber = (int) $tahun_sumber;
		$bulan_sumber = (int) $bulan_sumber;
		$tahun_target = (int) $tahun_target;
		$bulan_target = (int) $bulan_target;

		$sql_filter_t10 = $only_total10_gt0
			? " AND CAST(REPLACE(REPLACE(TRIM(COALESCE(`total_10`,'0')), ',', ''), ' ', '') AS DECIMAL(20,4)) > 0"
			: '';

		$sumber = $this->db->query(
			"SELECT * FROM `persediaan`
			WHERE YEAR(`tanggal_beli`) = ? AND MONTH(`tanggal_beli`) = ?" . $sql_filter_t10 . "
			ORDER BY `id` ASC",
			array($tahun_sumber, $bulan_sumber)
		)->result();

		$target = $this->db->query(
			"SELECT * FROM `persediaan`
			WHERE YEAR(`tanggal_beli`) = ? AND MONTH(`tanggal_beli`) = ?
			ORDER BY `id` ASC",
			array($tahun_target, $bulan_target)
		)->result();

		$sum_nilai_sumber = 0.0;
		$sum_nilai_target = 0.0;
		$sum_t10_sumber = 0.0;
		$sum_t10_target = 0.0;

		$target_by_uuid = array();
		$target_used = array();
		foreach ($target as $trow) {
			$sum_nilai_target += $this->parse_angka_persediaan(isset($trow->nilai_persediaan) ? $trow->nilai_persediaan : 0);
			$sum_t10_target += $this->parse_angka_persediaan(isset($trow->total_10) ? $trow->total_10 : 0);
			$uuid = trim((string) (isset($trow->uuid_persediaan) ? $trow->uuid_persediaan : ''));
			$key = $uuid !== ''
				? 'u:' . $uuid
				: 'n:' . strtolower(trim((string) (isset($trow->namabarang) ? $trow->namabarang : '')))
					. '|' . strtolower(trim((string) (isset($trow->satuan) ? $trow->satuan : '')))
					. '|' . $this->parse_angka_persediaan(isset($trow->hpp) ? $trow->hpp : 0)
					. '|' . trim((string) (isset($trow->spop) ? $trow->spop : ''));
			if (!isset($target_by_uuid[$key])) {
				$target_by_uuid[$key] = array();
			}
			$target_by_uuid[$key][] = $trow;
		}

		$masalah = array();
		$no = 0;
		foreach ($sumber as $srow) {
			$nilai_s = $this->parse_angka_persediaan(isset($srow->nilai_persediaan) ? $srow->nilai_persediaan : 0);
			$t10_s = $this->parse_angka_persediaan(isset($srow->total_10) ? $srow->total_10 : 0);
			$sum_nilai_sumber += $nilai_s;
			$sum_t10_sumber += $t10_s;

			$uuid = trim((string) (isset($srow->uuid_persediaan) ? $srow->uuid_persediaan : ''));
			$key = $uuid !== ''
				? 'u:' . $uuid
				: 'n:' . strtolower(trim((string) (isset($srow->namabarang) ? $srow->namabarang : '')))
					. '|' . strtolower(trim((string) (isset($srow->satuan) ? $srow->satuan : '')))
					. '|' . $this->parse_angka_persediaan(isset($srow->hpp) ? $srow->hpp : 0)
					. '|' . trim((string) (isset($srow->spop) ? $srow->spop : ''));

			$trow = null;
			if (isset($target_by_uuid[$key]) && count($target_by_uuid[$key]) > 0) {
				$trow = array_shift($target_by_uuid[$key]);
				$target_used[(int) $trow->id] = true;
			}

			$masalah_tipe = array();
			$nilai_t = 0.0;
			$t10_t = 0.0;
			$id_target = 0;

			if (!$trow) {
				$masalah_tipe[] = 'TIDAK_ADA_DI_TARGET';
			} else {
				$id_target = (int) $trow->id;
				$nilai_t = $this->parse_angka_persediaan(isset($trow->nilai_persediaan) ? $trow->nilai_persediaan : 0);
				$t10_t = $this->parse_angka_persediaan(isset($trow->total_10) ? $trow->total_10 : 0);
				if (abs($nilai_s - $nilai_t) > 0.0001) {
					$masalah_tipe[] = 'NILAI_PERSEDIAAN_BEDA';
				}
				if (abs($t10_s - $t10_t) > 0.0001) {
					$masalah_tipe[] = 'TOTAL_10_BEDA';
				}
			}

			if (!empty($masalah_tipe)) {
				$no++;
				$masalah[] = array(
					'no' => $no,
					'id_sumber' => isset($srow->id) ? (int) $srow->id : 0,
					'id_target' => $id_target,
					'uuid_persediaan' => $uuid,
					'namabarang' => isset($srow->namabarang) ? (string) $srow->namabarang : '',
					'satuan' => isset($srow->satuan) ? (string) $srow->satuan : '',
					'hpp' => isset($srow->hpp) ? (string) $srow->hpp : '',
					'total_10_sumber' => $this->format_angka_persediaan($t10_s),
					'total_10_target' => $this->format_angka_persediaan($t10_t),
					'nilai_sumber' => $this->format_angka_persediaan($nilai_s),
					'nilai_target' => $this->format_angka_persediaan($nilai_t),
					'selisih_nilai' => $this->format_angka_persediaan($nilai_t - $nilai_s),
					'selisih_total_10' => $this->format_angka_persediaan($t10_t - $t10_s),
					'masalah' => implode(', ', $masalah_tipe),
				);
			}
		}

		// Target ekstra yang tidak punya pasangan sumber
		foreach ($target as $trow) {
			$id_t = isset($trow->id) ? (int) $trow->id : 0;
			if (isset($target_used[$id_t])) {
				continue;
			}
			$no++;
			$nilai_t = $this->parse_angka_persediaan(isset($trow->nilai_persediaan) ? $trow->nilai_persediaan : 0);
			$t10_t = $this->parse_angka_persediaan(isset($trow->total_10) ? $trow->total_10 : 0);
			$masalah[] = array(
				'no' => $no,
				'id_sumber' => 0,
				'id_target' => $id_t,
				'uuid_persediaan' => isset($trow->uuid_persediaan) ? (string) $trow->uuid_persediaan : '',
				'namabarang' => isset($trow->namabarang) ? (string) $trow->namabarang : '',
				'satuan' => isset($trow->satuan) ? (string) $trow->satuan : '',
				'hpp' => isset($trow->hpp) ? (string) $trow->hpp : '',
				'total_10_sumber' => '0',
				'total_10_target' => $this->format_angka_persediaan($t10_t),
				'nilai_sumber' => '0',
				'nilai_target' => $this->format_angka_persediaan($nilai_t),
				'selisih_nilai' => $this->format_angka_persediaan($nilai_t),
				'selisih_total_10' => $this->format_angka_persediaan($t10_t),
				'masalah' => 'EXTRA_DI_TARGET',
			);
		}

		$selisih_nilai = $sum_nilai_target - $sum_nilai_sumber;
		$selisih_t10 = $sum_t10_target - $sum_t10_sumber;
		$ok = (abs($selisih_nilai) <= 0.0001)
			&& (abs($selisih_t10) <= 0.0001)
			&& (count($masalah) === 0)
			&& (count($sumber) === count($target));

		return array(
			'ok' => $ok,
			'count_sumber' => count($sumber),
			'count_target' => count($target),
			'sum_nilai_sumber' => $sum_nilai_sumber,
			'sum_nilai_target' => $sum_nilai_target,
			'selisih_nilai' => $selisih_nilai,
			'sum_total10_sumber' => $sum_t10_sumber,
			'sum_total10_target' => $sum_t10_target,
			'selisih_total10' => $selisih_t10,
			'count_masalah' => count($masalah),
			'masalah' => $masalah,
		);
	}

	/**
	 * Pastikan tabel history draft referensi ada.
	 */
	private function ensure_persediaan_draft_bulan_referensi_table()
	{
		if ($this->db->table_exists('persediaan_draft_bulan_referensi')) {
			return true;
		}

		$sql_file = FCPATH . 'database/sql/persediaan_draft_bulan_referensi.sql';
		if (is_file($sql_file)) {
			$sql = @file_get_contents($sql_file);
			if (is_string($sql) && trim($sql) !== '') {
				$this->db->query($sql);
			}
		}

		return $this->db->table_exists('persediaan_draft_bulan_referensi');
	}

	/**
	 * Simpan snapshot bulan sebelumnya ke history draft (replace per bulan_target).
	 */
	private function simpan_persediaan_draft_bulan_referensi($bulan_target, $bulan_sumber, $rows_sumber, $rows_preview)
	{
		if (!$this->ensure_persediaan_draft_bulan_referensi_table()) {
			return 0;
		}

		$bulan_target = trim((string) $bulan_target);
		$bulan_sumber = trim((string) $bulan_sumber);
		if ($bulan_target === '' || !preg_match('/^\d{4}-\d{2}$/', $bulan_target)) {
			return 0;
		}

		$status_by_id = array();
		if (is_array($rows_preview)) {
			foreach ($rows_preview as $prev) {
				$id_src = isset($prev['id']) ? (int) $prev['id'] : 0;
				if ($id_src > 0) {
					$status_by_id[$id_src] = isset($prev['status_copy']) ? (string) $prev['status_copy'] : '';
				}
			}
		}

		$this->db->where('bulan_target', $bulan_target);
		$this->db->delete('persediaan_draft_bulan_referensi');

		$draft_fields = $this->db->list_fields('persediaan_draft_bulan_referensi');
		$skip_fields = array('id', 'id_sumber', 'bulan_target', 'bulan_sumber', 'status_copy', 'created_at');
		$saved = 0;
		$now = date('Y-m-d H:i:s');

		foreach ($rows_sumber as $row) {
			$id_sumber = isset($row->id) ? (int) $row->id : 0;
			$total_10 = $this->parse_angka_persediaan(isset($row->total_10) ? $row->total_10 : 0);
			// History referensi: simpan yang total_10 > 0 (dasar generate)
			if ($total_10 <= 0) {
				continue;
			}

			$data = array(
				'id_sumber' => $id_sumber,
				'bulan_target' => $bulan_target,
				'bulan_sumber' => $bulan_sumber,
				'status_copy' => isset($status_by_id[$id_sumber]) ? $status_by_id[$id_sumber] : 'COPIED',
				'created_at' => $now,
			);

			foreach ($draft_fields as $field) {
				if (in_array($field, $skip_fields, true)) {
					continue;
				}
				$data[$field] = isset($row->$field) ? $row->$field : null;
			}

			if ($this->db->insert('persediaan_draft_bulan_referensi', $data)) {
				$saved++;
			}
		}

		return $saved;
	}

	/**
	 * Ambil history draft referensi untuk bulan target yang sedang dilihat di tab Persediaan.
	 */
	private function get_persediaan_draft_bulan_referensi_by_target($bulan_target)
	{
		$bulan_target = trim((string) $bulan_target);
		if ($bulan_target === '' || !preg_match('/^\d{4}-\d{2}$/', $bulan_target)) {
			return array();
		}
		if (!$this->ensure_persediaan_draft_bulan_referensi_table()) {
			return array();
		}

		return $this->db->query(
			"SELECT * FROM `persediaan_draft_bulan_referensi`
			WHERE `bulan_target` = ?
			ORDER BY `id` ASC",
			array($bulan_target)
		)->result();
	}

	/**
	 * Label bulan sumber dari draft terbaru untuk bulan target.
	 */
	private function get_persediaan_draft_bulan_referensi_sumber_label($bulan_target)
	{
		$bulan_target = trim((string) $bulan_target);
		if ($bulan_target === '' || !preg_match('/^\d{4}-\d{2}$/', $bulan_target)) {
			return '';
		}
		if (!$this->ensure_persediaan_draft_bulan_referensi_table()) {
			return '';
		}

		$row = $this->db->query(
			"SELECT `bulan_sumber` FROM `persediaan_draft_bulan_referensi`
			WHERE `bulan_target` = ?
			ORDER BY `id` DESC
			LIMIT 1",
			array($bulan_target)
		)->row();

		if (!$row || empty($row->bulan_sumber)) {
			$ts = strtotime($bulan_target . '-01');
			return ($ts !== false) ? date('Y-m', strtotime('-1 month', $ts)) : '';
		}

		return (string) $row->bulan_sumber;
	}

	/**
	 * Hapus semua record persediaan untuk bulan+tahun tertentu (berdasarkan tanggal_beli).
	 */
	private function generate_hapus_persediaan_bulan_tahun($tahun, $bulan)
	{
		$tahun = (int) $tahun;
		$bulan = (int) $bulan;
		if ($tahun < 2000 || $bulan < 1 || $bulan > 12) {
			return 0;
		}

		$row_cnt = $this->db->query(
			"SELECT COUNT(*) AS jml FROM `persediaan`
			WHERE YEAR(`tanggal_beli`) = ? AND MONTH(`tanggal_beli`) = ?",
			array($tahun, $bulan)
		)->row();
		$count = $row_cnt ? (int) $row_cnt->jml : 0;
		if ($count > 0) {
			$this->db->query(
				"DELETE FROM `persediaan`
				WHERE YEAR(`tanggal_beli`) = ? AND MONTH(`tanggal_beli`) = ?",
				array($tahun, $bulan)
			);
		}

		return $count;
	}

	/**
	 * AJAX: tampilan box Persediaan Proses Generate (datatable lengkap bulan sumber & target).
	 */
	public function ajax_generate_proses_persediaan_view()
	{
		$this->load->helper(array('pembelian_persediaan', 'persediaan_display'));

		try {
			if (!$this->persediaan_user_can_generate()) {
				persediaan_ajax_json_output($this, array(
					'ok' => false,
					'message' => $this->persediaan_restricted_access_message('Generate Persediaan'),
				));
				return;
			}

			$bulan = trim((string) $this->input->get_post('bulan', TRUE));
			if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
				persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).'));
				return;
			}

			$package = persediaan_generate_proses_package($this, $bulan);
			if (empty($package['ok'])) {
				persediaan_ajax_json_output($this, array(
					'ok' => false,
					'message' => isset($package['message']) ? $package['message'] : 'Gagal memuat data proses generate.',
				));
				return;
			}

			$html = $this->load->view(
				'anekadharma/persediaan/_generate_proses_persediaan_box',
				$package,
				true
			);

			persediaan_ajax_json_output($this, array(
				'ok' => true,
				'html' => $html,
				'rekap' => isset($package['rekap']) ? $package['rekap'] : array(),
				'bulan_target_label' => isset($package['bulan_target_label']) ? $package['bulan_target_label'] : '',
				'bulan_sumber_label' => isset($package['bulan_sumber_label']) ? $package['bulan_sumber_label'] : '',
			));
		} catch (Exception $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Error: ' . $e->getMessage()));
		} catch (Throwable $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Error: ' . $e->getMessage()));
		}
	}

	/**
	 * AJAX: tampilan box Proses Pembelian (verifikasi + datatable tbl_pembelian / jasa).
	 */
	public function ajax_generate_proses_pembelian_view()
	{
		$this->load->helper(array('pembelian_persediaan', 'persediaan_display'));

		try {
			if (!$this->persediaan_user_can_generate()) {
				persediaan_ajax_json_output($this, array(
					'ok' => false,
					'message' => $this->persediaan_restricted_access_message('Generate Persediaan'),
				));
				return;
			}

			$bulan = trim((string) $this->input->get_post('bulan', TRUE));
			if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
				persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).'));
				return;
			}

			$package = persediaan_generate_proses_pembelian_package($this, $bulan);
			if (empty($package['ok'])) {
				persediaan_ajax_json_output($this, array(
					'ok' => false,
					'message' => isset($package['message']) ? $package['message'] : 'Gagal memuat data proses pembelian.',
				));
				return;
			}

			$html = $this->load->view(
				'anekadharma/persediaan/_generate_proses_pembelian_box',
				$package,
				true
			);

			persediaan_ajax_json_output($this, array(
				'ok' => true,
				'html' => $html,
				'rekap' => isset($package['rekap']) ? $package['rekap'] : array(),
				'bulan_target_label' => isset($package['bulan_target_label']) ? $package['bulan_target_label'] : '',
			));
		} catch (Exception $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Error: ' . $e->getMessage()));
		} catch (Throwable $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Error: ' . $e->getMessage()));
		}
	}

	/**
	 * AJAX: tampilan box Proses Produksi (verifikasi + datatable sys_unit_produk).
	 */
	public function ajax_generate_proses_produksi_view()
	{
		$this->load->helper(array('pembelian_persediaan', 'persediaan_display'));

		try {
			if (!$this->persediaan_user_can_generate()) {
				persediaan_ajax_json_output($this, array(
					'ok' => false,
					'message' => $this->persediaan_restricted_access_message('Generate Persediaan'),
				));
				return;
			}

			$bulan = trim((string) $this->input->get_post('bulan', TRUE));
			if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
				persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).'));
				return;
			}

			$package = persediaan_generate_proses_produksi_package($this, $bulan);
			if (empty($package['ok'])) {
				persediaan_ajax_json_output($this, array(
					'ok' => false,
					'message' => isset($package['message']) ? $package['message'] : 'Gagal memuat data proses produksi.',
				));
				return;
			}

			$html = $this->load->view(
				'anekadharma/persediaan/_generate_proses_produksi_box',
				$package,
				true
			);

			persediaan_ajax_json_output($this, array(
				'ok' => true,
				'html' => $html,
				'rekap' => isset($package['rekap']) ? $package['rekap'] : array(),
				'bulan_target_label' => isset($package['bulan_target_label']) ? $package['bulan_target_label'] : '',
			));
		} catch (Exception $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Error: ' . $e->getMessage()));
		} catch (Throwable $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Error: ' . $e->getMessage()));
		}
	}

	/**
	 * AJAX: tampilan box Proses Pecah Satuan (verifikasi + datatable tbl_pembelian_pecah_satuan).
	 */
	public function ajax_generate_proses_pecah_satuan_view()
	{
		$this->load->helper(array('pembelian_persediaan', 'persediaan_display'));

		try {
			if (!$this->persediaan_user_can_generate()) {
				persediaan_ajax_json_output($this, array(
					'ok' => false,
					'message' => $this->persediaan_restricted_access_message('Generate Persediaan'),
				));
				return;
			}

			$bulan = trim((string) $this->input->get_post('bulan', TRUE));
			if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
				persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).'));
				return;
			}

			$package = persediaan_generate_proses_pecah_satuan_package($this, $bulan);
			if (empty($package['ok'])) {
				persediaan_ajax_json_output($this, array(
					'ok' => false,
					'message' => isset($package['message']) ? $package['message'] : 'Gagal memuat data proses pecah satuan.',
				));
				return;
			}

			$html = $this->load->view(
				'anekadharma/persediaan/_generate_proses_pecah_satuan_box',
				$package,
				true
			);

			persediaan_ajax_json_output($this, array(
				'ok' => true,
				'html' => $html,
				'rekap' => isset($package['rekap']) ? $package['rekap'] : array(),
				'bulan_target_label' => isset($package['bulan_target_label']) ? $package['bulan_target_label'] : '',
			));
		} catch (Exception $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Error: ' . $e->getMessage()));
		} catch (Throwable $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Error: ' . $e->getMessage()));
		}
	}

	/**
	 * AJAX: tampilan box Proses Penjualan (verifikasi + datatable tbl_penjualan).
	 */
	public function ajax_generate_proses_penjualan_view()
	{
		$this->load->helper(array('pembelian_persediaan', 'persediaan_display'));

		try {
			if (!$this->persediaan_user_can_generate()) {
				persediaan_ajax_json_output($this, array(
					'ok' => false,
					'message' => $this->persediaan_restricted_access_message('Generate Persediaan'),
				));
				return;
			}

			$bulan = trim((string) $this->input->get_post('bulan', TRUE));
			if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
				persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).'));
				return;
			}

			$package = persediaan_generate_proses_penjualan_package($this, $bulan);
			if (empty($package['ok'])) {
				persediaan_ajax_json_output($this, array(
					'ok' => false,
					'message' => isset($package['message']) ? $package['message'] : 'Gagal memuat data proses penjualan.',
				));
				return;
			}

			$html = $this->load->view(
				'anekadharma/persediaan/_generate_proses_penjualan_box',
				$package,
				true
			);

			persediaan_ajax_json_output($this, array(
				'ok' => true,
				'html' => $html,
				'rekap' => isset($package['rekap']) ? $package['rekap'] : array(),
				'bulan_target_label' => isset($package['bulan_target_label']) ? $package['bulan_target_label'] : '',
			));
		} catch (Exception $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Error: ' . $e->getMessage()));
		} catch (Throwable $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Error: ' . $e->getMessage()));
		}
	}

	/**
	 * AJAX: cari record persediaan mirip untuk solusi penjualan gagal.
	 */
	public function ajax_gen_penjualan_cari_persediaan_mirip()
	{
		$this->load->helper(array('pembelian_persediaan', 'persediaan_display'));

		if (!$this->persediaan_user_can_generate()) {
			persediaan_ajax_json_output($this, array(
				'ok' => false,
				'message' => $this->persediaan_restricted_access_message('Solusi penjualan'),
			));
			return;
		}

		$bulan = trim((string) $this->input->get_post('bulan', TRUE));
		if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).'));
			return;
		}

		$id_penjualan = (int) $this->input->get_post('id_penjualan', TRUE);
		if ($id_penjualan <= 0) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'ID penjualan tidak valid.'));
			return;
		}

		$ctx = persediaan_gen_v2_penjualan_ctx($bulan);
		if (empty($ctx['ok'])) {
			persediaan_ajax_json_output($this, $ctx);
			return;
		}

		$row_pen = $this->db->where('id', $id_penjualan)->limit(1)->get('tbl_penjualan')->row();
		if (!$row_pen) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Record penjualan tidak ditemukan.'));
			return;
		}

		$map = persediaan_gen_v2_build_map_persediaan_bulan_range($this, $ctx['tgl_awal'], $ctx['tgl_akhir']);
		$result = persediaan_gen_v2_cari_persediaan_mirip_penjualan($this, $ctx, $row_pen, 25, $map);
		$result['penjualan'] = array(
			'id' => (int) $row_pen->id,
			'nama_barang' => isset($row_pen->nama_barang) ? (string) $row_pen->nama_barang : '',
			'satuan' => isset($row_pen->satuan) ? (string) $row_pen->satuan : '',
			'hpp' => isset($row_pen->harga_satuan) ? $row_pen->harga_satuan : '',
			'jumlah' => isset($row_pen->jumlah) ? $row_pen->jumlah : '',
			'unit' => isset($row_pen->unit) ? (string) $row_pen->unit : '',
		);
		persediaan_ajax_json_output($this, $result);
	}

	/**
	 * AJAX: proses penjualan ke record persediaan terpilih (tombol Solusi).
	 */
	public function ajax_gen_penjualan_apply_persediaan()
	{
		$this->load->helper(array('pembelian_persediaan', 'persediaan_display'));

		if (!$this->persediaan_user_can_generate()) {
			persediaan_ajax_json_output($this, array(
				'ok' => false,
				'message' => $this->persediaan_restricted_access_message('Solusi penjualan'),
			));
			return;
		}

		if (strtolower($this->input->method()) !== 'post') {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Method tidak valid.'));
			return;
		}

		$bulan = trim((string) $this->input->post('bulan', TRUE));
		if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).'));
			return;
		}

		$id_penjualan = (int) $this->input->post('id_penjualan', TRUE);
		$id_persediaan = (int) $this->input->post('id_persediaan', TRUE);
		$result = persediaan_gen_v2_apply_penjualan_ke_persediaan($this, $bulan, $id_penjualan, $id_persediaan);
		persediaan_ajax_json_output($this, $result);
	}

	/**
	 * AJAX: cari persediaan sumber untuk proses manual record pecah satuan gagal.
	 */
	public function ajax_gen_pecah_cari_persediaan_sumber()
	{
		$this->load->helper(array('pembelian_persediaan', 'persediaan_display'));

		if (!$this->persediaan_user_can_generate()) {
			persediaan_ajax_json_output($this, array(
				'ok' => false,
				'message' => $this->persediaan_restricted_access_message('Solusi pecah satuan'),
			));
			return;
		}

		$bulan = trim((string) $this->input->get_post('bulan', TRUE));
		if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).'));
			return;
		}

		$id_pecah_satuan = (int) $this->input->get_post('id_pecah_satuan', TRUE);
		if ($id_pecah_satuan <= 0) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'ID pecah satuan tidak valid.'));
			return;
		}

		$result = persediaan_gen_v2_pecah_satuan_cari_persediaan_sumber($this, $bulan, $id_pecah_satuan, 60);
		persediaan_ajax_json_output($this, $result);
	}

	/**
	 * AJAX: proses manual 1 record pecah satuan gagal.
	 */
	public function ajax_gen_pecah_proses_record()
	{
		$this->load->helper(array('pembelian_persediaan', 'persediaan_display'));

		if (!$this->persediaan_user_can_generate()) {
			persediaan_ajax_json_output($this, array(
				'ok' => false,
				'message' => $this->persediaan_restricted_access_message('Proses record pecah satuan'),
			));
			return;
		}

		if (strtolower($this->input->method()) !== 'post') {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Method tidak valid.'));
			return;
		}

		$bulan = trim((string) $this->input->post('bulan', TRUE));
		if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).'));
			return;
		}

		$id_pecah_satuan = (int) $this->input->post('id_pecah_satuan', TRUE);
		$id_persediaan_sumber = (int) $this->input->post('id_persediaan_sumber', TRUE);
		$result = persediaan_gen_v2_pecah_satuan_proses_record($this, $bulan, $id_pecah_satuan, $id_persediaan_sumber);
		persediaan_ajax_json_output($this, $result);
	}

	/**
	 * AJAX: penyesuaian pecah satuan dari modal penjualan gagal.
	 */
	public function ajax_gen_penjualan_penyesuaian_pecah()
	{
		$this->load->helper(array('pembelian_persediaan', 'persediaan_display'));

		if (!$this->persediaan_user_can_generate()) {
			persediaan_ajax_json_output($this, array(
				'ok' => false,
				'message' => $this->persediaan_restricted_access_message('Penyesuaian pecah satuan'),
			));
			return;
		}

		if (strtolower($this->input->method()) !== 'post') {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Method tidak valid.'));
			return;
		}

		$bulan = trim((string) $this->input->post('bulan', TRUE));
		if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).'));
			return;
		}

		$payload = array(
			'id_penjualan' => $this->input->post('id_penjualan', TRUE),
			'id_persediaan_sumber' => $this->input->post('id_persediaan_sumber', TRUE),
			'jumlah_pecah' => $this->input->post('jumlah_pecah', TRUE),
			'jumlah_barang_baru' => $this->input->post('jumlah_barang_baru', TRUE),
			'nama_barang_baru' => $this->input->post('nama_barang_baru', TRUE),
			'satuan_barang_baru' => $this->input->post('satuan_barang_baru', TRUE),
			'harga_satuan_barang_baru' => $this->input->post('harga_satuan_barang_baru', TRUE),
		);
		$result = persediaan_gen_v2_penyesuaian_pecah_satuan_dari_penjualan($this, $bulan, $payload);
		persediaan_ajax_json_output($this, $result);
	}

	/**
	 * AJAX: penyesuaian produksi dari modal penjualan gagal.
	 */
	public function ajax_gen_penjualan_penyesuaian_produksi()
	{
		$this->load->helper(array('pembelian_persediaan', 'persediaan_display'));

		if (!$this->persediaan_user_can_generate()) {
			persediaan_ajax_json_output($this, array(
				'ok' => false,
				'message' => $this->persediaan_restricted_access_message('Penyesuaian produksi'),
			));
			return;
		}

		if (strtolower($this->input->method()) !== 'post') {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Method tidak valid.'));
			return;
		}

		$bulan = trim((string) $this->input->post('bulan', TRUE));
		if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).'));
			return;
		}

		$payload = array(
			'id_penjualan' => $this->input->post('id_penjualan', TRUE),
			'id_persediaan' => $this->input->post('id_persediaan', TRUE),
			'jumlah_produksi' => $this->input->post('jumlah_produksi', TRUE),
		);
		$result = persediaan_gen_v2_penyesuaian_produksi_dari_penjualan($this, $bulan, $payload);
		persediaan_ajax_json_output($this, $result);
	}

	/**
	 * AJAX: tampilan box verifikasi persediaan lengkap (setelah copy + pembelian + produksi + penjualan).
	 */
	public function ajax_generate_proses_persediaan_full_view()
	{
		$this->load->helper(array('pembelian_persediaan', 'persediaan_display'));

		try {
			if (!$this->persediaan_user_can_generate()) {
				persediaan_ajax_json_output($this, array(
					'ok' => false,
					'message' => $this->persediaan_restricted_access_message('Generate Persediaan'),
				));
				return;
			}

			$bulan = trim((string) $this->input->get_post('bulan', TRUE));
			if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
				persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).'));
				return;
			}

			$package = persediaan_generate_proses_persediaan_full_package($this, $bulan);
			if (empty($package['ok'])) {
				persediaan_ajax_json_output($this, array(
					'ok' => false,
					'message' => isset($package['message']) ? $package['message'] : 'Gagal memuat verifikasi persediaan lengkap.',
				));
				return;
			}

			$tab_rows = $this->get_persediaan_by_bulan($bulan);
			$package['rows_target_barang'] = persediaan_filter_rows_by_kategori_tab($tab_rows, false);
			$package['rows_target_jasa'] = persediaan_filter_rows_by_kategori_tab($tab_rows, true);
			$package['count_target_all'] = is_array($tab_rows) ? count($tab_rows) : 0;

			$html = $this->load->view(
				'anekadharma/persediaan/_generate_proses_persediaan_full_box',
				$package,
				true
			);

			persediaan_ajax_json_output($this, array(
				'ok' => true,
				'html' => $html,
				'rekap' => isset($package['rekap']) ? $package['rekap'] : array(),
				'bulan_target_label' => isset($package['bulan_target_label']) ? $package['bulan_target_label'] : '',
				'bulan_sumber_label' => isset($package['bulan_sumber_label']) ? $package['bulan_sumber_label'] : '',
			));
		} catch (Exception $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Error: ' . $e->getMessage()));
		} catch (Throwable $e) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Error: ' . $e->getMessage()));
		}
	}

	/**
	 * Export Excel datatable box verifikasi proses generate (copy / pembelian / produksi / penjualan / lengkap).
	 */
	public function excel_generate_proses()
	{
		$this->load->helper(array('exportexcel', 'pembelian_persediaan', 'persediaan_display'));

		if (!$this->persediaan_user_can_generate()) {
			show_error(strip_tags($this->persediaan_restricted_access_message('Export Excel verifikasi proses')), 403);
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
		$allowed = array_keys(persediaan_gen_proses_excel_jenis_definitions());
		if ($jenis === '' || !in_array($jenis, $allowed, true)) {
			show_error('Jenis export tidak valid.', 400);
			return;
		}

		$namaFile = 'Verifikasi_Proses_' . $bulan . '_' . $jenis . '_' . date('Y-m-d_H-i-s') . '.xlsx';
		excel_prepare_download($namaFile);
		persediaan_gen_proses_export_excel_output($this, $bulan, $jenis);
		exit();
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
	 * AJAX: muat 6 tabel ringkasan hasil generate (persediaan bulan lalu, pembelian, produksi, pecah, penjualan).
	 */
	public function ajax_gen_recalc_summary_tables()
	{
		$this->load->helper(array('pembelian_persediaan', 'persediaan_display'));

		if (!$this->persediaan_user_can_generate()) {
			persediaan_ajax_json_output($this, array(
				'ok' => false,
				'message' => $this->persediaan_restricted_access_message('Ringkasan generate'),
			));
			return;
		}

		$bulan = trim((string) $this->input->get_post('bulan', TRUE));
		if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).'));
			return;
		}

		$result = persediaan_gen_recalc_summary_tables_load($this, $bulan);
		persediaan_ajax_json_output($this, $result);
	}

	/**
	 * AJAX: muat tabel tambahan hasil generate (SPOP pembelian + penjualan berhasil/gagal + uuid orphan).
	 */
	public function ajax_gen_recalc_extra_tables()
	{
		$this->load->helper(array('pembelian_persediaan', 'persediaan_display'));

		if (!$this->persediaan_user_can_generate()) {
			persediaan_ajax_json_output($this, array(
				'ok' => false,
				'message' => $this->persediaan_restricted_access_message('Tabel tambahan generate'),
			));
			return;
		}

		$bulan = trim((string) $this->input->get_post('bulan', TRUE));
		if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).'));
			return;
		}

		$ctx = persediaan_gen_recalc_ctx_from_bulan($bulan);
		if (!$ctx) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Bulan tidak valid.'));
			return;
		}

		$decode_json = function ($key) {
			$raw = $this->input->get_post($key, FALSE);
			if ($raw === null || $raw === '') {
				return array();
			}
			if (is_array($raw)) {
				return $raw;
			}
			$parsed = json_decode((string) $raw, true);
			return is_array($parsed) ? $parsed : array();
		};

		$batch_items = array(
			'items_pembelian' => $decode_json('items_pembelian'),
			'items_pembelian_update' => $decode_json('items_pembelian_update'),
			'items_pembelian_baru' => $decode_json('items_pembelian_baru'),
			'items_penjualan' => $decode_json('items_penjualan'),
			'items_penjualan_update' => $decode_json('items_penjualan_update'),
		);

		$extra = persediaan_gen_recalc_build_result_extra_tables($this, $ctx, $batch_items);
		persediaan_ajax_json_output($this, array_merge(array('ok' => true, 'bulan' => $bulan), $extra));
	}

	/**
	 * AJAX: preview data persediaan untuk baris Gagal Generate atau Recalculate.
	 */
	public function ajax_gen_recalc_gagal_preview_persediaan()
	{
		$this->load->helper(array('pembelian_persediaan', 'persediaan_display'));

		if (!$this->persediaan_user_can_generate()) {
			persediaan_ajax_json_output($this, array(
				'ok' => false,
				'message' => $this->persediaan_restricted_access_message('Preview gagal ke persediaan'),
			));
			return;
		}

		$bulan = trim((string) $this->input->get_post('bulan', TRUE));
		if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).'));
			return;
		}

		$params = array(
			'fase' => $this->input->get_post('fase', TRUE),
			'aksi' => $this->input->get_post('aksi', TRUE),
			'tabel' => $this->input->get_post('tabel', TRUE),
			'id_sumber' => $this->input->get_post('id_sumber', TRUE),
			'id_target' => $this->input->get_post('id_target', TRUE),
			'namabarang' => $this->input->get_post('namabarang', TRUE),
			'satuan' => $this->input->get_post('satuan', TRUE),
			'hpp' => $this->input->get_post('hpp', TRUE),
			'spop' => $this->input->get_post('spop', TRUE),
			'jumlah' => $this->input->get_post('jumlah', TRUE),
			'keterangan' => $this->input->get_post('keterangan', TRUE),
		);

		$result = persediaan_gen_recalc_gagal_preview_persediaan($this, $bulan, $params);
		persediaan_ajax_json_output($this, $result);
	}

	/**
	 * AJAX: simpan manual ke tabel persediaan dari baris Gagal Generate atau Recalculate.
	 */
	public function ajax_gen_recalc_gagal_save_persediaan()
	{
		$this->load->helper(array('pembelian_persediaan', 'persediaan_display'));

		if (!$this->persediaan_user_can_generate()) {
			persediaan_ajax_json_output($this, array(
				'ok' => false,
				'message' => $this->persediaan_restricted_access_message('Simpan gagal ke persediaan'),
			));
			return;
		}

		if (strtolower($this->input->method()) !== 'post') {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Method tidak valid.'));
			return;
		}

		$bulan = trim((string) $this->input->post('bulan', TRUE));
		if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).'));
			return;
		}

		$fields_raw = $this->input->post('fields', FALSE);
		$fields = is_array($fields_raw) ? $fields_raw : json_decode((string) $fields_raw, true);
		if (!is_array($fields)) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Data field persediaan tidak valid.'));
			return;
		}

		$meta_raw = $this->input->post('meta', FALSE);
		$meta = is_array($meta_raw) ? $meta_raw : json_decode((string) $meta_raw, true);
		if (!is_array($meta)) {
			$meta = array();
		}

		$result = persediaan_gen_recalc_gagal_save_persediaan_manual($this, $bulan, $fields, $meta);
		persediaan_ajax_json_output($this, $result);
	}

	/**
	 * AJAX: sinkronkan snapshot tabel gagal dari browser ke history server.
	 */
	public function ajax_gen_recalc_sync_gagal_snapshot()
	{
		$this->load->helper(array('pembelian_persediaan', 'persediaan_display'));

		if (!$this->persediaan_user_can_generate()) {
			persediaan_ajax_json_output($this, array(
				'ok' => false,
				'message' => $this->persediaan_restricted_access_message('Sinkron gagal generate'),
			));
			return;
		}

		if (strtolower($this->input->method()) !== 'post') {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Method tidak valid.'));
			return;
		}

		$bulan = trim((string) $this->input->post('bulan', TRUE));
		if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Format bulan tidak valid (YYYY-MM).'));
			return;
		}

		$decode_list = function ($key) {
			$raw = $this->input->post($key, FALSE);
			if (is_array($raw)) {
				return $raw;
			}
			$parsed = json_decode((string) $raw, true);
			return is_array($parsed) ? $parsed : array();
		};

		$gagal = $decode_list('gagal_generate_recalculate');
		$gagal_ins = $decode_list('gagal_insert_persediaan');

		if (empty($gagal) && empty($gagal_ins)) {
			persediaan_ajax_json_output($this, array('ok' => true, 'message' => 'Tidak ada data gagal untuk disinkronkan.'));
			return;
		}

		if (!persediaan_gen_recalc_table_exists($this)) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Tabel history belum tersedia.'));
			return;
		}

		$log = persediaan_gen_recalc_history_get_log($this, $bulan);
		if (!$log) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'History proses untuk bulan ini belum ditemukan.'));
			return;
		}

		$hist_nomor = persediaan_gen_recalc_hist_nomor_init();
		$data = array(
			'gagal_generate_recalculate' => $gagal,
			'gagal_insert_persediaan' => $gagal_ins,
		);
		persediaan_gen_recalc_history_save_gagal_snapshots($this, (int) $log->id, $hist_nomor, $data);

		persediaan_ajax_json_output($this, array(
			'ok' => true,
			'message' => 'Snapshot gagal berhasil disinkronkan.',
			'gagal_count' => count($gagal),
			'gagal_insert_count' => count($gagal_ins),
		));
	}

	/**
	 * AJAX: daftar history generate (persediaan_history_generate) per bulan target.
	 */
	public function ajax_list_history_generate()
	{
		$this->load->helper(array('pembelian_persediaan', 'persediaan_display'));

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

		// Ensure history + generate_* tables exist
		$tables_created = persediaan_history_generate_ensure_tables($this);
		generate_hasil_datatable_ensure_tables($this);

		$list = persediaan_history_generate_list_by_bulan($this, $bulan, 100);
		$history_ids = array();
		foreach ($list as $row) {
			$history_ids[] = (int) $row->id;
		}
		$v2_flags = persediaan_history_generate_v2_flags_for_ids($this, $history_ids);
		$items = array();
		foreach ($list as $row) {
			$hid = (int) $row->id;
			$items[] = array(
				'id' => $hid,
				'bulan_target' => $row->bulan_target,
				'tanggal_klik_generate' => $row->tanggal_klik_generate,
				'tanggal_selesai' => $row->tanggal_selesai,
				'reset_deleted_count' => (int) $row->reset_deleted_count,
				'target_kosong_verified' => (int) $row->target_kosong_verified,
				'generate_insert' => (int) $row->generate_insert,
				'generate_update' => (int) $row->generate_update,
				'pembelian_update' => (int) $row->pembelian_update,
				'pembelian_insert' => (int) $row->pembelian_insert,
				'pembelian_gagal' => (int) $row->pembelian_gagal,
				'total_pembelian' => (int) $row->total_pembelian,
				'status' => $row->status,
				'fase_terakhir' => $row->fase_terakhir,
				'nama_user' => isset($row->nama_user) ? $row->nama_user : '',
				'has_v2_snapshot' => !empty($v2_flags[$hid]),
			);
		}

		$tables_ready = persediaan_history_generate_table_exists($this);

		persediaan_ajax_json_output($this, array(
			'ok' => true,
			'bulan' => $bulan,
			'tables_ready' => $tables_ready,
			'tables_created' => $tables_created,
			'items' => $items,
			'total' => count($items),
			'snapshot_source' => 'database',
			'message' => !$tables_ready
				? 'Tabel history generate belum tersedia di database. Refresh halaman Persediaan — tabel akan dibuat otomatis oleh aplikasi.'
				: (count($items) > 0 ? '' : 'Belum ada history generate untuk bulan ini.'),
		));
	}

	/**
	 * AJAX: muat snapshot history generate by id (rekap + datatable proses).
	 */
	public function ajax_load_history_generate()
	{
		$this->load->helper(array('pembelian_persediaan', 'persediaan_display'));

		if (!$this->persediaan_user_can_generate()) {
			persediaan_ajax_json_output($this, array(
				'ok' => false,
				'message' => $this->persediaan_restricted_access_message('History generate'),
			));
			return;
		}

		@ini_set('memory_limit', '512M');
		@set_time_limit(300);

		generate_hasil_datatable_ensure_tables($this);

		$id = (int) $this->input->get_post('id', TRUE);
		if ($id < 1) {
			persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'ID history tidak valid.'));
			return;
		}

		$result = persediaan_history_generate_load($this, $id);
		persediaan_ajax_json_output($this, $result);
	}

	/**
	 * Export Excel tabel ringkasan generate (persediaan_bulan_lalu|pembelian_barang|...|penjualan).
	 */
	public function excel_gen_recalc_summary()
	{
		$this->load->helper(array('exportexcel', 'pembelian_persediaan', 'persediaan_display'));

		if (!$this->persediaan_user_can_generate()) {
			show_error(strip_tags($this->persediaan_restricted_access_message('Export Excel ringkasan generate')), 403);
			return;
		}

		$bulan = trim((string) $this->input->post('bulan', TRUE));
		if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
			show_error('Format bulan tidak valid (YYYY-MM).', 400);
			return;
		}

		$jenis = trim((string) $this->input->post('jenis', TRUE));
		$allowed = array_keys(persediaan_gen_recalc_summary_jenis_definitions());
		if ($jenis === '' || !in_array($jenis, $allowed, true)) {
			show_error('Jenis export tidak valid.', 400);
			return;
		}

		$namaFile = 'Ringkasan_Generate_' . $bulan . '_' . $jenis . '_' . date('Y-m-d_H-i-s') . '.xlsx';
		excel_prepare_download($namaFile);
		persediaan_gen_recalc_summary_export_excel_output($this, $bulan, $jenis);
		exit();
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
		$inline_raw = $this->input->post('gen_recalc_data', false);
		persediaan_gen_recalc_export_excel_output(
			$this,
			$bulan,
			$jenis !== '' ? $jenis : null,
			$inline_raw
		);
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
	 * AJAX: insert data tabel hasil import CSV compare ke tabel persediaan.
	 */
	public function ajax_compare_insert_to_persediaan()
	{
		@set_time_limit(0);
		@ini_set('memory_limit', '512M');
		$this->load->helper('pembelian_persediaan');

		if (!$this->persediaan_user_can_compare()) {
			persediaan_ajax_json_output($this, array(
				'ok' => false,
				'message' => strip_tags($this->persediaan_restricted_access_message('Insert compare ke persediaan')),
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

		$bulan = trim((string) $this->input->post('bulan', TRUE));
		if ($bulan === '') {
			$bulan = $this->_compare_tabel_bulan_from_post();
		}

		persediaan_ajax_json_output($this, persediaan_compare_insert_table_to_persediaan($this, $table, $bulan));
	}

	/**
	 * AJAX: cek struktur tabel untuk tombol insert ke persediaan (combobox DB tab Compare).
	 */
	public function ajax_compare_check_insert_persediaan_eligible()
	{
		$this->load->helper('pembelian_persediaan');

		if (!$this->persediaan_user_can_compare()) {
			persediaan_ajax_json_output($this, array(
				'ok' => false,
				'message' => strip_tags($this->persediaan_restricted_access_message('Compare')),
			));
			return;
		}

		$table = trim((string) $this->input->post('tabel', TRUE));
		if ($table === '') {
			persediaan_ajax_json_output($this, array(
				'ok' => false,
				'eligible' => false,
				'message' => 'Nama tabel belum dipilih.',
			));
			return;
		}

		persediaan_ajax_json_output($this, persediaan_compare_table_eligible_insert_persediaan_fields($this, $table));
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
	 * Saldo awal generate dari sisa stok akhir bulan sumber (total_10 net per baris/spop).
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
		$kategori_sumber = persediaan_generate_recalculate_resolve_kategori_sumber($this, $row);

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
			'kategori' => ($kategori_sumber !== '' && $this->db->field_exists('kategori', 'persediaan')) ? $kategori_sumber : '',
			'namabarang' => $nama,
			'satuan' => $satuan,
			'hpp' => $hpp,
			'sa' => $sa_tampil,
			'spop' => isset($row->spop) ? trim((string) $row->spop) : '0',
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
			. ' (dari saldo akhir sumber/net total_10 ' . $this->format_angka_persediaan($total_10_sumber) . ')'
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

	private function persediaan_field_exists($field_name)
	{
		static $fields_cache = null;
		if ($fields_cache === null) {
			$fields_cache = $this->db->list_fields('persediaan');
		}
		return in_array($field_name, $fields_cache, true);
	}

	private function persediaan_aggregate_sql_column($field_name, $alias = null)
	{
		$alias = $alias === null ? $field_name : $alias;
		$quoted_alias = '`' . str_replace('`', '``', $alias) . '`';
		$column_name = $field_name;
		if ($field_name === 'sekret' && !$this->persediaan_field_exists('sekret') && $this->persediaan_field_exists('Sekretariat')) {
			$column_name = 'Sekretariat';
		} elseif ($field_name === 'cetak' && !$this->persediaan_field_exists('cetak') && $this->persediaan_field_exists('CETAK')) {
			$column_name = 'CETAK';
		} elseif ($field_name === 'grafikita' && !$this->persediaan_field_exists('grafikita') && $this->persediaan_field_exists('GRAFIKITA')) {
			$column_name = 'GRAFIKITA';
		}
		$quoted_column = '`' . str_replace('`', '``', $column_name) . '`';
		return 'SUM(COALESCE(' . $quoted_column . ', 0)) AS ' . $quoted_alias;
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
				$rows = array();
			} else {
				$bulan_key = date('Y-m', $ts);
				$select_parts = array(
					'MIN(`id`) AS `id`',
					'MAX(`uuid_persediaan`) AS `uuid_persediaan`',
					'MAX(`uuid_spop`) AS `uuid_spop`',
					'MAX(`uuid_gudang`) AS `uuid_gudang`',
					'MAX(`nama_gudang`) AS `nama_gudang`',
					'MAX(`uuid_barang`) AS `uuid_barang`',
					'MAX(`kode_barang`) AS `kode_barang`',
					'MAX(`tanggal_beli`) AS `tanggal_beli`',
					'MAX(`tanggal`) AS `tanggal`',
					'MAX(`kode`) AS `kode`',
					'MAX(`kategori`) AS `kategori`',
					'MAX(`tuj`) AS `tuj`',
					'MAX(`tgl_keluar`) AS `tgl_keluar`',
					'`namabarang`',
					'`satuan`',
					'`hpp`',
					'`spop`',
					$this->persediaan_aggregate_sql_column('sa'),
					$this->persediaan_aggregate_sql_column('beli'),
					$this->persediaan_aggregate_sql_column('sekret', 'sekret'),
					$this->persediaan_aggregate_sql_column('cetak', 'cetak'),
					$this->persediaan_aggregate_sql_column('grafikita', 'grafikita'),
					$this->persediaan_aggregate_sql_column('dinas_umum', 'dinas_umum'),
					$this->persediaan_aggregate_sql_column('atk_rsud', 'atk_rsud'),
					$this->persediaan_aggregate_sql_column('ppbmp_kbs', 'ppbmp_kbs'),
					$this->persediaan_aggregate_sql_column('kbs', 'kbs'),
					$this->persediaan_aggregate_sql_column('ppbmp', 'ppbmp'),
					$this->persediaan_aggregate_sql_column('medis', 'medis'),
					$this->persediaan_aggregate_sql_column('siiplah_bosda', 'siiplah_bosda'),
					$this->persediaan_aggregate_sql_column('sembako', 'sembako'),
					$this->persediaan_aggregate_sql_column('fc_gose', 'fc_gose'),
					$this->persediaan_aggregate_sql_column('fc_manding', 'fc_manding'),
					$this->persediaan_aggregate_sql_column('fc_psamya', 'fc_psamya'),
					$this->persediaan_aggregate_sql_column('total_10', 'total_10'),
					$this->persediaan_aggregate_sql_column('nilai_persediaan', 'nilai_persediaan'),
					$this->persediaan_aggregate_sql_column('penjualan', 'penjualan'),
					$this->persediaan_aggregate_sql_column('pecah_satuan', 'pecah_satuan'),
					$this->persediaan_aggregate_sql_column('bahan_produksi', 'bahan_produksi')
				);
				$sql = "SELECT " . implode(",\n\t\t\t\t\t\t", $select_parts) . "
					FROM `persediaan`
					WHERE LEFT(COALESCE(`tanggal_beli`, ''), 7) = ?
					GROUP BY `spop`, `namabarang`, `satuan`, `hpp`
					ORDER BY `namabarang` ASC, `spop` ASC";
				$rows = $this->db->query($sql, array($bulan_key))->result();
			}
		}

		$rows = persediaan_export_sort_rows_by_namabarang($rows, 'namabarang');

		return persediaan_filter_rows_tab_data($rows);
	}

	/**
	 * Baris persediaan sama persis dengan tab Data (Barang/Jasa): GROUP BY spop+nama+satuan+hpp.
	 */
	public function get_persediaan_tab_rows_for_bulan($bulan)
	{
		return $this->get_persediaan_by_bulan($bulan);
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

		$filter = strtolower(trim((string) $this->input->post('filter_kategori', TRUE)));
		$this->load->helper(array('exportexcel', 'persediaan_display', 'pembelian_persediaan'));
		$Persediaan = $this->get_persediaan_by_bulan($bulan);

		if ($filter === 'jasa') {
			$Persediaan = persediaan_filter_rows_by_kategori_tab($Persediaan, true);
		} elseif ($filter === 'barang') {
			$Persediaan = persediaan_filter_rows_by_kategori_tab($Persediaan, false);
		} else {
			$filter = 'semua';
		}

		persediaan_export_excel_tab_data_output($this, $bulan, $Persediaan, $filter);
		exit();
	}

	/**
	 * Export Excel tab persediaan_draft_bulan_referensi (bulan sebelumnya).
	 * Pakai nilai_persediaan & total_10 tersimpan (sama seperti DataTable draft).
	 */
	public function excel_draft_bulan_referensi()
	{
		$bulan_target = trim((string) $this->input->post('bulan_persediaan', TRUE));
		if ($bulan_target === '' || !preg_match('/^\d{4}-\d{2}$/', $bulan_target)) {
			$bulan_target = date('Y-m');
		}

		$this->load->helper(array('exportexcel', 'persediaan_display'));

		$rows = $this->get_persediaan_draft_bulan_referensi_by_target($bulan_target);
		$bulan_sumber = $this->get_persediaan_draft_bulan_referensi_sumber_label($bulan_target);
		if ($bulan_sumber === '' || !preg_match('/^\d{4}-\d{2}$/', $bulan_sumber)) {
			$ts = strtotime($bulan_target . '-01');
			$bulan_sumber = ($ts !== false) ? date('Y-m', strtotime('-1 month', $ts)) : $bulan_target;
		}

		$label_sumber = date('m/Y', strtotime($bulan_sumber . '-01'));
		$label_target = date('m/Y', strtotime($bulan_target . '-01'));
		$title = 'PERSEDIAAN DRAFT BULAN REFERENSI (BULAN SEBELUMNYA) — Sumber '
			. $label_sumber . ' untuk Target ' . $label_target;

		persediaan_export_excel_tab_data_output(
			$this,
			$bulan_sumber,
			$rows,
			'draft_referensi',
			true,
			$title,
			true
		);
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

	/**
	 * Jalankan migrasi index database untuk mempercepat proses Generate & Recalculate.
	 * Akses: https://anekadharma.my.id/index.php/persediaan/migrasi_index
	 */
	public function migrasi_index()
	{
		@set_time_limit(300);
		@ini_set('memory_limit', '256M');

		if (!$this->persediaan_user_can_generate()) {
			show_error('Akses ditolak. Hanya untuk admin.', 403);
			return;
		}

		$this->load->helper('persediaan_perbaikan_indexes');
		$result = persediaan_perbaikan_tambah_indexes($this);

		header('Content-Type: text/html; charset=UTF-8');
		echo '<!doctype html><html><head><meta charset="utf-8"><title>Migrasi Index Database</title>';
		echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">';
		echo '</head><body class="p-4"><div class="container" style="max-width:720px;">';
		echo '<h3>Migrasi Index Database Persediaan</h3>';
		echo '<div class="alert alert-info">';
		echo '<strong>' . (int) $result['added'] . '</strong> index baru ditambahkan dari <strong>' . (int) $result['total'] . '</strong> total.';
		echo '</div>';

		if (!empty($result['errors'])) {
			echo '<div class="alert alert-warning"><ul>';
			foreach ($result['errors'] as $e) {
				echo '<li>' . htmlspecialchars($e) . '</li>';
			}
			echo '</ul></div>';
		}

		echo '<a href="' . site_url('persediaan') . '" class="btn btn-primary">Kembali ke Persediaan</a>';
		echo '</div></body></html>';
	}

	/**
	 * AJAX: perbaiki batch penjualan yang stuck dengan limit lebih kecil.
	 */
	public function ajax_generate_v2_batch_patch()
	{
		@set_time_limit(0);
		@ini_set('memory_limit', '1024M');
		@ignore_user_abort(true);

		$this->load->helper(array('pembelian_persediaan', 'persediaan_display'));

		if (!$this->persediaan_user_can_generate()) {
			persediaan_ajax_json_output($this, array(
				'ok' => false,
				'message' => $this->persediaan_restricted_access_message('Generate batch'),
			));
			return;
		}

		$bulan = trim((string) $this->input->get_post('bulan', TRUE));
		if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
			persediaan_ajax_json_output($this, array(
				'ok' => false,
				'message' => 'Format bulan tidak valid (YYYY-MM).',
			));
			return;
		}

		$offset = max(0, (int) $this->input->get_post('offset', TRUE));
		$limit = (int) $this->input->get_post('limit', TRUE);
		if ($limit < 1 || $limit > 200) {
			$limit = 20; // Limit lebih kecil untuk mencegah timeout
		}
		$start = ($this->input->get_post('start', TRUE) === '1');

		$db_debug = $this->db->db_debug;
		$this->db->db_debug = false;

		try {
			$result = persediaan_generate_v2_batch($this, $bulan, $offset, $limit, $start);

			// Tambahkan info eksekusi
			if (!isset($result['execution_info'])) {
				$result['execution_info'] = array(
					'limit' => $limit,
					'offset' => $offset,
					'batch_limit_small' => true,
				);
			}

			$this->db->db_debug = $db_debug;
			persediaan_ajax_json_output($this, $result);
		} catch (Exception $e) {
			$this->db->db_debug = $db_debug;
			persediaan_ajax_json_output($this, array(
				'ok' => false,
				'message' => 'Error: ' . $e->getMessage(),
			));
		} catch (Throwable $e) {
			$this->db->db_debug = $db_debug;
			persediaan_ajax_json_output($this, array(
				'ok' => false,
				'message' => 'Error: ' . $e->getMessage(),
			));
		}
	}
}

/* End of file Persediaan.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-10-23 04:04:45 */
/* http://harviacode.com */