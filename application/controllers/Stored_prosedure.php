<?php
class Stored_prosedure extends CI_Controller{
    
    
    function index($GET_UUID_SALES_X = null, $GET_TAHUN_X = null, $GET_SEMESTER_X = NULL, $GET_TINGKAT_X = null){
        // $this->load->view('auth/blokir_akses');
        
        // MEMANGGIL STORED PROSEDUR
        $GET_UUID_SALES = "'{$GET_UUID_SALES_X}'";
        $GET_TAHUN = $GET_TAHUN_X;
        $GET_SEMESTER = $GET_SEMESTER_X;
        $GET_TINGKAT = "'{$GET_TINGKAT_X}'";
        
        // print_r($GET_UUID_SALES);
        // print_r("<br/>");
        
        // print_r($GET_TAHUN);
        // print_r("<br/>");
        
        // print_r($GET_SEMESTER);
        // print_r("<br/>");
        
        // print_r($GET_TINGKAT);
        // print_r("<br/>");

        $data = $this->db->query("CALL Get_tgl_transaksi_by_sales_tahun_semester_tingkat($GET_UUID_SALES,$GET_TAHUN,$GET_SEMESTER,$GET_TINGKAT)");
        $result = $data->result();
        print_r($result);
        die;
    }
}