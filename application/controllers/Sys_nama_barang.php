<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sys_nama_barang extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Sys_nama_barang_model');
        $this->load->library('form_validation');
    }

    public function refresh_data_from_persediaan_data()
    {
        $sql = "SELECT `namabarang`,`satuan` FROM `persediaan` WHERE `namabarang`<>'' GROUP by `namabarang`";
       
        foreach ($this->db->query($sql)->result() as $m) {
            // echo "<option value='$m->uuid_barang' ";
            // echo ">  " . strtoupper($m->kode_barang) . strtoupper($m->namabarang)  . "</option>";
       
            $data = array(
                // 'uuid_barang' => $this->input->post('uuid_barang',TRUE),
                'kode_barang' => str_replace(" ","",$m->namabarang),
                'nama_barang' => $m->namabarang,
                'satuan' => $m->satuan,
                // 'keterangan' => $this->input->post('keterangan', TRUE),
            );

            $this->Sys_nama_barang_model->insert($data);
       
       
        }
print_r("selesai");



    }


    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        if ($q <> '') {
            $config['base_url'] = base_url() . 'sys_nama_barang/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'sys_nama_barang/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'sys_nama_barang/index.html';
            $config['first_url'] = base_url() . 'sys_nama_barang/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Sys_nama_barang_model->total_rows($q);
        $sys_nama_barang = $this->Sys_nama_barang_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'sys_nama_barang_data' => $sys_nama_barang,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        // $this->load->view('sys_nama_barang/sys_nama_barang_list', $data);
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_nama_barang/sys_nama_barang_list', $data);
    }

    public function read($id)
    {
        $row = $this->Sys_nama_barang_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'uuid_barang' => $row->uuid_barang,
                'kode_barang' => $row->kode_barang,
                'nama_barang' => $row->nama_barang,
                'satuan' => $row->satuan,
                'keterangan' => $row->keterangan,
            );
            $this->load->view('sys_nama_barang/sys_nama_barang_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_nama_barang'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('sys_nama_barang/create_action'),
            'id' => set_value('id'),
            'uuid_barang' => set_value('uuid_barang'),
            'kode_barang' => set_value('kode_barang'),
            'nama_barang' => set_value('nama_barang'),
            'satuan' => set_value('satuan'),
            'keterangan' => set_value('keterangan'),
        );
        // $this->load->view('sys_nama_barang/sys_nama_barang_form', $data);
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_nama_barang/sys_nama_barang_form', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                // 'uuid_barang' => $this->input->post('uuid_barang',TRUE),
                'kode_barang' => $this->input->post('kode_barang', TRUE),
                'nama_barang' => $this->input->post('nama_barang', TRUE),
                'satuan' => $this->input->post('satuan', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
            );

            $this->Sys_nama_barang_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('sys_nama_barang'));
        }
    }

    public function update($id)
    {
        $row = $this->Sys_nama_barang_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('sys_nama_barang/update_action'),
                'id' => set_value('id', $row->id),
                // 'uuid_barang' => set_value('uuid_barang', $row->uuid_barang),
                'kode_barang' => set_value('kode_barang', $row->kode_barang),
                'nama_barang' => set_value('nama_barang', $row->nama_barang),
                'satuan' => set_value('satuan', $row->satuan),
                'keterangan' => set_value('keterangan', $row->keterangan),
            );
            $this->load->view('sys_nama_barang/sys_nama_barang_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_nama_barang'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                // 'uuid_barang' => $this->input->post('uuid_barang',TRUE),
                'kode_barang' => $this->input->post('kode_barang', TRUE),
                'nama_barang' => $this->input->post('nama_barang', TRUE),
                'satuan' => $this->input->post('satuan', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
            );

            $this->Sys_nama_barang_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('sys_nama_barang'));
        }
    }

    public function delete($id)
    {
        $row = $this->Sys_nama_barang_model->get_by_id($id);

        if ($row) {
            $this->Sys_nama_barang_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('sys_nama_barang'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_nama_barang'));
        }
    }

    public function _rules()
    {
        // $this->form_validation->set_rules('uuid_barang', 'uuid barang', 'trim|required');
        // $this->form_validation->set_rules('kode_barang', 'kode barang', 'trim|required');
        $this->form_validation->set_rules('nama_barang', 'nama barang', 'trim|required');
        // $this->form_validation->set_rules('satuan', 'satuan', 'trim|required');
        // $this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "sys_nama_barang.xls";
        $judul = "sys_nama_barang";
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
        xlsWriteLabel($tablehead, $kolomhead++, "Uuid Barang");
        xlsWriteLabel($tablehead, $kolomhead++, "Kode Barang");
        xlsWriteLabel($tablehead, $kolomhead++, "Nama Barang");
        xlsWriteLabel($tablehead, $kolomhead++, "Satuan");
        xlsWriteLabel($tablehead, $kolomhead++, "Keterangan");

        foreach ($this->Sys_nama_barang_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->uuid_barang);
            xlsWriteLabel($tablebody, $kolombody++, $data->kode_barang);
            xlsWriteLabel($tablebody, $kolombody++, $data->nama_barang);
            xlsWriteLabel($tablebody, $kolombody++, $data->satuan);
            xlsWriteLabel($tablebody, $kolombody++, $data->keterangan);

            $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }
}

/* End of file Sys_nama_barang.php */
/* Location: ./application/controllers/Sys_nama_barang.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-09-11 15:47:14 */
/* http://harviacode.com */