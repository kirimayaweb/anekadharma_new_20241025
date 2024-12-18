<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sys_unit_produk extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model(array('Sys_unit_produk_model', 'Sys_unit_model', 'Sys_nama_barang_model', 'Persediaan_model', 'Sys_konsumen_model', 'Tbl_pembelian_model'));
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
        $Sys_unit_produk_data = $this->Sys_unit_produk_model->get_all();

        // print_r($Sys_unit_produk_data);
        // die;

        // $start = 0;
        $data = array(
            'action' => site_url('Sys_unit_produk/create_produksi'),
            'Sys_unit_produk_data' => $Sys_unit_produk_data,
            // 'start' => $start,
        );
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_unit_produk/adminlte310_sys_unit_produk_list', $data);
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


    public function create_produksi($id_persediaan_barang = null)
    {
        // print_r("create_produksi");
        // print_r("<br/>");
        if ($id_persediaan_barang) {
            // print_r("Sudah Ada");
            // print_r("<br/>");
            // print_r($id_persediaan_barang);

            $data_barang_selected = $this->Persediaan_model->get_by_id($id_persediaan_barang);

            // print_r($data_barang_selected);

            $data = array(
                'button' => 'Simpan Produk',
                'action' => site_url('sys_unit_produk/create_action_produksi/' . $id_persediaan_barang),

                'id' => set_value('id'),
                'uuid_unit' => set_value('uuid_unit'),
                'kode_unit' => set_value('kode_unit'),
                'nama_unit' =>  set_value('nama_unit'),
                'tgl_transaksi' => set_value('tgl_transaksi'),

                'id_persediaan_barang' => $id_persediaan_barang,
                'uuid_barang' => $data_barang_selected->uuid_barang,
                'kode_barang' => $data_barang_selected->kode_barang,
                'nama_barang' => $data_barang_selected->namabarang,
                'satuan' => $data_barang_selected->satuan,
                'harga_satuan' => $data_barang_selected->hpp,

                'jumlah_produksi' => set_value('jumlah_produksi'),
            );

            // $this->load->view('sys_unit_produk/sys_unit_produk_form', $data);

            // $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_unit_produk/adminlte310_sys_unit_produk_form_baru', $data);
        } else {
            // print_r("Baru");
            // print_r("<br/>");
            // print_r($this->input->post('nama_barang', TRUE));
            // die;



            $data = array(
                'button' => 'Simpan Produk',
                'action' => site_url('sys_unit_produk/create_action_produksi/' . $id_persediaan_barang),

                'id' => set_value('id'),
                'uuid_unit' => set_value('uuid_unit'),
                'kode_unit' => set_value('kode_unit'),
                'nama_unit' =>  set_value('nama_unit'),
                'tgl_transaksi' => set_value('tgl_transaksi'),

                'id_persediaan_barang' => set_value('id_persediaan_barang'),
                'uuid_barang' => set_value('uuid_barang'),
                'kode_barang' => set_value('kode_barang'),
                'nama_barang' => $this->input->post('nama_barang', TRUE),
                'satuan' => set_value('satuan'),
                'harga_satuan' => set_value('harga_satuan'),

                'jumlah_produksi' => set_value('jumlah_produksi'),
            );

            // $this->load->view('sys_unit_produk/sys_unit_produk_form', $data);


        }
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_unit_produk/adminlte310_sys_unit_produk_form_baru', $data);
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
                // Sudah ada nama produk

                // 




                $row_sys_nama_barang = $this->Sys_nama_barang_model->get_by_uuid_barang($this->input->post('uuid_barang', TRUE));

                $data = array(
                    // 'id' => $this->input->post('id', TRUE),
                    'tanggal' => $date_tgl_produksi,
                    // 'tanggal_new' => $date_persediaan,
                    'uuid_barang' => $row_sys_nama_barang->uuid_barang,
                    'kode' => $row_sys_nama_barang->kode_barang,
                    'namabarang' => $row_sys_nama_barang->nama_barang,
                    'satuan' => $this->input->post('satuan', TRUE),
                    'hpp' => preg_replace("/[^0-9]/", "", $this->input->post('harga_satuan', TRUE)),
                    // 'sa' => ,
                    'tanggal_beli' => $date_tgl_produksi,
                    'spop' => "produksi",
                    'total_10' => preg_replace("/[^0-9]/", "", $this->input->post('jumlah_produksi', TRUE)),
                );

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

                    'uuid_barang' => $this->input->post('uuid_barang', TRUE),
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

                $this->Tbl_pembelian_model->insert($data);
            } else {
                // Produk Baru
                // INPUT BARANG BARU : Simpan ke tabel sys_barang & persediaan

                $data = array(
                    // 'uuid_barang' => $this->input->post('uuid_barang',TRUE),
                    'kode_barang' => str_replace(" ", "", $this->input->post('nama_barang', TRUE)),
                    'nama_barang' => $this->input->post('nama_barang', TRUE),
                    'satuan' => $this->input->post('satuan', TRUE),
                    // 'keterangan' => $this->input->post('keterangan', TRUE),
                );

                $id_sys_nama_barang = $this->Sys_nama_barang_model->insert($data);

                // kemudian mendapatkan uuid_barang setelah input ke tabel sys_namabarang

                $row_sys_nama_barang = $this->Sys_nama_barang_model->get_by_id($id_sys_nama_barang);

                $data = array(
                    // 'id' => $this->input->post('id', TRUE),
                    'tanggal' => $date_tgl_produksi,
                    // 'tanggal_new' => $date_persediaan,
                    'uuid_barang' => $row_sys_nama_barang->uuid_barang,
                    'kode' => $row_sys_nama_barang->kode_barang,
                    'namabarang' => $row_sys_nama_barang->nama_barang,
                    'satuan' => $this->input->post('satuan', TRUE),
                    'hpp' => preg_replace("/[^0-9]/", "", $this->input->post('harga_satuan', TRUE)),
                    // 'sa' => ,
                    'tanggal_beli' => $date_tgl_produksi,
                    'spop' => "produksi",
                    'total_10' => preg_replace("/[^0-9]/", "", $this->input->post('jumlah_produksi', TRUE)),
                );

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


                    'uuid_barang' => $row_sys_nama_barang->uuid_barang,
                    'kode_barang' => $row_sys_nama_barang->kode_barang,
                    'uraian' => $row_sys_nama_barang->nama_barang,

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
                'uuid_produk' => $row_sys_nama_barang->uuid_barang,
                'kode_barang' => $row_sys_nama_barang->kode_barang,
                'nama_barang' => $row_sys_nama_barang->nama_barang,
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


            // INPUT BARANG BARU : Simpan ke tabel sys_barang & persediaan



            $data = array(
                // 'uuid_barang' => $this->input->post('uuid_barang',TRUE),
                'kode_barang' => str_replace(" ", "", $this->input->post('nama_barang', TRUE)),
                'nama_barang' => $this->input->post('nama_barang', TRUE),
                'satuan' => $this->input->post('satuan', TRUE),
                // 'keterangan' => $this->input->post('keterangan', TRUE),
            );

            $id_sys_nama_barang = $this->Sys_nama_barang_model->insert($data);

            // kemudian mendapatkan uuid_barang setelah input ke tabel sys_namabarang

            $row_sys_nama_barang = $this->Sys_nama_barang_model->get_by_id($id_sys_nama_barang);


            // Kemudian Insert ke tabel persediaan

            if (date("Y", strtotime($this->input->post('tgl_transaksi', TRUE))) < 2020) {
                $date_tgl_produksi = date("Y-m-d H:i:s");
            } else {
                $date_tgl_produksi = date("Y-m-d H:i:s", strtotime($this->input->post('tgl_transaksi', TRUE)));
            }



            $data = array(
                // 'id' => $this->input->post('id', TRUE),
                'tanggal' => $date_tgl_produksi,
                // 'tanggal_new' => $date_persediaan,
                'kode' => $row_sys_nama_barang->kode_barang,
                'namabarang' => $row_sys_nama_barang->nama_barang,
                'satuan' => $this->input->post('satuan', TRUE),
                'hpp' => $this->input->post('harga_satuan', TRUE),
                'sa' => preg_replace("/[^0-9]/", "", $this->input->post('jumlah_produksi', TRUE)),
                'tanggal_beli' => $date_tgl_produksi,
                'spop' => "produksi",
                'total_10' => preg_replace("/[^0-9]/", "", $this->input->post('jumlah_produksi', TRUE)),
            );
            $this->Persediaan_model->insert($data);

            // End of Kemudian Insert ke tabel persediaan





            $data_unit = $this->Sys_unit_model->get_by_uuid_unit($this->input->post('uuid_unit', TRUE));


            $data = array(
                'uuid_unit' => $data_unit->uuid_unit,
                'kode_unit' => $data_unit->kode_unit,
                'nama_unit' => $data_unit->nama_unit,
                'tgl_transaksi' => $date_tgl_produksi,
                'uuid_produk' => $row_sys_nama_barang->uuid_barang,
                'kode_barang' => $row_sys_nama_barang->kode_barang,
                'nama_barang' => $row_sys_nama_barang->nama_barang,
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
        $uuid_produk=$row_data_produk->uuid_produk;

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
            // print_r($data);

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
                // 'sa' => ,
                'tanggal_beli' => $date_tgl_produksi,
                'spop' => "produksi",
                'total_10' => preg_replace("/[^0-9]/", "", $this->input->post('jumlah_produksi', TRUE)),
            );

            // SIMPAN KE PERSEDIAAN
            $this->Persediaan_model->update($id_persediaan_barang,$data);


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

            $this->Tbl_pembelian_model->update($uuid_produk,$data);

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