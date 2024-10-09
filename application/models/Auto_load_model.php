<?php

class Auto_load_model extends CI_Model
{


      function cek_tingkat_by_user(){
            $this->db->where('id_users', $this->session->userdata('sess_iduser'));
            $users = $this->db->get('tbl_tingkat_by_user');

            if ($users->num_rows() > 0) {
                  $xc=$this->session->userdata('sess_iduser');
                  $XY="TAMPIL";
                                  
                  $this->db->where('id_users', $this->session->userdata('sess_iduser'));
                  $this->db->where('status_tampil', $XY);
                  $list_tingkat = $this->db->get('tbl_tingkat_by_user');
                  return $list_tingkat->result();
            }else{
                  // ALL TINGKAT LIST
                  $sql = "select tingkat from  tbl_produk_mapel_referensi group by tingkat order by  tingkat asc";
                  return $this->db->query($sql)->result();
            }
            
      }

      function get_level_name($level_user)
      {
            $this->db->where('id_user_level ', $level_user);
            $users_level = $this->db->get('tbl_user_level');
            return $users_level->row();
      }
      function check_login()
      {

            $cek = $this->session->userdata('company');

            switch ($cek) {
                  case $cek == 'administrator':
                        return "true";
                        break;
                  case $cek == 'admin':
                        return "true";
                        break;
                  case $cek == 'manager':
                        return "true";
                        break;
                  case $cek == 'user':
                        return "true";
                        break;
                  case $cek == '':
                        return "false";
                        break;

                  default:
                        return "false";
            }
      }




}
