<?php
class Auth extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        // is_login();
        $this->load->model(array('KirimWa_model','Tbl_user_model'));
        // $this->load->library('form_validation');
    }

    function index()
    {
        $this->load->view('auth/login');
    }

    // 2021 okt 18
    // Tiga level pengguna sistem: 
    // 1. Owner dan administrator: all of sistem
    // 2. Manager : semua, kecuali yang tidak bisa diakses: 
    // -Tagihan
    // -Data Penjualan
    // -Pembayaran
    // -Refresh Tahun dan Semester
    // -Produk
    // -Cover

    // 3. User: hanya bisa input: 
    // - Pemesanan
    // - Penjualan
    // - Gudang

    function cheklogin()
    {



        $email      = $this->input->post('email');
        //$password   = $this->input->post('password');
        $password = $this->input->post('password', TRUE);
        $hashPass = password_hash($password, PASSWORD_DEFAULT);
        $test     = password_verify($password, $hashPass);

        // query chek users
        $this->db->where('email', $email);
        //$this->db->where('password',  $test);
        $users = $this->db->get('tbl_user');

        if ($users->num_rows() > 0) {

            $user = $users->row_array();



            if (password_verify($password, $user['password'])) {
                // retrive user data to session
                $this->session->set_userdata($user);
                $sess = array(
                    'sess_username'        => $user['full_name'],
                    'sess_iduser'        => $user['id_users'],
                    'sess_id_user_level'        => $user['id_user_level'],
                    'sess_email_user'        => $user['email'],
                    'sess_no_hp'        => $user['no_hp'],
                    // 'sess_status_tagihan'        => $user['status_tagihan'],
                );
                $this->session->set_userdata($sess);
               
                $get_client = $this->get_client_ip();
                $get_client_browser = $this->get_client_browser();
                $date_login = date('Y-m-d H:i:s');
                $pesan = "pilarpustakagroup.com ,  Hai " . $user['full_name']  . " / " . $get_client . " / " . $get_client_browser . " = " . $date_login . " Terimakasih Sudah Login";
                $nomorhp = "08157045860";
                $this->KirimWa_model->kirimwa($nomorhp, $pesan);

                if ($this->session->userdata('sess_id_user_level') == '1') { //admin
                    redirect('Dashboard');
                } elseif ($this->session->userdata('sess_id_user_level') == '99') { //administrator
                    redirect('Dashboard');
                } elseif ($this->session->userdata('sess_id_user_level') == '2') { //manager
                    redirect('Dashboard');
                } elseif ($this->session->userdata('sess_id_user_level') == '7') { //kasir
                    redirect('Dashboard');
                } elseif ($this->session->userdata('sess_id_user_level') == '3') { //sales
                    redirect('Dashboard');
                } elseif ($this->session->userdata('sess_id_user_level') == '4') { //customer
                    redirect('Dashboard');
                } else {
                    // header("location:" . base_url());
                    redirect('auth');
                }
            } else {
                redirect('auth');
            }
        } else {

                $X_data="TIDAK_ADA_DATA";

                //CEK DI tbl_user_lupapassword
                $this->db->where('email', $email);
                $users = $this->db->get('tbl_user_lupapassword');

                if ($users->num_rows() > 0) {
                    $user = $users->row_array();
                    $X_data="ADA";
                }

                // $this->db->where('no_hp', $email);
                // $users = $this->db->get('tbl_user_lupapassword');

                

                $sql = "select * from tbl_user_lupapassword order by  date_input desc limit 1";

                if ($this->db->query($sql)->num_rows() > 0) {
                    $user = $this->db->query($sql)->row_array();
                    $X_data="ADA";
                }
                
                if($X_data == "ADA"){

                    // //Cek waktu: date_input, apakah kurang dari 5 menit.

                    $date = $user['date_input'];
                    $currentDate = strtotime($date);
                    $futureDate = $currentDate+(60*5);
                    $KadaluarsaDate = date("Y-m-d H:i:s", $futureDate);

                    if(date('Y-m-d H:i:s') <= $KadaluarsaDate){

                        if (password_verify($password, $user['password'])) {
                            
                             //GET DATA USER DARI tbl_user
                             $this->db->where('id_user_level', $user['id_user_level']);
                             $this->db->where('email', $user['email']);
                             $users = $this->db->get('tbl_user');                            
 
                             $user = $users->row_array();

                            // retrive user data to session
                            $this->session->set_userdata($user);

                           
                            // print_r($users_tbl_user->row()->id_users);
                            // die;

                            $sess = array(
                                'sess_username'        => $user['full_name'],
                                'sess_iduser'        => $user['id_users'],
                                'sess_id_user_level'        => $user['id_user_level'],
                                'sess_email_user'        => $user['email'],
                                'sess_no_hp'        => $user['no_hp'],
                                // 'sess_status_tagihan'        => $user['status_tagihan'],
                            );
                            $this->session->set_userdata($sess);
            
            
                            // print_r($sess);
                            // print_r("<br/>");
                            // print_r("<br/>");
                            // die;
            
                            $get_client = $this->get_client_ip();
                            $get_client_browser = $this->get_client_browser();
                            $date_login = date('Y-m-d H:i:s');
                            $pesan = "pilarpustakagroup.com [LOGIN DARURAT],  Hai " . $user['full_name']  . " / " . $get_client . " / " . $get_client_browser . " = " . $date_login . " Terimakasih Sudah Login";
                            $nomorhp = "08157045860";
                            $this->KirimWa_model->kirimwa($nomorhp, $pesan);
            
                            // print_r($this->session->userdata('sess_id_user_level'));
                            // die;

                            if ($this->session->userdata('sess_id_user_level') == '1') { //admin
                                redirect('Dashboard');
                            } elseif ($this->session->userdata('sess_id_user_level') == '99') { //administrator
                                redirect('Dashboard');
                            } elseif ($this->session->userdata('sess_id_user_level') == '2') { //manager
                                redirect('Dashboard');
                            } elseif ($this->session->userdata('sess_id_user_level') == '7') { //kasir
                                redirect('Dashboard');
                            } elseif ($this->session->userdata('sess_id_user_level') == '3') { //sales
                                redirect('Dashboard');
                            } elseif ($this->session->userdata('sess_id_user_level') == '4') { //customer
                                redirect('Dashboard');
                            } else {
                                // header("location:" . base_url());
                                redirect('auth');
                            }
                        } else {
                            // print_r("tidak lolos password");
                            // die;
                            redirect('auth');
                        }




                    }else{

                        $this->session->set_flashdata('status_login', 'email atau password yang anda input salah');
                        redirect('auth');
                    }


                }else{
                            
                    $this->session->set_flashdata('status_login', 'email atau password yang anda input salah');
                    redirect('auth');
                }





        }
    }

    function forgotpassword(){
        $this->load->view('auth/lupapassword');
    }

    function sendwhatsappnumber(){

       

        $whatsappnumber      = $this->input->post('whatsappnumber');

        $a = $this->random_str(32);
        $b = $this->random_str(5, '1234567890');
        $c = $this->random_str();
        // var_dump($a, $b, $c);

        //cek nomor apakah ada di tabel user, jika tidak ada kirim pesan informasi terimakasih sudah menghubungi
        // untuk yang ada nomor, dikirimkan 5 digit ke nomor dan berlaku selama 5 menit untuk request yang terakhir

        $this->db->where('no_hp', $whatsappnumber);
        $users = $this->db->get('tbl_user');  
        
        $date_request = date('Y-m-d H:i:s');

        if ($users->num_rows() > 0) {

            $user = $users->row_array(); // ada nomor wa di tbl_user

            //convert 5 code angkan menjadi hash dan simpan ke tbl_user_lupapassword
            $data = array(
                'date_input' => $date_request,
                'email' => $user['email'],
                'id_user_level' => $user['id_user_level'],
                // 'id_users' => $user['id_users'],
                'no_hp' => $user['no_hp'],
                'password' => password_hash($b, PASSWORD_DEFAULT),
            );
    
            $this->Tbl_user_model->insert_tbl_user_lupapassword($data);

            //kirim kode $user['no_hp'] ==> ke nomor whatsapp $user['no_hp']
            $get_client = $this->get_client_ip();
            $get_client_browser = $this->get_client_browser();
            $date_login = $date_request;
            $pesan = "pilarpustakagroup.com ,  \r\n Hai " . $user['full_name']  . " \r\n\r\n *SEND LUPA PASSWORD* : \r\n *username:* " . $whatsappnumber . " \r\n *password:* " . $b . " \r\n dari: " . $get_client . " \r\n browser: " . $get_client_browser . " \r\n tgl: " . $date_login . " \r\n\r\n Terimakasih Sudah Request Password";
            $nomorhp = "08157045860";
            $this->KirimWa_model->kirimwa($nomorhp, $pesan);
            
            $pesan = " *Thanks for visiting.* \r\n\r\n silahkan login: \r\n *user* = " . $whatsappnumber . " \r\n *Password* = " .  $b . " \r\n\r\n *Password ini hanya berlaku selama 5 menit* \r\n\r\n berlaku mulai " . $date_login . " \r\n\r\n *NOTE:* \r\n Setelah masuk ke aplikasi, silahkan buka menu *UBAH PASSWORD* dan isikan *password baru* dan klik *Simpan* \r\n\r\n *Selanjutnya* anda bisa login dengan password yang baru dibuat.";
            $nomorhp = $whatsappnumber;
            $this->KirimWa_model->kirimwa($nomorhp, $pesan);



            // print_r("Berhasil insert");
            // alert("Berhasil kirim pesan");
            //$message = '<div class="alert alert-success" role="alert">Success</div>';

            echo "<script>alert('Thanks for visiting whatsapp forgotten password');</script>";
            

            // Lompat ke controller AUTH ==> login Form
            $this->load->view('auth/login');

        }else{
                       //convert 5 code angkan menjadi hash dan simpan ke tbl_user_lupapassword
                       $data = array(
                        'date_input' => $date_request,
                        // 'email' => $user['email'],
                        'no_hp' => $whatsappnumber,
                        'password' => "tidak ada nomor hp di sistem",
                    );
            
                    $this->Tbl_user_model->insert_tbl_user_lupapassword($data);
        
                    //kirim kode $user['no_hp'] ==> ke nomor whatsapp $user['no_hp']
                    $get_client = $this->get_client_ip();
                    $get_client_browser = $this->get_client_browser();
                    $date_login = date('Y-m-d H:i:s');
                    $pesan = "pilarpustakagroup.com ,  \r\n SEND LUPA PASSWORD: *TIDAK ADA NOMOR HP DI SISTEM* : " . $whatsappnumber . "\r\n\r\n dari: " . $get_client . " \r\n browser: " . $get_client_browser . " \r\n pada: " . $date_request . " \r\n\r\n Terimakasih Sudah Request Password";
                    $nomorhp = "08157045860";
                    $this->KirimWa_model->kirimwa($nomorhp, $pesan);
                    
                    $pesan = "Thanks for visiting. \r\n\r\n Cheers";
                    $nomorhp = $whatsappnumber;
                    $this->KirimWa_model->kirimwa($nomorhp, $pesan);
        
        
        
                    // print_r("Berhasil insert");
                    // alert("Berhasil kirim pesan");
                    //$message = '<div class="alert alert-success" role="alert">Success</div>';
        
                    echo "<script>alert('Thanks for visiting whatsapp forgotten password');</script>";
                    
        
                    // Lompat ke controller AUTH ==> login Form
                    $this->load->view('auth/login');
        }

    }


    function random_str(
        int $length = 64,
        string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ): string {
        if ($length < 1) {
            throw new \RangeException("Length must be a positive integer");
        }
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces []= $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }
    


    function logout()
    {
        $this->session->sess_destroy();
        $this->session->set_flashdata('status_login', 'Anda sudah berhasil keluar dari aplikasi');
        redirect('auth');
    }

    // function cekIP(){

    // Mengetahui IP Pengunjung
    function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'IP tidak dikenali';
        return $ipaddress;
    }


    // Mengetahui web browser yang digunakan pengunjung
    function get_client_browser()
    {
        $browser = '';
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'Netscape'))
            $browser = 'Netscape';
        else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox'))
            $browser = 'Firefox';
        else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome'))
            $browser = 'Chrome';
        else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera'))
            $browser = 'Opera';
        else if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE'))
            $browser = 'Internet Explorer';
        else
            $browser = 'Other';
        return $browser;
    }
    //    echo "IP anda adalah : ". get_client_ip()."<br>";
    //    echo "Browser : ".get_client_browser()."<br>";
    //    echo "Sistem Operasi : ".$_SERVER['HTTP_USER_AGENT'];

    // }


}
