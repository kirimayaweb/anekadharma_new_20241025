<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jurnal_penyesuaian extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Jurnal_penyesuaian_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . 'jurnal_penyesuaian/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'jurnal_penyesuaian/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'jurnal_penyesuaian/index.html';
            $config['first_url'] = base_url() . 'jurnal_penyesuaian/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Jurnal_penyesuaian_model->total_rows($q);
        $jurnal_penyesuaian = $this->Jurnal_penyesuaian_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'jurnal_penyesuaian_data' => $jurnal_penyesuaian,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->load->view('jurnal_penyesuaian/jurnal_penyesuaian_list', $data);
    }

    public function read($id) 
    {
        $row = $this->Jurnal_penyesuaian_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'uuid_jurnal_penyesuaian' => $row->uuid_jurnal_penyesuaian,
		'kode_akun' => $row->kode_akun,
		'tanggal' => $row->tanggal,
		'keterangan' => $row->keterangan,
		'kode_rekening' => $row->kode_rekening,
		'debet' => $row->debet,
		'kredit' => $row->kredit,
	    );
            $this->load->view('jurnal_penyesuaian/jurnal_penyesuaian_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_penyesuaian'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('jurnal_penyesuaian/create_action'),
	    'id' => set_value('id'),
	    'uuid_jurnal_penyesuaian' => set_value('uuid_jurnal_penyesuaian'),
	    'kode_akun' => set_value('kode_akun'),
	    'tanggal' => set_value('tanggal'),
	    'keterangan' => set_value('keterangan'),
	    'kode_rekening' => set_value('kode_rekening'),
	    'debet' => set_value('debet'),
	    'kredit' => set_value('kredit'),
	);
        $this->load->view('jurnal_penyesuaian/jurnal_penyesuaian_form', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'uuid_jurnal_penyesuaian' => $this->input->post('uuid_jurnal_penyesuaian',TRUE),
		'kode_akun' => $this->input->post('kode_akun',TRUE),
		'tanggal' => $this->input->post('tanggal',TRUE),
		'keterangan' => $this->input->post('keterangan',TRUE),
		'kode_rekening' => $this->input->post('kode_rekening',TRUE),
		'debet' => $this->input->post('debet',TRUE),
		'kredit' => $this->input->post('kredit',TRUE),
	    );

            $this->Jurnal_penyesuaian_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('jurnal_penyesuaian'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Jurnal_penyesuaian_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('jurnal_penyesuaian/update_action'),
		'id' => set_value('id', $row->id),
		'uuid_jurnal_penyesuaian' => set_value('uuid_jurnal_penyesuaian', $row->uuid_jurnal_penyesuaian),
		'kode_akun' => set_value('kode_akun', $row->kode_akun),
		'tanggal' => set_value('tanggal', $row->tanggal),
		'keterangan' => set_value('keterangan', $row->keterangan),
		'kode_rekening' => set_value('kode_rekening', $row->kode_rekening),
		'debet' => set_value('debet', $row->debet),
		'kredit' => set_value('kredit', $row->kredit),
	    );
            $this->load->view('jurnal_penyesuaian/jurnal_penyesuaian_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_penyesuaian'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
		'uuid_jurnal_penyesuaian' => $this->input->post('uuid_jurnal_penyesuaian',TRUE),
		'kode_akun' => $this->input->post('kode_akun',TRUE),
		'tanggal' => $this->input->post('tanggal',TRUE),
		'keterangan' => $this->input->post('keterangan',TRUE),
		'kode_rekening' => $this->input->post('kode_rekening',TRUE),
		'debet' => $this->input->post('debet',TRUE),
		'kredit' => $this->input->post('kredit',TRUE),
	    );

            $this->Jurnal_penyesuaian_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('jurnal_penyesuaian'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Jurnal_penyesuaian_model->get_by_id($id);

        if ($row) {
            $this->Jurnal_penyesuaian_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('jurnal_penyesuaian'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jurnal_penyesuaian'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('uuid_jurnal_penyesuaian', 'uuid jurnal penyesuaian', 'trim|required');
	$this->form_validation->set_rules('kode_akun', 'kode akun', 'trim|required');
	$this->form_validation->set_rules('tanggal', 'tanggal', 'trim|required');
	$this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');
	$this->form_validation->set_rules('kode_rekening', 'kode rekening', 'trim|required');
	$this->form_validation->set_rules('debet', 'debet', 'trim|required|numeric');
	$this->form_validation->set_rules('kredit', 'kredit', 'trim|required|numeric');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "jurnal_penyesuaian.xls";
        $judul = "jurnal_penyesuaian";
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
	xlsWriteLabel($tablehead, $kolomhead++, "Uuid Jurnal Penyesuaian");
	xlsWriteLabel($tablehead, $kolomhead++, "Kode Akun");
	xlsWriteLabel($tablehead, $kolomhead++, "Tanggal");
	xlsWriteLabel($tablehead, $kolomhead++, "Keterangan");
	xlsWriteLabel($tablehead, $kolomhead++, "Kode Rekening");
	xlsWriteLabel($tablehead, $kolomhead++, "Debet");
	xlsWriteLabel($tablehead, $kolomhead++, "Kredit");

	foreach ($this->Jurnal_penyesuaian_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
	    xlsWriteLabel($tablebody, $kolombody++, $data->uuid_jurnal_penyesuaian);
	    xlsWriteLabel($tablebody, $kolombody++, $data->kode_akun);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tanggal);
	    xlsWriteLabel($tablebody, $kolombody++, $data->keterangan);
	    xlsWriteLabel($tablebody, $kolombody++, $data->kode_rekening);
	    xlsWriteNumber($tablebody, $kolombody++, $data->debet);
	    xlsWriteNumber($tablebody, $kolombody++, $data->kredit);

	    $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

}

/* End of file Jurnal_penyesuaian.php */
/* Location: ./application/controllers/Jurnal_penyesuaian.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2025-05-20 10:28:43 */
/* http://harviacode.com */