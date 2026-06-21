<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jurnal_umum extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Jurnal_umum_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    public function index()
    {
        $Get_date_awal = date('Y-m-01');
        $Get_date_akhir = date('Y-m-t');

        $list_data = $this->_load_list_data($Get_date_awal, $Get_date_akhir);
        $data = $this->_jurnal_umum_view_data(array_merge($list_data, array(
            'date_awal' => $Get_date_awal,
            'date_akhir' => $Get_date_akhir,
        )));

        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/jurnal_umum/adminlte310_jurnal_umum_list', $data);
    }

    public function cari_between_date()
    {
        $this->load->helper('jurnal_umum_list');
        $tgl_awal_raw = $this->input->post('tgl_awal', TRUE);
        $tgl_akhir_raw = $this->input->post('tgl_akhir', TRUE);

        $Get_date_awal = jurnal_umum_parse_date_input($tgl_awal_raw, date('Y-m-01'));
        $Get_date_akhir = jurnal_umum_parse_date_input($tgl_akhir_raw, date('Y-m-t'));
        if ($Get_date_awal > $Get_date_akhir) {
            $tmp = $Get_date_awal;
            $Get_date_awal = $Get_date_akhir;
            $Get_date_akhir = $tmp;
        }

        $list_data = $this->_load_list_data($Get_date_awal, $Get_date_akhir);
        $data = $this->_jurnal_umum_view_data(array_merge($list_data, array(
            'date_awal' => $Get_date_awal,
            'date_akhir' => $Get_date_akhir,
            'active_tab' => trim((string) $this->input->post('active_tab', TRUE)) === 'compare' ? 'compare' : 'data',
        )));

        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/jurnal_umum/adminlte310_jurnal_umum_list', $data);
    }

    private function _load_list_data($date_awal, $date_akhir)
    {
        $this->load->helper('jurnal_umum_list');
        return jurnal_umum_compute_list_data_by_date_range($this, $date_awal, $date_akhir);
    }

    private function _jurnal_umum_view_data($data = array())
    {
        $this->load->helper('jurnal_umum_list');
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
        $data['Get_date_awal'] = date('d-m-Y', strtotime($date_awal));
        $data['Get_date_akhir'] = date('d-m-Y', strtotime($date_akhir));
        $data['periode_label'] = isset($data['periode_label']) ? $data['periode_label'] : jurnal_umum_format_tanggal_display($date_awal) . ' s/d ' . jurnal_umum_format_tanggal_display($date_akhir);
        $data['url_cari_between_date'] = site_url('Jurnal_umum/cari_between_date');
        $data['url_jurnal_umum_excel'] = site_url('Jurnal_umum/excel_list');
        $data['url_compare_jurnal_umum_run'] = site_url('Jurnal_umum/ajax_compare_jurnal_umum_manual_online');
        $data['url_compare_jurnal_umum_excel'] = site_url('Jurnal_umum/excel_compare_jurnal_umum_manual_online');
        $data['url_compare_jurnal_umum_import_csv'] = site_url('Jurnal_umum/ajax_compare_import_csv_jurnal_umum');
        $data['url_compare_jurnal_umum_tabel_list'] = site_url('Jurnal_umum/ajax_compare_tabel_list_jurnal_umum');
        $data['url_compare_jurnal_umum_tabel_preview'] = site_url('Jurnal_umum/ajax_compare_tabel_preview_jurnal_umum');
        $data['url_compare_jurnal_umum_tabel_excel'] = site_url('Jurnal_umum/excel_compare_tabel_preview_jurnal_umum');

        return $data;
    }

    private function _compare_jurnal_umum_bulan_from_post()
    {
        $bulan_num = (int) $this->input->post('bulan_num', TRUE);
        $tahun = (int) $this->input->post('tahun', TRUE);
        if ($bulan_num >= 1 && $bulan_num <= 12 && $tahun >= 2000) {
            return $tahun . '-' . str_pad((string) $bulan_num, 2, '0', STR_PAD_LEFT);
        }

        return '';
    }

    public function ajax_compare_jurnal_umum_manual_online()
    {
        $this->load->helper(array('pembelian_persediaan', 'penjualan_jurnal_compare', 'jurnal_umum_compare'));

        $bulan = trim((string) $this->input->post('bulan', TRUE));
        if ($bulan === '') {
            $bulan = $this->_compare_jurnal_umum_bulan_from_post();
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

        persediaan_ajax_json_output($this, jurnal_umum_compare_run($this, $bulan, $table));
    }

    public function ajax_compare_tabel_list_jurnal_umum()
    {
        $this->load->helper(array('pembelian_persediaan', 'jurnal_umum_compare'));

        persediaan_ajax_json_output($this, array(
            'ok' => true,
            'tables' => persediaan_compare_list_db_tables($this),
        ));
    }

    public function ajax_compare_import_csv_jurnal_umum()
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');
        $this->load->helper(array('pembelian_persediaan', 'penjualan_jurnal_compare', 'jurnal_umum_compare'));

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

        $bulan = $this->_compare_jurnal_umum_bulan_from_post();
        $bulan_num = (int) $this->input->post('bulan_num', TRUE);
        $tahun = (int) $this->input->post('tahun', TRUE);
        $result = jurnal_umum_compare_import_csv_to_db(
            $this,
            $_FILES['csv_file']['tmp_name'],
            $original_name,
            $bulan,
            $bulan_num,
            $tahun
        );

        persediaan_ajax_json_output($this, $result);
    }

    public function ajax_compare_tabel_preview_jurnal_umum()
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');
        $this->load->helper(array('pembelian_persediaan', 'jurnal_umum_compare'));

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

    public function excel_compare_jurnal_umum_manual_online()
    {
        $this->load->helper(array('exportexcel', 'pembelian_persediaan', 'penjualan_jurnal_compare', 'jurnal_umum_compare', 'persediaan_display'));

        $bulan = trim((string) $this->input->post('bulan', TRUE));
        if ($bulan === '') {
            $bulan = $this->_compare_jurnal_umum_bulan_from_post();
        }
        $table = trim((string) $this->input->post('tabel', TRUE));

        if (!preg_match('/^\d{4}-\d{2}$/', $bulan) || $table === '') {
            show_error('Parameter compare tidak valid.', 400);
        }

        $namaFile = 'Compare_Jurnal_Umum_' . $bulan . '_' . date('Y-m-d_H-i-s') . '.xlsx';
        excel_prepare_download($namaFile);
        jurnal_umum_compare_export_excel_output($this, $bulan, $table);
        exit();
    }

    public function excel_compare_tabel_preview_jurnal_umum()
    {
        $this->load->helper(array('jurnal_umum_list', 'pembelian_persediaan'));
        $table = trim((string) $this->input->post('tabel', TRUE));
        if ($table === '') {
            show_error('Nama tabel belum dipilih.', 400);
        }
        jurnal_umum_export_excel_table_preview_output($this, $table);
        exit();
    }

    public function excel_list()
    {
        $this->load->helper('jurnal_umum_list');
        jurnal_umum_export_excel_list_output($this);
        exit();
    }

    public function index_SERVER_SIDE()
    {
        $this->load->view('anekadharma/jurnal_umum/jurnal_umum_list');
    }

    public function json()
    {
        header('Content-Type: application/json');
        echo $this->Jurnal_umum_model->json();
    }

    public function read($id)
    {
        $row = $this->Jurnal_umum_model->get_by_id($id);
        if ($row) {
            $data = array(
                'nomor' => $row->nomor,
                'tanggal' => $row->tanggal,
                'bukti' => $row->bukti,
                'pl' => $row->pl,
                'ref' => $row->ref,
                'uraian_kode_rekening' => $row->uraian_kode_rekening,
                'rekening' => $row->rekening,
                'debit' => $row->debit,
                'kredit' => $row->kredit,
            );
            $this->load->view('jurnal_umum/jurnal_umum_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_umum'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('jurnal_umum/create_action'),
            'nomor' => set_value('nomor'),
            'tanggal' => set_value('tanggal'),
            'bukti' => set_value('bukti'),
            'pl' => set_value('pl'),
            'ref' => set_value('ref'),
            'uraian_kode_rekening' => set_value('uraian_kode_rekening'),
            'rekening' => set_value('rekening'),
            'debit' => set_value('debit'),
            'kredit' => set_value('kredit'),
        );
        $this->load->view('jurnal_umum/jurnal_umum_form', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'tanggal' => $this->input->post('tanggal', TRUE),
                'bukti' => $this->input->post('bukti', TRUE),
                'pl' => $this->input->post('pl', TRUE),
                'ref' => $this->input->post('ref', TRUE),
                'uraian_kode_rekening' => $this->input->post('uraian_kode_rekening', TRUE),
                'rekening' => $this->input->post('rekening', TRUE),
                'debit' => $this->input->post('debit', TRUE),
                'kredit' => $this->input->post('kredit', TRUE),
            );

            $this->Jurnal_umum_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('jurnal_umum'));
        }
    }

    public function update($id)
    {
        $row = $this->Jurnal_umum_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('jurnal_umum/update_action'),
                'nomor' => set_value('nomor', $row->nomor),
                'tanggal' => set_value('tanggal', $row->tanggal),
                'bukti' => set_value('bukti', $row->bukti),
                'pl' => set_value('pl', $row->pl),
                'ref' => set_value('ref', $row->ref),
                'uraian_kode_rekening' => set_value('uraian_kode_rekening', $row->uraian_kode_rekening),
                'rekening' => set_value('rekening', $row->rekening),
                'debit' => set_value('debit', $row->debit),
                'kredit' => set_value('kredit', $row->kredit),
            );
            $this->load->view('jurnal_umum/jurnal_umum_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_umum'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('nomor', TRUE));
        } else {
            $data = array(
                'tanggal' => $this->input->post('tanggal', TRUE),
                'bukti' => $this->input->post('bukti', TRUE),
                'pl' => $this->input->post('pl', TRUE),
                'ref' => $this->input->post('ref', TRUE),
                'uraian_kode_rekening' => $this->input->post('uraian_kode_rekening', TRUE),
                'rekening' => $this->input->post('rekening', TRUE),
                'debit' => $this->input->post('debit', TRUE),
                'kredit' => $this->input->post('kredit', TRUE),
            );

            $this->Jurnal_umum_model->update($this->input->post('nomor', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('jurnal_umum'));
        }
    }

    public function delete($id)
    {
        $row = $this->Jurnal_umum_model->get_by_id($id);

        if ($row) {
            $this->Jurnal_umum_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('jurnal_umum'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_umum'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('tanggal', 'tanggal', 'trim|required');
        $this->form_validation->set_rules('bukti', 'bukti', 'trim|required');
        $this->form_validation->set_rules('pl', 'pl', 'trim|required');
        $this->form_validation->set_rules('ref', 'ref', 'trim|required');
        $this->form_validation->set_rules('uraian_kode_rekening', 'uraian kode rekening', 'trim|required');
        $this->form_validation->set_rules('rekening', 'rekening', 'trim|required');
        $this->form_validation->set_rules('debit', 'debit', 'trim|required');
        $this->form_validation->set_rules('kredit', 'kredit', 'trim|required');

        $this->form_validation->set_rules('nomor', 'nomor', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->excel_list();
    }
}

/* End of file Jurnal_umum.php */
/* Location: ./application/controllers/Jurnal_umum.php */
