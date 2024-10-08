<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller
{

	public function index()
	{
		// You can put anything here to generate of barcode
		$string = 'code39';
		// $this->set_barcode($string);
	}

	public function set_barcode($code)
	{
		// Load library
		$this->load->library('zend');
		// Load in folder Zend
		$this->zend->load('Zend/Barcode');
		// Generate barcode
		Zend_Barcode::render('code128', 'image', array('text' => $code), array());
	}
}
