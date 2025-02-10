<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bukubank extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Bukubank_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    public function index(){
        $data_buku_bank = $this->Bukubank_model->get_all_sort_by_tanggal();
		// $start = 0;



		$data = array(
			'data_buku_bank' => $data_buku_bank,
			// 'start' => $start,
		);

		$this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/buku_bank/adminlte310_buku_bank_list', $data);
    }


    public function cek_data_csv(){
        $data_buku_bank = $this->Bukubank_model->get_all();
        foreach ($data_buku_bank as $list_data) {
            print_r($list_data->tanggal);
            print_r("<br/>");
            // $sub_kalimat = substr($list_data->tanggal,-4);
            // print_r("<br/>");
            print_r(substr($list_data->tanggal,-4));
            print_r("<br/>");
            print_r(date("Y", strtotime(intval( $list_data->tanggal ),-4)));
            print_r("<br/>");

        }
    }

    public function index_server_side()
    {
        $this->load->view('bukubank/bukubank_list');
    }

    public function json()
    {
        header('Content-Type: application/json');
        echo $this->Bukubank_model->json();
    }

    public function read($id)
    {
        $row = $this->Bukubank_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'tanggal' => $row->tanggal,
                'bank' => $row->bank,
                'norek' => $row->norek,
                'keterangan' => $row->keterangan,
                'kode' => $row->kode,
                'debet' => $row->debet,
                'kredit' => $row->kredit,
                'saldo' => $row->saldo,
            );
            $this->load->view('bukubank/bukubank_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('bukubank'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('bukubank/create_action'),
            'id' => set_value('id'),
            'tanggal' => set_value('tanggal'),
            'bank' => set_value('bank'),
            'norek' => set_value('norek'),
            'keterangan' => set_value('keterangan'),
            'kode' => set_value('kode'),
            'debet' => set_value('debet'),
            'kredit' => set_value('kredit'),
            'saldo' => set_value('saldo'),
        );
        // $this->load->view('bukubank/bukubank_form', $data);
        
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/buku_bank/adminlte310_buku_bank_form', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'tanggal' => $this->input->post('tanggal', TRUE),
                'bank' => $this->input->post('bank', TRUE),
                'norek' => $this->input->post('norek', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
                'kode' => $this->input->post('kode', TRUE),
                'debet' => $this->input->post('debet', TRUE),
                'kredit' => $this->input->post('kredit', TRUE),
                'saldo' => $this->input->post('saldo', TRUE),
            );

            $this->Bukubank_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('bukubank'));
        }
    }

    public function update($id)
    {
        $row = $this->Bukubank_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('bukubank/update_action'),
                'id' => set_value('id', $row->id),
                'tanggal' => set_value('tanggal', $row->tanggal),
                'bank' => set_value('bank', $row->bank),
                'norek' => set_value('norek', $row->norek),
                'keterangan' => set_value('keterangan', $row->keterangan),
                'kode' => set_value('kode', $row->kode),
                'debet' => set_value('debet', $row->debet),
                'kredit' => set_value('kredit', $row->kredit),
                'saldo' => set_value('saldo', $row->saldo),
            );
            $this->load->view('bukubank/bukubank_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('bukubank'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'tanggal' => $this->input->post('tanggal', TRUE),
                'bank' => $this->input->post('bank', TRUE),
                'norek' => $this->input->post('norek', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
                'kode' => $this->input->post('kode', TRUE),
                'debet' => $this->input->post('debet', TRUE),
                'kredit' => $this->input->post('kredit', TRUE),
                'saldo' => $this->input->post('saldo', TRUE),
            );

            $this->Bukubank_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('bukubank'));
        }
    }

    public function delete($id)
    {
        $row = $this->Bukubank_model->get_by_id($id);

        if ($row) {
            $this->Bukubank_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('bukubank'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('bukubank'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('tanggal', 'tanggal', 'trim|required');
        $this->form_validation->set_rules('bank', 'bank', 'trim|required');
        $this->form_validation->set_rules('norek', 'norek', 'trim|required');
        $this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');
        $this->form_validation->set_rules('kode', 'kode', 'trim|required');
        $this->form_validation->set_rules('debet', 'debet', 'trim|required');
        $this->form_validation->set_rules('kredit', 'kredit', 'trim|required');
        $this->form_validation->set_rules('saldo', 'saldo', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "bukubank.xls";
        $judul = "bukubank";
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
        xlsWriteLabel($tablehead, $kolomhead++, "Bank");
        xlsWriteLabel($tablehead, $kolomhead++, "Norek");
        xlsWriteLabel($tablehead, $kolomhead++, "Keterangan");
        xlsWriteLabel($tablehead, $kolomhead++, "Kode");
        xlsWriteLabel($tablehead, $kolomhead++, "Debet");
        xlsWriteLabel($tablehead, $kolomhead++, "Kredit");
        xlsWriteLabel($tablehead, $kolomhead++, "Saldo");

        foreach ($this->Bukubank_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->tanggal);
            xlsWriteLabel($tablebody, $kolombody++, $data->bank);
            xlsWriteLabel($tablebody, $kolombody++, $data->norek);
            xlsWriteLabel($tablebody, $kolombody++, $data->keterangan);
            xlsWriteNumber($tablebody, $kolombody++, $data->kode);
            xlsWriteLabel($tablebody, $kolombody++, $data->debet);
            xlsWriteLabel($tablebody, $kolombody++, $data->kredit);
            xlsWriteLabel($tablebody, $kolombody++, $data->saldo);

            $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }
}

/* End of file Bukubank.php */
/* Location: ./application/controllers/Bukubank.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-12-01 11:25:05 */
/* http://harviacode.com */