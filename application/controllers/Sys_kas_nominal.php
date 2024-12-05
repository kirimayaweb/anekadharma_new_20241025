<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sys_kas_nominal extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Sys_kas_nominal_model');
        $this->load->library('form_validation');
		$this->load->library('datatables');
		$this->load->library('Pdf');
		$this->load->helper(array('nominal'));
    }

    public function indexXBU()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        if ($q <> '') {
            $config['base_url'] = base_url() . 'sys_kas_nominal/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'sys_kas_nominal/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'sys_kas_nominal/index.html';
            $config['first_url'] = base_url() . 'sys_kas_nominal/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Sys_kas_nominal_model->total_rows($q);
        $sys_kas_nominal = $this->Sys_kas_nominal_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'sys_kas_nominal_data' => $sys_kas_nominal,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->load->view('sys_kas_nominal/sys_kas_nominal_list', $data);
    }

    public function index()
    {
        $Sys_kas_nominal_data = $this->Sys_kas_nominal_model->get_all();
        // $start = 0;
        // $data = array(
        //     'Sys_kas_nominal_data' => $Sys_kas_nominal_data,
        //     // 'start' => $start,
        //     // 'status_laporan' => $status_laporan,
        // );
        // $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_bank/adminlte310_sys_kas_nominal_list', $data);


        // $Data_var_pajak = $this->Sys_pajak_model->get_all();
        $data = array(
            'Sys_kas_nominal_data' => $Sys_kas_nominal_data,
        );


        // $this->load->view('sys_pajak/sys_pajak_list', $data);
        $this->template->load('anekadharma/adminlte310_anekadharma_topnav_aside', 'anekadharma/sys_kas_nominal/adminlte310_sys_kas_nominal_form', $data);
    }

    public function read($id)
    {
        $row = $this->Sys_kas_nominal_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'tgl_input' => $row->tgl_input,
                'uuid_kas_nominal' => $row->uuid_kas_nominal,
                'total_kas_nominal' => $row->total_kas_nominal,
                'kode_kas_nominal' => $row->kode_kas_nominal,
            );
            $this->load->view('sys_kas_nominal/sys_kas_nominal_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_kas_nominal'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('sys_kas_nominal/create_action'),
            'id' => set_value('id'),
            'tgl_input' => set_value('tgl_input'),
            'uuid_kas_nominal' => set_value('uuid_kas_nominal'),
            'total_kas_nominal' => set_value('total_kas_nominal'),
            'kode_kas_nominal' => set_value('kode_kas_nominal'),
        );
        $this->load->view('sys_kas_nominal/sys_kas_nominal_form', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'tgl_input' => $this->input->post('tgl_input', TRUE),
                'uuid_kas_nominal' => $this->input->post('uuid_kas_nominal', TRUE),
                'total_kas_nominal' => $this->input->post('total_kas_nominal', TRUE),
                'kode_kas_nominal' => $this->input->post('kode_kas_nominal', TRUE),
            );

            $this->Sys_kas_nominal_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('sys_kas_nominal'));
        }
    }




    public function update($id)
    {
        $row = $this->Sys_kas_nominal_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('sys_kas_nominal/update_action'),
                'id' => set_value('id', $row->id),
                'tgl_input' => set_value('tgl_input', $row->tgl_input),
                'uuid_kas_nominal' => set_value('uuid_kas_nominal', $row->uuid_kas_nominal),
                'total_kas_nominal' => set_value('total_kas_nominal', $row->total_kas_nominal),
                'kode_kas_nominal' => set_value('kode_kas_nominal', $row->kode_kas_nominal),
            );
            $this->load->view('sys_kas_nominal/sys_kas_nominal_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_kas_nominal'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'tgl_input' => $this->input->post('tgl_input', TRUE),
                'uuid_kas_nominal' => $this->input->post('uuid_kas_nominal', TRUE),
                'total_kas_nominal' => $this->input->post('total_kas_nominal', TRUE),
                'kode_kas_nominal' => $this->input->post('kode_kas_nominal', TRUE),
            );

            $this->Sys_kas_nominal_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('sys_kas_nominal'));
        }
    }

    public function update_action_by_id($uuid_kas_nominal)
    {
        // print_r("update_action_by_id");
        // print_r("<br/>");

        // $this->_rules();

        // if ($this->form_validation->run() == FALSE) {
        //     $this->update($this->input->post('id', TRUE));
        // } else {
        $data = array(
            'tgl_input' => date("Y-m-d"),
            // 'uuid_kas_nominal' => $this->input->post('uuid_kas_nominal', TRUE),
            'total_kas_nominal' => preg_replace("/[^0-9]/", "", $this->input->post('total_kas_nominal', TRUE)),
            // 'kode_kas_nominal' => $this->input->post('kode_kas_nominal', TRUE),
        );

        $this->Sys_kas_nominal_model->update_by_uuid_kas_nominal($uuid_kas_nominal, $data);


        //     print_r("<script>
        //     Swal.fire({
        //       title: 'Sukses!',
        //       text: 'Data berhasil disimpan.',
        //       icon: 'success',
        //       confirmButtonText: 'OK'
        //     });
        //   </script>") ;


        // Get Data
        $row = $this->Sys_kas_nominal_model->get_by_uuid_kas_nominal($uuid_kas_nominal);
        print_r($row);
        print_r("<br/>");
        print_r($row->uuid_kas_nominal);
        print_r("<br/>");
        print_r($row->total_kas_nominal);
        print_r("<br/>");
        print_r($row->kode_kas_nominal);
        print_r("<br/>");
        print_r("<br/>");
        print_r("<br/>");
        // die;

        // if ($row) {
        //     $data = array(
        //         'id' => $row->id,
        //         'tgl_input' => $row->tgl_input,
        //         'uuid_kas_nominal' => $row->uuid_kas_nominal,
        //         'total_kas_nominal' => $row->total_kas_nominal,
        //         'kode_kas_nominal' => $row->kode_kas_nominal,
        //     );
        // Kendali display sweetalert
        $sweetalert_sess = array(
            'sess_berhasil_simpan'        => '1',
            'sess_uuid_kas_nominal' => $row->uuid_kas_nominal,
            'sess_total_kas_nominal' => $row->total_kas_nominal,
            'sess_kode_kas_nominal' => 'kode_kas_nominal',
        );
        $this->session->set_userdata($sweetalert_sess);
        // print_r($this->session->userdata('sess_berhasil_simpan'));
        // print_r("<br/>");
        // print_r($this->session->userdata('uuid_kas_nominal'));
        // print_r("<br/>");
        // print_r($this->session->userdata('total_kas_nominal'));
        // print_r("<br/>");
        // print_r($this->session->userdata('kode_kas_nominal'));
        // print_r("<br/>");

        // END OF Kendali display sweetalert

        $this->session->set_flashdata('message', 'Update Record Success');
        redirect(site_url('sys_kas_nominal'));
        // }
    }

    public function delete($id)
    {
        $row = $this->Sys_kas_nominal_model->get_by_id($id);

        if ($row) {
            $this->Sys_kas_nominal_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('sys_kas_nominal'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sys_kas_nominal'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('tgl_input', 'tgl input', 'trim|required');
        $this->form_validation->set_rules('uuid_kas_nominal', 'uuid kas nominal', 'trim|required');
        $this->form_validation->set_rules('total_kas_nominal', 'total kas nominal', 'trim|required|numeric');
        $this->form_validation->set_rules('kode_kas_nominal', 'kode kas nominal', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "sys_kas_nominal.xls";
        $judul = "sys_kas_nominal";
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
        xlsWriteLabel($tablehead, $kolomhead++, "Tgl Input");
        xlsWriteLabel($tablehead, $kolomhead++, "Uuid Kas Nominal");
        xlsWriteLabel($tablehead, $kolomhead++, "Total Kas Nominal");
        xlsWriteLabel($tablehead, $kolomhead++, "Kode Kas Nominal");

        foreach ($this->Sys_kas_nominal_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->tgl_input);
            xlsWriteLabel($tablebody, $kolombody++, $data->uuid_kas_nominal);
            xlsWriteNumber($tablebody, $kolombody++, $data->total_kas_nominal);
            xlsWriteLabel($tablebody, $kolombody++, $data->kode_kas_nominal);

            $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }
}

/* End of file Sys_kas_nominal.php */
/* Location: ./application/controllers/Sys_kas_nominal.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2024-10-22 03:53:19 */
/* http://harviacode.com */