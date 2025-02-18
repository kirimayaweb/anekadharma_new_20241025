<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Tbl_penjualan_accounting extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		// $this->load->model('Tbl_penjualan_accounting_model');
		$this->load->model(array('Tbl_penjualan_accounting_model', 'Tbl_pembelian_model', 'Sys_konsumen_model', 'Sys_unit_model', 'Sys_nama_barang_model', 'Persediaan_model'));
		$this->load->library('form_validation');
		$this->load->library('form_validation');
		$this->load->library('datatables');
		$this->load->library('Pdf');
		$this->load->helper(array('nominal'));
	}

	public function index()
	{

		$Tbl_penjualan = $this->Tbl_penjualan_accounting_model->get_all_group_by_tgl_jual_nmrpesan_nmr_kirim();
		// $start = 0;
		// print_r($Tbl_penjualan);
		// print_r("<br/>");
		// print_r("<br/>");

		$data = array(
			'Tbl_penjualan_data' => $Tbl_penjualan,
			// 'q' => $q,
			// 'pagination' => $this->pagination->create_links(),
			// 'total_rows' => $config['total_rows'],
			// 'start' => $start,
		);


		// $this->load->view('anekadharma/tbl_penjualan/tbl_penjualan_list', $data);		
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan_accounting/adminlte310_tbl_penjualan_accounting_list', $data);
	}

	public function index_server_side()
	{
		$this->load->view('anekadharma/tbl_penjualan_accounting/tbl_penjualan_accounting_list');
	}

	public function json()
	{
		header('Content-Type: application/json');
		echo $this->Tbl_penjualan_accounting_model->json();
	}

	public function read($id)
	{
		$row = $this->Tbl_penjualan_accounting_model->get_by_id($id);
		if ($row) {
			$data = array(
				'id' => $row->id,
				'uuid_penjualan_proses' => $row->uuid_penjualan_proses,
				'uuid_penjualan' => $row->uuid_penjualan,
				'uuid_persediaan' => $row->uuid_persediaan,
				'id_persediaan_barang' => $row->id_persediaan_barang,
				'uuid_barang' => $row->uuid_barang,
				'tgl_input' => $row->tgl_input,
				'tgl_jual' => $row->tgl_jual,
				'nmrpesan' => $row->nmrpesan,
				'nmrkirim' => $row->nmrkirim,
				'uuid_konsumen' => $row->uuid_konsumen,
				'konsumen_id' => $row->konsumen_id,
				'konsumen_nama' => $row->konsumen_nama,
				'kode_barang' => $row->kode_barang,
				'nama_barang' => $row->nama_barang,
				'uuid_unit' => $row->uuid_unit,
				'unit' => $row->unit,
				'satuan' => $row->satuan,
				'harga_satuan' => $row->harga_satuan,
				'jumlah' => $row->jumlah,
				'total_nominal' => $row->total_nominal,
				'umpphpsl22' => $row->umpphpsl22,
				'piutang' => $row->piutang,
				'penjualandpp' => $row->penjualandpp,
				'utangppn' => $row->utangppn,
				'cetak_bukti_penjualan' => $row->cetak_bukti_penjualan,
				'id_usr' => $row->id_usr,
				'proses_bayar' => $row->proses_bayar,
				'tgl_bayar_input' => $row->tgl_bayar_input,
				'tgl_bayar' => $row->tgl_bayar,
				'nmr_bukti_bayar' => $row->nmr_bukti_bayar,
				'kode_akun' => $row->kode_akun,
				'proses_transaksi' => $row->proses_transaksi,
			);
			$this->load->view('tbl_penjualan_accounting/tbl_penjualan_accounting_read', $data);
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(site_url('tbl_penjualan_accounting'));
		}
	}

	public function create()
	{
		$data = array(
			'button' => 'Create',
			'action' => site_url('tbl_penjualan_accounting/create_action'),
			'id' => set_value('id'),
			'uuid_penjualan_proses' => set_value('uuid_penjualan_proses'),
			'uuid_penjualan' => set_value('uuid_penjualan'),
			'uuid_persediaan' => set_value('uuid_persediaan'),
			'id_persediaan_barang' => set_value('id_persediaan_barang'),
			'uuid_barang' => set_value('uuid_barang'),
			'tgl_input' => set_value('tgl_input'),
			'tgl_jual' => set_value('tgl_jual'),
			'nmrpesan' => set_value('nmrpesan'),
			'nmrkirim' => set_value('nmrkirim'),
			'uuid_konsumen' => set_value('uuid_konsumen'),
			'konsumen_id' => set_value('konsumen_id'),
			'konsumen_nama' => set_value('konsumen_nama'),
			'kode_barang' => set_value('kode_barang'),
			'nama_barang' => set_value('nama_barang'),
			'uuid_unit' => set_value('uuid_unit'),
			'unit' => set_value('unit'),
			'satuan' => set_value('satuan'),
			'harga_satuan' => set_value('harga_satuan'),
			'jumlah' => set_value('jumlah'),
			'total_nominal' => set_value('total_nominal'),
			'umpphpsl22' => set_value('umpphpsl22'),
			'piutang' => set_value('piutang'),
			'penjualandpp' => set_value('penjualandpp'),
			'utangppn' => set_value('utangppn'),
			'cetak_bukti_penjualan' => set_value('cetak_bukti_penjualan'),
			'id_usr' => set_value('id_usr'),
			'proses_bayar' => set_value('proses_bayar'),
			'tgl_bayar_input' => set_value('tgl_bayar_input'),
			'tgl_bayar' => set_value('tgl_bayar'),
			'nmr_bukti_bayar' => set_value('nmr_bukti_bayar'),
			'kode_akun' => set_value('kode_akun'),
			'proses_transaksi' => set_value('proses_transaksi'),
		);
		// $this->load->view('tbl_penjualan_accounting/tbl_penjualan_accounting_form', $data);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan_accounting/adminlte310_tbl_penjualan_accounting_form', $data);
	}

	public function create_action()
	{
		$this->_rules();

		if ($this->form_validation->run() == FALSE) {
			$this->create();
		} else {
			$data = array(
				'uuid_penjualan_proses' => $this->input->post('uuid_penjualan_proses', TRUE),
				'uuid_penjualan' => $this->input->post('uuid_penjualan', TRUE),
				'uuid_persediaan' => $this->input->post('uuid_persediaan', TRUE),
				'id_persediaan_barang' => $this->input->post('id_persediaan_barang', TRUE),
				'uuid_barang' => $this->input->post('uuid_barang', TRUE),
				'tgl_input' => $this->input->post('tgl_input', TRUE),
				'tgl_jual' => $this->input->post('tgl_jual', TRUE),
				'nmrpesan' => $this->input->post('nmrpesan', TRUE),
				'nmrkirim' => $this->input->post('nmrkirim', TRUE),
				'uuid_konsumen' => $this->input->post('uuid_konsumen', TRUE),
				'konsumen_id' => $this->input->post('konsumen_id', TRUE),
				'konsumen_nama' => $this->input->post('konsumen_nama', TRUE),
				'kode_barang' => $this->input->post('kode_barang', TRUE),
				'nama_barang' => $this->input->post('nama_barang', TRUE),
				'uuid_unit' => $this->input->post('uuid_unit', TRUE),
				'unit' => $this->input->post('unit', TRUE),
				'satuan' => $this->input->post('satuan', TRUE),
				'harga_satuan' => $this->input->post('harga_satuan', TRUE),
				'jumlah' => $this->input->post('jumlah', TRUE),
				'total_nominal' => $this->input->post('total_nominal', TRUE),
				'umpphpsl22' => $this->input->post('umpphpsl22', TRUE),
				'piutang' => $this->input->post('piutang', TRUE),
				'penjualandpp' => $this->input->post('penjualandpp', TRUE),
				'utangppn' => $this->input->post('utangppn', TRUE),
				'cetak_bukti_penjualan' => $this->input->post('cetak_bukti_penjualan', TRUE),
				'id_usr' => $this->input->post('id_usr', TRUE),
				'proses_bayar' => $this->input->post('proses_bayar', TRUE),
				'tgl_bayar_input' => $this->input->post('tgl_bayar_input', TRUE),
				'tgl_bayar' => $this->input->post('tgl_bayar', TRUE),
				'nmr_bukti_bayar' => $this->input->post('nmr_bukti_bayar', TRUE),
				'kode_akun' => $this->input->post('kode_akun', TRUE),
				'proses_transaksi' => $this->input->post('proses_transaksi', TRUE),
			);

			$this->Tbl_penjualan_accounting_model->insert($data);
			$this->session->set_flashdata('message', 'Create Record Success');
			redirect(site_url('tbl_penjualan_accounting'));
		}
	}

	public function update($id)
	{
		$row = $this->Tbl_penjualan_accounting_model->get_by_id($id);

		if ($row) {
			$data = array(
				'button' => 'Update',
				'action' => site_url('tbl_penjualan_accounting/update_action'),
				'id' => set_value('id', $row->id),
				'uuid_penjualan_proses' => set_value('uuid_penjualan_proses', $row->uuid_penjualan_proses),
				'uuid_penjualan' => set_value('uuid_penjualan', $row->uuid_penjualan),
				'uuid_persediaan' => set_value('uuid_persediaan', $row->uuid_persediaan),
				'id_persediaan_barang' => set_value('id_persediaan_barang', $row->id_persediaan_barang),
				'uuid_barang' => set_value('uuid_barang', $row->uuid_barang),
				'tgl_input' => set_value('tgl_input', $row->tgl_input),
				'tgl_jual' => set_value('tgl_jual', $row->tgl_jual),
				'nmrpesan' => set_value('nmrpesan', $row->nmrpesan),
				'nmrkirim' => set_value('nmrkirim', $row->nmrkirim),
				'uuid_konsumen' => set_value('uuid_konsumen', $row->uuid_konsumen),
				'konsumen_id' => set_value('konsumen_id', $row->konsumen_id),
				'konsumen_nama' => set_value('konsumen_nama', $row->konsumen_nama),
				'kode_barang' => set_value('kode_barang', $row->kode_barang),
				'nama_barang' => set_value('nama_barang', $row->nama_barang),
				'uuid_unit' => set_value('uuid_unit', $row->uuid_unit),
				'unit' => set_value('unit', $row->unit),
				'satuan' => set_value('satuan', $row->satuan),
				'harga_satuan' => set_value('harga_satuan', $row->harga_satuan),
				'jumlah' => set_value('jumlah', $row->jumlah),
				'total_nominal' => set_value('total_nominal', $row->total_nominal),
				'umpphpsl22' => set_value('umpphpsl22', $row->umpphpsl22),
				'piutang' => set_value('piutang', $row->piutang),
				'penjualandpp' => set_value('penjualandpp', $row->penjualandpp),
				'utangppn' => set_value('utangppn', $row->utangppn),
				'cetak_bukti_penjualan' => set_value('cetak_bukti_penjualan', $row->cetak_bukti_penjualan),
				'id_usr' => set_value('id_usr', $row->id_usr),
				'proses_bayar' => set_value('proses_bayar', $row->proses_bayar),
				'tgl_bayar_input' => set_value('tgl_bayar_input', $row->tgl_bayar_input),
				'tgl_bayar' => set_value('tgl_bayar', $row->tgl_bayar),
				'nmr_bukti_bayar' => set_value('nmr_bukti_bayar', $row->nmr_bukti_bayar),
				'kode_akun' => set_value('kode_akun', $row->kode_akun),
				'proses_transaksi' => set_value('proses_transaksi', $row->proses_transaksi),
			);
			$this->load->view('tbl_penjualan_accounting/tbl_penjualan_accounting_form', $data);
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(site_url('tbl_penjualan_accounting'));
		}
	}

	public function update_action()
	{
		$this->_rules();

		if ($this->form_validation->run() == FALSE) {
			$this->update($this->input->post('id', TRUE));
		} else {
			$data = array(
				'uuid_penjualan_proses' => $this->input->post('uuid_penjualan_proses', TRUE),
				'uuid_penjualan' => $this->input->post('uuid_penjualan', TRUE),
				'uuid_persediaan' => $this->input->post('uuid_persediaan', TRUE),
				'id_persediaan_barang' => $this->input->post('id_persediaan_barang', TRUE),
				'uuid_barang' => $this->input->post('uuid_barang', TRUE),
				'tgl_input' => $this->input->post('tgl_input', TRUE),
				'tgl_jual' => $this->input->post('tgl_jual', TRUE),
				'nmrpesan' => $this->input->post('nmrpesan', TRUE),
				'nmrkirim' => $this->input->post('nmrkirim', TRUE),
				'uuid_konsumen' => $this->input->post('uuid_konsumen', TRUE),
				'konsumen_id' => $this->input->post('konsumen_id', TRUE),
				'konsumen_nama' => $this->input->post('konsumen_nama', TRUE),
				'kode_barang' => $this->input->post('kode_barang', TRUE),
				'nama_barang' => $this->input->post('nama_barang', TRUE),
				'uuid_unit' => $this->input->post('uuid_unit', TRUE),
				'unit' => $this->input->post('unit', TRUE),
				'satuan' => $this->input->post('satuan', TRUE),
				'harga_satuan' => $this->input->post('harga_satuan', TRUE),
				'jumlah' => $this->input->post('jumlah', TRUE),
				'total_nominal' => $this->input->post('total_nominal', TRUE),
				'umpphpsl22' => $this->input->post('umpphpsl22', TRUE),
				'piutang' => $this->input->post('piutang', TRUE),
				'penjualandpp' => $this->input->post('penjualandpp', TRUE),
				'utangppn' => $this->input->post('utangppn', TRUE),
				'cetak_bukti_penjualan' => $this->input->post('cetak_bukti_penjualan', TRUE),
				'id_usr' => $this->input->post('id_usr', TRUE),
				'proses_bayar' => $this->input->post('proses_bayar', TRUE),
				'tgl_bayar_input' => $this->input->post('tgl_bayar_input', TRUE),
				'tgl_bayar' => $this->input->post('tgl_bayar', TRUE),
				'nmr_bukti_bayar' => $this->input->post('nmr_bukti_bayar', TRUE),
				'kode_akun' => $this->input->post('kode_akun', TRUE),
				'proses_transaksi' => $this->input->post('proses_transaksi', TRUE),
			);

			$this->Tbl_penjualan_accounting_model->update($this->input->post('id', TRUE), $data);
			$this->session->set_flashdata('message', 'Update Record Success');
			redirect(site_url('tbl_penjualan_accounting'));
		}
	}



	public function create_action_inisiasi($id_proses = null)
	{

		// $Data_stock = $this->Persediaan_model->get_all();
		// print_r($Data_stock);
		// die;

		// print_r($id_proses);
		// print_r("<br/>");
		// print_r($this->input->post('tgl_jual', TRUE));
		// print_r("<br/>");
		// print_r($this->input->post('uuid_konsumen', TRUE));
		// print_r("<br/>");
		// print_r($this->input->post('nmrpesan', TRUE));
		// print_r("<br/>");
		// print_r($this->input->post('nmrkirim', TRUE));

		$this->db->where('uuid_konsumen', $this->input->post('uuid_konsumen', TRUE));
		//$this->db->where('password',  $test);
		$sys_konsumen_data = $this->db->get('sys_konsumen');

		if ($sys_konsumen_data->num_rows() > 0) {
			// Konsumen dari sys_konsumen
			$uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
			$data_konsumen = $this->Sys_konsumen_model->get_by_uuid_konsumen($uuid_konsumen);
			$data_nama_konsumen = $data_konsumen->nama_konsumen;
		} else {
			// Konsumen dari unit

			$uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
			$data_konsumen = $this->Sys_unit_model->get_by_uuid_unit($uuid_konsumen);
			$data_nama_konsumen = $data_konsumen->nama_unit;
		}

		$data = array(
			'button' => 'Simpan',
			'button_detail_nomor_kirim' => 'Simpan Perubahan Detail Nomor Kirim',
			'action' => site_url('tbl_penjualan_accounting/create_action_simpan_barang/'),
			'tgl_jual' => $this->input->post('tgl_jual', TRUE),
			'nmrpesan' => $this->input->post('nmrpesan', TRUE),
			'nmrkirim' => $this->input->post('nmrkirim', TRUE),
			'uuid_konsumen' => $uuid_konsumen,
			'nama_konsumen' => $data_nama_konsumen,
		);
		// print_r($data);

		// $this->load->view('anekadharma/tbl_penjualan_accounting/tbl_penjualan_accounting_form', $data);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan_accounting/adminlte310_tbl_penjualan_accounting_form_input_barang', $data);
	}


	public function create_action_simpan_barang($uuid_penjualan = null, $id_persediaan_barang = null)
	{

		// print_r("create_action_simpan_barang");
		// print_r("<br/>");
		// print_r($uuid_penjualan);
		// print_r("<br/>");
		// print_r($id_persediaan_barang);
		// print_r("<br/>");
		// die;

		// print_r("create_action_simpan_barang");
		// die;
		// print_r("<br/>");

		// print_r("tgl_jual : ");
		// print_r($this->input->post('tgl_jual', TRUE));
		// print_r("<br/>");
		// print_r("uuid_konsumen : ");
		// print_r($this->input->post('uuid_konsumen', TRUE));
		// print_r("<br/>");
		// print_r("nmrpesan : ");
		// print_r($this->input->post('nmrpesan', TRUE));
		// print_r("<br/>");
		// print_r("nmrkirim : ");
		// print_r($this->input->post('nmrkirim', TRUE));
		// print_r("<br/>");
		// print_r("id_pembelian_barang : ");
		// print_r($id_proses);
		// print_r("<br/>");
		// print_r("harga_satuan_beli : ");
		// print_r($this->input->post('harga_satuan_beli', TRUE));
		// print_r("<br/>");
		// print_r("jumlah : ");
		// print_r($this->input->post('jumlah', TRUE));
		// print_r("<br/>");
		// print_r("<br/>");

		// $x_1 = $id_proses;

		// AMBIL DATA DARI PEMBELIAN
		// $sql = "SELECT * FROM `tbl_pembelian` WHERE `id`='$id_proses'";
		// $data_barang = $this->db->query($sql)->row();

		// AMBIL DATA DARI PERSEDIAAN
		$sql = "SELECT * FROM `persediaan` WHERE `id`='$id_persediaan_barang'";
		$data_barang = $this->db->query($sql)->row();



		// print_r("jumlah penjualan di persediaan");
		// print_r("<br/>");
		// print_r($id_persediaan_barang);
		// print_r("<br/>");
		// print_r($data_barang->id);
		// print_r("<br/>");
		// print_r($data_barang->uuid_persediaan);
		// print_r("<br/>");
		// die;
		// print_r($data_barang);
		// print_r("<br/>");
		// print_r("<br/>");
		// die;



		// print_r($data_barang);
		// die;

		// [uuid_pembelian] => 5d4b4221756411ef88650021ccc9061e [uuid_barang] => ae37d726715911ef9fe90021ccc906hh [tgl_po] => 2024-09-01 00:00:00 [nmrsj] => [nmrfakturkwitansi] => 1 [nmrbpb] => [uuid_spop] => 54548eeb756411ef88650021ccc9061e [spop] => 1 [uuid_supplier] => 458c5d176b2311ef80a80021ccc9061e [supplier_kode] => [supplier_nama] => Supplier 1 [uraian] => Buku [jumlah] => 2 [satuan] => rim [uuid_konsumen] => b728e22d6b5811ef80a80021ccc9061e [konsumen] => pj-atk [harga_satuan] => 100000 [harga_total] => 0 [statuslu] => L [kas_bank] => kas [tgl_bayar] => 0000-00-00 00:00:00 [id_usr] => 1 )

		// print_r($data_barang->id);
		// print_r("<br/>");
		// print_r($data_barang->uuid_barang);
		// print_r("<br/>");
		// print_r($data_barang->uraian);
		// print_r("<br/>");
		// print_r($data_barang->satuan);
		// print_r("<br/>");


		// die;

		$uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
		$data_konsumen = $this->Sys_konsumen_model->get_by_uuid_konsumen($uuid_konsumen);


		if (empty($data_konsumen)) {
			$data_konsumen = $this->Sys_unit_model->get_by_uuid_unit($uuid_konsumen);
			$data_nama_konsumen = $data_konsumen->nama_unit;
		} else {
			$data_nama_konsumen = $data_konsumen->nama_konsumen;
		}

		// print_r($uuid_konsumen);
		// print_r("<br/>");
		// print_r($data_konsumen);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r($data_nama_konsumen);
		// print_r("<br/>");
		// print_r("<br/>");

		// die;

		// $uuid_unit_selected = $this->input->post('uuid_unit', TRUE);
		// $data_unit = $this->Sys_unit_model->get_by_uuid_unit($uuid_unit_selected);
		// $data_nama_unit = $data_unit->nama_unit;

		// print_r($data_nama_unit);
		// print_r("<br/>");

		// =========SIMPAN DATA==================
		$tgl_jual_X = date("Y-m-d", strtotime($this->input->post('tgl_jual', TRUE)));

		// print_r($this->input->post('harga_satuan_beli', TRUE));
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r(preg_replace("/[^0-9]/", "", $this->input->post('harga_satuan_beli', TRUE)));
		// print_r("<br/>");
		// print_r("<br/>");

		// hilangkan titik dan ubah koma menjadi titik
		// print_r(str_replace(",", ".", str_replace(".", "", $this->input->post('harga_satuan_beli', TRUE)))); 

		// die;
		// die;



		if ($uuid_penjualan == "new") {
			// print_r("NEW");
			$data = array(
				'tgl_input' => date("Y-m-d H:i:s"),
				'tgl_jual' => $tgl_jual_X,
				// 'uuid_penjualan' => "new",
				'nmrpesan' => $this->input->post('nmrpesan', TRUE),
				'nmrkirim' => $this->input->post('nmrkirim', TRUE),
				'uuid_konsumen' => $uuid_konsumen,
				'konsumen_nama' => $data_nama_konsumen,
				// 'uuid_barang' => $data_barang->uuid_pembelian, //uuid_barang berdasarkan uuid_pembelian karena beda harga (barang sama, waktu beda belanja harga beda)
				'uuid_persediaan' => $data_barang->uuid_persediaan,
				'id_persediaan_barang' => $id_persediaan_barang, //uuid_barang berdasarkan uuid_pembelian karena beda harga (barang sama, waktu beda belanja harga beda)
				'uuid_barang' => $data_barang->uuid_barang, //uuid_barang berdasarkan uuid_pembelian karena beda harga (barang sama, waktu beda belanja harga beda)
				'kode_barang' => $data_barang->kode_barang,
				// 'nama_barang' => $data_barang->uraian,
				'nama_barang' => $data_barang->namabarang,
				// 'uuid_unit' => $uuid_unit_selected,
				// 'unit' => $data_nama_unit,
				'proses_bayar' => "belum_bayar",
				'jumlah' => preg_replace("/[^0-9]/", "", $this->input->post('jumlah', TRUE)),
				'satuan' => $data_barang->satuan,
				'harga_satuan' => str_replace(",", ".", str_replace(".", "", $this->input->post('harga_satuan_beli', TRUE))),
				'total_nominal' =>  preg_replace("/[^0-9]/", "", $this->input->post('jumlah', TRUE)) * str_replace(",", ".", str_replace(".", "", $this->input->post('harga_satuan_beli', TRUE))),
				'id_usr' => 1,
			);



			$uuid_penjualan = $this->Tbl_penjualan_accounting_model->insert_new($data);
			// print_r('create_action_simpan_barang NEW');
			// print_r("<br/>");
			// print_r("<br/>");
			// print_r($data);
			// die;
			// print_r($uuid_penjualan);
		} else {
			// print_r("BUKAN NEW");




			$data = array(
				'tgl_input' => date("Y-m-d H:i:s"),

				'tgl_jual' => $tgl_jual_X,
				'uuid_penjualan' => $uuid_penjualan,
				'nmrpesan' => $this->input->post('nmrpesan', TRUE),
				'nmrkirim' => $this->input->post('nmrkirim', TRUE),
				'uuid_konsumen' => $uuid_konsumen,
				'konsumen_nama' => $data_nama_konsumen,
				// 'uuid_barang' => $data_barang->uuid_pembelian, //uuid_barang berdasarkan uuid_pembelian karena beda harga (barang sama, waktu beda belanja harga beda)
				'uuid_persediaan' => $data_barang->uuid_persediaan,
				'id_persediaan_barang' => $id_persediaan_barang,
				'uuid_barang' => $data_barang->uuid_barang, //uuid_barang berdasarkan uuid_pembelian karena beda harga (barang sama, waktu beda belanja harga beda)
				'kode_barang' => $data_barang->kode_barang,
				// 'nama_barang' => $data_barang->uraian,
				'nama_barang' => $data_barang->namabarang,
				// 'uuid_unit' => $uuid_unit_selected,
				// 'unit' => $data_nama_unit,
				'proses_bayar' => "belum_bayar",
				'jumlah' => preg_replace("/[^0-9]/", "", $this->input->post('jumlah', TRUE)),
				'satuan' => $data_barang->satuan,
				'harga_satuan' => str_replace(",", ".", str_replace(".", "", $this->input->post('harga_satuan_beli', TRUE))),
				'total_nominal' =>  preg_replace("/[^0-9]/", "", $this->input->post('jumlah', TRUE)) * str_replace(",", ".", str_replace(".", "", $this->input->post('harga_satuan_beli', TRUE))),
				'id_usr' => 1,
			);

			// print_r($data);
			// die;

			$this->Tbl_penjualan_accounting_model->insert_add_barang($data);
		}

		// die;
		// =========SIMPAN DATA==================



		// // UNTUK PENJUALAN ACCOUNTING , TIDAK MENYIMPAN PENJUALAN DI TABEL PERSEDIAAN DAN TIDAK MENGURANGI JUMLAH PERSEDIAAN
		// // update field penjualan di tabel persediaan: dapatkan total penjualan, kemudian update penjualan field + penjulan baru
		// // $this->db->where('email', $email);
		// // $users = $this->db->get('persediaan');
		// // print_r("jumlah penjualan di persediaan");
		// // print_r("<br/>");
		// // print_r($data_barang->penjualan);




		// $Total_penjualan = $data_barang->penjualan + preg_replace("/[^0-9]/", "", $this->input->post('jumlah', TRUE));
		// // print_r($Total_penjualan);



		// $sql_stock = "UPDATE `persediaan` SET `penjualan`='$Total_penjualan' WHERE `id`='$id_persediaan_barang'";

		// $this->db->query($sql_stock);


		// die;

		// redirect("kasir_penjualan/".$uuid_penjualan);
		// redirect(site_url('tbl_penjualan/kasir_penjualan/' . $uuid_penjualan . '/' . $tgl_jual_X . '/' . $this->input->post('nmrkirim', TRUE)));
		redirect(site_url('tbl_penjualan_accounting/kasir_penjualan/' . $uuid_penjualan));
	}

	// public function kasir_penjualan($uuid_penjualan, $tgl_jual, $nmrkirim)
	public function kasir_penjualan($uuid_penjualan)
	{

		// Get tgl_jual dan nmrkirim dari uuid_penjualan


		$data_penjualan_per_uuid_penjualan = $this->Tbl_penjualan_accounting_model->get_ROW_by_uuid_penjualan_first_row($uuid_penjualan);

		// print_r($data_penjualan_per_uuid_penjualan);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");

		// print_r($data_penjualan_per_uuid_penjualan->tgl_jual);
		// print_r("<br/>");

		$tgl_jual_X = date("Y-m-d", strtotime($data_penjualan_per_uuid_penjualan->tgl_jual));

		// print_r($tgl_jual_X);
		// print_r("<br/>");
		// print_r($data_penjualan_per_uuid_penjualan->nmrkirim);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");
		// die;

		// --------------TAMPILKAN DATA INPUT PENJUALAN SESUAI UUID_NOMOR PESAN yang barusan di inputkan ----------------------
		// $data_penjualan_per_uuid_penjualan = $this->Tbl_penjualan_accounting_model->get_all_by_tgl_jual_nmrkirim($tgl_jual_X, $data_penjualan_per_uuid_penjualan->nmrkirim);
		$data_penjualan_per_uuid_penjualan = $this->Tbl_penjualan_accounting_model->get_all_by_uuid_penjualan($uuid_penjualan);

		// print_r($data_penjualan_per_uuid_penjualan);
		// print_r("<br/>");
		// // print_r("<br/>");
		// print_r("<br/>");


		// $data_penjualan_per_uuid_penjualan_first_row = $this->Tbl_penjualan_accounting_model->get_all_by_tgl_jual_nmrkirim_first_row($tgl_jual_X, $data_penjualan_per_uuid_penjualan->nmrkirim);
		$data_penjualan_per_uuid_penjualan_first_row = $this->Tbl_penjualan_accounting_model->get_all_by_uuid_penjualan_first_row($uuid_penjualan);

		// print_r($data_penjualan_per_uuid_penjualan_first_row);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");
		// die;

		$data = array(
			'data_penjualan_per_uuid_penjualan' => $data_penjualan_per_uuid_penjualan,
			'button' => 'Simpan',
			'button_detail_nomor_kirim' => 'Simpan Perubahan Detail Nomor Kirim',
			'action' => site_url('tbl_penjualan_accounting/create_action_simpan_barang/'),
			'tgl_jual' => $data_penjualan_per_uuid_penjualan_first_row->tgl_jual,
			'nmrpesan' => $data_penjualan_per_uuid_penjualan_first_row->nmrpesan,
			'nmrkirim' => $data_penjualan_per_uuid_penjualan_first_row->nmrkirim,
			'uuid_konsumen' => $data_penjualan_per_uuid_penjualan_first_row->uuid_konsumen,
			'nama_konsumen' => $data_penjualan_per_uuid_penjualan_first_row->konsumen_nama,
			'uuid_penjualan' => $uuid_penjualan,
			'action_ubah_per_id' => site_url('tbl_penjualan_accounting/create_action_nmrkirim_update_per_id_penjualan/'),
			'action_ubah_detail_nomor_kirim' => site_url('tbl_penjualan_accounting/action_ubah_detail_nomor_kirim/' . $data_penjualan_per_uuid_penjualan_first_row->nmrkirim . '/' . $uuid_penjualan),
		);

		// print_r($data);
		// die;

		// $this->load->view('anekadharma/tbl_penjualan_accounting/tbl_penjualan_accounting_form', $data);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan_accounting/adminlte310_tbl_penjualan_accounting_form_input_barang', $data);
	}

	public function action_ubah_detail_nomor_kirim($NomorKirim = null, $uuid_penjualan = null)
	{
		// print_r("action_ubah_detail_nomor_kirim");
		// print_r("<br/>");
		// print_r($NomorKirim);
		// print_r("<br/>");
		// print_r($this->input->post('tgl_jual', TRUE));
		// print_r("<br/>");
		// print_r($this->input->post('uuid_konsumen', TRUE));
		// print_r("<br/>");
		// print_r($this->input->post('nmrpesan', TRUE));
		// print_r("<br/>");
		// print_r($this->input->post('nmrkirim', TRUE));
		// print_r("<br/>");
		// print_r($this->input->post('uuid_penjualan_proses', TRUE));
		// print_r("<br/>");
		// print_r($uuid_penjualan);
		// print_r("<br/>");
		// die;


		if (date("Y", strtotime($this->input->post('tgl_jual', TRUE))) < 2020) {
			// print_r("Tahun kurang dari 2020");
			$date_jual = date("Y-m-d H:i:s");
		} else {
			// print_r("Tahun lebih dari 2020");
			$date_jual = date("Y-m-d H:i:s", strtotime($this->input->post('tgl_jual', TRUE)));
		}




		// $Get_uuid_penjualan = ;
		$NomorKirim_baru = $this->input->post('nmrkirim', TRUE);
		$NomorPesan_baru = $this->input->post('nmrpesan', TRUE);
		$GET_uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);

		// // Data Unit
		// $sql = "select * from sys_unit order by nama_unit ASC ";
		// foreach ($this->db->query($sql)->result() as $m) {
		// 	echo "<option value='$m->uuid_unit' ";
		// 	echo ">  " . strtoupper($m->nama_unit)  . "  ==> [UNIT] </option>";
		// }
		// // Data Sys_konsumen
		// $sql = "select * from sys_konsumen order by nama_konsumen ASC ";
		// foreach ($this->db->query($sql)->result() as $m) {
		// 	echo "<option value='$m->uuid_konsumen' ";
		// 	echo ">  " . strtoupper($m->nama_konsumen) . strtoupper($m->nmr_kontak_konsumen) . strtoupper($m->alamat_konsumen) . "</option>";
		// }


		$this->db->where('uuid_unit', $GET_uuid_konsumen);
        $GET_sys_unit = $this->db->get('sys_unit');
        if ($GET_sys_unit->num_rows() > 0) {
			$GET_DATA_sys_unit = $GET_sys_unit->row_array();

        	// $kode_konsumen = $GET_DATA_sys_unit['kode_unit'],
        	$nama_konsumen = $GET_DATA_sys_unit['nama_unit'];
		}

		$this->db->where('uuid_konsumen', $GET_uuid_konsumen);
        $GET_sys_konsumen = $this->db->get('sys_konsumen');
        if ($GET_sys_konsumen->num_rows() > 0) {
			$GET_DATA_sys_konsumen = $GET_sys_konsumen->row_array();

        	// $kode_konsumen = $GET_DATA_sys_konsumen['kode_konsumen'],
        	$nama_konsumen = $GET_DATA_sys_konsumen['nama_konsumen'];
		}


		// $sql_update_penjualan_by_uuid_penjualan = "UPDATE `tbl_penjualan` SET `nmrkirim`=$NomorKirim_baru , `tgl_jual`=$date_jual , `nmrpesan`=$NomorPesan_baru, `uuid_konsumen`=$GET_uuid_konsumen, `konsumen_nama`=$nama_konsumen  WHERE `uuid_penjualan`='$uuid_penjualan'";

		$sql_update_penjualan_by_uuid_penjualan = "UPDATE `tbl_penjualan_accounting` SET `tgl_jual`='$date_jual' , `nmrkirim`='$NomorKirim_baru', `nmrpesan`='$NomorPesan_baru', `uuid_konsumen`='$GET_uuid_konsumen', `konsumen_nama`='$nama_konsumen'  WHERE `uuid_penjualan`='$uuid_penjualan'";

		$this->db->query($sql_update_penjualan_by_uuid_penjualan);
	
		
		redirect(site_url('tbl_penjualan_accounting/'));	
	}


	public function create_action_nmrkirim_update_per_id_penjualan($id)
	{

		// print_r("create_action_nmrkirim_update_per_id_penjualan");
		// print_r("<br/>");
		// print_r($id);
		// print_r("<br/>");
		// print_r($this->input->post('uuid_konsumen', TRUE));
		// print_r("<br/>");
		// print_r($this->input->post('uuid_barang', TRUE));
		// print_r("<br/>");
		// print_r($this->input->post('satuan', TRUE));
		// print_r("<br/>");
		// print_r($this->input->post('jumlah', TRUE));
		// print_r("<br/>");
		// print_r($this->input->post('uuid_gudang', TRUE));
		// print_r("<br/>");
		// print_r($this->input->post('harga_satuan', TRUE));
		// print_r("<br/>");
		// die;

		// Get uuid_spop from id
		$sql_data_penjualan = "SELECT * FROM `tbl_penjualan_accounting` WHERE `id`='$id'";
		// $get_uuid_spop = $this->db->query($sql_data_penjualan)->row()->uuid_spop;
		$Get_uuid_penjualan = $this->db->query($sql_data_penjualan)->row()->uuid_penjualan;
		$get_id_persediaan = $this->db->query($sql_data_penjualan)->row()->id_persediaan_barang;
		$get_jmlh_penjualan_awal = $this->db->query($sql_data_penjualan)->row()->jumlah;

		// print_r($this->db->query($sql_data_pembelian)->row());
		// print_r("<br/>");

		// print_r($this->db->query($sql_data_pembelian)->row()->uuid_persediaan);
		// print_r("<br/>");

		// die;
		// print_r($get_uuid_spop);

		// die;

		// Data Persediaan berdasarkan uuid_persediaan
		$sql_data_persediaan_by_uuid_persediaan = "SELECT * FROM `persediaan` WHERE `id`='$get_id_persediaan'";
		$get_jmlh_penjualan_dipersediaan = $this->db->query($sql_data_persediaan_by_uuid_persediaan)->row()->penjualan;
		$get_jmlh_STOCK_dipersediaan = $this->db->query($sql_data_persediaan_by_uuid_persediaan)->row()->total_10;








		// $row_per_uuid_spop = $this->Tbl_pembelian_model->get_by_uuid_spop($uuid_spop);

		// GET KONSUMEN DATA
		// $GET_uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);

		// $sql_uuid_konsumen = "SELECT * FROM `sys_unit` WHERE `uuid_unit`='$GET_uuid_konsumen'";
		// $get_kode_konsumen = $this->db->query($sql_uuid_konsumen)->row()->kode_unit;
		// $get_nama_konsumen = $this->db->query($sql_uuid_konsumen)->row()->nama_unit;

		// GET BARANG DATA
		$GET_uuid_barang = $this->input->post('uuid_barang', TRUE);
		$sql_uuid_barang = "SELECT * FROM `sys_nama_barang` WHERE `uuid_barang`='$GET_uuid_barang'";
		// $get_kode_barang = $this->db->query($sql_uuid_barang)->row()->kode_barang;
		// $get_nama_barang = $this->db->query($sql_uuid_barang)->row()->nama_barang;

		$jumlah_Jual_ubah = preg_replace("/[^0-9]/", "", $this->input->post('jumlah', TRUE));


		// print_r("jumlah jual awal:");
		// print_r($get_jmlh_penjualan_awal);
		// print_r("<br/>");
		// print_r($jumlah_Jual_ubah);
		// print_r("<br/>");

		// $harga_satuan_tanpa_titik = str_replace('.', '', $this->input->post('harga_satuan', TRUE)); // menghilangkan titik		
		// $harga_satuan_x = str_replace(',', '.', $harga_satuan_tanpa_titik); // mengganti koma dengan titik
		// Masukkan data ke database

		$harga_satuan_x = str_replace(",", ".", str_replace(".", "", $this->input->post('harga_satuan', TRUE))); // menghilangkan titik dan mengubah koma menjadi titik agar bisa masuk ke type data decimal di mysql

		// print_r($this->input->post('harga_satuan', TRUE));
		// print_r("<br/>");
		// print_r($harga_satuan_x);
		// die;

		$TOTAL_X = $jumlah_Jual_ubah * $harga_satuan_x;

		// UPDATE DATA BARANG
		$data = array(
			// 'date_input' => date("Y-m-d H:i:s"),
			'jumlah' => $jumlah_Jual_ubah,
			'harga_satuan' => $harga_satuan_x,
			'total_nominal' => $TOTAL_X,
		);

		// print_r($id);
		// print_r("<br/>");
		// print_r($data);
		// die;

		$this->Tbl_penjualan_accounting_model->update($id, $data);

		//KONTROL INI BELUM ADA:
		// NOTE : HARUS CEK FIELD PENJUALAN , JIKA SUDAH ADA PROSES PENJUALAN, MAKA TIDAK BOLEH MENGUBAH NAMA BARANG, HANYA BISA MENGUBAH HPP DAN JUMLAH BELI (JUMLAH BELI HARUS LEBIH DARI TOTAL JUMLAH TERJUAL)

		// UPDATE DATA DI PERSEDIAAN berdasarkan id persediaan atau uuid_persediaan


		// fiel penjualan tabel persediaan: Kalkulasi total penjualan - jumlah jual awal kemudian total penjualan + jumlah jual akhir

		$Total_jual_perubahan = ($get_jmlh_penjualan_dipersediaan - $get_jmlh_penjualan_awal) + $jumlah_Jual_ubah;

		// print_r($Total_jual_perubahan);
		// print_r("<br/>");

		$Update_data_persediaan = array(
			'penjualan' => $Total_jual_perubahan,
		);

		$this->Persediaan_model->update($get_id_persediaan, $Update_data_persediaan);

		// print_r("persediaan");
		// print_r("<br/>");
		// print_r($Update_data_persediaan);
		// die;

		redirect(site_url('tbl_penjualan_accounting/kasir_penjualan/' . $Get_uuid_penjualan));
	}





	public function delete($id)
	{
		$row = $this->Tbl_penjualan_accounting_model->get_by_id($id);

		if ($row) {
			$this->Tbl_penjualan_accounting_model->delete($id);
			$this->session->set_flashdata('message', 'Delete Record Success');
			redirect(site_url('tbl_penjualan_accounting'));
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(site_url('tbl_penjualan_accounting'));
		}
	}

	public function _rules()
	{
		$this->form_validation->set_rules('uuid_penjualan_proses', 'uuid penjualan proses', 'trim|required');
		$this->form_validation->set_rules('uuid_penjualan', 'uuid penjualan', 'trim|required');
		$this->form_validation->set_rules('uuid_persediaan', 'uuid persediaan', 'trim|required');
		$this->form_validation->set_rules('id_persediaan_barang', 'id persediaan barang', 'trim|required');
		$this->form_validation->set_rules('uuid_barang', 'uuid barang', 'trim|required');
		$this->form_validation->set_rules('tgl_input', 'tgl input', 'trim|required');
		$this->form_validation->set_rules('tgl_jual', 'tgl jual', 'trim|required');
		$this->form_validation->set_rules('nmrpesan', 'nmrpesan', 'trim|required');
		$this->form_validation->set_rules('nmrkirim', 'nmrkirim', 'trim|required');
		$this->form_validation->set_rules('uuid_konsumen', 'uuid konsumen', 'trim|required');
		$this->form_validation->set_rules('konsumen_id', 'konsumen id', 'trim|required');
		$this->form_validation->set_rules('konsumen_nama', 'konsumen nama', 'trim|required');
		$this->form_validation->set_rules('kode_barang', 'kode barang', 'trim|required');
		$this->form_validation->set_rules('nama_barang', 'nama barang', 'trim|required');
		$this->form_validation->set_rules('uuid_unit', 'uuid unit', 'trim|required');
		$this->form_validation->set_rules('unit', 'unit', 'trim|required');
		$this->form_validation->set_rules('satuan', 'satuan', 'trim|required');
		$this->form_validation->set_rules('harga_satuan', 'harga satuan', 'trim|required|numeric');
		$this->form_validation->set_rules('jumlah', 'jumlah', 'trim|required|numeric');
		$this->form_validation->set_rules('total_nominal', 'total nominal', 'trim|required|numeric');
		$this->form_validation->set_rules('umpphpsl22', 'umpphpsl22', 'trim|required|numeric');
		$this->form_validation->set_rules('piutang', 'piutang', 'trim|required|numeric');
		$this->form_validation->set_rules('penjualandpp', 'penjualandpp', 'trim|required|numeric');
		$this->form_validation->set_rules('utangppn', 'utangppn', 'trim|required|numeric');
		$this->form_validation->set_rules('cetak_bukti_penjualan', 'cetak bukti penjualan', 'trim|required');
		$this->form_validation->set_rules('id_usr', 'id usr', 'trim|required');
		$this->form_validation->set_rules('proses_bayar', 'proses bayar', 'trim|required');
		$this->form_validation->set_rules('tgl_bayar_input', 'tgl bayar input', 'trim|required');
		$this->form_validation->set_rules('tgl_bayar', 'tgl bayar', 'trim|required');
		$this->form_validation->set_rules('nmr_bukti_bayar', 'nmr bukti bayar', 'trim|required');
		$this->form_validation->set_rules('kode_akun', 'kode akun', 'trim|required');
		$this->form_validation->set_rules('proses_transaksi', 'proses transaksi', 'trim|required');

		$this->form_validation->set_rules('id', 'id', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
	}

	public function excel()
	{
		$this->load->helper('exportexcel');
		$namaFile = "tbl_penjualan_accounting.xls";
		$judul = "tbl_penjualan_accounting";
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
		xlsWriteLabel($tablehead, $kolomhead++, "Uuid Penjualan Proses");
		xlsWriteLabel($tablehead, $kolomhead++, "Uuid Penjualan");
		xlsWriteLabel($tablehead, $kolomhead++, "Uuid Persediaan");
		xlsWriteLabel($tablehead, $kolomhead++, "Id Persediaan Barang");
		xlsWriteLabel($tablehead, $kolomhead++, "Uuid Barang");
		xlsWriteLabel($tablehead, $kolomhead++, "Tgl Input");
		xlsWriteLabel($tablehead, $kolomhead++, "Tgl Jual");
		xlsWriteLabel($tablehead, $kolomhead++, "Nmrpesan");
		xlsWriteLabel($tablehead, $kolomhead++, "Nmrkirim");
		xlsWriteLabel($tablehead, $kolomhead++, "Uuid Konsumen");
		xlsWriteLabel($tablehead, $kolomhead++, "Konsumen Id");
		xlsWriteLabel($tablehead, $kolomhead++, "Konsumen Nama");
		xlsWriteLabel($tablehead, $kolomhead++, "Kode Barang");
		xlsWriteLabel($tablehead, $kolomhead++, "Nama Barang");
		xlsWriteLabel($tablehead, $kolomhead++, "Uuid Unit");
		xlsWriteLabel($tablehead, $kolomhead++, "Unit");
		xlsWriteLabel($tablehead, $kolomhead++, "Satuan");
		xlsWriteLabel($tablehead, $kolomhead++, "Harga Satuan");
		xlsWriteLabel($tablehead, $kolomhead++, "Jumlah");
		xlsWriteLabel($tablehead, $kolomhead++, "Total Nominal");
		xlsWriteLabel($tablehead, $kolomhead++, "Umpphpsl22");
		xlsWriteLabel($tablehead, $kolomhead++, "Piutang");
		xlsWriteLabel($tablehead, $kolomhead++, "Penjualandpp");
		xlsWriteLabel($tablehead, $kolomhead++, "Utangppn");
		xlsWriteLabel($tablehead, $kolomhead++, "Cetak Bukti Penjualan");
		xlsWriteLabel($tablehead, $kolomhead++, "Id Usr");
		xlsWriteLabel($tablehead, $kolomhead++, "Proses Bayar");
		xlsWriteLabel($tablehead, $kolomhead++, "Tgl Bayar Input");
		xlsWriteLabel($tablehead, $kolomhead++, "Tgl Bayar");
		xlsWriteLabel($tablehead, $kolomhead++, "Nmr Bukti Bayar");
		xlsWriteLabel($tablehead, $kolomhead++, "Kode Akun");
		xlsWriteLabel($tablehead, $kolomhead++, "Proses Transaksi");

		foreach ($this->Tbl_penjualan_accounting_model->get_all() as $data) {
			$kolombody = 0;

			//ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
			xlsWriteNumber($tablebody, $kolombody++, $nourut);
			xlsWriteLabel($tablebody, $kolombody++, $data->uuid_penjualan_proses);
			xlsWriteLabel($tablebody, $kolombody++, $data->uuid_penjualan);
			xlsWriteLabel($tablebody, $kolombody++, $data->uuid_persediaan);
			xlsWriteNumber($tablebody, $kolombody++, $data->id_persediaan_barang);
			xlsWriteLabel($tablebody, $kolombody++, $data->uuid_barang);
			xlsWriteLabel($tablebody, $kolombody++, $data->tgl_input);
			xlsWriteLabel($tablebody, $kolombody++, $data->tgl_jual);
			xlsWriteLabel($tablebody, $kolombody++, $data->nmrpesan);
			xlsWriteLabel($tablebody, $kolombody++, $data->nmrkirim);
			xlsWriteLabel($tablebody, $kolombody++, $data->uuid_konsumen);
			xlsWriteNumber($tablebody, $kolombody++, $data->konsumen_id);
			xlsWriteLabel($tablebody, $kolombody++, $data->konsumen_nama);
			xlsWriteLabel($tablebody, $kolombody++, $data->kode_barang);
			xlsWriteLabel($tablebody, $kolombody++, $data->nama_barang);
			xlsWriteLabel($tablebody, $kolombody++, $data->uuid_unit);
			xlsWriteLabel($tablebody, $kolombody++, $data->unit);
			xlsWriteLabel($tablebody, $kolombody++, $data->satuan);
			xlsWriteNumber($tablebody, $kolombody++, $data->harga_satuan);
			xlsWriteNumber($tablebody, $kolombody++, $data->jumlah);
			xlsWriteNumber($tablebody, $kolombody++, $data->total_nominal);
			xlsWriteNumber($tablebody, $kolombody++, $data->umpphpsl22);
			xlsWriteNumber($tablebody, $kolombody++, $data->piutang);
			xlsWriteNumber($tablebody, $kolombody++, $data->penjualandpp);
			xlsWriteNumber($tablebody, $kolombody++, $data->utangppn);
			xlsWriteNumber($tablebody, $kolombody++, $data->cetak_bukti_penjualan);
			xlsWriteNumber($tablebody, $kolombody++, $data->id_usr);
			xlsWriteLabel($tablebody, $kolombody++, $data->proses_bayar);
			xlsWriteLabel($tablebody, $kolombody++, $data->tgl_bayar_input);
			xlsWriteLabel($tablebody, $kolombody++, $data->tgl_bayar);
			xlsWriteLabel($tablebody, $kolombody++, $data->nmr_bukti_bayar);
			xlsWriteLabel($tablebody, $kolombody++, $data->kode_akun);
			xlsWriteLabel($tablebody, $kolombody++, $data->proses_transaksi);

			$tablebody++;
			$nourut++;
		}

		xlsEOF();
		exit();
	}


	// public function cetak_penjualan_per_uuid_penjualan($uuid_penjualan = null, $date_tgl_jual = null, $nmrkirim = null)
	public function cetak_penjualan_per_uuid_penjualan($uuid_penjualan = null)
	{

		// 2.a. PERSIAPAN LIBRARY
		// $this->load->library('PdfGenerator');



		// $data_master_penjualan_per_uuidpenjualan = $this->Tbl_penjualan_model->get_all_by_uuid_penjualan_first_row($uuid_penjualan);
		// $data_master_penjualan_per_uuidpenjualan = $this->Tbl_penjualan_model->get_all_by_uuid_penjualan_tgl_jual_nmrkirim_first_row($uuid_penjualan, $date_tgl_jual, $nmrkirim);
		$data_master_penjualan_per_uuidpenjualan = $this->Tbl_penjualan_accounting_model->get_all_by_uuid_penjualan_first_row($uuid_penjualan);

		// print_r($data_master_penjualan_per_uuidpenjualan);
		// print_r($data_master_penjualan_per_uuidpenjualan->nmrpesan);
		// print_r(date("d M Y", strtotime($data_master_penjualan_per_uuidpenjualan->tgl_jual)));
		// print_r($data_master_penjualan_per_uuidpenjualan->konsumen_nama);
		// die;


		// $date_po = date("d M Y", strtotime($data_master_penjualan_per_uuidpenjualan->tgl_jual));
		// die;

		// 2.b. PERSIAPAN DATA
		$data = array(
			'data_penjualan' => $this->Tbl_penjualan_accounting_model->get_all_by_uuid_penjualan($uuid_penjualan),
			'nmr_pesan_selected' => $data_master_penjualan_per_uuidpenjualan->nmrpesan,
			'tgl_jual_selected' => date("d M Y", strtotime($data_master_penjualan_per_uuidpenjualan->tgl_jual)),
			'konsumen_nama_selected' => $data_master_penjualan_per_uuidpenjualan->konsumen_nama,
		);



		// 2.C. MENAMPILKAN FILE DATA
		// $data = array_merge($data);
		$html = $this->load->view('anekadharma/tbl_penjualan_accounting/adminlte310_cetak_penjualan_accounting.php', $data, true);

		// 2.d. CONVERT TAMPILAN FILE DATA MENJADI FILE PDF
		$this->pdf->loadHtml($html);
		$this->pdf->render();

		$this->pdf->stream("NOTA_PENJUALAN_ACCOUNTING.pdf", array("Attachment" => 0));
	}



}

/* End of file Tbl_penjualan_accounting.php */
/* Location: ./application/controllers/Tbl_penjualan_accounting.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2025-02-13 10:35:02 */
/* http://harviacode.com */