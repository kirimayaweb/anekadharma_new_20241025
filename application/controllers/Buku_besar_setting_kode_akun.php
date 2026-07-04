<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Buku_besar_setting_kode_akun extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->helper('buku_besar_setting_kode_akun');
    }

    public function index()
    {
        $parsed = bb_ska_parse_bulan_from_post($this);
        if (!$this->input->post('bulan_ns') && !$this->input->post('bulan_num')) {
            $parsed = array(
                'year' => (int) date('Y'),
                'month' => (int) date('m'),
            );
        }

        $data = array(
            'bulan_ns_value' => sprintf('%04d-%02d', $parsed['year'], $parsed['month']),
            'bulan_num' => $parsed['month'],
            'tahun' => $parsed['year'],
            'values' => bb_ska_build_values_payload($this, $parsed['month'], $parsed['year'], true),
            'selected_tables' => bb_ska_tables_for_ui($this),
            'url_self' => site_url('Buku_besar_setting_kode_akun'),
            'url_json' => site_url('Buku_besar_setting_kode_akun/json'),
            'url_ajax_values' => site_url('Buku_besar_setting_kode_akun/ajax_values'),
            'url_get_value' => site_url('Buku_besar_setting_kode_akun/get_value'),
        );

        $this->template->load(
            'anekadharma/adminlte310_anekadharma_topnav_aside',
            'anekadharma/buku_besar/adminlte310_buku_besar_setting_kode_akun',
            $data
        );
    }

    public function json()
    {
        $this->load->helper('pembelian_persediaan');
        $parsed = bb_ska_parse_bulan_from_post($this);
        if (!$this->input->post('bulan_ns') && !$this->input->post('bulan_num') && !$this->input->get('bulan_ns')) {
            $bulan_ns = trim((string) $this->input->get('bulan_ns', TRUE));
            if (preg_match('/^(\d{4})-(\d{2})$/', $bulan_ns, $m)) {
                $parsed = array('year' => (int) $m[1], 'month' => (int) $m[2]);
            }
        }

        $include_zero = trim((string) $this->input->post('include_zero', TRUE));
        if ($include_zero === '') {
            $include_zero = trim((string) $this->input->get('include_zero', TRUE));
        }
        $include_all = ($include_zero === '' || $include_zero === '1' || strtolower($include_zero) === 'true');

        persediaan_ajax_json_output($this, array(
            'ok' => true,
            'bulan_ns' => sprintf('%04d-%02d', $parsed['year'], $parsed['month']),
            'bulan_num' => $parsed['month'],
            'tahun' => $parsed['year'],
            'values' => bb_ska_build_values_payload($this, $parsed['month'], $parsed['year'], $include_all),
            'values_map' => bb_ska_values_map($this, $parsed['month'], $parsed['year']),
            'selected_tables' => bb_ska_tables_for_ui($this),
        ));
    }

    public function ajax_values()
    {
        $this->json();
    }

    public function get_value($kode_akun = '')
    {
        $this->load->helper('pembelian_persediaan');
        $parsed = bb_ska_parse_bulan_from_post($this);
        if (!$this->input->post('bulan_ns') && !$this->input->post('bulan_num')) {
            $bulan_ns = trim((string) $this->input->get('bulan_ns', TRUE));
            if (preg_match('/^(\d{4})-(\d{2})$/', $bulan_ns, $m)) {
                $parsed = array('year' => (int) $m[1], 'month' => (int) $m[2]);
            }
        }
        if ($kode_akun === '') {
            $kode_akun = trim((string) $this->input->post('kode_akun', TRUE));
        }
        if ($kode_akun === '') {
            $kode_akun = trim((string) $this->input->get('kode_akun', TRUE));
        }

        persediaan_ajax_json_output($this, array(
            'ok' => true,
            'bulan_ns' => sprintf('%04d-%02d', $parsed['year'], $parsed['month']),
            'data' => bb_ska_get_kode_akun_value($this, $kode_akun, $parsed['month'], $parsed['year']),
        ));
    }
}

/* End of file Buku_besar_setting_kode_akun.php */
