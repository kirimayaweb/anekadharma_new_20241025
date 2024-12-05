<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_identitas_pengguna_pasar_pilar extends CI_Controller
{


    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model(array('Tbl_identitas_pengguna_model', 'Perijinan_pendaftaran_model'));
        $this->load->library('form_validation');
        $this->load->library('Pdf');
    }

    public function index()
    {

        // if ($this->General_login->check_login() == 'false') {
        //     header("location:" . base_url());
        // }

        // $sess = array(
        //     'sess_username'        => $user['full_name'],
        //     'sess_iduser'        => $user['id_users'],
        //     'sess_id_user_level'        => $user['id_user_level'],
        //     'sess_email_user'        => $user['email'],
        // );


        // $cek = $this->session->userdata('company');

        // print_r($this->session->userdata('sess_username'));
        // print_r("<br/>");
        // print_r($this->session->userdata('sess_iduser'));
        // print_r("<br/>");
        // print_r($this->session->userdata('sess_id_user_level'));
        // print_r("<br/>");
        // print_r($this->session->userdata('sess_email_user'));
        // print_r("<br/>");

        if (($this->session->userdata('sess_id_user_level') == '1') or ($this->session->userdata('sess_id_user_level') == '2')) {
            $cek = 'admin';
        }

        // print_r($cek);
        // die;


        if ($cek == 'admin') {
            $tbl_identitas_pengguna = $this->Tbl_identitas_pengguna_model->get_all();
        } elseif ($cek == 'adminpasar') {
            $tbl_identitas_pengguna = $this->Tbl_identitas_pengguna_model->get_all();
        } elseif ($cek == 'pasar') {
            $kodepasar = $this->session->userdata('kodepasar');
            $tbl_identitas_pengguna = $this->Tbl_identitas_pengguna_model->get_all();
        } else {
            header("location:" . base_url());
        }



        $data = array(
            'username'  => $this->session->userdata('username'),
            'company'    => $this->session->userdata('company'),
            'tbl_identitas_pengguna_data' => $tbl_identitas_pengguna
        );


        $this->template->load('template/template_retribusi_lte241', 'lte_241/tbl_identitas_pengguna_retripasar_list', $data);
    }




    public function dttble3_identitas()
    {
        $cek = $this->session->userdata('company');

        if ($cek == 'admin') {
            $tbl_identitas_pengguna = $this->Tbl_identitas_pengguna_model->get_all();
        } elseif ($cek == 'adminpasar') {
            $tbl_identitas_pengguna = $this->Tbl_identitas_pengguna_model->get_all();
        } elseif ($cek == 'pasar') {
            $kodepasar = $this->session->userdata('kodepasar');
            $tbl_identitas_pengguna = $this->Tbl_identitas_pengguna_model->get_all();
        } else {
            header("location:" . base_url());
        }

        $data = array(
            'username'  => $this->session->userdata('username'),
            'company'    => $this->session->userdata('company'),
            'tbl_identitas_pengguna_data' => $tbl_identitas_pengguna
        );
        $this->template->load('pasar/template_dtlte3', 'pasar/lte310/tbl_identitas_pengguna_list_lte3', $data);
    }




    public function read($id)
    {
        if ($this->General_login->check_login() == 'false') {
            header("location:" . base_url());
        }


        $cek = $this->session->userdata('company');

        if ($cek == 'admin') {
            $row = $this->Tbl_identitas_pengguna_model->get_by_id($id);
        } elseif ($cek == 'adminpasar') {
            $row = $this->Tbl_identitas_pengguna_model->get_by_id($id);
        } elseif ($cek == 'pasar') {
            $kodepasar = $this->session->userdata('kodepasar');
            $row = $this->Tbl_identitas_pengguna_model->get_by_id($id);
        } else {
            header("location:" . base_url());
        }

        if ($row) {
            $data = array(
                'id' => $row->id,
                'nik' => $row->nik,
                'idpedagang' => $row->idpedagang,
                'nama' => $row->nama,
                'tempat_lahir' => $row->tempat_lahir,
                'tgl_lahir' => $row->tgl_lahir,
                'alamat' => $row->alamat,
                'desa' => $row->desa,
                'kecamatan' => $row->kecamatan,
                'kabupaten' => $row->kabupaten,
                'propinsi' => $row->propinsi,
                'jeniskelamin' => $row->jeniskelamin,
                'status' => $row->status,
                'no_hp' => $row->no_hp,
                'npwp' => $row->npwp,
                'username'  => $this->session->userdata('username'),
                'company'    => $this->session->userdata('company'),
            );
            $this->template->load('template', 'tbl_identitas_pengguna_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tbl_identitas_pengguna'));
        }
    }

    public function create()
    {

        if ($this->General_login->check_login() == 'false') {
            header("location:" . base_url());
        }


        $cek = $this->session->userdata('company');

        if ($cek == 'admin') {
            /* $row = $this->Tbl_identitas_pengguna_model->get_by_id($id); */
        } elseif ($cek == 'adminpasar') {
            /* $row = $this->Tbl_identitas_pengguna_model->get_by_id($id); */
        } elseif ($cek == 'pasar') {
            $kodepasar = $this->session->userdata('kodepasar');
            /* $row = $this->Tbl_identitas_pengguna_model->get_by_id($id); */
        } else {
            header("location:" . base_url());
        }


        $data = array(
            'button' => 'SIMPAN',
            'action' => site_url('index.php/tbl_identitas_pengguna/create_action'),
            'id' => set_value('id'),
            'nik' => set_value('nik'),
            'idpedagang' => set_value('idpedagang'),
            'nama' => set_value('nama'),
            'tempat_lahir' => set_value('tempat_lahir'),
            'tgl_lahir' => set_value('tgl_lahir'),
            'alamat' => set_value('alamat'),
            'desa' => set_value('desa'),
            'kecamatan' => set_value('kecamatan'),
            'kabupaten' => set_value('kabupaten'),
            'propinsi' => set_value('propinsi'),
            'jeniskelamin' => set_value('jeniskelamin'),
            'status' => set_value('status'),
            'no_hp' => set_value('no_hp'),
            'npwp' => set_value('npwp'),
            'username'  => $this->session->userdata('username'),
            'company'    => $this->session->userdata('company'),
        );
        $this->template->load('pasar/template_pasar', 'tbl_identitas_pengguna_form', $data);
    }

    public function create_action()
    {

        if ($this->General_login->check_login() == 'false') {
            header("location:" . base_url());
        }

        $cek = $this->session->userdata('company');

        if ($cek == 'admin') {
            /* $row = $this->Tbl_identitas_pengguna_model->get_by_id($id); */
        } elseif ($cek == 'adminpasar') {
            /* $row = $this->Tbl_identitas_pengguna_model->get_by_id($id); */
        } elseif ($cek == 'pasar') {
            $kodepasar = $this->session->userdata('kodepasar');
            /* $row = $this->Tbl_identitas_pengguna_model->get_by_id($id); */
        } else {
            header("location:" . base_url());
        }



        // cek NIK existing on database
        $data_NIK = $this->input->post('nik', TRUE);
        $cekNIK = $this->db->query("SELECT * FROM tbl_identitas_pedagang_pasar where nik like '$data_NIK'");

        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } elseif ($cekNIK->num_rows() > 0) {
            $_SESSION['pesan_error_NIK'] = "<strong><h5 style='color:red;font-size:16px'>NIK Sudah ada di database</h5></strong><br/><br/>";
            $this->create();
        } else {


            // PROSES UPLOAD PHOTO
            $config['upload_path']          = './images_pedagang/';
            $config['allowed_types']        = 'gif|jpg|png';
            $config['max_size']             = 100;
            $config['max_width']            = 1024;
            $config['max_height']           = 768;
            $config['file_name']            = $this->input->post('nik', TRUE) . '.jpg';
            $config['overwrite']            = true;
            $this->load->library('upload', $config);
            $data = array('upload_data' => $this->upload->data());
            // END PROSES UPLOAD PHOTO

            $data = array(
                'nik' => $this->input->post('nik', TRUE),
                'idpedagang' => $this->input->post('nik', TRUE),
                'nama' => addslashes($this->input->post('nama', TRUE)),
                'tempat_lahir' => $this->input->post('tempat_lahir', TRUE),
                'tgl_lahir' => $this->input->post('tgl_lahir', TRUE),
                'alamat' => $this->input->post('alamat', TRUE),
                'desa' => $this->input->post('desa', TRUE),
                'kecamatan' => $this->input->post('kecamatan', TRUE),
                'kabupaten' => $this->input->post('kabupaten', TRUE),
                'propinsi' => $this->input->post('propinsi', TRUE),
                'jeniskelamin' => $this->input->post('jeniskelamin', TRUE),
                'status' => $this->input->post('status', TRUE),
                'no_hp' => $this->input->post('no_hp', TRUE),
                'npwp' => $this->input->post('npwp', TRUE),
                'kodepasar' => $kodepasar,
            );

            $this->Tbl_identitas_pengguna_model->insert($data);






            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('index.php/tbl_identitas_pengguna'));
        }
    }

    public function update($id)
    {

        if ($this->General_login->check_login() == 'false') {
            header("location:" . base_url());
        }

        $cek = $this->session->userdata('company');

        if ($cek == 'admin') {
            $row = $this->Tbl_identitas_pengguna_model->get_by_id_pengguna($id);
        } elseif ($cek == 'adminpasar') {
            $row = $this->Tbl_identitas_pengguna_model->get_by_id_pengguna($id);
        } elseif ($cek == 'pasar') {
            $kodepasar = $this->session->userdata('kodepasar');
            $row = $this->Tbl_identitas_pengguna_model->get_by_id_pengguna($id);
        } else {
            header("location:" . base_url());
        }


        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('index.php/tbl_identitas_pengguna/update_action/' . $id),
                'id' => set_value('id', $row->id),
                'nik' => set_value('nik', $row->nik),
                'idpedagang' => set_value('idpedagang', $row->idpedagang),
                'nama' => strip_tags(set_value('nama', $row->nama)),
                'tempat_lahir' => set_value('tempat_lahir', $row->tempat_lahir),
                'tgl_lahir' => set_value('tgl_lahir', $row->tgl_lahir),
                'alamat' => set_value('alamat', $row->alamat),
                'desa' => set_value('desa', $row->desa),
                'kecamatan' => set_value('kecamatan', $row->kecamatan),
                'kabupaten' => set_value('kabupaten', $row->kabupaten),
                'propinsi' => set_value('propinsi', $row->propinsi),
                'jeniskelamin' => set_value('jeniskelamin', $row->jeniskelamin),
                'status' => set_value('status', $row->status),
                'no_hp' => set_value('no_hp', $row->no_hp),
                'npwp' => set_value('npwp', $row->npwp),
                'username'  => $this->session->userdata('username'),
                'company'    => $this->session->userdata('company'),
            );
            $this->template->load('pasar/template_pasar', 'tbl_identitas_pengguna_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('index.php/tbl_identitas_pengguna'));
        }
    }

    public function update_action($id)
    {

        if ($this->General_login->check_login() == 'false') {
            header("location:" . base_url());
        }


        $cek = $this->session->userdata('company');

        if ($cek == 'admin') {
            $row = $this->Tbl_identitas_pengguna_model->get_by_id_pengguna($id);
        } elseif ($cek == 'adminpasar') {
            $row = $this->Tbl_identitas_pengguna_model->get_by_id_pengguna($id);
        } elseif ($cek == 'pasar') {
            $kodepasar = $this->session->userdata('kodepasar');
            $row = $this->Tbl_identitas_pengguna_model->get_by_id_pengguna($id);
        } else {
            header("location:" . base_url());
        }


        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {


            // PROSES UPLOAD PHOTO
            $config['upload_path']          = './images_pedagang/';
            $config['allowed_types']        = 'gif|jpg|png';
            $config['max_size']             = 100;
            $config['max_width']            = 1024;
            $config['max_height']           = 768;
            $config['file_name']            = $this->input->post('nik', TRUE) . '.jpg';
            $config['overwrite']            = true;
            $this->load->library('upload', $config);
            $data = array('upload_data' => $this->upload->data());
            // END PROSES UPLOAD PHOTO


            $data = array(
                'nik' => $this->input->post('nik', TRUE),
                'idpedagang' => $this->input->post('idpedagang', TRUE),
                'nama' => addslashes($this->input->post('nama', TRUE)),
                'alamat' => $this->input->post('alamat', TRUE),
                'desa' => $this->input->post('desa', TRUE),
                'kecamatan' => $this->input->post('kecamatan', TRUE),
                'kabupaten' => $this->input->post('kabupaten', TRUE),
                'propinsi' => $this->input->post('propinsi', TRUE),
                'jeniskelamin' => $this->input->post('jeniskelamin', TRUE),
                'status' => $this->input->post('status', TRUE),
                'no_hp' => $this->input->post('no_hp', TRUE),
                'npwp' => $this->input->post('npwp', TRUE),
                'tempat_lahir' => $this->input->post('tempat_lahir', TRUE),
                'tgl_lahir' => $this->input->post('tgl_lahir', TRUE),
            );

            $this->Tbl_identitas_pengguna_model->update($id, $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('index.php/tbl_identitas_pengguna'));
        }
    }

    public function delete($id)
    {

        if ($this->General_login->check_login() == 'false') {
            header("location:" . base_url());
        }

        $row = $this->Tbl_identitas_pengguna_model->get_by_id($id);

        if ($row) {
            $this->Tbl_identitas_pengguna_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('index.php/tbl_identitas_pengguna'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('index.php/tbl_identitas_pengguna'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('nik', 'nik', 'trim|required');
        //$this->form_validation->set_rules('idpedagang', 'idpedagang', 'trim|required');
        $this->form_validation->set_rules('nama', 'nama', 'trim|required');
        /* 	$this->form_validation->set_rules('alamat', 'alamat', 'trim|required');
	$this->form_validation->set_rules('jeniskelamin', 'jeniskelamin', 'trim|required');
	$this->form_validation->set_rules('status', 'status', 'trim|required');
	$this->form_validation->set_rules('no_hp', 'no hp', 'trim|required');
 */
        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {

        if ($this->General_login->check_login() == 'false') {
            header("location:" . base_url());
        }

        $this->load->helper('exportexcel');
        $namaFile = "tbl_identitas_pengguna.xls";
        $judul = "tbl_identitas_pengguna";
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
        xlsWriteLabel($tablehead, $kolomhead++, "Nik");
        xlsWriteLabel($tablehead, $kolomhead++, "Idpedagang");
        xlsWriteLabel($tablehead, $kolomhead++, "Nama");
        xlsWriteLabel($tablehead, $kolomhead++, "Alamat");
        xlsWriteLabel($tablehead, $kolomhead++, "Jeniskelamin");
        xlsWriteLabel($tablehead, $kolomhead++, "Status");
        xlsWriteLabel($tablehead, $kolomhead++, "No Hp");

        foreach ($this->Tbl_identitas_pengguna_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteNumber($tablebody, $kolombody++, $data->nik);
            xlsWriteLabel($tablebody, $kolombody++, $data->idpedagang);
            xlsWriteLabel($tablebody, $kolombody++, $data->nama);
            xlsWriteLabel($tablebody, $kolombody++, $data->alamat);
            xlsWriteLabel($tablebody, $kolombody++, $data->jeniskelamin);
            xlsWriteLabel($tablebody, $kolombody++, $data->status);
            xlsWriteLabel($tablebody, $kolombody++, $data->no_hp);

            $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

    public function word()
    {

        if ($this->General_login->check_login() == 'false') {
            header("location:" . base_url());
        }

        header("Content-type: application/vnd.ms-word");
        header("Content-Disposition: attachment;Filename=tbl_identitas_pengguna.doc");

        $data = array(
            'tbl_identitas_pengguna_data' => $this->Tbl_identitas_pengguna_model->get_all(),
            'start' => 0
        );

        $this->load->view('tbl_identitas_pengguna_doc', $data);
    }


    //tombol pdf
    public function pdf()
    {

        // if ($this->General_login->check_login() == 'false') {
        // header("location:" . base_url());
        // }

        $this->load->library('PdfGenerator');

        $nikchecck = "3402014302630001";
        $data = array(
            'tbl_identitas_pengguna_data' => $this->Tbl_identitas_pengguna_model->get_by_nik_pengguna($nikchecck),

        );

        $html = $this->load->view('tbl_identitas_pengguna_pdf', $data, true);

        $this->pdf->loadHtml($html);
        $this->pdf->render();
        $id_pengguna = "tbl_identitas_pengguna_pdf";
        $this->pdf->stream("" . $id_pengguna . ".pdf", array("Attachment" => 0));
    }

    //end tombol pdf




    public function upload_poto_pedagang($nik)
    {

        if ($this->General_login->check_login() == 'false') {
            header("location:" . base_url());
        }

        $config['upload_path']          = './images_pedagang/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 100;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;
        $config['file_name']            = $nik . '.jpg';
        $config['overwrite']            = true;

        $this->load->library('upload', $config);


        if (!$this->upload->do_upload('userfile')) {
            $error = array('error' => $this->upload->display_errors());
            //$this->load->view('index.php/form_data/profile_riwayat_pendidikan/'.$nip, $error);
            redirect('Form_data/' . $alamat . '/' . $nip);
        } else {
            $data = array('upload_data' => $this->upload->data());
            //$formx='profile_riwayat_pendidikan';
            redirect('Form_data/' . $alamat . '/' . $nip);
            //  $this->load->view('layout_simpeg/header_simpeg');
            //  $this->load->view('form_data/'.$formx.'/'.$nip,$data);
            //  $this->load->view('layout_simpeg/footer_simpeg');                        
        }
    }

    public function create_nikXXX()
    {

        if ($this->General_login->check_login() == 'false') {
            header("location:" . base_url());
        }

        $cek = $this->session->userdata('company');

        if ($cek == 'adminpasar') {
            $perijinan_pendaftaran = $this->Perijinan_pendaftaran_model->get_all();
            //$namaperijinan = $this->Perijinan_namaperijinan_model->get_all_row();  
            $data = array(
                'button' => 'Create',
                //'action' => site_url('index.php/perijinan_pendaftaran/create_action_nik'),
                'action_nik' => site_url('index.php/perijinan_pendaftaran/read_nik'),
                'id' => set_value('id'),
                'nik' => set_value('nik'),
                'nama' => set_value('nama'),
                'tempat_lahir' => set_value('tempat_lahir'),
                'tgl_lahir' => set_value('tgl_lahir'),
                'jenis_kelamin' => set_value('jenis_kelamin'),
                'alamat' => set_value('alamat'),
                'agama' => set_value('agama'),
                'statusperkawinan' => set_value('statusperkawinan'),
                'pekerjaan' => set_value('pekerjaan'),
                'kewarganegaraan' => set_value('kewarganegaraan'),
                'no_tlp' => set_value('no_tlp'),
                'id_perijinan' => set_value('id_perijinan'),
                //'nama_perijinan' =>  $namaperijinan,

                //'datamenu'  => $this->Perijinan_model->get_menu(),
                //'databidang'  => $this->Perijinan_bidang_model->get_all_asc(),

                'perijinan_pendaftaran_data' => $perijinan_pendaftaran,
                'username'  => $this->session->userdata('username'),
                'company'    => $this->session->userdata('company'),
            );
            // $this->template->load('template','perijinan_pendaftaran_form', $data);
            // $this->template->load('template','perijinan_pendaftaran_proses_form', $data);
            $this->template->load('template', 'pasar/pendaftaran_nik', $data);
        } else {
            header("location:" . base_url());
        }
    }


    public function read_nikXXX()
    {

        if ($this->General_login->check_login() == 'false') {
            header("location:" . base_url());
        }

        $cek = $this->session->userdata('company');
        if ($cek == 'adminpasar') {
            $detailnik = $this->Perijinan_pendaftaran_model->get_penduduk($this->input->post('nik'));
            if ($detailnik == "kosong") {
                $detailnik = "";
            }
            // $perijinan_pendaftaran = $this->Perijinan_pendaftaran_model->get_all();
            // $namaperijinan = $this->Perijinan_namaperijinan_model->get_all_row();  
            $data = array(
                'button' => 'Create',
                //'action' => site_url('index.php/perijinan_pendaftaran/create_action_nik'),
                'action_nik' => site_url('index.php/perijinan_pendaftaran/read_nik'),
                'id' => set_value('id'),
                'nik' => set_value('nik'),
                'nama' => set_value('nama'),
                'tempat_lahir' => set_value('tempat_lahir'),
                'tgl_lahir' => set_value('tgl_lahir'),
                'jenis_kelamin' => set_value('jenis_kelamin'),
                'alamat' => set_value('alamat'),
                'agama' => set_value('agama'),
                'statusperkawinan' => set_value('statusperkawinan'),
                'pekerjaan' => set_value('pekerjaan'),
                'kewarganegaraan' => set_value('kewarganegaraan'),
                'no_tlp' => set_value('no_tlp'),
                'id_perijinan' => set_value('id_perijinan'),
                //'nama_perijinan' =>  $this->Perijinan_namaperijinan_model->get_all_row(),

                //'datamenu'  => $this->Perijinan_model->get_menu(),
                //'databidang'  => $this->Perijinan_bidang_model->get_all_asc(),

                //'perijinan_pendaftaran_data' => $this->Perijinan_pendaftaran_model->get_all(),

                'detailnik' => $detailnik,

                'username'  => $this->session->userdata('username'),
                'company'    => $this->session->userdata('company'),
            );


            $sess_detailnik = array(
                'sess_detailnik' => $detailnik,
            );


            $this->session->set_userdata($sess_detailnik);

            // redirect(site_url('index.php/perijinan_pendaftaran/create_nik'));
            $this->template->load('template', 'pasar/pendaftaran_nik', $data);
        } else {
            header("location:" . base_url());
        }
    }

    public function create_nik()
    {

        if ($this->General_login->check_login() == 'false') {
            header("location:" . base_url());
        }

        $cek = $this->session->userdata('company');

        if ($cek == 'adminpasar') {
            $perijinan_pendaftaran = $this->Perijinan_pendaftaran_model->get_all();
            //$namaperijinan = $this->Perijinan_namaperijinan_model->get_all_row();  
            $data = array(
                'button' => 'Create',
                //'action' => site_url('index.php/perijinan_pendaftaran/create_action_nik'),
                'action_nik' => site_url('index.php/Tbl_identitas_pengguna/read_nik'),
                'id' => set_value('id'),
                'nik' => set_value('nik'),
                'nama' => set_value('nama'),
                'tempat_lahir' => set_value('tempat_lahir'),
                'tgl_lahir' => set_value('tgl_lahir'),
                'jenis_kelamin' => set_value('jenis_kelamin'),
                'alamat' => set_value('alamat'),
                'agama' => set_value('agama'),
                'statusperkawinan' => set_value('statusperkawinan'),
                'pekerjaan' => set_value('pekerjaan'),
                'kewarganegaraan' => set_value('kewarganegaraan'),
                'no_tlp' => set_value('no_tlp'),
                'id_perijinan' => set_value('id_perijinan'),
                //'nama_perijinan' =>  $namaperijinan,

                //'datamenu'  => $this->Perijinan_model->get_menu(),
                //'databidang'  => $this->Perijinan_bidang_model->get_all_asc(),

                'perijinan_pendaftaran_data' => $perijinan_pendaftaran,
                'username'  => $this->session->userdata('username'),
                'company'    => $this->session->userdata('company'),
            );
            // $this->template->load('template','perijinan_pendaftaran_form', $data);
            // $this->template->load('template','perijinan_pendaftaran_proses_form', $data);
            $this->template->load('template', 'pasar/pendaftaran_nik_pasar', $data);
        } else {
            header("location:" . base_url());
        }
    }


    public function read_nik()
    {

        if ($this->General_login->check_login() == 'false') {
            header("location:" . base_url());
        }

        $cek = $this->session->userdata('company');



        if ($cek == 'adminpasar') {
            $detailnik = $this->Perijinan_pendaftaran_model->get_penduduk($this->input->post('nik'));




            if ($detailnik == "kosong") {
                $detailnik = "";
            }
            // $perijinan_pendaftaran = $this->Perijinan_pendaftaran_model->get_all();
            // $namaperijinan = $this->Perijinan_namaperijinan_model->get_all_row();  
            $data = array(
                'button' => 'Create',
                //'action' => site_url('index.php/perijinan_pendaftaran/create_action_nik'),
                'action_nik' => site_url('index.php/Tbl_identitas_pengguna/read_nik'),
                'id' => set_value('id'),
                'nik' => set_value('nik'),
                'nama' => set_value('nama'),
                'tempat_lahir' => set_value('tempat_lahir'),
                'tgl_lahir' => set_value('tgl_lahir'),
                'jenis_kelamin' => set_value('jenis_kelamin'),
                'alamat' => set_value('alamat'),
                'agama' => set_value('agama'),
                'statusperkawinan' => set_value('statusperkawinan'),
                'pekerjaan' => set_value('pekerjaan'),
                'kewarganegaraan' => set_value('kewarganegaraan'),
                'no_tlp' => set_value('no_tlp'),
                //'id_perijinan' => set_value('id_perijinan'),
                //'nama_perijinan' =>  $this->Perijinan_namaperijinan_model->get_all_row(),

                //'datamenu'  => $this->Perijinan_model->get_menu(),
                //'databidang'  => $this->Perijinan_bidang_model->get_all_asc(),

                //'perijinan_pendaftaran_data' => $this->Perijinan_pendaftaran_model->get_all(),

                'detailnik' => $detailnik,

                'username'  => $this->session->userdata('username'),
                'company'    => $this->session->userdata('company'),
            );


            $sess_detailnik = array(
                'sess_detailnik' => $detailnik,
            );


            $this->session->set_userdata($sess_detailnik);

            // redirect(site_url('index.php/perijinan_pendaftaran/create_nik'));
            $this->template->load('template', 'pasar/pendaftaran_nik_pasar', $data);
        } else {
            header("location:" . base_url());
        }
    }
}

/* End of file Tbl_identitas_pengguna.php */
/* Location: ./application/controllers/Tbl_identitas_pengguna.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2018-09-28 08:33:26 */
/* http://harviacode.com */