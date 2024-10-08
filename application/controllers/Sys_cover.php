<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sys_cover extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Sys_cover_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->uri->segment(3));

        if ($q <> '') {
            $config['base_url'] = base_url() . '.php/c_url/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'index.php/sys_cover/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'index.php/sys_cover/index/';
            $config['first_url'] = base_url() . 'index.php/sys_cover/index/';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = FALSE;
        $config['total_rows'] = $this->Sys_cover_model->total_rows($q);


        // $sys_cover = $this->Sys_cover_model->get_limit_data($config['per_page'], $start, $q);
        $sys_cover = $this->Sys_cover_model->get_all_tingkat_ASC();


        $config['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
        $config['full_tag_close'] = '</ul>';
        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'sys_cover_data' => $sys_cover,
            // 'q' => $q,
            // 'pagination' => $this->pagination->create_links(),
            // 'total_rows' => $config['total_rows'],
            // 'start' => $start,
        );
        $this->template->load('template/adminlte310', 'sys_cover/adminlte310_sys_cover_list', $data);
    }

    public function read($id)
    {
        $row = $this->Sys_cover_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'uuid_cover' => $row->uuid_cover,
                'date_input' => $row->date_input,
                'id_user_input' => $row->id_user_input,
                'tingkat' => $row->tingkat,
                'mapel' => $row->mapel,
                'kelas' => $row->kelas,
                'tahun' => $row->tahun,
                'semester' => $row->semester,
                'halaman' => $row->halaman,
                'nama_cover' => $row->nama_cover,
                'keterangan' => $row->keterangan,
            );
            $this->template->load('template', 'sys_cover/sys_cover_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_cover'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('sys_cover/create_action'),
            'id' => set_value('id'),
            'uuid_cover' => set_value('uuid_cover'),
            'date_input' => set_value('date_input'),
            'id_user_input' => set_value('id_user_input'),
            'tingkat' => set_value('tingkat'),
            'mapel' => set_value('mapel'),
            'kelas' => set_value('kelas'),
            'tahun' => set_value('tahun'),
            'semester' => set_value('semester'),
            'halaman' => set_value('halaman'),
            'nama_cover' => set_value('nama_cover'),
            'keterangan' => set_value('keterangan'),
        );
        // $this->template->load('template', 'sys_cover/adminlte310_sys_cover_form', $data);
        $this->template->load('template/adminlte310', 'sys_cover/adminlte310_sys_cover_form', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                // 'uuid_cover' => $this->input->post('uuid_cover', TRUE),
                // 'date_input' => $this->input->post('date_input', TRUE),
                // 'id_user_input' => $this->input->post('id_user_input', TRUE),
                'tingkat' => $this->input->post('tingkat', TRUE),
                'mapel' => $this->input->post('mapel', TRUE),
                'kelas' => $this->input->post('kelas', TRUE),
                'tahun' => $this->input->post('tahun', TRUE),
                'semester' => $this->input->post('semester', TRUE),
                'halaman' => $this->input->post('halaman', TRUE),
                'nama_cover' => $this->input->post('nama_cover', TRUE),
                // 'keterangan' => $this->input->post('keterangan', TRUE),
            );

            $this->Sys_cover_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success 2');
            redirect(site_url('sys_cover'));
        }
    }

    public function update($id)
    {
        $row = $this->Sys_cover_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('sys_cover/update_action'),
                'id' => set_value('id', $row->id),
                'uuid_cover' => set_value('uuid_cover', $row->uuid_cover),
                'date_input' => set_value('date_input', $row->date_input),
                'id_user_input' => set_value('id_user_input', $row->id_user_input),
                'tingkat' => set_value('tingkat', $row->tingkat),
                'mapel' => set_value('mapel', $row->mapel),
                'kelas' => set_value('kelas', $row->kelas),
                'tahun' => set_value('tahun', $row->tahun),
                'semester' => set_value('semester', $row->semester),
                'halaman' => set_value('halaman', $row->halaman),
                'nama_cover' => set_value('nama_cover', $row->nama_cover),
                'keterangan' => set_value('keterangan', $row->keterangan),
            );
            // $this->template->load('template', 'sys_cover/sys_cover_form', $data);
            // $this->template->load('template/adminlte310', 'sys_cover/sys_cover_form', $data);
            $this->template->load('template/adminlte310', 'sys_cover/adminlte310_sys_cover_form', $data);

        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_cover'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                // 'uuid_cover' => $this->input->post('uuid_cover', TRUE),
                // 'date_input' => $this->input->post('date_input', TRUE),
                // 'id_user_input' => $this->input->post('id_user_input', TRUE),
                'tingkat' => $this->input->post('tingkat', TRUE),
                // 'mapel' => $this->input->post('mapel', TRUE),
                // 'kelas' => $this->input->post('kelas', TRUE),
                // 'tahun' => $this->input->post('tahun', TRUE),
                // 'semester' => $this->input->post('semester', TRUE),
                // 'halaman' => $this->input->post('halaman', TRUE),
                'nama_cover' => $this->input->post('nama_cover', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
            );

            $this->Sys_cover_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('sys_cover'));
        }
    }

    public function delete($id)
    {
        $row = $this->Sys_cover_model->get_by_id($id);

        if ($row) {
            $this->Sys_cover_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('sys_cover'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_cover'));
        }
    }

    public function _rules()
    {
        // $this->form_validation->set_rules('uuid_cover', 'uuid cover', 'trim|required');
        // $this->form_validation->set_rules('date_input', 'date input', 'trim|required');
        // $this->form_validation->set_rules('id_user_input', 'id user input', 'trim|required');
        $this->form_validation->set_rules('tingkat', 'tingkat', 'trim|required');
        // $this->form_validation->set_rules('mapel', 'mapel', 'trim|required');
        // $this->form_validation->set_rules('kelas', 'kelas', 'trim|required');
        // $this->form_validation->set_rules('tahun', 'tahun', 'trim|required');
        // $this->form_validation->set_rules('semester', 'semester', 'trim|required');
        // $this->form_validation->set_rules('halaman', 'halaman', 'trim|required');
        $this->form_validation->set_rules('nama_cover', 'nama cover', 'trim|required');
        // $this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "sys_cover.xls";
        $judul = "sys_cover";
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
        xlsWriteLabel($tablehead, $kolomhead++, "Uuid Cover");
        xlsWriteLabel($tablehead, $kolomhead++, "Date Input");
        xlsWriteLabel($tablehead, $kolomhead++, "Id User Input");
        xlsWriteLabel($tablehead, $kolomhead++, "Tingkat");
        xlsWriteLabel($tablehead, $kolomhead++, "Mapel");
        xlsWriteLabel($tablehead, $kolomhead++, "Kelas");
        xlsWriteLabel($tablehead, $kolomhead++, "Tahun");
        xlsWriteLabel($tablehead, $kolomhead++, "Semester");
        xlsWriteLabel($tablehead, $kolomhead++, "Halaman");
        xlsWriteLabel($tablehead, $kolomhead++, "Nama Cover");
        xlsWriteLabel($tablehead, $kolomhead++, "Keterangan");

        foreach ($this->Sys_cover_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->uuid_cover);
            xlsWriteLabel($tablebody, $kolombody++, $data->date_input);
            xlsWriteLabel($tablebody, $kolombody++, $data->id_user_input);
            xlsWriteLabel($tablebody, $kolombody++, $data->tingkat);
            xlsWriteLabel($tablebody, $kolombody++, $data->mapel);
            xlsWriteNumber($tablebody, $kolombody++, $data->kelas);
            xlsWriteLabel($tablebody, $kolombody++, $data->tahun);
            xlsWriteNumber($tablebody, $kolombody++, $data->semester);
            xlsWriteNumber($tablebody, $kolombody++, $data->halaman);
            xlsWriteLabel($tablebody, $kolombody++, $data->nama_cover);
            xlsWriteLabel($tablebody, $kolombody++, $data->keterangan);

            $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

    public function word()
    {
        header("Content-type: application/vnd.ms-word");
        header("Content-Disposition: attachment;Filename=sys_cover.doc");

        $data = array(
            'sys_cover_data' => $this->Sys_cover_model->get_all(),
            'start' => 0
        );

        $this->load->view('sys_cover/sys_cover_doc', $data);
    }


    function get_cover(){
        $tingkat=$this->input->post('tingkat');
        $data=$this->Sys_cover_model->get_cover($tingkat);
        echo json_encode($data);
    }
}

/* End of file Sys_cover.php */
/* Location: ./application/controllers/Sys_cover.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2021-08-11 02:32:32 */
/* http://harviacode.com */