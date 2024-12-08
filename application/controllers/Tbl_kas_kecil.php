<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_kas_kecil extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model(array('Tbl_kas_kecil_model','Sys_unit_model'));
        $this->load->library('form_validation');
        $this->load->library('datatables');
        $this->load->helper(array('nominal'));
    }

    public function indexSERVERSIDE()
    {
        $this->load->view('tbl_kas_kecil/tbl_kas_kecil_list');
    }

    public function index(){
        $Tbl_kas_kecil = $this->Tbl_kas_kecil_model->get_all();
		// $start = 0;
		$data = array(
			'Tbl_kas_kecil_data' => $Tbl_kas_kecil,
			// 'start' => $start,
		);
		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_kas_kecil/adminlte310_tbl_kas_kecil_list', $data);
    }

    public function json()
    {
        header('Content-Type: application/json');
        echo $this->Tbl_kas_kecil_model->json();
    }

    public function read($id)
    {
        $row = $this->Tbl_kas_kecil_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'uuid_kas_kecil' => $row->uuid_kas_kecil,
                'tanggal' => $row->tanggal,
                'unit' => $row->unit,
                'keterangan' => $row->keterangan,
                'debet' => $row->debet,
                'kredit' => $row->kredit,
                'saldo' => $row->saldo,
                'id_usr' => $row->id_usr,
            );
            $this->load->view('tbl_kas_kecil/tbl_kas_kecil_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_kas_kecil'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Simpan',
            'action' => site_url('tbl_kas_kecil/create_action'),
            'id' => set_value('id'),
            'uuid_kas_kecil' => set_value('uuid_kas_kecil'),
            'tanggal' => set_value('tanggal'),
            'unit' => set_value('unit'),
            'keterangan' => set_value('keterangan'),
            'debet' => set_value('debet'),
            'kredit' => set_value('kredit'),
            'saldo' => set_value('saldo'),
            'id_usr' => set_value('id_usr'),
        );
        // $this->load->view('tbl_kas_kecil/tbl_kas_kecil_form', $data);
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_kas_kecil/adminlte310_tbl_kas_kecil_form', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {

            
            $row_unit = $this->Sys_unit_model->get_by_uuid_unit($this->input->post('unit', TRUE));


            if (date("Y", strtotime($this->input->post('tanggal', TRUE))) < 2020) {
                $date_kas_kecil = date("Y-m-d H:i:s");
            } else {
                $date_kas_kecil = date("Y-m-d H:i:s", strtotime($this->input->post('tanggal', TRUE)));
            }



            $data = array(
                // 'uuid_kas_kecil' => $this->input->post('uuid_kas_kecil', TRUE),
                'tanggal' => $date_kas_kecil,
                'unit' => $row_unit->nama_unit,
                'keterangan' => $this->input->post('keterangan', TRUE),
                'debet' => preg_replace("/[^0-9]/", "", $this->input->post('debet', TRUE)),
                'kredit' => preg_replace("/[^0-9]/", "", $this->input->post('kredit', TRUE)),
                // 'saldo' => $this->input->post('saldo', TRUE),
                // 'id_usr' => $this->input->post('id_usr', TRUE),
            );

           

            $this->Tbl_kas_kecil_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('tbl_kas_kecil'));
        }
    }

    public function update($id)
    {
        $row = $this->Tbl_kas_kecil_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('tbl_kas_kecil/update_action'),
                'id' => set_value('id', $row->id),
                'uuid_kas_kecil' => set_value('uuid_kas_kecil', $row->uuid_kas_kecil),
                'tanggal' => set_value('tanggal', $row->tanggal),
                'unit' => set_value('unit', $row->unit),
                'keterangan' => set_value('keterangan', $row->keterangan),
                'debet' => set_value('debet', $row->debet),
                'kredit' => set_value('kredit', $row->kredit),
                'saldo' => set_value('saldo', $row->saldo),
                'id_usr' => set_value('id_usr', $row->id_usr),
            );
            $this->load->view('tbl_kas_kecil/tbl_kas_kecil_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_kas_kecil'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'uuid_kas_kecil' => $this->input->post('uuid_kas_kecil', TRUE),
                'tanggal' => $this->input->post('tanggal', TRUE),
                'unit' => $this->input->post('unit', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
                'debet' => $this->input->post('debet', TRUE),
                'kredit' => $this->input->post('kredit', TRUE),
                'saldo' => $this->input->post('saldo', TRUE),
                'id_usr' => $this->input->post('id_usr', TRUE),
            );

            $this->Tbl_kas_kecil_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('tbl_kas_kecil'));
        }
    }

    public function delete($id)
    {
        $row = $this->Tbl_kas_kecil_model->get_by_id($id);

        if ($row) {
            $this->Tbl_kas_kecil_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('tbl_kas_kecil'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_kas_kecil'));
        }
    }

    public function _rules()
    {
        // $this->form_validation->set_rules('uuid_kas_kecil', 'uuid kas kecil', 'trim|required');
        $this->form_validation->set_rules('tanggal', 'tanggal', 'trim|required');
        $this->form_validation->set_rules('unit', 'unit', 'trim|required');
        $this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');
        // $this->form_validation->set_rules('debet', 'debet', 'trim|required|numeric');
        // $this->form_validation->set_rules('kredit', 'kredit', 'trim|required|numeric');
        // $this->form_validation->set_rules('saldo', 'saldo', 'trim|required|numeric');
        // $this->form_validation->set_rules('id_usr', 'id usr', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "tbl_kas_kecil.xls";
        $judul = "tbl_kas_kecil";
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
        xlsWriteLabel($tablehead, $kolomhead++, "Uuid Kas Kecil");
        xlsWriteLabel($tablehead, $kolomhead++, "Tanggal");
        xlsWriteLabel($tablehead, $kolomhead++, "Unit");
        xlsWriteLabel($tablehead, $kolomhead++, "Keterangan");
        xlsWriteLabel($tablehead, $kolomhead++, "Debet");
        xlsWriteLabel($tablehead, $kolomhead++, "Kredit");
        xlsWriteLabel($tablehead, $kolomhead++, "Saldo");
        xlsWriteLabel($tablehead, $kolomhead++, "Id Usr");

        foreach ($this->Tbl_kas_kecil_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->uuid_kas_kecil);
            xlsWriteLabel($tablebody, $kolombody++, $data->tanggal);
            xlsWriteLabel($tablebody, $kolombody++, $data->unit);
            xlsWriteLabel($tablebody, $kolombody++, $data->keterangan);
            xlsWriteNumber($tablebody, $kolombody++, $data->debet);
            xlsWriteNumber($tablebody, $kolombody++, $data->kredit);
            xlsWriteNumber($tablebody, $kolombody++, $data->saldo);
            xlsWriteNumber($tablebody, $kolombody++, $data->id_usr);

            $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }
}

/* End of file Tbl_kas_kecil.php */
/* Location: ./application/controllers/Tbl_kas_kecil.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-12-08 13:38:25 */
/* http://harviacode.com */