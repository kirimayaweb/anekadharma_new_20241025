<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Barcode extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // $this->load->model('htmltopdf_model');
        $this->load->library('Pdf');
    }

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



    public function pdf_test()
    {

        // 1. PERSIAPAN LIBRARY
        $this->load->library('PdfGenerator');
        // 2. PERSIAPAN DATA
        $data = array(
            'data_tagihan_menara_by_id' => "1 pdf"
        );



        // 3. MENAMPILKAN FILE DATA
        $html = $this->load->view('pdf_test.php', $data, true);

        // 4. CONVERT TAMPILAN FILE DATA MENJADI FILE PDF
        $this->pdf->loadHtml($html);
        $this->pdf->render();
        // $ID_RETRIBUSI = NAMA FILE PDF YANG DIBUAT , 
        //$id_retribusi = "TEST_PDF";
        $this->pdf->stream("SKRD_.pdf", array("Attachment" => 0));
    }


    public function pdf_test_1()
    {
        $this->load->library('pdf');
        $html = $this->load->view('GeneratePdfView', [], true);
        $this->pdf->createPDF($html, 'mypdf', false);
    }


    public function pdf_test_2()
    {
        $customer_id = "TEST";
        // $html_content = '<h3 align="center">Convert HTML to PDF in CodeIgniter using Dompdf</h3>';
        // // $html_content .= $this->htmltopdf_model->fetch_single_details($customer_id);
        // $html_content .= '<br/>';
        // $html_content .= 'TEST';

        $html_content = $this->load->view('pdf_test/GeneratePdfView', [], true);
        $this->pdf->loadHtml($html_content);
        $this->pdf->render();
        $this->pdf->stream("" . $customer_id . ".pdf", array("Attachment" => 0));
    }
}
