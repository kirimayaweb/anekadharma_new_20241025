<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Tbl_neraca_data extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_login();
		$this->load->model(array('Tbl_neraca_data_model', 'Tbl_accounting_detail_model'));
		$this->load->library('form_validation');
		$this->load->library('datatables');
		$this->load->library('Pdf');
		$this->load->helper(array('nominal'));
	}

	public function index_BU()
	{
		$q = urldecode($this->input->get('q', TRUE));
		$start = intval($this->input->get('start'));

		if ($q <> '') {
			$config['base_url'] = base_url() . 'tbl_neraca_data/index.html?q=' . urlencode($q);
			$config['first_url'] = base_url() . 'tbl_neraca_data/index.html?q=' . urlencode($q);
		} else {
			$config['base_url'] = base_url() . 'tbl_neraca_data/index.html';
			$config['first_url'] = base_url() . 'tbl_neraca_data/index.html';
		}

		$config['per_page'] = 10;
		$config['page_query_string'] = TRUE;
		$config['total_rows'] = $this->Tbl_neraca_data_model->total_rows($q);
		$tbl_neraca_data = $this->Tbl_neraca_data_model->get_limit_data($config['per_page'], $start, $q);

		$this->load->library('pagination');
		$this->pagination->initialize($config);

		$data = array(
			'tbl_neraca_data_data' => $tbl_neraca_data,
			'q' => $q,
			'pagination' => $this->pagination->create_links(),
			'total_rows' => $config['total_rows'],
			'start' => $start,
		);
		$this->load->view('tbl_neraca_data/tbl_neraca_data_list', $data);
	}

	public function index($laporan_status = null)
	{


		if (isset($laporan_status)) {
			$status_laporan = "laporan";
		} else {
			$status_laporan = "bukan_laporan";
		}

		// print_r($status_laporan);
		// die;


		$Tbl_neraca_data = $this->Tbl_neraca_data_model->get_all();
		$start = 0;
		$data = array(
			'Tbl_neraca_data' => $Tbl_neraca_data,
			'start' => $start,
			'status_laporan' => $status_laporan,
			'action_input_neraca_baru' => site_url('Tbl_neraca_data/neraca_form_input/'),
			'action_input_neraca_baru_bulanan' => site_url('Tbl_neraca_data/neraca_form_input_bulanan/'),
		);

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_neraca_data/adminlte310_tbl_neraca_data_list', $data);
	}



	public function read($id)
	{
		$row = $this->Tbl_neraca_data_model->get_by_id($id);
		if ($row) {
			$data = array(
				'id' => $row->id,
				'date_input' => $row->date_input,
				'date_transaksi' => $row->date_transaksi,
				'tahun_transaksi' => $row->tahun_transaksi,
				'bulan_transaksi' => $row->bulan_transaksi,
				'uuid_data_neraca' => $row->uuid_data_neraca,
				'kas' => $row->kas,
				'bank' => $row->bank,
				'piutang_usaha' => $row->piutang_usaha,
				'piutang_non_usaha' => $row->piutang_non_usaha,
				'persediaan' => $row->persediaan,
				'uang_muka_pajak' => $row->uang_muka_pajak,
				'aktiva_tetap' => $row->aktiva_tetap,
				'aktiva_tetap_berwujud' => $row->aktiva_tetap_berwujud,
				'akumulasi_depresiasi_atb' => $row->akumulasi_depresiasi_atb,
				'piutang_non_usaha_pihak_ketiga' => $row->piutang_non_usaha_pihak_ketiga,
				'piutang_non_usaha_radio' => $row->piutang_non_usaha_radio,
				'ljpj_taman_gedung_kesenian_gabusan' => $row->ljpj - taman_gedung_kesenian_gabusan,
				'ljpj_kompleks_gedung_kesenian' => $row->ljpj_kompleks_gedung_kesenian,
				'ljpj_radio' => $row->ljpj_radio,
				'ljpj_kerjasama_operasi_apotek_dharma_usaha' => $row->ljpj_kerjasama_operasi_apotek_dharma_usaha,
				'ljpj_peternakan' => $row->ljpj_peternakan,
				'ljpj_kerjasama_adwm' => $row->ljpj_kerjasama_adwm,
				'ljpj_kerjasama_pdu_cabean_panggungharjo' => $row->ljpj_kerjasama_pdu_cabean_panggungharjo,
				'utang_usaha' => $row->utang_usaha,
				'utang_pajak' => $row->utang_pajak,
				'utang_lain_lain' => $row->utang_lain_lain,
				'utang_afiliasi' => $row->utang_afiliasi,
				'modal_dasar_dan_penyertaan' => $row->modal_dasar_dan_penyertaan,
				'cadangan_umum' => $row->cadangan_umum,
				'laba_bumd_pad' => $row->laba_bumd_pad,
				'laba_rugi_tahun_lalu' => $row->laba_rugi_tahun_lalu,
				'laba_rugi_tahun_berjalan' => $row->laba_rugi_tahun_berjalan,
			);
			$this->load->view('tbl_neraca_data/tbl_neraca_data_read', $data);
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(site_url('tbl_neraca_data'));
		}
	}

	public function create()
	{
		$data = array(
			'button' => 'Create',
			'action' => site_url('tbl_neraca_data/create_action'),
			'id' => set_value('id'),
			'date_input' => set_value('date_input'),
			'date_transaksi' => set_value('date_transaksi'),
			'tahun_transaksi' => set_value('tahun_transaksi'),
			'bulan_transaksi' => set_value('bulan_transaksi'),
			'uuid_data_neraca' => set_value('uuid_data_neraca'),
			'kas' => set_value('kas'),
			'bank' => set_value('bank'),
			'piutang_usaha' => set_value('piutang_usaha'),
			'piutang_non_usaha' => set_value('piutang_non_usaha'),
			'persediaan' => set_value('persediaan'),
			'uang_muka_pajak' => set_value('uang_muka_pajak'),
			'aktiva_tetap' => set_value('aktiva_tetap'),
			'aktiva_tetap_berwujud' => set_value('aktiva_tetap_berwujud'),
			'akumulasi_depresiasi_atb' => set_value('akumulasi_depresiasi_atb'),
			'piutang_non_usaha_pihak_ketiga' => set_value('piutang_non_usaha_pihak_ketiga'),
			'piutang_non_usaha_radio' => set_value('piutang_non_usaha_radio'),
			'ljpj_taman_gedung_kesenian_gabusan' => set_value('ljpj_taman_gedung_kesenian_gabusan'),
			'ljpj_kompleks_gedung_kesenian' => set_value('ljpj_kompleks_gedung_kesenian'),
			'ljpj_radio' => set_value('ljpj_radio'),
			'ljpj_kerjasama_operasi_apotek_dharma_usaha' => set_value('ljpj_kerjasama_operasi_apotek_dharma_usaha'),
			'ljpj_peternakan' => set_value('ljpj_peternakan'),
			'ljpj_kerjasama_adwm' => set_value('ljpj_kerjasama_adwm'),
			'ljpj_kerjasama_pdu_cabean_panggungharjo' => set_value('ljpj_kerjasama_pdu_cabean_panggungharjo'),
			'utang_usaha' => set_value('utang_usaha'),
			'utang_pajak' => set_value('utang_pajak'),
			'utang_lain_lain' => set_value('utang_lain_lain'),
			'utang_afiliasi' => set_value('utang_afiliasi'),
			'modal_dasar_dan_penyertaan' => set_value('modal_dasar_dan_penyertaan'),
			'cadangan_umum' => set_value('cadangan_umum'),
			'laba_bumd_pad' => set_value('laba_bumd_pad'),
			'laba_rugi_tahun_lalu' => set_value('laba_rugi_tahun_lalu'),
			'laba_rugi_tahun_berjalan' => set_value('laba_rugi_tahun_berjalan'),
		);
		$this->load->view('tbl_neraca_data/tbl_neraca_data_form', $data);
	}

	public function create_action()
	{
		$this->_rules();

		if ($this->form_validation->run() == FALSE) {
			$this->create();
		} else {
			$data = array(
				'date_input' => $this->input->post('date_input', TRUE),
				'date_transaksi' => $this->input->post('date_transaksi', TRUE),
				'tahun_transaksi' => $this->input->post('tahun_transaksi', TRUE),
				'bulan_transaksi' => $this->input->post('bulan_transaksi', TRUE),
				// 'uuid_data_neraca' => $this->input->post('uuid_data_neraca', TRUE),
				'kas' => $this->input->post('kas', TRUE),
				'bank' => $this->input->post('bank', TRUE),
				'piutang_usaha' => $this->input->post('piutang_usaha', TRUE),
				'piutang_non_usaha' => $this->input->post('piutang_non_usaha', TRUE),
				'persediaan' => $this->input->post('persediaan', TRUE),
				'uang_muka_pajak' => $this->input->post('uang_muka_pajak', TRUE),
				'aktiva_tetap' => $this->input->post('aktiva_tetap', TRUE),
				'aktiva_tetap_berwujud' => $this->input->post('aktiva_tetap_berwujud', TRUE),
				'akumulasi_depresiasi_atb' => $this->input->post('akumulasi_depresiasi_atb', TRUE),
				'piutang_non_usaha_pihak_ketiga' => $this->input->post('piutang_non_usaha_pihak_ketiga', TRUE),
				'piutang_non_usaha_radio' => $this->input->post('piutang_non_usaha_radio', TRUE),
				'ljpj_taman_gedung_kesenian_gabusan' => $this->input->post('ljpj_taman_gedung_kesenian_gabusan', TRUE),
				'ljpj_kompleks_gedung_kesenian' => $this->input->post('ljpj_kompleks_gedung_kesenian', TRUE),
				'ljpj_radio' => $this->input->post('ljpj_radio', TRUE),
				'ljpj_kerjasama_operasi_apotek_dharma_usaha' => $this->input->post('ljpj_kerjasama_operasi_apotek_dharma_usaha', TRUE),
				'ljpj_peternakan' => $this->input->post('ljpj_peternakan', TRUE),
				'ljpj_kerjasama_adwm' => $this->input->post('ljpj_kerjasama_adwm', TRUE),
				'ljpj_kerjasama_pdu_cabean_panggungharjo' => $this->input->post('ljpj_kerjasama_pdu_cabean_panggungharjo', TRUE),
				'utang_usaha' => $this->input->post('utang_usaha', TRUE),
				'utang_pajak' => $this->input->post('utang_pajak', TRUE),
				'utang_lain_lain' => $this->input->post('utang_lain_lain', TRUE),
				'utang_afiliasi' => $this->input->post('utang_afiliasi', TRUE),
				'modal_dasar_dan_penyertaan' => $this->input->post('modal_dasar_dan_penyertaan', TRUE),
				'cadangan_umum' => $this->input->post('cadangan_umum', TRUE),
				'laba_bumd_pad' => $this->input->post('laba_bumd_pad', TRUE),
				'laba_rugi_tahun_lalu' => $this->input->post('laba_rugi_tahun_lalu', TRUE),
				'laba_rugi_tahun_berjalan' => $this->input->post('laba_rugi_tahun_berjalan', TRUE),
			);

			$this->Tbl_neraca_data_model->insert($data);
			$this->session->set_flashdata('message', 'Create Record Success');
			redirect(site_url('tbl_neraca_data'));
		}
	}

	public function update($id)
	{
		$row = $this->Tbl_neraca_data_model->get_by_id($id);

		if ($row) {
			$data = array(
				'button' => 'Update',
				'action' => site_url('tbl_neraca_data/update_action'),
				'id' => set_value('id', $row->id),
				'date_input' => set_value('date_input', $row->date_input),
				'date_transaksi' => set_value('date_transaksi', $row->date_transaksi),
				'tahun_transaksi' => set_value('tahun_transaksi', $row->tahun_transaksi),
				'bulan_transaksi' => set_value('bulan_transaksi', $row->bulan_transaksi),
				'uuid_data_neraca' => set_value('uuid_data_neraca', $row->uuid_data_neraca),
				'kas' => set_value('kas', $row->kas),
				'bank' => set_value('bank', $row->bank),
				'piutang_usaha' => set_value('piutang_usaha', $row->piutang_usaha),
				'piutang_non_usaha' => set_value('piutang_non_usaha', $row->piutang_non_usaha),
				'persediaan' => set_value('persediaan', $row->persediaan),
				'uang_muka_pajak' => set_value('uang_muka_pajak', $row->uang_muka_pajak),
				'aktiva_tetap' => set_value('aktiva_tetap', $row->aktiva_tetap),
				'aktiva_tetap_berwujud' => set_value('aktiva_tetap_berwujud', $row->aktiva_tetap_berwujud),
				'akumulasi_depresiasi_atb' => set_value('akumulasi_depresiasi_atb', $row->akumulasi_depresiasi_atb),
				'piutang_non_usaha_pihak_ketiga' => set_value('piutang_non_usaha_pihak_ketiga', $row->piutang_non_usaha_pihak_ketiga),
				'piutang_non_usaha_radio' => set_value('piutang_non_usaha_radio', $row->piutang_non_usaha_radio),
				'ljpj_taman_gedung_kesenian_gabusan' => set_value('ljpj_taman_gedung_kesenian_gabusan', $row->ljpj - taman_gedung_kesenian_gabusan),
				'ljpj_kompleks_gedung_kesenian' => set_value('ljpj_kompleks_gedung_kesenian', $row->ljpj_kompleks_gedung_kesenian),
				'ljpj_radio' => set_value('ljpj_radio', $row->ljpj_radio),
				'ljpj_kerjasama_operasi_apotek_dharma_usaha' => set_value('ljpj_kerjasama_operasi_apotek_dharma_usaha', $row->ljpj_kerjasama_operasi_apotek_dharma_usaha),
				'ljpj_peternakan' => set_value('ljpj_peternakan', $row->ljpj_peternakan),
				'ljpj_kerjasama_adwm' => set_value('ljpj_kerjasama_adwm', $row->ljpj_kerjasama_adwm),
				'ljpj_kerjasama_pdu_cabean_panggungharjo' => set_value('ljpj_kerjasama_pdu_cabean_panggungharjo', $row->ljpj_kerjasama_pdu_cabean_panggungharjo),
				'utang_usaha' => set_value('utang_usaha', $row->utang_usaha),
				'utang_pajak' => set_value('utang_pajak', $row->utang_pajak),
				'utang_lain_lain' => set_value('utang_lain_lain', $row->utang_lain_lain),
				'utang_afiliasi' => set_value('utang_afiliasi', $row->utang_afiliasi),
				'modal_dasar_dan_penyertaan' => set_value('modal_dasar_dan_penyertaan', $row->modal_dasar_dan_penyertaan),
				'cadangan_umum' => set_value('cadangan_umum', $row->cadangan_umum),
				'laba_bumd_pad' => set_value('laba_bumd_pad', $row->laba_bumd_pad),
				'laba_rugi_tahun_lalu' => set_value('laba_rugi_tahun_lalu', $row->laba_rugi_tahun_lalu),
				'laba_rugi_tahun_berjalan' => set_value('laba_rugi_tahun_berjalan', $row->laba_rugi_tahun_berjalan),
			);
			$this->load->view('tbl_neraca_data/tbl_neraca_data_form', $data);
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(site_url('tbl_neraca_data'));
		}
	}

	public function update_action()
	{
		$this->_rules();

		if ($this->form_validation->run() == FALSE) {
			$this->update($this->input->post('id', TRUE));
		} else {
			$data = array(
				'date_input' => $this->input->post('date_input', TRUE),
				'date_transaksi' => $this->input->post('date_transaksi', TRUE),
				'tahun_transaksi' => $this->input->post('tahun_transaksi', TRUE),
				'bulan_transaksi' => $this->input->post('bulan_transaksi', TRUE),
				// 'uuid_data_neraca' => $this->input->post('uuid_data_neraca', TRUE),
				'kas' => $this->input->post('kas', TRUE),
				'bank' => $this->input->post('bank', TRUE),
				'piutang_usaha' => $this->input->post('piutang_usaha', TRUE),
				'piutang_non_usaha' => $this->input->post('piutang_non_usaha', TRUE),
				'persediaan' => $this->input->post('persediaan', TRUE),
				'uang_muka_pajak' => $this->input->post('uang_muka_pajak', TRUE),
				'aktiva_tetap' => $this->input->post('aktiva_tetap', TRUE),
				'aktiva_tetap_berwujud' => $this->input->post('aktiva_tetap_berwujud', TRUE),
				'akumulasi_depresiasi_atb' => $this->input->post('akumulasi_depresiasi_atb', TRUE),
				'piutang_non_usaha_pihak_ketiga' => $this->input->post('piutang_non_usaha_pihak_ketiga', TRUE),
				'piutang_non_usaha_radio' => $this->input->post('piutang_non_usaha_radio', TRUE),
				'ljpj_taman_gedung_kesenian_gabusan' => $this->input->post('ljpj_taman_gedung_kesenian_gabusan', TRUE),
				'ljpj_kompleks_gedung_kesenian' => $this->input->post('ljpj_kompleks_gedung_kesenian', TRUE),
				'ljpj_radio' => $this->input->post('ljpj_radio', TRUE),
				'ljpj_kerjasama_operasi_apotek_dharma_usaha' => $this->input->post('ljpj_kerjasama_operasi_apotek_dharma_usaha', TRUE),
				'ljpj_peternakan' => $this->input->post('ljpj_peternakan', TRUE),
				'ljpj_kerjasama_adwm' => $this->input->post('ljpj_kerjasama_adwm', TRUE),
				'ljpj_kerjasama_pdu_cabean_panggungharjo' => $this->input->post('ljpj_kerjasama_pdu_cabean_panggungharjo', TRUE),
				'utang_usaha' => $this->input->post('utang_usaha', TRUE),
				'utang_pajak' => $this->input->post('utang_pajak', TRUE),
				'utang_lain_lain' => $this->input->post('utang_lain_lain', TRUE),
				'utang_afiliasi' => $this->input->post('utang_afiliasi', TRUE),
				'modal_dasar_dan_penyertaan' => $this->input->post('modal_dasar_dan_penyertaan', TRUE),
				'cadangan_umum' => $this->input->post('cadangan_umum', TRUE),
				'laba_bumd_pad' => $this->input->post('laba_bumd_pad', TRUE),
				'laba_rugi_tahun_lalu' => $this->input->post('laba_rugi_tahun_lalu', TRUE),
				'laba_rugi_tahun_berjalan' => $this->input->post('laba_rugi_tahun_berjalan', TRUE),
			);

			$this->Tbl_neraca_data_model->update($this->input->post('id', TRUE), $data);
			$this->session->set_flashdata('message', 'Update Record Success');
			redirect(site_url('tbl_neraca_data'));
		}
	}

	public function delete($id)
	{
		$row = $this->Tbl_neraca_data_model->get_by_id($id);

		if ($row) {
			$this->Tbl_neraca_data_model->delete($id);
			$this->session->set_flashdata('message', 'Delete Record Success');
			redirect(site_url('tbl_neraca_data'));
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(site_url('tbl_neraca_data'));
		}
	}

	public function _rules()
	{
		// $this->form_validation->set_rules('date_input', 'date input', 'trim|required');
		$this->form_validation->set_rules('date_transaksi', 'date transaksi', 'trim|required');
		// $this->form_validation->set_rules('tahun_transaksi', 'tahun transaksi', 'trim|required');
		// $this->form_validation->set_rules('bulan_transaksi', 'bulan transaksi', 'trim|required');
		// $this->form_validation->set_rules('uuid_data_neraca', 'uuid data neraca', 'trim|required');
		// $this->form_validation->set_rules('kas', 'kas', 'trim|required|numeric');
		// $this->form_validation->set_rules('bank', 'bank', 'trim|required|numeric');
		// $this->form_validation->set_rules('piutang_usaha', 'piutang usaha', 'trim|required|numeric');
		// $this->form_validation->set_rules('piutang_non_usaha', 'piutang non usaha', 'trim|required|numeric');
		// $this->form_validation->set_rules('persediaan', 'persediaan', 'trim|required|numeric');
		// $this->form_validation->set_rules('uang_muka_pajak', 'uang muka pajak', 'trim|required|numeric');
		// $this->form_validation->set_rules('aktiva_tetap', 'aktiva tetap', 'trim|required|numeric');
		// $this->form_validation->set_rules('aktiva_tetap_berwujud', 'aktiva tetap berwujud', 'trim|required|numeric');
		// $this->form_validation->set_rules('akumulasi_depresiasi_atb', 'akumulasi depresiasi atb', 'trim|required|numeric');
		// $this->form_validation->set_rules('piutang_non_usaha_pihak_ketiga', 'piutang non usaha pihak ketiga', 'trim|required|numeric');
		// $this->form_validation->set_rules('piutang_non_usaha_radio', 'piutang non usaha radio', 'trim|required|numeric');
		// $this->form_validation->set_rules('ljpj_taman_gedung_kesenian_gabusan', 'ljpj-taman gedung kesenian gabusan', 'trim|required|numeric');
		// $this->form_validation->set_rules('ljpj_kompleks_gedung_kesenian', 'ljpj kompleks gedung kesenian', 'trim|required|numeric');
		// $this->form_validation->set_rules('ljpj_radio', 'ljpj radio', 'trim|required|numeric');
		// $this->form_validation->set_rules('ljpj_kerjasama_operasi_apotek_dharma_usaha', 'ljpj kerjasama operasi apotek dharma usaha', 'trim|required|numeric');
		// $this->form_validation->set_rules('ljpj_peternakan', 'ljpj peternakan', 'trim|required|numeric');
		// $this->form_validation->set_rules('ljpj_kerjasama_adwm', 'ljpj kerjasama adwm', 'trim|required|numeric');
		// $this->form_validation->set_rules('ljpj_kerjasama_pdu_cabean_panggungharjo', 'ljpj kerjasama pdu cabean panggungharjo', 'trim|required|numeric');
		// $this->form_validation->set_rules('utang_usaha', 'utang usaha', 'trim|required|numeric');
		// $this->form_validation->set_rules('utang_pajak', 'utang pajak', 'trim|required|numeric');
		// $this->form_validation->set_rules('utang_lain_lain', 'utang lain lain', 'trim|required|numeric');
		// $this->form_validation->set_rules('utang_afiliasi', 'utang afiliasi', 'trim|required|numeric');
		// $this->form_validation->set_rules('modal_dasar_dan_penyertaan', 'modal dasar dan penyertaan', 'trim|required|numeric');
		// $this->form_validation->set_rules('cadangan_umum', 'cadangan umum', 'trim|required|numeric');
		// $this->form_validation->set_rules('laba_bumd_pad', 'laba bumd pad', 'trim|required|numeric');
		// $this->form_validation->set_rules('laba_rugi_tahun_lalu', 'laba rugi tahun lalu', 'trim|required|numeric');
		// $this->form_validation->set_rules('laba_rugi_tahun_berjalan', 'laba rugi tahun berjalan', 'trim|required|numeric');

		$this->form_validation->set_rules('id', 'id', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
	}

	public function excel()
	{
		$this->load->helper('exportexcel');
		$namaFile = "tbl_neraca_data.xls";
		$judul = "tbl_neraca_data";
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
		xlsWriteLabel($tablehead, $kolomhead++, "Date Input");
		xlsWriteLabel($tablehead, $kolomhead++, "Date Transaksi");
		xlsWriteLabel($tablehead, $kolomhead++, "Tahun Transaksi");
		xlsWriteLabel($tablehead, $kolomhead++, "Bulan Transaksi");
		xlsWriteLabel($tablehead, $kolomhead++, "Uuid Data Neraca");
		xlsWriteLabel($tablehead, $kolomhead++, "Kas");
		xlsWriteLabel($tablehead, $kolomhead++, "Bank");
		xlsWriteLabel($tablehead, $kolomhead++, "Piutang Usaha");
		xlsWriteLabel($tablehead, $kolomhead++, "Piutang Non Usaha");
		xlsWriteLabel($tablehead, $kolomhead++, "Persediaan");
		xlsWriteLabel($tablehead, $kolomhead++, "Uang Muka Pajak");
		xlsWriteLabel($tablehead, $kolomhead++, "Aktiva Tetap");
		xlsWriteLabel($tablehead, $kolomhead++, "Aktiva Tetap Berwujud");
		xlsWriteLabel($tablehead, $kolomhead++, "Akumulasi Depresiasi Atb");
		xlsWriteLabel($tablehead, $kolomhead++, "Piutang Non Usaha Pihak Ketiga");
		xlsWriteLabel($tablehead, $kolomhead++, "Piutang Non Usaha Radio");
		xlsWriteLabel($tablehead, $kolomhead++, "Ljpj-taman Gedung Kesenian Gabusan");
		xlsWriteLabel($tablehead, $kolomhead++, "Ljpj Kompleks Gedung Kesenian");
		xlsWriteLabel($tablehead, $kolomhead++, "Ljpj Radio");
		xlsWriteLabel($tablehead, $kolomhead++, "Ljpj Kerjasama Operasi Apotek Dharma Usaha");
		xlsWriteLabel($tablehead, $kolomhead++, "Ljpj Peternakan");
		xlsWriteLabel($tablehead, $kolomhead++, "Ljpj Kerjasama Adwm");
		xlsWriteLabel($tablehead, $kolomhead++, "Ljpj Kerjasama Pdu Cabean Panggungharjo");
		xlsWriteLabel($tablehead, $kolomhead++, "Utang Usaha");
		xlsWriteLabel($tablehead, $kolomhead++, "Utang Pajak");
		xlsWriteLabel($tablehead, $kolomhead++, "Utang Lain Lain");
		xlsWriteLabel($tablehead, $kolomhead++, "Utang Afiliasi");
		xlsWriteLabel($tablehead, $kolomhead++, "Modal Dasar Dan Penyertaan");
		xlsWriteLabel($tablehead, $kolomhead++, "Cadangan Umum");
		xlsWriteLabel($tablehead, $kolomhead++, "Laba Bumd Pad");
		xlsWriteLabel($tablehead, $kolomhead++, "Laba Rugi Tahun Lalu");
		xlsWriteLabel($tablehead, $kolomhead++, "Laba Rugi Tahun Berjalan");

		foreach ($this->Tbl_neraca_data_model->get_all() as $data) {
			$kolombody = 0;

			//ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
			xlsWriteNumber($tablebody, $kolombody++, $nourut);
			xlsWriteLabel($tablebody, $kolombody++, $data->date_input);
			xlsWriteLabel($tablebody, $kolombody++, $data->date_transaksi);
			xlsWriteNumber($tablebody, $kolombody++, $data->tahun_transaksi);
			xlsWriteNumber($tablebody, $kolombody++, $data->bulan_transaksi);
			xlsWriteLabel($tablebody, $kolombody++, $data->uuid_data_neraca);
			xlsWriteNumber($tablebody, $kolombody++, $data->kas);
			xlsWriteNumber($tablebody, $kolombody++, $data->bank);
			xlsWriteNumber($tablebody, $kolombody++, $data->piutang_usaha);
			xlsWriteNumber($tablebody, $kolombody++, $data->piutang_non_usaha);
			xlsWriteNumber($tablebody, $kolombody++, $data->persediaan);
			xlsWriteNumber($tablebody, $kolombody++, $data->uang_muka_pajak);
			xlsWriteNumber($tablebody, $kolombody++, $data->aktiva_tetap);
			xlsWriteNumber($tablebody, $kolombody++, $data->aktiva_tetap_berwujud);
			xlsWriteNumber($tablebody, $kolombody++, $data->akumulasi_depresiasi_atb);
			xlsWriteNumber($tablebody, $kolombody++, $data->piutang_non_usaha_pihak_ketiga);
			xlsWriteNumber($tablebody, $kolombody++, $data->piutang_non_usaha_radio);
			xlsWriteNumber($tablebody, $kolombody++, $data->ljpj - taman_gedung_kesenian_gabusan);
			xlsWriteNumber($tablebody, $kolombody++, $data->ljpj_kompleks_gedung_kesenian);
			xlsWriteNumber($tablebody, $kolombody++, $data->ljpj_radio);
			xlsWriteNumber($tablebody, $kolombody++, $data->ljpj_kerjasama_operasi_apotek_dharma_usaha);
			xlsWriteNumber($tablebody, $kolombody++, $data->ljpj_peternakan);
			xlsWriteNumber($tablebody, $kolombody++, $data->ljpj_kerjasama_adwm);
			xlsWriteNumber($tablebody, $kolombody++, $data->ljpj_kerjasama_pdu_cabean_panggungharjo);
			xlsWriteNumber($tablebody, $kolombody++, $data->utang_usaha);
			xlsWriteNumber($tablebody, $kolombody++, $data->utang_pajak);
			xlsWriteNumber($tablebody, $kolombody++, $data->utang_lain_lain);
			xlsWriteNumber($tablebody, $kolombody++, $data->utang_afiliasi);
			xlsWriteNumber($tablebody, $kolombody++, $data->modal_dasar_dan_penyertaan);
			xlsWriteNumber($tablebody, $kolombody++, $data->cadangan_umum);
			xlsWriteNumber($tablebody, $kolombody++, $data->laba_bumd_pad);
			xlsWriteNumber($tablebody, $kolombody++, $data->laba_rugi_tahun_lalu);
			xlsWriteNumber($tablebody, $kolombody++, $data->laba_rugi_tahun_berjalan);

			$tablebody++;
			$nourut++;
		}

		xlsEOF();
		exit();
	}


	public function neraca_form_input()
	{

		// print_r("neraca_form_input");
		// print_r("<br/>");
		// print_r($this->input->post('tahun_neraca', TRUE));
		// print_r("<br/>");


		$data_detail = $this->Tbl_neraca_data_model->get_all_by_year($this->input->post('tahun_neraca', TRUE));

		// print_r($data_detail);
		// print_r("<br/>");
		// print_r("<br/>");

		// if(isset($data_detail)){
		// 	print_r("Ada data");
		// }else{
		// 	print_r("Tidak ada data");
		// }

		// die;


		if (isset($data_detail)) {
			$uuid_data_neraca = $data_detail->tahun_transaksi;
			$data = array(
				'button' => 'Update',
				'action' => site_url('Tbl_neraca_data/create_action_neraca/' . $uuid_data_neraca),
				'id' => set_value('id'),
				'data_detail' => $data_detail,
				'uuid_data_neraca' => $uuid_data_neraca,
				'tahun_neraca' => $data_detail->tahun_transaksi,
				'bulan_transaksi' => $data_detail->bulan_transaksi,
			);
		} else {
			$data = array(
				'button' => 'Simpan',
				'action' => site_url('Tbl_neraca_data/create_action_neraca'),
				'id' => set_value('id'),
				'data_detail' => $data_detail,
				'uuid_data_neraca' => "0",
				'tahun_neraca' => $this->input->post('tahun_neraca', TRUE),
				'bulan_transaksi' => 0,
			);
		}
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_neraca_data/adminlte310_neraca_form', $data);
	}



	public function neraca_form_input_bulanan()
	{

		// print_r("neraca_form_input");
		// print_r("<br/>");
		// print_r($this->input->post('tahun_neraca', TRUE));
		// print_r("<br/>");


		// print_r($this->input->post('bulan_neraca', TRUE));
		// print_r("<br/>");
		// print_r(date("Y", strtotime($this->input->post('bulan_neraca', TRUE))));
		// print_r("<br/>");
		// print_r(date("m", strtotime($this->input->post('bulan_neraca', TRUE))));
		// print_r("<br/>");
		// die;
		$bulan_transaksi = date("m", strtotime($this->input->post('bulan_neraca', TRUE)));
		$tahun_transaksi = date("Y", strtotime($this->input->post('bulan_neraca', TRUE)));

		$data_detail = $this->Tbl_neraca_data_model->get_all_by_MONTH_year($bulan_transaksi, $tahun_transaksi);

		// print_r($data_detail);
		// print_r("<br/>");
		// print_r("<br/>");
		// die;
		// if(isset($data_detail)){
		// 	print_r("Ada data");
		// }else{
		// 	print_r("Tidak ada data");
		// }

		// die;


		if (isset($data_detail)) {
			$uuid_data_neraca = $data_detail->tahun_transaksi;
			$data = array(
				'button' => 'Update',
				'action' => site_url('Tbl_neraca_data/create_action_neraca/' . $uuid_data_neraca),
				'id' => set_value('id'),
				'data_detail' => $data_detail,
				'uuid_data_neraca' => $uuid_data_neraca,
				'tahun_neraca' => $tahun_transaksi,
				'bulan_transaksi' => $bulan_transaksi,
			);
		} else {
			$data = array(
				'button' => 'Simpan',
				'action' => site_url('Tbl_neraca_data/create_action_neraca'),
				'id' => set_value('id'),
				'data_detail' => $data_detail,
				'uuid_data_neraca' => "0",
				'tahun_neraca' => $tahun_transaksi,
				'bulan_transaksi' => $bulan_transaksi,
			);
		}
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_neraca_data/adminlte310_neraca_form', $data);
	}




	public function neraca_form($uuid_data_neraca = null)
	{

		// print_r($uuid_data_neraca);
		// die;

		if ($uuid_data_neraca) {
			$data_detail = $this->Tbl_neraca_data_model->get_all_by_uuid_data_neraca($uuid_data_neraca);
		} else {
			$year_sekarang = date("Y", strtotime(date("Y-m-d H:i:s")));
			$data_detail = $this->Tbl_neraca_data_model->get_all_by_year($year_sekarang);
			// if ($data_detail) {
			// 	print_r($data_detail->uuid_data_neraca);
			// 	redirect(site_url('tbl_neraca_data/neraca_form/' . $data_detail->uuid_data_neraca));
			// }
		}

		if ($data_detail) {
			$data = array(
				'button' => 'Update',
				'action' => site_url('Tbl_neraca_data/update_action_neraca/' . $uuid_data_neraca),
				'id' => set_value('id'),
				'data_detail' => $data_detail,
				'uuid_data_neraca' => $uuid_data_neraca,
				'tahun_neraca' => $data_detail->tahun_transaksi,
				'bulan_transaksi' => $data_detail->bulan_transaksi,
			);
		} else {
			$data = array(
				'button' => 'Simpan',
				'action' => site_url('Tbl_neraca_data/create_action_neraca'),
				'id' => set_value('id'),
				'data_detail' => $data_detail,
				'uuid_data_neraca' => $uuid_data_neraca,
				'tahun_neraca' => $data_detail->tahun_transaksi,
				'bulan_transaksi' => 0,
			);
		}


		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_neraca_data/adminlte310_neraca_form', $data);
	}

	public function create_action_neraca($uuid_data_neraca = null)
	{
		// print_r($uuid_data_neraca);
		// print_r("<br/>");
		// print_r("create_action_neraca");
		// print_r("<br/>");
		// // die;

		if (isset($uuid_data_neraca)) {
			$get_id = $this->Tbl_neraca_data_model->get_all_by_uuid_data_neraca($uuid_data_neraca)->id;
		} else {
			$uuid_data_neraca = null;
		}



		$data = array(
			'date_input' => date("Y-m-d"),
			'date_transaksi' => date("Y-m-d"),
			'tahun_transaksi' => $this->input->post('tahun_transaksi', TRUE),
			'bulan_transaksi' => $this->input->post('bulan_transaksi', TRUE),
			// 'uuid_data_neraca' => $this->input->post('uuid_data_neraca', TRUE),

			// 'kas' => preg_replace("/[^0-9]/", "", $this->input->post('kas', TRUE)),
			'kas' => $this->input->post('kas', TRUE),

			// preg_replace('/[^\d-]+/', '', $myvariable); 

			// 'bank' => preg_replace("/[^0-9]/", "", $this->input->post('bank', TRUE)),
			'bank' => $this->input->post('bank', TRUE),

			'piutang_usaha' => preg_replace("/[^0-9]/", "", $this->input->post('piutang_usaha', TRUE)),
			'piutang_non_usaha' => preg_replace("/[^0-9]/", "", $this->input->post('piutang_non_usaha', TRUE)),
			'persediaan' => preg_replace("/[^0-9]/", "", $this->input->post('persediaan', TRUE)),
			'uang_muka_pajak' => preg_replace("/[^0-9]/", "", $this->input->post('uang_muka_pajak', TRUE)),
			'aktiva_tetap' => preg_replace("/[^0-9]/", "", $this->input->post('aktiva_tetap', TRUE)),
			'aktiva_tetap_berwujud' => preg_replace("/[^0-9]/", "", $this->input->post('aktiva_tetap_berwujud', TRUE)),
			'akumulasi_depresiasi_atb' =>  preg_replace("/[^0-9]/", "", $this->input->post('akumulasi_depresiasi_atb', TRUE)),
			'piutang_non_usaha_pihak_ketiga' => preg_replace("/[^0-9]/", "", $this->input->post('piutang_non_usaha_pihak_ketiga', TRUE)),
			'piutang_non_usaha_radio' => preg_replace("/[^0-9]/", "", $this->input->post('piutang_non_usaha_radio', TRUE)),
			'ljpj_taman_gedung_kesenian_gabusan' => preg_replace("/[^0-9]/", "", $this->input->post('ljpj_taman_gedung_kesenian_gabusan', TRUE)),
			'ljpj_kompleks_gedung_kesenian' => preg_replace("/[^0-9]/", "", $this->input->post('ljpj_kompleks_gedung_kesenian', TRUE)),
			'ljpj_radio' => preg_replace("/[^0-9]/", "", $this->input->post('ljpj_radio', TRUE)),
			'ljpj_kerjasama_operasi_apotek_dharma_usaha' => preg_replace("/[^0-9]/", "", $this->input->post('ljpj_kerjasama_operasi_apotek_dharma_usaha', TRUE)),
			'ljpj_peternakan' => preg_replace("/[^0-9]/", "", $this->input->post('ljpj_peternakan', TRUE)),
			'ljpj_kerjasama_adwm' => preg_replace("/[^0-9]/", "", $this->input->post('ljpj_kerjasama_adwm', TRUE)),
			'ljpj_kerjasama_pdu_cabean_panggungharjo' => preg_replace("/[^0-9]/", "", $this->input->post('ljpj_kerjasama_pdu_cabean_panggungharjo', TRUE)),
			'utang_usaha' => preg_replace("/[^0-9]/", "", $this->input->post('utang_usaha', TRUE)),
			'utang_pajak' => preg_replace("/[^0-9]/", "", $this->input->post('utang_pajak', TRUE)),
			'utang_lain_lain' => preg_replace("/[^0-9]/", "", $this->input->post('utang_lain_lain', TRUE)),
			'utang_afiliasi' => preg_replace("/[^0-9]/", "", $this->input->post('utang_afiliasi', TRUE)),
			'modal_dasar_dan_penyertaan' => preg_replace("/[^0-9]/", "", $this->input->post('modal_dasar_dan_penyertaan', TRUE)),
			'cadangan_umum' => preg_replace("/[^0-9]/", "", $this->input->post('cadangan_umum', TRUE)),
			'laba_bumd_pad' => preg_replace("/[^0-9]/", "", $this->input->post('laba_bumd_pad', TRUE)),
			'laba_rugi_tahun_lalu' => preg_replace("/[^0-9]/", "", $this->input->post('laba_rugi_tahun_lalu', TRUE)),
			'laba_rugi_tahun_berjalan' => preg_replace("/[^0-9]/", "", $this->input->post('laba_rugi_tahun_berjalan', TRUE)),
		);

		// print_r($data);
		// print_r("<br/>");
		// die;

		if (isset($uuid_data_neraca)) {
			// update data neraca
			// print_r("UPDATE DATA");
			// die;
			$this->Tbl_neraca_data_model->update($get_id, $data);
		} else {
			// data baru, create tahun
			// print_r("DATA BARU");
			// die;
			$this->Tbl_neraca_data_model->insert($data);
		}


		$this->session->set_flashdata('message', 'Create Record Success');
		redirect(site_url('tbl_neraca_data'));
	}



	public function update_action_neraca($uuid_data_neraca = null)
	{
		// print_r($uuid_data_neraca);
		// print_r("<br/>");
		// print_r("update_action_neraca");
		// print_r("<br/>");
		// print_r($this->input->post('kas', TRUE));
		// print_r("<br/>");
		// die;

		$data_detail = $this->Tbl_neraca_data_model->get_all_by_uuid_data_neraca($uuid_data_neraca);
		// $year_sekarang = date("Y", strtotime(date("Y-m-d H:i:s")));

				print_r($this->input->post('kas', TRUE));
				print_r("<br/>");
				print_r($this->input->post('bank', TRUE));
				print_r("<br/>");

		// die;


		$data = array(
			// 'date_input' => $this->input->post('date_input', TRUE),
			'date_transaksi' => date("Y-m-d H:i:s"),
			'tahun_transaksi' => $data_detail->tahun_transaksi,
			'bulan_transaksi' => $data_detail->bulan_transaksi,
			// 'uuid_data_neraca' => $this->input->post('uuid_data_neraca', TRUE),

			// 'kas' => preg_replace("/[^0-9]/", "", $this->input->post('kas', TRUE)),
			'kas' => $this->input->post('kas', TRUE),

			// 'bank' => preg_replace("/[^0-9]/", "", $this->input->post('bank', TRUE)),
			'bank' => $this->input->post('bank', TRUE),

			'piutang_usaha' => preg_replace("/[^0-9]/", "", $this->input->post('piutang_usaha', TRUE)),
			'piutang_non_usaha' => preg_replace("/[^0-9]/", "", $this->input->post('piutang_non_usaha', TRUE)),
			'persediaan' => preg_replace("/[^0-9]/", "", $this->input->post('persediaan', TRUE)),
			'uang_muka_pajak' => preg_replace("/[^0-9]/", "", $this->input->post('uang_muka_pajak', TRUE)),
			'aktiva_tetap' => preg_replace("/[^0-9]/", "", $this->input->post('aktiva_tetap', TRUE)),
			'aktiva_tetap_berwujud' => preg_replace("/[^0-9]/", "", $this->input->post('aktiva_tetap_berwujud', TRUE)),
			'akumulasi_depresiasi_atb' =>  preg_replace("/[^0-9]/", "", $this->input->post('akumulasi_depresiasi_atb', TRUE)),
			'piutang_non_usaha_pihak_ketiga' => preg_replace("/[^0-9]/", "", $this->input->post('piutang_non_usaha_pihak_ketiga', TRUE)),
			'piutang_non_usaha_radio' => preg_replace("/[^0-9]/", "", $this->input->post('piutang_non_usaha_radio', TRUE)),
			'ljpj_taman_gedung_kesenian_gabusan' => preg_replace("/[^0-9]/", "", $this->input->post('ljpj_taman_gedung_kesenian_gabusan', TRUE)),
			'ljpj_kompleks_gedung_kesenian' => preg_replace("/[^0-9]/", "", $this->input->post('ljpj_kompleks_gedung_kesenian', TRUE)),
			'ljpj_radio' => preg_replace("/[^0-9]/", "", $this->input->post('ljpj_radio', TRUE)),
			'ljpj_kerjasama_operasi_apotek_dharma_usaha' => preg_replace("/[^0-9]/", "", $this->input->post('ljpj_kerjasama_operasi_apotek_dharma_usaha', TRUE)),
			'ljpj_peternakan' => preg_replace("/[^0-9]/", "", $this->input->post('ljpj_peternakan', TRUE)),
			'ljpj_kerjasama_adwm' => preg_replace("/[^0-9]/", "", $this->input->post('ljpj_kerjasama_adwm', TRUE)),
			'ljpj_kerjasama_pdu_cabean_panggungharjo' => preg_replace("/[^0-9]/", "", $this->input->post('ljpj_kerjasama_pdu_cabean_panggungharjo', TRUE)),
			'utang_usaha' => preg_replace("/[^0-9]/", "", $this->input->post('utang_usaha', TRUE)),
			'utang_pajak' => preg_replace("/[^0-9]/", "", $this->input->post('utang_pajak', TRUE)),
			'utang_lain_lain' => preg_replace("/[^0-9]/", "", $this->input->post('utang_lain_lain', TRUE)),
			'utang_afiliasi' => preg_replace("/[^0-9]/", "", $this->input->post('utang_afiliasi', TRUE)),
			'modal_dasar_dan_penyertaan' => preg_replace("/[^0-9]/", "", $this->input->post('modal_dasar_dan_penyertaan', TRUE)),
			'cadangan_umum' => preg_replace("/[^0-9]/", "", $this->input->post('cadangan_umum', TRUE)),
			'laba_bumd_pad' => preg_replace("/[^0-9]/", "", $this->input->post('laba_bumd_pad', TRUE)),
			'laba_rugi_tahun_lalu' => preg_replace("/[^0-9]/", "", $this->input->post('laba_rugi_tahun_lalu', TRUE)),
			'laba_rugi_tahun_berjalan' => preg_replace("/[^0-9]/", "", $this->input->post('laba_rugi_tahun_berjalan', TRUE)),
		);


		$this->Tbl_neraca_data_model->update_by_uuid_data_neraca($uuid_data_neraca, $data);

		print_r($data);
		die;

		$this->session->set_flashdata('message', 'Create Record Success');
		redirect(site_url('tbl_neraca_data'));
	}




	public function neraca_view()
	{
		$data_detail = $this->Tbl_accounting_detail_model->get_all();

		$data = array(
			'data_detail' => $data_detail,
			// 'start' => $start,
		);

		// $this->load->view('tbl_accounting_detail/tbl_accounting_detail_list');
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_neraca_data/adminlte310_neraca_view', $data);
	}





	public function neraca_cetak($uuid_data_neraca = null)
	{
		if ($uuid_data_neraca) {
			$data_detail = $this->Tbl_neraca_data_model->get_all_by_uuid_data_neraca($uuid_data_neraca);
		} else {
			$year_sekarang = date("Y", strtotime(date("Y-m-d H:i:s")));

			$data_detail = $this->Tbl_neraca_data_model->get_all_by_year($year_sekarang);
		}




		// 2.a. PERSIAPAN LIBRARY
		$this->load->library('PdfGenerator');

		// 2.b. PERSIAPAN DATA
		$data = array(
			// 'button' => 'Simpan',
			// 'action' => site_url('Tbl_neraca_data/create_action_neraca'),
			// 'id' => set_value('id'),
			'data_detail' => $data_detail,
			'tahun_neraca' => $data_detail->tahun_transaksi,
			'bulan_neraca' => $data_detail->bulan_transaksi,
		);


		// 2.C. MENAMPILKAN FILE DATA
		// $data = array_merge($data);
		$html = $this->load->view('anekadharma/tbl_neraca_data/adminlte310_neraca_cetak.php', $data, true);

		// 2.d. CONVERT TAMPILAN FILE DATA MENJADI FILE PDF
		$this->pdf->loadHtml($html);
		$this->pdf->render();

		$this->pdf->stream("CETAK_NERACA.pdf", array("Attachment" => 0));
	}
}

/* End of file Tbl_neraca_data.php */
/* Location: ./application/controllers/Tbl_neraca_data.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-09-28 09:33:24 */
/* http://harviacode.com */