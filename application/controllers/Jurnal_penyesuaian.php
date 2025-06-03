<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jurnal_penyesuaian extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Jurnal_penyesuaian_model');
        $this->load->library('form_validation');
    }

    public function index_BU()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        if ($q <> '') {
            $config['base_url'] = base_url() . 'jurnal_penyesuaian/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'jurnal_penyesuaian/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'jurnal_penyesuaian/index.html';
            $config['first_url'] = base_url() . 'jurnal_penyesuaian/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Jurnal_penyesuaian_model->total_rows($q);
        $jurnal_penyesuaian = $this->Jurnal_penyesuaian_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'jurnal_penyesuaian_data' => $jurnal_penyesuaian,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->load->view('jurnal_penyesuaian/jurnal_penyesuaian_list', $data);
    }


    public function index()
    {




        // $Get_date_awal = date("Y-m-1 00:00:00");
        // // print_r($Get_date_awal);
        // // print_r("<br/>");


        // $Get_date_akhir = date("Y-m-t 23:59:59"); // TANGGAL AKHIR BULAN -t
        // $Get_month_akhir = date("m"); // TANGGAL AKHIR BULAN -t
        // // print_r($Get_month);
        // // print_r("<br/>");

        // // die;



        // $sql = "SELECT * FROM `jurnal_penyesuaian` WHERE `tanggal` between '$Get_date_awal' and '$Get_date_akhir' ORDER BY `tanggal`,`id` DESC";

        // $Data_kas = $this->db->query($sql)->result();
        // ===========================================================================
        // if ($Get_date) {
        //     $Get_month_selected = date("m", strtotime($Get_date));
        //     $Get_YEAR_selected = date("Y", strtotime($Get_date));
        // } else {
        $Get_month_selected = date("m");
        $Get_YEAR_selected = date("Y");
        // }
        $sql = "SELECT * FROM `jurnal_penyesuaian` WHERE MONTH(`tanggal`)=$Get_month_selected AND YEAR(`tanggal`)=$Get_YEAR_selected ORDER BY `tanggal`,`id`";

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

        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/jurnal_penyesuaian/adminlte310_jurnal_penyesuaian', $data);
    }

    public function cari_between_date($Bulan_Pilih = null)
    {

        // 'month_selected' => date("m", strtotime($this->input->post('bulan_ns', TRUE))),
        // 	'year_selected' => date("Y", strtotime($this->input->post('bulan_ns', TRUE))),
        // print_r(date("m", strtotime($this->input->post('bulan_ns', TRUE))));
        // print_r("<br/>");
        // print_r(date("Y", strtotime($this->input->post('bulan_ns', TRUE))));

        // $Get_date = date("Y-m-d", strtotime($Bulan_Pilih));


        if ($Bulan_Pilih) {
            $Get_month_selected = date("m", strtotime($Bulan_Pilih));
            $Get_YEAR_selected = date("Y", strtotime($Bulan_Pilih));
        } else {
            $Get_month_selected = date("m", strtotime($this->input->post('bulan_ns', TRUE)));
            $Get_YEAR_selected = date("Y", strtotime($this->input->post('bulan_ns', TRUE)));
        }

        $sql = "SELECT * FROM `jurnal_penyesuaian` WHERE MONTH(`tanggal`)=$Get_month_selected AND YEAR(`tanggal`)=$Get_YEAR_selected ORDER BY `tanggal`,`id`";

        $Data_kas = $this->db->query($sql)->result();

        // print_r($Data_kas);
        // die;

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

        // $sql = "SELECT * FROM `jurnal_penyesuaian` WHERE `tanggal` between '$Get_date_awal' and '$Get_date_akhir' ORDER BY `tanggal`,`id`";

        // $Data_kas = $this->db->query($sql)->result();

        $data = array(
            'Data_kas' => $Data_kas,
            // 'start' => $start,
            'date_awal' => $Get_date_awal,
            'date_akhir' => $Get_date_akhir,
            'month_akhir' => $Get_month_akhir,
            'month_selected' => $Get_month_selected,
            'year_selected' => $Get_YEAR_selected,
        );

        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/jurnal_penyesuaian/adminlte310_jurnal_penyesuaian', $data);
    }

    public function read($id)
    {
        $row = $this->Jurnal_penyesuaian_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'uuid_jurnal_penyesuaian' => $row->uuid_jurnal_penyesuaian,
                'kode_akun' => $row->kode_akun,
                'tanggal' => $row->tanggal,
                'keterangan' => $row->keterangan,
                'kode_rekening' => $row->kode_rekening,
                'debet' => $row->debet,
                'kredit' => $row->kredit,
            );
            $this->load->view('jurnal_penyesuaian/jurnal_penyesuaian_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_penyesuaian'));
        }
    }

    public function Simpan_input_data()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            // print_r("Error");
            // die;
            $this->index();
        } else {

            // print_r("proses");
            // print_r("<br/>");
            // // die;

            if (date("Y", strtotime($this->input->post('tgl_po', TRUE))) < 2020) {
                $date_jurnal_penyesuaian = date("Y-m-d H:i:s");
                $date_cari_between = date("Y-m-d");
            } else {
                $date_jurnal_penyesuaian = date("Y-m-d H:i:s", strtotime($this->input->post('tgl_po', TRUE)));
                $date_cari_between = date("Y-m-d", strtotime($this->input->post('tgl_po', TRUE)));
            }

            if ($this->input->post('status_proses', TRUE) == "debet") {

                $data = array(
                    // 'uuid_jurnal_penyesuaian' => $this->input->post('uuid_jurnal_penyesuaian', TRUE),

                    'tanggal' => $date_jurnal_penyesuaian,
                    'kode_akun' => $this->input->post('kode_akun', TRUE),
                    'keterangan' => $this->input->post('keterangan', TRUE),
                    'kode_rekening' => $this->input->post('kode_rekening', TRUE),
                    'debet' => $this->input->post('nominal_penyesuaian', TRUE),
                    // 'kredit' => $this->input->post('kredit', TRUE),
                );
            } else {

                $data = array(
                    // 'uuid_jurnal_penyesuaian' => $this->input->post('uuid_jurnal_penyesuaian', TRUE),

                    'tanggal' => $date_jurnal_penyesuaian,
                    'kode_akun' => $this->input->post('kode_akun', TRUE),
                    'keterangan' => $this->input->post('keterangan', TRUE),
                    'kode_rekening' => $this->input->post('kode_rekening', TRUE),
                    // 'debet' => $this->input->post('debet', TRUE),
                    'kredit' => $this->input->post('nominal_penyesuaian', TRUE),
                );
            }

            // print_r($data);
            // die;

            $this->Jurnal_penyesuaian_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('Jurnal_penyesuaian/cari_between_date/' . $date_cari_between));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('jurnal_penyesuaian/create_action'),
            'id' => set_value('id'),
            'uuid_jurnal_penyesuaian' => set_value('uuid_jurnal_penyesuaian'),
            'kode_akun' => set_value('kode_akun'),
            'tanggal' => set_value('tanggal'),
            'keterangan' => set_value('keterangan'),
            'kode_rekening' => set_value('kode_rekening'),
            'debet' => set_value('debet'),
            'kredit' => set_value('kredit'),
        );
        $this->load->view('jurnal_penyesuaian/jurnal_penyesuaian_form', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'uuid_jurnal_penyesuaian' => $this->input->post('uuid_jurnal_penyesuaian', TRUE),
                'kode_akun' => $this->input->post('kode_akun', TRUE),
                'tanggal' => $this->input->post('tanggal', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
                'kode_rekening' => $this->input->post('kode_rekening', TRUE),
                'debet' => $this->input->post('debet', TRUE),
                'kredit' => $this->input->post('kredit', TRUE),
            );

            $this->Jurnal_penyesuaian_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('jurnal_penyesuaian'));
        }
    }

    public function update($id)
    {
        $row = $this->Jurnal_penyesuaian_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('jurnal_penyesuaian/update_action'),
                'id' => set_value('id', $row->id),
                'uuid_jurnal_penyesuaian' => set_value('uuid_jurnal_penyesuaian', $row->uuid_jurnal_penyesuaian),
                'kode_akun' => set_value('kode_akun', $row->kode_akun),
                'tanggal' => set_value('tanggal', $row->tanggal),
                'keterangan' => set_value('keterangan', $row->keterangan),
                'kode_rekening' => set_value('kode_rekening', $row->kode_rekening),
                'debet' => set_value('debet', $row->debet),
                'kredit' => set_value('kredit', $row->kredit),
            );
            $this->load->view('jurnal_penyesuaian/jurnal_penyesuaian_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_penyesuaian'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'uuid_jurnal_penyesuaian' => $this->input->post('uuid_jurnal_penyesuaian', TRUE),
                'kode_akun' => $this->input->post('kode_akun', TRUE),
                'tanggal' => $this->input->post('tanggal', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
                'kode_rekening' => $this->input->post('kode_rekening', TRUE),
                'debet' => $this->input->post('debet', TRUE),
                'kredit' => $this->input->post('kredit', TRUE),
            );

            $this->Jurnal_penyesuaian_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('jurnal_penyesuaian'));
        }
    }

    public function delete($id)
    {
        $row = $this->Jurnal_penyesuaian_model->get_by_id($id);

        if ($row) {
            $this->Jurnal_penyesuaian_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('jurnal_penyesuaian'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_penyesuaian'));
        }
    }

    public function _rules()
    {
        // $this->form_validation->set_rules('uuid_jurnal_penyesuaian', 'uuid jurnal penyesuaian', 'trim|required');
        $this->form_validation->set_rules('kode_akun', 'kode akun', 'trim|required');
        // $this->form_validation->set_rules('tanggal', 'tanggal', 'trim|required');
        // $this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');
        // $this->form_validation->set_rules('kode_rekening', 'kode rekening', 'trim|required');
        // $this->form_validation->set_rules('debet', 'debet', 'trim|required|numeric');
        // $this->form_validation->set_rules('kredit', 'kredit', 'trim|required|numeric');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "jurnal_penyesuaian.xls";
        $judul = "jurnal_penyesuaian";
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
        xlsWriteLabel($tablehead, $kolomhead++, "Uuid Jurnal Penyesuaian");
        xlsWriteLabel($tablehead, $kolomhead++, "Kode Akun");
        xlsWriteLabel($tablehead, $kolomhead++, "Tanggal");
        xlsWriteLabel($tablehead, $kolomhead++, "Keterangan");
        xlsWriteLabel($tablehead, $kolomhead++, "Kode Rekening");
        xlsWriteLabel($tablehead, $kolomhead++, "Debet");
        xlsWriteLabel($tablehead, $kolomhead++, "Kredit");

        foreach ($this->Jurnal_penyesuaian_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->uuid_jurnal_penyesuaian);
            xlsWriteLabel($tablebody, $kolombody++, $data->kode_akun);
            xlsWriteLabel($tablebody, $kolombody++, $data->tanggal);
            xlsWriteLabel($tablebody, $kolombody++, $data->keterangan);
            xlsWriteLabel($tablebody, $kolombody++, $data->kode_rekening);
            xlsWriteNumber($tablebody, $kolombody++, $data->debet);
            xlsWriteNumber($tablebody, $kolombody++, $data->kredit);

            $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }
}

/* End of file Jurnal_penyesuaian.php */
/* Location: ./application/controllers/Jurnal_penyesuaian.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2025-05-20 10:28:43 */
/* http://harviacode.com */