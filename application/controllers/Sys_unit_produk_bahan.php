<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sys_unit_produk_bahan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
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
        $row = $this->Sys_unit_produk_bahan_model->get_by_id($id);

        if ($row) {
            $this->_kembalikan_persediaan_saat_hapus_bahan($row);
            $this->Sys_unit_produk_bahan_model->delete($id);

            $this->session->set_flashdata('message', 'Delete Record Success');
            $this->_redirect_setelah_hapus_bahan($source_page_pref_1, $source_page_pref_2, $source_page_pref_3);
            return;
        }

        $this->session->set_flashdata('message', 'Record Not Found');
        $this->_redirect_setelah_hapus_bahan($source_page_pref_1, $source_page_pref_2, $source_page_pref_3);
    }

    private function _redirect_setelah_hapus_bahan($source_page_pref_1, $source_page_pref_2, $source_page_pref_3)
    {
        if ($source_page_pref_1 === null) {
            redirect(site_url('sys_unit_produk_bahan'));
            return;
        }

        $url = site_url($source_page_pref_1 . '/' . $source_page_pref_2 . '/' . $source_page_pref_3);
        $bulan = trim((string) $this->session->userdata('bulan_produksi_selected'));
        if ($bulan !== '' && preg_match('/^\d{4}-\d{2}$/', $bulan)) {
            $url .= '?bulan=' . urlencode($bulan);
        }
        redirect($url);
    }

    /**
     * Kembalikan stock bahan ke persediaan saat hapus 1 record sys_unit_produk_bahan.
     * Prioritas: id_persediaan → uuid_persediaan_bahan (bulan sama).
     * Jika persediaan tidak ditemukan, proses update diabaikan (record bahan tetap dihapus).
     */
    private function _kembalikan_persediaan_saat_hapus_bahan($row_bahan)
    {
        if (!$row_bahan) {
            return false;
        }

        $jumlah_bahan = $this->_parse_jumlah_angka(isset($row_bahan->jumlah_bahan) ? $row_bahan->jumlah_bahan : 0);
        if ($jumlah_bahan <= 0 || !$this->db->field_exists('bahan_produksi', 'persediaan')) {
            return false;
        }

        $id_persediaan = 0;
        if ($this->db->field_exists('id_persediaan', 'sys_unit_produk_bahan')) {
            $id_persediaan = isset($row_bahan->id_persediaan) ? (int) $row_bahan->id_persediaan : 0;
        }

        $row_persediaan = null;
        if ($id_persediaan > 0) {
            $row_persediaan = $this->Persediaan_model->get_by_id($id_persediaan);
        } else {
            $row_persediaan = $this->_cari_persediaan_bahan_fallback_uuid($row_bahan, $jumlah_bahan);
        }

        if (!$row_persediaan) {
            return false;
        }

        return $this->_update_persediaan_kembalikan_bahan((int) $row_persediaan->id, $jumlah_bahan);
    }

    private function _cari_persediaan_bahan_fallback_uuid($row_bahan, $jumlah_bahan)
    {
        $uuid_persediaan_bahan = trim((string) (isset($row_bahan->uuid_persediaan_bahan) ? $row_bahan->uuid_persediaan_bahan : ''));
        if ($uuid_persediaan_bahan === '') {
            return null;
        }

        $bulan_ym = $this->_bulan_ym_dari_tgl_transaksi(isset($row_bahan->tgl_transaksi) ? $row_bahan->tgl_transaksi : '');
        if ($bulan_ym === '') {
            $bulan_sess = trim((string) $this->session->userdata('bulan_produksi_selected'));
            if ($bulan_sess !== '' && preg_match('/^\d{4}-\d{2}$/', $bulan_sess)) {
                $bulan_ym = $bulan_sess;
            }
        }

        $this->db->from('persediaan');
        $this->db->where('uuid_persediaan', $uuid_persediaan_bahan);
        if ($bulan_ym !== '') {
            $this->db->where("DATE_FORMAT(tanggal_beli, '%Y-%m') =", $bulan_ym);
        }
        $this->db->where(
            'CAST(COALESCE(NULLIF(TRIM(bahan_produksi), \'\'), \'0\') AS UNSIGNED) >',
            (int) $jumlah_bahan
        );
        $this->db->order_by('bahan_produksi', 'DESC');
        $this->db->order_by('id', 'ASC');
        $this->db->limit(1);

        return $this->db->get()->row();
    }

    private function _update_persediaan_kembalikan_bahan($id_persediaan, $jumlah_bahan)
    {
        $id_persediaan = (int) $id_persediaan;
        $jumlah_bahan = $this->_parse_jumlah_angka($jumlah_bahan);
        if ($id_persediaan <= 0 || $jumlah_bahan <= 0) {
            return false;
        }

        $row = $this->Persediaan_model->get_by_id($id_persediaan);
        if (!$row) {
            return false;
        }

        $bahan_lama = $this->_parse_jumlah_angka(isset($row->bahan_produksi) ? $row->bahan_produksi : 0);
        $total_10_lama = $this->_parse_jumlah_angka(isset($row->total_10) ? $row->total_10 : 0);
        $hpp = (float) preg_replace('/[^0-9.]/', '', (string) (isset($row->hpp) ? $row->hpp : 0));

        $bahan_baru = max(0, $bahan_lama - $jumlah_bahan);
        $total_10_baru = $total_10_lama + $jumlah_bahan;
        $nilai_persediaan = (int) floor($total_10_baru * $hpp);

        $update = array(
            'bahan_produksi' => (string) $bahan_baru,
            'total_10' => (string) $total_10_baru,
        );
        if ($this->db->field_exists('nilai_persediaan', 'persediaan')) {
            $update['nilai_persediaan'] = (string) $nilai_persediaan;
        }
        if ($this->db->field_exists('tuj', 'persediaan')) {
            $update['tuj'] = (string) $total_10_baru;
        }

        $this->Persediaan_model->update($id_persediaan, $update);

        return true;
    }

    private function _bulan_ym_dari_tgl_transaksi($tgl_transaksi)
    {
        $tgl_transaksi = trim((string) $tgl_transaksi);
        if ($tgl_transaksi === '') {
            return '';
        }
        $ts = strtotime($tgl_transaksi);
        if (!$ts) {
            return '';
        }

        return date('Y-m', $ts);
    }

    private function _parse_jumlah_angka($value)
    {
        return (int) preg_replace('/[^0-9]/', '', (string) $value);
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