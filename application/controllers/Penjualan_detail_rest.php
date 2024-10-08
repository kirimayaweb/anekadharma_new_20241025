<?php

require APPPATH . '/libraries/REST_Controller.php';

class Penjualan_detail_rest extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
    }

    // show data mahasiswa
    function index_get() {
        $get_uuid_penjualan = $this->get('uuid_trans_penjualan');
        $get_uuid_produk = $this->get('uuid_produk');
    
  
        if ($get_uuid_penjualan == '') {
            $data_uuid_penjualan = $this->db->get('trans_penjualan_detail')->result();
        } else {
            // $this->db->where('uuid_trans_penjualan', $get_uuid_penjualan);
            // // $this->db->where('uuid_produk', $get_uuid_produk);
            // $data_uuid_penjualan = $this->db->get('trans_penjualan_detail')->result();

            // $sql = "select * from trans_penjualan_detail where uuid_trans_penjualan= '$get_uuid_penjualan' AND uuid_produk= '$get_uuid_produk'";

            $sql = "select id,uuid_trans_penjualan,kode_penjualan,uuid_produk,uuid_sales,date_input,tahun_pesanan,semester_pesanan,tingkat_pesanan,mapel_pesanan,uuid_cover_produk,exemplar_pesanan,jumlah_halaman_pesanan from trans_penjualan_detail where uuid_trans_penjualan= '$get_uuid_penjualan'";

            $data_uuid_penjualan = $this->db->query($sql)->result();
        }
        $this->response($data_uuid_penjualan, 200);
    }

    // insert new data to mahasiswa
    function index_post() {
        $data = array(
                    'nim'           => $this->post('nim'),
                    'nama'          => $this->post('nama'),
                    'id_jurusan'    => $this->post('id_jurusan'),
                    'alamat'        => $this->post('alamat'));
        $insert = $this->db->insert('mahasiswa', $data);
        if ($insert) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }

    // update data mahasiswa
    function index_put() {
        $nim = $this->put('nim');
        $data = array(
                    'nim'       => $this->put('nim'),
                    'nama'      => $this->put('nama'),
                    'id_jurusan'=> $this->put('id_jurusan'),
                    'alamat'    => $this->put('alamat'));
        $this->db->where('nim', $nim);
        $update = $this->db->update('mahasiswa', $data);
        if ($update) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }

    // delete mahasiswa
    function index_delete() {
        $nim = $this->delete('nim');
        $this->db->where('nim', $nim);
        $delete = $this->db->delete('mahasiswa');
        if ($delete) {
            $this->response(array('status' => 'success'), 201);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }

}