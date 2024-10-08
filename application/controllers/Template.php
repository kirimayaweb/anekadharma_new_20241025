<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Template extends CI_Controller
{


    public function index()
    {
        $this->load->view('template/adminlte310');
        // $this->template->load('template', 'welcome');
    }
}
