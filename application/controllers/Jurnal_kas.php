<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jurnal_kas extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model(array('Jurnal_kas_model', 'Buku_besar_model', 'Jurnal_kas_saldo_akhir_bulan_model'));
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    public function index()
    {

        // $Data_kas = $this->Jurnal_kas_model->get_all();
        // $start = 0;

        // print_r($Data_kas);


        $Get_date_awal = date("Y-m-1 00:00:00");
        // print_r($Get_date_awal);
        // print_r("<br/>");


        $Get_date_akhir = date("Y-m-t 23:59:59"); // TANGGAL AKHIR BULAN -t
        $Get_month_akhir = date("m"); // TANGGAL AKHIR BULAN -t
        // print_r($Get_month);
        // print_r("<br/>");

        // die;



        $sql = "SELECT * FROM `jurnal_kas` WHERE `tanggal` between '$Get_date_awal' and '$Get_date_akhir' ORDER BY `tanggal`,`id` DESC";

        $Data_kas = $this->db->query($sql)->result();



        $data = $this->_jurnal_kas_view_data(array(
            'Data_kas' => $Data_kas,
            'date_awal' => $Get_date_awal,
            'date_akhir' => $Get_date_akhir,
            'month_akhir' => $Get_month_akhir,
            'month_selected' => date("m"),
            'year_selected' => date("Y"),
        ));

        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/jurnal_kas/adminlte310_jurnal_kas_list', $data);
    }


    public function cari_between_date($Tahun_selected = null, $Bulan_selected = null)
    {

        if ($Bulan_selected) {
            $Get_month_selected = $Bulan_selected;
            $Get_YEAR_selected = $Tahun_selected;
        } else {
            $Get_month_selected = date("m", strtotime($this->input->post('bulan_ns', TRUE)));
            $Get_YEAR_selected = date("Y", strtotime($this->input->post('bulan_ns', TRUE)));
        }

        $sql = "SELECT * FROM `jurnal_kas` WHERE MONTH(`tanggal`)=$Get_month_selected AND YEAR(`tanggal`)=$Get_YEAR_selected ORDER BY `tanggal`,`id`";

        $Data_kas = $this->db->query($sql)->result();

        // print_r($Data_kas);
        // die;


        // ------------------------------------------------------------------------------

        // if (date("Y", strtotime($this->input->post('tgl_awal', TRUE))) < 2020) {
        //     // $Get_date_awal = date("Y-m-d 00:00:00");
        //     $Get_date_awal = date('Y-m-d', strtotime('-1 day'));
        // } else {
        //     $Get_date_awal = date("Y-m-d 23:59:59", strtotime($this->input->post('tgl_awal', TRUE)));
        // }

        // if (date("Y", strtotime($this->input->post('tgl_akhir', TRUE))) < 2020) {
        //     $Get_date_akhir = date("Y-m-d 00:00:00");
        //     $Get_month_akhir = date("m");
        // } else {
        //     $Get_date_akhir = date("Y-m-d 23:59:59", strtotime($this->input->post('tgl_akhir', TRUE)));
        //     $Get_month_akhir = date("m", strtotime($this->input->post('tgl_akhir', TRUE))); // TANGGAL AKHIR BULAN -t
        // }





        // $sql = "SELECT * FROM `jurnal_kas` WHERE `tanggal` between '$Get_date_awal' and '$Get_date_akhir' ORDER BY `tanggal`,`id`";

        // $Data_kas = $this->db->query($sql)->result();

        // GET SALDO BULAN LALU
        // print_r($Bulan_selected);
        // die;
        // if ($Bulan_selected > 1) {
        //     // $Get_Tanggal_saldo_bulan_lalu = date("Y-m-01", strtotime($this->input->post('bulan_ns', TRUE)));

        //     $sql = "SELECT * FROM `jurnal_kas_saldo_akhir_bulan` WHERE MONTH(`tanggal`)=$Get_month_selected AND YEAR(`tanggal`)=$Get_YEAR_selected ORDER BY `tanggal`,`id`";

        //     $Data_SALDO = $this->db->query($sql)->row();

        //     print_r($Data_SALDO);
        // } else {

        //     print_r("desember tahun lalu");
        // }

        // die;

        $data = $this->_jurnal_kas_view_data(array(
            'Data_kas' => $Data_kas,
            'date_awal' => isset($Get_date_awal) ? $Get_date_awal : date("Y-m-1 00:00:00"),
            'date_akhir' => isset($Get_date_akhir) ? $Get_date_akhir : date("Y-m-t 23:59:59"),
            'month_akhir' => isset($Get_month_akhir) ? $Get_month_akhir : $Get_month_selected,
            'month_selected' => $Get_month_selected,
            'year_selected' => $Get_YEAR_selected,
        ));

        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/jurnal_kas/adminlte310_jurnal_kas_list', $data);
    }

    public function Jurnal_penerimaan_kas()
    {

        $Get_date_awal = date("Y-m-1 00:00:00");
        // print_r($Get_date_awal);
        // print_r("<br/>");

        $Get_date_akhir = date("Y-m-t 23:59:59"); // TANGGAL AKHIR BULAN -t
        $Get_month_akhir = date("m"); // TANGGAL AKHIR BULAN -t
        // print_r($Get_month);
        // print_r("<br/>");

        // die;



        $sql_kas_penerimaan = "SELECT * FROM `jurnal_kas` WHERE `tanggal` between '$Get_date_awal' AND '$Get_date_akhir' AND `debet`>0 order by `pl`,`tanggal`,`id` ASC";

        // $sql_kas_penerimaan = "SELECT * FROM `jurnal_kas` WHERE `debet`>0 order by `pl`,`tanggal`,`id` ASC";

        $Data_kas = $this->db->query($sql_kas_penerimaan)->result();

        $data = $this->_penerimaan_kas_view_data(array(
            'Data_kas' => $Data_kas,
            'date_awal' => $Get_date_awal,
            'date_akhir' => $Get_date_akhir,
            'month_akhir' => $Get_month_akhir,
        ));
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/jurnal_kas/adminlte310_penerimaan_kas', $data);
    }


    public function Jurnal_penerimaan_kas_cari_between_date()
    {

        if (date("Y", strtotime($this->input->post('tgl_awal', TRUE))) < 2020) {
            // $Get_date_awal = date("Y-m-d 00:00:00");
            $Get_date_awal = date('Y-m-d', strtotime('-1 day'));
        } else {
            $Get_date_awal = date("Y-m-d 23:59:59", strtotime($this->input->post('tgl_awal', TRUE)));
        }

        if (date("Y", strtotime($this->input->post('tgl_akhir', TRUE))) < 2020) {
            $Get_date_akhir = date("Y-m-d 00:00:00");
            $Get_month_akhir = date("m");
        } else {
            $Get_date_akhir = date("Y-m-d 23:59:59", strtotime($this->input->post('tgl_akhir', TRUE)));
            $Get_month_akhir = date("m", strtotime($this->input->post('tgl_akhir', TRUE))); // TANGGAL AKHIR BULAN -t
        }



        $sql_kas_penerimaan = "SELECT * FROM `jurnal_kas` WHERE `tanggal` between '$Get_date_awal' AND '$Get_date_akhir' AND `debet`>0 order by `pl`,`tanggal`,`id` ASC";

        // $sql_kas_penerimaan = "SELECT * FROM `jurnal_kas` WHERE `debet`>0 order by `pl`,`tanggal`,`id` ASC";

        $Data_kas = $this->db->query($sql_kas_penerimaan)->result();


        $data = $this->_penerimaan_kas_view_data(array(
            'Data_kas' => $Data_kas,
            'date_awal' => $Get_date_awal,
            'date_akhir' => $Get_date_akhir,
            'month_akhir' => $Get_month_akhir,
        ));

        // print_r($data);

        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/jurnal_kas/adminlte310_penerimaan_kas', $data);
    }



    public function Jurnal_pengeluaran_kas()
    {


        $Get_date_awal = date("Y-m-1 00:00:00");
        // print_r($Get_date_awal);
        // print_r("<br/>");

        $Get_date_akhir = date("Y-m-t 23:59:59"); // TANGGAL AKHIR BULAN -t
        $Get_month_akhir = date("m"); // TANGGAL AKHIR BULAN -t
        // print_r($Get_month);
        // print_r("<br/>");

        // die;



        $sql_kas_pengeluaran = "SELECT * FROM `jurnal_kas` WHERE `tanggal` between '$Get_date_awal' AND '$Get_date_akhir' AND `kredit`>0 order by `pl`,`tanggal`,`id` ASC";

        // $sql_kas_pengeluaran = "SELECT * FROM `jurnal_kas` WHERE `kredit`>0 order by `pl`,`tanggal`,`id` ASC";

        $Data_kas = $this->db->query($sql_kas_pengeluaran)->result();

        $data = $this->_pengeluaran_kas_view_data(array(
            'Data_kas' => $Data_kas,
            'date_awal' => $Get_date_awal,
            'date_akhir' => $Get_date_akhir,
            'month_akhir' => $Get_month_akhir,
        ));
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/jurnal_kas/adminlte310_pengeluaran_kas', $data);
    }

    public function Jurnal_pengeluaran_kas_cari_between_date()
    {

        if (date("Y", strtotime($this->input->post('tgl_awal', TRUE))) < 2020) {
            // $Get_date_awal = date("Y-m-d 00:00:00");
            $Get_date_awal = date('Y-m-d', strtotime('-1 day'));
        } else {
            $Get_date_awal = date("Y-m-d 23:59:59", strtotime($this->input->post('tgl_awal', TRUE)));
        }

        if (date("Y", strtotime($this->input->post('tgl_akhir', TRUE))) < 2020) {
            $Get_date_akhir = date("Y-m-d 00:00:00");
            $Get_month_akhir = date("m");
        } else {
            $Get_date_akhir = date("Y-m-d 23:59:59", strtotime($this->input->post('tgl_akhir', TRUE)));
            $Get_month_akhir = date("m", strtotime($this->input->post('tgl_akhir', TRUE))); // TANGGAL AKHIR BULAN -t
        }



        $sql_kas_pengeluaran = "SELECT * FROM `jurnal_kas` WHERE `tanggal` between '$Get_date_awal' AND '$Get_date_akhir' AND `kredit`>0 order by `pl`,`tanggal`,`id` ASC";

        $Data_kas = $this->db->query($sql_kas_pengeluaran)->result();

        $data = $this->_pengeluaran_kas_view_data(array(
            'Data_kas' => $Data_kas,
            'date_awal' => $Get_date_awal,
            'date_akhir' => $Get_date_akhir,
            'month_akhir' => $Get_month_akhir,
        ));

        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/jurnal_kas/adminlte310_pengeluaran_kas', $data);
    }



    public function ubah_kode_akun_pengeluaran($uuid_jurnal_kas = null)
    {

        $data_per_uuidjurnal = $this->Jurnal_kas_model->get_by_uuid_jurnal_kas($uuid_jurnal_kas);

        $data = array(
            'Data_kas' => $data_per_uuidjurnal,
            'action' => site_url('Jurnal_kas/update_kode_akun/' . $uuid_jurnal_kas  . '/pengeluaran'),
            'button' => 'Update Kode AKun',
            'get_kode_akun' => $get_kode_akun,
        );

        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/jurnal_kas/adminlte310_pengeluaran_kas_per_uuid_jurnal_kas', $data);
    }

    public function ubah_kode_akun_penerimaan($uuid_jurnal_kas = null)
    {

        // print_r("ubah_kode_akun_penerimaan");
        // die;






        $data_per_uuidjurnal = $this->Jurnal_kas_model->get_by_uuid_jurnal_kas($uuid_jurnal_kas);

        // print_r($data_per_uuidjurnal);
        // print_r("<br/>");
        // print_r("<br/>");

        // $Tbl_pembelian = $this->Jurnal_kas_model->get_by_spop($data_per_uuidjurnal->uuid_jurnal_kas);

        // $sql = "SELECT `uuid_jurnal_kas`,`kode_akun` FROM `jurnal_kas` WHERE `uuid_jurnal_kas`='$uuid_jurnal_kas' GROUP by `uuid_jurnal_kas`,`kode_akun`";

        // $get_kode_akun = $this->db->query($sql)->row()->kode_akun;

        // print_r($data_per_uuidjurnal);
        // print_r("<br/>");
        // print_r("<br/>");


        $data = array(
            'Data_kas' => $data_per_uuidjurnal,
            // 'spop' => $data_per_uuidjurnal->spop,
            'action' => site_url('Jurnal_kas/update_kode_akun/' . $uuid_jurnal_kas . '/penerimaan'),
            'button' => 'Update Kode AKun',
            'get_kode_akun' => $get_kode_akun,
        );

        // print_r($data['Data_kas']);
        // print_r("<br/>");
        // die;

        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/jurnal_kas/adminlte310_penerimaan_kas_per_uuid_jurnal_kas', $data);
    }

    public function update_kode_akun($uuid_jurnal_kas = null, $proses_form = null)
    {

        // print_r("update_kode_akun_penerimaan");
        // print_r("<br/>");
        // print_r($uuid_jurnal_kas);
        // print_r("<br/>");
        // die;
        // Get Id penerimaaan kas



        // ============ SETTING KODE AKUN UNTUK PENERIMAAN KAS KE BUKU BESAR =======



        // ============ END OF SETTING KODE AKUN UNTUK PENERIMAAN KAS KE BUKU BESAR =======


        $sql_kas_penerimaan = "SELECT * FROM `jurnal_kas` WHERE `uuid_jurnal_kas`='$uuid_jurnal_kas'";

        $Data_jurnal_penerimaan_kas = $this->db->query($sql_kas_penerimaan)->row();

        // print_r($Data_jurnal_penerimaan_kas->debet);
        // die;

        // ---------------------------------------------------

        $GET_tanggal_Jurnal_kas = date("Y-m-d H:i:s", strtotime($Data_jurnal_penerimaan_kas->tanggal, TRUE));

        if ($Data_jurnal_penerimaan_kas->id_buku_besar) {
        } else {
            $GET_ID_buku_besar = $Data_jurnal_penerimaan_kas->id_buku_besar;
        }


        // ---------------------------------------------------





        if ($Data_jurnal_penerimaan_kas->id_buku_besar or $Data_jurnal_penerimaan_kas->id_buku_besar > 0) {
            // print_r("ada ID");
            // proses update di tabel buku besar

            if ($proses_form == "penerimaan") {

                $data = array(
                    // 'uuid_buku_besar' => $this->input->post('uuid_buku_besar', TRUE),
                    'tanggal' => $GET_tanggal_Jurnal_kas,
                    'kode_akun' => $this->input->post('kode_akun', TRUE),
                    'keterangan' => "Jurnal Kas " . $Data_jurnal_penerimaan_kas->uuid_jurnal_kas,
                    // 'pl' => $this->input->post('kode_pl', TRUE),
                    // 'kode' => $this->input->post('kode_bb', TRUE),
                    'debet' => $Data_jurnal_penerimaan_kas->debet,
                    // 'kredit' => $GET_TOTAL_PENJUALAN,
                    // 'saldo' => $this->input->post('saldo', TRUE),
                );
            } else {
                $data = array(
                    // 'uuid_buku_besar' => $this->input->post('uuid_buku_besar', TRUE),
                    'tanggal' => $GET_tanggal_Jurnal_kas,
                    'kode_akun' => $this->input->post('kode_akun', TRUE),
                    'keterangan' => "Jurnal Kas " . $Data_jurnal_penerimaan_kas->uuid_jurnal_kas,
                    // 'pl' => $this->input->post('kode_pl', TRUE),
                    // 'kode' => $this->input->post('kode_bb', TRUE),
                    // 'debet' => $Data_jurnal_penerimaan_kas->debet,
                    'kredit' => $Data_jurnal_penerimaan_kas->kredit,
                    // 'saldo' => $this->input->post('saldo', TRUE),
                );
            }

            $this->Buku_besar_model->update($GET_ID_buku_besar, $data);
        } else {
            // print_r("TIDAK ADA ada ID");
            // Insert data baru di tabel buku besar

            if ($proses_form == "penerimaan") {
                $data = array(
                    // 'uuid_buku_besar' => $this->input->post('uuid_buku_besar', TRUE),
                    'tanggal' => $GET_tanggal_Jurnal_kas,
                    'kode_akun' => $this->input->post('kode_akun', TRUE),
                    'keterangan' => "Jurnal Kas " . $Data_jurnal_penerimaan_kas->uuid_jurnal_kas,
                    // 'pl' => $this->input->post('kode_pl', TRUE),
                    // 'kode' => $this->input->post('kode_bb', TRUE),
                    'debet' => $Data_jurnal_penerimaan_kas->debet,
                    // 'kredit' => $GET_TOTAL_PENJUALAN,
                    // 'saldo' => $this->input->post('saldo', TRUE),
                );
            } else {
                $data = array(
                    // 'uuid_buku_besar' => $this->input->post('uuid_buku_besar', TRUE),
                    'tanggal' => $GET_tanggal_Jurnal_kas,
                    'kode_akun' => $this->input->post('kode_akun', TRUE),
                    'keterangan' => "Jurnal Kas " . $Data_jurnal_penerimaan_kas->uuid_jurnal_kas,
                    // 'pl' => $this->input->post('kode_pl', TRUE),
                    // 'kode' => $this->input->post('kode_bb', TRUE),
                    // 'debet' => $Data_jurnal_penerimaan_kas->debet,
                    'kredit' => $Data_jurnal_penerimaan_kas->kredit,
                    // 'saldo' => $this->input->post('saldo', TRUE),
                );
            }

            $GET_id_buku_besar = $this->Buku_besar_model->insert($data);
        }




        // print_r($Data_jurnal_penerimaan_kas);
        // print_r("<br/>");
        // print_r($Data_jurnal_penerimaan_kas->id);
        // print_r("<br/>");
        // print_r("<br/>");
        // print_r("<br/>");


        $data = array(
            'kode_akun' => $this->input->post('kode_akun', TRUE),
            'id_buku_besar' => $GET_id_buku_besar,
        );

        // print_r($data);
        // die;

        $this->Jurnal_kas_model->update_kode_akun_per_uuid_jurnal_kas($Data_jurnal_penerimaan_kas->id, $data);

        if ($proses_form == "penerimaan") {
            redirect(site_url('Jurnal_kas/Jurnal_penerimaan_kas'));
        } else {
            redirect(site_url('Jurnal_kas/Jurnal_pengeluaran_kas'));
        }
    }



    public function index_server_side()
    {
        $this->load->view('anekadharma/jurnal_kas/jurnal_kas_list');
    }

    public function json()
    {
        header('Content-Type: application/json');
        echo $this->Jurnal_kas_model->json();
    }


    public function pemasukan_kas()
    {

        // $Data_kas = $this->Jurnal_kas_model->get_all();

        $data = array(
            'button' => 'Simpan',
            'action' => site_url('Jurnal_kas/pemasukan_kas_action'),
            'nomor' => set_value('nomor'),
            'tanggal' => set_value('tanggal'),
            'bukti' => set_value('bukti'),
            'pl' => set_value('pl'),
            'keterangan' => set_value('keterangan'),
            'kode_rekening' => set_value('kode_rekening'),
            'debet' => set_value('debet'),
            'kredit' => set_value('kredit'),
            // 'Data_kas' => $Data_kas,
        );
        // $this->load->view('Jurnal_kas/Jurnal_kas_form', $data);
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/jurnal_kas/adminlte310_jurnal_kas_form_pemasukan', $data);
    }

    public function pemasukan_kas_action()
    {
        $this->_rules_pemasukan();

        if ($this->form_validation->run() == FALSE) {
            $this->pemasukan_kas();
        } else {

            if (date("Y", strtotime($this->input->post('tanggal', TRUE))) < 2020) {
                $date_jurnal_kas = date("Y-m-d H:i:s");
            } else {
                $date_jurnal_kas = date("Y-m-d H:i:s", strtotime($this->input->post('tanggal', TRUE)));
            }

            // unIT
            $this->db->where('uuid_unit', $this->input->post('uuid_unit', TRUE));
            $sys_unit_data = $this->db->get('sys_unit');



            if ($sys_unit_data->num_rows() > 0) {

                $Get_unit_data = $sys_unit_data->row_array();

                // $Get_uuid_unit = $this->input->post('uuid_unit', TRUE);
                $Get_kode_unit = $Get_unit_data['kode_unit'];
                // $Get_nama_unit = $Get_unit_data['nama_unit'];
            }


            // print_r($this->input->post('uuid_unit', TRUE));
            // print_r("<br/>");
            // print_r($sys_unit_data);
            // print_r("<br/>");
            // print_r("<br/>");
            // print_r($Get_unit_data['kode_unit']);
            // print_r("<br/>");
            // print_r("<br/>");
            // print_r($Get_kode_unit);
            // print_r("<br/>");

            // die;

            if ($this->input->post('bukti', TRUE) == "BKM") {
                $data = array(
                    'tanggal' => $date_jurnal_kas,
                    'bukti' => $this->input->post('bukti', TRUE),
                    'pl' => $this->input->post('kode_pl', TRUE),
                    'keterangan' => $this->input->post('keterangan', TRUE),
                    // 'kode_rekening' => $this->input->post('kode_rekening', TRUE),
                    'uuid_unit' => $this->input->post('uuid_unit', TRUE),
                    'kode_unit' => $Get_kode_unit,
                    'debet' => str_replace(",", ".", str_replace(".", "", $this->input->post('nominal', TRUE))),
                    // 'kredit' => $this->input->post('kredit', TRUE),
                );
            } else {
                $data = array(
                    'tanggal' => $date_jurnal_kas,
                    'bukti' => $this->input->post('bukti', TRUE),
                    'pl' => $this->input->post('kode_pl', TRUE),
                    'keterangan' => $this->input->post('keterangan', TRUE),
                    // 'kode_rekening' => $this->input->post('kode_rekening', TRUE),
                    'uuid_unit' => $this->input->post('uuid_unit', TRUE),
                    'kode_unit' => $Get_kode_unit,
                    // 'debet' => str_replace(",", ".", str_replace(".", "", $this->input->post('debet', TRUE))),
                    'kredit' => str_replace(",", ".", str_replace(".", "", $this->input->post('nominal', TRUE))),
                );
            }



            $this->Jurnal_kas_model->insert($data);

            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('Jurnal_kas'));
        }
    }


    public function pemasukan_kas_update($id = null)
    {

        if ($id) {

            $row = $this->Jurnal_kas_model->get_by_id($id);
            if ($row) {
                $data = array(
                    'button' => 'Simpan Perubahan',
                    'action' => site_url('Jurnal_kas/pemasukan_kas_update_action/' . $id),
                    'id' => $row->id,
                    'tanggal' => $row->tanggal,
                    'bukti' => $row->bukti,
                    // 'pl' => $row->pl,
                    'keterangan' => $row->keterangan,
                    'uuid_unit' => $row->uuid_unit,
                    'kode_unit' => $row->kode_unit,
                    // 'kode_rekening' => $row->kode_rekening,
                    'debet' => str_replace(".", ",", $row->debet),
                    'kredit' => str_replace(".", ",", $row->kredit),
                );
                // $this->load->view('Jurnal_kas/Jurnal_kas_form', $data);
                $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/jurnal_kas/adminlte310_jurnal_kas_form_pemasukan', $data);
            } else {
                // Tidak ada id, kembali ke halaman jurnal kas
                redirect(site_url('Jurnal_kas'));
            }
        } else {
            // Tidak ada id, kembali ke halaman jurnal kas
            redirect(site_url('Jurnal_kas'));
        }
    }

    public function pemasukan_kas_update_action($id)
    {
        $this->_rules_pemasukan();

        if ($this->form_validation->run() == FALSE) {
            $this->pemasukan_kas();
        } else {

            $row = $this->Jurnal_kas_model->get_by_id($id);
            if ($row) {

                if (date("Y", strtotime($this->input->post('tanggal', TRUE))) < 2020) {
                    $date_jurnal_kas = date("Y-m-d H:i:s");
                } else {
                    $date_jurnal_kas = date("Y-m-d H:i:s", strtotime($this->input->post('tanggal', TRUE)));
                }


                // unIT
                $this->db->where('uuid_unit', $this->input->post('uuid_unit', TRUE));
                $sys_unit_data = $this->db->get('sys_unit');


                // $Get_month_selected = date("m", strtotime($this->input->post('tanggal', TRUE)));
                // $Get_YEAR_selected = date("Y", strtotime($this->input->post('tanggal', TRUE)));


                if ($sys_unit_data->num_rows() > 0) {

                    $Get_unit_data = $sys_unit_data->row_array();

                    // $Get_uuid_unit = $this->input->post('uuid_unit', TRUE);
                    $Get_kode_unit = $Get_unit_data['kode_unit'];
                    // $Get_nama_unit = $Get_unit_data['nama_unit'];
                }


                if ($this->input->post('bukti', TRUE) == "BKM") {
                    $data = array(
                        'tanggal' => $date_jurnal_kas,
                        'bukti' => $this->input->post('bukti', TRUE),
                        // 'pl' => $this->input->post('pl', TRUE),
                        'keterangan' => $this->input->post('keterangan', TRUE),
                        // 'kode_rekening' => $this->input->post('kode_rekening', TRUE),
                        'uuid_unit' => $this->input->post('uuid_unit', TRUE),
                        'kode_unit' => $Get_kode_unit,
                        'debet' => str_replace(",", ".", str_replace(".", "", $this->input->post('nominal', TRUE))),
                        'kredit' => 0,
                    );
                } else {
                    $data = array(
                        'tanggal' => $date_jurnal_kas,
                        'bukti' => $this->input->post('bukti', TRUE),
                        // 'pl' => $this->input->post('pl', TRUE),
                        'keterangan' => $this->input->post('keterangan', TRUE),
                        // 'kode_rekening' => $this->input->post('kode_rekening', TRUE),
                        'uuid_unit' => $this->input->post('uuid_unit', TRUE),
                        'kode_unit' => $Get_kode_unit,
                        'debet' => 0,
                        'kredit' => str_replace(",", ".", str_replace(".", "", $this->input->post('nominal', TRUE))),
                    );
                }

                $this->Jurnal_kas_model->update($id, $data);
            }
            $this->session->set_flashdata('message', 'Create Record Success');




            // redirect(site_url('Jurnal_kas'));

            // date("Y-m-d H:i:s", strtotime($this->input->post('tanggal', TRUE)))

            $Get_month_selected = date("m", strtotime($this->input->post('tanggal', TRUE)));
            $Get_YEAR_selected = date("Y", strtotime($this->input->post('tanggal', TRUE)));
            // }
            $sql = "SELECT * FROM `jurnal_kas` WHERE MONTH(`tanggal`)=$Get_month_selected AND YEAR(`tanggal`)=$Get_YEAR_selected ORDER BY `tanggal`,`id`";

            $Data_kas = $this->db->query($sql)->result();


            $data = array(
                'Data_kas' => $Data_kas,
                // 'start' => $start,
                'date_awal' => $Get_date_awal,
                'date_akhir' => $Get_date_akhir,
                'month_akhir' => $Get_month_akhir,
                'month_selected' => date("m", strtotime($this->input->post('tanggal', TRUE))),
                'year_selected' => date("Y", strtotime($this->input->post('tanggal', TRUE))),
            );

            // print_r($data);

            $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/jurnal_kas/adminlte310_jurnal_kas_list', $data);
        }
    }




    public function pengeluaran_kas()
    {

        // $Data_kas = $this->Jurnal_kas_model->get_all();

        $data = array(
            'button' => 'Simpan',
            'action' => site_url('Jurnal_kas/pengeluaran_kas_action'),
            'nomor' => set_value('nomor'),
            'tanggal' => set_value('tanggal'),
            'bukti' => set_value('bukti'),
            'pl' => set_value('pl'),
            'keterangan' => set_value('keterangan'),
            'kode_rekening' => set_value('kode_rekening'),
            'debet' => set_value('debet'),
            'kredit' => set_value('kredit'),
            // 'Data_kas' => $Data_kas,
        );
        // $this->load->view('Jurnal_kas/jurnal_kas_form', $data);
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/jurnal_kas/adminlte310_jurnal_kas_form_pengeluaran', $data);
    }

    public function pengeluaran_kas_action()
    {
        $this->_rules_pengeluaran();

        if ($this->form_validation->run() == FALSE) {
            $this->pengeluaran_kas();
        } else {

            if (date("Y", strtotime($this->input->post('tanggal', TRUE))) < 2020) {
                $date_jurnal_kas = date("Y-m-d H:i:s");
            } else {
                $date_jurnal_kas = date("Y-m-d H:i:s", strtotime($this->input->post('tanggal', TRUE)));
            }


            $data = array(
                'tanggal' => $date_jurnal_kas,
                'bukti' => $this->input->post('bukti', TRUE),
                'pl' => $this->input->post('pl', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
                'kode_rekening' => $this->input->post('kode_rekening', TRUE),
                'kredit' => str_replace(",", ".", str_replace(".", "", $this->input->post('kredit', TRUE))),

            );

            $this->Jurnal_kas_model->insert($data);

            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('Jurnal_kas'));
        }
    }





    public function pengeluaran_kas_update($id = null)
    {


        if ($id) {

            $row = $this->Jurnal_kas_model->get_by_id($id);
            if ($row) {
                $data = array(
                    'button' => 'Simpan Perubahan',
                    'action' => site_url('Jurnal_kas/pengeluaran_kas_update_action/' . $id),
                    'nomor' => $row->nomor,
                    'tanggal' => $row->tanggal,
                    'bukti' => $row->bukti,
                    'pl' => $row->pl,
                    'keterangan' => $row->keterangan,
                    'kode_rekening' => $row->kode_rekening,
                    // 'debet' => str_replace(".", ",", $row->debet),
                    'kredit' => str_replace(".", ",", $row->kredit),
                );
                // $this->load->view('Jurnal_kas/jurnal_kas_form', $data);
                $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/jurnal_kas/adminlte310_jurnal_kas_form_pengeluaran', $data);
            } else {
                redirect(site_url('Jurnal_kas'));
            }
        } else {
            redirect(site_url('Jurnal_kas'));
        }
    }

    public function pengeluaran_kas_update_action($id)
    {
        $this->_rules_pengeluaran();

        if ($this->form_validation->run() == FALSE) {
            $this->pengeluaran_kas();
        } else {

            $row = $this->Jurnal_kas_model->get_by_id($id);
            if ($row) {


                if (date("Y", strtotime($this->input->post('tanggal', TRUE))) < 2020) {
                    $date_jurnal_kas = date("Y-m-d H:i:s");
                } else {
                    $date_jurnal_kas = date("Y-m-d H:i:s", strtotime($this->input->post('tanggal', TRUE)));
                }


                $data = array(
                    'tanggal' => $date_jurnal_kas,
                    'bukti' => $this->input->post('bukti', TRUE),
                    'pl' => $this->input->post('pl', TRUE),
                    'keterangan' => $this->input->post('keterangan', TRUE),
                    'kode_rekening' => $this->input->post('kode_rekening', TRUE),
                    'kredit' => str_replace(",", ".", str_replace(".", "", $this->input->post('kredit', TRUE))),

                );

                $this->Jurnal_kas_model->update($id, $data);
            }
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('Jurnal_kas'));
        }
    }










    public function read($id)
    {
        $row = $this->Jurnal_kas_model->get_by_id($id);
        if ($row) {
            $data = array(
                'nomor' => $row->nomor,
                'tanggal' => $row->tanggal,
                'bukti' => $row->bukti,
                'keterangan' => $row->keterangan,
                'kode_rekening' => $row->kode_rekening,
                'debet' => $row->debet,
                'kredit' => $row->kredit,
            );
            $this->load->view('jurnal_kas/jurnal_kas_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_kas'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('jurnal_kas/create_action'),
            'nomor' => set_value('nomor'),
            'tanggal' => set_value('tanggal'),
            'bukti' => set_value('bukti'),
            'keterangan' => set_value('keterangan'),
            'kode_rekening' => set_value('kode_rekening'),
            'debet' => set_value('debet'),
            'kredit' => set_value('kredit'),
        );
        $this->load->view('jurnal_kas/jurnal_kas_form', $data);
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
                'keterangan' => $this->input->post('keterangan', TRUE),
                'kode_rekening' => $this->input->post('kode_rekening', TRUE),
                'debet' => $this->input->post('debet', TRUE),
                'kredit' => $this->input->post('kredit', TRUE),
            );

            $this->Jurnal_kas_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('jurnal_kas'));
        }
    }

    public function update($id)
    {
        $row = $this->Jurnal_kas_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('jurnal_kas/update_action'),
                'nomor' => set_value('nomor', $row->nomor),
                'tanggal' => set_value('tanggal', $row->tanggal),
                'bukti' => set_value('bukti', $row->bukti),
                'keterangan' => set_value('keterangan', $row->keterangan),
                'kode_rekening' => set_value('kode_rekening', $row->kode_rekening),
                'debet' => set_value('debet', $row->debet),
                'kredit' => set_value('kredit', $row->kredit),
            );
            $this->load->view('jurnal_kas/jurnal_kas_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_kas'));
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
                'keterangan' => $this->input->post('keterangan', TRUE),
                'kode_rekening' => $this->input->post('kode_rekening', TRUE),
                'debet' => $this->input->post('debet', TRUE),
                'kredit' => $this->input->post('kredit', TRUE),
            );

            $this->Jurnal_kas_model->update($this->input->post('nomor', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('jurnal_kas'));
        }
    }

    public function delete($id)
    {
        $row = $this->Jurnal_kas_model->get_by_id($id);

        if ($row) {
            $this->Jurnal_kas_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('jurnal_kas'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_kas'));
        }
    }

    public function _rules_pemasukan()
    {
        $this->form_validation->set_rules('tanggal', 'tanggal', 'trim|required');
        // $this->form_validation->set_rules('bukti', 'bukti', 'trim|required');
        $this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');
        // $this->form_validation->set_rules('kode_rekening', 'kode rekening', 'trim|required');
        $this->form_validation->set_rules('nominal', 'nominal', 'trim|required');
        // $this->form_validation->set_rules('kredit', 'kredit', 'trim|required');

        // $this->form_validation->set_rules('nomor', 'nomor', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
    public function _rules_pengeluaran()
    {
        $this->form_validation->set_rules('tanggal', 'tanggal', 'trim|required');
        // $this->form_validation->set_rules('bukti', 'bukti', 'trim|required');
        $this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');
        // $this->form_validation->set_rules('kode_rekening', 'kode rekening', 'trim|required');
        // $this->form_validation->set_rules('debet', 'debet', 'trim|required');
        $this->form_validation->set_rules('kredit', 'kredit', 'trim|required');

        // $this->form_validation->set_rules('nomor', 'nomor', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel($Tahun_selected = null, $Bulan_selected = null)
    {

        $date_sekarang = date('Y-m-d H:i:s');
        $variabel_date_awal_selected = date('Y-m-d', strtotime('+0 month', strtotime($date_sekarang)));





        if ($Bulan_selected) {
            $Get_month_selected = $Bulan_selected;
            $Get_YEAR_selected = $Tahun_selected;
        } else {
            $Get_month_selected = date('m', strtotime('+0 month', strtotime($variabel_date_awal_selected)));
            $Get_YEAR_selected = date('Y', strtotime('+0 month', strtotime($variabel_date_awal_selected)));
        }


        $Get_month_from_date = $Get_month_selected;
        $Get_month_from_date_lalu = $Get_month_selected - 1;
        $Get_year_Tahun_ini = $Get_YEAR_selected;
        // $Get_year_Setahun_lalu = date("Y", strtotime('-1 year'));

        if ($Get_month_selected > 1) {
            $Tahun_saldo_akhir_bulan_lalu = $Get_YEAR_selected;
        } else {
            $Tahun_saldo_akhir_bulan_lalu = $Get_YEAR_selected - 1;
        }



        // print_r($Get_month_selected);
        // print_r("<br/>");
        // print_r($Get_YEAR_selected);
        // print_r("<br/>");
        // die;

        $sql = "SELECT * FROM `jurnal_kas` WHERE MONTH(`tanggal`)=$Get_month_selected AND YEAR(`tanggal`)=$Get_YEAR_selected ORDER BY `tanggal`,`id`";

        $Data_kas = $this->db->query($sql)->result();


        $tgl_sekarang = date("d-m-Y H:i:s");

        function bulan_teks($angka_bulan)
        {
            if ($angka_bulan == 1) {
                $bulan_teks = "Januari";
            } elseif ($angka_bulan == 2) {
                $bulan_teks = "Februari";
            } elseif ($angka_bulan == 3) {
                $bulan_teks = "Maret";
            } elseif ($angka_bulan == 4) {
                $bulan_teks = "April";
            } elseif ($angka_bulan == 5) {
                $bulan_teks = "Mei";
            } elseif ($angka_bulan == 6) {
                $bulan_teks = "Juni";
            } elseif ($angka_bulan == 7) {
                $bulan_teks = "Juli";
            } elseif ($angka_bulan == 8) {
                $bulan_teks = "Agustus";
            } elseif ($angka_bulan == 9) {
                $bulan_teks = "September";
            } elseif ($angka_bulan == 10) {
                $bulan_teks = "Oktober";
            } elseif ($angka_bulan == 11) {
                $bulan_teks = "November";
            } elseif ($angka_bulan == 12) {
                $bulan_teks = "Desember";
            } else {
                $bulan_teks = "";
            }
            return $bulan_teks;
        }





        if ($Get_month_selected > 1) {
            // $Get_month_from_date = $Get_month_from_date_lalu;
            // echo "Saldo akhir bulan: " . bulan_teks($Get_month_from_date_lalu) . " " . $Get_year_Tahun_ini;

            $Get_Nama_bulan_lalu = bulan_teks($Get_month_from_date_lalu) . " " . $Get_YEAR_selected ;

            // GET NOMINAL SALDO DARI TABEL SALDO AKHIR BULAN KEMARIN
            $Get_bulan_saldo = date("$Get_year_Tahun_ini-$Get_month_from_date_lalu-01");

            $this->db->where('tanggal', $Get_bulan_saldo);
            $GET_jurnal_kas_saldo_akhir_bulan = $this->db->get('jurnal_kas_saldo_akhir_bulan');

            if ($GET_jurnal_kas_saldo_akhir_bulan->num_rows() > 0) {
                // $SALDO_AKHIR_BULAN_LALU = $GET_jurnal_kas_saldo_akhir_bulan->row()->saldo;

                if ($GET_jurnal_kas_saldo_akhir_bulan->row()->saldo > 0) {
                    $SALDO_DEBET = $GET_jurnal_kas_saldo_akhir_bulan->row()->saldo;
                    $SALDO_KREDIT = 0;
                } else {
                    $SALDO_KREDIT = $GET_jurnal_kas_saldo_akhir_bulan->row()->saldo;
                    $SALDO_DEBET = 0;
                }
            } else {
                $SALDO_KREDIT = 0;
                $SALDO_DEBET = 0;
            }
        } else {

            // echo "Saldo akhir bulan: Desember " . $Get_year_Setahun_lalu;

            $Get_Nama_bulan_lalu = "Desember " . $Tahun_saldo_akhir_bulan_lalu;

            // GET NOMINAL SALDO DARI TABEL SALDO AKHIR BULAN DESEMBER KEMARIN
            $Get_bulan_saldo = date("$Get_year_Setahun_lalu-12-01");

            $this->db->where('tanggal', $Get_bulan_saldo);
            $GET_jurnal_kas_saldo_akhir_bulan = $this->db->get('jurnal_kas_saldo_akhir_bulan');

            if ($GET_jurnal_kas_saldo_akhir_bulan->num_rows() > 0) {
                // echo $GET_jurnal_kas_saldo_akhir_bulan->row()->saldo;
                // $SALDO_AKHIR_BULAN_LALU = $GET_jurnal_kas_saldo_akhir_bulan->row()->saldo;

                if ($GET_jurnal_kas_saldo_akhir_bulan->row()->saldo > 0) {
                    $SALDO_DEBET = $GET_jurnal_kas_saldo_akhir_bulan->row()->saldo;
                    $SALDO_KREDIT = 0;
                } else {
                    $SALDO_KREDIT = $GET_jurnal_kas_saldo_akhir_bulan->row()->saldo;
                    $SALDO_DEBET = 0;
                }
            } else {
                // echo "0";
                // $SALDO_AKHIR_BULAN_LALU = 0;

                $SALDO_KREDIT = 0;
                $SALDO_DEBET = 0;
            }
        }














        $this->load->helper('exportexcel');
        $namaFile = "jurnal_kas_"  . $Get_month_selected . "_" . $Get_YEAR_selected . "_Dicetak_" . $tgl_sekarang  . ".xls";
        $judul = "jurnal_kas";
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
        xlsWriteLabel($tablehead, $kolomhead++, "Tanggal");
        xlsWriteLabel($tablehead, $kolomhead++, "Bukti");
        // xlsWriteLabel($tablehead, $kolomhead++, "");
        xlsWriteLabel($tablehead, $kolomhead++, "Keterangan");
        xlsWriteLabel($tablehead, $kolomhead++, "Kode");
        xlsWriteLabel($tablehead, $kolomhead++, "Debet");
        xlsWriteLabel($tablehead, $kolomhead++, "Kredit");

        // BARIS SALDO BULAN LALU

        $kolombody = 0;

        //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
        xlsWriteNumber($tablebody, $kolombody++, $nourut);
        xlsWriteLabel($tablebody, $kolombody++, "");
        xlsWriteLabel($tablebody, $kolombody++, "");
        // xlsWriteLabel($tablebody, $kolombody++, "");
        xlsWriteLabel($tablebody, $kolombody++, "SALDO  BULAN: " . $Get_Nama_bulan_lalu);
        xlsWriteLabel($tablebody, $kolombody++, "");
        xlsWriteLabel($tablebody, $kolombody++, $SALDO_DEBET);
        xlsWriteLabel($tablebody, $kolombody++, $SALDO_KREDIT);

        $tablebody++;
        $nourut++;

        // END OF BARIS SALDO BULAN LALU



        // foreach ($this->Jurnal_kas_model->get_all() as $data) {
        foreach ($Data_kas as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->tanggal);
            xlsWriteLabel($tablebody, $kolombody++, $data->bukti);
            // xlsWriteLabel($tablebody, $kolombody++, $data->pl);
            xlsWriteLabel($tablebody, $kolombody++, $data->keterangan);
            xlsWriteLabel($tablebody, $kolombody++, $data->kode_unit);
            xlsWriteLabel($tablebody, $kolombody++, $data->debet);
            xlsWriteLabel($tablebody, $kolombody++, $data->kredit);

            $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

    private function _penerimaan_kas_view_data($data = array())
    {
        if (!is_array($data)) {
            $data = array();
        }

        $compare_bulan_num = isset($data['month_akhir']) ? (int) $data['month_akhir'] : (int) date('m');
        if ($compare_bulan_num < 1 || $compare_bulan_num > 12) {
            $compare_bulan_num = (int) date('m');
        }

        $compare_tahun_num = (int) date('Y');
        if (!empty($data['date_akhir'])) {
            $compare_tahun_num = (int) date('Y', strtotime($data['date_akhir']));
        } elseif (!empty($data['date_awal'])) {
            $compare_tahun_num = (int) date('Y', strtotime($data['date_awal']));
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
        $data['url_compare_penerimaan_kas_run'] = site_url('Jurnal_kas/ajax_compare_penerimaan_kas_manual_online');
        $data['url_compare_penerimaan_kas_excel'] = site_url('Jurnal_kas/excel_compare_penerimaan_kas_manual_online');
        $data['url_compare_penerimaan_kas_import_csv'] = site_url('Jurnal_kas/ajax_compare_import_csv_penerimaan_kas');
        $data['url_compare_penerimaan_kas_check_csv'] = site_url('Jurnal_kas/ajax_compare_check_csv_penerimaan_kas');
        $data['url_compare_penerimaan_kas_validate_csv'] = site_url('Jurnal_kas/ajax_compare_validate_csv_penerimaan_kas');
        $data['url_compare_penerimaan_kas_tabel_list'] = site_url('Jurnal_kas/ajax_compare_tabel_list_penerimaan_kas');
        $data['url_compare_penerimaan_kas_tabel_preview'] = site_url('Jurnal_kas/ajax_compare_tabel_preview_penerimaan_kas');

        return $data;
    }

    private function _compare_penerimaan_kas_bulan_from_post()
    {
        $bulan_num = (int) $this->input->post('bulan_num', TRUE);
        $tahun = (int) $this->input->post('tahun', TRUE);
        if ($bulan_num >= 1 && $bulan_num <= 12 && $tahun >= 2000) {
            return $tahun . '-' . str_pad((string) $bulan_num, 2, '0', STR_PAD_LEFT);
        }

        return '';
    }

    public function ajax_compare_penerimaan_kas_manual_online()
    {
        $this->load->helper(array('pembelian_persediaan', 'penjualan_jurnal_compare', 'penerimaan_kas_compare'));

        $bulan = trim((string) $this->input->post('bulan', TRUE));
        if ($bulan === '') {
            $bulan = $this->_compare_penerimaan_kas_bulan_from_post();
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

        persediaan_ajax_json_output($this, penerimaan_kas_compare_run($this, $bulan, $table));
    }

    public function ajax_compare_tabel_list_penerimaan_kas()
    {
        $this->load->helper(array('pembelian_persediaan', 'penerimaan_kas_compare'));

        persediaan_ajax_json_output($this, array(
            'ok' => true,
            'tables' => persediaan_compare_list_db_tables($this),
        ));
    }

    public function ajax_compare_check_csv_penerimaan_kas()
    {
        $this->load->helper(array('pembelian_persediaan', 'penerimaan_kas_compare'));

        $original_name = trim((string) $this->input->post('filename', TRUE));
        if ($original_name === '') {
            persediaan_ajax_json_output($this, array(
                'ok' => false,
                'message' => 'Nama file CSV tidak valid.',
            ));
            return;
        }

        $bulan = $this->_compare_penerimaan_kas_bulan_from_post();
        persediaan_ajax_json_output($this, penerimaan_kas_compare_check_csv_table_name($this, $original_name, $bulan));
    }

    public function ajax_compare_validate_csv_penerimaan_kas()
    {
        $this->load->helper(array('pembelian_persediaan', 'penerimaan_kas_compare'));

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

        $result = penerimaan_kas_compare_validate_csv_file($_FILES['csv_file']['tmp_name']);
        $result['file'] = $original_name;
        persediaan_ajax_json_output($this, $result);
    }

    public function ajax_compare_import_csv_penerimaan_kas()
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');
        $this->load->helper(array('pembelian_persediaan', 'penjualan_jurnal_compare', 'penerimaan_kas_compare'));

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

        $bulan = $this->_compare_penerimaan_kas_bulan_from_post();
        $bulan_num = (int) $this->input->post('bulan_num', TRUE);
        $tahun = (int) $this->input->post('tahun', TRUE);
        $result = penerimaan_kas_compare_import_csv_to_db(
            $this,
            $_FILES['csv_file']['tmp_name'],
            $original_name,
            $bulan,
            $bulan_num,
            $tahun
        );

        persediaan_ajax_json_output($this, $result);
    }

    public function ajax_compare_tabel_preview_penerimaan_kas()
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');
        $this->load->helper(array('pembelian_persediaan', 'penerimaan_kas_compare'));

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

    public function excel_compare_penerimaan_kas_manual_online()
    {
        $this->load->helper(array('exportexcel', 'pembelian_persediaan', 'penjualan_jurnal_compare', 'penerimaan_kas_compare', 'persediaan_display'));

        $bulan = trim((string) $this->input->post('bulan', TRUE));
        if ($bulan === '') {
            $bulan = $this->_compare_penerimaan_kas_bulan_from_post();
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
        $allowed = array_keys(penerimaan_kas_compare_jenis_definitions());
        if ($jenis === '' || !in_array($jenis, $allowed, true)) {
            show_error('Jenis export compare tidak valid.', 400);
            return;
        }

        $defs = penerimaan_kas_compare_jenis_definitions();
        $suffix = isset($defs[$jenis]['file_suffix']) ? $defs[$jenis]['file_suffix'] : $jenis;
        $namaFile = 'Compare_Penerimaan_Kas_' . $suffix . '_' . $bulan . '_' . date('Y-m-d_H-i-s') . '.xlsx';
        excel_prepare_download($namaFile);
        penerimaan_kas_compare_export_excel_output($this, $bulan, $jenis, $table);
        exit();
    }

    private function _jurnal_kas_view_data($data = array())
    {
        if (!is_array($data)) {
            $data = array();
        }

        $compare_bulan_num = isset($data['month_selected']) ? (int) $data['month_selected'] : (int) date('m');
        if ($compare_bulan_num < 1 || $compare_bulan_num > 12) {
            $compare_bulan_num = (int) date('m');
        }

        $compare_tahun_num = isset($data['year_selected']) ? (int) $data['year_selected'] : (int) date('Y');

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
        $data['url_compare_jurnal_kas_run'] = site_url('Jurnal_kas/ajax_compare_jurnal_kas_manual_online');
        $data['url_compare_jurnal_kas_excel'] = site_url('Jurnal_kas/excel_compare_jurnal_kas_manual_online');
        $data['url_compare_jurnal_kas_import_csv'] = site_url('Jurnal_kas/ajax_compare_import_csv_jurnal_kas');
        $data['url_compare_jurnal_kas_tabel_list'] = site_url('Jurnal_kas/ajax_compare_tabel_list_jurnal_kas');
        $data['url_compare_jurnal_kas_tabel_preview'] = site_url('Jurnal_kas/ajax_compare_tabel_preview_jurnal_kas');

        return $data;
    }

    private function _compare_jurnal_kas_bulan_from_post()
    {
        $bulan_num = (int) $this->input->post('bulan_num', TRUE);
        $tahun = (int) $this->input->post('tahun', TRUE);
        if ($bulan_num >= 1 && $bulan_num <= 12 && $tahun >= 2000) {
            return $tahun . '-' . str_pad((string) $bulan_num, 2, '0', STR_PAD_LEFT);
        }

        return '';
    }

    public function ajax_compare_jurnal_kas_manual_online()
    {
        $this->load->helper(array('pembelian_persediaan', 'penjualan_jurnal_compare', 'jurnal_kas_compare'));

        $bulan = trim((string) $this->input->post('bulan', TRUE));
        if ($bulan === '') {
            $bulan = $this->_compare_jurnal_kas_bulan_from_post();
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

        persediaan_ajax_json_output($this, jurnal_kas_compare_run($this, $bulan, $table));
    }

    public function ajax_compare_tabel_list_jurnal_kas()
    {
        $this->load->helper(array('pembelian_persediaan', 'jurnal_kas_compare'));

        persediaan_ajax_json_output($this, array(
            'ok' => true,
            'tables' => persediaan_compare_list_db_tables($this),
        ));
    }

    public function ajax_compare_import_csv_jurnal_kas()
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');
        $this->load->helper(array('pembelian_persediaan', 'penjualan_jurnal_compare', 'jurnal_kas_compare'));

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

        $bulan = $this->_compare_jurnal_kas_bulan_from_post();
        $bulan_num = (int) $this->input->post('bulan_num', TRUE);
        $tahun = (int) $this->input->post('tahun', TRUE);
        $result = jurnal_kas_compare_import_csv_to_db(
            $this,
            $_FILES['csv_file']['tmp_name'],
            $original_name,
            $bulan,
            $bulan_num,
            $tahun
        );

        persediaan_ajax_json_output($this, $result);
    }

    public function ajax_compare_tabel_preview_jurnal_kas()
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');
        $this->load->helper(array('pembelian_persediaan', 'jurnal_kas_compare'));

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

    public function excel_compare_jurnal_kas_manual_online()
    {
        $this->load->helper(array('exportexcel', 'pembelian_persediaan', 'penjualan_jurnal_compare', 'jurnal_kas_compare', 'persediaan_display'));

        $bulan = trim((string) $this->input->post('bulan', TRUE));
        if ($bulan === '') {
            $bulan = $this->_compare_jurnal_kas_bulan_from_post();
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

        $namaFile = 'Compare_Jurnal_Kas_' . $bulan . '_' . date('Y-m-d_H-i-s') . '.xlsx';
        excel_prepare_download($namaFile);
        jurnal_kas_compare_export_excel_output($this, $bulan, $table);
        exit();
    }

    private function _pengeluaran_kas_view_data($data = array())
    {
        if (!is_array($data)) {
            $data = array();
        }

        $compare_bulan_num = isset($data['month_akhir']) ? (int) $data['month_akhir'] : (int) date('m');
        if ($compare_bulan_num < 1 || $compare_bulan_num > 12) {
            $compare_bulan_num = (int) date('m');
        }

        $compare_tahun_num = (int) date('Y');
        if (!empty($data['date_akhir'])) {
            $compare_tahun_num = (int) date('Y', strtotime($data['date_akhir']));
        } elseif (!empty($data['date_awal'])) {
            $compare_tahun_num = (int) date('Y', strtotime($data['date_awal']));
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
        $data['url_compare_pengeluaran_kas_run'] = site_url('Jurnal_kas/ajax_compare_pengeluaran_kas_manual_online');
        $data['url_compare_pengeluaran_kas_excel'] = site_url('Jurnal_kas/excel_compare_pengeluaran_kas_manual_online');
        $data['url_compare_pengeluaran_kas_import_csv'] = site_url('Jurnal_kas/ajax_compare_import_csv_pengeluaran_kas');
        $data['url_compare_pengeluaran_kas_tabel_list'] = site_url('Jurnal_kas/ajax_compare_tabel_list_pengeluaran_kas');
        $data['url_compare_pengeluaran_kas_tabel_preview'] = site_url('Jurnal_kas/ajax_compare_tabel_preview_pengeluaran_kas');

        return $data;
    }

    private function _compare_pengeluaran_kas_bulan_from_post()
    {
        $bulan_num = (int) $this->input->post('bulan_num', TRUE);
        $tahun = (int) $this->input->post('tahun', TRUE);
        if ($bulan_num >= 1 && $bulan_num <= 12 && $tahun >= 2000) {
            return $tahun . '-' . str_pad((string) $bulan_num, 2, '0', STR_PAD_LEFT);
        }

        return '';
    }

    public function ajax_compare_pengeluaran_kas_manual_online()
    {
        $this->load->helper(array('pembelian_persediaan', 'penjualan_jurnal_compare', 'jurnal_kas_compare', 'pengeluaran_kas_compare'));

        $bulan = trim((string) $this->input->post('bulan', TRUE));
        if ($bulan === '') {
            $bulan = $this->_compare_pengeluaran_kas_bulan_from_post();
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

        persediaan_ajax_json_output($this, pengeluaran_kas_compare_run($this, $bulan, $table));
    }

    public function ajax_compare_tabel_list_pengeluaran_kas()
    {
        $this->load->helper(array('pembelian_persediaan', 'pengeluaran_kas_compare'));

        persediaan_ajax_json_output($this, array(
            'ok' => true,
            'tables' => persediaan_compare_list_db_tables($this),
        ));
    }

    public function ajax_compare_import_csv_pengeluaran_kas()
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');
        $this->load->helper(array('pembelian_persediaan', 'penjualan_jurnal_compare', 'jurnal_kas_compare', 'pengeluaran_kas_compare'));

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

        $bulan = $this->_compare_pengeluaran_kas_bulan_from_post();
        $bulan_num = (int) $this->input->post('bulan_num', TRUE);
        $tahun = (int) $this->input->post('tahun', TRUE);
        $result = pengeluaran_kas_compare_import_csv_to_db(
            $this,
            $_FILES['csv_file']['tmp_name'],
            $original_name,
            $bulan,
            $bulan_num,
            $tahun
        );

        persediaan_ajax_json_output($this, $result);
    }

    public function ajax_compare_tabel_preview_pengeluaran_kas()
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');
        $this->load->helper(array('pembelian_persediaan', 'pengeluaran_kas_compare'));

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

    public function excel_compare_pengeluaran_kas_manual_online()
    {
        $this->load->helper(array('exportexcel', 'pembelian_persediaan', 'penjualan_jurnal_compare', 'pengeluaran_kas_compare', 'persediaan_display'));

        $bulan = trim((string) $this->input->post('bulan', TRUE));
        if ($bulan === '') {
            $bulan = $this->_compare_pengeluaran_kas_bulan_from_post();
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

        $namaFile = 'Compare_Pengeluaran_Kas_' . $bulan . '_' . date('Y-m-d_H-i-s') . '.xlsx';
        excel_prepare_download($namaFile);
        pengeluaran_kas_compare_export_excel_output($this, $bulan, $table);
        exit();
    }
}

/* End of file Jurnal_kas.php */
/* Location: ./application/controllers/Jurnal_kas.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-12-01 11:32:21 */
/* http://harviacode.com */