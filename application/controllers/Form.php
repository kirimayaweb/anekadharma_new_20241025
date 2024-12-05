<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Form extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		is_login();
		// $this->load->model('ProvinsiModel');
		// $this->load->model('KotaModel');
		// $this->load->model('JenisModel');
		// $this->load->model('TipeModel');
		// $this->load->model('KetinggianModel');
		// $this->load->model('Tbl_desa_model');
	}

	public function index()
	{
		$data['provinsi'] = $this->ProvinsiModel->view();

		$this->load->view('form', $data);
	}


		public function laporan_pdf(){

			$data = array(
				"dataku" => array(
					"nama" => "Petani Kode",
					"url" => "http://petanikode.com"
				)
			);
		
			$this->load->library('pdf');
		
			$this->pdf->setPaper('A4', 'potrait');
			$this->pdf->filename = "laporan-petanikode.pdf";
			$this->pdf->load_view('laporan_pdf', $data);
		
		
		}
	


	public function listKota()
	{
		// Ambil data ID Provinsi yang dikirim via ajax post
		$id_provinsi = $this->input->post('id_provinsi');

		$kota = $this->KotaModel->viewByProvinsi($id_provinsi);

		// Buat variabel untuk menampung tag-tag option nya
		// Set defaultnya dengan tag option Pilih
		$lists = "<option value=''>Pilih</option>";

		foreach ($kota as $data) {
			$lists .= "<option value='" . $data->id . "'>" . $data->nama . "</option>"; // Tambahkan tag option ke variabel $lists
		}

		$callback = array('list_kota' => $lists); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota

		echo json_encode($callback); // konversi varibael $callback menjadi JSON
	}

	public function listjenis()
	{
		// Ambil data ID Provinsi yang dikirim via ajax post
		$data_desa = $this->Tbl_desa_model->get_by_id($this->input->post('id_desa'));
		$id_kawasan = $data_desa->id_kawasan;
		$jenis = $this->JenisModel->viewByidkawasan($id_kawasan);

		// Buat variabel untuk menampung tag-tag option nya
		// Set defaultnya dengan tag option Pilih
		$lists = "<option value=''>Pilih Jenis</option>";

		foreach ($jenis as $data) {
			$lists .= "<option value='" . $data->id_jenis . "'>" . $data->jenis . "</option>"; // Tambahkan tag option ke variabel $lists
		}

		$callback = array('list_jenis' => $lists); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota

		echo json_encode($callback); // konversi varibael $callback menjadi JSON
	}



	public function listtipe()
	{
		// Ambil data ID Provinsi yang dikirim via ajax post

		$data_desa = $this->Tbl_desa_model->get_by_id($this->input->post('id_desa'));
		$id_kawasan = $data_desa->id_kawasan;
		$id_jenis = $this->input->post('id_jenis');

		$id_kendali = $id_kawasan . $id_jenis;

		// $tipe = $this->TipeModel->viewByidjenis($id_jenis);
		$tipe = $this->TipeModel->viewByidkendali($id_kendali);

		// Buat variabel untuk menampung tag-tag option nya
		// Set defaultnya dengan tag option Pilih
		$lists = "<option value=''>Pilih Tipe</option>";

		foreach ($tipe as $data) {
			$lists .= "<option value='" . $data->id_tipe . "'>" . $data->tipe . "</option>"; // Tambahkan tag option ke variabel $lists
		}

		$callback = array('list_tipe' => $lists); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota

		echo json_encode($callback); // konversi varibael $callback menjadi JSON
	}

	public function listketinggian()
	{
		// Ambil data ID Provinsi yang dikirim via ajax post
		$data_desa = $this->Tbl_desa_model->get_by_id($this->input->post('id_desa'));
		$id_kawasan = $data_desa->id_kawasan;
		$id_jenis = $this->input->post('id_jenis');
		$id_tipe = $this->input->post('id_tipe');

		$id_kendali = $id_kawasan . $id_jenis . $id_tipe;

		// $ketinggian = $this->KetinggianModel->viewByidkawasanjenistipe($id_kawasan, $id_jenis, $id_tipe);
		$ketinggian = $this->KetinggianModel->viewByidkendali($id_kendali);

		$lists = "<option value=''> Pilih Ketinggian </option>";

		foreach ($ketinggian as $data) {
			// $lists .= "<option value='" . $data->tinggi_maks . "'>" . $data->tinggi_maks . "</option>"; // Tambahkan tag option ke variabel $lists
			$maks = $data->tinggi_maks;
		}

		for ($x = 1; $x <= $maks; $x++) {
			$lists .= "<option value='" . $x . "'>" . $x . "</option>";
		}

		$callback = array('list_ketinggian' => $lists); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota

		echo json_encode($callback); // konversi varibael $callback menjadi JSON
	}
}
