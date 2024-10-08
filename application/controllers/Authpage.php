<?php
class Authpage extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        // is_login();
        // $this->load->model(array('KirimWa_model','Tbl_user_model'));
        // $this->load->library('form_validation');
    }

    function index()
    {
        $this->load->view('REFRESH_PAGE/refresh_page');
    }

    function refreshpage1()
    {
        $this->load->view('REFRESH_PAGE/refresh_page_1');
    }





}
