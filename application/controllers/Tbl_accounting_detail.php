<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_accounting_detail extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model(array('Tbl_accounting_detail_model', 'Tbl_accounting_group_model'));
        $this->load->library('form_validation');
        $this->load->library('datatables');
        $this->load->library('Pdf');
        $this->load->helper(array('nominal'));
    }

    public function index()
    {
        $data_detail = $this->Tbl_accounting_detail_model->get_all();

        $data = array(
            'data_detail' => $data_detail,
            // 'start' => $start,
        );

        // $this->load->view('tbl_accounting_detail/tbl_accounting_detail_list');
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_accounting_detail/adminlte310_detail_list', $data);
    }

    public function json()
    {
        header('Content-Type: application/json');
        echo $this->Tbl_accounting_detail_model->json();
    }

    public function read($id)
    {
        $row = $this->Tbl_accounting_detail_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'date_input' => $row->date_input,
                'date_transaksi' => $row->date_transaksi,
                'tahun_transaksi' => $row->tahun_transaksi,
                'bulan_transaksi' => $row->bulan_transaksi,
                'uuid_accounting' => $row->uuid_accounting,
                'uuid_group' => $row->uuid_group,
                'nama_group' => $row->nama_group,
                'detail_transaksi' => $row->detail_transaksi,
                'nominal_transaksi' => $row->nominal_transaksi,
                'keterangan' => $row->keterangan,
                'id_usr' => $row->id_usr,
            );
            $this->load->view('tbl_accounting_detail/tbl_accounting_detail_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_accounting_detail'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Simpan',
            'action' => site_url('tbl_accounting_detail/create_action'),
            'id' => set_value('id'),
            'date_input' => set_value('date_input'),
            'date_transaksi' => set_value('date_transaksi'),
            'tahun_transaksi' => set_value('tahun_transaksi'),
            'bulan_transaksi' => set_value('bulan_transaksi'),
            'uuid_accounting' => set_value('uuid_accounting'),
            'uuid_group' => set_value('uuid_group'),
            'nama_group' => set_value('nama_group'),
            'detail_transaksi' => set_value('detail_transaksi'),
            'nominal_transaksi' => set_value('nominal_transaksi'),
            'keterangan' => set_value('keterangan'),
            'id_usr' => set_value('id_usr'),
        );
        // $this->load->view('tbl_accounting_detail/tbl_accounting_detail_form', $data);
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_accounting_detail/adminlte310_detail_form', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {


            if (date("Y", strtotime($this->input->post('date_transaksi', TRUE))) < 2020) {
                $get_date_transaksi = date("Y-m-d H:i:s");
                $get_TAHUN_date_transaksi = date("Y");
                $get_BULAN_date_transaksi = date("m");
            } else {
                $get_date_transaksi = date("Y-m-d H:i:s", strtotime($this->input->post('date_transaksi', TRUE)));
                $get_TAHUN_date_transaksi = date("Y", strtotime($this->input->post('date_transaksi', TRUE)));
                $get_BULAN_date_transaksi = date("m", strtotime($this->input->post('date_transaksi', TRUE)));
            }

            $row_per_uuid_group = $this->Tbl_accounting_group_model->get_by_uuid_group($this->input->post('uuid_group', TRUE));

            $data = array(
                'date_input' => date("Y-m-d H:i:s"),
                'date_transaksi' => $get_date_transaksi,
                'tahun_transaksi' => $get_TAHUN_date_transaksi,
                'bulan_transaksi' => $get_BULAN_date_transaksi,
                // 'uuid_accounting' => $this->input->post('uuid_accounting',TRUE),
                'uuid_group' => $this->input->post('uuid_group', TRUE),
                'nama_group' => $row_per_uuid_group->nama_group,
                'detail_transaksi' => $this->input->post('detail_transaksi', TRUE),
                'nominal_transaksi' => preg_replace("/[^0-9]/", "", $this->input->post('nominal_transaksi', TRUE)),
                'keterangan' => $this->input->post('keterangan', TRUE),
                'id_usr' => $this->input->post('id_usr', TRUE),
            );

            $this->Tbl_accounting_detail_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('tbl_accounting_detail'));
        }
    }

    public function update($id)
    {
        $row = $this->Tbl_accounting_detail_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('tbl_accounting_detail/update_action'),
                'id' => set_value('id', $row->id),
                'date_input' => set_value('date_input', $row->date_input),
                'date_transaksi' => set_value('date_transaksi', $row->date_transaksi),
                'tahun_transaksi' => set_value('tahun_transaksi', $row->tahun_transaksi),
                'bulan_transaksi' => set_value('bulan_transaksi', $row->bulan_transaksi),
                'uuid_accounting' => set_value('uuid_accounting', $row->uuid_accounting),
                'uuid_group' => set_value('uuid_group', $row->uuid_group),
                'nama_group' => set_value('nama_group', $row->nama_group),
                'detail_transaksi' => set_value('detail_transaksi', $row->detail_transaksi),
                'nominal_transaksi' => set_value('nominal_transaksi', $row->nominal_transaksi),
                'keterangan' => set_value('keterangan', $row->keterangan),
                'id_usr' => set_value('id_usr', $row->id_usr),
            );
            $this->load->view('tbl_accounting_detail/tbl_accounting_detail_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_accounting_detail'));
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
                // 'uuid_accounting' => $this->input->post('uuid_accounting',TRUE),
                'uuid_group' => $this->input->post('uuid_group', TRUE),
                'nama_group' => $this->input->post('nama_group', TRUE),
                'detail_transaksi' => $this->input->post('detail_transaksi', TRUE),
                'nominal_transaksi' => $this->input->post('nominal_transaksi', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
                'id_usr' => $this->input->post('id_usr', TRUE),
            );

            $this->Tbl_accounting_detail_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('tbl_accounting_detail'));
        }
    }

    public function delete($id)
    {
        $row = $this->Tbl_accounting_detail_model->get_by_id($id);

        if ($row) {
            $this->Tbl_accounting_detail_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('tbl_accounting_detail'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_accounting_detail'));
        }
    }

    public function _rules()
    {
        // $this->form_validation->set_rules('date_input', 'date input', 'trim|required');
        // $this->form_validation->set_rules('date_transaksi', 'date transaksi', 'trim|required');
        // $this->form_validation->set_rules('tahun_transaksi', 'tahun transaksi', 'trim|required');
        // $this->form_validation->set_rules('bulan_transaksi', 'bulan transaksi', 'trim|required');
        // $this->form_validation->set_rules('uuid_accounting', 'uuid accounting', 'trim|required');
        $this->form_validation->set_rules('uuid_group', 'uuid group', 'trim|required');
        // $this->form_validation->set_rules('nama_group', 'nama group', 'trim|required');
        $this->form_validation->set_rules('detail_transaksi', 'detail transaksi', 'trim|required');
        $this->form_validation->set_rules('nominal_transaksi', 'nominal transaksi', 'trim|required|numeric');
        // $this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');
        // $this->form_validation->set_rules('id_usr', 'id usr', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "tbl_accounting_detail.xls";
        $judul = "tbl_accounting_detail";
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
        xlsWriteLabel($tablehead, $kolomhead++, "Date Input");
        xlsWriteLabel($tablehead, $kolomhead++, "Date Transaksi");
        xlsWriteLabel($tablehead, $kolomhead++, "Tahun Transaksi");
        xlsWriteLabel($tablehead, $kolomhead++, "Bulan Transaksi");
        xlsWriteLabel($tablehead, $kolomhead++, "Uuid Accounting");
        xlsWriteLabel($tablehead, $kolomhead++, "Uuid Group");
        xlsWriteLabel($tablehead, $kolomhead++, "Nama Group");
        xlsWriteLabel($tablehead, $kolomhead++, "Detail Transaksi");
        xlsWriteLabel($tablehead, $kolomhead++, "Nominal Transaksi");
        xlsWriteLabel($tablehead, $kolomhead++, "Keterangan");
        xlsWriteLabel($tablehead, $kolomhead++, "Id Usr");

        foreach ($this->Tbl_accounting_detail_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->date_input);
            xlsWriteLabel($tablebody, $kolombody++, $data->date_transaksi);
            xlsWriteNumber($tablebody, $kolombody++, $data->tahun_transaksi);
            xlsWriteNumber($tablebody, $kolombody++, $data->bulan_transaksi);
            xlsWriteLabel($tablebody, $kolombody++, $data->uuid_accounting);
            xlsWriteLabel($tablebody, $kolombody++, $data->uuid_group);
            xlsWriteLabel($tablebody, $kolombody++, $data->nama_group);
            xlsWriteLabel($tablebody, $kolombody++, $data->detail_transaksi);
            xlsWriteNumber($tablebody, $kolombody++, $data->nominal_transaksi);
            xlsWriteLabel($tablebody, $kolombody++, $data->keterangan);
            xlsWriteNumber($tablebody, $kolombody++, $data->id_usr);

            $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }




}

/* End of file Tbl_accounting_detail.php */
/* Location: ./application/controllers/Tbl_accounting_detail.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-09-24 15:01:13 */
/* http://harviacode.com */