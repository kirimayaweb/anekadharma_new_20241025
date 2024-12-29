<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_uang_muka_didepan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Tbl_uang_muka_didepan_model');
        $this->load->library('form_validation');
        $this->load->helper(array('nominal'));
    }

    public function index()
    {
        $tbl_uang_muka_didepan = $this->Tbl_uang_muka_didepan_model->get_all();

        // print_r($tbl_uang_muka_didepan);
        // print_r("<b/>");
        // print_r("<b/>");
        // print_r("<b/>");

        // $start = 0;
        $data = array(
            'tbl_uang_muka_didepan_data' => $tbl_uang_muka_didepan,
            // 'start' => $start,
        );
        // print_r($tbl_uang_muka_didepan);
        // print_r("<b/>");
        // print_r("<b/>");
        // print_r("<b/>");


        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_uang_muka_didepan/adminlte310_tbl_uang_muka_didepan_list', $data);
    }


    public function indexXBU()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        if ($q <> '') {
            $config['base_url'] = base_url() . 'tbl_uang_muka_didepan/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'tbl_uang_muka_didepan/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'tbl_uang_muka_didepan/index.html';
            $config['first_url'] = base_url() . 'tbl_uang_muka_didepan/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Tbl_uang_muka_didepan_model->total_rows($q);
        $tbl_uang_muka_didepan = $this->Tbl_uang_muka_didepan_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'tbl_uang_muka_didepan_data' => $tbl_uang_muka_didepan,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->load->view('tbl_uang_muka_didepan/tbl_uang_muka_didepan_list', $data);
    }

    public function read($id)
    {
        $row = $this->Tbl_uang_muka_didepan_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'uuid_uang_muka_didepan' => $row->uuid_uang_muka_didepan,
                'tgl_transaksi' => $row->tgl_transaksi,
                'kode' => $row->kode,
                'dari' => $row->dari,
                'uraian' => $row->uraian,
                'nominal' => $row->nominal,
                'bank' => $row->bank,
                'nmr_rekening' => $row->nmr_rekening,
            );
            $this->load->view('tbl_uang_muka_didepan/tbl_uang_muka_didepan_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_uang_muka_didepan'));
        }
    }



    public function pemasukan_uang_muka_didepan()
    {

        // print_r("pemasukan_uang_muka_didepan");
        // print_r("<br/>");
        
        // $Tbl_uang_muka_didepan = $this->Tbl_uang_muka_didepan_model->get_all();

        // print_r($Tbl_uang_muka_didepan);
        // die;

        $data = array(
            'button' => 'SIMPAN',
            'action' => site_url('Tbl_uang_muka_didepan/create_action'),
            'id' => set_value('id'),
            'uuid_uang_muka_didepan' => set_value('uuid_uang_muka_didepan'),
            'tgl_transaksi' => set_value('tgl_transaksi'),
            'kode' => set_value('kode'),
            'dari' => set_value('dari'),
            'uraian' => set_value('uraian'),
            'nominal' => set_value('nominal'),
            'bank' => set_value('bank'),
            'nmr_rekening' => set_value('nmr_rekening'),
        );

        // $this->load->view('tbl_kas_kecil/tbl_kas_kecil_form', $data);
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_uang_muka_didepan/adminlte310_tbl_uang_muka_didepan_form_pemasukan', $data);

    }



    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('tbl_uang_muka_didepan/create_action'),
            'id' => set_value('id'),
            'uuid_uang_muka_didepan' => set_value('uuid_uang_muka_didepan'),
            'tgl_transaksi' => set_value('tgl_transaksi'),
            'kode' => set_value('kode'),
            'dari' => set_value('dari'),
            'uraian' => set_value('uraian'),
            'nominal' => set_value('nominal'),
            'bank' => set_value('bank'),
            'nmr_rekening' => set_value('nmr_rekening'),
        );
        $this->load->view('tbl_uang_muka_didepan/tbl_uang_muka_didepan_form', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->pemasukan_uang_muka_didepan();
        } else {

            if (date("Y", strtotime($this->input->post('tgl_transaksi', TRUE))) < 2020) {
                $date_uang_muka_didepan = date("Y-m-d H:i:s");
            } else {
                $date_uang_muka_didepan = date("Y-m-d H:i:s", strtotime($this->input->post('tgl_transaksi', TRUE)));
            }



            $data = array(
                // 'uuid_uang_muka_didepan' => $this->input->post('uuid_uang_muka_didepan', TRUE),
                'tgl_transaksi' => $date_uang_muka_didepan,
                'kode' => $this->input->post('kode', TRUE),
                'dari' => $this->input->post('dari', TRUE),
                'uraian' => $this->input->post('uraian', TRUE),
                'nominal' => preg_replace("/[^0-9]/", "", $this->input->post('nominal', TRUE)),
                'bank' => $this->input->post('bank', TRUE),
                'nmr_rekening' => $this->input->post('nmr_rekening', TRUE),
            );

            $this->Tbl_uang_muka_didepan_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('tbl_uang_muka_didepan'));
        }
    }

    public function update($id)
    {
        $row = $this->Tbl_uang_muka_didepan_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('tbl_uang_muka_didepan/update_action'),
                'id' => set_value('id', $row->id),
                'uuid_uang_muka_didepan' => set_value('uuid_uang_muka_didepan', $row->uuid_uang_muka_didepan),
                'tgl_transaksi' => set_value('tgl_transaksi', $row->tgl_transaksi),
                'kode' => set_value('kode', $row->kode),
                'dari' => set_value('dari', $row->dari),
                'uraian' => set_value('uraian', $row->uraian),
                'nominal' => set_value('nominal', $row->nominal),
                'bank' => set_value('bank', $row->bank),
                'nmr_rekening' => set_value('nmr_rekening', $row->nmr_rekening),
            );
            $this->load->view('tbl_uang_muka_didepan/tbl_uang_muka_didepan_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_uang_muka_didepan'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'uuid_uang_muka_didepan' => $this->input->post('uuid_uang_muka_didepan', TRUE),
                'tgl_transaksi' => $this->input->post('tgl_transaksi', TRUE),
                'kode' => $this->input->post('kode', TRUE),
                'dari' => $this->input->post('dari', TRUE),
                'uraian' => $this->input->post('uraian', TRUE),
                'nominal' => $this->input->post('nominal', TRUE),
                'bank' => $this->input->post('bank', TRUE),
                'nmr_rekening' => $this->input->post('nmr_rekening', TRUE),
            );

            $this->Tbl_uang_muka_didepan_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('tbl_uang_muka_didepan'));
        }
    }

    public function delete($id)
    {
        $row = $this->Tbl_uang_muka_didepan_model->get_by_id($id);

        if ($row) {
            $this->Tbl_uang_muka_didepan_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('tbl_uang_muka_didepan'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_uang_muka_didepan'));
        }
    }

    public function _rules()
    {
        // $this->form_validation->set_rules('uuid_uang_muka_didepan', 'uuid uang muka didepan', 'trim|required');
        $this->form_validation->set_rules('tgl_transaksi', 'tgl transaksi', 'trim|required');
        // $this->form_validation->set_rules('kode', 'kode', 'trim|required');
        $this->form_validation->set_rules('dari', 'dari', 'trim|required');
        $this->form_validation->set_rules('uraian', 'uraian', 'trim|required');
        $this->form_validation->set_rules('nominal', 'nominal', 'trim|required');
        $this->form_validation->set_rules('bank', 'bank', 'trim|required');
        $this->form_validation->set_rules('nmr_rekening', 'nmr rekening', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "tbl_uang_muka_didepan.xls";
        $judul = "tbl_uang_muka_didepan";
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
        xlsWriteLabel($tablehead, $kolomhead++, "Uuid Uang Muka Didepan");
        xlsWriteLabel($tablehead, $kolomhead++, "Tgl Transaksi");
        xlsWriteLabel($tablehead, $kolomhead++, "Kode");
        xlsWriteLabel($tablehead, $kolomhead++, "Dari");
        xlsWriteLabel($tablehead, $kolomhead++, "Uraian");
        xlsWriteLabel($tablehead, $kolomhead++, "Nominal");
        xlsWriteLabel($tablehead, $kolomhead++, "Bank");
        xlsWriteLabel($tablehead, $kolomhead++, "Nmr Rekening");

        foreach ($this->Tbl_uang_muka_didepan_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->uuid_uang_muka_didepan);
            xlsWriteLabel($tablebody, $kolombody++, $data->tgl_transaksi);
            xlsWriteLabel($tablebody, $kolombody++, $data->kode);
            xlsWriteLabel($tablebody, $kolombody++, $data->dari);
            xlsWriteLabel($tablebody, $kolombody++, $data->uraian);
            xlsWriteNumber($tablebody, $kolombody++, $data->nominal);
            xlsWriteLabel($tablebody, $kolombody++, $data->bank);
            xlsWriteLabel($tablebody, $kolombody++, $data->nmr_rekening);

            $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }
}

/* End of file Tbl_uang_muka_didepan.php */
/* Location: ./application/controllers/Tbl_uang_muka_didepan.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-10-23 08:16:36 */
/* http://harviacode.com */