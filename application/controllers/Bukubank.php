<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bukubank extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Bukubank_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    public function index()
    {
        $data = $this->_bukubank_view_data();
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/buku_bank/adminlte310_buku_bank_list', $data);
    }

    public function ajax_list_data()
    {
        $this->load->helper(array('bukubank_list', 'pembelian_persediaan'));
        $parsed = bukubank_parse_bulan_ns($this->input->post('bulan_ns', TRUE));
        $list = bukubank_compute_list_data($this, $parsed['month'], $parsed['year']);

        persediaan_ajax_json_output($this, array(
            'ok' => true,
            'rows' => $list['data_buku_bank'],
            'total_debet' => bukubank_format_rupiah($list['total_debet'], true),
            'total_kredit' => bukubank_format_rupiah($list['total_kredit'], true),
            'total_saldo' => bukubank_format_rupiah($list['total_saldo'], true),
            'bulan_label' => $list['bulan_label'],
            'bulan_ns_value' => $list['bulan_ns_value'],
            'total_rows' => $list['total_rows'],
        ));
    }

    public function excel_list()
    {
        $this->load->helper('bukubank_list');
        bukubank_export_excel_list_output($this);
        exit();
    }

    private function _bukubank_view_data($data = array())
    {
        $this->load->helper('bukubank_list');
        if (!is_array($data)) {
            $data = array();
        }

        $month = isset($data['month']) ? (int) $data['month'] : (int) date('m');
        $year = isset($data['year']) ? (int) $data['year'] : (int) date('Y');
        if ($month < 1 || $month > 12) {
            $month = (int) date('m');
        }

        if (!isset($data['data_buku_bank'])) {
            $list = bukubank_compute_list_data($this, $month, $year);
            $data = array_merge($data, $list);
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
        $data['bulan_label'] = bukubank_bulan_teks($month) . ' ' . $year;
        $data['url_ajax_list'] = site_url('Bukubank/ajax_list_data');
        $data['url_bukubank_excel'] = site_url('Bukubank/excel_list');
        $data['url_compare_run'] = site_url('Bukubank/ajax_compare_bukubank_manual_online');
        $data['url_compare_excel'] = site_url('Bukubank/excel_compare_bukubank_manual_online');
        $data['url_compare_import'] = site_url('Bukubank/ajax_compare_import_csv_bukubank');
        $data['url_compare_list'] = site_url('Bukubank/ajax_compare_tabel_list_bukubank');
        $data['url_compare_validate'] = site_url('Bukubank/ajax_compare_tabel_validate_bukubank');
        $data['url_compare_detail'] = site_url('Bukubank/ajax_compare_tabel_detail_bukubank');
        $data['url_compare_tabel_import'] = site_url('Bukubank/ajax_compare_import_table_to_bukubank');
        $data['url_compare_detail_excel'] = site_url('Bukubank/excel_compare_tabel_detail_bukubank');
        $data['url_compare_section_excel'] = site_url('Bukubank/excel_compare_section_bukubank');

        return $data;
    }

    private function _compare_bukubank_bulan_from_post()
    {
        $bulan_num = (int) $this->input->post('bulan_num', TRUE);
        $tahun = (int) $this->input->post('tahun', TRUE);
        if ($bulan_num >= 1 && $bulan_num <= 12 && $tahun >= 2000) {
            return $tahun . '-' . str_pad((string) $bulan_num, 2, '0', STR_PAD_LEFT);
        }
        return '';
    }

    public function ajax_compare_bukubank_manual_online()
    {
        $this->load->helper(array('pembelian_persediaan', 'penjualan_jurnal_compare', 'bukubank_compare'));

        $bulan = trim((string) $this->input->post('bulan', TRUE));
        if ($bulan === '') {
            $bulan = $this->_compare_bukubank_bulan_from_post();
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

        persediaan_ajax_json_output($this, bukubank_compare_run($this, $bulan, $table));
    }

    public function ajax_compare_tabel_list_bukubank()
    {
        $this->load->helper(array('pembelian_persediaan', 'bukubank_compare'));
        persediaan_ajax_json_output($this, array('ok' => true, 'tables' => persediaan_compare_list_db_tables($this)));
    }

    public function ajax_compare_import_csv_bukubank()
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');
        $this->load->helper(array('pembelian_persediaan', 'penjualan_jurnal_compare', 'bukubank_compare'));

        if (empty($_FILES['csv_file']) || !is_uploaded_file($_FILES['csv_file']['tmp_name'])) {
            persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Pilih file CSV terlebih dahulu.'));
            return;
        }

        $original_name = trim((string) $_FILES['csv_file']['name']);
        if (strtolower(pathinfo($original_name, PATHINFO_EXTENSION)) !== 'csv') {
            persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'File harus berformat .csv'));
            return;
        }

        $bulan = $this->_compare_bukubank_bulan_from_post();
        $result = bukubank_compare_import_csv_to_db(
            $this,
            $_FILES['csv_file']['tmp_name'],
            $original_name,
            $bulan,
            (int) $this->input->post('bulan_num', TRUE),
            (int) $this->input->post('tahun', TRUE)
        );

        persediaan_ajax_json_output($this, $result);
    }

    public function ajax_compare_tabel_validate_bukubank()
    {
        $this->load->helper(array('pembelian_persediaan', 'penjualan_jurnal_compare', 'bukubank_compare'));

        $table = trim((string) $this->input->post('tabel', TRUE));
        $bulan = trim((string) $this->input->post('bulan', TRUE));
        if ($bulan === '') {
            $bulan = $this->_compare_bukubank_bulan_from_post();
        }

        if ($table === '') {
            persediaan_ajax_json_output($this, array(
                'ok' => true, 'eligible' => false, 'import_enabled' => false,
                'message' => 'Nama tabel belum dipilih.',
            ));
            return;
        }

        if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
            $structure = bukubank_compare_validate_import_table($this, $table);
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

        persediaan_ajax_json_output($this, bukubank_compare_validate_table_for_import($this, $table, $bulan));
    }

    public function ajax_compare_tabel_detail_bukubank()
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');
        $this->load->helper(array('pembelian_persediaan', 'penjualan_jurnal_compare', 'bukubank_compare'));

        $table = trim((string) $this->input->post('tabel', TRUE));
        $bulan = trim((string) $this->input->post('bulan', TRUE));
        if ($bulan === '') {
            $bulan = $this->_compare_bukubank_bulan_from_post();
        }

        if ($table === '') {
            persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Nama tabel belum dipilih.'));
            return;
        }
        if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
            persediaan_ajax_json_output($this, array('ok' => false, 'message' => 'Pilih bulan dan tahun yang valid.'));
            return;
        }

        persediaan_ajax_json_output($this, bukubank_compare_load_table_detail_for_bulan($this, $table, $bulan));
    }

    public function ajax_compare_import_table_to_bukubank()
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');
        $this->load->helper(array('pembelian_persediaan', 'penjualan_jurnal_compare', 'bukubank_compare'));

        $table = trim((string) $this->input->post('tabel', TRUE));
        $bulan = trim((string) $this->input->post('bulan', TRUE));
        if ($bulan === '') {
            $bulan = $this->_compare_bukubank_bulan_from_post();
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
            $result = bukubank_compare_import_to_bukubank($this, $table, $bulan);
            if (empty($result['ok']) && !empty($result['db_error'])) {
                $result['message'] = trim((string) $result['message']) . ' Detail database: ' . $result['db_error'];
            }
            persediaan_ajax_json_output($this, $result);
        } catch (Exception $e) {
            persediaan_ajax_json_output($this, array(
                'ok' => false,
                'message' => 'Gagal menyimpan ke bukubank: ' . $e->getMessage(),
                'error_detail' => $e->getMessage(),
            ));
        }
    }

    public function excel_compare_tabel_detail_bukubank()
    {
        $this->load->helper(array('exportexcel', 'pembelian_persediaan', 'penjualan_jurnal_compare', 'bukubank_compare'));

        $table = trim((string) $this->input->post('tabel', TRUE));
        if ($table === '') {
            $table = trim((string) $this->input->get('tabel', TRUE));
        }
        $bulan = trim((string) $this->input->post('bulan', TRUE));
        if ($bulan === '') {
            $bulan = trim((string) $this->input->get('bulan', TRUE));
        }
        if ($bulan === '') {
            $bulan = $this->_compare_bukubank_bulan_from_post();
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
        bukubank_compare_export_table_detail_excel($this, $table, $bulan);
        exit();
    }

    public function excel_compare_section_bukubank()
    {
        $this->load->helper(array('exportexcel', 'pembelian_persediaan', 'penjualan_jurnal_compare', 'bukubank_compare'));

        $bulan = trim((string) $this->input->post('bulan', TRUE));
        if ($bulan === '') {
            $bulan = $this->_compare_bukubank_bulan_from_post();
        }
        $table = trim((string) $this->input->post('tabel', TRUE));
        $jenis = trim((string) $this->input->post('jenis', TRUE));

        if (!preg_match('/^\d{4}-\d{2}$/', $bulan) || $table === '' || $jenis === '') {
            show_error('Parameter export section tidak valid.', 400);
        }

        $namaFile = 'Compare_BB_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $jenis) . '_' . $bulan . '_' . date('Y-m-d_H-i-s') . '.xlsx';
        excel_prepare_download($namaFile);
        bukubank_compare_export_section_excel($this, $bulan, $table, $jenis);
        exit();
    }

    public function excel_compare_bukubank_manual_online()
    {
        $this->load->helper(array('exportexcel', 'pembelian_persediaan', 'penjualan_jurnal_compare', 'bukubank_compare'));

        $bulan = trim((string) $this->input->post('bulan', TRUE));
        if ($bulan === '') {
            $bulan = $this->_compare_bukubank_bulan_from_post();
        }
        $table = trim((string) $this->input->post('tabel', TRUE));

        if (!preg_match('/^\d{4}-\d{2}$/', $bulan) || $table === '') {
            show_error('Parameter compare tidak valid.', 400);
        }

        $namaFile = 'Compare_Buku_Bank_' . $bulan . '_' . date('Y-m-d_H-i-s') . '.xlsx';
        excel_prepare_download($namaFile);
        bukubank_compare_export_excel_output($this, $bulan, $table);
        exit();
    }

    public function index_server_side()
    {
        $this->load->view('bukubank/bukubank_list');
    }

    public function json()
    {
        header('Content-Type: application/json');
        echo $this->Bukubank_model->json();
    }

    public function read($id)
    {
        $row = $this->Bukubank_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'tanggal' => $row->tanggal,
                'bank' => $row->bank,
                'norek' => $row->norek,
                'keterangan' => $row->keterangan,
                'kode' => $row->kode,
                'debet' => $row->debet,
                'kredit' => $row->kredit,
                'saldo' => $row->saldo,
            );
            $this->load->view('bukubank/bukubank_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('bukubank'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Simpan',
            'action' => site_url('bukubank/create_action'),
            'id' => set_value('id'),
            'tanggal' => set_value('tanggal'),
            'bank' => set_value('bank'),
            'norek' => set_value('norek'),
            'keterangan' => set_value('keterangan'),
            'kode' => set_value('kode'),
            'debet' => set_value('debet'),
            'kredit' => set_value('kredit'),
            'saldo' => set_value('saldo'),
        );

        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/buku_bank/adminlte310_buku_bank_form', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            if (date("Y", strtotime($this->input->post('tgl_po', TRUE))) < 2020) {
                $date_po = date("Y-m-d H:i:s");
            } else {
                $date_po = date("Y-m-d H:i:s", strtotime($this->input->post('tgl_po', TRUE)));
            }

            $data = array(
                'tanggal' => $date_po,
                'bank' => $this->input->post('bank', TRUE),
                'norek' => $this->input->post('norek', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
                'kode' => $this->input->post('kode', TRUE),
                'debet' => $this->input->post('debet', TRUE),
                'kredit' => $this->input->post('kredit', TRUE),
            );

            $this->Bukubank_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('bukubank'));
        }
    }

    public function update($id)
    {
        $row = $this->Bukubank_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('bukubank/update_action'),
                'id' => set_value('id', $row->id),
                'tanggal' => set_value('tanggal', $row->tanggal),
                'bank' => set_value('bank', $row->bank),
                'norek' => set_value('norek', $row->norek),
                'keterangan' => set_value('keterangan', $row->keterangan),
                'kode' => set_value('kode', $row->kode),
                'debet' => set_value('debet', $row->debet),
                'kredit' => set_value('kredit', $row->kredit),
                'saldo' => set_value('saldo', $row->saldo),
            );
            $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/buku_bank/adminlte310_buku_bank_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('bukubank'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            if (date("Y", strtotime($this->input->post('tgl_po', TRUE))) < 2020) {
                $date_po = date("Y-m-d H:i:s");
            } else {
                $date_po = date("Y-m-d H:i:s", strtotime($this->input->post('tgl_po', TRUE)));
            }

            $data = array(
                'tanggal' => $date_po,
                'bank' => $this->input->post('bank', TRUE),
                'norek' => $this->input->post('norek', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
                'kode' => $this->input->post('kode', TRUE),
                'debet' => $this->input->post('debet', TRUE),
                'kredit' => $this->input->post('kredit', TRUE),
                'saldo' => $this->input->post('saldo', TRUE),
            );

            $this->Bukubank_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('bukubank'));
        }
    }

    public function delete($id)
    {
        $row = $this->Bukubank_model->get_by_id($id);

        if ($row) {
            $this->Bukubank_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('bukubank'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('bukubank'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('tgl_po', 'tgl_po', 'trim|required');
        $this->form_validation->set_rules('bank', 'bank', 'trim|required');
        $this->form_validation->set_rules('norek', 'norek', 'trim|required');
        $this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');
        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('bukubank_list');
        bukubank_export_excel_list_output($this);
        exit();
    }
}

/* End of file Bukubank.php */
