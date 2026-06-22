<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Neraca_saldo extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_login();
		$this->load->model(array('Sys_kode_akun_model', 'Neraca_saldo_model'));
		$this->load->library('form_validation');
		$this->load->library('datatables');
	}


	private function _neraca_saldo_session_bulan_key()
	{
		return 'neraca_saldo_bulan_ns';
	}

	private function _resolve_neraca_saldo_bulan()
	{
		$bulan_ns = trim((string) $this->input->post('bulan_ns', TRUE));
		if ($bulan_ns === '') {
			$bulan_ns = trim((string) $this->input->get('bulan_ns', TRUE));
		}

		if ($bulan_ns !== '' && preg_match('/^(\d{4})-(\d{2})$/', $bulan_ns, $m)) {
			$this->session->set_userdata($this->_neraca_saldo_session_bulan_key(), $bulan_ns);
			return array(
				'month' => (int) $m[2],
				'year' => (int) $m[1],
				'bulan_ns_value' => sprintf('%04d-%02d', (int) $m[1], (int) $m[2]),
			);
		}

		$bulan_ns = trim((string) $this->session->userdata($this->_neraca_saldo_session_bulan_key()));
		if ($bulan_ns !== '' && preg_match('/^(\d{4})-(\d{2})$/', $bulan_ns, $m)) {
			return array(
				'month' => (int) $m[2],
				'year' => (int) $m[1],
				'bulan_ns_value' => sprintf('%04d-%02d', (int) $m[1], (int) $m[2]),
			);
		}

		return array(
			'month' => (int) date('m'),
			'year' => (int) date('Y'),
			'bulan_ns_value' => date('Y-m'),
		);
	}

	private function _neraca_saldo_view_data($extra = array())
	{
		$parsed = $this->_resolve_neraca_saldo_bulan();
		$month = (int) $parsed['month'];
		$year = (int) $parsed['year'];

		if ($month < 1 || $month > 12) {
			$month = (int) date('m');
		}
		if ($year < 2000) {
			$year = (int) date('Y');
		}

		$data = array(
			'Data_Kode_Akun' => $this->Sys_kode_akun_model->get_all_order_by_kode_akun_ASC(),
			'action' => site_url('Buku_besar/cari_kode_akun'),
			'month_selected' => sprintf('%02d', $month),
			'year_selected' => $year,
			'bulan_ns_value' => sprintf('%04d-%02d', $year, $month),
			'Get_month_from_date' => $month,
			'Get_year_Tahun_ini' => $year,
			'Get_year_Setahun_lalu' => (int) date('Y', strtotime('-1 year')),
			'url_cari_bulan' => site_url('Neraca_saldo/Cari_bulan_data'),
			'url_ajax_refresh' => site_url('Neraca_saldo/ajax_refresh_datatable'),
			'url_neraca_saldo_excel' => site_url('Neraca_saldo/excel_list'),
		);

		if (!is_array($extra)) {
			$extra = array();
		}

		return array_merge($data, $extra);
	}

	public function index()
	{
		$this->load->helper('neraca_saldo_list');


		// $Get_date_awal = date("Y-m-1 00:00:00");

		// $Get_date_akhir = date("Y-m-t 23:59:59"); // TANGGAL AKHIR BULAN -t
		// $Get_month_akhir = date("m"); // TANGGAL AKHIR BULAN -t

		// print_r($Get_date_awal);
		// print_r("<br/>");
		// print_r($Get_date_akhir);
		// print_r("<br/>");
		// print_r($Get_month_akhir);
		// print_r("<br/>");


		// // 1. hapus data bulan terpilih / bulan ini
		// DELETE FROM `neraca_saldo_test` WHERE MONTH(`tanggal`) = 3;


		// $sql_data = "SELECT * FROM `neraca_saldo_test` WHERE MONTH(`tanggal`) = $Get_month_akhir";

		// $Get_sql_data = $this->db->query($sql_data)->result();
		// print_r($Get_sql_data);
		// die;



		// $data_Buku_besar = $this->Buku_besar_model->get_all_sort_by_tanggal();

		// $Tbl_pembelian = $this->Tbl_pembelian_model->get_all();
		$sql_pembelian = "SELECT sum(tbl_pembelian.jumlah*tbl_pembelian.harga_satuan) as kredit,
		tbl_pembelian.kode_akun as kode_akun
		                    FROM tbl_pembelian    
		                    group BY tbl_pembelian.kode_akun";

		$sql_penjualan = "SELECT tbl_penjualan.tgl_jual as tanggal,        
		tbl_penjualan.nama_barang as keterangan,
		tbl_penjualan.jumlah as jumlah,
		tbl_penjualan.harga_satuan as harga_satuan,
		(tbl_penjualan.jumlah * tbl_penjualan.harga_satuan) as debet,
		tbl_penjualan.kode_akun as kode_akun
		                    FROM tbl_penjualan    
		                    ORDER BY tbl_penjualan.tgl_jual DESC, tbl_penjualan.kode_akun ASC";

		// $Get_sys_kode_akun = $this->Sys_kode_akun_model->get_all();

		// print_r($Get_sys_kode_akun);
		// die;


		$data = $this->_neraca_saldo_view_data();

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/neraca_saldo/adminlte310_neraca_saldo_list', $data);
	}

	public function input_neraca_saldo_waktu_lalu($kode_akun = null, $debet_kredit = null, $tahun_proses = null, $bulan_proses = null)
	{
		$this->load->helper('neraca_saldo_list');

		$nominal_existing = '';
		$sql = 'SELECT * FROM `neraca_saldo` WHERE MONTH(`tanggal`) = ? AND YEAR(`tanggal`) = ? AND `kode_akun` = ?';
		$neraca_saldo_data = $this->db->query($sql, array((int) $bulan_proses, (int) $tahun_proses, (int) $kode_akun));
		if ($neraca_saldo_data->num_rows() > 0) {
			$ns_row = $neraca_saldo_data->row();
			if ($debet_kredit === 'debet' && !is_null($ns_row->debet_akhir_tahun_lalu)) {
				$nominal_existing = neraca_saldo_format_rupiah($ns_row->debet_akhir_tahun_lalu, true);
			} elseif ($debet_kredit === 'kredit' && !is_null($ns_row->kredit_akhir_tahun_lalu)) {
				$nominal_existing = neraca_saldo_format_rupiah($ns_row->kredit_akhir_tahun_lalu, true);
			}
		}

		$data = array(
			// 'Tbl_pembelian_data' => $Tbl_pembelian,
			// 'spop' => $data_per_uuidspop->spop,
			'action' => site_url('Neraca_saldo/Simpan_Nominal_tahun_lalu/' . $kode_akun . '/' . $debet_kredit . '/' . $tahun_proses . '/' . $bulan_proses),
			'button' => 'Simpan',
			// 'start' => $start,
			// 'get_kode_akun' => $get_kode_akun,
			// 'get_kode_pl' => $get_kode_pl,
			// 'get_kode_bb' => $get_kode_bb,

			'kode_akun' => $kode_akun,
			'debet_kredit' => $debet_kredit,
			'tahun_proses' => $tahun_proses,
			'bulan_proses' => $bulan_proses,
			'nominal_existing' => $nominal_existing,


		);
		// print_r($data);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/neraca_saldo/adminlte310_neraca_saldo_input_nominal_tahun_lalu', $data);
	}

	public function Simpan_Nominal_tahun_lalu($kode_akun = null, $debet_kredit = null, $tahun_proses = null, $bulan_proses = null)
	{
		$this->load->helper('neraca_saldo_list');
		$nominal = neraca_saldo_parse_nominal($this->input->post('nominal', TRUE));

		// $this->_rules();

		// if ($this->form_validation->run() == FALSE) {
		// 	$this->create();
		// } else {

		// Cek apakah kode akun dan bulan tahun ini sudah ada record , jika sudah ada maka proses update
		// jika belum ada maka proses insert

		$sql = "SELECT * FROM `neraca_saldo` WHERE MONTH(`tanggal`)=$bulan_proses AND YEAR(`tanggal`)=$tahun_proses AND `kode_akun`=$kode_akun ";

		$Neraca_Saldo_Data = $this->db->query($sql);



		if ($Neraca_Saldo_Data->num_rows() > 0) {

			// Get ID



			// echo "ada data ---";
			// echo "<br/>";
			// echo $Neraca_Saldo_Data->row()->id;
			// die;

			// echo $Buku_besar_DATA->row()->debet;
			if ($debet_kredit == "debet") {

				$data = array(
					'debet_akhir_tahun_lalu' => $nominal,
				);

				$this->Neraca_saldo_model->update($Neraca_Saldo_Data->row()->id, $data);
			} else {

				$data = array(
					'kredit_akhir_tahun_lalu' => $nominal,
				);

				$this->Neraca_saldo_model->update($Neraca_Saldo_Data->row()->id, $data);
			}
		} else {
			// echo "Kosong";
			// insert data

			// GET TANGGAL
			$date_Setting = $tahun_proses . '-' . $bulan_proses . '-1';

			// GET DATA KODE AKUN
			$sql = "SELECT * FROM `sys_kode_akun` WHERE `kode_akun`=$kode_akun ";

			$sys_kode_akun_Data = $this->db->query($sql);




			if ($debet_kredit == "debet") {

				$data = array(
					'tanggal' => $date_Setting,
					'kode_akun' => $kode_akun,
					'uuid_kode_akun' => $sys_kode_akun_Data->row()->uuid_kode_akun,
					'nama_akun' => $sys_kode_akun_Data->row()->nama_akun,
					// 'uraian' => $sys_kode_akun_Data->row()->,
					// 'group' => $sys_kode_akun_Data->row()->,
					'debet_akhir_tahun_lalu' => $nominal,
					// 'kredit_akhir_tahun_lalu' => "",
					// 'debet_penyesuaian' => "",
					// 'kredit_penyesuaian' => "",
					// 'debet_ns_setelah_penyesuaian' => "",
					// 'kredit_ns_setelah_penyesuaian' => "",
					// 'debet_laba_rugi' => "",
					// 'kreditdebet_laba_rugi' => "",
				);
			} else {

				// $data = array(
				// 	'kredit_akhir_tahun_lalu' => $this->input->post('nominal', TRUE),
				// );

				$data = array(
					'tanggal' => $date_Setting,
					'kode_akun' => $kode_akun,
					'uuid_kode_akun' => $sys_kode_akun_Data->row()->uuid_kode_akun,
					'nama_akun' => $sys_kode_akun_Data->row()->nama_akun,
					'nama_akun' => "",
					'uraian' => "",
					'group' => "",
					// 'debet_akhir_tahun_lalu' => $this->input->post('nominal', TRUE),
					'kredit_akhir_tahun_lalu' => $nominal,
					// 'debet_penyesuaian' => "",
					// 'kredit_penyesuaian' => "",
					// 'debet_ns_setelah_penyesuaian' => "",
					// 'kredit_ns_setelah_penyesuaian' => "",
					// 'debet_laba_rugi' => "",
					// 'kreditdebet_laba_rugi' => "",
				);


				$this->Neraca_saldo_model->update($Neraca_Saldo_Data->row()->id, $data);
			}



			$this->Neraca_saldo_model->insert($data);
		}
		// die;


		$this->session->set_flashdata('message', 'Create Record Success');
		redirect(site_url('neraca_saldo'));
		// }
	}


	public function Cari_bulan_data()
	{
		$data = $this->_neraca_saldo_view_data();
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/neraca_saldo/adminlte310_neraca_saldo_list', $data);
	}

	public function ajax_refresh_datatable()
	{
		$this->load->helper(array('buku_besar_list', 'neraca_saldo_list'));
		header('Content-Type: application/json; charset=utf-8');

		$data = $this->_neraca_saldo_view_data();
		$month_int = (int) $data['Get_month_from_date'];
		$year_int = (int) $data['Get_year_Tahun_ini'];

		if ($month_int < 1 || $month_int > 12) {
			$month_int = (int) date('m');
		}
		if ($year_int < 2000) {
			$year_int = (int) date('Y');
		}

		$data['Get_month_from_date'] = $month_int;
		$data['Get_year_Tahun_ini'] = $year_int;
		$data['month_selected'] = sprintf('%02d', $month_int);
		$data['year_selected'] = $year_int;
		$data['bulan_ns_value'] = sprintf('%04d-%02d', $year_int, $month_int);

		$tbody_html = $this->load->view('anekadharma/neraca_saldo/adminlte310_neraca_saldo_tbody', $data, TRUE);

		echo json_encode(array(
			'ok' => true,
			'tbody_html' => $tbody_html,
			'periode_label' => buku_besar_bulan_teks($month_int) . ' ' . $year_int,
			'bulan_ns_value' => $data['bulan_ns_value'],
			'tahun_lalu' => $data['Get_year_Setahun_lalu'],
		));
	}

	public function index_server_side()
	{
		$this->load->view('neraca_saldo/neraca_saldo_list');
	}

	public function json()
	{
		header('Content-Type: application/json');
		echo $this->Neraca_saldo_model->json();
	}

	public function read($id)
	{
		$row = $this->Neraca_saldo_model->get_by_id($id);
		if ($row) {
			$data = array(
				'id' => $row->id,
				'uuid_kode_akun' => $row->uuid_kode_akun,
				'kode_akun' => $row->kode_akun,
				'nama_akun' => $row->nama_akun,
				'uraian' => $row->uraian,
				'group' => $row->group,
				'debet_akhir_tahun_lalu' => $row->debet_akhir_tahun_lalu,
				'kredit_akhir_tahun_lalu' => $row->kredit_akhir_tahun_lalu,
				'debet_penyesuaian' => $row->debet_penyesuaian,
				'kredit_penyesuaian' => $row->kredit_penyesuaian,
				'debet_ns_setelah_penyesuaian' => $row->debet_ns_setelah_penyesuaian,
				'kredit_ns_setelah_penyesuaian' => $row->kredit_ns_setelah_penyesuaian,
				'debet_laba_rugi' => $row->debet_laba_rugi,
				'kreditdebet_laba_rugi' => $row->kreditdebet_laba_rugi,
			);
			$this->load->view('neraca_saldo/neraca_saldo_read', $data);
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(site_url('neraca_saldo'));
		}
	}

	public function create()
	{
		$data = array(
			'button' => 'Create',
			'action' => site_url('neraca_saldo/create_action'),
			'id' => set_value('id'),
			'uuid_kode_akun' => set_value('uuid_kode_akun'),
			'kode_akun' => set_value('kode_akun'),
			'nama_akun' => set_value('nama_akun'),
			'uraian' => set_value('uraian'),
			'group' => set_value('group'),
			'debet_akhir_tahun_lalu' => set_value('debet_akhir_tahun_lalu'),
			'kredit_akhir_tahun_lalu' => set_value('kredit_akhir_tahun_lalu'),
			'debet_penyesuaian' => set_value('debet_penyesuaian'),
			'kredit_penyesuaian' => set_value('kredit_penyesuaian'),
			'debet_ns_setelah_penyesuaian' => set_value('debet_ns_setelah_penyesuaian'),
			'kredit_ns_setelah_penyesuaian' => set_value('kredit_ns_setelah_penyesuaian'),
			'debet_laba_rugi' => set_value('debet_laba_rugi'),
			'kreditdebet_laba_rugi' => set_value('kreditdebet_laba_rugi'),
		);
		$this->load->view('neraca_saldo/neraca_saldo_form', $data);
	}

	public function create_action()
	{
		$this->_rules();

		if ($this->form_validation->run() == FALSE) {
			$this->create();
		} else {
			$data = array(
				// 'uuid_kode_akun' => $this->input->post('uuid_kode_akun', TRUE),
				'kode_akun' => $this->input->post('kode_akun', TRUE),
				'nama_akun' => $this->input->post('nama_akun', TRUE),
				'uraian' => $this->input->post('uraian', TRUE),
				'group' => $this->input->post('group', TRUE),
				'debet_akhir_tahun_lalu' => $this->input->post('debet_akhir_tahun_lalu', TRUE),
				'kredit_akhir_tahun_lalu' => $this->input->post('kredit_akhir_tahun_lalu', TRUE),
				'debet_penyesuaian' => $this->input->post('debet_penyesuaian', TRUE),
				'kredit_penyesuaian' => $this->input->post('kredit_penyesuaian', TRUE),
				'debet_ns_setelah_penyesuaian' => $this->input->post('debet_ns_setelah_penyesuaian', TRUE),
				'kredit_ns_setelah_penyesuaian' => $this->input->post('kredit_ns_setelah_penyesuaian', TRUE),
				'debet_laba_rugi' => $this->input->post('debet_laba_rugi', TRUE),
				'kreditdebet_laba_rugi' => $this->input->post('kreditdebet_laba_rugi', TRUE),
			);

			$this->Neraca_saldo_model->insert($data);
			$this->session->set_flashdata('message', 'Create Record Success');
			redirect(site_url('neraca_saldo'));
		}
	}

	public function update($id)
	{
		$row = $this->Neraca_saldo_model->get_by_id($id);

		if ($row) {
			$data = array(
				'button' => 'Update',
				'action' => site_url('neraca_saldo/update_action'),
				'id' => set_value('id', $row->id),
				'uuid_kode_akun' => set_value('uuid_kode_akun', $row->uuid_kode_akun),
				'kode_akun' => set_value('kode_akun', $row->kode_akun),
				'nama_akun' => set_value('nama_akun', $row->nama_akun),
				'uraian' => set_value('uraian', $row->uraian),
				'group' => set_value('group', $row->group),
				'debet_akhir_tahun_lalu' => set_value('debet_akhir_tahun_lalu', $row->debet_akhir_tahun_lalu),
				'kredit_akhir_tahun_lalu' => set_value('kredit_akhir_tahun_lalu', $row->kredit_akhir_tahun_lalu),
				'debet_penyesuaian' => set_value('debet_penyesuaian', $row->debet_penyesuaian),
				'kredit_penyesuaian' => set_value('kredit_penyesuaian', $row->kredit_penyesuaian),
				'debet_ns_setelah_penyesuaian' => set_value('debet_ns_setelah_penyesuaian', $row->debet_ns_setelah_penyesuaian),
				'kredit_ns_setelah_penyesuaian' => set_value('kredit_ns_setelah_penyesuaian', $row->kredit_ns_setelah_penyesuaian),
				'debet_laba_rugi' => set_value('debet_laba_rugi', $row->debet_laba_rugi),
				'kreditdebet_laba_rugi' => set_value('kreditdebet_laba_rugi', $row->kreditdebet_laba_rugi),
			);
			$this->load->view('neraca_saldo/neraca_saldo_form', $data);
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(site_url('neraca_saldo'));
		}
	}

	public function update_action()
	{
		$this->_rules();

		if ($this->form_validation->run() == FALSE) {
			$this->update($this->input->post('id', TRUE));
		} else {
			$data = array(
				'uuid_kode_akun' => $this->input->post('uuid_kode_akun', TRUE),
				'kode_akun' => $this->input->post('kode_akun', TRUE),
				'nama_akun' => $this->input->post('nama_akun', TRUE),
				'uraian' => $this->input->post('uraian', TRUE),
				'group' => $this->input->post('group', TRUE),
				'debet_akhir_tahun_lalu' => $this->input->post('debet_akhir_tahun_lalu', TRUE),
				'kredit_akhir_tahun_lalu' => $this->input->post('kredit_akhir_tahun_lalu', TRUE),
				'debet_penyesuaian' => $this->input->post('debet_penyesuaian', TRUE),
				'kredit_penyesuaian' => $this->input->post('kredit_penyesuaian', TRUE),
				'debet_ns_setelah_penyesuaian' => $this->input->post('debet_ns_setelah_penyesuaian', TRUE),
				'kredit_ns_setelah_penyesuaian' => $this->input->post('kredit_ns_setelah_penyesuaian', TRUE),
				'debet_laba_rugi' => $this->input->post('debet_laba_rugi', TRUE),
				'kreditdebet_laba_rugi' => $this->input->post('kreditdebet_laba_rugi', TRUE),
			);

			$this->Neraca_saldo_model->update($this->input->post('id', TRUE), $data);
			$this->session->set_flashdata('message', 'Update Record Success');
			redirect(site_url('neraca_saldo'));
		}
	}

	public function delete($id)
	{
		$row = $this->Neraca_saldo_model->get_by_id($id);

		if ($row) {
			$this->Neraca_saldo_model->delete($id);
			$this->session->set_flashdata('message', 'Delete Record Success');
			redirect(site_url('neraca_saldo'));
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(site_url('neraca_saldo'));
		}
	}

	public function _rules()
	{
		$this->form_validation->set_rules('uuid_kode_akun', 'uuid kode akun', 'trim|required');
		$this->form_validation->set_rules('kode_akun', 'kode akun', 'trim|required');
		$this->form_validation->set_rules('nama_akun', 'nama akun', 'trim|required');
		$this->form_validation->set_rules('uraian', 'uraian', 'trim|required');
		$this->form_validation->set_rules('group', 'group', 'trim|required');
		$this->form_validation->set_rules('debet_akhir_tahun_lalu', 'debet akhir tahun lalu', 'trim|required|numeric');
		$this->form_validation->set_rules('kredit_akhir_tahun_lalu', 'kredit akhir tahun lalu', 'trim|required|numeric');
		$this->form_validation->set_rules('debet_penyesuaian', 'debet penyesuaian', 'trim|required|numeric');
		$this->form_validation->set_rules('kredit_penyesuaian', 'kredit penyesuaian', 'trim|required|numeric');
		$this->form_validation->set_rules('debet_ns_setelah_penyesuaian', 'debet ns setelah penyesuaian', 'trim|required|numeric');
		$this->form_validation->set_rules('kredit_ns_setelah_penyesuaian', 'kredit ns setelah penyesuaian', 'trim|required|numeric');
		$this->form_validation->set_rules('debet_laba_rugi', 'debet laba rugi', 'trim|required|numeric');
		$this->form_validation->set_rules('kreditdebet_laba_rugi', 'kreditdebet laba rugi', 'trim|required|numeric');

		$this->form_validation->set_rules('id', 'id', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
	}

	public function excel_list()
	{
		$this->load->helper('neraca_saldo_list');
		neraca_saldo_export_excel_list_output($this);
	}

	public function excel()
	{
		$this->load->helper('exportexcel');
		$namaFile = "neraca_saldo.xls";
		$judul = "neraca_saldo";
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
		xlsWriteLabel($tablehead, $kolomhead++, "Uuid Kode Akun");
		xlsWriteLabel($tablehead, $kolomhead++, "Kode Akun");
		xlsWriteLabel($tablehead, $kolomhead++, "Nama Akun");
		xlsWriteLabel($tablehead, $kolomhead++, "Uraian");
		xlsWriteLabel($tablehead, $kolomhead++, "Group");
		xlsWriteLabel($tablehead, $kolomhead++, "Debet Akhir Tahun Lalu");
		xlsWriteLabel($tablehead, $kolomhead++, "Kredit Akhir Tahun Lalu");
		xlsWriteLabel($tablehead, $kolomhead++, "Debet Penyesuaian");
		xlsWriteLabel($tablehead, $kolomhead++, "Kredit Penyesuaian");
		xlsWriteLabel($tablehead, $kolomhead++, "Debet Ns Setelah Penyesuaian");
		xlsWriteLabel($tablehead, $kolomhead++, "Kredit Ns Setelah Penyesuaian");
		xlsWriteLabel($tablehead, $kolomhead++, "Debet Laba Rugi");
		xlsWriteLabel($tablehead, $kolomhead++, "Kreditdebet Laba Rugi");

		foreach ($this->Neraca_saldo_model->get_all() as $data) {
			$kolombody = 0;

			//ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
			xlsWriteNumber($tablebody, $kolombody++, $nourut);
			xlsWriteLabel($tablebody, $kolombody++, $data->uuid_kode_akun);
			xlsWriteLabel($tablebody, $kolombody++, $data->kode_akun);
			xlsWriteLabel($tablebody, $kolombody++, $data->nama_akun);
			xlsWriteLabel($tablebody, $kolombody++, $data->uraian);
			xlsWriteLabel($tablebody, $kolombody++, $data->group);
			xlsWriteNumber($tablebody, $kolombody++, $data->debet_akhir_tahun_lalu);
			xlsWriteNumber($tablebody, $kolombody++, $data->kredit_akhir_tahun_lalu);
			xlsWriteNumber($tablebody, $kolombody++, $data->debet_penyesuaian);
			xlsWriteNumber($tablebody, $kolombody++, $data->kredit_penyesuaian);
			xlsWriteNumber($tablebody, $kolombody++, $data->debet_ns_setelah_penyesuaian);
			xlsWriteNumber($tablebody, $kolombody++, $data->kredit_ns_setelah_penyesuaian);
			xlsWriteNumber($tablebody, $kolombody++, $data->debet_laba_rugi);
			xlsWriteNumber($tablebody, $kolombody++, $data->kreditdebet_laba_rugi);

			$tablebody++;
			$nourut++;
		}

		xlsEOF();
		exit();
	}
}

/* End of file Neraca_saldo.php */
/* Location: ./application/controllers/Neraca_saldo.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2025-02-12 07:32:35 */
/* http://harviacode.com */