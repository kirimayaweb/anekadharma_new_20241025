<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_pembelian_pengajuan_bayar extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Tbl_pembelian_pengajuan_bayar_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . 'tbl_pembelian_pengajuan_bayar/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'tbl_pembelian_pengajuan_bayar/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'tbl_pembelian_pengajuan_bayar/index.html';
            $config['first_url'] = base_url() . 'tbl_pembelian_pengajuan_bayar/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Tbl_pembelian_pengajuan_bayar_model->total_rows($q);
        $tbl_pembelian_pengajuan_bayar = $this->Tbl_pembelian_pengajuan_bayar_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'tbl_pembelian_pengajuan_bayar_data' => $tbl_pembelian_pengajuan_bayar,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->load->view('tbl_pembelian_pengajuan_bayar/tbl_pembelian_pengajuan_bayar_list', $data);
    }

    public function read($id) 
    {
        $row = $this->Tbl_pembelian_pengajuan_bayar_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'proses_input' => $row->proses_input,
		'date_input' => $row->date_input,
		'uuid_pengajuan_bayar' => $row->uuid_pengajuan_bayar,
		'uuid_spop' => $row->uuid_spop,
		'tgl_pengajuan' => $row->tgl_pengajuan,
		'nominal_pengajuan' => $row->nominal_pengajuan,
		'id_usr' => $row->id_usr,
	    );
            $this->load->view('tbl_pembelian_pengajuan_bayar/tbl_pembelian_pengajuan_bayar_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_pembelian_pengajuan_bayar'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('tbl_pembelian_pengajuan_bayar/create_action'),
	    'id' => set_value('id'),
	    'proses_input' => set_value('proses_input'),
	    'date_input' => set_value('date_input'),
	    'uuid_pengajuan_bayar' => set_value('uuid_pengajuan_bayar'),
	    'uuid_spop' => set_value('uuid_spop'),
	    'tgl_pengajuan' => set_value('tgl_pengajuan'),
	    'nominal_pengajuan' => set_value('nominal_pengajuan'),
	    'id_usr' => set_value('id_usr'),
	);
        $this->load->view('tbl_pembelian_pengajuan_bayar/tbl_pembelian_pengajuan_bayar_form', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'proses_input' => $this->input->post('proses_input',TRUE),
		'date_input' => $this->input->post('date_input',TRUE),
		'uuid_pengajuan_bayar' => $this->input->post('uuid_pengajuan_bayar',TRUE),
		'uuid_spop' => $this->input->post('uuid_spop',TRUE),
		'tgl_pengajuan' => $this->input->post('tgl_pengajuan',TRUE),
		'nominal_pengajuan' => $this->input->post('nominal_pengajuan',TRUE),
		'id_usr' => $this->input->post('id_usr',TRUE),
	    );

            $this->Tbl_pembelian_pengajuan_bayar_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('tbl_pembelian_pengajuan_bayar'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Tbl_pembelian_pengajuan_bayar_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('tbl_pembelian_pengajuan_bayar/update_action'),
		'id' => set_value('id', $row->id),
		'proses_input' => set_value('proses_input', $row->proses_input),
		'date_input' => set_value('date_input', $row->date_input),
		'uuid_pengajuan_bayar' => set_value('uuid_pengajuan_bayar', $row->uuid_pengajuan_bayar),
		'uuid_spop' => set_value('uuid_spop', $row->uuid_spop),
		'tgl_pengajuan' => set_value('tgl_pengajuan', $row->tgl_pengajuan),
		'nominal_pengajuan' => set_value('nominal_pengajuan', $row->nominal_pengajuan),
		'id_usr' => set_value('id_usr', $row->id_usr),
	    );
            $this->load->view('tbl_pembelian_pengajuan_bayar/tbl_pembelian_pengajuan_bayar_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_pembelian_pengajuan_bayar'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
		'proses_input' => $this->input->post('proses_input',TRUE),
		'date_input' => $this->input->post('date_input',TRUE),
		'uuid_pengajuan_bayar' => $this->input->post('uuid_pengajuan_bayar',TRUE),
		'uuid_spop' => $this->input->post('uuid_spop',TRUE),
		'tgl_pengajuan' => $this->input->post('tgl_pengajuan',TRUE),
		'nominal_pengajuan' => $this->input->post('nominal_pengajuan',TRUE),
		'id_usr' => $this->input->post('id_usr',TRUE),
	    );

            $this->Tbl_pembelian_pengajuan_bayar_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('tbl_pembelian_pengajuan_bayar'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Tbl_pembelian_pengajuan_bayar_model->get_by_id($id);

        if ($row) {
            $this->Tbl_pembelian_pengajuan_bayar_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('tbl_pembelian_pengajuan_bayar'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_pembelian_pengajuan_bayar'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('proses_input', 'proses input', 'trim|required');
	$this->form_validation->set_rules('date_input', 'date input', 'trim|required');
	$this->form_validation->set_rules('uuid_pengajuan_bayar', 'uuid pengajuan bayar', 'trim|required');
	$this->form_validation->set_rules('uuid_spop', 'uuid spop', 'trim|required');
	$this->form_validation->set_rules('tgl_pengajuan', 'tgl pengajuan', 'trim|required');
	$this->form_validation->set_rules('nominal_pengajuan', 'nominal pengajuan', 'trim|required|numeric');
	$this->form_validation->set_rules('id_usr', 'id usr', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "tbl_pembelian_pengajuan_bayar.xls";
        $judul = "tbl_pembelian_pengajuan_bayar";
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
	xlsWriteLabel($tablehead, $kolomhead++, "Proses Input");
	xlsWriteLabel($tablehead, $kolomhead++, "Date Input");
	xlsWriteLabel($tablehead, $kolomhead++, "Uuid Pengajuan Bayar");
	xlsWriteLabel($tablehead, $kolomhead++, "Uuid Spop");
	xlsWriteLabel($tablehead, $kolomhead++, "Tgl Pengajuan");
	xlsWriteLabel($tablehead, $kolomhead++, "Nominal Pengajuan");
	xlsWriteLabel($tablehead, $kolomhead++, "Id Usr");

	foreach ($this->Tbl_pembelian_pengajuan_bayar_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
	    xlsWriteNumber($tablebody, $kolombody++, $data->proses_input);
	    xlsWriteLabel($tablebody, $kolombody++, $data->date_input);
	    xlsWriteLabel($tablebody, $kolombody++, $data->uuid_pengajuan_bayar);
	    xlsWriteLabel($tablebody, $kolombody++, $data->uuid_spop);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tgl_pengajuan);
	    xlsWriteNumber($tablebody, $kolombody++, $data->nominal_pengajuan);
	    xlsWriteNumber($tablebody, $kolombody++, $data->id_usr);

	    $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

}

/* End of file Tbl_pembelian_pengajuan_bayar.php */
/* Location: ./application/controllers/Tbl_pembelian_pengajuan_bayar.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-09-23 04:38:11 */
/* http://harviacode.com */