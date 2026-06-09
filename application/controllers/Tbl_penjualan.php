<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Tbl_penjualan extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_login();
		$this->load->model(array('Tbl_penjualan_model', 'Tbl_pembelian_model', 'Sys_konsumen_model', 'Sys_unit_model', 'Sys_nama_barang_model', 'Persediaan_model', 'Buku_besar_model'));
		$this->load->library('form_validation');
		$this->load->library('form_validation');
		$this->load->library('datatables');
		$this->load->library('Pdf');
		$this->load->helper(array('nominal', 'pembelian_persediaan'));
		// $this->load->helper('number');
	}






	private function _parse_cari_between_dates($tgl_awal_input, $tgl_akhir_input)
	{
		if (date('Y', strtotime($tgl_awal_input)) < 2020) {
			$Get_date_awal = date('Y-m-d', strtotime('-1 day'));
		} else {
			$Get_date_awal = date('Y-m-d 00:00:00', strtotime($tgl_awal_input));
		}

		if (date('Y', strtotime($tgl_akhir_input)) < 2020) {
			$Get_date_akhir = date('Y-m-d 23:59:59');
		} else {
			$Get_date_akhir = date('Y-m-d 23:59:59', strtotime($tgl_akhir_input));
		}

		return array($Get_date_awal, $Get_date_akhir);
	}

	private function _set_filter_session_penjualan($date_awal, $date_akhir, $tgl_awal_display = null, $tgl_akhir_display = null, $rows = null)
	{
		$this->session->set_userdata('filter_tbl_penjualan_date_awal', $date_awal);
		$this->session->set_userdata('filter_tbl_penjualan_date_akhir', $date_akhir);
		if ($tgl_awal_display !== null && $tgl_akhir_display !== null) {
			$this->session->set_userdata('filter_tbl_penjualan_tgl_awal_display', $tgl_awal_display);
			$this->session->set_userdata('filter_tbl_penjualan_tgl_akhir_display', $tgl_akhir_display);
		}
		if ($rows !== null) {
			$this->session->set_userdata('filter_tbl_penjualan_ids', $this->_collect_row_ids($rows));
		}
	}

	private function _collect_row_ids($rows)
	{
		$ids = array();
		foreach ($rows as $row) {
			if (isset($row->id)) {
				$ids[] = (int) $row->id;
			}
		}
		return $ids;
	}

	private function _get_penjualan_rows_for_excel_by_ids(array $ids, $preserve_request_order = false)
	{
		$ids = array_values(array_filter(array_map('intval', $ids)));
		if (empty($ids)) {
			return array();
		}

		$this->db->from('tbl_penjualan');
		$this->db->where_in('id', $ids);
		$rows = $this->db->get()->result();

		if (!$preserve_request_order) {
			usort($rows, function ($a, $b) {
				$c = strcmp((string) $a->tgl_jual, (string) $b->tgl_jual);
				if ($c !== 0) {
					return $c;
				}
				$c = strcmp((string) $a->nmrkirim, (string) $b->nmrkirim);
				if ($c !== 0) {
					return $c;
				}
				return (int) $a->id - (int) $b->id;
			});
			return $rows;
		}

		$map = array();
		foreach ($rows as $row) {
			$map[(int) $row->id] = $row;
		}

		$ordered = array();
		foreach ($ids as $id) {
			if (isset($map[$id])) {
				$ordered[] = $map[$id];
			}
		}

		return $ordered;
	}

	private function _penjualan_hitung_kolom_tampilan($data)
	{
		$jumlah_total = (float) $data->jumlah * (float) $data->harga_satuan;
		$umpphpsl22 = ($jumlah_total * 1.351351) / 100;
		$piutang = $jumlah_total - (($jumlah_total * 11.261261) / 100);
		$penjualandpp = ($jumlah_total * 90.090090) / 100;
		$utangppn = ($jumlah_total * 9.909910) / 100;

		return array(
			'jumlah_total' => $jumlah_total,
			'umpphpsl22' => $umpphpsl22,
			'piutang' => $piutang,
			'penjualandpp' => $penjualandpp,
			'utangppn' => $utangppn,
		);
	}

	/**
	 * @return array [sql_awal, sql_akhir, tampil_awal, tampil_akhir]
	 */
	private function _resolve_penjualan_filter_dates()
	{
		$tgl_awal_in = $this->input->get_post('tgl_awal', TRUE);
		$tgl_akhir_in = $this->input->get_post('tgl_akhir', TRUE);
		if (!empty($tgl_awal_in) && !empty($tgl_akhir_in)) {
			list($awal, $akhir) = $this->_parse_cari_between_dates($tgl_awal_in, $tgl_akhir_in);
			$this->_set_filter_session_penjualan($awal, $akhir, $tgl_awal_in, $tgl_akhir_in);
			return array($awal, $akhir, $tgl_awal_in, $tgl_akhir_in);
		}

		$awal = $this->session->userdata('filter_tbl_penjualan_date_awal');
		$akhir = $this->session->userdata('filter_tbl_penjualan_date_akhir');
		$disp_awal = $this->session->userdata('filter_tbl_penjualan_tgl_awal_display');
		$disp_akhir = $this->session->userdata('filter_tbl_penjualan_tgl_akhir_display');
		if ($awal && $akhir) {
			if (!$disp_awal) {
				$disp_awal = date('j-n-Y', strtotime($awal));
				$disp_akhir = date('j-n-Y', strtotime($akhir));
			}
			return array($awal, $akhir, $disp_awal, $disp_akhir);
		}

		$awal = date('Y-m-01 00:00:00');
		$akhir = date('Y-m-t 23:59:59');
		$disp_awal = date('j-n-Y', strtotime($awal));
		$disp_akhir = date('j-n-Y', strtotime($akhir));
		$this->_set_filter_session_penjualan($awal, $akhir, $disp_awal, $disp_akhir);
		return array($awal, $akhir, $disp_awal, $disp_akhir);
	}

	private function _rekap_order_field($field_rekap)
	{
		if ($field_rekap === 'unit') {
			return 'unit';
		}
		if ($field_rekap === 'konsumen_nama' || $field_rekap === 'konsumen') {
			return 'konsumen_nama';
		}
		if ($field_rekap === 'nama_barang') {
			return 'nama_barang';
		}
		return 'unit';
	}

	/**
	 * Opsi combobox filter rekap (unit / konsumen / barang) dalam rentang tanggal.
	 */
	private function _get_rekap_filter_options($date_awal, $date_akhir, $field_rekap)
	{
		$col = 'unit';
		if ($field_rekap === 'konsumen_nama' || $field_rekap === 'konsumen') {
			$col = 'konsumen_nama';
		} elseif ($field_rekap === 'nama_barang') {
			$col = 'nama_barang';
		}

		$this->db->distinct();
		$this->db->select($col . ' AS val', false);
		$this->db->from('tbl_penjualan');
		$this->db->where('tgl_jual >=', $date_awal);
		$this->db->where('tgl_jual <=', $date_akhir);
		$this->db->order_by($col, 'ASC');
		$rows = $this->db->get()->result();

		$options = array();
		foreach ($rows as $row) {
			$val = isset($row->val) ? trim((string) $row->val) : '';
			if ($val !== '') {
				$options[] = $val;
			}
		}

		return $options;
	}

	private function _get_penjualan_between($date_awal, $date_akhir, $order_field = null)
	{
		$this->db->where('tgl_jual >=', $date_awal);
		$this->db->where('tgl_jual <=', $date_akhir);
		if ($order_field) {
			$this->db->order_by($order_field, 'ASC');
			$this->db->order_by('tgl_jual', 'ASC');
			$this->db->order_by('nmrkirim', 'ASC');
		} else {
			$this->db->order_by('tgl_jual', 'ASC');
			$this->db->order_by('nmrkirim', 'ASC');
			$this->db->order_by('id', 'ASC');
		}
		return $this->db->get('tbl_penjualan')->result();
	}

	private function _penjualan_filter_query_string($tgl_awal_display, $tgl_akhir_display)
	{
		return '?tgl_awal=' . rawurlencode($tgl_awal_display) . '&tgl_akhir=' . rawurlencode($tgl_akhir_display);
	}

	public function index()
	{
		list($Get_date_awal, $Get_date_akhir, $disp_awal, $disp_akhir) = $this->_resolve_penjualan_filter_dates();
		penjualan_set_list_bulan_context($this, $disp_awal, $disp_akhir);

		$Tbl_penjualan_data = $this->_get_penjualan_between($Get_date_awal, $Get_date_akhir);
		$this->_set_filter_session_penjualan($Get_date_awal, $Get_date_akhir, $disp_awal, $disp_akhir, $Tbl_penjualan_data);

		$data = array(
			'Tbl_penjualan_data' => $Tbl_penjualan_data,
			// 'q' => $q,
			// 'pagination' => $this->pagination->create_links(),
			// 'total_rows' => $config['total_rows'],
			// 'start' => $start,
			'date_awal' => $Get_date_awal,
			'date_akhir' => $Get_date_akhir,
		);


		// $this->load->view('anekadharma/tbl_penjualan/tbl_penjualan_list', $data);		
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_list', $data);
	}

	public function cari_between_date()
	{
		$tgl_awal_raw = $this->input->post('tgl_awal', TRUE);
		$tgl_akhir_raw = $this->input->post('tgl_akhir', TRUE);
		list($Get_date_awal, $Get_date_akhir) = $this->_parse_cari_between_dates($tgl_awal_raw, $tgl_akhir_raw);

		penjualan_set_list_bulan_context($this, $tgl_awal_raw, $tgl_akhir_raw);
		$Tbl_penjualan_data = $this->_get_penjualan_between($Get_date_awal, $Get_date_akhir);
		$this->_set_filter_session_penjualan($Get_date_awal, $Get_date_akhir, $tgl_awal_raw, $tgl_akhir_raw, $Tbl_penjualan_data);

		$data = array(
			'Tbl_penjualan_data' => $Tbl_penjualan_data,
			// 'q' => $q,
			// 'pagination' => $this->pagination->create_links(),
			// 'total_rows' => $config['total_rows'],
			// 'start' => $start,
			'date_awal' => $Get_date_awal,
			'date_akhir' => $Get_date_akhir,
		);


		// $this->load->view('anekadharma/tbl_penjualan/tbl_penjualan_list', $data);		
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_list', $data);
	}


	public function RekapPenjualanPerBarang()
	{
		// $sql_stock = "SELECT persediaan.id as id_persediaan_barang, persediaan.uuid_barang as uuid_barang, persediaan.kode_barang as kode_barang, persediaan.namabarang as nama_barang_beli,persediaan.total_10 as jumlah_sediaan,  persediaan.hpp as harga_satuan_persediaan,  persediaan.satuan as satuan,
		// 	-- 	tbl_pembelian.uuid_pembelian as uuid_pembelian,tbl_pembelian.uraian as barang_beli, tbl_pembelian.jumlah as jumlah_belanja, tbl_pembelian.harga_satuan as harga_satuan_beli,  tbl_pembelian.tgl_po as tgl_po,tbl_pembelian.uuid_gudang as uuid_gudang, tbl_pembelian.nama_gudang as nama_gudang,  tbl_pembelian.satuan as satuan,
		// 	-- tbl_penjualan.nama_barang as barang_jual, tbl_penjualan.jumlah as jumlah_terjual
		// 	persediaan.penjualan as penjualan
		// 	FROM persediaan  
		// 	-- left join tbl_pembelian ON persediaan.uuid_barang = tbl_pembelian.uuid_barang 
		// 	-- left join tbl_penjualan ON persediaan.uuid_barang = tbl_penjualan.uuid_barang  
		// 	Full Outer Join tbl_penjualan ON persediaan.uuid_barang = tbl_penjualan.uuid_barang  
		// 	-- WHERE (persediaan.uuid_barang, persediaan.tanggal) IN (SELECT persediaan.uuid_barang, Max(persediaan.tanggal) FROM persediaan 

		// 	-- GROUP BY persediaan.uuid_barang)  

		// 	ORDER BY persediaan.namabarang ASC";


		$sql_persediaan = "SELECT persediaan.id as id,
								persediaan.uuid_persediaan as uuid_persediaan,
								persediaan.namabarang as namabarang_persediaan, 
								persediaan.sa as saldo_awal_persediaan, 
								persediaan.spop as spop, 

		 						tbl_penjualan.id_persediaan_barang as id_persediaan_barang,
		 						tbl_penjualan.tgl_jual as tgl_jual_penjualan,
		 						tbl_penjualan.nmrkirim as nmrkirim_penjualan,
		 						tbl_penjualan.uuid_konsumen as uuid_konsumen_penjualan,
		 						tbl_penjualan.konsumen_nama as konsumen_nama_penjualan,
		 						tbl_penjualan.nama_barang as nama_barang_penjualan,
		 						tbl_penjualan.jumlah as jumlah_penjualan,
		 						tbl_penjualan.harga_satuan as harga_satuan_penjualan,
		 						tbl_penjualan.uuid_persediaan as uuid_persediaan_penjualan
							FROM persediaan
							right JOIN  tbl_penjualan ON persediaan.id= tbl_penjualan.id_persediaan_barang
							-- group by persediaan.namabarang, tbl_penjualan.nama_barang
							ORDER BY tbl_penjualan.tgl_jual DESC, tbl_penjualan.nama_barang ASC, tbl_penjualan.nmrkirim DESC;";

		// print_r($this->db->query($sql_persediaan)->num_rows());
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");

		$data_penjualan_per_barang = $this->db->query($sql_persediaan)->result();
		// print_r($this->db->query($sql_persediaan)->result());
		// die;


		$start = 0;
		// print_r($Tbl_pembelian);
		// print_r("<br/>");
		// print_r("<br/>");

		$data = array(
			'Tbl_penjualan_data' => $this->db->query($sql_persediaan)->result(),
			// 'q' => $q,
			// 'pagination' => $this->pagination->create_links(),
			// 'total_rows' => $config['total_rows'],
			'start' => $start,

			// 'action_cari_konsumen' => site_url('tbl_penjualan/rekap'),
			// 'data_selection' => $data_selection,
			// 'nama_konsumen_selection' => $nama_konsumen_selection,
		);


		// $this->load->view('anekadharma/tbl_penjualan/tbl_penjualan_list', $data);		
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_list_rekap', $data);
	}

	public function RekapPenjualanPerKonsumen()
	{

		$sql_persediaan = "SELECT 
		-- persediaan.id as id,
		-- 						persediaan.uuid_persediaan as uuid_persediaan,
		-- 						persediaan.namabarang as namabarang_persediaan, 
		-- 						persediaan.sa as saldo_awal_persediaan, 
		-- 						persediaan.spop as spop, 

		 						tbl_penjualan.id_persediaan_barang as id_persediaan_barang,
		 						tbl_penjualan.tgl_jual as tgl_jual_penjualan,
		 						tbl_penjualan.nmrkirim as nmrkirim_penjualan,
		 						tbl_penjualan.uuid_konsumen as uuid_konsumen_penjualan,
		 						tbl_penjualan.konsumen_nama as konsumen_nama_penjualan,
		 						tbl_penjualan.nama_barang as nama_barang_penjualan,
		 						tbl_penjualan.jumlah as jumlah_penjualan,
		 						tbl_penjualan.harga_satuan as harga_satuan_penjualan,
		 						tbl_penjualan.uuid_persediaan as uuid_persediaan_penjualan
							FROM tbl_penjualan
							-- right JOIN  tbl_penjualan ON persediaan.id= tbl_penjualan.id_persediaan_barang
							GROUP BY tbl_penjualan.uuid_konsumen
							ORDER BY tbl_penjualan.konsumen_nama ASC;";

		// $data_penjualan_per_barang = $this->db->query($sql_persediaan)->result();

		$data = array(
			'Tbl_penjualan_data' => $this->db->query($sql_persediaan)->result(),
		);

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_list_rekap_per_konsumen', $data);
	}

	public function RekapData($field_rekap = null)
	{
		list($Get_date_awal, $Get_date_akhir, $tgl_awal_param, $tgl_akhir_param) = $this->_resolve_penjualan_filter_dates();
		$order_field = $this->_rekap_order_field($field_rekap);
		$field_rekap = $order_field;
		$filter_qs = $this->_penjualan_filter_query_string($tgl_awal_param, $tgl_akhir_param);
		$Tbl_penjualan_data = $this->_get_penjualan_between($Get_date_awal, $Get_date_akhir, $order_field);
		$this->_set_filter_session_penjualan($Get_date_awal, $Get_date_akhir, $tgl_awal_param, $tgl_akhir_param, $Tbl_penjualan_data);

		$data = array(
			'Tbl_penjualan_data' => $Tbl_penjualan_data,
			'field_rekap' => $field_rekap,
			'date_awal' => $Get_date_awal,
			'date_akhir' => $Get_date_akhir,
			'tgl_awal_param' => $tgl_awal_param,
			'tgl_akhir_param' => $tgl_akhir_param,
			'filter_query_string' => $filter_qs,
			'rekap_filter_options' => $this->_get_rekap_filter_options($Get_date_awal, $Get_date_akhir, $field_rekap),
		);

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_list_rekap_data', $data);
	}

	/**
	 * Export rekap penjualan (.xlsx) sesuai baris yang tampil di DataTable (sort + filter).
	 */
	public function excel_rekap_data()
	{
		$this->load->helper('exportexcel');

		$field_rekap = $this->input->get_post('field_rekap', TRUE);
		$from_datatable = ($this->input->get_post('from_datatable', TRUE) === '1');
		if (!$from_datatable) {
			show_error('Export rekap harus dari tampilan DataTable.', 400);
			return;
		}

		$rows_json = $this->input->post('export_rows', FALSE);
		if ($rows_json === null || $rows_json === '') {
			show_error('Tidak ada data rekap untuk diekspor sesuai tampilan DataTable.', 400);
			return;
		}

		$rows = json_decode($rows_json, true);
		if (!is_array($rows)) {
			show_error('Format data export rekap tidak valid.', 400);
			return;
		}

		$headers_json = $this->input->post('export_headers', FALSE);
		$headers = array();
		if ($headers_json !== null && $headers_json !== '') {
			$decoded_headers = json_decode($headers_json, true);
			if (is_array($decoded_headers)) {
				$headers = $decoded_headers;
			}
		}

		$field_label = preg_replace('/[^a-z0-9_]+/i', '_', (string) $field_rekap);
		if ($field_label === '') {
			$field_label = 'rekap';
		}
		$namaFile = 'Rekap_penjualan_' . $field_label . '_' . date('Y-m-d_H-i-s') . '.xlsx';

		excel_prepare_download($namaFile);
		xlsBOF();

		$tablehead = 0;
		$tablebody = 1;

		if (!empty($headers)) {
			$kolomhead = 0;
			foreach ($headers as $header) {
				xlsWriteLabel($tablehead, $kolomhead++, (string) $header);
			}
		}

		foreach ($rows as $rowCells) {
			if (!is_array($rowCells)) {
				continue;
			}
			$kolombody = 0;
			foreach ($rowCells as $cell) {
				$val = trim((string) $cell);
				if ($val !== '' && $this->_excel_cell_looks_numeric($val)) {
					xlsWriteNumber($tablebody, $kolombody++, $this->_excel_parse_numeric($val));
				} else {
					xlsWriteLabel($tablebody, $kolombody++, $val);
				}
			}
			$tablebody++;
		}

		xlsEOF();
		exit();
	}

	private function _excel_cell_looks_numeric($value)
	{
		$normalized = str_replace(array(' ', '.'), '', (string) $value);
		$normalized = str_replace(',', '.', $normalized);
		return is_numeric($normalized);
	}

	private function _excel_parse_numeric($value)
	{
		$normalized = str_replace(' ', '', (string) $value);
		if (strpos($normalized, ',') !== false && strpos($normalized, '.') !== false) {
			$normalized = str_replace('.', '', $normalized);
			$normalized = str_replace(',', '.', $normalized);
		} elseif (strpos($normalized, ',') !== false) {
			$normalized = str_replace(',', '.', $normalized);
		}
		return (float) $normalized;
	}

	public function bayar()
	{

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/pembayaran/bayar_form');
	}

	public function accounting()
	{

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/accounting/accounting_form');
	}


	public function bukukas()
	{

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/bukukas/bukukas_form');
	}

	public function kaskecil()
	{

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/kaskecil/kaskecil_form');
	}

	public function neraca()
	{

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/neraca/neraca_form');
	}

	public function labarugi()
	{

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/labarugi/labarugi_form');
	}

	public function read($id)
	{
		$row = $this->Tbl_penjualan_model->get_by_id($id);
		if ($row) {
			$data = array(
				'id' => $row->id,
				'tgl_input' => $row->tgl_input,
				'nmrpesan' => $row->nmrpesan,
				'nmrkirim' => $row->nmrkirim,
				'konsumen_id' => $row->konsumen_id,
				'konsumen_nama' => $row->konsumen_nama,
				'kode_barang' => $row->kode_barang,
				'nama_barang' => $row->nama_barang,
				'unit' => $row->unit,
				'satuan' => $row->satuan,
				'harga_satuan' => $row->harga_satuan,
				'jumlah' => $row->jumlah,
				'umpphpsl22' => $row->umpphpsl22,
				'piutang' => $row->piutang,
				'penjualandpp' => $row->penjualandpp,
				'utangppn' => $row->utangppn,
				'id_usr' => $row->id_usr,
			);
			$this->load->view('anekadharma/tbl_penjualan/tbl_penjualan_read', $data);
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(site_url('tbl_penjualan'));
		}
	}

	public function create()
	{
		$tgl_awal_in = $this->input->get('tgl_awal', TRUE);
		$tgl_akhir_in = $this->input->get('tgl_akhir', TRUE);
		if (!empty($tgl_awal_in) && !empty($tgl_akhir_in)) {
			penjualan_set_list_bulan_context($this, $tgl_awal_in, $tgl_akhir_in);
		} else {
			$disp_awal = $this->session->userdata('filter_tbl_penjualan_tgl_awal_display');
			$disp_akhir = $this->session->userdata('filter_tbl_penjualan_tgl_akhir_display');
			if ($disp_awal && $disp_akhir) {
				penjualan_set_list_bulan_context($this, $disp_awal, $disp_akhir);
			}
		}

		$list_ctx = penjualan_get_list_bulan_context($this);
		$default_tgl_jual = set_value('tgl_jual');
		if ($default_tgl_jual === '') {
			$default_tgl_jual = penjualan_get_default_tgl_jual_dari_filter_list($this, $tgl_awal_in, $tgl_akhir_in);
		}

		$data = array(
			'button' => 'Simpan',
			'action' => site_url('tbl_penjualan/create_action'),
			'id' => set_value('id'),
			'tgl_input' => set_value('tgl_input'),
			'tgl_jual' => $default_tgl_jual,
			'penjualan_list_bulan_key' => $list_ctx['bulan_key'],
			'penjualan_list_bulan_label' => $list_ctx['bulan_label'],
			'nmrpesan' => set_value('nmrpesan'),
			'nmrkirim' => set_value('nmrkirim'),
			'konsumen_id' => set_value('konsumen_id'),
			'konsumen_nama' => set_value('konsumen_nama'),
			'kode_barang' => set_value('kode_barang'),
			'nama_barang' => set_value('nama_barang'),
			'unit' => set_value('unit'),
			'satuan' => set_value('satuan'),
			'harga_satuan' => set_value('harga_satuan'),
			'jumlah' => set_value('jumlah'),
			'umpphpsl22' => set_value('umpphpsl22'),
			'piutang' => set_value('piutang'),
			'penjualandpp' => set_value('penjualandpp'),
			'utangppn' => set_value('utangppn'),
			'id_usr' => set_value('id_usr'),
		);

		// $this->load->view('anekadharma/tbl_penjualan/tbl_penjualan_form', $data);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_form', $data);
	}

	public function create_action()
	{
		$this->_rules();

		if ($this->form_validation->run() == FALSE) {
			$this->create();
		} else {


			// GET KONSUMEN DATA


			// =================

			// unIT
			$this->db->where('uuid_unit', $this->input->post('uuid_unit', TRUE));
			$sys_unit_data = $this->db->get('sys_unit');

			// print_r($sys_unit_data);
			// print_r("<br/>");

			if ($sys_unit_data->num_rows() > 0) {

				$Get_unit_data = $sys_unit_data->row_array();

				$Get_uuid_unit = $this->input->post('uuid_unit', TRUE);
				$Get_kode_unit = $Get_unit_data['kode_unit'];
				$Get_nama_unit = $Get_unit_data['nama_unit'];
			}

			// print_r($Get_kode_unit);
			// print_r("<br/>");
			// print_r($Get_nama_unit);
			// print_r("<br/>");



			// KONSUMEN
			$this->db->where('uuid_konsumen', $this->input->post('uuid_konsumen', TRUE));
			$sys_konsumen_data = $this->db->get('sys_konsumen');

			if ($sys_konsumen_data->num_rows() > 0) {
				// Konsumen dari sys_konsumen
				$get_uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
				$sql_uuid_konsumen = "SELECT * FROM `sys_konsumen` WHERE `uuid_konsumen`='$get_uuid_konsumen'";
				// $get_kode_konsumen = $this->db->query($sql_uuid_konsumen)->row()->kode_konsumen;
				$get_nama_konsumen = $this->db->query($sql_uuid_konsumen)->row()->nama_konsumen;
			} else {
				// Konsumen dari unit

				// $uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
				// $data_konsumen = $this->Sys_unit_model->get_by_uuid_unit($uuid_konsumen);
				// $data_nama_konsumen = $data_konsumen->nama_unit;


				$get_uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
				$sql_uuid_konsumen = "SELECT * FROM `sys_unit` WHERE `uuid_unit`='$get_uuid_konsumen'";
				// $get_kode_konsumen = $this->db->query($sql_uuid_konsumen)->row()->kode_konsumen;
				$get_nama_konsumen = $this->db->query($sql_uuid_konsumen)->row()->nama_unit;
			}







			if (date("Y", strtotime($this->input->post('tgl_jual', TRUE))) < 2020) {
				// print_r("Tahun kurang dari 2020");
				$date_tgl_jual = date("Y-m-d H:i:s");
			} else {
				// print_r("Tahun lebih dari 2020");
				$date_tgl_jual = date("Y-m-d H:i:s", strtotime($this->input->post('tgl_jual', TRUE)));
			}


			if (isset($_POST["namabarang"])) {
				$loopdata = $_POST["namabarang"];
				reset($loopdata);
				while (list($key, $value) = each($loopdata)) {
					$namabarang   = $_POST["namabarang"][$key];
					$unit     = $_POST["unit"][$key];
					$satuan     = $_POST["satuan"][$key];
					$hargasatuan     = $_POST["hargasatuan"][$key];

					// $sql_loopdata   = "INSERT INTO tbl_loopdata(rincian_loopdata,jenis_loopdata,id_karyawan)
					//   VALUES('$rincian_loopdata','$jenis_loopdata','$id_karyawan')";
					// $hasil_loopdata = mysql_query($sql_loopdata);

					$data = array(
						'tgl_input' => date("Y-m-d H:i:s"),
						'tgl_jual' => $date_tgl_jual,
						'nmrpesan' => $this->input->post('nmrpesan', TRUE),
						'nmrkirim' => $this->input->post('nmrkirim', TRUE),
						'uuid_konsumen' => $get_uuid_konsumen,
						'konsumen_nama' => $get_nama_konsumen,
						'nama_barang' => $namabarang,
						'uuid_unit' => $Get_uuid_unit,
						'unit' => $Get_nama_unit,
						'satuan' => $satuan,

						'harga_satuan' => preg_replace("/[^0-9]/", "", $hargasatuan),
						'id_usr' => 1,
					);

					// print_r($data);
					// // print_r("<br/>");
					// // print_r("<br/>");
					// die;
					$this->Tbl_penjualan_model->insert($data);
				}
			}



			// $data = array(
			// 	'tgl_jual' => $this->input->post('tgl_jual', TRUE),
			// 	'nmrpesan' => $this->input->post('nmrpesan', TRUE),
			// 	'nmrkirim' => $this->input->post('nmrkirim', TRUE),
			// 	'uuid_konsumen' => $get_uuid_konsumen,
			// 	'konsumen_nama' => $get_nama_konsumen,
			// 	// 'kode_barang' => $this->input->post('kode_barang', TRUE),
			// 	'nama_barang' => $this->input->post('nama_barang', TRUE),
			// 	'unit' => $this->input->post('unit', TRUE),
			// 	'satuan' => $this->input->post('satuan', TRUE),
			// 	'harga_satuan' => $this->input->post('harga_satuan', TRUE),
			// 	'jumlah' => $this->input->post('jumlah', TRUE),
			// 	'umpphpsl22' => $this->input->post('umpphpsl22', TRUE),
			// 	'piutang' => $this->input->post('piutang', TRUE),
			// 	'penjualandpp' => $this->input->post('penjualandpp', TRUE),
			// 	'utangppn' => $this->input->post('utangppn', TRUE),
			// 	'id_usr' => $this->input->post('id_usr', TRUE),
			// );







			$this->session->set_flashdata('message', 'Create Record Success');
			redirect(site_url('tbl_penjualan'));
		}
	}

	public function create_action_inisiasi($id_proses = null)
	{
		$tgl_jual_post = trim((string) $this->input->post('tgl_jual', TRUE));
		if ($tgl_jual_post === '') {
			$this->session->set_flashdata('message', 'Tgl Jual wajib diisi sebelum Input Barang Penjualan.');
			redirect(site_url('tbl_penjualan/create'));
			return;
		}

		$Get_UUID_unit = trim((string) $this->input->post('uuid_unit', TRUE));
		$Get_kode_unit = '';
		$Get_nama_unit = '';

		// unIT
		$this->db->where('uuid_unit', $Get_UUID_unit);
		$sys_unit_data = $this->db->get('sys_unit');

		if ($sys_unit_data->num_rows() > 0) {
			$Get_unit_data = $sys_unit_data->row_array();
			$Get_kode_unit = $Get_unit_data['kode_unit'];
			$Get_nama_unit = $Get_unit_data['nama_unit'];
		}

		// KONSUMEN
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

		$filter_bulan_penjualan = penjualan_sync_filter_bulan_from_tgl_jual($this, $tgl_jual_post);
		$hasil_kolom_unit = penjualan_ensure_persediaan_kolom_unit($this, $Get_UUID_unit);
		if (empty($hasil_kolom_unit['ok'])) {
			$this->session->set_flashdata(
				'message',
				isset($hasil_kolom_unit['message']) ? $hasil_kolom_unit['message'] : 'Gagal menyiapkan kolom unit di persediaan.'
			);
			redirect(site_url('tbl_penjualan/create'));
			return;
		}

		try {
			$Data_stock = penjualan_get_stock_persediaan_rows($this, $tgl_jual_post, $Get_UUID_unit);
		} catch (Exception $e) {
			$this->session->set_flashdata('message', 'Gagal memuat persediaan: ' . $e->getMessage());
			redirect(site_url('tbl_penjualan/create'));
			return;
		}

		$data = array(
			'button' => 'Simpan',
			'button_detail_nomor_kirim' => 'Simpan Perubahan Detail Nomor Kirim',
			'action' => site_url('tbl_penjualan/create_action_simpan_barang/'),
			'tgl_jual' => $this->input->post('tgl_jual', TRUE),
			'nmrpesan' => $this->input->post('nmrpesan', TRUE),
			'nmrkirim' => $this->input->post('nmrkirim', TRUE),
			'uuid_konsumen' => $uuid_konsumen,
			'nama_konsumen' => $data_nama_konsumen,
			'uuid_unit' => $Get_UUID_unit,
			'unit' => $Get_nama_unit,
			'filter_bulan_penjualan' => $filter_bulan_penjualan,
			'Data_stock' => $Data_stock,
			'action_ubah_detail_nomor_kirim' => site_url('tbl_penjualan/action_ubah_detail_nomor_kirim/' . $this->input->post('nmrkirim', TRUE) . '/new'),
		);
		// 		print_r($data);
		// die;
		// $this->load->view('anekadharma/tbl_penjualan/tbl_penjualan_form', $data);
		$data['jumlah_barang_penjualan'] = 0;
		$data['penjualan_bulan_key'] = penjualan_get_bulan_key_from_tgl($tgl_jual_post);
		$list_ctx = penjualan_get_list_bulan_context($this);
		$data['penjualan_list_bulan_key'] = $list_ctx['bulan_key'];
		$data['penjualan_list_bulan_label'] = $list_ctx['bulan_label'];
		$data['penjualan_redirect_list_url'] = penjualan_build_redirect_list_url($this, $tgl_jual_post);

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_form_input_barang', $data);
	}

	/**
	 * AJAX: daftar persediaan modal Pilih Barang (filter bulan Tgl Jual).
	 */
	public function list_persediaan_penjualan_ajax()
	{
		$this->output->set_content_type('application/json');

		try {
			$tgl_jual = trim((string) $this->input->get_post('tgl_jual', TRUE));
			if ($tgl_jual === '') {
				echo json_encode(array('ok' => false, 'message' => 'Tgl Jual wajib diisi.'));
				return;
			}

			penjualan_sync_filter_bulan_from_tgl_jual($this, $tgl_jual);
			$filter = penjualan_get_filter_tgl_jual($this, $tgl_jual);
			$uuid_unit_ajax = trim((string) $this->input->get_post('uuid_unit', TRUE));
			$hasil_kolom_unit = penjualan_ensure_persediaan_kolom_unit($this, $uuid_unit_ajax);
			if (empty($hasil_kolom_unit['ok'])) {
				echo json_encode(array(
					'ok' => false,
					'message' => isset($hasil_kolom_unit['message']) ? $hasil_kolom_unit['message'] : 'Gagal menyiapkan kolom unit di persediaan.',
				));
				return;
			}

			$Data_stock = penjualan_get_stock_persediaan_rows($this, $tgl_jual, $uuid_unit_ajax);
			$tgl_jual_X = penjualan_format_tgl_jual_tampil($tgl_jual);
			$view_data = array(
				'Data_stock' => $Data_stock,
				'tgl_jual' => $tgl_jual,
				'tgl_jual_X' => $tgl_jual_X,
				'uuid_penjualan' => trim((string) $this->input->get_post('uuid_penjualan', TRUE)),
				'action' => site_url('tbl_penjualan/create_action_simpan_barang/'),
				'uuid_unit' => $this->input->get_post('uuid_unit', TRUE),
				'uuid_konsumen' => $this->input->get_post('uuid_konsumen', TRUE),
				'nmrpesan' => $this->input->get_post('nmrpesan', TRUE),
				'nmrkirim' => $this->input->get_post('nmrkirim', TRUE),
			);

			$render = penjualan_render_modal_pilih_barang($this, $view_data);

			$jumlah_tampil = 0;
			if (preg_match_all('/<tr\b/i', $render['tbody'], $m)) {
				$jumlah_tampil = count($m[0]);
				if (strpos($render['tbody'], 'Tidak ada barang persediaan') !== false) {
					$jumlah_tampil = 0;
				}
			}

			echo json_encode(array(
				'ok' => true,
				'bulan_label' => $filter['bulan_label'],
				'bulan_key' => penjualan_get_bulan_key_from_tgl($tgl_jual),
				'tgl_awal' => $filter['awal'],
				'tgl_akhir' => $filter['akhir'],
				'tbody' => $render['tbody'],
				'modals' => $render['modals'],
				'jumlah' => count($Data_stock),
				'jumlah_tampil' => $jumlah_tampil,
				'kolom_unit' => isset($hasil_kolom_unit['kolom']) ? $hasil_kolom_unit['kolom'] : '',
				'kolom_unit_created' => !empty($hasil_kolom_unit['created']),
			));
		} catch (Exception $e) {
			echo json_encode(array(
				'ok' => false,
				'message' => 'Gagal memuat persediaan: ' . $e->getMessage(),
			));
		}
	}

	/**
	 * AJAX: hapus semua barang penjualan saat Tgl Jual pindah ke bulan lain.
	 */
	public function ajax_ganti_bulan_tgl_jual()
	{
		$this->output->set_content_type('application/json');

		$tgl_jual = trim((string) $this->input->post('tgl_jual', TRUE));
		$uuid_penjualan = trim((string) $this->input->post('uuid_penjualan', TRUE));

		if ($tgl_jual === '') {
			echo json_encode(array('ok' => false, 'message' => 'Tgl Jual wajib diisi.'));
			return;
		}

		if ($uuid_penjualan !== '') {
			$rows = $this->Tbl_penjualan_model->get_all_by_uuid_penjualan($uuid_penjualan);
			if (is_array($rows) && count($rows) > 0) {
				$bulan_lama = penjualan_get_bulan_key_from_tgl($rows[0]->tgl_jual);
				$bulan_baru = penjualan_get_bulan_key_from_tgl($tgl_jual);
				if ($bulan_baru !== '' && $bulan_lama !== '' && $bulan_baru !== $bulan_lama) {
					echo json_encode(array(
						'ok' => false,
						'message' => 'Tgl Jual tidak boleh diubah ke bulan lain selama masih ada data barang penjualan.',
					));
					return;
				}
			}
		}

		penjualan_sync_filter_bulan_from_tgl_jual($this, $tgl_jual);

		echo json_encode(array(
			'ok' => true,
			'bulan_label' => penjualan_get_filter_tgl_jual($this, $tgl_jual)['bulan_label'],
			'bulan_key' => penjualan_get_bulan_key_from_tgl($tgl_jual),
			'message' => 'Bulan Tgl Jual diperbarui.',
		));
	}


	public function create_action_simpan_barang($uuid_penjualan = null, $id_persediaan_barang = null)
	{

		// AMBIL DATA DARI PERSEDIAAN (filter id + uuid_barang)
		$id_persediaan_barang = (int) $id_persediaan_barang;
		$data_barang = $this->Persediaan_model->get_by_id($id_persediaan_barang);
		if (empty($data_barang)) {
			$data_barang = $this->db->where('id', $id_persediaan_barang)->get('persediaan')->row();
		}

		if (empty($data_barang)) {
			$this->session->set_flashdata('message', 'Barang persediaan tidak ditemukan.');
			redirect(site_url('tbl_penjualan/create'));
			return;
		}

		if (trim((string) $data_barang->uuid_barang) === '') {
			$this->session->set_flashdata('message', 'Barang persediaan tidak memiliki uuid_barang.');
			redirect(site_url('tbl_penjualan/create'));
			return;
		}

		$uuid_barang_post = trim((string) $this->input->post('uuid_barang', TRUE));
		if ($uuid_barang_post !== '' && $uuid_barang_post !== $data_barang->uuid_barang) {
			$this->session->set_flashdata('message', 'Data barang tidak sesuai (uuid_barang).');
			redirect(site_url('tbl_penjualan/create'));
			return;
		}

		if ($this->db->field_exists('kategori', 'persediaan') && penjualan_is_kategori_jasa(isset($data_barang->kategori) ? $data_barang->kategori : '')) {
			$this->session->set_flashdata('message', 'Item kategori Jasa tidak dapat dijual melalui Input Barang Penjualan.');
			redirect(site_url('tbl_penjualan/create'));
			return;
		}

		$tgl_jual_simpan = trim((string) $this->input->post('tgl_jual', TRUE));
		$filter_bulan = penjualan_get_filter_tgl_jual($this, $tgl_jual_simpan);
		if (!empty($data_barang->tanggal_beli)) {
			$tgl_beli = date('Y-m-d', strtotime($data_barang->tanggal_beli));
			if ($tgl_beli < $filter_bulan['awal'] || $tgl_beli > $filter_bulan['akhir']) {
				$this->session->set_flashdata(
					'message',
					'Barang tidak termasuk persediaan bulan ' . $filter_bulan['bulan_label'] . ' (sesuai Tgl Jual).'
				);
				redirect(site_url('tbl_penjualan/create'));
				return;
			}
		}

		// unIT
		$this->db->where('uuid_unit', $this->input->post('uuid_unit', TRUE));
		$sys_unit_data = $this->db->get('sys_unit');

		// print_r($sys_unit_data);
		// print_r("<br/>");

		if ($sys_unit_data->num_rows() > 0) {

			$Get_unit_data = $sys_unit_data->row_array();

			$Get_uuid_unit = $this->input->post('uuid_unit', TRUE);
			$Get_kode_unit = $Get_unit_data['kode_unit'];
			$Get_nama_unit = $Get_unit_data['nama_unit'];
		}

		// 		print_r($Get_uuid_unit);
		// 		print_r("<br/>");
		// 		print_r($Get_nama_unit);
		// 		print_r("<br/>");
		// die;
		// KONSUMEN
		$uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
		$data_konsumen = $this->Sys_konsumen_model->get_by_uuid_konsumen($uuid_konsumen);


		if (empty($data_konsumen)) {
			$data_konsumen = $this->Sys_unit_model->get_by_uuid_unit($uuid_konsumen);
			$data_nama_konsumen = $data_konsumen->nama_unit;
		} else {
			$data_nama_konsumen = $data_konsumen->nama_konsumen;
		}

		$jumlah_simpan = preg_replace('/[^0-9]/', '', $this->input->post('jumlah', TRUE));
		if ((int) $jumlah_simpan <= 0) {
			$this->session->set_flashdata('message', 'Jumlah barang wajib diisi dan lebih dari 0.');
			redirect(site_url('tbl_penjualan/create'));
			return;
		}

		$uuid_unit_simpan = isset($Get_uuid_unit) ? $Get_uuid_unit : $this->input->post('uuid_unit', TRUE);
		$hasil_ensure_unit = penjualan_ensure_persediaan_kolom_unit($this, $uuid_unit_simpan);
		if (empty($hasil_ensure_unit['ok'])) {
			$this->session->set_flashdata(
				'message',
				isset($hasil_ensure_unit['message']) ? $hasil_ensure_unit['message'] : 'Gagal menyiapkan kolom unit di persediaan.'
			);
			redirect(site_url('tbl_penjualan/create'));
			return;
		}
		$kolom_unit_simpan = penjualan_resolve_kolom_persediaan_unit($this, $uuid_unit_simpan);
		$sisa_stock_simpan = penjualan_get_sisa_stock_penjualan($data_barang, $kolom_unit_simpan);
		if ((int) $jumlah_simpan > $sisa_stock_simpan) {
			$this->session->set_flashdata(
				'message',
				'Jumlah melebihi stok tersedia (' . (int) $sisa_stock_simpan . ').'
			);
			redirect(site_url('tbl_penjualan/create'));
			return;
		}

		// =========SIMPAN DATA==================
		$tgl_jual_X = date("Y-m-d", strtotime($this->input->post('tgl_jual', TRUE)));


		if ($uuid_penjualan == "new") {
			// print_r("NEW");
			$data = array(
				'tgl_input' => date("Y-m-d H:i:s"),
				'tgl_jual' => $tgl_jual_X,
				// 'uuid_penjualan' => "new",
				'nmrpesan' => $this->input->post('nmrpesan', TRUE),
				'nmrkirim' => $this->input->post('nmrkirim', TRUE),
				'uuid_unit' => $Get_uuid_unit,
				'unit' => $Get_nama_unit,
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



			$uuid_penjualan = $this->Tbl_penjualan_model->insert_new($data);
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
				'uuid_unit' => $Get_uuid_unit,
				'unit' => $Get_nama_unit,
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

			// print_r("Barang baru");
			// print_r("<br/>");
			// print_r($data);
			// die;

			$this->Tbl_penjualan_model->insert_add_barang($data);
		}

		// die;
		// =========SIMPAN DATA==================


		$hasil_persediaan = penjualan_update_persediaan_saat_jual(
			$this,
			$id_persediaan_barang,
			isset($Get_uuid_unit) ? $Get_uuid_unit : $this->input->post('uuid_unit', TRUE),
			$jumlah_simpan,
			'tambah'
		);
		if (empty($hasil_persediaan['ok'])) {
			$this->session->set_flashdata(
				'message',
				isset($hasil_persediaan['message']) ? $hasil_persediaan['message'] : 'Gagal memperbarui persediaan.'
			);
			if ($uuid_penjualan === 'new') {
				redirect(site_url('tbl_penjualan/create_action_inisiasi/new'));
			} else {
				redirect(site_url('tbl_penjualan/kasir_penjualan/' . $uuid_penjualan));
			}
			return;
		}


		// die;

		// redirect("kasir_penjualan/".$uuid_penjualan);
		// redirect(site_url('tbl_penjualan/kasir_penjualan/' . $uuid_penjualan . '/' . $tgl_jual_X . '/' . $this->input->post('nmrkirim', TRUE)));
		redirect(site_url('tbl_penjualan/kasir_penjualan/' . $uuid_penjualan));
	}

	// public function kasir_penjualan($uuid_penjualan, $tgl_jual, $nmrkirim)
	public function kasir_penjualan($uuid_penjualan)
	{

		// Get tgl_jual dan nmrkirim dari uuid_penjualan

		$data_penjualan_per_uuid_penjualan = $this->Tbl_penjualan_model->get_ROW_by_uuid_penjualan_first_row($uuid_penjualan);

		$tgl_jual_X = date("Y-m-d", strtotime($data_penjualan_per_uuid_penjualan->tgl_jual));

		// --------------TAMPILKAN DATA INPUT PENJUALAN SESUAI UUID_NOMOR PESAN yang barusan di inputkan ----------------------
		// $data_penjualan_per_uuid_penjualan = $this->Tbl_penjualan_model->get_all_by_tgl_jual_nmrkirim($tgl_jual_X, $data_penjualan_per_uuid_penjualan->nmrkirim);
		$data_penjualan_per_uuid_penjualan = $this->Tbl_penjualan_model->get_all_by_uuid_penjualan($uuid_penjualan);

		// $data_penjualan_per_uuid_penjualan_first_row = $this->Tbl_penjualan_model->get_all_by_tgl_jual_nmrkirim_first_row($tgl_jual_X, $data_penjualan_per_uuid_penjualan->nmrkirim);
		$data_penjualan_per_uuid_penjualan_first_row = $this->Tbl_penjualan_model->get_all_by_uuid_penjualan_first_row($uuid_penjualan);

		$tgl_jual_kasir = $data_penjualan_per_uuid_penjualan_first_row->tgl_jual;
		$filter_bulan_penjualan = penjualan_sync_filter_bulan_from_tgl_jual($this, $tgl_jual_kasir);
		penjualan_ensure_persediaan_kolom_unit($this, $data_penjualan_per_uuid_penjualan_first_row->uuid_unit);
		$Data_stock = penjualan_get_stock_persediaan_rows(
			$this,
			$tgl_jual_kasir,
			$data_penjualan_per_uuid_penjualan_first_row->uuid_unit
		);

		$data = array(
			'data_penjualan_per_uuid_penjualan' => $data_penjualan_per_uuid_penjualan,
			'button' => 'Simpan',
			'button_detail_nomor_kirim' => 'Simpan Perubahan Detail Nomor Kirim',
			'action' => site_url('tbl_penjualan/create_action_simpan_barang/'),
			'tgl_jual' => $tgl_jual_kasir,
			'nmrpesan' => $data_penjualan_per_uuid_penjualan_first_row->nmrpesan,
			'nmrkirim' => $data_penjualan_per_uuid_penjualan_first_row->nmrkirim,
			'uuid_unit' => $data_penjualan_per_uuid_penjualan_first_row->uuid_unit,
			'unit' => $data_penjualan_per_uuid_penjualan_first_row->unit,
			'uuid_konsumen' => $data_penjualan_per_uuid_penjualan_first_row->uuid_konsumen,
			'nama_konsumen' => $data_penjualan_per_uuid_penjualan_first_row->konsumen_nama,
			'uuid_penjualan' => $uuid_penjualan,
			'action_ubah_per_id' => site_url('tbl_penjualan/create_action_nmrkirim_update_per_id_penjualan/'),
			'action_ubah_detail_nomor_kirim' => site_url('tbl_penjualan/action_ubah_detail_nomor_kirim/' . $data_penjualan_per_uuid_penjualan_first_row->nmrkirim . '/' . $uuid_penjualan),
			'filter_bulan_penjualan' => $filter_bulan_penjualan,
			'Data_stock' => $Data_stock,
			'jumlah_barang_penjualan' => is_array($data_penjualan_per_uuid_penjualan) ? count($data_penjualan_per_uuid_penjualan) : 0,
			'penjualan_bulan_key' => penjualan_get_bulan_key_from_tgl($tgl_jual_kasir),
		);
		$list_ctx = penjualan_get_list_bulan_context($this);
		$data['penjualan_list_bulan_key'] = $list_ctx['bulan_key'];
		$data['penjualan_list_bulan_label'] = $list_ctx['bulan_label'];
		$data['penjualan_redirect_list_url'] = penjualan_build_redirect_list_url($this, $tgl_jual_kasir);

		// $this->load->view('anekadharma/tbl_penjualan/tbl_penjualan_form', $data);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_form_input_barang', $data);
	}

	public function action_ubah_detail_nomor_kirim($NomorKirim = null, $uuid_penjualan = null)
	{
		$uuid_penjualan = trim((string) $uuid_penjualan);
		$tgl_jual_post = trim((string) $this->input->post('tgl_jual', TRUE));

		if ($uuid_penjualan !== '' && $tgl_jual_post !== '') {
			$rows_penjualan = $this->Tbl_penjualan_model->get_all_by_uuid_penjualan($uuid_penjualan);
			if (is_array($rows_penjualan) && count($rows_penjualan) > 0) {
				$bulan_lama = penjualan_get_bulan_key_from_tgl($rows_penjualan[0]->tgl_jual);
				$bulan_baru = penjualan_get_bulan_key_from_tgl($tgl_jual_post);
				if ($bulan_baru !== '' && $bulan_lama !== '' && $bulan_baru !== $bulan_lama) {
					$this->session->set_flashdata(
						'message',
						'Tgl Jual tidak boleh diubah ke bulan lain karena sudah ada data barang penjualan pada bulan persediaan saat ini.'
					);
					redirect(site_url('tbl_penjualan/kasir_penjualan/' . $uuid_penjualan));
					return;
				}
			}
		}

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
		// $GET_uuid_unit = $this->input->post('uuid_unit', TRUE);

		// unIT
		$this->db->where('uuid_unit', $this->input->post('uuid_unit', TRUE));
		$sys_unit_data = $this->db->get('sys_unit');

		// print_r($sys_unit_data);
		// print_r("<br/>");

		if ($sys_unit_data->num_rows() > 0) {

			$Get_unit_data = $sys_unit_data->row_array();

			$Get_uuid_unit = $this->input->post('uuid_unit', TRUE);
			$Get_kode_unit = $Get_unit_data['kode_unit'];
			$Get_nama_unit = $Get_unit_data['nama_unit'];
		}




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

		$rows_penjualan = $this->Tbl_penjualan_model->get_all_by_uuid_penjualan($uuid_penjualan);
		$uuid_unit_lama = '';
		if (is_array($rows_penjualan) && count($rows_penjualan) > 0 && !empty($rows_penjualan[0]->uuid_unit)) {
			$uuid_unit_lama = trim((string) $rows_penjualan[0]->uuid_unit);
		}
		$uuid_unit_baru = isset($Get_uuid_unit) ? trim((string) $Get_uuid_unit) : '';

		if ($uuid_unit_baru !== '') {
			$hasil_ensure_unit_baru = penjualan_ensure_persediaan_kolom_unit($this, $uuid_unit_baru);
			if (empty($hasil_ensure_unit_baru['ok'])) {
				$this->session->set_flashdata(
					'message',
					isset($hasil_ensure_unit_baru['message']) ? $hasil_ensure_unit_baru['message'] : 'Gagal menyiapkan kolom unit baru di persediaan.'
				);
				redirect(site_url('tbl_penjualan/kasir_penjualan/' . $uuid_penjualan));
				return;
			}
		}

		if ($uuid_unit_lama !== '' && $uuid_unit_baru !== '' && $uuid_unit_lama !== $uuid_unit_baru) {
			$hasil_pindah_unit = penjualan_pindah_unit_semua_barang(
				$this,
				$rows_penjualan,
				$uuid_unit_lama,
				$uuid_unit_baru
			);
			if (empty($hasil_pindah_unit['ok'])) {
				$this->session->set_flashdata(
					'message',
					isset($hasil_pindah_unit['message'])
						? $hasil_pindah_unit['message']
						: 'Gagal memindahkan data penjualan ke unit baru di persediaan.'
				);
				redirect(site_url('tbl_penjualan/kasir_penjualan/' . $uuid_penjualan));
				return;
			}
		}

		// $sql_update_penjualan_by_uuid_penjualan = "UPDATE `tbl_penjualan` SET `nmrkirim`=$NomorKirim_baru , `tgl_jual`=$date_jual , `nmrpesan`=$NomorPesan_baru, `uuid_konsumen`=$GET_uuid_konsumen, `konsumen_nama`=$nama_konsumen  WHERE `uuid_penjualan`='$uuid_penjualan'";

		$sql_update_penjualan_by_uuid_penjualan = "UPDATE `tbl_penjualan` SET `tgl_jual`='$date_jual' , `nmrkirim`='$NomorKirim_baru', `nmrpesan`='$NomorPesan_baru', `uuid_konsumen`='$GET_uuid_konsumen', `konsumen_nama`='$nama_konsumen', `uuid_unit`='$Get_uuid_unit', `unit`='$Get_nama_unit'  WHERE `uuid_penjualan`='$uuid_penjualan'";

		$this->db->query($sql_update_penjualan_by_uuid_penjualan);

		$this->session->set_flashdata('message', 'Perubahan detail penjualan berhasil disimpan.');
		redirect(site_url('tbl_penjualan/kasir_penjualan/' . $uuid_penjualan));
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
		$sql_data_penjualan = "SELECT * FROM `tbl_penjualan` WHERE `id`='$id'";
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

		$this->Tbl_penjualan_model->update($id, $data);

		//KONTROL INI BELUM ADA:
		// NOTE : HARUS CEK FIELD PENJUALAN , JIKA SUDAH ADA PROSES PENJUALAN, MAKA TIDAK BOLEH MENGUBAH NAMA BARANG, HANYA BISA MENGUBAH HPP DAN JUMLAH BELI (JUMLAH BELI HARUS LEBIH DARI TOTAL JUMLAH TERJUAL)

		// UPDATE DATA DI PERSEDIAAN berdasarkan id persediaan atau uuid_persediaan


		// fiel penjualan tabel persediaan: Kalkulasi total penjualan - jumlah jual awal kemudian total penjualan + jumlah jual akhir

		$row_penjualan_unit = $this->db->query($sql_data_penjualan)->row();
		$hasil_persediaan = penjualan_update_persediaan_selisih_jual(
			$this,
			$get_id_persediaan,
			isset($row_penjualan_unit->uuid_unit) ? $row_penjualan_unit->uuid_unit : '',
			$get_jmlh_penjualan_awal,
			$jumlah_Jual_ubah
		);
		if (empty($hasil_persediaan['ok'])) {
			$this->session->set_flashdata(
				'message',
				isset($hasil_persediaan['message']) ? $hasil_persediaan['message'] : 'Gagal memperbarui persediaan.'
			);
			redirect(site_url('Tbl_penjualan/kasir_penjualan/' . $Get_uuid_penjualan));
			return;
		}

		// print_r("persediaan");
		// print_r("<br/>");
		// print_r($Update_data_persediaan);
		// die;

		redirect(site_url('Tbl_penjualan/kasir_penjualan/' . $Get_uuid_penjualan));
	}


	// public function cetak_penjualan_per_uuid_penjualan($uuid_penjualan = null, $date_tgl_jual = null, $nmrkirim = null)
	public function cetak_penjualan_per_uuid_penjualan($uuid_penjualan = null)
	{

		// 2.a. PERSIAPAN LIBRARY
		// $this->load->library('PdfGenerator');



		// $data_master_penjualan_per_uuidpenjualan = $this->Tbl_penjualan_model->get_all_by_uuid_penjualan_first_row($uuid_penjualan);
		// $data_master_penjualan_per_uuidpenjualan = $this->Tbl_penjualan_model->get_all_by_uuid_penjualan_tgl_jual_nmrkirim_first_row($uuid_penjualan, $date_tgl_jual, $nmrkirim);
		$data_master_penjualan_per_uuidpenjualan = $this->Tbl_penjualan_model->get_all_by_uuid_penjualan_first_row($uuid_penjualan);

		// print_r($data_master_penjualan_per_uuidpenjualan);
		// print_r($data_master_penjualan_per_uuidpenjualan->nmrpesan);
		// print_r(date("d M Y", strtotime($data_master_penjualan_per_uuidpenjualan->tgl_jual)));
		// print_r($data_master_penjualan_per_uuidpenjualan->konsumen_nama);
		// die;


		// $date_po = date("d M Y", strtotime($data_master_penjualan_per_uuidpenjualan->tgl_jual));
		// die;

		// 2.b. PERSIAPAN DATA (barang & stock dari tabel persediaan)
		$rows_penjualan = $this->Tbl_penjualan_model->get_all_by_uuid_penjualan($uuid_penjualan);
		$data = array(
			'data_penjualan' => penjualan_enrich_data_cetak_penjualan($this, $rows_penjualan),
			'nmr_pesan_selected' => $data_master_penjualan_per_uuidpenjualan->nmrpesan,
			'tgl_jual_selected' => date("d M Y", strtotime($data_master_penjualan_per_uuidpenjualan->tgl_jual)),
			'konsumen_nama_selected' => $data_master_penjualan_per_uuidpenjualan->konsumen_nama,
		);



		// 2.C. MENAMPILKAN FILE DATA
		// $data = array_merge($data);
		$html = $this->load->view('anekadharma/tbl_penjualan/adminlte310_cetak_penjualan.php', $data, true);

		// 2.d. CONVERT TAMPILAN FILE DATA MENJADI FILE PDF
		$this->pdf->loadHtml($html);
		$this->pdf->render();

		$nmr_pesan = isset($data_master_penjualan_per_uuidpenjualan->nmrpesan)
			? trim((string) $data_master_penjualan_per_uuidpenjualan->nmrpesan)
			: '';
		$nmr_pesan_safe = preg_replace('/[^A-Za-z0-9._-]+/', '_', $nmr_pesan);
		$nmr_pesan_safe = trim($nmr_pesan_safe, '._-');
		if ($nmr_pesan_safe === '') {
			$nmr_pesan_safe = 'tanpa_nomor';
		}
		$pdf_nama_file = 'NOTA_PENJUALAN_' . $nmr_pesan_safe . '.pdf';

		$this->pdf->stream($pdf_nama_file, array("Attachment" => 0));
	}



	public function update($id)
	{
		$row = $this->Tbl_penjualan_model->get_by_id($id);

		if ($row) {
			$data = array(
				'button' => 'Update',
				'action' => site_url('tbl_penjualan/update_action'),
				'id' => set_value('id', $row->id),
				'tgl_input' => set_value('tgl_input', $row->tgl_input),
				'nmrpesan' => set_value('nmrpesan', $row->nmrpesan),
				'nmrkirim' => set_value('nmrkirim', $row->nmrkirim),
				'konsumen_id' => set_value('konsumen_id', $row->konsumen_id),
				'konsumen_nama' => set_value('konsumen_nama', $row->konsumen_nama),
				'kode_barang' => set_value('kode_barang', $row->kode_barang),
				'nama_barang' => set_value('nama_barang', $row->nama_barang),
				'unit' => set_value('unit', $row->unit),
				'satuan' => set_value('satuan', $row->satuan),
				'harga_satuan' => set_value('harga_satuan', $row->harga_satuan),
				'jumlah' => set_value('jumlah', $row->jumlah),
				'umpphpsl22' => set_value('umpphpsl22', $row->umpphpsl22),
				'piutang' => set_value('piutang', $row->piutang),
				'penjualandpp' => set_value('penjualandpp', $row->penjualandpp),
				'utangppn' => set_value('utangppn', $row->utangppn),
				'id_usr' => set_value('id_usr', $row->id_usr),
			);

			// $this->load->view('anekadharma/tbl_penjualan/tbl_penjualan_form', $data);
			$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_form_update', $data);
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(site_url('tbl_penjualan'));
		}
	}

	public function update_penjualan($uuid_penjualan_proses)
	{




		$row = $this->Tbl_penjualan_model->get_all_by_uuid_penjualan_proses($uuid_penjualan_proses);

		if ($row) {
			$data = array(
				'button' => 'Update',
				'uuid_penjualan_proses' => $uuid_penjualan_proses,
				'action' => site_url('Tbl_penjualan/update_action_proses/' . $uuid_penjualan_proses),
				'id' => set_value('id', $row->id),
				'tgl_input' => set_value('tgl_input', $row->tgl_input),
				'tgl_jual' => set_value('tgl_jual', $row->tgl_jual),
				'nmrpesan' => set_value('nmrpesan', $row->nmrpesan),
				'nmrkirim' => set_value('nmrkirim', $row->nmrkirim),

				'konsumen_id' => set_value('konsumen_id', $row->konsumen_id),
				'uuid_konsumen' => set_value('uuid_konsumen', $row->uuid_konsumen),
				'konsumen_nama' => set_value('konsumen_nama', $row->konsumen_nama),

				'id_persediaan_barang' => set_value('id_persediaan_barang', $row->id_persediaan_barang),
				'uuid_barang' => set_value('uuid_barang', $row->uuid_barang),
				'kode_barang' => set_value('kode_barang', $row->kode_barang),
				'nama_barang' => set_value('nama_barang', $row->nama_barang),
				'unit' => set_value('unit', $row->unit),
				'satuan' => set_value('satuan', $row->satuan),
				'harga_satuan' => set_value('harga_satuan', $row->harga_satuan),
				'jumlah' => set_value('jumlah', $row->jumlah),
				'umpphpsl22' => set_value('umpphpsl22', $row->umpphpsl22),
				'piutang' => set_value('piutang', $row->piutang),
				'penjualandpp' => set_value('penjualandpp', $row->penjualandpp),
				'utangppn' => set_value('utangppn', $row->utangppn),
				'id_usr' => set_value('id_usr', $row->id_usr),
				// 'Data_stock' => $Data_stock,
			);

			// Update Persediaan Field penjualan : uuid_proses lama di kurangi , uuid_proses baru di tambah

			// $this->load->view('anekadharma/tbl_penjualan/tbl_penjualan_form', $data);
			$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_form_update_new', $data);
		} else {

			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(site_url('tbl_penjualan'));
		}
	}

	public function update_action_pilih_barang($uuid_penjualan_proses = null, $proses_cek = null, $id_persediaan_barang = null)
	{

		// print_r($id);
		// print_r("<br/>");
		// print_r("<br/>");

		$row_barang = $this->Persediaan_model->get_by_id($id_persediaan_barang);

		// print_r($row_barang);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r($uuid_penjualan_proses);
		// print_r("<br/>");
		// print_r($proses_cek);
		// print_r("<br/>");
		// print_r($id);
		// print_r("<br/>");
		// print_r("update_action_prosesX");



		$row = $this->Tbl_penjualan_model->get_all_by_uuid_penjualan_proses($uuid_penjualan_proses);


		// print_r($row);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");

		if ($row) {
			$data = array(
				'button' => 'Update',
				'uuid_penjualan_proses' => $uuid_penjualan_proses,
				'action' => site_url('tbl_penjualan/update_action_proses/' . $uuid_penjualan_proses . '/'),
				'id' => set_value('id', $row->id),
				'tgl_input' => set_value('tgl_input', $row->tgl_input),
				'tgl_jual' => set_value('tgl_jual', $row->tgl_jual),
				'nmrpesan' => set_value('nmrpesan', $row->nmrpesan),
				'nmrkirim' => set_value('nmrkirim', $row->nmrkirim),
				'uuid_konsumen' => set_value('uuid_konsumen', $row->uuid_konsumen),
				'konsumen_id' => set_value('konsumen_id', $row->konsumen_id),
				'konsumen_nama' => set_value('konsumen_nama', $row->konsumen_nama),

				'id_persediaan_barang' => $id_persediaan_barang,
				'uuid_barang' => set_value('uuid_barang', $row_barang->uuid_barang),
				'kode_barang' => set_value('kode_barang', $row_barang->kode_barang),
				'nama_barang' => set_value('nama_barang', $row_barang->namabarang),
				'satuan' => set_value('satuan', $row_barang->satuan),
				'harga_satuan' => set_value('harga_satuan', $row_barang->hpp),

				'unit' => set_value('unit', $row->unit),


				'jumlah' => set_value('jumlah', $row->jumlah),
				'umpphpsl22' => set_value('umpphpsl22', $row->umpphpsl22),
				'piutang' => set_value('piutang', $row->piutang),
				'penjualandpp' => set_value('penjualandpp', $row->penjualandpp),
				'utangppn' => set_value('utangppn', $row->utangppn),
				'id_usr' => set_value('id_usr', $row->id_usr),
				// 'Data_stock' => $Data_stock,
			);


			// print_r($id);
			// print_r("<br/>");
			// print_r("<br/>");
			// print_r($data);
			// die;

			// $this->load->view('anekadharma/tbl_penjualan/tbl_penjualan_form', $data);
			$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_form_update_new', $data);
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(site_url('tbl_penjualan'));
		}
	}

	public function update_action_proses($uuid_penjualan_proses = null)
	{


		// $row = $this->Tbl_penjualan_model->get_all_by_uuid_penjualan_proses($uuid_penjualan_proses);

		$tgl_jual_X = date("Y-m-d", strtotime($this->input->post('tgl_jual', TRUE)));

		$uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
		$data_konsumen = $this->Sys_konsumen_model->get_by_uuid_konsumen($uuid_konsumen);


		if (empty($data_konsumen)) {
			$data_konsumen = $this->Sys_unit_model->get_by_uuid_unit($uuid_konsumen);
			$data_id_konsumen = $data_konsumen->id;
			$data_nama_konsumen = $data_konsumen->nama_unit;
		} else {
			$data_id_konsumen = $data_konsumen->id;
			$data_nama_konsumen = $data_konsumen->nama_konsumen;
		}

		if (empty($this->input->post('id_persediaan_barang', TRUE)) or $this->input->post('id_persediaan_barang', TRUE) == 0) {

			$get_uuid_barang = $this->input->post('uuid_barang', TRUE);

			$sql_stock = "SELECT persediaan.id as id_persediaan_barang, persediaan.uuid_barang as uuid_barang, persediaan.kode_barang as kode_barang, persediaan.namabarang as nama_barang_beli,persediaan.total_10 as jumlah_sediaan,  persediaan.hpp as harga_satuan_persediaan,  persediaan.satuan as satuan
			-- 	tbl_pembelian.uuid_pembelian as uuid_pembelian,tbl_pembelian.uraian as barang_beli, tbl_pembelian.jumlah as jumlah_belanja, tbl_pembelian.harga_satuan as harga_satuan_beli,  tbl_pembelian.tgl_po as tgl_po,tbl_pembelian.uuid_gudang as uuid_gudang, tbl_pembelian.nama_gudang as nama_gudang,  tbl_pembelian.satuan as satuan,
			-- tbl_penjualan.nama_barang as barang_jual, tbl_penjualan.jumlah as jumlah_terjual
			FROM persediaan  
			-- left join tbl_pembelian ON persediaan.uuid_barang = tbl_pembelian.uuid_barang 
			-- left join tbl_penjualan ON persediaan.uuid_barang = tbl_penjualan.uuid_barang  
			WHERE (persediaan.uuid_barang, persediaan.tanggal) IN (SELECT persediaan.uuid_barang, Max(persediaan.tanggal) FROM persediaan GROUP BY persediaan.uuid_barang)  AND persediaan.uuid_barang='$get_uuid_barang'
			ORDER BY persediaan.uuid_barang ASC";

			$data_barang = $this->db->query($sql_stock)->row();
		} else {
			$get_id_persediaan_barang = $this->input->post('id_persediaan_barang', TRUE);

			$sql_stock = "SELECT persediaan.id as id_persediaan_barang, persediaan.uuid_barang as uuid_barang, persediaan.kode_barang as kode_barang, persediaan.namabarang as nama_barang_beli,persediaan.total_10 as jumlah_sediaan,  persediaan.hpp as harga_satuan_persediaan,  persediaan.satuan as satuan
			-- 	tbl_pembelian.uuid_pembelian as uuid_pembelian,tbl_pembelian.uraian as barang_beli, tbl_pembelian.jumlah as jumlah_belanja, tbl_pembelian.harga_satuan as harga_satuan_beli,  tbl_pembelian.tgl_po as tgl_po,tbl_pembelian.uuid_gudang as uuid_gudang, tbl_pembelian.nama_gudang as nama_gudang,  tbl_pembelian.satuan as satuan,
			-- tbl_penjualan.nama_barang as barang_jual, tbl_penjualan.jumlah as jumlah_terjual
			FROM persediaan  
			-- left join tbl_pembelian ON persediaan.uuid_barang = tbl_pembelian.uuid_barang 
			-- left join tbl_penjualan ON persediaan.uuid_barang = tbl_penjualan.uuid_barang  
			WHERE (persediaan.uuid_barang, persediaan.tanggal) IN (SELECT persediaan.uuid_barang, Max(persediaan.tanggal) FROM persediaan GROUP BY persediaan.uuid_barang)  AND persediaan.id='$get_id_persediaan_barang'
			ORDER BY persediaan.uuid_barang ASC";

			$data_barang = $this->db->query($sql_stock)->row();
		}





		$data = array(
			'tgl_input' => date("Y-m-d H:i:s"),
			'tgl_jual' => $tgl_jual_X,
			'nmrpesan' => $this->input->post('nmrpesan', TRUE),
			'nmrkirim' => $this->input->post('nmrkirim', TRUE),
			'uuid_konsumen' => $this->input->post('uuid_konsumen', TRUE),
			'konsumen_id' => $data_id_konsumen,
			'konsumen_nama' => $data_nama_konsumen,


			'id_persediaan_barang' => $data_barang->id_persediaan_barang,
			'uuid_barang' => $data_barang->uuid_barang,
			'kode_barang' => $data_barang->kode_barang,
			'nama_barang' => $data_barang->nama_barang_beli,

			// 'unit' => $this->input->post('unit', TRUE),
			'satuan' => $data_barang->satuan,
			'harga_satuan' => $data_barang->harga_satuan_persediaan,


			'jumlah' => $this->input->post('jumlah', TRUE),



			// 'umpphpsl22' => $this->input->post('umpphpsl22', TRUE),
			// 'piutang' => $this->input->post('piutang', TRUE),
			// 'penjualandpp' => $this->input->post('penjualandpp', TRUE),
			// 'utangppn' => $this->input->post('utangppn', TRUE),
			// 'id_usr' => $this->input->post('id_usr', TRUE),







		);

		// print_r($data);
		// print_r("update");
		// die;
		$this->Tbl_penjualan_model->update_proses($uuid_penjualan_proses, $data);
		$this->session->set_flashdata('message', 'Update Record Success');
		redirect(site_url('tbl_penjualan'));
		// }
		// }
	}


	public function update_action()
	{
		$this->_rules();

		if ($this->form_validation->run() == FALSE) {
			$this->update($this->input->post('id', TRUE));
		} else {
			$data = array(
				'tgl_input' => $this->input->post('tgl_input', TRUE),
				'nmrpesan' => $this->input->post('nmrpesan', TRUE),
				'nmrkirim' => $this->input->post('nmrkirim', TRUE),
				'konsumen_id' => $this->input->post('konsumen_id', TRUE),
				'konsumen_nama' => $this->input->post('konsumen_nama', TRUE),
				'kode_barang' => $this->input->post('kode_barang', TRUE),
				'nama_barang' => $this->input->post('nama_barang', TRUE),
				'unit' => $this->input->post('unit', TRUE),
				'satuan' => $this->input->post('satuan', TRUE),
				'harga_satuan' => $this->input->post('harga_satuan', TRUE),
				'jumlah' => $this->input->post('jumlah', TRUE),
				'umpphpsl22' => $this->input->post('umpphpsl22', TRUE),
				'piutang' => $this->input->post('piutang', TRUE),
				'penjualandpp' => $this->input->post('penjualandpp', TRUE),
				'utangppn' => $this->input->post('utangppn', TRUE),
				'id_usr' => $this->input->post('id_usr', TRUE),
			);

			$this->Tbl_penjualan_model->update($this->input->post('id', TRUE), $data);
			$this->session->set_flashdata('message', 'Update Record Success');
			redirect(site_url('tbl_penjualan'));
		}
	}

	public function delete($id = null, $uuid_penjualan = null)
	{
		$row = $this->Tbl_penjualan_model->get_by_id($id);

		// if ($row) {

		// Get data penjualan berdasarkan uuid_penjualan , mengurangi jumlah field penjualan di tabel persediaan berdasarkan uuid_penjualan

		$Get_id_persediaan_barang = $row->id_persediaan_barang;

		// Cek nominal penjualan di tabel persediaan berdasarkan id_persediaan_barang
		$row_data_persediaan = $this->Persediaan_model->get_by_id($Get_id_persediaan_barang);

		// print_r($row_data_persediaan);
		// print_r("<br/>");
		// print_r("<br/>");

		$Get_total_penjualan_by_id_persediaan = $row_data_persediaan->penjualan;

		// print_r($Get_total_penjualan_by_id_persediaan);
		// print_r("<br/>");
		// print_r("<br/>");

		$Get_total_persediaan_total_10_di_tbl_persediaan = $row_data_persediaan->total_10;

		// print_r($Get_total_persediaan_total_10_di_tbl_persediaan);
		// print_r("<br/>");
		// print_r("<br/>");


		// print_r("Get_total_penjualan_by_id_persediaan=");
		// print_r("<br/>");
		// print_r($Get_total_penjualan_by_id_persediaan);
		// print_r("<br/>");
		// print_r($row->jumlah);
		// print_r("<br/>");
		// print_r($Get_total_penjualan_by_id_persediaan > $row->jumlah);
		// print_r("<br/>");
		// print_r("<br/>");

		// die;


		// if ($Get_total_penjualan_by_id_persediaan > 0 and $Get_total_penjualan_by_id_persediaan > $row->jumlah) {
		if ($Get_total_penjualan_by_id_persediaan >= $row->jumlah) {

			penjualan_update_persediaan_saat_jual(
				$this,
				$Get_id_persediaan_barang,
				isset($row->uuid_unit) ? $row->uuid_unit : '',
				$row->jumlah,
				'kurangi'
			);

			// } else {
			// 	// print_r("Buat fieldnya jadi 0");
			// 	// print_r("<br/>");
			// 	// print_r("tidak ada yang dikurangi");
			// 	// die;
			// }

			// Hapus record di tabel penjualan
			$this->Tbl_penjualan_model->delete($id);


			$this->session->set_flashdata('message', 'Delete Record Success');


			// die;

			redirect(site_url('Tbl_penjualan/kasir_penjualan/' . $uuid_penjualan));
		} else {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(site_url('tbl_penjualan'));
		}
	}

	public function _rules()
	{
		// $this->form_validation->set_rules('tgl_input', 'tgl input', 'trim|required');
		// $this->form_validation->set_rules('nmrpesan', 'nmrpesan', 'trim|required');
		// $this->form_validation->set_rules('nmrkirim', 'nmrkirim', 'trim|required');
		$this->form_validation->set_rules('uuid_konsumen', 'uuid_konsumen id', 'trim|required');
		// $this->form_validation->set_rules('konsumen_nama', 'konsumen nama', 'trim|required');
		// $this->form_validation->set_rules('kode_barang', 'kode barang', 'trim|required');
		// $this->form_validation->set_rules('nama_barang', 'nama barang', 'trim|required');
		// $this->form_validation->set_rules('unit', 'unit', 'trim|required');
		// $this->form_validation->set_rules('satuan', 'satuan', 'trim|required');
		// $this->form_validation->set_rules('harga_satuan', 'harga satuan', 'trim|required|numeric');
		// $this->form_validation->set_rules('jumlah', 'jumlah', 'trim|required|numeric');
		// $this->form_validation->set_rules('umpphpsl22', 'umpphpsl22', 'trim|required|numeric');
		// $this->form_validation->set_rules('piutang', 'piutang', 'trim|required|numeric');
		// $this->form_validation->set_rules('penjualandpp', 'penjualandpp', 'trim|required|numeric');
		// $this->form_validation->set_rules('utangppn', 'utangppn', 'trim|required|numeric');
		// $this->form_validation->set_rules('id_usr', 'id usr', 'trim|required');

		$this->form_validation->set_rules('id', 'id', 'trim');
		$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
	}

	public function excel()
	{
		$this->load->helper('exportexcel');

		$source = $this->input->get('source', TRUE);
		if ($source !== 'tbl_penjualan') {
			show_error('Export tidak valid untuk modul penjualan.', 403);
			return;
		}

		$from_datatable = ($this->input->get('from_datatable', TRUE) === '1');
		$Tbl_penjualan_rows = array();
		$ids_param = $this->input->get('ids', TRUE);

		if ($ids_param !== null && $ids_param !== '') {
			$ids = array_values(array_filter(array_map('intval', explode(',', $ids_param))));
			if (!empty($ids)) {
				$Tbl_penjualan_rows = $this->_get_penjualan_rows_for_excel_by_ids($ids, $from_datatable);
			}
		}

		if ($from_datatable) {
			if (empty($Tbl_penjualan_rows)) {
				show_error('Tidak ada data penjualan untuk diekspor sesuai tampilan DataTable.', 400);
				return;
			}
		} else {
			if (empty($Tbl_penjualan_rows)) {
				$session_ids = $this->session->userdata('filter_tbl_penjualan_ids');
				if (is_array($session_ids) && count($session_ids) > 0) {
					$Tbl_penjualan_rows = $this->_get_penjualan_rows_for_excel_by_ids($session_ids);
				}
			}

			if (empty($Tbl_penjualan_rows)) {
				list($Get_date_awal, $Get_date_akhir) = $this->_resolve_penjualan_filter_dates();
				$Tbl_penjualan_rows = $this->_get_penjualan_between($Get_date_awal, $Get_date_akhir);
			}
		}

		$namaFile = 'Data_penjualan_' . date('Y-m-d_H-i-s') . '.xlsx';
		$tablehead = 0;
		$tablebody = 1;
		$nourut = 1;

		excel_prepare_download($namaFile);
		xlsBOF();

		$kolomhead = 0;
		xlsWriteLabel($tablehead, $kolomhead++, 'No');
		xlsWriteLabel($tablehead, $kolomhead++, 'Tgl Jual');
		xlsWriteLabel($tablehead, $kolomhead++, 'nmrkirim');
		xlsWriteLabel($tablehead, $kolomhead++, 'nmrpesan');
		xlsWriteLabel($tablehead, $kolomhead++, 'Konsumen');
		xlsWriteLabel($tablehead, $kolomhead++, 'Kode');
		xlsWriteLabel($tablehead, $kolomhead++, 'Nama Barang');
		xlsWriteLabel($tablehead, $kolomhead++, 'Unit');
		xlsWriteLabel($tablehead, $kolomhead++, 'Satuan');
		xlsWriteLabel($tablehead, $kolomhead++, 'Harga Satuan');
		xlsWriteLabel($tablehead, $kolomhead++, 'Jumlah');
		xlsWriteLabel($tablehead, $kolomhead++, 'Total harga');
		xlsWriteLabel($tablehead, $kolomhead++, 'UM PPH PSL 22');
		xlsWriteLabel($tablehead, $kolomhead++, 'Piutang');
		xlsWriteLabel($tablehead, $kolomhead++, 'Penjualan DPP');
		xlsWriteLabel($tablehead, $kolomhead++, 'Utang PPN');

		foreach ($Tbl_penjualan_rows as $data) {
			$calc = $this->_penjualan_hitung_kolom_tampilan($data);
			$kolombody = 0;

			xlsWriteNumber($tablebody, $kolombody++, $nourut);
			xlsWriteLabel($tablebody, $kolombody++, date('d M Y', strtotime($data->tgl_jual)));
			xlsWriteLabel($tablebody, $kolombody++, $data->nmrkirim);
			xlsWriteLabel($tablebody, $kolombody++, $data->nmrpesan);
			xlsWriteLabel($tablebody, $kolombody++, $data->konsumen_nama);
			xlsWriteLabel($tablebody, $kolombody++, $data->kode_barang);
			xlsWriteLabel($tablebody, $kolombody++, $data->nama_barang);
			xlsWriteLabel($tablebody, $kolombody++, $data->unit);
			xlsWriteLabel($tablebody, $kolombody++, $data->satuan);
			xlsWriteNumber($tablebody, $kolombody++, $data->harga_satuan);
			xlsWriteNumber($tablebody, $kolombody++, $data->jumlah);
			xlsWriteNumber($tablebody, $kolombody++, $calc['jumlah_total']);
			xlsWriteNumber($tablebody, $kolombody++, $calc['umpphpsl22']);
			xlsWriteNumber($tablebody, $kolombody++, $calc['piutang']);
			xlsWriteNumber($tablebody, $kolombody++, $calc['penjualandpp']);
			xlsWriteNumber($tablebody, $kolombody++, $calc['utangppn']);

			$tablebody++;
			$nourut++;
		}

		xlsEOF();
		exit();
	}

	public function excel_rekap_unit()
	{
		$tgl_jual_Now = date("Y-m-d H:i:sa");
		$this->load->helper('exportexcel');
		$namaFile = "Rekap_penjualan_per_Unit_" . $tgl_jual_Now . ".xls";
		$judul = "Data Rekap Penjualan Per Unit";
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
		xlsWriteLabel($tablehead, $kolomhead++, "Tgl Input");
		xlsWriteLabel($tablehead, $kolomhead++, "Unit");
		xlsWriteLabel($tablehead, $kolomhead++, "Nmrpesan");
		xlsWriteLabel($tablehead, $kolomhead++, "Nmrkirim");
		// xlsWriteLabel($tablehead, $kolomhead++, "Konsumen Id");
		xlsWriteLabel($tablehead, $kolomhead++, "Konsumen Nama");
		xlsWriteLabel($tablehead, $kolomhead++, "Kode Barang");
		xlsWriteLabel($tablehead, $kolomhead++, "Nama Barang");
		xlsWriteLabel($tablehead, $kolomhead++, "Satuan");
		xlsWriteLabel($tablehead, $kolomhead++, "Harga Satuan");
		xlsWriteLabel($tablehead, $kolomhead++, "Jumlah");
		// xlsWriteLabel($tablehead, $kolomhead++, "Umpphpsl22");
		// xlsWriteLabel($tablehead, $kolomhead++, "Piutang");
		// xlsWriteLabel($tablehead, $kolomhead++, "Penjualandpp");
		// xlsWriteLabel($tablehead, $kolomhead++, "Utangppn");
		// xlsWriteLabel($tablehead, $kolomhead++, "Id Usr");

		list($Get_date_awal, $Get_date_akhir) = $this->_resolve_penjualan_filter_dates();
		foreach ($this->_get_penjualan_between($Get_date_awal, $Get_date_akhir, 'unit') as $data) {
			$kolombody = 0;

			//ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
			xlsWriteNumber($tablebody, $kolombody++, $nourut);
			xlsWriteLabel($tablebody, $kolombody++, $data->tgl_input);
			xlsWriteLabel($tablebody, $kolombody++, $data->unit);
			xlsWriteLabel($tablebody, $kolombody++, $data->nmrpesan);
			xlsWriteLabel($tablebody, $kolombody++, $data->nmrkirim);
			// xlsWriteNumber($tablebody, $kolombody++, $data->konsumen_id);
			xlsWriteLabel($tablebody, $kolombody++, $data->konsumen_nama);
			xlsWriteLabel($tablebody, $kolombody++, $data->kode_barang);
			xlsWriteLabel($tablebody, $kolombody++, $data->nama_barang);
			xlsWriteLabel($tablebody, $kolombody++, $data->satuan);
			xlsWriteNumber($tablebody, $kolombody++, $data->harga_satuan);
			xlsWriteNumber($tablebody, $kolombody++, $data->jumlah);
			// xlsWriteNumber($tablebody, $kolombody++, $data->id_usr);
			$tablebody++;
			$nourut++;
		}

		xlsEOF();
		exit();
	}

	public function excel_rekap_konsumen()
	{
		$tgl_jual_Now = date("Y-m-d H:i:sa");
		$this->load->helper('exportexcel');
		$namaFile = "Rekap_penjualan_per_konsumen_" . $tgl_jual_Now . ".xls";
		$judul = "Data Rekap Penjualan Per konsumen";
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
		xlsWriteLabel($tablehead, $kolomhead++, "Tgl Input");
		xlsWriteLabel($tablehead, $kolomhead++, "Konsumen Nama");
		xlsWriteLabel($tablehead, $kolomhead++, "Unit");
		xlsWriteLabel($tablehead, $kolomhead++, "Nmrpesan");
		xlsWriteLabel($tablehead, $kolomhead++, "Nmrkirim");
		// xlsWriteLabel($tablehead, $kolomhead++, "Konsumen Id");
		xlsWriteLabel($tablehead, $kolomhead++, "Kode Barang");
		xlsWriteLabel($tablehead, $kolomhead++, "Nama Barang");
		xlsWriteLabel($tablehead, $kolomhead++, "Satuan");
		xlsWriteLabel($tablehead, $kolomhead++, "Harga Satuan");
		xlsWriteLabel($tablehead, $kolomhead++, "Jumlah");
		// xlsWriteLabel($tablehead, $kolomhead++, "Umpphpsl22");
		// xlsWriteLabel($tablehead, $kolomhead++, "Piutang");
		// xlsWriteLabel($tablehead, $kolomhead++, "Penjualandpp");
		// xlsWriteLabel($tablehead, $kolomhead++, "Utangppn");
		// xlsWriteLabel($tablehead, $kolomhead++, "Id Usr");

		list($Get_date_awal, $Get_date_akhir) = $this->_resolve_penjualan_filter_dates();
		foreach ($this->_get_penjualan_between($Get_date_awal, $Get_date_akhir, 'konsumen_nama') as $data) {
			$kolombody = 0;

			//ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
			xlsWriteNumber($tablebody, $kolombody++, $nourut);
			xlsWriteLabel($tablebody, $kolombody++, $data->tgl_input);
			xlsWriteLabel($tablebody, $kolombody++, $data->konsumen_nama);
			xlsWriteLabel($tablebody, $kolombody++, $data->unit);
			xlsWriteLabel($tablebody, $kolombody++, $data->nmrpesan);
			xlsWriteLabel($tablebody, $kolombody++, $data->nmrkirim);
			// xlsWriteNumber($tablebody, $kolombody++, $data->konsumen_id);
			xlsWriteLabel($tablebody, $kolombody++, $data->kode_barang);
			xlsWriteLabel($tablebody, $kolombody++, $data->nama_barang);
			xlsWriteLabel($tablebody, $kolombody++, $data->satuan);
			xlsWriteNumber($tablebody, $kolombody++, $data->harga_satuan);
			xlsWriteNumber($tablebody, $kolombody++, $data->jumlah);
			// xlsWriteNumber($tablebody, $kolombody++, $data->id_usr);
			$tablebody++;
			$nourut++;
		}

		xlsEOF();
		exit();
	}


	public function excel_rekap_barang()
	{
		$tgl_jual_Now = date("Y-m-d H:i:sa");
		$this->load->helper('exportexcel');
		$namaFile = "Rekap_penjualan_per_barang_" . $tgl_jual_Now . ".xls";
		$judul = "Data Rekap Penjualan Per barang";
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
		xlsWriteLabel($tablehead, $kolomhead++, "Tgl Input");
		xlsWriteLabel($tablehead, $kolomhead++, "Kode Barang");
		xlsWriteLabel($tablehead, $kolomhead++, "Nama Barang");
		xlsWriteLabel($tablehead, $kolomhead++, "Konsumen Nama");
		xlsWriteLabel($tablehead, $kolomhead++, "Unit");
		xlsWriteLabel($tablehead, $kolomhead++, "Nmrpesan");
		xlsWriteLabel($tablehead, $kolomhead++, "Nmrkirim");
		// xlsWriteLabel($tablehead, $kolomhead++, "Konsumen Id");
		xlsWriteLabel($tablehead, $kolomhead++, "Satuan");
		xlsWriteLabel($tablehead, $kolomhead++, "Harga Satuan");
		xlsWriteLabel($tablehead, $kolomhead++, "Jumlah");
		// xlsWriteLabel($tablehead, $kolomhead++, "Umpphpsl22");
		// xlsWriteLabel($tablehead, $kolomhead++, "Piutang");
		// xlsWriteLabel($tablehead, $kolomhead++, "Penjualandpp");
		// xlsWriteLabel($tablehead, $kolomhead++, "Utangppn");
		// xlsWriteLabel($tablehead, $kolomhead++, "Id Usr");

		list($Get_date_awal, $Get_date_akhir) = $this->_resolve_penjualan_filter_dates();
		foreach ($this->_get_penjualan_between($Get_date_awal, $Get_date_akhir, 'nama_barang') as $data) {
			$kolombody = 0;

			//ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
			xlsWriteNumber($tablebody, $kolombody++, $nourut);
			xlsWriteLabel($tablebody, $kolombody++, $data->tgl_input);
			xlsWriteLabel($tablebody, $kolombody++, $data->kode_barang);
			xlsWriteLabel($tablebody, $kolombody++, $data->nama_barang);
			xlsWriteLabel($tablebody, $kolombody++, $data->konsumen_nama);
			xlsWriteLabel($tablebody, $kolombody++, $data->unit);
			xlsWriteLabel($tablebody, $kolombody++, $data->nmrpesan);
			xlsWriteLabel($tablebody, $kolombody++, $data->nmrkirim);
			// xlsWriteNumber($tablebody, $kolombody++, $data->konsumen_id);
			xlsWriteLabel($tablebody, $kolombody++, $data->satuan);
			xlsWriteNumber($tablebody, $kolombody++, $data->harga_satuan);
			xlsWriteNumber($tablebody, $kolombody++, $data->jumlah);
			// xlsWriteNumber($tablebody, $kolombody++, $data->id_usr);
			$tablebody++;
			$nourut++;
		}

		xlsEOF();
		exit();
	}


	public function rekap($uuid_konsumen = null)
	{
		// print_r("rekap");
		// print_r("<br/>");
		// print_r($uuid_konsumen);
		// die;

		if (isset($uuid_konsumen)) {
			if ($uuid_konsumen == "semua") {
				$Tbl_penjualan = $this->Tbl_penjualan_model->get_all();
				$data_selection = "semua";
				$nama_konsumen_selection = "semua";
			} else {
				$Tbl_penjualan = $this->Tbl_penjualan_model->get_all_by_uuid_konsumen($uuid_konsumen);
				$data_selection = $uuid_konsumen;

				$sql_uuid_konsumen = "SELECT * FROM `sys_konsumen` WHERE `uuid_konsumen`='$uuid_konsumen'";
				$nama_konsumen_selection = $this->db->query($sql_uuid_konsumen)->row()->nama_konsumen;

				// $nama_konsumen_selection = $get_nama_konsumen;
			}
		} else {
			if ($this->input->post('uuid_konsumen', TRUE) == null) {
				$Tbl_penjualan = $this->Tbl_penjualan_model->get_all();
				$data_selection = "semua";
				$nama_konsumen_selection = "semua";
			} else {
				redirect(site_url('tbl_penjualan/rekap/' . $this->input->post('uuid_konsumen', TRUE)));
			}
		}




		$start = 0;
		// print_r($Tbl_pembelian);
		// print_r("<br/>");
		// print_r("<br/>");

		$data = array(
			'Tbl_penjualan_data' => $Tbl_penjualan,
			// 'q' => $q,
			// 'pagination' => $this->pagination->create_links(),
			// 'total_rows' => $config['total_rows'],
			'start' => $start,

			'action_cari_konsumen' => site_url('tbl_penjualan/rekap'),
			'data_selection' => $data_selection,
			'nama_konsumen_selection' => $nama_konsumen_selection,
		);


		// $this->load->view('anekadharma/tbl_penjualan/tbl_penjualan_list', $data);		
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_list_rekap', $data);
	}



	public function setting_kode_akun_penjualan2()
	{

		$Tbl_penjualan = $this->Tbl_penjualan_model->get_all_group_by_tgl_jual_nmrpesan_nmr_kirim();



		$data = array(
			'Tbl_penjualan_data' => $Tbl_penjualan,
			// 'q' => $q,
			// 'pagination' => $this->pagination->create_links(),
			// 'total_rows' => $config['total_rows'],
		);


		// $this->load->view('anekadharma/tbl_penjualan/tbl_penjualan_list', $data);		
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_list_jurnal', $data);
	}

	public function jurnal_penjualan2()
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


		// print_r($Get_month_selected);
		// print_r("<br/>");
		// print_r($Get_YEAR_selected);
		// print_r("<br/>");

		$GET_Source = "penjualan";
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

		// print_r($data);
		// die;

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_list__jurnal_penjualan', $data);
	}

	public function input_kode_akun($nmrkirim = null, $Tgl_JUAL = null)
	{

		// print_r($nmrkirim);
		// print_r("<br/>");
		// print_r($Tgl_JUAL);
		// print_r("<br/>");

		$Get_date = date("Y-m-d", strtotime($Tgl_JUAL));

		// print_r($Get_date);
		// print_r("<br/>");


		// Update field kode_akun by spop ==> open form input kode akun

		$data_per_nmrkirim = $this->Tbl_penjualan_model->get_all_by_nmr_kirim($nmrkirim);

		// // $Tbl_pembelian = $this->Tbl_pembelian_model->get_by_spop($spop);
		// $Tbl_pembelian = $this->Tbl_penjualan_model->get_by_spop($data_per_uuidspop->spop);

		// // SELECT `status_spop` FROM `tbl_pembelian` WHERE `uuid_spop`="53d056417ed111ef95300021ccc9061e";



		// $start = 0;
		$data = array(
			'Tbl_penjualan_data' => $data_per_nmrkirim,
			'nmrkirim' => $nmrkirim,
			'action' => site_url('Tbl_penjualan/update_kode_akun/' . $nmrkirim . '/' . $Get_date),
			'button' => 'Simpan Kode AKun',
			// 'start' => $start,
		);
		// print_r($data);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_list_per_nmrkirim', $data);
	}

	public function update_kode_akun($nmrkirim = null, $Tgl_JUAL = null)
	{


		// 		print_r($nmrkirim);
		// 		print_r("<br/>");
		// 		print_r($Tgl_JUAL);
		// 		print_r("<br/>");
		// // die;
		// print_r($this->input->post('kode_akun', TRUE));
		// print_r("<br/>");


		// ================ NOTE SETTING KODE AKUN UNTUK TRANSAKSI PENJUALAN =========

		// dan 
		// untuk penjualan otomatis 2 data di buku besar:
		// 1. kode akun terpilih ( 11101, 41101 dan 21201 ) masuk kredit
		// 2. 11301 ( debet )


		// Pak maaf ralat.. yg jurnal penjualan itu : 

		// - Total penjualan masuk kode akun 11301 (otomatis)
		// - kreditnya ada 2 : 41101 - 41131 (penjualan) dan 21201 (utang PPN)/21204 (utang pph 23)

		// Untuk penjualan cetak, atk, kebersihan, medis


		// ================ END OF NOTE SETTING KODE AKUN UNTUK TRANSAKSI PENJUALAN =========

		// Cek data di buku_besar
		// $data_Penjualan_by_nmr_kirim_tgl_jual = $this->Tbl_penjualan_model->get_all_by_nmr_kirim_TGL_JUAL($nmrkirim, $Tgl_JUAL);


		// $GET_Source = "penjualan";
		// $sql = "SELECT * FROM `tbl_penjualan` WHERE `tgl_jual`='$Tgl_JUAL' AND `nmrkirim`='$nmrkirim'  ORDER BY `id`";
		$sql = "SELECT * FROM `tbl_penjualan` WHERE  `nmrkirim`='$nmrkirim'  ORDER BY `id`";

		// SELECT * FROM `tbl_penjualan` WHERE `tgl_jual`='2025-05-12' and `nmrkirim`='qweqwewqewq';

		$data_Penjualan_by_nmr_kirim_tgl_jual = $this->db->query($sql)->result();



		// print_r($data_Penjualan_by_nmr_kirim_tgl_jual);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");
		// die;
		// GET id_buku_besar , jika belum ada maka insert , jika sudah ada maka update di 

		$GET_TOTAL_PENJUALAN = 0;
		$GET_tanggal_PENJUALAN = null;
		$GET_nmrkirim_PENJUALAN = null;
		foreach ($data_Penjualan_by_nmr_kirim_tgl_jual as $list_data) {


			// print_r($list_data->tgl_jual);
			// print_r("<br/>");
			// if ($GET_tanggal_PENJUALAN OR $GET_tanggal_PENJUALAN <> null) {
			// } else {
			// $GET_tanggal_PENJUALAN = $list_data->tgl_jual;
			// }

			$GET_tanggal_PENJUALAN = date("Y-m-d H:i:s", strtotime($list_data->tgl_jual, TRUE));

			// print_r($GET_tanggal_PENJUALAN);
			// print_r("<br/>");


			if ($GET_nmrkirim_PENJUALAN or $GET_nmrkirim_PENJUALAN <> null) {
			} else {
				$GET_nmrkirim_PENJUALAN = $list_data->nmrkirim;
			}



			// GET DATA KONSUMEN
			$GET_UUID_KONSUMEN = $list_data->uuid_konsumen;
			$GET_ID_KONSUMEN = $list_data->konsumen_id;
			$GET_NAMA_KONSUMEN = $list_data->konsumen_nama;


			$Harga_TOtal = $list_data->jumlah * $list_data->harga_satuan;
			$GET_TOTAL_PENJUALAN = $GET_TOTAL_PENJUALAN + $Harga_TOtal;

			if ($GET_ID_buku_besar) {
			} else {
				$GET_ID_buku_besar = $list_data->id_buku_besar;
			}
		}


		// TOTAL PENJUALAN

		// debit umpphpsl22
		$x_var_umpphpsl22 = 1.351351;
		$umpphpsl22_per_nmrkirim = ($Harga_TOtal * $x_var_umpphpsl22) / 100;

		// debet piutang 11301
		$x_piutang_percentage = 11.261261;
		$piutang_per_nmrkirim = ($Harga_TOtal - (($jumlah_per_nmrkirim * $x_piutang_percentage) / 100));

		// kredit penjualan dpp 41101 --> pilihan combo box
		$x_penjualandpp_percentage = 90.090090;
		$penjualandpp_per_nmrkirim = ($Harga_TOtal * $x_penjualandpp_percentage) / 100;

		// kredit utang ppn 21101
		$x_utangppn_percentage = 9.909910;
		$utangppn_per_nmrkirim = ($Harga_TOtal * $x_utangppn_percentage) / 100;

		// END OF TOTAL PENJUALAN 






		if ($list_data->id_buku_besar or $list_data->id_buku_besar > 0) {
			// print_r("ada ID");
			// proses update di tabel buku besar


			// KODE AKUN PILIHAN DARI FORM INPUT 41101 - 41131 ==> pilihan combobox kode akun ------------------------------

			$Get_kode_akun = $this->input->post('kode_akun', TRUE);
			$this->db->where('kode_akun', $Get_kode_akun);
			$GET_DATA_sys_kode_akun = $this->db->get('sys_kode_akun')->row()->nama_akun;
			// print_r("Update data");
			// print_r("<br/>");

			$data = array(
				// 'uuid_buku_besar' => $this->input->post('uuid_buku_besar', TRUE),
				'tanggal' => $GET_tanggal_PENJUALAN,
				'kode_akun' => $this->input->post('kode_akun', TRUE),
				'nama_akun' => $GET_DATA_sys_kode_akun,
				'source' => "penjualan",
				'nokirim' => $nmrkirim,
				'uuid_konsumen' => $GET_UUID_KONSUMEN,
				'konsumen_id' => $GET_ID_KONSUMEN,
				'konsumen_nama' => $GET_NAMA_KONSUMEN,
				'keterangan' => "Penjualan Nomor Kirim" . $nmrkirim,
				'pl' => $this->input->post('kode_pl', TRUE),
				'kode' => $this->input->post('kode_bb', TRUE),
				// 'debet' => $penjualandpp_per_nmrkirim,
				'kredit' => $penjualandpp_per_nmrkirim,
				// 'saldo' => $this->input->post('saldo', TRUE),


			);

			// print_r($data);
			// print_r("<br/>");
			// print_r("<br/>");
			// print_r("<br/>");
			// die;

			$this->Buku_besar_model->update($GET_ID_buku_besar, $data);



			// KREDIT 21201
			$Get_kode_akun = 21201;
			$this->db->where('kode_akun', $Get_kode_akun);
			$this->db->where('nokirim', $nmrkirim);
			$GET_id_buku_besar_by_21201_by_uuid_spop = $this->db->get('buku_besar')->row()->id;

			// // $Get_kode_akun = 11301;
			// $this->db->where('kode_akun', $Get_kode_akun);
			// $this->db->where('nokirim', $nmrkirim);
			// $GET_id_buku_besar_by_11301_by_uuid_spop = $this->db->get('buku_besar')->row()->id;


			$data = array(
				// 'uuid_buku_besar' => $this->input->post('uuid_buku_besar', TRUE),
				'tanggal' => $GET_tanggal_PENJUALAN,
				'kode_akun' => $Get_kode_akun,
				'nama_akun' => $GET_DATA_sys_kode_akun,
				'source' => "penjualan",
				'nokirim' => $nmrkirim,
				'uuid_konsumen' => $GET_UUID_KONSUMEN,
				'konsumen_id' => $GET_ID_KONSUMEN,
				'konsumen_nama' => $GET_NAMA_KONSUMEN,
				'keterangan' => "Penjualan Nomor Kirim" . $nmrkirim,
				'pl' => $this->input->post('kode_pl', TRUE),
				'kode' => $this->input->post('kode_bb', TRUE),
				// 'debet' => $utangppn_per_nmrkirim,
				'kredit' => $utangppn_per_nmrkirim,
				// 'saldo' => $this->input->post('saldo', TRUE),
			);
			$this->Buku_besar_model->update($GET_id_buku_besar_by_21201_by_uuid_spop, $data);



			// KODE AKUN 11301 & nmrkirim update datanya -----------------------------------------------------------------------

			$Get_kode_akun = 11301;
			$this->db->where('kode_akun', $Get_kode_akun);
			$GET_DATA_sys_kode_akun = $this->db->get('sys_kode_akun')->row()->nama_akun;

			// $Get_kode_akun = 11301;
			$this->db->where('kode_akun', $Get_kode_akun);
			$this->db->where('nokirim', $nmrkirim);
			$GET_id_buku_besar_by_11301_by_uuid_spop = $this->db->get('buku_besar')->row()->id;




			$data = array(
				// 'uuid_buku_besar' => $this->input->post('uuid_buku_besar', TRUE),
				'tanggal' => $GET_tanggal_PENJUALAN,
				'kode_akun' => $Get_kode_akun,
				'nama_akun' => $GET_DATA_sys_kode_akun,
				'source' => "penjualan",
				'nokirim' => $nmrkirim,
				'uuid_konsumen' => $GET_UUID_KONSUMEN,
				'konsumen_id' => $GET_ID_KONSUMEN,
				'konsumen_nama' => $GET_NAMA_KONSUMEN,
				'keterangan' => "Penjualan Nomor Kirim" . $nmrkirim,
				'pl' => $this->input->post('kode_pl', TRUE),
				'kode' => $this->input->post('kode_bb', TRUE),
				'debet' => $piutang_per_nmrkirim,
				// 'kredit' => $GET_TOTAL_PENJUALAN,
				// 'saldo' => $this->input->post('saldo', TRUE),
			);
			$this->Buku_besar_model->update($GET_id_buku_besar_by_11301_by_uuid_spop, $data);

			// UPDATE DI TABEL PENJUALAN
			$data = array(
				'kode_akun' => $this->input->post('kode_akun', TRUE),
				'kode_pl' => $this->input->post('kode_pl', TRUE),
				'kode_bb' => $this->input->post('kode_bb', TRUE),
				// 'id_buku_besar' => $GET_id_buku_besar,
			);

			$this->Tbl_penjualan_model->update_by_nmrkirim($nmrkirim, $data);

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



			// KREDIT COMBO PILIHAN
			$Get_kode_akun = $this->input->post('kode_akun', TRUE);
			$this->db->where('kode_akun', $Get_kode_akun);
			$GET_DATA_sys_kode_akun = $this->db->get('sys_kode_akun')->row()->nama_akun;

			$data = array(
				// 'uuid_buku_besar' => $this->input->post('uuid_buku_besar', TRUE),
				'tanggal' => $GET_tanggal_PENJUALAN,
				'kode_akun' => $this->input->post('kode_akun', TRUE),
				'nama_akun' => $GET_DATA_sys_kode_akun,
				'source' => "penjualan",
				'nokirim' => $nmrkirim,
				'uuid_konsumen' => $GET_UUID_KONSUMEN,
				'konsumen_id' => $GET_ID_KONSUMEN,
				'konsumen_nama' => $GET_NAMA_KONSUMEN,
				'keterangan' => "Penjualan Nomor Kirim" . $nmrkirim,
				'pl' => $this->input->post('kode_pl', TRUE),
				'kode' => $this->input->post('kode_bb', TRUE),
				// 'debet' => $GET_TOTAL_PENJUALAN,
				'kredit' => $penjualandpp_per_nmrkirim,
				// 'saldo' => $this->input->post('saldo', TRUE),


			);

			$GET_id_buku_besar = $this->Buku_besar_model->insert($data);


			// KREDIT 21201
			$Get_kode_akun = 21201;
			$this->db->where('kode_akun', $Get_kode_akun);
			$this->db->where('nokirim', $nmrkirim);
			$GET_id_buku_besar_by_11301_by_uuid_spop = $this->db->get('buku_besar')->row()->id;


			$data = array(
				// 'uuid_buku_besar' => $this->input->post('uuid_buku_besar', TRUE),
				'tanggal' => $GET_tanggal_PENJUALAN,
				'kode_akun' => $Get_kode_akun,
				'nama_akun' => $GET_DATA_sys_kode_akun,
				'source' => "penjualan",
				'nokirim' => $nmrkirim,
				'uuid_konsumen' => $GET_UUID_KONSUMEN,
				'konsumen_id' => $GET_ID_KONSUMEN,
				'konsumen_nama' => $GET_NAMA_KONSUMEN,
				'keterangan' => "Penjualan Nomor Kirim" . $nmrkirim,
				'pl' => $this->input->post('kode_pl', TRUE),
				'kode' => $this->input->post('kode_bb', TRUE),
				// 'debet' => $GET_TOTAL_PENJUALAN,
				'kredit' => $utangppn_per_nmrkirim,
				// 'saldo' => $this->input->post('saldo', TRUE),
			);
			$GET_id_buku_besar = $this->Buku_besar_model->insert($data);


			// DEBET 11301
			$Get_kode_akun = 11301;
			$this->db->where('kode_akun', $Get_kode_akun);
			$this->db->where('nokirim', $nmrkirim);
			$GET_id_buku_besar_by_11301_by_uuid_spop = $this->db->get('buku_besar')->row()->id;


			$data = array(
				// 'uuid_buku_besar' => $this->input->post('uuid_buku_besar', TRUE),
				'tanggal' => $GET_tanggal_PENJUALAN,
				'kode_akun' => $Get_kode_akun,
				'nama_akun' => $GET_DATA_sys_kode_akun,
				'source' => "penjualan",
				'nokirim' => $nmrkirim,
				'uuid_konsumen' => $GET_UUID_KONSUMEN,
				'konsumen_id' => $GET_ID_KONSUMEN,
				'konsumen_nama' => $GET_NAMA_KONSUMEN,
				'keterangan' => "Penjualan Nomor Kirim" . $nmrkirim,
				'pl' => $this->input->post('kode_pl', TRUE),
				'kode' => $this->input->post('kode_bb', TRUE),
				'debet' => $piutang_per_nmrkirim,
				// 'kredit' => $GET_TOTAL_PENJUALAN,
				// 'saldo' => $this->input->post('saldo', TRUE),
			);
			$GET_id_buku_besar = $this->Buku_besar_model->insert($data);

			$data = array(
				'kode_akun' => $this->input->post('kode_akun', TRUE),
				'kode_pl' => $this->input->post('kode_pl', TRUE),
				'kode_bb' => $this->input->post('kode_bb', TRUE),
				'id_buku_besar' => $GET_id_buku_besar,
			);

			// print_r($data);
			// die;

			$this->Tbl_penjualan_model->update_by_nmrkirim($nmrkirim, $data);
		}




		redirect(site_url('Tbl_penjualan/setting_kode_akun_penjualan2'));
	}


	public function ubah_kode_akun($nmrkirim = null)
	{

		$data_per_nmrkirim = $this->Tbl_penjualan_model->get_all_by_nmr_kirim($nmrkirim);

		$sql = "SELECT `nmrkirim`,`kode_akun`,`kode_pl`,`kode_bb` FROM `tbl_penjualan` WHERE `nmrkirim`='$nmrkirim' GROUP by `nmrkirim`,`kode_akun`";

		// $this->db->query($sql)->result();
		// print_r($this->db->query($sql)->row()->kode_akun);

		$get_kode_akun = $this->db->query($sql)->row()->kode_akun;
		$get_kode_pl = $this->db->query($sql)->row()->kode_pl;
		$get_kode_bb = $this->db->query($sql)->row()->kode_bb;
		// die;

		// $start = 0;
		$data = array(
			'Tbl_penjualan_data' => $data_per_nmrkirim,
			'nmrkirim' => $nmrkirim,
			'action' => site_url('Tbl_penjualan/update_kode_akun/' . $nmrkirim),
			'button' => 'Update Kode AKun',
			// 'start' => $start,
			'get_kode_akun' => $get_kode_akun,
			'get_kode_pl' => $get_kode_pl,
			'get_kode_bb' => $get_kode_bb,
		);
		// print_r($data);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_list_per_nmrkirim', $data);
	}
}

/* End of file Tbl_penjualan.php */
/* Location: ./application/controllers/Tbl_penjualan.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-09-03 14:59:44 */
/* http://harviacode.com */