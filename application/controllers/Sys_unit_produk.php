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
        foreach ($Sys_unit_produk_data as $row_produk) {
            $row_produk->sudah_terjual = $this->is_produk_sudah_terjual($row_produk);
        }

        $data = array(
            'action' => site_url('Sys_unit_produk/simpan_produk_baru'),
            'Sys_unit_produk_data' => $Sys_unit_produk_data,
            'bulan_produksi_selected' => $bulan_selected,
            'url_ajax_list_by_bulan' => site_url('Sys_unit_produk/ajax_list_by_bulan'),
            'url_create_produksi' => site_url('Sys_unit_produk/create_produksi'),
            'url_create_produksi_tanpa_bahan' => site_url('Sys_unit_produk/create_produksi_tanpa_bahan'),
            'url_ajax_delete_produksi' => site_url('Sys_unit_produk/ajax_delete_produksi'),
            'url_ajax_cek_produksi_hapus' => site_url('Sys_unit_produk/ajax_cek_produksi_hapus'),
            'url_ajax_list_penjualan_sold_out' => site_url('Sys_unit_produk/ajax_list_penjualan_sold_out'),
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
            $sudah_terjual = $this->is_produk_sudah_terjual($list_data);

            $rows[] = array(
                'no' => ++$start,
                'produk_id' => (int) $list_data->id,
                'uuid_persediaan' => isset($list_data->uuid_persediaan) ? (string) $list_data->uuid_persediaan : '',
                'can_edit' => $persediaan_id > 0,
                'sudah_terjual' => $sudah_terjual,
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
        $id_persediaan_bahan = (int) $this->input->post('id_persediaan', TRUE);
        $jumlah_bahan_input = $this->input->post('jumlah', TRUE);
        $tgl_transaksi_input = $this->input->post('tgl_transaksi', TRUE);

        if ($id_persediaan_barang) {
            $this->_simpan_draft_produk_produksi_dari_post($id_persediaan_barang);
        }

        $bulan_proses = $this->_resolve_bulan_dari_input_produksi(
            $this->input->post('draft_tgl_transaksi', TRUE),
            $this->input->post('bulan_produksi', TRUE)
        );

        if ($id_persediaan_bahan <= 0) {
            $this->session->set_flashdata(
                'message',
                'Record persediaan bahan belum dipilih. Pilih barang dari daftar persediaan (1 record) lalu simpan.'
            );
            $this->_redirect_create_produksi_dengan_bulan($id_persediaan_barang, $bulan_proses);
            return;
        }

        $row_persediaan_bahan = $this->Persediaan_model->get_by_id($id_persediaan_bahan);
        if (!$row_persediaan_bahan) {
            $this->session->set_flashdata(
                'message',
                'Record persediaan bahan dengan id ' . $id_persediaan_bahan . ' tidak ditemukan.'
            );
            $this->_redirect_create_produksi_dengan_bulan($id_persediaan_barang, $bulan_proses);
            return;
        }

        if (!$this->_persediaan_sesuai_bulan_produksi($row_persediaan_bahan, $bulan_proses)) {
            $this->session->set_flashdata(
                'message',
                'Barang tidak boleh diproses. Tanggal beli persediaan harus sesuai bulan pemrosesan (' . $this->_bulan_nama_indonesia($bulan_proses) . ').'
            );
            $this->_redirect_create_produksi_dengan_bulan($id_persediaan_barang, $bulan_proses);
            return;
        }

        $uuid_persediaan_produk = '';
        if ($id_persediaan_barang) {
            $data_barang_selected = $this->Persediaan_model->get_by_id($id_persediaan_barang);
            if (!$data_barang_selected) {
                $this->session->set_flashdata('message', 'Record persediaan produk tidak ditemukan.');
                $this->_redirect_create_produksi_dengan_bulan($id_persediaan_barang, $bulan_proses);
                return;
            }
            if ($tgl_transaksi_input === '' || $tgl_transaksi_input === null) {
                $tgl_transaksi_input = $data_barang_selected->tanggal;
            }
            $uuid_persediaan_produk = $data_barang_selected->uuid_persediaan;
        } else {
            $date_tgl_produksi = $this->_tanggal_produksi_dari_input($tgl_transaksi_input);
            $tanggal_beli = $this->_tanggal_beli_bulan_dari_transaksi($tgl_transaksi_input);

            $data = array(
                'tanggal' => $date_tgl_produksi,
                'tanggal_beli' => $tanggal_beli,
                'spop' => $this->_generate_spop_produksi(
                    $date_tgl_produksi,
                    $this->_ambil_post_draft_produk_field('draft_nama_barang', 'nama_barang')
                ),
            );
            $id_persediaan_barang = $this->Persediaan_model->insert_produk_baru($data);

            $row_persediaan_produk_baru = $this->Persediaan_model->get_by_id($id_persediaan_barang);
            if (!$row_persediaan_produk_baru) {
                $this->session->set_flashdata('message', 'Gagal membuat draft persediaan produk.');
                $this->_redirect_create_produksi_dengan_bulan(null, $bulan_proses);
                return;
            }

            $uuid_persediaan_produk = $row_persediaan_produk_baru->uuid_persediaan;
            $tgl_transaksi_input = $date_tgl_produksi;
        }

        $data_bahan = $this->_build_data_sys_unit_produk_bahan_dari_persediaan(
            $row_persediaan_bahan,
            $uuid_persediaan_produk,
            $tgl_transaksi_input,
            $jumlah_bahan_input
        );
        $this->Sys_unit_produk_bahan_model->insert($data_bahan);

        // Update stock persediaan bahan berdasarkan id record yang dipilih (bukan uuid_persediaan).
        $this->_update_persediaan_bahan_produksi($id_persediaan_bahan, $jumlah_bahan_input);

        $this->_simpan_draft_produk_produksi_dari_post($id_persediaan_barang);
        $this->_redirect_create_produksi_dengan_bulan($id_persediaan_barang, $bulan_proses);
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
        $data = $this->_apply_produksi_draft_ke_form_data($data);
        $data = $this->_sync_bulan_produksi_dari_tgl_form($data);
        $data = $this->_enrich_produksi_form_data($data);
        $data['mode_update_produksi'] = false;
        $data['bulan_produksi_terkunci'] = '';
        $data['bulan_produksi_terkunci_label'] = '';
        $data['tgl_transaksi_awal'] = '';
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_unit_produk/adminlte310_sys_unit_produk_form_baru', $data);
    }

    /**
     * Cancel draft produksi: kembalikan stock semua bahan, hapus sys_unit_produk_bahan,
     * hapus draft persediaan produk (jika produk belum disimpan), lalu kembali ke list.
     */
    public function cancel_produksi($id_persediaan_barang = null)
    {
        $id_persediaan_barang = (int) $id_persediaan_barang;
        $bulan = trim((string) $this->input->get_post('bulan', TRUE));
        if ($bulan === '' || !preg_match('/^\d{4}-\d{2}$/', $bulan)) {
            $bulan = trim((string) $this->session->userdata('bulan_produksi_selected'));
        }
        if ($bulan === '' || !preg_match('/^\d{4}-\d{2}$/', $bulan)) {
            $bulan = date('Y-m');
        }
        $url_list = site_url('Sys_unit_produk') . '?bulan=' . urlencode($bulan);

        if ($id_persediaan_barang <= 0) {
            $this->session->set_flashdata('message', 'Tidak ada draft produksi yang dibatalkan.');
            redirect($url_list);
            return;
        }

        $row_persediaan_produk = $this->Persediaan_model->get_by_id($id_persediaan_barang);
        if (!$row_persediaan_produk) {
            $this->session->set_flashdata('message', 'Record persediaan produk tidak ditemukan.');
            redirect($url_list);
            return;
        }

        $uuid_persediaan_produk = trim((string) $row_persediaan_produk->uuid_persediaan);
        $produk_sudah_ada = ($uuid_persediaan_produk !== '')
            ? (bool) $this->Sys_unit_produk_model->get_by_uuid_persediaan($uuid_persediaan_produk)
            : false;

        // Produk yang sudah tersimpan di sys_unit_produk tidak boleh di-cancel lewat form create.
        if ($produk_sudah_ada) {
            $this->_hapus_draft_produk_produksi($id_persediaan_barang);
            $this->session->set_flashdata(
                'message',
                'Produk sudah tersimpan. Gunakan hapus dari daftar produk jika ingin membatalkan produksi.'
            );
            redirect($url_list);
            return;
        }

        $bulan_produksi_ym = '';
        if (!empty($row_persediaan_produk->tanggal_beli)) {
            $ts = strtotime($row_persediaan_produk->tanggal_beli);
            if ($ts !== false) {
                $bulan_produksi_ym = date('Y-m', $ts);
            }
        }
        if ($bulan_produksi_ym === '' && !empty($row_persediaan_produk->tanggal)) {
            $ts = strtotime($row_persediaan_produk->tanggal);
            if ($ts !== false) {
                $bulan_produksi_ym = date('Y-m', $ts);
            }
        }
        if ($bulan_produksi_ym === '') {
            $bulan_produksi_ym = $bulan;
        }

        $list_bahan = ($uuid_persediaan_produk !== '')
            ? $this->Sys_unit_produk_bahan_model->get_by_uuid_persediaan($uuid_persediaan_produk)
            : array();

        $this->db->trans_start();

        // 1) Semua bahan yang sudah diinput: kembalikan stock (bahan_produksi -, total_10 +)
        foreach ($list_bahan as $bahan) {
            $jumlah_bahan = $this->_parse_jumlah_angka(isset($bahan->jumlah_bahan) ? $bahan->jumlah_bahan : 0);
            if ($jumlah_bahan <= 0) {
                continue;
            }

            $id_persediaan_bahan = 0;
            if ($this->db->field_exists('id_persediaan', 'sys_unit_produk_bahan')
                && !empty($bahan->id_persediaan)) {
                $id_persediaan_bahan = (int) $bahan->id_persediaan;
            }

            if ($id_persediaan_bahan > 0) {
                $this->_kembalikan_persediaan_bahan_saat_cancel($id_persediaan_bahan, $jumlah_bahan);
            } else {
                $uuid_bahan = trim((string) (isset($bahan->uuid_persediaan_bahan) ? $bahan->uuid_persediaan_bahan : ''));
                if ($uuid_bahan !== '') {
                    $tanggal_beli_bulan = $bulan_produksi_ym !== '' ? ($bulan_produksi_ym . '-01') : '';
                    $id_fallback = $this->_resolve_id_persediaan_bahan_bulan_produksi(0, $uuid_bahan, $tanggal_beli_bulan);
                    if ($id_fallback > 0) {
                        $this->_kembalikan_persediaan_bahan_saat_cancel($id_fallback, $jumlah_bahan);
                    }
                }
            }
        }

        // 2) Hapus semua record bahan untuk uuid_persediaan produk ini
        if ($uuid_persediaan_produk !== '') {
            $this->db->where('uuid_persediaan', $uuid_persediaan_produk);
            $this->db->delete('sys_unit_produk_bahan');
        }

        // 3) Hapus draft record produk di persediaan (belum pernah di-simpan ke sys_unit_produk)
        $this->Persediaan_model->delete($id_persediaan_barang);

        $this->db->trans_complete();
        $this->_hapus_draft_produk_produksi($id_persediaan_barang);

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('message', 'Gagal membatalkan produksi. Transaksi dibatalkan.');
            redirect(site_url('Sys_unit_produk/create_produksi/' . $id_persediaan_barang . '?bulan=' . urlencode($bulan)));
            return;
        }

        $this->session->set_flashdata(
            'message',
            'Produksi dibatalkan. Stock bahan dikembalikan dan data bahan draft dihapus.'
        );
        redirect($url_list);
    }

    /**
     * Kembalikan 1 record persediaan bahan saat cancel: bahan_produksi - jumlah, total_10 + jumlah.
     */
    private function _kembalikan_persediaan_bahan_saat_cancel($id_persediaan_bahan, $jumlah_bahan)
    {
        $id_persediaan_bahan = (int) $id_persediaan_bahan;
        $jumlah_bahan = $this->_parse_jumlah_angka($jumlah_bahan);
        if ($id_persediaan_bahan <= 0 || $jumlah_bahan <= 0) {
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

        $bahan_baru = max(0, $bahan_lama - $jumlah_bahan);
        $total_10_baru = $total_10_lama + $jumlah_bahan;
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

    public function create_produksi_tanpa_bahan($id_persediaan_barang = null)
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
        $data = $this->_apply_produksi_draft_ke_form_data($data);
        $data = $this->_sync_bulan_produksi_dari_tgl_form($data);
        $data = $this->_enrich_produksi_form_data($data);
        $data['mode_update_produksi'] = false;
        $data['bulan_produksi_terkunci'] = '';
        $data['bulan_produksi_terkunci_label'] = '';
        $data['tgl_transaksi_awal'] = '';
        $data['mode_produksi_tanpa_bahan'] = true;
        $data['action_simpan_produk_form'] = site_url('Sys_unit_produk/action_simpan_produksi_tanpa_bahan');
        $data['label_btn_produk'] = 'Simpan';
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_unit_produk/adminlte310_sys_unit_produk_form_baru_tanpa_bahan', $data);
    }
    public function update_produksi($id_persediaan_barang = null)
    {
        $bulan_produksi_terkunci = '';
        $tgl_transaksi_awal = '';
        // print_r("create_produksi");
        // print_r("<br/>");
        if ($id_persediaan_barang) {
            // print_r("Sudah Ada");
            // print_r("<br/>");
            // print_r($id_persediaan_barang);

            $data_barang_selected = $this->Persediaan_model->get_by_id($id_persediaan_barang);


            $get_data_produk_unit = $this->Sys_unit_produk_model->get_by_uuid_persediaan($data_barang_selected->uuid_persediaan);
            $get_result_data_bahan_produk_unit = $this->Sys_unit_produk_bahan_model->get_by_uuid_persediaan($data_barang_selected->uuid_persediaan);

            $tgl_asli = $get_data_produk_unit ? $get_data_produk_unit->tgl_transaksi : $data_barang_selected->tanggal;
            $ts_asli = $this->_parse_tanggal_transaksi_ts($tgl_asli);
            if ($ts_asli) {
                $bulan_produksi_terkunci = date('Y-m', $ts_asli);
                $tgl_transaksi_awal = date('d-m-Y H:i:s', $ts_asli);
            }

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
                // Pakai tgl produksi record, bukan tanggal_beli persediaan (uuid_persediaan bisa lintas bulan)
                'tanggal_beli_bulan' => $this->_tanggal_beli_bulan_dari_transaksi(
                    $get_data_produk_unit && !empty($get_data_produk_unit->tgl_transaksi)
                        ? $get_data_produk_unit->tgl_transaksi
                        : (!empty($data_barang_selected->tanggal) ? $data_barang_selected->tanggal : $data_barang_selected->tanggal_beli)
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
        $bulan_untuk_view = $this->input->get('bulan', TRUE);
        if ((!$bulan_untuk_view || !preg_match('/^\d{4}-\d{2}$/', $bulan_untuk_view)) && $bulan_produksi_terkunci !== '') {
            $bulan_untuk_view = $bulan_produksi_terkunci;
        }
        $data = array_merge($data, $this->_produksi_bulan_view_data($bulan_untuk_view));
        $data = $this->_apply_produksi_draft_ke_form_data($data);
        $data = $this->_sync_bulan_produksi_dari_tgl_form($data);
        $data = $this->_enrich_produksi_form_data($data);
        $data['mode_update_produksi'] = !empty($id_persediaan_barang);
        $data['bulan_produksi_terkunci'] = $bulan_produksi_terkunci;
        $data['bulan_produksi_terkunci_label'] = $bulan_produksi_terkunci !== ''
            ? $this->_bulan_nama_indonesia($bulan_produksi_terkunci)
            : '';
        $data['tgl_transaksi_awal'] = $tgl_transaksi_awal;
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_unit_produk/adminlte310_sys_unit_produk_form_baru', $data);
    }

    public function action_simpan_produksi_tanpa_bahan()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }
        header('Content-Type: application/json; charset=utf-8');

        $kosong = array();
        $tgl_input = trim((string) $this->input->post('tgl_transaksi', TRUE));
        $uuid_unit = trim((string) $this->input->post('uuid_unit', TRUE));
        $nama_barang_input = trim((string) $this->input->post('nama_barang', TRUE));
        $satuan = trim((string) $this->input->post('satuan', TRUE));
        $jumlah_produksi = $this->_parse_jumlah_angka($this->input->post('jumlah_produksi', TRUE));
        $harga_satuan = $this->_parse_jumlah_angka($this->input->post('harga_satuan', TRUE));

        if ($tgl_input === '') {
            $kosong[] = 'Tanggal produksi';
        }
        if ($uuid_unit === '') {
            $kosong[] = 'Unit';
        }
        if ($nama_barang_input === '') {
            $kosong[] = 'Nama produk';
        }
        if ($satuan === '') {
            $kosong[] = 'Satuan';
        }
        if ($jumlah_produksi <= 0) {
            $kosong[] = 'Jumlah produksi';
        }
        if ($harga_satuan <= 0) {
            $kosong[] = 'Harga satuan';
        }

        if (!empty($kosong)) {
            echo json_encode(array(
                'ok' => false,
                'message' => 'Data belum lengkap. Harap isi: ' . implode(', ', $kosong) . '.',
                'fields' => $kosong,
            ));
            return;
        }

        $data_unit = $this->Sys_unit_model->get_by_uuid_unit($uuid_unit);
        if (!$data_unit) {
            echo json_encode(array(
                'ok' => false,
                'message' => 'Unit tidak ditemukan. Pilih unit yang valid.',
            ));
            return;
        }

        $date_tgl_produksi = $this->_tanggal_produksi_dari_input($tgl_input);
        if (!$date_tgl_produksi) {
            echo json_encode(array(
                'ok' => false,
                'message' => 'Format tanggal produksi tidak valid.',
            ));
            return;
        }

        $tanggal_beli = $this->_tanggal_beli_bulan_dari_transaksi($tgl_input);
        $row_barang = $this->_resolve_uuid_barang_produksi($nama_barang_input, $satuan);
        $spop_produksi = $this->_generate_spop_produksi($date_tgl_produksi, $row_barang->nama_barang);
        $jumlah_nominal = $harga_satuan * $jumlah_produksi;

        $data_persediaan = array(
            'tanggal' => $date_tgl_produksi,
            'uuid_barang' => $row_barang->uuid_barang,
            'namabarang' => $row_barang->nama_barang,
            'kode_barang' => $row_barang->kode_barang,
            'kode' => $row_barang->kode_barang,
            'tanggal_beli' => $tanggal_beli,
            'satuan' => $satuan,
            'hpp' => (string) $harga_satuan,
            'sa' => (string) $jumlah_produksi,
            'beli' => '0',
            'spop' => $spop_produksi,
            'total_10' => (string) $jumlah_produksi,
            'nilai_persediaan' => (string) $jumlah_nominal,
        );
        if ($this->db->field_exists('tuj', 'persediaan')) {
            $data_persediaan['tuj'] = (string) $jumlah_produksi;
        }

        $id_persediaan_barang = $this->Persediaan_model->insert_produk_baru($data_persediaan);
        $row_persediaan = $this->Persediaan_model->get_by_id($id_persediaan_barang);

        $data_produk = array(
            'uuid_persediaan' => $row_persediaan ? $row_persediaan->uuid_persediaan : '',
            'uuid_unit' => $data_unit->uuid_unit,
            'kode_unit' => $data_unit->kode_unit,
            'nama_unit' => $data_unit->nama_unit,
            'tgl_transaksi' => $date_tgl_produksi,
            'uuid_produk' => $row_barang->uuid_barang,
            'kode_barang' => $row_barang->kode_barang,
            'nama_barang' => $row_barang->nama_barang,
            'jumlah_produksi' => (string) $jumlah_produksi,
            'satuan' => $satuan,
            'harga_satuan' => (string) $harga_satuan,
            'keterangan' => $this->input->post('keterangan', TRUE),
        );
        $this->Sys_unit_produk_model->insert($data_produk);

        echo json_encode(array(
            'ok' => true,
            'message' => 'Data produksi tanpa bahan berhasil tersimpan.',
        ));
    }

    public function action_simpan_nama_produk_baru($Get_id_persediaan_barang = null)
    {
        $id_persediaan_barang = $this->_resolve_id_persediaan_produk($Get_id_persediaan_barang);
        if ($id_persediaan_barang <= 0) {
            $this->session->set_flashdata('message', 'ID persediaan produk tidak ditemukan.');
            redirect(site_url('Sys_unit_produk'));
            return;
        }

        $bulan_kembali = $this->_resolve_bulan_dari_input_produksi(
            $this->input->post('tgl_transaksi', TRUE),
            $this->input->post('bulan_produksi', TRUE)
        );
        $url_form_kembali = site_url('Sys_unit_produk/create_produksi/' . $id_persediaan_barang . '?bulan=' . urlencode($bulan_kembali));

        $nama_barang_input = trim((string) $this->input->post('nama_barang', TRUE));
        if ($nama_barang_input === '') {
            $this->session->set_flashdata('message', 'Nama produk wajib diisi sebelum menyimpan.');
            redirect($url_form_kembali);
            return;
        }

        $tgl_input = trim((string) $this->input->post('tgl_transaksi', TRUE));
        if ($tgl_input === '') {
            $this->session->set_flashdata('message', 'Tanggal produksi wajib dipilih sebelum menyimpan.');
            redirect($url_form_kembali);
            return;
        }

        $row_persediaan_cek = $this->Persediaan_model->get_by_id($id_persediaan_barang);
        $bahan_list = $row_persediaan_cek
            ? $this->Sys_unit_produk_bahan_model->get_by_uuid_persediaan($row_persediaan_cek->uuid_persediaan)
            : array();
        if (count($bahan_list) < 1) {
            $this->session->set_flashdata('message', 'Tambahkan minimal 1 bahan melalui tombol Input Bahan sebelum menyimpan produk.');
            redirect($url_form_kembali);
            return;
        }

        $date_tgl_produksi = $this->_tanggal_produksi_dari_input($tgl_input);
        $tanggal_beli = $this->_tanggal_beli_bulan_dari_transaksi($tgl_input);

        $row_barang = $this->_resolve_uuid_barang_produksi(
            $nama_barang_input,
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


        $SPOP_Produksi = $this->_generate_spop_produksi($date_tgl_produksi, $Get_nama_barang);

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
        $this->_hapus_draft_produk_produksi($id_persediaan_barang);
        $bulan = $this->_resolve_bulan_dari_input_produksi(
            $this->input->post('tgl_transaksi', TRUE),
            $this->input->post('bulan_produksi', TRUE)
        );
        redirect(site_url('Sys_unit_produk?bulan=' . urlencode($bulan)));
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

        $existing_produk = $this->Sys_unit_produk_model->get_by_uuid_persediaan($row_persediaan->uuid_persediaan);
        $tgl_asli = $existing_produk ? $existing_produk->tgl_transaksi : $row_persediaan->tanggal;
        $tgl_input = $this->input->post('tgl_transaksi', TRUE);
        if (!$this->_validasi_bulan_tgl_produksi_sama($tgl_asli, $tgl_input)) {
            $bulan_asli = $this->_bulan_nama_indonesia(date('Y-m', $this->_parse_tanggal_transaksi_ts($tgl_asli)));
            $this->session->set_flashdata(
                'message',
                'Tanggal produksi tidak boleh diubah ke bulan/tahun berbeda (' . $bulan_asli . '). Perubahan ini dapat menyebabkan kesalahan data persediaan.'
            );
            $bulan_kembali = $this->_resolve_bulan_dari_input_produksi($tgl_asli, $this->input->post('bulan_produksi', TRUE));
            redirect(site_url('Sys_unit_produk/update_produksi/' . $id_persediaan_barang . '?bulan=' . urlencode($bulan_kembali)));
            return;
        }

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
        $this->_hapus_draft_produk_produksi($id_persediaan_barang);
        $bulan = $this->_resolve_bulan_dari_input_produksi(
            $this->input->post('tgl_transaksi', TRUE),
            $this->input->post('bulan_produksi', TRUE)
        );
        redirect(site_url('Sys_unit_produk?bulan=' . urlencode($bulan)));
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

    public function ajax_cek_produksi_hapus()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }
        header('Content-Type: application/json; charset=utf-8');

        $id = (int) $this->input->post('id', TRUE);
        $uuid_persediaan = trim((string) $this->input->post('uuid_persediaan', TRUE));

        if ($uuid_persediaan === '' && $id > 0) {
            $row_by_id = $this->Sys_unit_produk_model->get_by_id($id);
            if ($row_by_id && !empty($row_by_id->uuid_persediaan)) {
                $uuid_persediaan = trim((string) $row_by_id->uuid_persediaan);
            }
        }

        if ($uuid_persediaan === '') {
            echo json_encode(array(
                'ok' => false,
                'message' => 'uuid_persediaan tidak valid / kosong.',
            ));
            return;
        }

        $row_produk = null;
        if ($id > 0) {
            $row_produk = $this->Sys_unit_produk_model->get_by_id($id);
            if ($row_produk && trim((string) $row_produk->uuid_persediaan) !== $uuid_persediaan) {
                echo json_encode(array(
                    'ok' => false,
                    'message' => 'uuid_persediaan tidak cocok dengan record produksi yang dipilih.',
                ));
                return;
            }
        }

        if (!$row_produk) {
            $row_produk = $this->Sys_unit_produk_model->get_by_uuid_persediaan($uuid_persediaan);
        }

        if (!$row_produk) {
            echo json_encode(array(
                'ok' => false,
                'message' => 'Record sys_unit_produk dengan uuid_persediaan tersebut tidak ditemukan.',
                'uuid_persediaan' => $uuid_persediaan,
            ));
            return;
        }

        $cek_penjualan = $this->_cek_penjualan_setelah_produksi($row_produk);

        $bulan_produk_ym = '';
        $ts_produk = strtotime(isset($row_produk->tgl_transaksi) ? $row_produk->tgl_transaksi : '');
        if ($ts_produk !== false) {
            $bulan_produk_ym = date('Y-m', $ts_produk);
        }

        $row_persediaan = $this->_get_persediaan_produk_bulan_produksi($row_produk);
        $spop = ($row_persediaan && isset($row_persediaan->spop)) ? (string) $row_persediaan->spop : '';

        $persediaan_list = array();
        if ($row_persediaan) {
            $total_10 = isset($row_persediaan->total_10) ? (float) $row_persediaan->total_10 : 0;
            $penjualan_field = isset($row_persediaan->penjualan) ? (float) $row_persediaan->penjualan : 0;
            $bahan_produksi_field = isset($row_persediaan->bahan_produksi) ? (float) $row_persediaan->bahan_produksi : 0;
            $pecah_satuan_field = isset($row_persediaan->pecah_satuan) ? (float) $row_persediaan->pecah_satuan : 0;
            $sa_field = isset($row_persediaan->sa) ? (float) $row_persediaan->sa : 0;
            // Sisa stock = nilai stok aktual di persediaan (total_10), jangan dikurangi lagi SUM penjualan
            $sisa_stock = $total_10;
            $jumlah_produksi = isset($row_produk->jumlah_produksi) ? (float) $row_produk->jumlah_produksi : 0;
            $satuan_p = isset($row_persediaan->satuan) ? (string) $row_persediaan->satuan : '';
            $tgl_beli_tampil = '-';
            if (!empty($row_persediaan->tanggal_beli)) {
                $ts_beli = strtotime($row_persediaan->tanggal_beli);
                if ($ts_beli) {
                    $tgl_beli_tampil = date('d-M-Y', $ts_beli);
                }
            }
            $persediaan_list[] = array(
                'no' => 1,
                'id' => isset($row_persediaan->id) ? (int) $row_persediaan->id : 0,
                'tanggal_beli' => $tgl_beli_tampil,
                'namabarang' => isset($row_persediaan->namabarang) ? (string) $row_persediaan->namabarang : '',
                'spop' => isset($row_persediaan->spop) ? (string) $row_persediaan->spop : '',
                'satuan' => $satuan_p,
                'jumlah_produksi' => number_format($jumlah_produksi, 0, ',', '.'),
                'sa' => number_format($sa_field, 0, ',', '.'),
                'total_10' => number_format($total_10, 0, ',', '.'),
                'penjualan' => number_format($penjualan_field, 0, ',', '.'),
                'bahan_produksi' => number_format($bahan_produksi_field, 0, ',', '.'),
                'pecah_satuan' => number_format($pecah_satuan_field, 0, ',', '.'),
                'sisa_stock' => number_format($sisa_stock, 0, ',', '.'),
            );
        }

        $list_bahan = $this->Sys_unit_produk_bahan_model->get_by_uuid_persediaan($uuid_persediaan);
        $bahan_list = array();
        $no_bahan = 0;
        foreach ($list_bahan as $bahan) {
            $harga = isset($bahan->harga_satuan_bahan) ? (float) $bahan->harga_satuan_bahan : 0;
            $jumlah = isset($bahan->jumlah_bahan) ? (float) $bahan->jumlah_bahan : 0;
            $bahan_list[] = array(
                'no' => ++$no_bahan,
                'nama_barang_bahan' => isset($bahan->nama_barang_bahan) ? (string) $bahan->nama_barang_bahan : '',
                'jumlah_bahan' => isset($bahan->jumlah_bahan) ? $bahan->jumlah_bahan : '',
                'satuan_bahan' => isset($bahan->satuan_bahan) ? (string) $bahan->satuan_bahan : '',
                'harga_satuan_bahan' => number_format($harga, 2, ',', '.'),
                'harga_total_bahan' => number_format($jumlah * $harga, 2, ',', '.'),
                'tgl_transaksi' => isset($bahan->tgl_transaksi) ? (string) $bahan->tgl_transaksi : '',
                'uuid_persediaan_bahan' => isset($bahan->uuid_persediaan_bahan) ? (string) $bahan->uuid_persediaan_bahan : '',
            );
        }

        echo json_encode(array(
            'ok' => true,
            'message' => !empty($cek_penjualan['sudah_terjual'])
                ? 'Produk sudah terjual — tidak bisa dihapus.'
                : 'Record sys_unit_produk ditemukan.',
            'uuid_persediaan' => $uuid_persediaan,
            'produk_id' => (int) $row_produk->id,
            'record' => $this->_row_to_detail_array($row_produk),
            'bahan_list' => $bahan_list,
            'persediaan_list' => $persediaan_list,
            'can_delete' => empty($cek_penjualan['sudah_terjual']),
            'sudah_terjual' => !empty($cek_penjualan['sudah_terjual']),
            'penjualan_list' => isset($cek_penjualan['penjualan_list']) ? $cek_penjualan['penjualan_list'] : array(),
            'produk_info' => array(
                'nama_barang' => isset($row_produk->nama_barang) ? (string) $row_produk->nama_barang : '',
                'spop' => $spop,
                'tgl_transaksi' => isset($row_produk->tgl_transaksi) ? (string) $row_produk->tgl_transaksi : '',
            ),
        ));
    }

    public function ajax_list_penjualan_sold_out()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }
        header('Content-Type: application/json; charset=utf-8');

        $produk_id = (int) $this->input->get_post('produk_id', TRUE);
        $uuid_persediaan = trim((string) $this->input->get_post('uuid_persediaan', TRUE));

        $row_produk = null;
        if ($produk_id > 0) {
            $row_produk = $this->Sys_unit_produk_model->get_by_id($produk_id);
        }
        if (!$row_produk && $uuid_persediaan !== '') {
            $row_produk = $this->Sys_unit_produk_model->get_by_uuid_persediaan($uuid_persediaan);
        }
        if (!$row_produk) {
            echo json_encode(array(
                'ok' => false,
                'message' => 'Data produksi tidak ditemukan.',
                'rows' => array(),
            ));
            return;
        }

        if ($uuid_persediaan === '') {
            $uuid_persediaan = trim((string) $row_produk->uuid_persediaan);
        }

        $cek = $this->_cek_penjualan_setelah_produksi($row_produk);
        $rows = array();
        $no = 0;
        foreach ($cek['penjualan_list'] as $item) {
            $tgl_jual = isset($item['tgl_jual']) ? $item['tgl_jual'] : '';
            $tgl_tampil = $tgl_jual ? date('d-M-Y', strtotime($tgl_jual)) : '-';
            $jumlah = isset($item['jumlah']) ? $item['jumlah'] : '';
            $satuan = isset($item['satuan']) ? $item['satuan'] : '';
            $rows[] = array(
                'no' => ++$no,
                'tgl_jual' => $tgl_tampil,
                'unit' => isset($item['unit']) ? $item['unit'] : '',
                'konsumen' => isset($item['konsumen_nama']) ? $item['konsumen_nama'] : '',
                'jumlah' => trim($jumlah . ($satuan !== '' ? ' ' . $satuan : '')),
                'nama_barang' => isset($item['nama_barang']) ? $item['nama_barang'] : '',
                'nmrkirim' => isset($item['nmrkirim']) ? $item['nmrkirim'] : '',
                'nmrpesan' => isset($item['nmrpesan']) ? $item['nmrpesan'] : '',
            );
        }

        echo json_encode(array(
            'ok' => true,
            'uuid_persediaan' => $uuid_persediaan,
            'nama_barang' => isset($row_produk->nama_barang) ? (string) $row_produk->nama_barang : '',
            'rows' => $rows,
        ));
    }

    /**
     * Cek apakah uuid_persediaan produk sudah ada di tbl_penjualan
     * mulai bulan produksi sampai bulan saat ini, dengan tgl_jual >= tanggal produksi.
     */
    private function _cek_penjualan_setelah_produksi($row_produk)
    {
        $uuid_persediaan = trim((string) $row_produk->uuid_persediaan);
        if ($uuid_persediaan === '') {
            return array(
                'sudah_terjual' => false,
                'penjualan_list' => array(),
            );
        }

        $range = $this->_rentang_tanggal_cek_penjualan_produk($row_produk);
        $this->db->select('id, uuid_penjualan, uuid_persediaan, tgl_jual, nmrpesan, nmrkirim, konsumen_nama, kode_barang, nama_barang, satuan, jumlah, harga_satuan, total_nominal, unit');
        $this->db->from('tbl_penjualan');
        $this->db->where('uuid_persediaan', $uuid_persediaan);
        $this->db->where('tgl_jual >=', $range['awal']);
        $this->db->where('tgl_jual <=', $range['akhir']);
        $this->db->order_by('tgl_jual', 'ASC');
        $this->db->order_by('id', 'ASC');
        $rows = $this->db->get()->result();

        $penjualan_list = array();
        foreach ($rows as $row) {
            $penjualan_list[] = $this->_row_to_detail_array($row);
        }

        return array(
            'sudah_terjual' => count($penjualan_list) > 0,
            'penjualan_list' => $penjualan_list,
        );
    }

    /**
     * Cek cepat (EXISTS) apakah produk sudah terjual — untuk list badge / tombol hapus.
     */
    public function is_produk_sudah_terjual($row_produk)
    {
        $uuid_persediaan = isset($row_produk->uuid_persediaan) ? trim((string) $row_produk->uuid_persediaan) : '';
        if ($uuid_persediaan === '') {
            return false;
        }

        $range = $this->_rentang_tanggal_cek_penjualan_produk($row_produk);
        $this->db->select('id');
        $this->db->from('tbl_penjualan');
        $this->db->where('uuid_persediaan', $uuid_persediaan);
        $this->db->where('tgl_jual >=', $range['awal']);
        $this->db->where('tgl_jual <=', $range['akhir']);
        $this->db->limit(1);
        $row = $this->db->get()->row();
        if ($row) {
            return true;
        }

        // Cadangan: kolom penjualan di persediaan bulan produksi sudah terisi
        $ts_produk = strtotime(isset($row_produk->tgl_transaksi) ? $row_produk->tgl_transaksi : '');
        if ($ts_produk !== false) {
            $bulan_ym = date('Y-m', $ts_produk);
            $this->db->select('id, penjualan');
            $this->db->from('persediaan');
            $this->db->where('uuid_persediaan', $uuid_persediaan);
            $this->db->where("DATE_FORMAT(tanggal_beli, '%Y-%m') =", $bulan_ym);
            $this->db->limit(1);
            $row_p = $this->db->get()->row();
            if ($row_p && (float) $row_p->penjualan > 0) {
                return true;
            }
        }

        return false;
    }

    private function _rentang_tanggal_cek_penjualan_produk($row_produk)
    {
        $ts_produk = strtotime(isset($row_produk->tgl_transaksi) ? $row_produk->tgl_transaksi : '');
        if ($ts_produk === false) {
            $ts_produk = time();
        }

        // Mulai dari awal bulan produksi (bukan tanggal produksi),
        // agar penjualan di bulan yang sama tetap terdeteksi sebagai "sudah terjual".
        return array(
            'awal' => date('Y-m-01 00:00:00', $ts_produk),
            'akhir' => date('Y-m-d 23:59:59'),
        );
    }

    public function ajax_delete_produksi()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }
        header('Content-Type: application/json; charset=utf-8');

        $id = (int) $this->input->post('id', TRUE);
        if ($id <= 0) {
            echo json_encode(array(
                'ok' => false,
                'message' => 'ID produksi tidak valid.',
            ));
            return;
        }

        echo json_encode($this->_proses_hapus_produksi($id));
    }

    public function delete($id)
    {
        $row = $this->Sys_unit_produk_model->get_by_id($id);

        if ($row) {
            $result = $this->_proses_hapus_produksi($id);
            $this->session->set_flashdata(
                'message',
                !empty($result['ok']) ? 'Delete Record Success' : (isset($result['message']) ? $result['message'] : 'Gagal menghapus data produksi.')
            );
            redirect(site_url('sys_unit_produk'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_unit_produk'));
        }
    }

    private function _proses_hapus_produksi($id)
    {
        $row_produk = $this->Sys_unit_produk_model->get_by_id($id);
        if (!$row_produk) {
            return array(
                'ok' => false,
                'message' => 'Data produksi tidak ditemukan.',
            );
        }

        $cek_penjualan = $this->_cek_penjualan_setelah_produksi($row_produk);
        if (!empty($cek_penjualan['sudah_terjual'])) {
            $row_persediaan = $this->_get_persediaan_produk_bulan_produksi($row_produk);
            return array(
                'ok' => false,
                'message' => 'Tidak bisa dihapus, karena sudah dilakukan penjualan.',
                'sudah_terjual' => true,
                'penjualan_list' => $cek_penjualan['penjualan_list'],
                'produk_info' => array(
                    'nama_barang' => isset($row_produk->nama_barang) ? (string) $row_produk->nama_barang : '',
                    'spop' => ($row_persediaan && isset($row_persediaan->spop)) ? (string) $row_persediaan->spop : '',
                ),
            );
        }

        $uuid_persediaan_produk = trim((string) $row_produk->uuid_persediaan);
        $nama_produk = trim((string) $row_produk->nama_barang);
        $list_bahan = ($uuid_persediaan_produk !== '')
            ? $this->Sys_unit_produk_bahan_model->get_by_uuid_persediaan($uuid_persediaan_produk)
            : array();

        // Validasi dulu: record produk di persediaan (bulan produksi) harus ada & nama/spop cocok
        $row_persediaan_produk = $this->_get_persediaan_produk_bulan_produksi($row_produk);
        if (!$row_persediaan_produk) {
            return array(
                'ok' => false,
                'message' => 'Produk di persediaan tidak ada.',
            );
        }

        $nama_persediaan = trim((string) (isset($row_persediaan_produk->namabarang) ? $row_persediaan_produk->namabarang : ''));
        $spop_persediaan = trim((string) (isset($row_persediaan_produk->spop) ? $row_persediaan_produk->spop : ''));
        if ($nama_persediaan === '' || $spop_persediaan === ''
            || strcasecmp($nama_persediaan, $nama_produk) !== 0) {
            return array(
                'ok' => false,
                'message' => 'Produk di persediaan tidak ada. Nama barang / SPOP tidak cocok dengan data produksi.',
            );
        }

        $detail = array(
            'persediaan_bahan_dikembalikan' => array(),
            'persediaan_bahan_dilewati' => array(),
            'persediaan_bahan_bulan_berikutnya_diupdate' => array(),
            'sys_unit_produk_bahan_dihapus' => array(),
            'persediaan_produk_dihapus' => null,
            'persediaan_produk_bulan_berikutnya_dihapus' => array(),
            'sys_unit_produk_dihapus' => $this->_row_to_detail_array($row_produk),
        );

        foreach ($list_bahan as $bahan) {
            $detail['sys_unit_produk_bahan_dihapus'][] = $this->_row_to_detail_array($bahan);
        }

        $bulan_produksi_ym = '';
        $ts_produk = strtotime(isset($row_produk->tgl_transaksi) ? $row_produk->tgl_transaksi : '');
        if ($ts_produk !== false) {
            $bulan_produksi_ym = date('Y-m', $ts_produk);
        }

        $this->db->trans_start();

        // 1) Loop bahan pembentuk produk dari sys_unit_produk_bahan
        //    (filter: uuid_persediaan = uuid_persediaan record tombol hapus / sys_unit_produk).
        //    Untuk tiap bahan: update persediaan WHERE uuid_persediaan = uuid_persediaan_bahan
        //    (bulan produksi + bulan setelahnya):
        //      bahan_produksi = bahan_produksi - jumlah_bahan
        //      total_10       = total_10 + jumlah_bahan
        foreach ($list_bahan as $bahan) {
            $jumlah_bahan = $this->_parse_jumlah_angka(isset($bahan->jumlah_bahan) ? $bahan->jumlah_bahan : 0);
            $uuid_bahan = trim((string) (isset($bahan->uuid_persediaan_bahan) ? $bahan->uuid_persediaan_bahan : ''));

            if ($uuid_bahan === '' || $jumlah_bahan <= 0) {
                $detail['persediaan_bahan_dilewati'][] = array(
                    'bahan_record' => $this->_row_to_detail_array($bahan),
                    'keterangan' => 'uuid_persediaan_bahan kosong atau jumlah_bahan tidak valid — dilewati.',
                );
                continue;
            }

            $hasil_update = $this->_update_persediaan_bahan_by_uuid_persediaan_bahan(
                $uuid_bahan,
                $jumlah_bahan,
                $bulan_produksi_ym
            );

            if (empty($hasil_update)) {
                $detail['persediaan_bahan_dilewati'][] = array(
                    'bahan_record' => $this->_row_to_detail_array($bahan),
                    'keterangan' => 'Tidak ada record persediaan dengan uuid_persediaan = uuid_persediaan_bahan untuk bulan produksi / setelahnya.',
                );
                continue;
            }

            foreach ($hasil_update as $item_update) {
                $bulan_row = isset($item_update['bulan_ym']) ? $item_update['bulan_ym'] : '';
                $payload = array(
                    'bahan_record' => $this->_row_to_detail_array($bahan),
                    'uuid_persediaan_bahan' => $uuid_bahan,
                    'jumlah_bahan' => $jumlah_bahan,
                    'persediaan_sebelum' => $item_update['sebelum'],
                    'persediaan_sesudah' => $item_update['sesudah'],
                );

                if ($bulan_produksi_ym !== '' && $bulan_row !== '' && $bulan_row > $bulan_produksi_ym) {
                    $detail['persediaan_bahan_bulan_berikutnya_diupdate'][] = $payload;
                } else {
                    $detail['persediaan_bahan_dikembalikan'][] = $payload;
                }
            }
        }

        // 2) Hapus semua record bahan produk
        if ($uuid_persediaan_produk !== '') {
            $this->db->where('uuid_persediaan', $uuid_persediaan_produk);
            $this->db->delete('sys_unit_produk_bahan');
        }

        // 3) Hapus record produk di persediaan bulan produksi
        $detail['persediaan_produk_dihapus'] = $this->_row_to_detail_array($row_persediaan_produk);
        $this->Persediaan_model->delete((int) $row_persediaan_produk->id);

        // 3b) Hapus juga record produk di persediaan untuk bulan-bulan SETELAH bulan produksi
        //     agar tidak tersisa di stock bulan berikutnya (uuid_persediaan lintas bulan).
        if ($uuid_persediaan_produk !== '' && $bulan_produksi_ym !== '') {
            $this->db->where('uuid_persediaan', $uuid_persediaan_produk);
            $this->db->where("DATE_FORMAT(tanggal_beli, '%Y-%m') >", $bulan_produksi_ym);
            $this->db->order_by('tanggal_beli', 'ASC');
            $this->db->order_by('id', 'ASC');
            $rows_bulan_berikutnya = $this->db->get('persediaan')->result();

            foreach ($rows_bulan_berikutnya as $row_next) {
                $nama_next = trim((string) (isset($row_next->namabarang) ? $row_next->namabarang : ''));
                $spop_next = trim((string) (isset($row_next->spop) ? $row_next->spop : ''));

                // Hanya hapus jika nama/spop masih cocok dengan produk yang dihapus
                if ($nama_next !== '' && $spop_next !== ''
                    && strcasecmp($nama_next, $nama_produk) === 0
                    && strcasecmp($spop_next, $spop_persediaan) === 0) {
                    $detail['persediaan_produk_bulan_berikutnya_dihapus'][] = $this->_row_to_detail_array($row_next);
                    $this->Persediaan_model->delete((int) $row_next->id);
                }
            }
        }

        // 4) Hapus record produksi
        $this->Sys_unit_produk_model->delete($id);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return array(
                'ok' => false,
                'message' => 'Gagal menghapus data produksi. Transaksi dibatalkan.',
            );
        }

        $jumlah_bulan_lanjut = count($detail['persediaan_produk_bulan_berikutnya_dihapus']);
        $jumlah_bahan_sync = count($detail['persediaan_bahan_bulan_berikutnya_diupdate']);
        $pesan_lanjut = '';
        if ($jumlah_bulan_lanjut > 0) {
            $pesan_lanjut .= ' Juga dihapus ' . $jumlah_bulan_lanjut . ' record produk di persediaan bulan setelahnya.';
        }
        if ($jumlah_bahan_sync > 0) {
            $pesan_lanjut .= ' Stock bahan di ' . $jumlah_bahan_sync . ' record bulan setelahnya ikut di-update (total_10 & bahan_produksi).';
        }

        return array(
            'ok' => true,
            'message' => 'Proses hapus produksi berhasil. Bahan dikembalikan ke stock dan produk dihapus dari persediaan.' . $pesan_lanjut,
            'detail' => $detail,
        );
    }

    /**
     * Ambil record persediaan produk sesuai bulan produksi (uuid_persediaan bisa lintas bulan).
     */
    private function _get_persediaan_produk_bulan_produksi($row_produk)
    {
        if (!$row_produk) {
            return null;
        }
        $uuid_persediaan = trim((string) $row_produk->uuid_persediaan);
        if ($uuid_persediaan === '') {
            return null;
        }

        $bulan_ym = '';
        $ts = strtotime(isset($row_produk->tgl_transaksi) ? $row_produk->tgl_transaksi : '');
        if ($ts !== false) {
            $bulan_ym = date('Y-m', $ts);
        }

        if ($bulan_ym !== '') {
            $this->db->where('uuid_persediaan', $uuid_persediaan);
            $this->db->where("DATE_FORMAT(tanggal_beli, '%Y-%m') =", $bulan_ym);
            $this->db->order_by('id', 'ASC');
            $this->db->limit(1);
            $row = $this->db->get('persediaan')->row();
            if ($row) {
                return $row;
            }
        }

        $this->db->where('uuid_persediaan', $uuid_persediaan);
        $this->db->order_by('id', 'ASC');
        $this->db->limit(1);
        return $this->db->get('persediaan')->row();
    }

    /**
     * Update stock bahan di tabel persediaan berdasarkan uuid_persediaan_bahan
     * (dari sys_unit_produk_bahan milik produk yang dihapus).
     *
     * Filter: persediaan.uuid_persediaan = uuid_persediaan_bahan,
     *         dan tanggal_beli bulan >= bulan produksi (bulan produksi + setelahnya).
     * Update: bahan_produksi -= jumlah_bahan, total_10 += jumlah_bahan.
     */
    private function _update_persediaan_bahan_by_uuid_persediaan_bahan($uuid_persediaan_bahan, $jumlah_bahan_kembalikan, $bulan_produksi_ym = '')
    {
        $hasil = array();
        $uuid_persediaan_bahan = trim((string) $uuid_persediaan_bahan);
        $bulan_produksi_ym = trim((string) $bulan_produksi_ym);
        $jumlah_bahan_kembalikan = $this->_parse_jumlah_angka($jumlah_bahan_kembalikan);

        if ($uuid_persediaan_bahan === '' || $jumlah_bahan_kembalikan <= 0) {
            return $hasil;
        }
        if (!$this->db->field_exists('bahan_produksi', 'persediaan')) {
            return $hasil;
        }

        $this->db->where('uuid_persediaan', $uuid_persediaan_bahan);
        if ($bulan_produksi_ym !== '') {
            $this->db->where("DATE_FORMAT(tanggal_beli, '%Y-%m') >=", $bulan_produksi_ym);
        }
        $this->db->order_by('tanggal_beli', 'ASC');
        $this->db->order_by('id', 'ASC');
        $rows = $this->db->get('persediaan')->result();

        foreach ($rows as $row) {
            $sebelum = $this->_row_to_detail_array($row);

            $bahan_lama = $this->_parse_jumlah_angka(isset($row->bahan_produksi) ? $row->bahan_produksi : 0);
            $total_10_lama = $this->_parse_jumlah_angka(isset($row->total_10) ? $row->total_10 : 0);
            $hpp = $this->_parse_jumlah_angka(isset($row->hpp) ? $row->hpp : 0);

            $bahan_baru = max(0, $bahan_lama - $jumlah_bahan_kembalikan);
            $total_10_baru = $total_10_lama + $jumlah_bahan_kembalikan;
            $nilai_persediaan = (int) floor($total_10_baru * $hpp);

            $update = array(
                'bahan_produksi' => (string) $bahan_baru,
                'total_10' => (string) $total_10_baru,
                'nilai_persediaan' => (string) $nilai_persediaan,
            );
            if ($this->db->field_exists('tuj', 'persediaan')) {
                $update['tuj'] = (string) $total_10_baru;
            }

            $this->Persediaan_model->update((int) $row->id, $update);
            $row_sesudah = $this->Persediaan_model->get_by_id((int) $row->id);

            $bulan_ym = '';
            if (!empty($row->tanggal_beli)) {
                $ts = strtotime($row->tanggal_beli);
                if ($ts !== false) {
                    $bulan_ym = date('Y-m', $ts);
                }
            }

            $hasil[] = array(
                'bulan_ym' => $bulan_ym,
                'sebelum' => $sebelum,
                'sesudah' => $this->_row_to_detail_array($row_sesudah),
            );
        }

        return $hasil;
    }

    private function _row_to_detail_array($row)
    {
        if (!$row) {
            return array();
        }

        return json_decode(json_encode($row), true);
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

    private function _prefix_nama_barang_spop($nama_barang)
    {
        $nama_barang = trim((string) $nama_barang);
        if ($nama_barang === '') {
            return 'BARAN';
        }
        if (function_exists('mb_substr')) {
            $prefix = mb_substr($nama_barang, 0, 5, 'UTF-8');
        } else {
            $prefix = substr($nama_barang, 0, 5);
        }

        return preg_replace('/\s+/', '', $prefix);
    }

    private function _generate_spop_produksi($date_tgl_produksi, $nama_barang = '')
    {
        $nama_kode = $this->_prefix_nama_barang_spop($nama_barang);
        $kode_waktu = date('YmdHi', strtotime($date_tgl_produksi));
        $spop_produksi = 'PRODUK_' . $nama_kode . '_' . $kode_waktu;

        $this->db->where('spop', $spop_produksi);
        $jumlah_spop = $this->db->count_all_results('persediaan');
        if ($jumlah_spop > 0) {
            $counter = 1;
            do {
                $spop_candidate = 'PRODUK_' . $nama_kode . '_' . $kode_waktu . '_' . $counter;
                $this->db->where('spop', $spop_candidate);
                $exists = $this->db->count_all_results('persediaan') > 0;
                $counter++;
            } while ($exists);
            $spop_produksi = $spop_candidate;
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
                    AND CAST(COALESCE(NULLIF(TRIM(persediaan.total_10), ''), '0') AS UNSIGNED) > 0
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

    private function _produksi_draft_session_key($id_persediaan_barang)
    {
        return 'produksi_draft_' . (int) $id_persediaan_barang;
    }

    private function _hapus_draft_produk_produksi($id_persediaan_barang)
    {
        $id = (int) $id_persediaan_barang;
        if ($id <= 0) {
            return;
        }
        $this->session->unset_userdata($this->_produksi_draft_session_key($id));
    }

    private function _validasi_bulan_tgl_produksi_sama($tgl_asli, $tgl_baru)
    {
        $ts_asli = $this->_parse_tanggal_transaksi_ts($tgl_asli);
        $ts_baru = $this->_parse_tanggal_transaksi_ts($tgl_baru);
        if (!$ts_asli || !$ts_baru) {
            return true;
        }

        return date('Y-m', $ts_asli) === date('Y-m', $ts_baru);
    }

    private function _resolve_bulan_dari_input_produksi($tgl_transaksi = null, $bulan_fallback = null)
    {
        $tgl_transaksi = trim((string) $tgl_transaksi);
        if ($tgl_transaksi !== '') {
            $ts = $this->_parse_tanggal_transaksi_ts($tgl_transaksi);
            if ($ts) {
                return $this->_resolve_bulan_produksi_selected(date('Y-m', $ts));
            }
        }

        return $this->_resolve_bulan_produksi_selected($bulan_fallback);
    }

    private function _ambil_post_draft_produk_field($draft_key, $fallback_key = '')
    {
        $value = trim((string) $this->input->post($draft_key, TRUE));
        if ($value === '' && $fallback_key !== '') {
            $value = trim((string) $this->input->post($fallback_key, TRUE));
        }

        return $value;
    }

    private function _simpan_draft_produk_produksi_dari_post($id_persediaan_barang)
    {
        $id = (int) $id_persediaan_barang;
        if ($id <= 0) {
            return;
        }

        $draft = array(
            'nama_barang' => $this->_ambil_post_draft_produk_field('draft_nama_barang', 'nama_barang'),
            'satuan' => $this->_ambil_post_draft_produk_field('draft_satuan', 'satuan'),
            'harga_satuan' => $this->_ambil_post_draft_produk_field('draft_harga_satuan', 'harga_satuan'),
            'tgl_transaksi' => $this->_ambil_post_draft_produk_field('draft_tgl_transaksi', 'tgl_transaksi'),
            'uuid_unit' => $this->_ambil_post_draft_produk_field('draft_uuid_unit', 'uuid_unit'),
            'jumlah_produksi' => $this->_ambil_post_draft_produk_field('draft_jumlah_produksi', 'jumlah_produksi'),
            'keterangan' => $this->_ambil_post_draft_produk_field('draft_keterangan', 'keterangan'),
        );

        $has_data = false;
        foreach ($draft as $value) {
            if ($value !== '') {
                $has_data = true;
                break;
            }
        }
        if (!$has_data) {
            return;
        }

        $this->session->set_userdata($this->_produksi_draft_session_key($id), $draft);

        $update = array();
        if ($draft['nama_barang'] !== '') {
            $update['namabarang'] = $draft['nama_barang'];
        }
        if ($draft['satuan'] !== '') {
            $update['satuan'] = $draft['satuan'];
        }
        $harga_satuan = $this->_parse_jumlah_angka($draft['harga_satuan']);
        if ($harga_satuan > 0) {
            $update['hpp'] = (string) $harga_satuan;
        }
        $jumlah_produksi = $this->_parse_jumlah_angka($draft['jumlah_produksi']);
        if ($jumlah_produksi > 0) {
            $update['sa'] = (string) $jumlah_produksi;
            $update['total_10'] = (string) $jumlah_produksi;
            $update['nilai_persediaan'] = (string) ($harga_satuan * $jumlah_produksi);
        }
        if ($draft['tgl_transaksi'] !== '') {
            $update['tanggal'] = $this->_tanggal_produksi_dari_input($draft['tgl_transaksi']);
            $update['tanggal_beli'] = $this->_tanggal_beli_bulan_dari_transaksi($draft['tgl_transaksi']);
        }

        if (!empty($update)) {
            $this->Persediaan_model->update($id, $update);
        }
    }

    private function _get_produksi_draft_session($id_persediaan_barang)
    {
        $id = (int) $id_persediaan_barang;
        if ($id <= 0) {
            return array();
        }

        $draft = $this->session->userdata($this->_produksi_draft_session_key($id));
        return is_array($draft) ? $draft : array();
    }

    private function _apply_produksi_draft_ke_form_data($data)
    {
        $id = isset($data['id_persediaan_barang']) ? (int) $data['id_persediaan_barang'] : 0;
        $draft = $this->_get_produksi_draft_session($id);
        $data['produk_draft'] = $draft;
        $data['hapus_produk_draft_client'] = false;

        $flash_message = $this->session->flashdata('message');
        if ($flash_message && stripos((string) $flash_message, 'berhasil') !== false) {
            $data['hapus_produk_draft_client'] = true;
        }

        if ($id <= 0 || empty($draft)) {
            return $data;
        }

        $fields = array('nama_barang', 'satuan', 'harga_satuan', 'tgl_transaksi', 'uuid_unit', 'jumlah_produksi', 'keterangan');
        foreach ($fields as $field) {
            if (isset($draft[$field]) && trim((string) $draft[$field]) !== '') {
                $data[$field] = $draft[$field];
            }
        }

        return $data;
    }

    private function _sync_bulan_produksi_dari_tgl_form($data)
    {
        $tgl = isset($data['tgl_transaksi']) ? trim((string) $data['tgl_transaksi']) : '';
        if ($tgl === '') {
            return $data;
        }

        $ts = $this->_parse_tanggal_transaksi_ts($tgl);
        if (!$ts) {
            return $data;
        }

        $bulan_ym = date('Y-m', $ts);
        $tanggal_beli_bulan = $this->_tanggal_beli_bulan_dari_transaksi($tgl);
        $data['bulan_produksi_selected'] = $bulan_ym;
        $data['bulan_produksi_ym'] = $bulan_ym;
        $data['bulan_produksi_label'] = $this->_bulan_nama_indonesia($bulan_ym);
        $data['tanggal_beli_bulan'] = $tanggal_beli_bulan;
        $data['tgl_transaksi_bahan'] = $tanggal_beli_bulan;
        $data['Data_stock'] = $this->_get_stock_persediaan_by_bulan($bulan_ym);

        return $data;
    }

    private function _enrich_produksi_form_data($data)
    {
        if (!isset($data['data_bahan_produk_unit']) || !is_array($data['data_bahan_produk_unit'])) {
            $data['data_bahan_produk_unit'] = array();
        }
        $data['jumlah_bahan_count'] = count($data['data_bahan_produk_unit']);
        $data['produk_sudah_ada'] = false;
        $id = isset($data['id_persediaan_barang']) ? (int) $data['id_persediaan_barang'] : 0;
        $bulan_ym = isset($data['bulan_produksi_selected']) ? $data['bulan_produksi_selected'] : date('Y-m');
        $data['url_kembali_data_produk'] = site_url('Sys_unit_produk') . '?bulan=' . urlencode($bulan_ym);
        $data['url_cancel_produksi'] = $id > 0
            ? site_url('Sys_unit_produk/cancel_produksi/' . $id)
            : site_url('Sys_unit_produk/cancel_produksi');
        if ($id > 0) {
            $row_p = $this->Persediaan_model->get_by_id($id);
            if ($row_p) {
                $data['produk_sudah_ada'] = (bool) $this->Sys_unit_produk_model->get_by_uuid_persediaan($row_p->uuid_persediaan);
                $data['uuid_persediaan_produk'] = isset($row_p->uuid_persediaan) ? (string) $row_p->uuid_persediaan : '';
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
            $data['uuid_persediaan_produk'] = '';
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

    private function _redirect_create_produksi_dengan_bulan($id_persediaan_barang, $bulan_proses)
    {
        if ($id_persediaan_barang) {
            redirect(site_url('Sys_unit_produk/create_produksi/' . (int) $id_persediaan_barang . '?bulan=' . urlencode($bulan_proses)));
            return;
        }
        redirect(site_url('Sys_unit_produk/create_produksi?bulan=' . urlencode($bulan_proses)));
    }

    /**
     * Salin 1 record persediaan bahan (by id) ke sys_unit_produk_bahan.
     * uuid_persediaan = produk produksi; id_persediaan = id record bahan yang dipilih.
     */
    private function _build_data_sys_unit_produk_bahan_dari_persediaan($row_persediaan_bahan, $uuid_persediaan_produk, $tgl_transaksi_input, $jumlah_bahan_input)
    {
        $data = array(
            'uuid_persediaan' => $uuid_persediaan_produk,
            'tgl_transaksi' => $this->_tanggal_produksi_dari_input($tgl_transaksi_input),
            'uuid_produk' => $this->_uuid_barang_dari_persediaan($row_persediaan_bahan),
            'uuid_persediaan_bahan' => isset($row_persediaan_bahan->uuid_persediaan) ? (string) $row_persediaan_bahan->uuid_persediaan : '',
            'kode_barang_bahan' => $this->_kode_barang_dari_persediaan($row_persediaan_bahan),
            'nama_barang_bahan' => $row_persediaan_bahan->namabarang,
            'satuan_bahan' => $row_persediaan_bahan->satuan,
            'harga_satuan_bahan' => $row_persediaan_bahan->hpp,
            'jumlah_bahan' => $jumlah_bahan_input,
        );
        if ($this->db->field_exists('id_persediaan', 'sys_unit_produk_bahan')) {
            $data['id_persediaan'] = (int) $row_persediaan_bahan->id;
        }

        return $data;
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