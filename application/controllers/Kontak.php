<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
// use Restserver\Libraries\REST_Controller;

class Kontak extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
    }

    //Menampilkan data kontak
    function index_get() {
        
        $id = $this->get('id');
        if ($id == 7) {
            $kontak = "Horeee angka 7";
            // $kontak = $this->db->get('telepon')->result();
        } else {
            $kontak = "Duh salah";
            // $this->db->where('id', $id);
            // $kontak = $this->db->get('telepon')->result();
        }
        $this->response($kontak, 200);

        
    }
    //Masukan function selanjutnya disini
}
?>