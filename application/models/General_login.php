<?php

class General_login extends CI_Model
{




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


      // function check_login_pasar(){    

      //     $cek = $this->session->userdata('company'); 

      //     if(($cek == 'admin')  OR ($cek == 'administrator') OR ($cek == 'adminretribusi')  OR ($cek == 'adminpasar')   OR ($cek == 'lurahpasar')  OR ($cek == 'pasar') )
      //     {           
      //         return "true";
      //     }
      //     else
      //     {
      //         return "false";
      //     }   

      // }


      // function check_login_kominfo_emenara(){    

      //     $cek = $this->session->userdata('company'); 

      //     if(($cek == 'admin')  OR ($cek == 'administrator') OR ($cek == 'adminkominfo') )
      //     {           
      //         return "true";
      //     }
      //     else
      //     {
      //         return "false";
      //     }   

      // }



      // function check_login_dlh_layanan_kebersihan(){    

      //     $cek = $this->session->userdata('company'); 

      //     if(($cek == 'admin')  OR ($cek == 'admindlh') OR ($cek == 'userdlh') OR ($cek == 'admindlhpemanfaatanlab') )
      //     {           
      //         return "true";
      //     }
      //     else
      //     {
      //         return "false";
      //     }   

      // }


      //    function check_login_rusunawa(){    

      // 	$cek = $this->session->userdata('company');	

      // 	if(($cek == 'admin')  OR ($cek == 'adminrusunawa') OR ($cek == 'adminpts') OR ($cek == 'adminupt') )
      //        {			
      //        	return "true";
      //        }
      //        else
      //        {
      //            return "false";
      //        }	

      // }

      //    function check_login_dinkes_uji_air(){    

      //        $cek = $this->session->userdata('company'); 

      //        if(($cek == 'admin')  OR ($cek == 'admindinkes') OR ($cek == 'puskesmas') OR ($cek == 'labs') OR ($cek == 'AdminAppPelatihanDinkes') OR ($cek == 'pesertapelatihandinkes') OR ($cek == 'administrator') OR ($cek == 'adminpelatihandinkes') )
      //        {           
      //            return "true";
      //        }
      //        else
      //        {
      //            return "false";
      //        }   

      //    }



}
