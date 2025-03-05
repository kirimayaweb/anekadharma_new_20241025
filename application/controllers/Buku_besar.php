<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Buku_besar extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array('Tbl_pembelian_model', 'Buku_besar_model'));
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    public function index()
    {
        $data_Buku_besar = $this->Buku_besar_model->get_all_sort_by_tanggal();

        // $Tbl_pembelian = $this->Tbl_pembelian_model->get_all();
        $sql_pembelian = "SELECT tbl_pembelian.tgl_po as tanggal,        
		tbl_pembelian.uraian as keterangan,
		tbl_pembelian.jumlah as jumlah,
		tbl_pembelian.harga_satuan as harga_satuan,
		(tbl_pembelian.jumlah*tbl_pembelian.harga_satuan) as kredit,
		tbl_pembelian.kode_akun as kode_akun,
		tbl_pembelian.kode_pl as kode_pl,
		tbl_pembelian.kode_bb as kode_bb
		                    FROM tbl_pembelian    
		                    ORDER BY tbl_pembelian.tgl_po DESC, tbl_pembelian.kode_akun ASC";
        // print_r($this->db->query($sql_pembelian)->result());

        // print_r("<br/>");
        // print_r("<br/>");
        // print_r("<br/>");

        $sql_penjualan = "SELECT tbl_penjualan.tgl_jual as tanggal,        
		tbl_penjualan.nama_barang as keterangan,
		tbl_penjualan.jumlah as jumlah,
		tbl_penjualan.harga_satuan as harga_satuan,
		(tbl_penjualan.jumlah * tbl_penjualan.harga_satuan) as debet,
		tbl_penjualan.kode_akun as kode_akun,
		tbl_penjualan.kode_pl as kode_pl,
		tbl_penjualan.kode_bb as kode_bb
		                    FROM tbl_penjualan    
		                    ORDER BY tbl_penjualan.tgl_jual DESC, tbl_penjualan.kode_akun ASC";
        // print_r($this->db->query($sql_penjualan)->result());

        // SELECT ``,`nmrkirim`,``,`jumlah`,`harga_satuan`,`kode_akun` FROM `


        // die;

        $data = array(
            'data_Buku_besar' => $data_Buku_besar,
            'Data_pembelian' => $this->db->query($sql_pembelian)->result(),
            'Data_penjualan' => $this->db->query($sql_penjualan)->result(),
            'action' => site_url('Buku_besar/cari_kode_akun'),
        );
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/buku_besar/adminlte310_buku_besar_list', $data);
    }

    public function cari_data($kode_akun = null)
    {

        // print_r("cari_data");
        // print_r("<br/>");
        // print_r($kode_akun);
        // print_r("<br/>");
        // // die;

        // Get kode akun dari uuid_kode_akun
        // $this->db->where('uuid_kode_akun', $kode_akun);
        // $get_kode_akun = $this->db->get('sys_kode_akun')->row()->kode_akun;

        // print_r($get_kode_akun);



        if ($kode_akun) {

            // Get kode akun dari uuid_kode_akun
            $this->db->where('uuid_kode_akun', $kode_akun);
            $get_kode_akun = $this->db->get('sys_kode_akun')->row()->kode_akun;
            $get_nama_akun = $this->db->get('sys_kode_akun')->row()->nama_akun;

            // print_r($get_kode_akun);
        } else {
            redirect(site_url("Buku_besar"));
        }

        // die;

        $data_Buku_besar = $this->Buku_besar_model->get_all_sort_by_tanggal();

        // $Tbl_pembelian = $this->Tbl_pembelian_model->get_all();
        $sql_pembelian = "SELECT tbl_pembelian.tgl_po as tanggal,        
		tbl_pembelian.uraian as keterangan,
		tbl_pembelian.jumlah as jumlah,
		tbl_pembelian.harga_satuan as harga_satuan,
		(tbl_pembelian.jumlah*tbl_pembelian.harga_satuan) as kredit,
		tbl_pembelian.kode_akun as kode_akun
		                    FROM tbl_pembelian    
                            WHERE tbl_pembelian.kode_akun = '$get_kode_akun'
		                    ORDER BY tbl_pembelian.tgl_po DESC, tbl_pembelian.kode_akun ASC";
        // print_r($this->db->query($sql_pembelian)->result());

        // print_r("<br/>");
        // print_r("<br/>");
        // print_r("<br/>");

        $sql_penjualan = "SELECT tbl_penjualan.tgl_jual as tanggal,        
		tbl_penjualan.nama_barang as keterangan,
		tbl_penjualan.jumlah as jumlah,
		tbl_penjualan.harga_satuan as harga_satuan,
		(tbl_penjualan.jumlah * tbl_penjualan.harga_satuan) as debet,
		tbl_penjualan.kode_akun as kode_akun
		                    FROM tbl_penjualan    
                            WHERE tbl_penjualan.kode_akun = '$get_kode_akun'
		                    ORDER BY tbl_penjualan.tgl_jual DESC, tbl_penjualan.kode_akun ASC";
        // print_r($this->db->query($sql_penjualan)->result());

        // SELECT ``,`nmrkirim`,``,`jumlah`,`harga_satuan`,`kode_akun` FROM `


        // die;

        $data = array(
            'uuid_kode_akun' => $kode_akun,
            'kode_akun' => $get_kode_akun,
            'nama_akun' => $get_nama_akun,
            'data_Buku_besar' => $data_Buku_besar,
            'Data_pembelian' => $this->db->query($sql_pembelian)->result(),
            'Data_penjualan' => $this->db->query($sql_penjualan)->result(),
            'action' => site_url('Buku_besar/cari_kode_akun'),
        );
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/buku_besar/adminlte310_buku_besar_list', $data);
    }

    public function cari_kode_akun()
    {
        redirect(site_url('Buku_besar/cari_data/' . $this->input->post('kode_akun', TRUE)));
    }



    public function index_server_side()
    {
        $this->load->view('buku_besar/buku_besar_list');
    }

    public function json()
    {
        header('Content-Type: application/json');
        echo $this->Buku_besar_model->json();
    }

    public function read($id)
    {
        $row = $this->Buku_besar_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'uuid_buku_besar' => $row->uuid_buku_besar,
                'tanggal' => $row->tanggal,
                'kode_akun' => $row->kode_akun,
                'keterangan' => $row->keterangan,
                'kode' => $row->kode,
                'debet' => $row->debet,
                'kredit' => $row->kredit,
                'saldo' => $row->saldo,
            );
            $this->load->view('buku_besar/buku_besar_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('buku_besar'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('buku_besar/create_action'),
            'id' => set_value('id'),
            'uuid_buku_besar' => set_value('uuid_buku_besar'),
            'tanggal' => set_value('tanggal'),
            'kode_akun' => set_value('kode_akun'),
            'keterangan' => set_value('keterangan'),
            'kode' => set_value('kode'),
            'debet' => set_value('debet'),
            'kredit' => set_value('kredit'),
            'saldo' => set_value('saldo'),
        );
        $this->load->view('buku_besar/buku_besar_form', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'uuid_buku_besar' => $this->input->post('uuid_buku_besar', TRUE),
                'tanggal' => $this->input->post('tanggal', TRUE),
                'kode_akun' => $this->input->post('kode_akun', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
                'kode' => $this->input->post('kode', TRUE),
                'debet' => $this->input->post('debet', TRUE),
                'kredit' => $this->input->post('kredit', TRUE),
                'saldo' => $this->input->post('saldo', TRUE),
            );

            $this->Buku_besar_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('buku_besar'));
        }
    }

    public function update($id)
    {
        $row = $this->Buku_besar_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('buku_besar/update_action'),
                'id' => set_value('id', $row->id),
                'uuid_buku_besar' => set_value('uuid_buku_besar', $row->uuid_buku_besar),
                'tanggal' => set_value('tanggal', $row->tanggal),
                'kode_akun' => set_value('kode_akun', $row->kode_akun),
                'keterangan' => set_value('keterangan', $row->keterangan),
                'kode' => set_value('kode', $row->kode),
                'debet' => set_value('debet', $row->debet),
                'kredit' => set_value('kredit', $row->kredit),
                'saldo' => set_value('saldo', $row->saldo),
            );
            $this->load->view('buku_besar/buku_besar_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('buku_besar'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'uuid_buku_besar' => $this->input->post('uuid_buku_besar', TRUE),
                'tanggal' => $this->input->post('tanggal', TRUE),
                'kode_akun' => $this->input->post('kode_akun', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
                'kode' => $this->input->post('kode', TRUE),
                'debet' => $this->input->post('debet', TRUE),
                'kredit' => $this->input->post('kredit', TRUE),
                'saldo' => $this->input->post('saldo', TRUE),
            );

            $this->Buku_besar_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('buku_besar'));
        }
    }

    public function delete($id)
    {
        $row = $this->Buku_besar_model->get_by_id($id);

        if ($row) {
            $this->Buku_besar_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('buku_besar'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('buku_besar'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('uuid_buku_besar', 'uuid buku besar', 'trim|required');
        $this->form_validation->set_rules('tanggal', 'tanggal', 'trim|required');
        $this->form_validation->set_rules('kode_akun', 'kode akun', 'trim|required');
        $this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');
        $this->form_validation->set_rules('kode', 'kode', 'trim|required');
        $this->form_validation->set_rules('debet', 'debet', 'trim|required|numeric');
        $this->form_validation->set_rules('kredit', 'kredit', 'trim|required|numeric');
        $this->form_validation->set_rules('saldo', 'saldo', 'trim|required|numeric');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "buku_besar.xls";
        $judul = "buku_besar";
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
        xlsWriteLabel($tablehead, $kolomhead++, "Uuid Buku Besar");
        xlsWriteLabel($tablehead, $kolomhead++, "Tanggal");
        xlsWriteLabel($tablehead, $kolomhead++, "Kode Akun");
        xlsWriteLabel($tablehead, $kolomhead++, "Keterangan");
        xlsWriteLabel($tablehead, $kolomhead++, "Kode");
        xlsWriteLabel($tablehead, $kolomhead++, "Debet");
        xlsWriteLabel($tablehead, $kolomhead++, "Kredit");
        xlsWriteLabel($tablehead, $kolomhead++, "Saldo");

        foreach ($this->Buku_besar_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->uuid_buku_besar);
            xlsWriteLabel($tablebody, $kolombody++, $data->tanggal);
            xlsWriteLabel($tablebody, $kolombody++, $data->kode_akun);
            xlsWriteLabel($tablebody, $kolombody++, $data->keterangan);
            xlsWriteLabel($tablebody, $kolombody++, $data->kode);
            xlsWriteNumber($tablebody, $kolombody++, $data->debet);
            xlsWriteNumber($tablebody, $kolombody++, $data->kredit);
            xlsWriteNumber($tablebody, $kolombody++, $data->saldo);

            $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }
}

/* End of file Buku_besar.php */
/* Location: ./application/controllers/Buku_besar.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2025-02-10 08:11:36 */
/* http://harviacode.com */