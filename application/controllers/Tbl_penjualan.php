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
		$ts_awal = pembelian_parse_tanggal_po($tgl_awal_input);
		if ($ts_awal === false) {
			$ts_awal = strtotime(str_replace('/', '-', trim((string) $tgl_awal_input)));
		}

		$ts_akhir = pembelian_parse_tanggal_po($tgl_akhir_input);
		if ($ts_akhir === false) {
			$ts_akhir = strtotime(str_replace('/', '-', trim((string) $tgl_akhir_input)));
		}

		if ($ts_awal === false || date('Y', $ts_awal) < 2020) {
			$Get_date_awal = date('Y-m-d 00:00:00', strtotime('-1 day'));
		} else {
			$Get_date_awal = date('Y-m-d 00:00:00', $ts_awal);
		}

		if ($ts_akhir === false || date('Y', $ts_akhir) < 2020) {
			$Get_date_akhir = date('Y-m-d 23:59:59');
		} else {
			$Get_date_akhir = date('Y-m-d 23:59:59', $ts_akhir);
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
	private function _get_penjualan_active_tab()
	{
		$allowed = array('tab-penjualan-semua', 'tab-penjualan-belum-bayar', 'tab-penjualan-terbayar');
		$tab = trim((string) $this->input->get_post('penjualan_active_tab', TRUE));
		if ($tab === '' || !in_array($tab, $allowed, true)) {
			$tab = trim((string) $this->session->userdata('filter_tbl_penjualan_active_tab'));
		}
		if ($tab === '' || !in_array($tab, $allowed, true)) {
			$tab = 'tab-penjualan-semua';
		}
		$this->session->set_userdata('filter_tbl_penjualan_active_tab', $tab);
		return $tab;
	}

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

	private function _get_rekap_filter_col_name($field_rekap)
	{
		if ($field_rekap === 'konsumen_nama' || $field_rekap === 'konsumen') {
			return 'konsumen_nama';
		}
		if ($field_rekap === 'nama_barang') {
			return 'nama_barang';
		}
		return 'unit';
	}

	/**
	 * Opsi combobox filter rekap â€” hanya nilai yang benar-benar ada di data penjualan
	 * pada rentang tanggal (inklusif awal & akhir).
	 */
	private function _get_rekap_filter_options_from_rows($rows, $field_rekap)
	{
		$col = $this->_get_rekap_filter_col_name($field_rekap);
		$seen = array();
		$options = array();

		if (!is_array($rows)) {
			return $options;
		}

		foreach ($rows as $row) {
			if (!is_object($row) || !isset($row->$col)) {
				continue;
			}
			$val = trim((string) $row->$col);
			if ($val === '') {
				continue;
			}
			$key = strtoupper($val);
			if (!isset($seen[$key])) {
				$seen[$key] = $val;
				$options[] = $val;
			}
		}

		natcasesort($options);
		return array_values($options);
	}

	private function _filter_penjualan_proses_bayar($rows, $filter = 'all')
	{
		if (!is_array($rows)) {
			return array();
		}

		if ($filter === 'belum_bayar') {
			return array_values(array_filter($rows, function ($row) {
				$proses = strtolower(trim((string) (isset($row->proses_bayar) ? $row->proses_bayar : '')));
				return $proses === 'belum_bayar' || $proses === '';
			}));
		}

		if ($filter === 'terbayar') {
			return array_values(array_filter($rows, function ($row) {
				$proses = strtolower(trim((string) (isset($row->proses_bayar) ? $row->proses_bayar : '')));
				return $proses !== 'belum_bayar' && $proses !== '';
			}));
		}

		return $rows;
	}

	private function _count_penjualan_proses_bayar($rows, $filter = 'all')
	{
		return count($this->_filter_penjualan_proses_bayar($rows, $filter));
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
		$penjualan_active_tab = $this->_get_penjualan_active_tab();

		$data = array(
			'Tbl_penjualan_data' => $Tbl_penjualan_data,
			'Tbl_penjualan_data_belum_bayar' => $this->_filter_penjualan_proses_bayar($Tbl_penjualan_data, 'belum_bayar'),
			'Tbl_penjualan_data_terbayar' => $this->_filter_penjualan_proses_bayar($Tbl_penjualan_data, 'terbayar'),
			'penjualan_count_belum_bayar' => $this->_count_penjualan_proses_bayar($Tbl_penjualan_data, 'belum_bayar'),
			'penjualan_count_terbayar' => $this->_count_penjualan_proses_bayar($Tbl_penjualan_data, 'terbayar'),
			'penjualan_active_tab' => $penjualan_active_tab,
			// 'q' => $q,
			// 'pagination' => $this->pagination->create_links(),
			// 'total_rows' => $config['total_rows'],
			// 'start' => $start,
			'date_awal' => $Get_date_awal,
			'date_akhir' => $Get_date_akhir,
			'skip_filter_restore' => false,
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
		$penjualan_active_tab = $this->_get_penjualan_active_tab();

		$data = array(
			'Tbl_penjualan_data' => $Tbl_penjualan_data,
			'Tbl_penjualan_data_belum_bayar' => $this->_filter_penjualan_proses_bayar($Tbl_penjualan_data, 'belum_bayar'),
			'Tbl_penjualan_data_terbayar' => $this->_filter_penjualan_proses_bayar($Tbl_penjualan_data, 'terbayar'),
			'penjualan_count_belum_bayar' => $this->_count_penjualan_proses_bayar($Tbl_penjualan_data, 'belum_bayar'),
			'penjualan_count_terbayar' => $this->_count_penjualan_proses_bayar($Tbl_penjualan_data, 'terbayar'),
			'penjualan_active_tab' => $penjualan_active_tab,
			// 'q' => $q,
			// 'pagination' => $this->pagination->create_links(),
			// 'total_rows' => $config['total_rows'],
			// 'start' => $start,
			'date_awal' => $Get_date_awal,
			'date_akhir' => $Get_date_akhir,
			'skip_filter_restore' => true,
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
			'rekap_filter_options' => $this->_get_rekap_filter_options_from_rows($Tbl_penjualan_data, $field_rekap),
			'action_ubah_per_id' => site_url('tbl_penjualan/create_action_nmrkirim_update_per_id_penjualan/'),
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
			'uuid_penjualan' => '',
			'filter_bulan_penjualan' => $filter_bulan_penjualan,
			'Data_stock' => $Data_stock,
			'action_ubah_detail_nomor_kirim' => site_url('tbl_penjualan/action_ubah_detail_nomor_kirim'),
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


	/**
	 * URL kembali setelah simpan/validasi barang penjualan.
	 * Kasir (uuid ada) â†’ kasir_penjualan/{uuid}; penjualan baru â†’ create.
	 */
	private function _url_kembali_setelah_simpan_barang($uuid_penjualan)
	{
		$uuid_penjualan = trim((string) $uuid_penjualan);
		if ($uuid_penjualan !== '' && strtolower($uuid_penjualan) !== 'new') {
			return site_url('tbl_penjualan/kasir_penjualan/' . $uuid_penjualan);
		}
		return site_url('tbl_penjualan/create');
	}

	private function _redirect_setelah_simpan_barang($uuid_penjualan, $message = null)
	{
		if ($message !== null && $message !== '') {
			$this->session->set_flashdata('message', $message);
		}
		redirect($this->_url_kembali_setelah_simpan_barang($uuid_penjualan));
	}

	private function _is_ajax_penjualan_request()
	{
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
			&& strtolower((string) $_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
			return true;
		}
		if ((string) $this->input->post('ajax', TRUE) === '1') {
			return true;
		}
		if ((string) $this->input->post('redirect_rekap', TRUE) === '1') {
			return true;
		}
		return false;
	}

	private function _respon_simpan_barang($ok, $message, $uuid_penjualan, $extra = array())
	{
		$redirect = $this->_url_kembali_setelah_simpan_barang($uuid_penjualan);
		if ($this->_is_ajax_penjualan_request()) {
			$this->output->set_content_type('application/json');
			echo json_encode(array_merge(array(
				'ok' => (bool) $ok,
				'message' => (string) $message,
				'redirect' => $redirect,
				'uuid_penjualan' => $uuid_penjualan,
			), is_array($extra) ? $extra : array()));
			return;
		}
		if ($ok) {
			$this->session->set_flashdata('message', $message !== '' ? $message : 'Barang penjualan berhasil ditambahkan.');
			redirect($redirect);
			return;
		}
		$this->_redirect_setelah_simpan_barang($uuid_penjualan, $message);
	}

	public function create_action_simpan_barang($uuid_penjualan = null, $id_persediaan_barang = null)
	{
		$this->load->helper('pembelian_persediaan');

		$uuid_penjualan = trim((string) $uuid_penjualan);
		if ($uuid_penjualan === '') {
			$uuid_penjualan = trim((string) $this->input->post('uuid_penjualan', TRUE));
		}
		if ($uuid_penjualan === '') {
			$uuid_penjualan = trim((string) $this->input->post('uuid_penjualan_proses', TRUE));
		}
		if ($uuid_penjualan === '') {
			$uuid_penjualan = 'new';
		}

		$id_persediaan_barang = (int) $id_persediaan_barang;
		if ($id_persediaan_barang <= 0) {
			$id_persediaan_barang = (int) $this->input->post('id_persediaan_barang', TRUE);
		}

		$uuid_persediaan_post = trim((string) $this->input->post('uuid_persediaan', TRUE));

		$is_new = (strtolower($uuid_penjualan) === 'new');
		$row_header = null;
		if (!$is_new) {
			$row_header = $this->Tbl_penjualan_model->get_ROW_by_uuid_penjualan_first_row($uuid_penjualan);
			if (empty($row_header)) {
				$this->_respon_simpan_barang(false, 'Data penjualan (uuid) tidak ditemukan.', 'new');
				return;
			}
		}

		// Ambil data barang dari tabel persediaan via uuid_persediaan (utama) / id
		$data_barang = null;
		if ($uuid_persediaan_post !== '') {
			$data_barang = $this->Persediaan_model->get_by_uuid_persediaan($uuid_persediaan_post);
			if (empty($data_barang)) {
				$data_barang = $this->db->where('uuid_persediaan', $uuid_persediaan_post)->get('persediaan')->row();
			}
		}
		if (empty($data_barang) && $id_persediaan_barang > 0) {
			$data_barang = $this->Persediaan_model->get_by_id($id_persediaan_barang);
			if (empty($data_barang)) {
				$data_barang = $this->db->where('id', $id_persediaan_barang)->get('persediaan')->row();
			}
		}
		if (empty($data_barang)) {
			$this->_respon_simpan_barang(
				false,
				'Barang persediaan tidak ditemukan'
					. ($uuid_persediaan_post !== '' ? ' (uuid_persediaan: ' . $uuid_persediaan_post . ')' : '')
					. ($id_persediaan_barang > 0 ? ' (id: ' . $id_persediaan_barang . ')' : '') . '.',
				$uuid_penjualan
			);
			return;
		}

		$id_persediaan_barang = (int) $data_barang->id;
		$uuid_persediaan = trim((string) (isset($data_barang->uuid_persediaan) ? $data_barang->uuid_persediaan : ''));
		if ($uuid_persediaan === '') {
			$this->_respon_simpan_barang(false, 'Barang persediaan tidak memiliki uuid_persediaan.', $uuid_penjualan);
			return;
		}
		if ($uuid_persediaan_post !== '' && $uuid_persediaan_post !== $uuid_persediaan) {
			$this->_respon_simpan_barang(false, 'Data barang tidak sesuai (uuid_persediaan).', $uuid_penjualan);
			return;
		}

		// uuid_barang opsional: pakai dari persediaan jika ada, jika kosong pakai uuid_persediaan
		$uuid_barang_simpan = trim((string) (isset($data_barang->uuid_barang) ? $data_barang->uuid_barang : ''));
		if ($uuid_barang_simpan === '') {
			$uuid_barang_simpan = $uuid_persediaan;
		}

		if ($this->db->field_exists('kategori', 'persediaan') && penjualan_is_kategori_jasa(isset($data_barang->kategori) ? $data_barang->kategori : '')) {
			$this->_respon_simpan_barang(false, 'Item kategori Jasa tidak dapat dijual melalui Input Barang Penjualan.', $uuid_penjualan);
			return;
		}

		// Header transaksi: POST dulu, fallback ke data penjualan existing (kasir ubah)
		$tgl_jual_simpan = trim((string) $this->input->post('tgl_jual', TRUE));
		$Get_uuid_unit = trim((string) $this->input->post('uuid_unit', TRUE));
		$uuid_konsumen = trim((string) $this->input->post('uuid_konsumen', TRUE));
		$nmrpesan = trim((string) $this->input->post('nmrpesan', TRUE));
		$nmrkirim = trim((string) $this->input->post('nmrkirim', TRUE));

		if (!$is_new && !empty($row_header)) {
			if ($tgl_jual_simpan === '' && !empty($row_header->tgl_jual)) {
				$tgl_jual_simpan = penjualan_format_tgl_jual_tampil($row_header->tgl_jual);
			}
			if ($Get_uuid_unit === '' && !empty($row_header->uuid_unit)) {
				$Get_uuid_unit = trim((string) $row_header->uuid_unit);
			}
			if ($uuid_konsumen === '' && !empty($row_header->uuid_konsumen)) {
				$uuid_konsumen = trim((string) $row_header->uuid_konsumen);
			}
			if ($nmrpesan === '' && isset($row_header->nmrpesan)) {
				$nmrpesan = (string) $row_header->nmrpesan;
			}
			if ($nmrkirim === '' && isset($row_header->nmrkirim)) {
				$nmrkirim = (string) $row_header->nmrkirim;
			}
		}

		if ($tgl_jual_simpan === '') {
			$this->_respon_simpan_barang(false, 'Tgl Jual wajib diisi.', $uuid_penjualan);
			return;
		}

		$Get_nama_unit = '';
		if ($Get_uuid_unit !== '') {
			$this->db->where('uuid_unit', $Get_uuid_unit);
			$sys_unit_data = $this->db->get('sys_unit');
			if ($sys_unit_data->num_rows() > 0) {
				$Get_unit_data = $sys_unit_data->row_array();
				$Get_nama_unit = $Get_unit_data['nama_unit'];
			} elseif (!$is_new && !empty($row_header->unit)) {
				$Get_nama_unit = (string) $row_header->unit;
			}
		}
		if ($Get_uuid_unit === '' || $Get_nama_unit === '') {
			$this->_respon_simpan_barang(false, 'Unit penjualan wajib dipilih.', $uuid_penjualan);
			return;
		}

		$data_nama_konsumen = '';
		if ($uuid_konsumen !== '') {
			$data_konsumen = $this->Sys_konsumen_model->get_by_uuid_konsumen($uuid_konsumen);
			if (empty($data_konsumen)) {
				$data_konsumen = $this->Sys_unit_model->get_by_uuid_unit($uuid_konsumen);
				if (!empty($data_konsumen)) {
					$data_nama_konsumen = $data_konsumen->nama_unit;
				}
			} else {
				$data_nama_konsumen = $data_konsumen->nama_konsumen;
			}
		}
		if ($data_nama_konsumen === '' && !$is_new && !empty($row_header->konsumen_nama)) {
			$data_nama_konsumen = (string) $row_header->konsumen_nama;
		}
		if ($uuid_konsumen === '' || $data_nama_konsumen === '') {
			$this->_respon_simpan_barang(false, 'Konsumen / unit konsumen tidak ditemukan.', $uuid_penjualan);
			return;
		}

		$jumlah_simpan = preg_replace('/[^0-9]/', '', (string) $this->input->post('jumlah', TRUE));
		if ((int) $jumlah_simpan <= 0) {
			$this->_respon_simpan_barang(false, 'Jumlah barang wajib diisi dan lebih dari 0.', $uuid_penjualan);
			return;
		}

		$hasil_ensure_unit = penjualan_ensure_persediaan_kolom_unit($this, $Get_uuid_unit);
		if (empty($hasil_ensure_unit['ok'])) {
			$this->_respon_simpan_barang(
				false,
				isset($hasil_ensure_unit['message']) ? $hasil_ensure_unit['message'] : 'Gagal menyiapkan kolom unit di persediaan.',
				$uuid_penjualan
			);
			return;
		}

		$kolom_unit_simpan = penjualan_resolve_kolom_persediaan_unit($this, $Get_uuid_unit);
		$sisa_stock_simpan = penjualan_get_sisa_stock_penjualan($data_barang, $kolom_unit_simpan);
		if ((int) $jumlah_simpan > $sisa_stock_simpan) {
			$this->_respon_simpan_barang(
				false,
				'Jumlah melebihi stok tersedia (' . (int) $sisa_stock_simpan . ').',
				$uuid_penjualan
			);
			return;
		}

		$ts_jual = pembelian_parse_tanggal_po($tgl_jual_simpan);
		if ($ts_jual === false) {
			$ts_jual = strtotime(str_replace('/', '-', $tgl_jual_simpan));
		}
		if ($ts_jual === false) {
			$this->_respon_simpan_barang(false, 'Format Tgl Jual tidak valid.', $uuid_penjualan);
			return;
		}
		$tgl_jual_X = date('Y-m-d', $ts_jual);

		$harga_satuan_simpan = str_replace(',', '.', str_replace('.', '', (string) $this->input->post('harga_satuan_beli', TRUE)));
		if ($harga_satuan_simpan === '' || !is_numeric($harga_satuan_simpan)) {
			$harga_satuan_simpan = isset($data_barang->hpp) ? $data_barang->hpp : 0;
		}
		$total_nominal_simpan = ((int) $jumlah_simpan) * (float) $harga_satuan_simpan;

		$data = array(
			'tgl_input' => date('Y-m-d H:i:s'),
			'tgl_jual' => $tgl_jual_X,
			'nmrpesan' => $nmrpesan,
			'nmrkirim' => $nmrkirim,
			'uuid_unit' => $Get_uuid_unit,
			'unit' => $Get_nama_unit,
			'uuid_konsumen' => $uuid_konsumen,
			'konsumen_nama' => $data_nama_konsumen,
			'uuid_persediaan' => $uuid_persediaan,
			'id_persediaan_barang' => $id_persediaan_barang,
			'uuid_barang' => $uuid_barang_simpan,
			'kode_barang' => isset($data_barang->kode_barang) ? $data_barang->kode_barang : '',
			'nama_barang' => isset($data_barang->namabarang) ? $data_barang->namabarang : '',
			'proses_bayar' => 'belum_bayar',
			'jumlah' => (int) $jumlah_simpan,
			'satuan' => isset($data_barang->satuan) ? $data_barang->satuan : '',
			'harga_satuan' => $harga_satuan_simpan,
			'total_nominal' => $total_nominal_simpan,
			'id_usr' => 1,
		);

		if ($is_new) {
			$uuid_penjualan = $this->Tbl_penjualan_model->insert_new($data);
		} else {
			$data['uuid_penjualan'] = $uuid_penjualan;
			$uuid_hasil = $this->Tbl_penjualan_model->insert_add_barang($data);
			if (empty($uuid_hasil)) {
				$db_err = $this->db->error();
				$msg_db = !empty($db_err['message']) ? $db_err['message'] : 'Insert penjualan gagal.';
				$this->_respon_simpan_barang(false, $msg_db, $uuid_penjualan);
				return;
			}
		}

		if ($uuid_penjualan === '' || $uuid_penjualan === null || strtolower((string) $uuid_penjualan) === 'new') {
			$this->_respon_simpan_barang(false, 'Gagal menyimpan data penjualan (UUID tidak valid).', 'new');
			return;
		}

		$hasil_persediaan = penjualan_update_persediaan_saat_jual(
			$this,
			$id_persediaan_barang,
			$Get_uuid_unit,
			$jumlah_simpan,
			'tambah'
		);

		if (empty($hasil_persediaan['ok'])) {
			$this->_respon_simpan_barang(
				false,
				isset($hasil_persediaan['message'])
					? ($hasil_persediaan['message'] . ' (barang penjualan sudah tersimpan; periksa stok persediaan)')
					: 'Gagal memperbarui persediaan.',
				$uuid_penjualan
			);
			return;
		}

		$this->_respon_simpan_barang(true, 'Barang penjualan berhasil ditambahkan.', $uuid_penjualan, array(
			'id_persediaan_barang' => $id_persediaan_barang,
			'jumlah' => (int) $jumlah_simpan,
			'nama_barang' => $data['nama_barang'],
		));
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
			'action_ubah_detail_nomor_kirim' => site_url('tbl_penjualan/action_ubah_detail_nomor_kirim'),
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
		$this->load->helper('pembelian_persediaan');

		$uuid_penjualan = trim((string) $this->input->post('uuid_penjualan_proses', TRUE));
		if ($uuid_penjualan === '') {
			$uuid_penjualan = trim((string) func_get_arg(1));
		}

		$redirect_kasir = ($uuid_penjualan !== '' && $uuid_penjualan !== 'new')
			? site_url('tbl_penjualan/kasir_penjualan/' . $uuid_penjualan)
			: site_url('tbl_penjualan');

		if ($uuid_penjualan === '' || $uuid_penjualan === 'new') {
			$this->_penjualan_respon_simpan_detail(false, 'UUID penjualan tidak valid.', $redirect_kasir);
			return;
		}

		$rows_penjualan = $this->Tbl_penjualan_model->get_all_by_uuid_penjualan($uuid_penjualan);
		if (empty($rows_penjualan)) {
			$this->_penjualan_respon_simpan_detail(false, 'Data penjualan tidak ditemukan.', site_url('tbl_penjualan'));
			return;
		}

		$row_awal = $rows_penjualan[0];
		$tgl_jual_post = trim((string) $this->input->post('tgl_jual', TRUE));

		if ($tgl_jual_post !== '') {
			$bulan_lama = penjualan_get_bulan_key_from_tgl($row_awal->tgl_jual);
			$bulan_baru = penjualan_get_bulan_key_from_tgl($tgl_jual_post);
			if ($bulan_baru !== '' && $bulan_lama !== '' && $bulan_baru !== $bulan_lama) {
				$this->_penjualan_respon_simpan_detail(
					false,
					'Tgl Jual tidak boleh diubah ke bulan lain karena sudah ada data barang penjualan pada bulan persediaan saat ini.',
					$redirect_kasir
				);
				return;
			}
		} elseif (isset($row_awal->tgl_jual) && trim((string) $row_awal->tgl_jual) !== '') {
			$tgl_jual_post = penjualan_format_tgl_jual_tampil($row_awal->tgl_jual);
		}

		$ts_jual = pembelian_parse_tanggal_po($tgl_jual_post);
		if ($ts_jual === false) {
			$ts_jual = strtotime(str_replace('/', '-', $tgl_jual_post));
		}
		if ($ts_jual === false || date('Y', $ts_jual) < 2020) {
			$date_jual = date('Y-m-d H:i:s');
		} else {
			$date_jual = date('Y-m-d H:i:s', $ts_jual);
		}

		$NomorKirim_baru = trim((string) $this->input->post('nmrkirim', TRUE));
		$NomorPesan_baru = trim((string) $this->input->post('nmrpesan', TRUE));
		$GET_uuid_konsumen = trim((string) $this->input->post('uuid_konsumen', TRUE));
		$GET_uuid_unit = trim((string) $this->input->post('uuid_unit', TRUE));

		$Get_uuid_unit = isset($row_awal->uuid_unit) ? trim((string) $row_awal->uuid_unit) : '';
		$Get_nama_unit = isset($row_awal->unit) ? trim((string) $row_awal->unit) : '';
		if ($GET_uuid_unit !== '') {
			$sys_unit_data = $this->db->get_where('sys_unit', array('uuid_unit' => $GET_uuid_unit));
			if ($sys_unit_data->num_rows() > 0) {
				$Get_unit_data = $sys_unit_data->row_array();
				$Get_uuid_unit = $GET_uuid_unit;
				$Get_nama_unit = $Get_unit_data['nama_unit'];
			}
		}

		$nama_konsumen = isset($row_awal->konsumen_nama) ? trim((string) $row_awal->konsumen_nama) : '';
		if ($GET_uuid_konsumen !== '') {
			$GET_sys_konsumen = $this->db->get_where('sys_konsumen', array('uuid_konsumen' => $GET_uuid_konsumen));
			if ($GET_sys_konsumen->num_rows() > 0) {
				$nama_konsumen = $GET_sys_konsumen->row()->nama_konsumen;
			} else {
				$GET_sys_unit = $this->db->get_where('sys_unit', array('uuid_unit' => $GET_uuid_konsumen));
				if ($GET_sys_unit->num_rows() > 0) {
					$nama_konsumen = $GET_sys_unit->row()->nama_unit;
				}
			}
		}

		$uuid_unit_lama = isset($row_awal->uuid_unit) ? trim((string) $row_awal->uuid_unit) : '';
		$uuid_unit_baru = $Get_uuid_unit;

		// Ubah header transaksi (unit/konsumen/nomor) tidak memindahkan kolom unit di persediaan.
		// Migrasi kolom persediaan hanya relevan saat input/ubah barang, bukan saat koreksi header.

		$update_data = array(
			'tgl_jual' => $date_jual,
			'nmrkirim' => $NomorKirim_baru,
			'nmrpesan' => $NomorPesan_baru,
			'uuid_konsumen' => $GET_uuid_konsumen,
			'konsumen_nama' => $nama_konsumen,
			'uuid_unit' => $Get_uuid_unit,
			'unit' => $Get_nama_unit,
		);

		$this->db->where('uuid_penjualan', $uuid_penjualan);
		$ok_update = $this->db->update('tbl_penjualan', $update_data);

		if (!$ok_update) {
			$this->_penjualan_respon_simpan_detail(false, 'Gagal menyimpan perubahan detail penjualan.', $redirect_kasir);
			return;
		}

		$this->_penjualan_respon_simpan_detail(true, 'Perubahan detail penjualan berhasil disimpan.', $redirect_kasir);
	}

	private function _penjualan_respon_simpan_detail($ok, $message, $redirect_url)
	{
		if ($this->input->is_ajax_request()) {
			$this->output->set_content_type('application/json');
			echo json_encode(array(
				'ok' => (bool) $ok,
				'message' => (string) $message,
				'redirect' => (string) $redirect_url,
			));
			return;
		}

		$this->session->set_flashdata('message', $message);
		redirect($redirect_url);
	}


	public function create_action_nmrkirim_update_per_id_penjualan($id)
	{
		$is_ajax = $this->_is_ajax_penjualan_request();

		try {
			$row_penjualan = $this->Tbl_penjualan_model->get_by_id($id);
			if (empty($row_penjualan)) {
				$pesan = 'Data penjualan tidak ditemukan.';
				$this->session->set_flashdata('message', $pesan);
				$redirect_url = penjualan_resolve_redirect_setelah_ubah_hapus($this);
				if ($this->_penjualan_respond_ubah_barang_ajax(false, $pesan, $redirect_url)) {
					return;
				}
				redirect($redirect_url);
				return;
			}

			$jumlah_Jual_ubah = preg_replace("/[^0-9]/", "", $this->input->post('jumlah', TRUE));
			$harga_satuan_x = penjualan_parse_harga_satuan_input($this->input->post('harga_satuan', TRUE));
			$konfirmasi_ubah_harga = ($this->input->post('konfirmasi_ubah_harga', TRUE) === '1');

			$hasil = penjualan_proses_ubah_barang_per_id($this, $id, $jumlah_Jual_ubah, $harga_satuan_x, $konfirmasi_ubah_harga);
			if (!is_array($hasil)) {
				throw new Exception('Fungsi proses ubah barang tidak mengembalikan data valid.');
			}

			$ok = !empty($hasil['ok']);
			if (!$ok) {
				$pesan = isset($hasil['message']) ? $hasil['message'] : 'Gagal memperbarui data penjualan.';
				$this->session->set_flashdata('message', $pesan);
			} else {
				$pesan = 'Proses update selesai. Data penjualan berhasil diperbarui.';
				$this->session->set_flashdata('message', $pesan);
			}

			$uuid_penjualan = !empty($hasil['uuid_penjualan'])
				? $hasil['uuid_penjualan']
				: (isset($row_penjualan->uuid_penjualan) ? $row_penjualan->uuid_penjualan : '');
			$redirect_url = penjualan_resolve_redirect_setelah_ubah_hapus($this, $uuid_penjualan);
			if ($this->_penjualan_respond_ubah_barang_ajax($ok, $pesan, $redirect_url)) {
				return;
			}
			redirect($redirect_url);
		} catch (Exception $e) {
			$pesan = 'Error: ' . $e->getMessage();
			log_message('error', 'create_action_nmrkirim_update_per_id_penjualan id=' . (int) $id . ' — ' . $e->getMessage());
			if ($is_ajax) {
				$redirect_url = penjualan_resolve_redirect_setelah_ubah_hapus($this);
				$this->_penjualan_respond_ubah_barang_ajax(false, $pesan, $redirect_url);
				return;
			}
			$this->session->set_flashdata('message', $pesan);
			redirect(penjualan_resolve_redirect_setelah_ubah_hapus($this));
		} catch (Throwable $e) {
			$pesan = 'Error fatal: ' . $e->getMessage();
			log_message('error', 'create_action_nmrkirim_update_per_id_penjualan id=' . (int) $id . ' — ' . $e->getMessage());
			if ($is_ajax) {
				$redirect_url = penjualan_resolve_redirect_setelah_ubah_hapus($this);
				$this->_penjualan_respond_ubah_barang_ajax(false, $pesan, $redirect_url);
				return;
			}
			$this->session->set_flashdata('message', $pesan);
			redirect(penjualan_resolve_redirect_setelah_ubah_hapus($this));
		}
	}

	private function _penjualan_respond_ubah_barang_ajax($ok, $message, $redirect_url)
	{
		if (!$this->_is_ajax_penjualan_request()) {
			return false;
		}

		while (ob_get_level() > 0) {
			ob_end_clean();
		}

		$this->output->set_content_type('application/json', 'utf-8');
		echo json_encode(array(
			'ok' => (bool) $ok,
			'message' => (string) $message,
			'redirect_url' => (string) $redirect_url,
		), JSON_UNESCAPED_UNICODE);
		exit;
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
		$konsumen_nama_selected = $data_master_penjualan_per_uuidpenjualan->konsumen_nama;
		$data = array(
			'data_penjualan' => penjualan_enrich_data_cetak_penjualan($this, $rows_penjualan),
			'nmr_pesan_selected' => $data_master_penjualan_per_uuidpenjualan->nmrpesan,
			'tgl_jual_selected' => date("d M Y", strtotime($data_master_penjualan_per_uuidpenjualan->tgl_jual)),
			'konsumen_nama_selected' => $konsumen_nama_selected,
			'konsumen_nama_kepada_yth_lines' => wrap_kepada_yth_nama_cetak_lines($konsumen_nama_selected),
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

	public function delete($id = null, $uuid_penjualan = null, $source_form = null)
	{
		$row = $this->Tbl_penjualan_model->get_by_id($id);

		if (empty($row)) {
			$this->session->set_flashdata('message', 'Record Not Found');
			redirect(penjualan_resolve_redirect_setelah_ubah_hapus($this, $uuid_penjualan, $source_form));
			return;
		}

		// print_r($row);
		// print_r("<br/>");
		// print_r("<br/>");
		// print_r("<br/>");
		// die;

		// CEK APAKAH ADA DATA DI PERSEDIAAN SESUAI UUID_PERSEDIAAN ATAU ID ATAU NAMA BARANG , SATUAN DAN HARGA SATUANYA
		// JIKA TIDAK ADA DI DATA PERSEDIAAN MAKA LANGSUNG HAPUS SAJA YANG DI PENJUALAN TANPA PERHATIKAN DATA DI PERSEDIAAN 
		// KARENA KEMUNGKINAN DATA TIDAK SINKRON DENGAN DATA PERSEDIAAN , JADI HAPUS SAJA BAGIAN PENJUALAN


		$this->db->where('uuid_persediaan', $row->uuid_persediaan);
		$DATA_persediaan = $this->db->get('persediaan');

		if ($DATA_persediaan->num_rows() > 0) {
			// print_r("ADa record berdasarkan uuid_persediaan");


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

				redirect(penjualan_resolve_redirect_setelah_ubah_hapus($this, $uuid_penjualan, $source_form));
			} else {
				$this->session->set_flashdata('message', 'Record Not Found');
				redirect(penjualan_resolve_redirect_setelah_ubah_hapus($this, $uuid_penjualan, $source_form));
			}
		} else {
			// print_r("TIDAK ADA DATA");
			// hapus data di penjualan berdasarkan id penjualan dan kembali ke halaman source
			$this->Tbl_penjualan_model->delete($id);
			redirect(penjualan_resolve_redirect_setelah_ubah_hapus($this, $uuid_penjualan, $source_form));
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
		$Get_date_awal = $this->session->userdata('filter_setting_kode_akun_penjualan_date_awal');
		$Get_date_akhir = $this->session->userdata('filter_setting_kode_akun_penjualan_date_akhir');
		if (empty($Get_date_awal) || empty($Get_date_akhir)) {
			$Get_date_awal = date('Y-m-1 00:00:00');
			$Get_date_akhir = date('Y-m-t 23:59:59');
		}

		$spop_filter = trim((string) $this->input->get('spop_filter', TRUE));
		if ($spop_filter === '') {
			$spop_filter = trim((string) $this->session->userdata('filter_setting_kode_akun_penjualan_spop'));
		}

		$this->_render_setting_kode_akun_penjualan2($Get_date_awal, $Get_date_akhir, $spop_filter);
	}

	public function cari_between_date_setting_kode_akun_penjualan()
	{
		$tgl_awal_post = $this->input->post('tgl_awal', TRUE);
		$tgl_akhir_post = $this->input->post('tgl_akhir', TRUE);
		$spop_filter = trim((string) $this->input->post('spop', TRUE));

		if ($tgl_awal_post || $tgl_akhir_post || $this->input->post('spop', TRUE) !== null) {
			list($Get_date_awal, $Get_date_akhir) = $this->_parse_cari_between_dates($tgl_awal_post, $tgl_akhir_post);
			$this->session->set_userdata('filter_setting_kode_akun_penjualan_date_awal', $Get_date_awal);
			$this->session->set_userdata('filter_setting_kode_akun_penjualan_date_akhir', $Get_date_akhir);
			$this->session->set_userdata('filter_setting_kode_akun_penjualan_spop', $spop_filter);
			$redirect_url = 'Tbl_penjualan/setting_kode_akun_penjualan2?keep_filter=1';
			if ($spop_filter !== '') {
				$redirect_url .= '&spop_filter=' . rawurlencode($spop_filter);
			}
			redirect(site_url($redirect_url));
			return;
		}

		$Get_date_awal = $this->session->userdata('filter_setting_kode_akun_penjualan_date_awal');
		$Get_date_akhir = $this->session->userdata('filter_setting_kode_akun_penjualan_date_akhir');
		if (empty($Get_date_awal) || empty($Get_date_akhir)) {
			$Get_date_awal = date('Y-m-1 00:00:00');
			$Get_date_akhir = date('Y-m-t 23:59:59');
		}
		$spop_filter = trim((string) $this->session->userdata('filter_setting_kode_akun_penjualan_spop'));
		$this->_render_setting_kode_akun_penjualan2($Get_date_awal, $Get_date_akhir, $spop_filter);
	}

	private function _render_setting_kode_akun_penjualan2($Get_date_awal, $Get_date_akhir, $spop_filter = '')
	{
		$spop_filter = trim((string) $spop_filter);

		$this->session->set_userdata('filter_setting_kode_akun_penjualan_date_awal', $Get_date_awal);
		$this->session->set_userdata('filter_setting_kode_akun_penjualan_date_akhir', $Get_date_akhir);
		$this->session->set_userdata('filter_setting_kode_akun_penjualan_spop', $spop_filter);

		$Tbl_penjualan = $this->Tbl_penjualan_model->get_for_setting_kode_akun_by_tgl_range($Get_date_awal, $Get_date_akhir, $spop_filter);
		$this->session->set_userdata('filter_setting_kode_akun_penjualan_ids', $this->_collect_row_ids($Tbl_penjualan));

		$compare_bulan_num = (int) date('m', strtotime($Get_date_awal));
		$compare_tahun_num = (int) date('Y', strtotime($Get_date_awal));
		$nama_bulan_id = array(
			1 => 'Januari',
			2 => 'Februari',
			3 => 'Maret',
			4 => 'April',
			5 => 'Mei',
			6 => 'Juni',
			7 => 'Juli',
			8 => 'Agustus',
			9 => 'September',
			10 => 'Oktober',
			11 => 'November',
			12 => 'Desember',
		);
		$active_tab = trim((string) $this->input->get('tab', TRUE));
		if ($active_tab !== 'compare') {
			$active_tab = 'data';
		}

		$data = array(
			'Tbl_penjualan_data' => $Tbl_penjualan,
			'date_awal' => $Get_date_awal,
			'date_akhir' => $Get_date_akhir,
			'spop_filter' => $spop_filter,
			'search_filter' => trim((string) $this->input->get('search_filter', TRUE)),
			'start' => 0,
			'compare_bulan_num' => $compare_bulan_num,
			'compare_tahun_num' => $compare_tahun_num,
			'nama_bulan_id' => $nama_bulan_id,
			'gen_tahun_min' => 2019,
			'gen_tahun_max' => (int) date('Y') + 1,
			'active_tab' => $active_tab,
			'url_compare_jurnal_penjualan_run' => site_url('Tbl_penjualan/ajax_compare_jurnal_penjualan_manual_online'),
			'url_compare_jurnal_penjualan_excel' => site_url('Tbl_penjualan/excel_compare_jurnal_penjualan_manual_online'),
			'url_compare_jurnal_penjualan_import_csv' => site_url('Tbl_penjualan/ajax_compare_import_csv_jurnal_penjualan'),
			'url_compare_jurnal_penjualan_check_csv' => site_url('Tbl_penjualan/ajax_compare_check_csv_jurnal_penjualan'),
			'url_compare_jurnal_penjualan_validate_csv' => site_url('Tbl_penjualan/ajax_compare_validate_csv_jurnal_penjualan'),
			'url_compare_jurnal_penjualan_tabel_list' => site_url('Tbl_penjualan/ajax_compare_tabel_list_jurnal_penjualan'),
			'url_compare_jurnal_penjualan_tabel_preview' => site_url('Tbl_penjualan/ajax_compare_tabel_preview_jurnal_penjualan'),
		);

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_setting_kode_akun', $data);
	}

	public function excel_setting_kode_akun_penjualan()
	{
		$this->load->helper('exportexcel');

		$rows = array();
		$ids_param = $this->input->get('ids', TRUE);
		if (!empty($ids_param)) {
			$ids = array_values(array_unique(array_filter(array_map('intval', explode(',', $ids_param)))));
			if (!empty($ids)) {
				$rows = $this->Tbl_penjualan_model->get_for_setting_kode_akun_by_ids($ids);
			}
		}

		if (empty($rows)) {
			$session_ids = $this->session->userdata('filter_setting_kode_akun_penjualan_ids');
			if (is_array($session_ids) && count($session_ids) > 0) {
				$rows = $this->Tbl_penjualan_model->get_for_setting_kode_akun_by_ids($session_ids);
			}
		}

		if (empty($rows)) {
			$Get_date_awal = $this->session->userdata('filter_setting_kode_akun_penjualan_date_awal');
			$Get_date_akhir = $this->session->userdata('filter_setting_kode_akun_penjualan_date_akhir');
			$spop_filter = trim((string) $this->session->userdata('filter_setting_kode_akun_penjualan_spop'));
			if (empty($Get_date_awal) || empty($Get_date_akhir)) {
				$Get_date_awal = date('Y-m-1 00:00:00');
				$Get_date_akhir = date('Y-m-t 23:59:59');
			}
			$rows = $this->Tbl_penjualan_model->get_for_setting_kode_akun_by_tgl_range($Get_date_awal, $Get_date_akhir, $spop_filter);
		}

		$export_rows = $this->_build_setting_kode_akun_penjualan_excel_rows($rows);
		$date_awal_display = trim((string) $this->input->get('date_awal_display', TRUE));
		$date_akhir_display = trim((string) $this->input->get('date_akhir_display', TRUE));
		$filter_text = trim((string) $this->input->get('filter_text', TRUE));
		$spop_filter_display = trim((string) $this->input->get('spop_filter', TRUE));
		if ($date_awal_display === '') {
			$date_awal_display = trim((string) $this->session->userdata('filter_setting_kode_akun_penjualan_date_awal'));
		}
		if ($date_akhir_display === '') {
			$date_akhir_display = trim((string) $this->session->userdata('filter_setting_kode_akun_penjualan_date_akhir'));
		}
		if ($spop_filter_display === '') {
			$spop_filter_display = trim((string) $this->session->userdata('filter_setting_kode_akun_penjualan_spop'));
		}
		if ($filter_text === '') {
			$filter_text = '-';
		}

		$namaFile = 'kode_akun_penjualan_' . date('Y-m-d_H-i-s') . '.xlsx';
		$tablehead = 4;
		$tablebody = 5;

		excel_prepare_download($namaFile);
		xlsBOF();
		xlsWriteLabel(0, 0, 'Laporan Setting Kode Akun Penjualan');
		xlsWriteLabel(1, 0, 'Periode Tanggal Awal');
		xlsWriteLabel(1, 1, $date_awal_display);
		xlsWriteLabel(1, 2, 'sampai Tanggal Akhir');
		xlsWriteLabel(1, 3, $date_akhir_display);
		xlsWriteLabel(2, 0, 'Filter SPOP');
		xlsWriteLabel(2, 1, $spop_filter_display !== '' ? $spop_filter_display : '-');
		xlsWriteLabel(3, 0, 'Filter Data');
		xlsWriteLabel(3, 1, $filter_text);

		$kolomhead = 0;
		xlsWriteLabel($tablehead, $kolomhead++, 'No');
		xlsWriteLabel($tablehead, $kolomhead++, 'Kode Akun');
		xlsWriteLabel($tablehead, $kolomhead++, 'SPOP');
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

		foreach ($export_rows as $row) {
			$kolombody = 0;
			if ($row['is_number']) {
				xlsWriteNumber($tablebody, $kolombody++, $row['no']);
			} else {
				xlsWriteLabel($tablebody, $kolombody++, $row['no']);
			}
			xlsWriteLabel($tablebody, $kolombody++, $row['kode_akun']);
			xlsWriteLabel($tablebody, $kolombody++, $row['spop'] !== '' && $row['spop'] !== null ? (string) $row['spop'] : '');
			xlsWriteLabel($tablebody, $kolombody++, $row['tgl_jual']);
			xlsWriteLabel($tablebody, $kolombody++, $row['nmrkirim']);
			xlsWriteLabel($tablebody, $kolombody++, $row['nmrpesan']);
			xlsWriteLabel($tablebody, $kolombody++, $row['konsumen_nama']);
			xlsWriteLabel($tablebody, $kolombody++, $row['kode_barang']);
			xlsWriteLabel($tablebody, $kolombody++, $row['nama_barang']);
			xlsWriteLabel($tablebody, $kolombody++, $row['unit']);
			xlsWriteLabel($tablebody, $kolombody++, $row['satuan']);
			if ($row['harga_satuan'] !== '') {
				xlsWriteRupiah($tablebody, $kolombody++, $row['harga_satuan']);
			} else {
				xlsWriteLabel($tablebody, $kolombody++, '');
			}
			if ($row['jumlah'] !== '') {
				xlsWriteNumber($tablebody, $kolombody++, $row['jumlah']);
			} else {
				xlsWriteLabel($tablebody, $kolombody++, '');
			}
			if ($row['total_harga'] !== '') {
				xlsWriteRupiah($tablebody, $kolombody++, $row['total_harga']);
			} else {
				xlsWriteLabel($tablebody, $kolombody++, '');
			}
			if ($row['umpphpsl22'] !== '') {
				xlsWriteRupiah($tablebody, $kolombody++, $row['umpphpsl22']);
			} else {
				xlsWriteLabel($tablebody, $kolombody++, '');
			}
			if ($row['piutang'] !== '') {
				xlsWriteRupiah($tablebody, $kolombody++, $row['piutang']);
			} else {
				xlsWriteLabel($tablebody, $kolombody++, '');
			}
			if ($row['penjualandpp'] !== '') {
				xlsWriteRupiah($tablebody, $kolombody++, $row['penjualandpp']);
			} else {
				xlsWriteLabel($tablebody, $kolombody++, '');
			}
			if ($row['utangppn'] !== '') {
				xlsWriteRupiah($tablebody, $kolombody++, $row['utangppn']);
			} else {
				xlsWriteLabel($tablebody, $kolombody++, '');
			}
			$tablebody++;
		}

		xlsEOF();
		exit();
	}

	private function _build_setting_kode_akun_penjualan_excel_rows($Tbl_penjualan_data)
	{
		$export_rows = array();
		$compare_nmr_kirim = 0;
		$compare_nmr_pesan = 0;
		$Total_Jumlah_per_nmrkirim = 0;
		$Total_UMPPHPSL22_per_nmrkirim = 0;
		$Total_piutang_per_nmrkirim = 0;
		$Total_penjualandpp_per_nmrkirim = 0;
		$Total_utangppn_per_nmrkirim = 0;
		$TOTAL_ALL_JUMLAH = 0;
		$TOTAL_ALL_UMPPHPSL22 = 0;
		$TOTAL_ALL_piutang = 0;
		$TOTAL_ALL_penjualandpp = 0;
		$TOTAL_ALL_utangppn = 0;
		$start = 0;
		$last_row = null;

		foreach ($Tbl_penjualan_data as $list_data) {
			if (($start >= 1) && (((string) $compare_nmr_kirim !== (string) $list_data->nmrkirim) || ((string) $compare_nmr_pesan !== (string) $list_data->nmrpesan))) {
				$export_rows[] = $this->_setting_kode_akun_penjualan_excel_total_row(++$start, $compare_nmr_kirim, $compare_nmr_pesan, $Total_Jumlah_per_nmrkirim, $Total_UMPPHPSL22_per_nmrkirim, $Total_piutang_per_nmrkirim, $Total_penjualandpp_per_nmrkirim, $Total_utangppn_per_nmrkirim);
				$Total_Jumlah_per_nmrkirim = 0;
				$Total_UMPPHPSL22_per_nmrkirim = 0;
				$Total_piutang_per_nmrkirim = 0;
				$Total_penjualandpp_per_nmrkirim = 0;
				$Total_utangppn_per_nmrkirim = 0;
			}

			$calc = $this->_penjualan_hitung_kolom_tampilan($list_data);
			$Total_Jumlah_per_nmrkirim += $calc['jumlah_total'];
			$Total_UMPPHPSL22_per_nmrkirim += $calc['umpphpsl22'];
			$Total_piutang_per_nmrkirim += $calc['piutang'];
			$Total_penjualandpp_per_nmrkirim += $calc['penjualandpp'];
			$Total_utangppn_per_nmrkirim += $calc['utangppn'];
			$TOTAL_ALL_JUMLAH += $calc['jumlah_total'];
			$TOTAL_ALL_UMPPHPSL22 += $calc['umpphpsl22'];
			$TOTAL_ALL_piutang += $calc['piutang'];
			$TOTAL_ALL_penjualandpp += $calc['penjualandpp'];
			$TOTAL_ALL_utangppn += $calc['utangppn'];

			$export_rows[] = array(
				'is_number' => true,
				'no' => ++$start,
				'kode_akun' => $list_data->kode_akun ? $list_data->kode_akun : '',
				'spop' => isset($list_data->spop) ? (string) $list_data->spop : '',
				'tgl_jual' => date('d M Y', strtotime($list_data->tgl_jual)),
				'nmrkirim' => $list_data->nmrkirim,
				'nmrpesan' => $list_data->nmrpesan,
				'konsumen_nama' => $list_data->konsumen_nama,
				'kode_barang' => $list_data->kode_barang,
				'nama_barang' => $list_data->nama_barang,
				'unit' => $list_data->unit,
				'satuan' => $list_data->satuan,
				'harga_satuan' => $list_data->harga_satuan,
				'jumlah' => $list_data->jumlah,
				'total_harga' => $calc['jumlah_total'],
				'umpphpsl22' => $calc['umpphpsl22'],
				'piutang' => $calc['piutang'],
				'penjualandpp' => $calc['penjualandpp'],
				'utangppn' => $calc['utangppn'],
				'is_total_nmrkirim' => false,
			);

			$compare_nmr_kirim = $list_data->nmrkirim;
			$compare_nmr_pesan = $list_data->nmrpesan;
			$last_row = $list_data;
		}

		if ($last_row) {
			$export_rows[] = $this->_setting_kode_akun_penjualan_excel_total_row(++$start, $last_row->nmrkirim, $last_row->nmrpesan, $Total_Jumlah_per_nmrkirim, $Total_UMPPHPSL22_per_nmrkirim, $Total_piutang_per_nmrkirim, $Total_penjualandpp_per_nmrkirim, $Total_utangppn_per_nmrkirim);
		}

		if (!empty($export_rows)) {
			$export_rows[] = array(
				'is_number' => false,
				'no' => '',
				'kode_akun' => '',
				'spop' => '',
				'tgl_jual' => '',
				'nmrkirim' => '',
				'nmrpesan' => '',
				'konsumen_nama' => '',
				'kode_barang' => '',
				'nama_barang' => '',
				'unit' => '',
				'satuan' => '',
				'harga_satuan' => 'TOTAL',
				'jumlah' => '',
				'total_harga' => $TOTAL_ALL_JUMLAH,
				'umpphpsl22' => $TOTAL_ALL_UMPPHPSL22,
				'piutang' => $TOTAL_ALL_piutang,
				'penjualandpp' => $TOTAL_ALL_penjualandpp,
				'utangppn' => $TOTAL_ALL_utangppn,
				'is_total_nmrkirim' => false,
			);
		}

		return $export_rows;
	}

	private function _setting_kode_akun_penjualan_excel_total_row($no, $nmrkirim, $nmrpesan, $total_jumlah, $total_umpphpsl22, $total_piutang, $total_penjualandpp, $total_utangppn)
	{
		return array(
			'is_number' => true,
			'no' => $no,
			'kode_akun' => '',
			'spop' => '',
			'tgl_jual' => 'TOTAL',
			'nmrkirim' => (string) $nmrkirim,
			'nmrpesan' => (string) $nmrpesan,
			'konsumen_nama' => '',
			'kode_barang' => '',
			'nama_barang' => '',
			'unit' => '',
			'satuan' => '',
			'harga_satuan' => '',
			'jumlah' => '',
			'total_harga' => $total_jumlah,
			'umpphpsl22' => $total_umpphpsl22,
			'piutang' => $total_piutang,
			'penjualandpp' => $total_penjualandpp,
			'utangppn' => $total_utangppn,
			'is_total_nmrkirim' => true,
		);
	}

	public function jurnal_penjualan2()
	{
		$Get_bulan_ns = $this->input->post('bulan_ns', TRUE);
		if ($Get_bulan_ns) {
			$this->session->set_userdata('jurnal_penjualan2_bulan_ns', $Get_bulan_ns);
		} else {
			$Get_bulan_ns = $this->session->userdata('jurnal_penjualan2_bulan_ns');
		}

		if (!$Get_bulan_ns) {
			$Get_bulan_ns = date("Y-m");
		}

		$Get_month_selected = date("m", strtotime($Get_bulan_ns . "-01"));
		$Get_YEAR_selected = date("Y", strtotime($Get_bulan_ns . "-01"));

		$Buku_besar_DATA = $this->_get_jurnal_penjualan2_rows($Get_month_selected, $Get_YEAR_selected);
		$Buku_besar_DATA_baris = $this->_prepare_jurnal_penjualan2_baris_rows($Buku_besar_DATA);
		$Buku_besar_DATA_baris = $this->_enrich_jurnal_penjualan2_baris_rows($Buku_besar_DATA_baris, $Get_month_selected, $Get_YEAR_selected);
		$jurnal_penjualan_per_unit_data = $this->_get_jurnal_penjualan2_per_unit_data_by_units($Get_month_selected, $Get_YEAR_selected);

		$Get_date_awal = sprintf('%04d-%02d-01 00:00:00', (int) $Get_YEAR_selected, (int) $Get_month_selected);
		$Get_date_akhir = date('Y-m-t 23:59:59', strtotime($Get_date_awal));

		$data = array(
			'Buku_besar_DATA_data' => $Buku_besar_DATA,
			'Buku_besar_DATA_baris' => $Buku_besar_DATA_baris,
			'jurnal_penjualan_per_unit_data' => $jurnal_penjualan_per_unit_data,
			'month_selected' => $Get_month_selected,
			'year_selected' => $Get_YEAR_selected,
			'bulan_ns_selected' => $Get_bulan_ns,
			'date_awal' => $Get_date_awal,
			'date_akhir' => $Get_date_akhir,
		);

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_list__jurnal_penjualan', $data);
	}

	public function ajax_bb_modal_jurnal_penjualan()
	{
		$this->load->helper('buku_besar_list');
		$parsed = buku_besar_parse_bulan_ns($this->input->post('bulan_ns', TRUE));
		$month = (int) $parsed['month'];
		$year = (int) $parsed['year'];
		$Buku_besar_DATA = $this->_get_jurnal_penjualan2_rows($month, $year);
		$Buku_besar_DATA_baris = $this->_prepare_jurnal_penjualan2_baris_rows($Buku_besar_DATA);
		$Buku_besar_DATA_baris = $this->_enrich_jurnal_penjualan2_baris_rows($Buku_besar_DATA_baris, $month, $year);
		$jurnal_penjualan_per_unit_data = $this->_get_jurnal_penjualan2_per_unit_data_by_units($month, $year);
		$data = array(
			'Buku_besar_DATA_data' => $Buku_besar_DATA,
			'Buku_besar_DATA_baris' => $Buku_besar_DATA_baris,
			'jurnal_penjualan_per_unit_data' => $jurnal_penjualan_per_unit_data,
			'month_selected' => $month,
			'year_selected' => $year,
			'bulan_ns_selected' => $parsed['bulan_ns'],
			'bulan_label' => buku_besar_bulan_teks($month) . ' ' . $year,
		);
		$this->load->view('anekadharma/buku_besar/partials/modal_jurnal_penjualan', $data);
	}

	private function _get_jurnal_penjualan2_rows($month_selected, $year_selected)
	{
		$GET_Source = "penjualan";
		$sql = "SELECT * FROM `buku_besar` WHERE MONTH(`tanggal`)=$month_selected AND YEAR(`tanggal`)=$year_selected AND `source`='$GET_Source'  ORDER BY `pl`,`tanggal`,`id`";
		return $this->db->query($sql)->result();
	}

	private function _prepare_jurnal_penjualan2_baris_rows($buku_besar_data)
	{
		$groups = array();

		foreach ($buku_besar_data as $list_data) {
			$debet_val = isset($list_data->debet) ? (float) $list_data->debet : 0;
			$kredit_val = isset($list_data->kredit) ? (float) $list_data->kredit : 0;
			if ($debet_val == 0 && $kredit_val == 0) {
				continue;
			}

			$tanggal = isset($list_data->tanggal) ? date('Y-m-d', strtotime($list_data->tanggal)) : '';
			$bukti = isset($list_data->nokirim) ? (string) $list_data->nokirim : '';
			$group_key = $tanggal . '|' . $bukti;

			if (!isset($groups[$group_key])) {
				$groups[$group_key] = array(
					'debet' => array(),
					'kredit' => array(),
					'sort_pl' => isset($list_data->pl) ? (string) $list_data->pl : '',
					'sort_tanggal' => $tanggal,
					'sort_id' => isset($list_data->id) ? (int) $list_data->id : 0,
				);
			}

			if ($debet_val != 0) {
				$groups[$group_key]['debet'][] = $list_data;
			} else {
				$groups[$group_key]['kredit'][] = $list_data;
			}
		}

		uasort($groups, function ($a, $b) {
			$pl_cmp = strcmp($a['sort_pl'], $b['sort_pl']);
			if ($pl_cmp !== 0) {
				return $pl_cmp;
			}

			$tgl_cmp = strcmp($a['sort_tanggal'], $b['sort_tanggal']);
			if ($tgl_cmp !== 0) {
				return $tgl_cmp;
			}

			return $a['sort_id'] - $b['sort_id'];
		});

		$result = array();
		foreach ($groups as $group) {
			usort($group['kredit'], function ($a, $b) {
				$rek_a = isset($a->kode_akun) ? (string) $a->kode_akun : '';
				$rek_b = isset($b->kode_akun) ? (string) $b->kode_akun : '';
				return strcmp($rek_a, $rek_b);
			});

			foreach ($group['debet'] as $row) {
				$result[] = $row;
			}
			foreach ($group['kredit'] as $row) {
				$result[] = $row;
			}
		}

		return $result;
	}

	private function _enrich_jurnal_penjualan2_baris_rows($rows, $month_selected, $year_selected)
	{
		$penjualan_kode_map = $this->_jurnal_penjualan2_penjualan_kode_by_nokirim_map($month_selected, $year_selected);
		$piutang_rek_by_pl = $this->_jurnal_penjualan2_piutang_rek_by_pl_map();
		$resolved_reks = array();

		foreach ($rows as $row) {
			$rek_display = $this->_jurnal_penjualan2_resolve_rek($row, $penjualan_kode_map, $piutang_rek_by_pl);
			$row->rek_display = $rek_display;
			if ($rek_display !== '') {
				$resolved_reks[$rek_display] = $rek_display;
			}
		}

		$nama_akun_map = $this->_jurnal_penjualan2_nama_akun_map_by_codes(array_values($resolved_reks));
		foreach ($rows as $row) {
			$row->keterangan_display = $this->_jurnal_penjualan2_keterangan_from_rek($row->rek_display, $nama_akun_map);
		}

		return $rows;
	}

	private function _jurnal_penjualan2_penjualan_kode_by_nokirim_map($month_selected, $year_selected)
	{
		$map = array();
		$sql = "SELECT `nmrkirim`, `kode_akun`
			FROM `tbl_penjualan`
			WHERE MONTH(`tgl_jual`)=" . (int) $month_selected . "
			AND YEAR(`tgl_jual`)=" . (int) $year_selected . "
			AND `kode_akun` IS NOT NULL
			AND TRIM(`kode_akun`) != ''
			ORDER BY `id` DESC";

		foreach ($this->db->query($sql)->result() as $row) {
			$nokirim = trim((string) $row->nmrkirim);
			$kode_akun = trim((string) $row->kode_akun);
			if ($nokirim !== '' && $kode_akun !== '' && !isset($map[$nokirim])) {
				$map[$nokirim] = $kode_akun;
			}
		}

		return $map;
	}

	private function _jurnal_penjualan2_piutang_rek_by_pl_map()
	{
		$map = array();
		$this->db->like('kode_akun', '11301-', 'after');
		foreach ($this->db->get('sys_kode_akun')->result() as $row) {
			$code = trim((string) $row->kode_akun);
			$nama = trim((string) $row->nama_akun);
			if ($code === '' || $nama === '') {
				continue;
			}

			if (stripos($nama, 'Percetakan') !== false) {
				$map['2'] = $code;
			} elseif (stripos($nama, 'Angsuran PU') !== false) {
				$map['4'] = $code;
			}
		}

		return $map;
	}

	private function _jurnal_penjualan2_resolve_rek($list_data, $penjualan_kode_map, $piutang_rek_by_pl)
	{
		$base_rek = trim((string) (isset($list_data->kode_akun) ? $list_data->kode_akun : ''));
		if ($base_rek === '') {
			return '';
		}

		if (strpos($base_rek, '-') !== false) {
			return $base_rek;
		}

		$nokirim = trim((string) (isset($list_data->nokirim) ? $list_data->nokirim : ''));
		$pl = trim((string) (isset($list_data->pl) ? $list_data->pl : ''));
		$debet_val = isset($list_data->debet) ? (float) $list_data->debet : 0;
		$kredit_val = isset($list_data->kredit) ? (float) $list_data->kredit : 0;

		if ($debet_val > 0 && $base_rek === '11301') {
			if ($pl !== '' && isset($piutang_rek_by_pl[$pl])) {
				return $piutang_rek_by_pl[$pl];
			}
		}

		if ($kredit_val > 0 && $base_rek !== '21201' && $nokirim !== '' && isset($penjualan_kode_map[$nokirim])) {
			return $penjualan_kode_map[$nokirim];
		}

		return $base_rek;
	}

	private function _jurnal_penjualan2_nama_akun_map_by_codes($rek_codes)
	{
		$codes = array();
		foreach ($rek_codes as $rek) {
			$rek = trim((string) $rek);
			if ($rek !== '') {
				$codes[$rek] = $rek;
			}
		}

		$map = array();
		if (empty($codes)) {
			return $map;
		}

		$this->db->where_in('kode_akun', array_values($codes));
		foreach ($this->db->get('sys_kode_akun')->result() as $row) {
			$key = trim((string) $row->kode_akun);
			$map[$key] = isset($row->nama_akun) ? trim((string) $row->nama_akun) : '';
		}

		return $map;
	}

	private function _jurnal_penjualan2_nama_akun_by_rek_map($buku_besar_data)
	{
		$rek_codes = array();
		foreach ($buku_besar_data as $list_data) {
			$rek = isset($list_data->rek_display) ? trim((string) $list_data->rek_display) : trim((string) (isset($list_data->kode_akun) ? $list_data->kode_akun : ''));
			if ($rek !== '') {
				$rek_codes[$rek] = $rek;
			}
		}

		return $this->_jurnal_penjualan2_nama_akun_map_by_codes(array_values($rek_codes));
	}

	private function _jurnal_penjualan2_keterangan_from_rek($rek, $nama_akun_map)
	{
		$rek = trim((string) $rek);
		return ($rek !== '' && isset($nama_akun_map[$rek])) ? $nama_akun_map[$rek] : '';
	}

	private function _jurnal_penjualan2_kredit_by_nokirim($nokirim, $tanggal, $kode_akun)
	{
		$Get_date = date("Y-m-d", strtotime($tanggal));
		$this->db->where('kode_akun', $kode_akun);
		$this->db->where('nokirim', $nokirim);
		$this->db->where('tanggal', $Get_date);
		$row = $this->db->get('buku_besar')->row();
		return $row ? (float) $row->kredit : 0;
	}

	private function _bulan_indonesia($angka_bulan)
	{
		$daftar = array(
			1 => 'Januari',
			2 => 'Februari',
			3 => 'Maret',
			4 => 'April',
			5 => 'Mei',
			6 => 'Juni',
			7 => 'Juli',
			8 => 'Agustus',
			9 => 'September',
			10 => 'Oktober',
			11 => 'November',
			12 => 'Desember',
		);
		return isset($daftar[(int) $angka_bulan]) ? $daftar[(int) $angka_bulan] : '';
	}

	public function excel_jurnal_penjualan2()
	{
		$this->load->helper('exportexcel');

		$Get_bulan_ns = trim((string) $this->input->get('bulan_ns', TRUE));
		if ($Get_bulan_ns === '') {
			$Get_bulan_ns = trim((string) $this->session->userdata('jurnal_penjualan2_bulan_ns'));
		}
		if ($Get_bulan_ns === '') {
			$Get_bulan_ns = date('Y-m');
		}

		$Get_month_selected = date("m", strtotime($Get_bulan_ns . "-01"));
		$Get_YEAR_selected = date("Y", strtotime($Get_bulan_ns . "-01"));
		$Buku_besar_DATA = $this->_get_jurnal_penjualan2_rows($Get_month_selected, $Get_YEAR_selected);
		$styles = $this->_excel_jurnal_penjualan2_style_map();

		$namaFile = "jurnal_penjualan_" . $Get_bulan_ns . ".xlsx";
		$tablehead_row1 = 2;
		$tablehead_row2 = 3;
		$tablehead_row3 = 4;
		$tablebody = 5;

		excel_prepare_download($namaFile);
		xlsBOF();
		xlsSetColumnWidths(array(5, 12, 12, 12, 10, 28, 14, 14, 14));

		$bulan_tahun_label = $this->_bulan_indonesia((int) $Get_month_selected) . ' ' . $Get_YEAR_selected;
		$this->_excel_jurnal_penjualan2_write_merged_title_row(0, 'JURNAL PENJUALAN', $styles['title']);
		$this->_excel_jurnal_penjualan2_write_merged_title_row(1, 'Jurnal Penjualan Bulan ' . $bulan_tahun_label, $styles['subtitle']);

		$this->_excel_jurnal_penjualan2_write_table_header($tablehead_row1, $tablehead_row2, $tablehead_row3, $styles['header']);

		$nomor = 0;
		$TOTAL_debet_11301 = 0;
		$TOTAL_kredit_41101 = 0;
		$TOTAL_kredit_21201 = 0;

		foreach ($Buku_besar_DATA as $list_data) {
			if ((string) $list_data->kode_akun !== '11301') {
				continue;
			}

			$nilai_kredit_41101 = $this->_jurnal_penjualan2_kredit_by_nokirim($list_data->nokirim, $list_data->tanggal, 41101);
			$nilai_kredit_21201 = $this->_jurnal_penjualan2_kredit_by_nokirim($list_data->nokirim, $list_data->tanggal, 21201);

			$this->_excel_jurnal_penjualan2_write_data_row(
				$tablebody,
				++$nomor,
				$list_data,
				$nilai_kredit_41101,
				$nilai_kredit_21201,
				$styles['left'],
				$styles['amount']
			);
			$tablebody++;

			$TOTAL_debet_11301 += (float) $list_data->debet;
			$TOTAL_kredit_41101 += (float) $nilai_kredit_41101;
			$TOTAL_kredit_21201 += (float) $nilai_kredit_21201;
		}

		$this->_excel_jurnal_penjualan2_write_grand_total_row(
			$tablebody,
			$TOTAL_debet_11301,
			$TOTAL_kredit_41101,
			$TOTAL_kredit_21201,
			$styles['total_label'],
			$styles['total_amount']
		);

		xlsEOF();
		exit();
	}

	public function excel_jurnal_penjualan2_baris()
	{
		$this->load->helper('exportexcel');

		$Get_bulan_ns = trim((string) $this->input->get('bulan_ns', TRUE));
		if ($Get_bulan_ns === '') {
			$Get_bulan_ns = trim((string) $this->session->userdata('jurnal_penjualan2_bulan_ns'));
		}
		if ($Get_bulan_ns === '') {
			$Get_bulan_ns = date('Y-m');
		}

		$Get_month_selected = date("m", strtotime($Get_bulan_ns . "-01"));
		$Get_YEAR_selected = date("Y", strtotime($Get_bulan_ns . "-01"));
		$Buku_besar_DATA = $this->_prepare_jurnal_penjualan2_baris_rows(
			$this->_get_jurnal_penjualan2_rows($Get_month_selected, $Get_YEAR_selected)
		);
		$Buku_besar_DATA = $this->_enrich_jurnal_penjualan2_baris_rows($Buku_besar_DATA, $Get_month_selected, $Get_YEAR_selected);
		$styles = $this->_excel_jurnal_penjualan2_style_map();

		$namaFile = "jurnal_penjualan_baris_" . $Get_bulan_ns . ".xlsx";
		$tablehead_row1 = 2;
		$tablehead_row2 = 3;
		$tablebody = 4;

		excel_prepare_download($namaFile);
		xlsBOF();
		xlsSetColumnWidths(array(5, 12, 10, 6, 8, 8, 35, 14, 14));

		$bulan_tahun_label = $this->_bulan_indonesia((int) $Get_month_selected) . ' ' . $Get_YEAR_selected;
		$this->_excel_jurnal_penjualan2_write_merged_title_row(0, 'JURNAL PENJUALAN', $styles['title']);
		$this->_excel_jurnal_penjualan2_write_merged_title_row(1, 'Jurnal Penjualan (Baris) Bulan ' . $bulan_tahun_label, $styles['subtitle']);

		$this->_excel_jurnal_penjualan2_baris_write_table_header($tablehead_row1, $tablehead_row2, $styles['header']);

		$nomor = 0;
		$TOTAL_debet = 0;
		$TOTAL_kredit = 0;

		foreach ($Buku_besar_DATA as $list_data) {
			$debet_val = isset($list_data->debet) ? (float) $list_data->debet : 0;
			$kredit_val = isset($list_data->kredit) ? (float) $list_data->kredit : 0;
			if ($debet_val == 0 && $kredit_val == 0) {
				continue;
			}

			$this->_excel_jurnal_penjualan2_baris_write_data_row(
				$tablebody,
				++$nomor,
				$list_data,
				$styles['left'],
				$styles['amount']
			);
			$tablebody++;

			$TOTAL_debet += $debet_val;
			$TOTAL_kredit += $kredit_val;
		}

		$this->_excel_jurnal_penjualan2_baris_write_grand_total_row(
			$tablebody,
			$TOTAL_debet,
			$TOTAL_kredit,
			$styles['total_label'],
			$styles['total_amount']
		);

		xlsEOF();
		exit();
	}

	private function _excel_jurnal_penjualan2_style_map()
	{
		return array(
			'title' => 2,
			'subtitle' => 13,
			'header' => 14,
			'left' => 7,
			'amount' => 8,
			'total_label' => 9,
			'total_amount' => 10,
		);
	}

	private function _excel_jurnal_penjualan2_write_merged_title_row($row, $text, $styleIndex, $colStart = 0, $colEnd = 8)
	{
		xlsWriteCellStyle($row, $colStart, $text, $styleIndex);
		xlsAddMerge($row, $colStart, $row, $colEnd);
		for ($col = $colStart + 1; $col <= $colEnd; $col++) {
			xlsWriteCellStyle($row, $col, '', $styleIndex);
		}
	}

	private function _excel_jurnal_penjualan2_format_amount($value)
	{
		return number_format((float) $value, 2, ',', '.');
	}

	private function _excel_jurnal_penjualan2_write_table_header($rowStart, $rowMid, $rowEnd, $style)
	{
		$single_headers = array('No', 'TANGGAL', 'No. INVOICE', 'No. PESAN', 'No. KIRIM', 'KONSUMEN');
		foreach ($single_headers as $col => $label) {
			xlsWriteCellStyle($rowStart, $col, $label, $style);
			xlsAddMerge($rowStart, $col, $rowEnd, $col);
			xlsWriteCellStyle($rowMid, $col, '', $style);
			xlsWriteCellStyle($rowEnd, $col, '', $style);
		}

		xlsWriteCellStyle($rowStart, 6, 'DEBET', $style);
		xlsAddMerge($rowStart, 6, $rowStart, 6);
		xlsWriteCellStyle($rowStart, 7, 'KREDIT', $style);
		xlsAddMerge($rowStart, 7, $rowStart, 8);
		xlsWriteCellStyle($rowStart, 8, '', $style);

		xlsWriteCellStyle($rowMid, 6, '11301', $style);
		xlsWriteCellStyle($rowMid, 7, '41101', $style);
		xlsWriteCellStyle($rowMid, 8, '21201', $style);

		xlsWriteCellStyle($rowEnd, 6, 'Piutang', $style);
		xlsWriteCellStyle($rowEnd, 7, 'Penjualan DPP', $style);
		xlsWriteCellStyle($rowEnd, 8, 'Utang PPN', $style);
	}

	private function _excel_jurnal_penjualan2_write_data_row($row, $nomor, $list_data, $nilai_kredit_41101, $nilai_kredit_21201, $style_left, $style_amount)
	{
		$Get_date = date("Y-m-d", strtotime($list_data->tanggal));
		xlsWriteCellStyle($row, 0, (string) $nomor, $style_left);
		xlsWriteCellStyle($row, 1, $Get_date, $style_left);
		xlsWriteCellStyle($row, 2, '', $style_left);
		xlsWriteCellStyle($row, 3, '', $style_left);
		xlsWriteCellStyle($row, 4, (string) $list_data->nokirim, $style_left);
		xlsWriteCellStyle($row, 5, (string) $list_data->konsumen_nama, $style_left);
		xlsWriteCellStyle($row, 6, $this->_excel_jurnal_penjualan2_format_amount($list_data->debet), $style_amount);
		xlsWriteCellStyle($row, 7, $this->_excel_jurnal_penjualan2_format_amount($nilai_kredit_41101), $style_amount);
		xlsWriteCellStyle($row, 8, $this->_excel_jurnal_penjualan2_format_amount($nilai_kredit_21201), $style_amount);
	}

	private function _excel_jurnal_penjualan2_write_grand_total_row($row, $total_debet, $total_kredit_41101, $total_kredit_21201, $style_label, $style_amount)
	{
		xlsAddMerge($row, 0, $row, 5);
		xlsWriteCellStyle($row, 0, 'TOTAL', $style_label);
		for ($col = 1; $col <= 5; $col++) {
			xlsEnsureCellStyle($row, $col, $style_label, '');
		}
		xlsWriteCellStyle($row, 6, $this->_excel_jurnal_penjualan2_format_amount($total_debet), $style_amount);
		xlsWriteCellStyle($row, 7, $this->_excel_jurnal_penjualan2_format_amount($total_kredit_41101), $style_amount);
		xlsWriteCellStyle($row, 8, $this->_excel_jurnal_penjualan2_format_amount($total_kredit_21201), $style_amount);
	}

	private function _excel_jurnal_penjualan2_baris_write_table_header($rowStart, $rowSub, $style)
	{
		$single_headers = array(
			0 => 'No',
			1 => 'TANGGAL',
			2 => 'Bukti',
			6 => 'Keterangan',
			7 => 'DEBET',
			8 => 'KREDIT',
		);

		foreach ($single_headers as $col => $label) {
			xlsWriteCellStyle($rowStart, $col, $label, $style);
			xlsAddMerge($rowStart, $col, $rowSub, $col);
			xlsWriteCellStyle($rowSub, $col, '', $style);
		}

		xlsWriteCellStyle($rowStart, 3, 'KODE', $style);
		xlsAddMerge($rowStart, 3, $rowStart, 5);
		xlsWriteCellStyle($rowStart, 4, '', $style);
		xlsWriteCellStyle($rowStart, 5, '', $style);

		xlsWriteCellStyle($rowSub, 3, 'PL', $style);
		xlsWriteCellStyle($rowSub, 4, 'Ref', $style);
		xlsWriteCellStyle($rowSub, 5, 'Rek', $style);
	}

	private function _excel_jurnal_penjualan2_baris_write_data_row($row, $nomor, $list_data, $style_left, $style_amount)
	{
		$Get_date = isset($list_data->tanggal) ? date("Y-m-d", strtotime($list_data->tanggal)) : '';

		$bukti = isset($list_data->nokirim) ? (string) $list_data->nokirim : '';
		$pl = isset($list_data->pl) ? (string) $list_data->pl : '';
		$ref = isset($list_data->ref) ? (string) $list_data->ref : ((isset($list_data->kode) ? (string) $list_data->kode : ''));
		$rek = isset($list_data->rek_display) ? trim((string) $list_data->rek_display) : trim((string) (isset($list_data->kode_akun) ? $list_data->kode_akun : ''));
		$keterangan = isset($list_data->keterangan_display) ? trim((string) $list_data->keterangan_display) : '';

		$debet_val = isset($list_data->debet) ? (float) $list_data->debet : 0;
		$kredit_val = isset($list_data->kredit) ? (float) $list_data->kredit : 0;

		xlsWriteCellStyle($row, 0, (string) $nomor, $style_left);
		xlsWriteCellStyle($row, 1, $Get_date, $style_left);
		xlsWriteCellStyle($row, 2, $bukti, $style_left);
		xlsWriteCellStyle($row, 3, $pl, $style_left);
		xlsWriteCellStyle($row, 4, $ref, $style_left);
		xlsWriteCellStyle($row, 5, $rek, $style_left);
		xlsWriteCellStyle($row, 6, $keterangan, $style_left);

		xlsWriteCellStyle(
			$row,
			7,
			$debet_val != 0 ? $this->_excel_jurnal_penjualan2_format_amount($debet_val) : '',
			$style_amount
		);
		xlsWriteCellStyle(
			$row,
			8,
			$kredit_val != 0 ? $this->_excel_jurnal_penjualan2_format_amount($kredit_val) : '',
			$style_amount
		);
	}

	private function _excel_jurnal_penjualan2_baris_write_grand_total_row($row, $total_debet, $total_kredit, $style_label, $style_amount)
	{
		xlsAddMerge($row, 0, $row, 6);
		xlsWriteCellStyle($row, 0, 'TOTAL', $style_label);
		for ($col = 1; $col <= 6; $col++) {
			xlsEnsureCellStyle($row, $col, $style_label, '');
		}
		xlsWriteCellStyle($row, 7, $this->_excel_jurnal_penjualan2_format_amount($total_debet), $style_amount);
		xlsWriteCellStyle($row, 8, $this->_excel_jurnal_penjualan2_format_amount($total_kredit), $style_amount);
	}

	private function _get_sys_unit_list_ordered()
	{
		return $this->db->order_by('kode_unit', 'ASC')->get('sys_unit')->result();
	}

	private function _jurnal_penjualan2_per_unit_group_key($row)
	{
		$tgl = isset($row->tgl_jual) ? date('Y-m-d', strtotime($row->tgl_jual)) : '';
		return $tgl . '|' . (string) $row->nmrpesan . '|' . (string) $row->nmrkirim . '|' . (string) $row->uuid_unit;
	}

	private function _jurnal_penjualan2_pembayaran_map($month_selected, $year_selected)
	{
		if (!$this->db->table_exists('tbl_penjualan_pembayaran')) {
			return array();
		}

		$month_selected = (int) $month_selected;
		$year_selected = (int) $year_selected;
		$sql = "SELECT
			DATE(`tgl_jual`) AS `tgl_jual`,
			`nmrpesan`,
			`nmrkirim`,
			`uuid_unit`,
			SUM(COALESCE(`nominal_bayar`, 0)) AS `jumlah_bayar`,
			MAX(`tgl_bayar`) AS `tgl_bayar`
		FROM `tbl_penjualan_pembayaran`
		WHERE MONTH(`tgl_jual`) = {$month_selected}
			AND YEAR(`tgl_jual`) = {$year_selected}
		GROUP BY DATE(`tgl_jual`), `nmrpesan`, `nmrkirim`, `uuid_unit`";

		$map = array();
		foreach ($this->db->query($sql)->result() as $row) {
			$key = $this->_jurnal_penjualan2_per_unit_group_key($row);
			$map[$key] = array(
				'jumlah_bayar' => (float) $row->jumlah_bayar,
				'tgl_bayar' => $row->tgl_bayar,
			);
		}

		return $map;
	}

	private function _enrich_jurnal_penjualan2_per_unit_rows($rows, $month_selected, $year_selected)
	{
		$payment_map = $this->_jurnal_penjualan2_pembayaran_map($month_selected, $year_selected);

		foreach ($rows as $row) {
			$key = $this->_jurnal_penjualan2_per_unit_group_key($row);
			$piutang = (float) $row->piutang;
			$jumlah_bayar = 0;
			$tgl_bayar_display = '';

			if (isset($payment_map[$key])) {
				$jumlah_bayar = (float) $payment_map[$key]['jumlah_bayar'];
				if (!empty($payment_map[$key]['tgl_bayar']) && $payment_map[$key]['tgl_bayar'] !== '0000-00-00 00:00:00') {
					$tgl_bayar_display = date('Y-m-d', strtotime($payment_map[$key]['tgl_bayar']));
				}
			} elseif (!empty($row->tgl_bayar) && $row->tgl_bayar !== '0000-00-00 00:00:00') {
				$tgl_bayar_display = date('Y-m-d', strtotime($row->tgl_bayar));
				if (strtolower((string) $row->proses_bayar) === 'bayar') {
					$jumlah_bayar = $piutang;
				}
			}

			$row->tgl_jual_display = date('Y-m-d', strtotime($row->tgl_jual));
			$row->no_invoice = '';
			$row->tgl_bayar_display = $tgl_bayar_display;
			$row->jumlah_bayar = $jumlah_bayar;
			$row->selisih = $piutang - $jumlah_bayar;
		}

		return $rows;
	}

	private function _get_jurnal_penjualan2_per_unit_rows($uuid_unit, $month_selected, $year_selected)
	{
		$month_selected = (int) $month_selected;
		$year_selected = (int) $year_selected;
		$uuid_unit_esc = $this->db->escape($uuid_unit);

		$sql = "SELECT
			DATE(`tgl_jual`) AS `tgl_jual`,
			`nmrpesan`,
			`nmrkirim`,
			`uuid_unit`,
			MAX(`konsumen_nama`) AS `konsumen_nama`,
			MAX(`tgl_bayar`) AS `tgl_bayar`,
			MAX(`proses_bayar`) AS `proses_bayar`,
			SUM(COALESCE(`harga_satuan`, 0) * COALESCE(`jumlah`, 0)) AS `piutang`,
			SUM((COALESCE(`harga_satuan`, 0) * COALESCE(`jumlah`, 0)) / 1.11) AS `penjualan`,
			SUM(((COALESCE(`harga_satuan`, 0) * COALESCE(`jumlah`, 0)) / 1.11) * 0.11) AS `utang_ppn`
		FROM `tbl_penjualan`
		WHERE MONTH(`tgl_jual`) = {$month_selected}
			AND YEAR(`tgl_jual`) = {$year_selected}
			AND `uuid_unit` = {$uuid_unit_esc}
		GROUP BY DATE(`tgl_jual`), `nmrpesan`, `nmrkirim`, `uuid_unit`
		ORDER BY `tgl_jual`, `nmrpesan`, `nmrkirim`";

		$rows = $this->db->query($sql)->result();
		return $this->_enrich_jurnal_penjualan2_per_unit_rows($rows, $month_selected, $year_selected);
	}

	private function _get_jurnal_penjualan2_per_unit_data_by_units($month_selected, $year_selected)
	{
		$result = array();
		foreach ($this->_get_sys_unit_list_ordered() as $unit) {
			$uuid_unit = isset($unit->uuid_unit) ? (string) $unit->uuid_unit : '';
			$result[] = array(
				'unit' => $unit,
				'rows' => $this->_get_jurnal_penjualan2_per_unit_rows($uuid_unit, $month_selected, $year_selected),
				'format_type' => $this->_jurnal_penjualan2_per_unit_format_type($unit),
			);
		}

		return $result;
	}

	private function _resolve_jurnal_penjualan2_bulan_ns_from_request()
	{
		$Get_bulan_ns = trim((string) $this->input->get('bulan_ns', TRUE));
		if ($Get_bulan_ns === '') {
			$Get_bulan_ns = trim((string) $this->session->userdata('jurnal_penjualan2_bulan_ns'));
		}
		if ($Get_bulan_ns === '') {
			$Get_bulan_ns = date('Y-m');
		}

		return $Get_bulan_ns;
	}

	private function _jurnal_penjualan2_per_unit_format_type($unit_row)
	{
		if (!$unit_row) {
			return 'standard';
		}

		$kode = strtoupper(trim((string) (isset($unit_row->kode_unit) ? $unit_row->kode_unit : '')));
		$nama = strtoupper(trim((string) (isset($unit_row->nama_unit) ? $unit_row->nama_unit : '')));
		$gabung = $kode . ' ' . $nama;
		$gabung_norm = preg_replace('/[\s_\-]+/', '', $gabung);

		if ($kode === 'CETAK' || $nama === 'CETAK') {
			return 'cetak';
		}

		if ($kode === 'PU-ATK' || $nama === 'PU-ATK' || preg_replace('/[\s_\-]+/', '', $kode) === 'PUATK') {
			return 'pu_atk';
		}

		if (
			strpos($gabung_norm, 'PUFCGOSE') !== false
			|| strpos($gabung_norm, 'FCGOSE') !== false
			|| (strpos($gabung, 'FC') !== false && strpos($gabung, 'GOSE') !== false)
		) {
			return 'pu_fc_gose';
		}

		return 'standard';
	}

	private function _is_jurnal_penjualan2_per_unit_special_format($format_type)
	{
		return in_array($format_type, array('cetak', 'pu_atk', 'pu_fc_gose'), true);
	}

	private function _excel_jurnal_penjualan2_per_unit_header_style_by_theme($theme_index)
	{
		$styles = array(6, 14, 4, 6, 14, 4, 6, 14);

		return $styles[(int) $theme_index % count($styles)];
	}

	private function _excel_jurnal_penjualan2_per_unit_theme_index_by_uuid($uuid_unit)
	{
		foreach ($this->_get_sys_unit_list_ordered() as $index => $unit_row) {
			if (isset($unit_row->uuid_unit) && (string) $unit_row->uuid_unit === (string) $uuid_unit) {
				return (int) $index;
			}
		}

		return 0;
	}

	private function _excel_jurnal_penjualan2_per_unit_konsumen_label($list_data)
	{
		return trim((string) (isset($list_data->konsumen_nama) ? $list_data->konsumen_nama : ''));
	}

	public function excel_jurnal_penjualan2_per_unit()
	{
		$this->load->helper('exportexcel');

		$Get_bulan_ns = $this->_resolve_jurnal_penjualan2_bulan_ns_from_request();
		$uuid_unit = trim((string) $this->input->get('uuid_unit', TRUE));
		if ($uuid_unit === '') {
			show_error('Unit tidak valid untuk ekspor jurnal penjualan per unit.', 400);
			return;
		}

		$unit_row = $this->db->where('uuid_unit', $uuid_unit)->limit(1)->get('sys_unit')->row();
		if (!$unit_row) {
			show_error('Data unit tidak ditemukan.', 404);
			return;
		}

		$Get_month_selected = (int) date('m', strtotime($Get_bulan_ns . '-01'));
		$Get_YEAR_selected = (int) date('Y', strtotime($Get_bulan_ns . '-01'));
		$rows = $this->_get_jurnal_penjualan2_per_unit_rows($uuid_unit, $Get_month_selected, $Get_YEAR_selected);
		$format_type = $this->_jurnal_penjualan2_per_unit_format_type($unit_row);
		$theme_index = $this->_excel_jurnal_penjualan2_per_unit_theme_index_by_uuid($uuid_unit);
		$header_style = $this->_excel_jurnal_penjualan2_per_unit_header_style_by_theme($theme_index);
		$styles = $this->_excel_jurnal_penjualan2_style_map();

		$unit_label = trim((string) (isset($unit_row->nama_unit) ? $unit_row->nama_unit : ''));
		if ($unit_label === '') {
			$unit_label = trim((string) (isset($unit_row->kode_unit) ? $unit_row->kode_unit : 'unit'));
		}
		$unit_slug = preg_replace('/[^A-Za-z0-9_-]+/', '_', $unit_label);
		$namaFile = 'jurnal_penjualan_per_unit_' . $unit_slug . '_' . $Get_bulan_ns . '.xlsx';
		$col_end = 11;

		excel_prepare_download($namaFile);
		xlsBOF();
		if ($format_type === 'pu_fc_gose') {
			xlsSetColumnWidths(array(5, 12, 10, 10, 16, 11, 11, 12, 12, 12, 14, 14));
		} else {
			xlsSetColumnWidths(array(5, 12, 12, 12, 10, 16, 14, 14, 14, 12, 14, 14));
		}

		$bulan_tahun_label = $this->_bulan_indonesia($Get_month_selected) . ' ' . $Get_YEAR_selected;
		$this->_excel_jurnal_penjualan2_per_unit_write_merged_title_row(0, 'JURNAL PENJUALAN PER UNIT', $styles['title'], 0, $col_end);
		$this->_excel_jurnal_penjualan2_per_unit_write_merged_title_row(1, 'Unit: ' . $unit_label . ' | Bulan ' . $bulan_tahun_label, $header_style, 0, $col_end);

		if ($format_type === 'cetak') {
			$tablebody = 6;
			$this->_excel_jurnal_penjualan2_per_unit_write_table_header_cetak(3, 4, 5, $header_style);
		} elseif ($format_type === 'pu_atk') {
			$tablebody = 6;
			$this->_excel_jurnal_penjualan2_per_unit_write_table_header_pu_atk(3, 4, 5, $header_style);
		} elseif ($format_type === 'pu_fc_gose') {
			$tablebody = 7;
			$this->_excel_jurnal_penjualan2_per_unit_write_table_header_pu_fc_gose(3, 4, 5, 6, $header_style);
		} else {
			$tablebody = 4;
			$this->_excel_jurnal_penjualan2_per_unit_write_table_header(3, $header_style);
		}

		$nomor = 0;
		$total_piutang = 0;
		$total_penjualan = 0;
		$total_utang_ppn = 0;
		$total_jumlah = 0;
		$total_selisih = 0;

		foreach ($rows as $list_data) {
			$this->_excel_jurnal_penjualan2_per_unit_write_data_row_by_format(
				$tablebody,
				++$nomor,
				$list_data,
				$format_type,
				$styles['left'],
				$styles['amount']
			);
			$tablebody++;

			$total_piutang += (float) $list_data->piutang;
			$total_penjualan += (float) $list_data->penjualan;
			$total_utang_ppn += (float) $list_data->utang_ppn;
			$total_jumlah += (float) $list_data->jumlah_bayar;
			$total_selisih += (float) $list_data->selisih;
		}

		$this->_excel_jurnal_penjualan2_per_unit_write_grand_total_row_by_format(
			$tablebody,
			$format_type,
			$total_piutang,
			$total_penjualan,
			$total_utang_ppn,
			$total_jumlah,
			$total_selisih,
			$styles['total_label'],
			$styles['total_amount']
		);

		xlsEOF();
		exit();
	}

	private function _excel_jurnal_penjualan2_per_unit_write_merged_title_row($row, $text, $styleIndex, $colStart = 0, $colEnd = 11)
	{
		xlsWriteCellStyle($row, $colStart, $text, $styleIndex);
		xlsAddMerge($row, $colStart, $row, $colEnd);
		for ($col = $colStart + 1; $col <= $colEnd; $col++) {
			xlsWriteCellStyle($row, $col, '', $styleIndex);
		}
	}

	private function _excel_jurnal_penjualan2_per_unit_write_table_header($row, $style)
	{
		$headers = array(
			'No',
			'Tanggal',
			'NO INVOICE',
			'Nomor Pesan',
			'Nomor Kirim',
			'KONSUMEN',
			'Piutang',
			'Penjualan',
			'Utang PPN',
			'Tanggal Bayar',
			'Jumlah',
			'Selisih',
		);

		foreach ($headers as $col => $label) {
			xlsWriteCellStyle($row, $col, $label, $style);
		}
	}

	private function _excel_jurnal_penjualan2_per_unit_write_table_header_cetak($rowStart, $rowMid, $rowEnd, $style)
	{
		$this->_excel_jurnal_penjualan2_per_unit_write_table_header_debet_kredit(
			$rowStart,
			$rowMid,
			$rowEnd,
			$style,
			'41101',
			'Penjualan DPP'
		);
	}

	private function _excel_jurnal_penjualan2_per_unit_write_table_header_pu_atk($rowStart, $rowMid, $rowEnd, $style)
	{
		$this->_excel_jurnal_penjualan2_per_unit_write_table_header_debet_kredit(
			$rowStart,
			$rowMid,
			$rowEnd,
			$style,
			'41116',
			'Penjualan ATK'
		);
	}

	private function _excel_jurnal_penjualan2_per_unit_write_table_header_debet_kredit($rowStart, $rowMid, $rowEnd, $style, $kredit_kode_penjualan, $label_penjualan)
	{
		$single_headers = array(
			0 => 'No',
			1 => 'Tanggal',
			2 => 'NO INVOICE',
			3 => 'Nomor Pesan',
			4 => 'Nomor Kirim',
			5 => 'KONSUMEN',
			9 => 'Tanggal Bayar',
			10 => 'Jumlah',
			11 => 'Selisih',
		);

		foreach ($single_headers as $col => $label) {
			xlsWriteCellStyle($rowStart, $col, $label, $style);
			xlsAddMerge($rowStart, $col, $rowEnd, $col);
			xlsWriteCellStyle($rowMid, $col, '', $style);
			xlsWriteCellStyle($rowEnd, $col, '', $style);
		}

		xlsWriteCellStyle($rowStart, 6, 'DEBET', $style);
		xlsWriteCellStyle($rowStart, 7, 'KREDIT', $style);
		xlsAddMerge($rowStart, 7, $rowStart, 8);
		xlsWriteCellStyle($rowStart, 8, '', $style);

		xlsWriteCellStyle($rowMid, 6, '11301', $style);
		xlsWriteCellStyle($rowMid, 7, $kredit_kode_penjualan, $style);
		xlsWriteCellStyle($rowMid, 8, '21201', $style);

		xlsWriteCellStyle($rowEnd, 6, 'Piutang', $style);
		xlsWriteCellStyle($rowEnd, 7, $label_penjualan, $style);
		xlsWriteCellStyle($rowEnd, 8, 'Utang PPN', $style);
	}

	private function _excel_jurnal_penjualan2_per_unit_write_table_header_pu_fc_gose($rowStart, $rowArgo, $rowKode, $rowLabel, $style)
	{
		$single_headers = array(
			0 => 'No',
			1 => 'Tanggal',
			4 => 'KONSUMEN',
			9 => 'Tanggal Bayar',
			10 => 'Jumlah',
			11 => 'Selisih',
		);

		foreach ($single_headers as $col => $label) {
			xlsWriteCellStyle($rowStart, $col, $label, $style);
			xlsAddMerge($rowStart, $col, $rowLabel, $col);
			xlsWriteCellStyle($rowArgo, $col, '', $style);
			xlsWriteCellStyle($rowKode, $col, '', $style);
			xlsWriteCellStyle($rowLabel, $col, '', $style);
		}

		xlsWriteCellStyle($rowStart, 2, 'ARGO', $style);
		xlsAddMerge($rowStart, 2, $rowStart, 3);
		xlsWriteCellStyle($rowStart, 3, '', $style);

		xlsWriteCellStyle($rowArgo, 2, 'AWAL', $style);
		xlsAddMerge($rowArgo, 2, $rowKode, 2);
		xlsWriteCellStyle($rowArgo, 3, 'Akhir', $style);
		xlsAddMerge($rowArgo, 3, $rowKode, 3);

		xlsWriteCellStyle($rowStart, 5, 'DEBET', $style);
		xlsAddMerge($rowStart, 5, $rowStart, 6);
		xlsWriteCellStyle($rowStart, 6, '', $style);

		xlsWriteCellStyle($rowStart, 7, 'KREDIT', $style);
		xlsAddMerge($rowStart, 7, $rowStart, 8);
		xlsWriteCellStyle($rowStart, 8, '', $style);

		xlsWriteCellStyle($rowKode, 5, '52711', $style);
		xlsWriteCellStyle($rowKode, 6, '52704', $style);
		xlsWriteCellStyle($rowKode, 7, '41102', $style);
		xlsWriteCellStyle($rowKode, 8, '21201', $style);

		xlsWriteCellStyle($rowLabel, 5, 'BOU- Lain lain', $style);
		xlsWriteCellStyle($rowLabel, 6, 'BOU- Foto Copy & Cetak', $style);
		xlsWriteCellStyle($rowLabel, 7, 'Penjualan FC Gose', $style);
		xlsWriteCellStyle($rowLabel, 8, 'Utang PPN', $style);
	}

	private function _excel_jurnal_penjualan2_per_unit_write_data_row_by_format($row, $nomor, $list_data, $format_type, $style_left, $style_amount)
	{
		if ($format_type === 'pu_fc_gose') {
			xlsWriteCellStyle($row, 0, (string) $nomor, $style_left);
			xlsWriteCellStyle($row, 1, (string) $list_data->tgl_jual_display, $style_left);
			xlsWriteCellStyle($row, 2, (string) $list_data->no_invoice, $style_left);
			xlsWriteCellStyle($row, 3, (string) $list_data->nmrpesan, $style_left);
			xlsWriteCellStyle($row, 4, $this->_excel_jurnal_penjualan2_per_unit_konsumen_label($list_data), $style_left);
			xlsWriteCellStyle($row, 5, '', $style_left);
			xlsWriteCellStyle($row, 6, '', $style_left);
			xlsWriteCellStyle($row, 7, $this->_excel_jurnal_penjualan2_format_amount($list_data->penjualan), $style_amount);
			xlsWriteCellStyle($row, 8, $this->_excel_jurnal_penjualan2_format_amount($list_data->utang_ppn), $style_amount);
			xlsWriteCellStyle($row, 9, (string) $list_data->tgl_bayar_display, $style_left);
			xlsWriteCellStyle(
				$row,
				10,
				$list_data->jumlah_bayar != 0 ? $this->_excel_jurnal_penjualan2_format_amount($list_data->jumlah_bayar) : '',
				$style_amount
			);
			xlsWriteCellStyle(
				$row,
				11,
				$list_data->selisih != 0 ? $this->_excel_jurnal_penjualan2_format_amount($list_data->selisih) : '',
				$style_amount
			);
			return;
		}

		$this->_excel_jurnal_penjualan2_per_unit_write_data_row($row, $nomor, $list_data, $style_left, $style_amount);
	}

	private function _excel_jurnal_penjualan2_per_unit_write_grand_total_row_by_format($row, $format_type, $total_piutang, $total_penjualan, $total_utang_ppn, $total_jumlah, $total_selisih, $style_label, $style_amount)
	{
		if ($format_type === 'pu_fc_gose') {
			xlsAddMerge($row, 0, $row, 4);
			xlsWriteCellStyle($row, 0, 'TOTAL', $style_label);
			for ($col = 1; $col <= 4; $col++) {
				xlsEnsureCellStyle($row, $col, $style_label, '');
			}
			xlsWriteCellStyle($row, 5, '', $style_label);
			xlsWriteCellStyle($row, 6, '', $style_label);
			xlsWriteCellStyle($row, 7, $this->_excel_jurnal_penjualan2_format_amount($total_penjualan), $style_amount);
			xlsWriteCellStyle($row, 8, $this->_excel_jurnal_penjualan2_format_amount($total_utang_ppn), $style_amount);
			xlsWriteCellStyle($row, 9, '', $style_label);
			xlsWriteCellStyle($row, 10, $this->_excel_jurnal_penjualan2_format_amount($total_jumlah), $style_amount);
			xlsWriteCellStyle($row, 11, $this->_excel_jurnal_penjualan2_format_amount($total_selisih), $style_amount);
			return;
		}

		$this->_excel_jurnal_penjualan2_per_unit_write_grand_total_row(
			$row,
			$total_piutang,
			$total_penjualan,
			$total_utang_ppn,
			$total_jumlah,
			$total_selisih,
			$style_label,
			$style_amount
		);
	}

	private function _excel_jurnal_penjualan2_per_unit_write_data_row($row, $nomor, $list_data, $style_border, $style_amount)
	{
		xlsWriteCellStyle($row, 0, (string) $nomor, $style_border);
		xlsWriteCellStyle($row, 1, (string) $list_data->tgl_jual_display, $style_border);
		xlsWriteCellStyle($row, 2, (string) $list_data->no_invoice, $style_border);
		xlsWriteCellStyle($row, 3, (string) $list_data->nmrpesan, $style_border);
		xlsWriteCellStyle($row, 4, (string) $list_data->nmrkirim, $style_border);
		xlsWriteCellStyle($row, 5, $this->_excel_jurnal_penjualan2_per_unit_konsumen_label($list_data), $style_border);
		xlsWriteCellStyle($row, 6, $this->_excel_jurnal_penjualan2_format_amount($list_data->piutang), $style_amount);
		xlsWriteCellStyle($row, 7, $this->_excel_jurnal_penjualan2_format_amount($list_data->penjualan), $style_amount);
		xlsWriteCellStyle($row, 8, $this->_excel_jurnal_penjualan2_format_amount($list_data->utang_ppn), $style_amount);
		xlsWriteCellStyle($row, 9, (string) $list_data->tgl_bayar_display, $style_border);
		xlsWriteCellStyle(
			$row,
			10,
			$list_data->jumlah_bayar != 0 ? $this->_excel_jurnal_penjualan2_format_amount($list_data->jumlah_bayar) : '',
			$style_amount
		);
		xlsWriteCellStyle(
			$row,
			11,
			$list_data->selisih != 0 ? $this->_excel_jurnal_penjualan2_format_amount($list_data->selisih) : '',
			$style_amount
		);
	}

	private function _excel_jurnal_penjualan2_per_unit_write_grand_total_row($row, $total_piutang, $total_penjualan, $total_utang_ppn, $total_jumlah, $total_selisih, $style_label, $style_amount)
	{
		xlsAddMerge($row, 0, $row, 5);
		xlsWriteCellStyle($row, 0, 'TOTAL', $style_label);
		for ($col = 1; $col <= 5; $col++) {
			xlsEnsureCellStyle($row, $col, $style_label, '');
		}
		xlsWriteCellStyle($row, 6, $this->_excel_jurnal_penjualan2_format_amount($total_piutang), $style_amount);
		xlsWriteCellStyle($row, 7, $this->_excel_jurnal_penjualan2_format_amount($total_penjualan), $style_amount);
		xlsWriteCellStyle($row, 8, $this->_excel_jurnal_penjualan2_format_amount($total_utang_ppn), $style_amount);
		xlsWriteCellStyle($row, 9, '', $style_label);
		xlsWriteCellStyle($row, 10, $this->_excel_jurnal_penjualan2_format_amount($total_jumlah), $style_amount);
		xlsWriteCellStyle($row, 11, $this->_excel_jurnal_penjualan2_format_amount($total_selisih), $style_amount);
	}

	public function input_kode_akun($nmrkirim = null, $Tgl_JUAL = null)
	{

		// print_r($nmrkirim);
		// print_r("<br/>");
		// print_r($Tgl_JUAL);
		// print_r("<br/>");

		$Get_date = date("Y-m-d", strtotime($Tgl_JUAL));
		$nmrpesan = trim((string) $this->input->get('nmrpesan', TRUE));

		if ($nmrpesan !== '') {
			$data_per_nmrkirim = $this->Tbl_penjualan_model->get_all_by_nmr_kirim_nmrpesan($nmrkirim, $nmrpesan);
		} else {
			$data_per_nmrkirim = $this->Tbl_penjualan_model->get_all_by_nmr_kirim($nmrkirim);
		}

		$search_filter = trim((string) $this->input->get('search_filter', TRUE));
		$spop_filter = trim((string) $this->input->get('spop_filter', TRUE));
		$action_url = site_url('Tbl_penjualan/update_kode_akun/' . $nmrkirim . '/' . $Get_date);
		$query = array();
		if ($nmrpesan !== '') {
			$query[] = 'nmrpesan=' . rawurlencode($nmrpesan);
		}
		if ($search_filter !== '') {
			$query[] = 'search_filter=' . rawurlencode($search_filter);
		}
		if ($spop_filter !== '') {
			$query[] = 'spop_filter=' . rawurlencode($spop_filter);
		}
		if (!empty($query)) {
			$action_url .= '?' . implode('&', $query);
		}

		$data = array(
			'Tbl_penjualan_data' => $data_per_nmrkirim,
			'nmrkirim' => $nmrkirim,
			'nmrpesan' => $nmrpesan,
			'action' => $action_url,
			'button' => 'Simpan Kode AKun',
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
		// 2.Â 11301Â (Â debetÂ )


		// Pak maaf ralat.. yg jurnal penjualan itu : 

		// - Total penjualan masuk kode akun 11301 (otomatis)
		// - kreditnya ada 2 : 41101 - 41131 (penjualan) dan 21201 (utang PPN)/21204 (utang pph 23)

		// Untuk penjualan cetak, atk, kebersihan,Â medis


		// ================ END OF NOTE SETTING KODE AKUN UNTUK TRANSAKSI PENJUALAN =========

		// Cek data di buku_besar
		// $data_Penjualan_by_nmr_kirim_tgl_jual = $this->Tbl_penjualan_model->get_all_by_nmr_kirim_TGL_JUAL($nmrkirim, $Tgl_JUAL);


		// $GET_Source = "penjualan";
		// $sql = "SELECT * FROM `tbl_penjualan` WHERE `tgl_jual`='$Tgl_JUAL' AND `nmrkirim`='$nmrkirim'  ORDER BY `id`";
		$nmrpesan = trim((string) $this->input->get('nmrpesan', TRUE));
		$sql = "SELECT * FROM `tbl_penjualan` WHERE `nmrkirim`='" . $this->db->escape_str($nmrkirim) . "'";
		if ($nmrpesan !== '') {
			$sql .= " AND `nmrpesan`='" . $this->db->escape_str($nmrpesan) . "'";
		}
		$sql .= " ORDER BY `id`";

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

			if ($nmrpesan !== '') {
				$this->Tbl_penjualan_model->update_by_nmrkirim_nmrpesan($nmrkirim, $nmrpesan, $data);
			} else {
				$this->Tbl_penjualan_model->update_by_nmrkirim($nmrkirim, $data);
			}

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

			if ($nmrpesan !== '') {
				$this->Tbl_penjualan_model->update_by_nmrkirim_nmrpesan($nmrkirim, $nmrpesan, $data);
			} else {
				$this->Tbl_penjualan_model->update_by_nmrkirim($nmrkirim, $data);
			}
		}




		$search_filter = trim((string) $this->input->get('search_filter', TRUE));
		$spop_filter = trim((string) $this->input->get('spop_filter', TRUE));
		$redirect_url = 'Tbl_penjualan/setting_kode_akun_penjualan2?keep_filter=1';
		if ($search_filter !== '') {
			$redirect_url .= '&search_filter=' . rawurlencode($search_filter);
		}
		if ($spop_filter !== '') {
			$redirect_url .= '&spop_filter=' . rawurlencode($spop_filter);
		}
		redirect(site_url($redirect_url));
	}


	public function ubah_kode_akun($nmrkirim = null, $Tgl_JUAL = null)
	{
		$nmrpesan = trim((string) $this->input->get('nmrpesan', TRUE));
		if ($nmrpesan !== '') {
			$data_per_nmrkirim = $this->Tbl_penjualan_model->get_all_by_nmr_kirim_nmrpesan($nmrkirim, $nmrpesan);
		} else {
			$data_per_nmrkirim = $this->Tbl_penjualan_model->get_all_by_nmr_kirim($nmrkirim);
		}

		$sql = "SELECT `nmrkirim`,`kode_akun`,`kode_pl`,`kode_bb` FROM `tbl_penjualan` WHERE `nmrkirim`='" . $this->db->escape_str($nmrkirim) . "'";
		if ($nmrpesan !== '') {
			$sql .= " AND `nmrpesan`='" . $this->db->escape_str($nmrpesan) . "'";
		}
		$sql .= " GROUP BY `nmrkirim`,`kode_akun`";

		// $this->db->query($sql)->result();
		// print_r($this->db->query($sql)->row()->kode_akun);

		$get_kode_akun = $this->db->query($sql)->row()->kode_akun;
		$get_kode_pl = $this->db->query($sql)->row()->kode_pl;
		$get_kode_bb = $this->db->query($sql)->row()->kode_bb;
		// die;

		$search_filter = trim((string) $this->input->get('search_filter', TRUE));
		$spop_filter = trim((string) $this->input->get('spop_filter', TRUE));
		$Get_date = $Tgl_JUAL ? date('Y-m-d', strtotime($Tgl_JUAL)) : '';
		$action_url = site_url('Tbl_penjualan/update_kode_akun/' . $nmrkirim . ($Get_date ? '/' . $Get_date : ''));
		$query = array();
		if ($nmrpesan !== '') {
			$query[] = 'nmrpesan=' . rawurlencode($nmrpesan);
		}
		if ($search_filter !== '') {
			$query[] = 'search_filter=' . rawurlencode($search_filter);
		}
		if ($spop_filter !== '') {
			$query[] = 'spop_filter=' . rawurlencode($spop_filter);
		}
		if (!empty($query)) {
			$action_url .= '?' . implode('&', $query);
		}

		$data = array(
			'Tbl_penjualan_data' => $data_per_nmrkirim,
			'nmrkirim' => $nmrkirim,
			'nmrpesan' => $nmrpesan,
			'action' => $action_url,
			'button' => 'Update Kode AKun',
			// 'start' => $start,
			'get_kode_akun' => $get_kode_akun,
			'get_kode_pl' => $get_kode_pl,
			'get_kode_bb' => $get_kode_bb,
		);
		// print_r($data);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penjualan/adminlte310_tbl_penjualan_list_per_nmrkirim', $data);
	}

	public function ajax_compare_jurnal_penjualan_manual_online()
	{
		$this->load->helper(array('pembelian_persediaan', 'penjualan_jurnal_compare'));

		$bulan = trim((string) $this->input->post('bulan', TRUE));
		if ($bulan === '') {
			$bulan = $this->_compare_jurnal_penjualan_bulan_from_post();
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
				'message' => 'Pilih tabel manual yang akan dibandingkan.',
			));
			return;
		}

		persediaan_ajax_json_output($this, penjualan_jurnal_compare_run($this, $bulan, $table));
	}

	public function ajax_compare_tabel_list_jurnal_penjualan()
	{
		$this->load->helper(array('pembelian_persediaan', 'penjualan_jurnal_compare'));

		persediaan_ajax_json_output($this, array(
			'ok' => true,
			'tables' => persediaan_compare_list_db_tables($this),
		));
	}

	public function ajax_compare_check_csv_jurnal_penjualan()
	{
		$this->load->helper(array('pembelian_persediaan', 'penjualan_jurnal_compare'));

		$original_name = trim((string) $this->input->post('filename', TRUE));
		if ($original_name === '') {
			persediaan_ajax_json_output($this, array(
				'ok' => false,
				'message' => 'Nama file CSV tidak valid.',
			));
			return;
		}

		$bulan = $this->_compare_jurnal_penjualan_bulan_from_post();
		persediaan_ajax_json_output($this, penjualan_jurnal_compare_check_csv_table_name($this, $original_name, $bulan));
	}

	public function ajax_compare_validate_csv_jurnal_penjualan()
	{
		$this->load->helper(array('pembelian_persediaan', 'penjualan_jurnal_compare'));

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

		$result = penjualan_jurnal_compare_validate_csv_file($_FILES['csv_file']['tmp_name']);
		$result['file'] = $original_name;
		persediaan_ajax_json_output($this, $result);
	}

	public function ajax_compare_import_csv_jurnal_penjualan()
	{
		@set_time_limit(0);
		@ini_set('memory_limit', '512M');
		$this->load->helper(array('pembelian_persediaan', 'penjualan_jurnal_compare'));

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

		$bulan = $this->_compare_jurnal_penjualan_bulan_from_post();
		$bulan_num = (int) $this->input->post('bulan_num', TRUE);
		$tahun = (int) $this->input->post('tahun', TRUE);
		$result = penjualan_jurnal_compare_import_csv_to_db(
			$this,
			$_FILES['csv_file']['tmp_name'],
			$original_name,
			$bulan,
			$bulan_num,
			$tahun
		);

		persediaan_ajax_json_output($this, $result);
	}

	public function ajax_compare_tabel_preview_jurnal_penjualan()
	{
		@set_time_limit(0);
		@ini_set('memory_limit', '512M');
		$this->load->helper(array('pembelian_persediaan', 'penjualan_jurnal_compare'));

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

	public function excel_compare_jurnal_penjualan_manual_online()
	{
		$this->load->helper(array('exportexcel', 'pembelian_persediaan', 'penjualan_jurnal_compare', 'persediaan_display'));

		$bulan = trim((string) $this->input->post('bulan', TRUE));
		if ($bulan === '') {
			$bulan = $this->_compare_jurnal_penjualan_bulan_from_post();
		}
		$table = trim((string) $this->input->post('tabel', TRUE));

		if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
			show_error('Format bulan tidak valid (YYYY-MM).', 400);
			return;
		}
		if ($table === '') {
			show_error('Pilih tabel manual yang akan dibandingkan.', 400);
			return;
		}

		$jenis = trim((string) $this->input->post('jenis', TRUE));
		$allowed = array_keys(penjualan_jurnal_compare_jenis_definitions());
		if ($jenis === '' || !in_array($jenis, $allowed, true)) {
			show_error('Jenis export compare tidak valid.', 400);
			return;
		}

		$defs = penjualan_jurnal_compare_jenis_definitions();
		$suffix = isset($defs[$jenis]['file_suffix']) ? $defs[$jenis]['file_suffix'] : $jenis;
		$namaFile = 'Compare_Jurnal_Penjualan_' . $suffix . '_' . $bulan . '_' . date('Y-m-d_H-i-s') . '.xlsx';
		excel_prepare_download($namaFile);
		penjualan_jurnal_compare_export_excel_output($this, $bulan, $jenis, $table);
		exit();
	}

	private function _compare_jurnal_penjualan_bulan_from_post()
	{
		$bulan_num = (int) $this->input->post('bulan_num', TRUE);
		$tahun = (int) $this->input->post('tahun', TRUE);
		if ($bulan_num >= 1 && $bulan_num <= 12 && $tahun >= 2000) {
			return $tahun . '-' . str_pad((string) $bulan_num, 2, '0', STR_PAD_LEFT);
		}

		return '';
	}
}

/* End of file Tbl_penjualan.php */
/* Location: ./application/controllers/Tbl_penjualan.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-09-03 14:59:44 */
/* http://harviacode.com */
