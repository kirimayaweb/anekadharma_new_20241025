<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_penyusutan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Tbl_penyusutan_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    // KODE - KODE YANG MASUK KE DATA PENYUSUTAN
    // Itu persediaan pak, kalo penyusutan 13101, 13102, 13103, 13104, 13105,
    // Sama nanti ada akumulasi penyusutan 52501, 52502, 52503, 52505, 52504, 52505, 52511


    public function index_server_side()
    {
        $this->load->view('anekadharma/tbl_penyusutan/tbl_penyusutan_list');
    }




    public function index()
    {
        // $GET_data_penyusutan = $this->Tbl_penyusutan_model->get_all();


        $data = array(
            'Data_penyusutan' => $this->Tbl_penyusutan_model->get_all(),
            // 'action' => site_url('Buku_besar/cari_kode_akun'),
        );

        // print_r($data);
        // die;

        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penyusutan/adminlte310_tbl_penyusutan_list', $data);
    }

    public function cari_between_date($GET_tahun = null, $GET_bulan = null)
    {

        // print_r("cari_between_date");
        // print_r("<br/>");
        // print_r("<br/>");
        // print_r("<br/>");

        if ($GET_tahun) {

            $Get_month_selected = $GET_bulan;
            $Get_YEAR_selected = $GET_tahun;
        } else {

            $Get_month_selected = date("m", strtotime($this->input->post('bulan_ns', TRUE)));
            $Get_YEAR_selected = date("Y", strtotime($this->input->post('bulan_ns', TRUE)));
        }

        // print_r($Get_month_selected);
        // print_r("<br/>");
        // print_r("<br/>");
        // print_r($Get_YEAR_selected);
        // print_r("<br/>");
        // print_r("<br/>");
        // // die;


        // $sql = "SELECT * FROM `tbl_penyusutan` WHERE MONTH(`tanggal`)=$Get_month_selected AND YEAR(`tanggal`)=$Get_YEAR_selected ORDER BY `group_kelompok_harta`,`id`";

        // $Data_kas = $this->db->query($sql)->result();

        $sql = "SELECT * FROM `tbl_penyusutan` WHERE `tahun_transaksi`='$Get_YEAR_selected' And `bulan_transaksi`='$Get_month_selected' order by tahun_transaksi,bulan_transaksi,group_kelompok_harta, id";
        $GET_Penyusutan_data_RECORD = $this->db->query($sql);

        // print_r($GET_Penyusutan_data_RECORD->num_rows());
        // die;


        if ($GET_Penyusutan_data_RECORD->num_rows() > 0) {
            // Buat data master semua list data dari master record tahun: 2025 bulan: 0


            // Tampilkan data sesuai tahun dan bulan

            $data = array(
                'Data_penyusutan' => $GET_Penyusutan_data_RECORD->result(),
            );
        } else {

            // print_r("proses copy");
            $data = array(
                'Data_penyusutan' => "",
            );
        }

        // print_r($data);
        // die;

        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penyusutan/adminlte310_tbl_penyusutan_list', $data);
    }


    public function Input_list_data_baru()
    {
        $data = array(
            'button' => 'Simpan',
            'action' => site_url('Tbl_penyusutan/Simpan_Input_list_data_baru_action'),
            'id' => set_value('id'),
            'uuid_penyusutan' => set_value('uuid_penyusutan'),
            'kelompok_harta' => set_value('kelompok_harta'),
            'tanggal_perolehan' => set_value('tanggal_perolehan'),
            'harga_perolehan' => set_value('harga_perolehan'),
            'user' => set_value('user'),
            'armorst_penyusutan_thn_lalu' => set_value('armorst_penyusutan_thn_lalu'),
            'nilai_buku_thn_lalu' => set_value('nilai_buku_thn_lalu'),
            'penyusutan_bulan_ini' => set_value('penyusutan_bulan_ini'),
            'armorst_penyusutan_bulan_ini' => set_value('armorst_penyusutan_bulan_ini'),
            'nilai_buku_bulan_ini' => set_value('nilai_buku_bulan_ini'),
        );


        // $this->load->view('tbl_penyusutan/tbl_penyusutan_form', $data);        

        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penyusutan/adminlte310_tbl_penyusutan_form_input_list_baru', $data);
    }

    public function Simpan_Input_list_data_baru_action()
    {

        // print_r("Simpan_Input_list_data_baru_action");
        // print_r("<br/>");
        // print_r("<br/>");

        $month_selected = date("m", strtotime($this->input->post('bulan_ns', TRUE)));
        $year_selected = date("Y", strtotime($this->input->post('bulan_ns', TRUE)));

        // print_r($month_selected);
        // print_r("<br/>");
        // print_r($year_selected);
        // print_r("<br/>");
        // print_r($this->input->post('group_kelompok_harta', TRUE));
        // print_r("<br/>");
        // print_r($this->input->post('KlmpkJenisHarta', TRUE));
        // print_r("<br/>");
        // print_r($this->input->post('HargaPerolehan', TRUE));
        // print_r("<br/>");
        // print_r($this->input->post('User', TRUE));
        // print_r("<br/>");
        // print_r($this->input->post('AmortisasiPenyusutanTahunLalu', TRUE));
        // print_r("<br/>");
        // print_r($this->input->post('NilaiBuku', TRUE));
        // print_r("<br/>");
        // print_r($this->input->post('Penyusutan', TRUE));
        // print_r("<br/>");
        // print_r($this->input->post('AmortisasiPenyusutanTahunIni', TRUE));
        // print_r("<br/>");
        // print_r($this->input->post('nilaibuku', TRUE));
        // print_r("<br/>");

        // die;

        // $Get_date_input = date("Y-m-d H:i:s");


        $data_input_box_HargaPerolehan = str_replace('.', '', $this->input->post('HargaPerolehan', TRUE));
        $data_input_box_HargaPerolehan = str_replace(',', '.', $data_input_box_HargaPerolehan);

        $data_input_box_AmortisasiPenyusutanTahunLalu = str_replace('.', '', $this->input->post('AmortisasiPenyusutanTahunLalu', TRUE));
        $data_input_box_AmortisasiPenyusutanTahunLalu = str_replace(',', '.', $data_input_box_AmortisasiPenyusutanTahunLalu);

        $data_input_box_NilaiBukuTahunLalu = str_replace('.', '', $this->input->post('NilaiBukuTahunLalu', TRUE));
        $data_input_box_NilaiBukuTahunLalu = str_replace(',', '.', $data_input_box_NilaiBukuTahunLalu);

        $data_input_box_Penyusutan = str_replace('.', '', $this->input->post('Penyusutan', TRUE));
        $data_input_box_Penyusutan = str_replace(',', '.', $data_input_box_Penyusutan);

        $data_input_box_AmortisasiPenyusutanTahunIni = str_replace('.', '', $this->input->post('AmortisasiPenyusutanTahunIni', TRUE));
        $data_input_box_AmortisasiPenyusutanTahunIni = str_replace(',', '.', $data_input_box_AmortisasiPenyusutanTahunIni);

        $data_input_box_nilaibukubulanini = str_replace('.', '', $this->input->post('nilaibukubulanini', TRUE));
        $data_input_box_nilaibukubulanini = str_replace(',', '.', $data_input_box_nilaibukubulanini);


        $data = array(
            'tgl_input' => date("Y-m-d H:i:s"),
            'tahun_transaksi' => $year_selected,
            'bulan_transaksi' => $month_selected,
            'tanggal_perolehan' => date("Y-m-d H:i:s", strtotime($this->input->post('tanggal', TRUE))),
            'group_kelompok_harta' => $this->input->post('group_kelompok_harta', TRUE),
            'kelompok_harta' => $this->input->post('KlmpkJenisHarta', TRUE),
            // 'tanggal_perolehan' => $this->input->post('', TRUE),
            'harga_perolehan' => $data_input_box_HargaPerolehan,
            'user' => $this->input->post('User', TRUE),
            'armorst_penyusutan_thn_lalu' => $data_input_box_AmortisasiPenyusutanTahunLalu,
            'nilai_buku_thn_lalu' => $data_input_box_NilaiBukuTahunLalu,
            'penyusutan_bulan_ini' => $data_input_box_Penyusutan,
            'armorst_penyusutan_bulan_ini' => $data_input_box_AmortisasiPenyusutanTahunIni,
            'nilai_buku_bulan_ini' => $data_input_box_nilaibukubulanini,
        );

        $this->Tbl_penyusutan_model->insert($data);
        $this->session->set_flashdata('message', 'Create Record Success');
        redirect(site_url('Tbl_penyusutan/cari_between_date/' . $year_selected . '/' . $month_selected));
    }



    public function update_list_data($uuid_penyusutan)
    {
        $row = $this->Tbl_penyusutan_model->get_by_uuid_penyusutan($uuid_penyusutan);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('tbl_penyusutan/update_list_data_action/' . $uuid_penyusutan),
                'id' => set_value('id', $row->id),
                'uuid_penyusutan' => set_value('uuid_penyusutan', $row->uuid_penyusutan),
                'group_kelompok_harta' => set_value('group_kelompok_harta', $row->group_kelompok_harta),
                'kelompok_harta' => set_value('kelompok_harta', $row->kelompok_harta),
                'tanggal_perolehan' => set_value('tanggal_perolehan', $row->tanggal_perolehan),
                'harga_perolehan' => set_value('harga_perolehan', $row->harga_perolehan),
                'user' => set_value('user', $row->user),
                'armorst_penyusutan_thn_lalu' => set_value('armorst_penyusutan_thn_lalu', $row->armorst_penyusutan_thn_lalu),
                'nilai_buku_thn_lalu' => set_value('nilai_buku_thn_lalu', $row->nilai_buku_thn_lalu),
                'penyusutan_bulan_ini' => set_value('penyusutan_bulan_ini', $row->penyusutan_bulan_ini),
                'armorst_penyusutan_bulan_ini' => set_value('armorst_penyusutan_bulan_ini', $row->armorst_penyusutan_bulan_ini),
                'nilai_buku_bulan_ini' => set_value('nilai_buku_bulan_ini', $row->nilai_buku_bulan_ini),
            );

            // $this->load->view('tbl_penyusutan/tbl_penyusutan_form', $data);
            $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_penyusutan/adminlte310_tbl_penyusutan_form_input_list_update', $data);
        } else {

            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_penyusutan'));
        }
    }

    public function update_list_data_action($uuid_penyusutan = null)
    {

        $row = $this->Tbl_penyusutan_model->get_by_uuid_penyusutan($uuid_penyusutan);



        $data_input_box_HargaPerolehan = str_replace('.', '', $this->input->post('HargaPerolehan', TRUE));
        $data_input_box_HargaPerolehan = str_replace(',', '.', $data_input_box_HargaPerolehan);

        $data_input_box_AmortisasiPenyusutanTahunLalu = str_replace('.', '', $this->input->post('AmortisasiPenyusutanTahunLalu', TRUE));
        $data_input_box_AmortisasiPenyusutanTahunLalu = str_replace(',', '.', $data_input_box_AmortisasiPenyusutanTahunLalu);

        $data_input_box_NilaiBukuTahunLalu = str_replace('.', '', $this->input->post('NilaiBukuTahunLalu', TRUE));
        $data_input_box_NilaiBukuTahunLalu = str_replace(',', '.', $data_input_box_NilaiBukuTahunLalu);

        $data_input_box_Penyusutan = str_replace('.', '', $this->input->post('Penyusutan', TRUE));
        $data_input_box_Penyusutan = str_replace(',', '.', $data_input_box_Penyusutan);

        $data_input_box_AmortisasiPenyusutanTahunIni = str_replace('.', '', $this->input->post('AmortisasiPenyusutanTahunIni', TRUE));
        $data_input_box_AmortisasiPenyusutanTahunIni = str_replace(',', '.', $data_input_box_AmortisasiPenyusutanTahunIni);

        $data_input_box_nilaibukubulanini = str_replace('.', '', $this->input->post('nilaibukubulanini', TRUE));
        $data_input_box_nilaibukubulanini = str_replace(',', '.', $data_input_box_nilaibukubulanini);


        $data = array(
            // 'uuid_penyusutan' => $this->input->post('uuid_penyusutan', TRUE),
            'group_kelompok_harta' => $this->input->post('group_kelompok_harta', TRUE),
            'kelompok_harta' => $this->input->post('KlmpkJenisHarta', TRUE),
            'tanggal_perolehan' => date("Y-m-d H:i:s", strtotime($this->input->post('tanggal', TRUE))),
            'harga_perolehan' => $data_input_box_HargaPerolehan,
            'user' => $this->input->post('User', TRUE),
            'armorst_penyusutan_thn_lalu' => $data_input_box_AmortisasiPenyusutanTahunLalu,
            'nilai_buku_thn_lalu' => $data_input_box_NilaiBukuTahunLalu,
            'penyusutan_bulan_ini' => $data_input_box_Penyusutan,
            'armorst_penyusutan_bulan_ini' => $data_input_box_AmortisasiPenyusutanTahunIni,
            'nilai_buku_bulan_ini' => $data_input_box_nilaibukubulanini,
        );

        $this->Tbl_penyusutan_model->update($row->id, $data);
        $this->session->set_flashdata('message', 'Update Record Success');
        redirect(site_url('Tbl_penyusutan'));
    }


    public function json()
    {
        header('Content-Type: application/json');
        echo $this->Tbl_penyusutan_model->json();
    }

    public function read($id)
    {
        $row = $this->Tbl_penyusutan_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'uuid_penyusutan' => $row->uuid_penyusutan,
                'kelompok_harta' => $row->kelompok_harta,
                'tanggal_perolehan' => $row->tanggal_perolehan,
                'harga_perolehan' => $row->harga_perolehan,
                'user' => $row->user,
                'armorst_penyusutan_thn_lalu' => $row->armorst_penyusutan_thn_lalu,
                'nilai_buku_thn_lalu' => $row->nilai_buku_thn_lalu,
                'penyusutan_bulan_ini' => $row->penyusutan_bulan_ini,
                'armorst_penyusutan_bulan_ini' => $row->armorst_penyusutan_bulan_ini,
                'nilai_buku_bulan_ini' => $row->nilai_buku_bulan_ini,
            );
            $this->load->view('tbl_penyusutan/tbl_penyusutan_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_penyusutan'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('tbl_penyusutan/create_action'),
            'id' => set_value('id'),
            'uuid_penyusutan' => set_value('uuid_penyusutan'),
            'kelompok_harta' => set_value('kelompok_harta'),
            'tanggal_perolehan' => set_value('tanggal_perolehan'),
            'harga_perolehan' => set_value('harga_perolehan'),
            'user' => set_value('user'),
            'armorst_penyusutan_thn_lalu' => set_value('armorst_penyusutan_thn_lalu'),
            'nilai_buku_thn_lalu' => set_value('nilai_buku_thn_lalu'),
            'penyusutan_bulan_ini' => set_value('penyusutan_bulan_ini'),
            'armorst_penyusutan_bulan_ini' => set_value('armorst_penyusutan_bulan_ini'),
            'nilai_buku_bulan_ini' => set_value('nilai_buku_bulan_ini'),
        );
        $this->load->view('tbl_penyusutan/tbl_penyusutan_form', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'uuid_penyusutan' => $this->input->post('uuid_penyusutan', TRUE),
                'kelompok_harta' => $this->input->post('kelompok_harta', TRUE),
                'tanggal_perolehan' => $this->input->post('tanggal_perolehan', TRUE),
                'harga_perolehan' => $this->input->post('harga_perolehan', TRUE),
                'user' => $this->input->post('user', TRUE),
                'armorst_penyusutan_thn_lalu' => $this->input->post('armorst_penyusutan_thn_lalu', TRUE),
                'nilai_buku_thn_lalu' => $this->input->post('nilai_buku_thn_lalu', TRUE),
                'penyusutan_bulan_ini' => $this->input->post('penyusutan_bulan_ini', TRUE),
                'armorst_penyusutan_bulan_ini' => $this->input->post('armorst_penyusutan_bulan_ini', TRUE),
                'nilai_buku_bulan_ini' => $this->input->post('nilai_buku_bulan_ini', TRUE),
            );

            $this->Tbl_penyusutan_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('tbl_penyusutan'));
        }
    }

    public function update($id)
    {
        $row = $this->Tbl_penyusutan_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('tbl_penyusutan/update_action'),
                'id' => set_value('id', $row->id),
                'uuid_penyusutan' => set_value('uuid_penyusutan', $row->uuid_penyusutan),
                'kelompok_harta' => set_value('kelompok_harta', $row->kelompok_harta),
                'tanggal_perolehan' => set_value('tanggal_perolehan', $row->tanggal_perolehan),
                'harga_perolehan' => set_value('harga_perolehan', $row->harga_perolehan),
                'user' => set_value('user', $row->user),
                'armorst_penyusutan_thn_lalu' => set_value('armorst_penyusutan_thn_lalu', $row->armorst_penyusutan_thn_lalu),
                'nilai_buku_thn_lalu' => set_value('nilai_buku_thn_lalu', $row->nilai_buku_thn_lalu),
                'penyusutan_bulan_ini' => set_value('penyusutan_bulan_ini', $row->penyusutan_bulan_ini),
                'armorst_penyusutan_bulan_ini' => set_value('armorst_penyusutan_bulan_ini', $row->armorst_penyusutan_bulan_ini),
                'nilai_buku_bulan_ini' => set_value('nilai_buku_bulan_ini', $row->nilai_buku_bulan_ini),
            );
            $this->load->view('tbl_penyusutan/tbl_penyusutan_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_penyusutan'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'uuid_penyusutan' => $this->input->post('uuid_penyusutan', TRUE),
                'kelompok_harta' => $this->input->post('kelompok_harta', TRUE),
                'tanggal_perolehan' => $this->input->post('tanggal_perolehan', TRUE),
                'harga_perolehan' => $this->input->post('harga_perolehan', TRUE),
                'user' => $this->input->post('user', TRUE),
                'armorst_penyusutan_thn_lalu' => $this->input->post('armorst_penyusutan_thn_lalu', TRUE),
                'nilai_buku_thn_lalu' => $this->input->post('nilai_buku_thn_lalu', TRUE),
                'penyusutan_bulan_ini' => $this->input->post('penyusutan_bulan_ini', TRUE),
                'armorst_penyusutan_bulan_ini' => $this->input->post('armorst_penyusutan_bulan_ini', TRUE),
                'nilai_buku_bulan_ini' => $this->input->post('nilai_buku_bulan_ini', TRUE),
            );

            $this->Tbl_penyusutan_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('tbl_penyusutan'));
        }
    }

    public function delete($id)
    {
        $row = $this->Tbl_penyusutan_model->get_by_id($id);

        if ($row) {
            $this->Tbl_penyusutan_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('tbl_penyusutan'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_penyusutan'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('uuid_penyusutan', 'uuid penyusutan', 'trim|required');
        $this->form_validation->set_rules('kelompok_harta', 'kelompok harta', 'trim|required');
        $this->form_validation->set_rules('tanggal_perolehan', 'tanggal perolehan', 'trim|required');
        $this->form_validation->set_rules('harga_perolehan', 'harga perolehan', 'trim|required|numeric');
        $this->form_validation->set_rules('user', 'user', 'trim|required');
        $this->form_validation->set_rules('armorst_penyusutan_thn_lalu', 'armorst penyusutan thn lalu', 'trim|required|numeric');
        $this->form_validation->set_rules('nilai_buku_thn_lalu', 'nilai buku thn lalu', 'trim|required|numeric');
        $this->form_validation->set_rules('penyusutan_bulan_ini', 'penyusutan bulan ini', 'trim|required|numeric');
        $this->form_validation->set_rules('armorst_penyusutan_bulan_ini', 'armorst penyusutan bulan ini', 'trim|required|numeric');
        $this->form_validation->set_rules('nilai_buku_bulan_ini', 'nilai buku bulan ini', 'trim|required|numeric');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "tbl_penyusutan.xls";
        $judul = "tbl_penyusutan";
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
        xlsWriteLabel($tablehead, $kolomhead++, "Uuid Penyusutan");
        xlsWriteLabel($tablehead, $kolomhead++, "Kelompok Harta");
        xlsWriteLabel($tablehead, $kolomhead++, "Tanggal Perolehan");
        xlsWriteLabel($tablehead, $kolomhead++, "Harga Perolehan");
        xlsWriteLabel($tablehead, $kolomhead++, "User");
        xlsWriteLabel($tablehead, $kolomhead++, "Armorst Penyusutan Thn Lalu");
        xlsWriteLabel($tablehead, $kolomhead++, "Nilai Buku Thn Lalu");
        xlsWriteLabel($tablehead, $kolomhead++, "Penyusutan Bulan Ini");
        xlsWriteLabel($tablehead, $kolomhead++, "Armorst Penyusutan Bulan Ini");
        xlsWriteLabel($tablehead, $kolomhead++, "Nilai Buku Bulan Ini");

        foreach ($this->Tbl_penyusutan_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->uuid_penyusutan);
            xlsWriteLabel($tablebody, $kolombody++, $data->kelompok_harta);
            xlsWriteLabel($tablebody, $kolombody++, $data->tanggal_perolehan);
            xlsWriteNumber($tablebody, $kolombody++, $data->harga_perolehan);
            xlsWriteNumber($tablebody, $kolombody++, $data->user);
            xlsWriteNumber($tablebody, $kolombody++, $data->armorst_penyusutan_thn_lalu);
            xlsWriteNumber($tablebody, $kolombody++, $data->nilai_buku_thn_lalu);
            xlsWriteNumber($tablebody, $kolombody++, $data->penyusutan_bulan_ini);
            xlsWriteNumber($tablebody, $kolombody++, $data->armorst_penyusutan_bulan_ini);
            xlsWriteNumber($tablebody, $kolombody++, $data->nilai_buku_bulan_ini);

            $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }
}

/* End of file Tbl_penyusutan.php */
/* Location: ./application/controllers/Tbl_penyusutan.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2025-02-13 09:40:17 */
/* http://harviacode.com */