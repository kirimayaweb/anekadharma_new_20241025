<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Laporan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        // is_login();
        $this->load->model(array('Tbl_pembelian_model', 'Tbl_penjualan_model', 'Tbl_pembelian_pengajuan_bayar_model', 'User_model', 'Sys_bank_model'));
        $this->load->library('form_validation');
        $this->load->library('datatables');
        $this->load->library('Pdf');
        $this->load->helper(array('nominal'));
    }

    public function index()
    {
        // $this->template->load('template', 'laporan/laporan');

        // PRODUK
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->uri->segment(3));

        if ($q <> '') {
            $config['base_url'] = base_url() . '.php/c_url/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'index.php/tbl_produk/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'index.php/tbl_produk/index/';
            $config['first_url'] = base_url() . 'index.php/tbl_produk/index/';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = FALSE;
        $config['total_rows'] = $this->Tbl_produk_model->total_rows($q);
        $tbl_produk = $this->Tbl_produk_model->get_limit_data($config['per_page'], $start, $q);
        $config['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
        $config['full_tag_close'] = '</ul>';
        $this->load->library('pagination');
        $this->pagination->initialize($config);

        // $data = array(
        //     'tbl_produk_data' => $tbl_produk,
        //     'q' => $q,
        //     'pagination' => $this->pagination->create_links(),
        //     'total_rows' => $config['total_rows'],
        //     'start' => $start,
        // );
        // END OF PRODUK


        // STOCK BARANG

        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->uri->segment(3));

        if ($q <> '') {
            $config['base_url'] = base_url() . '.php/c_url/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'index.php/tbl_stok_barang/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'index.php/tbl_stok_barang/index/';
            $config['first_url'] = base_url() . 'index.php/tbl_stok_barang/index/';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = FALSE;
        $config['total_rows'] = $this->Tbl_stok_barang_model->total_rows($q);
        $tbl_stok_barang = $this->Tbl_stok_barang_model->get_limit_data($config['per_page'], $start, $q);
        $config['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
        $config['full_tag_close'] = '</ul>';
        $this->load->library('pagination');
        $this->pagination->initialize($config);

        // $data = array(
        //     'tbl_stok_barang_data' => $tbl_stok_barang,
        //     'q' => $q,
        //     'pagination' => $this->pagination->create_links(),
        //     'total_rows' => $config['total_rows'],
        //     'start' => $start,
        // );

        // END OF STOCK BARANG


        // SALES

        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->uri->segment(3));

        if ($q <> '') {
            $config['base_url'] = base_url() . '.php/c_url/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'index.php/tbl_sales/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'index.php/tbl_sales/index/';
            $config['first_url'] = base_url() . 'index.php/tbl_sales/index/';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = FALSE;
        $config['total_rows'] = $this->Tbl_sales_model->total_rows($q);
        $tbl_sales = $this->Tbl_sales_model->get_limit_data($config['per_page'], $start, $q);
        $config['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
        $config['full_tag_close'] = '</ul>';
        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'tbl_sales_data' => $tbl_sales,
            'tbl_stok_barang_data' => $tbl_stok_barang, //DARI DATA STOCK=================
            'tbl_produk_data' => $tbl_produk, //DARI DATA PRODUK===============            
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );

        // END OF SALES



        $this->template->load('template', 'laporan/laporan', $data);
    }


    public function create_action_laba_rugi_NEW($Get_tahun = null, $Get_bulan = null, $data_name = null)
    {
        print_r("create_action_laba_rugi_NEW");
        print_r("<br/>");
        print_r($Get_tahun);
        print_r("<br/>");
        print_r($Get_bulan);
        print_r("<br/>");
        print_r($data_name);
        print_r("<br/>");
        die;
    }

    public function update_laba_rugi($Get_id_RECORD = null, $data_name = null)
    {

        print_r("update_laba_rugi");
        print_r("<br/>");
        print_r($Get_id_RECORD);
        print_r("<br/>");
        print_r($data_name);
        print_r("<br/>");
        die;
    }


    public function labarugi_form() {}

    public function labarugi($Get_tahun = null, $Get_bulan = null)
    {



        if ($Get_bulan) {
            // Neraca bulanan
            // print_r("Bulanan");
            // print_r("<br/>");
            $sql = "SELECT * FROM `tbl_laba_rugi` WHERE `tahun_transaksi`='$Get_tahun' And `bulan_transaksi`='$Get_bulan' ";
            $GET_tbl_laba_rugi_RECORD = $this->db->query($sql);


            if ($GET_tbl_laba_rugi_RECORD->num_rows() > 0) {
                $data = array(
                    'button' => 'Update',
                    'action' => site_url('Laporan/update_laba_rugi/'  . $GET_tbl_laba_rugi_RECORD->row()->id),
                    'id' => set_value('id'),
                    // 'data_detail' => $data_detail,
                    'data_tbl_laba_rugi' => $GET_tbl_laba_rugi_RECORD->row(),
                    'uuid_data_neraca' => $GET_tbl_laba_rugi_RECORD->row()->uuid_data_neraca,
                    'tahun_neraca' => $Get_tahun,
                    'bulan_transaksi' => $Get_bulan,
                );
            } else {
                $data = array(
                    'button' => 'Simpan',
                    'action' => site_url('Laporan/create_action_neraca_NEW/' . $Get_tahun . '/' . $Get_bulan),
                    'id' => set_value('id'),
                    // 'data_detail' => $data_detail,
                    // 'uuid_data_neraca' => "",
                    'tahun_neraca' => $Get_tahun,
                    'bulan_transaksi' => $Get_bulan,
                );
            }
        } else {
            // Neraca tahunan
            // print_r("Tahunan");
            // print_r("<br/>");

            $sql = "SELECT * FROM `tbl_laba_rugi` WHERE `tahun_transaksi`='$Get_tahun' And `bulan_transaksi`='' ";

            $GET_tbl_laba_rugi_RECORD = $this->db->query($sql);



            if ($GET_tbl_laba_rugi_RECORD->num_rows() > 0) {
                $data = array(
                    'button' => 'Update',
                    'action' => site_url('Laporan/update_laba_rugi/'  . $GET_tbl_laba_rugi_RECORD->row()->id),
                    'id' => set_value('id'),
                    // 'data_detail' => $data_detail,
                    'data_tbl_laba_rugi' => $GET_tbl_laba_rugi_RECORD->row(),
                    'uuid_data_neraca' => $GET_tbl_laba_rugi_RECORD->row()->uuid_data_neraca,
                    'tahun_neraca' => $Get_tahun,
                    'bulan_transaksi' => 0,
                );
            } else {
                $data = array(
                    'button' => 'Simpan',
                    'action' => site_url('Laporan/create_action_neraca_NEW/' . $Get_tahun . '/' . $Get_bulan),
                    'id' => set_value('id'),
                    // 'data_detail' => $data_detail,
                    // 'uuid_data_neraca' => "",
                    'tahun_neraca' => $Get_tahun,
                    'bulan_transaksi' => 0,
                );
            }
        }




        // $data = array(

        //     'button' => 'Simpan',
        //     'action' => site_url('tbl_pembelian/create_pembayaran_action/'),
        //     'id' => set_value('id'),


        // );

        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/labarugi/adminlte310_labarugi', $data);
    }

    public function labarugi_print()
    {

        // $data = array(

        // 	'button' => 'Simpan',
        // 	'action' => site_url('tbl_pembelian/create_pembayaran_action/'),
        // 	'id' => set_value('id'),


        // );

        // $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/labarugi/adminlte310_labarugi_cetak', $data);


        // 2.a. PERSIAPAN LIBRARY
        $this->load->library('PdfGenerator');

        // 2.b. PERSIAPAN DATA
        $data = array(
            // 'button' => 'Simpan',
            // 'action' => site_url('Laporan/create_action_neraca'),
            // 'id' => set_value('id'),
            'id' => set_value('id'),
        );


        // 2.C. MENAMPILKAN FILE DATA
        // $data = array_merge($data);
        $html = $this->load->view('anekadharma/labarugi/adminlte310_labarugi_cetak.php', $data, true);

        // 2.d. CONVERT TAMPILAN FILE DATA MENJADI FILE PDF
        $this->pdf->loadHtml($html);
        $this->pdf->render();

        $this->pdf->stream("CETAK_LABA_RUGI.pdf", array("Attachment" => 0));
    }
}
