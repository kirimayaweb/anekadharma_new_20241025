<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Freeze extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        // $this->load->model(array('Tbl_sales_model', 'Trans_penjualan_detail_model', 'Trans_pemesanan_model', 'Tbl_produk_model', 'Trans_penjualan_model', 'Tbl_produk_mapel_referensi_model', 'Trans_retur_model', 'KirimWa_model', 'Trans_pemesanan_detail_model', 'Trans_retur_detail_model'));
        $this->load->library('form_validation');
        $this->load->library('Pdf');
        $this->load->helper(array('nominal'));
    }

    public function index()
    {
        $this->load->view('template/freeze');
    }
}