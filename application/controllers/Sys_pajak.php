<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sys_pajak extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Sys_pajak_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        // $q = urldecode($this->input->get('q', TRUE));
        // $start = intval($this->input->get('start'));

        // if ($q <> '') {
        //     $config['base_url'] = base_url() . 'sys_pajak/index.html?q=' . urlencode($q);
        //     $config['first_url'] = base_url() . 'sys_pajak/index.html?q=' . urlencode($q);
        // } else {
        //     $config['base_url'] = base_url() . 'sys_pajak/index.html';
        //     $config['first_url'] = base_url() . 'sys_pajak/index.html';
        // }

        // $config['per_page'] = 10;
        // $config['page_query_string'] = TRUE;
        // $config['total_rows'] = $this->Sys_pajak_model->total_rows($q);
        // $sys_pajak = $this->Sys_pajak_model->get_limit_data($config['per_page'], $start, $q);

        // $this->load->library('pagination');
        // $this->pagination->initialize($config);

        // $data = array(
        //     'sys_pajak_data' => $sys_pajak,
        //     'q' => $q,
        //     'pagination' => $this->pagination->create_links(),
        //     'total_rows' => $config['total_rows'],
        //     'start' => $start,
        // );



        $Data_var_pajak = $this->Sys_pajak_model->get_all();
        $data = array(
            'Data_var_pajak' => $Data_var_pajak,
        );

        // $this->load->view('sys_pajak/sys_pajak_list', $data);
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_pajak/adminlte310_sys_pajak_form', $data);
    }

    public function read($id)
    {
        $row = $this->Sys_pajak_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'uuid_var_pajak' => $row->uuid_var_pajak,
                'varaibel' => $row->varaibel,
                'nominal' => $row->nominal,
                'keterangan' => $row->keterangan,
            );
            $this->load->view('sys_pajak/sys_pajak_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_pajak'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('sys_pajak/create_action'),
            'id' => set_value('id'),
            'uuid_var_pajak' => set_value('uuid_var_pajak'),
            'varaibel' => set_value('varaibel'),
            'nominal' => set_value('nominal'),
            'keterangan' => set_value('keterangan'),
        );
        $this->load->view('sys_pajak/sys_pajak_form', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'uuid_var_pajak' => $this->input->post('uuid_var_pajak', TRUE),
                'varaibel' => $this->input->post('varaibel', TRUE),
                'nominal' => $this->input->post('nominal', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
            );

            $this->Sys_pajak_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('sys_pajak'));
        }
    }

    public function update($id)
    {
        $row = $this->Sys_pajak_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('sys_pajak/update_action'),
                'id' => set_value('id', $row->id),
                'uuid_var_pajak' => set_value('uuid_var_pajak', $row->uuid_var_pajak),
                'varaibel' => set_value('varaibel', $row->varaibel),
                'nominal' => set_value('nominal', $row->nominal),
                'keterangan' => set_value('keterangan', $row->keterangan),
            );
            $this->load->view('sys_pajak/sys_pajak_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_pajak'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                // 'uuid_var_pajak' => $this->input->post('uuid_var_pajak', TRUE),
                // 'varaibel' => $this->input->post('varaibel', TRUE),
                'nominal' => $this->input->post('nominal', TRUE),
                // 'keterangan' => $this->input->post('keterangan', TRUE),
            );

            $this->Sys_pajak_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('sys_pajak'));
        }
    }

    public function update_action_by_id($id)
    {

        print_r($id);
        print_r("<br/>");

            $data = array(
                'nominal' => $this->input->post('nominal', TRUE),
            );

            print_r($data);
            print_r("<br/>");
             
            $this->Sys_pajak_model->update($id, $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('sys_pajak'));
        
    }




    public function delete($id)
    {
        $row = $this->Sys_pajak_model->get_by_id($id);

        if ($row) {
            $this->Sys_pajak_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('sys_pajak'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_pajak'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('uuid_var_pajak', 'uuid var pajak', 'trim|required');
        $this->form_validation->set_rules('varaibel', 'varaibel', 'trim|required');
        $this->form_validation->set_rules('nominal', 'nominal', 'trim|required|numeric');
        $this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "sys_pajak.xls";
        $judul = "sys_pajak";
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
        xlsWriteLabel($tablehead, $kolomhead++, "Uuid Var Pajak");
        xlsWriteLabel($tablehead, $kolomhead++, "Varaibel");
        xlsWriteLabel($tablehead, $kolomhead++, "Nominal");
        xlsWriteLabel($tablehead, $kolomhead++, "Keterangan");

        foreach ($this->Sys_pajak_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->uuid_var_pajak);
            xlsWriteLabel($tablebody, $kolombody++, $data->varaibel);
            xlsWriteNumber($tablebody, $kolombody++, $data->nominal);
            xlsWriteLabel($tablebody, $kolombody++, $data->keterangan);

            $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }
}

/* End of file Sys_pajak.php */
/* Location: ./application/controllers/Sys_pajak.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-09-12 02:52:56 */
/* http://harviacode.com */