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

	public function refresh_data_from_sys_data_barang()
	{

		$sql = "SELECT `namabarang`,`satuan` FROM `persediaan` WHERE `namabarang`<>'' GROUP by `namabarang`";

		foreach ($this->db->query($sql)->result() as $m) {

			$data_barang = $this->Sys_nama_barang_model->get_by_nama_barang($m->namabarang);

			// $sql = "UPDATE `persediaan` SET `uuid_barang`='$data_barang->uuid_barang',`kode_barang`='$data_barang->kode_barang' WHERE `namabarang`= '$m->namabarang'";

			$this->db->set('uuid_barang', $data_barang->uuid_barang, true);
			$this->db->set('kode_barang', $data_barang->kode_barang, true);
			$this->db->where('namabarang', $m->namabarang);
			$this->db->update('persediaan');
		}

		print_r("Selesai update uuid_barang dan kode_barang di tabel persediaan berdasarkan uuid_barang dan kode barang dari sys_data_barang");

	}

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