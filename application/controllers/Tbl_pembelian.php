<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Tbl_pembelian extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_login();
		$this->load->model(array('Tbl_pembelian_model', 'Tbl_penjualan_model', 'Tbl_pembelian_pengajuan_bayar_model', 'User_model', 'Sys_bank_model', 'Sys_status_transaksi_model', 'Tbl_penjualan_pembayaran_model', 'Tbl_pembelian_pecah_satuan_model', 'Sys_nama_barang_model', 'Sys_kode_akun_model', 'Sys_unit_model', 'Persediaan_model', 'Tbl_kas_kecil_model', 'Buku_besar_model', 'Tbl_penjualan_accounting_pembayaran_model', 'Tbl_penjualan_accounting_model'));
		$this->load->library('form_validation');
		$this->load->library('datatables');
		$this->load->library('Pdf');
		$this->load->helper(array('nominal'));
	}


	public function update_uuidpersediaan_pembelian()
	{



		// LOOP TABEL PEMBELIAN
		foreach ($this->Tbl_pembelian_model->get_all() as $list_data) {

			// Cek uuid_persediaan berdasarkan uuid_spop dan uuid_barang , kemudian update field uuid_persediaan tbl_pembelian dari tabel persediaan

			echo $list_data->id;
			echo " , ";
			echo $list_data->uuid_barang;
			echo " , ";
			echo $list_data->uraian;
			echo " , ";
			echo "<br/>";
			echo "<br/>";

			$Data_persediaan = $this->Persediaan_model->get_by_uuidspop_uuid_barang($list_data->uuid_spop, $list_data->uuid_barang);
			print_r($Data_persediaan);
			print_r("<br/>");
			print_r("<br/>");
			echo $Data_persediaan->id;
			echo " , ";
			echo $Data_persediaan->uuid_persediaan;
			echo " , ";

			// Update field uuid_persediaan dan id_persediaan di tabel tbl_pembelian

			$data = array(
				'uuid_persediaan' => $Data_persediaan->uuid_persediaan,
				'id_persediaan_barang' => $Data_persediaan->id,
			);

			$this->Tbl_pembelian_model->update($list_data->id, $data);


			print_r("<br/>");
			print_r("<br/>");
			print_r("<br/>");

			// if ($list_data->id == 3) {
			// 	die;
			// }
		}
	}

	public function index_BU()
	{
		$this->load->view('anekadharma/tbl_pembelian/tbl_pembelian_list');
	}


	public function json()
	{
		header('Content-Type: application/json');
		echo $this->Tbl_pembelian_model->json();
	}

	public function index()
	{


		$Get_date_awal = date("Y-m-1 00:00:00");
		// print_r($Get_date_awal);
		// print_r("<br/>");


		$Get_date_akhir = date("Y-m-t 00:00:00"); // TANGGAL AKHIR BULAN -t
		// print_r($Get_date_akhir);
		// print_r("<br/>");

		// die;


		$sql = "SELECT * FROM `tbl_pembelian` WHERE `tgl_po` between '$Get_date_awal' and '$Get_date_akhir' ORDER BY `tgl_po`,`spop`,`id`";

		// print_r($this->db->query($sql)->result());
		// die;

		// $Tbl_pembelian = $this->Tbl_pembelian_model->get_all();
		$Tbl_pembelian = $this->db->query($sql)->result();

		// $start = 0;
		$data = array(
			'Tbl_pembelian_data' => $Tbl_pembelian,
			'date_awal' => $Get_date_awal,
			'date_akhir' => $Get_date_akhir,
		);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_list', $data);
	}


	public function cari_between_date()
	{

		// print_r($this->input->post('tgl_awal', TRUE));
		// print_r("<br/>");
		// print_r($this->input->post('tgl_akhir', TRUE));
		// print_r("<br/>");

		// $Get_date_awal = $this->input->post('tgl_awal', TRUE);
		if (date("Y", strtotime($this->input->post('tgl_awal', TRUE))) < 2020) {
			// $Get_date_awal = date("Y-m-d 00:00:00");
			$Get_date_awal = date('Y-m-d', strtotime('-1 day'));
		} else {
			$Get_date_awal = date("Y-m-d 23:59:59", strtotime($this->input->post('tgl_awal', TRUE)));
		}

		// print_r($Get_date_awal);
		// print_r("<br/>");

		// $Get_date_awal_proses =  date('Y-m-d', strtotime($Get_date_awal. ' - 1 day'));

		// print_r($Get_date_awal_proses);
		// print_r("<br/>");

		// $Get_date_akhir = $this->input->post('tgl_akhir', TRUE);
		if (date("Y", strtotime($this->input->post('tgl_akhir', TRUE))) < 2020) {
			$Get_date_akhir = date("Y-m-d 00:00:00");
		} else {
			$Get_date_akhir = date("Y-m-d 23:59:59", strtotime($this->input->post('tgl_akhir', TRUE)));
		}
		// print_r($Get_date_akhir);
		// print_r("<br/>");


		$sql = "SELECT * FROM `tbl_pembelian` WHERE `tgl_po` between '$Get_date_awal' and '$Get_date_akhir' ORDER BY `tgl_po`,`spop`,`id`";

		// print_r($this->db->query($sql)->result());
		// die;

		// $Tbl_pembelian = $this->Tbl_pembelian_model->get_all();
		$Tbl_pembelian = $this->db->query($sql)->result();

		$data = array(
			'Tbl_pembelian_data' => $Tbl_pembelian,
			'date_awal' => $Get_date_awal,
			'date_akhir' => $Get_date_akhir,
		);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_list', $data);
	}


	public function stock($uuid_gudang = null)
	{

		// $uuid_gudang="cd64c3af883c11ef9d7f0021ccc9061e";

		// $sql_stock = "SELECT persediaan.namabarang as nama_barang_beli,persediaan.total_10 as jumlah_sediaan, 
		// tbl_pembelian.uraian as barang_beli, tbl_pembelian.jumlah as jumlah_belanja, tbl_pembelian.harga_satuan as harga_satuan_beli, tbl_pembelian.tgl_po as tgl_po,tbl_pembelian.uuid_gudang as uuid_gudang, tbl_pembelian.nama_gudang as nama_gudang, tbl_pembelian.satuan as satuan,
		// tbl_penjualan.nama_barang as barang_jual, tbl_penjualan.jumlah as jumlah_terjual
		//                     FROM persediaan  
		//                    	left join tbl_pembelian ON persediaan.uuid_barang = tbl_pembelian.uuid_barang 
		//                     left join tbl_penjualan ON persediaan.uuid_barang = tbl_penjualan.uuid_barang  
		// 					WHERE tbl_pembelian.uuid_gudang='$uuid_gudang' AND (persediaan.uuid_barang, persediaan.tanggal) IN (SELECT persediaan.uuid_barang, Max(persediaan.tanggal) FROM persediaan GROUP BY persediaan.uuid_barang)  
		//                     ORDER BY persediaan.uuid_barang ASC";
		// print_r($this->db->query($sql_stock)->result());


		// die;

		if (null !== ($this->input->post('uuid_gudang', TRUE))) {
			if ($this->input->post('uuid_gudang', TRUE) == "semua") {

				// print_r("IF SEMUA");
				//Pilih combobox dengan pilihan semua
				// $Data_stock = $this->Tbl_pembelian_model->stock();

				$sql_stock = "SELECT persediaan.kode_barang as kode_barang, persediaan.namabarang as nama_barang_beli,persediaan.total_10 as jumlah_sediaan,  persediaan.hpp as harga_satuan_persediaan,
								tbl_pembelian.uuid_pembelian as uuid_pembelian,tbl_pembelian.uraian as barang_beli, tbl_pembelian.jumlah as jumlah_belanja, tbl_pembelian.harga_satuan as harga_satuan_beli,  tbl_pembelian.tgl_po as tgl_po,tbl_pembelian.uuid_gudang as uuid_gudang, tbl_pembelian.nama_gudang as nama_gudang,  tbl_pembelian.satuan as satuan,
							tbl_penjualan.nama_barang as barang_jual, tbl_penjualan.jumlah as jumlah_terjual
                            FROM persediaan  
                           	left join tbl_pembelian ON persediaan.uuid_barang = tbl_pembelian.uuid_barang 
                            left join tbl_penjualan ON persediaan.uuid_barang = tbl_penjualan.uuid_barang  
							WHERE (persediaan.uuid_barang, persediaan.tanggal) IN (SELECT persediaan.uuid_barang, Max(persediaan.tanggal) FROM persediaan GROUP BY persediaan.uuid_barang)  
                            ORDER BY persediaan.uuid_barang ASC";

				// print_r($this->db->query($sql_stock)->result());
				$Data_stock = $this->db->query($sql_stock)->result();
			} else {
				// print_r("IF GUDANG");
				$uuid_gudang = $this->input->post('uuid_gudang', TRUE);
				// $Data_stock = $this->Tbl_pembelian_model->stock_by_gudang($uuid_gudang);
				$sql_stock = "SELECT persediaan.kode_barang as kode_barang, persediaan.namabarang as nama_barang_beli,persediaan.total_10 as jumlah_sediaan, persediaan.hpp as harga_satuan_persediaan,
								tbl_pembelian.uuid_pembelian as uuid_pembelian,tbl_pembelian.uraian as barang_beli, tbl_pembelian.jumlah as jumlah_belanja, tbl_pembelian.harga_satuan as harga_satuan_beli, tbl_pembelian.tgl_po as tgl_po,tbl_pembelian.uuid_gudang as uuid_gudang, tbl_pembelian.nama_gudang as nama_gudang,tbl_pembelian.satuan as satuan,
							tbl_penjualan.nama_barang as barang_jual, tbl_penjualan.jumlah as jumlah_terjual
							FROM persediaan  
			   				left join tbl_pembelian ON persediaan.uuid_barang = tbl_pembelian.uuid_barang 
							left join tbl_penjualan ON persediaan.uuid_barang = tbl_penjualan.uuid_barang  
							WHERE tbl_pembelian.uuid_gudang='$uuid_gudang' AND (persediaan.uuid_barang, persediaan.tanggal) IN (SELECT persediaan.uuid_barang, Max(persediaan.tanggal) FROM persediaan GROUP BY persediaan.uuid_barang)  
							ORDER BY persediaan.uuid_barang ASC";

				// print_r($this->db->query($sql_stock)->result());
				$Data_stock = $this->db->query($sql_stock)->result();
			}
		} else {
			// $Data_stock = $this->Tbl_pembelian_model->stock();
			// print_r("ELSE SEMUA");
			$sql_stock = "SELECT persediaan.kode_barang as kode_barang, persediaan.namabarang as nama_barang_beli,persediaan.total_10 as jumlah_sediaan, persediaan.hpp as harga_satuan_persediaan,
						tbl_pembelian.uuid_pembelian as uuid_pembelian,tbl_pembelian.uraian as barang_beli, tbl_pembelian.jumlah as jumlah_belanja, tbl_pembelian.harga_satuan as harga_satuan_beli, tbl_pembelian.tgl_po as tgl_po,tbl_pembelian.uuid_gudang as uuid_gudang, tbl_pembelian.nama_gudang as nama_gudang,tbl_pembelian.satuan as satuan,
					tbl_penjualan.nama_barang as barang_jual, tbl_penjualan.jumlah as jumlah_terjual
					FROM persediaan  
					left join tbl_pembelian ON persediaan.uuid_barang = tbl_pembelian.uuid_barang 
					left join tbl_penjualan ON persediaan.uuid_barang = tbl_penjualan.uuid_barang  
					WHERE (persediaan.uuid_barang, persediaan.tanggal) IN (SELECT persediaan.uuid_barang, Max(persediaan.tanggal) FROM persediaan GROUP BY persediaan.uuid_barang)  
					ORDER BY persediaan.uuid_barang ASC";

			// print_r($this->db->query($sql_stock)->result());
			$Data_stock = $this->db->query($sql_stock)->result();
		}






		if (isset($uuid_gudang)) {
			// $Data_stock = $this->Tbl_pembelian_model->stock_by_gudang($uuid_gudang);
			// print_r("NON  IF GUDANG");
			$uuid_gudang = $this->input->post('uuid_gudang', TRUE);
			// $Data_stock = $this->Tbl_pembelian_model->stock_by_gudang($uuid_gudang);
			$sql_stock = "SELECT persediaan.kode_barang as kode_barang, persediaan.namabarang as nama_barang_beli,persediaan.total_10 as jumlah_sediaan, persediaan.hpp as harga_satuan_persediaan,persediaan.tanggal_beli as tanggal_beli_persediaan, persediaan.nilai_persediaan as nilai_persediaan,
							tbl_pembelian.uuid_pembelian as uuid_pembelian,tbl_pembelian.uraian as barang_beli, tbl_pembelian.jumlah as jumlah_belanja, tbl_pembelian.harga_satuan as harga_satuan_beli, tbl_pembelian.tgl_po as tgl_po,tbl_pembelian.uuid_gudang as uuid_gudang, tbl_pembelian.nama_gudang as nama_gudang,tbl_pembelian.satuan as satuan,
						tbl_penjualan.nama_barang as barang_jual, tbl_penjualan.jumlah as jumlah_terjual
						FROM persediaan  
						   left join tbl_pembelian ON persediaan.uuid_barang = tbl_pembelian.uuid_barang 
						left join tbl_penjualan ON persediaan.uuid_barang = tbl_penjualan.uuid_barang  
						WHERE tbl_pembelian.uuid_gudang='$uuid_gudang' AND (persediaan.uuid_barang, persediaan.tanggal) IN (SELECT persediaan.uuid_barang, Max(persediaan.tanggal) FROM persediaan GROUP BY persediaan.uuid_barang)  
						ORDER BY persediaan.uuid_barang ASC";

			// print_r($this->db->query($sql_stock)->result());
			$Data_stock = $this->db->query($sql_stock)->result();
		} else {
			// $Data_stock = $this->Tbl_pembelian_model->stock();
			// print_r("NON  IF SEMUA");
			$sql_stock = "SELECT persediaan.kode_barang as kode_barang, 
			persediaan.uuid_persediaan as uuid_persediaan,
			persediaan.namabarang as nama_barang_persediaan,
			persediaan.total_10 as jumlah_sediaan, 
			persediaan.hpp as harga_satuan_persediaan,
			persediaan.tanggal_beli as tanggal_beli_persediaan, 
			persediaan.satuan as satuan, 
			persediaan.spop as spop, 
			persediaan.penjualan as penjualan, 
			persediaan.pecah_satuan as pecah_satuan, 
			persediaan.bahan_produksi as bahan_produksi, 
			
					-- 	tbl_pembelian.uuid_pembelian as uuid_pembelian,
					-- 	tbl_pembelian.uraian as barang_beli, 
					-- 	tbl_pembelian.jumlah as jumlah_belanja, 
					-- 	tbl_pembelian.harga_satuan as harga_satuan_beli, 
					-- 	tbl_pembelian.tgl_po as tgl_po, 
					-- 	tbl_pembelian.uuid_gudang as uuid_gudang, 
					-- 	tbl_pembelian.nama_gudang as nama_gudang,
					-- 	tbl_pembelian.satuan as satuan,
					-- tbl_penjualan.nama_barang as barang_jual, 
					-- tbl_penjualan.jumlah as jumlah_terjual

			persediaan.nilai_persediaan as nilai_persediaan
					
					FROM persediaan  
					-- left join tbl_pembelian ON persediaan.uuid_barang = tbl_pembelian.uuid_barang 
					-- left join tbl_penjualan ON persediaan.uuid_barang = tbl_penjualan.uuid_barang  
					-- WHERE (persediaan.uuid_barang, persediaan.tanggal) IN (SELECT persediaan.uuid_barang, Max(persediaan.tanggal) FROM persediaan GROUP BY persediaan.uuid_barang)  
					ORDER BY persediaan.tanggal_beli DESC";

			// print_r($this->db->query($sql_stock)->result());
			$Data_stock = $this->db->query($sql_stock)->result();
		}

		// print_r($this->db->query($sql_stock)->num_rows());
		// print_r("<br/>");
		// print_r($Data_stock);

		// die;

		$data = array(
			'action_cari_gudang' => site_url('Tbl_pembelian/stock'),
			'Data_stock' => $Data_stock,
		);


		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/stock/adminlte310_stock_barang', $data);
	}

	public function create_pembayaran($uuid_spop = null, $from_pembelian_page = null)
	{

		$row_per_uuid_spop = $this->Tbl_pembelian_model->get_by_uuid_spop($uuid_spop);
		$RESULT_per_uuid_spop = $this->Tbl_pembelian_model->get_by_uuid_spop_ALL_result($uuid_spop);

		$data = array(
			'data_ALL_per_SPOP' => $RESULT_per_uuid_spop,
			'button' => 'Simpan',
			'action' => site_url('tbl_pembelian/create_pembayaran_action/' . $uuid_spop),
			'id' => set_value('id'),
			'tgl_po' => $row_per_uuid_spop->tgl_po,
			// 'nmrsj' => $row_per_uuid_spop->nmrsj,
			'nmrfakturkwitansi' => $row_per_uuid_spop->nmrfakturkwitansi,
			// 'nmrbpb' => $row_per_uuid_spop->nmrbpb,
			'uuid_spop' => $row_per_uuid_spop->uuid_spop,
			'spop' => $row_per_uuid_spop->spop,
			'supplier_kode' => $row_per_uuid_spop->supplier_kode,
			'supplier_nama' => $row_per_uuid_spop->supplier_nama,
			// 'uraian' => $row_per_uuid_spop->uraian,
			// 'jumlah' => $row_per_uuid_spop->jumlah,
			// 'satuan' => $row_per_uuid_spop->satuan,
			'uuid_konsumen' => $row_per_uuid_spop->uuid_konsumen,
			'konsumen' => $row_per_uuid_spop->konsumen,
			// 'harga_satuan' => $row_per_uuid_spop->harga_satuan,
			// 'harga_total' => $row_per_uuid_spop->harga_total,
			'statuslu' => $row_per_uuid_spop->statuslu,
			'kas_bank' => $row_per_uuid_spop->kas_bank,
			// 'tgl_bayar' => $row_per_uuid_spop->tgl_bayar,
			// 'id_usr' => $row_per_uuid_spop->,

		);

		// print_r($data);
		// die;


		// $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_form_spop_pengajuan_bayar', $data);


		// direktur
		$id_user_level = 999;
		$row_per_direktur = $this->User_model->get_by_id_user_level($id_user_level);

		// kabagkeuangan
		$id_user_level = 888;
		$row_per_kabagkeuangan = $this->User_model->get_by_id_user_level($id_user_level);

		// kasirpembelian
		$id_user_level = 777;
		$row_per_kasirpembelian = $this->User_model->get_by_id_user_level($id_user_level);


		// open form pengajuan INPUT
		$row_per_uuid_spop = $this->Tbl_pembelian_model->get_by_uuid_spop($uuid_spop);
		$RESULT_per_uuid_spop = $this->Tbl_pembelian_model->get_by_uuid_spop_ALL_result($uuid_spop);


		$RESULT_get_total_nominal_per_spop = $this->Tbl_pembelian_model->get_total_nominal_per_spop($uuid_spop);

		// JUMLAH NOMINAL PEMBELIAN
		$x_total = 0;
		foreach ($RESULT_get_total_nominal_per_spop as $list_data) {
			$x_total = $x_total + $list_data->total_pembelian;
		}

		if ($from_pembelian_page) {
			$from_pembelian_page = "Pembelian";
		} else {
			$from_pembelian_page = "BukanPembelian";
		}


		// JUMLAH NOMINAL SUDAH TERBAYAR : DARI TRANSFER DAN KAS KECIL
		// PEMBAYARAN TRANSFER PER UID_SPOP
		$this->db->where('uuid_spop', $uuid_spop);
		$Query_data_pengajuan_bayar_by_uuid_spop = $this->db->get('tbl_pembelian_pengajuan_bayar');
		$Get_data_TERBAYAR_VIA_TRANSFER_by_uuid_spop = $Query_data_pengajuan_bayar_by_uuid_spop->result();


		// $start_TRANSFER = 0;
		$uuid_SPOP_TRANSFER = 0;
		foreach ($Get_data_TERBAYAR_VIA_TRANSFER_by_uuid_spop as $list_data_TRANSFER) {
			// if ($start_TRANSFER > 0) {
			// 	echo "<br/>";
			// }

			// echo number_format($list_data_TRANSFER->nominal_pengajuan, 2, ',', '.');

			$uuid_SPOP_TRANSFER = $uuid_SPOP_TRANSFER + $list_data_TRANSFER->nominal_pengajuan;
			// $TOTAL_BAYAR_TRANSFER = $TOTAL_BAYAR_TRANSFER + $list_data_TRANSFER->nominal_pengajuan;

			// ++$start_TRANSFER;
		}



		// PEMBAYARAN KAS PER UUID_SPOP

		$this->db->where('uuid_spop', $uuid_spop);
		$Query_data_KAS_KECIL_by_uuid_spop = $this->db->get('tbl_kas_kecil');
		$Get_data_TERBAYAR_VIA_KAS_ECIL_by_uuid_spop = $Query_data_KAS_KECIL_by_uuid_spop->result();

		// $start_KAS_KECIL = 0;
		$UUID_SPOP_KAS_KECIL = 0;
		foreach ($Get_data_TERBAYAR_VIA_KAS_ECIL_by_uuid_spop as $list_data_KAS_KECIL) {
			// if ($start_KAS_KECIL > 0) {
			// 	echo "<br/>";
			// }

			// echo number_format($list_data_KAS_KECIL->kredit, 2, ',', '.');

			$UUID_SPOP_KAS_KECIL = $UUID_SPOP_KAS_KECIL + $list_data_KAS_KECIL->kredit;
			// $TOTAL_BAYAR_KAS_KECIL = $TOTAL_BAYAR_KAS_KECIL + $list_data_KAS_KECIL->kredit;

			// ++$start_KAS_KECIL;
		}

		$Total_Nominal_Sisa_Tagihan = $x_total - ($uuid_SPOP_TRANSFER + $UUID_SPOP_KAS_KECIL);

		// print_r($Total_Nominal_Sisa_Tagihan);
		// die;

		$data = array(
			'data_ALL_per_SPOP' => $RESULT_per_uuid_spop,
			'button' => 'Simpan',
			'action' => site_url('tbl_pembelian/create_pembayaran_action/' . $uuid_spop),
			'id' => set_value('id'),
			'tgl_po' => $row_per_uuid_spop->tgl_po,
			'nmrfakturkwitansi' => $row_per_uuid_spop->nmrfakturkwitansi,
			'uuid_spop' => $row_per_uuid_spop->uuid_spop,
			'spop' => $row_per_uuid_spop->spop,
			'nominal_pengajuan' => $Total_Nominal_Sisa_Tagihan,
			// 'nominal_pengajuan' => preg_replace("/[^0-9]/", "", $x_total),
			// 'nominal_pengajuan' => str_replace(",", ".", str_replace(".", "", $x_total)),
			'supplier_kode' => $row_per_uuid_spop->supplier_kode,
			'supplier_nama' => $row_per_uuid_spop->supplier_nama,
			'uuid_konsumen' => $row_per_uuid_spop->uuid_konsumen,
			'konsumen' => $row_per_uuid_spop->konsumen,
			'statuslu' => $row_per_uuid_spop->statuslu,
			'kas_bank' => $row_per_uuid_spop->kas_bank,
			'nama_direktur' => $row_per_direktur->full_name,
			'nama_kabagkeuangan' => $row_per_kabagkeuangan->full_name,
			'nama_kasirpemebelian' => $row_per_kasirpembelian->full_name,
			'from_pembelian_page' => $from_pembelian_page,
		);

		// print_r($data);

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_cetak_pengajuan_pembayaran', $data);
	}

	public function create_pembayaran_action($uuid_spop = null)
	{

		// print_r($uuid_spop);
		// print_r("<br/>");
		// print_r($this->input->post('tgl_pembayaran', TRUE));
		// print_r("<br/>");

		// print_r($this->input->post('tgl_nomor_bkk', TRUE));
		// print_r("<br/>");

		// print_r($this->input->post('uuid_bank_bkk', TRUE));
		// print_r("<br/>");

		// print_r($this->input->post('nomor_rekening_bkk', TRUE));
		// print_r("<br/>");

		// print_r($this->input->post('atas_nama_rekening_bkk', TRUE));
		// print_r("<br/>");

		// print_r($this->input->post('uuid_unit', TRUE));

		// die;


		$row_per_uuid_bank = $this->Sys_bank_model->get_by_uuid_bank($this->input->post('uuid_bank', TRUE));


		if (date("Y", strtotime($this->input->post('tgl_permohonan', TRUE))) < 2020) {
			$date_tgl_permohonan = date("Y-m-d H:i:s");
		} else {
			$date_tgl_permohonan = date("Y-m-d H:i:s", strtotime($this->input->post('tgl_permohonan', TRUE)));
		}


		if (date("Y", strtotime($this->input->post('tgl_nomor_bkk', TRUE))) < 2020) {
			$date_tgl_nomor_bkk = date("Y-m-d H:i:s");
		} else {
			$date_tgl_nomor_bkk = date("Y-m-d H:i:s", strtotime($this->input->post('tgl_nomor_bkk', TRUE)));
		}

		if (date("Y", strtotime($this->input->post('tgl_jatuh_tempo', TRUE))) < 2020) {
			$date_tgl_jatuh_tempo = date("Y-m-d H:i:s");
		} else {
			$date_tgl_jatuh_tempo = date("Y-m-d H:i:s", strtotime($this->input->post('tgl_jatuh_tempo', TRUE)));
		}

		if (date("Y", strtotime($this->input->post('tgl_nomor_bkk', TRUE))) < 2020) {
			$date_tgl_nomor_bkk = date("Y-m-d H:i:s");
		} else {
			$date_tgl_nomor_bkk = date("Y-m-d H:i:s", strtotime($this->input->post('tgl_nomor_bkk', TRUE)));
		}

		$get_uuid_unit = $this->input->post('uuid_unit', TRUE);
		$data_unit = $this->Sys_unit_model->get_by_uuid_unit($get_uuid_unit);
		$data_nama_unit = $data_unit->nama_unit;

		// print_r(preg_replace("/[^0-9]/", "", $this->input->post('jumlah_nominal', TRUE)));
		// print_r("<br/>");
		// print_r($this->input->post('jumlah_nominal', TRUE));

		// print_r("<br/>");
		// print_r(number_format($this->input->post('jumlah_nominal', TRUE), 2, ',', '.') );

		// print_r("<br/>");
		// print_r(str_replace('.', '', $this->input->post('jumlah_nominal', TRUE)) );

		// die;

		$data = array(
			'uuid_spop' => $uuid_spop,
			'date_input' => date("Y-m-d H:i:s"),
			'tgl_pengajuan' => date("Y-m-d H:i:s"),
			'supplier_nama' => $this->input->post('supplier_nama', TRUE),


			// 'nominal_pengajuan' => preg_replace("/[^0-9]/", "", $this->input->post('jumlah_nominal', TRUE)),
			'nominal_pengajuan' => str_replace('.', '', $this->input->post('jumlah_nominal', TRUE)),
			'nomor_permohonan' => $this->input->post('nomor_permohonan', TRUE),
			'jumlah_nominal' => str_replace('.', '', $this->input->post('jumlah_nominal', TRUE)),

			'tgl_permohonan' => $date_tgl_permohonan,
			'tgl_pembayaran' => $date_tgl_permohonan,

			'terbilang' => $this->input->post('terbilang', TRUE),
			'keterangan' => $this->input->post('keterangan', TRUE),
			'tgl_jatuh_tempo' => $date_tgl_jatuh_tempo,
			'nomor_faktur' => $this->input->post('nomor_faktur', TRUE),
			'uuid_bank' => $this->input->post('uuid_bank', TRUE),

			// ditransfer ke
			'nama_bank' => $row_per_uuid_bank->nama_bank,
			'nomor_rekening' => $this->input->post('nomor_rekening', TRUE),
			'atas_nama_rekening' => $this->input->post('atas_nama_rekening', TRUE),

			'nomor_bkk' => $this->input->post('nomor_bkk', TRUE),
			'tgl_nomor_bkk' => $date_tgl_nomor_bkk,

			// 'bank_checkbox' => $this->input->post('bank_checkbox', TRUE),
			'uuid_bank_bkk' => $this->input->post('uuid_bank_bkk', TRUE),
			'nomor_rekening_bkk' => $this->input->post('nomor_rekening_bkk', TRUE),
			'atas_nama_rekening_bkk' => $this->input->post('atas_nama_rekening_bkk', TRUE),


			'kas_checkbox' => $this->input->post('kas_checkbox', TRUE),

			'uuid_account_unit' => $this->input->post('uuid_unit', TRUE),
			'account' => $data_nama_unit,

			'nomor_cek_giro' => $this->input->post('nomor_cek_giro', TRUE),
			'nama_direktur' => $this->input->post('nama_direktur', TRUE),
			'nama_kabagkeuangan' => $this->input->post('nama_kabagkeuangan', TRUE),
			'nama_kasirpemebelian' => $this->input->post('nama_kasirpemebelian', TRUE),
		);



		$uuid_pengajuan_bayar_terproses = $this->Tbl_pembelian_pengajuan_bayar_model->insert($data);

		// print_r("uuid_pengajuan_bayar_terproses : ");
		// print_r($uuid_pengajuan_bayar_terproses);
		// die;

		// UPDATE TABEL PEMBELIAN : Jika nominal pembayaran = jumlah tagihan maka  statuslu ==> Lunas
		// $data_status = $this->Sys_status_transaksi_model->get_by_uuid_status_transaksi($this->input->post('uuid_status_transaksi', TRUE));

		// CEK TAGIHAN DAN CEK JUMLAH PEMBAYARAN

		$this->db->where('uuid_spop', $uuid_spop);
		$Get_data_pembayaran_pembelian = $this->db->get('tbl_pembelian_pengajuan_bayar');

		// print_r($Get_data_pembayaran_pembelian);
		// print_r("<br/>");
		// print_r("<br/>");

		if ($Get_data_pembayaran_pembelian->num_rows() > 0) {

			// $RowArray_data_kas_Kecil = $Get_data_kas_Kecil->row_array();
			// $get_id = $RowArray_data_kas_Kecil['id'];

			$sql_pembayaran = "SELECT `uuid_spop`, sum(`nominal_pengajuan`) as total_sudah_terbayar FROM `tbl_pembelian_pengajuan_bayar` WHERE `uuid_spop`='$uuid_spop' GROUP by `uuid_spop`";

			$Data_Pembayaran_uuid_spop = $this->db->query($sql_pembayaran)->row();


			// print_r($Data_Pembayaran_uuid_spop);
			// print_r("<br/>");

			// print_r($Data_Pembayaran_uuid_spop->total_sudah_terbayar);
			// print_r("<br/>");

			// GET TAGIHAN DI TABEL PEMBELIAN

			$sql_data_pembelian = "SELECT `uuid_spop`,`spop`,`jumlah`,`harga_satuan` FROM `tbl_pembelian` WHERE `uuid_spop`='$uuid_spop'";
			$jumlah_tagihan_total = 0;
			foreach ($this->db->query($sql_data_pembelian)->result() as $list_data) {
				// print_r($list_data->jumlah);
				// print_r("<br/>");
				// print_r($list_data->harga_satuan);
				// print_r("<br/>");
				$jumlah_tagihan_total = $jumlah_tagihan_total + ($list_data->jumlah * $list_data->harga_satuan);

				// print_r($jumlah_tagihan_total);
				// print_r("<br/>");
				// print_r("<br/>");
			}

			if ($Data_Pembayaran_uuid_spop->total_sudah_terbayar >= $jumlah_tagihan_total) {
				$data = array(
					'statuslu' => "L",
				);

				$this->Tbl_pembelian_model->update_statuslu_per_spop($uuid_spop, $data);
			}
		}

		// print_r($Data_Pembayaran_uuid_spop->total_sudah_terbayar);
		// print_r("<br/>");
		// print_r($jumlah_tagihan_total);
		// print_r("<br/>");
		// die;

		redirect(site_url('tbl_pembelian/success_pengajuan/' . $uuid_pengajuan_bayar_terproses));
	}

	public function success_pengajuan($uuid_pengajuan_bayar_terproses)
	{

		$row_per_uuid_pengajuan_bayar_terproses = $this->Tbl_pembelian_pengajuan_bayar_model->get_by_uuid_pengajuan_bayar($uuid_pengajuan_bayar_terproses);

		// print_r($row_per_uuid_pengajuan_bayar_terproses);

		// if ($row_per_uuid_pengajuan_bayar_terproses->uuid_bank_bkk) {
		// 	print_r("ada bank");
		// } else {
		// 	print_r("tidak ada bank");
		// }
		// print_r("<br/>");

		$uuid_spop = $row_per_uuid_pengajuan_bayar_terproses->uuid_spop;

		$row_per_uuid_spop = $this->Tbl_pembelian_model->get_by_uuid_spop($uuid_spop);
		$RESULT_per_uuid_spop = $this->Tbl_pembelian_model->get_by_uuid_spop_ALL_result($uuid_spop);

		// die;

		// print_r($row_per_uuid_pengajuan_bayar_terproses->nominal_pengajuan);

		$data = array(
			'data_ALL_per_SPOP' => $RESULT_per_uuid_spop,
			'button' => 'Simpan',
			'action' => site_url('tbl_pembelian/create_pembayaran_action/' . $uuid_spop),
			'id' => set_value('id'),
			'tgl_po' => $row_per_uuid_spop->tgl_po,
			'nmrfakturkwitansi' => $row_per_uuid_spop->nmrfakturkwitansi,
			'uuid_spop' => $row_per_uuid_spop->uuid_spop,
			'spop' => $row_per_uuid_spop->spop,
			'supplier_kode' => $row_per_uuid_spop->supplier_kode,
			'supplier_nama' => $row_per_uuid_spop->supplier_nama,
			'uuid_konsumen' => $row_per_uuid_spop->uuid_konsumen,
			'konsumen' => $row_per_uuid_spop->konsumen,
			'statuslu' => $row_per_uuid_spop->statuslu,
			'kas_bank' => $row_per_uuid_spop->kas_bank,

			'uuid_pengajuan_bayar_terproses' => $uuid_pengajuan_bayar_terproses,


			'tgl_pengajuan' => $row_per_uuid_pengajuan_bayar_terproses->tgl_pengajuan,
			'supplier_nama' => $row_per_uuid_pengajuan_bayar_terproses->supplier_nama,
			'nominal_pengajuan' => $row_per_uuid_pengajuan_bayar_terproses->nominal_pengajuan,
			'nomor_permohonan' => $row_per_uuid_pengajuan_bayar_terproses->nomor_permohonan,
			'jumlah_nominal' => $row_per_uuid_pengajuan_bayar_terproses->jumlah_nominal,
			'tgl_permohonan' => $row_per_uuid_pengajuan_bayar_terproses->tgl_permohonan,
			'terbilang' => $row_per_uuid_pengajuan_bayar_terproses->terbilang,
			'keterangan' => $row_per_uuid_pengajuan_bayar_terproses->keterangan,
			'tgl_jatuh_tempo' => $row_per_uuid_pengajuan_bayar_terproses->tgl_jatuh_tempo,
			'nomor_faktur' => $row_per_uuid_pengajuan_bayar_terproses->nomor_faktur,
			'nama_bank' => $row_per_uuid_pengajuan_bayar_terproses->nama_bank,
			'nomor_rekening' => $row_per_uuid_pengajuan_bayar_terproses->nomor_rekening,
			'atas_nama_rekening' => $row_per_uuid_pengajuan_bayar_terproses->atas_nama_rekening,
			'nomor_bkk' => $row_per_uuid_pengajuan_bayar_terproses->nomor_bkk,
			'tgl_nomor_bkk' => $row_per_uuid_pengajuan_bayar_terproses->tgl_nomor_bkk,
			'bank_checkbox' => $row_per_uuid_pengajuan_bayar_terproses->bank_checkbox,
			'kas_checkbox' => $row_per_uuid_pengajuan_bayar_terproses->kas_checkbox,
			'account' => $row_per_uuid_pengajuan_bayar_terproses->account,
			'nomor_cek_giro' => $row_per_uuid_pengajuan_bayar_terproses->nomor_cek_giro,
			'nama_direktur' => $row_per_uuid_pengajuan_bayar_terproses->nama_direktur,
			'nama_kabagkeuangan' => $row_per_uuid_pengajuan_bayar_terproses->nama_kabagkeuangan,
			'nama_kasirpemebelian' => $row_per_uuid_pengajuan_bayar_terproses->nama_kasirpemebelian,
			'uuid_bank_bkk' => $row_per_uuid_pengajuan_bayar_terproses->uuid_bank_bkk,
			'nomor_rekening_bkk' => $row_per_uuid_pengajuan_bayar_terproses->nomor_rekening_bkk,
			'atas_nama_rekening_bkk' => $row_per_uuid_pengajuan_bayar_terproses->atas_nama_rekening_bkk,

		);

		// print_r($data);
		// die;

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_cetak_pengajuan_pembayaran_view', $data);
	}


	public function cetak_pengajuan_bayar_per_spop($uuid_pengajuan_bayar_terproses = null)
	{



		$row_per_uuid_pengajuan_bayar_terproses = $this->Tbl_pembelian_pengajuan_bayar_model->get_by_uuid_pengajuan_bayar($uuid_pengajuan_bayar_terproses);

		$uuid_spop = $row_per_uuid_pengajuan_bayar_terproses->uuid_spop;

		$row_per_uuid_spop = $this->Tbl_pembelian_model->get_by_uuid_spop($uuid_spop);
		$RESULT_per_uuid_spop = $this->Tbl_pembelian_model->get_by_uuid_spop_ALL_result($uuid_spop);

		// die;


		// 2.a. PERSIAPAN LIBRARY
		$this->load->library('PdfGenerator');

		// 2.b. PERSIAPAN DATA

		$data = array(
			'data_ALL_per_SPOP' => $RESULT_per_uuid_spop,
			'button' => 'Simpan',
			'action' => site_url('tbl_pembelian/create_pembayaran_action/' . $uuid_spop),
			'id' => set_value('id'),
			'tgl_po' => $row_per_uuid_spop->tgl_po,
			'nmrfakturkwitansi' => $row_per_uuid_spop->nmrfakturkwitansi,
			'uuid_spop' => $row_per_uuid_spop->uuid_spop,
			'spop' => $row_per_uuid_spop->spop,
			'supplier_kode' => $row_per_uuid_spop->supplier_kode,
			'supplier_nama' => $row_per_uuid_spop->supplier_nama,
			'uuid_konsumen' => $row_per_uuid_spop->uuid_konsumen,
			'konsumen' => $row_per_uuid_spop->konsumen,
			'statuslu' => $row_per_uuid_spop->statuslu,
			'kas_bank' => $row_per_uuid_spop->kas_bank,


			'tgl_pengajuan' => $row_per_uuid_pengajuan_bayar_terproses->tgl_pengajuan,
			'supplier_nama' => $row_per_uuid_pengajuan_bayar_terproses->supplier_nama,
			'nominal_pengajuan' => $row_per_uuid_pengajuan_bayar_terproses->nominal_pengajuan,
			'nomor_permohonan' => $row_per_uuid_pengajuan_bayar_terproses->nomor_permohonan,
			'jumlah_nominal' => $row_per_uuid_pengajuan_bayar_terproses->jumlah_nominal,
			'tgl_permohonan' => $row_per_uuid_pengajuan_bayar_terproses->tgl_permohonan,
			'terbilang' => $row_per_uuid_pengajuan_bayar_terproses->terbilang,
			'keterangan' => $row_per_uuid_pengajuan_bayar_terproses->keterangan,
			'tgl_jatuh_tempo' => $row_per_uuid_pengajuan_bayar_terproses->tgl_jatuh_tempo,
			'nomor_faktur' => $row_per_uuid_pengajuan_bayar_terproses->nomor_faktur,
			'nama_bank' => $row_per_uuid_pengajuan_bayar_terproses->nama_bank,
			'nomor_rekening' => $row_per_uuid_pengajuan_bayar_terproses->nomor_rekening,
			'atas_nama_rekening' => $row_per_uuid_pengajuan_bayar_terproses->atas_nama_rekening,
			'nomor_bkk' => $row_per_uuid_pengajuan_bayar_terproses->nomor_bkk,
			'tgl_nomor_bkk' => $row_per_uuid_pengajuan_bayar_terproses->tgl_nomor_bkk,
			'bank_checkbox' => $row_per_uuid_pengajuan_bayar_terproses->bank_checkbox,
			'kas_checkbox' => $row_per_uuid_pengajuan_bayar_terproses->kas_checkbox,
			'account' => $row_per_uuid_pengajuan_bayar_terproses->account,
			'nomor_cek_giro' => $row_per_uuid_pengajuan_bayar_terproses->nomor_cek_giro,
			'nama_direktur' => $row_per_uuid_pengajuan_bayar_terproses->nama_direktur,
			'nama_kabagkeuangan' => $row_per_uuid_pengajuan_bayar_terproses->nama_kabagkeuangan,
			'nama_kasirpemebelian' => $row_per_uuid_pengajuan_bayar_terproses->nama_kasirpemebelian,
			'uuid_bank_bkk' => $row_per_uuid_pengajuan_bayar_terproses->uuid_bank_bkk,
			'nomor_rekening_bkk' => $row_per_uuid_pengajuan_bayar_terproses->nomor_rekening_bkk,
			'atas_nama_rekening_bkk' => $row_per_uuid_pengajuan_bayar_terproses->atas_nama_rekening_bkk,

		);


		// 2.C. MENAMPILKAN FILE DATA
		// $data = array_merge($data);
		$html = $this->load->view('anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_cetak_pengajuan_pembayaran_print.php', $data, true);

		// 2.d. CONVERT TAMPILAN FILE DATA MENJADI FILE PDF
		$this->pdf->loadHtml($html);
		$this->pdf->render();

		$this->pdf->stream("NOTA_PENJUALAN.pdf", array("Attachment" => 0));
	}


	public function pembayaran()
	{
		// Data pembayaran KE supplier
		// $Data_supplier_tagihan = $this->Tbl_pembelian_model->supplier_tagihan();
		// $Data_supplier_tagihan = $this->Tbl_pembelian_pengajuan_bayar_model->get_all();


		$sql = "SELECT tbl_pembelian_a.uuid_spop as uuid_spop, 
        tbl_pembelian_a.spop as spop,
        tbl_pembelian_a.jumlah as jumlah,
        tbl_pembelian_a.harga_satuan as harga_satuan,
        sum(tbl_pembelian_a.harga_total) as total_pembelian,
        tbl_pembelian_a.supplier_nama as supplier_nama,
        tbl_pembelian_pengajuan_bayar_a.uuid_pengajuan_bayar as uuid_pengajuan_bayar,
        tbl_pembelian_pengajuan_bayar_a.nominal_pengajuan as nominal_pengajuan


        FROM tbl_pembelian tbl_pembelian_a 
		
		left join   tbl_pembelian_pengajuan_bayar  tbl_pembelian_pengajuan_bayar_a ON  tbl_pembelian_pengajuan_bayar_a.uuid_spop = tbl_pembelian_a.uuid_spop

		group by tbl_pembelian_a.uuid_spop,tbl_pembelian_pengajuan_bayar_a.uuid_pengajuan_bayar
        ";

		// return $this->db->query($sql)->result();

		// print_r($this->db->query($sql)->result());


		$Data_supplier_tagihan = $this->db->query($sql)->result();


		$Data_konsumen_tagihan = $this->Tbl_penjualan_model->konsumen_tagihan();
		// print_r($Data_konsumen_tagihan);
		// die;

		$data = array(
			'Data_supplier_tagihan' => $Data_supplier_tagihan,
			'Data_konsumen_tagihan' => $Data_konsumen_tagihan,
		);

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/pembayaran/adminlte310_pembayaran_list', $data);
	}


	public function pembayaran_ke_supplier()
	{
		// Data pembayaran KE supplier
		// $Data_supplier_tagihan = $this->Tbl_pembelian_model->supplier_tagihan();
		// $Data_supplier_tagihan = $this->Tbl_pembelian_pengajuan_bayar_model->get_all();

		$sql = "SELECT 
		tbl_pembelian_a.id as id, 
		tbl_pembelian_a.uuid_spop as uuid_spop, 
        tbl_pembelian_a.tgl_po as tgl_po,
        tbl_pembelian_a.spop as spop,
        tbl_pembelian_a.jumlah as jumlah,
        tbl_pembelian_a.harga_satuan as harga_satuan,
        sum(tbl_pembelian_a.harga_total) as total_pembelian,
        tbl_pembelian_a.supplier_nama as supplier_nama,
        tbl_pembelian_a.statuslu as statuslu,
        -- tbl_pembelian_pengajuan_bayar_a.uuid_pengajuan_bayar as uuid_pengajuan_bayar,
        -- tbl_pembelian_pengajuan_bayar_a.nominal_pengajuan as nominal_pengajuan
		tbl_pembelian_a.kas_bank as kas_bank
        FROM tbl_pembelian tbl_pembelian_a 
		-- left join   tbl_pembelian_pengajuan_bayar  tbl_pembelian_pengajuan_bayar_a ON  tbl_pembelian_pengajuan_bayar_a.uuid_spop = tbl_pembelian_a.uuid_spop
		group by tbl_pembelian_a.uuid_spop
		order by tbl_pembelian_a.tgl_po asc
        ";
		// return $this->db->query($sql)->result();
		// print_r($this->db->query($sql)->result());

		$Data_supplier_tagihan = $this->db->query($sql)->result();


		// $Data_konsumen_tagihan = $this->Tbl_penjualan_model->konsumen_tagihan();
		// print_r($Data_konsumen_tagihan);
		// die;

		$data = array(
			'Data_supplier_tagihan' => $Data_supplier_tagihan,
			// 'Data_konsumen_tagihan' => $Data_konsumen_tagihan,
		);

		// print_r($data);


		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/pembayaran/adminlte310_pembayaran_list_ke_supplier', $data);
	}


	// public function update_pembayaran_ke_supplier($id_data = null)
	// {
	// 	print_r("update_pembayaran_ke_supplier");
	// 	print_r("<br/>");
	// 	print_r($id_data);


	// 	die;

	// 	return ('pembayaran_ke_supplier');
	// }

	// public function update_pembayaran_ke_supplier($uuid_spop = null, $from_pembelian_page = null)
	public function update_pembayaran_ke_supplier($id_data = null)
	{

		// direktur
		$id_user_level = 999;
		$row_per_direktur = $this->User_model->get_by_id_user_level($id_user_level);

		// kabagkeuangan
		$id_user_level = 888;
		$row_per_kabagkeuangan = $this->User_model->get_by_id_user_level($id_user_level);

		// kasirpembelian
		$id_user_level = 777;
		$row_per_kasirpembelian = $this->User_model->get_by_id_user_level($id_user_level);

		// print_r("update_pembayaran_ke_supplier");
		// print_r("<br/>");
		// print_r($id_data);
		// print_r("<br/>");

		// PEMBAYARAN TRANSFER PER UID_SPOP
		$this->db->where('id', $id_data);
		$Query_data_pengajuan_bayar_by_id = $this->db->get('tbl_pembelian_pengajuan_bayar')->row();
		// $Get_data_TERBAYAR_VIA_TRANSFER_by_uuid_spop = $Query_data_pengajuan_bayar_by_uuid_spop->row();

		// print_r($Query_data_pengajuan_bayar_by_id);
		// print_r("<br/>");

		// print_r("<br/>");
		// print_r("<br/>");

		// GET DATA MASTER PEMBELIAN UNTUK MENDAPATKAN DATA UMUM PEMBELIAN
		$row_per_uuid_spop = $this->Tbl_pembelian_model->get_by_uuid_spop($Query_data_pengajuan_bayar_by_id->uuid_spop);

		// print_r($row_per_uuid_spop);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r($row_per_uuid_spop->uuid_supplier);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");


		// $RESULT_per_uuid_spop = $this->Tbl_pembelian_model->get_by_uuid_spop_ALL_result($uuid_spop);

		$from_pembelian_page = "BukanPembelian";
		$UPDATE_PROSES = "UPDATE_PROSES";
		// die;

		$data = array(
			'data_ALL_per_SPOP' => $RESULT_per_uuid_spop,
			'button' => 'Update',
			'action' => site_url('tbl_pembelian/create_pembayaran_action/' . $uuid_spop),
			'id' => $Query_data_pengajuan_bayar_by_id->id,
			'tgl_po' => $row_per_uuid_spop->tgl_po,
			'nmrfakturkwitansi' => $Query_data_pengajuan_bayar_by_id->nomor_faktur,
			'uuid_spop' => $row_per_uuid_spop->uuid_spop,
			'spop' => $row_per_uuid_spop->spop,
			'nominal_pengajuan' => $Query_data_pengajuan_bayar_by_id->nominal_pengajuan,
			// 'nominal_pengajuan' => preg_replace("/[^0-9]/", "", $x_total),
			// 'nominal_pengajuan' => str_replace(",", ".", str_replace(".", "", $x_total)),
			'supplier_uuid' => $row_per_uuid_spop->uuid_supplier,
			'supplier_kode' => $row_per_uuid_spop->supplier_kode,
			'supplier_nama' => $Query_data_pengajuan_bayar_by_id->supplier_nama,
			'uuid_konsumen' => $Query_data_pengajuan_bayar_by_id->uuid_konsumen,
			'konsumen' => $Query_data_pengajuan_bayar_by_id->konsumen,
			'statuslu' => $Query_data_pengajuan_bayar_by_id->statuslu,

			'uuid_bank' => $Query_data_pengajuan_bayar_by_id->uuid_bank,
			'nama_bank' => $Query_data_pengajuan_bayar_by_id->nama_bank,
			'kas_bank' => $Query_data_pengajuan_bayar_by_id->uuid_bank,
			
			
			'nama_direktur' => $Query_data_pengajuan_bayar_by_id->nama_direktur,
			'nama_kabagkeuangan' => $Query_data_pengajuan_bayar_by_id->nama_kabagkeuangan,
			'nama_kasirpemebelian' => $Query_data_pengajuan_bayar_by_id->nama_kasirpemebelian,
			
			'nomor_permohonan' => $Query_data_pengajuan_bayar_by_id->nomor_permohonan,
			'keterangan' => $Query_data_pengajuan_bayar_by_id->keterangan,
			'nomor_bkk' => $Query_data_pengajuan_bayar_by_id->nomor_bkk,
			'nomor_rekening' => $Query_data_pengajuan_bayar_by_id->nomor_rekening,
			'atas_nama_rekening' => $Query_data_pengajuan_bayar_by_id->atas_nama_rekening,
			'nomor_cek_giro' => $Query_data_pengajuan_bayar_by_id->nomor_cek_giro,
			
			'uuid_account_unit' => $Query_data_pengajuan_bayar_by_id->uuid_account_unit,
			'account' => $Query_data_pengajuan_bayar_by_id->account,
			
			
			
			'from_pembelian_page' => $from_pembelian_page,
			'UPDATE_DATA' => $UPDATE_PROSES,


		);

		// print_r($data);

		// die;

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_cetak_pengajuan_pembayaran', $data);
	}


	public function delete_pembayaran_dari_supplier($id_data = null)
	{
		print_r("PROCESSING delete_pembayaran_dari_supplier");
		print_r("<br/>");
		// print_r($id_data);
		die;
	}

	public function pembayaran_ke_supplier_test()
	{
		// Data pembayaran KE supplier
		// $Data_supplier_tagihan = $this->Tbl_pembelian_model->supplier_tagihan();
		// $Data_supplier_tagihan = $this->Tbl_pembelian_pengajuan_bayar_model->get_all();


		$sql = "SELECT tbl_pembelian_a.uuid_spop as uuid_spop, 
        tbl_pembelian_a.tgl_po as tgl_po,
        tbl_pembelian_a.spop as spop,
        tbl_pembelian_a.jumlah as jumlah,
        tbl_pembelian_a.harga_satuan as harga_satuan,
        sum(tbl_pembelian_a.harga_total) as total_pembelian,
        tbl_pembelian_a.supplier_nama as supplier_nama,
        tbl_pembelian_a.statuslu as statuslu,
        
        -- tbl_pembelian_pengajuan_bayar_a.uuid_pengajuan_bayar as uuid_pengajuan_bayar,
        -- tbl_pembelian_pengajuan_bayar_a.nominal_pengajuan as nominal_pengajuan

		tbl_pembelian_a.kas_bank as kas_bank

        FROM tbl_pembelian tbl_pembelian_a 
		
		-- left join   tbl_pembelian_pengajuan_bayar  tbl_pembelian_pengajuan_bayar_a ON  tbl_pembelian_pengajuan_bayar_a.uuid_spop = tbl_pembelian_a.uuid_spop

		group by tbl_pembelian_a.uuid_spop

		order by tbl_pembelian_a.tgl_po asc
        ";

		// return $this->db->query($sql)->result();

		// print_r($this->db->query($sql)->result());


		$Data_supplier_tagihan = $this->db->query($sql)->result();


		// $Data_konsumen_tagihan = $this->Tbl_penjualan_model->konsumen_tagihan();
		// print_r($Data_konsumen_tagihan);
		// die;

		$data = array(
			'Data_supplier_tagihan' => $Data_supplier_tagihan,
			// 'Data_konsumen_tagihan' => $Data_konsumen_tagihan,
		);

		print_r($data);


		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/pembayaran/adminlte310_pembayaran_list_ke_supplier', $data);
	}



	public function pembayaran_dari_konsumen()
	{

		$sql = "SELECT SUM(tbl_penjualan_a.jumlah * tbl_penjualan_a.harga_satuan) as total_belanja, 
		tbl_penjualan_a.uuid_konsumen as uuid_konsumen, 
		tbl_penjualan_a.konsumen_nama as nama_konsumen

		-- tbl_penjualan_pembayaran_b.tgl_bayar as tgl_bayar,		
		-- tbl_penjualan_pembayaran_b.nmr_bukti_bayar as nmr_bukti_bayar,
		-- tbl_penjualan_pembayaran_b.nominal_bayar as nominal_bayar
		
		FROM tbl_penjualan tbl_penjualan_a

		-- left join   tbl_penjualan_pembayaran  tbl_penjualan_pembayaran_b ON  tbl_penjualan_pembayaran_b.uuid_konsumen = tbl_penjualan_a.uuid_konsumen
		
		-- WHERE uuid_konsumen
		GROUP BY tbl_penjualan_a.uuid_konsumen";

		$Data_konsumen_tagihan = $this->db->query($sql)->result();

		$data = array(
			// 'Data_supplier_tagihan' => $Data_supplier_tagihan,
			'Data_konsumen_tagihan' => $Data_konsumen_tagihan,
		);
		// print_r($data);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/pembayaran/adminlte310_pembayaran_list_dari_konsumen', $data);
	}


	public function tagihan_per_uuid_konsumen($uuid_konsumen = null)
	{

		// RIWAYAT TRANSAKSI PENJUALAN
		$bayar_text = "bayar";
		$proses_text = "proses";
		// or `proses_bayar`<>'proses' or `proses_bayar`<>'$bayar_text'
		$sql = "SELECT * FROM `tbl_penjualan` WHERE `uuid_konsumen`='$uuid_konsumen' and `proses_bayar` <> '$proses_text' and `proses_bayar` <> '$bayar_text' ";
		$Data_konsumen_tagihan = $this->db->query($sql)->result();

		// RIWAYAT PEMBAYARAN
		$sql = "SELECT * FROM `tbl_penjualan_pembayaran` WHERE `uuid_konsumen`='$uuid_konsumen'";
		$Data_konsumen_pembayaran = $this->db->query($sql)->result();

		// RIWAYAT TRANSAKSI PENJUALAN
		$sql = "SELECT * FROM `tbl_penjualan` WHERE `uuid_konsumen`='$uuid_konsumen' and `proses_bayar`='proses'";

		$Data_konsumen_proses_bayar = $this->db->query($sql)->result();

		// DATA KONSUMEN
		// $sql = "SELECT kode_konsumen,nama_konsumen,nmr_kontak_konsumen,alamat_konsumen FROM sys_konsumen WHERE uuid_konsumen='$uuid_konsumen'";

		// $Data_konsumen = $this->db->query($sql)->row();



		// RIWAYAT PENJUALAN ACCOUNTING
		$bayar_text = "bayar";
		$proses_text = "proses";
		// $sql = "SELECT * FROM ` tbl_penjualan_accounting` WHERE `uuid_konsumen`='$uuid_konsumen' and `proses_bayar` <> '$proses_text' or `proses_bayar` <> '$bayar_text' ";
		$sql = "SELECT SUM( tbl_penjualan_accounting.jumlah *  tbl_penjualan_accounting.harga_satuan) as total_belanja_accounting FROM `tbl_penjualan_accounting` WHERE `uuid_konsumen`='$uuid_konsumen' and (`proses_bayar` <> '$proses_text' or `proses_bayar` <> '$bayar_text') ";
		// $Data_konsumen_tagihan_accounting = $this->db->query($sql)->num_rows();

		if ($this->db->query($sql)->num_rows() > 0) {
			$GET_Data_konsumen_tagihan_accounting = $this->db->query($sql)->row()->total_belanja_accounting;
		} else {
			$GET_Data_konsumen_tagihan_accounting = 0;
		}

		// RIWAYAT PEMBAYARAN PENJUALAN ACCOUNTING

		$this->db->where('uuid_konsumen', $uuid_konsumen);
		//$this->db->where('password',  $test);
		$sys_konsumen_data = $this->db->get('sys_konsumen');

		if ($sys_konsumen_data->num_rows() > 0) {
			// Konsumen dari sys_konsumen
			// $get_uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
			$sql_uuid_konsumen = "SELECT * FROM `sys_konsumen` WHERE `uuid_konsumen`='$uuid_konsumen'";
			$get_kode_konsumen = $this->db->query($sql_uuid_konsumen)->row()->kode_konsumen;
			$get_nama_konsumen = $this->db->query($sql_uuid_konsumen)->row()->nama_konsumen;
			$get_nomor_kontak_konsumen = $this->db->query($sql_uuid_konsumen)->row()->nmr_kontak_konsumen;
			$get_alamat_konsumen = $this->db->query($sql_uuid_konsumen)->row()->alamat_konsumen;
			$Data_konsumen = $this->db->query($sql_uuid_konsumen)->row();
		} else {
			// Konsumen dari unit

			// $uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
			// $data_konsumen = $this->Sys_unit_model->get_by_uuid_unit($uuid_konsumen);
			// $data_nama_konsumen = $data_konsumen->nama_unit;

			// $get_uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
			$sql_uuid_konsumen = "SELECT * FROM `sys_unit` WHERE `uuid_unit`='$uuid_konsumen'";
			$get_kode_konsumen = $this->db->query($sql_uuid_konsumen)->row()->kode_unit;
			$get_nama_konsumen = $this->db->query($sql_uuid_konsumen)->row()->nama_unit;
			$get_nomor_kontak_konsumen = $this->db->query($sql_uuid_konsumen)->row()->keterangan;
			$get_alamat_konsumen = $this->db->query($sql_uuid_konsumen)->row()->keterangan;
			$Data_konsumen = $this->db->query($sql_uuid_konsumen)->row();
		}

		$data = array(
			'button' => 'Simpan',
			'action_nominal' => site_url('tbl_pembelian/create_pembayaran_per_uuid_konsumen_by_nominal_action/' . $uuid_konsumen),
			'action_pertransaksi' => site_url('tbl_pembelian/create_pembayaran_per_uuid_konsumen_by_transaksi_action/' . $uuid_konsumen),
			'nominal_bayar_input' => set_value('nominal_bayar_input'),

			'Data_konsumen_proses_bayar' => $Data_konsumen_proses_bayar,
			'Data_konsumen_tagihan' => $Data_konsumen_tagihan,
			'Data_konsumen_pembayaran' => $Data_konsumen_pembayaran,
			'GET_Data_konsumen_tagihan_accounting' => $GET_Data_konsumen_tagihan_accounting,

			'kode_konsumen' => $get_kode_konsumen,
			'nama_konsumen' => $get_nama_konsumen,
			'nmr_kontak_konsumen' => $get_nomor_kontak_konsumen,
			'alamat_konsumen' => $get_alamat_konsumen,
			'uuid_konsumen' => $uuid_konsumen,
		);


		// print_r($Data_konsumen_tagihan);

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/pembayaran/adminlte310_pembayaran_form_per_uuid_konsumen', $data);
	}


	public function create_pembayaran_per_uuid_konsumen_by_nominal_action($uuid_konsumen = null)
	{

		$get_data_kode_akun = $this->Sys_kode_akun_model->get_by_uuid_kode_akun($this->input->post('uuid_kode_akun_nominal', TRUE));

		$data = array(
			'tgl_input' => date("Y-m-d H:i:s"),
			'tgl_bayar' => date("Y-m-d H:i:s", strtotime($this->input->post('tanggal_bayar_input', TRUE))),
			'nominal_bayar' => preg_replace("/[^0-9]/", "", $this->input->post('nominal_bayar_input', TRUE)),
			'nmr_bukti_bayar' => $this->input->post('nomor_bayar_input', TRUE),
			'uuid_konsumen' => $uuid_konsumen,
			'uuid_kode_akun' => $get_data_kode_akun->uuid_kode_akun,
			'kode_akun' => $get_data_kode_akun->kode_akun,
			'nama_akun' => $get_data_kode_akun->nama_akun,
		);

		$this->Tbl_penjualan_pembayaran_model->insert($data);

		redirect(site_url('tbl_pembelian/pembayaran_dari_konsumen'));
	}

	public function create_pembayaran_per_uuid_konsumen_by_transaksi_action($uuid_konsumen = null)
	{

		// print_r($uuid_konsumen);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");

		$get_data_kode_akun = $this->Sys_kode_akun_model->get_by_uuid_kode_akun($this->input->post('uuid_kode_akun', TRUE));

		// PENJUALAN
		$sql = "select * from `tbl_penjualan` where  `uuid_konsumen`='$uuid_konsumen' and (`proses_bayar`='proses' or `proses_bayar`='belum_bayar' )";

		foreach ($this->db->query($sql)->result() as $list_data) {

			// print_r($list_data);
			// die;
			// Copy record dari tbl_penjualan ke tbl_penjualan_bayar
			$data = array(
				'tgl_input' => date("Y-m-d H:i:s"),
				'tgl_bayar' => date("Y-m-d H:i:s", strtotime($this->input->post('tanggal_bayar_input', TRUE))),
				// 'nominal_bayar' => preg_replace("/[^0-9]/", "", $this->input->post('nominal_bayar_input', TRUE)),
				'nmr_bukti_bayar' => $this->input->post('nomor_bayar_input_per_transaksi', TRUE),
				'uuid_konsumen' => $uuid_konsumen,
				// ALL FIELD FROM tbl_penjualan

				// 'tgl_bayar' => $list_data->tgl_bayar,
				'nominal_bayar' => $list_data->jumlah * $list_data->harga_satuan,
				// 'nmr_bukti_bayar' => $list_data->nmr_bukti_bayar,
				'uuid_penjualan' => $list_data->uuid_penjualan,
				'uuid_barang' => $list_data->uuid_barang,
				'tgl_input' => $list_data->tgl_input,
				'tgl_jual' => $list_data->tgl_jual,
				'nmrpesan' => $list_data->nmrpesan,
				'nmrkirim' => $list_data->nmrkirim,
				'konsumen_id' => $list_data->konsumen_id,
				'konsumen_nama' => $list_data->konsumen_nama,
				'kode_barang' => $list_data->kode_barang,
				'nama_barang' => $list_data->nama_barang,
				'uuid_unit' => $list_data->uuid_unit,
				'unit' => $list_data->unit,
				'satuan' => $list_data->satuan,
				'harga_satuan' => $list_data->harga_satuan,
				'jumlah' => $list_data->jumlah,
				'total_nominal' => $list_data->total_nominal,
				'umpphpsl22' => $list_data->umpphpsl22,
				'piutang' => $list_data->piutang,
				'penjualandpp' => $list_data->penjualandpp,
				'utangppn' => $list_data->utangppn,
				'uuid_kode_akun' => $get_data_kode_akun->uuid_kode_akun,
				'kode_akun' => $get_data_kode_akun->kode_akun,
				'nama_akun' => $get_data_kode_akun->nama_akun,

			);

			print_r($data);
			print_r("<br/>");
			print_r("<br/>");

			$this->Tbl_penjualan_pembayaran_model->insert($data);


			// Refresh / update data tbl_penjualan = update proses_bayar=bayar ,tgl-input, tgl_bayar dan nomor bukti bayar
			$data = array(
				'proses_bayar' => "bayar",
				'tgl_bayar_input' => date("Y-m-d H:i:s"),
				'tgl_bayar' => date("Y-m-d H:i:s", strtotime($this->input->post('tanggal_bayar_input', TRUE))),
				'nmr_bukti_bayar' => $this->input->post('nomor_bayar_input_per_transaksi', TRUE),
			);

			$this->Tbl_penjualan_model->update($list_data->id, $data);
		}
		// END OF PENJUALAN

		print_r("proses");
		print_r("<br/>");
		print_r("<br/>");
		print_r("<br/>");
		// die;

		// PENJUALAN ACCOUNTING


		print_r($uuid_konsumen);
		print_r("<br/>");
		print_r("<br/>");
		print_r("<br/>");


		$start = 0;
		$sql = "select * from `tbl_penjualan_accounting` where  `uuid_konsumen`='$uuid_konsumen' and (`proses_bayar`='proses' or `proses_bayar`='belum_bayar' )";

		print_r($this->db->query($sql)->result());
		print_r("<br/>");
		print_r("<br/>");
		print_r("<br/>");

		foreach ($this->db->query($sql)->result() as $list_data) {

			echo ++$start;
			print_r("<br/>");
			print_r($list_data->id);
			print_r("<br/>");

			// print_r($list_data);
			// die;
			// Copy record dari tbl_penjualan ke tbl_penjualan_bayar
			$data = array(
				'tgl_input' => date("Y-m-d H:i:s"),
				'tgl_bayar' => date("Y-m-d H:i:s", strtotime($this->input->post('tanggal_bayar_input', TRUE))),
				// 'nominal_bayar' => preg_replace("/[^0-9]/", "", $this->input->post('nominal_bayar_input', TRUE)),
				'nmr_bukti_bayar' => $this->input->post('nomor_bayar_input_per_transaksi', TRUE),
				'uuid_konsumen' => $uuid_konsumen,
				// ALL FIELD FROM tbl_penjualan

				// 'tgl_bayar' => $list_data->tgl_bayar,
				'nominal_bayar' => $list_data->jumlah * $list_data->harga_satuan,
				// 'nmr_bukti_bayar' => $list_data->nmr_bukti_bayar,
				'uuid_penjualan' => $list_data->uuid_penjualan,
				'uuid_barang' => $list_data->uuid_barang,
				'tgl_input' => $list_data->tgl_input,
				'tgl_jual' => $list_data->tgl_jual,
				'nmrpesan' => $list_data->nmrpesan,
				'nmrkirim' => $list_data->nmrkirim,
				'konsumen_id' => $list_data->konsumen_id,
				'konsumen_nama' => $list_data->konsumen_nama,
				'kode_barang' => $list_data->kode_barang,
				'nama_barang' => $list_data->nama_barang,
				'uuid_unit' => $list_data->uuid_unit,
				'unit' => $list_data->unit,
				'satuan' => $list_data->satuan,
				'harga_satuan' => $list_data->harga_satuan,
				'jumlah' => $list_data->jumlah,
				'total_nominal' => $list_data->total_nominal,
				'umpphpsl22' => $list_data->umpphpsl22,
				'piutang' => $list_data->piutang,
				'penjualandpp' => $list_data->penjualandpp,
				'utangppn' => $list_data->utangppn,
				'uuid_kode_akun' => $get_data_kode_akun->uuid_kode_akun,
				'kode_akun' => $get_data_kode_akun->kode_akun,
				'nama_akun' => $get_data_kode_akun->nama_akun,

			);

			print_r($data);
			print_r("<br/>");
			print_r("<br/>");

			$this->Tbl_penjualan_accounting_pembayaran_model->insert($data);


			// Refresh / update data tbl_penjualan = update proses_bayar=bayar ,tgl-input, tgl_bayar dan nomor bukti bayar
			$data = array(
				'proses_bayar' => "bayar",
				'tgl_bayar_input' => date("Y-m-d H:i:s"),
				'tgl_bayar' => date("Y-m-d H:i:s", strtotime($this->input->post('tanggal_bayar_input', TRUE))),
				'nmr_bukti_bayar' => $this->input->post('nomor_bayar_input_per_transaksi', TRUE),
			);

			print_r($data);
			print_r("<br/>");
			print_r("<br/>");



			$this->Tbl_penjualan_accounting_model->update($list_data->id, $data);

			print_r("Accounting selesai");
			print_r("<br/>");
			print_r("<br/>");
			print_r("<br/>");
		}
		// END OF PENJUALAN ACCOUNTING

		print_r("proses");
		// die;

		// $sql = "UPDATE `tbl_penjualan` SET `proses_bayar`='bayar',`tgl_bayar_input`='',`proses_bayar`='bayar' WHERE `uuid_konsumen`='$uuid_konsumen' and `proses_bayar`='proses' ";
		// $this->db->query($sql);

		redirect(site_url('tbl_pembelian/tagihan_per_uuid_konsumen/' . $uuid_konsumen));
	}



	public function pilih_proses_bayar_pertransaksi($uuid_konsumen_selected = null, $uuid_penjualan_proses_selected = null)
	{

		$sql = "UPDATE `tbl_penjualan` SET `proses_bayar`='proses' WHERE `uuid_penjualan_proses`='$uuid_penjualan_proses_selected'";
		$this->db->query($sql);
		redirect(site_url('tbl_pembelian/tagihan_per_uuid_konsumen/' . $uuid_konsumen_selected));
	}

	public function batal_proses_bayar_pertransaksi($uuid_konsumen_selected = null, $uuid_penjualan_proses_selected = null)
	{

		$sql = "UPDATE `tbl_penjualan` SET `proses_bayar`='belum_bayar' WHERE `uuid_penjualan_proses`='$uuid_penjualan_proses_selected'";
		$this->db->query($sql);
		redirect(site_url('tbl_pembelian/tagihan_per_uuid_konsumen/' . $uuid_konsumen_selected));
	}


	public function read($id)
	{
		$row = $this->Tbl_pembelian_model->get_by_id($id);
		if ($row) {
			$data = array(
				'id' => $row->id,
				'tgl_po' => $row->tgl_po,
				'nmrsj' => $row->nmrsj,
				'nmrfakturkwitansi' => $row->nmrfakturkwitansi,
				'nmrbpb' => $row->nmrbpb,
				'spop' => $row->spop,
				'supplier_kode' => $row->supplier_kode,
				'supplier_nama' => $row->supplier_nama,
				'uraian' => $row->uraian,
				'jumlah' => $row->jumlah,
				'satuan' => $row->satuan,
				'konsumen' => $row->konsumen,
				'harga_satuan' => $row->harga_satuan,
				'harga_total' => $row->harga_total,
				'statuslu' => $row->statuslu,
				'kas_bank' => $row->kas_bank,
				'tgl_bayar' => $row->tgl_bayar,
				'id_usr' => $row->id_usr,
			);
			$this->load->view('anekadharma/tbl_pembelian/tbl_pembelian_read', $data);
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(site_url('tbl_pembelian'));
		}
	}

	public function create()
	{
		$data = array(
			'button' => 'Simpan',
			'action' => site_url('tbl_pembelian/create_action_uuid_spop'),
			'id' => set_value('id'),
			'tgl_po' => set_value('tgl_po'),
			'nmrsj' => set_value('nmrsj'),
			'nmrfakturkwitansi' => set_value('nmrfakturkwitansi'),
			'nmrbpb' => set_value('nmrbpb'),
			'spop' => set_value('spop'),
			'supplier_kode' => set_value('supplier_kode'),
			'supplier_nama' => set_value('supplier_nama'),
			'uraian' => set_value('uraian'),
			'jumlah' => set_value('jumlah'),
			'satuan' => set_value('satuan'),
			'konsumen' => set_value('konsumen'),
			'harga_satuan' => set_value('harga_satuan'),
			'harga_total' => set_value('harga_total'),
			'statuslu' => set_value('statuslu'),
			'kas_bank' => set_value('kas_bank'),
			'tgl_bayar' => set_value('tgl_bayar'),
			'uuid_gudang' => set_value('uuid_gudang'),
			'id_usr' => set_value('id_usr'),
		);
		// $this->load->view('anekadharma/tbl_pembelian/tbl_pembelian_form', $data);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_form', $data);
	}


	public function create_action()
	{
		$this->_rules();

		if (($this->form_validation->run() == FALSE) or is_null($this->input->post('uuid_supplier', TRUE))  or is_null($this->input->post('uuid_konsumen', TRUE))) {
			$this->create();
		} else {

			// print_r("create_action");
			// die;

			// print_r($_POST["uraian"]);
			// print_r("<br/>");

			// GET SPOP
			$sql = "SELECT MAX(`spop`) as maxSPOP FROM `tbl_pembelian`";
			$get_maxSPOP = $this->db->query($sql)->row()->maxSPOP;

			$get_maxSPOP = $get_maxSPOP + 1;


			// DATE PO

			if (date("Y", strtotime($this->input->post('tgl_po', TRUE))) < 2020) {
				// print_r("Tahun kurang dari 2020");
				$date_po = date("Y-m-d H:i:s");
			} else {
				// print_r("Tahun lebih dari 2020");
				$date_po = date("Y-m-d H:i:s", strtotime($this->input->post('tgl_po', TRUE)));
			}


			// GET SUPPLIER DATA
			$get_uuid_supplier = $this->input->post('uuid_supplier', TRUE);
			$sql_uuid_supplier = "SELECT * FROM `sys_supplier` WHERE `uuid_supplier`='$get_uuid_supplier'";
			$get_kode_supplier = $this->db->query($sql_uuid_supplier)->row()->kode_supplier;
			$get_nama_supplier = $this->db->query($sql_uuid_supplier)->row()->nama_supplier;


			// GET KONSUMEN DATA
			$GET_uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
			// $sql_uuid_konsumen = "SELECT * FROM `sys_konsumen` WHERE `uuid_konsumen`='$GET_uuid_konsumen'";
			$sql_uuid_konsumen = "SELECT * FROM `sys_unit` WHERE `uuid_unit`='$GET_uuid_konsumen'";
			$get_kode_konsumen = $this->db->query($sql_uuid_konsumen)->row()->kode_unit;
			$get_nama_konsumen = $this->db->query($sql_uuid_konsumen)->row()->nama_unit;


			// GET GUDANG DATA
			$GET_uuid_gudang = $this->input->post('uuid_gudang', TRUE);
			$sql_uuid_gudang = "SELECT * FROM `sys_gudang` WHERE `uuid_gudang`='$GET_uuid_gudang'";
			$get_kode_gudang = $this->db->query($sql_uuid_gudang)->row()->kode_gudang;
			$get_nama_gudang = $this->db->query($sql_uuid_gudang)->row()->nama_gudang;




			// print_r(date("Y-m-d H:i:s", strtotime($this->input->post('tgl_po', TRUE))) );
			// die;

			if (isset($_POST["uraian"])) {
				$hoby = $_POST["uraian"];
				reset($hoby);
				while (list($key, $value) = each($hoby)) {
					$uraian   = $_POST["uraian"][$key];
					$jumlah     = $_POST["jumlah"][$key];
					$satuan     = $_POST["satuan"][$key];
					$hargasatuan     = $_POST["hargasatuan"][$key];

					// $sql_hoby   = "INSERT INTO tbl_hoby(rincian_hoby,jenis_hoby,id_karyawan)
					//   VALUES('$rincian_hoby','$jenis_hoby','$id_karyawan')";
					// $hasil_hoby = mysql_query($sql_hoby);

					$data = array(
						'date_input' => date("Y-m-d H:i:s"),
						'tgl_po' => $date_po,
						'nmrsj' => $this->input->post('nmrsj', TRUE),
						'nmrfakturkwitansi' => $this->input->post('nmrfakturkwitansi', TRUE),
						'nmrbpb' => $this->input->post('nmrbpb', TRUE),
						'spop' => $get_maxSPOP,
						// 'supplier_kode' => $get_kode_supplier,
						'supplier_nama' => $get_nama_supplier,
						'uraian' => $uraian,
						'jumlah' => $jumlah,
						'satuan' => $satuan,
						'konsumen' => $get_nama_konsumen,
						'harga_satuan' => $hargasatuan,
						// 'harga_total' => $this->input->post('harga_total', TRUE),
						'statuslu' => $this->input->post('statuslu', TRUE),
						'kas_bank' => $this->input->post('kas_bank', TRUE),
						'uuid_gudang' => $this->input->post('uuid_gudang', TRUE),
						'nama_gudang' => $get_nama_gudang,
						'id_usr' => 1,
					);

					$this->Tbl_pembelian_model->insert($data);
				}
			}



			$this->session->set_flashdata('message', 'Create Record Success');
			redirect(site_url('tbl_pembelian'));
		}
	}



	public function create_add_uraian_update($uuid_spop = null)
	{

		// print_r("create_add_uraian_update");
		// print_r("<br/>");
		// print_r($uuid_spop);
		// print_r("<br/>");

		$row_per_uuid_spop = $this->Tbl_pembelian_model->get_by_uuid_spop($uuid_spop);

		// if ($row_per_uuid_spop) {
		// 		print_r("ada data");
		// 		print_r($row_per_uuid_spop->num_row());
		// } else {
		// 	print_r("tidak ada data");

		// }
		// die;

		$RESULT_per_uuid_spop = $this->Tbl_pembelian_model->get_by_uuid_spop_ALL_result($uuid_spop);

		// print_r($row_per_uuid_spop);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r($RESULT_per_uuid_spop);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");
		// die;

		// print_r($RESULT_per_uuid_spop);

		// $jumlah_x = preg_replace("/[^0-9]/", "", $this->input->post('jumlah', TRUE));
		// $harga_satuan_x = preg_replace("/[^0-9]/", "", $this->input->post('harga_satuan', TRUE));
		$TOTAL_X = $row_per_uuid_spop->jumlah * $row_per_uuid_spop->harga_satuan;
		// print_r($row_per_uuid_spop->statuslu);
		if ($row_per_uuid_spop->statuslu == "u") {
			$get_statuslu = $row_per_uuid_spop->statuslu;
			$get_kas_bank = "";
		} else {
			$get_statuslu = $row_per_uuid_spop->statuslu;
			$get_kas_bank = $row_per_uuid_spop->kas_bank;
		}


		$data = array(
			'data_ALL_per_SPOP' => $RESULT_per_uuid_spop,
			'button' => 'Simpan Perubahan Detail SPOP',
			'action' => site_url('tbl_pembelian/create_action_detail_uuid_spop_update/' . $uuid_spop),
			'action_ubah_detail_spop' => site_url('tbl_pembelian/create_action_detail_uuid_spop_update/' . $uuid_spop),
			'id' => set_value('id'),
			'tgl_po' => $row_per_uuid_spop->tgl_po,
			// 'nmrsj' => $row_per_uuid_spop->nmrsj,
			'nmrfakturkwitansi' => $row_per_uuid_spop->nmrfakturkwitansi,
			// 'nmrbpb' => $row_per_uuid_spop->nmrbpb,
			'uuid_spop' => $row_per_uuid_spop->uuid_spop,
			'spop' => $row_per_uuid_spop->spop,
			'uuid_supplier' => $row_per_uuid_spop->uuid_supplier,
			'supplier_kode' => $row_per_uuid_spop->supplier_kode,
			'supplier_nama' => $row_per_uuid_spop->supplier_nama,
			// 'uraian' => $row_per_uuid_spop->uraian,
			// 'jumlah' => $row_per_uuid_spop->jumlah,
			// 'satuan' => $row_per_uuid_spop->satuan,
			'uuid_konsumen' => $row_per_uuid_spop->uuid_konsumen,
			'konsumen' => $row_per_uuid_spop->konsumen,
			// 'harga_satuan' => $row_per_uuid_spop->harga_satuan,
			'harga_total' => $TOTAL_X,
			'statuslu' => $get_statuslu,
			'kas_bank' => $get_kas_bank,
			// 'tgl_bayar' => $row_per_uuid_spop->tgl_bayar,
			// 'id_usr' => $row_per_uuid_spop->,
			'action_ubah_per_id' => site_url('tbl_pembelian/create_action_uuid_spop_update_per_id/'),
			'action_tambah_barang_per_spop' => site_url('tbl_pembelian/create_action_tambah_barang_per_spop/'),
		);

		// print_r($data);
		// die;

		// $this->load->view('anekadharma/tbl_pembelian/tbl_pembelian_form', $data);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_form_spop_ready', $data);
	}

	public function create_action_detail_uuid_spop_update($uuid_spop = null)
	{

		// print_r("create_action_detail_uuid_spop_update");
		// print_r("<br/>");
		// print_r($uuid_spop);
		// print_r("<br/>");
		// print_r($this->input->post('spop', TRUE));
		// die;

		// print_r("<br/>");
		// print_r($this->input->post('tgl_po', TRUE));
		// print_r("<br/>");
		// print_r($this->input->post('uuid_supplier', TRUE));
		// print_r("<br/>");
		// print_r($this->input->post('statuslu', TRUE));
		// print_r("<br/>");
		// print_r($this->input->post('kas_bank', TRUE));
		// print_r("<br/>");
		// print_r($this->input->post('spop', TRUE));
		// print_r("<br/>");
		// print_r($this->input->post('nmrfakturkwitansi', TRUE));


		// --------------------------------------------------------------------------------------------
		if (date("Y", strtotime($this->input->post('tgl_po', TRUE))) < 2020) {
			// print_r("Tahun kurang dari 2020");
			$date_po = date("Y-m-d H:i:s");
		} else {
			// print_r("Tahun lebih dari 2020");
			$date_po = date("Y-m-d H:i:s", strtotime($this->input->post('tgl_po', TRUE)));
		}



		// GET SUPPLIER DATA
		$get_uuid_supplier = $this->input->post('uuid_supplier', TRUE);
		$sql_uuid_supplier = "SELECT * FROM `sys_supplier` WHERE `uuid_supplier`='$get_uuid_supplier'";
		$get_kode_supplier = $this->db->query($sql_uuid_supplier)->row()->kode_supplier;
		$get_nama_supplier = $this->db->query($sql_uuid_supplier)->row()->nama_supplier;




		// --------------------------------------------------------------------------------------------


		// die;

		//  cek apakah SPOP diubah ? , jika di ubah: cek apakah spop yang baru sudah ada dalam sistem ? jika sudah ada , maka harus ada konfirmasi: apakah di gabung atau tidak ?

		$row_per_uuid_spop = $this->Tbl_pembelian_model->get_by_uuid_spop($uuid_spop);

		// print_r($row_per_uuid_spop->uuid_spop);
		// print_r("<br/>");
		// print_r($row_per_uuid_spop->spop);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r($this->input->post('spop', TRUE));
		// print_r("<br/>");

		if ($row_per_uuid_spop->spop <> $this->input->post('spop', TRUE)) {

			// print_r("<br/>");
			// echo "SPOP BEDA DDDDDDDDDDDDDD";

			// CEK APAKAH SPOP BARU SUDAH ADA DI DATABASE , JIKA SUDAH ADA MAKA HARUS KONFIRMASI UNTUK MENGGABUNGKAN ATAU TIDAK ?
			// JIKA MENGGABUNGKAN : MAKA UPDATE UUID_SPOP DAN SPOP SEMUA RECORD KE UUID_SPOP & SPOP YANG BARU , DAN SEMUA DETAIL SPOP UPDATE

			// $message = "SPOP BEDA BBBBBBBBBBBBBBBBBBBB";
			// echo "<script type='text/javascript'>alert('$message');</script>";
			// die;

			$table_pembelian = "tbl_pembelian";
			$this->db->where('uuid_spop', $uuid_spop);
			$this->db->update($table_pembelian, array(
				'tgl_po' => $date_po,
				'uuid_supplier' => $get_uuid_supplier,
				'supplier_nama' => $get_nama_supplier,
				'statuslu' => $this->input->post('statuslu', TRUE),
				'kas_bank' => $this->input->post('kas_bank', TRUE),
				'nmrfakturkwitansi' => $this->input->post('nmrfakturkwitansi', TRUE),
			));

			// redirect(site_url('Tbl_pembelian/create_add_uraian_update/' . $uuid_spop));

			// die;

			// Update data berdasarkan spop lama

			// update spop lama menjadi spop baru, 

			// Get uuid_spop by  $this->input->post('spop', TRUE)

			$get_spop_proses_new = $this->input->post('spop', TRUE);

			$this->db->where('spop', $get_spop_proses_new);
			$Get_record_spop = $this->db->get('tbl_pembelian');

			// print_r($Get_record_spop->row());
			// print_r("<br/>");
			// print_r("<br/>");

			if ($Get_record_spop->num_rows() > 0) {

				// ada spop , maka diubah uuid_spop

				// echo "ada record dengan spop sama, maka update uuid_spop dengan uuid_spop yang baru (copy spop)";
				// print_r("<br/>");
				// print_r($Get_record_spop->row()->uuid_spop);
				// print_r("<br/>");
				// print_r($Get_record_spop->row()->spop);

				$table_pembelian = "tbl_pembelian";
				$this->db->where('uuid_spop', $uuid_spop);
				$this->db->update($table_pembelian, array(
					'tgl_po' => $date_po,
					'uuid_supplier' => $get_uuid_supplier,
					'supplier_nama' => $get_nama_supplier,
					'statuslu' => $this->input->post('statuslu', TRUE),
					'kas_bank' => $this->input->post('kas_bank', TRUE),
					'nmrfakturkwitansi' => $this->input->post('nmrfakturkwitansi', TRUE),
					'uuid_spop' => $Get_record_spop->row()->uuid_spop,
					'spop' => $this->input->post('spop', TRUE),
				));

				// print_r("selesai gabung uuid_spop ");
				// print_r("<br/>");
				// print_r($this->input->post('spop', TRUE));
				// print_r("<br/>");
				// print_r($Get_record_spop->row()->uuid_spop);

				$uuid_spop_processing = $Get_record_spop->row()->uuid_spop;
			} else {
				// belum ada spop, maka buat spop baru
				// echo "Belum ada spop yang sama, maka buat uuid_spop baru, maka ubah spop saja & uuid_spop tidak di ubah";

				$table_pembelian = "tbl_pembelian";
				$this->db->where('uuid_spop', $uuid_spop);
				$this->db->update($table_pembelian, array(
					'tgl_po' => $date_po,
					'uuid_supplier' => $get_uuid_supplier,
					'supplier_nama' => $get_nama_supplier,
					'statuslu' => $this->input->post('statuslu', TRUE),
					'kas_bank' => $this->input->post('kas_bank', TRUE),
					'nmrfakturkwitansi' => $this->input->post('nmrfakturkwitansi', TRUE),
					// 'uuid_spop' => $Get_record_spop->row()->uuid_spop, // uuid_spop tidak perlu diubah, karena tidak ada yang sama
					'spop' => $this->input->post('spop', TRUE),
				));

				// print_r("selesai spop berubah ");
				// print_r("<br/>");
				// print_r($this->input->post('spop', TRUE));
				// print_r("<br/>");
				// print_r($Get_record_spop->row()->uuid_spop);

				$uuid_spop_processing = $uuid_spop;
			}
			// die;
			redirect(site_url('Tbl_pembelian/create_add_uraian_update/' . $uuid_spop_processing));
		} else {

			// echo "SPOP sama";
			// LANJUT PROSES UPDATE DATA

			$data_update_spop = array(
				'date_input' => date("Y-m-d H:i:s"),
				'tgl_po' => $date_po,

				'uuid_supplier' => $get_uuid_supplier,
				'supplier_nama' => $get_nama_supplier,

				'statuslu' => $this->input->post('statuslu', TRUE),
				'kas_bank' => $this->input->post('kas_bank', TRUE),
				'nmrfakturkwitansi' => $this->input->post('nmrfakturkwitansi', TRUE),
				// 'uuid_spop' => $row_per_uuid_spop->uuid_spop,
				// 'spop' => $row_per_uuid_spop->spop,

			);

			$this->Tbl_pembelian_model->update_proses_per_spop($uuid_spop, $data_update_spop);

			redirect(site_url('Tbl_pembelian/create_add_uraian_update/' . $uuid_spop));
		}

		// die;


		// $this->db->where('uuid_spop', $uuid_spop);
		// $get_data_spop = $this->db->get('tbl_pembelian');
		// if ($get_data_spop->num_rows() > 0) {
		// 	echo "ada spop yang sama";
		// } else {
		// 	echo "Tidak ada spop";
		// }



	}

	public function create_action_tambah_barang_per_spop($uuid_spop = null)
	{

		// print_r("create_action_tambah_barang_per_spop");
		// die;
		// print_r("<br/>");
		// print_r($uuid_spop);
		// print_r("<br/>");
		// print_r($this->input->post('uuid_konsumen', TRUE));
		// print_r("<br/>");
		// print_r($this->input->post('uuid_barang', TRUE));
		// print_r("<br/>");
		// print_r($this->input->post('jumlah', TRUE));
		// print_r("<br/>");
		// print_r($this->input->post('harga_satuan', TRUE));
		// print_r("<br/>");
		// print_r($this->input->post('uuid_gudang', TRUE));
		// die;



		$row_per_uuid_spop = $this->Tbl_pembelian_model->get_by_uuid_spop($uuid_spop);

		// GET KONSUMEN DATA
		$GET_uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
		// $sql_uuid_konsumen = "SELECT * FROM `sys_konsumen` WHERE `uuid_konsumen`='$GET_uuid_konsumen'";

		$sql_uuid_konsumen = "SELECT * FROM `sys_unit` WHERE `uuid_unit`='$GET_uuid_konsumen'";
		$get_kode_konsumen = $this->db->query($sql_uuid_konsumen)->row()->kode_unit;
		// $get_nama_konsumen = $this->db->query($sql_uuid_konsumen)->row()->nama_unit;

		// print_r($sql_uuid_konsumen);
		// die;

		$get_nama_konsumen = $this->db->query($sql_uuid_konsumen)->row()->nama_unit;


		// GET BARANG DATA
		$GET_uuid_barang = $this->input->post('uuid_barang', TRUE);
		$sql_uuid_barang = "SELECT * FROM `sys_nama_barang` WHERE `uuid_barang`='$GET_uuid_barang'";
		$get_kode_barang = $this->db->query($sql_uuid_barang)->row()->kode_barang;
		$get_nama_barang = $this->db->query($sql_uuid_barang)->row()->nama_barang;

		$jumlah_x = preg_replace("/[^0-9]/", "", $this->input->post('jumlah', TRUE));

		$harga_satuan_tanpa_titik = str_replace('.', '', $this->input->post('harga_satuan', TRUE)); // menghilangkan titik
		// print_r("harga_satuan_tanpa_titik: ");
		// print_r($harga_satuan_tanpa_titik);
		// print_r("<br/>");

		$harga_satuan_x = str_replace(',', '.', $harga_satuan_tanpa_titik); // mengganti koma dengan titik
		// Masukkan data ke database

		// print_r("harga_satuan_x: ");
		// print_r($harga_satuan_x);
		// print_r("<br/>");


		$TOTAL_X = $jumlah_x * $harga_satuan_x;


		// print_r("TOTAL_X: ");
		// print_r($TOTAL_X);
		// print_r("<br/>");
		// die;

		// GET GUDANG DATA
		$GET_uuid_gudang = $this->input->post('uuid_gudang', TRUE);
		$sql_uuid_gudang = "SELECT * FROM `sys_gudang` WHERE `uuid_gudang`='$GET_uuid_gudang'";
		$get_kode_gudang = $this->db->query($sql_uuid_gudang)->row()->kode_gudang;
		$get_nama_gudang = $this->db->query($sql_uuid_gudang)->row()->nama_gudang;


		$Get_tanggal_beli = $row_per_uuid_spop->tgl_po;
		$Get_uuid_spop = $row_per_uuid_spop->uuid_spop;
		$Get_spop = $row_per_uuid_spop->spop;
		$Get_Status_LU = $row_per_uuid_spop->statuslu;
		$Get_Kas_BANK = $row_per_uuid_spop->kas_bank;
		$Get_date_po = $row_per_uuid_spop->tgl_po;
		$Get_nominal = $jumlah_x * $harga_satuan_x;


		// Insert barang ke data persediaan
		$Get_nominal_persediaan = $jumlah_x * $harga_satuan_x;
		$data_persediaan = array(
			// 'id' => $this->input->post('id', TRUE),
			'tanggal' => date("Y-m-d H:i:s"),
			'tanggal_beli' => $row_per_uuid_spop->tgl_po,
			// 'kode' => $this->input->post('kode', TRUE),
			'uuid_barang' => $this->input->post('uuid_barang', TRUE),
			'namabarang' => $get_nama_barang,
			'satuan' => $this->input->post('satuan', TRUE),
			'hpp' => $harga_satuan_x,
			'sa' => $jumlah_x,
			'uuid_spop' => $row_per_uuid_spop->uuid_spop,
			'spop' => $row_per_uuid_spop->spop,
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
			'total_10' => $jumlah_x,
			'nilai_persediaan' => $Get_nominal_persediaan,
		);

		$GET_id_persediaan_beli_baru = $this->Persediaan_model->insert_produk_baru($data_persediaan);

		// print_r($GET_id_persediaan_beli_baru);
		// print_r("<br/>");

		// GET UUID_PERSEDIAAN BY ID PERSEDIAAN

		$this->db->where('id', $GET_id_persediaan_beli_baru);
		$DATA_persediaan = $this->db->get('persediaan');

		// print_r($DATA_persediaan->row());
		// print_r("<br/>");

		$GET_UUID_PERSEDIAAN = $DATA_persediaan->row()->uuid_persediaan;

		// print_r($GET_UUID_PERSEDIAAN);
		// print_r("<br/>");

		// die;

		// SIMPAN DATA KE PEMBELIAN
		$data = array(
			'date_input' => $row_per_uuid_spop->date_input,

			'tgl_po' => $row_per_uuid_spop->tgl_po,

			// 'nmrsj' => $this->input->post('nmrsj', TRUE),

			'nmrfakturkwitansi' => $row_per_uuid_spop->nmrfakturkwitansi,

			// 'nmrbpb' => $this->input->post('nmrbpb', TRUE),

			'id_persediaan_barang' => $GET_id_persediaan_beli_baru,
			'uuid_persediaan' => $GET_UUID_PERSEDIAAN,

			'uuid_spop' => $row_per_uuid_spop->uuid_spop,
			'spop' => $row_per_uuid_spop->spop,

			// 'supplier_kode' => $get_kode_supplier,
			'uuid_supplier' => $row_per_uuid_spop->uuid_supplier,
			'supplier_nama' => $row_per_uuid_spop->supplier_nama,


			'statuslu' => $row_per_uuid_spop->statuslu,
			'kas_bank' => $row_per_uuid_spop->kas_bank,


			'uuid_barang' => $this->input->post('uuid_barang', TRUE),
			'kode_barang' => $get_kode_barang,
			'uraian' => $get_nama_barang,

			'jumlah' => $jumlah_x,
			'satuan' => $this->input->post('satuan', TRUE),
			'harga_satuan' => $harga_satuan_x,

			'uuid_konsumen' => $this->input->post('uuid_konsumen', TRUE),
			'konsumen' => $get_nama_konsumen,

			'harga_total' => $TOTAL_X,
			'uuid_gudang' => $this->input->post('uuid_gudang', TRUE),
			'nama_gudang' => $get_nama_gudang,
			'id_usr' => 1,
		);
		// print_r($data);
		// die;

		$this->Tbl_pembelian_model->insert($data); // insert untuk data lanjutan uuid_spop sudah ada

		// TABEL KAS KECIL
		// JIKA STATUSLU = L & PILIHAN KAS ==> MAKA OTOMATIS MASUK DATA PENGELUARAN KAS KECIL

		if ($Get_Status_LU == "L" and $Get_Kas_BANK == "kas") {
			// echo "L kas";

			// Cek data spop di kas kecil , apakah sudah ada? , jika belum maka insert data pengeluaran keas kecil dan apabila sudah ada maka update data dan menjumlahkan nominal

			$this->db->where('uuid_spop', $Get_uuid_spop);
			//$this->db->where('password',  $test);
			$Get_data_kas_Kecil = $this->db->get('tbl_kas_kecil');

			// print_r($Get_data_kas_Kecil->num_rows());
			// print_r("<br/>");

			if ($Get_data_kas_Kecil->num_rows() > 0) {
				// Ada data spop di kas kecil ==> dapatkan nominal dan jumlahkan kemudian update berdasarkan spop


				// print_r("UPDATE: ");
				// print_r("<br/>");
				// print_r("Nominal Kredit");
				// print_r("<br/>");

				$RowArray_data_kas_Kecil = $Get_data_kas_Kecil->row_array();
				$get_id = $RowArray_data_kas_Kecil['id'];

				// print_r($RowArray_data_kas_Kecil['kredit']);
				// print_r("<br/>");
				// print_r($RowArray_data_kas_Kecil['id']);

				$data = array(
					// 'uuid_kas_kecil' => $this->input->post('uuid_kas_kecil', TRUE),
					'date_input' => date("Y-m-d H:i:s"),
					// 'tanggal' => $date_kas_kecil,
					// 'uuid_spop' => $get_spop,
					// 'uuid_unit' => $this->input->post('unit', TRUE),
					// 'unit' => $row_unit->nama_unit,
					// 'keterangan' => $this->input->post('keterangan', TRUE),
					// 'status_data' => "pengeluaran",

					// 'debet' => preg_replace("/[^0-9]/", "", $this->input->post('debet', TRUE)),

					// 'kredit' => preg_replace("/[^0-9]/", "", $this->input->post('kredit', TRUE)),
					'kredit' => $RowArray_data_kas_Kecil['kredit'] + str_replace(",", ".", str_replace(".", "", $Get_nominal)),


					// 'saldo' => $this->input->post('saldo', TRUE),
					// 'id_usr' => $this->input->post('id_usr', TRUE),
				);
				$this->Tbl_kas_kecil_model->update($get_id, $data);
			} else {
				// Belum ada data spop ==> lanjutkan dengan insert data pengeluaran baru berdasarkan uuid_spop


				// $row_unit = $this->Sys_unit_model->get_by_uuid_unit($this->input->post('unit', TRUE));


				// if (date("Y", strtotime($this->input->post('tanggal', TRUE))) < 2020) {
				// 	$date_kas_kecil = date("Y-m-d H:i:s");
				// } else {
				// 	$date_kas_kecil = date("Y-m-d H:i:s", strtotime($this->input->post('tanggal', TRUE)));
				// }

				// print_r($date_kas_kecil);
				// die;

				if (date("Y", strtotime($Get_date_po)) < 2020) {
					$date_proses = date("d-m-Y");
				} else {
					$date_proses = date("d-m-Y", strtotime($Get_date_po));
				}


				$data_pengeluaran_kas_kecil = array(
					// 'uuid_kas_kecil' => $this->input->post('uuid_kas_kecil', TRUE),
					'date_input' => date("Y-m-d H:i:s"),
					'tanggal' => $Get_date_po,

					'uuid_spop' => $Get_uuid_spop,
					'uuid_unit' => $GET_uuid_konsumen,
					'unit' => $get_nama_konsumen,

					'keterangan' => "Pembayaran SPOP No " . $Get_spop  . " tgl " . $date_proses,
					'status_data' => "pengeluaran",

					// 'debet' => preg_replace("/[^0-9]/", "", $this->input->post('debet', TRUE)),
					// 'debet' => str_replace(".", "", $this->input->post('debet', TRUE)),
					'kredit' => str_replace(",", ".", str_replace(".", "", $Get_nominal)),


					// 'kredit' => preg_replace("/[^0-9]/", "", $this->input->post('kredit', TRUE)),
					// 'saldo' => $this->input->post('saldo', TRUE),
					// 'id_usr' => $this->input->post('id_usr', TRUE),
				);



				$this->Tbl_kas_kecil_model->insert($data_pengeluaran_kas_kecil);

				// print_r("INSERT: ");
				// print_r("<br/>");
				// print_r($data_pengeluaran_kas_kecil);
			}
		}

		// die;

		// print_r($data);
		// die;


		redirect(site_url('Tbl_pembelian/create_add_uraian_update/' . $uuid_spop));
	}


	public function create_action_uuid_spop_update_per_id($id)
	{

		// print_r("create_action_uuid_spop_update_per_id");
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
		// // die;

		// Get uuid_spop from id
		$sql_data_pembelian = "SELECT * FROM `tbl_pembelian` WHERE `id`='$id'";
		$get_uuid_spop = $this->db->query($sql_data_pembelian)->row()->uuid_spop;
		$get_uuid_persediaan = $this->db->query($sql_data_pembelian)->row()->uuid_persediaan;
		$get_id_persediaan = $this->db->query($sql_data_pembelian)->row()->id_persediaan_barang;

		// print_r($this->db->query($sql_data_pembelian)->row());
		// print_r("<br/>");

		// print_r($this->db->query($sql_data_pembelian)->row()->uuid_persediaan);
		// print_r("<br/>");

		// // die;
		// print_r("<br/>");
		// print_r($get_uuid_spop);
		// print_r("<br/>");
		// // die;


		// $row_per_uuid_spop = $this->Tbl_pembelian_model->get_by_uuid_spop($uuid_spop);

		// GET KONSUMEN DATA
		$GET_uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);

		$sql_uuid_konsumen = "SELECT * FROM `sys_unit` WHERE `uuid_unit`='$GET_uuid_konsumen'";
		$get_kode_konsumen = $this->db->query($sql_uuid_konsumen)->row()->kode_unit;
		$get_nama_konsumen = $this->db->query($sql_uuid_konsumen)->row()->nama_unit;

		// GET BARANG DATA
		$GET_uuid_barang = $this->input->post('uuid_barang', TRUE);
		$sql_uuid_barang = "SELECT * FROM `sys_nama_barang` WHERE `uuid_barang`='$GET_uuid_barang'";
		$get_kode_barang = $this->db->query($sql_uuid_barang)->row()->kode_barang;
		$get_nama_barang = $this->db->query($sql_uuid_barang)->row()->nama_barang;

		$jumlah_x = preg_replace("/[^0-9]/", "", $this->input->post('jumlah', TRUE));

		$harga_satuan_tanpa_titik = str_replace('.', '', $this->input->post('harga_satuan', TRUE)); // menghilangkan titik		
		$harga_satuan_x = str_replace(',', '.', $harga_satuan_tanpa_titik); // mengganti koma dengan titik

		// print_r("<br/>");
		// print_r($harga_satuan_tanpa_titik);
		// print_r("<br/>");


		// Masukkan data ke database





		$harga_satuan_x = str_replace(",", ".", str_replace(".", "", $this->input->post('harga_satuan', TRUE))); // menghilangkan titik dan mengubah koma menjadi titik agar bisa masuk ke type data decimal di mysql


		$TOTAL_X = $jumlah_x * $harga_satuan_x;

		// GET GUDANG DATA
		$GET_uuid_gudang = $this->input->post('uuid_gudang', TRUE);
		$sql_uuid_gudang = "SELECT * FROM `sys_gudang` WHERE `uuid_gudang`='$GET_uuid_gudang'";
		$get_kode_gudang = $this->db->query($sql_uuid_gudang)->row()->kode_gudang;
		$get_nama_gudang = $this->db->query($sql_uuid_gudang)->row()->nama_gudang;




		// UPDATE DATA BARANG
		$data = array(
			'date_input' => date("Y-m-d H:i:s"),

			'uuid_barang' => $this->input->post('uuid_barang', TRUE),
			'kode_barang' => $get_kode_barang,
			'uraian' => $get_nama_barang,

			'jumlah' => $jumlah_x,
			'satuan' => $this->input->post('satuan', TRUE),
			'harga_satuan' => $harga_satuan_x,

			'uuid_konsumen' => $this->input->post('uuid_konsumen', TRUE),
			'konsumen' => $get_nama_konsumen,

			'harga_total' => $TOTAL_X,
			'uuid_gudang' => $this->input->post('uuid_gudang', TRUE),
			'nama_gudang' => $get_nama_gudang,
			// 'id_usr' => 1,
		);

		$this->Tbl_pembelian_model->update($id, $data);


		// print_r("<br/>");
		// print_r("<br/>");
		// print_r($data);
		// print_r("<br/>");
		// print_r("<br/>");

		//KONTROL INI BELUM ADA:
		// NOTE : HARUS CEK FIELD PENJUALAN , JIKA SUDAH ADA PROSES PENJUALAN, MAKA TIDAK BOLEH MENGUBAH NAMA BARANG, HANYA BISA MENGUBAH HPP DAN JUMLAH BELI (JUMLAH BELI HARUS LEBIH DARI TOTAL JUMLAH TERJUAL)

		// UPDATE DATA DI PERSEDIAAN berdasarkan id persediaan atau uuid_persediaan
		// $get_uuid_persediaan
		// $Get_nominal_persediaan = $jumlah_x * $harga_satuan_x;
		$Update_data_persediaan = array(
			// 'id' => $this->input->post('id', TRUE),
			// 'tanggal' => date("Y-m-d H:i:s"),
			// 'tanggal_beli' => $row_per_uuid_spop->tgl_po,
			// 'kode' => $this->input->post('kode', TRUE),
			'uuid_barang' => $this->input->post('uuid_barang', TRUE),
			'namabarang' => $get_nama_barang,
			'satuan' => $this->input->post('satuan', TRUE),
			'hpp' => $harga_satuan_x,
			'sa' => $jumlah_x,
			// 'uuid_spop' => $row_per_uuid_spop->uuid_spop,
			// 'spop' => $row_per_uuid_spop->spop,
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
			'total_10' => $jumlah_x,
			'nilai_persediaan' => $TOTAL_X,
		);

		$this->Persediaan_model->update($get_id_persediaan, $Update_data_persediaan);

		// print_r("<br/>");
		// print_r("<br/>");
		// print_r($Update_data_persediaan);
		// print_r("<br/>");
		// print_r("<br/>");
		// die;

		redirect(site_url('Tbl_pembelian/create_add_uraian_update/' . $get_uuid_spop));
	}

	public function create_action_uuid_spop_update($uuid_spop = null)
	{
		// print_r("create_add_uraian");
		// print_r("<br/>");
		// die;

		// print_r($this->input->post('harga_satuan', TRUE));

		// die;

		if ($uuid_spop) {
			// print_r("ada SPOP");
			// die;

			$row_per_uuid_spop = $this->Tbl_pembelian_model->get_by_uuid_spop($uuid_spop);

			// GET KONSUMEN DATA
			$GET_uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
			// $sql_uuid_konsumen = "SELECT * FROM `sys_konsumen` WHERE `uuid_konsumen`='$GET_uuid_konsumen'";

			$sql_uuid_konsumen = "SELECT * FROM `sys_unit` WHERE `uuid_unit`='$GET_uuid_konsumen'";
			$get_kode_konsumen = $this->db->query($sql_uuid_konsumen)->row()->kode_unit;
			// $get_nama_konsumen = $this->db->query($sql_uuid_konsumen)->row()->nama_unit;

			// print_r($sql_uuid_konsumen);
			// die;

			$get_nama_konsumen = $this->db->query($sql_uuid_konsumen)->row()->nama_unit;


			// GET BARANG DATA
			$GET_uuid_barang = $this->input->post('uuid_barang', TRUE);
			$sql_uuid_barang = "SELECT * FROM `sys_nama_barang` WHERE `uuid_barang`='$GET_uuid_barang'";
			$get_kode_barang = $this->db->query($sql_uuid_barang)->row()->kode_barang;
			$get_nama_barang = $this->db->query($sql_uuid_barang)->row()->nama_barang;

			$jumlah_x = preg_replace("/[^0-9]/", "", $this->input->post('jumlah', TRUE));

			$harga_satuan_tanpa_titik = str_replace('.', '', $this->input->post('harga_satuan', TRUE)); // menghilangkan titik
			// print_r("harga_satuan_tanpa_titik: ");
			// print_r($harga_satuan_tanpa_titik);
			// print_r("<br/>");

			$harga_satuan_x = str_replace(',', '.', $harga_satuan_tanpa_titik); // mengganti koma dengan titik
			// Masukkan data ke database

			// print_r("harga_satuan_x: ");
			// print_r($harga_satuan_x);
			// print_r("<br/>");


			$TOTAL_X = $jumlah_x * $harga_satuan_x;


			// print_r("TOTAL_X: ");
			// print_r($TOTAL_X);
			// print_r("<br/>");
			// die;

			// GET GUDANG DATA
			$GET_uuid_gudang = $this->input->post('uuid_gudang', TRUE);
			$sql_uuid_gudang = "SELECT * FROM `sys_gudang` WHERE `uuid_gudang`='$GET_uuid_gudang'";
			$get_kode_gudang = $this->db->query($sql_uuid_gudang)->row()->kode_gudang;
			$get_nama_gudang = $this->db->query($sql_uuid_gudang)->row()->nama_gudang;



			$data = array(
				'date_input' => $row_per_uuid_spop->date_input,

				'tgl_po' => $row_per_uuid_spop->tgl_po,

				// 'nmrsj' => $this->input->post('nmrsj', TRUE),

				'nmrfakturkwitansi' => $row_per_uuid_spop->nmrfakturkwitansi,

				// 'nmrbpb' => $this->input->post('nmrbpb', TRUE),

				'uuid_spop' => $row_per_uuid_spop->uuid_spop,
				'spop' => $row_per_uuid_spop->spop,

				// 'supplier_kode' => $get_kode_supplier,
				'uuid_supplier' => $row_per_uuid_spop->uuid_supplier,
				'supplier_nama' => $row_per_uuid_spop->supplier_nama,


				'statuslu' => $row_per_uuid_spop->statuslu,
				'kas_bank' => $row_per_uuid_spop->kas_bank,


				'uuid_barang' => $this->input->post('uuid_barang', TRUE),
				'kode_barang' => $get_kode_barang,
				'uraian' => $get_nama_barang,

				'jumlah' => $jumlah_x,
				'satuan' => $this->input->post('satuan', TRUE),
				'harga_satuan' => $harga_satuan_x,

				'uuid_konsumen' => $this->input->post('uuid_konsumen', TRUE),
				'konsumen' => $get_nama_konsumen,

				'harga_total' => $TOTAL_X,
				'uuid_gudang' => $this->input->post('uuid_gudang', TRUE),
				'nama_gudang' => $get_nama_gudang,
				'id_usr' => 1,
			);
			// print_r($data);
			// die;

			$this->Tbl_pembelian_model->insert($data); // insert untuk data lanjutan uuid_spop sudah ada
			$get_uuid_spop_generating = $uuid_spop;
		} else {

			// print_r("Tidak ada SPOP");
			// print_r("<br/>");
			// print_r($this->input->post('uuid_barang', TRUE));
			// print_r("<br/>");
			// print_r($this->input->post('uuid_supplier', TRUE));
			// print_r("<br/>");
			// // Generate uuid_spop
			// die;

			// DATE PO

			if (date("Y", strtotime($this->input->post('tgl_po', TRUE))) < 2020) {
				// print_r("Tahun kurang dari 2020");
				$date_po = date("Y-m-d H:i:s");
			} else {
				// print_r("Tahun lebih dari 2020");
				$date_po = date("Y-m-d H:i:s", strtotime($this->input->post('tgl_po', TRUE)));
			}

			// GET SUPPLIER DATA
			$get_uuid_supplier = $this->input->post('uuid_supplier', TRUE);
			$sql_uuid_supplier = "SELECT * FROM `sys_supplier` WHERE `uuid_supplier`='$get_uuid_supplier'";
			$get_kode_supplier = $this->db->query($sql_uuid_supplier)->row()->kode_supplier;
			$get_nama_supplier = $this->db->query($sql_uuid_supplier)->row()->nama_supplier;

			// GET KONSUMEN DATA
			$GET_uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
			// $sql_uuid_konsumen = "SELECT * FROM `sys_konsumen` WHERE `uuid_konsumen`='$GET_uuid_konsumen'";

			$sql_uuid_konsumen = "SELECT * FROM `sys_unit` WHERE `uuid_unit`='$GET_uuid_konsumen'";
			$get_kode_konsumen = $this->db->query($sql_uuid_konsumen)->row()->kode_unit;
			$get_nama_konsumen = $this->db->query($sql_uuid_konsumen)->row()->nama_unit;

			// $get_kode_konsumen = $this->db->query($sql_uuid_konsumen)->row()->kode_konsumen;
			// $get_nama_konsumen = $this->db->query($sql_uuid_konsumen)->row()->nama_konsumen;


			// GET BARANG DATA
			$GET_uuid_barang = $this->input->post('uuid_barang', TRUE);
			$sql_uuid_barang = "SELECT * FROM `sys_nama_barang` WHERE `uuid_barang`='$GET_uuid_barang'";
			$get_kode_barang = $this->db->query($sql_uuid_barang)->row()->kode_barang;
			$get_nama_barang = $this->db->query($sql_uuid_barang)->row()->nama_barang;

			// print_r($GET_uuid_barang);
			// print_r("<br/>");
			$jumlah_x = preg_replace("/[^0-9]/", "", $this->input->post('jumlah', TRUE));

			// $harga_satuan_x = preg_replace("/[^0-9]/", "", $this->input->post('harga_satuan', TRUE));

			$harga_satuan_tanpa_titik = str_replace('.', '', $this->input->post('harga_satuan', TRUE)); // menghilangkan titik
			// print_r("harga_satuan_tanpa_titik: ");
			// print_r($harga_satuan_tanpa_titik);
			// print_r("<br/>");

			$harga_satuan_x = str_replace(',', '.', $harga_satuan_tanpa_titik); // mengganti koma dengan titik
			// Masukkan data ke database

			// print_r("harga_satuan_x: ");
			// print_r($harga_satuan_x);
			// print_r("<br/>");


			$TOTAL_X = $jumlah_x * $harga_satuan_x;


			// print_r("TOTAL_X: ");
			// print_r($TOTAL_X);
			// print_r("<br/>");
			// die;
			// GET GUDANG DATA
			$GET_uuid_gudang = $this->input->post('uuid_gudang', TRUE);
			$sql_uuid_gudang = "SELECT * FROM `sys_gudang` WHERE `uuid_gudang`='$GET_uuid_gudang'";
			$get_kode_gudang = $this->db->query($sql_uuid_gudang)->row()->kode_gudang;
			$get_nama_gudang = $this->db->query($sql_uuid_gudang)->row()->nama_gudang;



			$data = array(
				'date_input' => date("Y-m-d H:i:s"),

				'tgl_po' => $date_po,

				// 'nmrsj' => $this->input->post('nmrsj', TRUE),

				'nmrfakturkwitansi' => $this->input->post('nmrfakturkwitansi', TRUE),

				// 'nmrbpb' => $this->input->post('nmrbpb', TRUE),

				'spop' => $this->input->post('spop', TRUE),

				// 'supplier_kode' => $get_kode_supplier,
				'uuid_supplier' => $this->input->post('uuid_supplier', TRUE),
				'supplier_nama' => $get_nama_supplier,

				'nmrfakturkwitansi' => $this->input->post('nmrfakturkwitansi', TRUE),

				'uuid_barang' => $this->input->post('uuid_barang', TRUE),
				'kode_barang' => $get_kode_barang,
				'uraian' => $get_nama_barang,

				'jumlah' => $this->input->post('jumlah', TRUE),
				'satuan' => $this->input->post('satuan', TRUE),
				'harga_satuan' => $harga_satuan_x,

				'uuid_konsumen' => $this->input->post('uuid_konsumen', TRUE),
				'konsumen' => $get_nama_konsumen,

				'harga_total' => $TOTAL_X,
				'statuslu' => $this->input->post('statuslu', TRUE),
				'kas_bank' => $this->input->post('kas_bank', TRUE),
				'uuid_gudang' => $this->input->post('uuid_gudang', TRUE),
				'nama_gudang' => $get_nama_gudang,
				'id_usr' => 1,
			);


			$get_uuid_spop_generating = $this->Tbl_pembelian_model->insert_spop($data);
			// print_r("<br/>");
			// print_r($get_uuid_spop_generating);
			// print_r("<br/>");
			// // print_r("Selesai");
			// print_r("Selesai");
		}


		redirect(site_url('tbl_pembelian/create_add_uraian/' . $get_uuid_spop_generating));
	}

	public function create_add_uraian($uuid_spop = null)
	{

		$row_per_uuid_spop = $this->Tbl_pembelian_model->get_by_uuid_spop($uuid_spop);
		$RESULT_per_uuid_spop = $this->Tbl_pembelian_model->get_by_uuid_spop_ALL_result($uuid_spop);

		// print_r($RESULT_per_uuid_spop);

		// $jumlah_x = preg_replace("/[^0-9]/", "", $this->input->post('jumlah', TRUE));
		// $harga_satuan_x = preg_replace("/[^0-9]/", "", $this->input->post('harga_satuan', TRUE));
		$TOTAL_X = $row_per_uuid_spop->jumlah * $row_per_uuid_spop->harga_satuan;
		// print_r($row_per_uuid_spop->statuslu);
		if ($row_per_uuid_spop->statuslu == "u") {
			$get_statuslu = $row_per_uuid_spop->statuslu;
			$get_kas_bank = "";
		} else {
			$get_statuslu = $row_per_uuid_spop->statuslu;
			$get_kas_bank = $row_per_uuid_spop->kas_bank;
		}
		$data = array(
			'data_ALL_per_SPOP' => $RESULT_per_uuid_spop,
			'button' => 'Simpan',
			'action' => site_url('tbl_pembelian/create_action_uuid_spop/' . $uuid_spop),
			'id' => set_value('id'),
			'tgl_po' => $row_per_uuid_spop->tgl_po,
			// 'nmrsj' => $row_per_uuid_spop->nmrsj,
			'nmrfakturkwitansi' => $row_per_uuid_spop->nmrfakturkwitansi,
			// 'nmrbpb' => $row_per_uuid_spop->nmrbpb,
			'uuid_spop' => $row_per_uuid_spop->uuid_spop,
			'spop' => $row_per_uuid_spop->spop,

			'uuid_supplier' => $row_per_uuid_spop->uuid_supplier,
			'supplier_kode' => $row_per_uuid_spop->supplier_kode,
			'supplier_nama' => $row_per_uuid_spop->supplier_nama,

			// 'uraian' => $row_per_uuid_spop->uraian,
			// 'jumlah' => $row_per_uuid_spop->jumlah,
			// 'satuan' => $row_per_uuid_spop->satuan,
			'uuid_konsumen' => $row_per_uuid_spop->uuid_konsumen,
			'konsumen' => $row_per_uuid_spop->konsumen,
			// 'harga_satuan' => $row_per_uuid_spop->harga_satuan,
			'harga_total' => $TOTAL_X,
			'statuslu' => $get_statuslu,
			'kas_bank' => $get_kas_bank,
			// 'tgl_bayar' => $row_per_uuid_spop->tgl_bayar,
			// 'id_usr' => $row_per_uuid_spop->,
			'action_ubah_detail_spop' => site_url('tbl_pembelian/create_action_detail_uuid_spop_update/' . $uuid_spop),
			'action_ubah_per_id' => site_url('tbl_pembelian/create_action_uuid_spop_update_per_id/'),
			'action_tambah_barang_per_spop' => site_url('tbl_pembelian/create_action_tambah_barang_per_spop/'),
		);

		// print_r($data);
		// die;

		// $this->load->view('anekadharma/tbl_pembelian/tbl_pembelian_form', $data);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_form_spop_ready', $data);
	}

	public function create_action_uuid_spop($uuid_spop = null)
	{
		// print_r("create_action_uuid_spop");
		// print_r("<br/>");
		// die;

		// print_r($this->input->post('harga_satuan', TRUE));

		// die;




		// TABEL PEMBELIAN

		if ($uuid_spop) {
			// print_r("ada SPOP");
			// die;

			$row_per_uuid_spop = $this->Tbl_pembelian_model->get_by_uuid_spop($uuid_spop);

			// GET KONSUMEN DATA
			$GET_uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
			// $sql_uuid_konsumen = "SELECT * FROM `sys_konsumen` WHERE `uuid_konsumen`='$GET_uuid_konsumen'";

			$sql_uuid_konsumen = "SELECT * FROM `sys_unit` WHERE `uuid_unit`='$GET_uuid_konsumen'";
			$get_kode_konsumen = $this->db->query($sql_uuid_konsumen)->row()->kode_unit;
			// $get_nama_konsumen = $this->db->query($sql_uuid_konsumen)->row()->nama_unit;

			// print_r($sql_uuid_konsumen);
			// die;

			$get_nama_konsumen = $this->db->query($sql_uuid_konsumen)->row()->nama_unit;


			// GET BARANG DATA
			$GET_uuid_barang = $this->input->post('uuid_barang', TRUE);
			$sql_uuid_barang = "SELECT * FROM `sys_nama_barang` WHERE `uuid_barang`='$GET_uuid_barang'";
			$get_kode_barang = $this->db->query($sql_uuid_barang)->row()->kode_barang;
			$get_nama_barang = $this->db->query($sql_uuid_barang)->row()->nama_barang;

			$jumlah_x = preg_replace("/[^0-9]/", "", $this->input->post('jumlah', TRUE));

			$harga_satuan_tanpa_titik = str_replace('.', '', $this->input->post('harga_satuan', TRUE)); // menghilangkan titik
			// print_r("harga_satuan_tanpa_titik: ");
			// print_r($harga_satuan_tanpa_titik);
			// print_r("<br/>");

			$harga_satuan_x = str_replace(',', '.', $harga_satuan_tanpa_titik); // mengganti koma dengan titik
			// Masukkan data ke database

			// print_r("harga_satuan_x: ");
			// print_r($harga_satuan_x);
			// print_r("<br/>");


			$TOTAL_X = $jumlah_x * $harga_satuan_x;


			// print_r("TOTAL_X: ");
			// print_r($TOTAL_X);
			// print_r("<br/>");
			// die;

			// GET GUDANG DATA
			$GET_uuid_gudang = $this->input->post('uuid_gudang', TRUE);
			$sql_uuid_gudang = "SELECT * FROM `sys_gudang` WHERE `uuid_gudang`='$GET_uuid_gudang'";
			$get_kode_gudang = $this->db->query($sql_uuid_gudang)->row()->kode_gudang;
			$get_nama_gudang = $this->db->query($sql_uuid_gudang)->row()->nama_gudang;



			$Get_tanggal_beli = $row_per_uuid_spop->tgl_po;
			$Get_uuid_spop = $row_per_uuid_spop->uuid_spop;
			$Get_spop = $row_per_uuid_spop->spop;
			$Get_Status_LU = $row_per_uuid_spop->statuslu;
			$Get_Kas_BANK = $row_per_uuid_spop->kas_bank;
			$Get_date_po = $row_per_uuid_spop->tgl_po;
			$Get_nominal = $jumlah_x * $harga_satuan_x;


			$data = array(
				'date_input' => $row_per_uuid_spop->date_input,

				'tgl_po' => $row_per_uuid_spop->tgl_po,

				// 'nmrsj' => $this->input->post('nmrsj', TRUE),

				'nmrfakturkwitansi' => $row_per_uuid_spop->nmrfakturkwitansi,

				// 'nmrbpb' => $this->input->post('nmrbpb', TRUE),

				'uuid_spop' => $row_per_uuid_spop->uuid_spop,
				'spop' => $row_per_uuid_spop->spop,

				// 'supplier_kode' => $get_kode_supplier,
				'uuid_supplier' => $row_per_uuid_spop->uuid_supplier,
				'supplier_nama' => $row_per_uuid_spop->supplier_nama,

				'statuslu' => $row_per_uuid_spop->statuslu,
				'kas_bank' => $row_per_uuid_spop->kas_bank,

				'uuid_barang' => $this->input->post('uuid_barang', TRUE),
				'kode_barang' => $get_kode_barang,
				'uraian' => $get_nama_barang,

				'jumlah' => $jumlah_x,
				'satuan' => $this->input->post('satuan', TRUE),
				'harga_satuan' => $harga_satuan_x,

				'uuid_konsumen' => $this->input->post('uuid_konsumen', TRUE),
				'konsumen' => $get_nama_konsumen,

				'harga_total' => $TOTAL_X,
				'uuid_gudang' => $this->input->post('uuid_gudang', TRUE),
				'nama_gudang' => $get_nama_gudang,
				'id_usr' => 1,
			);
			// print_r($data);
			// die;

			$GET_ID_PEMBELIAN_AFTER_INSERT = $this->Tbl_pembelian_model->insert($data); // insert untuk data lanjutan uuid_spop sudah ada
			$get_uuid_spop_generating = $uuid_spop;

			// print_r("Ada SPOP");
			// print_r("<br/>");
			// print_r($GET_ID_PEMBELIAN_AFTER_INSERT);
			// print_r("<br/>");
			// print_r($uuid_spop);
			// print_r("<br/>");
			// print_r($get_uuid_spop_generating);
			// print_r("<br/>");
		} else {

			// print_r("Tidak ada SPOP");
			// print_r("<br/>");
			// print_r($this->input->post('uuid_barang', TRUE));
			// print_r("<br/>");
			// print_r($this->input->post('uuid_supplier', TRUE));
			// print_r("<br/>");
			// // Generate uuid_spop
			// die;

			// DATE PO

			if (date("Y", strtotime($this->input->post('tgl_po', TRUE))) < 2020) {
				// print_r("Tahun kurang dari 2020");
				$date_po = date("Y-m-d H:i:s");
			} else {
				// print_r("Tahun lebih dari 2020");
				$date_po = date("Y-m-d H:i:s", strtotime($this->input->post('tgl_po', TRUE)));
			}

			// GET SUPPLIER DATA
			$get_uuid_supplier = $this->input->post('uuid_supplier', TRUE);
			$sql_uuid_supplier = "SELECT * FROM `sys_supplier` WHERE `uuid_supplier`='$get_uuid_supplier'";
			$get_kode_supplier = $this->db->query($sql_uuid_supplier)->row()->kode_supplier;
			$get_nama_supplier = $this->db->query($sql_uuid_supplier)->row()->nama_supplier;

			// GET KONSUMEN DATA
			$GET_uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
			// $sql_uuid_konsumen = "SELECT * FROM `sys_konsumen` WHERE `uuid_konsumen`='$GET_uuid_konsumen'";

			$sql_uuid_konsumen = "SELECT * FROM `sys_unit` WHERE `uuid_unit`='$GET_uuid_konsumen'";
			$get_kode_konsumen = $this->db->query($sql_uuid_konsumen)->row()->kode_unit;
			$get_nama_konsumen = $this->db->query($sql_uuid_konsumen)->row()->nama_unit;

			// $get_kode_konsumen = $this->db->query($sql_uuid_konsumen)->row()->kode_konsumen;
			// $get_nama_konsumen = $this->db->query($sql_uuid_konsumen)->row()->nama_konsumen;


			// GET BARANG DATA
			$GET_uuid_barang = $this->input->post('uuid_barang', TRUE);
			$sql_uuid_barang = "SELECT * FROM `sys_nama_barang` WHERE `uuid_barang`='$GET_uuid_barang'";
			$get_kode_barang = $this->db->query($sql_uuid_barang)->row()->kode_barang;
			$get_nama_barang = $this->db->query($sql_uuid_barang)->row()->nama_barang;

			// print_r($GET_uuid_barang);
			// print_r("<br/>");
			$jumlah_x = preg_replace("/[^0-9]/", "", $this->input->post('jumlah', TRUE));

			// $harga_satuan_x = preg_replace("/[^0-9]/", "", $this->input->post('harga_satuan', TRUE));

			$harga_satuan_tanpa_titik = str_replace('.', '', $this->input->post('harga_satuan', TRUE)); // menghilangkan titik
			// print_r("harga_satuan_tanpa_titik: ");
			// print_r($harga_satuan_tanpa_titik);
			// print_r("<br/>");

			$harga_satuan_x = str_replace(',', '.', $harga_satuan_tanpa_titik); // mengganti koma dengan titik
			// Masukkan data ke database

			// print_r("harga_satuan_x: ");
			// print_r($harga_satuan_x);
			// print_r("<br/>");


			$TOTAL_X = $jumlah_x * $harga_satuan_x;


			// print_r("TOTAL_X: ");
			// print_r($TOTAL_X);
			// print_r("<br/>");
			// die;
			// GET GUDANG DATA
			$GET_uuid_gudang = $this->input->post('uuid_gudang', TRUE);
			$sql_uuid_gudang = "SELECT * FROM `sys_gudang` WHERE `uuid_gudang`='$GET_uuid_gudang'";
			$get_kode_gudang = $this->db->query($sql_uuid_gudang)->row()->kode_gudang;
			$get_nama_gudang = $this->db->query($sql_uuid_gudang)->row()->nama_gudang;


			$data = array(
				'date_input' => date("Y-m-d H:i:s"),

				'tgl_po' => $date_po,

				// 'nmrsj' => $this->input->post('nmrsj', TRUE),

				'nmrfakturkwitansi' => $this->input->post('nmrfakturkwitansi', TRUE),

				// 'nmrbpb' => $this->input->post('nmrbpb', TRUE),

				'spop' => $this->input->post('spop', TRUE),

				// 'supplier_kode' => $get_kode_supplier,
				'uuid_supplier' => $this->input->post('uuid_supplier', TRUE),
				'supplier_nama' => $get_nama_supplier,

				'nmrfakturkwitansi' => $this->input->post('nmrfakturkwitansi', TRUE),

				'uuid_barang' => $this->input->post('uuid_barang', TRUE),
				'kode_barang' => $get_kode_barang,
				'uraian' => $get_nama_barang,

				'jumlah' => $this->input->post('jumlah', TRUE),
				'satuan' => $this->input->post('satuan', TRUE),
				'harga_satuan' => $harga_satuan_x,

				'uuid_konsumen' => $this->input->post('uuid_konsumen', TRUE),
				'konsumen' => $get_nama_konsumen,

				'harga_total' => $TOTAL_X,
				'statuslu' => $this->input->post('statuslu', TRUE),
				'kas_bank' => $this->input->post('kas_bank', TRUE),
				'uuid_gudang' => $this->input->post('uuid_gudang', TRUE),
				'nama_gudang' => $get_nama_gudang,
				'id_usr' => 1,
			);


			$GET_ID_PEMBELIAN_AFTER_INSERT = $this->Tbl_pembelian_model->insert_spop($data);
			// $GET_ID_PEMBELIAN_AFTER_INSERT = $this->Tbl_pembelian_model->insert($data);

			// print_r("TIDAK Ada SPOP");
			// print_r("<br/>");
			// print_r($GET_ID_PEMBELIAN_AFTER_INSERT);
			// print_r("<br/>");

			// $this->db->where($this->id, $GET_ID_PEMBELIAN_AFTER_INSERT);
			// $get_uuid_spop_generating = $this->db->get($this->table)->row()->uuid_spop;

			$this->db->where('id', $GET_ID_PEMBELIAN_AFTER_INSERT);
			$GET_DATA_PEMBELIAN = $this->db->get('tbl_pembelian');
			$get_uuid_spop_generating = $GET_DATA_PEMBELIAN->row()->uuid_spop;


			// print_r($get_uuid_spop_generating);
			// print_r("<br/>");
			// die;

			// $this->db->where('id', $GET_ID_PEMBELIAN_AFTER_INSERT);
			// $GET_DATA_PEMBELIAN = $this->db->get('tbl_pembelian');
			// $get_uuid_spop_generating = $GET_DATA_PEMBELIAN->row()->uuid_spop;


			// print_r("<br/>");
			// print_r($get_uuid_spop_generating);
			// print_r("<br/>");
			// // print_r("Selesai");
			// print_r("Selesai");

			$Get_tanggal_beli = $date_po;
			$Get_uuid_spop = $get_uuid_spop_generating;
			$Get_spop = $this->input->post('spop', TRUE);
			$Get_Status_LU = $this->input->post('statuslu', TRUE);
			$Get_Kas_BANK = $this->input->post('kas_bank', TRUE);
			$Get_date_po = $date_po;
			$Get_nominal = $this->input->post('jumlah', TRUE) * $harga_satuan_x;
		}

		// die;
		// PERSEDIAAN
		// Simpan Barang ke persediaan ==> sebagai spop baru dan otomatis uuid_persediaan baru

		$Get_nominal_persediaan = $this->input->post('jumlah', TRUE) * $harga_satuan_x;
		$data_persediaan = array(
			// 'id' => $this->input->post('id', TRUE),
			'tanggal' => date("Y-m-d H:i:s"),
			'tanggal_beli' => $Get_tanggal_beli,
			// 'kode' => $this->input->post('kode', TRUE),
			'uuid_barang' => $this->input->post('uuid_barang', TRUE),
			'namabarang' => $get_nama_barang,
			'satuan' => $this->input->post('satuan', TRUE),
			'hpp' => $harga_satuan_x,
			'sa' => $this->input->post('jumlah', TRUE),
			'uuid_spop' => $Get_uuid_spop,
			// 'uuid_spop' => $get_uuid_spop_generating,
			'spop' => $Get_spop,
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
			'total_10' => $this->input->post('jumlah', TRUE),
			'nilai_persediaan' => $Get_nominal_persediaan,
		);

		// $this->Persediaan_model->insert($data_persediaan);
		$GET_ID_PERSEDIAAN = $this->Persediaan_model->insert_produk_baru($data_persediaan);

		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("DATA PERSEDIAAN");
		// print_r("<br/>");
		// print_r($GET_ID_PERSEDIAAN);
		// print_r("<br/>");

		// UPDATE TBL_PEMBELIAN : ID_PERSEDIAAN DAN UUID_PERSEDIAAN DARI TABEL PERSEDIAAN DENGAN FILTER UUID_SPOP DARI TABEL PERSEDIAAN

		$this->db->where('id', $GET_ID_PERSEDIAAN);
		$GET_DATA_PERSEDIAAN = $this->db->get('persediaan');
		$GET_uuid_persediaan = $GET_DATA_PERSEDIAAN->row()->uuid_persediaan;

		// print_r($GET_uuid_persediaan);
		// print_r("<br/>");

		// UPDATE TBL_PEMBELIAN

		$sql_update_tbl_pembelian = "UPDATE `tbl_pembelian` SET `uuid_persediaan`='$GET_uuid_persediaan',`id_persediaan_barang`='$GET_ID_PERSEDIAAN' WHERE `id`='$GET_ID_PEMBELIAN_AFTER_INSERT'";

		$this->db->query($sql_update_tbl_pembelian);

		// $this->db->set(array(
		// 	'id_persediaan_barang' => $GET_ID_PERSEDIAAN,
		// 	'uuid_persediaan' => $GET_uuid_persediaan,
		// ));  // pass fields in array
		// $this->db->where('id', $GET_ID_PEMBELIAN_AFTER_INSERT);
		// $this->db->update('tbl_pembelian'); // table name

		// END OF UPDATE TBL_PEMBELIAN : ID_PERSEDIAAN DAN UUID_PERSEDIAAN DARI TABEL PERSEDIAAN DENGAN FILTER UUID_SPOP DARI TABEL PERSEDIAAN

		// die;

		// TABEL KAS KECIL
		// JIKA STATUSLU = L & PILIHAN KAS ==> MAKA OTOMATIS MASUK DATA PENGELUARAN KAS KECIL

		if ($Get_Status_LU == "L" and $Get_Kas_BANK == "kas") {
			// echo "L kas";

			// Cek data spop di kas kecil , apakah sudah ada? , jika belum maka insert data pengeluaran keas kecil dan apabila sudah ada maka update data dan menjumlahkan nominal

			$this->db->where('uuid_spop', $Get_uuid_spop);
			//$this->db->where('password',  $test);
			$Get_data_kas_Kecil = $this->db->get('tbl_kas_kecil');

			if ($Get_data_kas_Kecil->num_rows() > 0) {
				// Ada data spop di kas kecil ==> dapatkan nominal dan jumlahkan kemudian update berdasarkan spop
				// print_r("Nominal Kredit");
				// print_r("<br/>");

				$RowArray_data_kas_Kecil = $Get_data_kas_Kecil->row_array();
				$get_id = $RowArray_data_kas_Kecil['id'];

				// print_r($RowArray_data_kas_Kecil['kredit']);
				// print_r("<br/>");
				// print_r($RowArray_data_kas_Kecil['id']);

				$data = array(
					// 'uuid_kas_kecil' => $this->input->post('uuid_kas_kecil', TRUE),
					'date_input' => date("Y-m-d H:i:s"),
					// 'tanggal' => $date_kas_kecil,
					// 'uuid_spop' => $get_spop,
					// 'uuid_unit' => $this->input->post('unit', TRUE),
					// 'unit' => $row_unit->nama_unit,
					// 'keterangan' => $this->input->post('keterangan', TRUE),
					// 'status_data' => "pengeluaran",

					// 'debet' => preg_replace("/[^0-9]/", "", $this->input->post('debet', TRUE)),

					// 'kredit' => preg_replace("/[^0-9]/", "", $this->input->post('kredit', TRUE)),
					'kredit' => $RowArray_data_kas_Kecil['kredit'] + str_replace(",", ".", str_replace(".", "", $Get_nominal)),


					// 'saldo' => $this->input->post('saldo', TRUE),
					// 'id_usr' => $this->input->post('id_usr', TRUE),
				);
				$this->Tbl_kas_kecil_model->update($get_id, $data);
			} else {
				// Belum ada data spop ==> lanjutkan dengan insert data pengeluaran baru berdasarkan uuid_spop


				// $row_unit = $this->Sys_unit_model->get_by_uuid_unit($this->input->post('unit', TRUE));


				if (date("Y", strtotime($Get_date_po)) < 2020) {
					$date_proses = date("d-m-Y");
				} else {
					$date_proses = date("d-m-Y", strtotime($Get_date_po));
				}

				// print_r($date_kas_kecil);
				// die;

				$data_pengeluaran_kas_kecil = array(
					// 'uuid_kas_kecil' => $this->input->post('uuid_kas_kecil', TRUE),
					'date_input' => date("Y-m-d H:i:s"),
					'tanggal' => $Get_date_po,

					'uuid_spop' => $Get_uuid_spop,
					'uuid_unit' => $GET_uuid_konsumen,
					'unit' => $get_nama_konsumen,

					'keterangan' => "Pembayaran SPOP No " . $Get_spop  . " tgl " . $date_proses,
					'status_data' => "pengeluaran",

					// 'debet' => preg_replace("/[^0-9]/", "", $this->input->post('debet', TRUE)),
					// 'debet' => str_replace(".", "", $this->input->post('debet', TRUE)),
					'kredit' => str_replace(",", ".", str_replace(".", "", $Get_nominal)),


					// 'kredit' => preg_replace("/[^0-9]/", "", $this->input->post('kredit', TRUE)),
					// 'saldo' => $this->input->post('saldo', TRUE),
					// 'id_usr' => $this->input->post('id_usr', TRUE),
				);



				$this->Tbl_kas_kecil_model->insert($data_pengeluaran_kas_kecil);

				// print_r($data_pengeluaran_kas_kecil);
			}
		}

		// die;


		redirect(site_url('tbl_pembelian/create_add_uraian/' . $get_uuid_spop_generating));
	}

	public function simpan_data_spop($uuid_spop = null)
	{

		// 1. UPDATE DATA
		$data = array(
			'proses_input' => 1,
		);

		$this->Tbl_pembelian_model->update_proses_per_spop($uuid_spop, $data);

		// redirect("https://google.com", ['target' => '_blank']);
		redirect(site_url('tbl_pembelian'));

		// die;

		// 2. proses cetak form , di tab baru

		// echo "
		// <script>
		// 	window.open('https://duckduckgo.com/?t=ffab', '_blank');
		// </script>
		// ";

	}


	public function cetak_belanja_per_spop($uuid_spop = null)
	{

		// 2.a. PERSIAPAN LIBRARY
		$this->load->library('PdfGenerator');

		// 2.b. PERSIAPAN DATA
		$data = array(
			'data_belanja' => $this->Tbl_pembelian_model->get_by_spop($uuid_spop, $data),
		);

		// 2.C. MENAMPILKAN FILE DATA
		// $data = array_merge($data);
		$html = $this->load->view('anekadharma/tbl_pembelian/adminlte310_cetak_pembelian.php', $data, true);

		// 2.d. CONVERT TAMPILAN FILE DATA MENJADI FILE PDF
		$this->pdf->loadHtml($html);
		$this->pdf->render();

		$this->pdf->stream("NOTA_PENJUALAN.pdf", array("Attachment" => 0));
	}

	public function update($id)
	{
		$row = $this->Tbl_pembelian_model->get_by_id($id);

		if ($row) {
			$data = array(
				'button' => 'Update',
				'action' => site_url('tbl_pembelian/update_action'),
				'id' => set_value('id', $row->id),
				'tgl_po' => set_value('tgl_po', $row->tgl_po),
				'nmrsj' => set_value('nmrsj', $row->nmrsj),
				'nmrfakturkwitansi' => set_value('nmrfakturkwitansi', $row->nmrfakturkwitansi),
				'nmrbpb' => set_value('nmrbpb', $row->nmrbpb),
				'spop' => set_value('spop', $row->spop),
				'supplier_kode' => set_value('supplier_kode', $row->supplier_kode),
				'supplier_nama' => set_value('supplier_nama', $row->supplier_nama),
				'uraian' => set_value('uraian', $row->uraian),
				'jumlah' => set_value('jumlah', $row->jumlah),
				'satuan' => set_value('satuan', $row->satuan),
				'konsumen' => set_value('konsumen', $row->konsumen),
				'harga_satuan' => set_value('harga_satuan', $row->harga_satuan),
				'harga_total' => set_value('harga_total', $row->harga_total),
				'statuslu' => set_value('statuslu', $row->statuslu),
				'kas_bank' => set_value('kas_bank', $row->kas_bank),
				'tgl_bayar' => set_value('tgl_bayar', $row->tgl_bayar),
				'id_usr' => set_value('id_usr', $row->id_usr),
			);
			$this->load->view('anekadharma/tbl_pembelian/tbl_pembelian_form', $data);
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(site_url('tbl_pembelian'));
		}
	}

	public function update_per_spop($uuid_spop)
	{


		$data_per_uuidspop = $this->Tbl_pembelian_model->get_by_uuid_spop($uuid_spop);
		// print_r($data_per_uuidspop->spop);
		// die;

		$Tbl_pembelian = $this->Tbl_pembelian_model->get_by_spop($data_per_uuidspop->spop);
		$start = 0;
		$data = array(

			'Tbl_pembelian_data' => $Tbl_pembelian,
			'start' => $start,
		);

		// print_r($data);
		// 		print_r("<br/>");
		// 		print_r("<br/>");
		// die;

		// $this->template->load('anekadharma/adminlte310_anekadharma', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_list_per_spop', $data);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_list_per_spop', $data);
	}


	public function update_per_id($uuid_pembelian)
	{


		// $data_per_uuidspop = $this->Tbl_pembelian_model->get_by_id($uuid_spop);
		// print_r($data_per_uuidspop->spop);
		// die;

		// print_r($uuid_pembelian);
		// print_r("<br/>");

		$get_id_pembelian = $this->Tbl_pembelian_model->get_by_uuid_pembelian($uuid_pembelian);
		// print_r($get_id_pembelian->id);
		// print_r("<br/>");

		$Tbl_pembelian = $this->Tbl_pembelian_model->get_by_id($get_id_pembelian->id);
		// print_r($Tbl_pembelian);
		// print_r("<br/>");

		$start = 0;
		$data = array(
			'button' => 'Update',
			'action' => site_url('tbl_pembelian/update_action'),
			'Tbl_pembelian_data' => $Tbl_pembelian,
			'start' => $start,
			'id' => $get_id_pembelian->id,
			'tgl_po' => date($Tbl_pembelian->tgl_po),
			'uuid_supplier' => $Tbl_pembelian->uuid_supplier,
			'supplier_nama' => $Tbl_pembelian->supplier_nama,
			'statuslu' => $Tbl_pembelian->statuslu,
			'kas_bank' => $Tbl_pembelian->kas_bank,
			'spop' => $Tbl_pembelian->spop,
			'nmrfakturkwitansi' => $Tbl_pembelian->nmrfakturkwitansi,
			'uuid_gudang' => $Tbl_pembelian->uuid_gudang,
			'nama_gudang' => $Tbl_pembelian->nama_gudang,

			'uraian' => $Tbl_pembelian->uraian,
			'uuid_barang' => $Tbl_pembelian->uuid_barang,
			'kode_barang' => $Tbl_pembelian->kode_barang,

			'jumlah' => $Tbl_pembelian->jumlah,
			'satuan' => $Tbl_pembelian->satuan,
			'harga_satuan' => $Tbl_pembelian->harga_satuan,

		);
		// print_r($data);



		// $this->template->load('anekadharma/adminlte310_anekadharma', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_list_per_spop', $data);

		// $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_list_per_spop', $data);

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_form_per_id', $data);
	}



	public function update_action()
	{

		// print_r($this->input->post('id', TRUE));
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");

		$this->_rules();

		if ($this->form_validation->run() == FALSE) {
			$this->update($this->input->post('id', TRUE));
		} else {

			if (date("Y", strtotime($this->input->post('tgl_po', TRUE))) < 2020) {
				// print_r("Tahun kurang dari 2020");
				$date_po = date("Y-m-d H:i:s");
			} else {
				// print_r("Tahun lebih dari 2020");
				$date_po = date("Y-m-d H:i:s", strtotime($this->input->post('tgl_po', TRUE)));
			}


			// GET SUPPLIER DATA
			$get_uuid_supplier = $this->input->post('uuid_supplier', TRUE);
			$sql_uuid_supplier = "SELECT * FROM `sys_supplier` WHERE `uuid_supplier`='$get_uuid_supplier'";
			$get_kode_supplier = $this->db->query($sql_uuid_supplier)->row()->kode_supplier;
			$get_nama_supplier = $this->db->query($sql_uuid_supplier)->row()->nama_supplier;

			// // GET KONSUMEN DATA
			// $GET_uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);

			// $sql_uuid_konsumen = "SELECT * FROM `sys_unit` WHERE `uuid_unit`='$GET_uuid_konsumen'";
			// $get_kode_konsumen = $this->db->query($sql_uuid_konsumen)->row()->kode_unit;
			// $get_nama_konsumen = $this->db->query($sql_uuid_konsumen)->row()->nama_unit;


			// $this->db->where('uuid_konsumen', $this->input->post('uuid_konsumen', TRUE));
			// //$this->db->where('password',  $test);
			// $sys_konsumen_data = $this->db->get('sys_konsumen');

			// if ($sys_konsumen_data->num_rows() > 0) {
			// 	// Konsumen dari sys_konsumen
			// 	$get_uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
			// 	$sql_uuid_konsumen = "SELECT * FROM `sys_konsumen` WHERE `uuid_konsumen`='$get_uuid_konsumen'";
			// 	// $get_kode_konsumen = $this->db->query($sql_uuid_konsumen)->row()->kode_konsumen;
			// 	$get_nama_konsumen = $this->db->query($sql_uuid_konsumen)->row()->nama_konsumen;
			// } else {
			// 	// Konsumen dari unit

			// 	// $uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
			// 	// $data_konsumen = $this->Sys_unit_model->get_by_uuid_unit($uuid_konsumen);
			// 	// $data_nama_konsumen = $data_konsumen->nama_unit;


			// 	$get_uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
			// 	$sql_uuid_konsumen = "SELECT * FROM `sys_unit` WHERE `uuid_unit`='$get_uuid_konsumen'";
			// 	// $get_kode_konsumen = $this->db->query($sql_uuid_konsumen)->row()->kode_konsumen;
			// 	$get_nama_konsumen = $this->db->query($sql_uuid_konsumen)->row()->nama_unit;
			// }

			// print_r($get_nama_konsumen);
			// die;










			// GET BARANG DATA
			$GET_uuid_barang = $this->input->post('uuid_barang', TRUE);
			$sql_uuid_barang = "SELECT * FROM `sys_nama_barang` WHERE `uuid_barang`='$GET_uuid_barang'";
			$get_kode_barang = $this->db->query($sql_uuid_barang)->row()->kode_barang;
			$get_nama_barang = $this->db->query($sql_uuid_barang)->row()->nama_barang;

			// print_r($GET_uuid_barang);
			// print_r("<br/>");
			$jumlah_x = preg_replace("/[^0-9]/", "", $this->input->post('jumlah', TRUE));

			// $harga_satuan_x = preg_replace("/[^0-9]/", "", $this->input->post('harga_satuan', TRUE));
			// $TOTAL_X = $jumlah_x * $harga_satuan_x;

			$harga_satuan_tanpa_titik = str_replace('.', '', $this->input->post('harga_satuan', TRUE)); // menghilangkan titik
			// print_r("harga_satuan_tanpa_titik: ");
			// print_r($harga_satuan_tanpa_titik);
			// print_r("<br/>");

			$harga_satuan_x = str_replace(',', '.', $harga_satuan_tanpa_titik); // mengganti koma dengan titik
			// Masukkan data ke database

			// print_r("harga_satuan_x: ");
			// print_r($harga_satuan_x);
			// print_r("<br/>");


			$TOTAL_X = $jumlah_x * $harga_satuan_x;


			// print_r("TOTAL_X: ");
			// print_r($TOTAL_X);
			// print_r("<br/>");



			// GET GUDANG DATA
			$GET_uuid_gudang = $this->input->post('uuid_gudang', TRUE);
			$sql_uuid_gudang = "SELECT * FROM `sys_gudang` WHERE `uuid_gudang`='$GET_uuid_gudang'";
			$get_kode_gudang = $this->db->query($sql_uuid_gudang)->row()->kode_gudang;
			$get_nama_gudang = $this->db->query($sql_uuid_gudang)->row()->nama_gudang;




			// $data = array(
			// 	'tgl_po' => $this->input->post('tgl_po', TRUE),
			// 	'nmrsj' => $this->input->post('nmrsj', TRUE),
			// 	'nmrfakturkwitansi' => $this->input->post('nmrfakturkwitansi', TRUE),
			// 	'nmrbpb' => $this->input->post('nmrbpb', TRUE),
			// 	'spop' => $this->input->post('spop', TRUE),
			// 	'supplier_kode' => $this->input->post('supplier_kode', TRUE),
			// 	'supplier_nama' => $this->input->post('supplier_nama', TRUE),
			// 	'uraian' => $this->input->post('uraian', TRUE),
			// 	'jumlah' => $this->input->post('jumlah', TRUE),
			// 	'satuan' => $this->input->post('satuan', TRUE),
			// 	'konsumen' => $this->input->post('konsumen', TRUE),
			// 	'harga_satuan' => $this->input->post('harga_satuan', TRUE),
			// 	'harga_total' => $this->input->post('harga_total', TRUE),
			// 	'statuslu' => $this->input->post('statuslu', TRUE),
			// 	'kas_bank' => $this->input->post('kas_bank', TRUE),
			// 	'tgl_bayar' => $this->input->post('tgl_bayar', TRUE),
			// 	'id_usr' => $this->input->post('id_usr', TRUE),
			// );



			$data = array(
				// 'date_input' => date("Y-m-d H:i:s"),

				'tgl_po' => $date_po,

				// 'nmrsj' => $this->input->post('nmrsj', TRUE),

				'nmrfakturkwitansi' => $this->input->post('nmrfakturkwitansi', TRUE),

				// 'nmrbpb' => $this->input->post('nmrbpb', TRUE),

				'spop' => $this->input->post('spop', TRUE),

				// 'supplier_kode' => $get_kode_supplier,
				'uuid_supplier' => $this->input->post('uuid_supplier', TRUE),
				'supplier_nama' => $get_nama_supplier,

				'nmrfakturkwitansi' => $this->input->post('nmrfakturkwitansi', TRUE),

				'uuid_barang' => $this->input->post('uuid_barang', TRUE),
				'kode_barang' => $get_kode_barang,
				'uraian' => $get_nama_barang,

				'jumlah' => $this->input->post('jumlah', TRUE),
				'satuan' => $this->input->post('satuan', TRUE),
				'harga_satuan' => $harga_satuan_x,

				'uuid_konsumen' => $this->input->post('uuid_konsumen', TRUE),
				// 'konsumen' => $get_nama_konsumen,

				'harga_total' => $TOTAL_X,
				'statuslu' => $this->input->post('statuslu', TRUE),
				'kas_bank' => $this->input->post('kas_bank', TRUE),
				'uuid_gudang' => $this->input->post('uuid_gudang', TRUE),
				'nama_gudang' => $get_nama_gudang,
				// 'id_usr' => 1,
			);



			// print_r($data);
			// die;
			$this->Tbl_pembelian_model->update($this->input->post('id', TRUE), $data);
			$this->session->set_flashdata('message', 'Update Record Success');
			redirect(site_url('tbl_pembelian'));
		}
	}


	public function update_status_per_spop($uuid_spop = null)
	{

		$data_per_uuidspop = $this->Tbl_pembelian_model->get_by_uuid_spop($uuid_spop);

		// $Tbl_pembelian = $this->Tbl_pembelian_model->get_by_spop($spop);
		$Tbl_pembelian = $this->Tbl_pembelian_model->get_by_spop($data_per_uuidspop->spop);

		// SELECT `status_spop` FROM `tbl_pembelian` WHERE `uuid_spop`="53d056417ed111ef95300021ccc9061e";



		$start = 0;
		$data = array(
			'Tbl_pembelian_data' => $Tbl_pembelian,
			'spop' => $data_per_uuidspop->spop,
			'action' => site_url('tbl_pembelian/update_status_per_spop_action/' . $uuid_spop),
			'button' => 'Simpan',
			'start' => $start,
		);
		// print_r($data);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_list_per_spop_status', $data);
	}



	public function update_status_per_spop_action($uuid_spop)
	{

		$data_status = $this->Sys_status_transaksi_model->get_by_uuid_status_transaksi($this->input->post('uuid_status_transaksi', TRUE));

		// print_r($data_status->status);

		// print_r("update_status_per_spop_action");
		// print_r("<br/>");
		// print_r($uuid_spop);
		// print_r("<br/>");
		// print_r($this->input->post('uuid_status_transaksi', TRUE));
		// print_r("<br/>");
		// die;

		// $this->_rules();

		// if ($this->form_validation->run() == FALSE) {
		// 	$this->update($this->input->post('id', TRUE));
		// } else {
		$data = array(
			'status_spop' => $data_status->status,
		);

		$this->Tbl_pembelian_model->update_status_per_spop($uuid_spop, $data);
		$this->session->set_flashdata('message', 'Update Record Success');
		redirect(site_url('tbl_pembelian'));
		// }
	}



	public function delete_per_spop($uuid_spop)
	{

		// print_r("delete_per_spop");
		// print_r("<br/>");
		// $row = $this->Tbl_pembelian_model->get_by_id($uuid_spop);
		$data_per_uuidspop = $this->Tbl_pembelian_model->get_by_uuid_spop($uuid_spop);
		// print_r($data_per_uuidspop);
		// print_r("<br/>");

		if ($data_per_uuidspop) {
			// print_r("Ada data");
			$this->Tbl_pembelian_model->delete_by_uuid_spop($uuid_spop);
			// die;
			$this->session->set_flashdata('message', 'Delete Record Success');
			redirect(site_url('tbl_pembelian'));
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(site_url('tbl_pembelian'));
		}
	}

	public function delete($id)
	{
		$row = $this->Tbl_pembelian_model->get_by_id($id);

		if ($row) {
			$this->Tbl_pembelian_model->delete($id);
			$this->session->set_flashdata('message', 'Delete Record Success');
			redirect(site_url('tbl_pembelian'));
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(site_url('tbl_pembelian'));
		}
	}

	public function delete_by_uuid_pembelian_from_per_spop_update($uuid_pembelian = null, $get_uuid_spop = null)
	{


		$get_id_pembelian = $this->Tbl_pembelian_model->get_by_uuid_pembelian($uuid_pembelian);
		$get_id_persediaan_di_tbl_pembelian = $get_id_pembelian->id_persediaan_barang;


		$row = $this->Tbl_pembelian_model->get_by_id($get_id_pembelian->id);

		if ($row) {

			// Hapus record di tabel pembelian
			$this->Tbl_pembelian_model->delete($get_id_pembelian->id);

			// hapus record di tabel persediaan
			$this->Persediaan_model->delete($get_id_persediaan_di_tbl_pembelian);

			$this->session->set_flashdata('message', 'Delete Record Success');
			redirect(site_url('tbl_pembelian/create_add_uraian_update/' . $get_uuid_spop));
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(site_url('tbl_pembelian'));
		}
	}

	public function delete_by_uuid_spop($uuid_spop)
	{


		// $get_data_uuid_spop = $this->Tbl_pembelian_model->get_by_uuid_spop($uuid_spop);

		$row = $this->Tbl_pembelian_model->get_by_uuid_spop($uuid_spop);

		if ($row) {
			$this->Tbl_pembelian_model->delete_by_uuid_spop($uuid_spop);
			$this->session->set_flashdata('message', 'Delete Record Success');
			redirect(site_url('tbl_pembelian'));
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(site_url('tbl_pembelian'));
		}
	}
	public function delete_by_uuid_pembelian($uuid_pembelian)
	{


		$get_id_pembelian = $this->Tbl_pembelian_model->get_by_uuid_pembelian($uuid_pembelian);

		$row = $this->Tbl_pembelian_model->get_by_id($get_id_pembelian->id);

		if ($row) {
			$this->Tbl_pembelian_model->delete($get_id_pembelian->id);
			$this->session->set_flashdata('message', 'Delete Record Success');
			redirect(site_url('tbl_pembelian'));
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(site_url('tbl_pembelian'));
		}
	}

	public function _rules()
	{
		// $this->form_validation->set_rules('tgl_po', 'tgl po', 'trim|required');
		// // $this->form_validation->set_rules('nmrsj', 'nmrsj', 'trim|required');
		// $this->form_validation->set_rules('nmrfakturkwitansi', 'nmrfakturkwitansi', 'trim|required');
		// $this->form_validation->set_rules('nmrbpb', 'nmrbpb', 'trim|required');
		$this->form_validation->set_rules('spop', 'spop', 'trim|required');
		$this->form_validation->set_rules('uuid_supplier', 'supplier', 'trim|required');
		// $this->form_validation->set_rules('supplier_nama', 'supplier nama', 'trim|required');
		// $this->form_validation->set_rules('uraian', 'uraian', 'trim|required');
		// $this->form_validation->set_rules('jumlah', 'jumlah', 'trim|required');
		// $this->form_validation->set_rules('satuan', 'satuan', 'trim|required');
		// // $this->form_validation->set_rules('konsumen', 'konsumen', 'trim|required');
		// $this->form_validation->set_rules('harga_satuan', 'harga satuan', 'trim|required|numeric');
		// // $this->form_validation->set_rules('harga_total', 'harga total', 'trim|required|numeric');
		// $this->form_validation->set_rules('statuslu', 'statuslu', 'trim|required');
		// // $this->form_validation->set_rules('kas_bank', 'kas bank', 'trim|required');
		// // $this->form_validation->set_rules('tgl_bayar', 'tgl bayar', 'trim|required');
		// // $this->form_validation->set_rules('id_usr', 'id usr', 'trim|required');

		$this->form_validation->set_rules('id', 'id', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
	}

	public function excel()
	{
		$this->load->helper('exportexcel');
		$namaFile = "tbl_pembelian.xls";
		$judul = "tbl_pembelian";
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
		xlsWriteLabel($tablehead, $kolomhead++, "Tgl Po");
		xlsWriteLabel($tablehead, $kolomhead++, "Nmrsj");
		xlsWriteLabel($tablehead, $kolomhead++, "Nmrfakturkwitansi");
		xlsWriteLabel($tablehead, $kolomhead++, "Nmrbpb");
		xlsWriteLabel($tablehead, $kolomhead++, "Spop");
		xlsWriteLabel($tablehead, $kolomhead++, "Supplier Kode");
		xlsWriteLabel($tablehead, $kolomhead++, "Supplier Nama");
		xlsWriteLabel($tablehead, $kolomhead++, "Uraian");
		xlsWriteLabel($tablehead, $kolomhead++, "Jumlah");
		xlsWriteLabel($tablehead, $kolomhead++, "Satuan");
		xlsWriteLabel($tablehead, $kolomhead++, "Konsumen");
		xlsWriteLabel($tablehead, $kolomhead++, "Harga Satuan");
		xlsWriteLabel($tablehead, $kolomhead++, "Harga Total");
		xlsWriteLabel($tablehead, $kolomhead++, "Statuslu");
		xlsWriteLabel($tablehead, $kolomhead++, "Kas Bank");
		xlsWriteLabel($tablehead, $kolomhead++, "Tgl Bayar");
		xlsWriteLabel($tablehead, $kolomhead++, "Id Usr");

		foreach ($this->Tbl_pembelian_model->get_all() as $data) {
			$kolombody = 0;

			//ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
			xlsWriteNumber($tablebody, $kolombody++, $nourut);
			xlsWriteLabel($tablebody, $kolombody++, $data->tgl_po);
			xlsWriteLabel($tablebody, $kolombody++, $data->nmrsj);
			xlsWriteLabel($tablebody, $kolombody++, $data->nmrfakturkwitansi);
			xlsWriteNumber($tablebody, $kolombody++, $data->nmrbpb);
			xlsWriteNumber($tablebody, $kolombody++, $data->spop);
			xlsWriteNumber($tablebody, $kolombody++, $data->supplier_kode);
			xlsWriteLabel($tablebody, $kolombody++, $data->supplier_nama);
			xlsWriteLabel($tablebody, $kolombody++, $data->uraian);
			xlsWriteNumber($tablebody, $kolombody++, $data->jumlah);
			xlsWriteLabel($tablebody, $kolombody++, $data->satuan);
			xlsWriteLabel($tablebody, $kolombody++, $data->konsumen);
			xlsWriteNumber($tablebody, $kolombody++, $data->harga_satuan);
			xlsWriteNumber($tablebody, $kolombody++, $data->harga_total);
			xlsWriteLabel($tablebody, $kolombody++, $data->statuslu);
			xlsWriteLabel($tablebody, $kolombody++, $data->kas_bank);
			xlsWriteLabel($tablebody, $kolombody++, $data->tgl_bayar);
			xlsWriteNumber($tablebody, $kolombody++, $data->id_usr);

			$tablebody++;
			$nourut++;
		}

		xlsEOF();
		exit();
	}

	public function unit()
	{
		$Data_stock = $this->Tbl_pembelian_model->stock();
		$data = array(
			'Data_stock' => $Data_stock,
		);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/stock/adminlte310_stock_barang', $data);
	}

	public function pecah_satuan_proses($uuid_persediaan = null)
	{
		// $Data_Barang = $this->Tbl_pembelian_model->get_by_uuid_pembelian($uuid_pembelian);
		// $Data_Barang = $this->Persediaan_model->get_by_uuid_persediaan($uuid_persediaan);

		// print_r($Data_Barang);

		// get data pembelian awal ==> jika tidak ada di data tbl_pembelian maka termasuk data stock persediaan
		// get_uuid_barang dengan filter uuid_barang di tbl_pembelian

		// get uuid barang dari uuid_persediaan
		// $get_data_barang = $this->Persediaan_model->get_by_uuid_persediaan($uuid_persediaan);

		// print_r($get_data_barang);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r($get_data_barang->uuid_barang);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");

		// $x = $get_data_barang->uuid_barang;


		// print_r($x);
		// print_r("<br/>");
		// print_r("<br/>");

		// $sql_stock = "SELECT persediaan.*,
		// sum(tbl_pembelian.jumlah) as sum_jumlah_beli,
		// sum(tbl_penjualan.jumlah) as sum_jumlah_jual
		// 		FROM persediaan  
		// 		left join tbl_pembelian ON persediaan.uuid_barang = tbl_pembelian.uuid_barang 
		// 		left join tbl_penjualan ON persediaan.uuid_barang = tbl_penjualan.uuid_barang  
		// 		-- WHERE (persediaan.uuid_barang, persediaan.tanggal) IN (SELECT persediaan.uuid_barang, Max(persediaan.tanggal) FROM persediaan persediaan_a GROUP BY persediaan.uuid_barang)  
		// 		where persediaan.uuid_barang='$x'
		// 		Group by persediaan.uuid_barang,tbl_pembelian.uuid_barang,tbl_penjualan.uuid_barang
		// 		ORDER BY persediaan.namabarang ASC";



		$sql_stock = "SELECT persediaan.id as id_persediaan, 
						persediaan.uuid_persediaan as uuid_persediaan,
						persediaan.kode_barang as kode_barang_persediaan,
						persediaan.namabarang as nama_barang_persediaan,
						persediaan.total_10 as jumlah_sediaan, 
						persediaan.hpp as harga_satuan_persediaan,
						persediaan.tanggal_beli as tanggal_beli_persediaan, 
						persediaan.satuan as satuan, 
						persediaan.spop as spop, 
						-- persediaan.satuan as satuan, 

								-- 	tbl_pembelian.uuid_pembelian as uuid_pembelian,
								-- 	tbl_pembelian.uraian as barang_beli, 
								-- 	tbl_pembelian.jumlah as jumlah_belanja, 
								-- 	tbl_pembelian.harga_satuan as harga_satuan_beli, 
								-- 	tbl_pembelian.tgl_po as tgl_po, 
								-- 	tbl_pembelian.uuid_gudang as uuid_gudang, 
								-- 	tbl_pembelian.nama_gudang as nama_gudang,
								-- 	tbl_pembelian.satuan as satuan,
								-- tbl_penjualan.nama_barang as barang_jual, 
								-- tbl_penjualan.jumlah as jumlah_terjual

						persediaan.nilai_persediaan as nilai_persediaan
		
						FROM persediaan  
						-- left join tbl_pembelian ON persediaan.uuid_barang = tbl_pembelian.uuid_barang 
						-- left join tbl_penjualan ON persediaan.uuid_barang = tbl_penjualan.uuid_barang  
						-- WHERE (persediaan.uuid_barang, persediaan.tanggal) IN (SELECT persediaan.uuid_barang, Max(persediaan.tanggal) FROM persediaan GROUP BY persediaan.uuid_barang)  
						where persediaan.uuid_persediaan='$uuid_persediaan'
						ORDER BY persediaan.uuid_barang ASC";


		// print_r($this->db->query($sql_stock)->result());
		$Data_Barang = $this->db->query($sql_stock)->row();

		// print_r($Data_Barang);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r($Data_Barang->id_persediaan);
		// print_r("<br/>");
		// print_r("<br/>");
		// // die;

		$Get_id_persediaan_barang = $Data_Barang->id_persediaan;

		// jumlah beli berdasarkan $Data_Barang->id_persediaan
		$sql_pembelian_barang = "SELECT `uuid_persediaan`,`id_persediaan_barang`,`uuid_barang`,`uraian`,sum(`jumlah`) as jumlah_beli FROM `tbl_pembelian` WHERE `id_persediaan_barang`='$Get_id_persediaan_barang'";

		$Data_Pembelian_barang = $this->db->query($sql_pembelian_barang)->row();


		// print_r($Data_Pembelian_barang);
		// print_r("<br/>");
		// print_r("<br/>");

		// jumlah jual berdasarkan $Data_Barang->id_persediaan




		$sql_penjualan_barang = "SELECT `uuid_penjualan`,`uuid_persediaan`,`id_persediaan_barang`,`uuid_barang`,`nama_barang`, SUM(`jumlah`) as Sum_jumlah_jual FROM `tbl_penjualan` WHERE `id_persediaan_barang`='$Get_id_persediaan_barang'";

		$Data_Penjualan_barang = $this->db->query($sql_penjualan_barang)->row();

		// print_r($Data_Penjualan_barang);
		// print_r("<br/>");

		$data = array(
			'Data_Barang' => $Data_Barang,
			'action' => site_url('tbl_pembelian/pecah_satuan_action/' . $uuid_persediaan),
			'button' => 'Simpan',
			// 'uuid_pembelian' => $uuid_pembelian,
			'uuid_barang' => $Data_Barang->uuid_barang,
			// 'tgl_po' => $Data_Barang->tgl_po,
			// 'uuid_spop' => $Data_Barang->uuid_spop,
			'kode_barang' => $Data_Barang->kode_barang_persediaan,
			'nama_barang' => $Data_Barang->nama_barang_persediaan,
			'jumlah_persediaan' => $Data_Barang->jumlah_sediaan,
			'satuan' => $Data_Barang->satuan,
			// 'uuid_gudang' => $Data_Barang->uuid_gudang,
			// 'nama_gudang' => $Data_Barang->nama_gudang,
			'harga_satuan' => $Data_Barang->harga_satuan_persediaan,
			'uuid_barang' => $Data_Barang->uuid_barang,
			'uuid_persediaan' => $Data_Barang->uuid_persediaan,

			'jumlah_beli' => $Data_Barang->jumlah_sediaan,
			'jumlah_jual' => $Data_Penjualan_barang->Sum_jumlah_jual,



		);

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_form_pecah_satuan_barang', $data);
	}

	public function rollback_satuan_proses($uuid_persediaan = null)
	{

		// print_r("rollback_satuan_proses");
		// die;

		// $Data_Barang = $this->Tbl_pembelian_model->get_by_uuid_pembelian($uuid_pembelian);
		// $Data_Barang = $this->Persediaan_model->get_by_uuid_persediaan($uuid_persediaan);

		// print_r($Data_Barang);

		// get data pembelian awal ==> jika tidak ada di data tbl_pembelian maka termasuk data stock persediaan
		// get_uuid_barang dengan filter uuid_barang di tbl_pembelian

		// get uuid barang dari uuid_persediaan
		$get_data_barang = $this->Persediaan_model->get_by_uuid_persediaan($uuid_persediaan);

		// print_r($get_data_barang);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r($get_data_barang->uuid_barang);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");

		$GET_uuid_barang = $get_data_barang->uuid_barang;

		$sql_stock = "SELECT persediaan.*,
		sum(tbl_pembelian.jumlah) as sum_jumlah_beli,
		sum(tbl_penjualan.jumlah) as sum_jumlah_jual
				FROM persediaan  
				left join tbl_pembelian ON persediaan.uuid_barang = tbl_pembelian.uuid_barang 
				left join tbl_penjualan ON persediaan.uuid_barang = tbl_penjualan.uuid_barang  
				-- WHERE (persediaan.uuid_barang, persediaan.tanggal) IN (SELECT persediaan.uuid_barang, Max(persediaan.tanggal) FROM persediaan persediaan_a GROUP BY persediaan.uuid_barang)  
				where persediaan.uuid_barang='$GET_uuid_barang'
				Group by persediaan.uuid_barang,tbl_pembelian.uuid_barang,tbl_penjualan.uuid_barang
				ORDER BY persediaan.namabarang ASC";

		// print_r($this->db->query($sql_stock)->result());
		$Data_Barang = $this->db->query($sql_stock)->row();

		// print_r($Data_Barang);
		// die;


		// Source Data Barang Pecah
		$sql_barang_pecah_satuan = "SELECT * FROM tbl_pembelian_pecah_satuan where tbl_pembelian_pecah_satuan.uuid_barang_baru='$GET_uuid_barang' ";
		$Data_Barang_pecah_satuan = $this->db->query($sql_barang_pecah_satuan)->row();

		$Get_uuid_pecah_satuan_proses = $Data_Barang_pecah_satuan->uuid_pecah_satuan;
		// print_r("Data_Barang_pecah_satuan");
		// print_r("<br/>");
		// print_r($Data_Barang_pecah_satuan);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");

		$sql_source_data = "SELECT persediaan.*,
		sum(tbl_pembelian.jumlah) as sum_jumlah_beli,
		sum(tbl_penjualan.jumlah) as sum_jumlah_jual
				FROM persediaan  
				left join tbl_pembelian ON persediaan.uuid_barang = tbl_pembelian.uuid_barang 
				left join tbl_penjualan ON persediaan.uuid_barang = tbl_penjualan.uuid_barang  
				-- WHERE (persediaan.uuid_barang, persediaan.tanggal) IN (SELECT persediaan.uuid_barang, Max(persediaan.tanggal) FROM persediaan persediaan_a GROUP BY persediaan.uuid_barang)  
				where persediaan.uuid_barang='$Data_Barang_pecah_satuan->uuid_barang'
				Group by persediaan.uuid_barang,tbl_pembelian.uuid_barang,tbl_penjualan.uuid_barang
				ORDER BY persediaan.namabarang ASC";

		// print_r($this->db->query($sql_stock)->result());
		$SOURCE_Data_Barang = $this->db->query($sql_source_data)->row();

		// print_r($SOURCE_Data_Barang);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");


		$data = array(
			'Data_Barang' => $Data_Barang,
			'action' => site_url('tbl_pembelian/rollback_satuan_action/' . $Get_uuid_pecah_satuan_proses),
			'button' => 'Simpan',

			// Barang Setelah pecah satuan di persediaan
			'uuid_barang' => $Data_Barang->uuid_barang,
			'kode_barang' => $Data_Barang->kode_barang,
			'nama_barang' => $Data_Barang->namabarang,
			'jumlah_persediaan' => $Data_Barang->total_10,
			'jumlah_beli' => $Data_Barang->sum_jumlah_beli,
			'jumlah_jual' => $Data_Barang->sum_jumlah_jual,

			'satuan' => $Data_Barang->satuan,
			'harga_satuan' => $Data_Barang->hpp,
			'uuid_barang' => $Data_Barang->uuid_barang,
			'uuid_persediaan' => $uuid_persediaan,



			// Barang Setelah pecah satuan di persediaan
			'uuid_barang_source' => $SOURCE_Data_Barang->uuid_barang,
			'kode_barang_source' => $SOURCE_Data_Barang->kode_barang,
			'nama_barang_source' => $SOURCE_Data_Barang->namabarang,
			'jumlah_persediaan_source' => $SOURCE_Data_Barang->total_10,

			'jumlah_beli_source' => $SOURCE_Data_Barang->sum_jumlah_beli,
			'jumlah_jual_source' => $SOURCE_Data_Barang->sum_jumlah_jual,

			'satuan_source' => $SOURCE_Data_Barang->satuan,
			'harga_satuan_source' => $SOURCE_Data_Barang->hpp,


			'jumlah_di_pecah_satuan_source' => $Data_Barang_pecah_satuan->jumlah,
			'jumlah_setelah_di_pecah_satuan' => $Data_Barang_pecah_satuan->jumlah_barang_baru,


		);

		// print_r($data);

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_form_rollback_barang', $data);
	}


	public function rollback_satuan_action($Get_uuid_pecah_satuan_proses = null)
	{

		// print_r($Get_uuid_pecah_satuan_proses);
		// print_r("<br/>");

		// print_r($this->input->post('jumlah_barang_rollback_stock', TRUE));
		// print_r("<br/>");

		// print_r("rollback_satuan_action");
		// print_r("<br/>");
		// die;

		// Update tbl_pembelian_pecah_satuan ==> mengurangi jumlah yang terpecah


		// $get_data_barang = $this->Persediaan_model->get_by_uuid_persediaan($uuid_persediaan);

		// $GET_uuid_barang = $get_data_barang->uuid_barang;

		// Get Jumlah yang terpecah 


		$sql_barang_pecah_satuan = "SELECT * FROM `tbl_pembelian_pecah_satuan` WHERE `uuid_pecah_satuan`='$Get_uuid_pecah_satuan_proses'";
		$Data_Barang_pecah_satuan = $this->db->query($sql_barang_pecah_satuan)->row();
		$Get_uuid_barang_baru = $Data_Barang_pecah_satuan->uuid_barang_baru;

		// print_r($Data_Barang_pecah_satuan);
		// print_r("<br/>");
		// print_r("<br/>");

		$Get_jumlah_sebelum_terpecah_awal = $Data_Barang_pecah_satuan->jumlah;

		// print_r("STOCK Get_jumlah_sebelum_terpecah_awal: ");
		// print_r($Get_jumlah_sebelum_terpecah_awal);
		// print_r("<br/>");


		$Get_jumlah_setelah_terpecah = $Data_Barang_pecah_satuan->jumlah_barang_baru;

		// print_r("BARANG BARU Get_jumlah_setelah_terpecah: ");
		// print_r($Get_jumlah_setelah_terpecah);
		// print_r("<br/>");
		// // print_r("<br/>");


		$Get_jumlah_variabel_perubahan_jumlah_satuan = $Get_jumlah_setelah_terpecah / $Get_jumlah_sebelum_terpecah_awal;

		// print_r("Variabel pecahan ke satuan BARU : Get_jumlah_variabel_perubahan_jumlah_satuan: ");
		// print_r($Get_jumlah_variabel_perubahan_jumlah_satuan);
		// print_r("<br/>");
		// // print_r("<br/>");

		$Get_jumlah_rollback = preg_replace("/[^0-9]/", "", $this->input->post('jumlah_barang_rollback_stock', TRUE));

		// print_r("<br/>");
		// print_r("Yang di ROLL BACK : Get_jumlah_rollback: ");
		// print_r($Get_jumlah_rollback);
		// print_r("<br/>");




		$Get_jumlah_akhir_proses = $Get_jumlah_sebelum_terpecah_awal - preg_replace("/[^0-9]/", "", $this->input->post('jumlah_barang_rollback_stock', TRUE));
		// die;

		// print_r("Get_jumlah_akhir_proses: ");
		// print_r($Get_jumlah_akhir_proses);
		// print_r("<br/>");


		$Get_jumlah_update_jumlah_terpecah = $Get_jumlah_setelah_terpecah - ($Get_jumlah_variabel_perubahan_jumlah_satuan * preg_replace("/[^0-9]/", "", $this->input->post('jumlah_barang_rollback_stock', TRUE)));

		// print_r("<br/>");
		// print_r("UPDATE Jumlah BARANG BARU Get_jumlah_update_jumlah_terpecah: ");
		// print_r($Get_jumlah_update_jumlah_terpecah);
		// print_r("<br/>");


		// // Source Data Barang Pecah
		$sql_barang_pecah_satuan = "UPDATE `tbl_pembelian_pecah_satuan` SET `jumlah` = $Get_jumlah_akhir_proses , `jumlah_barang_baru` = $Get_jumlah_update_jumlah_terpecah WHERE `uuid_pecah_satuan`='$Get_uuid_pecah_satuan_proses'";
		$this->db->query($sql_barang_pecah_satuan);

		// Jumlah baru untuk barang baru setelah ROLL BACK
		$sql_persediaan_jumlah_stock = "SELECT `total_10` FROM `persediaan` WHERE `uuid_barang`='$Get_uuid_barang_baru'";
		$Get_JUMLAH_stock_barang_baru_di_persediaan = $this->db->query($sql_persediaan_jumlah_stock)->row();

		// print_r("JUMLAH DI PERSEDIAAN Get_JUMLAH_stock_barang_baru_di_persediaan->total_10");
		// print_r($Get_JUMLAH_stock_barang_baru_di_persediaan->total_10);
		// print_r("<br/>");

		$Get_sisa_stock_barang_baru = $Get_JUMLAH_stock_barang_baru_di_persediaan->total_10 - ($Get_jumlah_variabel_perubahan_jumlah_satuan * preg_replace("/[^0-9]/", "", $this->input->post('jumlah_barang_rollback_stock', TRUE)));

		// print_r($Get_sisa_stock_barang_baru);
		// print_r("<br/>");


		// update barang pecah satuan di data stock persediaan , dikurang sejumlah yang di rollback
		$sql_persediaan_update_jumlah_barang_baru = "UPDATE `persediaan` SET `sa` = $Get_sisa_stock_barang_baru , `beli` = $Get_sisa_stock_barang_baru , `tuj` =$Get_sisa_stock_barang_baru , `total_10` = $Get_sisa_stock_barang_baru WHERE `uuid_barang`='$Get_uuid_barang_baru'";
		$this->db->query($sql_persediaan_update_jumlah_barang_baru);



		// Display Akhir data =================================================================================================
		$sql_barang_pecah_satuan = "SELECT * FROM `tbl_pembelian_pecah_satuan` WHERE `uuid_pecah_satuan`='$Get_uuid_pecah_satuan_proses'";
		$Data_Barang_pecah_satuan = $this->db->query($sql_barang_pecah_satuan)->row();

		// print_r($Data_Barang_pecah_satuan);
		// print_r("<br/>");
		// print_r($Data_Barang_pecah_satuan->uuid_barang_baru);
		$Get_uuid_barang_baru = $Data_Barang_pecah_satuan->uuid_barang_baru;
		// print_r("<br/>");
		// print_r("<br/>");


		$sql_barang_pecah_satuan_di_persediaan = "SELECT * FROM `persediaan` WHERE `uuid_barang`='$Get_uuid_barang_baru'";
		$Data_Barang_pecah_satuan_di_persediaan = $this->db->query($sql_barang_pecah_satuan_di_persediaan)->row();

		// print_r($Data_Barang_pecah_satuan_di_persediaan);
		// print_r("<br/>");
		// print_r("<br/>");

		// print_r("Selesai");
		// die;
		redirect(site_url('tbl_pembelian/pecah_satuan'));
	}

	public function pecah_satuan($uuid_gudang = null)
	{



		// if (null !== ($this->input->post('uuid_gudang', TRUE))) {
		// 	if ($this->input->post('uuid_gudang', TRUE) == "semua") {

		// 		print_r("IF SEMUA");

		// 		$sql_stock = "SELECT persediaan.kode_barang as kode_barang, persediaan.namabarang as nama_barang_beli,persediaan.total_10 as jumlah_sediaan,  persediaan.hpp as harga_satuan_persediaan,
		// 						tbl_pembelian.uuid_pembelian as uuid_pembelian,tbl_pembelian.uraian as barang_beli, tbl_pembelian.jumlah as jumlah_belanja, tbl_pembelian.harga_satuan as harga_satuan_beli,  tbl_pembelian.tgl_po as tgl_po,tbl_pembelian.uuid_gudang as uuid_gudang, tbl_pembelian.nama_gudang as nama_gudang,  tbl_pembelian.satuan as satuan,
		// 					tbl_penjualan.nama_barang as barang_jual, tbl_penjualan.jumlah as jumlah_terjual
		//                     FROM persediaan  
		//                    	left join tbl_pembelian ON persediaan.uuid_barang = tbl_pembelian.uuid_barang 
		//                     left join tbl_penjualan ON persediaan.uuid_barang = tbl_penjualan.uuid_barang  
		// 					WHERE (persediaan.uuid_barang, persediaan.tanggal) IN (SELECT persediaan.uuid_barang, Max(persediaan.tanggal) FROM persediaan GROUP BY persediaan.uuid_barang)  
		//                     ORDER BY persediaan.uuid_barang ASC";


		// 		$Data_stock = $this->db->query($sql_stock)->result();
		// 	} else {
		// 		print_r("IF GUDANG");
		// 		$uuid_gudang = $this->input->post('uuid_gudang', TRUE);

		// 		$sql_stock = "SELECT persediaan.kode_barang as kode_barang, persediaan.namabarang as nama_barang_beli,persediaan.total_10 as jumlah_sediaan, persediaan.hpp as harga_satuan_persediaan,
		// 						tbl_pembelian.uuid_pembelian as uuid_pembelian,tbl_pembelian.uraian as barang_beli, tbl_pembelian.jumlah as jumlah_belanja, tbl_pembelian.harga_satuan as harga_satuan_beli, tbl_pembelian.tgl_po as tgl_po,tbl_pembelian.uuid_gudang as uuid_gudang, tbl_pembelian.nama_gudang as nama_gudang,tbl_pembelian.satuan as satuan,
		// 					tbl_penjualan.nama_barang as barang_jual, tbl_penjualan.jumlah as jumlah_terjual
		// 					FROM persediaan  
		// 	   				left join tbl_pembelian ON persediaan.uuid_barang = tbl_pembelian.uuid_barang 
		// 					left join tbl_penjualan ON persediaan.uuid_barang = tbl_penjualan.uuid_barang  
		// 					WHERE tbl_pembelian.uuid_gudang='$uuid_gudang' AND (persediaan.uuid_barang, persediaan.tanggal) IN (SELECT persediaan.uuid_barang, Max(persediaan.tanggal) FROM persediaan GROUP BY persediaan.uuid_barang)  
		// 					ORDER BY persediaan.uuid_barang ASC";


		// 		$Data_stock = $this->db->query($sql_stock)->result();
		// 	}
		// } else {

		// 	print_r("ELSE SEMUA");
		// 	$sql_stock = "SELECT persediaan.kode_barang as kode_barang, persediaan.namabarang as nama_barang_beli,persediaan.total_10 as jumlah_sediaan, persediaan.hpp as harga_satuan_persediaan,
		// 				tbl_pembelian.uuid_pembelian as uuid_pembelian,tbl_pembelian.uraian as barang_beli, tbl_pembelian.jumlah as jumlah_belanja, tbl_pembelian.harga_satuan as harga_satuan_beli, tbl_pembelian.tgl_po as tgl_po,tbl_pembelian.uuid_gudang as uuid_gudang, tbl_pembelian.nama_gudang as nama_gudang,tbl_pembelian.satuan as satuan,
		// 			tbl_penjualan.nama_barang as barang_jual, tbl_penjualan.jumlah as jumlah_terjual
		// 			FROM persediaan  
		// 			left join tbl_pembelian ON persediaan.uuid_barang = tbl_pembelian.uuid_barang 
		// 			left join tbl_penjualan ON persediaan.uuid_barang = tbl_penjualan.uuid_barang  
		// 			WHERE (persediaan.uuid_barang, persediaan.tanggal) IN (SELECT persediaan.uuid_barang, Max(persediaan.tanggal) FROM persediaan GROUP BY persediaan.uuid_barang)  
		// 			ORDER BY persediaan.uuid_barang ASC";


		// 	$Data_stock = $this->db->query($sql_stock)->result();
		// }






		if (isset($uuid_gudang)) {
			// $Data_stock = $this->Tbl_pembelian_model->stock_by_gudang($uuid_gudang);
			// print_r("NON  IF GUDANG");

			$uuid_gudang = $this->input->post('uuid_gudang', TRUE);
			// $Data_stock = $this->Tbl_pembelian_model->stock_by_gudang($uuid_gudang);

			$sql_stock = "SELECT persediaan.uuid_persediaan as uuid_persediaan, persediaan.uuid_barang as uuid_barang, persediaan.kode_barang as kode_barang, persediaan.namabarang as nama_barang_beli,persediaan.total_10 as jumlah_sediaan, persediaan.hpp as harga_satuan_persediaan, persediaan.tanggal as tanggal , persediaan.satuan as satuan , sum(tbl_pembelian_a.jumlah) as sum_jumlah_beli, sum(tbl_penjualan_a.jumlah) as sum_jumlah_jual
						FROM persediaan persediaan_a   
						   left join tbl_pembelian tbl_pembelian_a ON persediaan.uuid_barang = tbl_pembelian_a.uuid_barang 
						left join tbl_penjualan tbl_penjualan_a ON persediaan.uuid_barang = tbl_penjualan_a.uuid_barang  
						WHERE tbl_pembelian_a.uuid_gudang='$uuid_gudang' AND (persediaan.uuid_barang, persediaan.tanggal) IN (SELECT persediaan.uuid_barang, Max(persediaan.tanggal) FROM persediaan persediaan_a GROUP BY persediaan.uuid_barang)  
						ORDER BY persediaan.namabarang ASC";

			// print_r($this->db->query($sql_stock)->result());
			$Data_stock = $this->db->query($sql_stock)->result();
		} else {
			// $Data_stock = $this->Tbl_pembelian_model->stock();
			// print_r("NON  IF SEMUA");

			// $sql_stock = "SELECT persediaan.uuid_persediaan as uuid_persediaan, 
			// persediaan.uuid_barang as uuid_barang, 
			// persediaan.kode_barang as kode_barang, 
			// persediaan.namabarang as nama_barang_persediaan, 
			// persediaan.total_10 as jumlah_sediaan, 
			// persediaan.hpp as harga_satuan_persediaan, 
			// persediaan.tanggal as tanggal, 
			// persediaan.satuan as satuan,
			// sum(tbl_pembelian.jumlah) as sum_jumlah_beli,
			// sum(tbl_penjualan.jumlah) as sum_jumlah_jual,
			// tbl_pembelian_pecah_satuan.jumlah as jumlah_terpecah,
			// tbl_pembelian_pecah_satuan.jumlah_barang_baru as jumlah_setelah_terpecah,
			// tbl_pembelian_pecah_satuan.uuid_barang as uuid_barang_pecah,
			// tbl_pembelian_pecah_satuan.uuid_barang_baru as uuid_barang_baru
			// 		FROM persediaan  
			// 		left join tbl_pembelian ON persediaan.uuid_barang = tbl_pembelian.uuid_barang 
			// 		left join tbl_penjualan ON persediaan.uuid_barang = tbl_penjualan.uuid_barang  
			// 		left join tbl_pembelian_pecah_satuan ON persediaan.uuid_barang = tbl_pembelian_pecah_satuan.uuid_barang  
			// 		-- WHERE (persediaan.uuid_barang, persediaan.tanggal) IN (SELECT persediaan.uuid_barang, Max(persediaan.tanggal) FROM persediaan persediaan_a GROUP BY persediaan.uuid_barang)  
			// 		Group by persediaan.uuid_barang,tbl_pembelian.uuid_barang,tbl_penjualan.uuid_barang
			// 		ORDER BY persediaan.namabarang ASC";

			// // print_r($this->db->query($sql_stock)->result());
			// $Data_stock = $this->db->query($sql_stock)->result();



			$sql_stock = "SELECT persediaan.kode_barang as kode_barang, 
			persediaan.uuid_persediaan as uuid_persediaan,
			persediaan.namabarang as nama_barang_persediaan,
			persediaan.total_10 as jumlah_sediaan, 
			persediaan.hpp as harga_satuan_persediaan,
			persediaan.tanggal_beli as tanggal_beli_persediaan, 
			persediaan.satuan as satuan, 
			persediaan.spop as spop, 
			persediaan.penjualan as penjualan, 
			persediaan.pecah_satuan as pecah_satuan, 
			persediaan.bahan_produksi as bahan_produksi, 
			
					-- 	tbl_pembelian.uuid_pembelian as uuid_pembelian,
					-- 	tbl_pembelian.uraian as barang_beli, 
					-- 	tbl_pembelian.jumlah as jumlah_belanja, 
					-- 	tbl_pembelian.harga_satuan as harga_satuan_beli, 
					-- 	tbl_pembelian.tgl_po as tgl_po, 
					-- 	tbl_pembelian.uuid_gudang as uuid_gudang, 
					-- 	tbl_pembelian.nama_gudang as nama_gudang,
					-- 	tbl_pembelian.satuan as satuan,
					-- tbl_penjualan.nama_barang as barang_jual, 
					-- tbl_penjualan.jumlah as jumlah_terjual

			persediaan.nilai_persediaan as nilai_persediaan
					
					FROM persediaan  
					-- left join tbl_pembelian ON persediaan.uuid_barang = tbl_pembelian.uuid_barang 
					-- left join tbl_penjualan ON persediaan.uuid_barang = tbl_penjualan.uuid_barang  
					-- WHERE (persediaan.uuid_barang, persediaan.tanggal) IN (SELECT persediaan.uuid_barang, Max(persediaan.tanggal) FROM persediaan GROUP BY persediaan.uuid_barang)  
					ORDER BY persediaan.uuid_barang ASC";

			// print_r($this->db->query($sql_stock)->result());
			$Data_stock = $this->db->query($sql_stock)->result();
		}

		// print_r($Data_stock);

		$data = array(
			'action_cari_gudang' => site_url('Tbl_pembelian/pecah_satuan'),
			'Data_stock' => $Data_stock,
		);


		// print_r($data);

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/pecah_satuan/adminlte310_list_barang', $data);
	}

	public function pecah_satuan_action($uuid_persediaan)
	{



		$jumlah_proses = preg_replace("/[^0-9]/", "", $this->input->post('jumlah_barang_baru', TRUE));
		$harga_satuan_proses = preg_replace("/[^0-9]/", "", $this->input->post('harga_satuan_barang_baru', TRUE));

		// $Data_Barang = $this->Tbl_pembelian_model->get_by_uuid_pembelian($uuid_pembelian);
		$Data_Barang = $this->Persediaan_model->get_by_uuid_persediaan($uuid_persediaan);

		// print_r("Data_Barang Persediaan: ");
		// print_r("<br/>");
		// print_r($Data_Barang);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");

		$get_jumlah_barang_di_pecah = preg_replace("/[^0-9]/", "", $this->input->post('jumlah_barang_dari_stock', TRUE));


		// print_r("get_jumlah_barang_di_pecah: ");
		// print_r("<br/>");
		// print_r($get_jumlah_barang_di_pecah);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");

		$get_jumlah_barang_baru = preg_replace("/[^0-9]/", "", $this->input->post('jumlah_barang_baru', TRUE));


		// print_r("get_jumlah_barang_baru: ");
		// print_r("<br/>");
		// print_r($get_jumlah_barang_baru);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");

		if ($this->input->post('kode_barang_baru', TRUE)) {
			// print_r("ada kode barang");
			$get_kode_barang = $this->input->post('kode_barang_baru', TRUE);
		} else {
			// print_r("kosong kode barang");
			// Otomatis membuat kode barang

			$get_kode_barang = "";

			$teks = $this->input->post('nama_barang_baru', TRUE);

			$split = explode(' ', $teks);
			foreach ($split as $kata) {
				$get_kode_barang = $get_kode_barang . substr($kata, 0, 2);
			}

			// print_r($get_kode_barang);
			// print_r("<br/>");

			// CEK KODE APAKAH SUDAH ADA, JIKA SUDAH ADA MAKA DITAMBAHKAN NOMOR
			// query chek sys_nama_barang
			$this->db->where('kode_barang', $get_kode_barang);
			$sys_nama_barang = $this->db->get('sys_nama_barang');

			if ($sys_nama_barang->num_rows() > 0) {
				// print_r ("Sudah ada ");
				$get_kode_barang = $get_kode_barang . "_" . $sys_nama_barang->num_rows();
			}
			// print_r($get_kode_barang);
			// print_r("<br/>");

		}
		// print_r("get_kode_barang: ");
		// print_r($get_kode_barang);
		// print_r("<br/>");


		if ($this->input->post('harga_satuan_barang_baru', TRUE)) {
			// print_r("isi harga_satuan_barang_baru");
			$get_harga_satuan_barang_baru = preg_replace("/[^0-9]/", "", $this->input->post('harga_satuan_barang_baru', TRUE));
		} else {
			// print_r("tidak ada harga_satuan_barang_baru");
			// Get harga satuan lama dibagi dengan (jumlah stock / jumlah baru)
			$get_harga_satuan_barang_baru = $Data_Barang->hpp / ($get_jumlah_barang_baru / $get_jumlah_barang_di_pecah);
		}

		// print_r("<br/>");
		// print_r("get_harga_satuan_barang_baru: ");
		// print_r($get_harga_satuan_barang_baru);
		// print_r("<br/>");




		// Insert nama barang baru ke sys_nama_barang dan mendapatkan uuid_barang
		$data_Sys_nama_barang = array(
			// 'uuid_barang' => $this->input->post('uuid_barang',TRUE),
			'kode_barang' => $get_kode_barang,
			'nama_barang' => $this->input->post('nama_barang_baru', TRUE),
			'satuan' => $this->input->post('satuan_barang_baru', TRUE),
			// 'keterangan' => $this->input->post('keterangan',TRUE),
		);


		// print_r("data_Sys_nama_barang: ");
		// print_r("<br/>");
		// print_r($data_Sys_nama_barang);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");

		$uuid_barang_baru = $this->Sys_nama_barang_model->insert_dari_pecah_satuan($data_Sys_nama_barang);

		// Simpan ke tabel persediaan

		$get_uuid_unit = $this->input->post('uuid_unit', TRUE);
		$data_unit = $this->Sys_unit_model->get_by_uuid_unit($get_uuid_unit);
		$data_nama_unit = $data_unit->nama_unit;




		// // Proses simpan ke tbl_penjualan dari barang asli sesuai jumlah yang di pecah : ditambah info status
		// $data_Tbl_penjualan = array(
		// 	'tgl_input' => date("Y-m-d H:i:s"),
		// 	'tgl_jual' => date("Y-m-d H:i:s"),


		// 	'uuid_persediaan' => $uuid_persediaan,
		// 	'id_persediaan_barang' => $Data_Barang->id,


		// 	'nmrpesan' => "pecahsatuan",
		// 	'nmrkirim' => "pecahsatuan",

		// 	'uuid_konsumen' => $get_uuid_unit,
		// 	'konsumen_nama' => $data_nama_unit,

		// 	'uuid_barang' => $Data_Barang->uuid_barang,
		// 	'kode_barang' => $Data_Barang->kode_barang,
		// 	'nama_barang' => $Data_Barang->namabarang,

		// 	'uuid_unit' => $get_uuid_unit,
		// 	'unit' => $data_nama_unit,

		// 	'jumlah' => $get_jumlah_barang_di_pecah,
		// 	'satuan' => $Data_Barang->satuan,
		// 	'harga_satuan' => $Data_Barang->harga_satuan,
		// 	'total_nominal' =>  $get_jumlah_barang_di_pecah * $Data_Barang->harga_satuan,
		// 	'id_usr' => 1,
		// );


		// // print_r("data_Tbl_penjualan: ");
		// // print_r("<br/>");
		// // print_r($data_Tbl_penjualan);
		// // print_r("<br/>");
		// // print_r("<br/>");
		// // print_r("<br/>");

		// $this->Tbl_penjualan_model->insert_new($data_Tbl_penjualan);

		// Proses simpan ke tbl_pembelian menjadi barang baru

		// Input ke data persediaan
		$data_Persediaan = array(
			// 'id' => $this->input->post('id', TRUE),
			'tanggal' => date("Y-m-d H:i:s"),
			// 'tanggal_new' => date("Y-m-d H:i:s"),
			'uuid_barang' => $uuid_barang_baru,
			'kode_barang' => $get_kode_barang,
			'kode' => $get_kode_barang,
			'namabarang' => $this->input->post('nama_barang_baru', TRUE),
			'satuan' => $this->input->post('satuan_barang_baru', TRUE),
			'hpp' => $get_harga_satuan_barang_baru,
			'sa' => preg_replace("/[^0-9]/", "", $this->input->post('jumlah_barang_baru', TRUE)),
			'spop' => "pecahsatuan",
			'beli' => $get_jumlah_barang_baru,
			'tuj' => $get_jumlah_barang_baru,
			'total_10' => $get_jumlah_barang_baru,
			'nilai_persediaan' => $get_harga_satuan_barang_baru * $get_jumlah_barang_baru,
		);

		// print_r("data_Persediaan: ");
		// print_r("<br/>");
		// print_r($data_Persediaan);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");




		// // Update Field Pecah satuan : ditambahkan sejumlah stock yang di pecah
		// $Get_jumlah_setelah_dipecah = $Data_Barang->sa - $get_jumlah_barang_di_pecah;
		// $Get_nominal_persediaan = $Get_jumlah_setelah_dipecah * $Data_Barang->hpp;

		$data_update_persediaan_setelah_di_pecah = array(
			// 'sa' => $Get_jumlah_setelah_dipecah,
			// 'total_10' => $Get_jumlah_setelah_dipecah,
			'pecah_satuan' => $get_jumlah_barang_di_pecah,
		);

		$this->Persediaan_model->update($Data_Barang->id, $data_update_persediaan_setelah_di_pecah);


		$get_id_persediaan_new_pecah_satuan = $this->Persediaan_model->insert_pecah_satuan($data_Persediaan);

		// print_r($get_id_persediaan_new_pecah_satuan);
		// print_r("<br/>");
		// print_r("<br/>");

		$Get_data_persediaan_row_id = $this->Persediaan_model->get_by_id($get_id_persediaan_new_pecah_satuan);


		// print_r($Get_data_persediaan_row_id);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r($Get_data_persediaan_row_id->uuid_persediaan);
		// print_r("<br/>");
		// print_r("<br/>");


		// Simpan ke tabel pecah satuan

		$data_Tbl_pembelian_pecah_satuan = array(

			'proses_input' => date("Y-m-d H:i:s"),
			// 'uuid_pembelian' => $Data_Barang->uuid_pembelian,
			'uuid_barang' => $Data_Barang->uuid_barang,
			'uuid_persediaan' => $uuid_persediaan,
			'tgl_po' => date("Y-m-d H:i:s"),
			// 'nmrsj' => $Data_Barang->nmrsj,
			// 'nmrfakturkwitansi' => $Data_Barang->nmrfakturkwitansi,
			// 'nmrbpb' => $Data_Barang->nmrbpb,
			// 'uuid_spop' => $Data_Barang->uuid_spop,
			// 'spop' => $Data_Barang->spop,
			// 'status_spop' => $Data_Barang->status_spop,
			// 'uuid_supplier' => $Data_Barang->uuid_supplier,
			// 'supplier_kode' => $Data_Barang->supplier_kode,
			// 'supplier_nama' => $Data_Barang->supplier_nama,
			'kode_barang' => $Data_Barang->kode_barang,
			'uraian' => $Data_Barang->namabarang,
			'jumlah' => $get_jumlah_barang_di_pecah,
			'satuan' => $Data_Barang->satuan,
			// 'uuid_konsumen' => $Data_Barang->uuid_konsumen,
			// 'konsumen' => $Data_Barang->konsumen,
			// 'uuid_gudang' => $Data_Barang->uuid_gudang,
			// 'nama_gudang' => $Data_Barang->nama_gudang,
			'harga_satuan' => $Data_Barang->hpp,
			// 'uuid_gudang_baru' => $this->input->post('uuid_gudang', TRUE),

			'id_persediaan_baru' => $get_id_persediaan_new_pecah_satuan,
			'uuid_persediaan_baru' => $Get_data_persediaan_row_id->uuid_persediaan,
			'uuid_barang_baru' => $uuid_barang_baru,
			'kode_barang_baru' => $get_kode_barang,
			'nama_barang_baru' => $this->input->post('nama_barang_baru', TRUE),
			'jumlah_barang_baru' => $get_jumlah_barang_baru,
			'satuan_barang_baru' => $this->input->post('satuan_barang_baru', TRUE),
			'harga_satuan_barang_baru' => $get_harga_satuan_barang_baru,

		);

		// print_r("Tbl_pembelian_pecah_satuan: ");
		// print_r("<br/>");
		// print_r($data_Tbl_pembelian_pecah_satuan);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");
		// die;

		$this->Tbl_pembelian_pecah_satuan_model->insert($data_Tbl_pembelian_pecah_satuan);

		// die;

		// // GET GUDANG DATA
		// $GET_uuid_gudang = $this->input->post('uuid_gudang', TRUE);
		// $sql_uuid_gudang = "SELECT * FROM `sys_gudang` WHERE `uuid_gudang`='$GET_uuid_gudang'";
		// $get_kode_gudang = $this->db->query($sql_uuid_gudang)->row()->kode_gudang;
		// $get_nama_gudang = $this->db->query($sql_uuid_gudang)->row()->nama_gudang;

		// print_r($data_Tbl_pembelian_pecah_satuan);
		// die;


		redirect(site_url('tbl_pembelian/pecah_satuan'));
	}



	public function jurnal_pembelian()
	{
		$Tbl_pembelian = $this->Tbl_pembelian_model->get_all();
		$start = 0;
		$data = array(
			'Tbl_pembelian_data' => $Tbl_pembelian,
			'start' => $start,
			'action_by_bulan' => site_url('tbl_pembelian/jurnal_pembelian_per_bulan'),
		);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_jurnal_pembelian', $data);
	}

	public function setting_kode_akun_pembelian2()
	{
		$Tbl_pembelian = $this->Tbl_pembelian_model->get_all();
		$start = 0;
		$data = array(
			'Tbl_pembelian_data' => $Tbl_pembelian,
			'start' => $start,
		);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_setting_kode_akun', $data);
	}
	public function jurnal_pembelian2()
	{
		if ($this->input->post('bulan_ns', TRUE)) {
			$Get_month_selected = date("m", strtotime($this->input->post('bulan_ns', TRUE)));
			$Get_YEAR_selected = date("Y", strtotime($this->input->post('bulan_ns', TRUE)));
		} else {
			// $Get_date_awal = date("Y-m-1 00:00:00");
			// $Get_date_akhir = date("Y-m-t 23:59:59"); // TANGGAL AKHIR BULAN -t
			$Get_month_selected = date("m"); // TANGGAL AKHIR BULAN -t
			$Get_YEAR_selected = date("Y"); // TANGGAL AKHIR BULAN -t
		}

		$GET_Source = "pembelian";
		$sql = "SELECT * FROM `buku_besar` WHERE MONTH(`tanggal`)=$Get_month_selected AND YEAR(`tanggal`)=$Get_YEAR_selected AND `source`='$GET_Source'  ORDER BY `pl`,`tanggal`,`id`";

		$Buku_besar_DATA = $this->db->query($sql)->result();

		// $Buku_besar_DATA = $this->Buku_besar_model->get_by_source($GET_Source);
		// $start = 0;
		$data = array(
			'Buku_besar_DATA_data' => $Buku_besar_DATA,
			// 'start' => $start,
			'month_selected' => $Get_month_selected,
			'year_selected' => $Get_YEAR_selected,
		);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_list__jurnal_pembelian', $data);
	}

	public function jurnal_pembelian2_cekBelumAdaKodeAkun()
	{
		$Tbl_pembelian = $this->Tbl_pembelian_model->get_all();
		$start = 0;
		$data = array(
			'Tbl_pembelian_data' => $Tbl_pembelian,
			'start' => $start,
		);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_list_jurnal', $data);
	}

	public function input_kode_akun($uuid_spop = null)
	{
		// print_r("input_kode_akun");
		// print_r("<br/>");
		// print_r($uuid_spop);
		// die;

		// Update field kode_akun by spop ==> open form input kode akun

		$data_per_uuidspop = $this->Tbl_pembelian_model->get_by_uuid_spop($uuid_spop);

		// $Tbl_pembelian = $this->Tbl_pembelian_model->get_by_spop($spop);
		$Tbl_pembelian = $this->Tbl_pembelian_model->get_by_spop($data_per_uuidspop->spop);

		// SELECT `status_spop` FROM `tbl_pembelian` WHERE `uuid_spop`="53d056417ed111ef95300021ccc9061e";



		// $start = 0;
		$data = array(
			'Tbl_pembelian_data' => $Tbl_pembelian,
			'spop' => $data_per_uuidspop->spop,
			'action' => site_url('tbl_pembelian/update_kode_akun/' . $uuid_spop),
			'button' => 'Simpan Kode AKun',
			// 'start' => $start,
		);
		// print_r($data);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_list_per_spop_kode_akun', $data);
	}

	public function update_kode_akun($uuid_spop = null)
	{
		// print_r("update_kode_akun");
		// die;


		// ================NOTE INPUT KE BUKU BESAR ==================

		// Berarti di form input kode akun pembelian ini:

		// 	pilihan kode akun tetap 1 saja , tinggal nanti pilih :
		// 	11501 - persediaan 
		// 	atau 
		// 	51101 - BPP

		// 	dan ketika di simpan,
		// 	maka debitnya : 11501 atau 51101 DAN kreditnya otomatis 21101

		// ================END OF NOTE INPUT KE BUKU BESAR ==================

		// Cek data di buku_besar
		$data_Pembelian_by_uuid_spop = $this->Tbl_pembelian_model->get_by_uuid_spop_ALL_result($uuid_spop);

		// print_r($uuid_spop);
		// print_r("<br/>");
		// print_r($data_Pembelian_by_uuid_spop);
		// print_r("<br/>");
		// print_r("<br/>");
		// // print_r("<br/>");

		// GET id_buku_besar , jika belum ada maka insert , jika sudah ada maka update di 

		$GET_TOTAL_PEMBELIAN = 0;

		foreach ($data_Pembelian_by_uuid_spop as $list_data) {

			// Tanggal
			if ($GET_tanggal_pembelian) {
			} else {
				$GET_tanggal_pembelian = $list_data->tgl_po;
			}

			// SPOP
			if ($GET_SPOP_pembelian) {
			} else {
				$GET_SPOP_pembelian = $list_data->spop;
			}

			$GET_Supplier = $list_data->supplier_nama;

			// TOTAL PEMBELIAN
			$GET_TOTAL_PEMBELIAN = $GET_TOTAL_PEMBELIAN + $list_data->harga_total;

			// ID BUKU BESAR
			if ($GET_ID_buku_besar) {
			} else {

				$GET_ID_buku_besar = $list_data->id_buku_besar;
			}
		}


		// print_r($list_data->id_buku_besar);
		// print_r("<br/>");
		// die;

		if ($list_data->id_buku_besar or $list_data->id_buku_besar > 0) {
			// print_r("ada ID");
			// proses update di tabel buku besar

			// KODE AKUN PILIHAN DARI FORM INPUT -----------------------------------------------------------------------

			$Get_kode_akun = $this->input->post('kode_akun', TRUE);
			$this->db->where('kode_akun', $Get_kode_akun);
			$GET_DATA_sys_kode_akun = $this->db->get('sys_kode_akun')->row()->nama_akun;
			// print_r("Update data");
			// print_r("<br/>");

			$data = array(
				// 'uuid_buku_besar' => $this->input->post('uuid_buku_besar', TRUE),
				'tanggal' => $GET_tanggal_pembelian,
				'kode_akun' => $this->input->post('kode_akun', TRUE),
				'nama_akun' => $GET_DATA_sys_kode_akun,
				'source' => "pembelian",
				'uuid_spop' => $uuid_spop,
				'spop' => $GET_SPOP_pembelian,
				'supplier' => $GET_Supplier,
				'keterangan' => "Pembelian UUID SPOP" . $uuid_spop . " SPOP: " . $GET_SPOP_pembelian . " " . $this->input->post('kode_bb', TRUE),
				'pl' => $this->input->post('kode_pl', TRUE),
				'kode' => $this->input->post('kode_bb', TRUE),
				'debet' => $GET_TOTAL_PEMBELIAN,
				// 'kredit' => $GET_TOTAL_PEMBELIAN,
				// 'saldo' => $this->input->post('saldo', TRUE),
			);

			// print_r($data);
			// print_r("<br/>");
			// print_r("<br/>");
			// print_r("<br/>");
			// die;

			$this->Buku_besar_model->update($GET_ID_buku_besar, $data);


			// KODE AKUN 21101-UU -----------------------------------------------------------------------


			$Get_kode_akun = 21101;
			$this->db->where('kode_akun', $Get_kode_akun);
			$GET_DATA_sys_kode_akun = $this->db->get('sys_kode_akun')->row()->nama_akun;



			// GET ID BUKU BESAR BY UUID_SPOP DAN KODE AKUN : 21101

			$Get_kode_akun = 21101;
			$this->db->where('kode_akun', $Get_kode_akun);
			$this->db->where('uuid_spop', $uuid_spop);
			$GET_id_buku_besar_by_21101_by_uuid_spop = $this->db->get('buku_besar')->row()->id;




			$data = array(
				// 'uuid_buku_besar' => $this->input->post('uuid_buku_besar', TRUE),
				'tanggal' => $GET_tanggal_pembelian,
				'kode_akun' => 21101,
				'nama_akun' => $GET_DATA_sys_kode_akun,
				'source' => "pembelian",
				'uuid_spop' => $uuid_spop,
				'spop' => $GET_SPOP_pembelian,
				'supplier' => $GET_Supplier,
				'keterangan' => "Pembelian UUID SPOP" . $uuid_spop . " SPOP: " . $GET_SPOP_pembelian . " " . $this->input->post('kode_bb', TRUE),
				'pl' => $this->input->post('kode_pl', TRUE),
				'kode' => $this->input->post('kode_bb', TRUE),
				// 'debet' => $GET_TOTAL_PEMBELIAN,
				'kredit' => $GET_TOTAL_PEMBELIAN,
				// 'saldo' => $this->input->post('saldo', TRUE),
			);
			$this->Buku_besar_model->update($GET_id_buku_besar_by_21101_by_uuid_spop, $data);




			// UPDATE DI TABEL PEMBELIAN
			$data = array(
				'kode_akun' => $this->input->post('kode_akun', TRUE),
				'kode_pl' => $this->input->post('kode_pl', TRUE),
				'kode_bb' => $this->input->post('kode_bb', TRUE),
				// 'id_buku_besar' => $GET_id_buku_besar,
			);

			$this->Tbl_pembelian_model->update_statuslu_per_spop($uuid_spop, $data);

			// print_r($GET_ID_buku_besar);
			// print_r("<br/>");
			// print_r($data);
			// print_r("<br/>");
			// print_r("<br/>");
			// // print_r("<br/>");
			// // die;

		} else {
			// print_r("TIDAK ADA ada ID");
			// Insert data baru di tabel buku besar

			// KODE AKUN PILIHAN DI FORM-------------------------------------------------------------


			$Get_kode_akun = $this->input->post('kode_akun', TRUE);
			$this->db->where('kode_akun', $Get_kode_akun);
			$GET_DATA_sys_kode_akun = $this->db->get('sys_kode_akun')->row()->nama_akun;

			// print_r($GET_DATA_sys_kode_akun);
			// die;

			$data = array(
				// 'uuid_buku_besar' => $this->input->post('uuid_buku_besar', TRUE),
				'tanggal' => $GET_tanggal_pembelian,
				'kode_akun' => $this->input->post('kode_akun', TRUE),
				'nama_akun' => $GET_DATA_sys_kode_akun,
				'source' => "pembelian",
				'uuid_spop' => $uuid_spop,
				'spop' => $GET_SPOP_pembelian,
				'supplier' => $GET_Supplier,
				'keterangan' => "Pembelian UUID SPOP" . $uuid_spop . " SPOP: " . $GET_SPOP_pembelian . " " . $this->input->post('kode_bb', TRUE),
				'pl' => $this->input->post('kode_pl', TRUE),
				'kode' => $this->input->post('kode_bb', TRUE),
				'debet' => $GET_TOTAL_PEMBELIAN,
				// 'kredit' => $GET_TOTAL_PEMBELIAN,
				// 'saldo' => $this->input->post('saldo', TRUE),
			);

			$GET_id_buku_besar = $this->Buku_besar_model->insert($data);


			// KODE AKUN 21101-UU-----------------------------------------------------------------------

			$Get_kode_akun = 21101;
			$this->db->where('kode_akun', $Get_kode_akun);
			$GET_DATA_sys_kode_akun = $this->db->get('sys_kode_akun')->row()->nama_akun;

			// print_r($GET_DATA_sys_kode_akun);
			// die;



			$data = array(
				// 'uuid_buku_besar' => $this->input->post('uuid_buku_besar', TRUE),
				'tanggal' => $GET_tanggal_pembelian,
				'kode_akun' => 21101,
				'nama_akun' => $GET_DATA_sys_kode_akun,
				'source' => "pembelian",
				'uuid_spop' => $uuid_spop,
				'spop' => $GET_SPOP_pembelian,
				'supplier' => $GET_Supplier,
				'keterangan' => "Pembelian UUID SPOP" . $uuid_spop . " SPOP: " . $GET_SPOP_pembelian . " " . $this->input->post('kode_bb', TRUE),
				'pl' => $this->input->post('kode_pl', TRUE),
				'kode' => $this->input->post('kode_bb', TRUE),
				// 'debet' => $GET_TOTAL_PEMBELIAN,
				'kredit' => $GET_TOTAL_PEMBELIAN,
				// 'saldo' => $this->input->post('saldo', TRUE),
			);

			$this->Buku_besar_model->insert($data);


			// UPDATE DI TABEL PEMBELIAN
			$data = array(
				'kode_akun' => $this->input->post('kode_akun', TRUE),
				'kode_pl' => $this->input->post('kode_pl', TRUE),
				'kode_bb' => $this->input->post('kode_bb', TRUE),
				'id_buku_besar' => $GET_id_buku_besar,
			);

			$this->Tbl_pembelian_model->update_statuslu_per_spop($uuid_spop, $data);
		}

		// print_r("ID buku besar: ");
		// print_r($GET_id_buku_besar);
		// die;




		// Cek di tabel buku besar , jika belum ada data maka insert , jika sudah ada maka update


		redirect(site_url('Tbl_pembelian/setting_kode_akun_pembelian2/'));
	}

	public function ubah_kode_akun($uuid_spop = null)
	{

		$data_per_uuidspop = $this->Tbl_pembelian_model->get_by_uuid_spop($uuid_spop);

		// $Tbl_pembelian = $this->Tbl_pembelian_model->get_by_spop($spop);
		$Tbl_pembelian = $this->Tbl_pembelian_model->get_by_spop($data_per_uuidspop->spop);

		// SELECT `status_spop` FROM `tbl_pembelian` WHERE `uuid_spop`="53d056417ed111ef95300021ccc9061e";

		// print_r($Tbl_pembelian);
		// print_r("<br/>");
		// print_r("<br/>");

		// print_r($Tbl_pembelian->kode_akun);
		// print_r("<br/>");
		// print_r("<br/>");


		$sql = "SELECT `spop`,`kode_akun`,`kode_pl`,`kode_bb` FROM `tbl_pembelian` WHERE `uuid_spop`='$uuid_spop' GROUP by `uuid_spop`,`kode_akun`";

		// $this->db->query($sql)->result();
		// print_r($this->db->query($sql)->row()->kode_akun);

		$get_kode_akun = $this->db->query($sql)->row()->kode_akun;
		$get_kode_pl = $this->db->query($sql)->row()->kode_pl;
		$get_kode_bb = $this->db->query($sql)->row()->kode_bb;
		// die;

		$start = 0;
		$data = array(
			'Tbl_pembelian_data' => $Tbl_pembelian,
			'spop' => $data_per_uuidspop->spop,
			'action' => site_url('tbl_pembelian/update_kode_akun/' . $uuid_spop),
			'button' => 'Update Kode AKun',
			'start' => $start,
			'get_kode_akun' => $get_kode_akun,
			'get_kode_pl' => $get_kode_pl,
			'get_kode_bb' => $get_kode_bb,
		);
		// print_r($data);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_tbl_pembelian_list_per_spop_kode_akun', $data);
	}

	public function jurnal_pembelian_per_bulan()
	{
		// print_r("jurnal_pembelian_per_bulan");
		// print_r("<br/>");
		// print_r($this->input->post('bulan_pembelian', TRUE));
		// print_r("<br/>");
		// print_r(date("Y-m-1", strtotime($this->input->post('bulan_pembelian', TRUE))));
		// print_r("<br/>");
		// print_r(date("Y-m-t", strtotime($this->input->post('bulan_pembelian', TRUE))));


		// print_r("<br/>");

		$from_date = date("Y-m-1", strtotime($this->input->post('bulan_pembelian', TRUE)));
		$to_date = date("Y-m-t", strtotime($this->input->post('bulan_pembelian', TRUE)));

		$sql = "SELECT * FROM tbl_pembelian WHERE tgl_po >= '" . $from_date . "' AND tgl_po <= '" . $to_date . "' ORDER by id DESC";

		// $this->db->query($sql)->result();
		// print_r($this->db->query($sql)->result());

		// die;

		// $this->input->post('bulan_pembelian', TRUE)

		$Tbl_pembelian = $this->db->query($sql)->result();
		$start = 0;
		$data = array(
			'Tbl_pembelian_data' => $Tbl_pembelian,
			'start' => $start,
			'action_by_bulan' => site_url('tbl_pembelian/jurnal_pembelian_per_bulan'),
		);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_jurnal_pembelian', $data);
	}


	public function kas_kecil()
	{


		$sql = "SELECT * FROM tbl_pembelian where kas_bank='kas' union ALL SELECT * FROM tbl_pembelian_kas_kecil ";

		// 		print_r($this->db->query($sql)->result());
		// die;



		$Tbl_pembelian = $this->db->query($sql)->result();
		$start = 0;
		$data = array(
			'bulan_data' => date("Y-m-d"),
			'Tbl_pembelian_data' => $Tbl_pembelian,
			'start' => $start,
			'action_by_bulan' => site_url('tbl_pembelian/kas_kecil_per_bulan'),
		);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_kas_kecil', $data);
	}


	public function kas_kecil_per_bulan()
	{
		// print_r("jurnal_pembelian_per_bulan");
		// print_r("<br/>");
		// print_r($this->input->post('bulan_pembelian', TRUE));
		// print_r("<br/>");
		// print_r(date("Y-m-1", strtotime($this->input->post('bulan_pembelian', TRUE))));
		// print_r("<br/>");
		// print_r(date("Y-m-t", strtotime($this->input->post('bulan_pembelian', TRUE))));


		// print_r("<br/>");

		$from_date = date("Y-m-1", strtotime($this->input->post('bulan_pembelian', TRUE)));
		$to_date = date("Y-m-t", strtotime($this->input->post('bulan_pembelian', TRUE)));

		// $sql = "SELECT * FROM tbl_pembelian WHERE tgl_po >= '" . $from_date . "' AND tgl_po <= '" . $to_date . "' ORDER by id DESC";

		$sql = "SELECT * FROM tbl_pembelian where kas_bank='kas' and tgl_po >= '" . $from_date . "' AND tgl_po <= '" . $to_date . "' UNION ALL SELECT * FROM tbl_pembelian_kas_kecil where  tgl_po >= '" . $from_date . "' AND tgl_po <= '" . $to_date . "'";

		$Tbl_pembelian = $this->db->query($sql)->result();
		$start = 0;
		$data = array(
			'bulan_data' => $this->input->post('bulan_pembelian', TRUE),
			'Tbl_pembelian_data' => $Tbl_pembelian,
			'start' => $start,
			'action_by_bulan' => site_url('tbl_pembelian/kas_kecil_per_bulan'),
		);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pembelian/adminlte310_kas_kecil', $data);
	}

	public function buku_kas()
	{

		$from_date = date("Y-m-1", strtotime(date("Y-m-d")));
		$to_date = date("Y-m-t", strtotime(date("Y-m-d")));


		$sql = "SELECT tgl_po as tgl_transaksi, uraian as nama_barang,proses_transaksi,kode_akun,jumlah,harga_satuan FROM tbl_pembelian where tgl_po >= '" . $from_date . "' AND tgl_po <= '" . $to_date . "' UNION ALL SELECT tgl_jual as tgl_transaksi, nama_barang as nama_barang,proses_transaksi,kode_akun,jumlah,harga_satuan FROM tbl_penjualan  where  tgl_jual >= '" . $from_date . "' AND tgl_jual <= '" . $to_date . "'  order by tgl_transaksi";

		$buku_kas_data = $this->db->query($sql)->result();

		// print_r($buku_kas_data);
		// die;

		$data = array(
			'buku_kas_data' => $buku_kas_data,
			'action_by_bulan' => site_url('tbl_pembelian/buku_kas_per_bulan'),
		);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/bukukas/adminlte310_buku_kas_list', $data);
	}
	public function buku_kas_per_bulan()
	{

		$from_date = date("Y-m-1", strtotime($this->input->post('bulan_pembelian', TRUE)));
		$to_date = date("Y-m-t", strtotime($this->input->post('bulan_pembelian', TRUE)));



		$sql = "SELECT tgl_po as tgl_transaksi, uraian as nama_barang,proses_transaksi FROM tbl_pembelian where tgl_po >= '" . $from_date . "' AND tgl_po <= '" . $to_date . "' UNION ALL SELECT tgl_jual as tgl_transaksi, nama_barang as nama_barang,proses_transaksi FROM tbl_penjualan  where  tgl_jual >= '" . $from_date . "' AND tgl_jual <= '" . $to_date . "'  order by tgl_transaksi";

		$buku_kas_data = $this->db->query($sql)->result();

		// print_r($buku_kas_data);
		// die;

		$data = array(
			'buku_kas_data' => $buku_kas_data,
			'action_by_bulan' => site_url('tbl_pembelian/buku_kas_per_bulan'),
		);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/bukukas/adminlte310_buku_kas_list', $data);
	}
}

/* End of file Tbl_pembelian.php */
/* Location: ./application/controllers/Tbl_pembelian.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-09-03 14:57:23 */
/* http://harviacode.com */