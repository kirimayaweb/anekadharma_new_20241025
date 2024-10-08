<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
// use Restserver\Libraries\REST_Controller;

class RestApi extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('Tbl_sales_model');
        $this->load->model(array('Tbl_sales_model', 'KirimWa_GET_DATA_REQ_model','KirimWa_model'));
    }

    //test laptop t420
    
    //Menampilkan data kontak
    function index_get() {
  
        $pesan = $this->get('pesan');
        $nomorhp = $this->get('nomorhp');

        //string yang akan dipecah
        // $teks = "Mangga Apel Durian";
        //pecah string berdasarkan string "," 
        $pecah_explode = explode(" ", $pesan);
        // // //mencari element array 0
        $kata1 = $pecah_explode[0];
        $kata2 = $pecah_explode[1];
        $kata3 = $pecah_explode[2];
        // $kata4 = $pecah_explode[3];
        // $kata5 = $pecah_explode[4];
        // $kata6 = $pecah_explode[5];
       

        // $this->KirimWa_GET_DATA_REQ_model->kirimwa($pesan);

        $kata1 = strtolower($kata1);
        

        switch ($kata1) {
            case "cetak":
                $pesan_balik = "CETAK WORKING GREAT!";
              break;
            case "solopos":
                $pesan_balik = "SOLOPOS WORKING GREAT!";
              break;
            case "finishing":
                $pesan_balik = "FINISHING WORKING GREAT!";
              break;
            case "gudang":
                $pesan_balik = "GUDANG WORKING GREAT!";
              break;
            case "stock":
                // $pesan_balik = "STOCK WORKING GREAT!";
                // $this->KirimWa_model->kirimwa($nomorhp, $pesan_balik);
                $this->KirimWa_GET_DATA_REQ_model->kirimwa_data_stock($nomorhp,  $kata2,  $kata3);
              break;
            case "penjualan":

                // GET UUID dari nama

                // $pesan_balik = "PENJUALAN WORKING GREAT!";
                $uuid_sales_selected = "2570638fdd3b11ebab780242ac120004";
                // $pesan_balik = $this->KirimWa_GET_DATA_REQ_model->testdata($uuid_sales_selected);
                $pesan_balik = $this->KirimWa_GET_DATA_REQ_model->penjualan_sales($uuid_sales_selected);

              break;
            case "tagihan":
                $pesan_balik = "TAGIHAN WORKING GREAT!";
              break;
            default:
            //kirim format penulisan request ==> untuk dijadikan contoh
            $pesan_balik = "kata awal belum terdifinisi.....!!!";
          }

        // $this->KirimWa_model->kirimwa($nomorhp, $pesan_balik);




        // $pesan_balik = "Pesan = " . $pesan  . "  dan nomor hp = " . $nomorhp ;

        //Kembalikan data ke webhook untuk dikirimkan ke nomor pengirim
       
        $this->response($pesan, 200);

    }
    //Masukan function selanjutnya disini
}
?>