<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Perijinan_pendaftaran_model extends CI_Model
{

    // public $tablePendaftaranProses = 'perijinan_pendaftaran_proses';

    // public $perijinan_namaperijinan = 'perijinan_namaperijinan';
    // public $id_perijinan_namaperijinan = 'id';

    // public $tableOPD = 'perijinan_opd';
    // public $opdname = 'opd';

    public $table = 'perijinan_pendaftaran';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // get all
    function get_all()
    {
        $this->db->order_by($this->id, $this->order);
        return $this->db->get($this->table)->result();
    }

    // get data by id
    function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }
    
    // get total rows
    function total_rows($q = NULL) {
        $this->db->like('id', $q);
	$this->db->or_like('nik', $q);
	$this->db->or_like('nama', $q);
	$this->db->or_like('tempat_lahir', $q);
	$this->db->or_like('tgl_lahir', $q);
	$this->db->or_like('jenis_kelamin', $q);
	$this->db->or_like('alamat', $q);
	$this->db->or_like('agama', $q);
	$this->db->or_like('statusperkawinan', $q);
	$this->db->or_like('pekerjaan', $q);
	$this->db->or_like('kewarganegaraan', $q);
	$this->db->or_like('no_tlp', $q);
	$this->db->or_like('id_perijinan', $q);
	$this->db->or_like('nama_perijinan', $q);
	$this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL) {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id', $q);
	$this->db->or_like('nik', $q);
	$this->db->or_like('nama', $q);
	$this->db->or_like('tempat_lahir', $q);
	$this->db->or_like('tgl_lahir', $q);
	$this->db->or_like('jenis_kelamin', $q);
	$this->db->or_like('alamat', $q);
	$this->db->or_like('agama', $q);
	$this->db->or_like('statusperkawinan', $q);
	$this->db->or_like('pekerjaan', $q);
	$this->db->or_like('kewarganegaraan', $q);
	$this->db->or_like('no_tlp', $q);
	$this->db->or_like('id_perijinan', $q);
	$this->db->or_like('nama_perijinan', $q);
	$this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }



// cek search ajax refresh sebagian halaman
  public function view(){
    return $this->db->get('siswa')->result(); // Tampilkan semua data yang ada di tabel siswa
  }
  
  public function search($keyword){
    $this->db->like('nis', $keyword);
    $this->db->or_like('nama', $keyword);
    $this->db->or_like('jenis_kelamin', $keyword);
    $this->db->or_like('telp', $keyword);
    $this->db->or_like('alamat', $keyword);
    
    $result = $this->db->get('siswa')->result(); // Tampilkan data siswa berdasarkan keyword
    
    return $result; 
  }

// cek search ajax refresh sebagian halaman





    // insert data
function insert($datapendaftaran,$dataOPDdptr,$dataOPDdisbud,$dataOPDdisnakertrans,$dataOPDkesbangpol,$dataOPDdlh,$dataOPDperpusdanarsip,$dataOPDdpupkp,$dataOPDdiperpautkan,$dataOPDdisdukcapil,$dataOPDdinasperdagangan,$dataOPDdkukmp,$dataOPDdikpora,$dataOPDdinasperhubungan,$dataOPDdipertahutkan,$dataOPDdinkes,$dataOPDdinaspariwisata,$dataOPDdpmpt,$dataOPDdiskominfo)
    {
       
        $namapendaftar=$datapendaftaran['nama'];

        $id_perijinanpilih=$datapendaftaran['id_perijinan'];

        $this->db->where($this->id_perijinan_namaperijinan, $id_perijinanpilih);
        $namaperijinan=$this->db->get($this->perijinan_namaperijinan)->row();



$datapendaftaranNEW = array(
                        'nik' => $datapendaftaran['nik'],
                        'nama' => $datapendaftaran['nama'],
                        'tempat_lahir' => $datapendaftaran['tempat_lahir'],
                        'tgl_lahir' => $datapendaftaran['tgl_lahir'],
                        'jenis_kelamin' => $datapendaftaran['jenis_kelamin'],
                        'alamat' => $datapendaftaran['alamat'],
                        'agama' => $datapendaftaran['agama'],
                        'statusperkawinan' => $datapendaftaran['statusperkawinan'],
                        'pekerjaan' => $datapendaftaran['pekerjaan'],
                        'kewarganegaraan' => $datapendaftaran['kewarganegaraan'],
                        'no_tlp' => $datapendaftaran['no_tlp'],
                        'id_perijinan' => $datapendaftaran['id_perijinan'],
                        'nama_perijinan ' => $namaperijinan->name,
                        'tgl_pengajuan' => date('Y-m-d H:i:s',time()),
                        ); 
        $this->db->insert($this->table, $datapendaftaranNEW);
        $id_datapendaftaran=$this->db->insert_id();

        if($dataOPDdptr){   
            $this->db->where($this->opdname, $dataOPDdptr);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDdptr,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }

        if($dataOPDdisbud){   
            $this->db->where($this->opdname, $dataOPDdisbud);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDdisbud,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,                        
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }


        if($dataOPDdisnakertrans){   
            $this->db->where($this->opdname, $dataOPDdisnakertrans);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDdisnakertrans,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,                        
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }            

        if($dataOPDkesbangpol){   
            $this->db->where($this->opdname, $dataOPDkesbangpol);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDkesbangpol,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,                        
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }            

        if($dataOPDdlh){   
            $this->db->where($this->opdname, $dataOPDdlh);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDdlh,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,                                                
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }             


        if($dataOPDperpusdanarsip){   
            $this->db->where($this->opdname, $dataOPDperpusdanarsip);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDperpusdanarsip,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,                                                
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }            

        if($dataOPDdpupkp){   
            $this->db->where($this->opdname, $dataOPDdpupkp);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDdpupkp,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,                                                
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }               

        if($dataOPDdiperpautkan){   
            $this->db->where($this->opdname, $dataOPDdiperpautkan);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDdiperpautkan,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,                                                
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }            

        if($dataOPDdisdukcapil){   
            $this->db->where($this->opdname, $dataOPDdisdukcapil);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDdisdukcapil,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,                                                
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }             

        if($dataOPDdinasperdagangan){   
            $this->db->where($this->opdname, $dataOPDdinasperdagangan);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDdinasperdagangan,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,                                                
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }             

        if($dataOPDdkukmp){   
            $this->db->where($this->opdname, $dataOPDdkukmp);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDdkukmp,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,                                                
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }             

        if($dataOPDdikpora){   
            $this->db->where($this->opdname, $dataOPDdikpora);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDdikpora,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,                                                
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }             

        if($dataOPDdinasperhubungan){   
            $this->db->where($this->opdname, $dataOPDdinasperhubungan);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDdinasperhubungan,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,                                                
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }             

        if($dataOPDdipertahutkan){   
            $this->db->where($this->opdname, $dataOPDdipertahutkan);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDdipertahutkan,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,                                                
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }              

        if($dataOPDdinkes){   
            $this->db->where($this->opdname, $dataOPDdinkes);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDdinkes,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,                                                
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }             

        if($dataOPDdinaspariwisata){   
            $this->db->where($this->opdname, $dataOPDdinaspariwisata);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDdinaspariwisata,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,                                                
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }            


        if($dataOPDdpmpt){   
            $this->db->where($this->opdname, $dataOPDdpmpt);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDdpmpt,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,                                                
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }

        if($dataOPDdiskominfo){   
            $this->db->where($this->opdname, $dataOPDdiskominfo);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDdiskominfo,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,                                                
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }               
        
}


    // insert data
function insert_nik($datapendaftaran,$dataOPDdptr,$dataOPDdisbud,$dataOPDdisnakertrans,$dataOPDkesbangpol,$dataOPDdlh,$dataOPDperpusdanarsip,$dataOPDdpupkp,$dataOPDdiperpautkan,$dataOPDdisdukcapil,$dataOPDdinasperdagangan,$dataOPDdkukmp,$dataOPDdikpora,$dataOPDdinasperhubungan,$dataOPDdipertahutkan,$dataOPDdinkes,$dataOPDdinaspariwisata,$dataOPDdpmpt,$dataOPDdiskominfo)
    {

        $namapendaftar=$datapendaftaran['nama'];

        $id_perijinanpilih=$datapendaftaran['id_perijinan'];

// LIST DETAIL NAMA PERIJINAN
        $this->db->where($this->id_perijinan_namaperijinan, $id_perijinanpilih);
        $namaperijinan=$this->db->get($this->perijinan_namaperijinan)->row();

        // $datapendaftaran = array(
        //                         $datapendaftaran,
        //                         'nama_perijinan' => $namaperijinan->name,
        //                         );




$datapendaftaranNEW = array(
                        'nik' => $datapendaftaran['nik'],
                        'nama' => $datapendaftaran['nama'],
                        'tempat_lahir' => $datapendaftaran['tempat_lahir'],
                        'tgl_lahir' => $datapendaftaran['tgl_lahir'],
                        'jenis_kelamin' => $datapendaftaran['jenis_kelamin'],
                        'alamat' => $datapendaftaran['alamat'],
                        'agama' => $datapendaftaran['agama'],
                        'statusperkawinan' => $datapendaftaran['statusperkawinan'],
                        'pekerjaan' => $datapendaftaran['pekerjaan'],
                        'kewarganegaraan' => $datapendaftaran['kewarganegaraan'],
                        'no_tlp' => $datapendaftaran['no_tlp'],
                        'id_perijinan' => $datapendaftaran['id_perijinan'],
                        'nama_perijinan ' => $namaperijinan->name,
                        'tgl_pengajuan' => date('Y-m-d H:i:s',time()),
                        ); 




        $this->db->insert($this->table, $datapendaftaranNEW);
        $id_datapendaftaran=$this->db->insert_id();

        if($dataOPDdptr){   
            $this->db->where($this->opdname, $dataOPDdptr);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDdptr,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }

        if($dataOPDdisbud){   
            $this->db->where($this->opdname, $dataOPDdisbud);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDdisbud,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,                        
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }


        if($dataOPDdisnakertrans){   
            $this->db->where($this->opdname, $dataOPDdisnakertrans);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDdisnakertrans,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,                        
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }            

        if($dataOPDkesbangpol){   
            $this->db->where($this->opdname, $dataOPDkesbangpol);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDkesbangpol,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,                        
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }            

        if($dataOPDdlh){   
            $this->db->where($this->opdname, $dataOPDdlh);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDdlh,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,                                                
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }             


        if($dataOPDperpusdanarsip){   
            $this->db->where($this->opdname, $dataOPDperpusdanarsip);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDperpusdanarsip,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,                                                
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }            

        if($dataOPDdpupkp){   
            $this->db->where($this->opdname, $dataOPDdpupkp);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDdpupkp,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,                                                
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }               

        if($dataOPDdiperpautkan){   
            $this->db->where($this->opdname, $dataOPDdiperpautkan);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDdiperpautkan,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,                                                
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }            

        if($dataOPDdisdukcapil){   
            $this->db->where($this->opdname, $dataOPDdisdukcapil);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDdisdukcapil,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,                                                
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }             

        if($dataOPDdinasperdagangan){   
            $this->db->where($this->opdname, $dataOPDdinasperdagangan);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDdinasperdagangan,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,                                                
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }             

        if($dataOPDdkukmp){   
            $this->db->where($this->opdname, $dataOPDdkukmp);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDdkukmp,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,                                                
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }             

        if($dataOPDdikpora){   
            $this->db->where($this->opdname, $dataOPDdikpora);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDdikpora,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,                                                
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }             

        if($dataOPDdinasperhubungan){   
            $this->db->where($this->opdname, $dataOPDdinasperhubungan);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDdinasperhubungan,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,                                                
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }             

        if($dataOPDdipertahutkan){   
            $this->db->where($this->opdname, $dataOPDdipertahutkan);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDdipertahutkan,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,                                                
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }              

        if($dataOPDdinkes){   
            $this->db->where($this->opdname, $dataOPDdinkes);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDdinkes,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,                                                
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }             

        if($dataOPDdinaspariwisata){   
            $this->db->where($this->opdname, $dataOPDdinaspariwisata);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDdinaspariwisata,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,                                                
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }            


        if($dataOPDdpmpt){   
            $this->db->where($this->opdname, $dataOPDdpmpt);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDdpmpt,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,                                                
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }      
        
        if($dataOPDdiskominfo){   
            $this->db->where($this->opdname, $dataOPDdiskominfo);
            $dataOPD=$this->db->get($this->tableOPD)->row_array();
            $entryDATA=array(
                                'id_pendaftaran' =>  $id_datapendaftaran,
                                'id_namaperijinan' => $id_perijinanpilih,
                                'id_opd' =>$dataOPD['id'],
                                'nama_opd' =>$dataOPDdiskominfo,
                                'nama_pendaftar' =>  $namapendaftar,
                                'nama_perijinan' => $namaperijinan->name,                                                
                                // 'tgl_assign' => date('Y-m-d H:i:s',time()),
                                );
            $this->db->insert($this->tablePendaftaranProses, $entryDATA);
        }      
        
}




    // update data
    function update($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);
    }

    // delete data
    function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }


    function get_penduduk($id=0){
error_reporting(0); //menghapus pesan error php jika
                            $json = file_get_contents( "http://e-retribusi.bantulkab.go.id/nik_retribusi.php?nik=".$id );
                            $res = json_decode($json, false, 512, JSON_BIGINT_AS_STRING);  //updated 08 feb 2018 for NIK display format as string

                            foreach ($res->content as $key => $value) { //looping data penduduk
                                foreach ($value as $key => $value) {

                                    if ($key=='DUSUN'){$dataJson['dusunJson'] = $value;}
                                    if ($key=='NAMA_LGKP'){$dataJson['nama_lgkpJson'] = $value;}
                                    if ($key=='STAT_HBKEL'){$dataJson['stat_hbkelJson'] = $value;}
                                    if ($key=='AGAMA'){$dataJson['agamaJson'] = $value;}
                                    if ($key=='JENIS_PKRJN'){$dataJson['jenis_pkrjnJson'] = $value;}
                                    if ($key=='PDDK_AKH'){$dataJson['pddk_akhJson'] = $value;}
                                    if ($key=='TMPT_LHR'){$dataJson['tmpt_lhrJson'] = $value;}
                                    if ($key=='STATUS_KAWIN'){$dataJson['status_kawinJson'] = $value;}
                                    if ($key=='GOL_DARAH'){$dataJson['gol_darahJson'] = $value;}
                                    if ($key=='TGL_KWN'){$dataJson['tgl_kawinJson'] = $value;}
                                    if ($key=='NO_AKTA_KWN'){$dataJson['no_akta_kwnJson'] = $value;}
                                    if ($key=='JENIS_KLMIN'){$dataJson['jenis_klminJson'] = $value;}
                                    if ($key=='TGL_CRAI'){$dataJson['tgl_craiJson'] = $value;}
                                    if ($key=='NO_KK'){$dataJson['no_kkJson'] = $value;}
                                    if ($key=='NIK'){$dataJson['nikJson'] = $value;}
                                    if ($key=='KAB_NAME'){$dataJson['kab_nameJson'] = $value;}
                                    if ($key=='NAMA_LGKP_AYAH'){$dataJson['nama_lgkp_ayahJson'] = $value;}
                                    if ($key=='NO_RW'){$dataJson['no_rwJson'] = $value;}
                                    if ($key=='KEC_NAME'){$dataJson['kec_nameJson'] = $value;}
                                    if ($key=='NO_AKTA_LHR'){$dataJson['no_akta_lhrJson'] = $value;}
                                    if ($key=='NO_KEL'){$dataJson['no_kelJson'] = $value;}
                                    if ($key=='NO_RT'){$dataJson['no_rtJson'] = $value;}
                                    if ($key=='KODE_POS'){$dataJson['kode_posJson'] = $value;}
                                    if ($key=='NO_KEC'){$dataJson['no_kecJson'] = $value;}
                                    if ($key=='ALAMAT'){$dataJson['alamatJson'] = $value;}
                                    if ($key=='NO_PROP'){$dataJson['no_propJson'] = $value;}
                                    if ($key=='NAMA_LGKP_IBU'){$dataJson['nama_lgkp_ibuJson'] = $value;}
                                    if ($key=='NO_AKTA_CRAI'){$dataJson['no_akta_craiJson'] = $value;}
                                    if ($key=='PROP_NAME'){$dataJson['prop_nameJson'] = $value;}
                                    if ($key=='NO_KAB'){$dataJson['no_kabJson'] = $value;}
                                    if ($key=='TGL_LHR'){$dataJson['tgl_lhrJson'] = $value;}
                                    if ($key=='KEL_NAME'){$dataJson['kel_nameJson'] = $value;}  
                                } // end of looping dataJson content    



                                if(!empty($dataJson['nikJson']))
                                {
                                    if($dataJson['nikJson']==$id)
                                    {
                                            $data['dusun']=$dataJson['dusunJson'];
                                            $data['nama']=$dataJson['nama_lgkpJson'];
                                            $data['stat_hbkel']=$dataJson['stat_hbkelJson'];
                                            $data['agama'] =$dataJson['agamaJson'];
                                            $data['jenis_pkrjn']=$dataJson['jenis_pkrjnJson'];
                                            $data['pendidikan'] =$dataJson['pddk_akhJson'];
                                            $data['tempatlahir'] =$dataJson['tmpt_lhrJson'];
                                            $data['status_kawin']=$dataJson['status_kawinJson'];
                                            $data['gol_darah']=$dataJson['gol_darahJson'];
                                            $data['tgl_kawin']=$dataJson['tgl_kawinJson'];
                                            $data['no_akta_kwn']=$dataJson['no_akta_kwnJson'];
                                            $data['jenis_klmin']=$dataJson['jenis_klminJson'];
                                            $data['tgl_crai']=$dataJson['tgl_craiJson'];
                                            $data['no_kk']=$dataJson['no_kkJson'];
                                            $data['nik']=$dataJson['nikJson'];
                                            $data['kab_name']=$dataJson['kab_nameJson'];
                                            $data['nama_lgkp_ayah']=$dataJson['nama_lgkp_ayahJson'];
                                            $data['no_rw']=$dataJson['no_rwJson'];
                                            $data['kec_name']=$dataJson['kec_nameJson'];
                                            $data['no_akta_lhr']=$dataJson['no_akta_lhrJson'];
                                            $data['no_kel']=$dataJson['no_kelJson'];
                                            $data['no_rt']=$dataJson['no_rtJson'];
                                            $data['kode_pos']=$dataJson['kode_posJson'];
                                            $data['no_kec']=$dataJson['no_kecJson'];
                                            $data['alamat'] =$dataJson['alamatJson'];
                                            $data['no_prop']=$dataJson['no_propJson'];
                                            $data['nama_lgkp_ibu']=$dataJson['nama_lgkp_ibuJson'];
                                            $data['no_akta_crai']=$dataJson['no_akta_craiJson'];
                                            $data['prop_name']=$dataJson['prop_nameJson'];
                                            $data['no_kab']=$dataJson['no_kabJson'];
                                            $data['tanggallahir'] =$dataJson['tgl_lhrJson'];
                                            $data['kel_name']=$dataJson['kel_nameJson'];




                                    }
                            }
                        }

if(empty($data)){ $data = "kosong";}

        return $data;
    }





    

}

/* End of file Perijinan_pendaftaran_model.php */
/* Location: ./application/models/Perijinan_pendaftaran_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2018-04-04 03:57:01 */
/* http://harviacode.com */