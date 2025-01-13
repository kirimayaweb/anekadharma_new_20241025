<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_kas_kecil extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model(array('Tbl_kas_kecil_model', 'Sys_unit_model', 'Tbl_pembelian_model', 'Tbl_pembelian_pengajuan_bayar_model'));
        $this->load->library('form_validation');
        $this->load->library('datatables');
        $this->load->helper(array('nominal'));
    }

    public function indexSERVERSIDE()
    {
        $this->load->view('tbl_kas_kecil/tbl_kas_kecil_list');
    }




    public function cek_pembelian_kaskecil()
    {

        $sql = "SELECT date_input,tgl_po,uuid_konsumen,konsumen,uuid_spop,spop, sum(harga_total) as harga_total, statuslu,kas_bank FROM `tbl_pembelian` WHERE `statuslu` LIKE 'L' AND `kas_bank` LIKE 'kas' group by uuid_spop";
        $data_Lunas_kas_pembelian = $this->db->query($sql);

        // print_r($data_Lunas_kas_pembelian->result());
        // die;

        // [74] => stdClass Object ( [id] => 415 [proses_input] => 1 [date_input] => 2025-01-07 10:52:19 [uuid_pembelian] => c897252eccaa11ef9652246e9611a63c [uuid_persediaan] => [id_persediaan_barang] => [uuid_barang] => b77ca692ccaa11ef9652246e9611a63c [tgl_po] => 2024-10-02 00:00:00 [nmrsj] => [nmrfakturkwitansi] => [nmrbpb] => [uuid_spop] => c8972513ccaa11ef9652246e9611a63c [spop] => 561 [status_spop] => [uuid_supplier] => f0526577b6bd11efb1fe246e9611a63c [supplier_kode] => [supplier_nama] => Toko Devi Stationery [kode_barang] => DOCLJO32 [uraian] => double clip joyko 320 [jumlah] => 10 [satuan] => dus [uuid_konsumen] => 783a780eb6b811efb1fe246e9611a63c [konsumen] => PU-ATK [uuid_gudang] => cd64c3af883c11ef9d7f0021ccc9061e [nama_gudang] => Gudang 1 [harga_satuan] => 13500 [harga_total] => 135000 [statuslu] => L [kas_bank] => kas [tgl_bayar] => 2024-10-01 00:00:00 [id_usr] => 1 [tgl_pengajuan_1] => [nominal_pengajuan_1] => [tgl_pengajuan_2] => [nominal_pengajuan_2] => [kode_akun] => [proses_transaksi] => ) )


        // Loop data, cek spop di kas kecil sudah ada atau belum
        $start = 0;
        foreach ($data_Lunas_kas_pembelian->result() as $list_data) {
            echo ++$start;
            echo " : ";
            echo $list_data->uuid_spop;
            echo " : ";
            echo $list_data->spop;
            echo "<br/>";


            // cek di tabel kas kecil

            // SELECT * FROM `tbl_kas_kecil` WHERE `uuid_spop` LIKE 'a383069bb69c11efaa6a246e9611a63c'
            // $sql = "SELECT * FROM `tbl_pembelian` WHERE `statuslu` LIKE 'L' AND `kas_bank` LIKE 'kas'";
            //         $data_Lunas_kas_pembelian = $this->db->query($sql);

            $this->db->where('uuid_spop', $list_data->uuid_spop);
            $data_kas_kecil = $this->db->get('tbl_kas_kecil');

            if ($data_kas_kecil->num_rows() > 0) {
                print_r("ada uuid spop");
                print_r($list_data->harga_total);

                print_r(" : kas kecil : ");
                print_r($data_kas_kecil->row()->kredit);

                if ($data_kas_kecil->row()->kredit <> $list_data->harga_total) {
                    print_r("TIDAK SAMA ");

                    print_r($list_data->harga_total);


                    // Update penyesuaian harga total di pembelian dengan kredit di kas kecil
                    $data_update = array(
                        'kredit' => $list_data->harga_total,
                    );


                    // print_r($data);

                    $this->Tbl_kas_kecil_model->update($data_kas_kecil->row()->id, $data_update);
                    print_r(' SELESAI UPDATE');
                } else {

                    print_r(" SAMA : LUNAS");
                }
            } else {

                print_r("tidak ada spop");

                $date_po = date("Y-m-d", strtotime($list_data->tgl_po));

                // insert ke kas kecil
                $data = array(
                    // 'uuid_kas_kecil' => $this->input->post('uuid_kas_kecil', TRUE),
                    'date_input' => date("Y-m-d H:i:s"),
                    'tanggal' => $list_data->tgl_po,
                    'uuid_spop' => $list_data->uuid_spop,
                    'uuid_unit' => $list_data->uuid_konsumen,
                    'unit' => $list_data->konsumen,
                    'keterangan' => "Pembayaran SPOP No. " . $list_data->spop . " tgl " . $date_po,
                    'status_data' => "pengeluaran",

                    // 'debet' => preg_replace("/[^0-9]/", "", $this->input->post('debet', TRUE)),
                    'kredit' => $list_data->harga_total,

                    // 'saldo' => $this->input->post('saldo', TRUE),
                    'id_usr' => 99999,
                );

                print_r($data);
                print_r("<br/>");

                $this->Tbl_kas_kecil_model->insert($data);
                print_r("TERSIMPAN DI KAS KECIL");
                // die;

            }


            echo "<br/>";
            echo "<br/>";
        }
    }


    public function index()
    {
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

    public function pemasukan_kas_kecil()
    {

        $Tbl_pembelian = $this->Tbl_pembelian_model->get_all();


        $data = array(
            'button' => 'Simpan',
            'action' => site_url('tbl_kas_kecil/pemasukan_kas_kecil_action'),
            'id' => set_value('id'),
            'uuid_kas_kecil' => set_value('uuid_kas_kecil'),
            'tanggal' => set_value('tanggal'),
            'unit' => set_value('unit'),
            'keterangan' => set_value('keterangan'),
            'status_data' => set_value('status_data'),
            'debet' => set_value('debet'),
            'kredit' => set_value('kredit'),
            'saldo' => set_value('saldo'),
            'id_usr' => set_value('id_usr'),
            'Tbl_pembelian_data' => $Tbl_pembelian,
        );
        // $this->load->view('tbl_kas_kecil/tbl_kas_kecil_form', $data);
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_kas_kecil/adminlte310_tbl_kas_kecil_form_pemasukan', $data);
    }

    public function pemasukan_kas_kecil_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->pemasukan_kas_kecil();
        } else {


            // print_r($this->input->post('debet', TRUE));
            // print_r("<br/>");

            // $x=str_replace(".", "", $this->input->post('debet', TRUE));

            // print_r($x);
            // print_r("<br/>");

            // $nominal_debet = str_replace(",", ".", $this->input->post('debet', TRUE));


            // print_r($nominal_debet);
            // print_r("<br/>");
            // print_r(preg_replace("/[^0-9]/", "", $this->input->post('debet', TRUE)));
            // print_r("<br/>");

            // $nominal_debet_x = str_replace(",", ".", str_replace(".", "", $this->input->post('debet', TRUE)));

            // print_r($nominal_debet_x);

            // die;


            $row_unit = $this->Sys_unit_model->get_by_uuid_unit($this->input->post('unit', TRUE));


            if (date("Y", strtotime($this->input->post('tanggal', TRUE))) < 2020) {
                $date_kas_kecil = date("Y-m-d H:i:s");
            } else {
                $date_kas_kecil = date("Y-m-d H:i:s", strtotime($this->input->post('tanggal', TRUE)));
            }

            // print_r($date_kas_kecil);
            // die;

            $data = array(
                // 'uuid_kas_kecil' => $this->input->post('uuid_kas_kecil', TRUE),
                'date_input' => date("Y-m-d H:i:s"),
                'tanggal' => $date_kas_kecil,
                'unit' => $row_unit->nama_unit,
                'keterangan' => $this->input->post('keterangan', TRUE),
                'status_data' => "pemasukan",

                // 'debet' => preg_replace("/[^0-9]/", "", $this->input->post('debet', TRUE)),
                // 'debet' => str_replace(".", "", $this->input->post('debet', TRUE)),
                'debet' => str_replace(",", ".", str_replace(".", "", $this->input->post('debet', TRUE))),


                // 'kredit' => preg_replace("/[^0-9]/", "", $this->input->post('kredit', TRUE)),
                // 'saldo' => $this->input->post('saldo', TRUE),
                // 'id_usr' => $this->input->post('id_usr', TRUE),
            );



            $this->Tbl_kas_kecil_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('tbl_kas_kecil'));
        }
    }

    public function pengeluaran_kas_kecil_update($get_id = null, $get_spop = null)
    {

        // List data pembelian
        $sql = "SELECT 
        tbl_pembelian_a.id as id,
        tbl_pembelian_a.tgl_po as tanggal,
        tbl_pembelian_a.tgl_po as tanggal,
        tbl_pembelian_a.supplier_nama as nama_supplier,
        tbl_pembelian_a.uuid_spop as uuid_spop,
        tbl_pembelian_a.spop as spop,
        tbl_pembelian_a.statuslu as statuslu,
        tbl_pembelian_a.uuid_konsumen as uuid_konsumen,
        tbl_pembelian_a.konsumen as konsumen,
        -- tbl_pembelian_a.harga_satuan as harga_satuan,
        sum(tbl_pembelian_a.harga_total) as sum_harga_total,
        (tbl_pembelian_a.jumlah*tbl_pembelian_a.harga_satuan) as total_belanja,
        sys_supplier_a.nama_supplier as nama_supplier_1
    
        FROM tbl_pembelian tbl_pembelian_a 

        left join   sys_supplier  sys_supplier_a ON  sys_supplier_a.nama_supplier = tbl_pembelian_a.supplier_nama
        where tbl_pembelian_a.statuslu='U'
        group by tbl_pembelian_a.uuid_spop
        order by tbl_pembelian_a.spop ASC
        ";

        $Tbl_pembelian = $this->db->query($sql)->result();

        // GET DATA KAS KECIL
        $GET_DATA_KAS_KECIL = $this->Tbl_kas_kecil_model->get_by_id($get_id);

        // print_r($GET_DATA_KAS_KECIL);
        // die;

        // CEK APAKAH ADA UUID_UNIT ?
        if ($GET_DATA_KAS_KECIL->uuid_unit) {
            $get_uuid_unit = $GET_DATA_KAS_KECIL->uuid_unit;
        } else {

            // cari uuid_unit berdasarkan nama unit
            if ($GET_DATA_KAS_KECIL->unit) {

                $get_nama_unit = $GET_DATA_KAS_KECIL->unit;

                $sql_data_unit = "SELECT * FROM `sys_unit` WHERE `nama_unit` LIKE '$get_nama_unit'";
                $Get_data_unit = $this->db->query($sql_data_unit)->row();

                $get_uuid_unit = $Get_data_unit->uuid_unit;

                // print_r("blm ada uuid_unit");
                // print_r("<br/>");
                // print_r($GET_DATA_KAS_KECIL->unit);
                // print_r("<br/>");
                // print_r($get_uuid_unit);
                // print_r("<br/>");
                // print_r($Get_data_unit->nama_unit);
                // die;

                // UPDATE UUID_UNIT TABEL KAS KECIL 
                // UPDATE `sys_unit` SET `uuid_unit`='[value-2]' WHERE `id`=1
                $sql_update_uuid_unit = "UPDATE `tbl_kas_kecil` SET `uuid_unit`='$get_uuid_unit' WHERE `id`='$get_id'";
                $this->db->query($sql_update_uuid_unit);
            } else {
                $get_uuid_unit = "";
            }
        }


        if ($GET_DATA_KAS_KECIL) {
            if ($get_spop) {

                // CEK get_spop apakah sama dengan uuid_spop di id kas_kecil , jika beda maka akan merubah spop ( ubah spop lama menjadi u dan spop baru menjadi L )

                // $message = "wrong answer";
                // echo "<script type='text/javascript'>alert('$message');</script>";
                // $this->pengeluaran_kas_kecil_update($get_id,$get_spop);
                // redirect(site_url('Tbl_kas_kecil/pengeluaran_kas_kecil_update/' . $get_id .'/'.$get_spop));

                if ($get_spop <> $GET_DATA_KAS_KECIL->uuid_spop) {

                    // $message = "Berbeda spop";
                    // echo "<script type='text/javascript'>alert('$message');</script>";

                    $Get_spop_lama = $GET_DATA_KAS_KECIL->uuid_spop;



                    $sql = "SELECT 
                    tbl_pembelian_a.id as id,
                    tbl_pembelian_a.tgl_po as tanggal,
                    tbl_pembelian_a.supplier_nama as nama_supplier,
                    tbl_pembelian_a.uuid_spop as uuid_spop,
                    tbl_pembelian_a.spop as spop,
                    -- tbl_pembelian_a.jumlah as jumlah,
                    -- tbl_pembelian_a.harga_satuan as harga_satuan,
                    sum(tbl_pembelian_a.harga_total) as sum_harga_total,
                    (tbl_pembelian_a.jumlah*tbl_pembelian_a.harga_satuan) as total_belanja,
                    sys_supplier_a.nama_supplier as nama_supplier_1
                
                    FROM tbl_pembelian tbl_pembelian_a 
        
                    left join   sys_supplier  sys_supplier_a ON  sys_supplier_a.nama_supplier = tbl_pembelian_a.supplier_nama
                    where tbl_pembelian_a.uuid_spop LIKE '$Get_spop_lama'
                    group by tbl_pembelian_a.uuid_spop
                    order by tbl_pembelian_a.spop ASC
                    ";

                    $Get_data_SPOP_Lama_proses = $this->db->query($sql)->row();
                } else {

                    // $message = "spop sama";
                    // echo "<script type='text/javascript'>alert('$message');</script>";

                    $Get_data_SPOP_Lama_proses = "";
                    $Get_spop_lama = "";
                }

                // print_r("update spop");
                // print_r("<br/>");
                // die;

                $sql = "SELECT 
                tbl_pembelian_a.id as id,
                tbl_pembelian_a.tgl_po as tanggal,
                tbl_pembelian_a.supplier_nama as nama_supplier,
                tbl_pembelian_a.uuid_spop as uuid_spop,
                tbl_pembelian_a.spop as spop,
                -- tbl_pembelian_a.jumlah as jumlah,
                -- tbl_pembelian_a.harga_satuan as harga_satuan,
                sum(tbl_pembelian_a.harga_total) as sum_harga_total,
                (tbl_pembelian_a.jumlah*tbl_pembelian_a.harga_satuan) as total_belanja,
                sys_supplier_a.nama_supplier as nama_supplier_1
            
                FROM tbl_pembelian tbl_pembelian_a 
    
                left join   sys_supplier  sys_supplier_a ON  sys_supplier_a.nama_supplier = tbl_pembelian_a.supplier_nama
                where tbl_pembelian_a.uuid_spop LIKE '$get_spop'
                group by tbl_pembelian_a.uuid_spop
                order by tbl_pembelian_a.spop ASC
                ";

                $Get_data_proses = $this->db->query($sql)->row();

                $data = array(
                    'button' => 'Ubah',
                    'action' => site_url('tbl_kas_kecil/pengeluaran_kas_kecil_update_action/' . $get_id . '/' . $get_spop . '/' . $Get_spop_lama),
                    'id' => $GET_DATA_KAS_KECIL->id,
                    'uuid_kas_kecil' => $GET_DATA_KAS_KECIL->uuid_kas_kecil,
                    'tanggal' => $GET_DATA_KAS_KECIL->tanggal,
                    'uuid_spop' => $get_spop,
                    'uuid_unit' => $get_uuid_unit,
                    'unit' => $GET_DATA_KAS_KECIL->unit,
                    'keterangan' => $GET_DATA_KAS_KECIL->keterangan,
                    'debet' => $GET_DATA_KAS_KECIL->debet,
                    'kredit' => $GET_DATA_KAS_KECIL->kredit,
                    'saldo' => $GET_DATA_KAS_KECIL->saldo,
                    'id_usr' => $GET_DATA_KAS_KECIL->id_usr,
                    'Tbl_pembelian_data' => $Tbl_pembelian,
                    'Get_data_proses' => $Get_data_proses,
                    'Get_spop_lama' => $Get_data_SPOP_Lama_proses,
                );
            } else {

                $Get_data_proses = "";
                $Get_data_SPOP_Lama_proses = "";

                $data = array(
                    'button' => 'Ubah',
                    'action' => site_url('tbl_kas_kecil/pengeluaran_kas_kecil_update_action/' . $get_id),
                    'id' => $GET_DATA_KAS_KECIL->id,
                    'uuid_kas_kecil' => $GET_DATA_KAS_KECIL->uuid_kas_kecil,
                    'tanggal' => $GET_DATA_KAS_KECIL->tanggal,
                    'uuid_spop' => $get_spop,
                    'uuid_unit' => $get_uuid_unit,
                    'unit' => $GET_DATA_KAS_KECIL->unit,
                    'keterangan' => $GET_DATA_KAS_KECIL->keterangan,
                    'debet' => $GET_DATA_KAS_KECIL->debet,
                    'kredit' => $GET_DATA_KAS_KECIL->kredit,
                    'saldo' => $GET_DATA_KAS_KECIL->saldo,
                    'id_usr' => $GET_DATA_KAS_KECIL->id_usr,
                    'Tbl_pembelian_data' => $Tbl_pembelian,
                    'Get_data_proses' => $Get_data_proses,
                    'Get_spop_lama' => $Get_data_SPOP_Lama_proses,
                );
            }

            // $this->load->view('tbl_kas_kecil/tbl_kas_kecil_form', $data);
            $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_kas_kecil/adminlte310_tbl_kas_kecil_form_pengeluaran_update', $data);
        } else {

            // Tidak ada id 
            redirect(site_url('tbl_kas_kecil'));
        }
    }


    public function pengeluaran_kas_kecil_update_action($get_id = null, $get_spop = null, $Get_spop_lama = null)
    {


        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->pengeluaran_kas_kecil_update($get_id);
        } else {


            // print_r($get_id);
            // print_r("<br/>");
            // print_r($get_spop);
            // print_r("<br/>");
            // print_r($Get_spop_lama);
            // print_r("<br/>");
            // die;


            if (date("Y", strtotime($this->input->post('tanggal', TRUE))) < 2020) {
                $date_kas_kecil = date("Y-m-d H:i:s");
            } else {
                $date_kas_kecil = date("Y-m-d H:i:s", strtotime($this->input->post('tanggal', TRUE)));
            }


            $row_unit = $this->Sys_unit_model->get_by_uuid_unit($this->input->post('unit', TRUE));



            if ($get_spop) {
                if ($Get_spop_lama) {

                    // Update tabel pembelian : spop lama = U dan spop baru = L & tabel kas kecil berdasarkan id di update

                    // Update spop lama pembelian ==> menjadi U kembali
                    $sql = "UPDATE `tbl_pembelian` SET `statuslu`='U',`kas_bank`='kas',`tgl_bayar`='0000-00-00 00:00:00' WHERE `uuid_spop`='$Get_spop_lama'";
                    $this->db->query($sql);

                    // proses update tbl_pembelian ==> memproses Lunas jika sudah sesuai total nominal per spop nya
                    $sql = "UPDATE `tbl_pembelian` SET `statuslu`='L',`kas_bank`='kas',`tgl_bayar`='$date_kas_kecil' WHERE `uuid_spop`='$get_spop'";
                    $this->db->query($sql);

                    // print_r("update uuid_spop");
                    $data = array(
                        // 'uuid_kas_kecil' => $this->input->post('uuid_kas_kecil', TRUE),
                        'date_input' => date("Y-m-d H:i:s"),
                        'tanggal' => $date_kas_kecil,
                        'uuid_spop' => $get_spop,
                        'uuid_unit' => $this->input->post('unit', TRUE),
                        'unit' => $row_unit->nama_unit,
                        'keterangan' => $this->input->post('keterangan', TRUE),
                        'status_data' => "pengeluaran",
                        
                        // 'debet' => preg_replace("/[^0-9]/", "", $this->input->post('debet', TRUE)),
                        
                        
                        // 'kredit' => preg_replace("/[^0-9]/", "", $this->input->post('kredit', TRUE)),
                        'kredit' => str_replace(",", ".", str_replace(".", "", $this->input->post('kredit', TRUE))),
                        
                        
                        
                        // 'saldo' => $this->input->post('saldo', TRUE),
                        // 'id_usr' => $this->input->post('id_usr', TRUE),
                    );
                } else {
                    // Update sesuai spop ( update tabel kas kecil berdasarkan id tanpa merubah status tabel pembelian )

                    // proses update tbl_pembelian ==> memproses Lunas jika sudah sesuai total nominal per spop nya
                    $sql = "UPDATE `tbl_pembelian` SET `statuslu`='L',`kas_bank`='kas',`tgl_bayar`='$date_kas_kecil' WHERE `uuid_spop`='$get_spop'";
                    $this->db->query($sql);

                    // print_r("update uuid_spop");
                    $data = array(
                        // 'uuid_kas_kecil' => $this->input->post('uuid_kas_kecil', TRUE),
                        'date_input' => date("Y-m-d H:i:s"),
                        'tanggal' => $date_kas_kecil,
                        'uuid_spop' => $get_spop,
                        'uuid_unit' => $this->input->post('unit', TRUE),
                        'unit' => $row_unit->nama_unit,
                        'keterangan' => $this->input->post('keterangan', TRUE),
                        'status_data' => "pengeluaran",
                        
                        // 'debet' => preg_replace("/[^0-9]/", "", $this->input->post('debet', TRUE)),
                        
                        // 'kredit' => preg_replace("/[^0-9]/", "", $this->input->post('kredit', TRUE)),
                        'kredit' => str_replace(",", ".", str_replace(".", "", $this->input->post('kredit', TRUE))),
                        
                        
                        // 'saldo' => $this->input->post('saldo', TRUE),
                        // 'id_usr' => $this->input->post('id_usr', TRUE),
                    );
                }
            } else {
                // Update data tabel kas kecil berdasarkan id secara langsung data tanpa ada data spop pembelian

                $data = array(
                    // 'uuid_kas_kecil' => $this->input->post('uuid_kas_kecil', TRUE),
                    'date_input' => date("Y-m-d H:i:s"),
                    'tanggal' => $date_kas_kecil,
                    'uuid_unit' => $this->input->post('unit', TRUE),
                    'unit' => $row_unit->nama_unit,
                    'keterangan' => $this->input->post('keterangan', TRUE),
                    'status_data' => "pengeluaran",
                    // 'debet' => preg_replace("/[^0-9]/", "", $this->input->post('debet', TRUE)),

                    // 'kredit' => preg_replace("/[^0-9]/", "", $this->input->post('kredit', TRUE)),
                    // 'kredit' => str_replace(".", "", $this->input->post('kredit', TRUE)),
                    'kredit' => str_replace(",", ".", str_replace(".", "", $this->input->post('kredit', TRUE))),
                    
                    
                    // 'saldo' => $this->input->post('saldo', TRUE),
                    // 'id_usr' => $this->input->post('id_usr', TRUE),
                );
            }





            $this->Tbl_kas_kecil_model->update($get_id, $data);

            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('tbl_kas_kecil'));
        }
    }

    public function pengeluaran_kas_kecil($get_spop = null)
    {

        // $Tbl_pembelian = $this->Tbl_pembelian_model->get_all();


        // List data proses
        if ($get_spop) {
            $sql = "SELECT 
            tbl_pembelian_a.id as id,
            tbl_pembelian_a.tgl_po as tanggal,
            tbl_pembelian_a.supplier_nama as nama_supplier,
            tbl_pembelian_a.uuid_spop as uuid_spop,
            tbl_pembelian_a.spop as spop,
            -- tbl_pembelian_a.jumlah as jumlah,
            -- tbl_pembelian_a.harga_satuan as harga_satuan,
            sum(tbl_pembelian_a.harga_total) as sum_harga_total,
            (tbl_pembelian_a.jumlah*tbl_pembelian_a.harga_satuan) as total_belanja,
            sys_supplier_a.nama_supplier as nama_supplier_1
        

            FROM tbl_pembelian tbl_pembelian_a 

            left join   sys_supplier  sys_supplier_a ON  sys_supplier_a.nama_supplier = tbl_pembelian_a.supplier_nama
            where tbl_pembelian_a.uuid_spop LIKE '$get_spop'
            group by tbl_pembelian_a.uuid_spop
            order by tbl_pembelian_a.spop ASC
            ";

            $Get_data_proses = $this->db->query($sql)->row();
        } else {
            $Get_data_proses = "";
        }

        // return $this->db->query($sql)->result();


        // List data pembelian
        $sql = "SELECT 
        tbl_pembelian_a.id as id,
        tbl_pembelian_a.tgl_po as tanggal,
        tbl_pembelian_a.tgl_po as tanggal,
        tbl_pembelian_a.supplier_nama as nama_supplier,
        tbl_pembelian_a.uuid_spop as uuid_spop,
        tbl_pembelian_a.spop as spop,
        tbl_pembelian_a.statuslu as statuslu,
        -- tbl_pembelian_a.harga_satuan as harga_satuan,
        sum(tbl_pembelian_a.harga_total) as sum_harga_total,
        (tbl_pembelian_a.jumlah*tbl_pembelian_a.harga_satuan) as total_belanja,
        sys_supplier_a.nama_supplier as nama_supplier_1
    

        FROM tbl_pembelian tbl_pembelian_a 

        left join   sys_supplier  sys_supplier_a ON  sys_supplier_a.nama_supplier = tbl_pembelian_a.supplier_nama
        where tbl_pembelian_a.statuslu='U'
        group by tbl_pembelian_a.uuid_spop
        order by tbl_pembelian_a.spop ASC
        ";

        $Tbl_pembelian = $this->db->query($sql)->result();

        // print_r($this->db->query($sql)->result());
        // die;

        if ($get_spop) {
            $data = array(
                'button' => 'Simpan',
                'action' => site_url('tbl_kas_kecil/pengeluaran_kas_kecil_action/' . $get_spop),
                'id' => set_value('id'),
                'uuid_kas_kecil' => set_value('uuid_kas_kecil'),
                'tanggal' => set_value('tanggal'),
                'unit' => set_value('unit'),
                'keterangan' => set_value('keterangan'),
                'status_data' => set_value('status_data'),
                'debet' => set_value('debet'),
                'kredit' => set_value('kredit'),
                'saldo' => set_value('saldo'),
                'id_usr' => set_value('id_usr'),
                'Tbl_pembelian_data' => $Tbl_pembelian,
                'Get_data_proses' => $Get_data_proses,
            );
        } else {
            $data = array(
                'button' => 'Simpan',
                'action' => site_url('tbl_kas_kecil/pengeluaran_kas_kecil_action'),
                'id' => set_value('id'),
                'uuid_kas_kecil' => set_value('uuid_kas_kecil'),
                'tanggal' => set_value('tanggal'),
                'unit' => set_value('unit'),
                'keterangan' => set_value('keterangan'),
                'status_data' => set_value('status_data'),
                'debet' => set_value('debet'),
                'kredit' => set_value('kredit'),
                'saldo' => set_value('saldo'),
                'id_usr' => set_value('id_usr'),
                'Tbl_pembelian_data' => $Tbl_pembelian,
                'Get_data_proses' => $Get_data_proses,
            );
        }


        // $this->load->view('tbl_kas_kecil/tbl_kas_kecil_form', $data);
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_kas_kecil/adminlte310_tbl_kas_kecil_form_pengeluaran', $data);
    }

    public function pengeluaran_kas_kecil_action($get_spop = null)
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->pengeluaran_kas_kecil();
        } else {

            // print_r($this->input->post('kredit', TRUE));
            // print_r("<br/>");
            // print_r(preg_replace("/[^0-9]/", "", $this->input->post('kredit', TRUE)));
            // print_r("<br/>");
            
            // print_r(str_replace(".", "", $this->input->post('kredit', TRUE)) );
            // die;

            if (date("Y", strtotime($this->input->post('tanggal', TRUE))) < 2020) {
                $date_kas_kecil = date("Y-m-d H:i:s");
            } else {
                $date_kas_kecil = date("Y-m-d H:i:s", strtotime($this->input->post('tanggal', TRUE)));
            }


            $row_unit = $this->Sys_unit_model->get_by_uuid_unit($this->input->post('unit', TRUE));

            if ($get_spop) {
                // proses update tbl_pembelian ==> memproses Lunas jika sudah sesuai total nominal per spop nya
                $sql = "UPDATE `tbl_pembelian` SET `statuslu`='L',`kas_bank`='kas',`tgl_bayar`='$date_kas_kecil' WHERE `uuid_spop`='$get_spop'";
                $this->db->query($sql);

                // print_r("update uuid_spop");
                $data = array(
                    // 'uuid_kas_kecil' => $this->input->post('uuid_kas_kecil', TRUE),
                    'date_input' => date("Y-m-d H:i:s"),
                    'tanggal' => $date_kas_kecil,
                    'uuid_spop' => $get_spop,
                    'uuid_unit' => $this->input->post('unit', TRUE),
                    'unit' => $row_unit->nama_unit,
                    'keterangan' => $this->input->post('keterangan', TRUE),
                    'status_data' => "pengeluaran",
                    // 'debet' => preg_replace("/[^0-9]/", "", $this->input->post('debet', TRUE)),

                    // 'kredit' => preg_replace("/[^0-9]/", "", $this->input->post('kredit', TRUE)),
                    // 'kredit' => str_replace(".", "", $this->input->post('kredit', TRUE)),
                    'kredit' => str_replace(",", ".", str_replace(".", "", $this->input->post('kredit', TRUE))),


                    // 'saldo' => $this->input->post('saldo', TRUE),
                    // 'id_usr' => $this->input->post('id_usr', TRUE),
                );
            } else {

                $data = array(
                    // 'uuid_kas_kecil' => $this->input->post('uuid_kas_kecil', TRUE),
                    'date_input' => date("Y-m-d H:i:s"),
                    'tanggal' => $date_kas_kecil,
                    'uuid_unit' => $this->input->post('unit', TRUE),
                    'unit' => $row_unit->nama_unit,
                    'keterangan' => $this->input->post('keterangan', TRUE),
                    'status_data' => "pengeluaran",
                    // 'debet' => preg_replace("/[^0-9]/", "", $this->input->post('debet', TRUE)),
                    
                    // 'kredit' => preg_replace("/[^0-9]/", "", $this->input->post('kredit', TRUE)),
                    // 'kredit' => str_replace(".", "", $this->input->post('kredit', TRUE)),
                    'kredit' => str_replace(",", ".", str_replace(".", "", $this->input->post('kredit', TRUE))),

                    // 'saldo' => $this->input->post('saldo', TRUE),
                    // 'id_usr' => $this->input->post('id_usr', TRUE),
                );
            }

            $this->Tbl_kas_kecil_model->insert($data);

            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('tbl_kas_kecil'));
        }
    }


    public function create()
    {

        $Tbl_pembelian = $this->Tbl_pembelian_model->get_all();


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
            'Tbl_pembelian_data' => $Tbl_pembelian,
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


            if ($status_proses == "debet") {
                $data = array(
                    // 'uuid_kas_kecil' => $this->input->post('uuid_kas_kecil', TRUE),
                    'tanggal' => $date_kas_kecil,
                    'unit' => $row_unit->nama_unit,
                    'keterangan' => $this->input->post('keterangan', TRUE),
                    'debet' => preg_replace("/[^0-9]/", "", $this->input->post('debet', TRUE)),
                    // 'kredit' => preg_replace("/[^0-9]/", "", $this->input->post('kredit', TRUE)),
                    // 'saldo' => $this->input->post('saldo', TRUE),
                    // 'id_usr' => $this->input->post('id_usr', TRUE),
                );
            } else {
                $data = array(
                    // 'uuid_kas_kecil' => $this->input->post('uuid_kas_kecil', TRUE),
                    'tanggal' => $date_kas_kecil,
                    'unit' => $row_unit->nama_unit,
                    'keterangan' => $this->input->post('keterangan', TRUE),
                    // 'debet' => preg_replace("/[^0-9]/", "", $this->input->post('debet', TRUE)),
                    'kredit' => preg_replace("/[^0-9]/", "", $this->input->post('kredit', TRUE)),
                    // 'saldo' => $this->input->post('saldo', TRUE),
                    // 'id_usr' => $this->input->post('id_usr', TRUE),
                );
            }



            $this->Tbl_kas_kecil_model->insert($data);


            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('tbl_kas_kecil'));
        }
    }

    public function update($uuid_kas_kecil = null, $get_spop_update = null)
    {
        $row = $this->Tbl_kas_kecil_model->get_by_uuid_kas_kecil($uuid_kas_kecil);

        if ($row) {


            if ($get_spop_update) {
                $sql = "SELECT 
                tbl_pembelian_a.id as id,
                tbl_pembelian_a.tgl_po as tanggal,
                tbl_pembelian_a.supplier_nama as nama_supplier,
                tbl_pembelian_a.uuid_spop as uuid_spop,
                tbl_pembelian_a.spop as spop,
                -- tbl_pembelian_a.jumlah as jumlah,
                -- tbl_pembelian_a.harga_satuan as harga_satuan,
                sum(tbl_pembelian_a.harga_total) as sum_harga_total,
                (tbl_pembelian_a.jumlah*tbl_pembelian_a.harga_satuan) as total_belanja,
                sys_supplier_a.nama_supplier as nama_supplier_1
            
    
                FROM tbl_pembelian tbl_pembelian_a 
    
                left join   sys_supplier  sys_supplier_a ON  sys_supplier_a.nama_supplier = tbl_pembelian_a.supplier_nama
                where tbl_pembelian_a.uuid_spop LIKE '$get_spop_update'
                group by tbl_pembelian_a.uuid_spop
                order by tbl_pembelian_a.spop ASC
                ";

                $Get_data_proses = $this->db->query($sql)->row();
            } else {
                $Get_data_proses = "";
            }



            // List data pembelian
            $sql = "SELECT 
                    tbl_pembelian_a.id as id,
                    tbl_pembelian_a.tgl_po as tanggal,
                    tbl_pembelian_a.tgl_po as tanggal,
                    tbl_pembelian_a.supplier_nama as nama_supplier,
                    tbl_pembelian_a.uuid_spop as uuid_spop,
                    tbl_pembelian_a.spop as spop,
                    tbl_pembelian_a.statuslu as statuslu,
                    -- tbl_pembelian_a.jumlah as jumlah,
                    -- tbl_pembelian_a.harga_satuan as harga_satuan,
                    sum(tbl_pembelian_a.harga_total) as sum_harga_total,
                    (tbl_pembelian_a.jumlah*tbl_pembelian_a.harga_satuan) as total_belanja,
                    sys_supplier_a.nama_supplier as nama_supplier_1
                

                    FROM tbl_pembelian tbl_pembelian_a 

                    left join   sys_supplier  sys_supplier_a ON  sys_supplier_a.nama_supplier = tbl_pembelian_a.supplier_nama

                    group by tbl_pembelian_a.uuid_spop
                    order by tbl_pembelian_a.spop ASC
                    ";

            $Tbl_pembelian = $this->db->query($sql)->result();



            if ($get_spop_update) {
                $data = array(
                    'button' => 'Update',
                    'action' => site_url('tbl_kas_kecil/update_action/' . $uuid_kas_kecil . '/' . $get_spop_update),
                    'id' => set_value('id', $row->id),
                    'uuid_kas_kecil' => set_value('uuid_kas_kecil', $row->uuid_kas_kecil),
                    'uuid_spop' => set_value('uuid_spop', $row->uuid_spop),
                    'tanggal' => set_value('tanggal', $row->tanggal),
                    'uuid_unit' => set_value('uuid_unit', $row->uuid_unit),
                    'unit' => set_value('unit', $row->unit),
                    'keterangan' => set_value('keterangan', $row->keterangan),
                    'debet' => set_value('debet', $row->debet),
                    'kredit' => set_value('kredit', $row->kredit),
                    'saldo' => set_value('saldo', $row->saldo),
                    // 'id_usr' => set_value('id_usr', $row->id_usr),
                    'Tbl_pembelian_data' => $Tbl_pembelian,
                    'Get_data_proses' => $Get_data_proses,
                );
            } else {
                $data = array(
                    'button' => 'Update',
                    'action' => site_url('tbl_kas_kecil/update_action/' . $uuid_kas_kecil),
                    'id' => set_value('id', $row->id),
                    'uuid_kas_kecil' => set_value('uuid_kas_kecil', $row->uuid_kas_kecil),
                    'uuid_spop' => set_value('uuid_spop', $row->uuid_spop),
                    'tanggal' => set_value('tanggal', $row->tanggal),
                    'uuid_unit' => set_value('uuid_unit', $row->uuid_unit),
                    'unit' => set_value('unit', $row->unit),
                    'keterangan' => set_value('keterangan', $row->keterangan),
                    'debet' => set_value('debet', $row->debet),
                    'kredit' => set_value('kredit', $row->kredit),
                    'saldo' => set_value('saldo', $row->saldo),
                    // 'id_usr' => set_value('id_usr', $row->id_usr),
                    'Tbl_pembelian_data' => $Tbl_pembelian,
                    'Get_data_proses' => $Get_data_proses,
                );
            }

            // print_r($data);
            // die;
            // $this->load->view('tbl_kas_kecil/tbl_kas_kecil_form', $data);

            $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/tbl_kas_kecil/adminlte310_tbl_kas_kecil_form_update', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_kas_kecil'));
        }
    }

    public function update_action($uuid_kas_kecil = null, $get_spop_update = null)
    {

        // print_r("update_action");
        // print_r("<br/>");

        $this->_rules();

        if ($this->form_validation->run() == FALSE) {

            // print_r("update_action FALSE");
            // print_r("<br/>");

            $this->update($uuid_kas_kecil);
        } else {

            // print_r("update_action TRUE");
            // print_r("<br/>");
            // print_r($this->input->post('unit', TRUE));
            // print_r("<br/>");


            // Cek apakah ada uuid_spop , jika ada cek apakah uuid_spop berbeda dengan uuid_spop kas kecil berdasarkan filter uuid_kas_kecil
            // Jika uuid_spop berbeda , maka update yang tabel tbl_pembelian
            $get_data_kas_kecil = $this->Tbl_kas_kecil_model->get_by_uuid_kas_kecil($uuid_kas_kecil);

            $get_row_unit = $this->Sys_unit_model->get_by_uuid_unit($this->input->post('unit', TRUE));

            // print_r($get_row_unit);
            // die;

            if (date("Y", strtotime($this->input->post('tanggal', TRUE))) < 2020) {
                $date_kas_kecil = date("Y-m-d H:i:s");
            } else {
                $date_kas_kecil = date("Y-m-d H:i:s", strtotime($this->input->post('tanggal', TRUE)));
            }


            // print_r($this->input->post('tanggal', TRUE));
            // print_r("<br/>");
            // print_r($date_kas_kecil);
            // print_r("<br/>");


            if ($get_spop_update) {
                if ($get_spop_update <> $get_data_kas_kecil->uuid_spop) {
                    $uuid_spop_lama = $get_data_kas_kecil->uuid_spop;
                    $uuid_spop_update_proses = $get_spop_update;

                    $date_kas_kecil = $get_data_kas_kecil->uuid_spop;

                    //uuid_pembelian di ubah ==> uuid_spop awal di kembalikan ke U dan proses get_spop_update ke L

                    // 1. update uuid_spop di tbl_pembelian == U                    
                    $sql = "UPDATE `tbl_pembelian` SET `statuslu`='U',`kas_bank`='',`tgl_bayar`='$date_kas_kecil' WHERE `uuid_spop`='$uuid_spop_lama'";
                    $this->db->query($sql);

                    // 2. update get_spop_update di tbl_pembelian == L dan kas 
                    $sql = "UPDATE `tbl_pembelian` SET `statuslu`='L',`kas_bank`='kas',`tgl_bayar`='$date_kas_kecil' WHERE `uuid_spop`='$get_spop_update'";
                    $this->db->query($sql);
                }
            } else {

                $uuid_spop_update_proses = $get_data_kas_kecil->uuid_spop;
            }




            if ($this->input->post('status_proses', TRUE) == "debet") {

                // print_r("debet");
                // print_r("<br/>");
                // print_r($this->input->post('debet', TRUE));

                $data = array(
                    // 'uuid_kas_kecil' => $this->input->post('uuid_kas_kecil', TRUE),
                    'tanggal' => $date_kas_kecil,
                    'uuid_spop' => $uuid_spop_update_proses,
                    'uuid_unit' => $get_row_unit->uuid_unit,
                    'unit' => $get_row_unit->nama_unit,
                    'keterangan' => $this->input->post('keterangan', TRUE),
                    'debet' => preg_replace("/[^0-9]/", "", $this->input->post('debet', TRUE)),
                    // 'kredit' => preg_replace("/[^0-9]/", "", $this->input->post('kredit', TRUE)),
                    // 'saldo' => $this->input->post('saldo', TRUE),
                    // 'id_usr' => $this->input->post('id_usr', TRUE),
                );
            } else {

                // print_r("kredit");
                // print_r("<br/>");
                // print_r($this->input->post('debet', TRUE));


                $data = array(
                    // 'uuid_kas_kecil' => $this->input->post('uuid_kas_kecil', TRUE),
                    'tanggal' => $date_kas_kecil,
                    'uuid_spop' => $uuid_spop_update_proses,
                    'uuid_unit' => $get_row_unit->uuid_unit,
                    'unit' => $get_row_unit->nama_unit,
                    'keterangan' => $this->input->post('keterangan', TRUE),
                    // 'debet' => preg_replace("/[^0-9]/", "", $this->input->post('debet', TRUE)),
                    'kredit' => preg_replace("/[^0-9]/", "", $this->input->post('kredit', TRUE)),
                    // 'saldo' => $this->input->post('saldo', TRUE),
                    // 'id_usr' => $this->input->post('id_usr', TRUE),
                );
            }

            // print_r($data);

            $this->Tbl_kas_kecil_model->update($this->input->post('id', TRUE), $data);

            //    die;
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('tbl_kas_kecil'));
        }
    }

    public function delete($id)
    {
        $row = $this->Tbl_kas_kecil_model->get_by_id($id);

        if ($row) {

            $reset_date_bayar_pembelian = "0000-00-00 00:00:00";
            $get_spop = $row->uuid_spop;

            // Cek apaah ada isi record uuid_spop tbl_pembelian , jika ada maka update uuid_spop --> statuslu menjadi U
            $sql = "UPDATE `tbl_pembelian` SET `statuslu`='U',`kas_bank`='',`tgl_bayar`='$reset_date_bayar_pembelian' WHERE `uuid_spop`='$get_spop'";
            $this->db->query($sql);

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

        $tgl_sekarang = date("d-m-Y H:i:s");

        $this->load->helper('exportexcel');
        $namaFile = "KAS_KECIL_" . $tgl_sekarang . ".xls";
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
        // xlsWriteLabel($tablehead, $kolomhead++, "Uuid Kas Kecil");
        xlsWriteLabel($tablehead, $kolomhead++, "Tanggal");
        xlsWriteLabel($tablehead, $kolomhead++, "Unit");
        xlsWriteLabel($tablehead, $kolomhead++, "Keterangan");
        xlsWriteLabel($tablehead, $kolomhead++, "Debet");
        xlsWriteLabel($tablehead, $kolomhead++, "Kredit");
        xlsWriteLabel($tablehead, $kolomhead++, "Saldo");
        // xlsWriteLabel($tablehead, $kolomhead++, "Id Usr");

        $get_total_debet = 0;
        $get_total_kredit = 0;
        foreach ($this->Tbl_kas_kecil_model->get_all() as $data) {
            $kolombody = 0;

            $get_total_debet = $get_total_debet + $data->debet;
            $get_total_kredit = $get_total_kredit + $data->kredit;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            // xlsWriteLabel($tablebody, $kolombody++, $data->uuid_kas_kecil);
            xlsWriteLabel($tablebody, $kolombody++, $data->tanggal);
            xlsWriteLabel($tablebody, $kolombody++, $data->unit);
            xlsWriteLabel($tablebody, $kolombody++, $data->keterangan);
            xlsWriteLabel($tablebody, $kolombody++, number_format($data->debet, 2, ',', '.'));
            xlsWriteLabel($tablebody, $kolombody++, number_format($data->kredit, 2, ',', '.'));
            xlsWriteLabel($tablebody, $kolombody++, number_format($get_total_debet - $get_total_kredit, 2, ',', '.'));
            // xlsWriteNumber($tablebody, $kolombody++, $data->id_usr);

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