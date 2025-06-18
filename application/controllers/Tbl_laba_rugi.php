<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_laba_rugi extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        // $this->load->model('Tbl_laba_rugi_model');
        // $this->load->library('form_validation');

        $this->load->model(array('Tbl_laba_rugi_model', 'Tbl_pembelian_model', 'Tbl_penjualan_model', 'Tbl_pembelian_pengajuan_bayar_model', 'User_model', 'Sys_bank_model'));
        $this->load->library('form_validation');
        $this->load->library('datatables');
        $this->load->library('Pdf');
        $this->load->helper(array('nominal'));
    }

    public function index()
    {
        if ($laporan_status) {
            $status_laporan = $laporan_status;
        } else {
            $status_laporan = "laporan";
        }

        $sql = "SELECT year(`tgl_po`) as tahun_neraca FROM `tbl_pembelian` GROUP by year(`tgl_po`) order by  `tgl_po` DESC";

        $tahun_labarugi_data = $this->db->query($sql)->result();

        $sql = "SELECT year(`tgl_po`) as tahun_neraca, month(`tgl_po`) as bulan_neraca FROM `tbl_pembelian` GROUP by year(`tgl_po`), month(`tgl_po`) order by `tgl_po` DESC;";

        $bulan_neraca_labarugi_data = $this->db->query($sql)->result();

        $start = 0;
        $data = array(
            'Tbl_TAHUN_labarugi_data' => $tahun_labarugi_data,
            'Tbl_BULAN_labarugi_data' => $bulan_neraca_labarugi_data,
            'start' => $start,
            'status_laporan' => $status_laporan,
            'action_input_labarugi_baru' => site_url('Tbl_laba_rugi/labarugi_form_input/'),
            'action_input_labarugi_baru_bulanan' => site_url('Tbl_laba_rugi/labarugi_form_input_bulanan/'),
        );

        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_laba_rugi/adminlte310_tbl_labarugi_data_list', $data);
    }

    public function laporan($laporan_status = null)
    {

        if ($laporan_status) {
            $status_laporan = $laporan_status;
        } else {
            $status_laporan = "laporan";
        }

        $sql = "SELECT year(`tgl_po`) as tahun_neraca FROM `tbl_pembelian` GROUP by year(`tgl_po`) order by  `tgl_po` DESC";

        $tahun_labarugi_data = $this->db->query($sql)->result();

        $sql = "SELECT year(`tgl_po`) as tahun_neraca, month(`tgl_po`) as bulan_neraca FROM `tbl_pembelian` GROUP by year(`tgl_po`), month(`tgl_po`) order by `tgl_po` DESC;";

        $bulan_neraca_labarugi_data = $this->db->query($sql)->result();

        $start = 0;
        $data = array(
            'Tbl_TAHUN_labarugi_data' => $tahun_labarugi_data,
            'Tbl_BULAN_labarugi_data' => $bulan_neraca_labarugi_data,
            'start' => $start,
            'status_laporan' => $status_laporan,
            'action_input_labarugi_baru' => site_url('Tbl_laba_rugi/labarugi_form_input/'),
            'action_input_labarugi_baru_bulanan' => site_url('Tbl_laba_rugi/labarugi_form_input_bulanan/'),
        );

        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_laba_rugi/adminlte310_tbl_labarugi_data_list', $data);
    }

    public function labarugi_form($Get_tahun = null, $Get_bulan = null)
    {

        // print_r("labarugi_form");
        // print_r("<br/>");
        // print_r($Get_tahun);
        // print_r("<br/>");
        // print_r($Get_bulan);
        // print_r("<br/>");
        // die;

        if ($Get_bulan) {
            // Neraca bulanan
            // print_r("Bulanan");
            // print_r("<br/>");
            $sql = "SELECT * FROM `tbl_laba_rugi` WHERE `tahun_transaksi`='$Get_tahun' And `bulan_transaksi`='$Get_bulan' ";
            $GET_tbl_laba_rugi_RECORD = $this->db->query($sql);


            if ($GET_tbl_laba_rugi_RECORD->num_rows() > 0) {
                $data = array(
                    'button' => 'Update',
                    'action' => site_url('Tbl_laba_rugi/update_labarugi_data/'  . $GET_tbl_laba_rugi_RECORD->row()->id),
                    'id' => set_value('id'),
                    // 'data_detail' => $data_detail,
                    'data_tbl_laba_rugi' => $GET_tbl_laba_rugi_RECORD->row(),
                    'uuid_data_laba_rugi' => $GET_tbl_laba_rugi_RECORD->row()->uuid_data_laba_rugi,
                    'tahun_neraca' => $Get_tahun,
                    'bulan_transaksi' => $Get_bulan,
                );
            } else {
                $data = array(
                    'button' => 'Simpan',
                    'action' => site_url('Tbl_laba_rugi/create_action_labarugi_NEW/' . $Get_tahun . '/' . $Get_bulan),
                    'id' => set_value('id'),
                    // 'data_detail' => $data_detail,
                    // 'uuid_data_laba_rugi' => "",
                    'tahun_neraca' => $Get_tahun,
                    'bulan_transaksi' => $Get_bulan,
                );
            }
        } else {
            // Neraca tahunan
            // print_r("Tahunan");
            // print_r("<br/>");

            $sql = "SELECT * FROM `tbl_laba_rugi` WHERE `tahun_transaksi`='$Get_tahun' And `bulan_transaksi`='0' ";

            $GET_tbl_laba_rugi_RECORD = $this->db->query($sql);

            // print_r("GET_tbl_laba_rugi_RECORD");
            // print_r("<br/>");
            // print_r($GET_tbl_laba_rugi_RECORD);
            // print_r("<br/>");
            // print_r($GET_tbl_laba_rugi_RECORD->num_rows());
            // print_r("<br/>");
            // print_r($GET_tbl_laba_rugi_RECORD->row());
            // print_r("<br/>");
            // print_r("<br/>");
            // print_r("<br/>");
            // die;


            if ($GET_tbl_laba_rugi_RECORD->num_rows() > 0) {
                $data = array(
                    'button' => 'Update',
                    'action' => site_url('Tbl_laba_rugi/update_labarugi_data/'  . $GET_tbl_laba_rugi_RECORD->row()->id),
                    'id' => set_value('id'),
                    // 'data_detail' => $data_detail,
                    'data_tbl_laba_rugi' => $GET_tbl_laba_rugi_RECORD->row(),
                    'uuid_data_laba_rugi' => $GET_tbl_laba_rugi_RECORD->row()->uuid_data_laba_rugi,
                    'tahun_neraca' => $Get_tahun,
                    'bulan_transaksi' => 0,
                );

                // print_r($data);
                // print_r("<br/>");
                // die;


            } else {
                $data = array(
                    'button' => 'Simpan',
                    'action' => site_url('Tbl_laba_rugi/create_action_labarugi_NEW/' . $Get_tahun . '/0'),
                    'id' => set_value('id'),
                    // 'data_detail' => $data_detail,
                    // 'uuid_data_laba_rugi' => "",
                    'tahun_neraca' => $Get_tahun,
                    'bulan_transaksi' => 0,
                );
            }
        }

        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_laba_rugi/adminlte310_labarugi_form', $data);
    }


    public function create_action_labarugi_NEW($Get_tahun = null, $Get_bulan = null, $data_name = null)
    {
        // print_r("create_action_labarugi_NEW");
        // print_r("<br/>");
        // print_r($Get_tahun);
        // print_r("<br/>");
        // print_r($Get_bulan);
        // print_r("<br/>");
        // print_r($data_name);
        // print_r("<br/>");
        // print_r($this->input->post('input_box', TRUE));
        // print_r("<br/>");

        // die;

        // CEK JIKA BELUM ADA RECORD PADA TAHUN DAN BULAN YANG TERPILIH , MAKA BUAT 1 RECORD BARU


        if ($Get_bulan) {
            $sql = "SELECT * FROM `tbl_laba_rugi` WHERE `tahun_transaksi`='$Get_tahun' And `bulan_transaksi`='$Get_bulan' ";
            $GET_tbl_laba_rugi_RECORD = $this->db->query($sql);
        } else {
            $Get_bulan = 0;
            $sql = "SELECT * FROM `tbl_laba_rugi` WHERE `tahun_transaksi`='$Get_tahun' And `bulan_transaksi`='$Get_bulan' ";
            $GET_tbl_laba_rugi_RECORD = $this->db->query($sql);
        }


        if ($GET_tbl_laba_rugi_RECORD->num_rows() > 0) {
            // Proses update dan isi data

            $data = array(
                $data_name => str_replace('.', '', $this->input->post('input_box', TRUE)),
            );

            // print_r($data);
            // die;

            $this->Tbl_laba_rugi_model->update($GET_tbl_laba_rugi_RECORD->row()->id, $data);
        } else {
            // proses buat record baru dan isi data

            $data = array(
                'date_input' => date("Y-m-d H:i:s"),
                'date_transaksi' => date("Y-m-d H:i:s"),
                'tahun_transaksi' => $Get_tahun,
                'bulan_transaksi' => $Get_bulan,

            );

            // print_r($data);
            // print_r("<br/>");
            // die;

            $Get_id_RECORD_NEW = $this->Tbl_laba_rugi_model->insert($data);


            // print_r($Get_tahun);
            // print_r("<br/>");
            // print_r("BUlan: ");
            // print_r($Get_bulan);
            // print_r("<br/>");
            // print_r("id: ");
            // print_r($Get_id_RECORD_NEW);
            // print_r("<br/>");
            // print_r($this->input->post('input_box', TRUE));
            // print_r("<br/>");
            // // die;


            $data = array(
                $data_name => str_replace('.', '', $this->input->post('input_box', TRUE)),
            );

            // print_r($data);
            // die;

            $this->Tbl_laba_rugi_model->update($Get_id_RECORD_NEW, $data);
        }

        redirect(site_url('Tbl_laba_rugi/labarugi_form/' . $Get_tahun . '/' . $Get_bulan));
    }


    public function update_labarugi_data($Get_id_RECORD = null, $data_name = null)
    {

        // $data_name = str_replace('.', '', $this->input->post('input_box', TRUE));
        // print_r($data_name);
        // print_r("<br/>");
        // $data_name = str_replace(',', '.', $this->input->post('input_box', TRUE));
        // print_r($data_name);
        // print_r("<br/>");
        // $data_name = $data_name + $data_name;
        // print_r($data_name);
        // print_r("<br/>");

        // print_r("<br/>");
        // print_r("<br/>");

        // print_r("update_labarugi_data");
        // print_r("<br/>");
        // print_r($Get_id_RECORD);
        // print_r("<br/>");
        // print_r($data_name);
        // print_r("<br/>");
        // die;

        $sql = "SELECT * FROM `tbl_laba_rugi` WHERE `id`='$Get_id_RECORD'";

        $GET_tbl_laba_rugi_RECORD = $this->db->query($sql);

        // print_r($GET_tbl_laba_rugi_RECORD->row());
        // die;

        if ($GET_tbl_laba_rugi_RECORD->num_rows() > 0) {

            // print_r("ada record");
            // print_r("<br/>");

            $data_input_box = str_replace('.', '', $this->input->post('input_box', TRUE));

            // print_r($data_name);
            // print_r("<br/>");

            $data_input_box = str_replace(',', '.', $data_input_box);

            // print_r($data_input_box);            
            // print_r("<br/>");
            // die;




            $data = array(
                $data_name => $data_input_box,
            );

            // print_r($data);
            // die;

            $this->Tbl_laba_rugi_model->update($Get_id_RECORD, $data);
        }


        // Kembali ke form neraca
        $sql = "SELECT * FROM `tbl_laba_rugi` WHERE `id`='$Get_id_RECORD' ";

        $GET_tbl_laba_rugi_RECORD = $this->db->query($sql);

        // print_r($GET_tbl_laba_rugi_RECORD->row());
        // die;



        if ($GET_tbl_laba_rugi_RECORD->num_rows() > 0) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('Tbl_laba_rugi/update_labarugi_data/'  . $Get_id_RECORD),
                'id' => set_value('id'),
                // 'data_detail' => $data_detail,
                'data_tbl_laba_rugi' => $GET_tbl_laba_rugi_RECORD->row(),
                'uuid_data_laba_rugi' => $GET_tbl_laba_rugi_RECORD->row()->uuid_data_laba_rugi,
                'tahun_neraca' => $GET_tbl_laba_rugi_RECORD->row()->tahun_transaksi,
                'bulan_transaksi' => $GET_tbl_laba_rugi_RECORD->row()->bulan_transaksi,
            );

            // print_r($data);
            // print_r("<br/>");
            // die;

        } else {
            $data = array(
                'button' => 'Simpan',
                'action' => site_url('Tbl_laba_rugi/create_action_labarugi_NEW/' . $GET_tbl_laba_rugi_RECORD->row()->tahun_transaksi . '/' . $GET_tbl_laba_rugi_RECORD->row()->bulan_transaksi),
                'id' => set_value('id'),
                // 'data_detail' => $data_detail,
                // 'uuid_data_laba_rugi' => "",
                'tahun_neraca' => $GET_tbl_laba_rugi_RECORD->row()->tahun_transaksi,
                'bulan_transaksi' => $GET_tbl_laba_rugi_RECORD->row()->bulan_transaksi,
            );
        }


        // $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_laba_rugi/adminlte310_labarugi_form', $data);

        // print_r($GET_tbl_laba_rugi_RECORD->row()->tahun_transaksi);
        // print_r("<br/>");
        // print_r($GET_tbl_laba_rugi_RECORD->row()->bulan_transaksi);
        // print_r("<br/>");
        // die;
        redirect(site_url('Tbl_laba_rugi/labarugi_form/' . $GET_tbl_laba_rugi_RECORD->row()->tahun_transaksi . '/' . $GET_tbl_laba_rugi_RECORD->row()->bulan_transaksi));
    }




    public function labarugi_form_input()
    {
        print_r("labarugi_form_input");
        die;
    }

    public function labarugi_form_input_bulanan()
    {
        print_r("labarugi_form_input_bulanan");
        die;
    }


    public function index_X()
    {

        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        if ($q <> '') {
            $config['base_url'] = base_url() . 'tbl_laba_rugi/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'tbl_laba_rugi/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'tbl_laba_rugi/index.html';
            $config['first_url'] = base_url() . 'tbl_laba_rugi/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Tbl_laba_rugi_model->total_rows($q);
        $tbl_laba_rugi = $this->Tbl_laba_rugi_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'tbl_laba_rugi_data' => $tbl_laba_rugi,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->load->view('tbl_laba_rugi/tbl_laba_rugi_list', $data);
    }



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
                    'uuid_data_laba_rugi' => $GET_tbl_laba_rugi_RECORD->row()->uuid_data_laba_rugi,
                    'tahun_neraca' => $Get_tahun,
                    'bulan_transaksi' => $Get_bulan,
                );
            } else {
                $data = array(
                    'button' => 'Simpan',
                    'action' => site_url('Laporan/create_action_labarugi_NEW/' . $Get_tahun . '/' . $Get_bulan),
                    'id' => set_value('id'),
                    // 'data_detail' => $data_detail,
                    // 'uuid_data_laba_rugi' => "",
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
                    'uuid_data_laba_rugi' => $GET_tbl_laba_rugi_RECORD->row()->uuid_data_laba_rugi,
                    'tahun_neraca' => $Get_tahun,
                    'bulan_transaksi' => 0,
                );
            } else {
                $data = array(
                    'button' => 'Simpan',
                    'action' => site_url('Laporan/create_action_labarugi_NEW/' . $Get_tahun . '/' . $Get_bulan),
                    'id' => set_value('id'),
                    // 'data_detail' => $data_detail,
                    // 'uuid_data_laba_rugi' => "",
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

    public function labarugi_print($Get_tahun = null, $Get_bulan = null)
    {



        if ($Get_bulan) {
            $sql = "SELECT * FROM `tbl_laba_rugi` WHERE `tahun_transaksi`='$Get_tahun' And `bulan_transaksi`='$Get_bulan' ";
            $data_detail = $this->db->query($sql)->row();
        } else {
            $Get_bulan = 0;
            $sql = "SELECT * FROM `tbl_laba_rugi` WHERE `tahun_transaksi`='$Get_tahun' And `bulan_transaksi`='$Get_bulan' ";
            $data_detail = $this->db->query($sql)->row();
            // $Get_bulan=0;
        }

        // print_r($data_detail);
        // die;

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
            // 'id' => set_value('id'),

            'data_tbl_laba_rugi' => $data_detail,
            'tahun_laba_rugi' => $data_detail->tahun_transaksi,
            'bulan_laba_rugi' => $data_detail->bulan_transaksi,

        );

        // print_r($data);
        // die;

        // 2.C. MENAMPILKAN FILE DATA
        // $data = array_merge($data);
        $html = $this->load->view('anekadharma/tbl_laba_rugi/adminlte310_labarugi_cetak.php', $data, true);

        // 2.d. CONVERT TAMPILAN FILE DATA MENJADI FILE PDF
        $this->pdf->loadHtml($html);
        $this->pdf->render();

        $this->pdf->stream("CETAK_LABA_RUGI.pdf", array("Attachment" => 0));
    }


    public function read($id)
    {
        $row = $this->Tbl_laba_rugi_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'date_input' => $row->date_input,
                'date_transaksi' => $row->date_transaksi,
                'tahun_transaksi' => $row->tahun_transaksi,
                'bulan_transaksi' => $row->bulan_transaksi,
                'uuid_data_laba_rugi' => $row->uuid_data_laba_rugi,
            );
            $this->load->view('tbl_laba_rugi/tbl_laba_rugi_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_laba_rugi'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('tbl_laba_rugi/create_action'),
            'id' => set_value('id'),
            'date_input' => set_value('date_input'),
            'date_transaksi' => set_value('date_transaksi'),
            'tahun_transaksi' => set_value('tahun_transaksi'),
            'bulan_transaksi' => set_value('bulan_transaksi'),
            'uuid_data_laba_rugi' => set_value('uuid_data_laba_rugi'),
        );
        $this->load->view('tbl_laba_rugi/tbl_laba_rugi_form', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'date_input' => $this->input->post('date_input', TRUE),
                'date_transaksi' => $this->input->post('date_transaksi', TRUE),
                'tahun_transaksi' => $this->input->post('tahun_transaksi', TRUE),
                'bulan_transaksi' => $this->input->post('bulan_transaksi', TRUE),
                'uuid_data_laba_rugi' => $this->input->post('uuid_data_laba_rugi', TRUE),
            );

            $this->Tbl_laba_rugi_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('tbl_laba_rugi'));
        }
    }

    public function update($id)
    {
        $row = $this->Tbl_laba_rugi_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('tbl_laba_rugi/update_action'),
                'id' => set_value('id', $row->id),
                'date_input' => set_value('date_input', $row->date_input),
                'date_transaksi' => set_value('date_transaksi', $row->date_transaksi),
                'tahun_transaksi' => set_value('tahun_transaksi', $row->tahun_transaksi),
                'bulan_transaksi' => set_value('bulan_transaksi', $row->bulan_transaksi),
                'uuid_data_laba_rugi' => set_value('uuid_data_laba_rugi', $row->uuid_data_laba_rugi),
            );
            $this->load->view('tbl_laba_rugi/tbl_laba_rugi_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_laba_rugi'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'date_input' => $this->input->post('date_input', TRUE),
                'date_transaksi' => $this->input->post('date_transaksi', TRUE),
                'tahun_transaksi' => $this->input->post('tahun_transaksi', TRUE),
                'bulan_transaksi' => $this->input->post('bulan_transaksi', TRUE),
                'uuid_data_laba_rugi' => $this->input->post('uuid_data_laba_rugi', TRUE),
            );

            $this->Tbl_laba_rugi_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('tbl_laba_rugi'));
        }
    }

    public function delete($id)
    {
        $row = $this->Tbl_laba_rugi_model->get_by_id($id);

        if ($row) {
            $this->Tbl_laba_rugi_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('tbl_laba_rugi'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_laba_rugi'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('date_input', 'date input', 'trim|required');
        $this->form_validation->set_rules('date_transaksi', 'date transaksi', 'trim|required');
        $this->form_validation->set_rules('tahun_transaksi', 'tahun transaksi', 'trim|required');
        $this->form_validation->set_rules('bulan_transaksi', 'bulan transaksi', 'trim|required');
        $this->form_validation->set_rules('uuid_data_laba_rugi', 'uuid data neraca', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
}

/* End of file Tbl_laba_rugi.php */
/* Location: ./application/controllers/Tbl_laba_rugi.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2025-06-17 02:28:51 */
/* http://harviacode.com */