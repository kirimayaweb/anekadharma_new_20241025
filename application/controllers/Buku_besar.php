<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Buku_besar extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model(array('Tbl_pembelian_model', 'Tbl_penjualan_model', 'Buku_besar_model'));
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    public function index()
    {
        $data = $this->_buku_besar_view_data();
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/buku_besar/adminlte310_buku_besar_list', $data);
    }

    public function cari_data($uuid_kode_akun = null, $Get_Year_Selected = null, $Get_Month_Selected = null)
    {
        $this->load->helper('buku_besar_list');

        if ($Get_Month_Selected) {
            $month = (int) $Get_Month_Selected;
            $year = (int) $Get_Year_Selected;
        } else {
            $parsed = buku_besar_parse_bulan_ns($this->input->post('bulan_ns', TRUE));
            $month = $parsed['month'];
            $year = $parsed['year'];
        }

        $uuid = 'tampil_semua';
        $bb_filter_kode = isset($data['bb_filter_kode']) ? $data['bb_filter_kode'] : 'semua';
        $bb_filter_teks = isset($data['bb_filter_teks']) ? $data['bb_filter_teks'] : '';
        $list = buku_besar_compute_list_data($this, $month, $year, $uuid, $bb_filter_kode, $bb_filter_teks);

        $posted_tab = trim((string) $this->input->post('active_tab', TRUE));
        $active_tab = in_array($posted_tab, array('compare', 'setting'), true) ? $posted_tab : 'data';
        $data = $this->_buku_besar_view_data(array_merge($list, array(
            'active_tab' => $active_tab,
        )));

        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/buku_besar/adminlte310_buku_besar_list', $data);
    }

    public function cari_kode_akun()
    {
        $this->load->helper('buku_besar_list');
        $parsed = buku_besar_parse_bulan_ns($this->input->post('bulan_ns', TRUE));
        redirect(site_url('Buku_besar/cari_data/' . $this->input->post('kode_akun', TRUE) . '/' . $parsed['year'] . '/' . $parsed['month']));
    }

    public function ajax_list_data()
    {
        $this->load->helper(array('buku_besar_list', 'pembelian_persediaan'));
        $parsed = buku_besar_parse_bulan_ns($this->input->post('bulan_ns', TRUE));
        $uuid = 'tampil_semua';
        $bb_filter_kode = trim((string) $this->input->post('bb_filter_kode', TRUE));
        $bb_filter_teks = trim((string) $this->input->post('bb_filter_teks', TRUE));
        if ($bb_filter_kode === '') {
            $bb_filter_kode = 'semua';
        }
        $list = buku_besar_compute_list_data($this, $parsed['month'], $parsed['year'], $uuid, $bb_filter_kode, $bb_filter_teks);

        persediaan_ajax_json_output($this, array(
            'ok' => true,
            'rows' => $list['data_Buku_besar'],
            'total_debet' => buku_besar_format_rupiah($list['total_debet'], true),
            'total_kredit' => buku_besar_format_rupiah($list['total_kredit'], true),
            'total_debet_raw' => $list['total_debet'],
            'total_kredit_raw' => $list['total_kredit'],
            'bulan_label' => $list['bulan_label'],
            'bulan_ns_value' => $list['bulan_ns_value'],
            'uuid_kode_akun' => $list['uuid_kode_akun'],
            'kode_akun' => $list['kode_akun'],
            'nama_akun' => $list['nama_akun'],
            'total_rows' => $list['total_rows'],
            'source_stats' => isset($list['source_stats']) ? $list['source_stats'] : array(),
        ));
    }

    public function excel_list()
    {
        $this->load->helper('buku_besar_list');
        buku_besar_export_excel_list_output($this);
        exit();
    }

    private function _buku_besar_view_data($data = array())
    {
        $this->load->helper('buku_besar_list');
        if (!is_array($data)) {
            $data = array();
        }

        $month = isset($data['month']) ? (int) $data['month'] : (int) date('m');
        $year = isset($data['year']) ? (int) $data['year'] : (int) date('Y');
        if ($month < 1 || $month > 12) {
            $month = (int) date('m');
        }

        if (!isset($data['data_Buku_besar'])) {
            $bb_filter_kode = isset($data['bb_filter_kode']) ? $data['bb_filter_kode'] : 'semua';
            $bb_filter_teks = isset($data['bb_filter_teks']) ? $data['bb_filter_teks'] : '';
            $list = buku_besar_compute_list_data(
                $this,
                $month,
                $year,
                'tampil_semua',
                $bb_filter_kode,
                $bb_filter_teks
            );
            $data = array_merge($data, $list);
        }

        if (!isset($data['source_stats'])) {
            $data['source_stats'] = buku_besar_get_source_kode_akun_stats($this, $month, $year);
        }

        $data['compare_bulan_num'] = $month;
        $data['compare_tahun_num'] = $year;
        $data['nama_bulan_id'] = array(
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        );
        $data['gen_tahun_min'] = 2019;
        $data['gen_tahun_max'] = (int) date('Y') + 1;
        $data['active_tab'] = isset($data['active_tab']) ? $data['active_tab'] : 'data';
        $data['bulan_ns_value'] = sprintf('%04d-%02d', $year, $month);
        $data['bulan_label'] = buku_besar_bulan_teks($month) . ' ' . $year;
        $data['action'] = site_url('Buku_besar/cari_kode_akun');
        $data['url_cari_data'] = site_url('Buku_besar/cari_data');
        $data['url_ajax_list'] = site_url('Buku_besar/ajax_list_data');
        $data['url_modal_jurnal_pembelian'] = site_url('Tbl_pembelian/ajax_bb_modal_jurnal_pembelian');
        $data['url_modal_jurnal_penjualan'] = site_url('Tbl_penjualan/ajax_bb_modal_jurnal_penjualan');
        $data['url_buku_besar_excel'] = site_url('Buku_besar/excel_list');
        $data['url_compare_run'] = site_url('Buku_besar/ajax_compare_buku_besar_manual_online');
        $data['url_compare_excel'] = site_url('Buku_besar/excel_compare_buku_besar_manual_online');
        $data['url_compare_import'] = site_url('Buku_besar/ajax_compare_import_csv_buku_besar');
        $data['url_compare_list'] = site_url('Buku_besar/ajax_compare_tabel_list_buku_besar');
        $data['url_compare_validate'] = site_url('Buku_besar/ajax_compare_tabel_validate_buku_besar');
        $data['url_compare_detail'] = site_url('Buku_besar/ajax_compare_tabel_detail_buku_besar');
        $data['url_compare_tabel_import'] = site_url('Buku_besar/ajax_compare_import_table_to_buku_besar');
        $data['url_compare_detail_excel'] = site_url('Buku_besar/excel_compare_tabel_detail_buku_besar');
        $data['url_compare_section_excel'] = site_url('Buku_besar/excel_compare_section_buku_besar');
        $data['url_bb_recalculate'] = site_url('Buku_besar/ajax_recalculate_penjualan');
        $data['url_setting_kode_akun_panel'] = site_url('Buku_besar/ajax_setting_kode_akun_panel');

        $this->load->helper('buku_besar_recalculate');
        $data = array_merge($data, bb_setting_kode_akun_panel_data($this));

        $sql = "SELECT * FROM sys_kode_akun ORDER BY kode_akun ASC";
        $data['list_kode_akun'] = $this->db->query($sql)->result();

        return $data;
    }

    public function ajax_setting_kode_akun_panel()
    {
        $this->load->helper(array('buku_besar_recalculate', 'pembelian_persediaan'));
        $data = bb_setting_kode_akun_panel_data($this);
        $html = $this->load->view('anekadharma/buku_besar/partials/setting_kode_akun_unit_panel', $data, true);
        persediaan_ajax_json_output($this, array(
            'ok' => true,
            'html' => $html,
        ));
    }

    public function ajax_recalculate_penjualan()
    {
        $this->load->helper(array('buku_besar_recalculate', 'buku_besar_list', 'pembelian_persediaan'));
        $parsed = buku_besar_parse_bulan_ns($this->input->post('bulan_ns', TRUE));
        persediaan_ajax_json_output($this, bb_recalc_penjualan($this, $parsed['month'], $parsed['year']));
    }

    private function _compare_buku_besar_bulan_from_post()
    {
        $bulan_num = (int) $this->input->post('bulan_num', TRUE);
        $tahun = (int) $this->input->post('tahun', TRUE);
        if ($bulan_num >= 1 && $bulan_num <= 12 && $tahun >= 2000) {
            return $tahun . '-' . str_pad((string) $bulan_num, 2, '0', STR_PAD_LEFT);
        }
        return '';
    }

    public function ajax_compare_buku_besar_manual_online()
    {
        $this->load->helper(array('pembelian_persediaan', 'penjualan_jurnal_compare', 'buku_besar_compare'));

        $bulan = trim((string) $this->input->post('bulan', TRUE));
        if ($bulan === '') {
            $bulan = $this->_compare_buku_besar_bulan_from_post();
        }
        $table = trim((string) $this->input->post('tabel', TRUE));

        if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
            persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Pilih bulan dan tahun yang valid.'));
            return;
        }
        if ($table === '') {
            persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Pilih tabel manual yang akan dibandingkan.'));
            return;
        }

        persediaan_ajax_json_output($this, buku_besar_compare_run($this, $bulan, $table));
    }

    public function ajax_compare_tabel_list_buku_besar()
    {
        $this->load->helper(array('pembelian_persediaan', 'buku_besar_compare'));
        persediaan_ajax_json_output($this, array('ok' => true, 'tables' => persediaan_compare_list_db_tables($this)));
    }

    public function ajax_compare_import_csv_buku_besar()
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');
        $this->load->helper(array('pembelian_persediaan', 'penjualan_jurnal_compare', 'buku_besar_compare'));

        if (empty($_FILES['csv_file']) || !is_uploaded_file($_FILES['csv_file']['tmp_name'])) {
            persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Pilih file CSV terlebih dahulu.'));
            return;
        }

        $original_name = trim((string) $_FILES['csv_file']['name']);
        if (strtolower(pathinfo($original_name, PATHINFO_EXTENSION)) !== 'csv') {
            persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'File harus berformat .csv'));
            return;
        }

        $bulan = $this->_compare_buku_besar_bulan_from_post();
        $result = buku_besar_compare_import_csv_to_db(
            $this,
            $_FILES['csv_file']['tmp_name'],
            $original_name,
            $bulan,
            (int) $this->input->post('bulan_num', TRUE),
            (int) $this->input->post('tahun', TRUE)
        );

        persediaan_ajax_json_output($this, $result);
    }

    public function ajax_compare_tabel_validate_buku_besar()
    {
        $this->load->helper(array('pembelian_persediaan', 'penjualan_jurnal_compare', 'buku_besar_compare'));

        $table = trim((string) $this->input->post('tabel', TRUE));
        $bulan = trim((string) $this->input->post('bulan', TRUE));
        if ($bulan === '') {
            $bulan = $this->_compare_buku_besar_bulan_from_post();
        }

        if ($table === '') {
            persediaan_ajax_json_output($this, array(
                'ok' => true, 'eligible' => false, 'import_enabled' => false,
                'message' => 'Nama tabel belum dipilih.',
            ));
            return;
        }

        if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
            $structure = buku_besar_compare_validate_import_table($this, $table);
            persediaan_ajax_json_output($this, array(
                'ok' => true,
                'eligible' => !empty($structure['ok']),
                'import_enabled' => false,
                'message' => isset($structure['message']) ? $structure['message'] : 'Struktur tabel tidak valid.',
                'missing_fields' => isset($structure['missing_fields']) ? $structure['missing_fields'] : array(),
                'table' => $table,
            ));
            return;
        }

        persediaan_ajax_json_output($this, buku_besar_compare_validate_table_for_import($this, $table, $bulan));
    }

    public function ajax_compare_tabel_detail_buku_besar()
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');
        $this->load->helper(array('pembelian_persediaan', 'penjualan_jurnal_compare', 'buku_besar_compare'));

        $table = trim((string) $this->input->post('tabel', TRUE));
        $bulan = trim((string) $this->input->post('bulan', TRUE));
        if ($bulan === '') {
            $bulan = $this->_compare_buku_besar_bulan_from_post();
        }

        if ($table === '') {
            persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Nama tabel belum dipilih.'));
            return;
        }
        if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
            persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Pilih bulan dan tahun yang valid.'));
            return;
        }

        persediaan_ajax_json_output($this, buku_besar_compare_load_table_detail_for_bulan($this, $table, $bulan));
    }

    public function ajax_compare_import_table_to_buku_besar()
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');
        $this->load->helper(array('pembelian_persediaan', 'penjualan_jurnal_compare', 'buku_besar_compare'));

        $table = trim((string) $this->input->post('tabel', TRUE));
        $bulan = trim((string) $this->input->post('bulan', TRUE));
        if ($bulan === '') {
            $bulan = $this->_compare_buku_besar_bulan_from_post();
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
            $result = buku_besar_compare_import_to_buku_besar($this, $table, $bulan);
            if (empty($result['ok']) && !empty($result['db_error'])) {
                $result['message'] = trim((string) $result['message']) . ' Detail database: ' . $result['db_error'];
            }
            persediaan_ajax_json_output($this, $result);
        } catch (Exception $e) {
            persediaan_ajax_json_output($this, array(
                'ok' => false,
                'message' => 'Gagal menyimpan ke buku_besar: ' . $e->getMessage(),
                'error_detail' => $e->getMessage(),
            ));
        }
    }

    public function excel_compare_tabel_detail_buku_besar()
    {
        $this->load->helper(array('exportexcel', 'pembelian_persediaan', 'penjualan_jurnal_compare', 'buku_besar_compare'));

        $table = trim((string) $this->input->post('tabel', TRUE));
        if ($table === '') {
            $table = trim((string) $this->input->get('tabel', TRUE));
        }
        $bulan = trim((string) $this->input->post('bulan', TRUE));
        if ($bulan === '') {
            $bulan = trim((string) $this->input->get('bulan', TRUE));
        }
        if ($bulan === '') {
            $bulan = $this->_compare_buku_besar_bulan_from_post();
        }

        if ($table === '') {
            show_error('Nama tabel belum dipilih.', 400);
            return;
        }
        if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
            show_error('Format bulan tidak valid (YYYY-MM).', 400);
            return;
        }

        $namaFile = 'Detail_Tabel_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $table) . '_' . $bulan . '_' . date('Y-m-d_H-i-s') . '.xlsx';
        excel_prepare_download($namaFile);
        buku_besar_compare_export_table_detail_excel($this, $table, $bulan);
        exit();
    }

    public function excel_compare_section_buku_besar()
    {
        $this->load->helper(array('exportexcel', 'pembelian_persediaan', 'penjualan_jurnal_compare', 'buku_besar_compare'));

        $bulan = trim((string) $this->input->post('bulan', TRUE));
        if ($bulan === '') {
            $bulan = $this->_compare_buku_besar_bulan_from_post();
        }
        $table = trim((string) $this->input->post('tabel', TRUE));
        $jenis = trim((string) $this->input->post('jenis', TRUE));

        if (!preg_match('/^\d{4}-\d{2}$/', $bulan) || $table === '' || $jenis === '') {
            show_error('Parameter export section tidak valid.', 400);
        }

        $namaFile = 'Compare_BB_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $jenis) . '_' . $bulan . '_' . date('Y-m-d_H-i-s') . '.xlsx';
        excel_prepare_download($namaFile);
        buku_besar_compare_export_section_excel($this, $bulan, $table, $jenis);
        exit();
    }

    public function excel_compare_buku_besar_manual_online()
    {
        $this->load->helper(array('exportexcel', 'pembelian_persediaan', 'penjualan_jurnal_compare', 'buku_besar_compare'));

        $bulan = trim((string) $this->input->post('bulan', TRUE));
        if ($bulan === '') {
            $bulan = $this->_compare_buku_besar_bulan_from_post();
        }
        $table = trim((string) $this->input->post('tabel', TRUE));

        if (!preg_match('/^\d{4}-\d{2}$/', $bulan) || $table === '') {
            show_error('Parameter compare tidak valid.', 400);
        }

        $namaFile = 'Compare_Buku_Besar_' . $bulan . '_' . date('Y-m-d_H-i-s') . '.xlsx';
        excel_prepare_download($namaFile);
        buku_besar_compare_export_excel_output($this, $bulan, $table);
        exit();
    }

    public function index_server_side()
    {
        $this->load->view('buku_besar/buku_besar_list');
    }

    public function json()
    {
        header('Content-Type: application/json');
        echo $this->Buku_besar_model->json();
    }

    public function read($id)
    {
        $row = $this->Buku_besar_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'uuid_buku_besar' => $row->uuid_buku_besar,
                'tanggal' => $row->tanggal,
                'kode_akun' => $row->kode_akun,
                'keterangan' => $row->keterangan,
                'kode' => $row->kode,
                'debet' => $row->debet,
                'kredit' => $row->kredit,
                'saldo' => $row->saldo,
            );
            $this->load->view('buku_besar/buku_besar_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('buku_besar'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('buku_besar/create_action'),
            'id' => set_value('id'),
            'uuid_buku_besar' => set_value('uuid_buku_besar'),
            'tanggal' => set_value('tanggal'),
            'kode_akun' => set_value('kode_akun'),
            'keterangan' => set_value('keterangan'),
            'kode' => set_value('kode'),
            'debet' => set_value('debet'),
            'kredit' => set_value('kredit'),
            'saldo' => set_value('saldo'),
        );
        $this->load->view('buku_besar/buku_besar_form', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'uuid_buku_besar' => $this->input->post('uuid_buku_besar', TRUE),
                'tanggal' => $this->input->post('tanggal', TRUE),
                'kode_akun' => $this->input->post('kode_akun', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
                'kode' => $this->input->post('kode', TRUE),
                'debet' => $this->input->post('debet', TRUE),
                'kredit' => $this->input->post('kredit', TRUE),
                'saldo' => $this->input->post('saldo', TRUE),
            );

            $this->Buku_besar_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('buku_besar'));
        }
    }

    public function update($id)
    {
        $row = $this->Buku_besar_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('buku_besar/update_action'),
                'id' => set_value('id', $row->id),
                'uuid_buku_besar' => set_value('uuid_buku_besar', $row->uuid_buku_besar),
                'tanggal' => set_value('tanggal', $row->tanggal),
                'kode_akun' => set_value('kode_akun', $row->kode_akun),
                'keterangan' => set_value('keterangan', $row->keterangan),
                'kode' => set_value('kode', $row->kode),
                'debet' => set_value('debet', $row->debet),
                'kredit' => set_value('kredit', $row->kredit),
                'saldo' => set_value('saldo', $row->saldo),
            );
            $this->load->view('buku_besar/buku_besar_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('buku_besar'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'uuid_buku_besar' => $this->input->post('uuid_buku_besar', TRUE),
                'tanggal' => $this->input->post('tanggal', TRUE),
                'kode_akun' => $this->input->post('kode_akun', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
                'kode' => $this->input->post('kode', TRUE),
                'debet' => $this->input->post('debet', TRUE),
                'kredit' => $this->input->post('kredit', TRUE),
                'saldo' => $this->input->post('saldo', TRUE),
            );

            $this->Buku_besar_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('buku_besar'));
        }
    }

    public function delete($id)
    {
        $row = $this->Buku_besar_model->get_by_id($id);

        if ($row) {
            $this->Buku_besar_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('buku_besar'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('buku_besar'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('uuid_buku_besar', 'uuid buku besar', 'trim|required');
        $this->form_validation->set_rules('tanggal', 'tanggal', 'trim|required');
        $this->form_validation->set_rules('kode_akun', 'kode akun', 'trim|required');
        $this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');
        $this->form_validation->set_rules('kode', 'kode', 'trim|required');
        $this->form_validation->set_rules('debet', 'debet', 'trim|required|numeric');
        $this->form_validation->set_rules('kredit', 'kredit', 'trim|required|numeric');
        $this->form_validation->set_rules('saldo', 'saldo', 'trim|required|numeric');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "buku_besar.xls";
        $judul = "buku_besar";
        $tablehead = 0;
        $tablebody = 1;
        $nourut = 1;
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
        xlsWriteLabel($tablehead, $kolomhead++, "Uuid Buku Besar");
        xlsWriteLabel($tablehead, $kolomhead++, "Tanggal");
        xlsWriteLabel($tablehead, $kolomhead++, "Kode Akun");
        xlsWriteLabel($tablehead, $kolomhead++, "Keterangan");
        xlsWriteLabel($tablehead, $kolomhead++, "Kode");
        xlsWriteLabel($tablehead, $kolomhead++, "Debet");
        xlsWriteLabel($tablehead, $kolomhead++, "Kredit");
        xlsWriteLabel($tablehead, $kolomhead++, "Saldo");

        foreach ($this->Buku_besar_model->get_all() as $data) {
            $kolombody = 0;
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->uuid_buku_besar);
            xlsWriteLabel($tablebody, $kolombody++, $data->tanggal);
            xlsWriteLabel($tablebody, $kolombody++, $data->kode_akun);
            xlsWriteLabel($tablebody, $kolombody++, $data->keterangan);
            xlsWriteLabel($tablebody, $kolombody++, $data->kode);
            xlsWriteNumber($tablebody, $kolombody++, $data->debet);
            xlsWriteNumber($tablebody, $kolombody++, $data->kredit);
            xlsWriteNumber($tablebody, $kolombody++, $data->saldo);

            $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }
}

/* End of file Buku_besar.php */
