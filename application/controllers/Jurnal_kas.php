<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jurnal_kas extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model(array('Jurnal_kas_model', 'Buku_besar_model'));
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



        $data = array(
            'Data_kas' => $Data_kas,
            // 'start' => $start,
            'date_awal' => $Get_date_awal,
            'date_akhir' => $Get_date_akhir,
            'month_akhir' => $Get_month_akhir,
            'month_selected' => date("m"),
            'year_selected' => date("Y"),
        );

        // print_r($data);

        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/jurnal_kas/adminlte310_jurnal_kas_list', $data);
    }


    public function cari_between_date()
    {

        // if ($GET_DATE) {
        //     print_r("GET_DATE");
        //     print_r("<br/>");
        //     print_r($GET_DATE);
        // } else {

        // print_r("NOTTTT GET_DATE");
        // print_r("<br/>");
        $Get_month_selected = date("m", strtotime($this->input->post('bulan_ns', TRUE)));
        $Get_YEAR_selected = date("Y", strtotime($this->input->post('bulan_ns', TRUE)));
        // }
        $sql = "SELECT * FROM `jurnal_kas` WHERE MONTH(`tanggal`)=$Get_month_selected AND YEAR(`tanggal`)=$Get_YEAR_selected ORDER BY `tanggal`,`id`";

        $Data_kas = $this->db->query($sql)->result();





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



        $data = array(
            'Data_kas' => $Data_kas,
            // 'start' => $start,
            'date_awal' => $Get_date_awal,
            'date_akhir' => $Get_date_akhir,
            'month_akhir' => $Get_month_akhir,
            'month_selected' => date("m", strtotime($this->input->post('bulan_ns', TRUE))),
            'year_selected' => date("Y", strtotime($this->input->post('bulan_ns', TRUE))),
        );

        // print_r($data);

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

        $data = array(
            'Data_kas' => $Data_kas,
            'date_awal' => $Get_date_awal,
            'date_akhir' => $Get_date_akhir,
            'month_akhir' => $Get_month_akhir,
        );
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


        $data = array(
            'Data_kas' => $Data_kas,
            // 'start' => $start,
            'date_awal' => $Get_date_awal,
            'date_akhir' => $Get_date_akhir,
            'month_akhir' => $Get_month_akhir,
        );

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

        $data = array(
            'Data_kas' => $Data_kas,
            'date_awal' => $Get_date_awal,
            'date_akhir' => $Get_date_akhir,
            'month_akhir' => $Get_month_akhir,
        );
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



        $sql_kas_penerimaan = "SELECT * FROM `jurnal_kas` WHERE `tanggal` between '$Get_date_awal' AND '$Get_date_akhir' AND `debet`>0 order by `pl`,`tanggal`,`id` ASC";

        // $sql_kas_penerimaan = "SELECT * FROM `jurnal_kas` WHERE `debet`>0 order by `pl`,`tanggal`,`id` ASC";

        $Data_kas = $this->db->query($sql_kas_penerimaan)->result();


        $data = array(
            'Data_kas' => $Data_kas,
            // 'start' => $start,
            'date_awal' => $Get_date_awal,
            'date_akhir' => $Get_date_akhir,
            'month_akhir' => $Get_month_akhir,
        );

        // print_r($data);

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

    public function excel()
    {
        $tgl_sekarang = date("d-m-Y H:i:s");

        $this->load->helper('exportexcel');
        $namaFile = "jurnal_kas_" . $tgl_sekarang . ".xls";
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
        xlsWriteLabel($tablehead, $kolomhead++, "");
        xlsWriteLabel($tablehead, $kolomhead++, "Keterangan");
        xlsWriteLabel($tablehead, $kolomhead++, "Kode Rekening");
        xlsWriteLabel($tablehead, $kolomhead++, "Debet");
        xlsWriteLabel($tablehead, $kolomhead++, "Kredit");

        foreach ($this->Jurnal_kas_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->tanggal);
            xlsWriteLabel($tablebody, $kolombody++, $data->bukti);
            xlsWriteLabel($tablebody, $kolombody++, $data->pl);
            xlsWriteLabel($tablebody, $kolombody++, $data->keterangan);
            xlsWriteNumber($tablebody, $kolombody++, $data->kode_rekening);
            xlsWriteLabel($tablebody, $kolombody++, $data->debet);
            xlsWriteLabel($tablebody, $kolombody++, $data->kredit);

            $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }
}

/* End of file Jurnal_kas.php */
/* Location: ./application/controllers/Jurnal_kas.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-12-01 11:32:21 */
/* http://harviacode.com */