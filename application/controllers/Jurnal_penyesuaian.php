<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jurnal_penyesuaian extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Jurnal_penyesuaian_model');
        $this->load->library('form_validation');
    }

    public function index_BU()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        if ($q <> '') {
            $config['base_url'] = base_url() . 'jurnal_penyesuaian/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'jurnal_penyesuaian/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'jurnal_penyesuaian/index.html';
            $config['first_url'] = base_url() . 'jurnal_penyesuaian/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Jurnal_penyesuaian_model->total_rows($q);
        $jurnal_penyesuaian = $this->Jurnal_penyesuaian_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'jurnal_penyesuaian_data' => $jurnal_penyesuaian,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->load->view('jurnal_penyesuaian/jurnal_penyesuaian_list', $data);
    }


    public function index()
    {
        $this->load->helper('jurnal_penyesuaian_list');

        $Get_date_awal = date('Y-m-01');
        $Get_date_akhir = date('Y-m-t');

        $list_data = jurnal_penyesuaian_compute_list_data_by_date_range($this, $Get_date_awal, $Get_date_akhir);
        $data = $this->_jurnal_penyesuaian_view_data(array_merge($list_data, array(
            'date_awal' => $Get_date_awal,
            'date_akhir' => $Get_date_akhir,
        )));

        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/jurnal_penyesuaian/adminlte310_jurnal_penyesuaian', $data);
    }

    public function cari_between_date($Tahun_selected = null, $Bulan_selected = null)
    {
        $this->load->helper('jurnal_penyesuaian_list');

        if ($Bulan_selected) {
            $month = (int) $Bulan_selected;
            $year = (int) $Tahun_selected;
        } else {
            $bulan_ns = trim((string) $this->input->post('bulan_ns', TRUE));
            if (preg_match('/^(\d{4})-(\d{2})$/', $bulan_ns, $m)) {
                $year = (int) $m[1];
                $month = (int) $m[2];
            } else {
                $year = (int) date('Y');
                $month = (int) date('m');
            }
        }

        if ($month < 1 || $month > 12) {
            $month = (int) date('m');
        }

        $Get_date_awal = sprintf('%04d-%02d-01', $year, $month);
        $Get_date_akhir = date('Y-m-t', strtotime($Get_date_awal));

        $list_data = jurnal_penyesuaian_compute_list_data_by_date_range($this, $Get_date_awal, $Get_date_akhir);
        $data = $this->_jurnal_penyesuaian_view_data(array_merge($list_data, array(
            'date_awal' => $Get_date_awal,
            'date_akhir' => $Get_date_akhir,
            'active_tab' => trim((string) $this->input->post('active_tab', TRUE)) === 'compare' ? 'compare' : 'data',
        )));

        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/jurnal_penyesuaian/adminlte310_jurnal_penyesuaian', $data);
    }

    private function _jurnal_penyesuaian_view_data($data = array())
    {
        $this->load->helper('jurnal_penyesuaian_list');
        if (!is_array($data)) {
            $data = array();
        }

        $date_awal = isset($data['date_awal']) ? $data['date_awal'] : date('Y-m-01');
        $date_akhir = isset($data['date_akhir']) ? $data['date_akhir'] : date('Y-m-t');

        $compare_bulan_num = (int) date('m', strtotime($date_awal));
        $compare_tahun_num = (int) date('Y', strtotime($date_awal));
        if ($compare_bulan_num < 1 || $compare_bulan_num > 12) {
            $compare_bulan_num = (int) date('m');
        }

        $data['compare_bulan_num'] = $compare_bulan_num;
        $data['compare_tahun_num'] = $compare_tahun_num;
        $data['nama_bulan_id'] = array(
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        );
        $data['gen_tahun_min'] = 2019;
        $data['gen_tahun_max'] = (int) date('Y') + 1;
        $data['active_tab'] = isset($data['active_tab']) ? $data['active_tab'] : 'data';
        $data['bulan_ns_value'] = sprintf('%04d-%02d', $compare_tahun_num, $compare_bulan_num);
        $data['bulan_label'] = jurnal_penyesuaian_bulan_teks($compare_bulan_num) . ' ' . $compare_tahun_num;
        $data['Get_date_awal'] = date('d-m-Y', strtotime($date_awal));
        $data['Get_date_akhir'] = date('d-m-Y', strtotime($date_akhir));
        $data['periode_label'] = isset($data['periode_label']) ? $data['periode_label'] : jurnal_penyesuaian_format_tanggal_display($date_awal) . ' s/d ' . jurnal_penyesuaian_format_tanggal_display($date_akhir);
        $data['url_cari_between_date'] = site_url('Jurnal_penyesuaian/cari_between_date');
        $data['url_jurnal_penyesuaian_excel'] = site_url('Jurnal_penyesuaian/excel_list');
        $data['url_compare_jurnal_penyesuaian_run'] = site_url('Jurnal_penyesuaian/ajax_compare_jurnal_penyesuaian_manual_online');
        $data['url_compare_jurnal_penyesuaian_excel'] = site_url('Jurnal_penyesuaian/excel_compare_jurnal_penyesuaian_manual_online');
        $data['url_compare_jurnal_penyesuaian_import_csv'] = site_url('Jurnal_penyesuaian/ajax_compare_import_csv_jurnal_penyesuaian');
        $data['url_compare_jurnal_penyesuaian_tabel_list'] = site_url('Jurnal_penyesuaian/ajax_compare_tabel_list_jurnal_penyesuaian');
        $data['url_compare_jurnal_penyesuaian_tabel_preview'] = site_url('Jurnal_penyesuaian/ajax_compare_tabel_preview_jurnal_penyesuaian');
        $data['url_compare_jurnal_penyesuaian_tabel_excel'] = site_url('Jurnal_penyesuaian/excel_compare_tabel_preview_jurnal_penyesuaian');
        $data['url_compare_jurnal_penyesuaian_tabel_validate'] = site_url('Jurnal_penyesuaian/ajax_compare_tabel_validate_jurnal_penyesuaian');
        $data['url_compare_jurnal_penyesuaian_tabel_detail'] = site_url('Jurnal_penyesuaian/ajax_compare_tabel_detail_jurnal_penyesuaian');
        $data['url_compare_jurnal_penyesuaian_tabel_import'] = site_url('Jurnal_penyesuaian/ajax_compare_import_table_to_jurnal_penyesuaian');
        $data['url_compare_jurnal_penyesuaian_tabel_detail_excel'] = site_url('Jurnal_penyesuaian/excel_compare_tabel_detail_jurnal_penyesuaian');
        $data['url_ajax_list_jurnal_penyesuaian'] = site_url('Jurnal_penyesuaian/ajax_list_jurnal_penyesuaian');
        $data['url_ajax_simpan_input'] = site_url('Jurnal_penyesuaian/ajax_simpan_input_data');

        return $data;
    }

    public function ajax_list_jurnal_penyesuaian()
    {
        $this->load->helper(array('jurnal_penyesuaian_list', 'pembelian_persediaan'));

        $bulan_ns = trim((string) $this->input->post('bulan_ns', TRUE));
        if (preg_match('/^(\d{4})-(\d{2})$/', $bulan_ns, $m)) {
            $date_awal = sprintf('%04d-%02d-01', (int) $m[1], (int) $m[2]);
            $date_akhir = date('Y-m-t', strtotime($date_awal));
        } else {
            $date_awal = $this->input->post('tgl_awal', TRUE);
            $date_akhir = $this->input->post('tgl_akhir', TRUE);
        }
        $list_data = jurnal_penyesuaian_compute_list_data_by_date_range($this, $date_awal, $date_akhir);

        persediaan_ajax_json_output($this, array(
            'ok' => true,
            'rows' => $list_data['Data_kas'],
            'total_debet' => jurnal_penyesuaian_format_rupiah($list_data['Total_debet'], true),
            'total_kredit' => jurnal_penyesuaian_format_rupiah($list_data['Total_kredit'], true),
            'periode_label' => $list_data['periode_label'],
            'total_rows' => (int) $list_data['total_rows'],
        ));
    }

    public function ajax_simpan_input_data()
    {
        $this->load->helper(array('jurnal_penyesuaian_list', 'pembelian_persediaan'));

        $built = $this->_build_simpan_data_from_post();
        if (empty($built['ok'])) {
            persediaan_ajax_json_output($this, $built);
            return;
        }

        $this->Jurnal_penyesuaian_model->insert($built['data']);
        $insert_id = $this->db->insert_id();

        persediaan_ajax_json_output($this, array(
            'ok' => true,
            'message' => 'Data berhasil disimpan.',
            'insert_id' => $insert_id,
            'tanggal' => $built['tanggal_display'],
        ));
    }

    private function _build_simpan_data_from_post()
    {
        $this->load->helper('jurnal_penyesuaian_list');

        $kode_akun = trim((string) $this->input->post('kode_akun', TRUE));
        $kode_rekening = trim((string) $this->input->post('kode_rekening', TRUE));
        $status_proses = trim((string) $this->input->post('status_proses', TRUE));
        $nominal = $this->input->post('nominal_penyesuaian', TRUE);
        $tgl_po_raw = trim((string) $this->input->post('tgl_po', TRUE));

        if ($kode_akun === '') {
            return array('ok' => false, 'message' => 'Kode akun wajib dipilih.');
        }
        if ($kode_rekening === '') {
            return array('ok' => false, 'message' => 'Kode rekening wajib diisi.');
        }
        if ($status_proses !== 'debet' && $status_proses !== 'kredit') {
            return array('ok' => false, 'message' => 'Pilih Debet atau Kredit.');
        }
        if ($nominal === '' || !is_numeric($nominal) || (float) $nominal <= 0) {
            return array('ok' => false, 'message' => 'Nominal harus diisi dan lebih dari 0.');
        }

        $date_jurnal_penyesuaian = jurnal_penyesuaian_parse_date_input($tgl_po_raw, date('Y-m-d'));
        $date_jurnal_penyesuaian .= ' ' . date('H:i:s');

        $data = array(
            'tanggal' => $date_jurnal_penyesuaian,
            'kode_akun' => $kode_akun,
            'keterangan' => trim((string) $this->input->post('keterangan', TRUE)),
            'kode_rekening' => $kode_rekening,
            'bukti' => trim((string) $this->input->post('bukti', TRUE)),
            'pl' => trim((string) $this->input->post('pl', TRUE)),
        );

        if ($status_proses === 'debet') {
            $data['debet'] = $nominal;
        } else {
            $data['kredit'] = $nominal;
        }

        return array(
            'ok' => true,
            'data' => $data,
            'tanggal_display' => jurnal_penyesuaian_format_tanggal_display($date_jurnal_penyesuaian),
        );
    }

    private function _compare_jurnal_penyesuaian_bulan_from_post()
    {
        $bulan_num = (int) $this->input->post('bulan_num', TRUE);
        $tahun = (int) $this->input->post('tahun', TRUE);
        if ($bulan_num >= 1 && $bulan_num <= 12 && $tahun >= 2000) {
            return $tahun . '-' . str_pad((string) $bulan_num, 2, '0', STR_PAD_LEFT);
        }

        return '';
    }

    public function ajax_compare_jurnal_penyesuaian_manual_online()
    {
        $this->load->helper(array('pembelian_persediaan', 'penjualan_jurnal_compare', 'jurnal_penyesuaian_compare'));

        $bulan = trim((string) $this->input->post('bulan', TRUE));
        if ($bulan === '') {
            $bulan = $this->_compare_jurnal_penyesuaian_bulan_from_post();
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

        persediaan_ajax_json_output($this, jurnal_penyesuaian_compare_run($this, $bulan, $table));
    }

    public function ajax_compare_tabel_list_jurnal_penyesuaian()
    {
        $this->load->helper(array('pembelian_persediaan', 'jurnal_penyesuaian_compare'));

        persediaan_ajax_json_output($this, array(
            'ok' => true,
            'tables' => persediaan_compare_list_db_tables($this),
        ));
    }

    public function ajax_compare_import_csv_jurnal_penyesuaian()
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');
        $this->load->helper(array('pembelian_persediaan', 'penjualan_jurnal_compare', 'jurnal_penyesuaian_compare'));

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

        $bulan = $this->_compare_jurnal_penyesuaian_bulan_from_post();
        $bulan_num = (int) $this->input->post('bulan_num', TRUE);
        $tahun = (int) $this->input->post('tahun', TRUE);
        $result = jurnal_penyesuaian_compare_import_csv_to_db(
            $this,
            $_FILES['csv_file']['tmp_name'],
            $original_name,
            $bulan,
            $bulan_num,
            $tahun
        );

        persediaan_ajax_json_output($this, $result);
    }

    public function ajax_compare_tabel_preview_jurnal_penyesuaian()
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');
        $this->load->helper(array('pembelian_persediaan', 'jurnal_penyesuaian_compare'));

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

    public function excel_compare_jurnal_penyesuaian_manual_online()
    {
        $this->load->helper(array('exportexcel', 'pembelian_persediaan', 'penjualan_jurnal_compare', 'jurnal_penyesuaian_compare', 'persediaan_display'));

        $bulan = trim((string) $this->input->post('bulan', TRUE));
        if ($bulan === '') {
            $bulan = $this->_compare_jurnal_penyesuaian_bulan_from_post();
        }
        $table = trim((string) $this->input->post('tabel', TRUE));

        if (!preg_match('/^\d{4}-\d{2}$/', $bulan) || $table === '') {
            show_error('Parameter compare tidak valid.', 400);
        }

        $namaFile = 'Compare_Jurnal_Penyesuaian_' . $bulan . '_' . date('Y-m-d_H-i-s') . '.xlsx';
        excel_prepare_download($namaFile);
        jurnal_penyesuaian_compare_export_excel_output($this, $bulan, $table);
        exit();
    }

    public function excel_compare_tabel_preview_jurnal_penyesuaian()
    {
        $this->load->helper(array('jurnal_penyesuaian_list', 'pembelian_persediaan'));
        $table = trim((string) $this->input->post('tabel', TRUE));
        if ($table === '') {
            show_error('Nama tabel belum dipilih.', 400);
        }
        jurnal_penyesuaian_export_excel_table_preview_output($this, $table);
        exit();
    }

    public function ajax_compare_tabel_validate_jurnal_penyesuaian()
    {
        $this->load->helper(array('pembelian_persediaan', 'penjualan_jurnal_compare', 'jurnal_penyesuaian_compare'));

        $table = trim((string) $this->input->post('tabel', TRUE));
        $bulan = trim((string) $this->input->post('bulan', TRUE));
        if ($bulan === '') {
            $bulan = $this->_compare_jurnal_penyesuaian_bulan_from_post();
        }

        if ($table === '') {
            persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Nama tabel belum dipilih.'));
            return;
        }
        if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
            persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Pilih bulan dan tahun yang valid.'));
            return;
        }

        try {
            persediaan_ajax_json_output($this, jurnal_penyesuaian_compare_validate_table_for_import($this, $table, $bulan));
        } catch (Exception $e) {
            persediaan_ajax_json_output($this, array(
                'ok' => false,
                'message' => 'Gagal memeriksa tabel: ' . $e->getMessage(),
            ));
        }
    }

    public function ajax_compare_tabel_detail_jurnal_penyesuaian()
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');
        $this->load->helper(array('pembelian_persediaan', 'penjualan_jurnal_compare', 'jurnal_penyesuaian_compare'));

        $table = trim((string) $this->input->post('tabel', TRUE));
        $bulan = trim((string) $this->input->post('bulan', TRUE));
        if ($bulan === '') {
            $bulan = $this->_compare_jurnal_penyesuaian_bulan_from_post();
        }

        if ($table === '') {
            persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Nama tabel belum dipilih.'));
            return;
        }
        if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
            persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Pilih bulan dan tahun yang valid.'));
            return;
        }

        persediaan_ajax_json_output($this, jurnal_penyesuaian_compare_load_table_detail_for_bulan($this, $table, $bulan));
    }

    public function ajax_compare_import_table_to_jurnal_penyesuaian()
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');
        $this->load->helper(array('pembelian_persediaan', 'penjualan_jurnal_compare', 'jurnal_penyesuaian_compare'));

        $table = trim((string) $this->input->post('tabel', TRUE));
        $bulan = trim((string) $this->input->post('bulan', TRUE));
        if ($bulan === '') {
            $bulan = $this->_compare_jurnal_penyesuaian_bulan_from_post();
        }

        if ($table === '') {
            persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Nama tabel belum dipilih.'));
            return;
        }
        if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
            persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Pilih bulan dan tahun yang valid.'));
            return;
        }

        try {
            $result = jurnal_penyesuaian_compare_import_to_jurnal_penyesuaian($this, $table, $bulan);
            if (empty($result['ok']) && !empty($result['db_error'])) {
                $result['message'] = trim((string) $result['message']) . ' Detail database: ' . $result['db_error'];
            }
            persediaan_ajax_json_output($this, $result);
        } catch (Exception $e) {
            persediaan_ajax_json_output($this, array(
                'ok' => false,
                'message' => 'Gagal menyimpan ke jurnal_penyesuaian: ' . $e->getMessage(),
                'error_detail' => $e->getMessage(),
            ));
        }
    }

    public function excel_compare_tabel_detail_jurnal_penyesuaian()
    {
        $this->load->helper(array('exportexcel', 'pembelian_persediaan', 'penjualan_jurnal_compare', 'jurnal_penyesuaian_compare'));

        $table = trim((string) $this->input->post('tabel', TRUE));
        if ($table === '') {
            $table = trim((string) $this->input->get('tabel', TRUE));
        }
        $bulan = trim((string) $this->input->post('bulan', TRUE));
        if ($bulan === '') {
            $bulan = trim((string) $this->input->get('bulan', TRUE));
        }
        if ($bulan === '') {
            $bulan = $this->_compare_jurnal_penyesuaian_bulan_from_post();
        }

        if ($table === '') {
            show_error('Nama tabel belum dipilih.', 400);
            return;
        }
        if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
            show_error('Format bulan tidak valid (YYYY-MM).', 400);
            return;
        }

        $namaFile = 'Detail_Tabel_Penyesuaian_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $table) . '_' . $bulan . '_' . date('Y-m-d_H-i-s') . '.xlsx';
        excel_prepare_download($namaFile);
        jurnal_penyesuaian_compare_export_table_detail_excel($this, $table, $bulan);
        exit();
    }

    public function excel_list()
    {
        $this->load->helper('jurnal_penyesuaian_list');
        jurnal_penyesuaian_export_excel_list_output($this);
        exit();
    }

    public function read($id)
    {
        $row = $this->Jurnal_penyesuaian_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'uuid_jurnal_penyesuaian' => $row->uuid_jurnal_penyesuaian,
                'kode_akun' => $row->kode_akun,
                'tanggal' => $row->tanggal,
                'keterangan' => $row->keterangan,
                'kode_rekening' => $row->kode_rekening,
                'debet' => $row->debet,
                'kredit' => $row->kredit,
            );
            $this->load->view('jurnal_penyesuaian/jurnal_penyesuaian_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_penyesuaian'));
        }
    }

    public function Simpan_input_data()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->index();
        } else {
            $this->load->helper('jurnal_penyesuaian_list');
            $built = $this->_build_simpan_data_from_post();
            if (empty($built['ok'])) {
                $this->session->set_flashdata('message', isset($built['message']) ? $built['message'] : 'Gagal menyimpan data.');
                redirect(site_url('Jurnal_penyesuaian'));
                return;
            }

            $this->Jurnal_penyesuaian_model->insert($built['data']);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('Jurnal_penyesuaian/cari_between_date/' . jurnal_penyesuaian_parse_date_input($this->input->post('tgl_po', TRUE), date('Y-m-d'))));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('jurnal_penyesuaian/create_action'),
            'id' => set_value('id'),
            'uuid_jurnal_penyesuaian' => set_value('uuid_jurnal_penyesuaian'),
            'kode_akun' => set_value('kode_akun'),
            'tanggal' => set_value('tanggal'),
            'keterangan' => set_value('keterangan'),
            'kode_rekening' => set_value('kode_rekening'),
            'debet' => set_value('debet'),
            'kredit' => set_value('kredit'),
        );
        $this->load->view('jurnal_penyesuaian/jurnal_penyesuaian_form', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'uuid_jurnal_penyesuaian' => $this->input->post('uuid_jurnal_penyesuaian', TRUE),
                'kode_akun' => $this->input->post('kode_akun', TRUE),
                'tanggal' => $this->input->post('tanggal', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
                'kode_rekening' => $this->input->post('kode_rekening', TRUE),
                'debet' => $this->input->post('debet', TRUE),
                'kredit' => $this->input->post('kredit', TRUE),
            );

            $this->Jurnal_penyesuaian_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('jurnal_penyesuaian'));
        }
    }

    public function update($id)
    {
        $row = $this->Jurnal_penyesuaian_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('jurnal_penyesuaian/update_action'),
                'id' => set_value('id', $row->id),
                'uuid_jurnal_penyesuaian' => set_value('uuid_jurnal_penyesuaian', $row->uuid_jurnal_penyesuaian),
                'kode_akun' => set_value('kode_akun', $row->kode_akun),
                'tanggal' => set_value('tanggal', $row->tanggal),
                'keterangan' => set_value('keterangan', $row->keterangan),
                'kode_rekening' => set_value('kode_rekening', $row->kode_rekening),
                'debet' => set_value('debet', $row->debet),
                'kredit' => set_value('kredit', $row->kredit),
            );
            $this->load->view('jurnal_penyesuaian/jurnal_penyesuaian_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_penyesuaian'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'uuid_jurnal_penyesuaian' => $this->input->post('uuid_jurnal_penyesuaian', TRUE),
                'kode_akun' => $this->input->post('kode_akun', TRUE),
                'tanggal' => $this->input->post('tanggal', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
                'kode_rekening' => $this->input->post('kode_rekening', TRUE),
                'debet' => $this->input->post('debet', TRUE),
                'kredit' => $this->input->post('kredit', TRUE),
            );

            $this->Jurnal_penyesuaian_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('jurnal_penyesuaian'));
        }
    }

    public function delete($id)
    {
        $row = $this->Jurnal_penyesuaian_model->get_by_id($id);

        if ($row) {
            $this->Jurnal_penyesuaian_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('jurnal_penyesuaian'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_penyesuaian'));
        }
    }

    public function _rules()
    {
        // $this->form_validation->set_rules('uuid_jurnal_penyesuaian', 'uuid jurnal penyesuaian', 'trim|required');
        $this->form_validation->set_rules('kode_akun', 'kode akun', 'trim|required');
        // $this->form_validation->set_rules('tanggal', 'tanggal', 'trim|required');
        // $this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');
        // $this->form_validation->set_rules('kode_rekening', 'kode rekening', 'trim|required');
        // $this->form_validation->set_rules('debet', 'debet', 'trim|required|numeric');
        // $this->form_validation->set_rules('kredit', 'kredit', 'trim|required|numeric');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "jurnal_penyesuaian.xls";
        $judul = "jurnal_penyesuaian";
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
        xlsWriteLabel($tablehead, $kolomhead++, "Uuid Jurnal Penyesuaian");
        xlsWriteLabel($tablehead, $kolomhead++, "Kode Akun");
        xlsWriteLabel($tablehead, $kolomhead++, "Tanggal");
        xlsWriteLabel($tablehead, $kolomhead++, "Keterangan");
        xlsWriteLabel($tablehead, $kolomhead++, "Kode Rekening");
        xlsWriteLabel($tablehead, $kolomhead++, "Debet");
        xlsWriteLabel($tablehead, $kolomhead++, "Kredit");

        foreach ($this->Jurnal_penyesuaian_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->uuid_jurnal_penyesuaian);
            xlsWriteLabel($tablebody, $kolombody++, $data->kode_akun);
            xlsWriteLabel($tablebody, $kolombody++, $data->tanggal);
            xlsWriteLabel($tablebody, $kolombody++, $data->keterangan);
            xlsWriteLabel($tablebody, $kolombody++, $data->kode_rekening);
            xlsWriteNumber($tablebody, $kolombody++, $data->debet);
            xlsWriteNumber($tablebody, $kolombody++, $data->kredit);

            $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }
}

/* End of file Jurnal_penyesuaian.php */
/* Location: ./application/controllers/Jurnal_penyesuaian.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2025-05-20 10:28:43 */
/* http://harviacode.com */