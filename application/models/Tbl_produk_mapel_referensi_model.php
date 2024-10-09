<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tbl_produk_mapel_referensi_model extends CI_Model
{

    public $table = 'tbl_produk_mapel_referensi';
    public $table_TINGKAT = 'sys_tingkat';
    public $table_MAPEL = 'sys_mapel';
    public $id = 'id';
    public $tingkat = 'tingkat';
    public $semester = 'semester';
    public $order = 'DESC';
    public $orderASC = 'ASC';

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
    function get_all_ASC()
    {
        $this->db->order_by($this->id, $this->orderASC);
        return $this->db->get($this->table)->result();
    }

    // get data by id
    function get_by_TINGKAT($tingkat)
    {
        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];
		

        if($tingkat=="SD"){
            $this->db->where($this->tingkat, $tingkat);
            $this->db->where($this->semester, $semester_selected);
            $this->db->order_by($this->id, $this->orderASC);
        }else{
            $this->db->where($this->tingkat, $tingkat);
            // $this->db->where($this->semester, $semester_selected);
            $this->db->order_by($this->id, $this->orderASC);
        }
        return $this->db->get($this->table)->result();
    }
    // get data by id
    function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }

    function get_tingkat(){
        $hasil=$this->db->query("SELECT id,uuid_produk,tingkat FROM tbl_produk_mapel_referensi GROUP BY tingkat");
        return $hasil;
    }

 
    function get_cover($uuid){
        $hasil_row_tingkat=$this->db->query("SELECT * FROM tbl_produk_mapel_referensi WHERE uuid_produk='$uuid'")->row()->tingkat;

        $hasil=$this->db->query("SELECT * FROM tbl_produk_mapel_referensi WHERE tingkat='$hasil_row_tingkat' Group By tbl_produk_mapel_referensi.uuid_cover_produk");
        return $hasil->result();
    }


    // get total rows
    function total_rows($q = NULL)
    {
        $this->db->like('id', $q);
        $this->db->or_like('uuid_produk', $q);
        $this->db->or_like('kode_produk', $q);
        $this->db->or_like('date_input', $q);
        $this->db->or_like('id_user_input', $q);
        $this->db->or_like('tingkat', $q);
        $this->db->or_like('mapel', $q);
        $this->db->or_like('kelas', $q);
        $this->db->or_like('tahun', $q);
        $this->db->or_like('semester', $q);
        $this->db->or_like('halaman', $q);
        $this->db->or_like('uuid_cover_produk', $q);
        $this->db->or_like('keterangan', $q);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL)
    {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id', $q);
        $this->db->or_like('uuid_produk', $q);
        $this->db->or_like('kode_produk', $q);
        $this->db->or_like('date_input', $q);
        $this->db->or_like('id_user_input', $q);
        $this->db->or_like('tingkat', $q);
        $this->db->or_like('mapel', $q);
        $this->db->or_like('kelas', $q);
        $this->db->or_like('tahun', $q);
        $this->db->or_like('semester', $q);
        $this->db->or_like('halaman', $q);
        $this->db->or_like('uuid_cover_produk', $q);
        $this->db->or_like('keterangan', $q);
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }


    public function get_all_tingkat()
    {

        $sql = "SELECT tbl_produk_mapel_referensi_a.tingkat as tingkat_produk,
        tbl_produk_mapel_referensi_a.keterangan as keterangan_produk

        FROM tbl_produk_mapel_referensi tbl_produk_mapel_referensi_a
        group by tbl_produk_mapel_referensi_a.tingkat
        order by tbl_produk_mapel_referensi_a.tingkat asc
        ";

        return $this->db->query($sql)->result();
    }

    public function get_all_SD()
    {

        $sql = "SELECT tbl_produk_mapel_referensi_SD_a.tingkat as tingkat_produk,
        tbl_produk_mapel_referensi_SD_a.mapel as mapel,
        tbl_produk_mapel_referensi_SD_a.mapel_display as mapel_display,
        tbl_produk_mapel_referensi_SD_a.kelas as kelas,
        tbl_produk_mapel_referensi_SD_a.halaman as halaman,
        tbl_produk_mapel_referensi_SD_a.keterangan as keterangan_produk

        FROM tbl_produk_mapel_referensi_SD tbl_produk_mapel_referensi_SD_a
        ";

        return $this->db->query($sql)->result();
    }

    public function get_all_SD_smtsr2()
    {

        $sql = "SELECT tbl_produk_mapel_referensi_SD_a.tingkat as tingkat_produk,
        tbl_produk_mapel_referensi_SD_a.mapel as mapel,
        tbl_produk_mapel_referensi_SD_a.mapel_display as mapel_display,
        tbl_produk_mapel_referensi_SD_a.kelas as kelas,
        tbl_produk_mapel_referensi_SD_a.mapel_detail as mapel_detail,
        tbl_produk_mapel_referensi_SD_a.semester as semester,
        tbl_produk_mapel_referensi_SD_a.halaman as halaman,
        tbl_produk_mapel_referensi_SD_a.keterangan as keterangan_produk

        FROM tbl_produk_mapel_referensi_SD_MSTR_2 tbl_produk_mapel_referensi_SD_a
        ";

        return $this->db->query($sql)->result();
    }

    public function get_all_SMP()
    {

        $sql = "SELECT tbl_produk_mapel_referensi_SMP_a.tingkat as tingkat_produk,
        tbl_produk_mapel_referensi_SMP_a.mapel as mapel,
        tbl_produk_mapel_referensi_SMP_a.mapel_display as mapel_display,
        tbl_produk_mapel_referensi_SMP_a.kelas as kelas,
        tbl_produk_mapel_referensi_SMP_a.halaman as halaman,
        tbl_produk_mapel_referensi_SMP_a.keterangan as keterangan_produk

        FROM tbl_produk_mapel_referensi_SMP tbl_produk_mapel_referensi_SMP_a
        ";

        return $this->db->query($sql)->result();
    }
    public function get_all_MA2()
    {

        $sql = "SELECT tbl_produk_mapel_referensi_ma_2_a.tingkat as tingkat_produk,
        tbl_produk_mapel_referensi_ma_2_a.mapel as mapel,
        tbl_produk_mapel_referensi_ma_2_a.mapel_display as mapel_display,
        tbl_produk_mapel_referensi_ma_2_a.kelas as kelas,
        tbl_produk_mapel_referensi_ma_2_a.mapel_detail as mapel_detail,
        tbl_produk_mapel_referensi_ma_2_a.semester as semester,
        tbl_produk_mapel_referensi_ma_2_a.halaman as halaman,
        tbl_produk_mapel_referensi_ma_2_a.keterangan as keterangan_produk

        FROM tbl_produk_mapel_referensi_ma_2 tbl_produk_mapel_referensi_ma_2_a
        ";

        return $this->db->query($sql)->result();
    }

    public function get_all_data_produk_mapel_reference_by_tingkat($tingkatselected = null)
    {

        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];


        if (!is_null($tingkatselected)) {
            $sql = "SELECT tbl_produk_a.id as id_produk,
        tbl_produk_a.uuid_produk as uuid_produk,
        tbl_produk_a.date_input as date_input_produk,
        tbl_produk_a.id_user_input as id_user_input_produk,
        tbl_produk_a.kode_produk as kode_produk_produk,
        tbl_produk_a.tingkat as tingkat_produk,
        tbl_produk_a.kelas as kelas_produk,
        tbl_produk_a.tahun as tahun_produk,
        tbl_produk_a.semester as semester_produk,
        tbl_produk_a.mapel as mapel_produk_old,
        tbl_produk_a.mapel_display as mapel_produk,
        tbl_produk_a.halaman as halaman_produk,
        tbl_produk_a.uuid_cover_produk as uuid_cover_produk,
        tbl_produk_a.mapel_detail as mapel_detail_produk,
        tbl_produk_a.keterangan as keterangan_produk

        FROM tbl_produk_mapel_referensi tbl_produk_a
        -- where tbl_produk_a.tingkat = '$tingkatselected' AND tbl_produk_a.tahun = '$tahun_selected' AND tbl_produk_a.semester = '$semester_selected'
        where tbl_produk_a.tingkat = '$tingkatselected' 
        group by tbl_produk_a.mapel
        order by tbl_produk_a.id asc
        ";
        } else {
            $sql = "SELECT tbl_produk_a.id as id_produk,
        tbl_produk_a.uuid_produk as uuid_produk,
        tbl_produk_a.date_input as date_input_produk,
        tbl_produk_a.id_user_input as id_user_input_produk,
        tbl_produk_a.kode_produk as kode_produk_produk,
        tbl_produk_a.tingkat as tingkat_produk,
        tbl_produk_a.kelas as kelas_produk,
        tbl_produk_a.tahun as tahun_produk,
        tbl_produk_a.semester as semester_produk,
        tbl_produk_a.mapel as mapel_produk_old,
        tbl_produk_a.mapel_display as mapel_produk,
        tbl_produk_a.halaman as halaman_produk,
        tbl_produk_a.uuid_cover_produk as uuid_cover_produk,
        tbl_produk_a.mapel_detail as mapel_detail_produk,
        tbl_produk_a.keterangan as keterangan_produk

        FROM tbl_produk_mapel_referensi tbl_produk_a
        -- where tbl_produk_a.tahun = '$tahun_selected' AND tbl_produk_a.semester = '$semester_selected'
        group by tbl_produk_a.mapel
        order by tbl_produk_a.id asc
        ";
        }
        // print_r($this->db->query($sql)->result());
        // die;
        return $this->db->query($sql)->result();
    }


    public function get_all_data_produk_mapel_reference_by_tingkat_by_semester($tingkatselected = null, $semesterselected = null)
    {

        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];


        if (!is_null($tingkatselected)) {
            $sql = "SELECT tbl_produk_a.id as id_produk,
                tbl_produk_a.uuid_produk as uuid_produk,
                tbl_produk_a.date_input as date_input_produk,
                tbl_produk_a.id_user_input as id_user_input_produk,
                tbl_produk_a.kode_produk as kode_produk_produk,
                tbl_produk_a.tingkat as tingkat_produk,
                tbl_produk_a.kelas as kelas_produk,
                tbl_produk_a.tahun as tahun_produk,
                tbl_produk_a.semester as semester_produk,
                tbl_produk_a.mapel as mapel_produk_old,
                tbl_produk_a.mapel_display as mapel_produk,
                tbl_produk_a.halaman as halaman_produk,
                tbl_produk_a.uuid_cover_produk as uuid_cover_produk,
                tbl_produk_a.mapel_detail as mapel_detail_produk,
                tbl_produk_a.keterangan as keterangan_produk

                FROM tbl_produk_mapel_referensi tbl_produk_a
                where tbl_produk_a.tingkat = '$tingkatselected' AND  tbl_produk_a.semester = '$semester_selected' 
                group by tbl_produk_a.mapel
                order by tbl_produk_a.id asc
                ";
        } else {
            $sql = "SELECT tbl_produk_a.id as id_produk,
                tbl_produk_a.uuid_produk as uuid_produk,
                tbl_produk_a.date_input as date_input_produk,
                tbl_produk_a.id_user_input as id_user_input_produk,
                tbl_produk_a.kode_produk as kode_produk_produk,
                tbl_produk_a.tingkat as tingkat_produk,
                tbl_produk_a.kelas as kelas_produk,
                tbl_produk_a.tahun as tahun_produk,
                tbl_produk_a.semester as semester_produk,
                tbl_produk_a.mapel as mapel_produk_old,
                tbl_produk_a.mapel_display as mapel_produk,
                tbl_produk_a.halaman as halaman_produk,
                tbl_produk_a.uuid_cover_produk as uuid_cover_produk,
                tbl_produk_a.mapel_detail as mapel_detail_produk,
                tbl_produk_a.keterangan as keterangan_produk

                FROM tbl_produk_mapel_referensi tbl_produk_a
                where   tbl_produk_a.semester = '$semester_selected' 
                group by tbl_produk_a.mapel
                order by tbl_produk_a.id asc
                ";
        }


        return $this->db->query($sql)->result();
    }


    public function get_all_data_produk_mapel_reference_by_tingkat_GROUP_MAPEL_DETAIL_by_semester($tingkatselected = null, $semesterselected = null)
    {

    

        if (!is_null($tingkatselected)) {
            $sql = "SELECT tbl_produk_a.id as id_produk,
                tbl_produk_a.uuid_produk as uuid_produk,
                tbl_produk_a.date_input as date_input_produk,
                tbl_produk_a.id_user_input as id_user_input_produk,
                tbl_produk_a.kode_produk as kode_produk_produk,
                tbl_produk_a.tingkat as tingkat_produk,
                tbl_produk_a.kelas as kelas_produk,
                tbl_produk_a.tahun as tahun_produk,
                tbl_produk_a.semester as semester_produk,
                tbl_produk_a.mapel as mapel_produk_old,
                tbl_produk_a.mapel_display as mapel_produk,
                tbl_produk_a.halaman as halaman_produk,
                tbl_produk_a.uuid_cover_produk as uuid_cover_produk,
                tbl_produk_a.mapel_detail as mapel_detail_produk,
                tbl_produk_a.keterangan as keterangan_produk

                FROM tbl_produk_mapel_referensi tbl_produk_a
                where tbl_produk_a.tingkat = '$tingkatselected' AND  tbl_produk_a.semester = '$semesterselected'
                group by tbl_produk_a.mapel_detail
                order by tbl_produk_a.id asc
                ";
        } else {
            $sql = "SELECT tbl_produk_a.id as id_produk,
                tbl_produk_a.uuid_produk as uuid_produk,
                tbl_produk_a.date_input as date_input_produk,
                tbl_produk_a.id_user_input as id_user_input_produk,
                tbl_produk_a.kode_produk as kode_produk_produk,
                tbl_produk_a.tingkat as tingkat_produk,
                tbl_produk_a.kelas as kelas_produk,
                tbl_produk_a.tahun as tahun_produk,
                tbl_produk_a.semester as semester_produk,
                tbl_produk_a.mapel as mapel_produk_old,
                tbl_produk_a.mapel_display as mapel_produk,
                tbl_produk_a.halaman as halaman_produk,
                tbl_produk_a.uuid_cover_produk as uuid_cover_produk,
                tbl_produk_a.mapel_detail as mapel_detail_produk,
                tbl_produk_a.keterangan as keterangan_produk

                FROM tbl_produk_mapel_referensi tbl_produk_a
                -- where tbl_produk_a.tingkat = '$tingkatselected'
                group by tbl_produk_a.mapel_detail
                order by tbl_produk_a.id asc
                ";
        }

        return $this->db->query($sql)->result();
    }


    public function get_all_data_produk_mapel_reference_by_tingkat_GROUP_MAPEL_DETAIL($tingkatselected = null)
    {
        

        if (!is_null($tingkatselected)) {
            $sql = "SELECT tbl_produk_a.id as id_produk,
            tbl_produk_a.uuid_produk as uuid_produk,
            tbl_produk_a.date_input as date_input_produk,
            tbl_produk_a.id_user_input as id_user_input_produk,
            tbl_produk_a.kode_produk as kode_produk_produk,
            tbl_produk_a.tingkat as tingkat_produk,
            tbl_produk_a.kelas as kelas_produk,
            tbl_produk_a.tahun as tahun_produk,
            tbl_produk_a.semester as semester_produk,
            tbl_produk_a.mapel as mapel_produk_old,
            tbl_produk_a.mapel_display as mapel_produk,
            tbl_produk_a.halaman as halaman_produk,
            tbl_produk_a.uuid_cover_produk as uuid_cover_produk,
            tbl_produk_a.mapel_detail as mapel_detail_produk,
            tbl_produk_a.keterangan as keterangan_produk

            FROM tbl_produk_mapel_referensi tbl_produk_a
            where tbl_produk_a.tingkat = '$tingkatselected'
            group by tbl_produk_a.mapel_detail
            order by tbl_produk_a.id asc
            ";



        } else {
            $sql = "SELECT tbl_produk_a.id as id_produk,
            tbl_produk_a.uuid_produk as uuid_produk,
            tbl_produk_a.date_input as date_input_produk,
            tbl_produk_a.id_user_input as id_user_input_produk,
            tbl_produk_a.kode_produk as kode_produk_produk,
            tbl_produk_a.tingkat as tingkat_produk,
            tbl_produk_a.kelas as kelas_produk,
            tbl_produk_a.tahun as tahun_produk,
            tbl_produk_a.semester as semester_produk,
            tbl_produk_a.mapel as mapel_produk_old,
            tbl_produk_a.mapel_display as mapel_produk,
            tbl_produk_a.halaman as halaman_produk,
            tbl_produk_a.uuid_cover_produk as uuid_cover_produk,
            tbl_produk_a.mapel_detail as mapel_detail_produk,
            tbl_produk_a.keterangan as keterangan_produk

            FROM tbl_produk_mapel_referensi tbl_produk_a
            -- where tbl_produk_a.tingkat = '$tingkatselected'
            group by tbl_produk_a.mapel_detail
            order by tbl_produk_a.id asc
            ";
        }

        return $this->db->query($sql)->result();
    }


    public function get_NAMA_MAPEL_all_data_produk_mapel_reference_by_tingkat_GROUP_MAPEL_DETAIL($tingkatselected = null)
    {
        

        if (!is_null($tingkatselected)) {
            $sql = "SELECT 
            tbl_produk_a.mapel as mapel_produk_old,
            tbl_produk_a.mapel_display as mapel_produk,
           
            tbl_produk_a.mapel_detail as mapel_detail_produk,
            tbl_produk_a.keterangan as keterangan_produk

            FROM tbl_produk_mapel_referensi tbl_produk_a
            where tbl_produk_a.tingkat = '$tingkatselected'
            group by tbl_produk_a.mapel_detail
            order by tbl_produk_a.id asc
            ";



        } else {
            $sql = "SELECT  
            tbl_produk_a.mapel as mapel_produk_old,
            tbl_produk_a.mapel_display as mapel_produk,
           
            tbl_produk_a.mapel_detail as mapel_detail_produk,
            tbl_produk_a.keterangan as keterangan_produk

            FROM tbl_produk_mapel_referensi tbl_produk_a
            -- where tbl_produk_a.tingkat = '$tingkatselected'
            group by tbl_produk_a.mapel_detail
            order by tbl_produk_a.id asc
            ";
        }


        return $this->db->query($sql)->result();
    }




    public function get_JUMLAH_KELAS_all_data_produk_mapel_reference_by_tingkat_GROUP_MAPEL_DETAIL($tingkatselected = null)
    {
        

        if (!is_null($tingkatselected)) {
            $sql = "SELECT 
            tbl_produk_a.kelas as kelas_produk

            FROM tbl_produk_mapel_referensi tbl_produk_a
            where tbl_produk_a.tingkat = '$tingkatselected'
            group by tbl_produk_a.kelas
            order by tbl_produk_a.kelas ASC
            ";



        } else {
            $sql = "SELECT tbl_produk_a.kelas as kelas_produk

            FROM tbl_produk_mapel_referensi tbl_produk_a
            -- where tbl_produk_a.tingkat = '$tingkatselected'
            group by tbl_produk_a.kelas
            order by tbl_produk_a.kelas ASC
            ";
        }

       

        return $this->db->query($sql)->result();
    }


    // insert data
    function insert($data)
    {
        $this->db->set('uuid_produk', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table, $data);
    }

    // insert data
    function insert_TINGKAT($data)
    {
        $this->db->set('uuid_tingkat', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table_TINGKAT, $data);
    }

    // insert data
    function insert_MAPEL($data)
    {
        // print_r($data);
        // print_r("<br/>");
        $this->db->set('uuid_tingkat', "replace(uuid(),'-','')", FALSE);
        $this->db->insert($this->table_MAPEL, $data);
        // print_r("SIMPAN SELESAI");
        // die;
    }

    // update data
    function update_urutan($get_id, $get_id_update)
    {


        // Ubah ID ATAS menjadi id=999999  (FILTER : Berdasarkan Tahun dan semester terpilih)
        $set_id=9999;
        $data_ubah = array(
            'id' => $set_id,
        );
        $this->db->where($this->id, $get_id_update);
        $this->db->update($this->table, $data_ubah);




        // Ubah get_id menjadi sesuai nomor variabel get_id  (FILTER : Berdasarkan Tahun dan semester terpilih)


        $data_ubah = array(
            'id' => $get_id_update,
        );
        $this->db->where($this->id, $get_id);
        $this->db->update($this->table, $data_ubah);



        // Ubah id=999999 menjadi sesuai nomor variabel get_id  (FILTER : Berdasarkan Tahun dan semester terpilih)
        $data_ubah = array(
            'id' => $get_id,
        );
        $this->db->where($this->id, $set_id);
        $this->db->update($this->table, $data_ubah);



    }


    // update data
    function update($id, $data)
    {
        

        $nama_mapel_form = strtolower(str_replace(' ', '', $data['new_mapel']));

        $data_ubah = array(
            // 'uuid_produk' => $this->input->post('uuid_produk', TRUE),
            // 'kode_produk' => $this->input->post('kode_produk', TRUE),
            'date_input' => $this->input->post('date_input', TRUE),
            'id_user_input' => $this->input->post('id_user_input', TRUE),
            'tingkat' => $data['new_tingkat'],
            'mapel' => $data['new_mapel'],
            'kelas' => $data['new_kelas'],
            // 'tahun' => $data['new_tahun'],
            'semester' => $data['new_semester'],
            'halaman' => $data['new_halaman'],
            'mapel_display' => $data['new_mapel'] . " " . $data['new_kelas'] ,
            // 'mapel_form' =>  ,

            // 'mapel_display' => $this->input->post('mapel', TRUE) . " " . $this->input->post('kelas', TRUE),
            'mapel_form' =>  $nama_mapel_form . $data['new_kelas'], 
            'mapel_detail' => $data['new_mapel'],

            // 'uuid_cover_produk' => $this->input->post('uuid_cover_produk', TRUE),
            'keterangan' => $this->input->post('keterangan', TRUE),
        );


        // print_r($id);
        // print_r("<br/>");
        // print_r("<br/>");
        // print_r($data_ubah);
        // print_r("<br/>");
        // print_r("<br/>");
        // die;

        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data_ubah);
    }

    // delete data
    function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }


    public function get_all_data_produk_mapel_reference_by_tingkat_tahun_semester($tingkatselected = null)
    {

        $tahun_selected = $_SESSION['thn_selected'];
        $semester_selected = $_SESSION['semester_selected'];

        if (!is_null($tingkatselected)) {

            // $this->db->where('tingkat', $tingkatselected);
            // $this->db->where('tahun', $tahun_selected);
            // $this->db->where('semester', $semester_selected);
            // $data_mapel = $this->db->get('tbl_produk_mapel_referensi');
            
            // print_r($data_mapel->num_rows());
            // print_r("<br/>");

            // if ($data_mapel->num_rows() < 1){
            //     $this->db->where('tingkat', $tingkatselected);
            //     // $this->db->where('tahun', $tahun_selected);
            //     $this->db->where('semester', $semester_selected);
            //     $data_mapel = $this->db->get('tbl_produk_mapel_referensi');
            //     print_r($data_mapel->num_rows());  
            //     print_r("<br/>");  
            // }
            // return $data_mapel->result();            
            // die;





            $sql = "SELECT tbl_produk_a.id as id_produk,
                    tbl_produk_a.uuid_produk as uuid_produk,
                    tbl_produk_a.date_input as date_input_produk,
                    tbl_produk_a.id_user_input as id_user_input_produk,
                    tbl_produk_a.kode_produk as kode_produk_produk,
                    tbl_produk_a.tingkat as tingkat_produk,
                    tbl_produk_a.kelas as kelas_produk,
                    tbl_produk_a.tahun as tahun_produk,
                    tbl_produk_a.semester as semester_produk,
                    tbl_produk_a.mapel as mapel_produk_old,
                    tbl_produk_a.mapel_display as mapel_produk,
                    tbl_produk_a.halaman as halaman_produk,
                    tbl_produk_a.uuid_cover_produk as uuid_cover_produk,
                    tbl_produk_a.mapel_detail as mapel_detail_produk,
                    tbl_produk_a.keterangan as keterangan_produk

                    FROM tbl_produk_mapel_referensi tbl_produk_a
                    where tbl_produk_a.tingkat = '$tingkatselected' AND tbl_produk_a.tahun = '$tahun_selected' AND tbl_produk_a.semester = '$semester_selected'
                    -- where tbl_produk_a.tingkat = '$tingkatselected' AND tbl_produk_a.semester = '$semester_selected' 
                    group by tbl_produk_a.mapel
                    order by tbl_produk_a.id asc
                    ";
                    // print_r($this->db->query($sql)->num_rows());
                    // print_r("<br/>");


                    if($this->db->query($sql)->num_rows() < 1){
                        $sql = "SELECT tbl_produk_a.id as id_produk,
                        tbl_produk_a.uuid_produk as uuid_produk,
                        tbl_produk_a.date_input as date_input_produk,
                        tbl_produk_a.id_user_input as id_user_input_produk,
                        tbl_produk_a.kode_produk as kode_produk_produk,
                        tbl_produk_a.tingkat as tingkat_produk,
                        tbl_produk_a.kelas as kelas_produk,
                        tbl_produk_a.tahun as tahun_produk,
                        tbl_produk_a.semester as semester_produk,
                        tbl_produk_a.mapel as mapel_produk_old,
                        tbl_produk_a.mapel_display as mapel_produk,
                        tbl_produk_a.halaman as halaman_produk,
                        tbl_produk_a.uuid_cover_produk as uuid_cover_produk,
                        tbl_produk_a.mapel_detail as mapel_detail_produk,
                        tbl_produk_a.keterangan as keterangan_produk
    
                        FROM tbl_produk_mapel_referensi tbl_produk_a
                        -- where tbl_produk_a.tingkat = '$tingkatselected' AND tbl_produk_a.tahun = '$tahun_selected' AND tbl_produk_a.semester = '$semester_selected'
                        where tbl_produk_a.tingkat = '$tingkatselected' AND tbl_produk_a.semester = '$semester_selected' 
                        group by tbl_produk_a.mapel
                        order by tbl_produk_a.id asc
                        ";
                        // print_r($this->db->query($sql)->num_rows());
                        // print_r("<br/>");
                    }

                    return $this->db->query($sql)->result();
        } else {
            $sql = "SELECT tbl_produk_a.id as id_produk,
                    tbl_produk_a.uuid_produk as uuid_produk,
                    tbl_produk_a.date_input as date_input_produk,
                    tbl_produk_a.id_user_input as id_user_input_produk,
                    tbl_produk_a.kode_produk as kode_produk_produk,
                    tbl_produk_a.tingkat as tingkat_produk,
                    tbl_produk_a.kelas as kelas_produk,
                    tbl_produk_a.tahun as tahun_produk,
                    tbl_produk_a.semester as semester_produk,
                    tbl_produk_a.mapel as mapel_produk_old,
                    tbl_produk_a.mapel_display as mapel_produk,
                    tbl_produk_a.halaman as halaman_produk,
                    tbl_produk_a.uuid_cover_produk as uuid_cover_produk,
                    tbl_produk_a.mapel_detail as mapel_detail_produk,
                    tbl_produk_a.keterangan as keterangan_produk

                    FROM tbl_produk_mapel_referensi tbl_produk_a
                    -- where tbl_produk_a.tahun = '$tahun_selected' AND tbl_produk_a.semester = '$semester_selected'
                    group by tbl_produk_a.mapel
                    order by tbl_produk_a.id asc
                    ";
                    return $this->db->query($sql)->result();
        }

        
    }


}

/* End of file Tbl_produk_mapel_referensi_model.php */
/* Location: ./application/models/Tbl_produk_mapel_referensi_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2021-09-06 06:35:22 */
/* http://harviacode.com */