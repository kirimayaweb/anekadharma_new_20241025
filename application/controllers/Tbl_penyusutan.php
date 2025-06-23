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