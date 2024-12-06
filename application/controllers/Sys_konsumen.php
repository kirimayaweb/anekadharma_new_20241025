<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sys_konsumen extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Sys_konsumen_model');
        $this->load->library('form_validation');
    }

    public function refresh_data_sys_konsumen() {
        $sql = "SELECT * FROM `sys_konsumen_new` ORDER BY nama_konsumen asc";

        foreach ($this->db->query($sql)->result() as $m) {

            $data = array(
                // 'uuid_supplier' => $this->input->post('uuid_supplier',TRUE),
                'kode_konsumen' => str_replace(" ","",$m->nama_konsumen),
                'nama_konsumen' => $m->nama_konsumen,
                'kelompok_dipersediaan' => $m->kelompok_dipersediaan,
                'nmr_kontak_konsumen' => $m->nmr_kontak_konsumen,
                'alamat_konsumen' => $m->alamat_konsumen,
                // 'keterangan' => $this->input->post('keterangan', TRUE),
            );

            $this->Sys_konsumen_model->insert($data);

        }

        print_r("Selesai insert sys_konsumen_new ke sys_konsumen");
    }

    public function indexXXXX()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        if ($q <> '') {
            $config['base_url'] = base_url() . 'sys_konsumen/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'sys_konsumen/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'sys_konsumen/index.html';
            $config['first_url'] = base_url() . 'sys_konsumen/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Sys_konsumen_model->total_rows($q);
        $sys_konsumen = $this->Sys_konsumen_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'sys_konsumen_data' => $sys_konsumen,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        // $this->load->view('sys_konsumen/sys_konsumen_list', $data);
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_konsumen/sys_konsumen_list', $data);
    }


    public function index()
    {
        // $this->load->view('sys_gudang/sys_gudang_list');

        $data_konsumen = $this->Sys_konsumen_model->get_all();

        $data = array(
            'data_konsumen' => $data_konsumen,
            'action' => site_url('Sys_konsumen/cari_unit'),
        );




        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_konsumen/adminlte310_sys_konsumen_list', $data);
    }

    public function read($id)
    {
        $row = $this->Sys_konsumen_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'uuid_konsumen' => $row->uuid_konsumen,
                'kode_konsumen' => $row->kode_konsumen,
                'nama_konsumen' => $row->nama_konsumen,
                'nmr_kontak_konsumen' => $row->nmr_kontak_konsumen,
                'alamat_konsumen' => $row->alamat_konsumen,
                'keterangan' => $row->keterangan,
            );
            $this->load->view('sys_konsumen/sys_konsumen_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_konsumen'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('sys_konsumen/create_action'),
            'id' => set_value('id'),
            'uuid_konsumen' => set_value('uuid_konsumen'),
            'kode_konsumen' => set_value('kode_konsumen'),
            'nama_konsumen' => set_value('nama_konsumen'),
            'nmr_kontak_konsumen' => set_value('nmr_kontak_konsumen'),
            'alamat_konsumen' => set_value('alamat_konsumen'),
            'keterangan' => set_value('keterangan'),
        );
        // $this->load->view('sys_konsumen/sys_konsumen_form', $data);
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_konsumen/adminlte310_sys_konsumen_form', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                // 'uuid_konsumen' => $this->input->post('uuid_konsumen',TRUE),
                'kode_konsumen' => $this->input->post('kode_konsumen', TRUE),
                'nama_konsumen' => $this->input->post('nama_konsumen', TRUE),
                'nmr_kontak_konsumen' => $this->input->post('nmr_kontak_konsumen', TRUE),
                'alamat_konsumen' => $this->input->post('alamat_konsumen', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
            );

            $this->Sys_konsumen_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('sys_konsumen'));
        }
    }

    public function update($id)
    {
        $row = $this->Sys_konsumen_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('sys_konsumen/update_action'),
                'id' => set_value('id', $row->id),
                'uuid_konsumen' => set_value('uuid_konsumen', $row->uuid_konsumen),
                'kode_konsumen' => set_value('kode_konsumen', $row->kode_konsumen),
                'nama_konsumen' => set_value('nama_konsumen', $row->nama_konsumen),
                'nmr_kontak_konsumen' => set_value('nmr_kontak_konsumen', $row->nmr_kontak_konsumen),
                'alamat_konsumen' => set_value('alamat_konsumen', $row->alamat_konsumen),
                'keterangan' => set_value('keterangan', $row->keterangan),
            );
            $this->load->view('sys_konsumen/sys_konsumen_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_konsumen'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                // 'uuid_konsumen' => $this->input->post('uuid_konsumen',TRUE),
                'kode_konsumen' => $this->input->post('kode_konsumen', TRUE),
                'nama_konsumen' => $this->input->post('nama_konsumen', TRUE),
                'nmr_kontak_konsumen' => $this->input->post('nmr_kontak_konsumen', TRUE),
                'alamat_konsumen' => $this->input->post('alamat_konsumen', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
            );

            $this->Sys_konsumen_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('sys_konsumen'));
        }
    }

    public function delete($id)
    {
        $row = $this->Sys_konsumen_model->get_by_id($id);

        if ($row) {
            $this->Sys_konsumen_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('sys_konsumen'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_konsumen'));
        }
    }

    public function _rules()
    {
        // $this->form_validation->set_rules('uuid_konsumen', 'uuid konsumen', 'trim|required');
        // $this->form_validation->set_rules('kode_konsumen', 'kode konsumen', 'trim|required');
        $this->form_validation->set_rules('nama_konsumen', 'nama konsumen', 'trim|required');
        // $this->form_validation->set_rules('nmr_kontak_konsumen', 'nmr kontak konsumen', 'trim|required');
        // $this->form_validation->set_rules('alamat_konsumen', 'alamat konsumen', 'trim|required');
        // $this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "sys_konsumen.xls";
        $judul = "sys_konsumen";
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
        xlsWriteLabel($tablehead, $kolomhead++, "Uuid Konsumen");
        xlsWriteLabel($tablehead, $kolomhead++, "Kode Konsumen");
        xlsWriteLabel($tablehead, $kolomhead++, "Nama Konsumen");
        xlsWriteLabel($tablehead, $kolomhead++, "Nmr Kontak Konsumen");
        xlsWriteLabel($tablehead, $kolomhead++, "Alamat Konsumen");
        xlsWriteLabel($tablehead, $kolomhead++, "Keterangan");

        foreach ($this->Sys_konsumen_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->uuid_konsumen);
            xlsWriteNumber($tablebody, $kolombody++, $data->kode_konsumen);
            xlsWriteLabel($tablebody, $kolombody++, $data->nama_konsumen);
            xlsWriteLabel($tablebody, $kolombody++, $data->nmr_kontak_konsumen);
            xlsWriteLabel($tablebody, $kolombody++, $data->alamat_konsumen);
            xlsWriteLabel($tablebody, $kolombody++, $data->keterangan);

            $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }
}

/* End of file Sys_konsumen.php */
/* Location: ./application/controllers/Sys_konsumen.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-09-05 09:25:47 */
/* http://harviacode.com */