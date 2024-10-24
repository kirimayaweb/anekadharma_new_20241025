<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_pendapatan_lain_lain extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Tbl_pendapatan_lain_lain_model');
        $this->load->library('form_validation');
    }

    public function indexXBU()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        if ($q <> '') {
            $config['base_url'] = base_url() . 'tbl_pendapatan_lain_lain/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'tbl_pendapatan_lain_lain/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'tbl_pendapatan_lain_lain/index.html';
            $config['first_url'] = base_url() . 'tbl_pendapatan_lain_lain/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Tbl_pendapatan_lain_lain_model->total_rows($q);
        $tbl_pendapatan_lain_lain = $this->Tbl_pendapatan_lain_lain_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'tbl_pendapatan_lain_lain_data' => $tbl_pendapatan_lain_lain,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->load->view('tbl_pendapatan_lain_lain/tbl_pendapatan_lain_lain_list', $data);
    }


    public function index()
    {

        $data_pendapatan_lain_lain = $this->Tbl_pendapatan_lain_lain_model->get_all();
        $data = array(
            'pendapatan_lain_lain_data' => $data_pendapatan_lain_lain,
            // 'action' => site_url('Tbl_pendapatan_lain_lain/cari_unit'),
        );
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pendapatan_lain_lain/adminlte310_tbl_pendapatan_lain_lain_list', $data);
    }

    public function read($id)
    {
        $row = $this->Tbl_pendapatan_lain_lain_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'uuid_pendapatan_lain_lain' => $row->uuid_pendapatan_lain_lain,
                'tgl_transaksi' => $row->tgl_transaksi,
                'kode' => $row->kode,
                'dari' => $row->dari,
                'uraian' => $row->uraian,
                'nominal' => $row->nominal,
                'bank' => $row->bank,
                'nmr_rekening' => $row->nmr_rekening,
            );
            $this->load->view('tbl_pendapatan_lain_lain/tbl_pendapatan_lain_lain_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_pendapatan_lain_lain'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('tbl_pendapatan_lain_lain/create_action'),
            'id' => set_value('id'),
            'uuid_pendapatan_lain_lain' => set_value('uuid_pendapatan_lain_lain'),
            'tgl_transaksi' => set_value('tgl_transaksi'),
            'kode' => set_value('kode'),
            'dari' => set_value('dari'),
            'uraian' => set_value('uraian'),
            'nominal' => set_value('nominal'),
            'bank' => set_value('bank'),
            'nmr_rekening' => set_value('nmr_rekening'),
        );
        // $this->load->view('tbl_pendapatan_lain_lain/tbl_pendapatan_lain_lain_form', $data);
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_pendapatan_lain_lain/adminlte310_tbl_pendapatan_lain_lain_form', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                // 'uuid_pendapatan_lain_lain' => $this->input->post('uuid_pendapatan_lain_lain', TRUE),
                'tgl_transaksi' => $this->input->post('tgl_transaksi', TRUE),
                'kode' => $this->input->post('kode', TRUE),
                'dari' => $this->input->post('dari', TRUE),
                'uraian' => $this->input->post('uraian', TRUE),
                'nominal' => $this->input->post('nominal', TRUE),
                'bank' => $this->input->post('bank', TRUE),
                'nmr_rekening' => $this->input->post('nmr_rekening', TRUE),
            );

            $this->Tbl_pendapatan_lain_lain_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('tbl_pendapatan_lain_lain'));
        }
    }

    public function update($id)
    {
        $row = $this->Tbl_pendapatan_lain_lain_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('tbl_pendapatan_lain_lain/update_action'),
                'id' => set_value('id', $row->id),
                'uuid_pendapatan_lain_lain' => set_value('uuid_pendapatan_lain_lain', $row->uuid_pendapatan_lain_lain),
                'tgl_transaksi' => set_value('tgl_transaksi', $row->tgl_transaksi),
                'kode' => set_value('kode', $row->kode),
                'dari' => set_value('dari', $row->dari),
                'uraian' => set_value('uraian', $row->uraian),
                'nominal' => set_value('nominal', $row->nominal),
                'bank' => set_value('bank', $row->bank),
                'nmr_rekening' => set_value('nmr_rekening', $row->nmr_rekening),
            );
            $this->load->view('tbl_pendapatan_lain_lain/tbl_pendapatan_lain_lain_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_pendapatan_lain_lain'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                // 'uuid_pendapatan_lain_lain' => $this->input->post('uuid_pendapatan_lain_lain', TRUE),
                'tgl_transaksi' => $this->input->post('tgl_transaksi', TRUE),
                'kode' => $this->input->post('kode', TRUE),
                'dari' => $this->input->post('dari', TRUE),
                'uraian' => $this->input->post('uraian', TRUE),
                'nominal' => $this->input->post('nominal', TRUE),
                'bank' => $this->input->post('bank', TRUE),
                'nmr_rekening' => $this->input->post('nmr_rekening', TRUE),
            );

            $this->Tbl_pendapatan_lain_lain_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('tbl_pendapatan_lain_lain'));
        }
    }

    public function delete($id)
    {
        $row = $this->Tbl_pendapatan_lain_lain_model->get_by_id($id);

        if ($row) {
            $this->Tbl_pendapatan_lain_lain_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('tbl_pendapatan_lain_lain'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_pendapatan_lain_lain'));
        }
    }

    public function _rules()
    {
        // $this->form_validation->set_rules('uuid_pendapatan_lain_lain', 'uuid pendapatan lain lain', 'trim|required');
        $this->form_validation->set_rules('tgl_transaksi', 'tgl transaksi', 'trim|required');
        $this->form_validation->set_rules('kode', 'kode', 'trim|required');
        $this->form_validation->set_rules('dari', 'dari', 'trim|required');
        // $this->form_validation->set_rules('uraian', 'uraian', 'trim|required');
        $this->form_validation->set_rules('nominal', 'nominal', 'trim|required|numeric');
        $this->form_validation->set_rules('bank', 'bank', 'trim|required');
        $this->form_validation->set_rules('nmr_rekening', 'nmr rekening', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "tbl_pendapatan_lain_lain.xls";
        $judul = "tbl_pendapatan_lain_lain";
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
        xlsWriteLabel($tablehead, $kolomhead++, "Uuid Pendapatan Lain Lain");
        xlsWriteLabel($tablehead, $kolomhead++, "Tgl Transaksi");
        xlsWriteLabel($tablehead, $kolomhead++, "Kode");
        xlsWriteLabel($tablehead, $kolomhead++, "Dari");
        xlsWriteLabel($tablehead, $kolomhead++, "Uraian");
        xlsWriteLabel($tablehead, $kolomhead++, "Nominal");
        xlsWriteLabel($tablehead, $kolomhead++, "Bank");
        xlsWriteLabel($tablehead, $kolomhead++, "Nmr Rekening");

        foreach ($this->Tbl_pendapatan_lain_lain_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->uuid_pendapatan_lain_lain);
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

/* End of file Tbl_pendapatan_lain_lain.php */
/* Location: ./application/controllers/Tbl_pendapatan_lain_lain.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-10-23 08:11:09 */
/* http://harviacode.com */