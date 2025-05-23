<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Kirimwa extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model(array('User_model','KirimWa_GET_DATA_REQ_model'));
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    public function index()
    {
        echo "on process";
        // $this->template->load('template', 'user/tbl_user_list');
    }

    public function get_data(){
        // print_r("Kirimwa get_data");
        // die;
        // header('Content-Type: application/json; charset=utf-8');

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $device = $data['device'];
        $sender = $data['sender'];
        $message = $data['message'];
        $text= $data['text']; //button text
        $member= $data['member']; //group member who send the message
        $name = $data['name'];
        $location = $data['location'];
        $pollname= $data['pollname'];
        $choices= $data['choices'];
        
        //data below will only received by device with all feature package
        //start
        $url =  $data['url'];
        $filename =  $data['filename'];
        $extension=  $data['extension'];
        //end
        
        function sendFonnte($target, $data) {
            $curl = curl_init();
        
            curl_setopt_array($curl, array(
              CURLOPT_URL => "https://api.fonnte.com/send",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => array(
                    'target' => $target,
                    'message' => $data['message'],
                    'url' => $data['url'],
                    'filename' => $data['filename'],
                ),
              CURLOPT_HTTPHEADER => array(
                "Authorization: 1BFYxDnYcsZjm9nahfEG"
              ),
            ));
        
            $response = curl_exec($curl);
        
            curl_close($curl);
        
            return $response;
        }
        
        if ( $message == "test" ) {
            $reply = [
                "message" => "working great! from pilarpustakagroup.com",
            ];
        } elseif ( $message == "image" ) {
            $reply = [
                "message" => "image message",
                "url" => "https://filesamples.com/samples/image/jpg/sample_640%C3%97426.jpg",
            ];
        } elseif ( $message == "audio" ) {
            $reply = [
                    "message" => "audio message",
                "url" => "https://filesamples.com/samples/audio/mp3/sample3.mp3",
                "filename" => "music",
            ];
        } elseif ( $message == "video" ) {
            $reply = [
                "message" => "video message",
                "url" => "https://filesamples.com/samples/video/mp4/sample_640x360.mp4",
            ];
        } elseif ( $message == "file" ) {
            $reply = [
                "message" => "file message",
                "url" => "https://filesamples.com/samples/document/docx/sample3.docx",
                "filename" => "document",
            ];
        } else {
            $reply = [
                "message" => "Sorry, i don't understand. Please use one of the following keyword :
                    
        Test
        Audio
        Video
        Image
        File",
        ];
        }
        
        sendFonnte($sender, $reply);
    }

    public function kirimwa($nomorwa = null, $pesantext = null, $redirect_uri_from = null)
    {

        if (!($nomorwa)) {
            $nomorwa = "628157045860";
        }
        if (!($pesantext)) {
            $pesantext = "ada akses masuk";
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://fonnte.com/api/send_message.php",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array('phone' => $nomorwa, 'type' => 'text', 'text' => $pesantext, 'delay' => '1', 'delay_req' => '1', 'schedule' => '0'),
            CURLOPT_HTTPHEADER => array(
                "Authorization: K1mzoiD4FwEVub8duMKY"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }


    public function json()
    {
        header('Content-Type: application/json');
        echo $this->User_model->json();
    }

    public function read($id)
    {
        $row = $this->User_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id_users'      => $row->id_users,
                'full_name'     => $row->full_name,
                'email'         => $row->email,
                'password'      => $row->password,
                'images'        => $row->images,
                'id_user_level' => $row->id_user_level,
                'is_aktif'      => $row->is_aktif,
            );
            $this->template->load('template', 'user/tbl_user_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('user'));
        }
    }

    public function create()
    {
        $data = array(
            'button'        => 'Create',
            'action'        => site_url('user/create_action'),
            'id_users'      => set_value('id_users'),
            'full_name'     => set_value('full_name'),
            'email'         => set_value('email'),
            'password'      => set_value('password'),
            'images'        => set_value('images'),
            'id_user_level' => set_value('id_user_level'),
            'is_aktif'      => set_value('is_aktif'),
        );
        $this->template->load('template', 'user/tbl_user_form', $data);
    }


    public function create_action()
    {
        $this->_rules();
        $foto = $this->upload_foto();
        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $password       = $this->input->post('password', TRUE);
            $options        = array("cost" => 4);
            $hashPassword   = password_hash($password, PASSWORD_BCRYPT, $options);

            $data = array(
                'full_name'     => $this->input->post('full_name', TRUE),
                'email'         => $this->input->post('email', TRUE),
                'password'      => $hashPassword,
                'images'        => $foto['file_name'],
                'id_user_level' => $this->input->post('id_user_level', TRUE),
                'is_aktif'      => $this->input->post('is_aktif', TRUE),
            );

            $this->User_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('user'));
        }
    }

    public function update($id)
    {
        $row = $this->User_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button'        => 'Update',
                'action'        => site_url('user/update_action'),
                'id_users'      => set_value('id_users', $row->id_users),
                'full_name'     => set_value('full_name', $row->full_name),
                'email'         => set_value('email', $row->email),
                'password'      => set_value('password', $row->password),
                'images'        => set_value('images', $row->images),
                'id_user_level' => set_value('id_user_level', $row->id_user_level),
                'is_aktif'      => set_value('is_aktif', $row->is_aktif),
            );
            $this->template->load('template', 'user/tbl_user_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('user'));
        }
    }

    public function update_action()
    {
        $this->_rules();
        $foto = $this->upload_foto();
        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_users', TRUE));
        } else {
            if ($foto['file_name'] == '') {
                $data = array(
                    'full_name'     => $this->input->post('full_name', TRUE),
                    'email'         => $this->input->post('email', TRUE),
                    'id_user_level' => $this->input->post('id_user_level', TRUE),
                    'is_aktif'      => $this->input->post('is_aktif', TRUE)
                );
            } else {
                $data = array(
                    'full_name'     => $this->input->post('full_name', TRUE),
                    'email'         => $this->input->post('email', TRUE),
                    'images'        => $foto['file_name'],
                    'id_user_level' => $this->input->post('id_user_level', TRUE),
                    'is_aktif'      => $this->input->post('is_aktif', TRUE)
                );

                // ubah foto profil yang aktif
                $this->session->set_userdata('images', $foto['file_name']);
            }

            $this->User_model->update($this->input->post('id_users', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('user'));
        }
    }


    function upload_foto()
    {
        $config['upload_path']          = './assets/foto_profil';
        $config['allowed_types']        = 'gif|jpg|png';
        //$config['max_size']             = 100;
        //$config['max_width']            = 1024;
        //$config['max_height']           = 768;
        $this->load->library('upload', $config);
        $this->upload->do_upload('images');
        return $this->upload->data();
    }

    public function delete($id)
    {
        $row = $this->User_model->get_by_id($id);

        if ($row) {
            $this->User_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('user'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('user'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('full_name', 'full name', 'trim|required');
        $this->form_validation->set_rules('email', 'email', 'trim|required');
        //$this->form_validation->set_rules('password', 'password', 'trim|required');
        //$this->form_validation->set_rules('images', 'images', 'trim|required');
        $this->form_validation->set_rules('id_user_level', 'id user level', 'trim|required');
        $this->form_validation->set_rules('is_aktif', 'is aktif', 'trim|required');

        $this->form_validation->set_rules('id_users', 'id_users', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "tbl_user.xls";
        $judul = "tbl_user";
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
        xlsWriteLabel($tablehead, $kolomhead++, "Full Name");
        xlsWriteLabel($tablehead, $kolomhead++, "Email");
        xlsWriteLabel($tablehead, $kolomhead++, "Password");
        xlsWriteLabel($tablehead, $kolomhead++, "Images");
        xlsWriteLabel($tablehead, $kolomhead++, "Id User Level");
        xlsWriteLabel($tablehead, $kolomhead++, "Is Aktif");

        foreach ($this->User_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->full_name);
            xlsWriteLabel($tablebody, $kolombody++, $data->email);
            xlsWriteLabel($tablebody, $kolombody++, $data->password);
            xlsWriteLabel($tablebody, $kolombody++, $data->images);
            xlsWriteNumber($tablebody, $kolombody++, $data->id_user_level);
            xlsWriteLabel($tablebody, $kolombody++, $data->is_aktif);

            $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

    public function word()
    {
        header("Content-type: application/vnd.ms-word");
        header("Content-Disposition: attachment;Filename=tbl_user.doc");

        $data = array(
            'tbl_user_data' => $this->User_model->get_all(),
            'start' => 0
        );

        $this->load->view('user/tbl_user_doc', $data);
    }

    function profile()
    {
    }


    function cekdata($uuid_sales_selected=null){
        $pesan_balik = $this->KirimWa_GET_DATA_REQ_model->testdata($uuid_sales_selected);
        print_r($pesan_balik);
        print_r("<br/>");
        print_r("<br/>");
        print_r("<br/>");

        
        $pesan_balik = $this->KirimWa_GET_DATA_REQ_model->penjualan_sales($uuid_sales_selected);
        print_r($pesan_balik);
        print_r("<br/>");
        print_r("<br/>");
        print_r("<br/>");
    
    }


    function penjualan($uuid_sales_selected=null){
        $pesan_balik = $this->KirimWa_GET_DATA_REQ_model->penjualan_sales($uuid_sales_selected);
        print_r($pesan_balik);
    }

}
