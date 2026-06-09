<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sys_unit_produk extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model(array('Sys_unit_produk_model', 'Sys_unit_model', 'Persediaan_model', 'Sys_konsumen_model', 'Tbl_pembelian_model', 'Sys_unit_produk_bahan_model'));
        $this->load->library('form_validation');
        $this->load->library('datatables');
        $this->load->library('Pdf');
        $this->load->helper(array('nominal'));
    }

    public function indexXBU()
    {
        $this->load->view('sys_unit_produk/sys_unit_produk_list');
    }



    public function index()
    {
        $bulan_selected = $this->_resolve_bulan_produksi_selected($this->input->get('bulan', TRUE));
        $Sys_unit_produk_data = $this->Sys_unit_produk_model->get_by_bulan_ym($bulan_selected);

        $data = array(
            'action' => site_url('Sys_unit_produk/simpan_produk_baru'),
            'Sys_unit_produk_data' => $Sys_unit_produk_data,
            'bulan_produksi_selected' => $bulan_selected,
            'url_ajax_list_by_bulan' => site_url('Sys_unit_produk/ajax_list_by_bulan'),
            'url_create_produksi' => site_url('Sys_unit_produk/create_produksi'),
        );
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_unit_produk/adminlte310_sys_unit_produk_list', $data);
    }

    public function ajax_list_by_bulan()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }
        header('Content-Type: application/json; charset=utf-8');
        $bulan_ym = trim((string) $this->input->get('bulan', TRUE));
        if (!$bulan_ym || !preg_match('/^\d{4}-\d{2}$/', $bulan_ym)) {
            echo json_encode(array(
                'ok' => false,
                'message' => 'Format bulan tidak valid (YYYY-MM).',
            ));
            return;
        }
        $this->session->set_userdata('bulan_produksi_selected', $bulan_ym);
        $Sys_unit_produk_data = $this->Sys_unit_produk_model->get_by_bulan_ym($bulan_ym);
        echo json_encode(array(
            'ok' => true,
            'bulan_label' => date('m/Y', strtotime($bulan_ym . '-01')),
            'rows' => $this->_produksi_list_rows($Sys_unit_produk_data),
        ));
    }

    public function ajax_stock_persediaan_by_bulan()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }
        header('Content-Type: application/json; charset=utf-8');
        $bulan_ym = trim((string) $this->input->get('bulan', TRUE));
        if (!$bulan_ym || !preg_match('/^\d{4}-\d{2}$/', $bulan_ym)) {
            echo json_encode(array(
                'ok' => false,
                'message' => 'Format bulan tidak valid (YYYY-MM).',
            ));
            return;
        }
        $this->session->set_userdata('bulan_produksi_selected', $bulan_ym);
        $id_persediaan_barang = $this->input->get('id_persediaan_barang', TRUE);
        $action_simpan_bahan = $id_persediaan_barang
            ? site_url('sys_unit_produk/create_action_produksi_input_bahan/' . $id_persediaan_barang)
            : site_url('sys_unit_produk/create_action_produksi_input_bahan/');
        $view_data = array(
            'Data_stock' => $this->_get_stock_persediaan_by_bulan($bulan_ym),
            'action_simpan_bahan' => $action_simpan_bahan,
            'tgl_transaksi_bahan' => $bulan_ym . '-01',
            'bulan_produksi_ym' => $bulan_ym,
        );
        echo json_encode(array(
            'ok' => true,
            'bulan_label' => $this->_bulan_nama_indonesia($bulan_ym),
            'html' => $this->load->view('anekadharma/sys_unit_produk/_partial_modal_stock_persediaan_table', $view_data, true),
        ));
    }

    private function _produksi_list_rows($Sys_unit_produk_data)
    {
        $rows = array();
        $start = 0;
        foreach ($Sys_unit_produk_data as $list_data) {
            $this->db->where('uuid_persediaan', $list_data->uuid_persediaan);
            $persediaan_nama_barang = $this->db->get('persediaan');
            $persediaan_row = $persediaan_nama_barang->row();
            $spop = $persediaan_row ? $persediaan_row->spop : '';
            $persediaan_id = $persediaan_row ? (int) $persediaan_row->id : 0;

            $rows[] = array(
                'no' => ++$start,
                'edit_url' => site_url('Sys_unit_produk/update_produksi/' . $persediaan_id),
                'tgl_transaksi' => date('d-M-Y', strtotime($list_data->tgl_transaksi)),
                'spop' => $spop,
                'nama_unit' => $list_data->nama_unit,
                'nama_barang' => $list_data->nama_barang,
                'jumlah_produksi' => $list_data->jumlah_produksi,
                'satuan' => $list_data->satuan,
                'harga_satuan' => number_format($list_data->harga_satuan, 2, ',', '.'),
            );
        }
        return $rows;
    }


    public function simpan_produk_baru()
    {
        if (date("Y", strtotime($this->input->post('tgl_transaksi', TRUE))) < 2020) {
            $date_tgl_produksi = date("Y-m-d H:i:s");
        } else {
            $date_tgl_produksi = date("Y-m-d H:i:s", strtotime($this->input->post('tgl_transaksi', TRUE)));
        }

        $row_barang = $this->_resolve_uuid_barang_produksi(
            $this->input->post('nama_barang', TRUE),
            $this->input->post('satuan', TRUE)
        );

        $data = array(
            'tanggal' => $date_tgl_produksi,
            'kode' => $row_barang->kode_barang,
            'uuid_barang' => $row_barang->uuid_barang,
            'namabarang' => $row_barang->nama_barang,
            'satuan' => $this->input->post('satuan', TRUE),
            'hpp' => $this->input->post('harga_satuan', TRUE),
            'sa' => preg_replace("/[^0-9]/", "", $this->input->post('jumlah_produksi', TRUE)),
            'tanggal_beli' => $this->_tanggal_beli_bulan_dari_transaksi($this->input->post('tgl_transaksi', TRUE)),
            'spop' => "produksi",
            'total_10' => preg_replace("/[^0-9]/", "", $this->input->post('jumlah_produksi', TRUE)),
        );
        if ($this->db->field_exists('kode_barang', 'persediaan')) {
            $data['kode_barang'] = $row_barang->kode_barang;
        }
        $id_persediaan_barang = $this->Persediaan_model->insert_produk_baru($data);

        $sql_data_persediaan = "SELECT * FROM `persediaan` WHERE `id`='$id_persediaan_barang'";
        $get_uuid_persediaan = $this->db->query($sql_data_persediaan)->row()->uuid_persediaan;

        $data_unit = $this->Sys_unit_model->get_by_uuid_unit($this->input->post('uuid_unit', TRUE));

        $data = array(
            'uuid_persediaan' => $get_uuid_persediaan,
            'uuid_unit' => $data_unit->uuid_unit,
            'kode_unit' => $data_unit->kode_unit,
            'nama_unit' => $data_unit->nama_unit,
            'tgl_transaksi' => $date_tgl_produksi,
            'uuid_produk' => $row_barang->uuid_barang,
            'kode_barang' => $row_barang->kode_barang,
            'nama_barang' => $row_barang->nama_barang,
            'jumlah_produksi' => $this->input->post('jumlah_produksi', TRUE),
            'satuan' => $this->input->post('satuan', TRUE),
            'harga_satuan' => $this->input->post('harga_satuan', TRUE),
        );

        $this->Sys_unit_produk_model->insert($data);
        $this->session->set_flashdata('message', 'Create Record Success');
        redirect(site_url('Sys_unit_produk/create_produksi/' . $id_persediaan_barang));
    }


    public function json()
    {
        header('Content-Type: application/json');
        echo $this->Sys_unit_produk_model->json();
    }

    public function read($id)
    {
        $row = $this->Sys_unit_produk_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'uuid_unit' => $row->uuid_unit,
                'kode_unit' => $row->kode_unit,
                'nama_unit' => $row->nama_unit,
                'tgl_transaksi' => $row->tgl_transaksi,
                'uuid_barang' => $row->uuid_barang,
                'kode_barang' => $row->kode_barang,
                'nama_barang' => $row->nama_barang,
                'jumlah_produksi' => $row->jumlah_produksi,
                'satuan' => $row->satuan,
                'harga_satuan' => $row->harga_satuan,
            );
            $this->load->view('sys_unit_produk/sys_unit_produk_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_unit_produk'));
        }
    }




    public function create()
    {

        if ($this->input->post('uuid_unit', TRUE)) {

            $data_unit = $this->Sys_unit_model->get_by_uuid_unit($this->input->post('uuid_unit', TRUE));

            $data = array(
                'button' => 'Simpan',
                'action' => site_url('sys_unit_produk/create_action_produksi/' . $this->input->post('uuid_unit', TRUE)),
                // 'action_bahan' => site_url('sys_unit_produk/create_action_simpan_bahan/' . $this->input->post('uuid_unit', TRUE)),
                'id' => set_value('id'),
                'uuid_unit' => $this->input->post('uuid_unit', TRUE),
                'kode_unit' => set_value('kode_unit'),
                'nama_unit' => $data_unit->nama_unit,
                'tgl_transaksi' => set_value('tgl_transaksi'),
                'uuid_barang' => set_value('uuid_barang'),
                'kode_barang' => set_value('kode_barang'),
                'nama_barang' => set_value('nama_barang'),
                'jumlah_produksi' => set_value('jumlah_produksi'),
                'satuan' => set_value('satuan'),
                'harga_satuan' => set_value('harga_satuan'),
            );

            // $this->load->view('sys_unit_produk/sys_unit_produk_form', $data);

            $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_unit_produk/adminlte310_sys_unit_produk_form', $data);
        } else {
            redirect(site_url('sys_unit_produk'));
        }
    }

    public function create_action_simpan_bahan($uuid_unit = null, $id_proses = null)
    {

        // print_r("create_action_simpan_bahan");
        // print_r("<br/>");
        // print_r($uuid_unit);
        // print_r("<br/>");
        // print_r($id_proses);
        // print_r("<br/>");
        // print_r($this->input->post('uuid_konsumen', TRUE));
        // print_r("<br/>");


        // print_r("create_action_simpan_barang");
        // die;
        // print_r("<br/>");

        // print_r("tgl_jual : ");
        // print_r($this->input->post('tgl_jual', TRUE));
        // print_r("<br/>");
        // print_r("uuid_konsumen : ");
        // print_r($this->input->post('uuid_konsumen', TRUE));
        // print_r("<br/>");
        // print_r("nmrpesan : ");
        // print_r($this->input->post('nmrpesan', TRUE));
        // print_r("<br/>");
        // print_r("nmrkirim : ");
        // print_r($this->input->post('nmrkirim', TRUE));
        // print_r("<br/>");
        // print_r("id_pembelian_barang : ");
        // print_r($id_proses);
        // print_r("<br/>");
        // print_r("harga_satuan_beli : ");
        // print_r($this->input->post('harga_satuan_beli', TRUE));
        // print_r("<br/>");
        // print_r("jumlah : ");
        // print_r($this->input->post('jumlah', TRUE));
        // print_r("<br/>");
        // print_r("<br/>");

        // $x_1 = $id_proses;

        // AMBIL DATA DARI PEMBELIAN
        // $sql = "SELECT * FROM `tbl_pembelian` WHERE `id`='$id_proses'";
        // $data_barang = $this->db->query($sql)->row();

        // AMBIL DATA DARI PERSEDIAAN
        $sql = "SELECT * FROM `persediaan` WHERE `id`='$id_proses'";
        $data_barang = $this->db->query($sql)->row();


        // print_r($data_barang);
        // print_r("<br/>");
        // print_r("<br/>");
        // print_r("<br/>");
        // // die;



        // print_r($data_barang);
        // die;

        // [uuid_pembelian] => 5d4b4221756411ef88650021ccc9061e [uuid_barang] => ae37d726715911ef9fe90021ccc906hh [tgl_po] => 2024-09-01 00:00:00 [nmrsj] => [nmrfakturkwitansi] => 1 [nmrbpb] => [uuid_spop] => 54548eeb756411ef88650021ccc9061e [spop] => 1 [uuid_supplier] => 458c5d176b2311ef80a80021ccc9061e [supplier_kode] => [supplier_nama] => Supplier 1 [uraian] => Buku [jumlah] => 2 [satuan] => rim [uuid_konsumen] => b728e22d6b5811ef80a80021ccc9061e [konsumen] => pj-atk [harga_satuan] => 100000 [harga_total] => 0 [statuslu] => L [kas_bank] => kas [tgl_bayar] => 0000-00-00 00:00:00 [id_usr] => 1 )

        // print_r($data_barang->id);
        // print_r("<br/>");
        // print_r($data_barang->uuid_barang);
        // print_r("<br/>");
        // print_r($data_barang->uraian);
        // print_r("<br/>");
        // print_r($data_barang->satuan);
        // print_r("<br/>");


        // die;

        // $uuid_konsumen = $this->input->post('uuid_konsumen', TRUE);
        $data_konsumen = $this->Sys_konsumen_model->get_by_uuid_konsumen($uuid_unit);


        if (empty($data_konsumen)) {
            $data_konsumen = $this->Sys_unit_model->get_by_uuid_unit($uuid_unit);
            // print_r($data_konsumen);
            // die;
            $data_nama_konsumen = $data_konsumen->nama_unit;
        } else {
            $data_nama_konsumen = $data_konsumen->nama_konsumen;
        }


        // print_r($data_nama_konsumen);
        // die;

        // =========SIMPAN DATA==================
        $tgl_jual_X = date("Y-m-d", strtotime($this->input->post('tgl_jual', TRUE)));


        if ($uuid_penjualan == "new") {
            // print_r("NEW");
            $data = array(
                'tgl_input' => date("Y-m-d H:i:s"),
                'tgl_jual' => $tgl_jual_X,
                // 'uuid_penjualan' => "new",
                'nmrpesan' => $this->input->post('nmrpesan', TRUE),
                'nmrkirim' => $this->input->post('nmrkirim', TRUE),
                'uuid_konsumen' => $uuid_konsumen,
                'konsumen_nama' => $data_nama_konsumen,
                // 'uuid_barang' => $data_barang->uuid_pembelian, //uuid_barang berdasarkan uuid_pembelian karena beda harga (barang sama, waktu beda belanja harga beda)
                'uuid_barang' => $data_barang->uuid_barang, //uuid_barang berdasarkan uuid_pembelian karena beda harga (barang sama, waktu beda belanja harga beda)
                'kode_barang' => $data_barang->kode_barang,
                // 'nama_barang' => $data_barang->uraian,
                'nama_barang' => $data_barang->namabarang,
                // 'uuid_unit' => $uuid_unit_selected,
                // 'unit' => $data_nama_unit,
                'jumlah' => preg_replace("/[^0-9]/", "", $this->input->post('jumlah', TRUE)),
                'satuan' => $data_barang->satuan,
                'harga_satuan' => preg_replace("/[^0-9]/", "", $this->input->post('harga_satuan_beli', TRUE)),
                'total_nominal' =>  preg_replace("/[^0-9]/", "", $this->input->post('jumlah', TRUE)) * preg_replace("/[^0-9]/", "", $this->input->post('harga_satuan_beli', TRUE)),
                'id_usr' => 1,
            );

            $uuid_penjualan = $this->Tbl_penjualan_model->insert_new($data);
            // print_r($uuid_penjualan);
        } else {
            // print_r("BUKAN NEW");

            $data = array(
                'tgl_input' => date("Y-m-d H:i:s"),

                'tgl_jual' => $tgl_jual_X,
                'uuid_penjualan' => $uuid_penjualan,
                'nmrpesan' => $this->input->post('nmrpesan', TRUE),
                'nmrkirim' => $this->input->post('nmrkirim', TRUE),
                'uuid_konsumen' => $uuid_konsumen,
                'konsumen_nama' => $data_nama_konsumen,
                // 'uuid_barang' => $data_barang->uuid_pembelian, //uuid_barang berdasarkan uuid_pembelian karena beda harga (barang sama, waktu beda belanja harga beda)
                'uuid_barang' => $data_barang->uuid_barang, //uuid_barang berdasarkan uuid_pembelian karena beda harga (barang sama, waktu beda belanja harga beda)
                'kode_barang' => $data_barang->kode_barang,
                // 'nama_barang' => $data_barang->uraian,
                'nama_barang' => $data_barang->namabarang,
                // 'uuid_unit' => $uuid_unit_selected,
                // 'unit' => $data_nama_unit,
                'jumlah' => preg_replace("/[^0-9]/", "", $this->input->post('jumlah', TRUE)),
                'satuan' => $data_barang->satuan,
                'harga_satuan' => preg_replace("/[^0-9]/", "", $this->input->post('harga_satuan_beli', TRUE)),
                'total_nominal' =>  preg_replace("/[^0-9]/", "", $this->input->post('jumlah', TRUE)) * preg_replace("/[^0-9]/", "", $this->input->post('harga_satuan_beli', TRUE)),
                'id_usr' => 1,
            );
            $this->Tbl_penjualan_model->insert_add_barang($data);
        }

        // die;
        // =========SIMPAN DATA==================


        // redirect("kasir_penjualan/".$uuid_penjualan);
        redirect(site_url('tbl_penjualan/kasir_penjualan/' . $uuid_penjualan));
    }

    public function create_action_produksi_input_bahan($id_persediaan_barang = null)
    {

        // print_r("create_action_produksi_input_bahan");
        // print_r("<br/>");

        // print_r($id_persediaan_barang);
        // print_r("<br/>");
        // print_r($this->input->post('jumlah', TRUE));
        // print_r("<br/>");
        // print_r($this->input->post('uuid_persediaan', TRUE));
        // print_r("<br/>");
        // print_r($this->input->post('id_persediaan', TRUE));
        // print_r("<br/>");
        // // die;
        
        $Get_id_persediaan_bahan = $this->input->post('id_persediaan', TRUE);
        $jumlah_bahan_input = $this->input->post('jumlah', TRUE);
        $tgl_transaksi_input = $this->input->post('tgl_transaksi', TRUE);
        $bulan_proses = $this->_resolve_bulan_produksi_selected($this->input->post('bulan_produksi', TRUE));
        $row_persediaan_bahan = $Get_id_persediaan_bahan
            ? $this->Persediaan_model->get_by_id($Get_id_persediaan_bahan)
            : null;
        if (!$this->_persediaan_sesuai_bulan_produksi($row_persediaan_bahan, $bulan_proses)) {
            $this->session->set_flashdata(
                'message',
                'Barang tidak boleh diproses. Tanggal beli persediaan harus sesuai bulan pemrosesan (' . $this->_bulan_nama_indonesia($bulan_proses) . ').'
            );
            if ($id_persediaan_barang) {
                redirect(site_url('Sys_unit_produk/create_produksi/' . $id_persediaan_barang . '?bulan=' . urlencode($bulan_proses)));
            }
            redirect(site_url('Sys_unit_produk/create_produksi?bulan=' . urlencode($bulan_proses)));
            return;
        }

        if ($id_persediaan_barang) {

            // print_r("Ada ID PERSEDIAAN");
            // print_r("<br/>");

            $data_barang_selected = $this->Persediaan_model->get_by_id($id_persediaan_barang);
            if ($tgl_transaksi_input === '' || $tgl_transaksi_input === null) {
                $tgl_transaksi_input = $data_barang_selected ? $data_barang_selected->tanggal : '';
            }

            // Simpan bahan ke tabel sys_unit_produk_bahan berdasarkan id_persediaan barang
            $get_data_barang_dipersediaan_by_uuid_persediaan = $this->Persediaan_model->get_by_uuid_persediaan($this->input->post('uuid_persediaan', TRUE));

            $data = array(

                'uuid_persediaan' => $data_barang_selected->uuid_persediaan,

                'tgl_transaksi' => $this->_tanggal_produksi_dari_input($tgl_transaksi_input),

                'uuid_produk' => $this->_uuid_barang_dari_persediaan($get_data_barang_dipersediaan_by_uuid_persediaan),
                'uuid_persediaan_bahan' => $this->input->post('uuid_persediaan', TRUE),

                'kode_barang_bahan' => $this->_kode_barang_dari_persediaan($get_data_barang_dipersediaan_by_uuid_persediaan),
                'nama_barang_bahan' => $get_data_barang_dipersediaan_by_uuid_persediaan->namabarang,
                'satuan_bahan' => $get_data_barang_dipersediaan_by_uuid_persediaan->satuan,
                'harga_satuan_bahan' => $get_data_barang_dipersediaan_by_uuid_persediaan->hpp,
                'jumlah_bahan' => $jumlah_bahan_input,

            );

            // print_r($data);
            // die;

            $this->Sys_unit_produk_bahan_model->insert($data);

            $tanggal_beli_bulan = $this->_tanggal_beli_bulan_dari_transaksi($tgl_transaksi_input);
            $id_persediaan_bahan_bulan = $this->_resolve_id_persediaan_bahan_bulan_produksi(
                $Get_id_persediaan_bahan,
                $this->input->post('uuid_persediaan', TRUE),
                $tanggal_beli_bulan
            );
            $this->_update_persediaan_bahan_produksi($id_persediaan_bahan_bulan, $jumlah_bahan_input);

            redirect(site_url('Sys_unit_produk/create_produksi/' . $id_persediaan_barang . '?bulan=' . urlencode($bulan_proses)));
        } else { // Belum ada id_persediaan_barang dan uuid_persediaan_barang

            // print_r("BELUM ADA ID PERSEDIAAN");
            // print_r("<br/>");

            // Jika belum ada id_persediaan_barang di tabel persediaan , maka di buatkan id_persediaan_barang (uuid_persediaan_barang)

            // Membuat record persediaan barang dengan nama produk dan uuid_barang belum di generate
            $date_tgl_produksi = $this->_tanggal_produksi_dari_input($tgl_transaksi_input);
            $tanggal_beli = $this->_tanggal_beli_bulan_dari_transaksi($tgl_transaksi_input);


            $data = array(
                // 'id' => $this->input->post('id', TRUE),
                'tanggal' => $date_tgl_produksi,
                // 'tanggal_new' => $date_persediaan,
                // 'kode' => $row_sys_nama_barang->kode_barang,    
                // 'uuid_barang' => $row_sys_nama_barang->uuid_barang,
                // 'namabarang' => $row_sys_nama_barang->nama_barang,
                // 'satuan' => $this->input->post('satuan', TRUE),
                // 'hpp' => $this->input->post('harga_satuan', TRUE),
                // 'sa' => preg_replace("/[^0-9]/", "", $this->input->post('jumlah_produksi', TRUE)),
                'tanggal_beli' => $tanggal_beli,
                'spop' => 'produksi_' . date('Ymd', strtotime($date_tgl_produksi)),
                // 'total_10' => preg_replace("/[^0-9]/", "", $this->input->post('jumlah_produksi', TRUE)),
            );
            $id_persediaan_barang = $this->Persediaan_model->insert_produk_baru($data);

            $sql_data_persediaan = "SELECT * FROM `persediaan` WHERE `id`='$id_persediaan_barang'";
            $get_uuid_persediaan = $this->db->query($sql_data_persediaan)->row()->uuid_persediaan;

            // print_r($id_persediaan_barang);
            // print_r("<br/>");
            // print_r("<br/>");
            // print_r("<br/>");


            // End of Kemudian Insert ke tabel persediaan

            // SIMPAN PRODUK DARI UNIT
            // // $data_unit = $this->Sys_unit_model->get_by_uuid_unit($this->input->post('uuid_unit', TRUE));

            // $data = array(
            //     'uuid_persediaan' => $get_uuid_persediaan,
            //     // 'uuid_unit' => $data_unit->uuid_unit,
            //     // 'kode_unit' => $data_unit->kode_unit,
            //     // 'nama_unit' => $data_unit->nama_unit,
            //     'tgl_transaksi' => $date_tgl_produksi,
            //     'uuid_produk' => $row_sys_nama_barang->uuid_barang,
            //     'kode_barang' => $row_sys_nama_barang->kode_barang,
            //     'nama_barang' => $row_sys_nama_barang->nama_barang,
            //     'jumlah_produksi' => $this->input->post('jumlah_produksi', TRUE),
            //     'satuan' => $this->input->post('satuan', TRUE),
            //     'harga_satuan' => $this->input->post('harga_satuan', TRUE),
            // );

            // $this->Sys_unit_produk_model->insert($data);


            $data_barang_selected = $this->Persediaan_model->get_by_id($id_persediaan_barang);

            // $get_data_produk_unit = $this->Sys_unit_produk_model->get_by_uuid_persediaan($data_barang_selected->uuid_persediaan);

            // $get_result_data_bahan_produk_unit = $this->Sys_unit_produk_bahan_model->get_by_uuid_persediaan($data_barang_selected->uuid_persediaan);

            // $get_data_barang = $this->Sys_nama_barang_model->get_by_uuid_barang($this->input->post('uuid_barang', TRUE));



            // print_r($get_data_barang);
            $get_data_barang_dipersediaan_by_uuid_persediaan = $this->Persediaan_model->get_by_uuid_persediaan($this->input->post('uuid_persediaan', TRUE));


            $data = array(

                'uuid_persediaan' => $get_uuid_persediaan,
                // 'uuid_unit' => $get_data_produk_unit->uuid_unit,
                // 'kode_unit' => $this->input->post('kode_unit', TRUE),
                // 'nama_unit' => $get_data_produk_unit->nama_unit,
                'tgl_transaksi' => $date_tgl_produksi,
                
                'uuid_persediaan_bahan' => $this->input->post('uuid_persediaan', TRUE),

                'uuid_produk' => $this->_uuid_barang_dari_persediaan($get_data_barang_dipersediaan_by_uuid_persediaan),
                'kode_barang_bahan' => $this->_kode_barang_dari_persediaan($get_data_barang_dipersediaan_by_uuid_persediaan),
                'nama_barang_bahan' => $get_data_barang_dipersediaan_by_uuid_persediaan->namabarang,
                'satuan_bahan' => $get_data_barang_dipersediaan_by_uuid_persediaan->satuan,
                'harga_satuan_bahan' => $get_data_barang_dipersediaan_by_uuid_persediaan->hpp,

                'jumlah_bahan' => $jumlah_bahan_input,



            );

            // print_r($data);
            // die;

            $this->Sys_unit_produk_bahan_model->insert($data);

            $tanggal_beli_bulan = $this->_tanggal_beli_bulan_dari_transaksi($tgl_transaksi_input);
            $id_persediaan_bahan_bulan = $this->_resolve_id_persediaan_bahan_bulan_produksi(
                $Get_id_persediaan_bahan,
                $this->input->post('uuid_persediaan', TRUE),
                $tanggal_beli_bulan
            );
            $this->_update_persediaan_bahan_produksi($id_persediaan_bahan_bulan, $jumlah_bahan_input);

            redirect(site_url('Sys_unit_produk/create_produksi/' . $id_persediaan_barang . '?bulan=' . urlencode($bulan_proses)));

        }
    }

    public function create_produksi($id_persediaan_barang = null)
    {
        // print_r("create_produksi");
        // print_r("<br/>");
        if ($id_persediaan_barang) {
            // print_r("Sudah Ada");
            // print_r("<br/>");
            // print_r($id_persediaan_barang);

            $data_barang_selected = $this->Persediaan_model->get_by_id($id_persediaan_barang);


            $get_data_produk_unit = $this->Sys_unit_produk_model->get_by_uuid_persediaan($data_barang_selected->uuid_persediaan);
            $get_result_data_bahan_produk_unit = $this->Sys_unit_produk_bahan_model->get_by_uuid_persediaan($data_barang_selected->uuid_persediaan);


            // print_r($data_barang_selected);

            $data = array(
                'data_bahan_produk_unit' => $get_result_data_bahan_produk_unit,
                'button' => 'Simpan Produk',
                'button_data_produk' => 'Update Produk',
                'action' => site_url('sys_unit_produk/create_action_produksi/' . $id_persediaan_barang),
                'action_simpan_bahan' => site_url('sys_unit_produk/create_action_produksi_input_bahan/' . $id_persediaan_barang),
                'action_simpan_nama_produk_baru' => site_url('sys_unit_produk/action_simpan_nama_produk_baru/'),

                'id' => set_value('id'),

                'id_persediaan_barang' => $id_persediaan_barang,
                'uuid_barang' => $data_barang_selected->uuid_barang,
                'kode_barang' => $data_barang_selected->kode_barang,
                'nama_barang' => $data_barang_selected->namabarang,
                'satuan' => $data_barang_selected->satuan,
                'harga_satuan' => $data_barang_selected->hpp,

                'uuid_unit' => $get_data_produk_unit ? $get_data_produk_unit->uuid_unit : set_value('uuid_unit'),
                // 'kode_unit' => set_value('kode_unit'),
                'nama_unit' => $get_data_produk_unit ? $get_data_produk_unit->nama_unit : set_value('nama_unit'),
                'tgl_transaksi' => $get_data_produk_unit ? $get_data_produk_unit->tgl_transaksi : $data_barang_selected->tanggal,
                'jumlah_produksi' => $get_data_produk_unit ? $get_data_produk_unit->jumlah_produksi : $data_barang_selected->sa,
                'keterangan' => $get_data_produk_unit ? $get_data_produk_unit->keterangan : set_value('keterangan'),
                'tanggal_beli_bulan' => $this->_tanggal_beli_bulan_dari_transaksi(
                    !empty($data_barang_selected->tanggal_beli) ? $data_barang_selected->tanggal_beli : $data_barang_selected->tanggal
                ),
            );

            // print_r($data);

            // $this->load->view('sys_unit_produk/sys_unit_produk_form', $data);

            // $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_unit_produk/adminlte310_sys_unit_produk_form_baru', $data);
        } else {
            // print_r("Baru");
            // print_r("<br/>");
            // print_r($this->input->post('nama_barang', TRUE));
            // die;



            $data = array(
                'button' => 'Simpan Produk',
                'button_data_produk' => 'Update Produk',
                'action' => site_url('sys_unit_produk/create_action_produksi/'),
                'action_simpan_bahan' => site_url('sys_unit_produk/create_action_produksi_input_bahan/'),
                'action_simpan_nama_produk_baru' => site_url('sys_unit_produk/action_simpan_nama_produk_baru/'),

                'id' => set_value('id'),
                'tanggal_beli_bulan' => $this->_tanggal_beli_bulan_dari_transaksi($this->input->post('tgl_transaksi', TRUE)),

                'id_persediaan_barang' => set_value('id_persediaan_barang'),
                'uuid_barang' => set_value('uuid_barang'),
                'kode_barang' => set_value('kode_barang'),
                'nama_barang' => $this->input->post('nama_barang', TRUE),
                'satuan' => set_value('satuan'),
                'harga_satuan' => set_value('harga_satuan'),

                'uuid_unit' => set_value('uuid_unit'),
                'kode_unit' => set_value('kode_unit'),
                'nama_unit' =>  set_value('nama_unit'),
                'tgl_transaksi' => set_value('tgl_transaksi'),
                'jumlah_produksi' => set_value('jumlah_produksi'),
                'keterangan' => set_value('keterangan'),
            );


            // print_r($data);

            // $this->load->view('sys_unit_produk/sys_unit_produk_form', $data);


        }
        $data = array_merge($data, $this->_produksi_bulan_view_data($this->input->get('bulan', TRUE)));
        $data = $this->_enrich_produksi_form_data($data);
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_unit_produk/adminlte310_sys_unit_produk_form_baru', $data);
    }

    public function update_produksi($id_persediaan_barang = null)
    {
        // print_r("create_produksi");
        // print_r("<br/>");
        if ($id_persediaan_barang) {
            // print_r("Sudah Ada");
            // print_r("<br/>");
            // print_r($id_persediaan_barang);

            $data_barang_selected = $this->Persediaan_model->get_by_id($id_persediaan_barang);


            $get_data_produk_unit = $this->Sys_unit_produk_model->get_by_uuid_persediaan($data_barang_selected->uuid_persediaan);
            $get_result_data_bahan_produk_unit = $this->Sys_unit_produk_bahan_model->get_by_uuid_persediaan($data_barang_selected->uuid_persediaan);


            // print_r($data_barang_selected);

            $data = array(
                'data_bahan_produk_unit' => $get_result_data_bahan_produk_unit,
                'button' => 'Simpan Produk',
                'button_data_produk' => 'Update Produk',
                'action' => site_url('sys_unit_produk/create_action_produksi/' . $id_persediaan_barang),
                'action_simpan_bahan' => site_url('sys_unit_produk/create_action_produksi_input_bahan/' . $id_persediaan_barang),
                'action_simpan_nama_produk_baru' => site_url('sys_unit_produk/UPDATE_action_simpan_nama_produk_baru/'),

                'id' => set_value('id'),

                'id_persediaan_barang' => $id_persediaan_barang,
                'uuid_barang' => $data_barang_selected->uuid_barang,
                'kode_barang' => $data_barang_selected->kode_barang,
                'nama_barang' => $data_barang_selected->namabarang,
                'satuan' => $data_barang_selected->satuan,
                'harga_satuan' => $data_barang_selected->hpp,

                'uuid_unit' => $get_data_produk_unit ? $get_data_produk_unit->uuid_unit : set_value('uuid_unit'),
                // 'kode_unit' => set_value('kode_unit'),
                'nama_unit' => $get_data_produk_unit ? $get_data_produk_unit->nama_unit : set_value('nama_unit'),
                'tgl_transaksi' => $get_data_produk_unit ? $get_data_produk_unit->tgl_transaksi : $data_barang_selected->tanggal,
                'jumlah_produksi' => $get_data_produk_unit ? $get_data_produk_unit->jumlah_produksi : $data_barang_selected->sa,
                'keterangan' => $get_data_produk_unit ? $get_data_produk_unit->keterangan : set_value('keterangan'),
                'tanggal_beli_bulan' => $this->_tanggal_beli_bulan_dari_transaksi(
                    !empty($data_barang_selected->tanggal_beli) ? $data_barang_selected->tanggal_beli : $data_barang_selected->tanggal
                ),
            );

            // print_r($data);

            // $this->load->view('sys_unit_produk/sys_unit_produk_form', $data);

            // $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_unit_produk/adminlte310_sys_unit_produk_form_baru', $data);
        } else {
            // print_r("Baru");
            // print_r("<br/>");
            // print_r($this->input->post('nama_barang', TRUE));
            // die;



            $data = array(
                'button' => 'Simpan Produk',

                'action' => site_url('sys_unit_produk/create_action_produksi/'),
                'action_simpan_bahan' => site_url('sys_unit_produk/create_action_produksi_input_bahan/'),
                'action_simpan_nama_produk_baru' => site_url('sys_unit_produk/UPDATE_action_simpan_nama_produk_baru/'),

                'id' => set_value('id'),

                'id_persediaan_barang' => set_value('id_persediaan_barang'),
                'uuid_barang' => set_value('uuid_barang'),
                'kode_barang' => set_value('kode_barang'),
                'nama_barang' => $this->input->post('nama_barang', TRUE),
                'satuan' => set_value('satuan'),
                'harga_satuan' => set_value('harga_satuan'),

                'uuid_unit' => set_value('uuid_unit'),
                'kode_unit' => set_value('kode_unit'),
                'nama_unit' =>  set_value('nama_unit'),
                'tgl_transaksi' => set_value('tgl_transaksi'),
                'jumlah_produksi' => set_value('jumlah_produksi'),
                'keterangan' => set_value('keterangan'),
            );


            // print_r($data);

            // $this->load->view('sys_unit_produk/sys_unit_produk_form', $data);


        }
        $data = array_merge($data, $this->_produksi_bulan_view_data($this->input->get('bulan', TRUE)));
        $data = $this->_enrich_produksi_form_data($data);
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_unit_produk/adminlte310_sys_unit_produk_form_baru', $data);
    }

    public function action_simpan_nama_produk_baru($Get_id_persediaan_barang = null)
    {
        $id_persediaan_barang = $this->_resolve_id_persediaan_produk($Get_id_persediaan_barang);
        if ($id_persediaan_barang <= 0) {
            $this->session->set_flashdata('message', 'ID persediaan produk tidak ditemukan.');
            redirect(site_url('Sys_unit_produk'));
            return;
        }

        $tgl_input = $this->input->post('tgl_transaksi', TRUE);
        $date_tgl_produksi = $this->_tanggal_produksi_dari_input($tgl_input);
        $tanggal_beli = $this->_tanggal_beli_bulan_dari_transaksi($tgl_input);

        $row_barang = $this->_resolve_uuid_barang_produksi(
            $this->input->post('nama_barang', TRUE),
            $this->input->post('satuan', TRUE)
        );

        $Get_uuid_barang = $row_barang->uuid_barang;
        $Get_kode_barang = $row_barang->kode_barang;
        $Get_nama_barang = $row_barang->nama_barang;

        // die;

        // Update tabel persediaan , berdasarkan uuid_persediaan dengan nama produk : uuid_barang , nama barang dan jumlah produksi total_10
        // PROSES UPDATE TABEL PERSEDIAAN BARANG , FILTER ID_PERSEDIAAN_BARANG
        // UPDATE `persediaan` SET `uuid_barang`='[value-6]',`kode_barang`='[value-7]',`tanggal_beli`='[value-8]',`tanggal`='[value-9]',`namabarang`='[value-11]',`satuan`='[value-12]',`hpp`='[value-13]',`sa`='[value-14]',`spop`='[value-15]',`beli`='[value-16]',`tuj`='[value-17]'`total_10`='[value-33]',`nilai_persediaan`='[value-34]' WHERE `id`='[value-1]'


        // $sql_update_uuid_persediaan = "UPDATE `persediaan` SET `uuid_persediaan`=replace(uuid(),'-','') WHERE `id`='$Get_id_persediaan_barang'";

        // $date_tgl_produksi = date("Y-m-d H:i:s");
        // $KODE_tgl_produksi = date("Ymd");
        // $SPOP_Produksi = "PRODUKSI_" . $KODE_tgl_produksi;
        // // $Jumlah_nominal = $this->input->post('jumlah_produksi', TRUE) * $this->input->post('harga_satuan', TRUE);


        $SPOP_Produksi = $this->_generate_spop_produksi($date_tgl_produksi);

        $Get_satuan = $this->input->post('satuan', TRUE);
        $Get_harga_satuan = $this->_parse_jumlah_angka($this->input->post('harga_satuan', TRUE));
        $Get_jumlah_produksi = $this->_parse_jumlah_angka($this->input->post('jumlah_produksi', TRUE));
        $Jumlah_nominal = $Get_harga_satuan * $Get_jumlah_produksi;

        $data_persediaan = array(
            'uuid_barang' => $Get_uuid_barang,
            'namabarang' => $Get_nama_barang,
            'kode_barang' => $Get_kode_barang,
            'kode' => $Get_kode_barang,
            'tanggal_beli' => $tanggal_beli,
            'tanggal' => $date_tgl_produksi,
            'satuan' => $Get_satuan,
            'hpp' => (string) $Get_harga_satuan,
            'sa' => (string) $Get_jumlah_produksi,
            'beli' => '0',
            'spop' => $SPOP_Produksi,
            'total_10' => (string) $Get_jumlah_produksi,
            'nilai_persediaan' => (string) $Jumlah_nominal,
        );
        if ($this->db->field_exists('tuj', 'persediaan')) {
            $data_persediaan['tuj'] = (string) $Get_jumlah_produksi;
        }

        $this->db->set('uuid_spop', "replace(uuid(),'-','')", FALSE);
        $this->Persediaan_model->update($id_persediaan_barang, $data_persediaan);

        $row_persediaan = $this->Persediaan_model->get_by_id($id_persediaan_barang);
        $data_unit = $this->Sys_unit_model->get_by_uuid_unit($this->input->post('uuid_unit', TRUE));

        $data = array(
            'uuid_persediaan' => $row_persediaan ? $row_persediaan->uuid_persediaan : '',
            'uuid_unit' => $data_unit->uuid_unit,
            'kode_unit' => $data_unit->kode_unit,
            'nama_unit' => $data_unit->nama_unit,
            'tgl_transaksi' => $date_tgl_produksi,
            'uuid_produk' => $Get_uuid_barang,
            'kode_barang' => $Get_kode_barang,
            'nama_barang' => $Get_nama_barang,
            'jumlah_produksi' => (string) $Get_jumlah_produksi,
            'satuan' => $Get_satuan,
            'harga_satuan' => (string) $Get_harga_satuan,
            'keterangan' => $this->input->post('keterangan', TRUE),
        );

        $existing_produk = $row_persediaan
            ? $this->Sys_unit_produk_model->get_by_uuid_persediaan($row_persediaan->uuid_persediaan)
            : null;
        if ($existing_produk) {
            $this->Sys_unit_produk_model->update($existing_produk->id, $data);
        } else {
            $this->Sys_unit_produk_model->insert($data);
        }

        $this->session->set_flashdata('message', 'Produk produksi berhasil disimpan.');
        $bulan = $this->_resolve_bulan_produksi_selected($this->input->post('bulan_produksi', TRUE));
        redirect(site_url('Sys_unit_produk/create_produksi/' . $id_persediaan_barang . '?bulan=' . urlencode($bulan)));
    }

    public function UPDATE_action_simpan_nama_produk_baru($Get_id_persediaan_barang = null)
    {
        $id_persediaan_barang = $this->_resolve_id_persediaan_produk($Get_id_persediaan_barang);
        if ($id_persediaan_barang <= 0) {
            $this->session->set_flashdata('message', 'ID persediaan produk tidak ditemukan.');
            redirect(site_url('Sys_unit_produk'));
            return;
        }

        $row_persediaan = $this->Persediaan_model->get_by_id($id_persediaan_barang);
        if (!$row_persediaan) {
            $this->session->set_flashdata('message', 'Data persediaan produk tidak ditemukan.');
            redirect(site_url('Sys_unit_produk'));
            return;
        }

        $tgl_input = $this->input->post('tgl_transaksi', TRUE);
        $date_tgl_produksi = $this->_tanggal_produksi_dari_input($tgl_input);
        $tanggal_beli = $this->_tanggal_beli_bulan_dari_transaksi($tgl_input);

        $row_barang = $this->_resolve_uuid_barang_produksi(
            $this->input->post('nama_barang', TRUE),
            $this->input->post('satuan', TRUE)
        );

        $get_satuan = $this->input->post('satuan', TRUE);
        $get_harga_satuan = $this->_parse_jumlah_angka($this->input->post('harga_satuan', TRUE));
        $get_jumlah_produksi = $this->_parse_jumlah_angka($this->input->post('jumlah_produksi', TRUE));
        $jumlah_nominal = $get_harga_satuan * $get_jumlah_produksi;

        $data_persediaan = array(
            'uuid_barang' => $row_barang->uuid_barang,
            'namabarang' => $row_barang->nama_barang,
            'kode_barang' => $row_barang->kode_barang,
            'kode' => $row_barang->kode_barang,
            'tanggal_beli' => $tanggal_beli,
            'tanggal' => $date_tgl_produksi,
            'satuan' => $get_satuan,
            'hpp' => (string) $get_harga_satuan,
            'sa' => (string) $get_jumlah_produksi,
            'total_10' => (string) $get_jumlah_produksi,
            'nilai_persediaan' => (string) $jumlah_nominal,
        );
        if ($this->db->field_exists('tuj', 'persediaan')) {
            $data_persediaan['tuj'] = (string) $get_jumlah_produksi;
        }

        $this->Persediaan_model->update($id_persediaan_barang, $data_persediaan);

        $data_unit = $this->Sys_unit_model->get_by_uuid_unit($this->input->post('uuid_unit', TRUE));
        $existing_produk = $this->Sys_unit_produk_model->get_by_uuid_persediaan($row_persediaan->uuid_persediaan);

        $data = array(
            'uuid_persediaan' => $row_persediaan->uuid_persediaan,
            'uuid_unit' => $data_unit->uuid_unit,
            'kode_unit' => $data_unit->kode_unit,
            'nama_unit' => $data_unit->nama_unit,
            'tgl_transaksi' => $date_tgl_produksi,
            'uuid_produk' => $row_barang->uuid_barang,
            'kode_barang' => $row_barang->kode_barang,
            'nama_barang' => $row_barang->nama_barang,
            'jumlah_produksi' => (string) $get_jumlah_produksi,
            'satuan' => $get_satuan,
            'harga_satuan' => (string) $get_harga_satuan,
            'keterangan' => $this->input->post('keterangan', TRUE),
        );

        if ($existing_produk) {
            $this->Sys_unit_produk_model->update($existing_produk->id, $data);
        } else {
            $this->Sys_unit_produk_model->insert($data);
        }

        $this->session->set_flashdata('message', 'Produk produksi berhasil diperbarui.');
        $bulan = $this->_resolve_bulan_produksi_selected($this->input->post('bulan_produksi', TRUE));
        redirect(site_url('Sys_unit_produk/create_produksi/' . $id_persediaan_barang . '?bulan=' . urlencode($bulan)));
    }

    public function create_action_produksi($id_persediaan_barang = null)
    {


        // print_r($id_persediaan_barang);
        // print_r("<br/>");

        // print_r($this->input->post('uuid_barang', TRUE));
        // print_r("<br/>");

        // print_r($this->input->post('nama_barang', TRUE));
        // print_r("<br/>");

        // print_r($this->input->post('satuan', TRUE));
        // print_r("<br/>");

        // print_r($this->input->post('harga_satuan', TRUE));
        // print_r("<br/>");

        // print_r($this->input->post('tgl_transaksi', TRUE));
        // print_r("<br/>");

        // print_r($this->input->post('uuid_unit', TRUE));
        // print_r("<br/>");

        // print_r($this->input->post('jumlah_produksi', TRUE));
        // print_r("<br/>");




        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {



            // GET SUPPLIER DATA
            $get_uuid_unit = $this->input->post('uuid_unit', TRUE);
            $sql_uuid_unit = "SELECT * FROM `sys_unit` WHERE `uuid_unit`='$get_uuid_unit'";
            $get_kode_unit = $this->db->query($sql_uuid_unit)->row()->kode_unit;
            $get_nama_unit = $this->db->query($sql_uuid_unit)->row()->nama_unit;

            // Kemudian Insert ke tabel persediaan

            if (date("Y", strtotime($this->input->post('tgl_transaksi', TRUE))) < 2020) {
                $date_tgl_produksi = date("Y-m-d H:i:s");
            } else {
                $date_tgl_produksi = date("Y-m-d H:i:s", strtotime($this->input->post('tgl_transaksi', TRUE)));
            }


            if ($id_persediaan_barang) {
                $row_persediaan_produk = $this->Persediaan_model->get_by_id($id_persediaan_barang);
                if (!$row_persediaan_produk) {
                    $row_persediaan_produk = $this->Persediaan_model->get_by_uuid_barang($this->input->post('uuid_barang', TRUE));
                }
                $row_barang = (object) array(
                    'uuid_barang' => $this->_uuid_barang_dari_persediaan($row_persediaan_produk),
                    'kode_barang' => $this->_kode_barang_dari_persediaan($row_persediaan_produk),
                    'nama_barang' => $row_persediaan_produk ? $row_persediaan_produk->namabarang : $this->input->post('nama_barang', TRUE),
                );

                $jumlah_produksi = $this->_parse_jumlah_angka($this->input->post('jumlah_produksi', TRUE));
                $harga_satuan = $this->_parse_jumlah_angka($this->input->post('harga_satuan', TRUE));
                $tanggal_beli = $this->_tanggal_beli_bulan_dari_transaksi($this->input->post('tgl_transaksi', TRUE));

                $data = array(
                    'tanggal' => $date_tgl_produksi,
                    'uuid_barang' => $row_barang->uuid_barang,
                    'kode' => $row_barang->kode_barang,
                    'namabarang' => $row_barang->nama_barang,
                    'satuan' => $this->input->post('satuan', TRUE),
                    'hpp' => (string) $harga_satuan,
                    'sa' => (string) $jumlah_produksi,
                    'tanggal_beli' => $tanggal_beli,
                    'total_10' => (string) $jumlah_produksi,
                    'nilai_persediaan' => (string) ($harga_satuan * $jumlah_produksi),
                );
                if ($this->db->field_exists('kode_barang', 'persediaan')) {
                    $data['kode_barang'] = $row_barang->kode_barang;
                }
                if ($this->db->field_exists('tuj', 'persediaan')) {
                    $data['tuj'] = (string) $jumlah_produksi;
                }

                $this->Persediaan_model->update($id_persediaan_barang, $data);


                // SIMPAN KE TABEL PEMBELIAN

                $data = array(
                    'date_input' => date("Y-m-d H:i:s"),
                    'tgl_po' => $date_tgl_produksi,
                    'nmrsj' => "produksi",
                    'nmrfakturkwitansi' => "produksi",
                    'nmrbpb' => "produksi",
                    'spop' => "produksi",

                    'supplier_kode' => $get_kode_unit,
                    'uuid_supplier' => $this->input->post('uuid_unit', TRUE),
                    'supplier_nama' => $get_nama_unit,

                    'uuid_barang' => $row_barang->uuid_barang,
                    'uraian' => $row_barang->nama_barang,
                    'jumlah' => preg_replace("/[^0-9]/", "", $this->input->post('jumlah_produksi', TRUE)),
                    'satuan' => $this->input->post('satuan', TRUE),
                    'harga_satuan' => preg_replace("/[^0-9]/", "", $this->input->post('harga_satuan', TRUE)),
                );

                $this->Tbl_pembelian_model->insert($data);
            } else {
                $row_barang = $this->_resolve_uuid_barang_produksi(
                    $this->input->post('nama_barang', TRUE),
                    $this->input->post('satuan', TRUE),
                    $date_tgl_produksi
                );

                $tanggal_beli = $this->_tanggal_beli_bulan_dari_transaksi($this->input->post('tgl_transaksi', TRUE));

                $data = array(
                    'tanggal' => $date_tgl_produksi,
                    'uuid_barang' => $row_barang->uuid_barang,
                    'kode' => $row_barang->kode_barang,
                    'namabarang' => $row_barang->nama_barang,
                    'satuan' => $this->input->post('satuan', TRUE),
                    'hpp' => preg_replace("/[^0-9]/", "", $this->input->post('harga_satuan', TRUE)),
                    'tanggal_beli' => $tanggal_beli,
                    'spop' => "produksi",
                    'total_10' => preg_replace("/[^0-9]/", "", $this->input->post('jumlah_produksi', TRUE)),
                );
                if ($this->db->field_exists('kode_barang', 'persediaan')) {
                    $data['kode_barang'] = $row_barang->kode_barang;
                }

                // SIMPAN KE PERSEDIAAN
                $this->Persediaan_model->insert($data);


                // SIMPAN KE TABEL PEMBELIAN

                $data = array(
                    'date_input' => date("Y-m-d H:i:s"),
                    'tgl_po' => $date_tgl_produksi,
                    'nmrsj' => "produksi",
                    'nmrfakturkwitansi' => "produksi",
                    'nmrbpb' => "produksi",
                    'spop' => "produksi",

                    'supplier_kode' => $get_kode_unit,
                    'uuid_supplier' => $this->input->post('uuid_unit', TRUE),
                    'supplier_nama' => $get_nama_unit,

                    'uuid_barang' => $row_barang->uuid_barang,
                    'kode_barang' => $row_barang->kode_barang,
                    'uraian' => $row_barang->nama_barang,

                    'jumlah' => preg_replace("/[^0-9]/", "", $this->input->post('jumlah_produksi', TRUE)),
                    'satuan' => $this->input->post('satuan', TRUE),
                    'harga_satuan' => preg_replace("/[^0-9]/", "", $this->input->post('harga_satuan', TRUE)),
                );

                $this->Tbl_pembelian_model->insert($data);
            }







            // print_r($data);
            // die;



            // End of Kemudian Insert ke tabel persediaan





            $data_unit = $this->Sys_unit_model->get_by_uuid_unit($this->input->post('uuid_unit', TRUE));


            $data = array(
                'uuid_unit' => $data_unit->uuid_unit,
                'kode_unit' => $data_unit->kode_unit,
                'nama_unit' => $data_unit->nama_unit,
                'tgl_transaksi' => $date_tgl_produksi,
                'uuid_produk' => $row_barang->uuid_barang,
                'kode_barang' => $row_barang->kode_barang,
                'nama_barang' => $row_barang->nama_barang,
                'jumlah_produksi' => $this->input->post('jumlah_produksi', TRUE),
                'satuan' => $this->input->post('satuan', TRUE),
                'harga_satuan' => $this->input->post('harga_satuan', TRUE),
            );



            $this->Sys_unit_produk_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('sys_unit_produk'));
        }
    }


    public function create_unit($uuid_unit_selected)
    {

        $data_unit = $this->Sys_unit_model->get_by_uuid_unit($uuid_unit_selected);

        $data = array(
            'button' => 'Simpan',
            'action' => site_url('sys_unit_produk/create_action_unit/' . $uuid_unit_selected),
            'id' => set_value('id'),
            'uuid_unit' => $uuid_unit_selected,
            'kode_unit' => set_value('kode_unit'),
            'nama_unit' => $data_unit->nama_unit,
            'tgl_transaksi' => set_value('tgl_transaksi'),
            'uuid_barang' => set_value('uuid_barang'),
            'kode_barang' => set_value('kode_barang'),
            'nama_barang' => set_value('nama_barang'),
            'jumlah_produksi' => set_value('jumlah_produksi'),
            'satuan' => set_value('satuan'),
            'harga_satuan' => set_value('harga_satuan'),
        );
        // $this->load->view('sys_unit_produk/sys_unit_produk_form', $data);
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_unit_produk/adminlte310_sys_unit_produk_form', $data);
    }




    public function create_action_unit($uuid_unit_selected)
    {




        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {

            $data_unit = $this->Sys_unit_model->get_by_uuid_unit($uuid_unit_selected);


            if (date("Y", strtotime($this->input->post('tgl_transaksi', TRUE))) < 2020) {
                $date_tgl_produksi = date("Y-m-d H:i:s");
            } else {
                $date_tgl_produksi = date("Y-m-d H:i:s", strtotime($this->input->post('tgl_transaksi', TRUE)));
            }


            $data = array(
                'uuid_unit' => $uuid_unit_selected,
                'kode_unit' => $this->input->post('kode_unit', TRUE),
                'nama_unit' => $data_unit->nama_unit,
                'tgl_transaksi' => $date_tgl_produksi,
                'uuid_barang' => $this->input->post('uuid_barang', TRUE),
                'kode_barang' => $this->input->post('kode_barang', TRUE),
                'nama_barang' => $this->input->post('nama_barang', TRUE),
                'jumlah_produksi' => $this->input->post('jumlah_produksi', TRUE),
                'satuan' => $this->input->post('satuan', TRUE),
                'harga_satuan' => $this->input->post('harga_satuan', TRUE),
            );

            $this->Sys_unit_produk_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('sys_unit/detail_unit/' . $uuid_unit_selected));
        }
    }


    public function create_action()
    {



        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {


            if (date("Y", strtotime($this->input->post('tgl_transaksi', TRUE))) < 2020) {
                $date_tgl_produksi = date("Y-m-d H:i:s");
            } else {
                $date_tgl_produksi = date("Y-m-d H:i:s", strtotime($this->input->post('tgl_transaksi', TRUE)));
            }

            $row_barang = $this->_resolve_uuid_barang_produksi(
                $this->input->post('nama_barang', TRUE),
                $this->input->post('satuan', TRUE),
                $date_tgl_produksi
            );

            $data = array(
                'tanggal' => $date_tgl_produksi,
                'kode' => $row_barang->kode_barang,
                'uuid_barang' => $row_barang->uuid_barang,
                'namabarang' => $row_barang->nama_barang,
                'satuan' => $this->input->post('satuan', TRUE),
                'hpp' => $this->input->post('harga_satuan', TRUE),
                'sa' => preg_replace("/[^0-9]/", "", $this->input->post('jumlah_produksi', TRUE)),
                'tanggal_beli' => $date_tgl_produksi,
                'spop' => "produksi",
                'total_10' => preg_replace("/[^0-9]/", "", $this->input->post('jumlah_produksi', TRUE)),
            );
            if ($this->db->field_exists('kode_barang', 'persediaan')) {
                $data['kode_barang'] = $row_barang->kode_barang;
            }
            $this->Persediaan_model->insert($data);

            // End of Kemudian Insert ke tabel persediaan





            $data_unit = $this->Sys_unit_model->get_by_uuid_unit($this->input->post('uuid_unit', TRUE));


            $data = array(
                'uuid_unit' => $data_unit->uuid_unit,
                'kode_unit' => $data_unit->kode_unit,
                'nama_unit' => $data_unit->nama_unit,
                'tgl_transaksi' => $date_tgl_produksi,
                'uuid_produk' => $row_barang->uuid_barang,
                'kode_barang' => $row_barang->kode_barang,
                'nama_barang' => $row_barang->nama_barang,
                'jumlah_produksi' => $this->input->post('jumlah_produksi', TRUE),
                'satuan' => $this->input->post('satuan', TRUE),
                'harga_satuan' => $this->input->post('harga_satuan', TRUE),
            );



            $this->Sys_unit_produk_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('sys_unit_produk'));
        }
    }

    public function update_produk($id_produk)
    {

        // print_r($id_produk);
        // die;
        $row_data_produk = $this->Sys_unit_produk_model->get_by_id($id_produk);
        $uuid_produk = $row_data_produk->uuid_produk;

        print_r($row_data_produk);
        print_r("<br/>");
        print_r("<br/>");
        print_r("<br/>");

        if ($row_data_produk) {
            // $data = array(
            //     'button' => 'Update',
            //     'action' => site_url('Sys_unit_produk/update_produk_action'),
            //     'id' => set_value('id', $row_data_produk->id),

            //     'uuid_unit' => set_value('uuid_unit', $row_data_produk->uuid_unit),
            //     'kode_unit' => set_value('kode_unit', $row_data_produk->kode_unit),
            //     'nama_unit' => set_value('nama_unit', $row_data_produk->nama_unit),

            //     'tgl_transaksi' => set_value('tgl_transaksi', $row_data_produk->tgl_transaksi),

            //     'uuid_barang' => set_value('uuid_barang', $row_data_produk->uuid_barang),
            //     'kode_barang' => set_value('kode_barang', $row_data_produk->kode_barang),
            //     'nama_barang' => set_value('nama_barang', $row_data_produk->nama_barang),

            //     'jumlah_produksi' => set_value('jumlah_produksi', $row_data_produk->jumlah_produksi),
            //     'satuan' => set_value('satuan', $row_data_produk->satuan),
            //     'harga_satuan' => set_value('harga_satuan', $row_data_produk->harga_satuan),
            // );
            // $this->load->view('sys_unit_produk/sys_unit_produk_form', $data);


            // $get_uuid_barang = $this->input->post('uuid_barang', TRUE);
            $sql_stock = "SELECT persediaan.id as id_persediaan_barang, persediaan.uuid_barang as uuid_barang, persediaan.kode_barang as kode_barang, persediaan.namabarang as nama_barang_beli,persediaan.total_10 as jumlah_sediaan,  persediaan.hpp as harga_satuan_persediaan,  persediaan.satuan as satuan
			-- 	tbl_pembelian.uuid_pembelian as uuid_pembelian,tbl_pembelian.uraian as barang_beli, tbl_pembelian.jumlah as jumlah_belanja, tbl_pembelian.harga_satuan as harga_satuan_beli,  tbl_pembelian.tgl_po as tgl_po,tbl_pembelian.uuid_gudang as uuid_gudang, tbl_pembelian.nama_gudang as nama_gudang,  tbl_pembelian.satuan as satuan,
			-- tbl_penjualan.nama_barang as barang_jual, tbl_penjualan.jumlah as jumlah_terjual
			FROM persediaan  
			-- left join tbl_pembelian ON persediaan.uuid_barang = tbl_pembelian.uuid_barang 
			-- left join tbl_penjualan ON persediaan.uuid_barang = tbl_penjualan.uuid_barang  
			WHERE (persediaan.uuid_barang, persediaan.tanggal) IN (SELECT persediaan.uuid_barang, Max(persediaan.tanggal) FROM persediaan GROUP BY persediaan.uuid_barang)  AND persediaan.uuid_barang='$uuid_produk'
			ORDER BY persediaan.uuid_barang ASC";

            $data_persediaan = $this->db->query($sql_stock)->row();


            $data = array(
                'button' => 'Update Produk',
                'button_data_produk' => 'Update Produk',
                'action' => site_url('sys_unit_produk/update_produk_action/' . $uuid_produk),

                'id' => $row_data_produk->id,
                'uuid_unit' => $row_data_produk->uuid_unit,
                'kode_unit' => $row_data_produk->kode_unit,
                'nama_unit' =>  $row_data_produk->nama_unit,
                'tgl_transaksi' => $row_data_produk->tgl_transaksi,

                'id_persediaan_barang' => $data_persediaan->id_persediaan_barang,
                'uuid_barang' => $row_data_produk->uuid_produk,
                'kode_barang' => $row_data_produk->kode_barang,
                'nama_barang' => $row_data_produk->nama_barang,
                'satuan' => $row_data_produk->satuan,
                'harga_satuan' => $row_data_produk->harga_satuan,

                'jumlah_produksi' => $row_data_produk->jumlah_produksi,
            );
            print_r($data);

            $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_unit_produk/adminlte310_sys_unit_produk_form_baru', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_unit_produk'));
        }
    }

    public function update_produk_action($uuid_produk)
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {

            if (date("Y", strtotime($this->input->post('tgl_transaksi', TRUE))) < 2020) {
                $date_tgl_produksi = date("Y-m-d H:i:s");
            } else {
                $date_tgl_produksi = date("Y-m-d H:i:s", strtotime($this->input->post('tgl_transaksi', TRUE)));
            }


            // GET SUPPLIER DATA
            $get_uuid_unit = $this->input->post('uuid_unit', TRUE);
            $sql_uuid_unit = "SELECT * FROM `sys_unit` WHERE `uuid_unit`='$get_uuid_unit'";
            $get_kode_unit = $this->db->query($sql_uuid_unit)->row()->kode_unit;
            $get_nama_unit = $this->db->query($sql_uuid_unit)->row()->nama_unit;

            $data = array(
                'uuid_unit' => $get_uuid_unit,
                'kode_unit' => $get_kode_unit,
                'nama_unit' => $get_nama_unit,
                'tgl_transaksi' => $date_tgl_produksi,
                // 'uuid_produk' => $this->input->post('uuid_barang', TRUE),
                // 'kode_barang' => $this->input->post('kode_barang', TRUE),
                'nama_barang' => $this->input->post('nama_barang', TRUE),
                'jumlah_produksi' => preg_replace("/[^0-9]/", "", $this->input->post('jumlah_produksi', TRUE)),
                'satuan' => $this->input->post('satuan', TRUE),
                'harga_satuan' => preg_replace("/[^0-9]/", "", $this->input->post('harga_satuan', TRUE)),
            );

            $this->Sys_unit_produk_model->update($this->input->post('id', TRUE), $data);

            $id_persediaan_barang = $this->input->post('id_persediaan_barang', TRUE);

            if (date("Y", strtotime($this->input->post('tgl_transaksi', TRUE))) < 2020) {
                $date_tgl_produksi = date("Y-m-d H:i:s");
            } else {
                $date_tgl_produksi = date("Y-m-d H:i:s", strtotime($this->input->post('tgl_transaksi', TRUE)));
            }



            $data = array(
                // 'id' => $this->input->post('id', TRUE),
                // 'tanggal' => $date_tgl_produksi,
                // 'tanggal_new' => $date_persediaan,
                // 'uuid_barang' => $row_sys_nama_barang->uuid_barang,
                // 'kode' => $row_sys_nama_barang->kode_barang,
                'namabarang' => $this->input->post('nama_barang', TRUE),
                'satuan' => $this->input->post('satuan', TRUE),
                'hpp' => preg_replace("/[^0-9]/", "", $this->input->post('harga_satuan', TRUE)),
                'sa' => preg_replace("/[^0-9]/", "", $this->input->post('jumlah_produksi', TRUE)),
                'tanggal_beli' => $this->_tanggal_beli_bulan_dari_transaksi($this->input->post('tgl_transaksi', TRUE)),
                'tanggal' => $date_tgl_produksi,
                'spop' => "produksi",
                'total_10' => preg_replace("/[^0-9]/", "", $this->input->post('jumlah_produksi', TRUE)),
            );

            // SIMPAN KE PERSEDIAAN
            $this->Persediaan_model->update($id_persediaan_barang, $data);


            // print_r("update pembelian by uuid_barang & update sys_nama_barang ==> Belum selesau");
            // die;

            // SIMPAN KE TABEL PEMBELIAN




            $data = array(
                // 'date_input' => date("Y-m-d H:i:s"),
                'tgl_po' => $date_tgl_produksi,
                'nmrsj' => "produksi",
                'nmrfakturkwitansi' => "produksi",
                'nmrbpb' => "produksi",
                'spop' => "produksi",

                'supplier_kode' => $get_kode_unit,
                'uuid_supplier' => $get_uuid_unit,
                'supplier_nama' => $get_nama_unit,





                // 'uuid_barang' => $row_sys_nama_barang->uuid_barang,
                // 'kode_barang' => $row_sys_nama_barang->kode_barang,
                'uraian' => $this->input->post('nama_barang', TRUE),

                'jumlah' => preg_replace("/[^0-9]/", "", $this->input->post('jumlah_produksi', TRUE)),
                'satuan' => $this->input->post('satuan', TRUE),
                // 'konsumen' => $get_nama_konsumen,
                'harga_satuan' => preg_replace("/[^0-9]/", "", $this->input->post('harga_satuan', TRUE)),
                // 'harga_total' => $this->input->post('harga_total', TRUE),
                // 'statuslu' => $this->input->post('statuslu', TRUE),
                // 'kas_bank' => $this->input->post('kas_bank', TRUE),
                // 'uuid_gudang' => $this->input->post('uuid_gudang', TRUE),
                // 'nama_gudang' => $get_nama_gudang,
                // 'id_usr' => 1,
            );

            $this->Tbl_pembelian_model->update($uuid_produk, $data);

            // update sys_nama_barang



            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('sys_unit_produk'));
        }
    }


    public function update($id)
    {
        $row = $this->Sys_unit_produk_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('sys_unit_produk/update_action'),
                'id' => set_value('id', $row->id),
                'uuid_unit' => set_value('uuid_unit', $row->uuid_unit),
                'kode_unit' => set_value('kode_unit', $row->kode_unit),
                'nama_unit' => set_value('nama_unit', $row->nama_unit),
                'tgl_transaksi' => set_value('tgl_transaksi', $row->tgl_transaksi),
                'uuid_barang' => set_value('uuid_barang', $row->uuid_barang),
                'kode_barang' => set_value('kode_barang', $row->kode_barang),
                'nama_barang' => set_value('nama_barang', $row->nama_barang),
                'jumlah_produksi' => set_value('jumlah_produksi', $row->jumlah_produksi),
                'satuan' => set_value('satuan', $row->satuan),
                'harga_satuan' => set_value('harga_satuan', $row->harga_satuan),
            );
            $this->load->view('sys_unit_produk/sys_unit_produk_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_unit_produk'));
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
                'kode_unit' => $this->input->post('kode_unit', TRUE),
                'nama_unit' => $this->input->post('nama_unit', TRUE),
                'tgl_transaksi' => $this->input->post('tgl_transaksi', TRUE),
                'uuid_barang' => $this->input->post('uuid_barang', TRUE),
                'kode_barang' => $this->input->post('kode_barang', TRUE),
                'nama_barang' => $this->input->post('nama_barang', TRUE),
                'jumlah_produksi' => $this->input->post('jumlah_produksi', TRUE),
                'satuan' => $this->input->post('satuan', TRUE),
                'harga_satuan' => $this->input->post('harga_satuan', TRUE),
            );

            $this->Sys_unit_produk_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('sys_unit_produk'));
        }
    }

    public function delete($id)
    {
        $row = $this->Sys_unit_produk_model->get_by_id($id);

        if ($row) {
            $this->Sys_unit_produk_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('sys_unit_produk'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_unit_produk'));
        }
    }

    private function _parse_jumlah_angka($value)
    {
        return max(0, (int) preg_replace('/[^0-9]/', '', (string) $value));
    }

    private function _parse_tanggal_transaksi_ts($tgl_transaksi = null)
    {
        $this->load->helper('pembelian_persediaan');
        $ts = pembelian_parse_tanggal_po($tgl_transaksi);
        if ($ts === false) {
            return time();
        }

        return $ts;
    }

    private function _resolve_id_persediaan_produk($id_dari_url = null)
    {
        $id = (int) $id_dari_url;
        if ($id <= 0) {
            $id = (int) $this->input->post('id_persediaan_barang', TRUE);
        }

        return $id;
    }

    private function _generate_spop_produksi($date_tgl_produksi)
    {
        $kode_tgl_produksi = date('Ymd', strtotime($date_tgl_produksi));
        $waktu_tgl_produksi = date('Hi', strtotime($date_tgl_produksi));
        $spop_produksi = 'PRODUKSI_' . $kode_tgl_produksi . $waktu_tgl_produksi;

        $this->db->where('spop', $spop_produksi);
        $jumlah_spop = $this->db->count_all_results('persediaan');
        if ($jumlah_spop > 0) {
            $spop_produksi = 'PRODUKSI_' . $kode_tgl_produksi . '_' . ($jumlah_spop + 1);
        }

        return $spop_produksi;
    }

    private function _tanggal_produksi_dari_input($tgl_transaksi = null)
    {
        $ts = $this->_parse_tanggal_transaksi_ts($tgl_transaksi);
        if (date('Y', $ts) < 2020) {
            return date('Y-m-d H:i:s');
        }

        return date('Y-m-d H:i:s', $ts);
    }

    private function _tanggal_beli_bulan_dari_transaksi($tgl_transaksi = null)
    {
        $ts = $this->_parse_tanggal_transaksi_ts($tgl_transaksi);
        if (date('Y', $ts) < 2020) {
            return date('Y-m-01');
        }

        return date('Y-m-01', $ts);
    }

    private function _bulan_nama_indonesia($bulan_ym)
    {
        $nama_bulan = array(
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        );
        if (!preg_match('/^(\d{4})-(\d{2})$/', $bulan_ym, $m)) {
            return date('F Y');
        }
        $bulan = (int) $m[2];
        return (isset($nama_bulan[$bulan]) ? $nama_bulan[$bulan] : $m[2]) . ' ' . $m[1];
    }

    private function _resolve_bulan_produksi_selected($bulan_input = null)
    {
        $bulan = trim((string) $bulan_input);
        if ($bulan === '' && $this->input->get('bulan')) {
            $bulan = trim((string) $this->input->get('bulan', TRUE));
        }
        if ($bulan === '' && $this->session->userdata('bulan_produksi_selected')) {
            $bulan = trim((string) $this->session->userdata('bulan_produksi_selected'));
        }
        if (!$bulan || !preg_match('/^\d{4}-\d{2}$/', $bulan)) {
            $bulan = date('Y-m');
        }
        $this->session->set_userdata('bulan_produksi_selected', $bulan);
        return $bulan;
    }

    private function _get_stock_persediaan_by_bulan($bulan_ym)
    {
        if (!$bulan_ym || !preg_match('/^\d{4}-\d{2}$/', $bulan_ym)) {
            return array();
        }
        $sql_stock = "SELECT persediaan.id as id,
                        persediaan.uuid_persediaan as uuid_persediaan,
                        persediaan.tanggal_beli as tanggal_beli,
                        persediaan.uuid_spop as uuid_spop,
                        persediaan.spop as spop,
                        persediaan.uuid_barang as uuid_barang,
                        persediaan.kode_barang as kode_barang,
                        persediaan.namabarang as nama_barang_beli,
                        persediaan.total_10 as jumlah_sediaan,
                        persediaan.hpp as harga_satuan_persediaan,
                        persediaan.satuan as satuan_persediaan,
                        persediaan.pecah_satuan as pecah_satuan_persediaan,
                        persediaan.bahan_produksi as bahan_produksi,
                        persediaan.penjualan as penjualan
                    FROM persediaan
                    WHERE TRIM(COALESCE(persediaan.namabarang, '')) <> ''
                    AND persediaan.tanggal_beli IS NOT NULL
                    AND TRIM(persediaan.tanggal_beli) <> ''
                    AND DATE_FORMAT(persediaan.tanggal_beli, '%Y-%m') = " . $this->db->escape($bulan_ym) . "
                    ORDER BY persediaan.namabarang ASC, persediaan.id ASC";
        return $this->db->query($sql_stock)->result();
    }

    private function _persediaan_bulan_ym_dari_tanggal_beli($tanggal_beli)
    {
        $tanggal_beli = trim((string) $tanggal_beli);
        if ($tanggal_beli === '') {
            return '';
        }
        $ts = strtotime($tanggal_beli);
        if (!$ts) {
            return '';
        }
        return date('Y-m', $ts);
    }

    private function _persediaan_sesuai_bulan_produksi($row_persediaan, $bulan_ym)
    {
        if (!$row_persediaan || !isset($row_persediaan->tanggal_beli)) {
            return false;
        }
        if (!$bulan_ym || !preg_match('/^\d{4}-\d{2}$/', $bulan_ym)) {
            return false;
        }
        return $this->_persediaan_bulan_ym_dari_tanggal_beli($row_persediaan->tanggal_beli) === $bulan_ym;
    }

    private function _enrich_produksi_form_data($data)
    {
        if (!isset($data['data_bahan_produk_unit']) || !is_array($data['data_bahan_produk_unit'])) {
            $data['data_bahan_produk_unit'] = array();
        }
        $data['jumlah_bahan_count'] = count($data['data_bahan_produk_unit']);
        $data['produk_sudah_ada'] = false;
        $id = isset($data['id_persediaan_barang']) ? (int) $data['id_persediaan_barang'] : 0;
        if ($id > 0) {
            $row_p = $this->Persediaan_model->get_by_id($id);
            if ($row_p) {
                $data['produk_sudah_ada'] = (bool) $this->Sys_unit_produk_model->get_by_uuid_persediaan($row_p->uuid_persediaan);
            }
            if ($data['produk_sudah_ada']) {
                $data['action_simpan_produk_form'] = site_url('sys_unit_produk/UPDATE_action_simpan_nama_produk_baru/' . $id);
                $data['label_btn_produk'] = 'Update Produk';
            } else {
                $data['action_simpan_produk_form'] = site_url('sys_unit_produk/action_simpan_nama_produk_baru/' . $id);
                $data['label_btn_produk'] = 'Simpan';
            }
        } else {
            $data['action_simpan_produk_form'] = '';
            $data['label_btn_produk'] = 'Simpan';
        }
        return $data;
    }

    private function _produksi_bulan_view_data($bulan_override = null)
    {
        $bulan_selected = $this->_resolve_bulan_produksi_selected($bulan_override);
        $tanggal_beli_bulan = $bulan_selected . '-01';
        return array(
            'bulan_produksi_selected' => $bulan_selected,
            'bulan_produksi_label' => $this->_bulan_nama_indonesia($bulan_selected),
            'tanggal_beli_bulan' => $tanggal_beli_bulan,
            'tgl_transaksi_bahan' => $tanggal_beli_bulan,
            'Data_stock' => $this->_get_stock_persediaan_by_bulan($bulan_selected),
            'url_ajax_stock_persediaan' => site_url('Sys_unit_produk/ajax_stock_persediaan_by_bulan'),
            'bulan_produksi_ym' => $bulan_selected,
        );
    }

    private function _resolve_id_persediaan_bahan_bulan_produksi($id_persediaan_picker, $uuid_persediaan_bahan, $tanggal_beli_bulan)
    {
        $id_persediaan_picker = (int) $id_persediaan_picker;
        $tanggal_beli_bulan = trim((string) $tanggal_beli_bulan);
        $uuid_persediaan_bahan = trim((string) $uuid_persediaan_bahan);

        if ($tanggal_beli_bulan === '') {
            return $id_persediaan_picker;
        }

        if ($uuid_persediaan_bahan !== '') {
            $row = $this->db->query(
                "SELECT `id` FROM `persediaan`
                WHERE `uuid_persediaan` = ?
                AND (
                    `tanggal_beli` = ?
                    OR DATE_FORMAT(`tanggal_beli`, '%Y-%m') = DATE_FORMAT(?, '%Y-%m')
                )
                ORDER BY `id` ASC
                LIMIT 1",
                array($uuid_persediaan_bahan, $tanggal_beli_bulan, $tanggal_beli_bulan)
            )->row();
            if ($row) {
                return (int) $row->id;
            }
        }

        $picker = $id_persediaan_picker > 0 ? $this->Persediaan_model->get_by_id($id_persediaan_picker) : null;
        if ($picker) {
            $uuid_barang = $this->_uuid_barang_dari_persediaan($picker);
            if ($uuid_barang !== '') {
                $row = $this->db->query(
                    "SELECT `id` FROM `persediaan`
                    WHERE `uuid_barang` = ?
                    AND (
                        `tanggal_beli` = ?
                        OR DATE_FORMAT(`tanggal_beli`, '%Y-%m') = DATE_FORMAT(?, '%Y-%m')
                    )
                    ORDER BY `id` ASC
                    LIMIT 1",
                    array($uuid_barang, $tanggal_beli_bulan, $tanggal_beli_bulan)
                )->row();
                if ($row) {
                    return (int) $row->id;
                }
            }

            $nama = trim((string) $picker->namabarang);
            $satuan = trim((string) $picker->satuan);
            if ($nama !== '') {
                $row = $this->db->query(
                    "SELECT `id` FROM `persediaan`
                    WHERE `namabarang` = ?
                    AND `satuan` = ?
                    AND (
                        `tanggal_beli` = ?
                        OR DATE_FORMAT(`tanggal_beli`, '%Y-%m') = DATE_FORMAT(?, '%Y-%m')
                    )
                    ORDER BY `id` ASC
                    LIMIT 1",
                    array($nama, $satuan, $tanggal_beli_bulan, $tanggal_beli_bulan)
                )->row();
                if ($row) {
                    return (int) $row->id;
                }
            }
        }

        return $id_persediaan_picker;
    }

    private function _update_persediaan_bahan_produksi($id_persediaan_bahan, $jumlah_bahan_tambah)
    {
        $id_persediaan_bahan = (int) $id_persediaan_bahan;
        $jumlah_bahan_tambah = $this->_parse_jumlah_angka($jumlah_bahan_tambah);
        if ($id_persediaan_bahan <= 0 || $jumlah_bahan_tambah <= 0) {
            return false;
        }

        if (!$this->db->field_exists('bahan_produksi', 'persediaan')) {
            return false;
        }

        $row = $this->Persediaan_model->get_by_id($id_persediaan_bahan);
        if (!$row) {
            return false;
        }

        $bahan_lama = $this->_parse_jumlah_angka(isset($row->bahan_produksi) ? $row->bahan_produksi : 0);
        $total_10_lama = $this->_parse_jumlah_angka(isset($row->total_10) ? $row->total_10 : 0);
        $hpp = $this->_parse_jumlah_angka(isset($row->hpp) ? $row->hpp : 0);

        $bahan_baru = $bahan_lama + $jumlah_bahan_tambah;
        $total_10_baru = max(0, $total_10_lama - $jumlah_bahan_tambah);
        $nilai_persediaan = (int) floor($total_10_baru * $hpp);

        $update = array(
            'bahan_produksi' => (string) $bahan_baru,
            'total_10' => (string) $total_10_baru,
            'nilai_persediaan' => (string) $nilai_persediaan,
        );
        if ($this->db->field_exists('tuj', 'persediaan')) {
            $update['tuj'] = (string) $total_10_baru;
        }

        $this->Persediaan_model->update($id_persediaan_bahan, $update);

        return true;
    }

    private function _uuid_barang_dari_persediaan($row)
    {
        if (!$row) {
            return '';
        }
        if (!empty($row->uuid_barang)) {
            return $row->uuid_barang;
        }
        return !empty($row->uuid_persediaan) ? $row->uuid_persediaan : '';
    }

    private function _kode_barang_dari_persediaan($row)
    {
        if (!$row) {
            return '';
        }
        if (isset($row->kode_barang) && trim((string) $row->kode_barang) !== '') {
            return $row->kode_barang;
        }
        return isset($row->kode) ? $row->kode : '';
    }

    private function _generate_kode_barang_dari_nama($nama_barang)
    {
        $get_kode_barang = '';
        $split = explode(' ', (string) $nama_barang);
        foreach ($split as $kata) {
            if (trim($kata) === '') {
                continue;
            }
            $get_kode_barang .= substr($kata, 0, 2);
        }
        $get_kode_barang = strtoupper($get_kode_barang);

        $this->db->group_start();
        $this->db->where('kode', $get_kode_barang);
        if ($this->db->field_exists('kode_barang', 'persediaan')) {
            $this->db->or_where('kode_barang', $get_kode_barang);
        }
        $this->db->group_end();
        $jumlah = $this->db->count_all_results('persediaan');
        if ($jumlah > 0) {
            $get_kode_barang = $get_kode_barang . '_' . ($jumlah + 1);
        }

        return $get_kode_barang;
    }

    private function _generate_uuid_barang_baru()
    {
        $row = $this->db->query("SELECT REPLACE(UUID(),'-','') AS u")->row();
        return ($row && trim((string) $row->u) !== '') ? trim((string) $row->u) : '';
    }

    private function _resolve_uuid_barang_produksi($nama_barang, $satuan = '', $tanggal = null)
    {
        $row = $this->Persediaan_model->get_by_namabarang($nama_barang);
        if ($row) {
            return (object) array(
                'uuid_barang' => $this->_uuid_barang_dari_persediaan($row),
                'kode_barang' => $this->_kode_barang_dari_persediaan($row),
                'nama_barang' => $row->namabarang,
            );
        }

        return (object) array(
            'uuid_barang' => $this->_generate_uuid_barang_baru(),
            'kode_barang' => $this->_generate_kode_barang_dari_nama($nama_barang),
            'nama_barang' => trim((string) $nama_barang),
        );
    }

    public function _rules()
    {
        // $this->form_validation->set_rules('uuid_unit', 'uuid unit', 'trim|required');
        // $this->form_validation->set_rules('kode_unit', 'kode unit', 'trim|required');
        // $this->form_validation->set_rules('nama_unit', 'nama unit', 'trim|required');
        $this->form_validation->set_rules('tgl_transaksi', 'tgl transaksi', 'trim|required');
        // $this->form_validation->set_rules('uuid_barang', 'uuid barang', 'trim|required');
        // $this->form_validation->set_rules('kode_barang', 'kode barang', 'trim|required');
        $this->form_validation->set_rules('nama_barang', 'nama barang', 'trim|required');
        $this->form_validation->set_rules('jumlah_produksi', 'jumlah produksi', 'trim|required');
        $this->form_validation->set_rules('satuan', 'satuan', 'trim|required');
        $this->form_validation->set_rules('harga_satuan', 'harga satuan', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "sys_unit_produk.xls";
        $judul = "sys_unit_produk";
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
        xlsWriteLabel($tablehead, $kolomhead++, "Kode Unit");
        xlsWriteLabel($tablehead, $kolomhead++, "Nama Unit");
        xlsWriteLabel($tablehead, $kolomhead++, "Tgl Transaksi");
        xlsWriteLabel($tablehead, $kolomhead++, "Uuid Barang");
        xlsWriteLabel($tablehead, $kolomhead++, "Kode Barang");
        xlsWriteLabel($tablehead, $kolomhead++, "Nama Barang");
        xlsWriteLabel($tablehead, $kolomhead++, "Jumlah Produksi");
        xlsWriteLabel($tablehead, $kolomhead++, "Satuan");
        xlsWriteLabel($tablehead, $kolomhead++, "Harga Satuan");

        foreach ($this->Sys_unit_produk_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->uuid_unit);
            xlsWriteLabel($tablebody, $kolombody++, $data->kode_unit);
            xlsWriteLabel($tablebody, $kolombody++, $data->nama_unit);
            xlsWriteLabel($tablebody, $kolombody++, $data->tgl_transaksi);
            xlsWriteLabel($tablebody, $kolombody++, $data->uuid_barang);
            xlsWriteNumber($tablebody, $kolombody++, $data->kode_barang);
            xlsWriteLabel($tablebody, $kolombody++, $data->nama_barang);
            xlsWriteNumber($tablebody, $kolombody++, $data->jumlah_produksi);
            xlsWriteLabel($tablebody, $kolombody++, $data->satuan);
            xlsWriteNumber($tablebody, $kolombody++, $data->harga_satuan);

            $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }
}

/* End of file Sys_unit_produk.php */
/* Location: ./application/controllers/Sys_unit_produk.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-09-30 05:21:50 */
/* http://harviacode.com */