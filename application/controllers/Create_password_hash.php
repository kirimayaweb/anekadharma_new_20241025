<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Create_password_hash extends CI_Controller
{
	   function __construct()
    {
        parent::__construct();
		$this->load->helper('url');  
		$this->load->library('form_validation');
		$this->load->model(array('General_login'));          
    }


	public function index($string_input)
	{

// print_r($string_input);
// die;

var_dump(password_hash($string_input, PASSWORD_DEFAULT));

	}

public function convert_hash_default($string_input)
{

	print_r($string_input);

	var_dump(password_hash($string_input, PASSWORD_DEFAULT));

}


public function convert_hash_bcrypt($string_input)
{

// $cek = $this->session->userdata('company'); 

// if(($this->General_login->check_login() == 'false')  ){header("location:".base_url());}

// print_r($this->General_login->check_login());
// print_r("<br>");
// print_r("<br>");
// print_r("<br>");

	// print_r($string_input);

	// var_dump(password_hash($string_input, PASSWORD_DEFAULT));


	$options = [
	    'cost' => 12,
	];
	echo password_hash($string_input, PASSWORD_BCRYPT, $options);

	print_r("<br/>");
	print_r("<br/>");

	var_dump(password_hash($string_input, PASSWORD_BCRYPT, $options));	


	// print_r("<br/>");
	// print_r("<br/>");
	
	// echo password_get_info();
}





}