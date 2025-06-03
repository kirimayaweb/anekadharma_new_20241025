<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Neraca_saldo extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model(array('Sys_kode_akun_model', 'Neraca_saldo_model'));
		$this->load->library('form_validation');
		$this->load->library('datatables');
	}


	public function index()
	{


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


		$Get_month_from_date = date("m");
        $Get_year_Tahun_ini = date("Y");

		$data = array(
			// 'data_Buku_besar' => $data_Buku_besar,
			// 'Data_pembelian' => $this->db->query($sql_pembelian)->result(),
			// 'Data_penjualan' => $this->db->query($sql_penjualan)->result(),
			'Data_Kode_Akun' => $this->Sys_kode_akun_model->get_all_order_by_kode_akun_ASC(),
			'action' => site_url('Buku_besar/cari_kode_akun'),
			'month_selected' => $Get_month_from_date,
			'year_selected' => $Get_year_Tahun_ini,
		);

		// print_r($data);
		// die;

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/neraca_saldo/adminlte310_neraca_saldo_list', $data);
	}


	public function Cari_bulan_data(){
		// print_r("Cari_bulan_data");
		// print_r("<br/>");
		// print_r(date("Y", strtotime($this->input->post('bulan_ns', TRUE))));
		// print_r("<br/>");
		// print_r(date("m", strtotime($this->input->post('bulan_ns', TRUE))));
		// die;

		if ($this->input->post('bulan_ns', TRUE)) {
			$Get_month_selected = date("m", strtotime($this->input->post('bulan_ns', TRUE)));
			$Get_YEAR_selected = date("Y", strtotime($this->input->post('bulan_ns', TRUE)));
		} else {
			// $Get_date_awal = date("Y-m-1 00:00:00");
			// $Get_date_akhir = date("Y-m-t 23:59:59"); // TANGGAL AKHIR BULAN -t
			$Get_month_selected = date("m"); // TANGGAL AKHIR BULAN -t
			$Get_YEAR_selected = date("Y"); // TANGGAL AKHIR BULAN -t
		}


		// print_r($Get_month_selected);
		// print_r("<br/>");
		// print_r($Get_YEAR_selected);
		// print_r("<br/>");

		// $GET_Source = "penjualan";
		// $sql = "SELECT * FROM `buku_besar` WHERE MONTH(`tanggal`)=$Get_month_selected AND YEAR(`tanggal`)=$Get_YEAR_selected  ORDER BY `tanggal`,`id`";

		// $Buku_besar_DATA = $this->db->query($sql)->result();

		// print_r($Buku_besar_DATA);
		

		$data = array(
			// 'data_Buku_besar' => $data_Buku_besar,
			// 'Data_pembelian' => $this->db->query($sql_pembelian)->result(),
			// 'Data_penjualan' => $this->db->query($sql_penjualan)->result(),
			'Data_Kode_Akun' => $this->Sys_kode_akun_model->get_all_order_by_kode_akun_ASC(),
			// 'Data_Kode_Akun' =>  $this->db->query($sql)->result(),
			'action' => site_url('Buku_besar/cari_kode_akun'),
			'month_selected' => $Get_month_selected,
			'year_selected' => $Get_YEAR_selected,
		);

		// print_r($data);
		// die;

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/neraca_saldo/adminlte310_neraca_saldo_list', $data);

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