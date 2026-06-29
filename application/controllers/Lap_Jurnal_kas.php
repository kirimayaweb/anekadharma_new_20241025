<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lap_Jurnal_kas extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
    }

    public function index()
    {
        $saved_bulan = $this->_lap_jurnal_kas_get_bulan_from_session();
        if ($saved_bulan) {
            redirect(site_url('Lap_Jurnal_kas/cari_between_date/' . $saved_bulan['year'] . '/' . $saved_bulan['month']));
            return;
        }

        $month = (int) date('m');
        $year = (int) date('Y');
        $this->_render_lap_page($month, $year);
    }

    public function cari_between_date($Tahun_selected = null, $Bulan_selected = null)
    {
        if ($Bulan_selected) {
            $month = (int) $Bulan_selected;
            $year = (int) $Tahun_selected;
        } else {
            $month = (int) date('m', strtotime($this->input->post('bulan_ns', TRUE)));
            $year = (int) date('Y', strtotime($this->input->post('bulan_ns', TRUE)));
        }

        if ($month < 1 || $month > 12 || $year < 2000) {
            $month = (int) date('m');
            $year = (int) date('Y');
        }

        $this->_lap_jurnal_kas_save_bulan_session($year, $month);
        $this->_render_lap_page($month, $year);
    }

    public function excel($Tahun_selected = null, $Bulan_selected = null)
    {
        $this->load->helper('jurnal_kas_lap');

        $month = $Bulan_selected ? (int) $Bulan_selected : (int) date('m');
        $year = $Tahun_selected ? (int) $Tahun_selected : (int) date('Y');

        if ($month < 1 || $month > 12) {
            $month = (int) date('m');
        }

        jurnal_kas_lap_export_excel_output($this, $month, $year);
        exit();
    }

    private function _lap_jurnal_kas_session_bulan_key()
    {
        return 'lap_jurnal_kas_bulan_terpilih';
    }

    private function _lap_jurnal_kas_save_bulan_session($year, $month)
    {
        $year = (int) $year;
        $month = (int) $month;
        if ($month >= 1 && $month <= 12 && $year >= 2000) {
            $this->session->set_userdata($this->_lap_jurnal_kas_session_bulan_key(), sprintf('%04d-%02d', $year, $month));
        }
    }

    private function _lap_jurnal_kas_get_bulan_from_session()
    {
        $val = $this->session->userdata($this->_lap_jurnal_kas_session_bulan_key());
        if ($val && preg_match('/^(\d{4})-(\d{1,2})$/', (string) $val, $m)) {
            $month = (int) $m[2];
            $year = (int) $m[1];
            if ($month >= 1 && $month <= 12 && $year >= 2000) {
                return array('year' => $year, 'month' => $month);
            }
        }

        return null;
    }

    private function _render_lap_page($month, $year)
    {
        $this->load->helper(array('jurnal_kas_lap', 'jurnal_kas_list'));

        $report = jurnal_kas_lap_compute_report_data($this, $month, $year, null, null, true);
        if (empty($report['ok'])) {
            show_error(isset($report['message']) ? $report['message'] : 'Data laporan tidak tersedia.', 400);
            return;
        }

        $is_published = empty($report['not_published']);
        $publish_setting = isset($report['publish_setting']) ? $report['publish_setting'] : null;

        $date_awal = date('Y-m-01 00:00:00', mktime(0, 0, 0, $month, 1, $year));
        $date_akhir = date('Y-m-t 23:59:59', mktime(0, 0, 0, $month, 1, $year));

        $data = array(
            'report_rows' => $report['rows'],
            'TOTAL_debet' => $report['TOTAL_debet'],
            'TOTAL_kredit' => $report['TOTAL_kredit'],
            'SALDO_AKHIR' => $report['SALDO_AKHIR'],
            'source_type' => $report['source_type'],
            'source_table' => $report['source_table'],
            'publish_setting' => $publish_setting,
            'is_published' => $is_published,
            'not_published' => !$is_published,
            'date_awal' => $date_awal,
            'date_akhir' => $date_akhir,
            'month_selected' => $month,
            'year_selected' => $year,
            'bulan_label' => $report['bulan_label'],
            'bulan_ns_value' => sprintf('%04d-%02d', $year, $month),
            'url_cari_lap_jurnal_kas' => site_url('Lap_Jurnal_kas/cari_between_date'),
            'url_lap_jurnal_kas_excel' => site_url('Lap_Jurnal_kas/excel/' . $year . '/' . $month),
            'nama_bulan_id' => array(
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
            ),
        );

        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/jurnal_kas/adminlte310_lap_jurnal_kas_list', $data);
    }
}

/* End of file Lap_Jurnal_kas.php */
