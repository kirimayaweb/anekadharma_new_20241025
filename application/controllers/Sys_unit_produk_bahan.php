<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sys_unit_produk_bahan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array('Sys_unit_produk_bahan_model', 'Sys_unit_produk_model','Persediaan_model'));
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    public function UpdateSatuanHargaSatuan()
    {

        $sql_data_bahan_produksi = "SELECT * FROM `sys_unit_produk_bahan` order by id";

        foreach ($this->db->query($sql_data_bahan_produksi)->result() as $list_data) {
            // filter data persediaan by UUID_PERSEDIAN dan cek satuan dan harga satuan kemudian update k eabel sys_unit_produk_bahan



        }
    }

    public function insertProdukBahan_to_sysUnitProduk()
    {
        $sql_data_bahan_produksi_group_by_unit_tgl_produksi = "SELECT * FROM `sys_unit_produk_bahan` GROUP BY `uuid_unit`,`tgl_transaksi`";
        // $get_data_produk_bahan = $this->db->query($sql_data_bahan_produksi_group_by_unit_tgl_produksi)->result();

        // `id`, ``, ``, ``, ``, ``, `uuid_produk`, `kode_barang_bahan`, `nama_barang_bahan`, `jumlah_bahan`, `satuan_bahan`, `harga_satuan_bahan`

        foreach ($this->db->query($sql_data_bahan_produksi_group_by_unit_tgl_produksi)->result() as $get_data_produk_bahan) {
            $data = array(
                'uuid_persediaan' => $get_data_produk_bahan->uuid_persediaan,
                'uuid_unit' => $get_data_produk_bahan->uuid_unit,
                'kode_unit' => $get_data_produk_bahan->kode_unit,
                'nama_unit' => $get_data_produk_bahan->nama_unit,
                'tgl_transaksi' => $get_data_produk_bahan->tgl_transaksi,
                // 'uuid_produk' => $get_data_produk_bahan->,
                // 'kode_barang' => $get_data_produk_bahan->,
                // 'nama_barang' => $get_data_produk_bahan->,
                // 'jumlah_produksi' => ,
                // 'satuan' => ,
                // 'harga_satuan' => ,
            );

            $this->Sys_unit_produk_model->insert($data);
        }
    }


    public function index()
    {
        $this->load->view('sys_unit_produk_bahan/sys_unit_produk_bahan_list');
    }



    public function json()
    {
        header('Content-Type: application/json');
        echo $this->Sys_unit_produk_bahan_model->json();
    }

    public function read($id)
    {
        $row = $this->Sys_unit_produk_bahan_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'uuid_unit' => $row->uuid_unit,
                'uuid_persediaan' => $row->uuid_persediaan,
                'kode_unit' => $row->kode_unit,
                'nama_unit' => $row->nama_unit,
                'tgl_transaksi' => $row->tgl_transaksi,
                'uuid_produk' => $row->uuid_produk,
                'kode_barang_bahan' => $row->kode_barang_bahan,
                'nama_barang_bahan' => $row->nama_barang_bahan,
                'jumlah_bahan' => $row->jumlah_bahan,
                'satuan_bahan' => $row->satuan_bahan,
                'harga_satuan_bahan' => $row->harga_satuan_bahan,
            );
            $this->load->view('sys_unit_produk_bahan/sys_unit_produk_bahan_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_unit_produk_bahan'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('sys_unit_produk_bahan/create_action'),
            'id' => set_value('id'),
            'uuid_unit' => set_value('uuid_unit'),
            'uuid_persediaan' => set_value('uuid_persediaan'),
            'kode_unit' => set_value('kode_unit'),
            'nama_unit' => set_value('nama_unit'),
            'tgl_transaksi' => set_value('tgl_transaksi'),
            'uuid_produk' => set_value('uuid_produk'),
            'kode_barang_bahan' => set_value('kode_barang_bahan'),
            'nama_barang_bahan' => set_value('nama_barang_bahan'),
            'jumlah_bahan' => set_value('jumlah_bahan'),
            'satuan_bahan' => set_value('satuan_bahan'),
            'harga_satuan_bahan' => set_value('harga_satuan_bahan'),
        );
        $this->load->view('sys_unit_produk_bahan/sys_unit_produk_bahan_form', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'uuid_unit' => $this->input->post('uuid_unit', TRUE),
                'uuid_persediaan' => $this->input->post('uuid_persediaan', TRUE),
                'kode_unit' => $this->input->post('kode_unit', TRUE),
                'nama_unit' => $this->input->post('nama_unit', TRUE),
                'tgl_transaksi' => $this->input->post('tgl_transaksi', TRUE),
                'uuid_produk' => $this->input->post('uuid_produk', TRUE),
                'kode_barang_bahan' => $this->input->post('kode_barang_bahan', TRUE),
                'nama_barang_bahan' => $this->input->post('nama_barang_bahan', TRUE),
                'jumlah_bahan' => $this->input->post('jumlah_bahan', TRUE),
                'satuan_bahan' => $this->input->post('satuan_bahan', TRUE),
                'harga_satuan_bahan' => $this->input->post('harga_satuan_bahan', TRUE),
            );

            $this->Sys_unit_produk_bahan_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('sys_unit_produk_bahan'));
        }
    }

    public function update($id)
    {
        $row = $this->Sys_unit_produk_bahan_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('sys_unit_produk_bahan/update_action'),
                'id' => set_value('id', $row->id),
                'uuid_unit' => set_value('uuid_unit', $row->uuid_unit),
                'uuid_persediaan' => set_value('uuid_persediaan', $row->uuid_persediaan),
                'kode_unit' => set_value('kode_unit', $row->kode_unit),
                'nama_unit' => set_value('nama_unit', $row->nama_unit),
                'tgl_transaksi' => set_value('tgl_transaksi', $row->tgl_transaksi),
                'uuid_produk' => set_value('uuid_produk', $row->uuid_produk),
                'kode_barang_bahan' => set_value('kode_barang_bahan', $row->kode_barang_bahan),
                'nama_barang_bahan' => set_value('nama_barang_bahan', $row->nama_barang_bahan),
                'jumlah_bahan' => set_value('jumlah_bahan', $row->jumlah_bahan),
                'satuan_bahan' => set_value('satuan_bahan', $row->satuan_bahan),
                'harga_satuan_bahan' => set_value('harga_satuan_bahan', $row->harga_satuan_bahan),
            );
            $this->load->view('sys_unit_produk_bahan/sys_unit_produk_bahan_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_unit_produk_bahan'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'uuid_unit' => $this->input->post('uuid_unit', TRUE),
                'uuid_persediaan' => $this->input->post('uuid_persediaan', TRUE),
                'kode_unit' => $this->input->post('kode_unit', TRUE),
                'nama_unit' => $this->input->post('nama_unit', TRUE),
                'tgl_transaksi' => $this->input->post('tgl_transaksi', TRUE),
                'uuid_produk' => $this->input->post('uuid_produk', TRUE),
                'kode_barang_bahan' => $this->input->post('kode_barang_bahan', TRUE),
                'nama_barang_bahan' => $this->input->post('nama_barang_bahan', TRUE),
                'jumlah_bahan' => $this->input->post('jumlah_bahan', TRUE),
                'satuan_bahan' => $this->input->post('satuan_bahan', TRUE),
                'harga_satuan_bahan' => $this->input->post('harga_satuan_bahan', TRUE),
            );

            $this->Sys_unit_produk_bahan_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('sys_unit_produk_bahan'));
        }
    }

    public function delete($id = null, $source_page_pref_1 = null, $source_page_pref_2 = null, $source_page_pref_3 = null)
    {

        // print_r($source_page_pref_1); //Untuk mengembalikan kembali ke halaman sys_unit_produk yang menghapus salah satu bahan, kembali ke halaman Sys_uni_produk/Create_produksi/id_persediaan_barang
        // print_r($source_page_pref_2); //Untuk mengembalikan kembali ke halaman sys_unit_produk yang menghapus salah satu bahan, kembali ke halaman Sys_uni_produk/Create_produksi/id_persediaan_barang
        // print_r($source_page_pref_3); //Untuk mengembalikan kembali ke halaman sys_unit_produk yang menghapus salah satu bahan, kembali ke halaman Sys_uni_produk/Create_produksi/id_persediaan_barang
        // // die;

        $row = $this->Sys_unit_produk_bahan_model->get_by_id($id);

        // print_r($row);
        // die;

        if ($row) {

            // GET ID PERSEDIAAN / UUID_PERSEDIAAN UNTUK REKALKULASI FIELD bahan_produksi ( mengurangi jumlah bahan_produksi )


            $GET_Detail_Data_Bahan = $this->Sys_unit_produk_bahan_model->get_by_id($id);

            // print_r("<br/>");
            // print_r($GET_Detail_Data_Bahan);
            // print_r("<br/>");

            $get_uuid_persediaan_bahan = $GET_Detail_Data_Bahan->uuid_persediaan_bahan;
            $GET_jumlah_bahan_yang_dihapus = $GET_Detail_Data_Bahan->jumlah_bahan;

            // print_r("<br/>");
            // print_r($get_uuid_persediaan_bahan);
            // print_r("<br/>");

            $GET_Jumlah_Bahan_Produksi = $this->Persediaan_model->get_by_uuid_persediaan($get_uuid_persediaan_bahan);
            // print_r($GET_Jumlah_Bahan_Produksi);
            // print_r("<br/>");
            // print_r($GET_Jumlah_Bahan_Produksi->bahan_produksi);
            // print_r("<br/>");

            $GET_id_persediaan_bahan=$GET_Jumlah_Bahan_Produksi->id;

            $GET_jumlah_bahan_setelah_dikurangi=$GET_Jumlah_Bahan_Produksi->bahan_produksi-$GET_jumlah_bahan_yang_dihapus;

            // print_r($GET_jumlah_bahan_setelah_dikurangi);
            // print_r("<br/>");

            // UPDATE jumlah field bahan_produksi di tabel persediaan

            $sql_update_uuid_persediaan = "UPDATE `persediaan` SET `bahan_produksi`='$GET_jumlah_bahan_setelah_dikurangi' WHERE `id`='$GET_id_persediaan_bahan'";
    
            $this->db->query($sql_update_uuid_persediaan);


            // die;






            $this->Sys_unit_produk_bahan_model->delete($id);



            $this->session->set_flashdata('message', 'Delete Record Success');
            if ($source_page_pref_1 == null) {

                redirect(site_url('sys_unit_produk_bahan'));
            } else {

                redirect(site_url($source_page_pref_1 . '/' . $source_page_pref_2 . '/' . $source_page_pref_3));
            }
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            if ($source_page_pref_1 == null) {

                redirect(site_url('sys_unit_produk_bahan'));
            } else {

                redirect(site_url($source_page_pref_1 . '/' . $source_page_pref_2 . '/' . $source_page_pref_3));
            }
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('uuid_unit', 'uuid unit', 'trim|required');
        $this->form_validation->set_rules('uuid_persediaan', 'uuid persediaan', 'trim|required');
        $this->form_validation->set_rules('kode_unit', 'kode unit', 'trim|required');
        $this->form_validation->set_rules('nama_unit', 'nama unit', 'trim|required');
        $this->form_validation->set_rules('tgl_transaksi', 'tgl transaksi', 'trim|required');
        $this->form_validation->set_rules('uuid_produk', 'uuid produk', 'trim|required');
        $this->form_validation->set_rules('kode_barang_bahan', 'kode barang bahan', 'trim|required');
        $this->form_validation->set_rules('nama_barang_bahan', 'nama barang bahan', 'trim|required');
        $this->form_validation->set_rules('jumlah_bahan', 'jumlah bahan', 'trim|required|numeric');
        $this->form_validation->set_rules('satuan_bahan', 'satuan bahan', 'trim|required');
        $this->form_validation->set_rules('harga_satuan_bahan', 'harga satuan bahan', 'trim|required|numeric');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "sys_unit_produk_bahan.xls";
        $judul = "sys_unit_produk_bahan";
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
        xlsWriteLabel($tablehead, $kolomhead++, "Uuid Unit");
        xlsWriteLabel($tablehead, $kolomhead++, "Uuid Persediaan");
        xlsWriteLabel($tablehead, $kolomhead++, "Kode Unit");
        xlsWriteLabel($tablehead, $kolomhead++, "Nama Unit");
        xlsWriteLabel($tablehead, $kolomhead++, "Tgl Transaksi");
        xlsWriteLabel($tablehead, $kolomhead++, "Uuid Produk");
        xlsWriteLabel($tablehead, $kolomhead++, "Kode Barang Bahan");
        xlsWriteLabel($tablehead, $kolomhead++, "Nama Barang Bahan");
        xlsWriteLabel($tablehead, $kolomhead++, "Jumlah Bahan");
        xlsWriteLabel($tablehead, $kolomhead++, "Satuan Bahan");
        xlsWriteLabel($tablehead, $kolomhead++, "Harga Satuan Bahan");

        foreach ($this->Sys_unit_produk_bahan_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->uuid_unit);
            xlsWriteLabel($tablebody, $kolombody++, $data->uuid_persediaan);
            xlsWriteLabel($tablebody, $kolombody++, $data->kode_unit);
            xlsWriteLabel($tablebody, $kolombody++, $data->nama_unit);
            xlsWriteLabel($tablebody, $kolombody++, $data->tgl_transaksi);
            xlsWriteLabel($tablebody, $kolombody++, $data->uuid_produk);
            xlsWriteLabel($tablebody, $kolombody++, $data->kode_barang_bahan);
            xlsWriteLabel($tablebody, $kolombody++, $data->nama_barang_bahan);
            xlsWriteNumber($tablebody, $kolombody++, $data->jumlah_bahan);
            xlsWriteLabel($tablebody, $kolombody++, $data->satuan_bahan);
            xlsWriteNumber($tablebody, $kolombody++, $data->harga_satuan_bahan);

            $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }
}

/* End of file Sys_unit_produk_bahan.php */
/* Location: ./application/controllers/Sys_unit_produk_bahan.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2025-01-16 00:08:29 */
/* http://harviacode.com */